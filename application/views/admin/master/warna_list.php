<link rel="stylesheet" type="text/css" href="<?= base_url('assets/css/map_style.css'); ?>" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css'); ?>" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css'); ?>" />
<link rel="stylesheet" type="text/css" href="<?= base_url('assets/global/plugins/bootstrap-colorpicker/css/colorpicker.css'); ?>" />

<div class="page-content">
    <div class='container'>
        <!-- list warna -->
        <div class="row margin-top-10">
            <div class="col-md-12">
                <div class="portlet light">
                    <!-- tab navigation -->
                    <ul class="nav nav-tabs">
                        <li><a href="<?= base_url(is_setting_link('master/barang_list')); ?>/">Barang</a></li>
                        <li><a href="<?= base_url(is_setting_link('master/satuan_list')); ?>/">Satuan</a></li>
                        <li class="active"><a href="#">Warna</a></li>
                    </ul>

                    <!-- filtering -->
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
                            <thead>
                                <tr>
                                    <th class='status_column text-center'>
                                        Status
                                    </th>
                                    <th scope="col" class="text-center">
                                        Warna Beli
                                    </th>
                                    <th scope="col" class="text-center">
                                        Warna Jual
                                    </th>
                                    <th scope="col" class="text-center">
                                        Kode Warna
                                    </th>
                                    <th scope="col" style="width:110px;" class="text-center">
                                        Actions
                                    </th>
                                </tr>
                            </thead>

                            <tbody>
                                <? foreach ($warna_list as $row) { ?>

                                <tr>
                                    <td class="status_column">
                                        <?= $row->status_aktif ?>
                                    </td>
                                    <td>
                                        <span class='warna_beli'><?= $row->warna_beli; ?></span>
                                    </td>
                                    <td>
                                        <span class='warna_jual'><?= $row->warna_jual; ?></span>
                                    </td>
                                    <td style='position:relative;;'>
                                        <div style="position:absolute; top:3px; left 0px; display:inline-block; width:30px; height:30px; border:1px solid #ddd; background:<?= $row->kode_warna; ?>"></div> <span style="padding-left:40px" class='kode_warna'><?= $row->kode_warna ?></span>
                                    </td>
                                    <td>
                                        <span class='status_aktif' hidden='hidden'><?= $row->status_aktif; ?></span>
                                        <span class='id' hidden><?= $row->id; ?></span>

                                        <a href='#portlet-config-edit' data-toggle='modal' class="btn-xs btn green btn-edit"><i class="fa fa-edit"></i></a>

                                        <?php if ($row->status_aktif == 1) { ?>

                                        <a class='btn-xs btn red btn-activate'><i class='fa fa-times'></i> </a>

                                        <?php } else { ?>

                                        <a class='btn-xs btn blue btn-activate'><i class='fa fa-play'></i> </a>

                                        <?php } ?>
                                    </td>
                                </tr>

                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- tambah data -->
        <div class="modal fade" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <form action="<?= base_url('master/warna_list_insert') ?>" autocomplete='off' class="form-horizontal" id="form_add_data" method="post" onkeydown="return event.key != 'Enter';">
                            <h3 class='block'> Tambah </h3>

                            <div class="form-group">
                                <label class="control-label col-md-3">Warna Beli
                                    <span class="required">*</span>
                                </label>

                                <div class="col-md-6">
                                    <input type="text" class="form-control input1" name="warna_beli" />
                                    <span class="help-block"></span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3">Warna Jual
                                    <span class="required">*</span>
                                </label>

                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="warna_jual" />
                                    <span class="help-block"></span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3">Kode Warna
                                    <span class="required">*</span>
                                </label>

                                <div class="col-md-6">
                                    <input id="kode_warna_1" class="form-control colorpicker-default" name="kode_warna" />
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn blue btn-save">Save</button>
                        <button type="button" class="btn default btn-close" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- edit data -->
        <div class="modal fade" id="portlet-config-edit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <form action="<?= base_url('master/warna_list_update') ?>" autocomplete='off' class="form-horizontal" id="form_edit_data" method="post" onkeydown="return event.key != 'Enter';">
                            <h3 class='block'> Edit </h3>

                            <div class="form-group">
                                <label class="control-label col-md-3">Warna Beli
                                    <span class="required">*</span>
                                </label>

                                <div class="col-md-6">
                                    <input hidden type="text" id="id" name="id" />
                                    <input type="text" class="form-control" name="warna_beli" />
                                    <span class="help-block"></span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3">Warna Jual
                                    <span class="required">*</span>
                                </label>

                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="warna_jual" />
                                    <span class="help-block"></span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3">Kode Warna
                                    <span class="required">*</span>
                                </label>

                                <div class="col-md-6">
                                    <input type="text" id="kode_warna_2" class="form-control colorpicker-default" name="kode_warna" />
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn blue btn-edit-save">Save</button>
                        <button type="button" class="btn default btn-close" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="<?= base_url('assets/global/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.js'); ?>"></script>
<script src="<?php echo base_url('assets/global/plugins/datatables/media/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets_noondev/js/table-advanced.js'); ?>"></script>
<script src="<?php echo base_url('assets_noondev/js/components-pickers.js'); ?>"></script>
<script src="<?php echo base_url('assets/global/plugins/bootbox/bootbox.min.js'); ?>" type="text/javascript"></script>

<script>
jQuery(document).ready(function() {

    ComponentsPickers.init();

    //////////////////////////////////////////
    // table 
    //////////////////////////////////////////
    $('#general_table').DataTable({
        "paging": true,
        "pageLength": 100
    });

    var oTable;
    oTable = $('#general_table').dataTable();
    oTable.fnFilter(1, 0);

    $('#status_aktif_select').change(function() {
        oTable.fnFilter($(this).val(), 0);
    });

    // $('.btn-close').click(function() {
    //     window.location.reload();
    // })

    ///////////////////////////////////
    // save data 
    ///////////////////////////////////
    $('.btn-save').click(function() {
        $('btn-save').text('saving...');
        $('btn-save').attr('disabled', true);

        $.ajax({
            url: "<?= base_url('master/warna_validate_start/new/0') ?>",
            type: "POST",
            data: $('#form_add_data').serialize(),
            dataType: "JSON",
            success: function(data) {
                if (data.status) {
                    $('#form_add_data').submit();
                    btn_disabled_load($(this));
                } else {
                    for (var i = 0; i < data.inputerror.length; i++) {
                        $('[name="' + data.inputerror[i] + '"]').parent().parent().addClass('has-error');
                        $('[name="' + data.inputerror[i] + '"]').next().text(data.error_string[i]);
                    }
                }

                $('.btn-save').text('save');
                $('.btn-save').attr('disabled', false);

            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('Error penyimpanan data!');
                $('.btn-save').text('save');
                $('.btn-save').attr('disabled', false);
            }
        });
    });

    ////////////////////////////////////////////////////////
    // edit data 
    ////////////////////////////////////////////////////////
    $('#general_table').on('click', '.btn-edit', function() {
        const ini = $(this).closest('tr');
        $('#form_edit_data [name=id]').val(ini.find('.id').html());
        $('#form_edit_data [name=warna_beli]').val(ini.find('.warna_beli').html());
        $('#form_edit_data [name=warna_jual]').val(ini.find('.warna_jual').html());

        let color2 = ini.find('.kode_warna').html();
        $('#kode_warna_2').val(color2);
        $('#kode_warna_2').colorpicker('setValue', color2);
    });

    $('.btn-edit-save').click(function() {
        //get id
        id = $('#form_edit_data [name=id]').val();

        //validasi
        $('btn-save').text('saving...');
        $('btn-save').attr('disabled', true);

        $.ajax({
            url: "<?= base_url('master/warna_validate_start/edit/'); ?>" + '/' + id,
            type: "POST",
            data: $('#form_edit_data').serialize(),
            dataType: "JSON",
            success: function(data) {
                if (data.status) {
                    $('#form_edit_data').submit();
                    btn_disabled_load($(this));
                } else {
                    for (var i = 0; i < data.inputerror.length; i++) {
                        $('[name="' + data.inputerror[i] + '"]').parent().parent().addClass('has-error');
                        $('[name="' + data.inputerror[i] + '"]').next().text(data.error_string[i]);
                    }
                }

                $('.btn-save').text('save');
                $('.btn-save').attr('disabled', false);

            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('Error penyimpanan data!');
                $('.btn-save').text('save');
                $('.btn-save').attr('disabled', false);
            }
        });
    });

    //////////////////////////////////////////////////////////
    // nonaktifkan data 
    //////////////////////////////////////////////////////////
    $('#general_table').on('click', '.btn-activate', function() {
        var data = status_aktif_get($(this).closest('tr')) + '=?=warna';
        var nama = $(this).closest('tr').find('.warna_beli').html();
        var status = $(this).closest('tr').find('.status_aktif').html();

        if (status == 0) {
            var text = "mengaktifkan";
        } else {
            var text = "menonaktifkan";
        };

        bootbox.confirm("Yakin untuk " + text + " warna <b>" + nama + "</b> ?", function(respond) {
            if (respond) {
                window.location.replace(baseurl + "master/ubah_status_aktif?data_sent=" + data + '&link=warna_list');
            };
        });
    });

    ////////////////////////////////////
    // color picker 
    ////////////////////////////////////
    $("#kode_warna_1").change(function() {
        var warna = $(this).val();

        if (warna == '') {
            warna = '#ffffff';
            $(this).val(warna);
        };

        $(this).css('background-color', warna);
    });

    $("#kode_warna_2").change(function() {
        var warna = $(this).val();

        if (warna == '') {
            warna = '#ffffff';
            $(this).val(warna);
        };

        $(this).css('background-color', warna);
    });
});
</script>