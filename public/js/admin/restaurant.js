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
        // {
        //     "data": "id", // can be null or undefined ->type
        //     "defaultContent": "",
        //     "width": "5%",
        //     "sortable": false,
        //     render: function(data, type, row)
        //     {
        //         return `<label class="cst-check"><input name="id" class="checkboxitem" type="checkbox" value="${row.id}"><span class="checkmark"></span></label>`
        //     }
        // },
        {
            "data": "name", // can be null or undefined ->type
            "defaultContent": "",
            "width": "30%",
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

            context.makeDatatables();
            context.searchFilter();
            context.openModal();
            context.closeRestaurantModal();
            context.deleteRestaurant();
            XS.Common.fileReaderBind();

            // context.restaurantFormSubmit();

            /** Search place */
            // jQuery('body').on('keyup', '#street1, #street2, [name="country_id"], #state, #city', function() {
            //     context.initAutocomplete($(this));
            // });
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
                var type            = $(this).data('type');

                    if(type == 2) {
                        $(".img-text").html('Event Image *');
                        $('.start_end_date').removeAttr("style");
                        $('#start_date').attr("placeholder", "Please Select Start Date");
                        $('#end_date').attr("placeholder", "Please Select End Date");
                        $('#name').attr("placeholder", "Event Name *");
                    } else {
                        $(".img-text").html('Restaurant Image *');
                        $('.start_end_date').attr("style");
                        $('#name').attr("placeholder", "Restaurant Name *");
                    }

                    if(restaurantId == undefined)
                    {
                        context.selectors.restaurantModalTitle.html('Create');
                        context.addRestaurantFormValidation();
                        context.selectors.restaurantForm.attr('action', moduleConfig.storeRestaurant);
                        context.selectors.restaurantForm.find('input[type="password"]').attr('placeholder', 'Password *');
                    } else {
                        context.selectors.restaurantModalTitle.html('Edit');
                        context.editRestaurantFormValidation();
                        context.selectors.restaurantForm.attr('action', moduleConfig.updateRestaurant.replace(':ID', restaurantId));
                        context.getRestaurantData(restaurantId);
                        context.selectors.restaurantForm.append(`<input type="hidden" name="_method" value="PUT" />`);
                        context.selectors.restaurantForm.find('input[type="password"]').attr('placeholder', 'Password');
                    }

                    $("#type").val(type);
                    // context.selectors.restaurantForm.append(`<input type="hidden" name="type" id="type" value=${type} />`);
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
                // context.selectors.restaurantForm.find('input[name="type"]').remove();
                $("#type").val('');
                context.selectors.restaurantForm.removeAttr('action');
                $('.start_end_date').attr("style");
                $this.find('.pip').remove();
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
            context.selectors.restaurantTable.add('.restaurant_event_datatable').on("click", '.res-delete', function(){
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
                ignore: [],
                rules: {
                    first_name: {
                        required: true,
                    },
                    postcode: {
                        required: true,
                        digits: true,
                    },
                    image: {
                        required: true,
                        accept: "image/*",
                    },
                    street1: {
                        required: true,
                    },
                    password: {
                        required: true,
                    },
                    country_id: {
                        required: true,
                    },
                    city: {
                        required: true,
                    },
                    description: {
                        required: true,
                    },
                    email: {
                        required: true,
                        email: true,
                    },
                    phone: {
                        required: true,
                        digits: true,
                    },
                    name: {
                        required: true,
                    },
                },
                messages: {
                    name: {
                        required: "Please Enter Name",
                    },
                    image: {
                        required: "Please upload files",
                        accept:    "Only image files are allowed.", //accept: 'Not an image!'
                    },
                },
                errorPlacement: function (error, element) {
                    if (element.attr("type") == "select") {
                        error.insertAfter($(element).closest('div'));
                    } else if( element.attr("type") === 'file' ) {
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
                    street1: {
                        required: true,
                    },
                    country_id: {
                        required: true,
                    },
                    city: {
                        required: true,
                    },
                    first_name: {
                        required: true,
                    },
                    postcode: {
                        required: true,
                        digits: true,
                    },
                    email: {
                        required: true,
                        email: true,
                    },
                    phone: {
                        required: true,
                        digits: true,
                    },
                    name: {
                        required: true,
                    },
                    image: {
                        required: true,
                        accept: "image/*",
                    },
                },
                messages: {
                    image: {
                        required: "Please upload files",
                        accept:    "Only image files are allowed.", //accept: 'Not an image!'
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
            // context.selectors.restaurantForm.on('submit', function(e)
            // {
                // e.preventDefault();

                // ajax start

                var $this       = $(this),
                    formData    = new FormData(form);
                    XS.Common.btnProcessingStart(context.selectors.restaurantSubmitBtn);

                    context.selectors.restaurantForm.find('.error').remove();

                // if( jQuery('.pip').length == 0 )
                // {
                //     console.log('Image is required');
                //     XS.Common.btnProcessingStop(context.selectors.restaurantSubmitBtn);
                //     return false;
                // }

                $.ajax(
                {
                    url: $(form).attr('action'), // Use $this.attr('action') instead of $(form).attr('action')
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if(response.type == 2) {
                            XS.Common.handleSwalSuccess('Event form has been submitted successfully.');
                        } else {
                            XS.Common.handleSwalSuccess('Restaurant form has been submitted successfully.');
                        }
                        document.getElementById("create_update_restaurant").reset();
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
                        if( jqXHR.status === 403 )
                        {
                            const {error}   = jqXHR.responseJSON;
                            const {message} = error;

                            XS.Common.handleSwalError(message, true);
                        }

                    },
                    complete: function()
                    {
                        XS.Common.btnProcessingStop(context.selectors.restaurantSubmitBtn);
                    }
                });
            // });
        },

        makeDatatables: function()
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
                    context.selectors.restaurantTable.find('tbody tr').find('td:first');
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
                    $("#latitude").val(res.data.latitude);
                    $("#longitude").val(res.data.longitude);
                    $("#type").val(res.data.type);
                    $("#start_date").val(res.data.start_date);
                    $("#end_date").val(res.data.end_date);
                    $("#type").val(res.data.type);
                    

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