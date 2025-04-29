<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Pembelian_Lain extends CI_Controller
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

            $this->load->model('transaksi/pembelian_lain_model', 'tr_model', true);
            $this->load->model('transaksi/common_model', 'common_model', true);

            $this->supplier_list_aktif = $this->common_model->db_select('nd_supplier where status_aktif = 1 ORDER BY id asc');
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
                  'content' => 'admin/transaction/transaksi/pembelian_lain_list',
                  'breadcrumb_title' => 'Transaction',
                  'breadcrumb_small' => 'Pembelian Lain2',
                  'nama_menu' => $menu[0],
                  'nama_submenu' => $menu[1],
                  'common_data' => $this->data,
                  'status_aktif' => $status_aktif,
                  'data_isi' => $this->data
            );

            $data['tgl_dari'] = date('01/m/Y');

            $data['user_id'] = is_user_id();
            $data['pembelian_lain_list'] = $this->tr_model->get_list();

            $this->load->view('admin/template', $data);
      }

      function daftar_ajax()
      {
            $aColumns = array('status', 'tanggal', 'no_faktur', 'supplier', 'keterangan', 'grand_total', 'id', 'status_data');

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

            $rResultTotal = $this->common_model->db_select_num_rows('nd_pembelian_lain');
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
            $tanggal  = $ini->post('dtpTanggal');
            $no_faktur  = $ini->post('txtNoFaktur');
            $supplier_id  = $ini->post('cboSupplier');
            $supplier_nama  = $ini->post('txtNamaSupplier');
            $supplier_alamat  = $ini->post('txtAlamatSupplier');
            $supplier_telepon  = $ini->post('txtTeleponSupplier');
            $catatan  = $ini->post('txtCatatan');

            if ($supplier_id != '') {
                  $supplier_nama = '';
                  $supplier_alamat = '';
                  $supplier_telepon = '';
            }

            $data = array(
                  'tanggal' => is_date_formatter($tanggal),
                  'no_faktur' => $no_faktur,
                  'supplier_id' => $supplier_id,
                  'supplier_nama' => $supplier_nama,
                  'supplier_alamat' => $supplier_alamat,
                  'supplier_telepon' => $supplier_telepon,
                  'keterangan' => $catatan,
                  'user_id' => is_user_id()
            );

            $id = $this->common_model->db_insert('nd_pembelian_lain', $data);

            redirect(is_setting_link('admin/transaction/transaksi/pembelian_lain/editor') . '?id=' . $id);
      }

      private function header_insert_validate()
      {
            $data = array();
            $data['error_string'] = array();
            $data['inputerror'] = array();
            $data['status'] = TRUE;

            //get data
            $no_faktur = $this->input->post('txtNoFaktur');
            $supplier_id = $this->input->post('cboSupplier');
            $supplier_nama = $this->input->post('txtNamaSupplier');
            $catatan = $this->input->post('txtCatatan');

            if ($supplier_id == '') {
                  if ($supplier_nama == '') {
                        $data['inputerror'][] = 'txtNamaSupplier';
                        $data['error_string'][] = 'Nama Supplier belum diisi!';
                        $data['status'] = FALSE;
                  } else {
                        $data['inputerror'][] = 'txtNamaSupplier';
                        $data['error_string'][] = '';
                  }
            } else {
                  $data['inputerror'][] = 'txtNamaSupplier';
                  $data['error_string'][] = '';
            }

            if ($no_faktur == '') {
                  $data['inputerror'][] = 'txtNoFaktur';
                  $data['error_string'][] = 'No. Faktur belum diisi!';
                  $data['status'] = FALSE;
            } else {
                  $data['inputerror'][] = 'txtNoFaktur';
                  $data['error_string'][] = '';
            }

            if ($catatan == '') {
                  $data['inputerror'][] = 'txtCatatan';
                  $data['error_string'][] = 'Catatan belum diisi!';
                  $data['status'] = FALSE;
            } else {
                  $data['inputerror'][] = 'txtCatatan';
                  $data['error_string'][] = '';
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
            $tanggal  = $ini->post('dtpTanggalEdit');
            $no_faktur  = $ini->post('txtNoFakturEdit');
            $supplier_id  = $ini->post('cboSupplierEdit');
            $supplier_nama  = $ini->post('txtNamaSupplierEdit');
            $supplier_alamat  = $ini->post('txtAlamatSupplierEdit');
            $supplier_telepon  = $ini->post('txtTeleponSupplierEdit');
            $catatan  = $ini->post('txtCatatanEdit');

            if ($supplier_id != '') {
                  $supplier_nama = '';
                  $supplier_alamat = '';
                  $supplier_telepon = '';
            }

            $data = array(
                  'tanggal' => is_date_formatter($tanggal),
                  'no_faktur' => $no_faktur,
                  'supplier_id' => $supplier_id,
                  'supplier_nama' => $supplier_nama,
                  'supplier_alamat' => $supplier_alamat,
                  'supplier_telepon' => $supplier_telepon,
                  'keterangan' => $catatan,
                  'user_id' => is_user_id()
            );

            $this->common_model->db_update('nd_pembelian_lain', $data, 'id', $id);

            redirect(is_setting_link('admin/transaction/transaksi/pembelian_lain/editor') . '?id=' . $id);
      }

      private function header_edit_validate()
      {
            $data = array();
            $data['error_string'] = array();
            $data['inputerror'] = array();
            $data['status'] = TRUE;

            //get data
            $no_faktur = $this->input->post('txtNoFakturEdit');
            $supplier_id = $this->input->post('cboSupplierEdit');
            $supplier_nama = $this->input->post('txtNamaSupplierEdit');
            $catatan = $this->input->post('txtCatatanEdit');

            if ($supplier_id == '') {
                  if ($supplier_nama == '') {
                        $data['inputerror'][] = 'txtNamaSupplierEdit';
                        $data['error_string'][] = 'Nama Supplier belum diisi!';
                        $data['status'] = FALSE;
                  } else {
                        $data['inputerror'][] = 'txtNamaSupplierEdit';
                        $data['error_string'][] = '';
                  }
            } else {
                  $data['inputerror'][] = 'txtNamaSupplierEdit';
                  $data['error_string'][] = '';
            }

            if ($no_faktur == '') {
                  $data['inputerror'][] = 'txtNoFakturEdit';
                  $data['error_string'][] = 'No. Faktur belum diisi!';
                  $data['status'] = FALSE;
            } else {
                  $data['inputerror'][] = 'txtNoFakturEdit';
                  $data['error_string'][] = '';
            }

            if ($catatan == '') {
                  $data['inputerror'][] = 'txtCatatanEdit';
                  $data['error_string'][] = 'Catatan belum diisi!';
                  $data['status'] = FALSE;
            } else {
                  $data['inputerror'][] = 'txtCatatanEdit';
                  $data['error_string'][] = '';
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
            $no_faktur = $this->input->post('txtNoFakturCari');

            //cari di database
            $hasil = $this->common_model->db_select("nd_pembelian_lain where status_aktif = 1 AND no_faktur = '" . $no_faktur . "'");

            $id = '';

            foreach ($hasil as $row) {
                  $id = $row->id;
            }

            redirect(is_setting_link('admin/transaction/transaksi/pembelian_lain/editor') . '?id=' . $id);
      }

      private function cari_validate()
      {
            $data = array();
            $data['error_string'] = array();
            $data['inputerror'] = array();
            $data['status'] = TRUE;

            //get data
            $no_faktur = $this->input->post('txtNoFakturCari');

            if ($no_faktur == '') {
                  $data['inputerror'][] = 'txtNoFakturCari';
                  $data['error_string'][] = 'Nomor belum diisi!';
                  $data['status'] = FALSE;
            } else {
                  //cari di database
                  $jumlah_data = $this->common_model->db_select_num_rows("nd_pembelian_lain
                                                                              where
                                                                                    status_aktif = 1 AND
                                                                                    no_faktur = '" . $no_faktur . "'");

                  if ($jumlah_data <= 0) {
                        $data['inputerror'][] = 'txtNoFakturCari';
                        $data['error_string'][] = 'Nomor tidak terdaftar!';
                        $data['status'] = FALSE;
                  } else {
                        $data['inputerror'][] = 'txtNoFakturCari';
                        $data['error_string'][] = '';
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
                  'content' => 'admin/transaction/transaksi/pembelian_lain_input',
                  'breadcrumb_title' => 'Transaction',
                  'breadcrumb_small' => 'Detail Pembelian Lain2',
                  'nama_menu' => $menu[0],
                  'nama_submenu' => $menu[1],
                  'common_data' => $this->data,
                  'data_isi' => $this->data
            );

            //header
            $header = $this->tr_model->get_header($id);
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
                  $total_jual += $row->qty * $row->harga;
            }
            $data['total_jual'] = $total_jual;

            $this->load->view('admin/template', $data);
      }

      // insert barang
      function barang_insert()
      {
            $ini = $this->input;
            $id = $ini->post('txtBarangHeaderId');
            $barang = $ini->post('txtBarang');
            $qty = $ini->post('txtQty');
            $harga = $ini->post('txtHarga');

            $data = array(
                  'pembelian_lain_id' => $id,
                  'barang' => $barang,
                  'qty' => $qty,
                  'harga' => $harga
            );

            $this->common_model->db_insert('nd_pembelian_lain_detail', $data);

            redirect(is_setting_link('admin/transaction/transaksi/pembelian_lain/editor') . '?id=' . $id);
      }

      private function barang_insert_validate()
      {
            $data = array();
            $data['error_string'] = array();
            $data['inputerror'] = array();
            $data['status'] = TRUE;

            //get data
            $ini = $this->input;
            $id = $ini->post('txtBarangHeaderId');
            $barang = $ini->post('txtBarang');
            $qty = $ini->post('txtQty');
            $harga = $ini->post('txtHarga');

            // barang
            if ($barang == '') {
                  $data['inputerror'][] = 'txtBarang';
                  $data['error_string'][] = 'Barang belum diisi!';
                  $data['status'] = FALSE;
            } else {
                  //cek apakah ada transaksi barang yang sama 
                  $jum_data = $this->common_model->db_select_num_rows("nd_pembelian_lain_detail
                                                                        WHERE
                                                                              pembelian_lain_id = '" . $id . "' AND
                                                                              barang = '" . $barang . "'");

                  if ($jum_data > 0) {
                        $data['inputerror'][] = 'txtBarang';
                        $data['error_string'][] = 'Barang sudah terdaftar!';
                        $data['status'] = FALSE;
                  } else {
                        $data['inputerror'][] = 'txtBarang';
                        $data['error_string'][] = '';
                  }
            }

            if ($qty == 0) {
                  $data['inputerror'][] = 'txtQty';
                  $data['error_string'][] = 'Qty belum diisi!';
                  $data['status'] = FALSE;
            } else {
                  $data['inputerror'][] = 'txtQty';
                  $data['error_string'][] = '';
            }

            if ($harga == 0) {
                  $data['inputerror'][] = 'txtHarga';
                  $data['error_string'][] = 'Harga belum diisi!';
                  $data['status'] = FALSE;
            } else {
                  $data['inputerror'][] = 'txtHarga';
                  $data['error_string'][] = '';
            }

            if ($data['status'] == FALSE) {
                  echo json_encode($data);
                  exit();
            }
      }

      public function barang_insert_validate_start()
      {
            $this->barang_insert_validate();

            echo json_encode(array("status" => TRUE));
      }

      // edit barang
      function barang_edit()
      {
            $ini = $this->input;
            $id = $ini->post('txtIdEdit');
            $id_detail = $ini->post('txtIdDetailEdit');
            $barang = $ini->post('txtBarangEdit');
            $qty = $ini->post('txtQtyEdit');
            $harga = $ini->post('txtHargaEdit');

            $data = array(
                  'barang' => $barang,
                  'qty' => $qty,
                  'harga' => $harga
            );

            $this->common_model->db_update('nd_pembelian_lain_detail', $data, 'id', $id_detail);

            redirect(is_setting_link('admin/transaction/transaksi/pembelian_lain/editor') . '?id=' . $id);
      }

      private function barang_edit_validate()
      {
            $data = array();
            $data['error_string'] = array();
            $data['inputerror'] = array();
            $data['status'] = TRUE;

            //get data
            $ini = $this->input;
            $id = $ini->post('txtIdEdit');
            $id_detail = $ini->post('txtIdDetailEdit');
            $barang = $ini->post('txtBarangEdit');
            $qty = $ini->post('txtQtyEdit');
            $harga = $ini->post('txtHargaEdit');

            $jum_data = $this->common_model->db_select_num_rows("nd_pembelian_lain_detail
                                                                  WHERE
                                                                        pembelian_lain_id = '" . $id . "' AND
                                                                        barang = '" . $barang . "' AND
                                                                        id <> '" . $id_detail . "'");

            if ($jum_data > 0) {
                  $data['inputerror'][] = 'cboGudangEdit';
                  $data['error_string'][] = 'Barang dengan warna sudah terdaftar!';
                  $data['status'] = FALSE;
            } else {
                  $data['inputerror'][] = 'cboGudangEdit';
                  $data['error_string'][] = '';
            }

            if ($barang == '') {
                  $data['inputerror'][] = 'txtBarangEdit';
                  $data['error_string'][] = 'Barang belum diisi!';
                  $data['status'] = FALSE;
            } else {
                  $data['inputerror'][] = 'txtBarangEdit';
                  $data['error_string'][] = '';
            }

            if ($qty == 0) {
                  $data['inputerror'][] = 'txtQtyEdit';
                  $data['error_string'][] = 'Qty belum diisi!';
                  $data['status'] = FALSE;
            } else {
                  $data['inputerror'][] = 'txtQtyEdit';
                  $data['error_string'][] = '';
            }

            if ($harga == 0) {
                  $data['inputerror'][] = 'txtHargaEdit';
                  $data['error_string'][] = 'Harga belum diisi!';
                  $data['status'] = FALSE;
            } else {
                  $data['inputerror'][] = 'txtHargaEdit';
                  $data['error_string'][] = '';
            }

            if ($data['status'] == FALSE) {
                  echo json_encode($data);
                  exit();
            }
      }

      public function barang_edit_validate_start()
      {
            $this->barang_edit_validate();

            echo json_encode(array("status" => TRUE));
      }

      // hapus barang
      function barang_delete()
      {
            $id = $this->input->post('id');

            // hapus detail
            $this->common_model->db_delete('nd_pembelian_lain_detail', 'id', $id);

            echo 'OK';
      }

      // lock data
      function lock()
      {
            $id = $this->input->post('id');

            $data = array(
                  'status' => 0
            );

            $this->common_model->db_update('nd_pembelian_lain', $data, 'id', $id);

            echo 'OK';
      }

      // unlock transaction
      function unlock()
      {
            $id_header = $this->input->post('txtPINHeaderId');

            $data = array(
                  'status' => 1
            );

            $this->common_model->db_update("nd_pembelian_lain", $data, 'id', $id_header);

            redirect(is_setting_link('admin/transaction/transaksi/pembelian_lain/editor') . '?id=' . $id_header);
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