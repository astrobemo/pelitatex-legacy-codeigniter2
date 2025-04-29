<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css'); ?>"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css'); ?>"/>
<?=link_tag('assets/global/plugins/bootstrap-datepicker/css/datepicker3.css'); ?>
<link href="<?=base_url('assets/global/plugins/bootstrap-modal/css/bootstrap-modal.css');?>" rel="stylesheet" type="text/css"/>

<?=link_tag('assets/global/plugins/select2/select2.css'); ?>
<link href="<?=base_url('assets_noondev/css/bootstrap-modal-bs3patch.css');?>" rel="stylesheet" type="text/css"/>

<style type="text/css">
#general_table tr th{
	vertical-align: middle;
	text-align: center;
	/*font-size: 0.95em;*/

}

#general_table tr td{
	color:#000;
	/*font-size: 0.8em;*/
	/*font-family: Arial;*/
	/*font-size: 12px;*/
}

#general_table{
	border-bottom: 2px solid #ddd;
}

.nama-barang-column{
	 min-width:200px !important;
}

.ket-lunas{
	padding: 0;
	margin: 0;
}

.ket-lunas li{
	list-style-type: none;
	padding: none;
	margin: none;
}

.pembayaran-info{
	font-weight: bold;
}

.card{
	position: absolute;
	background: white;
	padding: 10px;
	width: 200px;
	right: 80px;
	top: 0;
	border: 1px solid #ddd;
}

