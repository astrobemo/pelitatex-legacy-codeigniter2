<!-- ------------------------------------------------- -->
<!-- load library -->
<!-- ------------------------------------------------- -->
<link rel="stylesheet" type="text/css" href="<?= base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css'); ?>" />
<link rel="stylesheet" type="text/css" href="<?= base_url('assets/global/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css'); ?>" />
<link href="<?= base_url('assets/global/plugins/bootstrap-modal/css/bootstrap-modal.css'); ?>" rel="stylesheet" type="text/css" />
<?= link_tag('assets/global/plugins/select2/select2.css'); ?>

<link rel="stylesheet" type="text/css" href="<?= base_url('assets/css/map_pembelian_lain.css'); ?>" />
<link rel="stylesheet" type="text/css" href="<?= base_url('assets/css/map_style.css'); ?>" />

<!-- ------------------------------------------------- -->
<!-- get data from server -->
<!-- ------------------------------------------------- -->
<?php
// get header
$id_header = 0;
$tanggal = date('d/m/Y');
$no_faktur = '';
$supplier_id = '';
$supplier_nama = '';
$supplier_alamat = '';
$supplier_telepon = '';
$catatan = '';
$status_aktif  = '';
$status = -99;
$status_icon = 'fa fa-ban';

foreach ($header as $row) {
      $id_header = $row->id;
      $tanggal = is_reverse_date($row->tanggal);
      $no_faktur = $row->no_faktur;
      $supplier_id = $row->supplier_id;
      $supplier_nama = $row->supplier_nama;
      $supplier_alamat = $row->supplier_alamat;
      $supplier_telepon = $row->supplier_telepon;
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

//edit header
$show_supplier = '';

if ($supplier_id != '')
      $show_supplier = 'display: none';
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
                                    <table width='100%'>
                                          <tr>
                                                <td class='text-left' width="60%" style='vertical-align:top'>
                                                      <span style='font-size:2em'><b> No. Faktur : <?= $no_faktur; ?></b></span><br />
                                                      <span style='font-size:1.4em'>Tanggal : <?= $tanggal ?> </span> <br />
                                                </td>

                                                <td>
                                                      <button href="#portlet-config-edit" data-toggle='modal' class='btn btn-xs hidden-print' <?= $disabled_control; ?>>
                                                            <i class='fa fa-edit'></i> Edit
                                                      </button>

                                                      <br />

                                                      <table id="supplier-data" style='font-size:1.2em'>
                                                            <tr>
                                                                  <td>Kepada</td>
                                                                  <td class='padding-rl-5'> : </td>
                                                                  <td><b><?= $supplier_nama; ?></b> </td>
                                                            </tr>

                                                            <tr>
                                                                  <td style='vertical-align:top'>Alamat</td>
                                                                  <td style='vertical-align:top' class='padding-rl-5'> : </td>
                                                                  <td><b><?= nl2br($supplier_alamat); ?></b> </td>
                                                            </tr>

                                                            <tr>
                                                                  <td>Telepon</td>
                                                                  <td class='padding-rl-5'> : </td>
                                                                  <td><b><?= $supplier_telepon ?></b> </td>
                                                            </tr>
                                                      </table>
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
                                                                  Qty.
                                                            </th>
                                                            <th scope="col" class="text-center">
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

                                                      foreach ($pengeluaran_stok_lain_detail as $row) {
                                                      ?>

                                                      <tr id='id_<?= $row->id; ?>'>
                                                            <td hidden>
                                                                  <span class='id'><?= $row->id; ?></span>
                                                                  <span class='barang'><?= $row->barang; ?></span>
                                                                  <span class='qty'><?= $row->qty; ?></span>
                                                                  <span class='harga'><?= $row->harga; ?></span>
                                                            </td>

                                                            <td class="text-center">
                                                                  <?= $no_urut; ?>
                                                            </td>
                                                            <td>
                                                                  <?= $row->barang; ?>
                                                            </td>
                                                            <td class="text-right">
                                                                  <?= format_angka($row->qty); ?>
                                                            </td>
                                                            <td class="text-right">
                                                                  <?= format_angka($row->harga); ?>
                                                            </td>
                                                            <td class="text-right">
                                                                  <?php
                                                                        $total_harga = $row->qty * $row->harga;
                                                                        $grand_total += $total_harga;

                                                                        $qty_total += $row->qty;
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
                                                            </td>
                                                      </tr>

                                                      <?php
                                                            $no_urut++;
                                                      }
                                                      ?>

                                                      <tr class='subtotal-data'>
                                                            <td colspan='2' class='text-right'><b>TOTAL</b></td>
                                                            <td class='text-right'><b><?= format_angka($qty_total); ?></b></td>
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

<!-- -------------------------------------------------- -->
<!-- header baru  -->
<!-- -------------------------------------------------- -->
<div class="modal fade" autocomplete="off" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
            <form action="<?= base_url('transaksi/pembelian_lain/header_insert') ?>" class="form-horizontal" id="form_add_data" name="form_add_data" method="post" autocomplete="off">
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
                                    <label class="control-label col-md-3">Nomor Faktur
                                          <span class="required">*</span>
                                    </label>

                                    <div class="col-md-8">
                                          <input type="text" id="txtNoFaktur" name="txtNoFaktur" class="form-control" />
                                          <span class="help-block"></span>
                                    </div>
                              </div>

                              <div class="form-group">
                                    <label class="control-label col-md-3">Supplier
                                          <span class="required">*</span>
                                    </label>

                                    <div class="col-md-8">
                                          <select class='form-control' id='cboSupplier' name="cboSupplier">
                                                <option value="">Custom Supplier</option>

                                                <?php
                                                foreach ($this->supplier_list_aktif as $row) {
                                                ?>

                                                <option value="<?= $row->id ?>"><?= $row->nama; ?></option>

                                                <?php } ?>
                                          </select>
                                    </div>
                              </div>

                              <div class="form-group custom-supplier">
                                    <label class="control-label col-md-3">Nama Supplier
                                          <span class="required">*</span>
                                    </label>

                                    <div class="col-md-8">
                                          <input type="text" id="txtNamaSupplier" name="txtNamaSupplier" class="form-control" />
                                          <span class="help-block"></span>
                                    </div>
                              </div>

                              <div class="form-group custom-supplier">
                                    <label class="control-label col-md-3">Alamat</label>

                                    <div class="col-md-8">
                                          <input type="text" id="txtAlamatSupplier" name="txtAlamatSupplier" class="form-control" />
                                    </div>
                              </div>

                              <div class="form-group custom-supplier">
                                    <label class="control-label col-md-3">Telepon</label>

                                    <div class="col-md-8">
                                          <input type="text" id="txtTeleponSupplier" name="txtTeleponSupplier" class="form-control" />
                                    </div>
                              </div>

                              <div class="form-group">
                                    <label class="control-label col-md-3">Catatan
                                          <span class="required">*</span>
                                    </label>

                                    <div class="col-md-8">
                                          <textarea id="txtCatatan" name="txtCatatan" rows="3" style="width:100%"></textarea>
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
            <form action="<?= base_url('transaksi/pembelian_lain/header_edit') ?>" class="form-horizontal" id="form_edit_data" name="form_edit_data" method="post" autocomplete="off">
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

                                    <div class="col-md-8">
                                          <input type="text" readonly class="form-control date-picker" value="<?= $tanggal; ?>" id="dtpTanggalEdit" name="dtpTanggalEdit" />
                                    </div>
                              </div>

                              <div class="form-group">
                                    <label class="control-label col-md-3">Nomor Faktur
                                          <span class="required">*</span>
                                    </label>

                                    <div class="col-md-8">
                                          <input type="text" id="txtNoFakturEdit" name="txtNoFakturEdit" class="form-control" value="<?= $no_faktur; ?>" />
                                          <span class="help-block"></span>
                                    </div>
                              </div>

                              <div class="form-group">
                                    <label class="control-label col-md-3">Supplier
                                          <span class="required">*</span>
                                    </label>

                                    <div class="col-md-8">
                                          <select class='form-control' id='cboSupplierEdit' name="cboSupplierEdit">
                                                <option value="">Custom Supplier</option>

                                                <?php
                                                foreach ($this->supplier_list_aktif as $row) {
                                                      if ($supplier_id == $row->id)
                                                            $supplier_selected = 'selected';
                                                      else
                                                            $supplier_selected = '';
                                                ?>

                                                <option value="<?= $row->id ?>" <?= $supplier_selected; ?>><?= $row->nama; ?></option>

                                                <?php } ?>
                                          </select>
                                    </div>
                              </div>

                              <div class="form-group custom-supplier-edit" style="<?= $show_supplier; ?>">
                                    <label class="control-label col-md-3">Nama Supplier
                                          <span class="required">*</span>
                                    </label>

                                    <div class="col-md-8">
                                          <input type="text" id="txtNamaSupplierEdit" name="txtNamaSupplierEdit" class="form-control" value="<?= $supplier_nama; ?>" />
                                          <span class="help-block"></span>
                                    </div>
                              </div>

                              <div class="form-group custom-supplier-edit" style="<?= $show_supplier; ?>">
                                    <label class="control-label col-md-3">Alamat</label>

                                    <div class="col-md-8">
                                          <input type="text" id="txtAlamatSupplierEdit" name="txtAlamatSupplierEdit" class="form-control" value="<?= $supplier_alamat; ?>" />
                                    </div>
                              </div>

                              <div class="form-group custom-supplier-edit" style="<?= $show_supplier; ?>">
                                    <label class="control-label col-md-3">Telepon</label>

                                    <div class="col-md-8">
                                          <input type="text" id="txtTeleponSupplierEdit" name="txtTeleponSupplierEdit" class="form-control" value="<?= $supplier_telepon; ?>" />
                                    </div>
                              </div>

                              <div class="form-group">
                                    <label class="control-label col-md-3">Catatan</label>

                                    <div class="col-md-8">
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
            <form action="<?= base_url('transaksi/pembelian_lain/cari') ?>" class="form-horizontal" id="form_cari" method="post" autocomplete="off">
                  <div class="modal-content">
                        <div class="modal-header">
                              <h3>Pencarian Transaksi</h3>
                        </div>

                        <div class="modal-body">
                              <div class="form-group">
                                    <label class="control-label col-md-3">Nomor Faktur</label>

                                    <div class="col-md-8">
                                          <input type="text" class="form-control" id="txtNoFakturCari" name="txtNoFakturCari" />
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
            <form action="<?= base_url('transaksi/pembelian_lain/barang_insert') ?>" class="form-horizontal" id="form_add_barang" name="form_add_barang" method="post" autocomplete="off">
                  <div class="modal-content">
                        <div class="modal-header">
                              <h3>Tambah Barang</h3>

                              <input type="hidden" id="txtBarangHeaderId" name='txtBarangHeaderId' value='<?= $id_header; ?>'>
                        </div>

                        <div class="modal-body">
                              <div class="form-group">
                                    <label class="control-label col-md-3">Barang
                                          <span class="required">*</span>
                                    </label>

                                    <div class="col-md-8">
                                          <input type="text" class='form-control' id="txtBarang" name="txtBarang" />
                                          <span class="help-block"></span>
                                    </div>
                              </div>

                              <div class="form-group">
                                    <label class="control-label col-md-3">Qty.
                                          <span class="required">*</span>
                                    </label>

                                    <div class="col-md-8">
                                          <input type="number" class='form-control' id='txtQty' name="txtQty" />
                                          <span class="help-block"></span>
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

                              <div class="form-group">
                                    <label class="control-label col-md-3">Total Harga</label>

                                    <div class="col-md-8">
                                          <input type="text" class='form-control' id='txtTotalHarga' name="txtTotalHarga" readonly />
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

<!-- ----------------------------------------------- -->
<!-- EDIT BARANG -->
<!-- ----------------------------------------------- -->
<div class="modal fade bs-modal" id="modal_edit_barang" tabindex="-1" role="dialog" aria-labelledby="MyBarangEdit" aria-hidden="true">
      <div class="modal-dialog modal-md">
            <form action="<?= base_url('transaksi/pembelian_lain/barang_edit') ?>" class="form-horizontal" id="form_edit_barang" name="form_edit_barang" method="post" autocomplete="off">
                  <div class="modal-content">
                        <div class="modal-header">
                              <h3>Edit Barang</h3>

                              <input type="hidden" id="txtIdEdit" name='txtIdEdit' value='<?= $id_header; ?>'>
                              <input type="hidden" id="txtIdDetailEdit" name='txtIdDetailEdit'>
                        </div>

                        <div class="modal-body">
                              <div class="form-group">
                                    <label class="control-label col-md-3">Barang</label>

                                    <div class="col-md-8">
                                          <input type="text" id="txtBarangEdit" name='txtBarangEdit' class='form-control'>
                                          <span class="help-block"></span>
                                    </div>
                              </div>

                              <div class="form-group">
                                    <label class="control-label col-md-3">Qty.
                                          <span class="required">*</span>
                                    </label>

                                    <div class="col-md-8">
                                          <input type="number" class='form-control' id='txtQtyEdit' name="txtQtyEdit" />
                                          <span class="help-block"></span>
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

                              <div class="form-group">
                                    <label class="control-label col-md-3">Total Harga</label>

                                    <div class="col-md-8">
                                          <input type="text" class='form-control' id='txtTotalHargaEdit' name="txtTotalHargaEdit" readonly />
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

<!-- ----------------------------------- -->
<!-- unlock -->
<!-- ----------------------------------- -->
<div class="modal fade" id="modal-unlock" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
            <div class="modal-content">
                  <form action="<?= base_url('transaksi/pembelian_lain/unlock'); ?>" class="form-horizontal" id="form-unlock" name="form-unlock" method="post" autocomplete="off">
                        <div class="modal-header">
                              <h3>Request Open</h3>

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
      $("#cboTipe, #cboTipeEdit").select2();
      $("#cboSupplier, #cboSupplierEdit").select2();
      $('#cboGudang, #cboGudangEdit').select2();

      //select custom supplier
      $('#cboSupplier').change(function() {
            var supplier = $('#cboSupplier').val();

            if (supplier == '') {
                  $('.custom-supplier').show();
                  $('#txtNamaSupplier').val("");
                  $('#txtAlamatSupplier').val("");
                  $('#txtTeleponSupplier').val("");
            } else {
                  $('.custom-supplier').hide();
            }
      });

      //insert header
      $(".btn-save").click(function() {
            $('#form_add_data').click(function() {
                  $.ajax({
                        url: "<?= base_url('transaksi/pembelian_lain/header_insert_validate_start') ?>",
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

      //edit header
      $(".btn-edit-save").click(function() {
            $.ajax({
                  url: "<?= base_url('transaksi/pembelian_lain/header_edit_validate_start') ?>",
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

      //select custom supplier edit
      $('#cboSupplierEdit').change(function() {
            var supplier = $('#cboSupplierEdit').val();

            if (supplier == '') {
                  $('.custom-supplier-edit').show();
                  $('#txtNamaSupplierEdit').val("");
                  $('#txtAlamatSupplierEdit').val("");
                  $('#txtTeleponSupplierEdit').val("");
            } else {
                  $('.custom-supplier-edit').hide();
            }
      });

      //pencarian
      $(".btn-cari").click(function() {
            $.ajax({
                  url: "<?= base_url('transaksi/pembelian_lain/cari_validate_start') ?>",
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

      //save barang
      $(".btn-save-brg").click(function() {
            $.ajax({
                  url: "<?= base_url('transaksi/pembelian_lain/barang_insert_validate_start') ?>",
                  type: "POST",
                  data: $('#form_add_barang').serialize(),
                  dataType: "JSON",
                  success: function(data) {
                        if (data.status) {
                              $('#form_add_barang').submit();
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

      // edit barang
      $('#general_table').on('click', '.btn-edit-barang', function() {
            const ini = $(this).closest('tr');
            let form = $('#form_edit_barang');

            var qty = ini.find('.qty').html();
            var harga = ini.find('.harga').html();
            var total_harga = qty * harga;

            form.find("[name=txtIdDetailEdit]").val(ini.find('.id').html());
            form.find("[name=txtBarangEdit]").val(ini.find('.barang').html());
            form.find("[name=txtQtyEdit]").val(ini.find('.qty').html());
            form.find("[name=txtHargaEdit]").val(ini.find('.harga').html());
            form.find("[name=txtTotalHargaEdit]").val(addCommas(total_harga));
      });

      $(".btn-edit-brg").click(function() {
            $.ajax({
                  url: "<?= base_url('transaksi/pembelian_lain/barang_edit_validate_start') ?>",
                  type: "POST",
                  data: $('#form_edit_barang').serialize(),
                  dataType: "JSON",
                  success: function(data) {
                        if (data.status) {
                              $('#form_edit_barang').submit();
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

      // hapus barang
      $('#general_table').on('click', '.btn-detail-remove', function() {
            var ini = $(this).closest('tr');
            bootbox.confirm("Mau menghapus item ini ? ", function(respond) {
                  if (respond) {
                        var data = {};
                        data['id'] = ini.find('.id').html();
                        var url = 'transaksi/pembelian_lain/barang_delete';

                        ajax_data_sync(url, data).done(function(data_respond) {
                              if (data_respond == 'OK') {
                                    ini.remove();
                                    window.location.reload();
                              };
                        });
                  };
            });
      });

      //lock
      $('.btn-lock').click(function() {
            var id_header = "<?= $id_header ?>";

            bootbox.confirm("Transaksi sudah selesai. <b>Lock transaksi ini?</b>", function(respond) {
                  if (respond) {
                        var data = {};
                        data['id'] = id_header;
                        var url = 'transaksi/pembelian_lain/lock';

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
                  url: "<?= base_url('transaksi/pembelian_lain/unlock_validate_start') ?>",
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

      // hitung total harga
      $("#txtQty, #txtHarga").focusout(function() {
            hitung_total_harga();
      });

      // hitung total harga (edit)
      $("#txtQtyEdit, #txtHargaEdit").focusout(function() {
            hitung_total_harga_edit();
      });
});
</script>

<script>
//hitung total harga
function hitung_total_harga() {
      var qty = $("#txtQty").val();
      var harga = $("#txtHarga").val();

      var total_harga = parseFloat(harga) * parseFloat(qty);
      $("#txtTotalHarga").val(addCommas(total_harga));
}

//hitung total harga edit
function hitung_total_harga_edit() {
      var qty = $("#txtQtyEdit").val();
      var harga = $("#txtHargaEdit").val();

      var total_harga = parseFloat(harga) * parseFloat(qty);
      $("#txtTotalHargaEdit").val(addCommas(total_harga));
}

// digit grouping
function addCommas(nStr) {
      //cek region
      var reg = 'E';
      var hasil = 100 / 3;
      hasil += '';
      var hasilx = hasil.split(',');
      reg = hasilx.length > 1 ? 'I' : 'E';

      var hasil_akhir = '0';

      //if english
      if (reg = 'E') {
            nStr += '';
            x = nStr.split('.');
            x1 = x[0];
            x2 = x.length > 1 ? ',' + x[1] : '';
            var rgx = /(\d+)(\d{3})/;
            while (rgx.test(x1)) {
                  x1 = x1.replace(rgx, '$1' + ',' + '$2');
            }
            hasil_akhir = x1 + x2;
      }

      //if indonesia
      if (reg = 'I') {
            nStr += '';
            x = nStr.split(',');
            x1 = x[0];
            x2 = x.length > 1 ? '.' + x[1] : '';
            var rgx = /(\d+)(\d{3})/;
            while (rgx.test(x1)) {
                  x1 = x1.replace(rgx, '$1' + '.' + '$2');
            }
            hasil_akhir = x1 + x2;
      }

      return hasil_akhir;
}
</script>