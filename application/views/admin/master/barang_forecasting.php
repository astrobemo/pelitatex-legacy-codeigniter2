<?php echo link_tag('assets/global/plugins/jqvmap/jqvmap/jqvmap.css'); ?>
<?php echo link_tag('assets/global/plugins/morris/morris.css'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css'); ?>"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css'); ?>"/>

<style type="text/css">
.page-content{
	min-width: 1200px !important;
}

#dataContainer{
	position: absolute;
	top:0px;
	left: 0px;
	padding: 15px;
	background: rgba(64,64,64,0.8);
	color: white;
}

.barang-on-edit{
	background: yellow !important;
}

.keterangan-input{
	/*display:none;*/
	text-align:left; 
	width: 200px;
}

/*.keterangan-input:focus{
	display: block;
}*/

.table-qty .thead th{
	position: -webkit-sticky;
	position: sticky;
	top: 50px;
	z-index: 99;
	min-height: 50px;
	border-bottom: 2px solid #ddd;
	background: #fff;

	-webkit-full-screen{
		top: 0px;
	}
}


.table-qty tr td:not(.data-container), .table-qty tr th{
	border: 1px solid #ddd;
	padding: 5px;
}

.data-container{
	position: relative;
	border: 1px solid #ddd;
	position: relative;
	text-align: right;
	width: 70px;
	height: 100%;
	padding:0px !important;
}

.input-forecast, .input-forecastreadonly{
	position: absolute;
	top: 0px;
	left: 0px;
	width:100%;
	height: 100%; 
	text-align:right;
	padding-right: 5px;
	border:none;
	background: transparent;
}

.additional:active{
	display: block;
}

.additional:focus{
	display: block;
}

.keterangan-input:focus{
	display: block;
}

.additional{
	position: absolute;
	padding: 5px;
	background: #ffff88;
	color: black;
	top: 0px;
	left: 100%;
	z-index: 9999;
	/*display: none;*/
	width: 220px;
	text-align:left;
}

.warna-selector{
	cursor: pointer;
}



.non-active{
	color: #ddd;
	text-decoration: line-through;
}

.row-nonactive{
	display: none;
}

.gray-bg  tr:nth-child(2n){
	background: #ddd;
}

.blue-bg  tr:nth-child(2n){
	background: #deeeff;
}

.orange-bg  tr:nth-child(2n){
	background: #ffe2c2;
}

.bj-view{
    position: relative;
}

.bj-div{
    display:none;
    position:absolute;
    top:0px;
    left:100%;
    background:white;
    padding:10px 20px;
    z-index: 999;
}

/*.additional input:focus{
	display: block;
}

.additional:hover{
	display: block;
}*/

</style>

