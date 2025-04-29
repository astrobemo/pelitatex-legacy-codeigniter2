<link rel="stylesheet" type="text/css" href="<?= base_url('assets/css/map_style.css'); ?>" />

<div class="page-content">
    <div class='container'>
        <!-- update form  -->
        <div class="row margin-top-10">
            <div class="col-md-12">
                <div class="portlet light">
                    <!-- filter data  -->
                    <!-- tab navigation -->
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#">Toko</a></li>
                        <li><a href="<?= base_url(is_setting_link('master/bank_list')); ?>/">Rekening</a></li>
                        <li><a href="<?= base_url(is_setting_link('master/gudang_list')); ?>/">Gudang</a></li>
                    </ul>

                    <!-- title -->
                    <div class="portlet-title">
                        <div class="caption caption-md">
                            <i class="icon-bar-chart theme-font hide"></i>
                            <span class="caption-subject theme-font bold uppercase">DATA TOKO</span>
                        </div>
                    </div>

                    <div class="portlet-body">
                        <!-- form update  -->
                        <form action="<?= base_url('master/toko_list_update') ?>" class="form-horizontal" id="form_add_data" method="post">

                            <? foreach ($toko_list as $row) { ?>

                            <div class="form-group">
                                <label class="control-label col-md-3">Nama
                                    <span class="required">*</span>
                                </label>

                                <div class="col-md-6">
                                    <input type="hidden" class="form-control" id="id" name="id" autocomplete="off" value="<?= set_value('id', $row->id); ?>" />
                                    <input type="text" class="form-control" id="nama" name="nama" autocomplete="off" value="<?= set_value('nama', $row->nama); ?>" />
                                </div>
                            </div>

                            <div class=" form-group">
                                <label class="control-label col-md-3">Pre Faktur</label>

                                <div class="col-md-6">
                                    <input type="text" class="form-control" maxlength='2' name="pre_faktur" autocomplete="off" value="<?= set_value('pre_faktur', $row->pre_faktur); ?>" />
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3">Kode Toko</label>

                                <div class="col-md-6">
                                    <input type="text" class="form-control" maxlength='2' name="pre_po" autocomplete="off" value="<?= set_value('pre_po', $row->pre_po); ?>" />
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3">Alamat</label>

                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="alamat" autocomplete="off" value="<?= set_value('alamat', $row->alamat); ?>" />
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3">Telepon</label>

                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="telepon" autocomplete="off" value="<?= set_value('telepon', $row->telepon); ?>" />
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3">Fax</label>

                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="fax" autocomplete="off" value="<?= set_value('fax', $row->fax); ?>" />
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3">Kota</label>

                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="kota" autocomplete="off" value="<?= set_value('kota', $row->kota); ?>" />
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3">Kode Pos</label>

                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="kode_pos" autocomplete="off" value="<?= set_value('kode_pos', $row->kode_pos); ?>" />
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3">NPWP</label>

                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="NPWP" autocomplete="off" value="<?= set_value('NPWP', $row->NPWP); ?>" />
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3"></label>

                                <div class="col-md-6">
                                    <a href="#portlet-config" data-toggle='modal' class="btn btn-primary">
                                        Save
                                    </a>
                                </div>
                            </div>

                            <?php } ?>

                            <!-- popup confirm pin -->
                            <div class="modal fade" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-body">
                                            <span id="success_message"></span>

                                            <h3 class='block'> Enter Pin </h3>

                                            <div class="form-group">
                                                <label class="control-label col-md-3">Your Pin here
                                                    <span class="required">*</span>
                                                </label>

                                                <div class="col-md-6">
                                                    <input type="text" class="form-control input1" id="pin" name="pin" />
                                                    <span class="help-block"></span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="btn blue btn-active btn-save">Ok</button>
                                            <button type="button" class="btn default btn-close" data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
jQuery(document).ready(function() {
    $('#nama').focus();
});

////////////////////////////////////////////////
// check pin 
////////////////////////////////////////////////
$('.btn-save').click(function() {
    $('btn-save').text('checking...');
    $('btn-save').attr('disabled', true);

    $.ajax({
        url: "<?= base_url('master/pin_validate_start') ?>",
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

            $('.btn-save').text('Ok');
            $('.btn-save').attr('disabled', false);

        },
        error: function(jqXHR, textStatus, errorThrown) {
            alert('Error confirm pin!');
            $('.btn-save').text('Ok');
            $('.btn-save').attr('disabled', false);
        }
    });
});
</script>