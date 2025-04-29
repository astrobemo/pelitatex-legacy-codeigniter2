<link href="<?=base_url('assets/global/plugins/bootstrap-modal/css/bootstrap-modal.css');?>" rel="stylesheet" type="text/css"/>


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
	padding: 5px;
	/*padding-right: 10px;*/
}

.table-qty tr td{
	padding: 5px 10px 10px 5px;
	/*padding-right: 10px;*/
}

.table-qty-border tr td, .table-qty-border tr th{
	border: 2px solid #333;
}

.table-qty-border-2 tr td, .table-qty-border-2 tr th{
	border: 2px solid #ccc;
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

.data-container:hover .performance-bar-toggle{
	display: block;
}

.stok-bar{
	background: #c6ffb8;
	position: absolute;
	display: inline;
	top: 0px;
	left: 0px;
	margin: 0px;
	padding: 0px;
	font-size: 0.5em;
}

.outstanding-bar{
	display: inline;
	position: absolute;
	display: inline;
	top: 0px;
	left: 0px;
	background: #758283;
	margin: 0px;
	padding: 0px;
	font-size: 0.5em;
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


.data-container-select{
	cursor: pointer;
}

.data-container:hover .additional-show-history{
	/*display: block;*/
}

.request-selected{
	background-color: yellow;
}

#general_table2 tr td{
	vertical-align: middle;
}

/*.additional input:focus{
	display: block;
}

.additional:hover{
	display: block;
}*/

#tanggalRequestAktif{
	/*border: none;*/
	width: 120px;
	/*border-bottom: 1px solid #333;*/
}

#tanggalRequestAktif:focus{
}

