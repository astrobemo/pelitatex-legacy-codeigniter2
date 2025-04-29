<?

	
	foreach ($data_penjualan as $row) {
		$nama_customer = $row->nama_keterangan;
		$no_faktur = $row->no_faktur;
		$no_faktur_lengkap = $row->no_faktur_lengkap;
		// $tanggal = date('d F Y', strtotime($row->tanggal));
		$tanggal = $tFungsi(date('d F Y', strtotime($row->tanggal)));
		
		$kota = $row->kota;
		$alamat = $row->alamat_bon;
		// $alamat1 = $row->alamat_bon;
		// $alamat2 = $row->alamat_kelurahan;
		$bayar_dp = $row->bayar_dp;
		$penjualan_type_id = $row->penjualan_type_id;
		$po_number = $row->po_number;
		$no_packing_list = $row->no_packing_list;
		$no_surat_jalan = $row->no_surat_jalan;
	}


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
	$getY = $pdf->getY();

	$pdf->SetFont( "ARIAL", 'B', 12 );
	// $pdf->SetFont( "", 'U', 12 );
	// $pdf->SetFont( "", 'B', 12 );

	$pdf->Ln(-5);
	// $pdf->Cell( 10 );
	// baris 1
	// $pdf->setY(6.5);
	$pdf->Text( 73, 9.7, "PACKING LIST");
	$pdf->Text( 3, 9.7, strtoupper($nama_toko));
	// $pdf->Cell( 200, 3.8, "BANDUNG,".$tanggal, 0, 1, 'R' );
	$tanggal = '22 NOPEMBER 2022';
	$pdf->Text( 137, 9.7, "BANDUNG,".$tanggal);
	$pdf->setY(11);
	// $pdf->Line(3, 9, 43, 9);
	// $pdf->Line(75, 9, 93, 9);
	// $pdf->Line(135, 9, 203, 9);


	$pdf->SetFont( $font_name, '', 9 );
	// $pdf->Text( 74, 13.5, "NO  ");
	// $pdf->Text( 78.5, 13.5, ":");
	$pdf->Text( 77.7, 13.5, $no_packing_list);
	$pdf->Text( 134, 13.5, "KEPADA : ");
	// $pdf->Text( 134, 16.9, "CV.ANDRE & SHINTA PRODUCTION [AS PRODUCTION]");
	$pdf->Text( 134, 17.4, $nama_customer);
	
	//baris 2
	$pdf->Text( 67, 17.5, "SURAT JALAN  ");
	$pdf->Text( 85.5, 17.5, ":");
	$pdf->Text( 87, 17.5, $no_surat_jalan);
	
	
	
	$pdf->Line(3, 20, 203, 20);
	$pdf->setY(22);