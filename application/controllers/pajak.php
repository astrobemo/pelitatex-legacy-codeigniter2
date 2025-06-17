<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pajak extends CI_Controller {

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
		$this->load->model('pajak_model','pjk_model',true);
		
		//======================data aktif section===========================
		
		$this->supplier_list_aktif = $this->common_model->db_select('nd_supplier where status_aktif = 1');
		$this->customer_list_aktif = $this->common_model->db_select('nd_customer where status_aktif = 1');
		$this->toko_list_aktif = $this->common_model->db_select('nd_toko where status_aktif = 1');
		
		$this->gudang_list_aktif = $this->common_model->db_select('nd_gudang where status_aktif = 1  ORDER BY urutan asc');

		$this->warna_list_aktif = $this->common_model->db_select('nd_warna where status_aktif = 1 order by warna_jual asc');
		$this->barang_list_aktif = $this->common_model->get_barang_list_aktif();
		$this->satuan_list_aktif = $this->common_model->db_select('nd_satuan where status_aktif = 1');

		// $this->output->enable_profiler(TRUE);
	   	$this->pre_faktur = get_pre_faktur();
	   	$this->piutang_warn = get_piutang_warn();
	   	
		

	}

//============================ no faktur pajak=================================================

	function no_fp_list(){
		$menu = is_get_url($this->uri->segment(1)) ;

		$data = array(
			'content' =>'admin/pajak/no_faktur_pajak_list',
			'breadcrumb_title' => 'Finance',
			'breadcrumb_small' => 'Daftar No Faktur Pajak',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data'=> $this->data,
			'data_isi'=> $this->data );


		$data['user_id'] = is_user_id();
		$data['fp_list'] = $this->pjk_model->get_no_faktur_pajak_list();
		$this->load->view('admin/template',$data);
	}

	function no_fp_insert(){
		$tahun_pajak = $this->input->post('tahun_pajak');
		if ($tahun_pajak == '') {
			$tahun_pajak = substr(is_date_formatter($this->input->post('tanggal')), 0,4);
		}
		$data = array(
			'tanggal' => is_date_formatter($this->input->post('tanggal')) ,
			'tahun_pajak' =>$this->input->post('tahun_pajak').'-01-01',
			'no_fp_awal' => $this->input->post('no_fp_awal') ,
			'no_fp_akhir' => $this->input->post('no_fp_akhir') ,
			'user_id' => is_user_id()
			);

		$this->common_model->db_insert("nd_no_faktur_pajak", $data);
		redirect(is_setting_link('pajak/no_fp_list'));
	}

	function no_fp_list_detail(){
		$menu = is_get_url($this->uri->segment(1)) ;
		$no_faktur_pajak_id = $this->input->get('id');
		// $this->common_model->

		$data = array(
			'content' =>'admin/pajak/no_faktur_pajak_list_detail',
			'breadcrumb_title' => 'Finance',
			'breadcrumb_small' => 'Daftar No Faktur Pajak',
			'nama_menu' => $menu[0],
			'no_faktur_pajak_id' => $no_faktur_pajak_id,
			'nama_submenu' => $menu[1],
			'common_data'=> $this->data,
			'data_isi'=> $this->data );


		$data['user_id'] = is_user_id();
		$data['fp_list'] = $this->pjk_model->get_no_faktur_pajak_list_detail($no_faktur_pajak_id);
		$data['fp_link_list'] = $this->pjk_model->get_rekam_faktur_pajak_detail_by_no($no_faktur_pajak_id);
		$this->load->view('admin/template',$data);
	}

	function check_no_fp_digit(){
		$get_data = $this->common_model->db_select("nd_rekam_faktur_pajak_detail");
		$idx = 0;
		foreach ($get_data as $row) {
			$fp_raw = explode('.', $row->no_faktur_pajak);
			if (strlen(end($fp_raw)) < 8 ) {
				$new_fp = str_pad(end($fp_raw), 8,'0', STR_PAD_LEFT);
				$sliced = array_slice($fp_raw, 0, -1);
				$new_fp_w[$idx] = implode('.', $sliced).'.'.$new_fp;
				$row_id[$idx] = $row->id;
				$idx++;
			}
		}

		$case_fp = "CASE
			";
		foreach ($new_fp_w as $key => $value) {
			$case_fp .="WHEN id=".$row_id[$key]." THEN '".$value."'
			";
		}

		/*$this->common_model->db_free_query_superadmin("UPDATE nd_rekam_faktur_pajak_detail
			SET no_faktur_pajak = 
			$case_fp
			ELSE no_faktur_pajak
			END");*/ 
	}

	function check_no_fp_baru(){
		$no_fp_awal = $this->input->post('no_fp_awal');
		$no_fp_akhir = $this->input->post('no_fp_akhir');

		$where = "WHERE no_fp_awal IN ('".$no_fp_awal."','".$no_fp_akhir."') OR no_fp_akhir IN ('".$no_fp_awal."','".$no_fp_akhir."') ";
		$cek = $this->common_model->db_select_num_rows("nd_no_faktur_pajak $where");
		echo (!$cek ? 'OK' : 'NO');
	}

	function insert_fp_manual(){
		$id = $this->input->post('id');
		$no_faktur_pajak_id = $this->input->post('no_faktur_pajak_id');
		$data = array(
			'rekam_faktur_pajak_id' => 0 ,
			'penjualan_id' => 0,
			'no_faktur_pajak' => $this->input->post('no_faktur_pajak'),
			'no_faktur_pajak_id' => $no_faktur_pajak_id,
			'keterangan' => $this->input->post('keterangan'),
			);
		if ($id == '') {
			$this->common_model->db_insert('nd_rekam_faktur_pajak_detail', $data);
		}else{
			$this->common_model->db_update('nd_rekam_faktur_pajak_detail', $data,'id',$id);
		}
		redirect(is_setting_link('pajak/no_fp_list_detail').'?id='.$no_faktur_pajak_id);

	}

