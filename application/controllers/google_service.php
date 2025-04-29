<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Google_Service extends CI_Controller {

	private $data = [];

	function __construct() 
	{
		parent:: __construct();
		
		is_logged_in();
		if(is_username() == ''){
			redirect('home');
		}elseif (is_user_time() == false) {
			redirect('home');
		}

		if (is_maintenance_on() && $row->posisi_id != 1) {
			redirect(base_url().'home/maintenance_mode');
		}

		$this->data['username'] = is_username();
		$this->load->model('admin_model','',true);
		
		$this->data['user_menu_list'] = is_user_menu(is_posisi_id());
		
		// date_default_timezone_set("Asia/Jakarta");		
		$this->supplier_list_aktif = $this->common_model->db_select('nd_supplier where status_aktif = 1');
		$this->customer_list_aktif = $this->common_model->db_select('nd_customer where status_aktif = 1 order by nama asc');
		$this->toko_list_aktif = $this->common_model->db_select('nd_toko where status_aktif = 1');
		$this->gudang_list_aktif = $this->common_model->db_select('nd_gudang where status_aktif = 1');

		$this->warna_list_aktif = $this->common_model->db_select('nd_warna where status_aktif = 1');
		$this->barang_list_aktif = $this->common_model->get_barang_list_aktif();
		$this->satuan_list_aktif = $this->common_model->db_select('nd_satuan where status_aktif = 1');
	   	$this->pre_faktur = get_pre_faktur();		
	}

	function index()
	{
		echo 'y';
		// redirect('admin/dashboard');
		// $res = $this->common_model->db_free_query_superadmin("CALL get_stock('2021-01-05 23:59:59')");
		// print_r($res->result());
		// $this->common_model->db_free_query_superadmin()
		// $this->dashboard();
		// echo 'admin';
	}

	function get_token()
	{
		if (isset($_SERVER['HTTP_ORIGIN'])) {
			$http_origin = $_SERVER['HTTP_ORIGIN'];
			// $data['google_setting']  =$this->common_model->db_select('nd_setting');
			// $this->load->view('admin/setting/google_get_token', $data);

			$google_setting  =$this->common_model->db_select('nd_setting');

			$client_id = "";
			$client_secret = "";
			$refresh_token = ""; 
			$credentials = "";

			foreach ($google_setting as $row) {
				$client_id = $row->google_client_id;
				$client_secret = $row->google_client_secret;
				$refresh_token = $row->google_refresh_token;
				$credentials = $row->google_credentials;
			}
		}else{
			redirect('admin/dashboard');
		}
	}
}

/* End of file admin.php */
/* Location: ./application/controllers/admin.php */