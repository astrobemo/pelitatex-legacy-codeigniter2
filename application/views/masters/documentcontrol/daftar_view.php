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

                              
                              <div class="portlet-title">
                                    <div class="caption caption-md">
                                          <ul class="nav nav-pills">
                                                <li class="active"><a href="<?= base_url(is_setting_link('masters/documentcontrol/daftar')); ?>">Document Control</a></li>
                                                <li><a href="<?= base_url(is_setting_link('masters/department/daftar')); ?>">Department</a></li>
                                          </ul>
                                    </div>

                                    <div class="actions">
                                          <a href="#" data-toggle='modal' class="btn btn-default btn-xs btn-form-add" id="btnAdd" name="btnAdd">
                                                <i class="fa fa-plus"></i> Tambah
                                          </a>
                                    </div>
                              </div>

                              <div class="portlet-body">
                                    
                                    <table>
                                          <tr>
                                                <td>Department</td>
                                                <td style='padding:0 10px'> : </td>
                                                <td>
                                                      <select id='cboDepartmentFilter' name='cboDepartmentFilter' style="width: 300px" >
                                                            <?php
                                                            foreach ($department as $row) {
                                                            ?>

                                                                  <option value="<?= $row->id ?>"><?= $row->nama; ?></option>

                                                            <?php } ?>
                                                      </select>
                                                </td>
                                          </tr>
                                          <tr>
                                                <td>Status</td>
                                                <td style='padding:0 10px'> : </td>
                                                <td>
                                                      <select id='cboStatusFilter' name='cboStatusFilter' style="width: 100%" >
                                                            <option value="1" selected>Aktif</option>
                                                            <option value="0">Non Aktif</option>
                                                      </select>
                                                </td>
                                          </tr>
                                          <tr>
                                                <td></td>
                                                <td></td>
                                                <td>
                                                      <button type="button" class="btn btn-active btn-trigger default btn-xs btn-block" id="btnStart" name="btnStart">
                                                            <i class="fa fa-search"></i> Filter
                                                      </button>
                                                </td>
                                          </tr>
                                    </table>
                                    <hr/>
                                    <table id="table" class="table table-bordered table-hover">
                                          <thead>
                                                <tr>
                                                      <th class="text-center">Id</th>
                                                      <th class="text-center">Kode</th>
                                                      <th class="text-center">Nama</th>
                                                      <th class="text-center">Keterangan</th>
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


<?php $this->load->view($editor); ?>

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
      var address_root = '<?= get_api_base_link(); ?>' + 'document_control/';
      var address_root_department = '<?= get_api_base_link(); ?>' + 'department/';
      var token = '<?= get_api_key(); ?>';

      var table;
      var department_id = '1';

      $(document).ready(function() {
            // registrasi select2
            $("#cboDepartment, #cboDepartmentFilter").select2();

            //load data Department katalog
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

                        let hasil = data['data'];

                        for (var k in hasil) {
                              html += `<option value='` + hasil[k].id + `'>` + hasil[k].nama + `</option>`;
                        }

                        $('#cboDepartmentFilter').html(html);
                        $("#cboDepartmentFilter").change();
                  }
            });

            // populate datatables
            table = $('#table').DataTable({
                  "pageLength": 100,
                  "ajax": {
                        'url': address_root + 'list/1/' + department_id,
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
                              "data": "keterangan"
                        }
                  ],
                  "columnDefs": [{
                              "targets": 0,
                              "visible": false
                        },
                        {
                              "targets": 1,
                              "className": "text-center"
                        },
                        {
                              "targets": 4,
                              "orderable": false,
                              "className": "text-center",
                              "width": "100px",
                              "render": function(data, type, row, meta) {
                                    let hasil = JSON.stringify([row['id'], row['kode'], row['nama'], row['keterangan'], row['status_aktif'], row['department_id']]);

                                    return `<a href="#mdlAdd" data-toggle="modal" id="btnEdit" name="btnEdit" class="btn btn-default btn-xs">
								<i class="fa fa-pencil"></i>
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
                  department_id = $("#cboDepartmentFilter").val();

                  table.ajax.url(address_root + 'list/' + status_aktif + '/' + department_id).load();

                  $("#mdlFilter").modal('toggle');
            });

            //terminate 
            $('#table').on('click', '#btnTerminate', function() {
                  const ini = $(this).closest('tr');
                  let hasil = ini.find('.id').html();
                  const data = JSON.parse(hasil);

                  let id = data[0];
                  let status_aktif = data[5];

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