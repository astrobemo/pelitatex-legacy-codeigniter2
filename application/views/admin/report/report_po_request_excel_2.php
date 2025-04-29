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

$styleArrayBG = array(
    'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'eeeeee')
        )
    );

$styleArrayBG0 = array(
        'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => 'e6faea')
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
$sheet->setTitle("LAPORAN");

$objPHPExcel->getActiveSheet()->mergeCells("A1:M1");
$objPHPExcel->getActiveSheet()->mergeCells("A2:M2");

$objPHPExcel->getActiveSheet()->mergeCells("A4:A5");
$objPHPExcel->getActiveSheet()->mergeCells("B4:C5");
$objPHPExcel->getActiveSheet()->mergeCells("D4:H4");
$objPHPExcel->getActiveSheet()->mergeCells("I4:K4");
$objPHPExcel->getActiveSheet()->mergeCells("L4:L5");



$objPHPExcel->setActiveSheetIndex(0)
->setCellValue('A1', ' LAPORAN REQUEST BARANG '.$no_request_lengkap)
->setCellValue('A2', ' Periode '. $bulan_awal)
->setCellValue('A4', 'NO')
->setCellValue('B4', 'NAMA BARANG')
->setCellValue('D4', strtoupper($bulan_awal))
->setCellValue('I4', 'SELANJUTNYA')
->setCellValue('L4', 'NON REQUEST')
;

$objPHPExcel->setActiveSheetIndex(0)
->setCellValue('D5', 'REQUEST')
->setCellValue('E5', 'DATANG')
->setCellValue('F5', '%')
->setCellValue('G5', 'ON REQUEST')
->setCellValue('H5', '%')
->setCellValue('I5', 'REQUEST')
->setCellValue('J5', 'DATANG')
->setCellValue('K5', '%')
;

$objPHPExcel->getActiveSheet()->getStyle('J4:K4')->getAlignment()->setWrapText(true); 
$objPHPExcel->getActiveSheet()->getStyle('A1:A2')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->getStyle("A4:L5")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle("A4:L5")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

$data_overview = array();

$row_no = 6;
$idx = 1;

foreach (json_decode($data_barang) as $row) {
    $coll = 'A';

    $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$idx);
    $coll++;

    
    $objPHPExcel->getActiveSheet()->mergeCells("B".$row_no.":C".$row_no);
    $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$row->nama_barang);
    $objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
    $objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(20);
    $coll++;
    $coll++;

    $col_req = $coll;
    $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$row->request_awal);
    $objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(10);
    $coll++;

    $col_total_dtg = $coll;
    $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,"=G".$row_no."+J".$row_no."+L".$row_no);
    $objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(10);
    $coll++;

    $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,"=ROUND(IF(".$col_req.$row_no.">0,".$col_total_dtg.$row_no."/".$col_req.$row_no.",0),4)*100");
    $objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(10);
    $coll++;

    $col_dtg = $coll;
    $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$row->datang_awal);
    $objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(10);
    $coll++;

    $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,"=ROUND(IF(".$col_req.$row_no.">0,".$col_dtg.$row_no."/".$col_req.$row_no.",0),4)*100");
    $objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(10);
    $coll++;

    $col_req_lain = $coll;
    $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$row->request_lain);
    $objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(10);
    $coll++;

    $col_dtg_lain = $coll;
    $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$row->datang_lain);
    $objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(10);
    $coll++;

    $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,"=ROUND(IF(".$col_req_lain.$row_no.">0,".$col_dtg_lain.$row_no."/".$col_req_lain.$row_no.",0),4)*100");
    $objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(10);
    $coll++;

    $col_nr = $coll;
    $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$row->datang_nr);
    $objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(10);
    $coll++;

    $row_no_end = $row_no;
    $last_coll = $coll;
    $row_no++;
    $idx++;
}

$objPHPExcel->getActiveSheet()->getStyle("D5:D".$row_no)->applyFromArray($styleArrayBG);
$objPHPExcel->getActiveSheet()->getStyle("E5:F".$row_no)->applyFromArray($styleArrayBG0);
$objPHPExcel->getActiveSheet()->getStyle("G5:H".$row_no)->applyFromArray($styleArrayBG1);    
$objPHPExcel->getActiveSheet()->getStyle("I5:K".$row_no)->applyFromArray($styleArrayBG2);
$objPHPExcel->getActiveSheet()->getStyle("L5:L".$row_no)->applyFromArray($styleArrayBG3);

