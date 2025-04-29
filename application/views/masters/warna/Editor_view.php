<div class="modal fade" autocomplete="off" id="mdlAdd" name="mdlAdd" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
            <form action="#" class="form-horizontal" id="frmAdd" name="frmAdd" method="post" autocomplete="off">
                  <div class="modal-content">
                        <div class="modal-header">
                              <h3><span id="flgData" name="flgData"></span> <b>Warna</b></h3>

                              <input type="hidden" id="txtFlag" name="txtFlag" class="form-control" />
                              <input type="hidden" id="txtId" name="txtId" class="form-control" />
                        </div>

                        <div class="modal-body">
                              <div class="form-group">
                                    <label class="control-label col-md-3">Nama
                                          <span class="required">*</span>
                                    </label>

                                    <div class="col-md-8">
                                          <input type="text" id="txtNama" name="txtNama" class="form-control" />
                                          <span id="errNama" name="errNama" class="help-block" style="color: red"></span>
                                    </div>
                              </div>

                              <div class="form-group">
                                    <label class="control-label col-md-3">Kode Warna
                                          <span class="required">*</span>
                                    </label>

                                    <div class="col-md-8">
                                          <input type="text" id="txtKodeWarna" name="txtKodeWarna" class="form-control" />
                                          <span id="errKodeWarna" name="errKodeWarna" class="help-block" style="color: red"></span>
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
      var address_root = '<?= get_api_base_link(); ?>' + 'warna/';
      var base_code = '<?= get_api_base_code(); ?>';
      var token = '<?= get_api_key(); ?>';

      $(document).ready(function() {
            $('#txtKodeWarna').colorpicker();

            // add data
            let form = "#frmAdd";
            let controls = ['txtNama', 'txtKodeWarna'];
            let errControls = ['errNama', 'errKodeWarna'];

            function clear_error() {
                  for (let i = 0; i < errControls.length; i++) {
                        $(form + ' #' + errControls[i]).text('');
                  }
            }

            function clear_control() {
                  $("#txtKodeWarna").val('');
                  $("#txtKodeWarna").colorpicker('setValue', '#ffffff');
                  changeColor();

                  for (let i = 0; i < controls.length; i++) {
                        $(form + ' #' + controls[i]).val('');

                        if (controls[i].substring(0, 3) == 'cbo') {
                              $(form + ' #' + controls[i]).change();
                        }
                  }
            }

            // save data
            $("#btnAdd").click(function() {
                  clear_error();
                  clear_control();

                  $("#flgData").text('Tambah');
                  $("#txtFlag").val('Tambah');

                  $("#mdlAdd").modal('show');
            });

            $('#mdlAdd').on('shown.bs.modal', function() {
                  $('#txtNama').focus();
            })

            $("#btnSave").click(function() {
                  let flag = $("#txtFlag").val();
                  let id = $('#txtId').val();
                  let nama = $('#txtNama').val();
                  let kode_warna = $('#txtKodeWarna').val();

                  if (flag == 'Tambah') {
                        $.ajax({
                              url: address_root + 'simpan/' + base_code,
                              data: {
                                    'token': token,
                                    'nama': nama,
                                    'kode_warna': kode_warna
                              },
                              method: 'post',
                              dataType: 'json',
                              success: function(data) {
                                    let result = data['success'];

                                    if (result == false) {
                                          clear_error();

                                          if (typeof(data['data']['nama']) != 'undefined')
                                                $(form + ' #' + errControls[0]).text(data['data']['nama']);

                                          if (typeof(data['data']['kode_warna']) != 'undefined')
                                                $(form + ' #' + errControls[1]).text(data['data']['kode_warna']);
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
                                    'nama': nama,
                                    'kode_warna': kode_warna
                              },
                              method: 'put',
                              dataType: 'json',
                              success: function(data) {
                                    let result = data['success'];

                                    if (result == false) {
                                          clear_error();

                                          if (typeof(data['data']['nama']) != 'undefined')
                                                $(form + ' #' + errControls[0]).text(data['data']['nama']);

                                          if (typeof(data['data']['kode_warna']) != 'undefined')
                                                $(form + ' #' + errControls[1]).text(data['data']['kode_warna']);
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
                  form.find("[name=txtNama]").val(data[1]);
                  form.find("[name=txtKodeWarna]").val(data[2]);
                  $("#txtKodeWarna").colorpicker('setValue', data[2]);
                  changeColor();

                  $("#frmAdd #txtNama").focus();
            });

            $("#txtKodeWarna").on("focusout", function() {
                  changeColor();
            });
      });
</script>

<script>
      function changeColor() {
            let warna = $("#txtKodeWarna").val();
            let warna_font = "#000000";

            if (warna == '' || warna == '#ffffff') {
                  warna = '#ffffff';
                  warna_font = '#000000';

                  $("#txtKodeWarna").val(warna);
            } else {
                  warna_font = '#ffffff';
            }

            $("#txtKodeWarna").css('background-color', warna);
            $("#txtKodeWarna").css('color', warna_font);
      }
</script>