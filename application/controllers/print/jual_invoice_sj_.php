<?php
class Jual_Invoice_SJ extends CI_Controller
{
      function __construct()
      {
            parent::__construct();

            $this->load->library('/fpdf17/fpdf.php');
            $this->load->model('print/jual_model');
      }

      //////////////////////////////////////////////////////////
      // general setup (1 -> INVOICE)
      //////////////////////////////////////////////////////////
      private $judul = 'INVOICE [PJ01]';

      private $valuta = '';

      private $total_width = 200;

      private $col_barang = array(
            'no' => 15,
            'jumlah' => 20,
            'satuan' => 20,
            'roll' => 15,
            'space' => 5,
            'barang' => 75,
            'harga' => 15,
            'total' => 35
      );

      private $col_pl = array(
            'kode' => 30,
            'warna' => 25,
            'unit' => 8,
            'roll' => 10,
            'total' => 15,
            'detail_all' => 112,
            'detail' => 112 / 10
      );

      /////////////////////////////////////////////////////////////////
      //get data
      /////////////////////////////////////////////////////////////////
      function index()
      {
            //********************************************************* */
            // get profile
            //********************************************************* */
            $profile_me = $this->jual_model->get_profile()->row();

            if (isset($profile_me)) {
                  $profile = [
                        'nama' => $profile_me->nama,
                        'alamat1' => $profile_me->alamat,
                        'alamat2' => $profile_me->kota . ', ' . $profile_me->kode_pos . ' | TELP : ' . $profile_me->telepon,
                        'npwp' => 'NPWP : ' . $profile_me->NPWP,
                        'kota' => $profile_me->kota
                  ];
            }

            //********************************************************* */
            // get header
            //********************************************************* */
            $id = $this->uri->segment(4);

            $header_me = $this->jual_model->get_header($id)->row();

            if (isset($header_me)) {
                  $tanggal = $header_me->tanggal;
                  $no_sj = $header_me->no_sj;
                  $no_invoice = $header_me->no_invoice;
                  $no_po = $header_me->po_number;

                  if ($header_me->penjualan_type_id != 3) {
                        $nama_customer = $header_me->nama_customer;
                        $alamat_customer = $header_me->alamat_customer . $header_me->rt_customer . $header_me->rw_customer . ', ' . $header_me->kota_customer . ', ' . $header_me->provinsi_customer;
                        $alamat_customer = $header_me->alamat_customer_lengkap;
                  } else {
                        $nama_customer = $header_me->nama_keterangan;
                        $alamat_customer = $header_me->alamat_keterangan;
                  }

                  $alamat_customer1 = for_string($alamat_customer, 50);
                  $alamat_customer2 = for_string(substr($alamat_customer, strlen($alamat_customer1), strlen($alamat_customer)), 50);
                  $alamat_customer3 = strlen($alamat_customer) - (strlen($alamat_customer1) + strlen($alamat_customer2) + 1) > 0 ? for_string(substr($alamat_customer, strlen($alamat_customer1) + strlen($alamat_customer2) + 1), strlen($alamat_customer)) : '';
            }

            $header = [
                  'tanggal' => date('d', strtotime($tanggal)) . ' ' . ubah_nama_bulan(date('m', strtotime($tanggal))) . ' ' . date('Y', strtotime($tanggal)),
                  'no_po' => $no_po,
                  'no_sj' => $no_sj,
                  'no_invoice' => $no_invoice,
                  'nama_customer' => $nama_customer,
                  'alamat_customer1' => $alamat_customer1,
                  'alamat_customer2' => $alamat_customer2,
                  'alamat_customer3' => $alamat_customer3,
            ];

            //********************************************************* */
            // get barang
            //********************************************************* */
            $barang_me = $this->jual_model->get_barang($id)->result_array();

            $barang = array();

            foreach ($barang_me as $row) {
                  array_push($barang, array($row['qty'], $row['satuan'], $row['roll'], $row['nama_barang'], $row['harga'], $row['total_harga']));
            }

            //********************************************************* */
            // get unit
            //********************************************************* */
            $unit_me = $this->jual_model->get_unit($id)->result_array();

            $unit = array();

            foreach ($unit_me as $row) {
                  array_push($unit, array($row['satuan'], $row['jumlah'], $row['roll']));
            }

            //********************************************************* */
            // get payment
            //********************************************************* */
            $bayar_me = $this->jual_model->get_bayar($id)->result_array();

            $bayar = array();

            foreach ($bayar_me as $row) {
                  array_push($bayar, array($row['nama_tipe_pembayaran'], $row['amount']));
            }

            //********************************************************* */
            // get packing list 
            //********************************************************* */
            // sebelum diambil, data packing list harap di group by qty, sum(roll)
            // untuk qty = 0, ubah terlebih dahulu menjadi 1 agar muncul di packing list
            $pl_me = $this->jual_model->get_packing_list($id)->result_array();

            $pl = array();

            foreach ($pl_me as $row) {
                  array_push($pl, array($row['kode'], $row['warna'], $row['satuan'], $row['roll'], $row['total'], (float)$row['qty'], (float)$row['jumlah_roll']));
            }

            //parsing data ke 1
            // menjadikan data tersusun kebawah sesuai dengan jumlah roll (pl1)
            // menciptakan group sku (pl2)
            $pl1 = array();
            $pl2 = array();

            $sku_pl1 = '';
            $sku_pl2 = '';

            $counter = 0;

            for ($i = 0; $i <= count($pl) - 1; $i++) {
                  //per roll
                  for ($x = 0; $x <= $pl[$i][6] - 1; $x++) {
                        if ($sku_pl1 != $pl[$i][0] . $pl[$i][1]) {
                              $sku_pl1 = $pl[$i][0] . $pl[$i][1];
                              $counter++;
                        }

                        array_push($pl1, array($counter, $pl[$i][5]));
                  }

                  //ambil sku aja
                  if ($sku_pl2 != $pl[$i][0] . $pl[$i][1]) {
                        array_push($pl2, array($counter, $pl[$i][0], $pl[$i][1], $pl[$i][2], $pl[$i][3], $pl[$i][4], $pl[$i][5]));

                        $sku_pl2 = $pl[$i][0] . $pl[$i][1];
                  }
            }

            //parsing data ke 2
            // menjadikan data ke kanan dengan pembatas (%) per sepuluh kolom
            $pl3 = array();
            $sku_pl3 = '';

            $_detail = '';
            $c_detail = 0;
            $c_detail_multiple = 11;
            $c_d = 1;

            for ($ia = 0; $ia < count($pl2); $ia++) {
                  $sku_pl3 = $pl2[$ia][0];

                  $next_sku = '';

                  // isi detail
                  for ($ja = 0; $ja < count($pl1); $ja++) {
                        if ($sku_pl3 == $pl1[$ja][0]) {
                              $c_detail += 1;

                              if ($_detail == '')
                                    $_detail = format_angka($pl1[$ja][1]);
                              else {
                                    if ($c_detail == $c_detail_multiple) {
                                          $_detail = $_detail . '%' . format_angka($pl1[$ja][1]);
                                          $c_d += 1;
                                          $c_detail_multiple = ($c_d * 11) - ($c_d - 1);
                                    } else {
                                          $_detail = $_detail . ' ' . format_angka($pl1[$ja][1]);
                                    }
                              }

                              if ($ja <= count($pl1) - 2)
                                    $next_sku = $pl1[$ja + 1][0];
                              else
                                    $next_sku = '';

                              if ($next_sku != $sku_pl3) {
                                    array_push($pl3, array($sku_pl3, $_detail));

                                    $c_detail = 0;
                                    $_detail = '';
                              }
                        } else {
                              $c_d = 1;
                              $c_detail_multiple = 11;
                        }
                  }
            }

            //parsing data ke 3
            // menggabungkan seluruh parsing
            $pl4 = array();
            $sku_pl4 = '';

            $_kode = '';
            $_warna = '';
            $_unit = '';
            $_roll = 0;
            $_total = 0;
            $_detail = '';

            for ($ia = 0; $ia < count($pl2); $ia++) {
                  // isi data
                  $sku_pl4 = $pl2[$ia][0];
                  $_kode = $pl2[$ia][1];
                  $_warna = $pl2[$ia][2];
                  $_unit = $pl2[$ia][3];
                  $_roll = $pl2[$ia][4];
                  $_total = $pl2[$ia][5];

                  // isi detail
                  $_detail = explode('%', $pl3[$ia][1]);

                  for ($ib = 0; $ib <= count($_detail) - 1; $ib++) {
                        array_push($pl4, array($sku_pl4, $_kode, $_warna, $_unit, $_roll, $_total, $_detail[$ib]));
                  }
            }

            //********************************************************* */
            // start report
            //********************************************************* */
            //setup pdf
            $pdf = new FPDF('L', 'mm', array(216, 140));

            $pdf->addfont('Calibri', '', 'calibriL.php');
            $pdf->addfont('Calibri', 'B', 'calibri.php');
            $pdf->addfont('Calibri', 'I', 'calibriLI.php');

            $pdf->SetMargins(4, 10);
            $pdf->AliasNbPages();
            $pdf->SetAutoPageBreak(true, 10);
            $pdf->SetTitle($this->judul);

            $pdf->AddPage();

            //show report
            $this->show_pdf($pdf, $profile, $header, $barang, $unit, $bayar, $pl4);
      }

