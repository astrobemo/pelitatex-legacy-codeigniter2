<script>

function print_surat_jalan(printer_name){
	

	<?
	$i = 1; $baris_print = 0;
	$baris_idx = 16;
	$count = count($penjualan_print);

	foreach ($data_penjualan_detail_group as $row) {

		$nama_warna = explode('??', $row->nama_warna);
		$data_qty = explode('??', $row->data_qty);
		$qty = explode('??', $row->qty);
		$jumlah_roll = explode('??', $row->jumlah_roll);
		$roll_qty = explode('??', $row->roll_qty);
		
		$data_all = explode('=??=', $row->data_all);

		foreach ($nama_warna as $key => $value) {
			$total = 0;
			$total_roll = 0;

			$qty_c = array();

			$qty_detail = explode(' ', $data_qty[$key]);
			$roll_detail = explode(',', $roll_qty[$key]);
			
			$j = 0; 
			foreach ($qty_detail as $key2 => $value2) {
				if ($roll_detail[$key2] == 0) {
					$roll_detail[$key2] = 1;
				}
				
				for ($l=0; $l < $roll_detail[$key2] ; $l++) { 
					$qty_c[$j] = number_format($qty_detail[$key2],'2','.',',');
					$j++;
					
				}
			}

			asort($qty_c);

			$qty_c = array_values($qty_c);
			$baris = ceil($jml_angka/10);
			
			for ($m=0; $m < $baris ; $m++) { 
		   		$baris_idx++;
			}
			$baris_idx++;
		}
	}

	$baris_total = $baris_idx + $count;

		if ($baris_idx > 20 ) { ?>

			var data = ['\x1B' + '\x40'+          // init
			   	'\x1B' + '\x21' + '\x39'+ // em mode on
			   	<?="'".sprintf('%-20.18s','SURAT JALAN')."'";?>+
			   	'\x1B' + '\x21' + '\x15'+
			   	'\x09'+
			   	'\x1B' + '\x21' + '\x18'+ // em mode on
			   	<?="'".sprintf('%44.27s', 'BANDUNG, '.$tanggal_print)."'";?> + '\x0A'+

			   	'\x1B' + '\x21' + '\x18'+ // em mode on
			   	<?="'".sprintf('%-35.35s', strtoupper($nama_toko) )."'";?>+
			   	'\x1B' + '\x21' + '\x15'+
			   	'\x09'+
			   	'\x1B' + '\x21' + '\x18'+ // em mode on
			   	<?="'".sprintf('%42.40s', 'Kepada Yth,')."'";?> + '\x0A'+

			   	'\x1B' + '\x21' + '\x18'+ // em mode on
			   	<?="'".sprintf('%-35.35s', strtoupper($alamat_toko))."'";?>+
			   	'\x1B' + '\x21' + '\x15'+
			   	'\x09'+
			   	'\x1B' + '\x21' + '\x18'+ // em mode on
			   	<?='"'.sprintf('%42.42s', strtoupper($nama_keterangan) ).'"';?> + '\x0A'+


			   	'\x1B' + '\x21' + '\x18'+ // em mode on
			   	<?="'".sprintf('%-31.31s', 'TELP:'.$telepon)."'";?>+
			   	'\x1B' + '\x21' + '\x15'+ //spacing
			   	'\x09'+
			   	'\x1B' + '\x21' + '\x18'+ // em mode on
			   	<?="'".sprintf('%47.47s', $alamat1)."'";?> + '\x0A'+

			   	'\x1B' + '\x21' + '\x18'+ // em mode on
			   	<?="'".sprintf('%-35.35s', ($fax != '' ? 'FAX:'.$fax : ''))."'";?>+
			   	'\x1B' + '\x21' + '\x15'+ //spacing
			   	'\x09'+
			   	'\x1B' + '\x21' + '\x18'+ // em mode on
			   	<?="'".sprintf('%42.42s',$alamat2 )."'";?> + '\x0A'+

		   		'\x1B' + '\x21' + '\x18'+ // em mode on
			   	<?="'".sprintf('%-30.30s', 'NPWP : '.$npwp)."'";?>+
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
			   	<?="'".sprintf("%${'garis1'}80s", '')."'";?>+ '\x0A'+
			   	'\x1B' + '\x21' + '\x18'+ // em mode on
			   	<?="'".sprintf('%-6.6s', 'JUMLAH ')."'";?>+'\x09'+
			   	<?="'".sprintf('%-6.6s', 'SATUAN ')."'";?>+'\x09'+
			   	<?="'".sprintf('%-4.4s', 'ROLL ')."'";?>+'\x09'+
			   	<?="'".sprintf('%-10.10s', 'NAMA ')."'";?>+'\x09'+
			   	<?="'".sprintf('%-27.27s', 'KODE ')."'";?>+'\x09'+
			   	<?="'".sprintf('%-8.8s', 'HARGA ')."'";?>+'\x0A'+
			   	'\x1B' + '\x21' + '\x18'+ // em mode on
			   	<?="'".sprintf("%${'garis1'}80s", '')."'";?>+ '\x0A'+
				

			   	//==============================================================================
			   	<?
			   	$total = 0; $total_roll = 0; $idx = 0;
			   	foreach ($penjualan_print as $row) {
			   		$total += $row->qty * 0;
			   		$total_roll += $row->jumlah_roll;?>
			   			'\x1B' + '\x21' + '\x18'+ // em mode on
					   	<?="'".sprintf('%6.6s', is_qty_general($row->qty))."'";?>+'\x09'+
					   	<?="'".sprintf('%6.6s', $row->nama_satuan)."'";?>+'\x09'+
					   	<?="'".sprintf('%4.4s', ($row->tipe_qty != 3 ? $row->jumlah_roll : '') )."'";?>+'\x09'+
					   	<?="'".sprintf('%-12.12s', strtoupper($row->jenis_barang))."'";?>+
					   	<?="'".sprintf('%-27.27s', strtoupper($row->nama_barang))."'";?>+'\x09'+
					   	<?="'".sprintf('%-8.8s', number_format($row->harga_jual,'0',',','.'))."'";?>+'\x0A'+
					   	
			   	<?$idx++;}?>
			   	<?for ($i = $idx; $i < 10; $i++) {?>
			   		'\x0A'+
			   	<?};?>
			   	
			   	<?="'".sprintf("%${'garis1'}80s", '')."'";?>+ '\x0A'+
			   	<?="'".sprintf('%10.10s', 'TOTAL ROLL')."'";?>+
			   	<?="'".sprintf('%4.4s', ' ')."'";?>+
			   	<?="'".sprintf('%4.4s', $total_roll)."'";?>+'\x0A'+

			   	//==============================================================================

			   	'\x0A'+
			   	'\x0A'+
			   	'\x0A'+
			   	'\x0A'+
			   	'\x0A'+
			   	'\x0A'+
			   	'\x1B' + '\x21' + '\x19'+ // em mode on
			   	<?="'".sprintf('%-18.14s %-5.5s %-15.15s %-15.15s ', 'Tanda Terima', '', 'Checker', 'Hormat Kami')."'";?> + 
			   	'\x0A'+
			   	'\x0A'+
			   	'\x0A'+

			   	//=========================================detail=========================================================

			   	//==============================================================================
			   	'\x1B' + '\x21' + '\x10'+ // em mode on
			   	<?="'".sprintf("%${'garis1'}80s", '')."'";?>+ '\x0A'+
			   	

			   	'\x1B' + '\x21' + '\x14'+ // em mode on
			   	<?="'".sprintf('%-15.15s', 'Kode ')."'";?>+ '\x09'+
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

				$i = 1; $baris_print = 3;
				foreach ($data_penjualan_detail_group as $row) {

					$nama_warna = explode('??', $row->nama_warna);
					$data_qty = explode('??', $row->data_qty);
					$qty = explode('??', $row->qty);
					$jumlah_roll = explode('??', $row->jumlah_roll);
					$roll_qty = explode('??', $row->roll_qty);
					
					$data_all = explode('=??=', $row->data_all);

					


					foreach ($nama_warna as $key => $value) {

						$total = 0;
						$total_roll = 0;
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
						
						if ( $baris_print % 30 == 0 && $baris_print > 0) {?>
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

						if ( $baris_print % 30 == 0  && $baris_print > 0) {?>
							'\x0A'+
							'\x0A'+
							'\x0A'+
							'\x0A'+
							'\x0A'+
							'\x0A'+
							'\x0A'+
							'\x0A'+
							'\x0A'+
						<?};
				   	?>
					
				<?}?>
			   	
				'\x1B' + '\x69',          // cut paper
			   	'\x10' + '\x14' + '\x01' + '\x00' + '\x05',  // Generate Pulse to kick-out cash drawer**
			                                                // **for legacy drawer cable CD-005A.  Research before using.
			                                                // see also http://keyhut.com/popopen4.htm
		    ];

		<?}else{?>
			var data = ['\x1B' + '\x40'+          // init
			   	'\x1B' + '\x21' + '\x39'+ // em mode on
			   	//1
			   	<?="'".sprintf('%-20.18s','SURAT JALAN')."'";?>+
			   	'\x1B' + '\x21' + '\x15'+
			   	'\x09'+
			   	'\x1B' + '\x21' + '\x18'+ // em mode on
			   	<?="'".sprintf('%44.27s', 'BANDUNG, '.$tanggal_print)."'";?> + '\x0A'+

			   	//2
			   	'\x1B' + '\x21' + '\x18'+ // em mode on
			   	<?="'".sprintf('%-35.35s', strtoupper($nama_toko) )."'";?>+
			   	'\x1B' + '\x21' + '\x15'+
			   	'\x09'+
			   	'\x1B' + '\x21' + '\x18'+ // em mode on
			   	<?="'".sprintf('%42.40s', 'Kepada Yth,')."'";?> + '\x0A'+

			   	//3
			   	'\x1B' + '\x21' + '\x18'+ // em mode on
			   	<?="'".sprintf('%-35.35s', strtoupper($alamat_toko))."'";?>+
			   	'\x1B' + '\x21' + '\x15'+
			   	'\x09'+
			   	'\x1B' + '\x21' + '\x18'+ // em mode on
			   	<?='"'.sprintf('%42.42s', strtoupper($nama_keterangan) ).'"';?> + '\x0A'+

			   	//4
			   	'\x1B' + '\x21' + '\x18'+ // em mode on
			   	<?="'".sprintf('%-31.31s', 'TELP:'.$telepon)."'";?>+
			   	'\x1B' + '\x21' + '\x15'+ //spacing
			   	'\x09'+
			   	'\x1B' + '\x21' + '\x18'+ // em mode on
			   	<?="'".sprintf('%47.47s', $alamat1)."'";?> + '\x0A'+

			   	//5
			   	'\x1B' + '\x21' + '\x18'+ // em mode on
			   	<?="'".sprintf('%-35.35s', ($fax != '' ? 'FAX:'.$fax : ''))."'";?>+
			   	'\x1B' + '\x21' + '\x15'+ //spacing
			   	'\x09'+
			   	'\x1B' + '\x21' + '\x18'+ // em mode on
			   	<?="'".sprintf('%42.42s',$alamat2 )."'";?> + '\x0A'+

			   	//6
		   		'\x1B' + '\x21' + '\x18'+ // em mode on
			   	<?="'".sprintf('%-30.30s', 'NPWP : '.$npwp)."'";?>+
			   	'\x1B' + '\x21' + '\x15'+ //spacing
			   	'\x09'+
			   	'\x1B' + '\x21' + '\x18'+ // em mode on
			   	<?="'".sprintf('%47.47s', strtoupper($kota))."'";?> + '\x0A'+


			   	//7
			   	'\x1B' + '\x21' + '\x18'+ // em mode on
			   	<?="'".sprintf("%${'garis1'}80s", '')."'";?>+ '\x0A'+
			   	//=============================no invoice==========================================================

			   	//8
			   	'\x1B' + '\x21' + '\x18'+ // em mode on
			   	<?="'".sprintf('%-30.30s', ($po_number != '' ? $po_number : '') )."'";?>+'\x09'+
			   	<?="'".sprintf('%47.47s', 'SURAT JALAN : '.$no_surat_jalan)."'";?> + '\x0A'+
			   	//9
			   	<?="'".sprintf("%${'garis1'}80s", '')."'";?>+ '\x0A'+

			   	//=============================invoice end==========================================================
			   	//10
			   	'\x1B' + '\x21' + '\x18'+ // em mode on
			   	<?="'".sprintf('%-6.6s', 'JUMLAH ')."'";?>+'\x09'+
			   	<?="'".sprintf('%-6.6s', 'SATUAN ')."'";?>+'\x09'+
			   	<?="'".sprintf('%-4.4s', 'ROLL ')."'";?>+'\x09'+
			   	<?="'".sprintf('%-10.10s', 'NAMA ')."'";?>+'\x09'+
			   	<?="'".sprintf('%-27.27s', 'KODE ')."'";?>+'\x09'+
			   	<?="'".sprintf('%-8.8s', 'HARGA ')."'";?>+'\x0A'+
			   	'\x1B' + '\x21' + '\x18'+ // em mode on
			   	<?="'".sprintf("%${'garis1'}80s", '')."'";?>+ '\x0A'+
				

			   	//==============================================================================
			   	<?
			   	$total = 0; $total_roll = 0; $idx = 0;
			   	foreach ($penjualan_print as $row) {
			   		$total += $row->qty * 0;
			   		$total_roll += $row->jumlah_roll;?>
			   			'\x1B' + '\x21' + '\x18'+ // em mode on
					   	<?="'".sprintf('%6.6s', is_qty_general($row->qty))."'";?>+'\x09'+
					   	<?="'".sprintf('%6.6s', $row->nama_satuan)."'";?>+'\x09'+
					   	<?="'".sprintf('%4.4s', ($row->tipe_qty != 3 ? $row->jumlah_roll : '') )."'";?>+'\x09'+
					   	<?="'".sprintf('%-12.12s', strtoupper($row->jenis_barang))."'";?>+
					   	<?="'".sprintf('%-27.27s', strtoupper($row->nama_barang))."'";?>+'\x09'+
					   	<?="'".sprintf('%-8.8s', number_format($row->harga_jual,'0',',','.'))."'";?>+'\x0A'+
					   	
			   	<?$idx++;}?>
			   	<?for ($i = $idx; $i < 2; $i++) {?>
			   		'\x0A'+
			   	<?};?>
			   	//14
			   	<?="'".sprintf("%${'garis1'}80s", '')."'";?>+ '\x0A'+
			   	<?="'".sprintf('%10.10s', 'TOTAL ROLL')."'";?>+
			   	<?="'".sprintf('%4.4s', ' ')."'";?>+
			   	<?="'".sprintf('%4.4s', $total_roll)."'";?>+
			   	//16
			   	'\x0A'+
			   	//17 ->
			   	'\x0A'+
			   	
			   	//================================faktur end==============================================

			   	
			   	//==================================detail start============================================
			   	//->17
			   	'\x1B' + '\x21' + '\x10'+ // em mode on
			   	<?="'".sprintf("%${'garis1'}80s", '')."'";?>+ '\x0A'+
			   	
			   	//18
			   	'\x1B' + '\x21' + '\x14'+ // em mode on
			   	<?="'".sprintf('%-15.15s', 'Kode ')."'";?>+ '\x09'+
			   	<?="'".sprintf('%-15.15s', 'Warna ')."'";?>+ '\x09'+
			   	<?="'".sprintf('%-2.2s', '|')."'";?>+
			   	<?="'".sprintf('%-4.4s', 'Roll ')."'";?>+ '\x09'+
			   	<?="'".sprintf('%-9.9s', 'Total ')."'";?>+ '\x09'+
			   	<?="'".sprintf('%-1.1s', '|')."'";?>+
			   	<?="'".sprintf('%-1.1s %-40.40s','', 'Detail ')."'";?>+
				'\x0A'+
				//19
			   	'\x1B' + '\x21' + '\x10'+ // em mode on
			   	<?="'".sprintf("%${'garis1'}80s", '')."'";?>+ 
			   	//20->
			   	'\x0A'+
				
			   	//===============================end tbl header===============================================
			   	//->20 - 22
			   	<?

				$idx = 0; $baris_print = 3;
				foreach ($data_penjualan_detail_group as $row) {

					$nama_warna = explode('??', $row->nama_warna);
					$data_qty = explode('??', $row->data_qty);
					$qty = explode('??', $row->qty);
					$jumlah_roll = explode('??', $row->jumlah_roll);
					$roll_qty = explode('??', $row->roll_qty);
					
					$data_all = explode('=??=', $row->data_all);


					foreach ($nama_warna as $key => $value) {

						$total = 0;
						$total_roll = 0;
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

						//sorting detail yard
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
						}			
					$idx++;}

					?>
					'\x1B' + '\x21' + '\x10'+ // em mode on
				   	<?="'".sprintf("%${'garis1'}80s", '')."'";$idx++?>+ '\x0A'+
				<?}?>

				//in case belum sampe baris 22
				<?for ($i = $idx; $i < 2; $i++) {?>
			   		'\x0A'+
			   	<?};?>

			   	//24-28
			   	<?for ($i = 0; $i < 4; $i++) {?>
			   		'\x0A'+
			   	<?};?>
				
				<?
					echo "'".sprintf('%-2.0s %-12.12s %-2.2s %-4.4s %-12.12s %-5.5s %-15.15s ', '','Tanda Terima', '', '', 'Checker','','Hormat Kami')."'";
				?>+
				'\x1B' + '\x69',          // cut paper
			   	'\x10' + '\x14' + '\x01' + '\x00' + '\x05',  // Generate Pulse to kick-out cash drawer**
			                                                // **for legacy drawer cable CD-005A.  Research before using.
			                                                // see also http://keyhut.com/popopen4.htm
		    ];
		<?};
	?>

	console.log('<?=$baris_idx?>');
	console.log(data);

	webprint.printRaw(data, printer_name);
	
}

</script>