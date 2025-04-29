<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css'); ?>"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css'); ?>"/>
<?php echo link_tag('assets/global/plugins/bootstrap-datepicker/css/datepicker3.css'); ?>

<div class="page-content">
	<div class='container'>

		<?foreach ($supplier_data as $row) {
			$nama_supplier = $row->nama;
		}?>
		<div class="row margin-top-10">
			<div class="col-md-12">
				<div class="portlet light">
					<div class="portlet-title">
						<div class="caption caption-md">
							<i class="icon-bar-chart theme-font hide"></i>
							<span class="caption-subject theme-font bold uppercase"><?=$breadcrumb_small;?> - <?=$nama_supplier;?></span>
						</div>
					</div>
					<div class="portlet-body">
						<div class="tabbable tabbable-custom">
							<ul class="nav nav-tabs">
								<li class="active">
									<a href="#tab_1_1" data-toggle="tab">
									Hutang </a>
								</li>
								<li>
									<a href="#tab_1_2" data-toggle="tab">
									Retur </a>
								</li>
							</ul>

							<div class="tab-content">
								<div class="tab-pane active" id="tab_1_1">
									<h4>Hutang List</h4>
									<table class="table table-hover table-striped table-bordered" id="general_table">
										<thead>
											<tr>									
												<th scope="col">
													Tanggal
												</th>
												<th>
													No Faktur
												</th>
												<th scope="col">
													Hutang
												</th>
												<th scope="col">
													Actions
												</th>
											</tr>
										</thead>
										<tbody>
											<?
											$hutang_total = 0;
											foreach ($hutang_list_detail as $row) { ?>
												<tr>
													<td>
														<?=is_reverse_date($row->tanggal);?>
													</td>
													<td>
														<?if ($row->tanggal < $this->tanggal_start_program) {?>
															<?=$row->no_faktur;?> 
														<?}else{?>
															<a target='_blank' href="<?=base_url().is_setting_link('transaction/pembelian_list_detail');?>/<?=$row->id;?>"><?=$row->no_faktur;?> <?=($row->OCKH != '' ? "($row->OCKH)" : '');?></a> 
														<?}?>
													</td>
													<td>
														<?=number_format($row->sisa_hutang,'0',',','.');?>
														<?$hutang_total += $row->sisa_hutang;?>
													</td>
													<td>
													</td>
												</tr>
											<?}?>
										</tbody>
										<tfoot>
											<tr style="font-size:1.2em;">
												<td class='text-right' colspan='2'>
													<b>TOTAL</b>
												</td>
												<td>
													<b><?=number_format($hutang_total,0,',','.');?></b>
												</td>
												<td></td>
											</tr>
										</tfoot>
									</table>
								</div>
								<div class="tab-pane" id="tab_1_2">
									<h4>Retur List</h4>
									<table class="table table-hover table-striped table-bordered" id="general_table">
										<thead>
											<tr>									
												<th scope="col">
													Tanggal
												</th>
												<th>
													No SJ
												</th>
												<th scope="col">
													NILAI
												</th>
												<th>
													Keterangan
												</th>
											</tr>
										</thead>
										<tbody>
											<?
											$retur_total = 0;
											foreach ($retur_list_detail as $row) { ?>
												<tr>
													<td>
														<?=is_reverse_date($row->tanggal);?>
													</td>
													<td>
														<?if ($row->tanggal < $this->tanggal_start_program) {?>
															<?=$row->no_sj_lengkap;?>
														<?}else{?>
															<a target='_blank' href="<?=base_url().is_setting_link('transaction/retur_beli_list_detail');?>/<?=$row->id;?>"><?=$row->no_sj_lengkap;?></a> 
														<?}?>
													</td>
													<td>
														<?=number_format($row->sisa_hutang,'0',',','.');?>
														<?$retur_total += $row->sisa_hutang;?>
													</td>
													<td>
														<?=$row->keterangan1?>
														<?=$row->keterangan2?>
													</td>
												</tr>
											<?}?>
											<tr style="font-size:1.2em;">
												<td class='text-right' colspan='2'>
													<b>TOTAL</b>
												</td>
												<td>
													<b><?=number_format($retur_total,0,',','.');?></b>
												</td>
												<td></td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
					<div>
	                	<a href="javascript:window.open('','_self').close();" class="btn default button-previous hidden-print">Close</a>
					</div>
				</div>
			</div>
		</div>
	</div>			
</div>

<script src="<?php echo base_url('assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/datatables/media/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets_noondev/js/table-advanced.js'); ?>"></script>

<script>
jQuery(document).ready(function() {

	// dataTableTrue();

	// oTable = $('#general_table').DataTable();
	// oTable.state.clear();
	// oTable.destroy();
	$("#general_table").DataTable({
		"ordering":false,
		// "bFilter":false
	});


	$('.btn-save').click(function(){
		if ($('#form_add_data [name=tanggal]').val() != '' && $('#form_add_data [name=amount]').val() != '' ) {
			$('#form_add_data').submit();
		}else{
			alert('Tanggal dan Jumlah harus diisi');
		}
	});

});
</script>
