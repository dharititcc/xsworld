(function()
{
    XS_Admin.Customer = {
        /** table object for datatable */
        table: null,
        /** table columns for datatable */
        tableCols: [
        // {
        //     "data": "id", // can be null or undefined
        //     "defaultContent": "",
        //     "width": "10%",
        //     "sortable": false,
        //     render: function (data, type, row) {
        //         return `<label class="cst-check"><input name="id" class="checkboxitem" type="checkbox" value="${row.id}"><span class="checkmark"></span></label>`
        //     }
        // },
        {
            "data": "full_name", // can be null or undefined
            "defaultContent": "",
            "width": "30%",
            render: function (data, type, row) {
                return row.full_name
            }
        },
        {
            "data": "email", // can be null or undefined
            "width": "30%",
            "defaultContent": ""
        },
        {
            "data": "phone", // can be null or undefined
            "width": "30%",
            "defaultContent": ""
        },
        {
            "data": "actions", // can be null or undefined
            "width": "10%",
            "sortable": false,
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
                columns: context.tableCols,
                drawCallback: function ( settings )
                {
                    context.selectors.customerTable.find('tbody tr').find('td:first');
                }
            }).on('click', '.res-delete', function(){
                var $this = jQuery(this),
                    userId= $this.data('id');

                swal({
                    title: `Are you sure you want to delete this Records?`,
                    // text: "It will gone forevert",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete)
                    {
                        $.ajax(
                        {
                            url: moduleConfig.deleteCustomer.replace(':ID', userId),
                            type: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                            },
                            success: function (response){
                                XS.Common.handleSwalSuccess(response.success);
                            },
                            error: function(jqXHR, exception)
                            {
                                const {error}   = jqXHR.responseJSON;
                                const {message} = error;

                                XS.Common.handleSwalError(message, true);
                            }
                        });
                    }
                });
            });
        },
    }
})();