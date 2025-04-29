<?

		ini_set("memory_limit", "600M");

		/** Caching to discISAM*/
		$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_discISAM;
		$cacheSettings = array('');;

		PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);

		$objPHPExcel = new PHPExcel();

		$styleArray = array(
			'font'=>array(
				'bold'=>true,
				'size'=>12,
				)
			);

		$objPHPExcel->getActiveSheet()->mergeCells("A4:A5");
		$objPHPExcel->getActiveSheet()->mergeCells("B4:B5");
		$objPHPExcel->getActiveSheet()->mergeCells("C4:C5");
		
		
		$objPHPExcel->getActiveSheet()->mergeCells("A1:".$coll_next."1");
		$objPHPExcel->getActiveSheet()->mergeCells("A2:".$coll_next."2");

		$objPHPExcel->getActiveSheet()->setCellValue('A2', ' Tanggal '.is_reverse_date($tanggal_start).' s/d '.is_reverse_date($tanggal_end));
		$objPHPExcel->getActiveSheet()->setCellValue('A3', 'Mutasi Barang : '.$nama_barang." ".$nama_warna);

		
		$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('A4', 'Tanggal')
		->setCellValue('B4', 'Barang')
		->setCellValue('C4', 'Lokasi Sebelum')
		->setCellValue('D4', 'Lokasi Setelah')
		->setCellValue('E4', 'Qty')
		->setCellValue('F4', 'Jumlah Roll')
		;
	

		$row_no = 6;
		$idx = 1;
		foreach ($mutasi_barang_list as $row) {
			$coll = "A";
			
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$idx);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(7);
			$coll++;

			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,is_reverse_date($row->tanggal));
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;


			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$row->nama_barang.' '.$row->nama_warna);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(30);
			$coll++;

			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$row->gudang_before);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;

			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$row->gudang_after);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;

			
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,str_replace('.00', '', $row->qty));
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;

			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$row->jumlah_roll);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;
			$row_no++;
			$idx++;

		}

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

		//ob_end_clean();

		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header("Content-Disposition: attachment;filename=mutasi_barang".date("dmY",strtotime($tanggal_start))."_".date("dmY",strtotime($tanggal_start)).".xlsx");
		header('Cache-Control: max-age=0');
		$objWriter->save('php://output');
?>