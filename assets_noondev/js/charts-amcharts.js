var ChartsAmcharts = function() {

    const tahun = $("#tahun_2").val();
    let doneLoading = 0;

    function cekDoneLoading(){
        // console.log(doneLoading);
        if (doneLoading >= 8) {
            $("#warna-tab, #barang-warna-tab, #customer-tab").removeClass('active');
        };
    }

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
                "minimum" : 500000,
                "maximum" : 20000000,
                "dashLength" : 3,
        
            }],
            "startDuration": 1,
            "graphs": [{
                "alphaField": "alpha",
                "balloonText": "<span style='font-size:13px;'>[[title]] in [[category]]:<b>[[value]]</b> [[additional]]</span>",
                "dashLengthField": "dashLengthColumn",
                "fillAlphas": 1,
                "title": "Penjualan (x1000)",
                "type": "column",
                "valueField": "penjualan"
            }],
            "categoryField": "month",
            "categoryAxis": {
                "gridPosition": "start",
                "axisAlpha": 0,
                "tickLength": 0
            }
        });
        
        var url = "admin/get_penjualan_tahun?tahun="+tahun;
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

    var initChartWarna1 = function() {
        var chart = AmCharts.makeChart("chart_warna_1", {
            "theme": "light",
            "type": "serial",
            "startDuration": 2,

            "fontFamily": 'Open Sans',
            
            "color":    '#000',

            
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
                "tickLength": 0,
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

        // var warna = ['#FF0F00','#FF6600','#FF9E01','#FCD202','#F8FF01','#B0DE09','#04D215','#0D8ECF','#0D52D1','#2A0CD0'];
        var warna = ['#FF0F00','#FF6600','#FF9E01','#FCD202','#F8FF01','#B0DE09','#04D215','#0D8ECF','#0D52D1','#2A0CD0'];
        
        var url = "admin/get_barang_jual_warna_terbanyak?tahun="+tahun;
        var data_st  ={};
        var chartData = [];
        var j = 0;
        let idx=1;

        ajax_data_sync(url,data_st).done(function(data_respond /* ,textStatus, jqXHR */){
            // console.log(data_respond);
            $.each(JSON.parse(data_respond),function(k,v){
                chartData[j] = {
                    'barang': v.barang,
                    'qty': v.qty
                }

                j++;

            });
            chart.dataProvider = chartData;
            chart.validateData();
            doneLoading++;
            cekDoneLoading();
        });
        // console.log(chartData);

            
        
        $('#chart_warna_1').closest('.portlet').find('.fullscreen').click(function() {
            chart.invalidateSize();
        });
    }

    var initChartWarna2 = function() {
        var chart = AmCharts.makeChart("chart_warna_2", {
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
        var url = "admin/get_barang_jual_warna_terbanyak?tahun="+tahun;
        var data_st  ={};
        var chartData = [];
        var j = 0;

        ajax_data_sync(url,data_st).done(function(data_respond /* ,textStatus, jqXHR */){
            // console.log(data_respond);
            $.each(JSON.parse(data_respond),function(k,v){
                chartData[j] = {
                    "barang": v.barang,
                    "qty": v.qty
                }
                j++;

            });

            chart.dataProvider = chartData;
            chart.validateData();
            doneLoading++;
            cekDoneLoading();
        });

        $('#chart_warna_2').closest('.portlet').find('.fullscreen').click(function() {
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
                "tickLength": 0,
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
        
        var url = "admin/get_barang_jual_terbanyak?tahun="+tahun;
        var data_st  ={};
        var chartData = [];
        var j = 0;

        ajax_data_sync(url,data_st).done(function(data_respond /* ,textStatus, jqXHR */){
            // console.log(data_respond);
            $.each(JSON.parse(data_respond),function(k,v){
                chartData[j] = {
                    'barang': v.barang,
                    'qty': v.qty,
                }

                j++;

            });
            chart.dataProvider = chartData;
            chart.validateData();
            doneLoading++;
            cekDoneLoading();
        });
        // console.log(chartData);


            
        
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
        var url = "admin/get_barang_jual_terbanyak?tahun="+tahun;
        var data_st  ={};
        var chartData = [];
        var j = 0;

        ajax_data_sync(url,data_st).done(function(data_respond /* ,textStatus, jqXHR */){
            // console.log(data_respond);
            $.each(JSON.parse(data_respond),function(k,v){
                chartData[j] = {
                    "barang": v.barang,
                    "qty": v.qty
                }
                j++;

            });

            chart.dataProvider = chartData;
            chart.validateData();
            doneLoading++;
            cekDoneLoading();
        });

        $('#chart_3').closest('.portlet').find('.fullscreen').click(function() {
            chart.invalidateSize();
        });
    }

    var initChartBW = function() {
        var chart = AmCharts.makeChart("chart_bw", {
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
                "tickLength": 0,
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
        
        var url = "admin/get_barang_warna_jual_terbanyak?tahun="+tahun;
        var data_st  ={};
        var chartData = [];
        var j = 0;

        ajax_data_sync(url,data_st).done(function(data_respond /* ,textStatus, jqXHR */){
            // console.log(data_respond);
            $.each(JSON.parse(data_respond),function(k,v){
                chartData[j] = {
                    'barang': v.barang,
                    'qty': v.qty,
                }

                j++;

            });
            chart.dataProvider = chartData;
            chart.validateData();
            doneLoading++;
            cekDoneLoading();
        });
        // console.log(chartData);


            
        
        $('#chart_2').closest('.portlet').find('.fullscreen').click(function() {
            chart.invalidateSize();
        });
    }

    var initChartBWPie = function() {
        var chart = AmCharts.makeChart("chart_bw_pie", {
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
        var url = "admin/get_barang_warna_jual_terbanyak?tahun="+tahun;
        var data_st  ={};
        var chartData = [];
        var j = 0;

        ajax_data_sync(url,data_st).done(function(data_respond /* ,textStatus, jqXHR */){
            // console.log(data_respond);
            $.each(JSON.parse(data_respond),function(k,v){
                chartData[j] = {
                    "barang": v.barang,
                    "qty": v.qty
                }
                j++;

            });

            chart.dataProvider = chartData;
            chart.validateData();
            doneLoading++;
            cekDoneLoading();
        });

        $('#chart_3').closest('.portlet').find('.fullscreen').click(function() {
            chart.invalidateSize();
        });
    }

    var initChart4 = function() {
        var chart = AmCharts.makeChart("chart_4", {
            "theme": "light",
            "type": "serial",
            "startDuration": 2,

            "fontFamily": 'Open Sans',
            
            "color":    '#000',

            
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
                "valueField": "amount"
            }],
            "depth3D": 40,
            "angle": 30,
            "chartCursor": {
                "categoryBalloonEnabled": false,
                "cursorAlpha": 0,
                "zoomable": false
            },
            "categoryField": "nama_customer",
            "categoryAxis": {
                "gridPosition": "start",
                "axisAlpha": 0,
                "tickLength": 0,
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
        
        var url = "admin/get_customer_beli_terbanyak";
        var data_st  ={};
        data_st['tahun'] = tahun;
        data_st['tipe'] = 1;
        var chartData = [];
        var j = 0;

        ajax_data_sync(url,data_st).done(function(data_respond /* ,textStatus, jqXHR */){
            // console.log(data_respond);
            $.each(JSON.parse(data_respond),function(k,v){
                chartData[j] = {
                    'nama_customer': v.nama_customer,
                    'amount': v.amount
                }
                j++;
            });


            chart.dataProvider = chartData;
            chart.validateData();
            doneLoading++;
            cekDoneLoading();

            // generate_table_customer_all();


        });

        // console.log(chartData);    
        
        $('#chart_4').closest('.portlet').find('.fullscreen').click(function() {
            chart.invalidateSize();
        });

    }

    var initChart5 = function() {
        var chart = AmCharts.makeChart("chart_5", {
            "type": "pie",
            "theme": "light",

            "fontFamily": 'Open Sans',
            
            "color":    '#000',
            "bold": true,
            "valueField": "amount",
            "titleField": "nama_customer",
            "exportConfig": {
                menuItems: [{
                    icon: Metronic.getGlobalPluginsPath() + "amcharts/amcharts/images/export.png",
                    format: 'png'
                }]
            }
        });

        var warna = ['#FF0F00','#FF6600','#FF9E01','#FCD202','#F8FF01','#B0DE09','#04D215','#0D8ECF','#0D52D1','#2A0CD0'];
        var url = "admin/get_customer_beli_terbanyak";
        var data_st  ={};
        data_st['tahun'] = tahun;
        data_st['tipe'] = 1;
        var chartData = [];
        var j = 0;

        ajax_data_sync(url,data_st).done(function(data_respond /* ,textStatus, jqXHR */){
            // console.log(data_respond);
            $.each(JSON.parse(data_respond),function(k,v){
                chartData[j] = {
                    "nama_customer": v.nama_customer,
                    "amount": v.amount
                }
                j++;

            });

            chart.dataProvider = chartData;
            chart.validateData();
            doneLoading++;
            cekDoneLoading();

        });

        $('#chart_5').closest('.portlet').find('.fullscreen').click(function() {
            chart.invalidateSize();
        });
    }

    return {
        //main function to initiate the module

        init: function() {

            initChart1();
            initChart2();
            initChart3();
            initChart4();
            initChart5();
            initChartBW();
            initChartBWPie();
            initChartWarna1();
            initChartWarna2();
           
        }

    };

}();