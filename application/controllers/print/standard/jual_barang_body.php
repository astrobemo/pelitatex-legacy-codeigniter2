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

            $pdf->Cell($this->col_barang['no'], $frame_detail_height, $i + 1, $frame_detail, 0, 'C');
            $pdf->Cell($this->col_barang['jumlah'], $frame_detail_height, format_angka($barang[$i][0]), $frame_detail, 0, 'C');
            $pdf->Cell($this->col_barang['satuan'], $frame_detail_height, strtoupper($barang[$i][1]), $frame_detail, 0, 'C');
            $pdf->Cell($this->col_barang['roll'], $frame_detail_height, format_angka($barang[$i][2]), $frame_detail, 0, 'C');
            $pdf->Cell($this->col_barang['barang'], $frame_detail_height, $barang[$i][3], $frame_detail, 0, 'L');
            $pdf->Cell($this->col_barang['space'], $frame_detail_height, '', $frame_detail, 0, 'L');
            $pdf->Cell($this->col_barang['harga'], $frame_detail_height, $this->valuta . format_angka($barang[$i][4]), $frame_detail, 0, 'R'); //harga
            $pdf->Cell($this->col_barang['total'], $frame_detail_height, $this->valuta . format_angka($barang[$i][5]), $frame_detail, 0, 'R'); //total harga
            $pdf->Cell($this->col_barang['space'], $frame_detail_height, '', $frame_detail, 1, 'L');
      }
}

return $count;