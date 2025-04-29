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
                        <li class="active"><a href="#">User</a></li>
                        <li><a href="<?= base_url(is_setting_link('master/printer_list')); ?>/">Printer</a></li>
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
                                        Username
                                    </th>
                                    <th scope="col" class="text-center">
                                        Posisi
                                    </th>
                                    <th scope="col" class="text-center">
                                        Jam Mulai
                                    </th>
                                    <th scope="col" class="text-center">
                                        Jam Selesai
                                    </th>
                                    <th scope="col" style="width:20px" class="text-center">
                                        Actions
                                    </th>
                                </tr>
                            </thead>

                            <tbody>
                                <? foreach ($user_list as $row) { ?>

                                <tr class='status_aktif_<?= $row->status_aktif; ?>'>
                                    <td>
                                        <span class='id' hidden="hidden"><?= $row->id; ?></span>
                                        <span class='username'><?= $row->username; ?></span>
                                    </td>
                                    <td>
                                        <span class='posisi_id' hidden="hidden"><?= $row->posisi_id; ?></span>
                                        <?= $row->posisi_name; ?>
                                    </td>
                                    <td>
                                        <span class='time_start'><?= date('H:i', strtotime($row->time_start)); ?></span>
                                    </td>
                                    <td>
                                        <span class='time_end'><?= date('H:i', strtotime($row->time_end)); ?></span>
                                    </td>
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
                        <form action="<?= base_url('master/user_list_insert') ?>" autocomplete='off' class="form-horizontal" id="form_add_data" method="post" onkeydown="return event.key != 'Enter';">
                            <h3 class='block'> Tambah </h3>

                            <div class="form-group">
                                <label class="control-label col-md-3">User Name
                                    <span class="required">*</span>
                                </label>

                                <div class="col-md-6">
                                    <input type="text" class="form-control input1" name="username" />
                                    <span class="help-block"></span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3">Password
                                    <span class="required">*</span>
                                </label>

                                <div class="col-md-6">
                                    <input type="password" class="form-control" name="password" />
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3">Posisi
                                    <span class="required">*</span>
                                </label>

                                <div class="col-md-6">
                                    <select class="form-control" name="posisi_id">
                                        <?if(is_posisi_id() == 1){?>
                                            <option value="1">Superadmin</option>
                                        <?}?>
                                        <?php foreach ($posisi_list as $row) { ?>
                                        <option value="<?= $row->id ?>"><?= $row->name; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3">Jam Kerja
                                    <span class="required">*</span>
                                </label>

                                <div class="col-md-6">
                                    <div class="input-group">
                                        <input name='time_start' type="text" class="form-control timepicker timepicker-24" value='07:30'>
                                        <span class="input-group-addon">to</span>
                                        <input name='time_end' type="text" class="form-control timepicker timepicker-24" value='17:30'>
                                    </div>
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

        <!-- update data -->
        <div class="modal fade" id="portlet-config-edit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <form action="<?= base_url('master/user_list_update') ?>" autocomplete='off' class="form-horizontal" id="form_edit_data" method="post" onkeydown="return event.key != 'Enter';">
                            <h3 class='block'> Edit </h3>

                            <div class="form-group">
                                <label class="control-label col-md-3">Username
                                    <span class="required">*</span>
                                </label>

                                <div class="col-md-6">
                                    <input hidden='hidden' type="text" name="user_id" />
                                    <input type="text" class="form-control" name="username" />
                                    <span class="help-block"></span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3">Password
                                    <span class="required">*</span>
                                </label>

                                <div class="col-md-6">
                                    <input type="password" class="form-control" name="password" />
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3">Posisi
                                    <span class="required">*</span>
                                </label>

                                <div class="col-md-6">
                                    <select class="form-control" name="posisi_id">
                                        <?if(is_posisi_id() == 1){?>
                                            <option value="1">Superadmin</option>
                                        <?}?>
                                        <?php foreach ($posisi_list as $row) { ?>
                                        <option value="<?= $row->id ?>"><?= $row->name; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3">Jam Kerja
                                    <span class="required">*</span>
                                </label>

                                <div class="col-md-6">
                                    <div class="input-group">
                                        <input name='time_start' type="text" class="form-control timepicker timepicker-24">
                                        <span class="input-group-addon"> to </span>
                                        <input name='time_end' type="text" class="form-control timepicker timepicker-24">
                                    </div>
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

<script src="<?php echo base_url('assets/global/plugins/jquery-validation/js/jquery.validate.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/jquery-validation/js/additional-methods.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets_noondev/js/nd_user_manage.js'); ?>"></script>
<script src="<?php echo base_url('assets/global/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/admin/pages/scripts/components-pickers.js'); ?>"></script>

<script src="<?php echo base_url('assets/global/plugins/datatables/media/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets_noondev/js/table-advanced.js'); ?>"></script>

<script src="<?php echo base_url('assets/global/plugins/bootbox/bootbox.min.js'); ?>" type="text/javascript"></script>

<script>
jQuery(document).ready(function() {

    FormAddUser.init();
    FormEditUser.init();
    ComponentsPickers.init();
    //TableAdvanced.init();

    $('#general_table').DataTable({
        "paging": true,
        "pageLength": 100
    });

    // $('.btn-close').click(function() {
    //     window.location.reload();
    // })

    /////////////////////////////////////////////////////
    // add data 
    /////////////////////////////////////////////////////
    $('.btn-save').click(function() {
        $('btn-save').text('saving...');
        $('btn-save').attr('disabled', true);

        $.ajax({
            url: "<?= base_url('master/user_validate_start/new/0') ?>",
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

    /////////////////////////////////////////////////////
    // edit data 
    /////////////////////////////////////////////////////
    $('#general_table').on('click', '.btn-edit', function() {
        const ini = $(this).closest('tr');
        $('#form_edit_data [name=user_id]').val(ini.find('.id').html());
        $('#form_edit_data [name=posisi_id]').val(ini.find('.posisi_id').html());
        $('#form_edit_data [name=username]').val(ini.find('.username').html());
        $('#form_edit_data [name=time_start]').val(ini.find('.time_start').html());
        $('#form_edit_data [name=time_end]').val(ini.find('.time_end').html());
    });

    $('.btn-edit-save').click(function() {
        //get id
        var id = $('#form_edit_data [name=user_id]').val();

        //validasi
        $('btn-save').text('saving...');
        $('btn-save').attr('disabled', true);

        $.ajax({
            url: "<?= base_url('master/user_validate_start/edit/'); ?> " + '/' + id,
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

    /////////////////////////////////////////////////////
    //tombol hapus
    /////////////////////////////////////////////////////
    $('#general_table').on('click', '.btn-activate', function() {
        var data = status_aktif_get($(this).closest('tr')) + '=?=user';
        var nama = $(this).closest('tr').find('.username').html();
        var status = $(this).closest('tr').find('.status_aktif').html();

        if (status == 0) {
            var text = "mengaktifkan";
        } else {
            var text = "menonaktifkan";
        };

        bootbox.confirm("Yakin untuk " + text + " user <b>" + nama + "</b> ?", function(respond) {
            if (respond) {
                window.location.replace(baseurl + "master/ubah_status_aktif?data_sent=" + data + '&link=user_list');
            };
        });
    });
});
</script>