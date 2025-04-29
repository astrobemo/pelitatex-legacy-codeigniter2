<?php

$pl_source = array();
            $no = 0;

            $kode_merge = '';
            $warna_merge = '';
            $merge = '';

            for ($i = 0; $i < count($pl1); $i++) {
                  if ($kode_merge == $pl1[$i][1] && $warna_merge == $pl1[$i][2]) {
                        $merge = 'Y';
                  } else {
                        $merge = '';
                  }

                  $kode_merge = $pl1[$i][1];
                  $warna_merge = $pl1[$i][2];

                  $no++;
                  $satuan = $pl1[$i][3];
                  $roll = $pl1[$i][4];
                  $total = $pl1[$i][5];

                  // label kode
                  $kar_kode = ceil(strlen($pl1[$i][1]) / 17);
                  $kode = array();

                  if ($kar_kode <= 1) {
                        array_push($kode, substr($pl1[$i][1], 0, 17));
                  } else {
                        array_push($kode, substr($pl1[$i][1], 0, 17));
                        array_push($kode, trim(substr($pl1[$i][1], 16, 1000)));
                  }

                  // label warna
                  $kar_warna = ceil(strlen($pl1[$i][2])) / 13;
                  $warna = array();
                  

                  //nama warna ada di var $pl1[$i][2]
                  if ($kar_warna <= 1) {
                        array_push($warna, substr($pl1[$i][2], 0, 13));
                  } else {
                        array_push($warna, substr($pl1[$i][2], 0, 13));
                        array_push($warna, trim(substr($pl1[$i][2], 13, 1000)));

                        $last_1 = substr($warna[0], -1,1);
                        $last_2 = substr($warna[1], 0,1);

                        $positions = array();
                        $pos = -1;
                        while (($pos = strpos(trim($pl1[$i][2])," ", $pos+1 )) !== false) {
                              $positions[] = $pos;
                        }

                        $warna = array();
                        $max = 47;
                        if ($last_1 != '' && $last_2 != '') {
                              $posisi = array_filter(array_reverse($positions),
                                    function($value) use ($max) {
                                          return $value <= $max;
                                    });

                              $posisi = array_values($posisi);

                              array_push($warna, substr(trim($pl1[$i][2]), 0,$posisi[0]));
                              array_push($warna, trim(substr(trim($pl1[$i][2]), $posisi[0])));
                        }
                  }

                  // packing list
                  $pl = $pl1[$i][6];
                  $exp_pl = explode(' ', $pl);
                  $kar_pl = ceil(count($exp_pl) / 10);

                  $pl_res = array();
                  $pl_count = 0;

                  // per jumlah baris
                  for ($i1 = 0; $i1 < $kar_pl; $i1++) {
                        $plx1 = isset($exp_pl[$pl_count]) ? $exp_pl[$pl_count] : null;
                        $plx2 = isset($exp_pl[$pl_count + 1]) ? $exp_pl[$pl_count + 1] : null;
                        $plx3 = isset($exp_pl[$pl_count + 2]) ? $exp_pl[$pl_count + 2] : null;
                        $plx4 = isset($exp_pl[$pl_count + 3]) ? $exp_pl[$pl_count + 3] : null;
                        $plx5 = isset($exp_pl[$pl_count + 4]) ? $exp_pl[$pl_count + 4] : null;
                        $plx6 = isset($exp_pl[$pl_count + 5]) ? $exp_pl[$pl_count + 5] : null;
                        $plx7 = isset($exp_pl[$pl_count + 6]) ? $exp_pl[$pl_count + 6] : null;
                        $plx8 = isset($exp_pl[$pl_count + 7]) ? $exp_pl[$pl_count + 7] : null;
                        $plx9 = isset($exp_pl[$pl_count + 8]) ? $exp_pl[$pl_count + 8] : null;
                        $plx10 = isset($exp_pl[$pl_count + 9]) ? $exp_pl[$pl_count + 9] : null;

                        array_push($pl_res, array($plx1, $plx2, $plx3, $plx4, $plx5, $plx6, $plx7, $plx8, $plx9, $plx10));

                        $pl_count = $pl_count + 10;
                  }

                  // hitung row terbanyak
                  $estimate_row  = count($kode);

                  if ($estimate_row < count($warna))
                        $estimate_row = count($warna);
                  else if ($estimate_row < $kar_pl)
                        $estimate_row = $kar_pl;

                  for ($j = 0; $j < $estimate_row; $j++) {
                        $kode_x = isset($kode[$j]) ? $kode[$j] : '';
                        $warna_x = isset($warna[$j]) ? $warna[$j] : '';
                        $pl11 = isset($pl_res[$j][0]) ? format_angka($pl_res[$j][0]) : '';
                        $pl12 = isset($pl_res[$j][1]) ? format_angka($pl_res[$j][1]) : '';
                        $pl13 = isset($pl_res[$j][2]) ? format_angka($pl_res[$j][2]) : '';
                        $pl14 = isset($pl_res[$j][3]) ? format_angka($pl_res[$j][3]) : '';
                        $pl15 = isset($pl_res[$j][4]) ? format_angka($pl_res[$j][4]) : '';
                        $pl16 = isset($pl_res[$j][5]) ? format_angka($pl_res[$j][5]) : '';
                        $pl17 = isset($pl_res[$j][6]) ? format_angka($pl_res[$j][6]) : '';
                        $pl18 = isset($pl_res[$j][7]) ? format_angka($pl_res[$j][7]) : '';
                        $pl19 = isset($pl_res[$j][8]) ? format_angka($pl_res[$j][8]) : '';
                        $pl110 = isset($pl_res[$j][9]) ? format_angka($pl_res[$j][9]) : '';

                        array_push($pl_source, array($no, $kode_x, $warna_x, $satuan, $roll, $total, $pl11, $pl12, $pl13, $pl14, $pl15, $pl16, $pl17, $pl18, $pl19, $pl110, $merge));
                  }
            }

            //setup
            $frame_detail = '';
            $frame_detail_height = 4.5;

            $nomor = 0;
            $counter = 0;
            $page_ke = 1;
            $visible = true;

            for ($x = 0; $x < count($pl_source); $x++) {
                  $visible = true;

                  if ($nomor == $pl_source[$x][0]) {
                        if ($kode == '' && $warna == '')
                              $visible = false;
                  }

                  if ($visible == true) {
                        if ($x == 0) {
                              $frame_detail = '';
                        } else {
                              $frame_detail = $nomor == $pl_source[$x][0] ? '' : 'T';
                        }

                        if ($pl_source[$x][16] == 'Y') {
                              $kode = '';
                              $warna = '';
                              $unit = '';
                              $roll = '';
                              $total = '';
                        } else {
                              $kode = $pl_source[$x][1];
                              $warna = $pl_source[$x][2];
                              $unit = $kode == '' || $warna == '' ? '' : $pl_source[$x][3];
                              $roll = $kode == '' || $warna == '' ? '' : $pl_source[$x][4];
                              $total = $kode == '' || $warna == '' ? '' : format_angka($pl_source[$x][5]);
                        }

                        $nomor = $pl_source[$x][0];
                        $pl1 = $pl_source[$x][6];
                        $pl2 = $pl_source[$x][7];
                        $pl3 = $pl_source[$x][8];
                        $pl4 = $pl_source[$x][9];
                        $pl5 = $pl_source[$x][10];
                        $pl6 = $pl_source[$x][11];
                        $pl7 = $pl_source[$x][12];
                        $pl8 = $pl_source[$x][13];
                        $pl9 = $pl_source[$x][14];
                        $pl10 = $pl_source[$x][15];

                        $counter++;

                        if ($counter == 1) {
                              $this->print_packing_list_header($pdf);
                        }

                        $pdf->SetFont('Calibri', '', 9.5);

                        $pdf->Cell($this->col_pl['kode'], $frame_detail_height, $kode,  $frame_detail, 0, 'L');
                        $pdf->Cell($this->col_pl['warna'], $frame_detail_height, $warna, $frame_detail . '', 0, 'L');
                        $pdf->Cell($this->col_pl['unit'], $frame_detail_height, $unit, $frame_detail . '', 0, 'C');
                        $pdf->Cell($this->col_pl['roll'], $frame_detail_height, $roll, $frame_detail . '', 0, 'C');
                        $pdf->Cell($this->col_pl['total'], $frame_detail_height, $total, $frame_detail . '', 0, 'C');
                        $pdf->Cell($this->col_pl['detail'], $frame_detail_height, $pl1, 'L' . $frame_detail, 0, 'R');
                        $pdf->Cell($this->col_pl['detail'], $frame_detail_height, $pl2, '' . $frame_detail, 0, 'R');
                        $pdf->Cell($this->col_pl['detail'], $frame_detail_height, $pl3, '' . $frame_detail, 0, 'R');
                        $pdf->Cell($this->col_pl['detail'], $frame_detail_height, $pl4, '' . $frame_detail, 0, 'R');
                        $pdf->Cell($this->col_pl['detail'], $frame_detail_height, $pl5, '' . $frame_detail, 0, 'R');
                        $pdf->Cell($this->col_pl['detail'], $frame_detail_height, $pl6, '' . $frame_detail, 0, 'R');
                        $pdf->Cell($this->col_pl['detail'], $frame_detail_height, $pl7, '' . $frame_detail, 0, 'R');
                        $pdf->Cell($this->col_pl['detail'], $frame_detail_height, $pl8, '' . $frame_detail, 0, 'R');
                        $pdf->Cell($this->col_pl['detail'], $frame_detail_height, $pl9, '' . $frame_detail, 0, 'R');
                        $pdf->Cell($this->col_pl['detail'], $frame_detail_height, $pl10, '' . $frame_detail, 1, 'R');

                        if ($page_ke == 1) {
                              if ($counter >= 22) {
                                    $counter = 0;
                                    $page_ke++;
                                    $this->print_halaman($pdf);
                                    $pdf->addPage();
                              }
                        } else {
                              if ($counter >= 25) {
                                    $counter = 0;
                                    $page_ke++;
                                    $this->print_halaman($pdf);
                                    $pdf->addPage();
                              }
                        }
                  }
            }

            $pdf->Cell($this->total_width, 1, '', "T", 1, 'C');