@media print {

	* {
		-webkit-print-color-adjust: exact !important;
		print-color-adjust: exact !important;
	}

	a[href]:after {
    	content: none !important;
  	}

  	table{
  		font-size: 14px;
  	}

  	.nama-bulan{
  		background: #d4e3ff !important;
  		/*background: yellow;*/
  	}

}



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
			$color_list[$row->warna_id] = ($row->kode_warna != '' ? $row->kode_warna : '#ccc');
			$total_qty[$row->warna_id]=0;
			$total_amount[$row->warna_id]=0;
			$total_trx_warna_all[$row->warna_id]=0;
			$qty_total_data[$row->warna_id] = 0;
			$qty_stok[$row->warna_id] = 0;
			$qty_stok_update[$row->warna_id] = 0;
			$qty_stok_data[$row->warna_id] = '';
			$qty_outstanding[$row->warna_id] = 0;
			$qty_outstanding_update[$row->warna_id] = 0;
			$qty_jual[$row->warna_id] = [];
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

		$po_number_list = array(); 
		$po_qty_list = array(); 
		foreach($data_outstanding_po as $row){
			$qty_outstanding[$row->warna_id] = $row->qty_outstanding;
			$po_qty[$row->warna_id] = explode(",", $row->po_qty);
			$po_number[$row->warna_id] = explode("??", $row->po_number);
			$batch_tanggal[$row->warna_id] = explode(",", $row->batch_tanggal);
			$batch_id[$row->warna_id] = explode(",", $row->po_pembelian_batch_id);
			$qty_detail[$row->warna_id] = explode(",", $row->qty_data);
			foreach ( $batch_tanggal[$row->warna_id] as $key => $value) {
				$po_number_list[$value][$batch_id[$row->warna_id][$key]] = $po_number[$row->warna_id][$key];
				$po_qty_list[$row->warna_id][$value][$batch_id[$row->warna_id][$key]] = $po_qty[$row->warna_id][$key];
				$po_qty_sisa[$row->warna_id][$value][$batch_id[$row->warna_id][$key]] = $qty_detail[$row->warna_id][$key];
			}
		};

		foreach ($data_outstanding_update as $row) {
			$qty_outstanding_update[$row->warna_id] = $row->qty_outstanding;
		}

		// echo count($qty_outstanding);
		// print_r($qty_outstanding);
		// echo '<hr/>';
		// echo count($qty_outstanding_update);
		// print_r($qty_outstanding_update);

		foreach ($stok_barang_update as $row) {
			foreach ($this->gudang_list_aktif as $row2) {
				if (isset($qty_stok_update[$row->warna_id])) {
					$qty_stok_update[$row->warna_id] += $row->{'gudang_'.$row2->id.'_qty'};
				}
			}
		};

		foreach ($penjualan_berjalan as $row) {
			// echo $row->warna_id.' '.$row->bulan_berjalan.' '.$row->qty.'<br/>';
			$qty_jual[$row->warna_id][$row->bulan_berjalan]	= $row->qty;
		}

		ksort($po_number_list);
		// print_r($po_number_list);
		// echo "<hr/>";
		// print_r($po_qty_list);

		$request_barang_id = ''; $tanggal = ''; $no_batch = ''; $locked_by = ''; $locked_date = '';
		foreach ($request_barang_data as $row) {
			$request_barang_id = $row->id;
			$tanggal = $row->tanggal;
			$no_batch = $row->batch;
			$no_request = $row->no_request;
			$locked_by = $row->locked_by;
			$locked_date = $row->locked_date;
		}

		$nama_jual = ''; $nama_beli = '';
		foreach ($data_barang as $row) {
			$nama_jual = $row->nama_jual;
			$nama_beli = $row->nama;

		}
		?>

		<div class="modal fade  bs-modal-full" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-full">
				<div class="modal-content">
					<div class="modal-body">
						<div class='row'>
							<div class='col-xs-12'>
								<h3>Request Barang <b><?=$nama_beli?></b>
								</h3>
							</div>
							<div class='col-xs-12' id='requestTableContainer'>

							</div>
						</div>

					</div>

					<div class="modal-footer">
						<button type="button" class="btn blue btn-active btn-trigger btn-brg-save" onclick="submitRequest()">Save</button>
						<button type="button" class="btn btn-active default" onclick="savedCheck()" data-dismiss="modal">Close</button>
					</div>
				</div>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
		</div>

		<div class="modal fade" id="portlet-config-new" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body">
						<form action="<?=base_url('master/new_request_insert')?>" class="form-horizontal" id="form_add_data" method="post">
							<h3 class='block'><span class='keterangan-batch' style='color:green'>Batch</span> <span class='keterangan-request'>Request</span>  Baru</h3>
							
                			<input name='type' hidden>
                			<input name='request_barang_id' value="<?=$request_barang_id;?>" hidden>
                			<input name='barang_id' value="<?=$barang_id;?>" hidden>
							<div class="form-group">
			                    <label class="control-label col-md-3">Tanggal
			                    </label>
			                    <div class="col-md-6">
	                    			<input readonly style='cursor:pointer' name='tanggal' class='form-control date-picker' value="<?=date('d/m/Y')?>" >
			                    </div>
			                </div> 

			                <div class="form-group keterangan-batch">
			                    <label class="control-label col-md-3">Batch NO<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
	                    			<input name='no_batch' id='new-batch-no' class='form-control' value="<?=$no_batch +1;?>" readonly>
			                    </div>
			                </div>

			                <div >
			                	<label class="control-label col-md-3">Info
			                    </label>
			                    <div class="col-md-8">
				                	<p  class="keterangan-batch">
				                		- <span style='color:red'>No Request tetap sama</span>, namun no batch di set baru <br/>
				                		- Barang di batch sebelumnya otomatis akan ikut ke batch selanjutnya
				                	</p>
				                	<p class="keterangan-request">
				                		- <span style='color:red'>No Request baru</span>, no batch akan di set ke 1<br/>
				                		- Barang di request selanjutnya TIDAK akan ikut
				                	</p>
			                    </div>
			                </div>

			            </form>

					</div>

					<div class="modal-footer">
						<button type="button" class="btn green btn-active btn-trigger btn-form-save keterangan-batch" onclick="submitNewRequest()">Create New Batch</button>
						<button type="button" class="btn blue btn-active btn-trigger btn-form-save keterangan-request" onclick="submitNewRequest()">Create Fresh Request</button>

						<button type="button" class="btn btn-active default" data-dismiss="modal">Close</button>
					</div>
				</div>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
		</div>


		<div class="row margin-top-10">
			<div class="col-md-12 hidden-print">
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
											<?foreach ($this->barang_list_aktif as $row2) {?>
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
										<?=$nama_jual;?>
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
				<div class="portlet light hidden-print">
					<div class="portlet-title">
						<div class="caption caption-md">
							<i class="icon-bar-chart theme-font hide"></i>
							<span class="caption-subject theme-font bold uppercase">Health Planner</span>
						</div>
						<?//if (is_posisi_id() == 1 ) {?>
							<div class="actions hidden-print">
								<?if ($request_barang_id != '' && $locked_by == '') {?>
									<button onclick="modeToggle()" class="btn btn-default btn-sm">
									Change Mode to <span class='planner' hidden>Planner</span> <span class='request'>Request</span> </button>

									<a href='#portlet-config' data-toggle='modal' onclick="populateBarang()" class="btn btn-default btn-sm"> Daftar PO </a>
								<?}?>
							
							</div>
						<?//}?>
					</div>
					<div class="portlet-body">
						note : <b style='color:red'>*</b> berarti ada penjualan
						<table class='table-qty table-qty-border' id='general-table' width='100%'>
							<?$total_stok = 0 ; $total_outstanding = 0;
							$total_stok_update = 0;  $total_outstanding_update = 0;
							$total_kurang_stok = 0; $total_kurang_po = 0;
							$total_kurang_stok_update = 0; $total_kurang_po_update = 0;
							foreach ($data_warna as $row) {

								foreach ($periode as $date) {
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
										
									$total_trx_warna_all[$row->warna_id] += (isset($qty_data[$key][$row->warna_id]) ? ($forecast_now <0 ? 0 : $forecast_now) : 0);
								
								}

								$total_stok += $qty_stok[$row->warna_id];
								$total_outstanding += $qty_outstanding[$row->warna_id];

								$total_stok_update += $qty_stok_update[$row->warna_id];
								$total_outstanding_update += $qty_outstanding_update[$row->warna_id];

								$kekurangan_stok_update = $total_trx_warna_all[$row->warna_id] - $qty_stok_update[$row->warna_id];
								$kekurangan_stok = $total_trx_warna_all[$row->warna_id] - $qty_stok[$row->warna_id];

								$total_kurang_stok += ($kekurangan_stok < 0 ? 0 : $kekurangan_stok);
								$total_kurang_stok_update += ($kekurangan_stok_update < 0 ? 0 : $kekurangan_stok_update);


								$kekurangan_po_update = $total_trx_warna_all[$row->warna_id] - $qty_stok_update[$row->warna_id] - $qty_outstanding_update[$row->warna_id];
								$kekurangan_po = $total_trx_warna_all[$row->warna_id] - $qty_stok[$row->warna_id] - $qty_outstanding[$row->warna_id];

								$total_kurang_po += ($kekurangan_po < 0 ? 0 : $kekurangan_po);
								$total_kurang_po_update += ($kekurangan_po_update < 0 ? 0 : $kekurangan_po_update);
								
							}?>
							<thead  class='thead'>
								<tr>
									<th rowspan='2'>Warna/bulan</th>
									<th class='text-center' rowspan='2'>Stok</th>
									<th class='text-center' rowspan='2'>Outstanding</th>

									<th class='text-center' colspan='2' style='border-bottom:1px solid black' >Kekurangan</th>
									<!-- <th>History</th> -->
									<?
									foreach ($periode_now as $date) {
									// for ($i=$bulan_berjalan; $i <= 12 ; $i++) { ?>
										<th  style='min-width:7%; max-width:10%' rowspan='2' class='text-center idx-<?=$i;?>'><?=$date->format("M y");?></th>
									<?}?>
									<th  class='text-center' rowspan='2'>TOTAL<br/> Forecast</th>
								</tr>
								<tr>
									<th class='text-center'>Stok</th>
									<th class='text-center'>PO</th>
								</tr>
							</thead>
							<thead>
								<tr>
									<th>TOTAL</th>
									<th class='text-center' style="font-size:1.1em;" > 
										<span class='info-update'><?=number_format($total_stok_update,'0',',','.')?> </span>
										<span class='info-request' hidden><?=number_format($total_stok,'0',',','.')?></span>
									</th>
									<th  class='text-center' style="font-size:1.1em;" >
										<span class='info-update'><?=number_format($total_outstanding_update,'0',',','.')?></span>
										<span class='info-request' hidden><?=number_format($total_outstanding,'0',',','.')?></span>
									</th>
									<th class='text-center'>
										<span class='info-update'><?=number_format($total_kurang_stok_update,'0',',','.')?></span>
										<span class='info-request' hidden><?=number_format($total_kurang_stok,'0',',','.')?></span>
									</th>
									<th class='text-center'>
										<span class='info-update'><?=number_format($total_kurang_po_update,'0',',','.')?></span>
										<span class='info-request' hidden><?=number_format($total_kurang_po,'0',',','.')?></span>
									</th>
									<?$grand_total = 0;
									foreach ($periode as $date) {
										$i = $date->format('n'); 
										$grand_total += $qty_total_bulan[$date->format('Y-m')];?>
										<th class="text-center nama-bulan idx-<?=$date->format('Y-m-01');?>" id="totalBulan-<?=$date->format('Y-m')?>" style="font-size:1.1em;"><?=number_format($qty_total_bulan[$date->format('Y-m')],'0',',','.');?></th>
									<?}?>
									<th style="font-size:1.1em; text-align:right" id='grand-total'><?=number_format($grand_total,'0',',','.')?></th>
								</tr>
							</thead>
							<tbody>
								<?$total_stok = 0 ; $total_outstanding = 0; 
								foreach ($data_warna as $row) {

									$kekurangan_stok_update = $total_trx_warna_all[$row->warna_id] - $qty_stok_update[$row->warna_id];
									$kekurangan_stok = $total_trx_warna_all[$row->warna_id] - $qty_stok[$row->warna_id];

									$kekurangan_stok = ($kekurangan_stok < 0 ? 0 : $kekurangan_stok);
									$kekurangan_stok_update = ($kekurangan_stok_update < 0 ? 0 : $kekurangan_stok_update);

									$kekurangan_po_update = $total_trx_warna_all[$row->warna_id] - $qty_stok_update[$row->warna_id] - $qty_outstanding_update[$row->warna_id];
									$kekurangan_po = $total_trx_warna_all[$row->warna_id] - $qty_stok[$row->warna_id] - $qty_outstanding[$row->warna_id];

									$kekurangan_po = ($kekurangan_po < 0 ? 0 : $kekurangan_po);
									$kekurangan_po_update = ($kekurangan_po_update < 0 ? 0 : $kekurangan_po_update);
									?>
									<tr>
										<td id='idx-<?=$row->warna_id;?>'><?=$row->warna_jual?> <?=(is_posisi_id()==1 ? $row->warna_id : '')?></td>
										<td  class='text-right stok-display'>
											<span class='info-request' hidden>
												<?=number_format($qty_stok[$row->warna_id],'0',',','.');$total_stok += $qty_stok[$row->warna_id];?>
											</span>
											<span class='info-update'>
												<?=number_format($qty_stok_update[$row->warna_id],'0',',','.');?>
											</span>
											<div class='additional'>
												<?=$qty_stok_data[$row->warna_id]; ?>
											</div>
										</td>
										<td class='text-right' style='position:relative'>
											<span class='info-request' hidden>
												<?=number_format($qty_outstanding[$row->warna_id],'0',',','.');$total_outstanding += $qty_outstanding[$row->warna_id]; ?>
											</span> 
											<span class='info-update'>
												<?=number_format($qty_outstanding_update[$row->warna_id],'0',',','.'); ?>
											</span>
										</td>
										<td class='text-center'>
											<span class='info-request' hidden><?=number_format($kekurangan_stok,'0',',','.');?></span> 
											<span class='info-update'><?=number_format($kekurangan_stok_update,'0',',','.');?></span> 
										</td>
										<td class='text-center'>
											<span class='info-request' hidden><?=number_format($kekurangan_po,'0',',','.');?></span> 
											<span class='info-update'><?=number_format($kekurangan_po_update,'0',',','.');?></span> 
										</td>
										<?
											$stok_bar = $qty_stok[$row->warna_id];
											$outstanding_bar = $qty_outstanding[$row->warna_id];
										?>
										<?
										foreach ($periode as $date) {
										// for ($i=$bulan_berjalan; $i <= 12 ; $i++) {
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
											
											$qty_f = (isset($qty_data[$key][$row->warna_id]) ? $qty_data[$key][$row->warna_id] : 0);
											$performance_value = ($qty_f != 0 ? $qty_jual_now/$qty_f : 0);
											if ($qty_f != 0) {
												// echo $qty_jual_now.' /'.$qty_f.'<br/>';
												# code...
											}
											$outstanding_bar_width = 0;
											$stok_bar_width = 0;
											if ($stok_bar > 0) {
												if ($forecast_now > 0) {
													$stok_bar_width = round($stok_bar/$forecast_now,2);
												}else{
													$stok_bar_width = 1;
												}
												$stok_bar_width = ($stok_bar_width > 1 ? 1 : $stok_bar_width);
											}

											if ($stok_bar_width < 1 && $outstanding_bar > 0) {
												if ($forecast_now > 0) {
												 	$outstanding_bar_width = round($outstanding_bar/$forecast_now,2);
												}else{
													$outstanding_bar_width = 1;
												}

											 	$outstanding_bar_width = ($outstanding_bar_width + $stok_bar_width > 1 ? 1-$stok_bar_width : $outstanding_bar_width);
											 	$outstanding_bar = ($outstanding_bar + $stok_bar > $forecast_now ? $outstanding_bar - ($forecast_now - $stok_bar) : $outstanding_bar - $forecast_now);
												if ($stok_bar_width > 0) {
													$outstanding_bar_width += $stok_bar_width;
												}
											}
											$stok_bar = ($forecast_now < 0 ? $stok_bar : $stok_bar - $forecast_now);											
											$stok_bar = ($stok_bar < 0 ? 0 : $stok_bar);
											$outstanding_bar = ($outstanding_bar < 1 ? 0 : $outstanding_bar);

											// $total_trx_warna_all[$row->warna_id] += (isset($qty_data[$key][$row->warna_id]) ? ($forecast_now <0 ? 0 : $forecast_now) : 0);

											//
											?> 

											 <!-- ondblclick="selectData('<?=$row->warna_id?>','<?=$key?>', '<?=(isset($qty_data[$key][$row->warna_id]) ? (float)$forecast_now : 0);?>')" -->
											<td class='data-container' id='cell-<?=$key?>-<?=$row->warna_id?>'>
												<div style="width:100%: height:100%;" onclick="showHistory('<?=$row->warna_id?>','<?=$key?>', '<?=(isset($qty_data[$key][$row->warna_id]) ? (float)$forecast_now : 0);?>')" 
													>
													<div class='performance-bar performance-bar-toggle' style="width:<?=($performance_value  <= 1 ? CEIL($performance_value*100) : 100);?>%"><?=($performance_value > 0  ? ($performance_value <= 1 ? round($performance_value*100,1) : 100) .'%' : '');?></div>
													<div class='health-bar'>
														<?if ($outstanding_bar_width > 0) {?>
															<div class='outstanding-bar' style='width:<?=$outstanding_bar_width*100;?>%' ><?=($outstanding_bar_width-$stok_bar_width)*100;?>%</div>
														<?}?>
														<?if ($stok_bar_width > 0) {?>
															<div class='stok-bar' style='width:<?=$stok_bar_width*100;?>%'><?=$stok_bar_width*100;?>%</div>
														<?}?>
													</div>
													<!-- <input class='amount-number' id='inputWarna-<?=$key?>-01-<?=$row->warna_id?>' onfocusout="focusOut('<?=$row->warna_id?>','<?=$key?>-01')" onfocus="showHistory('<?=$row->warna_id?>','<?=$key?>-01','1')" onchange="updateForecast('<?=$key?>-01','<?=$row->warna_id?>')" style='text-align:left; width:60px; text-align:right' value="<?=(isset($qty_data[$key][$row->warna_id]) ? number_format((float)$qty_data[$key][$row->warna_id],'0',',','.') : 0)?>"> -->
													<span style='color:red'><?=($qty_jual_now != 0 ? '*' : '');?></span>
													<span><?=(isset($qty_data[$key][$row->warna_id]) ? number_format((float)$forecast_now ,'0',',','.') : 0)?></span>
												</div>
												<div class='additional' id="additional-<?=$key?>-<?=$row->warna_id?>">
													Forecast : <?=number_format($qty_f,'0',',','.'); ?><br/>
													Jual : <?=number_format($qty_jual_now,'0',',','.'); ?>
													<div>
														<span onclick="closeAdditional('additional-<?=$key?>-<?=$row->warna_id?>')" style="cursor:pointer;color:red; position:absolute; top:10px; right:10px"> <i class='fa fa-times'></i> </span>
													</div>
												</div>
											</td>
										<?}?>
										<th style="font-size:1.1em; text-align:right"><?=number_format($total_trx_warna_all[$row->warna_id],'0',',','.');?></th>
									</tr>
								<?}?>
							</tbody>
							<tfooter>
								<tr>
									<th>TOTAL</th>
									<th class='text-center' style="font-size:1.1em;" > 
										<span class='info-update'><?=number_format($total_stok_update,'0',',','.')?> </span>
										<span class='info-request' hidden><?=number_format($total_stok,'0',',','.')?></span>
									</th>
									<th  class='text-center' style="font-size:1.1em;" >
										<span class='info-update'><?=number_format($total_outstanding_update,'0',',','.')?></span>
										<span class='info-request' hidden><?=number_format($total_outstanding,'0',',','.')?></span>
									</th>
									<th class='text-center'>
										<span class='info-update'><?=number_format($total_kurang_stok_update,'0',',','.')?></span>
										<span class='info-request' hidden><?=number_format($total_kurang_stok,'0',',','.')?></span>
									</th>
									<th class='text-center'>
										<span class='info-update'><?=number_format($total_kurang_po_update,'0',',','.')?></span>
										<span class='info-request' hidden><?=number_format($total_kurang_po,'0',',','.')?></span>
									</th>
									<?$grand_total = 0;
									foreach ($periode as $date) {
										$i=$date->format('Y-m');
									// for ($i=$bulan_berjalan; $i <= 12 ; $i++) {
										$grand_total += $qty_total_bulan[$i];?>
										<th class='text-center idx-<?=$i;?>' style="font-size:1.1em;"><?=number_format($qty_total_bulan[$i],'0',',','.');?></th>
									<?}?>
									<th style="font-size:1.1em;"><?=number_format($grand_total,'0',',','.')?></th>
								</tr>
							</tfooter>
						</table>
					</div>
					
				</div>

				<div class="portlet light">
					<div class="portlet-title hidden-print">
						<div class="caption caption-md">
							<i class="icon-bar-chart theme-font hide"></i>
							<span class="caption-subject theme-font bold uppercase">Request Barang</span>
						</div>
						<div class="actions hidden-print">
							<?if ($request_barang_id != '' ) {?>
								<button class="btn green btn-sm" onclick="modalNewRequest('2')">
								<i class="fa fa-plus"></i> New Batch </button>
							<?}?>

							<button class="btn btn-default btn-sm" onclick="modalNewRequest('1')">
							<i class="fa fa-plus"></i> New Request </button>

							<?if ($request_barang_id != '' && $locked_by == '') {?>
								<a href='#portlet-config' data-toggle='modal' onclick="populateBarang()" class="btn btn-default btn-sm"> Daftar PO </a>
							<?}?>

						</div>
					</div>
					<div class="portlet-body">

							<?$pre_po = '';
							foreach (get_toko_obj() as $row) {
								$pre_po = $row->pre_po.'-';
							}?>
							<table style='font-size:1.5em'>
								<tr>
									<td>REQUEST NO</td>
									<td style='padding: 0px 10px'> : </td>
									<td><span id='requestNoAktif'><?=$pre_po;?><?=date('y', strtotime($tanggal))?>/42/<?=str_pad($no_batch, '3','0', STR_PAD_LEFT);?></span> </td>
								</tr>
								<tr>
									<td>TANGGAL</td>
									<td style='padding: 0px 10px'> : </td>
									<td><input <?=($locked_by == '' ? '' : 'readonly');?> class="<?=($locked_by == '' ? 'date-picker': '');?>" id="tanggalRequestAktif" value="<?=($tanggal != '' ? is_reverse_date($tanggal) : '')?>" onchange="gantiTanggal()" ></td>
								</tr>
							</table>

						<table class='table table-bordered'>
						<?
						$status_beda = array();
						foreach ($request_barang_qty_all as $row) {
							$status_beda[$row->barang_id][$row->warna_id][$row->bulan_request] = ($no_batch == 1 ? 0 : $row->tipe );
						}
						$month_color = ['lightSalmon','lemonChiffon','lightGreen','thistle','lightGray'];
						$idx_color = 0;
						foreach ($request_barang_detail as $row) {
								$barang_id_get = explode("??", $row->barang_id);
								$nama_barang = explode("??", $row->nama_barang);
								$request_barang_detail_id = explode("??", $row->request_barang_detail_id);
								$po_pembelian_batch_id = explode("??", $row->po_pembelian_batch_id);
								$po_number = explode("??", $row->po_number);
								$warna_id = explode("??", $row->warna_id);
								$nama_warna = explode("??", $row->nama_warna);
								$qty = explode("??", $row->qty);
								$bulan_request = $row->bulan_request;
							?>
							<tr  style="border-top:2px solid #aaa">
								<td colspan='5' class='text-center nama-bulan' style='background:<?=$month_color[$idx_color];?>'><span style='font-size:1.2em'><?=date('F Y', strtotime("+1 year", strtotime($bulan_request)));?> </span> </td>
							</tr>
							<?$idx_color++;?>
							<?foreach ($barang_id_get as $key => $value) {?>
								<?
									$request_barang_detail_id_b = explode(",", $request_barang_detail_id[$key]);
									$po_pembelian_batch_id_b = explode(",", $po_pembelian_batch_id[$key]);
									$po_number_b = explode(",", $po_number[$key]);
									$nama_warna_b = explode(",", $nama_warna[$key]);
									$warna_id_b = explode(",", $warna_id[$key]);

									$qty_b = explode(",", $qty[$key]);
									$warna_id_group = array_unique($warna_id_b);
									$warna_id_count = array_count_values($warna_id_b);
									$idx = 0;
								?>
								<?if ($barang_id == $value) {
									foreach ($po_pembelian_batch_id_b as $key2 => $value2) {
										$bln_r = date('Y-m', strtotime($bulan_request));
										$warna_id_selected[$warna_id_b[$key2]][$bln_r][$value2] = $qty_b[$key2];
									}
								}?>
								<?
									$widList = array();
									$subtotal = array();
									foreach ($warna_id_b as $key2 => $value2) {
										if (!isset($widList[$value2])) {
											$widList[$value2] = array();
											$subtotal[$value2] = 0;
										}

										$subtotal[$value2] += $qty_b[$key2];
										array_push($widList[$value2],array(
											'nama' => $nama_warna_b[$key2],
											'qty' => $qty_b[$key2],
											'po_number' => $po_number_b[$key2],
											'po_pembelian_batch_id' => $po_pembelian_batch_id_b[$key2],
											'request_barang_detail_id' => $request_barang_detail_id_b[$key2]
										));
									}
								?>
									<?foreach ($warna_id_count as $key2 => $value2) {
										$idx2 = 0;
										$tp = (isset($status_beda[$value][$key2][$bulan_request]) ? $status_beda[$value][$key2][$bulan_request] : 0  );
										$classTipe= ( $tp == 1 ? 'background-color:lightblue' : ($tp == 2 ? 'background-color:yellow' : '') );
										foreach ($widList[$key2] as $key3 => $value3) {
											?>
											<tr style="<?=($idx == 0 ? 'border-top:2px solid #aaa' : '')?>">
												<?if ($idx == 0) {?>
													<td rowspan="<?=count($request_barang_detail_id_b)?>"  style="font-size:1.2em; vertical-align:middle" class="text-center">
														<?if ($value == $barang_id) {?>
															<span style='background:yellow'><?=$nama_barang[$key]?></span>  <!-- <span class='badge badge-roundless badge-success'>active</span> -->
														<?}else{?>
															<a href="<?=base_url().is_setting_link('master/barang_planner_2');?>?id=<?=$value;?>"><?=$nama_barang[$key]?></a>
														<?}?>
													</td>
												<?}?>

												<?if ($idx2 == 0) {?>
													<td rowspan="<?=$value2?>"  style="<?=$classTipe?>; font-size:1.2em; vertical-align:middle" class="text-center"><?=$value3['nama']?></td>
												<?}?>
												<td style="<?=$classTipe?>"><?=$value3['po_number']?></td>
												<td style="<?=$classTipe?>"><?=number_format($value3['qty'],'0',',','.');?></td>
												<?if ($idx2 == 0) {?>
													<td rowspan="<?=$value2?>" style="<?=$classTipe?>; font-size:1.2em; vertical-align:middle" class="text-center" ><?=number_format($subtotal[$key2],'0',',','.')?></td>
												<?}?>
											</tr>
										<?$idx2++; $idx++; }?>
									<? }?>
									
								<?}?>
							<?}?>
							<tr style='background-color:#aaa'><td colspan='5'></td></tr>
						</table>
						<div class='text-right hidden-print'>
							<?if ($request_barang_id != '' && $locked_by == '') {?>
								<button type="button" class="btn btn-lg red btn-active btn-trigger btn-lock" onclick="lockRequest('1')">LOCK</button>
							<?}else if($request_barang_id != '' && $locked_by != ''){?>
								<button type="button" class="btn btn-lg blue btn-print" onclick='window.print()'>PRINT</button>
								<button type="button" class="btn btn-lg green btn-active btn-trigger btn-lock" onclick="lockRequest('2')">OPEN</button>
							<?}?>
						</div>
					</div>
					
				</div>
			</div>

		</div>
	</div>			
</div>

<script src="<?php echo base_url('assets/global/plugins/bootstrap-modal/js/bootstrap-modalmanager.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/bootstrap-modal/js/bootstrap-modal.js'); ?>" type="text/javascript"></script>




<script>
	
	var request_barang_id = "<?=$request_barang_id?>";
	var barang_id = "<?=$barang_id;?>";

    let data_trx = [];
    let data_trx_warna =[];
    let dtWarna = [];
    let indexShow = '';
    let getIndex = '';
    let dataShow = '';
    let qtyHistory = [];
    let createdHistory = [];
    var mode = 1;
    var tanggalRequestAktif;
    var onSubmitTime = 2000;
    var isGantiTanggal = true;
    var gantiTanggalOri = "<?=($tanggal != '' ? is_reverse_date($tanggal) : '')?>";
    let barangSelected = {};

    //barangQtySelected = qty barang request / ubahan intinya QTY request barang nya 
	let barangQtySelected = {};
    //barangPoQty = SISA PO original yang available, ga akan berubah kecuali di refresh 
	let barangPoQty = {};
    //barangPoQtyC = container untuk barangPoQty buat dirubah ubah
	let barangPoQtyC = {};
    //barangQty = list barang qty yang diambil tiap po batch jadi VALUE QTY tiap po batch
	let barangQty = {};
    //barangPoBaru = list barang qty po baru otomatis ke isi klo jatah abis
	let barangPoBaru = {};

    //barangPoSelected = 
    let barangPoSelected = {};

    //barangMax = 
	let barangMax = {};
	let namaWarna = {};

	let barangPoNum = [];
	let barangPoNumStatus = [];
	let barangPoNumLengkap = [];
	let barangRequest = [];
	let qtyRequestList = [];
	var today = "<?=date('d/m/Y', strtotime('-1 day'));?>";
	var isSaved = true;

	var dataSend = [];

    <?foreach ($data_warna as $row) {?>
    	namaWarna[`s-<?=$row->warna_id;?>`] = "<?=$row->warna_jual;?>";
    	barangSelected[`s-<?=$row->warna_id;?>`] = {};
    	barangQtySelected[`s-<?=$row->warna_id;?>`] = {};
    	barangPoSelected[`s-<?=$row->warna_id;?>`] = {};
    	barangMax[`s-<?=$row->warna_id;?>`] = {};
    	barangQty[`s-<?=$row->warna_id;?>`] = {};
    	barangPoQty[`s-<?=$row->warna_id;?>`] = {};
    	barangPoBaru[`s-<?=$row->warna_id;?>`] = {};
    	<?foreach ($po_number_list as $key => $value) {
			foreach ($value as $key2 => $value2) {
				if(isset($po_qty_list[$row->warna_id][$key][$key2]) ){?>
					// barangPoNum.push('id-<?=$key2;?>');
					barangPoSelected[`s-<?=$row->warna_id;?>`][`id-<?=$key2;?>`] = 0;
					barangMax[`s-<?=$row->warna_id;?>`][`id-<?=$key2;?>`] = <?=$po_qty_list[$row->warna_id][$key][$key2];?>;
					// barangQty[`s-<?=$row->warna_id;?>`][`id-<?=$key2;?>`] = 0;
					barangPoQty[`s-<?=$row->warna_id;?>`][`id-<?=$key2;?>`] = <?=(ceil($po_qty_sisa[$row->warna_id][$key][$key2]/100) * 100 );?>;
					// console.log(<?=((float)($po_qty_sisa[$row->warna_id][$key][$key2]/100) * 100 );?>);
				<?}
			}
		}
    }

    foreach ($po_number_list as $key => $value) {
		foreach ($value as $key2 => $value2) {?>
			barangPoNum.push('id-<?=$key2;?>');
			barangPoNumStatus.push(1);
			barangPoNumLengkap.push("<?=$value2;?>");
		<?}
	}

	foreach ($request_barang_qty as $row) {
		$bln = date('Y-m',strtotime($row->bulan_request));?>
		barangQtySelected[`s-<?=$row->warna_id?>`][`<?=$bln;?>`] = <?=$row->qty;?>	
		$(`#cell-<?=$bln?>-<?=$row->warna_id?>`).toggleClass('request-selected');
		barangSelected[`s-<?=$row->warna_id;?>`]['<?=$bln;?>'] = 1;
	<?}

	if (isset($warna_id_selected)) {
		foreach ($warna_id_selected as $key => $value) {?>
			//$key  = warna_id
			<?foreach ($value as $bulan => $value2) {?>
				barangQty[`s-<?=$key?>`]['<?=$key2?>'] = {};
				<?foreach ($value2 as $po_batch_id => $value3) {?>
					barangQty[`s-<?=$key?>`]['<?=$key2?>'][`id-<?=$po_batch_id;?>`] = <?=(float)$value3;?>;
				<?}?>
			<?}?>
		<?}
	}

    ?>


jQuery(document).ready(function() {
    console.log('selected',barangSelected);
    console.log('Max',barangMax);
    console.log('Qty',barangQty);
    console.log('po Num',barangPoNum);
    console.log('po qty',barangPoQty);

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
			if (mode == 1) {
				$(".additional").hide();
			};
		};
		// alert(e.target);
	});

	$('body').on('focus','.datepicker_dynamic',function() {
		
		// alert(e.target);
		$(this).datepicker({
	        autoclose : true,
	        format: "dd/mm/yyyy"
	    });
	});
    
});


