<link rel="stylesheet" type="text/css" href="<?= base_url('assets/css/map_style.css'); ?>" />

<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css'); ?>" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css'); ?>" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css'); ?>" />
<?= link_tag('assets/global/plugins/dropzone/css/dropzone.css'); ?>

<style type="text/css">
.npwp {
    background: yellow;
}

.nik {
    background: lightblue;
}

#customer-id-img {
    max-width: 100%;
    max-height: 100%;
}

.inputSource{
    display: none;
    width:100px;
    border:1px solid #ccc;
    padding: 5px;
}

.btn-save-source, .sourceDetailInput, .btn-cancel-source{
    display: none;
}

<? if (is_posisi_id() !=1) {
    ?>.kolom-id {
        display: none;
    }

    <?
}

?>form small {
    color: #aaa;
}
</style>

<div class="page-content">
    <div class='container'>
        <!-- list data  -->
        <div class="row margin-top-10">
            <div class="col-md-12">
                <div class="portlet light">
                    <!-- filter data  -->
                        <div class="tabbable tabs-left" style='margin-bottom:5px'>
                            <ul class="nav nav-tabs" style='padding:0px; margin:10px 0px;'>
                                <li class='active'>
                                    <a href="#">
                                        <?= $breadcrumb_small; ?>
                                    </a> 
                                </li>
                                <li>
                                    <a href="<?=base_url().is_setting_link('masters/group_harga_customer/daftar')?>">
                                        DAFTAR HARGA
                                    </a> 
                                </li>
                            </ul>
                        </div>
                    <div class="portlet-title">per
                        
                        <div class="caption caption-md">
                            <div class="caption caption-md">
                                <i class="icon-bar-chart theme-font hide"></i>
                                <span class="caption-subject theme-font bold uppercase"><?= $breadcrumb_small; ?></span>
                            </div>
                        </div>

                        <div class="actions">
                            <select class='btn btn-sm btn-default' id='customer_type_id_filter' name='customer_type_id_filter'>
                                <option value='0'>SEMUA</option>
                                <option value='2'>KREDIT</option>
                                <option value='1'>NON-KREDIT</option>
                            </select>

                            <select class='btn btn-sm btn-default' id='status_aktif_select' name="status_aktif_select">
                                <option value="1" selected>Aktif</option>
                                <option value="0">Tidak Aktif</option>
                            </select>

                            <?php if (is_posisi_id() <= 3 || is_posisi_id() == 6) { ?>

                            <a href="#portlet-config" data-toggle='modal' class="btn btn-default btn-sm btn-form-add">
                                <i class="fa fa-plus"></i> Tambah
                            </a>

                            <?php } ?>
                        </div>
                        
                    </div>

                    <div class="portlet-body">
                        <div id='main-table'>
                            <table class="table table-striped table-bordered table-hover" id="general_table">
                                <thead>
                                    <tr>
                                        <th scope="col" class='status_column text-center'>
                                            Status Aktif
                                        </th>
                                        <th scope="col" class="text-center" index="1">
                                            Nama
                                        </th>
                                        <th scope="col" class="status_column text-center" index="2">
                                            Alias
                                        </th>
                                        <th scope="col" class="status_column text-center" index="3">
                                            Tipe
                                        </th>
                                        <th scope="col" class="text-center" index="4">
                                            Alamat
                                        </th>
                                        <th scope="col" class="text-center" index="5">
                                            Identitas
                                        </th>
                                        <th scope="col" class="status_column text-center">
                                            <i class='fa fa-phone-square'></i> 1
                                        </th>
                                        <th scope="col" class='status_column text-center'>
                                            <i class='fa fa-phone-square'></i>2
                                        </th>
                                        <th scope="col" class="text-center" index="8">
                                            Tempo
                                        </th>
                                        <th scope="col" class="status_column text-center" index="9">
                                            Limit Kredit
                                        </th>
                                        <th scope="col" class="text-center" index="10" style="min-width:120px !important">
                                            Source
                                        </th>
                                        <th scope="col" style="min-width:100px !important" class="text-center" index="11">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?/*foreach ($customer_list as $row) { ?>
                                    <tr>
                                        <td>
                                            <span class='id' hidden="hidden"><?=$row->id;?></span>
                                            <span class='nama'><?=$row->nama;?></span>
                                        </td>
                                        <td>
                                            <span class='alamat'><?=$row->alamat;?></span>
                                        </td>
                                        <td>
                                            <span class='kota'><?=$row->kota;?></span>
                                        </td>
                                        <td>
                                            <span class='telepon1'><?=$row->telepon1;?></span>
                                        </td>
                                        <td>
                                            <span class='telepon2'><?=$row->telepon2;?></span>
                                        </td>
                                        <td>
                                            <span hidden class='kode_pos'><?=$row->kode_pos;?></span>
                                            <span hidden class='email'><?=$row->email;?></span>
                                            <span hidden class='npwp'><?=$row->npwp;?></span>
                                            <a href='#portlet-config-edit' data-toggle='modal' class="btn-xs btn green btn-edit"><i class="fa fa-edit"></i> </a>
                                            <a class="btn-xs btn red btn-remove"><i class="fa fa-times"></i> </a>
                                        </td>
                                    </tr>
                                    <? } */ ?>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- popup tambah data -->
        <div class="modal fade bs-modal-lg" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="portlet-body form">
                            <form action="<?= base_url('master/customer_list_insert') ?>" class="form-horizontal" id="form_add_data" method="post" onkeydown="return event.key != 'Enter';">
                                <div class="form-body">
                                    <!-- navigasi section data  -->
                                    <ul class="nav nav-tabs">
                                        <li class="active"><a data-toggle="tab" href="#tab_datadiri">Data Diri</a></li>
                                        <li><a data-toggle="tab" href="#tab_datalain">Data Lain</a></li>
                                        <li><a data-toggle="tab" href="#tab_images">Images</a></li>
                                    </ul>

                                    <!-- data penting -->
                                    <div class="tab-content">
                                        <div id="tab_datadiri" class="tab-pane fade in active">
                                            <div class="row">
                                                <div class='col-md-6'>
                                                    <div class="form-group">
                                                        <label class="control-label col-md-3">Tipe</label>

                                                        <div class="col-md-9">
                                                            <div class="radio-list">
                                                                <label class="radio-inline">
                                                                    <input type="radio" name="tipe_company" value="" checked> Pribadi
                                                                </label>

                                                                <label class="radio-inline">
                                                                    <input type="radio" name="tipe_company" value="PT"> PT
                                                                </label>

                                                                <label class="radio-inline">
                                                                    <input type="radio" name="tipe_company" value="PT."> PT.
                                                                </label>
                                                            </div>

                                                            <div class='radio-list'>
                                                                <label class="radio-inline">
                                                                    <input type="radio" name="tipe_company" value="CV"> CV </label>
                                                                <label class="radio-inline">
                                                                    <input type="radio" name="tipe_company" value="CV."> CV. </label>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="control-label col-md-3">Nama</label>

                                                        <div class="col-md-9">
                                                            <input type="text" autocomplete='off' class="form-control" tabindex='1' name="nama" id='new-nama' />
                                                            <small>Tidak perlu input cv / pt ke dalam nama</small>
                                                            <div id='like-name' hidden>Terdaftar : <span></span></div>
                                                            <span class="help-block"></span>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="control-label col-md-3">Alias</label>

                                                        <div class="col-md-9">
                                                            <input type="text" class="form-control" tabindex='2' name="alias" autocomplete='off' />
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="control-label col-md-3">Alamat</label>

                                                        <div class="col-md-9">
                                                            <textarea class="form-control" name="alamat" rows='3' tabindex='3' id='alamat'></textarea>
                                                            <small><b>Sertakan JL. / Jln. / Jalan</b> sesuai yang tertera </small>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="control-label col-md-3">Blok</label>
                                                        <div class="col-md-9">
                                                            <table>
                                                                <tr>
                                                                    <td>
                                                                        <input type="text" class="form-control" name="blok" tabindex='4' id='blok' autocomplete='off' />
                                                                    </td>

                                                                    <td style='padding:0 10px; vertical-align:top'>
                                                                        <label class="control-label">NO</label>
                                                                    </td>

                                                                    <td>
                                                                        <input type="text" class="form-control" name="no" tabindex='5' id="no" autocomplete='off' />
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td><small>Isi <b>'-'</b> jika tidak ada</small></td>
                                                                    <td></td>
                                                                    <td><small>Isi <b>'-'</b> jika tidak ada</small></td>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="control-label col-md-3">RT/RW</label>

                                                        <div class="col-md-9">
                                                            <table>
                                                                <tr>
                                                                    <td>
                                                                        <input type="text" class="form-control" name="rt" tabindex='6' id='rt' autocomplete='off' maxlength="3" />
                                                                    </td>

                                                                    <td style='padding:0 10px; font-size:1.5em'>/</td>

                                                                    <td>
                                                                        <input type="text" class="form-control" name="rw" tabindex='7' id='rw' autocomplete='off' maxlength="3" />
                                                                    </td>
                                                                </tr>

                                                                <tr>
                                                                    <td><small>Isi <b>'000'</b> jika tidak ada</small></td>
                                                                    <td></td>
                                                                    <td><small>Isi <b>'000'</b> jika tidak ada</small></td>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="control-label col-md-3">Kelurahan</label>

                                                        <div class="col-md-9">
                                                            <input type="text" class="form-control" name="kelurahan" tabindex='8' id='kelurahan' autocomplete='off' />
                                                            <small>Isi <b>'-'</b> jika tidak ada</small>
                                                        </div>
                                                    </div>

                                                    
                                                    
                                                </div>
                                                
                                                <div class='col-md-6'>
                                                    <div class="form-group">
                                                        <label class="control-label col-md-3">Kecamatan</label>
    
                                                        <div class="col-md-9">
                                                            <input type="text" class="form-control" name="kecamatan" tabindex='9' id='kecamatan' autocomplete='off' />
                                                            <small>Isi <b>'-'</b> jika tidak ada</small>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="control-label col-md-3">Kab/Kota</label>

                                                        <div class="col-md-9">
                                                            <input type="text" class="form-control" name="kota" tabindex='10' id='kota' autocomplete='off' />
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="control-label col-md-3">Provinsi</label>

                                                        <div class="col-md-9">
                                                            <input type="text" class="form-control" name="provinsi" tabindex='11' id='provinsi' autocomplete='off' />
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="control-label col-md-3">Kode Pos</label>

                                                        <div class="col-md-9">
                                                            <input type="text" class="form-control" name="kode_pos" maxlength='5' tabindex='11' id='kode_pos' />
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="control-label col-md-3">NPWP</label>

                                                        <div class="col-md-9">
                                                            <input type="text" class="form-control mask-npwp" id='npwp-add' name="npwp" maxlength='20' tabindex='12' id='npwp' autocomplete='off' />
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="control-label col-md-3">NIK</label>

                                                        <div class="col-md-9">
                                                            <input type="text" style='color:black' class="form-control mask-nik" id='nik-add' maxlength='19' name="nik" tabindex='13' id='nik' autocomplete="off" />
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="control-label col-md-3"> Telepon</label>

                                                        <div class="col-md-9">
                                                            <input type="text" class="form-control" name="telepon1" tabindex='16' autocomplete='off' />
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="control-label col-md-3">Nama UP</label>

                                                        <div class="col-md-9">
                                                            <input type="text" class="form-control" id='kontak' maxlength='100' name="contact_person" tabindex='14' />
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="control-label col-md-3"> NO HP</label>

                                                        <div class="col-md-9">
                                                            <input type="text" class="form-control" name="telepon2" tabindex='16' autocomplete='off' />
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="control-label col-md-3">Email</label>

                                                        <div class="col-md-9">
                                                            <input type="text" class="form-control" name="email" tabindex='15' id='email' autocomplete='off' />
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>

                                        <!-- tab data lain -->
                                        <div id="tab_datalain" class="tab-pane fade">
                                            <div class="row">
                                                <div class='col-md-5'>
                                                    <div class="form-group" hidden>
                                                        <label class="control-label col-md-3">Tipe</label>

                                                        <div class="col-md-9">
                                                            <div class="radio-list">
                                                                <label class="radio-inline">
                                                                    <input name='customer_type_id' type='radio' value='1' checked>CASH
                                                                </label>

                                                                <label class="radio-inline">
                                                                    <input name='customer_type_id' type='radio' value='2'>KREDIT
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="control-label col-md-3">Tempo</label>

                                                        <div class="col-md-9">
                                                            <input type="text" readonly class="form-control kredit-field" style='width:120px; display:inline' name="tempo_kredit" autocomplete='off' /> <b>hari</b>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="control-label col-md-3">Reminder</label>

                                                        <div class="col-md-9">
                                                            <input type="text" readonly class="form-control kredit-field" name="warning_kredit" style='width:120px; display:inline;' autocomplete='off' /> <b>hari</b>
                                                            <small class='limit-text'></small>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class='col-md-7'>
                                                    <div class="form-group">
                                                        <label class="control-label col-md-4">Limit</label>

                                                        <div class="col-md-8">
                                                            <input type="text" readonly class="form-control amount-number kredit-field" name="limit_amount" style='width:120px; display:inline;' autocomplete='off' />
                                                            <small class='limit-text'></small>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="control-label col-md-4">Tipe Warning</label>

                                                        <div class="col-md-8">
                                                            <div class="radio-list">
                                                                <label class="radio-inline">
                                                                    <input name='limit_warning_type' class='kredit-field' disabled type='radio' value='1'>Persen
                                                                </label>

                                                                <label class="radio-inline">
                                                                    <input name='limit_warning_type' class='kredit-field' disabled type='radio' value='2'>Rupiah
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="control-label col-md-4">Ketika Mencapai</label>

                                                        <div class="col-md-8">
                                                            <span class='rp-field' style="font-weight:bold" hidden>Rp.</span> <input type="text" readonly class="form-control kredit-field" style='width:120px; display:inline;' name="limit_warning_amount" autocomplete='off' /> <span class='percent-field' style="font-weight:bold">%</span>
                                                            <small class='limit-warning-text'></small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <hr>

                                            <div class="row">
                                                <div class="col-md-8">
                                                    <div class="form-group">
                                                        <label class="control-label col-md-3">Medsos Link</label>

                                                        <div class="col-md-9">
                                                            <textarea class="form-control" name="medsos_link" rows='4' id='medsos_link'></textarea>
                                                            <small>Contoh : Instagram, Facebook, Twitter, dll. </small>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-4"></div>
                                            </div>
                                        </div>

                                        <div id="tab_images" class="tab-pane fade">
                                            <div class="row">
                                                <div class='col-md-6 pict-id-container'>
                                                    <h3>Upload file NPWP di sini!</h3>

                                                    <div style="width: 200px; height: 200px; border:1px solid #ddd">
                                                        <img src='<?= base_url('image/blank.png'); ?>' width="200px" height="200px" id='customer-id-img' alt="Image preview...">
                                                    </div>
                                                    <hr>
                                                    <input type="file" id="changepict" onchange="previewFile(this)" class="hidden" accept=".jpg,.jpeg,.png" />
                                                    <label class='btn btn-xs btn-primary' for="changepict"><i class='fa fa-picture-o'></i> Select file</label>
                                                    <button style='display:none' type='button' onclick="removePictAdd()" class='btn btn-xs red btn-pict-remove'><i class='fa fa-times'></i> Remove</button>
                                                    <textarea hidden name='pict_data' id='pict-data'></textarea>
                                                </div>

                                                <div class='col-md-6 pict-id-container'>
                                                    <h3>Upload file KTP di sini!</h3>

                                                    <div style="width: 200px; height: 200px; border:1px solid #ddd">
                                                        <img src='<?= base_url('image/blank.png'); ?>' width="200px" height="200px" id='customer-id-ktp' alt="Image preview...">
                                                    </div>
                                                    <hr>
                                                    <input type="file" id="changepictKTP" onchange="previewFileKTP(this)" class="hidden" accept=".jpg,.jpeg,.png" />
                                                    <label class='btn btn-xs btn-primary' for="changepictKTP"><i class='fa fa-picture-o'></i> Select file</label>
                                                    <button style='display:none' type='button' onclick="removePictAddKTP()" class='btn btn-xs red btn-pict-remove-ktp'><i class='fa fa-times'></i> Remove</button>
                                                    <textarea hidden name='pict_data_ktp' id='pict-data-ktp'></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn blue btn-active btn-save">Save</button>
                        <button type="button" class="btn default btn-close" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- popup edit -->
        <div class="modal fade bs-modal-lg" id="portlet-config-edit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="portlet-body form">
                            <form action="<?= base_url('master/customer_list_update') ?>" autocomplete="off" class="form-horizontal" id="form_edit_data" method="post" onkeydown="return event.key != 'Enter';">
                                <div class="form-body">
                                    <!-- navigasi section data  -->
                                    <ul class="nav nav-tabs">
                                        <li class="active"><a data-toggle="tab" href="#tab_datadiri_edit">Data Diri</a></li>
                                        <li><a data-toggle="tab" href="#tab_datalain_edit">Data Lain</a></li>
                                        <li><a data-toggle="tab" href="#tab_images_edit">Images</a></li>
                                    </ul>

                                    <!-- data penting -->
                                    <div class="tab-content">
                                        <div id="tab_datadiri_edit" class="tab-pane fade in active">
                                            <div class="row">
                                                <div class=' col-md-6'>
                                                    <div class="form-group">
                                                        <label class="control-label col-md-3">Tipe
                                                        </label>
                                                        <div class="col-md-9">
                                                            <div class="radio-list">
                                                                <label class="radio-inline">
                                                                    <input type="radio" name="tipe_company" value="" checked> Pribadi
                                                                </label>

                                                                <label class="radio-inline">
                                                                    <input type="radio" name="tipe_company" value="PT"> PT
                                                                </label>

                                                                <label class="radio-inline">
                                                                    <input type="radio" name="tipe_company" value="PT."> PT.
                                                                </label>
                                                            </div>

                                                            <div class='radio-list'>
                                                                <label class="radio-inline">
                                                                    <input type="radio" name="tipe_company" value="CV"> CV
                                                                </label>

                                                                <label class="radio-inline">
                                                                    <input type="radio" name="tipe_company" value="CV."> CV.
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="control-label col-md-3">Nama</label>

                                                        <div class="col-md-9">
                                                            <input type="text" class="form-control" name="nama" tabindex='1' autocomplete='off' />
                                                            <small>Tidak perlu input cv / pt ke dalam nama</small>
                                                            <span class="help-block"></span>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="control-label col-md-3">Alias</label>

                                                        <div class="col-md-9">
                                                            <input type="text" class="form-control" tabindex='2' name="alias" autocomplete='off' />
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="control-label col-md-3">Alamat</label>

                                                        <div class="col-md-9">
                                                            <textarea class="form-control" name="alamat" rows='3' tabindex='3'></textarea>
                                                            <small><b>Sertakan JL. / Jln. / Jalan</b> sesuai yang tertera </small>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="control-label col-md-3">Blok</label>

                                                        <div class="col-md-9">
                                                            <table>
                                                                <tr>
                                                                    <td>
                                                                        <input type="text" class="form-control" name="blok" tabindex='4' autocomplete='off' />
                                                                    </td>

                                                                    <td style='padding:0 10px; vertical-align:top'>
                                                                        <label class="control-label">NO</label>
                                                                    </td>

                                                                    <td>
                                                                        <input type="text" class="form-control" name="no" tabindex='5' autocomplete='off' />
                                                                    </td>
                                                                </tr>

                                                                <tr>
                                                                    <td><small>Isi <b>'-'</b> jika tidak ada</small></td>
                                                                    <td></td>
                                                                    <td><small>Isi <b>'-'</b> jika tidak ada</small></td>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="control-label col-md-3">RT/RW</label>

                                                        <div class="col-md-9">
                                                            <table>
                                                                <tr>
                                                                    <td>
                                                                        <input type="text" class="form-control" name="rt" tabindex='6' autocomplete='off' maxlength="3" />
                                                                    </td>

                                                                    <td style='padding:0 10px; font-size:1.5em'>/</td>

                                                                    <td>
                                                                        <input type="text" class="form-control" name="rw" tabindex='7' autocomplete='off' maxlength="3" />
                                                                    </td>
                                                                </tr>

                                                                <tr>
                                                                    <td><small>Isi <b>'000'</b> jika tidak ada</small></td>
                                                                    <td></td>
                                                                    <td><small>Isi <b>'000'</b> jika tidak ada</small></td>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class='col-md-6'>
                                                    <div class="form-group">
                                                        <label class="control-label col-md-3">Kelurahan</label>

                                                        <div class="col-md-9">
                                                            <input type="text" class="form-control" name="kelurahan" tabindex='8' autocomplete='off' />
                                                            <small>Isi <b>'-'</b> jika tidak ada</small>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="control-label col-md-3">Kecamatan</label>

                                                        <div class="col-md-9">
                                                            <input type="text" class="form-control" name="kecamatan" tabindex='9' autocomplete='off' />
                                                            <small>Isi <b>'-'</b> jika tidak ada</small>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="control-label col-md-3">Kab/Kota</label>

                                                        <div class="col-md-9">
                                                            <input type="text" class="form-control" name="kota" tabindex='10' autocomplete='off' />
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="control-label col-md-3">Provinsi</label>

                                                        <div class="col-md-9">
                                                            <input type="text" class="form-control" name="provinsi" tabindex='11' autocomplete='off' />
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="control-label col-md-3">Kode Pos</label>

                                                        <div class="col-md-9">
                                                            <input type="text" class="form-control" name="kode_pos" maxlength='5' tabindex='11' autocomplete='off' />
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="control-label col-md-3">NPWP</label>

                                                        <div class="col-md-9">
                                                            <input type="text" class="form-control mask-npwp-edit" id='npwp-edit' maxlength='20' name="npwp" tabindex='12' autocomplete='off' />
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="control-label col-md-3">NIK</label>

                                                        <div class="col-md-9">
                                                            <input type="text" class="form-control mask-nik-edit" id='nik-edit' maxlength='19' name="nik" tabindex='13' autocomplete='off' />
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="control-label col-md-3">Kontak</label>

                                                        <div class="col-md-9">
                                                            <input type="text" class="form-control" id='kontak' maxlength='100' name="kontak" id='kontak' tabindex='14' />
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="control-label col-md-3">Email</label>

                                                        <div class="col-md-9">
                                                            <input type="text" class="form-control" name="email" tabindex='15' autocomplete='off' />
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="control-label col-md-3">Telepon</label>

                                                        <div class="col-md-9">
                                                            <input type="text" class="form-control" name="telepon1" tabindex='16' autocomplete='off' />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div id="tab_datalain_edit" class="tab-pane fade">
                                            <div class="row">
                                                <div class='col-md-5'>
                                                    <div class="form-group" hidden>
                                                        <label class="control-label col-md-3">Tipe</label>

                                                        <div class="col-md-9">
                                                            <div class="radio-list">
                                                                <label class="radio-inline">
                                                                    <input name='customer_type_id' type='radio' value='1' checked>CASH
                                                                </label>

                                                                <label class="radio-inline">
                                                                    <input name='customer_type_id' type='radio' value='2'>KREDIT
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="control-label col-md-3">Tempo</label>

                                                        <div class="col-md-9">
                                                            <input type="text" readonly class="form-control kredit-field" style='width:120px; display:inline' name="tempo_kredit" autocomplete='off' /> <b>hari</b>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="control-label col-md-3">Reminder</label>

                                                        <div class="col-md-9">
                                                            <input type="text" readonly class="form-control kredit-field" name="warning_kredit" style='width:120px; display:inline;' autocomplete='off' /> <b>hari</b>
                                                            <small class='limit-text'></small>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class='col-md-7'>
                                                    <div class="form-group">
                                                        <label class="control-label col-md-4">Limit</label>

                                                        <div class="col-md-8">
                                                            <input type="text" readonly class="form-control amount-number kredit-field" name="limit_amount" style='width:120px; display:inline;' autocomplete='off' />
                                                            <small class='limit-text'></small>
                                                            <br /><span class='sisa-piutang-info'></span>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="control-label col-md-4">Tipe Warning</label>

                                                        <div class="col-md-8">
                                                            <div class="radio-list">
                                                                <label class="radio-inline">
                                                                    <input name='limit_warning_type' class='kredit-field' disabled type='radio' value='1'>Persen
                                                                </label>

                                                                <label class="radio-inline">
                                                                    <input name='limit_warning_type' class='kredit-field' disabled type='radio' value='2'>Rupiah
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="control-label col-md-4">Ketika Mencapai</label>

                                                        <div class="col-md-8">
                                                            <span class='rp-field' style="font-weight:bold" hidden>Rp.</span> <input type="text" readonly class="form-control kredit-field" style='width:120px; display:inline;' name="limit_warning_amount" autocomplete='off' /> <span class='percent-field' style="font-weight:bold">%</span>
                                                            <small class='limit-warning-text'></small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <hr>

                                            <div class="row">
                                                <div class="col-md-8">
                                                    <div class="form-group">
                                                        <label class="control-label col-md-3">Medsos Link</label>

                                                        <div class="col-md-9">
                                                            <textarea class="form-control" name="medsos_link" rows='4' id='medsos_link'></textarea>
                                                            <small>Contoh : Instagram, Facebook, Twitter, dll. </small>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-4"></div>
                                            </div>
                                        </div>

                                        <div id="tab_images_edit" class="tab-pane fade">
                                            <input name='customer_id' hidden>

                                            <div class='row'>
                                                <div class='col-md-6 pict-id-container'>
                                                    <h3>Upload file NPWP di sini!</h3>

                                                    <input name='npwp_link' hidden>

                                                    <div class='col-md-12 pict-id-container'>
                                                        <div style="width: 200px; height: 200px; border:1px solid #ddd">
                                                            <img src='' style="width: 200px; height: 200px;" id='customer_id_img_edit' name='customer_id_img_edit' alt="Image preview...">
                                                        </div>
                                                        <hr>
                                                        <input type="file" id="changepict_edit" onchange="previewFileEdit(this)" accept=".jpg,.jpeg,.png" class="hidden" />
                                                        <label class='btn btn-xs btn-primary' for="changepict_edit"><i class='fa fa-picture-o'></i> Select File</label>
                                                        <button style='display:none' type='button' onclick="removePictEdit()" class='btn btn-xs red btn-pict-remove-edit'><i class='fa fa-times'></i> Remove</button>
                                                        <textarea <?= (is_posisi_id() != 1 ? 'hidden' : ''); ?> name='pict_data_edit' id='pict_data_edit'></textarea>
                                                    </div>
                                                </div>

                                                <div class='col-md-6 pict-id-container'>
                                                    <h3>Upload file KTP di sini!</h3>

                                                    <input name='ktp_link' hidden>

                                                    <div class='col-md-12 pict-id-container'>
                                                        <div style="width: 200px; height: 200px; border:1px solid #ddd">
                                                            <img src='' style="width: 200px; height: 200px;" id='customer_id_img_edit_ktp' alt="Image preview...">
                                                        </div>
                                                        <hr>
                                                        <input type="file" id="changepict_edit_ktp" onchange="previewFileEditKTP(this)" accept=".jpg,.jpeg,.png" class="hidden" />
                                                        <label class='btn btn-xs btn-primary' for="changepict_edit_ktp"><i class='fa fa-picture-o'></i> Select File</label>
                                                        <button style='display:none' type='button' onclick="removePictEditKTP()" class='btn btn-xs red btn-pict-remove-edit-ktp'><i class='fa fa-times'></i> Remove</button>
                                                        <textarea <?= (is_posisi_id() != 1 ? 'hidden' : ''); ?> name='pict_data_edit_ktp' id='pict_data_edit_ktp'></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
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

