<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Report extends CI_Controller {

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
		$this->load->model('report_model','rpt_model',true);
		$this->load->model('admin_model','admin_model',true);
		
		//======================data aktif section===========================
		
		$this->supplier_list_aktif = $this->common_model->db_select('nd_supplier where status_aktif = 1');
		$this->customer_list_aktif = $this->common_model->db_select('nd_customer where status_aktif = 1');
		$this->toko_list_aktif = $this->common_model->db_select('nd_toko where status_aktif = 1');
		$this->gudang_list_aktif = $this->common_model->db_select('nd_gudang where status_aktif = 1');

		$this->warna_list_aktif = $this->common_model->db_select('nd_warna where status_aktif = 1');
		$this->barang_list_aktif = $this->common_model->get_barang_list_aktif();
		$this->barang_list_aktif_beli = $this->common_model->get_barang_list_aktif_beli();
		
		$this->satuan_list_aktif = $this->common_model->db_select('nd_satuan where status_aktif = 1');
	   	$this->pre_faktur = get_pre_faktur();

	}


//=====================================penjualan report==================================================

	function penjualan_list_report(){
		$menu = is_get_url($this->uri->segment(1)) ;

		$tanggal_start = date('Y-m-d');
		$tanggal_end = date('Y-m-d');
		$status_excel = '0';
		$tipe_search = 1;
		$customer_id = 0;
		$view_type=0;


		if ($this->input->get('tanggal_start') && $this->input->get('tanggal_end') != '') {
			$tanggal_start = is_date_formatter($this->input->get('tanggal_start'));
			$tanggal_end = is_date_formatter($this->input->get('tanggal_end'));
			$tipe_search = $this->input->get('tipe_search');
			$customer_id = $this->input->get('customer_id');
			if ($tipe_search > 6 || $tipe_search < 1) {
				$tipe_search = 1;
			}
			$status_excel = '1';
			$view_type=$this->input->get('view_type');
		}

		if (is_posisi_id() == 1) {
			// $view_type = 2;
		}

		$cond = '';
		$customer_cond = "";
		$gudang_cond = "";
		$customer_cond_retur = "";
		$gudang_id = 0;
		$penjualan_type_id = 0;

		$barang_id = '';
		$barang_id_cond = '';
		$warna_id = '';
		$warna_id_cond = '';

		if ($customer_id != null && $customer_id != 0) {
			$customer_cond = 'AND customer_id = '.$customer_id;
			$customer_cond_retur = 'WHERE customer_id = '.$customer_id ;
		}

		$tipe_penjualan_cond = "";
		if ($this->input->get('penjualan_type_id') && $this->input->get('penjualan_type_id') != 0) {
			$penjualan_type_id = $this->input->get('penjualan_type_id');
			$tipe_penjualan_cond = "AND penjualan_type_id = ".$this->input->get('penjualan_type_id');
		}


		if ($tipe_search == 2) {
			$cond = " AND pembayaran_type_id != 5 AND keterangan >= 0";
		}elseif ($tipe_search == 3) {
			$cond = " AND pembayaran_type_id = 5 AND  keterangan >= 0";
		}elseif ($tipe_search == 4) {
			$cond = " AND  ket_lunas != 'lunas' AND ket_lunas != 'lunas1' AND ket_lunas != 'lunas,lunas' ";
			// $cond = " AND (ifnull(total_bayar,0) - (ifnull(g_total,0) - ifnull(diskon,0)) + ifnull(ongkos_kirim,0)) < 0";
		}elseif ($tipe_search == 5) {
			$customer_cond .= " AND  fp_status = 1";
			$customer_cond_retur .= ($customer_cond_retur == '' ? "WHERE " : " AND ")."fp_status = 1";
		}elseif ($tipe_search == 6) {
			$customer_cond .= " AND  fp_status != 1";
			$customer_cond_retur .= ($customer_cond_retur == '' ? "WHERE " : " AND ")."fp_status != 1";
		}

		if($this->input->get('gudang_id') && $this->input->get('gudang_id') != 0){
			$gudang_id = $this->input->get('gudang_id');
			$gudang_cond = " AND gudang_id = ".$gudang_id;
		}

		if ($this->input->get('barang_id') && $this->input->get('barang_id') != '') {
			$barang_id = $this->input->get('barang_id');
			$barang_id_cond = " AND barang_id = ".$barang_id;
		}

		if ($this->input->get('warna_id') && $this->input->get('warna_id') != '') {
			$warna_id = $this->input->get('warna_id');
			$warna_id_cond = " AND warna_id = ".$warna_id;
		}


		$data = array(
			'content' =>'admin/report/penjualan_list_report',
			'breadcrumb_title' => 'Laporan',
			'breadcrumb_small' => 'Laporan Penjualan',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data' => $this->data,
			'tanggal_start' => is_reverse_date($tanggal_start),
			'tanggal_end' => is_reverse_date($tanggal_end),
			'status_excel' => $status_excel,
			'tipe_search' => $tipe_search,
			'customer_id' => $customer_id,
			'gudang_id' => $gudang_id,
			'barang_id' => $barang_id,
			'warna_id' => $warna_id,
			'penjualan_type_id' => $penjualan_type_id,
			'tipe_bayar' => $this->common_model->db_select('nd_pembayaran_type'),
			// 'penjualan_list' => $this->rpt_model->get_penjualan_report($tanggal_start, $tanggal_end, $cond,$customer_cond, $gudang_cond, $tipe_penjualan_cond),
			// 'penjualan_list' => $this->rpt_model->get_penjualan_report($tanggal_start, $tanggal_end, $cond,$customer_cond, $gudang_cond, $barang_id_cond, $warna_id_cond, $tipe_penjualan_cond),
			'retur_list' => $this->rpt_model->get_retur_report($tanggal_start, $tanggal_end, $customer_cond_retur, $gudang_cond),
			'view_type' => $view_type
			);

		$get_list = $this->common_model->db_select("nd_penjualan 
			WHERE tanggal >= '$tanggal_start'
			AND tanggal <= '$tanggal_end'
			AND status_aktif = 1
			$customer_cond
			$tipe_penjualan_cond
			ORDER BY tanggal desc");

		$data['penjualan_list'] = array();
		$id_list = array();
		foreach ($get_list as $row) {
			array_push($id_list, $row->id);
		}
		
		if (count($id_list) > 0) {
			$data['penjualan_list'] = $this->rpt_model->get_penjualan_report($cond, $gudang_cond,$barang_id_cond, $warna_id_cond, implode(",", $id_list));
		}

		$penjualan_list_pelunasan = array();
		if ($tipe_search == 4) {
			$penjualan_list_pelunasan = array();
		}else{
			$penjualan_list_pelunasan = $this->rpt_model->get_penjualan_report_pelunasan($tanggal_start, $tanggal_end, $cond,$customer_cond, $gudang_cond, $tipe_penjualan_cond);
		}
		
		foreach ($penjualan_list_pelunasan as $row) {
			$data['pelunasan_piutang_nilai'][$row->penjualan_id][$row->id] = $row;
			if ($row->pembayaran_piutang_id != '') {
				// $data['color'][$row->pembayaran_piutang_id] =  str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
				$data['color'][$row->pembayaran_piutang_id] =  rand(190,250).','.rand(190,250).','.rand(170,250);
			}
		}
		
		// print_r($data['color']);
		if (is_posisi_id() == 1) {
			// print_r($id_list);
			// $this->output->enable_profiler(TRUE);
			$this->load->view('admin/template',$data);
			// echo  $customer_cond_retur;
			# code...
		}else{
			$this->load->view('admin/template',$data);
		}
	}

	function penjualan_list_export_excel(){

		$tanggal_start = is_date_formatter($this->input->get('tanggal_start'));
		$tanggal_end = is_date_formatter($this->input->get('tanggal_end'));
		$tipe_search = $this->input->get('tipe_search');
		$customer_id = $this->input->get('customer_id');
		if ($tipe_search > 6 || $tipe_search < 1) {
			$tipe_search = 1;
		}
		$status_excel = '1';
		if ($this->input->get('tanggal_start') && $this->input->get('tanggal_end') != '') {
		}

		if (is_posisi_id() == 1) {
			// $view_type = 2;
		}

		$cond = '';
		$customer_cond = "";
		$gudang_cond = "";
		$customer_cond_retur = "";
		$gudang_id = 0;
		$penjualan_type_id = 0;

		$barang_id = '';
		$barang_id_cond = '';
		$warna_id = '';
		$warna_id_cond = '';

		if ($customer_id != null && $customer_id != 0) {
			$customer_cond = 'AND customer_id = '.$customer_id;
			$customer_cond_retur = 'WHERE customer_id = '.$customer_id ;
		}

		$tipe_penjualan_cond = "";
		if ($this->input->get('penjualan_type_id') && $this->input->get('penjualan_type_id') != 0) {
			$penjualan_type_id = $this->input->get('penjualan_type_id');
			$tipe_penjualan_cond = "AND penjualan_type_id = ".$this->input->get('penjualan_type_id');
		}


		if ($tipe_search == 2) {
			$cond = " AND pembayaran_type_id != 5 AND keterangan >= 0";
		}elseif ($tipe_search == 3) {
			$cond = " AND pembayaran_type_id = 5 AND  keterangan >= 0";
		}elseif ($tipe_search == 4) {
			$cond = " AND  ket_lunas = 'belum lunas' ";
			// $cond = " AND (ifnull(total_bayar,0) - (ifnull(g_total,0) - ifnull(diskon,0)) + ifnull(ongkos_kirim,0)) < 0";
		}elseif ($tipe_search == 5) {
			$customer_cond .= " AND  fp_status = 1";
			$customer_cond_retur .= ($customer_cond_retur == '' ? "WHERE " : "AND ")."fp_status = 1";
		}elseif ($tipe_search == 6) {
			$customer_cond .= " AND  fp_status != 1";
			$customer_cond_retur .= ($customer_cond_retur == '' ? "WHERE " : "AND ")."fp_status != 1";
		}

		if($this->input->get('gudang_id') && $this->input->get('gudang_id') != 0){
			$gudang_id = $this->input->get('gudang_id');
			$gudang_cond = "AND gudang_id = ".$gudang_id;
		}

		if ($this->input->get('barang_id') && $this->input->get('barang_id') != '') {
			$barang_id = $this->input->get('barang_id');
			$barang_id_cond = " AND barang_id = ".$barang_id;
		}

		if ($this->input->get('warna_id') && $this->input->get('warna_id') != '') {
			$warna_id = $this->input->get('warna_id');
			$warna_id_cond = " AND warna_id = ".$warna_id;
		}

		$get_list = $this->common_model->db_select("nd_penjualan 
			WHERE tanggal >= '$tanggal_start'
			AND tanggal <= '$tanggal_end'
			AND status_aktif = 1
			$customer_cond
			$tipe_penjualan_cond
			ORDER BY tanggal desc");

		$penjualan_list = array();
		$id_list = array();
		foreach ($get_list as $row) {
			array_push($id_list, $row->id);
		}
		
		if (count($id_list > 0)) {
			$penjualan_list = $this->rpt_model->get_penjualan_report($cond, $gudang_cond,$barang_id_cond, $warna_id_cond, implode(",", $id_list));
		}

		$penjualan_list_pelunasan = array();
		if ($tipe_search == 4) {
			$penjualan_list_pelunasan = array();
		}else{
			$penjualan_list_pelunasan = $this->rpt_model->get_penjualan_report_pelunasan($tanggal_start, $tanggal_end, $cond,$customer_cond, $gudang_cond, $tipe_penjualan_cond);
		}
		
		foreach ($penjualan_list_pelunasan as $row) {
			$pelunasan_piutang_nilai[$row->penjualan_id][$row->id] = $row;
			if ($row->pembayaran_piutang_id != '') {
				// $data['color'][$row->pembayaran_piutang_id] =  str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
				$data['color'][$row->pembayaran_piutang_id] =  rand(190,250).','.rand(190,250).','.rand(170,250);
			}
		}


		// $penjualan_list = $this->rpt_model->get_penjualan_report($tanggal_start, $tanggal_end, $cond, $customer_cond, $gudang_cond,$barang_id_cond, $warna_id_cond, $tipe_penjualan_cond);
		$retur_list = $this->rpt_model->get_retur_report($tanggal_start, $tanggal_end, $customer_cond_retur, $gudang_cond);
		$tipe_bayar = $this->common_model->db_select("nd_pembayaran_type");

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

		$objPHPExcel->getActiveSheet()->mergeCells("A1:M1");
		$objPHPExcel->getActiveSheet()->mergeCells("A2:M2");

		// .($nama_customer ? $nama_customer : "")
		$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('A1', ' LAPORAN PENJUALAN ' )
		->setCellValue('A2', ' Periode '.date('d F Y', strtotime($tanggal_start)).' s/d '.date('d F Y', strtotime($tanggal_end)))
		->setCellValue('A4', 'No')
		->setCellValue('B4', 'No Faktur')
		->setCellValue('C4', 'Tanggal')
		->setCellValue('D4', 'Qty')
		->setCellValue('E4', 'Jumlah Roll')
		->setCellValue('F4', 'Gudang')
		->setCellValue('G4', 'Nama Barang')
		->setCellValue('H4', 'Nama Jual')
		->setCellValue('I4', 'Harga Jual')
		->setCellValue('J4', 'Total')
		// ->setCellValue('J4', 'Diskon')
		// ->setCellValue('J4', 'Ongkos Kirim')
		->setCellValue('K4', 'Nama Customer')
		->setCellValue('L4', 'Keterangan')
		;

		$coll_now = "M";
		foreach ($tipe_bayar as $row2) {
			$objPHPExcel->getActiveSheet()->setCellValue($coll_now."4",$row2->nama);
			$coll_now++;
		}

		$objPHPExcel->getActiveSheet()->getStyle('A1:'.$coll_now.'4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('A1:'.$coll_now.'4')->applyFromArray($styleArray);

		foreach ($this->satuan_list_aktif as $row) {
			${'g_total_'.$row->id} = 0;
			${'g_returtotal_'.$row->id} = 0;
			${'idx_'.$row->id} = 0;
			${'yard_total_'.$row->id} = 0;
			${'roll_total_'.$row->id} = 0;
			${'yard_returtotal_'.$row->id} = 0;
			${'roll_returtotal_'.$row->id} = 0;
		}
		$idx = 1; $row_no = 5; $g_total = 0;
		$yard_total = 0;
		$roll_total = 0;
		$row_jual_last = 6;

		$total_lunas = 0;
		$total_kontra = 0;
		$total_belum_lunas = 0;
		
		foreach ($penjualan_list as $row) {
			$total = array();

			$qty = explode('??', $row->qty);
			$harga_jual = explode('??', $row->harga_jual);
			$jumlah_roll = explode('??', $row->jumlah_roll);
			$nama_gudang = explode('??', $row->nama_gudang);
			$nama_barang = explode('??', $row->nama_barang);
			$nama_jual = explode('??', $row->nama_jual);
			$satuan_id = explode('??', $row->satuan_id);
			$count = count($qty);
			// $g_total = 0;


			$coll = "A";
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$idx);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(7);
			$coll++;

			// echo $coll.$row_no.':'.$coll.$row_end.'--'.$isi.'<br>';
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$row->no_faktur);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(20);
			$coll++;

			$tanggal = date('d-m-Y',strtotime($row->tanggal));
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$tanggal);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;

			$coll_start = $coll;
			$row_start = $row_no;
			$sub_total = 0;

			foreach ($harga_jual as $key => $value) {
				$coll = $coll_start;
				$yard_total += $qty[$key];
				$roll_total += $jumlah_roll[$key];
				${'yard_total_'.$satuan_id[$key]} += $qty[$key];
				if ($key != 3) {
					${'roll_total_'.$satuan_id[$key]} += $jumlah_roll[$key];
				}

				// $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, str_replace("??","\n",$row->qty));
				$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $qty[$key]);				
				// $objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setWrapText(true);
				$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
				$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(10);
				$coll++;

				// $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, str_replace('??',"\n",$row->jumlah_roll));
				$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $jumlah_roll[$key]);
				// $objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setWrapText(true);
				$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
				$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(12);
				$coll++;

				$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $nama_gudang[$key]);				
				$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
				$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(20);
				$coll++;

				$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $nama_barang[$key]);				
				$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
				$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(30);
				$coll++;

				// $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, str_replace('??',"\n",$row->nama_jual));
				$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $nama_jual[$key]);				
				// $objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setWrapText(true);
				$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
				$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(30);
				$coll++;

				// $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, str_replace('??',"\n",$row->harga_jual));
				$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $harga_jual[$key]);				
				// $objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setWrapText(true);
				$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
				$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
				$coll++;

				$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $qty[$key] * $harga_jual[$key]);
				// $objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setWrapText(true);
				$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
				$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
				$coll++;

				if ($key != $count -1) {
					$row_no++;
				}
				$sub_total += $qty[$key] * $harga_jual[$key];
				$g_total += $qty[$key] * $harga_jual[$key];
				${'g_total_'.$satuan_id[$key]} += $qty[$key] * $harga_jual[$key];
			}

			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_start, $row->nama_customer);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_start)->getAlignment()->setWrapText(true);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_start)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_start)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(30);
			$coll++;

			$status = '';
			$x = explode(",", $row->ket_lunas);
			$x2 = array_unique($x);
			$lunas_ket = implode(",",$x2);



			if ($lunas_ket == 'belum lunas') {
				$total_belum_lunas += $sub_total;
				$status = "belum lunas";
			}elseif ($lunas_ket == 'lunas1' || $lunas_ket == 'lunas' ) {
				$total_lunas += $sub_total;
				$status = 'lunas';
			}else{
				$total_kontra += $sub_total;
				$status = 'kontra bon';
			}
 
			// if (is_posisi_id()==1) {
			// 	echo $status.'<hr/>';
			// }

			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_start, $status);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_start)->getAlignment()->setWrapText(true);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_start)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_start)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(30);
			$coll++;

			$pembayaran_type_id = explode(',', $row->pembayaran_type_id);
			$data_bayar = explode(',', $row->data_bayar);
			$bayar = array_combine($pembayaran_type_id, $data_bayar);
			$total_except_cash = 0;

			foreach ($bayar as $key => $value) {
				if ($key != 2 && $key != 5 && $key != 6) {
					$total_except_cash += $value;
				}
			}

			foreach ($tipe_bayar as $row2) {
				if (isset($bayar[$row2->id])) {
					$temp = $total_except_cash - $sub_total;
					if ($row2->id == 2 && $temp > 0) {								
						$value = $bayar[$row2->id] - $temp;
					}else if($row2->id == 2 && $bayar[$row2->id] > $sub_total){
						$value = $sub_total;
					}else{
						$value = str_replace("??", "\n", $bayar[$row2->id]);
					}
					$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_start, $value);
					$objPHPExcel->getActiveSheet()->getStyle($coll.$row_start)->getAlignment()->setWrapText(true);
					$objPHPExcel->getActiveSheet()->getStyle($coll.$row_start)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
					$objPHPExcel->getActiveSheet()->getStyle($coll.$row_start)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
				}

				if ($row2->id == 2 && !isset($bayar[2]) && $total_except_cash != $sub_total && $total_except_cash != 0) {
					$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_start, $sub_total - $total_except_cash);
					$objPHPExcel->getActiveSheet()->getStyle($coll.$row_start)->getAlignment()->setWrapText(true);
					$objPHPExcel->getActiveSheet()->getStyle($coll.$row_start)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
					$objPHPExcel->getActiveSheet()->getStyle($coll.$row_start)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
				}
				$coll++;
			}

			$idx++;
			$last_row = $row_no;
			$row_no++;

			$objPHPExcel->getActiveSheet()->setCellValue('I'.$row_no, "SUBTOTAL");
			$objPHPExcel->getActiveSheet()->getStyle("I".$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

			$objPHPExcel->getActiveSheet()->setCellValue('J'.$row_no, $sub_total);
			$objPHPExcel->getActiveSheet()->getStyle("J".$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);

			$objPHPExcel->getActiveSheet()->getStyle('I'.$row_no.':J'.$row_no)->applyFromArray($styleArray);
			
			$row_jual_last = $row_no;

			$row_no++;			
			$row_no++;			
		}

		$row_no++;
		if (count($retur_list) > 0) {
			$objPHPExcel->getActiveSheet()->setCellValue("A".$row_no,'RETUR');
			$objPHPExcel->getActiveSheet()->getStyle('A'.$row_no.':L'.$row_no)->applyFromArray($styleArrayBG);
			$row_no++;
			$row_no++;
		
		}

		$yard_retur_total = 0;
		$roll_retur_total = 0;
		$g_retur_total = 0;

		// $objPHPExcel->getActiveSheet()->setTitle('Rit 1');

		//============================================================================================
		foreach ($retur_list as $row) {
			$total = array();

			$qty = explode('??', $row->qty);
			$harga_jual = explode('??', $row->harga_jual);
			$jumlah_roll = explode('??', $row->jumlah_roll);
			$nama_gudang = explode('??', $row->nama_gudang);
			$nama_barang = explode('??', $row->nama_barang);
			$nama_jual = explode('??', $row->nama_jual);
			$satuan_id = explode('??', $row->satuan_id);
			$count = count($qty);
			// $g_total = 0;


			$coll = "A";
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$idx);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(7);
			$coll++;

			// echo $coll.$row_no.':'.$coll.$row_end.'--'.$isi.'<br>';
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$row->no_faktur);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(20);
			$coll++;

			$tanggal = date('d-m-Y',strtotime($row->tanggal));
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$tanggal);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;

			$coll_start = $coll;
			$row_start = $row_no;
			$sub_total = 0;

			foreach ($harga_jual as $key => $value) {
				$coll = $coll_start;
				$yard_retur_total += $qty[$key];
				$roll_retur_total += $jumlah_roll[$key];
				${'yard_returtotal_'.$satuan_id[$key]} += $qty[$key];
				${'roll_returtotal_'.$satuan_id[$key]} += $jumlah_roll[$key];

				// $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, str_replace("??","\n",$row->qty));
				$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $qty[$key]);				
				// $objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setWrapText(true);
				$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
				$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(10);
				$coll++;

				// $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, str_replace('??',"\n",$row->jumlah_roll));
				$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $jumlah_roll[$key]);
				// $objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setWrapText(true);
				$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
				$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(12);
				$coll++;

				$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $nama_gudang[$key]);				
				$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
				$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(20);
				$coll++;

				$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $nama_barang[$key]);				
				$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
				$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(30);
				$coll++;

				// $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, str_replace('??',"\n",$row->nama_jual));
				$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $nama_jual[$key]);				
				// $objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setWrapText(true);
				$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
				$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(30);
				$coll++;

				// $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, str_replace('??',"\n",$row->harga_jual));
				$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $harga_jual[$key]);				
				// $objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setWrapText(true);
				$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
				$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
				$coll++;			
				$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $qty[$key] * $harga_jual[$key]);
				// $objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setWrapText(true);
				$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
				$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
				$coll++;

				if ($key != $count -1) {
					$row_no++;
				}
				$sub_total += $qty[$key] * $harga_jual[$key];
				$g_retur_total += $qty[$key] * $harga_jual[$key];
				${'g_returtotal_'.$satuan_id[$key]} += $qty[$key] * $harga_jual[$key];
			}

			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_start, $row->nama_customer);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_start)->getAlignment()->setWrapText(true);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_start)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_start)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(30);
			$coll++;

			$status = '';
			// if ($row->keterangan < 0) {
			// $status = 'belum lunas';
			// }else if ($row->keterangan >= 0){
			// 	$status = 'lunas';
			// } 

			if ($row->ket_lunas == 'belum lunas') {
				$status = "belum lunas";
			}elseif ($row->ket_lunas == 'lunas1') {
				$status = 'lunas';
			}else{
				$status = 'kontra bon';
			}

			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_start, $status);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_start)->getAlignment()->setWrapText(true);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_start)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_start)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(30);
			$coll++;

			$pembayaran_type_id = explode(',', $row->pembayaran_type_id);
			$data_bayar = explode(',', $row->data_bayar);
			$bayar = array_combine($pembayaran_type_id, $data_bayar);

			foreach ($tipe_bayar as $row2) {
				if (isset($bayar[$row2->id])) {
					$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_start, $bayar[$row2->id]);
					$objPHPExcel->getActiveSheet()->getStyle($coll.$row_start)->getAlignment()->setWrapText(true);
					$objPHPExcel->getActiveSheet()->getStyle($coll.$row_start)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
					$objPHPExcel->getActiveSheet()->getStyle($coll.$row_start)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
				}
				$coll++;
			}

			$idx++;
			$last_row = $row_no;
			$row_no++;

			$objPHPExcel->getActiveSheet()->setCellValue('I'.$row_no, "SUBTOTAL");
			$objPHPExcel->getActiveSheet()->getStyle("I".$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

			$objPHPExcel->getActiveSheet()->setCellValue('J'.$row_no, $sub_total);
			$objPHPExcel->getActiveSheet()->getStyle("J".$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);

			$objPHPExcel->getActiveSheet()->getStyle('I'.$row_no.':J'.$row_no)->applyFromArray($styleArray);

			$row_no++;
			$row_no++;			
			
		}

		//=======================================================================================
				
		$row_start = $row_no;
		$coll = "M";
		foreach ($tipe_bayar as $row2) {
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_start, "=SUM(".$coll."5:".$coll.$row_jual_last.")");
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_start)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_start)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;
		}

		$objPHPExcel->getActiveSheet()->setCellValue('B'.$row_no, 'Penjualan');
		$objPHPExcel->getActiveSheet()->setCellValue('D'.$row_no, $yard_total);
		$objPHPExcel->getActiveSheet()->setCellValue('E'.$row_no, $roll_total);
		$objPHPExcel->getActiveSheet()->setCellValue('J'.$row_no, $g_total);
		// $objPHPExcel->getActiveSheet()->setCellValue('I'.$row_no, $g_total);
		$objPHPExcel->getActiveSheet()->getStyle('A'.$row_no.':J'.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('A'.$row_no.':J'.$row_no)->applyFromArray($styleArray);
		$row_no++;

		if (count($retur_list) > 0) {
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$row_no, 'Retur');
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$row_no, $yard_retur_total);
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$row_no, $roll_retur_total);
			$objPHPExcel->getActiveSheet()->setCellValue('J'.$row_no, $g_retur_total);
			// $objPHPExcel->getActiveSheet()->setCellValue('I'.$row_no, $g_total);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$row_no.':J'.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$row_no.':J'.$row_no)->applyFromArray($styleArray);
			$row_no++;

			$objPHPExcel->getActiveSheet()->setCellValue('B'.$row_no, 'TOTAL');
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$row_no, $yard_total - $yard_retur_total);
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$row_no, $roll_total - $roll_retur_total);
			$objPHPExcel->getActiveSheet()->setCellValue('J'.$row_no, $g_total - $g_retur_total);
			// $objPHPExcel->getActiveSheet()->setCellValue('I'.$row_no, $g_total);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$row_no.':J'.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$row_no.':J'.$row_no)->applyFromArray($styleArray);
		}

		$row_no++;

		$objPHPExcel->getActiveSheet()->setCellValue('B'.$row_no, 'TOTAL JUAL');
		$objPHPExcel->getActiveSheet()->setCellValue('C'.$row_no, $g_total);
		$row_no++;
		$objPHPExcel->getActiveSheet()->setCellValue('B'.$row_no, 'TOTAL LUNAS');
		$objPHPExcel->getActiveSheet()->setCellValue('C'.$row_no, $total_lunas);
		$row_no++;
		$objPHPExcel->getActiveSheet()->setCellValue('B'.$row_no, 'TOTAL KONTRA');
		$objPHPExcel->getActiveSheet()->setCellValue('C'.$row_no, $total_kontra);
		$row_no++;
		$objPHPExcel->getActiveSheet()->setCellValue('B'.$row_no, 'TOTAL BELUM LUNAS');
		$objPHPExcel->getActiveSheet()->setCellValue('C'.$row_no, $total_belum_lunas);
		$row_no++;


		$rmov_char = [',','.'];
		// echo "Laporan_Penjualan_".str_replace($rmov_char, '', $nama_customer_raw).' '.date("dmY",strtotime($tanggal_start))."sd_".date("dmY",strtotime($tanggal_end)).".xls";
		
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		ob_end_clean();


		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header("Content-Disposition: attachment;filename=Laporan_Penjualan_".date("dmY",strtotime($tanggal_start))."sd_".date("dmY",strtotime($tanggal_end)).".xls");
		header('Cache-Control: max-age=0');
		$objWriter->save('php://output');
		
	}

	function penjualan_list_export_excel_split_month(){

		$tanggal_start = is_date_formatter($this->input->get('tanggal_start'));
		$tanggal_end = is_date_formatter($this->input->get('tanggal_end'));
		$tipe_search = $this->input->get('tipe_search');
		$customer_id = $this->input->get('customer_id');
		$nama_customer = '';
		$gudang_id = $this->input->get('gudang_id');
		$gudang_cond = '';
		if ($gudang_id != 0) {
			$gudang_cond = " AND gudang_id = ".$gudang_id;
		}

		if ($customer_id != 0) {
			$get_customer = $this->common_model->db_select('nd_customer where id='.$customer_id);
			foreach ($get_customer as $row) {
				$nama_customer = $row->nama.'_';
			}
		}
		
		$cond = '';
		$customer_cond = "";
		$customer_cond_retur = "";
		$nama_customer = "";
		$nama_customer_raw = "";

		if ($customer_id != null && $customer_id != 0) {
			$customer_cond = ' AND customer_id = '.$customer_id;
			$customer_cond_retur = ($customer_cond_retur == '' ? 'WHERE ': 'AND ').' customer_id = '.$customer_id ;

			$get = $this->common_model->db_select("nd_customer where id=".$customer_id);
			foreach ($get as $row) {
				$nama_customer = "Customer : ".$row->nama;
				$nama_customer_raw = $row->nama;
			}
		}

		if ($tipe_search == 2) {
			$cond = "WHERE pembayaran_type_id LIKE '%2%' AND  (ifnull(total_bayar,0) - (ifnull(g_total,0) - ifnull(diskon,0)) + ifnull(ongkos_kirim,0)) >= 0";
		}elseif ($tipe_search == 3) {
			$cond = "WHERE pembayaran_type_id LIKE '%2%' AND  (ifnull(total_bayar,0) - (ifnull(g_total,0) - ifnull(diskon,0)) + ifnull(ongkos_kirim,0)) >= 0";
		}elseif ($tipe_search == 4) {
			$cond = " WHERE  ket_lunas != 'lunas' AND ket_lunas != 'lunas1' ";
		}elseif ($tipe_search == 5) {
			$customer_cond .= " AND  fp_status = 1";
			$customer_cond_retur .= ($customer_cond_retur == '' ? "WHERE " : "AND ")."fp_status = 1";
		}elseif ($tipe_search == 6) {
			$customer_cond .= " AND  fp_status != 1";
			$customer_cond_retur .= ($customer_cond_retur == '' ? "WHERE " : "AND ")."fp_status != 1";
		}

		$tipe_penjualan_cond = "";
		if ($this->input->get('penjualan_type_id') && $this->input->get('penjualan_type_id') != 0) {
			$penjualan_type_id = $this->input->get('penjualan_type_id');
			$tipe_penjualan_cond = "AND penjualan_type_id = ".$this->input->get('penjualan_type_id');
		}

		$barang_id_cond = '';
		if ($this->input->get('barang_id') && $this->input->get('barang_id') != '') {
			$barang_id = $this->input->get('barang_id');
			$barang_id_cond = " AND barang_id = ".$barang_id;
		}

		$warna_id_cond = '';
		if ($this->input->get('warna_id') && $this->input->get('warna_id') != '') {
			$warna_id = $this->input->get('warna_id');
			$warna_id_cond = " AND warna_id = ".$warna_id;
		}


		$data['nama_customer'] = $nama_customer;
		$data['nama_customer_raw'] = $nama_customer_raw;

		$data['tanggal_start'] = $tanggal_start;
		$data['tanggal_end'] = $tanggal_end;
		
		
		$get_list = $this->common_model->db_select("nd_penjualan 
			WHERE tanggal >= '$tanggal_start'
			AND tanggal <= '$tanggal_end'
			AND status_aktif = 1
			$customer_cond
			$tipe_penjualan_cond
			ORDER BY tanggal desc");

		$data['penjualan_list'] = array();
		$id_list = array();
		foreach ($get_list as $row) {
			array_push($id_list, $row->id);
		}
		
		if (count($id_list > 0)) {
			$data['penjualan_list'] = $this->rpt_model->get_penjualan_report($cond, $gudang_cond,$barang_id_cond, $warna_id_cond, implode(",", $id_list));
		}
		$data['retur_list'] = $this->rpt_model->get_retur_report($tanggal_start, $tanggal_end, $customer_cond_retur, $gudang_cond);
		$data['tipe_bayar'] = $this->common_model->db_select("nd_pembayaran_type");

		$this->load->library('Excel/PHPExcel');

		ini_set("memory_limit", "600M");

		$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_discISAM;
		$cacheSettings = array('');;

		PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);

		$data['objPHPExcel'] = new PHPExcel();
		$data[$tanggal_start] = $tanggal_start;
		$data['tanggal_end'] = $tanggal_end;

		$this->load->view('admin/report/penjualan_list_report_excel',$data);
		
	}

