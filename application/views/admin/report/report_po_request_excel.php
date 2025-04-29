<?

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

$styleArraySplitter = array(
    'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'dddddd')
        )
    );

$styleArrayBG1 = array(
    'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'bfffc6')
        )
    );

$styleArrayBG2 = array(
    'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'ffffc7')
        )
    );

$styleArrayBG3 = array(
    'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'ffe5e3')
        )
    );


$sheet = $objPHPExcel->getActiveSheet();
$sheet->setTitle("LAPORAN PER WARNA");

$objPHPExcel->getActiveSheet()->mergeCells("A1:M1");
$objPHPExcel->getActiveSheet()->mergeCells("A2:M2");

$objPHPExcel->getActiveSheet()->mergeCells("A4:A5");
$objPHPExcel->getActiveSheet()->mergeCells("B4:B5");
$objPHPExcel->getActiveSheet()->mergeCells("C4:C5");
$objPHPExcel->getActiveSheet()->mergeCells("D4:F4");
$objPHPExcel->getActiveSheet()->mergeCells("G4:I4");
$objPHPExcel->getActiveSheet()->mergeCells("J4:J5");
$objPHPExcel->getActiveSheet()->mergeCells("K4:K5");



$objPHPExcel->setActiveSheetIndex(0)
->setCellValue('A1', ' LAPORAN REQUEST BARANG '.$no_request_lengkap)
->setCellValue('A2', ' Periode '. $bulan_awal)
->setCellValue('A4', 'NO')
->setCellValue('B4', 'BARANG')
->setCellValue('C4', 'WARNA')
->setCellValue('D4', strtoupper($bulan_awal))
->setCellValue('G4', 'SELANJUTNYA')
->setCellValue('J4', 'NON REQUEST')
->setCellValue('K4', 'TOTAL DATANG')
;

$objPHPExcel->setActiveSheetIndex(0)
->setCellValue('D5', 'REQUEST')
->setCellValue('E5', 'DATANG')
->setCellValue('F5', '%')
->setCellValue('G5', 'REQUEST')
->setCellValue('H5', 'DATANG')
->setCellValue('I5', '%')
;

$objPHPExcel->getActiveSheet()->getStyle('J4:K4')->getAlignment()->setWrapText(true); 
$objPHPExcel->getActiveSheet()->getStyle('A1:A2')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->getStyle("A4:J5")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle("A4:J5")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

$data_overview = array();

