<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Finance extends CI_Controller {

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
		$this->load->model('finance_model','fi_model',true);
		
		//======================data aktif section===========================
		
		$this->supplier_list_aktif = $this->common_model->db_select('nd_supplier where status_aktif = 1 ORDER BY nama asc');
		$this->customer_list_aktif = $this->common_model->db_select('nd_customer WHERE status_aktif = 1 ORDER BY nama asc ');
		$this->toko_list_aktif = $this->common_model->db_select('nd_toko where status_aktif = 1');
		$this->gudang_list_aktif = $this->common_model->db_select('nd_gudang where status_aktif = 1');

		$this->warna_list_aktif = $this->common_model->db_select('nd_warna where status_aktif = 1');
		$this->barang_list_aktif = $this->common_model->get_barang_list_aktif();
		$this->satuan_list_aktif = $this->common_model->db_select('nd_satuan where status_aktif = 1');
		
		$this->bank_list_aktif = $this->common_model->db_select('nd_bank_list ORDER BY status_default desc');
	   	$this->pre_faktur = get_pre_faktur();

	   	if (is_posisi_id() == 1) {
            // $this->output->enable_profiler(TRUE);
        }
	}

	function index(){
		redirect('admin/dashboard');
	}

//============================giro register=================================================

	function giro_register_list(){
		$menu = is_get_url($this->uri->segment(1)) ;

		$data = array(
			'content' =>'admin/finance/giro_register_list',
			'breadcrumb_title' => 'Finance',
			'breadcrumb_small' => 'Daftar Giro',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data'=> $this->data,
			'data_isi'=> $this->data );


		$data['user_id'] = is_user_id();
		$data['giro_list'] = $this->fi_model->get_giro_register();
		$this->load->view('admin/template',$data);
	}

	function giro_numerator($number){
		$giro_num = filter_var($number,FILTER_SANITIZE_NUMBER_INT);
		$pad_length = strlen(filter_var($giro_num,FILTER_SANITIZE_NUMBER_INT));
		$giro_num_end=filter_var($giro_num,FILTER_SANITIZE_NUMBER_INT);
		return str_pad($giro_num_end, $pad_length,'0', STR_PAD_LEFT);
	}

	function giro_register_insert(){
		$no_giro_awal = $this->input->post('no_giro_awal');
		$jml_giro = $this->input->post('jml_giro');

		$start = (int)filter_var($no_giro_awal, FILTER_SANITIZE_NUMBER_INT);

		$data = array(
			'tipe_trx' => $this->input->post('tipe_trx') ,
			'no_giro_awal' => $no_giro_awal,
			'jml_giro' => $jml_giro,
			'bank_list_id' => $this->input->post('bank_list_id'),
			'user_id' => is_user_id(),
			);

		$result_id = $this->common_model->db_insert('nd_giro_list', $data);
		redirect(is_setting_link('finance/giro_register_list_detail')."?id=".$result_id);

	}

	function giro_register_list_detail(){
		$menu = is_get_url($this->uri->segment(1)) ;

		$id = $this->input->get('id');
		$data = array(
			'content' =>'admin/finance/giro_register_list_detail',
			'breadcrumb_title' => 'Finance',
			'breadcrumb_small' => 'Daftar Giro Detail',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data'=> $this->data,
			'data_isi'=> $this->data );


		$data['user_id'] = is_user_id();
		$data['giro_data'] = $this->common_model->db_free_query_superadmin("SELECT t1.*, t2.nama_bank, t2.no_rek_bank
			FROM nd_giro_list t1
			LEFT JOIN nd_bank_list t2
			ON t1.bank_list_id = t2.id
			WHERE t1.id=$id");
		foreach ($data['giro_data']->result() as $row) {
			$tipe_trx = $row->tipe_trx;
			if ($tipe_trx == 1) {
				$pembayaran_type_id = 2;
			}elseif ($tipe_trx == 2) {
				$pembayaran_type_id = 5;
			}
		}
		$data['giro_list_detail'] = $this->fi_model->get_giro_register_detail($id, $pembayaran_type_id);
		$this->load->view('admin/template',$data);
	}

	function giro_register_detail_insert(){
		$id = $this->input->post('id');
		$giro_list_id = $this->input->post('giro_list_id');
		$no_giro = $this->input->post('no_giro');
		$data = array(
			'tanggal' => is_date_formatter($this->input->post('tanggal')) ,
			'giro_list_id' => $this->input->post('giro_list_id') ,
			'no_giro' => $this->input->post('no_giro') ,
			'jatuh_tempo' => is_date_formatter($this->input->post('jatuh_tempo')) ,
			'penerima' => $this->input->post('penerima') ,
			'amount' => str_replace('.', '', ($this->input->post('amount') == '' ? 0 : $this->input->post('amount') )),
			'keterangan' => $this->input->post('keterangan'),
			'status' => 1,
			'user_id' => is_user_id()
			 );

		$get_data = $this->common_model->db_select("nd_giro_list_detail WHERE no_giro='".$no_giro."' AND giro_list_id=".$giro_list_id);
		foreach ($get_data as $row) {
			if ($id=='') {
				$id = $row->id;
			}
		}

		if ($id=='') {
			$this->common_model->db_insert('nd_giro_list_detail', $data);
		}else{
			$this->common_model->db_update('nd_giro_list_detail', $data,'id', $id);
		}

		redirect(is_setting_link('finance/giro_register_list_detail').'?id='.$giro_list_id);
	}

	function giro_list_detail_remove(){
		$id = $this->input->get('id');
		$giro_list_id = $this->input->get('giro_list_id');
		$this->common_model->db_delete('nd_giro_list_detail', 'id', $id);
		redirect(is_setting_link('finance/giro_register_list_detail').'?id='.$giro_list_id);
		
	}

	function giro_register_detail_batal(){
		$id = $this->input->post('id');
		$tipe = $this->input->post('tipe');
		$giro_list_id = $this->input->post('giro_list_id');

		$data = array(
			'no_giro' => $this->input->post('no_giro'),
			'keterangan' => $this->input->post('keterangan'),
			'status' => 0,
			'user_id' => is_user_id(),
			'giro_list_id'=> $giro_list_id
			 );

		// echo $giro_list_id;
		// echo $tipe;

		if ($tipe == 1) {
			$this->common_model->db_delete("nd_pembayaran_pembayaran_hutang_removenilai", 'id', $id);
			$this->common_model->db_insert("nd_giro_list_detail", $data);			
		}elseif ($tipe==3) {
			$this->common_model->db_insert("nd_giro_list_detail", $data);
		}else{
			$this->common_model->db_update("nd_giro_list_detail", $data,'id',$id);
		}

		redirect(is_setting_link('finance/giro_register_list_detail').'?id='.$giro_list_id);

	}

//============================hutang awal section=================================================

	function hutang_awal(){
		$menu = is_get_url($this->uri->segment(1)) ;

		$data = array(
			'content' =>'admin/finance/hutang_awal',
			'breadcrumb_title' => 'Finance',
			'breadcrumb_small' => 'Hutang Awal',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data'=> $this->data,
			'data_isi'=> $this->data );


		$data['user_id'] = is_user_id();
		$data['hutang_list'] = $this->fi_model->get_hutang_awal(); 
		$this->load->view('admin/template',$data);
	}

	function hutang_awal_detail(){
		$menu = is_get_url($this->uri->segment(1)) ;
		$supplier_id = $this->input->get('supplier_id');

		$data = array(
			'content' =>'admin/finance/hutang_awal_detail',
			'breadcrumb_title' => 'Finance',
			'breadcrumb_small' => 'Hutang Awal Detail',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data'=> $this->data,
			'data_isi'=> $this->data,
			'supplier_id' => $supplier_id,
			'supplier_data' => $this->common_model->db_select("nd_supplier where id=".$supplier_id)
			 );


		$data['user_id'] = is_user_id();
		$data['hutang_list_detail'] = $this->fi_model->get_hutang_awal_detail($supplier_id); 
		$this->load->view('admin/template',$data);
	}

	function hutang_awal_insert(){

		$supplier_id = $this->input->post('supplier_id');

		$data = array(
			'supplier_id' => $supplier_id,
			'toko_id' => $this->input->post('toko_id'),
			'tanggal' => is_date_formatter($this->input->post('tanggal')) ,
			'no_faktur' => $this->input->post('no_faktur'),
			'amount' => str_replace('.', '', $this->input->post('amount')),
			'jatuh_tempo' => is_date_formatter($this->input->post('jatuh_tempo')) ,
			'user_id' => is_user_id() ,
			 );

		$this->common_model->db_insert('nd_hutang_awal',$data);
		redirect(is_setting_link('finance/hutang_awal_detail').'?supplier_id='.$supplier_id);
	}

	function hutang_awal_update(){

		$supplier_id = $this->input->post('supplier_id');
		$id = $this->input->post('id');

		$data = array(
			'toko_id' => $this->input->post('toko_id'),
			'tanggal' => is_date_formatter($this->input->post('tanggal')) ,
			'no_faktur' => $this->input->post('no_faktur'),
			'amount' => str_replace('.', '', $this->input->post('amount')),
			'jatuh_tempo' => is_date_formatter($this->input->post('jatuh_tempo')) ,
			'user_id' => is_user_id()
			 );

		$this->common_model->db_update('nd_hutang_awal',$data,'id',$id);
		redirect(is_setting_link('finance/hutang_awal_detail').'?supplier_id='.$supplier_id);
	}

//============================hutang section=================================================

	function hutang_list(){
		$menu = is_get_url($this->uri->segment(1)) ;

		$data = array(
			'content' =>'admin/finance/hutang_list',
			'breadcrumb_title' => 'Finance',
			'breadcrumb_small' => 'Daftar Hutang',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data'=> $this->data,
			'data_isi'=> $this->data );

		$tanggal = date('Y-m-d');
		$data['user_id'] = is_user_id();
		$data['hutang_list'] = $this->fi_model->get_hutang_list($tanggal); 
		$this->load->view('admin/template',$data);
	}

	function hutang_list_detail(){
		$menu = is_get_url($this->uri->segment(1)) ;
		$supplier_id = $this->input->get('supplier_id');

		$data = array(
			'content' =>'admin/finance/hutang_list_detail',
			'breadcrumb_title' => 'Finance',
			'breadcrumb_small' => 'Daftar Hutang Detil',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data'=> $this->data,
			'data_isi'=> $this->data,
			'supplier_id' => $supplier_id,
			'supplier_data' => $this->common_model->db_select('nd_supplier where id='.$supplier_id) );


		foreach ($data['supplier_data'] as $row) {
			$tipe_supplier = $row->tipe_supplier;
		}

		if ($tipe_supplier == 1) {
			$data['hutang_list_detail'] = $this->fi_model->get_hutang_list_detail($supplier_id); 
			$data['retur_list_detail']  = $this->fi_model->get_retur_list_detail($supplier_id);
		}else{
			$data['hutang_list_detail'] = $this->fi_model->get_hutang_lain_detail($supplier_id); 
			$data['retur_list_detail'] = array();
		}
		$data['tipe_supplier'] = $tipe_supplier;
		$this->load->view('admin/template_no_sidebar',$data);
	}

	function outstanding_hutang_list_detail(){
		$menu = is_get_url($this->uri->segment(1)) ;
		$supplier_id = $this->input->get('supplier_id');
		$edit_mode = 0;
		if ($this->input->get('edit_mode')) {
			$edit_mode = $this->input->get('edit_mode');
		}
		$jatuh_tempo = date('Y-m-d', strtotime('-6 days'));

		$data = array(
			'content' =>'admin/finance/outstanding_hutang_list_detail',
			'breadcrumb_title' => 'Finance',
			'breadcrumb_small' => 'Daftar Outstanding Hutang Detil',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data'=> $this->data,
			'data_isi'=> $this->data,
			'supplier_id' => $supplier_id,
			'edit_mode' => $edit_mode,
			'toko_id'=> $this->input->get('toko_id'),
			'jatuh_tempo' => is_reverse_date($jatuh_tempo)
			 );

		$data['supplier_data'] = $this->common_model->db_select('nd_supplier where id='.$supplier_id);
		$data['outstanding_list_detail'] = $this->fi_model->get_pembayaran_hutang_unbalance_by_supplier($supplier_id);
		$data['outstanding_list_giro'] = $this->fi_model->get_giro_keluar_belum_cair($supplier_id, $jatuh_tempo);
		
		// echo $pembayaran_outstanding_id_list;
		// $data['outstanding_list_detail'] = $this->fi_model->get_outstanding_list_detail($customer_id, $tanggal); 
		$this->load->view('admin/template',$data);
	}


	function hutang_payment(){
		$menu = is_get_url($this->uri->segment(1)) ;
		$tanggal_start = date("Y-m-d");
		$tanggal_end = date("Y-m-d");
		$supplier_id = '';
		$toko_id = '1';

		$view_type = 1;

		if ($this->input->get('tanggal_start') && $this->input->get('tanggal_start') != ''&& $this->input->get('tanggal_end') != '') {
			$tanggal_start = is_date_formatter($this->input->get('tanggal_start'));
			$tanggal_end = is_date_formatter($this->input->get('tanggal_end'));
		}

		if ($this->input->get('supplier_id') && $this->input->get('supplier_id') != '') {
			$supplier_id = $this->input->get('supplier_id');
		}

		if ($this->input->get('toko_id') && $this->input->get('toko_id') != '') {
			$toko_id = $this->input->get('toko_id');
		}

		$cond = "WHERE toko_id = ".$toko_id." ";
		if ($supplier_id != '') {
			$cond .= "AND supplier_id = ".$supplier_id;
		}

		if ($this->input->get('view_type') && $this->input->get('view_type') != '') {
			$view_type = $this->input->get('view_type');
		}

		$data = array(
			'content' =>'admin/finance/pembayaran_hutang',
			'breadcrumb_title' => 'Finance',
			'breadcrumb_small' => 'Daftar Pembayaran Hutang',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data'=> $this->data,
			'data_isi'=> $this->data,
			'supplier_id' => $supplier_id,
			'toko_id' => $toko_id,
			'tanggal_start' => is_reverse_date($tanggal_start),
			'tanggal_end' => is_reverse_date($tanggal_end),
			'view_type' => $view_type
			 );


		if ($this->input->get('tanggal_start') && $this->input->get('tanggal_start') != ''&& $this->input->get('tanggal_end') != '') {
			$data['pembayaran_hutang_list'] = $this->fi_model->get_pembayaran_hutang($tanggal_start, $tanggal_end, $cond);
			foreach ($data['pembayaran_hutang_list'] as $row) {

				$periode = $this->fi_model->get_periode_pembelian($row->id);
				foreach ($periode as $row2) {
					$data['periode'][$row->id]['tanggal_start'] = is_reverse_date($row2->tanggal_start);
					$data['periode'][$row->id]['tanggal_end'] = is_reverse_date($row2->tanggal_end);
				}
				$data['pembayaran_hutang_awal_detail'][$row->id] = $this->fi_model->get_pembayaran_hutang_awal_detail($row->id);
				$data['pembayaran_hutang_detail'][$row->id] = $this->fi_model->get_pembayaran_hutang_detail($row->id);
				$data['pembayaran_hutang_retur'][$row->id] = $this->fi_model->get_pembayaran_retur_beli_detail($row->id);
				$data['pembayaran_hutang_nilai'][$row->id] = $this->fi_model->get_pembayaran_nilai($row->id);

			}
		}else{
			if ($view_type == 1) {
				$data['pembayaran_hutang_list'] = $this->fi_model->get_pembayaran_hutang_unbalance();
				foreach ($data['pembayaran_hutang_list'] as $row) {

					$periode = $this->fi_model->get_periode_pembelian($row->id);
					foreach ($periode as $row2) {
						$data['periode'][$row->id]['tanggal_start'] = is_reverse_date($row2->tanggal_start);
						$data['periode'][$row->id]['tanggal_end'] = is_reverse_date($row2->tanggal_end);
					}
					$data['pembayaran_hutang_awal_detail'][$row->id] = $this->fi_model->get_pembayaran_hutang_awal_detail($row->id);
					$data['pembayaran_hutang_detail'][$row->id] = $this->fi_model->get_pembayaran_hutang_detail($row->id);
					$data['pembayaran_hutang_retur'][$row->id] = $this->fi_model->get_pembayaran_retur_beli_detail($row->id);
					$data['pembayaran_hutang_nilai'][$row->id] = $this->common_model->db_select("nd_pembayaran_hutang_nilai WHERE pembayaran_hutang_id=".$row->id);
				}
			}else{
				$data['pembayaran_hutang_list'] = $this->fi_model->get_pembayaran_hutang_nilai_last_10();
				foreach ($data['pembayaran_hutang_list'] as $row) {

					$periode = $this->fi_model->get_periode_pembelian($row->pembayaran_hutang_id);
					foreach ($periode as $row2) {
						$data['periode'][$row->id]['tanggal_start'] = is_reverse_date($row2->tanggal_start);
						$data['periode'][$row->id]['tanggal_end'] = is_reverse_date($row2->tanggal_end);
					}
					$data['pembayaran_hutang_awal_detail'][$row->id] = $this->fi_model->get_pembayaran_hutang_awal_detail($row->id);
					$data['pembayaran_hutang_detail'][$row->id] = $this->fi_model->get_pembayaran_hutang_detail($row->id);
					$data['pembayaran_hutang_nilai'][$row->id] = $this->fi_model->get_pembayaran_nilai($row->id);

				}
			}

			$data['status_view'] = 0;
		}

		// print_r($data['pembayaran_hutang_list']);
		if (is_posisi_id() == 1) {
			// print_r($data['pembayaran_hutang_detail']);
			// print_r($data['pembayaran_hutang_nilai']);
			$this->load->view('admin/template',$data);
		}else{
			$this->load->view('admin/template',$data);
			
		}
	}

	function hutang_payment_form(){
		$menu = is_get_url($this->uri->segment(1));

		if ($this->input->get('tanggal_start')) {
			$tanggal_start = is_date_formatter($this->input->get('tanggal_start'));
			$tanggal_end = is_date_formatter($this->input->get('tanggal_end'));
			$toko_id = $this->input->get('toko_id');
			$supplier_id = $this->input->get('supplier_id');

		}else{
			$tanggal_start = date("Y-m-01"); 
			$tanggal_end = date("Y-m-t");
			$toko_id = '';
			$supplier_id = '';
		}

		$pembayaran_hutang_id = '';
		if ($this->input->get('id')) {
			$pembayaran_hutang_id = $this->input->get('id');
		}

		$data = array(
			'content' =>'admin/finance/pembayaran_hutang_form',
			'breadcrumb_title' => 'Finance',
			'breadcrumb_small' => 'Formulir Pembayaran Hutang',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data'=> $this->data,
			'data_isi'=> $this->data,
			'tanggal_start' => is_reverse_date($tanggal_start),
			'tanggal_end' => is_reverse_date($tanggal_end),
			'toko_id' => $toko_id,
			'supplier_data' => array(),
			'supplier_id' => $supplier_id );



			$data['pembayaran_hutang_lain'] = array();
		if ($pembayaran_hutang_id != '') {

			$data['pembayaran_hutang_data'] = $this->fi_model->get_pembayaran_hutang_data($pembayaran_hutang_id);
			foreach ($data['pembayaran_hutang_data'] as $row) {
				$data['supplier_data'] = $this->common_model->db_select("nd_supplier where id=".$row->supplier_id);	
			}

			foreach ($data['supplier_data'] as $row) {
				$tipe_supplier = $row->tipe_supplier;
			}

			$periode = $this->fi_model->get_periode_pembelian($pembayaran_hutang_id);
			foreach ($periode as $row) {
				$data['tanggal_start'] = $row->tanggal_start;
				$data['tanggal_end'] = $row->tanggal_end;
			}

			$data['pembayaran_hutang_retur']=array();
			$data['bank_history'] = $this->fi_model->get_bank_bayar_history();
			$data['pembayaran_hutang_awal'] = $this->fi_model->get_pembayaran_hutang_awal_detail($pembayaran_hutang_id); 
			if ($tipe_supplier == 1) {
				$data['pembayaran_hutang_detail'] = $this->fi_model->get_pembayaran_hutang_detail($pembayaran_hutang_id); 
				$data['pembayaran_hutang_retur'] = $this->fi_model->get_pembayaran_retur_beli_detail($pembayaran_hutang_id); 
			}else{
				$data['pembayaran_hutang_detail'] = $this->fi_model->get_pembayaran_hutang_lain_detail($pembayaran_hutang_id); 
			}
			
			$data['pembayaran_hutang_nilai'] = $this->fi_model->get_pembayaran_hutang_nilai_info($pembayaran_hutang_id);
			$data['status_default_bank_id'] = 1;
			foreach ($this->bank_list_aktif as $baris) {
				
				$cond_bank_list = 'AND bank_list_id = '.$baris->id;
				$data['buku_giro_list'][$baris->id] = $this->fi_model->get_buku_giro(1, $cond_bank_list,2, $pembayaran_hutang_id);
				$data['buku_cek_list'][$baris->id] = $this->fi_model->get_buku_giro(2, $cond_bank_list,5, $pembayaran_hutang_id);
				
				foreach ($data['buku_giro_list'][$baris->id] as $row) {
					$giro_register_list = $this->fi_model->giro_register_detail($row->id, $pembayaran_hutang_id,2,'');
					foreach ($giro_register_list as $row2) {
						$data['giro_register_list'][$row2->no_giro]=$row2->tipe;
					}
				}

				foreach ($data['buku_cek_list'][$baris->id] as $row) {
					$cek_register_list = $this->fi_model->giro_register_detail($row->id, $pembayaran_hutang_id,5,'');
					foreach ($cek_register_list as $row2) {
						$data['cek_register_list'][$row2->no_giro]=$row2->tipe;
					}
					// print_r($cek_register_list);
				}
			}

			if (is_posisi_id()==1) {
				// print_r($data['giro_register_list']);
			}
			// $cond_bank_list = ($bank_list_id != '' ? "AND bank_list_id = ".$bank_list_id : '' );
			

		}elseif ($toko_id != '' && $supplier_id != '') {

			foreach ($this->common_model->db_select('nd_supplier where id='.$supplier_id) as $row) {
				$tipe_supplier = $row->tipe_supplier;
			}

			$data['pembayaran_hutang_retur']=array();
			$data['pembayaran_hutang_data'] = array();
			$data['pembayaran_hutang_awal'] = $this->fi_model->get_hutang_awal_by_date($tanggal_start, $tanggal_end, $toko_id, $supplier_id); 
			if ($tipe_supplier == 1) {
				$data['pembayaran_hutang_detail'] = $this->fi_model->get_hutang_list_by_date($tanggal_start, $tanggal_end, $toko_id, $supplier_id); 
				$data['pembayaran_hutang_retur'] = $this->fi_model->get_retur_beli_list_by_date($tanggal_start, $tanggal_end, $toko_id, $supplier_id); 
			}else{
				$data['pembayaran_hutang_detail'] = $this->fi_model->get_hutang_list_lain_by_date($tanggal_start, $tanggal_end, $toko_id, $supplier_id); 
			}
			$data['pembayaran_hutang_nilai'] = array();
			$data['bank_history'] = array();

			$data['buku_giro_list'] = array();
			$data['buku_cek_list'] = array();
		}else{
			$data['pembayaran_hutang_data'] = array();
			$data['pembayaran_hutang_awal'] = array();
			$data['pembayaran_hutang_detail'] = array(); 
			$data['pembayaran_hutang_retur']=array();
			$data['pembayaran_hutang_nilai'] = array();
			$data['bank_history'] = array();

			$data['buku_giro_list'] = array();
			$data['buku_cek_list'] = array();
			$tipe_supplier = 1;
		}

		$data['tipe_supplier'] = $tipe_supplier;
		$this->load->view('admin/template',$data);
	}

	
	function pembayaran_hutang_insert(){
		$ini = $this->input;
		$pembayaran_hutang_id = $this->input->post('pembayaran_hutang_id');
		// echo $pembayaran_hutang_id;
		

		if ($pembayaran_hutang_id == '') {

			$data = array(
			'supplier_id' => $ini->post('supplier_id'),
			'toko_id' => $ini->post('toko_id'),
			'pembulatan' => 0,
			'user_id' => is_user_id() );

			$result_id = $this->common_model->db_insert('nd_pembayaran_hutang',$data);

			$post = (array)$this->input->post();
			$idx = 0;
			foreach ($post as $key => $value) {
				if (strpos($key, 'bayar_') !== false) {
					// echo $key.'-->'.$value.'<br/>';
					$data_get = explode('_', $key);
					if ($value != '' && $value != 0) {
						$pembelian_id[$idx] = $data_get[1];
						$idx++;
					}
				}elseif (strpos($key, 'hutang_') !== false) {
					// echo $key.'-->'.$value.'<br/>';
					$data_get = explode('_', $key);
					if ($value != '' && $value != 0) {
						$hutang_awal_id[$idx] = $data_get[1];
						$idx++;
					}
				}
			}

			$idx = 0;
			if (isset($pembelian_id)) {
				foreach ($pembelian_id as $key => $value) {
					$data_detail[$idx] = array(
						'pembayaran_hutang_id' => $result_id,
						'pembelian_id' => $value ,
						'amount' => str_replace('.', '', $post['bayar_'.$value]),
						'data_status' => $this->input->post('data_status_'.$value)
						 );
					$idx++;
				}
			}

			if (isset($hutang_awal_id)) {
				foreach ($hutang_awal_id as $key => $value) {
					$data_detail[$idx] = array(
						'pembayaran_hutang_id' => $result_id,
						'pembelian_id' => $value ,
						'amount' => str_replace('.', '', $post['hutang_'.$value]),
						'data_status' => 2
						 );
					$idx++;
				}
			}


			$this->common_model->db_insert_batch('nd_pembayaran_hutang_detail',$data_detail);

			$pembayaran_hutang_id = $result_id;	
		}else{

			$data = array(
			'pembayaran_type_id' => $ini->post('pembayaran_type_id'),
			'nama_bank' => $ini->post('nama_bank'),
			'no_rek_bank' => $ini->post('no_rek_bank'),
			'tanggal_transfer' => is_date_formatter($ini->post('tanggal_transfer')),
			'tanggal_giro' => is_date_formatter($ini->post('tanggal_giro')),
			'jatuh_tempo' => is_date_formatter($ini->post('jatuh_tempo')),
			'nama_penerima' => $ini->post('nama_penerima'),
			'keterangan' => $ini->post('keterangan'),
			'user_id' => is_user_id() );

			$this->common_model->db_update('nd_pembayaran_hutang',$data,'id',$pembayaran_hutang_id);	
		}
		
		redirect(is_setting_link('finance/hutang_payment_form').'/?id='.$pembayaran_hutang_id);

	}

	function update_bayar_hutang_detail(){
		$id = $this->input->post('id');
		$data = array(
			'amount' => $this->input->post('amount') );
		$this->common_model->db_update('nd_pembayaran_hutang_detail',$data,'id',$id);
		echo "OK";
	}

	function pembayaran_hutang_nilai_insert(){
		// print_r($this->input->post());
		$ini = $this->input;
		$pembayaran_hutang_id = $ini->post('pembayaran_hutang_id');
		$pembayaran_type_id = $ini->post('pembayaran_type_id');
		$giro_register_id = ($pembayaran_type_id == 2 ? $ini->post('giro_register_id') : ( $pembayaran_type_id == 5 ? $ini->post('cek_register_id') : null ) );
		$data = array(
			'pembayaran_hutang_id' =>  $pembayaran_hutang_id,
			'pembayaran_type_id' =>  $ini->post('pembayaran_type_id'),
			'giro_register_id' =>  ($giro_register_id == '' ? null : $giro_register_id),
			// 'giro_register_id' =>  $giro_register_id,
			'tanggal_transfer' => is_date_formatter($ini->post('tanggal_transfer')),
			'nama_bank' => ($ini->post('nama_bank') != '' ? $ini->post('nama_bank') : null),
			'no_rek_bank'=> ($ini->post('no_rek_bank') != '' ?  $ini->post('no_rek_bank') : null),
			'no_giro' => ($ini->post('no_giro') != '' ? $ini->post('no_giro') : null) ,
			'bank_list_id' => $ini->post('bank_list_id'),
			// 'no_akun_giro'=> ($ini->post('no_akun_giro') != '' ? $ini->post('no_akun_giro') : null),
			// 'tanggal_giro' => is_date_formatter($ini->post('tanggal_giro')),
			'jatuh_tempo' => ($ini->post('jatuh_tempo') !='' ? is_date_formatter($ini->post('jatuh_tempo')) : null),
			'amount' => str_replace('.', '', $ini->post('amount')),
			'keterangan' => $ini->post('keterangan'),
			'user_id' => is_user_id()
			);

		// print_r($data);
		$this->common_model->db_insert('nd_pembayaran_hutang_nilai', $data);

		redirect(is_setting_link('finance/hutang_payment_form').'/?id='.$pembayaran_hutang_id.'#bayar-section');


	}

	function buku_giro_cek_get_page(){
		$tipe_trx = $this->input->post('tipe_trx');
		$bank_list_id = $this->input->post('bank_list_id');
		$pembayaran_hutang_id = $this->input->post('pembayaran_hutang_id');
		$pembayaran_hutang_nilai_id = $this->input->post('pembayaran_hutang_nilai_id');
		$cond_nilai_id = ($pembayaran_hutang_nilai_id != '' ? 'AND id != '.$pembayaran_hutang_nilai_id : '' );
		$cond_bank_list = 'AND bank_list_id = '.$bank_list_id;
		$pembayaran_type_id = ($tipe_trx == 1 ? 2 : 5);
		$get_list = $this->fi_model->get_buku_giro($tipe_trx, $cond_bank_list, $pembayaran_type_id, $pembayaran_hutang_id);
		
		$giro_register_list = array();
		$giro_register = array();
		foreach ($get_list as $row) {
			$giro_register = $this->fi_model->giro_register_detail($row->id, $pembayaran_hutang_id,$pembayaran_type_id,$cond_nilai_id);
			foreach ($giro_register as $row2) {
				$giro_register_list[$row2->no_giro]=$row2->tipe;
			}
		}

		// print_r($giro_register_list);

		$idx = 1;
		$option = array();
		$option[0] = "<option value=''>Pilih</option>";
		foreach ($get_list as $row) {
			$giro_awal = filter_var($row->no_giro_awal,FILTER_SANITIZE_NUMBER_INT);
			$pad_length = strlen($giro_awal);
			$jml_giro = $row->jml_giro;
			$giro_register_id[$row->id] = $row->id;
			$pre = $pre = str_replace($giro_awal, '', $row->no_giro_awal);
			$bg_color = $row->{'tipe_trx_'.$row->tipe_trx};
			for ($i=$giro_awal; $i < $giro_awal + $jml_giro ; $i++) { 
				$left_pad = str_pad($i, $pad_length,'0', STR_PAD_LEFT);
				if(!isset($giro_register_list[$pre.$left_pad])){
					$option[$idx] = "<option value='".$row->id."' style='background-color:".$bg_color."' >".$pre.$left_pad."</option>";
				}elseif (isset($giro_register_list[$pre.$left_pad]) && $giro_register_list[$pre.$left_pad] == 2 ) {
					$option[$idx] = "<option value='".$row->id."'  style='background-color:".$bg_color."'  disabled >".$pre.$left_pad."</option>";
				}
				$idx++;
			}
		}

		// print_r($get_list);
	    echo json_encode( $option );
	    // print_r($this->input->post());

	}

	function pembayaran_hutang_nilai_delete(){
		// print_r($this->input->post());
		$ini = $this->input;
		$pembayaran_hutang_id = $ini->get('pembayaran_hutang_id');
		$id = $ini->get('id');

		// print_r($data);
		$this->common_model->db_delete('nd_pembayaran_hutang_nilai', 'id',$id);

		redirect(is_setting_link('finance/hutang_payment_form').'/?id='.$pembayaran_hutang_id.'#bayar-section');

	}

	function pembayaran_hutang_nilai_update(){
		// print_r($this->input->post());
		$ini = $this->input;
		$pembayaran_hutang_id = $ini->post('pembayaran_hutang_id');
		$id = $ini->post('pembayaran_hutang_nilai_id');
		$pembayaran_type_id = $ini->post('pembayaran_type_id');
		$giro_register_id = ($pembayaran_type_id == 2 ? $ini->post('giro_register_id') : ( $pembayaran_type_id == 5 ? $ini->post('cek_register_id') : null ) );
		$data = array(
			'pembayaran_hutang_id' =>  $pembayaran_hutang_id,
			'pembayaran_type_id' =>  $ini->post('pembayaran_type_id'),
			'giro_register_id' =>  ($giro_register_id == '' ? null : $giro_register_id),
			// 'giro_register_id' =>  $giro_register_id,
			'tanggal_transfer' => is_date_formatter($ini->post('tanggal_transfer')),
			'nama_bank' => ($ini->post('nama_bank') != '' ? $ini->post('nama_bank') : null),
			'no_rek_bank'=> ($ini->post('no_rek_bank') != '' ?  $ini->post('no_rek_bank') : null),
			'no_giro' => ($ini->post('no_giro') != '' ? $ini->post('no_giro') : null) ,
			'bank_list_id' => $ini->post('bank_list_id'),
			// 'no_akun_giro'=> ($ini->post('no_akun_giro') != '' ? $ini->post('no_akun_giro') : null),
			// 'tanggal_giro' => is_date_formatter($ini->post('tanggal_giro')),
			'jatuh_tempo' => ($ini->post('jatuh_tempo') !='' ? is_date_formatter($ini->post('jatuh_tempo')) : null),
			'amount' => str_replace('.', '', $ini->post('amount')),
			'keterangan' => $ini->post('keterangan'),
			'user_id' => is_user_id()
			 );

		// print_r($data);
		$this->common_model->db_update("nd_pembayaran_hutang_nilai", $data,'id', $id);
		redirect(is_setting_link('finance/hutang_payment_form').'/?id='.$pembayaran_hutang_id);
	}

	function update_pembulatan_hutang(){
		$id = $this->input->post('id');
		$data = array(
			'pembulatan' => $this->input->post('pembulatan') );
		$this->common_model->db_update("nd_pembayaran_hutang", $data, "id", $id);
		echo "OK";

	}

	function pembayaran_hutang_remove(){
		$pembayaran_hutang_id = $this->input->get('pembayaran_hutang_id');

		$this->common_model->db_delete('nd_pembayaran_hutang_detail', 'pembayaran_hutang_id',$pembayaran_hutang_id);
		$this->common_model->db_delete('nd_pembayaran_hutang_nilai','pembayaran_hutang_id',$pembayaran_hutang_id);
		$this->common_model->db_delete('nd_pembayaran_hutang','id',$pembayaran_hutang_id);
		redirect(is_setting_link('finance/hutang_payment'));
	}

	

//============================piutang awal section=================================================

	function piutang_awal(){
		$menu = is_get_url($this->uri->segment(1)) ;

		$data = array(
			'content' =>'admin/finance/piutang_awal',
			'breadcrumb_title' => 'Finance',
			'breadcrumb_small' => 'piutang Awal',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data'=> $this->data,
			'data_isi'=> $this->data );


		$data['user_id'] = is_user_id();
		$data['piutang_list'] = $this->fi_model->get_piutang_awal(); 
		$this->load->view('admin/template',$data);
	}

	function piutang_awal_detail(){
		$menu = is_get_url($this->uri->segment(1)) ;
		$customer_id = $this->input->get('customer_id');

		$data = array(
			'content' =>'admin/finance/piutang_awal_detail',
			'breadcrumb_title' => 'Finance',
			'breadcrumb_small' => 'piutang Awal Detail',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data'=> $this->data,
			'data_isi'=> $this->data,
			'customer_id' => $customer_id,
			'customer_data' => $this->common_model->db_select("nd_customer where id=".$customer_id)
			 );


		$data['user_id'] = is_user_id();
		$data['piutang_list_detail'] = $this->fi_model->get_piutang_awal_detail($customer_id); 
		$this->load->view('admin/template',$data);
	}

	function piutang_awal_insert(){

		$customer_id = $this->input->post('customer_id');

		$data = array(
			'customer_id' => $customer_id,
			'toko_id' => $this->input->post('toko_id'),
			'tanggal' => is_date_formatter($this->input->post('tanggal')) ,
			'jumlah_roll' => $this->input->post('jumlah_roll'),
			'no_faktur' => $this->input->post('no_faktur'),
			'amount' => str_replace('.', '', $this->input->post('amount')),
			'jatuh_tempo' => is_date_formatter($this->input->post('jatuh_tempo')) ,
			'user_id' => is_user_id() ,
			 );

		$this->common_model->db_insert('nd_piutang_awal',$data);
		redirect(is_setting_link('finance/piutang_awal_detail').'?customer_id='.$customer_id);
	}

	function piutang_awal_update(){

		$customer_id = $this->input->post('customer_id');
		$id = $this->input->post('id');

		$data = array(
			'toko_id' => $this->input->post('toko_id'),
			'tanggal' => is_date_formatter($this->input->post('tanggal')) ,
			'no_faktur' => $this->input->post('no_faktur'),
			'jumlah_roll' => $this->input->post('jumlah_roll'),
			'amount' => str_replace('.', '', $this->input->post('amount')),
			'jatuh_tempo' => is_date_formatter($this->input->post('jatuh_tempo')) ,
			'user_id' => is_user_id()
			 );

		$this->common_model->db_update('nd_piutang_awal',$data,'id',$id);
		redirect(is_setting_link('finance/piutang_awal_detail').'?customer_id='.$customer_id);
	}


//============================piutang section=================================================

	function piutang_list(){
		$menu = is_get_url($this->uri->segment(1)) ;

		$view_type = 1;
		$tanggal_filter = '';
		if ($this->input->get('view_type') && $this->input->get('view_type') == '2' && $this->input->get('tanggal') !='' ) {
			$tanggal = is_date_formatter($this->input->get('tanggal'));
			$view_type = 2;
			$tanggal_filter = $this->input->get('tanggal');
		}

		$data = array(
			'content' =>'admin/finance/piutang_list',
			'breadcrumb_title' => 'Finance',
			'breadcrumb_small' => 'Daftar Piutang',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data'=> $this->data,
			'data_isi'=> $this->data,
			'view_type' => $view_type,
			'tanggal_filter' => $tanggal_filter );

		if ($view_type == 2) {
			$data['piutang_list'] = $this->fi_model->get_piutang_list_by_tgl_trx($tanggal);
		}else{
			$data['piutang_list'] = $this->fi_model->get_piutang_list_all();
		}
		$this->load->view('admin/template',$data);
	}

	function piutang_list_detail(){
		$menu = is_get_url($this->uri->segment(1)) ;
		$customer_id = $this->input->get('customer_id');
		$tanggal = is_date_formatter($this->input->get('tanggal'));

		$data = array(
			'content' =>'admin/finance/piutang_list_detail',
			'breadcrumb_title' => 'Finance',
			'breadcrumb_small' => 'Daftar Piutang Detil',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data'=> $this->data,
			'data_isi'=> $this->data,
			'customer_id' => $customer_id,
			'tanggal' =>$this->input->get('tanggal') );

		$data['customer_data'] = $this->common_model->db_select('nd_customer where id='.$customer_id);
		$data['piutang_list_detail'] = $this->fi_model->get_piutang_list_detail($customer_id);
		$this->load->view('admin/template',$data);
	}

	function outstanding_list_detail(){
		$menu = is_get_url($this->uri->segment(1)) ;
		$customer_id = $this->input->get('customer_id');
		$edit_mode = 0;
		if ($this->input->get('edit_mode')) {
			$edit_mode = $this->input->get('edit_mode');
		}

		$data = array(
			'content' =>'admin/finance/outstanding_list_detail',
			'breadcrumb_title' => 'Finance',
			'breadcrumb_small' => 'Daftar Outstanding Detil',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data'=> $this->data,
			'data_isi'=> $this->data,
			'customer_id' => $customer_id,
			'edit_mode' => $edit_mode,
			'toko_id'=> $this->input->get('toko_id')
			 );

		$data['customer_data'] = $this->common_model->db_select('nd_customer where id='.$customer_id);
		$data['outstanding_list_detail'] = $this->fi_model->get_kontra_belum_lunas($customer_id);
		
		// echo $pembayaran_outstanding_id_list;
		// $data['outstanding_list_detail'] = $this->fi_model->get_outstanding_list_detail($customer_id, $tanggal); 
		$this->load->view('admin/template',$data);
	}

	function piutang_list_detail_rearrange(){
		// print_r((array)$this->input->post());
		$toko_id = $this->input->post('toko_id');
		$customer_id = $this->input->post('customer_id');
		$case = "CASE
		";
		foreach ($this->input->post('detail_id') as $key => $value) {
			$detail_id = $key;
			$case .="WHEN id=".$key." THEN ".$value."
			";
			$pool_detail_id[$key] = $key; 
			// echo $detail_id.'->'.$value.'<br/>';
		}

		// echo $case;
		// echo $pool_detail = implode(',', $pool_detail_id);
		

		$this->common_model->db_free_query_superadmin("UPDATE nd_pembayaran_piutang_detail 
			SET pembayaran_piutang_id = 
			$case
			ELSE pembayaran_piutang_id
			END ");

		redirect(is_setting_link('finance/outstanding_list_detail').'?customer_id='.$customer_id."&toko_id=".$toko_id);

	}

	function piutang_payment(){
		$menu = is_get_url($this->uri->segment(1)) ;
		$tanggal_start = date("Y-m-d");
		$tanggal_end = date("Y-m-d");
		$customer_id = '';
		$toko_id = '1';

		if ($this->input->get('tanggal_start') && $this->input->get('tanggal_start') != ''&& $this->input->get('tanggal_end') != '') {
			$tanggal_start = is_date_formatter($this->input->get('tanggal_start'));
			$tanggal_end = is_date_formatter($this->input->get('tanggal_end'));
		}

		if ($this->input->get('customer_id') && $this->input->get('customer_id') != '') {
			$customer_id = $this->input->get('customer_id');
		}

		if ($this->input->get('toko_id') && $this->input->get('toko_id') != '') {
			$toko_id = $this->input->get('toko_id');
		}

		$cond = "WHERE toko_id = ".$toko_id." ";
		if ($customer_id != '') {
			$cond .= "AND customer_id = ".$customer_id;
		}

		$data = array(
			'content' =>'admin/finance/pembayaran_piutang',
			'breadcrumb_title' => 'Finance',
			'breadcrumb_small' => 'Daftar Pembayaran Piutang',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data'=> $this->data,
			'data_isi'=> $this->data,
			'customer_id' => $customer_id,
			'toko_id' => $toko_id,
			'tanggal_start' => is_reverse_date($tanggal_start),
			'tanggal_end' => is_reverse_date($tanggal_end) 
		);

		// if (is_posisi_id() == 1) {


		if ($this->input->get('tanggal_start') && $this->input->get('tanggal_start') != ''&& $this->input->get('tanggal_end') != '') {
			$data['pembayaran_piutang_list'] = $this->fi_model->get_pembayaran_piutang($tanggal_start, $tanggal_end, $cond);
			// echo $data['pembayaran_piutang_list'];
			$id_array = array();
			foreach ($data['pembayaran_piutang_list'] as $row) {
				$periode = $this->fi_model->get_periode_penjualan($row->id);
				foreach ($periode as $row2) {
					$data['periode'][$row->id]['tanggal_start'] = is_reverse_date($row2->tanggal_start);
					$data['periode'][$row->id]['tanggal_end'] = is_reverse_date($row2->tanggal_end);
				}

				$data['pembayaran_piutang_awal_detail'][$row->id] = array();
				$data['pembayaran_piutang_detail'][$row->id] = array();
				$data['pembayaran_retur_detail'][$row->id] = array();
				$data['pembayaran_piutang_nilai'][$row->id] = array();
				array_push($id_array, $row->id);
			}

			$id_list = implode(", ", $id_array);

			if (count($id_array) > 0) {
				$pembayaran_piutang_awal_detail = $this->fi_model->get_pembayaran_piutang_awal_detail($id_list);
				$pembayaran_piutang_detail = $this->fi_model->get_pembayaran_piutang_detail($id_list);
				$pembayaran_retur_detail = $this->fi_model->get_pembayaran_retur_detail($id_list);
				$pembayaran_piutang_nilai = $this->common_model->db_select("nd_pembayaran_piutang_nilai WHERE pembayaran_piutang_id IN ($id_list)");

				foreach ($pembayaran_piutang_awal_detail as $r) {
					if (!isset($data['pembayaran_piutang_awal_detail'][$r->pembayaran_piutang_id])) {
						$data['pembayaran_piutang_awal_detail'][$r->pembayaran_piutang_id] = array();
					}
					array_push($data['pembayaran_piutang_awal_detail'][$r->pembayaran_piutang_id] ,$r);
					
				}
	
				foreach ($pembayaran_piutang_detail as $r) {
					if (!isset($data['pembayaran_piutang_detail'][$r->pembayaran_piutang_id])) {
						$data['pembayaran_piutang_detail'][$r->pembayaran_piutang_id] = array();
					}
					array_push($data['pembayaran_piutang_detail'][$r->pembayaran_piutang_id] ,$r);
				}
	
				foreach ($pembayaran_retur_detail as $r) {
					if (!isset($data['pembayaran_retur_detail'][$r->pembayaran_piutang_id])) {
						$data['pembayaran_retur_detail'][$r->pembayaran_piutang_id] = array();
					}
					array_push($data['pembayaran_retur_detail'][$r->pembayaran_piutang_id] ,$r);
				}
	
				foreach ($pembayaran_piutang_nilai as $r) {
					if (!isset($data['pembayaran_piutang_nilai'][$r->pembayaran_piutang_id])) {
						$data['pembayaran_piutang_nilai'][$r->pembayaran_piutang_id] = array();
					}
					array_push($data['pembayaran_piutang_nilai'][$r->pembayaran_piutang_id] ,$r);
					// echo "<hr/>";
					// print_r($r);
					// echo "<hr/>";
					if ($r->pembayaran_type_id == 5) {
						$data['pembayaran_piutang_dp'][$r->pembayaran_piutang_id][$r->id] = $this->common_model->db_select("nd_dp_masuk WHERE id=".$r->dp_masuk_id);
						$data['bayar_dp_amount'][$r->pembayaran_piutang_id][$r->id] = $r->amount;
					}
				}
			}


		}else{
			$data['pembayaran_piutang_list'] = $this->fi_model->get_pembayaran_piutang_unbalance();

			$id_array = array();
			foreach ($data['pembayaran_piutang_list'] as $row) {

				$periode = $this->fi_model->get_periode_penjualan($row->id);
				foreach ($periode as $row2) {
					$data['periode'][$row->id]['tanggal_start'] = is_reverse_date($row2->tanggal_start);
					$data['periode'][$row->id]['tanggal_end'] = is_reverse_date($row2->tanggal_end);
				}
				array_push($id_array, $row->id);

				$data['pembayaran_piutang_awal_detail'][$row->id] = array();
				$data['pembayaran_piutang_detail'][$row->id] = array();
				$data['pembayaran_retur_detail'][$row->id] = array();
				$data['pembayaran_piutang_nilai'][$row->id] = array();

				// $data['pembayaran_piutang_awal_detail'][$row->id] = $this->fi_model->get_pembayaran_piutang_awal_detail($row->id);
				// $data['pembayaran_piutang_detail'][$row->id] = $this->fi_model->get_pembayaran_piutang_detail($row->id);
				// $data['pembayaran_retur_detail'][$row->id] = $this->fi_model->get_pembayaran_retur_detail($row->id);
				// $data['pembayaran_piutang_nilai'][$row->id] = $this->common_model->db_select("nd_pembayaran_piutang_nilai WHERE pembayaran_piutang_id=".$row->id);
				// foreach ($data['pembayaran_piutang_nilai'][$row->id] as $isi) {
				// 	if ($isi->pembayaran_type_id == 5) {
				// 		$data['pembayaran_piutang_dp'][$row->id][$isi->id] = $this->common_model->db_select("nd_dp_masuk WHERE id=".$isi->dp_masuk_id);
				// 		$data['bayar_dp_amount'][$row->id][$isi->id] = $isi->amount;
				// 		// print_r($data['pembayaran_piutang_dp'][$row->id]);
				// 	}
				// }
			}

			$id_list = implode(", ", $id_array);
			

			if (count($id_array) > 0) {
				$pembayaran_piutang_awal_detail = $this->fi_model->get_pembayaran_piutang_awal_detail($id_list);
				$pembayaran_piutang_detail = $this->fi_model->get_pembayaran_piutang_detail($id_list);
				$pembayaran_retur_detail = $this->fi_model->get_pembayaran_retur_detail($id_list);
				$pembayaran_piutang_nilai = $this->common_model->db_select("nd_pembayaran_piutang_nilai WHERE pembayaran_piutang_id IN ($id_list)");

				foreach ($pembayaran_piutang_awal_detail as $r) {
					if (!isset($data['pembayaran_piutang_awal_detail'][$r->pembayaran_piutang_id])) {
						$data['pembayaran_piutang_awal_detail'][$r->pembayaran_piutang_id] = array();
					}
					array_push($data['pembayaran_piutang_awal_detail'][$r->pembayaran_piutang_id] ,$r);
					
				}
	
				foreach ($pembayaran_piutang_detail as $r) {
					if (!isset($data['pembayaran_piutang_detail'][$r->pembayaran_piutang_id])) {
						$data['pembayaran_piutang_detail'][$r->pembayaran_piutang_id] = array();
					}
					array_push($data['pembayaran_piutang_detail'][$r->pembayaran_piutang_id] ,$r);
				}
	
				foreach ($pembayaran_retur_detail as $r) {
					if (!isset($data['pembayaran_retur_detail'][$r->pembayaran_piutang_id])) {
						$data['pembayaran_retur_detail'][$r->pembayaran_piutang_id] = array();
					}
					array_push($data['pembayaran_retur_detail'][$r->pembayaran_piutang_id] ,$r);
				}
	
				foreach ($pembayaran_piutang_nilai as $r) {
					if (!isset($data['pembayaran_piutang_nilai'][$r->pembayaran_piutang_id])) {
						$data['pembayaran_piutang_nilai'][$r->pembayaran_piutang_id] = array();
					}
					array_push($data['pembayaran_piutang_nilai'][$r->pembayaran_piutang_id] ,$r);
					// echo "<hr/>";
					// print_r($r);
					// echo "<hr/>";
					if ($r->pembayaran_type_id == 5) {
						$data['pembayaran_piutang_dp'][$r->pembayaran_piutang_id][$r->id] = $this->common_model->db_select("nd_dp_masuk WHERE id=".$r->dp_masuk_id);
						$data['bayar_dp_amount'][$r->pembayaran_piutang_id][$r->id] = $r->amount;
					}
				}
			}
			$data['status_view'] = 0;
		}
	// }

		if (is_posisi_id() == 1) {

			// print_r($data['pembayaran_piutang_nilai']);
			$this->load->view('admin/template',$data);
			$this->output->enable_profiler(TRUE);
		}else{
			$this->load->view('admin/template',$data);
			// $this->load->view('admin/template',$data);

			// echo "<h1>Maintenance</h1>";
		}
	}

	function set_piutang_form_by_customer(){
		$customer_id = $this->input->get('customer_id');
		$get_piutang = $this->fi_model->get_piutang_list_by_customer($customer_id);
		foreach ($get_piutang as $row) {
			$tanggal_start = $row->tanggal_start;
			$tanggal_end = $row->tanggal_end;
		}

		redirect(is_setting_link('finance/piutang_payment_form')."?customer_id=".$customer_id."&toko_id=1&tanggal_start=".$tanggal_start."&tanggal_end=".$tanggal_end);
	}

	function piutang_payment_form(){
		$menu = is_get_url($this->uri->segment(1));

		if ($this->input->get('tanggal_start')) {
			$tanggal_start = is_date_formatter($this->input->get('tanggal_start'));
			$tanggal_end = is_date_formatter($this->input->get('tanggal_end'));
			$toko_id = $this->input->get('toko_id');
			$customer_id = $this->input->get('customer_id');
			$status_jt = $this->input->get('status_jt');

		}else{
			$tanggal_start = date("Y-m-01"); 
			$tanggal_end = date("Y-m-t");
			$toko_id = 1;
			$customer_id = '';
			$status_jt = 0;
		}

		$pembayaran_piutang_id = '';
		if ($this->input->get('id')) {
			$pembayaran_piutang_id = $this->input->get('id');
		}

		$data = array(
			'content' =>'admin/finance/pembayaran_piutang_form',
			'breadcrumb_title' => 'Finance',
			'breadcrumb_small' => 'Formulir Pembayaran Piutang',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data'=> $this->data,
			'data_isi'=> $this->data,
			'tanggal_start' => is_reverse_date($tanggal_start),
			'tanggal_end' => is_reverse_date($tanggal_end),
			'toko_id' => $toko_id,
			'customer_id' => $customer_id,
			'status_jt' => $status_jt 
		);


		$data['latest_pembayaran_piutang_nilai_id'] = '';
		if ($pembayaran_piutang_id != '' && $this->common_model->db_select_num_rows("nd_pembayaran_piutang where id=".$pembayaran_piutang_id) > 0 ) {
			$data['pembayaran_piutang_data'] = $this->fi_model->get_pembayaran_piutang_data($pembayaran_piutang_id);
			$periode = $this->fi_model->get_periode_penjualan($pembayaran_piutang_id);
			foreach ($periode as $row) {
				$data['tanggal_start'] = $row->tanggal_start;
				$data['tanggal_end'] = $row->tanggal_end;
			}
			foreach ($data['pembayaran_piutang_data'] as $row) {
				$customer_id = $row->customer_id;
			}
			$data['pembayaran_piutang_awal_detail'] = $this->fi_model->get_pembayaran_piutang_awal_detail($pembayaran_piutang_id); 
			$data['pembayaran_piutang_detail'] = $this->fi_model->get_pembayaran_piutang_detail($pembayaran_piutang_id); 
			$data['pembayaran_piutang_retur'] = $this->fi_model->get_pembayaran_retur_detail($pembayaran_piutang_id); 
			$data['pembayaran_piutang_nilai'] = $this->fi_model->get_pembayaran_nilai_detail_info($pembayaran_piutang_id);
			$data['giro_tolakan_list'] = $this->fi_model->get_giro_tolakan_piutang($pembayaran_piutang_id);
			$data['bank_history'] = $this->fi_model->get_customer_bank_bayar_history($customer_id);
            $data['dp_list_detail'] = $this->fi_model->get_dp_berlaku($customer_id, $pembayaran_piutang_id); 
			$data['piutang_other'] = $this->fi_model->get_piutang_other($toko_id, $customer_id, $pembayaran_piutang_id); 

			if ($this->session->flashdata('latest_payment_nilai')) {
				$data['latest_pembayaran_piutang_nilai_id'] = $this->session->flashdata('latest_payment_nilai');
			}

			$data['toko_data'] = $this->common_model->db_select('nd_toko WHERE id=1');
	        $data['customer_data'] = $this->common_model->db_select('nd_customer WHERE id='.$customer_id);

		}elseif ($toko_id != '' && $customer_id != '') {
			
			$cond_jt = '';
			if ($status_jt == 1) {
				$cond_jt = "AND new_jatuh_tempo <= '".date('Y-m-d')."'";
			}

			$data['pembayaran_piutang_data'] = array();
			$data['pembayaran_piutang_awal_detail'] = $this->fi_model->get_piutang_awal_by_date($tanggal_start, $tanggal_end, $toko_id, $customer_id); 
			$data['pembayaran_piutang_detail'] = $this->fi_model->get_piutang_list_by_date($tanggal_start, $tanggal_end, $toko_id, $customer_id,$cond_jt); 
			$data['pembayaran_piutang_retur'] = $this->fi_model->get_retur_list_by_date($tanggal_start, $tanggal_end, $toko_id, $customer_id,$cond_jt); 
			$data['giro_tolakan_list'] = $this->fi_model->get_giro_tolakan_sisa($customer_id);
			$data['pembayaran_piutang_nilai'] = array();
			$data['bank_history'] = array();
            $data['dp_list_detail'] = $this->fi_model->get_dp_berlaku($customer_id, 0);
			$data['toko_data'] = array();
	        $data['customer_data'] = array();
	        $data['piutang_other'] = array();

		}else{
			$data['pembayaran_piutang_awal_detail'] = array(); 
			$data['pembayaran_piutang_data'] = array();
			$data['pembayaran_piutang_detail'] = array(); 
			$data['pembayaran_piutang_retur'] = array(); 
			$data['pembayaran_piutang_nilai'] = array();
			$data['giro_tolakan_list'] = array();
			$data['bank_history'] = array();
            $data['dp_list_detail'] = array();
			$data['toko_data'] = array();
	        $data['customer_data'] = array();
	        $data['piutang_other'] = array();
		}

		$data['piutang_list'] = $this->fi_model->get_piutang_list_all(); 
		$data['printer_list'] = $this->common_model->db_select('nd_printer_list');

		$this->load->view('admin/template',$data);
		if (is_posisi_id() == 1) {
            // $this->output->enable_profiler(TRUE);
        }
	}

	function pembayaran_piutang_insert(){
		$ini = $this->input;
		$pembayaran_piutang_id = $this->input->post('pembayaran_piutang_id');
		// echo $pembayaran_piutang_id;
		

		if ($pembayaran_piutang_id == '') {

			$data = array(
				'tanggal_kontra' =>is_date_formatter($ini->post('tanggal_kontra')),
				'customer_id' => $ini->post('customer_id'),
				'toko_id' => $ini->post('toko_id'),
				'pembulatan' => 0,
				'user_id' => is_user_id() 
				);

			$result_id = $this->common_model->db_insert('nd_pembayaran_piutang',$data);

			$post = (array)$this->input->post();
			$idx = 0;
			foreach ($post as $key => $value) {
				if (strpos($key, 'bayar_') !== false) {
					// echo $key.'-->'.$value.'<br/>';
					$data_get = explode('_', $key);
					if ($value != '' && $value != 0) {
						$penjualan_id[$idx] = $data_get[1];
						$idx++;
					}
				}elseif (strpos($key, 'piutang_') !== false) {
					// echo $key.'-->'.$value.'<br/>';
					$data_get = explode('_', $key);
					if ($value != '' && $value != 0) {
						$piutang_awal_id[$idx] = $data_get[1];
						$idx++;
					}
				}elseif (strpos($key, 'retur_') !== false) {
					// echo $key.'-->'.$value.'<br/>';
					$data_get = explode('_', $key);
					if ($value != '' && $value != 0) {
						$piutang_retur_id[$idx] = $data_get[1];
						$idx++;
					}
				}elseif (strpos($key, 'girotolak_') !== false) {
					// echo $key.'-->'.$value.'<br/>';
					$data_get = explode('_', $key);
					if ($value != '' && $value != 0) {
						$piutang_girotolak_id[$idx] = $data_get[1];
						$idx++;
					}
				}
				
			}

			//===========================

			// print_r($penjualan_id);
			$idx = 0;

			if (isset($penjualan_id)) {
				foreach ($penjualan_id as $key => $value) {
					$data_detail[$idx] = array(
						'pembayaran_piutang_id' => $result_id,
						'penjualan_id' => $value ,
						'amount' => str_replace('.', '', $post['bayar_'.$value]),
						'data_status' => 1
						);
					$idx++;
				}
			}

			if (isset($piutang_awal_id)) {
				foreach ($piutang_awal_id as $key => $value) {
					$data_detail[$idx] = array(
						'pembayaran_piutang_id' => $result_id,
						'penjualan_id' => $value ,
						'amount' => str_replace('.', '', $post['piutang_'.$value]),
						'data_status' => 2
						 );
					$idx++;
				}
			}

			if (isset($piutang_retur_id)) {
				foreach ($piutang_retur_id as $key => $value) {
					$data_detail[$idx] = array(
						'pembayaran_piutang_id' => $result_id,
						'penjualan_id' => $value ,
						'amount' => str_replace('.', '', $post['retur_'.$value]),
						'data_status' => 3
						 );
					$idx++;
				}
			}

			if (isset($piutang_girotolak_id)) {
				foreach ($piutang_girotolak_id as $key => $value) {
					$data_detail[$idx] = array(
						'pembayaran_piutang_id' => $result_id,
						'penjualan_id' => $value ,
						'amount' => str_replace('.', '', $post['girotolak_'.$value]),
						'data_status' => 4
						 );
					$idx++;
				}
			}

			
			$this->common_model->db_insert_batch('nd_pembayaran_piutang_detail',$data_detail);	
			$pembayaran_piutang_id = $result_id;	
		}else{

			$data = array(
			'pembayaran_type_id' => $ini->post('pembayaran_type_id'),
			'nama_bank' => $ini->post('nama_bank'),
			'no_rek_bank' => $ini->post('no_rek_bank'),
			'tanggal_transfer' => is_date_formatter($ini->post('tanggal_transfer')),
			'tanggal_giro' => is_date_formatter($ini->post('tanggal_giro')),
			'jatuh_tempo' => is_date_formatter($ini->post('jatuh_tempo')),
			'nama_penerima' => $ini->post('nama_penerima'),
			'keterangan' => $ini->post('keterangan'),
			'user_id' => is_user_id() );

			$this->common_model->db_update('nd_pembayaran_piutang',$data,'id',$pembayaran_piutang_id);
					
		}
		
		redirect(is_setting_link('finance/piutang_payment_form').'/?id='.$pembayaran_piutang_id);

	}

	function piutang_list_detail_add_faktur(){
		// print_r($this->input->post());
		$dt_get = explode('??', $this->input->post('penjualan_id'));
		$pembayaran_piutang_id = $this->input->post('pembayaran_piutang_id');



		$data = array(
			'pembayaran_piutang_id' => $pembayaran_piutang_id ,
			'penjualan_id' => $dt_get[0],
			'amount' => (float)$dt_get[1],
			'data_status' => 1 );

		// print_r($data);

		$this->common_model->db_insert('nd_pembayaran_piutang_detail', $data);
		redirect(is_setting_link('finance/piutang_payment_form').'/?id='.$pembayaran_piutang_id);

	}

	function update_tanggal_kontra_bon(){
		$pembayaran_piutang_id = $this->input->post('id');
		$data = array(
			'tanggal_kontra' => is_date_formatter($this->input->post('tanggal_kontra')) );
		$this->common_model->db_update("nd_pembayaran_piutang",$data,'id', $pembayaran_piutang_id);
		echo 'OK';
	}

	function predictive_urutan_giro(){
		// $tahun = date('Y');
		$tanggal = is_date_formatter($this->input->post('tanggal'));
		$tahun = date('Y', strtotime($tanggal));
		$urutan_giro = 1;
		foreach ($this->common_model->predictive_urutan_giro($tahun) as $row) {
			$urutan_giro = $row->urutan_giro + 1;
		}
		echo $urutan_giro;
	}

	function pembayaran_piutang_dp_update(){
		$pembayaran_piutang_id = $this->input->post('pembayaran_piutang_id');
		$tanggal_transfer = is_date_formatter($this->input->post('tanggal_transfer'));
		$post = (array)$this->input->post();
        $idx = 0;
        foreach ($post as $key => $value) {
            if (strpos($key, 'amount_') !== false) {
                // echo $key.'-->'.$value.'<br/>';
                $data_get = explode('_', $key);
                if ($value != '' && $value != 0) {
                    $dp_masuk_id[$idx] = $data_get[1];
                    $isi[$idx] = str_replace('.', '', $value);
                    $idx++;
                }
            }
            
        }


        foreach ($dp_masuk_id as $key => $value) {
            $data = array(
            	'tanggal_transfer' => $tanggal_transfer,
                'pembayaran_piutang_id' => $pembayaran_piutang_id ,
                'pembayaran_type_id' => 5,
                'dp_masuk_id' => $dp_masuk_id[$key],
                'amount' => $isi[$key],
                );

            $id = '';
            $get_id = $this->common_model->db_select("nd_pembayaran_piutang_nilai where pembayaran_piutang_id =".$pembayaran_piutang_id." AND pembayaran_type_id = 5 AND dp_masuk_id =".$value);
            foreach ($get_id as $row) {
                $id = $row->id;
            }

            // echo $id;
            // print_r($data);
            if ($id == '') {
                if ($isi[$key] != 0) {
                    $this->common_model->db_insert('nd_pembayaran_piutang_nilai', $data);
                }
            }else{
                $this->common_model->db_update('nd_pembayaran_piutang_nilai',$data, 'id', $id);
            }

        }

        redirect(is_setting_link('finance/piutang_payment_form').'/?id='.$pembayaran_piutang_id);

	}

	function update_bayar_piutang_detail(){
		$id = $this->input->post('id');
		$data = array(
			'amount' => $this->input->post('amount') );
		$this->common_model->db_update('nd_pembayaran_piutang_detail',$data,'id',$id);
		echo "OK";
	}

	function giro_urutan_initial(){
		$tahun = date('2019');
		$rank = 1;
		$get_list = $this->common_model->get_giro_list_by_year($tahun);
		foreach ($get_list as $row) {
			$data = array('urutan_giro' => $rank);
			if ($row->tipe==1) {
				$this->common_model->db_update('nd_pembayaran_piutang_nilai',$data,'id', $row->id);
			}else{
				$this->common_model->db_update('nd_dp_masuk',$data,'id', $row->id);
			}
			$rank++;
		}
		// $query = ";
		// 	UPDATE nd_pembayaran_piutang_nilai
		// 	SET urutan_giro=@rank:=@rank+1
		// 	WHERE YEAR(tanggal_transfer) ='$tahun'";
		// $this->common_model->db_free_query_superadmin($query);
	}

	function pembayaran_piutang_nilai_insert(){
		// print_r($this->input->post());
		$ini = $this->input;
		$pembayaran_piutang_id = $ini->post('pembayaran_piutang_id');
		$pembayaran_type_id = $ini->post('pembayaran_type_id');
		$urutan_giro = null;
		$tanggal = is_date_formatter($ini->post('tanggal_transfer'));
		$tahun = date('Y', strtotime($tanggal));

		if ($pembayaran_type_id == 2) {
			$urutan_giro = 1;
			$dt_last_giro = $this->common_model->get_last_urutan_giro($tahun);
			foreach ($dt_last_giro as $row) {
				$urutan_giro = $row->urutan_giro + 1;
			}
		}

		$data = array(
			'pembayaran_piutang_id' =>  $pembayaran_piutang_id,
			'pembayaran_type_id' =>  $ini->post('pembayaran_type_id'),
			'tanggal_transfer' => $tanggal,
			'nama_penerima' => ($ini->post('nama_penerima') != '' ? $ini->post('nama_penerima') : null),
			'nama_bank' => ($ini->post('nama_bank') != '' ? $ini->post('nama_bank') : null),
			'no_rek_bank'=> ($ini->post('no_rek_bank') != '' ?  $ini->post('no_rek_bank') : null),
			'no_giro' => ($ini->post('no_giro') != '' ? $ini->post('no_giro') : null) ,
			'no_akun_giro'=> ($ini->post('no_akun_giro') != '' ? $ini->post('no_akun_giro') : null),
			'urutan_giro' => $urutan_giro,
			'jatuh_tempo' => ($ini->post('jatuh_tempo') !='' && is_date_formatter($ini->post('jatuh_tempo')) != '0000-00-00' ? is_date_formatter($ini->post('jatuh_tempo')) : null),
			'amount' => str_replace('.', '', $ini->post('amount')),
			'keterangan' => $ini->post('keterangan'),
			'user_id' => is_user_id() );

		// print_r($data);
		$result_id = $this->common_model->db_insert('nd_pembayaran_piutang_nilai', $data);

		$pembayaran_piutang_detail_id_info = $this->input->post('pembayaran_piutang_detail_id_info');
		foreach ($pembayaran_piutang_detail_id_info as $key => $value) {
			$val = explode('??', $value);
			$data_detail[$key] = array(
				'pembayaran_piutang_nilai_id' => $result_id ,
				'pembayaran_piutang_detail_id' => $val[1],
				'penjualan_id' => $val[0] );
		}

		// print_r($this->input->post());
		if (isset($data_detail)) {
			$this->common_model->db_insert_batch('nd_pembayaran_piutang_nilai_info', $data_detail);
		}



		$this->session->set_flashdata('latest_payment_nilai', $result_id);

		redirect(is_setting_link('finance/piutang_payment_form').'/?id='.$pembayaran_piutang_id.'#bayar-section-'.$result_id);

	}

	function pembayaran_piutang_nilai_update(){
		// print_r($this->input->post());
		$ini = $this->input;
		$pembayaran_piutang_id = $ini->post('pembayaran_piutang_id');
		$id = $ini->post('pembayaran_piutang_nilai_id');
		$pembayaran_type_id = $ini->post('pembayaran_type_id');
		$tanggal = is_date_formatter($ini->post('tanggal_transfer'));
		$tahun = date('Y', strtotime($tanggal));
		$urutan_giro = ($ini->post('urutan_giro') == '' ? null : $ini->post('urutan_giro'));

		if ($pembayaran_type_id == 2) {
			$get_before_data = $this->common_model->db_select('nd_pembayaran_piutang_nilai WHERE id ='.$id);
			foreach ($get_before_data as $row) {
				if ($row->pembayaran_type_id != 2) {
                    $urutan_giro = 1;
					
					$dt_last_giro = $this->common_model->get_last_urutan_giro($tahun);
					foreach ($dt_last_giro as $row2) {
						$urutan_giro = $row2->urutan_giro + 1;
					}
				}
			}

		}

		$data = array(
			'pembayaran_piutang_id' =>  $pembayaran_piutang_id,
			'pembayaran_type_id' =>  $ini->post('pembayaran_type_id'),
			'tanggal_transfer' => is_date_formatter($ini->post('tanggal_transfer')),
			'nama_penerima' => ($ini->post('nama_penerima') != '' ? $ini->post('nama_penerima') : null),
			'nama_bank' => ($ini->post('nama_bank') != '' ? $ini->post('nama_bank') : null),
			'no_rek_bank'=> ($ini->post('no_rek_bank') != '' ?  $ini->post('no_rek_bank') : null),
			'no_giro' => ($ini->post('no_giro') != '' ? $ini->post('no_giro') : null) ,
			'no_akun_giro'=> ($ini->post('no_akun_giro') != '' ? $ini->post('no_akun_giro') : null),
			'urutan_giro' => $urutan_giro,
			// 'tanggal_giro' => is_date_formatter($ini->post('tanggal_giro')),
			'jatuh_tempo' => ($ini->post('jatuh_tempo') !='' && is_date_formatter($ini->post('jatuh_tempo')) != '0000-00-00' ? is_date_formatter($ini->post('jatuh_tempo')) : null),
			'amount' => str_replace('.', '', $ini->post('amount')),
			'keterangan' => $ini->post('keterangan'),
			'user_id' => is_user_id()
				);
		
		$pembayaran_piutang_detail_id_info = $this->input->post('pembayaran_piutang_detail_id_info');
		$this->common_model->db_delete('nd_pembayaran_piutang_nilai_info','pembayaran_piutang_nilai_id', $id);
		foreach ($pembayaran_piutang_detail_id_info as $key => $value) {
			$val = explode('??', $value);
			$data_detail[$key] = array(
				'pembayaran_piutang_nilai_id' => $id ,
				'pembayaran_piutang_detail_id' => $val[1],
				'penjualan_id' => $val[0] );
		}
		// print_r($data_detail);
		if (isset($data_detail)) {
			$this->common_model->db_insert_batch('nd_pembayaran_piutang_nilai_info', $data_detail);
		}
		$this->common_model->db_update("nd_pembayaran_piutang_nilai", $data,'id', $id);
		redirect(is_setting_link('finance/piutang_payment_form').'/?id='.$pembayaran_piutang_id);
	}

	function pembayaran_piutang_nilai_delete()
	{
		$id = $this->input->get('id');
		$this->common_model->db_delete('nd_pembayaran_piutang_nilai','id',$id);
		$this->common_model->db_delete('nd_pembayaran_piutang_nilai_info','pembayaran_piutang_nilai_id',$id);
		$pembayaran_piutang_id = $this->input->get('pembayaran_piutang_id');
		redirect(is_setting_link('finance/piutang_payment_form').'?id='.$pembayaran_piutang_id);
	}

	function update_pembulatan_piutang(){
		$id = $this->input->post('id');
		// print_r($this->input->post());
		$data = array(
			$this->input->post('kolom') => str_replace('.', '', $this->input->post('nilai')) );
		$this->common_model->db_update("nd_pembayaran_piutang", $data, "id", $id);
		echo "OK";

	}

	function update_pembayaran_nilai_by_mutasi(){
		$pembayaran_piutang_nilai_id = $this->input->post('id');
		$data = array(
			'amount' => str_replace(',', '', $this->input->post('amount')) );
		$this->common_model->db_update('nd_pembayaran_piutang_nilai',$data,'id',$pembayaran_piutang_nilai_id);
		echo 'OK';
	}

	function pembayaran_piutang_remove(){
		$pembayaran_piutang_id = $this->input->get('pembayaran_piutang_id');

		$this->common_model->db_delete('nd_pembayaran_piutang_detail', 'pembayaran_piutang_id',$pembayaran_piutang_id);
		$this->common_model->db_delete('nd_pembayaran_piutang_nilai','pembayaran_piutang_id',$pembayaran_piutang_id);
		$this->common_model->db_delete('nd_pembayaran_piutang','id',$pembayaran_piutang_id);
		redirect(is_setting_link('finance/piutang_payment'));
	}


//==============================daftar giro=================================================

	function giro_list(){
		$menu = is_get_url($this->uri->segment(1)) ;

		$tanggal_start = date('Y-m-01');
		$tanggal_end = date('Y-m-t');
		$tanggal_awal = '2019-01-01';
		$cond = '';
		$filter_type = '';

		if ($this->input->get('tanggal_start') && $this->input->get('tanggal_start') != '' && $this->input->get('tanggal_end') != '' ) {
			$tanggal_start = is_date_formatter($this->input->get('tanggal_start'));
			$tanggal_end = is_date_formatter($this->input->get('tanggal_end'));

			if (strtotime($tanggal_start) < strtotime($tanggal_awal)) {
				$tanggal_start = $tanggal_awal;
			}

			if (strtotime($tanggal_end) < strtotime($tanggal_awal)) {
				$tanggal_end = $tanggal_awal;
			}

			$filter_type = $this->input->get('filter_type');
			if ($filter_type != '') {
				if ($filter_type == 'a1') {
					$cond = "WHERE tanggal_setor is null";
				}else{
					$cond = "WHERE tanggal_setor is not null";
				}
			}
		}

		$data = array(
			'content' =>'admin/finance/giro_list',
			'breadcrumb_title' => 'Finance',
			'breadcrumb_small' => $menu[1],
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data'=> $this->data,
			'data_isi'=> $this->data,
			'tanggal_start' => is_reverse_date($tanggal_start),
			'tanggal_end' => is_reverse_date($tanggal_end),
			'filter_type' => $filter_type
			);

		$data['giro_setor_list'] = $this->fi_model->get_daftar_giro($tanggal_start, $tanggal_end, $tanggal_awal, $cond); 
		$this->load->view('admin/template',$data);
	}

	function giro_setor_initial(){
		// $this->common_model->db_bersihkan_tabel('nd_giro_urutan');

		// $tahun = $date('2018');
		$data_list = $this->fi_model->get_giro_setor_not_indexed('2020');
		// print_r($data_list);
		$idx = 1;
		foreach ($data_list as $row) {
			$dt_detail[$idx] = array(
				'source_table_id' => $row->id ,
				'data_type' => $row->data_type,
				'urutan' => $idx );
			$idx++;
		}

		print_r($data_list);
		// $this->common_model->db_insert_batch('nd_giro_urutan', $dt_detail);
	}

	function giro_setor_list(){
		$menu = is_get_url($this->uri->segment(1)) ;

		$tanggal_start = date('Y-m-01');
		$tanggal_end = date('Y-m-31');
		$cond = '';

		if ($this->input->get('tanggal_start') && $this->input->get('tanggal_end') ) {
			if ($this->input->get('tanggal_start') != '' && $this->input->get('tanggal_end') != '') {
				$tanggal_start = is_date_formatter($this->input->get('tanggal_start'));
				$tanggal_end = is_date_formatter($this->input->get('tanggal_end'));
				$cond  = "WHERE tanggal >= '".$tanggal_start."' AND tanggal <=".$tanggal_end."'";
			}
		}

		$data = array(
			'content' =>'admin/finance/giro_setor_list',
			'breadcrumb_title' => 'Finance',
			'breadcrumb_small' => 'Daftar Setoran Giro',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data'=> $this->data,
			'data_isi'=> $this->data,
			// 'tanggal_start' => is_reverse_date($tanggal_start),
			// 'tanggal_end' => is_reverse_date($tanggal_end)
			 );

		$data['giro_setor_list'] = $this->fi_model->get_giro_setor_list(); 
		$this->load->view('admin/template',$data);
	}

	function giro_setor_list_form(){
		$menu = is_get_url($this->uri->segment(1)) ;

		$tanggal_start = date('Y-m-01');
		$tanggal_end = date('Y-m-31');
		$toko_id = 1;

		$cond2 = 'WHERE toko_id = 1';
		$cond = '';
		$tanggal_setor = date('Y-m-d');

		if ($this->input->get('tanggal_start') && $this->input->get('tanggal_end') ) {
			if ($this->input->get('tanggal_start') != '' && $this->input->get('tanggal_end') != '') {
				$tanggal_start = is_date_formatter($this->input->get('tanggal_start'));
				$tanggal_end = is_date_formatter($this->input->get('tanggal_end'));
				$toko_id = $this->input->get('toko_id');
				$cond  = "AND jatuh_tempo >= '".$tanggal_start."' AND jatuh_tempo <='".$tanggal_end."'";
			}
		}

		$data = array(
			'content' =>'admin/finance/giro_setor_list_form',
			'breadcrumb_title' => 'Finance',
			'breadcrumb_small' => 'Formulir Setoran Giro',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data'=> $this->data,
			'data_isi'=> $this->data,
			'tanggal_start' => is_reverse_date($tanggal_start),
			'tanggal_end' => is_reverse_date($tanggal_end),
			'tanggal_setor' => is_reverse_date($tanggal_setor), 
			'toko_id' => $toko_id
			 );

		if ($this->input->get('id') && $this->input->get('id') != '') {
			$giro_setor_id = $this->input->get('id');
			$data['giro_data'] = $this->common_model->db_select('nd_giro_setor where id='.$giro_setor_id);
			$data['giro_list_detail'] = $this->fi_model->get_daftar_giro_setor($giro_setor_id);
		}else{
			$data['giro_data'] = array();
			$data['giro_list_detail'] = array();
		}

		$this->load->view('admin/template',$data);
	}

	function get_giro_data_search(){
		$urutan_giro = $this->input->post('urutan_giro');
		$tahun = $this->input->post('tahun');
		$result = $this->fi_model->get_daftar_giro_search($urutan_giro, $tahun);
		// print_r($result->result());
		if ($result->num_rows() > 0) {
	        echo json_encode( $result->result() );
		}else{
			echo "no data";
		}
	}

	function giro_list_add(){
		$giro_setor_id = $this->input->post('giro_setor_id');
		$giro_list_id = $this->input->post('giro_list_id');
		$data = array(
			'giro_setor_id' => $giro_setor_id ,
			'pembayaran_piutang_nilai_id' => $giro_list_id,
			'data_type' => $this->input->post('giro_list_type') 
			);

		// print_r($data);
		$this->common_model->db_insert('nd_giro_setor_detail', $data);
		redirect(is_setting_link('finance/giro_setor_list_form').'?id='.$giro_setor_id);

	}


	function giro_setor_insert(){
		$ini = $this->input;
		$giro_setor_id = $this->input->post('giro_setor_id');
		
		if ($giro_setor_id == '') {

			$data = array(
			'toko_id' => $ini->post('toko_id'),
			'keterangan' => $ini->post('keterangan'),
			'tanggal' => is_date_formatter($ini->post('tanggal')),
			'user_id' => is_user_id() );


			// print_r($data);

			$result_id = $this->common_model->db_insert('nd_giro_setor',$data);
			$giro_setor_id = $result_id;

		}else{
			$data = array(
			'toko_id' => $ini->post('toko_id'),
			'keterangan' => $ini->post('keterangan'),
			'tanggal' => is_date_formatter($ini->post('tanggal')),
			'user_id' => is_user_id() );
			// print_r($data);

			$this->common_model->db_update("nd_giro_setor", $data, 'id', $giro_setor_id);
		}
			
		$giro_list_id = $this->input->post('giro_list_id');
		$giro_list_type = $this->input->post('giro_list_type');
		$detail_id = $this->input->post('detail_id');
		// print_r($this->input->post());
		// print_r($this->input->post('giro_list_id'));



		$idx = 0;
		foreach ($giro_list_id as $key => $value) {
			if ($value != 0 && $detail_id[$key] == 0) {
				$dt_detail[$idx] = array(
					'giro_setor_id' => $giro_setor_id,
					'pembayaran_piutang_nilai_id' => $value,
					'data_type' => $giro_list_type[$key] );
				$idx++;
			}
		}
		// print_r($dt_detail);

		if (isset($dt_detail)) {
			$this->common_model->db_insert_batch('nd_giro_setor_detail',$dt_detail);
		}

		redirect(is_setting_link('finance/giro_setor_list_form').'/?id='.$giro_setor_id);
	}

	function giro_setor_detail_remove(){
		$id = $this->input->post('id');
		$giro_setor_id = $this->input->post('giro_setor_id');
		$this->common_model->db_delete('nd_giro_setor_detail', 'id', $id);
		// redirect(is_setting_link('finance/giro_setor_list_form').'?id='.$giro_setor_id);
		echo "OK";
	
	}

	// function update_setor_giro(){
	// 	$pembayaran_piutang_id = $this->input->post('pembayaran_piutang_id');
	// 	$data = array(
	// 		'tanggal_setor' => is_date_formatter($this->input->post('tanggal_setor'))
	// 		);

	// 	$this->common_model->db_update('nd_pembayaran_piutang',$data,'id',$pembayaran_piutang_id);
	// 	echo 'OK';
		
	// }

//==============================mutasi hutang=================================================

	function mutasi_hutang_list(){
		$menu = is_get_url($this->uri->segment(1)) ;

		$tanggal_start = date("Y-m-1"); 
		$tanggal_end = date("Y-m-t"); 
		$toko_id = 1;
		$supplier_id = '';

		if ($this->input->get('tanggal_start')) {
			// $tanggal = strto($this->input->get('tanggal'));
			$tanggal_start = is_date_formatter($this->input->get('tanggal_start'));
			$tanggal_end = is_date_formatter($this->input->get('tanggal_end'));
		
			// echo $tanggal;
			$toko_id = $this->input->get('toko_id');
		}

		$data = array(
			'content' =>'admin/finance/mutasi_hutang_list',
			'breadcrumb_title' => 'Finance',
			'breadcrumb_small' => 'Mutasi Hutang',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data'=> $this->data,
			'toko_id' => $toko_id,
			'tanggal_start' => is_reverse_date($tanggal_start),
			'tanggal_end' => is_reverse_date($tanggal_end)
			);


		$data['user_id'] = is_user_id();
		$data['mutasi_list'] = $this->fi_model->get_mutasi_hutang($tanggal_start, $tanggal_end, $toko_id);
		// echo $tanggal_start.' '.$tanggal_end;
		foreach ($data['mutasi_list'] as $row) {
			$data['bayar_list'][$row->supplier_id] = $this->fi_model->get_mutasi_hutang_bayar($row->supplier_id, $toko_id, $tanggal_start, $tanggal_end);
			// echo $data['bayar_list'][$row->supplier_id].'<br/>';
			// echo $row->supplier_id.'<br/>';
		}
		// $data['mutasi_list'] = $this->fi_model->get_mutasi_hutang_list($supplier_id, $tanggal_start, $tanggal_end); 
		$this->load->view('admin/template',$data);
	}

	function mutasi_hutang_list_detail(){
		$menu = is_get_url($this->uri->segment(1)) ;

		$tanggal = date("Y-m-01"); 
		$tanggal_end = date("Y-m-t");
		$toko_id = $this->input->get('toko_id');
		$supplier_id = $this->input->get('supplier_id');

		if ($this->input->get('tanggal_start') && $this->input->get('tanggal_start') != '' && $this->input->get('tanggal_end') != '' ) {
			$tanggal = is_date_formatter($this->input->get('tanggal_start'));
			$tanggal_end = is_date_formatter($this->input->get('tanggal_end'));
			$toko_id = $this->input->get('toko_id');
			$supplier_id = $this->input->get('supplier_id');
		}

		$supplier = $this->common_model->db_select('nd_supplier where id = '.$supplier_id);
		foreach ($supplier as $row) {
			$nama_supplier = $row->nama;
		}

		$data = array(
			'content' =>'admin/finance/mutasi_hutang_list_detail',
			'breadcrumb_title' => 'Finance',
			'breadcrumb_small' => 'Kartu Hutang',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data'=> $this->data,
			'supplier_id' => $supplier_id,
			'nama_supplier' => $nama_supplier,
			'toko_id' => $toko_id,
			'tanggal_start' => is_reverse_date($tanggal),
			'tanggal_end' => is_reverse_date($tanggal_end)
			);


		$data['user_id'] = is_user_id();
		if ($supplier_id != '') {
			$data['saldo_awal_list'] = $this->fi_model->get_mutasi_hutang_detail_saldo_awal($supplier_id, $toko_id, $tanggal); 
			$data['mutasi_list'] = $this->fi_model->get_mutasi_hutang_list_detail($supplier_id, $toko_id, $tanggal, $tanggal_end); 
		}else{
			$data['saldo_awal_list'] = array();
			$data['mutasi_list'] = array();
		}
		// echo $tanggal.'<br>';
		// echo $tanggal_end.'<br>';
		
		// print_r($this->input->get());
		$this->load->view('admin/template_no_sidebar',$data);
	}

	function mutasi_hutang_excel(){
		
		$tanggal_start = is_date_formatter($this->input->get('tanggal_start'));
		$tanggal_end = is_date_formatter($this->input->get('tanggal_end'));

		$tanggal_print_start = date('d F Y',strtotime($this->input->get('tanggal_start') ));
		$tanggal_print_end = date('d F Y',strtotime($this->input->get('tanggal_end') ));

		$bulan = date('F Y',strtotime($this->input->get('tanggal_start') ));
		$toko_id = $this->input->get('toko_id');

		$mutasi_list = $this->fi_model->get_mutasi_hutang($tanggal_start, $tanggal_end, $toko_id);
		foreach ($mutasi_list as $row) {
			$bayar_list[$row->supplier_id] = $this->fi_model->get_mutasi_hutang_bayar($row->supplier_id, $toko_id, $tanggal_start, $tanggal_end);
		}
		
		$this->load->library('Excel/PHPExcel');
		ini_set("memory_limit", "600M");

		/** Caching to discISAM*/
		$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_discISAM;
		$cacheSettings = array('');;

		PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);

		$objPHPExcel = new PHPExcel();

		$styleArray = array(
			'font'=>array(
				'bold'=>true,
				'size'=>12,
				)
			);

		
		$objPHPExcel->getActiveSheet()->mergeCells("A1:F1");
		$objPHPExcel->getActiveSheet()->mergeCells("A2:F2");

		$objPHPExcel->getActiveSheet()->setCellValue('A1', ' Mutasi Hutang ');
		$objPHPExcel->getActiveSheet()->setCellValue('A2', 'Bulan : '.$bulan);

		
		$objPHPExcel->getActiveSheet()->mergeCells("A4:A5");
		$objPHPExcel->getActiveSheet()->mergeCells("B4:B5");
		$objPHPExcel->getActiveSheet()->mergeCells("C4:C5");
		$objPHPExcel->getActiveSheet()->mergeCells("D4:D5");
		$objPHPExcel->getActiveSheet()->mergeCells("E4:E5");
		$objPHPExcel->getActiveSheet()->mergeCells("F4:I4");
		$objPHPExcel->getActiveSheet()->mergeCells("J4:J5");
		$objPHPExcel->getActiveSheet()->mergeCells("K4:K5");
		$objPHPExcel->getActiveSheet()->mergeCells("L4:L5");
		$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('A4', 'No')
		->setCellValue('B4', 'Nama Supplier')
		->setCellValue('C4', 'Saldo Awal')
		->setCellValue('D4', 'Pembelian')
		->setCellValue('E4', 'Retur (sudah kompensasi)')
		->setCellValue('F4', 'Pembayaran')
		->setCellValue('F5', 'Transfer')
		->setCellValue('G5', 'Giro Mundur')
		->setCellValue('H5', 'Cash')
		->setCellValue('I5', 'Pembulatan')
		->setCellValue('J4', 'Saldo Akhir')
		->setCellValue('K4', 'Retur (belum kompensasi)')
		;
	

		$row_no = 6;
		$idx = 1;
		foreach ($mutasi_list as $row) {
			$coll = "A";
			
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$idx);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(7);
			$coll++;

			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$row->nama_supplier);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(30);
			$coll++;


			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $row->amount - $row->amount_bayar);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;

			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $row->amount_beli);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;

			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $row->amount_retur);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;

			$bayar1 = 0;
			$bayar2 = 0;
			$bayar3 = 0;
			$bayar99 = 0;
			$total_bayar = 0;
			foreach ($bayar_list[$row->supplier_id] as $row2) {
				$total_bayar += $row2->bayar;
				if ($row2->pembayaran_type_id == 1) {
					$bayar1 = $row2->bayar;
				}

				if ($row2->pembayaran_type_id == 2) {
					$bayar2 = $row2->bayar;
				}
				if ($row2->pembayaran_type_id == 3) {
					$bayar3 = $row2->bayar;
				}
				if ($row2->pembayaran_type_id == 99) {
					$bayar99 = $row2->bayar;
				}
			}

			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $bayar1);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;

			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $bayar2);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;

			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $bayar3);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;

			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $bayar99);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;


			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$row->amount - $row->amount_bayar + $row->amount_beli - $total_bayar - $row->amount_retur );
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;

			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$row->amount_retur_belum);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;
			
			$row_no++;
			$idx++;

		}

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');

		//ob_end_clean();

		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header("Content-Disposition: attachment;filename=mutasi_hutang ".$bulan.".xls");
		header('Cache-Control: max-age=0');
		$objWriter->save('php://output');
	}

