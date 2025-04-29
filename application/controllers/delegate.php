<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Delegate extends CI_Controller {

	private $data = [];

	function __construct() 
	{
		parent:: __construct();
		
		is_logged_in();
		if(is_username() == ''){
			redirect('home');
		}
		$this->data['username'] = is_username();
		$this->data['user_menu_list'] = is_user_menu(is_posisi_id());
		//======================data aktif section===========================
		

		$this->supplier_list_aktif = $this->common_model->db_select('nd_supplier where status_aktif = 1 ORDER BY id asc');
		$this->customer_list_aktif = $this->common_model->db_select('nd_customer where status_aktif = 1 ORDER BY nama asc');
		$this->toko_list_aktif = $this->common_model->db_select('nd_toko where status_aktif = 1');
		$this->gudang_list_aktif = $this->common_model->db_select('nd_gudang where status_aktif = 1 ORDER BY urutan asc');

		$this->warna_list_aktif = $this->common_model->db_select('nd_warna where status_aktif = 1 ORDER BY warna_jual asc');
        $this->barang_list_aktif = $this->common_model->get_barang_list_aktif();
		$this->barang_list_aktif_beli = $this->common_model->get_barang_list_aktif_beli();
        $this->satuan_list_aktif = $this->common_model->db_select('nd_satuan where status_aktif = 1');

        $this->po_pembelian_batch_aktif = $this->common_model->get_po_pembelian_batch_aktif();
        $this->pre_faktur = get_pre_faktur();
		
	}

	function index(){
		redirect('admin/dashboard');
	}

//==================================================================================

	function posisi_list(){

		$menu = is_get_url($this->uri->segment(1)) ;

		$data = array(
			'content' =>'admin/delegate/posisi_list' ,
			'breadcrumb_title' => 'Delegate',
			'breadcrumb_small' => 'atur akses level',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data'=> $this->data );

		$data['user_id'] = is_user_id();
		$data['posisi_list'] = $this->common_model->db_select('pelita_menu.nd_posisi');
		$this->load->view('admin/template',$data);
	}

	function menu_posisi_list_manage(){
		$posisi_id = $this->input->get('posisi_id');
		$data['posisi_id'] = $posisi_id;
		$data['menu_posisi_list'] = $this->common_model->db_select_cond('pelita_menu.nd_menu_posisi','posisi_id',$posisi_id, '');
		$data['menu_list'] = $this->common_model->db_select('pelita_menu.nd_menu');
		$data['menu_detail'] = $this->common_model->db_select('pelita_menu.nd_menu_detail');
		$this->load->view('admin/delegate/menu_posisi_list_manage',$data);	
	}

	function menu_posisi_update(){
		$posisi_id = $this->input->post('posisi_id');
		$result = $this->common_model->db_select_cond('pelita_menu.nd_menu_posisi','posisi_id',$posisi_id,'');
		$id = '';
		foreach ($result as $row) {
			$id = $row->id;
		}

		$data = array(
			'posisi_id' => $posisi_id , 
			'menu_id' => $this->input->post('menu_id') ,
			'menu_detail_id' => $this->input->post('menu_detail_id')
			);

		// print_r($data);
		// echo $posisi_id;

		if ($id == '') {
			$this->common_model->db_insert('pelita_menu.nd_menu_posisi',$data);
		}else{
			$this->common_model->db_update('pelita_menu.nd_menu_posisi',$data,'id', $id);
			// // echo 'update';
			// // print_r($data);
		}

		// print_r($this->input->post());
		// print_r($data);

		redirect(trim(base64_encode('delegate/posisi_list'),'='));
	}

	function posisi_list_insert(){
		$data = array(
			'name' => $this->input->post('name')
			);
		$this->common_model->db_insert('nd_posisi',$data);
		redirect(trim(base64_encode('delegate/posisi_list'),'='));
	}

//======================================menu list============================

	function menu_list(){
		$menu = is_get_url($this->uri->segment(1)) ;

		$data = array(
			'content' =>'admin/delegate/menu_list' ,
			'breadcrumb_title' => 'Delegate',
			'breadcrumb_small' => 'daftar menu',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data'=> $this->data );

		$data['user_id'] = is_user_id();
		$data['menu_list'] = $this->common_model->db_select('pelita_menu.nd_menu');
		$this->load->view('admin/template',$data);
	}

	function menu_insert(){
		$data = array(
			'nama_id' => $this->input->post('nama_id'),
			'text' => $this->input->post('text'),
			'icon_class' => $this->input->post('icon_class'),
			'urutan' => $this->input->post('urutan')
			);
		// print_r($data);
		$this->common_model->db_insert('pelita_menu.nd_menu',$data);
		redirect(trim(base64_encode('delegate/menu_list'),'='));
	}

	function menu_update(){
		$id = $this->input->post('menu_id');

		$data = array(
			'nama_id' => $this->input->post('nama_id'),
			'text' => $this->input->post('text'),
			'icon_class' => $this->input->post('icon_class'),
			'urutan' => $this->input->post('urutan')
			);
		$this->common_model->db_update('pelita_menu.nd_menu',$data, 'id', $id);
		redirect(trim(base64_encode('delegate/menu_list'),'='));
	}

	function menu_detail_list(){
		// $menu = is_get_url($this->uri->segment(1)) ;
		$menu_id = $this->input->get('menu_id');
		
		$data = array(
			'content' =>'admin/delegate/menu_detail_list' ,
			'breadcrumb_title' => 'Delegate',
			'breadcrumb_small' => 'daftar menu',
			'nama_menu' => 'menu_delegate',
			'nama_submenu' => 'menu_list',
			'common_data'=> $this->data,
			'menu_id' =>  $menu_id );

		$data['user_id'] = is_user_id();
		$data['controller_list'] = $this->common_model->db_select('pelita_menu.nd_controller');
		$data['menu_list_detail'] = $this->common_model->db_select_cond('pelita_menu.nd_menu_detail','menu_id', $menu_id, 'order by urutan asc');
		$data['menu_list_parent'] = $this->common_model->db_select_cond('pelita_menu.nd_menu_detail','menu_id', $menu_id, 'and level=3 order by urutan asc');
		$this->load->view('admin/template',$data);

	}

	function menu_detail_insert(){
		$menu_id = $this->input->post('menu_id');
		$data = array(
			'menu_id' => $menu_id,
			'controller' => $this->input->post('controller'),
			'page_link' => $this->input->post('page_link'),
			'text' => $this->input->post('text'),
			'urutan' => $this->input->post('urutan'),
			'level' => $this->input->post('level'),
			'parent_id' => ($this->input->post('parent_id') != '' ? $this->input->post('parent_id') : 0),
			'status_aktif' => $this->input->post('status_aktif')
			);
		// print_r($data);
		$this->common_model->db_insert('pelita_menu.nd_menu_detail',$data);
		redirect(is_setting_link('delegate/menu_detail_list')."?menu_id=".$menu_id);
		// return $data;
		// echo 'OK';
	}

	function menu_detail_update(){
		$id = $this->input->post('menu_detail_id');
		$menu_id = $this->input->post('menu_id');

		$data = array(
			'menu_id' => $menu_id,
			'controller' => $this->input->post('controller'),
			'page_link' => $this->input->post('page_link'),
			'text' => $this->input->post('text'),
			'urutan' => $this->input->post('urutan'),
			'level' => $this->input->post('level'),
			'parent_id' => ($this->input->post('parent_id') == '' ? null : $this->input->post('parent_id')) ,
			'status_aktif' => $this->input->post('status_aktif')
			);
		$this->common_model->db_update('pelita_menu.nd_menu_detail',$data, 'id', $id);
		redirect(is_setting_link('delegate/menu_detail_list')."?menu_id=".$menu_id);
	}

//============================================================================

//==================================menu sort======================================

	function menu_sort(){
		$menu = is_get_url($this->uri->segment(1)) ;

		$data = array(
			'content' =>'admin/delegate/menu_sort' ,
			'breadcrumb_title' => 'Delegate',
			'breadcrumb_small' => 'daftar menu',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data'=> $this->data );

		$data['user_id'] = is_user_id();
		$data['menu_list'] = $this->common_model->db_select('pelita_menu.nd_menu');
		foreach ($data['menu_list'] as $row) {
			$data['menu_list_detail'][$row->id] = $this->common_model->db_select('pelita_menu.nd_menu_detail where menu_id='.$row->id." AND status_aktif = 1 AND urutan != 99 ORDER BY urutan  ");
		}
		$this->load->view('admin/template',$data);
	}

	function menu_sort_update(){
		$data_break = (array)$this->input->post();
		// print_r($this->input->post());
		foreach ($data_break as $key => $value) {
			// $data[$i] = explode('_', $key);
			$i = 0;
			$menu_id = explode('_', $key);
			foreach ($value as $key2 => $value2) {
				$data[$i] = array(
					'urutan' => $key2 );
				$cond = array(
					'menu_id' => $menu_id[1] ,
					'id' => $value2 );

				// print_r($cond);
				// echo '<hr/>';
				$this->common_model->db_update_array('pelita_menu.nd_menu_detail',$data[$i], $cond);
				$i++;
			}

			// echo '<br>';
		}

		redirect(is_setting_link('delegate/menu_sort'));

		// print_r($data);
	}


}