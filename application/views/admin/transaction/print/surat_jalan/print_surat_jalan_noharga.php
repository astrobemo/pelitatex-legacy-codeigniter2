<?

	$pdf->Ln();
	$pdf->SetFont( $font_name, '', 10 );
	//baris 6
	$pdf->Cell( 12, 6, 'NO', "TLB", 0, 'C' );
	$pdf->Cell( 18, 6, 'JUMLAH', "TLB", 0, 'C' );
	$pdf->Cell( 15, 6, 'SATUAN', "TLB", 0, 'C' );
	$pdf->Cell( 10, 6, 'ROLL', "TLB", 0, 'C' );
	// $pdf->Cell( 25, 6, 'Nama', "TLB", 0, 'C' );
	$pdf->Cell( 145, 6, 'KODE', 1, 1, 'C' );
	// $pdf->Cell( 50, 6, 'HARGA', 1, 1, 'C' );
	// $pdf->Cell( 0, 0, '', 1, 1, 'R' );
	$no = 1;
	unset($total_satuan);

	foreach ($data_penjualan_detail as $row) {
		# code...
		if (!isset($total_satuan['nama_satuan'])) {
			$total_satuan[$row->satuan_id]['qty'] = $row->qty;
			$total_satuan[$row->satuan_id]['roll'] = $row->jumlah_roll;
			$total_satuan[$row->satuan_id]['nama_satuan'] = $row->nama_satuan;
		}else{
			$total_satuan[$row->satuan_id]['qty'] += $row->qty;
			$total_satuan[$row->satuan_id]['roll'] = $row->jumlah_roll;
		}
		// $pdf->Cell( 10, 5, $no, $border, 0, 'C' );
		// $pdf->Cell( 18, 5, (float)$row->qty, $border, 0, 'C' );
		// $pdf->Cell( 15, 5, $row->nama_satuan, $border, 0, 'C' );
		// $pdf->Cell( 10, 5, $row->jumlah_roll, $border, 0, 'C' );
		// // $pdf->Cell( 2,5,'',0);
		// // $pdf->Cell( 23, 5, $row->jenis_barang, 0, 0, 'L' );
		// $pdf->Cell( 2,5,'',"LB");
		// // $pdf->Cell( 63, 5, $row->nama_barang, 0, 0, 'L' );
		// $pdf->Cell( 80, 5, $row->nama_barang, "B", 0, 'L' );
		// // $pdf->Cell( 10);
		// $pdf->Cell( 30, 5, 'Rp '.number_format($row->harga_jual,'0',',','.'), $border, 0, 'C' );
		// $pdf->Cell( 34, 5, 'Rp '.number_format($row->harga_jual*$row->qty,'0',',','.'), $border, 0, 'R' );
		// $pdf->Cell( 1, 5, '', $border_end, 1, 'R' );

		$pdf->Cell( 12, 5, $no, "L", 0, 'C' );
		$pdf->Cell( 18, 5, (float)$row->qty, "L", 0, 'C' );
		$pdf->Cell( 15, 5, $row->nama_satuan, "L", 0, 'C' );
		$pdf->Cell( 10, 5, $row->jumlah_roll, "L", 0, 'C' );

		$pdf->Cell( 2,5,'','L');
		$pdf->Cell( 143, 5, $row->nama_barang, "R", 1, 'C' );
		// $pdf->Cell( 10);
		// $pdf->Cell( 50, 5, 'Rp '.number_format($row->harga_jual,'0',',','.'), "LR", 1, 'C' );

		$no++;
	}


	

	
	//==============================blank row==================================

	for ($i=$no; $i <=5 ; $i++) { 
		$pdf->Cell( 12, 5, '', "L", 0, 'C' );
		$pdf->Cell( 18, 5, '', "L", 0, 'C' );
		$pdf->Cell( 15, 5, '', "L", 0, 'C' );
		$pdf->Cell( 10, 5, '', "L", 0, 'C' );
		// $pdf->Cell( 2,5,'','L');
		// $pdf->Cell( 23, 5, '', 0, 0, 'L' );
		$pdf->Cell( 2,5,'','L');
		$pdf->Cell( 143, 5, '', "R", 1, 'L' );
		// $pdf->Cell( 10);
		// $pdf->Cell( 50, 5, '', "LR", 1, 'C' );
	}
	//=====================REKAP

	$pdf->SetFont( $font_name_bold, '', 10 );
	// $pdf->Cell( 0, 0, '', 1, 1, 'R' );
	foreach ($total_satuan as $key => $value) {
		$pdf->SetFont( $font_name_bold, '', 10 );
		// $pdf->Cell( 0, 0, '', 1, 1, 'R' );
		$pdf->Cell( 12, 5, 'TOTAL', 1, 0, 'C' );
		$pdf->Cell( 18, 5, (float)$value['qty'], 1, 0, 'C' );
		$pdf->Cell( 15, 5, $value['nama_satuan'], 1, 0, 'C' );
		$pdf->Cell( 10, 5, $value['roll'], 1, 0, 'C' );
		$pdf->Cell( 145, 5, '', "T", 1, 'C' );
		
	}
	

	
?>