//============================rekam faktur pajak=================================================

	function rekam_faktur_pajak_list(){
		$menu = is_get_url($this->uri->segment(1)) ;

		$data = array(
			'content' =>'admin/pajak/rekam_faktur_pajak_list',
			'breadcrumb_title' => 'Pajak',
			'breadcrumb_small' => 'Daftar Faktur',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data'=> $this->data,
			'data_isi'=> $this->data );


		$last_date = '2019-08-04';
		$get_last_date = $this->common_model->db_free_query_superadmin("SELECT *
			FROM (
				SELECT max(t2.tanggal) as last_date
				FROM nd_rekam_faktur_pajak_detail t1
				LEFT JOIN  nd_penjualan t2
				ON t1.penjualan_id = t2.id ) result
			WHERE last_date is not null
			");
		if (count($get_last_date->result()) > 0) {
			foreach ($get_last_date->result() as $row) {
				$last_date = $row->last_date;
			}
		}

		$data['submitted_count'] = $this->pjk_model->get_submitted_pajak_list();
		$data['user_id'] = is_user_id();
		$data['last_date'] = $last_date;
		$data['fp_list'] = $this->pjk_model->get_rekam_faktur_pajak_list('','');
		// if (is_posisi_id() == 1) {
		// 	print_r($get_last_date->result()) ;
		// 	echo $last_date;
		// }else{
			$this->load->view('admin/template',$data);
		// }
		if (is_posisi_id() == 1) {
			$this->output->enable_profiler(TRUE);
		}
	}

	function rekam_faktur_pajak_insert(){
		

		$rekam_faktur_pajak_id = $this->input->post('rekam_faktur_pajak_id');
		if ($rekam_faktur_pajak_id != '') {
			$this->common_model->db_delete("nd_rekam_faktur_pajak_detail","rekam_faktur_pajak_id", $rekam_faktur_pajak_id);
		}
		$tanggal_start = is_date_formatter($this->input->post('tanggal_start'));
		$tahun = substr($tanggal_start, 0,4);
		$tanggal_end = is_date_formatter($this->input->post('tanggal_end'));

		$get_penjualan = $this->pjk_model->get_faktur_to_rekam($tanggal_start, $tanggal_end);

		$get_available_faktur = $this->pjk_model->get_available_no_faktur_pajak($tahun);

		$sisa_total = 0; $idx = 0;
		foreach ($get_available_faktur as $row) {
			$sisa_total += $row->sisa;
			$id_[$idx] = $row->id;
			$sisa[$idx] = $row->sisa;
			$terpakai[$idx] = $row->jml;
			$no_fp_awal[$idx] = $row->no_fp_awal;
			$no_fp_get = explode('.', $row->no_fp_awal);
			$pre_fp[$idx] = str_replace(end($no_fp_get), '', $row->no_fp_awal);
			$no_fp_now[$idx] = end($no_fp_get) + $row->jml;
			$no_fp_akhir[$idx] = $row->no_fp_akhir;
			$idx++;
		}

		if(count($get_penjualan) == 0){
			echo "
				<div style='text-align:center'>
					<h1>Tidak ada faktur yang dapat direkam
					</h1>
					<a href='".base_url().is_setting_link('pajak/rekam_faktur_pajak_list')."' style='font-size:2em' >KEMBALI</a>
				</div>
				";
		}else if ($sisa_total >=  count($get_penjualan)) {

			$idx_f = 0;
			for ($i=0; $i < count($get_penjualan) ; $i++) { 
				if ($sisa[$idx_f] <= 0) {
					$idx_f++;
				}
				$no_fp_now[$idx_f] = str_pad($no_fp_now[$idx_f], 8,'0', STR_PAD_LEFT);
				if ($fp_awal == '') {
					$fp_awal = $pre_fp[$idx_f].$no_fp_now[$idx_f];
				}
				
				$fp_akhir = $pre_fp[$idx_f].$no_fp_now[$idx_f];
				$no_fp_set[$i] = $pre_fp[$idx_f].$no_fp_now[$idx_f];
				$no_fp_id[$i] = $id_[$idx_f];
				$no_fp_now[$idx_f]++;
				$sisa[$idx_f]--;
				// $no_fp[$i] = 
			}

			$data = array(
				"tanggal_awal" => $tanggal_start,
				"tanggal_akhir" => $tanggal_end,
				"no_fp_awal"=> $fp_awal,
				"no_fp_akhir"=> $fp_akhir,
				"jumlah_trx" => count($get_penjualan),
				'user_id' => is_user_id() );


			// print_r($no_fp_set);
			if ($rekam_faktur_pajak_id == '') {
				$result_id = $this->common_model->db_insert('nd_rekam_faktur_pajak',$data);
				$rekam_faktur_pajak_id = $result_id;
			}
			
			$idx = 0 ;
			foreach ($get_penjualan as $row) {
				$dt_detail[$idx] = array(
					'rekam_faktur_pajak_id' => $rekam_faktur_pajak_id,
					'penjualan_id' => $row->id,
					'tanggal'=> $row->tanggal,
					'customer_id'=> $row->customer_id,
					'no_faktur_pajak' => $no_fp_set[$idx],
					'no_faktur_pajak_id' => $no_fp_id[$idx],
					"nama_customer" => $row->nama_customer,
					"no_faktur_jual" => $row->no_faktur_lengkap,
					"ppn_berlaku" => $row->ppn_berlaku_now,
					"total_jual" => $row->g_total,
					"total_ppn" => $row->g_total * ($row->ppn_berlaku_now/100),
					'penjualan_id' => $row->id,
					'alamat_lengkap' => $row->alamat_cust_fp,
					'no_npwp' => ($row->npwp_cust_fp == '' ? null : $row->npwp_cust_fp),
					'no_nik' => ($row->nik_cust_fp == '' ? null : $row->nik_cust_fp),
				);
				// print_r($dt_detail[$idx]);
				// echo '<hr/>';
				$idx++;
			}

			$this->common_model->db_insert_batch("nd_rekam_faktur_pajak_detail", $dt_detail);
			redirect(is_setting_link('pajak/rekam_faktur_pajak_detail').'?id='.$rekam_faktur_pajak_id);
		}else{
			echo "
				<div style='text-align:center'>
					<h1>Jumlah Faktur Lebih banyak dari nomor Faktur yang tersedia, <br/>
						Mohon input no faktur baru terlebih dahulu
					</h1>
					<p>
						SISA NOMOR FAKTUR :$sisa_total
					</p>
					<p>
						FP HINGGA ".date('Y-m-d', strtotime('-1 day')) .":".count($get_penjualan)."
					</p>
					<a href='".base_url().is_setting_link('pajak/rekam_faktur_pajak_list')."' style='font-size:2em' >KEMBALI</a>
				</div>
				";
		}
	}

	function rekam_faktur_pajak_coretax_insert(){
		
		$tanggal_start = is_date_formatter($this->input->post('tanggal_start'));
		$tahun = substr($tanggal_start, 0,4);
		$tanggal_end = is_date_formatter($this->input->post('tanggal_end'));

		$get_penjualan = $this->pjk_model->get_faktur_to_rekam($tanggal_start, $tanggal_end);

		$sisa_total = 0; $idx = 0;

		if(count($get_penjualan) == 0){
			echo "
				<div style='text-align:center'>
					<h1>Tidak ada faktur yang dapat direkam
					</h1>
					<a href='".base_url().is_setting_link('pajak/rekam_faktur_pajak_list')."' style='font-size:2em' >KEMBALI</a>
				</div>
				";
		}else{

			$data = array(
				"tanggal_awal" => $tanggal_start,
				"tanggal_akhir" => $tanggal_end,
				"no_fp_awal"=> $fp_awal,
				"no_fp_akhir"=> $fp_akhir,
				"jumlah_trx" => count($get_penjualan),
				'user_id' => is_user_id() );


			// print_r($no_fp_set);
			$result_id = $this->common_model->db_insert('nd_rekam_faktur_pajak',$data);
			$rekam_faktur_pajak_id = $result_id;
			
			$idx = 0 ;
			foreach ($get_penjualan as $row) {
				$dt_detail[$idx] = array(
					'rekam_faktur_pajak_id' => $rekam_faktur_pajak_id,
					'penjualan_id' => $row->id,
					'tanggal'=> $row->tanggal,
					'customer_id'=> $row->customer_id,
					'no_faktur_pajak' => "",
					'no_faktur_pajak_id' => 0,
					"nama_customer" => $row->nama_cust_fp,
					"no_faktur_jual" => $row->no_faktur_lengkap,
					"ppn_berlaku" => $row->ppn_berlaku_now,
					"total_jual" => $row->g_total,
					"total_ppn" => $row->g_total * ($row->ppn_berlaku_now/100),
					'penjualan_id' => $row->id,
					'alamat_lengkap' => $row->alamat_cust_fp,
					'no_npwp' => ($row->npwp_cust_fp == '' ? null : $row->npwp_cust_fp),
					'no_nik' => ($row->nik_cust_fp == '' ? null : $row->nik_cust_fp),
				);
				// print_r($dt_detail[$idx]);
				// echo '<hr/>';
				$idx++;
			}

			$this->common_model->db_insert_batch("nd_rekam_faktur_pajak_detail", $dt_detail);
			redirect(is_setting_link('pajak/rekam_faktur_pajak_detail').'?id='.$rekam_faktur_pajak_id);
		}
	}

	function rekam_faktur_pajak_gunggung_insert(){
		
		$tanggal_start = is_date_formatter($this->input->post('tanggal_start'));
		$tanggal_end = is_date_formatter($this->input->post('tanggal_end'));

		$data = array(
			"is_gunggung" => 1,
			"tanggal_awal" => $tanggal_start,
			"tanggal_akhir" => $tanggal_end,
			"no_fp_awal"=> "",
			"no_fp_akhir"=> "",
			"jumlah_trx" => 0,
			'user_id' => is_user_id() );


		// print_r($no_fp_set);
		$result_id = $this->common_model->db_insert('nd_rekam_faktur_pajak',$data);
		$rekam_faktur_pajak_id = $result_id;
		
		redirect(is_setting_link('pajak/rekam_faktur_pajak_detail').'?id='.$rekam_faktur_pajak_id);
	
	}

	function rekam_tambah_faktur_gunggung(){
		$rekam_faktur_pajak_id = $this->input->post('rekam_faktur_pajak_id');
		$penjualan_id = $this->input->post('penjualan_id');
		$get_penjualan = $this->pjk_model->get_faktur_to_rekam_satuan($penjualan_id);
		$dt_detail = null;


		foreach ($get_penjualan as $row) {

			$no_npwp = $row->no_cust_fp;
			$no_nik = $row->nik_cust_fp;

			if($no_npwp == '' && $no_nik == ''){
				$no_npwp = null;
				$no_nik = "0000000000000000";
			}


			$dt_detail = array(
				'rekam_faktur_pajak_id' => $rekam_faktur_pajak_id,
				'penjualan_id' => $row->id,
				'tanggal'=> $row->tanggal,
				'customer_id'=> $row->customer_id,
				'no_faktur_pajak_id' => 0,
				"nama_customer" => $row->nama_customer,
				"no_faktur_jual" => $row->no_faktur_lengkap,
				"ppn_berlaku" => $row->ppn_berlaku_now,
				"total_jual" => $row->g_total,
				"total_ppn" => 0,
				'penjualan_id' => $row->id,
				'alamat_lengkap' => $row->alamat_cust_fp,
				'no_npwp' => $no_npwp,
				'no_nik' => $no_nik,
			);
		}

		if ($dt_detail != null) {
			$this->common_model->db_insert("nd_rekam_faktur_pajak_detail", $dt_detail);
		}	


		redirect(is_setting_link('pajak/rekam_faktur_pajak_detail').'?id='.$rekam_faktur_pajak_id);

	}

	function rekam_faktur_pajak_remove(){
		$id = $this->input->post("id");
		$this->common_model->db_delete("nd_rekam_faktur_pajak", "id", $id);
		$this->common_model->db_delete("nd_rekam_faktur_pajak_detail", "rekam_faktur_pajak_id", $id);
		echo "OK";
	}

	function rekam_faktur_pajak_detail(){
		$menu = is_get_url($this->uri->segment(1)) ;

		$id = $this->input->get('id');
		$filter = 0; $cond='';
		if ($this->input->get('filter')) {
			if ($this->input->get('filter') == 1) {
				$filter = 1;
				$cond = "WHERE npwp = '' AND nik = '' ";
			}
		}

		// 1. e-faktur
		// 2. coretax
		
		$taxType = 2;
		$isGunggung = false;
		
		$data_rekam_faktur = $this->pjk_model->get_data_rekam_faktur($id);

		$tahun = date('Y');
		foreach ($data_rekam_faktur as $row) {
			$tahun = strtotime("Y", strtotime($row->tanggal_awal));
			if($tahun < 2025){
				$taxType = 1;
			}

			if($row->is_gunggung == 1){
				$isGunggung = true;
			}
			$tanggal_start = $row->tanggal_awal;
			$tanggal_end = $row->tanggal_akhir;
		}
		$content = ($taxType == 1 ? 'rekam_faktur_pajak_detail':'rekam_faktur_pajak_detail_coretax');
		$content = ($isGunggung ? 'rekam_faktur_pajak_detail_gunggung':$content);

		$data = array(
			'content' =>'admin/pajak/'.$content,
			'breadcrumb_title' => 'Pajak',
			'breadcrumb_small' => 'Daftar Faktur',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data'=> $this->data,
			'data_isi'=> $this->data,
			'data_rekam_faktur' => $data_rekam_faktur,
			'rekam_faktur_pajak_id' => $id,
			'filter' => $filter
		);

		if(!$isGunggung){

			foreach ($this->pjk_model->get_range_faktur($id) as $row) {
				$tanggal_start = $row->tanggal_start;
				$tanggal_end = $row->tanggal_end;
			}
	
			foreach ($data['data_rekam_faktur'] as $row) {
				$tanggal_start = $row->tanggal_start;
				$tanggal_end = $row->tanggal_end;
			}
		}

		$data['fp_list_detail'] = $this->pjk_model->get_rekam_faktur_pajak_detail($id, $cond);
		$data['faktur_tambahan'] = [];

		if($isGunggung){
			$data['faktur_tambahan'] = $this->pjk_model->get_rekam_faktur_belum_no_fp($tanggal_start, $tanggal_end);
		}
		
		$data['no_fp'] = $this->common_model->db_select('nd_no_faktur_pajak');
		
		// $data['faktur_list'] = $this->pjk_model->get_faktur_to_rekam();
		$this->load->view('admin/template',$data);
		if (is_posisi_id() == 1) {
			$this->output->enable_profiler(TRUE);
		}
	}

	function rekam_tambah_faktur(){
		$rekam_faktur_pajak_id = $this->input->post('rekam_faktur_pajak_id');
		$get_latest_rekam_faktur_pajak = $this->common_model->db_select("nd_rekam_faktur_pajak_detail ORDER BY id desc limit 1");
		foreach ($get_latest_rekam_faktur_pajak as $row) {
			$no_faktur_pajak_latest = $row->no_faktur_pajak;
			$no_faktur_pajak_id = $row->no_faktur_pajak_id;
		}

		$no_fp_break = explode('.', $no_faktur_pajak_latest);
		$no_fp = substr(end($no_fp_break), -5)+1;

		$get_no_faktur = $this->common_model->db_select("nd_no_faktur_pajak where id=".$no_faktur_pajak_id);
		foreach ($get_no_faktur as $row) {
			$no_fp_rekam_break = explode('.', $row->no_fp_akhir);
		}

		$no_fp_akhir = substr(end($no_fp_rekam_break), -5);


		$penjualan_id = $this->input->post('penjualan_id');
		if ($penjualan_id != '') {
			$data = array(
				'rekam_faktur_pajak_id' => $rekam_faktur_pajak_id ,
				'penjualan_id' => $penjualan_id );
		}


		// redirect(is_setting_link('pajak/rekam_faktur_pajak_detail').'?id='.$rekam_faktur_pajak_id);

	}

	function rekalkulasi_no_pajak(){
		// print_r($this->input->post());
		$rekam_faktur_pajak_id = $this->input->post('id');
		$no_start = $this->input->post('no_start');
		$get_first_data = $this->common_model->db_select("nd_rekam_faktur_pajak_detail where rekam_faktur_pajak_id =".$rekam_faktur_pajak_id." ORDER BY id asc limit 1");
		foreach ($get_first_data as $row) {
			$fktr_data = explode('.', $row->no_faktur_pajak);
			$no_faktur_awal = end($fktr_data);
		}
		$selisih = $no_start - $no_faktur_awal;
		if ($selisih != 0) {
			$case_fp = "CASE
			";
			foreach ($this->common_model->db_select("nd_rekam_faktur_pajak_detail WHERE rekam_faktur_pajak_id = ".$rekam_faktur_pajak_id) as $row) {
				$fktr_data = explode('.', $row->no_faktur_pajak);
				$no_update = $fktr_data[2]+$selisih;
				$case_fp .="WHEN id=".$row->id." THEN '".$fktr_data[0].'.'.$fktr_data[1].'.'.str_pad($no_update, 8,'0', STR_PAD_LEFT)."' 
				";
			}
			$this->common_model->db_free_query_superadmin("UPDATE nd_rekam_faktur_pajak_detail
				SET no_faktur_pajak = 
				$case_fp
				ELSE no_faktur_pajak
				END
				 ");
		}
		redirect(is_setting_link('pajak/rekam_faktur_pajak_detail').'?id='.$rekam_faktur_pajak_id);

	}

	function rekalkulasi_no_pajak_dari_awal(){
		// print_r($this->input->post());
		$rekam_faktur_pajak_id = $this->input->get('id');

		$get_first_data = $this->common_model->db_select("nd_rekam_faktur_pajak_detail where rekam_faktur_pajak_id =".$rekam_faktur_pajak_id." ORDER BY id asc limit 1");
		foreach ($get_first_data as $row) {
			$fktr_data = explode('.', $row->no_faktur_pajak);
			$no_faktur_awal = end($fktr_data);
		}

		$no_faktur = $no_faktur_awal;
		$get_all = $this->common_model->db_select("nd_rekam_faktur_pajak_detail where rekam_faktur_pajak_id=".$rekam_faktur_pajak_id);
		foreach ($get_all as $row) {
			$data = array(
				'no_faktur_pajak' => $fktr_data[0].'.'.$fktr_data[1].'.'.$no_faktur);
			$this->common_model->db_update("nd_rekam_faktur_pajak_detail",$data,"id",$row->id);
			$no_faktur++;
		}
		redirect(is_setting_link('pajak/rekam_faktur_pajak_detail').'?id='.$rekam_faktur_pajak_id);

	}

	function rekam_faktur_pajak_detail_insert(){
		$rekam_faktur_pajak_id = $this->input->post('rekam_faktur_pajak_id');
		$penjualan_id_data = $this->input->post('penjualan_id_data');
		$idx = 0;
		foreach ($penjualan_id_data as $key => $value) {
			$data[$idx] = array(
				'rekam_faktur_pajak_id' => $rekam_faktur_pajak_id,
				'penjualan_id' => $value,
				'is_user_id' => is_user_id() 
				);
		}
		$this->common_model->db_insert_batch("nd_rekam_faktur_pajak_detail", $data);

		$result_id = $this->common_model->db_insert("nd_rekam_faktur_pajak",$data);
		redirect(is_setting_link('pajak/rekam_faktur_pajak_detail').'?id='.$rekam_faktur_pajak_id);
	}

	function generate_rekam_faktur()
	{
		$pre_faktur = '003.19.';
		$no_awal = 4645271;
		$char = 9;

		$faktur_list = $this->pjk_model->get_rekam_faktur_pajak_detail(1);
		foreach ($faktur_list as $row) {
			$id = $row->id;
			$data = array(
				'no_faktur_pajak' => $pre_faktur.str_pad($no_awal, 8,'0', STR_PAD_LEFT) ,
				'no_faktur_pajak_id' => 1 );
			$this->common_model->db_update('nd_rekam_faktur_pajak_detail',$data,'id', $id);
			// print_r($data);
			$no_awal++;
		}
	}

	function faktur_rekam_pajak_status_change(){
		$id = $this->input->get('id');
		$rekam_faktur_pajak_id = $this->input->get('rekam_faktur_pajak_id');
		$data = array(
			'status' => $this->input->get('status') );
		$this->common_model->db_update('nd_rekam_faktur_pajak_detail',$data,'id', $id);
		redirect(is_setting_link('pajak/rekam_faktur_pajak_detail').'?id='.$rekam_faktur_pajak_id);

	}

	function faktur_rekam_pajak_remove(){
		$id = $this->input->get('id');
		$rekam_faktur_pajak_id = $this->input->get('rekam_faktur_pajak_id');
		
		$get_rest_data = $this->common_model->db_select("nd_rekam_faktur_pajak_detail where id >=".$id." AND rekam_faktur_pajak_id=".$rekam_faktur_pajak_id);

		$idx=0;
		foreach ($get_rest_data as $row) {
			$row_id[$idx] = $row->id;
			$no_fp_update[$idx] = $row->no_faktur_pajak;
			$no_faktur_pajak_id[$idx] = $row->no_faktur_pajak_id;
			$idx++;
		}

		// echo count($get_rest_data);echo "<br/>";
		// echo $idx;

		$case_fp = "CASE
		";
		$case_fp_id  = "CASE
		";
		for ($i=1; $i < $idx ; $i++) { 
			$case_fp .="WHEN id=".$row_id[$i]." THEN '".$no_fp_update[$i-1]."'
			";
			
			$case_fp_id .="WHEN id=".$row_id[$i]." THEN ".$no_faktur_pajak_id[$i-1]."
			";
		}

		// echo $case_fp.'<hr/>'.$case_fp_id;

		// echo "UPDATE nd_rekam_faktur_pajak_detail
		// 	SET no_faktur_pajak = 
		// 	$case_fp
		// 	ELSE no_faktur_pajak
		// 	END,
		// 	no_faktur_pajak_id = 
		// 	$case_fp_id
		// 	ELSE no_faktur_pajak_id
		// 	END";

		$this->common_model->db_free_query_superadmin("UPDATE nd_rekam_faktur_pajak_detail
			SET no_faktur_pajak = 
			$case_fp
			ELSE no_faktur_pajak
			END,
			no_faktur_pajak_id = 
			$case_fp_id
			ELSE no_faktur_pajak_id
			END
			 ");

		$this->common_model->db_delete("nd_rekam_faktur_pajak_detail", 'id',$id);
		
		redirect(is_setting_link('pajak/rekam_faktur_pajak_detail').'?id='.$rekam_faktur_pajak_id);
	}

	function faktur_rekam_pajak_remove_coretax(){
		$id = $this->input->get('id');
		$rekam_faktur_pajak_id = $this->input->get('rekam_faktur_pajak_id');

		$this->common_model->db_delete("nd_rekam_faktur_pajak_detail", 'id',$id);
		
		redirect(is_setting_link('pajak/rekam_faktur_pajak_detail').'?id='.$rekam_faktur_pajak_id);
	}

	function faktur_rekam_pajak_lock()
	{
		$id = $this->input->post('rekam_faktur_pajak_id');
		$get_data = $this->common_model->db_free_query_superadmin("SELECT *, YEAR(created_at) as tahun 
			FROM nd_rekam_faktur_pajak 
			where id=$id");

		$no_surat = '';
		$tahun = date('2020');

		foreach ($get_data->result() as $row) {
			$tahun = $row->tahun;
			$no_surat = ($row->no_surat == 0 ? '' : $row->no_surat);
		}

		if ($no_surat == '') {
			$no_surat = 1;
			$get_last = $this->common_model->db_free_query_superadmin("SELECT * 
				FROM nd_rekam_faktur_pajak 
				where id != $id 
				AND YEAR(created_at) = '$tahun'
				AND no_surat != ''
				ORDER BY id desc
				LIMIT 1");

			foreach ($get_last->result() as $row) {
				$no_surat = $row->no_surat + 1;
			}
		}

		$data = array(
			'status' => 0,
			'no_surat' => $no_surat,
			'nilai' => $this->input->post('nilai'),
			'nilai_ppn' => $this->input->post('nilai_ppn'),
			'locked_date' => date('Y-m-d H:i:s')
			);
		$this->common_model->db_update("nd_rekam_faktur_pajak", $data,'id', $id);
		redirect(is_setting_link('pajak/rekam_faktur_pajak_detail').'?id='.$id);
	}

	function faktur_rekam_pajak_unlock()
	{
		$id = $this->input->get('rekam_faktur_pajak_id');

		$data = array(
			'status' => 1,
			);

		$this->common_model->db_update("nd_rekam_faktur_pajak", $data,'id', $id);
		redirect(is_setting_link('pajak/rekam_faktur_pajak_detail').'?id='.$id);
	}

	function faktur_rekam_pajak_update_nik(){
		$id = $this->input->post('id');
		$nik = $this->input->post('nik');
		$data = array(
			'no_nik' => $nik
			);
		$this->common_model->db_update("nd_rekam_faktur_pajak_detail", $data,'id', $id);
		echo "OK";
	}


//====================================faktur pajak donwload=========================================

	function rekam_faktur_list_export_excel(){
			
		$id = $this->input->get('id');
		$is_faktur_pengganti = $this->input->get('is_faktur_pengganti');
		$fp_list = $this->common_model->db_select("nd_rekam_faktur_pajak_detail where rekam_faktur_pajak_id=".$id." and status = 1 ");
		$idx=0;
		foreach ($fp_list as $row) {
			$penjualan_id[$idx] = $row->penjualan_id;
			$idx++;
		}

		$penjualan_id_list = implode(',', $penjualan_id);
		$get_detail_list = $this->pjk_model->get_item_penjualan_list($penjualan_id_list);
		$faktur_data_list = $this->pjk_model->get_data_penjualan_list($penjualan_id_list);

		// echo count($get_detail_list).'<hr/>';
		$idx=0;
		foreach ($get_detail_list as $row) {
			$penjualan_data_detail['detail'][$row->penjualan_id][$row->barang_id][$row->harga_jual] = $row;
			if (!isset($sum_jual[$row->penjualan_id])) {
				$sum_jual[$row->penjualan_id] = 0;
			}
			$sum_jual[$row->penjualan_id] += number_format($row->harga_jual/(1+($row->ppn_berlaku/100)),'4','.','') * $row->qty; 
			$idx++;
		}
		// print_r($penjualan_data_detail['detail'][14380]);

		foreach ($faktur_data_list as $row) {
			$penjualan_data_detail['data'][$row->penjualan_id] = $row;
			$no_faktur[$row->penjualan_id] = $row->no_faktur;
		}

		if($is_faktur_pengganti == ''){
			$is_faktur_pengganti = 0;
		}

		// print_r($penjualan_data_detail['data']);
		// echo $penjualan_data_detail['data'][13112]->penjualan_id;

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

		$styleArrayBG = array(
			'fill' => array(
					'type' => PHPExcel_Style_Fill::FILL_SOLID,
					'color' => array('rgb' => 'FFFF00')
				)
			);
		
		$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('A1', 'FK')
		->setCellValue('B1', 'KD_JENIS_TRANSAKSI')
		->setCellValue('C1', 'FG_PENGGANTI')
		->setCellValue('D1', 'NOMOR_FAKTUR')
		->setCellValue('E1', 'MASA_PAJAK')
		->setCellValue('F1', 'TAHUN_PAJAK')
		->setCellValue('G1', 'TANGGAL_FAKTUR')
		->setCellValue('H1', 'NPWP')
		->setCellValue('I1', 'NAMA')
		->setCellValue('J1', 'ALAMAT_LENGKAP')
		->setCellValue('K1', 'JUMLAH_DPP')
		->setCellValue('L1', 'JUMLAH_PPN')
		->setCellValue('M1', 'JUMLAH_PPNBM')
		->setCellValue('N1', 'ID_KETERANGAN_TAMBAHAN')
		->setCellValue('O1', 'FG_UANG_MUKA')
		->setCellValue('P1', 'UANG_MUKA_DPP')
		->setCellValue('Q1', 'UANG_MUKA_PPN')
		->setCellValue('R1', 'UANG_MUKA_PPNBM')
		->setCellValue('S1', 'REFERENSI')
		->setCellValue('T1', 'KODE_DOKUMEN_PENDUKUNG')
		
		->setCellValue('A2', 'LT')
		->setCellValue('B2', 'NPWP')
		->setCellValue('C2', 'NAMA')
		->setCellValue('D2', 'JALAN')
		->setCellValue('E2', 'BLOK')
		->setCellValue('F2', 'NOMOR')
		->setCellValue('G2', 'RT')
		->setCellValue('H2', 'RW')
		->setCellValue('I2', 'KECAMATAN')
		->setCellValue('J2', 'KELURAHAN')
		->setCellValue('K2', 'KABUPATEN')
		
		->setCellValue('L2', 'PROPINSI')
		->setCellValue('M2', 'KODE_POS')
		->setCellValue('N2', 'NOMOR_TELEPON')
		
		->setCellValue('A3', 'OF')
		->setCellValue('B3', 'KODE_OBJEK')
		->setCellValue('C3', 'NAMA')
		->setCellValue('D3', 'HARGA_SATUAN')
		->setCellValue('E3', 'JUMLAH_BARANG')
		->setCellValue('F3', 'HARGA_TOTAL')
		->setCellValue('G3', 'DISKON')
		->setCellValue('H3', 'DPP')
		->setCellValue('I3', 'PPN')
		->setCellValue('J3', 'TARIF_PPNBM')
		->setCellValue('K3', 'PPNBM')
		;


		$row_no = 4;
				
		foreach ($fp_list as $row) {
			$total = array();
			// $objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(10);
			
			$coll = "A";
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,'FK');
			
			$coll++;//B
			// $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,"01");
			$objPHPExcel->getActiveSheet()->getCell($coll.$row_no)->setValueExplicit("01", PHPExcel_Cell_DataType::TYPE_STRING);

			$coll++;//C
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $is_faktur_pengganti );

			$coll++;//D
			// $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,str_replace('.', '', $row->no_faktur_pajak));
			$no_faktur_pajak=str_replace('.', '', $row->no_faktur_pajak);
			$no_faktur_pajak = str_pad($no_faktur_pajak, 13, '0',STR_PAD_LEFT);
			$objPHPExcel->getActiveSheet()->getCell($coll.$row_no)->setValueExplicit($no_faktur_pajak, PHPExcel_Cell_DataType::TYPE_STRING);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);

			$coll++;//E
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$penjualan_data_detail['data'][$row->penjualan_id]->MASA_PAJAK);

			$coll++;//F
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$penjualan_data_detail['data'][$row->penjualan_id]->TAHUN_PAJAK);

			$coll++;//G
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$penjualan_data_detail['data'][$row->penjualan_id]->TANGGAL_FAKTUR);

			$filter_npwp = ['.','-'];
			$npwp = str_replace($filter_npwp, '', $penjualan_data_detail['data'][$row->penjualan_id]->NPWP);
			// obsolete since nik = npwp
			/* $npwp = ($npwp == '' ? 0 : $npwp);
			$npwp = str_pad($npwp, 15, '0',STR_PAD_LEFT); */
			if ($npwp == '') {
				# code...
				$npwp = $penjualan_data_detail['data'][$row->penjualan_id]->NIK;
			}
			$coll++;//H
			// $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$npwp);
			$objPHPExcel->getActiveSheet()->getCell($coll.$row_no)->setValueExplicit($npwp, PHPExcel_Cell_DataType::TYPE_STRING);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(22);

			//====================cek pake nik atau tidak===============================
			$filter_nama = strtoupper(trim(htmlspecialchars_decode($penjualan_data_detail['data'][$row->penjualan_id]->NAMA)));
			$filter = [',','.'];
			$filter_nama = str_replace($filter, '', $filter_nama);
			// $filter_nama = str_replace($filter, '', $filter_nama);
			if (substr($filter_nama, -2) === 'CV') {
				$filter = [',','.'];
				$filter_nama = "CV. ".substr($filter_nama, 0, (strlen($filter_nama)-2) );
			}elseif (substr($filter_nama, -2) === 'PT') {
				if (substr($filter_nama, -3,1) == '' || substr($filter_nama, -3,1) == ' ' || substr($filter_nama, -3,1) == ',' || substr($filter_nama, -3,1) == '.' ) {
					$filter = [',','.'];
					$filter_nama = "PT. ".substr($filter_nama, 0, (strlen($filter_nama)-2) );
				}
			}
			// echo $filter_nama.'<br/>';

			$NPWP_now = $penjualan_data_detail['data'][$row->penjualan_id]->NPWP;
			$NPWP_now = str_replace('.', '', $NPWP_now);
			$NPWP_now = (float)$NPWP_now;

			$nama = $penjualan_data_detail['data'][$row->penjualan_id]->NAMA;


			/* if ($NPWP_now == '' || $NPWP_now == 0) {
				$nama = $penjualan_data_detail['data'][$row->penjualan_id]->NIK." #NIK#NAMA#".$penjualan_data_detail['data'][$row->penjualan_id]->NAMA;
			}else{
				$nama = $penjualan_data_detail['data'][$row->penjualan_id]->NAMA;
			} */
			
			$coll++;//I
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, trim($nama) );

			$alamat = $penjualan_data_detail['data'][$row->penjualan_id]->ALAMAT_LENGKAP;
			$kota = $penjualan_data_detail['data'][$row->penjualan_id]->kota;
			$provinsi = $penjualan_data_detail['data'][$row->penjualan_id]->provinsi;
			$kode_pos = $penjualan_data_detail['data'][$row->penjualan_id]->kode_pos;
			$kode_pos = ($kode_pos == '' || $kode_pos == '0' ? '00000' : $kode_pos);
			if (strlen($kode_pos) < 5) {
				$kode_pos = '00000';
			}
			if($alamat != 'ERROR'){
				// $alamat .= ($kota != '' ? ' Kota/Kab.'.$kota.' ' : '').' '.$provinsi.' '.$kode_pos;
			}
			$coll++;//J
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$alamat);

			// $ppn = number_format($penjualan_data_detail['data'][$row->penjualan_id]->g_total/1.1,'2','.','')*0.1;
			$ppn_fix = $penjualan_data_detail['data'][$row->penjualan_id]->ppn_berlaku;
			$ppn_pengali = $ppn_fix /100;
			$ppn_pembagi = $ppn_pengali + 1;

			$nilai_raw = number_format($penjualan_data_detail['data'][$row->penjualan_id]->g_total/$ppn_pembagi,'2','.','');
			$no_ref = $no_faktur[$row->penjualan_id]; 
			$ppn = number_format($penjualan_data_detail['data'][$row->penjualan_id]->g_total/$ppn_pembagi,'2','.','')*$ppn_pengali;
			$coll++;//K
			if(count(explode('.',$nilai_raw)) > 1){
				// echo $nama.'==><br/>'.$penjualan_data_detail['data'][$row->penjualan_id]->g_total.' == '.number_format($penjualan_data_detail['data'][$row->penjualan_id]->g_total/1.1,'2','.','').'<br/>'.count(explode('.',$nilai_raw));
				// echo "<hr/>";
				$nilai_raw = floor($nilai_raw);
			}

			if(count(explode('.',$ppn)) > 1){
				// $ppn = floor($ppn);
				$ppn = floor($ppn);
			}
			
			// $nilai_raw =
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $nilai_raw );
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);

			$coll++;//L
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,number_format($ppn,'0','','' ) );
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(13);

			$coll++;//M
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,"0");

			$coll++;//N
			$coll++;//O
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,"0");

			$coll++;//P
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,"0");

			$coll++;//Q
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,"0");

			$coll++;//R
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,"0");

			$coll++;//S
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$no_ref);

			//====================================baris 2 data toko===========================

			$row_no++;
			$coll="A";
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,"FAPR");

			$coll++;//B
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$penjualan_data_detail['data'][$row->penjualan_id]->NAMA_TOKO);

			$coll++;//C
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$penjualan_data_detail['data'][$row->penjualan_id]->ALAMAT_TOKO);

			//====================================baris barang===========================

			foreach ($penjualan_data_detail['detail'][$row->penjualan_id] as $key => $value) {
				foreach ($value as $key2 => $value2) {
					$harga_dpp = number_format($value2->harga_jual/$ppn_pembagi,'4','.','');
					$row_no++;
					$coll="A";
					$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,"OF");

					$coll++;//B
					$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$value2->kode_barang);
					
					$coll++;//C
					$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$value2->kode_barang);

					$coll++;//D
					$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$harga_dpp);

					$coll++;//E
					$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$value2->qty);

					$nilai_barang = $value2->harga_jual * $value2->qty;
					$nilai_barang_raw = $nilai_barang/$ppn_pembagi;
					$nilai_barang_ppn = $nilai_barang - $nilai_barang_raw;
					$coll++;//F
					$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,number_format($nilai_barang_raw,'2','.',''));

					$coll++;//G
					$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,"0");

					$coll++;//H
					$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,number_format($nilai_barang_raw,'2','.','') );

					$coll++;//I
					$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,number_format($nilai_barang_ppn,'2','.',''));

					$coll++;//J
					$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,"0");

					$coll++;//K
					$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,"0");
				}

			}

			$coll_start = $coll;
			$row_start = $row_no;
			$sub_total = 0;


			$row_no++;			
		}

		foreach ($this->toko_list_aktif as $row) {
			$nama_toko = $row->nama;
		}

		// $row_no++;
		
		// $objPHPExcel->getActiveSheet()->setTitle('Rit 1');

		//$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		// if (is_posisi_id() != 1) {
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
			ob_end_clean();


			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header("Content-Disposition: attachment;filename=Laporan_Faktur_Pajak_".$nama_toko."_".date('dmy').".xls");
			header('Cache-Control: max-age=0');
			$objWriter->save('php://output');
			# code...
		// }
	}

	function rekam_faktur_list_export_excel_legacy(){
		
		$id = $this->input->get('id');
		$is_faktur_pengganti = $this->input->get('is_faktur_pengganti');
		$fp_list = $this->common_model->db_select("nd_rekam_faktur_pajak_detail where rekam_faktur_pajak_id=".$id." and status = 1 ");
		$idx=0;
		foreach ($fp_list as $row) {
			$penjualan_id[$idx] = $row->penjualan_id;
			$idx++;
		}

		$penjualan_id_list = implode(',', $penjualan_id);
		$get_detail_list = $this->pjk_model->get_item_penjualan_list($penjualan_id_list);
		$faktur_data_list = $this->pjk_model->get_data_penjualan_list($penjualan_id_list);

		// echo count($get_detail_list).'<hr/>';
		$idx=0;
		foreach ($get_detail_list as $row) {
			$penjualan_data_detail['detail'][$row->penjualan_id][$row->barang_id][$row->harga_jual] = $row;
			if (!isset($sum_jual[$row->penjualan_id])) {
				$sum_jual[$row->penjualan_id] = 0;
			}
			$sum_jual[$row->penjualan_id] += number_format($row->harga_jual/(1+($row->ppn_berlaku/100)),'4','.','') * $row->qty; 
			$idx++;
		}
		// print_r($penjualan_data_detail['detail'][14380]);

		foreach ($faktur_data_list as $row) {
			$penjualan_data_detail['data'][$row->penjualan_id] = $row;
			$no_faktur[$row->penjualan_id] = $row->no_faktur;
		}

		if($is_faktur_pengganti == ''){
			$is_faktur_pengganti = 0;
		}

		// print_r($penjualan_data_detail['data']);
		// echo $penjualan_data_detail['data'][13112]->penjualan_id;

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

		$styleArrayBG = array(
			'fill' => array(
		            'type' => PHPExcel_Style_Fill::FILL_SOLID,
		            'color' => array('rgb' => 'FFFF00')
		        )
			);
		
		$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('A1', 'FK')
		->setCellValue('B1', 'KD_JENIS_TRANSAKSI')
		->setCellValue('C1', 'FG_PENGGANTI')
		->setCellValue('D1', 'NOMOR_FAKTUR')
		->setCellValue('E1', 'MASA_PAJAK')
		->setCellValue('F1', 'TAHUN_PAJAK')
		->setCellValue('G1', 'TANGGAL_FAKTUR')
		->setCellValue('H1', 'NPWP')
		->setCellValue('I1', 'NAMA')
		->setCellValue('J1', 'ALAMAT_LENGKAP')
		->setCellValue('K1', 'JUMLAH_DPP')
		->setCellValue('L1', 'JUMLAH_PPN')
		->setCellValue('M1', 'JUMLAH_PPNBM')
		->setCellValue('N1', 'ID_KETERANGAN_TAMBAHAN')
		->setCellValue('O1', 'FG_UANG_MUKA')
		->setCellValue('P1', 'UANG_MUKA_DPP')
		->setCellValue('Q1', 'UANG_MUKA_PPN')
		->setCellValue('R1', 'UANG_MUKA_PPNBM')
		->setCellValue('S1', 'REFERENSI')
		->setCellValue('T1', 'KODE_DOKUMEN_PENDUKUNG')
		
		->setCellValue('A2', 'LT')
		->setCellValue('B2', 'NPWP')
		->setCellValue('C2', 'NAMA')
		->setCellValue('D2', 'JALAN')
		->setCellValue('E2', 'BLOK')
		->setCellValue('F2', 'NOMOR')
		->setCellValue('G2', 'RT')
		->setCellValue('H2', 'RW')
		->setCellValue('I2', 'KECAMATAN')
		->setCellValue('J2', 'KELURAHAN')
		->setCellValue('K2', 'KABUPATEN')
		
		->setCellValue('L2', 'PROPINSI')
		->setCellValue('M2', 'KODE_POS')
		->setCellValue('N2', 'NOMOR_TELEPON')
		
		->setCellValue('A3', 'OF')
		->setCellValue('B3', 'KODE_OBJEK')
		->setCellValue('C3', 'NAMA')
		->setCellValue('D3', 'HARGA_SATUAN')
		->setCellValue('E3', 'JUMLAH_BARANG')
		->setCellValue('F3', 'HARGA_TOTAL')
		->setCellValue('G3', 'DISKON')
		->setCellValue('H3', 'DPP')
		->setCellValue('I3', 'PPN')
		->setCellValue('J3', 'TARIF_PPNBM')
		->setCellValue('K3', 'PPNBM')
		;


		$row_no = 4;
				
		foreach ($fp_list as $row) {
			$total = array();
			// $objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(10);
			
			$coll = "A";
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,'FK');
			
			$coll++;//B
			// $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,"01");
			$objPHPExcel->getActiveSheet()->getCell($coll.$row_no)->setValueExplicit("01", PHPExcel_Cell_DataType::TYPE_STRING);

			$coll++;//C
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $is_faktur_pengganti );

			$coll++;//D
			// $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,str_replace('.', '', $row->no_faktur_pajak));
			$no_faktur_pajak=str_replace('.', '', $row->no_faktur_pajak);
			$no_faktur_pajak = str_pad($no_faktur_pajak, 13, '0',STR_PAD_LEFT);
			$objPHPExcel->getActiveSheet()->getCell($coll.$row_no)->setValueExplicit($no_faktur_pajak, PHPExcel_Cell_DataType::TYPE_STRING);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);

			$coll++;//E
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$penjualan_data_detail['data'][$row->penjualan_id]->MASA_PAJAK);

			$coll++;//F
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$penjualan_data_detail['data'][$row->penjualan_id]->TAHUN_PAJAK);

			$coll++;//G
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$penjualan_data_detail['data'][$row->penjualan_id]->TANGGAL_FAKTUR);

			$filter_npwp = ['.','-'];
			$npwp = str_replace($filter_npwp, '', $penjualan_data_detail['data'][$row->penjualan_id]->NPWP);
			$npwp = ($npwp == '' ? 0 : $npwp);
			$npwp = str_pad($npwp, 15, '0',STR_PAD_LEFT);
			$coll++;//H
			// $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$npwp);
			$objPHPExcel->getActiveSheet()->getCell($coll.$row_no)->setValueExplicit($npwp, PHPExcel_Cell_DataType::TYPE_STRING);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(22);

			//====================cek pake nik atau tidak===============================
			$filter_nama = strtoupper(trim(htmlspecialchars_decode($penjualan_data_detail['data'][$row->penjualan_id]->NAMA)));
			$filter = [',','.'];
			$filter_nama = str_replace($filter, '', $filter_nama);
			// $filter_nama = str_replace($filter, '', $filter_nama);
			if (substr($filter_nama, -2) === 'CV') {
				$filter = [',','.'];
				$filter_nama = "CV. ".substr($filter_nama, 0, (strlen($filter_nama)-2) );
			}elseif (substr($filter_nama, -2) === 'PT') {
				if (substr($filter_nama, -3,1) == '' || substr($filter_nama, -3,1) == ' ' || substr($filter_nama, -3,1) == ',' || substr($filter_nama, -3,1) == '.' ) {
					$filter = [',','.'];
					$filter_nama = "PT. ".substr($filter_nama, 0, (strlen($filter_nama)-2) );
				}
			}
			// echo $filter_nama.'<br/>';

			$NPWP_now = $penjualan_data_detail['data'][$row->penjualan_id]->NPWP;
			$NPWP_now = str_replace('.', '', $NPWP_now);
			$NPWP_now = (float)$NPWP_now;

			if ($NPWP_now == '' || $NPWP_now == 0) {
				$nama = $penjualan_data_detail['data'][$row->penjualan_id]->NIK." #NIK#NAMA#".$penjualan_data_detail['data'][$row->penjualan_id]->NAMA;
			}else{
				$nama = $penjualan_data_detail['data'][$row->penjualan_id]->NAMA;
			}

			$coll++;//I
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$nama);

			$alamat = $penjualan_data_detail['data'][$row->penjualan_id]->ALAMAT_LENGKAP;
			$kota = $penjualan_data_detail['data'][$row->penjualan_id]->kota;
			$provinsi = $penjualan_data_detail['data'][$row->penjualan_id]->provinsi;
			$kode_pos = $penjualan_data_detail['data'][$row->penjualan_id]->kode_pos;
			$kode_pos = ($kode_pos == '' || $kode_pos == '0' ? '00000' : $kode_pos);
			if (strlen($kode_pos) < 5) {
				$kode_pos = '00000';
			}
			if($alamat != 'ERROR'){
				// $alamat .= ($kota != '' ? ' Kota/Kab.'.$kota.' ' : '').' '.$provinsi.' '.$kode_pos;
			}
			$coll++;//J
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$alamat);

			// $ppn = number_format($penjualan_data_detail['data'][$row->penjualan_id]->g_total/1.1,'2','.','')*0.1;
			$ppn_fix = $penjualan_data_detail['data'][$row->penjualan_id]->ppn_berlaku;
			$ppn_pengali = $ppn_fix /100;
			$ppn_pembagi = $ppn_pengali + 1;

			$nilai_raw = number_format($penjualan_data_detail['data'][$row->penjualan_id]->g_total/$ppn_pembagi,'2','.','');
			$no_ref = $no_faktur[$row->penjualan_id]; 
			$ppn = number_format($penjualan_data_detail['data'][$row->penjualan_id]->g_total/$ppn_pembagi,'2','.','')*$ppn_pengali;
			$coll++;//K
			if(count(explode('.',$nilai_raw)) > 1){
				// echo $nama.'==><br/>'.$penjualan_data_detail['data'][$row->penjualan_id]->g_total.' == '.number_format($penjualan_data_detail['data'][$row->penjualan_id]->g_total/1.1,'2','.','').'<br/>'.count(explode('.',$nilai_raw));
				// echo "<hr/>";
				$nilai_raw = floor($nilai_raw);
			}

			if(count(explode('.',$ppn)) > 1){
				// $ppn = floor($ppn);
				$ppn = floor($ppn);
			}
			
			// $nilai_raw =
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $nilai_raw );
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);

			$coll++;//L
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,number_format($ppn,'0','','' ) );
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(13);

			$coll++;//M
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,"0");

			$coll++;//N
			$coll++;//O
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,"0");

			$coll++;//P
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,"0");

			$coll++;//Q
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,"0");

			$coll++;//R
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,"0");

			$coll++;//S
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$no_ref);

			//====================================baris 2 data toko===========================

			$row_no++;
			$coll="A";
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,"FAPR");

			$coll++;//B
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$penjualan_data_detail['data'][$row->penjualan_id]->NAMA_TOKO);

			$coll++;//C
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$penjualan_data_detail['data'][$row->penjualan_id]->ALAMAT_TOKO);

			//====================================baris barang===========================

			foreach ($penjualan_data_detail['detail'][$row->penjualan_id] as $key => $value) {
				foreach ($value as $key2 => $value2) {
					$harga_dpp = number_format($value2->harga_jual/$ppn_pembagi,'4','.','');
					$row_no++;
					$coll="A";
					$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,"OF");

					$coll++;//B
					$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$value2->kode_barang);
					
					$coll++;//C
					$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$value2->kode_barang);

					$coll++;//D
					$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$harga_dpp);

					$coll++;//E
					$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$value2->qty);

					$nilai_barang = $value2->harga_jual * $value2->qty;
					$nilai_barang_raw = $nilai_barang/$ppn_pembagi;
					$nilai_barang_ppn = $nilai_barang - $nilai_barang_raw;
					$coll++;//F
					$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,number_format($nilai_barang_raw,'2','.',''));

					$coll++;//G
					$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,"0");

					$coll++;//H
					$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,number_format($nilai_barang_raw,'2','.','') );

					$coll++;//I
					$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,number_format($nilai_barang_ppn,'2','.',''));

					$coll++;//J
					$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,"0");

					$coll++;//K
					$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,"0");
				}

			}
	
			$coll_start = $coll;
			$row_start = $row_no;
			$sub_total = 0;


			$row_no++;			
		}

		foreach ($this->toko_list_aktif as $row) {
			$nama_toko = $row->nama;
		}

		// $row_no++;
		
		// $objPHPExcel->getActiveSheet()->setTitle('Rit 1');

		//$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		// if (is_posisi_id() != 1) {
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
			ob_end_clean();


			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header("Content-Disposition: attachment;filename=Laporan_Faktur_Pajak_".$nama_toko."_".date('dmy').".xls");
			header('Cache-Control: max-age=0');
			$objWriter->save('php://output');
			# code...
		// }
	}

