<?foreach ($stok_awal as $row) {
	$sa[$row->warna_id] = $row->qty;
}

print_r($list_stok);
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
echo "<hr/>";

?>

<div style="height:50px;background-image:linear-gradient(90deg, red 33.33%, white 10%, white 40%, blue 66.66%)"></div>
<table>
	<tr>
		<th rowspan='2'>NO/th>
		<th rowspan='2'>Nama Warna</th>
		<th rowspan='2'>Warna_id</th>
		<th rowspan='2'>jual trx</th>
		<th rowspan='2'>sisa jual awal</th>
		<?foreach ($batch_for_pre_po as $row) {
			$batch[$row->po_pembelian_batch_id] = $row->po_pembelian_batch_id?>
			<th colspan='2'><?=$row->batch?></th>
		<?}?>
		<th rowspan='2'>sisa jual trx</th>
	</tr>
	<tr>
		<?foreach ($batch_for_pre_po as $row) {?>
			<th>Masuk</th>
			<th>Keluar</th>
		<?}?>
	</tr>
	<?$no=1;foreach ($data_set as $row) {
		$batch_id = explode(',', $row->batch_id);
		$qty_beli = explode(',', $row->qty_beli);
		$batch_set = array_combine($batch_id, $qty_beli);
		?>
		<tr>
			<td><?=$no?></td>
			<td><?=$row->nama_warna?></td>
			<td><?=$row->warna_id;?></td>
			<!-- jumlah sisa transaksi penjualan -->
			<td><?=(isset($jual[$row->warna_id]) ? count($jual[$row->warna_id]) : '');?></td>
			<!-- sisa qty jual pas stok awal habis -->
			<td><?//=$jual_qty_latest_sisa[$row->warna_id];?></td>
			<?foreach ($batch_for_pre_po as $row2) {
				$terjual[$row->warna_id][$row2->po_pembelian_batch_id] = 0;
				$bm_first[$row->warna_id][$row2->po_pembelian_batch_id] = '';
				$bm_last[$row->warna_id][$row2->po_pembelian_batch_id] = '';
				$first[$row->warna_id][$row2->po_pembelian_batch_id]='';
				$last[$row->warna_id][$row2->po_pembelian_batch_id]='';
				$count_jual[$row->warna_id][$row2->po_pembelian_batch_id]=0;
				$count_jual_by_customer[$row->warna_id][$row2->po_pembelian_batch_id]=0;
				$count_jual_by_non_customer[$row->warna_id][$row2->po_pembelian_batch_id]=0;
				unset($cust_idx);

				?>
				<td>
					<!-- jumlah barang masuk -->
					<?if (isset($stok[$row->warna_id][$row2->po_pembelian_batch_id])) {
						echo (float)$stok[$row->warna_id][$row2->po_pembelian_batch_id]->qty;
						$bm_first[$row->warna_id][$row2->po_pembelian_batch_id] = $stok[$row->warna_id][$row2->po_pembelian_batch_id]->tanggal_first;
						$bm_last[$row->warna_id][$row2->po_pembelian_batch_id] = $stok[$row->warna_id][$row2->po_pembelian_batch_id]->tanggal_last;
					}?>
				</td>
				<td>
					<!-- masih sama barang masuk -->
					<?//=(isset($stok_bm[$row->warna_id][$row2->po_pembelian_batch_id]['qty']) ? $stok_bm[$row->warna_id][$row2->po_pembelian_batch_id]['qty'] : '');?><br/>
					<?if (isset($jual[$row->warna_id]) && isset($stok_bm[$row->warna_id][$row2->po_pembelian_batch_id]['qty'])) {
						$i=0; 
						// walking process nemuin qtyjual meet qty barang masuk 
						foreach ($jual[$row->warna_id] as $key => $value) {
							unset($jual_by_cust);
							if ($stok_bm[$row->warna_id][$row2->po_pembelian_batch_id]['qty'] > 0) {
								if (isset($jual_qty_latest_sisa[$row->warna_id][$key])) {
									// echo '-'.$jual_qty_latest_sisa[$row->warna_id][$key];
									// pengurangan barang masuk dengan dijual 
									$stok_bm[$row->warna_id][$row2->po_pembelian_batch_id]['qty'] -= $jual_qty_latest_sisa[$row->warna_id][$key];
									// assign setting tanggal awal penjualan
									$first[$row->warna_id][$row2->po_pembelian_batch_id] = $latest_data_jual[$row->warna_id][$key]->tanggal;
									// add total terjual per batch
									$terjual[$row->warna_id][$row2->po_pembelian_batch_id] += $jual_qty_latest_sisa[$row->warna_id][$key];
								}else{
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
									if (!isset($cust_idx[$jual[$row->warna_id][$key]->customer_id]) && $jual[$row->warna_id][$key]->customer_id != 0 ) {
										$count_jual_by_customer[$row->warna_id][$row2->po_pembelian_batch_id]++;
										$cust_idx[$jual[$row->warna_id][$key]->customer_id] = 1;
									}else if($jual[$row->warna_id][$key]->customer_id == 0){
										$count_jual_by_non_customer[$row->warna_id][$row2->po_pembelian_batch_id]++;
									}else{
										$cust_idx[$jual[$row->warna_id][$key]->customer_id]++;
									}
									unset($jual[$row->warna_id][$key]);
								}
								# code...
								$i++;
							}
						}?>
						1. Terjual : <?=$terjual[$row->warna_id][$row2->po_pembelian_batch_id];?><br/>
						1. Barang Masuk : <?=$bm_first[$row->warna_id][$row2->po_pembelian_batch_id];?> s/d <?=$bm_last[$row->warna_id][$row2->po_pembelian_batch_id];?> <br/>
						2. Penjualan : <?=$first[$row->warna_id][$row2->po_pembelian_batch_id];?> s/d
						<?=$last[$row->warna_id][$row2->po_pembelian_batch_id];?> <br/>
						3. Jml Trx : <?=$count_jual[$row->warna_id][$row2->po_pembelian_batch_id];?> trx <br/>
						4. Jml Cust : <?=$count_jual_by_customer[$row->warna_id][$row2->po_pembelian_batch_id];?> 
						5. Jml Non Cust : <?=$count_jual_by_non_customer[$row->warna_id][$row2->po_pembelian_batch_id];?> 
					<?}?>
				</td>
			<?}?>
			<td><?=(isset($jual[$row->warna_id]) ? count($jual[$row->warna_id]) : '');?></td>

		</tr>
	<?$no++;}?>
</table>
<?//print_r($jual[116]);?>
<hr/>
<?//print_r($list_stok);?>
