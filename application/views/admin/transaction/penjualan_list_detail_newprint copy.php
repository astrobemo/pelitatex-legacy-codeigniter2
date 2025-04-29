
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>PRINT</title>

	
<script src="<?php echo base_url('assets/global/plugins/jquery.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/jquery-migrate.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/jquery-ui/jquery-ui.min.js'); ?>" type="text/javascript"></script>
</head>




<?

	$status_aktif  ='';
	$penjualan_id = '';
	$customer_id = '';
	$nama_customer = '';
	$gudang_id = '';
	$nama_gudang = '';
	$no_faktur = '';
	$tanggal = date('d/m/Y');
	$tanggal_print = '';
	$ori_tanggal = '';
	$po_number = '';
	$note_info = ''; 

	$jatuh_tempo = date('d/m/Y', strtotime("+60 days") );
	$ori_jatuh_tempo = '';
	$status = -99;

	$diskon = 0;
	$ongkos_kirim = 0;
	$nama_keterangan = '';
	$alamat_keterangan = '';
	$kota = '';
	$keterangan = '';
	$penjualan_type_id = 3;
	$tipe_penjualan = '';
	$customer_id = '';
	$no_faktur_lengkap = '';
	$no_surat_jalan = '';
	$fp_status = 1;

	$nik = '';
	$npwp = '';

	$g_total = 0;
	$readonly = '';
	$disabled = '';
	$alamat_customer = '';
	$disabled_status = '';
	$bg_info = '';
	$closed_date = '';
	$warning_type = 0;
	$limit_warning_amount = 0;
	$limit_amount = 0;
	$batas_atas = 0;
	$limit_sisa_atas = 0;
	$sisa_update = 0;
	$limit_sisa = 0;
	$ppn = $ppn_set;

	$hidden_spv = '';
	// if (is_posisi_id() != 1) {
		$hidden_spv = 'hidden';
	// }

	foreach ($penjualan_data as $row) {
		$tipe_penjualan = $row->tipe_penjualan;
		$penjualan_id = $row->id;
		$customer_id = $row->customer_id;
		$nama_customer = $row->nama_keterangan;
		$alamat_filter_0 = str_replace('BLOK - ', '', $row->alamat_keterangan);
		$alamat_filter_0_1 = str_replace('No.- ', '', $alamat_filter_0);
		$alamat_filter_1 = str_replace('RT:000 RW:000 ', '', $alamat_filter_0_1);
		$alamat_filter_2 = str_replace('Kel.-', '', $alamat_filter_1);
		$alamat_filter_3 = str_replace('Kel.-', ',', $alamat_filter_2);
		// $alamat_filter_4 = str_replace('Kec.', ',', $alamat_filter_3);
		$alamat_final = $alamat_filter_3;
		$alamat_customer = $alamat_final;
		// $gudang_id = $row->gudang_id;
		// $nama_gudang = $row->nama_gudang;
		$no_faktur = $row->no_faktur;
		$penjualan_type_id = $row->penjualan_type_id; 
		$po_number = $row->po_number;
		$fp_status = $row->fp_status;
		$iClass = '';

		$closed_date = $row->closed_date;
		
		$tanggal_print = date('d F Y', strtotime($row->tanggal));

		$tanggal = is_reverse_date($row->tanggal);
		$ori_tanggal = $row->tanggal;
		$status_cek = 0;

		if ($penjualan_type_id == 1) {
			$note_info = "note note-info";
			$bg_info = "background: #95a5a6";

		}elseif ($penjualan_type_id == 2) {
			$note_info = "note note-warning";
			$bg_info = "background: #d35400";
		}elseif ($penjualan_type_id == 3) {
			$note_info = "note note-success";
			$bg_info = "background: #2980b9";
		}


		if ($penjualan_type_id == 2) {
			$dt = strtotime(' +'.get_jatuh_tempo($customer_id).' days', strtotime($row->tanggal) );
			if ($row->jatuh_tempo == $row->tanggal) {
				$status_cek = 1;
			}
		}
		$get_jt = '';
		if (isset($dt)) {
			$get_jt = ($row->jatuh_tempo == '' || $status_cek == 1  ? date('Y-m-d',$dt) : $row->jatuh_tempo);
		}
		// print_r($get_jt);
		$jatuh_tempo = is_reverse_date($get_jt);
		$ori_jatuh_tempo = $row->jatuh_tempo;
		$status = $row->status;
		
		$diskon = $row->diskon;
		$ongkos_kirim = $row->ongkos_kirim;
		$status_aktif = $row->status_aktif;
		$nama_keterangan = $row->nama_keterangan;
		$alamat_keterangan = $alamat_customer;
		$kota = $row->kota;
		$keterangan = $row->keterangan;
		$customer_id = $row->customer_id;
		$no_faktur_lengkap = $row->no_faktur_lengkap;
		$no_surat_jalan = $row->no_surat_jalan;
		$ppn = $row->ppn;

		if ($status_aktif == -1 ) {
			$note_info = 'note note-danger';
		}

	}

	$ppn_fix = $ppn;

	$nama_bank = '';
	$no_rek_bank = '';
	$tanggal_giro = '';
	$jatuh_tempo_giro = '';
	$no_akun = '';

	foreach ($data_giro as $row) {
		$nama_bank = $row->nama_bank;
		$no_rek_bank = $row->no_rek_bank;
		$tanggal_giro =is_reverse_date($row->tanggal_giro) ;
		$jatuh_tempo_giro = is_reverse_date($row->jatuh_tempo);
		$no_akun = $row->no_akun;
	}

	$tanggal_ambil = '';
	$tipe_ambil_barang_id = '';
	$status_ambil = '';
	/*foreach ($penjualan_posisi_barang as $row) {
		$tipe_ambil_barang_id = $row->tipe_ambil_barang_id;
		$tanggal_ambil = is_reverse_date($row->tanggal_pengambilan);
		$status_ambil = $row->status;
	}*/

	if ($status != 1) {
		if ( is_posisi_id() != 1 ) {
			$readonly = 'readonly';
		}
	}

	foreach ($customer_data as $row) {
		$nik = $row->nik;
		$npwp = $row->npwp;
	}

	if ($penjualan_id == '') {
		$disabled = 'disabled';
	}

	if ($status != 0) {
		$disabled_status = 'disabled';
	}

	$lock_ = '';
	$read_ = '';
	if (is_posisi_id() == 6) {
		// $disabled = 'disabled';
		// $readonly = 'readonly';
	}

	$nama_toko = '';
	$alamat_toko = '';
	$telepon_toko = '';
	$fax = '';
	$npwp = '';
	$kota_toko = "";



	if ($penjualan_id != '') {

		foreach ($data_toko as $row) {
			$nama_toko = trim($row->nama);
			$alamat_toko = trim($row->alamat);
			$telepon_toko = trim($row->telepon);
			$fax = trim($row->fax);
			$npwp = trim($row->NPWP);
			$kota_toko = trim($row->kota);

		}

		$garis1 = "'-";
		$garis2 = "=";

		$cek_alamat = preg_split('/\r\n|[\r\n]/', $alamat_keterangan);
		$array_rep = ["\n","\r"];
		$alamat_keterangan = str_replace($array_rep, ' ', $alamat_keterangan);

		$alamat1 = substr(strtoupper(trim($alamat_keterangan)), 0,47);
		$alamat2 = substr(strtoupper(trim($alamat_keterangan)), 47);
		$last_1 = substr($alamat1, -1,1);
		$last_2 = substr($alamat2, 0,1);

		$positions = array();
		$pos = -1;
		while (($pos = strpos(trim($alamat_keterangan)," ", $pos+1 )) !== false) {
			$positions[] = $pos;
		}

		$max = 47;
		if ($last_1 != '' && $last_2 != '') {
			$posisi =array_filter(array_reverse($positions),
				function($value) use ($max) {
					return $value <= $max;
				});

			$posisi = array_values($posisi);

			$alamat1 = substr(strtoupper(trim($alamat_keterangan)), 0,$posisi[0]);
			$alamat2 = substr(strtoupper(trim($alamat_keterangan)), $posisi[0]);
		}
	}
