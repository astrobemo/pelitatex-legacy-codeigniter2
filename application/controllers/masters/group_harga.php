<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Group_harga extends CI_Controller
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
		$this->load->library('form_validation');

		$this->load->model('inventory_model', 'inv_model', true);
		$this->load->model('group_harga_model', 'gh_model', true);

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
		$this->view_folder = 'admin/master/group_harga/';

	}

	function index()
	{
		// redirect('admin/dashboard');
	}


    function daftar(){
        // $menu = is_get_url($this->uri->segment(1));

		$data = array(
			'content' => $this->view_folder."daftar",
			'breadcrumb_title' => 'Master',
			'breadcrumb_small' => 'Daftar Harga',
			// 'nama_menu' => $menu[0],
			// 'nama_submenu' => $menu[1],
			'common_data' => $this->data
		);

		$data['harga_list'] = $this->gh_model->get_group_harga_all();
		$data['base_price_list'] = $this->gh_model->get_harga_list_berlaku();


		// $this->form_validation->set_message('check_nama_group',"Mohon isi tipe 'CASH' atau 'KREDIT' ");
		
		$this->form_validation->set_rules('id','Index', '');
		$this->form_validation->set_rules('tipe','Tipe Harga', 'required');
		$this->form_validation->set_rules('nama','Nama Group', 'trim|required|callback_check_nama_group');
		$this->form_validation->set_rules('is_default','Default', '');
		$this->form_validation->set_rules('deskripsi','Default', '');

		if($this->form_validation->run() == FALSE){
			$this->load->view('admin/template', $data);
		}else{

			$tipe = $this->input->post('tipe');
			$base_price = $this->input->post("base_price");
			$add_price = $this->input->post("add_price");
			$add_price = ($add_price === '' ? 0 : $add_price);

			
			//set yang lain ke 0
			$data = array(
				'nama' => $this->input->post('nama') ,
				'deskripsi' => trim($this->input->post('deskripsi')),
				'tipe' => $tipe,
				'user_id' => is_posisi_id()
			);

			

			if ($this->input->post('id') == '') {
				$id = $this->common_model->db_insert('nd_group_harga_barang', $data);
				$this->create_start_price($base_price, $id, $tipe, $add_price);
			}else{
				$id = $this->input->post('id');
				$this->common_model->db_update('nd_group_harga_barang', $data,'id', $id);
				redirect(is_setting_link('masters/group_harga/daftar'));
			}
			

			redirect(is_setting_link('masters/group_harga/edit').'?id='.$id);
		}
    }

	function create_start_price($base_price, $group_harga_barang_id, $tipe, $add_price){
		$group_harga = array();
		if ($base_price !== 0) {	
			$get = $this->gh_model->get_harga_berlaku($base_price);
			foreach ($get as $row) {
				$group_harga[$row->barang_id] = $row->harga;
			}
		}

		$group_harga_new = array();
		$hrg = 'harga_kredit';
		if ($tipe==1) {
			$hrg = 'harga_cash';
		}
		foreach ($this->barang_list_aktif as $row) {
			$set_harga = (isset($group_harga[$row->id]) ? $group_harga[$row->id] : $row->harga_jual);
			array_push($group_harga_new, array(
				'group_harga_barang_id' => $group_harga_barang_id,
				'barang_id' => $row->id,
				$hrg=>$set_harga + $add_price
			));
		}
		

		$this->common_model->db_insert_batch("nd_group_harga_berlaku", $group_harga_new);
	}

	function check_nama_group($nama_group){
		//Field validation succeeded.  Validate against database
		//query the database
		$nama_group = trim($nama_group);
		$id = $this->input->post('id');
		$cond = '';
		if ($id != '') {
			$cond = " AND id != $id";
		}
		$result = $this->common_model->db_select("nd_group_harga_barang WHERE nama = '$nama_group' $cond");
		if(count($result) > 0){
			$this->form_validation->set_message('check_nama_group',"Nama '$nama_group' sudah terdaftar, mohon gunakan yang lain");
			return false;
		}else{
			return true;
		}
	}
	

	function daftar_update(){
		$id = $this->input->post('id');

		$this->form_validation->set_rules('nama','Nama Group', 'trim|required|callback_check_nama_group');
		$this->form_validation->set_rules('id','Index', '');

		$data = array(
			'nama' => $this->input->post('nama') ,
			'deskripsi' => trim($this->input->post('deskripsi')),
			'user_id' => is_posisi_id()  
		);

		if($this->form_validation->run() == FALSE){
			$this->load->view('admin/template', $data);
		}else{
			print_r($this->validation_errors());
		}
	}

