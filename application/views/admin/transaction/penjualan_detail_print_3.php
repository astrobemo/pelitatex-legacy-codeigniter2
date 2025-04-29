<?
	$nama_customer = '';
	$tanggal = '';
	$no_faktur = '';
	$no_surat_jalan = '';
	$penjualan_type_id = '';
	$po_number = '';

	foreach ($data_penjualan as $row) {
		$nama_customer = $row->nama_keterangan;
		$no_faktur = $row->no_faktur;
		$no_faktur_lengkap = $row->no_faktur_lengkap;
		$tanggal = date('d F Y', strtotime($row->tanggal));
		$kota = $row->kota;
		$alamat = $row->alamat_keterangan;
		$bayar_dp = $row->bayar_dp;
		$no_surat_jalan = $row->no_surat_jalan;
		$penjualan_type_id = $row->penjualan_type_id;
		$po_number = $row->po_number;
	}

	$pdf = new FPDF( 'L', 'mm', array(210 ,139 ) );
	$pdf->cMargin = 0;
	$pdf->AddPage();
	$pdf->SetMargins(2,0,3);
	$pdf->SetTextColor( 0,0,0 );

	$pdf->AddFont('calibriL','','calibriL.php');
	$pdf->AddFont('calibri','','calibri.php');
	$pdf->AddFont('calibriLI','','calibriLI.php');

	$font_name = 'calibriL';
	$font_name_bold = 'calibri';
	$font_name_italic = 'calibriLI';

	// $font_name = 'Arial';
	// $font_name_bold = 'Arial';
	// $font_name_italic = 'Arial';

	foreach ($toko_data as $row) {
		$nama_toko = $row->nama;
		$alamat_toko = $row->alamat;
		$phone = $row->telepon;
		$fax = $row->fax;
		$npwp = $row->NPWP;
		$kota_toko = $row->kota;
	}

	$pdf->SetFont( $font_name, '', 12 );

	$pdf->Ln(-10);
	// $pdf->Cell( 10 );
	$pdf->Cell( 2, 4, strtoupper($nama_toko), 0, 1, 'L' );

	$pdf->Cell( 0, 4, strtoupper($alamat_toko).','.strtoupper($kota_toko), 0, 0, 'L' );

	$pdf->Cell( 0, 4, '', 0, 1, 'R' );

	$pdf->Cell( 50, 4, 'TELP/FAX: '.$phone.' / '.$fax, 0, 1, 'L' );
	$pdf->Cell( 50, 4, 'NPWP : '.$npwp, 0, 0, 'L' );

	$pdf->SetFont( $font_name_bold, '', 18 );
	$pdf->Cell( 150, 4, 'FAKTUR PENJUALAN', 0, 1, 'R' );
	$pdf->SetFont( $font_name, '', 12 );
	

	$pdf->Ln(2);
	$pdf->Cell( 200, 1, '', 'T', 1, 'L' );
	$pdf->Cell( 170, 4, 'KEPADA YTH,', 0, 0, 'L' );
	$pdf->Cell( 30, 4, "INVOICE NO : ".$no_faktur_lengkap, 0, 1, 'R' );	

	$pdf->Cell( 180, 4, strtoupper($nama_customer), 0, 0, 'L' );
	$pdf->Cell( 20, 4, 'BANDUNG, '.$tanggal, 0, 1, 'R' );
	$pdf->Cell( 0, 4, strtoupper($alamat), 0, 1, 'L' );
	$pdf->Cell( 0, 4, strtoupper($kota), 0, 1, 'L' );



	$pdf->Ln();

	if ($penjualan_type_id != 3) {
		$pdf->Cell( 0, 6, 'PO : '.$po_number, 0, 1, 'L' );
	}

	$pdf->SetFont( $font_name, '', 12 );

	$pdf->Cell( 37.5, 6, 'Spec', 1, 0, 'C' );
	$pdf->Cell( 37.5, 6, 'Warna', 1, 0, 'C' );
	$pdf->Cell( 91, 6, 'Detail', 1, 0, 'C' );
	$pdf->Cell( 12, 6, 'Roll', 1, 0, 'C' );
	$pdf->Cell( 20, 6, 'Total', 1, 1, 'C' );
	

	$i = 1; 
	foreach ($data_penjualan_detail_group as $row) {

		$nama_warna = explode('??', $row->nama_warna);
		$data_qty = explode('??', $row->data_qty);
		$qty = explode('??', $row->qty);
		$jumlah_roll = explode('??', $row->jumlah_roll);
		$roll_qty = explode('??', $row->roll_qty);
		
		$data_all = explode('=??=', $row->data_all);
		

		$total = 0;
		$total_roll = 0;

		foreach ($nama_warna as $key => $value) {

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

			// print_r($qty_detail);echo '<br/>';
			// print_r($roll_detail);echo '<hr/>';
			asort($qty_c);

			$jml_angka = count($qty_c);
			// print_r($qty_c);
			$qty_c = array_values($qty_c);
			$jml_angka;
			$baris = ceil($jml_angka/8);
			$total_baris = $baris;


			$kolom = 8 - ( count($qty_c) % 8) ;


			$pdf->SetFont( 'Arial', '', 12 );
			$idx = 1;
			$baris_idx = 1;
			$y = $pdf->GetY();
			$x = $pdf->GetX();
			foreach ($qty_c as $keyX => $valueX) {
				$pdf->SetFont( 'Arial', '', 12 );

				if ($baris_idx == 1 && $baris_idx != $baris) {
					$border_nama_baris1 = 'TL';
					// $border_awal_rinci_baris1 = 'TL';
				}elseif ($baris_idx == 1) {
					$border_nama_baris1 = "TLB";
					$border_awal_rinci_baris1 = 'TLB';
				}

				if ($keyX % 8 == 0 && $baris_idx == 1) {
					
					$tinggi = 5;
					if (strlen($row->nama_barang) > 18 && $baris != 1) {
						$y = $pdf->getY();
						// $x = $pdf->getX();
						$pdf->Multicell( 37.5, 5, $row->nama_barang, $border_nama_baris1 , 'TB', '' );
						$pdf->setY($y);
						$pdf->setX(39.5);
						// $pdf->Cell( 37.5, 5, , 0, 'C' );
						$pdf->Cell( 37.5, 5, $value, $border_nama_baris1, 0, 'C' );
						$pdf->Cell( 1, 5, '', $border_nama_baris1 , 0, 'L' );
					}elseif (strlen($row->nama_barang) > 18 && $baris == 1) {
						$y = $pdf->getY();
						// $x = $pdf->getX();
						$pdf->Multicell( 37.5, 5, $row->nama_barang, $border_nama_baris1 , 'TB', '' );
						$pdf->setY($y);
						$pdf->setX(39.5);

						$pdf->Cell( 37.5, 10, $value, $border_nama_baris1, 0, 'C' );
						$pdf->Cell( 1, 10, '', $border_nama_baris1 , 0, 'L' );
						$tinggi = 10;
					}else{
						$pdf->Cell( 37.5, 5, $row->nama_barang, 'LTB', 0, 'L' );
						$pdf->Cell( 37.5, 5, $value, $border_nama_baris1, 0, 'C' );
						$pdf->Cell( 1, 5, '', $border_nama_baris1 , 0, 'L' );
					}

					
				}elseif ($baris_idx == $baris && $keyX % 8 == 0) {
					$pdf->Cell( 37.5, 5, "" , "LB", 'C' );
					$pdf->Cell( 37.5, 5, "", "LB", 0, 'C' );
					$pdf->Cell( 1, 5, '', "LB", 0, 'L' );
					$border = '';
				} else if($keyX % 8 == 0){
					$pdf->Cell( 37.5, 5, "", "L", 0, 'C' );
					$pdf->Cell( 37.5, 5, "", "L", 0, 'C' );
					$pdf->Cell( 1, 5, '', 'L', 0, 'L' );
					$border = '';
				}

				if ($baris_idx == 1 && $baris_idx != $baris) {
					$border_batas = 'R';
					$border_qty_top = 'T';
				}elseif ($baris_idx == 1 && $baris_idx == $baris) {
					$border_batas = 'TR';
					$border_qty_top = 'T';
				}else{
					$border_batas = 'R';
					$border_qty_top = '';						
				}

				$border = '';
				if ($baris_idx == $baris) {
					$border = 'B';
				}

				$pdf->SetFont( 'Arial', '', 11 );

				$pdf->Cell(11,$tinggi,is_qty_general($valueX),$border_qty_top.$border,0,'R');

				//UNTUK FILL BLANK COLUMN
				if ($idx == count($qty_c) && count($qty_c) % 8 != 0 ) {
					for ($i=0; $i < $kolom ; $i++) { 
						$idx++;
						$pdf->Cell(11,$tinggi,'',$border_qty_top.'B',0,'R');
					}
				}else{
					
				}

				$pdf->SetFont( 'Arial', '', 12 );


				if( $idx == 8){
					$pdf->Cell( 2, $tinggi, '', $border_batas.$border_qty_top, 0, 'L' );
					$pdf->Cell( 12, $tinggi, $jumlah_roll[$key], $border_batas.$border_qty_top, 0, 'C' );
					$pdf->Cell( 20, $tinggi, str_replace('.00','',number_format($qty[$key],'2','.',',')), $border_batas.$border_qty_top, 1,'R');
					$baris_idx++; 
					
				}elseif($idx % 8 == 0 && $baris_idx == $baris){

					$pdf->Cell( 2, $tinggi, '', 'BR', 0, 'L' );
					$pdf->Cell( 12, $tinggi, '', 'BR', 0, 'C' );
					$pdf->Cell( 20, $tinggi, '', 'BR', 1,'R');
					$baris_idx++; 

				}elseif($idx % 8 == 0){
					$pdf->Cell( 2, $tinggi, '', 'R', 0, 'L' );
					$pdf->Cell( 12, $tinggi,'' , 'R', 0, 'C' );
					$pdf->Cell( 20, $tinggi, '', 'R', 1,'R');
					$baris_idx++; 
					
				}

				$idx ++;
			}
			// $pdf->Multicell( 80, 5, $qty_show, 'TB', 'L' );
			// $pdf->SetXY($x+75,$y);
			// $pdf->Cell( 4, 5*$baris, '', 'TRB', 0, 'R' );

			// $pdf->Cell( 10, 5*$baris, $jumlah_roll[$key], 1, 0, 'C' );
			// $pdf->Cell( 20, 5*$baris, number_format($qty[$key],'0',',','.'), 1, 1, 'R' );
			$total_roll +=  $jumlah_roll[$key];
			$total += $qty[$key]; 

			$pdf->SetFont( $font_name, '', 12 );
			//===========================================================
			$pdf->Text( 2, 125, 'Tanda Terima');
			$pdf->Text( 130, 125, 'Checker');
			$pdf->Text( 160, 125, 'Hormat Kami');

			//=============================================================

		
		}

		$pdf->SetFont( 'Arial', '', 12 );
		$pdf->Cell( 146, 5, '', 0, 0, 'C' );
		$pdf->Cell( 20, 5, 'TOTAL', 1, 0, 'C' );
		$pdf->Cell( 12, 5, $total_roll, 1, 0, 'C' );
		$pdf->Cell( 20, 5, str_replace('.00','',number_format($total,'2','.',',')), 1, 1, 'R' );
		$i++;

		$pdf->Ln(5);

	}

	

	$pdf->Output( 'Penjualan_Detail.pdf', "I" );
?>