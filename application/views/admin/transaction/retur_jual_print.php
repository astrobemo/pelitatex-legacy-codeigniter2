<?

	foreach ($data_retur as $row) {
		$nama_customer = $row->nama_customer;
		$no_faktur = $row->no_faktur;
		$no_faktur_lengkap = $row->no_faktur_lengkap;
		$tanggal = date('d F Y', strtotime($row->tanggal));
		$alamat = $row->alamat;
	}

	$pdf = new FPDF( 'L', 'mm', array(225 ,139 ) );
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
	
	
	$pdf->SetFont( $font_name, '', 11 );

	$pdf->Ln(-10);
	// $pdf->Cell( 10 );
	$pdf->Cell( 2, 4, 'CV. PELITA ABADI ', 0, 1, 'L' );

	$pdf->Cell( 0, 4, 'TAMIM NO. 53, BANDUNG', 0, 0, 'L' );

	$pdf->Cell( 0, 4, '', 0, 1, 'R' );

	$pdf->Cell( 50, 4, 'TELP/FAX: (022)4238165 / (022)4218628', 0, 1, 'L' );
	$pdf->Cell( 50, 4, 'NPWP : 74.113.065.2-428.000', 0, 0, 'L' );

	$pdf->SetFont( $font_name_bold, '', 18 );
	$pdf->Cell( 135, 4, 'RETUR PENJUALAN', 0, 1, 'R' );
	$pdf->SetFont( $font_name, '', 11 );
	

	$pdf->Ln(2);
	$pdf->Cell( 185, 1, '', 'T', 1, 'L' );
	$pdf->Cell( 165, 4, 'Kepada Yth,', 0, 0, 'L' );
	$pdf->Cell( 20, 4, "No Retur : ".$no_faktur_lengkap, 0, 1, 'R' );	

	$pdf->Cell( 165, 4, strtoupper($nama_customer), 0, 0, 'L' );
	$pdf->Cell( 20, 4, 'BANDUNG, '.$tanggal, 0, 1, 'R' );
	$pdf->Cell( 0, 4, strtoupper($alamat), 0, 1, 'L' );
	
	$pdf->Ln();

	$pdf->Cell( 20, 6, 'Jumlah', 1, 0, 'C' );
	$pdf->Cell( 15, 6, 'Satuan', 1, 0, 'C' );
	$pdf->Cell( 15, 6, 'Roll', 1, 0, 'C' );
	$pdf->Cell( 60, 6, 'Nama Barang', 1, 0, 'C' );
	$pdf->Cell( 35, 6, 'Harga', 1, 0, 'C' );
	$pdf->Cell( 40, 6, 'Total', 1, 1, 'C' );
	// $pdf->Cell( 0, 0, '', 1, 1, 'R' );


	$i = 1; $g_total = 0;$t_roll = 0;
	foreach ($data_retur_detail as $row) {
		// $pdf->Cell( 0, 0, '', 1, 1, 'R' );
		$pdf->Cell( 20, 5, number_format($row->qty,'0',',','.'), 1, 0, 'C' );
		$pdf->Cell( 15, 5, $row->nama_satuan, 1, 0, 'C' );
		$pdf->Cell( 15, 5, $row->jumlah_roll, 1, 0, 'C' );
		$t_roll += $row->jumlah_roll;
		$pdf->Cell( 2,5,'','TLB');
		$pdf->Cell( 58, 5, $row->nama_barang, 'TRB', 0, 'L' );
		// $pdf->Cell( 10);
		$pdf->Cell( 35, 5, 'Rp '.number_format($row->harga,'2',',','.'), 1, 0, 'C' );
		$pdf->Cell( 40, 5, 'Rp '.number_format($row->harga*$row->qty,'2',',','.'), 1, 1, 'R' );
		$g_total += $row->harga*$row->qty; 
		$i++;
		
	}

	$pdf->SetFont( $font_name_bold, '', 11 );
	// $pdf->Cell( 0, 0, '', 1, 1, 'R' );
	$pdf->Cell( 185, 0.5, '', 1, 1, 'C' );
	$pdf->Cell( 35, 5, 'TOTAL ROLL', 1, 0, 'C' );
	$pdf->Cell( 15, 5, $t_roll, 1, 0, 'C' );


	$pdf->Cell( 60, 5, '', 0, 0, 'C' );
	
	$pdf->Cell( 35, 5, 'Total', 1, 0, 'C' );
	
	$pdf->Cell( 40, 5, 'Rp'.number_format($g_total,'2',',','.'), 1, 1, 'R' );


	$pdf->Ln(20);
	$pdf->SetFont( $font_name, '', 11 );

	//===========================================================
	$pdf->Text( 2, 125, 'Tanda Terima');
	$pdf->Text( 130, 125, 'Checker');
	$pdf->Text( 160, 125, 'Hormat Kami');

	//=============================================================

	$pdf->Output( 'retur_penjualan_'.$no_faktur_lengkap.'.pdf', "I" );
?>