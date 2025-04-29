<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Inventory extends CI_Controller {

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

	function update_gudang_visible(){
		$gudang_id = $this->input->post('gudang_id');
		$dt = $this->common_model->db_select('nd_gudang where id ='.$gudang_id);
		foreach ($dt as $row) {
			$visible = abs($row->visible - 1);
		}

		$data = array(
			'visible' => $visible );
		$dt = $this->common_model->db_update("nd_gudang", $data,'id',$gudang_id);
		echo "OK";
	}


//======================================stok ppo===============================================

	function stok_barang_ppo(){
        $menu = is_get_url($this->uri->segment(1)) ;
        $tanggal = date('Y-m-d');
        $barang_id = '';
        $count_po = '';
        if ($this->input->get('barang_id') ) {
        	$barang_id = $this->input->get('barang_id');
        }
        // if ($this->input->get('count_po') && $this->input->get('count_po') != '' && $this->input->get('count_po') != 0 ) {
        // 	$count_po = $this->input->get('count_po');
        // }
        $cond_barang = '';
        $data = array(
            'content' =>'admin/inventory/stok_barang_ppo',
            'breadcrumb_title' => 'Inventory',
            'breadcrumb_small' => 'Daftar Barang PPO ',
            'nama_menu' => $menu[0],
            'nama_submenu' => $menu[1],
            'common_data'=> $this->data,
            'barang_id' => $barang_id,
            'count_po' => $count_po,
            'data_isi'=> $this->data );

        $tanggal_awal = '2018-01-01';
        $stok_opname_id = 0;
        $getOpname = $this->common_model->db_select("nd_stok_opname where tanggal <= '".$tanggal."' order by tanggal desc limit 1");
        foreach ($getOpname as $row) {
            $tanggal_awal = $row->tanggal;
            $stok_opname_id = $row->id;
        }

    	$batch_id_list = array();
        
        if ($barang_id != '') {
        	$cond_barang = "WHERE barang_id = ".$barang_id;
	        $data['data_set'] = $this->inv_model->get_stok_ppo($barang_id, $tanggal, $tanggal_awal, $stok_opname_id, $cond_barang, ($ppo_lock_id != ''? $ppo_lock_id : 0 ));
	    	$data['batch_for_pre_po'] = $this->inv_model->get_batch_for_ppo($barang_id);

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
			}else{
				$data['list_stok'] = array();
			}
        }else{
	        $data['data_set'] = array();
        	$data['batch_for_pre_po'] = array();
        	$data['stok_awal'] = array();
        	$data['jual'] = array();
        	$data['list_stok'] = array();
        }



        $this->load->view('admin/template',$data);
		// $this->output->enable_profiler(TRUE);   
    }

    function stok_barang_ppo_2(){
        $menu = is_get_url($this->uri->segment(1)) ;
        $tanggal = date('Y-m-d');
        $barang_id = '';
        $count_po = '';
        $ppo_lock_id = '';

        //================================================================
	        if ($this->input->get('ppo_lock_id') && $this->input->get('ppo_lock_id') != '') {
	        	$ppo_lock_id = $this->input->get('ppo_lock_id');
	        	$get_ppo_data = $this->common_model->db_select("nd_ppo_lock where id=".$ppo_lock_id);
	        	foreach ($get_ppo_data as $row) {
	        		$barang_id = $row->barang_id;
	        		$tanggal = $row->tanggal;
	        	}
	        }else{
	        	$get_ppo_data = array();
	        }

	        if ($this->input->get('barang_id') && $ppo_lock_id == '' ) {
	        	$barang_id = $this->input->get('barang_id');
	        }

	        if ($this->input->get('tanggal') && $this->input->get('tanggal') != '' ) {
	        	$tanggal = is_date_formatter($this->input->get('tanggal'));
	        }
	        
	        $data = array(
	            'content' =>'admin/inventory/'.(is_posisi_id()==1?'stok_barang_ppo_3':'stok_barang_ppo_3'),
	            'breadcrumb_title' => 'Inventory',
	            'breadcrumb_small' => 'Daftar Barang PPO ',
	            'nama_menu' => $menu[0],
	            'nama_submenu' => $menu[1],
	            'common_data'=> $this->data,
	            'barang_id' => $barang_id,
	            'count_po' => $count_po,
	            'tanggal' => $tanggal,
	            'barang_id' => $barang_id,
	            'data_isi'=> $this->data,
	            'ppo_lock_id' => $ppo_lock_id,
	            'ppo_lock_data' => $get_ppo_data
	             );
	        $tanggal_awal = '2020-01-01';
	        $stok_opname_id = 0;
	        $getOpname = $this->common_model->db_select("nd_stok_opname where tanggal <= '".$tanggal."' order by tanggal desc limit 1");
	        foreach ($getOpname as $row) {
	            $tanggal_awal = $row->tanggal;
	            $stok_opname_id = $row->id;
	        }
        //================================================================

    	$batch_id_list = array();
        if ($barang_id != '') {
        	$cond_barang = "WHERE barang_id = ".$barang_id;
	        $data['data_set'] = $this->inv_model->get_stok_ppo($barang_id, $tanggal, $tanggal_awal, $stok_opname_id, $cond_barang, ($ppo_lock_id != ''? $ppo_lock_id : 0 ));
	    	$data['batch_for_pre_po'] = $this->inv_model->get_batch_for_ppo($barang_id, $tanggal);

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
	        $data['ppo_lock_list'] = $this->common_model->db_select("nd_ppo_lock where barang_id=".$barang_id);
        	if ($ppo_lock_id != '') {
	        	$data['po_pembelian_untuk_ppo'] = $this->inv_model->po_pembelian_untuk_ppo($barang_id, $ppo_lock_id);
        	}else{
	        	$data['po_pembelian_untuk_ppo'] = array();
        	}
        }else{
	        $data['data_set'] = array();
        	$data['batch_for_pre_po'] = array();
        	$data['stok_awal'] = array();
        	$data['jual'] = array();
        	$data['list_stok'] = array();
        	$data['list_stok_by_tanggal'] = array();
        	$data['ppo_lock_list'] = array();
        	$data['po_pembelian_untuk_ppo'] = array();
        }

        // if (is_posisi_id() == 1) {
        // 	$data['content'] = "admin/inventory/stok_barang_ppo_3";
        // }
        // echo $barang_id, $tanggal, $tanggal_awal, $stok_opname_id, $cond_barang, ($ppo_lock_id != ''? $ppo_lock_id : 0 );
        $this->load->view('admin/template',$data);
		// $this->output->enable_profiler(TRUE);   
    }

    function ppo_request_open(){
		$id = $this->input->post('ppo_lock_id');
		$data = array(
			'status' => 1 );
		$this->common_model->db_update('nd_ppo_lock',$data,'id',$id);
		redirect(is_setting_link('inventory/stok_barang_ppo_2').'/?ppo_lock_id='.$id);
	}

	function lock_ppo_lock(){
		$id = $this->input->get('ppo_lock_id');
		$data = array(
			'status' => $this->input->get('status') );
		$this->common_model->db_update('nd_ppo_lock',$data,'id',$id);
		redirect(is_setting_link('inventory/stok_barang_ppo_2').'/?ppo_lock_id='.$id);
	}

    function ppo_add_warna(){
    	$barang_id = $this->input->post('barang_id');
    	$warna_id = $this->input->post('warna_id');
    	$data = array(
    		'barang_id' => $barang_id ,
    		'warna_id' => $warna_id,
    		'qty' => 0,
    		'user_id' => is_user_id() );

    	$this->common_model->db_insert('nd_ppo_qty_current', $data);
    	redirect(is_setting_link('inventory/stok_barang_ppo_2')."?barang_id=".$barang_id);
    }

    function ppo_remove_warna(){
    	$barang_id = $this->input->get('barang_id');
    	$warna_id = $this->input->get('warna_id');
    	$this->common_model->db_free_query_superadmin("DELETE FROM nd_ppo_qty_current WHERE barang_id=$barang_id AND warna_id=$warna_id");
    	redirect(is_setting_link('inventory/stok_barang_ppo_2')."?barang_id=".$barang_id);
    }

    function ppo_update_qty_current(){
    	$barang_id = $this->input->post('barang_id');
    	$warna_id = $this->input->post('warna_id');
    	$ppo_lock_id = $this->input->post('ppo_lock_id');
    	$id = '';
    	if ($ppo_lock_id=='') {
	    	$data = array(
	    		'barang_id' => $barang_id ,
	    		'warna_id' => $this->input->post('warna_id'),
	    		'qty' => $this->input->post('qty'),
	    		'user_id' => is_user_id() );

	    	$get = $this->common_model->db_select('nd_ppo_qty_current where barang_id ='.$barang_id." AND warna_id=".$warna_id);
	    	foreach ($get as $row) {
	    		$id = $row->id;
	    	}

	    	if ($id == '') {
		    	$this->common_model->db_insert('nd_ppo_qty_current', $data);
	    		# code...
	    	}else{
		    	$this->common_model->db_update('nd_ppo_qty_current', $data,'id',$id);
	    	}
    	}else{
    		$data = array(
    			'ppo_lock_id' => $ppo_lock_id,
	    		'warna_id' => $this->input->post('warna_id'),
	    		'qty' => $this->input->post('qty'),
	    		'user_id' => is_user_id() );

	    	$get = $this->common_model->db_select('nd_ppo_lock_detail where ppo_lock_id ='.$ppo_lock_id." AND warna_id=".$warna_id);
	    	foreach ($get as $row) {
	    		$id = $row->id;
	    	}
	    	if ($id == '') {
		    	$this->common_model->db_insert('nd_ppo_lock_detail', $data);
	    		# code...
	    	}else{
		    	$this->common_model->db_update('nd_ppo_lock_detail', $data,'id',$id);
	    	}
    	}
    	echo "OK";
    }

    function ppo_change_table_setting(){
    	$kolom = $this->input->post('column');
    	$value = $this->input->post('value');
    	$barang_id = $this->input->post('barang_id');
    	$batch_id = $this->input->post('batch_id');

    	$id = '';
    	$get = $this->common_model->db_select('nd_ppo_table_setting where po_pembelian_batch_id ='.$batch_id);
    	foreach ($get as $row) {
    		$id=$row->id;
    	}

    	$data = array(
    		'po_pembelian_batch_id' => $batch_id,
    		'user_id' => is_user_id(),
    		'barang_id' => $barang_id,
    		$kolom => $value 
    		);

    	if ($id == '') {
    		$this->common_model->db_insert('nd_ppo_table_setting', $data);
    	}else{
    		$this->common_model->db_update('nd_ppo_table_setting', $data,'id', $id);
    	}

    	echo 'OK';
    }

    function ppo_lock_insert(){
    	$barang_id = $this->input->post('barang_id');
    	$data = array(
    		'tanggal' => $this->input->post('tanggal') ,
    		'barang_id' => $barang_id,
    		'po_pembelian_batch_id_aktif' => $this->input->post('po_pembelian_batch_id_aktif'),
    		'locked_by' => is_user_id() );
    	$result_id = $this->common_model->db_insert("nd_ppo_lock", $data);
    	$id = $result_id;

    	$get = $this->common_model->db_select("nd_ppo_qty_current where barang_id=".$barang_id);
    	foreach ($get as $row) {
    		$data_detail[$row->id] = array(
    			'ppo_lock_id' => $id, 
    			'warna_id' => $row->warna_id,
    			'qty' => $row->qty,
    			'user_id' => is_user_id());
    	}

    	$data_update = array(
    		'qty' => 0 );

    	$this->common_model->db_insert_batch('nd_ppo_lock_detail', $data_detail);
    	$this->common_model->db_update('nd_ppo_qty_current',$data_update,'barang_id', $barang_id);

    	// redirect("inventory/ppo_lock_download_excel?ppo_lock_id=".$result_id);
    	redirect(is_setting_link('inventory/stok_barang_ppo_2')."?ppo_lock_id=".$result_id);

    }

    function ppo_lock_download_excel()
    {
    	$ppo_lock_id = $this->input->get('ppo_lock_id');
    	$ppo_lock_data = $this->common_model->db_select("nd_ppo_lock where id=".$ppo_lock_id);

    	foreach ($ppo_lock_data as $row) {
    		$barang_id = $row->barang_id;
    		$tanggal = $row->tanggal;
    	}
        
        $ppo_lock_list = $this->common_model->db_select("nd_ppo_lock where barang_id=".$barang_id);
        $data_barang = $this->common_model->db_select("nd_barang where id=".$barang_id);

    	$tanggal_awal = '2018-01-01';
        $stok_opname_id = 0;
        $getOpname = $this->common_model->db_select("nd_stok_opname where tanggal <= '".$tanggal."' order by tanggal desc limit 1");
        foreach ($getOpname as $row) {
            $tanggal_awal = $row->tanggal;
            $stok_opname_id = $row->id;
        }

        $cond_barang = "WHERE barang_id = ".$barang_id;
        $data_set = $this->inv_model->get_stok_ppo($barang_id, $tanggal, $tanggal_awal, $stok_opname_id, $cond_barang, ($ppo_lock_id != ''? $ppo_lock_id : 0 ));
    	$batch_for_pre_po = $this->inv_model->get_batch_for_ppo($barang_id, $tanggal);

    	$batch_id_list = array();
    	foreach ($data_set as $row) {
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
	    	$stok_awal = $this->inv_model->get_penyesuaian_stok_awal($barang_id);
	    }else{
	    	$stok_awal = $this->inv_model->get_stok_by_opname($stok_opname_id, $barang_id);
	    }
		$list_jual = $this->inv_model->get_penjualan_by_barang($barang_id, $tanggal_awal, $tanggal);
		foreach ($list_jual as $row) {
			$jual[$row->warna_id][$row->penjualan_id] = $row;
		}
		if (count($batch_id_list) > 0) {
			$list_stok = $this->inv_model->stok_by_po(implode(',', $batch_id_list), $barang_id, $tanggal);
			$list_stok_by_tanggal = $this->inv_model->stok_by_po_by_tanggal(implode(',', $batch_id_list), $barang_id, $tanggal);
		}else{
			$list_stok = array();
			$list_stok_by_tanggal = array();
		}
    
    	//================================olah data initial======================================

		foreach ($stok_awal as $row) {
			$sa[$row->warna_id] = $row->qty;
		}

		foreach ($list_stok_by_tanggal as $row) {
			$stok_bt[$row->warna_id][$row->tanggal] = (isset($stok_bt[$row->warna_id][$row->tanggal]) ? $stok_bt[$row->warna_id][$row->tanggal].'=??='.$row->qty.'??'.$row->po_pembelian_batch_id : $row->qty.'??'.$row->po_pembelian_batch_id );
			$stok_po[$row->warna_id][$row->po_pembelian_batch_id] = 0;
		}

		foreach ($list_stok as $row) {
			$stok[$row->warna_id][$row->po_pembelian_batch_id] = $row;
			$stok_bm[$row->warna_id][$row->po_pembelian_batch_id] = $row->qty;
			$bm_all[$row->warna_id] = (isset($bm_all[$row->warna_id]) ? $bm_all[$row->warna_id] + $row->qty : $row->qty); 
			$bm_first[$row->warna_id][$row->po_pembelian_batch_id] = $row->tanggal_first;
		}

		$deleted = array();
		foreach ($data_set as $val) {
			$bm_first_warna[$val->warna_id] = array();
			$jual_last_warna[$val->warna_id] = array();
			$isListed[$val->warna_id] = 1;
			$stok_now[$val->warna_id] = (isset($sa[$val->warna_id]) ? $sa[$val->warna_id] : 0);

			$jual_latest[$val->warna_id] = array();
			$terjual_sa[$val->warna_id] = 0;
			// $jual_qty_latest_sisa[$val->warna_id] = 0;
			// $jual_qty_latest_qty[$val->warna_id] = '';
			$latest = 0;
			// echo $val->warna_id.' = '.(isset($jual[$val->warna_id]) ? count($jual[$val->warna_id]) : 0)."<br/>";
			if (isset($jual[$val->warna_id])) {
				foreach ($jual[$val->warna_id] as $key => $value) {
					//set milestone sebelum dikurangin
					$qty_now = $stok_now[$val->warna_id];
					//stok awal terus dikurangin qty jual
					$stok_now[$val->warna_id] -= $value->qty;
					/*if ($val->warna_id == 30) {
						echo $stok_now[$val->warna_id].' '.$jual[$val->warna_id][$key]->tanggal.'<br/>';
						echo 'Terjual : '.$terjual_sa[$val->warna_id].'<hr/>';
					}*/
					// klo stok nya masih di atas 0 
					if ($stok_now[$val->warna_id] > 0) {
						// echo $key.'--';
						//data penjualan terakhir
						$jual_latest[$val->warna_id] = $value;
						//data qty(total) di penjualan terakhir
						$jual_qty[$val->warna_id] = $value->qty; 
						array_push($deleted, $key);
						unset($jual[$val->warna_id][$key]);
					}else if($latest == 0){
						//data penjualan terakhir waktu pas stok awal 0
						//sisa qty penjualan di pas stok awal 0
						$jual_qty_latest_sisa[$val->warna_id][$key] = $value->qty - $qty_now;
						//qty as penjualan pad di stok 0
						$jual_qty_latest_qty[$val->warna_id][$key] = $value->qty;
						//data penjualan pas stok awal habis, untuk dipake stok awal data
						$latest_data_jual[$val->warna_id][$key] = $value;
						$latest++;
					}
				}
			}
		}

		$color_idx = ['bae1ff','baffc9','ffffba','ffdfba','ffb3ba'];
		$color_lock_idx = ['a9cde8','a8e6b5','ebebab','e8cba9','e8a5ab'];

    	//======================================start php excel==================================
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

		$styleArrayBorder = array(
            'allborders' => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN,
                'color' => array(
                    'rgb' => '000000'
                )
            )
        );

		$styleArrayLockLR = array(
            'left' => array(
                'style' => PHPExcel_Style_Border::BORDER_DASHED,
                'color' => array(
                    'rgb' => '800000'
                )
            ),'right' => array(
                'style' => PHPExcel_Style_Border::BORDER_DASHED,
                'color' => array(
                    'rgb' => '800000'
                )
            )
        );

        $styleArrayLockTop = array(
            'top' => array(
                'style' => PHPExcel_Style_Border::BORDER_DASHED,
                'color' => array(
                    'rgb' => '800000'
                )
            )
        );

        $styleArrayLockBottom = array(
            'bottom' => array(
                'style' => PHPExcel_Style_Border::BORDER_DASHED,
                'color' => array(
                    'rgb' => '800000'
                )
            )
        );


		foreach ($data_barang as $row) {
        	$nama_barang = $row->nama;
        }

		$objPHPExcel->getActiveSheet()->setCellValue('A1', $nama_barang);
		$objPHPExcel->getActiveSheet()->setCellValue('A2', 'Tanggal : '.is_reverse_date($tanggal));

		
		$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('A4', 'No')
		->setCellValue('B4', 'Warna')
		->setCellValue('C4', 'Stok')
		->setCellValue('D4', 'Roll')
		->setCellValue('E4', 'Data PO')
		;

		$coll_header = "E";
		foreach ($batch_for_pre_po as $row) {

			if ($row->status_include == 1) {
				$status_include[$row->po_pembelian_batch_id] = true;
			}else{
				$status_include[$row->po_pembelian_batch_id] = false;
			}
		}

		foreach ($ppo_lock_data as $row) {
			foreach ($status_include as $key => $value) {
				$status_include[$key] = false;
			}
			$po_pembelian_batch_id_aktif = explode(',', $row->po_pembelian_batch_id_aktif);
			foreach ($po_pembelian_batch_id_aktif as $key => $value) {
				$status_include[$value] = true;
			}
		}

		foreach ($batch_for_pre_po as $row) {
			if ($status_include[$row->po_pembelian_batch_id]) {
				$objPHPExcel->getActiveSheet()->setCellValue($coll_header.'5', $row->batch );
				$coll_header_end = $coll_header;
				$coll_header++;
			}
		}

		$objPHPExcel->getActiveSheet()->mergeCells("A4:A5");
		$objPHPExcel->getActiveSheet()->mergeCells("B4:B5");
		$objPHPExcel->getActiveSheet()->mergeCells("C4:C5");
		$objPHPExcel->getActiveSheet()->mergeCells("D4:D5");
		
		$objPHPExcel->getActiveSheet()->mergeCells("E4:".$coll_header_end."4");

		$objPHPExcel->getActiveSheet()->setCellValue($coll_header.'4', "REKAP" );
		$objPHPExcel->getActiveSheet()->mergeCells($coll_header."4:".$coll_header."5");
		$coll_header++;

		$objPHPExcel->getActiveSheet()->setCellValue($coll_header.'4', "STOK + OUTSTANDING" );
		$objPHPExcel->getActiveSheet()->mergeCells($coll_header."4:".$coll_header."5");
		$coll_header++;

		$objPHPExcel->getActiveSheet()->setCellValue($coll_header.'4', "PPO" );
		$objPHPExcel->getActiveSheet()->mergeCells($coll_header."4:".$coll_header."5");
		$coll_header++;
		
		$objPHPExcel->getActiveSheet()->setCellValue($coll_header.'4', "TOTAL" );
		$objPHPExcel->getActiveSheet()->mergeCells($coll_header."4:".$coll_header."5");
		
		$objPHPExcel->getActiveSheet()->getStyle('A4:'.$coll_header.'5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);


		$idx = 1; $total_stok = 0; $total_po = 0; $total_input_ppo=0;
		$g_total_qty = 0; $total_roll = 0; $total_qty_now = 0; $baris_lock=array();
		$row_no = 6;$baris_idx = 0;
		foreach ($data_set as $row) {
			$batch_id = explode(',', $row->batch_id);
			$qty_beli = explode(',', $row->qty_beli);
			$batch_set = array_combine($batch_id, $qty_beli);
				
			$qty_sisa = explode(',', $row->qty_sisa_data);
			$qty_sisa_data = array_combine($batch_id, $qty_sisa);
			$qty_beli = explode(',', $row->qty_beli);
			$qty_beli_data = array_combine($batch_id, $qty_beli);
			
			$locked_by = explode(',', $row->locked_by);
			$locked_data = array_combine($batch_id, $locked_by);

			$qty_po_data = explode(',', $row->qty_po_data);
			$qty_po_data = array_combine($batch_id, $qty_po_data);

			$harga_po = explode(',', $row->harga_po);
			$harga_po = array_combine($batch_id, $harga_po);

			$OCKH = explode(',', $row->OCKH);
			$OCKH = array_combine($batch_id, $OCKH);

			$info = "";

			$total_stok += $row->qty_stok;
			$total_roll += $row->jumlah_roll_stok;
			$total_po += $row->qty_sisa;
			$qty_now = 0;
			$total_bm[$row->warna_id] = 0;
			$total_jual[$row->warna_id] = 0;
			$total_trx[$row->warna_id] = 0;
			$total_lt_warna[$row->warna_id] = 0;
			$terjual_all[$row->warna_id] = 0;
			$terjual_show[$row->warna_id] = 0;
			$trx_show[$row->warna_id] = 0;
			$total_bm_show[$row->warna_id] = 0;
			$sisa_belum_datang_total = 0;
			$total_po_qty = 0;
			$jarak_total_all=0;
			$total_input_ppo += $row->qty_ppo;

			if (isset($stok_bt[$row->warna_id])) {
				foreach ($stok_bt[$row->warna_id] as $key => $value) {
					$break = explode('=??=', $value);

					foreach ($break as $keys => $values) {
						$data_break = explode('??', $values);
						$stok_now = $data_break[0];
						$tanggal_awal = $key;
						if (isset($jual[$row->warna_id])) {
							foreach ($jual[$row->warna_id] as $key2 => $value2) {
								$count_jual[$row->warna_id][$data_break[1]] = (isset($count_jual[$row->warna_id][$data_break[1]]) ? $count_jual[$row->warna_id][$data_break[1]] + 1 : 1 );
								if (!isset($jual_now[$row->warna_id][$value2->penjualan_id])) {
									$qty_j = $value2->qty;
								}else{
									$qty_j = $jual_now[$row->warna_id][$value2->penjualan_id];
								}
								if ($stok_now - $qty_j < 0) {
									$jual_now[$row->warna_id][$value2->penjualan_id] = $qty_j - $stok_now; 
									$terjual[$row->warna_id][$data_break[1]] = (isset($terjual[$row->warna_id][$data_break[1]]) ? $terjual[$row->warna_id][$data_break[1]] + $stok_now : $stok_now  );
									$last_tgl[$row->warna_id][$data_break[1]] = $value2->tanggal;
									$lead_time[$row->warna_id][$data_break[1]] = 0;
									$stok_tgl[$row->warna_id][$data_break[1]][$key] = 0;
									$terjual_all[$row->warna_id] += $stok_now;
									$last_jual[$row->warna_id][$data_break[1]][$key] = $value2->tanggal;
									$last_jual_po[$row->warna_id][$data_break[1]] = $value2->tanggal;
									break;
								}else{
									$stok_now -= $qty_j;
									$terjual_all[$row->warna_id] += $qty_j;
									$terjual[$row->warna_id][$data_break[1]] = (isset($terjual[$row->warna_id][$data_break[1]]) ? $terjual[$row->warna_id][$data_break[1]] + $qty_j  : $qty_j ).'<br/>';
									unset($jual[$row->warna_id][$key2]);
									$stok_tgl[$row->warna_id][$data_break[1]][$key] = $stok_now;
									$last_jual[$row->warna_id][$data_break[1]][$key] = $value2->tanggal;
									$last_jual_po[$row->warna_id][$data_break[1]] = $value2->tanggal;
								}
								$tanggal_akhir = $value2->tanggal;
							}
						}
						if (!isset($stok_tgl[$row->warna_id][$data_break[1]][$key])) {
							$stok_tgl[$row->warna_id][$data_break[1]][$key] = $stok_now;
							$last_jual[$row->warna_id][$data_break[1]][$key] = '';
						}
					}
				}												
				$stok_all[$row->warna_id] = $bm_all[$row->warna_id] - $terjual_all[$row->warna_id];
			}

			//=====================================generate tiap cell===========================================
			$coll = "A";
			$row_no_next = '';

			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$idx);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(7);
			$coll++;

			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$row->nama_warna);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(14);
			$coll++;

			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$row->qty_stok);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(10);
			$coll++;

			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $row->jumlah_roll_stok);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(7);
			$coll++;

			//berarti seharus nya sekarang kolom E
			//====================================generate tiap po per warna==============================================
			$i=0; $row_no_next = '';
			foreach ($batch_for_pre_po as $row2) {
				$terjual[$row->warna_id][$row2->po_pembelian_batch_id] = (isset($terjual[$row->warna_id][$row2->po_pembelian_batch_id]) ? $terjual[$row->warna_id][$row2->po_pembelian_batch_id] : 0);
				// $bm_first[$row->warna_id][$row2->po_pembelian_batch_id] = '';
				$bm_last[$row->warna_id][$row2->po_pembelian_batch_id] = '';
				$last[$row->warna_id][$row2->po_pembelian_batch_id]='';
				$count_jual_by_customer[$row->warna_id][$row2->po_pembelian_batch_id]=0;
				$count_jual_by_non_customer[$row->warna_id][$row2->po_pembelian_batch_id]=0;
				unset($cust_idx);
				$total_lt[$row->warna_id][$row2->po_pembelian_batch_id] = 0;
				$total_lt_po[$row->warna_id][$row2->po_pembelian_batch_id] = 0;
				$jarak_jual = array();
				$jarak_total[$row2->po_pembelian_batch_id]=0;

				if ($status_include[$row2->po_pembelian_batch_id]) {
					$stok_bm_show = (isset($stok_bm[$row->warna_id][$row2->po_pembelian_batch_id]) ? $stok_bm[$row->warna_id][$row2->po_pembelian_batch_id] :  0);
					$terjual_now = (float)$terjual[$row->warna_id][$row2->po_pembelian_batch_id];
					$is_locked = (isset($locked_data[$row2->po_pembelian_batch_id]) && $locked_data[$row2->po_pembelian_batch_id] != 0 ? true : false);
					$sisa_belum_datang = (isset($qty_po_data[$row2->po_pembelian_batch_id]) ? $qty_po_data[$row2->po_pembelian_batch_id] : 0) - $stok_bm_show;
					$qty_now += (!$is_locked ? $sisa_belum_datang : 0);
					$sisa_belum_datang_total += (!$is_locked ? $sisa_belum_datang : 0);
					
					if (isset($qty_po_data[$row2->po_pembelian_batch_id])) {
						$full = $qty_po_data[$row2->po_pembelian_batch_id];
						$total_po_qty += $full;
						$bm_full = (isset($stok_bm[$row->warna_id][$row2->po_pembelian_batch_id]) ? $stok_bm[$row->warna_id][$row2->po_pembelian_batch_id] : 0);
						// =========================================================================
						$bm_persen = $bm_full/$full*100;
						$terjual_persen = $terjual_now/($bm_full != 0 ? $bm_full : 1)*100;
						$jual_persen_bar = $terjual_now/$full*100;
						// =========================================================================
						$terjual_show[$row->warna_id] += $terjual_now;
						$total_bm_show[$row->warna_id] += $bm_full;
						$count_jual_po = (isset($count_jual[$row->warna_id][$row2->po_pembelian_batch_id]) ? $count_jual[$row->warna_id][$row2->po_pembelian_batch_id] : 0);
						if (isset($stok_tgl[$row->warna_id][$row2->po_pembelian_batch_id])) {
							foreach ($stok_tgl[$row->warna_id][$row2->po_pembelian_batch_id] as $key => $value) {
								if ($terjual_now != 0) {
									$tgl_1 = date_create($key);
									if (isset($last_jual[$row->warna_id][$row2->po_pembelian_batch_id][$key]) && $last_jual[$row->warna_id][$row2->po_pembelian_batch_id][$key] != '') {
										$tgl_akhir_jual = date_create($last_jual[$row->warna_id][$row2->po_pembelian_batch_id][$key]);
										$jj = date_diff($tgl_1,$tgl_akhir_jual)->format('%a');
										// $jarak_total[$row2->po_pembelian_batch_id] += $jj;
										// $jarak_total_all += $jj;
										array_push($jarak_jual, $jj);
									}
								}
								$stok_po[$row->warna_id][$row2->po_pembelian_batch_id] += $value;
							}
							if (isset($last_jual_po[$row->warna_id][$row2->po_pembelian_batch_id]) && $last_jual_po[$row->warna_id][$row2->po_pembelian_batch_id] != '') {
								$tgl_1 = date_create($bm_first[$row->warna_id][$row2->po_pembelian_batch_id]);
								$tgl_2 = date_create($last_jual_po[$row->warna_id][$row2->po_pembelian_batch_id]);
								$jt = date_diff($tgl_1,$tgl_2)->format('%a')+1;
								$jarak_total[$row2->po_pembelian_batch_id] += $jt;
								array_push($bm_first_warna[$row->warna_id], $bm_first[$row->warna_id][$row2->po_pembelian_batch_id]);
								array_push($jual_last_warna[$row->warna_id], $last_jual_po[$row->warna_id][$row2->po_pembelian_batch_id]);
								echo $row->warna_id;
								
								$jarak_total_all += $jt;
							}
							//=============================================================================================
							foreach ($stok_tgl[$row->warna_id][$row2->po_pembelian_batch_id] as $key => $value) {
								$tgl_1 = date_create($key);
								$tgl_2 = date_create($tanggal);
								$intvl = date_diff($tgl_1,$tgl_2)->format('%a')+1;
								if ($value != 0) {
									$persen = $value/$stok_all[$row->warna_id]*100;
									$persen_po = $value/($stok_po[$row->warna_id][$row2->po_pembelian_batch_id] != 0 ? $stok_po[$row->warna_id][$row2->po_pembelian_batch_id] : 1) * 100;
									//=====================================================================
									$lt = $intvl*$persen/100;
									$lt_po = $intvl*$persen_po/100;
									//====================================================================
									$total_lt[$row->warna_id][$row2->po_pembelian_batch_id] += $lt;
									$total_lt_po[$row->warna_id][$row2->po_pembelian_batch_id] += $lt_po;
								}
							}
							$total_lt_warna[$row->warna_id] += $total_lt[$row->warna_id][$row2->po_pembelian_batch_id];
																		
							$t_rx = (isset($count_jual[$row->warna_id][$row2->po_pembelian_batch_id]) ? $count_jual[$row->warna_id][$row2->po_pembelian_batch_id] : 0);
							// $trx_show[$row->warna_id] += $t_rx;
							// $batch_masa_total[$row2->po_pembelian_batch_id] += $jarak_total[$row2->po_pembelian_batch_id];
							// $batch_trx_total[$row2->po_pembelian_batch_id] += $t_rx;
							// $batch_lt_total[$row2->po_pembelian_batch_id] += round($total_lt[$row->warna_id][$row2->po_pembelian_batch_id],2);
						}
						$t_rx = (isset($count_jual[$row->warna_id][$row2->po_pembelian_batch_id]) ? $count_jual[$row->warna_id][$row2->po_pembelian_batch_id] : 0);
						$trx_show[$row->warna_id] += $t_rx;
						//=============================================================================================
						$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(20);						
						$row_no_generated = $row_no;
						$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no_generated, ($sisa_belum_datang < 0 ? 0 : ($is_locked ? 0 : $sisa_belum_datang) ) );
						$row_no_generated++;
						$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no_generated, $full);
						$row_no_generated++;
						$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no_generated, $bm_full);
						$row_no_generated++;
						$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no_generated, $terjual_now.'/'.round($terjual_persen,2).'%');
						$row_no_generated++;
						$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no_generated, 'Dalam : '.$jarak_total[$row2->po_pembelian_batch_id].'hari');
						$row_no_generated++;
						$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no_generated, 'Trx : '.$t_rx);
						$row_no_generated++;
						$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no_generated, 'L.Time : '.round($total_lt_po[$row->warna_id][$row2->po_pembelian_batch_id],2));
						if ($is_locked) {
							$baris_lock[$i][$baris_idx] = $coll.$row_no.':'.$coll.$row_no_generated;
							// echo '<br/>'.$row->nama_warna.':'.$i.'||'.$baris_idx .'='. $coll.$row_no.':'.$coll.$row_no_generated;
							// $objPHPExcel->getActiveSheet()->getStyle($coll.$row_no.':'.$coll.$row_no_generated)->getBorders()->applyFromArray($styleArrayLockLR);	
							// $objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getBorders()->applyFromArray($styleArrayLockTop);	
							// $objPHPExcel->getActiveSheet()->getStyle($coll.$row_no_generated)->getBorders()->applyFromArray($styleArrayLockBottom);	
						}
						$row_no_generated++;
						$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no.':'.$coll.$row_no_generated)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						$row_no_next = $row_no_generated;

						//=========================start ke cell=======================
						$i++;
					}
					$coll++;
				}
			}

			$total_qty_now += $qty_now;
			if ($total_po_qty > 0 ) {
				
				$row_no_generated = $row_no;
				$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(20);
				$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no_generated, $sisa_belum_datang_total);
				
				$row_no_generated++;
				$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no_generated, $total_po_qty);
				$objPHPExcel->getActiveSheet()->getRowDimension($row_no_generated)->setVisible(false);
				
				$row_no_generated++;
				$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no_generated, $total_bm_show[$row->warna_id]);
				$objPHPExcel->getActiveSheet()->getRowDimension($row_no_generated)->setVisible(false);
				
				$terjual_p = $terjual_show[$row->warna_id] / ($total_bm_show[$row->warna_id] != 0 ? $total_bm_show[$row->warna_id] : 1);
				$row_no_generated++;
				$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no_generated, $terjual_show[$row->warna_id].'/'.round($terjual_p,2).'%' );
				$objPHPExcel->getActiveSheet()->getRowDimension($row_no_generated)->setVisible(false);
				
				
				if ($terjual_show[$row->warna_id] != 0) {
					$tgl1 = date_create(min($bm_first_warna[$row->warna_id]));
					$tgl2 = date_create(max($jual_last_warna[$row->warna_id]));
					$jarak_total_all = date_diff($tgl1,$tgl2)->format('%a')+1;
				}else{
					$jarak_total_all = 0;
				}
				$row_no_generated++;
				$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no_generated, 'Dalam : '.$jarak_total_all.' hari');
				$objPHPExcel->getActiveSheet()->getRowDimension($row_no_generated)->setVisible(false);
				
				$row_no_generated++;
				$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no_generated, 'Trx : '.$trx_show[$row->warna_id]);
				$objPHPExcel->getActiveSheet()->getRowDimension($row_no_generated)->setVisible(false);
				
				$row_no_generated++;
				$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no_generated, 'L.Time : '.round($total_lt_warna[$row->warna_id],2));
				$objPHPExcel->getActiveSheet()->getRowDimension($row_no_generated)->setVisible(false);
				
				$objPHPExcel->getActiveSheet()->mergeCells("A".$row_no.":A".$row_no_generated);
				$objPHPExcel->getActiveSheet()->mergeCells("B".$row_no.":B".$row_no_generated);
				$objPHPExcel->getActiveSheet()->mergeCells("C".$row_no.":C".$row_no_generated);
				$objPHPExcel->getActiveSheet()->mergeCells("D".$row_no.":D".$row_no_generated);

				$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no.':'.$coll.$row_no_generated)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

				$coll++;
			}else{
				$row_no_generated = $row_no;
				$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, 0);
				$objPHPExcel->getActiveSheet()->getStyle($coll)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(20);
				$coll++;
			}

			$objPHPExcel->getActiveSheet()->mergeCells($coll.$row_no.":".$coll.$row_no_generated);			
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $row->qty_stok+$sisa_belum_datang_total);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(20);
			$coll++;

			$objPHPExcel->getActiveSheet()->mergeCells($coll.$row_no.":".$coll.$row_no_generated);
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, ($row->qty_ppo == "" ? 0 : $row->qty_ppo) );
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(20);
			$coll++;

			$g_total_qty += $qty_now+$row->qty_ppo;
			$objPHPExcel->getActiveSheet()->mergeCells($coll.$row_no.":".$coll.$row_no_generated);
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $row->qty_stok+$qty_now+$row->qty_ppo);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(20);
			$last_coll =$coll;
			$coll++;

			$row_no = ($row_no_next != '' ? $row_no_next : $row_no+1);
			$row_no_end = $row_no;
			$idx++;
			$baris_idx++;
		}

		$objPHPExcel->getActiveSheet()->getStyle("A4:".$coll.$row_no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

		$coll_now = 'E'; $i=0;
		foreach ($batch_for_pre_po as $row) {
			if ($status_include[$row->po_pembelian_batch_id]) {
				$styleArrayBG = array(
					'fill' => array(
			            'type' => PHPExcel_Style_Fill::FILL_SOLID,
			            'color' => array('rgb' => $color_idx[$i])
		            )
				);

				$styleArrayBGLock = array(
					'fill' => array(
			            'type' => PHPExcel_Style_Fill::FILL_SOLID,
			            'color' => array('rgb' => $color_lock_idx[$i])
		            )
				);

				$objPHPExcel->getActiveSheet()->getStyle($coll_now.'5:'.$coll_now.$row_no_end)->applyFromArray($styleArrayBG);
				if (isset($baris_lock[$i])) {
					foreach ($baris_lock[$i] as $value) {
						$objPHPExcel->getActiveSheet()->getStyle($value)->applyFromArray($styleArrayBGLock);
					}
				}
				$i++; $coll_now++;
			}
		}

		$objPHPExcel->getActiveSheet()->getStyle('A4:'.$last_coll.$row_no)->getBorders()->applyFromArray($styleArrayBorder);	
		
		// print_r($baris_lock);

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		ob_end_clean();

		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header("Content-Disposition: attachment;filename=PPO_".str_replace(" ", "_", $nama_barang)."_".str_replace('/', '', is_reverse_date($tanggal)).".xls");
		header('Cache-Control: max-age=0');
		$objWriter->save('php://output');
    }

    function ppo_export_to_po(){
    	$po_pembelian_detail_id = $this->input->post('po_pembelian_detail_id');
    	$ppo_lock_id = $this->input->post('ppo_lock_id');
    	$get_po_data = $this->common_model->db_select("nd_po_pembelian_detail where id=".$po_pembelian_detail_id);

    	foreach ($get_po_data as $row) {
    		$po_pembelian_id = $row->po_pembelian_id;
    	}

    	$batch = 1;
        $get_batch = $this->common_model->db_select("nd_po_pembelian_batch where po_pembelian_id=".$po_pembelian_id." ORDER BY batch desc limit 1");
        foreach ($get_batch as $row) {
            $batch = $row->batch + 1;
        }
    	$data = array(
    		'po_pembelian_id' => $po_pembelian_id ,
    		'tanggal' => is_date_formatter($this->input->post('tanggal')),
            'batch' => $batch );

        $result_id = $this->common_model->db_insert('nd_po_pembelian_batch', $data);
        $batch_id = $result_id;

        $get_warna = $this->common_model->db_select("nd_ppo_lock_detail where ppo_lock_id=".$ppo_lock_id." AND qty > 0");
        $data_warna = array();
        foreach ($get_warna as $row) {
        	$data_batch = array(
        		'po_pembelian_detail_id' => $po_pembelian_detail_id,
	            'po_pembelian_batch_id' => $batch_id,
	            'tipe_barang' => 1,
	            'warna_id' => $row->warna_id,
	            'qty' => $row->qty
            );

            array_push($data_warna, $data_batch);
        }

        $this->common_model->db_insert_batch("nd_po_pembelian_warna", $data_warna);

        $data_store = array(
        	'ppo_lock_id' => $ppo_lock_id,
        	'po_pembelian_detail_id' => $po_pembelian_detail_id,
        	'po_pembelian_batch_id' => $result_id,
        	'user_id' => is_user_id() 
        	);

        $this->common_model->db_insert('nd_ppo_to_po', $data_store);
        redirect(is_setting_link('transaction/po_pembelian_detail_warna_batch').'?id='.$po_pembelian_id."&batch_id=".$result_id);

    }

	

