<?php
      ////////////////////////////////////////////////////////////////////////////////////////////////
      // PRINT BARANG
      ////////////////////////////////////////////////////////////////////////////////////////////////
            $font_size  = 11;

            $frame_header = '';
            $frame_header_height = 6;

            $pdf->SetFont('Calibri', '', $font_size);

            $pdf->Cell($this->col_barang['no'], $frame_header_height, 'NO.', 'TB' . $frame_header, 0, 'C');
            $pdf->Cell($this->col_barang['jumlah'], $frame_header_height, 'JUMLAH', 'TB' . $frame_header, 0, 'C');
            $pdf->Cell($this->col_barang['satuan'], $frame_header_height, 'SATUAN', 'TB' . $frame_header, 0, 'C');
            $pdf->Cell($this->col_barang['roll'], $frame_header_height, 'ROLL', 'TB' . $frame_header, 0, 'C');
            $pdf->Cell($this->col_barang['kode_beli'], $frame_header_height, 'KODE', 'TB' . $frame_header, 0, 'C');
            $pdf->Cell($this->col_barang['barang'], $frame_header_height, 'BARANG', 'TB' . $frame_header, 0, 'L');
            $pdf->Cell($this->col_barang['harga'], $frame_header_height, 'HARGA', 'TB' . $frame_header, 0, 'L');
            $pdf->Cell($this->col_barang['total'] - $this->col_barang['space'], $frame_header_height, 'TOTAL', 'TB' . $frame_header, 0, 'R');
            $pdf->Cell($this->col_barang['space'], $frame_header_height, '', 'TB' . $frame_header, 0, 'C');
            $pdf->Cell($this->col_barang['space'], $frame_header_height, '', 'TB' . $frame_header, 1, 'C');

