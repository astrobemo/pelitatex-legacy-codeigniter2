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
						<form action='' method='get'>
							<table>
								<tr>
									<td>Tanggal Mutasi</td>
									<td class='padding-rl-5'> : </td>
									<td width='300px'>
										<b>
											<input name='tanggal_start' readonly class='date-picker' style='border:none; border-bottom:1px solid #ddd; width:100px;' value='<?=$tanggal_start;?>'>
											s/d
											<input name='tanggal_end' readonly class='date-picker2 ' style='border:none; border-bottom:1px solid #ddd; width:100px;' value='<?=$tanggal_end;?>'> 
										</b>
									</td>
									<td>
										<button class='btn btn-xs default btn-search'><i class='fa fa-search'></i></button>
									</td>
								</tr>
								<tr>
									<td>Supplier</td>
									<td class='padding-rl-5'> : </td>
									<td>
										<b>
										<!-- <select readonly name='supplier_id'>
											<option <?=($supplier_id == '' ? "selected" : "" );?> value=''>Pilih</option>
											<?foreach ($this->supplier_list_aktif as $row) { ?>
												<option <?=($supplier_id == $row->id ? "selected" : "" );?> value='<?=$row->id;?>'><?=$row->nama;?></option>
											<?}?>
										</select> -->
										<?foreach ($this->supplier_list_aktif as $row) { 
											($supplier_id == $row->id ? $nama_supplier = $row->nama : "" );
										}?>
										<input name='supplier_id' value='<?=$supplier_id;?>' hidden>
										<input style='width:100%;' disabled name='nama_supplier' value='<?=$nama_supplier;?>'>
									</b></td>
									<td></td>
								</tr>
								<tr>
									<td>Toko</td>
									<td class='padding-rl-5'> : </td>
									<td>
										<b>
										<!-- <select readonly name='toko_id'>
											<?foreach ($this->toko_list_aktif as $row) { ?>
												<option <?=($toko_id == $row->id ? "selected" : "" );?> value='<?=$row->id;?>'><?=$row->nama;?></option>
											<?}?>
										</select> -->
										<?foreach ($this->toko_list_aktif as $row) { 
											$nama_toko = ($toko_id == $row->id ? $row->nama : "" );
										}?>
										<input name='toko_id' value='<?=$toko_id;?>' hidden>
										<input style='width:100%;' disabled name='nama_toko' value='<?=$nama_toko;?>'>										

									</b></td>
									<td></td>
								</tr>
								
							</table>
							
							
						</form>
						<hr/>
						<?
							$qty = 0;
							$roll = 0;
							?>
						<table class="table table-striped table-bordered table-hover" id="general_table">
							<thead>
								<tr>
									<th scope="col" rowspan='2'>
										Tanggal
									</th>
									<th scope="col" rowspan='2' style='width:200px;'>
										Keterangan
									</th>
									<th scope="col" colspan='2' style='border-bottom:1px solid #ddd'>
										Mutasi
									</th>
									<th rowspan='2'>
										Saldo
									</th>
								</tr>
								<tr>
									<th scope="col">
										Total Bon
									</th>
									<th scope="col"  style='border-right:1px solid #ddd'>
										Pembayaran
									</th>

								</tr>
							</thead>
							<tbody>
								<? $saldo_awal = 0; $total_bon = 0; $total_bayar = 0 ;
								foreach ($saldo_awal_list as $row) { ?>
									<tr>
										<td>
											
										</td>
										<td>
											<b>Saldo Awal</b>
										</td>
										<td>
										</td>
										<td>
										</td>
										<td style='font-size:1.1em'>
											<b>
												<?=number_format($row->saldo_awal,'0',',','.');
												$saldo_awal = $row->saldo_awal;
												?>
											</b>
										</td>
									</tr>
								<?}?>
								<?foreach ($mutasi_list as $row) { ?>
									<tr style="<?=($row->status_lunas == 1 ? 'color:blue' : ($row->status_lunas == 2 ? 'color:#e86161' : 'color:black'));?>" >
										<td>
											<?=is_reverse_date($row->tanggal);?>
										</td>
										<td>
											<?if ($row->tipe == 1) {?>
												<a target='_blank' href="<?=base_url().is_setting_link('transaction/pembelian_list_detail')?>/<?=$row->pembelian_id?>"><?=$row->no_faktur;?></a> 
											<?}elseif ($row->tipe == 2) {?>
												<a target='_blank' href="<?=base_url().is_setting_link('finance/hutang_payment_form')?>?id=<?=$row->detail_id?>"><?=$row->no_faktur;?></a> 
											<?}else{?>
												<?=$row->no_faktur;?>
											<?}?>
										</td>
										<td>
											<?=($row->amount_beli == 0 ? '' : "<span class='amount_bayar'>".number_format($row->amount_beli,'0',',','.')."</span>" ) ;?>
											<?$saldo_awal += $row->amount_beli; $total_bon += $row->amount_beli;?>
										</td>
										<td>
											<?=($row->amount_bayar == 0 ?  '' : "<span class='amount_beli'>".number_format($row->amount_bayar,'0',',','.')."</span>" );?>
											<?$saldo_awal -= $row->amount_bayar; $total_bayar += $row->amount_bayar;?>
										</td>
										<td style='font-size:1.1em'>
											<b><?=number_format($saldo_awal,'0',',','.');?></b>
											<?//=$row->data_status;?> 
										</td>
									</tr>
								<? } ?>

							</tbody>
							<tfoot>
								<tr>
									<th colspan='3'>
										TOTAL
									</th>
									<th>
										<?=number_format($total_bon,'0',',','.')?>
									</th>
								</tr>
							</tfoot>
						</table>
						<br/>
						<table class='table'>
							<tr>
								<th></th>
								<th>BON</th>
								<th>PEMBAYARAN</th>
								<th>SALDO</th>
							</tr>
							<tr style="font-size:1.1em; font-weight:bold">
								<td>
									TOTAL
								</td>
								<td>
									<?=number_format($total_bon,'0',',','.');?>
								</td>
								<td>
									<?=number_format($total_bayar,'0',',','.');?>
								</td>
								<td style=''>
									<b><?=number_format($saldo_awal,'0',',','.');?></b>
								</td>
							</tr>
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

<script>
jQuery(document).ready(function() {

	oTable = $('#general_table').DataTable();
	oTable.state.clear();
	oTable.destroy();

	$("#general_table").DataTable({
		"ordering":false
	});
});
</script>