//=====================================pembelian report==================================================

	function pembelian_list_report(){
		$menu = is_get_url($this->uri->segment(1)) ;

		$tanggal_start = date('Y-m-d');
		$tanggal_end = date('Y-m-d');
		$status_excel = '0';
		$toko_id = 1;
		$supplier_id = 0;
		$barang_id = 0;
		$barang_beli_id = 0;
		$warna_id = 0;
		$gudang_id = 0;
		$tipe_tanggal = 1;

		if ($this->input->get('tanggal_start') && $this->input->get('tanggal_end') != '') {
			$tanggal_start = is_date_formatter($this->input->get('tanggal_start'));
			$tanggal_end = is_date_formatter($this->input->get('tanggal_end'));
			$status_excel = '1';
			$toko_id = $this->input->get('toko_id');
			$supplier_id = $this->input->get('supplier_id');
			$barang_id = $this->input->get('barang_id');
			$barang_beli_id = $this->input->get('barang_beli_id');
			$warna_id = $this->input->get('warna_id');
			$gudang_id = $this->input->get('gudang_id');
			$tipe_tanggal = $this->input->get('tipe_tanggal');
		}

		$data = array(
			'content' =>'admin/report/pembelian_list_report',
			'breadcrumb_title' => 'Laporan',
			'breadcrumb_small' => 'Laporan Pembelian',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data' => $this->data,
			'tanggal_start' => is_reverse_date($tanggal_start),
			'tanggal_end' => is_reverse_date($tanggal_end),
			'status_excel' => $status_excel,
			'toko_id' => $toko_id,
			'barang_id' => $barang_id,
			'barang_beli_id' => $barang_beli_id,
			'warna_id' => $warna_id,
			'supplier_id' => $supplier_id,
			'gudang_id' => $gudang_id,
			'tipe_tanggal' => $tipe_tanggal
			);

		$cond = '';
		if ($toko_id != 0) {
			$cond .= " AND toko_id = $toko_id";
		}

		if ($supplier_id != 0) {
			$cond .= " AND supplier_id = $supplier_id";
		}

		$cond_gudang = ($gudang_id != 0 ? ' AND gudang_id = '.$gudang_id : '');
		$cond_barang = ($barang_id != 0 ? " AND barang_id = ".$barang_id : '');
		$cond_barang_beli = ($barang_beli_id != 0 ? " AND barang_beli_id = ".$barang_beli_id : '');
		$cond_warna = ($warna_id != 0 ? " AND warna_id = ".$warna_id : '');
		$cond_tanggal = "";
		$order_by = "tanggal";

		$tipe_supplier = 1;
		$tipe_supplier = ($supplier_id == 0 ? 1 : '');
		if ($supplier_id != 0) {
			foreach ($this->supplier_list_aktif as $row) {
				$tipe_supplier = $row->tipe_supplier;
			}
		}
		if ($tipe_tanggal == 1) {
			$cond_tanggal = "AND tanggal >= '$tanggal_start' AND tanggal <= '$tanggal_end'";
			$order_by = "tanggal";
		}else{
			$cond_tanggal = "AND created_at >= '$tanggal_start 00:00:00' AND created_at <= '$tanggal_end 23:59:59'";
			$order_by = "created_at";
		}
		if ($supplier_id == 0 || $tipe_supplier == 1) {
			$data['pembelian_list'] = $this->rpt_model->get_pembelian_report($cond_tanggal, $cond, $cond_barang, $cond_warna, $cond_gudang, $order_by, $cond_barang_beli);
		}

		if ($supplier_id == 0 || $tipe_supplier == 2 ) {
			if ($cond_barang == '' && $cond_warna == '') {
				$data['pembelian_list_lain'] = $this->rpt_model->get_pembelian_lain_report($tanggal_start, $tanggal_end, ($supplier_id == 0 ? '' : "AND supplier_id=".$supplier_id) );
			}
		}

		$data['pembelian_retur']= $this->rpt_model->get_pembelian_retur($cond_tanggal, $cond, $cond_barang, $cond_warna, $cond_gudang, $cond_barang_beli);
		$data['tipe_supplier'] = $tipe_supplier;
		if (is_posisi_id()==1) {
			// echo $cond_tanggal;
			// print_r($data['pembelian_retur']);
			// echo $cond_tanggal, $cond, $cond_barang, $cond_warna, $cond_gudang, $order_by;
			$this->load->view('admin/template',$data);
			# code...
		}else{
			$this->load->view('admin/template',$data);
		}

	}

	function pembelian_list_export_excel(){

		$tanggal_start = is_date_formatter($this->input->get('tanggal_start'));
		$tanggal_end = is_date_formatter($this->input->get('tanggal_end'));
		$toko_id = $this->input->get('toko_id');
		$gudang_id = $this->input->get('gudang_id');
		$barang_id = $this->input->get('barang_id');
		$warna_id = $this->input->get('warna_id');
		$supplier_id = $this->input->get('supplier_id');
		$tipe_tanggal = $this->input->get('tipe_tanggal');
		$tipe_supplier = $this->input->get('tipe_supplier');
		$nama_supplier = '';
		if ($toko_id == 0) {
			$nama_toko = "SEMUA TOKO";
		}else{
			$result = $this->common_model->db_select('nd_toko WHERE id='.$toko_id);
			foreach ($result as $row) {
				$nama_toko = $row->nama;
			}
		}

		$cond = '';
		if ($toko_id != 0) {
			$cond = " AND toko_id = $toko_id";
		}

		if ($supplier_id != 0) {
			$cond .= " AND supplier_id = $supplier_id";
			$get = $this->common_model->db_select('nd_supplier where id='.$supplier_id);
			foreach ($get as $row) {
				$nama_supplier = " ke supplier ".$row->nama;
			}
		}

		$cond_gudang = ($gudang_id != 0 ? ' AND gudang_id = '.$gudang_id : '');
		$cond_barang = ($barang_id != 0 ? " AND barang_id = ".$barang_id : '');
		$cond_warna = ($warna_id != 0 ? " AND warna_id = ".$warna_id : '');
		$cond_tanggal = '';
		$order_by = '';

		if ($tipe_tanggal == 1) {
			$cond_tanggal = "AND tanggal >= '$tanggal_start' AND tanggal <= '$tanggal_end'";
			$order_by = "tanggal";
		}else{
			$cond_tanggal = "AND created_at >= '$tanggal_start 00:00:00' AND created_at <= '$tanggal_end 23:59:59'";
			// $cond_tanggal = "AND created_at >= '$tanggal_start' AND created_at <= '$tanggal_end'";
			$order_by = "created_at";
		}
		// if ($supplier_id == 0 || $tipe_supplier == 1) {
		// 	$pembelian_list = $this->rpt_model->get_pembelian_report($cond_tanggal, $cond, $cond_barang, $cond_warna, $cond_gudang, $order_by);
		// }else{
			
		// }

		$pembelian_list = $this->rpt_model->get_pembelian_report_excel($cond_tanggal, $cond, $cond_barang, $cond_warna, $cond_gudang, $order_by);
		// $pembelian_retur= $this->rpt_model->get_pembelian_retur($tanggal_start, $tanggal_end, $cond, $cond_barang, $cond_warna, $cond_gudang);
		$pembelian_retur= $this->rpt_model->get_pembelian_retur($cond_tanggal, $cond, $cond_barang, $cond_warna, $cond_gudang);
		
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

		$objPHPExcel->getActiveSheet()->mergeCells("A1:L1");
		$objPHPExcel->getActiveSheet()->mergeCells("A2:L2");

		
		$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('A1', ' LAPORAN PEMBELIAN '.($tipe_tanggal == 1 ? '(by tanggal surat jalan)' : '(by tanggal input)').$nama_toko.$nama_supplier)
		->setCellValue('A2', ' Periode '.date('d F Y', strtotime($tanggal_start)).' s/d '.date('d F Y', strtotime($tanggal_end)))
		->setCellValue('A4', 'No')
		->setCellValue('B4', 'No Faktur')
		->setCellValue('C4', 'Tanggal')
		->setCellValue('D4', 'Tanggal Input')
		->setCellValue('E4', 'Qty')
		->setCellValue('F4', 'Jumlah Roll')
		->setCellValue('G4', 'Nama Barang')
		->setCellValue('H4', 'Nama Jual')
		->setCellValue('I4', 'Harga Beli')
		->setCellValue('J4', 'Diskon')
		->setCellValue('K4', 'Total')
		->setCellValue('L4', 'Supplier')
		->setCellValue('M4', 'Lokasi')
		->setCellValue('N4', 'Po Number')
		->setCellValue('O4', 'Jatuh Tempo')
		->setCellValue('P4', 'Keterangan')
		;

		$objPHPExcel->getActiveSheet()->setTitle('PEMBELIAN');


		$objPHPExcel->getActiveSheet()->getStyle('A1:N4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('A1:N4')->applyFromArray($styleArray);


		// print_r($pembelian_list);
		$idx = 1; $row_no = 5; $g_total = 0; $last_row= 6;
		$yard_total = 0;
		$roll_total = 0;

		foreach ($this->satuan_list_aktif as $row) {
			${'g_total_'.$row->id} = 0;
			${'idx_'.$row->id} = 0;
			${'yard_total_'.$row->id} = 0;
			${'roll_total_'.$row->id} = 0;
		}

		foreach ($pembelian_list as $row) {
			$total = array();

			$qty = explode('??', $row->qty);
			$harga_beli = explode('??', $row->harga_beli);
			$jumlah_roll = explode('??', $row->jumlah_roll);
			$nama_barang = explode('??', $row->nama_barang);
			$nama_jual = explode('??', $row->nama_jual);
			$satuan_id = explode('??', $row->satuan_id);
			$count = count($qty);
			// $g_total = 0;


			$coll = "A";
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$idx);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(7);
			$coll++;

			// echo $coll.$row_no.':'.$coll.$row_end.'--'.$isi.'<br>';
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$row->no_faktur);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(20);
			$coll++;

			$tanggal = date('d-m-Y',strtotime($row->tanggal));
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$tanggal);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;

			$created = date('d-m-Y H:i',strtotime($row->created_at));
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$created);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;

			$coll_start = $coll;
			$row_start = $row_no;

			

			foreach ($harga_beli as $key => $value) {
				$coll = $coll_start;
				$yard_total += $qty[$key];
				$roll_total += $jumlah_roll[$key];
				${'yard_total_'.$satuan_id[$key]} += $qty[$key];
				${'roll_total_'.$satuan_id[$key]} += $jumlah_roll[$key];

				// $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, str_replace("??","\n",$row->qty));
				$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $qty[$key]);				
				// $objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setWrapText(true);
				$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
				$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
				$coll++;

				// $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, str_replace('??',"\n",$row->jumlah_roll));
				$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $jumlah_roll[$key]);
				// $objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setWrapText(true);
				$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
				$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(12);
				$coll++;

				$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $nama_barang[$key]);				
				$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
				$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(30);
				$coll++;

				$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $nama_jual[$key]);				
				$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
				$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(30);
				$coll++;

				// $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, str_replace('??',"\n",$row->harga_beli));
				$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $harga_beli[$key]);				
				// $objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setWrapText(true);
				$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
				$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
				$coll++;

				$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_start, $row->diskon);
				$objPHPExcel->getActiveSheet()->getStyle($coll.$row_start)->getAlignment()->setWrapText(true);
				$objPHPExcel->getActiveSheet()->getStyle($coll.$row_start)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
				$objPHPExcel->getActiveSheet()->getStyle($coll.$row_start)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
				$coll++;

				$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $qty[$key] * $harga_beli[$key] - $row->diskon);
				// $objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setWrapText(true);
				$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
				$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
				$coll++;

				$row_akhir = $row_no;
				if ($key != $count -1) {
					$row_no++;
				}
				$g_total += $qty[$key] * $harga_beli[$key];
				${'g_total_'.$satuan_id[$key]} += $qty[$key] * $value;
			}

			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_start, $row->nama_supplier);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_start)->getAlignment()->setWrapText(true);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_start)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_start)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;

			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_start, $row->nama_gudang);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_start)->getAlignment()->setWrapText(true);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_start)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_start)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;

			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_start, $row->po_number);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_start)->getAlignment()->setWrapText(true);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_start)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_start)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;

			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_start, date('d-m-Y', strtotime($row->jatuh_tempo)));
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_start)->getAlignment()->setWrapText(true);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_start)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_start)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(30);
			$coll++;

			$status = '';
			if ($row->keterangan < 0) {
				$status = 'belum lunas';
			}else if ($row->keterangan >= 0){
				$status = 'lunas';
			} 

			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_start, $status);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_start)->getAlignment()->setWrapText(true);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_start)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_start)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(30);
			$coll++;


			$objPHPExcel->getActiveSheet()->mergeCells("A$row_start:A$row_akhir");
			$objPHPExcel->getActiveSheet()->mergeCells("B$row_start:B$row_akhir");
			$objPHPExcel->getActiveSheet()->mergeCells("C$row_start:C$row_akhir");
			$objPHPExcel->getActiveSheet()->mergeCells("D$row_start:D$row_akhir");
			
			$objPHPExcel->getActiveSheet()->mergeCells("L$row_start:L$row_akhir");
			$objPHPExcel->getActiveSheet()->mergeCells("M$row_start:M$row_akhir");
			$objPHPExcel->getActiveSheet()->mergeCells("N$row_start:N$row_akhir");
			$objPHPExcel->getActiveSheet()->mergeCells("O$row_start:O$row_akhir");
			$objPHPExcel->getActiveSheet()->mergeCells("P$row_start:P$row_akhir");

			$idx++;
			$last_row = $row_no;
			$row_no++;
			
		}

		//=======================================================================================		

		foreach ($this->satuan_list_aktif as $row) {
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$row_no, 'TOTAL');
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$row_no, $row->nama);
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$row_no, ${'yard_total_'.$row->id});
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$row_no, ${'roll_total_'.$row->id});
			// $objPHPExcel->getActiveSheet()->setCellValue('J'.$row_no, ${'g_total_'.$row->id});
			$objPHPExcel->getActiveSheet()->setCellValue('K'.$row_no, ${'g_total_'.$row->id});
			// $objPHPExcel->getActiveSheet()->setCellValue('I'.$row_no, $g_total);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$row_no.':K'.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$row_no.':K'.$row_no)->applyFromArray($styleArray);
			$row_no++;	
		}

		$objPHPExcel->getActiveSheet()->setCellValue('B'.$row_no, 'TOTAL');
		$objPHPExcel->getActiveSheet()->setCellValue('D'.$row_no, $yard_total);
		$objPHPExcel->getActiveSheet()->setCellValue('E'.$row_no, $roll_total);
		$objPHPExcel->getActiveSheet()->setCellValue('H'.$row_no, "=SUM( H5:H".$last_row.')');
		$objPHPExcel->getActiveSheet()->setCellValue('I'.$row_no, "=SUM( I5:I".$last_row.')');
		// $objPHPExcel->getActiveSheet()->setCellValue('J'.$row_no, "=SUM( J5:J".$last_row.')');
		$objPHPExcel->getActiveSheet()->setCellValue('K'.$row_no, "=SUM( K5:K".$last_row.')');
		// $objPHPExcel->getActiveSheet()->setCellValue('I'.$row_no, $g_total);
		$objPHPExcel->getActiveSheet()->getStyle('A'.$row_no.':K'.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('A'.$row_no.':K'.$row_no)->applyFromArray($styleArray);

		
		// $objPHPExcel->getActiveSheet()->setTitle('Rit 1');

		//====================================================================================

		//============================================================================================
		
		$objPHPExcel->createSheet(1);
		$objPHPExcel->setActiveSheetIndex(1)
		->setCellValue('A1', ' RETUR PEMBELIAN '.$nama_toko.$nama_supplier)
		->setCellValue('A2', ' Periode '.date('d F Y', strtotime($tanggal_start)).' s/d '.date('d F Y', strtotime($tanggal_end)))
		->setCellValue('A4', 'No')
		->setCellValue('B4', 'Tanggal')
		->setCellValue('C4', 'No Surat Jalan')
		->setCellValue('D4', 'NO PO')
		->setCellValue('E4', 'Qty')
		->setCellValue('F4', 'Jumlah Roll')
		->setCellValue('G4', 'Nama Barang')
		->setCellValue('H4', 'Harga Beli')
		->setCellValue('I4', 'Total')
		->setCellValue('J4', 'Supplier')
		->setCellValue('K4', 'Keterangan')
		->setCellValue('L4', 'Keterangan')
		;

		$objPHPExcel->getActiveSheet()->setTitle('RETUR');


		$objPHPExcel->getActiveSheet()->getStyle('A1:L4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('A1:L4')->applyFromArray($styleArray);

			$qty_retur_total = 0;
			$roll_retur_total = 0;
			$g_retur_total = 0;
			$idx=1;
			$row_no =5;
			$coll = "A";

		foreach ($pembelian_retur as $row) {
			$total = array();

			$qty = explode('??', $row->qty);
			$harga_beli = explode('??', $row->harga_beli);
			$jumlah_roll = explode('??', $row->jumlah_roll);
			$nama_gudang = explode('??', $row->nama_gudang);
			$nama_barang = explode('??', $row->nama_barang);
			$nama_barang = explode('??', $row->nama_barang);
			$satuan_id = explode('??', $row->satuan_id);
			$count = count($qty);
			// $g_total = 0;


			$coll = "A";
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$idx);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(7);
			$coll++;

			// echo $coll.$row_no.':'.$coll.$row_end.'--'.$isi.'<br>';
			$tanggal = date('d-m-Y',strtotime($row->tanggal));
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$tanggal);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;

			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$row->no_sj_lengkap);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(20);
			$coll++;


			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$row->po_number);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(20);
			$coll++;


			$coll_start = $coll;
			$row_start = $row_no;
			$sub_total = 0;

			foreach ($harga_beli as $key => $value) {
				$coll = $coll_start;
				$qty_retur_total += $qty[$key];
				$roll_retur_total += $jumlah_roll[$key];

				// $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, str_replace("??","\n",$row->qty));
				$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $qty[$key]);				
				// $objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setWrapText(true);
				$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
				$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(10);
				$coll++;

				// $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, str_replace('??',"\n",$row->jumlah_roll));
				$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $jumlah_roll[$key]);
				// $objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setWrapText(true);
				$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
				$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(12);
				$coll++;

				$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $nama_barang[$key]);				
				$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
				$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(30);
				$coll++;

				// $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, str_replace('??',"\n",$row->harga_beli));
				$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $harga_beli[$key]);				
				// $objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setWrapText(true);
				$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
				$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
				$coll++;			
				$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $qty[$key] * $harga_beli[$key]);
				// $objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setWrapText(true);
				$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
				$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
				$coll++;

				if ($key != $count -1) {
					$row_no++;
				}
				$sub_total += $qty[$key] * $harga_beli[$key];
				$g_retur_total += $qty[$key] * $harga_beli[$key];
				// ${'g_returtotal_'.$satuan_id[$key]} += $qty[$key] * $harga_beli[$key];
			}

			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_start, $row->nama_supplier);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_start)->getAlignment()->setWrapText(true);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_start)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_start)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(30);
			$coll++;

			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_start, $row->keterangan1);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_start)->getAlignment()->setWrapText(true);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_start)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_start)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(30);
			$coll++;

			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_start, $row->keterangan2);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_start)->getAlignment()->setWrapText(true);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_start)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_start)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(30);
			$coll++;

			$status = '';
			// if ($row->keterangan < 0) {
			// $status = 'belum lunas';
			// }else if ($row->keterangan >= 0){
			// 	$status = 'lunas';
			// } 

			$idx++;
			$last_row = $row_no;
			$row_no++;
			// $row_no++;
			// $row_no++;			
			
		}

			$objPHPExcel->getActiveSheet()->setCellValue('H'.$row_no, "TOTAL");
			$objPHPExcel->getActiveSheet()->getStyle("H".$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

			$objPHPExcel->getActiveSheet()->setCellValue('I'.$row_no, $g_retur_total);
			$objPHPExcel->getActiveSheet()->getStyle("I".$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);

			$objPHPExcel->getActiveSheet()->getStyle('H'.$row_no.':I'.$row_no)->applyFromArray($styleArray);


		/*$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');

		ob_end_clean();

		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header("Content-Disposition: attachment;filename=Laporan_Pembelian_".str_replace(' ', '_', $nama_toko)."_".date("dmY",strtotime($tanggal_start))."sd_".date("dmY",strtotime($tanggal_end)).".xls");
		header('Cache-Control: max-age=0');
		$objWriter->save('php://output');
	*/


		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');

		ob_end_clean();

		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header("Content-Disposition: attachment;filename=Laporan_Pembelian_".($tipe_tanggal == 1 ? '(by tanggal surat jalan)' : '(by tanggal input)').str_replace(' ', '_', $nama_toko)."_".date("dmY",strtotime($tanggal_start))."sd_".date("dmY",strtotime($tanggal_end)).".xls");
		header('Cache-Control: max-age=0');
		$objWriter->save('php://output');
	}

