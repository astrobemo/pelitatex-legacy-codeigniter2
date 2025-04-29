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


$objPHPExcel->setActiveSheetIndex(0)
->setCellValue('A1', ' LAPORAN STOK OPNAME BARANG ')
->setCellValue('A2', ' Periode '.date('F Y', strtotime($tanggal_start)).' s/d '.date('F Y', strtotime($tanggal_end)))
->setCellValue('A4', 'NO')
->setCellValue('B4', 'NO SO')
->setCellValue('C4', "TANGGAL")
->setCellValue('D4', "LOKASI")
->setCellValue('E4', "BARANG")
->setCellValue('F4', "QTY")
->setCellValue('G4', "ROLL")
->setCellValue('H4', "PENYESUAIAN")
->setCellValue('I4', 'PETUGAS')
;

// $objPHPExcel->getActiveSheet()->getStyle('J4:K4')->getAlignment()->setWrapText(true); 
$objPHPExcel->getActiveSheet()->getStyle('A1:A2')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->getStyle('A4:L4')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->getStyle("A4:L4")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$data_overview = array();

$row_no = 5;
$idx = 1;

foreach ($so_list as $row) {

    $tanggal_so = explode(',', $row->tanggal_so);
    $nama_barang = explode(',', $row->nama_barang);
    $nama_warna = explode(',', $row->nama_warna);
    $nama_gudang = explode(',', $row->nama_gudang);
    $stok_opname_id = explode(',', $row->stok_opname_id);
    $qty = explode(',', $row->qty_data);
    $jumlah_roll = explode(',', $row->jumlah_roll_data);
    $qty_penyesuaian = explode(',', $row->qty_data_penyesuaian);

    $coll = 'A';

    $row_awal = $row_no;
    $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$idx);
    $coll++;

    $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$row->no_so);
    $coll++;
    
    $coll = 'I';
    $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$row->keterangan);
    
    foreach ($tanggal_so as $key => $value) {
        $coll = 'C';
        $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$value);
        $coll++;
        
        $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$nama_gudang[$key]);
        $coll++;
        
        $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$nama_barang[$key].' '.$nama_warna[$key]);
        $coll++;
        
        $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$qty[$key]);
        $coll++;
        
        $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$jumlah_roll[$key]);
        $coll++;

        $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$qty_penyesuaian[$key]);
        $coll++;

        $row_akhir = $row_no;
        $row_no++;
        
    }

    $objPHPExcel->getActiveSheet()->mergeCells("A".$row_awal.":A".$row_akhir);
    $objPHPExcel->getActiveSheet()->mergeCells("B".$row_awal.":B".$row_akhir);
    $objPHPExcel->getActiveSheet()->mergeCells("I".$row_awal.":I".$row_akhir);

    if ($idx % 2 == 0) {
        $objPHPExcel->getActiveSheet()->getStyle("A".$row_awal.":I".$row_akhir)->applyFromArray($styleArrayBG);
    }

        
    // $objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
    // $objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    // $row_no++;
    $idx++;
}


$objPHPExcel->getActiveSheet()->getColumnDimension("A")->setWidth(10);
$objPHPExcel->getActiveSheet()->getColumnDimension("B")->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension("C")->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension("D")->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension("E")->setWidth(30);
$objPHPExcel->getActiveSheet()->getColumnDimension("F")->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension("G")->setWidth(10);
$objPHPExcel->getActiveSheet()->getColumnDimension("H")->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension("I")->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(20);
$objPHPExcel->getActiveSheet()->getStyle("D4:D".$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle("E4:E".$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$objPHPExcel->getActiveSheet()->getStyle("A5:B".$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle("A5:B".$row_no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle("I5:I".$row_no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle("I5:I".$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle("B5:B".$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

// $objPHPExcel->getActiveSheet()->getStyle("D5:D".$row_no)->applyFromArray($styleArrayBG);
// $objPHPExcel->getActiveSheet()->getStyle("E5:F".$row_no)->applyFromArray($styleArrayBG0);
// $objPHPExcel->getActiveSheet()->getStyle("G5:H".$row_no)->applyFromArray($styleArrayBG1);    
// $objPHPExcel->getActiveSheet()->getStyle("I5:K".$row_no)->applyFromArray($styleArrayBG2);
// $objPHPExcel->getActiveSheet()->getStyle("L5:L".$row_no)->applyFromArray($styleArrayBG3);

$coll = 'C';
    



$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
ob_end_clean();


header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment;filename=Laporan_SO.xls");
header('Cache-Control: max-age=0');
$objWriter->save('php://output');
?>