<!-- ------------------------------------------------- -->
<!-- load library -->
<!-- ------------------------------------------------- -->
<link rel="stylesheet" type="text/css" href="<?= base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css'); ?>" />
<link rel="stylesheet" type="text/css" href="<?= base_url('assets/global/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css'); ?>" />
<link href="<?= base_url('assets/global/plugins/bootstrap-modal/css/bootstrap-modal.css'); ?>" rel="stylesheet" type="text/css" />
<?= link_tag('assets/global/plugins/select2/select2.css'); ?>

<link rel="stylesheet" type="text/css" href="<?= base_url('assets/css/map_pengeluaran_rupa.css'); ?>" />
<link rel="stylesheet" type="text/css" href="<?= base_url('assets/css/map_style.css'); ?>" />

<!-- ------------------------------------------------- -->
<!-- get data from server -->
<!-- ------------------------------------------------- -->
<?php
// get header
$id_header = 0;
$tanggal = date('d/m/Y');
$no_faktur = '';
$no_faktur_lengkap = '';
$catatan = '';
$status_aktif  = '';
$status = -99;
$status_icon = 'fa fa-ban';

foreach ($header as $row) {
      $id_header = $row->id;
      $tanggal = is_reverse_date($row->tanggal);
      $no_faktur = $row->no_faktur;
      $no_faktur_lengkap = $row->no_faktur_lengkap;
      $catatan = $row->keterangan;
      $status_aktif = $row->status_aktif;
      $status = $row->status;
}

//button set
$disabled_control = $status_aktif == 1 && $status == 1 ? '' : 'disabled';

if ($status_aktif == 1 && $status == 1)
      $status_icon = 'fa fa-unlock';

if ($status_aktif == 1 && $status == 0)
      $status_icon = 'fa fa-lock';

if ($status_aktif != 1)
      $status_icon = 'fa fa-exclamationc-circle';

$disabled_lock = '';
?>

<!-- ------------------------------------------------- -->
<!-- main -->
<!-- ------------------------------------------------- -->
<div class="page-content">
      <div class='container'>
            <div class="row margin-top-10">
                  <div class="col-md-12">
                        <div class="portlet light">
                              <div class="portlet-title">
                                    <div class="caption caption-md">
                                          <i class="icon-bar-chart theme-font hide"></i>
                                          <span class="caption-subject theme-font bold uppercase"><?= $breadcrumb_small; ?></span>
                                    </div>

                                    <div class="actions hidden-print">
                                          <a href="#portlet-config" data-toggle='modal' class="btn btn-default btn-xs btn-form-add">
                                                <i class="fa fa-plus"></i> Baru
                                          </a>

                                          <a href="#portlet-config-cari" data-toggle='modal' class="btn btn-default btn-xs btn-form-cari">
                                                <i class="fa fa-search"></i> Cari
                                          </a>
                                    </div>
                              </div>

                              <div class="portlet-body">
                                    <table style='width:100%'>
                                          <tr>
                                                <td>
                                                      <table>
                                                            <tr>
                                                                  <td colspan='3'>
                                                                        <button href="#portlet-config-edit" data-toggle='modal' class='btn btn-xs' <?= $disabled_control; ?>>
                                                                              <i class='fa fa-edit'></i> Edit
                                                                        </button>
                                                                  </td>
                                                            </tr>

                                                            <tr>
                                                                  <td>Tanggal</td>
                                                                  <td class='padding-rl-5'> : </td>
                                                                  <td class='td-isi-bold'><?= $tanggal; ?></td>
                                                            </tr>
                                                            <tr>
                                                                  <td>Keterangan</td>
                                                                  <td class='padding-rl-5'> : </td>
                                                                  <td class='td-isi-bold'><?= $catatan; ?></td>
                                                            </tr>
                                                      </table>
                                                </td>

                                                <td class='text-right' style='<?= $bg_info; ?>; padding:10px;'>
                                                      <div class=''>
                                                            <i class='fa <?= $status_icon; ?>' style='font-size:2em'>
                                                                  <?= ($status_aktif == '-1' ? 'BATAL' : ''); ?>
                                                            </i>

                                                            <span class='no-faktur-lengkap'> <?= $no_faktur_lengkap; ?></span><br />
                                                      </div>
                                                </td>
                                          </tr>
                                    </table>

                                    <hr />

                                    <div style='overflow:auto'>
                                          <table class="table table-hover table-striped table-bordered" id="general_table">
                                                <thead>
                                                      <tr>
                                                            <th scope="col" class="text-center">
                                                                  No.
                                                            </th>
                                                            <th scope="col" class="text-center">
                                                                  Barang
                                                                  <a href="#modal_add_barang" data-toggle='modal' class="btn btn-xs blue btn-brg-add <?= $disabled_control; ?>">
                                                                        <i class="fa fa-plus"></i>
                                                                  </a>
                                                            </th>
                                                            <th scope="col" class="text-center">
                                                                  Warna
                                                            </th>
                                                            <th scope="col" class="text-center">
                                                                  Gudang
                                                            </th>
                                                            <th scope="col" class="text-center">
                                                                  Satuan
                                                            </th>
                                                            <th scope="col" class="text-center">
                                                                  Qty.
                                                            </th>
                                                            <th scope="col" class="text-center">
                                                                  Roll
                                                            </th>
                                                            <th scope="col">
                                                                  Harga
                                                            </th>
                                                            <th scope="col" class="text-center">
                                                                  Total Harga
                                                            </th>
                                                            <th scope="col" class="text-center">
                                                                  Action
                                                            </th>
                                                      </tr>
                                                </thead>

                                                <tbody>
                                                      <?
                                                      $no_urut = 1;
                                                      $grand_total = 0;
                                                      $qty_total = 0;
                                                      $roll_total = 0;

                                                      foreach ($pengeluaran_stok_lain_detail as $row) {
                                                      ?>

                                                      <tr id='id_<?= $row->id; ?>'>
                                                            <td hidden>
                                                                  <span class='id'><?= $row->id; ?></span>
                                                                  <span class='barang_id'><?= $row->barang_id; ?></span>
                                                                  <span class='barang_nama'><?= $row->nama_barang; ?></span>
                                                                  <span class='warna_id'><?= $row->warna_id; ?></span>
                                                                  <span class='warna_nama'><?= $row->nama_warna; ?></span>
                                                                  <span class='gudang_id'><?= $row->gudang_id; ?></span>
                                                                  <span class='qty'><?= $row->qty; ?></span>
                                                            </td>

                                                            <td class="text-center">
                                                                  <?= $no_urut; ?>
                                                            </td>
                                                            <td>
                                                                  <?= $row->nama_barang; ?>
                                                            </td>
                                                            <td>
                                                                  <?= $row->nama_warna; ?>
                                                            </td>
                                                            <td>
                                                                  <?= $row->nama_gudang; ?>
                                                            </td>
                                                            <td>
                                                                  <span class='nama_satuan'><?= $row->nama_satuan; ?></span>
                                                            </td>
                                                            <td class="text-right">
                                                                  <span class='qty'><?= format_angka($row->qty); ?></span>
                                                            </td>
                                                            <td class="text-right">
                                                                  <span class='jumlah_roll'><?= format_angka($row->jumlah_roll); ?></span>
                                                            </td>
                                                            <td class="text-right">
                                                                  <span class='harga_jual'><?= format_angka($row->harga_jual); ?></span>
                                                            </td>
                                                            <td class="text-right">
                                                                  <?php
                                                                        $total_harga = $row->qty * $row->harga_jual;
                                                                        $grand_total += $total_harga;

                                                                        $qty_total += $row->qty;

                                                                        $roll_total += $row->jumlah_roll;
                                                                        ?>

                                                                  <span class='total_harga'><?= format_angka($total_harga); ?></span>
                                                            </td>

                                                            <td class='hidden-print'>
                                                                  <a href='#modal_edit_barang' data-toggle='modal' class="btn-xs btn green btn-edit-barang <?= $disabled_control; ?>">
                                                                        <i class="fa fa-edit"></i>
                                                                  </a>

                                                                  <a class="btn-xs btn red btn-detail-remove <?= $disabled_control; ?>">
                                                                        <i class="fa fa-times"></i>
                                                                  </a>

                                                                  <a href='#modal_info_pl' data-toggle='modal' class="btn-xs btn blue btn-detail-info">
                                                                        <i class=" fa fa-info-circle"></i>
                                                                  </a>
                                                            </td>
                                                      </tr>

                                                      <?php
                                                            $no_urut++;
                                                      }
                                                      ?>

                                                      <tr class='subtotal-data'>
                                                            <td colspan='5' class='text-right'><b>TOTAL</b></td>
                                                            <td class='text-right'><b><?= format_angka($qty_total); ?></b></td>
                                                            <td class='text-right'><b><?= format_angka($roll_total); ?></b></td>

                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                      </tr>
                                                </tbody>
                                          </table>
                                    </div>

                                    <div style='overflow:auto'>
                                          <table style='width:100%'>
                                                <tr>
                                                      <td style='vertical-align:top;font-size:2.5em;' class='text-right'>
                                                            <table style='float:right;'>
                                                                  <tr style='border:2px solid #ffd7b5'>
                                                                        <td class='padding-rl-25' style='background:#ffd7b5'>TOTAL</td>

                                                                        <td class='text-right padding-rl-10'>
                                                                              <b>Rp <span style=''><?= format_angka($grand_total); ?></span></b>
                                                                        </td>
                                                                  </tr>
                                                            </table>
                                                      </td>
                                                </tr>
                                          </table>
                                    </div>

                                    <hr />

                                    <div>
                                          <?php
                                          if ($grand_total <= 0)
                                                $disabled_lock = 'disabled';

                                          if ($status_aktif == 1 && $status == 1) {
                                          ?>

                                          <button type='button' class='btn btn-lg red hidden-print btn-lock' <?= $disabled_lock; ?>>
                                                <i class='fa fa-lock'></i> Lock
                                          </button>

                                          <?php } ?>

                                          <?php if ($status_aktif == 1 && $status == 0) { ?>

                                          <a href='#modal-unlock' data-toggle='modal' class='btn btn-lg red'>
                                                <i class='fa fa-unlock'></i> Unlock
                                          </a>

                                          <!-- <button type='button' class='btn btn-lg blue hidden-print btn-print'>
                                                <i class='fa fa-print'></i> Print
                                          </button> -->

                                          <?php } ?>
                                    </div>
                              </div>
                        </div>
                  </div>
            </div>
      </div>
