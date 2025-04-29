<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('is_maintenance_on')){
	function is_maintenance_on(){
		$CI =& get_instance();

		$status = 0;
		$result = $CI->common_model->db_select('nd_maintenance_list order by id desc limit 1');
		foreach ($result as $row) {
			$status = $row->status;
		}

		return $status;
	}
}


if ( ! function_exists('is_logged_in')){
	function is_logged_in(){
		$CI =& get_instance();

		$is_logged_in = $CI->session->userdata('hameyean_logged_in');
		if(!isset($is_logged_in) || $is_logged_in != true){
			redirect('home');
		}
	}
}

if ( ! function_exists('is_posisi_id')){
	function is_posisi_id(){
		$CI =& get_instance();
		$session_data = $CI->session->userdata('hameyean_logged_in');
		return $session_data['posisi_id'];
	}
}

if ( ! function_exists('is_username')){
	function is_username(){
		$CI =& get_instance();
		$session_data = $CI->session->userdata('hameyean_logged_in');
		return $session_data['username'];
	}
}

if ( ! function_exists('is_user_id')){
	function is_user_id(){
		$CI =& get_instance();
		$session_data = $CI->session->userdata('hameyean_logged_in');
		return $session_data['user_id'];
	}
}

if ( ! function_exists('is_user_time')){
	function is_user_time(){
		$CI =& get_instance();
		$session_data = $CI->session->userdata('hameyean_logged_in');
		$time_start = $session_data['time_start'];
		$time_end = $session_data['time_end'];
		if ($time_start <= date('H:i:s') && $time_end >= date('H:i:s')) {
			return true;
		}else{
			return false;
		}
		// return $session_data['user_id'];
	}
}

if ( ! function_exists('is_user_session')){
	function is_user_session(){
		$CI =& get_instance();
		if ($CI->session->userdata('user_session') != null ) {
			$data_session = $CI->session->userdata('user_session');
			$time = $data_session['time'];
			if (time() - $time > 1800 ) {
				$result = false;
			}else{
				$data = array(
					'time' => time() );
				$CI->session->set_userdata('user_session',$data);
				$result = true;
			}
		}else{
			$result = false;
		}

		return $result;
		// return $session_data['user_id'];
	}
}

if ( ! function_exists('is_user_menu')){
	function is_user_menu($posisi_id){
		$CI =& get_instance();
		$CI->load->model('common_model');
		
		$session_data = $CI->session->userdata('hameyean_logged_in');
		$menu = $CI->common_model->db_select_cond('pelita_menu.nd_menu_posisi','posisi_id', $posisi_id,'');
		$menu_list = "";
		$menu_detail = "";
		foreach ($menu as $row) {
			$menu_list = explode('??', $row->menu_id);
			$menu_detail = explode("??", $row->menu_detail_id);
		}
		
		$result['menu_list'] = $CI->common_model->db_select_array('pelita_menu.nd_menu', 'id', $menu_list,'urutan');
		$result['menu_list_detail'] = $CI->common_model->db_select_array('pelita_menu.nd_menu_detail','id',$menu_detail, 'urutan');
		foreach ($result['menu_list_detail'] as $row) {
			if ($row->level == 1 || $row->level == 3) {
				$result['menu_'.$row->menu_id]['menu_level_1'][$row->id] = $row;
			}else if ($row->level == 2) {
				if ($row->parent_id != 0) {
					$result['menu_'.$row->menu_id]['menu_level_2'][$row->parent_id][$row->id] = $row;
				}
			}
		}
		return $result;
	}
}