<div class="page-content">
	<div class='container'>

		<?$color_list = [];

		$kode_warna=[];
		foreach ($this->warna_list_aktif as $row) {
			if ($row->kode_warna != '') {
				$kode_warna[$row->id] = $row->kode_warna;
			}else{
				$kode_warna[$row->id] = "#333";
			}
		}
		$tgl1 = new DateTime(is_date_formatter($tanggal_start_forecast));
		$tgl2 = new DateTime(is_date_formatter($tanggal_end_forecast));

		$tgl1_now = new DateTime(is_date_formatter($tanggal_start));
		$tgl2_now = new DateTime(is_date_formatter($tanggal_end));

		//yg atas sama dengan bawah
		// echo is_date_formatter($tanggal_start);
		// echo is_date_formatter($tanggal_end);
		$jarak = date_diff($tgl1, $tgl2);
		$range_tanggal = $jarak->format('%m')+1;
		// echo "<h1>$range_tanggal</h1>";

		$interval = new DateInterval('P1M');
		$periode = new DatePeriod($tgl1, $interval, $tgl2->setTime(0,0,1));
		$periode_now = new DatePeriod($tgl1_now, $interval, $tgl2_now->setTime(0,0,1));

		// foreach ($periode as $date) {
		// 	echo $date->format('Y-m-d').'/'.$date->format('F Y').'<br/>';
		// }

		foreach ($data_warna as $row) {
			$color = sprintf('#%06X', mt_rand(0, 0xFFFFFF));
			$color = strtolower($color);
			while ( in_array($color, $color_list)) {
				$color = sprintf('#%06X', mt_rand(0, 0xFFFFFF));
				$color = strtolower($color);
			}
			array_push($color_list, $color);
			$total_qty[$row->warna_id]=0;
			$total_amount[$row->warna_id]=0;
			$total_trx_warna_all[$row->warna_id]=0;
			$qty_total_data[$row->warna_id] = 0;
		}

		$data_jual = (array)$data_penjualan;
		$data_show = array();
		foreach ($data_jual as $key => $value) {
			$data_show[$value->bulan_jual] = $value;
			$total_trx[$value->bulan_jual] = 0;
			$penjualan_id_list[$value->bulan_jual] = array();
            // if ($value->warna_id==24) {
                // print_r($value);
                // echo '<hr/>';
                // echo '<hr/>';
            // }
		}

		

		
		foreach ($data_show as $i => $value) {
			$warna_id_list = explode(",", $value->warna_id);
			// $penjualan_data[$i] = explode(",", $value->penjualan_data);
			// $count_trx_data[$i] = explode("=?=", $value->penjualan_id);
			$qty_data[$i] = explode(",", $value->qty_data);
			$qty_history[$i] = explode(",", $value->qty_history);
			$history_id[$i] = explode(",", $value->history_id);
			$created_history[$i] = explode(",", $value->history_created);
			$keterangan_data[$i] = explode("=?=", $value->keterangan);

            
			// echo count($count_trx_data[$i]).'=='.count($keterangan_data[$i]).'<hr/>';

			// foreach ($count_trx_data[$i] as $key2 => $value2) {
			// 	$break = explode("??", $value2);
			// 	foreach ($break as $key3 => $value3) {
			// 		$break2 = explode(",", $value3);
			// 		foreach ($break2 as $key4 => $value4) {
			// 			array_push($penjualan_id_list[$i], $value4);
			// 		}
			// 	}
			// }

			// if (is_posisi_id() == 1) {
			// }
			// $count_trx_warna[$i] = array_combine($warna_id_list, $count_trx_data[$i]);
			// foreach ($count_trx_warna[$i] as $key2 => $value2) {
			// 	$total_trx_warna[$i][$key2] = array();
			// }
			// foreach ($count_trx_warna[$i] as $key2 => $value2) {
			// 	$break = explode("??", $value2);
			// 	foreach ($break as $key3 => $value3) {
			// 		$break2 = explode(",", $value3);
			// 		foreach ($break2 as $key4 => $value4) {
			// 			array_push($total_trx_warna[$i][$key2], $value4);
			// 		}
			// 	}
			// 	$total_trx_warna[$i][$key2] = array_unique($total_trx_warna[$i][$key2]);
			// }

			// $penjualan_id_list[$i] = array_unique($penjualan_id_list[$i]);

			// $total_trx[$i] = count($penjualan_id_list[$i]);
			// $penjualan_data[$i] = array_combine($warna_id_list, $penjualan_data[$i]);
			$qty_data[$i] = array_combine($warna_id_list, $qty_data[$i]);
			$keterangan_data[$i] = array_combine($warna_id_list, $keterangan_data[$i]);
			// if (isset($qty_history[$i])) {
				// print_r($qty_history[$i]);
				// echo '<br/>';
				// print_r($warna_id_list);
				// echo '<br/>';
				// echo $i;
				$qty_history_data[$i] = array_combine($warna_id_list, $qty_history[$i]);
				$history_id_data[$i] = array_combine($warna_id_list, $history_id[$i]);
				$created_history_data[$i] = array_combine($warna_id_list, $created_history[$i]);
				// echo '<hr/>';
			// }
		}

		// print_r($qty_history_data);

		// for ($i=1; $i <=12 ; $i++) {
		foreach ($periode as $date) {
			$qty_total_bulan[$date->format('Y-m')] = 0;
			$qty_total_bulan_ori[$date->format('Y-m')] = 0;
			if (!isset($qty_data[$date->format('Y-m')])) {
				$qty_data[$date->format('Y-m')] = array();
			}

			if (!isset($keterangan_data[$date->format('Y-m')])) {
				$keterangan_data[$date->format('Y-m')] = array();
			}
			foreach ($qty_data[$date->format('Y-m')] as $key => $value) {
				// echo $key.'+='.$value.'<br/>';
				$qty_total_data[$key] += $value;
			}
		}



		foreach ($data_warna as $row) {
			// echo $qty_total_data[$row->warna_id].' / '.$range_tanggal;
			$rate = round($qty_total_data[$row->warna_id]/$range_tanggal);
			foreach ($periode as $date) {
				$i = $date->format('n');
				$qty_rate[$date->format('Y-m')][$row->warna_id] = $rate;
				if (is_posisi_id()==1) {
					// echo $date->format('Y-m').' == '.$qty_rate[$date->format('Y-m')][$row->warna_id].'<hr/>';
				}
			}

			foreach ($periode as $date) {
				$i = $date->format('n');
				$key = $date->format('Y-m');
				$key_now = ($date->format('Y')+1).'-'.$date->format('m');
				// $total_trx_warna_all[$row->warna_id] += (isset($qty_data[$key][$row->warna_id]) ? $qty_data[$key][$row->warna_id] : 0);
				// echo $i .' == '.(isset($qty_data[$key][$row->warna_id]) ? $qty_data[$key][$row->warna_id] : 0).'&nbsp;';

				$qtyOri = '';
				if (isset($created_history_data[$key][$row->warna_id]) && $created_history_data[$key][$row->warna_id] != '-') {
					if (isset( $qty_history_data[$key][$row->warna_id])) {
						$qty_total_bulan[$key] += (isset($qty_data[$key][$row->warna_id]) ? $qty_data[$key][$row->warna_id] : 0);
						$qtyList = explode('??', $qty_history_data[$key][$row->warna_id]); 
						foreach ($qtyList as $key2 => $value2) {
							if ($key2 == 0) {
								$qtyOri += $value2;
							}
						}
					}
				}

				if ($qtyOri == '') {
					$qtyOri = (isset($qty_data[$key][$row->warna_id]) ? $qty_data[$key][$row->warna_id] : 0);
				}

				$qty_total_bulan_ori[$key] += $qtyOri;

			}
		}

		if (is_posisi_id() == 1) {
			// print_r($qty_total_bulan_ori);
		}


		foreach ($penjualan_berjalan as $row) {
			if (!isset($total_jual_berjalan[$row->bulan_berjalan])) {
				$total_jual_berjalan[$row->bulan_berjalan] = 0;

			}
			// echo $row->warna_id.' '.$row->bulan_berjalan.' '.$row->qty.'<hr/>';
			$total_jual_berjalan[$row->bulan_berjalan] += $row->qty;
			$qty_jual[$row->warna_id][$row->bulan_berjalan]	= $row->qty;
		}

		// if(is_posisi_id() == 1){
		// 	print_r($total_bulan_berjalan);
		// }

	?>


		<div class="row margin-top-10">
			<div class="col-md-12">
				<div class="portlet light">
					<div class="portlet-title">
						<div class="caption caption-md">
							<i class="icon-bar-chart theme-font hide"></i>
							<span class="caption-subject theme-font bold uppercase">Data Barang</span>
						</div>
					</div>
					<div class="portlet-body">
						<table style='font-size:1.2em;' width='100%;'>
							<form method='get'>
								<tr>
									<td style='width:100px;'>Tanggal</td>
									<td class='padding-rl-5' style='width:10px'>
										:
									</td>
									<td style='padding-bottom:5px; text-align:left'>
										<input name='tanggal_start' style="width:150px; text-align:center" class='date-picker-month' value="<?=date('F Y', strtotime(is_date_formatter($tanggal_start)));?>"> 
										to  <input name='tanggal_end'  style="width:150px; text-align:center" class='date-picker-month' value="<?=date('F Y', strtotime(is_date_formatter($tanggal_end)));?>" >
									</td>
								</tr>
								<?foreach ($data_barang as $row) { ?>
									<tr>
										<td>
											Nama Beli
										</td>
										<td class='padding-rl-5'>
											:
										</td>
										<td style='padding-bottom:5px; text-align:left'>
											<select name='id' style="width:322px; " id='barang-list-select'>
												<?foreach ($this->barang_list_aktif as $row2) {?>
													<option value="<?=($row2->id)?>" <?=($barang_id == $row2->id ? 'selected' : '');?> ><?=$row2->nama?></option>
												<?}?>
											</select>
											<button class='btn btn-xs default' type='submit'>OK</button>
											<span hidden style='font-size:1.3em'><?=$row->nama;?></span>
										</td>
									</tr>
									<tr hidden>
										<td>
											Nama Jual
										</td>
										<td class='padding-rl-5'>
											:
										</td>
										<td style='font-size:1.3em'>
											<?=$nama_jual;?>
										</td>
									</tr>
									<tr>
										<td style='vertical-align:top' >Deskripsi</td>
										<td class='padding-rl-5' style='vertical-align:top'>
											:
										</td>
										<td style='padding-bottom:5px; text-align:left'>
											<?=$row->deskripsi_info?>
										</td>
									</tr>
								<?}?>
							</form>
							<tr>
								<td style='vertical-align:top' >Warna Terdaftar</td>
								<td class='padding-rl-5' style='vertical-align:top'>
									:
								</td>
								<td  style='padding-bottom:5px; text-align:left'>
									<div style='column-count:4'>
										<?foreach ($data_warna as $row) { 
											$qty_warna[$row->warna_id] = 0;?>
											<div class='warna-selector' id="warnaSelector-<?=$row->warna_id;?>" onclick="hideWarna('<?=$row->warna_id;?>')">
												- <?=$row->warna_jual;?> <span style='display:inline-block; width:15px;height:15px; border:2px solid #ddd;background-color:<?=$row->kode_warna;?>'></span>
											</div>
										<?}?>
									</div>
								</td>
							</tr>
						</table>
					</div>
					
				</div>
			</div>

			<div class="col-md-12">
				<div class="portlet light">
					<div class="portlet-title">
						<div class="caption caption-md">
							<i class="icon-bar-chart theme-font hide"></i>
							<span class="caption-subject theme-font bold uppercase"><span id='forecast-title' style='color:gray'>Forecast</span><span id='ori-title' style='color:blue' hidden>Penjualan Tahun Lalu</span><span id='berjalan-title' style='color:orange' hidden>Penjualan Tahun Berjalan</span> </span>
						</div>
						<div class="actions hidden-print">
							<button onclick='switchView()' id="switchBtn" class="btn default btn-sm btn-form-add">
							<i class="fa fa-exchange"></i> Switch </button>
						</div>
					</div>
					<div class="portlet-body">
                        <table style='margin-top:10px' class='table-qty gray-bg ' id="general-table" width='100%'>
							<thead class='thead'>
								<tr>
									<th style='width:100px;'>
										<div style="text-align:right">Bulan</div>
										<div style="text-align:left">Warna</div>
									</th>
									<!-- <th>Qty</th> -->
									<!-- <th>Keterangan</th>
									<th>History</th> -->
									<?foreach ($periode_now as $date) {
                                        $bg = ($date->format('Y-m') == date('Y-m') ? 'background-color:lightgreen' : '' );
                                        $t = ($date->format('Y-m') == date('Y-m') ? 'BULAN BERJALAN' : '' );?>
										<th style='<?=$bg?>' class="text-center nama-bulan <?=($date->format('Y-m') == date('Y-m') ? "bj-view" : '');?> idx-<?=date("Y-m-01" ,strtotime($date->format('Y-m-d').' -1 year'));?>">
                                            <span class='year-now'><?=$date->format("M y");?></span> 
                                            <span class='year-before' hidden>
											<?=date("M y" ,strtotime($date->format('Y-m-d').' -1 year'));?>
											</span> 
                                            
                                        </th>
									<?}?>
									<th class='text-right'>TOTAL</th>
								</tr>
							</thead>
							<thead>
								<tr>
									<th>TOTAL</th>
									<?$grand_total = 0; $grand_total_ori = 0;
									$grand_total_berjalan = 0;
									// print_r($total_jual_berjalan);
									foreach ($periode as $date) {
                                        $i = $date->format('n'); 
										$key_now = ($date->format('Y')+1).'-'.$date->format('m');
                                        $bg = ($key_now == date('Y-m') ? 'background-color:lightgreen' : '' );
										$showTotal = $qty_total_bulan[$date->format('Y-m')];
										$showBerjalan = 0;
										if (isset($total_jual_berjalan[$key_now])) {
											$showBerjalan = $total_jual_berjalan[$key_now];
											// print_r($total_jual_berjalan);
										} 
										if($key_now == date('Y-m') && isset($total_bulan_berjalan[$key_now])){
                                            $total_bj = $total_bulan_berjalan[$key_now];
                                            // $qty_bj = $qty_jual;
                                        }
										$grand_total += $qty_total_bulan[$date->format('Y-m')];
										$grand_total_ori += $qty_total_bulan_ori[$date->format('Y-m')];
										$grand_total_berjalan += $showBerjalan;
										?>
										<th class="text-center <?=($key_now == date('Y-m') ? "bj-view" : '');?> nama-bulan idx-<?=$date->format('Y-m-01');?>" id="totalBulan-<?=$date->format('Y-m')?>" style="font-size:1.1em;<?=$bg?>">
											<span class='total-forecast'><?=number_format($showTotal,'0',',','.');?></span>
											<span class='total-ori' hidden><?=number_format($qty_total_bulan_ori[$date->format('Y-m')],'0',',','.');?></span>
											<span class='total-berjalan' hidden><?=number_format($showBerjalan,'0',',','.');?></span>
                                            
											<?/* if ($key_now == date('Y-m')) {?>
                                                <div class='bj-div'>
                                                    <?=$total_bj;?>
                                                    <div id="bj-detail"></div>
                                                </div>
                                            <?} */?>
										</th>
									<?}?>
									<th style="font-size:1.1em; text-align:right">
										<span id='grand-total'><?=number_format($grand_total,'0',',','.')?></span> 
										<span id='grand-total-ori' hidden><?=number_format($grand_total_ori,'0',',','.')?></span> 
										<span id='grand-total-berjalan' hidden><?=number_format($grand_total_berjalan,'0',',','.')?></span> 
									</th>
								</tr>
							</thead>
							<tbody>
								<?foreach ($data_warna as $row) {?>
									<tr id="row-<?=$row->warna_id;?>" class='row-active'>
										<td class='nama-warna' id='idx-<?=$row->warna_id;?>'><?=$row->warna_jual?></td>
										<?$total_ori = 0;$total_berjalan =0 ;
										$today = date("Y-m");
										foreach ($periode as $date) {
											$key = $date->format('Y-m');

											$qtyOri = '';
											$qtyShow = '';
											$otherList = "";
											$key_now = ($date->format('Y')+1).'-'.$date->format('m');

                                            $bgn = ($key_now == date('Y-m') ? 'background-color:lightgreen' : '' );
											$qty_show = 0;
											$qty_jual_berjalan = 0;
											$disabled='';

											if (isset($qty_jual[$row->warna_id][$key_now]) ) {
												$qty_jual_berjalan=$qty_jual[$row->warna_id][$key_now];

												$dateTarget = date("Y-m", strtotime("$key_now-01"));
												if ($today >= $dateTarget) {
													$disabled='readonly';
												}
												
											}
											if(isset($qty_jual[$row->warna_id][$key_now]) && $key_now == date('Y-m')){
                                                $qty_bj = $qty_jual[$row->warna_id][$key_now];
                                            }

                                            // echo $key.' '.$row->warna_id;
                                            // echo '<br/>';
                                            // echo($qty_show);
                                            // echo '<br/>';
                                            // echo date("Y-m", strtotime($key.' + 1 year'));
                                            // echo '<hr/>';


											if (isset($created_history_data[$key][$row->warna_id]) && $created_history_data[$key][$row->warna_id] != '-') {

                                                if (!isset($qty_show) || $qty_show == 0) {
                                                    $qty_show=(isset($qty_data[$key][$row->warna_id]) ? $qty_data[$key][$row->warna_id] : 0);
                                                }
												$qtyList = explode('??', $qty_history_data[$key][$row->warna_id]); 
												$tglList = explode('??', $created_history_data[$key][$row->warna_id]);
												$idList = explode('??', $history_id_data[$key][$row->warna_id]);
												foreach ($qtyList as $key => $value) {
													if ($key == 0) {
														$qtyOri = $value;
														if($disabled != '' ){
															// $qtyShow.='- Qty Ori:'.number_format($qtyOri,'0',',','.').'<br/>';
														}
													}else if ($key < 4){
														$bg = '';
														if ($key == 0) {
															$bg = "background:#ddd";
														}
														$qtyShow .= "- <span style='$bg'>".date("d/m/y", strtotime($tglList[$key-1])).':'.number_format((float)$value,'0',',','.').' '.(is_posisi_id()==1 ? $idList[$key-1] : '').'</span><br/>';
													}else{
														$otherList .= '- '.date("d/m/y", strtotime($tglList[$key-1])).':'.number_format((float)$value,'0',',','.').' '.(is_posisi_id()==1 ? $idList[$key-1] : '').'<br/>';
													}
												}
											}

											if ($qtyOri == '') {
												$qtyOri = (isset($qty_data[$key][$row->warna_id]) ? $qty_data[$key][$row->warna_id] : 0);
											}
											$total_ori += $qtyOri;
											$total_berjalan += $qty_jual_berjalan;

											if (is_posisi_id()==1) {
												// echo $total_ori;
											}


											$i = $date->format('n');
											$key = $date->format('Y-m');
											$total_trx_warna_all[$row->warna_id] += $qty_show ;
											?>
											<!-- onfocusout="focusOut('<?=$row->warna_id?>','<?=$key?>-01')" onclick="showHistory('<?=$row->warna_id?>','<?=$key?>-01','1')"  -->
											<td style='<?=$bgn?>' class='data-container <?=($key_now == date('Y-m') ? "bj-view" : '');?> ' 
												title='double click to edit' 
												onclick="focusOut('<?=$row->warna_id?>','<?=$key?>-01')" 
												id="container-<?=$date->format('Y-m-01')?>-<?=$row->warna_id?>" >
												
												<input class='amount_number input-forecast<?=($bgn == '' ? $disabled : '');?>'  data-period="<?=$key?>" data-warna="<?=$row->warna_id?>" readonly id='inputWarna-<?=$key?>-01-<?=$row->warna_id?>' onfocus="showHistory('<?=$row->warna_id?>','<?=$key?>-01','1')" onfocusout="cekIfEmpty('<?=$key?>-01','<?=$row->warna_id?>')" onchange="updateForecast('<?=$key?>-01','<?=$row->warna_id?>')" value="<?=number_format($qty_show,'0',',','.');?>">
												<span class='qty-ori' style='padding-right:5px; color:navy' hidden ><?=number_format($qtyOri,'0',',','.')?></span>
												<span class='qty-berjalan' style='padding-right:5px;' hidden ><?=number_format($qty_jual_berjalan,'0',',','.')?></span>
												<div hidden class='additional' id="additional-<?=$date->format('Y-m-01')?>-<?=$row->warna_id?>">
													<textarea class='keterangan-input' readonly id="input-<?=$date->format('Y-m-01')?>-<?=$row->warna_id?>" onfocus="showHistory('<?=$row->warna_id?>','<?=$date->format('Y-m-01')?>','1')" focusout="focusOutKeterangan()" maxlength='200' onchange="updateKeterangan('<?=$key?>-01','<?=$row->warna_id?>')" placeholder='keterangan' rows='3'><?=(!isset($keterangan_data[$key][$row->warna_id]) || $keterangan_data[$key][$row->warna_id] == '-' ? '' : $keterangan_data[$key][$row->warna_id]);?></textarea><br/>
													<?=$qtyShow;
													/*if ($otherList!='') {?>
														<span style='color:#dddd'>
															<?=$otherList;?>
														</span>
													<?}*/?>
													<!-- <span style="color:red;position:absolute; top:5px; right:5px;" onclick="focusOut('<?=$row->warna_id?>','<?=$key?>-01')"> <i class='fa fa-times'></i> </span> -->
												</div>
                                                <?/* if ($key_now == date('Y-m')) {?>
                                                    <div class='bj-div'>
                                                        <?=$qty_bj;?>
                                                    </div>
                                                <?} */?>
											</td>
										<?}?>
										<th style="font-size:1.1em; text-align:right" id="totalWarna-<?=$row->warna_id;?>">
											<span class='total-forecast'><?=number_format($total_trx_warna_all[$row->warna_id],'0',',','.');?></span>
											<span class='qty-ori' hidden><?=number_format($total_ori,'0',',','.')?></span>
											<span class='qty-berjalan' hidden><?=number_format($total_berjalan,'0',',','.')?></span>
                                        </th>
									</tr>
								<?}?>
							</tbody>
							
						</table>
					</div>					
				</div>
			</div>

			<div class="col-md-12">
				<div class="portlet light">
					<div class="portlet-title">
						<div class="caption caption-md">
							<i class="icon-bar-chart theme-font hide"></i>
							<span class="caption-subject theme-font bold uppercase">Forecasting <?=$tahun;?></span>
						</div>
					</div>
					<div class="portlet-body">
						<?if (is_posisi_id() <= 3) {?>
							<div class="row list-separated">
								<div col='col-xs-12'>
									<div id="sales_statistics_total" class="chart" style="height: 300px;">
									</div>
								</div>
							</div>
							
						<?}?>
					</div>
				</div>


				<div class="portlet light">
					<div class="portlet-title">
						<div class="caption caption-md">
							<i class="icon-bar-chart theme-font hide"></i>
							<span class="caption-subject theme-font bold uppercase">Chart Forecasting <?=$tahun;?></span>
						</div>
					</div>
					<div class="portlet-body">
						<h4><b><?=$tahun;?></b> </h4>
						<?if (is_posisi_id() <= 3) {?>
							<div class="row list-separated">
								<?foreach ($data_warna as $row) {?>
									<div class="col-xs-3" id="chart-<?=$row->warna_id;?>" style="">
										<h4><b><?=$row->warna_jual;?></b> </h4>
										<div id="statistics_<?=$row->warna_id?>" class="chart" style="height:150px;<?=($kode_warna[$row->warna_id] == '#FFF' || $kode_warna[$row->warna_id] == '#FFFFFF' ? 'background-color:#ddd' : 'background-color:transparent')?>">
										</div>
									</div>
								<?}?>
								6
							</div>
							
						<?}?>
					</div>

					<span id='barang_id_data' hidden><?=$barang_id;?></span>
					<span id='tahun_data' hidden><?=$tahun;?></span>	
				</div>

				<div class="portlet light">
					<div class="portlet-body">
						<?if (is_posisi_id() <= 3) {?>
							<div class="row list-separated">
								<div class="col-xs-12">
									<div style='position:relative'>
										<h4><b><?=$tahun;?></b> </h4>
										<div id="sales_statistics_trx" class="chart" style="height: 1000px;">
										</div>

										<div id="dataContainer">
											<h3 class='title'></h3>
											<table>

											</table>
										</div>
									</div>
								</div>
							</div>
							
						<?}?>
					</div>
					
				</div>
			</div>
		</div>
	</div>			
