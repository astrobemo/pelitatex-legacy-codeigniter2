<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Emergency_User extends CI_Controller {

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
		
		// $this->data['user_menu_list'] = is_user_menu(is_posisi_id());
		
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


	function updatePenjualanBackDate(){
		$get_penjualan = $this->common_model->db_select("gracetdj_system2021.nd_penjualan where id = 14");
		foreach ($get_penjualan as $row) {
			unset($data_penjualan);			
			$data_penjualan = array(
				'toko_id' => 1 ,
				'penjualan_type_id' => $row->penjualan_type_id,
				'no_faktur' => $row->no_faktur,
				'po_number' => $row->po_number,
				'tanggal' => $row->tanggal,
				'customer_id' => $row->customer_id,
				'gudang_id' => $row->gudang_id,
				'nama_keterangan' => $row->nama_keterangan,
				'alamat_keterangan' => $row->alamat_keterangan,
				'status' => $row->status,
				'closed_by' => $row->closed_by,
				'closed_date' => $row->closed_date,
				'created' => $row->created,
				'revisi' => $row->revisi,
				'fp_status' => $row->fp_status,
				'user_id' => $row->user_id );

			$result_id = $this->common_model->db_insert('nd_penjualan', $data_penjualan);
			$get_penjualan_detail = $this->common_model->db_select("gracetdj_system2021.nd_penjualan_detail WHERE penjualan_id=".$row->id);

			foreach ($get_penjualan_detail as $row2) {
				unset($data_penjualan_detail);
				unset($data_qty);
				$data_penjualan_detail = array(
					'penjualan_id' => $result_id,
					'barang_id' => $row2->barang_id,
					'warna_id' => $row2->warna_id,
					'harga_jual' => $row2->harga_jual,
					'gudang_id' => $row2->gudang_id );

				$result_penjualan_detail_id = $this->common_model->db_insert("nd_penjualan_detail", $data_penjualan_detail);
				$get_penjualan_qty = $this->common_model->db_select("gracetdj_system2021.nd_penjualan_qty_detail WHERE penjualan_detail_id=".$row2->id);
				$data_qty = array();
				foreach ($get_penjualan_qty as $row3) {
					array_push($data_qty, array(
						'penjualan_detail_id' => $result_penjualan_detail_id,
						'qty' => $row3->qty,
						'jumlah_roll' => $row3->jumlah_roll 
						)
					);
				}
				$this->common_model->db_insert_batch("nd_penjualan_qty_detail",$data_qty);
			}
		}

	}
}