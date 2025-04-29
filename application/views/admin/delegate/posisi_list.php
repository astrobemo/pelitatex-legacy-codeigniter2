<link href="<?=base_url('assets_noondev/css/bootstrap-modal-bs3patch.css');?>" rel="stylesheet" type="text/css"/>
<link href="<?=base_url('assets/global/plugins/bootstrap-modal/css/bootstrap-modal.css');?>" rel="stylesheet" type="text/css"/>
<div class="page-content">
	<div class='container'>
		
		<div id="ajax-modal" class="modal fade" tabindex="-1">
		</div>

		<div class="modal fade" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<!-- <div class="modal-dialog"> -->
				<div class="modal-content">
					<!-- <div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
						<h4 class="modal-title">Modal title</h4>
					</div> -->
					<div class="modal-body">
						<form action="<?=base_url('delegate/posisi_list_insert')?>" class="form-horizontal" id="form_add_data" method="post">
							<h3 class='block'> Tambah </h3>
				                <div class="form-group">
				                    <label class="control-label col-md-3">Posisi<span class="required">
				                    * </span>
				                    </label>
				                    <div class="col-md-6">
				                    	<input type="text" class="form-control input1" name="name"/>
				                    </div>
				                    
				                </div>
									
						</form>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn blue btn-add-posisi">Save</button>
						<button type="button" class="btn default" data-dismiss="modal">Close</button>
					</div>
				</div>
				<!-- /.modal-content -->
			<!-- </div> -->
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
							<a href="#portlet-config" data-toggle='modal' class="btn btn-default btn-sm btn-form-add">
							<i class="fa fa-plus"></i> Tambah </a>
						</div>
					</div>
					<div class="portlet-body">
						<table class="table table-striped table-bordered table-hover" id="general_table">
							<thead>
								<tr>
									<th scope="col">
										Posisi
									</th>
									<th scope="col" style="min-width:150px !important">
										Actions
									</th>
								</tr>
							</thead>
							<tbody>
								<?foreach ($posisi_list as $row) { ?>
									<?if ($row->id == 1) { ?>
										<?if ($user_id == 1) { ?>
											<tr>
												<td>
													<span class='id' hidden="hidden"><?=$row->id;?></span>
													<span class='name'><?=$row->name;?></span> 
												</td>
												<td>
													<a data-toggle='modal' class="btn-xs btn yellow-gold btn-manage"><i class="fa fa-edit"></i> Manage</a>
												</td>
											</tr>
										<?}
									}else{ ?>
										<tr>
											<td>
												<span class='id' hidden="hidden"><?=$row->id;?></span>
												<span class='name'><?=$row->name;?></span> 
											</td>
											<td>
												<a data-toggle='modal' class="btn-xs btn yellow-gold btn-manage"><i class="fa fa-edit"></i> Manage</a>
											</td>
										</tr>
									<? } ?>
								<? } ?>

							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>			
</div>

<script src="<?php echo base_url('assets_noondev/js/ui-extended-modals.js'); ?>"></script>
<script src="<?php echo base_url('assets/global/plugins/bootstrap-modal/js/bootstrap-modalmanager.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/bootstrap-modal/js/bootstrap-modal.js'); ?>" type="text/javascript"></script>

<script>
jQuery(document).ready(function() {       
   	UIExtendedModals.init();

   	$('.btn-add-posisi').click(function(){
   		if( $('#form_add_data [name=name]').val() != '' ){
   			$('#form_add_data').submit();
   		}
   	});
});
</script>
