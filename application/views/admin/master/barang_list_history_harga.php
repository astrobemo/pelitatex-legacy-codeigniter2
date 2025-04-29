<link rel="stylesheet" type="text/css" href="<?= base_url('assets/css/map_style.css'); ?>" />

<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css'); ?>" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css'); ?>" />

<div class="page-content">
    <div class='container'>
        <!-- list data -->
        <div class="row margin-top-10">
            <div class="col-md-12">
                <div class="portlet light">
                    <!-- tab -->
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#">Barang</a></li>
                        <li><a href="<?= base_url(is_setting_link('master/satuan_list')); ?>/">Satuan</a></li>

                        <li><a href="<?= base_url(is_setting_link('master/warna_list')); ?>/">Warna</a></li>
                    </ul>

                    <!-- judul dan filtering -->
                    <div class="portlet-title">
                        <div class="caption caption-md">
                            <i class="icon-bar-chart theme-font hide"></i>
                            <span class="caption-subject theme-font bold uppercase"><?= $breadcrumb_small; ?></span>
                        </div>

                        <div class="actions">
                            <select class='btn btn-sm btn-default' name='status_aktif_select' id='status_aktif_select'>
                                <option value="1" selected>Aktif</option>
                                <option value="0">Tidak Aktif</option>
                            </select>

                            <a href="#portlet-config" data-toggle='modal' class="btn btn-default btn-sm btn-form-add">
                                <i class="fa fa-plus"></i> Tambah
                            </a>
                        </div>
                    </div>

                    <div class="portlet-body">
                        <table class="table table-striped table-bordered table-hover" id="general_table">
                            <!-- header table -->
                            <thead>
                                <tr>
                                    <th scope="col" class='status_column text-center'>
                                        Status Aktif
                                    </th>
                                    <th scope="col" class="text-center">
                                        Nama Beli
                                    </th>
                                    <th scope="col" class="text-center">
                                        Nama Jual
                                    </th>
                                    <th scope="col" class="text-center">
                                        Satuan
                                    </th>
                                    <th scope="col" class="text-center">
                                        Harga Jual
                                    </th>
                                    <th scope="col" class="text-center">
                                        Harga Beli
                                    </th>
                                    <th scope="col" style="min-width:150px !important" class="text-center">
                                        Actions
                                    </th>
                                </tr>
                            </thead>

                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo base_url('assets/global/plugins/datatables/media/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/bootbox/bootbox.min.js'); ?>" type="text/javascript"></script>

<script>
var harga_history;
jQuery(document).ready(function() {

    oTable = $('#general_table').DataTable();
    oTable.state.clear();
    oTable.destroy();
    

});

function get_harga_history(){
    var data_st = {};
    var url = "master/get_harga_history";
    ajax_data_sync(url,data_st).done(function(data_respond  /*,textStatus, jqXHR*/ ){
        console.log(data_respond)
    });
}
</script>