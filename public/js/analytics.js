(function() {
    XS.Analytic = {
        table: null,
        tableColumns: [{
                "data": "restaurant_items.name", // can be null or undefined ->type
                "width": "35%",
                "defaultContent": "",
                render: function(data, type, row) {
                    return `${row.restaurant_item.name}`;
                }
            },
            {
                "data": "type", // can be null or undefined ->type
                "width": "20%",
                "defaultContent": "",
                render: function(data, type, row) {
                    if (row.variation) {
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
                render: function(data, type, row) {
                    var price = parseInt(row.price) * parseFloat(row.variation_qty_sum);
                    return `${row.order.restaurant.country.symbol}${price.toFixed(2)}`;
                }
            },
            {
                "data": "count", // can be null or undefined
                "defaultContent": "",
                "width": "25%",
                "bSortable": false,
                render: function(data, type, row) {
                    if (row.variation_id) {
                        // var cal = parseInt(row.variation_count) * parseInt(row.variation_qty_sum);
                        return `${row.variation_qty_sum} Units Sold`;
                    } else {
                        var cal = parseInt(row.total_item ? row.total_item : 0) * parseInt(row.total_quantity ? row.total_quantity : 0)
                        console.log(cal);
                        return `${cal} Units Sold`;
                    }
                }
            }
        ],
        selectors: {
            drinkModal: jQuery('#wd930'),
            graphContainer: jQuery('#mygraph'),
            drinkTable: jQuery('.drink_datatable'),
        },

        init: function() {
            this.addHandler();
        },

        addHandler: function() {
            var context = this;
            context.makeDatatable();
            context.bindChart();
            context.filterChart();

            // Analytics range picker
            $('input[name="dates"]').daterangepicker({
                startDate: moment().startOf('month'),
                endDate: moment().endOf('month'),
            }).on('apply.daterangepicker', function(e, picker) {
                var startDate = picker.startDate.format('DD-MM-YYYY');
                var endDate = picker.endDate.format('DD-MM-YYYY');
                context.bindChart();
                // context.makeDatatable(startDate , endDate);
                context.table.ajax.reload();
            });

            context.categoryFilter();

        },

        categoryFilter: function() {
            jQuery('.product_items').on('click', function(e) {
                e.preventDefault();
                var $this = $(this),
                    categoryId = $this.data('category_id');

                // clear active class
                $this.closest('ul').find('li').find('a').each(function() {
                    $(this).removeClass('active');
                });

                if (!categoryId) {
                    // all focus
                    $this.find('.product_items').removeClass('active');
                    $this.addClass('active');
                } else {
                    // specific category focus
                    $this.find('.product_items').removeClass('active');
                    $this.addClass('active');
                }

                context.bindChart();
                context.table.ajax.reload();
            });
        },

        filterChart: function() {
            context = this;
        },

        bindChart: function() {
            var context         = this,
                categoryFilter  = $('.item-list.overview').find('li').find('a.active').data('category_id'),
                picker          = jQuery('input[name="dates"]'),
                dateVal         = picker.val(),
                dateArr         = dateVal.split('-'),
                startDate       = dateArr[0],
                endDate         = dateArr[1],
                start_date      = dateVal != '' ? startDate : '',
                end_date        = dateVal != '' ? endDate : '';

            context.getChart([]);
            XS.Common.showLoader(jQuery('#mygraph'));

            jQuery.ajax({
                url: moduleConfig.graphUrl,
                type: 'POST',
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content'),
                },
                data: { 'start_date': start_date, 'end_date': end_date, category_id: categoryFilter },
                success: function(res) {
                    // console.log(res);
                    context.getChart(res);
                },
                complete: function() {
                    XS.Common.hideLoader(jQuery('#mygraph'));
                },
            });
        },

        /**
         * Get Chart
         * @param {*} chartResponse
         */
        getChart: function(chartResponse) {
            var context = this,
                chart = null,
                categories = chartResponse.data,
                seriesArr = [];

            context.chart = new Highcharts.Chart({
                chart: {
                    renderTo: 'mygraph',
                    type: 'line',
                    backgroundColor: 'transparent'
                },
                title: {
                    text: ''
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
                        return '<b>' + this.series.name + '</b><br/>' +
                            this.x + ': ' + this.y;
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


        /**
         * Make Datatable
         */
        makeDatatable: function() {
            var context = this;

            context.table = context.selectors.drinkTable.DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                scrollCollapse: true,
                scrollY: '500px',
                order: [
                    [0, 'asc']
                ],
                ajax: {
                    url: moduleConfig.getAccessibles,
                    type: 'get',
                    data: function(data) {
                        var categoryFilter  = $('.item-list.overview').find('li').find('a.active').data('category_id'),
                            picker          = jQuery('input[name="dates"]'),
                            dateVal         = picker.val(),
                            dateArr         = dateVal.split('-'),
                            startDate       = dateArr[0],
                            endDate         = dateArr[1];

                        data.category       = categoryFilter === undefined ? 0 : categoryFilter
                        data.start_date      = dateVal != '' ? startDate : '';
                        data.end_date        = dateVal != '' ? endDate : '';
                    },
                },
                columns: context.tableColumns,
                drawCallback: function(settings) {
                    context.selectors.drinkTable.find('tbody tr').find('td:first').addClass('dt-center');
                }
            });
        },
    }
})();