</div>

<!-- ------------------------------------------ -->
<!-- INSERT HEADER  -->
<!-- ------------------------------------------ -->
<div class="modal fade" autocomplete="off" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
            <form action="<?= base_url('transaksi/pengeluaran_rupa/header_insert') ?>" class="form-horizontal" id="form_add_data" name="form_add_data" method="post" autocomplete="off">
                  <div class="modal-content">
                        <div class="modal-header">
                              <h3>Tambah Transaksi</h3>
                        </div>

                        <div class="modal-body">
                              <div class="form-group">
                                    <label class="control-label col-md-3">Tanggal
                                          <span class="required">*</span>
                                    </label>

                                    <div class="col-md-8">
                                          <input type="text" readonly class="form-control date-picker" value="<?= date('d/m/Y'); ?>" name="dtpTanggal" />
                                    </div>
                              </div>

                              <div class="form-group">
                                    <label class="control-label col-md-3">Catatan</label>

                                    <div class="col-md-6">
                                          <textarea id="txtCatatan" name="txtCatatan" rows="4" style="width:100%"></textarea>
                                          <span class="help-block"></span>
                                    </div>
                              </div>
                        </div>

                        <div class="modal-footer">
                              <button type="button" class="btn btn-active btn-trigger blue btn-save pull-left">Save</button>
                              <button type="button" class="btn  btn-active default pull-left" data-dismiss="modal">Close</button>
                        </div>
                  </div>
            </form>
      </div>
</div>

<!-- ------------------------------------------ -->
<!-- EDIT HEADER -->
<!-- ------------------------------------------ -->
<div class="modal fade" id="portlet-config-edit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
            <form action="<?= base_url('transaksi/pengeluaran_rupa/header_edit') ?>" class="form-horizontal" id="form_edit_data" method="post" autocomplete="off">
                  <div class="modal-content">
                        <div class="modal-header">
                              <h3>Edit Transaksi</h3>

                              <input type="hidden" id="txtIdHeaderEdit" name="txtIdHeaderEdit" value='<?= $id_header; ?>' />
                        </div>

                        <div class="modal-body">
                              <div class="form-group">
                                    <label class="control-label col-md-3">Tanggal
                                          <span class="required">*</span>
                                    </label>

                                    <div class="col-md-6">
                                          <input type="text" readonly class="form-control date-picker" value="<?= $tanggal; ?>" id="dtpTanggalEdit" name="dtpTanggalEdit" />
                                    </div>
                              </div>

                              <div class="form-group">
                                    <label class="control-label col-md-3">Catatan</label>

                                    <div class="col-md-6">
                                          <textarea id="txtCatatanEdit" name="txtCatatanEdit" rows="4" style="width:100%"><?= $catatan; ?></textarea>
                                          <span class="help-block"></span>
                                    </div>
                              </div>
                        </div>

                        <div class="modal-footer">
                              <button type="button" class="btn blue btn-active btn-trigger btn-edit-save pull-left">Save</button>
                              <button type="button" class="btn default btn-active pull-left" data-dismiss="modal">Close</button>
                        </div>
                  </div>
            </form>
      </div>