      ////////////////////////////////////////////////////////////////////////////////////////////////////
      // print
      ////////////////////////////////////////////////////////////////////////////////////////////////////
      function show_pdf($pdf, $profile, $header, $barang, $unit, $bayar, $pl)
      {
            //print header
            $this->print_header($pdf, $this->judul, $profile, $header);

            // hitung jumlah unit dan pembayaran tertinggi
            $total_row_barang = count($unit) > count($bayar) + 1 ? count($barang) + count($unit) : count($barang) + count($bayar) + 1;
            $total_row_pl = count($pl);

            // hitung grand total
            $grand_total = 0;

            foreach ($barang as $barangs) {
                  $grand_total += $barangs[5];
            }

            //print barang
            //kalau barang dan total kurang dari sama dengan 11 dan packing list kurang dari sama dengan 4
            //cetak packing list ditengah
            // print_r($total_row_barang);
            if ($total_row_barang <= 10 && $total_row_pl <= 6) {
                  $this->print_barang_header($pdf);
                  $this->print_barang($pdf, $barang, 0, count($barang) - 1);
                  $this->print_grand_total($pdf, $grand_total, $unit, $bayar);
                  $this->print_tanda_tangan($pdf);
                  $this->print_halaman($pdf);
            } else {
                  // kalau barang dan total lebih dari 11
                  if ($total_row_barang <= 17) {
                        //kalau barang kurang dari 17 row
                        $this->print_barang_header($pdf);
                        $this->print_barang($pdf, $barang, 0, count($barang) - 1);
                        $this->print_grand_total($pdf, $grand_total, $unit, $bayar);
                        $this->print_tanda_tangan($pdf);
                        $this->print_halaman($pdf);
                  } else {
                        //kalau barang kurang dari 17 row
                        //cetak barang dari 0 sampai 21
                        $this->print_barang_header($pdf);
                        $sisa_barang = $this->print_barang($pdf, $barang, 0, 21);
                        $this->print_halaman($pdf);

                        $sisa_barang = count($barang) - 21;
                        $sisa_pembagi = $sisa_barang % 28;
                        $sisa_barang = $sisa_barang - $sisa_pembagi;
                        $total_page = $sisa_barang / 28;
                        $barang_ke = 22;

                        for ($x = 0; $x < $total_page; $x++) {
                              $pdf->AddPage();
                              $this->print_barang_header($pdf);
                              $this->print_barang($pdf, $barang, $barang_ke, $barang_ke + 28);
                              $this->print_halaman($pdf);

                              $barang_ke = $barang_ke + 28 + 1;
                        }

                        //cetak page terakhir
                        if ($sisa_pembagi > 0) {
                              $pdf->AddPage();

                              $total_sisa = 0;

                              if ($sisa_barang > 0) {
                                    $this->print_barang_header($pdf);
                                    $total_sisa = $this->print_barang($pdf, $barang, $barang_ke, $barang_ke + 28);
                              }

                              if ($total_sisa <= 20) {
                                    $this->print_grand_total($pdf, $grand_total, $unit, $bayar);
                                    $this->print_tanda_tangan($pdf);
                                    $this->print_halaman($pdf);
                              } else {
                                    $pdf->AddPage();
                                    $this->print_grand_total($pdf, $grand_total, $unit, $bayar);
                                    $this->print_tanda_tangan($pdf);
                                    $this->print_halaman($pdf);
                              }
                        }

                        $pdf->AddPage();
                        $this->print_packing_list($pdf, $pl);
                        $this->print_halaman($pdf);
                  }
            }

            //print sj
            $this->print_sj($pdf);

            $pdf->Output();
      }



