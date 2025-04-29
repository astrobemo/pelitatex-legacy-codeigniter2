<?
	
	$pdf->SetFont( $font_name, '', 10 );
	$border = "TB";
	$border = "LTB";
	$border_end = "TRB";
	//baris 6
	$pdf->Cell( 10, 6, 'No', $border, 0, 'C' );
	$pdf->Cell( 18, 6, 'Jumlah', $border, 0, 'C' );
	$pdf->Cell( 15, 6, 'Satuan', $border, 0, 'C' );
	$pdf->Cell( 10, 6, 'Roll', $border, 0, 'C' );
	// $pdf->Cell( 2, 6, '', "TB", 0, 'L' );
	// $pdf->Cell( 23, 6, 'Nama', "TB", 0, 'L' );
	$pdf->Cell( 2, 6, '', "TLB", 0, 'L' );
	// $pdf->Cell( 63, 6, 'Kode', "TB", 0, 'L' );
	$pdf->Cell( 80, 6, 'Kode', "TB", 0, 'L' );
	$pdf->Cell( 30, 6, 'Harga', $border, 0, 'C' );
	$pdf->Cell( 34, 6, 'Total', $border, 0, 'C' );
	$pdf->Cell( 1, 6, '', $border_end, 1, 'C' );
	// $pdf->Cell( 0, 0, '', 1, 1, 'R' );

	$border = "LB";
	$border_end = "BR";

	$satuan_idx = 0;
	$no = 1;
	foreach ($data_penjualan_detail as $row) {
		# code...
		$total+=$row->harga_jual*$row->qty;
		if (!isset($total_satuan['nama_satuan'])) {
			$total_satuan[$row->satuan_id]['qty'] = $row->qty;
			$total_satuan[$row->satuan_id]['roll'] = $row->jumlah_roll;
			$total_satuan[$row->satuan_id]['nama_satuan'] = $row->nama_satuan;
		}else{
			$total_satuan[$row->satuan_id]['qty'] += $row->qty;
			$total_satuan[$row->satuan_id]['roll'] = $row->jumlah_roll;
		}
		$pdf->Cell( 10, 5, $no, $border, 0, 'C' );
		$pdf->Cell( 18, 5, (float)$row->qty, $border, 0, 'C' );
		$pdf->Cell( 15, 5, $row->nama_satuan, $border, 0, 'C' );
		$pdf->Cell( 10, 5, $row->jumlah_roll, $border, 0, 'C' );
		// $pdf->Cell( 2,5,'',0);
		// $pdf->Cell( 23, 5, $row->jenis_barang, 0, 0, 'L' );
		$pdf->Cell( 2,5,'',"LB");
		// $pdf->Cell( 63, 5, $row->nama_barang, 0, 0, 'L' );
		$pdf->Cell( 80, 5, $row->nama_barang, "B", 0, 'L' );
		// $pdf->Cell( 10);
		$pdf->Cell( 30, 5, 'Rp '.number_format($row->harga_jual,'0',',','.'), $border, 0, 'C' );
		$pdf->Cell( 34, 5, 'Rp '.number_format($row->harga_jual*$row->qty,'0',',','.'), $border, 0, 'R' );
		$pdf->Cell( 1, 5, '', $border_end, 1, 'R' );

		$no++;
	}

	//=====================REKAP========================


	$b_idx = 1;$total_bayar = 0;
	foreach ($data_pembayaran as $row) {
		if ($row->amount > 0) {
			$dt[$b_idx] = $row;
			$b_idx++;
		}

		$total_bayar+=$row->amount;
	}

	$b_idx = 0; $kembali_baris = false; 
	$posY = $pdf->getY();
	// $pdf->Line(3, ($posY+1), 203, ($posY+1) );
	$pdf->setY($posY+1);
	// $pdf->Ln();

	foreach ($total_satuan as $key => $value) {
		$pdf->SetFont( $font_name_bold, '', 9 );
		$pdf->Cell( 10, 5, 'TOTAL', "LTB", 0, 'C' );
		$pdf->SetFont( $font_name_bold, '', 10 );
		$pdf->Cell( 18, 5, (float)$value['qty'], "LTB", 0, 'C' );
		$pdf->Cell( 15, 5, $value['nama_satuan'], "LTB", 0, 'C' );
		$pdf->Cell( 10, 5, $value['roll'], 1, 0, 'C' );

		if ($b_idx == 0) {
			$pdf->Cell( 82, 5, '', 0, 0, 'C' );
			$pdf->Cell( 10, 5, '', "LTB", 0, 'C' );
			$pdf->Cell( 20, 5, 'Total*', "TB", 0, 'L' );
			$pdf->Cell( 34, 5, 'Rp '.number_format($total,'0',',','.'), "LTB", 0, 'R' );
			$pdf->Cell( 1, 5, '', "RTB", 1, 'R' );
		}else if (isset($dt[$b_idx])) {
			$pdf->Cell( 85, 5, '', 0, 0, 'L' );
			$pdf->Cell( 10, 5, '', "LB", 0, 'L' );
			$pdf->Cell( 20, 5, $dt[$b_idx]->nama_bayar, "B", 0, 'L' );
			$pdf->Cell( 34, 5, 'Rp '.number_format($dt[$b_idx]->amount,'0',',','.'), "LB", 0, 'R' );
			$pdf->Cell( 1, 5, '', "RB", 1, 'R' );
		}elseif (!isset($dt[$b_idx]) && $kembali_baris == false) {
			$kembali_baris = true;
			$pdf->Cell( 130, 5, '', 0, 0, 'L' );
			$pdf->Cell( 10, 5, '', "LB", 0, 'L' );
			$pdf->Cell( 20, 5, "KEMBALI", "B", 0, 'L' );
			$pdf->Cell( 34, 5, 'Rp '.number_format($total-$total_bayar,'0',',','.'), "LB", 0, 'R' );
			$pdf->Cell( 1, 5, '', "RB", 1, 'R' );
		}

		unset($dt[$b_idx]);

		$b_idx++;
	}

	if (count($dt) > 0) {
		foreach ($dt as $key => $value) {
			$pdf->Cell( 135, 5, '', 0, 0, 'L' );
			$pdf->Cell( 10, 5, '', "LB", 0, 'L' );
			$pdf->Cell( 20, 5, $dt[$key]->nama_bayar, "B", 0, 'L' );
			$pdf->Cell( 34, 5, 'Rp '.number_format($dt[$key]->amount,'0',',','.'), "LB", 0, 'R' );
			$pdf->Cell( 1, 5, '', "RB", 1, 'R' );
		}

		// $kembali_baris = true;
		// $pdf->Cell( 135, 5, '', 0, 0, 'L' );
		// $pdf->Cell( 10, 5, '', "LB", 0, 'L' );
		// $pdf->Cell( 20, 5, "KEMBALI", "B", 0, 'L' );
		// $pdf->Cell( 34, 5, 'Rp '.number_format($total_bayar-$total,'0',',','.'), "LB", 0, 'R' );
		// $pdf->Cell( 1, 5, '', "RB", 1, 'R' );
		
	}
	// $pdf->Cell( 0, 0, '', 1, 1, 'R' );
	

	$pdf->SetFont( $font_name, '', 8 );
	$pdf->Cell( 200, 4, '*harga sudah termasuk ppn', 0, 1, 'R' );


?>