//======================================stok barang============================================

	function stok_barang(){
		$menu = is_get_url($this->uri->segment(1)) ;
		if ($this->input->get('tanggal') && $this->input->get('tanggal') != '') {
			$tanggal = is_date_formatter($this->input->get('tanggal'));
		}else{
			$tanggal = date("Y-m-d");
		}
		$print_mode = 0;
		if ($this->input->get('print_mode')) {
			$print_mode = 1;
		}
		$data = array(
			'content' =>'admin/inventory/stok_barang',
			'breadcrumb_title' => 'Stok',
			'breadcrumb_small' => 'Barang',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data'=> $this->data,
			'tanggal' => is_reverse_date($tanggal),
			'print_mode' => $print_mode
			 );
		$tanggal_awal = '2018-01-01';
		$stok_opname_id = 0;
		$getOpname = $this->common_model->db_select("nd_stok_opname where tanggal <= '".$tanggal."' order by tanggal desc limit 1");
		foreach ($getOpname as $row) {
			$tanggal_awal = $row->tanggal;
			$stok_opname_id = $row->id;
		}
		$select = '';
		foreach ($this->gudang_list_aktif as $row) {
			$select .= ", SUM( if(gudang_id=".$row->id.", ifnull(qty_masuk,0), 0 ) ) - SUM( if(gudang_id=".$row->id.", ifnull(qty_keluar,0), 0 ) )  as ".$row->nama."_qty , SUM( if(gudang_id=".$row->id.", jumlah_roll_masuk, 0 ) ) - SUM( if(gudang_id=".$row->id.", jumlah_roll_keluar, 0 ) )  as ".$row->nama."_roll ";
		}
		// echo $select.'<br>';
		// echo $tanggal_awal;
		// $data['gudang_list'] = $this->common_model->db_select("nd_gudang where status_aktif = 1 ORDER BY id desc");
		$data['stok_barang'] = $this->inv_model->get_stok_barang_list($select, $tanggal, $tanggal_awal, $stok_opname_id); 
		$data['stok_opname_id'] = $stok_opname_id;
		// echo $data['stok_barang'];
		$this->load->view('admin/template',$data);
	}

	function stok_barang_ajax(){
		$menu = is_get_url($this->uri->segment(1)) ;

		$tanggal = date("Y-m-d");
		if ($this->input->get('tanggal') && $this->input->get('tanggal') != '') {
			$tanggal = is_date_formatter($this->input->get('tanggal'));
		}

		$print_mode = 0;

		if ($this->input->get('print_mode')) {
			$print_mode = 1;
		}

		$data = array(
			'content' =>'admin/inventory/stok_barang_ajax',
			'breadcrumb_title' => 'Stok',
			'breadcrumb_small' => 'Barang',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data'=> $this->data,
			'tanggal' => is_reverse_date($tanggal),
			'print_mode' => $print_mode
			 );

		$tanggal_awal = '2018-01-01';
		$stok_opname_id = 0;
		$getOpname = $this->common_model->db_select("nd_stok_opname where tanggal <= '".$tanggal."' order by tanggal desc limit 1");
		foreach ($getOpname as $row) {
			$tanggal_awal = $row->tanggal;
			$stok_opname_id = $row->id;
		}
		$select = '';
		foreach ($this->gudang_list_aktif as $row) {
			$select .= ", SUM( if(gudang_id=".$row->id.", ifnull(qty_masuk,0), 0 ) ) - SUM( if(gudang_id=".$row->id.", ifnull(qty_keluar,0), 0 ) )  as gudang_".$row->id."_qty , if(tipe_qty != 3,SUM( if(gudang_id=".$row->id.", jumlah_roll_masuk, 0 ) ) - SUM( if(gudang_id=".$row->id.", jumlah_roll_keluar, 0 ) ),0)  as gudang_".$row->id."_roll ";
		}
		if ($print_mode == 1) {
			$data['stok_barang'] = $this->inv_model->get_stok_barang_list($select, $tanggal, $tanggal_awal, $stok_opname_id); 
			$data['stok_opname_id'] = $stok_opname_id;
		}else{			
			// $data['stok_barang_total'] = $this->inv_model->get_stok_barang_list_total($select, $tanggal, $tanggal_awal, $stok_opname_id); 
			$data['stok_barang_total'] = array(); 
		}
		$this->load->view('admin/template',$data);
		
	}

	function data_select_inv(){
		$select = "";
		$select2 = "";
		$select_status = '';
		$select_all = "";
		$columnSelect = array();
		$dt[0] = 'urutan';
		$dt[1] = 'nama_barang_jual';
		$dt[2] = 'status_aktif';
		$dt[3] = 'last_edit';
		$idx = 4;
		$i = 0;
		$cond_qty = "";
		foreach ($this->gudang_list_aktif as $row) {
			$select .= ", ROUND(
				SUM( if(gudang_id=".$row->id.", 
						ifnull(
							if(tanggal_stok is not null, if(tanggal >= tanggal_stok,qty_masuk,0) , qty_masuk  ),
						0) ,
					0 )
				),2) - 
				ROUND(SUM( if(gudang_id=".$row->id.", 
						ifnull( 
							if(tanggal_stok is not null, if(tanggal >= tanggal_stok, qty_keluar,0 ), qty_keluar ) 
						,0), 
					0 )
				),
				2)  
				as gudang_".$row->id."_qty ,

				SUM( if(gudang_id=".$row->id.", 
						if(tanggal_stok is not null, 
							if(tanggal >= tanggal_stok,jumlah_roll_masuk,0 ), 
						jumlah_roll_masuk), 
					0 ) 
				) -
				SUM( if(gudang_id=".$row->id.", 
						if(tanggal_stok is not null,
							if(tanggal >= tanggal_stok, jumlah_roll_keluar, 0)
						,jumlah_roll_keluar), 
					0 ) 
				)  
				as gudang_".$row->id."_roll,
				concat('".$row->id."','/',barang_id,'/',warna_id) as gudang_".$row->id."_button,
				sum(if(gudang_id=".$row->id.",if(tanggal_stok is not null AND tanggal_penyesuaian is not null, if(tanggal_stok > tanggal_penyesuaian, 0 , 1 ) , if(tanggal_penyesuaian is not null, 1, 0) ),0)) as gudang_".$row->id."_status 
				";

			$select_all .= ", SUM(ifnull(qty_masuk,0)) - SUM( ifnull(qty_keluar,0)) as qty, SUM(ifnull(jumlah_roll_masuk,0)) - SUM( ifnull(jumlah_roll_keluar,0)) as jumlah_roll ";
			
			if ($i == 0) {
				$cond_qty = "WHERE gudang_".$row->id."_qty > 0 ";
			}else{
				$cond_qty .= "OR gudang_".$row->id."_qty > 0 ";
			}
			
			$dt[$idx] = 'gudang_'.$row->id.'_qty';
			$idx++;
			$dt[$idx] = "gudang_".$row->id."_data";
			$idx++;
			$dt[$idx] = 'gudang_'.$row->id.'_button';
			$idx++;


			$qty_add[$i] = 'gudang_'.$row->id.'_qty';
			$roll_add[$i]= 'gudang_'.$row->id.'_roll';
			$i++;
			$select_status .= ",CONCAT(gudang_".$row->id."_roll,'??',gudang_".$row->id."_status) as gudang_".$row->id."_data";
		}

		echo $select_status."<hr/>";

		$select2 .= ', if(tipe_qty != 3,'.implode('+', $qty_add).',"0") as qty_total, '.implode('+', $roll_add).' as roll_total'.$select_status;

		$dt[$idx] = 'qty_total';
		$idx++;
		$dt[$idx] = 'roll_total';
		$idx++;
		echo $select2.'<hr/>';

		echo $select."<hr/>";
		$select2 .= ', '.implode('+', $qty_add).' as qty_total, '.implode('+', $roll_add).' as roll_total';
		echo $select2;
		echo "<hr/>";
		echo 'OR '.implode('+', $qty_add).' > 0';
		echo "<hr/>";
		echo $cond_qty;
		echo "<hr/>";
		echo $tgl_lalu = date('Y-m-d', strtotime('-3 months'));
		echo "<hr/>";
		echo $select_all;

		echo "<hr/>";
		echo "testing";
		// print_r($this->common_model->db_select("gracetdj_system.nd_barang"));

	}

	function data_stok_barang(){

		// $session_data = $this->session->userdata('do_filter');
		$select = '';
		$select2 = "";
		$select_all = '';
		$select_status = '';
		$columnSelect = array();
		$dt[0] = 'urutan';
		$dt[1] = 'nama_barang_jual';
		$dt[2] = 'status_aktif';
		$dt[3] = 'last_edit';
		$cond_filter = '';
		$idx = 4;
		$i = 0;
		$cond_qty = "";
		foreach ($this->gudang_list_aktif as $row) {
			$select .= ", ROUND(
				SUM( if(gudang_id=".$row->id.", 
						ifnull(
							if(tanggal_stok is not null, if(tanggal >= tanggal_stok,qty_masuk,0) , qty_masuk  ),
						0) ,
					0 )
				),2) - 
				ROUND(SUM( if(gudang_id=".$row->id.", 
						ifnull( 
							if(tanggal_stok is not null, if(tanggal >= tanggal_stok, qty_keluar,0 ), qty_keluar ) 
						,0), 
					0 )
				),
				2)  
				as gudang_".$row->id."_qty ,

				SUM( if(gudang_id=".$row->id.", 
						if(tanggal_stok is not null, 
							if(tanggal >= tanggal_stok,jumlah_roll_masuk,0 ), 
						jumlah_roll_masuk), 
					0 ) 
				) -
				SUM( if(gudang_id=".$row->id.", 
						if(tanggal_stok is not null,
							if(tanggal >= tanggal_stok, jumlah_roll_keluar, 0)
						,jumlah_roll_keluar), 
					0 ) 
				)  
				as gudang_".$row->id."_roll,
				concat('".$row->id."','/',barang_id,'/',warna_id) as gudang_".$row->id."_button,
				SUM(if(gudang_id=".$row->id.",if(tanggal_stok is not null && tanggal_penyesuaian is not null, if(tanggal_stok > tanggal_penyesuaian, 0 , 1 ) , if(tanggal_penyesuaian is not null, 1, 0) ),0)) as gudang_".$row->id."_status 
				";

			$select_all .= ", SUM(ifnull(qty_masuk,0)) - SUM( ifnull(qty_keluar,0)) as qty, SUM(ifnull(jumlah_roll_masuk,0)) - SUM( ifnull(jumlah_roll_keluar,0)) as jumlah_roll ";
			
			if ($i == 0) {
				$cond_qty = "WHERE gudang_".$row->id."_qty > 0 ";
			}else{
				$cond_qty .= "OR gudang_".$row->id."_qty > 0 ";
			}
			
			$dt[$idx] = 'gudang_'.$row->id.'_qty';
			$idx++;
			$dt[$idx] = "gudang_".$row->id."_data";
			$idx++;
			$dt[$idx] = 'gudang_'.$row->id.'_button';
			$idx++;


			$qty_add[$i] = 'gudang_'.$row->id.'_qty';
			$roll_add[$i]= 'gudang_'.$row->id.'_roll';
			$i++;
			$select_status .= ",CONCAT(gudang_".$row->id."_roll,'??',gudang_".$row->id."_status) as gudang_".$row->id."_data";
		}

		$dt[$idx] = 'qty_total';
		$idx++;
		$dt[$idx] = 'roll_total';
		$idx++;

		$select2 .= ', if(tipe_qty != 3,'.implode('+', $qty_add).',"0") as qty_total, '.implode('+', $roll_add).' as roll_total'.$select_status;
		$cond_filter .= 'OR ('.implode('+', $qty_add).') > 0';


		$aColumns = $dt;
		// $aColumns = array('urutan','nama_barang_jual','status_aktif','gudang_2_qty','gudang_2_roll','gudang_1_qty','gudang_1_roll','gudang_3_qty','gudang_3_roll');

        
        $sIndexColumn = "urutan";
        
        // paging
        $sLimit = "";
        if ( isset( $_GET['iDisplayStart'] ) && $_GET['iDisplayLength'] != '-1' ){
            $sLimit = "LIMIT ".mysql_real_escape_string( $_GET['iDisplayStart'] ).", ".
                mysql_real_escape_string( $_GET['iDisplayLength'] );
        }
        $numbering = mysql_real_escape_string( $_GET['iDisplayStart'] );
        $page = 1;
        
        // ordering
        $sOrder = '';
        if ( isset( $_GET['iSortCol_0'] ) ){
            $sOrder = "ORDER BY";
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

        $where_add = '';
        $filter_type = 0;
        if ($this->input->get('filter_type') && $this->input->get('filter_type') != '' && $this->input->get('filter_type') != 0 ) {
        	$filter_type = 	$this->input->get('filter_type');
        	if ($filter_type == 1) {
	        	$tgl_lalu = date('Y-m-d', strtotime('-6 months'));
	        	$where_add = "WHERE last_edit >='".$tgl_lalu."' "; 
        	}elseif ($filter_type == 2) {
	        	$tgl_lalu = date('Y-m-d', strtotime('-3 months'));
	        	$where_add .= "WHERE last_edit >='".$tgl_lalu."' "; 
	        }
        }

        $stok_opname_id = 0;
        $tanggal = is_date_formatter($this->input->get("tanggal"));
        $tanggal_awal = '2018-01-01';
		$getOpname = $this->common_model->db_select("nd_stok_opname where tanggal <= '".$tanggal."' order by tanggal desc limit 1");
		foreach ($getOpname as $row) {
			// $tanggal_awal = $row->tanggal;
			$stok_opname_id = $row->id;
		}

		$tanggal_awal = '2018-01-01';
		$sOrder = "ORDER BY nama_barang_jual, urutan asc";
		if ($filter_type == 0) {
			$rResult = $this->inv_model->get_stok_barang_list_ajax($aColumns, $sWhere, "order by urutan asc", $sLimit, $select, $tanggal, $stok_opname_id, $tanggal_awal, $select2);
		}else{
			$rResult = $this->inv_model->get_stok_barang_list_ajax_new($aColumns, $sWhere, $sOrder, $sLimit, $select, $tanggal, $stok_opname_id, $tanggal_awal, $select2, $tgl_lalu, $cond_qty, $cond_filter);
		}

		// echo $tanggal;
		// echo $stok_opname_id;
		// echo $rResult;
		// echo "column".$aColumns; echo '<br>';
		// echo "WHERE:".$sWhere; echo '<br>';
		// echo "Order:".$sOrder; echo '<br>';
		// echo "Limit:".$sLimit; echo '<br>';
		// echo $select; echo '<br>';
		// echo $tanggal; echo '<br>';
		// echo $stok_opname_id; echo '<br>';
		// echo $tanggal_awal; echo '<br>';
        
        $Totalan = $this->inv_model->get_stok_barang_list_ajax($aColumns,  '' , $sOrder, '', $select, $tanggal,$stok_opname_id, $tanggal_awal, $select2);

        $Filternya = $this->inv_model->get_stok_barang_list_ajax($aColumns,  $sWhere , $sOrder, '', $select, $tanggal,$stok_opname_id, $tanggal_awal, $select2);
        $rResultTotal = $Totalan->num_rows();
        $iFilteredTotal = $Filternya->num_rows();
		
		// print_r($Filternya->result());
        // $iTotal = $rResultTotal;
        // $iFilteredTotal = $iTotal;
        
        $output = array(
            "sEcho" => intval($_GET['sEcho']),
            "iTotalRecords" => $rResultTotal,
            "iTotalDisplayRecords" => $iFilteredTotal,
            "aaData" => array()
        );
        
        foreach ($rResult->result_array() as $aRow){
        	// echo print_r($aRow).'<hr/>';
        	$y = 0;
            $row = array();
            for ( $i=0 ; $i<count($aColumns) ; $i++ ){
            	$row[] = $aRow[ $aColumns[$i] ];
            }
            $y++;
            $page++;
            $output['aaData'][] = $row;
        }

        // print_r($output);
        echo json_encode( $output );
	}

	function stok_barang_rekap(){
		$menu = is_get_url($this->uri->segment(1)) ;

		if ($this->input->get('tanggal') && $this->input->get('tanggal') != '') {
			$tanggal = is_date_formatter($this->input->get('tanggal'));
		}else{
			$tanggal = date("Y-m-d");
		}

		$data = array(
			'content' =>'admin/inventory/stok_barang_rekap',
			'breadcrumb_title' => 'Stok',
			'breadcrumb_small' => 'Barang No Warna',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data'=> $this->data,
			'tanggal' => is_reverse_date($tanggal) );


		$select = ''; $select_all='';
		foreach ($this->gudang_list_aktif as $row) {
			//$select .= ", SUM( if(gudang_id=".$row->id.", ifnull(qty_masuk,0), 0 ) ) - SUM( if(gudang_id=".$row->id.", ifnull(qty_keluar,0), 0 ) )  as ".$row->nama."_qty , SUM( if(gudang_id=".$row->id.", jumlah_roll_masuk, 0 ) ) - SUM( if(gudang_id=".$row->id.", jumlah_roll_keluar, 0 ) )  as ".$row->nama."_roll ";
		
			$select .= ", ROUND(
				SUM( if(gudang_id=".$row->id.", 
						ifnull(
							if(tanggal_stok is not null, if(tanggal >= tanggal_stok,qty_masuk,0) , qty_masuk  ),
						0) ,
					0 )
				),2) - 
				ROUND(SUM( if(gudang_id=".$row->id.", 
						ifnull( 
							if(tanggal_stok is not null, if(tanggal >= tanggal_stok, qty_keluar,0 ), qty_keluar ) 
						,0), 
					0 )
				),
				2)  
				as gudang_".$row->id."_qty ,

				SUM( if(gudang_id=".$row->id.", 
						if(tanggal_stok is not null, 
							if(tanggal >= tanggal_stok,jumlah_roll_masuk,0 ), 
						jumlah_roll_masuk), 
					0 ) 
				) -
				SUM( if(gudang_id=".$row->id.", 
						if(tanggal_stok is not null,
							if(tanggal >= tanggal_stok, jumlah_roll_keluar, 0)
						,jumlah_roll_keluar), 
					0 ) 
				)  
				as gudang_".$row->id."_roll,
				concat('".$row->id."','/',barang_id,'/',warna_id) as gudang_".$row->id."_button";


			$select_all .= ", SUM(gudang_".$row->id."_qty) as gudang_".$row->id."_qty, SUM(gudang_".$row->id."_roll) as gudang_".$row->id."_roll ";
			

		}

		$stok_opname_id = 0;
		$tanggal_awal = '2018-01-01';
		$getOpname = $this->common_model->db_select("nd_stok_opname where tanggal <= '".$tanggal."' order by tanggal desc limit 1");
		foreach ($getOpname as $row) {
			$tanggal_awal = $row->tanggal;
			$stok_opname_id = $row->id;
		}

		// echo $select."<hr/>". $select_all."<hr/>". $tanggal."<hr/>". $tanggal_awal."<hr/>". $stok_opname_id;

		// echo $select.'<br>';
		// $data['gudang_list'] = $this->common_model->db_select("nd_gudang where status_aktif = 1 ORDER BY id desc");
		$data['stok_barang'] = $this->inv_model->get_stok_barang_list_rekap($select, $select_all, $tanggal, $tanggal_awal, $stok_opname_id); 
		// echo $data['stok_barang'];
		$this->load->view('admin/template',$data);
	}

	
	function stok_barang_excel(){

		$tanggal = is_date_formatter($this->input->get('tanggal'));
		$select = '';
		foreach ($this->gudang_list_aktif as $row) {
			$select .= ", SUM( if(gudang_id=".$row->id.", ifnull(qty_masuk,0), 0 ) ) - SUM( if(gudang_id=".$row->id.", ifnull(qty_keluar,0), 0 ) )  as ".$row->nama."_qty , SUM( if(gudang_id=".$row->id.", jumlah_roll_masuk, 0 ) ) - SUM( if(gudang_id=".$row->id.", jumlah_roll_keluar, 0 ) )  as ".$row->nama."_roll ";
		}
		$tanggal_awal='2018-01-01';
		$stok_opname_id = 0;
		$getOpname = $this->common_model->db_select("nd_stok_opname where tanggal <= '".$tanggal."' order by tanggal desc limit 1");
		foreach ($getOpname as $row) {
			$tanggal_awal = $row->tanggal;
			$stok_opname_id = $row->id;
		}
		$stok_barang = $this->inv_model->get_stok_barang_list($select, $tanggal, $tanggal_awal, $stok_opname_id);
		$gudang_list = $this->common_model->db_select("nd_gudang where status_aktif = 1 ORDER BY id desc");

		
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

		$objPHPExcel->getActiveSheet()->mergeCells("A4:A5");
		$objPHPExcel->getActiveSheet()->mergeCells("B4:B5");
		$objPHPExcel->getActiveSheet()->mergeCells("C4:C5");
		

		$coll = 'D'; $coll_next = 'E';
		foreach ($this->gudang_list_aktif as $row) {
			$objPHPExcel->getActiveSheet()->mergeCells($coll."4:".$coll_next."4");
			$objPHPExcel->getActiveSheet()->setCellValue($coll.'4',$row->nama);
			$objPHPExcel->getActiveSheet()->setCellValue($coll.'5','Yard/Kg');
			$objPHPExcel->getActiveSheet()->setCellValue($coll_next.'5','Jumlah Roll');
			$objPHPExcel->getActiveSheet()->getStyle($coll."4:".$coll_next."5")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$coll++;
			$coll_next++;
			$coll++;
			$coll_next++;
		}

		
		$objPHPExcel->getActiveSheet()->mergeCells($coll."4:".$coll_next."4");
		$objPHPExcel->getActiveSheet()->setCellValue($coll.'4',"TOTAL");
		$objPHPExcel->getActiveSheet()->getStyle($coll."4:".$coll_next."5")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->setCellValue($coll.'5','Yard/Kg');
		$objPHPExcel->getActiveSheet()->setCellValue($coll_next.'5','Jumlah Roll');
		
		$objPHPExcel->getActiveSheet()->mergeCells("A1:".$coll_next."1");
		$objPHPExcel->getActiveSheet()->mergeCells("A2:".$coll_next."2");

		$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('A1', ' STOK BARANG ')
		->setCellValue('A2', ' Tanggal '.is_reverse_date($tanggal))
		->setCellValue('A4', 'No')
		->setCellValue('B4', 'Nama Beli')
		->setCellValue('C4', 'Nama Jual')
		;
	

		$row_no = 6;
		$idx = 1;
		foreach ($stok_barang as $row) {
			$coll = "A";
			
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$idx);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(7);
			$coll++;

			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$row->nama_barang.' '.$row->nama_warna);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(30);
			$coll++;

			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$row->nama_barang_jual.' '.$row->nama_warna_jual);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(30);
			$coll++;

			$subtotal_qty = 0;
			$subtotal_roll = 0;
			foreach ($this->gudang_list_aktif as $isi) { 

				$qty = $isi->nama.'_qty';
				$roll = $isi->nama.'_roll';
				$subtotal_qty += $row->$qty;
				$subtotal_roll += $row->$roll;
				
				$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, number_format($row->$qty,'2','.',''));
				$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
				$coll++;

				$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$row->$roll);
				$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
				$coll++;
				
			}

			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$subtotal_qty);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;

			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$subtotal_roll);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;
			$row_no++;
			$idx++;

		}

		// $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		//ob_end_clean();
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		ob_end_clean();	


		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header("Content-Disposition: attachment;filename=Stok_Barang_".date("dmY",strtotime($tanggal)).".xls");
		header('Cache-Control: max-age=0');
		$objWriter->save('php://output');
	}

	function kartu_stok(){
		$menu = is_get_url($this->uri->segment(1)) ;
		$gudang_id = $this->uri->segment(2);
		$barang_id = $this->uri->segment(3);
		$warna_id = $this->uri->segment(4);
		$tanggal_start = date("Y-m-1");
		$tanggal_end = date("Y-m-t");
		$nama_satuan = '';

		if ($this->input->get('barang_id') != '') {
			$barang_id = $this->input->get('barang_id');
		}

		if ($this->input->get('warna_id') != '') {
			$warna_id = $this->input->get('warna_id');
		}

		if ($this->input->get('gudang_id') != '') {
			$gudang_id = $this->input->get('gudang_id');
		}

		$barang = $this->common_model->db_select('nd_barang where id='.$barang_id);
		foreach ($barang as $row) {
			$nama_beli = $row->nama;
			$nama_jual = $row->nama_jual;
			$satuan_id = $row->satuan_id;
		}

		foreach ($this->satuan_list_aktif as $row) {
			if ($row->id == $satuan_id) {
				$nama_satuan = $row->nama;
			}
		}

		$warna = $this->common_model->db_select('nd_warna where id='.$warna_id);
		foreach ($warna as $row) {
			$warna_beli = $row->warna_beli;
			$warna_jual = $row->warna_jual;
		}

		$gudang = $this->common_model->db_select('nd_gudang where id='.$gudang_id);
		foreach ($gudang as $row) {
			$nama_gudang = $row->nama;
		}

		if ($this->input->get('tanggal_start') && $this->input->get('tanggal_start') != '') {
			$tanggal_start = is_date_formatter($this->input->get('tanggal_start'));
			if ($this->input->get('tanggal_end') != '') {
				$tanggal_end = is_date_formatter($this->input->get('tanggal_end'));
			}else{
				$tanggal_start = date("Y-m-1");
				$tanggal_end = date("Y-m-t");
			}
		}

		$data = array(
			'content' =>'admin/inventory/kartu_stok',
			'breadcrumb_title' => 'Stok',
			'breadcrumb_small' => 'Kartu Stok',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data'=> $this->data,
			'barang_id' => $barang_id,
			'warna_id' => $warna_id,
			'gudang_id' => $gudang_id,
			'tanggal_start' => is_reverse_date($tanggal_start),
			'tanggal_end' => is_reverse_date($tanggal_end),
			'nama_gudang' => $nama_gudang,
			'nama_beli' => $nama_beli,
			'nama_satuan' => $nama_satuan,
			'nama_jual' => $nama_jual,
			'warna_beli' => $warna_beli,
			'warna_jual' => $warna_jual );

		
		$tanggal_awal = '2018-01-01';
		$stok_opname_id = 0;
		$getOpname = $this->inv_model->get_last_opname($barang_id, $warna_id, $gudang_id, $tanggal_start);
		foreach ($getOpname as $row) {
			$tanggal_awal = $row->tanggal;
			$stok_opname_id = $row->id;
		}

		$data['stok_barang'] = $this->inv_model->get_stok_barang_satuan($gudang_id, $barang_id, $warna_id, $tanggal_start, $tanggal_end, '2018-01-01', $stok_opname_id); 
		$data['stok_awal'] = $this->inv_model->get_stok_barang_satuan_awal($gudang_id, $barang_id, $warna_id, $tanggal_start, '2018-01-01', $stok_opname_id);



		$data['stok_barang_by_satuan'] = $this->inv_model->get_kartu_stok_barang_by_satuan($gudang_id, $barang_id, $warna_id, $tanggal_start, $tanggal_end, $tanggal_awal, $stok_opname_id); 
		// $data['stok_barang_by_satuan'] = array(); 

		// $data['stok_barang'] = array();
		// $data['stok_awal'] = array();

		// echo $data['stok_barang'];

		// if (is_posisi_id() == 1) {
		// 	echo $tanggal_start.' '.$tanggal_awal.' '.$stok_opname_id;
		// }else{
			$this->load->view('admin/template_no_sidebar',$data);
			
		// }
	}

	function stok_barang_by_barang(){
		$menu = is_get_url($this->uri->segment(1)) ;

		if ($this->input->get('tanggal') && $this->input->get('tanggal') != '') {
			$tanggal = is_date_formatter($this->input->get('tanggal'));
		}else{
			$tanggal = date("Y-m-d");
		}

		$barang_id = '';$cond_barang = '';
		if ($this->input->get('barang_id') && $this->input->get('barang_id') != '') {
			$barang_id = $this->input->get('barang_id');
			$cond_barang = 'AND barang_id ='.$barang_id;
		}

		$print_mode = 0;

		if ($this->input->get('print_mode')) {
			$print_mode = 1;
		}

		$data = array(
			'content' =>'admin/inventory/stok_barang_by_barang',
			'breadcrumb_title' => 'Stok',
			'breadcrumb_small' => 'By Barang (Warna)',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data'=> $this->data,
			'tanggal' => is_reverse_date($tanggal),
			'print_mode'=> $print_mode,
			'barang_id' => $barang_id );


		
		$tanggal_awal = '2018-01-01';
		$stok_opname_id = 0;
		$getOpname = $this->common_model->db_select("nd_stok_opname where tanggal <= '".$tanggal."' order by tanggal desc limit 1");
		foreach ($getOpname as $row) {
			// $tanggal_awal = $row->tanggal;
			$stok_opname_id = $row->id;
		}
		
		$data['gudang_list'] = $this->gudang_list_aktif;
		$data['stok_barang'] = array();
	
			$select = '';
			$select2 = "";
			$select_all = '';
			$columnSelect = array();
			$dt[0] = 'urutan';
			$dt[1] = 'nama_barang_jual';
			$dt[2] = 'status_aktif';
			$dt[3] = 'last_edit';
			$cond_filter = '';
			$idx = 4;
			$i = 0;
			$cond_qty = "";
			foreach ($this->gudang_list_aktif as $row) {
				$select .= ", ROUND(
					SUM( if(gudang_id=".$row->id.", 
							ifnull(
								if(tanggal_stok is not null, if(tanggal >= tanggal_stok,qty_masuk,0) , qty_masuk  ),
							0) ,
						0 )
					) - 
					SUM( if(gudang_id=".$row->id.", 
							ifnull( 
								if(tanggal_stok is not null, if(tanggal >= tanggal_stok, qty_keluar,0 ), qty_keluar ) 
							,0), 
						0 )
					),
					2)  
					as gudang_".$row->id."_qty ,

					SUM( if(gudang_id=".$row->id.", 
							if(tanggal_stok is not null, 
								if(tanggal >= tanggal_stok,jumlah_roll_masuk,0 ), 
							jumlah_roll_masuk), 
						0 ) 
					) -
					SUM( if(gudang_id=".$row->id.", 
							if(tanggal_stok is not null,
								if(tanggal >= tanggal_stok, jumlah_roll_keluar, 0)
							,jumlah_roll_keluar), 
						0 ) 
					)  
					as gudang_".$row->id."_roll,
					concat('".$row->id."','/',barang_id,'/',warna_id) as gudang_".$row->id."_button";

				$select_all .= ", SUM(ifnull(qty_masuk,0)) - SUM( ifnull(qty_keluar,0)) as qty, SUM(ifnull(jumlah_roll_masuk,0)) - SUM( ifnull(jumlah_roll_keluar,0)) as jumlah_roll ";
				
				if ($i == 0) {
					$cond_qty = "WHERE gudang_".$row->id."_qty > 0 ";
				}else{
					$cond_qty .= "OR gudang_".$row->id."_qty > 0 ";
				}
				
				$dt[$idx] = 'gudang_'.$row->id.'_qty';
				$idx++;
				$dt[$idx] = 'gudang_'.$row->id.'_roll';
				$idx++;
				$dt[$idx] = 'gudang_'.$row->id.'_button';
				$idx++;


				$qty_add[$i] = 'gudang_'.$row->id.'_qty';
				$roll_add[$i]= 'gudang_'.$row->id.'_roll';
				$i++;
			}

			$dt[$idx] = 'qty_total';
			$idx++;
			$dt[$idx] = 'roll_total';
			$idx++;

			$select2 .= ', if(tipe_qty != 3,'.implode('+', $qty_add).',"0") as qty_total, '.implode('+', $roll_add).' as roll_total';
			$cond_filter .= 'OR ('.implode('+', $qty_add).') > 0';


			// echo $select;
			// echo $cond_barang;
			// echo "<hr/>";
			$data['stok_barang'] = $this->inv_model->get_stok_barang_list_by_barang_temp($tanggal_awal, $select, $tanggal, $cond_barang, $stok_opname_id); 
			// echo $data['stok_barang'];
			$this->load->view('admin/template',$data);
			// echo $data['stok_barang'];
		
		// echo $data['stok_barang'];
	}

