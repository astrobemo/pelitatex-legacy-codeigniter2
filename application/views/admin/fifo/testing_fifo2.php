<style type="text/css">	
	/*=================================================*/
	hr{
		margin: 2px;
		padding:0px;
	}

	#general_table tr th, #general_table tr td, #table-per-barang tr th, #table-per-barang tr td{
		border: 1px solid #ddd;
		padding: 3px 3px;
	}
	#general_table tr td{
		vertical-align: top;
	}

	#general_table tr th{
		z-index: 9;
	}

	.card-ppo-recap{
		position: sticky;
		top: 50px;
	}

	.card-ppo{
		padding: 3px;
		border: 2px solid #ddd;
		margin: 5px 3px 10px 3px;
	}
	#general_table tr:nth-child(2n){
		background: #eee;
	}

	.bm-detail{
		margin: auto;
		border-collapse: collapse;
	}

	.bm-detail tr td, .bm-detail tr th{
		border: 0px solid #fff !important;
	}

	.bm-detail td:nth-child(3){
		padding: 0 5px;
	}

	.bm-detail tr:nth-child(2n){
		background: transparent !important;
	}

	.penjualan-container .bm-detail tbody tr:last-child td{
		border-bottom:1px solid !important;
	}

	.card-ppo-column .bm-detail tbody tr:last-child td{
		border-bottom:1px solid !important;
	}



	/*#general_table tr th div{
		position: fixed;
		background-color: yellow;
		width: inherit;
		height: inherit;
		border: 1px solid #333;
	}*/


	/*=================================================*/
	.bar-progress{
		width: 100%;
		height: 20px;
		font-size: 0.9em;
		font-weight: bold;
		text-align: center;
	}

	/*=================================================*/
	.card-samping-view{
		position: fixed;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
		background: rgba(0,0,0,0.7);
		display: none;
		padding-top: 1%;
		padding-bottom: 20px;
		overflow: auto;
		z-index: 10;
	}

	.card-samping-view tfoot{
		font-size: 1.2em;
	}

	.card-samping-view .card-ppo-container{
		margin: 10px 20px;
		padding: 10px;
		text-align: center;
		vertical-align: top;
	}

	.card-samping-view .card-ppo{
		min-width: 150px;
		max-width: 250px;
		background: #eee;
		margin: auto;
		display: inline-block;
		margin: 0px 5px 20px 5px;
		vertical-align: top;
	}

	.card-ppo-column .bm-detail{
		margin: auto;
	}

	.card-samping-view .card-ppo-column-samping{
		min-width: 250px;
		max-width: 300px;
		background: #eee;
		margin: auto;
		display: inline-block;
		margin: 0px 5px 20px 5px;
		vertical-align: top;
	}

	.card-ppo-column-samping .bm-detail{
		margin:auto;
	}

	.card-samping-view .lt-table{
		min-width: 300px;
		background: #eee;
		margin: auto;
		display: inline-block;
		margin: 0px 5px 20px 5px;
		vertical-align: top;
	}

	.card-samping-view .lt-table tr td{
		border: 1px solid #ddd;
		padding: 3px 10px;
	}

</style>

<?
$tanggal_get = $tanggal;
$count_po = 0;
foreach ($stok_awal as $row) {
	$sa[$row->warna_id] = $row->qty;
}

foreach ($batch_for_pre_po as $row) {
	if ($row->status_include == 1) {
		$status_include[$row->po_pembelian_batch_id] = true;
	}else{
		$status_include[$row->po_pembelian_batch_id] = false;
	}
	$stok_po_all[$row->po_pembelian_batch_id] = 0;
	$lt_po_all[$row->po_pembelian_batch_id] = 0;

	$batch_bm_total[$row->po_pembelian_batch_id] = 0;
	$batch_jual_total[$row->po_pembelian_batch_id] = 0;
	$batch_trx_total[$row->po_pembelian_batch_id] = 0;
	$batch_lt_total[$row->po_pembelian_batch_id] = 0;

}

foreach ($ppo_lock_data as $row) {

	foreach ($status_include as $key => $value) {
		$status_include[$key] = false;
	}
	$po_pembelian_batch_id_aktif = explode(',', $row->po_pembelian_batch_id_aktif);
	foreach ($po_pembelian_batch_id_aktif as $key => $value) {
		$status_include[$value] = true;
	}
}

if ($view_type == 2) {
	foreach ($batch_for_pre_po as $row) {
		$status_include[$row->po_pembelian_batch_id] = true;
	}
}

foreach ($status_include as $key => $value) {
	if ($value) {
		$count_po++;
	}
}

$count_show = $count_po;

// print_r($list_stok_by_tanggal);

foreach ($list_stok as $row) {
	$stok[$row->warna_id][$row->po_pembelian_batch_id] = $row;
	$stok_bm[$row->warna_id][$row->po_pembelian_batch_id] = $row->qty;
	$bm_all[$row->warna_id] = (isset($bm_all[$row->warna_id]) ? $bm_all[$row->warna_id] + $row->qty : $row->qty); 
	$bm_first[$row->warna_id][$row->po_pembelian_batch_id] = $row->tanggal_first;
	$bm_first_po[$row->po_pembelian_batch_id][$row->warna_id] = $row->tanggal_first;
}

$deleted = array();
foreach ($data_set as $val) {
	$urutan[$val->warna_id] = 1;
	$stok_now[$val->warna_id] = (isset($sa[$val->warna_id]) ? $sa[$val->warna_id] : 0);
	$stok_awal[$val->warna_id] = $stok_now[$val->warna_id];


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
			/*if ($val->warna_id == 30) {
				echo $stok_now[$val->warna_id].' '.$jual[$val->warna_id][$key]->tanggal.'<br/>';
				echo 'Terjual : '.$terjual_sa[$val->warna_id].'<hr/>';
			}*/
			// klo stok nya masih di atas 0 
			if ($stok_now[$val->warna_id] - $value->qty > 0) {
				$stok_now[$val->warna_id] -= $value->qty;
				$stok_awal[$val->warna_id] = $stok_now[$val->warna_id];
				// echo $key.'--';
				//data penjualan terakhir
				$jual_latest[$val->warna_id] = $value;
				//data qty(total) di penjualan terakhir
				$jual_qty[$val->warna_id] = $value->qty; 
				array_push($deleted, $key);
				unset($jual[$val->warna_id][$key]);
			}else{
				//data penjualan terakhir waktu pas stok awal 0
			//sisa qty penjualan di pas stok awal 0
				$jual_qty_latest_sisa[$val->warna_id][$key] = $value->qty - $qty_now;
				//qty as penjualan pad di stok 0
				$jual_qty_latest_qty[$val->warna_id][$key] = $value->qty;
				//data penjualan pas stok awal habis, untuk dipake stok awal data
				$latest_data_jual[$val->warna_id][$key] = $value;
				$stok_awal[$val->warna_id] = 0;
				break;
			}
		}
	}
}

