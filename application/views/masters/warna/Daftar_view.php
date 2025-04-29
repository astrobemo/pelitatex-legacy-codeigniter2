<link rel="stylesheet" type="text/css" href="<?= base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css'); ?>" />
<link rel="stylesheet" type="text/css" href="<?= base_url('assets/global/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css'); ?>" />
<link rel="stylesheet" type="text/css" href="<?= base_url('assets/global/plugins/bootstrap-modal/css/bootstrap-modal.css'); ?>" />
<link rel="stylesheet" type="text/css" href="<?= base_url('assets/global/plugins/select2/select2.css'); ?>" />
<link rel="stylesheet" type="text/css" href="<?= base_url('assets/global/plugins/bootstrap-colorpicker/css/colorpicker.css'); ?>" />

<div class="page-content">
      <div class='container'>
            <div class="row margin-top-10">
                  <div class="col-md-12">
                        <div class="portlet light">
                              <!-- tab -->
                              <ul class="nav nav-tabs">
                                    <li><a href="<?= base_url(is_setting_link('masters/barang/daftar')); ?>">Barang</a></li>
                                    <li class="active"><a href="<?= base_url(is_setting_link('masters/warna/daftar')); ?>">Link Warna</a></li>
                                    <li><a href="<?= base_url(is_setting_link('masters/skubarang/daftar')); ?>">SKU Barang</a></li>
                              </ul>

                              <div class="portlet-title">
                                    <div class="caption caption-md">
                                          <i class="icon-bar-chart theme-font hide"></i>
                                          <span class="caption-subject theme-font bold uppercase"><?= $breadcrumb_small; ?></span>
                                    </div>

                                    <div class="actions">
                                          <a href="#" data-toggle='modal' class="btn btn-default btn-xs btn-form-add" id="btnAdd" name="btnAdd">
                                                <i class="fa fa-plus"></i> Baru
                                          </a>

                                          <a href="#mdlFilter" data-toggle='modal' class="btn btn-default btn-xs" id="btnFilter" name="btnFilter">
                                                <i class="fa fa-filter"></i> Filter
                                          </a>
                                    </div>
                              </div>

                              <div class="portlet-body">
                                    <table id="table" class="table table-bordered table-hover">
                                          <thead>
                                                <tr>
                                                      <th class="text-center">Id</th>
                                                      <th class="text-center">Nama</th>
                                                      <th class="text-center">Katalog Warna</th>
                                                      <th class="text-center" width="100px">Kode Warna</th>
                                                      <th class="text-center" width="100px">Actions</th>
                                                </tr>
                                          </thead>

                                          <tbody>
                                          </tbody>
                                    </table>
                              </div>
                        </div>
                  </div>
            </div>
      </div>
</div>

<div class="modal fade" autocomplete="off" id="mdlFilter" name="mdlFilter" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
            <form id="form-filter" class="form-horizontal">
                  <div class="modal-content">
                        <div class="modal-header">
                              <h3>Filter Data</h3>
                        </div>

                        <div class="modal-body">
                              <div class="form-group">
                                    <label class="control-label col-md-3">Status Link</label>

                                    <div class="col-md-8">
                                          <select id='cboSudahLink' name='cboSudahLink' style="width: 100%" class="form-control">
                                                <option value="0" selected>Belum</option>
                                                <option value="1">Sudah</option>
                                          </select>
                                    </div>
                              </div>

                              <div class="form-group">
                                    <label class="control-label col-md-3">Status Aktif</label>

                                    <div class="col-md-8">
                                          <select id='cboStatusFilter' name='cboStatusFilter' style="width: 100%" class="form-control">
                                                <option value="1" selected>Aktif</option>
                                                <option value="0">Non Aktif</option>
                                          </select>
                                    </div>
                              </div>
                        </div>

                        <div class="modal-footer">
                              <button type="button" class="btn btn-active btn-trigger blue pull-left" id="btnStart" name="btnStart">
                                    <i class="fa fa-flag"></i> Start
                              </button>

                              <button type="button" class="btn  btn-active default pull-left" data-dismiss="modal" id='btnResetFilter' name='btnResetFilter'>
                                    <i class="fa fa-times"></i> Close
                              </button>
                        </div>
                  </div>
            </form>
      </div>
</div>