//======================================stok barang all+ PO============================================

	function stok_barang_and_po(){
        $menu = is_get_url($this->uri->segment(1)) ;
        $tanggal = date('Y-m-d');
        $barang_id = '';
        if ($this->input->get('barang_id') ) {
        	$barang_id = $this->input->get('barang_id');
        }
        $cond_barang = '';
        $data = array(
            'content' =>'admin/inventory/stok_barang_and_po',
            'breadcrumb_title' => 'Inventory',
            'breadcrumb_small' => 'Daftar Barang + PO ',
            'nama_menu' => $menu[0],
            'nama_submenu' => $menu[1],
            'common_data'=> $this->data,
            'barang_id' => $barang_id,
            'data_isi'=> $this->data );

        $tanggal_awal = '2018-01-01';
        $stok_opname_id = 0;
        $getOpname = $this->common_model->db_select("nd_stok_opname where tanggal <= '".$tanggal."' order by tanggal desc limit 1");
        foreach ($getOpname as $row) {
            // $tanggal_awal = $row->tanggal;
            $stok_opname_id = $row->id;
        }

        if ($barang_id != '') {
        	$cond_barang = "WHERE barang_id = ".$barang_id;
	        $data['barang_list_selected'] = $this->inv_model->get_stok_for_pre_po('', $tanggal, $tanggal_awal, $stok_opname_id, $cond_barang);
        	$data['batch_for_pre_po'] = $this->inv_model->get_batch_for_pre_po($barang_id);
        }else{
	        $data['barang_list_selected'] = array();
        	$data['batch_for_pre_po'] = array();
        }

        // if (is_posisi_id() != 1) {
        // 	$data['content'] = 'admin/under_construction';
        // }
        // echo ''."<hr/>". $tanggal."<hr/>". $tanggal_awal."<hr/>". $stok_opname_id."<hr/>". $cond_barang;
        $this->load->view('admin/template',$data);
    }