//======================================laporan_harian===========================================

	function penerimaan_harian_penjualan(){
		$menu = is_get_url($this->uri->segment(1)) ;
		$tanggal_start = date('Y-m-d');
		$tanggal_end = date('Y-m-d');

		if($this->input->get('tanggal_start') && $this->input->get('tanggal_start') != '' && $this->input->get('tanggal_end') != '' ){
			$tanggal_start = is_date_formatter($this->input->get('tanggal_start'));
			$tanggal_end = is_date_formatter($this->input->get('tanggal_end'));
		}

		$data = array(
			'content' =>'admin/report/penerimaan_harian_penjualan',
			'breadcrumb_title' => 'Laporan',
			'breadcrumb_small' => 'Penerimaan Harian Penjualan',
			'tanggal_start' => is_reverse_date($tanggal_start),
			'tanggal_end' => is_reverse_date($tanggal_end),
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data'=> $this->data,
			'data_isi'=> $this->data
		);

		$data['penjualan_list'] = $this->rpt_model->get_penjualan_bayar_by_date($tanggal_start.' 00:00:00', $tanggal_end.' 23:59:59 ');
		$data['retur_list'] = $this->rpt_model->get_retur_jual_by_date($tanggal_start, $tanggal_end);
		$data['dp_list'] = $this->rpt_model->get_dp_by_date($tanggal_start, $tanggal_end);
		// foreach ($data['dp_list'] as $row) {
		// 	foreach ($row as $key => $value) {
		// 		echo 'group_concat(ifnull('.$key.",'-') SEPARATOR '??') as $key ,";
		// 	}
		// 	echo "<hr/>";
		// }
		$data['pembayaran_type'] = $this->common_model->db_select("nd_pembayaran_type");
		$this->load->view('admin/template',$data);

		if (is_posisi_id()==1) {
			// $this->output->enable_profiler(TRUE);
		}

	}


