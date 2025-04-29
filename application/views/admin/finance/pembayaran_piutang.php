<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css'); ?>"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css'); ?>"/>
<?php echo link_tag('assets/global/plugins/bootstrap-datepicker/css/datepicker3.css'); ?>

<style type="text/css">
.rincian-bayar{
	/*background: #eee;*/
	padding:10px 2px;
	margin:15px 0;
	border-top: 2px solid #000;
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
									<td>Customer</td>
									<td class='padding-rl-5'> : </td>
									<td>
										<select name='customer_id' id="customer_id_select" style='width:250px'>
											<option <?=($customer_id == '' ? "selected" : "");?> value="">Pilih..</option>
											<?foreach ($this->customer_list_aktif as $row) { ?>
												<option <?=($customer_id == $row->id ? "selected" : "");?>  value="<?=$row->id;?>"><?=$row->nama;?></option>
											<?}?>
										</select>
									</td>
								</tr>
								<tr>
									<td>Toko</td>
									<td class='padding-rl-5'> : </td>
									<td>
										<select name='toko_id'>
											<?foreach ($this->toko_list_aktif as $row) { ?>
												<option <?=($toko_id == $row->id ? "selected" : "");?>   value="<?=$row->id;?>"><?=$row->nama;?></option>
											<?}?>
										</select>
									</td>
								</tr>
								<tr>
									<td>Tanggal Pelunasan</td>
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
							</table>
						</form>

						<hr/>
						
						<?
						$stat = array();
						foreach ($pembayaran_piutang_list as $baris) { ?>
						<div class='rincian-bayar' id="bayar-<?=$baris->id;?>">
							<h4>
								<table width='100%'>
									<tr>
										<td>
											<table>
												<tr>
													<td>Customer</td>
													<td style='padding: 0 10px;'> : </td>
													<td><b><?=$baris->nama_customer;?></b> </td>
												</tr>
												<tr>
													<td>Toko</td>
													<td style='padding: 0 10px;'> : </td>
													<td><b><?=$baris->nama_toko;?></b> </td>
												</tr>
												<tr>
													<?
													$tanggal_bayar = '';
													foreach ($pembayaran_piutang_nilai[$baris->id] as $row) {
														$tanggal_bayar=is_reverse_date($row->tanggal_transfer);
													}?>
													<td>Tanggal Kontra</td>
													<td style='padding: 0 10px;'> : </td>
													<td>
														<b><?=(isset($baris->tanggal) ? is_reverse_date($baris->tanggal) : '-');?></b> 
													</td>
												</tr>
												<tr>
													<td>Periode Nota</td>
													<td style='padding: 0 10px;'> : </td>
													<td>
														<b><?=$periode[$baris->id]['tanggal_start'];?></b> 
														s/d
														<b><?=$periode[$baris->id]['tanggal_end'];?></b> 
													</td>
												</tr>
												<tr>
													<td>Total Faktur</td>
													<td style='padding: 0 10px;'> : </td>
													<td>
														<?$total = 0; $subtotal_bayar = 0; $idx = 0;
														foreach ($pembayaran_piutang_awal_detail[$baris->id] as $row) { 
															$total += $row->sisa_piutang;
														}

														foreach ($pembayaran_piutang_detail[$baris->id] as $row) {
															$total += $row->sisa_piutang;
														}

														foreach ($pembayaran_retur_detail[$baris->id] as $row) { 
															$total -= $row->amount;
														}?>
														<b><?=number_format($total,'0',',','.');?></b>

													</td>
												</tr>
											</table>
										</td>
										<td class='text-right'>
											<span class='unbalance_<?=$baris->id;?>' hidden style='color:red; margin-right:20px;'><b>UNBALANCED</b></span>
											<button id='button_<?=$baris->id;?>' class='btn btn-md yellow-gold btn-view'>VIEW <i class='fa fa-sort-down'></i></button>
											<a target='_blank' href="<?=base_url().is_setting_link('finance/piutang_payment_form')?>?id=<?=$baris->id;?>" class='btn btn-md green'>EDIT</a>
											<?if (is_posisi_id() < 4) {?>
											<span class='pembayaran_piutang_id' hidden><?=$baris->id?></span>
												<!-- <button class='btn btn-md red btn-remove' hidden>DEL</a> -->
												<button class='btn btn-md red' onclick="removeHutangPaymentFetch('<?=$baris->id;?>')">DEL</a>
											<?}?>
										</td>
									</tr>
								</table>
								</h4>

								<?
								$hidden ='';
								if (isset($status_view) && $status_view == 0) { $hidden='hidden'; }?>

								<div id='rinci_<?=$baris->id?>' <?=$hidden;?>>
									<table class="table table-hover table-striped table-bordered">
										<thead>
											<tr <?=$hidden?> >		
												<th scope="col">
													Tanggal Penjualan
												</th>
												<th scope="col">
													No Faktur
												</th>
												<th scope="col">
													Piutang
												</th>
												<th scope="col">
													Pembayaran
												</th>
												<th scope="col">
													Sisa
												</th>
												<!-- <th scope="col">
													Actions
												</th> -->
											</tr>
										</thead>
										<tbody>
											<?
											$total = 0; $subtotal_bayar = 0; $idx = 0; $total_bayar=0; $total_sisa = 0;
											foreach ($pembayaran_piutang_awal_detail[$baris->id] as $row) { 
												$total_bayar += $row->amount; $total_sisa += $row->sisa_piutang - $row->amount; ?>
												<tr>
													<td>
														<?=is_reverse_date($row->tanggal);?>
													</td>
													<td>
														<?=$row->no_faktur;?>
													</td>
													<td>
														<?=number_format($row->sisa_piutang,'0',',','.');
															$total += $row->sisa_piutang;
														?>
													</td>
													<td><?=number_format($row->amount,'0',',','.');?></td>
													<td><?=number_format($row->sisa_piutang - $row->amount,'0',',','.');?></td>
												</tr>
											<?}

											foreach ($pembayaran_piutang_detail[$baris->id] as $row) { 
												$total_bayar += $row->amount; $total_sisa += $row->sisa_piutang - $row->amount; ?>
												<tr>
													<td>
														<?=is_reverse_date($row->tanggal);?>
													</td>
													<td><?=$row->no_faktur;?></td>
													<td>
														<?=number_format($row->sisa_piutang,'0',',','.');
														$total += $row->sisa_piutang;
														?>
													</td>
													<td><?=number_format($row->amount,'0',',','.');?></td>
													<td><?=number_format($row->sisa_piutang - $row->amount,'0',',','.');?></td>
												</tr>
											<?}

											foreach ($pembayaran_retur_detail[$baris->id] as $row) { 
												$total_bayar -= $row->sisa_piutang; 
												$total_sisa -= $row->sisa_piutang - $row->amount; ?>
												<tr>
													<td>
														<?=is_reverse_date($row->tanggal);?>
													</td>
													<td><?=$row->no_faktur;?></td>
													<td style='color:red'>
														(<?=number_format($row->amount,'0',',','.');
														$total -= $row->amount;
														?>)
													</td>
													<td><?=number_format($row->sisa_piutang - $row->amount,'0',',','.');?></td>
													<td><?=number_format($row->sisa_piutang - $row->amount,'0',',','.');?></td>
												</tr>
											<?}?>

											<tr style='font-size:1.2em; border-top:2px solid #ccc;'>
												<td></td>
												<td><b>TOTAL</b></td>
												<td><b><?=number_format($total,'0',',','.');?></b></td>
												<td><b><?=number_format($total_bayar,'0',',','.');?></b></td>
												<td><b><?=number_format($total_sisa,'0',',','.');?></b></td>
											</tr>
											<?foreach ($pembayaran_piutang_nilai[$baris->id] as $row) { 
												$subtotal_bayar += $row->amount;
												?>
												<?if ($row->pembayaran_type_id == 1) { ?>
													<tr style='font-size:1.1em;'>
														<td></td>
														<td></td>
														<td> <b>Transfer</b> </td>
														<td> <b><?=number_format($row->amount,'0',',','.');?></b> </td>
														<td></td>
													</tr>
												<?}elseif ($row->pembayaran_type_id == 2) { ?>
													<tr style='font-size:1.1em;'>
														<td></td>
														<td></td>
														<td> <b>GIRO</b> </td>
														<td> <b><?=number_format($row->amount,'0',',','.');?></b> </td>
														<td></td>
													</tr>
												<?}elseif ($row->pembayaran_type_id == 3) { ?>
													<tr style='font-size:1.1em;'>
														<td></td>
														<td></td>
														<td> <b>CASH</b> </td>
														<td> <b><?=number_format($row->amount,'0',',','.');?></b> </td>
														<td></td>
													</tr>
												<?}elseif ($row->pembayaran_type_id == 4) { ?>
													<tr style='font-size:1.1em;'>
														<td></td>
														<td></td>
														<td> <b>EDC</b> </td>
														<td> <b><?=number_format($row->amount,'0',',','.');?></b> </td>
														<td></td>
													</tr>
												<?}elseif ($row->pembayaran_type_id == 5) { ?>
													<tr style='font-size:1.1em;'>
														<td></td>
														<td></td>
														<td> <b>DP</b> </td>
														<td> <b><?=number_format($row->amount,'0',',','.');?></b> </td>
														<td></td>
													</tr>
												<?}?>
											<?}?>

											<?if ($baris->pembulatan != 0) { ?>
												<tr style='font-size:1.1em;'>
													<td></td>
													<td> <b>Pembulatan</b> </td>
													<td> <b><?=number_format($baris->pembulatan,'0',',','.');?></b> </td>
													<td></td>
													<td></td>
												</tr>
											<?}?>

											<tr style='font-size:1.1em; background:#c2c2ff'>
												<td> <?$sisa_piutang=$total - ($subtotal_bayar + $baris->pembulatan);?></td>
												<td></td>
												<td> <b>SISA BAYAR</b> </td>
												<td> <b><?=number_format($total_bayar - ($subtotal_bayar + $baris->pembulatan),'0',',','.');?></b> </td>
												<td></td>
											</tr>
											<?if($total_bayar - ( $subtotal_bayar + $baris->pembulatan ) != 0) {
												$stat[$baris->id] = $baris->id;
												$idx++;
											}?>
										</tbody>
									</table>

									<table>
										<?
										$idx = 1;
										foreach ($pembayaran_piutang_nilai[$baris->id] as $row) { 
											$subtotal_bayar += $row->amount;
											?>
											<?if ($row->pembayaran_type_id == 1) { ?>
												<tr>
													<td rowspan='5' style='vertical-align:top; font-size:1.2em; padding: 0 10px 0 0'>
														<b><?=$idx?>. </b> 
													</td>
													<td> <b>Tipe </b> </td>
													<td> : </td>
													<td> <b>Transfer</b> </td>
												</tr>
												<tr style='font-size:1.1em;'>
													<td> <b>Tanggal Transfer </b> </td>
													<td> : </td>
													<td> <b><?=date('d/m/Y', strtotime($row->tanggal_transfer));?></b> </td>
												</tr>
												<tr style='font-size:1.1em;'>
													<td> <b>Bank</b> </td>
													<td> : </td>
													<td> <b><?=$row->nama_bank;?></b> </td>
												</tr>
												<tr style='font-size:1.1em;'>
													<td> <b>No Rek</b> </td>
													<td> : </td>
													<td> <b><?=$row->no_rek_bank;?></b> </td>
												</tr>
												<tr style='font-size:1.1em;'>
													<td><b>Nilai</b></td>
													<td> : </td>
													<td> <b><?=number_format($row->amount,'0',',','.');?></b> </td>
												</tr>
												<tr>
													<td colspan='3'>
														<hr/>
													</td>
												</tr>				
											<?}elseif ($row->pembayaran_type_id == 2) { ?>
												<tr>
													<td rowspan='7' style='vertical-align:top; font-size:1.2em; padding: 0 10px 0 0'>
														<b><?=$idx?>. </b> 
													</td>
													<td> <b>Tipe </b> </td>
													<td> : </td>
													<td> <b>Giro</b> </td>
												</tr>
												<tr style='font-size:1.1em;'>
													<td> <b>Tanggal Terima / Bayar</b> </td>
													<td> : </td>
													<td> <b><?=date('d/m/Y', strtotime($row->tanggal_transfer));?></b> </td>
												</tr>
												<tr style='font-size:1.1em;'>
													<td> <b>Bank</b> </td>
													<td> : </td>
													<td> <b><?=$row->nama_bank;?></b> </td>
												</tr>
												<tr style='font-size:1.1em;'>
													<td> <b>No Rek</b> </td>
													<td> : </td>
													<td> <b><?=$row->no_rek_bank;?></b> </td>
												</tr>
												<tr style='font-size:1.1em;'>
													<td> <b>No Giro</b> </td>
													<td> : </td>
													<td> <b><?=$row->no_giro;?></b> </td>
												</tr>
												<tr style='font-size:1.1em;'>
													<td> <b>Jatuh tempo</b> </td>
													<td> : </td>
													<td> <b><?=is_reverse_date($row->jatuh_tempo);?></b> </td>
												</tr>
												<tr style='font-size:1.1em;'>
													<td> <b>Nilai</b> </td>
													<td> : </td>
													<td> <b><?=number_format($row->amount,'0',',','.');?></b> </td>
												</tr>
												<tr>
													<td colspan='3'>
														<hr/>
													</td>
												</tr>
											<?}elseif ($row->pembayaran_type_id == 3) { ?>
												<tr>
													<td rowspan='4' style='vertical-align:top; font-size:1.2em; padding: 0 10px 0 0'>
														<b><?=$idx?>. </b> 
													</td>
													<td> <b>Tipe </b> </td>
													<td> : </td>
													<td> <b>Cash</b> </td>
												</tr>
												<tr style='font-size:1.1em;'>
													<td> <b>Tanggal Terima </b> </td>
													<td> : </td>
													<td> <b><?=is_reverse_date($row->tanggal_transfer);?></b> </td>
												</tr>
												<tr style='font-size:1.1em;'>
													<td><b>Nilai</b> </td>
													<td> : </td>
													<td> <b><?=number_format($row->amount,'0',',','.');?></b> </td>
												</tr>
												<tr style='font-size:1.1em;'>
													<td> <b>Penerima </b> </td>
													<td> : </td>
													<td> <b><?=is_reverse_date($row->nama_penerima);?></b> </td>
												</tr>
												<tr>
													<td colspan='3'>
														<hr/>
													</td>
												</tr>
											<?}elseif ($row->pembayaran_type_id == 4) { ?>
												<tr>
													<td rowspan='5' style='vertical-align:top; font-size:1.2em; padding: 0 10px 0 0'>
														<b><?=$idx?>. </b> 
													</td>
													<td> <b>Tipe </b> </td>
													<td> : </td>
													<td> <b>EDC</b> </td>
												</tr>
												<tr style='font-size:1.1em;'>
													<td> <b>Tanggal Terima </b> </td>
													<td> : </td>
													<td> <b><?=date('d/m/Y', strtotime($row->tanggal_transfer));?></b> </td>
												</tr>
												<tr style='font-size:1.1em;'>
													<td> <b>Bank</b> </td>
													<td> : </td>
													<td> <b><?=$row->nama_bank;?></b> </td>
												</tr>
												<tr style='font-size:1.1em;'>
													<td> <b>No Rek</b> </td>
													<td> : </td>
													<td> <b><?=$row->no_rek_bank;?></b> </td>
												</tr>
												<tr style='font-size:1.1em;'>
													<td><b>Nilai</b></td>
													<td> : </td>
													<td> <b><?=number_format($row->amount,'0',',','.');?></b> </td>
												</tr>
												<tr>
													<td colspan='3'>
														<hr/>
													</td>
												</tr>				
											<?}?>
										<? 

											if (isset($pembayaran_piutang_dp[$baris->id][$row->id])) {
												foreach ($pembayaran_piutang_dp[$baris->id][$row->id] as $row2) {?>
												<?if ($row2->pembayaran_type_id == 4) { ?>
													<tr>
														<td rowspan='5' style='vertical-align:top; font-size:1.2em; padding: 0 10px 0 0'>
															<b><?=$idx?>. </b> 
														</td>
														<td> <b>Tipe </b> </td>
														<td> : </td>
														<td> <b>Transfer</b> <b style='color:blue'>(DP)</b></td>
													</tr>
													<tr style='font-size:1.1em;'>
														<td> <b>Tanggal Transfer </b> </td>
														<td> : </td>
														<td> <b><?=date('d/m/Y', strtotime($row2->tanggal));?></b> </td>
													</tr>
													<tr style='font-size:1.1em;'>
														<td> <b>Bank</b> </td>
														<td> : </td>
														<td> <b><?=$row2->nama_bank;?></b> </td>
													</tr>
													<tr style='font-size:1.1em;'>
														<td> <b>No Rek</b> </td>
														<td> : </td>
														<td> <b><?=$row2->no_rek_bank;?></b> </td>
													</tr>
													<tr style='font-size:1.1em;'>
														<td><b>Nilai</b></td>
														<td> : </td>
														<td> 
															<?foreach ($bayar_dp_amount[$baris->id] as $key => $value) {?>
																<b><?=number_format($value,'0',',','.');?></b>
																<b hidden><?=number_format($row2->amount,'0',',','.');?></b> 
															<?}?>
														</td>
													</tr>
													<tr>
														<td colspan='3'>
															<hr/>
														</td>
													</tr>				
												<?}elseif ($row2->pembayaran_type_id == 6) { ?>
													<tr>
														<td rowspan='7' style='vertical-align:top; font-size:1.2em; padding: 0 10px 0 0'>
															<b><?=$idx?>. </b> 
														</td>
														<td> <b>Tipe </b> </td>
														<td> : </td>
														<td> <b>Giro</b>  <b style='color:blue'>(DP)</b></td>
													</tr>
													<tr style='font-size:1.1em;'>
														<td> <b>Tanggal Bayar</b> </td>
														<td> : </td>
														<td> <b><?=date('d/m/Y', strtotime($row2->tanggal));?></b> </td>
													</tr>
													<tr style='font-size:1.1em;'>
														<td> <b>Bank</b> </td>
														<td> : </td>
														<td> <b><?=$row2->nama_bank;?></b> </td>
													</tr>
													<tr style='font-size:1.1em;'>
														<td> <b>No Rek</b> </td>
														<td> : </td>
														<td> <b><?=$row2->no_rek_bank;?></b> </td>
													</tr>
													<tr style='font-size:1.1em;'>
														<td> <b>No Giro</b> </td>
														<td> : </td>
														<td> <b><?=$row2->no_giro;?></b> </td>
													</tr>
													<tr style='font-size:1.1em;'>
														<td> <b>Jatuh tempo</b> </td>
														<td> : </td>
														<td> <b><?=is_reverse_date($row2->jatuh_tempo);?></b> </td>
													</tr>
													<tr style='font-size:1.1em;'>
														<td> <b>Nilai</b> </td>
														<td> : </td>
														<td>
															<b><?=number_format($bayar_dp_amount[$baris->id][$row->id],'0',',','.');?></b>
															<b hidden><?=number_format($row2->amount,'0',',','.');?></b> 
														</td>
													</tr>
													<tr>
														<td colspan='3'>
															<hr/>
														</td>
													</tr>
												<?}elseif ($row2->pembayaran_type_id == 2) { ?>
													<tr>
														<td rowspan='4' style='vertical-align:top; font-size:1.2em; padding: 0 10px 0 0'>
															<b><?=$idx?>. </b> 
														</td>
														<td> <b>Tipe </b> </td>
														<td> : </td>
														<td> <b>Cash</b>  <b style='color:blue'>(DP)</b></td>
													</tr>
													<tr style='font-size:1.1em;'>
														<td> <b>Tanggal Terima </b> </td>
														<td> : </td>
														<td> <b><?=is_reverse_date($row2->tanggal);?></b> </td>
													</tr>
													<tr style='font-size:1.1em;'>
														<td><b>Nilai</b> </td>
														<td> : </td>
														<td> 
															<b><?=number_format($bayar_dp_amount[$baris->id][$row->id],'0',',','.');?></b>
															<b hidden><?=number_format($row2->amount,'0',',','.');?></b> 
														</td>
													</tr>
													<tr style='font-size:1.1em;'>
														<td> <b>Penerima </b> </td>
														<td> : </td>
														<td> <b><?=is_reverse_date($row2->nama_penerima);?></b> </td>
													</tr>
													<tr>
														<td colspan='3'>
															<hr/>
														</td>
													</tr>
												<?}elseif ($row2->pembayaran_type_id == 3) { ?>
													<tr>
														<td rowspan='5' style='vertical-align:top; font-size:1.2em; padding: 0 10px 0 0'>
															<b><?=$idx?>. </b> 
														</td>
														<td> <b>Tipe </b> </td>
														<td> : </td>
														<td> <b>EDC</b>  <b style='color:blue'>(DP)</b></td>
													</tr>
													<tr style='font-size:1.1em;'>
														<td> <b>Tanggal Bayar </b> </td>
														<td> : </td>
														<td> <b><?=date('d/m/Y', strtotime($row2->tanggal));?></b> </td>
													</tr>
													<tr style='font-size:1.1em;'>
														<td> <b>Bank</b> </td>
														<td> : </td>
														<td> <b><?=$row2->nama_bank;?></b> </td>
													</tr>
													<tr style='font-size:1.1em;'>
														<td> <b>No Rek</b> </td>
														<td> : </td>
														<td> <b><?=$row2->no_rek_bank;?></b> </td>
													</tr>
													<tr style='font-size:1.1em;'>
														<td><b>Nilai</b></td>
														<td> : </td>
														<td> 
															<b><?=number_format($bayar_dp_amount[$baris->id],'0',',','.');?></b>
															<b hidden><?=number_format($row2->amount,'0',',','.');?></b> 
														</td>
													</tr>
													<tr>
														<td colspan='3'>
															<hr/>
														</td>
													</tr>				
												<?}
												}
											}
											$idx++;
										}

										?>
									</table>
								</div>
						</div>
						<?}?>
					</div>
					<div style="border-top: 2px solid #000;padding:10px 0;">
						<a href="<?=base_url().is_setting_link('finance/piutang_list');?>" class='btn btn-lg default'>BACK</a>
					</div>
				</div>
			</div>
		</div>
	</div>			
</div>

<script src="<?php echo base_url('assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/datatables/media/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/bootbox/bootbox.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets_noondev/js/table-advanced.js'); ?>"></script>

<script>
jQuery(document).ready(function() {

	// dataTableTrue();

	// oTable = $('#general_table').DataTable();
	// oTable.state.clear();
	// oTable.destroy();
	// TableAdvanced.init();

	$("#customer_id_select").select2();

	$('.btn-view').click(function(){
		var data_id = $(this).attr('id').split('_');
		var id = data_id[1];
		// alert(id);
		$("#rinci_"+id).toggle('slow');
	});

	<?foreach ($stat as $key => $value) { ?>
		$(".unbalance_"+"<?=$value?>").show();
	<?}?>

	<?if (is_posisi_id() < 4) {?>
		$(".btn-remove").click(function(){
			var pembayaran_piutang_id = $(this).closest('tr').find('.pembayaran_piutang_id').html();
			bootbox.confirm("Yakin menghapus data pembayaran ? <br/>", function(respond){
				if (respond) {
					// alert('error, mohon hubungi admin IT');
					window.location.replace(baseurl+"finance/pembayaran_piutang_remove?pembayaran_piutang_id="+pembayaran_piutang_id);
					
				};
			} );
		});
	<?}?>

});

function removeHutangPaymentFetch(id) {

	// let dialogFetch = bootbox.dialog({
	// 	message: `<p class="text-center mb-0"><i class="fa fa-spin fa-spinner"></i> Please wait...</p>`,
	// 	closeButton: false
	// });

	const dialog = bootbox.confirm(`Yakin untuk menghapus data ? <br/> Data akan <b>dihapus permanen</b> dan tidak bisa di kembalikan `, function(respond){
		if (respond) {
			// dialogFetch.init();
			dialog.find('.bootbox-body').html(`<p class="text-center mb-0"><i class="fa fa-spin fa-spinner"></i> Please wait...</p>`);
			setTimeout(() => {
				removeData();
			}, 500);
		}
	});

	async function removeData(){
		const response = await fetch(baseurl+"finance/pembayaran_piutang_remove_async?id="+id, {
			method: "DELETE",
		});
		const result_data = await response.json();

		if (result_data == "OK") {
			$(`#bayar-${id}`).remove();
			dialog.find('.bootbox-body').html('Data berhasil dihapus!');
			setTimeout(() => {
				dialog.modal('hide');
			}, 1000);
		}else{
			bootbox.alert("Error, mohon hubungi pihak IT");
			dialog.modal('hide');

		}
		console.log(result_data, result_data == "OK");
	}
	
}
</script>
