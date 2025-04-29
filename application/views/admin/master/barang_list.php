<link rel="stylesheet" type="text/css" href="<?= base_url('assets/css/map_style.css'); ?>" />

<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css'); ?>" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css'); ?>" />

<style>
    .history-kredit-info{
        position: relative;
        cursor: pointer;
    }

    .history-kredit-info-active{
        font-weight:bold;
    }

    .history-kredit-info:hover{
        font-weight:bold;
    }

    .history-kredit-info div{
        position:absolute;
        top:0px; 
        left:100%;
        background:#eee;
        padding:10px;
        z-index: 999;
        min-width: 150px;
    }

    .barang-beli{
        cursor:pointer;
        color:navy;
    }

    .barang-beli:hover{
        color:blue
    }

    .div-beli .del, .div-beli .edt{
        display:none;
    }

    .div-beli:hover .del, .div-beli:hover .edt{
        display:inline-block;
    }

    .del:disabled i, .edt:disabled i{
        color:gray;
        cursor:no-drop;
    }

    .nama-beli-noaktif{
        text-decoration:line-through
    }

    .add-beli-button{
        display:none;
    }

    .cell-beli:hover .add-beli-button{
        display:block;
    }

</style>

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
                                        Nama Jual
                                    </th>
                                    <th scope="col" class="text-center">
                                        Nama Beli
                                    </th>
                                    <th scope="col" class="text-center">
                                        Satuan
                                    </th>
                                    <th scope="col" class="text-center status_column" >
                                        Harga Jual
                                    </th>
                                    <th scope="col" class="text-center status_column">
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

        <!-- history harga -->
        <div class="modal fade bs-modal-lg" id="portlet-config-harga" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-body">
                        <h2 id='nama-barang-harga'></h2>
                        <div id='harga-history'></div>
                        <hr/>
                        <!-- <h2>Harga Kredit</h2>
                        <p>Click di harga untuk lihat daftar customer</p>
                        <div id='harga-history-kredit'></div> -->
                        <!-- <div id='harga-history-jual'></div> -->
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn blue btn-save">Save</button>
                        <button type="button" class="btn default btn-close" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="portlet-config-beli" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <form action="<?= base_url('master/barang_beli_insert') ?>" autocomplete='off' class="form-horizontal" id="form_beli_data_add" method="post" onkeydown="return event.key != 'Enter';">
                            <h3 class='block'> Edit Nama Beli </h3>

                            <div class="form-group">
                                <label class="control-label col-md-3">Nama Jual
                                    <span class="required">*</span>
                                </label>
                                <div class="col-md-6">
                                    <input disabled id='nama-jual-form-beli-add' type="text" class="form-control" nama='nama_jual' />
                                    <span class="help-block"></span>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="control-label col-md-3">Nama Beli
                                    <span class="required">*</span>
                                </label>
                                <div class="col-md-6">
                                    <input id='nama-beli-form-beli-add' type="text" class="form-control" name="nama" />
                                    <span class="help-block"></span>
                                </div>
                            </div>
                            <input hidden id="barang_id_form_beli_add" name="barang_id" />

                        </form>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn blue" id="btn-add-beli" onclick='addNamaBeli()'>Save</button>
                        <button type="button" class="btn default btn-close" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="portlet-config-beli-edit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <form action="<?= base_url('master/barang_beli_update') ?>" autocomplete='off' class="form-horizontal" id="form_beli_data" method="post" onkeydown="return event.key != 'Enter';">
                            <h3 class='block'> Tambah Nama Beli </h3>

                            <div class="form-group">
                                <label class="control-label col-md-3">Nama Jual
                                    <span class="required">*</span>
                                </label>
                                <div class="col-md-6">
                                    <input disabled id='nama-jual-form-beli' type="text" class="form-control" nama='nama_jual' />
                                    <span class="help-block"></span>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="control-label col-md-3">Nama Beli
                                    <span class="required">*</span>
                                </label>
                                <div class="col-md-6">
                                    <input hidden id="id-form-beli" name="id" />
                                    <input id='nama-beli-form-beli' type="text" class="form-control" name="nama" />
                                    <span class="help-block"></span>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn blue" id="btn-update-beli" onclick='updateNamaBeli()'>Update</button>
                        <button type="button" class="btn default btn-close" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- tambah data -->
        <div class="modal fade" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <form action="<?= base_url('master/barang_list_insert') ?>" autocomplete='off' class="form-horizontal" id="form_add_data" method="post" onkeydown="return event.key != 'Enter';">
                            <h3 class='block'> Tambah </h3>

                            <div class="form-group">
                                <label class="control-label col-md-3">Nama Beli
                                    <span class="required">*</span>
                                </label>

                                <div class="col-md-6">
                                    <input type="text" class="form-control input1" name="nama" />
                                    <span class="help-block"></span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3">Nama Jual
                                    <span class="required">*</span>
                                </label>

                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="nama_jual" />
                                    <span class="help-block"></span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3">Harga Jual
                                    <span class="required">*</span>
                                </label>

                                <div class="col-md-6">
                                    <input type="text" class="form-control amount_number" name="harga_jual" />
                                    <span class="help-block"></span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3">Harga Beli
                                    <span class="required">*</span>
                                </label>

                                <div class="col-md-6">
                                    <input type="text" class="form-control amount_number" name="harga_beli" />
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3">Satuan
                                    <span class="required">*</span>
                                </label>

                                <div class="col-md-6">
                                    <select class="form-control " name="satuan_id">
                                        <? foreach ($satuan_list as $row) { ?>
                                        <option value="<?= $row->id ?>"><?= $row->nama; ?></option>
                                        <? } ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3">Status
                                    <span class="required">*</span>
                                </label>

                                <div class="col-md-6">
                                    <select class='form-control' name='status_aktif'>
                                        <option value='0'>Tidak Aktif</option>
                                        <option value='1' selected>Aktif</option>
                                    </select>
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

        <!-- edit barang  -->
        <div class="modal fade" id="portlet-config-edit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <form action="<?= base_url('master/barang_list_update') ?>" autocomplete='off' class="form-horizontal" id="form_edit_data" method="post" onkeydown="return event.key != 'Enter';">
                            <h3 class='block'> Edit </h3>

                            <!-- <div class="form-group">

                                <label class="control-label col-md-3">Nama Beli
                                    <span class="required">*</span>
                                </label>

                                <div class="col-md-6">
                                    <input type="hidden" class="form-control" id="id" name="id" />
                                    <input type="text" class="form-control edit1" name="nama" />
                                    <span class="help-block"></span>
                                </div>
                            </div> -->

                            

                            <div class="form-group">
                                <label class="control-label col-md-3">Nama Beli
                                </label>

                                <div class="col-md-6">
                                    <div class="note note-info">
                                        <input hidden id="id" name="id" />
                                        <i class='fa fa-info-circle'></i> Edit nama beli dilakukan langsung di kolom nama beli
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3">Nama Jual
                                    <span class="required">*</span>
                                </label>

                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="nama_jual" />
                                    <span class="help-block"></span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3">Harga Jual
                                    <span class="required">*</span>
                                </label>

                                <div class="col-md-6">
                                    <input type="text" class="form-control amount_number" name="harga_jual" />
                                    <span class="help-block"></span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3">Harga Beli
                                    <span class="required">*</span>
                                </label>

                                <div class="col-md-6">
                                    <input type="text" class="form-control amount_number" name="harga_beli" />
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3">Satuan
                                    <span class="required">*</span>
                                </label>

                                <div class="col-md-6">
                                    <select class="form-control " name="satuan_id">
                                        <? foreach ($satuan_list as $row) { ?>
                                        <option value="<?= $row->id ?>"><?= $row->nama; ?></option>
                                        <? } ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3">Status
                                    <span class="required">*</span>
                                </label>

                                <div class="col-md-6">
                                    <select class='form-control' name='status_aktif'>
                                        <option value='0'>Tidak Aktif</option>
                                        <option value='1' selected>Aktif</option>
                                    </select>
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

