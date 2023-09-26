(function () {
    XS.Drink = {
        table: null,
        tableColumns: [{
            "data": "id", // can be null or undefined
            "defaultContent": "",
            "sortable": false,
            render: function (data, type, row) {
                return `<label class="cst-check"><input name="id" class="checkboxitem" type="checkbox" value="${row.id}"><span class="checkmark"></span></label>`
            }
        },
        {
            "data": "name", // can be null or undefined ->type
            "defaultContent": "",
            render: function (data, type, row) {
                var color = (row.is_available == 1) ? "green" : "red";
                return `<div class="prdname ${color}"> ${row.name} </div>
                            <a href="javascript:void(0);" data-id="${row.id}" class="drink_modal edit">Edit</a>
                            <div class="add-date">Added ${XS.Common.formatDate(row.created_at)}</div>`
            }
        },
        {
            "data": "type", // can be null or undefined
            "defaultContent": "",
            "bSortable": false,
            render: function (data, type, row) {
                var text = "";
                if (row.variations.length > 0) {
                    for (let i = 0; i < row.variations.length; i++) {
                        text += '<label class="">' + row.variations[i]['name'] + "</label>";
                    }
                    return text
                }
                return ""
            }
        },
        {
            "data": "price", // can be null or undefined
            "defaultContent": "",
            "bSortable": false,
            render: function (data, type, row) {
                var text = "";
                if (row.variations.length > 0) {
                    for (let i = 0; i < row.variations.length; i++) {
                        text += '<label class="price">$' + row.variations[i]['price'] +
                            "</label>";
                    }
                    return text
                }
                return row.price
            }
        },
        {
            "data": "description", // can be null or undefined
            "defaultContent": "",
            "bSortable": false,
            render: function (data, type, row) {
                var string = row.description;

                if (string) {
                    return string ? string.slice(0, 50) + (string.length > 10 ? "..." : "") : '';
                }

                return '';
            }
        },
        {
            "data": "favorite", // can be null or undefined
            "defaultContent": "",
            "bSortable": false,
            render: function (data, type, row) {
                if (row.is_featured == 1) {
                    return '<a href="javascript:void(0)" class="favorite"></a>'
                }
                return '<a href="javascript:void(0)" class="favorite null"></a>'
            }
        },
        {
            "data": "status", // can be null or undefined
            "defaultContent": "",
            "bSortable": false,
            render: function (data, type, row) {
                var html = '';
                if (row.is_featured == 1) {
                    html += '<div class="green"><strong>Featured Drink</strong> </div>'
                }
                if (row.is_available == 1) {
                    html += '<div class="green"><strong> In-Stock</strong></div>'
                } else {
                    html += '<div class="red"><strong>  Out Of Stock</strong></div>'
                }
                return html
            }
        }],

        selectors: {
            drinkModal:         jQuery('#wd930'),
            drinkTable:         jQuery('.drink_datatable'),
            // activeCategory:     jQuery('.category.active'),
            search:             jQuery("#search"),
            category:           jQuery('.drink_cat'),
            drinkModalAnchor:   jQuery('.drink_modal'),
            drinkForm:          jQuery('#drinkpopup'),
            drinkModalTitle:    jQuery('model_title'),
            drinkSubmitBtn:     jQuery('submitBtn'),
            drinkModalBtn:      jQuery('.drink_popup_modal'),
        },

        init: function (){
            this.addHandler();
        },

        addHandler: function (){
            var context = this;

            context.makeDatatable();

            context.productTypeFilter();

            context.isFavorite();

            context.openDrinkModal();
            context.closeDrinkModal();
            XS.Common.fileReaderBind();
        },

        categoryFilter: function(){
            var context = this;

            context.selectors.category.on('click', function(e)
            {
                e.preventDefault();

                var $this       = $(this),
                    categoryId  = $this.data('category_id');

                if( !categoryId )
                {
                    // all focus
                    $this.closest('.filter-box').find('.drink_cat').removeClass('active');
                    $this.addClass('active');
                }
                else
                {
                    // specific category focus
                    $this.closest('.filter-box').find('.drink_cat').removeClass('active');
                    $this.addClass('active');
                }

                context.table.ajax.reload();
            });
        },

        searchFilter: function(){
            var context = this;

            context.selectors.search.on('keyup', function()
            {
                context.table.ajax.reload();
            });
        },

        makeDatatable: function (){
            var context = this;

            context.categoryFilter();
            context.searchFilter();

            context.table = context.selectors.drinkTable.DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                order: [[1, 'asc']],
                ajax: {
                    url: moduleConfig.getAccessibles,
                    type: 'get',
                    data: function(data)
                    {
                        data.category   = jQuery('.drink_cat.active').data('category_id'),
                        data.search_main= context.selectors.search.val()
                    },
                },
                columns: context.tableColumns
            });
        },

        isFavorite: function()
        {
            $('.is_favorite').click(function(e)
            {
                var is_favorite = $(this).data('is_favorite');
                if(is_favorite === 0){
                    $('.is_favorite').removeClass('null');
                    $(this).data('is_favorite',1);
                    $('#is_featured').val(1);
                }else{
                    $(this).data('is_favorite',0);
                    $('#is_featured').val(0);
                    $('.is_favorite').addClass('null');
                }
            });
        },

        productTypeFilter: function()
        {
            jQuery('.product_type').on('click', function(e)
            {
                var $this       = jQuery(this),
                    productType = $this.data('product_type');

                    jQuery('.product_type').removeClass('active');

                if( productType == 1 )
                {
                    $('#product_type').val(1);
                    document.getElementById("price").style.visibility='hidden';
                    $('.prd-variation').removeAttr("style");
                }
                else
                {
                    $('#product_type').val(0);
                    document.getElementById("price").style.visibility='visible';
                    $(".prd-variation").css("display", "none");
                }

                $this.addClass('active');
            });
        },

        openDrinkModal: function()
        {
            var context = this;

            $('.showin-mob, .drink_datatable').on('click', '.drink_modal', function(e)
            {
                e.preventDefault();

                var $this       = $(this),
                    drinkId     = $this.data('id'),
                    productType = $this.data('product_type');
                    $('#product_type').val(0);
                    $('#is_featured').val(0);
                    
                if(drinkId == undefined)
                {
                    context.selectors.drinkModalTitle.html('Manually ');
                    context.addDrinkFormValidation();
                    context.selectors.drinkForm.attr('action', moduleConfig.drinkStore);

                } else {
                    context.selectors.drinkModalTitle.html('Manually Edit ');
                    context.editDrinkFormValidation();
                    context.selectors.drinkForm.attr('action', moduleConfig.drinkUpdate.replace(':ID', drinkId));
                    context.getDrinkData(drinkId);
                    context.selectors.drinkForm.append(`<input type="hidden" name="_method" value="PUT" />`);
                }

                context.selectors.drinkModal.modal('show');
            });
        },

        closeDrinkModal: function()
        {
            jQuery('#wd930').on('hide.bs.modal', function()
            {
                var $this = $(this);

                jQuery('.product_type').each(function()
                {
                    var $this       = $(this),
                        productType = $this.data('product_type');

                    // remove class active
                    $this.removeClass('active');

                    if( productType == 0 )
                    {
                        $this.addClass('active');
                        $(".prd-variation").css("display", "none");
                    }
                });

                context.selectors.drinkForm.validate().resetForm();
                context.selectors.drinkForm.find('.error').removeClass('error');
                context.selectors.drinkForm.find('input[name="_method"]').remove();
                context.selectors.drinkForm.removeAttr('action');
                $this.find('.pip').remove();
                $this.find('.cstm-catgory').find('input[name="category_id[]"]').prop('checked', false);
            });
        },

        addDrinkFormValidation: function()
        {
            var context = this;
            context.selectors.drinkForm.validate({
                errorPlacement: function($error, $element) {
                    $error.appendTo($element.closest("div"));
                },
                rules: {
                    name: {
                        required: true,
                    },
                    description: {
                        required: true,
                    },
                    category_id : {
                        required: true,
                    },
                    price: {
                        required: true,
                        pattern: /^\d+(\.\d{1,2})?$/,
                    },
                    image: {
                        required: true,
                    },
                    ingredients: {
                        required: true,
                    },
                    country_of_origin: {
                        required: true,
                    },
                    type_of_drink: {
                        required: true,
                    },
                    year_of_production: {
                        required: true,
                    },
                    message: {
                        required: true
                    },
                },
                messages: {
                    name: {
                        required: "Please enter name",
                        maxlength: "Your name maxlength should be 50 characters long."
                    },
                    price: {
                        required: "please Enter Amount",
                        pattern: "Please enter a valid price format (e.g., 100.50).",
                    },
                    image: {
                        required: "Please enter files", //accept: 'Not an image!'
                    }
                },
                submitHandler: function() {
                    console.log('new');
                    context.submitDrinkForm(context.selectors.drinkForm.get(0));
                }
            });
        },

        editDrinkFormValidation: function()
        {
            var context = this;
            context.selectors.drinkForm.validate({
                rules: {
                    name: {
                        required: true,
                    },
                    description: {
                        required: true,
                    },
                    price: {
                        required: true,
                        pattern: /^\d+(\.\d{1,2})?$/,
                    },
                    ingredients: {
                        required: true,
                    },
                    country_of_origin: {
                        required: true,
                    },
                    type_of_drink: {
                        required: true,
                    },
                    year_of_production: {
                        required: true,
                    },
                    message: {
                        required: true
                    },
                },
                messages: {
                    name: {
                        required: "Please enter name",
                        maxlength: "Your name maxlength should be 50 characters long."
                    },
                    price: {
                        required: "please Enter Amount",
                        pattern: "Please enter a valid price format (e.g., 100.50).",
                    },
                    
                },
                submitHandler: function() {
                    console.log('edit');
                    context.submitDrinkForm(context.selectors.drinkForm.get(0));
                }
            });
        },

        submitDrinkForm: function(form)
        {
            var context = this,
                data = new FormData(form);
            XS.Common.btnProcessingStart(context.selectors.drinkSubmitBtn);
            var category = [];
            $.each($("input[name='category_id']:checked"), function(i) {
                category[i] = $(context).val();
            });
            
            $.ajax({
                url: $(form).attr('action'),
                type: "POST",
                data: data,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    alert('Drink form has been submitted successfully');
                    document.getElementById("drinkpopup").reset();
                    location.reload(true);
                },
                complete: function()
                {
                    XS.Common.btnProcessingStop(context.selectors.drinkSubmitBtn);
                }
            });
        },

        getDrinkData: function(id)
        {
            context = this;
            $.ajax({
                url: moduleConfig.drinkGet.replace(':ID',id),
                type: 'GET',
                success: function(res) {
                    console.log(res.data);
                    $('#name').val(res.data.name);
                    $('#ingredients').val(res.data.ingredients);
                    $('#country_of_origin').val(res.data.country_of_origin);
                    $('#year_of_production').val(res.data.year_of_production);
                    $('#type_of_drink').val(res.data.type_of_drink);
                    $('#description').val(res.data.description);
                    $('input[name="category_id[]"]').val(res.data.categories);
                    $('#price').val(res.data.price);

                    var image = `
                        <div class="pip">
                            <img class="imageThumb" src="${ res.data.image != "" ? res.data.image : ''}" title="" />
                            <i class="icon-trash remove"></i>
                        </div>
                    `;

                    if( res.data.image != "" )
                    {
                        $(".image_box").children('.pip').remove();
                        $("#upload").after(image);
                    }

                    $(".remove").click(function() {
                        $(this).parent(".pip").remove();
                    });
                },
                
            });
        }
    }
})();