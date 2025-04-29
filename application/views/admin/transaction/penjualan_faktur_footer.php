<?
	$pdf->SetFont( $font_name, '', 11 );

	//===========================================================
	$pdf->Text( 2, 125, 'Tanda Terima');
	$pdf->Text( 130, 125, 'Checker');
	$pdf->Text( 160, 125, 'Hormat Kami');

	//=============================================================
	$pdf->Output( 'faktur_penjualan.pdf', "I" );

?>