
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css'); ?>"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css'); ?>"/>

<div class="page-content">
	<div class='container'>

		<div class="modal fade" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body">
						<form action="<?=base_url('master/barang_group_insert')?>" class="form-horizontal" id="form_add_data" method="post">
							<h3 class='block'> Tambah Baru </h3>
							
			                
			                <div class="form-group">
			                    <label class="control-label col-md-3">Barang Asli<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
                                    <select name="barang_id" class='form-control' id="barang-id">
                                        <option value="">Pilih</option>
                                        <?foreach ($this->barang_list_aktif_beli as $row) {?>
                                            <option value="<?=$row->id?>"><?=$row->nama;?></option>
                                        <?}?>
                                    </select>
			                    </div>				                    
			                </div>

			                <div class="form-group">
			                    <label class="control-label col-md-3">Barang Induk<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
                                    <select name="barang_id_induk" class='form-control' id="barang-induk">
                                        <option value="">Pilih</option>
                                        <?foreach ($this->barang_list_aktif_beli as $row) {?>
                                            <option value="<?=$row->id?>"><?=$row->nama;?></option>
                                        <?}?>
                                    </select>
			                    </div>				                    
			                </div>

						</form>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn blue btn-save">Save</button>
						<button type="button" class="btn default" data-dismiss="modal">Close</button>
					</div>
				</div>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
		</div>

		<div class="modal fade" id="portlet-config-edit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body">
						<form action="<?=base_url('master/barang_group_update')?>" class="form-horizontal" id="form_edit_data" method="post">
							<h3 class='block'> Edit </h3>
                            
                            <input name='id' hidden>
							<div class="form-group">
			                    <label class="control-label col-md-3">Barang Asli<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
                                    <select name="barang_id" class='form-control' id="barang-id-edit">
                                        <option value="">Pilih</option>
                                        <?foreach ($this->barang_list_aktif_beli as $row) {?>
                                            <option value="<?=$row->id?>"><?=$row->nama;?></option>
                                        <?}?>
                                    </select>
			                    </div>				                    
			                </div>

			                <div class="form-group">
			                    <label class="control-label col-md-3">Barang Induk<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
                                    <select name="barang_id_induk" class='form-control' id="barang-induk-edit">
                                        <option value="">Pilih</option>
                                        <?foreach ($this->barang_list_aktif_beli as $row) {?>
                                            <option value="<?=$row->id?>"><?=$row->nama;?></option>
                                        <?}?>
                                    </select>
			                    </div>				                    
			                </div>
				                
						</form>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn blue btn-edit-save">Save</button>
						<button type="button" class="btn default" data-dismiss="modal">Close</button>
					</div>
				</div>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
		</div>

		<div class="row margin-top-10">
			<div class="col-md-12">
				<div class="portlet light">
					<div class="portlet-title">
						<div class="caption caption-md">
							<i class="icon-bar-chart theme-font hide"></i>
							<span class="caption-subject theme-font bold uppercase"><?=$breadcrumb_small;?></span>
						</div>
						<div class="actions">
							<select class='btn btn-sm btn-default' name='status_aktif_select' id='status_aktif_select'>
								<option value="1" selected>Aktif</option>
								<option value="0">Tidak Aktif</option>
							</select>
							<a href="#portlet-config" data-toggle='modal' class="btn btn-default btn-sm btn-form-add">
							<i class="fa fa-plus"></i> Tambah </a>
						</div>
					</div>
					<div class="portlet-body">
						<table class="table table-striped table-bordered table-hover" id="general_table">
							<thead>
								<tr>
									<th scope="col" class='status_column'>
										Status Aktif
									</th>
									<th scope="col">
										Nama Barang
									</th>
									<th scope="col">
										Menginduk
									</th>
									<th scope="col" style="min-width:150px !important">
										Actions
									</th>
								</tr>
							</thead>
							<tbody>
								<?foreach ($barang_group_list as $row) { ?>
									<tr>
                                        <td class='status_column'><?=$row->status_aktif?></td>
										<td>
											<span class='nama'><?=$row->nama_barang;?></span> 
										</td>
										<td>
											<span class='nama_induk'><?=$row->nama_barang_induk;?></span> 
										</td>
										<td>
											<span class='id' hidden><?=$row->id;?></span>
											<span class='barang_id' hidden><?=$row->barang_id;?></span>
											<span class='barang_id_induk' hidden><?=$row->barang_id_induk;?></span>
											<a href='#portlet-config-edit' data-toggle='modal' class="btn-xs btn green btn-edit"><i class="fa fa-edit"></i> </a>
											<a class="btn-xs btn red btn-remove"><i class="fa fa-times"></i> </a>
										</td>
									</tr>
								<? }?>

							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>			
</div>

<script src="<?php echo base_url('assets/global/plugins/datatables/media/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js'); ?>" type="text/javascript"></script>

<script>
jQuery(document).ready(function() {

	$("#barang-id, #barang-id-edit, #barang-induk, #barang-induk-edit").select2();

	$('.btn-save').click(function(){
   		if( $('#barang-id').val() != '' && $('#barang-induk').val() != '' ){
   			$('#form_add_data').submit();
   		}
   	});

   	$('.btn-edit-save').click(function(){
   		if( $('#barang-id-edit').val() != '' && $('#barang-induk-edit').val() != '' ){
   			$('#form_edit_data').submit();
   		}
   	});

    $('#general_table').on('click', '.btn-edit', function(){
        let ini = $(this).closest('tr');
   		$('#form_edit_data [name=id]').val(ini.find('.id').html());
   		$('#barang-id-edit').val(ini.find('.barang_id').html()).change();
   		$('#barang-induk-edit').val(ini.find('.barang_id_induk').html()).change();
   	});
});
</script>
