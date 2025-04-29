<?php

     
            // inisiasi
            $x_font_size = 11;
            $x_height = 4.5;

            // unit 
            $pdf->ln(1);

            $frame_unit = '';

            $pdf->SetFont('Calibri', '', $x_font_size);

            for ($i = 0; $i <= count($unit) - 1; $i++) {
                  if ($i == 0) {
                        if ($i == count($unit) - 1)
                              $frame_unit = 'B';

                        //record ke 0
                        $pdf->Cell($this->col_sj['no'], $x_height, 'TOTAL', 'TL' . $frame_unit, 0, 'L');
                        $pdf->Cell($this->col_sj['jumlah'], $x_height, format_angka($unit[$i][1]), 'T' . $frame_unit, 0, 'C');
                        $pdf->Cell($this->col_sj['satuan'], $x_height, strtoupper($unit[$i][0]), 'T' . $frame_unit, 0, 'C');
                        $pdf->Cell($this->col_sj['roll'], $x_height, format_angka($unit[$i][2]), 'TR' . $frame_unit, 0, 'C');
                        $pdf->Cell($this->col_sj['space'] + $this->col_sj['barang'], $x_height, '', 0, 1, 'B');
                  } else {
                        if ($i == count($unit) - 1)
                              $frame_unit = 'B';
                        else
                              $frame_unit = '';

                        //record selanjutnya
                        $pdf->Cell($this->col_sj['no'], $x_height, '', 'L' . $frame_unit, 0, 'L');
                        $pdf->Cell($this->col_sj['jumlah'], $x_height, format_angka($unit[$i][1]), $frame_unit, 0, 'C');
                        $pdf->Cell($this->col_sj['satuan'], $x_height, strtoupper($unit[$i][0]), $frame_unit, 0, 'C');
                        $pdf->Cell($this->col_sj['roll'], $x_height, format_angka($unit[$i][2]), 'R' . $frame_unit, 0, 'C');
                        $pdf->Cell($this->col_sj['space'] + $this->col_sj['barang'], $x_height, '', 0, 1, 'B');
                  }
            }

            $pdf->ln(1);