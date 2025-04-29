<link rel="stylesheet" type="text/css" href="<?= base_url('assets/css/map_style.css'); ?>" />

<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css'); ?>" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css'); ?>" />

<div class="page-content">
    <div class='container'>

        <!-- list data  -->
        <div class="row margin-top-10">
            <div class="col-md-12">
                <div class="portlet light">
                    <!-- tab navigation  -->
                    <ul class="nav nav-tabs">
                        <li><a href="<?= base_url(is_setting_link('master/toko_list')); ?>/">Toko</a></li>
                        <li><a href="<?= base_url(is_setting_link('master/bank_list')); ?>/">Rekening</a></li>
                        <li class="active"><a href="#">Gudang</a></li>
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
                                    <th scope="col" class='status_column text-center'>
                                        Status
                                    </th>
                                    <th scope="col" class="text-center">
                                        Nama
                                    </th>
                                    <th scope="col" class="text-center">
                                        Lokasi
                                    </th>
                                    <th scope="col" class="text-center">
                                        Default
                                    </th>
                                    <th scope="col" class="text-center">
                                        Visible
                                    </th>
                                    <th scope="col" class="text-center">
                                        Urutan
                                    </th>
                                    <th scope="col" style="width:10px" class="text-center">
                                        Actions
                                    </th>
                                </tr>
                            </thead>

                            <tbody>
                                <? foreach ($gudang_list as $row) { ?>

                                <tr class='status_aktif_<?= $row->status_aktif; ?>'>
                                    <td class='status_column'>
                                        <span class='status_aktif' hidden='hidden'><?= $row->status_aktif; ?></span>
                                    </td>
                                    <td>
                                        <span class='id' hidden="hidden"><?= $row->id; ?></span>
                                        <span class='nama'><?= $row->nama; ?></span>
                                    </td>
                                    <td>
                                        <span class='lokasi'><?= $row->lokasi; ?></span>
                                    </td>

                                    <?php $color_status_default = $row->status_default == '0' ? '#ffffff' : '#9bfccf'; ?>

                                    <td class="text-center" bgcolor="<?= $color_status_default; ?>">
                                        <span class='status_defaultX'><?= $row->status_default == 1 ? 'Ya' : ''; ?></span>
                                        <span class='status_default' hidden="hidden"><?= $row->status_default; ?></span>
                                    </td>

                                    <?php $color_visible = $row->visible == '0' ? '#ffffff' : '#f5e8d4'; ?>

                                    <td class="text-center" bgcolor="<?= $color_visible; ?>">
                                        <span class='visibleX'><?= $row->visible == '1' ? 'Ya' : ''; ?></span>
                                        <span class='visible' hidden="hidden"><?= $row->visible; ?></span>
                                    </td>
                                    <td class="text-center">
                                        <span class='urutan'><?= $row->urutan; ?></span>
                                    </td>
                                    <td>
                                        <a href='#portlet-config-edit' data-toggle='modal' class="btn-xs btn green btn-edit"><i class="fa fa-edit"></i></a>

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
                        <form action="<?= base_url('master/gudang_list_insert') ?>" class="form-horizontal" id="form_add_data" method="post" onkeydown="return event.key != 'Enter';">
                            <h3 class='block'> Tambah </h3>

                            <div class="form-group">
                                <label class="control-label col-md-3">Nama
                                    <span class="required">*</span>
                                </label>

                                <div class="col-md-6">
                                    <input type="text" class="form-control input1" name="nama" autocomplete='off' />
                                    <span class="help-block"></span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3">Lokasi</label>

                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="lokasi" autocomplete='off' />
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3">Status Default</label>

                                <div class="col-md-9">
                                    <div class="radio-list">
                                        <label class="radio-inline">
                                            <input type="radio" name="status_default" value="1"> Ya
                                        </label>

                                        <label class="radio-inline">
                                            <input type="radio" name="status_default" value="0" checked> Tidak
                                        </label>
                                    </div>

                                    <div class="alert alert-success">
                                        <strong>Ya : </strong> Menjadikan gudang ini sebagai gudang utama. Gudang yang lainnya akan berstatus Tidak Default.
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3">Visible</label>

                                <div class="col-md-9">
                                    <div class="radio-list">
                                        <label class="radio-inline">
                                            <input type="radio" name="visible" value="1" checked> Ya
                                        </label>

                                        <label class="radio-inline">
                                            <input type="radio" name="visible" value="0"> Tidak
                                        </label>
                                    </div>

                                    <div class="alert alert-warning">
                                        <strong>Ya : </strong> Menjadikan gudang ini dapat dilihat
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3">Urutan</label>

                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="urutan" autocomplete='off' />
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

        <!-- edit data  -->
        <div class="modal fade" id="portlet-config-edit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <form action="<?= base_url('master/gudang_list_update') ?>" class="form-horizontal" id="form_edit_data" method="post" onkeydown="return event.key != 'Enter';">
                            <h3 class='block'> Edit </h3>

                            <div class="form-group">
                                <label class="control-label col-md-3">Nama
                                    <span class="required">*</span>
                                </label>

                                <div class="col-md-6">
                                    <input id="gudang_list_id" name="gudang_list_id" hidden='hidden' />
                                    <input type="text" class="form-control input1" name="nama" autocomplete='off' />
                                    <span class="help-block"></span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3">Lokasi</label>

                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="lokasi" autocomplete='off' />
                                    <span class="help-block"></span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3">Status Default</label>

                                <div class="col-md-9">
                                    <div class="radio-list">
                                        <label class="radio-inline">
                                            <input type="radio" name="status_default" value="1"> Ya
                                        </label>

                                        <label class="radio-inline">
                                            <input type="radio" name="status_default" value="0"> Tidak
                                        </label>
                                    </div>

                                    <div class="alert alert-success">
                                        <strong>Ya : </strong> Menjadikan gudang ini sebagai gudang utama. Gudang yang lainnya akan berstatus Tidak Default.
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3">Visible</label>

                                <div class="col-md-9">
                                    <div class="radio-list">
                                        <label class="radio-inline">
                                            <input type="radio" name="visible" value="1"> Ya
                                        </label>

                                        <label class="radio-inline">
                                            <input type="radio" name="visible" value="0"> Tidak
                                        </label>
                                    </div>

                                    <div class="alert alert-warning">
                                        <strong>Ya : </strong> Menjadikan gudang ini dapat dilihat
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3">Urutan</label>

                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="urutan" autocomplete='off' />
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

<script type="text/javascript" src="<?php echo base_url('assets/global/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.js'); ?>"></script>
<script src="<?php echo base_url('assets/global/plugins/datatables/media/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets_noondev/js/table-advanced.js'); ?>"></script>
<script src="<?php echo base_url('assets_noondev/js/components-pickers.js'); ?>"></script>
<script src="<?php echo base_url('assets/global/plugins/bootbox/bootbox.min.js'); ?>" type="text/javascript"></script>

<script>
jQuery(document).ready(function() {
    $('#general_table').DataTable({
        "paging": true,
        "pageLength": 100
    });

    // $('.btn-close').click(function() {
    //     window.location.reload();
    // })

    $('#general_table').on('click', '.btn-edit', function() {
        const ini = $(this).closest('tr');
        $('#form_edit_data [name=gudang_list_id]').val(ini.find('.id').html());
        $('#form_edit_data [name=nama]').val(ini.find('.nama').html());
        $('#form_edit_data [name=lokasi]').val(ini.find('.lokasi').html());
        $('#form_edit_data [name=urutan]').val(ini.find('.urutan').html());

        var form = "#form_edit_data";
        var status_default = ini.find('.status_default').html();
        var visible = ini.find('.visible').html();

        $(form + " [name=status_default][value='" + status_default + "']").prop('checked', true);
        $.uniform.update($(form).find("[name=status_default]"));
        $(form + " [name=visible][value='" + visible + "']").prop('checked', true);
        $.uniform.update($(form).find("[name=visible]"));
    });

    $('.btn-save').click(function() {
        $('btn-save').text('saving...');
        $('btn-save').attr('disabled', true);

        $.ajax({
            url: "<?=base_url('master/gudang_validate_start/new/0') ?>",
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

    $('.btn-edit-save').click(function() {
        //get id
        id = $('#form_edit_data [name=gudang_list_id]').val();

        //validasi
        $('btn-save').text('saving...');
        $('btn-save').attr('disabled', true);

        $.ajax({
            url: "<?= base_url('master/gudang_validate_start/edit/'); ?>" + '/' + id,
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

    //tombol hapus
    $('#general_table').on('click', '.btn-activate', function() {
        var data = status_aktif_get($(this).closest('tr')) + '=?=gudang';
        var nama = $(this).closest('tr').find('.nama').html();
        var status = $(this).closest('tr').find('.status_aktif').html();

        if (status == 0) {
            var text = "mengaktifkan";
        } else {
            var text = "menonaktifkan";
        };

        bootbox.confirm("Yakin untuk " + text + " gudang <b>" + nama + "</b> ?", function(respond) {
            if (respond) {
                window.location.replace(baseurl + "master/ubah_status_aktif?data_sent=" + data + '&link=gudang_list');
            };
        });
    });

});
</script>