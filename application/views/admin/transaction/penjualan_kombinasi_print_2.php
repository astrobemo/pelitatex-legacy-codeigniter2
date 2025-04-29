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

	// $font_name = 'Arial';
	// $font_name_bold = 'Arial';
	// $font_name_italic = 'Arial';

	$pdf->SetFont( $font_name, '', 12 );

	$pdf->Ln(-10);
	// $pdf->Cell( 10 );
	$pdf->Cell( 2, 4, 'CV. PELITA ABADI ', 0, 1, 'L' );

	$pdf->Cell( 0, 4, 'TAMIM NO. 53, BANDUNG', 0, 0, 'L' );

	$pdf->Cell( 0, 4, '', 0, 1, 'R' );

	$pdf->Cell( 50, 4, 'TELP/FAX: (022)4238165 / (022)4218628', 0, 1, 'L' );
	$pdf->Cell( 50, 4, 'NPWP : 74.113.065.2-428.000', 0, 0, 'L' );

	$pdf->SetFont( $font_name_bold, '', 18 );
	$pdf->Cell( 135, 4, 'FAKTUR PENJUALAN', 0, 1, 'R' );
	$pdf->SetFont( $font_name, '', 12 );
	

	$pdf->Ln(2);
	$pdf->Cell( 185, 1, '', 'T', 1, 'L' );
	$pdf->Cell( 165, 4, 'KEPADA YTH,', 0, 0, 'L' );
	$pdf->Cell( 20, 4, "INVOICE NO : ".$no_faktur_lengkap, 0, 1, 'R' );	

	$pdf->Cell( 165, 4, strtoupper($nama_customer), 0, 0, 'L' );
	$pdf->Cell( 20, 4, 'BANDUNG, '.$tanggal, 0, 1, 'R' );
	$pdf->Cell( 0, 4, strtoupper($alamat), 0, 1, 'L' );
	$pdf->Cell( 0, 4, strtoupper($kota), 0, 1, 'L' );

	$pdf->Ln();

	//===========================================================
	$pdf->Text( 2, 125, 'Tanda Terima');
	$pdf->Text( 130, 125, 'Checker');
	$pdf->Text( 160, 125, 'Hormat Kami');

	//=============================================================

	if ($penjualan_type_id != 3) {
		$pdf->Cell( 0, 6, 'PO : '.$po_number, 0, 1, 'L' );
	}

	$pdf->Cell( 20, 6, 'Jumlah', 1, 0, 'C' );
	$pdf->Cell( 15, 6, 'Satuan', 1, 0, 'C' );
	$pdf->Cell( 15, 6, 'Roll', 1, 0, 'C' );
	$pdf->Cell( 60, 6, 'Nama Barang', 1, 0, 'C' );
	$pdf->Cell( 35, 6, 'Harga', 1, 0, 'C' );
	$pdf->Cell( 40, 6, 'Total', 1, 1, 'C' );
	// $pdf->Cell( 0, 0, '', 1, 1, 'R' );


	$i = 1; $g_total = 0; $t_roll = 0;
	foreach ($data_penjualan_detail as $row) {
		// $pdf->Cell( 0, 0, '', 1, 1, 'R' );
		$pdf->Cell( 20, 5, number_format($row->qty,'2',',','.'), 1, 0, 'C' );
		$pdf->Cell( 15, 5, $row->nama_satuan, 1, 0, 'C' );
		$pdf->Cell( 15, 5, $row->jumlah_roll, 1, 0, 'C' );
		$t_roll += $row->jumlah_roll;
		$pdf->Cell( 2,5,'','TLB');
		$pdf->Cell( 58, 5, $row->nama_barang, 'TRB', 0, 'L' );
		// $pdf->Cell( 10);
		$pdf->Cell( 35, 5, 'Rp'.number_format($row->harga_jual,'2',',','.'), 1, 0, 'C' );
		$pdf->Cell( 40, 5, 'Rp'.number_format($row->harga_jual*$row->qty,'2',',','.'), 1, 1, 'R' );
		$g_total += $row->harga_jual*$row->qty; 
		$i++;
		
	}



	//===========================================

	$pdf->SetFont( $font_name_bold, '', 12 );
	// $pdf->Cell( 0, 0, '', 1, 1, 'R' );
	$pdf->Cell( 185, 0.5, '', 1, 1, 'C' );
	$pdf->Cell( 35, 5, 'TOTAL ROLL', 1, 0, 'C' );
	$pdf->Cell( 15, 5, $t_roll, 1, 0, 'C' );
	
	// $pdf->Cell( 60, 5, '', 0, 0, 'C' );
	// if ($bayar_dp != 0) {
	// 	$pdf->Cell( 35, 5, 'Subtotal', 1, 0, 'C' );
	// }else{
	// 	$pdf->Cell( 35, 5, 'Total*', 1, 0, 'C' );
	// }
	// $pdf->Cell( 40, 5, 'Rp '.number_format($g_total,'2',',','.'), 1, 1, 'R' );

	// if ($bayar_dp != 0) {
	// 	$pdf->Cell( 110, 5, '', 0, 0, 'C' );
	// 	$pdf->Cell( 35, 5, 'DP', 1, 0, 'C' );
	// 	$pdf->Cell( 40, 5, 'Rp '.number_format($bayar_dp,'2',',','.'), 1, 1, 'R' );

	// 	$pdf->Cell( 110, 5, '', 0, 0, 'C' );
	// 	$pdf->Cell( 35, 5, 'Total*', 1, 0, 'C' );
	// 	$pdf->Cell( 40, 5, 'Rp '.number_format($g_total - $bayar_dp,'2',',','.'), 1, 1, 'R' );
	// }

	$pdf->Cell( 60, 5, '', 0, 0, 'C' );
	$pdf->Cell( 35, 5, 'Total*', 1, 0, 'C' );
	$pdf->Cell( 40, 5, 'Rp '.number_format($g_total - $bayar_dp,'2',',','.'), 1, 1, 'R' );

	$total_bayar = 0;
	foreach ($data_pembayaran as $row) {
		if ($row->amount != 0) {
			$pdf->Cell( 110, 5, '', 0, 0, 'C' );
			$pdf->Cell( 35, 5, $row->nama_bayar, 1, 0, 'C' );
			$pdf->Cell( 40, 5, 'Rp '.number_format($row->amount,'2',',','.'), 1, 1, 'R' );
			$total_bayar += $row->amount;
		}
	}
	$pdf->Cell( 110, 0.5, '', 0, 0, 'C' );
	$pdf->Cell( 35, 0.5, "", 1, 0, 'C' );
	$pdf->Cell( 40, 0.5, '' , 1, 1, 'R' );
	
	$pdf->Cell( 110, 5, '', 0, 0, 'C' );
	$pdf->Cell( 35, 5, "KEMBALI", 1, 0, 'C' );
	$pdf->Cell( 40, 5, 'Rp '.number_format($total_bayar - $g_total,'2',',','.'), 1, 1, 'R' );

	$pdf->SetFont( $font_name, '', 9 );
	$pdf->Cell( 185, 4, '*harga sudah termasuk ppn', 0, 1, 'R' );

	$pdf->Ln(10);
	$pdf->SetFont( $font_name, '', 10 );

	$pdf->Cell( 45, 6, 'Spec', 1, 0, 'C' );
	$pdf->Cell( 45, 6, 'Warna', 1, 0, 'C' );
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
					$qty_c[$j] = str_replace('.00','',number_format($qty_detail[$key2]));
					// $qty_c[$j] = $qty_detail[$key2];
					$j++;
				}
			}

			$qty_show = implode($qty_c, ' ');
			// $baris = ceil($j/7);
			$baris = ceil(strlen($qty_show)/28);

			$pdf->SetFont( $font_name, '', 11 );
			$pdf->Cell( 50, 5*$baris, $row->nama_barang, 1, 0, 'C' );
			$pdf->Cell( 50, 5*$baris, $nama_warna[$key], 1, 0, 'C' );
			$pdf->Cell( 1, 5*$baris, '', 'TLB', 0, 'L' );
			
			$y = $pdf->GetY();
			$x = $pdf->GetX();
			$pdf->Multicell( 50, 5, $qty_show, 'TB', 'L' );
			$pdf->SetXY($x+50,$y);
			$pdf->Cell( 4, 5*$baris, '', 'TRB', 0, 'R' );

			$pdf->Cell( 10, 5*$baris, $jumlah_roll[$key], 1, 0, 'C' );
			$pdf->Cell( 20, 5*$baris, number_format($qty[$key],'0',',','.'), 1, 1, 'R' );
			$total_roll +=  $jumlah_roll[$key];
			$total += $qty[$key]; 
		
		}

		$pdf->SetFont( $font_name_bold, '', 12 );
		$pdf->Cell( 135, 5, '', 0, 0, 'C' );
		$pdf->Cell( 20, 5, 'TOTAL', 1, 0, 'C' );
		$pdf->Cell( 10, 5, $total_roll, 1, 0, 'C' );
		$pdf->Cell( 20, 5, number_format($total,'0',',','.'), 1, 1, 'R' );
		$i++;

		$pdf->Ln(5);

	}
	
	$pdf->SetFont( $font_name, '', 12 );

	//===========================================================
	$pdf->Text( 2, 125, 'Tanda Terima');
	$pdf->Text( 130, 125, 'Checker');
	$pdf->Text( 160, 125, 'Hormat Kami');

	//=============================================================

	$pdf->Output( 'faktur_penjualan_'.$no_faktur_lengkap.'.pdf', "I" );
?>