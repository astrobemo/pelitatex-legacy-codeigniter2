<div class="modal fade" autocomplete="off" id="mdlAdd" name="mdlAdd" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
            <form action="#" class="form-horizontal" id="frmAdd" name="frmAdd" method="post" autocomplete="off">
                  <div class="modal-content">
                        <div class="modal-header">
                              <button type="button" class="close" aria-label="Close" data-dismiss="modal" id="btnCloseEditor" name="btnCloseEditor">
                                    <span aria-hidden="true">&times;</span>
                              </button>

                              <h3><span id="flgData" name="flgData"></span> <b>SKU Barang</b></h3>
                        </div>

                        <div class="modal-body">
                              <div class="col-md-12">
                                    <div class="row">
                                          <div class="col-md-4">
                                                <div class="form-group">
                                                      <label class="control-label">Barang Beli</label>
                                                      <input type="hidden" id="txtId" name="txtId" class="form-control" readonly />
                                                      <input type="hidden" id="txtKode" name="txtKode" class="form-control" readonly />
                                                      <input type="text" id="txtNamaBeli" name="txtNamaBeli" class="form-control" readonly />
                                                </div>

                                                <div class="form-group">
                                                      <label class="control-label">Barang Jual</label>
                                                      <input type="text" id="txtNamaJual" name="txtNamaJual" class="form-control" readonly />
                                                </div>

                                                <div class="form-group">
                                                      <label class="control-label">Satuan</label>
                                                      <input type="hidden" id="txtSatuanId" name="txtSatuanId" class="form-control" readonly />
                                                      <input type="text" id="txtSatuan" name="txtSatuan" class="form-control" readonly />
                                                </div>

                                                <div class="form-group">
                                                      <label class="control-label">Kategori</label>
                                                      <input type="hidden" id="txtKategoriId" name="txtKategoriId" class="form-control" readonly />
                                                      <input type="text" id="txtKategori" name="txtKategori" class="form-control" readonly />
                                                </div>

                                                <div class="form-group">
                                                      <label class="control-label">Tipe</label>
                                                      <input type="hidden" id="txtTipeId" name="txtTipeId" class="form-control" readonly />
                                                      <input type="text" id="txtTipe" name="txtTipe" class="form-control" readonly />
                                                </div>

                                                <div class="form-group">
                                                      <label class="control-label">Fitur</label>
                                                      <input type="hidden" id="txtFiturId" name="txtFiturId" class="form-control" readonly />
                                                      <input type="text" id="txtFitur" name="txtFitur" class="form-control" readonly />
                                                </div>

                                                <div class="form-group">
                                                      <label class="control-label">Grade</label>
                                                      <input type="hidden" id="txtGradeId" name="txtGradeId" class="form-control" readonly />
                                                      <input type="text" id="txtGrade" name="txtGrade" class="form-control" readonly />
                                                </div>

                                                <div class="form-group">
                                                      <label class="control-label">Jenis</label>
                                                      <input type="hidden" id="txtJenisId" name="txtJenisId" class="form-control" readonly />
                                                      <input type="text" id="txtJenis" name="txtJenis" class="form-control" readonly />
                                                </div>
                                          </div>

                                          <div class="col-md-8">
                                                <label class="control-label">Katalog</label>

                                                <select id='cboKatalog' name='cboKatalog' style="width: 100%" class="form-control">
                                                      <option value=''>Pilih Katalog</option>
                                                </select>

                                                <span id="errKatalog" name="errKatalog" class="help-block" style="color: red"></span>

                                                <div class="row text-center">
                                                      <button type="button" class="btn btn-active btn-trigger" id="btnAddKatalog" name="btnAddKatalog">
                                                            <i class="fa fa-plus"></i> Add
                                                      </button>
                                                </div>

                                                <hr />

                                                <table id="table_register_sku" class="table table-bordered table-hover" width="100%">
                                                      <thead>
                                                            <tr>
                                                                  <th class="text-center" width="120px">SKU</th>
                                                                  <th class="text-center">Katalog</th>
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
            </form>
      </div>
</div>

