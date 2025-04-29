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

	// $pdf->Ln(-5);
	$pdf->Ln(-4);
	// $pdf->Cell( 10 );
	// baris 1
	// $pdf->Line(3, 9.2, 203, 9.2);

	$posYawal = $pdf->getY();
	$pdf->Text( 3, 9.2, strtoupper($nama_toko));
	$pdf->Text( 74, 9.2, "FAKTUR PENJUALAN");
	$pdf->Cell( 200, 3.8, "BANDUNG,02 SEPTEMBER 2020", 0, 1, 'R' );

	$pdf->SetFont( "ARIAL", 'B', 9 );
	//baris 2
	$pdf->Cell( 200, 4, "Kepada Yth,", 0, 1, 'R' );
	$pdf->Text( 3, 13, strtoupper($alamat_toko));
	$pdf->Text( 80, 13, "NO:FPJ020920-02550");
	
	//baris 3
	$pdf->Cell( 200, 4, "CV.ANDRE & SHINTA PRODUCTION (AS PRODUCTION)", 0, 1, 'R' );
	$pdf->Text( 3, 17, strtoupper($kota_toko).", INDONESIA, 40181");
	$pdf->Text( 80, 17, "PO:");

	//baris 4
	$pdf->Cell( 200, 4, "KOMP TAMAN KOPO INDAH III RUKO BLOK D,", 0, 1, 'R' );
	$pdf->Text( 3, 21, "TELP:".$phone);
	$pdf->Text( 80, 21, "SJ:SJ020920-02550");

	//baris 5
	$pdf->Cell( 200, 4, "NO: 57 RT/RW:000/000 KEL.RAHAYU,", 0, 1, 'R' );
	$pdf->Text( 3, 25, 'NPWP : '.$npwp);
	$pdf->Text( 85, 25, "SJ020920-02550");

	$pdf->Cell( 200, 4, "KEC.MARGAASIH KAB/KOTA. BANDUNG", 0, 1, 'R' );

	$pdf->Line(3, 30, 203, 30);
	$pdf->Ln();

	$pdf->SetFont( "ARIAL", 'B', 10 );
	//baris 6
	$pdf->Cell( 5, 6, 'NO', 1, 0, 'C' );
	$pdf->Cell( 15, 6, 'Jumlah', 1, 0, 'C' );
	$pdf->Cell( 15, 6, 'Satuan', 1, 0, 'C' );
	$pdf->Cell( 10, 6, 'Roll', 1, 0, 'C' );
	$pdf->Cell( 25, 6, 'Nama', 1, 0, 'C' );
	$pdf->Cell( 65, 6, 'Kode', 1, 0, 'C' );
	$pdf->Cell( 30, 6, 'Harga', 1, 0, 'C' );
	$pdf->Cell( 35, 6, 'Total', 1, 1, 'C' );
	// $pdf->Cell( 0, 0, '', 1, 1, 'R' );


	$pdf->Cell( 5, 5, 1, 1, 0, 'C' );
	$pdf->Cell( 15, 5, 200.00, 1, 0, 'C' );
	$pdf->Cell( 15, 5, 'yard', 1, 0, 'C' );
	$pdf->Cell( 10, 5, 2, 1, 0, 'C' );
	$pdf->Cell( 2,5,'','TLB');
	$pdf->Cell( 23, 5, "POLYESTER", 'TRB', 0, 'L' );
	$pdf->Cell( 2,5,'','TLB');
	$pdf->Cell( 63, 5, "COLOMBIA WP GRADE B", 'TRB', 0, 'L' );
	// $pdf->Cell( 10);
	$pdf->Cell( 30, 5, 'Rp '.number_format(20250,'0',',','.'), 1, 0, 'C' );
	$pdf->Cell( 35, 5, 'Rp '.number_format(4050000,'0',',','.'), 1, 1, 'R' );

	$pdf->Cell( 5, 5, 2, 1, 0, 'C' );
	$pdf->Cell( 15, 5, 300.00, 1, 0, 'C' );
	$pdf->Cell( 15, 5, 'yard', 1, 0, 'C' );
	$pdf->Cell( 10, 5, 3, 1, 0, 'C' );
	$pdf->Cell( 2,5,'','TLB');
	$pdf->Cell( 23, 5, "POLYESTER", 'TRB', 0, 'L' );
	$pdf->Cell( 2,5,'','TLB');
	$pdf->Cell( 63, 5, "HEAVY OXFORD WP GRADE B", 'TRB', 0, 'L' );
	// $pdf->Cell( 10);
	$pdf->Cell( 30, 5, 'Rp '.number_format(17000,'0',',','.'), 1, 0, 'C' );
	$pdf->Cell( 35, 5, 'Rp '.number_format(5100000,'0',',','.'), 1, 1, 'R' );

	//=====================REKAP

	$pdf->SetFont( "ARIAL", 'B', 10 );
	// $pdf->Cell( 0, 0, '', 1, 1, 'R' );
	$pdf->Cell( 5, 5, 'T', 1, 0, 'C' );
	$pdf->Cell( 15, 5, 500.00, 1, 0, 'C' );
	$pdf->Cell( 15, 5, 'YARD', 1, 0, 'C' );
	$pdf->Cell( 10, 5, 5, 1, 0, 'C' );
	
	$pdf->Cell( 90, 5, '', 0, 0, 'C' );
	$pdf->Cell( 30, 5, 'Total*', 0, 0, 'L' );
	$pdf->Cell( 35, 5, 'Rp '.number_format(9150000,'0',',','.'), 0, 1, 'R' );

	$pdf->Cell( 135, 5, '', 0, 0, 'C' );
	$pdf->Cell( 30, 5, "TRANSFER", 0, 0, 'L' );
	$pdf->Cell( 35, 5, 'Rp '.number_format(7000000,'0',',','.'), 0, 1, 'R' );

	$pdf->Cell( 135, 5, '', 0, 0, 'C' );
	$pdf->Cell( 30, 5, "EDC", 0, 0, 'L' );
	$pdf->Cell( 35, 5, 'Rp '.number_format(2150000,'0',',','.'), 0, 1, 'R' );
	
	$pdf->Cell( 135, 0.5, '', 0, 0, 'C' );
	$pdf->Cell( 30, 0.5, "", 1, 0, 'C' );
	$pdf->Cell( 35, 0.5, '' , 1, 1, 'R' );

	$pdf->Cell( 135, 5, '', 0, 0, 'C' );
	$pdf->Cell( 30, 5, "KEMBALI", 0, 0, 'L' );
	$pdf->Cell( 35, 5, 'Rp '.number_format(0,'0',',','.'), 0, 1, 'R' );

	$pdf->SetFont( "ARIAL", 'B', 8 );
	$pdf->Cell( 200, 4, '*harga sudah termasuk ppn', 0, 1, 'R' );

	// $pdf->Ln(20);
	$pdf->SetFont( "ARIAL", 'B', 11 );

	//===========================================================
	$pdf->Text( 2, 125, 'Tanda Terima');
	$pdf->Text( 130, 125, 'Checker');
	$pdf->Text( 170, 125, 'Hormat Kami');

	//=============================================================
	//==============================================================

	


	$pdf->Output( 'faktur_penjualan_'.$no_faktur_lengkap.'.pdf', "I" );
?>