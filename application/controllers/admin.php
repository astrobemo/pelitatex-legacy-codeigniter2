<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends CI_Controller {

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
		redirect('admin/dashboard');
		$res = $this->common_model->db_free_query_superadmin("CALL get_stock('2021-01-05 23:59:59')");
		print_r($res->result());
		// $this->common_model->db_free_query_superadmin()
		// $this->dashboard();
		// echo 'admin';
	}

	function test_print_litte()
	{
		$tmpdir = sys_get_temp_dir();   # ambil direktori temporary untuk simpan file.
		$file =  tempnam($tmpdir, 'ctk');  # nama file temporary yang akan dicetak
		$handle = fopen($file, 'w');
		$condensed = Chr(27) . Chr(33) . Chr(4);
		$bold1 = Chr(27) . Chr(69);
		$bold0 = Chr(27) . Chr(70);
		$initialized = chr(27).chr(64);
		$condensed1 = chr(15);
		$condensed0 = chr(18);
		$Data  = $initialized;
		$Data .= $condensed1;
		$Data .= "==========================\n";
		$Data .= "|     ".$bold1."OFIDZ MAJEZTY".$bold0."      |\n";
		$Data .= "==========================\n";
		$Data .= "Ofidz Majezty is here\n";
		$Data .= "We Love PHP Indonesia\n";
		$Data .= "We Love PHP Indonesia\n";
		$Data .= "We Love PHP Indonesia\n";
		$Data .= "We Love PHP Indonesia\n";
		$Data .= "We Love PHP Indonesia\n";
		$Data .= "--------------------------\n";
		fwrite($handle, $Data);
		fclose($handle);
		copy($file, "../xprinter");  # Lakukan cetak
		unlink($file);
	}

	function dashboard()
	{
		if (is_posisi_id() == 1) {
			// $content = 'admin/dashboard_testing';
			$content = 'admin/dashboard_staff';
		}elseif (is_posisi_id() < 3) {
			$content = 'admin/dashboard_spv';
		}else{
			$content = 'admin/dashboard_staff';
		}
		$data = array(
			'content' => $content,
			'breadcrumb_title' => 'Dashboard',
			'breadcrumb_small' => 'dashboard',
			'nama_menu' => 'menu_dashboard',
			'nama_submenu' => '',
			'printer_list' => $this->common_model->db_select('nd_printer_list'),
			'common_data'=> $this->data );
		$data['notifikasi_akunting'] = $this->admin_model->get_notifikasi_akunting_report();
		$data['notifikasi_faktur_kosong'] = $this->admin_model->get_notifikasi_faktur_kosong();
		// $data['recap_pembelian_bulanan'] = $this->admin_model->recap_pembelian_bulanan(date('m'), date('Y'));
		// $data['recap_penjualan_bulanan'] = $this->admin_model->recap_penjualan_bulanan(date('m'), date('Y'));

		$data['recap_pembelian_bulanan'] = array();
		$data['recap_penjualan_bulanan'] = array();
		$this->load->view('admin/template', $data);
		if (is_posisi_id() == 1) {
			$this->output->enable_profiler(TRUE);
		}
	}

	function is_customer_limit(){
		$customer_id = $this->input->post('customer_id');
		$data = $this->common_model->is_customer_limit($customer_id);
		echo json_encode($data);
	}

	function setting_link($string){
		return rtrim(base64_encode($string),'=');
	}


//====================================cek barang harga===========================================
	
	function cek_harga_barang(){
		$barang_id = $this->input->post('barang_id');
		$customer_id = $this->input->post('customer_id');
		$limit = ($this->input->post('limit') == '' ? '3': $this->input->post('limit') ) ;

		$cond = ( $customer_id != '' ? 'WHERE customer_id = '.$customer_id : '' );

		$get = $this->common_model->cek_harga_penjualan_barang($barang_id, $cond, $limit);
		echo json_encode($get);		
	}