$coll = 'C';
    
$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,"TOTAL");
$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$coll++;

$col_req = $coll;
$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,"=SUM(".$coll."6:".$coll.$row_no_end.")");
$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$coll++;

$col_total_dtg = $coll;
$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,"=SUM(".$coll."6:".$coll.$row_no_end.")");
$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$coll++;

$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,"=ROUND(IF(".$col_req.$row_no.">0,".$col_total_dtg.$row_no."/".$col_req.$row_no.",0),4)*100");
$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$coll++;

$col_dtg = $coll;
$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,"=SUM(".$coll."6:".$coll.$row_no_end.")");
$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$coll++;

$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,"=ROUND(IF(".$col_req.$row_no.">0,".$col_dtg.$row_no."/".$col_req.$row_no.",0),4)*100");
$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$coll++;

$col_req = $coll;
$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,"=SUM(".$coll."6:".$coll.$row_no_end.")");
$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$coll++;

$col_dtg = $coll;
$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,"=SUM(".$coll."6:".$coll.$row_no_end.")");
$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$coll++;

$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,"=ROUND(IF(".$col_req_lain.$row_no.">0,".$col_dtg.$row_no."/".$col_req.$row_no.",0),4)*100");
$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$coll++;

$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,"=SUM(".$coll."6:".$coll.$row_no_end.")");
$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$coll++;


$objPHPExcel->getActiveSheet()->getStyle('A4:L5')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->getStyle('C'.$row_no.':'.$last_coll.$row_no)->applyFromArray($styleArray);


$row_no++;
$objPHPExcel->getActiveSheet()->getStyle('A'.$row_no.':'.$last_coll.$row_no)->applyFromArray($styleArraySplitter);
$objPHPExcel->getActiveSheet()->getRowDimension($row_no)->setRowHeight(5);
$row_no++;

/**
=========================================================================================================================
**/

$row_no = $row_no+6;
$row_warna = $row_no;
$objPHPExcel->getActiveSheet()->mergeCells("A".$row_no.":A".($row_no+1)."");
$objPHPExcel->getActiveSheet()->mergeCells("B".$row_no.":B".($row_no+1)."");
$objPHPExcel->getActiveSheet()->mergeCells("C".$row_no.":C".($row_no+1)."");
$objPHPExcel->getActiveSheet()->mergeCells("D".$row_no.":H".$row_no."");
$objPHPExcel->getActiveSheet()->mergeCells("I".$row_no.":K".$row_no."");
$objPHPExcel->getActiveSheet()->mergeCells("L".$row_no.":L".($row_no+1)."");



$objPHPExcel->setActiveSheetIndex(0)
->setCellValue("A".($row_no)."", "NO")
->setCellValue("B".($row_no)."", "BARANG")
->setCellValue("C".($row_no)."", "WARNA")
->setCellValue("D".($row_no)."", strtoupper($bulan_awal))
->setCellValue("I".($row_no)."", "SELANJUTNYA")
->setCellValue("L".($row_no)."", "NON REQUEST")
;

$objPHPExcel->setActiveSheetIndex(0)
->setCellValue("D".($row_no+1)."", "REQUEST")
->setCellValue("E".($row_no+1)."", "DATANG")
->setCellValue("F".($row_no+1)."", "%")
->setCellValue("G".($row_no+1)."", "ON REQUEST")
->setCellValue("H".($row_no+1)."", "%")
->setCellValue("I".($row_no+1)."", "REQUEST")
->setCellValue("J".($row_no+1)."", "DATANG")
->setCellValue("K".($row_no+1)."", "%")
;