//==============================mutasi piutang=================================================
	function mutasi_piutang_list(){
		$menu = is_get_url($this->uri->segment(1)) ;

		$tanggal_start = date("Y-m-01"); 
		$tanggal_end = date("Y-m-t"); 
		$toko_id = 1;
		
		if ($this->input->get('tanggal_start')) {
			// $tanggal = strto($this->input->get('tanggal'));
			$tanggal_start = is_date_formatter($this->input->get('tanggal_start'));
			$tanggal_end = is_date_formatter($this->input->get('tanggal_end'));
			$toko_id = $this->input->get('toko_id');
		}

		$data = array(
			'content' =>'admin/finance/mutasi_piutang_list',
			'breadcrumb_title' => 'Finance',
			'breadcrumb_small' => 'Mutasi Piutang',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data'=> $this->data,
			'toko_id' => $toko_id,
			'tanggal_start' => is_reverse_date($tanggal_start),
			'tanggal_end' => is_reverse_date($tanggal_end)
		);


		$data['user_id'] = is_user_id();
		// if (is_posisi_id() == 1) {
			$data['mutasi_list'] = $this->fi_model->get_mutasi_piutang_2($tanggal_start, $tanggal_end, $toko_id); 
		// }else{
		// 	$data['mutasi_list'] = $this->fi_model->get_mutasi_piutang($tanggal_start, $tanggal_end, $toko_id); 
		// }
		// $data['mutasi_list'] = $this->fi_model->get_mutasi_piutang_saldo_awal_by_created($tanggal_start, $tanggal_end, $toko_id); 
		$customer_id_list = array();
		foreach ($data['mutasi_list'] as $row) {
			// $data['bayar_list'][$row->customer_id] = $this->fi_model->get_mutasi_piutang_bayar($row->customer_id, $toko_id, $tanggal_start, $tanggal_end);
			// $data['bayar_list_jual'][$row->customer_id] = $this->fi_model->get_bayar_penjualan($row->customer_id, $toko_id, $tanggal_start, $tanggal_end);
			// $data['pembayaran_pembulatan'][$row->customer_id] = $this->fi_model->get_pembulatan_piutang($row->customer_id, $toko_id, $tanggal_start, $tanggal_end);

			// echo $row->customer_id.'<br/> ';
			// $data['bayar_list'][$row->customer_id] = array();
			// $data['bayar_list_jual'][$row->customer_id] = array();
			// $data['pembayaran_pembulatan'][$row->customer_id] = array();
			if ($row->customer_id != '') {
				array_push($customer_id_list, $row->customer_id);
			}

		}

		$data['bayar_list'] = array();
		$data['bayar_list_jual'] = array();
		$data['pembayaran_pembulatan'] = array();

		$cust_list = implode(",", $customer_id_list);
		if ($cust_list != '') {
			$data['bayar_list'] = $this->fi_model->get_mutasi_piutang_bayar_all($cust_list, $toko_id, $tanggal_start, $tanggal_end);
			// $data['bayar_list_jual'] = $this->fi_model->get_bayar_penjualan_by_created($cust_list, $toko_id, $tanggal_start, $tanggal_end);
			$data['bayar_list_jual'] = array();
			$data['pembayaran_pembulatan'] = $this->fi_model->get_pembulatan_piutang_by_created($cust_list, $toko_id, $tanggal_start, $tanggal_end);
		}

		if (is_posisi_id() == 1) {
			// $data['bayar_list_jual'] = array();
			// echo $cust_list;
			// echo $cust_list.'<br/>'. $toko_id.'<br/>'. $tanggal_start.'<br/>'. $tanggal_end;
		}
		$data['pembayaran_type'] = $this->common_model->db_select('nd_pembayaran_type');
		
		if (is_posisi_id() == 1) {
			// print_r($data['bayar_list_jual']);
			// $this->output->enable_profiler(TRUE);
			$this->load->view('admin/template',$data);
		}else{
			$this->load->view('admin/template',$data);
			
		}
	}

	function mutasi_piutang_excel(){
		
		$tanggal_start = is_date_formatter($this->input->get('tanggal_start'));
		$tanggal_end = is_date_formatter($this->input->get('tanggal_end'));
		$toko_id = $this->input->get('toko_id');
		
		$tanggal_print_start = date('d F Y',strtotime($this->input->get('tanggal_start') ));
		$tanggal_print_end = date('d F Y',strtotime($this->input->get('tanggal_end') ));
		$bulan = date('F Y',strtotime($this->input->get('tanggal_start') ));
		$toko_id = $this->input->get('toko_id');


		$mutasi_list = $this->fi_model->get_mutasi_piutang_2($tanggal_start, $tanggal_end, $toko_id); 
		// $mutasi_list = $this->fi_model->get_mutasi_piutang($tanggal_start, $tanggal_end, $toko_id); 
		// foreach ($mutasi_list as $row) {
		// 	$bayar_list[$row->customer_id] = $this->fi_model->get_mutasi_piutang_bayar($row->customer_id, $toko_id, $tanggal_start, $tanggal_end);
		// 	$bayar_list_jual[$row->customer_id] = $this->fi_model->get_bayar_penjualan($row->customer_id, $toko_id, $tanggal_start, $tanggal_end);
		// 	// $bayar_list_jual[$row->customer_id] = array();
		// 	$pembayaran_pembulatan[$row->customer_id] = $this->fi_model->get_pembulatan_piutang($row->customer_id, $toko_id, $tanggal_start, $tanggal_end);
		
		// }

		$customer_id_list = array();
		foreach ($mutasi_list as $row) {
			array_push($customer_id_list, $row->customer_id);

		}

		$bayar_list = array();
		$bayar_list_jual = array();
		$pembayaran_pembulatan = array();

		$cust_list = implode(",", $customer_id_list);
		if ($cust_list != '') {
			$bayar_list = $this->fi_model->get_mutasi_piutang_bayar_all($cust_list, $toko_id, $tanggal_start, $tanggal_end);
			// $bayar_list_jual = $this->fi_model->get_bayar_penjualan_by_created($cust_list, $toko_id, $tanggal_start, $tanggal_end);
			$bayar_list_jual = array();
			$pembayaran_pembulatan = $this->fi_model->get_pembulatan_piutang_by_created($cust_list, $toko_id, $tanggal_start, $tanggal_end);
		}
		
		$pembayaran_type = $this->common_model->db_select('nd_pembayaran_type');

		foreach ($pembayaran_pembulatan as $row2) {
			$pmbltn[$row2->customer_id] = $row2->pembulatan;
		}
	
		$this->load->library('Excel/PHPExcel');
		ini_set("memory_limit", "600M");

		/** Caching to discISAM*/
		$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_discISAM;
		$cacheSettings = array('');;

		PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);

		$objPHPExcel = new PHPExcel();

		$styleArray = array(
			'font'=>array(
				'bold'=>true,
				'size'=>12,
				)
			);

		
		$objPHPExcel->getActiveSheet()->mergeCells("A1:F1");
		$objPHPExcel->getActiveSheet()->mergeCells("A2:F2");

		$objPHPExcel->getActiveSheet()->setCellValue('A1', ' Mutasi Piutang ');
		$objPHPExcel->getActiveSheet()->setCellValue('A2', 'tanggal : '.$tanggal_print_start.' sd '.$tanggal_print_end);

		
		$objPHPExcel->getActiveSheet()->mergeCells("A4:A5");
		$objPHPExcel->getActiveSheet()->mergeCells("B4:B5");
		$objPHPExcel->getActiveSheet()->mergeCells("C4:C5");
		$objPHPExcel->getActiveSheet()->mergeCells("D4:D5");
		$objPHPExcel->getActiveSheet()->mergeCells("E4:E5");
		$objPHPExcel->getActiveSheet()->mergeCells("F4:F5");
		$objPHPExcel->getActiveSheet()->mergeCells("G4:L4");
		$objPHPExcel->getActiveSheet()->mergeCells("M4:M5");
		

		$kolom = count($pembayaran_type) - 1;
		

		$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('A4', 'No')
		->setCellValue('B4', 'Nama Customer')
		->setCellValue('C4', 'Saldo Awal')
		->setCellValue('D4', 'Penjualan')
		->setCellValue('E4', 'Retur')
		->setCellValue('F4', 'Giro Tolakan')
		->setCellValue('G4', 'Pembayaran')
		->setCellValue('G5', 'DP')
		->setCellValue('H5', 'Cash')
		->setCellValue('I5', 'Edc')
		->setCellValue('J5', 'Transfer')
		->setCellValue('K5', 'Giro')
		->setCellValue('L5', 'Pembulatan')
		->setCellValue('M4', 'Saldo Akhir')
		;
	

		$row_no = 6;
		$idx = 1;
		foreach ($mutasi_list as $row) {

			$total = 0; $count = 0;

			$coll = "A";

			$total+=$row->amount - $row->amount_bayar + $row->penjualan - $row->retur_jual + $row->giro_tolakan;

			foreach ($pembayaran_type as $row2) {
				${"bayar".$row2->id} = 0;
			}

			/*piutang bayar tipe : 
			===piutang form===	===tipe db ===	
			1. Transfer 		4. Transfer
			2. GIRO 			-
			3. CASH 			2. CASH
			4. EDC 				3. EDC
			5. DP 				1. DP
			*/
			foreach ($bayar_list as $row2) {
				if ($row2->customer_id == $row->customer_id) {
					if ($row2->pembayaran_type_id == 1) {
						$bayar4 += $row2->bayar;
						$count++;
					}
	
					if ($row2->pembayaran_type_id == 2) {
						$bayar6 += $row2->bayar;
						$count++;
					}
	
					if ($row2->pembayaran_type_id == 3) {
						$bayar2 += $row2->bayar;
						$count++;
					}
					if ($row2->pembayaran_type_id == 4) {
						$bayar3 += $row2->bayar;
						$count++;
					}
	
					if ($row2->pembayaran_type_id == 5) {
						$bayar1 += $row2->bayar;
						$count++;
					}
				}
			}

			foreach ($bayar_list_jual as $row2) {
				if ($row2->customer_id == $row->customer_id) {
					if ($row2->pembayaran_type_id == 1) {
						$bayar1 += $row2->bayar;
					}

					if ($row2->pembayaran_type_id == 2) {
						$bayar2 += $row2->bayar;
					}
					if ($row2->pembayaran_type_id == 3) {
						$bayar3 += $row2->bayar;
					}
					if ($row2->pembayaran_type_id == 4) {
						$bayar4 += $row2->bayar;
					}
					// if ($row2->pembayaran_type_id == 5) {
					// 	$bayar5 += $row2->bayar;
					// }
					if ($row2->pembayaran_type_id == 6) {
						$bayar6 += $row2->amount;
					}
				}
			}

			$total_bayar = 0;
			foreach ($pembayaran_type as $row2) {
				$total_bayar += ${"bayar".$row2->id}; 
			}

			$sisa_jualan = $row->penjualan - $row->pembayaran_penjualan;


			if ($row->amount - $row->amount_bayar > 0 || $sisa_jualan > 0 || $count > 0 || $row->giro_tolakan > 0 ) { 
				
				$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$idx);
				$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(7);
				$coll++;

				$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$row->nama_customer);
				$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(30);
				$coll++;


				$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $row->amount - $row->amount_bayar);
				$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
				$coll++;

				$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $row->penjualan);
				$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
				$coll++;

				$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $row->retur_jual);
				$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
				$coll++;

				$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $row->giro_tolakan);
				$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
				$coll++;

				foreach ($pembayaran_type as $row2) { 
					if ($row2->id != 5) { 
						$total -= ${"bayar".$row2->id};

						$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, ${"bayar".$row2->id});
						$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
						$coll++;
					}	
				}

				
				$bulat = 0;
				if (isset($pmbltn[$row->customer_id])) {
					$total -= $pmbltn[$row->customer_id];	
					$bulat = $pmbltn[$row->customer_id];
				}
				$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $bulat);
				$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
				$coll++;
				// if ($pembayaran_pembulatan[$row->customer_id])) {
				// }else{
				// }


				$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$total );
				$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
				$coll++;
				$row_no++;
				$idx++;
				
			}
			

		}

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');

		//ob_end_clean();

		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header("Content-Disposition: attachment;filename=mutasi_piutang ".$tanggal_print_start.".xls");
		header('Cache-Control: max-age=0');
		$objWriter->save('php://output');
	}

	function mutasi_piutang_list_detail(){
		$menu = is_get_url($this->uri->segment(1)) ;

		$tanggal_start = date("Y-m-01"); 
		$tanggal_end = date("Y-m-t");
		$toko_id = '';
		$customer_id = '';

		if ($this->input->get('tanggal_start')) {
			// $tanggal = is_date_formatter($this->input->get('tanggal'));
			$tanggal_start = is_date_formatter($this->input->get('tanggal_start'));
			$tanggal_end = is_date_formatter($this->input->get('tanggal_end'));
			$toko_id = $this->input->get('toko_id');
			$customer_id = $this->input->get('customer_id');

		}

		$data = array(
			'content' =>'admin/finance/mutasi_piutang_list_detail',
			'breadcrumb_title' => 'Finance',
			'breadcrumb_small' => 'Kartu Piutang',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data'=> $this->data,
			'customer_id' => $customer_id,
			'toko_id' => $toko_id,
			'tanggal_start' => is_reverse_date($tanggal_start),
			'tanggal_end' => is_reverse_date($tanggal_end)
			);


		$data['user_id'] = is_user_id();
		if ($customer_id != '') {
			$data['saldo_awal_list'] = $this->fi_model->get_mutasi_piutang_saldo_awal($customer_id, $toko_id, $tanggal_start); 
			// $data['mutasi_list'] = $this->fi_model->get_mutasi_piutang_list_detail($customer_id, $toko_id, $tanggal_start, $tanggal_end); 
			// if (is_posisi_id () == 1) {
				# code...
				$data['mutasi_list'] = $this->fi_model->get_mutasi_piutang_list_detail_2($customer_id, $toko_id, $tanggal_start, $tanggal_end); 
			// }else{
			// 	$data['mutasi_list'] = $this->fi_model->get_mutasi_piutang_list_detail($customer_id, $toko_id, $tanggal_start, $tanggal_end); 
			// }
		}else{
			$data['saldo_awal_list'] = array();
			$data['mutasi_list'] = array();
		}
		// echo $tanggal.'<br>';
		// echo $tanggal_end.'<br>';
		$this->load->view('admin/template_no_sidebar',$data);
	}

	function mutasi_piutang_list_detail_excel(){

		
		$tanggal_start = $this->input->get('tanggal_start');
		$tanggal_end = $this->input->get('tanggal_end');
		$toko_id = $this->input->get('toko_id');
		$customer_id = $this->input->get('customer_id');

		$customer_data = $this->common_model->db_select("nd_customer where id = ".$customer_id);
		foreach ($customer_data as $row) {
			$nama_customer = $row->nama;
		}
		$saldo_awal_list = $this->fi_model->get_mutasi_piutang_saldo_awal($customer_id, $toko_id, $tanggal_start); 
		// $mutasi_list = $this->fi_model->get_mutasi_piutang_list_detail($customer_id, $toko_id, $tanggal_start, $tanggal_end); 
		$mutasi_list = $this->fi_model->get_mutasi_piutang_list_detail_2($customer_id, $toko_id, $tanggal_start, $tanggal_end); 
		
		
		$this->load->library('Excel/PHPExcel');
		ini_set("memory_limit", "600M");

		/** Caching to discISAM*/
		$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_discISAM;
		$cacheSettings = array('');;

		PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);

		$objPHPExcel = new PHPExcel();

		$styleArray = array(
			'font'=>array(
				'bold'=>true,
				'size'=>12,
				)
			);

		
		$objPHPExcel->getActiveSheet()->mergeCells("A1:F1");
		$objPHPExcel->getActiveSheet()->mergeCells("A2:F2");

		$objPHPExcel->getActiveSheet()->setCellValue('A1', ' Kartu Piutang '.$nama_customer);
		$objPHPExcel->getActiveSheet()->setCellValue('A2', 'tanggal : '.date('d F Y', strtotime($tanggal_start)).' sd '.date('d F Y', strtotime($tanggal_end)));

		
		$objPHPExcel->getActiveSheet()->mergeCells("A4:A5");
		$objPHPExcel->getActiveSheet()->mergeCells("B4:B5");
		$objPHPExcel->getActiveSheet()->mergeCells("C4:C5");
		$objPHPExcel->getActiveSheet()->mergeCells("D4:E4");
		$objPHPExcel->getActiveSheet()->mergeCells("F4:F5");
		
		

		$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('A4', 'No')
		->setCellValue('B4', 'Tanggal')
		->setCellValue('C4', 'Keterangan')
		->setCellValue('D4', 'Mutasi')
		->setCellValue('F4', 'Saldo')
		->setCellValue('D5', 'Total Bon')
		->setCellValue('E5', 'Pembayaran')
		;
	

		$row_no = 6;
		$idx = 1;
		$saldo = 0;
		foreach ($saldo_awal_list as $row) {

			$total = 0; $count = 0;
			$coll = "A";

			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$idx );
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(7);
			$coll++;

			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, is_reverse_date($tanggal_start) );
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;

			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, "SALDO AWAL" );
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(30);
			$coll++;

			$saldo += $row->saldo_awal;
			$objPHPExcel->getActiveSheet()->setCellValue("F".$row_no, $row->saldo_awal );
			$objPHPExcel->getActiveSheet()->getStyle('F'.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
			$coll++;

			$row_no++;
			$idx++;
				
			

		}

		foreach ($mutasi_list as $row) {

			$total = 0; $count = 0;
			$coll = "A";

			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$idx );
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(7);
			$coll++;

			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, is_reverse_date($row->tanggal) );
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;

			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, ($row->ket=='tolakan_giro' ? 'TOLAKAN GIRO :' : '').$row->no_faktur );
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(30);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setWrapText(true);
			$coll++;

			$saldo += $row->amount_jual;
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, ($row->amount_jual == 0 ? '' : $row->amount_jual) );
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;

			$saldo -= $row->amount_bayar;
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, ($row->amount_bayar == 0 ? '' : $row->amount_bayar) );
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;

			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $saldo );
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;

			$row_no++;
			$idx++;
				
			

		}


		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');

		//ob_end_clean();

		$cek =array(',','.');
		$nama_customer = str_replace($cek, '', $nama_customer);

		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header("Content-Disposition: attachment;filename=kartu_piutang_".$nama_customer."_".date('dmY', strtotime($tanggal_start))."sd".date('dmY', strtotime($tanggal_end)).".xls");
		header('Cache-Control: max-age=0');
		$objWriter->save('php://output');
	}