//====================================cek lewat kredit===========================================


	function cek_customer_lewat_tempo_kredit(){
		$customer_id = $this->input->post('customer_id');

		$tempo_kredit = 30;
		$warning_kredit = 5;
		foreach ($this->common_model->db_select("nd_customer where id=".$customer_id) as $row) {
			if ($row->tempo_kredit > 0) {
				$tempo_kredit = $row->tempo_kredit;
			}

			if ($warning_kredit > 0) {
				$warning_kredit = $row->warning_kredit;
			}
		}

		// echo $tempo_kredit + $warning_kredit; 
		if ($tempo_kredit + $warning_kredit > 0) {
			// echo 'ini';
			$get = $this->common_model->cek_customer_lewat_tempo_kredit($customer_id, $tempo_kredit+$warning_kredit  ,$tempo_kredit);
			$get2 = $this->common_model->is_customer_limit($customer_id);
			
			$sisa = '';
			foreach ($get2 as $row) {
				$limit_set = ($row->limit_warning_type==1 ? $row->limit_amount*($row->limit_warning_amount == 0 ? 90 : $row->limit_warning_amount )/100 : $row->limit_warning_amount ); 
				$sisa = ($row->limit_amount == '' || $row->limit_warning_amount == 0 ? '-' :  $limit_set - $row->sisa_piutang);
			}

			echo json_encode($get->result());
			if (is_posisi_id()==1) {
				// echo $customer_id.' - '. ($tempo_kredit+$warning_kredit) .' - '.$tempo_kredit;
				// echo json_encode($sisa);
				# code...
			}
		}else{
			echo "none";
		}

	}


//====================================nota order===========================================

	function note_order_insert(){
		$ini = $this->input;
		$link = $ini->post('link');
		$id = $this->input->post('id');
		$tanggal_target = $ini->post('tanggal_target');
		if ($tanggal_target == '') {
			$tanggal_target = null;
		}else{
			$tanggal_target = is_date_formatter($ini->post('tanggal_target'));
		}

		$data = array(
			'tanggal_note_order' => is_datetime_formatter($ini->post('tanggal_note_order')),
			'tanggal_target' => $tanggal_target,
			'tipe_customer' => $ini->post('tipe_customer'),
			'customer_id' => ($ini->post('customer_id') == '' ? null : $ini->post('customer_id')),
			'nama_customer' => ($ini->post('nama_customer') == '' ? null : $ini->post('nama_customer')),
			'contact_info' => ($ini->post('contact_info') == '' ? null : $ini->post('contact_info'))
			 );

		if ($id == '') {
			$result_id = $this->common_model->db_insert("nd_note_order", $data);

			$data_detail = array(
				'note_order_id' => $result_id,
				'tipe_barang' => $ini->post('tipe_barang'),
				'barang_id' => $ini->post('barang_id'),
				'nama_barang' => $ini->post('nama_barang'),
				'warna_id' => $ini->post('warna_id'),
				'nama_warna' => $ini->post('nama_warna'),
				'roll' => ($ini->post('roll') ==''? 1 :$ini->post('roll')),
				'qty' => str_replace('.', '', $ini->post('qty')),
				'harga' => str_replace('.', '', $ini->post('harga')),
				 );

			$this->common_model->db_insert('nd_note_order_detail',$data_detail);

		}else{
			$this->common_model->db_update("nd_note_order", $data,'id', $id);
		}


		
		redirect($link);
	}

	function note_order_detail_insert(){
		$ini = $this->input;
		$link = $ini->post('link');
		$id = $this->input->post('note_order_detail_id');
		
		$data = array(
			'note_order_id' => $ini->post('note_order_id'),
			'tipe_barang' => $ini->post('tipe_barang'),
			'barang_id' => $ini->post('barang_id'),
			'nama_barang' => $ini->post('nama_barang'),
			'warna_id' => $ini->post('warna_id'),
			'nama_warna' => $ini->post('nama_warna'),
			'roll' => $ini->post('roll'),
			'qty' => str_replace('.', '', $ini->post('qty')),
			'harga' => str_replace('.', '', $ini->post('harga')),
			);

		// print_r($data);
		if ($id == '') {
			$result_id = $this->common_model->db_insert("nd_note_order_detail", $data);
		}else{
			$this->common_model->db_update("nd_note_order_detail", $data,'id', $id);
		}
		
		redirect($link);
	}

	function note_order_status_update(){
		$id = $this->input->get('id');
		$status = $this->input->get('status');
		if ($status == 0) {
			$done_by = null;
			$done_time = null;
		}else{	
			$done_by = is_user_id();
			$done_time = date("Y-m-d H:i:s");
		}

		$data = array(
			'status' => $status,
			'done_by' => $done_by,
			'done_time' => $done_time
			);
		// print_r($data);
		$this->common_model->db_update('nd_note_order_detail',$data,'id',$id);
		redirect('admin/dashboard');

	}

	function set_reminder(){

		$reminder = $this->input->get('reminder');

		if ($reminder != '') {
			$data = array(
				'note_order_id' => $this->input->get('note_order_id') ,
				'reminder' => is_datetime_formatter($reminder),
				'user_id' => is_user_id()
				);

			$this->common_model->db_insert('nd_reminder', $data);
		}

		redirect('admin/dashboard');
	}

	function reminder_remove(){
		$reminder_id = $this->input->post('reminder_id');
		$data = array(
			'status_on' => 0 );
		$this->common_model->db_update('nd_reminder', $data, 'id', $reminder_id);
		echo 'OK';
	}

	function note_order_item_remove(){
		$note_order_detail_id = $this->input->post('note_order_detail_id');
		$this->common_model->db_delete('note_order_detail','id',$note_order_detail_id);
		echo "OK";

	}


