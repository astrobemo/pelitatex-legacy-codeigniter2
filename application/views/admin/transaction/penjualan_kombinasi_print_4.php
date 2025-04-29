<?

	foreach ($data_penjualan as $row) {
		$nama_customer = $row->nama_keterangan;
		$no_faktur = $row->no_faktur;
		$no_faktur_lengkap = $row->no_faktur_lengkap;
		$tanggal = date('d F Y', strtotime($row->tanggal));
		$kota = $row->kota;
		$kode_pos = $row->kode_pos;
		$alamat = $row->alamat_keterangan;
		$alamat_keterangan = $row->alamat_keterangan;
		$bayar_dp = $row->bayar_dp;
		$penjualan_type_id = $row->penjualan_type_id;
		$po_number = $row->po_number;
	}

	$alamat1 = substr(strtoupper(trim($alamat_keterangan)), 0,47);
   	$alamat2 = substr(strtoupper(trim($alamat_keterangan)), 47);
	$last_1 = substr($alamat1, -1,1);
	$last_2 = substr($alamat2, 0,1);

	$positions = array();
	$pos = -1;
	while (($pos = strpos(trim($alamat_keterangan)," ", $pos+1 )) !== false) {
		$positions[] = $pos;
	}

	$max = 47;
	if ($last_1 != '' && $last_2 != '') {
		$posisi =array_filter(array_reverse($positions),
			function($value) use ($max) {
				return $value <= $max;
			});

		$posisi = array_values($posisi);

		$alamat1 = substr(strtoupper(trim($alamat_keterangan)), 0,$posisi[0]);
	   	$alamat2 = substr(strtoupper(trim($alamat_keterangan)), $posisi[0]);
	}

	if ($kota != '' && $kode_pos != '' && $kode_pos != '00000') {
		$kota .= ' ,'.$kode_pos;
	}



	$pdf = new FPDF( 'L', 'mm', array(210 ,139 ) );
	$pdf->AliasNbPages();
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

	$xFooter = 195;
	$yFooter = 130;

	$pdf->SetFont( 'Arial', '', 6 );
	$pdf->Text($xFooter,$yFooter, $pdf->PageNo().' / {nb}');
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

	
	$pdf->Ln(-10);
	// $pdf->Cell( 10 );
	//=====================TITLE==================================
	$pdf->SetFont( $font_name_bold, '', 18 );
	$pdf->Cell( 200, 5, 'FAKTUR PENJUALAN', 0, 0, 'R' );
	
	//=====================header kiri==================================
	$pdf->SetFont( $font_name, '', 12 );
	$pdf->Text( 3, 8.3, strtoupper($nama_toko));
	$pdf->Text( 3, 12.3, strtoupper($alamat_toko).','.strtoupper($kota_toko));
	$pdf->Text( 3, 16.1, 'TELP: '.$phone );
	$pdf->Text( 3, 20.3, 'FAX: '.$fax );
	$pdf->Text( 3, 24.3, 'NPWP : '.$npwp );

	//=====================header kanan==================================
	$pdf->SetY(5.3);
	
	$pdf->SetFont( $font_name, '', 12 );
	$pdf->Cell( 200, 4, "BANDUNG, ".$tanggal, 0, 1, 'R' );
	$pdf->Cell( 200, 4, "KEPADA YTH,", 0, 1, 'R' );
	$pdf->Cell( 200, 4, strtoupper($nama_customer), 0, 1, 'R' );
	$pdf->Cell( 200, 4, $alamat1, 0, 1, 'R' );
	$pdf->Cell( 200, 4, $alamat2, 0, 1, 'R' );
	$pdf->Cell( 200, 4, $kota, 0, 1, 'R' );
	// $pdf->Cell( 200, 4, $kota, 0, 1, 'R' );

	
	$pdf->SetFont( $font_name, '', 12 );
	$pdf->Ln(1.5);

	$x1=0;
	$x2=0;
	$i=0;
	$y1=30.3;
	$y2=37;
	$y3=43.5;
	$pdf->SetDrawColor(120);
	while ($x2 < 201) {
		$x1 = (2*$i) +3;
		$x2 = (2*$i) +4.5;
		
		if ($x2==202.5) {
			$x2 = 203;
		}

		$pdf->Line($x1, $y1, $x2, $y1);
		// $pdf->SetDrawColor(120);
		$pdf->Line($x1, $y2, $x2, $y2);
		$pdf->Line($x1, $y3, $x2, $y3);
		$i++;	
	}
	
	// for ($i=0; $i < 20 ; $i++) { 
	// 	$x1 = (2*$i) +3;
	// 	$x2 = (2*$i) +4.5;
	// 	// if ($i > 0) {
	// 	// 	$x1 = ($i + 1) +3;
	// 	// 	$x2 = ($i + 1) +4.8;
	// 	// }
	// 	$pdf->Line($x1, 22.3, $x2, 22.3);
	// }
	
	// $pdf->Cell( 200, 0.2, '', 'T', 1, 'L' );
	if ($po_number != '') {
		$pdf->Cell( 100, 6, 'PO : '.$po_number, 0, 0, 'L' );
		$pdf->Cell( 200, 6, "INVOICE NO : ".$no_faktur_lengkap, 0, 1, 'R' );	
	}else{
		$pdf->Cell( 200, 6, "INVOICE NO : ".$no_faktur_lengkap, 0, 1, 'R' );	
	}


	// $pdf->Cell( 200, 0.2, '', 'T', 1, 'L' );
	$pdf->Cell( 200, 6.5, '', 0, 1, 'C' );
	// $pdf->Cell( 40, 6.5, 'TOTAL', 0, 1, 'C' );
	// $pdf->Cell( 0, 0, '', 1, 1, 'R' );

	$y_tbl_header = 41.5;

	$pdf->Text( 5.79, $y_tbl_header, 'JUMLAH' );
	$pdf->Text( 26.77, $y_tbl_header, 'SAT.' );
	$pdf->Text( 41.3, $y_tbl_header, 'ROLL' );
	$pdf->Text( 55, $y_tbl_header, 'NAMA' );
	$pdf->Text( 138.5, $y_tbl_header, 'HARGA' );
	$pdf->Text( 177.35, $y_tbl_header, 'TOTAL' );
	// $pdf->Cell( 200, 0.2, '', 'T', 1, 'L' );

	$pdf->setY(44.5);

	$pdf->SetFont( $font_name, '', 11 );
	$i = 1; $g_total = 0;$t_roll = 0;
	foreach ($data_penjualan_detail as $row) {
		// $pdf->Cell( 0, 0, '', 1, 1, 'R' );
		$pdf->Cell( 20, 5, is_qty_general($row->qty), 0, 0, 'C' );
		$pdf->Cell( 15, 5, $row->nama_satuan, 0, 0, 'C' );
		$pdf->Cell( 15, 5, $row->jumlah_roll, 0, 0, 'C' );
		$t_roll += $row->jumlah_roll;
		$pdf->Cell( 2,5,'',0);
		$pdf->Cell( 73, 5, $row->nama_barang, 0, 0, 'L' );
		// $pdf->Cell( 10);
		$pdf->Cell( 10, 5, '', 0, 0, 'C' );
		$pdf->Cell( 25, 5, 'Rp '.number_format($row->harga_jual,'0',',','.'), 0 , 0, 'L' );
		$pdf->Cell( 40, 5, 'Rp '.number_format($row->harga_jual*$row->qty,'0',',','.'), 0, 1, 'R' );
		$g_total += $row->harga_jual*$row->qty; 
		$i++;
		
	}

	// $pdf->Cell( 0, 0, '', 1, 1, 'R' );

	$x1=0;
	$x2=0;
	$i=0;
	$y_akhir_item = $pdf->getY()+1;
	$pdf->setY($pdf->getY() + 1.5);

	$pdf->SetFont( $font_name, '', 11.5 );

	$pdf->Cell( 35, 5, 'TOTAL ROLL', 0, 0, 'C' );
	$pdf->Cell( 15, 5, $t_roll, 0, 0, 'C' );

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
	$pdf->Cell( 32, 5, 'TOTAL', 0, 0, 'R' );
	$pdf->Cell( 3, 5, "*", 0, 0, 'L' );
	$pdf->Cell( 40, 5, 'Rp '.number_format($g_total - $bayar_dp,'0',',','.'), 0, 1, 'R' );

	$total_bayar = 0;
	foreach ($data_pembayaran as $row) {
		if ($row->amount != 0) {
			$pdf->Cell( 125, 5, '', 0, 0, 'C' );
			$pdf->Cell( 32, 5, $row->nama_bayar, 0, 0, 'R' );
			$pdf->Cell( 3, 5, "", 0, 0, 'C' );
			$pdf->Cell( 40, 5, 'Rp '.number_format($row->amount,'0',',','.'), 0, 1, 'R' );
			$total_bayar += $row->amount;	
		}
	}

	$y_akhir =  $pdf->getY();
	$x1=0;
	$x2=0;
	$i=0;
	$pdf->SetDrawColor(120);
	while ($x2 < 201) {
		$x1 = (2*$i) +3;
		$x2 = (2*$i) +4.5;
		
		if ($x2==202.5) {
			$x2 = 203;
		}

		$pdf->Line($x1, $y_akhir_item, $x2, $y_akhir_item);
		if ($x1 > 140) {
			$pdf->Line($x1, $y_akhir, $x2, $y_akhir);
		}
		$i++;
	}

	$pdf->Cell( 125, 0.5, '', 0, 0, 'C' );
	$pdf->Cell( 35, 0.5, "", 0, 0, 'C' );
	$pdf->Cell( 40, 0.5, '' , 0, 1, 'R' );

	$pdf->Cell( 125, 5, '', 0, 0, 'C' );
	$pdf->Cell( 32, 5, "KEMBALI", 0, 0, 'R' );
	$pdf->Cell( 3, 5, "", 0, 0, 'C' );
	$pdf->Cell( 40, 5, 'Rp '.number_format($total_bayar - $g_total,'0',',','.'), 0, 1, 'R' );

	$pdf->SetFont( $font_name_italic, '', 9 );
	$pdf->Cell( 200, 4, '*harga sudah termasuk ppn', 0, 1, 'R' );


	$pdf->SetFont( $font_name, '', 12 );
	//===========================================================
	$pdf->Text( 2, 125, 'Tanda Terima');
	$pdf->Text( 130, 125, 'Checker');
	$pdf->Text( 160, 125, 'Hormat Kami');

	//=============================================================

	// $pdf->Text($pdf->getX(),$pdf->getY(), $pdf->getY() );
	/**
	=============================================group=====================================
	**/

	$pdf->Ln(5);
	$posY = $pdf->getY() + 6;

	//qty per baris
	$qpb = 10;
	$total_baris = 0;
	$jml_baris = array();
	
	foreach ($data_penjualan_detail_group as $row) {

		$jumlah_roll = explode('??', $row->jumlah_roll);
		$warna_list = explode("??", $row->warna_id);

		for ($i=0; $i < count($jumlah_roll) ; $i++) { 
			$total_baris += ceil($jumlah_roll[$i]/$qpb);
			$jml_baris[$row->barang_id][$warna_list[$i]] = ceil($jumlah_roll[$i]/$qpb);
		}
	}

	if ($posY > 100 || $posY + ($total_baris * 5) > 120) {
		$pdf->AddPage();
		$pdf->setY(5);
		$pdf->SetFont( 'Arial', '', 6 );
		$pdf->Text($xFooter,$yFooter, $pdf->PageNo().' / {nb}');
	}

	// echo count($data_penjualan_detail);
	// echo '<br/>';
	// echo count($data_penjualan_detail_group);

	
	// $pdf->Text($pdf->getX(),$pdf->getY(), $pdf->getY() );

	$x1=0;
	$x2=0;
	$i=0;
	$pdf->SetDrawColor(120);
	$y1 = $pdf->getY();
	$y2 = $y1+5.5;
	while ($x2 < 201) {
		$x1 = (2*$i) +3;
		$x2 = (2*$i) +4.5;
		
		if ($x2==202.5) {
			$x2 = 203;
		}

		$pdf->Line($x1, $y1, $x2, $y1);
		$pdf->Line($x1, $y2, $x2, $y2);
		$i++;
	}

	$kolom1 = 34;
	$kolom2 = 34;
	$line = 1;
	$line2 = 2;
	$kolom3 = 10;
	$kolom4 = 14;
	$kolom5 = 105;
	$klm_detail = 10.5;

	$pdf->SetFont( $font_name, '', 10 );

	$pdf->Cell( $kolom1, 6, 'BARANG', 0, 0, 'L' );
	$pdf->Cell( $kolom2, 6, 'WARNA', 0, 0, 'L' );

	$pdf->SetFont( 'Arial', '', 8 );
	$pdf->Cell( $line, 6, '|', 0, 0, 'L' );
	
	$pdf->SetFont( $font_name, '', 10 );
	$pdf->Cell( $kolom3, 6, 'ROLL', 0, 0, 'C' );
	$pdf->Cell( $kolom4, 6, 'TOTAL', 0, 0, 'R' );
	
	$pdf->SetFont( 'Arial', '', 8 );
	$pdf->Cell( $line2, 6, '|', 0, 0, 'R' );

	$pdf->SetFont( $font_name, '', 10 );
	$pdf->Cell( $kolom5, 6, 'DETAIL', 0, 1, 'L' );
	
	$i = 1; 
	
	foreach ($data_penjualan_detail_group as $row) {

		$nama_warna = explode('??', $row->nama_warna);
		$data_qty = explode('??', $row->data_qty);
		$qty = explode('??', $row->qty);
		$jumlah_roll = explode('??', $row->jumlah_roll);
		$roll_qty = explode('??', $row->roll_qty);
		$warna_list = explode("??", $row->warna_id);
		
		$data_all = explode('=??=', $row->data_all);
		

		$total = 0;
		$total_roll = 0;
		
		$tinggi = 5;
		
		foreach ($nama_warna as $key => $value) {
			// $pdf->Text($pdf->getX(),$pdf->getY(), $pdf->getY() + $jml_baris[$row->barang_id][$warna_list[$key]] * $tinggi );

			$posY = $pdf->getY();
			if ($posY > 112) {

				$pdf->AddPage();
				$pdf->setY(5);
				$pdf->SetFont( 'Arial', '', 6 );
				$pdf->Text($xFooter,$yFooter, $pdf->PageNo().' / {nb}');
			}else if($pdf->getY() + $jml_baris[$row->barang_id][$warna_list[$key]] * $tinggi > 120){
				$pdf->AddPage();
				$pdf->setY(5);
				$pdf->SetFont( 'Arial', '', 6 );
				$pdf->Text($xFooter,$yFooter, $pdf->PageNo().' / {nb}');
			}

			$qty_c = array();

			$qty_detail = explode(' ', $data_qty[$key]);
			$roll_detail = explode(',', $roll_qty[$key]);

			$j = 0; 
			foreach ($qty_detail as $key2 => $value2) {
				if ($roll_detail[$key2] == 0) {
					$roll_detail[$key2] = 1;
				}
				
				for ($l=0; $l < $roll_detail[$key2] ; $l++) { 
					$qty_c[$j] = number_format($qty_detail[$key2],'2','.',',');
					$j++;
				}
			}

			// print_r($qty_detail);echo '<br/>';
			// print_r($roll_detail);echo '<hr/>';
			asort($qty_c);

			$jml_angka = count($qty_c);
			// print_r($qty_c);
			$qty_c = array_values($qty_c);
			$jml_angka;
			$baris = ceil($jml_angka/$qpb);
			$total_baris = $baris;

			$kolom = $qpb - ( count($qty_c) % $qpb);

			$pdf->SetFont( 'Arial', '', 9 );
			$idx = 1;
			$baris_idx = 1;
			$y = $pdf->GetY();
			$x = $pdf->GetX();

			// print_r($qty_c);
			// echo "<hr/>";
			foreach ($qty_c as $keyX => $valueX) {
				// echo $keyX.' '.$valueX.' &nbsp;';
				// $row->nama_barang = 'POLY RIPSTOP GRADE B';
			
				if ($keyX % $qpb == 0 && $baris_idx == 1) {
					
					$y = $pdf->getY();
					// cek klo nama barang kepanjangan and punya > 1 baris
					if (strlen($row->nama_barang) >= 18 && $baris != 1) {
						$pdf->SetFont( 'Arial', '', 7.5 );
						$pdf->Multicell( $kolom1, $tinggi, $row->nama_barang, 0 , 'L' );
					// cek klo nama barang kepanjangan and cuman punya 1 baris
					}elseif (strlen($row->nama_barang) >= 18 && $baris == 1) {
						$pdf->SetFont( 'Arial', '', 7.5 );
						$pdf->Cell( $kolom1, $tinggi, $row->nama_barang, 0, 'L' );
					}else{
						$pdf->SetFont( 'Arial', '', 8 );
						$pdf->Cell( $kolom1, $tinggi, $row->nama_barang, 0, 0, 'L' );
					}

					// set nama warna
					$pdf->setXY(3, $y);
					$pdf->Cell( $kolom1, $tinggi, '', 0, 0, 'L' );
					$pdf->Cell( $kolom2, $tinggi, $value, 0, 0, 'L' );
					$pdf->Cell( $line, $tinggi, "|", 0,0, 'C' );
				

					
				}else if ($keyX % $qpb == 0) {
					$pdf->SetFont( 'Arial', '', 8 );

					// set space kosong baris > 1, 
					$pdf->Cell( $kolom1, 5, "", 0, 0, 'C' );
					$pdf->Cell( $kolom2, 5, "", 0, 0, 'C' );
					$pdf->Cell( $line, $tinggi, "|", 0,0, 'C' );

				}


				$pdf->SetFont( 'Arial', '', 8 );

				// klo baris 1 set data roll, 
				if( $idx == 1){
					$pdf->Cell( $kolom3, $tinggi, $jumlah_roll[$key], 0, 0, 'C' );
					$pdf->Cell( $kolom4, $tinggi, str_replace('.00','',number_format($qty[$key],'2','.',',')), 0, 0,'R');
					$pdf->Cell( $line2, $tinggi, '|', 0, 0, "R" );
					
				}else if($idx > 1 && $idx % $qpb == 1){
					$pdf->Cell( $kolom3, $tinggi, '', 0, 0, 'C' );
					$pdf->Cell( $kolom4, $tinggi, '', 0, 0,'R');
					$pdf->Cell( $line2, $tinggi, '|', 0, 0, "R" );
				}


				//ISI DETAIL
				// $pdf->Cell(10,$tinggi, $idx.' '.count($qty_c),0,0,'R');
				$br = ($idx % $qpb == 0 ? 1 : 0) ;
				// $pdf->Cell(10,$tinggi, is_qty_general($valueX),0, $br ,'L');
				$pdf->Cell($klm_detail,$tinggi, is_qty_general($valueX),0, $br ,'L');

				if ($baris_idx == $baris && $idx % $qpb == 1		) {
					$x1=0;
					$x2=0;
					$j=0;
					$pdf->SetDrawColor(120);
					$y1 = $pdf->getY() + $tinggi;
					// $y2 = $y1+5.5;
					while ($x2 < 201) {
						$x1 = (2*$j) +3;
						$x2 = (2*$j) +4.5;
						
						if ($x2==202.5) {
							$x2 = 203;
						}

						$pdf->Line($x1, $y1, $x2, $y1);
						// $pdf->Line($x1, $y2, $x2, $y2);
						$j++;
					}
				}

				//UNTUK FILL BLANK COLUMN
				if ($idx == count($qty_c) && count($qty_c) % $qpb != 0 ) {
					// for ($i=0; $i < $kolom ; $i++) {
					// 	$br = ($i == $kolom - 1 ? 1 : 0) ;
					// 	$pdf->Cell(10,$tinggi,'',0,$br,'R');
					// 	$idx += ($i == $kolom - 1 ? 0 : 1) ;;
					// }
					// echo '<hr/>';
					$pdf->Cell($kolom * $klm_detail,$tinggi,'',0,1,'R');
					$idx = $qpb;
				}



				if($idx % $qpb == 0 && $baris_idx == $baris){

					// $pdf->Cell( 16, $tinggi, '', 'BR', 1,'R');
					$baris_idx++; 

				}elseif($idx % $qpb == 0){
					// $pdf->Cell( 16, $tinggi, '', 'R', 1,'R');
					$baris_idx++; 
					
				}
				
				// $pdf->SetFont( 'Arial', '', 11 );

				$idx++;
			}


			// $pdf->Multicell( 80, 5, $qty_show, 'TB', 'L' );
			// $pdf->SetXY($x+75,$y);
			// $pdf->Cell( 4, 5*$baris, '', 'TRB', 0, 'R' );

			// $pdf->Cell( 10, 5*$baris, $jumlah_roll[$key], 1, 0, 'C' );
			// $pdf->Cell( 20, 5*$baris, number_format($qty[$key],'0',',','.'), 1, 1, 'R' );
			$total_roll +=  $jumlah_roll[$key];
			$total += $qty[$key]; 

		
		}

		// $pdf->SetFont( 'Arial', '', 10.5 );
		// $pdf->Cell( 150, 6, '', "T", 0, 'C' );
		// $pdf->Cell( 23, 6, 'TOTAL', "T", 0, 'C' );
		// $pdf->Cell( 11, 6, $total_roll, "BLT", 0, 'C' );
		// $pdf->Cell( 15, 6, str_replace('.00','',number_format($total,'2','.',',')), "BLT", 0, 'R' );
		// $pdf->Cell( 1, 6, '', "BRT", 1, 'L' );
		// $i++;

		// $pdf->Ln(5);
		

	}

	$pdf->Output( 'faktur_penjualan_'.$no_faktur_lengkap.'.pdf', "I" );
?>