//====================================faktur pajak email=========================================

	function rekam_faktur_email_list(){
		$menu = is_get_url($this->uri->segment(1)) ;
		$id = $this->input->get('id');

		$this->load->helper('directory');
		$dir = 'fp_list/fp_'.$id;

		$rekam_faktur_data = $this->pjk_model->get_rekam_faktur_pajak_list_by_id($id);
		$tahun = date('Y');
		foreach ($rekam_faktur_data as $row) {
			$tahun = date('Y', strtotime($row->tanggal_end));
		}

		$content = ($tahun < 2025 ? 'rekam_faktur_pajak_email' : 'rekam_faktur_pajak_email_coretax' );


		$data = array(
			'content' =>'admin/pajak/'.$content,
			'breadcrumb_title' => 'Pajak',
			'breadcrumb_small' => 'Daftar Faktur u/ Email',
			'rekam_faktur_pajak_id' => $id,
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data'=> $this->data,
			'dir'=>$dir,
			'map'=>directory_map($dir),
			'data_isi'=> $this->data );


		$last_date = '2019-08-04';

		$data['rekam_faktur_data'] = $rekam_faktur_data;		
		$data['fp_list_npwp'] = $this->pjk_model->get_rekam_faktur_pajak_email($id, "WHERE npwp != '' AND npwp is not null ",'');
		$data['fp_list_nik'] = $this->pjk_model->get_rekam_faktur_pajak_email($id, "WHERE npwp ='' OR npwp is null ",'');
		$data['google_setting'] = $this->common_model->db_select("nd_setting");
		$data['draft_list'] = $this->pjk_model->get_draft_list($id);

		if (is_posisi_id() == 1) {
			# code...
			$this->load->view('admin/template',$data);
			// print_r($data['fp_list_npwp']);
		}else{
			$this->load->view('admin/template',$data);
		}


	}	

	function rekam_faktur_list_raw(){
		echo json_encode($this->pjk_model->get_rekam_faktur_pajak_list("LIMIT 5"));
	}

	function email_list_body_legacy(){

		$res_toko = $this->common_model->db_select("nd_toko where status_aktif = 1 LIMIT 1");
		$rekam_faktur_pajak_id = $this->input->get('id');

		$kode_toko = '';
		$nama_toko = '';
		$alamat_toko = '';
		$email_toko = '';
		$host_toko = '';
		$relay_email_toko = '';
		$telepon_toko = '';

		foreach ($res_toko as $row) {
			$kode_toko = $row->pre_po;
			$nama_toko = $row->nama;
			$alamat_toko = $row->alamat;
			$email_toko = $row->email;
			$host_toko = $row->host;
			$telepon_toko = $row->telepon;
			$relay_email_toko = $row->relay_mail;
		}

		$message_list = array();
		$parts = array();
		$headers = array();
		$idx = 0;
		$head = array();

		$data = $this->pjk_model->get_rekam_faktur_pajak_email($rekam_faktur_pajak_id, "WHERE npwp != '' AND npwp is not null ",'');

		$get_db = $this->common_model->db_select('nd_rekam_faktur_pajak_email where rekam_faktur_pajak_id='.$rekam_faktur_pajak_id." ");
		$rekam_email_id = array();
		foreach ($get_db as $row) {
			$rekam_email_id[$rekam_faktur_pajak_id][$row->customer_id] = $row->id;
		}


		foreach ($data as $key => $value) {	
		
			if ($value->email != '') {
				# code...
				//=====================================multipart==========================================
			
				$boundary1 = "000000000142536475869"; //buat pdf
				$boundary2 = "000000000475869142536"; //buat text
			
				$idx = 0;
				$second = str_pad($idx,2,"0",STR_PAD_LEFT);
				$idx+=2;
				$header_msg = "Date:".date('D, d M Y H:i:'.$second)."\r\n";
				$header_msg .= "Message-ID:PLT-".$kode_toko.date('ymdHis').rand(100,999)."\r\n";
				$header_msg .= 'MIME-Version: 1.0\r\n'."\r\n";
				$header_msg .= "Subject:Pajak Keluaran $value->nama\r\n";
				$header_msg .= "From:$nama_toko<$relay_email_toko>\r\n";
				$header_msg .= "To: $value->email\r\n";
				$header_msg .= 'Content-Type: multipart/mixed; boundary=000000000142536475869'."\r\n\r\n";
			
				$header_msg .= "--$boundary1\r\n";
				$header_msg .= 'Content-Type: multipart/alternative; boundary=000000000475869142536'."\r\n\r\n";
			
				// ================message
				$message = "--$boundary2\r\n";
				$message .= 'Content-Type:text/html; charset=utf-8'."\r\n";
				$message .= 'Content-Transfer-Encoding: quoted-printable'."\r\n\r\n";
			
				$message .= 'Kepada Yth.<br>'.
				$value->nama."<br><br>
				Berikut adalah file attachment faktur pajak keluaran $nama_toko<br><br>
				
				Regards,<br>
				Anthony Tedjasukmana<br><br>
				
				
				$nama_toko<br>
				$alamat_toko<br>
				West Java, Indonesia<br>
				40181<br><br>
				
				&#9742; : 0812 2313 0909 // $telepon_toko<br>
				&#9993; : <a href='mailto:".$relay_email_toko."'>$relay_email_toko</a> // <a href='mailto:$email_toko'>$email_toko</a>\r\n\r\n";
				$message .= "--$boundary2--\r\n";
				
			
			
				// ================attachment
		
				
				$filter_nama = [',','.',' ','/'];
				$filter_nama_cust = ["'","/","&"];
				$no_faktur_pajak = explode('??', $value->no_faktur_pajak);
				$tanggal_invoice = explode(',', $value->tanggal_invoice);
			
				$attachment_list = ''; 
				$file_loc_list = array();
				$attachment = '';
				foreach ($no_faktur_pajak as $key2 => $baris) {
					$break_name = explode('.', $baris);
					$filename = str_replace($filter_nama, '_', $nama_toko.'-'.str_replace($filter_nama_cust, '', trim($value->nama) )).'-'.date('dmY',strtotime($tanggal_invoice[$key2])).'-'.end($break_name).'.pdf';
					$file_loc = "https://".$host_toko."/fp_list/fp_".$rekam_faktur_pajak_id.'/'.$filename;
					$pdf64 = chunk_split(base64_encode(file_get_contents($file_loc)));
					array_push($file_loc_list, $file_loc);
				
			
					//===================================================
					$attachment .= "--$boundary1\r\n";
					$attachment .= 'Content-Type: Application/pdf; name='.$filename."\r\n";
					$attachment .= 'Content-Disposition: attachment; filename='.$filename."\r\n";
					$attachment .= 'Content-Transfer-Encoding: base64'."\r\n\r\n";        
					$attachment .= $pdf64;
					$attachment .= "\r\n\r\n";
		
					
				}
		
				$attachment .= "--$boundary1--\r\n";
				$attachment .= base64_encode($attachment);
				
				
				$header_64 = base64_encode($header_msg);
				$message_64 = base64_encode($message);
			
				// $msg_encoded = $header_64.$message_64.$attachment_64;
				$msg_encoded = base64_encode($header_msg.$message.$attachment);
				// $raw_encoded = base64_encode($raw_msg.$body_raw);
			
				// $parts_body = array(
				// 	"data" => $msg_encoded,
				// 	// "size" => strlen($msg_encoded)
				// );
			
				// $parts[$key] = array(
				// 	"mimeType" => "text/html", 
				// 	"headers"=> $parts_headers,
				// 	"body" => $parts_body
				// );

				if (!isset($rekam_email_id[$rekam_faktur_pajak_id][$value->customer_id])) {
					$rekam_email_id[$rekam_faktur_pajak_id][$value->customer_id] = '';
				}
				
				array_push($head, array(
					'rekam_email_id'=>$rekam_email_id[$rekam_faktur_pajak_id][$value->customer_id],
					'nama' => $value->nama,
					'customer_id' => $value->customer_id
				));
				
				array_push($message_list, array(
					'message' => array(
						'raw' => $msg_encoded,
						// 'raw_html' => $msg_encoded,
						// 'headers'=>$headers,
					)
				));
			}
		}

		$parts['head'] = $head;
		$parts['body'] = $message_list;

		echo json_encode($parts);

	}

	function email_list_draft_list(){
		$rekam_faktur_pajak_id = $this->input->get('id');
		$data = $this->pjk_model->get_rekam_faktur_pajak_email($rekam_faktur_pajak_id, "WHERE npwp != '' AND npwp is not null AND (draft_id is null or draft_id = '') and email != '' ",'');
		echo count($data);

	}

	function email_list_body(){

		$res_toko = $this->common_model->db_select("nd_toko where status_aktif = 1 LIMIT 1");
		$rekam_faktur_pajak_id = $this->input->get('id');
		$limit = $this->input->get('limit');
		$cond_limit = '';
		if ($limit != '' && $limit != 'undefined') {
			$cond_limit = "LIMIT ".$limit;
		}

		$kode_toko = '';
		$nama_toko = '';
		$alamat_toko = '';
		$email_toko = '';
		$host_toko = '';
		$relay_email_toko = '';
		$telepon_toko = '';

		foreach ($res_toko as $row) {
			$kode_toko = $row->pre_po;
			$nama_toko = $row->nama;
			$alamat_toko = $row->alamat;
			$email_toko = $row->email;
			$host_toko = $row->host;
			$telepon_toko = $row->telepon;
			$relay_email_toko = $row->relay_mail;
		}

		$message_list = array();
		$parts = array();
		$headers = array();
		$idx = 0;
		$head = array();

		$data = $this->pjk_model->get_rekam_faktur_pajak_email($rekam_faktur_pajak_id, "WHERE npwp != '' AND npwp is not null AND (draft_id is null or draft_id = '') and email != '' ", $cond_limit);

		$get_db = $this->common_model->db_select('nd_rekam_faktur_pajak_email where rekam_faktur_pajak_id='.$rekam_faktur_pajak_id);
		$rekam_email_id = array();
		foreach ($get_db as $row) {
			$rekam_email_id[$rekam_faktur_pajak_id][$row->customer_id] = $row->id;
		}


		foreach ($data as $key => $value) {	
		
			if ($value->email != '' && !isset($rekam_email_id[$rekam_faktur_pajak_id][$value->customer_id] )  ) {
				# code...
				//=====================================multipart==========================================
			
				$boundary1 = "000000000142536475869"; //buat pdf
				$boundary2 = "000000000475869142536"; //buat text
			
				$idx = 0;
				$second = str_pad($idx,2,"0",STR_PAD_LEFT);
				$idx+=2;
				$header_msg = "Date:".date('D, d M Y H:i:'.$second)."\r\n";
				$header_msg .= "Message-ID:PLT-".$kode_toko.date('ymdHis').rand(100,999)."\r\n";
				$header_msg .= 'MIME-Version: 1.0\r\n'."\r\n";
				$header_msg .= "Subject:Pajak Keluaran $value->nama\r\n";
				$header_msg .= "From:$nama_toko<$relay_email_toko>\r\n";
				$header_msg .= "To: $value->email\r\n";
				$header_msg .= 'Content-Type: multipart/mixed; boundary=000000000142536475869'."\r\n\r\n";
			
				$header_msg .= "--$boundary1\r\n";
				$header_msg .= 'Content-Type: multipart/alternative; boundary=000000000475869142536'."\r\n\r\n";
			
				// ================message
				$message = "--$boundary2\r\n";
				$message .= 'Content-Type:text/html; charset=utf-8'."\r\n";
				$message .= 'Content-Transfer-Encoding: quoted-printable'."\r\n\r\n";
			
				$message .= 'Kepada Yth.<br>'.
				$value->nama."<br><br>
				Berikut adalah file attachment faktur pajak keluaran $nama_toko<br><br>
				
				Regards,<br>
				Anthony Tedjasukmana<br><br>
				
				
				$nama_toko<br>
				$alamat_toko<br>
				West Java, Indonesia<br>
				40181<br><br>
				
				&#9742; : 0812 2313 0909 // $telepon_toko<br>
				&#9993; : <a href='mailto:".$relay_email_toko."'>$relay_email_toko</a> // <a href='mailto:$email_toko'>$email_toko</a>\r\n\r\n";
				$message .= "--$boundary2--\r\n";
				
			
			
				// ================attachment
		
				
				$filter_nama = [',','.',' ','/'];
				$filter_nama_cust = ["'","/","&"];
				$no_faktur_pajak = explode('??', $value->no_faktur_pajak);
				$tanggal_invoice = explode(',', $value->tanggal_invoice);
			
				$attachment_list = ''; 
				$file_loc_list = array();
				$attachment = '';
				foreach ($no_faktur_pajak as $key2 => $baris) {
					$break_name = explode('.', $baris);
					$filename = str_replace($filter_nama, '_', $nama_toko.'-'.str_replace($filter_nama_cust, '', trim($value->nama) )).'-'.date('dmY',strtotime($tanggal_invoice[$key2])).'-'.end($break_name).'.pdf';
					$file_loc = "https://".$host_toko."/fp_list/fp_".$rekam_faktur_pajak_id.'/'.$filename;
					$pdf64 = chunk_split(base64_encode(file_get_contents($file_loc)));
					array_push($file_loc_list, $file_loc);
				
			
					//===================================================
					$attachment .= "--$boundary1\r\n";
					$attachment .= 'Content-Type: Application/pdf; name='.$filename."\r\n";
					$attachment .= 'Content-Disposition: attachment; filename='.$filename."\r\n";
					$attachment .= 'Content-Transfer-Encoding: base64'."\r\n\r\n";        
					$attachment .= $pdf64;
					$attachment .= "\r\n\r\n";
		
					
				}
		
				$attachment .= "--$boundary1--\r\n";
				$attachment .= base64_encode($attachment);
				
				
				$header_64 = base64_encode($header_msg);
				$message_64 = base64_encode($message);
			
				// $msg_encoded = $header_64.$message_64.$attachment_64;
				$msg_encoded = base64_encode($header_msg.$message.$attachment);
				// $raw_encoded = base64_encode($raw_msg.$body_raw);
			
				// $parts_body = array(
				// 	"data" => $msg_encoded,
				// 	// "size" => strlen($msg_encoded)
				// );
			
				// $parts[$key] = array(
				// 	"mimeType" => "text/html", 
				// 	"headers"=> $parts_headers,
				// 	"body" => $parts_body
				// );

				if (!isset($rekam_email_id[$rekam_faktur_pajak_id][$value->customer_id])) {
					$rekam_email_id[$rekam_faktur_pajak_id][$value->customer_id] = '';
				}
				
				array_push($head, array(
					'rekam_email_id'=>$rekam_email_id[$rekam_faktur_pajak_id][$value->customer_id],
					'nama' => $value->nama,
					'customer_id' => $value->customer_id
				));
				
				array_push($message_list, array(
					'message' => array(
						'raw' => $msg_encoded,
						// 'raw_html' => $msg_encoded,
						// 'headers'=>$headers,
					)
				));
			}
		}

		$parts['head'] = $head;
		$parts['body'] = $message_list;
		$parts['data'] = $data;

		echo json_encode($parts);

	}

	function email_single_list_body(){

		$res_toko = $this->common_model->db_select("nd_toko where status_aktif = 1 LIMIT 1");
		$rekam_faktur_pajak_id = $this->input->get('rekam_faktur_pajak_id');
		$rekam_faktur_pajak_email_id = $this->input->get('rekam_faktur_pajak_email_id');
		$customer_id = $this->input->get('customer_id');

		$kode_toko = '';
		$nama_toko = '';
		$alamat_toko = '';
		$email_toko = '';
		$host_toko = '';
		$relay_email_toko = '';
		$telepon_toko = '';

		foreach ($res_toko as $row) {
			$kode_toko = $row->pre_po;
			$nama_toko = $row->nama;
			$alamat_toko = $row->alamat;
			$email_toko = $row->email;
			$host_toko = $row->host;
			$telepon_toko = $row->telepon;
			$relay_email_toko = $row->relay_mail;
		}

		$message_list = array();
		$parts = array();
		$headers = array();
		$idx = 0;
		$head = array();

		$data = $this->pjk_model->get_rekam_faktur_pajak_email($rekam_faktur_pajak_id, "WHERE npwp != '' AND npwp is not null AND t2.customer_id=$customer_id ",'');

		$get_db = $this->common_model->db_select('nd_rekam_faktur_pajak_email where rekam_faktur_pajak_id='.$rekam_faktur_pajak_id);
		$rekam_email_id = array();
		foreach ($get_db as $row) {
			$rekam_email_id[$rekam_faktur_pajak_id][$row->customer_id] = $row->id;
		}


		foreach ($data as $key => $value) {	
		
			if ($value->email != '') {
				//=====================================multipart==========================================
			
				$boundary1 = "000000000142536475869"; //buat pdf
				$boundary2 = "000000000475869142536"; //buat text
			
				$idx = 0;
				$second = str_pad($idx,2,"0",STR_PAD_LEFT);
				$idx+=2;
				$header_msg = "Date:".date('D, d M Y H:i:'.$second)."\r\n";
				$header_msg .= "Message-ID:PLT-".$kode_toko.date('ymdHis').rand(100,999)."\r\n";
				$header_msg .= 'MIME-Version: 1.0\r\n'."\r\n";
				$header_msg .= "Subject:Pajak Keluaran $value->nama\r\n";
				$header_msg .= "From:$nama_toko<$relay_email_toko>\r\n";
				$header_msg .= "To: $value->email\r\n";
				$header_msg .= 'Content-Type: multipart/mixed; boundary=000000000142536475869'."\r\n\r\n";
			
				$header_msg .= "--$boundary1\r\n";
				$header_msg .= 'Content-Type: multipart/alternative; boundary=000000000475869142536'."\r\n\r\n";
			
				// ================message
				$message = "--$boundary2\r\n";
				$message .= 'Content-Type:text/html; charset=utf-8'."\r\n";
				$message .= 'Content-Transfer-Encoding: quoted-printable'."\r\n\r\n";
			
				$message .= 'Kepada Yth.<br>'.
				$value->nama."<br><br>
				Berikut adalah file attachment faktur pajak keluaran $nama_toko<br><br>
				
				Regards,<br>
				Anthony Tedjasukmana<br><br>
				
				
				$nama_toko<br>
				$alamat_toko<br>
				West Java, Indonesia<br>
				40181<br><br>
				
				&#9742; : 0812 2313 0909 // $telepon_toko<br>
				&#9993; : <a href='mailto:".$relay_email_toko."'>$relay_email_toko</a> // <a href='mailto:$email_toko'>$email_toko</a>\r\n\r\n";
				$message .= "--$boundary2--\r\n";
				
			
			
				// ================attachment
			
				
				$filter_nama = [',','.',' ','/'];
				$filter_nama_cust = ["'","/","&"];
				$no_faktur_pajak = explode('??', $value->no_faktur_pajak);
				$tanggal_invoice = explode(',', $value->tanggal_invoice);
			
				$attachment_list = ''; 
				$file_loc_list = array();
				$attachment = '';
				foreach ($no_faktur_pajak as $key2 => $baris) {
					$break_name = explode('.', $baris);
					$filename = str_replace($filter_nama, '_', $nama_toko.'-'.str_replace($filter_nama_cust, '', trim($value->nama) )).'-'.date('dmY',strtotime($tanggal_invoice[$key2])).'-'.end($break_name).'.pdf';
					$file_loc = "https://".$host_toko."/fp_list/fp_".$rekam_faktur_pajak_id.'/'.$filename;
					$pdf64 = chunk_split(base64_encode(file_get_contents($file_loc)));
					array_push($file_loc_list, $file_loc);
				
			
					//===================================================
					$attachment .= "--$boundary1\r\n";
					$attachment .= 'Content-Type: Application/pdf; name='.$filename."\r\n";
					$attachment .= 'Content-Disposition: attachment; filename='.$filename."\r\n";
					$attachment .= 'Content-Transfer-Encoding: base64'."\r\n\r\n";        
					$attachment .= $pdf64;
					$attachment .= "\r\n\r\n";
		
					
				}
		
				$attachment .= "--$boundary1--\r\n";
				$attachment .= base64_encode($attachment);
				
				
				$header_64 = base64_encode($header_msg);
				$message_64 = base64_encode($message);
			
				$msg_encoded = base64_encode($header_msg.$message.$attachment);

				if (!isset($rekam_email_id[$rekam_faktur_pajak_id][$value->customer_id])) {
					$rekam_email_id[$rekam_faktur_pajak_id][$value->customer_id] = '';
				}
				
				array_push($head, array(
					'rekam_email_id'=>$rekam_email_id[$rekam_faktur_pajak_id][$value->customer_id],
					'nama' => $value->nama,
					'customer_id' => $value->customer_id
				));
				
				array_push($message_list, array(
					'message' => array(
						'raw' => $msg_encoded
					)
				));
			}
		}

		$parts['head'] = $head;
		$parts['body'] = $message_list;

		echo json_encode($parts);

	}

	function email_list_drafts(){

		$rekam_faktur_pajak_id = $this->input->get('id');
		$get_list = $this->pjk_model->get_draft_list($rekam_faktur_pajak_id);
		echo json_encode($get_list);
	}

	function upload_dropzone_faktur_pajak(){
		$id = $this->input->post('id');
		if(!empty($_FILES)){
			// $tempFile = $_FILES['file']['tmp_name'];
			// $targetPath = './image/sparepart';
			// $targetFile = $targetPath.$fileName;
			// move_uploaded_file($tempFile, $targetFile);

			$fileName = $_FILES['file']['name'];
			$break = explode('-', $fileName);

			foreach ($this->toko_list_aktif as $row) {
				$nama_toko = $row->nama;
			}
			$nama_file = trim(substr($break[1],-8));
			$nama_cust = '';
			$result = $this->pjk_model->get_data_dari_no_faktur($id, $nama_file);
			$tanggal_invoice = '';
			foreach ($result as $row) {
				$nama_cust = trim($row->nama);
				$tanggal_invoice = date('dmY', strtotime($row->tanggal_invoice));
			}

			if (!is_dir('fp_list/fp_'.$id)) {
			    mkdir('./fp_list/fp_'.$id, 0777, TRUE);
			}

			$filter = [',','.',' '];
			$config['upload_path'] = './fp_list/fp_'.$id;	
			$config['allowed_types'] = 'pdf';
			$config['file_name'] = trim(str_replace($filter, '_', $nama_toko)).($nama_cust != '' ? '-'.str_replace($filter, '_', $nama_cust) : '').($tanggal_invoice !='' ? '-'.$tanggal_invoice :'').'-'.substr($break[1],-8).'.pdf';
			$this->load->library('upload',$config);
			// echo $config['file_name'];
			//$this->upload->initialize($config);
			if(!$this->upload->do_upload("file")){
				$error = array('eror' => $this->upload->display_errors());
				print_r($error);
			}else{
				$data = array('upload_data' => $this->upload->data());
				print_r($data);
			}
		}
	}

	function remove_pdf(){
		$id = $this->input->post('id');
		$filename = $this->input->post('filename');

		unlink('./fp_list/fp_'.$id.'/'.$filename);
		echo "OK";
		}

	function get_pajak_pdf_list(){
		$id = $this->input->post('id');
		unset($dir);
		$this->load->helper('directory');
		$dir = 'fp_list/fp_'.$id;
		$map = directory_map($dir);

		echo json_encode($map);

	}

	function kirim_pajak_email(){
		$this->config_email = array(
		    'protocol' => 'mail',
		    'mailtype' => 'html',
		    'charset'   => 'utf-8'
		);

		$config = $this->config_email;
		$this->load->library('email', $config);
		$this->email->set_newline("\r\n");
		
		$id = $this->input->get('id');
		
		foreach ($this->toko_list_aktif as $row) {
			$nama_toko = $row->nama;
			$alamat_toko = $row->alamat;
			$telepon_toko = $row->telepon;
			$email_toko = $row->email;
			$host = $row->host;
			$relay_mail = $row->relay_mail;
		}

		$fp_list = $this->pjk_model->get_rekam_faktur_pajak_filter_email($id);
		$filter_nama = [',','.',' '];
		$filter_nama_cust = ["'","/", "&"];

		foreach ($fp_list as $row) {
			$filter = 1;
			if ($row->email != '') {
				$this->email->clear(TRUE);
				unset($no_fp);

				$tanggal_invoice = explode(',', $row->tanggal_invoice);
				$no_fp = explode('??', $row->no_faktur_pajak);
				foreach ($no_fp as $key => $value) {
					$break_nama = explode('.', $value);
					$nama_file[$key] = str_replace($filter_nama, '_', $nama_toko.'-'.str_replace($filter_nama_cust, '', trim($row->nama))).'-'.date('dmY',strtotime($tanggal_invoice[$key])).'-'.end($break_nama).'.pdf';
					if (!file_exists("./fp_list/fp_".$id."/".$nama_file[$key])) {$filter--;}	
				}
				if ($filter == 1) {
					// $row->email = 'jong_xiang@ymail.com, hendry0485@gmail.com';

					$date = date('d/m/Y H:i:s');
					$this->email->from($relay_mail, "Pajak Keluaran ".$nama_toko);
					$list = explode(',',$row->email);
					// $list = $this->email_target;
					$body = "Kepada Yth.<br/>";
					$body .= $row->nama."<br/><br/>";
					$body .= "<p>Berikut adalah file attachment faktur pajak keluaran $nama_toko</p>";
					$body .= "<p>Regards, <br/><br/> 
							<b>Anthony Tedjasukmana</b></p>";

					// $body .= "<p><img style='width:70px' src='".base_url()."image/LOGO_MED.png'></p>";

					$body .="<p style='color:#990000'><b>$nama_toko</b>
							<br/>$alamat_toko, Bandung
							<br/>West Java, Indonesia
							<br/>40181
							
							</p>";

					$body .= "<p style='color:#990000'>";
					$body .= "\xF0\x9F\x93\x9E".": 0812 2313 0909 // ".$telepon_toko."<br/>";
					$body .= "\xE2\x9C\x89".": ".$relay_mail.' // '.$email_toko;
					$body .= "</p>";

					$this->email->to($list);
					$this->email->subject('Faktur Pajak Keluaran '.$nama_toko);
					$this->email->message($body);
					foreach ($no_fp as $key => $value) {
						$attach = "/var/www/html/".$host."/public_html/fp_list/fp_".$id."/".$nama_file[$key];
						$this->email->attach($attach);
					}
					if (is_posisi_id() != 1) {
						# code...
						$result = $this->email->send();
	
	
						$get_db = $this->common_model->db_select('nd_rekam_faktur_pajak_email where customer_id='.$row->customer_id.' AND rekam_faktur_pajak_id='.$id);
						$id_detail = '';
						foreach ($get_db as $row2) {
							$id_detail = $row->id;
						}
						if ($result) {
							$data_insert = array(
								'rekam_faktur_pajak_id' => $id ,
								'customer_id' => $row->customer_id,
								'email_stat' => 1
								);
							if ($id_detail == '') {
								$this->common_model->db_insert('nd_rekam_faktur_pajak_email', $data_insert);
							}else{
								$this->common_model->db_update('nd_rekam_faktur_pajak_email', $data_insert,'id',$id_detail);
							}
						}	
					}else{
						echo $body;
						echo '<hr/>';
					}
					/*$msg = "Test w etec";
					$emailnya = "hendry0485@gmail.com";
					$file ='dummy.pdf';
					$result = shell_exec('echo "'.$msg.'" | mutt -s testing -a /var/www/html/sistem2019.favourtdj.com/public_html/pdf/'.$file.' -- '.$emailnya);*/

					
					// ECHO $result;
				}
			}
		}

		redirect(is_setting_link('pajak/rekam_faktur_email_list')."?id=".$id);
	}

	function update_email_send_manual(){
		$id = $this->input->post('id');
		$customer_id = $this->input->post('customer_id');
		$get_data = $this->common_model->db_select("nd_rekam_faktur_pajak_email where rekam_faktur_pajak_id=".$id." AND customer_id=".$customer_id);
		$rekam_faktur_pajak_email_id = '';
		foreach ($get_data as $row) {
			$rekam_faktur_pajak_email_id=$row->id;
		}

		if ($rekam_faktur_pajak_email_id == '') {
			$data = array(
				'rekam_faktur_pajak_id' => $id,
				'customer_id' => $customer_id );
			$result_id = $this->common_model->db_insert("nd_rekam_faktur_pajak_email", $data);
			$rekam_faktur_pajak_email_id = $result_id;
		}

		foreach ($this->input->post('status') as $key => $value) {
			$status[$value] = 1;
		}

		$data_status = array(
			'status_1' => (isset($status[1]) ? 1 : 0 ) ,
			'status_2' => (isset($status[2]) ? 1 : 0 ) ,
			'status_3' => (isset($status[3]) ? 1 : 0 ) ,
			'status_4' => (isset($status[4]) ? 1 : 0 ) ,
			);

		if (!isset($status[4])) {
			$data_ket = array('keterangan' => '' );
			$this->common_model->db_update("nd_rekam_faktur_pajak_email",$data_ket,'id',$rekam_faktur_pajak_email_id);
		}

		$this->common_model->db_update("nd_rekam_faktur_pajak_email",$data_status,"id", $rekam_faktur_pajak_email_id);

		echo "OK";
		// print_r($this->input->post());
		// redirect(is_setting_link('pajak/rekam_faktur_email_list')."?id=".$id);
	}

	function update_email_send_keterangan(){
		$id = $this->input->post('id');
		$customer_id = $this->input->post('customer_id');
		$cond = array('rekam_faktur_pajak_id' => $id ,
			'customer_id' => $customer_id );
		$data = array('keterangan' => $this->input->post('keterangan') );

		$this->common_model->db_update_multiple_cond("nd_rekam_faktur_pajak_email",$data,$cond);

		echo "OK";
		// print_r($this->input->post());
		// redirect(is_setting_link('pajak/rekam_faktur_email_list')."?id=".$id);
	}

	function insert_email_send_keterangan_batch(){

		$inputJSON = file_get_contents('php://input');
		$data = json_decode($inputJSON, TRUE);
		$affectedRows = $this->common_model->db_insert_batch('nd_rekam_faktur_pajak_email', $data	);
		// $this->db->affectedRows();
		
		echo json_encode([
			'status' => 200,
			'message' => 'Success',
			'data' => $data,
			'affected_rows' => $affectedRows
		]);
		
	}


	function update_email_send_keterangan_batch(){

		$inputJSON = file_get_contents('php://input');
		$data = json_decode($inputJSON, TRUE);
		$affectedRows = $this->common_model->db_update_batch('nd_rekam_faktur_pajak_email', $data, 'id');
		// $this->db->affectedRows();
		
		echo json_encode([
			'status' => 200,
			'message' => 'Success',
			'data' => $data,
			'affected_rows' => $affectedRows
		]);
		
	}

	function kirim_pajak_email_satuan(){

		$this->config_email = array(
		    'protocol' => 'mail',
		    'mailtype' => 'html',
		    'charset'   => 'utf-8'
		);

		$config = $this->config_email;
		$this->load->library('email', $config);
		$this->email->set_newline("\r\n");
		
		$id = $this->input->get('id');
		$customer_id = $this->input->get('customer_id');
		$filter_nama = [',','.',' '];
		$filter_nama_cust = ["'","/","&"];
		
		$pdf_list = explode('??', $this->input->post('pdf_list'));
		$data_toko = $this->common_model->db_select('nd_toko limit 1');
		foreach ($data_toko as $row) {
			$nama_toko = $row->nama;
			$alamat_toko = $row->alamat;
			$telepon_toko = $row->telepon;
			$email_toko = $row->email;
			$host = $row->host;
			$relay_mail = $row->relay_mail;
		}

		$fp_list = $this->pjk_model->get_rekam_faktur_pajak_email($id, "WHERE email != '' AND t2.customer_id = ".$customer_id,'');

		foreach ($fp_list as $row) {
			$filter = 1;
			if ($row->email != '') {
				// $row->email = 'jong_xiang@ymail.com, hendry0485@gmail.com';

				$pdf_list = explode('??', $row->no_faktur_pajak);
				$tanggal_invoice = explode(',', $row->tanggal_invoice);
				foreach ($pdf_list as $key => $value) {
					$break_nama = explode('.', $value);
					$nama_file[$key] = str_replace($filter_nama, '_', $nama_toko.'-'.str_replace($filter_nama_cust, '', trim($row->nama))).'-'.date('dmY',strtotime($tanggal_invoice[$key])).'-'.end($break_nama).'.pdf';
					if (!file_exists("./fp_list/fp_".$id."/".$nama_file[$key])) {$filter--;}		
				}
				if ($filter == 1) {
					$date = date('d/m/Y H:i:s');
					$this->email->from($relay_mail, "Pajak Keluaran ".$nama_toko);
					$list = explode(',',$row->email);
					// $list = $this->email_target;
					$body = "Kepada Yth.<br/>";
					$body .= $row->nama."<br/><br/>";
					$body .= "<p>Berikut adalah file attachment faktur pajak keluaran $nama_toko</p>";
					$body .= "<p>Regards, <br/><br/> 
							<b>Anthony Tedjasukmana</b></p>";

					// $body .= "<p><img style='width:70px' src='".base_url()."image/LOGO_MED.png'></p>";

					$body .="<p style='color:#990000'><b>$nama_toko</b>
							<br/>$alamat_toko, Bandung
							<br/>West Java, Indonesia
							<br/>40181
							</p>";

					$body .= "<p style='color:#990000'>";
					$body .= "\xF0\x9F\x93\x9E".": 0812 2313 0909 // ".$telepon_toko."<br/>";
					$body .= "\xE2\x9C\x89".": ".$relay_mail.' // '.$email_toko;
					$body .= "</p>";

					$this->email->to($list);
					$this->email->subject('Faktur Pajak Keluaran '.$nama_toko);
					$this->email->message($body);
					foreach ($pdf_list as $key => $value) {
						$attach = "/var/www/html/".$host."/public_html/fp_list/fp_".$id."/".$nama_file[$key];
						$this->email->attach($attach);
					}

 						// echo "<img src=".base_url().'pdf/profile_pict.jpg'.">";
					
					// echo site_url().'pdf/dummy.pdf';
					$result = $this->email->send();
					/*$msg = "Test w etec";
					$emailnya = "hendry0485@gmail.com";
					$file ='dummy.pdf';
					$result = shell_exec('echo "'.$msg.'" | mutt -s testing -a /var/www/html/sistem2019.favourtdj.com/public_html/pdf/'.$file.' -- '.$emailnya);*/

					
					// ECHO $result;
				}
			}
		}

		redirect(is_setting_link('pajak/rekam_faktur_email_list')."?id=".$id);
	}

	function kirim_pajak_email_satuan_test(){

		$this->config_email = array(
		    'protocol' => 'mail',
		    'mailtype' => 'html',
		    'charset'   => 'utf-8'
		);

		$config = $this->config_email;
		$this->load->library('email', $config);
		$this->email->set_newline("\r\n");
		
		$id = $this->input->get('id');
		$customer_id = $this->input->get('customer_id');
		$filter_nama = [',','.',' '];
		
		// $pdf_list = explode('??', $this->input->post('pdf_list'));
		$data_toko = $this->common_model->db_select('nd_toko limit 1');
		foreach ($data_toko as $row) {
			$nama_toko = $row->nama;
			$alamat_toko = $row->alamat;
			$telepon_toko = $row->telepon;
			$email_toko = $row->email;
			$host = $row->host;
			$relay_mail = $row->relay_mail;
		}

		
		$email = 'jong_xiang@ymail.com';

		$date = date('d/m/Y H:i:s');
		$this->email->from($relay_mail, "Pajak Keluaran ".$nama_toko);
		$list = explode(',',$email);
		// $list = $this->email_target;
		$body = "Kepada Yth.<br/>";
		$body .= "HENDRY LIOENARDI<br/><br/>";
		$body .= "<p>Berikut adalah file attachment faktur pajak keluaran $nama_toko</p>";
		$body .= "<p>Regards, <br/><br/> 
				<b>Anthony Tedjasukmana</b></p>";

		$body .= "<p><img style='width:70px' src='".base_url()."image/LOGO_MED.png'></p>";

		$body .="<p style='color:#990000'><b>$nama_toko</b>
				<br/>$alamat_toko, Bandung
				<br/>West Java, Indonesia
				<br/>40181
				</p>";

		$body .= "<p style='color:#990000'>";
		$body .= "\xF0\x9F\x93\x9E".": 0812 2313 0909 // ".$telepon_toko."<br/>";
		$body .= "\xE2\x9C\x89".": ".$relay_mail.' // '.$email_toko;
		$body .= "</p>";

		$this->email->to($list);
		$this->email->subject('Faktur Pajak Keluaran '.$nama_toko);
		$this->email->message($body);
		$attach = "/var/www/html/".$host."/public_html/image/dummy.pdf";
		$this->email->attach($attach);

		// echo $body;
		

		// echo "<img src=".base_url().'pdf/profile_pict.jpg'.">";
		
		// echo site_url().'pdf/dummy.pdf';
		$result = $this->email->send();
		/*$msg = "Test w etec";
		$emailnya = "hendry0485@gmail.com";
		$file ='dummy.pdf';
		$result = shell_exec('echo "'.$msg.'" | mutt -s testing -a /var/www/html/sistem2019.favourtdj.com/public_html/pdf/'.$file.' -- '.$emailnya);*/

		
		// ECHO $result;

		// redirect(is_setting_link('pajak/rekam_faktur_email_list')."?id=".$id);
	}

	function test_kirim_mail(){
		$this->config_email = array(
		    'protocol' => 'mail',
		    'mailtype' => 'html',
		    'charset'   => 'utf-8'
		);

		$config = $this->config_email;
		$this->load->library('email', $config);
		$this->email->set_newline("\r\n");
		$email_cust = $this->input->post('email');
		$nama_cust = $this->input->post('nama');
		$id = $this->input->post('id');
		$pdf_list = explode('??', $this->input->post('pdf_list'));
		$data_toko = $this->common_model->db_select('nd_toko limit 1');
		foreach ($data_toko as $row) {
			$nama_toko = $row->nama;
			$host = $row->host;
			$relay_mail = $row->relay_mail;
		}


		$date = date('d/m/Y H:i:s');
		$this->email->from($relay_mail, "web-system");
		$list = array($email_cust);
		// $list = $this->email_target;
		$body = "Kepada Yth.<br/>";
		$body .= $nama_cust."<br/><br/>";
		$body .= "<p>Berikut adalah file attachment faktur pajak keluaran $nama_toko</p>";
		$body .= "<p>Regards, <br/><br/> Anthony Tedjasukmana<br/>Bandung, Indonesia</p>";

		$this->email->to($list);
		$this->email->subject('Faktur Pajak Keluaran '.$nama_toko);
		$this->email->message($body);
		foreach ($pdf_list as $key => $value) {
			$attach = "/var/www/html/".$host."/public_html/fp_list/fp_".$id."/".substr($value, -7).".pdf";
			$this->email->attach($attach);
		}
		// echo "<img src=".base_url().'pdf/profile_pict.jpg'.">";
		
		// echo site_url().'pdf/dummy.pdf';
		$result = $this->email->send();
		/*$msg = "Test w etec";
		$emailnya = "hendry0485@gmail.com";
		$file ='dummy.pdf';
		$result = shell_exec('echo "'.$msg.'" | mutt -s testing -a /var/www/html/sistem2019.favourtdj.com/public_html/pdf/'.$file.' -- '.$emailnya);*/


		ECHO $result;
	}

	function rekam_faktur_pajak_email_lock(){
		$id = $this->input->get('id');
		$data = array('status_email' =>0, 'locked_date' => date("Y-m-d H:i:s") );
		$this->common_model->db_update("nd_rekam_faktur_pajak", $data,'id',$id);
		redirect(is_setting_link('pajak/rekam_faktur_email_list')."?id=".$id);

	}

	function pajak_email_request_open(){
		$id = $this->input->post('id');
		$data = array(
			'status_email' => 1 );
		$this->common_model->db_update("nd_rekam_faktur_pajak", $data,'id', $id);
		redirect(is_setting_link('pajak/rekam_faktur_email_list').'?id='.$id);
	}

	// =====================new by fetch===========================
	function email_data_status_insert(){
		$raw_data =  json_decode($this->input->post('data'));
		// var_dump($raw_data);
		$id = $this->input->post('id');
		$customer_id = $this->input->post('customer_id');
		$rekam_faktur_pajak_email_id = $this->input->post('rekam_faktur_pajak_email_id');

		$data = array(
			'rekam_faktur_pajak_id' => $id,
			'customer_id' => $customer_id,
			'message_id' => $raw_data->id ,
			'email_stat' => 1,
			'thread_id' => $raw_data->threadId,
			'draft_id' => $this->input->post('draft_id'),
			'label_id' => $raw_data->labelIds[0]
		);
		
		if ($rekam_faktur_pajak_email_id == '') {
			$result_id = $this->common_model->db_insert("nd_rekam_faktur_pajak_email", $data);
			$rekam_faktur_pajak_email_id = $result_id;
		}else{
			$this->common_model->db_update("nd_rekam_faktur_pajak_email", $data,'id', $rekam_faktur_pajak_email_id);
		}
		echo json_encode($rekam_faktur_pajak_email_id);
	}

	function email_data_status_update(){
		
		$id = $this->input->post('id');

		$data = array(
			'label_id' => "SENT"
		);
		
		if ($id != '') {
			$this->common_model->db_update("nd_rekam_faktur_pajak_email", $data,'id', $id);
		}
		echo json_encode("OK");
	}

	function decode64Base(){
		$data = $this->input->post('data');
		// $data = 'S2VwYWRhIFl0aC48YnI-UFQuIFNBTSBBUFBBUkVMIE1BTlVGQUNUVVJJTkcgSU5ET05FU0lBPGJyPjxicj4NCgkJCQlCZXJpa3V0IGFkYWxhaCBmaWxlIGF0dGFjaG1lbnQgZmFrdHVyIHBhamFrIGtlbHVhcmFuIENWLiBQRUxJVEEgU0VKQVRJPGJyPjxicj4NCgkJCQkNCgkJCQlSZWdhcmRzLDxicj4NCgkJCQlBbnRob255IFRlZGphc3VrbWFuYTxicj48YnI-DQoJCQkJDQoJCQkJDQoJCQkJQ1YuIFBFTElUQSBTRUpBVEk8YnI-DQoJCQkJSkwuIE1BWU9SIFNVTkFSWUEgTk8uIDIyPGJyPg0KCQkJCVdlc3QgSmF2YSwgSW5kb25lc2lhPGJyPg0KCQkJCTQwMTgxPGJyPjxicj4NCgkJCQkNCgkJCQkmIzk3NDI7IDogMDgxMiAyMzEzIDA5MDkgLy8gMDIyIDIwNTI2MzUxPGJyPg0KCQkJCSYjOTk5MzsgOiA8YSBocmVmJ21haWx0bzpwYWphay5wZWxpdGFzZWphdGlAZ21haWwuY29tJz5wYWphay5wZWxpdGFzZWphdGlAZ21haWwuY29tPC9hPiAvLyA8YSBocmVmJ21haWx0bzpwZWxpdGEuc2VqYXRpQG91dGxvb2suY29tJz5wZWxpdGEuc2VqYXRpQG91dGxvb2suY29tPC9hPg0K';
		// echo "<hr/>";
		// $data = trim($data);
		$res = base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT));;
		// echo "<hr/>";
		// // print_r($data);
		// echo "<hr/>";
		// var_dump($res);
		// $res = rtrim(base64_encode(strip_tags($res)), '=');
		echo json_encode($res);
	}

	