//======================================laporan_gp===========================================

	function penjualan_laba_list_report(){
		$menu = is_get_url($this->uri->segment(1)) ;

		if ($this->input->get('tanggal_start') && $this->input->get('tanggal_end') != '') {
			$tanggal_start = is_date_formatter($this->input->get('tanggal_start'));
			$tanggal_end = is_date_formatter($this->input->get('tanggal_end'));
			$tipe_search = $this->input->get('tipe_search');
			$customer_id = $this->input->get('customer_id');
			if ($tipe_search > 4 || $tipe_search < 1) {
				$tipe_search = 1;
			}
			$status_excel = '1';
		}else{
			$tanggal_start = date('Y-m-d');
			$tanggal_end = date('Y-m-d');
			$status_excel = '0';
			$tipe_search = 1;
			$customer_id = 0;
		}

		$cond = 'WHERE total is not null';
		if ($tipe_search == 2) {
			$cond = "AND pembayaran_type_id LIKE '%2%' AND  (ifnull(total_bayar,0) - (ifnull(g_total,0) - ifnull(diskon,0)) + ifnull(ongkos_kirim,0)) >= 0";
		}elseif ($tipe_search == 3) {
			$cond = "AND pembayaran_type_id LIKE '%2%' AND  (ifnull(total_bayar,0) - (ifnull(g_total,0) - ifnull(diskon,0)) + ifnull(ongkos_kirim,0)) >= 0";
		}elseif ($tipe_search == 4) {
			$cond = " AND  (ifnull(total_bayar,0) - (ifnull(g_total,0) - ifnull(diskon,0)) + ifnull(ongkos_kirim,0)) < 0";
		}
		$customer_cond = "";
		if ($customer_id != null && $customer_id != 0) {
			$customer_cond = 'AND customer_id = '.$customer_id;
		}

		$tahun_awal = '0';
		$bulan_harga = '12_harga';
		$get_first_tutup_buku = $this->common_model->db_select("nd_tutup_buku order by id asc limit 1");
		foreach ($get_first_tutup_buku as $row) {
			$tanggal_awal = $row->tanggal;
			$bulan_harga = date("m", strtotime($row->tanggal))."_harga";
		}

		$get_latest_tutup_buku = $this->common_model->db_select("nd_tutup_buku WHERE tanggal <= '".$tanggal_start."' order by tanggal desc, id desc limit 1");
		$get_tahun = date("Y", strtotime($tanggal_start));
		$get_bulan = date("m", strtotime($tanggal_start));
		$bulan_harga_2 = $get_bulan.'_harga';
		$bulan_qty_2 = $get_bulan.'_qty';
		$tgl_start_ambil = $tanggal_start;
		
		$tipe = 2;
		if ($this->input->get('tipe') != '') {
			$tipe = $this->input->get('tipe');
		}
		
		foreach ($get_latest_tutup_buku as $row) {
			$get_tahun = date("Y", strtotime($row->tanggal));
			$get_bulan = date("m", strtotime($row->tanggal));
			$bulan_harga_2 = $get_bulan.'_harga';
			$bulan_qty_2 = $get_bulan.'_qty';
			$tgl_tutup_buku = date("Y-m-t", strtotime($row->tanggal));
			$tgl_start_ambil = date("Y-m-d", strtotime($tgl_tutup_buku.' + 1 day'));
		}

		$data = array(
			'content' =>'admin/report/penjualan_laba_list_report',
			'breadcrumb_title' => 'Laporan',
			'breadcrumb_small' => 'Laporan GP',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data' => $this->data,
			'tanggal_start' => is_reverse_date($tanggal_start),
			'tanggal_end' => is_reverse_date($tanggal_end),
			'status_excel' => $status_excel,
			'tipe_search' => $tipe_search,
			'customer_id' => $customer_id,
			'penjualan_list' => $this->rpt_model->get_penjualan_laba_report($tanggal_start, $tanggal_end, $cond,$customer_cond, $tanggal_awal, $bulan_harga),
			'tgl_tutup_buku' => $tgl_tutup_buku,
			'tipe' => $tipe
		);

		$b = date("m", strtotime($tanggal_end));
		$b_idx = date("Y", strtotime($tanggal_end)).str_pad($b,2,'0');

		if ($tipe == 1) {
			# code...
			// klo pake hpp bulan berjalan
			$data['data_hpp'] = $this->rpt_model->get_hpp_by_mutasi($get_tahun,$get_bulan, $bulan_qty_2, $bulan_harga_2, $tanggal_start, $b_idx, $tgl_start_ambil);
		}else{
			// klo pake hpp bulan lalu + beli bulan berjalan
			$data['data_hpp'] = $this->rpt_model->get_hpp_by_mutasi2($get_tahun,$get_bulan, $bulan_qty_2, $bulan_harga_2, $tanggal_start, $b_idx, $tgl_start_ambil);
		}

			

			// echo $tanggal_start, $tanggal_end, $cond,$customer_cond, $tanggal_awal, $bulan_harga;
			// 2022-02-01, 2022-02-16, WHERE total is not null, 2018-12-31, 12_harga

			// echo $get_tahun,$get_bulan, $bulan_qty_2, $bulan_harga_2;
			// print_r($data['data_hpp']);
			// echo $bulan_harga;
		if (is_posisi_id() != 1) {
			// echo $tanggal_start, $tanggal_end.'--'. $cond.'--'.$customer_cond.'--'. $tanggal_awal.'--'.$bulan_harga;
			$this->load->view('admin/template',$data);
		}else{
			// echo $tipe;
			$this->load->view('admin/template',$data);
			
			
		}
	}

	function penjualan_laba_list_report_excel()
	{
		if ($this->input->get('tanggal_start') && $this->input->get('tanggal_end') != '') {
			$tanggal_start = is_date_formatter($this->input->get('tanggal_start'));
			$tanggal_end = is_date_formatter($this->input->get('tanggal_end'));
			$tipe_search = $this->input->get('tipe_search');
			$customer_id = $this->input->get('customer_id');
			if ($tipe_search > 4 || $tipe_search < 1) {
				$tipe_search = 1;
			}
			$status_excel = '1';
		}else{
			$tanggal_start = date('Y-m-d');
			$tanggal_end = date('Y-m-d');
			$status_excel = '0';
			$tipe_search = 1;
			$customer_id = 0;
		}

		$tipe = 2;
		if ($this->input->get('tipe') != '') {
			$tipe = $this->input->get('tipe');
		}

		$cond = 'WHERE total is not null';
		if ($tipe_search == 2) {
			$cond = "AND pembayaran_type_id LIKE '%2%' AND  (ifnull(total_bayar,0) - (ifnull(g_total,0) - ifnull(diskon,0)) + ifnull(ongkos_kirim,0)) >= 0";
		}elseif ($tipe_search == 3) {
			$cond = "AND pembayaran_type_id LIKE '%2%' AND  (ifnull(total_bayar,0) - (ifnull(g_total,0) - ifnull(diskon,0)) + ifnull(ongkos_kirim,0)) >= 0";
		}elseif ($tipe_search == 4) {
			$cond = " AND  (ifnull(total_bayar,0) - (ifnull(g_total,0) - ifnull(diskon,0)) + ifnull(ongkos_kirim,0)) < 0";
		}
		$customer_cond = "";
		if ($customer_id != null && $customer_id != 0) {
			$customer_cond = 'AND customer_id = '.$customer_id;
		}

		$tahun_awal = '0';
		$bulan_harga = '12_harga';
		$get_first_tutup_buku = $this->common_model->db_select("nd_tutup_buku order by id asc limit 1");
		foreach ($get_first_tutup_buku as $row) {
			$tanggal_awal = $row->tanggal;
			$bulan_harga = date("m", strtotime($row->tanggal))."_harga";
		}

		// $get_latest_tutup_buku = $this->common_model->db_select("nd_tutup_buku order by tanggal desc, id desc limit 1");
		$get_latest_tutup_buku = $this->common_model->db_select("nd_tutup_buku WHERE tanggal <= '".$tanggal_start."' order by tanggal desc, id desc limit 1");
		$get_tahun = date("Y", strtotime($tanggal_start));
		$get_bulan = date("m", strtotime($tanggal_start));
		$bulan_harga_2 = $get_bulan.'_harga';
		$bulan_qty_2 = $get_bulan.'_qty';
		$tgl_tutup_buku = '';
		$tgl_start_ambil = $tanggal_start;
		
		foreach ($get_latest_tutup_buku as $row) {
			$get_tahun = date("Y", strtotime($row->tanggal));
			$get_bulan = date("m", strtotime($row->tanggal));
			$bulan_harga_2 = $get_bulan.'_harga';
			$bulan_qty_2 = $get_bulan.'_qty';
			$tgl_tutup_buku = date("Y-m-t", strtotime($row->tanggal));
			$tgl_start_ambil = date("Y-m-d", strtotime($tgl_tutup_buku.' + 1 day'));
		}

		$data = array(
			'common_data' => $this->data,
			'tanggal_start' => ($tanggal_start),
			'tanggal_end' => ($tanggal_end)
		);

		$b = date("m", strtotime($tanggal_end));
		$b_idx = date("Y", strtotime($tanggal_end)).str_pad($b,2,'0');

		$data['penjualan_list'] = $this->rpt_model->get_penjualan_laba_report($tanggal_start, $tanggal_end, $cond,$customer_cond, $tanggal_awal, $bulan_harga);
		if ($tipe == 1) {
			$data['data_hpp'] = $this->rpt_model->get_hpp_by_mutasi($get_tahun,$get_bulan, $bulan_qty_2, $bulan_harga_2, $tanggal_start, $b_idx, $tgl_start_ambil);
			# code...
		}else{
			$data['data_hpp'] = $this->rpt_model->get_hpp_by_mutasi2($get_tahun,$get_bulan, $bulan_qty_2, $bulan_harga_2, $tanggal_start, $b_idx, $tgl_start_ambil);
		}
		$data['tgl_tutup_buku'] = $tgl_tutup_buku;

		// if (is_posisi_id() != 1) {
			$this->load->library('Excel/PHPExcel');
	
			ini_set("memory_limit", "600M");
			$this->load->view('admin/report/laporan_gp_excel', $data);
			# code...
		// }else{
		// 	echo $tgl_start_ambil;
		// 	echo $get_tahun.'<br/>'.$get_bulan.'<br/>'. $bulan_qty_2.'<br/>'. $bulan_harga_2.'<br/>'. $tanggal_start.'<br/>'.$b_idx;
		// }

	}