function modeToggle(){
	mode = (mode % 2)+1;
	$(".planner").toggle();
	$(".request").toggle();
	$(".performance-bar").toggleClass("performance-bar-toggle");
	$(".additional").toggleClass("additional-show-history");
	$(".additional").removeAttr("style");
	$(".table-qty").toggleClass('table-qty-border');
	$(".table-qty").toggleClass('table-qty-border-2');
	$('.data-container').toggleClass('data-container-select');

	if (mode==1) {
		$('.info-request').hide();
		$('.info-update').show();
	}else if(mode == 2){
		$('.info-request').show();
		$('.info-update').hide();
	};
}

function showHistory(warna_id, periode, qty) {
	if (mode == 1) {
		$('.data-container .additional').css('display','none');
		// console.log("id",`#additional-${periode}-${warna_id}`);
		$(`#additional-${periode}-${warna_id}`).show();
	}else if (mode == 2) {
		// $(`#cell-${periode}-${warna_id}`).toggleClass('request-selected');
		selectData(warna_id, periode, qty,1);
	};
}

//=============================================================================================

function modalNewRequest(tipe){
	$("#form_add_data [name=type]").val(tipe);
	if (tipe == 1) {
		$(".keterangan-request").show();
		$(".keterangan-batch").hide();
	}else if (tipe == 2) {
		$(".keterangan-batch").show();
		$(".keterangan-request").hide();
	};

	$("#portlet-config-new").modal("toggle");
}

