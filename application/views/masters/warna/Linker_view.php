<div class="modal fade" autocomplete="off" id="mdlLink" name="mdlLink" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
            <form action="#" class="form-horizontal" id="frmLink" name="frmLink" method="post" autocomplete="off">
                  <div class="modal-content">
                        <div class="modal-header">
                              <button type="button" class="close" aria-label="Close" data-dismiss="modal" id="btnCloseEditor" name="btnCloseEditor">
                                    <span aria-hidden="true">&times;</span>
                              </button>

                              <h3>Link <b>Warna</b></h3>

                              <input type="hidden" id="txtListKatalogWarna" name="txtListKatalogWarna" readonly />
                              <input type="hidden" id="txtCount" name="txtCount" value="0" readonly />
                              <input type="hidden" id="txtSelectedId" name="txtSelectedId" readonly />
                        </div>

                        <div class="modal-body">
                              <div class="col-md-12">
                                    <div class="row">
                                          <div class="col-md-9">
                                                <div class="form-group">
                                                      <label class="control-label">Warna</label>
                                                      <input type="hidden" id="txtWarnaId" name="txtWarnaId" class="form-control" readonly />
                                                      <input type="text" id="txtWarnaNama" name="txtWarnaNama" class="form-control" readonly />
                                                </div>
                                          </div>

                                          <div class="col-md-3">
                                                <div class="form-group">
                                                      <label class="control-label">Kode Warna</label>
                                                      <input type="text" id="txtWarnaKode" name="txtWarnaKode" class="form-control" readonly />
                                                </div>
                                          </div>
                                    </div>

                                    <div class="row">
                                          <div class="col-md-3">
                                                <div class="form-group">
                                                      <label class="control-label">Jenis</label>
                                                      <select id='cboJenis' name='cboJenis' style="width: 100%" class="form-control"></select>
                                                      <span id="errJenis" name="errJenis" class="help-block" style="color: red"></span>
                                                </div>
                                          </div>

                                          <div class="col-md-6">
                                                <div class="form-group">
                                                      <label class="control-label">Katalog</label>

                                                      <select id='cboKatalog' name='cboKatalog' style="width: 100%" class="form-control">
                                                            <option value=''>Pilih Katalog</option>
                                                      </select>

                                                      <span id="errKatalog" name="errKatalog" class="help-block" style="color: red"></span>
                                                </div>
                                          </div>

                                          <div class="col-md-3">
                                                <div class="form-group">
                                                      <label class="control-label">Kode</label>
                                                      <input type="text" style="height:29px" id="txtKodeKatalog" name="txtKodeKatalog" class="form-control" readonly />
                                                </div>
                                          </div>
                                    </div>

                                    <div class="row text-center">
                                          <button type="button" class="btn btn-active btn-trigger" id="btnAddKatalog" name="btnAddKatalog">
                                                <i class="fa fa-plus"></i> Add
                                          </button>
                                          <button type="button" class="btn btn-active btn-trigger" id="btnDelKatalog" name="btnDelKatalog">
                                                <i class="fa fa-plus"></i> Delete
                                          </button>

                                          <hr />
                                    </div>

                                    <div class="row">
                                          <table id="table_register_katalog" class="table table-bordered table-hover">
                                                <thead>
                                                      <tr>
                                                            <th class="text-center">Jenis</th>
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
            </form>
      </div>
</div>

