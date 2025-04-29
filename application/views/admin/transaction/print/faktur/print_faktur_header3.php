<?
	foreach ($data_penjualan as $row) {
		$nama_customer = $row->nama_keterangan;
		$no_faktur = $row->no_faktur;
		$no_faktur_lengkap = $row->no_faktur_lengkap;
		$tanggal = date('d F Y', strtotime($row->tanggal));
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

	$pdf->SetFont( "ARIAL", 'B', 12 );

	$pdf->Ln(-5);
	// $pdf->Cell( 10 );
	// baris 1
	$pdf->Text( 3, 8.1, "FAKTUR PENJUALAN");
	$pdf->Cell( 200, 3.8, "BANDUNG,".$tanggal, 0, 1, 'R' );
	$pdf->setY(11);
	$pdf->Line(3, 10, 203, 10);


	$pdf->SetFont( $font_name, '', 11 );
	$pdf->Text( 3, 14.1, strtoupper($nama_toko));
	
	//baris 2
	$pdf->Cell( 200, 4, "KEPADA YTH,", 0, 1, 'R' );
	$pdf->Text( 3, 17.9, strtoupper($alamat_toko));
	// $pdf->Text( 85, 17.9, $sj_pre[0]);
	// $pdf->Text( 92, 17.9, $sj_baris[0]);
	//baris 3
	$pdf->Cell( 200, 4, $nama_customer, 0, 1, 'R' );
	$pdf->Text( 3, 21.9, strtoupper($kota_toko).", INDONESIA, 40181");
	// $pdf->Text( 85, 21.9, $sj_pre[1]);
	// $pdf->Text( 92, 21.9, $sj_baris[1]);
	

	//baris 4
	$pdf->SetFont( $font_name, '', $font_alamat );
	$pdf->Cell( 200, 4, $alamat1, 0, 1, 'R' );

	$pdf->SetFont( $font_name, '', 11 );
	$pdf->Text( 3, 26, "TELP:".$phone);
	// $pdf->Text( 85, 26, $sj_pre[2]);
	// $pdf->Text( 92, 26, $sj_baris[2]);
	

	//baris 5
	$pdf->SetFont( $font_name, '', $font_alamat );
	$pdf->Cell( 200, 4, $alamat2, 0, 1, 'R' );
	$pdf->SetFont( $font_name, '', 11 );
	$pdf->Text( 3, 29.9, 'NPWP : '.$npwp);
	// $pdf->Text( 85, 29.9, $sj_pre[3]);
	// $pdf->Text( 92, 29.9, $sj_baris[3]);


	$pdf->SetFont( $font_name, '', $font_alamat );
	$pdf->Cell( 200, 4, $alamat3, 0, 1, 'R' );

	$pdf->Line(3, 30.8, 203, 30.8);
	$pdf->Cell( 100, 4, "PO :",0,0,'L');
	$pdf->Cell( 100, 4, $no_faktur_lengkap,0,1,'R');
	//surat jalan
	$pdf->Cell( 200, 4, $sj_inline,0,1,'R');


	// $pdf->Line(3, 30, 203, 30);

	