function selectData(warna_id, periode, qty, tipe){
	$(`#cell-${periode}-${warna_id}`).toggleClass('request-selected');
	// barangPoNum[`id-<?=$key2;?>`] = 0;
	if (typeof barangSelected[`s-${warna_id}`][periode] === 'undefined') {
		barangSelected[`s-${warna_id}`][periode] = 1;
	}else{
		barangSelected[`s-${warna_id}`][periode] = (barangSelected[`s-${warna_id}`][periode] - 1 ) * -1;
	};

	if (barangSelected[`s-${warna_id}`][periode] && qty >= 0) {
		let qtyAdjust = Math.ceil(qty/500) *500;
    	barangQtySelected[`s-${warna_id}`][periode] = qtyAdjust;
	}else{
    	barangQtySelected[`s-${warna_id}`][periode] = 0;
	};

	if (tipe==2) {
		$(`#tr-${warna_id}-${periode}`).remove();
	};
	isSaved = false;
	// populateBarang();
}

function closeAdditional(id){
	// console.log("id",id);
	$(`#${id}`).hide();
}

function populateBarang(){
	// for (var i = 0; i < barangPoNumStatus.length; i++) {
	// 	barangPoNumStatus[i] = 0;
	// };

	$.each(barangPoQty, function(i,v){
		barangPoQtyC[i] = {...v};
	});
	var poAktif = [];
	barangRequest = [];
	// console.log("OK", barangSelected);
	$.each(barangSelected, function(i,v){
		// console.log(v, v.length);
		$.each(v, function(tgl,status){
			let qtyRequest = barangQtySelected[i][tgl];
			barangRequest.push({
				bulan_request : tgl,
				barang_id : barang_id,
				warna_id : i.substring(2),
				qty : qtyRequest 
			});
			var sisa = qtyRequest;
			barangQty[i][tgl] = {} ;
			barangPoBaru[i][tgl] = 0;
			// console.log(barangPoQty[i]);
			$.each(barangPoQtyC[i] , function(i3,v3){
				poAktif[i3] = 1;
				// poAktif.push(i3);
				let reqQty = (sisa > v3 ? v3 : sisa );
				barangQty[i][tgl][i3] = reqQty;

				if (sisa - v3 >= 0) {
					sisa = sisa - v3;
					barangPoQtyC[i][i3] = 0;
				}else{
					barangPoQtyC[i][i3] = v3-sisa;
					sisa = 0
				};
				// console.log(i, i3, barangQty[tgl][i][i3]);
			});
			if (sisa > 0) {
				barangPoBaru[i][tgl] = sisa;
			};
		});
	});

	for (var i = 0; i < barangPoNum.length; i++) {
		// console.log(barangPoNum[i], poAktif[barangPoNum[i]]);
		if (poAktif[barangPoNum[i]] == 1) {
			barangPoNumStatus[i] = 1;
			// $(`#${barangPoNum[i]}`).show();
		}else{
			barangPoNumStatus[i] = 0;
			// $(`#${barangPoNum[i]}`).hide();
		};
	};

	console.log(barangPoQtyC);
	console.log(barangQty);


	drawRequestTableBody();
}

