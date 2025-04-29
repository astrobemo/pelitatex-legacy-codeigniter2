<?    /** Caching to discISAM*/
		

        $begin = new DateTime($tanggal_start);
        $end = new DateTime($tanggal_end);

        $interval = DateInterval::createFromDateString('1 month');
        $period = new DatePeriod($begin, $interval, $end);

        $arr1 = [0,1,2,3,4];
        $arr2 = [5,6,7,8,9];
        
		$styleArray = array(
			'font'=>array(
				'bold'=>true,
				'size'=>12,
				)
			);

		$styleArrayBG = array(
			'fill' => array(
		            'type' => PHPExcel_Style_Fill::FILL_SOLID,
		            'color' => array('rgb' => 'FFFF00')
		        )
			);

		$index = 0;
		$tgl_list = [];
		foreach ($period as $dt) {
			// echo $dt->format("Y-m") . "<br>\n";

			array_push($tgl_list, $dt->format("Y-m"));
			$objPHPExcel->getActiveSheet()->mergeCells("A1:M1");
			$objPHPExcel->getActiveSheet()->mergeCells("A2:M2");
	
			if ($index > 0) {
				$objPHPExcel->createSheet(1);
			}

			$objPHPExcel->getActiveSheet()->setTitle($dt->format("F Y"));

			$objPHPExcel->setActiveSheetIndex($index)
			->setCellValue('A1', ' LAPORAN PENJUALAN '.$nama_customer)
			->setCellValue('A2', ' Periode '.$dt->format("F Y"))
			->setCellValue('A4', 'No')
			->setCellValue('B4', 'No Faktur')
			->setCellValue('C4', 'Tanggal')
			->setCellValue('D4', 'Qty')
			->setCellValue('E4', 'Jumlah Roll')
			->setCellValue('F4', 'Gudang')
			->setCellValue('G4', 'Nama Barang')
			->setCellValue('H4', 'Nama Jual')
			->setCellValue('I4', 'Harga Jual')
			->setCellValue('J4', 'Total')
			// ->setCellValue('J4', 'Diskon')
			// ->setCellValue('J4', 'Ongkos Kirim')
			->setCellValue('K4', 'Nama Customer')
			->setCellValue('L4', 'Keterangan')
			;
	
			$coll_now = "M";
			foreach ($tipe_bayar as $row2) {
				$objPHPExcel->getActiveSheet()->setCellValue($coll_now."4",$row2->nama);
				$coll_now++;
			}
	
			$objPHPExcel->getActiveSheet()->getStyle('A1:'.$coll_now.'4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyle('A1:'.$coll_now.'4')->applyFromArray($styleArray);
			$index++;
		}	

		// foreach ($this->satuan_list_aktif as $row) {
		// 	${'g_total_'.$row->id} = 0;
		// 	${'g_returtotal_'.$row->id} = 0;
		// 	${'idx_'.$row->id} = 0;
		// 	${'yard_total_'.$row->id} = 0;
		// 	${'roll_total_'.$row->id} = 0;
		// 	${'yard_returtotal_'.$row->id} = 0;
		// 	${'roll_returtotal_'.$row->id} = 0;
		// }
		// $idx = 1; $row_no = 5; $g_total = 0;
		// $yard_total = 0;
		// $roll_total = 0;
		// $row_jual_last = 6;

		// $total_lunas = 0;
		// $total_kontra = 0;
		// $total_belum_lunas = 0;


		$index = 0;
		foreach ($penjualan_list as $row) {
			$blnThn = date('Y-m',strtotime($row->tanggal));

			while ($tgl_list[$index] != $blnThn ) {
				$objPHPExcel->setActiveSheetIndex($index);
				foreach ($this->satuan_list_aktif as $row) {
					${'g_total_'.$row->id} = 0;
					${'g_returtotal_'.$row->id} = 0;
					${'idx_'.$row->id} = 0;
					${'yard_total_'.$row->id} = 0;
					${'roll_total_'.$row->id} = 0;
					${'yard_returtotal_'.$row->id} = 0;
					${'roll_returtotal_'.$row->id} = 0;
				}
				$idx = 1; $row_no = 5; $g_total = 0;
				$yard_total = 0;
				$roll_total = 0;
				$row_jual_last = 6;
		
				$total_lunas = 0;
				$total_kontra = 0;
				$total_belum_lunas = 0;
				$index++;
			}

			$total = array();

			$qty = explode('??', $row->qty);
			$harga_jual = explode('??', $row->harga_jual);
			$jumlah_roll = explode('??', $row->jumlah_roll);
			$nama_gudang = explode('??', $row->nama_gudang);
			$nama_barang = explode('??', $row->nama_barang);
			$nama_jual = explode('??', $row->nama_jual);
			$satuan_id = explode('??', $row->satuan_id);
			$count = count($qty);
			// $g_total = 0;


			$coll = "A";
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$idx);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(7);
			$coll++;

			// echo $coll.$row_no.':'.$coll.$row_end.'--'.$isi.'<br>';
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$row->no_faktur);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(20);
			$coll++;

			$tanggal = date('d-m-Y',strtotime($row->tanggal));
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$tanggal);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;

			$coll_start = $coll;
			$row_start = $row_no;
			$sub_total = 0;

			foreach ($harga_jual as $key => $value) {
				$coll = $coll_start;
				$yard_total += $qty[$key];
				$roll_total += $jumlah_roll[$key];
				${'yard_total_'.$satuan_id[$key]} += $qty[$key];
				if ($key != 3) {
					${'roll_total_'.$satuan_id[$key]} += $jumlah_roll[$key];
				}

				// $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, str_replace("??","\n",$row->qty));
				$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $qty[$key]);				
				// $objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setWrapText(true);
				$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
				$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(10);
				$coll++;

				// $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, str_replace('??',"\n",$row->jumlah_roll));
				$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $jumlah_roll[$key]);
				// $objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setWrapText(true);
				$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
				$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(12);
				$coll++;

				$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $nama_gudang[$key]);				
				$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
				$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(20);
				$coll++;

				$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $nama_barang[$key]);				
				$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
				$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(30);
				$coll++;

				// $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, str_replace('??',"\n",$row->nama_jual));
				$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $nama_jual[$key]);				
				// $objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setWrapText(true);
				$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
				$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(30);
				$coll++;

				// $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, str_replace('??',"\n",$row->harga_jual));
				$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $harga_jual[$key]);				
				// $objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setWrapText(true);
				$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
				$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
				$coll++;

				$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $qty[$key] * $harga_jual[$key]);
				// $objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setWrapText(true);
				$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
				$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
				$coll++;

				if ($key != $count -1) {
					$row_no++;
				}
				$sub_total += $qty[$key] * $harga_jual[$key];
				$g_total += $qty[$key] * $harga_jual[$key];
				${'g_total_'.$satuan_id[$key]} += $qty[$key] * $harga_jual[$key];
			}

			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_start, $row->nama_customer);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_start)->getAlignment()->setWrapText(true);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_start)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_start)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(30);
			$coll++;

			$status = '';
			if ($row->ket_lunas == 'belum lunas') {
				$total_belum_lunas += $sub_total;
				$status = "belum lunas";
			}elseif ($row->ket_lunas == 'lunas1' || $row->ket_lunas == 'lunas' ) {
				$total_lunas += $sub_total;
				$status = 'lunas';
			}else{
				$total_kontra += $sub_total;
				$status = 'kontra bon';
			}
 

			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_start, $status);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_start)->getAlignment()->setWrapText(true);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_start)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_start)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(30);
			$coll++;

			$pembayaran_type_id = explode(',', $row->pembayaran_type_id);
			$data_bayar = explode(',', $row->data_bayar);
			$bayar = array_combine($pembayaran_type_id, $data_bayar);
			$total_except_cash = 0;

			foreach ($bayar as $key => $value) {
				if ($key != 2 && $key != 5 && $key != 6) {
					$total_except_cash += $value;
				}
			}

			foreach ($tipe_bayar as $row2) {
				if (isset($bayar[$row2->id])) {
					$temp = $total_except_cash - $sub_total;
					if ($row2->id == 2 && $temp > 0) {								
						$value = $bayar[$row2->id] - $temp;
					}else{
						$value = str_replace("??", "\n", $bayar[$row2->id]);
					}
					$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_start, $value);
					$objPHPExcel->getActiveSheet()->getStyle($coll.$row_start)->getAlignment()->setWrapText(true);
					$objPHPExcel->getActiveSheet()->getStyle($coll.$row_start)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
					$objPHPExcel->getActiveSheet()->getStyle($coll.$row_start)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
				}

				if ($row2->id == 2 && !isset($bayar[2]) && $total_except_cash != $sub_total && $total_except_cash != 0) {
					$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_start, $sub_total - $total_except_cash);
					$objPHPExcel->getActiveSheet()->getStyle($coll.$row_start)->getAlignment()->setWrapText(true);
					$objPHPExcel->getActiveSheet()->getStyle($coll.$row_start)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
					$objPHPExcel->getActiveSheet()->getStyle($coll.$row_start)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
				}
				$coll++;
			}

			$idx++;
			$last_row = $row_no;
			$row_no++;

			$objPHPExcel->getActiveSheet()->setCellValue('I'.$row_no, "SUBTOTAL");
			$objPHPExcel->getActiveSheet()->getStyle("I".$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

			$objPHPExcel->getActiveSheet()->setCellValue('J'.$row_no, $sub_total);
			$objPHPExcel->getActiveSheet()->getStyle("J".$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);

			$objPHPExcel->getActiveSheet()->getStyle('I'.$row_no.':J'.$row_no)->applyFromArray($styleArray);
			
			$row_jual_last = $row_no;

			$row_no++;			
			$row_no++;			
		}

		$row_no++;
		if (count($retur_list) > 0) {
			$objPHPExcel->getActiveSheet()->setCellValue("A".$row_no,'RETUR');
			$objPHPExcel->getActiveSheet()->getStyle('A'.$row_no.':L'.$row_no)->applyFromArray($styleArrayBG);
			$row_no++;
			$row_no++;
		
		}

		$yard_retur_total = 0;
		$roll_retur_total = 0;
		$g_retur_total = 0;

		// $objPHPExcel->getActiveSheet()->setTitle('Rit 1');

		//============================================================================================
		foreach ($retur_list as $row) {
			$total = array();

			$qty = explode('??', $row->qty);
			$harga_jual = explode('??', $row->harga_jual);
			$jumlah_roll = explode('??', $row->jumlah_roll);
			$nama_gudang = explode('??', $row->nama_gudang);
			$nama_barang = explode('??', $row->nama_barang);
			$nama_jual = explode('??', $row->nama_jual);
			$satuan_id = explode('??', $row->satuan_id);
			$count = count($qty);
			// $g_total = 0;


			$coll = "A";
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$idx);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(7);
			$coll++;

			// echo $coll.$row_no.':'.$coll.$row_end.'--'.$isi.'<br>';
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$row->no_faktur);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(20);
			$coll++;

			$tanggal = date('d-m-Y',strtotime($row->tanggal));
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$tanggal);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;

			$coll_start = $coll;
			$row_start = $row_no;
			$sub_total = 0;

			foreach ($harga_jual as $key => $value) {
				$coll = $coll_start;
				$yard_retur_total += $qty[$key];
				$roll_retur_total += $jumlah_roll[$key];
				${'yard_returtotal_'.$satuan_id[$key]} += $qty[$key];
				${'roll_returtotal_'.$satuan_id[$key]} += $jumlah_roll[$key];

				// $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, str_replace("??","\n",$row->qty));
				$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $qty[$key]);				
				// $objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setWrapText(true);
				$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
				$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(10);
				$coll++;

				// $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, str_replace('??',"\n",$row->jumlah_roll));
				$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $jumlah_roll[$key]);
				// $objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setWrapText(true);
				$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
				$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(12);
				$coll++;

				$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $nama_gudang[$key]);				
				$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
				$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(20);
				$coll++;

				$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $nama_barang[$key]);				
				$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
				$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(30);
				$coll++;

				// $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, str_replace('??',"\n",$row->nama_jual));
				$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $nama_jual[$key]);				
				// $objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setWrapText(true);
				$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
				$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(30);
				$coll++;

				// $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, str_replace('??',"\n",$row->harga_jual));
				$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $harga_jual[$key]);				
				// $objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setWrapText(true);
				$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
				$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
				$coll++;			
				$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $qty[$key] * $harga_jual[$key]);
				// $objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setWrapText(true);
				$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
				$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
				$coll++;

				if ($key != $count -1) {
					$row_no++;
				}
				$sub_total += $qty[$key] * $harga_jual[$key];
				$g_retur_total += $qty[$key] * $harga_jual[$key];
				${'g_returtotal_'.$satuan_id[$key]} += $qty[$key] * $harga_jual[$key];
			}

			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_start, $row->nama_customer);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_start)->getAlignment()->setWrapText(true);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_start)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_start)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(30);
			$coll++;

			$status = '';
			// if ($row->keterangan < 0) {
			// $status = 'belum lunas';
			// }else if ($row->keterangan >= 0){
			// 	$status = 'lunas';
			// } 

			if ($row->ket_lunas == 'belum lunas') {
				$status = "belum lunas";
			}elseif ($row->ket_lunas == 'lunas1') {
				$status = 'lunas';
			}else{
				$status = 'kontra bon';
			}

			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_start, $status);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_start)->getAlignment()->setWrapText(true);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_start)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_start)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(30);
			$coll++;

			$pembayaran_type_id = explode(',', $row->pembayaran_type_id);
			$data_bayar = explode(',', $row->data_bayar);
			$bayar = array_combine($pembayaran_type_id, $data_bayar);

			foreach ($tipe_bayar as $row2) {
				if (isset($bayar[$row2->id])) {
					$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_start, $bayar[$row2->id]);
					$objPHPExcel->getActiveSheet()->getStyle($coll.$row_start)->getAlignment()->setWrapText(true);
					$objPHPExcel->getActiveSheet()->getStyle($coll.$row_start)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
					$objPHPExcel->getActiveSheet()->getStyle($coll.$row_start)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
				}
				$coll++;
			}

			$idx++;
			$last_row = $row_no;
			$row_no++;

			$objPHPExcel->getActiveSheet()->setCellValue('I'.$row_no, "SUBTOTAL");
			$objPHPExcel->getActiveSheet()->getStyle("I".$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

			$objPHPExcel->getActiveSheet()->setCellValue('J'.$row_no, $sub_total);
			$objPHPExcel->getActiveSheet()->getStyle("J".$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);

			$objPHPExcel->getActiveSheet()->getStyle('I'.$row_no.':J'.$row_no)->applyFromArray($styleArray);

			$row_no++;
			$row_no++;			
			
		}

		//=======================================================================================
				
		$row_start = $row_no;
		$coll = "M";
		foreach ($tipe_bayar as $row2) {
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_start, "=SUM(".$coll."5:".$coll.$row_jual_last.")");
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_start)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_start)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;
		}

		$objPHPExcel->getActiveSheet()->setCellValue('B'.$row_no, 'Penjualan');
		$objPHPExcel->getActiveSheet()->setCellValue('D'.$row_no, $yard_total);
		$objPHPExcel->getActiveSheet()->setCellValue('E'.$row_no, $roll_total);
		$objPHPExcel->getActiveSheet()->setCellValue('J'.$row_no, $g_total);
		// $objPHPExcel->getActiveSheet()->setCellValue('I'.$row_no, $g_total);
		$objPHPExcel->getActiveSheet()->getStyle('A'.$row_no.':J'.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('A'.$row_no.':J'.$row_no)->applyFromArray($styleArray);
		$row_no++;

		if (count($retur_list) > 0) {
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$row_no, 'Retur');
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$row_no, $yard_retur_total);
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$row_no, $roll_retur_total);
			$objPHPExcel->getActiveSheet()->setCellValue('J'.$row_no, $g_retur_total);
			// $objPHPExcel->getActiveSheet()->setCellValue('I'.$row_no, $g_total);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$row_no.':J'.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$row_no.':J'.$row_no)->applyFromArray($styleArray);
			$row_no++;

			$objPHPExcel->getActiveSheet()->setCellValue('B'.$row_no, 'TOTAL');
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$row_no, $yard_total - $yard_retur_total);
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$row_no, $roll_total - $roll_retur_total);
			$objPHPExcel->getActiveSheet()->setCellValue('J'.$row_no, $g_total - $g_retur_total);
			// $objPHPExcel->getActiveSheet()->setCellValue('I'.$row_no, $g_total);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$row_no.':J'.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$row_no.':J'.$row_no)->applyFromArray($styleArray);
		}

		$row_no++;

		$objPHPExcel->getActiveSheet()->setCellValue('B'.$row_no, 'TOTAL JUAL');
		$objPHPExcel->getActiveSheet()->setCellValue('C'.$row_no, $g_total);
		$row_no++;
		$objPHPExcel->getActiveSheet()->setCellValue('B'.$row_no, 'TOTAL LUNAS');
		$objPHPExcel->getActiveSheet()->setCellValue('C'.$row_no, $total_lunas);
		$row_no++;
		$objPHPExcel->getActiveSheet()->setCellValue('B'.$row_no, 'TOTAL KONTRA');
		$objPHPExcel->getActiveSheet()->setCellValue('C'.$row_no, $total_kontra);
		$row_no++;
		$objPHPExcel->getActiveSheet()->setCellValue('B'.$row_no, 'TOTAL BELUM LUNAS');
		$objPHPExcel->getActiveSheet()->setCellValue('C'.$row_no, $total_belum_lunas);
		$row_no++;


		// $rmov_char = [',','.'];
		// echo "Laporan_Penjualan_".str_replace($rmov_char, '', $nama_customer_raw).' '.date("dmY",strtotime($tanggal_start))."sd_".date("dmY",strtotime($tanggal_end)).".xls";
		// $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		// ob_end_clean();


		// header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		// header("Content-Disposition: attachment;filename=Laporan_Penjualan_".date("dmY",strtotime($tanggal_start))."sd_".date("dmY",strtotime($tanggal_end)).".xls");
		// header('Cache-Control: max-age=0');
		// $objWriter->save('php://output');

        ?>