</div>

<!-- --------------------------------------- -->
<!-- PENCARIAN -->
<!-- --------------------------------------- -->
<div class="modal fade" id="portlet-config-cari" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
            <form action="<?= base_url('transaksi/pengeluaran_rupa/cari') ?>" class="form-horizontal" id="form_cari" method="post" autocomplete="off">
                  <div class="modal-content">
                        <div class="modal-header">
                              <h3>Pencarian Transaksi</h3>
                        </div>

                        <div class="modal-body">
                              <div class="form-group">
                                    <label class="control-label col-md-3">Nomor Faktur</label>

                                    <div class="col-md-8">
                                          <input type="text" class="form-control" name="txtNoFaktur" />
                                          <span class="help-block"></span>
                                    </div>
                              </div>
                        </div>

                        <div class="modal-footer">
                              <button type="button" class="btn btn-active btn-trigger blue btn-cari pull-left">Cari</button>
                              <button type="button" class="btn btn-active default pull-left" data-dismiss="modal">Close</button>
                        </div>
                  </div>
            </form>
      </div>
</div>

<!-- --------------------------------------------- -->
<!-- INSERT BARANG -->
<!-- --------------------------------------------- -->
<div class="modal fade bs-modal-md" id="modal_add_barang" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-md">
            <form action="" class="form-horizontal" id="form_add_barang" name="form_add_barang" method="post">
                  <div class="modal-content">
                        <div class="modal-header">
                              <h3>Tambah Barang</h3>

                              <input type="hidden" id="txtPengeluaranId" name='txtPengeluaranId' value='<?= $id_header; ?>'>
                        </div>

                        <div class="modal-body">
                              <div class="form-group">
                                    <label class="control-label col-md-3">Gudang
                                          <span class="required">*</span>
                                    </label>

                                    <div class="col-md-8">
                                          <select class='form-control' id='cboGudang' name="cboGudang">
                                                <?php
                                                foreach ($this->gudang_list_aktif as $row) {
                                                ?>

                                                <option value="<?= $row->id ?>"><?= $row->nama; ?></option>

                                                <?php } ?>
                                          </select>

                                          <span class="help-block"></span>
                                    </div>
                              </div>

                              <div class="form-group">
                                    <label class="control-label col-md-3">Barang
                                          <span class="required">*</span>
                                    </label>

                                    <div class="col-md-8">
                                          <select class='form-control' id='cboBarang' name="cboBarang">
                                                <option value=''>Pilih</option>

                                                <?php
                                                foreach ($this->barang_list_aktif as $row) {
                                                      if ($row->status_aktif == 1) {
                                                ?>

                                                <option value="<?= $row->id . ';' . $row->nama_satuan . ';' . $row->harga_jual; ?>"><?= $row->nama_jual; ?></option>

                                                <?php
                                                      }
                                                }
                                                ?>
                                          </select>

                                          <span class="help-block"></span>

                                          <input type="hidden" id="txtIdBarang" name='txtIdBarang' class='form-control'>
                                    </div>
                              </div>

                              <div class="form-group">
                                    <label class="control-label col-md-3">Warna
                                          <span class="required">*</span>
                                    </label>

                                    <div class="col-md-8">
                                          <select class='form-control' id='cboWarna' name="cboWarna">
                                                <option value=''>Pilih</option>

                                                <?php
                                                foreach ($this->warna_list_aktif as $row) {
                                                ?>

                                                <option value="<?= $row->id ?>"><?= $row->warna_beli; ?></option>

                                                <?php } ?>
                                          </select>

                                          <span class="help-block"></span>
                                    </div>
                              </div>

                              <div class="form-group">
                                    <label class="control-label col-md-3">Satuan</label>

                                    <div class="col-md-8">
                                          <input readonly type="text" class='form-control' id="txtSatuan" name="txtSatuan" />
                                    </div>
                              </div>

                              <div class="form-group">
                                    <label class="control-label col-md-3">Harga
                                          <span class="required">*</span>
                                    </label>

                                    <div class="col-md-8">
                                          <input type="number" class='form-control' id='txtHarga' name="txtHarga" />
                                          <span class="help-block"></span>
                                    </div>
                              </div>
                        </div>

                        <div class="modal-footer">
                              <button type="button" class="btn blue btn-save-brg pull-left">Simpan</button>
                              <button type="button" class="btn default btn-close-brg pull-left" data-dismiss="modal">Close</button>
                        </div>
                  </div>
            </form>
      </div>
</div>

<!-- -------------------------------------------- -->
<!-- INSERT PACKING LIST -->
<!-- -------------------------------------------- -->
<div class="modal fade bs-modal" id="modal_add_pl" tabindex="-1" role="dialog" aria-labelledby="MyPackingListAdd" aria-hidden="true">
      <div class="modal-dialog modal-md">
            <form action="<?= base_url('transaksi/pengeluaran_rupa/pl_insert') ?>" class="form-horizontal" id="form_add_pl" name="form_add_pl" method="post" autocomplete="off">
                  <div class="modal-content">
                        <div class="modal-header">
                              <h3>Tambah Packing List</h3>

                              <input type="hidden" id="txtPengeluaranIdPL" name='txtPengeluaranIdPL' value='<?= $id_header; ?>'>
                              <input type="hidden" id="gudang_id_pl" name='gudang_id_pl'>
                              <input type="hidden" id="barang_id_pl" name='barang_id_pl'>
                              <input type="hidden" id="warna_id_pl" name='warna_id_pl'>
                              <input type="hidden" id="harga_pl" name='harga_pl'>
                        </div>

                        <div class="modal-body">
                              <div class="row">
                                    <div class="col-md-6">
                                          <table id='qty-table' cellspacing="0" cellpadding="0">
                                                <tr>
                                                      <th width="50px" class="text-center"></th>
                                                      <th width="100px" class="text-center">Qty</th>
                                                      <th width="100px" class="text-center">Roll</th>
                                                </tr>

                                                <?php
                                                for ($i = 0; $i < 10; $i++) {
                                                ?>

                                                <tr>
                                                      <td width="50px"><label style="width: 50px" class="text-center"><?= $i + 1; ?></label></td>
                                                      <td width="100px"><input style="width: 100px;" type="number" class="form-control text-qty" id='<?= "txtQty" . $i; ?>' name='<?= "txtQty" . $i; ?>' min="0" max="999"></td>
                                                      <td width="100px"><input style="width: 100px;" type="number" class="form-control text-roll" id='<?= "txtRoll" . $i; ?>' name='<?= "txtRoll" . $i; ?>' min="0" max="999"></td>
                                                </tr>

                                                <?php } ?>
                                          </table>
                                    </div>

                                    <div class="col-md-6">
                                          <div class="row">
                                                <div class="col-md-6">
                                                      <label for="txtTotalQty">Total Qty</label>
                                                      <input id="txtTotalQty" name="txtTotalQty" type="text" name="name" class="form-control" readonly>
                                                      <span class="help-block"></span>
                                                </div>

                                                <div class="col-md-6">
                                                      <label for="txtTotalRoll">Total Roll</label>
                                                      <input id="txtTotalRoll" name="txtTotalRoll" type="text" name="name" class="form-control" readonly>
                                                      <span class="help-block"></span>
                                                </div>

                                          </div>

                                          <input type="hidden" id="txtTotalItem" name="txtTotalItem" value=10 />
                                    </div>
                              </div>
                        </div>

                        <div class="modal-footer">
                              <button type="button" id="btnAddPL" class="btn green"><i class='fa fa-plus'></i></button>
                              <button type="button" class="btn blue btn-save-pl pull-left">Simpan</button>
                              <button type="button" class="btn default btn-close-pl pull-left" data-dismiss="modal">Close</button>
                        </div>
                  </div>
            </form>
      </div>
