<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css'); ?>"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css'); ?>"/>
<?=link_tag('assets/global/plugins/bootstrap-datepicker/css/datepicker3.css'); ?>

<style type="text/css">
#general_table tr td, #general_table tr th {
	text-align: center;
	vertical-align: middle;
}
</style>

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
						<table width='100%'>
							<tr>
								<td>
									<form id='form-search' action='' method='get'>
										<table>
											<tr>
												<td>Tanggal Mutasi</td>
												<td class='padding-rl-5'> : </td>
												<td>
													<b>
														<input name='tanggal_start' readonly class='date-picker' style='border:none; border-bottom:1px solid #ddd; width:100px;' value='<?=$tanggal_start;?>'>
															s/d
														<input name='tanggal_end' readonly class='date-picker2 ' style='border:none; border-bottom:1px solid #ddd; width:100px;' value='<?=$tanggal_end;?>'> 
														<button class='btn btn-xs default'><i class='fa fa-search'></i></button>
													</b>
												</td>
											</tr>
											<tr>
												<td>Toko</td>
												<td class='padding-rl-5'> : </td>
												<td>
													<b>
													<select name='toko_id'>
														<?foreach ($this->toko_list_aktif as $row) { ?>
															<option <?=($toko_id == $row->id ? "selected" : "" );?> value='<?=$row->id;?>'><?=$row->nama;?></option>
														<?}?>
													</select>
												</b></td>
											</tr>
										</table>
										
										
									</form>
								</td>
								<td class='text-right'>
									<a href="<?=base_url().'finance/mutasi_piutang_excel?tanggal_start='.is_date_formatter($tanggal_start).'&tanggal_end='.is_date_formatter($tanggal_end).'&toko_id='.$toko_id;?>" class='btn btn-md green'><i class='fa fa-download'></i> EXCEL</a>
								</td>
							</tr>
						</table>
									
						<hr/>
						<?
							$qty = 0;
							$roll = 0;
							?>
						<table class="table table-striped table-bordered table-hover" id="general_table">
							<thead>
								<tr>
									<th scope="col" rowspan='2'>
										Nama Supplier
									</th>
									<th scope="col" rowspan='2'>
										Saldo Awal
									</th>
									<th scope="col" rowspan='2'>
										Penjualan
									</th>
									<th scope="col" rowspan='2'>
										Retur
									</th>
									<th scope="col" rowspan='2'>
										Giro Tolakan
									</th>
									<?$kolom = count($pembayaran_type);?>
									<th scope="col" colspan='<?=$kolom;?>' style='border-bottom:1px solid #ddd'>
										Pembayaran
									</th>
									<th scope="col" rowspan='2'>
										Saldo
									</th>
									<th rowspan='2'>
										Action
									</th>
								</tr>
								<tr>
									<!-- <th scope="col">
										Transfer
									</th>
									<th scope="col">
										GIRO Mundur
									</th>
									<th scope="col" style='border-right:1px solid #ddd'>
										CASH
									</th> -->

									<?foreach ($pembayaran_type as $row) { 
										if ($row->id != 5) { ?>
											<th scope="col" style='border-right:1px solid #ddd'><?=$row->nama;?></th>
										<?}?>
									<?}?>

									<th scope="col" style='border-right:1px solid #ddd'>Pembulatan</th>


								</tr>
							</thead>
							<tbody>
								<?

								foreach ($pembayaran_pembulatan as $row2) {
									$pmbltn[$row2->customer_id] = $row2->pembulatan;
								}

								foreach ($mutasi_list as $row) { 
									$total = 0; $count = 0;

									foreach ($pembayaran_type as $row2) {
										${"bayar".$row2->id} = 0;
									}

									foreach ($bayar_list as $row2) {
										if ($row2->customer_id == $row->customer_id) {
											# code...
											if ($row2->pembayaran_type_id == 1) {
												$bayar4 += $row2->bayar;
												$count++;
											}
	
											if ($row2->pembayaran_type_id == 2) {
												$bayar6 += $row2->bayar;
												$count++;
											}
											if ($row2->pembayaran_type_id == 3) {
												$bayar2 += $row2->bayar;
												$count++;
											}
	
											if ($row2->pembayaran_type_id == 4) {
												$bayar3 += $row2->bayar;
												$count++;
											}
	
											if ($row2->pembayaran_type_id == 5) {
												$bayar1 += $row2->bayar;
												$count++;
											}
										}
									}

									foreach ($bayar_list_jual as $row2) {
										// echo $row2->customer_id;
										if ($row2->customer_id == $row->customer_id) {
											# code...
											if ($row2->pembayaran_type_id == 1) {
												$bayar1 += $row2->bayar;
											}
	
											if ($row2->pembayaran_type_id == 2) {
												$bayar2 += $row2->bayar;
											}
											if ($row2->pembayaran_type_id == 3) {
												$bayar3 += $row2->bayar;
											}
											if ($row2->pembayaran_type_id == 4) {
												$bayar4 += $row2->bayar;
											}
											// if ($row2->pembayaran_type_id == 5) {
											// 	$bayar5 += $row2->bayar;
											// }
											if ($row2->pembayaran_type_id == 6) {
												$bayar6 += $row2->amount;
											}
										}
									}

									$total_bayar = 0;
									foreach ($pembayaran_type as $row2) {
										$total_bayar += ${"bayar".$row2->id}; 
									}


									$sisa_jualan = $row->penjualan - $row->pembayaran_penjualan;

									if ($row->amount - $row->amount_bayar != 0 || $sisa_jualan > 0 || $count > 0 || $row->giro_tolakan > 0 ) { ?>
										<tr>
											<td>
												<?=is_reverse_date($row->nama_customer);?>
											</td>
											<td>
												<?=number_format($row->amount - $row->amount_bayar,'0',',','.');?>
												<?$total+=$row->amount - $row->amount_bayar;?>
											</td>
											<td>
												<?=number_format($row->penjualan,'0',',','.');?>
												<?$total+=$row->penjualan;?>
											</td>
											<td>
												<span style='color:red'><?=number_format($row->retur_jual,'0',',','.');?></span> 
												<?$total-=$row->retur_jual;?>
											</td>
											<td>
												<?=number_format($row->giro_tolakan,'0',',','.');?>
												<?$total+=$row->giro_tolakan;?>
											</td>
											<?
											
											foreach ($pembayaran_type as $row2) { 
												if ($row2->id != 5) { 
													$total -= ${"bayar".$row2->id};?>
													<td><?=(${"bayar".$row2->id} != 0 ? number_format(${"bayar".$row2->id},'0',',','.') : '');?></td>
												<?}
												?>
											<?}?>

											<td>
												<?if (isset($pmbltn[$row->customer_id])) {?>
													<?$total -= $pmbltn[$row->customer_id];?>
													<?=number_format($pmbltn[$row->customer_id],'0',',','.');?>
												<?}?>
											</td>
												<td>
													<?=number_format($total,'0',',','.');?>
												</td>
											<td>
												<a href="<?=base_url().is_setting_link('finance/mutasi_piutang_list_detail')?>?customer_id=<?=$row->customer_id;?>&tanggal_start=<?=is_date_formatter($tanggal_start)?>&tanggal_end=<?=is_date_formatter($tanggal_end)?>&toko_id=<?=$toko_id;?>" class='btn btn-xs yellow-gold'  onclick="window.open(this.href, 'newwindow', 'width=1250, height=650'); return false;"><i class='fa fa-search'></i></a>
											</td>
										</tr>
									<?}?>
								<? } ?>

							</tbody>
						</table>
						<div>
		                	<a href="javascript:window.open('','_self').close();" class="btn default button-previous hidden-print">Close</a>
						</div>
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
<script src="<?php echo base_url('assets/global/plugins/bootbox/bootbox.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/select2/select2.min.js'); ?>" type="text/javascript" ></script>


<script>
jQuery(document).ready(function() {
    $('#customer_id_select').select2({
        placeholder: "Pilih..."
    });

    $("#general_table").DataTable({
		"ordering":false,
	});

});
</script>
