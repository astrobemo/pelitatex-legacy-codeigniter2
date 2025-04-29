<link rel="stylesheet" type="text/css" href="<?= base_url('assets/css/map_style.css'); ?>" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css'); ?>" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css'); ?>" />
<link rel="stylesheet" type="text/css" href="<?= base_url('assets/global/plugins/bootstrap-colorpicker/css/colorpicker.css'); ?>" />

<div class="page-content">
    <div class='container'>

        <!-- list data  -->
        <div class="row margin-top-10">
            <div class="col-md-12">
                <div class="portlet light">
                    <!-- tab navigasi -->
                    <ul class="nav nav-tabs">
                        <li><a href="<?= base_url(is_setting_link('master/toko_list')); ?>/">Toko</a></li>
                        <li class="active"><a href="#">Rekening</a></li>
                        <li><a href="<?= base_url(is_setting_link('master/gudang_list')); ?>/">Gudang</a></li>
                    </ul>

                    <!-- filter data  -->
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
                                    <th scope="col" class="text-center">
                                        Nama Bank
                                    </th>
                                    <th scope="col" class="text-center">
                                        Nomor Rekening
                                    </th>
                                    <th scope="col" class="text-center">
                                        Giro
                                    </th>
                                    <th scope="col" class="text-center">
                                        Cek
                                    </th>
                                    <th scope="col" style="width:10px" class="text-center">
                                        Actions
                                    </th>
                                </tr>
                            </thead>

                            <tbody>
                                <? foreach ($bank_list as $row) { ?>

                                <tr class='status_aktif_<?= $row->status_aktif; ?>'>
                                    <td>
                                        <span class='id' hidden="hidden"><?= $row->id; ?></span>
                                        <span class='nama_bank'><?= $row->nama_bank; ?></span>
                                    </td>
                                    <td>
                                        <span class='no_rek_bank'><?= $row->no_rek_bank; ?></span>
                                    </td>
                                    <td style="background:<?= $row->tipe_trx_1; ?>"><span class='tipe_trx_1'><?= $row->tipe_trx_1 ?></span></td>
                                    <td style="background:<?= $row->tipe_trx_2; ?>"><span class='tipe_trx_2'><?= $row->tipe_trx_2 ?></span></td>

                                    <td>
                                        <span class='status_aktif' hidden='hidden'><?= $row->status_aktif; ?></span>
                                        <a href='#portlet-config-edit' data-toggle='modal' class="btn-xs btn green btn-edit"><i class="fa fa-edit"></i> </a>

                                        <?php if ($row->status_aktif == 1) { ?>

                                        <a class='btn-xs btn red btn-activate'><i class='fa fa-times'></i> </a>

                                        <?php } else { ?>

                                        <a class='btn-xs btn blue btn-activate'><i class='fa fa-play'></i> </a>

                                        <?php } ?>
                                    </td>
                                </tr>

                                <? } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- input data  -->
        <div class="modal fade" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <form action="<?= base_url('master/bank_list_insert') ?>" autocomplete="off" class="form-horizontal" id="form_add_data" method="post" onkeydown="return event.key != 'Enter';">
                            <h3 class='block'> Tambah </h3>

                            <div class="form-group">
                                <label class="control-label col-md-3">Nama Bank
                                    <span class="required">*</span>
                                </label>

                                <div class="col-md-6">
                                    <input type="text" class="form-control input1" name="nama_bank" />
                                    <span class="help-block"></span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3">Nomor Rekening
                                    <span class="required">*</span>
                                </label>

                                <div class="col-md-6">
                                    <input type="text" class="form-control " name="no_rek_bank" />
                                    <span class="help-block"></span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3">Warna Giro
                                    <span class="required">*</span>
                                </label>

                                <div class="col-md-6">
                                    <input id='tipe_trx_i1' name='tipe_trx_1' class="form-control colorpicker-default" />
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3">Warna Cek
                                    <span class="required"> </span>
                                </label>

                                <div class="col-md-6">
                                    <input name='tipe_trx_i2' name='tipe_trx_2' class="form-control colorpicker-default" />
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn blue btn-save btn-active ">Save</button>
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
                        <form action="<?= base_url('master/bank_list_update') ?>" autocomplete="off" class="form-horizontal" id="form_edit_data" method="post" onkeydown="return event.key != 'Enter';">
                            <h3 class='block'> Edit </h3>

                            <div class="form-group">
                                <label class="control-label col-md-3">Nama Bank
                                    <span class="required">*</span>
                                </label>

                                <div class="col-md-6">
                                    <input hidden='hidden' type="text" id="bank_id" name="bank_id" />
                                    <input type="text" class="form-control" name="nama_bank" />
                                    <span class="help-block"></span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3">Nomor Rekening
                                    <span class="required">*</span>
                                </label>

                                <div class="col-md-6">
                                    <input type="text" class="form-control input1" name="no_rek_bank" />
                                    <span class="help-block"></span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3">Warna Giro
                                    <span class="required">*</span>
                                </label>

                                <div class="col-md-6">
                                    <input id='tipe_trx_e1' name='tipe_trx_1' class="form-control colorpicker-default" />
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3">Warna Cek
                                    <span class="required"></span>
                                </label>

                                <div class="col-md-6">
                                    <input id='tipe_trx_e2' name='tipe_trx_2' class="form-control colorpicker-default" />
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn blue btn-edit-save btn-active">Save</button>
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

    $('#general_table').DataTable({
        "paging": true,
        "pageLength": 100
    });

    // $('.btn-close').click(function() {
    //     window.location.reload();
    // })

    //////////////////////////////////////////
    // save data 
    //////////////////////////////////////////
    $('.btn-save').click(function() {
        $('btn-save').text('saving...');
        $('btn-save').attr('disabled', true);

        $.ajax({
            url: "<?= base_url('master/bank_validate_start/new/0') ?>",
            type: "POST",
            data: $('#form_add_data').serialize(),
            dataType: "JSON",
            success: function(data) {
                if (data.status) {
                    $('#form_add_data').submit();
                    btn_disabled_load($(this));
                } else {
                    for (var i = 0; i < data.inputerror.length; i++) {
                        $('[name="' + data.inputerror[i] + '"]').parent().parent().addClass('has-error'); //select parent twice to select div form-group class and add has-error class
                        $('[name="' + data.inputerror[i] + '"]').next().text(data.error_string[i]); //select span help-block class set text error string
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

    //////////////////////////////////////////
    // edit data 
    //////////////////////////////////////////
    $('#general_table').on('click', '.btn-edit', function() {
        const ini = $(this).closest('tr');
        $('#form_edit_data [name=bank_id]').val(ini.find('.id').html());
        $('#form_edit_data [name=nama_bank]').val(ini.find('.nama_bank').html());
        $('#form_edit_data [name=no_rek_bank]').val(ini.find('.no_rek_bank').html());

        let color1 = ini.find('.tipe_trx_1').html();
        $('#tipe_trx_e1').val(color1);
        $('#tipe_trx_e1').colorpicker('setValue', color1);

        let color2 = ini.find('.tipe_trx_2').html();
        $('#tipe_trx_e2').val(color2);
        $('#tipe_trx_e2').colorpicker('setValue', color2);
    });

    $('.btn-edit-save').click(function() {
        //get id
        id = $('#form_edit_data [name=bank_id]').val();

        //validasi
        $('btn-save').text('saving...');
        $('btn-save').attr('disabled', true);

        $.ajax({
            url: "<?= base_url('master/bank_validate_start/edit/'); ?>" + '/' + id,
            type: "POST",
            data: $('#form_edit_data').serialize(),
            dataType: "JSON",
            success: function(data) {
                if (data.status) {
                    $('#form_edit_data').submit();
                    btn_disabled_load($(this));
                } else {
                    for (var i = 0; i < data.inputerror.length; i++) {
                        $('[name="' + data.inputerror[i] + '"]').parent().parent().addClass('has-error'); //select parent twice to select div form-group class and add has-error class
                        $('[name="' + data.inputerror[i] + '"]').next().text(data.error_string[i]); //select span help-block class set text error string
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

    //////////////////////////////////
    //tombol hapus
    //////////////////////////////////
    $('#general_table').on('click', '.btn-activate', function() {
        var data = status_aktif_get($(this).closest('tr')) + '=?=bank_list';
        var nama_bank = $(this).closest('tr').find('.nama_bank').html();
        var no_rek_bank = $(this).closest('tr').find('.no_rek_bank').html();
        var status = $(this).closest('tr').find('.status_aktif').html();

        if (status == 0) {
            var text = "mengaktifkan";
        } else {
            var text = "menonaktifkan";
        };

        bootbox.confirm("Yakin untuk " + text + " rekening <b>" + nama_bank + " nomor : " + no_rek_bank + "</b> ?", function(respond) {
            if (respond) {
                window.location.replace(baseurl + "master/ubah_status_aktif?data_sent=" + data + '&link=bank_list');
            };
        });
    });

    // color picker 
    $("#tipe_trx_i1").change(function() {
        var warna = $(this).val();

        if (warna == '') {
            warna = '#ffffff';
            $(this).val(warna);
        };

        $(this).css('background-color', warna);
    });

    $("#tipe_trx_i2").change(function() {
        var warna = $(this).val();

        if (warna == '') {
            warna = '#ffffff';
            $(this).val(warna);
        };

        $(this).css('background-color', warna);
    });

    $("#tipe_trx_e1").change(function() {
        var warna = $(this).val();

        if (warna == '') {
            warna = '#ffffff';
            $(this).val(warna);
        };

        $(this).css('background-color', warna);
    });

    $("#tipe_trx_e2").change(function() {
        var warna = $(this).val();

        if (warna == '') {
            warna = '#ffffff';
            $(this).val(warna);
        };

        $(this).css('background-color', warna);
    });
});
</script>