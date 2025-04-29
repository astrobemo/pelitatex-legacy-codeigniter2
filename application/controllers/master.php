<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Master extends CI_Controller
{

	private $data = [];

	function __construct()
	{
		parent::__construct();

		is_logged_in();
		if (is_username() == '') {
			redirect('home');
		} elseif (is_user_time() == false) {
			redirect('home');
		}

		if (is_maintenance_on() && $row->posisi_id != 1) {
			redirect(base_url() . 'home/maintenance_mode');
		}

		$this->data['username'] = is_username();
		$this->load->model('inventory_model', 'inv_model', true);

		$this->data['user_menu_list'] = is_user_menu(is_posisi_id());
		$this->supplier_list_aktif = $this->common_model->db_select('nd_supplier where status_aktif = 1 ORDER BY nama asc');
		$this->customer_list_aktif = $this->common_model->db_select('nd_customer where status_aktif = 1');
		$this->toko_list_aktif = $this->common_model->db_select('nd_toko where status_aktif = 1');
		$this->gudang_list_aktif = $this->common_model->db_select('nd_gudang where status_aktif = 1 ORDER BY urutan asc');

		$this->warna_list_aktif = $this->common_model->db_select('nd_warna where status_aktif = 1');
		$this->barang_list_aktif = $this->common_model->get_barang_list_aktif();
		$this->barang_list_aktif_beli = $this->common_model->get_barang_list_aktif_beli();
		$this->satuan_list_aktif = $this->common_model->db_select('nd_satuan where status_aktif = 1');

		// $this->category_list_aktif = $this->common_model->db_select('nd_category where status_aktif = 1');
		date_default_timezone_set("Asia/Jakarta");
		$this->pre_faktur = get_pre_faktur();
	}

	function index()
	{
		redirect('admin/dashboard');
	}

	function ubah_status_aktif()
	{
		$data_get = explode('=?=', $this->input->get('data_sent'));
		$data = array('status_aktif' => $data_get[0]);
		$this->common_model->db_update('nd_' . $data_get[2], $data, 'id', $data_get[1]);
		$link = $this->input->get('link');
		redirect(is_setting_link('master/' . $link));
	}

	function ubah_status_aktif_with_filter()
	{
		$data_get = explode('=?=', $this->input->get('data_sent'));
		$data = array('status_aktif' => $data_get[0]);
		$this->common_model->db_update('nd_' . $data_get[2], $data, 'id', $data_get[1]);
		$link = $this->input->get('link');
		$filter_id = $data_get[3];
		redirect(is_setting_link('master/' . $link) . '/' . $filter_id);
	}

	//===================================ajax_check=============================================

	function check_user()
	{
		$username = $this->input->post('username');
		$id = '';
		$result = $this->common_model->db_select_cond('nd_user', 'username', $username, '');
		foreach ($result as $row) {
			$id = $row->id;
		}
		if ($id != '') {
			echo 'false';
		} else {
			echo 'true';
		}
	}

	function check_user_edit()
	{
		$username = $this->input->post('username');
		$id = $this->input->post('user_id');

		$result = $this->common_model->db_select_cond('nd_user', 'username', $username, '');
		$check = '';
		foreach ($result as $row) {
			$check = $row->id;
		}
		if ($check != '') {
			// echo $id;
			if ($check == $id) {
				echo 'true';
			} else {
				echo 'false';
			}
		} else {
			echo 'true';
		}
	}

	//================================user list================================================

	function user_list()
	{
		$menu = is_get_url($this->uri->segment(1));

		$data = array(
			'content' => 'admin/master/user_list',
			'breadcrumb_title' => 'Master',
			'breadcrumb_small' => 'Daftar User',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data' => $this->data
		);

		$data['user_id'] = is_user_id();
		$data['posisi_list'] = $this->common_model->db_select('pelita_menu.nd_posisi where id != 1');
		$data['user_list'] = $this->common_model->get_user_list();
		$this->load->view('admin/template', $data);
	}

	private function user_validate($tipe = 'new', $id = 0)
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		//validasi nama 
		$username = $this->input->post('username');

		if ($username == '') {
			$data['inputerror'][] = 'username';
			$data['error_string'][] = 'Nama harus diisi!';
			$data['status'] = FALSE;
		} else {
			//cek apakah double 
			if ($tipe == 'new') {
				$hasil_nama = $this->common_model->db_select_num_rows("nd_user where username = '" . $username . "'");
			} else {
				$hasil_nama = $this->common_model->db_select_num_rows("nd_user where username = '" . $username . "' AND id <> '" . $id . "'");
			}

			if ($hasil_nama > 0) {
				$data['inputerror'][] = 'username';
				$data['error_string'][] = 'Nama sudah terdaftar!';
				$data['status'] = FALSE;
			}
		}

		if ($data['status'] == FALSE) {
			echo json_encode($data);
			exit();
		}
	}

	public function user_validate_start()
	{
		$tipe = $this->uri->segment(3);
		$id = $this->uri->segment(4);

		$this->user_validate($tipe, $id);

		echo json_encode(array("status" => TRUE));
	}

	function user_list_insert()
	{
		$data = array(
			'username' => $this->input->post('username'),
			'password' => md5($this->input->post('password')),
			'posisi_id' => $this->input->post('posisi_id'),
			'time_start' => $this->input->post('time_start'),
			'time_end' => $this->input->post('time_end')
		);

		$this->common_model->db_insert('nd_user', $data);

		redirect(is_setting_link('master/user_list'));
	}

	function user_list_update()
	{

		$id = $this->input->post('user_id');

		if ($this->input->post('password') == '') {
			$data = array(
				'username' => $this->input->post('username'),
				'posisi_id' => $this->input->post('posisi_id'),
				'time_start' => $this->input->post('time_start'),
				'time_end' => $this->input->post('time_end')
			);
		} else {
			$data = array(
				'username' => $this->input->post('username'),
				'password' => md5($this->input->post('password')),
				'posisi_id' => $this->input->post('posisi_id'),
				'time_start' => $this->input->post('time_start'),
				'time_end' => $this->input->post('time_end')
			);
		}

		$this->common_model->db_update('nd_user', $data, 'id', $id);

		redirect(trim(base64_encode('master/user_list'), '='));
	}

	function user_list_status_update()
	{
		$id = $this->input->get('id');
		$data = array(
			'status_aktif' => $this->input->get('status_aktif')
		);

		$this->common_model->db_update('nd_user', $data, 'id', $id);
		redirect(trim(base64_encode('master/user_list'), '='));
	}

	//================================pengiriman list================================================

	function get_nama_customer($id)
	{
		$hasil = $this->common_model->db_select("nd_customer where id = '" . $id . "'");

		$hasil_ku = '';

		foreach ($hasil as $key) {
			$hasil_ku = $key->nama;
		}

		return $hasil_ku;
	}

	function pengiriman_list()
	{
		$menu = is_get_url($this->uri->segment(1));
		$id = $this->uri->segment(2);

		$data = array(
			'content' => 'admin/master/pengiriman_list',
			'breadcrumb_title' => 'Master',
			'breadcrumb_small' => 'Alamat Lain',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data' => $this->data
		);

		$data['user_id'] = is_user_id();
		$data['customer'] = $this->get_nama_customer($id);
		$data['customer_id'] = $id;
		$data['alamat'] = $this->common_model->db_select("nd_customer_alamat_kirim where customer_id = '" . $id . "'");
		$this->load->view('admin/template', $data);
	}

	function pengiriman_list_insert()
	{
		$data = array(
			'customer_id' => $this->input->post('customer_id'),
			'alamat' => $this->input->post('alamat'),
			'catatan' => $this->input->post('catatan'),
			'user_id' => is_user_id(),
			'created_at' => date('Y-m-d H:i:s'),
			'updated_at' => date('Y-m-d H:i:s'),
			'status_aktif' => 1
		);

		$this->common_model->db_insert('nd_customer_alamat_kirim', $data);

		redirect(trim(base64_encode('master/pengiriman_list'), '=') . '/' . $this->input->post('customer_id'));
	}

	function pengiriman_list_update()
	{
		$id = $this->input->post('id');

		$data = array(
			'alamat' => $this->input->post('alamat'),
			'catatan' => $this->input->post('catatan')
		);

		$this->common_model->db_update('nd_customer_alamat_kirim', $data, 'id', $id);

		redirect(trim(base64_encode('master/pengiriman_list'), '=') . '/' . $this->input->post('customer_id'));
	}


	//================================satuan list================================================

	function satuan_list()
	{
		$menu = is_get_url($this->uri->segment(1));

		$data = array(
			'content' => 'admin/master/satuan_list',
			'breadcrumb_title' => 'Master',
			'breadcrumb_small' => 'Daftar Satuan',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data' => $this->data
		);

		$data['user_id'] = is_user_id();
		$data['satuan_list'] = $this->common_model->db_select('nd_satuan');
		$this->load->view('admin/template', $data);
	}

	private function satuan_validate($tipe = 'new', $id = 0)
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		//validasi nama 
		$nama = $this->input->post('nama');

		if ($nama == '') {
			$data['inputerror'][] = 'nama';
			$data['error_string'][] = 'Nama harus diisi!';
			$data['status'] = FALSE;
		} else {
			//cek apakah double 
			if ($tipe == 'new') {
				$hasil_nama = $this->common_model->db_select_num_rows("nd_satuan where nama = '" . $nama . "'");
			} else {
				$hasil_nama = $this->common_model->db_select_num_rows("nd_satuan where nama = '" . $nama . "' AND id <> '" . $id . "'");
			}

			if ($hasil_nama > 0) {
				$data['inputerror'][] = 'nama';
				$data['error_string'][] = 'Nama sudah terdaftar!';
				$data['status'] = FALSE;
			}
		}

		if ($data['status'] == FALSE) {
			echo json_encode($data);
			exit();
		}
	}

	public function satuan_validate_start()
	{
		$tipe = $this->uri->segment(3);
		$id = $this->uri->segment(4);

		$this->satuan_validate($tipe, $id);

		echo json_encode(array("status" => TRUE));
	}

	function satuan_list_insert()
	{
		$data = array(
			'nama' => $this->input->post('nama')
		);

		$this->common_model->db_insert('nd_satuan', $data);

		redirect(trim(base64_encode('master/satuan_list'), '='));
	}

	function satuan_list_update()
	{

		$id = $this->input->post('id');

		$data = array(
			'nama' => $this->input->post('nama')
		);

		$this->common_model->db_update('nd_satuan', $data, 'id', $id);

		redirect(trim(base64_encode('master/satuan_list'), '='));
	}

	//================================printer list================================================

	function printer_list()
	{
		$menu = is_get_url($this->uri->segment(1));

		$data = array(
			'content' => 'admin/master/printer_list',
			'breadcrumb_title' => 'Master',
			'breadcrumb_small' => 'Daftar Printer',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data' => $this->data
		);

		$data['user_id'] = is_user_id();
		$data['printer_list'] = $this->common_model->db_select('nd_printer_list');
		$this->load->view('admin/template', $data);
	}

	private function printer_validate($tipe = 'new', $id = 0)
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		//validasi nama 
		$nama = $this->input->post('nama');

		if ($nama == '') {
			$data['inputerror'][] = 'nama';
			$data['error_string'][] = 'Nama harus diisi!';
			$data['status'] = FALSE;
		} else {
			//cek apakah double 
			if ($tipe == 'new') {
				$hasil_nama = $this->common_model->db_select_num_rows("nd_printer_list where nama = '" . $nama . "'");
			} else {
				$hasil_nama = $this->common_model->db_select_num_rows("nd_printer_list where nama = '" . $nama . "' AND id <> '" . $id . "'");
			}

			if ($hasil_nama > 0) {
				$data['inputerror'][] = 'nama';
				$data['error_string'][] = 'Nama sudah terdaftar!';
				$data['status'] = FALSE;
			}
		}

		if ($data['status'] == FALSE) {
			echo json_encode($data);
			exit();
		}
	}

	public function printer_validate_start()
	{
		$tipe = $this->uri->segment(3);
		$id = $this->uri->segment(4);

		$this->printer_validate($tipe, $id);

		echo json_encode(array("status" => TRUE));
	}

	function printer_list_insert()
	{
		$data = array(
			'nama' => $this->input->post('nama')
		);
		$this->common_model->db_insert('nd_printer_list', $data);
		redirect(trim(base64_encode('master/printer_list'), '='));
	}

	function printer_list_update()
	{

		$id = $this->input->post('printer_id');

		$data = array(
			'nama' => $this->input->post('nama')
		);

		$this->common_model->db_update('nd_printer_list', $data, 'id', $id);
		redirect(trim(base64_encode('master/printer_list'), '='));
	}

	/**
//================================barang list================================================
	 **/
	function barang_list()
	{
		$menu = is_get_url($this->uri->segment(1));

		$data = array(
			'content' => 'admin/master/barang_list',
			'breadcrumb_title' => 'Master',
			'breadcrumb_small' => 'Daftar Barang',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data' => $this->data
		);

		$data['user_id'] = is_user_id();
		$data['satuan_list'] = $this->common_model->db_select('nd_satuan');
		$data['barang_beli'] = $this->common_model->db_select('nd_barang_beli');
		$data['barang_list'] = array();

		$data['info_barang_beli'] = $this->session->flashdata('beli_update');

		$this->load->view('admin/template', $data);
	}

	function data_barang()
	{

		// $session_data = $this->session->userdata('do_filter');

		$aColumns = array('status_aktif', 'nama', 'nama_jual', 'nama_satuan', 'harga_jual', 'harga_beli', 'status_barang');

		$sIndexColumn = "id";

		// paging
		$sLimit = "";

		if (isset($_GET['iDisplayStart']) && $_GET['iDisplayLength'] != '-1') {
			$sLimit = "LIMIT " . mysql_real_escape_string($_GET['iDisplayStart']) . ", " .
				mysql_real_escape_string($_GET['iDisplayLength']);
		}

		$numbering = mysql_real_escape_string($_GET['iDisplayStart']);

		$page = 1;

		// ordering
		if (isset($_GET['iSortCol_0'])) {
			$sOrder = "ORDER BY  ";
			for ($i = 0; $i < intval($_GET['iSortingCols']); $i++) {
				if ($_GET['bSortable_' . intval($_GET['iSortCol_' . $i])] == "true") {
					$sOrder .= $aColumns[intval($_GET['iSortCol_' . $i])] . "
                        " . mysql_real_escape_string($_GET['sSortDir_' . $i]) . ", ";
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

		$rResult = $this->common_model->get_barang_list_ajax($aColumns, $sWhere/*, $sOrder*/, $sLimit);

		// $iFilteredTotal = 5;

		$rResultTotal = $this->common_model->db_select_num_rows('nd_barang');
		$Filternya = $this->common_model->get_barang_list_ajax($aColumns, $sWhere/*, $sOrder*/, '');
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

	function get_harga_history(){
		$cond_barang = '';
		if($this->input->post('barang_id') && $this->input->post('barang_id') != '' ){
			$cond_barang = "WHERE barang_id = ".$this->input->post('barang_id');
		}

		// if($this->input->post('warna_id') && $this->input->post('warna_id') != '' ){
		// 	$cond_barang .= "AND warna_id = ".$this->input->post('warna_id');
		// }
		$data['harga_jual_history_all'] = $this->common_model->barang_history_harga_jual_all($cond_barang,"WHERE harga_master != harga_jual");
		$data['harga_jual_history_master_all'] = $this->common_model->barang_history_harga_jual_all($cond_barang, "WHERE harga_master = harga_jual");
		$data['harga_jual_history_master'] = $this->common_model->harga_history_master($cond_barang);
		// $data['harga_jual_history'] = $this->common_model->barang_history_harga_jual($cond_barang);
		$data['harga_jual_credit_history'] = $this->common_model->barang_history_harga_jual_credit($cond_barang);
		$data['harga_beli_history'] = $this->common_model->barang_history_harga_beli_by_po($cond_barang);
		echo json_encode($data);
	}

	private function barang_validate($tipe = 'new', $id = 0)
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		//validasi nama beli
		$nama = $this->input->post('nama');

		if ($tipe == 'new' && $nama == '') {
			$data['inputerror'][] = 'nama';
			$data['error_string'][] = 'Nama Beli harus diisi!';
			$data['status'] = FALSE;
		} else {
			//cek apakah double 
			if ($tipe == 'new') {
				$hasil_nama = $this->common_model->db_select_num_rows("nd_barang_beli where nama = '" . $nama . "' AND status_aktif = 1");
			} else {
				$hasil_nama = $this->common_model->db_select_num_rows("nd_barang_beli where nama = '" . $nama . "' AND status_aktif = 1 AND id <> '" . $id . "'");
			}

			if ($hasil_nama > 0) {
				$data['inputerror'][] = 'nama';
				$data['error_string'][] = 'Nama Beli sudah terdaftar!';
				$data['status'] = FALSE;
			}
		}

		//validasi nama jual
		$nama_jual = $this->input->post('nama_jual');

		if ($nama_jual == '') {
			$data['inputerror'][] = 'nama_jual';
			$data['error_string'][] = 'Nama Jual harus diisi!';
			$data['status'] = FALSE;
		} else {
			//cek apakah double 
			if ($tipe == 'new') {
				$hasil_nama_jual = $this->common_model->db_select_num_rows("nd_barang where nama_jual = '" . $nama_jual . "' AND status_aktif = 1");
			} else {
				$hasil_nama_jual = $this->common_model->db_select_num_rows("nd_barang where nama_jual = '" . $nama_jual . "' AND status_aktif = 1 AND id <> '" . $id . "'");
			}

			if ($hasil_nama_jual > 0) {
				$data['inputerror'][] = 'nama_jual';
				$data['error_string'][] = 'Nama Jual sudah terdaftar!';
				$data['status'] = FALSE;
			}
		}

		//cek harga jual tidak boleh dibawah harga beli
		$harga_jual = ($this->input->post('harga_jual') != '' ? $this->input->post('harga_jual') : 0);
		$harga_jual = str_replace('.', '', $harga_jual);

		$harga_beli = ($this->input->post('harga_beli') != '' ? $this->input->post('harga_beli') : 0);
		$harga_beli = str_replace('.', '', $harga_beli);

		if ($harga_jual < $harga_beli) {
			$data['inputerror'][] = 'harga_jual';
			$data['error_string'][] = 'Harga Jual tidak boleh lebih rendah dari harga beli!';
			$data['status'] = FALSE;
		}

		if ($data['status'] == FALSE) {
			echo json_encode($data);
			exit();
		}
	}

	public function barang_validate_start()
	{
		$tipe = $this->uri->segment(3);
		$id = $this->uri->segment(4);

		$this->barang_validate($tipe, $id);

		echo json_encode(array("status" => TRUE));
	}

	function barang_list_insert()
	{
		$harga_jual = ($this->input->post('harga_jual') != '' ? $this->input->post('harga_jual') : 0);
		$harga_jual = str_replace('.', '', $harga_jual);

		$harga_beli = ($this->input->post('harga_beli') != '' ? $this->input->post('harga_beli') : 0);
		$harga_beli = str_replace('.', '', $harga_beli);
		$harga_beli = str_replace(',', '.', $harga_beli);

		$jenis_barang = $this->input->post('jenis_barang');
		if($jenis_barang == 0 || $jenis_barang == ''){
			$jenis_barang = "POLYESTER";
		}

		$data = array(
			'nama' => $this->input->post('nama'),
			'nama_jual' => $this->input->post('nama_jual'),
			'harga_jual' => $harga_jual,
			'harga_beli' => $harga_beli,
			'satuan_id' => $this->input->post('satuan_id'),
			'status_aktif' => $this->input->post('status_aktif')
		);
		
		$result_id = $this->common_model->db_insert('nd_barang', $data);

		$data_beli = array(
			'nama' => $this->input->post('nama'),
			'barang_id' => $result_id,
			'user_id' => is_user_id()
		);

		$this->common_model->db_insert('nd_barang_beli', $data_beli);


		$data_harga = array(
			'tanggal' => date('Y-m-d'),
			'barang_id' => $result_id,
			'harga_beli' => $harga_beli,
			'harga_jual' => $harga_jual,
			'user_id' => is_user_id()
		);

		$this->common_model->db_insert('nd_barang_harga_history', $data_harga);

		$getData = $this->common_model->db_select("nd_group_harga_barang");
		$nData = array();
		foreach ($getData as $row) {
			array_push($nData, array(
				'group_harga_barang_id' => $row->id,
				'barang_id' => $result_id,
				'harga_berlaku' => $harga_jual
			));
		}


		if (count($nData) > 0) {
			$this->common_model->db_insert_batch("nd_group_harga_berlaku", $nData);
		}
		redirect(is_setting_link('master/barang_list'));
	}
	

	function barang_list_update()
	{
		$id = $this->input->post('id');

		$harga_jual = ($this->input->post('harga_jual') != '' ? $this->input->post('harga_jual') : 0);
		$harga_jual = str_replace('.', '', $harga_jual);

		$harga_beli = ($this->input->post('harga_beli') != '' ? $this->input->post('harga_beli') : 0);
		$harga_beli = str_replace('.', '', $harga_beli);
		$harga_beli = str_replace(',', '.', $harga_beli);

		$data = array(
			// 'nama' => $this->input->post('nama'),
			'nama_jual' => $this->input->post('nama_jual'),
			'harga_jual' => $harga_jual,
			'harga_beli' => $harga_beli,
			'satuan_id' => $this->input->post('satuan_id'),
			'status_aktif' => $this->input->post('status_aktif')
		);

		$this->common_model->db_update('nd_barang', $data, 'id', $id);

		$get_latest_harga = $this->common_model->db_select("nd_barang_harga_history WHERE barang_id=$id AND harga_jual='$harga_jual' AND harga_beli = '$harga_beli'");
		if (count($get_latest_harga) == 0) {
			$data_harga = array(
				'tanggal' => date('Y-m-d'),
				'barang_id' => $id,
				'harga_beli' => $harga_beli,
				'harga_jual' => $harga_jual,
				'user_id' => is_user_id()
			);
			$this->common_model->db_insert('nd_barang_harga_history', $data_harga);
		}

		redirect(is_setting_link('master/barang_list'));
	}

	function cek_nama_beli(){
		$id = $this->input->post('id');
		$nama = $this->input->post('nama');
		$cond = '';

		if ($id != '') {
			$cond = " AND id != '$id' ";
		}

		$get = $this->common_model->db_select("nd_barang_beli WHERE nama ='$nama' $cond AND status_aktif = 1");
		if (count($get) == 0) {
			$res = "OK";
		}else{
			$res = "Nama sudah terpakai";
		}
		echo json_encode($res);
	}

	function barang_beli_insert(){
		$barang_id = $this->input->post('barang_id');
		$nama = $this->input->post('nama');
		$nama_jual = $this->input->post('nama_jual');
		
		$data = array(
			'barang_id' => $barang_id,
			'nama' => $nama );
		$this->common_model->db_insert("nd_barang_beli", $data,'id', $id);
		$this->session->set_flashdata('beli_update', "Nama Beli $nama_lama ditambahkan kepada $nama_jual");

		redirect(is_setting_link('master/barang_list'));
		
	}

	function barang_beli_update(){
		$id = $this->input->post('id');
		$nama = $this->input->post('nama');
		$get = $this->common_model->db_select("nd_barang_beli WHERE id = '$id' ");
		foreach ($get as $row) {
			$nama_lama = $row->nama;
		}

		$data = array(
			'nama' => $nama );
		$this->common_model->db_update("nd_barang_beli", $data,'id', $id);
		$this->session->set_flashdata('beli_update', "Nama Beli $nama_lama diubah menjadi $nama");

		redirect(is_setting_link('master/barang_list'));
		
	}

	function remove_nama_beli()
	{
		$id = $this->input->post('id');
		$nama = $this->input->post('nama');

		$data = array(
			'status_aktif' => 0
		);

		$this->common_model->db_update('nd_barang_beli', $data, 'id', $id);
		

		echo json_encode("OK");
	}

	function barang_group()
	{
		$menu = is_get_url($this->uri->segment(1));

		$data = array(
			'content' => 'admin/master/barang_group',
			'breadcrumb_title' => 'Master',
			'breadcrumb_small' => 'barang group',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data' => $this->data
		);


		$data['barang_group_list'] = $this->common_model->get_barang_group_list();
		$this->load->view('admin/template', $data);
	}

	function barang_group_insert()
	{
		$data = array(
			'barang_id' => $this->input->post('barang_id'),
			'barang_id_induk' => $this->input->post('barang_id_induk'),
			'user_id' => is_user_id()
		);

		$this->common_model->db_insert('nd_barang_group', $data);

		redirect(is_setting_link('master/barang_group'));
	}

	function barang_group_update()
	{
		$id = $this->input->post('id');
		$data = array(
			'barang_id' => $this->input->post('barang_id'),
			'barang_id_induk' => $this->input->post('barang_id_induk'),
			'user_id' => is_user_id()
		);
		$this->common_model->db_update('nd_barang_group', $data, 'id', $id);

		redirect(is_setting_link('master/barang_group'));
	}

	function barang_profile()
	{
		$menu = is_get_url($this->uri->segment(1));
		$barang_id = $this->uri->segment(2);
		$tahun = date('Y');
		if ($this->input->get('tahun') != '' && $this->input->get('id') != '') {
			$tahun = $this->input->get('tahun');
			$barang_id = $this->input->get('id');
		}

		$tanggal_awal = date("Y-m-01");
		$tanggal_akhir = date("Y-m-t");
		$warna_id = '';
		if ($this->input->get('tanggal_awal') != '' && $this->input->get('tanggal_akhir') != '') {
			$tanggal_awal = is_date_formatter($this->input->get('tanggal_awal'));
			$tanggal_akhir = is_date_formatter($this->input->get('tanggal_akhir'));
		}

		if ($this->input->get('warna_id') != '') {
			$warna_id = $this->input->get('warna_id');
		}

		$data = array(
			'content' => 'admin/master/barang_profile_2',
			'breadcrumb_title' => 'Master',
			'breadcrumb_small' => 'Profil Barang',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'barang_id' => $barang_id,
			'warna_id' => $warna_id,
			'tahun' => $tahun,
			'tanggal_awal' => $tanggal_awal,
			'tanggal_akhir' => $tanggal_akhir,
			'common_data' => $this->data
		);

		$data['data_barang'] = $this->common_model->db_select('nd_barang where id=' . $barang_id);
		$data['data_warna'] = $this->common_model->get_warna_asosiasi($barang_id);
		$data['data_penjualan'] = $this->common_model->get_penjualan_report_limit_by_barang($barang_id, $tahun);
		// $data['customer_profile_hutang'] = $this->common_model->get_customer_profile_piutang($customer_id); 
		// $data['customer_dp'] = $this->common_model->get_dp_by_customer($customer_id); 
		// $data['data_penjualan'] = $this->common_model->get_data_penjualan($customer_id);

		// $limit = "LIMIT ".(is_posisi_id() <= 5 ? '30' : '1');
		if (is_posisi_id() == 1) {
			// print_r($data['data_penjualan']);
		}
		// else{
		$this->load->view('admin/template_no_sidebar', $data);
		// echo "<h1>UNDER CONSTRUCTION</h1>";
		// }
	}

	function get_penjualan_by_barang_warna()
	{

		$barang_id = $this->input->get('barang_id');
		$tahun = $this->input->get('tahun');
		$tipe = $this->input->get('tipe');
		if ($tipe == 0) {
			$result = $this->common_model->get_penjualan_report_by_barang_warna($barang_id, $tahun);
		} else {
			$result = $this->common_model->get_penjualan_report_by_barang_customer($barang_id, $tahun);
		}
		echo json_encode($result);
	}

	function barang_forecasting()
	{
		$menu = is_get_url($this->uri->segment(1));
		$barang_id = $this->input->get('id');
		$tanggal_start = date('Y-01-01');
		$tanggal_end = date('Y-12-31');

		$tanggal_start_forecast = date('Y-m-d', strtotime($tanggal_start . ' -1 year'));
		$tanggal_end_forecast = date('Y-m-d', strtotime($tanggal_end . ' -1 year'));

		$tahun = date("Y", strtotime($tanggal_start));

		if ($this->input->get('tanggal_start') != '' && $this->input->get('tanggal_end') != '') {
			$tanggal_start = date('Y-m-01', strtotime($this->input->get('tanggal_start')));
			$tanggal_end = date('Y-m-28', strtotime($this->input->get('tanggal_end')));

			$tanggal_start_forecast = date('Y-m-01', strtotime($tanggal_start . ' -1 year'));;
			$tanggal_end_forecast = date('Y-m-t', strtotime($tanggal_end . ' -1 year'));

			$tanggal_end = date('Y-m-t', strtotime($this->input->get('tanggal_end')));
			$tahun = date("Y", strtotime($tanggal_start));
		}

		$data = array(
			'content' => 'admin/master/barang_forecasting',
			'breadcrumb_title' => 'Master',
			'breadcrumb_small' => 'Forecasting',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'barang_id' => $barang_id,
			'common_data' => $this->data,
			'tanggal_start' => is_reverse_date($tanggal_start),
			'tanggal_end' => is_reverse_date($tanggal_end),
			'tanggal_start_forecast' => $tanggal_start_forecast,
			'tanggal_end_forecast' => $tanggal_end_forecast,
			'tahun' => $tahun
		);

		$data['user_id'] = is_user_id();
		$data['data_barang'] = $this->common_model->db_select('nd_barang where id=' . $barang_id);
		$data['data_warna'] = $this->common_model->get_warna_asosiasi($barang_id);
		$data['data_penjualan'] = $this->common_model->get_penjualan_for_forecast($barang_id, $tanggal_start_forecast, $tanggal_end_forecast);
		// $data['data_penjualan_update'] = $this->common_model->get_penjualan_report_limit_by_barang($barang_id, $tahun);
		$data['penjualan_berjalan'] = $this->common_model->get_penjualan_bulan_berjalan($barang_id, $tanggal_start, $tanggal_end);

		if (is_posisi_id() == 1) {
			// echo $barang_id.' '.$tanggal_start.' '.$tanggal_end.' '. $tanggal_start_forecast.' '.$tanggal_end_forecast." <br/> ";
			// echo $this->input->get('tanggal_start').' '.$this->input->get('tanggal_end');
			// $data['content'] = 'admin/master/barang_forecasting_2';
			// echo $tanggal_start, $tanggal_end;
			// echo "<hr/>";
			// print_r($data['penjualan_berjalan']);
			// foreach ($data['penjualan_berjalan'] as $row) {
			// 	print_r($row);
			// 	echo '<hr/>';
			// }
			$this->load->view('admin/template', $data);
		}
			else{
			$this->load->view('admin/template', $data);
			// $this->load->view('admin/template', $data);
		# code...
		// 	echo $barang_id, $tanggal_start_forecast, $tanggal_end_forecast;
		}
	}

	function barang_planner()
	{
		$menu = is_get_url($this->uri->segment(1));
		$barang_id = $this->input->get('id');
		// $tahun = date('Y') - 1;

		if (is_posisi_id() <= 3) {
			// redirect(is_setting_link('master/barang_planner_3').'?id='.$barang_id);
		}


		$tanggal_start = date('Y-m-01');
		$tanggal_end = date('Y-m-01', strtotime('+5 months'));
		// $tanggal_end = date('Y-12-31');

		$tanggal_start_forecast = date('Y-m-01', strtotime($tanggal_start . ' -1 year'));
		$tanggal_end_forecast = date('Y-m-t', strtotime($tanggal_end . ' -1 year'));

		if ($this->input->get('tanggal_start') != '' && $this->input->get('tanggal_end') != '') {
			$tanggal_start = date('Y-m-01', strtotime($this->input->get('tanggal_start')));
			$tanggal_end = date('Y-m-t', strtotime($this->input->get('tanggal_end')));


			$tanggal_start_forecast = date('Y-m-01', strtotime($tanggal_start . ' -1 year'));;
			$tanggal_end_forecast = date('Y-m-t', strtotime($tanggal_end . ' -1 year'));
		}

		$data = array(
			'content' => 'admin/master/barang_planner_3',
			'breadcrumb_title' => 'Master',
			'breadcrumb_small' => 'Planner',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'barang_id' => $barang_id,
			'common_data' => $this->data,
			'tanggal_start' => $tanggal_start,
			'tanggal_end' => $tanggal_end,
			'tanggal_start_forecast' => $tanggal_start_forecast,
			'tanggal_end_forecast' => $tanggal_end_forecast
		);

		$data['user_id'] = is_user_id();
		$data['data_barang'] = $this->common_model->db_select('nd_barang where id=' . $barang_id);
		$data['request_barang'] = $this->common_model->get_latest_request_by_barang($barang_id, $tanggal_start_forecast);
		$data['data_warna'] = $this->common_model->get_warna_asosiasi($barang_id);
		$data['data_penjualan'] = $this->common_model->get_penjualan_for_forecast($barang_id, $tanggal_start_forecast, $tanggal_end_forecast);
		// $data['data_pembelian_now'] = $this->common_model->get_data_pembelian_by_date($barang_id, $tanggal_start, $tanggal_end);
		$data['data_outstanding_po'] = $this->common_model->get_outstanding_barang($barang_id, date('Y-m-d'));
		// $data['penjualan_berjalan'] = $this->common_model->get_penjualan_bulan_berjalan($barang_id, $tanggal_start, $tanggal_end);

		$tanggal_awal = '2018-01-01';
		$stok_opname_id = 1;
		$getOpname = $this->common_model->db_select("nd_stok_opname where tanggal <= '" . date('Y-m-d') . "' order by tanggal desc limit 1");
		foreach ($getOpname as $row) {
			// $tanggal_awal = $row->tanggal;
			// $stok_opname_id = $row->id;
		}

		$data['gudang_list'] = $this->gudang_list_aktif;
		$data['stok_barang'] = array();

		$select = '';
		$select2 = "";
		$select_all = '';
		$columnSelect = array();
		$dt[0] = 'urutan';
		$dt[1] = 'nama_barang_jual';
		$dt[2] = 'status_aktif';
		$dt[3] = 'last_edit';
		$cond_filter = '';

		$idx = 4;
		$i = 0;
		$cond_qty = "";
		foreach ($this->gudang_list_aktif as $row) {
			$select .= ", ROUND(
				SUM( if(gudang_id=" . $row->id . ", 
						ifnull(
							if(tanggal_stok is not null, if(tanggal >= tanggal_stok,qty_masuk,0) , qty_masuk  ),
						0) ,
					0 )
				) - 
				SUM( if(gudang_id=" . $row->id . ", 
						ifnull( 
							if(tanggal_stok is not null, if(tanggal >= tanggal_stok, qty_keluar,0 ), qty_keluar ) 
						,0), 
					0 )
				),
				2)  
				as gudang_" . $row->id . "_qty ,

				SUM( if(gudang_id=" . $row->id . ", 
						if(tanggal_stok is not null, 
							if(tanggal >= tanggal_stok,jumlah_roll_masuk,0 ), 
						jumlah_roll_masuk), 
					0 ) 
				) -
				SUM( if(gudang_id=" . $row->id . ", 
						if(tanggal_stok is not null,
							if(tanggal >= tanggal_stok, jumlah_roll_keluar, 0)
						,jumlah_roll_keluar), 
					0 ) 
				)  
				as gudang_" . $row->id . "_roll,
				concat('" . $row->id . "','/',barang_id,'/',warna_id) as gudang_" . $row->id . "_button";

			$select_all .= ", SUM(ifnull(qty_masuk,0)) - SUM( ifnull(qty_keluar,0)) as qty, SUM(ifnull(jumlah_roll_masuk,0)) - SUM( ifnull(jumlah_roll_keluar,0)) as jumlah_roll ";

			if ($i == 0) {
				$cond_qty = "WHERE gudang_" . $row->id . "_qty > 0 ";
			} else {
				$cond_qty .= "OR gudang_" . $row->id . "_qty > 0 ";
			}

			$dt[$idx] = 'gudang_' . $row->id . '_qty';
			$idx++;
			$dt[$idx] = 'gudang_' . $row->id . '_roll';
			$idx++;
			$dt[$idx] = 'gudang_' . $row->id . '_button';
			$idx++;


			$qty_add[$i] = 'gudang_' . $row->id . '_qty';
			$roll_add[$i] = 'gudang_' . $row->id . '_roll';
			$i++;
		}

		$dt[$idx] = 'qty_total';
		$idx++;
		$dt[$idx] = 'roll_total';
		$idx++;

		$select2 .= ', if(tipe_qty != 3,' . implode('+', $qty_add) . ',"0") as qty_total, ' . implode('+', $roll_add) . ' as roll_total';
		$cond_filter .= 'OR (' . implode('+', $qty_add) . ') > 0';


		// echo $select;
		// echo $cond_barang;
		// echo "<hr/>";
		$select = $this->generate_select_versi_2();
		$data['stok_barang'] = $this->inv_model->get_stok_barang_list_by_barang_temp_2($tanggal_awal, $select, date('Y-m-d'), "AND barang_id=$barang_id", $stok_opname_id);
		$data['penjualan_berjalan'] = $this->common_model->get_penjualan_bulan_berjalan($barang_id, $tanggal_start, $tanggal_end);


		/* if (is_posisi_id() != 1) {
			$data['content'] = 'admin/master/barang_planner_3';
			$this->load->view('admin/template', $data);
			# code...
		}else{
			// print_r($data['request_barang']);
			// echo $barang_id, $tanggal_start_forecast;
			$data['content'] = 'admin/master/barang_planner_3'; 
			// echo $barang_id, $tanggal_start_forecast, $tanggal_end_forecast;
		} */
		$this->load->view('admin/template', $data);
		// else{
		// 	echo $tanggal_start_forecast, $tanggal_end_forecast;
		// 	print_r($data['data_penjualan']);
		// }
	}

	function planner_warna_hide(){
		$barang_id = $this->input->get('barang_id');
		$warna_id = $this->input->get('warna_id');
		$status = $this->input->get('status');
		$user_id = is_user_id();
		$id = '';
		$data = $this->common_model->db_select("nd_planner_warna_status WHERE barang_id=$barang_id AND warna_id = $warna_id AND user_id= $user_id");
		foreach ($data as $row) {
			$id = $row->id;
		}

		$nData = array(
			'barang_id' => $barang_id ,
			'warna_id' => $warna_id,
			'user_id' => $user_id,
			'status' => $status
		 );

		if ($id == '') {
			$this->common_model->db_insert("nd_planner_warna_status", $nData);
		}else{
			$this->common_model->db_update("nd_planner_warna_status", $nData, 'id', $id);
		}

		echo json_encode("OK");

	}

	function generate_select_versi_2()
	{
		$select_v2 = '';
		foreach ($this->gudang_list_aktif as $row) {
			$select_v2 .= ", ROUND(
				SUM( if(gudang_id=" . $row->id . ", 
						ifnull(
							if(tanggal_stok is not null, if(time_stamp >= tanggal_stok,qty_masuk,0) , qty_masuk  ),
						0) ,
					0 )
				),2) - 
				ROUND(SUM( if(gudang_id=" . $row->id . ", 
						ifnull( 
							if(tanggal_stok is not null, if(time_stamp >= tanggal_stok, qty_keluar,0 ), qty_keluar ) 
						,0), 
					0 )
				),
				2)  
				as gudang_" . $row->id . "_qty ,

				SUM( if(gudang_id=" . $row->id . ", 
						if(tanggal_stok is not null, 
							if(time_stamp >= tanggal_stok,jumlah_roll_masuk,0 ), 
						jumlah_roll_masuk), 
					0 ) 
				) -
				SUM( if(gudang_id=" . $row->id . ", 
						if(tanggal_stok is not null,
							if(time_stamp >= tanggal_stok, jumlah_roll_keluar, 0)
						,jumlah_roll_keluar), 
					0 ) 
				)  
				as gudang_" . $row->id . "_roll,
				concat('" . $row->id . "','/',barang_id,'/',warna_id) as gudang_" . $row->id . "_button";
		}

		return $select_v2;
	}

	function data_forecasting_update()
	{
		$barang_id = $this->input->post('barang_id');
		$warna_id = $this->input->post('warna_id');
		$period = $this->input->post('period') . "-01";

		$id = '';
		$get = $this->common_model->db_select("nd_barang_forecasting_data where barang_id=$barang_id AND warna_id=$warna_id AND period='$period'");
		$data = array(
			'barang_id' => $barang_id,
			'warna_id' => $warna_id,
			'period' => $period,
			'qty' => str_replace(".", "", $this->input->post('qty')),
			'user_id' => is_user_id()
		);

		if ($id == '') {
			$id = $this->common_model->db_insert("nd_barang_forecasting_data", $data);
		} else {
			$this->common_model->db_update("nd_barang_forecasting_data", $data, 'id', $id);
		}

		// print_r($this->input->post());
		// print_r($id);
		echo "OK";
	}

	function data_forecasting_update_keterangan()
	{
		$barang_id = $this->input->post('barang_id');
		$warna_id = $this->input->post('warna_id');
		$period = $this->input->post('period');

		$id = '';
		$get = $this->common_model->db_select("nd_barang_forecasting_keterangan where barang_id=$barang_id AND warna_id=$warna_id AND period='$period'");
		foreach ($get as $row) {
			$id = $row->id;
		}
		$data = array(
			'barang_id' => $barang_id,
			'warna_id' => $warna_id,
			'period' => $period,
			'keterangan' => $this->input->post('keterangan'),
			'user_id' => is_user_id()
		);

		if ($id == '') {
			// echo "nd_barang_forecasting_keterangan where barang_id=$barang_id AND warna_id=$warna_id AND period='$period'";
			$this->common_model->db_insert("nd_barang_forecasting_keterangan", $data);
		} else {
			// echo '2';
			$this->common_model->db_update("nd_barang_forecasting_keterangan", $data, 'id', $id);
		}

		
		echo "OK";
	}

	function get_penjualan_perbulan_by_tahun()
	{
		$barang_id = $this->input->post('barang_id');
		$tahun = $this->input->post('tahun');
	}


	/**
//================================request barang================================================
	 **/

	function barang_planner_2()
	{
		$menu = is_get_url($this->uri->segment(1));
		$barang_id = $this->input->get('id');
		// $tahun = date('Y') - 1;

		$tanggal_start = date('Y-m-01');
		$tanggal_end = date('Y-m-01', strtotime('+5 months'));
		// $tanggal_end = date('Y-12-31');

		$tanggal_start_forecast = date('Y-m-01', strtotime($tanggal_start . ' -1 year'));
		$tanggal_end_forecast = date('Y-m-t', strtotime($tanggal_end . ' -1 year'));

		if ($this->input->get('tanggal_start') != '' && $this->input->get('tanggal_end') != '') {
			$tanggal_start = date('Y-m-01', strtotime($this->input->get('tanggal_start')));
			$tanggal_end = date('Y-m-t', strtotime($this->input->get('tanggal_end')));

			$tanggal_start_forecast = date('Y-m-01', strtotime($tanggal_start . ' -1 year'));;
			$tanggal_end_forecast = date('Y-m-t', strtotime($tanggal_end . ' -1 year'));
		}

		$data = array(
			'content' => 'admin/master/barang_planner_2',
			'breadcrumb_title' => 'Master',
			'breadcrumb_small' => 'Planner + Request Barang',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'barang_id' => $barang_id,
			'common_data' => $this->data,
			'tanggal_start' => $tanggal_start,
			'tanggal_end' => $tanggal_end,
			'tanggal_start_forecast' => $tanggal_start_forecast,
			'tanggal_end_forecast' => $tanggal_end_forecast
		);

		$data['user_id'] = is_user_id();
		$data['data_barang'] = $this->common_model->db_select('nd_barang where id=' . $barang_id);
		$data['data_warna'] = $this->common_model->get_warna_asosiasi($barang_id);
		$data['data_penjualan'] = $this->common_model->get_penjualan_for_forecast($barang_id, $tanggal_start_forecast, $tanggal_end_forecast);
		// $data['data_pembelian_now'] = $this->common_model->get_data_pembelian_by_date($barang_id, $tanggal_start, $tanggal_end);
		// $data['penjualan_berjalan'] = $this->common_model->get_penjualan_bulan_berjalan($barang_id, $tanggal_start, $tanggal_end);

		$tanggal_awal = '2018-01-01';
		$stok_opname_id = 1;
		$getOpname = $this->common_model->db_select("nd_stok_opname where tanggal <= '" . date('Y-m-d') . "' order by tanggal desc limit 1");
		foreach ($getOpname as $row) {
			// $tanggal_awal = $row->tanggal;
			// $stok_opname_id = $row->id;
		}

		$data['gudang_list'] = $this->gudang_list_aktif;
		$data['stok_barang'] = array();

		$select = '';
		$select2 = "";
		$select_all = '';
		$columnSelect = array();
		$dt[0] = 'urutan';
		$dt[1] = 'nama_barang_jual';
		$dt[2] = 'status_aktif';
		$dt[3] = 'last_edit';
		$cond_filter = '';

		$idx = 4;
		$i = 0;
		$cond_qty = "";
		foreach ($this->gudang_list_aktif as $row) {
			$select .= ", ROUND(
				SUM( if(gudang_id=" . $row->id . ", 
						ifnull(
							if(tanggal_stok is not null, if(tanggal >= tanggal_stok,qty_masuk,0) , qty_masuk  ),
						0) ,
					0 )
				) - 
				SUM( if(gudang_id=" . $row->id . ", 
						ifnull( 
							if(tanggal_stok is not null, if(tanggal >= tanggal_stok, qty_keluar,0 ), qty_keluar ) 
						,0), 
					0 )
				),
				2)  
				as gudang_" . $row->id . "_qty ,

				SUM( if(gudang_id=" . $row->id . ", 
						if(tanggal_stok is not null, 
							if(tanggal >= tanggal_stok,jumlah_roll_masuk,0 ), 
						jumlah_roll_masuk), 
					0 ) 
				) -
				SUM( if(gudang_id=" . $row->id . ", 
						if(tanggal_stok is not null,
							if(tanggal >= tanggal_stok, jumlah_roll_keluar, 0)
						,jumlah_roll_keluar), 
					0 ) 
				)  
				as gudang_" . $row->id . "_roll,
				concat('" . $row->id . "','/',barang_id,'/',warna_id) as gudang_" . $row->id . "_button";

			$select_all .= ", SUM(ifnull(qty_masuk,0)) - SUM( ifnull(qty_keluar,0)) as qty, SUM(ifnull(jumlah_roll_masuk,0)) - SUM( ifnull(jumlah_roll_keluar,0)) as jumlah_roll ";

			if ($i == 0) {
				$cond_qty = "WHERE gudang_" . $row->id . "_qty > 0 ";
			} else {
				$cond_qty .= "OR gudang_" . $row->id . "_qty > 0 ";
			}

			$dt[$idx] = 'gudang_' . $row->id . '_qty';
			$idx++;
			$dt[$idx] = 'gudang_' . $row->id . '_roll';
			$idx++;
			$dt[$idx] = 'gudang_' . $row->id . '_button';
			$idx++;


			$qty_add[$i] = 'gudang_' . $row->id . '_qty';
			$roll_add[$i] = 'gudang_' . $row->id . '_roll';
			$i++;
		}

		$dt[$idx] = 'qty_total';
		$idx++;
		$dt[$idx] = 'roll_total';
		$idx++;

		$select2 .= ', if(tipe_qty != 3,' . implode('+', $qty_add) . ',"0") as qty_total, ' . implode('+', $roll_add) . ' as roll_total';
		$cond_filter .= 'OR (' . implode('+', $qty_add) . ') > 0';


		// echo $select;
		// echo $cond_barang;
		// echo "<hr/>";
		$select = $this->generate_select_versi_2();
		$data['penjualan_berjalan'] = $this->common_model->get_penjualan_bulan_berjalan($barang_id, $tanggal_start, $tanggal_end);
		$data['request_barang_data'] = $this->common_model->db_select('nd_request_barang where status = 1');
		$id_request = 0;
		$no_batch = 0;
		$no_request = 0;
		$tanggal_request = date('Y-m-d');
		foreach ($data['request_barang_data'] as $row) {
			$id_request = $row->id;
			$no_batch = $row->batch;
			$no_request = $row->no_request;
			$tanggal_request = $row->tanggal;
		}

		$id_request_before = 0;
		$data['request_barang_qty_all'] = array();
		if ($no_batch > 1) {
			$get_data_before = $this->common_model->db_select("nd_request_barang WHERE no_request = " . $no_request . " AND batch < " . $no_batch . " ORDER BY batch DESC LIMIT 1");

			foreach ($get_data_before as $row) {
				$id_request_before = $row->id;
			}

			$data['request_barang_qty_all'] = $this->common_model->get_request_barang_qty_aktif($id_request, $id_request_before);
		}


		$data['data_outstanding_po'] = $this->common_model->get_outstanding_barang($barang_id, $tanggal_request);
		$data['stok_barang'] = $this->inv_model->get_stok_barang_list_by_barang_temp_2($tanggal_awal, $select, $tanggal_request, "AND barang_id=$barang_id", $stok_opname_id);

		if ($tanggal_request != date('Y-m-d')) {
			$data['data_outstanding_update'] = $this->common_model->get_outstanding_barang($barang_id, date('Y-m-d'));
			$data['stok_barang_update'] = $this->inv_model->get_stok_barang_list_by_barang_temp_2($tanggal_awal, $select, date('Y-m-d'), "AND barang_id=$barang_id", $stok_opname_id);
		} else {
			$data['data_outstanding_update'] = $data['data_outstanding_po'];
			$data['stok_barang_update'] = $data['stok_barang'];
		}


		$data['request_barang_qty'] = $this->common_model->get_request_barang_qty($barang_id, $id_request, $id_request_before);
		$data['request_barang_detail'] = $this->common_model->get_active_request_barang($id_request, $id_request_before);



		$this->load->view('admin/template', $data);

		if (is_posisi_id() == 1) {
			// $this->output->enable_profiler(TRUE);
		}
		// else{
		// 	echo $tanggal_start_forecast, $tanggal_end_forecast;
		// 	print_r($data['data_penjualan']);
		// }
	}

	function barang_planner_3()
	{
		$menu = is_get_url($this->uri->segment(1));
		$barang_id = $this->input->get('id');
		// $tahun = date('Y') - 1;

		$tanggal_start = date('Y-m-01');
		$tanggal_end = date('Y-m-01', strtotime('+5 months'));
		// $tanggal_end = date('Y-12-31');

		$tanggal_start_forecast = date('Y-m-01', strtotime($tanggal_start . ' -1 year'));
		$tanggal_end_forecast = date('Y-m-t', strtotime($tanggal_end . ' -1 year'));

		if ($this->input->get('tanggal_start') != '' && $this->input->get('tanggal_end') != '') {
			$tanggal_start = date('Y-m-01', strtotime($this->input->get('tanggal_start')));
			$tanggal_end = date('Y-m-t', strtotime($this->input->get('tanggal_end')));

			$tanggal_start_forecast = date('Y-m-01', strtotime($tanggal_start . ' -1 year'));;
			$tanggal_end_forecast = date('Y-m-t', strtotime($tanggal_end . ' -1 year'));
		}

		$data = array(
			'content' => 'admin/master/barang_planner_3',
			'breadcrumb_title' => 'Master',
			'breadcrumb_small' => 'Planner + Request Barang',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'barang_id' => $barang_id,
			'common_data' => $this->data,
			'tanggal_start' => $tanggal_start,
			'tanggal_end' => $tanggal_end,
			'tanggal_start_forecast' => $tanggal_start_forecast,
			'tanggal_end_forecast' => $tanggal_end_forecast
		);

		$data['user_id'] = is_user_id();
		$data['data_barang'] = $this->common_model->db_select('nd_barang where id=' . $barang_id);
		$data['data_warna'] = $this->common_model->get_warna_asosiasi($barang_id);
		$data['data_penjualan'] = $this->common_model->get_penjualan_for_forecast($barang_id, $tanggal_start_forecast, $tanggal_end_forecast);
		// $data['data_pembelian_now'] = $this->common_model->get_data_pembelian_by_date($barang_id, $tanggal_start, $tanggal_end);
		// $data['penjualan_berjalan'] = $this->common_model->get_penjualan_bulan_berjalan($barang_id, $tanggal_start, $tanggal_end);

		$tanggal_awal = '2018-01-01';
		$stok_opname_id = 1;
		$getOpname = $this->common_model->db_select("nd_stok_opname where tanggal <= '" . date('Y-m-d') . "' order by tanggal desc limit 1");
		foreach ($getOpname as $row) {
			// $tanggal_awal = $row->tanggal;
			// $stok_opname_id = $row->id;
		}

		$data['gudang_list'] = $this->gudang_list_aktif;
		$data['stok_barang'] = array();

		$select = '';
		$select2 = "";
		$select_all = '';
		$columnSelect = array();
		$dt[0] = 'urutan';
		$dt[1] = 'nama_barang_jual';
		$dt[2] = 'status_aktif';
		$dt[3] = 'last_edit';
		$cond_filter = '';

		$idx = 4;
		$i = 0;
		$cond_qty = "";
		foreach ($this->gudang_list_aktif as $row) {
			$select .= ", ROUND(
				SUM( if(gudang_id=" . $row->id . ", 
						ifnull(
							if(tanggal_stok is not null, if(tanggal >= tanggal_stok,qty_masuk,0) , qty_masuk  ),
						0) ,
					0 )
				) - 
				SUM( if(gudang_id=" . $row->id . ", 
						ifnull( 
							if(tanggal_stok is not null, if(tanggal >= tanggal_stok, qty_keluar,0 ), qty_keluar ) 
						,0), 
					0 )
				),
				2)  
				as gudang_" . $row->id . "_qty ,

				SUM( if(gudang_id=" . $row->id . ", 
						if(tanggal_stok is not null, 
							if(tanggal >= tanggal_stok,jumlah_roll_masuk,0 ), 
						jumlah_roll_masuk), 
					0 ) 
				) -
				SUM( if(gudang_id=" . $row->id . ", 
						if(tanggal_stok is not null,
							if(tanggal >= tanggal_stok, jumlah_roll_keluar, 0)
						,jumlah_roll_keluar), 
					0 ) 
				)  
				as gudang_" . $row->id . "_roll,
				concat('" . $row->id . "','/',barang_id,'/',warna_id) as gudang_" . $row->id . "_button";

			$select_all .= ", SUM(ifnull(qty_masuk,0)) - SUM( ifnull(qty_keluar,0)) as qty, SUM(ifnull(jumlah_roll_masuk,0)) - SUM( ifnull(jumlah_roll_keluar,0)) as jumlah_roll ";

			if ($i == 0) {
				$cond_qty = "WHERE gudang_" . $row->id . "_qty > 0 ";
			} else {
				$cond_qty .= "OR gudang_" . $row->id . "_qty > 0 ";
			}

			$dt[$idx] = 'gudang_' . $row->id . '_qty';
			$idx++;
			$dt[$idx] = 'gudang_' . $row->id . '_roll';
			$idx++;
			$dt[$idx] = 'gudang_' . $row->id . '_button';
			$idx++;


			$qty_add[$i] = 'gudang_' . $row->id . '_qty';
			$roll_add[$i] = 'gudang_' . $row->id . '_roll';
			$i++;
		}

		$dt[$idx] = 'qty_total';
		$idx++;
		$dt[$idx] = 'roll_total';
		$idx++;

		$select2 .= ', if(tipe_qty != 3,' . implode('+', $qty_add) . ',"0") as qty_total, ' . implode('+', $roll_add) . ' as roll_total';
		$cond_filter .= 'OR (' . implode('+', $qty_add) . ') > 0';


		// echo $select;
		// echo $cond_barang;
		// echo "<hr/>";
		$select = $this->generate_select_versi_2();
		$data['penjualan_berjalan'] = $this->common_model->get_penjualan_bulan_berjalan($barang_id, $tanggal_start, $tanggal_end);
		$data['request_barang_data'] = $this->common_model->db_select('nd_request_barang where status = 1');
		$id_request = 0;
		$no_batch = 0;
		$no_request = 0;
		$tanggal_request = date('Y-m-d');
		foreach ($data['request_barang_data'] as $row) {
			$id_request = $row->id;
			$no_batch = $row->batch;
			$no_request = $row->no_request;
			$tanggal_request = $row->tanggal;
		}

		$id_request_before = 0;
		$data['request_barang_qty_all'] = array();
		if ($no_batch > 1) {
			$get_data_before = $this->common_model->db_select("nd_request_barang WHERE no_request = " . $no_request . " AND batch < " . $no_batch . " ORDER BY batch DESC LIMIT 1");

			foreach ($get_data_before as $row) {
				$id_request_before = $row->id;
			}

			$data['request_barang_qty_all'] = $this->common_model->get_request_barang_qty_aktif($id_request, $id_request_before);
		}


		$data['data_outstanding_po'] = $this->common_model->get_outstanding_barang($barang_id, $tanggal_request);
		$data['stok_barang'] = $this->inv_model->get_stok_barang_list_by_barang_temp_2($tanggal_awal, $select, $tanggal_request, "AND barang_id=$barang_id", $stok_opname_id);

		if ($tanggal_request != date('Y-m-d')) {
			$data['data_outstanding_update'] = $this->common_model->get_outstanding_barang($barang_id, date('Y-m-d'));
			$data['stok_barang_update'] = $this->inv_model->get_stok_barang_list_by_barang_temp_2($tanggal_awal, $select, date('Y-m-d'), "AND barang_id=$barang_id", $stok_opname_id);
		} else {
			$data['data_outstanding_update'] = $data['data_outstanding_po'];
			$data['stok_barang_update'] = $data['stok_barang'];
		}


		$data['request_barang_qty'] = $this->common_model->get_request_barang_qty($barang_id, $id_request, $id_request_before);
		$data['request_barang_detail'] = $this->common_model->get_active_request_barang($id_request, $id_request_before);



		$this->load->view('admin/template', $data);

		if (is_posisi_id() == 1) {
			// $this->output->enable_profiler(TRUE);
		}
		// else{
		// 	echo $tanggal_start_forecast, $tanggal_end_forecast;
		// 	print_r($data['data_penjualan']);
		// }
	}

	function new_request_insert()
	{
		$dt_update = array(
			'status' => 0
		);

		$request_barang_id = $this->input->post('request_barang_id');
		$this->common_model->db_update("nd_request_barang", $dt_update, "id", $request_barang_id);
		$type = $this->input->post('type');
		$no_batch = 1;
		$no_request = 1;
		$tanggal =  is_date_formatter($this->input->post('tanggal'));
		$year =  date('Y', strtotime($tanggal));
		if ($type == 2) {
			$get_data = $this->common_model->db_select("nd_request_barang where id = " . $request_barang_id);
			foreach ($get_data as $row) {
				$no_batch = $row->batch + 1;
				$no_request = $row->no_request;
			}

			$get_detail = $this->common_model->db_select("nd_request_barang_detail WHERE request_barang_id=" . $request_barang_id);

			$get_request = $this->common_model->db_select("nd_request_barang_qty WHERE request_barang_id=" . $request_barang_id);

			$data_detail = [];
			$data_request = [];
		} else {
			// $get_data = $this->common_model->db_select("nd_request_barang where id = ".$request_barang_id);
			$get_data = $this->common_model->db_select("nd_request_barang where YEAR(tanggal) ='" . $year . "' ORDER BY no_request DESC LIMIT 1");
			foreach ($get_data as $row) {
				$no_request = $row->no_request + 1;
			}
		}

		$data = array(
			'no_request' => $no_request,
			'batch' => $no_batch,
			'tanggal' => $tanggal
		);

		$barang_id = $this->input->post('barang_id');
		$request_barang_id_new = $this->common_model->db_insert("nd_request_barang", $data);

		if ($type == 2) {
			foreach ($get_detail as $row) {
				array_push($data_detail, array(
					'request_barang_id' => $request_barang_id_new,
					'po_pembelian_batch_id' => $row->po_pembelian_batch_id,
					'bulan_request' => $row->bulan_request,
					'barang_id' => $row->barang_id,
					'warna_id' => $row->warna_id,
					'qty' => $row->qty,
				));
			}

			foreach ($get_request as $row) {
				array_push($data_request, array(
					'request_barang_id' => $request_barang_id_new,
					'bulan_request' => $row->bulan_request,
					'barang_id' => $row->barang_id,
					'warna_id' => $row->warna_id,
					'qty' => $row->qty
				));
			}

			$this->common_model->db_insert_batch("nd_request_barang_qty", $data_request);
			$this->common_model->db_insert_batch("nd_request_barang_detail", $data_detail);
		}
		redirect(is_setting_link('master/barang_planner_2') . "?id=" . $barang_id);
	}

	function request_barang_update_tanggal()
	{
		$tanggal = is_date_formatter($this->input->post('tanggal'));
		$request_barang_id = $this->input->post('request_barang_id');
		$data = array(
			'tanggal' => $tanggal,
		);
		$this->common_model->db_update("nd_request_barang", $data, 'id', $request_barang_id);
		echo "OK";
	}

	function request_barang_submit()
	{
		// print_r($this->input->post());
		// $data = array();
		$id = '';
		$get_aktif = $this->common_model->db_select("nd_request_barang WHERe status = 1 ORDER BY tanggal desc LIMIT 1");
		foreach ($get_aktif as $row) {
			$id = $row->id;
		}

		foreach ($this->input->post('data') as $key => $value) {
			if ($id == '') {
				$tgl = ($value['tanggal'] == '' ? null : is_date_formatter($value['tanggal']));
				$data = array(
					'tanggal' => $tgl,
					'user_id' => is_user_id(),
				);

				$id = $this->common_model->db_insert('nd_request_barang', $data);
			}

			$data_detail = array();
			$barang_id = '';
			$id_detail_list = array();
			$get_detail = $this->common_model->db_select('nd_request_barang_detail WHERE request_barang_id = ' . $id);
			foreach ($value['data_barang'] as $key2 => $value2) {
				$bulan = $key2 . '-01';
				foreach ($value2 as $key3 => $value3) {
					if ($barang_id == '') {
						$barang_id = $value3['barang_id'];
					}
					if ($value3['qty'] > 0) {
						$dt = array(
							'request_barang_id' => $id,
							'po_pembelian_batch_id' => $value3['po_pembelian_batch_id'],
							'bulan_request' => $bulan,
							'barang_id' => $value3['barang_id'],
							'warna_id' => $value3['warna_id'],
							'qty' => $value3['qty']
						);
						$id_detail = '';
						foreach ($get_detail as $row) {
							// echo $row->po_pembelian_batch_id .'=='. $value3['po_pembelian_batch_id'] .'&&'. $row->barang_id .'=='. $value3['barang_id'] .'&&'. $row->warna_id .'=='. $value3['warna_id'] .'&&'. $row->bulan_request .'=='. $value3['bulan_request'].'<br/>';
							if ($row->po_pembelian_batch_id == $value3['po_pembelian_batch_id'] && $row->barang_id == $value3['barang_id'] && $row->warna_id == $value3['warna_id'] && $row->bulan_request == $value3['bulan_request'] . '-01') {
								$id_detail = $row->id;
								array_push($id_detail_list, $id_detail);
							}
						}
						if ($id_detail != '') {
							$this->common_model->db_update("nd_request_barang_detail", $dt, 'id', $id_detail);
						} else {
							array_push($data_detail, $dt);
						}
					}
				}
			}

			// print_r($data_detail);

			if (count($id_detail_list) > 0) {
				$this->common_model->remove_request_detail(implode(",", $id_detail_list), $id, $barang_id);
			}

			if (count($data_detail) > 0) {
				$this->common_model->db_insert_batch('nd_request_barang_detail', $data_detail);
			}

			$data_detail_request = array();
			$get_request = $this->common_model->db_select("nd_request_barang_qty WHERE request_barang_id=" . $id);
			$id_request_list = array();
			foreach ($value['data_request'] as $key2 => $value2) {
				$bulan = $value2['bulan_request'] . '-01';

				if ($value2['qty'] > 0) {
					$dt = array(
						'request_barang_id' => $id,
						'bulan_request' => $bulan,
						'barang_id' => $value2['barang_id'],
						'warna_id' => $value2['warna_id'],
						'qty' => $value2['qty']
					);

					$id_request = '';
					foreach ($get_request as $row) {
						if ($row->barang_id == $value2['barang_id'] && $row->warna_id == $value2['warna_id'] && $row->bulan_request == $bulan) {
							$id_request = $row->id;
							array_push($id_request_list, $id_request);
						}
					}

					if ($id_request != '') {
						$this->common_model->db_update('nd_request_barang_qty', $dt, 'id', $id_request);
					} else {
						array_push($data_detail_request, $dt);
					}
				}
			}

			if (count($id_request_list) > 0) {
				$this->common_model->remove_request_qty(implode(",", $id_request_list), $id, $barang_id);
			}

			if (count($data_detail_request) > 0) {
				$this->common_model->db_insert_batch("nd_request_barang_qty", $data_detail_request);
			}
		}

		echo "OK";
	}

	function request_barang_lock()
	{
		$request_barang_id = $this->input->post('request_barang_id');
		$tipe = $this->input->post('tipe');
		if ($tipe == 1) {
			$data = array(
				'locked_by' => is_user_id(),
				'locked_date' => date("Y-m-d H:i:s")
			);
		} else if ($tipe == 2) {
			$data = array(
				'locked_by' => null,
				'locked_date' => null
			);
		}

		$this->common_model->db_update("nd_request_barang", $data, 'id', $request_barang_id);
		echo "OK";
	}


	/**
//================================supplier list================================================
	 **/

	function supplier_list()
	{
		$menu = is_get_url($this->uri->segment(1));

		$data = array(
			'content' => 'admin/master/supplier_list',
			'breadcrumb_title' => 'Master',
			'breadcrumb_small' => 'Daftar Supplier',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data' => $this->data
		);

		$data['user_id'] = is_user_id();
		$data['supplier_list'] = $this->common_model->db_select('nd_supplier');
		$this->load->view('admin/template', $data);
	}

	private function supplier_validate($tipe = 'new', $id = 0)
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		//validasi nama 
		$nama = $this->input->post('nama');
		// $id = $this->input->post('id');

		if ($nama == '') {
			$data['inputerror'][] = 'nama';
			$data['error_string'][] = 'Nama harus diisi!';
			$data['status'] = FALSE;
		} else {
			//cek apakah double 
			if ($tipe == 'new') {
				$hasil_nama = $this->common_model->db_select_num_rows("nd_supplier where nama = '" . $nama . "'");
			} else {
				$hasil_nama = $this->common_model->db_select_num_rows("nd_supplier where nama = '" . $nama . "' AND id <> '" . $id . "'");
			}

			if ($hasil_nama > 0) {
				$data['inputerror'][] = 'nama';
				$data['error_string'][] = 'Nama sudah terdaftar!'.$nama.' '.$id;
				$data['status'] = FALSE;
			}
		}

		//validasi kode 
		$kode = $this->input->post('kode');

		if ($kode == '') {
			$data['inputerror'][] = 'kode';
			$data['error_string'][] = 'Kode harus diisi!';
			$data['status'] = FALSE;
		} else {
			//cek apakah double 
			if ($tipe == 'new') {
				$hasil_kode = $this->common_model->db_select_num_rows("nd_supplier where kode = '" . $kode . "'");
			} else {
				$hasil_kode = $this->common_model->db_select_num_rows("nd_supplier where kode = '" . $kode . "' AND id <> '" . $id . "'");
			}

			if ($hasil_kode > 0) {
				$data['inputerror'][] = 'kode';
				$data['error_string'][] = 'Kode sudah terdaftar!';
				$data['status'] = FALSE;
			}
		}

		if ($data['status'] == FALSE) {
			echo json_encode($data);
			exit();
		}
	}

	public function supplier_validate_start()
	{
		$tipe = $this->uri->segment(3);
		$id = $this->uri->segment(4);

		$this->supplier_validate($tipe, $id);

		echo json_encode(array("status" => TRUE));
	}

	function supplier_list_insert()
	{
		$data = array(
			'tipe_supplier' => $this->input->post('tipe_supplier'),
			'nama' => $this->input->post('nama'),
			'kode' => $this->input->post('kode'),
			'alamat' => htmlentities($this->input->post('alamat')),
			'telepon' => $this->input->post('telepon'),
			'kota' => $this->input->post('kota'),
			'fax' => $this->input->post('fax'),
			'kode_pos' => $this->input->post('kode_pos'),
			'nama_bank' => trim($this->input->post('nama_bank')),
			'no_rek_bank' => trim($this->input->post('no_rek_bank')),
			'email' => $this->input->post('email'),
			'website' => $this->input->post('website')
		);

		$this->common_model->db_insert('nd_supplier', $data);

		redirect(is_setting_link('master/supplier_list'));
	}

	function supplier_list_update()
	{

		$id = $this->input->post('supplier_id');

		$data = array(
			'tipe_supplier' => $this->input->post('tipe_supplier'),
			'nama' => $this->input->post('nama'),
			'kode' => $this->input->post('kode'),
			'alamat' => htmlentities($this->input->post('alamat')),
			'telepon' => $this->input->post('telepon'),
			'kota' => $this->input->post('kota'),
			'fax' => $this->input->post('fax'),
			'kode_pos' => $this->input->post('kode_pos'),
			'nama_bank' => trim($this->input->post('nama_bank')),
			'no_rek_bank' => trim($this->input->post('no_rek_bank')),
			'email' => $this->input->post('email'),
			'website' => $this->input->post('website')
		);

		$this->common_model->db_update('nd_supplier', $data, 'id', $id);

		redirect(is_setting_link('master/supplier_list'));
	}

	//================================customer list================================================

	function customer_list_classification()
	{
		$data = $this->common_model->db_free_query_superadmin("SELECT t1.id, t1.customer_id, t1.penjualan_type_id from nd_penjualan t1 JOIN ( SELECT max(id) as id from nd_penjualan WHERE penjualan_type_id != 3 AND customer_id != 0 GROUP BY customer_id) t2 ON t1.id = t2.id WHERE t2.id is not null  ");
		foreach ($data->result() as $row) {
			$dt = array(
				'customer_type_id' => $row->penjualan_type_id
			);

			$this->common_model->db_update("nd_customer", $dt, 'id', $row->customer_id);
		}
	}

	function customer_npwp2()
	{
		$get_data = $this->common_model->db_free_query_superadmin("SELECT *
				FROM nd_customer
				ORDER BY id asc
				LIMIT 30,30
			");

		echo "<table>";
		echo "<tr>";
		echo "<td>id</td>";
		echo "<td>nama(temp)</td>";
		echo "<td>alamat</td>";
		echo "<td>npwp</td>";
		echo "<td>nik</td>";

		echo "</tr>";
		$idx = 1;
		foreach ($get_data->result() as $row) {

			// if ($row->nama == $row->nama_customer) {
			echo "<tr>";
			echo "<td>" . $row->id . "</td>";
			echo "<td style='border-bottom: 1px solid #ddd'>" . $row->nama . "</td>";
			echo "<td style='border-bottom: 1px solid #ddd'>" . $row->alamat . ' ' . $row->kota . " " . $row->provinsi . '</td>';
			echo "<td style='border-bottom: 1px solid #ddd'>" . $row->npwp . '</td>';
			echo "<td>" . $row->nik . '</td>';
			echo "</tr>";
			$idx++;
			// }
		}
		echo "</table>";
	}

	function customer_npwp()
	{
		$get_data = $this->common_model->db_free_query_superadmin("SELECT t1.* , t2.id as customer_id_origin, t2.nama as nama_customer, t2.alamat as alamat_customer, t2.status_aktif
			FROM (
				SELECT *
				FROM customer_temp
				) t1
			LEFT JOIN (
				SELECT *
				FROM nd_customer
				) t2
			ON t1.customer_id = t2.id
			WHERE t2.id is not null
			-- AND t2.status_aktif = 1
			-- ORDER BY t1.id
			");

		foreach ($get_data->result() as $row) {
			// $data_up = array('customer_id' => $row->customer_id_origin );
			// $this->common_model->db_free_query_superadmin("UPDATE customer_temp SET customer_id = ".$row->customer_id_origin." WHERE id=".$row->id);
			// $data_up = array(
			// 'alamat' => $row->alamat ,
			// 'npwp' => $row->npwp,
			// 'kota' => $row->kota,
			// 'provinsi' => $row->provinsi,
			// 'kode_pos' => $row->kode_pos );
			// echo "<hr/>";
			// echo $row->nama." ".$row->customer_id.'<br/>';
			// print_r($data_up);
			// echo "<br/>";
			// $this->common_model->db_update('nd_customer', $data_up,'id', $row->customer_id);
		}

		echo "<table>";
		echo "<tr>";
		echo "<td>No</td>";
		echo "<td>id</td>";
		echo "<td>customer(id)</td>";
		echo "<td>customer_id</td>";
		echo "<td>nama(temp)</td>";
		echo "<td>nama2</td>";
		echo "<td>npwp</td>";
		echo "<td>nik</td>";
		echo "<td>alamat C(temp)</td>";

		echo "</tr>";
		$idx = 1;
		foreach ($get_data->result() as $row) {

			// if ($row->nama == $row->nama_customer) {
			echo "<tr>";
			echo "<td>" . $idx . "</td>";
			echo "<td>" . $row->id . "</td>";
			echo "<td>" . $row->customer_id . "</td>";
			echo "<td>" . $row->customer_id_origin . "</td>";
			echo "<td>" . $row->nama . "</td>";
			echo "<td>" . $row->nama_customer . "</td>";
			echo "<td>" . $row->npwp . '</td>';
			echo "<td>" . $row->nik . '</td>';
			echo "<td>" . $row->alamat . '</td>';
			echo "<td>" . $row->alamat_customer . '</td>';
			echo "</tr>";
			$idx++;
			// }
		}
		echo "</table>";

		// foreach ($get_data->result() as $row) {
		// 	$data_update = array(
		// 		'customer_id' => $row->customer_id );

		// 	$this->common_model->db_update("customer_temp", $data_update, 'id', $row->id);
		// }


		/*$idx=0; $nik = array();
		foreach ($get_data->result() as $row) {
			if(strpos($row->npwp, '.') === false){
				$nik[$row->id] = trim(str_replace('NIK', '', $row->npwp)); 
			}}

			$case_fp = "CASE
			";

			foreach ($nik as $key => $value) {
				$case_fp .="WHEN id=".$key." THEN null
				";
				# code...
			}*/

		// $this->common_model->db_free_query_superadmin("UPDATE nd_customer
		// 	SET npwp = 
		// 		$case_fp
		// 		ELSE npwp
		// 		END");



		/*$get_data = $this->common_model->db_free_query_superadmin("SELECT t1.id, t1.nama, t2.npwp, t2.nik, t1.alamat as alamat1, t2.alamat as alamat2
			FROM nd_customer t1
			LEFT JOIN customer_copy t2
			ON t1.id = t2.id
			WHERE t2.nik != ''
			OR t2.npwp != ''
			");

		// $get_data = $this->common_model->db_select("customer_copy where nik is not null order by id asc");
		$idx = 1;
		echo "<table>";
		foreach ($get_data->result() as $row) {
			$alamat_filter_1 = str_replace('RT:000 RW:000 ', '', $row->alamat2);
			$alamat_filter_2 = str_replace('Kel.-', '', $alamat_filter_1);
			$alamat_filter_3 = str_replace('Kel.', ',', $alamat_filter_2);
			$alamat_final = $alamat_filter_3; $bg = ''; $split1 = ''; $split2='';
			echo "<tr>";
				echo "<td>".$idx."</td>";
				echo "<td>".$row->id."</td>";
				echo "<td>".$row->nama."</td>";
				echo "<td>".$row->npwp.'</td>';
				echo "<td>".$row->nik.'</td>';
				echo "<td>".$row->alamat1.'</td>';
				if (strlen($alamat_final) > 85) {
					$bg='yellow';
					$split1 = substr($alamat_final, 0,47);
					$split2 = substr($alamat_final, 47);
				}
				echo "<td>".$alamat_final." </td>";
				echo "<td style='background:$bg'>".strlen($alamat_final).'</td>';
			echo "</tr>";
			$idx++;
		}

		echo "</table>";*/
	}

	function customer_list()
	{
		$isMobileAccess = false;
		$useragent=$_SERVER['HTTP_USER_AGENT'];
		if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4))){
			// echo 'mobile access';
			// echo "";
			$isMobileAccess = true;
		}	
		$menu = is_get_url($this->uri->segment(1));
		$this->load->helper('directory');
		$dir = 'image/customer';
		$customer_id = $this->session->flashdata('customer_id');
	
		$data = array(
			'content' => 'admin/master/customer_list',
			'breadcrumb_title' => 'Master',
			'breadcrumb_small' => 'Daftar Customer',
			'customer_id_last' => $customer_id,
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data' => $this->data,
			'dir' => $dir,
			'isMobileAccess' => $isMobileAccess,
			'map' => directory_map($dir)
		);
	
		$data['user_id'] = is_user_id();
		$data['customer_list'] = $this->common_model->db_select('nd_customer');
		$this->load->view('admin/template', $data);

	}

	private function customer_validate($tipe = 'new', $id = 0)
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		//validasi nama 
		$nama = $this->input->post('nama');

		if ($nama == '') {
			$data['inputerror'][] = 'nama';
			$data['error_string'][] = 'Nama harus diisi!';
			$data['status'] = FALSE;
		} else {
			//cek apakah double 
			if ($tipe == 'new') {
				$hasil_nama = $this->common_model->db_select_num_rows("nd_customer where nama = '" . $nama . "'");
			} else {
				$hasil_nama = $this->common_model->db_select_num_rows("nd_customer where nama = '" . $nama . "' AND id <> '" . $id . "'");
			}

			if ($hasil_nama > 0) {
				$data['inputerror'][] = 'nama';
				$data['error_string'][] = 'Nama sudah terdaftar!';
				$data['status'] = FALSE;
			}
		}

		if ($data['status'] == FALSE) {
			echo json_encode($data);
			exit();
		}
	}

	public function customer_validate_start()
	{
		$tipe = $this->uri->segment(3);
		$id = $this->uri->segment(4);

		$this->customer_validate($tipe, $id);

		echo json_encode(array("status" => TRUE));
	}

	function cari_nama_mirip_customer()
	{
		$nama = $this->input->post('nama');
		$set = $this->common_model->db_select("nd_customer where nama like '%" . $nama . "%'");
		echo json_encode($set);
	}

	function data_customer()
	{

		// $session_data = $this->session->userdata('do_filter');
		$npwpSort = 'Identity';

		if (isset($_GET['iSortCol_0']) && $_GET['iSortCol_0'] == 5) {
			$npwpSort = 0;
			session_start();
			if (isset($_SESSION['NPWP_SORT'])) {
				$npwpSort = $_SESSION['NPWP_SORT'];
				$npwpSort++;
				$npwpSort %= 6;
			}
			$_SESSION['NPWP_SORT'] = $npwpSort;
		}else{
			session_start();
			$_SESSION['NPWP_SORT'] = -1;
			$npwpSort = 'Identity';
		}

		$identitySort = ["concat(ifnull(npwp,''),ifnull(nik,'')) asc",
		"concat(ifnull(npwp,''),ifnull(nik,'')) desc",
		"npwp asc, nik asc",
		"npwp desc, nik desc",
		"nik asc, npwp asc",
		"nik desc, npwp desc"];

		$aColumns = array('status_aktif', 'nama', 'alias', 'customer_type', 'alamat', 'kota', 'telepon1', 'npwp', 'tempo_kredit', 'limit_data', 'other_data');

		$sIndexColumn = "id";

		// paging
		$sLimit = "";
		if (isset($_GET['iDisplayStart']) && $_GET['iDisplayLength'] != '-1') {
			$sLimit = "LIMIT " . mysql_real_escape_string($_GET['iDisplayStart']) . ", " .
				mysql_real_escape_string($_GET['iDisplayLength']);
		}
		$numbering = mysql_real_escape_string($_GET['iDisplayStart']);
		$page = 1;

		// ordering
		if (isset($_GET['iSortCol_0'])) {
			// echo $_GET['iSortCol_0'];
			$sOrder = "ORDER BY  ";
			if ($_GET['iSortCol_0'] != 5) {
				for ($i = 0; $i < intval($_GET['iSortingCols']); $i++) {
					if ($_GET['bSortable_' . intval($_GET['iSortCol_' . $i])] == "true") {
						if ($_GET['iSortCol_0'] != 5) {
							$sOrder .= $aColumns[intval($_GET['iSortCol_' . $i])] . "
								" . mysql_real_escape_string($_GET['sSortDir_' . $i]) . ", ";
						}
					}
				}
			}else{
				$sOrder = "ORDER BY  ";
				$sOrder .= $identitySort[$npwpSort]." , ";
				
			}

			// echo $_GET['iSortCol_0'].' '.$sOrder.' '.$_GET['iSortingCols'];

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

		$customer_type_id = $this->input->get('customer_type_id');
		if ($customer_type_id != 0) {
			if ($sWhere == "") {
				$sWhere = "WHERE customer_type_id = " . $customer_type_id;
			} else {
				$sWhere .= " AND customer_type_id=" . $customer_type_id;
			}
		}

		$rResult = $this->common_model->get_customer_list_ajax($aColumns, $sWhere, $sOrder, $sLimit);

		$rResultTotal = $this->common_model->db_select_num_rows('nd_customer');
		$Filternya = $this->common_model->get_customer_list_ajax($aColumns, $sWhere, $sOrder, '');
		$iFilteredTotal = $Filternya->num_rows();
		// $iTotal = $rResultTotal;
		// $iFilteredTotal = $iTotal;

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

		$output['test'] = $_GET['iSortCol_0'];
		$output['npwpSort'] = $npwpSort;

		echo json_encode($output);
	}

	function get_history_harga_customer()
	{

		foreach ($this->toko_list_aktif as $row) {
			$nama_toko = $row->nama_toko;
			$nama_toko = str_replace(".", "_", $nama_toko);
		}

		$history_list = $this->common_model->get_history_harga_customer();

		$this->load->library('Excel/PHPExcel');

		ini_set("memory_limit", "600M");

		/** Caching to discISAM*/
		$cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_to_discISAM;
		$cacheSettings = array('');;

		PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);

		$objPHPExcel = new PHPExcel();

		$styleArray = array(
			'font' => array(
				'bold' => true,
				'size' => 12,
			)
		);


		$row_no = 4;
		$idx = 0;

		$coll = "A";
		foreach ($history_list as $row) {
			if ($idx == 0) {
				$objPHPExcel->getActiveSheet()->setCellValue($coll . $row_no, "NO");
				$coll++;
				foreach ($row as $key => $value) {
					$objPHPExcel->getActiveSheet()->setCellValue($coll . $row_no, $key);
					$coll++;
				}
			}
			$idx++;
			break;
		}
		$row_no++;

		foreach ($history_list as $row) {
			$coll = "A";

			$objPHPExcel->getActiveSheet()->setCellValue($coll . $row_no, $idx);
			$objPHPExcel->getActiveSheet()->getStyle($coll . $row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(7);
			$coll++;

			foreach ($row as $key => $value) {
				$objPHPExcel->getActiveSheet()->setCellValue($coll . $row_no, $row->value);
				$objPHPExcel->getActiveSheet()->getStyle($coll . $row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(20);
				$coll++;
			}

			$idx++;
			$row_no++;
		}

		// $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		//ob_end_clean();
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		ob_end_clean();


		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header("Content-Disposition: attachment;filename=History_customer" . $nama_toko . ".xls");
		header('Cache-Control: max-age=0');
		$objWriter->save('php://output');
	}

	function stok_barang_excel()
	{

		foreach ($this->toko_list_aktif as $row) {
			$nama_toko = $row->nama_toko;
			$nama_toko = str_replace(".", "_", $nama_toko);
		}

		$this->load->library('Excel/PHPExcel');

		ini_set("memory_limit", "600M");

		/** Caching to discISAM*/
		$cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_to_discISAM;
		$cacheSettings = array('');;

		PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);

		$objPHPExcel = new PHPExcel();

		$styleArray = array(
			'font' => array(
				'bold' => true,
				'size' => 12,
			)
		);


		$row_no = 4;
		$idx = 0;

		foreach ($this->customer_list_aktif as $row) {
			if ($idx == 0) {
				foreach ($row as $key => $value) {
					$objPHPExcel->getActiveSheet()->setCellValue($coll . $row_no, $key);
					$coll++;
				}
			}
			$idx++;
			break;
		}
		$row_no++;

		foreach ($this->customer_list_aktif as $row) {
			$coll = "A";

			$objPHPExcel->getActiveSheet()->setCellValue($coll . $row_no, $idx);
			$objPHPExcel->getActiveSheet()->getStyle($coll . $row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(7);
			$coll++;

			$objPHPExcel->getActiveSheet()->setCellValue($coll . $row_no, $row->nama_barang . ' ' . $row->nama_warna);
			$objPHPExcel->getActiveSheet()->getStyle($coll . $row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(30);
			$coll++;

			$objPHPExcel->getActiveSheet()->setCellValue($coll . $row_no, $row->nama_barang_jual . ' ' . $row->nama_warna_jual);
			$objPHPExcel->getActiveSheet()->getStyle($coll . $row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(30);
			$coll++;
		}

		// $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		//ob_end_clean();
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		ob_end_clean();


		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header("Content-Disposition: attachment;filename=Customer_" . $nama_toko . ".xls");
		header('Cache-Control: max-age=0');
		$objWriter->save('php://output');
	}

	function customer_cek()
	{
		$data = array(
			'nama' => $this->input->post('nama'),
			'alamat' => trim($this->input->post('alamat')),
			'blok' => $this->input->post('blok'),
			'no' => $this->input->post('no'),
			'rt' => $this->input->post('rt'),
			'rw' => $this->input->post('rw'),
			'kecamatan' => $this->input->post('kecamatan'),
			'kelurahan' => $this->input->post('kelurahan'),
			'kota' => $this->input->post('kota'),
			'provinsi' => $this->input->post('provinsi'),
			'kode_pos' => $this->input->post('kode_pos')
		);

		// print_r($data);

		$get = $this->common_model->db_select_array_2('nd_customer', $data);
		echo json_encode($get);
	}

	function customer_list_insert()
	{
		//insert data 
		$customer_type_id = $this->input->post('customer_type_id');
		$tempo_kredit = null;
		$limit_type = null;
		$limit_amount = null;
		$limit_warning_amount = null;
		$limit_atas = null;
		if ($customer_type_id == 2) {
			$tempo_kredit = ($this->input->post('tempo_kredit') == '' || (int)$this->input->post('tempo_kredit') == 0 ? null : $this->input->post('tempo_kredit'));
			$limit_amount = ($this->input->post('limit_amount') == '' || (int)$this->input->post('limit_amount') == 0 ? null : str_replace('.', '', $this->input->post('limit_amount')));
			$limit_type = ($this->input->post('limit_warning_type') == '' || (int)$this->input->post('limit_warning_type') == 0 ? null : $this->input->post('limit_warning_type'));
			$limit_warning_amount = ($this->input->post('limit_warning_amount') == '' || (int)$this->input->post('limit_warning_amount') == 0 ? null : $this->input->post('limit_warning_amount'));
			if ($limit_type == 2) {
				$limit_warning_amount = str_replace('.', '', $limit_warning_amount);
			}
		}

		if ($limit_amount != null) {
			$limit_atas = str_replace('.', '', $this->input->post('limit_atas'));
			$limit_atas = ($limit_atas == '' || $limit_atas < $limit_amount ? $limit_amount : $limit_atas);
		}

		$npwp = ($this->input->post('npwp') == 0 ? '' : $this->input->post('npwp'));

		$data = array(
			'customer_type_id' => $this->input->post('customer_type_id'),
			'tipe_company' => $this->input->post('tipe_company'),
			'nama' => $this->input->post('nama'),
			'alias' => $this->input->post('alias'),
			'alamat' => trim($this->input->post('alamat')),
			'blok' => $this->input->post('blok'),
			'no' => $this->input->post('no'),
			'rt' => $this->input->post('rt'),
			'rw' => $this->input->post('rw'),
			'kecamatan' => $this->input->post('kecamatan'),
			'kelurahan' => $this->input->post('kelurahan'),
			'provinsi' => $this->input->post('provinsi'),
			'npwp' => $npwp,
			'nik' => str_replace(' ', '', $this->input->post('nik')),
			'contact_person' => $this->input->post('contact_person'),
			'telepon1' => $this->input->post('telepon1'),
			'telepon2' => $this->input->post('telepon2'),
			'kota' => $this->input->post('kota'),
			'kode_pos' => $this->input->post('kode_pos'),
			'email' => $this->input->post('email'),
			'tempo_kredit' => ($this->input->post('tempo_kredit') != '' ? $this->input->post('tempo_kredit') : null),
			'warning_kredit' => ($this->input->post('warning_kredit') != '' ? $this->input->post('warning_kredit') : 0),
			'limit_amount' => $limit_amount,
			'limit_atas' => $limit_atas,
			'limit_warning_type' => $limit_type,
			'limit_warning_amount' => $limit_warning_amount,
			'user_id' => is_user_id(),
			'medsos_link' => $this->input->post('medsos_link')
		);

		$result_id = $this->common_model->db_insert('nd_customer', $data);

		//create folder (if no exists)
		$dir = 'image/customer/customer_' . $result_id;

		if (!is_dir($dir)) {
			mkdir($dir);
		}

		$tgl_img = date('YmdHis');

		// insert image npwp
		if ($this->input->post('pict_data') != '') {
			$str_remove = ['.', ',', '?', '!', ':', ';'];
			$pict_name = str_replace($str_remove, '', trim($this->input->post('nama')));
			$pict_name = 'npwp_' . $result_id . '_' . $tgl_img . '.jpeg';
			// echo $pict_name;

			$data_img = array(
				'npwp_link' => $pict_name
			);

			$this->common_model->db_update('nd_customer', $data_img, 'id', $result_id);

			$data_get = explode('base64,', $this->input->post('pict_data'));
			$img_decoded = base64_decode($data_get[1]);
			// print_r(getimagesize($this->input->post('pict_data'))) ."<br/>";
			$photo = imagecreatefromstring($img_decoded);
			// echo getimagesize($photo);
			imagejpeg($photo, $dir . '/' . $pict_name, 100);
		}

		// insert image ktp
		if ($this->input->post('pict_data_ktp') != '') {
			$str_remove = ['.', ',', '?', '!', ':', ';'];
			$pict_name = str_replace($str_remove, '', trim($this->input->post('nama')));
			$pict_name = 'ktp_' . $result_id . '_' . $tgl_img . '.jpeg';
			// echo $pict_name;

			$data_img = array(
				'ktp_link' => $pict_name
			);

			$this->common_model->db_update('nd_customer', $data_img, 'id', $result_id);

			$data_get = explode('base64,', $this->input->post('pict_data_ktp'));
			$img_decoded = base64_decode($data_get[1]);
			// print_r(getimagesize($this->input->post('pict_data'))) ."<br/>";
			$photo = imagecreatefromstring($img_decoded);
			// echo getimagesize($photo);
			imagejpeg($photo, $dir . '/' . $pict_name, 100);
		}

		redirect(is_setting_link('master/customer_list'));
	}

	function test_image_to_jpg()
	{

		$dataImg = "";
		$data_get = explode('base64,', $dataImg);
		$pict_name2 = 'test2.jpeg';
		$img_decoded = base64_decode($data_get[1]);
		$photo = imagecreatefromstring($img_decoded);
		imagejpeg($photo, 'image/customer/' . $pict_name2, 100);

		// file_put_contents('image/customer/'.$pict_name,$img_decoded);
		// file_put_contents('image/customer/'.$pict_name2,$imgSave);	
	}

	function customer_list_update()
	{
		//update data 
		$id = $this->input->post('customer_id');
		$customer_type_id = $this->input->post('customer_type_id');
		$npwp_link = $this->input->post('npwp_link');
		$ktp_link = $this->input->post('ktp_link');
		$tempo_kredit = null;
		$limit_type = null;
		$limit_amount = null;
		$limit_warning_amount = null;
		$limit_atas = null;
		$medsos_link = $this->input->post('medsos_link');

		if ($customer_type_id == 2) {
			$tempo_kredit = ($this->input->post('tempo_kredit') == '' || (int)$this->input->post('tempo_kredit') == 0 ? null : $this->input->post('tempo_kredit'));
			$limit_amount = ($this->input->post('limit_amount') == '' || (int)$this->input->post('limit_amount') == 0 ? null : str_replace('.', '', $this->input->post('limit_amount')));
			$limit_type = ($this->input->post('limit_warning_type') == '' || (int)$this->input->post('limit_warning_type') == 0 ? null : $this->input->post('limit_warning_type'));
			$limit_warning_amount = ($this->input->post('limit_warning_amount') == '' || (int)$this->input->post('limit_warning_amount') == 0 ? null : $this->input->post('limit_warning_amount'));
			if ($limit_type == 2) {
				$limit_warning_amount = str_replace('.', '', $limit_warning_amount);
			}
		}

		if ($limit_amount != null) {
			$limit_atas = str_replace('.', '', $this->input->post('limit_atas'));
			$limit_atas = ($limit_atas == '' || $limit_atas < $limit_amount ? $limit_amount : $limit_atas);
		}

		$npwp = ($this->input->post('npwp') == 0 ? '' : $this->input->post('npwp'));

		$data = array(
			'customer_type_id' => $this->input->post('customer_type_id'),
			'tipe_company' => $this->input->post('tipe_company'),
			'nama' => $this->input->post('nama'),
			'alias' => $this->input->post('alias'),
			'alamat' => trim($this->input->post('alamat')),
			'blok' => $this->input->post('blok'),
			'no' => $this->input->post('no'),
			'rt' => $this->input->post('rt'),
			'rw' => $this->input->post('rw'),
			'kecamatan' => $this->input->post('kecamatan'),
			'kelurahan' => $this->input->post('kelurahan'),
			'kota' => $this->input->post('kota'),
			'provinsi' => $this->input->post('provinsi'),
			'npwp' => $npwp,
			'nik' => str_replace(' ', '', $this->input->post('nik')),
			'telepon1' => $this->input->post('telepon1'),
			'contact_person' => $this->input->post('contact_person'),
			'telepon2' => $this->input->post('telepon2'),
			'kode_pos' => $this->input->post('kode_pos'),
			'email' => $this->input->post('email'),
			'tempo_kredit' => ($this->input->post('tempo_kredit') != '' ? $this->input->post('tempo_kredit') : null),
			'warning_kredit' => ($this->input->post('warning_kredit') != '' ? $this->input->post('warning_kredit') : 0),
			'limit_amount' => $limit_amount,
			'limit_atas' => $limit_atas,
			'limit_warning_type' => $limit_type,
			'limit_warning_amount' => $limit_warning_amount,
			'user_id' => is_user_id(),
			'npwp_link' => $npwp_link,
			'ktp_link' => $ktp_link,
			'medsos_link' => $medsos_link
		);

		$this->common_model->db_update('nd_customer', $data, 'id', $id);

		//create folder (if no exists)
		$dir = 'image/customer/customer_' . $id;

		if (!is_dir($dir)) {
			mkdir($dir);
		}

		$tgl_img = date('YmdHis');

		//upload file npwp
		if ($this->input->post('pict_data_edit') != '') {
			unlink($dir . '/' . $this->input->post('npwp_link'));

			$str_remove = ['.', ',', '?', '!', ':', ';'];
			$pict_name = str_replace($str_remove, '', trim($this->input->post('nama')));
			$pict_name = 'npwp_' . $id . '_' . $tgl_img . '.jpeg';
			// echo $pict_name;

			$data_img = array(
				'npwp_link' => $pict_name
			);

			$this->common_model->db_update('nd_customer', $data_img, 'id', $id);

			$data_get = explode('base64,', $this->input->post('pict_data_edit'));
			$img_decoded = base64_decode($data_get[1]);
			// print_r(getimagesize($this->input->post('pict_data'))) ."<br/>";
			$photo = imagecreatefromstring($img_decoded);
			// echo getimagesize($photo);
			imagejpeg($photo, $dir . '/' . $pict_name, 100);
		}

		//upload file ktp 
		if ($this->input->post('pict_data_edit_ktp') != '') {
			unlink($dir . '/' . $this->input->post('ktp_link'));

			$str_remove = ['.', ',', '?', '!', ':', ';'];
			$pict_name = str_replace($str_remove, '', trim($this->input->post('nama')));
			$pict_name = 'ktp_' . $id . '_' . $tgl_img . '.jpeg';
			// echo $pict_name;

			$data_img = array(
				'ktp_link' => $pict_name
			);

			$this->common_model->db_update('nd_customer', $data_img, 'id', $id);

			$data_get = explode('base64,', $this->input->post('pict_data_edit_ktp'));
			$img_decoded = base64_decode($data_get[1]);
			// print_r(getimagesize($this->input->post('pict_data'))) ."<br/>";
			$photo = imagecreatefromstring($img_decoded);
			// echo getimagesize($photo);
			imagejpeg($photo, $dir . '/' . $pict_name, 100);
		}

		redirect(is_setting_link('master/customer_list'));
	}

	function customer_profile()
	{
		$menu = is_get_url($this->uri->segment(1));
		$customer_id = $this->uri->segment(2);
		$year = date('Y');
		if ($this->input->get('tahun')) {
			$year = date('Y');
		}

		$tanggal_start = "2020-01-01";
		$tanggal_end = date("Y-m-t");
		$cond_tanggal = '';
		if ($this->input->get("tanggal_start") != '' && $this->input->get("tanggal_end") != '' ) {
			$tanggal_start = is_date_formatter($this->input->get('tanggal_start'));
			$tanggal_end = is_date_formatter($this->input->get('tanggal_end'));
			$cond_tanggal = "AND tanggal >= '$tanggal_start' AND tanggal <= '$tanggal_end'";
		}

		$data = array(
			'content' => 'admin/master/customer_profile',
			'breadcrumb_title' => 'Master',
			'breadcrumb_small' => 'Daftar Customer',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'customer_id' => $customer_id,
			'common_data' => $this->data,
			'tanggal_start' => is_reverse_date($tanggal_start),
			'tanggal_end' => is_reverse_date($tanggal_end)
		);

		$data['data_customer'] = $this->common_model->db_select('nd_customer where id=' . $customer_id);
		// $data['customer_profile_pembelian_barang'] = $this->common_model->get_customer_profile_pembelian_barang_terbanyak($customer_id, $year); 
		// $data['customer_profile_pembelanjaan'] = $this->common_model->get_customer_profile_pembelanjaan_tahun($customer_id, $year.'-01-01', $year.'-12-31'); 
		$data['customer_profile_hutang'] = $this->common_model->get_customer_profile_piutang($customer_id);
		$data['customer_dp'] = $this->common_model->get_dp_by_customer($customer_id);
		// $data['data_penjualan'] = $this->common_model->get_data_penjualan($customer_id);

		$limit = '';
		// $limit = "LIMIT ".(is_posisi_id() <= 5 ? '30' : '1');
		$data['data_penjualan'] = $this->common_model->get_penjualan_report('', $cond_tanggal, $customer_id, $limit);

		// echo $cond_tanggal;
		$this->load->view('admin/template_no_sidebar', $data);
	}

	function customer_get_alamat_lain()
	{
		$customer_id = $this->input->post('id');
		$get =  $this->common_model->db_select("nd_customer_alamat_kirim WHERE customer_id=" . $customer_id);
		echo json_encode($get);
	}

	function customer_update_alamat_lain()
	{
		$id = $this->input->post("alamat_kirim_id");
		$customer_id = $this->input->post("customer_id");
		$data = array(
			'customer_id' => $customer_id,
			'alamat' => $this->input->post("alamat"),
			'catatan' => $this->input->post("catatan"),
			'user_id' => is_user_id()
		);
		if ($id == '') {
			$this->common_model->db_insert("nd_customer_alamat_kirim", $data);
		} else {
			$this->common_model->db_update("nd_customer_alamat_kirim", $data, "id", $id);
		}
		$this->session->set_flashdata('customer_id', $customer_id);
		redirect(is_setting_link('master/customer_list'));
	}


	function get_penjualan_tahun()
	{
		$customer_id = $this->input->get('customer_id');
		$recap_list = $this->common_model->get_customer_profile_pembelanjaan_tahun($customer_id, date('Y-01-01'), date('Y-12-31'));

		echo json_encode($recap_list);
	}

	function get_barang_jual_terbanyak()
	{
		$customer_id = $this->input->get('customer_id');

		$recap_list = $this->common_model->get_customer_profile_pembelian_barang_terbanyak($customer_id, date('Y'));

		echo json_encode($recap_list);
	}

	function initial_customer_type()
	{
	}

	function customer_list_export_excel()
	{

		$get_customer = $this->common_model->db_select('nd_customer where status_aktif = 1');
		$get_toko = $this->common_model->db_select('nd_toko');
		foreach ($get_toko as $row) {
			$nama_toko = $row->nama;
		}
		$this->load->library('Excel/PHPExcel');

		ini_set("memory_limit", "600M");

		/** Caching to discISAM*/
		$cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_to_discISAM;
		$cacheSettings = array('');;

		PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);

		$objPHPExcel = new PHPExcel();

		$styleArray = array(
			'font' => array(
				'bold' => true,
				'size' => 12,
			)
		);

		$styleArrayBG = array(
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => 'FFFF00')
			)
		);

		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A1', ' CUSTOMER LIST ');

		$row_no = 4;
		$idx = 0;
		foreach ($get_customer as $row) {
			if ($idx == 0) {
				$coll = 'A';
				foreach ($row as $key => $value) {
					$objPHPExcel->getActiveSheet()->setCellValue($coll . $row_no, $key);
					$objPHPExcel->getActiveSheet()->getStyle($coll . $row_no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
					$objPHPExcel->getActiveSheet()->getStyle($coll . $row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					// $objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
					$coll++;
				}
				$row_no++;
				$idx++;
			}
		}

		$row_no = 5;
		$filter = array('.', '-');
		foreach ($get_customer as $row) {
			$coll = 'A';
			foreach ($row as $key => $value) {
				if ($key == 'npwp') {
					$value = str_replace($filter, '', $value);
				}
				$objPHPExcel->getActiveSheet()->setCellValue($coll . $row_no, $value);
				$objPHPExcel->getActiveSheet()->getStyle($coll . $row_no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
				$objPHPExcel->getActiveSheet()->getStyle($coll . $row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				// $objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
				$coll++;
			}
			$row_no++;
			// $coll++;
		}

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		ob_end_clean();


		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header("Content-Disposition: attachment;filename=Customer_list_$nama_toko.xls");
		header('Cache-Control: max-age=0');
		$objWriter->save('php://output');
	}

	///////////////////////////////////////////////////////////////////////////////////////////////
	//================================ warna list ================================================
	///////////////////////////////////////////////////////////////////////////////////////////////
	function warna_list()
	{
		$menu = is_get_url($this->uri->segment(1));

		$data = array(
			'content' => 'admin/master/warna_list',
			'breadcrumb_title' => 'Master',
			'breadcrumb_small' => 'Daftar Warna',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data' => $this->data
		);

		$data['user_id'] = is_user_id();
		$data['warna_list'] = $this->common_model->db_select('nd_warna order by warna_beli asc');
		$this->load->view('admin/template', $data);
	}

	private function warna_validate($tipe = 'new', $id = 0)
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		//validasi warna beli
		$warna_beli = $this->input->post('warna_beli');

		if ($warna_beli == '') {
			$data['inputerror'][] = 'warna_beli';
			$data['error_string'][] = 'Warna beli harus diisi!';
			$data['status'] = FALSE;
		} else {
			//cek apakah double 
			if ($tipe == 'new') {
				$hasil_warna_beli = $this->common_model->db_select_num_rows("nd_warna where warna_beli = '" . $warna_beli . "'");
			} else {
				$hasil_warna_beli = $this->common_model->db_select_num_rows("nd_warna where warna_beli = '" . $warna_beli . "' AND id <> '" . $id . "'");
			}

			if ($hasil_warna_beli > 0) {
				$data['inputerror'][] = 'warna_beli';
				$data['error_string'][] = 'Warna Beli sudah terdaftar!';
				$data['status'] = FALSE;
			}
		}

		//validasi warna jual
		$warna_jual = $this->input->post('warna_jual');

		if ($warna_jual == '') {
			$data['inputerror'][] = 'warna_jual';
			$data['error_string'][] = 'Warna jual harus diisi!';
			$data['status'] = FALSE;
		} else {
			//cek apakah double 
			if ($tipe == 'new') {
				$hasil_warna_jual = $this->common_model->db_select_num_rows("nd_warna where warna_jual = '" . $warna_jual . "'");
			} else {
				$hasil_warna_jual = $this->common_model->db_select_num_rows("nd_warna where warna_jual = '" . $warna_jual . "' AND id <> '" . $id . "'");
			}

			if ($hasil_warna_jual > 0) {
				$data['inputerror'][] = 'warna_jual';
				$data['error_string'][] = 'Warna Jual sudah terdaftar!';
				$data['status'] = FALSE;
			}
		}

		if ($data['status'] == FALSE) {
			echo json_encode($data);
			exit();
		}
	}

	public function warna_validate_start()
	{
		$tipe = $this->uri->segment(3);
		$id = $this->uri->segment(4);

		$this->warna_validate($tipe, $id);

		echo json_encode(array("status" => TRUE));
	}

	function warna_list_insert()
	{
		$data = array(
			'warna_beli' => $this->input->post('warna_beli'),
			'warna_jual' => $this->input->post('warna_jual'),
			'kode_warna' => $this->input->post('kode_warna'),
		);

		$this->common_model->db_insert('nd_warna', $data);

		redirect(trim(base64_encode('master/warna_list'), '='));
	}

	function warna_list_update()
	{

		$id = $this->input->post('id');

		$data = array(
			'warna_beli' => $this->input->post('warna_beli'),
			'warna_jual' => $this->input->post('warna_jual'),
			'kode_warna' => $this->input->post('kode_warna'),
		);

		$this->common_model->db_update('nd_warna', $data, 'id', $id);

		redirect(trim(base64_encode('master/warna_list'), '='));
	}






	function warna_profile()
	{
		$menu = is_get_url($this->uri->segment(1));
		$warna_id = $this->uri->segment(2);
		$tahun = date('Y');
		if ($this->input->get('tahun') && $this->input->get('tahun') != '') {
			$tahun = $this->input->get('tahun');
		}

		$tanggal_awal = date("Y-m-01");
		$tanggal_akhir = date("Y-m-t");
		$barang_id = '';
		if ($this->input->get('tanggal_awal') != '' && $this->input->get('tanggal_akhir') != '') {
			$tanggal_awal = is_date_formatter($this->input->get('tanggal_awal'));
			$tanggal_akhir = is_date_formatter($this->input->get('tanggal_akhir'));
		}

		if ($this->input->get('barang_id') != '') {
			$barang_id = $this->input->get('barang_id');
		}

		$data = array(
			'content' => 'admin/master/warna_profile',
			'breadcrumb_title' => 'Master',
			'breadcrumb_small' => 'Profil Warna',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'barang_id' => $barang_id,
			'warna_id' => $warna_id,
			'tahun' => $tahun,
			'tanggal_awal' => $tanggal_awal,
			'tanggal_akhir' => $tanggal_akhir,
			'common_data' => $this->data
		);


		$data['data_warna'] = $this->common_model->db_select('nd_warna where id=' . $warna_id);
		$data['data_barang'] = $this->common_model->get_barang_asosiasi($warna_id);
		$data['data_penjualan'] = $this->common_model->get_penjualan_report_limit_by_warna($warna_id, $tahun);
		// $data['customer_profile_hutang'] = $this->common_model->get_customer_profile_piutang($customer_id); 
		// $data['customer_dp'] = $this->common_model->get_dp_by_customer($customer_id); 
		// $data['data_penjualan'] = $this->common_model->get_data_penjualan($customer_id);

		// $limit = "LIMIT ".(is_posisi_id() <= 5 ? '30' : '1');

		$this->load->view('admin/template_no_sidebar', $data);
	}

	function get_penjualan_by_warna_barang()
	{

		$warna_id = $this->input->get('warna_id');
		$tahun = $this->input->get('tahun');
		$tipe = $this->input->get('tipe');
		if ($tipe == 0) {
			$result = $this->common_model->get_penjualan_report_by_warna_barang($warna_id, $tahun);
		} else {
			$result = $this->common_model->get_penjualan_report_by_warna_customer($warna_id, $tahun);
		}
		echo json_encode($result);
	}


	//================================toko list================================================

	function toko_list()
	{
		$menu = is_get_url($this->uri->segment(1));

		$data = array(
			'content' => 'admin/master/toko_list',
			'breadcrumb_title' => 'Master',
			'breadcrumb_small' => 'Daftar Toko',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data' => $this->data
		);

		$data['user_id'] = is_user_id();
		$data['toko_list'] = $this->common_model->db_select('nd_toko where id = 1');
		$this->load->view('admin/template', $data);
	}

	private function pin_validate()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		//validasi nama 
		$pin = $this->input->post('pin');

		if ($pin == '') {
			$data['inputerror'][] = 'pin';
			$data['error_string'][] = 'Pin harus diisi!';
			$data['status'] = FALSE;
		} else {
			//cek apakah pin ada 
			$hasil_nama = $this->common_model->db_select_num_rows("nd_user where pin = '" . $pin . "' AND status_aktif = 1 AND posisi_id <= '2'");

			if ($hasil_nama <= 0) {
				$data['inputerror'][] = 'pin';
				$data['error_string'][] = 'Pin salah!';
				$data['status'] = FALSE;
			}
		}

		if ($data['status'] == FALSE) {
			echo json_encode($data);
			exit();
		}
	}

	public function pin_validate_start()
	{
		$this->pin_validate();

		echo json_encode(array("status" => TRUE));
	}

	function toko_list_update()
	{

		$id = $this->input->post('id');

		$data = array(
			'nama' => $this->input->post('nama'),
			'alamat' => $this->input->post('alamat'),
			'pre_faktur' => $this->input->post('pre_faktur'),
			'pre_po' => $this->input->post('pre_po'),
			'telepon' => $this->input->post('telepon'),
			'fax' => ($this->input->post('fax') == 0 ? '' : $this->input->post('fax')),
			'kota' => $this->input->post('kota'),
			'kode_pos' => $this->input->post('kode_pos'),
			'NPWP' => ($this->input->post('NPWP') == '0' ? '' : $this->input->post('NPWP'))
		);

		$this->common_model->db_update('nd_toko', $data, 'id', $id);

		redirect(trim(base64_encode('master/toko_list'), '='));
	}

	//================================gudang list================================================

	function gudang_list()
	{
		$menu = is_get_url($this->uri->segment(1));

		$data = array(
			'content' => 'admin/master/gudang_list',
			'breadcrumb_title' => 'Master',
			'breadcrumb_small' => 'Daftar Gudang',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data' => $this->data
		);

		$data['user_id'] = is_user_id();
		$data['gudang_list'] = $this->common_model->db_select('nd_gudang order by urutan asc');
		$this->load->view('admin/template', $data);
	}

	private function gudang_validate($tipe = 'new', $id = 0)
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		//validasi nama 
		$nama = $this->input->post('nama');

		if ($nama == '') {
			$data['inputerror'][] = 'nama';
			$data['error_string'][] = 'Nama harus diisi!';
			$data['status'] = FALSE;
		} else {
			//cek apakah double 
			if ($tipe == 'new') {
				$hasil_nama = $this->common_model->db_select_num_rows("nd_gudang where nama = '" . $nama . "'");
			} else {
				$hasil_nama = $this->common_model->db_select_num_rows("nd_gudang where nama = '" . $nama . "' AND id <> '" . $id . "'");
			}

			if ($hasil_nama > 0) {
				$data['inputerror'][] = 'nama';
				$data['error_string'][] = 'Nama sudah terdaftar!';
				$data['status'] = FALSE;
			}
		}

		if ($data['status'] == FALSE) {
			echo json_encode($data);
			exit();
		}
	}

	public function gudang_validate_start()
	{
		$tipe = $this->uri->segment(3);
		$id = $this->uri->segment(4);

		$this->gudang_validate($tipe, $id);

		echo json_encode(array("status" => TRUE));
	}

	function gudang_list_insert()
	{
		//get status from post 
		$visible = $this->input->post('visible');
		$status_default = $this->input->post('status_default');
		$urutan = $this->input->post('urutan');

		//update seluruh gudang status_default = 0
		if ($status_default == 1) {
			$data = array(
				'status_default' => 0
			);

			$this->common_model->db_update_all('nd_gudang', $data);
		}

		//insert data
		$data = array(
			'nama' => $this->input->post('nama'),
			'lokasi' => $this->input->post('lokasi'),
			'status_default' => $status_default,
			'visible' => $visible,
			'urutan' => ($urutan != '' ? $urutan : 0)
		);

		$this->common_model->db_insert('nd_gudang', $data);

		redirect(trim(base64_encode('master/gudang_list'), '='));
	}

	function gudang_list_update()
	{
		//get data from post 
		$id = $this->input->post('gudang_list_id');
		$visible = $this->input->post('visible');
		$status_default = $this->input->post('status_default');
		$urutan = $this->input->post('urutan');

		//update data 
		$data = array(
			'nama' => $this->input->post('nama'),
			'lokasi' => $this->input->post('lokasi'),
			'status_default' => $status_default,
			'visible' => $visible,
			'urutan' => ($urutan != '' ? $urutan : 0)
		);

		$this->common_model->db_update('nd_gudang', $data, 'id', $id);

		//update seluruh gudang status_default = 0 (kecuali id ini)
		if ($status_default == 1) {
			$data = array(
				'status_default' => 0
			);

			$this->common_model->db_update('nd_gudang', $data, 'id != ', $id);
		}

		redirect(trim(base64_encode('master/gudang_list'), '='));
	}

	//================================close program date list================================================

	function close_day_list()
	{
		$menu = is_get_url($this->uri->segment(1));

		$data = array(
			'content' => 'admin/master/close_day_list',
			'breadcrumb_title' => 'Admin',
			'breadcrumb_small' => 'Daftar Hari Tutup Akses Program',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data' => $this->data
		);

		$data['user_id'] = is_user_id();
		$data['close_day_list'] = $this->common_model->db_select('nd_close_day');
		$this->load->view('admin/template', $data);
	}


	function close_day_list_insert()
	{
		$data = array(
			'tanggal_start' => is_date_formatter($this->input->post('tanggal_start')),
			'tanggal_end' => is_date_formatter($this->input->post('tanggal_end')),
			'keterangan' => $this->input->post('keterangan'),
			'user_id' => is_user_id(),
		);
		$this->common_model->db_insert('nd_close_day', $data);
		redirect(trim(base64_encode('master/close_day_list'), '='));
	}

	function close_day_list_update()
	{

		$id = $this->input->post('close_day_id');

		$data = array(
			'tanggal_start' => is_date_formatter($this->input->post('tanggal_start')),
			'tanggal_end' => is_date_formatter($this->input->post('tanggal_end')),
			'keterangan' => $this->input->post('keterangan'),
			'user_id' => is_user_id(),
		);

		$this->common_model->db_update('nd_close_day', $data, 'id', $id);
		redirect(trim(base64_encode('master/close_day_list'), '='));
	}

	//================================category list================================================

	function category_list()
	{
		$menu = is_get_url($this->uri->segment(1));

		$data = array(
			'content' => 'admin/master/category_list',
			'breadcrumb_title' => 'Master',
			'breadcrumb_small' => 'Daftar Category',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data' => $this->data
		);

		$data['user_id'] = is_user_id();
		$data['category_list'] = $this->common_model->db_select('nd_category where status_aktif = 1');
		$this->load->view('admin/template', $data);
	}


	function category_list_insert()
	{
		$data = array(
			'nama' => $this->input->post('nama'),
			'user_id' => is_user_id()
		);
		$this->common_model->db_insert('nd_category', $data);
		redirect(is_setting_link('master/category_list'));
	}

	function category_list_update()
	{

		$id = $this->input->post('category_id');

		$data = array(
			'nama' => $this->input->post('nama'),
			'user_id' => is_user_id()
		);

		$this->common_model->db_update('nd_category', $data, 'id', $id);
		redirect(is_setting_link('master/category_list'));
	}

	function category_list_remove()
	{
		$id = $this->input->get('id');

		$data = array(
			'status_aktif' => 0,
			'user_id' => is_user_id()
		);

		$this->common_model->db_update('nd_category', $data, 'id', $id);
		redirect(is_setting_link('master/category_list'));
	}

	//================================bank list================================================

	function bank_list()
	{
		$menu = is_get_url($this->uri->segment(1));

		$data = array(
			'content' => 'admin/master/bank_list',
			'breadcrumb_title' => 'Master',
			'breadcrumb_small' => 'Daftar Bank',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data' => $this->data
		);

		$data['user_id'] = is_user_id();
		$data['bank_list'] = $this->common_model->db_select('nd_bank_list');
		$this->load->view('admin/template', $data);
	}

	private function bank_validate($tipe = 'new', $id = 0)
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		//validasi nama 
		$nama_bank = $this->input->post('nama_bank');

		if ($nama_bank == '') {
			$data['inputerror'][] = 'nama_bank';
			$data['error_string'][] = 'Nama harus diisi!';
			$data['status'] = FALSE;
		} else {
			//cek apakah double 
			/* if ($tipe == 'new') {
				$hasil_nama = $this->common_model->db_select_num_rows("nd_bank_list where nama_bank = '" . $nama_bank . "'");
			} else {
				$hasil_nama = $this->common_model->db_select_num_rows("nd_bank_list where nama_bank = '" . $nama_bank . "' AND id <> '" . $id . "'");
			} */

			/* if ($hasil_nama > 0) {
				$data['inputerror'][] = 'nama_bank';
				$data['error_string'][] = 'Nama Bank sudah terdaftar!';
				$data['status'] = FALSE;
			} */
		}

		//validasi no rekening bank
		$no_rek_bank = $this->input->post('no_rek_bank');

		if ($no_rek_bank == '') {
			$data['inputerror'][] = 'no_rek_bank';
			$data['error_string'][] = 'No. Rekening Bank harus diisi!';
			$data['status'] = FALSE;
		} else {
			//cek apakah double 
			if ($tipe == 'new') {
				$hasil_no_rekening = $this->common_model->db_select_num_rows("nd_bank_list where no_rek_bank = '" . $no_rek_bank . "'");
			} else {
				$hasil_no_rekening = $this->common_model->db_select_num_rows("nd_bank_list where no_rek_bank = '" . $no_rek_bank . "' AND id <> '" . $id . "'");
			}

			if ($hasil_no_rekening > 0) {
				$data['inputerror'][] = 'no_rek_bank';
				$data['error_string'][] = 'No. Rekening sudah terdaftar!';
				$data['status'] = FALSE;
			}
		}

		if ($data['status'] == FALSE) {
			echo json_encode($data);
			exit();
		}
	}

	public function bank_validate_start()
	{
		$tipe = $this->uri->segment(3);
		$id = $this->uri->segment(4);

		$this->bank_validate($tipe, $id);

		echo json_encode(array("status" => TRUE));
	}

	function bank_list_insert()
	{
		$data = array(
			'nama_bank' => $this->input->post('nama_bank'),
			'no_rek_bank' => $this->input->post('no_rek_bank'),
			'tipe_trx_1' => $this->input->post('tipe_trx_1'),
			'tipe_trx_2' => $this->input->post('tipe_trx_2'),
			'user_id' => is_user_id(),
		);
		$this->common_model->db_insert('nd_bank_list', $data);
		redirect(trim(base64_encode('master/bank_list'), '='));
	}

	function bank_list_update()
	{

		$id = $this->input->post('bank_id');

		$data = array(
			'nama_bank' => $this->input->post('nama_bank'),
			'no_rek_bank' => $this->input->post('no_rek_bank'),
			'tipe_trx_1' => $this->input->post('tipe_trx_1'),
			'tipe_trx_2' => $this->input->post('tipe_trx_2'),
			'user_id' => is_user_id(),
		);

		$this->common_model->db_update('nd_bank_list', $data, 'id', $id);
		redirect(trim(base64_encode('master/bank_list'), '='));
	}
}