//======================================general_report===========================================
	function penjualan_general_report()
	{
		
		$tanggal_start = date('Y-m-01');
		$tanggal_end = date('Y-m-t');

		if ($this->input->get('tanggal_start') && $this->input->get('tanggal_start') != '' && $this->input->get('tanggal_end') != '') {
			$tanggal_start = is_date_formatter($this->input->get('tanggal_start'));
			$tanggal_end = is_date_formatter($this->input->get('tanggal_end'));
		}

		$bulan = date('d M y', strtotime($tanggal_start)).' s/d '.date('d M y', strtotime($tanggal_end));

		$tahun = date("Y");
		if ($this->input->get('tahun') != '') {
			$tahun = $this->input->get('tahun');
		}

		//tahun 2
		$tahun_2 = date('Y');

		if ($this->input->get('tahun_2') != '') {
			$tahun_2 = $this->input->get('tahun_2');
		}



		$data = array(
			'content' => 'admin/report/penjualan_general_report',
			'breadcrumb_title' => 'General Report Penjualan',
			'breadcrumb_small' => 'Statistik & Laporan',
			'nama_menu' => 'menu_report',
			'nama_submenu' => '',
			'tahun' => $tahun,
			'tahun_2' => $tahun_2,
			'tanggal_start' => is_reverse_date($tanggal_start),
			'tanggal_end' => is_reverse_date($tanggal_end),
			'ket_tgl' => $bulan,
			'common_data'=> $this->data );


		$data['recap_pembelian_bulanan'] = $this->admin_model->recap_pembelian_bulanan($tanggal_start, $tanggal_end);
		$data['recap_penjualan_bulanan'] = $this->admin_model->recap_penjualan_bulanan($tanggal_start, $tanggal_end);
		$this->load->view('admin/template',$data);
	}
	
	function get_penjualan_bulan(){

		$recap_list = $this->admin_model->get_list_penjualan_by_date(date('Y-m-01'), date('Y-m-t'));
		echo json_encode($recap_list);
	}

	function get_penjualan_tahun(){

		$recap_list = $this->admin_model->get_list_penjualan_tahunan(date('Y-01-01'), date('Y-12-31'));

		echo json_encode($recap_list);
	}

	function get_barang_jual_terbanyak(){
		$recap_list = $this->admin_model->get_barang_jual_terbanyak(date('Y'));

		echo json_encode($recap_list);
	}

	function get_customer_beli_terbanyak(){
		$recap_list = $this->admin_model->get_customer_beli_terbanyak(date('Y'));

		echo json_encode($recap_list);
	}

//=====================================barang masuk report==================================================	

	function barang_masuk_list_report(){
		$menu = is_get_url($this->uri->segment(1)) ;
		$tipe = 2;

		if ($this->input->get('tanggal_start') && $this->input->get('tanggal_end') != '') {
			$tanggal_start = is_date_formatter($this->input->get('tanggal_start'));
			$tanggal_end = is_date_formatter($this->input->get('tanggal_end'));
			$status_excel = '1';
			$toko_id = $this->input->get('toko_id');
			$supplier_id = $this->input->get('supplier_id');
			$barang_id = $this->input->get('barang_id');
			$warna_cond = $this->input->get('warna_cond');
		}else{
			$tanggal_start = date('Y-m-d');
			$tanggal_end = date('Y-m-d');
			$status_excel = '0';
			$toko_id = 1;
			$supplier_id = 0;
			$barang_id = 0;
			$warna_cond = 0;
		}

		if ($this->input->get('tipe') && $this->input->get('tipe') != '') {
			$tipe = $this->input->get('tipe');
		}

		$data = array(
			'content' =>'admin/report/barang_masuk_list_report',
			'breadcrumb_title' => 'Laporan',
			'breadcrumb_small' => 'Barang Masuk',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data' => $this->data,
			'tanggal_start' => is_reverse_date($tanggal_start),
			'tanggal_end' => is_reverse_date($tanggal_end),
			'status_excel' => $status_excel,
			'toko_id' => $toko_id,
			'supplier_id' => $supplier_id,
			'barang_id' => $barang_id,
			'warna_cond' => $warna_cond,
			'tipe' => $tipe
			);

		$cond = '';
		if ($toko_id != 0) {
			$cond .= "AND toko_id = $toko_id";
		}

		if ($supplier_id != 0) {
			$cond .= " AND supplier_id = $supplier_id";
		}

		$cond2 = '';
		if ($barang_id != 0) {
			$cond2 = " WHERE barang_id = $barang_id";
		}

		$group_by = "GROUP BY barang_id, warna_id";
		if ($warna_cond == 1) {
			$group_by = " GROUP BY barang_id";
		}

		if ($tipe == 1) {
			$data['barang_list'] = $this->rpt_model->get_barang_masuk_report($tanggal_start, $tanggal_end, $cond, $cond2, $group_by);
		}else{
			$data['barang_list'] = $this->rpt_model->get_barang_masuk_report_2($tanggal_start, $tanggal_end, $cond, $cond2, $group_by);
		}
		$this->load->view('admin/template',$data);
	}

	function barang_masuk_list_detail_report(){
		$menu = is_get_url($this->uri->segment(1)) ;

		$tanggal_start = is_date_formatter($this->input->get('tanggal_start'));
		$tanggal_end = is_date_formatter($this->input->get('tanggal_end'));
		$toko_id = $this->input->get('toko_id');
		$supplier_id = $this->input->get('supplier_id');
		$barang_id = $this->input->get('barang_id');
		$warna_id = $this->input->get('warna_id');

		$get = $this->common_model->db_select('nd_barang where id='.$barang_id);
		foreach ($get as $row) {
			$nama_barang = $row->nama;
		}
		$get = $this->common_model->db_select('nd_warna where id='.$warna_id);
		foreach ($get as $row) {
			$nama_warna = $row->warna_beli;
		}
		$data = array(
			'content' =>'admin/report/barang_masuk_list_detail_report' ,
			'breadcrumb_title' => 'Laporan',
			'breadcrumb_small' => 'barang masuk detail',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data'=> $this->data,
			'tanggal_start' => is_reverse_date($tanggal_start),
			'tanggal_end' => is_reverse_date($tanggal_end),
			'barang_id' => $barang_id,
			'warna_id' => $warna_id,
			'nama_barang' => $nama_barang,
			'nama_warna' => $nama_warna,
			'supplier_list_aktif'=>$this->supplier_list_aktif );

		$cond = '';
		if ($toko_id != 0) {
			$cond .= "AND toko_id = $toko_id";
		}

		if ($supplier_id != 0) {
			$cond .= " AND supplier_id = $supplier_id";
		}

		$cond2 = " WHERE barang_id = $barang_id AND warna_id = $warna_id";

		$data['barang_list'] = $this->rpt_model->get_barang_masuk_detail_report($tanggal_start, $tanggal_end, $cond, $cond2);
		$this->load->view('admin/template_no_sidebar',$data);
	}


