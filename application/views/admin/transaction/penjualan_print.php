<?

	foreach ($data_penjualan as $row) {
		$nama_customer = $row->nama_customer;
		$no_faktur = $row->no_faktur;
		$no_faktur_lengkap = $row->no_faktur_lengkap;
		$tanggal = date('d F Y', strtotime($row->tanggal));
		$kota = $row->kota;
		$alamat = $row->alamat;
		$bayar_dp = $row->bayar_dp;
		$penjualan_type_id = $row->penjualan_type_id;
		$po_number = $row->po_number;
	}

	$pdf = new FPDF( 'L', 'mm', array(200 ,130 ) );
	$pdf->cMargin = 0;
	$pdf->AddPage();
	$pdf->SetMargins(2,0,3);
	$pdf->SetTextColor( 0,0,0 );

	// $pdf->AddFont('calibriL','','calibriL.php');
	// $pdf->AddFont('calibri','','calibri.php');
	// $pdf->AddFont('calibriLI','','calibriLI.php');

	// $font_name = 'calibriL';
	// $font_name_bold = 'calibri';
	// $font_name_italic = 'calibriLI';

	$font_name = 'Arial';
	$font_name_bold = 'Arial';
	$font_name_italic = 'Arial';

	$pdf->SetFont( $font_name, '', 12 );	

	$pdf->Ln(-9);
	// $pdf->Cell( 10 );
	$pdf->Cell( 2, 4, 'CV. PELITA ABADI ', 0, 1, 'L' );

	$pdf->Cell( 0, 4, 'TAMIM NO. 60, BANDUNG', 0, 0, 'L' );

	$pdf->Cell( 0, 4, '', 0, 1, 'R' );

	$pdf->Cell( 0, 4, 'TELP/FAX: (022)4238165 / (022)4218628', 0, 1, 'L' );
	$pdf->Cell( 0, 4, 'NPWP : 74.113.065.2-428.000', 0, 0, 'L' );

	$pdf->SetFont( $font_name_bold, '', 18 );
	$pdf->Cell( 0, 4, 'FAKTUR PENJUALAN', 0, 1, 'R' );
	$pdf->SetFont( $font_name, '', 12 );
	

	$pdf->Ln(2);
	$pdf->Cell( 0, 1, '', 'T', 1, 'L' );
	$pdf->Cell( 0, 4, 'KEPADA YTH,', 0, 0, 'L' );
	$pdf->Cell( 0, 4, "INVOICE NO : ".$no_faktur_lengkap, 0, 1, 'R' );	

	$pdf->Cell( 0, 4, strtoupper($nama_customer), 0, 0, 'L' );
	$pdf->Cell( 0, 4, 'BANDUNG, '.$tanggal, 0, 1, 'R' );
	$pdf->Cell( 0, 4, strtoupper($alamat), 0, 1, 'L' );

	$pdf->Ln();

	if ($penjualan_type_id != 3) {
		$pdf->Cell( 0, 6, 'PO : '.$po_number, 0, 1, 'L' );
	}

	$pdf->Cell( 15, 6, 'Jumlah', 1, 0, 'C' );
	$pdf->Cell( 15, 6, 'Satuan', 1, 0, 'C' );
	$pdf->Cell( 15, 6, 'Roll', 1, 0, 'C' );
	$pdf->Cell( 75, 6, 'Nama Barang', 1, 0, 'C' );
	$pdf->Cell( 35, 6, 'Harga', 1, 0, 'C' );
	$pdf->Cell( 40, 6, 'Total', 1, 1, 'C' );
	// $pdf->Cell( 0, 0, '', 1, 1, 'R' );


	$i = 1; $g_total = 0;
	foreach ($data_penjualan_detail as $row) {
		// $pdf->Cell( 0, 0, '', 1, 1, 'R' );
		$pdf->Cell( 15, 5, $row->qty, 1, 0, 'C' );
		$pdf->Cell( 15, 5, $row->nama_satuan, 1, 0, 'C' );
		$pdf->Cell( 15, 5, $row->jumlah_roll, 1, 0, 'C' );
		$pdf->Cell( 5,5,'','TLB');
		$pdf->Cell( 70, 5, $row->nama_barang.' '.$row->nama_warna, 'TRB', 0, 'L' );
		// $pdf->Cell( 10);
		$pdf->Cell( 35, 5, 'Rp'.number_format($row->harga_jual,'2',',','.'), 1, 0, 'C' );
		$pdf->Cell( 40, 5, 'Rp'.number_format($row->harga_jual*$row->qty,'2',',','.'), 1, 1, 'R' );
		$g_total += $row->harga_jual*$row->qty; 
		$i++;
		
	}

	$pdf->SetFont( $font_name_bold, '', 12 );
	$pdf->Cell( 0, 0, '', 1, 1, 'R' );
	$pdf->Cell( 120, 5, '', 0, 0, 'C' );
	if ($bayar_dp != 0) {
		$pdf->Cell( 35, 5, 'Subtotal', 1, 0, 'C' );
	}else{
		$pdf->Cell( 35, 5, 'Total', 1, 0, 'C' );
	}
	$pdf->Cell( 40, 5, 'Rp'.number_format($g_total,'2',',','.'), 1, 1, 'R' );

	if ($bayar_dp != 0) {
		$pdf->Cell( 120, 5, '', 0, 0, 'C' );
		$pdf->Cell( 35, 5, 'DP', 1, 0, 'C' );
		$pdf->Cell( 40, 5, 'Rp'.number_format($bayar_dp,'2',',','.'), 1, 1, 'R' );

		$pdf->Cell( 120, 5, '', 0, 0, 'C' );
		$pdf->Cell( 35, 5, 'Total', 1, 0, 'C' );
		$pdf->Cell( 40, 5, 'Rp'.number_format($g_total - $bayar_dp,'2',',','.'), 1, 1, 'R' );
	}		

	$pdf->Ln(20);
	$pdf->SetFont( $font_name, '', 12 );

	//===========================================================
	$pdf->Text( 2, 125, 'Tanda Terima');
	$pdf->Text( 130, 125, 'Checker');
	$pdf->Text( 160, 125, 'Hormat Kami');

	//=============================================================

	$pdf->Output( 'faktur_penjualan_'.$no_faktur_lengkap.'.pdf', "I" );
?>