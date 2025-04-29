<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css'); ?>"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css'); ?>"/>
<?php echo link_tag('assets/global/plugins/bootstrap-datepicker/css/datepicker3.css'); ?>
<script src="<?php echo base_url('assets/global/plugins/bootbox/bootbox.min.js'); ?>" type="text/javascript"></script>

<style>
#table-overview tr td, #table-overview tr th{
	border:1px solid #ddd;
	padding: 4px 8px;
}

#table-overview tr th{
	font-size:14px;
}

#table-overview tr:nth-child(2n){
	background-color:#eee;
}

/* ================================== */

.table-overview tr td, .table-overview tr th{
	border:1px solid #ddd;
	padding: 4px 8px;
}

.table-overview tr th{
	font-size:14px;
}

.table-overview tr:nth-child(2n){
	background-color:#eee;
}

.warna-performance-cell{
	position: relative;
}

.warna-performance-bar{
	position:absolute;
	top:0px;
	left:0px;
	height:100%;
	background-color:rgba(144, 238, 144,0.4);
}

#pie-chart-info{
	margin:auto;
	font-size:1.2em;
}

#pie-chart-info tr td{
	padding: 2px 5px;
}

#general_table thead th, #general_table tbody th{
	position: -webkit-sticky;
	position: sticky;
	background: #fff;
	z-index: 99;
	min-height: 50px;
	border-bottom: 2px solid #ddd;
	background: #eee;

	-webkit-full-screen{
		top: 0px;
	}
}

#general_table tr td, #general_table tr th{
	border-left:1px solid #888;
	border-right:1px solid #888;
}

#general_table .tr-1 th{
	top: 50px;
}

#general_table .tr-2 th{
	top: 75px;
}

#general_table tbody .nama-barang{
	top: 80px;
}

.bg-awal{
	background-color:#bfffc6;
}
.bg-lain{
	background-color:#ffffc7;
}
.bg-nr{
	background-color:#ffe5e3;
}

/* progressbar */

#progressbar-container{
	text-align:right;
	font-size:16px;
	counter-reset: step;
	position:relative;
}

.progress-item{
	display:inline-block;
	width:150px;
	position:relative;
	text-align:center;
	padding-top:50px;
}

.progress-item:before{
	height:50px;
	width:50px;
	border-radius:50%;
	border:2px solid green;
	line-height:48px;
	content: counter(step);
	counter-increment: step;
	text-align:center;
	position:absolute;
	top:0px;
	right:50px;
	background-color:white;
	z-index: 5;
}

.progress-item:not(:last-child):after{
	content: "";
	position: absolute;
	width: 150px;
	height: 1px;
	background-color: green;
	top: 25px;
	left: 80px;
	z-index : 0;
}

.row-nr{
	background:lightpink;
}

.noreq-ori{
	background:white;
	position:absolute;
	left:100%;
	top:0px;
	padding:5px 10px;
	min-width: 100px;
	box-shadow:5px 5px #eee;
}