foreach ($list_stok_by_tanggal as $row) {
	$stok_bt[$row->warna_id][$row->tanggal] = (isset($stok_bt[$row->warna_id][$row->tanggal]) ? $stok_bt[$row->warna_id][$row->tanggal].'=??='.$row->qty.'??'.$row->po_pembelian_batch_id.'??'.$row->pembelian_id : $row->qty.'??'.$row->po_pembelian_batch_id.'??'.$row->pembelian_id );
	$stok_per_po[$row->warna_id][$row->po_pembelian_batch_id] = 0;
	$tgl_masuk[$row->warna_id][$row->po_pembelian_batch_id] = (isset($tgl_masuk[$row->warna_id][$row->po_pembelian_batch_id]) ? $tgl_masuk[$row->warna_id][$row->po_pembelian_batch_id].'?? '.$row->tanggal.'||'.$row->qty.'||'.$row->pembelian_id.'||'.$row->no_faktur.'||'.$row->qty_data : $row->tanggal.'||'.$row->qty.'||'.$row->pembelian_id.'||'.$row->no_faktur.'||'.$row->qty_data );
	$terjual_list[$row->warna_id][$row->po_pembelian_batch_id][$row->pembelian_id] = array();
}

// print_r($stok_bt);
foreach ($barang as $row) {
	$nama_beli = $row->nama;
	$nama_jual = $row->nama_jual;
}