</div>

<!-- ----------------------------------------------- -->
<!-- EDIT BARANG -->
<!-- ----------------------------------------------- -->
<div class="modal fade bs-modal" id="modal_edit_barang" tabindex="-1" role="dialog" aria-labelledby="MyBarangEdit" aria-hidden="true">
      <div class="modal-dialog modal-md">
            <form action="<?= base_url('transaksi/pengeluaran_rupa/barang_edit') ?>" class="form-horizontal" id="form_edit_barang" name="form_edit_barang" method="post">
                  <div class="modal-content">
                        <div class="modal-header">
                              <h3>Edit Barang</h3>

                              <input type="hidden" id="txtIdEdit" name='txtIdEdit' value='<?= $id_header; ?>'>
                              <input type="hidden" id="txtIdDetailEdit" name='txtIdDetailEdit'>
                        </div>

                        <div class="modal-body">
                              <div class="form-group">
                                    <label class="control-label col-md-3">Gudang
                                          <span class="required">*</span>
                                    </label>

                                    <div class="col-md-8">
                                          <select class='form-control' id='cboGudangEdit' name="cboGudangEdit">
                                                <?php
                                                foreach ($this->gudang_list_aktif as $row) {
                                                ?>

                                                <option value="<?= $row->id ?>"><?= $row->nama; ?></option>

                                                <?php } ?>
                                          </select>

                                          <span class="help-block"></span>
                                    </div>
                              </div>

                              <div class="form-group">
                                    <label class="control-label col-md-3">Barang</label>

                                    <div class="col-md-8">
                                          <div class="input-group">
                                                <input type="hidden" id="txtIdBarangEdit" name='txtIdBarangEdit' class='form-control'>
                                                <input type="text" id="txtNamaBarangEdit" name='txtNamaBarangEdit' class='form-control' readonly>

                                                <div class="input-group-btn">
                                                      <button type="button" class="btn btn-default" data-toggle="collapse" data-target="#info21">
                                                            <i class="glyphicon glyphicon-pencil"></i>
                                                      </button>
                                                </div>
                                          </div>
                                    </div>
                              </div>

                              <div id="info21" class="collapse">
                                    <div class="form-group">
                                          <label class="control-label col-md-3">Revisi Barang
                                                <span class="required">*</span>
                                          </label>

                                          <div class="col-md-8">
                                                <select class='form-control' id='cboBarangEdit' name="cboBarangEdit">
                                                      <option value=''>Pilih</option>

                                                      <?php
                                                      foreach ($this->barang_list_aktif as $row) {
                                                            if ($row->status_aktif == 1) {
                                                      ?>

                                                      <option value="<?= $row->id . ';' . $row->nama_satuan . ';' . $row->harga_jual; ?>"><?= $row->nama_jual; ?></option>

                                                      <?php
                                                            }
                                                      }
                                                      ?>
                                                </select>

                                                <span class="help-block"></span>

                                                <input type="hidden" id="txtIdBarangRevisi" name='txtIdBarangRevisi' class='form-control'>
                                          </div>
                                    </div>
                              </div>

                              <div class="form-group">
                                    <label class="control-label col-md-3">Warna
                                          <span class="required">*</span>
                                    </label>

                                    <div class="col-md-8">
                                          <select class='form-control' id='cboWarnaEdit' name="cboWarnaEdit">
                                                <option value=''>Pilih</option>

                                                <?php
                                                foreach ($this->warna_list_aktif as $row) {
                                                ?>

                                                <option value="<?= $row->id ?>"><?= $row->warna_beli; ?></option>

                                                <?php } ?>
                                          </select>

                                          <span class="help-block"></span>
                                    </div>
                              </div>

                              <div class="form-group">
                                    <label class="control-label col-md-3">Satuan</label>

                                    <div class="col-md-8">
                                          <input readonly type="text" class='form-control' id="txtSatuanEdit" name="txtSatuanEdit" />
                                    </div>
                              </div>

                              <div class="form-group">
                                    <label class="control-label col-md-3">Harga
                                          <span class="required">*</span>
                                    </label>

                                    <div class="col-md-8">
                                          <input type="number" class='form-control' id='txtHargaEdit' name="txtHargaEdit" />
                                          <span class="help-block"></span>
                                    </div>
                              </div>
                        </div>

                        <div class="modal-footer">
                              <button type="button" class="btn blue btn-edit-brg pull-left">Simpan</button>
                              <button type="button" class="btn default btn-close-brg-edit pull-left" data-dismiss="modal">Close</button>
                        </div>
                  </div>
            </form>
      </div>
</div>

