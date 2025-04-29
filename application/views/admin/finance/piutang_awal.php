<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css'); ?>"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css'); ?>"/>
<?php echo link_tag('assets/global/plugins/bootstrap-datepicker/css/datepicker3.css'); ?>

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
						
						<table class="table table-hover table-striped table-bordered" id="general_table">
							<thead>
								<tr>									
									<th scope="col">
										Nama Supplier
									</th>
									<th scope="col">
										Jumlah Faktur
									</th>
									<th scope="col">
										Piutang Awal
									</th>
									<th scope="col">
										Pelunasan
									</th>
									<th scope="col">
										Detail
									</th>
									<th scope="col">
										Actions
									</th>
								</tr>
							</thead>
							<tbody>
								<?
								$tipe[1] = 'TRANSFER'; 
								$tipe[2] = 'GIRO';
								$tipe[3] = 'CASH';
								$tipe[4] = 'EDC';
								$tipe[5] = 'DP';
								foreach ($piutang_list as $row) { 
									$pembayaran_type_id = explode(',', $row->pembayaran_type_id);
									$amount_bayar = explode(',', $row->amount_bayar);
									$tanggal_bayar = explode(',', $row->tanggal_bayar);
									$total_bayar = 0;
									?>
									<tr>
										<td>
											<?=$row->nama;?>
										</td>
										<td>
											<?=$row->jumlah_nota;?>
										</td>
										<td>
											<?=number_format($row->amount,'0',',','.');?>
										</td>
										<td>
											<?=number_format($row->amount_kontra,'0',',','.');?>
										</td>
										<td>
											<table>
												<?$pembayaran_type_id = ($row->pembayaran_type_id == '' ? array() : $pembayaran_type_id)?>
												<?foreach ($pembayaran_type_id as $key => $value) {
													$total_bayar += $amount_bayar[$key];?>
													<tr>
														<td><?=$tipe[$value]?></td>
														<td style='padding:0 10px'><?=is_reverse_date($tanggal_bayar[$key])?></td>
														<td><?=number_format($amount_bayar[$key],'0',',','.');?></td>
													</tr>
												<?}?>
											<?if ($total_bayar != 0) {?>
													<tr style='border-top:1px solid #000'>
														<td></td>
														<td>TOTAL</td>
														<td><b><?=number_format($total_bayar,'0',',','.');?></b></td>
													</tr>
												<?}?>
											</table>

										</td>
										<td>
											<a href="<?=base_url().is_setting_link('finance/piutang_awal_detail').'?customer_id='.$row->id;?>" class="btn btn-xs blue" onclick="window.open(this.href, 'newwindow', 'width=1050, height=750'); return false;"><i class='fa fa-search'></i></a>
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

<script src="<?php echo base_url('assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/datatables/media/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets_noondev/js/table-advanced.js'); ?>"></script>

<script>
jQuery(document).ready(function() {

	// dataTableTrue();
	TableAdvanced.init();

	// oTable = $('#general_table').DataTable();
	// oTable.state.clear();
	// oTable.destroy();

	$('.btn-save').click(function(){
		if ($('#form_add_data [name=tanggal]').val() != '' && $('#form_add_data [name=amount]').val() != '' ) {
			$('#form_add_data').submit();
		}else{
			alert('Tanggal dan Jumlah harus diisi');
		}
	});

});
</script>