//=====================================barang keluar report==================================================	

	function barang_keluar_list_report(){
		$menu = is_get_url($this->uri->segment(1)) ;

		if ($this->input->get('tanggal_start') && $this->input->get('tanggal_end') != '') {
			$tanggal_start = is_date_formatter($this->input->get('tanggal_start'));
			$tanggal_end = is_date_formatter($this->input->get('tanggal_end'));
			$status_excel = '1';
			$toko_id = $this->input->get('toko_id');
			$customer_id = $this->input->get('customer_id');
			$barang_id = $this->input->get('barang_id');
			$warna_cond = $this->input->get('warna_cond');
		}else{
			$tanggal_start = date('Y-m-d');
			$tanggal_end = date('Y-m-d');
			$status_excel = '0';
			$toko_id = 1;
			$customer_id = 0;
			$barang_id = 0;
			$warna_cond = 0;
		}

		$data = array(
			'content' =>'admin/report/barang_keluar_list_report',
			'breadcrumb_title' => 'Laporan',
			'breadcrumb_small' => 'Barang keluar',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data' => $this->data,
			'tanggal_start' => is_reverse_date($tanggal_start),
			'tanggal_end' => is_reverse_date($tanggal_end),
			'status_excel' => $status_excel,
			'toko_id' => $toko_id,
			'customer_id' => $customer_id,
			'barang_id' => $barang_id,
			'warna_cond' => $warna_cond
			);

		$cond = '';
		if ($toko_id != 0) {
			$cond .= "AND toko_id = $toko_id";
		}

		if ($customer_id != 0) {
			$cond .= " AND customer_id = $customer_id";
		}

		$cond2 = '';
		if ($barang_id != 0) {
			$cond2 = " WHERE barang_id = $barang_id";
		}

		$group_by = "GROUP BY barang_id, warna_id";
		if ($warna_cond == 1) {
			$group_by = " GROUP BY barang_id";
		}

		$data['barang_list'] = $this->rpt_model->get_barang_keluar_report($tanggal_start, $tanggal_end, $cond, $cond2, $group_by);
		$this->load->view('admin/template',$data);
	}

	function barang_keluar_list_detail_report(){
		$menu = is_get_url($this->uri->segment(1)) ;

		$tanggal_start = is_date_formatter($this->input->get('tanggal_start'));
		$tanggal_end = is_date_formatter($this->input->get('tanggal_end'));
		$toko_id = $this->input->get('toko_id');
		$customer_id = $this->input->get('customer_id');
		$barang_id = $this->input->get('barang_id');
		$warna_id = $this->input->get('warna_id');

		$get = $this->common_model->db_select('nd_barang where id='.$barang_id);
		foreach ($get as $row) {
			$nama_barang = $row->nama;
		}
		$get = $this->common_model->db_select('nd_warna where id='.$warna_id);
		foreach ($get as $row) {
			$nama_warna = $row->warna_beli;
		}
		$data = array(
			'content' =>'admin/report/barang_keluar_list_detail_report' ,
			'breadcrumb_title' => 'Laporan',
			'breadcrumb_small' => 'barang keluar detail',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data'=> $this->data,
			'tanggal_start' => is_reverse_date($tanggal_start),
			'tanggal_end' => is_reverse_date($tanggal_end),
			'barang_id' => $barang_id,
			'warna_id' => $warna_id,
			'nama_barang' => $nama_barang,
			'nama_warna' => $nama_warna,
			'customer_list_aktif'=>$this->customer_list_aktif );

		$cond = '';
		if ($toko_id != 0) {
			$cond .= "AND toko_id = $toko_id";
		}

		if ($customer_id != 0) {
			$cond .= " AND customer_id = $customer_id";
		}

		$cond2 = " WHERE barang_id = $barang_id AND warna_id = $warna_id";

		$data['barang_list'] = $this->rpt_model->get_barang_keluar_detail_report($tanggal_start, $tanggal_end, $cond, $cond2);
		$this->load->view('admin/template_no_sidebar',$data);
	}



//=====================================buku laporan piutang==================================================	

	function buku_laporan_piutang(){
		$menu = is_get_url($this->uri->segment(1)) ;

		if ($this->input->get('tanggal_start') && $this->input->get('tanggal_end') != '') {
			$tanggal_start = is_date_formatter($this->input->get('tanggal_start'));
			$tanggal_end = is_date_formatter($this->input->get('tanggal_end'));
			$tipe_search = $this->input->get('tipe_search');
			$customer_id = $this->input->get('customer_id');
			if ($tipe_search > 6 || $tipe_search < 1) {
				$tipe_search = 1;
			}
			$status_excel = '1';
		}else{
			$tanggal_start = date('Y-m-d');
			$tanggal_end = date('Y-m-d');
			$status_excel = '0';
			$tipe_search = 1;
			$customer_id = 0;
		}

		$cond = '';
		$customer_cond = "";
		
		if ($customer_id != null && $customer_id != 0) {
			$customer_cond = 'AND customer_id = '.$customer_id;
		}


		$data = array(
			'content' =>'admin/report/buku_laporan_piutang',
			'breadcrumb_title' => 'Laporan',
			'breadcrumb_small' => 'Buku Laporan Piutang',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data' => $this->data,
			'tanggal_start' => is_reverse_date($tanggal_start),
			'tanggal_end' => is_reverse_date($tanggal_end),
			'tipe_search' => $tipe_search,
			'customer_id' => $customer_id,
			// 'penjualan_list' => $this->rpt_model->buku_laporan_piutang($tanggal_start, $tanggal_end, $cond,$customer_cond)
			);

		$this->load->view('admin/template',$data);
	}


	function data_buku_laporan_piutang(){

		$aColumns = array('no_faktur','tanggal','qty','jumlah_roll','nama_barang', 'harga_jual','total','nama_customer','pembayaran_data','pelunasan_data','keterangan', 'penjualan_id');
        
        $sIndexColumn = "id";
        
        // paging
        $sLimit = "";
        if ( isset( $_GET['iDisplayStart'] ) && $_GET['iDisplayLength'] != '-1' ){
            $sLimit = "LIMIT ".mysql_real_escape_string( $_GET['iDisplayStart'] ).", ".
                mysql_real_escape_string( $_GET['iDisplayLength'] );
        }
        $numbering = mysql_real_escape_string( $_GET['iDisplayStart'] );
        $page = 1;
        
        // ordering
        if ( isset( $_GET['iSortCol_0'] ) ){
            $sOrder = "ORDER BY  ";
            for ( $i=0 ; $i<intval( $_GET['iSortingCols'] ) ; $i++ ){
                if ( $_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i]) ] == "true" ){
                    $sOrder .= $aColumns[ intval( $_GET['iSortCol_'.$i] ) ]."
                        ".mysql_real_escape_string( $_GET['sSortDir_'.$i] ) .", ";
                }
            }
            
            $sOrder = substr_replace( $sOrder, "", -2 );
            if ( $sOrder == "ORDER BY" ){
                $sOrder = "";
            }
        }

        // filtering
        $sWhere = "";
        if ( $_GET['sSearch'] != "" ){
            $sWhere = "WHERE (";
            for ( $i=0 ; $i<count($aColumns) ; $i++ ){
                $sWhere .= $aColumns[$i]." LIKE '%".mysql_real_escape_string( $_GET['sSearch'] )."%' OR ";
            }
            $sWhere = substr_replace( $sWhere, "", -3 );
            $sWhere .= ')';
        }
        
        // individual column filtering
        for ( $i=0 ; $i<count($aColumns) ; $i++ ){
            if ( $_GET['bSearchable_'.$i] == "true" && $_GET['sSearch_'.$i] != '' ){
                if ( $sWhere == "" ){
                    $sWhere = "WHERE ";
                }
                else{
                    $sWhere .= " AND ";
                }
                $sWhere .= $aColumns[$i]." LIKE '%".mysql_real_escape_string($_GET['sSearch_'.$i])."%' ";
            }
        }

        
        $tanggal_start = is_date_formatter($this->input->get('tanggal_start'));
		$tanggal_end = is_date_formatter($this->input->get('tanggal_end'));
		$cond_date = "WHERE tanggal >= '$tanggal_start' AND tanggal <= '$tanggal_end'";

		$customer_id = $this->input->get('customer_id');
		$customer_cond = ($customer_id != '' ? "AND customer_id =".$customer_id : '');
			
        
        $rResult = $this->rpt_model->buku_laporan_piutang($aColumns, $sWhere, $sOrder, $sLimit, $cond_date, $customer_cond);
        
        // $iFilteredTotal = 5;
        
        $rResultTotal = $this->common_model->db_select_num_rows('nd_penjualan '.$cond_date.' '.$customer_cond);
        $Filternya = $this->rpt_model->buku_laporan_piutang($aColumns, $sWhere, $sOrder, '', $cond_date, $customer_cond);
        $iFilteredTotal = $Filternya->num_rows();
        // $iTotal = $rResultTotal;
        // $iFilteredTotal = $iTotal;
        
        $output = array(
            "sEcho" => intval($_GET['sEcho']),
            "iTotalRecords" => $rResultTotal,
            "iTotalDisplayRecords" => $iFilteredTotal,
            "aaData" => array()
        );
        
        foreach ($rResult->result_array() as $aRow){
        	$y = 0;
            $row = array();
            for ( $i=0 ; $i<count($aColumns) ; $i++ ){
            	$row[] = $aRow[ $aColumns[$i] ];
            }
            $y++;
            $page++;
            $output['aaData'][] = $row;
        }
        
        echo json_encode( $output );
	}

//=====================================buku laporan penyesuaian==================================================

	function laporan_penyesuaian_stok(){
		$menu = is_get_url($this->uri->segment(1)) ;
		$cond_gudang = '1';
		$cond_barang = '';
		$cond_warna = '';

		$tanggal_start = date('Y-m-01');
		$tanggal_end = date('Y-m-t');
		$gudang_id = 1;
		$barang_id = '';
		$warna_id = '';

		if ($this->input->get('tanggal_start') && $this->input->get('tanggal_start') != '' && $this->input->get('tanggal_end') != '') {
			$tanggal_start = is_date_formatter($this->input->get('tanggal_start'));
			$tanggal_end = is_date_formatter($this->input->get('tanggal_end'));
			// $cond_gudang = $this->input->get('cond_gudang');
		}

		if ($this->input->get('gudang_id') && $this->input->get('gudang_id') != '') {
			$gudang_id = $this->input->get('gudang_id');
		}

		if ($this->input->get('barang_id') && $this->input->get('barang_id') != '') {
			$barang_id = $this->input->get('barang_id');
			$cond_barang = "AND barang_id = $barang_id";
		}

		if ($this->input->get('warna_id') && $this->input->get('warna_id') != '') {
			$warna_id = $this->input->get('warna_id');
			$cond_warna = "AND warna_id = $warna_id";
		}

		$cond_gudang = "AND gudang_id = ".$gudang_id;

		$data = array(
			'content' =>'admin/report/laporan_penyesuaian_stok',
			'breadcrumb_title' => 'Laporan',
			'breadcrumb_small' => 'Penyesuaian Stok Barang',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data'=> $this->data,
			'tanggal_start' => is_reverse_date($tanggal_start),
			'tanggal_end' => is_reverse_date($tanggal_end),
			'cond_gudang' => $cond_gudang,
			'barang_id' => $barang_id,
			'warna_id' => $warna_id,
			'gudang_id' => $gudang_id
			);


		$select = '';
		foreach ($this->gudang_list_aktif as $row) {
			$select .= ", SUM( if(gudang_id=".$row->id.", ifnull(qty_masuk,0), 0 ) ) - SUM( if(gudang_id=".$row->id.", ifnull(qty_keluar,0), 0 ) )  as ".$row->nama."_qty , SUM( if(gudang_id=".$row->id.", jumlah_roll_masuk, 0 ) ) - SUM( if(gudang_id=".$row->id.", jumlah_roll_keluar, 0 ) )  as ".$row->nama."_roll ";
		}
		$data['penyesuaian_stok_barang'] = $this->rpt_model->get_penyesuaian_stok($select, $tanggal_start, $tanggal_end, $cond_gudang, $cond_barang, $cond_warna); 
		$this->load->view('admin/template',$data);
	
	}

	function laporan_penyesuaian_stok_excel(){
		
		$tanggal_start = is_date_formatter($this->input->get('tanggal_start'));
		$tanggal_end = is_date_formatter($this->input->get('tanggal_end'));
		$gudang_id = $this->input->get('gudang_id');
		if ($gudang_id != 0) {
			$cond_gudang = "AND gudang_id = ".$gudang_id;
		}
		
		$select = '';
		foreach ($this->gudang_list_aktif as $row) {
			$select .= ", SUM( if(gudang_id=".$row->id.", ifnull(qty_masuk,0), 0 ) ) - SUM( if(gudang_id=".$row->id.", ifnull(qty_keluar,0), 0 ) )  as ".$row->nama."_qty , SUM( if(gudang_id=".$row->id.", jumlah_roll_masuk, 0 ) ) - SUM( if(gudang_id=".$row->id.", jumlah_roll_keluar, 0 ) )  as ".$row->nama."_roll ";
			$nama_gudang = ($row->id == $gudang_id ? $row->nama : '');
		}
		$penyesuaian_stok_barang = $this->rpt_model->get_penyesuaian_stok($select, $tanggal_start, $tanggal_end, $cond_gudang); 
		// print_r($penyesuaian_stok_barang);

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

		$objPHPExcel->getActiveSheet()->mergeCells("A1:M1");
		$objPHPExcel->getActiveSheet()->mergeCells("A2:M2");
		$objPHPExcel->getActiveSheet()->mergeCells("C4:D4");
		$objPHPExcel->getActiveSheet()->mergeCells("E4:F4");
		$objPHPExcel->getActiveSheet()->mergeCells("G4:H4");

		
		$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('A1', ' LAPORAN PENYESUAIAN STOK '.strtoupper($nama_gudang))
		->setCellValue('A2', ' Periode '.date('d F Y', strtotime($tanggal_start)).' s/d '.date('d F Y', strtotime($tanggal_end)))
		->setCellValue('A4', 'No')
		->setCellValue('B4', 'Nama')
		->setCellValue('C4', 'Masuk')
		->setCellValue('E4', 'Keluar')
		->setCellValue('G4', 'Total')

		->setCellValue('C5', 'Yard/Kg')
		->setCellValue('D5', 'Jumlah Roll')
		->setCellValue('E5', 'Yard/Kg')
		->setCellValue('F5', 'Jumlah Roll')
		->setCellValue('G5', 'Yard/Kg')
		->setCellValue('H5', 'Jumlah Roll')
		// ->setCellValue('J4', 'Diskon')
		// ->setCellValue('J4', 'Ongkos Kirim')
		;

		$coll_now = "H";
		
		$objPHPExcel->getActiveSheet()->getStyle('A1:'.$coll_now.'4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('A1:'.$coll_now.'4')->applyFromArray($styleArray);


		$idx = 1; $row_no = 6; $g_total = 0;
		$yard_total = 0;
		$roll_total = 0;
		foreach ($penyesuaian_stok_barang as $row) {
			$total = array();

			// $g_total = 0;


			$coll = "A";
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$idx);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(7);
			$coll++;

			// echo $coll.$row_no.':'.$coll.$row_end.'--'.$isi.'<br>';
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$row->nama_barang.' '.$row->nama_warna);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(30);
			$coll++;

			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$row->qty_masuk);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;

			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $row->jumlah_roll_masuk);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setWrapText(true);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;

			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$row->qty_keluar);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;

			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $row->jumlah_roll_keluar);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setWrapText(true);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;

			//===============================TOTAL=========================================
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,"=C".$row_no." - E".$row_no);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;

			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, "=D".$row_no." - F".$row_no);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setWrapText(true);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;

			$row_no++;
			$idx++;	
			
		}

		//=======================================================================================		

		
		// $objPHPExcel->getActiveSheet()->setTitle('Rit 1');


		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		ob_end_clean();


		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header("Content-Disposition: attachment;filename=Laporan_Penyesuaian_Stok_".$nama_gudang."_".date("dmY",strtotime($tanggal_start))."sd_".date("dmY",strtotime($tanggal_end)).".xls");
		header('Cache-Control: max-age=0');
		$objWriter->save('php://output');


	}