</div>
<div id='overlay-div' hidden style="left:0px; top:0px; position:fixed; height:100%; width:100%; background:rgba(0,0,0,0.5)">
	<p style="position:relative;color:#fff;top:40%;left:40%">Loading....</p>
</div>


<script src="<?php echo base_url('assets/global/plugins/datatables/media/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js'); ?>" type="text/javascript"></script>


<script src="<?=base_url('assets/global/plugins/morris/morris.min.js');?>" type="text/javascript"></script>
<script src="<?=base_url('assets/global/plugins/morris/raphael-min.js');?>" type="text/javascript"></script>


<script src="<?=base_url('assets/global/plugins/amcharts/amcharts/amcharts.js');?>" type="text/javascript"></script>
<script src="<?=base_url('assets/global/plugins/amcharts/amcharts/serial.js');?>" type="text/javascript"></script>
<script src="<?=base_url('assets/global/plugins/amcharts/amcharts/themes/light.js');?>" type="text/javascript"></script>
<script src="<?=base_url('assets/global/plugins/amcharts/amcharts/pie.js');?>" type="text/javascript"></script>



<script>
    let data_trx = [];
    let data_trx_warna =[];
    let dtWarna = [];
    let indexShow = '';
    let getIndex = '';
    let dataShow = '';
    let qtyHistory = [];
    let createdHistory = [];
    let forecast = [];
    let data_trx_bulan = [];
    let max_trx_bulan = [];
    let totalBulan = [];
	let qty_jual = [];

    <?foreach ($periode as $date) {?>
    	totalBulan["<?=$date->format('Y-m')?>"] = 0;
    <?}?>

	var pencet = 1;

