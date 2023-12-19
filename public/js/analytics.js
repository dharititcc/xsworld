(function () {
    XS.Analytic = {
        table: null,
        tableColumns: [
        {
            "data": "name", // can be null or undefined ->type
            "width": "35%",
            "defaultContent": "",
            render: function (data, type, row)
            {
                return `${row.restaurant_item.name}`;
            }
        },
        {
            "data": "type", // can be null or undefined ->type
            "width": "20%",
            "defaultContent": "",
            render: function (data, type, row)
            {
                if( row.variation )
                {
                    return `${row.variation_name}`;
                }

                return `-`;
            }
        },
        {
            "data": "price", // can be null or undefined
            "defaultContent": "",
            "width": "20%",
            "bSortable": false,
            render: function (data, type, row) {
                return `${row.order.restaurant.country.symbol}${row.restaurant_item.price}`;
            }
        },
        {
            "data": "count", // can be null or undefined
            "defaultContent": "",
            "width": "25%",
            "bSortable": false,
            render: function (data, type, row) {
                if( row.variation_id )
                {
                    var cal = parseInt(row.variation_count) * parseInt(row.variation_qty_sum);
                    return `${cal} Units Sold`;
                }
                else
                {
                    var cal = parseInt(row.total_item ? row.total_item : 0) * parseInt(row.total_quantity ? row.total_quantity : 0)
                    return `${cal} Units Sold`;
                }
            }
        }
        ],
        selectors: {
            drinkModal:     jQuery('#wd930'),
            graphContainer: jQuery('#mygraph'),
            drinkTable:     jQuery('.drink_datatable'),
        },

        init: function (){
            this.addHandler();
        },

        addHandler: function (){
            var context = this;
            context.makeDatatable();
            context.filterChart();
        },

        filterChart: function()
        {
            context = this;

            context.getChart([]);

            XS.Common.showLoader(jQuery('#mygraph'));

            jQuery.ajax(
            {
                url:moduleConfig.graphUrl,
                type:'POST',
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content'),
                },
                data: {'is_featured':'Test','id':1},
                success: function(res)
                {
                    console.log(res);
                    context.getChart(res);
                },
                complete: function()
                {
                    XS.Common.hideLoader(jQuery('#mygraph'));
                },
            });
        },

        getChart: function(chartResponse)
        {
            var context     = this,
                chart       = null,
                categories  = chartResponse.data,
                seriesArr   = [];

            context.chart = new Highcharts.Chart({
                chart: {
                    renderTo: 'mygraph',
                    type: 'line',
                    backgroundColor: 'transparent'
                },
                title: {
                    text: 'Comparison of Sugar, Rice and Wheat Flour'
                },
                xAxis: {
                    categories: chartResponse.x
                },
                yAxis: {
                    title: {
                        text: 'Number of Orders Amount'
                    }
                },
                tooltip: {
                    formatter: function() {
                            return '<b>'+ this.series.name +'</b><br/>'+
                            this.x +': '+ this.y;
                    }
                },
                legend: {
                    align: 'left',
                    verticalAlign: 'bottom',
                    borderWidth: 0
                },
                plotOptions: {
                    series: {
                        label: {
                            connectorAllowed: false
                        },
                    }
                },
                // series: [{
                //     name: 'Installation & Developers',
                //     data: [43934, 48656, 65165, 81827, 112143, 142383,
                //         171533, 165174]
                // }],
                series: categories,
                responsive: {
                    rules: [{
                        condition: {
                            maxWidth: 500
                        },
                        chartOptions: {
                            legend: {
                                layout: 'horizontal',
                                align: 'center',
                                verticalAlign: 'bottom'
                            }
                        }
                    }]
                }
            });
        },


        makeDatatable: function (){
            var context     = this;

            context.table = context.selectors.drinkTable.DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                order: [[0, 'asc']],
                ajax: {
                    url: moduleConfig.getAccessibles,
                    type: 'get'
                },
                columns: context.tableColumns,
                drawCallback: function ( settings )
                {
                    context.selectors.drinkTable.find('tbody tr').find('td:first').addClass('dt-center');
                }
            });
        },
    }
})();