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

                // print_r($data);
                // echo $data['nama'].' '.$data['barang_id'].'<br/>';
                // echo $data['jumlah_baris'].'<br/>';
                // $dt = $data['warna'];
                // for ($i=0; $i < count($dt); $i++) { 
                //     echo $dt[$i]['nama'].'==';
                //     echo $dt[$i]['jumlah_baris'].'<br/>';
                //     echo $dt[$i]['warna_id'].'<br/>';
                //     print_r($dt[$i]['data']);
                //     echo '<hr/>';
                // }
                // echo '<hr/>';
				$no_baris+=$data['jumlah_baris'];

			}


		}
		// $pageCount = count($brs);

        // echo $no_baris;
		$pdf->SetAutoPageBreak(0);
		$idx = 0;
		
		$general_font_size = 7;
		$tinggi = 3.4;
        $baris_max = 45;
        $baris_breakdown = 25;

        //index_baris_berjalan
        $p_idx = 1;
        $p_idx2 = 1;
        $page = [];
        $page[1][1] = [];
        $no_baris = 0;
        $total_baris[1][1] = 0;
        $bm = $baris_max;
        // print_r($brs);
        foreach ($brs as $periode => $value) {
			$bln_up = date('Y-m-01', strtotime("+1 year", strtotime($periode)));
            
            // $badrang_id = $value['barang_id'];
			
			if($bln_up >= $bln_up){
				// echo $bln_up;
                // index is barang_id
				foreach ($value as $index => $data) {
                    
                    $barang_id = $data['barang_id'];
                    
                    if ($data_view == 1) {
                        echo $index.' == ';
                        echo $data['nama'].'-- '.$data['jumlah_baris'].'<br/>';
                    }

                    if ($p_idx == 1) {
                        $baris_max = $bm - 8;
                    }else{
                        $baris_max = $bm;
                    }


                    if (!isset($j_baris[$p_idx][$p_idx2][$barang_id])) {
                        $j_baris[$p_idx][$p_idx2][$barang_id] = 0;
                        $nama_brg[$p_idx][$p_idx2][$barang_id] = $data['nama'];
                    }

                    $tmb = 0;
                    if (isset($tamb_baris[$periode][$barang_id])) {
                        $tmb = $tamb_baris[$periode][$barang_id];
                    }

                    // klo barang selanjutnya pendek
                    if ($no_baris + $data['jumlah_baris'] + $tmb <= $baris_max) {

                        if ($barang_id==6) {
                            // echo $data['jumlah_baris'] + $tmb.'<br/>';
                            // echo $no_baris + $data['jumlah_baris']  + $tmb.'<br/>';
                            // echo $baris_max.'<br/>';
                        }
                        
                        // jumlah baris di add di 1 variable jml baris
                        $jml_baris = $data['jumlah_baris'] + $tmb;
                        $total_baris[$p_idx][$p_idx2] += $jml_baris ;
                        $no_baris += $jml_baris;
                        $j_baris[$p_idx][$p_idx2][$barang_id] += $jml_baris;

                        array_push($page[$p_idx][$p_idx2],array(
                            'barang_id'=>$barang_id,
                            'bulan' => $bln_up,
                            'bulan_request' => $periode,
                            'nama'=>$data['nama'],
                            'total_request'=>$data['total_request'],                            
                            'jumlah_baris'=>$jml_baris,
                            'warna' =>$data['warna']));
                    }

                    // klo barisnya masih di bawah $baris_breakdown, tapi barang selanjutnya panjang
                    else if($no_baris <= $baris_breakdown && $no_baris + $data['jumlah_baris']  + $tmb >= $baris_max){

                        $baris_take1 = 0;
                        $baris_take2 = 0;
                        $n_data1 = [];
                        $n_data2 = [];

                        foreach ($data['warna'] as $k => $v) {
                            $tmb_warna = 0;
                            if (isset($keterangan_qty[$periode][$barang_id][$v['warna_id']])) {
                                $tmb_warna = 1;
                            }
                            $jml_baris = $v['jumlah_baris'] + $tmb_warna;
                            if ($no_baris + $jml_baris <= ($baris_max-5)) {
                                $baris_take1 += $jml_baris;
                                $no_baris +=$jml_baris;
                                $total_baris[$p_idx][$p_idx2] += $jml_baris ;
                                array_push($n_data1, $v);
                            }else{
                                $no_baris = $baris_max + 1;
                                $baris_take2 += $jml_baris;
                                array_push($n_data2, $v);

                            }
                        }

                        //generate dulu untuk halaman sblmnya
                        array_push($page[$p_idx][$p_idx2], array(
                            'barang_id'=>$barang_id,
                            'bulan' => $bln_up,
                            'bulan_request' => $periode,
                            'nama'=>$data['nama'],
                            'total_request'=>$data['total_request'],                            
                            'jumlah_baris'=>$baris_take1,
                            'warna' =>$n_data1));
                        
                        //generate untuk halaman selanjutnya
                        if ($p_idx2 % 2 == 0) {
                            $p_idx++;
                            $p_idx2 = 1;
                        }else{
                            $p_idx2++;
                        }

                        if (!isset($j_baris[$p_idx][$p_idx2][$barang_id])) {
                            $j_baris[$p_idx][$p_idx2][$barang_id] = 0;
                            $nama_brg[$p_idx][$p_idx2][$barang_id] = $data['nama'];
                        }
                        $page[$p_idx][$p_idx2] = array();
                        $total_baris[$p_idx][$p_idx2] = 0 ;

                        $no_baris = $baris_take2;
                        array_push($page[$p_idx][$p_idx2], array(
                            'barang_id'=>$barang_id,
                            'bulan' => $bln_up,
                            'bulan_request' => $periode,
                            'nama'=>$data['nama'],
                            'total_request'=>$data['total_request'],                            
                            'jumlah_baris'=>$no_baris,
                            'warna' =>$n_data2));
                        $total_baris[$p_idx][$p_idx2] = $no_baris ;
                    }
                    // klo barang selanjutnya panjang
                    else if($data['jumlah_baris'] + $tmb >= $baris_max){
                        $baris_take1 = 0;
                        $baris_take2 = 0;
                        $n_data1 = [];
                        $n_data2 = [];
                        $no_baris = 0;

                        // karena barang baru panjang dan no baris >= $baris_breakdown
                        // jadi bikin halaman baru
                        if ($p_idx2 % 2 == 0) {
                            $p_idx++;
                            $p_idx2 = 1;
                        }else{
                            $p_idx2++;
                        }

                        if (!isset($j_baris[$p_idx][$p_idx2][$barang_id])) {
                            $j_baris[$p_idx][$p_idx2][$barang_id] = 0;
                            $nama_brg[$p_idx][$p_idx2][$barang_id] = $data['nama'];
                        }
                        $page[$p_idx][$p_idx2] = array();
                        $total_baris[$p_idx][$p_idx2] = 0 ;

                        foreach ($data['warna'] as $k => $v) {
                            $tmb_warna = 0;
                            if (isset($keterangan_qty[$periode][$barang_id][$v['warna_id']])) {
                                $tmb_warna = 1;
                            }

                            $jml_baris = $v['jumlah_baris'] + $tmb_warna;
                            if ($no_baris + $jml_baris <= ($baris_max - 4)) {
                                $baris_take1 += $jml_baris;
                                $no_baris += $jml_baris;
                                $total_baris[$p_idx][$p_idx2] += $jml_baris;
                                array_push($n_data1, $v);
                            }else{
                                $no_baris = $baris_max + 1;
                                $baris_take2 += $jml_baris;
                                array_push($n_data2, $v);
                            }
                        }

                        //generate dulu untuk halaman sblmnya
                        array_push($page[$p_idx][$p_idx2], array(
                            'barang_id'=>$barang_id,
                            'bulan' => $bln_up,
                            'bulan_request' => $periode,
                            'nama'=>$data['nama'],
                            'total_request'=>$data['total_request'],                            
                            'jumlah_baris'=>$baris_take1,
                            'warna' =>$n_data1));
                        
                        //generate dulu untuk halaman selanjutnya
                        if ($p_idx2 % 2 == 0) {
                            $p_idx++;
                            $p_idx2 = 1;
                        }else{
                            $p_idx2++;
                        }

                        if (!isset($j_baris[$p_idx][$p_idx2][$barang_id])) {
                            $j_baris[$p_idx][$p_idx2][$barang_id] = 0;
                            $nama_brg[$p_idx][$p_idx2][$barang_id] = $data['nama'];
                        }
                        $page[$p_idx][$p_idx2] = array();

                        $no_baris = $baris_take2;
                        array_push($page[$p_idx][$p_idx2], array(
                            'barang_id'=>$barang_id,
                            'bulan' => $bln_up,
                            'bulan_request' => $periode,
                            'nama'=>$data['nama'],
                            'total_request'=>$data['total_request'],                            
                            'jumlah_baris'=>$no_baris,
                            'warna' =>$n_data2));
                        $total_baris[$p_idx][$p_idx2] = $no_baris ;

                    }else{
                        if ($p_idx2 % 2 == 0) {
                            $p_idx++;
                            $p_idx2 = 1;
                        }else{
                            $p_idx2++;
                        }

                        if (!isset($j_baris[$p_idx][$p_idx2][$barang_id])) {
                            $j_baris[$p_idx][$p_idx2][$barang_id] = 0;
                            $nama_brg[$p_idx][$p_idx2][$barang_id] = $data['nama'];
                        }

                        $no_baris = $data['jumlah_baris']+$tmb;
                        $page[$p_idx][$p_idx2] = array();
                        $total_baris[$p_idx][$p_idx2] = 0 ;
                        $total_baris[$p_idx][$p_idx2] += $no_baris ;

                        $j_baris[$p_idx][$p_idx2][$barang_id] += $no_baris;

                        array_push($page[$p_idx][$p_idx2],array(
                            'barang_id'=>$barang_id,
                            'bulan' => $bln_up,
                            'bulan_request' => $periode,
                            'nama'=>$data['nama'],
                            'total_request'=>$data['total_request'],                            
                            'jumlah_baris'=>$no_baris,
                            'warna' =>$data['warna']));
                    }
                    


                    /* foreach ($data['warna'] as $k => $v) {
                        if (!isset($j_baris[$p_idx][$p_idx2][$barang_id])) {
                            $j_baris[$p_idx][$p_idx2][$barang_id] = 0;
                            $nama_brg[$p_idx][$p_idx2][$barang_id] = $data['nama'];
                        }

                        if ($no_baris + $v['jumlah_baris'] <= $baris_max ) {
                            $no_baris += $v['jumlah_baris'];
                            $j_baris[$p_idx][$p_idx2][$barang_id] += $v['jumlah_baris'];
                            array_push($page[$p_idx][$p_idx2],array('barang_id'=> $barang_id,'no_baris'=>$no_baris,'data_warna' =>$v));

                            $total_baris[$p_idx][$p_idx2] += $v['jumlah_baris'] ;
                        }elseif($no_baris <= $baris_breakdown && $no_baris + $v['jumlah_baris'] >= $baris_max ){
                            $sisa_baris = ($baris_max - 5) - $no_baris;
                            $n_array = array(
                                'nama' => $v['nama'],
                                'jumlah_baris' => $sisa_baris,
                                'qty_warna' => $v['qty_warna'],
                                'data' => array_splice($v['data'],0, $sisa_baris)
                            );
                            $sisa_baris2 = $v['jumlah_baris'] - $sisa_baris;
                            $n_array2 = array(
                                'nama' => $v['nama'],
                                'jumlah_baris' => $sisa_baris,
                                'qty_warna' => $v['qty_warna'],
                                'data' => array_splice($v['data'],$sisa_baris+1)
                            );

                            $j_baris[$p_idx][$p_idx2][$barang_id] += $sisa_baris;
                            $total_baris[$p_idx][$p_idx2] += $sisa_baris ;
                            array_push($page[$p_idx][$p_idx2],array('barang_id'=> $barang_id,'no_baris'=>$total_baris[$p_idx][$p_idx2],'data_warna' =>$n_array));


                            if ($p_idx2 % 2 == 0) {
                                $p_idx++;
                                $p_idx2 = 1;
                            }else{
                                $p_idx2++;
                            }
                            $total_baris[$p_idx][$p_idx2] = $sisa_baris2 ;
                            $page[$p_idx][$p_idx2] = [];
                            $no_baris = $sisa_baris2;

                            if (!isset($j_baris[$p_idx][$p_idx2][$barang_id])) {
                                $j_baris[$p_idx][$p_idx2][$barang_id] = 0;
                                $nama_brg[$p_idx][$p_idx2][$barang_id] = $data['nama'];
                            }

                            $j_baris[$p_idx][$p_idx2][$barang_id] += $sisa_baris2;
                            array_push($page[$p_idx][$p_idx2],array('barang_id'=> $barang_id,'no_baris'=>$no_baris,'data_warna' =>$n_array2));
                        }elseif($no_baris + $v['jumlah_baris'] >= $baris_max ){
                            if ($p_idx2 % 2 == 0) {
                                $p_idx++;
                                $p_idx2 = 1;
                            }else{
                                $p_idx2++;
                            }
                            $page[$p_idx][$p_idx2] = [];

                            if (!isset($j_baris[$p_idx][$p_idx2][$barang_id])) {
                                $j_baris[$p_idx][$p_idx2][$barang_id] = 0;
                                $nama_brg[$p_idx][$p_idx2][$barang_id] = $data['nama'];
                            }

                            $j_baris[$p_idx][$p_idx2][$barang_id] += $v['jumlah_baris'];
                            $no_baris = $v['jumlah_baris'];
                            $total_baris[$p_idx][$p_idx2] = $v['jumlah_baris'] ;
                            array_push($page[$p_idx][$p_idx2],array('barang_id'=> $barang_id,'no_baris'=>$no_baris,'data_warna' =>$v));
                        }

                    } */
				}
				
			}
			
            $page_footer[$p_idx][$p_idx2] = true;

			// $yAxisBefore = $pdf->getY();
			// $yAxisAfter = $pdf->getY();
			
		}
        
        if ($data_view == 1) {

            print_r($page_footer);
            echo '<hr>';

            foreach ($page as $p_index => $val) {
                echo "PAGE $p_index";
                echo '<br/>';
                foreach ($val as $position => $val2) {
                    echo "POSITION $position";    
                    echo '<br/>';
                    echo "TOTAL_BARIS ".$total_baris[$p_index][$position];
                    echo '<br/>';
                    foreach ($val2 as $k => $v) {
                        echo $k.'xx';
                        echo $v['nama'].' == '.$v['barang_id'].' '.$v['jumlah_baris'].'<br/>';
                        // print_r($v['warna']);
                        foreach ($v['warna'] as $k2 => $v2) {
                            echo $k2.'--'.$v2['nama'].'--'.$v2['qty_warna'].'--'.$v2['terkirim'].'__';
                            echo $v2['jumlah_baris'].'<br/>';
                            print_r($v2['data']);
                            echo '<br/>';
                            echo '<br/>';
                        }
                        echo '<hr/>';
                    }
                    echo '<hr/>';
                }
            }
        }else{
            include_once 'generate_request_mode_5_write_pdf.php';
        }


		$pdf->Output( 'request_barang.pdf', "I" );
					