//======================================stok barang + HPP============================================
	function stok_barang_hpp(){
		$menu = is_get_url($this->uri->segment(1)) ;

		if ($this->input->get('tanggal') && $this->input->get('tanggal') != '') {
			$tanggal = is_date_formatter($this->input->get('tanggal'));
		}else{
			$tanggal = date("Y-m-d");
		}

		$data = array(
			'content' =>'admin/inventory/stok_barang_hpp',
			'breadcrumb_title' => 'Stok',
			'breadcrumb_small' => 'Barang + HPP',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data'=> $this->data,
			'tanggal' => is_reverse_date($tanggal) );


		$select2 = ''; $select = ''; $i=0;
		foreach ($this->gudang_list_aktif as $row) {
			$select .= ", ROUND(
					SUM( if(gudang_id=".$row->id.", 
							ifnull(
								if(tanggal_stok is not null, if(tanggal >= tanggal_stok,qty_masuk,0) , qty_masuk  ),
							0) ,
						0 ) 
					) - 
					SUM( if(gudang_id=".$row->id.", 
							ifnull( 
								if(tanggal_stok is not null, if(tanggal >= tanggal_stok, qty_keluar,0 ), qty_keluar ) 
							,0), 
						0 )
					),
					2)  
					as gudang_".$row->id."_qty ,

					SUM( if(gudang_id=".$row->id.", 
							if(tanggal_stok is not null, 
								if(tanggal >= tanggal_stok,jumlah_roll_masuk,0 ), 
							jumlah_roll_masuk), 
						0 ) 
					) -
					SUM( if(gudang_id=".$row->id.", 
							if(tanggal_stok is not null,
								if(tanggal >= tanggal_stok, jumlah_roll_keluar, 0)
							,jumlah_roll_keluar), 
						0 ) 
					)  
					as gudang_".$row->id."_roll,
					concat('".$row->id."','/',barang_id,'/',warna_id) as gudang_".$row->id."_button";

			$qty_add[$i] = 'gudang_'.$row->id.'_qty';
			$roll_add[$i]= 'gudang_'.$row->id.'_roll';
			$i++;
		}

		$select2 .= ', if(tipe_qty != 3,'.implode('+', $qty_add).',"0") as qty_total, '.implode('+', $roll_add).' as roll_total';
		// echo $select.'<br>';

		$kolom = "12_harga";
		$tgl_tutup_buku = "2019-12-31";
		$first_book = $this->common_model->db_select("nd_tutup_buku ORDER BY id asc LIMIT 1");
		foreach ($first_book as $row) {
			$kolom = date("m", strtotime($row->tanggal)).'_harga';
			$tgl_tutup_buku = $row->tanggal;
		}
		$data['gudang_list'] = $this->common_model->db_select("nd_gudang where status_aktif = 1 ORDER BY urutan desc");
		$data['stok_barang'] = $this->inv_model->get_stok_barang_list_hpp($select2, $select, $tanggal, $kolom, $tgl_tutup_buku); 
		// echo $data['stok_barang'];
		// if (is_posisi_id() != 1) {
			// $data['content'] = 'admin/under_construction';
			$this->load->view('admin/template',$data);
		// }else {
		// 	echo $kolom;
		// 	echo $tgl_tutup_buku;
		// 	echo "<hr/>";
		// 	echo $select;
		// 	echo "<hr/>";
		// 	echo $select2;
		// }
	}

