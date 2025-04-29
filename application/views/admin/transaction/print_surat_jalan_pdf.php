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
	$pdf->Text( 82, 8.1, "SURAT JALAN");
	$pdf->Cell( 200, 3.8, "BANDUNG,02 SEPTEMBER 2020", 0, 1, 'R' );

	$pdf->SetFont( $font_name, '', 11 );
	//baris 2
	$pdf->Cell( 200, 4, strtoupper($alamat_toko), 0, 1, 'L' );
	$pdf->Text( 137.5, 13, "NO.INVOICE:FPJ020920-02550");
	$pdf->Text( 82, 13, "NO:SJ020920-02550");
	
	//baris 3
	$pdf->Cell( 200, 4, strtoupper($kota_toko).", INDONESIA, 40181" , 0, 1, 'L' );
	$pdf->Text( 137.5, 17, "NO PO : " );

	$pdf->Line(3, 19, 203, 19);
	$pdf->Ln();

	$pdf->Cell( 101, 4, "CV.ANDRE & SHINTA PRODUCTION (AS PRODUCTION)" , 0, 0, 'L' );
	$pdf->Cell( 95, 4, "ALAMAT PENGIRIMAN | VIA:" , 0, 1, 'L' );

	$tempY = $pdf->getY();
	$pdf->setXY(4,$tempY);
	$pdf->Multicell(95,4, "GEDUNG KIRANA TWO, LANTAI 10-A, JL. BOULEVARD TIMUR - 88 JAKARTA UTARA RT:000, RW000 KELAPA GADING PEGANGSAAN DUA");
	$pdf->setXY(105, $tempY); 
	$pdf->Multicell(96,4, "JL. SMK PGRI I KAVLING BABAKAN BARU MARGA ENDAH BLOK.40C NO.C-3 CIMAHI, RT007 RW003, CIMAHI TENGAH CIMAHI");

	$pdf->Rect(3,$tempY,98,13);
	$pdf->Rect(104,$tempY,99,13);

	$pdf->Ln();
	$pdf->SetFont( $font_name, '', 10 );
	//baris 6
	$pdf->Cell( 10, 6, 'NO', "TLB", 0, 'C' );
	$pdf->Cell( 15, 6, 'Jumlah', "TLB", 0, 'C' );
	$pdf->Cell( 15, 6, 'Satuan', "TLB", 0, 'C' );
	$pdf->Cell( 10, 6, 'Roll', "TLB", 0, 'C' );
	$pdf->Cell( 25, 6, 'Nama', "TLB", 0, 'C' );
	$pdf->Cell( 65, 6, 'Kode', "TLB", 0, 'C' );
	$pdf->Cell( 60, 6, 'Harga', 1, 1, 'C' );
	// $pdf->Cell( 0, 0, '', 1, 1, 'R' );


	$pdf->Cell( 10, 5, 1, "L", 0, 'C' );
	$pdf->Cell( 15, 5, 200.00, "L", 0, 'C' );
	$pdf->Cell( 15, 5, 'yard', "L", 0, 'C' );
	$pdf->Cell( 10, 5, 2, "L", 0, 'C' );
	$pdf->Cell( 2,5,'','L');
	$pdf->Cell( 23, 5, "POLYESTER", 0, 0, 'L' );
	$pdf->Cell( 2,5,'','L');
	$pdf->Cell( 63, 5, "NYLON TASLAN HIGH WP 2000MM", 0, 0, 'L' );
	// $pdf->Cell( 10);
	$pdf->Cell( 60, 5, 'Rp '.number_format(20250,'0',',','.'), "LR", 1, 'C' );

	$pdf->Cell( 10, 5, 2, "L", 0, 'C' );
	$pdf->Cell( 15, 5, 300.00, "L", 0, 'C' );
	$pdf->Cell( 15, 5, 'yard', "L", 0, 'C' );
	$pdf->Cell( 10, 5, 3, "L", 0, 'C' );
	$pdf->Cell( 2,5,'','L');
	$pdf->Cell( 23, 5, "POLYESTER", 0, 0, 'L' );
	$pdf->Cell( 2,5,'','L');
	$pdf->Cell( 63, 5, "HEAVY OXFORD WP GRADE B", 0, 0, 'L' );
	// $pdf->Cell( 10);
	$pdf->Cell( 60, 5, 'Rp '.number_format(17000,'0',',','.'), "LR", 1, 'C' );
	

	//==============================blank row==================================

	for ($i=0; $i <5 ; $i++) { 
		$pdf->Cell( 10, 5, '', "L", 0, 'C' );
		$pdf->Cell( 15, 5, '', "L", 0, 'C' );
		$pdf->Cell( 15, 5, '', "L", 0, 'C' );
		$pdf->Cell( 10, 5, '', "L", 0, 'C' );
		$pdf->Cell( 2,5,'','L');
		$pdf->Cell( 23, 5, '', 0, 0, 'L' );
		$pdf->Cell( 2,5,'','L');
		$pdf->Cell( 63, 5, '', 0, 0, 'L' );
		// $pdf->Cell( 10);
		$pdf->Cell( 60, 5, '', "LR", 1, 'C' );
	}
	//=====================REKAP

	$pdf->SetFont( $font_name_bold, '', 10 );
	// $pdf->Cell( 0, 0, '', 1, 1, 'R' );
	$pdf->Cell( 10, 5, 'T', 1, 0, 'C' );
	$pdf->Cell( 15, 5, 500.00, 1, 0, 'C' );
	$pdf->Cell( 15, 5, 'YARD', 1, 0, 'C' );
	$pdf->Cell( 10, 5, 5, 1, 0, 'C' );
	$pdf->Cell( 150, 5, '', "T", 1, 'C' );
	

	// $pdf->Ln(20);
	$pdf->SetFont( $font_name, '', 11 );

	//===========================================================
	$pdf->Text( 2, 125, 'Tanda Terima');
	$pdf->Text( 130, 125, 'Checker');
	$pdf->Text( 170, 125, 'Hormat Kami');

	//=============================================================

	$pdf->Output( 'faktur_penjualan_'.$no_faktur_lengkap.'.pdf', "I" );
?>