//==========================================================================================
	function edit(){
		// $menu = is_get_url($this->uri->segment(1));
		$id = $this->input->get('id');
		$today = date("Y-m-d");

		$data = array(
			'content' => $this->view_folder."edit",
			'breadcrumb_title' => 'Master',
			'breadcrumb_small' => 'Daftar Harga',
			// 'nama_menu' => $menu[0],
			// 'nama_submenu' => $menu[1],
			'common_data' => $this->data,
			'id' => $id
		);

		// $data['harga_jual_cash_history'] = $this->common_model->db_select("nd_group_harga_history WHERE harga_cash is not null AND harga_cash > 0 AND group_harga_barang_id=$id  ORDER BY barang_id, tanggal_archive desc");
		// $data['harga_jual_kredit_history'] = $this->common_model->db_select("nd_group_harga_history WHERE harga_kredit is not null AND harga_kredit > 0 AND group_harga_barang_id=$id  ORDER BY barang_id, tanggal_archive desc");
		// $data['harga_jual_berlaku'] = $this->common_model->db_select("nd_group_harga_berlaku WHERE group_harga_barang_id=$id");
		$data['harga_jual_history'] = $this->common_model->db_select("nd_group_harga_history WHERE group_harga_barang_id=$id  ORDER BY barang_id, tanggal_archive desc");
		$data['harga_jual_berlaku'] = $this->gh_model->get_harga_berlaku($id);
		
		$data['harga_jual_baru'] = $this->common_model->db_select("nd_group_harga_baru WHERE group_harga_barang_id=$id");
		// $data['harga_po_terkhir'] = $this->gh_model->get_all_item_last_price();
		$data['harga_beli_terkhir'] = $this->gh_model->get_all_item_last_purchased();
		$ppn_berlaku = $this->common_model->db_select("nd_ppn WHERE tanggal <= '$today' ORDER BY tanggal DESC limit 1");
		$data['ppn_berlaku'] = $ppn_berlaku[0]->ppn;
		$info_active = $this->common_model->db_select("nd_group_harga_baru_info WHERE group_harga_barang_id=".$id." ORDER BY updated_at desc LIMIT 1");
		$data['harga_baru_info'] = $info_active;
		$data['daftar_harga_all'] = $this->common_model->db_select("nd_group_harga_barang");
		$data['base_price_list'] = $this->gh_model->get_harga_list_berlaku();

		$data['harga_hpp_history'] = $this->common_model->db_free_query_superadmin("SELECT *,
			(
				SELECT ppn 
				FROM nd_ppn tX 
				WHERE tX.tanggal <= result.tanggal 
				ORDER BY tX.tanggal DESC 
				LIMIT 1 
			) as ppn_berlaku
			FROM (
				SELECT barang_id, harga_total, jml, tanggal,
				CASE barang_id 
					WHEN @curType THEN @curRow := @curRow + 1 
					ELSE @curRow := 1
				END AS ranks,
				@curType := barang_id AS type
				FROM (
					SELECT barang_id, sum(harga) as harga_total, count(harga) as jml, 
					GROUP_CONCAT(warna_id), tanggal, barang_id as conc
					FROM vw_harga_hpp
					WHERE harga > 0
					GROUP BY barang_id, YEAR(tanggal), MONTH(tanggal)
				) res
				JOIN (SELECT @curRow := 0, @curType := '') r
				ORDER BY barang_id asc, tanggal desc
			) result
			WHERE ranks =1")->result();

		$this->load->view('admin/template', $data);	
	}

	function edit_insert(){
		$group_harga_barang_id = $this->input->post('group_harga_barang_id');
		$barang_id = $this->input->post('barang_id');
		$get_data = $this->common_model->db_select("nd_group_harga_baru WHERE group_harga_barang_id = $group_harga_barang_id AND barang_id = $barang_id");
		// $mirroring = $this->input->post('mirroring');
		// $mirroring = ($mirroring == 'true' ? true : false);
		

		$harga_baru_id = '';
		foreach ($get_data as $row) {
			$harga_baru_id = $row->id;
		}

		$harga_baru = str_replace(".","", $this->input->post('harga_baru'));
		$data = array(
			'group_harga_barang_id' => $group_harga_barang_id,
			'barang_id' => $barang_id ,
			'harga_baru' => ($harga_baru == '' ? 0 : $harga_baru),
		);
		
		if ($harga_baru_id != '') {
			$this->common_model->db_update("nd_group_harga_baru", $data, 'id', $harga_baru_id);
		}else{
			$this->common_model->db_insert("nd_group_harga_baru", $data);
		}

		echo "OK";
		// redirect(is_setting_link('mastercontrollers/daftar_rincian').'?id='.$id);
	}

	function edit_lock(){
		$group_harga_barang_id = $this->input->get('id');
		$harga_baru_info_id = $this->input->get('harga_baru_info_id');
		// $data = array(
		// 	'harga_baru_status' => 0
		// );

		$data_baru = array(
			'group_harga_barang_id' => $group_harga_barang_id ,
			'status' => 1,
			'locked_by' => is_user_id()
		);
		
		if ($harga_baru_info_id == '') {
			$this->common_model->db_insert("nd_group_harga_baru_info", $data_baru);
		}else{
			$this->common_model->db_update("nd_group_harga_baru_info", $data_baru,'id', $harga_baru_info_id);
		}		

		// $this->common_model->db_update("nd_group_harga_barang", $data,'id', $group_harga_barang_id);
		redirect(is_setting_link('masters/group_harga/edit').'?id='.$group_harga_barang_id);

	}

	function edit_open(){
		$group_harga_barang_id = $this->input->post('id');
		$harga_baru_info_id = $this->input->post('harga_baru_info_id');
		
		// $data = array(
		// 	'harga_baru_status' => 1
		// );

		$data_info = array(
			'status' => 2,
			'locked_by' => is_user_id()
		);		

		// $this->common_model->db_update("nd_group_harga_barang", $data,'id', $group_harga_barang_id);
		$this->common_model->db_update("nd_group_harga_baru_info", $data_info,'id', $harga_baru_info_id);
		redirect(is_setting_link('masters/group_harga/edit').'?id='.$group_harga_barang_id);

	}

	function edit_launch(){
		$group_harga_barang_id = $this->input->post('id');
		$tipe = $this->input->post("tipe");
		$t = explode(" ",$this->input->post("tanggal"));
		$tanggal = is_date_formatter($t[0]).' '.$t[1];
		$harga_baru_info_id = $this->input->post('harga_baru_info_id');

		// $data = array(
		// 	'harga_baru_status' => 0
		// );

		$data_info = array(
			'status' => 0,
			'launch_by' => is_user_id(),
			'launch_date' => date("Y-m-d H:i:s")
		);

		$this->common_model->db_update("nd_group_harga_baru_info", $data_info,"id", $harga_baru_info_id);

		$data_berlaku_baru = array();
		$data_history = array();

		foreach ($this->gh_model->get_harga_baru_launch($group_harga_barang_id) as $key => $row) {
			$data_history[$key] = array(
				"group_harga_barang_id" => $group_harga_barang_id,
				"harga_baru_info_id" => $harga_baru_info_id,
				"barang_id" => $row->barang_id,
				'harga_history' => $row->harga_berlaku ,
				'tanggal_archive' => $tanggal

			);

			$data_berlaku_baru[$key] = array(
				"id" => $row->berlaku_id,
				'harga_berlaku' => $row->harga_baru
			);

			// $barang_id
			// db_update_batch($table, $data, $param)
		}

		$data_baru_reset = array(
			'harga_cash' => null,
			'harga_kredit' => null,
			'harga_baru' => 0
		);

		if (count($data_berlaku_baru) > 0) {
			$this->common_model->db_insert_batch("nd_group_harga_history", $data_history);
			$this->common_model->db_update("nd_group_harga_baru", $data_baru_reset,"group_harga_barang_id", $group_harga_barang_id);
			$this->common_model->db_update_batch("nd_group_harga_berlaku", $data_berlaku_baru, "id");
		}

		redirect(is_setting_link('masters/group_harga/edit').'?id='.$group_harga_barang_id);
	}

	function daftar_rincian_update(){
		$id = $this->input->post('daftar_harga_baru_detail_id');
		$data = array(
			'barang_id' => $this->input->post('barang_id') ,
			'warna_id' => $this->input->post('warna_id'),
			'harga' => str_replace(".","", $this->input->post('harga'))
		);
		
		$this->common_model->db_update("nd_harga_baru", $data,'id', $id);
		// redirect(is_setting_link('mastercontrollers/daftar_rincian').'?id='.$id);
	}

	function daftar_rincian_remove(){
		$id = $this->input->post('daftar_harga_baru_detail_id');
		$this->common_model->db_delete("nd_harga_baru", 'id', $id);
		// redirect(is_setting_link('mastercontrollers/daftar_rincian').'?id='.$id);
	}

//==========================================================================================
	
	function edit_all(){
		$data = array(
			'content' => $this->view_folder."edit_all",
			'breadcrumb_title' => 'Master',
			'breadcrumb_small' => 'Daftar Harga',
			// 'nama_menu' => $menu[0],
			// 'nama_submenu' => $menu[1],
			'common_data' => $this->data
		);
		$data['base_price_list'] = $this->gh_model->get_harga_list_berlaku();

		$tipe = array();
		$data_header = $this->common_model->db_select("nd_group_harga_barang");
		foreach ($data_header as $row) {
			$tipe[$row->id] = $row->tipe;
		}
		$data['daftar_harga_all'] = $data_header;

		$get = $this->common_model->db_select("nd_group_harga_berlaku");
		$data_detail = array();
		foreach ($get as $row) {
			$data_detail[$row->group_harga_barang_id][$row->barang_id] = $row->harga_berlaku;
		}

		$data["brg"] = $data_detail;


		$this->load->view('admin/template', $data);	
	}

}