@media print {

	* {
		-webkit-print-color-adjust: exact;
		print-color-adjust: exact;
	}

	a[href]:after {
	    content: none !important;
	}

	.nama-barang-column{
		 width:200px !important;
	}

	table{
		font-size:11px;
	}

	table tr td, table tr th{
		padding:2px;
	}

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
						<div class="actions">
							
						</div>
					</div>
					<div class="portlet-body">
						<table width='100%' class='hidden-print'>
							<tr>
								<td>
									<form id='form-filter' action='' method='get'>
										<table>
											<tr>
												<td>Tanggal</td>
												<td>:</td>
												<td>
													<b>
														<input name='tanggal_start' readonly class='date-picker' style='border:none; border-bottom:1px solid #ddd; width:100px;' value='<?=$tanggal_start;?>'>
														s/d
														<input name='tanggal_end' readonly class='date-picker2 ' style='border:none; border-bottom:1px solid #ddd; width:100px;' value='<?=$tanggal_end;?>'> 
													</b>
												</td>
											</tr>
											<tr>
												<td>Tipe Trx </td>
												<td>: </td>
												<td>
													<b>
														<select name='penjualan_type_id'>
															<option <?=($penjualan_type_id == '' ? "selected" : "");?> value='0'>Semua</option>
															<option <?=($penjualan_type_id == 1 ? "selected" : "");?> value='1'>Cash Pelanggan</option>
															<option <?=($penjualan_type_id == 2 ? "selected" : "");?> value='2'>Kredit Pelanggan</option>
															<option <?=($penjualan_type_id == 3 ? "selected" : "");?> value='3' >Cash / Non Pelanggan</option>
														</select>
													</b>
												</td>
											</tr>
											<tr>
												<td>Tipe </td>
												<td>: </td>
												<td>
													<b>
														<select name='tipe_search'>
															<option <?=($tipe_search == 1 ? "selected" : "");?> value='1'>Semua</option>
															<option <?=($tipe_search == 2 ? "selected" : "");?> value='2'>Lunas (Cash)</option>
															<option <?=($tipe_search == 3 ? "selected" : "");?> value='3' >Lunas (Kredit)</option>
															<option <?=($tipe_search == 4 ? "selected" : "");?> value='4'>Belum Lunas</option>
															<option <?=($tipe_search == 5 ? "selected" : "");?> value='5'>Faktur Pajak</option>
															<option <?=($tipe_search == 6 ? "selected" : "");?> value='6'>Non Faktur Pajak</option>
														</select>
													</b>
												</td>
											</tr>
											<tr>
												<td>Customer</td>
												<td> : </td>
												<td>
													<b>
														<select name='customer_id' id="select_customer" style='width:100%'>
															<option <?=($customer_id == 0 ? "selected" : "");?> value='0'>Semua</option>
															<?foreach ($this->customer_list_aktif as $row) { ?>
																<option <?=($customer_id == $row->id ? "selected" : "");?> value="<?=$row->id?>"><?=$row->nama;?> (<?=substr($row->alamat,0,8);?>..)</option>
															<?}?>
														</select>
													</b>
												</td>
											</tr>
											<tr>
												<td>Gudang</td>
												<td> : </td>
												<td>
													<b>
														<select name='gudang_id' style='width:100%'>
															<option <?=($gudang_id == 0 ? "selected" : "");?> value='0'>Semua</option>
															<?foreach ($this->gudang_list_aktif as $row) { ?>
																<option <?=($gudang_id == $row->id ? "selected" : "");?> value="<?=$row->id?>"><?=$row->nama;?></option>
															<?}?>
														</select>
													</b>
												</td>
											</tr>
											<tr>
												<td>Barang</td>
												<td class='padding-rl-5'> : </td>
												<td>
													<select name='barang_id' id='barang_id_select' style="width:200px;">
														<option <?=($barang_id == 0 ? "selected" : "");?> value='0'>Semua</option>
														<?foreach ($this->barang_list_aktif as $row) { 
															if ($row->status_aktif == 1) {?>
																<option <?=($barang_id == $row->id ? "selected" : "");?> value='<?=$row->id;?>'><?=$row->nama_jual;?></option>
															<?}?>
														<?}?>
													</select>
												</td>
											</tr>
											<tr>
												<td>Warna</td>
												<td class='padding-rl-5'> : </td>
												<td>
													<select name='warna_id' id="warna_id_select" style="width:200px;">
														<option <?=($warna_id == 0 ? "selected" : "");?> value='0'>Semua</option>
														<?foreach ($this->warna_list_aktif as $row) { 
															if ($row->status_aktif == 1) {?>
																<option <?=($warna_id == $row->id ? "selected" : "");?> value='<?=$row->id;?>'><?=$row->warna_jual;?></option>
															<?}?>
														<?}?>
													</select>
												</td>
											</tr>
											<tr hidden>
												<td>View Type</td>
												<td> : </td>
												<td>
													<b>
														<select name='view_type' id='view-type' style='width:100%'>
															<option <?=($view_type == 0 ? "selected" : "");?> value='0'>Normal</option>
															<option <?=($view_type == 1 ? "selected" : "");?> value='1'>Print</option>
														</select>
													</b>
												</td>
											</tr>
											<tr>
												<td></td>
												<td></td>
												<td>
													<button class='btn btn-xs default' style='width:100%'><i class='fa fa-search'></i></button>
												</td>
											</tr>
										</table>
									</form>
								</td>
								<td class='text-right'>
									<form action='<?=base_url();?>report/penjualan_list_export_excel' method='get'>
										<input name='tanggal_start' value='<?=$tanggal_start;?>' hidden>
										<input name='tanggal_end' value='<?=$tanggal_end;?>' hidden>
										<input name='tipe_search' value='<?=$tipe_search;?>' hidden>
										<input name='customer_id' value='<?=$customer_id;?>' hidden>
										<input name='barang_id' value='<?=$barang_id;?>' hidden>
										<input name='warna_id' value='<?=$warna_id;?>' hidden>
										<input name='gudang_id' value='<?=$gudang_id;?>' hidden>
										<input name='penjualan_type_id' value='<?=$penjualan_type_id;?>' hidden>

										<button <?=(count($penjualan_list) == 0 && count($retur_list) == 0 ? "disabled" : "");?> class='hidden-print btn green'><i class='fa fa-download'></i> Excel</button>
									</form>

									<form action='<?=base_url();?>report/penjualan_list_export_excel_split_month' method='get'>
										<input name='tanggal_start' value='<?=$tanggal_start;?>' hidden>
										<input name='tanggal_end' value='<?=$tanggal_end;?>' hidden>
										<input name='tipe_search' value='<?=$tipe_search;?>' hidden>
										<input name='customer_id' value='<?=$customer_id;?>' hidden>
										<input name='barang_id' value='<?=$barang_id;?>' hidden>
										<input name='warna_id' value='<?=$warna_id;?>' hidden>
										<input name='gudang_id' value='<?=$gudang_id;?>' hidden>
										<input name='penjualan_type_id' value='<?=$penjualan_type_id;?>' hidden>

										<button <?=(count($penjualan_list) == 0 && count($retur_list) == 0 ? "disabled" : "");?> class='hidden-print btn yellow-gold'><i class='fa fa-download'></i> Excel<small style='font-size:10px'> (split month)</small></button>
									</form>

									<form action='<?=base_url();?>report/report_penjualan_dpp_excel' method='get'>
										<input name='tanggal_start' value='<?=$tanggal_start;?>' hidden>
										<input name='tanggal_end' value='<?=$tanggal_end;?>' hidden>
										<input name='customer_id' value='<?=$customer_id;?>' hidden>
										
										<button <?=($gudang_id == 0 && $barang_id == 0  && $warna_id == 0 ? "" : "disabled");?> class='hidden-print btn blue'><i class='fa fa-download'></i> Excel Nilai DPP + PPN<small style='font-size:10px'> </small></button>
									</form>
									<?/* if ($view_type == 0) {?>
										<button <?=(count($penjualan_list) == 0 && count($retur_list) == 0 ? "disabled" : "");?> class='hidden-print btn blue btn-print'><i class='fa fa-print'></i> PRINT</button>
									<?}else{?>
										<button <?=(count($penjualan_list) == 0 && count($retur_list) == 0 ? "disabled" : "");?> class='hidden-print btn blue btn-normal'><i class='fa fa-eye'></i> Normal</button>
									<?} */?>
								</td>
							</tr>
						</table>
									
						<hr class='hidden-print'/>
						<!-- table-striped table-bordered  -->
						<table class="table table-bordered table-hover table-striped " id="general_table">
							<thead>
								<tr style='background:#eee' >
									<th scope="col" style='width:90px !important;'>
										No Faktur
									</th>
									<th scope="col">
										Tanggal<br/> Penjualan
									</th>
									<th scope="col" style='min-width:90px !important'>
										Yard/KG
									</th>
									<th scope="col">
										Roll
									</th>
									<th scope="col" class='hidden-print' >
										Gudang
									</th>
									<th scope="col" class='nama-barang-column'>
										Nama Barang
									</th>
									<?if ($view_type != 2) {?>
										<th scope="col">
											Harga
										</th>
										<th scope="col">
											Total
										</th>
									<?}?>
									<th scope="col">
										Nama <br/> Customer
									</th>
									<?if ($view_type != 2) {?>
										<th scope="col">
											Keterangan
										</th>
										<?
										if (is_posisi_id() > 3 ||  is_posisi_id() == 1) {
											foreach ($tipe_bayar as $row2) { 
												$total_tipe_bayar[$row2->id] = 0;
												?>
												<th scope="col">
													<?=$row2->nama;?>
												</th>
											<?}
										}
									}?>
									<!-- <th scope="col">
										Jatuh Tempo
									</th> -->
								</tr>
							</thead>
							<tbody>
								<?
								foreach ($this->satuan_list_aktif as $row) {
									${'g_total_'.$row->id} = 0;
									${'idx_'.$row->id} = 0;
									${'yard_total_'.$row->id} = 0;
									${'roll_total_'.$row->id} = 0;
									${'g_returtotal_'.$row->id} = 0;
									${'yard_returtotal_'.$row->id} = 0;
									${'roll_returtotal_'.$row->id} = 0;
									$nama_sat[$row->id] = $row->nama;
								}
								$idx_total = 0; $g_total = 0;
								$yard_total = 0; $roll_total = 0;
								$idx_retur_total = 0; $g_retur_total = 0;
								$yard_retur_total = 0; $roll_retur_total = 0;
								$total_lunas = 0;
								$total_kontra = 0;
								$total_belum_lunas = 0;

								$count_lunas = 0;
								$count_kontra = 0;
								$count_belum_lunas = 0;
									
								foreach ($penjualan_list as $row) { 
									unset($yrd_sub);
									$roll_sub = 0;
									$penjualan_id = $row->id;
										$qty = ''; $jumlah_roll = ''; $nama_barang = ''; $harga_jual = '';
										if ($row->qty != '') {
											$qty = explode('??', $row->qty);
											$jumlah_roll = explode('??', $row->jumlah_roll);
											$nama_barang = explode('??', $row->nama_barang);
											$harga_jual = explode('??', $row->harga_jual);
											$nama_gudang = explode('??', $row->nama_gudang);
											$satuan_id = explode('??', $row->satuan_id);
											$nama_satuan = explode('??', $row->nama_satuan);
											$pembayaran_piutang_id = explode(',', $row->pembayaran_piutang_id);
											$ket_lunas = explode(',', $row->ket_lunas);

											// $bg_color = (isset($color[$pembayaran_piutang_id[0]]) ? "background-color:#".$color[$pembayaran_piutang_id[0]] : '' );
											$bg_color = (isset($color[$pembayaran_piutang_id[0]]) ? "background-color:rgb(".$color[$pembayaran_piutang_id[0]].")" : '' );
										}
										
									?>
									<tr class='text-center' style="<?=$bg_color;?>" >
										<td>
											<a style='color:#0d0033' href="<?=base_url().is_setting_link('transaction/penjualan_list_detail')?>/?id=<?=$row->id;?>" target='_blank'>
												<?=($row->no_faktur != '' ? $row->no_faktur : '??' );?>
											</a>
											
										</td>
										<td>
											<?if($view_type == 2){
												echo $row->closed_date;
											}else{
												echo is_reverse_date($row->tanggal);
											}
											?>
											<?
											if (is_posisi_id() == 1) {
												//echo '<hr/>';
												//print_r($row->pembayaran_piutang_id);
											}?>
										</td>
										<td>
											<?
											if ($qty != '') {
												$baris = count($qty);
												$j = 1; $idx = 1;
												foreach ($qty as $key => $value) {
													echo str_replace(',00', '', number_format($value,'2',',','.')).' '.$nama_satuan[$key].'<br/>';
													if ($j % 3 == 0 && $baris != $idx) {
														echo "<hr style='margin:2px'/>";
														$j = 1;
													}else{													
														$j++;
													}
													$idx++;
													$yard_total += $value;
													$yrd_sub[$satuan_id[$key]] = (isset($yrd_sub[$satuan_id[$key]]) ? $yrd_sub[$satuan_id[$key]]+ $value : $value);
													if (isset(${'yard_total_'.$satuan_id[$key]})) {
														${'yard_total_'.$satuan_id[$key]} += $value;
														${'roll_total_'.$satuan_id[$key]} += $jumlah_roll[$key];
													}
												}

												if ($view_type !=2) {
													foreach ($yrd_sub as $key => $value) {
														echo "<b>".str_replace(',00','', number_format($value,'2',',','.'))." ".$nama_sat[$key]."</b><br/>";
													}
												}
											}
											?>
										</td>
										<td>
											<?
											if ($jumlah_roll != '') {
												$j = 1; $idx = 1;
												foreach ($jumlah_roll as $key => $value) {
													echo str_replace(',00', '', number_format($value,'2',',','.')).'<br/>';
													if ($j % 3 == 0 && $baris != $idx) {
														echo "<hr style='margin:2px'/>";
														// echo '---<br/>';
														$j = 1;
													}else{										
														$j++;
													}
													$idx++;
													$roll_total += $value;
													$roll_sub += $value;
												}
												echo "<b>".$roll_sub."</b>";

											}

											?>
										</td>
										<td class='hidden-print'>
											<?
											if ($nama_barang != '') {
												$j = 1; $idx = 1;
												foreach ($nama_gudang as $key => $value) {
													echo "<span class='gudang'>".$value."</span>".'<br/>';
													if ($j % 3 == 0 && $baris != $idx) {
														echo "<hr style='margin:2px'/>";
														// echo '---<br/>';
														$j = 1;
													}else{													
														$j++;
													}
													$idx++;
												}
											}
											?>
										</td>
										<td>
											<span class='id' hidden="hidden"><?=$row->id;?></span>
											<?
											if ($nama_barang != '') {
												$j = 1; $idx = 1;
												foreach ($nama_barang as $key => $value) {
													echo "<span class='nama'>".$value."</span>".'<br/>';
													if ($j % 3 == 0 && $baris != $idx) {
														echo "<hr style='margin:2px'/>";
														// echo '---<br/>';
														$j = 1;
													}else{													
														$j++;
													}
													$idx++;
												}
											}
											?>
											<!-- <span class='nama'><?=str_replace('??', '<br/>', $row->nama_barang);?></span><br/> -->
											
										</td>
										<?if ($view_type !=2) {?>
											<td>
												<?
												if ($harga_jual != '') {
													$j = 1; $idx = 1;
													foreach ($harga_jual as $key => $value) {
														echo str_replace(',00', '', number_format($value,'2',',','.')).'<br/>';
														if ($j % 3 == 0 && $baris != $idx) {
															echo "<hr style='margin:2px'/>";
															// echo '---<br/>';
															$j = 1;
														}else{													
															$j++;
														}
														$idx++;
													}
												}?>
												<b>Subtotal:</b>
											</td>
											<td>
												<?
												$subtotal = 0; 
												if ($harga_jual != '') {
													$j = 1; $idx = 1;
													foreach ($harga_jual as $key => $value) {
														echo number_format($qty[$key] * $value,'0',',','.').'<br/>';
														$subtotal +=$qty[$key] * $value; 
														$g_total += $qty[$key] * $value;
														if (isset(${'g_total_'.$satuan_id[$key]})) {
															${'g_total_'.$satuan_id[$key]} += $qty[$key] * $value;
														}
	
														if ($j % 3 == 0 && $baris != $idx) {
															echo "<hr style='margin:2px'/>";
															// echo '---<br/>';
															$j = 1;
														}else{													
															$j++;
														}
														$idx++;
													}
												}
												
												?>
												<b><?=number_format($subtotal,'0',',','.')?></b>
											</td>
										<?}?>
										<td>
											<?=$row->nama_customer;?> 
										<?if (is_posisi_id()==1) {
											unset($pembayaran_type_id); unset($data_bayar);
											$pembayaran_type_id = explode(',', $row->pembayaran_type_id);
											$data_bayar = explode(',', $row->data_bayar);
											$bayar = array_combine($pembayaran_type_id, $data_bayar);
										}?>
										<?if ($view_type != 2) {?>
											</td>
											<td>
												<div style="position:relative">
													<?if ($row->ket_lunas == 'belum lunas') {
														$total_belum_lunas += $subtotal;
														$count_belum_lunas++;
														?>
														<span style='color:red'>belum lunas</span>
														<br/><a href="<?=base_url('finance/set_piutang_form_by_customer')?>?customer_id=<?=$row->customer_id?>" class='btn btn-xs green'>lunasi</a>
													<?}elseif ($row->ket_lunas == 'lunas1') {
														$total_lunas += $subtotal;
														$count_lunas++;
														if ($row->amount_kontra_tanggung != '' && $row->amount_kontra_tanggung != 0) {
															foreach ($pembayaran_piutang_id as $key => $value) {
																if (is_posisi_id() > 3) {?>
																	<a target="_blank" class="btn btn-xs green" href="<?=base_url().is_setting_link('finance/piutang_payment_form')?>?id=<?=$pembayaran_piutang_id[$key]?>"><i class='fa fa-search'></i> Lunas</a>
																<?}elseif($row->keterangan < 0){?>
																<?=(is_posisi_id() == 1 ? $row->amount_kontra_tanggung.'yaaaa': '' );?>
																	<button class="btn btn-xs yellow-gold btn-view-card" ><i class='fa fa-eye'></i> Kontra Bon</button>
																<?}else{?>
																	<button class="btn btn-xs green btn-view-card" ><i class='fa fa-eye'></i> Lunas</button>
																<?}?>
																
															<?}
															?>
														<?}else{?>
															<span style='color:green'>lunas</span>
														<?}?>
													<?}elseif($row->ket_lunas == 'kontra'){?>
														<a href="<?=base_url().is_setting_link('finance/piutang_payment_form');?>?id=<?=$row->pembayaran_piutang_id?>" target="_blank" class="btn btn-xs yellow-gold" ><i class='fa fa-eye'></i> Kontra</a>
													<?}else{
														foreach ($ket_lunas as $key => $value) {
															if ($value == 'lunas') {
																$total_lunas += $subtotal;
																$count_lunas++;
															}else{
																$total_kontra += $subtotal;
																$count_kontra++;
															}
															if (is_posisi_id() > 3) {?>
																<a target="_blank" class="btn btn-xs green" href="<?=base_url().is_setting_link('finance/piutang_payment_form')?>?id=<?=$pembayaran_piutang_id[$key]?>"><i class='fa fa-search'></i> Lunas</a>
															<?}else{?>	
																<button class="btn btn-xs green btn-view-card" ><i class='fa fa-eye'></i> Lunas</button>
															<?}?>
															
															<br/>
														<?}
													}
													if ($row->pembayaran_piutang_id != '') {
														$bayar_id = explode(","	, $row->pembayaran_piutang_id);
														foreach ($bayar_id as $key => $value) {?>
															<a href="<?=base_url().is_setting_link('finance/piutang_payment_form')?>?id=<?=$value?>" target='_blank' class="btn btn-xs blue" ><i class='fa fa-eye'></i> Form <br/>Pelunasan <?=(count($bayar_id ) == 1? '' : $key + 1)?></a>
														<?}
														?>
													<?}?>
													

													<?if (is_posisi_id() <= 3 || is_posisi_id()==1) {

														$pembayaran_type_id[1] = "TRANSFER";
														$pembayaran_type_id[2] = "GIRO";
														$pembayaran_type_id[3] = "CASH";
														$pembayaran_type_id[4] = "EDC";
														$pembayaran_type_id[5] = "DP";


														?>
														<ul class="ket-lunas hidden-print">
															<?if (isset($pelunasan_piutang_nilai[$row->id])) {
																$idx = 1;
																$ket_show = "";
																// echo $row->pembayaran_piutang_id;
																foreach ($pelunasan_piutang_nilai[$row->id] as $key => $value) {
																	$giro_count[$penjualan_id] = (isset($giro_count[$penjualan_id]) ? $giro_count[$penjualan_id] : 0  );
																	$giro_total[$penjualan_id] = (isset($giro_total[$penjualan_id]) ? $giro_total[$penjualan_id] : 0 );
																	if ($value->pembayaran_type_id == 2 ) {
																		$giro_dt[$value->id] = $value;
																		$giro_total[$penjualan_id] += $value->amount;
																		$giro_count[$penjualan_id]++;
																		?>
																		<li><b><?=$idx;?> GIRO</b></li>
																		<li><b><?=number_format($value->amount,'0',',','.');?></b></li>
																		<?
																	}else{
																		$tanggal_dp = '';
																		if ($value->tanggal_dp != '') {
																			$tanggal_dp = is_reverse_date($value->tanggal_dp);
																		}?>
																		<li>
																			<b>
																				<?=$idx;?> <?=$pembayaran_type_id[$value->pembayaran_type_id];?>  
																				<?=($value->pembayaran_type_id == 5 ? "(".$tanggal_dp.")" : '');?> 
																				<?//="(".substr($value->username, 0,1).")";?>
																			</b>
																		</li>
																		<li><b><?=is_reverse_date($value->tanggal_transfer);?></b></li>
																		<li><b><?=number_format($value->amount,'0',',','.');?></b></li>
																		<li>------------------</li>
																		
																	<?}
																	?>
																	<?$idx++;}
																	if (is_posisi_id() == 1) {
																		if ($giro_count[$penjualan_id] > 0) {?>
																			<b><?=$giro_count[$penjualan_id];?> Giro</b><br>
																			<b><?=number_format($giro_total[$penjualan_id],'0',',','.');?><br/>
																		<?}
																		?>
																	<?}
																}?>
														</ul>

														<ul class="ket-lunas hidden-print">
															<?foreach ($tipe_bayar as $row2) { 
																if ($row2->id != 5) {
																	if (isset($bayar[$row2->id])) {
																		$bayar_dt = explode('??', $bayar[$row2->id]);
																		$sub = 0;
																		foreach ($bayar_dt as $key => $value) {
																			$sub+=$value;?>
																			<li style='border-top: 1px solid #ddd;'><b><?=$row2->nama;?></b></li>
																			<li>[<b><?=is_reverse_date($row->tanggal);?></b>]</li>
																			<li><b><?=number_format($value,'0',',','.');?></b></li>
																		<?}
																	}
																}
															}?>
														</ul>
													<?}?>
													<?if (isset($pelunasan_piutang_nilai[$row->id])) {
															foreach ($pelunasan_piutang_nilai[$row->id] as $key => $value) {
																	if ($value->pembayaran_type_id == 2 ) {?>
																		<div class='card' hidden>
																			<table class='text-left'>
																				<tr>
																					<td>No Giro</td>
																					<td> : </td>
																					<td class='pembayaran-info'><?=$value->no_giro;?></td>
																				</tr>
																				<tr>
																					<td>Bank</td>
																					<td> : </td>
																					<td class='pembayaran-info'><?=$value->nama_bank;?></td>
																				</tr>
																				<tr>
																					<td>No Rek</td>
																					<td> : </td>
																					<td class='pembayaran-info'><?=$value->no_rek_bank;?></td>
																				</tr>																		
																				<tr>
																					<td>Jth Tempo</td>
																					<td> : </td>
																					<td class='pembayaran-info'><?=is_reverse_date($value->jatuh_tempo);?></td>
																				</tr>
																				<tr>
																					<td>Amount</td>
																					<td> : </td>
																					<td class='pembayaran-info'><?=number_format($value->amount,'0',',','.');?></td>
																				</tr>
																			</table>
																		</div>
																	<?}
															}
													}?>


												</div>
											</td>
											<?
											if (is_posisi_id() > 3 || is_posisi_id() == 1) {
												$total_except_cash = 0;
												unset($pembayaran_type_id); unset($data_bayar);
												$pembayaran_type_id = explode(',', $row->pembayaran_type_id);
												$data_bayar = explode(',', $row->data_bayar);
												$bayar = array_combine($pembayaran_type_id, $data_bayar);
												foreach ($bayar as $key => $value) {
													if ($key != 2 && $key != 5 && $key != 6) {
														$total_except_cash += $value;
													}
												}
												foreach ($tipe_bayar as $row2) { 
													?>
													<td>
														<?
														$sub = 0;
														if (isset($bayar[$row2->id])) {
															$bayar_dt = explode('??', $bayar[$row2->id]);
															
															foreach ($bayar_dt as $key => $value) {
																$temp = $total_except_cash - $subtotal;
																if ($row2->id == 2 && $temp > 0) {
																	$value -= $temp;
																}
																if ($row2->id==2 &&  $value > $subtotal) {
																	$value = $subtotal;
																}
																$total_tipe_bayar[$row2->id] += $value;
																$sub+=$value;
																echo number_format($value,'0',',','.')."<br/>";
															}
															if (count($bayar_dt > 2)) {?>
																<hr/ style='margin:0px; padding:0 2px'>
																<b><?=number_format($sub,'0',',','.')?></b>
															<?}
														}?>
														<?if ($row2->id == 2 && !isset($bayar[2]) && $total_except_cash != $subtotal && $total_except_cash != 0 ) {
															$temp = $subtotal - $total_except_cash;
															$sub += $temp;
															$total_tipe_bayar[$row2->id] += $temp;
															// $total_tipe_bayar[$row2->id] += $value;page
															echo number_format($temp,'0',',','.')."<br/>";
														}?>
													</td>
												<?}
											}
										}?>
										<!-- <td>
											<?=is_reverse_date($row->jatuh_tempo);?>
										</td> -->
									</tr>
								<? $idx_total++;} ?>
							</tbody>
						</table>

						<hr/>

						<?if (count($retur_list) > 0) {?>
							<table class="table table-bordered table-hover table-striped " id="general_table-1">
								<thead>
									<tr style='background:#eee' >
										<th scope="col" style='width:90px !important;'>
											No Faktur
										</th>
										<th scope="col">
											Tanggal<br/> Retur
										</th>
										<th scope="col" style='min-width:90px !important'>
											Yard/KG
										</th>
										<th scope="col">
											Roll
										</th>
										<th scope="col">
											Gudang
										</th>
										<th scope="col" style='min-width:300px !important'>
											Nama Barang
										</th>
										<th scope="col">
											Harga
										</th>
										<th scope="col">
											Total
										</th>
										<th scope="col">
											Nama <br/> Customer
										</th>
										<th scope="col" hidden>
											Keterangan
										</th>
										<?
										// if (is_posisi_id() > 4) {
											foreach ($tipe_bayar as $row) { 
												$total_retur_bayar[$row->id] = 0;

												if ($row->id == 1) { ?>
													
												<?}elseif ($row->id == 4) { ?>
													<th><?=$row->nama;?></th>
												<?}elseif ($row->id == 5) { ?>
													<th>Pemotongan Piutang</th>
												<?}elseif ($row->id == 6) { ?>
													
												<?}elseif ($row->id != 3){?>
													<th><?=$row->nama;?></th>
												<?}?>
											<?}
										//}
										?>
										<!-- <th scope="col">
											Jatuh Tempo
										</th> -->
									</tr>
								</thead>
								<tbody>
									<?
									foreach ($retur_list as $row) { 
										$yrd_sub = 0;
										$roll_sub = 0;
											$qty = ''; $jumlah_roll = ''; $nama_barang = ''; $harga_jual = '';
											if ($row->qty != '') {
												$qty = explode('??', $row->qty);
												$jumlah_roll = explode('??', $row->jumlah_roll);
												$nama_barang = explode('??', $row->nama_jual);
												$harga_jual = explode('??', $row->harga_jual);
												$nama_gudang = explode('??', $row->nama_gudang);
												$satuan_id = explode('??', $row->satuan_id);
												$pembayaran_piutang_id = explode(',', $row->pembayaran_piutang_id);
											}
											
										?>
										<tr class='text-center' >
											<td>
												<a style='color:#0d0033' href="<?=base_url().is_setting_link('transaction/penjualan_list_detail')?>/?id=<?=$row->id;?>" target='_blank'><?=($row->no_faktur != '' ? $row->no_faktur : '??' );?></a>
											</td>
											<td>
												<?=is_reverse_date($row->tanggal);?>
											</td>
											<td>
												<?
												if ($qty != '') {
													$baris = count($qty);
													$j = 1; $idx = 1;
													foreach ($qty as $key => $value) {
														echo str_replace(',00', '', number_format($value,'2',',','.')).' '.$row->nama_satuan.'<br/>';
														if ($j % 3 == 0 && $baris != $idx) {
															echo "<hr style='margin:2px'/>";
															$j = 1;
														}else{													
															$j++;
														}
														$idx++;
														$yard_retur_total += $value;
														$yrd_sub += $value;
														// if (is_posisi_id() == 1) {
															${'yard_returtotal_'.$satuan_id[$key]} += $value;
														// }
													}
													echo "<b>".$yrd_sub."</b>";
												}
												?>
											</td>
											<td>
												<?
												if ($jumlah_roll != '') {
													$j = 1; $idx = 1;
													foreach ($jumlah_roll as $key => $value) {
														echo str_replace(',00', '', number_format($value,'2',',','.')).'<br/>';
														if ($j % 3 == 0 && $baris != $idx) {
															echo "<hr style='margin:2px'/>";
															// echo '---<br/>';
															$j = 1;
														}else{										
															$j++;
														}
														$idx++;
														$roll_retur_total += $value;
														// if (is_posisi_id() == 1) {
															${'roll_returtotal_'.$satuan_id[$key]} += $value;
														// }
														$roll_sub += $value;
													}
													echo "<b>".$roll_sub."</b>";

												}

												?>
											</td>
											<td>
												<?
												if ($nama_barang != '') {
													$j = 1; $idx = 1;
													foreach ($nama_gudang as $key => $value) {
														echo "<span class='gudang'>".$value."</span>".'<br/>';
														if ($j % 3 == 0 && $baris != $idx) {
															echo "<hr style='margin:2px'/>";
															// echo '---<br/>';
															$j = 1;
														}else{													
															$j++;
														}
														$idx++;
													}
												}
												?>
											</td>
											<td>
												<span class='id' hidden="hidden"><?=$row->id;?></span>
												<?
												if ($nama_barang != '') {
													$j = 1; $idx = 1;
													foreach ($nama_barang as $key => $value) {
														echo "<span class='nama'>".$value."</span>".'<br/>';
														if ($j % 3 == 0 && $baris != $idx) {
															echo "<hr style='margin:2px'/>";
															// echo '---<br/>';
															$j = 1;
														}else{													
															$j++;
														}
														$idx++;
													}
												}
												?>
												<!-- <span class='nama'><?=str_replace('??', '<br/>', $row->nama_barang);?></span><br/> -->
												
											</td>
											<td>
												<?
												if ($harga_jual != '') {
													$j = 1; $idx = 1;
													foreach ($harga_jual as $key => $value) {
														echo str_replace(',00', '', number_format($value,'2',',','.')).'<br/>';
														if ($j % 3 == 0 && $baris != $idx) {
															echo "<hr style='margin:2px'/>";
															// echo '---<br/>';
															$j = 1;
														}else{													
															$j++;
														}
														$idx++;
													}
												}?>
												<b>Subtotal:</b>
											</td>
											<td>
												
												<?
												$subtotal = 0; 
												if ($harga_jual != '') {
													$j = 1; $idx = 1;
													foreach ($harga_jual as $key => $value) {
														echo number_format($qty[$key] * $value,'0',',','.').'<br/>';
														$subtotal +=$qty[$key] * $value; 
														$g_retur_total += $qty[$key] * $value;
														${'g_returtotal_'.$satuan_id[$key]} += $qty[$key] * $value;

														if ($j % 3 == 0 && $baris != $idx) {
															echo "<hr style='margin:2px'/>";
															// echo '---<br/>';
															$j = 1;
														}else{													
															$j++;
														}
														$idx++;
													}
												}
												
												?>
												<b><?=number_format($subtotal,'0',',','.')?></b>
											</td>
											<td>
												<?=$row->nama_customer;?> 
											</td>
											<td hidden>
												<?if ($row->keterangan < 0) { ?>
													<span style='color:red'>belum lunas</span>
												<?}else if ($row->keterangan >= 0){?>
													<span style='color:green'>lunas</span>
												<?}?>
												<br/>
												<?if (is_posisi_id() < 3) {
													foreach ($pembayaran_piutang_id as $key => $value) {
														if ($value != '') {?>
															<a target="_blank" href="<?=base_url().is_setting_link('finance/piutang_payment_form')?>?id=<?=$value?>"><i class='fa fa-search'></i> Bayar <?=$key+1;?></a><br/>
														<?}
													}
												}?>
											</td>
											<?
											// if (is_posisi_id() > 3) {
												unset($pembayaran_type_id); unset($data_bayar);
												$pembayaran_type_id = explode(',', $row->pembayaran_type_id);
												$data_bayar = explode(',', $row->data_bayar);
												$bayar = array_combine($pembayaran_type_id, $data_bayar);

												foreach ($tipe_bayar as $row2) { 
													if ($row2->id == 1) { ?>
													
													<?}elseif ($row2->id == 4) { ?>
														<td>
															<?if (isset($bayar[$row2->id])) {
																$total_retur_bayar[$row2->id] += $bayar[$row2->id];
																echo number_format($bayar[$row2->id],'0',',','.');
															}?>
														</td>
													<?}elseif ($row2->id == 5) { ?>
														<td>
															<?if (isset($bayar[$row2->id])) {
																$total_retur_bayar[$row2->id] += $bayar[$row2->id];
																echo number_format($bayar[$row2->id],'0',',','.');
															}?>
														</td>
													<?}elseif ($row2->id == 6) { ?>
														
													<?}elseif ($row2->id != 3){?>
														<td>
															<?if (isset($bayar[$row2->id]) && isset($total_retur_bayar[$row2->id])) {
																$total_retur_bayar[$row2->id] += $bayar[$row2->id];
																echo number_format($bayar[$row2->id],'0',',','.');
															}?>
														</td>
													<?}?>
													
												<?}
											//}
											?>
											<!-- <td>
												<?=is_reverse_date($row->jatuh_tempo);?>
											</td> -->
										</tr>
									<? $idx_retur_total++;} ?>
								</tbody>
							</table>
							<hr/>
						<?}?>


						<table class='table table-bordered table-hover table-striped hidden-print '>
							<tr>
								<th>Tipe</th>
								<th>Transaksi</th>
								<th>Yard</th>
								<th>Roll</th>
								<th>Total</th>
								<?
								if (is_posisi_id() > 3 || is_posisi_id() == 1) {
									foreach ($tipe_bayar as $row2) { ?>
										<th>
											<?=$row2->nama;?>
										</td>
									<?}
								}
								?>

							</tr>
							<?foreach ($this->satuan_list_aktif as $row) { ?>
								<tr style='font-size:1.2em;font-weight:bold'>
									<td><?=$row->nama;?></td>
									<td class='text-center'>-</td>
									<td class='text-center'><?=str_replace(',00', '', number_format(${'yard_total_'.$row->id},'2',',','.')) ;?></td>
									<td class='text-center'><?=number_format(${'roll_total_'.$row->id},'0',',','.');?></td>
									<td class='text-center'><b><?=number_format(${'g_total_'.$row->id},'0',',','.');?></b> </td>
									
								</tr>
							<?}?>
							<tr style='font-size:1.2em;font-weight:bold'>
								<td>Penjualan</td>
								<td class='text-center'><?=$idx_total;?></td>
								<td class='text-center'><?=number_format($yard_total,'2',',','.');?></td>
								<td class='text-center'><?=number_format($roll_total,'0',',','.');?></td>
								<td class='text-center'><b><?=number_format($g_total,'0',',','.');?></b> </td>
								<?
								if (is_posisi_id() > 3 || is_posisi_id() == 1) {
									foreach ($tipe_bayar as $row2) { ?>
									<td>
										<?if (isset($total_tipe_bayar[$row2->id])) {
											echo number_format($total_tipe_bayar[$row2->id],'0',',','.');
										}?>
									</td>
								<?}
								}
								?>
								<!-- <td></td>
								<td></td>
								<td></td> -->
							</tr>
							<?if (count($retur_list) > 0) {?>
								<?foreach ($this->satuan_list_aktif as $row) { ?>
									<tr style='font-size:1.2em;font-weight:bold' hidden>
										<td><?=$row->nama;?> (R)</td>
										<td class='text-center'>-</td>
										<td class='text-center'><?=number_format(${'yard_returtotal_'.$row->id},'2',',','.');?></td>
										<td class='text-center'><?=number_format(${'roll_returtotal_'.$row->id},'0',',','.');?></td>
										<td class='text-center'><b><?=number_format(${'g_returtotal_'.$row->id},'0',',','.');?></b> </td>
										<?
										if (is_posisi_id() > 3 || is_posisi_id() == 1) {

											foreach ($tipe_bayar as $row2) { ?>
											<td>
												
											</td>
										<?
										}}
										?>
								<?}?>

								<tr style='font-size:1.2em;font-weight:bold'>
									<td class='text-left'>Retur</td>
									<td class='text-center'><?=$idx_retur_total;?></td>
									<td class='text-center'><?=number_format($yard_retur_total,'2',',','.');?></td>
									<td class='text-center'><?=number_format($roll_retur_total,'0',',','.');?></td>
									<td class='text-center'><b><?=number_format($g_retur_total,'0',',','.');?></b> </td>
									<?
									if (is_posisi_id() > 3  || is_posisi_id() == 1) {
										foreach ($tipe_bayar as $row2) { ?>
										<td>
											<?if (isset($total_retur_bayar[$row2->id])) {
												echo number_format($total_retur_bayar[$row2->id],'0',',','.');
											}?>
										</td>
									<?}
									}
									?>
									<!-- <td></td>
									<td></td>
									<td></td> -->
								</tr>
							<?}?>

							<?if ($customer_id != 0 && $customer_id != '') {?>
								<tr style='font-size:1.2em;font-weight:bold'>
									<td class='text-left'>LUNAS</td>
									<td class='text-center'><?=$count_lunas;?></td>
									<td class='text-center'></td>
									<td class='text-center'></td>
									<td class='text-center'><b><?=number_format($total_lunas,'0',',','.');?></b> </td>
									<?
									if (is_posisi_id() > 3  || is_posisi_id() == 1) {
										foreach ($tipe_bayar as $row2) { 
											$bayar = 0;
											$retur = 0;
											?>
										<td>
											-
										</td>
									<?}
									}
									?>
								</tr>

								<tr style='font-size:1.2em;font-weight:bold'>
									<td class='text-left'>KONTRA</td>
									<td class='text-center'><?=$count_kontra;?></td>
									<td class='text-center'></td>
									<td class='text-center'></td>
									<td class='text-center'><b><?=number_format($total_kontra,'0',',','.');?></b> </td>
									<?
									if (is_posisi_id() > 3  || is_posisi_id() == 1) {
										foreach ($tipe_bayar as $row2) { 
											$bayar = 0;
											$retur = 0;
											?>
										<td>
											-
										</td>
									<?}
									}
									?>
								</tr>

								<tr style='font-size:1.2em;font-weight:bold'>
									<td class='text-left'>BELUM LUNAS</td>
									<td class='text-center'><?=$count_belum_lunas;?></td>
									<td class='text-center'></td>
									<td class='text-center'></td>
									<td class='text-center'><b><?=number_format($total_belum_lunas,'0',',','.');?></b> </td>
									<?
									if (is_posisi_id() > 3  || is_posisi_id() == 1) {
										foreach ($tipe_bayar as $row2) { 
											$bayar = 0;
											$retur = 0;
											?>
										<td>
											-
										</td>
									<?}
									}
									?>
								</tr>
							<?}?>

							<tr style='font-size:1.2em;font-weight:bold'>
								<td class='text-left'>TOTAL</td>
								<td class='text-center'>-</td>
								<td class='text-center'><?=number_format($yard_total - $yard_retur_total,'2',',','.');?></td>
								<td class='text-center'><?=number_format($roll_total - $roll_retur_total,'0',',','.');?></td>
								<td class='text-center'><b><?=number_format($g_total - $g_retur_total,'0',',','.');?></b> </td>
								<?
								if (is_posisi_id() > 3  || is_posisi_id() == 1) {
									foreach ($tipe_bayar as $row2) { 
										$bayar = 0;
										$retur = 0;
										?>
									<td>
										<?if (isset($total_retur_bayar[$row2->id])) {
											$bayar = $total_tipe_bayar[$row2->id];
										}?>
										<?if (isset($total_retur_bayar[$row2->id])) {
											$retur = $total_retur_bayar[$row2->id];
										}
										
										echo number_format($bayar - $retur,'0',',','.');

										?>
									</td>
								<?}
								}
								?>
							</tr>

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

<script src="<?php echo base_url('assets/global/plugins/select2/select2.min.js'); ?>" type="text/javascript" ></script>
<script>
jQuery(document).ready(function() {
	// Metronic.init(); // init metronic core components
	// Layout.init(); // init current layout
	// TableAdvanced.init();

	$("#general_table").on("click",".btn-view-card", function(){
		$(this).closest('tr').find(".card").toggle();
	});

	<?if ($view_type == 0) {
		if (is_posisi_id() != 1) {?>
			$("#general_table, #general_table-1").DataTable({
				"ordering":false,
			});
			<?};?>
	<?}else{?>
		// window.print();
		<?};?>


	// alert("<?=current_url();?>");
	$('#select_customer, #barang_id_select, #warna_id_select').select2({});

	$(".btn-normal").click(function(){
		$("#view-type").val('0');
		$("#form-filter").submit();
	});
	$(".btn-print").click(function(){
		$("#view-type").val('1');
		$("#form-filter").submit();
	});

});
</script>
