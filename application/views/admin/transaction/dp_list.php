<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css'); ?>"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css'); ?>"/>

<div class="page-content">
	<div class='container'>

		<div class="row margin-top-10">
			<div class="col-md-12">
				<div class="portlet light">
					<div class="portlet-title">
						<div class="caption caption-md">
							<i class="icon-bar-chart theme-font hide"></i>
							<span class="caption-subject theme-font bold uppercase"><?=$breadcrumb_small;?></span>
						</div>
					</div>
					<div class="portlet-body">
						<table class="table table-striped table-bordered table-hover" id="general_table">
							<thead>
								<tr>
									<th scope="col">
										Nama
									</th>
									<th scope="col">
										Status
									</th>
									<th scope="col">
										Saldo DP
									</th>
									<th scope="col" style="min-width:150px !important">
										Actions
									</th>
								</tr>
							</thead>
							<tbody>
								<?foreach ($dp_list as $row) { ?>
									<tr>
										<td>
											<span class='nama'><?=$row->nama;?></span>
										</td>
										<td>
											<?if ($row->status_aktif == 1) { ?>
												<span style='color:green'>AKTIF</span>
											<?}else{ ?>
												<span style='color:red'>TIDAK AKTIF</span>
											<?}?>
										</td>
										<td>
											<span class='saldo'><?=number_format($row->saldo,'0',',','.');?></span> 
										</td>
										<td>
											<span class='id' hidden="hidden"><?=$row->id;?></span>
											<a href="<?=base_url().is_setting_link('transaction/dp_list_detail').'/'.$row->id?>" class="btn-xs btn yellow-gold btn-view"><i class="fa fa-search"></i> </a>
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

	// dataTableTrue();

	oTable = $('#general_table').DataTable();
	// oTable.state.clear();
	// oTable.destroy();

});
</script>
