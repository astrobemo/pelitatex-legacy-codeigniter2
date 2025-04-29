<link rel="stylesheet" type="text/css" href="<?=base_url('assets/global/plugins/bootstrap-select/bootstrap-select.min.css'); ?>"/>
<style type="text/css">
.barang-list-container, .barang-list-pool{
	height: 250px;
	overflow: auto;
	border:2px dashed #ddd;
}

.item-list li, .item-list-selected li{
	cursor: pointer;
	margin-bottom: 0px;
	padding: 3px 0 3px 25px; 
	font-size: 1.2em;
	list-style-type: none;
	border-bottom: 0.5px solid #eee;
}
.item-list, .item-list-selected{
	padding: 0px;
}

.item-list li:hover{
	background: #ddd;
}

.item-list-selected li:hover{
	background: lime;
}

.list-hidden{
	display: none;
}

.garis-bawah{
	border-bottom: 2px solid #171717 !important;
}

/*=================================================*/
.bar-progress{
	width: 100%;
	height: 20px;
	font-size: 0.9em;
	font-weight: bold;
	text-align: center;
}
/*=================================================*/

:-webkit-full-screen {
	overflow: auto;
	background: #fff;
}

/* Firefox syntax */
:-moz-full-screen {
	overflow: auto;
	background: #fff;
}

/* IE/Edge syntax */
:-ms-fullscreen {
	overflow: auto;
	background: #fff;
}

/* Standard syntax */
:fullscreen {
	overflow: auto;
	background: #fff;
}

	@media print {
		a[href]:after {
		    content: none !important;
		}

		#general_table tbody tr td{
			border: 1px solid #ddd !important;
		}

		#general_table tbody tr:nth-child(even){
			background-color: #eee !important;
		}
	}

</style>

<?
$count_po = ($count_po == '' ? count($batch_for_pre_po) : $count_po);
$count_show= ($count_po == 0 ? 0 : $count_po - count($batch_for_pre_po));

foreach ($stok_awal as $row) {
	$sa[$row->warna_id] = $row->qty;
}

foreach ($list_stok as $row) {
	$stok_bm[$row->warna_id][$row->po_pembelian_batch_id]['qty'] = $row->qty;
	$stok_bm[$row->warna_id][$row->po_pembelian_batch_id]['roll'] = $row->jumlah_roll;
}

foreach ($list_stok as $row) {
	$stok[$row->warna_id][$row->po_pembelian_batch_id] = $row;
}

$deleted = array();
foreach ($data_set as $val) {
	$stok_now[$val->warna_id] = (isset($sa[$val->warna_id]) ? $sa[$val->warna_id] : 0);

	$jual_latest[$val->warna_id] = array();
	$terjual_sa[$val->warna_id] = 0;
	// $jual_qty_latest_sisa[$val->warna_id] = 0;
	// $jual_qty_latest_qty[$val->warna_id] = '';
	$latest = 0;
	// echo $val->warna_id.' = '.(isset($jual[$val->warna_id]) ? count($jual[$val->warna_id]) : 0)."<br/>";
	if (isset($jual[$val->warna_id])) {
		foreach ($jual[$val->warna_id] as $key => $value) {
			//set milestone sebelum dikurangin
			$qty_now = $stok_now[$val->warna_id];
			//stok awal terus dikurangin qty jual
			$stok_now[$val->warna_id] -= $value->qty;
			/*if ($val->warna_id == 30) {
				echo $stok_now[$val->warna_id].' '.$jual[$val->warna_id][$key]->tanggal.'<br/>';
				echo 'Terjual : '.$terjual_sa[$val->warna_id].'<hr/>';
			}*/
			// klo stok nya masih di atas 0 
			if ($stok_now[$val->warna_id] > 0) {
				// echo $key.'--';
				//data penjualan terakhir
				$jual_latest[$val->warna_id] = $value;
				//data qty(total) di penjualan terakhir
				$jual_qty[$val->warna_id] = $value->qty; 
				array_push($deleted, $key);
				unset($jual[$val->warna_id][$key]);
			}else if($latest == 0){
				//data penjualan terakhir waktu pas stok awal 0
				//sisa qty penjualan di pas stok awal 0
				$jual_qty_latest_sisa[$val->warna_id][$key] = $value->qty - $qty_now;
				//qty as penjualan pad di stok 0
				$jual_qty_latest_qty[$val->warna_id][$key] = $value->qty;
				//data penjualan pas stok awal habis, untuk dipake stok awal data
				$latest_data_jual[$val->warna_id][$key] = $value;
				$latest++;
			}
		}
	}
}