//=====================================buku laporan penyesuaian==================================================

	function laporan_mutasi_gudang(){
		$menu = is_get_url($this->uri->segment(1)) ;
		$cond_gudang = '1';

		$tanggal_start = date('Y-m-01');
		$tanggal_end = date('Y-m-t');
		$gudang_id = 1;

		if ($this->input->get('tanggal_start') && $this->input->get('tanggal_start') != '' && $this->input->get('tanggal_end') != '') {
			$tanggal_start = is_date_formatter($this->input->get('tanggal_start'));
			$tanggal_end = is_date_formatter($this->input->get('tanggal_end'));
			// $cond_gudang = $this->input->get('cond_gudang');
		}

		$data = array(
			'content' =>'admin/report/laporan_mutasi_gudang',
			'breadcrumb_title' => 'Laporan',
			'breadcrumb_small' => 'Mutasi Antar Gudang',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data'=> $this->data,
			'tanggal_start' => is_reverse_date($tanggal_start),
			'tanggal_end' => is_reverse_date($tanggal_end),
			'cond_gudang' => $cond_gudang,
			'gudang_id' => $gudang_id
			);

		$cond_gudang = " WHERE gudang_id_before = ".$gudang_id." OR gudang_id_after=".$gudang_id;
		$cond_tgl = " AND tanggal >='".$tanggal_start."' AND tanggal <'".$tanggal_end."'";


		$data['mutasi_gudang_barang'] = $this->rpt_model->db_select("nd_mutasi_barang ".$cond_gudang.$cond_tgl); 
		$this->load->view('admin/template',$data);
	
	}

//=======================================get PO Gantung=============================

	function po_gantung_list(){
		$menu = is_get_url($this->uri->segment(1)) ;
		
		$data = array(
			'content' =>'admin/report/po_gantung_list',
			'breadcrumb_title' => 'Laporan',
			'breadcrumb_small' => 'Laporan PO Gantung',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data' => $this->data,
			'po_gantung_list' => $this->rpt_model->get_po_gantung()
			);
		$this->load->view('admin/template',$data);
	}

	function po_gantung_list_detail(){
		$menu = is_get_url($this->uri->segment(1)) ;
		$id = $this->input->get('batch_id');
		$batch = $this->input->get('batch');
		$po_gantung_detail = $this->rpt_model->get_po_gantung_detail($id);

		$data = array(
			'content' =>'admin/report/po_gantung_list_detail',
			'breadcrumb_title' => 'Laporan',
			'breadcrumb_small' => 'Laporan PO Gantung Detail',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data' => $this->data,
			'po_gantung_detail' => $po_gantung_detail,
			'batch' => $batch
			);

		foreach ($po_gantung_detail as $row) {
			$po_pembelian_id = $row->po_pembelian_id;
		}
		$data['po_pembelian_data'] = $this->rpt_model->get_data_po_pembelian($po_pembelian_id);
		$this->load->view('admin/template',$data);
	}

//=======================================laporan PO Pembelian=============================

	function po_pembelian_report(){
		$menu = is_get_url($this->uri->segment(1)) ;
		$view_type = 1;
		$cond = '';
		if ($this->input->get('view_type')) {
			$view_type = $this->input->get('view_type');
		}

		if ($view_type == 2) {
			$cond = "WHERE locked_date is null";
		}
		$barang_id = '';
		$barang_beli_id = '';
		$warna_id = '';
		$supplier_id = '';

		$cond ='';
		if ($this->input->get('barang_id') && $this->input->get('barang_id') != '') {
			$barang_id = $this->input->get('barang_id');
			$cond = "WHERE barang_id = $barang_id";
		}

		if ($this->input->get('barang_beli_id') && $this->input->get('barang_beli_id') != '') {
			$barang_beli_id = $this->input->get('barang_beli_id');
			$cond = "WHERE barang_beli_id = $barang_beli_id";
		}

		if ($this->input->get('warna_id') && $this->input->get('warna_id') != '') {
			$warna_id = $this->input->get('warna_id');
			$cond = ($cond !='' ? $cond." AND warna_id = $warna_id" : "WHERE warna_id = $warna_id" );
		}

		if ($this->input->get('supplier_id') && $this->input->get('supplier_id') != '') {
			$supplier_id = $this->input->get('supplier_id');
			$cond = ($cond !='' ? $cond." AND supplier_id = $supplier_id" : "WHERE supplier_id = $supplier_id" );
		}
		$data = array(
			'content' =>'admin/report/po_pembelian_report',
			'breadcrumb_title' => 'Laporan',
			'breadcrumb_small' => 'Laporan Po Pembelian',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data' => $this->data,
			'view_type' => $view_type,
			'supplier_id' => $supplier_id,
			'barang_id' => $barang_id,
			'barang_beli_id' => $barang_beli_id,
			'warna_id' => $warna_id,
			'po_pembelian_report' => $this->rpt_model->po_pembelian_report($cond)
			);

			$this->load->view('admin/template',$data);
			if (is_posisi_id()==1) {
                $this->output->enable_profiler(TRUE);
			}
	}

	function po_pembelian_warna_lock_by_list(){
		$po_warna_list = explode(',', $this->input->post('po_warna_list'));
		$last_datang = explode(',', $this->input->post('last_datang'));
		$tgl = date('Y-m-d H:i:s');
		$user_id = is_user_id();

		$data = array();
		for ($i=0; $i < count($po_warna_list) ; $i++) { 
			array_push($data, array(
				'id' => $po_warna_list[$i] ,
				'locked_date' => $last_datang[$i],
				'locked_by' => $user_id )
			);
		}

		// $this->common_model->db_free_query_superadmin("UPDATE nd_po_pembelian_warna SET locked_date = '$tgl', locked_by=$user_id WHERE id in ($po_warna_list) AND locked_by is null ");
		$result = $this->common_model->db_update_batch("nd_po_pembelian_warna", $data, 'id');
		echo "OK";

	}

	function get_po_pembelian_detail_report(){
		$po_pembelian_batch_id = $this->input->post('batch_id');
		$po_pembelian_detail_report = $this->rpt_model->po_pembelian_detail_report($po_pembelian_batch_id);
		echo json_encode($po_pembelian_detail_report);
	}

	function po_pembelian_report_detail(){
		$menu = is_get_url($this->uri->segment(1)) ;
		$po_pembelian_id = $this->input->get('id');
		$po_pembelian_batch_id = $this->input->get('batch_id');
		$po_batch_data = $this->common_model->db_select("(
						SELECT t1.*, concat(if(t2.tanggal >= '2019-09-27', concat(ifnull(pre_po,''),t4.kode,'/',DATE_FORMAT(t2.tanggal,'%y'),'/',LPAD(po_number,3,'0')) ,concat(LPAD(po_number,3,'0'),'/',DATE_FORMAT(t2.tanggal,'%m'),'/',DATE_FORMAT(t2.tanggal,'%y'))),'-',batch,if(revisi > 1,concat('R',revisi-1),'') ) as po_number_lengkap
						FROM nd_po_pembelian_batch t1
						LEFT JOIN nd_po_pembelian t2
						ON t1.po_pembelian_id = t2.id
						LEFT JOIN nd_toko t3
						ON t2.toko_id = t3.id
						LEFT JOIN nd_supplier t4
						ON t2.supplier_id = t4.id
						WHERE t2.status_aktif = 1
						AND t1.id = $po_pembelian_batch_id
						AND t2.id = $po_pembelian_id
						) tPO");
		
		$data = array(
			'content' =>'admin/report/po_pembelian_report_detail',
			'breadcrumb_title' => 'Laporan',
			'breadcrumb_small' => 'Laporan Po Pembelian Detail',
			'nama_menu' => $menu[0],
			'po_pembelian_id' => $po_pembelian_id,
			'po_pembelian_batch_id' => $po_pembelian_batch_id,
			'nama_submenu' => $menu[1],
			'common_data' => $this->data,
			'po_data' => $this->common_model->db_select('nd_po_pembelian where id='.$po_pembelian_id),
			'po_batch_data' => $po_batch_data,
			'po_pembelian_detail_report' => $this->rpt_model->po_pembelian_detail_report($po_pembelian_batch_id),
			'po_pembelian_report' => $this->rpt_model->po_pembelian_report(''),
			);

		$this->load->view('admin/template',$data);
	}

	function po_pembelian_warna_lock(){
		$list = explode('??', $this->input->post('po_pembelian_warna_id'));
		foreach ($list as $key => $value) {
			$data = array(
				'locked_date' => date('Y-m-d H:i:s'),
				'locked_by' => is_user_id(),
				'locked_keterangan' => null );
			$this->common_model->db_update("nd_po_pembelian_warna", $data,'id', $value);
		}
		echo "OK";
	}

	function po_pembelian_warna_unlock(){
		$po_pembelian_warna_id = $this->input->post('po_pembelian_warna_id');
		$data = array(
			'locked_date' => null ,
			'locked_by' => null,
			'locked_keterangan' => null );
		$this->common_model->db_update("nd_po_pembelian_warna", $data,'id', $po_pembelian_warna_id);
		echo "OK";
	}

//=====================================laporan pembayaran hutang==================================================

	function pembayaran_hutang_report(){
		$menu = is_get_url($this->uri->segment(1)) ;
		
		$tanggal_awal = date('Y-m-01');
		$tanggal_akhir = date('Y-m-t');
		if ($this->input->get('tanggal_awal') && $this->input->get('tanggal_awal') != '' && $this->input->get('tanggal_akhir') != '') {
			$tanggal_awal = is_date_formatter($this->input->get('tanggal_awal'));
			$tanggal_akhir = is_date_formatter($this->input->get('tanggal_akhir'));
		}
		$data = array(
			'content' =>'admin/report/pembayaran_hutang_report',
			'breadcrumb_title' => 'Laporan',
			'breadcrumb_small' => 'Laporan Tutup Buku',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data' => $this->data,
			'tanggal_awal' => is_reverse_date($tanggal_awal),
			'tanggal_akhir' => is_reverse_date($tanggal_akhir)
			// 'penjualan_list' => $this->rpt_model->buku_laporan_piutang($tanggal_start, $tanggal_end, $cond,$customer_cond)
			);


		$data['pembayaran_list'] = $this->rpt_model->get_laporan_hutang($tanggal_awal, $tanggal_akhir);
		$this->load->view('admin/template',$data);
	}

//=====================================laporan tutup buku==================================================

	function tutup_buku_list(){
		$menu = is_get_url($this->uri->segment(1)) ;
		
		$data = array(
			'content' =>'admin/report/tutup_buku_list',
			'breadcrumb_title' => 'Laporan',
			'breadcrumb_small' => 'Laporan Tutup Buku',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data' => $this->data
			// 'penjualan_list' => $this->rpt_model->buku_laporan_piutang($tanggal_start, $tanggal_end, $cond,$customer_cond)
			);


		$data['tutup_buku_list'] = $this->common_model->db_select("nd_tutup_buku");
		$data['tahun_awal'] = 2019;
		$data['tahun_now'] = date('Y');
		$data['tgl_tutup'] = array();
		foreach ($data['tutup_buku_list'] as $row) {
			$data['tgl_tutup'][date('Y', strtotime($row->tanggal))][date('n',strtotime($row->tanggal))] = true;
		}
		$this->load->view('admin/template',$data);
	}

	function tutup_buku_insert(){
		$tanggal = $this->input->post('tanggal');
		$tanggal = date('Y-m-t', strtotime($tanggal.'-1'));
		$tanggal_start = date('Y-m-01', strtotime($tanggal));
		$tanggal_end = date('Y-m-t', strtotime($tanggal));
	 	$tanggal_awal = '2018-01-01';

		$stok_opname_id = 0;
		$getOpname = $this->common_model->db_select("nd_stok_opname where tanggal <= '".$tanggal_end."' order by tanggal desc limit 1");
		foreach ($getOpname as $row) {
			$tanggal_awal = $row->tanggal;
			$stok_opname_id = $row->id;
		}

		$id_before = 0;
		$get_data = $this->common_model->db_select("nd_tutup_buku where tanggal <'".$tanggal."' ORDER BY tanggal desc LIMIT 1");
		foreach ($get_data as $row) {
			$id_before = $row->id;
		}

		if ($id_before == 0) {
		 	echo $tanggal_awal.'<br/>';
		 	echo $tanggal_start.'<br/>';
		 	echo $tanggal_end.'<br/>';
		 	$get_mutasi_data = $this->rpt_model->data_tutup_buku_initial($tanggal_awal, $tanggal_start,$tanggal_end, $stok_opname_id);

		 	echo "<table>
		 		<tr>
					<td>Barang</td>
					<td>Warna</td>
					<td>Hpp</td>
					<td>Hpp beli</td>
					<td>gudang_id</td>
					<td colspan='2'>Stok</td>
					<td colspan='2'>Beli</td>
					<td colspan='2'>Mutasi Masuk</td>
					<td colspan='2'>Jual</td>
					<td colspan='2'>Mutasi</td>
					<td colspan='2'>Penyesuaian</td>
					<td colspan='2'>Retur</td>
					<td colspan='2'>Total</td>
				</tr>
		 	";
		 	foreach ($this->gudang_list_aktif as $row) {
			 	${'total_'.$row->id} = 0;
			 	${'totalroll_'.$row->id} = 0;
		 	}
			foreach ($get_mutasi_data as $row) { ?>
				<tr>
					<td><?=$row->nama_jual?>(<?=$row->barang_id;?>)</td>
					<td><?=$row->warna_jual?>(<?=$row->warna_id;?>)</td>
					<td><?=$row->hpp?></td>
					<td><?=$row->hpp_beli?></td>
					<td><?=$row->gudang_id?></td>
					<td><?=$row->qty_stock?></td>
					<td><?=$row->jumlah_roll_stock?></td>
					<td><?=$row->qty_beli?></td>
					<td><?=$row->jumlah_roll_beli?></td>
					<td><?=$row->qty_mutasi_masuk?></td>
					<td><?=$row->jumlah_roll_mutasi_masuk?></td>
					<td><?=$row->qty_jual?></td>
					<td><?=$row->jumlah_roll_jual?></td>
					<td><?=$row->qty_mutasi?></td>
					<td><?=$row->jumlah_roll_mutasi?></td>
					<td><?=$row->qty_penyesuaian?></td>
					<td><?=$row->jumlah_roll_penyesuaian?></td>
					<td><?=$row->qty_retur?></td>
					<td><?=$row->jumlah_roll_retur?></td>

					<?$sub_total=$row->qty_stock+$row->qty_beli+$row->qty_mutasi_masuk-$row->qty_jual-$row->qty_mutasi+$row->qty_penyesuaian+$row->qty_retur?>
					<?$sub_total_roll=$row->jumlah_roll_stock+$row->jumlah_roll_beli+$row->jumlah_roll_mutasi_masuk-$row->jumlah_roll_jual-$row->jumlah_roll_mutasi+$row->jumlah_roll_penyesuaian+$row->jumlah_roll_retur?>
					<?${'total_'.$row->gudang_id} += $sub_total;?>
					<?${'totalroll_'.$row->gudang_id} += $sub_total_roll;?>
					<td><?=$sub_total?></td>
					<td><?=$sub_total_roll?></td>
				</tr>
			<?}
		 	echo "</table>";
		};
	}