<script>
      var address_root = '<?= get_api_base_link(); ?>' + 'sku_barang/';
      var address_root_katalog = '<?= get_api_base_link(); ?>' + 'katalog_warna/';
      var base_code = '<?= get_api_base_code(); ?>';
      var token = '<?= get_api_key(); ?>';

      $(document).ready(function() {
            // registrasi
            $("#cboKatalog").select2();

            // modal opened
            $('#mdlAdd').on('shown.bs.modal', function() {
                  // load last item
                  let id = $("#txtId").val();

                  $.ajax({
                        url: address_root + 'list_sku/' + id,
                        method: "get",
                        async: false,
                        dataType: 'json',
                        'data': {
                              'token': token
                        },
                        success: function(data) {
                              let dataku = data['data'];
                              let sku = '';
                              let katalog_nama = '';

                              let table = "";

                              for (i = 0; i <= dataku.length - 1; i++) {
                                    sku = dataku[i].kode;
                                    katalog_nama = dataku[i].katalog_nama;

                                    table += "<tr>";
                                    table += "<td width='120px'>" + sku + "</td>";
                                    table += "<td>" + katalog_nama + "</td>";
                                    table += "</tr>";
                              }

                              $("#table_register_sku tbody").append(table);
                        }
                  });

                  $('#cboKatalog').focus();
            })

            //edit data 
            $('#table').on('click', '#btnEdit', function() {
                  const ini = $(this).closest('tr');
                  let hasil = ini.find('.id').html();
                  const data = JSON.parse(hasil);

                  let form = $('#frmAdd');
                  form.find("[name=txtId]").val(data[0]);
                  form.find("[name=txtKode]").val(data[1]);
                  form.find("[name=txtNamaBeli]").val(data[2]);
                  form.find("[name=txtNamaJual]").val(data[3]);
                  form.find("[name=txtSatuanId]").val(data[4]);
                  form.find("[name=txtSatuan]").val(data[5]);
                  form.find("[name=txtKategoriId]").val(data[6]);
                  form.find("[name=txtKategori]").val(data[7]);
                  form.find("[name=txtTipeId]").val(data[8]);
                  form.find("[name=txtTipe]").val(data[9]);
                  form.find("[name=txtFiturId]").val(data[10]);
                  form.find("[name=txtFitur]").val(data[11]);
                  form.find("[name=txtGradeId]").val(data[14]);
                  form.find("[name=txtGrade]").val(data[13]);
                  form.find("[name=txtJenisId]").val(data[14]);
                  form.find("[name=txtJenis]").val(data[15]);

                  $("#cboKatalog").empty();
                  $("#txtKodeKatalog").val('');

                  let html = `<option value=''>Pilih Katalog</option>`;

                  $.ajax({
                        url: address_root_katalog + 'list/1' + '/' + data[14],
                        method: "get",
                        data: {
                              'token': token
                        },
                        async: false,
                        dataType: 'json',
                        success: function(data) {
                              let i;

                              let hasil = data['data'];

                              for (var k in hasil) {
                                    html += `<option value='` + hasil[k].id + `'>` + hasil[k].kode + ' | ' + hasil[k].nama + `</option>`;
                              }
                        }
                  });

                  $('#cboKatalog').html(html);
                  $("#cboKatalog").val('');
                  $("#cboKatalog").change();

                  $("#frmAdd #cboKatalog").focus();
            });

            // save data
            let form = "#frmAdd";
            let controls = ['cboKatalog'];
            let errControls = ['errKatalog'];

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

            $("#btnAddKatalog").click(function() {
                  let barang_id = $('#txtId').val();
                  let barang_kode = $('#txtKode').val();
                  let katalog_id = $("#cboKatalog").val();
                  let katalog = $("#cboKatalog option:selected").text();
                  katalog_kode = katalog.substring(0, 4);
                  katalog_nama = katalog.substr(7, 100);
                  let sku = barang_kode + katalog_kode;

                  clear_error();

                  $.ajax({
                        url: address_root + 'simpan',
                        data: {
                              'token': token,
                              'barang_id': barang_id,
                              'katalog_id': katalog_id,
                              'kode': sku
                        },
                        method: 'post',
                        dataType: 'json',
                        success: function(data) {
                              let result = data['success'];

                              if (result == false) {
                                    if (typeof(data['data']['katalog_id']) != 'undefined')
                                          $(form + ' #' + errControls[0]).text(data['data']['katalog_id']);
                              } else {
                                    let table = "<tr>";
                                    table += "<td width='120px'>" + sku + "</td>";
                                    table += "<td>" + katalog_nama + "</td>";
                                    table += "</tr>";

                                    $("#table_register_sku tbody").append(table);
                              }
                        }
                  });
            });

            $("#btnCloseEditor").click(function() {
                  window.location.reload();
            });
      });
</script>