$row_no = 6;
$idx = 1;
foreach (json_decode($data_barang) as $row) {
    // echo $row->nama_barang;
    // echo '<hr/>';

    $row_no_start = $row_no;

    $row_awal = $row_no;
    $subtotal = 0;

    $total_baris = count($row->data_warna) + count($row->data_nr_warna);
    
    for ($i=0; $i < count($row->data_warna) ; $i++) {
        $tm = $row->data_warna[$i];
        $coll = 'C';
        $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$tm->nama_warna);
        $objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(20);
        $coll++;

        $col_req = $coll;
        $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$tm->request_awal);
        $objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(10);
        $coll++;

        $col_dtg = $coll;
        $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$tm->datang_awal);
        $objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(10);
        $coll++;

        $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,"=ROUND(IF(".$col_dtg.$row_no.">0,".$col_dtg.$row_no."/".$col_req.$row_no.",0),2)*100");
        $objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(10);
        $coll++;

        $col_req_lain = $coll;
        $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$tm->request_lain);
        $objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(10);
        $coll++;

        $col_dtg_lain = $coll;
        $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$tm->datang_lain);
        $objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(10);
        $coll++;

        $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,"=ROUND(IF(".$col_dtg_lain.$row_no.">0,".$col_dtg_lain.$row_no."/".$col_req_lain.$row_no.",0),2)*100");
        $objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(10);
        $coll++;

        $col_nr = $coll;
        $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$tm->datang_nr_warna);
        $objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(10);
        $coll++;

        $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,"=".$col_dtg.$row_no."+".$col_dtg_lain.$row_no."+".$col_nr.$row_no);
        $objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(10);
        $last_coll = $coll;
        $coll++;

        $row_no_end = $row_no;
        $row_no++;

    }
    
    $objPHPExcel->getActiveSheet()->getStyle('D'.$row_awal.':F'.$row_no)->applyFromArray($styleArrayBG1);
    $coll++;

    $objPHPExcel->getActiveSheet()->getStyle('G'.$row_awal.':I'.$row_no)->applyFromArray($styleArrayBG2);
    $coll++;

    $objPHPExcel->getActiveSheet()->getStyle('J'.$row_awal.':J'.$row_no)->applyFromArray($styleArrayBG3);
    $coll++;

    if (count($row->data_nr_warna) > 0) {
        $row_awal = $row_no;
    }
    for ($i=0; $i < count($row->data_nr_warna) ; $i++) {
        $tm = $row->data_nr_warna[$i];
        $coll = 'C';
        $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$tm->nama_warna);
        $objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(20);
        $coll++;

        $col_req = $coll;
        $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$tm->request_awal);
        $objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(10);
        $coll++;

        $col_dtg = $coll;
        $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$tm->datang_awal);
        $objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(10);
        $coll++;

        $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,"=ROUND(IF(".$col_dtg.$row_no.">0,".$col_dtg.$row_no."/".$col_req.$row_no.",0),2)*100");
        $objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(10);
        $coll++;

        $col_req_lain = $coll;
        $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$tm->request_lain);
        $objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(10);
        $coll++;

        $col_dtg_lain = $coll;
        $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$tm->datang_lain);
        $objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(10);
        $coll++;

        $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,"=ROUND(IF(".$col_dtg_lain.$row_no.">0,".$col_dtg_lain.$row_no."/".$col_req_lain.$row_no.",0),2)*100");
        $objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(10);
        $coll++;

        $col_nr = $coll;
        $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$tm->datang_nr_warna);
        $objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(10);
        $coll++;

        $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,"=".$col_dtg.$row_no."+".$col_dtg_lain.$row_no."+".$col_nr.$row_no);
        $objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(10);
        $last_coll = $coll;
        $coll++;

        $row_no_end = $row_no;
        $row_no++;
    }

    if (count($row->data_nr_warna) > 0) {
        $objPHPExcel->getActiveSheet()->getStyle('C'.$row_awal.':'.$last_coll.$row_no_end)->applyFromArray($styleArrayBG3);
        $coll++;
    }

    if ($total_baris > 0) {
        # code...
        $coll = "A";
        
        $objPHPExcel->getActiveSheet()->mergeCells("A".$row_no_start.":A".($row_no_end+1) );
        $objPHPExcel->getActiveSheet()->mergeCells("B".$row_no_start.":B".($row_no_end+1) );
    
        $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no_start,$idx);
        $objPHPExcel->getActiveSheet()->getStyle($coll.$row_no_start)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle($coll.$row_no_start)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $coll++;
    
        $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no_start,$row->nama_barang);
        $objPHPExcel->getActiveSheet()->getStyle($coll.$row_no_start)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle($coll.$row_no_start)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(20);
        $coll++;
    
    
        // $coll = 'C';
    
        $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,"TOTAL");
        $objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $coll++;
        
        $col_req = $coll;
        $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,"=SUM(".$coll.$row_no_start.":".$coll.$row_no_end.")");
        $objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $coll++;
    
        $col_dtg = $coll;
        $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,"=SUM(".$coll.$row_no_start.":".$coll.$row_no_end.")");
        $objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $coll++;
        
        $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,"=ROUND(IF(".$col_dtg.$row_no.">0,".$col_dtg.$row_no."/".$col_req.$row_no.",0),2)*100");
        $objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $coll++;
    
        $col_req = $coll;
        $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,"=SUM(".$coll.$row_no_start.":".$coll.$row_no_end.")");
        $objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $coll++;
    
        $col_dtg = $coll;
        $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,"=SUM(".$coll.$row_no_start.":".$coll.$row_no_end.")");
        $objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $coll++;
    
        $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,"=ROUND(IF(".$col_dtg.$row_no.">0,".$col_dtg.$row_no."/".$col_req.$row_no.",0),2)*100");
        $objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $coll++;
    
        $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,"=SUM(".$coll.$row_no_start.":".$coll.$row_no_end.")");
        $objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $coll++;
    
        $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,"=SUM(".$coll.$row_no_start.":".$coll.$row_no_end.")");
        $objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $coll++;
    
        $objPHPExcel->getActiveSheet()->getStyle('C'.$row_no.':'.$coll.$row_no)->applyFromArray($styleArray);
    
        
        $row_no++;
        $objPHPExcel->getActiveSheet()->getStyle('A'.$row_no.':'.$last_coll.$row_no)->applyFromArray($styleArraySplitter);
        $objPHPExcel->getActiveSheet()->getRowDimension($row_no)->setRowHeight(5);
        $row_no++;
    
        $idx++;
    }

};


$objPHPExcel->createSheet(); //Setting index when creating
$objPHPExcel->setActiveSheetIndex(1);

$sheet = $objPHPExcel->getActiveSheet();
$sheet->setTitle("LAPORAN OVERVIEW");
//Write cells
$objPHPExcel->getActiveSheet()->mergeCells("A4:A5");
$objPHPExcel->getActiveSheet()->mergeCells("B4:B5");
$objPHPExcel->getActiveSheet()->mergeCells("C4:C5");
$objPHPExcel->getActiveSheet()->mergeCells("D4:F4");
$objPHPExcel->getActiveSheet()->mergeCells("G4:I4");
$objPHPExcel->getActiveSheet()->mergeCells("J4:J5");
$objPHPExcel->getActiveSheet()->mergeCells("K4:K5");



$objPHPExcel->setActiveSheetIndex(1)
->setCellValue('A4', 'NO')
->setCellValue('B4', 'BARANG')
->setCellValue('C4', 'WARNA')
->setCellValue('D4', strtoupper($bulan_awal))
->setCellValue('G4', 'SELANJUTNYA')
->setCellValue('J4', 'NON REQUEST')
->setCellValue('K4', 'TOTAL DATANG')
->setCellValue('D5', 'REQUEST')
->setCellValue('E5', 'DATANG')
->setCellValue('F5', '%')
->setCellValue('G5', 'REQUEST')
->setCellValue('H5', 'DATANG')
->setCellValue('I5', '%')
;

$objPHPExcel->getActiveSheet()->getStyle('J4:K4')->getAlignment()->setWrapText(true); 
$objPHPExcel->getActiveSheet()->getStyle('A1:A2')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->getStyle("A4:J5")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle("A4:J5")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);




$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
ob_end_clean();


header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment;filename=Laporan_Request_Barang_$bulan_awal.xls");
header('Cache-Control: max-age=0');
$objWriter->save('php://output');
?>