      ////////////////////////////////////////////////////////////////////////////////////////////////////
      // HEADER
      ////////////////////////////////////////////////////////////////////////////////////////////////////
      function print_header($pdf, $judul, $profile, $header)
      {
            $font_judul = 14;
            $font_tanggal = 11;
            $font_perusahaan = 12;
            $font_header = 10;
            $font_nomor = 11;
            $height_header = 4;

            // line 1
            $pdf->SetFont('Arial', 'B', $font_judul);
            $pdf->Cell(39, 6, strtoupper($judul), 1, 0, 'C');

            // $pdf->SetFont('Arial', '', $font_tanggal);
            // $pdf->Cell(161, 6, strtoupper($profile['kota'] . ' ' . $header['tanggal']), 0, 1, 'R');
            $pdf->SetFont('Arial', '', $font_tanggal-0.5);
            $pdf->Cell(99, 6, "", 0, 0, 'R');
            $pdf->Cell(61, 6, strtoupper($profile['kota'] . ' ' . $header['tanggal']), 0, 1, 'R');
            
            $p = strlen($header['tanggal']);
            $diff = ($p - 11) * 2.5;
            $xs = 158.2 - $diff;
            $ws = 45 + $diff;          

            $pdf->Rect($xs,9.5,$ws,5.7);

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
            $pdf->Cell($x_sj, 6, $no_sj, 'T', 0, 'L');
            $pdf->Cell($x_batas, 6, '', 'T', 0, 'L');
            // $pdf->SetFont('Calibri', '', $font_nomor);
            $pdf->Cell($x_invoice_flag, 6, 'INVOICE :  ', 'T', 0, 'R');
            // $pdf->SetFont('Arial', '', $font_nomor);
            $pdf->Cell($x_invoice, 6, $no_invoice, 'T', 1, 'R');
      }

