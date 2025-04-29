
<?if (is_posisi_id()==1) {

	$TOTALROW = 0;
	$TOTALPAGE = 1;
	$PAGEDETAIL = 0;
	$row_detail_print = [];
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

	$bdT =  'border-top:1px dashed #ccc; ';
	$bdL =  'border-left:1px dashed #ccc; ';
	$bdR =  'border-right:1px dashed #ccc; ';
	$bdB =  'border-bottom:1px dashed #ccc; ';
	

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
									style='height:1mm'></td>
									<td style=''></td>
									<td style=''></td>
									<td style=''></td>
									<td style='$bR'></td>
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
		

	<div id="testTbl" style="margin-bottom:20px;">
		
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

		<?if ($TOTALROW > 11) {

			for ($pp=0; $pp < $PAGEDETAIL+1 ; $pp++) { ?>
				<div style="width:199mm; height:139mm; border:0.1mm solid #ccc; padding:1mm; position:relative">
					<div style="height:115mm;  border:0.1mm solid #ccc;">
						<?=$table_detail?>
						<?=$table_detail_body[$pp]."</table>";?>
					</div>
					<div style="position:absolute; bottom:10mm; width:18cm; ">		
						<div style="bottom:5mm; text-align:right; font-size:2mm">HAL <?=$pp+2;?>/<?=$TOTALPAGE;?></div>
					</div>
				</div>
			<?}?>

		<?}?>
	</div>

	<?function printDetailPage2(){

	}?>


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
		const data = `<html>
			<body style="width:195mm; height:<?=$TOTALPAGE*139?>mm;">`+
				$('#testTbl').html()+
				`</body>
			</html>`;
		webprint.printHtml(data, printer_name);

		const data2 = ['\x1B' + '\x40'+  // init
			'\x1B' + '\x21' + '\x04'+ // em mode on
			'\x0A'+
			'\x0A'+
			'\x0A'+
			'\x0A'+
			'\x0A'+
			
			'\x1D' + '\x56',          // cut paper
	   	
			];

		webprint.printRaw(data2, printer_name);
	}
	
</script>
<?}?>

