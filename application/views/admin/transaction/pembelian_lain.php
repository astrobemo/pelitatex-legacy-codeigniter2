<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css'); ?>"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css'); ?>"/>
<link href="<?=base_url('assets/global/plugins/bootstrap-modal/css/bootstrap-modal.css');?>" rel="stylesheet" type="text/css"/>



<style type="text/css">


</style>
<div class="page-content">
	<div class='container'>

		<div id="pembelian-modal" class="modal fade" style='width:100%' tabindex="-1">
		</div>

		<div class="modal fade" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body">
						<form action="<?=base_url('transaction/pembelian_lain_insert')?>" class="form-horizontal" id="form_add_data" method="post">
							<h3 class='block'> Pembelian Baru</h3>

							<div class="form-group">
			                    <label class="control-label col-md-3">Supplier<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<select class='input1 form-control supplier-input' style='font-weight:bold' name="supplier_id">
			                    		<?foreach ($this->supplier_list_aktif as $row) {
			                    			if ($row->tipe_supplier == 2) {?>
				                    			<option value="<?=$row->id?>"><?=$row->nama;?></option>
			                    			<?} ?>
			                    		<? } ?>
			                    	</select>
			                    </div>
			                </div>

			                <div class="form-group">
			                    <label class="control-label col-md-3">No Faktur<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                		<input type="text" class="form-control" name="no_faktur" id='no-faktur' />
			                    </div>
			                </div>

			                <div class="form-group">
			                    <label class="control-label col-md-3">Tanggal<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
					                <input type="text" readonly class="form-control date-picker" value="<?=date('d/m/Y');?>" name="tanggal"/>
			                    </div>
			                </div> 

						</form>
					</div>

					<div class="modal-footer">
						<button type="button" class="btn blue btn-active btn-trigger btn-save">Save</button>
						<button type="button" class="btn default  btn-active" data-dismiss="modal">Close</button>
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
							<select class='btn btn-sm btn-default' name='status_select' id='status_select'>
								<option value="" selected>All</option>
								<option value="1">Aktif</option>
								<!-- <option value="0">Tidak Aktif</option> -->
								<option value="0">Batal</option>

							</select>

							<a href="#portlet-config" data-toggle='modal' class="btn btn-default btn-sm btn-form-add">
							<i class="fa fa-plus"></i> Tambah </a>
						</div>
					</div>
					<div class="portlet-body">
						<table class="table table-hover table-striped table-bordered" id="general_table">
							<thead>
								<tr>
									<th scope="col" class='status_column'>
										Status Aktif
									</th>
									<th scope="col">
										No Faktur
									</th>
									<th scope="col">
										Tanggal 
									</th>
									<th scope="col">
										Total
									</th>
									<th scope="col">
										Supplier
									</th>
									<th scope="col" >
										Actions
									</th>
								</tr>
							</thead>
							<tbody>
								<?foreach ($pembelian_list as $row) {?>
									<tr>
										<td class='status_column'><?=$row->status_aktif?></td>
										<td><?=$row->no_faktur?></td>
										<td><?=is_reverse_date($row->tanggal);?></td>
										<td><?=number_format($row->total,0,',','.');?></td>
										<td><?=$row->nama_supplier?></td>
										<td>
											<a href="<?=base_url().is_setting_link('transaction/pembelian_lain_detail');?>/<?=$row->id?>" target="_blank" class='btn btn-xs green'><i class='fa fa-edit'></i></a>
										</td>
									</tr>
								<?}?>
							</tbody>
						</table>

					</div>
				</div>
			</div>
		</div>
	</div>			
</div>

<script src="<?php echo base_url('assets/global/plugins/bootstrap-modal/js/bootstrap-modalmanager.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/bootstrap-modal/js/bootstrap-modal.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/jquery-validation/js/jquery.validate.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/datatables/media/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets_noondev/js/table-advanced.js'); ?>"></script>
<script src="<?php echo base_url('assets/global/plugins/bootbox/bootbox.min.js'); ?>" type="text/javascript"></script>

<script src="<?php echo base_url('assets/global/plugins/select2/select2.min.js'); ?>" type="text/javascript" ></script>
<script src="<?php echo base_url('assets_noondev/js/ui-extended-modals.js'); ?>"></script>
<script>
jQuery(document).ready(function() {
	
	$(".btn-save").click(function(){
		if($("#form_add_data [name=tanggal]").val() != ''){
			$("#form_add_data").submit();
		}
	});	

});

</script>
