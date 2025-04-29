<?php echo link_tag('assets/global/plugins/jqvmap/jqvmap/jqvmap.css'); ?>
<?php echo link_tag('assets/global/plugins/morris/morris.css'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css'); ?>"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css'); ?>"/>

<style type="text/css">
.container{
	width: 100%;
}

#dataContainer{
	position: absolute;
	top:0px;
	left: 0px;
	padding: 15px;
	background: rgba(64,64,64,0.8);
	color: white;
}

#dataContainer input{
	color: black;
	padding-left: 5px;
	max-width: 100px;
}

.table-qty tr th{
	border: 2px solid #000;
	padding: 5px;
	/*padding-right: 10px;*/
}

.table-qty tr td{
	border: 2px solid #000;
	padding: 5px 10px 10px 5px;
	/*padding-right: 10px;*/
}

.table-qty input{
	border: none;
	background: transparent;
}

.table-qty tr:nth-child(2n){
	background: #eee;
}

.barang-on-edit{
	background: yellow;
}

.keterangan-input{
	/*display:none;*/
	text-align:left; 
	width: 100%;
}

/*.keterangan-input:focus{
	display: block;
}*/

.data-container{
	position: relative;
	text-align: right;
	width:90px;
}

/*.data-container:hover .additional{
	display: block;
}*/

.stok-display{
	position: relative;
}

.stok-display:hover .additional{
	display: block;
}

.data-selected{
	border: 2px solid orange !important;
}

.additional{
	position: absolute;
	padding: 5px;
	background: #ffff88;
	color: black;
	top: 0px;
	left: 100%;
	z-index: 9999;
	display: none;
	width: 150px;
	text-align:left;
}

.health-bar{
	position: absolute;
	bottom: 4px;
	left: 0px;
	height: 5px;
	width: 100%;
	margin: 0px;
	padding: 0px;
}

.performance-bar{
	background: #a3cbf7;
	position: absolute;
	font-size: 0.8em;
	top: 0px;
	left: 0px;
	height: 100%;
	margin: 0px;
	padding: 0px;
	text-align: center;
	display: none;
	color: rgba(0,0,0,0.5);
}

.data-container:hover .performance-bar{
	display: block;
}