      ////////////////////////////////////////////////////////////////////////////////////////////////
      // PRINT BARANG
      ////////////////////////////////////////////////////////////////////////////////////////////////
      function print_barang_header($pdf)
      {
            $font_size  = 11;

            $frame_header = '';
            $frame_header_height = 6;

            $pdf->SetFont('Calibri', '', $font_size);

            $pdf->Cell($this->col_barang['no'], $frame_header_height, 'NO.', 'TB' . $frame_header, 0, 'C');
            $pdf->Cell($this->col_barang['jumlah'], $frame_header_height, 'JUMLAH', 'TB' . $frame_header, 0, 'C');
            $pdf->Cell($this->col_barang['satuan'], $frame_header_height, 'SATUAN', 'TB' . $frame_header, 0, 'C');
            $pdf->Cell($this->col_barang['roll'], $frame_header_height, 'ROLL', 'TB' . $frame_header, 0, 'C');
            $pdf->Cell($this->col_barang['space'], $frame_header_height, '', 'TB' . $frame_header, 0, 'C');
            $pdf->Cell($this->col_barang['barang'], $frame_header_height, 'BARANG', 'TB' . $frame_header, 0, 'L');
            $pdf->Cell($this->col_barang['harga'], $frame_header_height, 'HARGA', 'TB' . $frame_header, 0, 'L');
            $pdf->Cell($this->col_barang['total'] - $this->col_barang['space'], $frame_header_height, 'TOTAL', 'TB' . $frame_header, 0, 'R');
            $pdf->Cell($this->col_barang['space'], $frame_header_height, '', 'TB' . $frame_header, 1, 'C');
      }

      function print_barang($pdf, $barang, $for, $until)
      {
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
      }

      function print_grand_total($pdf, $grand_total, $unit, $bayar)
      {
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
      }

      ////////////////////////////////////////////////////////////////////////
      // print packing list 
      ////////////////////////////////////////////////////////////////////////
      function print_packing_list_header($pdf)
      {
            $frame_header = '';
            $frame_header_height = 5;

            // header
            $pdf->SetFont('Calibri', '', 10);
            $pdf->Cell($this->col_pl['kode'], $frame_header_height, 'KODE', 'TB' . $frame_header, 0, 'C');
            $pdf->Cell($this->col_pl['warna'], $frame_header_height, 'WARNA', 'TB' . $frame_header, 0, 'C ');
            $pdf->Cell($this->col_pl['unit'], $frame_header_height, 'UNIT', 'TB' . $frame_header, 0, 'C');
            $pdf->Cell($this->col_pl['roll'], $frame_header_height, 'ROLL', 'TB' . $frame_header, 0, 'C ');
            $pdf->Cell($this->col_pl['total'], $frame_header_height, 'TOTAL', 'TB' . $frame_header, 0, 'C');
            $pdf->Cell($this->col_pl['detail_all'], $frame_header_height, 'DETAIL', 'LTB' . $frame_header, 1, 'C');
      }

      function print_packing_list($pdf, $pl1)
      {
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
      }

      /////////////////////////////////////////////////////////////////////////
      // print footer
      /////////////////////////////////////////////////////////////////////////
      function print_tanda_tangan($pdf)
      {
            $pdf->Ln(10);

            $pdf->SetFont('Calibri', '', 10);

            $pdf->Text(15, 133, 'TANDA TERIMA');
            $pdf->Text(83, 133, 'CHECKER');
            $pdf->Text(145, 133, 'HORMAT KAMI');
      }

      function print_halaman($pdf)
      {
            $pdf->SetFont('Calibri', 'I', 7.5);
            $pdf->text(193, 133, 'HAL : ' . $pdf->PageNo() . ' / {nb}', 0, 1, 'R');
      }

      /////////////////////////////////////////////////////////////////////////
      // print sj
      /////////////////////////////////////////////////////////////////////////
      private $judul_sj = 'SURAT JALAN [PJ02]';
      private $judul_pl = 'PACKING LIST [PJ03]';

      private $col_sj = array(
            'no' => 15,
            'jumlah' => 20,
            'satuan' => 20,
            'roll' => 20,
            'space' => 10,
            'barang' => 80,
            'harga' => 35
      );

      private $col_pl_sj = array(
            'kode' => 30,
            'warna' => 25,
            'unit' => 8,
            'roll' => 10,
            'total' => 15,
            'detail_all' => 112,
            'detail' => 112 / 10
      );