jQuery(document).ready(function() {
	$("#barang-list-select").select2();

	let idx = 0;
    let monthKey = [];
    let monthKeyWarna = [];
    let warna_bulan_show = [];
    let lineClassName = [];
    let line_colors = [];
    let warna_count = 0;

    $('.date-picker-month').datepicker({
        autoclose : true,
        format: "MM yyyy"
    });


    <?$max_trx_bulan = [];$idx= 0 ;
    foreach ($periode as $date) {
		$i = $date->format('n'); 
    	array_push($max_trx_bulan, $qty_total_bulan[$date->format('Y-m')]);?>
    	data_trx_bulan.push({
    		'period' : "<?=$date->format('Y-m-d');?>",
    		'qty':<?=$qty_total_bulan[$date->format('Y-m')]?>    		
    	});
    <?};?>

    <?foreach ($color_list as $key => $value) {?>
    	// line_colors.push("<?=$value?>");
    <?}
    foreach ($data_warna as $row) {
    	$max_trx_warna[$row->warna_id][0] = 0;?>
    	warna_bulan_show["<?=$row->warna_jual;?>"] = true;
    	monthKey.push("<?=$row->warna_jual;?>");
    	lineClassName.push("dtw-<?=$row->warna_id;?>");
    	warna_count++;
    	data_trx_warna[<?=$row->warna_id?>] = [];
    	monthKeyWarna[<?=$row->warna_id?>] = ["<?=$row->warna_jual;?>"];
    	monthKeyWarna[<?=$row->warna_id?>].push('average');
    	monthKeyWarna[<?=$row->warna_id?>].push('penjualan');
    	dtWarna["<?=$row->warna_jual;?>"] = <?=$row->warna_id;?>;
    	forecast["<?=$row->warna_id;?>"] = [];

    	line_colors.push("<?=$kode_warna[$row->warna_id];?>");

    <?}?>
    // console.log(line_colors);

    <?$max_trx[0] = 0;

	foreach ($data_show as $key => $value) {?>
    	period = "<?=$key.'-01';?>";
    	data_trx.push({
            'period': period,
            <?foreach ($data_warna as $row) {
            	array_push($max_trx, (isset($qty_data[$key][$row->warna_id]) ? ceil($qty_data[$key][$row->warna_id]) : 0 ));?>
            	"<?=$row->warna_jual;?>" : parseFloat(<?=(isset($qty_data[$key][$row->warna_id]) ? ceil($qty_data[$key][$row->warna_id]) : 0 );?>),
            <?}?>
        });

        <?foreach ($data_warna as $row) {
			$key_now = date('Y-m', strtotime($key ." +1 year"));
        	array_push($max_trx_warna[$row->warna_id], (isset($qty_data[$key][$row->warna_id]) ? $qty_data[$key][$row->warna_id] : 0 ));
        	array_push($max_trx_warna[$row->warna_id], (isset($qty_jual[$row->warna_id][$key_now]) ? $qty_jual[$row->warna_id][$key_now] : 0 ));
			?>
			// console.log('kN',"<?=$key_now;?>");

        	data_trx_warna[<?=$row->warna_id?>].push({
        		'period':period,
        		"<?=$row->warna_jual;?>" : parseFloat(<?=(isset($qty_data[$key][$row->warna_id]) ? $qty_data[$key][$row->warna_id] : 0 );?>),
        		'average' : parseFloat(<?=$qty_rate[$key][$row->warna_id];?>),
				'penjualan' : <?=(isset($qty_jual[$row->warna_id][$key_now]) ? $qty_jual[$row->warna_id][$key_now] : 0 );?>
        	});

        	forecast["<?=$row->warna_id?>"]["<?=$key?>-01"] = parseFloat(<?=(isset($qty_data[$key][$row->warna_id]) ? $qty_data[$key][$row->warna_id] : 0 );?>);


	        <?if (!isset($qty_history_data[$key][$row->warna_id])) {
	        	$qty_history_data[$key][$row->warna_id] = 0;
	        	$created_history_data[$key][$row->warna_id] = '-';
	        };?>
	        if (typeof qtyHistory[period] === 'undefined') {
	        	qtyHistory[period] = [];
	        	createdHistory[period] = [];
	        };
	        qtyHistory[period]['<?=$row->warna_id?>'] = "<?=$qty_history_data[$key][$row->warna_id];?>";
	        createdHistory[period]['<?=$row->warna_id?>'] = "<?=$created_history_data[$key][$row->warna_id];?>";
        // <?}?>
    	
        idx++;

    <?}?>

    // console.log('forecast', forecast);
    // console.log('history', qtyHistory);
    // console.log('dt', data_trx);
    console.log('dtw', data_trx_warna);

    dataShow = data_trx;

    $('#sales_statistics_trx').click(function(e){
    	// console.log(e);
    	let left = (e.offsetX > 900 ? 900 : e.offsetX);

    	$(`#dataContainer`).css({
    		"left": left + 'px',
            "top": (e.clientY-150) + 'px'
    	});
    	$('#dataContainer table').empty();
    	indexShow = getIndex;
	    // console.log('iS',indexShow);
	    let period = '';
    	$.each(dataShow[indexShow], function(i,v){
		    if (i == 'period') {
    			$('#dataContainer .title').text(v);
    			period = v;
    		};
    	})

    	$.each(dataShow[indexShow], function(i,v){

    		if (i != 'period') {
	    		if (qtyHistory[period][dtWarna[i]] != 0) {
	    				    			
	    		};
    			$('#dataContainer table').append(`
    				<tr>
    				<td>${i}</td>
    				<td><input id='input-${period}-${dtWarna[i]}' onfocus="showHistory('${dtWarna[i]}','${period}','0')" onchange="updateForecast('${period}','${dtWarna[i]}')" style='text-align:right' value='${change_number_format(v)}'> </td>
    				<td><input name='keterangan' autocomplete='off'></td>
    				<td style='position:relative'>
    					<div style='position:absolute; padding:10px; background:#ddd;  color:black' class='history-container' id='history-${period}-${dtWarna[i]}'>${qtyHistory[period][dtWarna[i]]} ${createdHistory[period][dtWarna[i]]}</div>
    				</td></tr>`);
    		}
    	})
    });

	
	<?$max_trx_stat = max($max_trx);?>
	let config_trx = {
		element:'sales_statistics_trx',
		data: data_trx,
		xkey: 'period',
		ykeys: monthKey,
		ymax: <?=CEIL($max_trx_stat/pow(10,strlen($max_trx_stat)-1)) * pow(10,strlen($max_trx_stat)-1);?>,
		labels: monthKey,
		barColors: '#ff0000',
		stacked : true,
		fillOpacity: 1,
		hideHover: 'false',
		behaveLikeLine: false,
		resize: true,
		pointFillColors:['#ffffff'],
		pointStrokeColors: line_colors,
		lineColors:line_colors,
	    hoverCallback: function(index, options, content, row) {
	    	// console.log(index);
	    	// console.log(options);
	    	// console.log(content);
	    	// console.log(row);
	    	// getIndex = index;

	    	/*let idx = 0
			let contentHtml = `<table>`;
			let sortable_dt = [];
			$.each(row, function(i,v){
				if (warna_bulan_show[i]) {
					if (idx == 0) {
						contentHtml += `<tr><td class='text-center' colspan='3'><b>${v}</b></td></tr>`;
					}
					else{
						sortable_dt.push([i,v]);
					}
				};
				idx++;
			});

			sortable_dt.sort(function(a,b){
				return b[1] - a[1]
			});

			for (var i = 0; i < sortable_dt.length; i++) {
				contentHtml += `<tr><td class='text-right'>${sortable_dt[i][0]}</td> <td style='padding:0 10px'>:</td> <td data-index='${i}' class='text-left'><input value="${change_number_format(sortable_dt[i][1])}"></td></tr>`;
			};
			contentHtml += '</table>';*/
	        return "testing";
	    },
	};

	Morris.Line(config_trx);

	<?foreach ($data_warna as $row) {
		$max_warna = max($max_trx_warna[$row->warna_id]);
		$max_warna = ceil($max_warna); ?>
		console.log(monthKeyWarna[<?=$row->warna_id?>]);
		let config_trx_<?=$row->warna_id;?> = {
			element:'statistics_<?=$row->warna_id;?>',
			data: data_trx_warna[<?=$row->warna_id?>],
			xkey: 'period',
			ykeys: monthKeyWarna[<?=$row->warna_id?>],
			// ymax: <?=max($max_trx_warna[$row->warna_id]);?>,
			ymax: <?=CEIL($max_warna/pow(10,strlen($max_warna)-1)) * pow(10,strlen($max_warna)-1);?>,
			labels: monthKeyWarna[<?=$row->warna_id?>],
			fillOpacity: 1,
			hideHover: 'false',
			behaveLikeLine: true,
			resize: true,
			// pointFillColors:['#ffffff'],
			pointStrokeColors: ["<?=$kode_warna[$row->warna_id];?>", "#808080","#fa9dbb"],
			lineColors:["<?=$kode_warna[$row->warna_id];?>","#808080","#fa9dbb"],
		    hoverCallback: function(index, options, content, row) {
		    	console.log('content',content);
		    	return content;
		    }
		};

		// console.log(config_trx_<?=$row->warna_id;?>);
		Morris.Line(config_trx_<?=$row->warna_id;?>);
	<?}?>

	// Morris.Line(config_trx_54);

	// $(".data-container").click(function(){
	// 	$(this).find(".additional").css("display",'block');
	// })

	//====================================================================================

	// console.log('dtb',data_trx_bulan);
	<?$max_tb = max($max_trx_bulan);?>
	let config_trx_bulan = {
		element:'sales_statistics_total',
		data: data_trx_bulan,
		xkey: 'period',
		ykeys: ['qty'],
		ymax: <?=CEIL($max_tb/pow(10,strlen($max_tb)-1)) * pow(10,strlen($max_tb)-1);?>,
		labels: ['qty'],
		fillOpacity: 1,
		hideHover: 'false',
		behaveLikeLine: true,
		resize: true,
		// pointFillColors:['#ffffff'],
		pointStrokeColors: ['#000000'],
		lineColors:['#000000'],
	    hoverCallback: function(index, options, content, row) {
	    	// console.log(content);
	    	return content;
	    }
	};

	Morris.Line(config_trx_bulan);
   

	$(".input-forecast").dblclick(function() {
		// alert('y');
		$(this).attr('readonly', false);
		$(this).focus();
		// $(`#additional-${period}-${warna_id}`).show();

	});

	$(".keterangan-input").dblclick(function() {
		// alert('y');
		$(this).attr('readonly', false);
		$(this).focus();
		// $(`#additional-${period}-${warna_id}`).show();

	});

	$(document).on('click','.page-content',function(e) {
		// console.log($(e.target).closest('table').attr('id'));
		if ($(e.target).closest('table').attr('id') != "general-table") {
			$(".input-forecast").attr("readonly",true);
			$(".additional").hide();
			$(".nama-bulan").removeClass('barang-on-edit');
			$(".nama-warna").removeClass("barang-on-edit");
			// $('.data-container').css("border","1px solid #ddd");	
			// return false;
		};
		// alert(e.target);
	})

	//=======================================================

	// var map1 = {18: false};
	$(document).keyup(function(e) {
		// console.log(e.keyCode);
	    if (e.keyCode == 18) {
	    	e.preventDefault();
		    switchView();
	    }
	})

	//=======================================================
    $(".bj-view").mouseenter(function(){
        $(".bj-div").show();
    }).mouseleave(function(){
        $(".bj-div").hide();
    });

});

function switchView() {
	pencet++;
	if(pencet > 3)
		pencet = 1

	if (pencet == 1) {
		notific8("teal", "Forecast By Input User");
		$('#general-table').addClass('gray-bg');
		$('#general-table').removeClass('blue-bg');
		$('#general-table').removeClass('orange-bg');
		$(".qty-ori").hide();
		$("#ori-title").hide();
		$(".total-ori").hide();
		$("#berjalan-title").hide();
		$(".qty-berjalan").hide();
		$(".total-berjalan, #grand-total-berjalan").hide();
		$("#forecast-title").show();
		$(".input-forecast, .input-forecastreadonly, .total-forecast, #grand-total").show();
		$('.year-before').hide();
		$('.year-now').show();

	}else if(pencet == 2){
		notific8("tangerine", "Data By Penjualan Berjalan");
		$('#general-table').removeClass('gray-bg');
		$('#general-table').removeClass('blue-bg');
		$('#general-table').addClass('orange-bg');
		$(".qty-ori").hide();
		$("#ori-title").hide();
		$("#forecast-title").hide();
		$(".qty-berjalan").show();
		$("#berjalan-title").show();
		$(".total-berjalan, #grandt-total-berjalan").show();
		$(".input-forecast, .input-forecastreadonly, .total-forecast, .total-ori, #grand-total, #grand-total-ori").hide();

		$('.year-before').hide();
		$('.year-now').show();
		
	} else if(pencet == 3){
		notific8("lime", "Data By Penjualan Tahun Lalu");
		$('.year-before').show();
		$('.year-now').hide();

		$('#general-table').removeClass('gray-bg');
		$('#general-table').addClass('blue-bg');
		$('#general-table').removeClass('orange-bg');
		$(".qty-ori").show();
		$("#ori-title").show();
		$(".total-ori").show();
		$(".qty-berjalan").hide();
		$("#berjalan-title").hide();
		$(".total-berjalan, #grand-total-berjalan").hide();
		$("#forecast-title").hide();
		$(".input-forecast, .input-forecastreadonly, .total-forecast, #grand-total").hide();

	} ;
	// console.log('uy');

	// $("#switchBtn").toggleClass("green");
	// $("#switchBtn").toggleClass("blue");
}

function hideWarna(warna_id) {
	$(`#warnaSelector-${warna_id}`).toggleClass('non-active');
	$(`#row-${warna_id}`).toggleClass('row-nonactive');
	$(`#row-${warna_id}`).toggleClass('row-active');
	$(`#chart-${warna_id}`).toggle();

	updateTotalBulan();
}

function updateTotalBulan () {
	$("#overlay-div").show();
	let totalAll = 0;
	<?foreach ($periode as $date) {?>
    	totalBulan["<?=$date->format('Y-m')?>"] = 0;
    <?}?>

	$('.row-active').each(function(){
		$(this).find('.input-forecast').each(function() {
			let period = $(this).attr('data-period');
			let qty = reset_number_format($(this).val());
			// console.log(period,'==',qty);
			totalBulan[period] += parseFloat(qty);
			totalAll += parseFloat(qty);
		})
	});
	// console.log(totalBulan);

	$("#grand-total").text(change_number_format(totalAll));
	<?foreach ($periode as $date) {?>
    	$("#totalBulan-<?=$date->format('Y-m')?>").text(change_number_format(totalBulan["<?=$date->format('Y-m')?>"]));
    <?}?>
	$("#overlay-div").hide();

}

function updateForecast(period, warna_id){
	// notific8('ruby',`${period}, ${warna_id}`);
	let qty = $(`#inputWarna-${period}-${warna_id}`).val();
	// alert(qty);
	if (qty != '' && qty !== 'undefined') {
		// alert(forecast[warna_id][period]);
		$(`#history-${period}-${warna_id}`).hide();
		var data_st = {};
		const url = "master/data_forecasting_update";
		data_st['period'] = period;
		data_st['warna_id'] = warna_id;
		data_st['barang_id'] = "<?=$barang_id?>";
		data_st['qty'] = qty;
		
		ajax_data_sync(url,data_st).done(function(data_respond  ,textStatus, jqXHR ){
			// console.log(data_respond);
			if (textStatus == 'success') {
				notific8("lime","data updated");
				forecast[warna_id][period] = qty;
			};
		});
	}else{
		// alert(forecast[warna_id][period]);
		$(`#inputWarna-${period}-${warna_id}`).val(forecast[warna_id][period]);
	};
}

function updateKeterangan(period, warna_id){
	// notific8('ruby',`${period}, ${warna_id}`);
	$(`#history-${period}-${warna_id}`).hide();
	var data_st = {};
	const url = "master/data_forecasting_update_keterangan";
	data_st['period'] = period;
	data_st['warna_id'] = warna_id;
	data_st['barang_id'] = "<?=$barang_id?>";
	data_st['keterangan'] = $(`#input-${period}-${warna_id}`).val();
	
	ajax_data_sync(url,data_st).done(function(data_respond  ,textStatus, jqXHR ){
		// console.log(textStatus);
		if (textStatus == 'success') {
			notific8("lime","keterangan updated");
		};
	});

	$(`#history-${period}-${warna_id}`).hide();

}

function cekIfEmpty(period, warna_id) {
	let qty = $(`#inputWarna-${period}-${warna_id}`).val();
	// console.log(`#inputWarna-${period}-${warna_id}`);
	if (qty == '' || qty == 'undefined') {
		// alert(qty);
		$(`#inputWarna-${period}-${warna_id}`).val(forecast[warna_id][period]);
	}

	// focusOut(warna_id, period);
}

function showHistory(warna_id, period, tipe){
	// alert('yu');
	$('.history-container').hide();
	$('.additional').hide()
	$(".nama-bulan").removeClass('barang-on-edit');
	$(".nama-warna").removeClass("barang-on-edit");
	$('.data-container').css("border","1px solid #ddd");
	// $(".input-forecast").attr("readonly",true);
	// $(".keterangan-input").attr("readonly",true);

	$(`#history-${period}-${warna_id}`).show();
	if (parseFloat(tipe) == 1) {
		$(".additional").hide();
		// console.log($(`#inputWarna-${period}-${warna_id}`).closest(".data-container").html());
		// $(`#inputWarna-${period}-${warna_id}`).closest(".data-container").addClass("data-selected");
		let row = $(`#inputWarna-${period}-${warna_id}`).closest('tr');
		row.find('td').eq(0).addClass("barang-on-edit");
		$(`.idx-${period}`).addClass("barang-on-edit");
		$(`#additional-${period}-${warna_id}`).show();
		$(`#container-${period}-${warna_id}`).css("border","2px solid black");
		// $(`#input-${period}-${warna_id}`).css("border:2px solid #000");

		// $(`#inputWarna-${period}-${warna_id}`).closest('.data-container').find(".additional").css("display",'block');
	};
}

function focusOut(warna_id, period){
	// $(`#inputWarna-${period}-${warna_id}`).closest(".data-container").removeClass("data-selected");
	if ( $(`#inputWarna-${period}-${warna_id}`).is(":focus") == false && $(`#input-${period}-${warna_id}`).is(":focus") == false ) {
		// console.log("yes");
		$(".input-forecast").attr("readonly",true);
		$(".keterangan-input").attr("readonly",true);
		$(".additional").hide();
		$(".nama-bulan").removeClass('barang-on-edit');
		$(".nama-warna").removeClass("barang-on-edit");
		$('.data-container').css("border","1px solid #ddd");
	};
	// $(`.idx-${parseFloat(brk[1])}`).removeClass("barang-on-edit");
	
}

</script>
