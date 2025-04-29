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
            'color' => array('rgb' => 'f7b7b2')
        )
    );

$sheet = $objPHPExcel->getActiveSheet();
$sheet->setTitle("LAPORAN");


$objPHPExcel->setActiveSheetIndex(0)
->setCellValue('A1', ' LAPORAN GP ')
->setCellValue('A2', ' Periode '.date('d F Y', strtotime($tanggal_start)).' s/d '.date('d F Y', strtotime($tanggal_end)))
->setCellValue('A4', 'NO')
->setCellValue('B4', 'NO FAKTUR')
->setCellValue('C4', "TANGGAL")
->setCellValue('D4', "CUSTOMER")
->setCellValue('E4', "QTY")
->setCellValue('F4', "ROLL")
->setCellValue('G4', "BARANG")
->setCellValue('H4', "HARGA")
->setCellValue('I4', "TOTAL1")
->setCellValue('J4', 'HPP + PPN 1')
->setCellValue('K4', 'SELISIH')
->setCellValue('L4', "TOTAL2")
->setCellValue('M4', 'HPP2')
->setCellValue('N4', 'SELISIH 2')
;

// $objPHPExcel->getActiveSheet()->getStyle('J4:K4')->getAlignment()->setWrapText(true); 
$objPHPExcel->getActiveSheet()->getStyle('A1:A2')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->getStyle('A4:N4')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->getStyle("A4:N4")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$data_overview = array();

$row_no = 5;
$idx = 1;

foreach ($data_hpp as $row) {
    $hpp2[$row->barang_id][$row->warna_id] = $row->hpp;
}

foreach ($penjualan_list as $row) { 
    $ppn = $row->ppn_berlaku;
    $ppn_pengali = $ppn/100;
    $ppn_pembagi = 1+$ppn_pengali;
    
    $qty = ''; $jumlah_roll = ''; 
    $nama_barang = ''; $harga_jual = '';

    if ($row->qty != '') {
        $qty = explode('??', $row->qty);
        $jumlah_roll = explode('??', $row->jumlah_roll);
        $nama_barang = explode('??', $row->nama_barang_jual);
        $barang_id = explode('??', $row->barang_id);
        $warna_id = explode('??', $row->warna_id);
        $harga_jual = explode('??', $row->harga_jual);
        $hpp = explode('??', $row->hpp);
        $untung = array();
        $untung2 = array();

        $barang_id = explode('??', $row->barang_id);
        $warna_id = explode('??', $row->warna_id);
    }
    

    
    $row_awal = $row_no;
    $row_akhir = $row_no + count($qty) - 1;
    
    $coll = 'A';
    $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$idx);
    $coll++;

    // B
    $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$row->no_faktur);
    $coll++;

    // C
    $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, is_reverse_date($row->tanggal));
    $coll++;

    // D
    $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $row->nama_customer);
    $coll++;
    
    foreach ($qty as $key => $value) {
        $coll = 'E';
        $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$value);
        $coll++;
        
        // F
        $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$jumlah_roll[$key]);
        $coll++;
        
        // G
        $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$nama_barang[$key]);
        $coll++;
        
        // H
        $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$harga_jual[$key]);
        $coll++;
        
        
        // I
        $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, "=ROUND(E".$row_no."*H".$row_no.",2)");
        $coll++;

        if ($hpp[$key] == 0){
            $objPHPExcel->getActiveSheet()->getStyle("E".$row_no.":O".$row_no)->applyFromArray($styleArrayBG3);
        }
        // J
        $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,"=ROUND(E".$row_no."*".$hpp[$key].",2)");
        $coll++;

        // K
        $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,"=I".$row_no."-J".$row_no);
        $coll++;

        // L
        $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,"=ROUND(I".$row_no."/$ppn_pembagi,2)");
        $coll++;

        $h = 0;
        if (isset($hpp2[$barang_id[$key]][$warna_id[$key]])) {
            $h = $hpp2[$barang_id[$key]][$warna_id[$key]];
        }
        
        if ($h == 0){
            $objPHPExcel->getActiveSheet()->getStyle("E".$row_no.":O".$row_no)->applyFromArray($styleArrayBG3);
        }
        // M
        $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,"=ROUND(E".$row_no."*".$h.",2)");
        $coll++;

        // N
        $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,"=L".$row_no."-M".$row_no);
        $coll++;

        if (is_posisi_id() == 1) {
            # code...
            $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$barang_id[$key]);
            $coll++;
            
            $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$warna_id[$key]);
            $coll++;
        }

        $row_no++;
        
    }
    

    $objPHPExcel->getActiveSheet()->mergeCells("A".$row_awal.":A".$row_akhir);
    $objPHPExcel->getActiveSheet()->mergeCells("B".$row_awal.":B".$row_akhir);
    $objPHPExcel->getActiveSheet()->mergeCells("C".$row_awal.":C".$row_akhir);
    $objPHPExcel->getActiveSheet()->mergeCells("D".$row_awal.":D".$row_akhir);

    

    
        
    // $objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
    // $objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

    // $objPHPExcel->getActiveSheet()->getStyle("A".$row_no.":N".$row_no)->applyFromArray($styleArrayBG);
    // $objPHPExcel->getActiveSheet()->getRowDimension($row_no)->setRowHeight(7);
    // $row_no++;

    // $objPHPExcel->getActiveSheet()->getStyle("A".$row_no.":M".$row_no)->applyFromArray($styleArrayBG);
    // $objPHPExcel->getActiveSheet()->getRowDimension($row_no)->setRowHeight(7);
    // $row_no++;
    
    
    $idx++;
}


$objPHPExcel->getActiveSheet()->getColumnDimension("A")->setWidth(7);
$objPHPExcel->getActiveSheet()->getColumnDimension("B")->setWidth(18);
$objPHPExcel->getActiveSheet()->getColumnDimension("C")->setWidth(12);
$objPHPExcel->getActiveSheet()->getColumnDimension("D")->setWidth(25);
$objPHPExcel->getActiveSheet()->getColumnDimension("E")->setWidth(10);
$objPHPExcel->getActiveSheet()->getColumnDimension("F")->setWidth(10);
$objPHPExcel->getActiveSheet()->getColumnDimension("G")->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension("H")->setWidth(12);
$objPHPExcel->getActiveSheet()->getColumnDimension("I")->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension("J")->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension("K")->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension("L")->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension("M")->setWidth(15);

// $objPHPExcel->getActiveSheet()->getStyle("D5:D".$row_no)->applyFromArray($styleArrayBG);
// $objPHPExcel->getActiveSheet()->getStyle("E5:F".$row_no)->applyFromArray($styleArrayBG0);
// $objPHPExcel->getActiveSheet()->getStyle("G5:H".$row_no)->applyFromArray($styleArrayBG1);    
// $objPHPExcel->getActiveSheet()->getStyle("I5:K".$row_no)->applyFromArray($styleArrayBG2);
// $objPHPExcel->getActiveSheet()->getStyle("L5:L".$row_no)->applyFromArray($styleArrayBG3);

$coll = 'C';
    

$nama_toko = '';
foreach ($this->toko_list_aktif as $row) {
    $nama_toko = $row->nama;
}


$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
ob_end_clean();


header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment;filename=Laporan_PENJUALAN_GP_".$nama_toko.'_'.date('d F Y', strtotime($tanggal_start)).' s/d '.date('d F Y', strtotime($tanggal_end)).".xls");
header('Cache-Control: max-age=0');
$objWriter->save('php://output');
?>