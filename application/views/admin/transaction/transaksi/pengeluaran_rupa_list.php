<!-- -------------------------------------------------- -->
<!-- transaksi > register component -->
<!-- -------------------------------------------------- -->
<link rel="stylesheet" type="text/css" href="<?= base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css'); ?>" />
<link rel="stylesheet" type="text/css" href="<?= base_url('assets/global/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css'); ?>" />
<link href="<?= base_url('assets/global/plugins/bootstrap-modal/css/bootstrap-modal.css'); ?>" rel="stylesheet" type="text/css" />

<?= link_tag('assets/global/plugins/select2/select2.css'); ?>

<link rel="stylesheet" type="text/css" href="<?= base_url('assets/css/map_po_pembelian_list.css'); ?>" />
<link rel="stylesheet" type="text/css" href="<?= base_url('assets/css/map_style.css'); ?>" />


<!-- -------------------------------------------------- -->
<!-- list data -->
<!-- -------------------------------------------------- -->
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

                                    <div class="actions">
                                          <a href="#portlet-config" data-toggle='modal' class="btn btn-default btn-xs btn-form-add">
                                                <i class="fa fa-plus"></i> Baru
                                          </a>

                                          <a href="#portlet-config-cari" data-toggle='modal' class="btn btn-default btn-xs btn-form-cari">
                                                <i class="fa fa-search"></i> Cari
                                          </a>

                                          <a href="#portlet-config-filter" data-toggle='modal' class="btn btn-default btn-xs btn-form-filter">
                                                <i class="fa fa-filter"></i> Filter
                                          </a>
                                    </div>
                              </div>

                              <div class="portlet-body">
                                    <table class="table table-hover table-striped table-bordered" id="general_table">
                                          <thead>
                                                <tr>
                                                      <th scope="col" class='status_column text-center'>
                                                            Status Aktif
                                                      </th>

                                                      <th scope="col" class="text-center">
                                                            Tanggal
                                                      </th>

                                                      <th scope="col" class="text-center">
                                                            No. Faktur
                                                      </th>

                                                      <th scope="col" class='text-center'>
                                                            Keterangan
                                                      </th>

                                                      <th scope="col" class="text-center">
                                                            Grand Total
                                                      </th>

                                                      <th scope="col" class="status_column text-center">
                                                            Status
                                                      </th>

                                                      <th scope="col" class="text-center" style="width: 25px">
                                                            Actions
                                                      </th>
                                                </tr>
                                          </thead>

                                          <tbody>
                                                <!-- isi data di sini... -->
                                          </tbody>
                                    </table>
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

                                    <div class="col-md-6">
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

<!-- -------------------------------------------------- -->
<!-- cari -->
<!-- -------------------------------------------------- -->
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
                              <button type="button" class="btn  btn-active default pull-left" data-dismiss="modal">Close</button>
                        </div>
                  </div>
            </form>
      </div>
</div>

<!-- -------------------------------------------------- -->
<!-- filter list -->
<!-- -------------------------------------------------- -->
<div class="modal fade" id="portlet-config-filter" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
            <form action="" class="form-horizontal" id="form_filter" method="post" autocomplete="off">
                  <div class="modal-content">
                        <div class="modal-header">
                              <h3>Filter Data</h3>
                        </div>

                        <div class="modal-body">
                              <div class="form-group">
                                    <label class="control-label col-md-3">Status</label>

                                    <div class="col-md-8">
                                          <select class="form-control" id='cboStatus' name='cboStatus'>
                                                <option value="0" class="text-left" selected>Lock</option>
                                                <option value="1" class="text-left">Belum Lock</option>
                                          </select>
                                    </div>
                              </div>

                              <div class="form-group">
                                    <label class="control-label col-md-3">Tanggal</label>

                                    <div class="col-md-8">
                                          <div class="input-group input-large date-picker input-daterange">
                                                <input type="text" class="form-control" id="dtpTglDari" name="dtpTglDari" value='<?= $tgl_dari; ?>'>
                                                <span class="input-group-addon">s/d</span>
                                                <input type="text" class="form-control" id="dtpTglSampai" name="dtpTglSampai" value='<?= date('d/m/Y'); ?>'>
                                          </div>
                                    </div>
                              </div>
                        </div>

                        <div class="modal-footer">
                              <button type="button" class="btn btn-active btn-trigger blue btn-filter pull-left">Filter</button>
                              <button type="button" class="btn  btn-active default pull-left" data-dismiss="modal">Close</button>
                        </div>
                  </div>
            </form>
      </div>
