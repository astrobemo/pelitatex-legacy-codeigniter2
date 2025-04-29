<?php

      
            $font_size = 10;

            // lebar kolom
            $frame_detail = '';
            $frame_detail_height = 4;

            //cetak row barang            
            $pdf->SetFont('Calibri', '', $font_size);

            $pdf->ln(1);

            $count = 0;

            for ($i = $for; $i <= $until; $i++) {
                  if (isset($barang[$i][4])) {
                        $count++;

                        $pdf->Cell($this->col_sj['no'], $frame_detail_height, $i + 1, $frame_detail, 0, 'C');
                        $pdf->Cell($this->col_sj['jumlah'], $frame_detail_height, format_angka($barang[$i][0]), $frame_detail, 0, 'C');
                        $pdf->Cell($this->col_sj['satuan'], $frame_detail_height, strtoupper($barang[$i][1]), $frame_detail, 0, 'C');
                        $pdf->Cell($this->col_sj['roll'], $frame_detail_height, format_angka($barang[$i][2]), $frame_detail, 0, 'C');
                        $pdf->Cell($this->col_sj['barang'], $frame_detail_height, $barang[$i][3], $frame_detail, 0, 'L');
                        if ($tipe == 1) {
                              $pdf->Cell($this->col_sj['harga'], $frame_detail_height, $this->valuta . format_angka($barang[$i][4]), $frame_detail, 1, 'R'); //harga
                        }else{
                              $pdf->Cell(0, 0, '', '', 1);
                        }
                  }
            }

            return $count;
      