//===================================stok opname===============================================

	function table_stok_opname(){
		$select = '';
		foreach ($this->gudang_list_aktif as $row) {
			$select .= ", SUM( if(gudang_id=".$row->id.", ifnull(qty_masuk,0), 0 ) ) - SUM( if(gudang_id=".$row->id.", ifnull(qty_keluar,0), 0 ) )  as ".$row->nama."_qty , SUM( if(gudang_id=".$row->id.", jumlah_roll_masuk, 0 ) ) - SUM( if(gudang_id=".$row->id.", jumlah_roll_keluar, 0 ) )  as ".$row->nama."_roll ";
		}
		$data['stok_barang'] = $this->inv_model->get_stok_barang_list($select, date('Y-m-d'));
		$data['content'] = 'admin/inventory/stok_opname';
		$this->load->view('admin/template_no_sidebar',$data);
		// $this->load->view('admin/inventory/stok_opname',$data);
	}


//================================penyesuaian stok ==================================================

	function penyesuaian_stok_insert(){
		$ini = $this->input;
		$gudang_id = $ini->post('gudang_id');
		$warna_id = $ini->post('warna_id');
		$barang_id = $ini->post('barang_id');
		$jumlah_roll = 0;
		$penyesuaian_stok_id = $ini->post('penyesuaian_stok_id');
		if ($ini->post('jumlah_roll') != '') {
			$jumlah_roll = $ini->post('jumlah_roll');
		}
		$data = array(
			'barang_id' => $ini->post('barang_id'),
			'warna_id' => $ini->post('warna_id'),
			'gudang_id' => $ini->post('gudang_id'),
			'tanggal' => is_date_formatter($ini->post('tanggal')),
			'tipe_transaksi' => $ini->post('tipe_transaksi'),
			'qty' => $ini->post('qty'),
			'jumlah_roll' => $jumlah_roll ,
			'keterangan' => $ini->post('keterangan'),
			'user_id' => is_user_id() );

		if ($penyesuaian_stok_id == '') {
			$penyesuaian_stok_id = $this->common_model->db_insert('nd_penyesuaian_stok', $data);
		}else{
			$this->common_model->db_update('nd_penyesuaian_stok', $data, 'id', $penyesuaian_stok_id);
		}

		
		$qty_data = explode('--', $this->input->post('rekap_qty'));
        foreach ($qty_data as $key => $value) {
            $pcs = explode('??', $value);
            if ($pcs[2] != 0 ) {
                $pembelian_qty_detail_id = $pcs[2];
                $dt_update = array(
                    'penyesuaian_stok_id' => $penyesuaian_stok_id , 
                    'qty' => $pcs[0],
                    'jumlah_roll' => $pcs[1]
                    );
                if ($pcs[0] != 0 && $pcs[0] != '') {
                    // echo 'update';
                    // print_r($dt_update);echo '<br/>';
                    $this->common_model->db_update('nd_penyesuaian_stok_qty',$dt_update,'id', $pcs[2]);
                }else{
                    // echo 'delete';
                    // echo '<br/>';
                    $this->common_model->db_delete('nd_penyesuaian_stok_qty','id', $pcs[2]);
                }
            }else{
            	if ($pcs[0] != '' && $pcs[0] != 0 && $pcs[0] != null) {
	                $dt_detail[$key] = array(
	                    'penyesuaian_stok_id' => $penyesuaian_stok_id , 
	                    'qty' => $pcs[0],
	                    'jumlah_roll' => $pcs[1]
	                );
            	}
            }

        }
        if (isset($dt_detail)) {
            // echo 'insert';
            $this->common_model->db_insert_batch('nd_penyesuaian_stok_qty', $dt_detail);
        }


		redirect(is_setting_link('inventory/kartu_stok').'/'.$gudang_id.'/'.$barang_id.'/'.$warna_id);
	}

	function penyesuaian_stok_update(){
		$ini = $this->input;
		$gudang_id = $ini->post('gudang_id');
		$warna_id = $ini->post('warna_id');
		$barang_id = $ini->post('barang_id');
		$id = $ini->post('penyesuaian_stok_id');
		$data = array(
			'tanggal' => is_date_formatter($ini->post('tanggal')),
			'tipe_transaksi' => $ini->post('tipe_transaksi'),
			'qty' => $ini->post('qty'),
			'jumlah_roll' => $ini->post('jumlah_roll'),
			'keterangan' => $ini->post('keterangan'),
			'user_id' => is_user_id() );

		// print_r($data);

		$this->common_model->db_update('nd_penyesuaian_stok', $data,'id', $id);
		redirect(is_setting_link('inventory/kartu_stok').'/'.$gudang_id.'/'.$barang_id.'/'.$warna_id);
	}

	function penyesuaian_stok_remove(){
		$ini = $this->input;
		$id = $this->input->post('penyesuaian_stok_id');
		$gudang_id = $ini->post('gudang_id');
		$warna_id = $ini->post('warna_id');
		$barang_id = $ini->post('barang_id');
		$this->common_model->db_delete('nd_penyesuaian_stok','id',$id);
		$this->common_model->db_delete('nd_penyesuaian_stok_qty','penyesuaian_stok_id',$id);
		redirect(is_setting_link('inventory/kartu_stok').'/'.$gudang_id.'/'.$barang_id.'/'.$warna_id);
	}

//================================penyesuaian stok split ==================================================

	function penyesuaian_stok_split_insert(){

		$ini = $this->input;
		$penyesuaian_stok_id = $this->input->post('penyesuaian_stok_id');
		$gudang_id = $ini->post('gudang_id');
		$warna_id = $ini->post('warna_id');
		$barang_id = $ini->post('barang_id');
		$tanggal = is_date_formatter($ini->post('tanggal'));

		$tanggal_start = date("Y-m-1", strtotime($tanggal));
		$tanggal_end = date("Y-m-t", strtotime($tanggal) );

		$tanggal_start = is_reverse_date($tanggal_start);
		$tanggal_end = is_reverse_date($tanggal_end);
		$jumlah_roll = 0;
		if ($ini->post('jumlah_roll') != '') {
			$jumlah_roll = $ini->post('jumlah_roll');
		}
		$data = array(
			'barang_id' => $ini->post('barang_id'),
			'warna_id' => $ini->post('warna_id'),
			'gudang_id' => $ini->post('gudang_id'),
			'tanggal' => is_date_formatter($ini->post('tanggal')),
			'tipe_transaksi' => 3,
			'qty' => $ini->post('qty_ori'),
			'jumlah_roll' => $ini->post('jumlah_roll_ori'),
			'keterangan' => $ini->post('keterangan'),
			'user_id' => is_user_id() );

		if ($penyesuaian_stok_id == '') {
			$penyesuaian_stok_id = $this->common_model->db_insert('nd_penyesuaian_stok', $data);
		}else{
			$this->common_model->db_update('nd_penyesuaian_stok', $data, 'id', $penyesuaian_stok_id);
		}

		foreach ($this->input->post('qty') as $key => $value) {
			$j_roll = $this->input->post('jumlah_roll')[$key];
			$data_detail = array(
				'penyesuaian_stok_id' => $penyesuaian_stok_id,
				'qty' => $value ,
				'jumlah_roll' => ($j_roll == '' ? 0 : $j_roll) 
				);
			if ($this->input->post('split_id')[$key] == '' || $this->input->post('split_id')[$key] == 0) {
				if ($value != '') {
					$data_split[$key] = $data_detail;
				}
			}else{
				if ($value == '') {
					$this->common_model->db_delete("nd_penyesuaian_stok_split",'id', $this->input->post('split_id')[$key]);
				}else{
					$this->common_model->db_update("nd_penyesuaian_stok_split",$data_detail,'id', $this->input->post('split_id')[$key]);
				}
			}
		}

		if (isset($data_split)) {
			$this->common_model->db_insert_batch('nd_penyesuaian_stok_split', $data_split);
		}

		redirect(is_setting_link('inventory/kartu_stok').'/'.$gudang_id.'/'.$barang_id.'/'.$warna_id.'?tanggal_start='.$tanggal_start.'&tanggal_end='.$tanggal_end);
	}

	function penyesuaian_stok_split_remove(){
		$penyesuaian_stok_id = $this->input->get('penyesuaian_stok_id');
		$get_data = $this->common_model->db_select("nd_penyesuaian_stok where id=".$penyesuaian_stok_id);
		foreach ($get_data as $row) {
			$gudang_id = $row->gudang_id;
			$barang_id = $row->barang_id;
			$warna_id = $row->warna_id;
			$tanggal = $row->tanggal;
		}

		$tanggal_start = date("Y-m-1", strtotime($tanggal));
		$tanggal_end = date("Y-m-t", strtotime($tanggal) );

		$tanggal_start = is_reverse_date($tanggal_start);
		$tanggal_end = is_reverse_date($tanggal_end);

		$this->common_model->db_delete("nd_penyesuaian_stok",'id', $penyesuaian_stok_id);
		$this->common_model->db_delete('nd_penyesuaian_stok_split','penyesuaian_stok_id', $penyesuaian_stok_id);
		redirect(is_setting_link('inventory/kartu_stok').'/'.$gudang_id.'/'.$barang_id.'/'.$warna_id.'?tanggal_start='.$tanggal_start.'&tanggal_end='.$tanggal_end);
		
	}