?>

<div class="page-content">
	<div class='container'>
		
		<div class="row margin-top-10">
			<div class="col-md-12">
				<div class="portlet light">
					<div class="portlet-title">
						<div class="caption caption-md">
							<i class="icon-bar-chart theme-font hide"></i>
							<span class="caption-subject theme-font bold uppercase hidden-print"><?=$breadcrumb_small;?></span>
						</div>
						<div class="actions">
							<a href="#portlet-config" data-toggle='modal' class="btn btn-default btn-sm">
							+ Warna </a>
							<button class="btn btn-default btn-sm" onclick="openFullscreen();">
							Fullscreen </button>
						</div>
					</div>
					<div class="portlet-body">
						<?/*$idx = 0;
						foreach ($pre_po_list_barang as $row) {?>
							<button style="font-weight:bold;<?=($idx==0 ? 'border:2px solid red' : 'border:2px solid' );?>" data-id="<?=$row->barang_id;?>" class="btn btn-md default btn-barang"><?=$row->nama_barang;?></button>
						<?$idx++;}*/
						?>
						<div class='hidden-print'>
							<form id="form-barang" >
								<table>
									<tr>
										<td>TANGGAL</td>
										<td> : </td>
										<td><input name='tanggal' style="font-size:1.2em;" class=' date-picker' value="<?=date('d/m/Y');?>"></td>
									</tr>
									<tr>
										<td>BARANG</td>
										<td> : </td>
										<td>
											<select name='barang_id' style="font-size:1.2em; width:350px;" id="barang_id_select" >
												<option value="">Pilih</option>
												<?foreach ($this->barang_list_aktif_beli as $row) {?>
													<option value="<?=$row->id?>" <?=($barang_id == $row->id ? 'selected' : '' )?> ><?=(is_posisi_id() == 1 ? $row->nama.' / ' : '');?><?=$row->nama_jual;?></option>
												<?}?>
											</select>
										</td>
									</tr>
									<tr>
										<td>Jumlah PO</td>
										<td> : </td>
										<td>
											<input style='width:50px' id='count-po' value="<?=($count_po == 0 ? '' : $count_po);?>" name='count_po'> Terakhir
											<br/><small>jika dikosongkan maka semua po akan muncul</small>
										</td>
									</tr>
								</table>
							</form>
						</div>
						
						<!-- " <button class='btn btn-xs red btn-show-po' style='position:relative; top:-5px'>Show QTY PO</button> "." <button class='btn btn-xs blue btn-show-harga' style='position:relative; top:-5px'>Show Harga</button> "." <button class='btn btn-xs green btn-show-ockh' style='position:relative; top:-5px'>Show OCKH</button>" -->
						<hr class='hidden-print'/>
						<div style="width:100%; overflow:auto"  id='body-table' >
							<h2 style='font-weight:bold; display:inline'> - <span class='nama_barang_tampil' ></span> -</h2> 
							<?if (count($batch_for_pre_po) > 0) {?>
								<?=($barang_id != '' ? " <button class='btn btn-xs yellow-gold btn-show-info' style='position:relative; top:-5px'>Show INFO</button> " : '' );?>
							<?}?>
							<table class="table table-striped table-bordered table-hover" id="general_table">
								<thead>
									<tr>
										<th scope="col" rowspan='2' >
											Warna
										</th>
										<th scope="col" rowspan='2' >
											STOK / ROLL
										</th>
										<th scope="col" <?=(count($batch_for_pre_po) == 0 ? 'hidden' : '')?> colspan='<?=($count_po == 0 ? count($batch_for_pre_po) : $count_po);?>' class='text-center'>
											Data PO
										</th>
										<th scope="col" rowspan='2' colspan='2' class='text-center'>
											TOTAL
										</th>
										<th scope="col" rowspan='2' class='text-center'>
											Penjualan
										</th>
										<th scope="col" rowspan='2' class='text-center'>
											PPO
										</th>
										<th scope="col" rowspan='2' colspan='2' class='text-center'>
											TOTAL
										</th>
										<!-- <th scope="col" rowspan='2' class='hidden-print' style="min-width:150px !important">
											Actions
										</th> -->
									</tr>
									<tr>
										<? $po_batch_id = array(); $qty_batch_id = array();
										for ($i=0; $i < 10 ; $i++) { 
											$_c1[$i] = rand(200,255);
											$_c2[$i] = rand(150,255);
											$_c3[$i] = rand(120,255);
										}
										$i =0; $idx_s = $count_show;
										foreach ($batch_for_pre_po as $row) {
											$batch[$row->po_pembelian_batch_id] = $row->po_pembelian_batch_id;
											$color[$row->po_pembelian_batch_id] = "rgba(".$_c1[$i].",".$_c2[$i].",".$_c3[$i].",0.3)";
											$po_batch_id[$row->po_pembelian_batch_id] = true; 
											$qty_batch_id[$row->po_pembelian_batch_id] = 0;
											if ($idx_s >= 0) {?>
												<th style="background:<?=$color[$row->po_pembelian_batch_id];?>; min-width:140px">
													<a style='font-size:0.8em' target="_blank" href="<?=base_url().is_setting_link('transaction/po_pembelian_detail_warna_batch')?>?id=<?=$row->po_pembelian_id?>&batch_id=<?=$row->po_pembelian_batch_id;?>"><?=$row->batch;?></a> <br/>
													<small><?=is_reverse_date($row->tanggal);?></small><br/>
													<div>
													<span class='hide-column-btn' data-batch="<?=$row->po_pembelian_batch_id?>" style='cursor:pointer; font-size:0.7em;padding:3px 8px; background:#abffea'>show</span>
													<span class='show-column-btn' data-batch="<?=$row->po_pembelian_batch_id?>" style='cursor:pointer; font-size:0.7em;padding:3px 8px; background:#ffba4a'>hide</span>
													</div>
												</th>
											<?}?>
										<?$i++; $idx_s++;}?>
									</tr>
								</thead>
								<?$idx = 0;
								$total_stok = 0;
								$total_po = 0;
								$total_roll = 0;?>
								<tbody class='data-warna'>
									<?foreach ($data_set as $row) {
											$batch_id = explode(',', $row->batch_id);
											$batch_set = array_flip($batch_id);
											$qty_sisa = explode(',', $row->qty_sisa_data);
											$qty_sisa_data = array_combine($batch_id, $qty_sisa);
											$qty_beli = explode(',', $row->qty_beli);
											$qty_beli_data = array_combine($batch_id, $qty_beli);
											$locked_by = explode(',', $row->locked_by);
											$locked_data = array_combine($batch_id, $locked_by);


											$qty_po_data = explode(',', $row->qty_po_data);
											$qty_po_data = array_combine($batch_id, $qty_po_data);

											$harga_po = explode(',', $row->harga_po);
											$harga_po = array_combine($batch_id, $harga_po);

											$OCKH = explode(',', $row->OCKH);
											$OCKH = array_combine($batch_id, $OCKH);

											$info = "";

											$total_stok += $row->qty_stok;
											$total_roll += $row->jumlah_roll_stok;
											$total_po += $row->qty_sisa;
											$qty_now = 0;
											$idx_s = $count_show;
											$total_bm[$row->warna_id] = 0;
											$total_jual[$row->warna_id] = 0;
											$total_trx[$row->warna_id] = 0;
											?>
											<tr class="<?=(($idx+1) % 5 == 0 ? 'garis-bawah' : '' );?>">
												<td>tes<?=$row->nama_warna?> <?=(is_posisi_id() == 1 ? $row->warna_id : '');?></td>
												<td><b class='qty-stock'><?=number_format($row->qty_stok,'0',',','.').' /'.$row->jumlah_roll_stok; $qty_now = $row->qty_stok;?></b></td>
												<!-- <td><?//=$row->jumlah_roll_stok; //print_r($batch_set); print_r($qty_sisa_data);?> -->
												</td>
												<?foreach ($batch_for_pre_po as $row2) {
													$terjual[$row->warna_id][$row2->po_pembelian_batch_id] = 0;
													$bm_first[$row->warna_id][$row2->po_pembelian_batch_id] = '';
													$bm_last[$row->warna_id][$row2->po_pembelian_batch_id] = '';
													$first[$row->warna_id][$row2->po_pembelian_batch_id]='';
													$last[$row->warna_id][$row2->po_pembelian_batch_id]='';
													$count_jual[$row->warna_id][$row2->po_pembelian_batch_id]=0;
													$count_jual_by_customer[$row->warna_id][$row2->po_pembelian_batch_id]=0;
													$count_jual_by_non_customer[$row->warna_id][$row2->po_pembelian_batch_id]=0;
													$bm_full = (isset($stok[$row->warna_id][$row2->po_pembelian_batch_id]) ? $stok_bm[$row->warna_id][$row2->po_pembelian_batch_id]['qty'] : 0);
													$total_bm[$row->warna_id] += $bm_full;
													unset($cust_idx);
													?>
														<!-- jumlah barang masuk -->
														<?if (isset($stok[$row->warna_id][$row2->po_pembelian_batch_id])) {
															$bm_first[$row->warna_id][$row2->po_pembelian_batch_id] = $stok[$row->warna_id][$row2->po_pembelian_batch_id]->tanggal_first;
															$bm_last[$row->warna_id][$row2->po_pembelian_batch_id] = $stok[$row->warna_id][$row2->po_pembelian_batch_id]->tanggal_last;
														}?>
													<td style="<?=($idx_s <0 ? 'display:none' : '')?>; background:<?=$color[$row2->po_pembelian_batch_id];?>">
														<!-- masih sama barang masuk -->
														<?if (isset($jual[$row->warna_id]) && isset($stok_bm[$row->warna_id][$row2->po_pembelian_batch_id]['qty'])) {
															$i=0; 
															// walking process nemuin qtyjual meet qty barang masuk 
															foreach ($jual[$row->warna_id] as $key => $value) {
																unset($jual_by_cust);
																if ($stok_bm[$row->warna_id][$row2->po_pembelian_batch_id]['qty'] > 0) {
																	// klo ada bekas dari pengurangan batch sebelumnya
																	if (isset($jual_qty_latest_sisa[$row->warna_id][$key])) {
																		// echo '-'.$jual_qty_latest_sisa[$row->warna_id][$key];
																		// pengurangan barang masuk dengan dijual 
																		$stok_bm[$row->warna_id][$row2->po_pembelian_batch_id]['qty'] -= $jual_qty_latest_sisa[$row->warna_id][$key];
																		// assign setting tanggal awal penjualan
																		$first[$row->warna_id][$row2->po_pembelian_batch_id] = $latest_data_jual[$row->warna_id][$key]->tanggal;
																		// cek biar ga bablas
																		$qty_cek = ($jual_qty_latest_sisa[$row->warna_id][$key] >= $stok_bm[$row->warna_id][$row2->po_pembelian_batch_id]['qty'] ? $stok_bm[$row->warna_id][$row2->po_pembelian_batch_id]['qty'] : $jual_qty_latest_sisa[$row->warna_id][$key] );
																		
																		$terjual[$row->warna_id][$row2->po_pembelian_batch_id] += $qty_cek;
																	
																	}else{
																		// cek biar ga bablas
																		$qty_cek = ($stok_bm[$row->warna_id][$row2->po_pembelian_batch_id]['qty'] >= $value->qty ? $value->qty : $stok_bm[$row->warna_id][$row2->po_pembelian_batch_id]['qty'] );
																		$terjual[$row->warna_id][$row2->po_pembelian_batch_id] += $qty_cek.'<br/>';
																		// echo '-'.$value->qty;
																		// pengurangan barang masuk dengan dijual 
																		$stok_bm[$row->warna_id][$row2->po_pembelian_batch_id]['qty'] -= $value->qty;
																		if ($i == 0) {
																			// add total terjual per batch
																			$first[$row->warna_id][$row2->po_pembelian_batch_id]=$jual[$row->warna_id][$key]->tanggal;
																		}
																	}
																	// echo '<br/>';
																	// waktu pas stok barang masuk nya abis assign beberapa value
																	if ($stok_bm[$row->warna_id][$row2->po_pembelian_batch_id]['qty'] < 0) {
																		// echo'<hr/>';
																		//set lagi jumlah terakhir jual
																		$jual_qty_latest_sisa[$row->warna_id][$key] = $stok_bm[$row->warna_id][$row2->po_pembelian_batch_id]['qty']*-1;
																		// set tanggal penjualan terakhir
																		$last[$row->warna_id][$row2->po_pembelian_batch_id]=$jual[$row->warna_id][$key]->tanggal;
																		// set lagi data penjualan terakhir
																		$latest_data_jual[$row->warna_id][$key] = $value;																	
																	}else{
																		// remove data penjualan dari daftar
																		$count_jual[$row->warna_id][$row2->po_pembelian_batch_id]++;
																		$get_terakir = $jual[$row->warna_id][$key]->tanggal;
																		if (!isset($cust_idx[$jual[$row->warna_id][$key]->customer_id]) && $jual[$row->warna_id][$key]->customer_id != 0 ) {
																			$count_jual_by_customer[$row->warna_id][$row2->po_pembelian_batch_id]++;
																			$cust_idx[$jual[$row->warna_id][$key]->customer_id] = 1;
																		}else if($jual[$row->warna_id][$key]->customer_id == 0){
																			$count_jual_by_non_customer[$row->warna_id][$row2->po_pembelian_batch_id]++;
																		}else{
																			$cust_idx[$jual[$row->warna_id][$key]->customer_id]++;
																		}
																			// add total terjual per batch
																		unset($jual[$row->warna_id][$key]);
																	}
																	# code...
																	$i++;
																}
															}?>
															<?/*if (isset($locked_data[$row2->po_pembelian_batch_id])) {?>
																SISA PO:
																<span><?=($locked_data[$row2->po_pembelian_batch_id] != 0 ? "<i class='fa fa-lock'></i>" : number_format((float)$qty_sisa_data[$row2->po_pembelian_batch_id],'0',',','.'));?></span>
															
															<hr style='padding:0px; margin:2px'/>
															<?}*/?>

															<span style='background:#fff'><?=($locked_data[$row2->po_pembelian_batch_id] != 0 ? "<i class='fa fa-lock'></i>" : number_format((float)$qty_sisa_data[$row2->po_pembelian_batch_id],'0',',','.'));?></span>
															<?
																$total_trx[$row->warna_id] += $count_jual[$row->warna_id][$row2->po_pembelian_batch_id];
																$full = $qty_po_data[$row2->po_pembelian_batch_id];
																$bm_persen = $bm_full/$full*100;
																$terjual_persen = $terjual[$row->warna_id][$row2->po_pembelian_batch_id]/$full*100;
																$jual_persen = $terjual[$row->warna_id][$row2->po_pembelian_batch_id]/$bm_full*100;
															?>
															<div class='bar-progress' style='background:#e5e4e2; border-bottom:1px dashed #333'><?=number_format($full,'0',',','.')?></div>
															<div class='bar-progress' style='background:linear-gradient(90deg, #ffbf7a <?=$bm_persen;?>%, transparent 0%); border-bottom:1px dashed #333'><?=number_format($bm_full,'0',',','.')?></div>
															<div class='bar-progress' style='background:linear-gradient(90deg, gold <?=$terjual_persen;?>%, transparent 0%); border-bottom:1px dashed #333'><?=number_format($terjual[$row->warna_id][$row2->po_pembelian_batch_id],'0',',','.')?></div>
															<hr style='padding:0px; margin:2px'/> 
															<!-- <div class='bar-progress' style='background:linear-gradient(to right, gold <?=$terjual_persen;?>%, #ffbf7a <?=$beda=$bm_persen-$terjual_persen;?>%; #e5e4e2 <?=100-$terjual_persen-$beda?>%); border-bottom:1px dashed #333'></div> -->
															<?
																$in_1 = date_create($bm_first[$row->warna_id][$row2->po_pembelian_batch_id]);
																$in_2 = date_create($bm_last[$row->warna_id][$row2->po_pembelian_batch_id]);
																$in_interval = date_diff($in_1,$in_2);
																//================
																$out_1 = date_create($first[$row->warna_id][$row2->po_pembelian_batch_id]);
																$out_2 = date_create(($last[$row->warna_id][$row2->po_pembelian_batch_id] != '' ? $jual[$row->warna_id][$key]->tanggal : $get_terakir ));
																$out_interval = ($first[$row->warna_id][$row2->po_pembelian_batch_id] == '' ? 0 : date_diff($out_1,$out_2)->format('%d') );
																$wait_interval = ($first[$row->warna_id][$row2->po_pembelian_batch_id] == '' ? 0 : date_diff($in_1, $out_1)->format('%d') );
																$in_out_interval = date_diff($in_1, $out_2);


															?>
															- Terjual : <?=str_replace('.00', '', number_format($jual_persen,'2','.',',')) ;?>%<br/>
															- Dalam : <?=$out_interval;?> hari<br/>
															- Jml Trx : <?=$count_jual[$row->warna_id][$row2->po_pembelian_batch_id];?> trx <br/>
															<!-- 6. Jml Cust : <?=$count_jual_by_customer[$row->warna_id][$row2->po_pembelian_batch_id];?>  -->
															<!-- 7. Jml Non Cust : <?=$count_jual_by_non_customer[$row->warna_id][$row2->po_pembelian_batch_id];?>  -->
														<?}?>
													</td>
												<?$idx_s++;}?>
												<!-- <a data-toggle="popover" class='btn btn-xs default' data-trigger='click' title="PO Gantung" data-html="true" data-content="<?=$info;?>"><i class='fa fa-info'></i></a> -->
												<?//$qty_now=$row->qty_stok + $row->qty_sisa;?>
												<td class='text-right' style='border-right:0px; font-size:1.1em'><?=number_format($qty_now,'0',',','.');?>
												</td>
												<td class='text-left' style='border-left:0px'><?=$row->nama_satuan?></td>
												<td>
													Terjual : <?=str_replace(',00', '', number_format($total_jual[$row->warna_id],'2',',','.'));?>
													Persen : <?=str_replace(',00', '', number_format($total_jual[$row->warna_id]/($total_bm[$row->warna_id] != 0 ? $total_bm[$row->warna_id] : 1) ,'2',',','.'));?>
												</td>
												<td class='text-center'>
													<input class='ppo-input amount-number' style='width:100px; border:none; border-bottom:1px solid #171717; padding:5px'>
												</td>
												<td></td>
											</tr>
									<?$idx++;} ?>
									<tr style='font-size:1.2em;text-align:right'>
										<td>TOTAL</td>
										<td><?=number_format((float)$total_stok,'0',',','.');?>/<?=$total_roll;?></td>
										<?$idx_s = $count_show;
										foreach ($batch_for_pre_po as $val) {?>
											<td style="<?=($idx_s < 0 ? 'display:none' : '');?>">
												<?=$qty_batch_id[$val->po_pembelian_batch_id];?>
											</td>
										<?$idx_s++;}?>
										<td style='border-right:0px;' ><?=number_format((float)($total_stok + $total_po),'0',',','.');?></td>
										<td style='border-left:0px; text-align:left'> <?=$row->nama_satuan;?> </td>
										<td class='hidden-print'></td>
										<td class='hidden-print'></td>
										<td class='hidden-print'></td>
										<td class='hidden-print'></td>
										<td class='hidden-print'></td>
									</tr>
								</tbody>

							</table>
								<hr/>
							<button class='btn btn-lg blue hidden-print' onclick="window.print()"><i class='fa fa-print'></i> PRINT</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>			
