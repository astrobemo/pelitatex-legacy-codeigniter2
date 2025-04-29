<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {

	function __construct() 
	{
		parent:: __construct();
		$this->load->helper(array('html_helper', 'url_helper'));
		$this->load->library(array('form_validation','session'));
		$this->load->library('form_validation');
		$this->load->model('home_model','',true);
	}

	function php_info(){
		//phpinfo();
	}

	//function get_autentifikasi(){
	//	echo 'true';
	//}

	function index()
	{
		redirect('home/login_soft');
		// echo 'test';
	}

	function login_soft()
	{
		$this->form_validation->set_rules('username','username', 'trim|required|xss_clean');
		$this->form_validation->set_rules('password','password', 'trim|required|xss_clean|callback_check_database');
		if($this->form_validation->run() == FALSE){
			$this->load->view('login');
			// $this->logout();
		}else{
			$session_data = $this->session->userdata('hameyean_logged_in');
			$id = $session_data['user_id'];

			redirect('admin');
			// redirect("http://localhost/hameyean/home/redirect_only_login_bWFzdGVyL2JhcmFuZ19saXN0?urd_798621_id=".$id);
		}
			
	}

	function redirect_only_login_bWFzdGVyL2JhcmFuZ19saXN0(){
		$id = $this->input->get('urd_798621_id');
		$result = $this->common_model->db_select("nd_user WHERE id=$id");
		$this->session->unset_userdata('hameyean_logged_in');
		$session_array = array();
		foreach ($result as $row) {
			// $user_type = $row->type;
			$session_array = array(
				'username'=>$row->username,
				'user_id'=>$row->id,
				'posisi_id'=>$row->posisi_id,
				'time_start' => $row->time_start,
				'time_end' => $row->time_end
				);

			$posisi_id = $row->posisi_id;


			if ($posisi_id == 1) {
				$this->session->sess_expiration = '14400';
			}
			$this->session->set_userdata('hameyean_logged_in',$session_array);
			$session_data = $this->session->userdata('hameyean_logged_in');

			$profile = $this->common_model->db_select("nd_toko where status_aktif = 1");

			foreach ($profile as $row) {
				$profile_array = array(
					'profile_id' => $row->id,
					'profile_nama' => $row->nama,
					'profile_alamat' => $row->alamat,
					'profile_kota' => $row->kota,
					'profile_kode_pos' => $row->kode_pos,
					'profile_telepon' => $row->telepon,
					'profile_fax' => $row->fax,
					'profile_npwp' => $row->NPWP
				);

				$this->session->set_userdata('hameyean_profile', $profile_array);
			}
			redirect('admin');
			
		}
		//echo 'test';

	}

	function check_database($password){
		 //Field validation succeeded.  Validate against database
		$username = $this->input->post('username');

		//query the database
   		$result = $this->home_model->check_database($username, $password);

   		if($result){

   			$tanggal = date('Y-m-d');
   			foreach ($result as $row) {
   				$posisi_id = $row->posisi_id;
   			}

   			if ($posisi_id >= 3) {
	   			$day_status = $this->common_model->db_select_num_rows("nd_close_day where tanggal_start <='".$tanggal."' AND tanggal_end >='".$tanggal."'");
	   			if ($day_status > 0) {
	   				$this->form_validation->set_message('check_database','TOKO Saat Init Sedang Tutup');
		   			return false;
	   			}
   			}

   			$this->session->unset_userdata('hameyean_logged_in');

   			$session_array = array();
   			foreach ($result as $row) {
   				// $user_type = $row->type;
   				$session_array = array(
   					'username'=>$username,
   					'user_id'=>$row->id,
   					'posisi_id'=>$row->posisi_id,
   					'time_start' => $row->time_start,
   					'time_end' => $row->time_end
   					);

   				$posisi_id = $row->posisi_id;

   				if (is_maintenance_on() && $row->posisi_id != 1) {
					redirect(base_url().'error/maintenance_mode');
				}else{
					if ($posisi_id == 1) {
						$this->session->sess_expiration = '14400';
					}
	   				$this->session->set_userdata('hameyean_logged_in',$session_array);
				}
   				
   			}

   			$data = array(
				'time' => time() );
			$this->session->set_userdata('user_session',$data);
   			return true;
   		}else{
   			$this->form_validation->set_message('check_database','Invalid username or password');
   			return false;
   		}
	}

	function logout(){
		$this->session->unset_userdata('hameyean_logged_in');
		redirect('home');
		// redirect('https://login.sistem.favourtdj.com/');
	}

	function maintenance_mode(){
		$this->session->unset_userdata('hameyean_logged_in');
		redirect('error/maintenance_mode');
	}
}

/* End of file home.php */
/* Location: ./application/controllers/home.php */
