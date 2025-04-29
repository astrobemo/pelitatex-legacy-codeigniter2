<?

	foreach ($data_dp as $row) {
		$nama_customer = $row->nama_customer;
		$no_faktur = $row->no_faktur;
		$no_faktur_lengkap = $row->no_faktur_lengkap;
		$tanggal = date('d F Y', strtotime($row->tanggal));
		$alamat = $row->alamat;
		$bayar_dp = $row->bayar_dp;
		$keterangan = $row->keterangan;
		$amount = $row->amount;
		$penyerah = '';
		$penerima = '';
	}

	$pdf = new FPDF( 'L', 'mm', array(200 ,130 ) );
	$pdf->cMargin = 0;
	$pdf->AddPage();
	$pdf->SetMargins(2,0,3);
	$pdf->SetTextColor( 0,0,0 );

	$font_name = 'Arial';
	
	$pdf->SetFont( $font_name, '', 11 );	
	$pdf->Ln(-9);
	// $pdf->Cell( 10 );
	$pdf->Cell( 2, 4, 'CV. PELITA ABADI ', 0, 1, 'L' );

	$pdf->Cell( 0, 4, 'TAMIM NO. 60, BANDUNG', 0, 0, 'L' );

	$pdf->Cell( 0, 4, '', 0, 1, 'R' );

	$pdf->Cell( 0, 4, 'TELP/FAX: (022)4238165 / (022)4218628', 0, 1, 'L' );
	$pdf->Cell( 0, 4, 'NPWP : 74.113.065.2-428.000', 0, 0, 'L' );

	$pdf->SetFont( $font_name, 'B', 18 );
	$pdf->Cell( 0, 4, 'UANG MUKA (DP)', 0, 1, 'R' );
	$pdf->SetFont( $font_name, '', 11 );
	
	

	$pdf->Ln(2);
	$pdf->Cell( 0, 1, '', 'T', 1, 'L' );
	// $pdf->Cell( 0, 4, 'Kepada Yth,', 0, 0, 'L' );
	$pdf->Cell( 0, 4, "NO DP : ".$no_faktur_lengkap, 0, 1, 'R' );	
	$pdf->Cell( 0, 4, 'BANDUNG, '.$tanggal, 0, 1, 'R' );

	$pdf->Ln();
	$pdf->Cell( 35, 5, ' Sudah diterima dari', 0, 0, 'L' );
	$pdf->Cell( 10, 5, ' : ', 0, 0, 'C' );
	$pdf->Cell( 0, 5, $nama_customer, 0, 1, 'L' );

	$pdf->Cell( 35, 5, ' Terbilang', 0, 0, 'L' );
	$pdf->Cell( 10, 5, ' : ', 0, 0, 'C' );
	$pdf->Cell( 0, 5, is_number_write($amount), 0, 1, 'L' );
	// $pdf->Cell( 0, 4, $alamat, 0, 1, 'L' );

	$pdf->Cell( 35, 5, ' Metode Pembayaran', 0, 0, 'L' );
	$pdf->Cell( 10, 5, ' : ', 0, 0, 'C' );
	$pdf->Cell( 0, 5, $row->bayar_dp, 0, 1, 'L' );

	$pdf->Cell(1);
	$pdf->Cell( 34, 5, 'Keterangan', 0, 0, 'L' );
	$pdf->Cell( 10, 5, ' : ', 0, 0, 'C' );
	$pdf->Cell( 0, 5, $row->keterangan, 0, 1, 'L' );

	$pdf->Ln(30);
	$pdf->SetFont( $font_name, '', 14 );
	$pdf->Cell( 80, 10, 'TOTAL : Rp. '.number_format($amount,'2',',','.'), 'TB', 1, 'L' );


	//===========================================================
	$pdf->Text( 10, 100, 'Yang Menyerahkan');
	$pdf->Text( 130, 100, 'Yang Menerima');

	$p_serah = 16 - strlen($penyerah);
	$p_terima = 15 - strlen($penerima);

	$pdf->Text( 10 + $p_serah, 130, $penyerah);
	$pdf->Text( 130 + $p_terima, 130, $penerima);
	//=============================================================

	$pdf->Output( 'faktur_penjualan_'.$no_faktur_lengkap.'.pdf', "I" );
?>