<?php $this->load->view($editor); ?>
<?php $this->load->view($linker); ?>

<script type="text/javascript" src="<?= base_url('assets/global/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.js'); ?>"></script>
<script src="<?php echo base_url('assets_noondev/js/components-pickers.js'); ?>"></script>
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
      var address_root = '<?= get_api_base_link(); ?>' + 'warna/';
      var base_code = '<?= get_api_base_code(); ?>';
      var token = '<?= get_api_key(); ?>';

      var table;

      $(document).ready(function() {
            // populate datatables
            table = $('#table').DataTable({
                  "pageLength": 25,
                  "ajax": {
                        'url': address_root + 'list/' + base_code + '/0/1',
                        'type': 'get',
                        'dataType': 'JSON',
                        'data': {
                              'token': token
                        }
                  },
                  "columns": [{
                              "data": "id"
                        },
                        {
                              "data": "warna_beli"
                        },
                        {
                              "data": "katalog_warna"
                        },
                        {
                              "data": "kode_warna",
                              createdCell: function(td, cellData, rowData, row, col) {
                                    $(td).css('background-color', cellData);
                                    $(td).css('color', '#ffffff');
                              }
                        },
                  ],
                  "columnDefs": [{
                              "targets": 0,
                              "visible": false
                        },
                        {
                              "targets": 2,
                              "className": "text-center"
                        },
                        {
                              "targets": 3,
                              "className": "text-center",
                              "width": "100px"
                        },
                        {
                              "targets": 4,
                              "orderable": false,
                              "className": "text-center",
                              "width": "100px",
                              "render": function(data, type, row, meta) {
                                    let hasil = JSON.stringify([row['id'], row['warna_beli'], row['kode_warna'], row['status_aktif']]);

                                    return `<a href="#mdlAdd" data-toggle="modal" id="btnEdit" name="btnEdit" class="btn btn-default btn-xs">
            						<i class="fa fa-pencil"></i>
            					  </a>
                                            <a href="#mdlLink" data-toggle="modal" id="btnLink" name="btnLink" class="btn btn-default btn-xs">
            						<i class="fa fa-stethoscope"></i>
            					  </a>
            					  <a href="#" data-toggle="modal" id="btnTerminate" name="btnTerminate" class="btn btn-default btn-xs">
            						<i class="fa fa-bell-slash"></i>
            					  </a>
            					  <span class='id' hidden>${hasil}</span>`;
                              }
                        },
                  ]
            });

            //filter data
            $('#btnStart').click(function() {
                  let status_aktif = $("#cboStatusFilter").val();
                  let sudah_link = $("#cboSudahLink").val();
                  table.ajax.url(address_root + 'list/' + base_code + '/' + sudah_link + '/' + status_aktif).load();
                  $("#mdlFilter").modal('toggle');
            });

            //terminate 
            $('#table').on('click', '#btnTerminate', function() {
                  const ini = $(this).closest('tr');
                  let hasil = ini.find('.id').html();
                  const data = JSON.parse(hasil);

                  let id = data[0];
                  let status_aktif = data[3];

                  let ubah_status_aktif = status_aktif == 1 ? 0 : 1;
                  let title_me = '';
                  let message_me = '';

                  if (status_aktif == 1) {
                        title_me = "Nonaktifkan Data?";
                        message_me = "Menonaktifkan data akan membuat data tidak dapat dipakai untuk transaksi. Data tetap dapat dipakai untuk melihat History atau Laporan.";
                  } else {
                        title_me = "Aktifkan Data?";
                        message_me = "Data dapat dipakai kembali untuk transaksi.";
                  }

                  bootbox.confirm({
                        title: title_me,
                        message: message_me,
                        buttons: {
                              cancel: {
                                    label: '<i class="fa fa-times"></i> Cancel'
                              },
                              confirm: {
                                    label: '<i class="fa fa-check"></i> Confirm'
                              }
                        },
                        callback: function(result) {
                              if (result == true) {
                                    $.ajax({
                                          url: address_root + 'ubah_status/' + id,
                                          data: {
                                                'token': token,
                                                'status_aktif': ubah_status_aktif
                                          },
                                          method: 'put',
                                          dataType: 'json'
                                    });

                                    window.location.reload();
                              }
                        }
                  });
            });
      });
</script>