(function()
{
    XS_Admin.Restaurant = {
        /** table object for datatable */
        table: null,
        /** table columns for datatable */
        tableCols: [{
            "data": "name", // can be null or undefined ->type
            "defaultContent": "",
            render: function(data, type, row)
            {
                return `<label class="cst-check"><input name="id" class="checkboxitem" type="checkbox" value="${row.id}"><span class="checkmark"></span></label> ${row.name}`
            }
        },
        {
            "data": "address", // can be null or undefined
            "defaultContent": "",
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
            "defaultContent": ""
        },
        {
            "data": "country", // can be null or undefined
            "defaultContent": ""
        },
        {
            "data": "actions", // can be null or undefined
            "sortable": false,
            "defaultContent": ""
        }],

        /** selectors for customers */
        selectors: {
            restaurantTable: jQuery('.restaurant_datatable'),
            search: jQuery('#search')
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
        },

        openModal: function()
        {
            jQuery('.create-restaurant').on('click', function(e)
            {
                e.preventDefault();

                var $this = jQuery(this);

                jQuery('#restaurant_modal').modal('show');
            });
        },

        searchFilter: function(){
            var context = this;

            context.selectors.search.on('keyup', function()
            {
                context.table.ajax.reload();
            });
        },

        makeDatatable: function()
        {
            var context = this;

            context.table = context.selectors.restaurantTable.DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                order: [[0, 'asc']],
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
                columns: context.tableCols
            });
        },
    }
})();