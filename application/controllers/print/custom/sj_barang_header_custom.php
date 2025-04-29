<?php

            $font_size  = 11;

            $frame_header = '';
            $frame_header_height = 6;

            $pdf->SetFont('Calibri', '', $font_size);

            $pdf->Cell($this->col_sj['no'], $frame_header_height, 'NO.', 'TB' . $frame_header, 0, 'C');
            $pdf->Cell($this->col_sj['jumlah'], $frame_header_height, 'JUMLAH', 'TB' . $frame_header, 0, 'C');
            $pdf->Cell($this->col_sj['satuan'], $frame_header_height, 'SATUAN', 'TB' . $frame_header, 0, 'C');
            $pdf->Cell($this->col_sj['roll'], $frame_header_height, 'ROLL', 'TB' . $frame_header, 0, 'C');
            $pdf->Cell($this->col_sj['space'], $frame_header_height, '', 'TB' . $frame_header, 0, 'C');
            $pdf->Cell($this->col_sj['barang'], $frame_header_height, 'BARANG', 'TB' . $frame_header, 0, 'L');
            if ($this->tipe_print == 1) {
                $pdf->Cell($this->col_sj['harga'], $frame_header_height, 'HARGA', 'TB' . $frame_header, 1, 'C');
            }else{
                $pdf->Cell($this->col_sj['harga'], $frame_header_height, '', 'TB' . $frame_header, 1, 'C');
            }
      