<?
	$pdf->Cell( 0, 6, 'PO : ', 0, 1, 'L' );

	$pdf->Cell( 20, 6, 'Jumlah', 1, 0, 'C' );
	$pdf->Cell( 20, 6, 'Roll', 1, 0, 'C' );
	$pdf->Cell( 80, 6, 'Nama Barang', 1, 0, 'C' );
	$pdf->Cell( 35, 6, 'Harga', 1, 0, 'C' );
	$pdf->Cell( 40, 6, 'Total', 1, 1, 'C' );
	// $pdf->Cell( 0, 0, '', 1, 1, 'R' );


	$i = 1; $g_total = 0;
	foreach ($data_penjualan_detail as $row) {
		// $pdf->Cell( 0, 0, '', 1, 1, 'R' );
		$pdf->Cell( 20, 5, $row->qty, 1, 0, 'C' );
		$pdf->Cell( 20, 5, $row->jumlah_roll, 1, 0, 'C' );
		$pdf->Cell( 5,5,'','TLB');
		$pdf->Cell( 75, 5, $row->nama_barang.' '.$row->nama_warna, 'TRB', 0, 'L' );
		// $pdf->Cell( 10);
		$pdf->Cell( 35, 5, 'Rp'.number_format($row->harga_jual,'2',',','.'), 1, 0, 'C' );
		$pdf->Cell( 40, 5, 'Rp'.number_format($row->harga_jual*$row->qty,'2',',','.'), 1, 1, 'R' );
		$g_total += $row->harga_jual*$row->qty; 
		$i++;
		
	}

	$pdf->SetFont( $font_name, 'B', 12 );
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
?>