<!-- -------------------------------------------- -->
<!-- EDIT PACKING LIST -->
<!-- -------------------------------------------- -->
<div class="modal fade bs-modal" id="modal_edit_pl" tabindex="-1" role="dialog" aria-labelledby="MyPackingListEdit" aria-hidden="true">
      <div class="modal-dialog modal-md">
            <form action="<?= base_url('transaksi/pengeluaran_rupa/pl_edit') ?>" class="form-horizontal" id="form_edit_pl" name="form_edit_pl" method="post" autocomplete="off">
                  <div class="modal-content">
                        <div class="modal-header">
                              <h3>Edit Packing List</h3>

                              <input type="hidden" id="txtPLHeaderIdEdit" name='txtPLHeaderIdEdit' value='<?= $id_header; ?>'>
                              <input type="hidden" id="txtPLDetailIdEdit" name='txtPLDetailIdEdit'>
                              <input type="hidden" id="gudang_id_pl_edit" name='gudang_id_pl_edit'>
                              <input type="hidden" id="barang_id_pl_edit" name='barang_id_pl_edit'>
                              <input type="hidden" id="warna_id_pl_edit" name='warna_id_pl_edit'>
                              <input type="hidden" id="harga_pl_edit" name='harga_pl_edit'>
                        </div>

                        <div class="modal-body">
                              <div class="row">
                                    <div class="col-md-6">
                                          <table id='qty-table-edit' cellspacing="0" cellpadding="0">
                                                <tr>
                                                      <th width="50px" class="text-center"></th>
                                                      <th width="100px" class="text-center">Qty</th>
                                                      <th width="100px" class="text-center">Roll</th>
                                                      <th class="text-center"></th>
                                                </tr>

                                                <?php
                                                for ($i = 0; $i < 10; $i++) {
                                                ?>

                                                <tr>
                                                      <td width="50px"><label style='width: 50px' class='text-center'><?= $i + 1; ?></label></td>
                                                      <td width="100px"><input type="number" style="width: 100px" class="form-control text-qty-edit" id='<?= "txtQtyEdit" . $i; ?>' name='<?= "txtQtyEdit" . $i; ?>' min="0" max="999"></td>
                                                      <td width="100px"><input type="number" style="width: 100px" class="form-control text-roll-edit" id='<?= "txtRollEdit" . $i; ?>' name='<?= "txtRollEdit" . $i; ?>' min="0" max="999"></td>
                                                      <td><input type="hidden" id='<?= "txtIndexEdit" . $i; ?>' name='<?= "txtIndexEdit" . $i; ?>'></input></td>
                                                </tr>

                                                <?php } ?>
                                          </table>
                                    </div>

                                    <div class="col-md-6">
                                          <div class="row">
                                                <div class="col-md-6">
                                                      <label for="txtTotalQtyEdit">Total Qty</label>
                                                      <input id="txtTotalQtyEdit" name="txtTotalQtyEdit" type="text" name="name" class="form-control" readonly>
                                                      <span class="help-block"></span>
                                                </div>

                                                <div class="col-md-6">
                                                      <label for="txtTotalRollEdit">Total Roll</label>
                                                      <input id="txtTotalRollEdit" name="txtTotalRollEdit" type="text" name="name" class="form-control" readonly>
                                                      <span class="help-block"></span>
                                                </div>

                                          </div>

                                          <input type="hidden" id="txtTotalItemEdit" name="txtTotalItemEdit" value=10 />
                                    </div>
                              </div>
                        </div>

                        <div class="modal-footer">
                              <button type="button" id="btnAddPLEdit" class="btn green"><i class='fa fa-plus'></i></button>
                              <button type="button" class="btn blue btn-edit-pl pull-left">Simpan</button>
                              <button type="button" class="btn default btn-close-pl-edit pull-left" data-dismiss="modal">Close</button>
                        </div>
                  </div>
            </form>
      </div>
</div>

<!-- -------------------------------------------- -->
<!-- INFO PACKING LIST -->
<!-- -------------------------------------------- -->
<div class="modal fade bs-modal" id="modal_info_pl" tabindex="-1" role="dialog" aria-labelledby="MyPackingListInfo" aria-hidden="true">
      <div class="modal-dialog modal-md">
            <form action="" class="form-horizontal" id="form_info_pl" name="form_info_pl" method="post" autocomplete="off">
                  <div class="modal-content">
                        <div class="modal-header">
                              <h3>Informasi Packing List</h3>

                              <input type="hidden" id="txtPLHeaderIdInfo" name='txtPLHeaderIdInfo' value='<?= $id_header; ?>'>
                              <input type="hidden" id="txtPLDetailIdInfo" name='txtPLDetailIdInfo'>
                        </div>

                        <div class="modal-body">
                              <div class="row">
                                    <div class="col-md-6">
                                          <table id='qty-table-info' cellspacing="0" cellpadding="0">
                                                <tr>
                                                      <th width="50px" class="text-center"></th>
                                                      <th class="text-center" width="100px">Qty</th>
                                                      <th class="text-center" width="100px">Roll</th>
                                                </tr>
                                          </table>
                                    </div>

                                    <div class="col-md-6">
                                          <div class="row">
                                                <div class="col-md-6">
                                                      <label for="txtTotalQtyInfo">Total Qty</label>
                                                      <input id="txtTotalQtyInfo" name="txtTotalQtyInfo" type="text" name="name" class="form-control" readonly>
                                                      <span class="help-block"></span>
                                                </div>

                                                <div class="col-md-6">
                                                      <label for="txtTotalRollInfo">Total Roll</label>
                                                      <input id="txtTotalRollInfo" name="txtTotalRollInfo" type="text" name="name" class="form-control" readonly>
                                                      <span class="help-block"></span>
                                                </div>
                                          </div>

                                          <input type="hidden" id="txtTotalItemInfo" name="txtTotalItemInfo" />
                                    </div>
                              </div>
                        </div>

                        <div class="modal-footer">
                              <button type="button" class="btn default btn-info-pl-close pull-left" data-dismiss="modal">Close</button>
                        </div>
                  </div>
            </form>
      </div>
</div>

<!-- ----------------------------------- -->
<!-- unlock -->
<!-- ----------------------------------- -->
<div class="modal fade" id="modal-unlock" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
            <div class="modal-content">
                  <form action="<?= base_url('transaksi/pengeluaran_rupa/unlock'); ?>" class="form-horizontal" id="form-unlock" name="form-unlock" method="post" autocomplete="off">
                        <div class="modal-header">
                              <h3>Unlock Transaksi</h3>

                              <input type="hidden" id="txtPINHeaderId" name='txtPINHeaderId' value='<?= $id_header; ?>'>
                        </div>

                        <div class="modal-body">
                              <div class="form-group">
                                    <label class="control-label col-md-3">PIN
                                          <span class="required">*</span>
                                    </label>

                                    <div class="col-md-6">
                                          <input id="txtPinOpen" name='txtPinOpen' type='password' class="form-control">
                                          <span class="help-block"></span>
                                    </div>
                              </div>
                        </div>

                        <div class="modal-footer">
                              <button type="button" class="btn blue btn-unlock pull-left">OPEN</button>
                              <button type="button" class="btn default pull-left" data-dismiss="modal">Close</button>
                        </div>
                  </form>
            </div>
      </div>
