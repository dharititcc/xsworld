(function () {
    XS.Drink = {
        table: null,
        tableColumns: [{
            "data": "id", // can be null or undefined
            "defaultContent": "",
            "sortable": false,
            render: function (data, type, row) {
                return `<label class="cst-check"><input name="id" class="checkboxitem" data-drink_id="${row.id}" type="checkbox" value="${row.id}"><span class="checkmark"></span></label>`
            }
        },
        {
            "data": "name", // can be null or undefined ->type
            "defaultContent": "",
            render: function (data, type, row) {
                var color = (row.is_available == 1) ? "green" : "red";
                return `<div class="prdname ${color}"> ${row.name} </div>
                            <a href="#" class="edit">Edit</a>
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
            activeCategory:     jQuery('.category.active'),
            search:             jQuery("#search"),
            category:           jQuery('.category'),
            drinkModalAnchor:   jQuery('#drink_modal'),
            drinkForm:          jQuery('#drinkpopup'),
            drinkModalTitle:    jQuery('model_title'),
            drinkSubmitBtn:     jQuery('submitBtn'),
        },

        init: function (){
            this.addHandler();
        },

        addHandler: function (){
            var context = this;

            context.makeDatatable();

            context.productTypeFilter();

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
                    $this.closest('.filter-box').find('.category').removeClass('active');
                    $this.addClass('active');
                }
                else
                {
                    // specific category focus
                    $this.closest('.filter-box').find('.category').removeClass('active');
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
                        data.category   = context.selectors.activeCategory.data('category_id'),
                        data.search_main= context.selectors.search.val()
                    },
                },
                columns: context.tableColumns
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
                    document.getElementById("price").style.visibility='hidden';
                    $('.prd-variation').removeAttr("style");
                }
                else
                {
                    document.getElementById("price").style.visibility='visible';
                    $(".prd-variation").css("display", "none");
                }

                $this.addClass('active');
            });
        },

        openDrinkModal: function()
        {
            var context = this;

            context.selectors.drinkModalAnchor.on('click', function(e)
            {
                e.preventDefault();

                var $this     = $(this),
                    drinkId = $this.data('drink_id');

                if(drinkId == undefined)
                {
                    console.log(drinkId);
                    context.selectors.drinkModalTitle.html('Add ');
                    context.addDrinkFormValidation();
                    context.selectors.drinkForm.attr('action', moduleConfig.drinkStore);
                } else {
                    context.selectors.drinkModalTitle.html('Edit ');
                    context.selectors.drinkSubmitBtn.html('Edit Drink');
                    context.editDrinkFormValidation();
                    context.selectors.drinkForm.attr('action', moduleConfig.drinkUpdate.replace(':ID', drinkId));
                }

                jQuery('#wd930').modal('show');
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
                        pattern: /^\d+(\.\d{1,2})?$/
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
                        pattern: "Please enter a valid price format (e.g., 100.50)"
                    },
                    image: {
                        required: "Please enter files", //accept: 'Not an image!'
                    }
                },
                submitHandler: function() {
                    // console.log('new');
                    context.submitDrinkForm(context.selectors.drinkForm.get(0));
                }
            });
        },

        editDrinkFormValidation: function()
        {
            var context = this;
        },

        submitDrinkForm: function(form)
        {
            console.log('submit call');
            var context = this;
            data = new FormData(form);
            XS.Common.btnProcessingStart(context.selectors.drinkSubmitBtn);
            var category = [];
            $.each($("input[name='category_id']:checked"), function(i) {
                category[i] = $(this).val();
            });
            
            $.ajax({
                url: $(form).attr('action'),
                type: "POST",
                data: data,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    alert('Ajax form has been submitted successfully');
                    document.getElementById("categorypopup").reset();
                    location.reload(true);
                },
                complete: function()
                {
                    XS.Common.btnProcessingStop(context.selectors.drinkSubmitBtn);
                }
            });
        }
    }
})();