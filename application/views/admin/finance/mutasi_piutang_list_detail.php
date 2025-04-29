<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css'); ?>"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css'); ?>"/>
<?=link_tag('assets/global/plugins/bootstrap-datepicker/css/datepicker3.css'); ?>

<style type="text/css">
#general_table tr td, #general_table tr th {
	text-align: center;
	vertical-align: middle;
}

.faktur_list {
    -webkit-column-count: 4; /* Chrome, Safari, Opera */
    -moz-column-count: 4; /* Firefox */
    column-count: 4;
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
												<td style='width:300px'>
													<b>
														<input name='tanggal_start' readonly class='date-picker' style='border:none; border-bottom:1px solid #ddd; width:100px;' value='<?=$tanggal_start;?>'>
														s/d
														<input name='tanggal_end' readonly class='date-picker2 ' style='border:none; border-bottom:1px solid #ddd; width:100px;' value='<?=$tanggal_end;?>'> 
														<button class='btn btn-xs default'><i class='fa fa-search'></i></button>
													</b>
												</td>
											</tr>
											<tr>
												<td>Customer</td>
												<td class='padding-rl-5'> : </td>
												<td width='200px'>
													<?//=$customer_id;?>
													<b>
													<!-- <select name='customer_id' id='customer_id_select' style='width:100%;'>
														<option <?=($customer_id == '' ? "selected" : "" );?> value=''>Pilih</option>
														<?foreach ($this->customer_list_aktif as $row) { ?>
															<option <?=($customer_id == $row->id ? "selected" : "" );?> value='<?=$row->id;?>'><?=$row->nama;?></option>
														<?}?>
													</select> -->
													<?foreach ($this->customer_list_aktif as $row) { 
														($customer_id == $row->id ? $nama_customer = $row->nama : "" );
													}
													?>
													<input name='customer_id' value='<?=$customer_id;?>' hidden>
													<input style='width:100%;' disabled name='nama_customer' value='<?=$nama_customer;?>'>
												</b></td>
											</tr>
											<tr>
												<td>Toko</td>
												<td class='padding-rl-5'> : </td>
												<td>
													<b>
													<!-- <select disabled name='toko_id' style='width:100%;'>
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
											</tr>
										</table>							
									</form>
								</td>
								<td class='text-right'>
									<a href="<?=base_url().'finance/mutasi_piutang_list_detail_excel?tanggal_start='.is_date_formatter($tanggal_start).'&tanggal_end='.is_date_formatter($tanggal_end).'&toko_id='.$toko_id;?>&customer_id=<?=$customer_id?>" class='btn btn-md green'><i class='fa fa-download'></i> EXCEL</a>
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
										Tanggal
									</th>
									<th scope="col" rowspan='2'>
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
									<th scope="col">
										Pembayaran
									</th>

								</tr>
							</thead>
							<tbody>
								<? $saldo_awal = 0;
								$total_bon = 0;
								$total_bayar = 0;
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
										<td>
											<?=number_format($row->saldo_awal,'0',',','.');
											$saldo_awal = $row->saldo_awal;
											?>
										</td>
									</tr>
								<?}?>
								<?foreach ($mutasi_list as $row) { ?>
									<tr style="<?=($row->ket == 'jual' ? ($row->ket_lunas == 0 ? 'color:blue' : 'color:red') : '');?>">
										<td>
											<?=is_reverse_date($row->tanggal);?>
										</td>
										<td>
											<?
											$ket = '';
											if ($row->ket == 'bayar piutang') { 
												$ket = '(pelunasan piutang)';
												?>
												<a href="<?=base_url().is_setting_link('finance/piutang_payment_form')?>?id=<?=$row->id?>" target="_blank">
											<?}elseif ($row->ket == 'bayar jual' || $row->ket == 'jual') {?>
												<a href="<?=base_url().is_setting_link('transaction/penjualan_list_detail')?>?id=<?=$row->id?>" target="_blank">
											<?}elseif ($row->ket == 'tolakan_giro') {
												echo "Tolakan Giro NO : <b style=".($row->ket_lunas == 0 ? 'color:blue' : 'color:red').">".$row->no_faktur."</b>";
											}?>
											
											<?if ($row->amount_bayar != 0 && strlen($row->no_faktur) > 140) {?>
												<div class='faktur_list'>
													<?=$row->no_faktur;?>
												</div>
											<?}elseif ($row->ket != 'tolakan_giro'){ echo $row->no_faktur; }?>
											<?
											if ($row->ket == 'bayar_piutang') { ?>
												<</a>
											<?} ?>
											<?=$ket;?>
										</td>
										<td>
											<?$total_bon += $row->amount_jual;?>
											<?=($row->amount_jual == 0 ? '' : "<span class='amount_bayar'>".number_format($row->amount_jual,'0',',','.')."</span>" ) ;?>
											<?$saldo_awal += $row->amount_jual;?>
										</td>
										<td>
											<?$total_bayar += $row->amount_bayar; ?>
											<?echo ($row->amount_bayar == 0 ?  '' : "<span class='amount_jual'>".number_format($row->amount_bayar,'0',',','.')."</span>" );?>
											<?$saldo_awal -= $row->amount_bayar;?>
										</td>
										<td style='font-size:1.1em'>
											<b><?=number_format($saldo_awal,'0',',','.');?></b> 
										</td>
									</tr>
								<? } ?>

								<tr>
									<td colspan='2'>TOTAL</td>
									<td>
										<b><?=number_format($total_bon,'0',',','.');?></b> 
									</td>
									<td>
										<b><?=number_format($total_bayar,'0',',','.');?></b> 
									</td>
									<td></td>
								</tr>

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


    $('#general_table').on('change','.amount-jual',function () {
    	var ini = $(this).closest('tr');
    	var data = {};
		data['id'] = $(this).attr('id');;
		data['amount'] = $(this).val();
		var url = 'finance/update_pembayaran_nilai_by_mutasi';
		ajax_data_sync(url,data).done(function(data_respond  /*,textStatus, jqXHR*/ ){
			// alert(data_respond);
			if (data_respond == 'OK') {
				
			}else{
				alert("error mohon refresh dengan menekan F5");
			}
   		});
    })

    // $('.btn-search').click(function(){
    // 	$('#form-search').submit();
    // 	$(this).prop('disabled',true);
    // 	$(this).html("Load..");
    // });
});
</script>
