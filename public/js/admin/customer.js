(function()
{
    XS_Admin.Customer = {
        /** table object for datatable */
        table: null,
        /** table columns for datatable */
        tableCols: [{
            "data": "id", // can be null or undefined
            "defaultContent": "",
            "sortable": false,
            render: function (data, type, row) {
                return `<label class="cst-check"><input name="id" class="checkboxitem" type="checkbox" value="${row.id}"><span class="checkmark"></span></label> #${row.id} ${row.full_name}`
            }
        },
        {
            "data": "email", // can be null or undefined
            "defaultContent": ""
        },
        {
            "data": "phone", // can be null or undefined
            "defaultContent": ""
        }],

        /** selectors for customers */
        selectors: {
            customerTable: jQuery('.customer_datatable'),
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

            context.table = context.selectors.customerTable.DataTable({
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
                        // var checkboxes = $.map($('input[name="id"]:checked'), function(c){return c.value; });
                        // data.category       = jQuery('.drink_cat.active').data('category_id'),
                        data.search_main    = context.selectors.search.val();
                        // data.enable         = $('#enable').get(0).classList.contains('enable_clicked') ? checkboxes : [],
                        // data.disable        = $('#disable').get(0).classList.contains('disable_clicked') ? checkboxes : []
                    },
                },
                columns: context.tableCols
            });
        },
    }
})();