//=====================================mutasi barang=============================================

	function mutasi_barang(){
		$menu = is_get_url($this->uri->segment(1)) ;

		$tanggal_start = date("Y-m-d", strtotime("-7days"));
		$tanggal_end = date("Y-m-d");

		$cond = '';
		if ($this->input->get('tanggal_start') && $this->input->get('tanggal_start') != ''&& $this->input->get('tanggal_end') != '') {
			$tanggal_end = is_date_formatter($this->input->get('tanggal_end'));
			$tanggal_start = is_date_formatter($this->input->get('tanggal_start'));
		}

		$cond = " WHERE tanggal >='".$tanggal_start."' AND tanggal <= '".$tanggal_end."'";


		$barang_id = 0;
		if ($this->input->get('barang_id')) {
			$connect = " AND ";
			if ($cond == '') {
				$connect = " WHERE "; 
			}
			$cond .= $connect.' barang_id = '.$this->input->get('barang_id');
			$barang_id = $this->input->get('barang_id');
		}

		$warna_id = 0;
		if ($this->input->get('warna_id')) {
			$connect = " AND ";
			if ($cond == '') {
				$connect = " WHERE "; 
			}
			$cond .= $connect.' WARNA_id = '.$this->input->get('warna_id');
			$warna_id = $this->input->get('warna_id');
		}

		$barang_id_latest = '';
		$gudang_before_latest = '';
		$gudang_after_latest = '';
		if ($this->session->flashdata('mutasi_barang')) {
			$data_latest = $this->session->flashdata('mutasi_barang');
			$data_latest = explode('??', $data_latest);
			$barang_id_latest = $data_latest[0];
			$gudang_before_latest = $data_latest[1];
			$gudang_after_latest = $data_latest[2];
		}

		$data = array(
			'content' =>'admin/inventory/mutasi_barang_list_2',
			'breadcrumb_title' => 'Inventory',
			'breadcrumb_small' => 'Mutasi Barang',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data'=> $this->data,
			'tanggal_start' => is_reverse_date($tanggal_start),
			'tanggal_end' => is_reverse_date($tanggal_end),
			'barang_id' => $barang_id,
			'warna_id' => $warna_id,
			'cond' => $cond,
			'barang_id_latest' => $barang_id_latest,
			'gudang_before_latest' => $gudang_before_latest,
			'gudang_after_latest' => $gudang_after_latest
			);


		// $data['mutasi_barang_list'] = $this->inv_model->mutasi_barang_list(); 
		if ($barang_id != '' && $warna_id != '') {
			# code...
		}
		$this->load->view('admin/template',$data);
	}

	function mutasi_barang_insert(){
		$ini = $this->input;
		$data = array(
			'gudang_id_before' => $ini->post('gudang_id_before') ,
			'gudang_id_after' => $ini->post('gudang_id_after') ,
			'tanggal' => is_date_formatter($ini->post('tanggal')) ,
			'barang_id' => $ini->post('barang_id') ,
			'warna_id' => $ini->post('warna_id'),
			'qty' => $ini->post('qty') ,
			'jumlah_roll' => $ini->post('jumlah_roll'),
			'user_id' => is_user_id() )
			;
		$rekap_qty = explode('--', $this->input->post('rekap_qty'));
		$result_id = $this->common_model->db_insert('nd_mutasi_barang',$data);
		
		foreach ($rekap_qty as $key => $value) {
			$dt = explode('??', $value);
			if ($dt[0] != 0 && $dt[0] != '' && $dt[0] != null) {
				$data_detail[$key] = array(
					'mutasi_barang_id' => $result_id ,
					'qty' => $dt[0],
					'jumlah_roll' => $dt[1] );
			}
		}

		$this->common_model->db_insert_batch('nd_mutasi_barang_qty', $data_detail);

		// print_r($data_detail);


		$this->session->set_flashdata('mutasi_barang', $ini->post('barang_id').'??'.$ini->post('gudang_id_before').'??'.$ini->post('gudang_id_after'));

		redirect(is_setting_link('inventory/mutasi_barang'));

	}

	function mutasi_barang_update(){
		$ini = $this->input;
		$id = $this->input->post('mutasi_barang_id');
		$data = array(
			'gudang_id_before' => $ini->post('gudang_id_before') ,
			'gudang_id_after' => $ini->post('gudang_id_after') ,
			'tanggal' => is_date_formatter($ini->post('tanggal')) ,
			'barang_id' => $ini->post('barang_id') ,
			'warna_id' => $ini->post('warna_id'),
			'user_id' => is_user_id() );
		$rekap_qty = explode('--', $this->input->post('rekap_qty'));
		$this->common_model->db_update('nd_mutasi_barang',$data,'id',$id);
		
		foreach ($rekap_qty as $key => $value) {
			$dt = explode('??', $value);
			if ($dt[2] != 0) {
				$data_update = array(
					'mutasi_barang_id' => $id ,
					'qty' => $dt[0],
					'jumlah_roll' => $dt[1] );

				if ($dt[0] != 0) {
					$this->common_model->db_update('nd_mutasi_barang_qty',$data_update,'id',$dt[2]);
				}else{
					$this->common_model->db_delete('nd_mutasi_barang_qty','id',$dt[2]);
				}
			}else{
				if($dt[0] != '' && $dt[0] !=0 && $dt[0] != null){
					$data_detail[$key] = array(
						'mutasi_barang_id' => $id ,
						'qty' => $dt[0],
						'jumlah_roll' => $dt[1] 
						);
					
				}
			}
		}

		if (isset($data_detail)) {
			$this->common_model->db_insert_batch('nd_mutasi_barang_qty', $data_detail);
		}
		// print_r($data_detail);


		redirect(is_setting_link('inventory/mutasi_barang'));

	}


	function data_mutasi(){

		// $session_data = $this->session->userdata('do_filter');
		
		// $aColumns = array('status_aktif','tanggal','nama_barang','gudang_before','gudang_after','qty','jumlah_roll', 'data');
        if (is_posisi_id() <= 3) {
			$aColumns = array('status_aktif','tanggal','nama_barang','gudang_before','gudang_after','qty','jumlah_roll','username', 'data');
		}else{
			$aColumns = array('status_aktif','tanggal','nama_barang','gudang_before','gudang_after','qty','jumlah_roll', 'data');
		}

        $sIndexColumn = "idx";
        
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

		$cond = $this->input->get('cond');

        $rResult = $this->inv_model->get_mutasi_barang_ajax($aColumns, $sWhere/*, $sOrder*/, $sLimit, $cond);        
        
        $rResultTotal = $this->common_model->db_select_num_rows('nd_pembelian group by tanggal');
        $Filternya = $this->inv_model->get_mutasi_barang_ajax($aColumns, $sWhere /*, $sOrder*/, '',$cond);
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

	function mutasi_barang_batal(){
		$id = $this->input->get('id');
		$status_aktif = $this->input->get('status_aktif');
		if ($status_aktif == 0) {
			$status_aktif_update = 1;
		}else if ($status_aktif == 1) {
			$status_aktif_update = 0;
		}
		$data = array(
			'status_aktif' => $status_aktif_update );

		$this->common_model->db_update('nd_mutasi_barang', $data,'id', $id);
		redirect(is_setting_link('inventory/mutasi_barang'));
	}

	function mutasi_barang_detail(){
		$menu = is_get_url($this->uri->segment(1)) ;
		$tanggal = $this->uri->segment(2);
		$tanggal = is_date_formatter($tanggal);

		$data = array(
			'content' =>'admin/transaction/mutasi_barang_detail',
			'breadcrumb_title' => 'Stok',
			'breadcrumb_small' => 'Mutasi Barang',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data'=> $this->data,
			'tanggal' => is_reverse_date($tanggal)
			);

		if ($tanggal == '') {
			$data['mutasi_barang_list'] = array();
		}else{
			$data['mutasi_barang_list'] = $this->inv_model->get_mutasi_list_detail($tanggal);
		}
		$this->load->view('admin/template',$data);
	}

	function cek_barang_qty(){
		$barang_id = $this->input->post('barang_id');
		$warna_id = $this->input->post('warna_id');
		$gudang_id = $this->input->post('gudang_id');
		$mutasi_barang_id = $this->input->post('mutasi_barang_id');
		$tanggal = is_date_formatter($this->input->post('tanggal'));
		// $get_stok_opname = $this->common_model->db_select("nd_stok_opname where tanggal <= '".$tanggal."' ORDER BY tanggal desc LIMIT 1");
		$getOpname = $this->inv_model->get_last_opname($barang_id, $warna_id, $gudang_id, $tanggal);
        $tanggal_awal = '2018-01-01';
        $stok_opname_id = 0;
        foreach ($getOpname as $row) {
            $tanggal_awal = $row->tanggal;
            $stok_opname_id = $row->id;
        }

        $cond_detail = '';
        if ($mutasi_barang_id != '') {
            $cond_detail = 'AND id != '.$mutasi_barang_id;
        }

        //sama kaya cek stok untuk penjualan di controllers transaction, models transaction -> get_qty_stok_by_barang
		$data = $this->inv_model->cek_barang_qty($gudang_id, $barang_id,$warna_id, $tanggal_awal, $stok_opname_id, $cond_detail);
        $stok_barang_by_satuan = $this->inv_model->get_kartu_stok_barang_by_satuan($gudang_id, $barang_id, $warna_id, $tanggal, $tanggal,  $tanggal_awal, $stok_opname_id); 


		$return[0] = ($data);
        $return[1] = ($stok_barang_by_satuan);
        $return[2] = $tanggal_awal;
        echo json_encode($return);

	}

	function mutasi_barang_remove(){
		$id = $this->uri->segment(3);
		// echo $id;

		$data = array(
			'status_aktif' => 0  );
		$this->common_model->db_update('nd_mutasi_barang',$data,'id',$id);
		redirect(is_setting_link('inventory/mutasi_barang'));
		
	}

	function mutasi_barang_excel(){
		
		$tanggal_end = $this->input->get('tanggal_end');
		$tanggal_start = $this->input->get('tanggal_start');
		
		$cond = " WHERE status_aktif = 1 AND tanggal >='".$tanggal_start."' AND tanggal <= '".$tanggal_end."'";


		$barang_id = 0;
		if ($this->input->get('barang_id') != '0' ) {
			$connect = " AND ";
			if ($cond == '') {
				$connect = " WHERE "; 
			}
			$cond .= $connect.' barang_id = '.$this->input->get('barang_id');
			$barang_id = $this->input->get('barang_id');
		}

		$warna_id = 0;
		if ($this->input->get('warna_id') != '0') {
			$connect = " AND ";
			if ($cond == '') {
				$connect = " WHERE "; 
			}
			$cond .= $connect.' WARNA_id = '.$this->input->get('warna_id');
			$warna_id = $this->input->get('warna_id');
		}

		$nama_barang = 'Semua';
		$get = $this->common_model->db_select("nd_barang where id=".$barang_id);
		foreach ($get as $row) {
			$nama_barang = $row->nama_barang;
		}

		$nama_warna = 'Semua';
		$get = $this->common_model->db_select("nd_warna where id=".$warna_id);
		foreach ($get as $row) {
			$nama_warna = $row->warna_jual;
		}

		$mutasi_barang_list = $this->inv_model->get_mutasi_barang($cond); 
		
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

		$objPHPExcel->getActiveSheet()->setCellValue('A1', ' Tanggal '.is_reverse_date($tanggal_start).' s/d '.is_reverse_date($tanggal_end));
		$objPHPExcel->getActiveSheet()->setCellValue('A2', 'Mutasi Barang : '.$nama_barang." ".$nama_warna);

		
		$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('A4', 'No')
		->setCellValue('B4', 'Tanggal')
		->setCellValue('C4', 'Barang')
		->setCellValue('D4', 'Lokasi Sebelum')
		->setCellValue('E4', 'Lokasi Setelah')
		->setCellValue('F4', 'Qty')
		->setCellValue('G4', 'Jumlah Roll')
		;
	

		$row_no = 6;
		$idx = 1;
		foreach ($mutasi_barang_list as $row) {
			$coll = "A";
			
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$idx);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(7);
			$coll++;

			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,is_reverse_date($row->tanggal));
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;


			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$row->nama_barang);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(30);
			$coll++;

			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$row->gudang_before);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;

			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$row->gudang_after);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;

			
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,str_replace('.00', '', $row->qty));
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;

			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$row->jumlah_roll);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;
			$row_no++;
			$idx++;

		}

		// $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		//ob_end_clean();
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		ob_end_clean();

		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header("Content-Disposition: attachment;filename=mutasi_barang.xls");
		header('Cache-Control: max-age=0');
		$objWriter->save('php://output');
	}

