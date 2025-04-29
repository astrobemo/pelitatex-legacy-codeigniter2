<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Group_Harga_Customer extends CI_Controller
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
		$this->view_folder = 'admin/master/group_harga_customer/';

	}

	function index()
	{
		redirect('admin/dashboard');
	}


    function daftar(){
        // $menu = is_get_url($this->uri->segment(1));

		$data = array(
			'content' => $this->view_folder."daftar",
			'breadcrumb_title' => 'Masters ',
			'breadcrumb_small' => 'Daftar Harga',
			'common_data' => $this->data,
			'customer_id_new' => ''
		);

		if ($this->session->flashdata('customer_new_harga')) {
            $data['customer_id_new'] = $this->session->flashdata('customer_new_harga');

        }

		$data['harga_customer'] = $this->gh_model->get_harga_customer();
		$data['harga_customer_detail'] = $this->common_model->db_select('nd_customer_harga_detail');
		$data['harga_list'] = $this->gh_model->get_harga_list_berlaku();
		$data['harga_berlaku'] = $this->gh_model->get_harga_berlaku_all();
		// $data['harga_berlaku'] = array();
		$this->load->view('admin/template', $data);	
    }

	function get_harga_berlaku_percustomer(){
		$customer_id = $this->input->get("customer_id");
		$tipe = $this->input->get("tipe");
		$data = $this->gh_model->get_harga_berlaku_percustomer($customer_id, $tipe);
		echo json_encode($data);
	}

	function get_harga_berlaku_by_group_id(){
		$group_harga_barang_id = $this->input->get("group_harga_barang_id");
		$data = $this->common_model->db_select("nd_group_harga_berlaku WHERE group_harga_barang_id = $group_harga_barang_id");
		echo json_encode($data);
	}

	function daftar_insert(){
		$customer_id = $this->input->post('customer_id');
		$tipe_cash = $this->input->post('tipe_cash');
		$tipe_kredit = $this->input->post('tipe_kredit');
		$idx = 0;
		if ($tipe_cash != '' && $tipe_cash > 0) {
			$data[$idx] = array(
				'customer_id' => $customer_id,
				'group_harga_barang_id' => $tipe_cash,
				'tipe' => 1,
				'user_id' => is_user_id()
 			);
			$idx++;
		}
		
		if ($tipe_kredit != '' && $tipe_kredit > 0 ) {
			$data[$idx] = array(
				'customer_id' => $customer_id,
				'group_harga_barang_id' => $tipe_kredit,
				'tipe' => 2,
				'user_id' => is_user_id()
 			);
		}

		// print_r($data);

		// if ($customer_id == 0) {
			
		// }
		$this->common_model->db_insert_batch('nd_customer_harga', $data);
        $this->session->set_flashdata('customer_new_harga', $customer_id);
		redirect(is_setting_link('masters/group_harga_customer/daftar'));
		
	}

	function daftar_update(){
		$customer_id = $this->input->post('customer_id');
		$tipe_cash = $this->input->post('tipe_cash');
		$tipe_kredit = $this->input->post('tipe_kredit');

		if ($customer_id == '') {
			echo "ERROR no customer";
			// redirect(is_setting_link('masters/group_harga_customer/daftar'));
			// $customer_id = 0;
		}else if($customer_id == 0){
			$reset = array('isDefault' => 0);
			$dlgt = array('isDefault' => 1);

			if ($tipe_cash != '') {
				$gh_id = $tipe_cash;
				$matching = array("group_harga_barang_id"=>$gh_id);
				$cond_gh = array(
					"customer_id" => $customer_id,
					"tipe" => 1
				);
				$this->common_model->db_update_multiple_cond("nd_customer_harga", $matching, $cond_gh);

				$cond = array(
					"tipe"=>1,
					"isDefault"=>1
				);
				$this->common_model->db_update_multiple_cond("nd_group_harga_barang", $reset, $cond);
				$this->common_model->db_update("nd_group_harga_barang",$dlgt, "id", $gh_id);
			}

			if ($tipe_kredit != '') {
				$gh_id = $tipe_kredit;
				$matching = array("group_harga_barang_id"=>$gh_id);
				$cond_gh = array(
					"customer_id" => $customer_id,
					"tipe" => 2
				);
				$this->common_model->db_update_multiple_cond("nd_customer_harga", $matching, $cond_gh);

				$cond = array(
					"tipe"=>2,
					"isDefault"=>1
				);
				
				$this->common_model->db_update_multiple_cond("nd_group_harga_barang", $reset, $cond);
				$this->common_model->db_update("nd_group_harga_barang",$dlgt, "id", $gh_id);
			}
			

		}
		$get_data = $this->common_model->db_select("nd_customer_harga WHERE customer_id=$customer_id");
		$get_data_detail = $this->common_model->db_select("nd_customer_harga_detail WHERE customer_id=$customer_id");

		$id_detail = [];
		foreach ($get_data_detail as $row) {
			$id_detail[$row->barang_id][$row->tipe] = $row->id;
			$total_before[$row->barang_id][$row->tipe] = $row->harga_total;
		}

		$harga_customer_id_cash = "";
		$harga_customer_id_kredit = "";
		foreach ($get_data as $row) {
			if ($row->tipe == 1) {
				$harga_customer_id_cash = $row->id;
			}
			if ($row->tipe == 2) {
				$harga_customer_id_kredit = $row->id;
			}
		}


		$new_data = [];
		$update_data = [];
		$history_data = [];
		if ($tipe_cash != '') {
			foreach ($this->input->post('cash') as $key => $value) {
				if ($value == '') {
					$n1 = 0;
				}else{
					$n1 = str_replace(".","",$value);
					$n1 = str_replace(",",".", $n1);
				}

				if ($this->input->post('totalcash')[$key] == '') {
					$n2 = 0;
				}else{
					$n2 = str_replace(".","",$this->input->post('totalcash')[$key]);
					$n2 = str_replace(",",".", $n2);
				}
				
				$n1 = (trim($n1) == '' ? 0 : $n1);
				$n2 = (trim($n2) == '' ? 0 : $n2);
				if (!isset($id_detail[$key][1])) {
					array_push($new_data, array(
						'customer_id' => $customer_id,
						'tipe' => 1,
						'barang_id' => $key,
						'selisih_harga' => $n1,
						'harga_total' => $n2
					));
				}else{
					array_push($update_data, array(
						'id' => $id_detail[$key][1],
						'customer_id' => $customer_id,
						'tipe' => 1,
						'barang_id' => $key,
						'selisih_harga' => $n1,
						'harga_total' => $n2
					));
					
					if (isset($total_before[$key][1]) && $total_before[$key][1] != $n2) {
						array_push($history_data, array(
							'customer_id' => $customer_id,
							'tipe' => 1,
							'barang_id' => $key,
							'harga_before' => $total_before[$key][1],
							'harga_after' => $n2,
							'user_id' => is_user_id()
						));	
					}
				}
			}

			$data_updt = array(
				"group_harga_barang_id" => $tipe_cash,
				'customer_id' => $customer_id,
				'user_id' => is_user_id(),
				'tipe' => 1
			);
			if ($harga_customer_id_cash != '') {
				$this->common_model->db_update("nd_customer_harga", $data_updt, "id", $harga_customer_id_cash);
			}else{
				$this->common_model->db_insert("nd_customer_harga", $data_updt);
			}
		}else{
			$cond = array(
				'customer_id' => $customer_id,
				'tipe' => 1
			);
			$this->common_model->db_delete_array("nd_customer_harga_detail", $cond);

			if ($harga_customer_id_cash != '') {
				$data_updt = array(
					"group_harga_barang_id" => null
				);	
				$this->common_model->db_update("nd_customer_harga", $data_updt, "id", $harga_customer_id_cash);
			}
		}

		if ($tipe_kredit != '') {
			foreach ($this->input->post('kredit') as $key => $value) {
				if ($value == '') {
					$n1 = 0;
				}else{
					$n1 = str_replace(".","",$value);
					$n1 = str_replace(",",".", $n1);
				}

				if ($this->input->post('totalcash')[$key] == '') {
					$n2 = 0;
				}else{
					$n2 = str_replace(".","",$this->input->post('totalkredit')[$key]);
					$n2 = str_replace(",",".", $n2);
				}

				$n1 = (trim($n1) == '' ? 0 : $n1);
				$n2 = (trim($n2) == '' ? 0 : $n2);
				if (!isset($id_detail[$key][2])) {
					array_push($new_data, array(
						'customer_id' => $customer_id,
						'tipe' => 2,
						'barang_id' => $key,
						'selisih_harga' => $n1,
						'harga_total' => $n2
					));
				}else{
					array_push($update_data, array(
						'id' => $id_detail[$key][2],
						'customer_id' => $customer_id,
						'tipe' => 2,
						'barang_id' => $key,
						'selisih_harga' => $n1,
						'harga_total' => $n2
					));

					if (isset($total_before[$key][2]) && $total_before[$key][2] != $n2) {
						array_push($history_data, array(
							'customer_id' => $customer_id,
							'tipe' => 2,
							'barang_id' => $key,
							'harga_before' => $total_before[$key][2],
							'harga_after' => $n2,
							'user_id' => is_user_id()
						));	
					}
				}
			}

			$data_updt = array(
				"group_harga_barang_id" => $tipe_kredit,
				'customer_id' => $customer_id,
				'user_id' => is_user_id(),
				'tipe' => 2
			);

			if ($harga_customer_id_kredit != '') {
				$this->common_model->db_update("nd_customer_harga", $data_updt, "id", $harga_customer_id_kredit);
			}else{
				$this->common_model->db_insert("nd_customer_harga", $data_updt);
			}
		}else{
			$cond = array(
				'customer_id' => $customer_id,
				'tipe' => 2
			);
			$this->common_model->db_delete_array("nd_customer_harga_detail", $cond);

			if ($harga_customer_id_kredit != '') {
				$data_updt = array(
					"group_harga_barang_id" => null
				);
	
				$this->common_model->db_update("nd_customer_harga", $data_updt, "id", $harga_customer_id_kredit);
			}


		}

		if (count($update_data) > 0) {
			$this->common_model->db_update_batch("nd_customer_harga_detail", $update_data, 'id');
		}
		
		if (count($new_data) > 0){
			$this->common_model->db_insert_batch("nd_customer_harga_detail", $new_data);
		}

		if (count($history_data) > 0){
			$this->common_model->db_insert_batch("nd_customer_harga_history_detail", $history_data);
		}
		redirect(is_setting_link('masters/group_harga_customer/daftar'));
		
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
		

		$this->load->view('admin/template', $data);	
	}


	function remove_harga_customer(){
		$customer_id = $this->input->get('customer_id');
		$get = $this->common_model->db_select("nd_customer_harga WHERE customer_id = $customer_id");
		$customer_harga_id = '';
		foreach ($get as $row) {
			$customer_harga_id = $row->id;
		}
		$this->common_model->db_delete("nd_customer_harga" ,'customer_id', "$customer_id");
		$this->common_model->db_delete("nd_customer_harga_detail" ,'customer_harga_id', "$customer_harga_id");
		echo  json_encode("OK");
	}

}