</div>


<script src="<?php echo base_url('assets/global/plugins/datatables/media/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets_noondev/js/table-advanced.js'); ?>"></script>

<script src="<?php echo base_url('assets/global/plugins/select2/select2.min.js'); ?>" type="text/javascript" ></script>

<script type="text/javascript" src="<?php echo base_url('assets/global/plugins/bootstrap-select/bootstrap-select.min.js'); ?>"></script>


<script>
jQuery(document).ready(function() {
	$('[data-toggle="popover"]').popover();

	$('#barang_id_select').select2();
	$(".nama_barang_tampil").html($("#barang_id_select :selected").text());

	$("#barang_id_select").change(function(){
		if ($(this).val() != '' ) {
			$("#form-barang").submit();
		};

		$(".nama_barang_tampil").html("<i>loading...</i>")
    	// var id = $(this).val();
    	// $(".data-warna").hide();
    	// $("#barang-"+id).show();
    	// $(".nama_barang_tampil").html($("#barang_id_select :selected").text());
	});

	$("#count-po").change(function(){
		if ($("#barang_id_select").val() != '') {
			$("#form-barang").submit();
			$(".nama_barang_tampil").html("<i>loading...</i>");
		};
	});

	$(".btn-show-harga").click(function(){
		$('.badge-harga').css('visibility', $('.badge-harga').css('visibility') == 'hidden' ? 'visible' : 'hidden');
	});

	$(".btn-show-po").click(function(){
		$('.badge-po').css('visibility', $('.badge-po').css('visibility') == 'hidden' ? 'visible' : 'hidden');
	});

	$(".btn-show-ockh").click(function(){
		$('.badge-ockh').css('visibility', $('.badge-ockh').css('visibility') == 'hidden' ? 'visible' : 'hidden');
	});

	$(".btn-show-info").click(function(){
		$('.badge-ockh, .badge-po, .badge-harga').css('visibility', $('.badge-ockh, .badge-po, .badge-harga').css('visibility') == 'hidden' ? 'visible' : 'hidden');
	});

	//=============================column control=============================


});

var elem = document.getElementById("body-table");
function recount_table(){

}

// var elem = document.documentElement;
function openFullscreen() {
  if (elem.requestFullscreen) {
    elem.requestFullscreen();
  } else if (elem.mozRequestFullScreen) { /* Firefox */
    elem.mozRequestFullScreen();
  } else if (elem.webkitRequestFullscreen) { /* Chrome, Safari & Opera */
    elem.webkitRequestFullscreen();
  } else if (elem.msRequestFullscreen) { /* IE/Edge */
    elem.msRequestFullscreen();
  }
}

</script>
