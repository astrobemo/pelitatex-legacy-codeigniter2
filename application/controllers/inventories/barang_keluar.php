<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Barang_Keluar extends CI_Controller {
    function __construct() 
	{
		parent:: __construct();
		
		is_logged_in();
		if(is_username() == ''){
			redirect('home');
		}

		if (is_maintenance_on() && $row->posisi_id != 1) {
			redirect(base_url().'home/maintenance_mode');
		}


		$this->data['username'] = is_username();
		$this->data['user_menu_list'] = is_user_menu(is_posisi_id());
		$this->load->model('inventory_model','inv_model',true);
		
		//======================data aktif section===========================
		
		$this->supplier_list_aktif = $this->common_model->db_select('nd_supplier where status_aktif = 1');
		$this->customer_list_aktif = $this->common_model->db_select('nd_customer where status_aktif = 1');
		$this->toko_list_aktif = $this->common_model->db_select('nd_toko where status_aktif = 1');
		
		$this->gudang_list_aktif = $this->common_model->db_select('nd_gudang where status_aktif = 1  ORDER BY urutan asc');

		$this->warna_list_aktif = $this->common_model->db_select('nd_warna where status_aktif = 1 order by warna_jual asc');
		$this->barang_list_aktif = $this->common_model->get_barang_list_aktif();
		$this->barang_list_aktif_beli = $this->common_model->get_barang_list_aktif_beli();
		$this->satuan_list_aktif = $this->common_model->db_select('nd_satuan where status_aktif = 1');

		// $this->output->enable_profiler(TRUE);
	   	$this->pre_faktur = get_pre_faktur();
	}

    function index(){
        
    }
}

?>