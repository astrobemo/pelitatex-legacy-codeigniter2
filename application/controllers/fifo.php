<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class FIFO extends CI_Controller {

	private $data = [];

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
		$this->load->model('transaction_model','tr_model',true);
		$this->load->model('report_model','rpt_model',true);
		$this->load->model('inventory_model','inv_model',true);
		
		//======================data aktif section===========================
		
        // $this->output->enable_profiler(TRUE);
        if (is_posisi_id() == 1) {
            // $this->output->enable_profiler(TRUE);
        }
        
    }

	function index(){
		redirect('admin');
	}

	function testing_fifo(){
		
		$barang_id = 2;
		$tanggal = date('Y-m-d');
		$tanggal_awal = '2018-01-01';
		$stok_opname_id = 0;
        if ($this->input->get('barang_id') ) {
        	$barang_id = $this->input->get('barang_id');
        }

	    $getOpname = $this->common_model->db_select("nd_stok_opname where tanggal <= '".$tanggal."' order by tanggal desc limit 1");
        foreach ($getOpname as $row) {
            $tanggal_awal = $row->tanggal;
            $stok_opname_id = $row->id;
        }

    	$cond_barang = "WHERE barang_id = ".$barang_id;
    	$batch_id_list = array();
    	$i = 0;
	    $data['data_set'] = $this->inv_model->get_stok_ppo('', $tanggal, $tanggal_awal, $stok_opname_id, $cond_barang);
	    foreach ($data['data_set'] as $row) {
	    	$batch_id = explode(',', $row->batch_id);
	    	foreach ($batch_id as $key => $value) {
	    		if ($value != 0) {
			    	array_push($batch_id_list, $value);
	    		}
	    	}
	    }
	    $batch_id_list = array_unique($batch_id_list);
	    // echo $stok_opname_id;
	    if ($stok_opname_id == 0) {
	    	$data['stok_awal'] = $this->inv_model->get_penyesuaian_stok_awal($barang_id);
	    }else{
	    	$data['stok_awal'] = $this->inv_model->get_stok_by_opname($stok_opname_id, $barang_id);
	    }
		$list_jual = $this->inv_model->get_penjualan_by_barang($barang_id, $tanggal_awal);
		foreach ($list_jual as $row) {
			$data['jual'][$row->warna_id][$row->penjualan_id] = $row;
		}
		if (count($batch_id_list) > 0) {
			$data['list_stok'] = $this->inv_model->stok_by_po(implode(',', $batch_id_list), $barang_id);
			$data['list_stok_by_tanggal'] = $this->inv_model->stok_by_po_by_tanggal(implode(',', $batch_id_list), $barang_id);
		}else{
			$data['list_stok'] = array();
			$data['list_stok_by_tanggal'] = array();
		}
    	$data['batch_for_pre_po'] = $this->inv_model->get_batch_for_ppo($barang_id);
    	// print_r($data['stok_awal']);
        $this->load->view('admin/fifo/testing_fifo',$data);


		$this->output->enable_profiler(TRUE);
	}

	function testing_fifo2(){
		
		$barang_id = 2;
		$tanggal = date('Y-m-d');
		$tanggal_awal = '2018-01-01';
		$ppo_lock_id = '';
		$stok_opname_id = 0;
		$view_type=1;
        if ($this->input->get('barang_id') ) {
        	$barang_id = $this->input->get('barang_id');
        }
        $data['barang_id'] = $barang_id;

        if ($this->input->get('tanggal') && $this->input->get('tanggal') != '') {
        	$tanggal = $this->input->get('tanggal');
        }

        if ($this->input->get('view_type') && $this->input->get('view_type') != '' ) {
        	$view_type = $this->input->get('view_type');
        }

        $data['view_type'] = $view_type;


        if ($this->input->get('ppo_lock_id') && $this->input->get('ppo_lock_id') != '') {
	    	$ppo_lock_id = $this->input->get('ppo_lock_id');
	    	$get = $this->common_model->db_select("nd_ppo_lock where id=".$ppo_lock_id);
	    	foreach ($get as $row) {
	    		$barang_id = $row->barang_id;
	    		$tanggal = $row->tanggal;
	    	}
	    	$data['ppo_lock_data'] = $get;
	    }else{
	    	$data['ppo_lock_data'] = array();
	    }

	    $data['ppo_lock_id'] = $ppo_lock_id;

	    $getOpname = $this->common_model->db_select("nd_stok_opname where tanggal <= '".$tanggal."' order by tanggal desc limit 1");
        foreach ($getOpname as $row) {
            $tanggal_awal = $row->tanggal;
            $stok_opname_id = $row->id;
        }

    	$cond_barang = "WHERE barang_id = ".$barang_id;
    	$batch_id_list = array();
    	$i = 0;
	    $data['data_set'] = $this->inv_model->get_stok_ppo($barang_id, $tanggal, $tanggal_awal, $stok_opname_id, $cond_barang, ($ppo_lock_id != ''? $ppo_lock_id : 0 ));
	    foreach ($data['data_set'] as $row) {
	    	$batch_id = explode(',', $row->batch_id);
	    	foreach ($batch_id as $key => $value) {
	    		if ($value != 0) {
			    	array_push($batch_id_list, $value);
	    		}
	    	}
	    }
	    $batch_id_list = array_unique($batch_id_list);
	    // echo $stok_opname_id;
	    if ($stok_opname_id == 0) {
	    	$data['stok_awal'] = $this->inv_model->get_penyesuaian_stok_awal($barang_id);
	    }else{
	    	$data['stok_awal'] = $this->inv_model->get_stok_by_opname($stok_opname_id, $barang_id);
	    }
		$list_jual = $this->inv_model->get_penjualan_by_barang($barang_id, $tanggal_awal, $tanggal);
		foreach ($list_jual as $row) {
			$data['jual'][$row->warna_id][$row->penjualan_id] = $row;
		}
		if (count($batch_id_list) > 0) {
			$data['list_stok'] = $this->inv_model->stok_by_po(implode(',', $batch_id_list), $barang_id, $tanggal);
			$data['list_stok_by_tanggal'] = $this->inv_model->stok_by_po_by_tanggal(implode(',', $batch_id_list), $barang_id, $tanggal);
		}else{
			$data['list_stok'] = array();
			$data['list_stok_by_tanggal'] = array();
		}
    	$data['batch_for_pre_po'] = $this->inv_model->get_batch_for_ppo($barang_id, $tanggal);
    	// print_r($data['stok_awal']);
    	$data['tanggal'] = $tanggal;
    	$data['barang'] = $this->common_model->db_select("nd_barang where id=".$barang_id);
        $this->load->view('admin/fifo/testing_fifo2',$data);
        // $data['content'] = 'admin/fifo/testing_fifo2';
		// $this->load->view('admin/template_no_sidebar',$data);
            // $this->output->enable_profiler(TRUE);


		// $this->output->enable_profiler(TRUE);
	}

	function testing_fifo3(){
        $this->load->view('admin/fifo/testing_fifo_3');

	}
}