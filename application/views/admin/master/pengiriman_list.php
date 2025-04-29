<link rel="stylesheet" type="text/css" href="<?= base_url('assets/css/map_style.css'); ?>" />

<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css'); ?>" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css'); ?>" />

<div class="page-content">
    <div class='container'>
        <!-- list data  -->
        <div class="row margin-top-10">
            <div class="col-md-12">
                <div class="portlet light">
                    <ul class="nav nav-tabs">
                        <li><a href="<?= base_url(is_setting_link('master/customer_list')); ?>/">Customer</a></li>
                        <li class="active"><a href="#">Alamat Lain</a></li>
                    </ul>

                    <!-- filter data  -->
                    <div class="portlet-title">
                        <div class="caption caption-md">
                            <i class="icon-bar-chart theme-font hide"></i>
                            <span class="caption-subject theme-font bold uppercase">Customer : <?= $customer; ?></span>
                        </div>

                        <div class="actions">
                            <select class='btn btn-sm btn-default' id='status_aktif_select' name="status_aktif_select">
                                <option value="1" selected>Aktif</option>
                                <option value="0">Tidak Aktif</option>
                            </select>

                            <a href="#portlet-add" data-toggle='modal' class="btn btn-default btn-sm btn-form-add">
                                <i class="fa fa-plus"></i> Tambah
                            </a>
                        </div>
                    </div>

                    <div class="portlet-body">
                        <div id='main-table'>
                            <table class="table table-striped table-bordered table-hover" id="general_table">
                                <thead>
                                    <tr>
                                        <th scope="col" class="text-center">
                                            Alamat
                                        </th>
                                        <th scope="col" class="text-center">
                                            Catatan
                                        </th>
                                        <th scope="col" style="width:100px" class="text-center">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <? foreach ($alamat as $row) { ?>

                                    <tr class='status_aktif_<?= $row->status_aktif; ?>'>
                                        <td>
                                            <span class='id' hidden="hidden"><?= $row->id; ?></span>
                                            <span class='customer_id' hidden="hidden"><?= $row->customer_id; ?></span>
                                            <span class='alamat'><?= $row->alamat; ?></span>
                                        </td>
                                        <td>
                                            <span class='catatan'><?= $row->catatan; ?></span>
                                        </td>

                                        <td>
                                            <span class='status_aktif' hidden='hidden'><?= $row->status_aktif; ?></span>

                                            <a href='#portlet-edit' data-toggle='modal' class="btn-xs btn green btn-edit"><i class="fa fa-edit"></i> </a>

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
        </div>

        <!-- popup tambah data -->
        <div class="modal fade" id="portlet-add" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <form action="<?= base_url('master/pengiriman_list_insert') ?>" autocomplete='off' class="form-horizontal" id="form_add_data" method="post" onkeydown="return event.key != 'Enter';">
                            <h3 class='block'> Tambah </h3>

                            <div class="form-group">
                                <input type="hidden" class="form-control" name="customer_id" value="<?= $customer_id; ?>" />

                                <label class="control-label col-md-3">Alamat
                                    <span class="required">*</span>
                                </label>

                                <div class="col-md-6">
                                    <textarea type="text" class="form-control input1" name="alamat" ></textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3">Catatan
                                    <span class="required">*</span>
                                </label>

                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="catatan" />
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn blue btn-active btn-save">Save</button>
                        <button type="button" class="btn default btn-close" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- popup edit data -->
        <div class="modal fade" id="portlet-edit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <form action="<?= base_url('master/pengiriman_list_update') ?>" autocomplete='off' class="form-horizontal" id="form_edit_data" method="post" onkeydown="return event.key != 'Enter';">
                            <h3 class='block'> Edit </h3>

                            <div class="form-group">
                                <input type="hidden" class="form-control" name="id" />
                                <input type="hidden" class="form-control" name="customer_id" value="<?= $customer_id; ?>" />

                                <label class="control-label col-md-3">Alamat
                                    <span class="required">*</span>
                                </label>

                                <div class="col-md-6">
                                    <textarea type="text" class="form-control input1" name="alamat" ></textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3">Catatan
                                    <span class="required">*</span>
                                </label>

                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="catatan" />
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn blue btn-active btn-edit-save">Save</button>
                        <button type="button" class="btn default btn-close" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo base_url('assets/global/plugins/datatables/media/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets_noondev/js/table-advanced.js'); ?>"></script>
<script src="<?php echo base_url('assets/global/plugins/bootbox/bootbox.min.js'); ?>" type="text/javascript"></script>

<script>
jQuery(document).ready(function() {

    $('#general_table').DataTable({
        "paging": true,
        "pageLength": 100
    });

    $('.btn-save').click(function() {
        if ($('#form_add_data [name=alamat]').val() != '') {
            $('#form_add_data').submit();
            btn_disabled_load($(this));
        }
    });

    // $('.btn-close').click(function() {
    //     window.location.reload();
    // })

    $('#general_table').on('click', '.btn-edit', function() {
        $('#form_edit_data [name=id]').val($(this).closest('tr').find('.id').html());
        $('#form_edit_data [name=alamat]').val($(this).closest('tr').find('.alamat').html());
        $('#form_edit_data [name=catatan]').val($(this).closest('tr').find('.catatan').html());
    });

    $('.btn-edit-save').click(function() {
        if ($('#form_edit_data [name=alamat]').val() != '') {
            $('#form_edit_data').submit();
            btn_disabled_load($(this));
        }
    });

    $('#general_table').on('click', '.btn-activate', function() {
        var customer_id = $(this).closest('tr').find('.customer_id').html()
        var alamat = $(this).closest('tr').find('.alamat').html();
        var status = $(this).closest('tr').find('.status_aktif').html();

        if (status == 0) {
            var text = "mengaktifkan";
        } else {
            var text = "menonaktifkan";
        };

        var data = status_aktif_get($(this).closest('tr')) + '=?=customer_alamat_kirim=?=' + customer_id;

        bootbox.confirm("Yakin untuk " + text + " alamat <b>" + alamat + "</b> ?", function(respond) {
            if (respond) {
                window.location.replace(baseurl + "master/ubah_status_aktif_with_filter?data_sent=" + data + '&link=pengiriman_list');
            };
        });
    });
});
</script>