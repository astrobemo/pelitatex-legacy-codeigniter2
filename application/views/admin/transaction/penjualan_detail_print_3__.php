<?
	$nama_customer = '';
	$tanggal = '';
	$no_faktur = '';
	$no_surat_jalan = '';
	$penjualan_type_id = '';
	$po_number = '';

	foreach ($data_penjualan as $row) {
		$nama_customer = $row->nama_customer;
		$no_faktur = $row->no_faktur;
		$no_faktur_lengkap = $row->no_faktur_lengkap;
		$tanggal = date('d F Y', strtotime($row->tanggal));
		$kota = $row->kota;
		$alamat = $row->alamat;
		$bayar_dp = $row->bayar_dp;
		$no_surat_jalan = $row->no_surat_jalan;
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

	$pdf->SetFont( $font_name, '', 11 );

	$pdf->Ln(-10);
	// $pdf->Cell( 10 );
	$pdf->Cell( 2, 4, 'CV. PELITA ABADI ', 0, 1, 'L' );

	$pdf->Cell( 0, 4, 'TAMIM NO. 53, BANDUNG', 0, 0, 'L' );

	$pdf->Cell( 0, 4, '', 0, 1, 'R' );

	$pdf->Cell( 50, 4, 'TELP/FAX: (022)4238165 / (022)4218628', 0, 1, 'L' );
	$pdf->Cell( 50, 4, 'NPWP : 74.113.065.2-428.000', 0, 0, 'L' );

	$pdf->SetFont( $font_name_bold, '', 18 );
	$pdf->Cell( 135, 4, 'FAKTUR PENJUALAN', 0, 1, 'R' );
	$pdf->SetFont( $font_name, '', 11 );
	

	$pdf->Ln(2);
	$pdf->Cell( 185, 1, '', 'T', 1, 'L' );
	$pdf->Cell( 165, 4, 'KEPADA YTH,', 0, 0, 'L' );
	$pdf->Cell( 20, 4, "INVOICE NO : ".$no_faktur_lengkap, 0, 1, 'R' );	

	$pdf->Cell( 165, 4, strtoupper($nama_customer), 0, 0, 'L' );
	$pdf->Cell( 20, 4, 'BANDUNG, '.$tanggal, 0, 1, 'R' );
	$pdf->Cell( 0, 4, strtoupper($alamat), 0, 1, 'L' );
	$pdf->Cell( 0, 4, strtoupper($kota), 0, 1, 'L' );


	$pdf->Ln();

	if ($penjualan_type_id != 3) {
		$pdf->Cell( 0, 6, 'PO : '.$po_number, 0, 1, 'L' );
	}

	$pdf->SetFont( $font_name, '', 11 );

	$pdf->Cell( 50, 6, 'Spec', 1, 0, 'C' );
	$pdf->Cell( 50, 6, 'Warna', 1, 0, 'C' );
	$pdf->Cell( 55, 6, 'Detail', 1, 0, 'C' );
	$pdf->Cell( 10, 6, 'Rol', 1, 0, 'C' );
	$pdf->Cell( 20, 6, 'Total', 1, 1, 'C' );
	

	$i = 1; 
	foreach ($data_penjualan_detail_group as $row) {

		$nama_warna = explode('??', $row->nama_warna);
		$data_qty = explode('??', $row->data_qty);
		$qty = explode('??', $row->qty);
		$jumlah_roll = explode('??', $row->jumlah_roll);
		$roll_qty = explode('??', $row->roll_qty); 
		// echo $row->data_all;
		$data_all = explode('=??=', $row->data_all);
		

		$total = 0;
		$total_roll = 0;

		foreach ($nama_warna as $key => $value) {

			unset($qty_c);

			$qty_detail = explode(' ', $data_qty[$key]);
			$roll_detail = explode(',', $roll_qty[$key]);

			$j = 0;
			foreach ($qty_detail as $key2 => $value2) {
				for ($l=0; $l < $roll_detail[$key2] ; $l++) { 
					$qty_c[$j] = str_replace('.00','',number_format($qty_detail[$key2],'2','.',','));
					$j++;
				}
			}

			
			asort($qty_c);

			$jml_angka = count($qty_c);
			// print_r($qty_c);
			$qty_c = array_values($qty_c);
			$jml_angka;
			$baris = ceil($jml_angka/10);
			$total_baris = $baris;

			if ($total_baris > 14) {

				$baris = 14;
				// $show_break = substr($qty_show, 0 , 421);

				$pdf->SetFont( $font_name, '', 10 );
				$pdf->Cell( 37.5, 5*$baris, $row->nama_barang, 'TLR', 0, 'C' );
				$pdf->Cell( 37.5, 5*$baris, $nama_warna[$key], 'TLR', 0, 'C' );
				
				for ($i=0; $i < 14; $i++) { 
					if ($i != 0 ) {
						$pdf->Cell(75);
					}
					$roll_baris = 0;
					$qty_baris = 0;
					// $pdf->Cell( 1, 5, '', 'L', 0, 'L' );
					for ($j=0; $j < 10 ; $j++) { 
						$pdf->Cell(6, 5, $qty_c[$j+($i*9)+$i],0,0,'R');
						$pdf->Cell(2, 5, ' ',0,0,'R');
						$roll_baris++;
						$qty_baris += $qty_c[$j+($i*9)+$i];
						$last = $j;
					}

					$pdf->Cell( 10, 5, $roll_baris, 'LR', 0, 'C' );
					$pdf->Cell( 20, 5, number_format($qty_baris,'0',',','.'), 'LR', 1, 'C' );
				}
				//===========================================================
				$pdf->Text( 2, 125, 'Tanda Terima');
				$pdf->Text( 130, 125, 'Checker');
				$pdf->Text( 160, 125, 'Hormat Kami');

				//=============================================================
				
				$new_total_baris = ceil(($total_baris - 14)/23);
				$end_baris = ($total_baris-14) % 23;
				
				for ($page=0; $page < $new_total_baris ; $page++) { 
					if ($page != $new_total_baris - 1) {
						$multiple = 14 + (23 * $page);

						$pdf->Cell( 37.5, 5*23, $row->nama_barang, 'LR', 0, 'C' );
						$pdf->Cell( 37.5, 5*23, $nama_warna[$key], 'LR', 0, 'C' );

						for ($i=0; $i < 23; $i++) { 
							if ($i != 0 ) {
								$pdf->Cell(75);
							}
							$roll_baris = 0;
							$qty_baris = 0;
							// $pdf->Cell( 1, 5, '', 'L', 0, 'L' );
							for ($j=0; $j < 10 ; $j++) { 
								$pdf->Cell(6, 5, $qty_c[(10*$multiple) + $j+($i*9)+$i],0,0,'R');
								$pdf->Cell(2, 5, ' ',0,0,'R');
								$roll_baris++;
								$qty_baris += $qty_c[(10*$multiple) + $j+($i*9)+$i];
								$last = $j;
							}

							$pdf->Cell( 10, 5, $roll_baris, 'LR', 0, 'C' );
							$pdf->Cell( 20, 5, number_format($qty_baris,'0',',','.'), 'LR', 1, 'C' );
						}
					}else{

						if ($end_baris == 0) {
							$end_baris = 23;
						}

						$multiple = 14 + (23 * $page);

						$pdf->Cell( 37.5, 5*($end_baris+1), $row->nama_barang, 'BLR', 0, 'C' );
						$pdf->Cell( 37.5, 5*($end_baris+1), $nama_warna[$key], 'BLR', 0, 'C' );

						for ($i=0; $i < $end_baris; $i++) { 
							if ($i != 0 ) {
								$pdf->Cell(75);
							}
							$roll_baris = 0;
							$qty_baris = 0;
							// $pdf->Cell( 1, 5, '', 'L', 0, 'L' );
							for ($j=0; $j < 10 ; $j++) { 
								if (!isset($qty_c[(10*$multiple) + $j+($i*9)+$i])) {
									$qty_c[(10*$multiple) + $j+($i*9)+$i] = ' ';
									$roll_baris--;
								}
								$align = 'R';
								if (strlen($qty_c[(10*$multiple) + $j+($i*9)+$i]) > 3) {
									$align = 'C';
								}
								$pdf->Cell(6, 5, $qty_c[(10*$multiple) + $j+($i*9)+$i],0,0,$align);
								$pdf->Cell(2, 5, ' ',0,0,'R');
								$roll_baris++;
								$qty_baris += $qty_c[(10*$multiple) + $j+($i*9)+$i];
								$last = $j;
							}

							$pdf->Cell( 10, 5, $roll_baris, 'LR', 0, 'C' );
							$pdf->Cell( 20, 5, str_replace('.00', '', number_format($qty_baris,'2','.',',')) , 'LR', 1, 'C' );
						}
						$pdf->Cell(75);
						$pdf->Cell(80,5,'','BR',0,'C');
						$pdf->Cell(10,5,'','LBR',0,'C');
						$pdf->Cell(20,5,'','LBR',1,'C');

					}
					//===========================================================
					$pdf->Text( 2, 125, 'Tanda Terima');
					$pdf->Text( 130, 125, 'Checker');
					$pdf->Text( 160, 125, 'Hormat Kami');

					//=============================================================
				}
				
				$total_roll +=  $jumlah_roll[$key];
				$total += $qty[$key];

				$jml_baris = ceil(($total_baris-14)/20);				

			}else{

				$qty_show = implode(' ', $qty_c);
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

				$pdf->SetFont( $font_name, '', 11 );
				//===========================================================
				$pdf->Text( 2, 125, 'Tanda Terima');
				$pdf->Text( 130, 125, 'Checker');
				$pdf->Text( 160, 125, 'Hormat Kami');

				//=============================================================
			}
		
		}

		$pdf->SetFont( $font_name_bold, '', 12 );
		$pdf->Cell( 135, 5, '', 0, 0, 'C' );
		$pdf->Cell( 20, 5, 'TOTAL', 1, 0, 'C' );
		$pdf->Cell( 10, 5, $total_roll, 1, 0, 'C' );
		$pdf->Cell( 20, 5, str_replace('.00','',number_format($total,'2','.',',')), 1, 1, 'R' );
		$i++;

		$pdf->Ln(5);

	}

	

	$pdf->Output( 'Penjualan_Detail.pdf', "I" );
?>