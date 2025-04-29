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
									<form action='' method='get'>
										<table>
											<tr>
												<td>Tanggal Mutasi</td>
												<td class='padding-rl-5'> : </td>
												<td>
													<b>
														<input name='tanggal_start' readonly class='date-picker' style='border:none; border-bottom:1px solid #ddd; width:100px;' value='<?=$tanggal_start;?>'>
														s/d
														<input name='tanggal_end' readonly class='date-picker2 ' style='border:none; border-bottom:1px solid #ddd; width:100px;' value='<?=$tanggal_end;?>'> 
													</b>
												</td>
												<td>
													<button class='btn btn-xs default'><i class='fa fa-search'></i></button>
												</td>
											</tr>
											<tr>
												<td>Toko</td>
												<td class='padding-rl-5'> : </td>
												<td>
													<b>
													<select name='toko_id' style='width:100%;'>
														<?foreach ($this->toko_list_aktif as $row) { ?>
															<option <?=($toko_id == $row->id ? "selected" : "" );?> value='<?=$row->id;?>'><?=$row->nama;?></option>
														<?}?>
													</select>
												</b></td>
												<td></td>
											</tr>
										</table>
									</form>
								</td>
								<td class='text-right'>
									<a href="<?=base_url();?>finance/mutasi_hutang_excel?&tanggal_start=<?=is_date_formatter($tanggal_start)?>&tanggal_end=<?=is_date_formatter($tanggal_end);?>&toko_id=<?=$toko_id;?>" class='btn btn-md green'><i class='fa fa-download'></i> EXCEL</a>
									<a href="<?=base_url();?>finance/mutasi_hutang_excel_all?&tanggal_start=<?=is_date_formatter($tanggal_start)?>&tanggal_end=<?=is_date_formatter($tanggal_end);?>&toko_id=<?=$toko_id;?>" class='btn btn-md blue'><i class='fa fa-download'></i> EXCEL ALL</a>
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
										Pembelian
									</th>
									<th scope="col" rowspan='2'>
										Retur (sudah kompensasi)
									</th>
									<th scope="col" colspan='5' style='border:1px solid #ddd'>
										Pembayaran
									</th>
									<th rowspan='2'>
										Saldo Akhir
									</th>
									<th scope="col" rowspan='2'>
										Retur (blm kompensasi)
									</th>
									<th rowspan='2'>
										Action
									</th>
								</tr>
								<tr>
									<th scope="col">
										Transfer
									</th>
									<th scope="col">
										GIRO Mundur
									</th>
									<th scope="col">
										CEK
									</th>
									<th scope="col">
										CASH
									</th>
									<th scope="col">
										PEMBULATAN
									</th>

								</tr>
							</thead>
							<tbody>
								<?foreach ($mutasi_list as $row) { ?>
									<tr>
										<td>
											<?=is_reverse_date($row->nama_supplier);?>
										</td>
										<td>
											<?=number_format($row->amount - $row->amount_bayar,'0',',','.');?>
										</td>
										<td>
											<?=number_format($row->amount_beli,'0',',','.');?>
										</td>
										<td>
											<?=($row->amount_retur > 0 ? "<span style='color:red'>(".number_format($row->amount_retur,'0',',','.').")</span>" : "0");?>
										</td>
										<?
										$bayar1 = 0;
										$bayar2 = 0;
										$bayar3 = 0;
										$bayar4 = 0;
										$bayar5 = 0;
										$bayar99 = 0;
										$total_bayar = 0;
										foreach ($bayar_list[$row->supplier_id] as $row2) {
											$total_bayar += $row2->bayar;
											if ($row2->pembayaran_type_id == 1) {
												$bayar1 = number_format($row2->bayar,'0',',','.');
											}

											if ($row2->pembayaran_type_id == 2) {
												$bayar2 = number_format($row2->bayar,'0',',','.');
											}
											if ($row2->pembayaran_type_id == 5) {
												$bayar5 = number_format($row2->bayar,'0',',','.');
											}
											if ($row2->pembayaran_type_id == 3) {
												$bayar3 = number_format($row2->bayar,'0',',','.');
											}

											if ($row2->pembayaran_type_id == 4) {
												$bayar4 = number_format($row2->bayar,'0',',','.');
											}

											if ($row2->pembayaran_type_id == 99) {
												$bayar99 = number_format($row2->bayar,'0',',','.');
											}

										}?>
											<td><?=$bayar1;?></td>
											<td><?=$bayar2;?></td>
											<td><?=$bayar5;?></td>
											<td><?=$bayar3;?></td>
											<td><?=$bayar99;?></td>
											<!-- <td><?=$bayar4;?></td> -->
											<td>
												<?=number_format($row->amount - $row->amount_bayar + $row->amount_beli - $total_bayar  - $row->amount_retur,'0',',','.');?>
											</td>
											<td style="<?=($row->amount_retur_belum > 0 ? 'background:#f6fac8' : '');?>">
												<?=($row->amount_retur_belum > 0 ? "<span>(".number_format($row->amount_retur_belum,'0',',','.').")</span>" : "0");?>
											</td>
										<td>
											<a href="<?=base_url().is_setting_link('finance/mutasi_hutang_list_detail')?>?supplier_id=<?=$row->supplier_id;?>&tanggal_start=<?=is_date_formatter($tanggal_start)?>&tanggal_end=<?=is_date_formatter($tanggal_end)?>&toko_id=<?=$toko_id;?>" class='btn btn-xs yellow-gold'  onclick="window.open(this.href, 'newwindow', 'width=1250, height=650'); return false;"><i class='fa fa-search'></i></a>
										</td>
									</tr>
								<? } ?>

							</tbody>
						</table>
						<div>
		                	<!-- <a href="javascript:window.open('','_self').close();" class="btn default button-previous hidden-print">Close</a> -->
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

<script>
jQuery(document).ready(function() {
	
});
</script>