</div>

<script src="<?php echo base_url('assets/global/plugins/bootstrap-modal/js/bootstrap-modalmanager.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/bootstrap-modal/js/bootstrap-modal.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/jquery-validation/js/jquery.validate.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/bootbox/bootbox.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets_noondev/js/webprint.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/select2/select2.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets_noondev/js/form-pengeluaran-stok-lain.js'); ?>" type="text/javascript"></script>

<script>
jQuery(document).ready(function() {
      //register 
      $('#cboGudang, #cboBarang, #cboWarna').select2();
      $('#cboGudangEdit, #cboBarangEdit, #cboWarnaEdit').select2();

      //insert header
      $(".btn-save").click(function() {
            $('#form_add_data').click(function() {
                  $.ajax({
                        url: "<?= base_url('transaksi/pengeluaran_rupa/header_insert_validate_start') ?>",
                        type: "POST",
                        data: $('#form_add_data').serialize(),
                        dataType: "JSON",
                        success: function(data) {
                              if (data.status) {
                                    $('#form_add_data').submit();
                              } else {
                                    for (var i = 0; i < data.inputerror.length; i++) {
                                          $('[name="' + data.inputerror[i] + '"]').parent().parent().addClass('has-error');
                                          $('[name="' + data.inputerror[i] + '"]').next().text(data.error_string[i]);
                                    }
                              }
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                              alert('Error penyimpanan data!');
                        }
                  });
            });
      });

      //pencarian
      $(".btn-cari").click(function() {
            $('#form_cari').click(function() {
                  $.ajax({
                        url: "<?= base_url('transaksi/pengeluaran_rupa/cari_validate_start') ?>",
                        type: "POST",
                        data: $('#form_cari').serialize(),
                        dataType: "JSON",
                        success: function(data) {
                              if (data.status) {
                                    $('#form_cari').submit();
                              } else {
                                    for (var i = 0; i < data.inputerror.length; i++) {
                                          $('[name="' + data.inputerror[i] + '"]').parent().parent().addClass('has-error');
                                          $('[name="' + data.inputerror[i] + '"]').next().text(data.error_string[i]);
                                    }
                              }
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                              alert('Error pencarian data!');
                        }
                  });
            });
      });

      //edit header
      $("#form_edit_data .btn-edit-save").click(function() {
            $.ajax({
                  url: "<?= base_url('transaksi/pengeluaran_rupa/header_edit_validate_start') ?>",
                  type: "POST",
                  data: $('#form_edit_data').serialize(),
                  dataType: "JSON",
                  success: function(data) {
                        if (data.status) {
                              $('#form_edit_data').submit();
                        } else {
                              for (var i = 0; i < data.inputerror.length; i++) {
                                    $('[name="' + data.inputerror[i] + '"]').parent().parent().addClass('has-error');
                                    $('[name="' + data.inputerror[i] + '"]').next().text(data.error_string[i]);
                              }
                        }
                  },
                  error: function(jqXHR, textStatus, errorThrown) {
                        alert('Error update data!');
                  }
            });
      });

      //insert barang
      $("#cboBarang").change(function() {
            var result = $("#cboBarang").val().split(';');
            var id = result[0];
            var satuan = result[1];
            var harga = result[2];

            $('#txtIdBarang').val(id);
            $('#txtSatuan').val(satuan);
            $('#txtHarga').val(harga);
      });

      $(".btn-save-brg").click(function() {
            $.ajax({
                  url: "<?= base_url('transaksi/pengeluaran_rupa/barang_insert_validate_start') ?>",
                  type: "POST",
                  data: $('#form_add_barang').serialize(),
                  dataType: "JSON",
                  success: function(data) {
                        if (data.status) {
                              show_pl();
                        } else {
                              for (var i = 0; i < data.inputerror.length; i++) {
                                    $('[name="' + data.inputerror[i] + '"]').parent().parent().addClass('has-error');
                                    $('[name="' + data.inputerror[i] + '"]').next().text(data.error_string[i]);
                              }
                        }
                  },
                  error: function(jqXHR, textStatus, errorThrown) {
                        alert('Error penyimpanan detail data!');
                  }
            });
      });

      function show_pl() {
            $('#modal_add_barang').hide();
            gudang_id = $('#form_add_barang #cboGudang').val();
            barang_id = $('#form_add_barang #txtIdBarang').val();
            warna_id = $('#form_add_barang #cboWarna').val();
            harga = $('#form_add_barang #txtHarga').val();

            $('#form_add_pl #gudang_id_pl').val(gudang_id);
            $('#form_add_pl #barang_id_pl').val(barang_id);
            $('#form_add_pl #warna_id_pl').val(warna_id);
            $('#form_add_pl #harga_pl').val(harga);
            $('#modal_add_pl').modal('show');
      }

      //insert pl
      $(".btn-save-pl").click(function() {
            $.ajax({
                  url: "<?= base_url('transaksi/pengeluaran_rupa/pl_insert_validate_start') ?>",
                  type: "POST",
                  data: $('#form_add_pl').serialize(),
                  dataType: "JSON",
                  success: function(data) {
                        if (data.status) {
                              $('#form_add_pl').submit();
                        } else {
                              for (var i = 0; i < data.inputerror.length; i++) {
                                    $('[name="' + data.inputerror[i] + '"]').parent().parent().addClass('has-error');
                                    $('[name="' + data.inputerror[i] + '"]').next().text(data.error_string[i]);
                              }
                        }
                  },
                  error: function(jqXHR, textStatus, errorThrown) {
                        alert('Error penyimpanan detail data!');
                  }
            });
      });

      $("#form_add_pl").submit(function() {
            window.location.reload();
      });

      $('.btn-close-pl').click(function() {
            $('#modal_add_barang .btn-close-brg').click();
      });

      $("#btnAddPL").click(function() {
            var total_item = $("#txtTotalItem").val();
            total_item++;
            $("#txtTotalItem").val(total_item);

            var no = total_item;
            var id = total_item - 1;

            var table = "<tr>";
            table += "<td width='50px'><label style='width: 50px' class='text-center'>" + no + "</label></td>";
            table += "<td width='100px'><input type='number' style='width: 100px;' class='form-control' id='txtQty" + id + "' name='txtQty" + id + "' min=0 max=999 /></td>";
            table += "<td width='100px'><input type='number' style='width: 100px;' class='form-control' id='txtRoll" + id + "' name='txtRoll" + id + "' min=0 max=999 /></td>";
            table += "</tr>";

            $("#qty-table tbody").append(table);
      });

      $('.text-qty, .text-roll').change(function() {
            var total_control = $('#txtTotalItem').val();

            var nama_control_qty = '';
            var nama_control_roll = '';

            var qty = 0;
            var roll = 0;
            var total_qty = 0;
            var total_roll = 0;

            for (var i = 0; i < $('#txtTotalItem').val(); i++) {
                  // named control
                  nama_control_qty = '#txtQty' + i;
                  nama_control_roll = '#txtRoll' + i;

                  // get from control
                  qty = $(nama_control_qty).val() == '' ? 0 : parseFloat($(nama_control_qty).val());
                  roll = $(nama_control_roll).val() == '' ? 0 : parseFloat($(nama_control_roll).val());

                  // hitung qty
                  if (qty > 0) {
                        roll = roll == 0 ? 1 : roll;
                        total_qty = total_qty + (qty * roll);
                        total_roll += roll;
                  }
            }

            $('#txtTotalQty').val(total_qty);
            $('#txtTotalRoll').val(total_roll);
      });

      // edit barang
      $('#general_table').on('click', '.btn-edit-barang', function() {
            const ini = $(this).closest('tr');
            let form = $('#form_edit_barang');

            form.find("[name=txtIdDetailEdit]").val(ini.find('.id').html());
            form.find("[name=txtIdBarangEdit]").val(ini.find('.barang_id').html());
            form.find("[name=txtNamaBarangEdit]").val(ini.find('.barang_nama').html());
            form.find("[name=cboGudangEdit]").val(ini.find('.gudang_id').html());
            form.find("[name=cboGudangEdit]").change();
            form.find("[name=cboWarnaEdit]").val(ini.find('.warna_id').html());
            form.find("[name=cboWarnaEdit]").change();
            form.find("[name=txtSatuanEdit]").val(ini.find('.nama_satuan').html());
            form.find("[name=txtHargaEdit]").val(ini.find('.harga_jual').html().replace('.', ''));
      });

      $("#cboBarangEdit").change(function() {
            var result = $("#cboBarangEdit").val().split(';');
            var id = result[0];
            var satuan = result[1];
            var harga = result[2];

            $('#txtIdBarangRevisi').val(id);
            $('#txtSatuanEdit').val(satuan);
            $('#txtHargaEdit').val(harga);
      });

      $(".btn-edit-brg").click(function() {
            $.ajax({
                  url: "<?= base_url('transaksi/pengeluaran_rupa/barang_edit_validate_start') ?>",
                  type: "POST",
                  data: $('#form_edit_barang').serialize(),
                  dataType: "JSON",
                  success: function(data) {
                        if (data.status) {
                              show_pl_edit();
                        } else {
                              for (var i = 0; i < data.inputerror.length; i++) {
                                    $('[name="' + data.inputerror[i] + '"]').parent().parent().addClass('has-error');
                                    $('[name="' + data.inputerror[i] + '"]').next().text(data.error_string[i]);
                              }
                        }
                  },
                  error: function(jqXHR, textStatus, errorThrown) {
                        alert('Error penyimpanan detail data!');
                  }
            });
      });

      // packing list edit
      function show_pl_edit() {
            $('#modal_edit_barang').hide();
            id_detail = $('#form_edit_barang #txtIdDetailEdit').val();
            gudang_id = $('#form_edit_barang #cboGudangEdit').val();
            barang_id = $('#form_edit_barang #txtIdBarangRevisi').val();
            warna_id = $('#form_edit_barang #cboWarnaEdit').val();
            harga = $('#form_edit_barang #txtHargaEdit').val();

            $('#form_edit_pl #txtPLDetailIdEdit').val(id_detail);
            $('#form_edit_pl #gudang_id_pl_edit').val(gudang_id);
            $('#form_edit_pl #barang_id_pl_edit').val(barang_id);
            $('#form_edit_pl #warna_id_pl_edit').val(warna_id);
            $('#form_edit_pl #harga_pl_edit').val(harga);

            $.ajax({
                  type: "POST",
                  url: "<?= base_url('transaksi/pengeluaran_rupa/pl_get_list') ?>",
                  dataType: "JSON",
                  data: {
                        detail_id: id_detail
                  },
                  cache: false,
                  success: function(data) {
                        // kalau data lebih besar dari 10 (jumlah control text default), tambah
                        var sisa = data.length - 10;

                        if (sisa > 0) {
                              for (var x = 0; x < sisa; x++) {
                                    var total_item = $("#form_edit_pl #txtTotalItemEdit").val();
                                    total_item++;
                                    $("#form_edit_pl #txtTotalItemEdit").val(total_item);

                                    var no = total_item;
                                    var id = total_item - 1;

                                    var table = "<tr>";
                                    table += "<td width='50px'><label style='width: 50px' class='text-center'>" + no + "</label></td>";
                                    table += "<td width='100px'><input type='number' style='width: 100px' class='form-control' id='txtQtyEdit" + id + "' name='txtQtyEdit" + id + "' min=0 max=999 /></td>"
                                    table += "<td width='100px'><input type='number' style='width: 100px'  class='form-control' id='txtRollEdit" + id + "' name='txtRollEdit" + id + "' min=0 max=999 /></td></tr>";
                                    table += "<td width='100px'><input type='hidden' id='txtIndexEdit" + id + "' name='txtIndexEdit" + id + "'/></td>";
                                    table += "</tr>";

                                    $("#form_edit_pl #qty-table-edit tbody").append(table);
                              }
                        }

                        // total qty & roll
                        var total_qty = 0;
                        var total_roll = 0;

                        for (var i = 0; i < data.length; i++) {
                              total_qty = total_qty + (parseFloat(data[i].qty) * parseFloat(data[i].jumlah_roll));
                              total_roll = total_roll + parseFloat(data[i].jumlah_roll);

                              $('#form_edit_pl #txtIndexEdit' + i).val(data[i].id);
                              $('#form_edit_pl #txtQtyEdit' + i).val(data[i].qty);
                              $('#form_edit_pl #txtRollEdit' + i).val(data[i].jumlah_roll);
                        }

                        $('#modal_edit_pl #txtTotalQtyEdit').val(total_qty);
                        $('#modal_edit_pl #txtTotalRollEdit').val(total_roll);
                  }
            });

            $('#modal_edit_pl').modal('show');
      }

      //save update packing list
      $(".btn-edit-pl").click(function() {
            $.ajax({
                  url: "<?= base_url('transaksi/pengeluaran_rupa/pl_edit_validate_start') ?>",
                  type: "POST",
                  data: $('#form_edit_pl').serialize(),
                  dataType: "JSON",
                  success: function(data) {
                        if (data.status) {
                              $('#form_edit_pl').submit();
                        } else {
                              for (var i = 0; i < data.inputerror.length; i++) {
                                    $('[name="' + data.inputerror[i] + '"]').parent().parent().addClass('has-error');
                                    $('[name="' + data.inputerror[i] + '"]').next().text(data.error_string[i]);
                              }
                        }
                  },
                  error: function(jqXHR, textStatus, errorThrown) {
                        alert('Error penyimpanan detail data!');
                  }
            });
      });

      $("#form_edit_pl").submit(function() {
            window.location.reload();
      });

      $('.btn-close-pl-edit').click(function() {
            $('#modal_edit_barang .btn-close-brg-edit').click();
      });

      $("#btnAddPLEdit").click(function() {
            var total_item = $("#txtTotalItemEdit").val();
            total_item++;
            $("#txtTotalItemEdit").val(total_item);

            var no = total_item;
            var id = total_item - 1;

            var table = "<tr>";
            table += "<td width='50px'><label style='width: 50px' class='text-center'>" + no + "</label></td>";
            table += "<td width='100px'><input type='number' style='width: 100px' class='form-control' id='txtQtyEdit" + id + "' name='txtQtyEdit" + id + "' min=0 max=999 /></td>"
            table += "<td width='100px'><input type='number' style='width: 100px' class='form-control' id='txtRollEdit" + id + "' name='txtRollEdit" + id + "' min=0 max=999 /></td></tr>";
            table += "<td><input type='hidden' id='txtIndexEdit" + id + "' name='txtIndexEdit" + id + "'/></td>";
            table += "</tr>";

            $("#qty-table-edit tbody").append(table);
      });

      $('.text-qty-edit, .text-roll-edit').change(function() {
            var total_control = $('#txtTotalItemEdit').val();

            var nama_control_qty = '';
            var nama_control_roll = '';

            var qty = 0;
            var roll = 0;
            var total_qty = 0;
            var total_roll = 0;

            for (var i = 0; i < $('#txtTotalItemEdit').val(); i++) {
                  // named control
                  nama_control_qty = '#txtQtyEdit' + i;
                  nama_control_roll = '#txtRollEdit' + i;

                  // get from control
                  qty = $(nama_control_qty).val() == '' ? 0 : parseFloat($(nama_control_qty).val());
                  roll = $(nama_control_roll).val() == '' ? 0 : parseFloat($(nama_control_roll).val());

                  // hitung qty
                  if (qty > 0) {
                        roll = roll == 0 ? 1 : roll;
                        total_qty = total_qty + (qty * roll);
                        total_roll += roll;
                  }
            }

            $('#txtTotalQtyEdit').val(total_qty);
            $('#txtTotalRollEdit').val(total_roll);
      });

      // hapus barang
      $('#general_table').on('click', '.btn-detail-remove', function() {
            var ini = $(this).closest('tr');
            bootbox.confirm("Mau menghapus item ini ? ", function(respond) {
                  if (respond) {
                        var data = {};
                        data['id'] = ini.find('.id').html();
                        var url = 'transaksi/pengeluaran_rupa/barang_delete';

                        ajax_data_sync(url, data).done(function(data_respond) {
                              if (data_respond == 'OK') {
                                    ini.remove();
                                    window.location.reload();
                              };
                        });
                  };
            });
      });

      // info packing list
      $('#general_table').on('click', '.btn-detail-info', function() {
            const ini = $(this).closest('tr');
            let form = $('#modal_info_pl');

            var id_detail = ini.find('.id').html();
            form.find("[name=txtPLDetailIdInfo]").val(id_detail);

            $.ajax({
                  type: "POST",
                  url: "<?= base_url('transaksi/pengeluaran_rupa/pl_get_list') ?>",
                  dataType: "JSON",
                  data: {
                        detail_id: id_detail
                  },
                  cache: false,
                  success: function(data) {
                        var no = 0;

                        var total_qty = 0;
                        var total_roll = 0;

                        for (var x = 0; x < data.length; x++) {
                              no++;
                              total_roll = total_roll + parseFloat(data[x].jumlah_roll);
                              total_qty = total_qty + (parseFloat(data[x].qty) * parseFloat(data[x].jumlah_roll));

                              var table = "<tr id='mycell" + no + "'>";
                              table += "<td class='text-center' width='50px'><input type='text' class='form-control' value='" + no + "' readonly/></td>";
                              table += "<td><input type='text' class='form-control' value='" + parseFloat(data[x].qty) + "' readonly/></td>"
                              table += "<td><input type='text' class='form-control' value='" + parseFloat(data[x].jumlah_roll) + "' readonly/></td>";
                              table += "</tr>";

                              $("#form_info_pl #qty-table-info tbody").append(table);
                        }

                        $('#form_info_pl #txtTotalQtyInfo').val(total_qty);
                        $('#form_info_pl #txtTotalRollInfo').val(total_roll);

                        $('#form_info_pl #txtTotalItemInfo').val(no);
                  }
            });
      });

      $('.btn-info-pl-close').click(function() {
            var total = $('#form_info_pl #txtTotalItemInfo').val();
            var nama_control = '';

            for (var a = 1; a <= total; a++) {
                  nama_control = '#mycell' + a;
                  $(nama_control).remove();
            }
      });

      //lock
      $('.btn-lock').click(function() {
            var id_header = "<?= $id_header ?>";

            bootbox.confirm("Transaksi sudah selesai. <b>Lock transaksi ini?</b>", function(respond) {
                  if (respond) {
                        var data = {};
                        data['id'] = id_header;
                        var url = 'transaksi/pengeluaran_rupa/lock';

                        ajax_data_sync(url, data).done(function(data_respond) {
                              if (data_respond == 'OK') {
                                    window.location.reload();
                              };
                        });
                  };
            });
      });

      //unlock
      $('.btn-unlock').click(function() {
            $.ajax({
                  url: "<?= base_url('transaksi/pengeluaran_rupa/unlock_validate_start') ?>",
                  type: "POST",
                  data: $('#form-unlock').serialize(),
                  dataType: "JSON",
                  success: function(data) {
                        if (data.status) {
                              $('#form-unlock').submit();
                        } else {
                              for (var i = 0; i < data.inputerror.length; i++) {
                                    $('[name="' + data.inputerror[i] + '"]').parent().parent().addClass('has-error');
                                    $('[name="' + data.inputerror[i] + '"]').next().text(data.error_string[i]);
                              }
                        }
                  },
                  error: function(jqXHR, textStatus, errorThrown) {
                        alert('Error update data!');
                  }
            });
      });
});
</script>