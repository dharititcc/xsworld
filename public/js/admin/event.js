(function()
{
    XS_Admin.Event = {
        /** table object for datatable */
        table: null,
        /** table columns for datatable */
        tableCols: [{
            "data": "id", // can be null or undefined
            "defaultContent": "",
            "width": "5%",
            "sortable": false,
            render: function (data, type, row) {
                return `<label class="cst-check"><input name="id" class="checkboxitem" type="checkbox" data-type="2" value="${row.id}"><span class="checkmark"></span></label>`
            }
        },
        {
            "data": "name", // can be null or undefined ->type
            "width": "25%",
            "defaultContent": "",
        },
        {
            "data": "address", // can be null or undefined
            "width": "25%",
            "defaultContent": "",
            render: function(data, type, row)
            {

                return `
                            ${row.street1},
                            ${row.street2 ?
                                `${row.street2},` : ``
                            }
                            ${row.city},<br/>
                            ${row.state}-${row.postcode}
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
        }],

        /** selectors for customers */
        selectors: {
            eventTable: jQuery('.restaurant_event_datatable'),
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

            context.table = context.selectors.eventTable.DataTable({
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
                    context.selectors.eventTable.find('tbody tr').find('td:first').addClass('dt-center');
                }
            });
        },
    }
})();