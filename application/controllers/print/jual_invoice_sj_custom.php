<?php
class Jual_Invoice_SJ_Custom extends CI_Controller
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
            'roll' => 5,
            'space' => 5,
            'kode_beli' => 35,
            'barang' => 50,
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

      private $tipe_print = 1;


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
                  $keterangan = $header_me->keterangan;

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
                  'keterangan' => $keterangan,
            ];

            //********************************************************* */
            // get barang
            //********************************************************* */
            $barang_me = $this->jual_model->get_barang($id)->result_array();

            $barang = array();

            foreach ($barang_me as $row) {
                  array_push($barang, array($row['qty'], $row['satuan'], $row['roll'], $row['kode_beli'], $row['nama_jual'], $row['harga'], $row['total_harga']));
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
                  $this->print_grand_total($pdf, $grand_total, $unit, $bayar, $header);
                  $this->print_tanda_tangan($pdf);
                  $this->print_halaman($pdf);
            } else {
                  // kalau barang dan total lebih dari 11
                  if ($total_row_barang <= 17) {
                        //kalau barang kurang dari 17 row
                        $this->print_barang_header($pdf);
                        $this->print_barang($pdf, $barang, 0, count($barang) - 1);
                        $this->print_grand_total($pdf, $grand_total, $unit, $bayar, $header);
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
                                    $this->print_grand_total($pdf, $grand_total, $unit, $bayar, $header);
                                    $this->print_tanda_tangan($pdf);
                                    $this->print_halaman($pdf);
                              } else {
                                    $pdf->AddPage();
                                    $this->print_grand_total($pdf, $grand_total, $unit, $bayar, $header);
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
            include_once "custom/jual_header_custom.php";
      }

      ////////////////////////////////////////////////////////////////////////////////////////////////
      // PRINT BARANG
      ////////////////////////////////////////////////////////////////////////////////////////////////
      function print_barang_header($pdf){
            include_once "custom/jual_barang_header_custom.php";
      }

      function print_barang($pdf, $barang, $for, $until)
      {
            include_once "custom/jual_barang_body_custom.php";
      }

      function print_grand_total($pdf, $grand_total, $unit, $bayar, $header)
      {
            include_once "custom/jual_barang_footer_custom.php";
      }

      ////////////////////////////////////////////////////////////////////////
      // print packing list 
      ////////////////////////////////////////////////////////////////////////
      function print_packing_list_header($pdf)
      {
            include_once "custom/pl_barang_header_custom.php";

      }

      function print_packing_list($pdf, $pl1)
      {
            include_once "custom/pl_barang_body_custom.php";
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
                  array_push($barang, array($row['qty'], $row['satuan'], $row['roll'], $row['nama_jual'], $row['harga'], $row['total_harga']));
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
                  array_push($pl, array($row['kode_jual'], $row['warna'], $row['satuan'], $row['roll'], $row['total'], (float)$row['qty'], (float)$row['jumlah_roll']));
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
                  $this->print_barang_sj($pdf, $barang, 0, count($barang) - 1,1);
                  $this->print_grand_total_sj($pdf, $unit,1, $header);
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
            include_once "custom/sj_barang_header_custom.php";
      }

      function print_barang_sj($pdf, $barang, $for, $until, $tipe)
      {
            include_once "custom/sj_barang_body_custom.php";
      }

      function print_grand_total_sj($pdf, $unit, $tipe)
      {
            include_once "custom/sj_barang_footer_custom.php";
      }

      ////////////////////////////////////////////////////////////////////////
      // print packing list 
      ////////////////////////////////////////////////////////////////////////
      function print_packing_list_header_sj($pdf)
      {
            include_once "custom/pl_barang_header_custom.php";
      }

      function print_packing_list_sj($pdf, $pl1)
      {
            include_once "custom/pl_barang_body_custom.php";
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