?>
<?

	$TOTALROW = 0;
	$TOTALPAGE = 1;
	$PAGEDETAIL = 0;
	$row_detail_print = [];
	$is_packing_list = false;
	$is_sj = false;
	

	$bT =  'border-top:1px solid #ccc; ';
	$bL =  'border-left:1px solid #ccc; ';
	$bR =  'border-right:1px solid #ccc; ';
	$bB =  'border-bottom:1px solid #ccc; ';
	$pR1 = 'padding-right:1mm; ';
	$pR2 = 'padding-right:1.3mm; ';

	$table_header_jual = "<table id='tbl-print' width='100%' style='border-collapse: collapse;' cellspacing='0'>
		<tr>
			<td style='height:7mm'><div style='font-size:1.1em'>INVOICE [PJ01]</div></td>
			<td style='text-align:right'>$tanggal_print</td>
		</tr>
		<tr>
			<td style='font-size:0.9em'>
				".strtoupper($nama_toko)."<br/>
				".strtoupper($alamat_toko)."<br/>
				".strtoupper($kota_toko)." | TELP : ".$telepon_toko."
			</td>
			<td style='text-align:right; font-size:0.9em'>
				KEPADA YTH.  <br/>
				".strtoupper($nama_keterangan)." <br/>
				".strtoupper($alamat1)." <br/>
				".strtoupper($alamat2)."
			</td>
		</tr>
		<tr>
			<td style='border-top:1px solid #ccc; border-bottom:1px solid #ccc;'>".($po_number != '' ? 'PO/Ket : '.$po_number : '')."</td>
			<td style='text-align:right; border-top:1px solid #ccc; border-bottom:1px solid #ccc;'>INVOICE NO : ".$no_faktur_lengkap."</td>
		</tr>
	</table>";

	$bT =  'border-top:1px solid #ccc; ';
	$bL =  'border-left:1px solid #ccc; ';
	$bR =  'border-right:1px solid #ccc; ';
	$bB =  'border-bottom:1px solid #ccc; ';
	$pR1 = 'padding-right:1mm; ';
	$pR2 = 'padding-right:1.3mm; ';

	$table_barang = "<table width='100%' id='tbl-body'  style='font-size:2.75mm; border-collapse: collapse;' cellspacing='0'>
	<tr>
		<td style='$bB solid #ccc; text-align:center; width:6mm;'>NO</td>
		<td style='$bB text-align:right; width:12mm'>JML</td>
		<td style='$bB text-align:center; '>SAT</td>
		<td style='$bB text-align:center; '>ROLL</td>
		<td style='$bB width:6mm'></td>
		<td style='$bB'>BARANG</td>
		<td style='$bB'>HARGA</td>
		<td style='$bB text-align:center; '>TOTAL</td>
		<td style='$bB width:5mm'></td>

	</tr>";
	
	$total = 0; $total_qty = 0; $total_roll = 0; 
	// print_r($penjualan_print);
	$idx = 1;
	
	foreach ($penjualan_print as $row) {
			$total += $row->qty * $row->harga_jual;
			$total_qty += $row->qty;
			$total_roll += $row->jumlah_roll;
			$TOTALROW += ceil($row->jumlah_roll/10) + 1;


		$table_barang .= "<tr>
			<td style='height:5mm; text-align:center;'>$idx</td>
			<td style='text-align:right;'>".str_replace(',00','',number_format($row->qty,'2',',','.'))."</td>
			<td style='text-align:center;'>".strtoupper($row->nama_satuan)."</td>
			<td style='text-align:center;'>".($row->tipe_qty != 3 ? $row->jumlah_roll : '')."</td>
			<td></td>
			<td>".strtoupper($row->nama_jual_tercetak)."</td>
			<td>".number_format($row->harga_jual,'0',',','.')."</td>
			<td style='text-align:right; $pR2'>".number_format($row->qty*$row->harga_jual,'0',',','.')."</td>
			<td style='width:5mm'></td>

		</tr>";
		$idx++;
		}
	
	$table_barang .= "<tr>
		<td style='$bT $bL $bB text-align:center'>TOTAL</td>
		<td style='$bT $bB text-align:right;'>".str_replace(',00','',number_format($total_qty,'2',',','.'))."</td>
		<td style='$bT $bB text-align:center;'>YARD</td>
		<td style='$bT $bR $bB text-align:center'>".$total_roll."</td>
		<td></td>
		<td></td>
		<td style=' $bT $bL '>TOTAL *</td>
		<td style='$bT $pR1 text-align:right;'>".number_format($total,'0',',','.')."</td>
		<td style='$bT $bR width:5mm'></td>

	</tr>";

	$total_bayar = 0;
	foreach ($data_pembayaran as $row) {
		$total_bayar += $row->amount;
		$table_barang .= "<tr>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td style='$bL'>".$row->nama_bayar."</td>
			<td style='$pR1 text-align:right;'>".number_format($row->amount,'0',',','.')."</td>
			<td style='$bR width:5mm'></td>

		</tr>";
	}

	$table_barang .= "<tr>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td style='$bT $bL $bB '>KEMBALI</td>
		<td style='$bT $bB $pR1 height:5mm; text-align:right;'>".number_format($total_bayar - $total,'0',',','.')."</td>
		<td style='$bT $bR $bB width:5mm'></td>

	</tr>
	<tr>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td style='$pR1 font-size:2mm; text-align:right;'><span>*harga sudah termasuk ppn</span></td>
			<td style='width:5mm'></td>
	</tr>
	</table>";



	$style='vertical-align:top; border-top:1px solid black; ';
	$table_detail = "<table width='100%' id='tbl-qty-detail'  style='font-size:2.8mm; border-collapse: collapse; border-bottom:1px solid black;' cellspacing='0'>";
		$table_detail_header = "
		<tr>
			<td style='$style width:28mm; text-align:left'>KODE</td>
			<td style='$style width:30mm; text-align:left'>WARNA</td>
			<td style='$style width:10mm; text-align:center'>UNIT</td>
			<td style='$style width:12mm; text-align:right'>ROLL</td>
			<td style='$style $bR solid black; width:17mm;text-align:right; padding-right:3mm; '>TOTAL</td>
			<td style='$style text-align:center; width:120mm' colspan='10'>DETAIL</td>
		</tr>";

		$table_detail_body = [];
		$table_detail_body[0] = "";
		foreach ($data_penjualan_detail_group as $row) {
			$nama_warna = explode('??',$row->nama_warna);
			$warna_id = explode('??',$row->warna_id);
			$total_qty = explode('??', $row->total_qty);
			$total_roll = explode('??', $row->total_roll);
			$data_qtys = explode('??', $row->qty);
			$data_rolls = explode('??', $row->jumlah_roll);
			

			$row_detail_print[$row->barang_id] = array(
				"nama_barang" => $row->nama_barang,
				"data_warna" => array()

			);
			foreach ($nama_warna as $key => $value) {
				$table_detail_body[0] .= "<tr>";
				if ($key == 0) {
					$table_detail_body[0] .= "<td rowspan = '".count($nama_warna)."' style='$style height:'".(count($nama_warna) * 4 * ceil($total_roll[$key]/10))."mm'>".strtoupper($row->nama_barang)."</td>";
				}

				$data_qty = explode(',', $data_qtys[$key]);
				$data_roll = explode(',', $data_rolls[$key]);
				$q_array = [];
				$q_100 = [];
				$q_pecah = [];
				$q_row = 0;
				foreach ($data_qty as $k => $v) {
					$x = $data_roll[$k];
					for ($y=0; $y < $x ; $y++) {
						if ($v == 100) {
							array_push($q_100, $v);
						}else{
							array_push($q_pecah, $v);
						}
					}
				}
				
				rsort($q_pecah);
				$q_array = array_merge($q_100, $q_pecah);



				$packing_list = "";
				$q_row = ceil($row->total_roll/10);

				array_push($row_detail_print[$row->barang_id]["data_warna"], array(
					"nama_warna" => $nama_warna[$key],
					"nama_satuan" => $row->nama_satuan,
					"total_roll" => $total_roll[$key],
					"total_qty" => $total_qty[$key],
					"total_row" => $q_row,
					"packing_list" => array()
				));

				for ($aa=0; $aa < $q_row ; $aa++) {
					$row_render = ""; 
					// if ($aa % 5 ==0 && $aa != 0) {
					// 	$row_render .=  "<td style='height:1mm; ' colspan='10'></td>";
					// 	array_push($row_detail_print[$row->barang_id]["data_warna"][$key]["packing_list"], $row_render);
					// 	$row_render = ""; 
					// }
					for ($z=0; $z < 10 ; $z++) {
						$border = 0;
						$i_arr = ($aa*10)+$z;
						if ($z == 0) {
							$packing_list .= "<tr>";
						}
						$packing_list .= "<td style='height:4mm; width:10.5mm; text-align:right; '>
								".(isset($q_array[$i_arr]) ? str_replace('.00', '', $q_array[$i_arr]) : '')."
							</td>";
						if ($z == 9 ) {
							$packing_list .= "</tr>";
						}


						$row_render .=  "<td style='height:4mm; width:10.5mm; text-align:right; ".($aa==0 ? $bT : "")." '>
							".(isset($q_array[$i_arr]) ? str_replace('.00', '', $q_array[$i_arr]) : '')."
						</td>";
					}

					array_push($row_detail_print[$row->barang_id]["data_warna"][$key]["packing_list"], $row_render);
				}

				$table_detail_body[0] .= "<td style=' $style font-size:2.5mm'>".strtoupper($nama_warna[$key])."</td>
					<td style='$style text-align:center'>".strtoupper($row->nama_satuan)."</td>
					<td style='$style text-align:center'>".$total_roll[$key]."</td>
					<td style='$style border-right:1px solid black; text-align:center'>
						".str_replace(',00','',number_format($total_qty[$key],'2',',','.'))."
					</td>
					<td style=' $style padding:0mm'>
						<table> $packing_list </table>
					</td>
				</tr>";
			}
		}

		// $table_detail .= $table_detail_body;
		// $table_detail .="</table>";

		$PAGEDETAIL = 0;
		$table_row = array();
		$max_row = 20;
		if ($TOTALROW > 11) {
			$table_detail_body[$PAGEDETAIL] = "";
			$row_idx = 0;
			foreach ($row_detail_print as $row) {
				// echo $row['nama_barang'];
				foreach ($row['data_warna'] as $k => $vl) {
					$row_after = $row_idx + $vl['total_row'];
					$style = "";
					$sisa_row = count($vl['packing_list']);
					foreach ($vl['packing_list'] as $k2 => $vl2) {
						$row_idx++;
						if ($k2 == 0) {
							$table_detail_body[$PAGEDETAIL] .= "<tr>
								<td 
								style='$bT height: 4mm'>".
								strtoupper($row['nama_barang']).
								"</td>
								<td style='$bT '>".strtoupper($vl['nama_warna'])."</td>
								<td style='$bT text-align:center; '>".strtoupper($vl['nama_satuan'])."</td>
								<td style='$bT text-align:right'>".strtoupper($vl['total_roll'])."</td>
								<td style='$bT $bR text-align:right; padding-right:3mm'>".strtoupper((float)$vl['total_qty'])."</td>
								$vl2</tr>";
						}else{
							$table_detail_body[$PAGEDETAIL] .= "<tr><td 
								style='height: 4mm'></td>
								<td style=''></td>
								<td style=''></td>
								<td style=''></td>
								<td style='$bR'></td>
								$vl2
								</tr>";
						}

						if ($row_idx >= $max_row) {
							$PAGEDETAIL++;
							$table_detail_body[$PAGEDETAIL] = "";
							$row_idx = 0;
						}else if($row_idx !== 0 && $row_idx%5 == 0 ){
							$table_detail_body[$PAGEDETAIL] .= "<tr><td 
								style='$bR height:1mm; padding:0px' colspan='5'></td>
								<td style='height:1mm; padding:0px' colspan='10'></td>
								</tr>";
						}
						$sisa_row--;
					}
				}
			}
		}

		// $table_detail .= $table_detail_body;
		// $table_detail .="</table>";
		$TOTALPAGE = ($TOTALROW < 11 ? 1 : $PAGEDETAIL+2);

