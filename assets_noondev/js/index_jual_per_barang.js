var IndexBarang = function() {

    var dashboardMainChart = null;

    return {

        //main function
        init: function() {
            IndexBarang.initCharts();
        },

        initCharts: function() {
            if (Morris.EventEmitter) {
                // Use Morris.Area instead of Morris.Line
                dashboardMainChart = Morris.Area({
                    element: 'sales_statistics',
                    padding: 0,
                    behaveLikeLine: false,
                    gridEnabled: false,
                    gridLineColor: false,
                    axes: false,
                    fillOpacity: 1,
                    lineColors: ['#00e5ee', '#888'],
                    xkey: 'period',
                    ykeys: ['sales'],
                    labels: ['Sales'],
                    pointSize: 0,
                    lineWidth: 0,
                    hideHover: 'auto',
                    resize: true
                });

            }

            var barang_id = $('#barang_id_data').html();
            var warna_id = $('#warna_id').val();
            var tanggal_awal = $('#tanggal-awal').val();
            var tanggal_akhir = $('#tanggal-akhir').val();

            var url = "admin/get_penjualan_per_barang_bulan?barang_id="+barang_id+"&warna_id="+warna_id+"&tanggal_awal="+tanggal_awal+"&tanggal_akhir="+tanggal_akhir;
            var data_st  ={};
            var data_set = [];
            var i = 0;
            ajax_data_sync(url,data_st).done(function(data_respond /* ,textStatus, jqXHR */){
                // console.log(data_respond);
                $.each(JSON.parse(data_respond),function(k,v){
                    data_set[i] = {
                        'period': v.tanggal,
                        'sales': v.amount
                    }
                    i++;
                });

                dashboardMainChart.setData(data_set);

            });

        },

        redrawCharts: function() {
            dashboardMainChart.resizeHandler();
        },
    };

}();