function drawRequestTableBody(){

	// console.log(barangPoNum);
	// $("#general_table2 tbody").html(baris);
	var baris = {};
	dataSend = [];
	var dataPO = {};
	$.each(barangSelected, function(i,v){
		var idx = 0;
		$.each(v, function(tgl, status){
			if (typeof baris[tgl] === 'undefined') {
				baris[tgl] = '';
				dataPO[tgl] = [];
			};

			var warna_id_get = i.substring(2);
			// console.log('wig',warna_id_get, i);

			if (status == 1) {
				baris[tgl] += `<tr ${(v == 0 ? 'hidden' : '')} id="tr-${warna_id_get}-${tgl}"><td>${namaWarna[i]}</td>
					<td style='width:100px; padding:0px; vertical-align:middle'><input style='width:100%; height:100%; border:none; ' onfocus="showPoKeterangan('keterangan-${tgl}-${i}')" onchange ="populateQty('${i}', '${tgl}')" id="req-${tgl}-${i}" value="${change_number_format(barangQtySelected[i][tgl])}" class='request-qty text-center amount_number' ></td>`;
				for (var i2 = 0; i2 < barangPoNum.length; i2++) {
					let batch_id = barangPoNum[i2].substring(3);
					// console.log('stat ',barangPoNum[i2], barangPoNumStatus[i2]);
					if (typeof barangQty[i][tgl][barangPoNum[i2]] !== 'undefined' && barangQty[i][tgl][barangPoNum[i2]] !== 'undefined') {
						dataPO[tgl].push({
							bulan_request : tgl,
							po_pembelian_batch_id : batch_id,
							barang_id : barang_id,
							warna_id : warna_id_get,
							qty : barangQty[i][tgl][barangPoNum[i2]],

						});
						baris[tgl] += `<td ${(barangPoNumStatus[i2] == 1 ? '' : 'hidden')} data-warna="${tgl}-${i}" data-batchid="${barangPoNum[i2]}" data-qty="${barangPoQtyC[i][barangPoNum[i2]]}">
							${change_number_format(barangQty[i][tgl][barangPoNum[i2]]) }<br/>
							<small class='keterangan-outstanding keterangan-${tgl}-${i}' hidden>sisa : ${barangPoQtyC[i][barangPoNum[i2]]}</small>
							</td>`;
					}else{
						baris[tgl] += `<td ${(barangPoNumStatus[i2] == 1 ? '' : 'hidden')} style='background:#eee' data-batchid="${barangPoNum[i2]}"></td>`;
					};
				};

				baris[tgl] += `<td id="poBaru-${i}">${change_number_format(barangPoBaru[i][tgl])}</td>`;
				baris[tgl] += `<td><button onclick="selectData('${warna_id_get}', '${tgl}', '0','2')" class='btn btn-xs red'><i class='fa fa-times'></i></button></td></tr>`;

				dataPO[tgl].push({
					bulan_request : tgl,
					po_pembelian_batch_id : 0,
					barang_id : barang_id,
					warna_id : warna_id_get,
					qty : barangPoBaru[i][tgl],
				});
				// console.log(tgl, baris[tgl]);
			};
			
		});

	});

	// console.log('dataPO',dataPO);

	var tglUse = {};
	// let tgl_now = today;
	// $.each(dataPO, function(tgl, isi){

	// 	let tgl_now = $(`#tgl-${tgl}`).val();
	// 	tglUse[tgl] = (tgl_now != '' && typeof tgl_now !== 'undefined' ? tgl_now : today);
	// 	// console.log(tgl, isi);
	// });

	dataSend.push({
		tanggal:$("#tanggalRequestAktif").val(),
		// bulan:tgl,
		data_barang:dataPO,
		data_request:barangRequest
	});

	// console.log(baris);

	$("#requestTableContainer").html('');


	var tblContent = `<table class="table table-striped table-bordered table-hover" id="general_table2">`;
	$.each(baris, function(tgl, isi){
		// let tglS = tgl.split('-')
		let tblHeader = '';
		for (var i = 0; i < barangPoNum.length; i++) {
			tblHeader += `<th ${(barangPoNumStatus[i] == 1 ? '' : 'hidden')} id="${barangPoNum[i]}">
					<span style='font-size:0.8em'>
						${barangPoNumLengkap[i]} <br/>
					</span> 
				</th>`;
		};

		tblContent += `<thead>
			<tr>
				<th colspan='${parseInt(barangPoNum.length) + 4 }' style='background:#d4e3ff'>
					Bulan : <span id='nama_bulan'>${namaBulan(tgl)}</span><br/>
				</th>
			</tr>
				<tr>
					<th scope="col">
						Warna
					</th>
					<th scope="col">
						Request
					</th>
					${tblHeader}
					<th id="id-baru">PO Baru</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				${isi}
			</tbody>`;


	});
	tblContent += `</table>`;

		$('#requestTableContainer').append(tblContent);

	// $("#general_table2 tbody").append(baris);
};

