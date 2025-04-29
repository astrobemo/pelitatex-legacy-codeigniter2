<link href="<?=base_url('assets/global/plugins/bootstrap-modal/css/bootstrap-modal.css');?>" rel="stylesheet" type="text/css"/>

<div class="page-content">
	<div class='container'>

		<div class="modal fade" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body">
						<form action="<?=base_url('admin/maintenance_list_insert');?>" class="form-horizontal" id="form-maintenance" method="post">
							<h3 class='block'> New Maintenance</h3>
							
							<div class="form-group">
			                    <label class="control-label col-md-3">START<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<input readonly name='start_time' value="<?=date('d F Y H:i:s')?>" class="form-control">
			                    </div>
			                </div>	
		                </form>		                
					</div>

					<div class="modal-footer">
						<button type="button" class="btn blue btn-ok">START</button>
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
							<span class="caption-subject theme-font bold uppercase">Ganti Password</span>
						</div>
						<div class="actions">
							<a href="#portlet-config" data-toggle='modal' class="btn btn-default btn-sm btn-form-add">
							<i class="fa fa-plus"></i> New </a>
						</div>
					</div>
					<div class="portlet-body">
						<table class="table table-striped table-bordered table-hover" id="general_table">
							<thead>
								<tr>
									<th scope="col">
										Mulai
									</th>
									<th scope="col">
										Selesai
									</th>
									<th scope="col">
										By
									</th>
									<th scope="col">
										Status
									</th>
									<th scope="col" style="min-width:150px !important">
										Actions
									</th>
								</tr>
							</thead>
							<tbody>
								<?foreach ($maintenance_list as $row) { ?>
									<tr>
										<td><?=date('d F Y H:i:s', strtotime($row->start_time));?></td>
										<td><?=($row->end_time != '' ? date('d F Y H:i:s', strtotime($row->end_time)) : '' );?></td>
										<td><?//=$row->user_id()?></td>
										<td><?=$row->status?></td>
										<td><?if ($row->status == 1) {?>
											<a href="<?=base_url();?>admin/maintenance_off?id=<?=$row->id;?>" class='btn btn-xs yellow-gold'>OFF</a>
										<?}?></td>
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

<script src="<?php echo base_url('assets/global/plugins/bootstrap-modal/js/bootstrap-modalmanager.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/bootstrap-modal/js/bootstrap-modal.js'); ?>" type="text/javascript"></script>

<script>
$(document).ready(function() { 

	$('.btn-ok').click(function(){
		$('#form-maintenance').submit();
	});
});
</script>