//=====================================laporan stok opname==================================================

	function stok_opname_report(){
		$menu = is_get_url($this->uri->segment(1)) ;
		$tanggal_start = date('Y-01-01');
		$tanggal_end = date('Y-12-31');
		$barang_cond = '';
		$warna_cond = '';
		$gudang_cond = '';
		$barang_id = '';
		$warna_id = '';
		$gudang_id = '';
		if ($this->input->get('tanggal_start') != '' && $this->input->get('tanggal_end') != '') {
			$tanggal_start = $this->input->get('tanggal_start');
			$tanggal_end = $this->input->get('tanggal_end');
			# code...
		}

		if($this->input->get('barang_id') != '' && $this->input->get('barang_id') != 0 ){
			$barang_id = $this->input->get('barang_id');
			$barang_cond = "AND barang_id = ".$barang_id;
		}
		if($this->input->get('warna_id') != '' && $this->input->get('warna_id') != 0){
			$warna_id = $this->input->get('warna_id');
			$warna_cond = "AND warna_id = ".$warna_id;
		}
		if($this->input->get('gudang_id') != '' && $this->input->get('gudang_id') != 0 ){
			$gudang_id = $this->input->get('gudang_id');
			$gudang_cond = "AND gudang_id = ".$gudang_id;
		}
		
		$data = array(
			'content' =>'admin/report/stok_opname_report',
			'breadcrumb_title' => 'Laporan',
			'breadcrumb_small' => 'Laporan Stok Opname',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data' => $this->data,
			'tanggal_start' => $tanggal_start,
			'tanggal_end' => $tanggal_end,
			'barang_id' => $barang_id,
			'warna_id' => $warna_id,
			'gudang_id' => $gudang_id
			// 'penjualan_list' => $this->rpt_model->buku_laporan_piutang($tanggal_start, $tanggal_end, $cond,$customer_cond)
			);


		$data['so_list'] = $this->rpt_model->get_so_report(is_date_formatter($tanggal_start).' 00:00:00', is_date_formatter($tanggal_end).' 23:59:00', $barang_cond, $warna_cond, $gudang_cond);
		$this->load->view('admin/template',$data);
	}

	function stok_opname_report_excel(){
		$tanggal_start = date('Y-01-01');
		$tanggal_end = date('Y-12-31');
		$barang_cond = '';
		$warna_cond = '';
		$gudang_cond = '';
		$barang_id = '';
		$warna_id = '';
		$gudang_id = '';
		if ($this->input->get('tanggal_start') != '' && $this->input->get('tanggal_end') != '') {
			$tanggal_start = $this->input->get('tanggal_start');
			$tanggal_end = $this->input->get('tanggal_end');
		}

		if($this->input->get('barang_id') != '' && $this->input->get('barang_id') != 0 ){
			$barang_id = $this->input->get('barang_id');
			$barang_cond = "AND barang_id = ".$barang_id;
		}
		if($this->input->get('warna_id') != '' && $this->input->get('warna_id') != 0){
			$warna_id = $this->input->get('warna_id');
			$warna_cond = "AND warna_id = ".$warna_id;
		}
		if($this->input->get('gudang_id') != '' && $this->input->get('gudang_id') != 0 ){
			$gudang_id = $this->input->get('gudang_id');
			$gudang_cond = "AND gudang_id = ".$gudang_id;
		}
		
		
		
		$this->load->library('Excel/PHPExcel');

		ini_set("memory_limit", "600M");
		$data['tanggal_start'] = $tanggal_start;
		$data['tanggal_end'] = $tanggal_end;
		$data['so_list'] = $this->rpt_model->get_so_report(is_date_formatter($tanggal_start).' 00:00:00', is_date_formatter($tanggal_end).' 23:59:00', $barang_cond, $warna_cond, $gudang_cond);
		$this->load->view('admin/report/stok_opname_report_excel', $data);

	}

	function penyesuaian_stok_opname_remove(){
		$penyesuaian_stok_id = $this->input->post('penyesuaian_stok_id');
		$stok_opname_id = $this->input->post('stok_opname_id');
		if ($penyesuaian_stok_id != '') {
			$this->common_model->db_delete('nd_penyesuaian_stok','id', $penyesuaian_stok_id);
			# code...
		}
		if ($stok_opname_id != '') {
			$this->common_model->db_delete('nd_stok_opname_detail','stok_opname_id', $stok_opname_id);
			$this->common_model->db_delete('nd_stok_opname','id', $stok_opname_id);
		}
		echo 'OK';
	}

//==========================================po request report===========================================

	function po_request_report(){
		$menu = is_get_url($this->uri->segment(1)) ;
		$barang_cond = '';
		$warna_cond = '';
		$barang_id = '';
		$warna_id = '';
		$gudang_id = '';
		$tipe = 1;
		if ($this->input->get('tipe') && $this->input->get('tipe') != '') {
			$tipe = $this->input->get('tipe');
		}
		
		if($this->input->get('barang_id') != '' ){
			$barang_id = $this->input->get('barang_id');
			$barang_cond = "AND barang_id = ".$barang_id;
		}
		if($this->input->get('warna_id') != '' ){
			$warna_id = $this->input->get('warna_id');
			$warna_cond = "AND warna_id = ".$warna_id;
		}
		
		$data = array(
			'content' =>'admin/report/po_request_report',
			'breadcrumb_title' => 'Laporan',
			'breadcrumb_small' => 'Laporan PO Request',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data' => $this->data,
			'barang_id' => $barang_id,
			'warna_id' => $warna_id,
			'tipe' => $tipe
		);

		

		if ($tipe == 1) {
			$req = $this->rpt_model->get_po_request_report();
		}else{
			$req = $this->rpt_model->get_po_request_report_by_input();
		}
		$data['request_list'] = $req;
		$this->load->view('admin/template',$data);
	}

	function po_request_report_detail(){
		$menu = is_get_url($this->uri->segment(1)) ;
		$request_barang_id = $this->input->get('id');
		$tipe = 1;

		if ($this->input->get('tipe') && $this->input->get('tipe') != '') {
			$tipe = $this->input->get('tipe');
		}
		
		$data = array(
			// 'content' =>'admin/report/po_request_report_detail',
			'content' =>'admin/report/request_barang_report_detail',
			'breadcrumb_title' => 'Laporan',
			'breadcrumb_small' => 'Laporan PO Request',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data' => $this->data,
			'request_barang_id' => $request_barang_id,
			'tipe' => $tipe
			);


		$batch_list = $this->common_model->db_select('nd_request_barang_batch where request_barang_id='.$request_barang_id." ORDER BY tanggal ASC");
		$data['batch_list'] = $batch_list;
		$data['request_data'] = $this->rpt_model->get_request_barang_data($request_barang_id);
		// $data['request_data'] = $this->rpt_model->get_request_barang_data_2($request_barang_id);
		$closed_date = date('Y-m-d H:i:s');
		foreach ($data['request_data'] as $row) {
			if ($row->closed_date != '') {
				$closed_date = $row->closed_date;
			}
		}

		//get semua tanggal batch dibuat
		foreach ($batch_list as $row) {
			$tgl_batch[$row->id] = $row->tanggal;
			$no_batch_list[$row->id] = $row->batch;
		}

		//udah gt dapatkan semua bulan request per batch
		$get_request = $this->rpt_model->get_bulan_request_by_batch($request_barang_id);
		$data['get_datang'] = array();

		foreach ($get_request as $row) {
			$bulan_request = explode(',', $row->bln_request);
			for ($i=0; $i < count($bulan_request) ; $i++) { 
				if (!isset($data['get_datang'][$bulan_request[$i]])) {
					$data['get_datang'][$bulan_request[$i]] = array();
				}
				$tgl_start = date('Y-m-01', strtotime($bulan_request[$i]));
				$tgl_end = date('Y-m-t', strtotime($bulan_request[$i]));
				if ($i==0) {
					if (strtotime($bulan_request[$i]) > strtotime($tgl_batch[$row->request_barang_batch_id])   ) {
						$tgl_start = $tgl_batch[$row->request_barang_batch_id];
					}
				}
				// echo $row->request_barang_batch_id, $bulan_request[$i], $tgl_start, $tgl_end, $closed_date;
				// print_r($data['get_datang']);
				// echo '<hr/>';
				array_push($data['get_datang'][$bulan_request[$i]], $this->rpt_model->get_po_request_datang_data($row->request_barang_batch_id, $bulan_request[$i], $tgl_start, $tgl_end, $closed_date));
			}
		}
		// $data['request_barang_list'] = $this->rpt_model->get_po_request_report_detail($request_barang_id);

		if ($tipe == 1) {
			$req = $this->rpt_model->get_po_request_report_detail_2($request_barang_id);
		}else{
			$req = $this->rpt_model->get_po_request_report_detail_by_input_2($request_barang_id);
		}
		$data['request_list'] = $req;

		// $data['get_request_bulan']  = $this->rpt_model->get_all_bulan_request($request_barang_id);
		if (is_posisi_id()!=1) {
			# code...
			$this->load->view('admin/template',$data);
		}else{
			$this->load->view('admin/template',$data);
			
		}
	}

	function po_request_list_export_excel(){

		$this->load->library('Excel/PHPExcel');

		ini_set("memory_limit", "600M");

		$data['request_barang_id'] = $this->input->post('request_barang_id');
		$data['data_warna'] = $this->input->post('data');
		$data['data_barang'] = $this->input->post('data_barang');
		$data['no_request_lengkap'] = $this->input->post('no_request_lengkap');
		$data['bulan_awal'] = $this->input->post('bulan_awal');
		$this->load->view('admin/report/report_po_request_excel_2', $data);
	}

//=======================================laporan PO Pembelian=============================

	function po_penjualan_report(){
		$menu = is_get_url($this->uri->segment(1)) ;
		$cond = '';
		$cond_customer = '';
		
		$barang_id = '';
		$warna_id = '';
		$customer_id = '';

		$cond ='';
		if ($this->input->get('barang_id') && $this->input->get('barang_id') != '') {
			$barang_id = $this->input->get('barang_id');
			$cond = "WHERE barang_id = $barang_id";
		}

		if ($this->input->get('warna_id') && $this->input->get('warna_id') != '') {
			$warna_id = $this->input->get('warna_id');
			$cond = ($cond !='' ? $cond." AND warna_id = $warna_id" : "WHERE warna_id = $warna_id" );
		}

		if ($this->input->get('customer_id') && $this->input->get('customer_id') != '') {
			$customer_id = $this->input->get('customer_id');
			$cond_customer = "WHERE customer_id = '$customer_id'";
		}
		
		$data = array(
			'content' =>'admin/report/po_penjualan_report',
			'breadcrumb_title' => 'Laporan',
			'breadcrumb_small' => 'Laporan Po Penjualan',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data' => $this->data,
			'customer_id' => $customer_id,
			'barang_id' => $barang_id,
			'warna_id' => $warna_id,
			'po_penjualan_report' => $this->rpt_model->po_penjualan_report($cond, $cond_customer)
			);

			$this->load->view('admin/template',$data);
			if (is_posisi_id()==1) {
				// $this->output->enable_profiler(TRUE);
			}
	}

	function po_penjualan_report_detail(){
		$menu = is_get_url($this->uri->segment(1)) ;
		$po_penjualan_id = $this->input->get('id');
		$data_header = $this->rpt_model->get_po_penjualan_header($po_penjualan_id);
		$po_number = '';
		foreach ($data_header as $row) {
			$po_number = $row->po_number;
		}
		
		$data = array(
			'content' =>'admin/report/po_penjualan_report_detail',
			'breadcrumb_title' => 'Laporan',
			'breadcrumb_small' => 'Laporan Po Penjualan ['.$po_number.']',
			'nama_menu' => $menu[0],
			'po_penjualan_id' => $po_penjualan_id,
			'nama_submenu' => $menu[1],
			'common_data' => $this->data,
			'po_penjualan_invoice' => $this->rpt_model->po_penjualan_invoices($po_penjualan_id),
			'po_penjualan_retur' => $this->rpt_model->po_penjualan_retur($po_penjualan_id),
			'data_header' => $data_header,
			'data_barang' => $this->rpt_model->get_po_penjualan_barang($po_penjualan_id)
			);

		$this->load->view('admin/template',$data);
	}

	function po_penjualan_close_perbarang(){
		$id = $this->input->get('id');
		$po_penjualan_id = $this->input->get('po_penjualan_id');
		$status = $this->input->get('status');
		$data = array(
			'closed_date' => ($status == 1 ? date('Y-m-d H:i:s') : null),
			'closed_by' => ($status == 1 ? is_user_id() : null)
		);
		$this->common_model->db_update("nd_po_penjualan_detail", $data,'id', $id);
		echo json_encode("OK");
	}

	function po_penjualan_close(){
		$po_penjualan_id = $this->input->get('po_penjualan_id');
		$ids = $this->input->get('ids');
		$id = explode("-", $ids);
		$data = array();
		// $status_po = $this->input->get('status_po');
		foreach($id as $key => $value){
			array_push($data,array(
				'id'=> $value,
				'closed_date' => date('Y-m-d H:i:s'),
				'closed_by' => is_user_id()));
		}
		
		$this->common_model->db_update_batch("nd_po_penjualan_detail", $data, "id");
		// $arrCond = array(
		// 	'po_penjualan_id' => $po_penjualan_id ,
		// 	'closed_date' => null
		// );
		// $this->common_model->db_update_multiple_cond("nd_po_penjualan_detail", $data, $arrCond);
		echo json_encode("OK");
	}
}