function populateQty(warna_id, periode){
	var sisa = reset_number_format($(`#req-${periode}-${warna_id}`).val());
	barangQtySelected[warna_id][periode] = sisa;
	$(`#general_table2_${periode} [data-warna='${periode}-${warna_id}']`).each(function(){
		let poQty = $(this).attr('data-qty');
		let reqQty = (parseInt(sisa) > parseInt(poQty) ? poQty : sisa );
		// console.log(poQty, sisa, reqQty);
		$(this).text(change_number_format(reqQty) );
		if (sisa - poQty > 0) {
			sisa = sisa - poQty;
		}else{sisa = 0};
	});

	$(`#poBaru-${warna_id}`).text(change_number_format(sisa) );
	populateBarang();

}


function gantiTanggal(){
	let tgl_now = $(`#tanggalRequestAktif`).val();
	if (tgl_now != gantiTanggalOri && tgl_now != '' && request_barang_id != '') {
		var data = {};
		data['tanggal'] = tgl_now;
		data['request_barang_id'] = request_barang_id;
		var url = "master/request_barang_update_tanggal";
		if (isGantiTanggal) {

			isGantiTanggal = false;
			ajax_data_sync(url,data).done(function(data_respond  ,textStatus, jqXHR ){
				setTimeout(function(){
					isGantiTanggal = true;
				},onSubmitTime);
				console.log(data_respond);
					// alert(data_respond)
					if (textStatus == 'success') {
						notific8("lime", "Tanggal Update");
					}else{
						alert("Gagal Update, Mohon hubungi admin");
					};
					// window.location.reload();

				// if (data_respond == 'OK') {
					// windows.location.reload();
					
				// };
			});
			
		};		
	}else if(tgl_now == '' && request_barang_id != ''){
		alert("Mohon isi tanggal Request");
	};
}

