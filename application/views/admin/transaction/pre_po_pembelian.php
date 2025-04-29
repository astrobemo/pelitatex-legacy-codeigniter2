<div class="page-content">
	<div class='container'>
		
		<div class="modal fade" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body">
						<form action="<?=base_url('transaction/pre_po_pembelian_insert')?>" class="form-horizontal" id="form_add_data" method="post">
							<h3 class='block'> Tambah </h3>
				                <div class="form-group">
				                    <label class="control-label col-md-3">Tanggal<span class="required">
				                    * </span>
				                    </label>
				                    <div class="col-md-6">
				                    	<input type="text" class="form-control input1 date-picker" name="tanggal" value="<?=date('d/m/Y')?>" />
				                    </div>				                    
				                </div>

						</form>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn blue btn-active btn-save">Save</button>
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
									<th scope="col">
										Per Tanggal
									</th>
									<th scope="col" style="min-width:150px !important">
										Actions
									</th>
								</tr>
							</thead>
							<tbody>
								<?foreach ($pre_po_list as $row) { ?>
									<tr>
										<td>
											<span class='id' hidden><?=$row->id;?></span>
											<span class='tanggal'><?=is_reverse_date($row->tanggal);?></span>
										</td>
										<td>
											<a href="<?=base_url().is_setting_link('transaction/pre_po_pembelian_detail')?>?id=<?=$row->id;?>" class="btn-xs btn green btn-edit"><i class="fa fa-edit"></i> </a>
										</td>
									</tr>
								<? } ?>

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
<script src="<?php echo base_url('assets_noondev/js/table-advanced.js'); ?>"></script>

<script>
jQuery(document).ready(function() {       

	TableAdvanced.init();
	
   	$('.btn-save').click(function(){
   		if( $('#form_add_data [name=tanggal]').val() != '' ){
   			$('#form_add_data').submit();
   		}
   	});
});
</script>