//=======================================notifikasi akunting=================================

	function notifikasi_akunting_insert(){
		
		$link = $this->input->post('link');
		$data = array(
			'customer_id' => $this->input->post('customer_id') ,
			'amount' => str_replace('.', '', $this->input->post('amount')),
			'keterangan' => $this->input->post('keterangan'),
			'created' => date('Y-m-d H:i:s')
			 );

		// print_r($data);
		$this->common_model->db_insert("nd_notifikasi_akunting", $data);
		redirect($link);

	}

	function dismiss_notifikasi_akunting(){
		$id = $this->input->post('notifikasi_akunting_id');
		$data = array(
			'read_by' => is_user_id() ,
			'read_time' => date('Y-m-d H:i:s')
			 );

		$this->common_model->db_update('nd_notifikasi_akunting',$data,'id',$id);
		echo 'OK';
	}

//======================================dashboard===========================================

	function get_penjualan_per_barang_bulan(){

		$barang_id = $this->input->get('barang_id');
		$warna_id = $this->input->get('warna_id');
		$cond_warna = ($warna_id != '' && $warna_id != 0 ? " AND warna_id=".$warna_id : '');
		$tanggal_awal = is_date_formatter($this->input->get("tanggal_awal"));
		$tanggal_akhir = is_date_formatter($this->input->get("tanggal_akhir"));
		$recap_list = $this->admin_model->get_list_penjualan_barang_by_date($barang_id,$cond_warna,$tanggal_awal,$tanggal_akhir);
		echo json_encode($recap_list);
	}

	function get_penjualan_bulan(){
		$tahun = date('Y');
		$tanggal_start = is_date_formatter($this->input->post('tanggal_start'));
		$tanggal_end = is_date_formatter($this->input->post('tanggal_end'));

		if ($tanggal_start == '') {
			$tanggal_start = date("Y-m-01");
			$tanggal_end = date("Y-m-t");
		}
		if ($this->input->get('tahun') != '') {
			$tahun = $this->input->get('tahun');
		}
		$recap_list = $this->admin_model->get_list_penjualan_by_date($tanggal_start, $tanggal_end);
		echo json_encode($recap_list);
	}

	function get_penjualan_pembelian_tahun(){
		$tahun = date('Y');
		if ($this->input->get('tahun') != '') {
			$tahun = $this->input->get('tahun');
		}
		$recap_list = $this->admin_model->get_list_penjualan_pembelian_tahunan($tahun.'-01-01', $tahun.'-12-31');

		echo json_encode($recap_list);
	}

	function get_barang_jual_terbanyak(){
		$tahun = date('Y');
		if ($this->input->get('tahun') != '') {
			$tahun = $this->input->get('tahun');
		}
		$recap_list = $this->admin_model->get_barang_jual_terbanyak($tahun);

		echo json_encode($recap_list);
	}

	function get_barang_jual_terbanyak_get(){
		$tahun = date('Y');
		if ($this->input->get('tahun') != '') {
			$tahun = $this->input->get('tahun');
		}
		$recap_list = $this->admin_model->get_barang_jual_terbanyak_all($tahun);
		$list= array();
		$idx = 1;
		foreach ($recap_list as $row) {
			array_push($list, '["'.$idx.'","'.addslashes($row->barang)	.'","'.number_format((float)$row->qty,"0",",",".").'","'.$row->jml_transaksi.'","'.$row->barang_id.'"]');
			$idx++;
		}
		echo '{"data":['.implode(",", $list)."]}";

	}


	function get_customer_beli_terbanyak(){
		$tahun = date('Y'); $tipe =1;
		if ($this->input->post('tahun') != '') {
			$tahun = $this->input->post('tahun');
			$tipe = $this->input->post('tipe');
		}
		if ($tipe == 1) {
			$recap_list = $this->admin_model->get_customer_beli_terbanyak($tahun);
		}else{
			$recap_list = $this->admin_model->get_customer_beli_terbanyak_all($tahun);
		}

		echo json_encode($recap_list);
	}

	function get_customer_beli_terbanyak_get(){
		$tahun = date('Y'); $tipe =2;
		if ($this->input->get('tahun') != '') {
			$tahun = $this->input->get('tahun');
			$tipe = $this->input->get('tipe');
		}

		$recap_list = $this->admin_model->get_customer_beli_terbanyak_all($tahun);
		$list= array();
		foreach ($recap_list as $row) {
			array_push($list, '["'.$row->urutan.'","'.$row->nama_customer.'","'.number_format((float)$row->amount,"0",",",'.').'","'.$row->jml_transaksi.'","'.$row->customer_id.'"]');
		}
		echo '{"data":['.implode(",", $list)."]}";
	}


	function get_barang_jual_warna_terbanyak(){
		$tahun = date('Y');
		if ($this->input->get('tahun') != '') {
			$tahun = $this->input->get('tahun');
		}
		$recap_list = $this->admin_model->get_barang_jual_warna_terbanyak($tahun);

		echo json_encode($recap_list);
	}

	function get_barang_jual_warna_terbanyak_get(){
		$tahun = date('Y');
		if ($this->input->get('tahun') != '') {
			$tahun = $this->input->get('tahun');
		}
		$recap_list = $this->admin_model->get_barang_jual_warna_terbanyak_all($tahun);

		$list= array();
		$idx = 1;
		foreach ($recap_list as $row) {
			array_push($list, '["'.$idx.'","'.$row->barang.'","'.number_format((float)$row->qty,"0",",",".").'","'.$row->jml_transaksi.'","'.$row->warna_id.'"]');
			$idx++;
		}
		echo '{"data":['.implode(",", $list)."]}";

	}

	function get_barang_warna_jual_terbanyak(){
		$tahun = date('Y');
		if ($this->input->get('tahun') != '') {
			$tahun = $this->input->get('tahun');
		}
		$recap_list = $this->admin_model->get_barang_warna_jual_terbanyak($tahun);

		echo json_encode($recap_list);
	}

	function get_barang_warna_jual_terbanyak_get(){
		$tahun = date('Y');
		if ($this->input->get('tahun') != '') {
			$tahun = $this->input->get('tahun');
		}
		$recap_list = $this->admin_model->get_barang_warna_jual_terbanyak_all($tahun);

		$list= array();
		$idx = 1;
		foreach ($recap_list as $row) {
			array_push($list, '["'.$idx.'","'.addslashes($row->barang).'","'.number_format((float)$row->qty,"0",",",".").'","'.$row->jml_transaksi.'","'.$row->barang_data.'"]');
			$idx++;
		}
		echo '{"data":['.implode(",", $list)."]}";

	}

	function get_non_customer_totalbeli(){
		$tahun = $this->input->post('tahun');
		$data = $this->admin_model->get_noncustomer_totalbeli($tahun);
		echo json_encode($data);
	}

