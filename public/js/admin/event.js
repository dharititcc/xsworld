(function()
{
    XS_Admin.Event = {
        /** table object for datatable */
        table: null,
        /** table columns for datatable */
        tableCols: [{
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
        },
        {
            "data": "address", // can be null or undefined
            "defaultContent": "",
            render: function(data, type, row)
            {
                console.log(row);

                return `
                            <p>${row.street1}</p>
                            ${row.street2 ?
                                `<p>${row.street2}</p>` : ``
                            },
                            <p>${row.city}</p>
                            <p>${row.state}</p>
                            <p>${row.postcode}</p>
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
        }],

        /** selectors for customers */
        selectors: {
            eventTable: jQuery('.event_datatable'),
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
                columns: context.tableCols
            });
        },
    }
})();