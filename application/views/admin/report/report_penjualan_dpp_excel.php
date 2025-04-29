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

$idx_data = 1;
$rows_total = count($data_faktur);
$idx = 1;
$idx_file = 1;

$row_limit = 1200;
$row_each = $row_limit;

if ($rows_total > $row_limit) {
    $divide = 2;
    while ($row_each == 0 || $row_each > 450) {
        $row_each = ceil($rows_total / $divide);
        $divide++;
    }
}

$filePath = [];

foreach ($data_faktur as $row) {
    // echo $row->nama_barang;
    // echo '<hr/>';

    if ($idx_data == 1) {
        $row_no = 4;
        $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue("A".($row_no)."", "NO")
        ->setCellValue("B".($row_no)."", "NO FP")
        ->setCellValue("C".($row_no)."", "NO FAKTUR")
        ->setCellValue("D".($row_no)."", "BULAN")
        ->setCellValue("E".($row_no)."", "TANGGAL")
        ->setCellValue("F".($row_no)."", "NAMA CUSTOMER")
        ->setCellValue("G".($row_no)."", "NILAI FAKTUR")
        ->setCellValue("H".($row_no)."", "DPP + PPN")
        ->setCellValue("I".($row_no)."", "DPP")
        ->setCellValue("J".($row_no)."", "PPN")
        ->setCellValue("K".($row_no)."", "SELISIH")
        ;

        $row_no = 5;
    }
    

    
    $coll = "A";
    $row_awal = $row_no;
    
    $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$idx);
    $objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
    $objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(5);
    $coll++;

    $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$row->no_faktur_pajak);
    $objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
    $objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
    $coll++;

    $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$row->no_faktur_jual);
    $objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
    $objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
    $coll++;

    $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, DATE("F", strtotime($row->tanggal)));
    $objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
    $objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(12);
    $coll++;

    $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, DATE("d/m/Y", strtotime($row->tanggal)));
    $objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
    $objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(12);
    $coll++;

    $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$row->nama_customer);
    $objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
    $objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(30);
    $coll++;

    

    $col_jual = $coll;
    $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$row->total_jual);
    $objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
    $objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
    $coll++;

    $col_jualB = $coll;
    $dpp = ($row->total_jual_fp/1.11) * 100;
    $dpp = floor($dpp)/100;
    $ppn = floor($dpp * ($row->ppn_berlaku/100));
    $dpp = floor($dpp);


    $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$dpp + $ppn);
    $objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
    $objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
    $coll++;

    $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$dpp);
    $objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
    $objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
    $coll++;

    $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$ppn);
    $objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
    $objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
    $coll++;    

    $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,"=(".$col_jual.$row_no."-".$col_jualB.$row_no.")");
    $objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
    $objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
    $coll++;    

   
    if ($idx_data == $row_each || $idx == $rows_total ) {

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        
        $fileName = "Laporan_JUAL_FP $tanggal_start - $tanggal_end - $idx_file.xls";

        if ($rows_total <= $row_limit) {
            # code...
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header("Content-Disposition: attachment;filename=".$fileName);
            header('Cache-Control: max-age=0');
            $objWriter->save('php://output');
        }else{
            $tempFilePath = sys_get_temp_dir() . "/$fileName";
            $objWriter->save($tempFilePath);

            $filePaths[] = $tempFilePath;


            $objPHPExcel->disconnectWorksheets();
            unset($objPHPExcel);

            $objPHPExcel = new PHPExcel();
            $idx_data = 0;
            $idx_file++;
        }
        

        

    }

    $idx++;
    $row_no++;
    $idx_data++;

};

if ($rows_total > $row_limit) {
    $fileZipName = "Laporan_Jual_FP_".$tanggal_start."_".$tanggal_end.".Zip";
    
    $zip = new ZipArchive();
    if ($zip->open($fileZipName, ZipArchive::CREATE) === TRUE) {
    
        foreach ($filePaths as $filePath) {
            $zip->addFile($filePath, basename($filePath));
        }
        $zip->close();
    
        header('Content-Type: application/zip');
        header("Content-Disposition: attachment;filename=$fileZipName");
        header('Cache-Control: max-age=0');
        header('Content-Length: ' . filesize($fileZipName));
    
        // Output the ZIP file
        readfile($fileZipName);
    
        // Delete the temporary Excel files and the ZIP file
        foreach ($filePaths as $filePath) {
            unlink($filePath);
        }
        unlink($fileZipName);
    } else {
        echo "Failed to create ZIP archive";
    }

}


?>