$link = ($ppo_lock_id != '' ? "?ppo_lock_id=".$ppo_lock_id : "?barang_id=".$barang_id);
?>
<!-- <body style='padding:20px'> -->

	<div class='table-container' style="margin-auto; padding:20px;">
		<h1>
			<?=$nama_beli;?>
			<br/><small>s/d tanggal : <?=is_reverse_date($tanggal_get);?></small>
		</h1>
		Nama Jual : <?=$nama_jual;?>
		<?if ($view_type == 1) {?>
			<a href="<?=$link?>&view_type=2">Include semua PO -></a>
		<?}else{?>
			<a href="<?=$link?>&view_type=1">Include PO terpilih saja-></a>
		<?}?>
		<div>
			Keterangan : 
			<ul>
				<li>Nomor dengan latar biru : <span style='background:lightblue'> 1/2/3 </span> merupakan index berdasarkan tanggal kedatangan per warna barang</li>
				<li>Kemudian di bawahnya adalah keterangan dan daftar <b>barang masuk(digabung per tanggal & per PO Batch)</b></li>
				<li>Klik pada no faktur barang masuk, untuk menuju halaman detail barang masuk tersebut</li>
				<li>Selanjutnya <b style="background:aqua">Penjualan</b> berdasarkan barang masuk tersebut</li>
				<li>Klik tombol <button style='cursor:pointer' >detail</button> di sebelah penjualan untuk memunculkan daftar penjualan</li>
				<li>Klik pada no faktur penjualan(hanya 4 digit terakhir), untuk menuju halaman detail penjualan tersebut</li>
				<li style='padding:10px; background:lightpink'>
					LEAD TIME(Batch & Warna) =  ( <b>sisa stok</b> per tanggal barang masuk/ <b>total sisa stok</b> per <span style="background:black; color:white">batch & warna</span> ) * <b>interval</b> dari barang masuk hingga tanggal terpilih
					<hr/>
					LEAD TIME(Warna) =  ( <b>sisa stok</b> per tanggal barang masuk/ <b>total sisa stok</b> per <span style="background:black; color:white">warna</span> ) * <b>interval</b> dari barang masuk hingga tanggal terpilih
				</li>
				<li style='padding:10px; background:lightgreen'>RANGE : Interval antara <b>tanggal pertama masuk</b> per batch per warna s/d <b>penjualan terakhir</b> per batch per warna</li>
			</ul>
		</div>

		<div style='text-align:center'>
			<hr/>
			<div class='text-center'>
				<b style='font-size:1.4em'>DAFTAR PER Warna</b> - <br/>
			</div>

			<button class='btn btn-lg narrow-all-row'>NARROW ALL <i class='fa fa-compress'></i></button>
			<button class='btn btn-lg expand-all-row'>EXPAND ALL <i class='fa fa-expand'></i></button>
			<hr/>
		</div>
		<table id="general_table" style="margin:auto">
			<thead style='position:relative'>
				<tr>
					<th style="position:sticky; top:0; background:#fff; width:50px;height:50px"><div>NO</div></th>
					<th style="position:sticky; top:0; background:#fff; width:120px;height:50px"><div>Nama Warna</div></th>
					<th style="position:sticky; top:0; background:#fff; width:50px;height:50px"><div>Warna_id</div></th>
					<!-- <th style="position:sticky; top:0; background:#fff; width:100px;height:50px"><div>jual trx</div></th> -->
					<!-- <th style="position:sticky; top:0; background:#fff; width:100px;height:50px"><div>sisa jual awal</div></th> -->
					<?foreach ($batch_for_pre_po as $row) {
						$batch[$row->po_pembelian_batch_id] = $row->po_pembelian_batch_id;
						if ($status_include[$row->po_pembelian_batch_id]) {?>
							<th style="position:sticky; top:0; background:#fff; height:25px">
								<div style='background:#fff;text-align:center'>
									<a href="<?=base_url().is_setting_link('report/po_pembelian_report_detail')?>?id=<?=$row->po_pembelian_id?>&batch_id=<?=$row->po_pembelian_batch_id?>" target="_blank"> <?=$row->batch?></a>
								</div>
							</th>
						<?}?>
					<?}?>
					<th style="position:sticky; top:0; background:#fff; height:25px">RECAP</th>
				</tr>
			</thead>
			<?/**
			==============================================================================================
			**/?>		
			<?
			$g_bm_first_warna = array();
			$g_jual_last_warna = array();
			$g_lt_each_warna = array();
			$g_stok_each_warna = array();
			$g_nama_each_warna = array();
			$g_total_trx_warna = 0;
			$g_stok_all_warna = 0;
			$g_total_bm_warna = 0;
			$g_total_jual_warna = 0;
			$g_total_lt_warna = 0;
			$no_idx=1;

			$g_total_bm_po = 0;
			$g_total_jual_po = 0;
			$g_total_stok_po = 0;
			$g_stok_po = array();
			$g_total_trx_po = 0;
			$g_total_lt_po = 0;
			$g_first_po = array();
			$g_last_po = array();
			$g_lt_po = array();
			foreach ($data_set as $row) {
				$batch_id = explode(',', $row->batch_id);
				$qty_beli = explode(',', $row->qty_beli);
				$batch_set = array_combine($batch_id, $qty_beli);
				$terjual_all[$row->warna_id] = 0;
				$stok_per_warna[$row->warna_id] = 0;
				$total_bm_show[$row->warna_id] = 0;
				$terjual_show[$row->warna_id] = 0;
				$range_warna[$row->warna_id] = 0;
				$total_lt_warna[$row->warna_id] = 0;
				$bm_first_warna[$row->warna_id] = array();
				$jual_last_warna[$row->warna_id] = array();
				$trx_warna = 0;
				?>
				<tr>
					<td style="position:relative;">
						<div style='font-size:1.2em;text-align:center; position:sticky; top: 50px; '>
							<?=$no_idx?>
						</div>
					</td>
					<td style="position:relative">
						<div style='font-size:1.2em;text-align:center; position:sticky; top: 50px; width:150px;left:0px;'>
							<span class='nama-warna'><?=$row->nama_warna?></span>
							<button class='btn btn-xs btn-narrow' >><</button>
							<button class='btn btn-xs btn-expand' style='display:none'><i class="fa fa-expand"></i></button>
							<button class='btn btn-block btn-info btn-inline-view' style='width:100%'>Inline View</button>
						</div>
					</td>
					<td class='text-center'><?=$row->warna_id;?></td>
					<!-- jumlah sisa transaksi penjualan -->
					<!-- <td><?=(isset($jual[$row->warna_id]) ? count($jual[$row->warna_id]) : '');?></td> -->
					<!-- sisa qty jual pas stok awal habis -->
					<?if (isset($stok_bt[$row->warna_id])) {
						foreach ($stok_bt[$row->warna_id] as $key => $value) {
							$break = explode('=??=', $value);

							foreach ($break as $keys => $values) {
								$data_break = explode('??', $values);
								$stok_now = $data_break[0];
								$tanggal_awal = $key;
								if (isset($jual[$row->warna_id])) {
									foreach ($jual[$row->warna_id] as $key2 => $value2) {
										if (!isset($jual_now[$row->warna_id][$value2->penjualan_id])) {
											$qty_j = $value2->qty;
										}else{
											$qty_j = $jual_now[$row->warna_id][$value2->penjualan_id];
										}
										if ($stok_now - $qty_j < 0) {
											$jual_now[$row->warna_id][$value2->penjualan_id] = $qty_j - $stok_now; 
											$terjual[$row->warna_id][$data_break[1]] = (isset($terjual[$row->warna_id][$data_break[1]]) ? $terjual[$row->warna_id][$data_break[1]] + $stok_now : $stok_now  );
											$last_tgl[$row->warna_id][$data_break[1]] = $value2->tanggal;
											$lead_time[$row->warna_id][$data_break[1]] = 0;
											$stok_tgl[$row->warna_id][$data_break[1]][$key] = 0;
											$terjual_all[$row->warna_id] += $stok_now;
											$last_jual[$row->warna_id][$data_break[1]][$key] = $value2->tanggal;
											$last_jual_po[$row->warna_id][$data_break[1]] = $value2->tanggal;
											$last_jual_warna[$data_break[1]][$row->warna_id] = $value2->tanggal;
											$last_jual_po[$row->warna_id][$data_break[1]] = $value2->tanggal;
											array_push($terjual_list[$row->warna_id][$data_break[1]][$data_break[2]], $value2->penjualan_id.'??'.$value2->no_faktur.'??'.$value2->tanggal.'??'.($stok_now) );
											break;
										}else{
											$stok_now -= $qty_j;
											$terjual_all[$row->warna_id] += $qty_j;
											$terjual[$row->warna_id][$data_break[1]] = (isset($terjual[$row->warna_id][$data_break[1]]) ? $terjual[$row->warna_id][$data_break[1]] + $qty_j  : $qty_j ).'<br/>';
											
											$stok_tgl[$row->warna_id][$data_break[1]][$key] = $stok_now;
											$last_jual[$row->warna_id][$data_break[1]][$key] = $value2->tanggal;
											$last_jual_po[$row->warna_id][$data_break[1]] = $value2->tanggal;
											$last_jual_warna[$data_break[1]][$row->warna_id] = $value2->tanggal;
											$last_jual_po[$row->warna_id][$data_break[1]] = $value2->tanggal;
											array_push($terjual_list[$row->warna_id][$data_break[1]][$data_break[2]], $value2->penjualan_id.'??'.$value2->no_faktur.'??'.$value2->tanggal.'??'.($qty_j) );
											unset($jual[$row->warna_id][$key2]);
										}
										$tanggal_akhir = $value2->tanggal;
									}
								}

								if (!isset($stok_tgl[$row->warna_id][$data_break[1]][$key])) {
									$stok_tgl[$row->warna_id][$data_break[1]][$key] = $stok_now;
									$last_jual[$row->warna_id][$data_break[1]][$key] = '';
								}

								if ($status_include[$data_break[1]]) {
									$urutan_bm[$row->warna_id][$data_break[1]][$key] = $urutan[$row->warna_id];
									if ($row->warna_id == 1) {
										// echo $key.'=='.$urutan[$row->warna_id].'<br/>';
									}
									$urutan[$row->warna_id]++;
								}
							}
						}
					
						$stok_all[$row->warna_id] = $bm_all[$row->warna_id] - $terjual_all[$row->warna_id];
					}?>
					<!-- <td><?=(isset($stok_awal[$row->warna_id]) ? $stok_awal[$row->warna_id] : 0);?></td> -->
					<?foreach ($batch_for_pre_po as $row2) {
						if ($status_include[$row2->po_pembelian_batch_id]) {
							$terjual[$row->warna_id][$row2->po_pembelian_batch_id] = (isset($terjual[$row->warna_id][$row2->po_pembelian_batch_id]) ? $terjual[$row->warna_id][$row2->po_pembelian_batch_id] : 0);
							// $bm_first[$row->warna_id][$row2->po_pembelian_batch_id] = '';
							$bm_last[$row->warna_id][$row2->po_pembelian_batch_id] = '';

							$total_bm_show[$row->warna_id] += (isset($stok_bm[$row->warna_id][$row2->po_pembelian_batch_id]) ? $stok_bm[$row->warna_id][$row2->po_pembelian_batch_id] : 0);
							
							$last[$row->warna_id][$row2->po_pembelian_batch_id]='';
							$count_jual[$row->warna_id][$row2->po_pembelian_batch_id]=0;
							$count_jual_by_customer[$row->warna_id][$row2->po_pembelian_batch_id]=0;
							$count_jual_by_non_customer[$row->warna_id][$row2->po_pembelian_batch_id]=0;
							unset($cust_idx);
							$total_lt_po[$row->warna_id][$row2->po_pembelian_batch_id] = 0;
							$stok_bm_show = (isset($stok_bm[$row->warna_id][$row2->po_pembelian_batch_id]) ? $stok_bm[$row->warna_id][$row2->po_pembelian_batch_id] :  0);
							$terjual_now = $terjual[$row->warna_id][$row2->po_pembelian_batch_id];
							$terjual_show[$row->warna_id] += $terjual_now;
							$trx_batch = 0;

							$total_lt[$row->warna_id][$row2->po_pembelian_batch_id] = 0;
							$lt_all[$row->warna_id] = 0;
							$jarak_jual = array();
							$jarak_total = 0;
							$tgl_masuk_list = explode('??', (isset($tgl_masuk[$row->warna_id][$row2->po_pembelian_batch_id]) ? $tgl_masuk[$row->warna_id][$row2->po_pembelian_batch_id] : ''));
							$is_locked = (isset($locked_data[$row2->po_pembelian_batch_id]) && $locked_data[$row2->po_pembelian_batch_id] != 0 ? true : false);

							if (isset($stok_tgl[$row->warna_id][$row2->po_pembelian_batch_id])) {
								foreach ($stok_tgl[$row->warna_id][$row2->po_pembelian_batch_id] as $key => $value) {
									if ($terjual_now != 0) {
										$tgl_1 = date_create($key);
										if (isset($last_jual[$row->warna_id][$row2->po_pembelian_batch_id][$key]) && $last_jual[$row->warna_id][$row2->po_pembelian_batch_id][$key] != '') {
											$tgl_akhir_jual = date_create($last_jual[$row->warna_id][$row2->po_pembelian_batch_id][$key]);
											$jj = date_diff($tgl_1,$tgl_akhir_jual)->format('%a');
											// $jarak_total[$row2->po_pembelian_batch_id] += $jj;
											// $jarak_total_all += $jj;
											array_push($jarak_jual, $jj);
										}
									}
									$stok_per_po[$row->warna_id][$row2->po_pembelian_batch_id] += $value;
									$stok_po_all[$row2->po_pembelian_batch_id] += $value;
								}
							}
							if (isset($stok_tgl[$row->warna_id][$row2->po_pembelian_batch_id])) {
								foreach ($stok_tgl[$row->warna_id][$row2->po_pembelian_batch_id] as $key => $value) {
									$tgl_a = date_create($key);
									$tgl_b = date_create($tanggal_get);
									$intvl = date_diff($tgl_a,$tgl_b)->format('%a')+1;
									$intvl_po[$row->warna_id][$row2->po_pembelian_batch_id][$key] = $intvl;
									if ($value != 0) {
										$persen = $value/$stok_all[$row->warna_id]*100;
										$persen_po = $value/($stok_per_po[$row->warna_id][$row2->po_pembelian_batch_id] != 0 ? $stok_per_po[$row->warna_id][$row2->po_pembelian_batch_id] : 1) * 100;
										//======================================================================================
										$lt_per_warna[$key] = $intvl*$persen/100;
										$lt_po[$key] = $intvl*$persen_po/100;
										//======================================================================================																					
										$total_lt[$row->warna_id][$row2->po_pembelian_batch_id] += $lt_per_warna[$key];
										$total_lt_po[$row->warna_id][$row2->po_pembelian_batch_id] += $lt_po[$key];
									
										$total_lt_warna[$row->warna_id] += $lt_per_warna[$key];

									}else{
										$lt_po[$key] = 0;
										$lt_per_warna[$key] = 0;
									}
								}
							}?>
							<?/**
							==========================================munculkan data per po batch ke layar===============================
							**/?>
							<td>
								<?if ($stok_bm_show != 0) {					
									foreach ($tgl_masuk_list as $key => $value) {
										$penjualan_now = 0;
										$split = explode('||', $value);
										$tgl_bm = $split[0];
										$bm_now = $split[1];?>
										<div class='card-ppo'>
											<div style='border-bottom:2px solid #ddd; text-align:center; padding-bottom:10px'>
												<p style='background:lightblue;font-weight:bold;font-size:1.2em; text-align:center' ><span class='ppo-index'><?=$urutan_bm[$row->warna_id][$row2->po_pembelian_batch_id][trim($split[0])];?></span>
													<br/><small style='font-size:0.7em;'><?=is_reverse_date(trim($split[0]) );?></small>
													<br/><small style='font-size:0.7em;' class='ket-batch-ppo' hidden><?=$row2->batch;?></small>
												</p>
												<div style='background:lightgrey'>Barang Masuk</div>
												<table class='bm-detail'>
												<?
													$pembelian_id = explode(',', $split[2]);
													$no_faktur_beli = explode(',', $split[3]);
													$qty_beli_data = explode(',', $split[4]);

													foreach ($no_faktur_beli as $k => $v) {?>
														<tr>
															<td style='text-align:right'>
																<a href="<?=base_url().is_setting_link('transaction/pembelian_list_detail')?>/<?=$pembelian_id[$k]?>" target="_blank"><?=$v?></a>
															</td>
															<td> : </td>
															<td><?=(float)$qty_beli_data[$k];?></td>
														</tr>
													<?}
												?>
												<tr>
													<td style='text-align:right'>TOTAL</td>
													<td> : </td>
													<td><b><?=(float)$bm_now;?></b></td>
												</tr>
												</table>
											</div>
											<?if (count($terjual_list[$row->warna_id][$row2->po_pembelian_batch_id][$split[2]]) > 0 ) { ?>
												<div style="text-align:center; margin-bottom:10px" class='penjualan-container'>
													<div style='background:aqua'>Penjualan : 
														<button style='cursor:pointer' class='btn btn-xs btn-toggle-detail-jual'>detail > </button>
													</div>
													<table class='bm-detail'>
														<thead hidden>
															<th>No</th>
															<th>Invoice</th>
															<th>Tanggal</th>
															<th>Qty</th>
														</thead>
														<tbody hidden>
															<?$no = 1;
															foreach ( $terjual_list[$row->warna_id][$row2->po_pembelian_batch_id][$split[2]] as $value2) {
																$split2 = explode('??', $value2);
																$penjualan_now += $split2[3]; ?>
																<?//=(float)$split2[3]?>
																<tr>
																	<td><?=$no;?>.</td>
																	<td>
																		<a href="<?=base_url().is_setting_link('transaction/penjualan_list_detail')?>?id=<?=$split2[0];?>" target='_blank'><?=$split2[1];?></a>
																	</td>
																	<td><?=is_reverse_date($split2[2])?></td>
																	<td style='text-align:right'><?=(float)$split2[3];?></td>
																</tr>

															<?$no++;}?>
														</tbody>
														<tfoot>
															<tr>
																<?$trx_warna += $no-1; $trx_batch += $no-1;?>
																<td colspan='2'  style='text-align:left; padding-right:10px;'><?=$no-1;?> trx</td>
																<td style='text-align:right'>TOTAL</td>
																<td style='text-align:right'>
																	<b><?=$penjualan_now;?></b>
																</td>
															</tr>
														</tfoot>
													</table>
												</div>
											<?}else{?>
												<div style="text-align:center; margin-bottom:10px" class='penjualan-container'>
													<div style='background:aqua'>Penjualan : -
													</div>
												</div>
											<?}?>

											<div style="background:lightpink">

												LEAD TIME(Batch & Warna):
												<br/>
												<b>
												<?$stok_batch_warna[$row2->po_pembelian_batch_id][$row->warna_id][trim($split[0])]=$bm_now - $penjualan_now; ?>
												(<?=($bm_now - $penjualan_now).'/'.$stok_per_po[$row->warna_id][$row2->po_pembelian_batch_id];?>) 
												* <?=$intvl_po[$row->warna_id][$row2->po_pembelian_batch_id][trim($tgl_bm)];?>
												 = <?=round($lt_po[trim($tgl_bm)],2);?>
												</b>
												<hr/>
												LEAD TIME(Warna):
												<br/>
												<b>
												(<?=($bm_now - $penjualan_now).'/'.$stok_all[$row->warna_id];?>) 
												* <?=$intvl_po[$row->warna_id][$row2->po_pembelian_batch_id][trim($tgl_bm)];?>
												 = <?=round($lt_per_warna[trim($tgl_bm)],2);?>
												</b>
											</div>
										</div>
									<?}

								}?>
								<?if($terjual_now != 0){
									$tgl_1 = date_create($bm_first[$row->warna_id][$row2->po_pembelian_batch_id]);
									$tgl_2 = date_create($last_jual_po[$row->warna_id][$row2->po_pembelian_batch_id]);
									$jt = date_diff($tgl_1,$tgl_2)->format('%a')+1;
									// echo $bm_first[$row->warna_id][$row2->po_pembelian_batch_id].' s/d '.$last_jual_po[$row->warna_id][$row2->po_pembelian_batch_id];
									// echo 'JT:'.$jt;
								} ?>
								<?if (isset($stok_tgl[$row->warna_id][$row2->po_pembelian_batch_id])) {
									foreach ($stok_tgl[$row->warna_id][$row2->po_pembelian_batch_id] as $key => $value) {
										if ($terjual_now != 0) {
											$tgl_1 = date_create($key);
											
											if (isset($last_jual[$row->warna_id][$row2->po_pembelian_batch_id][$key]) && $last_jual[$row->warna_id][$row2->po_pembelian_batch_id][$key] != '') {
												$tgl_akhir_jual = date_create($last_jual[$row->warna_id][$row2->po_pembelian_batch_id][$key]);
												// echo '<hr/>'.date_format($tgl_1,"d/m/y").' s/d '.date_format($tgl_akhir_jual,"d/m/y");
												$jj = date_diff($tgl_1,$tgl_akhir_jual)->format('%a');
												// echo '----'.$value;
												// $jarak_total += $jj;
												array_push($jarak_jual, $jj) ;
											}
										}
									}
									if ($terjual_now != 0) {
										$tgl_1 = date_create($bm_first[$row->warna_id][$row2->po_pembelian_batch_id]);
										$tgl_2 = date_create($last_jual_po[$row->warna_id][$row2->po_pembelian_batch_id]);
										$jt = date_diff($tgl_1,$tgl_2)->format('%a');
										$jarak_total += $jt;
										array_push($bm_first_warna[$row->warna_id], $bm_first[$row->warna_id][$row2->po_pembelian_batch_id]);
										array_push($jual_last_warna[$row->warna_id], $last_jual_po[$row->warna_id][$row2->po_pembelian_batch_id]) ;	
									
									}

								}
								if (count($jarak_jual) > 0) {
									// echo "TOTAL : ".$jarak_total.'<br/>';
									foreach ($jarak_jual as $key => $value) {
										// echo "JJ : ".$value.'<br/>';
									}
								}?>

								<div class='card-ppo-sum'>
									<?
										//=============================================================================================
										$batch_bm_total[$row2->po_pembelian_batch_id] += $stok_bm_show;
										$batch_jual_total[$row2->po_pembelian_batch_id] += $terjual_now;
										$batch_trx_total[$row2->po_pembelian_batch_id] += $trx_batch;

										//=============================================================================================
									?>
									<?if ($stok_bm_show != 0 || $terjual_now != 0) {?>
										<div style="margin-top:10px; background:khaki">
											TOTAL IN : <b><?=(float)$stok_bm_show?></b>
											<br/>TOTAL OUT : <b><?=$terjual_now;?></b>
											<br/>TRANSAKSI : <b><?=$trx_batch;?></b>
										</div>
									<?}?>

									<?if ($stok_bm_show > 0) {?>
										<div style="background:lightgreen">
											<?if ($terjual_now > 0) {?>
												RANGE  : 
												<b>
													<br/><?=date_format($tgl_1,"d/m/y").' s/d '.date_format($tgl_2,"d/m/y");?>
													<br/>= <?=$jarak_total+1;?> HARI
													<?$range_warna[$row->warna_id] += ($jarak_total+1);?>	
												</b>
											<?}else{?>
												RANGE : -
											<?}?>
										</div> 
									<?}?>
									<?if ($stok_bm_show > 0) {?>
										<div style="background-color:salmon;">
											T. LEAD TIME / PO : 
											<b><?=round($total_lt_po[$row->warna_id][$row2->po_pembelian_batch_id],2);?></b>
											<hr/>T. LEAD TIME / Warna : <b><?=round($total_lt_warna[$row->warna_id],2);?></b>
										</div>
									<?}?>
								</div>

							</td>
						<?}?>
						
					<?}?>
					<?/**
					==========================================rekap per warna===============================
					**/?>
					<td>
						<div class='card-ppo-recap'>
							<p style='background:dodgerblue;font-weight:bold;font-size:1.2em; text-align:center'>RECAP</p>

							<div style='background:lightgrey'>TOTAL Masuk : <b><?=number_format($total_bm_show[$row->warna_id],'0',',','.');?></b></div>	
							<div style="background:aqua">TOTAL Penjualan : <b><?=number_format($terjual_show[$row->warna_id],'0',',','.');?></b></div>		
							<div style='background:lightgreen'>
								DALAM :
								<?
								$lt_show = 0;
								if (count($bm_first_warna[$row->warna_id]) > 0 && count($jual_last_warna[$row->warna_id]) > 0) {?>
									<br/><?=is_reverse_date(min($bm_first_warna[$row->warna_id]));?> s/d <?=is_reverse_date(max($jual_last_warna[$row->warna_id]));?>	
									<?
										$tgl_1 = date_create(min($bm_first_warna[$row->warna_id]));
										$tgl_2 = date_create(max($jual_last_warna[$row->warna_id]));
										//====================================================================
										array_push($g_bm_first_warna, min($bm_first_warna[$row->warna_id]));
										array_push($g_jual_last_warna, max($jual_last_warna[$row->warna_id]));
									?>
								<?}?>
								<br/><b><?=$lt_show = date_diff($tgl_1, $tgl_2)->format('%a') + 1;?></b> Hari
							</div>		
							<div style="background:salmon">LEAD TIME : <b><?=round($total_lt_warna[$row->warna_id],2);?></b></div>
						</div>
						<?
							//=====================================================================
							$g_total_trx_warna += $trx_warna;
							$g_stok_all_warna += $total_bm_show[$row->warna_id] - $terjual_show[$row->warna_id];
							$g_total_bm_warna += $total_bm_show[$row->warna_id];
							$g_total_jual_warna += $terjual_show[$row->warna_id];
							array_push($g_nama_each_warna, $row->nama_warna);
							array_push($g_stok_each_warna, $total_bm_show[$row->warna_id] - $terjual_show[$row->warna_id]);
							array_push($g_lt_each_warna, round($total_lt_warna[$row->warna_id],2));				
						?>
					</td>
				</tr>
			<?$no_idx++;}?>
			<?/**
			==========================================bagian akhir baris rekap per PO===============================
			**/?>
			<tr>
				<td></td>
				<td></td>
				<td></td>
				<?foreach ($batch_for_pre_po as $row) {
					if ($status_include[$row->po_pembelian_batch_id]) {?>
						<td></td>
					<?}
				}?>
				<td>
					<div class='card-ppo-recap-all'>
						<p style='background:dodgerblue;font-weight:bold;font-size:1.2em; text-align:center'>RECAP</p>

						<div style='background:lightgrey'>TOTAL Masuk : <b><?=number_format($g_total_bm_warna,'0',',','.');?></b></div>	
						<div style="background:aqua">TOTAL Penjualan : <b><?=number_format($g_total_jual_warna,'0',',','.');?></b></div>		
						<div style='background:lightgreen'>
							DALAM :
							<?if (count($g_bm_first_warna) > 0 && count($g_jual_last_warna) > 0) {?>
								<br/><?=is_reverse_date(min($g_bm_first_warna));?> s/d <?=is_reverse_date(max($g_jual_last_warna));?>	
								<?
									$tgl_1 = date_create(min($g_bm_first_warna));
									$tgl_2 = date_create(max($g_jual_last_warna));
								?>
							<?}?>
							<br/><b><?=date_diff($tgl_1, $tgl_2)->format('%a') + 1;?></b> Hari
						</div>		
						<div style="background:salmon">
							LEAD TIME : <button class='btn-view-lt-recap-warna'>DETAIL</button>
							<table id="recap-warna-all">
								<thead hidden>
									<tr>
										<th>Warna</th>
										<th colspan='2'>Stok/Stok All * Lead Time</th>
										<th>Lead Time New</th>
									</tr>
								</thead>
								
								<tbody hidden>
								<?foreach ($g_stok_each_warna as $isi => $vlu) {?>
									<tr>
										<td class='text-left'><?=$g_nama_each_warna[$isi]?></td>
										<td class='text-right'>
											<?=$vlu?> / <?=$g_stok_all_warna;?>
										</td>									
										<td class='text-left'>
											x <?=$g_lt_each_warna[$isi];?>
										</td>
										<td>
											<?$lt_temp_now=($g_stok_each_warna[$isi]/$g_stok_all_warna) * $g_lt_each_warna[$isi];
											echo round($lt_temp_now,2);
											$g_total_lt_warna += round($lt_temp_now,2);?>
										</td>
									</tr>	
								<?}?>
								</tbody>
								<tfoot>
									<tr>
										<td colspan='3'>TOTAL</td>
										<td><b><?=$g_total_lt_warna;?></b></td>
									</tr>
								</tfoot>
							</table>
						</div>
					</div>
				</td>
			</tr>
		</table>
		<?/**
			==========================================beda table===============================
			**/?>
		<hr style="margin:15px 0px"/>

		<div class='text-center'>
			<b style='font-size:1.4em'>DAFTAR PER PO</b> - <br/>
			Daftar tanggal barang masuk pertama dan tanggal jual terakhir
		</div>

		<table id='table-per-barang' style="margin:auto">
			<tr>
				<th>Warna</th>

				<?$i=1;
				foreach ($batch_for_pre_po as $row) {
					if ($status_include[$row->po_pembelian_batch_id]) {?>
						<th>
							<div>
								<span class='nama-batch'><?=$row->batch;?></span>
								<br/><button class='btn-view-inlie-by-po' data-index='<?=$i;?>'>View Inline</button>
							</div>
						</th>
					<?$i++;}?>
				<?}?>
			</tr>
			<?foreach ($data_set as $row) {?>
				<tr>
					<td class='nama-warna'><?=$row->nama_warna;?></td>
					<?$i=1;
					foreach ($batch_for_pre_po as $row2) {
						$tgl_masuk_list = explode('??', (isset($tgl_masuk[$row->warna_id][$row2->po_pembelian_batch_id]) ? $tgl_masuk[$row->warna_id][$row2->po_pembelian_batch_id] : ''));
						if ($status_include[$row2->po_pembelian_batch_id]) {?>
							<td>
								<div class='card-ppo-column-<?=$i;?>'>
									<?if (isset($bm_first_po[$row2->po_pembelian_batch_id][$row->warna_id])) {?>
										1st IN : <?=(isset($bm_first_po[$row2->po_pembelian_batch_id][$row->warna_id]) ? is_reverse_date($bm_first_po[$row2->po_pembelian_batch_id][$row->warna_id]) : '');?>
										<br/>last OUT : <?=(isset($last_jual_warna[$row2->po_pembelian_batch_id][$row->warna_id]) ? is_reverse_date($last_jual_warna[$row2->po_pembelian_batch_id][$row->warna_id]) : '' );?>
										<div style='margin-top:10px;' class='card-ppo-column ' >
											<span style="background:lightgray">LEAD TIME(Batch)
												<button class='btn btn-xs yellow btn-lt-po-detail'><i class="fa fa-list"></i></button>
											</span>
											<table class='bm-detail'>
												<tbody hidden>
													<?$lt_temp = 0;
													foreach ($tgl_masuk_list as $key => $value) {
														$split = explode('||', $value);
														$tgl_now = trim($split[0]);
														$stok_bw = $stok_batch_warna[$row2->po_pembelian_batch_id][$row->warna_id][$tgl_now];
														$lt_now = round(($stok_bw / ($stok_po_all[$row2->po_pembelian_batch_id] != 0 ? $stok_po_all[$row2->po_pembelian_batch_id] : 1) ) * $intvl_po[$row->warna_id][$row2->po_pembelian_batch_id][$tgl_now],2);
														$lt_po_all[$row2->po_pembelian_batch_id] += $lt_now;
														$lt_temp += $lt_now;
														?>
															<tr>
																<td>
																	<span style='font-weight:bold;text-align:left' >
																		<?=is_reverse_date($tgl_now);?>
																	</span>
																</td>
																<td style='text-align:center'>
																	<span class='lt-po-rumus_'>(<?=$stok_bw;?> / <?=($stok_po_all[$row2->po_pembelian_batch_id] != 0 ? $stok_po_all[$row2->po_pembelian_batch_id] : 1);?>) * <?=$intvl_po[$row->warna_id][$row2->po_pembelian_batch_id][$tgl_now];?></span>
																</td>
																<td> = </td>
																<td style='text-align:right'>
																	<?=$lt_now;?>
																</td>
															</tr>
													<?}?>
												</tbody>
												<tfoot>
													<tr style='font-weight:bold'>
														<td colspan='2'  style='text-align:right'>TOTAL</td>
														<td style='text-align:center'> = </td>
														<td style='text-align:right; padding:0px;'><?=$lt_temp;?></td>
													</tr>
												</tfoot>
											</table>
										</div>

									<?}?>
								</div>
							</td>
						<?$i++;}?>
					<?}?>
				</tr>
			<?}?>
			<tr>
				<th  style="text-align:left">REKAP</th>
				<?$span = 0;foreach ($batch_for_pre_po as $row) {
					if ($status_include[$row->po_pembelian_batch_id]) {?>
					<th>

					<?	
						$span++;
						$g_total_bm_po += $batch_bm_total[$row->po_pembelian_batch_id];
						$g_total_jual_po += $batch_jual_total[$row->po_pembelian_batch_id];
						$g_total_trx_po += $batch_trx_total[$row->po_pembelian_batch_id]; 
						$g_stok_po[$row->po_pembelian_batch_id] = $batch_bm_total[$row->po_pembelian_batch_id] - $batch_jual_total[$row->po_pembelian_batch_id];
						$g_total_stok_po += $g_stok_po[$row->po_pembelian_batch_id];
						$g_lt_po[$row->po_pembelian_batch_id] = $lt_po_all[$row->po_pembelian_batch_id];
						$dlm = 0;
						if (isset($bm_first_po[$row->po_pembelian_batch_id]) && isset($last_jual_warna[$row->po_pembelian_batch_id]) ) {
							$tgl_1 = date_create(min($bm_first_po[$row->po_pembelian_batch_id]));
							$tgl_2 = date_create(max($last_jual_warna[$row->po_pembelian_batch_id]));
							array_push($g_bm_first_warna, min($bm_first_po[$row->po_pembelian_batch_id]));
							array_push($g_jual_last_warna, max($last_jual_warna[$row->po_pembelian_batch_id]));
							$dlm =  date_diff($tgl_1, $tgl_2)->format('%a');
							?>
						<?}?>
						<div style="margin-top:10px; background:khaki">
							TOTAL IN : <?=number_format($batch_bm_total[$row->po_pembelian_batch_id],'0',',','.');?>
							<br/>TOTAL OUT : <?=number_format($batch_jual_total[$row->po_pembelian_batch_id],'0',',','.');?>
							<br/>TRANSAKSI : <?=number_format($batch_trx_total[$row->po_pembelian_batch_id],'0',',','.');?>
						</div>
						<div style="background:lightgreen">
							<?if (isset($last_jual_warna[$row->po_pembelian_batch_id])) {?>
								<?=is_reverse_date(min($bm_first_po[$row->po_pembelian_batch_id]));?>
								s/d <?=is_reverse_date(max($last_jual_warna[$row->po_pembelian_batch_id]));?>
								<br/>= <?=$dlm+1;?>
							<?}else{echo '-';}?>
						</div>
						<div style="background:salmon">LEAD TIME : <b><?=$lt_po_all[$row->po_pembelian_batch_id];?></b></div>

					</th>
						
					<?}?>
				<?}?>
			</tr>
			<tr>
				<th  style="text-align:left">REKAP</th>
				<th colspan='<?=$span?>' class='text-center'>
					<div style="margin-top:10px; background:khaki">
						TOTAL IN : <?=number_format($g_total_bm_po,'0',',','.');?>
						<br/>TOTAL OUT : <?=number_format($g_total_jual_po,'0',',','.');?>
						<br/>TRANSAKSI : <?=number_format($g_total_trx_po,'0',',','.');?>
					</div>
					<div style="background:lightgreen">
						<?=is_reverse_date(min($g_bm_first_warna));?>
						s/d <?=is_reverse_date(max($g_jual_last_warna));?>
						<?	
							$tgl_1 = date_create(min($g_bm_first_warna));
							$tgl_2 = date_create(max($g_jual_last_warna));
							$dlm =  date_diff($tgl_1, $tgl_2)->format('%a');
						?>
						<br/>= <?=$dlm+1;?>
					</div>
					<div style="background:salmon">
						<table id="recap-po-all" style='margin:auto'>
							<thead hidden>
								<tr>
									<th>Batch</th>
									<th colspan='2'>Stok/Stok All * Lead Time</th>
									<th>Lead Time New</th>
								</tr>
							</thead>
							<tbody hidden>
								<?foreach ($batch_for_pre_po as $row) {
									if ($status_include[$row->po_pembelian_batch_id]) {?>
										<tr>
											<td><?=$row->batch?></td>
											<td><?=$g_stok_po[$row->po_pembelian_batch_id]?> / <?=$g_total_stok_po?></td>
											<td>x <?=$g_lt_po[$row->po_pembelian_batch_id]?></td>
											<td>
												<?=$lt_now = round(($g_stok_po[$row->po_pembelian_batch_id] / $g_total_stok_po ) * $g_lt_po[$row->po_pembelian_batch_id],2);?>
												<?$g_total_lt_po += $lt_now;?>
											</td>
										</tr>
									<?}?>
								<?}?>
							</tbody>
							<tfoot>
								<th colspan='3'>LEAD TIME</th>
								<th><b><?=$g_total_lt_po;?></b></th>
							</tfoot>
						</table>
						<button class='btn-view-lt-recap-po'>DETAIL</button>
					</div>

				</th>
			</tr>
		</table>
	</div>
		<?//print_r($jual[116]);?>
		<hr/>
		<?//print_r($list_stok);?>

		<div class='card-samping-view'>
			<div class='card-ppo-container'></div>
			<span class='hide-card-samping-view' style='background:red;padding:3px;cursor:pointer;color:white; font-size:1.5em; font-weight:bold; position:absolute; top:15px; right:20px'> X close</span>
		</div>


