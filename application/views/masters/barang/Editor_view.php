<div class="modal fade" autocomplete="off" id="mdlAdd" name="mdlAdd" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
            <form action="#" class="form-horizontal" id="frmAdd" name="frmAdd" method="post" autocomplete="off">
                  <div class="modal-content">
                        <div class="modal-header">
                              <h3><span id="flgData" name="flgData"></span> <b>Barang</b></h3>

                              <input type="hidden" id="txtFlag" name="txtFlag" class="form-control" />
                              <input type="hidden" id="txtId" name="txtId" class="form-control" />
                        </div>

                        <div class="modal-body">
                              <div class="form-group">
                                    <label class="control-label col-md-3">Nama Beli
                                          <span class="required">*</span>
                                    </label>

                                    <div class="col-md-8">
                                          <input type="text" id="txtNamaBeli" name="txtNamaBeli" class="form-control required" />
                                          <span id="errNamaBeli" name="errNamaBeli" class="help-block" style="color: red"></span>
                                    </div>
                              </div>

                              <div class="form-group">
                                    <label class="control-label col-md-3">Nama Jual
                                          <span class="required">*</span>
                                    </label>

                                    <div class="col-md-8">
                                          <input type="text" id="txtNamaJual" name="txtNamaJual" class="form-control" />
                                          <span id="errNamaJual" name="errNamaJual" class="help-block" style="color: red"></span>
                                    </div>
                              </div>

                              <div class="form-group">
                                    <label class="control-label col-md-3">Harga Jual
                                          <span class="required">*</span>
                                    </label>

                                    <div class="col-md-8">
                                          <input type="number" id="txtHargaJual" name="txtHargaJual" class="form-control" min=0 max=999999999999999 />
                                          <span id="errHargaJual" name="errHargaJual" class="help-block" style="color: red"></span>
                                    </div>
                              </div>

                              <div class="form-group">
                                    <label class="control-label col-md-3">Harga Beli
                                          <span class="required">*</span>
                                    </label>

                                    <div class="col-md-8">
                                          <input type="number" id="txtHargaBeli" name="txtHargaBeli" class="form-control" min=0 max=999999999999999 />
                                          <span id="errHargaBeli" name="errHargaBeli" class="help-block" style="color: red"></span>
                                    </div>
                              </div>

                              <div class="form-group">
                                    <label class="control-label col-md-3">Kategori
                                          <span class="required">*</span>
                                    </label>

                                    <div class="col-md-8">
                                          <select class='form-control' id='cboKategori' name="cboKategori" style="width: 100%">
                                          </select>

                                          <span id="errKategori" name="errKategori" class="help-block" style="color: red"></span>
                                    </div>
                              </div>

                              <div class="form-group">
                                    <label class="control-label col-md-3">Tipe
                                          <span class="required">*</span>
                                    </label>

                                    <div class="col-md-8">
                                          <select class='form-control' id='cboTipe' name="cboTipe" style="width: 100%">
                                          </select>

                                          <span id="errTipe" name="errTipe" class="help-block" style="color: red"></span>
                                    </div>
                              </div>

                              <div class="form-group">
                                    <label class="control-label col-md-3">Fitur
                                          <span class="required">*</span>
                                    </label>

                                    <div class="col-md-8">
                                          <select class='form-control' id='cboFitur' name="cboFitur" style="width: 100%">
                                          </select>

                                          <span id="errFitur" name="errFitur" class="help-block" style="color: red"></span>
                                    </div>
                              </div>

                              <div class="form-group">
                                    <label class="control-label col-md-3">Grade
                                          <span class="required">*</span>
                                    </label>

                                    <div class="col-md-8">
                                          <select class='form-control' id='cboGrade' name="cboGrade" style="width: 100%">
                                          </select>

                                          <span id="errGrade" name="errGrade" class="help-block" style="color: red"></span>
                                    </div>
                              </div>

                              <div class="form-group">
                                    <label class="control-label col-md-3">Satuan
                                          <span class="required">*</span>
                                    </label>

                                    <div class="col-md-8">
                                          <select class='form-control' id='cboSatuan' name="cboSatuan" style="width: 100%">
                                          </select>

                                          <span id="errSatuan" name="errSatuan" class="help-block" style="color: red"></span>
                                    </div>
                              </div>

                              <div class="form-group">
                                    <label class="control-label col-md-3">Jenis Katalog Warna
                                          <span class="required">*</span>
                                    </label>

                                    <div class="col-md-8">
                                          <select class='form-control' id='cboJenis' name="cboJenis" style="width: 100%">
                                          </select>

                                          <span id="errJenis" name="errJenis" class="help-block" style="color: red"></span>
                                    </div>
                              </div>
                        </div>

                        <div class="modal-footer">
                              <button type="button" class="btn btn-active btn-trigger blue pull-left" id="btnSave" name="btnSave">
                                    <i class="fa fa-save"></i> Save
                              </button>

                              <button type="button" class="btn  btn-active default pull-left" data-dismiss="modal">
                                    <i class="fa fa-times"></i> Close
                              </button>
                        </div>
                  </div>
            </form>
      </div>