.no-req{
	position: relative;
}
</style>
<div class="page-content">
	<div class='container'>
    
    	<div class="modal fade bs-modal-lg" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-body">
						<div class="row">
                            <div class="col-xs-12 col-md-6"></div>
                            <div class="col-xs-12 col-md-6"></div>
                        </div>
					</div>

					<div class="modal-footer">
						<button type="button" class="btn blue btn-active btn-trigger btn-save">Save</button>
						<button type="button" class="btn default  btn-active" data-dismiss="modal">Close</button>
					</div>
				</div>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
		</div>

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
						<div class='row'>
							<div class="col-xs-12">
								<div class='col-xs-12 col-md-4'>
									<?$no_request_lengkap = '';
									foreach ($request_data as $row) {
										$no_request_lengkap = $row->no_request_lengkap;?>
									<?}?>
									<h1><?=$no_request_lengkap;?></h1>
								</div>
								<div class='col-xs-12 col-md-8'>
									<div id="progressbar-container">
										<!-- <div class="progress-item">login</div>
										<div class="progress-item">choose interest</div>
										<div class="progress-item">add friends</div>
										<div class="progress-item">View map</div> -->
										<?foreach ($batch_list as $row) {?>
											<div class="progress-item"><span style='font-size:0.8em'>batch</span><br/><?=date('d M y', strtotime($row->tanggal))?></div>
										<?}?>
									</div>
								</div>
								<div class="col-xs-12">
									<hr/>
									<form action="" id="form-filter">
									<input type="text" name='id' value="<?=$request_barang_id?>" hidden>
										RANGE BY : 
										<label><input <?=($tipe == 1 ? 'checked' : '');?> type="radio" onchange="submitFormFilter()" name="tipe" id="tipe1" value='1'>By Tanggal <b>Surat Jalan</b></label>
										<label><input <?=($tipe == 2 ? 'checked' : '');?> type="radio" onchange="submitFormFilter()" name="tipe" id="tipe2" value='2'>By Tanggal <b>Input</b></label>
									</form>
									<div style='position:absolute;height:100%; width:100%; top:0; left:0; background:#999;opacity:0.5; font-size:1.2em;'
										id='form-filter-loader' 
										class='text-center' hidden>loading....</div>
									<hr/>

								</div>
							</div>
							<div class="col-xs-12 col-md-8">
								<?	
									// $total_request = 0;
									// $total_datang_no_request = 0;
									// $total_datang = 0;
								?>
									<h1>Overview </h1>
									<?
									$request_awal_total = 0;
									$request_lain_total = 0;
									$datang_awal_total = 0;
									$datang_lain_total = 0;
									$datang_noreq_total = 0;

									foreach ($request_list as $row) {
										$rid = $row->request_barang_id;
										$bid = $row->barang_id;
										// break warna_id
										$warna_id = explode(",", $row->warna_id);
										// break warna_id no request
										$warna_id_nr = explode(",", $row->warna_id_no_request);
										
										// totalan request per warna ga dibagi perbulan
										$qty_request = $row->qty_request;
										// totalan datang per warna ga dibagi perbulan
										$qty_datang = $row->qty_datang;
										// bulan awal request
										$bulan_request_awal = $row->bulan_request_awal;
										
										//=========================data request=============================
										// hasilnya array request per warna, , 
										// tapi dipisahkan request per bulan, group by ||
										$qty_data_request_perbulan = explode(",", $row->qty_data_request_perbulan);

										
										// hasilnya array bulan request per warna, 
										// group per bulan dipisahkan by ||, 
										// nanti di link sama qty_request_perbulan
										$bulan_request = explode(",", $row->bulan_request);
										// echo $bid.' -- '.$bulan_request_awal.'<hr/>'; 

										//=========================data datang=============================
										// hasilnya array qty datang per warna 
										// juga array per tanggal datang dipisahkan by ||
										// klo dia ga ada nilai nya 0
										$qty_datang_detail = explode(",", $row->qty_datang_detail);

										// hasilnya array bulan datang per warna
										// masih di pisahkan by tanggal datang, 
										//group tanggal by '||' nanti di link sama qty_datang_detail
										// klo dia ga ada nilai nya 'x'
										$bulan_datang = explode(",", $row->bulan_datang);
										
										$qty_datang_no_request = explode(",", $row->qty_datang_no_request);
										$qty_data_datang_no_request = explode(",", $row->qty_data_datang_no_request);
										$total_request_utama = 0;
										$total_request_lain = 0;
										$sub_datang[$bid] = array();
										
										$datang_awal[$bid] = 0;
										$datang_lain[$bid] = 0;
										$datang_noreq[$bid] = 0;
										$datang_noreq_ori[$bid] = 0;
										$datang_noreq_ori_data[$bid] = [];
										$isNoreq[$bid] = false;
										
										if (!isset($request_awal[$bid])) {
											$request_awal[$bid] = 0;
											$request_lain[$bid] = 0;
										}
										
										// iterating each array
										foreach ($warna_id as $key2 => $value2) {
											if ($value2 != '') {
												# code...
												$isRequestWarna = false;
												if (!isset($request_awal_warna[$bid][$value2])) {
													# code...
													$request_awal_warna[$bid][$value2] = 0;
													$request_lain_warna[$bid][$value2] = 0;
												}
												// initialize total reqeust per bulan
												// jadi kita akan bikin total request per barang saja
												// soalnya klo per warna udah ada data di brq
												// tapi akan di group per bulan
	
												//1. breakdown tiap qty_request_perbulan
												$qrb = explode("||", $qty_data_request_perbulan[$key2]);
												//1. breakdown tiap bulan request
												$brq = explode("||",$bulan_request[$key2]);
												//1. breakdown tiap qty_datang per warna
												$qda = explode("||", $qty_datang_detail[$key2]);
												//1. breakdown tiap bulan per warna
												$bda = explode("||", $bulan_datang[$key2]);
	
												// print_r($qrb);
												// echo "<hr/>";
	
												foreach ($qrb as $key3 => $value3) {
													
													if (!isset($total_request[$brq[$key3]][$bid])) {
														//total request per request per bulan per barang
														$total_request[$bid][$brq[$key3]] = 0;
														//total datang barang non request per tempo per barang
														$total_datang_no_request[$bid][$brq[$key3]] = 0;
														//total datang per request per bulan per barang
														$total_datang[$bid][$brq[$key3]] = 0;
													}
	
													if ($brq[$key3] == $bulan_request_awal) {
														$isRequestWarna = true;
														$request_awal_warna[$bid][$value2] += $value3;
														$request_awal[$bid] += $value3;
														$request_awal_total += $value3;
													}else{
														$request_lain_warna[$bid][$value2] += $value3;
														$request_lain[$bid] += $value3;
														$request_lain_total += $value3;
														$isRequestWarna = true;
													}

	
													//====================================================
													if (!isset($datang_awal_warna[$bid][$value2])) {
														$datang_awal_warna[$bid][$value2] = 0;
													}
													if (!isset($datang_lain_warna[$bid][$value2])) {
														$datang_lain_warna[$bid][$value2] = 0;
													}
													if (!isset($datang_nr_warna[$bid][$value2])) {
														$datang_nr_warna[$bid][$value2] = 0;
													}
	
	
													//add setiap qty request per request per warna per bulan
													// hasilnya nanti request tiap request per barang per bulan
													$total_request[$bid][$brq[$key3]] += $value3;
													
												}
	
												foreach ($qda as $key3 => $value3) {
													// initialisasi udah di atas di breakdown request
													// karena data per barang per warna dan per bulan udah di breakdown di atas
													// echo $bid.' '.$value2.' '.$value3.'<hr/>';
	
													//====================================================
													if (!isset($datang_awal_warna[$bid][$value2])) {
														$datang_awal_warna[$bid][$value2] = 0;
													}
													if (!isset($datang_lain_warna[$bid][$value2])) {
														$datang_lain_warna[$bid][$value2] = 0;
													}
													if (!isset($datang_nr_warna[$bid][$value2])) {
														$datang_nr_warna[$bid][$value2] = 0;
													}
	
													//====================================================
	
													if (date("Y-m", strtotime($bda[$key3])) == date("Y-m", strtotime($bulan_request_awal)) && isset($request_awal[$bid]) ) {
														if ($datang_awal_warna[$bid][$value2] + $value3 <= $request_awal_warna[$bid][$value2]) {
															$datang_awal[$bid] += $value3;
															$datang_awal_total += $value3;
															$datang_awal_warna[$bid][$value2] += $value3;
														}else{
															$sisa = $datang_awal_warna[$bid][$value2] + $value3 - $request_awal_warna[$bid][$value2];
															// print_r('--');
															// echo $datang_awal_warna[$bid][$value2] .'+'. $value3 .'-'. $request_awal[$bid][$value2];
															// echo '<br/>';
															// echo $datang_awal[$bid] .'+'. $value3 .'-'. $request_awal[$bid];
															// echo '<br/>'.$sisa.'<hr/>';
															$datang_awal[$bid] += $value3 - $sisa;
															$datang_awal_total += $value3 - $sisa;
															$datang_awal_warna[$bid][$value2] += $value3 - $sisa;
	
															if (!isset($datang_lain[$bid]) ) {
																$datang_lain[$bid] = 0;
																$datang_lain_warna[$bid][$value2] = 0;
															}
	
															if ($datang_lain_warna[$bid][$value2] + $sisa <= $request_lain_warna[$bid][$value2]) {
																$datang_lain_warna[$bid][$value2] += $sisa;
																$datang_lain[$bid] += $sisa;
																$datang_lain_total += $sisa;
															}else{
																$sisa_nr = $datang_lain_warna[$bid][$value2] + $sisa - $request_lain_warna[$bid][$value2];
	
																$datang_lain_warna[$bid][$value2] += $sisa - $sisa_nr;
																$datang_lain[$bid] += $sisa - $sisa_nr;
																$datang_lain_total += $sisa - $sisa_nr;
	
																$datang_nr_warna[$bid][$value2] += $sisa_nr;
																$datang_noreq[$bid] += $sisa_nr;
																$datang_noreq_total += $sisa_nr;
															}
														}
													}
													
													if (!isset($request_awal[$bid]) && !$isRequestWarna && $request_awal[$bid] == 0 && $value3 != 0 ) {
														$datang_nr_warna[$bid][$value2] += $value3;
														$datang_noreq[$bid] += $value3;
														// echo $bid.' '.$value2.'<br/>';
														$datang_noreq_total += $value3;
														
													}

													$datang_awal_warna_ori[$bid][$value2] = $datang_awal_warna[$bid][$value2];
													$datang_lain_warna_ori[$bid][$value2] = $datang_lain_warna[$bid][$value2];
												}
											}
										}

										// foreach ($warna_id_nr as $key2 => $value2) {
										// 	if ($value2 != '') {
										// 		if (isset($datang_noreq[$bid])) {
										// 			$datang_noreq_ori[$bid] = $datang_noreq[$bid];
										// 		}
										// 	}
										// }
										
										foreach ($warna_id_nr as $key2 => $value2) {
											if ($value2 != '') {
												if (!isset($datang_noreq[$bid])) {
													$datang_noreq[$bid] = 0;
												}
												$isShow[$bid][$value2] = false;
												
												if (!isset($datang_nr_warna[$bid][$value2])) {
													$datang_nr_warna[$bid][$value2] = 0;
												}

												if (!isset($datang_nr_warna_ori[$bid][$value2])) {
													$datang_nr_warna_ori[$bid][$value2] = 0;
												}

												if (!isset($datang_nr_warna_ori[$bid][$value2])) {
													$datang_nr_warna_ori[$bid][$value2] = 0;
												}

												if (!isset($datang_nr_warna_temp[$bid][$value2])) {
													$datang_nr_warna_temp[$bid][$value2] = 0;
												}
												/** 
												=========================================================================== 
												**/
												$sisa = 0;
												$qty_nr = 0;
												$datang_nr_warna_ori[$bid][$value2] += $qty_data_datang_no_request[$key2];
												$datang_noreq_ori[$bid] += $qty_data_datang_no_request[$key2];
												array_push($datang_noreq_ori_data[$bid], $qty_data_datang_no_request[$key2]);

												/**
												 * ============== *
												 * JANGAN DIHAPUS *
												 * ============== *
												**/
												// if (isset($request_awal_warna[$bid][$value2]) && $request_awal_warna[$bid][$value2] > $datang_awal_warna[$bid][$value2] && $request_awal_warna[$bid][$value2] > 0) {
												// 	$isNoreq[$bid] = true;
												// 	$da = 0;
												// 	if ($request_awal_warna[$bid][$value2] > $datang_awal_warna[$bid][$value2] + $qty_data_datang_no_request[$key2]) {
												// 		// $datang_awal_warna[$bid][$value2] += $qty_data_datang_no_request[$key2];
												// 		$da = $qty_data_datang_no_request[$key2];
												// 	}else{
												// 		$sisa = $datang_awal_warna[$bid][$value2] + $qty_data_datang_no_request[$key2] - $request_awal_warna[$bid][$value2];
												// 		$datang_awal_warna[$bid][$value2] = $request_awal_warna[$bid][$value2];
												// 		$da = $qty_data_datang_no_request[$key2] - $sisa;
												// 	}

												// 	$datang_awal[$bid] += $da;
												// 	$datang_awal_total += $da;
												// 	$datang_awal_warna[$bid][$value2] += $da;
												// 	$datang_nr_warna_temp[$bid][$value2] += $da;

												// 	$sisa_nr = 0;
												// 	if ($sisa > 0) {
												// 		if ($request_lain_warna[$bid][$value2] > $datang_lain_warna[$bid][$value2] + $qty_data_datang_no_request[$key2]) {
												// 			$datang_lain_warna[$bid][$value2] += $qty_data_datang_no_request[$key2];
												// 		}else{
												// 			$sisa_nr = $datang_lain_warna[$bid][$value2] + $qty_data_datang_no_request[$key2] - $request_lain_warna[$bid][$value2];
												// 			$datang_lain_warna[$bid][$value2] = $request_lain_warna[$bid][$value2];
												// 		}

												// 		$datang_lain[$bid] += $sisa - $sisa_nr;
												// 		$datang_lain_total += $sisa - $sisa_nr;
												// 	}
												// 	$qty_nr = $sisa_nr;
												// }else if (isset($request_lain_warna[$bid][$value2]) && $request_lain_warna[$bid][$value2] > $datang_lain_warna[$bid][$value2] && $request_lain_warna[$bid][$value2] > 0) {
												// 	$isNoreq[$bid] = true;
													
												// 	$sisa_nr = 0;
												// 	$nd = 0;
												// 	if ($request_lain_warna[$bid][$value2] > $datang_lain_warna[$bid][$value2] + $qty_data_datang_no_request[$key2]) {
												// 		$datang_lain_warna[$bid][$value2] += $qty_data_datang_no_request[$key2];
												// 		$nd = $qty_data_datang_no_request[$key2];
												// 	}else{
												// 		$sisa_nr = $datang_lain_warna[$bid][$value2] + $qty_data_datang_no_request[$key2] - $request_lain_warna[$bid][$value2];
												// 		$datang_lain_warna[$bid][$value2] = $request_lain_warna[$bid][$value2];
												// 		$nd = $qty_data_datang_no_request[$key2] - $sisa_nr;
												// 	}

												// 	$datang_lain[$bid] += $nd;
												// 	$datang_lain_total += $nd;

												// 	$qty_nr = $sisa_nr;
													

												// 	if ($bid == 4) {
												// 		if ($value2 == 61 || $value2 == 64) {
												// 			// echo $value2.' <br/>';
												// 			// echo $request_lain_warna[$bid][$value2] .'>'. $datang_lain_warna[$bid][$value2] .'+'. $qty_data_datang_no_request[$key2].'<br/>';
												// 			// echo $qty_nr.' 2<br/>';
												// 		}
												// 	}
												// }else{
													$qty_nr = $qty_data_datang_no_request[$key2];
												// }

												$datang_nr_warna[$bid][$value2] += $qty_nr;
												$datang_noreq[$bid] += $qty_nr;
												$datang_noreq_total += $qty_nr;

											}
											
										}
									}?>
									<!-- table overview -->
									<table id='table-overview' width="100%">
										<thead>
											<tr>
												<th rowspan='2'>Barang</th>
												<th colspan='3' class='text-center'><?=date('F Y', strtotime($bulan_request_awal));?></th>
												<th colspan='3' class='text-center'>SELANJUTNYA</th>
												<th rowspan='2'  class='text-center'>Non Request</th>
												<th rowspan='2' class='text-center'>TOTAL DATANG</th>
											</tr>
											<tr style='font-size:1.1em; border-bottom:2px solid #777'>
												<th class='text-center'>Request</th>
												<th class='text-center'>Datang</th>
												<th class='text-center'>%</th>
												<th class='text-center'>Request</th>
												<th class='text-center'>Datang </th>
												<th class='text-center'>%</th>
											</tr>
										</thead>
										<tbody>
											<?$idx = 1; $stotal = 0;
											foreach ($request_list as $row) {
												$persen = 0;
												if ($request_awal[$row->barang_id] != 0) {
													$persen = $datang_awal[$row->barang_id]/$request_awal[$row->barang_id];
												}
												$persen_lain = 0;
												if ($request_lain[$row->barang_id]) {
													$persen_lain = $datang_lain[$row->barang_id]/$request_lain[$row->barang_id];
												}

												$sub = $datang_awal[$row->barang_id] + $datang_lain[$row->barang_id] + $datang_noreq[$row->barang_id];
												$stotal += $sub;
												?>
												<!-- #35aa47 ijo -->
												<!-- #ffff91 kuning -->
												<!-- #fcdedc pink -->
												<tr style="<?=($idx%5 == 0 ? 'border-bottom:1.5px solid #aaa' : '')?>" >
													<td>
														<?=$row->nama_barang?>
													</td>
													<td class="bg-awal ">
														<?=number_format($request_awal[$row->barang_id],'0',',','.'); ?>
													</td>
													<td class='bg-awal text-right'>
														<?=str_replace(',00','',number_format($datang_awal[$row->barang_id],'2',',','.')) ;?> <small hidden>(<?=($persen_request)*100?>%)</small>
													</td>
													<td class='bg-awal text-center'><?=number_format($persen*100,'2',".","") ;?></td>
													<td class='bg-lain text-right'>
														<?=number_format($request_lain[$row->barang_id],'0',',','.'); ?>
													</td>
													<td class='bg-lain text-right'>
														<?=str_replace(',00','',number_format($datang_lain[$row->barang_id],'2',',','.')) ;?> <small hidden>(<?=$persen_non_request*100;?>%)
													</td>
													<td class='bg-lain text-center'><?=str_replace(".00","", number_format($persen_lain*100,'2',".","")) ;?></td>
													<td class='bg-nr text-right no-req'>
														<?=str_replace(',00','',number_format($datang_noreq[$row->barang_id],'2',',','.')) ;?> <small hidden>(<?=$persen_non_request*100;?>%)</small>
														<?if($isNoreq[$row->barang_id] > 0 ){
															// print_r($datang_noreq_ori_data[$row->barang_id]);?>
															<div class='noreq-ori' hidden>
																<?=number_format($datang_noreq_ori[$row->barang_id],'0',',','.')?>
															</div>
														<?};?>
													</td>
													<td class='bg-nr text-right'>
														<?=str_replace(',00','',number_format($sub,'2',',','.')) ;?> <small hidden>(<?=$persen_non_request*100;?>%)
													</td>
												</tr>
											<?$idx++;}?>
										</tbody>
										<tfooter>
											<tr style='font-size:1.1em; border-top:2px solid #777'>
												<?
													$persen_total = 0;
													if ($request_awal_total != 0) {
														$persen_total = $datang_awal_total/$request_awal_total;
													}

													$persen_lain_total = 0;
													if ($request_lain_total != 0) {
														$persen_lain_total = $datang_lain_total/$request_lain_total;
													}
													
												?>
												<th>TOTAL</th>
												<th class='bg-awal text-right'><?=number_format($request_awal_total,'0',',','.'); ?></th>
												<th class='bg-awal text-right'><?=number_format($datang_awal_total,'0',',','.'); ?></th>
												<th class='bg-awal text-right'><?=number_format($persen_total*100,'2',',','.'); ?>%</th>
												<th class='bg-lain text-right'><?=str_replace(',00','',number_format($request_lain_total,'2',',','.')) ;?></th>
												<th class='bg-lain text-right'><?=str_replace(',00','',number_format($datang_lain_total,'2',',','.')) ;?></th>
												<th class='bg-lain text-right'><?=number_format($persen_lain_total*100,'2',',','.'); ?>%</th>
												<th class='bg-nr text-right'>
													<?=str_replace(',00','',number_format($datang_noreq_total,'2',',','.'));?>
												</th>
												<th class='bg-nr text-right'><?=str_replace(',00','',number_format($stotal,'2',',','.')) ;?></th>
											</tr>
										</tfooter>
									</table>
							</div>
							<div class="col-xs-12 col-md-4">
								<div class="text-center">
									<h1>PIE CHART </h1>
									<?
										$total_all = $datang_awal_total + $datang_lain_total + $datang_noreq_total;
										$total_awal_persen = number_format($datang_awal_total/$total_all,'4','.','');
										$total_lain_persen = number_format($datang_lain_total/$total_all,'4','.','');
										$total_nr_persen = 1 - $total_awal_persen - $total_lain_persen;
		
										$awal_persen =$total_awal_persen* 360;
										$lain_persen =$total_lain_persen* 360;
										$nr_persen =$total_nr_persen* 360;

										
									?>
										
									<div class='pie' style="margin:auto;margin-bottom:20px; width:100px; height:100px; border-radius:50%; background-image: conic-gradient(#35aa47 <?=$awal_persen?>deg, #ffff91 <?=$awal_persen?>deg <?=$awal_persen+$lain_persen;?>deg, #ffbcb8 <?=$nr_persen;?>deg);"></div>
									<!-- #35aa47 ijo -->
									<!-- #ffff91 kuning -->
									<!-- #fcdedc pink -->

									<!-- conic-gradient(#c4ffd4 36deg, #ffff91 36deg 170deg, #b3e3ff 170deg) -->
									
									<table id='pie-chart-info'>
										<tr style="background-color:#35aa47; color:white">
											<td class="text-right">UTAMA</td>
											<td> :</td>
											<td class="text-right"><?=number_format($datang_awal_total,'0',',','.'); ?></td>
											<td class="text-right"><?=$total_awal_persen * 100?>%
											</td>
										</tr>
										<tr style="background-color:#ffff91">
											<td class="text-right">SELANJUTNYA</td>
											<td> : </td>
											<td class="text-right"><?=number_format($datang_lain_total,'0',',','.'); ?></td>
											<td class="text-right"><?=$total_lain_persen * 100?>%</td>
										</tr>
										<tr style="background-color:#fcdedc">
											<td class="text-right">NON REQUEST</td>
											<td> : </td>
											<td class="text-right"><?=number_format($datang_noreq_total,'0',',','.'); ?></td>
											<td class="text-right"><?=$total_nr_persen * 100?>%
											</td>
										</tr>
										<tr style="background-color:#ddd; font-weight:bold">
										<td class="text-right" >TOTAL</td>
											<td> : </td>
											<td class="text-right"><?=number_format($datang_awal_total + $datang_lain_total + $datang_noreq_total,'0',',','.'); ?></td>
											<td class="text-right">100%</td>
										</tr>
									</table>
									

								</div>
							</div>

						</div>
					</div>
				</div>

				<div class="portlet light">
					<div class="portlet-title">
						<div class="caption caption-md">
							<i class="icon-bar-chart theme-font hide"></i>
							<span class="caption-subject theme-font bold uppercase">Detail Warna</span>
						</div>
					</div>
					<div class="portlet-body">
						<div class='row'>
							<div class="col-xs-12">
								<table class="table table-striped table-bordered table-hover" id="general_table">
									<thead>
										<!-- status_aktif','tanggal_order', 'nama_sales','surat_jalan', 'tanggal','nama_customer','data' -->
										<tr class='tr-1'>
											<th scope="col" class='text-center' style="border-bottom:none">
												
											</th>
											<th scope="col" class='text-center' style="border-bottom:none">
												
											</th>
											<th scope="col" colspan='3' class='text-center' style="border-bottom:1px solid #ddd">
												REQUEST <?=date('F Y', strtotime($bulan_request_awal));?>
											</th>
											<th scope="col" colspan='4' class='text-center' style="border-bottom:1px solid #ddd">
												SELANJUTNYA
											</th>
											<th scope="col" class='text-center' style="border-bottom:none" >
												TOTAL
											</th>
											<th scope="col" class='text-center' style="border-bottom:none" >
												
											</th>
										</tr>
										<tr class='tr-2' style='font-size:1.1em; border-bottom:2px solid #777'>
											<th class='text-center' style="border-top:none">BARANG</th>
											<th class='text-center' style="border-top:none">WARNA</th>
											<th class="text-center">REQUEST</th>
											<th class="text-center">DATANG</th>
											<th class="text-center">%</th>
											<th class="text-center">REQUEST</th>
											<th class="text-center">DATANG</th>
											<th class="text-center">%</th>
											<th class="text-center">NON REQUEST</th>
											<th class="text-center">DATANG</th>
											<th class="text-center">CHART</th>
										</tr>
									</thead>
									<tbody>
										<!--//======================================================================-->

										<?
										$form_data = array();
										foreach ($request_list as $row) { 
											$barang_id = explode("??", $row->barang_id);
											$warna_id = explode("??", $row->warna_id);
											$nama_barang = explode("??", $row->nama_barang);
											$nama_warna = explode("??", $row->nama_warna);
											$qty_data_request = explode("??", $row->qty_data_request);
											$qty_data_datang = explode("??", $row->qty_data_datang);
											
											$bulan_request = explode("??", $row->bulan_request);
											$qty_request = explode("??", $row->qty_request);
											$qty_datang = explode("??", $row->qty_datang);
											
											$warna_id_nr = array();
											if ($row->warna_id_no_request != '') {
												$warna_id_nr = explode("??", $row->warna_id_no_request);
												# code...
											}
											$qty_datang_no_request = explode("??", $row->qty_datang_no_request);
											$nama_warna_nr = explode("??", $row->nama_warna_no_request);
											$qty_data_datang_nr = explode("??", $row->qty_data_datang_no_request);
											$total_sub =0;
											?>

											<?foreach ($barang_id as $key => $value) {

												$wid_list = explode(",", $warna_id[$key]);
												$nama_list = explode(",", $nama_warna[$key]);
												$bid = $value;

												// $wid_list = array_combine($nama_nr, $wid_nr);
												$wid = array_combine($nama_list, $wid_list);
												// $nama_nr = array_combine($nama_nr, $wid_nr);
												// $qd_nr = array_combine($nama_list, $qd_list);
												ksort($wid);

												$wid_nr = array();
												$nama_nr = array();

												if (count($warna_id_nr) > 0) {
													$wid_nr = explode(",", $warna_id_nr[$key]);
													$nama_nr = explode(",", $nama_warna_nr[$key]);
													$qd_nr = explode(",", $qty_data_datang_nr[$key]);


												}

												$idx_warna = 0;
												$dt_warna = array();
												if ($request_awal[$row->barang_id] > 0) {
													foreach ($wid as $key2 => $value2) {
														$isShow[$bid][$value2] = true;
														$persen = 0;
														if ($request_awal_warna[$value][$value2] != 0) {
															$persen = $datang_awal_warna[$value][$value2]/$request_awal_warna[$value][$value2];
														}
														$persen_lain = 0;			
														if ($request_lain_warna[$value][$value2]) {
															$persen_lain = $datang_lain_warna[$value][$value2]/$request_lain_warna[$value][$value2];
														}
														
														$idx = 1;

														array_push($dt_warna,array(
															'barang_id' => $value,
															'warna_id' => $value2,
															'nama_warna' => $key2,
															'request_awal' => $request_awal_warna[$value][$value2],
															'datang_awal' => $datang_awal_warna[$value][$value2],
															'request_lain' => $request_lain_warna[$value][$value2],
															'datang_lain' => $datang_lain_warna[$value][$value2],
															'datang_nr_warna' => $datang_nr_warna[$value][$value2],
														));
														?>
														<tr style="<?=(($idx_warna+1)%5 == 0 ? 'border-bottom:2px solid #aaa;' : '')?>; "  >
															<?if ($idx_warna == 0) {?>
																<td class='text-center nama-barang' style='padding-top:10px; border-bottom:2px solid #aaa; vertical-align:middle' rowspan="<?=count($datang_nr_warna[$bid])+1;?>">
																	<b style="font-size:1.4em"><?=$nama_barang[$key]?></b><?//=count($datang_nr_warna[$value])+1;?>
																	<?//=print_r($datang_nr_warna[$value]);?>
															</td>
															<?}?>
															<!-- <td><?=$nmr[$key2]?></td> -->
															<td><?=$key2?></td>
															<td class="bg-awal text-right">
																<?=number_format($request_awal_warna[$value][$value2],'0',',','.');?>
															</td>
															<td class="bg-awal text-right">
																<?=number_format($datang_awal_warna[$value][$value2],'0',',','.');?>
															</td>
															<td class='bg-awal text-center'><?=(float)number_format($persen*100,'2',".","");?>%</td>
															<td class="bg-lain text-right">
																<?=number_format($request_lain_warna[$value][$value2],'0',',','.');?>
															</td>
															<td class="bg-lain text-right">
																<?=number_format($datang_lain_warna[$value][$value2],'0',',','.');?>
															</td>
															<td class='bg-lain text-center'><?=(float)number_format($persen_lain*100,'2',".","") ;?>%</td>
															<td class="bg-nr text-right no-req">
																<?=number_format($datang_nr_warna[$value][$value2],'0',',','.');?>
																<?=(isset($datang_nr_warna_ori[$value][$value2]) ? "<b style='color:red'>*</b>" : '')?>
																<?if (isset($datang_nr_warna_ori[$value][$value2])) {?>
																	<div class='noreq-ori' hidden>
																		<b>Harusnya :</b> <?=number_format($datang_nr_warna_ori[$value][$value2],'0',',','.');?>
																	</div>
																<?}?>
															</td>
															<?$sub=$datang_awal_warna[$value][$value2] +$datang_lain_warna[$value][$value2] + $datang_nr_warna[$value][$value2]; ?>
															<?$total_sub += $sub;?>
															<td class='bg-lain text-center'><?=number_format($sub,'0',',','.') ;?></td>
															<?if ($idx_warna == 0) {?>
																<td class='text-center' rowspan="<?=count($wid)+count($wid_nr)+1;?>" style="border-left:1px solid #888;">
																<?=count($wid).'+'.count($wid_nr).'+1';?>
																<?print_r($nama_nr)?>
																</td>
															<?}?>
														</tr>
														
														<?$idx_warna++;
													}
												}else{
													$wid = array();
												}

												$dt_nr_warna = array();
												if (count($warna_id_nr) > 0) {
													$wid_nr_list = explode(",", $warna_id_nr[$key]);
													$nama_nr_list = explode(",", $nama_warna_nr[$key]);
													$qd_nr_list = explode(",", $qty_data_datang_nr[$key]);
													// $wid_list = array_combine($nama_nr, $wid_nr);
													$wid_nr = array_combine($nama_nr_list, $wid_nr_list);
													// $nama_nr = array_combine($nama_nr, $wid_nr);
													$qd_nr = array_combine($nama_nr_list, $qd_nr_list);
													ksort($wid_nr);
													
													foreach ($wid_nr as $key2 => $value2) {
														// print_r($v2);
														if ($isShow[$bid][$value2] == false) {
															$isShow[$bid][$value2] = true;
															$sub += $datang_nr_warna[$value][$value2]; 
															$total_sub += $sub;

															//asign buat excel
															array_push($dt_nr_warna,array(
																'barang_id' => $value,
																'warna_id' => $value2,
																'nama_warna' => $key2,
																'request_awal' => 0,
																'datang_awal' => 0,
																'request_lain' => 0,
																'datang_lain' => 0,
																'datang_nr_warna' => $datang_nr_warna[$value][$value2],
															));

															?>
															<tr>
																<?if (count($wid) == 0 && $key2 == 0) {?>
																	<td class='text-center' style="vertical-align:middle" rowspan="<?=count($wid_nr)+1;?>">
																		<b style="font-size:1.2em"><?=$nama_barang[$key]?></b>
																	</td>
																<?}?>
																<td><?=$key2?></td>
																<td class='text-center row-nr'> - </td>
																<td class='text-center row-nr'> - </td>
																<td class='text-center row-nr'> - </td>
																<td class='text-center row-nr'> - </td>
																<td class='text-center row-nr'> - </td>
																<td class='text-center row-nr'> - </td>
																<td class='text-right row-nr'><?=number_format($qd_nr[$key2],'0',',','.');?></td>
																<td class='text-right row-nr'><?=number_format($qd_nr[$key2],'0',',','.');?></td>
																<?if (count($wid) == 0 && $key2 == 0) {?>
																	<td class='text-center' rowspan="<?=count($wid)+count($wid_nr)+1;?>" style="border-left:1px solid #888;">
																		
																	</td>
																<?}?>
															</tr>
														<?}?>
														
													<?}?>
												<?}?>
												<?
												$persen_total = 0;
												if ($request_awal[$value] != 0) {
													$persen_total = $datang_awal[$value]/$request_awal[$value];
												}

												$persen_lain_total = 0;
												if ($request_lain[$value] != 0) {
													$persen_lain_total = $datang_lain[$value]/$request_lain[$value];
													
												}


												?>
													<tr style='font-size:1.1em; border-top:2px solid #aaa;font-weight:bold;'>
														<td>TOTAL</td>
														<td class='text-center'><?=number_format($request_awal[$value],'0',',','.');?></td>
														<td class='text-center'><?=number_format($datang_awal[$value],'0',',','.');?></td>
														<td class='text-center'><?=number_format($persen_total*100,'2',',','.');?>%</td>
														<td class='text-center'><?=number_format($request_lain[$value],'0',',','.');?></td>
														<td class='text-center'><?=number_format($datang_lain[$value],'0',',','.');?></td>
														<td class='text-center'><?=number_format($persen_lain_total*100,'2',',','.');?>%</td>
														<td class='text-center'><?=number_format($datang_noreq[$value],'0',',','.');?></td>
														<td class='text-center'><?=number_format($total_sub,'0',',','.');?></td>
													</tr>
													<tr style='background:#aaa'>
														<td colspan='11'></td>
													</tr>
											<?}
											array_push($form_data, array(
												'barang_id' => $value,
												'nama_barang' => $nama_barang[$key],
												'data_warna' => $dt_warna,
												'data_nr_warna' => $dt_nr_warna
											));
											?>

											
										<?}?>

										<!--//======================================================================-->


									</tbody>
								</table>
							</div>

							<div class="col-xs-12">

								<form action="<?=base_url()?>report/po_request_list_export_excel" target='_blank' method="POST" class='text-right'>
									<input type="text" name="request_barang_id" value="<?=$request_barang_id?>" hidden>
									<input type="text" name="bulan_awal" value="<?=date('F Y', strtotime($bulan_request_awal));?>" hidden>
									<input type="text" name='no_request_lengkap' value="<?=$no_request_lengkap;?>" hidden>
									<textarea type="text" name="data" hidden><?=json_encode($form_data);?></textarea>
									<button type='submit' class="btn btn-md green"><i class="fa fa-download"></i> EXCEL</button>
								</form>
							</div>
						</div>
					</div>
				</div>

				<div class="portlet light" hidden>
					<div class="portlet-title">
						<div class="caption caption-md">
							<i class="icon-bar-chart theme-font hide"></i>
							<span class="caption-subject theme-font bold uppercase">Detail Warna Per Bulan</span>
						</div>
					</div>
					<div class="portlet-body">
						<div class='row'>
							<div class="col-xs-12">
								<?$total_request = 0;
											$total_datang_no_request = 0;
											$total_datang = 0;
									?>
									<?foreach ($get_datang as $bln_r => $dt) {
										foreach ($dt as $idx => $list) {
											foreach ($list as $row) {
												$q_req[$row->barang_id][$row->warna_id][$bln_r] = $row->qty;
												$q_datang_before = 0;
												if (isset($q_datang[$row->barang_id][$row->warna_id][$bln_r])) {
													$q_datang_before = $q_datang[$row->barang_id][$row->warna_id][$bln_r];
												}
												$q_datang[$row->barang_id][$row->warna_id][$bln_r] = $row->qty_datang + $q_datang_before;
												$q_no_batch[$row->barang_id][$row->warna_id][$bln_r] = $row->no_batch;
												$tanggal_batch = $row->tanggal_batch;
												$first_datang = date('Y-m-d', strtotime($row->first_datang));
												$last_datang = date('Y-m-d', strtotime($row->last_datang));
												$dif = strtotime($first_datang) - strtotime($tanggal_batch);
												$wait[$row->barang_id][$row->warna_id][$bln_r] = round($dif / (60 * 60 * 24));

												$dif2 = strtotime($last_datang) - strtotime($first_datang);
												$le[$row->barang_id][$row->warna_id][$bln_r] = round($dif2 / (60 * 60 * 24));
												
											}
										}
									}?>
									<table class='table-overview' width="100%">
										<thead>
											<tr>
												<th class='text-center' rowspan='2'>Barang</th>
												<th class='text-center' rowspan='2'>Warna</th>
												<?foreach ($get_request_bulan as $row) {?>
													<th colspan='5' class='text-center'><?=date('F Y', strtotime($row->bulan_request))?></th>
												<?}?>
												<th class='text-center'rowspan='2'></th>
											</tr>
											<tr>
												<?foreach ($get_request_bulan as $row) {?>
													<th>Batch</th>
													<th>Request</th>
													<th>Datang</th>
													<th class='text-center'>Wait</th>
													<th class='text-center'>%</th>
												<?}?>
											</tr>
										</thead>
										<tbody>
											<?foreach ($request_barang_list as $row) {
												$warna = explode(",",$row->nama_warna);
												$warna_id = explode(",",$row->warna_id);
												
												$idx = 0;
												foreach ($warna as $key => $value) {?>
													<tr>
														<?if ($idx==0) {?>
															<td class='text-center' rowspan="<?=count($warna)+1;?>"><?=$row->nama_barang;?></td>
														<?}?>
														<td><?=$value;?></td>
														<?foreach ($get_request_bulan as $row2) {
															$qd = '';
															$qr = '';
															$perc = '';
															$nb = '';
															$w = '';
															if (isset($q_req[$row->barang_id][$warna_id[$key]][$row2->bulan_request])) {
																$qr = $q_req[$row->barang_id][$warna_id[$key]][$row2->bulan_request];
																$qd = $q_datang[$row->barang_id][$warna_id[$key]][$row2->bulan_request];
																$perc = ($qr != 0 ? str_replace('.00','',number_format($qd/$qr,'2','.','')) : 0);
																$perc = ($perc > 1 ? 1 : $perc);
																$nb = $q_no_batch[$row->barang_id][$warna_id[$key]][$row2->bulan_request];
																$w = $wait[$row->barang_id][$warna_id[$key]][$row2->bulan_request];
																if ( $w < 0) {
																	$w = '';
																}
															}?>
															<td><?=$nb;?></td>
															<td><?=($qr != '' ? str_replace(',00','',number_format($qr,'2',',','.')) : '');?></td>
															<td><?=($qd != '' ? str_replace(',00','',number_format($qd,'2',',','.')) : '');?></td>
															<td class='text-center'><?=$w;?></td>
															<td class='text-center'><?=($perc != '' ? ($perc*100).'%' : '');?></td>
														<?}?>
														<?if ($idx==0) {?>
															<td class='text-center' rowspan="<?=count($warna)+1;?>">
															</td>
														<?}?>
													</tr>
												<?$idx++;}?>
												<tr>
													<td colspan='11'></td>
												</tr>
											<?}?>
										</tbody>
									</table>
									
									
							</div>
							<div class="col-xs-12 col-md-6">
								<div class="text-center">
									
								</div>
							</div>

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
<script src="<?php echo base_url('assets/global/plugins/datatables/extensions/ColReorder/js/dataTables.colReorder.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets_noondev/js/table-advanced.js'); ?>"></script>


<script src="<?php echo base_url('assets/global/plugins/bootstrap-select/bootstrap-select.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/select2/select2.min.js'); ?>" type="text/javascript" ></script>

<script>
jQuery(document).ready(function() {

	// $('#general_table').dataTable();


});

function submitFormFilter(){
    $("#form-filter").submit();
    $("#form-filter-loader").show();
}

$(".no-req").mouseenter(function(){
	$(this).find(".noreq-ori").show();
}).mouseleave(function(){
	$(this).find(".noreq-ori").hide();
});
</script>