//====================================laporan faktur pajak=========================================

	function laporan_faktur_pajak(){
		$menu = is_get_url($this->uri->segment(1)) ;
		$tahun = date("Y");
		if ($this->input->get('tahun') && $this->input->get('tahun') != '' ) {
			$tahun = $this->input->get('tahun');
		}

		$data = array(
			'content' =>'admin/pajak/laporan_faktur_pajak',
			'breadcrumb_title' => 'Pajak',
			'breadcrumb_small' => 'Daftar Faktur',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data'=> $this->data,
			'data_isi'=> $this->data,
			'tahun' => $tahun
			);

		$last_date = '2019-08-04';
		$get_last_date = $this->common_model->db_free_query_superadmin("SELECT *
			FROM (
				SELECT max(t2.tanggal) as last_date
				FROM nd_rekam_faktur_pajak_detail t1
				LEFT JOIN  nd_penjualan t2
				ON t1.penjualan_id = t2.id ) result
			WHERE last_date is not null
			");
		if (count($get_last_date->result()) > 0) {
			foreach ($get_last_date->result() as $row) {
				$last_date = $row->last_date;
			}
		}
		$data['user_id'] = is_user_id();
		$data['last_date'] = $last_date;

		$data['laporan_fp'] = $this->pjk_model->get_laporan_pajak("WHERE YEAR(tanggal) = '$tahun'");
		$this->load->view('admin/template',$data);

	}

	function rekap_pajak(){

		$tahun = $this->input->get('tahun');
		$bulan = $this->input->get('bulan');
		$tgl =  date("F Y");

		$t_faktur =0;
		$t_email =0;
		$t_kirim =0;
		$t_ambil =0;
		$t_whatsapp =0;
		$t_others =0;


		foreach ($this->toko_list_aktif as $row) {
			$nama_toko = $row->nama;
			$pre_po = $row->pre_po;
		}

		$laporan_pajak = $this->pjk_model->get_laporan_pajak("WHERE YEAR(tanggal) = '$tahun' AND MONTH(tanggal) = '$bulan'");
		foreach ($laporan_pajak as $row) {
			$faktur_pajak_id = explode(',', $row->faktur_pajak_id);
			$tanggal_start = explode(',', $row->tanggal_start);
			$tanggal_end = explode(',', $row->tanggal_end);
			$locked_date = explode(',', $row->locked_date);
			$jumlah_faktur = explode(',', $row->jumlah_faktur);
			$jumlah_faktur_npwp = explode(',', $row->jml_npwp);
			$jml_action_id = explode(',', $row->action_id);
			
			$email = explode(',', $row->jml_email);
			$kirim = explode(',', $row->jml_kirim);
			$ambil = explode(',', $row->jml_ambil);
			$whatsapp = explode(',', $row->jml_wa);
			$others = explode(',', $row->jml_others);
			
			$no_surat = explode(',', $row->no_surat);
			$created_at = explode(',', $row->created_at);
			$faktur_pajak_id = explode(',', $row->faktur_pajak_id);
			$list_rekam_faktur_id = $row->faktur_pajak_id;
		}

		$get_tgl = date("F Y",strtotime($tanggal_start[0]));

		$get_customer = $this->pjk_model->get_customer_by_status_surat_pajak($list_rekam_faktur_id);
		

		$this->load->library('fpdf17/fpdf_css');
		$this->load->library('fpdf17/fpdf');

		$pdf = new FPDF( 'L', 'mm', 'A4' );
		
		$pdf->AddPage();
		$pdf->SetMargins(15,0,10);
		$pdf->SetTextColor( 0,0,0 );

		$font_name = 'Arial';
		
		$pdf->SetFont( $font_name, '', 9 );
		//1x3
		// $pdf->Cell( 0, 3, $nama_supplier, 0, 1, 'R' );
		// $pdf->Cell( 0, 3, ',telp : '.$telepon_supplier, 0, 1, 'R' );
		$pdf->Ln();

		$pdf->SetFont( $font_name, '', 16 );
		//1x5
		$pdf->Cell( 0, 5, strtoupper('REPORT PAJAK '.$get_tgl), 0, 1, 'C' );
		$pdf->Ln();
		
		$pdf->SetFont( $font_name, '', 12 );
		$pdf->Cell( 0, 5, strtoupper($nama_toko), 0, 1, 'L' );
		$pdf->Cell( 0, 5, strtoupper('Periode : '.$tgl), 0, 1, 'L' );
		$pdf->Cell( 0, 5, strtoupper('Tanggal Download : '.date('d F Y')), 0, 1, 'L' );

		$pdf->Ln();

		//1x8
		$pdf->Cell( 10, 10, strtoupper('No'), 1, 0, 'C' );
		$pdf->Cell( 50, 10, strtoupper('NO SURAT'), 1, 0, 'C' );
		$pdf->Cell( 25, 10, strtoupper('TANGGAL'), 1, 0, 'C' );
		$pdf->Cell( 50, 10, strtoupper('LOCKED'), 1, 0, 'C' );
		$pdf->Cell( 20, 10, strtoupper('FAKTUR'), 1, 0, 'C' );
		$pdf->Cell( 20, 10, strtoupper('EMAIl'), 1, 0, 'C' );
		$pdf->Cell( 20, 10, strtoupper('KIRIM'), 1, 0, 'C' );
		$pdf->Cell( 20, 10, strtoupper('AMBIL'), 1, 0, 'C' );
		$pdf->Cell( 20, 10, strtoupper('WA'), 1, 0, 'C' );
		$pdf->Cell( 20, 10, strtoupper('OTHER'), 1, 1, 'C' );

		$baris = 16;
		$i = 1; $g_total = 0;
		foreach ($tanggal_start as $key => $value) {
			$t_email +=$email[$key];
			$t_kirim +=$kirim[$key];
			$t_ambil +=$ambil[$key];
			$t_whatsapp +=$whatsapp[$key];
			$t_others +=$others[$key];

			$t_faktur +=$jumlah_faktur_npwp[$key];

			$no_srt[$faktur_pajak_id[$key]] = $pre_po.'-'.date('Y',strtotime($created_at[$key])).'/01/'.str_pad($no_surat[$key], 3,'0', STR_PAD_LEFT);
			//1x7
			$pdf->Cell( 10, 8, $i, 1, 0, 'C' );
			$pdf->Cell( 50, 8, $no_srt[$faktur_pajak_id[$key]], 1, 0, 'C' );
			$pdf->Cell( 25, 8, date("d", strtotime($value)).' - '.date("d", strtotime($tanggal_end[$key])), 1, 0, 'C' );

			// $pdf->Cell( 20, 8, $jumlah_faktur[$key], 1, 0, 'C' );
			$pdf->Cell( 50, 8, ($locked_date[$key] != 0 ? is_reverse_datetime(trim($locked_date[$key])) : '-'), 1, 0, 'C' );
			$pdf->Cell( 20, 8, $jumlah_faktur_npwp[$key], 1, 0, 'C' );

			$pdf->Cell( 20, 8, ($email[$key] == 0 ? '-' : $email[$key]), 1, 0, 'C' );
			$pdf->Cell( 20, 8, ($kirim[$key] == 0 ? '-' : $kirim[$key]), 1, 0, 'C' );
			$pdf->Cell( 20, 8, ($ambil[$key] == 0 ? '-' : $ambil[$key]), 1, 0, 'C' );
			$pdf->Cell( 20, 8, ($whatsapp[$key] == 0 ? '-' : $whatsapp[$key]), 1, 0, 'C' );
			$pdf->Cell( 20, 8, ($others[$key] == 0 ? '-' : $others[$key] ), 1, 1, 'C' );
			// $g_total += $row->harga_beli*$row->qty; 
			$i++;
		}
			//1x7
			$pdf->Cell( 135, 8, "TOTAL", 1, 0, 'C' );
			// $pdf->Cell( 20, 8, $jumlah_faktur[$key], 1, 0, 'C' );
			$pdf->Cell( 20, 8, $t_faktur, 1, 0, 'C' );

			$pdf->Cell( 20, 8, $t_email, 1, 0, 'C' );
			$pdf->Cell( 20, 8, $t_kirim, 1, 0, 'C' );
			$pdf->Cell( 20, 8, $t_ambil, 1, 0, 'C' );
			$pdf->Cell( 20, 8, $t_whatsapp, 1, 0, 'C' );
			$pdf->Cell( 20, 8, $t_others, 1, 1, 'C' );

			

		
		//=============================================================

		// $pdf = new FPDF( 'P', 'mm', 'A4' );

		$pdf->AddPage('L','A4');
		$pdf->SetMargins(15,0,10);
		$pdf->SetTextColor( 0,0,0 );
		$pdf->Ln();
		$pdf->SetFont( $font_name, '', 10 );

		$t_0 = $t_email;
		$t_1 = $t_kirim;
		$t_2 = $t_ambil;
		$t_3 = $t_whatsapp;
		$t_4 = $t_others;

		$stat[0] = 'email';
		$stat[1] = 'kirim';
		$stat[2] = 'ambil';
		$stat[3] = 'whatsapp';
		$stat[4] = 'others';


		$idx= 0;
		foreach ($get_customer as $row) {
			$nama_cust[$idx] = ($row->tipe_company !='' ? $row->tipe_company.' ' : '').$row->nama;
			$tanggal_list[$idx] = explode('??', $row->tanggal);
			$no_fp_list[$idx] = explode('??', $row->no_fp);
			$status_email[$idx] = explode(',', $row->status_email);
			$status_kirim[$idx] = explode(',', $row->status_kirim);
			$status_ambil[$idx] = explode(',', $row->status_ambil);
			$status_whatsapp[$idx] = explode(',', $row->status_wa);
			$status_others[$idx] = explode(',', $row->status_others);
			$rekam_faktur_pajak_id[$idx] = explode(',', $row->rekam_faktur_pajak_id);
			$idx++;
		}


		for ($stat_idx=1; $stat_idx < 5 ; $stat_idx++) { 
			$i = 1; $g_total = 0;
			# code...
			if (${'t_'.$stat_idx} > 0) {
				$pdf->SetFont( $font_name, '', 16 );
				$pdf->Cell( 0, 5, strtoupper($stat[$stat_idx]), 0, 1, 'L' );
				$pdf->SetFont( $font_name, '', 10 );
				$pdf->Cell( 10, 8, strtoupper('No'), 1, 0, 'C' );
				$pdf->Cell( 80, 8, strtoupper('Nama'), 1, 0, 'C' );
				$pdf->Cell( 30, 8, strtoupper('Invoice'), 1, 0, 'C' );
				$pdf->Cell( 50, 8, strtoupper('No FP'), 1, 0, 'C' );
				$pdf->Cell( 50, 8, strtoupper('Keterangan'), 1, 1, 'C' );
				foreach (${'status_'.$stat[$stat_idx]} as $kunci => $isi) {
					$urutan_fp = 0;
					unset($tgl_i);
					unset($no_f_pjk);
					unset($itung);
					foreach ($isi as $key => $value) {
						if ($value != 0) {
							$nama = $nama_cust[$kunci];

							$no_fp = explode(',', $no_fp_list[$kunci][$key]);
							$tgl_invoice = explode(',', $tanggal_list[$kunci][$key]);
							$ns[$rekam_faktur_pajak_id[$kunci][$key]] = $no_srt[$rekam_faktur_pajak_id[$kunci][$key]];

							foreach ($tgl_invoice as $key2 => $value2) {
								$itung[$rekam_faktur_pajak_id[$kunci][$key] ] = (isset($itung[$rekam_faktur_pajak_id[$kunci][$key] ]) ? $itung[$rekam_faktur_pajak_id[$kunci][$key] ] +1 : 1 ); 
								$tgl_i[$urutan_fp] = $value2;
								$no_f_pjk[$urutan_fp] = $no_fp[$key2];
								$urutan_fp++;
							}
						}
					}

					if ($urutan_fp > 0) {

						$pdf->Cell( 10, 7*$urutan_fp, $i, 1, 0, 'C' );
						$pdf->Cell( 80, 7*$urutan_fp, $nama_cust[$kunci], 1, 0, 'L' );
						$posYbefore = $pdf->GetY();
						for ($j=0; $j < $urutan_fp ; $j++) {
							$pdf->SetX(105); 
							$pdf->Cell( 30, 7, is_reverse_date($tgl_i[$j]), 1, 0, 'C' );
							$pdf->Cell( 50, 7, $no_f_pjk[$j], 1, 1, 'C' );
							$posYafter = $pdf->GetY();
						}
						$show = '';
						$pdf->SetY($posYbefore);
						foreach ($itung as $key_ => $value_) {
							// $show .= '('.$ns[$key_].') - '.$value_;
							$pdf->SetX(185);
							$pdf->Cell( 50, 7*$value_, $ns[$key_], 1, 1, 'C' );
						}
						$pdf->SetY($posYafter);

						$i++;
					}
				}
			}
			$pdf->Ln();
		}


		$pdf->SetAutoPageBreak(false);
		// $pdf->AddPage();

		$pdf->Output( 'Laporan Pajak '.$nama_toko.' '.$get_tgl.'.pdf', "D" );
		// echo $sisa;	
	}

//==============================download=============================

	function download_all_pajak_pdf(){
		$id=$this->input->get('id');
		if ($id != '') {
			# code...
			//================== load library ==============================
			$this->load->library('zip');
			$this->load->helper('directory');
			$dir = 'fp_list/fp_'.$id;

			//================== get data nama tahun ==============================
			$get_data_fp = $this->common_model->db_select("nd_rekam_faktur_pajak WHERE id=".$id);
			foreach ($get_data_fp as $row) {
				$tahun = date("Y", strtotime($row->created_at));
				$no_surat = $row->no_surat;
			}

			//================== data toko ==============================
			foreach ($this->toko_list_aktif as $row) {
				$nama_toko = $row->nama;
				$pre_po = $row->pre_po;
			}

			$nama_surat = $pre_po.'-'.$tahun.'_01_'.str_pad($no_surat, 3,'0', STR_PAD_LEFT);

			//================== create directory ==============================
			if (!is_dir($dir."/$nama_surat")) {
			    mkdir($dir."/$nama_surat", 0777, TRUE);
			}


			//================== copas file fp ==============================
			$fp_list = $this->pjk_model->get_rekam_faktur_pajak_email($id, "WHERE t4.id is not null",'');
			$filter_nama = [',','.',' '];
			$filter_nama_cust = ["'","/","&"];

			foreach ($fp_list as $row) {
				$filter = 1;
				unset($no_fp);
				unset($nama_file);
				$tanggal_invoice = explode(',', $row->tanggal_invoice);
				$no_fp = explode('??', $row->no_faktur_pajak);
				foreach ($no_fp as $key => $value) {
					$break_nama = explode('.', $value);
					$nama_file[$key] = str_replace($filter_nama, '_', $nama_toko.'-'.str_replace($filter_nama_cust, '', trim($row->nama))).'-'.date('dmY',strtotime($tanggal_invoice[$key])).'-'.end($break_nama).'.pdf';
					$nama_file_baru[$key] = str_replace("'", '', $row->nama).'-'.date('dmY',strtotime($tanggal_invoice[$key])).'-'.end($break_nama).'.pdf';
				}

				if ($row->email_stat != 0) {
					if (!is_dir($dir."/$nama_surat/email")) {
					    mkdir($dir."/$nama_surat/email", 0777, TRUE);
					}
					foreach ($nama_file as $key => $value) {
						if (!copy($dir.'/'.$value, $dir."/$nama_surat/email/".$value)) {
						    echo "failed to copy $value...\n";
						}
					}
				}

				if ($row->status_1 != 0) {
					if (!is_dir($dir."/$nama_surat/kirim")) {
					    mkdir($dir."/$nama_surat/kirim", 0777, TRUE);
					}
					foreach ($nama_file as $key => $value) {
						if (!copy($dir.'/'.$value, $dir."/$nama_surat/kirim/".$value)) {
						    echo "failed to copy $value...\n";
						}
					}
				}

				if ($row->status_2 != 0) {
					if (!is_dir($dir."/$nama_surat/ambil")) {
					    mkdir($dir."/$nama_surat/ambil", 0777, TRUE);
					}
					foreach ($nama_file as $key => $value) {
						if (!copy($dir.'/'.$value, $dir."/$nama_surat/ambil/".$value)) {
						    echo "failed to copy $value...\n";
						}
					}
				}

				if ($row->status_3 != 0) {
					if (!is_dir($dir."/$nama_surat/whatsapp")) {
					    mkdir($dir."/$nama_surat/whatsapp", 0777, TRUE);
					}
					foreach ($nama_file as $key => $value) {
						if (!copy($dir.'/'.$value, $dir."/$nama_surat/whatsapp/".$value)) {
						    echo "failed to copy $value...\n";
						}
					}
				}

				if ($row->status_4 != 0) {
					if (!is_dir($dir."/$nama_surat/others")) {
					    mkdir($dir."/$nama_surat/others", 0777, TRUE);
					}
					foreach ($nama_file as $key => $value) {
						if (!copy($dir.'/'.$value, $dir."/$nama_surat/others/".$value)) {
						    echo "failed to copy $value...\n";
						}
					}
				}
			}

			$this->zip->clear_data();
			$this->zip->read_dir($dir."/$nama_surat/", FALSE);

			// Write the zip file to a folder on your server. Name it "my_backup.zip"
			$this->zip->archive($dir.'/'.$nama_surat.'.zip'); 
			// $this->test_hapus($id);

			$this->zip->download($nama_surat.'.zip');
	
		}else{
			echo ":ERROR";
		}
	}

	function test_zip(){
		$id=$this->input->get('id');
		if ($id != '') {
			# code...
			//================== load library ==============================
			$this->load->library('zip');
			$this->load->helper('directory');
			$dir = 'fp_list/fp_'.$id;

			//================== get data nama tahun ==============================
			$get_data_fp = $this->common_model->db_select("nd_rekam_faktur_pajak WHERE id=".$id);
			foreach ($get_data_fp as $row) {
				$tahun = date("Y", strtotime($row->created_at));
				$no_surat = $row->no_surat;
			}

			//================== data toko ==============================
			foreach ($this->toko_list_aktif as $row) {
				$nama_toko = $row->nama;
				$pre_po = $row->pre_po;
			}

			$nama_surat = $pre_po.'-'.$tahun.'_01_'.str_pad($no_surat, 4,'0', STR_PAD_LEFT);

			//================== create directory ==============================
			if (!is_dir($dir."/$nama_surat")) {
			    mkdir($dir."/$nama_surat", 0777, TRUE);
			}


			//================== copas file fp ==============================
			$fp_list = $this->pjk_model->get_rekam_faktur_pajak_email($id, "WHERE t4.id is not null",'');
			$filter_nama = [',','.',' '];
			$filter_nama_cust = ["'","/","&"];

			foreach ($fp_list as $row) {
				$filter = 1;
				unset($no_fp);
				unset($nama_file);
				$tanggal_invoice = explode(',', $row->tanggal_invoice);
				$no_fp = explode('??', $row->no_faktur_pajak);
				foreach ($no_fp as $key => $value) {
					$break_nama = explode('.', $value);
					$nama_file[$key] = str_replace($filter_nama, '_', $nama_toko.'-'.str_replace($filter_nama_cust, '', trim($row->nama))).'-'.date('dmY',strtotime($tanggal_invoice[$key])).'-'.end($break_nama).'.pdf';
					$nama_file_baru[$key] = str_replace("'", '', $row->nama).'-'.date('dmY',strtotime($tanggal_invoice[$key])).'-'.end($break_nama).'.pdf';
				}

				// echo $row->customer_id.' : '.'<br/>';
				// echo 'email_stat : '.$row->email_stat.'<br/>';
				// echo 'status_1 : '.$row->status_1.'<br/>';
				// echo 'status_2 : '.$row->status_2.'<br/>';
				// echo 'status_3 : '.$row->status_3.'<br/>';
				// echo 'status_4 : '.$row->status_4.'<br/>';


				if ($row->email_stat != 0) {
					if (!is_dir($dir."/$nama_surat/email")) {
					    mkdir($dir."/$nama_surat/email", 0777, TRUE);
					}
					foreach ($nama_file as $key => $value) {
						if (!copy($dir.'/'.$value, $dir."/$nama_surat/email/".$value)) {
						    echo "failed to copy $value...\n";
						}
					}
				}

				if ($row->status_1 != 0) {
					if (!is_dir($dir."/$nama_surat/kirim")) {
					    mkdir($dir."/$nama_surat/kirim", 0777, TRUE);
					}
					foreach ($nama_file as $key => $value) {
						if (!copy($dir.'/'.$value, $dir."/$nama_surat/kirim/".$value)) {
						    echo "failed to copy $value...\n";
						}
					}
				}

				if ($row->status_2 != 0) {
					if (!is_dir($dir."/$nama_surat/ambil")) {
					    mkdir($dir."/$nama_surat/ambil", 0777, TRUE);
					}
					foreach ($nama_file as $key => $value) {
						if (!copy($dir.'/'.$value, $dir."/$nama_surat/ambil/".$value)) {
						    echo "failed to copy $value...\n";
						}
					}
				}

				if ($row->status_3 != 0) {
					if (!is_dir($dir."/$nama_surat/whatsapp")) {
					    mkdir($dir."/$nama_surat/whatsapp", 0777, TRUE);
					}
					foreach ($nama_file as $key => $value) {
						if (!copy($dir.'/'.$value, $dir."/$nama_surat/whatsapp/".$value)) {
						    echo "failed to copy $value...\n";
						}
					}
				}

				if ($row->status_4 != 0) {
					if (!is_dir($dir."/$nama_surat/others")) {
					    mkdir($dir."/$nama_surat/others", 0777, TRUE);
					}
					foreach ($nama_file as $key => $value) {
						if (!copy($dir.'/'.$value, $dir."/$nama_surat/others/".$value)) {
						    echo "failed to copy $value...\n";
						}
					}
				}
			}

			//================== bikin zip and download ==============================
			// $name = 'mydata1.txt';
			// $data = 'A Data String!';
			$this->zip->clear_data();
			$this->zip->read_dir($dir."/$nama_surat/", FALSE);

			// Write the zip file to a folder on your server. Name it "my_backup.zip"
			$this->zip->archive($dir.'/'.$nama_surat.'.zip'); 
			// $this->test_hapus($id);

			// Download the file to your desktop. Name it "my_backup.zip"
			$this->zip->download($nama_surat.'.zip');
			// unlink($dir.'/'.$nama_surat.'.zip');

		}else{
			echo ":ERROR";
		}
	}


	function test_hapus($id){
		// echo $id;

		if ($id != '') {
			# code...
			//================== load library ==============================
			$this->load->helper('directory');
			$dir = 'fp_list/fp_'.$id;

			$get_data_fp = $this->common_model->db_select("nd_rekam_faktur_pajak WHERE id=".$id);
			foreach ($get_data_fp as $row) {
				$tahun = date("Y", strtotime($row->created_at));
				$no_surat = $row->no_surat;
			}

			//================== data toko ==============================
			foreach ($this->toko_list_aktif as $row) {
				$nama_toko = $row->nama;
				$pre_po = $row->pre_po;
			}

			$nama_surat = $pre_po.'-'.$tahun.'_01_'.str_pad($no_surat, 3,'0', STR_PAD_LEFT);

			if (is_dir($dir."/$nama_surat/email")) {
				array_map('unlink', glob("$dir/$nama_surat/email/*.*"));
				rmdir($dir.'/'.$nama_surat.'/email');
			}
			if (is_dir($dir."/$nama_surat/kirim")) {
				array_map('unlink', glob("$dir/$nama_surat/kirim/*.*"));
				rmdir($dir.'/'.$nama_surat.'/kirim');
			}
			if (is_dir($dir."/$nama_surat/ambil")) {
				array_map('unlink', glob("$dir/$nama_surat/ambil/*.*"));
				rmdir($dir.'/'.$nama_surat.'/ambil');
			}
			if (is_dir($dir."/$nama_surat/whatsapp")) {
				array_map('unlink', glob("$dir/$nama_surat/whatsapp/*.*"));
				rmdir($dir.'/'.$nama_surat.'/whatsapp');
			}
			if (is_dir($dir."/$nama_surat/others")) {
				array_map('unlink', glob("$dir/$nama_surat/others/*.*"));
			}

			rmdir($dir.'/'.$nama_surat);


		}
	}

	function hapus_file_pdf_pajak_manual(){
		
		$id = $this->input->get('id');
		
		if ($id != '') {
			# code...
			//================== load library ==============================
			$this->load->helper('directory');
			$dir = 'fp_list/fp_'.$id;

			$get_data_fp = $this->common_model->db_select("nd_rekam_faktur_pajak WHERE id=".$id);
			foreach ($get_data_fp as $row) {
				$tahun = date("Y", strtotime($row->created_at));
				$no_surat = $row->no_surat;
			}

			//================== data toko ==============================
			foreach ($this->toko_list_aktif as $row) {
				$nama_toko = $row->nama;
				$pre_po = $row->pre_po;
			}

			array_map('unlink', glob("$dir/*.zip*"));


			$nama_surat = $pre_po.'-'.$tahun.'_01_'.str_pad($no_surat, 4,'0', STR_PAD_LEFT);

			if (is_dir($dir."/$nama_surat/email")) {
				array_map('unlink', glob("$dir/$nama_surat/email/*.*"));
				rmdir($dir.'/'.$nama_surat.'/email');
			}
			if (is_dir($dir."/$nama_surat/kirim")) {
				array_map('unlink', glob("$dir/$nama_surat/kirim/*.*"));
				rmdir($dir.'/'.$nama_surat.'/kirim');
			}
			if (is_dir($dir."/$nama_surat/ambil")) {
				array_map('unlink', glob("$dir/$nama_surat/ambil/*.*"));
				rmdir($dir.'/'.$nama_surat.'/ambil');
			}
			if (is_dir($dir."/$nama_surat/whatsapp")) {
				array_map('unlink', glob("$dir/$nama_surat/whatsapp/*.*"));
				rmdir($dir.'/'.$nama_surat.'/whatsapp');
			}
			if (is_dir($dir."/$nama_surat/others")) {
				array_map('unlink', glob("$dir/$nama_surat/others/*.*"));
			}

			if (is_dir($dir."/$nama_surat")) {
				rmdir($dir.'/'.$nama_surat);
				
			}



		}
	}

//==============================update rekam faktur pajak coretax=============================

	function rekam_pajak_update_no_faktur_coretax(){
		// $data = $this->input->post();
		
		$inputJSON = file_get_contents('php://input');
		$data = json_decode($inputJSON, TRUE);

		$affectedRows = $this->common_model->db_update_batch('nd_rekam_faktur_pajak_detail', $data, 'id');
		// $this->db->affectedRows();
		
		echo json_encode([
			'status' => 200,
			'message' => 'Success',
			'data' => $data,
			'affected_rows' => $affectedRows
		]);
	}

	function upload_dropzone_faktur_pajak_coretax(){
		$id = $this->input->post('id');
		if(!empty($_FILES)){
			// $tempFile = $_FILES['file']['tmp_name'];
			// $targetPath = './image/sparepart';
			// $targetFile = $targetPath.$fileName;
			// move_uploaded_file($tempFile, $targetFile);

			$fileName = $_FILES['file']['name'];
			$break = explode('-', $fileName);

			foreach ($this->toko_list_aktif as $row) {
				$nama_toko = $row->nama;
			}

			$nfp_index = count($break) - 2;
			$nama_file_fp = trim($break[$nfp_index]);
			$nama_cust = '';
			$result = $this->pjk_model->get_data_dari_no_faktur($id, $nama_file_fp);
			$tanggal_invoice = '';
			foreach ($result as $row) {
				$nama_cust = trim($row->nama);
				$tanggal_invoice = date('dmY', strtotime($row->tanggal_invoice));
			}

			if (!is_dir('fp_list/fp_'.$id)) {
			    mkdir('./fp_list/fp_'.$id, 0777, TRUE);
			}

			$filter = [',','.',' '];
			$config['upload_path'] = './fp_list/fp_'.$id;	
			$config['allowed_types'] = 'pdf';
			$config['file_name'] = trim(str_replace($filter, '_', $nama_toko)).($nama_cust != '' ? '-'.str_replace($filter, '_', $nama_cust) : '').($tanggal_invoice !='' ? '-'.$tanggal_invoice :'').'-'.$nama_file_fp.'.pdf';
			$this->load->library('upload',$config);
			// echo $config['file_name'];
			//$this->upload->initialize($config);
			if(!$this->upload->do_upload("file")){
				$error = array('eror' => $this->upload->display_errors());
				print_r($error);
			}else{
				$data = array('upload_data' => $this->upload->data());
				print_r($data);
			}
		}
	}

//==============================rekam faktur pajak xml=============================

	function rekam_faktur_pajak_xml_test(){
		$xml = new SimpleXMLElement('<?xml version="1.0" encoding="utf-8" ?><TaxInvoiceBulk xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="TaxInvoice.xsd"></TaxInvoiceBulk>');

		$xml->addChild('TIN', 'xxxxxxxxxxxxxxxx');

		$listOfTaxInvoice = $xml->addChild('ListOfTaxInvoice');
		$taxInvoice = $listOfTaxInvoice->addChild('TaxInvoice');
		$taxInvoice->addChild('TaxInvoiceDate', '2023-09-13');
		$taxInvoice->addChild('TaxInvoiceOpt', 'Normal');
		$taxInvoice->addChild('TrxCode', '01');
		$taxInvoice->addChild('AddInfo');
		$taxInvoice->addChild('CustomDoc');
		$taxInvoice->addChild('RefDesc');
		$taxInvoice->addChild('FacilityStamp');
		$taxInvoice->addChild('SellerIDTKU', '0000000000000000000000');
		$taxInvoice->addChild('BuyerTin', 'xxxxxxxxxxxxxxxx');
		$taxInvoice->addChild('BuyerDocument', 'TIN');
		$taxInvoice->addChild('BuyerCountry', 'IND');
		$taxInvoice->addChild('BuyerDocumentNumber');
		$taxInvoice->addChild('BuyerName');
		$taxInvoice->addChild('BuyerAdress');
		$taxInvoice->addChild('BuyerEmail', 'someemail@gmail.com');
		$taxInvoice->addChild('BuyerIDTKU', '0000000000000000000000');

		$listOfGoodService = $taxInvoice->addChild('ListOfGoodService');

		$goodService1 = $listOfGoodService->addChild('GoodService');
		$goodService1->addChild('Opt', 'A');
		$goodService1->addChild('Code', '000000');
		$goodService1->addChild('Name', 'Barang');
		$goodService1->addChild('Unit', 'UM.0001');
		$goodService1->addChild('Price', '15000');
		$goodService1->addChild('Qty', '200');
		$goodService1->addChild('TotalDiscount', '100000');
		$goodService1->addChild('TaxBase', '2900000');
		$goodService1->addChild('OtherTaxBase', '2900000');
		$goodService1->addChild('VATRate', '11');
		$goodService1->addChild('VAT', '319000');
		$goodService1->addChild('STLGRate', '20');
		$goodService1->addChild('STLG', '580000');

		$goodService2 = $listOfGoodService->addChild('GoodService');
		$goodService2->addChild('Opt', 'B');
		$goodService2->addChild('Code', '000000');
		$goodService2->addChild('Name', 'BarangB');
		$goodService2->addChild('Unit', 'UM.0002');
		$goodService2->addChild('Price', '15000');
		$goodService2->addChild('Qty', '200');
		$goodService2->addChild('TotalDiscount', '100000');
		$goodService2->addChild('TaxBase', '2900000');
		$goodService2->addChild('OtherTaxBase', '2900000');
		$goodService2->addChild('VATRate', '11');
		$goodService2->addChild('VAT', '319000');
		$goodService2->addChild('STLGRate', '20');
		$goodService2->addChild('STLG', '580000');

		// echo $xml->asXML();

		header('Content-Disposition: attachment; filename="tax_invoice.xml"');
		header('Content-Type: text/xml');
		echo $xml->asXML();
		exit();
	}

	function rekam_faktur_pajak_export_xml(){
		$id = $this->input->get('id');

		foreach ($this->toko_list_aktif as $row) {
			$nama_toko = $row->nama;
			$pre_po = $row->pre_po;
		}
		
		$fp_list = $this->common_model->db_select("nd_rekam_faktur_pajak_detail where rekam_faktur_pajak_id=".$id." and status = 1 ");
		$idx=0;
		foreach ($fp_list as $row) {
			$penjualan_id[$idx] = $row->penjualan_id;
			$idx++;
		}

		$penjualan_id_list = implode(',', $penjualan_id);
		$get_detail_list = $this->pjk_model->get_item_penjualan_list($penjualan_id_list);
		$faktur_data_list = $this->pjk_model->get_data_penjualan_list($penjualan_id_list);

		// echo count($get_detail_list).'<hr/>';
		$idx=0;
		foreach ($get_detail_list as $row) {
			$penjualan_data_detail['detail'][$row->penjualan_id][$row->barang_id][$row->harga_jual] = $row;
			if (!isset($sum_jual[$row->penjualan_id])) {
				$sum_jual[$row->penjualan_id] = 0;
			}
			$sum_jual[$row->penjualan_id] += number_format($row->harga_jual/(1+($row->ppn_berlaku/100)),'4','.','') * $row->qty; 
			$idx++;
		}

		foreach ($faktur_data_list as $row) {
			$penjualan_data_detail['data'][$row->penjualan_id] = $row;
			$no_faktur[$row->penjualan_id] = $row->no_faktur;
		}

		$xml = new SimpleXMLElement('<?xml version="1.0" encoding="utf-8" ?><TaxInvoiceBulk xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="TaxInvoice.xsd"></TaxInvoiceBulk>');

		$xml->addChild('TIN', 'xxxxxxxxxxxxxxxx');

		$listOfTaxInvoice = $xml->addChild('ListOfTaxInvoice');
		
		
		foreach ($fp_list as $key => $value) {
			
			$taxInvoice = $listOfTaxInvoice->addChild('TaxInvoice');
			$nama = trim($value->nama_customer);
			$alamat = $value->alamat_lengkap;
			$npwp = $value->no_npwp;
			$nik = $value->no_nik;
			$sellerID = ($npwp != '' ? $npwp : $nik);

			
			$taxInvoice->addChild('TaxInvoiceDate', '2023-09-13');
			$taxInvoice->addChild('TaxInvoiceOpt', 'Normal');
			$taxInvoice->addChild('TrxCode', '01');
			$taxInvoice->addChild('AddInfo');
			$taxInvoice->addChild('CustomDoc');
			$taxInvoice->addChild('RefDesc');
			$taxInvoice->addChild('FacilityStamp');
			$taxInvoice->addChild('SellerIDTKU', "0000000000000000000000");
			$taxInvoice->addChild('BuyerTin', $sellerID);
			$taxInvoice->addChild('BuyerDocument', 'TIN');
			$taxInvoice->addChild('BuyerCountry', 'IND');
			$taxInvoice->addChild('BuyerDocumentNumber');
			$taxInvoice->addChild('BuyerName', $nama);
			$taxInvoice->addChild('BuyerAdress', $alamat);
			$taxInvoice->addChild('BuyerEmail', '');
			$taxInvoice->addChild('BuyerIDTKU', '0000000000000000000000');

			$listOfGoodService = $taxInvoice->addChild('ListOfGoodService');
	
			$idx_l = "A";
			foreach ($penjualan_data_detail['detail'][$value->penjualan_id] as $key => $value) {
				foreach ($value as $key2 => $value2) {
	
					$ppn_fix = $penjualan_data_detail['data'][$row->penjualan_id]->ppn_berlaku;
					$ppn_pengali = $ppn_fix /100;
					$ppn_pembagi = $ppn_pengali + 1;
	
					$harga_dpp = number_format($value2->harga_jual/$ppn_pembagi,'4','.','');
					$kode_barang = $value2->kode_barang;
	
					// $satuan = $value2->satuan;
					$qty = $value2->qty;
	
					$nilai_barang = $value2->harga_jual * $value2->qty;
					$nilai_barang_raw = $nilai_barang/$ppn_pembagi;
					$nilai_barang_ppn = $nilai_barang - $nilai_barang_raw;
					
					$goodService1 = $listOfGoodService->addChild('GoodService');
					$goodService1->addChild('Opt', $idx_l);
					$goodService1->addChild('Code', $kode_barang);
					$goodService1->addChild('Name', $kode_barang);
					$goodService1->addChild('Unit', '');
					$goodService1->addChild('Price', $harga_dpp);
					$goodService1->addChild('Qty', $qty);
					$goodService1->addChild('TotalDiscount', '0');
					$goodService1->addChild('TaxBase', $nilai_barang_raw);
					$goodService1->addChild('OtherTaxBase', '0');
					$goodService1->addChild('VATRate', '11');
					$goodService1->addChild('VAT', $nilai_barang_ppn);
					$goodService1->addChild('STLGRate', '0');
					$goodService1->addChild('STLG', '0');
					$idx_l++;
				}
	
			}
		}


		// echo $xml->asXML();

		header('Content-Disposition: attachment; filename="'.'Faktur_Pajak_Coretax_'.$nama_toko.date("d-m-Y").'.xml"');
		header('Content-Type: text/xml');
		echo $xml->asXML();
		exit();
	}

	function rekam_faktur_pajak_export_xml_pretty(){
		$id = $this->input->get('id');

		foreach ($this->toko_list_aktif as $row) {
			$nama_toko = $row->nama;
			$pre_po = $row->pre_po;
			$npwp_toko = $row->NPWP;
			$rplc_npwp = [".","-"," "];
			$nitku_toko = str_replace($rplc_npwp, "", $npwp_toko);
			$idtku_toko = $nitku_toko."000000";
		}
		
		$fp_list = $this->common_model->db_select("nd_rekam_faktur_pajak_detail where rekam_faktur_pajak_id=".$id." and status = 1 ");
		$idx=0;
		foreach ($fp_list as $row) {
			$penjualan_id[$idx] = $row->penjualan_id;
			$idx++;
		}

		$penjualan_id_list = implode(',', $penjualan_id);
		$get_detail_list = $this->pjk_model->get_item_penjualan_list($penjualan_id_list);
		$faktur_data_list = $this->pjk_model->get_data_penjualan_list($penjualan_id_list);

		// echo count($get_detail_list).'<hr/>';
		$idx=0;
		foreach ($get_detail_list as $row) {
			$penjualan_data_detail['detail'][$row->penjualan_id][$row->barang_id][$row->harga_jual] = $row;
			if (!isset($sum_jual[$row->penjualan_id])) {
				$sum_jual[$row->penjualan_id] = 0;
			}
			$sum_jual[$row->penjualan_id] += number_format($row->harga_jual/(1+($row->ppn_berlaku/100)),'4','.','') * $row->qty; 
			$idx++;
		}

		foreach ($faktur_data_list as $row) {
			$penjualan_data_detail['data'][$row->penjualan_id] = $row;
			$no_faktur[$row->penjualan_id] = $row->no_faktur;
		}

		$dom = new DOMDocument('1.0', 'utf-8');
		$dom->preserveWhiteSpace = false;
		$dom->formatOutput = true;
		

		$taxInvoiceBulk = $dom->createElement('TaxInvoiceBulk');
		$taxInvoiceBulk->setAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
		$taxInvoiceBulk->setAttribute('xsi:noNamespaceSchemaLocation', 'TaxInvoice.xsd');
		$dom->appendChild($taxInvoiceBulk);

		$tin = $dom->createElement('TIN', $nitku_toko);
		$taxInvoiceBulk->appendChild($tin);

		$listOfTaxInvoice = $dom->createElement('ListOfTaxInvoice');
		$taxInvoiceBulk->appendChild($listOfTaxInvoice);

		foreach ($fp_list as $key => $value) {
			$taxInvoice = $dom->createElement('TaxInvoice');
			$listOfTaxInvoice->appendChild($taxInvoice);

			$nama = trim($value->nama_customer);
			$alamat = $value->alamat_lengkap;
			$npwp = $value->no_npwp;
			$nik = $value->no_nik;
			$tin_customer = str_replace($rplc_npwp, "", $npwp);
			$tin_customer = (strlen($tin_customer) == 15 ? "0".$tin_customer : $tin_customer);
			$idtku_customer = $tin_customer."000000";
			$sellerID = ($tin_customer != '' ? $tin_customer : $nik);
			$tanggal = $value->tanggal;

			$taxInvoice->appendChild($dom->createElement('TaxInvoiceDate', $tanggal));
			$taxInvoice->appendChild($dom->createElement('TaxInvoiceOpt', 'Normal'));
			$taxInvoice->appendChild($dom->createElement('TrxCode', '04'));
			$taxInvoice->appendChild($dom->createElement('AddInfo'));
			$taxInvoice->appendChild($dom->createElement('CustomDoc'));
			$taxInvoice->appendChild($dom->createElement('RefDesc'));
			$taxInvoice->appendChild($dom->createElement('FacilityStamp'));
			$taxInvoice->appendChild($dom->createElement('SellerIDTKU', $idtku_toko));
			$taxInvoice->appendChild($dom->createElement('BuyerTin', $tin_customer));
			$taxInvoice->appendChild($dom->createElement('BuyerDocument', 'TIN'));
			$taxInvoice->appendChild($dom->createElement('BuyerCountry', 'IND'));
			$taxInvoice->appendChild($dom->createElement('BuyerDocumentNumber'));
			$taxInvoice->appendChild($dom->createElement('BuyerName', $nama));
			$taxInvoice->appendChild($dom->createElement('BuyerAdress', $alamat));
			$taxInvoice->appendChild($dom->createElement('BuyerEmail', ''));
			$taxInvoice->appendChild($dom->createElement('BuyerIDTKU', $idtku_customer));

			$listOfGoodService = $dom->createElement('ListOfGoodService');
			$taxInvoice->appendChild($listOfGoodService);

			$opt = "A";
			foreach ($penjualan_data_detail['detail'][$value->penjualan_id] as $key => $value) {
				foreach ($value as $key2 => $value2) {
					$ppn_fix = $penjualan_data_detail['data'][$row->penjualan_id]->ppn_berlaku;
					$ppn_pengali = $ppn_fix / 100;
					$ppn_pembagi = $ppn_pengali + 1;

					// $harga_dpp = number_format($value2->harga_jual / $ppn_pembagi, '4', '.', '');
					$harga_dpp = round($value2->harga_jual / $ppn_pembagi,2);
					$kode_barang = $value2->kode_barang;
					$qty = $value2->qty;
					$total_invoice = $value2->harga_jual * $qty;

					$tax_base = $harga_dpp * $value2->qty;
					$dpp_lain = round($tax_base * 11 / 12, 2); 
					$vat = $total_invoice - $tax_base;

					$unit = $penjualan_data_detail['data'][$row->penjualan_id]->satuan;
					$unit_kode = "";
					if( strtoupper($unit) == "YARD"){
						$unit_kode = "UM.0016";
					}else if(strtoupper($unit) == "KG"){
						$unit_kode = "UM.0003";
					}

					$goodService = $dom->createElement('GoodService');
					$listOfGoodService->appendChild($goodService);
					
					

					$goodService->appendChild($dom->createElement('Opt', $opt));
					$goodService->appendChild($dom->createElement('Code', "00000"));
					$goodService->appendChild($dom->createElement('Name', $kode_barang));
					$goodService->appendChild($dom->createElement('Unit', $unit_kode));
					$goodService->appendChild($dom->createElement('Price', $harga_dpp));
					$goodService->appendChild($dom->createElement('Qty', $qty));
					$goodService->appendChild($dom->createElement('TotalDiscount', '0'));
					$goodService->appendChild($dom->createElement('TaxBase', $tax_base));
					$goodService->appendChild($dom->createElement('OtherTaxBase', $dpp_lain));
					$goodService->appendChild($dom->createElement('VATRate', '12'));
					$goodService->appendChild($dom->createElement('VAT', $vat));
					$goodService->appendChild($dom->createElement('STLGRate', '0'));
					$goodService->appendChild($dom->createElement('STLG', '0'));
				}
			}
		}

		header('Content-Disposition: attachment; filename="Faktur_Pajak_Coretax_' . $nama_toko . date("d-m-Y") . '.xml"');
		header('Content-Type: text/xml');
		echo $dom->saveXML();
		exit();
	}


}