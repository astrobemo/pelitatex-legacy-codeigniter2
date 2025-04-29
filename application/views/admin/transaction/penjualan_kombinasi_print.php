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
	$pdf->SetMargins(2,2,3);
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
	$pdf->Cell( 0, 4, 'FAKTUR PENJUALAN', 0, 1, 'R' );
	$pdf->SetFont( $font_name, '', 11 );
	

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
	$pdf->SetFont( $font_name, 'I', 8 );
	$pdf->Cell( 0, 5, '*Harga di atas sudah termasuk PPN', 0, 1, 'R' );


	$pdf->SetFont( $font_name, '', 11 );
	//===========================================================
	$pdf->Text( 2, 125, 'Tanda Terima');
	$pdf->Text( 130, 125, 'Checker');
	$pdf->Text( 160, 125, 'Hormat Kami');
	//===========================================================
	$pdf->Ln();
	

	$j = 1; 

	$pdf->Cell( 50, 6, 'Spec', 1, 0, 'C' );
	$pdf->Cell( 50, 6, 'Warna', 1, 0, 'C' );
	$pdf->Cell( 65, 6, 'Detail', 1, 0, 'C' );
	$pdf->Cell( 10, 6, 'Rol', 1, 0, 'C' );
	$pdf->Cell( 20, 6, 'Total', 1, 1, 'C' );
	

	$i = 1; 
	foreach ($data_penjualan_detail_group as $row) {

		$nama_warna = explode('??', $row->nama_warna);
		$data_qty = explode('??', $row->data_qty);
		$qty = explode('??', $row->qty);
		$jumlah_roll = explode('??', $row->jumlah_roll);
		$roll_qty = explode('??', $row->roll_qty);

		$total = 0;
		$total_roll = 0;

		foreach ($nama_warna as $key => $value) {
			unset($qty_c);

			$qty_detail = explode(' ', $data_qty[$key]);
			$roll_detail = explode(',', $roll_qty[$key]);

			$j = 0;
			foreach ($roll_detail as $key2 => $value2) {
				for ($l=0; $l < $value2 ; $l++) { 
					$qty_c[$j] = $qty_detail[$key2];
					$j++;
				}
			}

			$baris = ceil($j/8);

			$qty_show = implode($qty_c, ' ');
			
			$pdf->SetFont( $font_name, '', 11 );

			if ($baris > 1) {
				$x = $pdf->GetX();
				$pdf->Cell( 50, 5*$baris, '', 1, 0, 'C' );
				$y = $pdf->GetY();
				$p = strlen($row->nama_barang);
				$tx = ceil((50 - $p)/3);
				$pdf->Text($x + $tx,$y+4,$row->nama_barang);
				
				$x = $pdf->GetX();
				$pdf->Cell( 50, 5*$baris, '', 1, 0, 'C' );
				$y = $pdf->GetY();
				$p = strlen($nama_warna[$key]);
				$tx = ceil((50 - $p)/3);
				$pdf->Text($x + $tx,$y+4,$nama_warna[$key]);

				$pdf->Cell( 1, 5*$baris, '', 'TLB', 0, 'L' );
				
				$y = $pdf->GetY();
				$x = $pdf->GetX();
				$pdf->Multicell( 64, 5, $qty_show, 'TRB', 'L' );
				$pdf->SetXY($x+64,$y);

				$x = $pdf->GetX();
				$pdf->Cell( 10, 5*$baris, '', 1, 0, 'C' );
				$y = $pdf->GetY();
				$p = strlen($jumlah_roll[$key]);
				$tx = ceil((50 - $p)/3);
				$pdf->Text($x + 3,$y+4,$jumlah_roll[$key]);

				$x = $pdf->GetX();
				$pdf->Cell( 20, 5*$baris, '', 1, 1, 'R' );
				// $pdf->Cell( 10, 5*$baris, '', 1, 0, 'C' );
				// $y = $pdf->GetY();
				$p = strlen($qty[$key]);
				$tx = 192.5 - $p;
				$pdf->Text($tx,$y+4,$qty[$key]);

			}else{

				$pdf->Cell( 50, 5*$baris, $row->nama_barang, 1, 0, 'C' );
				$pdf->Cell( 50, 5*$baris, $nama_warna[$key], 1, 0, 'C' );
				$pdf->Cell( 1, 5*$baris, '', 'TLB', 0, 'L' );
				
				$y = $pdf->GetY();
				$x = $pdf->GetX();
				$pdf->Multicell( 64, 5, $qty_show, 'TRB', 'L' );
				$pdf->SetXY($x+64,$y);
				$pdf->Cell( 10, 5*$baris, $jumlah_roll[$key], 1, 0, 'C' );
				$pdf->Cell( 20, 5*$baris, $qty[$key], 1, 1, 'R' );

			}

			$total_roll +=  $jumlah_roll[$key];
			$total += $qty[$key]; 
		
		}

		$pdf->SetFont( $font_name, 'B', 11 );
		$pdf->Cell( 145, 5, '', 0, 0, 'C' );
		$pdf->Cell( 20, 5, 'TOTAL', 1, 0, 'C' );
		$pdf->Cell( 10, 5, $total_roll, 1, 0, 'C' );
		$pdf->Cell( 20, 5, $total, 1, 1, 'R' );
		$i++;

		$pdf->Ln(5);

	}


	
	$pdf->Ln(20);
	$pdf->SetFont( $font_name, '', 10 );
	//===========================================================
	$pdf->Text( 2, 125, 'Tanda Terima');
	$pdf->Text( 130, 125, 'Checker');
	$pdf->Text( 160, 125, 'Hormat Kami');

	//=============================================================

	$pdf->Output( 'Penjualan_faktur_detail.pdf', "I" );

?>