<script src="<?php echo base_url('assets/global/plugins/datatables/media/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/bootbox/bootbox.min.js'); ?>" type="text/javascript"></script>

<script>
var harga_history = [];
harga_history['beli'] = [];
harga_history['jual'] = [];
harga_history['jual_kredit'] = [];
const barang_beli = [];

<?foreach ($barang_beli as $row) {?>
    if (typeof barang_beli[<?=$row->barang_id;?>] === 'undefined') {
        barang_beli[<?=$row->barang_id;?>] = [];    
    }
    barang_beli[<?=$row->barang_id;?>][<?=$row->id;?>] = '<?=$row->nama;?>';
<?}?>

jQuery(document).ready(function() {

    // oTable = $('#general_table').DataTable();
    // oTable.state.clear();
    // oTable.destroy();
    

    ///////////////////////////////////////////////////
    // grid
    ///////////////////////////////////////////////////
    $("#general_table").DataTable({
        "fnCreatedRow": function(nRow, aData, iDataIndex) {
            var status = $('td:eq(6)', nRow).text().split('??');
            var id = status[0];
            var satuan_id = status[1];
            var barang_beli_id = status[2];
            var status_beli = status[3];
            var status_aktif = $('td:eq(0)', nRow).text();

            if (status_aktif == 1) {
                var btn_status = "<a class='btn-xs btn red btn-remove'><i class='fa fa-times'></i> </a>";
            } else {
                var btn_status = "<a class='btn-xs btn blue btn-remove'><i class='fa fa-play'></i> </a>";
            };


            const hidden = "<?=(is_posisi_id() != 1 ? 'hidden' : '');?>";
            var action = `<span class='id' ${hidden}>${id}</span><span class='satuan_id' ${hidden}>${satuan_id}</span>
                <span class='status_aktif' ${hidden}>${status_aktif}</span>
                <a href='#portlet-config-edit' data-toggle='modal' class='btn-xs btn green btn-edit'><i class='fa fa-edit'></i> </a>${btn_status}`;
            var btn_profile = '';
            var btn_forecast = '';
            var btn_planner = '';
            var btn_harga = '';

            <?php if (is_posisi_id() <= 3) { ?>
            
                let nm = $('td:eq(1)', nRow).text();
                let nmj = $('td:eq(2)', nRow).text();
                // btn_profile = `<a href="<?= base_url() . is_setting_link('master/barang_profile'); ?>/${id}" class='btn btn-xs blue' onclick="window.open(this.href, 'newwindow', 'width=1250, height=650'); return false;">profile</a>`;
                btn_profile = `<a href="<?= base_url() . is_setting_link('master/barang_profile'); ?>/${id}" class='btn btn-xs blue' target='_blank'><i class='fa fa-user'></i></a>`;
                btn_forecast = `<a href="<?= base_url() . is_setting_link('master/barang_forecasting'); ?>?id=${id}" class='btn btn-xs yellow-gold' target='_blank'><i class='fa fa-bar-chart-o'></i></a>`;
                btn_planner = `<a href="<?= base_url() . is_setting_link('master/barang_planner'); ?>?id=${id}" class='btn btn-xs default' target='_blank'><i class='fa fa-stethoscope'></i></a>`;
                // btn_harga = `<a href="#portlet-config-harga" data-toggle='modal' onclick="showHargaHistory('${id}','${nm}', '${nmj}')" class='btn btn-xs default'>Rp</a>`;
                
                // tipe_transaksi, tanggal, gudang_id, barang_id, warna_id, qty, jumlah_roll, user_id, created_at
                // 6942 - 7075
            <?php } ?>

            var brg_id = '';

            <?php if (is_posisi_id() == 1) { ?>

                brg_id = '(' + id + ')';

            <?php } ?>

            let nama_beli = '';
            if (typeof barang_beli[id] !== 'undefined') {
                // console.log(barang_beli[id].length);
                barang_beli[id].forEach(el => {
                    // console.log('el',el);
                });
            }
            
            const nama_jual = $('td:eq(2)', nRow).text();
            const nama_beli_list = $('td:eq(1)', nRow).text().split('??');
            const status_aktif_beli = status_beli.split(',');
            const beli_id_list = barang_beli_id.split(',');
            let = beli_list = [];
            nama_beli_list.forEach((beli,idx) => {
                if (status_aktif_beli[idx] == 1) {
                    beli_list.push(`<div class='div-beli'>
                        ${beli}
                        <button href='#portlet-config-beli-edit' data-toggle='modal' class='barang-beli edt' style='margin:0px; color:green;padding:0px;padding-left:3px; border:none; background:none;; margin:0px;' onclick="editBarangBeli('${beli_id_list[idx]}','${beli}','${nama_jual}')"><i style='font-size:16px' class='fa fa-edit'></i></button>
                        <button class='del' style='margin:0px; padding:0px;color:red; border:none; background:none;margin:0px;' onclick="disableNamaBeli('${beli_id_list[idx]}','${beli}','${nama_jual}')"><i style='font-size:16px' class='fa fa-times'></i></button>
                        </div>`);
                }else{
                    beli_list.push(`<span class='nama-beli nama-beli-noaktif'>
                        ${beli}
                    </span>`)
                }
            });

            let show_beli = "";
            if (beli_list.length > 0) {
                show_beli = beli_list.join('<br/>');
            }

            show_beli += `<div class='add-beli-button'>
                <hr style='margin:10px 0px 0px 0px;'/>
                <div style='padding:5px; background:lightblue'>
                    <a href="#portlet-config-beli" data-toggle='modal' onclick="showNewBeli('${nama_jual}','${id}')">Tambah nama beli <i class='fa fa-plus'></i></a>
                </div>
            </div>`;
            
            

            $('td:eq(0)', nRow).html($('td:eq(0)', nRow).text());
            $('td:eq(0)', nRow).addClass('status_column');
            $('td:eq(1)', nRow).html('<span class="nama_jual">' + nama_jual + '</span>');
            $('td:eq(2)', nRow).html(show_beli);
            $('td:eq(2)', nRow).addClass('cell-beli');
            $('td:eq(3)', nRow).html('<span class="nama_satuan">' + $('td:eq(3)', nRow).text() + '</span>');
            $('td:eq(4)', nRow).html('<span class="harga_jual">' + change_number_format($('td:eq(4)', nRow).text()) + '</span>');

            $('td:eq(4)', nRow).addClass('status_column');
            $('td:eq(5)', nRow).addClass('status_column');

            const hb = $('td:eq(5)', nRow).text();
            const hbs = (typeof hb  !== 'undefined' && hb != 0  ?  change_number_format2(hb).replace(",00","") : '');
            $('td:eq(5)', nRow).html('<span class="harga_beli">' + hbs + '</span>');
            $('td:eq(6)', nRow).html(action + btn_profile+ btn_harga + btn_forecast + btn_planner);
            // $(nRow).addClass('status_aktif_'+status_aktif);
        },
        "bProcessing": true,
        "bServerSide": true,
        "paging": true,
        "pageLength": 100,
        "bStateSave": true,
        //"bSort" : true,
        "sAjaxSource": baseurl + "master/data_barang",
        "initComplete":function(){
            // get_harga_history()
        }
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

    //////////////////////////////////////////
    // save data 
    //////////////////////////////////////////
    $('.btn-save').click(function() {
        $('btn-save').text('saving...');
        $('btn-save').attr('disabled', true);

        $.ajax({
            url: "<?= base_url('master/barang_validate_start/new/0') ?>",
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

    /////////////////////////////////////
    //tombol edit
    /////////////////////////////////////
    $('#general_table').on('click', '.btn-edit', function() {
        const ini = $(this).closest('tr');
        $('#form_edit_data [name=id]').val(ini.find('.id').html());
        $('#form_edit_data [name=nama]').val(ini.find('.nama').html());
        $('#form_edit_data [name=nama_jual]').val(ini.find('.nama_jual').html());
        $('#form_edit_data [name=harga_jual]').val(ini.find('.harga_jual').html());
        $('#form_edit_data [name=harga_beli]').val(ini.find('.harga_beli').html());
        $('#form_edit_data [name=satuan_id]').val(ini.find('.satuan_id').html());
        $('#form_edit_data [name=status_aktif]').val(ini.find('.status_aktif').html());
    });

    //tombol edit (selesai disave)
    $('.btn-edit-save').click(function() {
        //get id
        id = $('#form_edit_data [name=id]').val();

        <?if (is_posisi_id() == 1) {?>
            alert(id);
        <?}?>
 
        //validasi
        $('btn-save').text('saving...');
        $('btn-save').attr('disabled', true);

        $.ajax({
            url: "<?= base_url('master/barang_validate_start/edit/'); ?>" + '/' + id,
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

    /////////////////////////////////////////
    // tombol hapus
    /////////////////////////////////////////
    $('#general_table').on('click', '.btn-remove', function() {
        var data = status_aktif_get($(this).closest('tr')) + '=?=barang';
        var nama = $(this).closest('tr').find('.nama').html();
        var status = $(this).closest('tr').find('.status_aktif').html();

        if (status == 0) {
            var text = "mengaktifkan";
        } else {
            var text = "menonaktifkan";
        };

        bootbox.confirm("Yakin untuk " + text + " barang <b>" + nama + "</b> ?", function(respond) {
            if (respond) {
                window.location.replace(baseurl + "master/ubah_status_aktif?data_sent=" + data + '&link=barang_list');
            };
        });
    });

    $('#portlet-config-harga').click(function(e){
        if(!$(e.target).hasClass('history-kredit-info') )
        {
            $(".history-kredit-info div").hide();
            $(".history-kredit-info").removeClass("history-kredit-info-active");
        }
    })

    /////////////////////////////////////////
    // Notifikasi 
    /////////////////////////////////////////
    <?if ($info_barang_beli != '') {?>
        notific8("lime", "<?=$info_barang_beli;?>");
    <?}?>
});

function editBarangBeli(id,nama_beli, nama_jual){
    $('#nama-jual-form-beli').val(nama_jual);
    $('#nama-beli-form-beli').val(nama_beli);
    $('#id-form-beli').val(id);
}

function updateNamaBeli(){
    $("#btn-update-beli").prop('disabled', true);
    const id = $('#id-form-beli').val();
    const nama_beli = $('#nama-beli-form-beli').val();
    const dialog = bootbox.dialog({
        message: `<p class="text-center mb-0"><i class="fa fa-spin fa-cog"></i> Cek apakah ${nama_beli} sudah terdaftar...</p>`,
        closeButton: false
    });
    setTimeout(() => {
        cekNamaBeli(id, nama_beli)
        .then(data=>{
            if(data=='OK'){
                dialog.find('.bootbox-body').html(`<p class="text-center mb-0"><i class="fa fa-check"></i>${nama_beli} belum terdaftar, updating...</p>`);
                setTimeout(() => {
                    $("#form_beli_data").submit();
                }, 1500);
            }else{
                dialog.find('.bootbox-body').html(`<p class="text-center mb-0"><i class="fa fa-warning"></i>${nama_beli} sudah terdaftar</p>`);
                setTimeout(() => {
                    dialog.modal('hide');
                }, 1500);
                $("#btn-update-beli").prop('disabled', false);
            }
        });
    }, 1500);

    
}

async function cekNamaBeli(id, nama){
    const res = await fetch(baseurl+"master/cek_nama_beli", {
        method:"POST",
        headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `id=${id}&nama=${nama}`
    });
    
    return res.json();
}

async function removeNamaBeli(id, nama){
    const res = await fetch(baseurl+"master/remove_nama_beli", {
        method:"POST",
        headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `id=${id}&nama=${nama}`
    });
    
    return res.json();
}

async function disableNamaBeli(id, nama, nama_jual){
    $('.del, .edt').prop('disabled', true);
    bootbox.confirm(`Nonaktifkan <b>${nama}</b> dari daftar nama beli <b>${nama_jual}</b>  ?`, function(respond){
        if (respond) {
            const dialog = bootbox.dialog({
                message: `<p class="text-center mb-0"><i class="fa fa-spin fa-cog"></i> Menonaktifkan ${nama}...</p>`,
                closeButton: false
            });
            removeNamaBeli(id, nama)
            .then(data=>{
                if(data=='OK'){
                    dialog.find('.bootbox-body').html(`<p class="text-center mb-0"><i class="fa fa-check"></i>${nama} sukses di nonaktifkan. Window refresh...</p>`);
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                }else{
                    alert("ERROR");

                }
            })
        }else{
            $('.del, .edt').prop('disabled', false);
        }
    })
}

function showNewBeli(nama_jual, barang_id){
    $("#nama-jual-form-beli-add").val(nama_jual);
    $("#barang_id_form_beli_add").val(barang_id);
}

function addNamaBeli(){
    $("#btn-add-beli").prop('disabled', true);
    const nama_beli = $('#nama-beli-form-beli-add').val();
    const dialog = bootbox.dialog({
        message: `<p class="text-center mb-0"><i class="fa fa-spin fa-cog"></i> Cek apakah ${nama_beli} sudah terdaftar...</p>`,
        closeButton: false
    });
    setTimeout(() => {
        cekNamaBeli('', nama_beli)
        .then(data=>{
            if(data=='OK'){
                dialog.find('.bootbox-body').html(`<p class="text-center mb-0"><i class="fa fa-check"></i>${nama_beli} belum terdaftar, saving...</p>`);
                setTimeout(() => {
                    $("#form_beli_data_add").submit();
                }, 1500);
            }else{
                dialog.find('.bootbox-body').html(`<p class="text-center mb-0"><i class="fa fa-warning"></i>${nama_beli} sudah terdaftar</p>`);
                setTimeout(() => {
                    dialog.modal('hide');
                }, 1500);
                $("#btn-add-beli").prop('disabled', false);
            }
        });
    }, 1500);
}



/*
function get_harga_history(){
    var data_st = {};
    var url = "master/get_harga_history";
    ajax_data_sync(url,data_st).done(function(data_respond  ,textStatus, jqXHR ){
        // console.log(data_respond)
        let res = JSON.parse(data_respond);
        $.each(res.harga_beli_history, function(i,v){
            let harga_beli_list = v.harga_beli_data.split(',');
            let bulan = v.tahun_bulan.split(",");
            harga_history['beli'][v.barang_id] = [];
            let hB = [];
            // harga_history['beli'][v.barang_id] = `<table class='table'>`;
            // let row_harga_beli = [];
            // let row_tanggal_beli = [];
            // let harga_before = 0;
            for (let i = 0; i < harga_beli_list.length; i++) {
                hB[bulan[i]] = harga_beli_list[i];
                // let nd = new Date(bulan[i]+'-01');
                // let icon = '';
                // const mo = nd.toLocaleString('default', { month: 'short' });
                // if (i > 0) {
                //     if (harga_beli_list[i] > harga_before) {
                //         icon = "<i class='fa fa-arrow-up'></i>";
                //     }else{
                //         icon = "<i class='fa fa-arrow-down'></i>";
                //     }
                // }
                // row_tanggal += `<td>${mo} ${nd.getFullYear()}<td>`;
                // row_harga += `<td>${parseFloat(harga_beli_list[i])} ${icon}<td>`;
                // harga_before =  harga_beli_list[i];
            }
            // harga_history['beli'][v.barang_id] += `<tr>${row_tanggal}</tr><tr>${row_harga}</tr></table>`;
            harga_history['beli'][v.barang_id]['tanggal'] = bulan;
            harga_history['beli'][v.barang_id]['harga'] = hB;
            harga_history['beli'][v.barang_id]['master'] = v.harga_beli_master;
        })

        $.each(res.harga_jual_history, function(i,v){
            let harga_jual_list = v.harga_jual_data.split(',');
            let bulan = v.tahun_bulan.split(",");
            harga_history['jual'][v.barang_id] = [];
            let hJ = [];

            for (let i = 0; i < harga_jual_list.length; i++) {
                if (typeof hJ[bulan[i]] === 'undefined') {
                    hJ[bulan[i]] = []
                }
                hJ[bulan[i]].push(harga_jual_list[i]);    
                // hJ[bulan[i]] = 
            }

            // harga_history['jual'][v.barang_id] = `<table class='table'>`;
            // let row_harga = '<th>PENJUALAN</th>';
            // let row_tanggal = '<th>HISTORY</th>';
            // let harga_before = 0;
            // for (let i = 0; i < harga_jual_list.length; i++) {
            //     let nd = new Date(bulan[i]+'-01');
            //     let icon = '';
            //     const mo = nd.toLocaleString('default', { month: 'short' });
            //     if (i > 0) {
            //         if (harga_jual_list[i] > harga_before) {
            //             icon = "<i class='fa fa-arrow-up'></i>";
            //         }else{
            //             icon = "<i class='fa fa-arrow-down'></i>";
            //         }
            //     }
            //     row_tanggal += `<td>${mo} ${nd.getFullYear()}<td>`;
            //     row_harga += `<td>${parseFloat(harga_jual_list[i])} ${icon}<td>`;
            //     harga_before =  harga_jual_list[i];
            // }
            // harga_history['jual'][v.barang_id] += `<tr>${row_tanggal}</tr><tr>${row_harga}</tr></table>`;
            harga_history['jual'][v.barang_id]['tanggal'] = bulan;
            harga_history['jual'][v.barang_id]['harga'] = hJ;
            harga_history['jual'][v.barang_id]['master'] = v.harga_jual_master;
        })

        
        $.each(res.harga_jual_credit_history, function(i,v){
            // console.log(i, v);
            let harga_jual_list = v.harga_jual_data.split(',');
            let bulan = v.tahun_bulan.split(",");
            let nama_customer = v.nama_customer.split(",");
            harga_history['jual_kredit'][v.barang_id] = [];
            let hJ = [];
            let nC = [];

            let row_tanggal = '<th>PERIODE</th>';
            let row_harga = '<th>HARGA</th>';
            for (let i = 0; i < harga_jual_list.length; i++) {
                if (typeof hJ[bulan[i]] === 'undefined') {
                    hJ[bulan[i]] = [];
                    nC[bulan[i]] = [];
                }
                hJ[bulan[i]].push(harga_jual_list[i]);    
                nC[bulan[i]].push(nama_customer[i]);    
            }

            let nB = [];
            nB = bulan.filter(function(bln, index, self){
                return self.indexOf(bln) === index;
            });

            // console.log(nB)
            

            harga_history['jual_kredit'][v.barang_id]['tanggal'] = nB;
            harga_history['jual_kredit'][v.barang_id]['harga'] = hJ;
            harga_history['jual_kredit'][v.barang_id]['master'] = v.harga_jual_master;
            harga_history['jual_kredit'][v.barang_id]['customer'] = nC;
            // harga_history['jual'][v.barang_id]['tanggal'] = bulan;
            // harga_history['jual'][v.barang_id]['harga'] = hJ;
            // harga_history['jual'][v.barang_id]['master'] = v.harga_jual_master;
        });
        // console.log(harga_history['jual_kredit']);

        // console.log(harga_history);
    });
}

function showHargaHistory(barang_id, nm, nmj){
    // console.log(harga_history['beli'][barang_id])
    // console.log(harga_history['jual'][barang_id])

    $('#nama-barang-harga').html(`${nm} - ${nmj}`)

    let hj_master_beli = 0;
    if (typeof harga_history['beli'][barang_id] !== 'undefined') {
        var hB = harga_history['beli'][barang_id]['harga'];
        hj_master_beli = harga_history['beli'][barang_id]['master'];
    }
    
    let hj_master_jual = 0;
    // let hJ
    if (typeof harga_history['jual'][barang_id] !== 'undefined') {
        var hJ = harga_history['jual'][barang_id]['harga'];
        hj_master_jual = harga_history['jual'][barang_id]['master'];
        // console.log(hJ);
    }

    var tJC = [];
    if (typeof harga_history['jual_kredit'][barang_id] !== 'undefined') {
        var hJC = harga_history['jual_kredit'][barang_id]['harga'];
        tJC = harga_history['jual_kredit'][barang_id]['tanggal'];
        var nC = harga_history['jual_kredit'][barang_id]['customer'];
    }
    
    // console.log(hJC);

    let tB = [];
    let tJ = [];
    if (typeof hB !== 'undefined') {
        tB = harga_history['beli'][barang_id]['tanggal'];
    }

    if (typeof hJ !== 'undefined') {
        console.log(harga_history['jual'][barang_id]['tanggal']);
        tJ = harga_history['jual'][barang_id]['tanggal'];
    }
    
    let tgls = tB.concat(tJ);
    tgls = tgls.concat(tJC);
    let nD = [];
    nD = [...new Set(tgls)];
    nD.sort();

    let row_tanggal = '<th>BULAN</th>';
    let row_harga_beli = '<th>BELI</th>';
    let row_harga_jual = '<th>JUAL</th>';
    let row_harga_jual_kredit = '<th>JUAL KREDIT</th>';

    let harga_before_jual = 0;
    let harga_before_beli = 0;
    for (let i = 0; i < nD.length; i++) {
        let nT = new Date(nD[i]+'-01')
        let icon_beli = '';
        let icon_jual = '';
        const mo = nT.toLocaleString('default', { month: 'short' });

        row_tanggal += `<td class='text-right'>${mo} ${nT.getFullYear()}<td>`;
        if (typeof hB !== 'undefined' && typeof hB[nD[i]] !== 'undefined' ) {
            let txt = '';
            if (i > 0 && harga_before_beli != 0) {
                if (hB[nD[i]] > harga_before_beli) {
                    icon_beli = "fa-arrow-up";
                }else{
                    icon_beli = "fa-arrow-down";
                }
                txt = `<br/><span style='font-size:0.8em'><i style='color:#aaa; font-size:0.9em' class='fa ${icon_beli}'></i> ${hB[nD[i]] - harga_before_beli}</span> `;
            }

            row_harga_beli += `<td class='text-right'>
                    ${change_number_format2(parseFloat(hB[nD[i]])).replace(",00","")}
                    ${txt}
                <td>`;
            harga_before_beli = hB[nD[i]];

        }else{
            row_harga_beli += `<td class='text-right'> ... <td>`;
        }
        
        console.log(hJ);
        if (typeof hJ !== 'undefined' && typeof hJ[nD[i]] !== 'undefined' ) {
            let txt = '';
            // console.log(hJ[nD[i]]);
            if (i > 0 && harga_before_jual != 0) {
                if (hJ[nD[i]] > harga_before_jual) {
                    icon_jual = "fa-arrow-up";
                }else{
                    icon_jual = "fa-arrow-down";
                }
                // txt = `<br/><span style='font-size:0.8em'><i style='color:#aaa; font-size:0.9em' class='fa ${icon_jual}'></i> ${hJ[nD[i]] - harga_before_jual}</span> `;
            }
            
            
            let x = hJ[nD[i]];
            let y = [];
            for (let j = 0; j < x.length; j++) {
                y.push(`<div>${change_number_format2(parseFloat(x[j])).replace(",00","")}</div>`);
                
            }
            row_harga_jual += `<td class='text-right'>${y.join('')} 
                ${txt}
                <td>`;
            harga_before_jual = hJ[nD[i]];
        }else{
            row_harga_jual += `<td class='text-right'> ... <td>`;
        }

        if (typeof hJC !== 'undefined' && typeof hJC[nD[i]] !== 'undefined' ) {
            let txt = '';
            // console.log(hJ[nD[i]]);
            // if (i > 0 && harga_before_jual != 0) {
            //     if (hJ[nD[i]] > harga_before_jual) {
            //         icon_jual = "fa-arrow-up";
            //     }else{
            //         icon_jual = "fa-arrow-down";
            //     }
            //     // txt = `<br/><span style='font-size:0.8em'><i style='color:#aaa; font-size:0.9em' class='fa ${icon_jual}'></i> ${hJ[nD[i]] - harga_before_jual}</span> `;
            // }
            
            
            // let x = hJC[nD[i]];
            // let y = [];
            // for (let j = 0; j < x.length; j++) {
            //     y.push(`<div>${change_number_format2(parseFloat(x[j])).replace(",00","")}</div>`);
                
            // }

            let x = hJC[nD[i]];
            let y = nC[nD[i]];
            let z = [];
            for (let j = 0; j < x.length; j++) {
                // console.log(x[j],y[j]);
                let aa = y[j].split('??')
                z.push(`<div id="${mo}-${nT.getFullYear()}-${x[j]}-cont" class='history-kredit-info' onclick="showNames('${mo}-${nT.getFullYear()}-${x[j]}')">
                        ${change_number_format(x[j])} <div id="${mo}-${nT.getFullYear()}-${x[j]}" class='nama-list' hidden>${aa.join("<hr style='border-color:#555;padding:0px; margin:2px'/>")}</div>
                    </div>`);
                
            }
            
            row_harga_jual_kredit += `<td class='text-right'>${z.join('')}
                ${txt}
                <td>`;
            harga_before_jual = hJC[nD[i]];
        }else{
            row_harga_jual_kredit += `<td class='text-right'> ... <td>`;
        }
        
    }

    let beda_beli = 0;
    let icon_beli = '';
    if (hj_master_beli > 0) {
        beda_beli = hj_master_beli - harga_before_beli;
        if (beda_beli > 0) {
            icon_beli = `<i style='color:#aaa; font-size:0.9em' class='fa fa-arrow-up'></i> ${harga_history['beli'][barang_id]['master'] - harga_before_beli}`;
        }else if(beda_beli < 0){
            icon_beli = `<i style='color:#aaa; font-size:0.9em' class='fa fa-arrow-down'></i> ${harga_history['beli'][barang_id]['master'] - harga_before_beli}`;
        }
    }

    let beda_jual = 0;
    let icon_jual = '';
    if (hj_master_jual > 0) {
        beda_jual = hj_master_jual - harga_before_jual;
        if (beda_jual > 0) {
            icon_jual = `<i style='color:#aaa; font-size:0.9em' class='fa fa-arrow-up'></i> ${harga_history['jual'][barang_id]['master'] - harga_before_jual}` ;
        }else if(beda_jual < 0){
            icon_jual = `<i style='color:#aaa; font-size:0.9em' class='fa fa-arrow-down'></i> ${harga_history['jual'][barang_id]['master'] - harga_before_jual}`;
        }
    }

    if (tJC.length > 0) {
        let rT = '<th>BULAN</th>';
        let rHC = '<th>JUAL</th>';
        let tbl2 = '';
        let hBJ = 0;
        for (let i = 0; i < tJC.length; i++) {
            let nT = new Date(tJC[i]+'-01');
            let icon = '';
            const mo = nT.toLocaleString('default', { month: 'short' });
    
            rT += `<td class='text-right'>${mo} ${nT.getFullYear()}</td>`;
            // console.log(nC[tJC[i]]);
            let x = hJC[tJC[i]];
            let y = nC[tJC[i]];
    
            let z = [];
            for (let j = 0; j < x.length; j++) {
                // console.log(x[j],y[j]);
                let aa = y[j].split('??')
                z.push(`<div id="${mo}-${nT.getFullYear()}-${x[j]}-cont" class='history-kredit-info' onclick="showNames('${mo}-${nT.getFullYear()}-${x[j]}')">
                        ${change_number_format(x[j])} <div id="${mo}-${nT.getFullYear()}-${x[j]}" class='nama-list' hidden>${aa.join("<hr style='border-color:#555;padding:0px; margin:2px'/>")}</div>
                    </div>`);
                
            }
            rHC += `<td class='text-right'>${z.join('')}</td>`;
        }
    
        let w = "";
        if (tJC.length <=3) {
            w = "width:50%;";
        }
        $('#harga-history-kredit').html(`<table style="${w}" class='table'><tr>${rT}</tr> <tr>${rHC}</tr></table>`);
        
    }

    let tbl = `<table class='table'>
        <tr>${row_tanggal}<th class='text-right'>MASTER</th></tr>
        <tr>
            ${row_harga_beli}
            <th class='text-right'>
                ${change_number_format2(parseFloat(hj_master_beli)).replace(",00","")}
                <br/><span style='font-size:0.8em'>${icon_beli}</span>
            </th>
        </tr>
        <tr>
            ${row_harga_jual}
            <th class='text-right'>
                ${change_number_format(parseFloat(hj_master_jual))}
                <br/><span style='font-size:0.8em'>${icon_jual}</span>
            </th>
        </tr>
        <tr>
            ${row_harga_jual_kredit}
            <th class='text-right'>
                
            </th>
        </tr>
    </table>`;
    
    $('#harga-history').html(tbl);

}

function showNames(divId){
    // alert();
    $(".history-kredit-info div").hide();
    $(".history-kredit-info").removeClass("history-kredit-info-active");
    
    $(`#${divId}-cont`).addClass("history-kredit-info-active");
    $(`#${divId}`).toggle();
}
*/
</script>