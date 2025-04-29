<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

function ubah_nama_bulan($angka_bulan)
{
	$bulan = array('Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');

	return $bulan[$angka_bulan - 1];
}

function format_angka($angka)
{
	$decimal_flag = '.';
	$digit_flag = ',';

	$starter = explode($decimal_flag, number_format($angka, 2, $decimal_flag, $digit_flag));

	$awal = $starter[0];
	$akhir = $starter[1] == '00' ? '' : $decimal_flag . $starter[1];

	return $awal . $akhir;
}

function for_string($str, $max_char)
{
	$hasil = explode(' ', $str);

	$str_pitil = '';
	$str_has = '';
	$exit = 0;

	for ($i = 0; $i < count($hasil); $i++) {
		$str_has = $hasil[$i];

		if ($str_pitil == '') {
			$str_pitil = $str_has;
		} else {
			if (strlen($str_pitil) + strlen($str_has) > $max_char)
				$exit = 1;

			if ($exit == 0)
				$str_pitil = $str_pitil . ' ' . $str_has;
		}
	}

	return $str_pitil;
}

if (!function_exists('is_get_url_trans')) {
	function is_get_url_trans($data)
	{
		if(!isset($menu_id)){
			$menu_id = 0;
		}
		$CI = &get_instance();
		$CI->load->model('common_model');

		// $link = base64_decode($data);
		$link = explode('/', base64_decode($data));

		$link_controller = $link[0] . '/' . $link[1];
		$link_apps = $link[2];

		$data = $CI->common_model->db_select_cond('pelita_menu.nd_menu_detail', 'controller', $link_controller, " AND page_link = '" . $link_apps . "' ");

		foreach ($data as $row) {
			$menu_id = $row->menu_id;
		}

		$result = $CI->common_model->db_select_cond('pelita_menu.nd_menu', 'id', $menu_id, '');

		foreach ($result as $row) {
			$link[0] = $row->nama_id;
		}

		return $link;
	}
}

if (!function_exists('get_api_base_link')) {
	function get_api_base_link()
	{
		$CI = &get_instance();

		$result = $CI->common_model->db_select('nd_profile');

		foreach ($result as $row) {
			$hasil = $row->base_link;
		}

		return $hasil;
	}
}


if (!function_exists('get_api_base_code')) {
	function get_api_base_code()
	{
		$CI = &get_instance();

		$result = $CI->common_model->db_select('nd_profile');

		foreach ($result as $row) {
			$hasil = $row->base_code;
		}

		return $hasil;
	}
}

if (!function_exists('get_api_key')) {
	function get_api_key()
	{
		$CI = &get_instance();

		$result = $CI->common_model->db_select('nd_profile');

		foreach ($result as $row) {
			$hasil = $row->token;
		}

		return $hasil;
	}
}