//=========================get detail tambahan=======================================

	function get_warna_terbanyak_by_barang(){
		$tahun = date('Y');
		if ($this->input->get('tahun') != '') {
			$tahun = $this->input->get('tahun');
		}

		$barang_id = $this->input->get('barang_id');
		$recap_list = $this->admin_model->get_warna_terbanyak_by_barang($tahun, $barang_id);

		$list= array();
		$idx = 1;
		foreach ($recap_list as $row) {
			array_push($list, '["'.$idx.'","'.$row->barang.'","'.number_format((float)$row->qty,"0",",",".").'","'.$row->jml_transaksi.'","'.$row->warna_id.'" ]');
			$idx++;
		}
		echo '{"data":['.implode(",", $list)."]}";

	}

	function get_barang_terbanyak_by_warna(){
		$tahun = date('Y');
		if ($this->input->get('tahun') != '') {
			$tahun = $this->input->get('tahun');
		}

		$warna_id = $this->input->get('warna_id');
		$recap_list = $this->admin_model->get_barang_terbanyak_by_warna($tahun, $warna_id);

		$list= array();
		$idx = 1;
		foreach ($recap_list as $row) {
			array_push($list, '["'.$idx.'","'.$row->nama_barang.'","'.number_format((float)$row->qty,"0",",",".").'","'.$row->jml_transaksi.'","'.$row->barang_id.'" ]');
			$idx++;
		}
		echo '{"data":['.implode(",", $list)."]}";

	}

	function get_customer_terbanyak_by_barang_warna(){
		$tahun = $this->input->get('tahun');
		$barang_id = $this->input->get('barang_id');
		$warna_id = $this->input->get('warna_id');

		$recap_list = $this->admin_model->get_customer_terbanyak_by_barang_warna($tahun, $barang_id, $warna_id);
		$list= array();
		foreach ($recap_list as $row) {
			array_push($list, '["'.$row->urutan.'","'.$row->nama_customer.'","'.number_format((float)$row->amount,"0",",",'.').'","'.$row->jml_transaksi.'"]');
		}
		echo '{"data":['.implode(",", $list)."]}";
	}