.stok-bar{
		background: #c6ffb8;
		display: inline;
		position: absolute;
		top: 0px;
		left: 0px;
		margin: 0px;
		padding: 0px;
		font-size: 0.5em;
		color:#c6ffb8;
	}

	.empty-bar{
		/* background: #ff928a; */
		background: red;
		position: absolute;
		display: inline;
		top: 0px;
		left: 0px;
		margin: 0px;
		padding: 0px;
		font-size: 0.5em;
		color:transparent;
	}
	.request-bar{
		display: inline;
		position: absolute;
		display: inline;
		top: 0px;
		left: 0px;
		/* background: #99ddff; */
		margin: 0px;
		padding: 0px;
		font-size: 0.5em;
		background:rgba(255,200,0,0.7);
		/* background: #ff928a; */
		text-align:center;
		color:transparent;
	}

	.outstanding-bar{
		display: inline;
		position: absolute;
		display: inline;
		top: 0px;
		left: 0px;
		background: #999;
		margin: 0px;
		padding: 0px;
		font-size: 0.5em;
		text-align:center;
		color:transparent;
	}

	.perf-bar-container{
		position: absolute;
		bottom: 0px;
		height:8px;
		left:0px;
		overflow:hidden;
		z-index: 9;
	}


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
		$bulan_berjalan=(float)date("m", strtotime($tanggal_start));
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



		// echo $bulan_berjalan;
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
			$qty_stok[$row->warna_id] = 0;
			$qty_stok_data[$row->warna_id] = '';
			$qty_outstanding[$row->warna_id] = 0;
			$qty_jual[$row->warna_id] = [];
			$jml_trx[$row->warna_id] = [];
		}

		// foreach ($data_penjualan as $row) {
		// 	if (is_posisi_id()==1) {
		// 		print_r($row);
		// 		echo '<hr/>';
		// 	}
		// }

		$data_jual = (array)$data_penjualan;
		$data_show = array();
		foreach ($data_jual as $key => $value) {
			$data_show[$value->bulan_jual] = $value;
			$total_trx[$value->bulan_jual] = 0;
			$penjualan_id_list[$value->bulan_jual] = array();
		}

		
		foreach ($data_show as $i => $value) {
			$warna_id_list = explode(",", $value->warna_id);
			// $penjualan_data[$i] = explode(",", $value->penjualan_data);
			// $count_trx_data[$i] = explode("=?=", $value->penjualan_id);
			$qty_data[$i] = explode(",", $value->qty_data);
			
			$qty_history[$i] = explode(",", $value->qty_history);
			$created_history[$i] = explode(",", $value->history_created);

			// foreach ($count_trx_data[$i] as $key2 => $value2) {
			// 	$break = explode("??", $value2);
			// 	foreach ($break as $key3 => $value3) {
			// 		$break2 = explode(",", $value3);
			// 		foreach ($break2 as $key4 => $value4) {
			// 			array_push($penjualan_id_list[$i], $value4);
			// 		}
			// 	}
			// }

			if (is_posisi_id() == 1) {
			}
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
			// if (isset($qty_history[$i])) {
				// print_r($qty_history[$i]);
				// echo '<br/>';
				// print_r($warna_id_list);
				// echo '<br/>';
				// echo $i;
				$qty_history_data[$i] = array_combine($warna_id_list, $qty_history[$i]);
				$created_history_data[$i] = array_combine($warna_id_list, $created_history[$i]);
				// echo '<hr/>';
			// }
		}

		

		foreach ($periode as $date) {
		// for ($i=1; $i <= 12 ; $i++) {
			$bln = $date->format("Y-m");
			$qty_total_bulan[$bln] = 0;
			if (!isset($qty_data[$bln])) {
				$qty_data[$bln] = array();	
			}

			foreach ($qty_data[$bln] as $key => $value) {
				$qty_total_data[$key] += $value;
			}
		}


		// foreach ($data_warna as $row) {
		// 	$rate = round($qty_total_data[$row->warna_id]/12);
		// 	for ($i=1; $i <= 12 ; $i++) { 
		// 		$qty_rate[$tahun.'-'.str_pad($i,'2','0', STR_PAD_LEFT)][$row->warna_id] = $rate;
		// 	}
		// }

		foreach ($data_warna as $row) {
			// echo $qty_total_data[$row->warna_id].' / '.$range_tanggal;
			foreach ($periode as $date) {
				$i = $date->format('n');
				$key = $date->format('Y-m');
				
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

				// $qty_total_bulan_ori[$key] += $qtyOri;
			}
			
		}

		
		foreach ($stok_barang as $row) {
			foreach ($this->gudang_list_aktif as $row2) {
				if (isset($qty_stok[$row->warna_id])) {
					$qty_stok[$row->warna_id] += $row->{'gudang_'.$row2->id.'_qty'};
					if ($row->{'gudang_'.$row2->id.'_qty'} > 0) {
						$qty_stok_data[$row->warna_id] .= $row2->nama.' : '.number_format((float)$row->{'gudang_'.$row2->id.'_qty'},'0',',','.').'<br/>';
					}
				}else{
					// echo $row->nama_warna;
				}
			}
		};

		foreach($data_outstanding_po as $row){
			if (!isset($qty_outstanding[$row->warna_id])) {
				$qty_outstanding[$row->warna_id] = 0;
			}
			$qty_outstanding[$row->warna_id] += $row->qty_outstanding;
		};

		foreach ($penjualan_berjalan as $row) {
			// echo $row->warna_id.' '.$row->bulan_berjalan.' '.$row->qty.'<br/>';
			$qty_jual[$row->warna_id][$row->bulan_berjalan]	= $row->qty;
			$jml_trx[$row->warna_id][$row->bulan_berjalan]	= $row->jml_trx;
		}

		foreach ($request_barang as $row) {
			$rb[$row->warna_id] = $row->qty;
			$rbd[$row->warna_id] = explode(',', $row->qty_data);
			$rbb[$row->warna_id] = explode(',', $row->bulan_request);
		}

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
						<table style='font-size:1.2em'>
							<?foreach ($data_barang as $row) { ?>
							<form method='get'>
								<tr>
									<td>
										Tanggal
									</td>
									<td class='padding-rl-25'>
										:
									</td>
									<td>
										<input name='tanggal_start' style="width:150px; text-align:center" class='date-picker-month' value="<?=date('F Y', strtotime(is_date_formatter($tanggal_start)));?>"> 
										to  <input name='tanggal_end'  style="width:150px; text-align:center" class='date-picker-month' value="<?=date('F Y', strtotime(is_date_formatter($tanggal_end)));?>" >
									</td>
								</tr>
								<tr>
									<td>
										Nama Beli
									</td>
									<td class='padding-rl-25'>
										:
									</td>
									<td>
										<select name='id' id="barang-list-select" style='width:200px'>
											<?$nama_jual = ''; $deskripsi_info='';
											foreach ($this->barang_list_aktif as $row2) {?>
												<option value="<?=($row2->id)?>" <?=($barang_id == $row2->id ? 'selected' : '');?> ><?=$row2->nama?></option>
											<?}?>
										</select>
										<button class='btn btn-xs default' type='submit'>OK</button>
										<span hidden style='font-size:1.3em'><?=$row->nama;?></span>
									</td>
								</tr>
								<tr>
									<td>
										Nama Jual
									</td>
									<td class='padding-rl-25'>
										:
									</td>
									<td style='font-size:1.3em'>
										<?=$row->nama_jual;?>
									</td>
								</tr>
								<tr>
									<td style='vertical-align:top' >Deskripsi</td>
									<td class='padding-rl-25' style='vertical-align:top'>
										:
									</td>
									<td>
										<?=$row->deskripsi_info?>
									</td>
								</tr>
							</form>
							<?}?>
							<tr hidden>
								<td style='vertical-align:top' >Warna Terdaftar</td>
								<td class='padding-rl-25' style='vertical-align:top'>
									:
								</td>
								<td>
									<div style='column-count:4'>
										<?foreach ($data_warna as $row) { 
											$qty_warna[$row->warna_id] = 0;?>
											- <?=$row->warna_jual;?> <br/>
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
							<span class="caption-subject theme-font bold uppercase">Health Planner
							<button class="btn btn-xs default" onclick="viewBarToggler()">SHOW BAR</button>
							</span>
						</div>
					</div>
					<div class="portlet-body">
							KETERANGAN : 
							<button class="btn btn-xs default" onclick="showKeterangan()"><b id='keterangan-btn-text-show'>SHOW <i class='fa fa-caret-down'></i></b><b id='keterangan-btn-text-hide' style='display:none'>HIDE <i class='fa fa-caret-up'></i></b></button>
							<ul id="keterangan-div" style='display:none'>
								<li>
									tanda <b style='color:red'>*</b> berarti ada penjualan<br/>
								</li>
								<li>
									klik <button class="btn btn-xs default" onclick="viewBarToggler()"><b>alt</b></button> atau <button class="btn btn-xs default" onclick="viewBarToggler()"><span id='keterangan-btn-text-show'>SHOW BAR</span><span id='keterangan-btn-text-hide' style='display:none'>HIDE</span></button>
									untuk memunculkan bar request dll
								</li>
								<li>
									Keterangan BAR : 
									<ul style="list-style-type:none; margin-left:0px;padding-left:10px;">
										<li>
											<div style='height:10px; width:100px;background: #c6ffb8;display:inline-block'></div> : STOK 
										</li>
										<li>
											<div style='height:10px; width:100px;background:#999;display:inline-block'></div> : OUTSTANDING
										</li>
										<li>
											<div style='height:10px; width:100px;background:rgba(255,200,0,0.7);display:inline-block'></div> : REQUEST
										</li>
										<li>
											<div style='height:10px; width:100px;background:#ff928a;display:inline-block'></div> : NO REQUEST, NO OUTSTANDING
										</li>
									</ul>
								</li>
							</ul>
							<br/>
							HIDDEN COLUMN : <span id='hidden-column'></span>
							<br/>
							HIDDEN WARNA : 
							<?foreach ($data_warna as $row) {?>
								<button 
									style="display:<?=($row->status_planner == 1 ? 'none' : 'inline-block')?>" 
									id="btnStatus-<?=$row->warna_id?>" 
									class='btn btn-sm' 
									onclick="showWarna('<?=$row->warna_id?>', '<?=$barang_id;?>')"
									>
									<?=$row->warna_jual;?> <i class='fa fa-times'></i>
								</button>
							<?}?>
							<table class='table-qty' id='general-table' width='100%' style='margin-top:10px'>
							<?
							$total_stok = 0 ; $total_outstanding = 0; 
							$tr = array(); $total_request_all= 0;
							foreach ($data_warna as $row) {
								$total_stok += $qty_stok[$row->warna_id];
								$total_outstanding += $qty_outstanding[$row->warna_id];
								$tr[$row->warna_id] = 0;
								if (isset($rb[$row->warna_id])) {
									$tr[$row->warna_id] = $rb[$row->warna_id];
									$total_request_all += $rb[$row->warna_id];
								}
							}

							$total_count_request = 0;
							$index_date = 0;
							$index_col = 1;
							?>
							<thead  class='thead'>
								<tr>
									<th>Warna/bulan</th>
									<th class='text-center' style='width:100px' rowspan='2'><button title="hide" class="btn btn-link" onclick="hideColumn('<?=$index_col++?>','Stok')">Stok <i class="fa fa-toggle-down"></i></button> </th>
									<th class='text-center' style='width:100px' rowspan='2'><button title="hide" class="btn btn-link" onclick="hideColumn('<?=$index_col++?>','Outstanding')">Outstanding <i class="fa fa-toggle-down"></i></button> </th>
									<th class='text-center' style='width:130px' rowspan='2'><button title="hide" class="btn btn-link" onclick="hideColumn('<?=$index_col++?>','Total')">Total <i class="fa fa-toggle-down"></i></button></th>
									<th class='text-center' style='width:100px' rowspan='2'><button title="hide" class="btn btn-link" onclick="hideColumn('<?=$index_col++?>','Request')">Request <i class="fa fa-toggle-down"></i></button></th>
									<?
									foreach ($periode_now as $date) {
									// for ($i=$bulan_berjalan; $i <= 12 ; $i++) { ?>
										<th  style='min-width:7%; max-width:10%' class='text-center idx-<?=$i;?>'><button title="hide" class="btn btn-link" onclick="hideColumn('<?=$index_col++?>',`<?=$date->format('M y');?>`)"> <?=$date->format('M y');?> <i class="fa fa-toggle-down"></i></button></th>
									<?}?>
									<th class='text-center'>TOTAL</th>
								</tr>
							</thead>
							<thead>
								<tr>
									<th>TOTAL</th>
									<th class='text-center' style="font-size:1.1em;" > <?=number_format($total_stok,'0',',','.')?> </th>
									<th class='text-center' style="font-size:1.1em;" ><?=number_format($total_outstanding,'0',',','.')?> </th>
									<th class='text-center' style="font-size:1.1em;" ><?=number_format($total_outstanding+$total_stok,'0',',','.')?> </th>
									<th class='text-center' style="font-size:1.1em;" ><?=number_format($total_request_all,'0',',','.')?> </th>
									<?$grand_total = 0;
									foreach ($periode as $date) {
										$i = $date->format('n'); 
										$grand_total += $qty_total_bulan[$date->format('Y-m')];?>
										<th class="text-center nama-bulan idx-<?=$date->format('Y-m-01');?>" id="totalBulan-<?=$date->format('Y-m')?>" style="font-size:1.1em;"><?=number_format($qty_total_bulan[$date->format('Y-m')],'0',',','.');?></th>
									<?}?>
									<th style="font-size:1.1em; text-align:center" id='grand-total'><?=number_format($grand_total,'0',',','.')?></th>
								</tr>
							</thead>
							<tbody>
								<?$total_stok = 0 ; $total_outstanding = 0;
								foreach ($data_warna as $row) {?>
									<tr id="row-<?=$row->warna_id;?>" <?=($row->status_planner == 0 ? 'hidden' : '' )?> >  
										<td id='idx-<?=$row->warna_id;?>' style="position:relative">
											<?=$row->warna_jual?> <?=(is_posisi_id()==1 ? $row->warna_id : '')?>
											<a class="hidden-print" style="cursor:pointer;position:absolute; right:10px;" onclick="hideWarna('<?=$row->warna_id?>', '<?=$barang_id;?>')" >hide</a>
										</td>
										<td  class='text-right stok-display'>
											<?=number_format($qty_stok[$row->warna_id],'0',',','.');
											$total_stok += $qty_stok[$row->warna_id];?>
											<div class='additional'>
												<?=$qty_stok_data[$row->warna_id];?>
											</div>
										</td>
										<td class='text-right' style='position:relative'><?=number_format($qty_outstanding[$row->warna_id],'0',',','.');$total_outstanding += $qty_outstanding[$row->warna_id]; ?></td>
										<td class='text-right' style="font-weight:bold; font-size:1.05em"><?=number_format($qty_outstanding[$row->warna_id]+$qty_stok[$row->warna_id],'0',',','.');?></td>
										<td class='text-center' style="font-size:1.05em"><?=number_format($tr[$row->warna_id],'0',',','.');?></td>
										<?
										
										$stok_bar = $qty_stok[$row->warna_id];
										$outstanding_bar = $qty_outstanding[$row->warna_id];

										$width_cell = '90px';
										$total_outstanding_width = 0;
										$total_request_width = 0;
										$total_empty_width = 0;
										$total_stok_width = 0;
										$width_per_month[$row->warna_id] = [];
										$width_o_per_month[$row->warna_id] = [];
										$first_date = '';
										$stok_now = $stok_bar;
										$perf_bar_width = 0;

										$total_forecast = 0;
										$t_forecast[$row->warna_id] = 0;
										// $total_request_all = 0;
										$trn = 0;
										if (isset($tr[$row->warna_id])) {
											$trn = $tr[$row->warna_id];
										}
										$total_count_request = 0;
										$index_date = 0;

										$sisa_stok_now = $qty_stok[$row->warna_id];
										$sisa_outstanding_now = $qty_outstanding[$row->warna_id];
										$sisa_request_now = $trn;


										foreach ($periode as $date) {
											$perf_bar_width++;
											$i = $date->format("Y-m");
											$key = $i;
											$tahun_now = ($date->format('Y')+1).'-'.$date->format('m');
											$qty_jual_now = (isset($qty_jual[$row->warna_id][$tahun_now]) ? $qty_jual[$row->warna_id][$tahun_now]  : 0 ); 
		
											$qty_show = 0;
											if (isset($created_history_data[$key][$row->warna_id]) && $created_history_data[$key][$row->warna_id] != '-') {
		
												$qty_show=(isset($qty_data[$key][$row->warna_id]) ? $qty_data[$key][$row->warna_id] : 0);
											}
											// $forecast_now = (isset($qty_data[$key][$row->warna_id]) ? $qty_data[$key][$row->warna_id] - $qty_jual_now : 0);
											$forecast_now = $qty_show - $qty_jual_now;
		
											if (isset($forecast_now) && $forecast_now > 0 ) {
												$total_count_request = ($index_date+1);
		
												if ($sisa_stok_now > 0) {

													$width_per_month[$row->warna_id][$index_date] = $sisa_stok_now / $forecast_now;
													$width_per_month[$row->warna_id][$index_date] = ($width_per_month[$row->warna_id][$index_date] > 1 ? 1 : $width_per_month[$row->warna_id][$index_date]);
													$total_stok_width += $width_per_month[$row->warna_id][$index_date];
													$forecast_sisa = $forecast_now - $sisa_stok_now;
													$sisa_stok_now -= $forecast_now;
													if ($sisa_stok_now <= 0) {
														$sisa_stok_now = 0;
													}
		
													if ($sisa_stok_now <= 0 && $forecast_sisa > 0 && $sisa_outstanding_now > 0) {
														$w = $sisa_outstanding_now / $forecast_sisa;
														if ($w > 1) {
															$w = $forecast_sisa/$forecast_now;
														}
														
														$w = ($w > 1 ? 1 : $w);
														$total_outstanding_width += $w;
														$sisa_outstanding_now -= $forecast_sisa;
													}
		
													if ($sisa_stok_now <= 0 && $forecast_sisa > 0 && $sisa_request_now > 0) {
														
														$w = $sisa_request_now / $forecast_sisa;
														if ($w > 1) {
															$w = $forecast_sisa/$forecast_now;
														}
														

														
														$w = ($w > 1 ? 1 : $w);
														$total_request_width += $w;
														$sisa_request_now -= $forecast_sisa;
														if ($sisa_request_now <= 0) {
															$sisa_request_now = 0;
														}
													}

												}else{
													if($sisa_outstanding_now > 0){
														$w = $sisa_outstanding_now / $forecast_now;
														$w = ($w > 1 ? 1 : $w);
														$total_outstanding_width += $w;
														$sisa_outstanding_now -= $forecast_now;
														if ($sisa_outstanding_now <= 0) {
															$sisa_outstanding_now = 0;
														}
														// $total_outstanding_width += 
													}
		
													if($sisa_request_now > 0){
														$w = $sisa_request_now / $forecast_now;
														
														$w = ($w > 1 ? 1 : $w);
														$total_request_width += $w;
														$sisa_request_now -= $forecast_now;
														if ($sisa_request_now <= 0) {
															$sisa_request_now = 0;
														}
														// $total_request_width += 
													}
		
		
		
												}

											}else{
												if($sisa_stok_now > 0){
													$total_stok_width += 1;
												}else{
													if($sisa_outstanding_now > 0){
														$total_outstanding_width += 1;
													}
		
													if($sisa_request_now > 0){
														$total_request_width += 1;
													}
												}
		
											}

										}

										foreach ($periode as $date) {
										// for ($i=$bulan_berjalan; $i <= 12 ; $i++) {
											$i = $date->format("Y-m");
											$key = $i;
											$tahun_now = ($date->format('Y')+1).'-'.$date->format('m');
											$qty_jual_now = (isset($qty_jual[$row->warna_id][$tahun_now]) ? $qty_jual[$row->warna_id][$tahun_now]  : 0 ); 
											$j_trx = (isset($jml_trx[$row->warna_id][$tahun_now]) ? $jml_trx[$row->warna_id][$tahun_now]  : 0 ); 

											$qty_show = 0;
											if (isset($created_history_data[$key][$row->warna_id]) && $created_history_data[$key][$row->warna_id] != '-') {

												$qty_show=(isset($qty_data[$key][$row->warna_id]) ? $qty_data[$key][$row->warna_id] : 0);
											}
											// $forecast_now = (isset($qty_data[$key][$row->warna_id]) ? $qty_data[$key][$row->warna_id] - $qty_jual_now : 0);
											$forecast_now = $qty_show - $qty_jual_now;
											
											$qty_f = (isset($qty_data[$key][$row->warna_id]) ? $qty_data[$key][$row->warna_id] : 0);
											$performance_value = ($qty_f != 0 ? $qty_jual_now/$qty_f : 0);
											// if ($qty_f != 0) {
											// 	// echo $qty_jual_now.' /'.$qty_f.'<br/>';
											// 	# code...
											// }
											// $outstanding_bar_width = 0;
											// $stok_bar_width = 0;
											// if ($stok_bar > 0) {
											// 	if ($forecast_now > 0) {
											// 		$stok_bar_width = round($stok_bar/$forecast_now,2);
											// 	}else{
											// 		$stok_bar_width = 1;
											// 	}
											// 	$stok_bar_width = ($stok_bar_width > 1 ? 1 : $stok_bar_width);
											// }

											// if ($stok_bar_width < 1 && $outstanding_bar > 0) {
											// 	if ($forecast_now > 0) {
											// 	 	$outstanding_bar_width = round($outstanding_bar/$forecast_now,2);
											// 	}else{
											// 		$outstanding_bar_width = 1;
											// 	}

											//  	$outstanding_bar_width = ($outstanding_bar_width + $stok_bar_width > 1 ? 1-$stok_bar_width : $outstanding_bar_width);
											//  	$outstanding_bar = ($outstanding_bar + $stok_bar > $forecast_now ? $outstanding_bar - ($forecast_now - $stok_bar) : $outstanding_bar - $forecast_now);
											// 	if ($stok_bar_width > 0) {
											// 		$outstanding_bar_width += $stok_bar_width;
											// 	}
											// }
											// $stok_bar = ($forecast_now < 0 ? $stok_bar : $stok_bar - $forecast_now);											
											// $stok_bar = ($stok_bar < 0 ? 0 : $stok_bar);

											// $empty_bar_width = 0;
											// if($qty_stok[$row->warna_id] + $qty_outstanding[$row->warna_id] == 0){
											// 	$empty_bar_width = 1;
											// }
											// $outstanding_bar = ($outstanding_bar < 1 ? 0 : $outstanding_bar);

											$total_trx_warna_all[$row->warna_id] += (isset($qty_data[$key][$row->warna_id]) ? ($forecast_now <0 ? 0 : $forecast_now) : 0);

											?> 
											<td class='data-container' onclick="showHistory('<?=$row->warna_id?>','<?=$key?>')" style="position:relative">
												<div class='performance-bar performance-bar-toggle' style="width:<?=($performance_value  <= 1 ? CEIL($performance_value*100) : 100);?>%"><?=($performance_value > 0  ? ($performance_value <= 1 ? round($performance_value*100,1) : 100) .'%' : '');?></div>
												<?//=$tanggal_start.'=='.date('Y-m-d',strtotime($date->format("Y-m-d").'+1year'))?>
												<?if($tanggal_start == date('Y-m-d',strtotime($date->format("Y-m-d").'+1year'))){
													$left_empty = ($total_outstanding_width > $total_request_width ? $total_outstanding_width : $total_request_width);
													$total_width = $total_stok_width + $left_empty;
													if ($total_width < $perf_bar_width) {
														$total_empty_width += $perf_bar_width - $total_width;
													}
													// echo round($total_request_width,2).' '.round($total_outstanding_width,2) ;
													?>
													<div class='perf-bar-container' data-width='<?=$perf_bar_width?>' style="width:<?=($perf_bar_width*90)-2?>px;">
														<div class='stok-bar' data-width='<?=$total_stok_width?>' style='text-align:left;width:<?=$total_stok_width*90;?>px'><?=$total_stok_width;?></div>
														<div class='outstanding-bar' data-width='<?=$total_outstanding_width?>' style='left:<?=$total_stok_width*90;?>px;width:<?=$total_outstanding_width*90;?>px'><?=$total_outstanding_width;?></div>
														<div class='request-bar' data-width='<?=$total_request_width?>' style='left:<?=$total_stok_width*90;?>px;width:<?=$total_request_width*90;?>px; display:none'><?=$total_request_width;?></div>
														<div class='empty-bar' data-width='<?=$total_empty_width?>' style="left:<?=($total_stok_width*90) + ($left_empty *90) ;?>px;width:<?=$total_empty_width*90;?>px"><?=$total_empty_width;?></div>
													</div>
												<?}?>

												
												<!-- <input class='amount-number' id='inputWarna-<?=$key?>-01-<?=$row->warna_id?>' onfocusout="focusOut('<?=$row->warna_id?>','<?=$key?>-01')" onfocus="showHistory('<?=$row->warna_id?>','<?=$key?>-01','1')" onchange="updateForecast('<?=$key?>-01','<?=$row->warna_id?>')" style='text-align:left; width:60px; text-align:right' value="<?=(isset($qty_data[$key][$row->warna_id]) ? number_format((float)$qty_data[$key][$row->warna_id],'0',',','.') : 0)?>"> -->
												<span style='color:red'><?=($qty_jual_now != 0 ? '*' : '');?></span>
												<span ><?=(isset($qty_data[$key][$row->warna_id]) ? number_format((float)$forecast_now ,'0',',','.') : 0)?></span>
												<div class='additional' id="additional-<?=$key?>-<?=$row->warna_id?>">
													Forecast : <?=number_format($qty_f,'0',',','.'); ?><br/>
													Jual : <?=number_format($qty_jual_now,'0',',','.'); ?><br/>
													Transaksi : <?=number_format($j_trx,'0',',','.'); ?>
												</div>
											</td>
										<?}?>
										<th style="font-size:1.1em; text-align:center"><?=number_format($total_trx_warna_all[$row->warna_id],'0',',','.');?></th>
									</tr>
								<?}?>
							</tbody>
							<tfooter>
								<tr style="font-size:1.1em;">
									<th>TOTAL</th>
									<th  class='text-center'><?=number_format($total_stok,'0',',','.');?></th>
									<th class='text-center'><?=number_format($total_outstanding,'0',',','.');?></th>
									<th class='text-center'><?=number_format($total_outstanding+$total_stok,'0',',','.');?></th>
									<th class='text-center'><?=number_format($total_request_all,'0',',','.');?></th>
									<?$grand_total = 0;
									foreach ($periode as $date) {
										$i=$date->format('Y-m');
									// for ($i=$bulan_berjalan; $i <= 12 ; $i++) {
										$grand_total += $qty_total_bulan[$i];?>
										<th class='text-center idx-<?=$i;?>' style="font-size:1.1em;"><?=number_format($qty_total_bulan[$i],'0',',','.');?></th>
									<?}?>
									<th style="font-size:1.1em;"  class='text-center'><?=number_format($grand_total,'0',',','.')?></th>
								</tr>
								<!-- <tr>
									<th>Warna/bulan</th>
									<?for ($i=1; $i <= 6 ; $i++) { ?>
										<th class='text-center idx-<?=$i;?>'><?=date('F', strtotime($tahun.'-'.str_pad($i, 2,'0', STR_PAD_LEFT).'-01'));?></th>
									<?}?>
								</tr> -->
							</tfooter>
						</table>
					</div>
					
				</div>
			</div>

		</div>
	</div>			
</div>

<script src="<?php echo base_url('assets/global/plugins/datatables/media/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js'); ?>" type="text/javascript"></script>


<script src="<?=base_url('assets/global/plugins/morris/morris.min.js');?>" type="text/javascript"></script>
<script src="<?=base_url('assets/global/plugins/morris/raphael-min.js');?>" type="text/javascript"></script>


<script src="<?=base_url('assets/global/plugins/amcharts/amcharts/amcharts.js');?>" type="text/javascript"></script>
<script src="<?=base_url('assets/global/plugins/amcharts/amcharts/serial.js');?>" type="text/javascript"></script>
<script src="<?=base_url('assets/global/plugins/amcharts/amcharts/themes/light.js');?>" type="text/javascript"></script>
<script src="<?=base_url('assets/global/plugins/amcharts/amcharts/pie.js');?>" type="text/javascript"></script>

<script src="<?php echo base_url('assets/global/plugins/bootbox/bootbox.min.js'); ?>" type="text/javascript"></script>


<script>
    let data_trx = [];
    let data_trx_warna =[];
    let dtWarna = [];
    let indexShow = '';
    let getIndex = '';
    let dataShow = '';
    let qtyHistory = [];
    let createdHistory = [];
	var isHealthBarShow = false;
	var viewBarIndex = 2;

	const mainTabel = $(`#general-table`);


jQuery(document).ready(function() {

	$("#barang-list-select").select2();


	let idx = 0;
    let monthKey = [];
    let monthKeyWarna = [];
    let warna_bulan_show = [];
    let lineClassName = [];
    let line_colors = [];
    let warna_count = 0;

    $(document).on('click','.page-content',function(e) {
		// console.log($(e.target).closest('table').attr('id'));
		if ($(e.target).closest('table').attr('id') != "general-table") {
			$(".additional").hide();
		};
		// alert(e.target);
	})

	//=========================================================

		$(document).keyup(function(e) {
			if (e.keyCode == 18) {
				e.preventDefault();
				viewBarToggler();
			}
		})
    
});


function viewBarToggler(){
	viewBarIndex++;
	if (viewBarIndex != 2) {
		viewBarIndex = viewBarIndex%2;
	}
	isHealthBarShow = !isHealthBarShow;
	// if (isHealthBarShow) {
	// 	$(".perf-bar-container").show();
	// }else{
	// 	$(".perf-bar-container").hide();
	// }

	if (viewBarIndex == 1) {
		$(".request-bar").show();
	}else{
		$(".request-bar").hide();
	}
}

function showKeterangan(){
	$("#keterangan-div").toggle();
	$("#keterangan-btn-text-show").toggle();
	$("#keterangan-btn-text-hide").toggle();
}

function showHistory(warna_id, periode) {
	$('.data-container .additional').hide();
	$(`#additional-${periode}-${warna_id}`).show();
}

function hideWarna(warna_id, barang_id){

	const dialog = bootbox.dialog({
		message: `<p class="text-center mb-0">updating...<i class="fa fa-spin fa-cog"></i> </p>`,
		closeButton: false
	});

	fetch(baseurl+`master/planner_warna_hide?barang_id=${barang_id}&warna_id=${warna_id}&status=0`)
    .then((response) => response.json())
    .then((data) => {
		if(data == "OK"){
			dialog.modal('hide');
			document.querySelector(`#btnStatus-${warna_id}`).style.display='inline-block';
			// document.querySelector(`#row-${warna_id}`).style.display='none';
			document.querySelector(`#row-${warna_id}`).hidden=true

		}else{
			dialog.modal('hide');
			bootbox.dialog("error");
		}
    });
}

function showWarna(warna_id, barang_id){
	const dialog = bootbox.dialog({
		message: `<p class="text-center mb-0">updating...<i class="fa fa-spin fa-cog"></i> </p>`,
		closeButton: false
	});

	fetch(baseurl+`master/planner_warna_hide?barang_id=${barang_id}&warna_id=${warna_id}&status=1`)
	.then((response) => response.json())
	.then((data) => {
		if(data == "OK"){
			dialog.modal('hide');
			document.querySelector(`#btnStatus-${warna_id}`).style.display='none';
			document.querySelector(`#row-${warna_id}`).hidden=false;
		}else{
			dialog.modal('hide');
			bootbox.dialog("error");
		}
	});
}

function hideColumn(index, colName){
	index++;
	mainTabel.find(`th:nth-child(${index}), td:nth-child(${index})`).hide();

	const hiddenColumnButton = document.createElement('button');
	hiddenColumnButton.className = 'btn btn-xs default';
	hiddenColumnButton.innerText = `Show ${colName}`;
	hiddenColumnButton.style.marginRight = '5px';
	hiddenColumnButton.onclick = function() {
		mainTabel.find(`th:nth-child(${index}), td:nth-child(${index})`).show();
		hiddenColumnButton.remove();
	};
	document.getElementById('hidden-column').appendChild(hiddenColumnButton);
}

</script>
