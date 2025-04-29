<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css'); ?>"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css'); ?>"/>
<?=link_tag('assets/global/plugins/bootstrap-datepicker/css/datepicker3.css'); ?>

<style type="text/css">
.general_table tr td, .general_table tr th {
	text-align: center;
	vertical-align: middle;
}

.nav-tabs li.active a,{
	font-weight:bold !important;
}

#info-trx-selesai{
	display:none;
	font-size:12px;
	background-color:#ddd;
	padding:10px;
}

@media print {
	a[href]:after {
		content: none !important;
	}

	iframe{
		display: none !important;
	}
}

.on-blur{
	color:#eee !important;
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
							<span class="caption-subject theme-font bold uppercase">
								<?=$breadcrumb_small;?>
								<b><?=$tanggal_start.' - '. $tanggal_end;?> </b>
							</span>
						</div>
					</div>
					<div class="portlet-body">
						<form action='' method='get' class='hidden-print'>
							<table>
								<tr>
									<td>Tanggal</td>
									<td class='padding-rl-5'> : </td>
									<td>
										<b>
											<input name='tanggal_start' readonly class='date-picker' style='border:none; border-bottom:1px solid #ddd; width:100px;' value='<?=$tanggal_start;?>'>
											s/d
											<input name='tanggal_end' readonly class='date-picker2 ' style='border:none; border-bottom:1px solid #ddd; width:100px;' value='<?=$tanggal_end;?>'> 
											<button class='btn btn-xs default'><i class='fa fa-search'></i></button>
										</b>
								</tr>
							</table>
							<!-- <hr/> -->
						</form>
						<?
							$qty = 0;
							$roll = 0;
							?>
						<div style='width:100%; overflow:auto'>
							<?

							$background_bulan = ['lightblue','lightgreen', 'coral','khaki','LightPink','Orchid', 'Salmon', 'Tan','Thistle','Grey','Cyan','#34A65F' ];

							$date1 = new DateTime(is_date_formatter($tanggal_start));
							$date2 = new DateTime(is_date_formatter($tanggal_end));
							$date2->modify('first day of next month');
							
							$interval = DateInterval::createFromDateString('1 month');
							$period = new DatePeriod($date1, $interval, $date2);

							?>
									<ul class="nav nav-tabs" style="margin-top:20px">
										<? $idx = 0;
										foreach ($period as $dt) {?>
											<li class="<?=($idx == 0 ? 'active':'');?>">
												<a href="#tab_<?=$dt->format("Y_m")?>" data-toggle="tab" style="border:2px solid <?=$background_bulan[(int)$dt->format('m') - 1];?>">
												<?=$dt->format("F Y")?> </a>
											</li>
										<?$idx++;}?>
									</ul>
									<div class="tab-content" style="padding-top:20px">
										<div style='display:inline-block; width:15px; height:10px;background:#d9e7ff; border:1px solid #ddd'></div> Faktur FP
										<div style='display:inline-block; width:15px; height:10px;background:#fff; border:1px solid #ddd'></div> Faktur
										<div style='display:inline-block; width:15px; height:10px;background:#ffe3ea; border:1px solid #ddd'></div> Faktur Batal
									
									
										<?$idx = 0;foreach ($period as $dt) {?>
											<div class="tab-pane <?=($idx == 0 ? 'active':'');?>" id="tab_<?=$dt->format("Y_m")?>">
												<table class="table table-striped table-bordered table-hover general_table">
													<thead>
														<tr> 
															<!-- <th scope="col">
																Tanggal
															</th> -->
															<th scope="col" style='width:150px !important'>
																No Faktur
															</th>
															<th scope="col">
																Nama Customer
															</th>
															<th scope="col">
																Penjualan
															</th>
															<?foreach ($pembayaran_type as $row) { 
																if ($row->id != 6) {?>
																	<th scope="col">
																		<?=$row->nama;?>
																		<?${"count_".$row->id} = 0; ${"total_".$row->id} = 0;
																		${"countBatal_".$row->id} = 0;?>
																	</th>
																<?}?>
															<?}?>
														</tr>
													</thead>
													<tbody>
														<?
														$grand_total = 0; $idx = 0; $penerimaan_cash = 0;
														$grand_retur = 0; $penerimaan_cash_retur = 0;
														$penerimaan_cash_dp = 0; $idx_batal=0;

														$total_belum_beres = 0;
														$tgl_cast = '';

														foreach ($penjualan_list as $row) { 
															if (date("Y-m", strtotime($row->tanggal)) == $dt->format("Y-m")) {
																if ($tgl_cast != $row->tanggal) {
																$tgl_cast = $row->tanggal;?>
																<tr class='cell-date' style="background:<?=$background_bulan[(int)date('m', strtotime($row->tanggal)) - 1];?>">
																	<td  colspan='8' style="<?=((int)date('m', strtotime($row->tanggal)) == 12 ? 'color:white' : '' )?>" ><?=date('d M Y', strtotime($row->tanggal));?></td>
																</tr>
															<?}?>
															<tr style="<?=($row->fp_status == 1 && $row->status_aktif == 1 ? 'background:#d9e7ff' : ( $row->status_aktif == 1 ? '' : 'background:#ffe3ea; color:#ccc' ))?>" >
																<!-- <td><?=is_reverse_date($row->tanggal);?></td> -->
																<td>
																	<a target="_blank" href="<?=base_url().is_setting_link('transaction/penjualan_list_detail')?>?id=<?=$row->id?>">
																		<?=($row->no_faktur != '' ? $row->no_faktur : '----' );?>
																	</a>
																	
																	<?=(is_posisi_id()==1 ? $row->id : '');?>
																</td>
																<td><?=$row->nama_customer;?></td>
																<td><?=number_format($row->amount,'0',',','.');?></td>
																<?
																if ($row->status_aktif == 1 && $row->no_faktur != '') {
																	$idx++;
																	$grand_total += $row->amount;
																}else if($row->status_aktif == 1){
																	$total_belum_beres++;
																}else{
																	$idx_batal++;
																}
																unset($pembayaran_type_id);
																unset($bayar);
																unset($bayar_id);
																$pembayaran_type_id = explode(',', $row->pembayaran_type_id);
																$bayar = explode(',', $row->bayar);
																$bayar_total = 0;
																foreach ($pembayaran_type_id as $key => $value) {
																	$bayar_id[$value] = $bayar[$key];
																}
																?>
																<?foreach ($pembayaran_type as $row2) {
																	if ($row2->id != 6) {
																		if (isset($bayar_id[$row2->id]) && $row->status_aktif == 1 && $row->no_faktur != '') {
																			$bayar_total += $bayar_id[$row2->id]; 
																		}
																	}
																}

																?> 
																<?foreach ($pembayaran_type as $row2) { 
																	if ($row2->id != 6) {
																		$ket = '';

																		if (is_posisi_id() == 1) {
																			// print_r($row->keterangan_transfer);
																		}
																		if ($row2->id == 4 && $row->keterangan_transfer !='') {
																			$ket = "<br><span style='font-size:0.8em'><b>(".$row->keterangan_transfer.")</b></span>";
																		}
																		?>
																		<td>
																			<? $kembali = '';
																			if ($row->status_aktif == 1 && $row->no_faktur !='') {
																				$kembali = $bayar_total - $row->amount;
																			}
																			if (isset($bayar_id[$row2->id]) && $row->status_aktif == 1 && $row->no_faktur != '') { 
																				if ($row2->id == 2) {
																					if ($bayar_id[$row2->id] != 0) {
																						if ($bayar_id[$row2->id] > 0) {
																							echo number_format($bayar_id[$row2->id] - $row->kembali,'0',',','.');
																						}else if ($bayar_id[$row2->id] < 0) {
																							echo number_format($bayar_id[$row2->id],'0',',','.');
																						}
																						${"total_".$row2->id}+=$bayar_id[$row2->id] - $row->kembali;
																					}elseif ($kembali != 0) {
																						${"total_".$row2->id} -= $row->kembali;
																						echo '-'.number_format($kembali,'0',',','.');
																					}
																				}else{
																					if ($bayar_id[$row2->id] > 0) {
																						echo number_format($bayar_id[$row2->id],'0',',','.');	
																					}
																					${"total_".$row2->id}+=$bayar_id[$row2->id];
																				}
																				if ($bayar_id[$row2->id] > 0) {
																					${"count_".$row2->id}++;
																				}
																				
																			}elseif ($kembali != 0 && $row->no_faktur != '') {
																				if ($row2->id == 2) {
																					echo "<span style='color:red'>(".number_format($kembali * -1,'0',',','.').')</span>';
																					${"total_".$row2->id}-=$kembali;
																				}
																			}elseif (isset($bayar_id[$row2->id]) && $bayar_id[$row2->id] != 0){
																				echo number_format($bayar_id[$row2->id],'0',',','.');
																				${"countBatal_".$row2->id}++;

																			}?>
																			<?=$ket;?>
																		</td>
																	<?}
																}?>
															</tr>
															<?}?>
														<?}?>

														<tr style='font-size:1.1em;font-weight:bold; border-top:2px solid black'>
															<td colspan = '2'>
																Transaksi Selesai <i class='fa fa-info-circle' style='cursor:pointer' onclick="showInfo()"></i> 
																<div id='info-trx-selesai'>
																	Syarat Transaksi Selesai : <br/> 
																	- Status faktur tidak batal <br/>
																	- Sudah memiliki No Faktur
																</div>
															</td>
															<td><?=$idx;?></td>
															<?foreach ($pembayaran_type as $row2) { 
																if ($row2->id != 6) {?>
																	<td>
																		<?=${"count_".$row2->id};
																		?>
																	</td>
																<?}?>
															<?}?>
														</tr>
														<tr style='font-size:1.1em;font-weight:bold'>
															<td colspan = '2'>Total Nilai</td>
															<td><?=number_format($grand_total,'0',',','.');?></td>
															<?foreach ($pembayaran_type as $row2) { 
																if ($row2->id != 6) {
																	if ($row2->id == 2) {
																		$penerimaan_cash += ${"total_".$row2->id};
																	}?>
																	<td>
																		<?=number_format(${"total_".$row2->id},'0',',','.');?>
																	</td>
															<?}}?>
														</tr>

														<tr style='font-size:1.1em;font-weight:bold; border-top:2px solid black'>
															<td colspan = '2'>Transaksi Belum Selesai</td>
															<td><?=$total_belum_beres;?></td>
															<?foreach ($pembayaran_type as $row2) { 
																if ($row2->id != 6) {?>
																	<td>
																		
																	</td>
																<?}?>
															<?}?>
														</tr>

														<tr style='font-size:1.1em;font-weight:bold; border-top:2px solid black'>
															<td colspan = '2'>Total Batal</td>
															<td><?=$idx_batal;?></td>
															<?foreach ($pembayaran_type as $row2) { 
																if ($row2->id != 6) {?>
																	<td>
																		<?=${"countBatal_".$row2->id};
																		?>
																	</td>
																<?}?>
															<?}?>
														</tr>

														<tr style=' border-top:2px solid black'>
															<!-- <th scope="col">
																Tanggal
															</th> -->
															<th scope="col" colspan='2'>
																Keterangan
															</th>
															<th scope="col">
																Penjualan
															</th>
															<?foreach ($pembayaran_type as $row) { 
																if ($row->id != 6) {?>
																	<th scope="col">
																		<?=$row->nama;?>
																		<?${"count_".$row->id} = 0; ${"total_".$row->id} = 0;?>
																	</th>
															<?}}?>
														</tr>
														
													</tbody>
												</table>
												
												<?if (count($dp_list) > 0) {?>
													<hr>
													<div style="font-size:2em;">DP Masuk </div>
													<table class="table table-striped table-bordered table-hover">
														<tr>
															<th>Customer</th>
															<?foreach ($pembayaran_type as $row) { 
																if ($row->id != 1 && $row->id != 5) {?>
																	<th scope="col">
																		<?=$row->nama;?>
																		<?${"count_dp_".$row->id} = 0; ${"total_dp_".$row->id} = 0;?>
																	</th>
																<?}?>
															<?}?>
														</tr>
														<?foreach ($dp_list as $row) {
															$pembayaran_type_id = explode('??', $row->pembayaran_type_id);
															$tipe_idx = array_flip($pembayaran_type_id);
															$amount_dt = explode('??', $row->amount);
															$nama_bank_dt = explode('??', $row->nama_bank);
															$no_rek_bank_dt = explode('??', $row->no_rek_bank);
															$no_giro_dt = explode('??', $row->no_giro);
															$jatuh_tempo_dt = explode('??', $row->jatuh_tempo);
															$urutan_giro_dt = explode('??', $row->urutan_giro);
															$keterangan_dt = explode('??', $row->keterangan);
															$nama_penerima_dt = explode('??', $row->nama_penerima);
															?>
															<tr>
																<td><?=$row->nama_customer;?></td>
																<?foreach ($pembayaran_type as $row2) {
																	if ($row2->id != 1 && $row2->id != 5) {?>
																		<td>
																			<?if (isset($tipe_idx[$row2->id])) {
																				$amount = explode('||', $amount_dt[$tipe_idx[$row2->id]]);
																				$nama_bank = explode('||', $nama_bank_dt[$tipe_idx[$row2->id]]);
																				$no_rek_bank = explode('||', $no_rek_bank_dt[$tipe_idx[$row2->id]]);
																				$jatuh_tempo = explode('||', $jatuh_tempo_dt[$tipe_idx[$row2->id]]);
																				$no_giro = explode('||', $no_giro_dt[$tipe_idx[$row2->id]]);
																				$urutan_giro = explode('||', $urutan_giro_dt[$tipe_idx[$row2->id]]);
																				$keterangan = explode('||', $keterangan_dt[$tipe_idx[$row2->id]]);
																				$nama_penerima = explode('||', $nama_penerima_dt[$tipe_idx[$row2->id]]);
																				// echo number_format($amount[$tipe_idx[$row2->id]],'0',',','.');
																				foreach ($amount as $key => $value) {
																					${"total_dp_".$row2->id} += $value;
																					$info = '';
																					?>
																					<?=number_format($value,'0',',','.');?> 
																					<?if ($row2->id == 2) {
																						$penerimaan_cash_dp += $value;
																						$info .= "<tr><td>Penerima</td> <td>:</td> <td><b>".$nama_penerima[$key]."</b></td></tr>";
																					}
																					if ($row2->id == 6 ) {
																						$info .="<tr><td>No Giro </td> <td>:</td> <td><b> ".$no_giro[$key]."</b></td>";
																						$info .="<tr><td>Jth Tempo </td> <td>:</td> <td><b> ".is_reverse_date($jatuh_tempo[$key])."</b></td></tr>";
																						$info .="<tr><td>Urutan </td> <td>:</td> <td><b> ".$urutan_giro[$key]."</b></td></tr>";
																					}
																					if ($row2->id == 4 || $row2->id == 6 ) {
																						$info .="<tr><td>Bank </td> <td>:</td> <td><b> ".(isset($nama_bank[$key]) ? $nama_bank[$key] : '-')."</b></td></tr>";
																						$info .="<tr><td>No Rek </td> <td>:</td> <td><b> ".(isset($no_rek_bank[$key])?$no_rek_bank[$key]:'-')."</b></td></tr>";
																					}
																					if ($info != '') {
																						$info = "<table>".$info."</table>";
																						?>
																						<a target='_blank' data-toggle="popover" class='hidden-print' data-trigger='hover' title="Info" data-html='true' data-content="<?=$info;?>">
																							<i class='fa fa-info-circle' ></i>
																						</a>
																					<?}
																					?><br/>
																				<?}
																			}?>
																		</td>
																	<?}
																}?>
															</tr>
														<?}?>
														<tr style='font-size:1.1em;font-weight:bold'>
															<th>TOTAL</th>
															<?foreach ($pembayaran_type as $row) { 
																if ($row->id != 1 && $row->id != 5) {?>
																	<th scope="col">
																		<?=number_format(${"total_dp_".$row->id},'0',',','.');?>
																	</th>
																<?}?>
															<?}?>
														</tr>
													</table>
												<?}?>
											</div>
										<?$idx++;}?>
										
									</div>	

							

							<?if (count($retur_list) != 0) { ?>
							<hr/>
								<div style="font-size:2em;">Retur Penjualan </div>
									<table class="table table-striped table-bordered table-hover" id="general_table_2">
										<thead>
											<tr>
												<!-- <th scope="col">
													Tanggal
												</th> -->
												<th scope="col">
													No Retur
												</th>
												<th scope="col">
													No Penjualan
												</th>
												<th scope="col">
													Nama 
												</th>
												<th scope="col">
													Retur
												</th>
												<?foreach ($pembayaran_type as $row) { 
													if ($row->id == 2 || $row->id == 3 || $row->id == 5 ) {?>
														<th scope="col">
															<?if ($row->id == 5) {
																echo "Pengurangan Piutang";
															}else{
																echo $row->nama;
															} 
															${"count_".$row->id} = 0; ${"total_".$row->id} = 0;?>
														</th>
													<?}?>
												<?}?>
											</tr>
										</thead>
										<tbody>
											
											<?
											$idx = 0; 
											foreach ($retur_list as $row) { 
													$idx++;
													$grand_retur += $row->amount;
													unset($pembayaran_type_id);
													unset($retur);
													unset($retur_id);
													$pembayaran_type_id = explode(',', $row->pembayaran_type_id);
													$retur = explode(',', $row->bayar);
													foreach ($pembayaran_type_id as $key => $value) {
														$retur_id[$value] = $retur[$key];
													}
												?>
												<tr style='background:#eee'>
													<td><?=$row->no_faktur;?></td>
													<td><?=$row->no_faktur_penjualan;?></td>
													<td><?=$row->nama_customer;?></td>
													<td><?=number_format($row->amount,'0',',','.');?></td>
													<?foreach ($pembayaran_type as $row2) { 
														$ket = '';
														if ($row2->id == 2 || $row2->id == 3 || $row2->id == 5 ) {?>
														<td>
															<?if (isset($retur_id[$row2->id]) && $retur_id[$row2->id] != 0) { ?>
																<?=number_format($retur_id[$row2->id],'0',',','.');?>
																<?${"count_".$row2->id}++;?>
																<?${"total_".$row2->id}+=$retur_id[$row2->id];?>
															<?}else{echo '-';}?>
														</td>
														<?}
													}?>

												</tr>
											<?}?>
											<tr style='font-size:1.1em; border-top:2px solid black;'>
												<!-- <th scope="col">
													Tanggal
												</th> -->
												<th scope="col" colspan='3' class='text-center'>
													Total Transaksi
												</th>
												<th scope="col">
													<?=number_format($grand_retur,'0',',','.');?>
												</th>
												<?foreach ($pembayaran_type as $row) { 
													if ($row->id == 2 || $row->id == 3 || $row->id == 5 ) {
														if ($row->id == 2) {
															$penerimaan_cash_retur = ${"total_".$row->id};
														}?>
														<th scope="col">
															<?=number_format(${"total_".$row->id},'0',',','.');?>
														</th>
												<?	}
												}?>
											</tr>
										</tbody>
									</table>
								</div>

							<?} ;?>

							<?if (count($retur_list) != 0  || count($dp_list) != 0) {?>
								<table class='table' style='font-size:1.5em'>
									<tr>
										<th class='text-right;' style='width:50%'>TOTAL PENERIMAAN CASH : 
										</th>
										<th>
											<?=number_format($penerimaan_cash - $penerimaan_cash_retur + $penerimaan_cash_dp,'0',',','.');?>
										</th>
									</tr>
								</table>
							<?}?>
					</div>
					<div>
						<button class='btn blue hidden-print' onclick="window.print()"><i class='fa fa-print'></i> Print</button>
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
   	$('[data-toggle="popover"]').popover();
	// const today = new Date();
	// if (today.getMonth() == 11) {
	// 	snowEffect();
	// };
});

function showInfo(){
	$("#info-trx-selesai").toggle();
}

</script>
