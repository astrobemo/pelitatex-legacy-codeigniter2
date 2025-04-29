<?php
      $font_judul = 14;
      $font_tanggal = 11;
      $font_perusahaan = 12;
      $font_header = 10;
      $font_nomor = 11;
      $height_header = 4;
      $is_pengiriman = false;

      $id = $this->uri->segment(4);
      $is_pengiriman = $this->common_model->db_select("nd_penjualan_log WHERE penjualan_id = $id");

      // line 1
      $pdf->SetFont('Arial', 'B', $font_judul);
      $pdf->Cell(39, 6, strtoupper($judul), 1, 0, 'C');

      $pdf->SetFont('Arial', '', $font_tanggal);
      $pdf->Cell(161, 6, strtoupper($profile['kota'] . ' ' . $header['tanggal']), 0, 1, 'R');

      // line 2
      $pdf->ln(-0.5);
      $pdf->SetFont('Calibri', '', $font_header);
      $pdf->Cell(200, $height_header, 'KEPADA YTH.', 0, 1, 'R');

      // line 2
      $pdf->ln(-1.5);
      $pdf->SetFont('Arial', 'B', $font_perusahaan);
      $pdf->Cell(80, $height_header, strtoupper($profile['nama']), 0, 0, 'L');

      $pdf->ln(1.5);
      $pdf->SetFont('Calibri', '', $font_header);
      $pdf->Cell(200, $height_header, $header['nama_customer'], 0, 1, 'R');

      // line 3
      $pdf->ln(-0.5);
      $pdf->SetFont('Calibri', '', $font_header);
      $pdf->Cell(80, $height_header, $profile['alamat1'], 0, 0, 'L');

      $pdf->SetFont('Calibri', '', $font_header);
      $pdf->Cell(120, $height_header, $header['alamat_customer1'], 0, 1, 'R');

      // line 4
      $pdf->SetFont('Calibri', '', $font_header);
      $pdf->Cell(80, $height_header, $profile['alamat2'], 0, 0, 'L');

      $pdf->SetFont('Calibri', '', $font_header);
      $pdf->Cell(120, $height_header, $header['alamat_customer2'], 0, 1, 'R');

      // line 5
      $pdf->SetFont('Calibri', '', $font_header);
      $pdf->Cell(80, $height_header, $profile['npwp'], 0, 0, 'l');

      $pdf->SetFont('Calibri', '', $font_header);
      $pdf->Cell(120, $height_header, $header['alamat_customer3'], 0, 1, 'R');

      $pdf->ln(1);

      // penomoran (bisa flexible bertambah ke kanan)
      $pdf->SetFont('Calibri', '', $font_nomor);

      $no_po = 'PO : ' . $header['no_po'];
      $no_sj = 'SJ : ' . $header['no_sj'];
      $no_invoice = $header['no_invoice'];

      $x_po = $pdf->getStringWidth($no_po) + 15;
      $x_sj = $pdf->getStringWidth($no_sj) + 15;
      $x_invoice_flag = $pdf->getStringWidth('INVOICE :  ');
      $x_invoice = $pdf->getStringWidth($no_invoice);
      $x_batas = $this->total_width - $x_po - $x_sj - $x_invoice - $x_invoice_flag;

      $pdf->Cell($x_po, 6, $no_po, 'T', 0, 'L', 0);
      $pdf->Cell($x_sj, 6, ($is_pengiriman ? $no_sj : ''), 'T', 0, 'L');
      $pdf->Cell($x_batas, 6, '', 'T', 0, 'L');
      // $pdf->SetFont('Calibri', '', $font_nomor);
      $pdf->Cell($x_invoice_flag, 6, 'INVOICE :  ', 'T', 0, 'R');
      // $pdf->SetFont('Arial', '', $font_nomor);
      $pdf->Cell($x_invoice, 6, $no_invoice, 'T', 1, 'R');
