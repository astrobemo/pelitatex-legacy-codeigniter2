<?
		

		$nama_toko = '';
		foreach ($toko_data as $row) {
			$nama_toko = $row->nama;
		}
		$idx = 0;

        $row_before = 0;
        $isLeft = true;

		$pdf = new FPDF( 'L', 'mm', array(210 ,297 ) );
        $pdf->AliasNbPages();
		$pageIndex = 0;
		$pageCount = 0;

		foreach ($brs as $periode => $value) {
			$no_baris = 0;
			
			foreach ($value as $index => $data) {
				if ($no_baris + $data['jumlah_baris'] > 38 || $no_baris == 0) {
					$no_baris = 0;
					$idx++;
					if ($idx % 2 == 1) {
						$pageCount++;
					}
				}
				$no_baris+=$data['jumlah_baris'];

			}
		}
		// $pageCount = count($brs);

		$pdf->SetAutoPageBreak(0);
		$idx = 0;
		
		$general_font_size = 7;
		$tinggi = 4.3;
        $baris_max = 46;
		foreach ($brs as $periode => $value) {
			$no_baris = 0;
			$bln_up = date('Y-m-01', strtotime("+1 year", strtotime($periode)));
            // $barang_id = $value['barang_id'];
            
			$index_barang = 0;
			if($bln_up >= $bln_up){
				// echo $bln_up;
				foreach ($value as $index => $data) {
                    $index_barang++;
                    if (is_posisi_id() == 1) {
                        // echo $index.'<br/>';
                        // echo count($value).'<br/>';
                    }

                    $end_baris = 0;

                    if ($index_barang == count($value)) {
                        $end_baris = 5;
                    }


                    $barang_id = $data['barang_id'];
                    if ($data['jumlah_baris'] <= $baris_max) {
                        # code...
                        if ($no_baris + $data['jumlah_baris'] + $end_baris > 40 || $no_baris == 0) {
                            // echo strtoupper($data['nama']).' =1 '.($no_baris + $data['jumlah_baris']).'<br/>';
                            $no_baris = 1;
                            $idx++;
                            //==================awal perbedaan dengan mode 1=======================
                            if ($idx % 2 == 1) {
                                $setXStart = 3;
                                $setXEnd = 98;
        
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
                                
                                $pdf->SetFont( $font_name_bold, '', 7 );
                                $pageIndex++;
                                $pdf->Text(283,204, 'hal. '.$pdf->PageNo().' / {nb}');
                                    // $pdf->Text(283,204,'hal. '.$pageIndex.'/'.$pageCount);
                                $pdf->SetFont( $font_name_bold, '', 12 );
                                $pdf->setY(7);
                
                                if ($idx == 1) {
                                    
                                    $pdf->Cell(200,5,$nama_toko, 0, 1, 'L');
                                    $pdf->Cell(200,5,'REQUEST DELIVERY ORDER', 0, 1, 'L');
                                    $pdf->Cell(200,5,$no_request_lengkap, 0, 1, 'L');
                    
                                    // $pdf->Line(3, 10.8, 250, 10);
                                    // $pdf->Line(3, 15.8, 250, 15);
                                    // $pdf->Line(3, 20.8, 250, 20);
                    
                    
                                    $pdf->Text(220,15.3,'Kepada');
                                    $pdf->Text(220,20.3,'Attn');
                                    
                                    $pdf->Text(245,10.4, date('d F Y', strtotime( is_date_formatter($tanggal) ) ) );
                                    $pdf->Text(245,15.3,': '. strtoupper($nama_supplier));
                                    $pdf->Text(245,20.3,': '. strtoupper($attn));
                
                                }	
                                
                                $pdf->SetFont( $font_name, '', $general_font_size );
                                $pdf->setY(15);
                                $isLeft = true;

                            }else{
                                $setXStart = 150;
                                $setXEnd = 200;
                                $isLeft = false;
                                $row_before = 0;
                            }
                            // echo $key.'<br/>';
                            // print_r($value);
                            // echo $key.'<hr/>';
                            
                            $garis = 'LB';
                            $garis_kanan = 'LBR';
                            if ($idx <= 2) {
                                $yAx = 25;
                            }else{
                                $yAx = 10;
                            }
                            $pdf->setY($yAx);
                            $pdf->setX($setXStart);
                            $pdf->SetFont( $font_name_bold, '', 9 );
                            $clr = explode(',',$month_color[(int)date('m', strtotime($periode))-1]);
                            $pdf->setFillColor($clr[0],$clr[1],$clr[2]);
                            $pdf->Cell(140, 6, strtoupper(date('F Y', strtotime($periode.' +1year'))) ,1,1,'C',1);
                            $pdf->SetFont( $font_name, '', $general_font_size );
                    
                            $pdf->setX($setXStart);
                            $pdf->Cell(15,$tinggi*2, 'Barang ', $garis,0,'C');
                            $pdf->setX($setXStart+15);
                            $pdf->Cell(15,$tinggi*2, 'TOTAL', $garis,0,'C');
                            $pdf->setX($setXStart+30);
                            $pdf->Cell(45,$tinggi*2, 'Warna', $garis,0,'C');
                            $pdf->setX($setXStart+15+45+15);
                            $pdf->Cell(15,$tinggi*2, 'QTY', $garis,0,'C');
                            
                            $pdf->setX($setXStart+15+45+15+15);
                            $pdf->Cell(37.5,$tinggi, 'KETERANGAN', $garis,0,'C');
                            $pdf->setX($setXStart+15+42.5+15+15+40);
                            $pdf->Cell(12.5,$tinggi*2, 'TERKIRIM', $garis_kanan ,1,'C');
                            // $pdf->Cell(15,$tinggi*2, 'ORDER', 'LTR',0,'C');
                
                            $pdf->setY($yAx+$tinggi*2+(6-$tinggi) );
                            $pdf->setX($setXStart+15+45+15+15);
                            $pdf->Cell(25,$tinggi, 'PO', $garis,0,'C');
                            $pdf->Cell(12.5,$tinggi, 'QTY', $garis,1,'C');
            
                            //==================batas perbedaan dengan mode 1=======================
                        }else{
                            // echo strtoupper($data['nama']).' =0 '.($no_baris + $data['jumlah_baris']).'<br/>';
                            // $no_baris = 0;
                        }
                        $no_baris+=$data['jumlah_baris'];
        
                        
                        $yAxisAfter = $pdf->getY();
                        $pdf->setX($setXStart);
                        // print_r($data); echo '<hr/>';
                        //=====index nya barang id
                        $yAxisBefore = $yAxisAfter;
                        
                        if($data['jumlah_baris'] > 42){
                            $tinggi = 3.8;
                        }else{
                            $tinggi = 4.0;
                        }
        
                        //=====print nama barang=======\
                        $tamb_baris_ket = 0;
                        $ciri = ''; ;
                        $t_row = $row_before + count($data['warna']);
                        if (is_posisi_id()==1) {
                            $ciri = $t_row.' '.$index;//.' || '.count($data['warna']);
                        }

                        if ($t_row >= 38 && $isLeft == false) {
                            $setXStart = 3;
                            $setXEnd = 98;
    
                            $pdf->cMargin = 0;
                            
                            // ad page klo total page lebih dari 38 dan dia posisi nya ada di kanan
                            $pdf->AddPage();
                            $pdf->SetMargins(3,0,3);
                            $pdf->SetTextColor( 0,0,0 );
    
                            $pdf->SetFont( $font_name_bold, '', 7 );
                            $pageIndex++;
                            $pdf->Text(283,204, 'hal. '.$pdf->PageNo().' / {nb}');
                                // $pdf->Text(283,204,'hal. '.$pageIndex.'/'.$pageCount);
                            $pdf->SetFont( $font_name_bold, '', 12 );
                            $pdf->setY(7);
        
                            
                            $pdf->SetFont( $font_name, '', $general_font_size );
                            $pdf->setY(15);

                            $garis = 'LB';
                            $garis_kanan = 'LBR';
                            if ($idx <= 2) {
                                $yAx = 25;
                            }else{
                                $yAx = 10;
                            }
                            $pdf->setY($yAx);
                            $pdf->setX($setXStart);
                            $pdf->SetFont( $font_name_bold, '', 9 );
                            $clr = explode(',',$month_color[(int)date('m', strtotime($periode))-1]);
                            $pdf->setFillColor($clr[0],$clr[1],$clr[2]);
                            $pdf->Cell(140, 6, strtoupper(date('F Y', strtotime($periode.' +1year'))) ,1,1,'C',1);
                            $pdf->SetFont( $font_name, '', $general_font_size );
                    
                            $pdf->setX($setXStart);
                            $pdf->Cell(15,$tinggi*2, 'Barang ', $garis,0,'C');
                            $pdf->setX($setXStart+15);
                            $pdf->Cell(15,$tinggi*2, 'TOTAL', $garis,0,'C');
                            $pdf->setX($setXStart+30);
                            $pdf->Cell(45,$tinggi*2, 'Warna', $garis,0,'C');
                            $pdf->setX($setXStart+15+45+15);
                            $pdf->Cell(15,$tinggi*2, 'QTY', $garis,0,'C');
                            
                            $pdf->setX($setXStart+15+45+15+15);
                            $pdf->Cell(37.5,$tinggi, 'KETERANGAN', $garis,0,'C');
                            $pdf->setX($setXStart+15+42.5+15+15+40);
                            $pdf->Cell(12.5,$tinggi*2, 'TERKIRIM', $garis_kanan ,1,'C');
                            // $pdf->Cell(15,$tinggi*2, 'ORDER', 'LTR',0,'C');
                
                            $pdf->setY($yAx+$tinggi*2+(6-$tinggi) );
                            $pdf->setX($setXStart+15+45+15+15);
                            $pdf->Cell(25,$tinggi, 'PO', $garis,0,'C');
                            $pdf->Cell(12.5,$tinggi, 'QTY', $garis,1,'C');
                            $yAxisBefore = $pdf->getY();

                        }

                        

                        if (is_posisi_id()==1) {
                            // $ciri .= $pdf->getY();
                        }

                        $t_baris = $data['jumlah_baris'];
                        if (isset($keterangan_qty[$periode][$barang_id])) {
                            $tamb_baris_ket = count($keterangan_qty[$periode][$barang_id]);
                        }
                        $t_baris += $tamb_baris_ket;
                        if (strlen($data['nama']) > 7 && $t_baris > 2 ) {
                            $yNow = $pdf->getY();
                            
                            $pdf->setXY($setXStart + 1, $yNow+(($tinggi*$t_baris)/2) - 3 );
                            $pdf->Multicell(13,$tinggi, strtoupper($data['nama']).$ciri,0,'C');
                            
                            $pdf->setXY($setXStart,$yNow);
                            $pdf->Multicell(15,($tinggi*($t_baris)), '',$garis,'C');
                            $yAfter = $pdf->getY();
                            $pdf->setY($yAxisBefore + ($tinggi*($t_baris/2)));
                        }else if (strlen($data['nama']) > 7 && $t_baris == 2 ){
                            $pdf->SetFont( $font_name, '', 8 );
                            $pdf->Multicell(15,$tinggi, strtoupper($data['nama']).$ciri,$garis,'C');
                            $pdf->SetFont( $font_name, '', $general_font_size );
                        }else if(strlen($data['nama']) > 7 && $t_baris == 1){
                            $pdf->SetFont( $font_name, '', 6 );
                            $pdf->Cell(15,$tinggi, strtoupper($data['nama']).$ciri,$garis,'C');
                            $pdf->SetFont( $font_name, '', $general_font_size );
                        }else{
                            $pdf->Multicell(15,($tinggi*($t_baris)), strtoupper($data['nama']).$ciri,$garis,'C');
                        }
                        // $pdf->Text($setXStart+2,($yAxisBefore + ($tinggi*($t_baris/2))),'1995 WR/WP/CIRE GRADE B');
                        // $pdf->Multicell(15,4, '1995 WR/WP/CIRE GRADE B',$garis,'C');
                        $yAxisAfter = $pdf->getY();
                        //===============genereate warna==================
                        $pdf->setY($yAxisBefore);
                        $yAxisBeforeInt = $yAxisBefore;
        
                        $pdf->setXY($setXStart+15,$yAxisBefore);
                        $pdf->Multicell(15,($tinggi*$t_baris), str_replace(',00','',number_format($data['total_request'],'2',',','.')),$garis,'C');				
        
                        $pdf->setY($yAxisBefore);
                        $yAxisBeforeInt = $yAxisBefore;
        
                        $idx_x = 1;
                        foreach ($data['warna'] as $index2 => $data_warna) {
                            // print_r($data_warna); echo '<hr/>';

                            if ($index == 28) {
                                // echo 'yyy';
                                // print_r($classStatus[$index]);
                            }
                            $pdf->SetTextColor(0,0,0);
                            $status_fill = 0;
                            if (trim($classStatus[$index][$data_warna['warna_id']][$bln_up]) == 'finished') {
                                // echo '1';
                                $status_fill = 1;
                                $pdf->setFillColor(175,245,175);
                            }elseif(trim($classStatus[$index][$data_warna['warna_id']][$bln_up]) == 'revised'){
                                // echo '2';
                                $status_fill = 1;
                                $pdf->setFillColor(255,255,82);
                            }elseif(trim($classStatus[$index][$data_warna['warna_id']][$bln_up]) == 'revised-urgent'){
                                // echo '3';
                                $status_fill = 1;
                                $pdf->setFillColor(255,255,82);
                                $pdf->SetTextColor(255,0,0);
                            }elseif(trim($classStatus[$index][$data_warna['warna_id']][$bln_up]) == 'urgent'){
                                // echo '4';
                                $status_fill = 1;
                                $pdf->setFillColor(255,128,120);
                            }
                            
                            //=====cek ada keterangan ga
                            $tamb_baris_ket = 0;
                            if (isset($keterangan_qty[$periode][$barang_id][$data_warna['warna_id']])) {
                                $tamb_baris_ket = 1;
                            }

                            $brs_warna = $data_warna['jumlah_baris'];
                            //=====index nya 0....9
                            //=====print garis + space kiri=======
                            $pdf->setY($yAxisBeforeInt);
                            $pdf->setX($setXStart+15+15);
                            $pdf->Multicell(1,($tinggi*($brs_warna+$tamb_baris_ket)),'', $garis ,'C', $status_fill);
        
                            //=====print nama warna=======
                            $pdf->setY($yAxisBeforeInt);
                            $pdf->setX($setXStart+16+15);
                            $pdf->Multicell(44,($tinggi*($brs_warna+$tamb_baris_ket)),$data_warna['nama'],'B','L', $status_fill);
                            // $pdf->Multicell(44,($tinggi*$data_warna['jumlah_baris']),"LORENG LORENG DK OBSIDIANSSSSS",'B','L');
        
                            
                            //=====print qty request tiap warna=======
                            $pdf->setY($yAxisBeforeInt);
                            $pdf->setX($setXStart+60+15);
                            $pdf->Multicell(14,($tinggi*$brs_warna),$data_warna['qty_warna'], $garis ,'R', $status_fill);
                            
                            //=====print garis + space kanan=======
                            $pdf->setY($yAxisBeforeInt);
                            $pdf->setX($setXStart+75+14);
                            $pdf->Multicell(1,($tinggi*$brs_warna),'','B','C', $status_fill);
        
                            
                            //=====print qty terkirim=======
                            $pdf->setXY($setXStart+15+30+15+52.5+15,$yAxisBeforeInt);
                            $pdf->Multicell(12.5,($tinggi*$brs_warna), $data_warna['terkirim'],$garis_kanan,'C', $status_fill);

                            $yAxisBeforeInt += ($tinggi*($brs_warna+$tamb_baris_ket) );
                        }

                        
                        $pdf->setY($yAxisBefore);
                        foreach ($data['warna'] as $index2 => $data_warna) {
                            foreach ($data_warna['data'] as $index3 => $isi_data) {
                                // $status_fill = 0;
        
                                $pdf->SetTextColor(0,0,0);
                                $status_fill = 0;
                                if (trim($classStatus[$index][$data_warna['warna_id']][$bln_up]) == 'finished') {
                                    // echo '1';
                                    $status_fill = 1;
                                    $pdf->setFillColor(175,245,175);
                                }elseif(trim($classStatus[$index][$data_warna['warna_id']][$bln_up]) == 'revised'){
                                    // echo '2';
                                    $status_fill = 1;
                                    $pdf->setFillColor(255,255,82);
                                }elseif(trim($classStatus[$index][$data_warna['warna_id']][$bln_up]) == 'revised-urgent'){
                                    // echo '3';
                                    $status_fill = 1;
                                    $pdf->setFillColor(255,255,82);
                                    $pdf->SetTextColor(255,0,0);
                                }elseif(trim($classStatus[$index][$data_warna['warna_id']][$bln_up]) == 'urgent'){
                                    // echo '4';
                                    $status_fill = 1;
                                    $pdf->setFillColor(255,128,120);
                                }
        
                                if ($isi_data['status_urgent']) {
                                    // $status_fill = 1;
                                    // $pdf->setFillColor(255,128,120);
                                }
        
        
                                $pdf->setX($setXStart+15+45+15+15);
                                //========isi index 3 = nama object================
                                // print_r($isi_data); echo '<hr/>';
                                $pdf->Cell(1,$tinggi,'', $garis ,0,'C', $status_fill);
                                if ($isi_data['po_number'] == 'PO BARU') {
                                    $pdf->SetFont( $font_name_bold, '', $general_font_size );
                                }
                                $pdf->Cell(24,$tinggi,$isi_data['po_number'].(is_posisi_id() == 1 ? $idx_x : ''),'B',0,'L', $status_fill);
                                $pdf->SetFont( $font_name, '', $general_font_size );
                                $pdf->Cell(11.5,$tinggi, str_replace(',00','',number_format($isi_data['qty'],'2',',','.'))  , $garis,0,'R', $status_fill);
                                $pdf->Cell(1,$tinggi,'','B',1,'C', $status_fill);
                                $idx_x++;
                                $row_before++;
                            }
                            if (isset($keterangan_qty[$periode][$barang_id][$data_warna['warna_id']])) {
                                // $pdf->setY($yAxisBefore+=$tinggi);
                                $pdf->setX($setXStart+60+15);
                                //========isi index 3 = nama object================
                                // print_r($isi_data); echo '<hr/>';
                                $pdf->Cell(1,$tinggi,'', $garis ,0,'C', $status_fill);
                                $pdf->SetFont( $font_name, '', '6' );
                                $pdf->Cell(14,$tinggi,"KETERANGAN",'B',0,'C', $status_fill);
                                $pdf->SetFont( $font_name, '', $general_font_size );
                                $pdf->Cell(1,$tinggi, ''  , "LB",0,'L', $status_fill);
                                $pdf->Cell(48,$tinggi, $keterangan_qty[$periode][$barang_id][$data_warna['warna_id']]  ,"B",0,'L', $status_fill);
                                $pdf->Cell(1,$tinggi,'','BR',1,'C', $status_fill);
                                $yAxisBefore += $tinggi;
                                $idx_x++;
                                $row_before++;
                            }
                        }
                    }else{


                        $kolom0 = 0;
                        $kolom1 = 0;
                        $kolom2 = 0;
                        $col1= array();
                        $col2= array();
                        $isCol1 = true;
                        foreach ($data['warna'] as $index2 => $data_warna) {
                            if ($kolom1 + $data_warna['jumlah_baris'] <= $baris_max && $isCol1) {
                                // echo $data_warna['nama'].' ';
                                // echo $kolom1 .'+='. $data_warna['jumlah_baris'];
                                // echo "<br/>";
                                $kolom1 += $data_warna['jumlah_baris'];
                            }else{
                                $isCol1 = false;
                                $kolom2 += $data_warna['jumlah_baris'];
                            }
                        }

                        // echo count($col1);
                        // echo $kolom1;
                        // echo "<hr/>";
                        // echo count($col2);
                        // echo $kolom2;
                        // echo "<hr/>";

                        $no_baris+=$data['jumlah_baris'];

                        for ($m=0; $m < 2 ; $m++) { 
                            $idx++;
                            if ($m==0 || $no_baris == 0) {
                                // echo strtoupper($data['nama']).' =1 '.($no_baris + $data['jumlah_baris']).'<br/>';
                                $no_baris = 1;
                                
                                //==================awal perbedaan dengan mode 1=======================
                                if ($idx % 2 == 1) {
                                    $setXStart = 3;
                                    $setXEnd = 98;
            
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
                                    
                                    $pdf->SetFont( $font_name_bold, '', 7 );
                                    $pageIndex++;
                                    // $pdf->Text(283,204,'hal. '.$pageIndex.'/'.$pageCount);
                                    $pdf->Text(283,204, 'hal. '.$pdf->PageNo().' / {nb}');
                                    $pdf->SetFont( $font_name_bold, '', 12 );
                                    $pdf->setY(7);
                    
                                    if ($idx == 1) {
                                        
                                        $pdf->Cell(200,5,$nama_toko, 0, 1, 'L');
                                        $pdf->Cell(200,5,'REQUEST DELIVERY ORDER', 0, 1, 'L');
                                        $pdf->Cell(200,5,$no_request_lengkap, 0, 1, 'L');
                        
                                        // $pdf->Line(3, 10.8, 250, 10);
                                        // $pdf->Line(3, 15.8, 250, 15);
                                        // $pdf->Line(3, 20.8, 250, 20);
                        
                        
                                        $pdf->Text(220,15.3,'Kepada');
                                        $pdf->Text(220,20.3,'Attn');
                                        
                                        $pdf->Text(245,10.4, date('d F Y', strtotime( is_date_formatter($tanggal) ) ) );
                                        $pdf->Text(245,15.3,': '. strtoupper($nama_supplier));
                                        $pdf->Text(245,20.3,': '. strtoupper($attn));
                    
                                    }	
                                    
                                    $pdf->SetFont( $font_name, '', $general_font_size );
                                    $pdf->setY(15);
                    
                                }else{
                                    $setXStart = 150;
                                    $setXEnd = 200;
                                }
                                // echo $key.'<br/>';
                                // print_r($value);
                                // echo $key.'<hr/>';
                                
                                $garis = 'LB';
                                $garis_kanan = 'LBR';
                                if ($idx <= 2) {
                                    $yAx = 25;
                                }else{
                                    $yAx = 10;
                                }
                                $pdf->setY($yAx);
                                $pdf->setX($setXStart);
                                $pdf->SetFont( $font_name_bold, '', 9 );
                                $clr = explode(',',$month_color[(int)date('m', strtotime($periode))-1]);
                                $pdf->setFillColor($clr[0],$clr[1],$clr[2]);
                                $pdf->Cell(140, 6, strtoupper(date('F Y', strtotime($periode.' +1year'))) ,1,1,'C',1);
                                $pdf->SetFont( $font_name, '', $general_font_size );
                        
                                $pdf->setX($setXStart);
                                $pdf->Cell(15,$tinggi*2, 'Barang ', $garis,0,'C');
                                $pdf->setX($setXStart+15);
                                $pdf->Cell(15,$tinggi*2, 'TOTAL', $garis,0,'C');
                                $pdf->setX($setXStart+30);
                                $pdf->Cell(45,$tinggi*2, 'Warna', $garis,0,'C');
                                $pdf->setX($setXStart+15+45+15);
                                $pdf->Cell(15,$tinggi*2, 'QTY', $garis,0,'C');
                                
                                $pdf->setX($setXStart+15+45+15+15);
                                $pdf->Cell(37.5,$tinggi, 'KETERANGAN', $garis,0,'C');
                                $pdf->setX($setXStart+15+42.5+15+15+40);
                                $pdf->Cell(12.5,$tinggi*2, 'TERKIRIM', $garis_kanan ,1,'C');
                                // $pdf->Cell(15,$tinggi*2, 'ORDER', 'LTR',0,'C');
                    
                                $pdf->setY($yAx+$tinggi*2+(6-$tinggi) );
                                $pdf->setX($setXStart+15+45+15+15);
                                $pdf->Cell(25,$tinggi, 'PO', $garis,0,'C');
                                $pdf->Cell(12.5,$tinggi, 'QTY', $garis,1,'C');
                
                                //==================batas perbedaan dengan mode 1=======================
                            }else{
                                if ($idx % 2 == 1) {
                                    $setXStart = 3;
                                    $setXEnd = 98;
            
                                    $pdf->cMargin = 0;

                                    
                                    $garis = 'LB';
                                    $garis_kanan = 'LBR';
                                    

                                    // add page dengan syarat : 
                                    // 
                                    $pdf->AddPage();
                                    $pdf->SetMargins(3,0,3);
                                    $pdf->SetTextColor( 0,0,0 );
            
                                    $pdf->AddFont('calibriL','','calibriL.php');
                                    $pdf->AddFont('calibri','','calibri.php');
                                    $pdf->AddFont('calibriLI','','calibriLI.php');
            
                                    $font_name = 'calibriL';
                                    $font_name_bold = 'calibri';
                                    $font_name_italic = 'calibriLI';
                                    
                                    $pdf->SetFont( $font_name_bold, '', 7 );
                                    $pageIndex++;
                                    $pdf->Text(283,204, 'hal. '.$pdf->PageNo().' / {nb}');
                                    // $pdf->Text(283,204,'hal. '.$pageIndex.'/'.$pageCount);
                                    $pdf->SetFont( $font_name_bold, '', 12 );
                                    $pdf->setY(10);
                    
                                    $pdf->setY($yAx);
                                    $pdf->setX($setXStart);
                                    $pdf->SetFont( $font_name_bold, '', 9 );
                                    $clr = explode(',',$month_color[(int)date('m', strtotime($periode))-1]);
                                    $pdf->setFillColor($clr[0],$clr[1],$clr[2]);
                                    $pdf->Cell(140, 6, strtoupper(date('F Y', strtotime($periode.' +1year'))) ,1,1,'C',1);
                                    $pdf->SetFont( $font_name, '', $general_font_size );
                            
                                    $pdf->setX($setXStart);
                                    $pdf->Cell(15,$tinggi*2, 'Barang ', $garis,0,'C');
                                    $pdf->setX($setXStart+15);
                                    $pdf->Cell(15,$tinggi*2, 'TOTAL', $garis,0,'C');
                                    $pdf->setX($setXStart+30);
                                    $pdf->Cell(45,$tinggi*2, 'Warna', $garis,0,'C');
                                    $pdf->setX($setXStart+15+45+15);
                                    $pdf->Cell(15,$tinggi*2, 'QTY', $garis,0,'C');
                                    
                                    $pdf->setX($setXStart+15+45+15+15);
                                    $pdf->Cell(37.5,$tinggi, 'KETERANGAN', $garis,0,'C');
                                    $pdf->setX($setXStart+15+42.5+15+15+40);
                                    $pdf->Cell(12.5,$tinggi*2, 'TERKIRIM', $garis_kanan ,1,'C');

                                    $pdf->setY($yAx+$tinggi*2+(6-$tinggi) );
                                    $pdf->setX($setXStart+15+45+15+15);
                                    $pdf->Cell(25,$tinggi, 'PO', $garis,0,'C');
                                    $pdf->Cell(12.5,$tinggi, 'QTY', $garis,1,'C');

                                    $isLeft = true;
                                    
                    
                                }else{
                                    $setXStart = 150;
                                    $setXEnd = 200;
                                    $pdf->setY(16);
                                    $pdf->Line($setXStart,16, 290,16);
                                    $isLeft = false;
                                    $row_before = 0;
                                    // $idx++;
                                }
                            }
            
                            
                            $yAxisAfter = $pdf->getY();
                            $pdf->setX($setXStart);
                            // print_r($data); echo '<hr/>';
                            //=====index nya barang id
                            $yAxisBefore = $yAxisAfter;
                            
                            /**
                            =====================================TINGGI================================ 
                            **/
                            $tinggi = 3.8;

                            $t_baris = ${'kolom'.($m+1)};
                            $yNow = $pdf->getY();
                            
                            $ciri = '';
                            if (is_posisi_id()==1) {
                                $ciri = $row_before.' + '.count($data['warna']);
                            }
                            $pdf->setXY($setXStart + 1, $yNow+(($tinggi*$t_baris)/2) - 3 );
                            $pdf->Multicell(13,$tinggi, strtoupper($data['nama']).$ciri,0,'C');
                            
                            $pdf->setXY($setXStart,$yNow);
                            $pdf->Multicell(15,($tinggi*($t_baris)), '',$garis,'C');
                            $yAfter = $pdf->getY();
                            $pdf->setY($yAxisBefore + ($tinggi*($t_baris/2)));
                            // $pdf->Text($setXStart+2,($yAxisBefore + ($tinggi*($t_baris/2))),'1995 WR/WP/CIRE GRADE B');
                            // $pdf->Multicell(15,4, '1995 WR/WP/CIRE GRADE B',$garis,'C');
                            $yAxisAfter = $pdf->getY();
                            //===============genereate warna==================
                            $pdf->setY($yAxisBefore);
                            $yAxisBeforeInt = $yAxisBefore;
            
                            $pdf->setXY($setXStart+15,$yAxisBefore);
                            $pdf->Multicell(15,($tinggi*$t_baris), str_replace(',00','',number_format($data['total_request'],'2',',','.')),$garis,'C');				
            
                            $pdf->setY($yAxisBefore);
                            $yAxisBeforeInt = $yAxisBefore;

                            $kol_now = 0;
                            $idx_x2 = 1;
                            foreach ($data['warna'] as $index2 => $data_warna) {
                                // print_r($data_warna); echo '<hr/>';
                                
                                $kol_now += $data_warna['jumlah_baris'];
                                if ($kol_now > ${'kolom'.($m)} && $kol_now <= (${'kolom'.($m+1)} + ${'kolom'.($m)} ) ) {
                                    # code...
                                    $pdf->SetTextColor(0,0,0);
                                    $status_fill = 0;
                                    if (trim($classStatus[$index][$data_warna['warna_id']][$bln_up]) == 'finished') {
                                        // echo '1';
                                        $status_fill = 1;
                                        $pdf->setFillColor(175,245,175);
                                    }elseif(trim($classStatus[$index][$data_warna['warna_id']][$bln_up]) == 'revised'){
                                        // echo '2';
                                        $status_fill = 1;
                                        $pdf->setFillColor(255,255,82);
                                    }elseif(trim($classStatus[$index][$data_warna['warna_id']][$bln_up]) == 'revised-urgent'){
                                        // echo '3';
                                        $status_fill = 1;
                                        $pdf->setFillColor(255,255,82);
                                        $pdf->SetTextColor(255,0,0);
                                    }elseif(trim($classStatus[$index][$data_warna['warna_id']][$bln_up]) == 'urgent'){
                                        // echo '4';
                                        $status_fill = 1;
                                        $pdf->setFillColor(255,128,120);
                                    }
                                    
                                    //=====index nya 0....9
                                    $pdf->setY($yAxisBeforeInt);
                                    $pdf->setX($setXStart+15+15);
                                    $pdf->Multicell(1,($tinggi*$data_warna['jumlah_baris']),'', $garis ,'C', $status_fill);
                
                                    $pdf->setY($yAxisBeforeInt);
                                    $pdf->setX($setXStart+16+15);
                                    $pdf->Multicell(44,($tinggi*$data_warna['jumlah_baris']),$data_warna['nama'].(is_posisi_id() == 1 ? 'hai' : ''),'B','L', $status_fill);
                                    // $pdf->Multicell(44,($tinggi*$data_warna['jumlah_baris']),"LORENG LORENG DK OBSIDIANSSSSS",'B','L');
                
                                    
                                    $pdf->setY($yAxisBeforeInt);
                                    $pdf->setX($setXStart+60+15);
                                    $pdf->Multicell(14,($tinggi*$data_warna['jumlah_baris']),$data_warna['qty_warna'], $garis ,'R', $status_fill);
                                    
                                    $pdf->setY($yAxisBeforeInt);
                                    $pdf->setX($setXStart+75+14);
                                    $pdf->Multicell(1,($tinggi*$data_warna['jumlah_baris']),'','B','C', $status_fill);
                
                                    
                                    $pdf->setXY($setXStart+15+30+15+52.5+15,$yAxisBeforeInt);
                                    $pdf->Multicell(12.5,($tinggi*$data_warna['jumlah_baris']), $data_warna['terkirim'],$garis_kanan,'C', $status_fill);
                                    
                                    $yAxisBeforeInt += ($tinggi*$data_warna['jumlah_baris']);
                                }
            
                            }
                            
                            $kol_now = 0;
                            $pdf->setY($yAxisBefore);
                            foreach ($data['warna'] as $index2 => $data_warna) {
                                $kol_now += $data_warna['jumlah_baris'];
                                if ($kol_now > ${'kolom'.($m)} && $kol_now <= (${'kolom'.($m+1)} + ${'kolom'.($m)} ) ) {
                                    foreach ($data_warna['data'] as $index3 => $isi_data) {
                                    // $status_fill = 0;
                                    

                                        $pdf->SetTextColor(0,0,0);
                                        $status_fill = 0;
                                        if (trim($classStatus[$index][$data_warna['warna_id']][$bln_up]) == 'finished') {
                                            // echo '1';
                                            $status_fill = 1;
                                            $pdf->setFillColor(175,245,175);
                                        }elseif(trim($classStatus[$index][$data_warna['warna_id']][$bln_up]) == 'revised'){
                                            // echo '2';
                                            $status_fill = 1;
                                            $pdf->setFillColor(255,255,82);
                                        }elseif(trim($classStatus[$index][$data_warna['warna_id']][$bln_up]) == 'revised-urgent'){
                                            // echo '3';
                                            $status_fill = 1;
                                            $pdf->setFillColor(255,255,82);
                                            $pdf->SetTextColor(255,0,0);
                                        }elseif(trim($classStatus[$index][$data_warna['warna_id']][$bln_up]) == 'urgent'){
                                            // echo '4';
                                            $status_fill = 1;
                                            $pdf->setFillColor(255,128,120);
                                        }
                
                                        if ($isi_data['status_urgent']) {
                                            // $status_fill = 1;
                                            // $pdf->setFillColor(255,128,120);
                                        }
                
                
                                        $pdf->setX($setXStart+15+45+15+15);
                                        //========isi index 3 = nama object================
                                        // print_r($isi_data); echo '<hr/>';
                                        $pdf->Cell(1,$tinggi,'', $garis ,0,'C', $status_fill);
                                        if ($isi_data['po_number'] == 'PO BARU') {
                                            $pdf->SetFont( $font_name_bold, '', $general_font_size );
                                        }
                                        $pdf->Cell(24,$tinggi,$isi_data['po_number'].(is_posisi_id() == 1 ? $idx_x2 : ''),'B',0,'L', $status_fill);
                                        $pdf->SetFont( $font_name, '', $general_font_size );
                                        $pdf->Cell(11.5,$tinggi, str_replace(',00','',number_format($isi_data['qty'],'2',',','.'))  , $garis,0,'R', $status_fill);
                                        $pdf->Cell(1,$tinggi,'','B',1,'C', $status_fill);
                                        $idx_x2++;
                                        $row_before++;
                                    }
                                    
                                }
                            }
                        }

                    }
				}
	
				$pdf->SetFont( $font_name_bold, '', 10 );
	
				$pdf->setX($setXStart);
				
				// $pdf->Cell(15,6,"TOTAL",'LB',0,'C');
				// $pdf->Cell(15,6,str_replace(',00','',number_format($g_total[$periode],'2',',','.')),'LB',0,'C');
				// $pdf->Cell(110,6,'','LBR',0,'L');
				$pdf->Cell(75,6,"TOTAL REQUEST",'LB',0,'C');
				$pdf->Cell(65,6,str_replace(',00','',number_format($g_total[$periode],'2',',','.')),'LBR',1,'C');
				
				if (!isset($total_terkirim[$bln_up])) {
					$total_terkirim[$bln_up] = 0;
				}
				$pdf->setX($setXStart);
				$pdf->Cell(75,6,"TOTAL TERKIRIM",'LB',0,'C');
				$pdf->Cell(65,6,str_replace(',00','',number_format($total_terkirim[$bln_up],'2',',','.')),'LBR',1,'C');
	
				$pdf->setX($setXStart);
				$pdf->Cell(75,6,"SELISIH",'LB',0,'C');
				$pdf->Cell(65,6,str_replace(',00','',number_format($g_total[$periode] - $total_terkirim[$bln_up],'2',',','.')),'LBR',1,'C');

			}
			

			// $yAxisBefore = $pdf->getY();
			// $yAxisAfter = $pdf->getY();
			
		}


		$pdf->Output( 'request_barang.pdf', "I" );
					