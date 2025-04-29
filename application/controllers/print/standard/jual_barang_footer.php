<?php
      
            // inisiasi
            $x_font_size = 11;
            $x_height = 4.5;

            $font_size_kembali = 11;
            $height_kembali = 6;

            // unit 
            $pdf->ln(1);

            $frame_unit = '';
            $frame_bayar = '';

            // menjumlahkan pembayaran
            $total_bayar = 0;

            foreach ($bayar as $bayars) {
                  $total_bayar += $bayars[1];
            }

            $kembalian = $total_bayar - $grand_total;

            $label_kembalian  = 'KEMBALI';

            if (count($unit) < count($bayar) + 1) {
                  //kalau panjang unit lebih sedikit atau sama dengan panjang bayar
                  for ($i = 0; $i <= count($bayar); $i++) {
                        $pdf->SetFont('Calibri', '', $x_font_size);

                        $frame_unit = '';
                        $frame_bayar = '';

                        //cetak i dibawah unit
                        if ($i <= count($unit) - 1) {
                              if ($i == 0) {
                                    //record awal
                                    //kalau tidak ada record selanjutnya, maka beri garis bawah
                                    if (count($unit) == 1)
                                          $frame_unit = 'B';

                                    $pdf->Cell($this->col_barang['no'], $x_height, 'TOTAL', 'TL' . $frame_unit, 0, 'L');
                                    $pdf->Cell($this->col_barang['jumlah'], $x_height, format_angka($unit[$i][1]), 'T' . $frame_unit, 0, 'C');
                                    $pdf->Cell($this->col_barang['satuan'], $x_height, strtoupper($unit[$i][0]), 'T' . $frame_unit, 0, 'C');
                                    $pdf->Cell($this->col_barang['roll'], $x_height, format_angka($unit[$i][2]), 'TR' . $frame_unit, 0, 'C');

                                    $pdf->Cell($this->col_barang['space'] + $this->col_barang['barang'], $x_height, '', 0, 0, 'B'); //space barang

                                    $pdf->Cell($this->col_barang['harga'], $x_height, 'TOTAL *', 'LT' . $frame_bayar, 0, 'L');
                                    $pdf->Cell($this->col_barang['total'], $x_height, $this->valuta . format_angka($grand_total), 'TR' . $frame_bayar, 1, 'R');
                              } else {
                                    //kalau record terakhir, maka beri garis bawah
                                    if ($i == count($unit) - 1)
                                          $frame_unit = 'B';
                                    else
                                          $frame_unit = '';

                                    $pdf->Cell($this->col_barang['no'], $x_height, '', 'L' . $frame_unit, 0, 'L');
                                    $pdf->Cell($this->col_barang['jumlah'], $x_height, format_angka($unit[$i][1]), '' . $frame_unit, 0, 'C');
                                    $pdf->Cell($this->col_barang['satuan'], $x_height, strtoupper($unit[$i][0]), '' . $frame_unit, 0, 'C');
                                    $pdf->Cell($this->col_barang['roll'], $x_height, format_angka($unit[$i][2]), 'R' . $frame_unit, 0, 'C');

                                    $pdf->Cell($this->col_barang['space'] + $this->col_barang['barang'], $x_height, '', 0, 0, 'B'); //space barang

                                    $pdf->Cell($this->col_barang['harga'], $x_height, $bayar[$i - 1][0], 'L', 0, 'L');
                                    $pdf->Cell($this->col_barang['total'], $x_height, $this->valuta . format_angka($bayar[$i - 1][1]), 'R', 1, 'R');
                              }
                        } else {
                              if ($i <= count($bayar)) {
                                    //kalau masih ada pembayaran
                                    $pdf->Cell($this->col_barang['no'] + $this->col_barang['jumlah'] + $this->col_barang['satuan'] + $this->col_barang['roll'], $x_height, '', '', 0, 'L'); //unit kosong
                                    $pdf->Cell($this->col_barang['space'] + $this->col_barang['barang'], $x_height, '', 0, 0, 'B'); //space barang

                                    $pdf->Cell($this->col_barang['harga'], $x_height, $bayar[$i - 1][0], 'L', 0, 'L');
                                    $pdf->Cell($this->col_barang['total'], $x_height, $this->valuta . format_angka($bayar[$i - 1][1]), 'R', 1, 'R');
                              }

                              if ($i + 1 > count($bayar)) {
                                    // kalau sudah tidak ada pembayaran 
                                    $pdf->Cell($this->col_barang['no'] + $this->col_barang['jumlah'] + $this->col_barang['satuan'] + $this->col_barang['roll'], $x_height, '', '', 0, 'L'); //unit kosong
                                    $pdf->Cell($this->col_barang['space'] + $this->col_barang['barang'], $x_height, '', 0, 0, 'B'); //space barang

                                    $pdf->SetFont('Calibri', '', $font_size_kembali);

                                    $pdf->Cell($this->col_barang['harga'], $height_kembali, $label_kembalian, 'LTB', 0, 'L');
                                    $pdf->Cell($this->col_barang['total'], $height_kembali, $this->valuta . format_angka($kembalian), 'RTB', 1, 'R');
                              }
                        }
                  }
            }

            if (count($unit) > count($bayar) + 1) {
                  //kalau panjang unit lebih besar dari pada panjang bayar
                  $frame_unit = '';
                  $frame_bayar = '';

                  $clossing_kembalian = 0;

                  for ($i = 0; $i <= count($unit) - 1; $i++) {
                        $pdf->SetFont('Calibri', '', $x_font_size);

                        if ($i == 0) {
                              //record ke 0
                              $pdf->Cell($this->col_barang['no'], $x_height, 'TOTAL', 'TL' . $frame_unit, 0, 'L');
                              $pdf->Cell($this->col_barang['jumlah'], $x_height, format_angka($unit[$i][1]), 'T' . $frame_unit, 0, 'C');
                              $pdf->Cell($this->col_barang['satuan'], $x_height, strtoupper($unit[$i][0]), 'T' . $frame_unit, 0, 'C');
                              $pdf->Cell($this->col_barang['roll'], $x_height, format_angka($unit[$i][2]), 'TR' . $frame_unit, 0, 'C');

                              $pdf->Cell($this->col_barang['space'] + $this->col_barang['barang'], $x_height, '', 0, 0, 'B'); //space barang

                              if (count($bayar) <= 0)
                                    $frame_bayar =  'B';
                              else
                                    $frame_bayar = '';

                              $pdf->Cell($this->col_barang['harga'], $x_height, 'TOTAL *', 'LT' . $frame_bayar, 0, 'L');
                              $pdf->Cell($this->col_barang['total'], $x_height, $this->valuta . format_angka($grand_total), 'TR' . $frame_bayar, 1, 'R');
                        } else {
                              if ($i == count($unit) - 1)
                                    $frame_unit = 'B';
                              else
                                    $frame_unit = '';

                              //record selanjutnya
                              $pdf->Cell($this->col_barang['no'], $x_height, '', 'L' . $frame_unit, 0, 'L');
                              $pdf->Cell($this->col_barang['jumlah'], $x_height, format_angka($unit[$i][1]), $frame_unit, 0, 'C');
                              $pdf->Cell($this->col_barang['satuan'], $x_height, strtoupper($unit[$i][0]), $frame_unit, 0, 'C');
                              $pdf->Cell($this->col_barang['roll'], $x_height, format_angka($unit[$i][2]), 'R' . $frame_unit, 0, 'C');

                              $pdf->Cell($this->col_barang['space']  + $this->col_barang['barang'], $x_height, '', 0, 0, 'B'); //space barang

                              if ($i <= count($bayar)) {
                                    //kalau ada pembayaran
                                    $pdf->Cell($this->col_barang['harga'], $x_height, $bayar[$i - 1][0], 'L' . $frame_bayar, 0, 'L');
                                    $pdf->Cell($this->col_barang['total'], $x_height, $this->valuta . format_angka($bayar[$i - 1][1]), 'R' . $frame_bayar, 1, 'R');
                              } else {
                                    if ($total_bayar > 0) {
                                          //kalau tidak ada pembayaran
                                          if ($clossing_kembalian == 0) {
                                                $pdf->Cell($this->col_barang['harga'], $x_height, $label_kembalian, 'LTB', 0, 'L');
                                                $pdf->Cell($this->col_barang['total'], $x_height, $this->valuta . format_angka($kembalian), 'RTB', 1, 'R');

                                                $clossing_kembalian = 1;
                                          } else {
                                                $pdf->Cell($this->col_barang['harga'], $x_height, '', '', 1, 'L');
                                          }
                                    } else {
                                          $pdf->Cell($this->col_barang['harga'], $x_height, '', '', 1, 'L');
                                    }
                              }

                              if (count($bayar) + 1 > count($unit)) {
                                    $pdf->SetFont('Calibri', '', $font_size_kembali);

                                    $pdf->Cell($this->col_barang['harga'], $height_kembali, $label_kembalian, 'LTB', 0, 'L');
                                    $pdf->Cell($this->col_barang['total'], $height_kembali, $this->valuta . format_angka($kembalian), 'RTB', 1, 'R');
                              }
                        }
                  }
            }

            if (count($unit) == count($bayar) + 1) {
                  //kalau panjang unit sama dengan panjang bayar
                  $frame_unit = '';
                  $frame_bayar = '';

                  for ($i = 0; $i <= count($unit); $i++) {
                        $pdf->SetFont('Calibri', '', $x_font_size);

                        if ($i == 0) {
                              //record ke 0
                              if (count($unit) <= 1)
                                    $frame_unit = 'B';
                              else
                                    $frame_unit = '';

                              $pdf->Cell($this->col_barang['no'], $x_height, 'TOTAL', 'TL' . $frame_unit, 0, 'L');
                              $pdf->Cell($this->col_barang['jumlah'], $x_height, format_angka($unit[$i][1]), 'T' . $frame_unit, 0, 'C');
                              $pdf->Cell($this->col_barang['satuan'], $x_height, strtoupper($unit[$i][0]), 'T' . $frame_unit, 0, 'C');
                              $pdf->Cell($this->col_barang['roll'], $x_height, format_angka($unit[$i][2]), 'TR' . $frame_unit, 0, 'C');

                              $pdf->Cell($this->col_barang['space'] + $this->col_barang['barang'], $x_height, '', 0, 0, 'B'); //space barang

                              if (count($bayar) <= 0)
                                    $frame_bayar =  'B';
                              else
                                    $frame_bayar = '';

                              $pdf->Cell($this->col_barang['harga'], $x_height, 'TOTAL *', 'LT' . $frame_bayar, 0, 'L');
                              $pdf->Cell($this->col_barang['total'], $x_height, $this->valuta . format_angka($grand_total), 'TR' . $frame_bayar, 1, 'R');
                        } else {
                              if ($i <= count($unit) - 1) {
                                    if ($i == count($unit) - 1)
                                          $frame_unit = 'B';
                                    else
                                          $frame_unit = '';

                                    //record selanjutnya
                                    $pdf->Cell($this->col_barang['no'], $x_height, '', 'L' . $frame_unit, 0, 'L');
                                    $pdf->Cell($this->col_barang['jumlah'], $x_height, format_angka($unit[$i][1]), $frame_unit, 0, 'C');
                                    $pdf->Cell($this->col_barang['satuan'], $x_height, strtoupper($unit[$i][0]), $frame_unit, 0, 'C');
                                    $pdf->Cell($this->col_barang['roll'], $x_height, format_angka($unit[$i][2]), 'R' . $frame_unit, 0, 'C');

                                    $pdf->Cell($this->col_barang['space'] + $this->col_barang['barang'], $x_height, '', 0, 0, 'B'); //space barang

                                    $pdf->Cell($this->col_barang['harga'], $x_height, $bayar[$i - 1][0], 'L', 0, 'L');
                                    $pdf->Cell($this->col_barang['total'], $x_height, $this->valuta . format_angka($bayar[$i - 1][1]), 'R', 1, 'R');
                              } else {
                                    if (count($bayar) > 0) {
                                          $pdf->Cell($this->col_barang['no'] + $this->col_barang['jumlah'] + $this->col_barang['satuan'] + $this->col_barang['roll'] + $this->col_barang['space'] + $this->col_barang['barang'], $x_height, '', '', 0, 'L');

                                          $pdf->SetFont('Calibri', '', $font_size_kembali);

                                          $pdf->Cell($this->col_barang['harga'], $height_kembali, $label_kembalian, 'LTB', 0, 'L');
                                          $pdf->Cell($this->col_barang['total'], $height_kembali, $this->valuta . format_angka($kembalian), 'TRB', 1, 'R');
                                    }
                              }
                        }
                  }
            }

            //info
            $pdf->SetFont('Calibri', 'I', 7.5);
            $pdf->Cell($this->total_width, 3, '* harga sudah termasuk ppn', 0, 1, 'R');
            $pdf->ln(1);
      