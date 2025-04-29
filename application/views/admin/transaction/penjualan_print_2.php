<?

	foreach ($data_penjualan as $row) {
		$nama_customer = $row->nama_keterangan;
		$no_faktur = $row->no_faktur;
		$no_faktur_lengkap = $row->no_faktur_lengkap;
		$tanggal = date('d F Y', strtotime($row->tanggal));
		$kota = $row->kota;
		$alamat = $row->alamat_keterangan;
		$bayar_dp = $row->bayar_dp;
		$penjualan_type_id = $row->penjualan_type_id;
		$po_number = $row->po_number;
	}


	$pdf = new FPDF( 'L', 'mm', array(215 ,139 ) );
	$pdf->cMargin = 0;
	$pdf->AddPage();
	$pdf->SetMargins(3,0,3);
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

	foreach ($toko_data as $row) {
		$nama_toko = $row->nama;
		$alamat_toko = $row->alamat;
		$phone = $row->telepon;
		$fax = $row->fax;
		$npwp = $row->NPWP;
		$kota_toko = $row->kota;
	}

	$pdf->SetFont( $font_name, '', 12 );

	$pdf->Ln(-10);
	// $pdf->Cell( 10 );
	$pdf->Cell( 2, 4, strtoupper($nama_toko), 0, 1, 'L' );

	$pdf->Cell( 0, 4, strtoupper($alamat_toko).','.strtoupper($kota_toko), 0, 0, 'L' );

	$pdf->Cell( 0, 4, '', 0, 1, 'R' );

	$pdf->Cell( 50, 4, 'TELP/FAX: '.$phone.' / '.$fax, 0, 1, 'L' );
	$pdf->Cell( 50, 4, 'NPWP : '.$npwp, 0, 0, 'L' );

	$pdf->SetFont( $font_name_bold, '', 18 );
	$pdf->Cell( 150, 4, 'FAKTUR PENJUALAN', 0, 1, 'R' );
	$pdf->SetFont( $font_name, '', 12 );
	

	$pdf->Ln(2);
	$pdf->Cell( 200, 1, '', 'T', 1, 'L' );
	$pdf->Cell( 170, 4, 'KEPADA YTH,', 0, 0, 'L' );
	$pdf->Cell( 30, 4, "INVOICE NO : ".$no_faktur_lengkap, 0, 1, 'R' );	

	$pdf->Cell( 180, 4, strtoupper($nama_customer), 0, 0, 'L' );
	$pdf->Cell( 20, 4, 'BANDUNG, '.$tanggal, 0, 1, 'R' );
	$pdf->Cell( 0, 4, strtoupper($alamat), 0, 1, 'L' );
	$pdf->Cell( 0, 4, strtoupper($kota), 0, 1, 'L' );

	$pdf->Ln();

	if ($penjualan_type_id != 3) {
		$pdf->Cell( 0, 6, 'PO : '.$po_number, 0, 1, 'L' );
	}

	$pdf->Cell( 20, 6, 'Jumlah', 1, 0, 'C' );
	$pdf->Cell( 15, 6, 'Satuan', 1, 0, 'C' );
	$pdf->Cell( 15, 6, 'Roll', 1, 0, 'C' );
	$pdf->Cell( 75, 6, 'Nama Barang', 1, 0, 'C' );
	$pdf->Cell( 35, 6, 'Harga', 1, 0, 'C' );
	$pdf->Cell( 40, 6, 'Total', 1, 1, 'C' );
	// $pdf->Cell( 0, 0, '', 1, 1, 'R' );


	$i = 1; $g_total = 0;$t_roll = 0;
	foreach ($data_penjualan_detail as $row) {
		// $pdf->Cell( 0, 0, '', 1, 1, 'R' );
		$pdf->Cell( 20, 5, is_qty_general($row->qty), 1, 0, 'C' );
		$pdf->Cell( 15, 5, $row->nama_satuan, 1, 0, 'C' );
		$pdf->Cell( 15, 5, $row->jumlah_roll, 1, 0, 'C' );
		$t_roll += $row->jumlah_roll;
		$pdf->Cell( 2,5,'','TLB');
		$pdf->Cell( 73, 5, $row->nama_barang, 'TRB', 0, 'L' );
		// $pdf->Cell( 10);
		$pdf->Cell( 35, 5, 'Rp '.number_format($row->harga_jual,'0',',','.'), 1, 0, 'C' );
		$pdf->Cell( 40, 5, 'Rp '.number_format($row->harga_jual*$row->qty,'0',',','.'), 1, 1, 'R' );
		$g_total += $row->harga_jual*$row->qty; 
		$i++;
		
	}

	$pdf->SetFont( $font_name_bold, '', 12 );
	// $pdf->Cell( 0, 0, '', 1, 1, 'R' );
	$pdf->Cell( 190, 0.5, '', 1, 1, 'C' );
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

	$pdf->Cell( 75, 5, '', 0, 0, 'C' );
	$pdf->Cell( 35, 5, 'Total*', 1, 0, 'C' );
	$pdf->Cell( 40, 5, 'Rp '.number_format($g_total - $bayar_dp,'0',',','.'), 1, 1, 'R' );

	$total_bayar = 0;
	foreach ($data_pembayaran as $row) {
		if ($row->amount != 0) {
			$pdf->Cell( 125, 5, '', 0, 0, 'C' );
			$pdf->Cell( 35, 5, $row->nama_bayar, 1, 0, 'C' );
			$pdf->Cell( 40, 5, 'Rp '.number_format($row->amount,'0',',','.'), 1, 1, 'R' );
			$total_bayar += $row->amount;	
		}
	}

	$pdf->Cell( 125, 0.5, '', 0, 0, 'C' );
	$pdf->Cell( 35, 0.5, "", 1, 0, 'C' );
	$pdf->Cell( 40, 0.5, '' , 1, 1, 'R' );

	$pdf->Cell( 125, 5, '', 0, 0, 'C' );
	$pdf->Cell( 35, 5, "KEMBALI", 1, 0, 'C' );
	$pdf->Cell( 40, 5, 'Rp '.number_format($total_bayar - $g_total,'0',',','.'), 1, 1, 'R' );

	$pdf->SetFont( $font_name, '', 10 );
	$pdf->Cell( 200, 4, '*harga sudah termasuk ppn', 0, 1, 'R' );

	// $pdf->Ln(20);
	$pdf->SetFont( $font_name, '', 11 );

	//===========================================================
	$pdf->Text( 2, 125, 'Tanda Terima');
	$pdf->Text( 130, 125, 'Checker');
	$pdf->Text( 170, 125, 'Hormat Kami');

	//=============================================================

	$pdf->Output( 'faktur_penjualan_'.$no_faktur_lengkap.'.pdf', "I" );
?>