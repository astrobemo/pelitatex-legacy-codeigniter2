<script>

function print_detail_3(printer_name){
	

	// if (idx == 1) {
	// 	qz.websocket.connect().then(function() {
	// 	});
	// };

	// var config = qz.configs.create("Nota");             // Exact printer name from OS
	<?
		$baris25 = "'".sprintf('%-18.14s %-31.30s %-15.15s %-15.15s ', 'Tanda Terima', '', 'Checker', 'Hormat Kami')."'";
   	?>

  	var data = ['\x1B' + '\x40',          // init
  		'\x1B' + '\x21' + '\x18'+ // em mode on
	   	<?="'".sprintf('%38.27s', '')."'";?> + 
	   	'\x1B' + '\x21' + '\x15'+ //spacing
	   	'\x09'+
	   	'\x1B' + '\x21' + '\x39'+ // em mode on
	   	<?="'".sprintf('%24.18s','FAKTUR PENJUALAN')."'";?>+
	   	'\x0A'+

	   	'\x1B' + '\x21' + '\x18'+ // em mode on
	   	<?="'".sprintf('%-33.18s',strtoupper($nama_toko))."'";?>+
	   	'\x1B' + '\x21' + '\x15'+ //spacing
	   	'\x09'+
	   	'\x1B' + '\x21' + '\x18'+ // em mode on
	   	<?="'".sprintf('%44.27s', 'BANDUNG, '.$tanggal_print)."'";?> + '\x0A'+

	   	'\x1B' + '\x21' + '\x18'+ // em mode on
	   	<?="'".sprintf('%-35.35s', strtoupper($alamat_toko) )."'";?>+
	   	'\x1B' + '\x21' + '\x15'+
	   	'\x09'+
	   	'\x1B' + '\x21' + '\x18'+ // em mode on
	   	<?="'".sprintf('%42.40s', 'Kepada Yth,')."'";?> + '\x0A'+

	   	'\x1B' + '\x21' + '\x18'+ // em mode on
	   	<?="'".sprintf('%-4.4s','TELP' )."'";?>+
	   	<?="'".sprintf('%-2.2s',':' )."'";?>+
	   	<?="'".sprintf('%-29.29s',$telepon )."'";?>+
	   	'\x1B' + '\x21' + '\x15'+
	   	'\x09'+
	   	'\x1B' + '\x21' + '\x18'+ // em mode on
	   	<?='"'.sprintf('%42.42s', strtoupper($nama_keterangan) ).'"';?> + '\x0A'+


	   	'\x1B' + '\x21' + '\x18'+ // em mode on
	   	<?="'".sprintf('%-4.4s', ($fax != '' ? 'FAX' : ''))."'";?>+
	   	<?="'".sprintf('%-2.2s', ($fax != '' ? ':' : ''))."'";?>+
	   	<?="'".sprintf('%-25.25s', ($fax != '' ? $fax : ''))."'";?>+
	   	'\x1B' + '\x21' + '\x15'+ //spacing
	   	'\x09'+
	   	'\x1B' + '\x21' + '\x18'+ // em mode on
	   	<?='"'.sprintf('%47.47s', $alamat1).'"';?> + '\x0A'+

	   	'\x1B' + '\x21' + '\x18'+ // em mode on
	   	<?="'".sprintf('%-4.4s', 'NPWP' )."'";?>+
	   	<?="'".sprintf('%-2.2s', ': ' )."'";?>+
	   	<?="'".sprintf('%-29.29s', $npwp )."'";?>+
	   	'\x1B' + '\x21' + '\x15'+ //spacing
	   	'\x09'+
	   	'\x1B' + '\x21' + '\x18'+ // em mode on
	   	<?='"'.sprintf('%42.42s',$alamat2 ).'"';?> + '\x0A'+

   		'\x1B' + '\x21' + '\x18'+ // em mode on
	   	<?="'".sprintf('%-30.30s', '')."'";?>+
	   	'\x1B' + '\x21' + '\x15'+ //spacing
	   	'\x09'+
	   	'\x1B' + '\x21' + '\x18'+ // em mode on
	   	<?="'".sprintf('%47.47s', strtoupper($kota))."'";?> + '\x0A'+


	   	'\x1B' + '\x21' + '\x18'+ // em mode on
	   	<?="'".sprintf("%${'garis1'}80s", '')."'";?>+ '\x0A'+
	   	
	   	'\x1B' + '\x21' + '\x18'+ // em mode on
	   	<?="'".sprintf('%-47.47s', ($po_number != '' ? "PO/Ket : ".$po_number : '') )."'";?>+
	   	'\x1B' + '\x21' + '\x15'+ //spacing
	   	'\x09'+
	   	'\x1B' + '\x21' + '\x18'+ // em mode on
	   	<?="'".sprintf('%30.30s', 'INVOICE NO : '.$no_faktur_lengkap)."'";?> + '\x0A'+

	   	
	   	//==============================================================================
	   	'\x1B' + '\x21' + '\x10'+ // em mode on
	   	<?="'".sprintf("%${'garis1'}80s", '')."'";?>+ '\x0A'+
	   	

	   	'\x1B' + '\x21' + '\x14'+ // em mode on
	   	<?="'".sprintf('%-15.15s', 'Spec ')."'";?>+ '\x09'+
	   	<?="'".sprintf('%-15.15s', 'Warna ')."'";?>+ '\x09'+
	   	<?="'".sprintf('%-2.2s', '|')."'";?>+
	   	<?="'".sprintf('%-4.4s', 'Roll ')."'";?>+ '\x09'+
	   	<?="'".sprintf('%-9.9s', 'Total ')."'";?>+ '\x09'+
	   	<?="'".sprintf('%-1.1s', '|')."'";?>+
	   	<?="'".sprintf('%-1.1s %-40.40s','', 'Detail ')."'";?>+
		'\x0A'+

	   	'\x1B' + '\x21' + '\x10'+ // em mode on
	   	<?="'".sprintf("%${'garis1'}80s", '')."'";?>+ '\x0A'+
		

	   	//==============================================================================
	   	<?


	$i = 1; $baris_print = 0;
	foreach ($data_penjualan_detail_group as $row) {

		$nama_warna = explode('??', $row->nama_warna);
		$data_qty = explode('??', $row->data_qty);
		$qty = explode('??', $row->qty);
		$jumlah_roll = explode('??', $row->jumlah_roll);
		$roll_qty = explode('??', $row->roll_qty);
		
		$data_all = explode('=??=', $row->data_all);

		


		foreach ($nama_warna as $key => $value) {
			$total_roll = 0;
			$total = 0;

			$qty_c = array();

			$qty_detail = explode(' ', $data_qty[$key]);
			$roll_detail = explode(',', $roll_qty[$key]);
			$qty_roll_data = explode('??', $data_all[$key]);
			
			$j = 0; 
			foreach ($qty_detail as $key2 => $value2) {
				$total_roll += $roll_detail[$key2];

				if ($roll_detail[$key2] == 0) {
					$roll_detail[$key2] = 1;
				}
				
				for ($l=0; $l < $roll_detail[$key2] ; $l++) { 
					$total += $qty_detail[$key2];
					$qty_c[$j] = number_format($qty_detail[$key2],'2','.',',');
					$j++;
				}
			}

			// print_r($qty_detail);echo '<br/>';
			// print_r($roll_detail);echo '<hr/>';
			asort($qty_c);

			$jml_angka = count($qty_c);
			// print_r($qty_c);
			$qty_c = array_values($qty_c);
			$jml_angka;
			$baris = ceil($jml_angka/10);
			for ($m=0; $m < $baris ; $m++) { 
				if ($m == 0) {
					$nama_barang = $row->nama_barang;
					$nama_warna_print = $value;
				}else{
					$nama_barang = '';
					$nama_warna_print = '';
				}


			}?>

			<?for ($m=0; $m < $baris ; $m++) { 
				if ($m == 0) {
					$nama_barang = $row->nama_barang;
					$nama_warna_print = $value;
					$qty_total = is_qty_general($total);
					$roll_total = $total_roll;

					$total_show = number_format($total,2,',','.');
					$total_roll_show = $total_roll;
				}else{
					$nama_barang = '';
					$nama_warna_print = '';
					$total_show = '';
					$total_roll_show = '';
				}
				?>
				'\x1B' + '\x21' + '\x14'+ // em mode on
			   	<?="'".sprintf('%-15.15s', $nama_barang)."'";?>+ '\x09'+
			   	<?="'".sprintf('%-15.15s', $nama_warna_print)."'";?>+ '\x09'+
			   	<?="'".sprintf('%-2.2s', '|')."'";?>+
			   	<?="'".sprintf('%3.3s', $total_roll_show)."'";?>+ '\x09'+
			   	<?="'".sprintf('%9.9s', $total_show)."'";?>+ '\x09'+
			   	<?="'".sprintf('%-1.1s', '|')."'";?>+

			   		<?for ($n=0; $n < 10; $n++) { 
			   			$k = 10 * $m + $n;
			   			?>
						<?="'".sprintf('%6.6s', (isset($qty_c[$k]) ? is_qty_general($qty_c[$k]) : '' ) )."'";?>+ '\x09'+
			   		<?}?>

			   	'\x0A'+
			<?
			$baris_print++;
			if ($baris_print == 15) {?>
				'\x0A'+
				'\x0A'+
				'\x1B' + '\x21' + '\x18'+
				<?=$baris25;?>+'\x0A'+
			   	'\x1B' + '\x21' + '\x14'+ // em mode on
				'\x0A'+
				'\x0A'+
				'\x0A'+

			<?};

			if ( ($baris_print - 15 ) % 30 == 0 && $baris_print > 15) {?>
				'\x0A'+
				'\x0A'+
				'\x0A'+
			<?};
			}			
		}

		?>
		'\x1B' + '\x21' + '\x10'+ // em mode on
	   	<?="'".sprintf("%${'garis1'}80s", '')."'";?>+ '\x0A'+
	   	<?
			$baris_print++;

			if ($baris_print == 15) {?>
				'\x0A'+
				'\x0A'+
				'\x1B' + '\x21' + '\x18'+
				<?=$baris25;?>+'\x0A'+
			   	'\x1B' + '\x21' + '\x14'+ // em mode on
				'\x0A'+
				'\x0A'+
				'\x0A'+

			<?};
			if ( ($baris_print - 15 ) % 30 == 0 && $baris_print > 15) {?>
				'\x0A'+
				'\x0A'+
				'\x0A'+
			<?};
	   	?>
		
	<?}?>

	   	//==============================================================================
	   
	   	<?if ($baris_print < 25) {?>
	   		'\x0A'+
			'\x0A'+
			'\x0A'+
	   		'\x1B' + '\x21' + '\x18'+ // em mode on
		   	<?
				echo "'".sprintf('%-1.0s %-12.12s %-5.4s %-12.12s %-5.5s %-15.15s ', '','Tanda Terima', '', '', 'Checker','','Hormat Kami')."'";
			?>+
	   	<?};?>
	   	'\x0A'+
		'\x0A'+
		'\x0A'+

	   	/*
	   	<?
			echo "'".sprintf('%-15.12s %-4.4s %-31.30s %-12.11s %-12.11s ', 'TOTAL ROLL', $total_roll, '', 'Total*', number_format($total,'0',',','.'))."'";?> + '\x0A',<?
	   	?>		   	
	   	<?="'".sprintf("%${'garis1'}80s", '')."'";?>+ '\x0A',
	  	'\x0A',
		*/
	   	'\x0D'+          // cut paper
	   	'\x0C',  // Generate Pulse to kick-out cash drawer**
	                                                // **for legacy drawer cable CD-005A.  Research before using.
	                                                // see also http://keyhut.com/popopen4.htm
    ];
	console.log(data);

	// qz.print(config, data).then(function() {
	//    // alert("Sent data to printer");
	// });

	// console.log(printer_name);

    webprint.printRaw(data, printer_name);
	
}

</script>