      /////////////////////////////////////////////////////////////////
      //get data (2 -> SJ)
      /////////////////////////////////////////////////////////////////
      function print_sj($pdf)
      {
            //********************************************************* */
            // get profile
            //********************************************************* */
            $profile_me = $this->jual_model->get_profile()->row();

            if (isset($profile_me)) {
                  $profile = [
                        'nama' => $profile_me->nama,
                        'alamat1' => $profile_me->alamat,
                        'alamat2' => $profile_me->kota . ', ' . $profile_me->kode_pos . ' | TELP : ' . $profile_me->telepon,
                        'npwp' => 'NPWP : ' . $profile_me->NPWP,
                        'kota' => $profile_me->kota
                  ];
            }

            //********************************************************* */
            // get header sj
            //********************************************************* */
            $id = $this->uri->segment(4);
            $pengiriman_id = $this->uri->segment(5);

            $header_me = $this->jual_model->get_header($id)->row();

            if (isset($header_me)) {
                  $tanggal = $header_me->tanggal;
                  $no_sj = $header_me->no_sj;
                  $no_invoice = $header_me->no_invoice;
                  $no_po = $header_me->po_number;
                  $no_pl = $header_me->no_pl;

                  if ($header_me->penjualan_type_id != 3) {
                        $nama_customer = $header_me->nama_customer;
                        $alamat_customer = $header_me->alamat_customer . $header_me->rt_customer . $header_me->rw_customer . ', ' . $header_me->kota_customer . ', ' . $header_me->provinsi_customer;
                        $alamat_customer = $header_me->alamat_customer_lengkap;
                  } else {
                        $nama_customer = $header_me->nama_keterangan;
                        $alamat_customer = $header_me->alamat_keterangan;
                  }

                  $alamat_customer1 = for_string($alamat_customer, 50);
                  $alamat_customer2 = for_string(substr($alamat_customer, strlen($alamat_customer1), strlen($alamat_customer)), 50);
                  $alamat_customer3 = strlen($alamat_customer) - (strlen($alamat_customer1) + strlen($alamat_customer2) + 1) > 0 ? for_string(substr($alamat_customer, strlen($alamat_customer1) + strlen($alamat_customer2) + 1), strlen($alamat_customer)) : '';
            }

            if ($pengiriman_id != '' && $pengiriman_id != 0) {
                  $pengiriman_me = $this->jual_model->get_pengiriman($pengiriman_id)->row();

                  $alamat_kirim = $pengiriman_me->alamat;
                  $alamat_kirim1 = for_string($alamat_kirim, 50);
                  $alamat_kirim2 = for_string(substr($alamat_kirim, strlen($alamat_kirim1), strlen($alamat_kirim)), 50);
                  $alamat_kirim3 = strlen($alamat_kirim) - (strlen($alamat_kirim1) + strlen($alamat_kirim2) + 1) > 0 ? for_string(substr($alamat_kirim, strlen($alamat_kirim1) + strlen($alamat_kirim2) + 1), strlen($alamat_kirim)) : '';
            } else {
                  $alamat_kirim = $alamat_customer;
                  $alamat_kirim1 = $alamat_customer1;
                  $alamat_kirim2 = $alamat_customer2;
                  $alamat_kirim3 = $alamat_customer3;
            }

            $header = [
                  'tanggal' => date('d', strtotime($tanggal)) . ' ' . ubah_nama_bulan(date('m', strtotime($tanggal))) . ' ' . date('Y', strtotime($tanggal)),
                  'no_po' => $no_po,
                  'no_sj' => $no_sj,
                  'no_invoice' => $no_invoice,
                  'no_pl' => $no_pl,
                  'nama_customer' => $nama_customer,
                  'alamat_customer1' => $alamat_customer1,
                  'alamat_customer2' => $alamat_customer2,
                  'alamat_customer3' => $alamat_customer3,
                  'alamat_kirim1' => $alamat_kirim1,
                  'alamat_kirim2' => $alamat_kirim2,
                  'alamat_kirim3' => $alamat_kirim3,
            ];

            //********************************************************* */
            // get barang
            //********************************************************* */
            $barang_me = $this->jual_model->get_barang($id)->result_array();

            $barang = array();

            foreach ($barang_me as $row) {
                  array_push($barang, array($row['qty'], $row['satuan'], $row['roll'], $row['nama_barang'], $row['harga'], $row['total_harga']));
            }

            //********************************************************* */
            // get unit
            //********************************************************* */
            $unit_me = $this->jual_model->get_unit($id)->result_array();

            $unit = array();

            foreach ($unit_me as $row) {
                  array_push($unit, array($row['satuan'], $row['jumlah'], $row['roll']));
            }

            //********************************************************* */
            // get packing list 
            //********************************************************* */
            // sebelum diambil, data packing list harap di group by qty, sum(roll)
            // untuk qty = 0, ubah terlebih dahulu menjadi 1 agar muncul di packing list
            $pl_me = $this->jual_model->get_packing_list($id)->result_array();

            $pl = array();

            foreach ($pl_me as $row) {
                  array_push($pl, array($row['kode'], $row['warna'], $row['satuan'], $row['roll'], $row['total'], (float)$row['qty'], (float)$row['jumlah_roll']));
            }

            //parsing data ke 1
            // menjadikan data tersusun kebawah sesuai dengan jumlah roll (pl1)
            // menciptakan group sku (pl2)
            $pl1 = array();
            $pl2 = array();

            $sku_pl1 = '';
            $sku_pl2 = '';

            $counter = 0;

            for ($i = 0; $i <= count($pl) - 1; $i++) {
                  //per roll
                  for ($x = 0; $x <= $pl[$i][6] - 1; $x++) {
                        if ($sku_pl1 != $pl[$i][0] . $pl[$i][1]) {
                              $sku_pl1 = $pl[$i][0] . $pl[$i][1];
                              $counter++;
                        }

                        array_push($pl1, array($counter, $pl[$i][5]));
                  }

                  //ambil sku aja
                  if ($sku_pl2 != $pl[$i][0] . $pl[$i][1]) {
                        array_push($pl2, array($counter, $pl[$i][0], $pl[$i][1], $pl[$i][2], $pl[$i][3], $pl[$i][4], $pl[$i][5]));

                        $sku_pl2 = $pl[$i][0] . $pl[$i][1];
                  }
            }

            //parsing data ke 2
            // menjadikan data ke kanan dengan pembatas (%) per sepuluh kolom
            $pl3 = array();
            $sku_pl3 = '';

            $_detail = '';
            $c_detail = 0;
            $c_detail_multiple = 11;
            $c_d = 1;

            for ($ia = 0; $ia < count($pl2); $ia++) {
                  $sku_pl3 = $pl2[$ia][0];

                  $next_sku = '';

                  // isi detail
                  for ($ja = 0; $ja < count($pl1); $ja++) {
                        if ($sku_pl3 == $pl1[$ja][0]) {
                              $c_detail += 1;

                              if ($_detail == '')
                                    $_detail = format_angka($pl1[$ja][1]);
                              else {
                                    if ($c_detail == $c_detail_multiple) {
                                          $_detail = $_detail . '%' . format_angka($pl1[$ja][1]);
                                          $c_d += 1;
                                          $c_detail_multiple = ($c_d * 11) - ($c_d - 1);
                                    } else {
                                          $_detail = $_detail . ' ' . format_angka($pl1[$ja][1]);
                                    }
                              }

                              if ($ja <= count($pl1) - 2)
                                    $next_sku = $pl1[$ja + 1][0];
                              else
                                    $next_sku = '';

                              if ($next_sku != $sku_pl3) {
                                    array_push($pl3, array($sku_pl3, $_detail));

                                    $c_detail = 0;
                                    $_detail = '';
                              }
                        } else {
                              $c_d = 1;
                              $c_detail_multiple = 11;
                        }
                  }
            }

            //parsing data ke 3
            // menggabungkan seluruh parsing
            $pl4 = array();
            $sku_pl4 = '';

            $_kode = '';
            $_warna = '';
            $_unit = '';
            $_roll = 0;
            $_total = 0;
            $_detail = '';

            for ($ia = 0; $ia < count($pl2); $ia++) {
                  // isi data
                  $sku_pl4 = $pl2[$ia][0];
                  $_kode = $pl2[$ia][1];
                  $_warna = $pl2[$ia][2];
                  $_unit = $pl2[$ia][3];
                  $_roll = $pl2[$ia][4];
                  $_total = $pl2[$ia][5];

                  // isi detail
                  $_detail = explode('%', $pl3[$ia][1]);

                  for ($ib = 0; $ib <= count($_detail) - 1; $ib++) {
                        array_push($pl4, array($sku_pl4, $_kode, $_warna, $_unit, $_roll, $_total, $_detail[$ib]));
                  }
            }

            //********************************************************* */
            // start report
            //********************************************************* */
            $pdf->AddPage();

            //show report
            $this->show_pdf_sj($pdf, $profile, $header, $barang, $unit, $pl4);
      }

