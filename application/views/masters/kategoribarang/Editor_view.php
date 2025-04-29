<div class="modal fade" autocomplete="off" id="mdlAdd" name="mdlAdd" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
            <form action="#" class="form-horizontal" id="frmAdd" name="frmAdd" method="post" autocomplete="off">
                  <div class="modal-content">
                        <div class="modal-header">
                              <h3><span id="flgData" name="flgData"></span> <b>Kategori Barang</b></h3>

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
      var address_root = '<?= get_api_base_link(); ?>' + 'kategori_barang/';
      var token = '<?= get_api_key(); ?>';

      $(document).ready(function() {
            let form = "#frmAdd";
            let controls = ['txtNama'];
            let errControls = ['errNama'];

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

                  if (flag == 'Tambah') {
                        $.ajax({
                              url: address_root + 'simpan',
                              data: {
                                    'token': token,
                                    'nama': nama
                              },
                              method: 'post',
                              dataType: 'json',
                              success: function(data) {
                                    let result = data['success'];

                                    if (result == false) {
                                          clear_error();

                                          if (typeof(data['data']['nama']) != 'undefined')
                                                $(form + ' #' + errControls[0]).text(data['data']['nama']);
                                    } else {
                                          window.location.reload();
                                    }
                              }
                        });
                  } else {
                        $.ajax({
                              url: address_root + 'ubah/' + id,
                              data: {
                                    'token': token,
                                    'nama': nama
                              },
                              method: 'put',
                              dataType: 'json',
                              success: function(data) {
                                    let result = data['success'];

                                    if (result == false) {
                                          clear_error();

                                          if (typeof(data['data']['nama']) != 'undefined')
                                                $(form + ' #' + errControls[0]).text(data['data']['nama']);
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

                  $("#frmAdd #txtNama").focus();
            });
      });
</script>