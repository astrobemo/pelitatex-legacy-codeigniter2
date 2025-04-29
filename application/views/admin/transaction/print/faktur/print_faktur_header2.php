<?
	foreach ($data_penjualan as $row) {
		$nama_customer = $row->nama_keterangan;
		$no_faktur = $row->no_faktur;
		$no_faktur_lengkap = $row->no_faktur_lengkap;
		$tanggal = $tFungsi(date('d F Y', strtotime($row->tanggal)));
		$kota = $row->kota;
		$alamat = $row->alamat_bon;
		// $alamat1 = $row->alamat_bon;
		// $alamat2 = $row->alamat_kelurahan;
		$bayar_dp = $row->bayar_dp;
		$penjualan_type_id = $row->penjualan_type_id;
		$po_number = $row->po_number;
	}

	// $alamat1 = "JL. SMK PGRI I KAVLING BABAKAN BARU";
	// $alamat2 = "MARGA ENDAH no 40C-3, RT007 RW003";
	// $kota = "CIMAHI, CIMAHI TENGAH, ". $kota;



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

	$total = 0;

	$pdf->SetFont( "ARIAL", 'UB', 12 );

	$pdf->Ln(-5);
	// $pdf->Cell( 10 );
	// baris 1
	// $pdf->Text( 75, 8.1, "INVOICE");
	// $pdf->Text( 3, 8.1, strtoupper($nama_toko));
	// $pdf->Cell( 200, 3.8, "BANDUNG,".strtoupper($tanggal), 0, 1, 'R' );

	$pdf->setY(6.5);
	$pdf->Text( 75, 9.7, "INVOICE");
	$pdf->Text( 3, 9.7, strtoupper($nama_toko));
	// $pdf->Text( 150, 9.7, "BANDUNG,".$tanggal );
	$pdf->Cell( 200, 3.8, "BANDUNG,".strtoupper($tanggal), 0, 1, 'R' );

	$pdf->setY(11);
	// $pdf->Line(3, 9, 43, 9);
	// $pdf->Line(75, 9, 93, 9);
	// $pdf->Line(155, 9, 203, 9);


	$pdf->SetFont( $font_name, '', 9 );
	$pdf->Text( 3, 14, strtoupper($alamat_toko));
	$pdf->Text( 70, 14, "NO  ");
	$pdf->Text( 72.5, 14, ":");
	$pdf->Text( 75, 14, $no_faktur_lengkap);
	$pdf->Text( 122, 14, "KEPADA : ".$nama_customer);
	// $pdf->Text( 122, 13, "KEPADA : CV.ANDRE & SHINTA PRODUCTION [AS PRODUCTION]");
	//baris 2
	// $pdf->Cell( 121.4, 2, "KEPADA YTH,", 0, 1, 'R' );
	$pdf->Text( 121.4, 17.4, "ALAMAT : ".$alamat1);
	// $pdf->Text( 121.4, 17.4, "ALAMAT : GEDUNG MENARA SALEMBA LANTAI 7 UNIT 51A");
	$pdf->Text( 3, 17.4, strtoupper($kota_toko).", INDONESIA, 40181");
	$pdf->Text( 85, 17.4, $sj_pre[0]);
	if ($sj_pre[0] != '') {
		$pdf->Text( 90.5, 17.4, ":");
	}
	$pdf->Text( 92, 17.4, $sj_baris[0]);
	//baris 3
	// $pdf->Cell( 100, 4, $nama_customer, 0, 1, 'R' );
	$pdf->Text( 134.6, 21, trim($alamat2) );
	// $pdf->Text( 134.6, 21, "GEDUNG WISMA BHAKTI MULYA, JL. SALEMBA RAYA" );
	$pdf->Text( 3, 21, "TELP:".$phone);
	$pdf->Text( 85, 21, $sj_pre[1]);
	$pdf->Text( 92, 21, $sj_baris[1]);
	

	//baris 4
	// $pdf->SetFont( $font_name, '', $font_alamat );
	// $pdf->Cell( 200, 4, $alamat1, 0, 1, 'R' );

	$pdf->SetFont( $font_name, '', 9 );
	$pdf->Text( 134.6, 24.6, trim($alamat3));
	// $pdf->Text( 134.6, 24.6, "KEC.SENEN KAB/KOTA.JAKARTA, DKI JAKARTA 00000");
	$pdf->Text( 3, 24.6, 'NPWP : '.$npwp);
	$pdf->Text( 85, 24.6, $sj_pre[2]);
	$pdf->Text( 92, 24.6, $sj_baris[2]);
	

	//baris 5
	// $pdf->SetFont( $font_name, '', $font_alamat );
	// $pdf->Cell( 200, 4, $alamat2, 0, 1, 'R' );
	$pdf->SetFont( $font_name, '', 9 );
	$pdf->Text( 85, 29.9, $sj_pre[3]);
	$pdf->Text( 92, 29.9, $sj_baris[3]);


	// $pdf->SetFont( $font_name, '', $font_alamat );
	
	// $pdf->Cell( 200, 4, $alamat3, 0, 1, 'R' );

	$pdf->setY(30);
	// $pdf->Line(3, 30, 203, 30);

	