function namaBulan(tanggal){
	let dt = tanggal.split('-');
	let bulan = [
		"Januari", "Februari", "Maret", "April",
		"Mei", "Juni", "Juli", "Agustus",
		"September", "Oktober", "November", "Desember"
		];

	return bulan[parseInt(dt[1] - 1)]+' '+(parseInt(dt[0])+1);
}

function showPoKeterangan(keterangan){
	$(document).find(`.keterangan-outstanding`).hide();
	$(document).find(`.${keterangan}`).show();
}

function submitNewRequest(){
	let tanggal = $("#form_add_data [name=tanggal]").val();
	if (tanggal != '') {
		btn_disabled_load($('.btn-form-save'));
		$('#form_add_data').submit();
	}else{
		alert('Mohon isi tanggal');
	};
}

function submitRequest(){
	btn_disabled_load($(".btn-brg-save"));
	console.log(dataSend);
	drawRequestTableBody();
	var data = {};
	data['data'] = dataSend;
	var url = "master/request_barang_submit";
	ajax_data_sync(url,data).done(function(data_respond  ,textStatus, jqXHR ){
		console.log(data_respond);
			// alert(data_respond)
			window.location.reload();

		// if (data_respond == 'OK') {
			// windows.location.reload();
			
		// };
	});
}

function lockRequest(tipe){
	btn_disabled_load($(".btn-lock"));
	var data = {};
	data['request_barang_id'] = request_barang_id;
	data['tipe'] = tipe;
	var url = "master/request_barang_lock";
	ajax_data_sync(url,data).done(function(data_respond  ,textStatus, jqXHR ){
		console.log(data_respond);
			// alert(data_respond)
			if (data_respond == "OK") {
				window.location.reload();
			}else{
				alert("error, mohon kontek admin");
			};

		// if (data_respond == 'OK') {
			// windows.location.reload();
			
		// };
	});
}

function savedCheck(){
	if (!isSaved) {
		alert("Jangan lupa save setelah merubah data");
	};
}

</script>