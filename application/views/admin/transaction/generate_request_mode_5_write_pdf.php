<?php


    $pdf = new FPDF( 'L', 'mm', array(210 ,297 ) );

    $pdf->AddFont('calibriL','','calibriL.php');
    $pdf->AddFont('calibri','','calibri.php');
    $pdf->AddFont('calibriLI','','calibriLI.php');
    $pdf->AliasNbPages();
    
    foreach ($page as $p_index => $val) {

        $setXStart = 3;
        $setXEnd = 98;

        $pdf->cMargin = 0;

        $garis = 'LB';
        $garis_kanan = 'LBR';        

        $pdf->AddPage();
        $pdf->SetMargins(3,0,3);
        $pdf->SetTextColor( 0,0,0 );

        $font_name = 'calibriL';
        $font_name_bold = 'calibri';
        $font_name_italic = 'calibriLI';
        
        $pdf->SetFont( $font_name_bold, '', 7 );
        $pageIndex++;
        $pdf->Text(283,204, 'hal. '.$pdf->PageNo().' / {nb}');
        $pdf->SetFont( $font_name_bold, '', 12 );


        if ($p_index == 1) {
            $pdf->setX($setXStart);
            $pdf->Cell(200,5,$nama_toko, 0, 1, 'L');
            $pdf->Cell(200,5,'REQUEST DELIVERY ORDER', 0, 1, 'L');
            $pdf->Cell(200,5,$no_request_lengkap, 0, 1, 'L');

            $pdf->Text(150,15.3,'Kepada');
            $pdf->Text(150,20.3,'Attn');
            
            $pdf->Text(150,10.4, strtoupper(date('d F Y', strtotime( is_date_formatter($tanggal) ) )) );
            $pdf->Text(165,15.3,': '. strtoupper($nama_supplier));
            $pdf->Text(165,20.3,': '. strtoupper($attn));

        }
        
        foreach ($val as $position => $val2) {
            $pdf->SetFont( $font_name, '', $general_font_size );
            $pdf->setY(20);

            if ($p_index < 2) {
                $yAx = 25;
            }else{
                $yAx = 10;
            }

            if ($position % 2 == 1) {
                $setXStart = 3;
                $setXEnd = 98;
            }else{
                $setXStart = 150;
                $setXEnd = 200;
            }

            foreach ($val2 as $k => $data) {
                
                if ($k == 0) {
                    # code...
                    $garis = 'LB';
                    $garis_kanan = 'LBR';
                    $pdf->setY($yAx);
                    $pdf->setX($setXStart);
                    $pdf->SetFont( $font_name_bold, '', 9 );
                    $clr = explode(',',$month_color[(int)date('m', strtotime($data['bulan_request']))-1]);
                    $pdf->setFillColor($clr[0],$clr[1],$clr[2]);
                    $pdf->Cell(140, 6, strtoupper(date('F Y', strtotime($data['bulan_request'].' +1year'))) ,1,1,'C',1);
                    $pdf->SetFont( $font_name_bold, '', 8 );
            
                    $pdf->setX($setXStart);
                    $pdf->Cell(15,$tinggi*2, 'BARANG ', $garis,0,'C');
                    $pdf->setX($setXStart+15);
                    $pdf->Cell(15,$tinggi*2, 'TOTAL', $garis,0,'C');
                    $pdf->setX($setXStart+30);
                    $pdf->Cell(45,$tinggi*2, 'WARNA', $garis,0,'C');
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
    
                    $pdf->SetFont( $font_name, '', $general_font_size );
                }

                //=========================================================================
                $getFirstPOSY = $pdf->getY();
                // 8310 W7
                // 7617 W /6650 W
                $pdf->setX($setXStart);
                // $pdf->setXY($setXStart + 1, $yNow+(($tinggi*$data['jumlah_baris'])/2) - 3 );
                $setPOSX = $setXStart + 1;

                $pengurang = 2;
                if (strlen($data['nama']) > 8) {
                    $pengurang = 4.5;
                }
                
                $setPOSY = $pdf->getY();
                
                if ($data['jumlah_baris'] > 1) {
                    $setPOSY = $pdf->getY() +(($tinggi*$data['jumlah_baris'])/2) - $pengurang;
                }

                $pdf->Multicell(15,$tinggi*$data['jumlah_baris'], '','LB','C');

                $getPOSX = $pdf->getX();
                $getPOSY = $pdf->getY();
                
                //render nama barang
                $pdf->setXY($setPOSX, $setPOSY);
                if ($data['jumlah_baris'] > 1) {
                    $pdf->Multicell(13, $tinggi, strtoupper($data['nama']), 0, 'C');
                }else{
                    $pdf->Cell(13, $tinggi, strtoupper($data['nama']),'','', 0);
                }

                //render total request
                $pdf->setXY($setXStart+15, $getFirstPOSY);
                $pdf->Multicell(15, $tinggi*$data['jumlah_baris'], number_format($data['total_request'],'0',',','.') , 'LB', 'C');


                $pdf->setXY($getPOSX, $getPOSY);

                $barang_id = $data['barang_id'];
                $bln_now = $data['bulan'];
                $bulan_request = $data['bulan_request'];

                // echo $barang_id.' '.$bln_now;
                $pdf->setY($getFirstPOSY);
                foreach ($data['warna'] as $k_warna => $v_warna) {

                    $getPOSY = $pdf->getY();
                    $warna_id = $v_warna['warna_id'];
                    $brs_warna = $v_warna['jumlah_baris'];
                    $tamb_baris = 0;
                    if (isset($keterangan_qty[$bulan_request][$barang_id][$warna_id])) {
                        $tamb_baris = 1;
                    }
                    $pdf->SetTextColor(0,0,0);
                    $status_fill = 0;
                    //class status index : barang_id, warna_id, bulan_request
                    if (trim($classStatus[$barang_id][$warna_id][$bln_now]) == 'finished') {
                        // echo '1';
                        $status_fill = 1;
                        $pdf->setFillColor(175,245,175);
                    }elseif(trim($classStatus[$barang_id][$warna_id][$bln_now]) == 'revised'){
                        // echo '2';
                        $status_fill = 1;
                        $pdf->setFillColor(255,255,82);
                    }elseif(trim($classStatus[$barang_id][$warna_id][$bln_now]) == 'revised-urgent'){
                        // echo '3';
                        $status_fill = 1;
                        $pdf->setFillColor(255,255,82);
                        $pdf->SetTextColor(255,0,0);
                    }elseif(trim($classStatus[$barang_id][$warna_id][$bln_now]) == 'urgent'){
                        // echo '4';
                        $status_fill = 1;
                        $pdf->setFillColor(255,128,120);
                    }

                    //====gambar border dlu=======
                    // $pdf->setY($getFirstPOSY);
                    $pdf->setX($setXStart+15+15);
                    $pdf->Multicell(1,($tinggi*($brs_warna+$tamb_baris)),'', 'LB' ,'C', $status_fill);

                    //=====print nama warna=======
                    $pdf->setY($getPOSY);
                    $pdf->setX($setXStart+16+15);
                    $pdf->Multicell(44,($tinggi*($brs_warna+$tamb_baris)),$v_warna['nama'],'B','L', $status_fill);

                    //=====print request by warna=======
                    $pdf->setY($getPOSY);
                    $pdf->setX($setXStart+16+15+44);
                    $pdf->Multicell(14,($tinggi*($brs_warna+$tamb_baris)), $v_warna['qty_warna'],'LB','R', $status_fill);

                    //=====print spasi=======
                    $pdf->setY($getPOSY);
                    $pdf->setX($setXStart+16+15+44+14);
                    $pdf->Multicell(1,($tinggi*($brs_warna+$tamb_baris)), '','B','B', $status_fill);

                    //=====print terkirim dlu di ujung=======
                    $pdf->setY($getPOSY);
                    $pdf->setX($setXStart+16+15+44+52.5);
                    $pdf->Multicell(11.5,($tinggi*($brs_warna+$tamb_baris)), $v_warna['terkirim'],'LB','R', $status_fill);
                    
                    //=====print spasi=======
                    $pdf->setY($getPOSY);
                    $pdf->setX($setXStart+16+15+44+64);
                    $pdf->Multicell(1,($tinggi*($brs_warna+$tamb_baris)), '','RB','B', $status_fill);

                    // now render PO
                    $pdf->setY($getPOSY);
                    foreach ($v_warna['data'] as $k_po => $v_po) {
                        $pdf->setX($setXStart+16+15+44+15);
                        $pdf->Cell(1,$tinggi,'','LB','','',$status_fill);
                        $pdf->Cell(24,$tinggi,$v_po['po_number'],'B','','L',$status_fill);
                        $pdf->Cell(11,$tinggi,number_format($v_po['qty'],'0',',','.') ,'LB','','R',$status_fill);
                        $pdf->Cell(1.5,$tinggi,'','B','1','L',$status_fill);
                    }

                    if (isset($keterangan_qty[$bulan_request][$barang_id][$warna_id])) {
                        $pdf->setX($setXStart+16+15+44+15);
                        $pdf->Cell(1,$tinggi, ''  , "LB",0,'L', $status_fill);
                        $pdf->Cell(35,$tinggi, $keterangan_qty[$bulan_request][$barang_id][$warna_id]  ,"B",0,'L', $status_fill);
                        $pdf->Cell(1.5,$tinggi,'','B',1,'C', $status_fill);
                    }

                    // if ($k_warna == count($data['warna']) - 1) {
                    //     if (isset($keterangan_qty[$bulan_request][$barang_id][$warna_id])) {
                    //         $pdf->Cell(1,$tinggi, ''  , "LB",0,'L', $status_fill);
                    //         $pdf->Cell(48,$tinggi, $keterangan_qty[$bulan_request][$barang_id][$warna_id]  ,"B",0,'L', $status_fill);
                    //         $pdf->Cell(1,$tinggi,'','BR',1,'C', $status_fill);
                    //     }
                    // }

                    
                }


                // $pdf->Text($setXStart, $getPOSY, strtoupper($data['nama']));
                // $pdf->Multicell(13,$tinggi*$data['jumlah_baris'], strtoupper($data['nama']).$data['jumlah_baris'],0,'C');

                
                // echo $v['nama'].' == '.$v['jumlah_baris'].'<br/>';
                // print_r($v['warna']);
                // echo '<hr/>';
            }

            if (isset($page_footer[$p_index][$position])) {
                $pdf->SetFont( $font_name_bold, '', 10 );
	
				$pdf->setX($setXStart);
                $bln_up = date('Y-m-01', strtotime("+1 year", strtotime($data['bulan_request'])));
				
				// $pdf->Cell(15,6,"TOTAL",'LB',0,'C');
				// $pdf->Cell(15,6,str_replace(',00','',number_format($g_total[$periode],'2',',','.')),'LB',0,'C');
				// $pdf->Cell(110,6,'','LBR',0,'L');
				$pdf->Cell(75,6,"TOTAL REQUEST",'LB',0,'C');
				$pdf->Cell(65,6,str_replace(',00','',number_format($g_total[$data['bulan_request']],'2',',','.')),'LBR',1,'C');
				
				if (!isset($total_terkirim[$bln_up])) {
					$total_terkirim[$bln_up] = 0;
				}
				$pdf->setX($setXStart);
				$pdf->Cell(75,6,"TOTAL TERKIRIM",'LB',0,'C');
				$pdf->Cell(65,6,str_replace(',00','',number_format($total_terkirim[$bln_up],'2',',','.')),'LBR',1,'C');
	
				$pdf->setX($setXStart);
				$pdf->Cell(75,6,"SELISIH",'LB',0,'C');
				$pdf->Cell(65,6,str_replace(',00','',number_format($g_total[$data['bulan_request']] - $total_terkirim[$bln_up],'2',',','.')),'LBR',1,'C');
            }
            // echo '<hr/>';
        }
    }