//==========================change password==========================================

	function setting_change_password()
	{
		$menu = is_get_url($this->uri->segment(1)) ;

		$data = array(
			'content' =>'admin/setting/change_password' ,
			'breadcrumb_title' => 'Setting',
			'breadcrumb_small' => 'ubah password',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1], 
			'common_data'=> $this->data );


		if ($this->session->flashdata('update_password')) {
			$data['n8_message'] = 'Update password berhasil !!';
		}else{
			$data['n8_message'] = '';
		}		

		$this->load->view('admin/template',$data);
	}

	function update_password(){
		
		$data = array('password' => md5($this->input->post('password')) );
		$this->common_model->db_update('nd_user',$data,'id', is_user_id());
		$this->session->set_flashdata('update_password','Sukses');
		redirect(is_setting_link('admin/setting_change_password'));
	}

//==============================PIN======================================


	function change_pin()
	{
		$menu = is_get_url($this->uri->segment(1)) ;

		$data = array(
			'content' =>'admin/setting/change_pin' ,
			'breadcrumb_title' => 'Setting',
			'breadcrumb_small' => 'ubah PIN',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1], 
			'common_data'=> $this->data );
		if ($this->session->flashdata('change_code')) {
			$data['n8_message'] = 'Update password berhasil !!';
		}else{
			$data['n8_message'] = '';
		}

		$this->load->view('admin/template',$data);
	}

	function update_pin()
	{
		// $session_data = $this->session->userdata('logged_in');
		$user_id = is_user_id();
		$data = array(
			'PIN'=>$this->input->post('PIN'));
		
		$this->common_model->db_update('nd_user', $data,'id',$user_id);
		$session_array = array(
			'success'=>'OK');
		$this->session->set_flashdata('change_code',$session_array);
		redirect(is_setting_link('admin/change_pin'));
	}

