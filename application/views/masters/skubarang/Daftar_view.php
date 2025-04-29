<link rel="stylesheet" type="text/css" href="<?= base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css'); ?>" />
<link rel="stylesheet" type="text/css" href="<?= base_url('assets/global/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css'); ?>" />
<link rel="stylesheet" type="text/css" href="<?= base_url('assets/global/plugins/bootstrap-modal/css/bootstrap-modal.css'); ?>" />
<link rel="stylesheet" type="text/css" href="<?= base_url('assets/global/plugins/select2/select2.css'); ?>" />

<div class="page-content">
      <div class='container'>
            <div class="row margin-top-10">
                  <div class="col-md-12">
                        <div class="portlet light">
                              <!-- tab -->
                              <ul class="nav nav-tabs">
                                    <li><a href="<?= base_url(is_setting_link('masters/barang/daftar')); ?>">Barang</a></li>
                                    <li><a href="<?= base_url(is_setting_link('masters/warna/daftar')); ?>">Link Warna</a></li>
                                    <li class="active"><a href="<?= base_url(is_setting_link('masters/skubarang/daftar')); ?>">SKU Barang</a></li>
                              </ul>

                              <div class="portlet-title">
                                    <div class="caption caption-md">
                                          <i class="icon-bar-chart theme-font hide"></i>
                                          <span class="caption-subject theme-font bold uppercase"><?= $breadcrumb_small; ?></span>
                                    </div>

                                    <div class="actions">
                                          <a href="#mdlFilter" data-toggle='modal' class="btn btn-default btn-xs" id="btnFilter" name="btnFilter">
                                                <i class="fa fa-filter"></i> Filter
                                          </a>
                                    </div>
                              </div>

                              <div class="portlet-body">
                                    <table id="table" class="table table-striped table-bordered table-hover">
                                          <thead>
                                                <tr>
                                                      <th class="text-center">Id</th>
                                                      <th class="text-center">Kode</th>
                                                      <th class="text-center">Nama Beli</th>
                                                      <th class="text-center">Nama Jual</th>
                                                      <th class="text-center">Satuan</th>
                                                      <th class="text-center">Jumlah SKU</th>
                                                      <th class="text-center" width="30px">Actions</th>
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
                                    <label class="control-label col-md-3">Kategori
                                          <span class="required"></span>
                                    </label>

                                    <div class="col-md-8">
                                          <select class='form-control' id='cboKategoriFilter' name="cboKategoriFilter" style="width: 100%">
                                          </select>

                                          <span id="errKategoriFilter" name="errKategoriFilter" class="help-block" style="color: red"></span>
                                    </div>
                              </div>

                              <div class="form-group">
                                    <label class="control-label col-md-3">Tipe
                                          <span class="required"></span>
                                    </label>

                                    <div class="col-md-8">
                                          <select class='form-control' id='cboTipeFilter' name="cboTipeFilter" style="width: 100%">
                                          </select>

                                          <span id="errTipeFilter" name="errTipeFilter" class="help-block" style="color: red"></span>
                                    </div>
                              </div>

                              <div class="form-group">
                                    <label class="control-label col-md-3">Fitur
                                          <span class="required"></span>
                                    </label>

                                    <div class="col-md-8">
                                          <select class='form-control' id='cboFiturFilter' name="cboFiturFilter" style="width: 100%">
                                          </select>

                                          <span id="errfiturFilter" name="errfiturFilter" class="help-block" style="color: red"></span>
                                    </div>
                              </div>

                              <div class="form-group">
                                    <label class="control-label col-md-3">Grade
                                          <span class="required"></span>
                                    </label>

                                    <div class="col-md-8">
                                          <select class='form-control' id='cboGradeFilter' name="cboGradeFilter" style="width: 100%">
                                          </select>

                                          <span id="errGradeFilter" name="errGradeFilter" class="help-block" style="color: red"></span>
                                    </div>
                              </div>

                              <div class="form-group">
                                    <label class="control-label col-md-3">Jenis Katalog Warna
                                          <span class="required"></span>
                                    </label>

                                    <div class="col-md-8">
                                          <select class='form-control' id='cboJenisFilter' name="cboJenisFilter" style="width: 100%">
                                          </select>

                                          <span id="errJenisFilter" name="errJenisFilter" class="help-block" style="color: red"></span>
                                    </div>
                              </div>

                              <div class="form-group">
                                    <label class="control-label col-md-3">Status SKU</label>

                                    <div class="col-md-8">
                                          <select id='cboSudahSKU' name='cboSudahSKU' style="width: 100%" class="form-control">
                                                <option value="0" selected>Belum</option>
                                                <option value="1">Sudah</option>
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
      var address_root = '<?= get_api_base_link(); ?>' + 'sku_barang/';
      var address_root_kategori = '<?= get_api_base_link(); ?>' + 'kategori_barang/';
      var address_root_tipe = '<?= get_api_base_link(); ?>' + 'tipe_barang/';
      var address_root_fitur = '<?= get_api_base_link(); ?>' + 'barang_fitur/';
      var address_root_grade = '<?= get_api_base_link(); ?>' + 'barang_grade/';
      var address_root_jenis = '<?= get_api_base_link(); ?>' + 'katalog_warna_jenis/';
      var base_code = '<?= get_api_base_code(); ?>';
      var token = '<?= get_api_key(); ?>';

      var table;

      $(document).ready(function() {
            $("#cboTipeFilter, #cboKategoriFilter, #cboFiturFilter, #cboGradeFilter, #cboJenisFilter, #cboSudahSKU").select2();

            // populate datatables
            table = $('#table').DataTable({
                  "pageLength": 25,
                  "ajax": {
                        'url': address_root + 'list/' + base_code + '/0/0/0/0/0/0',
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
                              "data": "kode"
                        },
                        {
                              "data": "nama"
                        },
                        {
                              "data": "nama_jual"
                        },
                        {
                              "data": "satuan_nama"
                        },
                        {
                              "data": "jumlah_sku"
                        },
                  ],
                  "columnDefs": [{
                              "targets": 0,
                              "visible": false
                        },
                        {
                              "targets": 5,
                              "className": "text-center",
                        },
                        {
                              "targets": 6,
                              "orderable": false,
                              "className": "text-center",
                              "width": "30px",
                              "render": function(data, type, row, meta) {
                                    let hasil = JSON.stringify([row['id'], row['kode'], row['nama'], row['nama_jual'], row['satuan_id'], row['satuan_nama'], row['kategori_id'], row['kategori_nama'], row['tipe_id'], row['tipe_nama'], row['fitur_id'], row['fitur_nama'], row['grade_id'], row['grade_nama'], row['jenis_katalog_warna_id'], row['jenis_katalog_warna_kode']]);

                                    return `<a href="#mdlAdd" data-toggle="modal" id="btnEdit" name="btnEdit" class="btn btn-default btn-xs">
            						<i class="fa fa-cubes"></i>
            					  </a>
            					  <span class='id' hidden>${hasil}</span>`;
                              }
                        },
                  ]
            });

            //filter data
            $('#btnStart').click(function() {
                  let kategori_id = $("#cboKategoriFilter").val();
                  let tipe_id = $("#cboTipeFilter").val();
                  let fitur_id = $("#cboFiturFilter").val();
                  let grade_id = $("#cboGradeFilter").val();
                  let jenis_id = $("#cboJenisFilter").val();
                  let sudah_sku = $("#cboSudahSKU").val();

                  table.ajax.url(address_root + 'list/' + base_code + '/' + kategori_id + '/' + tipe_id + '/' + fitur_id + '/' + grade_id + '/' + jenis_id + '/' + sudah_sku).load();

                  $('#mdlFilter').modal('toggle');
            });

            // combo kategori
            $.ajax({
                  url: address_root_kategori + 'list/1',
                  method: "get",
                  async: false,
                  dataType: 'json',
                  'data': {
                        'token': token
                  },
                  success: function(data) {
                        let html = '';
                        let i;

                        html += `<option value='0'>Pilih Kategori</option>`;

                        let hasil = data['data'];

                        for (var k in hasil) {
                              html += `<option value='` + hasil[k].id + `'>` + hasil[k].nama + `</option>`;
                        }

                        $('#cboKategoriFilter').html(html);
                        $("#cboKategoriFilter").val('0');
                        $("#cboKategoriFilter").change();
                  }
            });

            // combo tipe
            $.ajax({
                  url: address_root_tipe + 'list/1',
                  method: "get",
                  async: false,
                  dataType: 'json',
                  'data': {
                        'token': token
                  },
                  success: function(data) {
                        let html = '';
                        let i;

                        html += `<option value='0'>Pilih Tipe</option>`;

                        let hasil = data['data'];

                        for (var k in hasil) {
                              html += `<option value='` + hasil[k].id + `'>` + hasil[k].nama + `</option>`;
                        }

                        $('#cboTipeFilter').html(html);
                        $("#cboTipeFilter").val('0');
                        $("#cboTipeFilter").change();
                  }
            });

            // combo fitur
            $.ajax({
                  url: address_root_fitur + 'list/1',
                  method: "get",
                  async: false,
                  dataType: 'json',
                  'data': {
                        'token': token
                  },
                  success: function(data) {
                        let html = '';
                        let i;

                        html += `<option value='0'>Pilih Fitur</option>`;

                        let hasil = data['data'];

                        for (var k in hasil) {
                              html += `<option value='` + hasil[k].id + `'>` + hasil[k].nama + `</option>`;
                        }

                        $('#cboFiturFilter').html(html);
                        $("#cboFiturFilter").val('0');
                        $("#cboFiturFilter").change();
                  }
            });

            // combo grade
            $.ajax({
                  url: address_root_grade + 'list/1',
                  method: "get",
                  async: false,
                  dataType: 'json',
                  'data': {
                        'token': token
                  },
                  success: function(data) {
                        let html = '';
                        let i;

                        html += `<option value='0'>Pilih Grade</option>`;

                        let hasil = data['data'];

                        for (var k in hasil) {
                              html += `<option value='` + hasil[k].id + `'>` + hasil[k].nama + `</option>`;
                        }

                        $('#cboGradeFilter').html(html);
                        $("#cboGradeFilter").val('0');
                        $("#cboGradeFilter").change();
                  }
            });

            // combo jenis katalog warna
            $.ajax({
                  url: address_root_jenis + 'list/1',
                  method: "get",
                  async: false,
                  dataType: 'json',
                  'data': {
                        'token': token
                  },
                  success: function(data) {
                        let html = '';
                        let i;

                        html += `<option value='0'>Pilih Jenis</option>`;

                        let hasil = data['data'];

                        for (var k in hasil) {
                              html += `<option value='` + hasil[k].id + `'>` + hasil[k].nama + `</option>`;
                        }

                        $('#cboJenisFilter').html(html);
                        $("#cboJenisFilter").val('0');
                        $("#cboJenisFilter").change();
                  }
            });
      });
</script>