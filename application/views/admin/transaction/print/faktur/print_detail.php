<?
	
	unset($total_satuan);
	
	foreach ($data_penjualan_detail as $row) {
		# code...
		$total+=$row->harga_jual*$row->qty;
		if (!isset($total_satuan[$row->satuan_id]['nama_satuan'])) {
			$total_satuan[$row->satuan_id]['qty'] = $row->qty;
			$total_satuan[$row->satuan_id]['roll'] = $row->jumlah_roll;
			$total_satuan[$row->satuan_id]['nama_satuan'] = $row->nama_satuan;
		}else{
			$total_satuan[$row->satuan_id]['qty'] += $row->qty;
			$total_satuan[$row->satuan_id]['roll'] += $row->jumlah_roll;
		}
	}

	$page_index = 1;

	if ($total_baris_warna + $total_baris_faktur > 12 && $tipe != 2) {
		$pdf->AddPage();
		$pdf->SetMargins(3,0,3);
		$pdf->SetTextColor( 0,0,0 );

		$pdf->setY(6.5);
		$page_index++;
		$pdf->SetFont( $font_name, '', 9 );
		$xFooter = 195;
		$yFooter = 130;
		$pdf->Text($xFooter,$yFooter, $pdf->PageNo().' / {nb}');
		//=============================================================
	}

	
	$pdf->SetFont( $font_name, '', 9 );
	//baris 6
	$pdf->Cell( 4, 6, '', "TLB", 0, 'C' );
	$pdf->Cell( 37, 6, 'KODE', "TLB", 0, 'C' );
	$pdf->Cell( 29, 6, 'WARNA', "TLB", 0, 'C' );
	$pdf->Cell( 8, 6, 'UNIT', "TLB", 0, 'C' );
	$pdf->Cell( 7, 6, 'ROLL', "TLB", 0, 'C' );
	$pdf->Cell( 14, 6, 'TOTAL', "TLB", 0, 'C' );
	$pdf->Cell( 101, 6, 'DETAIL ', 1, 1, 'C' );
	// $pdf->Cell( 0, 0, '', 1, 1, 'R' );
	

	$no = 0;
	$baris_marker = 0 ;
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
			if (is_posisi_id() == 1) {
				// echo $baris.'<br/>';
				if ($baris_marker + $baris > 26 && $page_index > 1) {
					$pdf->Line(3,$pdf->getY(), 203, $pdf->getY());
					$pdf->AddPage();
					$pdf->SetMargins(3,0,3);
					$pdf->SetTextColor( 0,0,0 );

					$pdf->setY(6.5);
					$baris_marker = $baris;
					$page_index++;
					$pdf->SetFont( $font_name, '', 9 );
					$xFooter = 195;
					$yFooter = 130;
					$pdf->Text($xFooter,$yFooter, $pdf->PageNo().' / {nb}');
					//=============================================================
				}else if($baris_marker + $baris > 20 && $page_index == 1){
					$pdf->Line(3,$pdf->getY(), 203, $pdf->getY());
					$pdf->AddPage();
					$pdf->SetMargins(3,0,3);
					$pdf->SetTextColor( 0,0,0 );

					$pdf->setY(6.5);
					$baris_marker = $baris;
					$page_index++;
				}else{
					$baris_marker += $baris;
					
				}
				
			}
			for ($h=0; $h < $baris; $h++) { 
				if ($h==0) {

					if (strlen($row->nama_barang) > 20 || strlen($value) > 17) {
						$pdf->SetFont( $font_name, '', 7 );
					}else{
						$pdf->SetFont( $font_name, '', 8 );
					}
					$pdf->Cell( 4, 4, $no, "LT", 0, 'C' );
					$pdf->Cell( 1, 4,'','LT');
					$pdf->Cell( 36, 4, strtoupper($row->nama_barang), "T", 0, 'L' );
					// $pdf->Cell( 36, 4, "HEAVY TASLAN MILKY GRADE B", "T", 0, 'L' );
					$pdf->Cell( 1, 4,'','LT');
					$pdf->Cell( 28, 4, strtoupper($value), "T", 0, 'L' );
					// $pdf->Cell( 28, 4, "LORENG UNI DK GREEN", "T", 0, 'L' );
					$pdf->Cell( 8, 4, strtoupper($row->nama_satuan), "LT", 0, 'C' );
					$pdf->Cell( 7, 4, $jumlah_roll[$key], "LT", 0, 'C' );
					$pdf->Cell( 13, 4, str_replace(",00", "", number_format($qty[$key],'2',',','.') ) , "LT", 0, 'R' );
					$pdf->Cell( 1, 4, "", "RT", 0, 'LT' );
				}else{
					$pdf->Cell( 4, 4, "", "L", 0, 'C' );
					$pdf->Cell( 1, 4,'','L');
					$pdf->Cell( 36, 4, "", 0, 0, 'L' );
					$pdf->Cell( 1, 4,'','L');
					$pdf->Cell( 28, 4, "", 0, 0, 'L' );
					$pdf->Cell( 8, 4, '', "L", 0, 'C' );
					$pdf->Cell( 7, 4, "", "L", 0, 'C' );
					$pdf->Cell( 13, 4, "", "L", 0, 'R' );
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
	$pdf->SetFont( $font_name, '', 10 );
	$t_idx = 0;
	foreach ($total_satuan as $key => $value) {
		/*$border = ($t_idx == 0 ? "T" : 0  );
		$pdf->Cell( 41, 5, "", $border, 0, 'L' );
		$pdf->Cell( 25, 5, ($t_idx == 0 ? "TOTAL" : ""), $border, 0, 'R' );

		$border = ($t_idx == 0 ? "T" : 0  );
		$pdf->Cell( 1, 5, "", $border, 0, 'R' );

		$border = ($t_idx == 0 ? "LTB" : "LB"  );
		$pdf->Cell( 10, 5, strtoupper($value['nama_satuan']), $border, 0, 'C' );
		$pdf->Cell( 7, 5, $value['roll'], $border, 0, 'C' );
		$pdf->Cell( 14, 5, str_replace(",00", "", number_format($value['qty'],'2',',','.' )), $border, 0, 'R' );
		
		$border = ($t_idx == 0 ? "TB" : "B"  );
		$pdf->Cell( 1, 5, "", $border, 0, 'LT' );
		$border = ($t_idx == 0 ? "LT" : "L"  );
		$pdf->Cell( 101, 5, "", $border, 1 );
		$t_idx++;*/
	}

		$border = "T";
		$pdf->Cell( 200, 1, "", $border, 0, 'L' );

	/**
	=====================================================================
	**/

	/**
	=====================================================================
	**/