</div>

<script>
      var address_root = '<?= get_api_base_link(); ?>' + 'barang/';
      var address_root_satuan = '<?= get_api_base_link(); ?>' + 'satuan_barang/';
      var address_root_kategori = '<?= get_api_base_link(); ?>' + 'kategori_barang/';
      var address_root_tipe = '<?= get_api_base_link(); ?>' + 'tipe_barang/';
      var address_root_jenis = '<?= get_api_base_link(); ?>' + 'katalog_warna_jenis/';
      var token = '<?= get_api_key(); ?>';
      var base_code = '<?= get_api_base_code(); ?>';

      $(document).ready(function() {
            $("#cboSatuan, #cboTipe, #cboKategori, #cboFitur, #cboGrade, #cboJenis").select2();

            let form = "#frmAdd";
            let controls = ['txtNamaBeli', 'txtNamaJual', 'txtHargaJual', 'txtHargaBeli', 'cboKategori', 'cboTipe', 'cboFitur', 'cboGrade', 'cboSatuan', 'cboJenis'];
            let errControls = ['errNamaBeli', 'errNamaJual', 'errHargaJual', 'errHargaBeli', 'errKategori', 'errTipe', 'errFitur', 'errGrade', 'errSatuan', 'errJenis'];

            function clear_error() {
                  for (let i = 0; i < errControls.length; i++) {
                        $(form + ' #' + errControls[i]).text('');
                  }
            }

            function clear_control() {
                  for (let i = 0; i < controls.length; i++) {
                        $(form + ' #' + controls[i]).val('');

                        if (controls[i].substring(0, 3) == 'cbo') {
                              $(form + ' #' + controls[i]).change();
                        }
                  }
            }

            //populate combo
            // combo satuan
            $.ajax({
                  url: address_root_satuan + 'list/1',
                  method: "get",
                  async: false,
                  dataType: 'json',
                  'data': {
                        'token': token
                  },
                  success: function(data) {
                        let html = '';
                        let i;

                        html += `<option value=''>Pilih Satuan</option>`;

                        let hasil = data['data'];

                        for (var k in hasil) {
                              html += `<option value='` + hasil[k].id + `'>` + hasil[k].nama + `</option>`;
                        }

                        $('#cboSatuan').html(html);
                        $("#cboSatuan").val('');
                        $("#cboSatuan").change();
                  }
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

                        html += `<option value=''>Pilih Kategori</option>`;

                        let hasil = data['data'];

                        for (var k in hasil) {
                              html += `<option value='` + hasil[k].id + `'>` + hasil[k].nama + `</option>`;
                        }

                        $('#cboKategori').html(html);
                        $("#cboKategori").val('');
                        $("#cboKategori").change();
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

                        html += `<option value=''>Pilih Tipe</option>`;

                        let hasil = data['data'];

                        for (var k in hasil) {
                              html += `<option value='` + hasil[k].id + `'>` + hasil[k].nama + `</option>`;
                        }

                        $('#cboTipe').html(html);
                        $("#cboTipe").val('');
                        $("#cboTipe").change();
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

                        html += `<option value=''>Pilih Fitur</option>`;

                        let hasil = data['data'];

                        for (var k in hasil) {
                              html += `<option value='` + hasil[k].id + `'>` + hasil[k].nama + `</option>`;
                        }

                        $('#cboFitur').html(html);
                        $("#cboFitur").val('');
                        $("#cboFitur").change();
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

                        html += `<option value=''>Pilih Grade</option>`;

                        let hasil = data['data'];

                        for (var k in hasil) {
                              html += `<option value='` + hasil[k].id + `'>` + hasil[k].nama + `</option>`;
                        }

                        $('#cboGrade').html(html);
                        $("#cboGrade").val('');
                        $("#cboGrade").change();
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

                        html += `<option value=''>Pilih Jenis</option>`;

                        let hasil = data['data'];

                        for (var k in hasil) {
                              html += `<option value='` + hasil[k].id + `'>` + hasil[k].nama + `</option>`;
                        }

                        $('#cboJenis').html(html);
                        $("#cboJenis").val('');
                        $("#cboJenis").change();
                  }
            });

            // save data
            $("#btnAdd").click(function() {
                  clear_error();
                  clear_control();

                  $("#flgData").text('Tambah');
                  $("#txtFlag").val('Tambah');
                  $("#mdlAdd").modal('show');
            });

            $('#mdlAdd').on('shown.bs.modal', function() {
                  $('#txtNamaBeli').focus();
            })

            $("#btnSave").click(function() {
                  let flag = $("#txtFlag").val();
                  let id = $('#txtId').val();
                  let nama_beli = $('#txtNamaBeli').val();
                  let nama_jual = $('#txtNamaJual').val();
                  let harga_jual = $('#txtHargaJual').val();
                  let harga_beli = $('#txtHargaBeli').val();
                  let kategori_id = $('#cboKategori').val();
                  let tipe_id = $('#cboTipe').val();
                  let fitur_id = $('#cboFitur').val();
                  let grade_id = $('#cboGrade').val();
                  let satuan_id = $('#cboSatuan').val();
                  let jenis_id = $('#cboJenis').val();

                  if (flag == 'Tambah') {
                        $.ajax({
                              url: address_root + 'simpan/' + base_code,
                              data: {
                                    'token': token,
                                    'nama_beli': nama_beli,
                                    'nama_jual': nama_jual,
                                    'harga_beli': harga_beli,
                                    'harga_jual': harga_jual,
                                    'kategori_id': kategori_id,
                                    'tipe_id': tipe_id,
                                    'fitur_id': fitur_id,
                                    'grade_id': grade_id,
                                    'satuan_id': satuan_id,
                                    'jenis_id': jenis_id,
                              },
                              method: 'post',
                              dataType: 'json',
                              success: function(data) {
                                    let result = data['success'];

                                    if (result == false) {
                                          clear_error();

                                          if (typeof(data['data']['nama_beli']) != 'undefined')
                                                $(form + ' #' + errControls[0]).text(data['data']['nama_beli']);

                                          if (typeof(data['data']['nama_jual']) != 'undefined')
                                                $(form + ' #' + errControls[1]).text(data['data']['nama_jual']);

                                          if (typeof(data['data']['harga_jual']) != 'undefined')
                                                $(form + ' #' + errControls[2]).text(data['data']['harga_jual']);

                                          if (typeof(data['data']['harga_beli']) != 'undefined')
                                                $(form + ' #' + errControls[3]).text(data['data']['harga_beli']);

                                          if (typeof(data['data']['kategori_id']) != 'undefined')
                                                $(form + ' #' + errControls[4]).text(data['data']['kategori_id']);

                                          if (typeof(data['data']['tipe_id']) != 'undefined')
                                                $(form + ' #' + errControls[5]).text(data['data']['tipe_id']);

                                          if (typeof(data['data']['fitur_id']) != 'undefined')
                                                $(form + ' #' + errControls[6]).text(data['data']['fitur_id']);

                                          if (typeof(data['data']['grade_id']) != 'undefined')
                                                $(form + ' #' + errControls[7]).text(data['data']['grade_id']);

                                          if (typeof(data['data']['satuan_id']) != 'undefined')
                                                $(form + ' #' + errControls[8]).text(data['data']['satuan_id']);

                                          if (typeof(data['data']['jenis_id']) != 'undefined')
                                                $(form + ' #' + errControls[9]).text(data['data']['jenis_id']);
                                    } else {
                                          window.location.reload();
                                    }
                              }
                        });
                  } else {
                        $.ajax({
                              url: address_root + 'ubah/' + base_code + '/' + id,
                              data: {
                                    'token': token,
                                    'nama_beli': nama_beli,
                                    'nama_jual': nama_jual,
                                    'harga_beli': harga_beli,
                                    'harga_jual': harga_jual,
                                    'kategori_id': kategori_id,
                                    'tipe_id': tipe_id,
                                    'fitur_id': fitur_id,
                                    'grade_id': grade_id,
                                    'satuan_id': satuan_id,
                                    'jenis_id': jenis_id,
                              },
                              method: 'put',
                              dataType: 'json',
                              success: function(data) {
                                    let result = data['success'];

                                    if (result == false) {
                                          clear_error();

                                          if (typeof(data['data']['nama_beli']) != 'undefined')
                                                $(form + ' #' + errControls[0]).text(data['data']['nama_beli']);

                                          if (typeof(data['data']['nama_jual']) != 'undefined')
                                                $(form + ' #' + errControls[1]).text(data['data']['nama_jual']);

                                          if (typeof(data['data']['harga_jual']) != 'undefined')
                                                $(form + ' #' + errControls[2]).text(data['data']['harga_jual']);

                                          if (typeof(data['data']['harga_beli']) != 'undefined')
                                                $(form + ' #' + errControls[3]).text(data['data']['harga_beli']);

                                          if (typeof(data['data']['kategori_id']) != 'undefined')
                                                $(form + ' #' + errControls[4]).text(data['data']['kategori_id']);

                                          if (typeof(data['data']['tipe_id']) != 'undefined')
                                                $(form + ' #' + errControls[5]).text(data['data']['tipe_id']);

                                          if (typeof(data['data']['fitur_id']) != 'undefined')
                                                $(form + ' #' + errControls[6]).text(data['data']['fitur_id']);

                                          if (typeof(data['data']['grade_id']) != 'undefined')
                                                $(form + ' #' + errControls[7]).text(data['data']['grade_id']);

                                          if (typeof(data['data']['satuan_id']) != 'undefined')
                                                $(form + ' #' + errControls[8]).text(data['data']['satuan_id']);

                                          if (typeof(data['data']['jenis_id']) != 'undefined')
                                                $(form + ' #' + errControls[9]).text(data['data']['jenis_id']);
                                    } else {
                                          window.location.reload();
                                    }
                              }
                        });
                  }
            });

            //edit data 
            $('#table').on('click', '#btnEdit', function() {
                  clear_error();
                  clear_control();

                  const ini = $(this).closest('tr');
                  let hasil = ini.find('.id').html();
                  const data = JSON.parse(hasil);

                  let form = $('#frmAdd');
                  $('#flgData').text('Update');
                  $('#txtFlag').val('Update');
                  form.find("[name=txtId]").val(data[0]);
                  form.find("[name=txtNamaBeli]").val(data[1]);
                  form.find("[name=txtNamaJual]").val(data[2]);
                  form.find("[name=txtHargaJual]").val(data[3]);
                  form.find("[name=txtHargaBeli]").val(data[4]);
                  form.find("[name=cboSatuan]").val(data[5]);
                  form.find("[name=cboSatuan]").change();
                  form.find("[name=cboKategori]").val(data[6]);
                  form.find("[name=cboKategori]").change();
                  form.find("[name=cboTipe]").val(data[7]);
                  form.find("[name=cboTipe]").change();
                  form.find("[name=cboFitur]").val(data[8]);
                  form.find("[name=cboFitur]").change();
                  form.find("[name=cboGrade]").val(data[9]);
                  form.find("[name=cboGrade]").change();
                  form.find("[name=cboJenis]").val(data[10]);
                  form.find("[name=cboJenis]").change();

                  $("#frmAdd #txtNamaBeli").focus();
            });

            //filter data
            $('#btnStart').click(function() {
                  table.ajax.reload();
                  $("#mdlFilter").modal('toggle');
            });
      });
</script>