//==============================PRINTER======================================


	function change_default_printer()
	{
		$menu = is_get_url($this->uri->segment(1)) ;

		$data = array(
			'content' =>'admin/setting/change_default_printer' ,
			'breadcrumb_title' => 'Setting',
			'breadcrumb_small' => 'ubah Default Printer',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1], 
			'common_data'=> $this->data );
		if ($this->session->flashdata('change_printer')) {
			$data['n8_message'] = 'Update default printer berhasil !!';
		}else{
			$data['n8_message'] = '';
		}

		$data['printer_list'] = $this->common_model->db_select('nd_printer_list');
		
		$this->load->view('admin/template',$data);
	}

	function user_printer_list_update()
	{
		// $session_data = $this->session->userdata('logged_in');
		$user_id = is_user_id();
		$data = array(
			'printer_list_id'=>$this->input->post('printer_list_id'));
		
		$this->common_model->db_update('nd_user', $data,'id',$user_id);
		$session_array = array(
			'success'=>'OK');
		$this->session->set_flashdata('change_printer',$session_array);
		redirect(is_setting_link('admin/change_default_printer'));
	}

//==============================MAINTENANCE======================================

	function maintenance_list()
	{
		$menu = is_get_url($this->uri->segment(1)) ;

		$data = array(
			'content' =>'admin/setting/maintenance_list' ,
			'breadcrumb_title' => 'Setting',
			'breadcrumb_small' => 'Maintenance List',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1], 
			'common_data'=> $this->data );

		$data['maintenance_list'] = $this->common_model->db_select('nd_maintenance_list');
		
		$this->load->view('admin/template',$data);
	}

	function maintenance_list_insert()
	{
		// $session_data = $this->session->userdata('logged_in');
		$data = array(
			'end_time' => null ,
			'user_id' => is_user_id() );
		$this->common_model->db_insert("nd_maintenance_list", $data);
		redirect(is_setting_link('admin/maintenance_list'));
	}

	function maintenance_off(){
		$id = $this->input->get('id');
		$data = array(
			'end_time' => date('Y-m-d H:i:s') ,
			'status' => 0 );
		$this->common_model->db_update("nd_maintenance_list", $data,'id', $id);
		redirect(is_setting_link('admin/maintenance_list'));	
	}




//==============================HEADER DATA======================================
	function get_piutang_warn()
	{
		echo json_encode(get_piutang_warn()->result());
	}

	function get_limit_warning(){
		$data = array();
		
		$lw = array();
		$jtw = array();
		
		$limit_warning = get_limit_belanja_warning();
		$limit_jtw = get_limit_jatuh_tempo_warning();
		
		$count_warning = $limit_warning->num_rows() + $limit_jtw->num_rows();
		
		foreach ($limit_warning->result() as $row) {
			if ($row->id != 0) {
				$lw['c-'.$row->id] = $row;
				$customer[$row->id] = true;
			}else{
				$count_warning--;
			}
		}

		foreach ($limit_jtw->result() as $row) {
			if ($row->customer_id != 0) {
				$jtw['c-'.$row->customer_id] = $row;
				if (isset($customer[$row->customer_id])) {
					$count_warning--;
				}
			}else{
				$count_warning--;
			}
		}
									
		$data['count_warning'] = $count_warning;
		$data['limit_warning'] = $lw;
		$data['limit_jtw'] = $jtw;
		

		
		echo json_encode($data);
	}