<script>
      var address_root_link = '<?= get_api_base_link(); ?>' + 'warna_link/';
      var address_root_warna = '<?= get_api_base_link(); ?>' + 'warna/';
      var address_root_katalog = '<?= get_api_base_link(); ?>' + 'katalog_warna/';
      var address_root_jenis_katalog = '<?= get_api_base_link(); ?>' + 'katalog_warna_jenis/';
      var base_code = '<?= get_api_base_code(); ?>';
      var token = '<?= get_api_key(); ?>';

      var jenis_id = '';

      $(document).ready(function() {
            // register select2
            $("#cboJenis, #cboKatalog").select2();

            //edit data 
            $('#table').on('click', '#btnLink', function() {
                  const ini = $(this).closest('tr');
                  let hasil = ini.find('.id').html();
                  const data = JSON.parse(hasil);

                  let form = $('#frmLink');
                  form.find("[name=txtWarnaId]").val(data[0]);
                  form.find("[name=txtWarnaNama]").val(data[1]);
                  form.find("[name=txtWarnaKode]").val(data[2]);
                  changeColorLinker();

                  $("#frmLink #cboJenis").focus();
            });

            $("#txtWarnaKode").on("focusout", function() {
                  changeColorLinker();
            });

            $('#mdlLink').on('shown.bs.modal', function() {
                  // load last item
                  let id = $("#txtWarnaId").val();
                  let mycount = $('#txtCount').val();

                  $.ajax({
                        url: address_root_link + 'list/' + base_code + '/' + id,
                        method: "get",
                        async: false,
                        dataType: 'json',
                        'data': {
                              'token': token
                        },
                        success: function(data) {
                              let dataku = data['data'];
                              let jenis_id = '';
                              let jenis_nama = '';
                              let katalog_id = '';
                              let katalog_kode = '';
                              let list_katalog_warna = '';
                              let katalog_nama = '';

                              let table = "";

                              for (i = 0; i <= dataku.length - 1; i++) {
                                    jenis_id = dataku[i].jenis_id;
                                    jenis_nama = dataku[i].jenis_nama;
                                    katalog_id = dataku[i].katalog_id;
                                    katalog_nama = dataku[i].katalog_nama;
                                    katalog_kode = dataku[i].katalog_kode;

                                    list_katalog_warna = list_katalog_warna == '' ? katalog_kode : list_katalog_warna + ', ' + katalog_kode;

                                    table += "<tr>";
                                    table += "<td>" + jenis_nama + "<input type='hidden' id='txtTblId" + i + "' name='txtTblId" + i + "' value='" + dataku[i].katalog_kode + "' readonly /></td>";
                                    table += "<td>" + katalog_nama + "</td>";
                                    table += "<td>" + katalog_kode + "</td>";
                                    table += "</tr>";
                                    mycount++;
                              }

                              $("#table_register_katalog tbody").append(table);
                              $("#txtCount").val(mycount);
                              $("#txtListKatalogWarna").val(list_katalog_warna);
                        }
                  });

                  $('#cboJenis').focus();
            })

            //load data jenis katalog
            $.ajax({
                  url: address_root_jenis_katalog + 'list/1',
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

            // populate katalog & kode katalog
            $("#cboJenis").on('change', function() {
                  let jenis_id = $("#cboJenis").val();

                  $("#cboKatalog").empty();
                  $("#txtKodeKatalog").val('');

                  let html = `<option value=''>Pilih Katalog</option>`;

                  $.ajax({
                        url: address_root_katalog + 'list/1' + '/' + jenis_id,
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
                                    html += `<option value='` + hasil[k].id + `'>` + hasil[k].nama + `</option>`;
                              }
                        }
                  });

                  $('#cboKatalog').html(html);
                  $("#cboKatalog").val('');
                  $("#cboKatalog").change();
            });

            $("#cboKatalog").on('change', function() {
                  var katalog_id = $("#cboKatalog").val();

                  $("txtKodeKatalog").val('');

                  $.ajax({
                        url: address_root_katalog + 'get_by_id/' + katalog_id,
                        method: "get",
                        data: {
                              'token': token
                        },
                        async: false,
                        dataType: 'json',
                        success: function(data) {
                              $('#txtKodeKatalog').val(data['data']['kode']);

                              let flag = $("#txtFlag").val();
                              let nama_lama = $('#txtWarnaNama').val();

                              if (flag != 'Update' && nama_lama == '') {
                                    $('#txtWarnaNama').val(data['data']['nama']);
                              }
                        }
                  });
            });

            // add katalog (save data)
            let form = "#frmLink";
            let controls = ['cboJenis', 'cboKatalog'];
            let errControls = ['errJenis', 'errKatalog'];

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
                  let mycount = $("#txtCount").val();
                  let warna_id = $('#txtWarnaId').val();
                  let jenis_id = $('#cboJenis').val();
                  let jenis_nama = $('#cboJenis option:selected').text();
                  let katalog_id = $('#cboKatalog').val();
                  let katalog_nama = $('#cboKatalog option:selected').text();
                  let katalog_kode = $('#txtKodeKatalog').val();
                  let list_katalog_warna = $("#txtListKatalogWarna").val();

                  clear_error();

                  $.ajax({
                        url: address_root_link + 'simpan/' + base_code,
                        data: {
                              'token': token,
                              'warna_id': warna_id,
                              'katalog_id': katalog_id,
                              'katalog_kode': katalog_kode
                        },
                        method: 'post',
                        dataType: 'json',
                        success: function(data) {
                              let result = data['success'];

                              if (result == false) {
                                    if (typeof(data['data']['jenis_id']) != 'undefined')
                                          $(form + ' #' + errControls[0]).text(data['data']['jenis_id']);

                                    if (typeof(data['data']['katalog_id']) != 'undefined')
                                          $(form + ' #' + errControls[1]).text(data['data']['katalog_id']);
                              } else {
                                    let table = "<tr>";
                                    table += "<td>" + jenis_nama + "<input type='hidden' id='txtTblId" + mycount + "' name='txtTblId" + mycount + "' value='" + katalog_kode + "' readonly /></td>";
                                    table += "<td>" + katalog_nama + "</td>";
                                    table += "<td>" + katalog_kode + "</td>";
                                    table += "</tr>";

                                    $("#table_register_katalog tbody").append(table);

                                    mycount++;
                                    $("#txtCount").val(mycount);

                                    list_katalog_warna = list_katalog_warna == '' ? katalog_kode : list_katalog_warna + ', ' + katalog_kode;
                                    $("#txtListKatalogWarna").val(list_katalog_warna);

                                    ubah_katalog_warna(list_katalog_warna);
                              }
                        }
                  });
            });

            $("#btnCloseEditor").click(function() {
                  window.location.reload();
            });

            $('#table_register_katalog tbody').on('click', 'tr', function() {
                  $('#table_register_katalog tbody tr').removeAttr('id');
                  $('#table_register_katalog tbody tr').css('background-color', 'white');
                  $(this).attr('id', 'selected');
                  $(this).css('background-color', 'silver');

                  let tbl_ix = $(this).index();
                  $('#txtSelectedId').val(tbl_ix);
            });

            $('#btnDelKatalog').click(function() {
                  let warna_id = $('#txtWarnaId').val();
                  let myindex = $('#txtSelectedId').val();
                  let kode_katalog = $("#txtTblId" + myindex).val();

                  if (typeof(kode_katalog) != "undefined") {
                        bootbox.confirm({
                              title: "Hapus Data",
                              message: "Link warna akan dihapus?",
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
                                                url: address_root_link + 'delete/' + base_code + '/' + warna_id + '/' + kode_katalog,
                                                data: {
                                                      'token': token,
                                                      'status_aktif': 0
                                                },
                                                method: 'delete',
                                                dataType: 'json',
                                                success: function(data) {
                                                      $('#selected').remove();

                                                      refresh_katalog_warna();
                                                      let list_katalog_warna = $("#txtListKatalogWarna").val();
                                                      ubah_katalog_warna(list_katalog_warna);
                                                }
                                          });
                                    }
                              }
                        });
                  }
            });

            function ubah_katalog_warna(katalog_warna) {
                  let warna_id = $("#txtWarnaId").val();

                  $.ajax({
                        url: address_root_warna + 'ubah_katalog_warna/' + base_code + '/' + warna_id,
                        data: {
                              'token': token,
                              'katalog_warna': katalog_warna
                        },
                        method: 'put',
                        dataType: 'json'
                  });
            }

            function refresh_katalog_warna() {
                  let warna_id = $("#txtWarnaId").val();

                  $.ajax({
                        url: address_root_link + 'list/' + base_code + '/' + warna_id,
                        method: "get",
                        async: false,
                        dataType: 'json',
                        'data': {
                              'token': token
                        },
                        success: function(data) {
                              let dataku = data['data'];
                              let katalog_kode = '';
                              let list_katalog_warna = '';

                              for (i = 0; i <= dataku.length - 1; i++) {
                                    katalog_kode = dataku[i].katalog_kode;

                                    list_katalog_warna = list_katalog_warna == '' ? katalog_kode : list_katalog_warna + ', ' + katalog_kode;
                              }

                              $("#txtListKatalogWarna").val(list_katalog_warna);
                        }
                  });
            }
      });
</script>

<script>
      function changeColorLinker() {
            let warna = $("#txtWarnaKode").val();
            let warna_font = "#000000";

            if (warna == '' || warna == '#ffffff') {
                  warna = '#ffffff';
                  warna_font = '#000000';

                  $("#txtWarnaKode").val(warna);
            } else {
                  warna_font = '#ffffff';
            }

            $("#txtWarnaKode").css('background-color', warna);
            $("#txtWarnaKode").css('color', warna_font);
      }

      function delete_me(row_index) {
            var table = $("#table_register_katalog").DataTable();
            table.row(row_index).remove().draw(false);
      }
</script>