<?
	$alamat_kirim_print = '';
	
	foreach ($data_penjualan as $row) {
		$nama_customer = $row->nama_keterangan;
		$no_faktur = $row->no_faktur;
		$no_faktur_lengkap = $row->no_faktur_lengkap;
		$tanggal = $tFungsi(date('d F Y', strtotime($row->tanggal)));
		// $tanggal = date('d F Y', strtotime($row->tanggal));
		$kota = $row->kota;
		$alamat = trim($row->alamat_keterangan);
		$penjualan_type_id = $row->penjualan_type_id;
		$no_sj_lengkap = $row->no_surat_jalan;
		$po_number = $row->po_number;
	}
	
	foreach ($alamat_kirim as $row) {
		$alamat_kirim_print .= $row->alamat.' - '.$row->catatan;
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
	$pdf->setY(6.5);
	$pdf->Text( 3, 9.7, strtoupper($nama_toko));
	$pdf->Text( 75, 9.7, "SURAT JALAN");
	$pdf->Cell( 200, 4, "", 0, 1, 'R' );
	$pdf->Text(136.3,9.7, "BANDUNG, ".strtoupper($tanggal));
	// $pdf->Cell( 200, 4, "BANDUNG,". $tanggal, 0, 1, 'R' );
	// $pdf->setY(6.5);

	// $pdf->Line(3, 7, 203, 7);


	$pdf->SetFont( $font_name, '', 10 );
	//baris 2
	$pdf->Cell( 200, 4, strtoupper($alamat_toko), 0, 1, 'L' );
	$pdf->Text( 136.3, 13.4, "INVOICE:".$no_faktur_lengkap);
	$pdf->Text( 77, 13.4, $no_sj_lengkap);
	
	//baris 3
	$pdf->Cell( 200, 4, strtoupper($kota_toko).", INDONESIA, 40181" , 0, 1, 'L' );
	if ($po_number != '') {
		$pdf->Text( 136.4, 17.4, "NO PO : " );
		# code...
	}

	$pdf->Line(3, 19, 203, 19);
	$pdf->Ln(1);

	$pdf->SetFont( $font_name, '', 9 );

	$pdf->Cell( 101, 4, "KEPADA" , 0, 0, 'L' );
	$pdf->Text( 15, 22.2, ":");
	$pdf->Text( 17, 22.3, strtoupper($nama_customer));
	$pdf->Cell( 100, 4, "" , 0, 1, 'L' );
	$pdf->Text( 110, 22.3, "ALAMAT PENGIRIMAN | VIA:" , 0, 1, 'L' );

	$tempY = $pdf->getY();
	$pdf->Text(3,26.4, "ALAMAT");
	$pdf->Text(15,26.3, ":");

	$pdf->setXY(17,$tempY);
	$alamat_kirim_print = ($alamat_kirim_print == '' ? $alamat : $alamat_kirim_print);
	// $alamat = "GEDUNG WISMA BHAKTI MULYA, JL. SALEMBA RAYA BLOK:- NO:5 RT/RW:002/002 Kel.PASEBAN Kec.SENEN JAKARTA, DKI JAKARTA 00000";
	// $alamat_kirim_print = "GEDUNG WISMA BHAKTI MULYA, JL. SALEMBA RAYA BLOK:- NO:5 RT/RW:002/002 Kel.PASEBAN Kec.SENEN JAKARTA, DKI JAKARTA 00000";
	$pdf->Multicell(82,4, strtoupper($alamat));
	$pdf->setXY(110, $tempY); 
	$pdf->Multicell(90,4, strtoupper($alamat_kirim_print));

	// $pdf->Rect(3,$tempY,98,13);
	// $pdf->Rect(104,$tempY,99,13);

	$pdf->setY(35);