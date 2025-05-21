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

    .color-box {
        float: left;
        height: 20px;
        width: 20px;
        margin-bottom: 15px;
        border: 1px solid white;
        clear: both;
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

                            <a href="#portlet-config" data-toggle='modal' class="btn btn-default btn-sm btn-form-add hidden">
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
                                    <th scope="col" class="text-center">
                                        SKU list
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

        <div class="modal fade" id="portlet-config-sku" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <h3 class='block'> Daftar SKU <span id="namaBarangSKU"></span> </h3>
                        <input type="search" class="form-control" placeholder="Search" id="search-sku" style="margin-bottom: 20px;" oninput="filterSKU()" />
                        <p id="listBarangSKU"></p>
                    </div>

                    <div class="modal-footer">
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
let skuListShow = [];
let filteredSkuList = [];

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

            const btnShowSku = `<button class='btn btn-xs default' onclick="showSku('${id}','${nama_jual}')"><i style='font-size:16px' class='fa fa-list'></i> sku list</button>`;
            
            

            $('td:eq(0)', nRow).html($('td:eq(0)', nRow).text());
            $('td:eq(0)', nRow).addClass('status_column');
            $('td:eq(1)', nRow).html('<span class="nama_jual">' + nama_jual + '</span>');
            $('td:eq(2)', nRow).html(show_beli);
            $('td:eq(2)', nRow).addClass('cell-beli');
            $('td:eq(3)', nRow).html('<span class="nama_satuan">' + $('td:eq(3)', nRow).text() + '</span>');
            $('td:eq(4)', nRow).html('<span class="harga_jual">' + change_number_format($('td:eq(4)', nRow).text()) + '</span>');

            $('td:eq(4)', nRow).addClass('status_column');
            $('td:eq(5)', nRow).addClass('text-center');

            const hb = $('td:eq(5)', nRow).text();
            const hbs = (typeof hb  !== 'undefined' && hb != 0  ?  change_number_format2(hb).replace(",00","") : '');
            $('td:eq(5)', nRow).html(btnShowSku);
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


function showSku(id, nama_jual){

    $('#namaBarangSKU').html(`(${nama_jual})`);
    $('#search-sku').val('');
    $(`#listBarangSKU`).html(`<p class="text-center mb-0"><i class="fa fa-spin fa-cog"></i> Loading...</p>`)
    $(`#portlet-config-sku`).modal('show'); 
    setTimeout(() => {
        $.ajax({
            url: baseurl + "master/get_barang_sku_by_barang_id",
            type: "GET",
            data: {barang_id:id},
            dataType: "JSON",
            success: function(data) {
                skuListShow = data;
                filteredSkuList = data;
                drawTableSKU();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('Error loading data!');
            }
        });
    }, 1500);  
}

function drawTableSKU(){
    let list = '';
    filteredSkuList.forEach((el, idx) => {
        const bg = el.kode_warna != '' ? `background-color:${el.kode_warna}` : '';
        // list += `<li class='list-group-item'>${el.barang_sku_id} - &nbsp; <b>${el.nama_barang_sku}</b></li>`;
        list += `<tr>
            <td>${idx+1}</td>
            <td>${el.barang_sku_id}</td>
            <td><b>${el.nama_barang_sku}</b></td>
            <td><div class='color-box' style='${bg}'></div></td>
        </tr>`;
    });
    list = `<table class='table'>
        <tr>
            <th></th>
            <th>SKU</th>
            <th>Nama Barang</th>
            <th>Warna</th>
        </tr>
        ${list}</table>`;
    $(`#listBarangSKU`).html(list);
}

function filterSKU(){
    const search = $('#search-sku').val().toLowerCase();
    filteredSkuList = skuListShow.filter((el) => {
        return (el.nama_barang_sku.toLowerCase().includes(search) || el.barang_sku_id.toLowerCase().includes(search));
    });
    drawTableSKU();
}

</script>