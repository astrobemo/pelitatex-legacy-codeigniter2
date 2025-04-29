var ChartsAmcharts = function() {

    var initChart1 = function() {
        var chart = AmCharts.makeChart("chart_1", {
            "type": "serial",
            "theme": "light",
            "pathToImages": Metronic.getGlobalPluginsPath() + "amcharts/amcharts/images/",
            "autoMargins": false,
            "marginLeft": 80,
            "marginRight": 8,
            "marginTop": 10,
            "marginBottom": 100,

            "fontFamily": 'Open Sans',
            "color":    '#888',
            
            
            "valueAxes": [{
                "position": "left",
                "axisAlpha" : 0.15,
                "minimum" : 10000000,
                "maximum" : 1000000000,
                "dashLength" : 1,
        
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
            "categoryField": "warna",
            "categoryAxis": {
                "categoryAxis.dashLength":100,
                "categoryAxis.gridPosition": "start",
                "gridPosition": "start",
                "autoGridCount": "true",
                "gridPosition": "start", 
                "autoGridCount": "true",
                "labelRotation": -60,
                "minHorizontalGap": 0

            }
        });
        
        var barang_id = $('#barang_id_data').html();
        var tahun = $('#tahun_data').html();
        var url = "master/get_penjualan_by_barang_warna?barang_id="+barang_id+"&tahun="+tahun+"&tipe=0";
        var data_st  ={};
        var chartData = [];
        var j = 0;

        ajax_data_sync(url,data_st).done(function(data_respond /* ,textStatus, jqXHR */){
            // console.log(data_respond);
            $.each(JSON.parse(data_respond),function(k,v){
             chartData[j] = {
                'warna': v.nama_warna,
                'penjualan': v.penjualan
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

    var initChartPie1 = function() {
        var chart = AmCharts.makeChart("chart_pie_1", {
            "type": "pie",
            "theme": "light",

            "fontFamily": 'Open Sans',
            
            "color":    '#888',            
            "valueField": "penjualan",
            "titleField": "warna",
            "exportConfig": {
                menuItems: [{
                    icon: Metronic.getGlobalPluginsPath() + "amcharts/amcharts/images/export.png",
                    format: 'png'
                }]
            }
        });

        var barang_id = $('#barang_id_data').html();
        var tahun = $('#tahun_data').html();
        var warna = ['#FF0F00','#FF6600','#FF9E01','#FCD202','#F8FF01','#B0DE09','#04D215','#0D8ECF','#0D52D1','#2A0CD0'];
        var url = "master/get_penjualan_by_barang_warna?barang_id="+barang_id+"&tahun="+tahun;
        var data_st  ={};
        var chartData = [];
        var j = 0;
        var penjualan_other = 0;

        ajax_data_sync(url,data_st).done(function(data_respond /* ,textStatus, jqXHR */){
            // console.log(data_respond);
            $.each(JSON.parse(data_respond),function(k,v){
                if (k < 10) {
                    chartData[j] = {
                        "warna": v.nama_warna,
                        "penjualan": v.penjualan
                    }
                    j++;
                }else{
                    penjualan_other += parseInt(v.penjualan);
                }

            });
            chartData[j] = {
                "warna": "Lain2",
                "penjualan": penjualan_other
            }

            chart.dataProvider = chartData;
            chart.validateData();
        });

        $('#chart_pie_1').closest('.portlet').find('.fullscreen').click(function() {
            chart.invalidateSize();
        });
    }

    var initChart2 = function() {
        var chart = AmCharts.makeChart("chart_2", {
            "type": "serial",
            "theme": "light",
            "pathToImages": Metronic.getGlobalPluginsPath() + "amcharts/amcharts/images/",
            "autoMargins": false,
            "marginLeft": 80,
            "marginRight": 8,
            "marginTop": 10,
            "marginBottom": 100,

            "fontFamily": 'Open Sans',
            "color":    '#888',
            
            
            "valueAxes": [{
                "position": "left",
                "axisAlpha" : 0.15,
                "minimum" : 10000000,
                "maximum" : 1000000000,
                "dashLength" : 1,
        
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
            "categoryField": "customer",
            "categoryAxis": {
                "categoryAxis.dashLength":100,
                "categoryAxis.gridPosition": "start",
                "gridPosition": "start",
                "autoGridCount": "true",
                "gridPosition": "start", 
                "autoGridCount": "true",
                "labelRotation": -60,
                "minHorizontalGap": 0

            }
        });
        
        var barang_id = $('#barang_id_data').html();
        var tahun = $('#tahun_data').html();
        var url = "master/get_penjualan_by_barang_warna?barang_id="+barang_id+"&tahun="+tahun+"&tipe=1";
        var data_st  ={};
        var chartData = [];
        var j = 0;
        var penjualan_other = 0;

        ajax_data_sync(url,data_st).done(function(data_respond /* ,textStatus, jqXHR */){
            // console.log(data_respond);
            $.each(JSON.parse(data_respond),function(k,v){
                if (k<10) {
                    chartData[j] = {
                        'customer': v.nama_customer,
                        'penjualan': v.penjualan
                    }
                    j++;
                    
                }else{
                    penjualan_other += parseInt(v.penjualan);
                }

            });
            chartData[j] = {
                "customer": "Lain2",
                "penjualan": penjualan_other
            }

        chart.dataProvider = chartData;
        chart.validateData();

            
        });

        $('#chart_2').closest('.portlet').find('.fullscreen').click(function() {
            chart.invalidateSize();
        });
    }
   
    return {
        //main function to initiate the module

        init: function() {

            initChart1();
            initChart2();
            initChartPie1();
            // initChart3();
            // initChart4();
            // initChart5();

           
        }

    };

}();