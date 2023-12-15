(function()
{
    XS_Admin.Restaurant = {
        placeSearch: null,
        autocomplete: null,
        map: null,
        /** table object for datatable */
        table: null,
        /** table columns for datatable */
        tableCols: [
        {
            "data": "id", // can be null or undefined ->type
            "defaultContent": "",
            "width": "5%",
            "sortable": false,
            render: function(data, type, row)
            {
                return `<label class="cst-check"><input name="id" class="checkboxitem" type="checkbox" value="${row.id}"><span class="checkmark"></span></label>`
            }
        },
        {
            "data": "name", // can be null or undefined ->type
            "defaultContent": "",
            "width": "25%",
            render: function(data, type, row)
            {
                return `${row.name}`
            }
        },
        {
            "data": "address", // can be null or undefined
            "defaultContent": "",
            "width": "25%",
            render: function(data, type, row)
            {
                return `
                            ${row.street1},
                            ${row.street2 ?
                                `${row.street2}` : ``
                            },
                            ${row.city},
                            ${row.state}<br/>
                            ${row.postcode}
                        `;
            }
        },
        {
            "data": "phone", // can be null or undefined
            "width": "15%",
            "defaultContent": ""
        },
        {
            "data": "country", // can be null or undefined
            "width": "15%",
            "defaultContent": ""
        },
        {
            "data": "actions", // can be null or undefined
            "sortable": false,
            "defaultContent": "",
            "width": "15%",
        }
    ],

        /** selectors for customers */
        selectors: {
            restaurantTable:            jQuery('.restaurant_datatable'),
            search:                     jQuery('#search'),
            restaurantForm:             jQuery('#create_update_restaurant'),
            restaurantSubmitBtn:        jQuery('#submitBtn'),
            restaurantModalTitle:       jQuery('.model_title'),
            restaurantDelete:           jQuery('.res-delete'),
        },

        init: function()
        {
            this.addHandler();
        },

        addHandler: function()
        {
            var context = this;

            context.makeDatatable();
            context.searchFilter();
            context.openModal();
            context.closeRestaurantModal();
            // context.addRestaurantFormValidation();
            context.deleteRestaurant();
            XS.Common.fileReaderBind();

            context.restaurantFormSubmit();

            /** Search place */
            jQuery('body').on('keyup', '#street1, #street2, [name="country_id"], #state, #city', function() {
                context.initAutocomplete($(this));
            });
        },

        initAutocomplete: function(elem)
        {
            var context = this;

            var street1Field = document.querySelector('#street1');
            var street2Field = document.querySelector('#street2');
            var postcodeField= document.querySelector('#postcode');

            // Create the autocomplete object, restricting the search predictions to
            // addresses in the US and Canada.
            context.autocomplete = new google.maps.places.Autocomplete(street1Field, {
                componentRestrictions: {country: "au"},
                fields: ["address_components", "geometry"],
                types: ["address"],
            });
            street1Field.focus();

            // When the user selects an address from the drop-down, populate the
            // address fields in the form.
            context.autocomplete.addListener("place_changed", context.fillInAddress);
        },

        fillInAddress: function()
        {
            var context = this;

            // Get the place details from the autocomplete object.
            const place = context.autocomplete.getPlace();
            let address1 = "";
            let postcode = "";

            console.log(place);

            // Get each component of the address from the place details,
            // and then fill-in the corresponding field on the form.
            // place.address_components are google.maps.GeocoderAddressComponent objects
            // which are documented at http://goo.gle/3l5i5Mr
            // for (const component of place.address_components) {
            //     // @ts-ignore remove once typings fixed
            //     const componentType = component.types[0];

            //     switch (componentType) {
            //     case "street_number": {
            //         address1 = `${component.long_name} ${address1}`;
            //         break;
            //     }

            //     case "route": {
            //         address1 += component.short_name;
            //         break;
            //     }

            //     case "postal_code": {
            //         postcode = `${component.long_name}${postcode}`;
            //         break;
            //     }

            //     case "postal_code_suffix": {
            //         postcode = `${postcode}-${component.long_name}`;
            //         break;
            //     }
            //     case "locality":
            //         document.querySelector("#locality").value = component.long_name;
            //         break;
            //     case "administrative_area_level_1": {
            //         document.querySelector("#state").value = component.short_name;
            //         break;
            //     }
            //     case "country":
            //         document.querySelector("#country").value = component.long_name;
            //         break;
            //     }
            // }

            // address1Field.value = address1;
            // postalField.value = postcode;
            // // After filling the form with address components from the Autocomplete
            // // prediction, set cursor focus on the second address line to encourage
            // // entry of subpremise information such as apartment, unit, or floor number.
            // address2Field.focus();
        },

        openModal: function()
        {
            var context = this;
            jQuery('body').on('click', '.create-restaurant', function(e)
            {
                e.preventDefault();
                var $this           = jQuery(this);
                var restaurantId    = $(this).data('id');

                    if(restaurantId == undefined)
                    {
                        context.selectors.restaurantModalTitle.html('Create');
                        context.selectors.restaurantForm.attr('action', moduleConfig.storeRestaurant);
                    } else {
                        context.selectors.restaurantModalTitle.html('Edit');
                        context.editRestaurantFormValidation();
                        context.selectors.restaurantForm.attr('action', moduleConfig.updateRestaurant.replace(':ID', restaurantId));
                        context.getRestaurantData(restaurantId);
                        // console.log(context.selectors.restaurantForm.get(0));return false;
                        context.restaurantFormSubmit(context.selectors.restaurantForm.get(0));
                        context.selectors.restaurantForm.append(`<input type="hidden" name="_method" value="PUT" />`);
                    }

                jQuery('#wd930').modal('show');
            });
        },

        closeRestaurantModal: function()
        {
            var context = this;
            jQuery('#wd930').on('hide.bs.modal', function()
            {
                var $this = $(this);

                context.selectors.restaurantForm.get(0).reset();
                context.selectors.restaurantForm.validate().resetForm();
                context.selectors.restaurantForm.find('.error').removeClass('error');
                context.selectors.restaurantForm.find('input[name="_method"]').remove();
                context.selectors.restaurantForm.removeAttr('action');
                $this.find('.pip').remove();
                $this.find('.cstm-catgory').find('input[name="category_id[]"]').prop('checked', false);
                context.selectors.restaurantForm.find('.variation_hidden').remove();
            });
        },


        searchFilter: function(){
            var context = this;

            context.selectors.search.on('keyup', function()
            {
                context.table.ajax.reload();
            });
        },

        deleteRestaurant: function()
        {
            
            var context = this;
            context.selectors.restaurantTable.on("click", '.res-delete', function(){
                var id = $(this).data("id");


                swal({
                    title: `Are you sure you want to delete this Records?`,
                    // text: "It will gone forevert",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                    .then((willDelete) => {
                        if (willDelete) {
                            $.ajax(
                            {
                                url: moduleConfig.deleteRestaurant.replace(':ID', id),
                                type: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                                },
                                data: {
                                    "id": id,
                                },
                                success: function (){
                                    XS.Common.handleSwalSuccess('Restaurant form has been Deleted successfully.');
                                }
                            });
                            form.submit();
                        }
                    });
            });
        },

        addRestaurantFormValidation: function()
        {
            var context = this;
            context.selectors.restaurantForm.validate({
                rules: {
                    name: {
                        required: true,
                    },
                    street1: {
                        required: true,
                    },
                    description: {
                        required: true,
                    },
                    first_name: {
                        required: true,
                    },
                    image: {
                        required: true,
                    },
                    email: {
                        required: true,
                    },
                    country_id: {
                        required: true,
                    },
                    phone: {
                        required: true,
                    },
                    city: {
                        required: true,
                    },
                    password: {
                        required: true
                    },
                },
                messages: {
                    name: {
                        required: "Please enter Restaurant name",
                        maxlength: "Your name maxlength should be 50 characters long."
                    },
                    first_name: {
                        required: "Please enter first name",
                        maxlength: "Your name maxlength should be 50 characters long."
                    },
                    image: {
                        required: "Please upload files", //accept: 'Not an image!'
                    },
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
                    context.restaurantFormSubmit(context.selectors.restaurantForm.get(0));
                }
            });
        },

        editRestaurantFormValidation: function()
        {
            var context = this;
            context.selectors.restaurantForm.validate({
                rules: {
                    name: {
                        required: true,
                    },
                    street1: {
                        required: true,
                    },
                    description: {
                        required: true,
                    },
                    first_name: {
                        required: true,
                    },
                    email: {
                        required: true,
                    },
                    country_id: {
                        required: true,
                    },
                    phone: {
                        required: true,
                    },
                    city: {
                        required: true,
                    },
                    password: {
                        required: true
                    },
                },
                messages: {
                    name: {
                        required: "Please enter Restaurant name",
                        maxlength: "Your name maxlength should be 50 characters long."
                    },
                    first_name: {
                        required: "Please enter first name",
                        maxlength: "Your name maxlength should be 50 characters long."
                    },
                    image: {
                        required: "Please upload files", //accept: 'Not an image!'
                    },
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
                    context.restaurantFormSubmit(context.selectors.restaurantForm.get(0));
                }
            });
        },

        restaurantFormSubmit: function(form)
        {
            var context = this;

            context.selectors.restaurantForm.on('submit', function(e)
            {
                e.preventDefault();

                // ajax start

                var $this       = $(this),
                    formData    = new FormData($this.get(0));
                    XS.Common.btnProcessingStart(context.selectors.restaurantSubmitBtn);

                    context.selectors.restaurantForm.find('.error').remove();

                jQuery.ajax(
                {
                    url: $(form).attr('action'),
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        document.getElementById("create_update_restaurant").reset();
                        XS.Common.handleSwalSuccess('Restaurant form has been submitted successfully.');
                    },
                    error: function(jqXHR, exception)
                    {
                        if( jqXHR.status === 422 )
                        {
                            const {error}   = jqXHR.responseJSON;
                            const {message} = error;

                            $.each(message, function(index, val)
                            {
                                var elem = context.selectors.restaurantForm.find(`[name="${index}"]`);

                                if(elem.is("input:file"))
                                {
                                    elem.closest('.featured-img').after(`<label class="error">${val[0]}</label>`);
                                }
                                else
                                {
                                    elem.after(`<label class="error">${val[0]}</label>`);
                                }
                                
                                
                            });
                        }
                    },
                    complete: function()
                    {
                        XS.Common.btnProcessingStop(context.selectors.restaurantSubmitBtn);
                    }
                });
            });
        },

        makeDatatable: function()
        {
            var context = this;

            context.table = context.selectors.restaurantTable.DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                order: [[1, 'asc']],
                ajax: {
                    headers: {
                        'X-CSRF-TOKEN': XS.Common.csrfToken()
                    },
                    url: moduleConfig.tableAjax,
                    type: 'post',
                    data: function(data)
                    {
                        data.search_main    = context.selectors.search.val();
                    },
                },
                columns: context.tableCols,
                drawCallback: function ( settings )
                {
                    context.selectors.restaurantTable.find('tbody tr').find('td:first').addClass('dt-center');
                }
            });
        },

        getRestaurantData: function(id)
        {
            var context = this;
            $.ajax({
                url: moduleConfig.getRestaurant.replace(':ID',id),
                type: "GET",
                success: function(res){
                    console.log(res);
                    $("#name").val(res.data.name);
                    $("#street1").val(res.data.street1);
                    $("#street2").val(res.data.street2);
                    $("#state").val(res.data.state);
                    $("#city").val(res.data.city);
                    $("#postcode").val(res.data.postcode);
                    $("#description").val(res.data.specialisation);
                    $("#first_name").val(res.data.first_name);
                    $("#last_name").val(res.data.last_name);
                    $("#email").val(res.data.email);
                    // $("#password").val(res.data.password);
                    $("#phone").val(res.data.phone);
                    $('select option[value="'+res.data.country_id+'"]').attr("selected",true);
                    $("#id").val(res.data.id);
                    

                    var image = `
                            <div class="pip">
                                <input type="hidden" name="image" value="${res.data.image != "" ? res.data.image : ''}" accept="image/*">
                                <img class="imageThumb" src="${res.data.image != "" ? res.data.image : ''}" title=""/>
                                <i class="icon-trash remove"></i>
                            </div>`;

                    if(res.data.image != "")
                    {
                        $(".image_box").children('.pip').remove();
                        $("#upload").after(image);
                    }

                    $(".remove").click(function() {
                        $(this).parent('.pip').remove();
                    })
                }
            });
        }
    }
})();