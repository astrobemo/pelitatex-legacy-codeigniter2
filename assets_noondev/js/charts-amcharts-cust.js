var ChartsAmcharts = function() {

    var tahun = $("#daftar-tahun").val();

    var initChart1 = function() {
        var chart = AmCharts.makeChart("chart_1", {
            "type": "serial",
            "theme": "light",
            "pathToImages": Metronic.getGlobalPluginsPath() + "amcharts/amcharts/images/",
            "autoMargins": false,
            "marginLeft": 80,
            "marginRight": 8,
            "marginTop": 10,
            "marginBottom": 26,

            "fontFamily": 'Open Sans',
            "color":    '#888',
            
            
            "valueAxes": [{
                "position": "left",
                "axisAlpha" : 0.15,
                "minimum" : 100000,
                "maximum" : 1000000,
                "dashLength" : 3,
        
            }],
            "startDuration": 1,
            "graphs": [{
                "alphaField": "alpha",
                "balloonText": "<span style='font-size:13px;'>[[title]] in [[category]]:<b>[[value]]</b> [[additional]]</span>",
                "dashLengthField": "dashLengthColumn",
                "fillAlphas": 1,
                "title": "Penjualan",
                "type": "column",
                "valueField": "penjualan"
            }],
            "categoryField": "month",
            "categoryAxis": {
                "gridPosition": "start",
                "axisAlpha": 0,
                "tickLength": 0,
                "labelRotation":10
            }
        });
        
        var customer_id = $('#customer_id_data').html();
        var url = "master/get_penjualan_tahun?customer_id="+customer_id+"&tahun="+tahun;
        var data_st  ={};
        var chartData = [];
        var j = 0;

        ajax_data_sync(url,data_st).done(function(data_respond /* ,textStatus, jqXHR */){
            // console.log(data_respond);
            $.each(JSON.parse(data_respond),function(k,v){
             chartData[j] = {
                 'month': v.tanggal,
                 'penjualan': v.amount
             }
             j++;
            });
            // console.log(chartData);

        chart.dataProvider = chartData;
        chart.validateData();

            
        });

        $('#chart_1').closest('.portlet').find('.fullscreen').click(function() {
            chart.invalidateSize();
        });
    }

    var initChart2 = function() {
        var chart = AmCharts.makeChart("chart_2", {
            "theme": "light",
            "type": "serial",
            "startDuration": 2,

            "fontFamily": 'Open Sans',
            
            "color":    '#888',

            
            "valueAxes": [{
                "position": "left",
                "axisAlpha": 0,
                "gridAlpha": 0
            }],
            "graphs": [{
                "balloonText": "[[category]]: <b>[[value]]</b>",
                "colorField": "color",
                "fillAlphas": 0.85,
                "lineAlpha": 0.1,
                "type": "column",
                "topRadius": 1,
                "valueField": "qty"
            }],
            "depth3D": 40,
            "angle": 30,
            "chartCursor": {
                "categoryBalloonEnabled": false,
                "cursorAlpha": 0,
                "zoomable": false
            },
            "categoryField": "barang",
            "categoryAxis": {
                "gridPosition": "start",
                "axisAlpha": 0,
                "gridAlpha": 0,
                "labelRotation":10
            },
            "exportConfig": {
                "menuTop": "20px",
                "menuRight": "20px",
                "menuItems": [{
                    "icon": '/lib/3/images/export.png',
                    "format": 'png'
                }]
            }
        }, 0);

        var warna = ['#FF0F00','#FF6600','#FF9E01','#FCD202','#F8FF01','#B0DE09','#04D215','#0D8ECF','#0D52D1','#2A0CD0'];
        
        var customer_id = $('#customer_id_data').html();
        var url = "master/get_barang_jual_terbanyak?customer_id="+customer_id+"&tahun="+tahun;
        var data_st  ={};
        var chartData = [];
        var j = 0;

        ajax_data_sync(url,data_st).done(function(data_respond /* ,textStatus, jqXHR */){
            // console.log(data_respond);
            $.each(JSON.parse(data_respond),function(k,v){
                chartData[j] = {
                    'barang': v.barang,
                    'qty': v.qty,
                    'color':warna[j]
                }

                j++;

            });
        });
        // console.log(chartData);

        chart.dataProvider = chartData;
        chart.validateData();

            
        
        $('#chart_2').closest('.portlet').find('.fullscreen').click(function() {
            chart.invalidateSize();
        });
    }

    var initChart3 = function() {
        var chart = AmCharts.makeChart("chart_3", {
            "type": "pie",
            "theme": "light",

            "fontFamily": 'Open Sans',
            
            "color":    '#888',            
            "valueField": "qty",
            "titleField": "barang",
            "exportConfig": {
                menuItems: [{
                    icon: Metronic.getGlobalPluginsPath() + "amcharts/amcharts/images/export.png",
                    format: 'png'
                }]
            }
        });

        var warna = ['#FF0F00','#FF6600','#FF9E01','#FCD202','#F8FF01','#B0DE09','#04D215','#0D8ECF','#0D52D1','#2A0CD0'];
        var customer_id = $('#customer_id_data').html();
        var url = "master/get_barang_jual_terbanyak?customer_id="+customer_id+"&tahun="+tahun;
        var data_st  ={};
        var chartData = [];
        var j = 0;

        ajax_data_sync(url,data_st).done(function(data_respond /* ,textStatus, jqXHR */){
            console.log(data_respond);
            $.each(JSON.parse(data_respond),function(k,v){
                chartData[j] = {
                    "barang": v.barang,
                    "qty": v.qty
                }
                j++;

            });

            chart.dataProvider = chartData;
            chart.validateData();
        });

        $('#chart_3').closest('.portlet').find('.fullscreen').click(function() {
            chart.invalidateSize();
        });
    }

    return {
        //main function to initiate the module

        init: function() {

            initChart1();
            initChart2();
            initChart3();
            // initChart4();
            // initChart5();

           
        }

    };

}();