<script src="<?php echo base_url('assets/global/plugins/jquery.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/jquery-migrate.min.js'); ?>" type="text/javascript"></script>

<script>

jQuery(document).ready(function() {
	$(".btn-toggle-detail-jual").click(function(){
		$(this).closest('.penjualan-container').find('thead').toggle();
		$(this).closest('.penjualan-container').find('tbody').toggle();
	});

	$(".btn-narrow").click(function(){
		let ini = $(this).closest('tr');
		ini.find('.card-ppo').hide();
		ini.find('.card-ppo-recap').hide();
		ini.find('.card-ppo-sum').hide();
		$(this).hide();
		ini.find('.btn-inline-view').hide();
		ini.find('.btn-expand').show();
	});
	$(".btn-expand").click(function(){
		let ini = $(this).closest('tr');
		ini.find('.card-ppo').show();
		ini.find('.card-ppo-sum').show();
		ini.find('.card-ppo-recap').show();
		$(this).hide();
		ini.find('.btn-inline-view').show();
		ini.find('.btn-narrow').show();
	});

	$(".narrow-all-row").click(function(){
		let ini = $("#general_table");
		ini.find('.card-ppo').hide();
		ini.find('.card-ppo-recap').hide();
		ini.find('.card-ppo-sum').hide();
		ini.find('.btn-narrow').hide();
		ini.find('.btn-inline-view').hide();
		ini.find('.btn-expand').show();
	});
	$(".expand-all-row").click(function(){
		let ini = $("#general_table");
		ini.find('.card-ppo').show();
		ini.find('.card-ppo-sum').show();
		ini.find('.card-ppo-recap').show();
		ini.find('.btn-inline-view').show();
		ini.find('.btn-narrow').show();
		ini.find('.btn-expand').hide();
	});

	$(".btn-inline-view").click(function(){
		let ini = $(this).closest('tr');
		let card_ppo_list = [];
		let card_ppo_show = '';
		ini.find('.card-ppo').each(function(){
			let val = $(this).html();
			let ppo_idx = $(this).find('.ppo-index').html();
			card_ppo_list[ppo_idx] = `<div class='card-ppo'>${val}</div>`;
		});

		
		let nama_warna = ini.find('.nama-warna').html();
		$(".card-samping-view .card-ppo-container").html(`<p style='font-size:2em;color:white'>${nama_warna}</p>${card_ppo_list}`);
		$(".card-samping-view .ket-batch-ppo").show();
		$(".card-samping-view").slideDown();
	});

	$(".hide-card-samping-view").click(function(){
		$(".card-samping-view").hide();
	});

	$(".card-samping-view").on('click','.btn-toggle-detail-jual',function(){
		$(this).closest('.penjualan-container').find('tbody').toggle();
	});


	//===================================================================================

	$(".btn-lt-po-detail").click(function(){
		$(this).closest('div').find('tbody').toggle();
	});

	$(".card-samping-view").on('click','.btn-lt-po-detail',function(){
		$(this).closest('div').find('tbody').toggle();
	});
	$(".btn-view-inlie-by-po").click(function(){
		let ini = $(this);
		let index = ini.attr('data-index');
		let card_ppo_list = [];
		let card_ppo_show = '';
		let nama_batch = ini.closest('div').find('.nama-batch').html();
		$('#table-per-barang').find('.card-ppo-column-'+index).each(function(){
			let val = $(this).html();
			val = val.trim();
			let nama_warna = $(this).closest('tr').find('.nama-warna').html();
			if (val.length > 0) {
				card_ppo_list.push(`<div class='card-ppo-column-samping'><p style='background:lightblue; padding:5px 0px;'><b>${nama_warna}</b></p>${val}</div>`);
			};				
		});
		
		$(".card-samping-view .card-ppo-container").html(`<p style='font-size:2em;color:white'>${nama_batch}</p>${card_ppo_list}`);
		$(".card-samping-view tbody").show();
		$(".card-samping-view").slideDown();
	});

	//===================================================================================
	$(".btn-view-lt-recap-warna").click(function(){
		let ini = $(this);
		let content = $('#recap-warna-all').html();
		console.log(content);
		$(".card-samping-view .card-ppo-container").html(`<table class='lt-table'>${content}</table>`);
		$(".card-samping-view thead").show();
		$(".card-samping-view tbody").show();
		$(".card-samping-view").slideDown();
	});

	//===================================================================================
	$(".btn-view-lt-recap-po").click(function(){
		let ini = $(this);
		let content = $('#recap-po-all').html();
		console.log(content);
		$(".card-samping-view .card-ppo-container").html(`<table class='lt-table'>${content}</table>`);
		$(".card-samping-view thead").show();
		$(".card-samping-view tbody").show();
		$(".card-samping-view").slideDown();
	});


});
</script>