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
            restaurantTable:    jQuery('.restaurant_datatable'),
            search:             jQuery('#search'),
            restaurantForm:     jQuery('#create_update_restaurant')
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
            jQuery('.create-restaurant').on('click', function(e)
            {
                e.preventDefault();

                var $this = jQuery(this);

                jQuery('#wd930').modal('show');
            });
        },

        searchFilter: function(){
            var context = this;

            context.selectors.search.on('keyup', function()
            {
                context.table.ajax.reload();
            });
        },

        restaurantFormSubmit: function()
        {
            var context = this;

            context.selectors.restaurantForm.on('submit', function(e)
            {
                e.preventDefault();

                // ajax start

                var $this       = $(this),
                    formData    = new FormData($this.get(0));

                jQuery.ajax(
                {
                    url: moduleConfig.storeRestaurant
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
    }
})();