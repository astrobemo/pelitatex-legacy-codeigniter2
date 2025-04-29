<?

	foreach ($data_penjualan as $row) {
		$nama_customer = $row->nama_keterangan;
		$no_faktur = $row->no_faktur;
		$no_faktur_lengkap = $row->no_faktur_lengkap;
		$tanggal = date('d F Y', strtotime($row->tanggal));
		$kota = $row->kota;
		$alamat = $row->alamat_keterangan;
		$bayar_dp = $row->bayar_dp;
		$penjualan_type_id = $row->penjualan_type_id;
		$po_number = $row->po_number;
	}


	$pdf = new FPDF( 'L', 'mm', array(215 ,139 ) );
	$pdf->cMargin = 0;
	$pdf->AddPage();
	$pdf->SetMargins(3,0,3);
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

	$pdf->SetFont( "ARIAL", 'BU', 12 );

	$pdf->Ln(-5);
	// $pdf->Cell( 10 );
	// baris 1
	$pdf->Text( 3, 8.1, strtoupper($nama_toko));
	$pdf->Text( 78, 8.1, "PACKING LIST");
	$pdf->Cell( 200, 3.8, "BANDUNG,02 SEPTEMBER 2020", 0, 1, 'R' );

	$pdf->SetFont( $font_name, '', 11 );
	//baris 2
	$pdf->Cell( 200, 4, "Kepada Yth,", 0, 1, 'R' );
	$pdf->Text( 78, 13, "NO:PL020920-02550");
	
	//baris 3
	$pdf->Cell( 200, 4, "CV.ANDRE & SHINTA PRODUCTION (AS PRODUCTION)" , 0, 1, 'R' );
	$pdf->Text( 78, 17, "SJ:SJ020920-02550");

	$pdf->Line(3, 19, 203, 19);
	$pdf->Ln();

	$pdf->SetFont( $font_name, '', 8.5 );
	//baris 6
	$pdf->Cell( 4, 6, 'N', "TLB", 0, 'C' );
	$pdf->Cell( 39, 6, 'Kode', "TLB", 0, 'C' );
	$pdf->Cell( 26, 6, 'Warna', "TLB", 0, 'C' );
	$pdf->Cell( 8, 6, 'Sat', "TLB", 0, 'C' );
	$pdf->Cell( 8, 6, 'Roll', "TLB", 0, 'C' );
	$pdf->Cell( 14, 6, 'Total', "TLB", 0, 'C' );
	$pdf->Cell( 101, 6, 'Detail', 1, 1, 'C' );
	// $pdf->Cell( 0, 0, '', 1, 1, 'R' );


	for ($h=0; $h < 2; $h++) { 
		$pdf->SetFont( $font_name, '', 8.5 );
		if ($h==0) {
			$pdf->Cell( 4, 5, 1, "LT", 0, 'C' );
			$pdf->Cell( 1,5,'','LT');
			$pdf->Cell( 38, 5, "Nylon taslan high wp 2000mm", "T", 0, 'L' );
			$pdf->Cell( 1,5,'','LT');
			$pdf->Cell( 25, 5, "Loreng digital green", "T", 0, 'L' );
			$pdf->Cell( 8, 5, 'YARD', "LT", 0, 'C' );
			$pdf->Cell( 8, 5, 20, "LT", 0, 'C' );
			$pdf->Cell( 13, 5, "2,000", "LT", 0, 'R' );
			$pdf->Cell( 1, 5, "", "RT", 0, 'LT' );
		}else{
			$pdf->Cell( 4, 5, "", "L", 0, 'C' );
			$pdf->Cell( 1,5,'','L');
			$pdf->Cell( 38, 5, "", 0, 0, 'L' );
			$pdf->Cell( 1,5,'','L');
			$pdf->Cell( 25, 5, "", 0, 0, 'L' );
			$pdf->Cell( 8, 5, '', "L", 0, 'C' );
			$pdf->Cell( 8, 5, "", "L", 0, 'C' );
			$pdf->Cell( 13, 5, "", "L", 0, 'R' );
			$pdf->Cell( 1, 5, "", "R", 0, 'L' );
		}

		$pdf->SetFont( $font_name, '', 9 );
		for ($i=0; $i <10 ; $i++) { 
			$border = ($h==0?"T" :0);
			$pdf->Cell( 10, 5, 100, $border, 0, 'R' );
			if ($i%9==0 && $i!=0) {
				$border = ($h==0?"RT" :"R");
				$pdf->Cell( 1, 5, "", $border, 1, 'R' );
			}
		}
	}

	for ($h=0; $h < 2; $h++) { 
		$pdf->SetFont( $font_name, '', 8.5 );
		if ($h==0) {
			$pdf->Cell( 4, 5, 2, "LT", 0, 'C' );
			$pdf->Cell( 1,5,'','LT');
			$pdf->Cell( 38, 5, "Nylon taslan high wp 2000mm", "T", 0, 'L' );
			$pdf->Cell( 1,5,'','LT');
			$pdf->Cell( 25, 5, "Loreng digital green", "T", 0, 'L' );
			$pdf->Cell( 8, 5, 'YARD', "LT", 0, 'C' );
			$pdf->Cell( 8, 5, 20, "LT", 0, 'C' );
			$pdf->Cell( 13, 5, "2,000", "LT", 0, 'R' );
			$pdf->Cell( 1, 5, "", "RT", 0, 'LT' );
		}else{
			$pdf->Cell( 4, 5, "", "L", 0, 'C' );
			$pdf->Cell( 1,5,'','L');
			$pdf->Cell( 38, 5, "", 0, 0, 'L' );
			$pdf->Cell( 1,5,'','L');
			$pdf->Cell( 25, 5, "", 0, 0, 'L' );
			$pdf->Cell( 8, 5, '', "L", 0, 'C' );
			$pdf->Cell( 8, 5, "", "L", 0, 'C' );
			$pdf->Cell( 13, 5, "", "L", 0, 'R' );
			$pdf->Cell( 1, 5, "", "R", 0, 'L' );
		}

		$pdf->SetFont( $font_name, '', 9 );
		for ($i=0; $i <10 ; $i++) { 
			$border = ($h==0?"T" :0);
			$pdf->Cell( 10, 5, 100, $border, 0, 'R' );
			if ($i%9==0 && $i!=0) {
				$border = ($h==0?"RT" :"R");
				$pdf->Cell( 1, 5, "", $border, 1, 'R' );
			}
		}
	}

	//====================================testing koma============================
	$data_qty = [
		23.33,24.43,25.03,25.72,27.72,
		29.29,29.99,30.22,30.62,30.99,
		31.11,31.72,40.01,45.32,52.72,
		35.16,37.06,39.11,45.32,52.72
	];

	for ($h=0; $h < 1; $h++) {
		$pdf->SetFont( $font_name, '', 8.5 );
		if ($h==0) {
			$pdf->Cell( 4, 5, 3, "LT", 0, 'C' );
			$pdf->Cell( 1,5,'','LT');
			$pdf->Cell( 38, 5, "JALA", "T", 0, 'L' );
			$pdf->Cell( 1,5,'','LT');
			$pdf->Cell( 25, 5, "NEON VOLT", "T", 0, 'L' );
			$pdf->Cell( 8, 5, 'KG', "LT", 0, 'C' );
			$pdf->Cell( 8, 5, 10, "LT", 0, 'C' );
			$pdf->Cell( 13, 5, 312.73, "LT", 0, 'R' );
			$pdf->Cell( 1, 5, "", "RT", 0, 'L' );
		}else{
			$pdf->Cell( 4, 5, "", "L", 0, 'C' );
			$pdf->Cell( 1,5,'','L');
			$pdf->Cell( 38, 5, "", 0, 0, 'L' );
			$pdf->Cell( 1,5,'','L');
			$pdf->Cell( 25, 5, "", 0, 0, 'L' );
			$pdf->Cell( 8, 5, '', "L", 0, 'C' );
			$pdf->Cell( 8, 5, '', "L", 0, 'C' );
			$pdf->Cell( 13, 5, "", "L", 0, 'R' );
			$pdf->Cell( 1, 5, "", "R", 0, 'LT' );
		}

		$pdf->SetFont( $font_name, '', 9 );
		for ($i=0; $i <10 ; $i++) {
			$border = ($h==0?"T" :0);
			$pdf->Cell( 10, 5, $data_qty[($h*10) + $i], $border, 0, 'R' );
			if ($i%9==0 && $i!=0) {
				$border = ($h==0?"RT" :"R");
				$pdf->Cell( 1, 5, "", $border, 1, 'R' );
			}
		}
	}

	for ($h=0; $h < 2; $h++) { 
		$pdf->SetFont( $font_name, '', 8.5 );
		if ($h==0) {
			$pdf->Cell( 4, 5, 4, "LT", 0, 'C' );
			$pdf->Cell( 1,5,'','LT');
			$pdf->Cell( 38, 5, "DIADORA", "T", 0, 'L' );
			$pdf->Cell( 1,5,'','LT');
			$pdf->Cell( 25, 5, "HIJAU", "T", 0, 'L' );
			$pdf->Cell( 8, 5, 'KG', "LT", 0, 'C' );
			$pdf->Cell( 8, 5, 20, "LT", 0, 'C' );
			$pdf->Cell( 13, 5, 868.83, "LT", 0, 'R' );
			$pdf->Cell( 1, 5, "", "RT", 0, 'L' );
		}else{
			$pdf->Cell( 4, 5, "", "L", 0, 'C' );
			$pdf->Cell( 1,5,'','L');
			$pdf->Cell( 38, 5, "", 0, 0, 'L' );
			$pdf->Cell( 1,5,'','L');
			$pdf->Cell( 25, 5, "", 0, 0, 'L' );
			$pdf->Cell( 8, 5, '', "L", 0, 'C' );
			$pdf->Cell( 8, 5, '', "L", 0, 'C' );
			$pdf->Cell( 13, 5, "", "L", 0, 'R' );
			$pdf->Cell( 1, 5, "", "R", 0, 'LT' );
		}

		$pdf->SetFont( $font_name, '', 9 );
		for ($i=0; $i < 10 ; $i++) {
			$border = ($h==0?"T" :0);
			$pdf->Cell( 10, 5, $data_qty[($h*10) + $i], $border, 0, 'R' );
			if ($i%9==0 && $i!=0) {
				$border = ($h==0?"RT" :"R");
				$pdf->Cell( 1, 5, "", $border, 1, 'R' );
			}
		}
	}

	//============================rekap=====================================
	$pdf->Cell( 43, 5, "", "LTB", 0, 'L' );
	$pdf->Cell( 26, 5, "TOTAL", "LTB", 0, 'R' );
	$pdf->Cell( 8, 5, 'YARD', "LTB", 0, 'C' );
	$pdf->Cell( 8, 5, 20, "LTB", 0, 'C' );
	$pdf->Cell( 13, 5, "4,000", "LTB", 0, 'R' );
	$pdf->Cell( 1, 5, "", "RTB", 0, 'LT' );
	$pdf->Cell( 101, 5, "", "RTB", 1 );

	$pdf->Cell( 43, 5, "", "LB", 0, 'L' );
	$pdf->Cell( 26, 5, "", "LB", 0, 'R' );
	$pdf->Cell( 8, 5, 'KG', "LB", 0, 'C' );
	$pdf->Cell( 8, 5, 30, "LB", 0, 'C' );
	$pdf->Cell( 13, 5, "1,379.53", "LB", 0, 'R' );
	$pdf->Cell( 1, 5, "", "RB", 0 );
	$pdf->Cell( 101, 5, "", "RB", 1 );
	

	// $pdf->Cell( 60, 5, 'Rp '.number_format(20250,'0',',','.'), "LR", 1, 'C' );

	// $pdf->Ln(20);
	$pdf->SetFont( $font_name, '', 11 );

	//===========================================================
	$pdf->Text( 2, 125, 'Tanda Terima');
	$pdf->Text( 130, 125, 'Checker');
	$pdf->Text( 170, 125, 'Hormat Kami');

	//=============================================================

	$pdf->Output( 'faktur_penjualan_'.$no_faktur_lengkap.'.pdf', "I" );
?>