      ////////////////////////////////////////////////////////////////////////////////////////////////////
      // print
      ////////////////////////////////////////////////////////////////////////////////////////////////////
      function show_pdf_sj($pdf, $profile, $header, $barang, $unit, $pl)
      {
            // hitung jumlah unit
            $total_row_barang = count($barang) + count($unit);

            if ($total_row_barang <= 14) {
                  $this->print_header_sj($pdf, $this->judul_sj, $profile, $header);
                  $this->print_barang_header_sj($pdf);
                  $this->print_barang_sj($pdf, $barang, 0, count($barang) - 1);
                  $this->print_grand_total_sj($pdf, $unit);
                  $this->print_tanda_tangan_sj($pdf);
                  $this->print_halaman_sj($pdf);
            } else {
                  //print dari 1 s/d 20
                  $this->print_header_sj($pdf, $this->judul_sj, $profile, $header);
                  $this->print_barang_header_sj($pdf);
                  $this->print_barang_sj($pdf, $barang, 0, 19);
                  $this->print_halaman_sj($pdf);

                  //hitung page selanjutnya
                  $barang_per_page = 29;
                  $sisa_barang = count($barang) - 20;
                  $total_page = ceil($sisa_barang / $barang_per_page);

                  $barang_ke = 20;

                  if ($total_page == 0) {
                        $pdf->AddPage();
                        $this->print_grand_total_sj($pdf, $unit);
                        $this->print_tanda_tangan_sj($pdf);
                        $this->print_halaman_sj($pdf);
                  } else {
                        for ($i = 1; $i <= $total_page; $i++) {
                              if ($i < $total_page) {
                                    $pdf->AddPage();
                                    $this->print_barang_header_sj($pdf);
                                    $this->print_barang_sj($pdf, $barang, $barang_ke, $barang_ke + $barang_per_page);
                                    $this->print_halaman_sj($pdf);
                                    $barang_ke  = $barang_ke + $barang_per_page + 1;
                              }

                              // kalau sudah mencapai akhir halaman
                              if ($i == $total_page) {
                                    $sisa_akhir = count($barang) - $barang_ke;

                                    if ($sisa_akhir < 0) {
                                          $pdf->AddPage();
                                          $this->print_grand_total_sj($pdf, $unit);
                                          $this->print_tanda_tangan_sj($pdf);
                                          $this->print_halaman_sj($pdf);
                                    } else {
                                          if ($sisa_akhir <= 21) {
                                                $pdf->AddPage();
                                                $this->print_barang_header_sj($pdf);
                                                $this->print_barang_sj($pdf, $barang, $barang_ke, $barang_ke + $barang_per_page);
                                                $this->print_grand_total_sj($pdf, $unit);
                                                $this->print_tanda_tangan_sj($pdf);
                                                $this->print_halaman_sj($pdf);
                                          } else {
                                                $pdf->AddPage();
                                                $this->print_barang_header_sj($pdf);
                                                $this->print_barang_sj($pdf, $barang, $barang_ke, $barang_ke + $barang_per_page);

                                                $pdf->AddPage();
                                                $this->print_grand_total_sj($pdf, $unit);
                                                $this->print_tanda_tangan_sj($pdf);
                                                $this->print_halaman_sj($pdf);
                                          }
                                    }
                              }
                        }
                  }
            }

            // print packing list
            // maks = 23 per page
            $pdf->AddPage();

            $this->print_header_sj_packing_list($pdf, $profile, $header);
            $this->print_packing_list_sj($pdf, $pl);
            $this->print_halaman_sj($pdf);
      }