?>
	
<body>
<div id="testTbl" style="margin-bottom:20px;">
	
	<div id="page1">
		<div style="width:199mm; height:139mm; border:0.1mm solid #ccc; padding:1mm; position:relative">
			
			<?=$table_header_jual;?>
	
			<div style="height:85mm">
				<?if ($TOTALROW < 11) {?>
					<?=$table_barang;?>
					<?=$table_detail?>
					<?=$table_detail_header?>
					<?=$table_detail_body[0]."</table>";?>
					
				<?}else{?>
					<?=$table_barang;?>
				<?}?>
			</div>
					
			<br/>
			<div style="position:absolute; bottom:10mm; width:18cm; ">
				<table style="font-size:3mm;"  width="100%" >
					<tr>
						<td style="text-align:center">TANDA TERIMA</td>
						<td style="text-align:center">CHECKER</td>
						<td style="text-align:center">HORMAT KAMI</td>
						<td style="text-align:center; font-size:2mm"></td>
					</tr>
				</table>
				<div style="bottom:5mm; text-align:right; font-size:2mm">HAL 1/<?=$TOTALPAGE;?></div>
			</div>
		</div>
	</div>

	<?if ($TOTALROW > 11) {

		for ($pp=0; $pp < $PAGEDETAIL+1 ; $pp++) { ?>
			<div id="page<?=$pp+2;?>">
				<div style="width:199mm; height:139mm; border:0.1mm solid #ccc; padding:1mm; position:relative">
					<div style="height:115mm;  border:0.1mm solid #ccc;">
						<?=$table_detail?>
						<?=($pp==0 ? $table_detail_header : "");?>
						<?=$table_detail_body[$pp]."</table>";?>
					</div>
					<div style="position:absolute; bottom:10mm; width:18cm; ">		
						<div style="bottom:5mm; text-align:right; font-size:2mm">HAL <?=$pp+2;?>/<?=$TOTALPAGE;?></div>
					</div>
				</div>
			</div>
		<?}?>
	<?}?>