//==========================================================

	function uploadFile()
	{
		// echo base_url()."uploads/";
		if(!empty($_FILES)){

			$fileName = $_FILES['file']['name'];
			
			$config['upload_path'] = './uploads/';	
			$config['allowed_types'] = 'csv';
			$config['file_name'] = $fileName;
			// echo $fileName;
			$this->load->library('upload',$config);
			//$this->upload->initialize($config);
			if(!$this->upload->do_upload('file')){
				$error = array('eror' => $this->upload->display_errors());
				print_r($error);
			}else{
				$data = array('upload_data' => $this->upload->data() );
				// print_r($data);
				// echo $data['upload_data']['file_name'];
				redirect('admin/showFileMutasi?nama='.$data['upload_data']['file_name']);
			}


	
			// $uploadOk = 1;
			// $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
			// // Check if image file is a actual image or fake image
			// if(isset($_POST["submit"])) {
			// 	$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
			// 	if($check !== false) {
			// 		echo "File is an image - " . $check["mime"] . ".";
			// 		$uploadOk = 1;
			// 	} else {
			// 		echo "File is not an image.";
			// 		$uploadOk = 0;
			// 	}
			// }
		}else{
			echo 'kosoong ngagorolong';
		}
	}

	function showFile(){
		
		// $fileName = "Laporan_Faktur_Pajak_CV._PELITA_LESTARI_110122_.csv";
		$fileName = $this->input->get('nama');
		echo '<hr/>';
		$target_file = "./uploads/".$fileName;
		echo $target_file;
		
		// $file = fopen($target_file,"r");
		// print_r(fgetcsv($file,5000, ";"));

		$row = 1;
		$isTable = false;
		if (($handle = fopen($target_file, "r")) !== FALSE) {
			echo "<table>";
			while (($data = fgetcsv($handle, 5000, ";",",")) !== FALSE) {
				$num = count($data);
				// echo "<p> $num fields in line $row: <br /></p>\n";
				$row++;
				echo "<tr>";
				for ($c=0; $c < $num; $c++) {
					// if ($data[$c] != '') {
					// 	echo stripos($data[$c],'"');
					// 	echo $data[$c]  . "<br />\n";
					// }

					echo "<td>".$data[$c]."</td>";
				}
				echo "</tr>";
			}
			echo "</table>";
			fclose($handle);
		}
	}

	function showFileMutasi(){

		$fileName = "mutasi_perdesember2020.csv";
		
		// $fileName = "Laporan_Faktur_Pajak_CV._PELITA_LESTARI_110122_.csv";
		if ($this->input->get('nama') != '') {
			$fileName = $this->input->get('nama');
		}
		echo '<hr/>';
		$target_file = "./uploads/".$fileName;
		echo $target_file;
		
		// $file = fopen($target_file,"r");
		// print_r(fgetcsv($file,5000, ";"));

		$row = 1;
		$isTable = false;
		$dt_list = array();
		if (($handle = fopen($target_file, "r")) !== FALSE) {
			// echo "<table>";
			// while (($data = fgetcsv($handle, 5000, ";",",")) !== FALSE) {
			// 	$num = count($data);
			// 	// echo "<p> $num fields in line $row: <br /></p>\n";
			// 	$row++;
			// 	echo "<tr>";
			// 	for ($c=0; $c < $num; $c++) {
			// 		// if ($data[$c] != '') {
			// 		// 	echo stripos($data[$c],'"');
			// 		// 	echo $data[$c]  . "<br />\n";
			// 		// }

			// 		echo "<td>".$data[$c]."</td>";
			// 	}
			// 	echo "</tr>";
			// }
			// echo "</table>";
			// fclose($handle);
			
			echo "<table>";
			
			$idx = 0;
			$gudangList = [];
			while (($data = fgetcsv($handle, 5000, ";",",")) !== FALSE) {
				$num = count($data);
				// echo "<p> $num fields in line $row: <br /></p>\n";
				$row++;
				echo "<tr><td>$idx</td>";
				if ($idx > 0) {
					if (!isset($dt_list[$data[1]])) {
						$dt_list[$data[1]]['warna_id'] = array();
						$dt_list[$data[1]]['data'] = array();
						$dt_list[$data[1]]['tanggal'] = array();
						$dt_list[$data[1]]['barang_id'] = $data[1];
						$dt_list[$data[1]]['nama'] = $data[0];
					}

					array_push($dt_list[$data[1]]['warna_id'], $data[2]);
					array_push($dt_list[$data[1]]['tanggal'], $data[3]);
					$dt_qty = array();
					for ($m=0; $m < count($gudangList); $m++) {
						// if (!isset($dt_list[$data[1]]['data'][$gudangList[$m]])) {
						// 	$dt_list[$data[1]]['data'][$gudangList[$m]] = array();
						// }
						// array_push($dt_list[$data[1]]['data'][$gudangList[$m]] ,$data[4+$m]);
						$dt_qty[$gudangList[$m]] = $data[4+$m];
					}
					array_push($dt_list[$data[1]]['data'], implode('||',$dt_qty));

					?>
					<td><?=$data[0]?></td>
					<td><?=$data[1]?></td>
					<td><?=$data[2]?></td>
					<td><?=$data[3]?></td>
					<?for ($m=0; $m < count($gudangList); $m++) {?> 
						<td><?=$data[4+$m]?></td>
					<?}?>
				<?}else{
					for ($c=0; $c < $num; $c++) {
						if ($c >= 4) {
							array_push($gudangList,$data[$c]);
						}
					}
				}
					// if ($data[$c] != '') {
					// 	echo stripos($data[$c],'"');
					// 	echo $data[$c]  . "<br />\n";
					// }

					// echo "<td>".$data[$c]."</td>";
				echo "</tr>";
				$idx++;
			}
			echo "</table>";
			fclose($handle);


			echo "<form action='".base_url()."admin/mutasi_gudang_manual_insert'  method='POST'>";
			echo "<input type='submit' value='submit'>";			
			for ($m=0; $m < count($gudangList); $m++) {?> 
				<input style="width:50px; text-align:center" name="gudangList[]" value="<?=$gudangList[$m];?>">
			<?}
				echo '<hr/>';

			foreach ($dt_list as $key => $value) {?>
				<input type="text" name="barang_id[<?=$value['barang_id']?>]" value="<?=$value['barang_id'];?>">
				<input type="text" name="warna_id[<?=$value['barang_id']?>]" value="<?=implode(",", $value['warna_id']) ;?>">
				<input type="text" name="tanggal[<?=$value['barang_id']?>]" value="<?=implode(",", $value['tanggal']) ;?>">
				<input type="text" name="data[<?=$value['barang_id']?>]" value="<?=implode(",", $value['data']) ;?>">
				
				<hr/>
			<?}
			echo "</form>";

		}
	}

	function mutasi_gudang_manual_insert()
	{
		$gudang = $this->input->post('gudangList');
		$ndata = array();
		foreach ($this->input->post('barang_id') as $key => $value) {
			$idx = 0;
			$bid = $value;
			$wlist = explode(",", $this->input->post('warna_id')[$bid]);
			$tanggal = explode(",", $this->input->post('tanggal')[$bid]);
			$qty_gudang = explode(",", $this->input->post('data')[$bid]);
			foreach ($wlist as $k2 => $v2) {
				$glist = explode('||', $qty_gudang[$k2]);
				foreach ($glist as $k3 => $v3) {
					$ndata[$idx] = array(
						'tanggal' => $tanggal[$k2],
						'barang_id' => $bid,
						'warna_id' => $v2,
						'gudang_id' => $gudang[$k3],
						'qty'=> $v3
					);
					$idx++;
				}
			}

			print_r($ndata);
			echo '<hr/>';
		}

	}

}

/* End of file admin.php */
/* Location: ./application/controllers/admin.php */