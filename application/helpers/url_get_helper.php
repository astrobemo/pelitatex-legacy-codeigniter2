<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('is_get_url')) {
	function is_get_url($data)
	{
		$CI = &get_instance();
		$CI->load->model('common_model');

		// $link = base64_decode($data);
		$link = explode('/', base64_decode($data));

		$data = $CI->common_model->db_select_cond('pelita_menu.nd_menu_detail', 'controller', $link[0], " AND page_link = '" . $link[1] . "' ");
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


if (!function_exists('is_piutang_alert')) {
	function is_piutang_alert()
	{
		$CI = &get_instance();
		$CI->load->model('common_model');

		$count = 0;
		$data = $CI->common_model->rekap_piutang_now();
		foreach ($data as $row) {
			$now = time();
			$tgl = strtotime($row->tanggal);
			$datediff = $now - $tgl;
			$diff = floor($datediff / (60 * 60 * 24));

			if ($diff > $row->batas_piutang) {
				$count++;
			}
		}

		return $count;
	}
}

if (!function_exists('is_piutang_alert_barang')) {
	function is_piutang_alert_barang()
	{
		$CI = &get_instance();
		$CI->load->model('common_model');

		$count = 0;
		$data_list = $CI->common_model->db_select('nd_customer_piutang_setting_khusus');
		foreach ($data_list as $row) {
			$data = $CI->common_model->rekap_piutang_by_barang_now($row->barang_id, $row->customer_id);
			foreach ($data as $row2) {
				$now = time();
				$tgl = strtotime($row2->tanggal);
				$datediff = $now - $tgl;
				$diff = floor($datediff / (60 * 60 * 24));

				if ($diff > $row->batas_piutang) {
					$count++;
				}
			}
		}

		return $count;
	}
}

if (!function_exists('is_qty_general')) {
	function is_qty_general($number)
	{
		$CI = &get_instance();

		$result = number_format($number, '2', ',', '.');
		return str_replace(',00', '', $result);
	}
}

if (!function_exists('is_date_formatter')) {
	function is_date_formatter($date)
	{
		$CI = &get_instance();

		$result = implode('-', array_reverse(explode('/', $date)));
		return $result;
	}
}

if (!function_exists('is_datetime_formatter')) {
	function is_datetime_formatter($date)
	{
		$CI = &get_instance();

		$tgl = explode('-', $date);
		$result = implode('-', array_reverse(explode('/', trim($tgl[0])))) . ' ' . $tgl[1];
		return $result;
	}
}

if (!function_exists('is_date_monthname')) {
	function is_date_monthname($date)
	{
		$CI = &get_instance();

		$tgl = implode('-', array_reverse(explode('/', $date)));
		$result = date('F d, Y', strtotime($tgl));
		return $result;
	}
}

if (!function_exists('is_reverse_date_monthname')) {
	function is_reverse_date_monthname($date)
	{
		$CI = &get_instance();

		$result = date('Y-m-d', strtotime($date));
		return $result;
	}
}

if (!function_exists('is_reverse_date')) {
	function is_reverse_date($date)
	{
		$CI = &get_instance();

		$result = implode('/', array_reverse(explode('-', $date)));
		return $result;
	}
}

if (!function_exists('is_reverse_datetime')) {
	function is_reverse_datetime($date)
	{
		$CI = &get_instance();

		$tgl = explode(' ', $date);
		$result = implode('/', array_reverse(explode('-', $tgl[0]))) . ' ' . $tgl[1];
		return $result;
	}
}

if (!function_exists('is_reverse_datetime2')) {
	function is_reverse_datetime2($date)
	{
		$CI = &get_instance();

		$tgl = explode(' ', $date);
		$result = implode('/', array_reverse(explode('-', $tgl[0]))) . ' - ' . $tgl[1];
		return $result;
	}
}

if (!function_exists('is_number_format4')) {
	function is_number_format4($number)
	{
		$CI = &get_instance();

		$result = number_format($number, '4', '.', ',');
		return $result;
	}
}

if (!function_exists('is_sj_formatter')) {
	function is_sj_formatter($number, $date)
	{
		$CI = &get_instance();

		$romani = array(
			'1' => 'I',
			'2' => 'II',
			'3' => 'III',
			'4' => 'IV',
			'5' => 'V',
			'6' => 'VI',
			'7' => 'VII',
			'8' => 'VIII',
			'9' => 'IX',
			'10' => 'X',
			'11' => 'XI',
			'12' => 'XII',
		);

		$tgl = explode('-', $date);
		$tahun = date('Y', strtotime($date));
		if ($tgl[1] < 10) {
			$month = str_replace('0', '', $tgl[1]);
		} else {
			$month = $tgl[1];
		}
		return 'SJ/' . $number . '/' . $romani[$month] . '/' . $tahun;
	}
}

if (!function_exists('is_seting_link')) {
	function is_setting_link($string)
	{

		$result = rtrim(base64_encode($string), '=');
		return $result;
	}
}

if (!function_exists('is_get_username')) {
	function is_get_username($id)
	{
		$CI = &get_instance();

		$username = '';
		if ($id != '') {
			$result = $CI->common_model->db_select('nd_user where id=' . $id);
			foreach ($result as $row) {
				$username = $row->username;
			}
		}
		return $username;
	}
}

if (!function_exists('is_number_write')) {
	function is_number_write($angka)
	{

		$length = strlen($angka);
		//k untuk penyebut, $l untuk awal
		$k = 1;

		$bilangan[1] = 'Satu ';
		$bilangan[2] = 'Dua ';
		$bilangan[3] = 'Tiga ';
		$bilangan[4] = 'Empat ';
		$bilangan[5] = 'Lima ';
		$bilangan[6] = 'Enam ';
		$bilangan[7] = 'Tujuh ';
		$bilangan[8] = 'Delapan ';
		$bilangan[9] = 'Sembilan ';
		$bilangan[10] = 'Sepuluh ';
		$bilangan[0] = ' ';


		$number = '';

		$mod = $length % 3;
		$break = floor($length / 3);
		if ($mod != 0) {
			$number_list[0] = substr($angka, 0, $mod);
			for ($i = 0; $i < $break; $i++) {
				$j = $i + 1;
				$number_list[$j] = substr($angka, 3 * $i + $mod, 3);
			}

			$number_list = array_reverse($number_list);
		} else {
			$number_list;
			for ($i = 0; $i < $break; $i++) {
				$number_list[$i] = substr($angka, 3 * $i, 3);
			}

			$number_list = array_reverse($number_list);
		}

		$count = count($number_list) - 1;
		foreach ($number_list as $key => $value) {
			if ($mod != 0 && $key == $count) {
				$add_hundred = '';
			} else {
				$hundred = substr($value, 0, 1);
				$ratusan = (int)$hundred;
				if ($ratusan == 0) {
					$add_hundred = '';
				} elseif ($ratusan == 1) {
					$add_hundred = 'Seratus ';
				} else {
					$add_hundred = $bilangan[$hundred] . 'Ratus ';
				}
			}

			if ($mod != 0 && $key == $count) {
				$dozens = substr($value, 0, $mod);
			} else {
				$dozens = substr($value, 1, 2);
			}

			$puluhan = (int)$dozens;
			if ($puluhan == 0) {
				$add_dozens = '';
			} elseif ($puluhan > 0 && $puluhan <= 10) {
				$add_dozens = $bilangan[$puluhan];
			} elseif ($puluhan == 11) {
				$add_dozens = 'Sebelas ';
			} elseif ($puluhan > 11 && $puluhan <= 19) {
				$add_dozens = $bilangan[substr($puluhan, 1, 1)] . 'Belas ';
			} elseif ($puluhan >= 20) {
				$add_dozens = $bilangan[substr($puluhan, 0, 1)] . 'Puluh ';
				$add_dozens .= $bilangan[substr($puluhan, 1, 1)];
			}

			if ($key == 0) {
				$number = $add_hundred . $add_dozens . 'Rupiah ';
			} elseif ($key == 1) {
				if ($add_hundred . $add_dozens != '') {
					$number = $add_hundred . $add_dozens . 'Ribu ' . $number;
				}
			} elseif ($key == 2) {
				$number = $add_hundred . $add_dozens . 'Juta ' . $number;
			}
		}
		return $number;
	}
}

if (!function_exists('get_note_order')) {
	function get_note_order()
	{
		$CI = &get_instance();

		$result = $CI->common_model->get_note_order();

		return $result->result();
	}
}

if (!function_exists('get_limit_belanja_warning')) {
	function get_limit_belanja_warning()
	{
		$CI = &get_instance();

		$today = date('Y-m-d');
		$result = $CI->common_model->get_limit_customer_harian($today);
		$diff = null;

		foreach($result->result() as $row){
			$datetime1 = new DateTime($row->created_at);
			$datetime2 = new DateTime();

			$interval = $datetime1->diff($datetime2);
			$jam_beda = $interval->h;
			$jam_beda += $interval->i / 60;
			$diff = 0;
		}
		
		if ($result->num_rows() ==0) {
			$nData = array();
			$getData = $CI->common_model->get_limit_belanja_warning()->result();
			foreach ($getData as $row) {
				array_push($nData,array(
					'tanggal_warning' => $today,
					'toko_id' => 1,
					'customer_id' => $row->id,
					'nama_customer' => $row->nama,
					'tipe_company' => $row->tipe_company,
					'sisa_limit' => $row->sisa_limit,
					'user_id'=>is_user_id()
				));
			}

			if (count($nData) == 0) {
				array_push($nData,array(
					'tanggal_warning' => $today,
					'toko_id' => 1,
					'customer_id' => 0,
					'nama_customer' => '-',
					'tipe_company' => '-',
					'sisa_limit' => 0,
					'user_id'=>is_user_id()
				));
			}

			$CI->common_model->db_insert_batch("nd_warning_limit_belanja_harian",$nData);
			$result = $CI->common_model->get_limit_customer_harian($today);

		}

		return $result;
	}
}

if (!function_exists('get_limit_jatuh_tempo_warning')) {
	function get_limit_jatuh_tempo_warning()
	{
		$CI = &get_instance();
		
		$today = date('Y-m-d');
		$result = $CI->common_model->db_select_raw("nd_warning_jatuh_tempo_harian where tanggal_warning = '$today'");

		if ($result->num_rows() == 0) {
			$getData = $CI->common_model->cek_all_customer_lewat_tempo_kredit()->result();
			$nData = array();
			foreach ($getData as $row) {
				array_push($nData, array(
					'tanggal_warning' => $today,
					'toko_id' => 1,
					'customer_id' => $row->customer_id,
					'nama_customer' => $row->nama,
					'tipe_company' => $row->tipe_company,
					'amount' => $row->amount,
					'amount_data' => $row->amount_data,
					'intvl' => $row->intvl,
					'jatuh_tempo' => $row->jatuh_tempo,
					'no_faktur' => $row->no_faktur,
					'tanggal' => $row->tanggal,
					'user_id' => is_user_id()
					));
			}

			if (count($nData) == 0) {
				array_push($nData,array(
					'tanggal_warning' => $today,
					'toko_id' => 1,
					'customer_id' => 0,
					'nama_customer' => '-',
					'tipe_company' => '-',
					'amount' => 0,
					'amount_data' => 0,
					'intvl' => '',
					'jatuh_tempo' => '',
					'no_faktur' => '',
					'tanggal' => '',
					'user_id' => is_user_id()
				));
			}

			$CI->common_model->db_insert_batch("nd_warning_jatuh_tempo_harian",$nData);
			$result = $CI->common_model->db_select_raw("nd_warning_jatuh_tempo_harian where tanggal_warning = '$today'");

		}

		return $result;
	}
}

if (!function_exists('get_notifikasi_akunting')) {
	function get_notifikasi_akunting()
	{
		$CI = &get_instance();

		$result = $CI->common_model->get_notifikasi_akunting();

		return $result->result();
	}
}

if (!function_exists('get_note_order_row')) {
	function get_note_order_row()
	{
		$CI = &get_instance();

		$result = $CI->common_model->get_note_order_pending();

		return $result->num_rows();
	}
}

if (!function_exists('get_note_order_target')) {
	function get_note_order_target()
	{
		$CI = &get_instance();

		$result = $CI->common_model->get_note_order_target();

		return $result->result();
	}
}

if (!function_exists('get_note_order_reminder')) {
	function get_note_order_reminder()
	{
		$CI = &get_instance();

		$result = $CI->common_model->get_note_order_reminder();

		return $result->result();
		// return $result;
	}
}

if (!function_exists('get_piutang_warn')) {
	function get_piutang_warn()
	{
		$CI = &get_instance();
		$today = date('Y-m-d');
		$result = $CI->common_model->db_select_raw("nd_warning_piutang_harian where tanggal_warning = '$today'");

		if ($result->num_rows() == 0) {
			$getData = $CI->common_model->get_piutang_warn()->result();
			$nData = array();
			foreach ($getData as $row) {
				array_push($nData, array(
					'tanggal_warning' => $today,
					'toko_id' => $row->toko_id,
					'customer_id' => $row->customer_id,
					'nama_customer' => $row->nama_customer,
					'sisa_piutang' => $row->sisa_piutang,
					'counter_invoice' =>  $row->counter_invoice,
					'tanggal_start' => $row->tanggal_start,
					'tanggal_end' => $row->tanggal_end,
					'flag' =>  1, 
					'user_id' => is_user_id())
				);
			}

			if (count($nData) == 0) {
				array_push($nData, array(
					'tanggal_warning' => $today,
					'toko_id' => 1,
					'customer_id' => 0,
					'nama_customer' => '-',
					'sisa_piutang' => 0,
					'counter_invoice' =>  0,
					'tanggal_start' => null,
					'tanggal_end' => null,
					'flag' =>  1, 
					'user_id' => is_user_id())
				);
			}
			$CI->common_model->db_insert_batch("nd_warning_piutang_harian",$nData);
			$result = $CI->common_model->db_select_raw("nd_warning_piutang_harian where tanggal_warning = '$today'");

		}

		return $result;
		// return $result;
	}
}

if (!function_exists('get_jatuh_tempo')) {
	function get_jatuh_tempo($customer_id)
	{
		$CI = &get_instance();
		$get = $CI->common_model->db_select('nd_customer where id=' . $customer_id);
		$tempo_kredit = 0;
		foreach ($get as $row) {
			$tempo_kredit = $row->tempo_kredit;
		}

		$tempo_kredit = ($tempo_kredit != 0 && $tempo_kredit != null ? $tempo_kredit : 60);
		return $tempo_kredit;
		// return $result;
	}
}

if (!function_exists('get_color_list')) {
	function get_color_list($id)
	{
		$color_list = ['', '#FFEBEE', '#FAFAFA', '#E3F2FD', '#E0F2F1', '#FFF3E0', '#F3E5F5','#FCF6AC','#fff2e6'];

		if (!isset($color_list[$id])) {
			$color_list[$id] = "#fff";
		}
		return $color_list[$id];
		// return $result;
	}
}

if (!function_exists('get_supplier_color')) {
	function get_supplier_color($kode)
	{

		$color_list[11] = "#E3F2FD";
		$color_list[22] = "#FFFAAA";
		$color_list[40] = "#FFEBEE";
		$color_list[50] = "#FFEAD6";

		if (!isset($color_list[$kode])) {
			$color_list[$kode] = "";
		}

		return $color_list[$kode];
		// return $result;
	}
}

if (!function_exists('get_color_list_all')) {
	function get_color_list_all()
	{

		$color_list[11] = "#E3F2FD";
		$color_list[22] = "#FFFAAA";
		$color_list[40] = "#EBFFE8";

		return $color_list;
		// return $result;
	}
}

if (!function_exists('get_pre_faktur')) {
	function get_pre_faktur()
	{
		$CI = &get_instance();

		$pre_faktur = '';
		$result = $CI->common_model->db_select('nd_toko');
		foreach ($result as $row) {
			$pre_faktur = $row->pre_faktur;
		}
		return "'" . $pre_faktur . "'";
		// return $result;
	}
}

if (!function_exists('get_toko_obj')) {
	function get_toko_obj()
	{
		$CI = &get_instance();

		$pre_faktur = '';
		$result = $CI->common_model->db_select('nd_toko');
		// foreach ($result as $row) {
		// 	$toko_data = $row;
		// }
		return $result;
		// return $result;
	}
}

if (!function_exists('get_supplier_obj')) {
	function get_subpplier_obj()
	{
		$CI = &get_instance();

		$pre_faktur = '';
		$result = $CI->common_model->db_select('nd_supplier');
		foreach ($result as $row) {
			$supplier_data = $row;
		}
		return $supplier_data;
		// return $result;
	}
}

if (!function_exists('get_default_printer')) {
	function get_default_printer()
	{
		$CI = &get_instance();

		$default_printer = '';
		$result = $CI->common_model->db_select('nd_user where id=' . is_user_id());
		foreach ($result as $row) {
			$printer_list_id = $row->printer_list_id;
		}
		return ($printer_list_id != '' ? $printer_list_id : 1);
		// return $result;
	}
}

if (!function_exists('get_unfinished_invoice')) {
	function get_unfinished_invoice()
	{
		$CI = &get_instance();

		$default_printer = '';
		$result = $CI->common_model->get_unfinished_invoice(date('Y-m-d H:i:s', strtotime('+10 hours')));
		// foreach ($result as $row) {
		// 	$printer_list_id = $row->printer_list_id;
		// }
		// return ($printer_list_id != '' ? $printer_list_id : 1);
		return $result;
	}
}

if (!function_exists('get_unfinished_posisi_barang')) {
	function get_unfinished_posisi_barang()
	{
		$CI = &get_instance();

		$default_printer = '';
		$result = $CI->common_model->get_unfinished_posisi_barang(date('Y-m-d'));
		// foreach ($result as $row) {
		// 	$printer_list_id = $row->printer_list_id;
		// }
		// return ($printer_list_id != '' ? $printer_list_id : 1);
		return $result;
	}
}

if (!function_exists('get_warna_all')) {
	function get_warna_all()
	{
		$CI = &get_instance();

		$result = $CI->common_model->db_select('nd_warna');
		// foreach ($result as $row) {
		// 	$printer_list_id = $row->printer_list_id;
		// }
		// return ($printer_list_id != '' ? $printer_list_id : 1);
		return $result;
	}
}


if (!function_exists('set_npwp_char')) {
	function set_npwp_char($npwp)
	{

		if (strpos($npwp, '-') !== false) {
			return $npwp;
		} else {
			return substr($npwp, 0, 2) . '.'
				. substr($npwp, 2, 3) . '.'
				. substr($npwp, 5, 3) . '.'
				. substr($npwp, 8, 1) . '-'
				. substr($npwp, 9, 3) . '.'
				. substr($npwp, 12, 3);
		}
	}
}

if (!function_exists('set_number_format')) {
	function set_number_format($number)
	{

		if ($number != '') {
			return str_replace(',00', '', number_format($number, '2', ',', '.'));
		} else {
			return $number;
		}
	}
}

if (!function_exists('get_ppn_now')) {
	function get_ppn_now($tanggal)
	{
		$CI = &get_instance();
		$CI->load->model('common_model');

		$result = $CI->common_model->db_select("nd_ppn WHERE tanggal <= '$tanggal' ORDER BY tanggal desc LIMIT 1");
		$ppn_now = '';
		foreach ($result as $row) {
			$ppn_now = $row->ppn;
		}
		return $ppn_now;
	}
}