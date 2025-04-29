<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Pengeluaran_Rupa extends CI_Controller
{

      private $data = [];

      function __construct()
      {
            parent::__construct();

            is_logged_in();

            if (is_username() == '') {
                  redirect('home');
            }

            if (is_maintenance_on() && is_posisi_id() != 1) {
                  redirect(base_url() . 'home/maintenance_mode');
            }

            $this->data['username'] = is_username();
            $this->data['user_menu_list'] = is_user_menu(is_posisi_id());

            $this->load->model('transaksi/pengeluaran_rupa_model', 'tr_model', true);
            $this->load->model('transaksi/common_model', 'common_model', true);

            $this->gudang_list_aktif = $this->common_model->db_select('nd_gudang where status_aktif = 1 ORDER BY urutan asc');
            $this->warna_list_aktif = $this->common_model->db_select('nd_warna where status_aktif = 1 ORDER BY warna_jual asc');
            $this->barang_list_aktif = $this->common_model->get_barang_list_aktif();
      }

      function index()
      {
            redirect('admin');
      }

      function setting_link($string)
      {
            return rtrim(base64_encode($string), '=');
      }

      // list data 
      function daftar()
      {
            $menu = is_get_url_trans($this->uri->segment(1));

            $status_aktif = 1;

            if ($this->input->get('status_aktif')) {
                  $status_aktif = $this->input->get('status_aktif');
            }

            $data = array(
                  'content' => 'admin/transaction/transaksi/pengeluaran_rupa_list',
                  'breadcrumb_title' => 'Transaction',
                  'breadcrumb_small' => 'Pengeluaran Rupa2',
                  'nama_menu' => $menu[0],
                  'nama_submenu' => $menu[1],
                  'common_data' => $this->data,
                  'status_aktif' => $status_aktif,
                  'data_isi' => $this->data
            );

            $data['tgl_dari'] = date('01/m/Y');

            $data['user_id'] = is_user_id();
            $data['pengeluaran_rupa_list'] = $this->tr_model->get_list();

            $this->load->view('admin/template', $data);
      }

      function daftar_ajax()
      {
            $aColumns = array('status', 'tanggal', 'no_faktur_lengkap', 'keterangan', 'grand_total', 'id', 'status_data');

            $sIndexColumn = "id";

            // paging
            $sLimit = "";

            if (isset($_GET['iDisplayStart']) && $_GET['iDisplayLength'] != '-1') {
                  $sLimit = "LIMIT " . mysql_real_escape_string($_GET['iDisplayStart']) . ", " . mysql_real_escape_string($_GET['iDisplayLength']);
            }

            $numbering = mysql_real_escape_string($_GET['iDisplayStart']);

            $page = 1;

            // ordering
            if (isset($_GET['iSortCol_0'])) {
                  $sOrder = "ORDER BY  ";

                  for ($i = 0; $i < intval($_GET['iSortingCols']); $i++) {
                        if ($_GET['bSortable_' . intval($_GET['iSortCol_' . $i])] == "true") {
                              $sOrder .= $aColumns[intval($_GET['iSortCol_' . $i])] . "" . mysql_real_escape_string($_GET['sSortDir_' . $i]) . ", ";
                        }
                  }

                  $sOrder = substr_replace($sOrder, "", -2);

                  if ($sOrder == "ORDER BY") {
                        $sOrder = "";
                  }
            }

            // filtering
            $sWhere = "";

            if ($_GET['sSearch'] != "") {
                  $sWhere = "WHERE (";

                  for ($i = 0; $i < count($aColumns); $i++) {
                        $sWhere .= $aColumns[$i] . " LIKE '%" . mysql_real_escape_string($_GET['sSearch']) . "%' OR ";
                  }

                  $sWhere = substr_replace($sWhere, "", -3);

                  $sWhere .= ')';
            }

            // individual column filtering
            for ($i = 0; $i < count($aColumns); $i++) {
                  if ($_GET['bSearchable_' . $i] == "true" && $_GET['sSearch_' . $i] != '') {
                        if ($sWhere == "") {
                              $sWhere = "WHERE ";
                        } else {
                              $sWhere .= " AND ";
                        }

                        $sWhere .= $aColumns[$i] . " LIKE '%" . mysql_real_escape_string($_GET['sSearch_' . $i]) . "%' ";
                  }
            }

            if ($this->input->get('status') && $this->input->get('status') != '') {
                  $status = $this->input->get('status');
                  $sWhere .= ($sWhere == '' ? 'WHERE ' : 'AND ') . 'status = ' . $status;
            }

            if ($this->input->get('tgl_dari') && $this->input->get('tgl_dari') != '' && $this->input->get('tgl_sampai') && $this->input->get('tgl_sampai') != '') {
                  $tgl_dari = $this->input->get('tgl_dari');
                  $tgl_sampai = $this->input->get('tgl_sampai');

                  $sWhere .= ($sWhere == '' ? 'WHERE ' : 'AND ') . "(tanggal >= " . $tgl_dari . " AND tanggal <= " . $tgl_sampai . ")";
            }

            $sOrder = '';

            $rResult = $this->tr_model->get_list_ajax($aColumns, $sWhere, $sOrder, $sLimit);

            $rResultTotal = $this->common_model->db_select_num_rows('nd_pengeluaran_stok_lain');
            $Filternya = $this->tr_model->get_list_ajax($aColumns, $sWhere, $sOrder, '');
            $iFilteredTotal = $Filternya->num_rows();

            $output = array(
                  "sEcho" => intval($_GET['sEcho']),
                  "iTotalRecords" => $rResultTotal,
                  "iTotalDisplayRecords" => $iFilteredTotal,
                  "aaData" => array()
            );

            foreach ($rResult->result_array() as $aRow) {
                  $y = 0;
                  $row = array();

                  for ($i = 0; $i < count($aColumns); $i++) {
                        $row[] = $aRow[$aColumns[$i]];
                  }

                  $y++;
                  $page++;
                  $output['aaData'][] = $row;
            }

            echo json_encode($output);
      }

      // insert header 
      function header_insert()
      {
            $ini = $this->input;

            $data = array(
                  'tanggal' => is_date_formatter($ini->post('dtpTanggal')),
                  'keterangan' => $ini->post('txtCatatan'),
                  'user_id' => is_user_id()
            );

            $id = $this->common_model->db_insert('nd_pengeluaran_stok_lain', $data);

            redirect(is_setting_link('admin/transaction/transaksi/pengeluaran_rupa/editor') . '?id=' . $id);
      }

      private function header_insert_validate()
      {
            $data = array();
            $data['error_string'] = array();
            $data['inputerror'] = array();
            $data['status'] = TRUE;

            //get data
            $catatan = $this->input->post('txtCatatan');

            if ($catatan == '') {
                  $data['inputerror'][] = 'txtCatatan';
                  $data['error_string'][] = 'Catatan belum diisi!';
                  $data['status'] = FALSE;
            } else {
                  $data['inputerror'][] = 'txtCatatan';
                  $data['error_string'][] = '';
                  $data['status'] = TRUE;
            }

            if ($data['status'] == FALSE) {
                  echo json_encode($data);
                  exit();
            }
      }

      public function header_insert_validate_start()
      {
            $this->header_insert_validate();

            echo json_encode(array("status" => TRUE));
      }

      // edit header
      function header_edit()
      {
            $ini = $this->input;
            $id = $ini->post('txtIdHeaderEdit');

            $data = array(
                  'tanggal' => is_date_formatter($ini->post('dtpTanggalEdit')),
                  'keterangan' => $ini->post('txtCatatanEdit'),
                  'user_id' => is_user_id()
            );

            $this->common_model->db_update('nd_pengeluaran_stok_lain', $data, 'id', $id);

            redirect(is_setting_link('admin/transaction/transaksi/pengeluaran_rupa/editor') . '?id=' . $id);
      }

      private function header_edit_validate()
      {
            $data = array();
            $data['error_string'] = array();
            $data['inputerror'] = array();
            $data['status'] = TRUE;

            //get data
            $catatan = $this->input->post('txtCatatanEdit');

            if ($catatan == '') {
                  $data['inputerror'][] = 'txtCatatanEdit';
                  $data['error_string'][] = 'Catatan belum diisi!';
                  $data['status'] = FALSE;
            } else {
                  $data['inputerror'][] = 'txtCatatanEdit';
                  $data['error_string'][] = '';
                  $data['status'] = TRUE;
            }

            if ($data['status'] == FALSE) {
                  echo json_encode($data);
                  exit();
            }
      }

      public function header_edit_validate_start()
      {
            $this->header_edit_validate();

            echo json_encode(array("status" => TRUE));
      }

      // pencarian
      function cari()
      {
            $no_faktur = $this->input->post('txtNoFaktur');

            //cari di database
            $hasil = $this->common_model->db_select("nd_pengeluaran_stok_lain where status_aktif = 1 AND no_faktur_lengkap = '" . $no_faktur . "'");

            $id = '';

            foreach ($hasil as $row) {
                  $id = $row->id;
            }

            redirect(is_setting_link('admin/transaction/transaksi/pengeluaran_rupa/editor') . '?id=' . $id);
      }

      private function cari_validate()
      {
            $data = array();
            $data['error_string'] = array();
            $data['inputerror'] = array();
            $data['status'] = TRUE;

            //get data
            $no_faktur = $this->input->post('txtNoFaktur');

            if ($no_faktur == '') {
                  $data['inputerror'][] = 'txtNoFaktur';
                  $data['error_string'][] = 'Nomor belum diisi!';
                  $data['status'] = FALSE;
            } else {
                  //cari di database
                  $jumlah_data = $this->common_model->db_select_num_rows("nd_pengeluaran_stok_lain
                                                                              where
                                                                                    status_aktif = 1 AND
                                                                                    no_faktur_lengkap = '" . $no_faktur . "'");

                  if ($jumlah_data <= 0) {
                        $data['inputerror'][] = 'txtNoFaktur';
                        $data['error_string'][] = 'Nomor tidak terdaftar!';
                        $data['status'] = FALSE;
                  } else {
                        $data['inputerror'][] = 'txtNoFaktur';
                        $data['error_string'][] = '';
                        $data['status'] = TRUE;
                  }
            }

            if ($data['status'] == FALSE) {
                  echo json_encode($data);
                  exit();
            }
      }

      public function cari_validate_start()
      {
            $this->cari_validate();

            echo json_encode(array("status" => TRUE));
      }

      // editor
      function editor()
      {
            $menu = is_get_url_trans($this->uri->segment(1));

            $id = $this->input->get('id');

            $data = array(
                  'content' => 'admin/transaction/transaksi/pengeluaran_rupa_input',
                  'breadcrumb_title' => 'Transaction',
                  'breadcrumb_small' => 'Detail Pengeluaran Rupa2',
                  'nama_menu' => $menu[0],
                  'nama_submenu' => $menu[1],
                  'common_data' => $this->data,
                  'data_isi' => $this->data
            );

            //header
            $header = $this->common_model->db_select("nd_pengeluaran_stok_lain
                                                      where
                                                            id=" . $id);
            $data['header'] = $header;
            foreach ($header as $row) {
                  $toko_id = $row->toko_id;
            }

            //get toko
            $data['data_toko'] = $this->common_model->db_select('nd_toko where id = ' . $toko_id);

            // detail barang
            $data['pengeluaran_stok_lain_detail'] = $this->tr_model->get_barang($id);
            $total_jual = 0;
            foreach ($data['pengeluaran_stok_lain_detail'] as $row) {
                  $total_jual += $row->qty * $row->harga_jual;
            }
            $data['total_jual'] = $total_jual;

            $this->load->view('admin/template', $data);
      }

      // insert barang
      function barang_insert()
      {
            $ini = $this->input;
            $pengeluaran_id = $ini->post('txtPengeluaranId');
            $gudang_id = $ini->post('cboGudang');
            $barang_id = $ini->post('txtIdBarang');
            $warna_id = $ini->post('cboWarna');
            $harga = $ini->post('txtHarga');

            $data = array(
                  'pengeluaran_stok_lain_id' => $pengeluaran_id,
                  'gudang_id' => $gudang_id,
                  'barang_id' => $barang_id,
                  'warna_id' => $warna_id,
                  'harga_jual' => $harga,
                  'user_id' => is_user_id()
            );

            $this->common_model->db_insert('nd_pengeluaran_stok_lain_detail', $data);

            redirect(is_setting_link('admin/transaction/transaksi/pengeluaran_rupa/editor') . '?id=' . $pengeluaran_id);
      }

      private function barang_insert_validate()
      {
            $data = array();
            $data['error_string'] = array();
            $data['inputerror'] = array();
            $data['status'] = TRUE;

            //get data
            $ada_error = 0;

            $ini = $this->input;

            $pengeluaran_id = $ini->post('txtPengeluaranId');
            $gudang_id = $ini->post('cboGudang');
            $barang_id = $ini->post('cboBarang');
            $warna_id = $ini->post('cboWarna');
            $harga = $ini->post('txtHarga');

            if ($barang_id == '') {
                  $data['inputerror'][] = 'cboBarang';
                  $data['error_string'][] = 'Barang belum diisi!';
                  $data['status'] = FALSE;
                  $ada_error = 1;
            } else {
                  //cek apakah ada transaksi barang yang sama 
                  $jum_data = $this->common_model->db_select_num_rows("nd_pengeluaran_stok_lain_detail
                                                                        WHERE
                                                                              pengeluaran_stok_lain_id = '" . $pengeluaran_id . "' AND
                                                                              barang_id = '" . $barang_id . "' AND
                                                                              warna_id = '" . $warna_id . "' AND 
                                                                              gudang_id = '" . $gudang_id . "'");

                  if ($jum_data > 0) {
                        $data['inputerror'][] = 'cboBarang';
                        $data['error_string'][] = 'Barang sudah terdaftar!';
                        $data['status'] = FALSE;
                        $ada_error = 1;
                  } else {
                        $data['inputerror'][] = 'cboBarang';
                        $data['error_string'][] = '';
                  }
            }

            if ($gudang_id == '') {
                  $data['inputerror'][] = 'cboGudang';
                  $data['error_string'][] = 'Gudang belum dipilih!';
                  $data['status'] = FALSE;
                  $ada_error = 1;
            } else {
                  $data['inputerror'][] = 'cboGudang';
                  $data['error_string'][] = '';
            }

            if ($warna_id == '') {
                  $data['inputerror'][] = 'cboWarna';
                  $data['error_string'][] = 'Warna belum dipilih!';
                  $data['status'] = FALSE;
                  $ada_error = 1;
            } else {
                  $data['inputerror'][] = 'cboWarna';
                  $data['error_string'][] = '';
            }

            if ($harga == 0) {
                  $data['inputerror'][] = 'txtHarga';
                  $data['error_string'][] = 'Harga belum diisi!';
                  $data['status'] = FALSE;
                  $ada_error = 1;
            } else {
                  $data['inputerror'][] = 'txtHarga';
                  $data['error_string'][] = '';
            }

            if ($ada_error == 0)
                  $data['status'] = TRUE;

            if ($ada_error == 1) {
                  echo json_encode($data);
                  exit();
            }
      }

      public function barang_insert_validate_start()
      {
            $this->barang_insert_validate();

            echo json_encode(array("status" => TRUE));
      }

      // insert pl
      function pl_insert()
      {
            $ini = $this->input;
            $pengeluaran_id = $ini->post('txtPengeluaranIdPL');
            $gudang_id = $ini->post('gudang_id_pl');
            $barang_id = $ini->post('barang_id_pl');
            $warna_id = $ini->post('warna_id_pl');
            $harga = $ini->post('harga_pl');

            //detail
            $data = array(
                  'pengeluaran_stok_lain_id' => $pengeluaran_id,
                  'gudang_id' => $gudang_id,
                  'barang_id' => $barang_id,
                  'warna_id' => $warna_id,
                  'harga_jual' => $harga,
                  'user_id' => is_user_id()
            );

            $detail_id = $this->common_model->db_insert('nd_pengeluaran_stok_lain_detail', $data);

            //pl
            $total_item = $ini->post('txtTotalItem');

            for ($i = 0; $i <= $total_item; $i++) {
                  $qty = $ini->post('txtQty' . $i);
                  $roll = $ini->post('txtRoll' . $i);

                  if ($qty != 0) {
                        $roll = $roll == '' || $roll == 0 ? 1 : $roll;

                        $pl = array(
                              'pengeluaran_stok_lain_detail_id' => $detail_id,
                              'qty' => $qty,
                              'jumlah_roll' => $roll,
                        );

                        $this->common_model->db_insert('nd_pengeluaran_stok_lain_qty_detail', $pl);
                  }
            }

            //redirect / finish
            redirect(is_setting_link('admin/transaction/transaksi/pengeluaran_rupa/editor') . '?id=' . $pengeluaran_id);
      }

      private function pl_insert_validate()
      {
            $data = array();
            $data['error_string'] = array();
            $data['inputerror'] = array();
            $data['status'] = TRUE;

            //get data
            $ada_error = 0;

            $ini = $this->input;
            $total_qty = $ini->post('txtTotalQty');
            $total_roll = $ini->post('txtTotalRoll');

            if ($total_qty == '' || $total_qty == 0) {
                  $data['inputerror'][] = 'txtTotalQty';
                  $data['error_string'][] = 'Qty belum diisi!';
                  $data['status'] = FALSE;
                  $ada_error = 1;
            } else {
                  $data['inputerror'][] = 'txtTotalQty';
                  $data['error_string'][] = '';
            }

            if ($total_roll == '' || $total_roll == 0) {
                  $data['inputerror'][] = 'txtTotalRoll';
                  $data['error_string'][] = 'Roll belum diisi!';
                  $data['status'] = FALSE;
                  $ada_error = 1;
            } else {
                  $data['inputerror'][] = 'txtTotalRoll';
                  $data['error_string'][] = '';
            }

            if ($ada_error == 0)
                  $data['status'] = TRUE;

            if ($ada_error == 1) {
                  echo json_encode($data);
                  exit();
            }
      }

      public function pl_insert_validate_start()
      {
            $this->pl_insert_validate();

            echo json_encode(array("status" => TRUE));
      }

      // edit barang
      function barang_edit()
      {
            $ini = $this->input;
            $id = $ini->post('txtIdEdit');
            $id_detail = $ini->post('txtIdDetailEdit');
            $gudang_id = $ini->post('cboGudangEdit');
            $barang_id = $ini->post('txtIdBarangRevisi');
            $warna_id = $ini->post('cboWarnaEdit');
            $harga = $ini->post('txtHargaEdit');

            if ($barang_id != '') {
                  $data = array(
                        'gudang_id' => $gudang_id,
                        'barang_id' => $barang_id,
                        'warna_id' => $warna_id,
                        'harga_jual' => $harga,
                        'user_id' => is_user_id()
                  );
            } else {
                  $data = array(
                        'gudang_id' => $gudang_id,
                        'warna_id' => $warna_id,
                        'harga_jual' => $harga,
                        'user_id' => is_user_id()
                  );
            }

            $this->common_model->db_update('nd_pengeluaran_stok_lain_detail', $data, 'id', $id_detail);

            redirect(is_setting_link('admin/transaction/transaksi/pengeluaran_rupa/editor') . '?id=' . $id);
      }

      private function barang_edit_validate()
      {
            $data = array();
            $data['error_string'] = array();
            $data['inputerror'] = array();
            $data['status'] = TRUE;

            //get data
            $ada_error = 0;

            $ini = $this->input;

            $id = $ini->post('txtIdEdit');
            $id_detail = $ini->post('txtIdDetailEdit');
            $gudang_id = $ini->post('cboGudangEdit');
            $barang_id = $ini->post('txtIdBarangRevisi');
            $warna_id = $ini->post('cboWarnaEdit');
            $harga = $ini->post('txtHargaEdit');

            if ($barang_id == '') {
                  $barang_id = $ini->post('txtIdBarangEdit');
            }

            $jum_data = $this->common_model->db_select_num_rows("nd_pengeluaran_stok_lain_detail
                                                                  WHERE
                                                                        pengeluaran_stok_lain_id = '" . $id . "' AND
                                                                        barang_id = '" . $barang_id . "' AND
                                                                        warna_id = '" . $warna_id . "' AND 
                                                                        gudang_id = '" . $gudang_id . "' AND
                                                                        id <> '" . $id_detail . "'");

            if ($jum_data > 0) {
                  $data['inputerror'][] = 'cboGudangEdit';
                  $data['error_string'][] = 'Barang dengan warna sudah terdaftar!';
                  $data['status'] = FALSE;
                  $ada_error = 1;
            } else {
                  $data['inputerror'][] = 'cboGudangEdit';
                  $data['error_string'][] = '';
            }

            if ($warna_id == '') {
                  $data['inputerror'][] = 'cboWarnaEdit';
                  $data['error_string'][] = 'Warna belum dipilih!';
                  $data['status'] = FALSE;
                  $ada_error = 1;
            } else {
                  $data['inputerror'][] = 'cboWarnaEdit';
                  $data['error_string'][] = '';
            }

            if ($harga == 0) {
                  $data['inputerror'][] = 'txtHargaEdit';
                  $data['error_string'][] = 'Harga belum diisi!';
                  $data['status'] = FALSE;
                  $ada_error = 1;
            } else {
                  $data['inputerror'][] = 'txtHargaEdit';
                  $data['error_string'][] = '';
            }

            if ($ada_error == 0)
                  $data['status'] = TRUE;

            if ($ada_error == 1) {
                  echo json_encode($data);
                  exit();
            }
      }

      public function barang_edit_validate_start()
      {
            $this->barang_edit_validate();

            echo json_encode(array("status" => TRUE));
      }

      // edit packing list
      function pl_edit()
      {
            $ini = $this->input;
            $id_header = $ini->post('txtPLHeaderIdEdit');
            $id_detail = $ini->post('txtPLDetailIdEdit');
            $gudang_id = $ini->post('gudang_id_pl_edit');
            $barang_id = $ini->post('barang_id_pl_edit');
            $warna_id = $ini->post('warna_id_pl_edit');
            $harga = $ini->post('harga_pl_edit');

            //detail
            if ($barang_id != '') {
                  $data = array(
                        'gudang_id' => $gudang_id,
                        'barang_id' => $barang_id,
                        'warna_id' => $warna_id,
                        'harga_jual' => $harga,
                        'user_id' => is_user_id()
                  );
            } else {
                  $data = array(
                        'gudang_id' => $gudang_id,
                        'warna_id' => $warna_id,
                        'harga_jual' => $harga,
                        'user_id' => is_user_id()
                  );
            }

            $this->common_model->db_update('nd_pengeluaran_stok_lain_detail', $data, 'id', $id_detail);

            //pl
            $total_item = $ini->post('txtTotalItemEdit');

            for ($i = 0; $i <= $total_item; $i++) {
                  $index = $ini->post('txtIndexEdit' . $i);
                  $qty = $ini->post('txtQtyEdit' . $i);
                  $roll = $ini->post('txtRollEdit' . $i);

                  if ($qty != 0) {
                        $roll = $roll == '' || $roll == 0 ? 1 : $roll;

                        $pl = array(
                              'pengeluaran_stok_lain_detail_id' => $id_detail,
                              'qty' => $qty,
                              'jumlah_roll' => $roll,
                        );

                        if ($index == '')
                              $this->common_model->db_insert('nd_pengeluaran_stok_lain_qty_detail', $pl);
                        else
                              $this->common_model->db_update('nd_pengeluaran_stok_lain_qty_detail', $pl, 'id', $index);
                  }

                  // hapus kalau kosong / 0
                  if ($index != '' && ($qty == '' || $qty == 0))
                        $this->common_model->db_delete('nd_pengeluaran_stok_lain_qty_detail', 'id', $index);
            }

            //redirect / finish
            redirect(is_setting_link('admin/transaction/transaksi/pengeluaran_rupa/editor') . '?id=' . $id_header);
      }

      private function pl_edit_validate()
      {
            $data = array();
            $data['error_string'] = array();
            $data['inputerror'] = array();
            $data['status'] = TRUE;

            //get data
            $ada_error = 0;

            $ini = $this->input;
            $total_qty = $ini->post('txtTotalQtyEdit');
            $total_roll = $ini->post('txtTotalRollEdit');

            if ($total_qty == '' || $total_qty == 0) {
                  $data['inputerror'][] = 'txtTotalQtyEdit';
                  $data['error_string'][] = 'Qty belum diisi!';
                  $data['status'] = FALSE;
                  $ada_error = 1;
            } else {
                  $data['inputerror'][] = 'txtTotalQtyEdit';
                  $data['error_string'][] = '';
            }

            if ($total_roll == '' || $total_roll == 0) {
                  $data['inputerror'][] = 'txtTotalRollEdit';
                  $data['error_string'][] = 'Roll belum diisi!';
                  $data['status'] = FALSE;
                  $ada_error = 1;
            } else {
                  $data['inputerror'][] = 'txtTotalRollEdit';
                  $data['error_string'][] = '';
            }

            if ($ada_error == 0)
                  $data['status'] = TRUE;

            if ($ada_error == 1) {
                  echo json_encode($data);
                  exit();
            }
      }

      public function pl_edit_validate_start()
      {
            $this->pl_edit_validate();

            echo json_encode(array("status" => TRUE));
      }

      // get list pl
      public function pl_get_list()
      {
            $detail_id = $this->input->post('detail_id');

            $data = $this->tr_model->get_pl($detail_id);

            echo json_encode($data);
      }

      // hapus barang
      function barang_delete()
      {
            $id = $this->input->post('id');

            // hapus detail
            $this->common_model->db_delete('nd_pengeluaran_stok_lain_detail', 'id', $id);

            // hapus packing list 
            $this->common_model->db_delete('nd_pengeluaran_stok_lain_qty_detail', 'pengeluaran_stok_lain_detail_id', $id);

            echo 'OK';
      }

      // lock data
      function lock()
      {
            $id = $this->input->post('id');

            $no_faktur = '';

            // ambil data header
            $data = $this->common_model->db_select('nd_pengeluaran_stok_lain WHERE id=' . $id);

            foreach ($data as $row) {
                  $id_toko = $row->toko_id;
                  $no_faktur = $row->no_faktur;

                  $bulan_nomor = substr('00' . date('m', strtotime($row->tanggal)), -2);
                  $tahun_nomor = substr('0000' . date('Y', strtotime($row->tanggal)), -2);

                  $tahun = date('Y', strtotime($row->tanggal));
            }

            //ambil data toko
            $toko = $this->common_model->db_select('nd_toko WHERE id = ' . $id_toko);

            foreach ($toko as $toko_row) {
                  $pre_po = $toko_row->pre_po;
            }

            //ambil nomor terakhir
            if ($no_faktur == '') {
                  // get nomor faktur akhir
                  $no_faktur = 1;

                  $get = $this->common_model->db_select("nd_pengeluaran_stok_lain where no_faktur is not null AND YEAR(tanggal)='" . $tahun . "' AND status_aktif = 1 order by no_faktur desc limit 1");

                  foreach ($get as $row) {
                        $no_faktur = $row->no_faktur + 1;
                  }

                  // susun no faktur lengkap
                  $no_faktur_lengkap = 'RR' . $tahun_nomor . '/' . $bulan_nomor . '-' . substr('000' . $no_faktur, -3);

                  $data = array(
                        'no_faktur' => $no_faktur,
                        'no_faktur_lengkap' => $no_faktur_lengkap,
                        'status' => 0
                  );
            } else {
                  $data = array('status' => 0);
            }

            $this->common_model->db_update('nd_pengeluaran_stok_lain', $data, 'id', $id);

            echo 'OK';
      }

      // unlock transaction
      function unlock()
      {
            $id_header = $this->input->post('txtPINHeaderId');

            $data = array(
                  'status' => 1
            );

            $this->common_model->db_update("nd_pengeluaran_stok_lain", $data, 'id', $id_header);

            redirect(is_setting_link('admin/transaction/transaksi/pengeluaran_rupa/editor') . '?id=' . $id_header);
      }

      private function unlock_validate()
      {
            $data = array();
            $data['error_string'] = array();
            $data['inputerror'] = array();
            $data['status'] = TRUE;

            $ini = $this->input;
            $pin = $ini->post('txtPinOpen');

            //cek apakah ada transaksi barang yang sama 
            if ($pin == '') {
                  $data['inputerror'][] = 'txtPinOpen';
                  $data['error_string'][] = "Mohon isi pin!";
                  $data['status'] = FALSE;
            } else {
                  $jum_data = $this->common_model->db_select_num_rows("nd_user
                                                                        WHERE
                                                                              posisi_id = 1 AND
                                                                              PIN = '" . $pin . "' AND
                                                                              id = '" . is_user_id() . "'");

                  if ($jum_data <= 0) {
                        $data['inputerror'][] = 'txtPinOpen';
                        $data['error_string'][] = 'Pin tidak terdaftar!';
                        $data['status'] = FALSE;
                  } else {
                        $data['inputerror'][] = 'txtPinOpen';
                        $data['error_string'][] = "";
                  }
            }

            if ($data['status'] == FALSE) {
                  echo json_encode($data);
                  exit();
            }
      }

      public function unlock_validate_start()
      {
            $this->unlock_validate();

            echo json_encode(array("status" => TRUE));
      }
}