</div>
Printers: <select id="printerlist"></select>
<button onclick="webprint.requestPrinters();">Refresh</button><br/>
<!-- <button onclick="webprint.printRaw(esc_init+esc_p+esc_init, $('#printerlist').val());">Cash Draw</button><br/>
<button onclick="webprint.printRaw(getEscSample($('#cutter').is(':checked'),$('#image').is(':checked')), $('#printerlist').val());">Print</button><br/> -->
<button onclick="printFakturHTML()">PrintHTML</button><br/>

<iframe id="webPrintFrame" name="webPrintFrame"  src="http://127.0.0.1:8080/printwindow" hidden></iframe>


</body>
</html>
<?if (is_posisi_id()==1) {?>


<script src="<?php echo base_url('assets_noondev/js/webprint.js'); ?>" type="text/javascript"></script>

<script>
var esc_init = "\x1B" + "\x40"; // initialize printer
var esc_p = "\x1B" + "\x70" + "\x30"; // open drawer
var gs_cut = "\x1D" + "\x56" + "\x4E"; // cut paper
var esc_a_l = "\x1B" + "\x61" + "\x30"; // align left
var esc_a_c = "\x1B" + "\x61" + "\x31"; // align center
var esc_a_r = "\x1B" + "\x61" + "\x32"; // align right
var esc_double = "\x1B" + "\x21" + "\x31"; // heading
var font_reset = "\x1B" + "\x21" + "\x02"; // styles off
var esc_ul_on = "\x1B" + "\x2D" + "\x31"; // underline on
var esc_bold_on = "\x1B" + "\x45" + "\x31"; // emphasis on
var esc_bold_off = "\x1B" + "\x45" + "\x30"; // emphasis off



jQuery(document).ready(function() {
	

	//===========================print=================================

	var populatePrinters = function(printers){
		var printerlist = $("#printerlist");
		printerlist.html('');
		for (var i in printers){
			printerlist.append('<option value="'+printers[i]+'">'+printers[i]+'</option>');
		}
	};

	webprint = new WebPrint(true, {
		relayHost: "127.0.0.1",
		relayPort: "8080",
		listPrinterCallback: populatePrinters,
		readyCallback: function(){
			webprint.requestPrinters();
		}
	});
});

function testPrintHTML(){
	
}

function print_faktur(printer_name){

	// if (idx == 1) {
	// 	qz.websocket.connect().then(function() {
	// 	});
	// };

	// var config = qz.configs.create("Nota");             // Exact printer name from OS
	// var data = ['\x1B' + '\x40'+          // init
		
	// ];
	// console.log(data);
	// webprint.printRaw(data, printer_name);

	


	// qz.print(config, data).then(function() {
	//    // alert("Sent data to printer");
	// });
}

function printFakturHTML(){
	const printer_name = $('#printerlist').val();
	let data = [];
	const main = document.querySelector('#testTbl');
	const TOTALPAGE = "<?=$TOTALPAGE?>";
	for (x=0; x < TOTALPAGE ; x++) { 
		const a = main.querySelector(`#page${x+1}`);
		if (a) {
			data.push(`<html>
				<body style="width:195mm; height:139mm;">${a.innerHTML}</body>
				</html>`);
		}
	}

	for (let x = 0; x < data.length; x++) {
		webprint.printHtml(data[x], printer_name);
	}

}

</script>
<?}?>