      ////////////////////////////////////////////////////////////////////////////////////////////////////
      // HEADER
      ////////////////////////////////////////////////////////////////////////////////////////////////////
      function print_header_sj($pdf, $judul_sj, $profile, $header)
      {
            $font_judul_sj = 14.5;
            $font_tanggal = 10;
            $font_perusahaan = 12;
            $font_header = 10;
            $font_nomor = 11;
            $height_header = 4.2;

            $no_po = $header['no_po'] != '' ? 'PO : ' . $header['no_po'] : '';
            $no_sj = 'SJ : ' . $header['no_sj'];
            $no_invoice = 'INVOICE : ' . $header['no_invoice'];

            // line 1
            $pdf->SetFont('Arial', 'B', $font_judul_sj);
            $pdf->Cell(55, 6, strtoupper($judul_sj), 1, 0, 'C');

            $pdf->SetFont('Arial', '', $font_tanggal);
            $pdf->Cell(145, 7, strtoupper($profile['kota'] . ' ' . $header['tanggal']), 0, 1, 'R');

            $pdf->ln(1);

            // line 2
            $pdf->SetFont('Arial', 'B', $font_perusahaan);
            $pdf->Cell(80, $height_header, strtoupper($profile['nama']), 0, 0, 'L');

            $pdf->ln(-1.5);

            $pdf->SetFont('Calibri', '', 12);
            $pdf->Cell(200, $height_header, $no_sj, '', 1, 'R');

            $pdf->ln(1.5);

            // line 3
            $pdf->SetFont('Calibri', '', $font_header);
            $pdf->Cell(80, $height_header, $profile['alamat1'], 0, 0, 'L');

            $pdf->ln(-1);

            $pdf->Cell(200, $height_header, $no_invoice, 0, 1, 'R');

            $pdf->ln(1);

            // line 4
            $pdf->Cell(80, $height_header, $profile['alamat2'], 0, 0, 'L');

            $pdf->ln(-0.5);

            $pdf->Cell(200, $height_header, $no_po, 0, 1, 'R');

            $pdf->ln(2);

            $pdf->line(4, 32, 204, 32);

            $pdf->ln(2);

            // line 4
            $pdf->SetFont('Calibri', '', $font_header);
            $pdf->Cell(12, $height_header, 'KEPADA ', 0, 0, 'L');
            $pdf->Cell(3, $height_header, ' : ', 0, 0, 'L');
            $pdf->Cell(90, $height_header, $header['nama_customer'], 0, 0, 'L');
            $pdf->Cell(96, $height_header, 'ALAMAT PENGIRIMAN / VIA :', 0, 1, 'L');

            // line 5
            $pdf->Cell(12, $height_header, 'ALAMAT ', 0, 0, 'L');
            $pdf->Cell(3, $height_header, ' : ', 0, 0, 'L');
            $pdf->Cell(90, $height_header, $header['alamat_customer1'], 0, 0, 'L');
            $pdf->Cell(96, $height_header, $header['alamat_kirim1'], 0, 1, 'L');

            if ($header['alamat_customer2'] != '' || $header['alamat_kirim2'] != '') {
                  // line 6
                  $pdf->Cell(12, $height_header, '', 0, 0, 'L');
                  $pdf->Cell(3, $height_header, '', 0, 0, 'L');
                  $pdf->Cell(90, $height_header, $header['alamat_customer2'], 0, 0, 'L');
                  $pdf->Cell(96, $height_header, $header['alamat_kirim2'], 0, 1, 'L');
            }

            if ($header['alamat_customer3'] != '' || $header['alamat_kirim3'] != '') {
                  // line 7
                  $pdf->Cell(12, $height_header, '', 0, 0, 'L');
                  $pdf->Cell(3, $height_header, '', 0, 0, 'L');
                  $pdf->Cell(90, $height_header, $header['alamat_customer3'], 0, 0, 'L');
                  $pdf->Cell(96, $height_header, $header['alamat_kirim3'], 0, 1, 'L');
            }

            $pdf->ln(1);
      }