$row_no++;
$row_no++;
$idx = 1;
foreach (json_decode($data_warna) as $row) {
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

        $col_total_dtg = $coll;
        $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,"=G".$row_no."+J".$row_no."+L".$row_no);
        $objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(10);
        $coll++;

        $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,"=ROUND(IF(".$col_total_dtg.$row_no.">0,".$col_total_dtg.$row_no."/".$col_req.$row_no.",0),4)*100");
        $objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(10);
        $coll++;

        $col_dtg = $coll;
        $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$tm->datang_awal);
        $objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(10);
        $coll++;

        $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,"=ROUND(IF(".$col_dtg.$row_no.">0,".$col_dtg.$row_no."/".$col_req.$row_no.",0),4)*100");
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

        $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,"=ROUND(IF(".$col_dtg_lain.$row_no.">0,".$col_dtg_lain.$row_no."/".$col_req_lain.$row_no.",0),4)*100");
        $objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(10);
        $coll++;

        $col_nr = $coll;
        $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$tm->datang_nr_warna);
        $objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(10);
        $last_coll = $coll;
        $coll++;

        // $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,"=".$col_dtg.$row_no."+".$col_dtg_lain.$row_no."+".$col_nr.$row_no);
        // $objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        // $objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(10);
        // $last_coll = $coll;
        // $coll++;

        $row_no_end = $row_no;
        $row_no++;

    }
    
    $objPHPExcel->getActiveSheet()->getStyle('D'.$row_awal.':D'.$row_no)->applyFromArray($styleArrayBG);
    $objPHPExcel->getActiveSheet()->getStyle('E'.$row_awal.':F'.$row_no)->applyFromArray($styleArrayBG0);
    $objPHPExcel->getActiveSheet()->getStyle('G'.$row_awal.':H'.$row_no)->applyFromArray($styleArrayBG1);    
    $objPHPExcel->getActiveSheet()->getStyle('I'.$row_awal.':K'.$row_no)->applyFromArray($styleArrayBG2);
    $objPHPExcel->getActiveSheet()->getStyle('L'.$row_awal.':L'.$row_no)->applyFromArray($styleArrayBG3);
    
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
        $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,"=G".$row_no."+J".$row_no."+L".$row_no);
        // $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$tm->datang_awal);
        $objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(10);
        $coll++;

        $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,"0");
        $objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(10);
        $coll++;

        $col_dtg = $coll;
        $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$tm->datang_awal);
        $objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(10);
        $coll++;

        $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,"=ROUND(IF(".$col_dtg.$row_no.">0,".$col_dtg.$row_no."/".$col_req.$row_no.",0),4)*100");
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

        $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,"=ROUND(IF(".$col_dtg_lain.$row_no.">0,".$col_dtg_lain.$row_no."/".$col_req_lain.$row_no.",0),4)*100");
        $objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(10);
        $coll++;

        $col_nr = $coll;
        $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$tm->datang_nr_warna);
        $objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(10);
        $coll++;

        // $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,"=".$col_dtg.$row_no."+".$col_dtg_lain.$row_no."+".$col_nr.$row_no);
        // $objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        // $objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(10);
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
        
        //total request
        $col_req = $coll;
        $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,"=SUM(".$coll.$row_no_start.":".$coll.$row_no_end.")");
        $objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $coll++;
    
        //total datang
        $col_dtg = $coll;
        $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,"=SUM(".$coll.$row_no_start.":".$coll.$row_no_end.")");
        $objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $coll++;
        
        //persen datang
        $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,"=ROUND(IF(".$col_dtg.$row_no.">0,".$col_dtg.$row_no."/".$col_req.$row_no.",0),4)*100");
        $objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $coll++;
    
        //total datang awal
        $col_dtg_awal = $coll;
        $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,"=SUM(".$coll.$row_no_start.":".$coll.$row_no_end.")");
        $objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $coll++;
    
        //persen datang awal
        $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,"=ROUND(IF(".$col_req.$row_no.">0,".$col_dtg_awal.$row_no."/".$col_req.$row_no.",0),4)*100");
        $objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $coll++;
        
        //total request lain
        $col_req_lain = $coll;
        $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,"=SUM(".$coll.$row_no_start.":".$coll.$row_no_end.")");
        $objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $coll++;
    
        //total datang lain
        $col_dtg_lain = $coll;
        $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,"=SUM(".$coll.$row_no_start.":".$coll.$row_no_end.")");
        $objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $coll++;
    
        //total persen lain
        $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,"=ROUND(IF(".$col_req_lain.$row_no.">0,".$col_dtg_lain.$row_no."/".$col_req_lain.$row_no.",0),4)*100");
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

$objPHPExcel->getActiveSheet()->getStyle("J".$row_warna.":K".$row_warna."")->getAlignment()->setWrapText(true); 
$objPHPExcel->getActiveSheet()->getStyle("A".$row_warna.":L".($row_warna+1)."")->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->getStyle("A".$row_warna.":L".($row_warna+1)."")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle("A".$row_warna.":L".($row_warna+1)."")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);




$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
ob_end_clean();


header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment;filename=Laporan_Request_Barang_$bulan_awal.xls");
header('Cache-Control: max-age=0');
$objWriter->save('php://output');
?>