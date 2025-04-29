<div class="modal fade" autocomplete="off" id="mdlAdd" name="mdlAdd" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
            <form action="#" class="form-horizontal" id="frmAdd" name="frmAdd" method="post" autocomplete="off">
                  <div class="modal-content">
                        <div class="modal-header">
                              <h3><span id="flgData" name="flgData"></span> <b>Document Control</b></h3>

                              <input type="hidden" id="txtFlag" name="txtFlag" class="form-control" />
                              <input type="hidden" id="txtId" name="txtId" class="form-control" />
                        </div>

                        <div class="modal-body">
                              <div class="form-group">
                                    <label class="control-label col-md-3">Department</label>

                                    <div class="col-md-8">
                                          <select id='cboDepartment' name='cboDepartment' style="width: 100%" class="form-control">
                                          </select>

                                          <span id="errDepartment" name="errDepartment" class="help-block" style="color: red"></span>
                                    </div>
                              </div>

                              <div class="form-group kode" hidden>
                                    <label class="control-label col-md-3">Kode
                                          <span class="required"></span>
                                    </label>

                                    <div class="col-md-8">
                                          <input type="text" id="txtKode" name="txtKode" class="form-control" readonly />
                                    </div>
                              </div>

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
                                    <label class="control-label col-md-3">Keterangan
                                          <span class="required">*</span>
                                    </label>

                                    <div class="col-md-8">
                                          <input type="text" id="txtKeterangan" name="txtKeterangan" class="form-control" />
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
      var address_root = '<?= get_api_base_link(); ?>' + 'document_controller/';
      var address_root_department = '<?= get_api_base_link(); ?>' + 'department/';
      var token = '<?= get_api_key(); ?>';

      $(document).ready(function() {
            $('#txtKodeWarna').colorpicker();

            //load data department katalog
            $.ajax({
                  url: address_root_department + 'list/1',
                  method: "get",
                  async: false,
                  dataType: 'json',
                  'data': {
                        'token': token
                  },
                  success: function(data) {
                        let html = '';
                        let i;

                        html += `<option value=''>Pilih Department</option>`;

                        let hasil = data['data'];

                        for (var k in hasil) {
                              html += `<option value='` + hasil[k].id + `'>` + hasil[k].nama + `</option>`;
                        }

                        $('#cboDepartment').html(html);
                        $("#cboDepartment").val('');
                        $("#cboDepartment").change();
                  }
            });

            // add data
            let form = "#frmAdd";
            let controls = ['cboDepartment', 'txtNama', 'txtKode', 'txtKeterangan'];
            let errControls = ['errDepartment', 'errNama'];

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

            // save data
            $("#btnAdd").click(function() {
                  $(".kode").attr('hidden', 'hidden');
                  $("#cboDepartment").removeAttr('disabled');

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
                  let keterangan = $('#txtKeterangan').val();
                  let department_id = $('#cboDepartment').val();

                  if (flag == 'Tambah') {
                        $.ajax({
                              url: address_root + 'simpan',
                              data: {
                                    'token': token,
                                    'nama': nama,
                                    'department_id': department_id,
                                    'keterangan': keterangan
                              },
                              method: 'post',
                              dataType: 'json',
                              success: function(data) {
                                    let result = data['success'];

                                    if (result == false) {
                                          clear_error();

                                          if (typeof(data['data']['department_id']) != 'undefined')
                                                $(form + ' #' + errControls[0]).text(data['data']['department_id']);

                                          if (typeof(data['data']['nama']) != 'undefined')
                                                $(form + ' #' + errControls[1]).text(data['data']['nama']);
                                    } else {
                                          // window.location.reload();
                                          table.ajax.reload();
                                          $('#mdlAdd').modal('hide');
                                    }
                              }
                        });
                  } else {
                        $.ajax({
                              url: address_root + 'ubah/' + id,
                              data: {
                                    'token': token,
                                    'nama': nama,
                                    'department_id': department_id,
                                    'keterangan': keterangan
                              },
                              method: 'put',
                              dataType: 'json',
                              success: function(data) {
                                    let result = data['success'];

                                    if (result == false) {
                                          clear_error();

                                          if (typeof(data['data']['department_id']) != 'undefined')
                                                $(form + ' #' + errControls[0]).text(data['data']['department_id']);

                                          if (typeof(data['data']['nama']) != 'undefined')
                                                $(form + ' #' + errControls[1]).text(data['data']['nama']);
                                    } else {
                                          table.ajax.reload();
                                          $('#mdlAdd').modal('hide');
                                    }
                              }
                        });
                  }
            });

            //edit data 
            $('#table').on('click', '#btnEdit', function() {
                  $(".kode").removeAttr('hidden');
                  $("#cboDepartment").attr('disabled', 'disabled');

                  clear_error();
                  clear_control();

                  const ini = $(this).closest('tr');
                  let hasil = ini.find('.id').html();
                  const data = JSON.parse(hasil);

                  let form = $('#frmAdd');
                  $('#flgData').text('Update');
                  $('#txtFlag').val('Update');
                  form.find("[name=txtId]").val(data[0]);
                  form.find("[name=cboDepartment]").val(data[4]);
                  form.find("[name=cboDepartment]").change();
                  form.find("[name=txtKode]").val(data[1]);
                  form.find("[name=txtNama]").val(data[2]);
                  form.find("[name=txtKeterangan]").val(data[3]);

                  $("#frmAdd #txtNama").focus();
            });

            $("#txtKodeWarna").on("focusout", function() {
                  changeColor();
            });
      });
</script>