      ////////////////////////////////////////////////////////////////////////////////////////////////////
      // HEADER PACKING LIST
      ////////////////////////////////////////////////////////////////////////////////////////////////////
      function print_header_sj_packing_list($pdf, $profile, $header)
      {
            $font_judul_sj = 14.5;
            $font_tanggal = 10;
            $font_perusahaan = 12;
            $font_header = 10;

            $height_header = 4.2;

            //penomoran
            $pdf->SetFont('Calibri', '', $font_header);
            $pdf->Text(85, 12, 'NO ');
            $pdf->Text(70, 16, 'SURAT JALAN');

            $pdf->Text(91, 12, ':');
            $pdf->Text(91, 16, ':');

            $pdf->Text(93, 12, $header['no_pl']);
            $pdf->Text(93, 16, $header['no_sj']);

            // line 1
            $pdf->SetFont('Arial', 'B', $font_judul_sj);
            $pdf->Cell(55, 6, strtoupper($this->judul_pl), 1, 0, 'C');

            $pdf->SetFont('Arial', '', $font_tanggal);
            $pdf->Cell(145, 6, strtoupper($profile['kota'] . ' ' . $header['tanggal']), 0, 1, 'R');

            $pdf->ln(2);

            // line 2
            $pdf->SetFont('Arial', 'B', $font_perusahaan);
            $pdf->Cell(100, $height_header, strtoupper($profile['nama']), 0, 0, 'L');

            $pdf->ln(-2);

            $pdf->SetFont('Calibri', '', $font_header);
            $pdf->Cell(200, $height_header, 'KEPADA YTH.', 0, 1, 'R');

            // line 3
            $pdf->Cell(200, $height_header, $header['nama_customer'], 0, 1, 'R');

            $pdf->ln(1);
      }

      ////////////////////////////////////////////////////////////////////////////////////////////////
      // PRINT BARANG
      ////////////////////////////////////////////////////////////////////////////////////////////////
      function print_barang_header_sj($pdf)
      {
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
            $pdf->Cell($this->col_sj['harga'], $frame_header_height, 'HARGA', 'TB' . $frame_header, 1, 'C');
      }

      function print_barang_sj($pdf, $barang, $for, $until)
      {
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
                        $pdf->Cell($this->col_sj['harga'], $frame_detail_height, $this->valuta . format_angka($barang[$i][4]), $frame_detail, 1, 'R'); //harga
                  }
            }

            return $count;
      }

      function print_grand_total_sj($pdf, $unit)
      {
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
      }

      ////////////////////////////////////////////////////////////////////////
      // print packing list 
      ////////////////////////////////////////////////////////////////////////
      function print_packing_list_header_sj($pdf)
      {
            $frame_header = '';
            $frame_header_height = 5;

            // header
            $pdf->SetFont('Calibri', '', 10);
            $pdf->Cell($this->col_pl_sj['kode'], $frame_header_height, 'KODE', 'TB' . $frame_header, 0, 'C');
            $pdf->Cell($this->col_pl_sj['warna'], $frame_header_height, 'WARNA', 'TB' . $frame_header, 0, 'C ');
            $pdf->Cell($this->col_pl_sj['unit'], $frame_header_height, 'UNIT', 'TB' . $frame_header, 0, 'C');
            $pdf->Cell($this->col_pl_sj['roll'], $frame_header_height, 'ROLL', 'TB' . $frame_header, 0, 'C ');
            $pdf->Cell($this->col_pl_sj['total'], $frame_header_height, 'TOTAL', 'TB' . $frame_header, 0, 'C');
            $pdf->Cell($this->col_pl_sj['detail_all'], $frame_header_height, 'DETAIL', 'LTB' . $frame_header, 1, 'C');
      }

      function print_packing_list_sj($pdf, $pl1)
      {
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
      }

      /////////////////////////////////////////////////////////////////////////
      // print footer
      /////////////////////////////////////////////////////////////////////////
      function print_tanda_tangan_sj($pdf)
      {
            $pdf->SetFont('Calibri', '', 10);

            $top = 133;

            $pdf->Text(10, $top, 'TANDA TERIMA');
            $pdf->Text(43, $top, 'TANGGAL');
            $pdf->Text(83, $top, 'CHECKER');
            $pdf->Text(110, $top, 'EXPEDISI');
            $pdf->Text(150, $top, 'HORMAT KAMI');
      }

      function print_halaman_sj($pdf)
      {
            $pdf->SetFont('Calibri', 'I', 7.5);
            $pdf->text(193, 133, 'HAL : ' . $pdf->PageNo() . ' / {nb}', 0, 1, 'R');
      }
}