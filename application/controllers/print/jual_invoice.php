<?php
class Jual_Invoice extends CI_Controller
{
      function __construct()
      {
            parent::__construct();

            $this->load->library('/fpdf17/fpdf.php');
            $this->load->model('print/jual_model');
            $this->load->model('common_model');


      }

      //////////////////////////////////////////////////////////
      // general setup
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
            // $is_pengiriman = $this->common_model->db_select_num_rows();


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
            // $pdf = new FPDF('P', 'mm', 'A4');

            $pdf->addfont('Calibri', '', 'calibriL.php');
            $pdf->addfont('Calibri', 'B', 'calibri.php');
            $pdf->addfont('Calibri', 'I', 'calibriLI.php');

            $pdf->SetMargins(4, 7);
            $pdf->AliasNbPages();
            $pdf->SetAutoPageBreak(true, 30);
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
            $pdf->Cell(80, $height_header, $profile['nama'], 0, 0, 'L');

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
            //store to private array
            $pl_source = array();
            $sku = '';
            $kode = '';
            $sisa_kode = '';
            $warna = '';
            $sisa_warna = '';
            $unit = '';
            $roll = '';
            $total = '';
            $detail = '';
            $frame_detail = '';

            $tambah_ke = 0;
            $satu_baris_sisa = 0;

            for ($i = 0; $i < count($pl1); $i++) {
                  if ($sku != $pl1[$i][0]) {
                        $sku = $pl1[$i][0];

                        $kode = $pl1[$i][1];
                        $sisa_kode = for_string($kode, 17);
                        $warna = $pl1[$i][2];
                        $sisa_warna = for_string($warna, 15);
                        $unit = $pl1[$i][3];
                        $roll = format_angka($pl1[$i][4]);
                        $total = format_angka($pl1[$i][5]);
                        $detail = $pl1[$i][6];
                        $frame_detail = 'T';
                        $tambah_ke = 1;

                        array_push($pl_source, array(
                              $sku,
                              $sisa_kode,
                              $sisa_warna,
                              $unit,
                              $roll,
                              $total,
                              $detail,
                              $frame_detail
                        ));

                        if (isset($pl1[$i + 1][0])) {
                              if ($sku != $pl1[$i + 1][0]) {
                                    //cek apakah ada baris 2
                                    $satu_baris_sisa = strlen($kode) - strlen($sisa_kode);

                                    if ($satu_baris_sisa > 0) {
                                          $sisa_kode = trim(substr($pl1[$i][1], strlen($sisa_kode), strlen($pl1[$i][1])), ' ');

                                          array_push($pl_source, array(
                                                $sku,
                                                $sisa_kode,
                                                '',
                                                '',
                                                '',
                                                '',
                                                '',
                                                ''
                                          ));
                                    }
                              }
                        }
                  } else {
                        $tambah_ke++;

                        if ($tambah_ke == 2) {
                              $sisa_kode = trim(substr($pl1[$i][1], strlen($sisa_kode), strlen($pl1[$i][1])), ' ');
                              $sisa_warna = trim(substr($pl1[$i][2], strlen($sisa_warna), strlen($pl1[$i][2])), ' ');
                        } else {
                              $sisa_kode = '';
                              $sisa_warna = '';
                        }

                        $unit = '';
                        $roll = '';
                        $total = '';
                        $detail = $pl1[$i][6];
                        $frame_detail = '';

                        array_push($pl_source, array(
                              $sku,
                              $sisa_kode,
                              $sisa_warna,
                              $unit,
                              $roll,
                              $total,
                              $detail,
                              $frame_detail
                        ));
                  }
            }

            //setup
            $frame_detail = '';
            $frame_detail_height = 4.5;

            $detail_x = array();

            $counter = 0;
            $page_ke = 1;

            for ($x = 0; $x < count($pl_source); $x++) {
                  $sku = $pl_source[$x][0];
                  $kode = $pl_source[$x][1];
                  $warna = $pl_source[$x][2];
                  $unit = strtoupper($pl_source[$x][3]);
                  $roll = $pl_source[$x][4];
                  $total = $pl_source[$x][5];
                  $detail_x = explode(' ', $pl_source[$x][6]);
                  $frame_detail = $pl_source[$x][7];

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
                  $pdf->Cell($this->col_pl['detail'], $frame_detail_height, isset($detail_x[0]) ? $detail_x[0] : '', 'L' . $frame_detail, 0, 'R');
                  $pdf->Cell($this->col_pl['detail'], $frame_detail_height, isset($detail_x[1]) ? $detail_x[1] : '', '' . $frame_detail, 0, 'R');
                  $pdf->Cell($this->col_pl['detail'], $frame_detail_height, isset($detail_x[2]) ? $detail_x[2] : '', '' . $frame_detail, 0, 'R');
                  $pdf->Cell($this->col_pl['detail'], $frame_detail_height, isset($detail_x[3]) ? $detail_x[3] : '', '' . $frame_detail, 0, 'R');
                  $pdf->Cell($this->col_pl['detail'], $frame_detail_height, isset($detail_x[4]) ? $detail_x[4] : '', '' . $frame_detail, 0, 'R');
                  $pdf->Cell($this->col_pl['detail'], $frame_detail_height, isset($detail_x[5]) ? $detail_x[5] : '', '' . $frame_detail, 0, 'R');
                  $pdf->Cell($this->col_pl['detail'], $frame_detail_height, isset($detail_x[6]) ? $detail_x[6] : '', '' . $frame_detail, 0, 'R');
                  $pdf->Cell($this->col_pl['detail'], $frame_detail_height, isset($detail_x[7]) ? $detail_x[7] : '', '' . $frame_detail, 0, 'R');
                  $pdf->Cell($this->col_pl['detail'], $frame_detail_height, isset($detail_x[8]) ? $detail_x[8] : '', '' . $frame_detail, 0, 'R');
                  $pdf->Cell($this->col_pl['detail'], $frame_detail_height, isset($detail_x[9]) ? $detail_x[9] : '', '' . $frame_detail, 1, 'R');

                  if ($counter >= 30) {
                        $counter = 0;
                        $page_ke++;
                        $this->print_halaman($pdf);
                        $pdf->addPage();
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
}
