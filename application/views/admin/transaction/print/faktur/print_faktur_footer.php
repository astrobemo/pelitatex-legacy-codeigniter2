<?

	// $pdf->Ln(20);
	$pdf->SetFont( $font_name, '', 11 );

	//===========================================================
	$pdf->Text( 5, 125, 'TANDA TERIMA');
	$pdf->Text( 130, 125, 'CHECKER');
	$pdf->Text( 170, 125, 'HORMAT KAMI');

	$pdf->SetFont( $font_name, '', 9 );
	$xFooter = 195;
	$yFooter = 130;
	$pdf->Text($xFooter,$yFooter, $pdf->PageNo().' / {nb}');
	//=============================================================
	
?>