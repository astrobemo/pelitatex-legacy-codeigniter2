<?

	// $pdf->Cell( 60, 5, 'Rp '.number_format(20250,'0',',','.'), "LR", 1, 'C' );

	// $pdf->Ln(20);
	$pdf->SetFont( $font_name, '', 11 );

	//===========================================================
	// $pdf->Text( 2, 125, 'Tanda Terima');
	// $pdf->Text( 130, 125, 'Checker');
	// $pdf->Text( 170, 125, 'Hormat Kami');

	//=============================================================

	$pdf->Text( 6, 119, strtoupper('Tanda Terima'));
	$pdf->Text( 38, 119, strtoupper('Tanggal'));
	$pdf->Text( 82, 119, strtoupper('Checker'));
	$pdf->Text( 109, 119, strtoupper('Ekspedisi'));
	$pdf->Text( 168, 119, strtoupper('Hormat Kami') );

	// $pdf->Rect(3,115,55,20);
	// $pdf->Rect(75,115,55,20);
	// $pdf->Rect(155,115,48,20);

	// $pdf->Line(3,120,58,120);
	// $pdf->Line(75,120,130,120);
	// $pdf->Line(155,120,203,120);

	// $pdf->Line(31,115,31,135);
	// $pdf->Line(103,115,103,135);


?>