//=====================================mutasi persediaan barang=============================================

	function mutasi_persediaan_barang(){
		$menu = is_get_url($this->uri->segment(1)) ;

		$tanggal_awal = '2018-01-01';
		
		$tanggal_start = date("Y-m-01"); 
		$tanggal_end = date("Y-m-t"); 
		$toko_id = 1;
		$gudang_id = 0;
		$tanggal = date('F Y');

		// if ($this->input->get('tanggal_start')) {
		if ($this->input->get('tanggal')) {
			// $tanggal_start = is_date_formatter($this->input->get('tanggal_start'));
			// $tanggal_end = is_date_formatter($this->input->get('tanggal_end'));
			$tanggal = $this->input->get('tanggal');
			$tanggal_get = str_replace(' ', '-', $tanggal);
			$tanggal_get = is_date_formatter('01-'.$tanggal_get);
			$tanggal_start = date('Y-m-01', strtotime($tanggal_get));
			$tanggal_end = date('Y-m-t', strtotime($tanggal_get));
			$toko_id = $this->input->get('toko_id');
		}else{
			$tanggal_start = date('Y-m-01');
			$tanggal_end = date('Y-m-t');
		}

		$tanggal_before = date("Y-m-d", strtotime($tanggal_start. "-1 month")); 
		$bulan_tutup_buku = date("m", strtotime($tanggal_before)); 
		$tahun_tutup_buku = date("Y", strtotime($tanggal_before));

		$bulan_tutup_buku_now = date("m", strtotime($tanggal_start)); 
		$tahun_tutup_buku_now = date("Y", strtotime($tanggal_start));

		$kolom_harga = $bulan_tutup_buku.'_harga';
		$tutup_buku_id_now = 0;
		// echo $tanggal_before;
		$get_tutup_buku = $this->common_model->db_select("nd_tutup_buku where MONTH(tanggal) ='".$bulan_tutup_buku."' AND YEAR(tanggal) ='".$tahun_tutup_buku."'");
		$tutup_buku_id = 0;
		foreach ($get_tutup_buku as $row) {
			$tutup_buku_id = $row->id;
		}

		$username = "";
		$updated_now = "";
		$tanggal_last = '';
		$tanggal_tutup_now = "";
		if ($tutup_buku_id != 0) {
			$get_tutup_buku_now = $this->inv_model->get_tutup_buku_now($tahun_tutup_buku_now, $bulan_tutup_buku_now);
			// print_r($get_tutup_buku_now)
			foreach ($get_tutup_buku_now as $row) {
				$tutup_buku_id_now = $row->id;
				$username = $row->username;
				$updated_now = $row->updated;
				$tanggal_tutup_now = $row->tanggal;
			}
		}else{
			$tanggal_tutup_last = $this->common_model->db_select("nd_tutup_buku ORDER BY tanggal desc limit 1");
			foreach ($tanggal_tutup_last as $row) {
				$tanggal_last = $row->tanggal;
			}
		}

		if ($this->input->get('gudang_id') && $this->input->get('gudang_id') != '') {
			$gudang_id = $this->input->get('gudang_id');
		}

		if ($gudang_id == 0) {
			$content = "mutasi_persediaan_barang";
		}else{
			$content = "mutasi_persediaan_barang_gudang";
		}

		$data = array(
			'content' =>'admin/inventory/'.$content,
			'breadcrumb_title' => 'Inventory',
			'breadcrumb_small' => 'Mutasi Persediaan Barang',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data'=> $this->data,
			'tanggal_start' => is_reverse_date($tanggal_start),
			'tanggal_end' => is_reverse_date($tanggal_end),
			'toko_id' => $toko_id,
			'gudang_id' => $gudang_id,
			'tanggal'=> $tanggal,
			'tanggal_before' => date('M Y', strtotime($tanggal_before)),
			'tutup_buku_id' => $tutup_buku_id,
			'tutup_buku_id_now' => $tutup_buku_id_now,
			'username' => $username,
			'updated_now' => $updated_now,
			'tanggal_tutup_now' => $tanggal_tutup_now,
			'tanggal_last' => $tanggal_last
			);

		$stok_opname_id = 0;
		$getOpname = $this->common_model->db_select("nd_stok_opname where tanggal <= '".$tanggal_end."' order by tanggal desc limit 1");
		foreach ($getOpname as $row) {
			$tanggal_awal = $row->tanggal;
			$stok_opname_id = $row->id;
		}

		// echo $tanggal_start;
		if ($gudang_id == 0) {
			$data['mutasi_barang_list'] = $this->inv_model->mutasi_persediaan_barang_global($tanggal_awal, $tanggal_start,$tanggal_end , $stok_opname_id, $tutup_buku_id, $kolom_harga); 
		}else{
			$data['mutasi_barang_list'] = $this->inv_model->mutasi_persediaan_temp($tanggal_awal, $tanggal_start,$tanggal_end, $gudang_id, $stok_opname_id); 
		}
		// echo $tanggal;
		$this->load->view('admin/template',$data);
	}

	function tutup_buku_insert(){
		$data_id = $this->input->post('data_id');
		$harga = $this->input->post('harga');

		$tanggal = is_date_formatter($this->input->post('tanggal'));
		$tanggal = date('Y-m-t', strtotime($tanggal));
		$tanggal_link = date('F+Y', strtotime($tanggal));
		$tahun = date('Y', strtotime($tanggal));
		$bulan = date('m', strtotime($tanggal));

		//initial case untuk mysql
		$case = "CASE
		";

		$idx = 0;
		foreach ($data_id as $key => $value) {
			$list_year = array();
			$data_brg = explode('--', $key);
			
			//data untuk tutup buku
			$data_tutup_buku = array(
				'tanggal' => $tanggal ,
				'user_id' => is_user_id() );

			//data temp untuk fill temporary table untuk di compare dengan data yg sudah ada di tutup buku detail
			$data_temp[$idx] =  array(
				'barang_id' => $data_brg[0] ,
				'warna_id' => $data_brg[1] ,
			);

			//klo belum ada set tutup buku detail, pake ini
			$data_new[$idx] = array(
				'tahun' => $tanggal,
				'barang_id' => $data_brg[0] ,
				'warna_id' => $data_brg[1] ,
				);

			//create case untuk update

			$case .="WHEN YEAR(tahun)='".$tahun."' and barang_id=".$data_brg[0]." and warna_id=".$data_brg[1]." THEN ".$harga[$value]."
			";

			$idx++;
		}


		//cek kalo data tahun tersebut tersedia/tidak
		$baris = $this->common_model->db_select_num_rows("nd_tutup_buku_detail where YEAR(tahun)='".$tahun."'");
		if ($baris == 0) {
			//klo tidak tersedia, insert new set
			$this->common_model->db_insert_batch("nd_tutup_buku_detail", $data_new);
		}else{
			//klo tersedia, cek set barang, takutnya ada barang/warna baru
			//bikin temp data in nd_barang_warna_temp
			$this->common_model->db_insert_batch("nd_barang_warna_temp", $data_temp);
			//compare sama tabel tutup buku
			$get_result = $this->common_model->get_tutup_buku_non_barang($tahun);
			$idx = 0;
			foreach ($get_result as $row) {
				$dt_bw_insert[$idx] = array(
					'tahun' => $tanggal,
					'barang_id' => $row->barang_id,
					'warna_id' => $row->warna_id );
				$idx++;
			}

			//hasilnya insert
			if ($idx > 0) {
				$this->common_model->db_insert_batch('nd_tutup_buku_detail', $dt_bw_insert);
			}

			// jangan lupa truncate table temp
			$this->common_model->db_free_query_superadmin("TRUNCATE nd_barang_warna_temp");
			
		}

		//akhirnya update data tutup_buku_detail;
		$this->common_model->db_insert('nd_tutup_buku', $data_tutup_buku);
		$this->common_model->db_free_query_superadmin("UPDATE nd_tutup_buku_detail 
			SET ".$bulan."_harga = 
			$case
			END;
			");

		redirect(is_setting_link('inventory/mutasi_persediaan_barang').'?tanggal='.$tanggal_link."&gudang_id=0" );

		// print_r($get_result);
		// print_r($data);

	}

	function mutasi_persediaan_barang_nilai_excel(){
		
		$tanggal = $this->input->get('tanggal_start');
		$tanggal_end = $this->input->get('tanggal_end');
		$toko_id = $this->input->get('toko_id');
		$gudang_id = $this->input->get('gudang_id');
		$tanggal_print = date('d F Y',strtotime($tanggal) );
		$tanggal_print_end = date('d F Y',strtotime($tanggal_end) );
		// echo $tanggal_print;
		
		$tanggal_start = $tanggal;
		$tanggal_awal = '2018-01-01';
		$stok_opname_id = 0;
		$getOpname = $this->common_model->db_select("nd_stok_opname where tanggal <= '".$tanggal_end."' order by tanggal desc limit 1");
		foreach ($getOpname as $row) {
			$tanggal_awal = $row->tanggal;
			$stok_opname_id = $row->id;
		}
		// $mutasi_barang_list = $this->inv_model->mutasi_persediaan_barang($tanggal_awal, $tanggal,$tanggal_end, $gudang_id, $stok_opname_id); 
		
		$tanggal_before = date("Y-m-d", strtotime($tanggal_start. "-1 month")); 
		$bulan_tutup_buku = date("m", strtotime($tanggal_before)); 
		$tahun_tutup_buku = date("Y", strtotime($tanggal_before));
		$kolom_harga = $bulan_tutup_buku.'_harga';
		// echo $tanggal_before;
		$get_tutup_buku = $this->common_model->db_select("nd_tutup_buku where MONTH(tanggal) ='".$bulan_tutup_buku."' AND YEAR(tanggal) ='".$tahun_tutup_buku."'");
		$tutup_buku_id = 0;
		foreach ($get_tutup_buku as $row) {
			$tutup_buku_id = $row->id;
		}

		$mutasi_barang_list = $this->inv_model->mutasi_persediaan_barang_global($tanggal_awal, $tanggal ,$tanggal_end , $stok_opname_id, $tutup_buku_id, $kolom_harga); 

		$cek_gudang = $this->common_model->db_select('nd_gudang where id ='.$gudang_id);
		foreach ($cek_gudang as $row) {
			$nama_gudang = $row->nama;
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

		$objPHPExcel->getActiveSheet()->setCellValue('A1', ' Mutasi Persediaan Barang ');
		$objPHPExcel->getActiveSheet()->setCellValue('A2', 'Tanggal : '.$tanggal_print.' sd '.$tanggal_print_end);

		
		$objPHPExcel->getActiveSheet()->mergeCells("A4:A5");
		$objPHPExcel->getActiveSheet()->mergeCells("B4:B5");
		$objPHPExcel->getActiveSheet()->mergeCells("C4:C5");
		
		$objPHPExcel->getActiveSheet()->mergeCells("D4:F4");
		$objPHPExcel->getActiveSheet()->mergeCells("G4:I4");
		$objPHPExcel->getActiveSheet()->mergeCells("J4:L4");
		// $objPHPExcel->getActiveSheet()->mergeCells("M4:O4");
		// $objPHPExcel->getActiveSheet()->mergeCells("P4:R4");
		$objPHPExcel->getActiveSheet()->mergeCells("M4:O4");
		$objPHPExcel->getActiveSheet()->mergeCells("P4:R4");
		$objPHPExcel->getActiveSheet()->mergeCells("S4:U4");
		$objPHPExcel->getActiveSheet()->mergeCells("V4:X4");
		$objPHPExcel->getActiveSheet()->mergeCells("Y4:AA4");

		
		$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('A4', 'No')
		->setCellValue('B4', 'Nama Barang')
		->setCellValue('C4', 'Harga Satuan')
		->setCellValue('D4', 'Stok PER ('.strtoupper(date('01 M Y', strtotime($tanggal))).')' )
		->setCellValue('G4', 'Pembelian')
		->setCellValue('J4', 'Penjualan')
		// ->setCellValue('M4', 'Mutasi Masuk')
		// ->setCellValue('P4', 'Mutasi Keluar')
		->setCellValue('M4', 'Penyesuaian')
		->setCellValue('P4', 'RETUR JUAL')
		->setCellValue('S4', 'RETUR BELI')
		->setCellValue('V4', 'Pengeluaran Lain2')
		->setCellValue('Y4', 'Saldo Akhir')

		->setCellValue('D5', 'Yard')
		->setCellValue('E5', 'Roll')
		->setCellValue('F5', 'Nilai')
		
		->setCellValue('G5', 'Yard')
		->setCellValue('H5', 'Roll')
		->setCellValue('I5', 'Nilai')

		->setCellValue('J5', 'Yard')
		->setCellValue('K5', 'Roll')
		->setCellValue('L5', 'Nilai')

		->setCellValue('M5', 'Yard')
		->setCellValue('N5', 'Roll')
		->setCellValue('O5', 'Nilai')

		->setCellValue('P5', 'Yard')
		->setCellValue('Q5', 'Roll')
		->setCellValue('R5', 'Nilai')

		->setCellValue('S5', 'Yard')
		->setCellValue('T5', 'Roll')
		->setCellValue('U5', 'Nilai')


		->setCellValue('V5', 'Yard')
		->setCellValue('W5', 'Roll')
		->setCellValue('X5', 'Nilai')


		->setCellValue('Y5', 'Yard')
		->setCellValue('Z5', 'Roll')
		->setCellValue('AA5', 'Nilai')

		;
	

		$row_no = 6;
		$idx = 1;
		foreach ($mutasi_barang_list as $row) {
			$coll = 'A';

			$total_qty = 0;
			$total_roll = 0;

			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$idx);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(7);
			$coll++;

			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$row->nama_jual.' '.$row->warna_jual);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(30);
			$coll++;


			$coll_hpp = $coll;
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $row->hpp);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;

			$coll_qty = $coll;
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $row->qty_stock);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $row->jumlah_roll_stock);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, "=".$coll_hpp.$row_no.'*'.$coll_qty.$row_no );
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;


			$total_nilai =($row->hpp * $row->qty_stock) + ($row->hpp_beli * $row->qty_beli);
			$total_qty_stock = $row->qty_stock + $row->qty_beli;
			if ($total_qty_stock == 0) {
			 	$total_qty_stock = 1;
			}
			$hpp_all = $total_nilai / $total_qty_stock;
			$hpp_all = number_format($hpp_all,'2','.','');

			$hpp_beli = $row->hpp_beli;
			if ($row->hpp_beli == '') {
				$hpp_beli = 0;
			}

			//=====================================beli=============================

			$coll_qty = $coll;
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $row->qty_beli);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $row->jumlah_roll_beli);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, "=".$hpp_beli.'*'.$coll_qty.$row_no );
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			// echo $row->hpp_beli.'*'.$coll_qty.$row_no.'<br/>';
			$coll++;


			//=====================================penjualan=============================

			$coll_qty = $coll;
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $row->qty_jual);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $row->jumlah_roll_jual);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;
			
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, "=".$hpp_all.'*'.$coll_qty.$row_no );
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;


			//=====================================Penyesuaian=============================

			$coll_qty = $coll;
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $row->qty_penyesuaian);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $row->jumlah_roll_penyesuaian);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, "=".$hpp_all.'*'.$coll_qty.$row_no );
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;


			//=====================================retur=============================
			$coll_qty = $coll;
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $row->qty_retur);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $row->jumlah_roll_retur);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, "=".$hpp_all.'*'.$coll_qty.$row_no );
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;

			//=====================================retur jual=============================
			$coll_qty = $coll;
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $row->qty_retur_beli);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $row->jumlah_roll_retur_beli);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, "=".$hpp_all.'*'.$coll_qty.$row_no );
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;

			
			//=====================================stok lain=============================

			$coll_qty = $coll;
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $row->qty_lain);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $row->jumlah_roll_lain);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, "=".$hpp_all.'*'.$coll_qty.$row_no );
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;



			//=====================================nilai akhir=============================
			$coll_qty = $coll;
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $row->qty_stock + $row->qty_beli - $row->qty_jual + $row->qty_mutasi_masuk - $row->qty_mutasi + $row->qty_retur - $row->qty_retur_beli - $row->qty_lain + $row->qty_penyesuaian);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;

			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $row->jumlah_roll_stock + $row->jumlah_roll_beli - $row->jumlah_roll_jual + $row->jumlah_roll_mutasi_masuk -  $row->jumlah_roll_mutasi + $row->jumlah_roll_retur - $row->jumlah_roll_retur_beli + $row->jumlah_roll_penyesuaian - $row->jumlah_roll_lain);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;

			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, "=(Y".$row_no."*".$hpp_all.")");
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;

			/*$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $row->barang_id);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(10);
			$coll++;

			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $row->warna_id);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(10);
			$coll++;*/
			

			$coll++;
			$row_no++;
			$idx++;			

		}

		// $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		//ob_end_clean();
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		ob_end_clean();


		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header("Content-Disposition: attachment;filename=mutasi_persediaan_barang_all_".$tanggal_print.".xls");
		header('Cache-Control: max-age=0');
		$objWriter->save('php://output');
	}

	function mutasi_persediaan_barang_excel(){
		
		$tanggal = $this->input->get('tanggal_start');
		$tanggal_end = $this->input->get('tanggal_end');
		$toko_id = $this->input->get('toko_id');
		$gudang_id = $this->input->get('gudang_id');
		$tanggal_print = date('d F Y',strtotime($tanggal) );
		$tanggal_print_end = date('d F Y',strtotime($tanggal_end) );
		// echo $tanggal_print;
		
		$tanggal_awal = '2018-01-01';
		$stok_opname_id = 0;
		$getOpname = $this->common_model->db_select("nd_stok_opname where tanggal <= '".$tanggal_end."' order by tanggal desc limit 1");
		foreach ($getOpname as $row) {
			$tanggal_awal = $row->tanggal;
			$stok_opname_id = $row->id;
		}
		$mutasi_barang_list = $this->inv_model->mutasi_persediaan_barang($tanggal_awal, $tanggal,$tanggal_end, $gudang_id, $stok_opname_id); 
		
		$cek_gudang = $this->common_model->db_select('nd_gudang where id ='.$gudang_id);
		foreach ($cek_gudang as $row) {
			$nama_gudang = $row->nama;
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

		$objPHPExcel->getActiveSheet()->setCellValue('A1', ' Mutasi Persediaan Barang ');
		$objPHPExcel->getActiveSheet()->setCellValue('A2', 'Tanggal : '.$tanggal_print.' sd '.$tanggal_print_end);

		
		$objPHPExcel->getActiveSheet()->mergeCells("A4:A5");
		$objPHPExcel->getActiveSheet()->mergeCells("B4:B5");
		$objPHPExcel->getActiveSheet()->mergeCells("C4:C5");
		
		$objPHPExcel->getActiveSheet()->mergeCells("C4:D4");
		$objPHPExcel->getActiveSheet()->mergeCells("E4:F4");
		$objPHPExcel->getActiveSheet()->mergeCells("G4:H4");
		$objPHPExcel->getActiveSheet()->mergeCells("I4:J4");
		$objPHPExcel->getActiveSheet()->mergeCells("K4:L4");
		$objPHPExcel->getActiveSheet()->mergeCells("M4:N4");
		$objPHPExcel->getActiveSheet()->mergeCells("O4:P4");
		$objPHPExcel->getActiveSheet()->mergeCells("Q4:R4");
		$objPHPExcel->getActiveSheet()->mergeCells("S4:T4");
		$objPHPExcel->getActiveSheet()->mergeCells("U4:V4");

		
		$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('A4', 'No')
		// ->setCellValue('B4', 'Nama Barang')
		// ->setCellValue('C4', 'Harga Satuan')
		->setCellValue('C4', 'Stok PER ('.strtoupper(date('01 M Y', strtotime($tanggal))).')' )
		->setCellValue('E4', 'Pembelian')
		->setCellValue('G4', 'Penjualan')
		->setCellValue('I4', 'Mutasi Masuk')
		->setCellValue('K4', 'Mutasi Keluar')
		->setCellValue('M4', 'Penyesuaian')
		->setCellValue('O4', 'RETUR Jual')
		->setCellValue('Q4', 'RETUR Beli')
		->setCellValue('S4', 'Lain2')
		->setCellValue('U4', 'Sakdo Akhir')

		->setCellValue('C5', 'Yard')
		->setCellValue('D5', 'Roll')
		// ->setCellValue('F5', 'Nilai')
		
		->setCellValue('E5', 'Yard')
		->setCellValue('F5', 'Roll')
		// ->setCellValue('I5', 'Nilai')

		->setCellValue('G5', 'Yard')
		->setCellValue('H5', 'Roll')
		// ->setCellValue('L5', 'Nilai')

		->setCellValue('I5', 'Yard')
		->setCellValue('J5', 'Roll')
		// ->setCellValue('O5', 'Nilai')

		->setCellValue('K5', 'Yard')
		->setCellValue('L5', 'Roll')
		// ->setCellValue('R5', 'Nilai')

		->setCellValue('M5', 'Yard')
		->setCellValue('N5', 'Roll')
		// ->setCellValue('U5', 'Nilai')

		->setCellValue('O5', 'Yard')
		->setCellValue('P5', 'Roll')
		// ->setCellValue('X5', 'Nilai')

		->setCellValue('Q5', 'Yard')
		->setCellValue('R5', 'Roll')

		->setCellValue('S5', 'Yard')
		->setCellValue('T5', 'Roll')

		->setCellValue('U5', 'Yard')
		->setCellValue('V5', 'Roll')
		//->setCellValue('X5', 'Roll')
		//->setCellValue('Y5', 'Roll')

		;
	

		$row_no = 6;
		$idx = 1;
		foreach ($mutasi_barang_list as $row) {
			$coll = 'A';

			$total_qty = 0;
			$total_roll = 0;

			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$idx);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(7);
			$coll++;

			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$row->nama_jual.' '.$row->warna_jual);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(30);
			$coll++;


			$coll_hpp = $coll;
			// $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $row->hpp);
			// $objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			// $objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			// $coll++;

			$coll_qty = $coll;
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $row->qty_stock);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $row->jumlah_roll_stock);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;
			// $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, "=".$coll_hpp.$row_no.'*'.$coll_qty.$row_no );
			// $objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			// $objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			// $coll++;


			$total_nilai =($row->hpp * $row->qty_stock) + ($row->hpp_beli * $row->qty_beli);
			$total_qty_stock = $row->qty_stock + $row->qty_beli;
			if ($total_qty_stock == 0) {
			 	$total_qty_stock = 1;
			}
			$hpp_all = $total_nilai / $total_qty_stock;

			$hpp_beli = $row->hpp_beli;
			if ($row->hpp_beli == '') {
				$hpp_beli = 0;
			}

			$coll_qty = $coll;
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $row->qty_beli);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $row->jumlah_roll_beli);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;
			// $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, "=".$hpp_beli.'*'.$coll_qty.$row_no );
			// $objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			// $objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			// echo $row->hpp_beli.'*'.$coll_qty.$row_no.'<br/>';
			// $coll++;


			$coll_qty = $coll;
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $row->qty_jual);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $row->jumlah_roll_jual);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;
			// $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, "=".$hpp_all.'*'.$coll_qty.$row_no );
			// $objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			// $objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			// $coll++;

			$coll_qty = $coll;
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $row->qty_mutasi_masuk);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $row->jumlah_roll_mutasi_masuk);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;
			// $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, "=".$hpp_all.'*'.$coll_qty.$row_no );
			// $objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			// $objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			// $coll++;

			$coll_qty = $coll;
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $row->qty_mutasi);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $row->jumlah_roll_mutasi);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;
			// $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, "=".$hpp_all.'*'.$coll_qty.$row_no );
			// $objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			// $objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			// $coll++;

			$coll_qty = $coll;
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $row->qty_penyesuaian);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $row->jumlah_roll_penyesuaian);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;
			// $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, "=".$hpp_all.'*'.$coll_qty.$row_no );
			// $objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			// $objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			// $coll++;

			$coll_qty = $coll;
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $row->qty_retur);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $row->jumlah_roll_retur);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;

			$coll_qty = $coll;
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $row->qty_retur_beli);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $row->jumlah_roll_retur_beli);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;

			$coll_qty = $coll;
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $row->qty_lain);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $row->jumlah_roll_lain);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;
			// $objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, "=".$hpp_all.'*'.$coll_qty.$row_no );
			// $objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			// $objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			// $coll++;

			$coll_qty = $coll;
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $row->qty_stock + $row->qty_beli - $row->qty_jual + $row->qty_mutasi_masuk - $row->qty_mutasi + $row->qty_retur - $row->qty_retur_beli - $row->qty_lain + $row->qty_penyesuaian);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;

			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $row->jumlah_roll_stock + $row->jumlah_roll_beli - $row->jumlah_roll_jual + $row->jumlah_roll_mutasi_masuk -  $row->jumlah_roll_mutasi + $row->jumlah_roll_retur - $row->jumlah_roll_retur_beli -$row->jumlah_roll_lain + $row->jumlah_roll_penyesuaian);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;

			/*$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $row->barang_id);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(10);
			$coll++;

			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $row->warna_id);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(10);
			$coll++;*/
			

			$coll++;
			$row_no++;
			$idx++;			

		}

		// $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		//ob_end_clean();
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		ob_end_clean();


		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header("Content-Disposition: attachment;filename=mutasi_persediaan_barang_".$nama_gudang."_".$tanggal_print.".xls");
		header('Cache-Control: max-age=0');
		$objWriter->save('php://output');
	}

//=================================input mutasi barang baru=================================

	function mutasi_stok_awal(){
		$menu = is_get_url($this->uri->segment(1)) ;
		
		$data = array(
			'content' =>'admin/inventory/stok_barang_awal',
			'breadcrumb_title' => 'Stok',
			'breadcrumb_small' => 'Mutasi Awal',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data'=> $this->data,
			'stok_awal_list' => $this->inv_model->get_stok_awal()
			);

		if ($this->session->flashdata('data_double')) {
			$data["data_double"] = "benar";
		}else{
			$data["data_double"] = "tidak";
		}
		// $data['stok_barang'] = $this->inv_model->get_stok_awal();
		$this->load->view('admin/template',$data);
	}

	function mutasi_stok_awal_insert(){
		$ini = $this->input;
		$gudang_id = $ini->post('gudang_id');
		$warna_id = $ini->post('warna_id');
		$barang_id = $ini->post('barang_id');
		$post_id = $ini->post('id');
		$result = $this->common_model->db_select("nd_penyesuaian_stok where gudang_id=".$gudang_id." AND barang_id=".$barang_id." AND warna_id =".$warna_id." limit 1");

		$data = array(
			'barang_id' => $ini->post('barang_id'),
			'warna_id' => $ini->post('warna_id'),
			'gudang_id' => $ini->post('gudang_id'),
			'tipe_transaksi' => $ini->post('tipe_transaksi'),
			'tanggal' => '2018-12-31',
			'qty' => $ini->post('qty'),
			'jumlah_roll' => $ini->post('jumlah_roll'),
			'user_id' => is_user_id() );

		if ($post_id != '') {
			$this->common_model->db_update('nd_penyesuaian_stok', $data, 'id',$post_id);
		}else{
			$id = '';
			foreach ($result as $row) {
				$id = $row->id;
			}
			

			if ($id == '') {
				$this->common_model->db_insert('nd_penyesuaian_stok', $data);
			}else{
				$data = array(
					'Error' => "Error");
				$this->session->set_flashdata("data_double",$data);
			}
		}

		redirect(is_setting_link('inventory/mutasi_stok_awal'));
	}

	function mutasi_stok_awal_update(){
		$ini = $this->input;
		$id = $this->input->post('id');
		$data = array(
			'qty' => $ini->post('qty'),
			'jumlah_roll' => $ini->post('jumlah_roll'),
			'user_id' => is_user_id() );

		$this->common_model->db_update('nd_penyesuaian_stok', $data,'id',$id);
		echo 'OK';
	}

	function mutasi_stok_awal_delete(){
		$id = $this->input->get('id');
		// echo $id;
		$this->common_model->db_delete('nd_penyesuaian_stok', 'id',$id);
		redirect(is_setting_link('inventory/mutasi_stok_awal'));

	}

