(function () {

    XS.Food = {
        table: null,
        tableColumns: [
        {
            "data": "", // can be null or undefined ->type
            "defaultContent": "",
            "width": "5%",
            "sortable": false,
            render: function (data, type, row) {
                var color = (row.is_available == 1) ? "green" : "red";
                $(row).addClass('dt-center');
                return `<label class="cst-check"><input name="id" class="checkboxitem" type="checkbox" value="${row.id}"><span class="checkmark"></span></label>`;
            }
        },
        {
            "data": "name", // can be null or undefined ->type
            "defaultContent": "",
            "width": "25%",
            render: function (data, type, row) {
                var color = (row.is_available == 1) ? "green" : "red";
                return `<div class="prdname ${color}"> ${row.name} </div>
                        <a href="javascript:void(0);" data-id="${row.id}" class="food_modal edit">Edit</a>
                        <div class="add-date">Added ${XS.Common.formatDate(row.created_at)}</div>`
            }
        },
        {
            "data": "category_name",
            "defaultContent": "",
            "width": "10%",
            render: function (data, type, row) {
                return data;
            }
        },
        {
            "data": "type", // can be null or undefined
            "defaultContent": "",
            "width": "10%",
            "bSortable": false,
            render: function (data, type, row) {
                var text = "";
                if (row.variations.length > 0) {
                    for (let i = 0; i < row.variations.length; i++) {
                        text += '<label class="">' + row.variations[i]['name'] + "</label>";
                    }
                    return text
                }
                return "Simple"
            }
        },
        {
            "data": "price", // can be null or undefined
            "defaultContent": "",
            "width": "10%",
            "bSortable": false,
            render: function (data, type, row) {
                var text = "";
                if (row.variations.length > 0) {
                    for (let i = 0; i < row.variations.length; i++) {
                        text += `<label class="price">${moduleConfig.currency}${row.variations[i]['price']}</label>`;
                    }
                    return text
                }
                return `<label class="price">${moduleConfig.currency}${row.price}</label>`;
            }
        },
        {
            "data": "description", // can be null or undefined
            "defaultContent": "",
            "width": "25%",
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
            "width": "5%",
            "bSortable": false,
            render: function (data, type, row) {
                return `<a href="javascript:void(0)" class="favorite ${row.is_featured == 0 ? 'null' : ''}" data-is_featured="${row.is_featured == 0 ? 1 : 0}" data-id="${row.id}"></a>`
            }
        },
        {
            "data": "status", // can be null or undefined
            "defaultContent": "",
            "width": "10%",
            "bSortable": false,
            render: function (data, type, row) {
                var html = '';
                if (row.is_featured == 1) {
                    html += '<div class="green"><strong>Featured Food</strong> </div>'
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
            foodModal:          jQuery('#wd930'),
            uploadfoodModal:    jQuery('#wd931'),
            foodTable:          jQuery('.drink_datatable'),
            // activeCategory:     jQuery('.category.active'),
            search:             jQuery("#search"),
            category:           jQuery('.food_cat'),
            foodModalAnchor:    jQuery('.food_modal'),
            foodForm:           jQuery('#drinkpopup'),
            foodModalTitle:     jQuery('.model_title'),
            foodSubmitBtn:      jQuery('#submitBtn'),
            foodModalBtn:       jQuery('.drink_popup_modal'),
            foodVariationBtn:   jQuery('.add_variations'),
            foodVariationModal: jQuery('#addDrink'),
            addVariationBtn:    jQuery('#add_variation_btn'),
        },

        init: function (){
            this.addHandler();
        },

        addHandler: function (){
            var context = this;

            context.makeDatatable();

            context.productTypeFilter();
            context.openVariationModal();
            context.closeVariationModal();

            XS.Common.isFavorite();

            context.openFoodModal();
            context.openUploadFoodModal();
            context.closeFoodModal();
            XS.Common.fileReaderBind();
            XS.Common.allCheckBox();
            context.addVariation();
            context.removeVariation();
            context.favoriteStatusUpdate();
            context.filterCategoryChange();
            XS.Common.enableSweetAlert(context.table);
            XS.Common.disableSweetAlert(context.table);

            // price input field validation for number
            context.selectors.foodVariationModal.find('input[name="variation_price"]').get(0).addEventListener('keyup', XS.Common.checkNumberInput);
            $('#price').get(0).addEventListener('keyup', XS.Common.checkNumberInput);
        },

        filterCategoryChange: function()
        {
            var context = this;
            // single category selection
            jQuery('.cstm-catgory').find('input:checkbox').on('change', function()
            {
                var $this = $(this);

                jQuery('.cstm-catgory').find('input:checkbox').each(function()
                {
                    $(this).prop('checked', false);
                });

                $this.prop('checked', true);
            });
        },

        openVariationModal: function()
        {
            var context = this;
            context.selectors.foodVariationBtn.on('click', function(e) {
                e.preventDefault();
                var $this = $(this);
                context.selectors.foodVariationModal.modal('show');
            });
        },

        closeVariationModal: function()
        {
            // code...
        },

        removeVariation: function()
        {
            var context = this;
            context.selectors.foodModal.find('.modal-body').find('.variety').on("click",'.remove', function(e) {
                e.preventDefault();
                var element     = $(this).closest('.item-box'),
                    className   = element.attr('class'),
                    classArray  = className.split(' ');

                element.remove();

                context.selectors.foodModal.find(`div.${classArray[2]}`).remove();
            });
        },

        addVariation: function()
        {
            var context = this;
            context.selectors.addVariationBtn.on('click', function(e) {
                e.preventDefault();

                var $this       = $(this),
                    parent      = $this.closest('.modal-body'),
                    isValid     = true,
                    name        = parent.find('input[name="variation_name"]'),
                    price       = parent.find('input[name="variation_price"]');

                $this.closest('.modal-body').find('.error').remove();

                // validation variation form
                if( name.val() == '' )
                {
                    isValid = false;

                    name.after(`<span class="error">The variation name field is required.</span>`);
                }

                if( price.val() == '' )
                {
                    isValid = false;
                    price.after(`<span class="error">The variation price field is required.</span>`);
                }

                if( !isValid )
                {
                    return false;
                }

                context.addVariationBlock(name.val(), price.val());

                context.selectors.foodVariationModal.modal('hide');
                context.selectors.foodVariationModal.find('.modal-body').find('.variation_field').each(function() {
                    $(this).val('');
                });
            });
        },

        addVariationBlock: function(name, price, index)
        {
            var context     = this,
                className   = XS.Common.randomId(8);

            context.selectors.foodModal.find('.modal-body').find('.variety')
            .append(
                `<div class="grey-brd-box item-box ${className}">
                    <button href="javascript:void(0);" class="remove" type="button"><i class="icon-minus"></i></button>
                    <aside> ${name}
                        <span>(${price})</span>
                    </aside>
                </div>`
            );

            context.selectors.foodModal.find('form').append(`
                <div class="${className}">
                    <input type="hidden" name="drink_variation_name[]" class="variation_hidden" value="${name}" />
                    <input type="hidden" name="drink_variation_price[]" class="variation_hidden" value="${price}" />
                </div>
            `);
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
                    $this.closest('.filter-box').find('.food_cat').removeClass('active');
                    $this.addClass('active');
                }
                else
                {
                    // specific category focus
                    $this.closest('.filter-box').find('.food_cat').removeClass('active');
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

            context.table = context.selectors.foodTable.DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                order: [[1, 'desc']],
                ajax: {
                    url: moduleConfig.getAccessibles,
                    type: 'get',
                    data: function(data)
                    {
                        var checkboxes = $.map($('input[name="id"]:checked'), function(c){return c.value; });
                        data.category       = jQuery('.food_cat.active').data('category_id'),
                        data.search_main    = context.selectors.search.val(),
                        data.enable         = $('#enable').get(0).classList.contains('enable_clicked') ? checkboxes : [],
                        data.disable        = $('#disable').get(0).classList.contains('disable_clicked') ? checkboxes : []
                    },
                },
                columns: context.tableColumns,
                drawCallback: function ( settings )
                {
                    context.selectors.foodTable.find('tbody tr').find('td:first').addClass('dt-center');
                }
            });
        },

        favoriteStatusUpdate: function()
        {
            var context = this;
            $('.drink_datatable').on('click', '.favorite', function() {
                var $this       = $(this),
                    id          = $this.data('id'),
                    is_featured = $this.data('is_featured');

                $.ajax({
                    url:moduleConfig.favoriteStatusUpdate,
                    type:'POST',
                    dataType: "json",
                    headers: {
                        'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content'),
                    },
                    data: {'is_featured':is_featured,'id':id},
                    success: function(res) {

                        XS.Common.handleSwalSuccessWithoutReload('Favorite status has been updated successfully.');
                        setTimeout(function()
                        {
                            context.table.ajax.reload();
                        }, 500);
                    },
                });
            });
        },

        productTypeFilter: function()
        {
            var context = this;
            jQuery('.product_type').on('click', function(e)
            {
                var $this       = jQuery(this),
                    productType = $this.data('product_type');

                jQuery('.product_type').removeClass('active');

                if( productType == 1 )
                {
                    $('#product_type').val(1);
                    // document.getElementById("price").style.visibility='hidden';
                    $('.prd-variation').removeAttr("style");
                    $('.show_price').addClass("d-none");
                }
                else
                {
                    $('#product_type').val(0);
                    document.getElementById("price").style.visibility='visible';
                    $(".prd-variation").css("display", "none");
                    $('.show_price').removeClass("d-none");

                    // remove hidden variation
                    context.selectors.foodModal.find('form').find('.variation_hidden').each(function() {
                        $(this).remove();
                    });

                    context.selectors.foodModal.find('.modal-body').find('.variety').find('.remove').each(function() {
                        $(this).remove();
                    });
                }

                $this.addClass('active');
            });
        },

        openFoodModal: function()
        {
            var context = this;

            $('.showin-mob, .drink_datatable').on('click', '.food_modal', function(e)
            {
                e.preventDefault();

                var $this       = $(this),
                    foodId     = $this.data('id'),
                    productType = $this.data('product_type');
                    $('#product_type').val(0);
                    $('#is_featured').val(0);

                if(foodId == undefined)
                {
                    context.selectors.foodModalTitle.html('Manually Add');
                    context.addFoodFormValidation();
                    context.selectors.foodForm.attr('action', moduleConfig.addFood);

                    $('.product_type:first').addClass('active').trigger('click');

                } else {
                    context.selectors.foodModalTitle.html('Manually Edit ');
                    context.editFoodFormValidation();
                    context.selectors.foodForm.attr('action', moduleConfig.updateFood.replace(':ID', foodId));
                    $('#item_id').val(foodId);
                    context.getFoodData(foodId);
                    context.selectors.foodForm.append(`<input type="hidden" name="_method" value="PUT" />`);
                }

                context.selectors.foodModal.modal('show');
            });
        },

        openUploadFoodModal: function()
        {
            var context = this;

            $('.showin-mob, .drink_datatable').on('click', '.upload_food_modal', function(e)
            {
            //     e.preventDefault();

            //     var $this       = $(this),
            //         drinkId     = $this.data('id'),
            //         productType = $this.data('product_type');
            //         $('#product_type').val(0);
            //         $('#is_featured').val(0);

            //     if(drinkId == undefined)
            //     {
                    context.selectors.foodModalTitle.html('Import');
            //         context.addDrinkFormValidation();
            //         context.selectors.drinkForm.attr('action', moduleConfig.drinkStore);

            //     } else {
            //         context.selectors.drinkModalTitle.html('Manually Edit ');
            //         context.editDrinkFormValidation();
            //         context.selectors.drinkForm.attr('action', moduleConfig.drinkUpdate.replace(':ID', drinkId));
            //         context.getDrinkData(drinkId);
            //         context.selectors.drinkForm.append(`<input type="hidden" name="_method" value="PUT" />`);
            //     }
                context.selectors.uploadfoodModal.modal('show');
            });
        },

        closeFoodModal: function()
        {
            var context = this;
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

                context.selectors.foodForm.validate().resetForm();
                context.selectors.foodForm.get(0).reset();
                context.selectors.foodForm.find('.error').removeClass('error');
                context.selectors.foodForm.find('input[name="_method"]').remove();
                context.selectors.foodForm.removeAttr('action');
                context.selectors.foodForm.find('.modal-body').find('.variety').find('.item-box').not('.add_variations').remove();
                $this.find('.pip').remove();
                $this.find('.cstm-catgory').find('input[name="category_id[]"]').prop('checked', false);
            });
        },

        addFoodFormValidation: function()
        {
            var context = this;
            context.selectors.foodForm.validate({
                ignore: [],
                rules: {
                    name: {
                        required: true,
                    },
                    description: {
                        required: true,
                    },
                    'category_id[]' : {
                        required: true,
                    },
                    price: {
                        required: function(){
                            return jQuery('.product_type.active').data('product_type') == 0 ? true : false;
                        },
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
                        required: "Please enter amount",
                        // pattern: "Please enter a valid price format (e.g., 100.50).",
                    },
                    image: {
                        required: "Please upload files", //accept: 'Not an image!'
                    }
                },
                errorPlacement: function (error, element) {
                    if (element.attr("type") == "checkbox") {
                        error.insertAfter($(element).closest('div'));
                    } else if( element.attr("type") == 'file' ) {
                        error.insertAfter($(element).closest('div'));
                    }else{
                        error.insertAfter($(element));
                    }
                },
                submitHandler: function() {
                    context.submitFoodForm(context.selectors.foodForm.get(0));
                }
            });
        },

        editFoodFormValidation: function()
        {
            var context = this;
            context.selectors.foodForm.validate({
                rules: {
                    name: {
                        required: true,
                    },
                    description: {
                        required: true,
                    },
                    price: {
                        required: function(){
                            return jQuery('.product_type.active').data('product_type') == 0 ? true : false;
                        },
                    },
                    ingredients: {
                        required: true,
                    },
                    country_of_origin: {
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
                        required: "Please enter amount",
                        // pattern: "Please enter a valid price format (e.g., 100.50).",
                    },

                },
                submitHandler: function() {
                    context.submitFoodForm(context.selectors.foodForm.get(0));
                }
            });
        },

        submitFoodForm: function(form)
        {
            var context = this,
                data = new FormData(form);
            var category = [];
            $.each($("input[name='category_id']:checked"), function(i) {
                category[i] = $(context).val();
            });

            if( jQuery('.product_type.active').data('product_type') == 1 )
            {
                if( jQuery('input[name="drink_variation_name[]"]').length == 0 )
                {
                    XS.Common.handleSwalError('Please create atleast one variation.');
                    return false;
                }
            }

            $(".error").remove();
            XS.Common.btnProcessingStart(context.selectors.foodSubmitBtn);

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
                    document.getElementById("drinkpopup").reset();
                    XS.Common.handleSwalSuccess('Food form has been submitted successfully.');
                },
                error: function(xhr)
                {
                    if( xhr.status == 403 )
                    {
                        var {error} = xhr.responseJSON;
                        context.selectors.foodForm.find('.duplicate_product').after(`<span class="error">${error.message}</span>`);
                        // $this.closest('#add_form_category').find('.cat_name').after(`<span class="error">${error.message}</span>`);
                    }
                    if( xhr.status === 422 )
                    {
                        const {error}   = xhr.responseJSON;
                        const {message} = error;

                        $.each(message, function(index, val)
                        {
                            var elem = context.selectors.foodForm.find(`[name="${index}"]`);

                            if(elem.is("input:text"))
                            {
                                elem.closest('#price').after(`<label class="error">${val[0]}</label>`);
                                elem.closest('#name').after(`<label class="error">${val[0]}</label>`);
                            }
                        });
                    }
                },
                complete: function()
                {
                    XS.Common.btnProcessingStop(context.selectors.foodSubmitBtn);
                }
            });
        },

        getFoodData: function(id)
        {
            context = this;
            $.ajax({
                url: moduleConfig.getFood.replace(':ID',id),
                type: 'GET',
                success: function(res) {
                    $('#name').val(res.data.name);
                    $('#ingredients').val(res.data.ingredients);
                    // $('#country_of_origin').val(res.data.country_of_origin);
                    // $('#year_of_production').val(res.data.year_of_production);
                    // $('#type_of_drink').val(res.data.type_of_drink);
                    $('#description').val(res.data.description);
                    $('input[name="category_id[]"]').val(res.data.categories);
                    $('.is_favorite').attr('data-is_favorite', res.data.is_featured);

                    if(res.data.is_featured == 1){
                        $('.is_favorite').removeClass('null');
                        $('.is_favorite').attr('data-is_favorite', res.data.is_featured);
                    }else{
                        $('.is_favorite').attr('data-is_favorite', 0);
                        $('.is_favorite').addClass('null');
                    }

                    console.log(res.data);
                    $('#price').val(res.data.price);
                    $('#item_id').val(id);
                    context.selectors.foodForm.find('.modal-body').find('.variety').find('.item-box').not('.add_variations').remove();

                    $('.product_type').each(function(){
                        var $this = $(this);
                        $(this).removeClass('active');

                        if( $this.data('product_type') == res.data.is_variable )
                        {
                            $(this).addClass('active').trigger('click');
                        }
                    });


                    var image = `
                        <div class="pip">
                            <img class="imageThumb" src="${ res.data.image != "" ? res.data.image : ''}" title="" />
                            <i class="icon-trash remove"></i>
                        </div>
                    `;

                    if( res.data.variation.length > 0 )
                    {
                        $.each(res.data.variation,function(key, val)
                        {
                            context.addVariationBlock(val.name, val.price);
                        });
                    }


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