//==============================giro tolakan=================================================

	function giro_tolakan_list(){
		$menu = is_get_url($this->uri->segment(1)) ;

		$data = array(
			'content' =>'admin/finance/giro_tolakan_list',
			'breadcrumb_title' => 'Finance',
			'breadcrumb_small' => 'Giro Tolakan',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data'=> $this->data,
			);

		$data['user_id'] = is_user_id();
		$data['giro_tolakan_list'] = $this->fi_model->giro_tolakan_list();
		// if (is_posisi_id() != 1) {
		// 	$data['content'] = 'admin/under_construction_with_time';
		// }
		$this->load->view('admin/template',$data);
	}

	function get_giro_list(){
		$cond = "";
		$customer_id = $this->input->post("customer_id");
		$cond = ($customer_id == "" ? "" : "AND customer_id=".$customer_id );
		$data = $this->fi_model->get_giro_list_by_customer($cond);
		echo json_encode($data);
	}

	function giro_tolakan_update(){
		$giro_tolakan_id = $this->input->post('giro_tolakan_id');
		$data = array(
			'pembayaran_piutang_nilai_id' => $this->input->post('pembayaran_piutang_nilai_id') ,
			'customer_id' => $this->input->post('customer_id') ,
			'tanggal' => is_date_formatter($this->input->post('tanggal')),
			'keterangan' => $this->input->post('keterangan') ,
			'user_id' => is_user_id()
			);
		// print_r($data);
		if ($giro_tolakan_id == '') {
			$this->common_model->db_insert("nd_giro_tolakan", $data);
		}else{
			$this->common_model->db_update("nd_giro_tolakan", $data,'id', $giro_tolakan_id);
		}
		redirect(is_setting_link("finance/giro_tolakan_list"));
	}

	function giro_tolakan_remove(){
		$id = $this->input->post('id');
		$this->common_model->db_delete("nd_giro_tolakan",'id',$id);
		echo "OK";
	}



}