<script type="text/javascript" src="<?php echo base_url('assets/global/plugins/jquery-inputmask/jquery.inputmask.bundle.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/global/plugins/jquery-validation/js/jquery.validate.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/global/plugins/jquery-validation/js/additional-methods.min.js'); ?>"></script>

<script src="<?php echo base_url('assets/global/plugins/datatables/media/js/jquery.dataTables.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js'); ?>"></script>

<script src="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/bootbox/bootbox.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets_noondev/js/table-advanced.js'); ?>"></script>
<script src="<?php echo base_url('assets_noondev/js/fnReload.js'); ?>"></script>
<script src="<?php echo base_url('assets/global/plugins/dropzone/dropzone.js'); ?>"></script>
<script src="<?php echo base_url('assets_noondev/js/form-customer.js'); ?>"></script>

<script>
const posisi_id = "<?is_posisi_id();?>";
jQuery(document).ready(function() {

    <?if (!$isMobileAccess) {?>
        FormAddCustomer.init();
        FormEditCustomer.init();
    <?}?>

    var loadImage = function(event) {
        var output = document.getElementById('img_logo');
        output.src = URL.createObjectURL(event.target.files[0]);
        document.getElementById('txtFileLogo').value = event.target.files[0].name;
    };


    let customer_id_last = "<?= $customer_id_last ?>";

    $(document).on('click','.radio-source',function(){
        const id = $(this).data('id');
        const source = $(this).val();

        if(source == 'other'){
            $(`#inputSourceDetail${id}`).show();
        }else{
            $(`#inputSourceDetail${id}`).hide();
        }

    });


    $("#general_table").DataTable({
        "fnCreatedRow": function(nRow, aData, iDataIndex) {
            const other_data = $('td:eq(11)', nRow).text().split('-?-');
            
            const limit_data = $('td:eq(9)', nRow).text().split(',');
            let kode_pos = other_data[0];
            if (kode_pos == null) {
                kode_pos = '';
            }
            var email = other_data[1];
            if (email == null) {
                email = '';
            }
            var npwp = other_data[2];
            if (npwp == null) {
                npwp = '';
            }
            var status_aktif = $('td:eq(0)', nRow).text();
            var id = other_data[4];
            var customer_type_id = other_data[5];
            var nik = other_data[6];
            if (nik == null) {
                nik = '';
            }

            var tipe_company = other_data[8];
            var medsos_link = other_data[9];
            var npwp_link = other_data[10];
            var ktp_link = other_data[11];
            var locked_status = other_data[12];
            var kontak = other_data[13];

            var data_alamat = $('td:eq(4)', nRow).text().split('?');
            var alamat = '';
            alamat += `<span class='alamat'>${data_alamat[0]}</span>`;
            alamat += ` BLOK : <span class='blok' ${(data_alamat[1] == '' ? 'hidden' : '')} >${data_alamat[1]}</span>`;
            alamat += ` NO : <span class='no'>${data_alamat[2]}</span>`;
            alamat += ` RT/RW: <span class='rt'>${data_alamat[3]}</span>`;
            alamat += `/<span class='rw'>${data_alamat[4]}</span>`;
            alamat += ` Kel.<b><span class='kelurahan'>${data_alamat[6]}</span></b>`;
            alamat += ` Kec.<b><span class='kecamatan'>${data_alamat[5]}</span></b>`;

            var btn_edit = "";
            var btn_status = "";

            <?php if (is_posisi_id() <= 3 || is_posisi_id() == 6) { ?>

                btn_edit = "<a href='#portlet-config-edit' data-toggle='modal' class='btn-xs btn green btn-edit'><i class='fa fa-edit'></i> </a>";

                if (status_aktif == 1) {
                    btn_status = "<a class='btn-xs btn red btn-remove' data-status='1' ><i class='fa fa-times'></i></a>";
                    var text_aktif = 'Aktif';
                } else {
                    btn_status = "<a class='btn-xs btn blue btn-remove' data-status='0' ><i class='fa fa-play'></i></a>";
                    var text_aktif = 'Tidak Aktif';
                };

                if (locked_status == 1) {
                    // btn_edit = "<a href='#portlet-config-edit' data-toggle='modal' class='btn-xs btn green btn-edit'><i class='fa fa-edit'></i> </a>";
                } else {
                    // btn_edit = "<a href='#portlet-config-pin' data-toggle='modal' class='btn-xs btn yellow-gold btn-edit btn-open-lock'><i class='fa fa-lock'></i> </a>";
                };

            <?php }; ?>

            var limit_action = '';
            if (customer_type_id == 2) {
                limit_action = '<i style="font-size:12px">currently unavailable</i>';
                // limit_action += "Limit : <span class='limit-amount'>" + change_number_format(limit_data[0]) + "</span><br/>";
                // limit_action += "Warning @ : <span class='limit-warning-type' hidden>" + limit_data[2] + "</span>";
                // var limit_warning = (limit_data[2] == 1 ? limit_data[2] : change_number_format(limit_data[3]));
                // limit_action += ((limit_data[2] == 1) ? '' : 'Rp ') + "<span class='limit-warning-amount'>" + limit_warning + "</span>" + ((limit_data[2] == 1) ? '%' : '');
            };

            // 
            var url = "<?= base_url(is_setting_link('master/customer_profile')); ?>/" + id;


            var btn_profile = '<a class="btn-xs btn blue" href="' + url + '" onclick="window.open(this.href, \'newwindow\', \'width=1250, height=650\'); return false;"><i class="fa fa-file-archive-o"></i></a>';
            var sisa_piutang = "";

            <?php if (is_posisi_id() <= 3) { ?>

            var warna_piutang_info = '';
            // console.log(parseFloat(limit_data[3]) +'>'+ parseFloat(limit_data[2]))
            var limit_in_numbers = (limit_data[1] == 1 ? limit_data[0] * limit_data[2] / 100 : limit_data[2]);
            // console.log(limit_in_numbers);
            if (parseFloat(limit_data[3]) > parseFloat(limit_in_numbers) && parseFloat(limit_data[2]) != 0) {
                warna_piutang_info = 'color:red';
            }
            if (limit_data[4] > 0 && typeof limit_data[4] !== 'undefined') {
                limit_data[4] = change_number_format(limit_data[4]);
                sisa_piutang = `<br/><b class='sisa-piutang'>(piutang : <span style ='${warna_piutang_info}'>${limit_data[4]}</span>)</b>`;
            }
            limit_action += sisa_piutang;

            <?php } ?>

            let btn_alamat = `<a href="<?= base_url(is_setting_link('master/pengiriman_list')); ?>/` + id + `" class='btn btn-xs grey-cascade'><i class='fa fa-truck'></i></a>`;

            var action = `<span class='id kolom-id' hidden="hidden">${id}</span>
              <span class='customer_type_id' hidden>${customer_type_id}</span>
              <span class='email' hidden>${email}</span>
              <span class='npwp' hidden>${npwp}</span>
              <span class='nik' hidden>${nik}</span>
              <span class='kontak' hidden>${kontak}</span>
              <span class='medsos_link' hidden>${medsos_link}</span>
              <span class='npwp_link' hidden>${npwp_link}</span>
              <span class='ktp_link' hidden>${ktp_link}</span>
              <span class='status_aktif' hidden>${status_aktif}</span>${btn_edit}${btn_status}${btn_profile}${btn_alamat}`;

            var data_pos = $('td:eq(5)', nRow).text().split('??');
            var kota = ' Kab/Kota.<b><span class="kota">' + data_pos[0] + '</span></b>';
            var provinsi = '<b style="color:blue"><span class="provinsi">' + (typeof data_pos[1] === 'undefined' ? '' : data_pos[1]) + '</span></b>';
            kode_pos = "<b><span class='kode_pos' style='color:green'>" + kode_pos + "</span></b>";

            $('td:eq(0)', nRow).html($('td:eq(0)', nRow).text());

            $('td:eq(0)', nRow).addClass('status_column');
            $('td:eq(2)', nRow).addClass('status_column');
            $('td:eq(3)', nRow).addClass('status_column');
            $('td:eq(6)', nRow).addClass('status_column');
            $('td:eq(7)', nRow).addClass('status_column');
            $('td:eq(9)', nRow).addClass('status_column');

            // var link_img = "<span class='npwp-link'></span>";
            // if (typeof data_img["customer_" + id + ".jpeg"] !== 'undefined') {
            //     link_img = `<a target='_blank' href="${folder_dir}customer_${id}.jpeg"><img src="${folder_dir}customer_${id}.jpeg" style='max-height:70px' /></a>`;
            //     link_img += `<span  class='npwp-link' hidden>customer_${id}.jpeg</span>`;
            // };

            $('td:eq(1)', nRow).html('<span class="tipe_company">' + tipe_company + '</span> ' + ' <span class="nama">' + $('td:eq(1)', nRow).text() + '</span>');
            $('td:eq(2)', nRow).html('<span class="alias">' + $('td:eq(2)', nRow).text() + '</span>');
            // $('td:eq(4)', nRow).html('<span class="alamat">'+$('td:eq(4)', nRow).text()+'</span> '+kota+', '+provinsi+' '+kode_pos);
            $('td:eq(4)', nRow).html(alamat + kota + ', ' + provinsi + ' ' + kode_pos);
            $('td:eq(5)', nRow).html("<span class='npwp'>" + npwp + "</span><br/><span class='nik'>" + nik + "</span>");
            $('td:eq(6)', nRow).html('<span class="telepon1">' + $('td:eq(6)', nRow).text() + '</span>');
            $('td:eq(7)', nRow).html('<span class="telepon2">' + $('td:eq(7)', nRow).text() + '</span>');
            $('td:eq(8)', nRow).html('<span class="tempo_kredit">' + $('td:eq(8)', nRow).text() + '</span>');

            $('td:eq(9)', nRow).html(limit_action);
            $('td:eq(11)', nRow).html(action);

            //========================source data=============================

            const source = $('td:eq(10)', nRow).text().split('??');
            const registered = (source[0] == '-' ? '' : source[0]);
            const source_type = (source[1] == '-' ? '' : source[1]);
            const source_detail = (source[2] == '-' ? '' : source[2]);
            const reg = (registered != '' ? registered.split('-').reverse().join('/') : '');

            const source_show = `
                reg : <span id="register${id}" class='spanSource${id}'>${reg}</span> 
                <input type='text' class='registeredInput inputSource inputSource${id}' id="registerSource${id}" data-id="${id}" value='${reg}' />
                <br/>
                sumber: 
                <span id="sourceType${id}" class='spanSource${id}' >${source_type}</span>
                <div class='input-group inputSource inputSource${id}' id="radioSource${id}" style='min-width: 120px;'>
                    <label>
                        <input type='radio' class='radio-source' data-id="${id}" name='source_type' value='instagram'> Instagram</label>
                    <label>
                        <input type='radio' class='radio-source' data-id="${id}" name='source_type' value='facebook'> Facebook</label>
                    <label>
                        <input type='radio' class='radio-source' data-id="${id}" name='source_type' value='google'> Google</label>
                    <label>
                        <input type='radio' class='radio-source' data-id="${id}" name='source_type' value='website'> Website</label>
                    <label>
                        <input type='radio' class='radio-source' data-id="${id}" name='source_type' value='online-campaign'> Online Campaign</label>
                    <label>
                        <input type='radio' class='radio-source' data-id="${id}" name='source_type' value='offline-campaign'> Offline Campaign</label>
                    <label>
                        <input type='radio' class='radio-source' data-id="${id}" name='source_type' value='other' id="otherSource${id}"> Other: </label>
                    <input type='text' class='sourceDetailInput inputSource' id='inputSourceDetail${id}' value='${source_detail}' />

                </div>
                <span ${source_detail == '-' ? 'hidden' : ''} class='spanSource${id}'>${source_detail}</span>`;
            let source_btn = '';
            let cancel_btn = '';
            let source_btn_save = '';
            if(posisi_id != '6'){
                source_btn = `<br/><button class='btn default btn-xs' id="btnShowSource${id}" onclick="showFormSource('${id}','${reg}','${source_type}','${source_detail}')">edit</button>`;
                cancel_btn = `<button class='btn red btn-xs btn-cancel-source' id="btnCancelSource${id}" onclick="cancelFormSource('${id}')">cancel</button>`;
                source_btn_save = `<button class='btn green btn-xs btn-save-source' id="btnSaveSource${id}" onclick="saveFormSource('${id}')">save</button>`;
            }
            $('td:eq(10)', nRow).html(source_show+source_btn+cancel_btn+source_btn_save);

            // console.log(action);
            // $(nRow).addClass('status_aktif_'+status_aktif);

        },
        "bStateSave": true,
        "bProcessing": true,
        "bServerSide": true,
        "ordering": true,
        "pageLength": 100,
        "order": [
            [1, "asc"]
        ],
        "sAjaxSource": baseurl + "master/data_customer?customer_type_id=0",
        "aoColumnDefs": [{
            "bVisible": true,
            "aTargets": [1]
        }],
        success: function(response) {
            if (customer_id_last != '') {
                $("#general_table").find(`[data-attr="${customer_id_last}"]`).trigger("click");
            };
        }
    });

    var oTable;
    oTable = $('#general_table').dataTable();
    oTable.fnFilter(1, 0);

    $('#status_aktif_select').change(function() {
        oTable.fnFilter($(this).val(), 0);
    });

    $('#customer_type_id_filter').change(function(e) {
        var customer_type_id = $(this).val();
        oTable.fnReloadAjax(baseurl + "master/data_customer?customer_type_id=" + customer_type_id);
    });

    $('[name=customer_type_id]').change(function() {
        var form = $(this).closest('form').attr('id');
        if ($('#' + form).find('[name=customer_type_id]:checked').val() == 2) {
            $('#' + form).find('.kredit-field').prop('readonly', false);
            $('#' + form).find('.kredit-field').prop('disabled', false);
        } else {
            $('#' + form).find('.kredit-field').prop('readonly', true);
            $('#' + form).find('.kredit-field').prop('disabled', true);
        }
    });

    $(document).on('input', '#new-nama', function() {
        const nama = $(this).val();
        if (nama.length > 3) {
            let data = {};
            data['nama'] = nama;
            const url = 'master/cari_nama_mirip_customer';
            let nama_list = [];
            let set_nama = '';
            ajax_data_sync(url, data).done(function(data_respond /*,textStatus, jqXHR*/ ) {
                $.each(JSON.parse(data_respond), function(i, v) {
                    set_nama = v.nama.toUpperCase();
                    const col = (v.status_aktif == 1 ? 'color:black' : 'font-style:italic;color:#999');
                    set_nama = set_nama.replace(nama.toUpperCase(), "<b style='color:red'>" + nama.toUpperCase() + '</b>');
                    nama_list.push(`<span style="${col}">${set_nama}</span>`);
                    // nama_list.push(set_nama);
                });

                if (nama_list.length > 0) {
                    $("#like-name span").html(nama_list.join("<b style='color:red'>, </b>"));
                    $("#like-name").show();
                } else {
                    $("#like-name").hide();
                };
            });
        };
    });

    $('input[name=limit_warning_type]').change(function() {
        var form = $(this).closest('form').attr('id');
        // alert($('#'+form).find('[name=limit_warning_type]:checked').val());
        if ($('#' + form).find('[name=limit_warning_type]:checked').val() == 1) {
            $('#' + form).find('[name=limit_warning_amount]').removeClass('amount_number');
            $('#' + form).find('.percent-field').show();
            $('#' + form).find('.rp-field').hide();
        } else {
            $('#' + form).find('[name=limit_warning_amount]').addClass('amount_number');
            $('#' + form).find('.rp-field').show();
            $('#' + form).find('.percent-field').hide();
        }
    });

    // $('.btn-close').click(function() {
    //     window.location.reload();
    // })

    $('#general_table').on('click', '.btn-edit', function() {
        let form = $('#form_edit_data');
        let ini = $(this).closest('tr');
        let tipe_company = ini.find('.tipe_company').html();
        let customer_type_id = ini.find('.customer_type_id').html();
        let limit_warning_type = ini.find('.limit-warning-type').html();
        var dt_tempo_kredit = ini.find('.tempo_kredit').html().split('/');

        // alert(ini.find('.id').html());
        form.find('[name=customer_id]').val(ini.find('.id').html());
        form.find('[name=customer_type_id]').prop('checked', false);
        form.find("[name=customer_type_id][value='" + customer_type_id + "']").prop('checked', true);
        $.uniform.update(form.find("[name=customer_type_id]"));
        form.find("[name=tipe_company][value='" + tipe_company + "']").prop('checked', true);
        $.uniform.update(form.find("[name=tipe_company]"));
        form.find('[name=nama]').val(ini.find('.nama').html().replace("&amp;", '&'));
        form.find('[name=alias]').val(ini.find('.alias').html());
        form.find('[name=alamat]').val(ini.find('.alamat').html());
        form.find('[name=kota]').val(ini.find('.kota').html());
        form.find('[name=blok]').val(ini.find('.blok').html());
        form.find('[name=no]').val(ini.find('.no').html());
        form.find('[name=rt]').val(ini.find('.rt').html());
        form.find('[name=rw]').val(ini.find('.rw').html());
        form.find('[name=kecamatan]').val(ini.find('.kecamatan').html());
        form.find('[name=kelurahan]').val(ini.find('.kelurahan').html());
        form.find('[name=email]').val(ini.find('.email').html());

        form.find('[name=npwp]').val(ini.find('.npwp').html());
        form.find('[name=nik]').val(ini.find('.nik').html());
        form.find('[name=kontak]').val(ini.find('.kontak').html());
        form.find('[name=telepon1]').val(ini.find('.telepon1').html());
        form.find('[name=telepon2]').val(ini.find('.telepon1').html());
        form.find('[name=kode_pos]').val(ini.find('.kode_pos').html());
        form.find('[name=provinsi]').val(ini.find('.provinsi').html());

        form.find('[name=tempo_kredit]').val(dt_tempo_kredit[0]);
        form.find('[name=warning_kredit]').val(dt_tempo_kredit[1]);
        form.find('.sisa-piutang-info').html(ini.find('.sisa-piutang').html());
        form.find('[name=medsos_link]').val(ini.find('.medsos_link').html());

        //show image npwp
        var npwp_link = ini.find('.npwp_link').html();
        form.find('[name=npwp_link]').val(npwp_link);

        if (npwp_link == '' && npwp_link == '-') {
            $(".btn-pict-remove-edit").hide();
        } else {
            var npwp_link_jadi = '';

            if (ini.find('.npwp_link').html() != '') {
                npwp_link_jadi = baseurl + "image/customer/customer_" + ini.find('.id').html() + '/' + npwp_link;
            } else {
                npwp_link_jadi = baseurl + "image/blank.png";
            }

            $("#customer_id_img_edit").attr('src', npwp_link_jadi);
            $(".btn-pict-remove-edit").show();
        };

        //show image ktp
        var ktp_link = ini.find('.ktp_link').html();
        form.find('[name=ktp_link]').val(ktp_link);

        if (ktp_link == '' && ktp_link == '-') {
            $(".btn-pict-remove-edit-ktp").hide();
        } else {
            var ktp_link_jadi = '';

            if (ini.find('.ktp_link').html() != '') {
                ktp_link_jadi = baseurl + "image/customer/customer_" + ini.find('.id').html() + '/' + ktp_link;
            } else {
                ktp_link_jadi = baseurl + "image/blank.png";
            }

            $("#customer_id_img_edit_ktp").attr('src', ktp_link_jadi);
            $(".btn-pict-remove-edit-ktp").show();
        };

        

        if (customer_type_id == 2) {
            // alert(limit_warning_type);
            form.find('[name=limit_amount]').val(ini.find('.limit-amount').html());
            // form.find('[name=limit_warning_type]').val(limit_warning_type);
            form.find('[name=limit_warning_amount]').val(ini.find('.limit-warning-amount').html());

            form.find("[name=limit_warning_type]").prop("checked", false);
            $.uniform.update(form.find('[name=limit_warning_type]'));
            form.find("[name=limit_warning_type][value='" + limit_warning_type + "']").prop("checked", true);
            $.uniform.update(form.find('[name=limit_warning_type]'));
            form.find('[name=limit_warning_type]').change();

            // $("#radio_1").prop("checked", true);


        } else {
            form.find('.kredit-field').prop('readonly', true);
            form.find('.kredit-field').prop('disabled', true);
        }

        if (customer_type_id == 2 && limit_warning_type == 1) {
            form.find('[name=limit_warning_amount]').removeClass('amount-number');
            form.find('.percent-field').show();
            form.find('.rp-field').hide();
        } else if (customer_type_id == 2 && limit_warning_type == 2) {
            form.find('[name=limit_warning_amount]').addClass('amount-number');
            form.find('.rp-field').show();
            form.find('.percent-field').hide();
        }

        form.find('.kredit-field').prop('readonly', false);
        form.find('.kredit-field').prop('disabled', false);

    });

    $('.btn-save').click(function() {
        $('btn-save').text('saving...');
        $('btn-save').attr('disabled', true);

        $.ajax({
            url: "<?= base_url('master/customer_validate_start/new/0') ?>",
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
        id = $('#form_edit_data [name=customer_id]').val();

        //validasi
        $('btn-save').text('saving...');
        $('btn-save').attr('disabled', true);

        $.ajax({
            url: "<?= base_url('master/customer_validate_start/edit/'); ?>" + '/' + id,
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

    $('#general_table').on('click', '.btn-remove', function() {
        var data = status_aktif_get($(this).closest('tr')) + '=?=customer';
        var nama = $(this).closest('tr').find('.nama').html();
        var status = $(this).attr("data-status");
        if (status == 0) {
            var text = "mengaktifkan (<b>periksa nik/npwp sebelum menggunakan</b>)";
        } else {
            var text = "menonaktifkan";
        };
        bootbox.confirm("Yakin untuk " + text + " customer <b>" + nama + "</b> ?", function(respond) {
            if (respond) {
                window.location.replace(baseurl + "master/ubah_status_aktif?data_sent=" + data + '&link=customer_list');
            };
        });

    });

    //=================================open pin=============================================
    $("#general_table").on("click", ".btn-open-lock", function() {
        setTimeout(function() {
            $(".pin_user").val('');
            $(".pin_user").focus();
        }, 700);
    });

    $('.btn-request-open').click(function() {
        if (cek_pin($('#form-request-open'))) {
            $('#portlet-config-pin').modal('toggle');
            $('#portlet-config-edit').modal('toggle');
        }
    });

    $('.pin_user').keypress(function(e) {
        var form = '#' + $(this).closest('form').attr('id');
        var obj_form = $(form);
        if (e.which == 13) {
            if (cek_pin(obj_form)) {
                $('#portlet-config-pin').modal('toggle');
                $('#portlet-config-edit').modal('toggle');
            }
        }
    });

    //=================================on enter=============================================
    var map = {
        13: false
    };

    $("form").on('keydown', 'input', function(e) {
        var form = $(this).closest('form');

        if (e.keyCode in map) {
            map[e.keyCode] = true;
            if (map[13]) {
                var tabindex = $(this).attr('tabindex');
                tabindex++;
                // console.log(tabindex);
                form.find('[tabindex=' + tabindex + ']').focus();
                // document.forms[idx].elements[2].focus();
            }
        }
    }).keyup(function(e) {
        if (e.keyCode in map) {
            map[e.keyCode] = false;
        }
    });

    // $("#custImgAdd").on('change', 'img', function() {
    //     // alert();
    //     var src = $(this).attr('src');
    //     alert(src);
    //     $('#form_add_data').find('[name=pict_data]').val(src);
    // });
});


function cek_pin(form) {
    var data = {};
    data['pin'] = form.find('.pin_user').val();
    var url = 'transaction/cek_pin';

    var result = ajax_data(url, data);
    if (result == 'OK') {
        return true;
    };
}

///////////////////////////////////////
//operation for image NPWP
///////////////////////////////////////
function previewFile(el) {
    var preview = document.getElementById('customer-id-img');
    var textarea = document.getElementById('pict-data');
    var file = el.files[0];
    var reader = new FileReader();

    reader.addEventListener("load", function() {
        console.log(reader);
        preview.src = reader.result;
        textarea.value = reader.result;
    }, false);

    if (el.files[0].size / 1024 / 1024 > 1) {
        alert("Ukuran File > 1 MB, disarankan untuk menggunakan file yang lebih kecil")
    }
    if (file) {
        reader.readAsDataURL(file);
        // $('#changepict').hide();
        $('.btn-pict-remove').show();
    }
}

function removePictAdd() {
    $("#changepict").val('');
    $("#customer-id-img").attr("src", '<?= base_url('image/blank.png'); ?>');
    $("#pict-data").val("");
    $('.btn-pict-remove').hide();
}

////////////////////////////////////
// operation for image ktp 
////////////////////////////////////
function previewFileKTP(el) {
    var preview = document.getElementById('customer-id-ktp');
    var textarea = document.getElementById('pict-data-ktp');
    var file = el.files[0];
    var reader = new FileReader();

    reader.addEventListener("load", function() {
        console.log(reader);
        preview.src = reader.result;
        textarea.value = reader.result;
    }, false);

    if (el.files[0].size / 1024 / 1024 > 1) {
        alert("Ukuran File > 1 MB, disarankan untuk menggunakan file yang lebih kecil")
    }
    if (file) {
        reader.readAsDataURL(file);
        // $('#changepict').hide();
        $('.btn-pict-remove-ktp').show();
    }
}

function removePictAddKTP() {
    $("#changepictktp").val('');
    $("#customer-id-ktp").attr("src", '<?= base_url('image/blank.png'); ?>');
    $("#pict-data-ktp").val("");
    $('.btn-pict-remove-ktp').hide();
}

////////////////////////////////////
// edit image npwp 
////////////////////////////////////
function previewFileEdit(el) {
    var preview = document.getElementById('customer_id_img_edit');
    var textarea = document.getElementById('pict_data_edit');
    var file = el.files[0];
    var reader = new FileReader();

    reader.addEventListener("load", function() {
        preview.src = reader.result;
        textarea.value = reader.result;
    }, false);

    if (file) {
        reader.readAsDataURL(file);
        $('.btn-pict-remove-edit').show();
    }
}

function removePictEdit() {
    $("#changepict_edit").val('');
    $("#customer_id_img_edit").attr("src", '<?= base_url('image/blank.png'); ?>');
    $("#pict_data_edit").val("");
    $('.btn-pict-remove-edit').hide();
    $('#form_edit_data [name=npwp_link]').val('');
}

////////////////////////////////////
// edit image ktp 
////////////////////////////////////
function previewFileEditKTP(el) {
    var preview = document.getElementById('customer_id_img_edit_ktp');
    var textarea = document.getElementById('pict_data_edit_ktp');
    var file = el.files[0];
    var reader = new FileReader();

    reader.addEventListener("load", function() {
        preview.src = reader.result;
        textarea.value = reader.result;
    }, false);

    if (file) {
        reader.readAsDataURL(file);
        $('.btn-pict-remove-edit-ktp').show();
    }
}

function removePictEditKTP() {
    $("#changepict_edit_ktp").val('');
    $("#customer_id_img_edit_ktp").attr("src", '<?= base_url('image/blank.png'); ?>');
    $("#pict_data_edit_ktp").val("");
    $('.btn-pict-remove-edit-ktp').hide();
    $('#form_edit_data [name=ktp_link]').val('');
}

function showFormSource(id, reg, source_type, source_detail) {
    $(`#btnCancelSource${id}`).show();
    $(`#btnSaveSource${id}`).show();
    
    $(`#btnShowSource${id}`).hide();
    $(".spanSource" + id).hide();
    $(`.inputSource${id}`).show();
    $(`#registerSource${id}`).datepicker({
        autoclose : true,
        format: "dd/mm/yyyy"
    });


    if(reg == ''){
        $(`#registerSource${id}`).val("<?=date('d/m/Y');?>");
    }

    if(source_type != ''){
        $(`#radioSource${id} input[value=${source_type}]`).prop('checked', true);
        if(source_type == 'other'){            
            $(`#inputSourceDetail${id}`).show().val(source_detail);
        }
    }
}

function cancelFormSource(id) {
    $(`#btnShowSource${id}`).show();
    $(`#btnSaveSource${id}`).hide();
    $(`#btnCancelSource${id}`).hide();
    $(`.inputSource${id}`).hide();
    $(`.spanSource${id}`).show();
}

function saveFormSource(id) {
    const source = $(`.inputSource${id} input[name=source_type]:checked`).val();
    const registered = $(`#registerSource${id}`).val();
    let source_detail = $(`#inputSourceDetail${id}`).val();


    if (source == undefined) {
        bootbox.alert('Mohon pilih sumber');
        return false;
    } else if (registered == '') {
        alert('Tanggal registrasi harus diisi!');
        return false;
    } else if (source == 'other' && source_detail == undefined) {
        bootbox.alert('Sumber lain harus diisi');
        return false;
    } else if(source != 'other'){
        source_detail = '';
    }

    let dataSource = {};
    dataSource['customer_id'] = id;
    dataSource['source_type'] = source;
    dataSource['registered_date'] = registered;
    dataSource['source_detail'] = source_detail;

    const url = 'master/update_source_customer';
    ajax_data_sync(url, dataSource).done(function(data_respond) {
        const res = JSON.parse(data_respond);
        if (res.status) {
            notific8('lime','Data berhasil disimpan!');
            location.reload();
        } else {
            bootbox.alert('Gagal menyimpan data!');
        }
    });
    /* $(`#btnSaveSource${id}`).hide();    
    $(`.inputSource${id}`).hide();
    $(`.inputSource${id} .source`).html(source);
    $(`.inputSource${id} .source_detail`).html(source_detail); */
};

</script>

<?if ($isMobileAccess) {?>
    <!-- mask -->
    <script src="<?= base_url('assets_noondev/js/mask/jquery.mask.js') ?>"></script>
    
    <script>
    $(document).ready(function() {

            
          //format textbox
          $('#npwp-add').mask('00.000.000.0-000.000', {
                placeholder: "00.000.000.0-000.000"
          });
    
          $('#npwp-edit').mask('00.000.000.0-000.000', {
                placeholder: "00.000.000.0-000.000"
          });
    
          $('#nik-add').mask('0000 0000 0000 0000', {
                placeholder: "0000 0000 0000 0000"
          });
    
          $('#nik-edit').mask('0000 0000 0000 0000', {
                placeholder: "0000 0000 0000 0000"
          });
    });
    </script>
<?}?>