</div>

<script src="<?= base_url('assets/global/plugins/bootstrap-modal/js/bootstrap-modalmanager.js'); ?>" type="text/javascript"></script>
<script src="<?= base_url('assets/global/plugins/bootstrap-modal/js/bootstrap-modal.js'); ?>" type="text/javascript"></script>
<script src="<?= base_url('assets/global/plugins/jquery-validation/js/jquery.validate.min.js'); ?>" type="text/javascript"></script>
<script src="<?= base_url('assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js'); ?>" type="text/javascript"></script>
<script src="<?= base_url('assets/global/plugins/datatables/media/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?= base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js'); ?>" type="text/javascript"></script>
<script src="<?= base_url('assets_noondev/js/table-advanced.js'); ?>"></script>
<script src="<?= base_url('assets/global/plugins/bootbox/bootbox.min.js'); ?>" type="text/javascript"></script>
<script src="<?= base_url('assets_noondev/js/fnReload.js'); ?>"></script>

<script>
jQuery(document).ready(function() {
      $("#general_table").DataTable({
            "fnCreatedRow": function(nRow, aData, iDataIndex) {
                  var status = $('td:eq(5)', nRow).text().split('??');
                  var id = status[0];
                  var no_faktur = status[1];

                  $('td:eq(0)', nRow).addClass('status_column');
                  $('td:eq(6)', nRow).addClass('status_column');

                  $('td:eq(4)', nRow).html('<span class="grand_total">' + change_number_format($('td:eq(4)', nRow).text()) + '</span>');

                  var url = "<?= base_url() . is_setting_link('transaksi/pengeluaran_rupa/editor'); ?>?id=" + id;
                  var btn_edit = '<a href="' + url + '" class="btn-xs btn green btn-view" target="_blank"><i class="fa fa-edit"></i> </a>';
                  $('td', nRow).eq(5).html(btn_edit);
            },
            "ordering": false,
            "bStateSave": true,
            "bProcessing": true,
            "bServerSide": true,
            "sAjaxSource": baseurl + "transaksi/pengeluaran_rupa/daftar_ajax?status_aktif=1",
            "order": [
                  [1, 'desc']
            ],
      });

      //filter data
      $(".btn-filter").click(function() {
            let status = $('#cboStatus').val();
            let tgl_dari = $("#dtpTglDari").val();
            let tgl_sampai = $("#dtpTglSampai").val();
            oTable.fnReloadAjax(baseurl + "transaksi/pengeluaran_rupa/daftar_ajax?status='" + status + "'&tgl_dari='" + format_tanggal(tgl_dari) + "'&tgl_sampai='" + format_tanggal(tgl_sampai) + "'");
      });

      //register table
      var oTable;
      oTable = $('#general_table').dataTable();
      oTable.fnFilter('', 0);

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
});
</script>

<script>
function change_number_comma(number) {
      let n = parseFloat(number).toString().split('.');
      if (typeof n[1] === 'undefined') {
            n[1] = '';
      } else {
            n[1] = ',' + n[1];
      }

      return change_number_format(n[0]) + n[1];
}

function format_tanggal(tanggal) {
      tanggal_baru = tanggal.split("/");

      return tanggal_baru[2] + '-' + tanggal_baru[1] + '-' + tanggal_baru[0] + ' 00:00:00';
}
</script>