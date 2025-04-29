<link rel="stylesheet" type="text/css" href="<?= base_url('assets/css/map_style.css'); ?>" />

<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css'); ?>" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css'); ?>" />

<div class="page-content">
    <div class='container'>
        <!-- list data -->
        <div class="row margin-top-10">
            <div class="col-md-12">
                <div class="portlet light">
                    <!-- filter data -->
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
                                <i class="fa fa-plus"></i> Tambah </a>
                        </div>
                    </div>

                    <div class="portlet-body">
                        <table class="table table-striped table-bordered table-hover" id="general_table">
                            <thead>
                                <tr>
                                    <th scope="col" class="text-center">
                                        Tipe
                                    </th>
                                    <th scope="col" class="text-center">
                                        Nama
                                    </th>
                                    <th scope="col" class="text-center">
                                        Kode
                                    </th>
                                    <th scope="col" class="text-center">
                                        Alamat
                                    </th>
                                    <th scope="col" class="text-center">
                                        Telepon
                                    </th>

                                    <th scope="col" class="text-center">
                                        Bank
                                    </th>
                                    <th scope="col" style="width: 2px" class="text-center">
                                        Actions
                                    </th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php foreach ($supplier_list as $row) { ?>

                                <tr class='status_aktif_<?= $row->status_aktif; ?>' style="background:<?= get_supplier_color($row->kode); ?>">
                                    <td>
                                        <span class='id' hidden="hidden"><?= $row->id; ?></span>
                                        <span class='id'><?= $row->tipe_supplier == 1 ? 'Kain' : 'Lain - Lain'; ?></span>
                                    </td>
                                    <td>
                                        <span class='nama'><?= $row->nama; ?></span>
                                    </td>
                                    <td>
                                        <span class='kode'><?= $row->kode; ?></span>
                                    </td>
                                    <td>
                                        <span class='alamat'><?= nl2br($row->alamat); ?></span> <?= ($row->alamat != '' ? '<br/>' : ''); ?>
                                        <span class='kota'><?= $row->kota; ?></span>
                                    </td>
                                    <td>
                                        <span class='telepon'><?= $row->telepon; ?></span>
                                    </td>
                                    <td>
                                        Bank : <span class='nama_bank'><?= $row->nama_bank; ?></span> <br />
                                        No Rek : <span class='no_rek_bank'><?= $row->no_rek_bank; ?></span>
                                    </td>
                                    <td>
                                        <span class='id' hidden><?= $row->id; ?></span>
                                        <span class='tipe_supplier' hidden><?= $row->tipe_supplier; ?></span>
                                        <span class='status_aktif' hidden><?= $row->status_aktif; ?></span>
                                        <span hidden class='kode_pos'><?= $row->kode_pos; ?></span>
                                        <span hidden class='email'><?= $row->email; ?></span>
                                        <span hidden class='fax'><?= $row->fax; ?></span>
                                        <span hidden class='website'><?= $row->website; ?></span>

                                        <a href='#portlet-config-edit' data-toggle='modal' class="btn-xs btn green btn-edit"><i class="fa fa-edit"></i> </a>

                                        <?php if ($row->status_aktif == 1) { ?>

                                        <a class='btn-xs btn red btn-remove'><i class='fa fa-times'></i> </a>

                                        <?php } else { ?>

                                        <a class='btn-xs btn blue btn-remove'><i class='fa fa-play'></i> </a>

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

        <!-- popup tambah -->
        <div class="modal fade bs-modal-lg" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-body">
                        <form action="<?= base_url('master/supplier_list_insert') ?>" autocomplete="off" class="form-horizontal" id="form_add_data" method="post" onkeydown="return event.key != 'Enter';">
                            <h3> Tambah </h3>

                            <hr />

                            <table style="width:90%">
                                <tr>
                                    <td>
                                        <div class="form-group">
                                            <label class="control-label col-md-4">Tipe</label>

                                            <div class="radio-list">
                                                <label class="radio-inline">
                                                    <input type="radio" name="tipe_supplier" value="1" checked> Kain
                                                </label>

                                                <label class="radio-inline">
                                                    <input type="radio" name="tipe_supplier" value="2"> Lain-lain
                                                </label>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-md-4">Nama</label>

                                            <div class="col-md-8">
                                                <input type="text" class="form-control input1" name="nama" autocomplete='off' />
                                                <span class="help-block"></span>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-md-4">Kode</label>

                                            <div class="col-md-8">
                                                <input type="text" class="form-control" name="kode" id="kode-add" autocomplete='off' />
                                                <span class="help-block"></span>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-md-4">Alamat</label>

                                            <div class="col-md-8">
                                                <textarea class="form-control" name="alamat"></textarea>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-md-4">Kota</label>

                                            <div class="col-md-8">
                                                <input type="text" class="form-control" name="kota" autocomplete='off' />
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-md-4">Kode Pos</label>

                                            <div class="col-md-8">
                                                <input type="text" class="form-control" name="kode_pos" autocomplete='off' />
                                            </div>
                                        </div>
                                    </td>

                                    <td>
                                        <div class="form-group">
                                            <label class="control-label col-md-4">Telepon</label>

                                            <div class="col-md-8">
                                                <input type="text" class="form-control" name="telepon" autocomplete='off' />
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-md-4">Fax</label>

                                            <div class="col-md-8">
                                                <input type="text" class="form-control" name="fax" autocomplete='off' />
                                            </div>

                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-md-4">Bank</label>

                                            <div class="col-md-8">
                                                <input type="text" class="form-control" name="nama_bank" autocomplete='off' />
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-md-4">Nomor Rekening</label>

                                            <div class="col-md-8">
                                                <input type="text" class="form-control" name="no_rek_bank" />
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-md-4">Email</label>

                                            <div class="col-md-8">
                                                <input type="text" class="form-control" name="email" />
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-md-4">Website</label>

                                            <div class="col-md-8">
                                                <input type="text" class="form-control" name="website" />
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </table>
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
        <div class="modal fade bs-modal-lg" id="portlet-config-edit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-body">
                        <form action="<?= base_url('master/supplier_list_update') ?>" autocomplete='off' class="form-horizontal" id="form_edit_data" method="post" onkeydown="return event.key != 'Enter';">
                            <h3 class='block'> Edit </h3>
                            <hr />
                            <table style="width:90%">
                                <tr>
                                    <td>
                                        <div class="form-group">
                                            <label class="control-label col-md-4">Tipe</label>

                                            <div class="radio-list">
                                                <label class="radio-inline">
                                                    <input type="radio" name="tipe_supplier" value="1"> Kain
                                                </label>

                                                <label class="radio-inline">
                                                    <input type="radio" name="tipe_supplier" value="2"> Lain-lain
                                                </label>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-md-4">Nama</label>

                                            <div class="col-md-8">
                                                <input hidden name="supplier_id" />
                                                <input type="text" class="form-control input1" name="nama" />
                                                <span class="help-block"></span>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-md-4">Kode</label>

                                            <div class="col-md-8">
                                                <input type="text" class="form-control" name="kode" id="kode-edit" />
                                                <span class="help-block"></span>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-md-4">Alamat</label>

                                            <div class="col-md-8">
                                                <textarea class="form-control" name="alamat"></textarea>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-md-4">Kota</label>

                                            <div class="col-md-8">
                                                <input type="text" class="form-control" name="kota" />
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-md-4">Kode Pos</label>

                                            <div class="col-md-8">
                                                <input type="text" class="form-control" name="kode_pos" />
                                            </div>
                                        </div>
                                    </td>

                                    <td>
                                        <div class="form-group">
                                            <label class="control-label col-md-4">Telepon</label>

                                            <div class="col-md-8">
                                                <input type="text" class="form-control" name="telepon" />
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-md-4">Fax</label>

                                            <div class="col-md-8">
                                                <input type="text" class="form-control" name="fax" />
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-md-4">Bank</label>

                                            <div class="col-md-8">
                                                <input type="text" class="form-control" name="nama_bank" />
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-md-4">Nomor Rekening</label>

                                            <div class="col-md-8">
                                                <input type="text" class="form-control" name="no_rek_bank" />
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-md-4">Email</label>

                                            <div class="col-md-8">
                                                <input type="text" class="form-control" name="email" />
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-md-4">Website</label>

                                            <div class="col-md-8">
                                                <input type="text" class="form-control" name="website" />
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </table>
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

<script src="<?php echo base_url('assets/global/plugins/datatables/media/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets_noondev/js/table-advanced.js'); ?>"></script>
<script src="<?php echo base_url('assets/global/plugins/bootbox/bootbox.min.js'); ?>" type="text/javascript"></script>

<script>
jQuery(document).ready(function() {

    //table paging
    $('#general_table').DataTable({
        "paging": true,
        "pageLength": 100
    });

    var kode_list = {};

    $("#general_table .kode").each(function() {
        let kode = $.trim($(this).html());
        let id = $(this).closest('tr').find('.id').html();
        if (kode != '') {
            kode_list[id] = kode;
        }
    });

    console.log(kode_list);

    var kode_new = [0, 1, 5, 11, 40, 50, 31];
    kode_cek = $.grep(kode_new, function(a) {
        return (a > 11);
    });

    console.log('kode_cek a');
    console.log(kode_cek);

    $("#kode-add").change(function() {
        if (!cek_kode_supplier($(this).val(), '')) {
            alert("Kode sudah terpakai");
            $(this).val('');
        }
    });

    $("#kode-edit").change(function() {
        if (!cek_kode_supplier($(this).val(), $("#form_edit_data [name=id]").val())) {
            alert("Kode sudah terpakai");
            $(this).val('');
        };
    });

    function cek_kode_supplier(kode_get, id_get) {
        var result = true;
        
        $.each(kode_list, function(i, v) {
            console.log(i,id_get);
            if (id_get == '') {
                if (kode_get == v) {
                    result = false;
                }
            } else if (i != id_get) {
                if (kode_get == v) {
                    result = false;
                }
            }
        });

        return result;
    }

    // $('.btn-close').click(function() {
    //     window.location.reload();
    // })

    //////////////////////////////////////////
    // insert data
    //////////////////////////////////////////
    $('.btn-save').click(function() {
        $('btn-save').text('saving...');
        $('btn-save').attr('disabled', true);

        $.ajax({
            url: "<?= base_url('master/supplier_validate_start/new/0') ?>",
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
        var ini = $(this).closest('tr');

        var form = "#form_edit_data";
        var tipe_supplier = ini.find('.tipe_supplier').html();
        $(form + " [name=tipe_supplier][value='" + tipe_supplier + "']").prop('checked', true);
        $.uniform.update($(form).find("[name=tipe_supplier]"));

        $(form + ' [name=supplier_id]').val(ini.find('.id').html());
        $(form + ' [name=nama]').val(ini.find('.nama').html());
        $(form + ' [name=kode]').val(ini.find('.kode').html());
        $(form + ' [name=alamat]').val(ini.find('.alamat').html());
        $(form + ' [name=telepon]').val(ini.find('.telepon').html());
        $(form + ' [name=kota]').val(ini.find('.kota').html());
        $(form + ' [name=kode_pos]').val(ini.find('.kode_pos').html());
        $(form + ' [name=nama_bank]').val(ini.find('.nama_bank').html());
        $(form + ' [name=no_rek_bank]').val(ini.find('.no_rek_bank').html());
        $(form + ' [name=fax]').val(ini.find('.fax').html());
        $(form + ' [name=email]').val(ini.find('.email').html());
        $(form + ' [name=website]').val(ini.find('.website').html());
    });

    $('.btn-edit-save').click(function() {
        //get id
        id = $('#form_edit_data [name=supplier_id]').val();

        //validasi
        $('btn-save').text('saving...');
        $('btn-save').attr('disabled', true);

        $.ajax({
            url: "<?= base_url('master/supplier_validate_start/edit/'); ?>" + '/' + id,
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

    //////////////////////////////////////////
    // disable data
    //////////////////////////////////////////
    $('#general_table').on('click', '.btn-remove', function() {
        var data = status_aktif_get($(this).closest('tr')) + '=?=supplier';
        var nama = $(this).closest('tr').find('.nama').html();
        var status = $(this).closest('tr').find('.status_aktif').html();

        if (status == 0) {
            var text = "mengaktifkan";
        } else {
            var text = "menonaktifkan";
        };

        bootbox.confirm("Yakin untuk " + text + " supplier <b>" + nama + "</b> ?", function(respond) {
            if (respond) {
                window.location.replace(baseurl + "master/ubah_status_aktif?data_sent=" + data + '&link=supplier_list');
            };
        });
    });
});
</script>