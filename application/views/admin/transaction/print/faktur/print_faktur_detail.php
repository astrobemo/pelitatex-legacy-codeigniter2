<?

	include_once 'print_faktur_header.php';

	$satuan_idx = 0;
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
		$pdf->Cell( 5, 5, 1, 1, 0, 'C' );
		$pdf->Cell( 15, 5, (float)$row->qty, 1, 0, 'C' );
		$pdf->Cell( 15, 5, $row->nama_satuan, 1, 0, 'C' );
		$pdf->Cell( 10, 5, $row->jumlah_roll, 1, 0, 'C' );
		$pdf->Cell( 2,5,'','TLB');
		$pdf->Cell( 23, 5, $row->jenis_barang, 'TRB', 0, 'L' );
		$pdf->Cell( 2,5,'','TLB');
		$pdf->Cell( 63, 5, $row->nama_barang, 'TRB', 0, 'L' );
		// $pdf->Cell( 10);
		$pdf->Cell( 30, 5, 'Rp '.number_format($row->harga_jual,'0',',','.'), 1, 0, 'C' );
		$pdf->Cell( 35, 5, 'Rp '.number_format($row->harga_jual*$row->qty,'0',',','.'), 1, 1, 'R' );
	}

	//=====================REKAP

	$pdf->SetFont( $font_name_bold, '', 10 );

	$b_idx = 1;$total_bayar = 0;
	foreach ($data_pembayaran as $row) {
		if ($row->amount > 0) {
			$dt[$b_idx] = $row;
			$b_idx++;
		}

		$total_bayar+=$row->amount;
	}

	$b_idx = 0; $kembali_baris = false; 
	foreach ($total_satuan as $key => $value) {
		$pdf->Cell( 5, 5, 'T', 1, 0, 'C' );
		$pdf->Cell( 15, 5, (float)$value['qty'], 1, 0, 'C' );
		$pdf->Cell( 15, 5, $value['nama_satuan'], 1, 0, 'C' );
		$pdf->Cell( 10, 5, $value['roll'], 1, 0, 'C' );

		if ($b_idx == 0) {
			$pdf->Cell( 90, 5, '', 0, 0, 'C' );
			$pdf->Cell( 30, 5, 'Total*', 0, 0, 'L' );
			$pdf->Cell( 35, 5, 'Rp '.number_format($total,'0',',','.'), 0, 1, 'R' );
		}else if (isset($dt[$b_idx])) {
			$pdf->Cell( 90, 5, '', 0, 0, 'L' );
			$pdf->Cell( 30, 5, $dt[$b_idx]->nama_bayar, 0, 0, 'L' );
			$pdf->Cell( 35, 5, 'Rp '.number_format($dt[$b_idx]->amount,'0',',','.'), 0, 1, 'R' );
		}elseif (!isset($dt[$b_idx]) && $kembali_baris == false) {
			$kembali_baris = true;
			$pdf->Cell( 135, 5, '', 0, 0, 'L' );
			$pdf->Cell( 30, 5, "KEMBALI", 0, 0, 'L' );
			$pdf->Cell( 35, 5, 'Rp '.number_format($total_bayar-$total,'0',',','.'), "T", 1, 'R' );
		}

		unset($dt[$b_idx]);

		$b_idx++;
	}

	if (count($dt) > 0) {
		foreach ($dt as $key => $value) {
			$pdf->Cell( 135, 5, '', 0, 0, 'L' );
			$pdf->Cell( 30, 5, $dt[$b_idx]->nama_bayar, 0, 0, 'L' );
			$pdf->Cell( 35, 5, 'Rp '.number_format($dt[$b_idx]->amount,'0',',','.'), 0, 1, 'R' );
		}

		$kembali_baris = true;
		$pdf->Cell( 135, 5, '', 0, 0, 'L' );
		$pdf->Cell( 30, 5, "KEMBALI", 0, 0, 'L' );
		$pdf->Cell( 35, 5, 'Rp '.number_format($total_bayar-$total,'0',',','.'), "T", 1, 'R' );
		
	}
	// $pdf->Cell( 0, 0, '', 1, 1, 'R' );
	

	$pdf->SetFont( $font_name, '', 8 );
	$pdf->Cell( 200, 4, '*harga sudah termasuk ppn', 0, 1, 'R' );


	$pdf->SetFont( $font_name, '', 9 );
	//baris 6
	$pdf->Cell( 5, 6, 'No', "TLB", 0, 'C' );
	$pdf->Cell( 36, 6, 'Kode', "TLB", 0, 'C' );
	$pdf->Cell( 26, 6, 'Warna', "TLB", 0, 'C' );
	$pdf->Cell( 10, 6, 'Sat.', "TLB", 0, 'C' );
	$pdf->Cell( 7, 6, 'Roll', "TLB", 0, 'C' );
	$pdf->Cell( 15, 6, 'Total', "TLB", 0, 'C' );
	$pdf->Cell( 101, 6, 'Detail', 1, 1, 'C' );
	// $pdf->Cell( 0, 0, '', 1, 1, 'R' );

	$no = 0;
	foreach ($data_penjualan_detail_group as $row) {
		

		$nama_warna = explode('??', $row->nama_warna);
		$data_qty = explode('??', $row->data_qty);
		$qty = explode('??', $row->qty);
		$jumlah_roll = explode('??', $row->jumlah_roll);
		$roll_qty = explode('??', $row->roll_qty);
		
		$data_all = explode('=??=', $row->data_all);
		

		$total = 0;
		$total_roll = 0;

		foreach ($nama_warna as $key => $value) {
			$no++;
			$qty_list = [];
			foreach (explode("--", $data_all[$key]) as $idx => $isi) {
				$br = explode("??", $isi);
				for ($j=0; $j < ($br[1] == 0 ? 1 : $br[1]) ; $j++) { 
					array_push($qty_list, $br[0]);
				}
			}

			// print_r($qty_list);

			$baris = ceil(count($qty_list)/10);
			//"Nylon Taslan High WP 2000mm"
			//"Loreng Digital Green"
			for ($h=0; $h < $baris; $h++) { 
				if ($h==0) {

					if (strlen($row->nama_barang) > 20 || strlen($value) > 17) {
						$pdf->SetFont( $font_name, '', 7 );
					}else{
						$pdf->SetFont( $font_name, '', 8 );
					}
					$pdf->Cell( 5, 4, $no, "LT", 0, 'C' );
					$pdf->Cell( 1, 4,'','LT');
					$pdf->Cell( 35, 4, strtoupper($row->nama_barang), "T", 0, 'L' );
					$pdf->Cell( 1, 4,'','LT');
					$pdf->Cell( 25, 4, strtoupper($value), "T", 0, 'L' );
					$pdf->Cell( 10, 4, strtoupper($row->nama_satuan), "LT", 0, 'C' );
					$pdf->Cell( 7, 4, $jumlah_roll[$key], "LT", 0, 'C' );
					$pdf->Cell( 14, 4, str_replace(",00", "", number_format($qty[$key],'2',',','.') ) , "LT", 0, 'R' );
					$pdf->Cell( 1, 4, "", "RT", 0, 'LT' );
				}else{
					$pdf->Cell( 5, 4, "", "L", 0, 'C' );
					$pdf->Cell( 1, 4,'','L');
					$pdf->Cell( 35, 4, "", 0, 0, 'L' );
					$pdf->Cell( 1, 4,'','L');
					$pdf->Cell( 25, 4, "", 0, 0, 'L' );
					$pdf->Cell( 10, 4, '', "L", 0, 'C' );
					$pdf->Cell( 7, 4, "", "L", 0, 'C' );
					$pdf->Cell( 14, 4, "", "L", 0, 'R' );
					$pdf->Cell( 1, 4, "", "R", 0, 'L' );
				}

				$pdf->SetFont( $font_name, '', 8.5 );

				for ($i=0; $i < 10 ; $i++) { 
					$border = ($h==0?"T" :0);
					$idx = ($h*10)+$i;
					$pdf->Cell( 10, 4, (isset($qty_list[$idx]) ? (float)$qty_list[$idx] : ''), $border, 0, 'R' );
					if ($i%9==0 && $i!=0) {
						$border = ($h==0?"RT" :"R");
						$pdf->Cell( 1, 4, "", $border, 1, 'R' );
					}
				}
			}
		}


	}

	//============================rekap=====================================
	$t_idx = 0;
	foreach ($total_satuan as $key => $value) {
		$border = ($t_idx == 0 ? "LTB" : "LB"  );
		$pdf->Cell( 41, 5, "", $border, 0, 'L' );
		$pdf->Cell( 25, 5, "TOTAL", $border, 0, 'R' );

		$border = ($t_idx == 0 ? "TB" : "B"  );
		$pdf->Cell( 1, 5, "", $border, 0, 'R' );

		$border = ($t_idx == 0 ? "LTB" : "LB"  );
		$pdf->Cell( 10, 5, strtoupper($value['nama_satuan']), $border, 0, 'C' );
		$pdf->Cell( 7, 5, $value['roll'], $border, 0, 'C' );
		$pdf->Cell( 14, 5, str_replace(",00", "", number_format($value['qty'],'2',',','.' )), $border, 0, 'R' );
		
		$border = ($t_idx == 0 ? "RTB" : "RB"  );
		$pdf->Cell( 1, 5, "", $border, 0, 'LT' );
		$pdf->Cell( 101, 5, "", $border, 1 );
		$t_idx++;
	}

	/**
	=====================================================================
	**/

	/**
	=====================================================================
	**/
	// $pdf->Ln(20);
	$pdf->SetFont( $font_name, '', 11 );

	//===========================================================
	$pdf->Text( 2, 125, 'Tanda Terima');
	$pdf->Text( 130, 125, 'Checker');
	$pdf->Text( 170, 125, 'Hormat Kami');

	//=============================================================

	$pdf->Output( 'faktur_penjualan_.pdf', "I" );
?>