//==========================================================================================

	function stok_awal_harga(){
		$menu = is_get_url($this->uri->segment(1)) ;
		
		$data = array(
			'content' =>'admin/inventory/stok_barang_awal_harga',
			'breadcrumb_title' => 'Stok',
			'breadcrumb_small' => 'Harga Stok Awal',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data'=> $this->data,
			'stok_awal_list' => $this->inv_model->get_harga_stok_awal()
			);

		$this->load->view('admin/template',$data);
	}

	function stok_awal_harga_init(){
		$get_data = $this->inv_model->get_harga_stok_by_barang();
		$barang_id = '';
		// foreach ($get_data as $row) {
		// 	if ($barang_id != $row->barang_id) {
		// 		echo '<hr/>';
		// 		echo $row->nama_barang.' '.$row->nama_beli.' : <br/>';
		// 		$barang_id = $row->barang_id;
		// 	}
		// 	echo "INSERT INTO nd_stok_awal_item_harga (barang_id, warna_id, harga_stok_awal, user_id) VALUES ($row->barang_id, $row->warna_id,0,1);"." $row->nama_warna <br/>";
		// }
	}

	function harga_stok_awal_update(){
		$ini = $this->input;
		$barang_id = $this->input->post('barang_id');
		$warna_id = $this->input->post('warna_id');
		$id = '';
		$get_check = $this->common_model->db_select('nd_stok_awal_item_harga where barang_id ='.$barang_id." AND warna_id =".$warna_id);
		foreach ($get_check as $row) {
			$id = $row->id;
		}
		$data = array(
			'barang_id' => $barang_id,
			'warna_id' => $warna_id,
			'harga_stok_awal' => str_replace('.', '', $ini->post('harga_stok_awal')),
			'user_id' => is_user_id() );

		if ($id == '') {
			$this->common_model->db_insert('nd_stok_awal_item_harga', $data);
		}else{
			$this->common_model->db_update('nd_stok_awal_item_harga', $data,'id',$id);
		}

		echo 'OK';
	}

//=================================input stok opname=================================

	function stok_opname(){
		$menu = is_get_url($this->uri->segment(1)) ;
		
		$data = array(
			'content' =>'admin/inventory/stok_opname',
			'breadcrumb_title' => 'Stok',
			'breadcrumb_small' => 'Stok Opnames',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data'=> $this->data,
			);

		$data['stok_opname_list'] = $this->common_model->db_select('nd_stok_opname');
		$this->load->view('admin/template',$data);
	}

	function stok_opname_insert(){
		$data = array(
			'tanggal' => is_date_formatter($this->input->post('tanggal')),
			'barang_id_so' => $this->input->post('barang_id_so'),
			'warna_id_so' => $this->input->post('warna_id_so'),
			'gudang_id_so' => $this->input->post('gudang_id_so'),
			'user_id' => is_user_id(),
			'created' => date('Y-m-d H:i:s') );
		$result_id = $this->common_model->db_insert('nd_stok_opname',$data);
		redirect(is_setting_link('inventory/stok_opname_detail').'?id='.$result_id);
	}

	function stok_opname_detail(){
		$menu = is_get_url($this->uri->segment(1)) ;
		$id = $this->input->get('id');
		$cond_barang = '';
		$barang_id_filter = '';

		$barang_id_so = '';
		$warna_id_so = '';
		$gudang_id_so = '';
		$get = $this->common_model->db_select("nd_stok_opname where id=$id");
		foreach ($get as $row) {
			$barang_id_so = $row->barang_id_so;
			$warna_id_so = $row->warna_id_so;
			$gudang_id_so = $row->gudang_id_so;
			$status_aktif = $row->status_aktif;
		}
		if ($this->input->get('barang_id_filter')) {
			$cond_barang = "AND t1.barang_id =".$this->input->get('barang_id_filter');
			$barang_id_filter = $this->input->get('barang_id_filter');
		}
		$stok_opname_data = $this->common_model->db_select('nd_stok_opname where id='.$id);
		foreach ($stok_opname_data as $row) {
			$tanggal_so = $row->tanggal;
		}
		$data = array(
			'content' =>'admin/inventory/stok_opname_detail',
			'breadcrumb_title' => 'Stok',
			'breadcrumb_small' => 'Daftar Barang Stok Opnames',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data'=> $this->data,
			'stok_opname_data' => $stok_opname_data,
			'stok_opname_id' => $id,
			'barang_id_filter' => $barang_id_filter,
			'barang_id_so' => $barang_id_so,
			'warna_id_so' => $warna_id_so,
			'gudang_id_so' => $gudang_id_so,
			'tanggal_so' => is_reverse_date($tanggal_so),
			'id' => $id,
			'status_aktif' => $status_aktif
			);	

		if ($this->session->flashdata('data_double')) {
			$data["data_double"] = "benar";
		}else{
			$data["data_double"] = "tidak";
		}
		$select = '';
		$select_before = '';
		foreach ($this->gudang_list_aktif as $row) {
			$select .= ", SUM( if(gudang_id=".$row->id.", ifnull(qty,0),0))  as ".$row->nama."_qty , SUM( if(gudang_id=".$row->id.", jumlah_roll, 0 )  )  as ".$row->nama."_roll ";
			$select_before .= ", SUM( if(gudang_id=".$row->id.", ifnull(qty_masuk,0), 0 ) ) - SUM( if(gudang_id=".$row->id.", ifnull(qty_keluar,0), 0 ) )  as ".$row->nama."_beforeqty , SUM( if(gudang_id=".$row->id.", jumlah_roll_masuk, 0 ) ) - SUM( if(gudang_id=".$row->id.", jumlah_roll_keluar, 0 ) )  as ".$row->nama."_beforeroll ";
		}

		$tanggal_awal = '2018-01-01';
		$get_stok_opname_before = $this->common_model->db_select("nd_stok_opname where tanggal <'".$tanggal_so."' ORDER BY tanggal desc LIMIT 1");
		foreach ($get_stok_opname_before as $row) {
			$tanggal_awal = $row->tanggal;
		}

		$data['nama_stok_barang'] = $this->inv_model->get_nama_stok_barang();
		// $data['stok_opname_detail'] = $this->inv_model->get_stok_opname_detail($id, $select, $select_before, $tanggal_awal, $tanggal_so, $cond_barang);
		$this->load->view('admin/template',$data);
	}

	function stok_opname_lock(){
		$id = $this->input->post('id');
		$data = array('status_aktif' => 1 );
		$this->common_model->db_update("nd_stok_opname",$data,'id', $id);
		echo $id;
	}

	function get_data_stok_opname_detail(){
		$stok_opname_id = $this->input->post('stok_opname_id');
		$barang_id = $this->input->post('barang_id');
		$warna_id = $this->input->post('warna_id');

		$result = $this->common_model->db_select("nd_stok_opname_detail where stok_opname_id=$stok_opname_id and barang_id = $barang_id and warna_id = $warna_id");
		echo json_encode($result);
	}

	function stok_opname_detail_insert(){
		$stok_opname_id = $this->input->post('stok_opname_id');
		$barang_id = $this->input->post('barang_id');
		$warna_id = $this->input->post('warna_id');
		$gudang_id = $this->input->post('gudang_id');
		$rekap = explode('--', $this->input->post('rekap_qty'));
		foreach ($rekap as $key => $value) {
			$dt = explode('??', $value);
			if (isset($dt[2]) && $dt[2] == 0) {
				if ($dt[0] != '' && $dt[0] != null) {
					$data[$key] = array(
						'stok_opname_id' => $stok_opname_id ,
						'barang_id' => $barang_id,
						'warna_id' => $warna_id,
						'gudang_id' => $gudang_id,
						'qty' => $dt[0],
						'jumlah_roll' => $dt[1]
						);
				}
			}else if(isset($dt[2]) && $dt[2] != 0){
				$data_update = array(
					'stok_opname_id' => $stok_opname_id ,
					'barang_id' => $barang_id,
					'warna_id' => $warna_id,
					'gudang_id' => $gudang_id,
					'qty' => $dt[0],
					'jumlah_roll' => $dt[1]
					);

				if($dt[0] != 0 && $dt[0] != ''){
					$this->common_model->db_update('nd_stok_opname_detail',$data_update,'id', $dt[2]);
				}else{
					$this->common_model->db_delete('nd_stok_opname_detail','id', $dt[2]);
				}
			}
		}

		if (isset($data)) {
			$this->common_model->db_insert_batch('nd_stok_opname_detail', $data);
		}

		$result = $this->common_model->db_select("nd_stok_opname_detail where stok_opname_id=$stok_opname_id and barang_id = $barang_id and warna_id = $warna_id and gudang_id=$gudang_id");

		// print_r($data);
		echo json_encode($result);
	}

	function stok_opname_overview(){
		$menu = is_get_url($this->uri->segment(1)) ;
		$id = $this->input->get('id');
		$cond_barang = '';
		$barang_id_filter = '';

		if ($this->input->get('barang_id_filter')) {
			$cond_barang = "AND t1.barang_id =".$this->input->get('barang_id_filter');
			$barang_id_filter = $this->input->get('barang_id_filter');
		}
		$stok_opname_data = $this->common_model->db_select('nd_stok_opname where id='.$id);
		foreach ($stok_opname_data as $row) {
			$tanggal_so = $row->tanggal;
		}
		$data = array(
			'content' =>'admin/inventory/stok_opname_overview',
			'breadcrumb_title' => 'Stok',
			'breadcrumb_small' => 'Daftar Barang Stok Opname',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data'=> $this->data,
			'stok_opname_data' => $stok_opname_data,
			'stok_opname_id' => $id,
			'barang_id_filter' => $barang_id_filter
			);	

		if ($this->session->flashdata('data_double')) {
			$data["data_double"] = "benar";
		}else{
			$data["data_double"] = "tidak";
		}
		$select = '';
		$select_before = '';
		foreach ($this->gudang_list_aktif as $row) {
			$select .= ", SUM( if(gudang_id=".$row->id.", ifnull(qty,0),0))  as ".$row->nama."_qty , SUM( if(gudang_id=".$row->id.", jumlah_roll, 0 )  )  as ".$row->nama."_roll ";
			$select_before .= ", SUM( if(gudang_id=".$row->id.", ifnull(qty_masuk,0), 0 ) ) - SUM( if(gudang_id=".$row->id.", ifnull(qty_keluar,0), 0 ) )  as ".$row->nama."_beforeqty , SUM( if(gudang_id=".$row->id.", jumlah_roll_masuk, 0 ) ) - SUM( if(gudang_id=".$row->id.", jumlah_roll_keluar, 0 ) )  as ".$row->nama."_beforeroll ";
		}

		$tanggal_awal = '2018-01-01';
		$get_stok_opname_before = $this->common_model->db_select("nd_stok_opname where tanggal <'".$tanggal_so."' ORDER BY tanggal desc LIMIT 1");
		foreach ($get_stok_opname_before as $row) {
			$tanggal_awal = $row->tanggal;
		}

		$data['nama_stok_barang'] = $this->inv_model->get_nama_stok_barang();
		$data['stok_opname_detail'] = $this->inv_model->get_stok_opname_detail($id, $select, $select_before, $tanggal_awal, $tanggal_so, $cond_barang);
		$this->load->view('admin/template',$data);
	}

	function stok_opname_overview_with_before(){
		$menu = is_get_url($this->uri->segment(1)) ;
		$id = $this->input->get('id');
		$cond_barang = '';
		$barang_id_filter = '';
		$tipe_view = 1;

		
		if ($this->input->get('barang_id_filter')) {
			$cond_barang = "AND t1.barang_id =".$this->input->get('barang_id_filter');
			$barang_id_filter = $this->input->get('barang_id_filter');
		}

		if ($this->input->get('tipe_view')) {
			$tipe_view = $this->input->get('tipe_view');
		}
		$stok_opname_data = $this->common_model->db_select('nd_stok_opname where id='.$id);
		$data = array(
			'content' =>'admin/inventory/stok_opname_overview_with_before',
			'breadcrumb_title' => 'Stok',
			'breadcrumb_small' => 'Daftar Barang Stok Opname',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data'=> $this->data,
			'stok_opname_data' => $stok_opname_data,
			'stok_opname_id' => $id,
			'barang_id_filter' => $barang_id_filter,
			'tipe_view' => $tipe_view
			);	

		$select = '';
		$select_before = '';
		foreach ($this->gudang_list_aktif as $row) {
			$select .= ", SUM( if(gudang_id=".$row->id.", ifnull(qty,0),0))  as ".$row->id."_qty , SUM( if(gudang_id=".$row->id.", jumlah_roll, 0 )  )  as ".$row->id."_roll ";
			$select_before .= ", SUM( if(gudang_id=".$row->id.", ifnull(qty_before,0), 0 ) ) as ".$row->id."_beforeqty , SUM( if(gudang_id=".$row->id.", jumlah_roll_before, 0 ) )  as ".$row->id."_beforeroll ";
		}

		// $data['nama_stok_barang'] = $this->inv_model->get_nama_stok_barang();
		$data['stok_opname_detail_with_before'] = $this->inv_model->get_stok_opname_detail_with_before($id, $select, $select_before, $cond_barang);
		$this->load->view('admin/template',$data);
	}

	function stok_opname_overview_with_before_excel(){
		$select = '';
		$select_before = '';
		foreach ($this->gudang_list_aktif as $row) {
			$select .= ", SUM( if(gudang_id=".$row->id.", ifnull(qty,0),0))  as ".$row->id."_qty , SUM( if(gudang_id=".$row->id.", jumlah_roll, 0 )  )  as ".$row->id."_roll ";
			$select_before .= ", SUM( if(gudang_id=".$row->id.", ifnull(qty_before,0), 0 ) ) as ".$row->id."_beforeqty , SUM( if(gudang_id=".$row->id.", jumlah_roll_before, 0 ) )  as ".$row->id."_beforeroll ";
		}
		$id = $this->input->get('id');
		$cond_barang = '';
		$stok_opname_detail_with_before = $this->inv_model->get_stok_opname_detail_with_before($id, $select, $select_before, $cond_barang);
		
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

		$objPHPExcel->getActiveSheet()->mergeCells("A4:A5");
		$objPHPExcel->getActiveSheet()->mergeCells("B4:B5");
		$objPHPExcel->getActiveSheet()->mergeCells("C4:C5");
		

		$coll = 'D'; $coll_next = 'E';
		foreach ($this->gudang_list_aktif as $row) {
			$objPHPExcel->getActiveSheet()->mergeCells($coll."4:".$coll_next."4");
			$objPHPExcel->getActiveSheet()->setCellValue($coll.'4',$row->nama);
			
			$objPHPExcel->getActiveSheet()->setCellValue($coll.'5','Yard/Kg');
			$objPHPExcel->getActiveSheet()->setCellValue($coll_next.'5','Jumlah Roll');
			$coll++;
			$coll_next++;
			$coll++;
			$coll_next++;
			$objPHPExcel->getActiveSheet()->setCellValue($coll.'5','Yard/Kg');
			$objPHPExcel->getActiveSheet()->setCellValue($coll_next.'5','Jumlah Roll');
			
			$objPHPExcel->getActiveSheet()->getStyle($coll."4:".$coll_next."5")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

			$coll++;
			$coll_next++;
			$coll++;
			$coll_next++;
		}

		
		// $objPHPExcel->getActiveSheet()->mergeCells($coll."4:".$coll_next."4");
		// $objPHPExcel->getActiveSheet()->setCellValue($coll.'4',"TOTAL");
		// $objPHPExcel->getActiveSheet()->getStyle($coll."4:".$coll_next."5")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		// $objPHPExcel->getActiveSheet()->setCellValue($coll.'5','Yard/Kg');
		// $objPHPExcel->getActiveSheet()->setCellValue($coll_next.'5','Jumlah Roll');
		
		$objPHPExcel->getActiveSheet()->mergeCells("A1:".$coll_next."1");
		$objPHPExcel->getActiveSheet()->mergeCells("A2:".$coll_next."2");

		$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('A1', ' STOK BARANG ')
		->setCellValue('A4', 'No')
		->setCellValue('B4', 'Nama')
		->setCellValue('C4', 'Warna')
		;
	

		$row_no = 6;
		$idx = 1;
		foreach ($stok_opname_detail_with_before as $row) {
			$coll = "A";
			
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$idx);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(7);
			$coll++;

			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$row->nama_jual);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(30);
			$coll++;

			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$row->warna_jual);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(30);
			$coll++;

			$subtotal_qty = 0;
			$subtotal_roll = 0;
			$subtotal_qty_before = 0;
			$subtotal_roll_before = 0;

			foreach ($this->gudang_list_aktif as $isi) { 

				$qty = $isi->id.'_qty';
				$roll = $isi->id.'_roll';

				$qty_before = $isi->id.'_beforeqty';
				$roll_before = $isi->id.'_beforeroll';

				$subtotal_qty += $row->$qty;
				$subtotal_roll += $row->$roll;

				$subtotal_qty_before += $row->$qty_before;
				$subtotal_roll_before += $row->$roll_before;

				
				$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, number_format($row->$qty_before,'2','.',''));
				$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
				$coll++;

				$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$row->$roll_before);
				$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
				$coll++;

				$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, number_format($row->$qty,'2','.',''));
				$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
				$coll++;

				$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$row->$roll);
				$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
				$coll++;
				
			}

			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$subtotal_qty);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;

			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$subtotal_roll);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;

			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$subtotal_qty_before);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;

			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$subtotal_roll_before);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;

			$row_no++;
			$idx++;

		}

		// $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		//ob_end_clean();
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		ob_end_clean();	


		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header("Content-Disposition: attachment;filename=Stok_Barang_Sebelum_Opname.xls");
		header('Cache-Control: max-age=0');
		$objWriter->save('php://output');
	}

	function update_stok_opname_tanggal(){
		$stok_opname_id = $this->input->post('stok_opname_id');
		$data = array(
			'tanggal' => is_date_formatter($this->input->post('tanggal')) );

		$this->common_model->db_update('nd_stok_opname',$data,'id',$stok_opname_id);
		echo "OK";

	}

	function update_stok_opname_barang(){
		$ini = $this->input;
		$stok_opname_id = $ini->post('stok_opname_id');
		$gudang_id = $ini->post('gudang_id');
		$warna_id = $ini->post('warna_id');
		$barang_id = $ini->post('barang_id');
		$data_qty = str_replace(',', '?', $this->input->post('qty'));
		$data_qty = str_replace('.', '', $data_qty);
		$qty = str_replace('?', '.', $data_qty);

		$data = array(
			'gudang_id' => $gudang_id ,
			'warna_id' => $warna_id,
			'barang_id' => $barang_id,
			'qty' => $qty,
			'stok_opname_id' => $stok_opname_id,
			'jumlah_roll' => $this->input->post('jumlah_roll')
			);

		$id = '';
		$getData = $this->common_model->db_select("nd_stok_opname_detail where stok_opname_id = ".$stok_opname_id." AND barang_id = ".$barang_id." AND warna_id =".$warna_id." AND gudang_id=".$gudang_id);
		foreach ($getData as $row) {
			$id = $row->id;
		}

		if ($id == '') {
			$this->common_model->db_insert('nd_stok_opname_detail', $data);
		}else{
			$this->common_model->db_update('nd_stok_opname_detail', $data,'id',$id);
		}

		echo "OK";
	}

//=====================================tutup buku=============================================


	

}