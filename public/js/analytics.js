(function () {
    XS.Analytic = {
        table: null,
        tableColumns: [
        {
            "data": "", // can be null or undefined ->type
            "defaultContent": "",
            "width": "5%",
            "sortable": false,
            render: function (data, type, row) {
                return `<label class="cst-check"><input name="id" class="checkboxitem" type="checkbox" value="${row.id}"><span class="checkmark"></span></label>`
            }
        },
        {
            "data": "name", // can be null or undefined ->type
            "width": "25%",
            "defaultContent": "",
            render: function (data, type, row) {
                console.log(row.order_items.restaurant_item);
                var color = (row.order_items.restaurant_item.is_available == 1) ? "green" : "red";
                return `<div class="prdname ${color}"> ${row.order_items.restaurant_item.name} </div>
                        <a href="javascript:void(0);" data-id="${row.id}" class="drink_modal edit">Edit</a>
                        <div class="add-date">Added ${XS.Common.formatDate(row.created_at)}</div>`
            }
        },
        {
            "data": "type", // can be null or undefined
            "defaultContent": "",
            "width": "15%",
            "bSortable": false,
            render: function (data, type, row) {
                var text = "";
                // if (row.variations.length > 0) {
                //     for (let i = 0; i < row.variations.length; i++) {
                //         text += '<label class="">' + row.variations[i]['name'] + "</label>";
                //     }
                //     return text
                // }
                return ""
            }
        },
        {
            "data": "price", // can be null or undefined
            "defaultContent": "",
            "width": "10%",
            "bSortable": false,
            render: function (data, type, row) {
                var text = "";
                // if (row.variations.length > 0) {
                //     for (let i = 0; i < row.variations.length; i++) {
                //         text += `<label class="price">${moduleConfig.currency}${row.variations[i]['price']}</label>`;
                //     }
                //     return text
                // }
                return `<label class="price">${moduleConfig.currency}${row.order_items.restaurant_item.price}</label>`;
            }
        },
       
        {
            "data": "favorite", // can be null or undefined
            "defaultContent": "",
            "width": "5%",
            "class": "dt-center",
            "bSortable": false,
            render: function (data, type, row) {
                return `<a href="javascript:void(0)" class="favorite ${row.order_items.restaurant_item.is_featured == 0 ? 'null' : ''} "  data-is_featured="${row.order_items.restaurant_item.is_featured == 0 ? 1 : 0}" data-id="${row.order_items.restaurant_item.id}"></a>`
            }
        }
        ],
        selectors: {
            drinkModal: jQuery('#wd930'),
            drinkTable:         jQuery('.drink_datatable'),
        },

        init: function (){
            this.addHandler();
        },

        addHandler: function (){
            var context = this;
            context.makeDatatable();
            context.getChart();
        },


        makeDatatable: function (){
            var context     = this;

            context.table = context.selectors.drinkTable.DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                order: [[1, 'asc']],
                ajax: {
                    url: moduleConfig.getAccessibles,
                    type: 'get',
                    data: function(data)
                    {
                        var checkboxes = $.map($('input[name="id"]:checked'), function(c){return c.value; });
                    },
                },
                columns: context.tableColumns,
                drawCallback: function ( settings )
                {
                    context.selectors.drinkTable.find('tbody tr').find('td:first').addClass('dt-center');
                }
            });
        },


        getChart: function()
        {
            var context = this;

            // A point click event that uses the Renderer to draw a label next to the point
            // On subsequent clicks, move the existing label instead of creating a new one.
            Highcharts.addEvent(Highcharts.Point, 'click', function () {
                if (this.series.options.className.indexOf('popup-on-click') !== -1) {
                    const chart = this.series.chart;
                    const date = Highcharts.dateFormat('%A, %b %e, %Y', this.x);
                    const text = `<b>${date}</b><br/>${this.y} ${this.series.name}`;

                    const anchorX = this.plotX + this.series.xAxis.pos;
                    const anchorY = this.plotY + this.series.yAxis.pos;
                    const align = anchorX < chart.chartWidth - 200 ? 'left' : 'right';
                    const x = align === 'left' ? anchorX + 10 : anchorX - 10;
                    const y = anchorY - 30;
                    if (!chart.sticky) {
                        chart.sticky = chart.renderer
                            .label(text, x, y, 'callout',  anchorX, anchorY)
                            .attr({
                                align,
                                fill: 'rgba(0, 0, 0, 0.75)',
                                padding: 10,
                                zIndex: 7 // Above series, below tooltip
                            })
                            .css({
                                color: 'white'
                            })
                            .on('click', function () {
                                chart.sticky = chart.sticky.destroy();
                            })
                            .add();
                    } else {
                        chart.sticky
                            .attr({ align, text })
                            .animate({ anchorX, anchorY, x, y }, { duration: 250 });
                    }
                }
            });


            // Highcharts.chart('mygraph', {
            //     chart: {
            //         scrollablePlotArea: {
            //             minWidth: 700
            //         },
            //         className: 'analytic-chart',
            //         backgroundColor:'transparent',
            //     },


            //     // data: {
            //     //     csvURL: 'https://cdn.jsdelivr.net/gh/highcharts/highcharts@v7.0.0/samples/data/analytics.csv',
            //     //     beforeParse: function (csv) {
            //     //         return csv.replace(/\n\n/g, '\n');
            //     //     }
            //     // },

            //     title: {
            //         text: 'Daily sessions',
            //         align: 'left',
            //         color: '#fff'
            //     },

            //     subtitle: {
            //         text: null,
            //         align: 'center',
            //         // y: 14
            //     },

            //     xAxis: {
            //         tickInterval: 7 * 24 * 3600 * 1000, // one week
            //         tickWidth: 0,
            //         gridLineWidth: 1,
            //         labels: {
            //             align: 'center',
            //             x: 3,
            //             y: -3
            //         }
            //     },

            //     yAxis: [{ // left y axis
            //         title: {
            //             text: null
            //         },
            //         labels: {
            //             align: 'left',
            //             x: 3,
            //             y: 16,
            //             format: '{value:.,0f}'
            //         },
            //         showFirstLabel: false
            //          }, { // right y axis
            //         linkedTo: 0,
            //         gridLineWidth: 0,
            //         opposite: true,
            //         title: {
            //             text: null
            //         },
            //         labels: {
            //             align: 'right',
            //             x: -3,
            //             y: 16,
            //             format: '{value:.,0f}'
            //         },
            //         showFirstLabel: false
            //     }],

            //     legend: {
            //         align: 'left',
            //         verticalAlign: 'bottom',
            //         borderWidth: 0
            //     },

            //     tooltip: {
            //         shared: true,
            //         crosshairs: true
            //     },

            //     plotOptions: {
            //         series: {
            //             cursor: 'pointer',
            //             className: 'popup-on-click',
            //             marker: {
            //                 lineWidth: 1
            //             },
            //             label: {
            //                 connectorAllowed: false
            //               },
            //             pointStart: 2023
            //         }
            //     },

            //     series: [{
            //         name: 'All sessions',
            //         lineWidth: 4,
            //         marker: {
            //             radius: 4
            //         },
            //         data: orders,
            //     }, {
            //         name: 'New users'
            //     }],
                
            // });


            Highcharts.chart('mygraph', {
                title: {
                    text: 'New User Growth, 2020'
                },
                subtitle: {
                    text: null
                },
                xAxis: {
                    // categories: ['1','5', '10', '15', '20', '25', '30'
                    // ]
                },
                yAxis: {
                    title: {
                        text: 'Number of New Users'
                    }
                },
                legend: {
                    layout: 'vertical',
                    align: 'right',
                    verticalAlign: 'middle'
                },
                plotOptions: {
                    series: {
                        allowPointSelect: true
                    }
                },
                series: [{
                    name: 'New Users',
                    data: order
                },
            ],
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
    }
})();