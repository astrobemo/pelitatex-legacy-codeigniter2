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

	function cek_pin($pin){
		$baris = $this->common_model->db_select_num_rows("nd_user where posisi_id < 3 and status_aktif = 1 and PIN is not null AND PIN ='".$pin."' limit 1");
		if ($baris == 1) {
			return true;
		}else{
			return false;
		}
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

	function get_total_stok()
	{
		$select = "";
		$select2 = "";
		$select_status = '';
		$select_v2 = '';
		$select_all = "";
		$columnSelect = array();
		$dt[0] = 'urutan';
		$dt[1] = 'nama_barang_jual';
		$dt[2] = 'status_aktif';
		$dt[3] = 'last_edit';
		$idx = 4;
		$tanggal = is_date_formatter($this->input->post('tanggal'));
		$i = 0;
		$cond_qty = "";
		$new_sum = "";
		foreach ($this->gudang_list_aktif as $row) {

			$select_v2 .= ", ROUND(
			SUM( if(gudang_id=".$row->id.", 
					ifnull(
						if(tanggal_stok is not null, if(time_stamp >= tanggal_stok,qty_masuk,0) , qty_masuk  ),
					0) ,
				0 )
			),2) - 
			ROUND(SUM( if(gudang_id=".$row->id.", 
					ifnull( 
						if(tanggal_stok is not null, if(time_stamp >= tanggal_stok, qty_keluar,0 ), qty_keluar ) 
					,0), 
				0 )
			),
			2)  
			as gudang_".$row->id."_qty ,

			SUM( if(gudang_id=".$row->id.", 
					if(tanggal_stok is not null, 
						if(time_stamp >= tanggal_stok,jumlah_roll_masuk,0 ), 
					jumlah_roll_masuk), 
				0 ) 
			) -
			SUM( if(gudang_id=".$row->id.", 
					if(tanggal_stok is not null,
						if(time_stamp >= tanggal_stok, jumlah_roll_keluar, 0)
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

			$new_sum .= ", SUM(ifnull(gudang_".$row->id."_qty,0)) as total_".$row->id."_qty";
			$new_sum .= ", SUM(ifnull(gudang_".$row->id."_roll,0)) as total_".$row->id."_roll";
		}

		$dt[$idx] = 'qty_total';
		$idx++;
		$dt[$idx] = 'roll_total';
		$idx++;

		$select2 .= ', if(tipe_qty != 3,'.implode('+', $qty_add).',"0") as qty_total, '.implode('+', $roll_add).' as roll_total';

		// $rResult = $this->inv_model->get_stok_barang_list_ajax_2($aColumns, $sWhere, "order by urutan asc", $sLimit, $select_v2, $tanggal.' 23:59:59', $stok_opname_id, $tanggal_awal, $select2);

		// $tanggal = is_date_formatter($this->input->get("tanggal"));
        // $tanggal_awal = '2018-01-01';
		// $getOpname = $this->common_model->db_select("nd_stok_opname where tanggal <= '".$tanggal."' order by tanggal desc limit 1");
		

		$tanggal_awal = '2018-01-01';

		$getTutupBuku = $this->common_model->db_select("nd_tutup_buku_stok where tanggal <= '".$tanggal." 23:59:59' order by tanggal desc limit 1");
		$tahun_tutup_buku = '2018';
		$bulan_qty = '01';
		foreach ($getTutupBuku as $row) {
			$tahun_tutup_buku = date('Y', strtotime($row->tanggal));
			$bulan_qty = date('m',strtotime($row->tanggal));
			$tanggal_awal = $row->tanggal;
			// $stok_opname_id = $row->id;
		}

		$get = $this->inv_model->get_total_stok($select_v2, $tanggal.' 23:59:59',  $tanggal_awal, $select2, $new_sum, $tahun_tutup_buku, $bulan_qty);
		// $get = $this->inv_model->get_total_stok_legacy($select_v2, $tanggal.' 23:59:59',  $tanggal_awal, $select2, $new_sum);

		if (is_posisi_id()==1) {
			// echo $select_v2.'<hr/>'. $tanggal.' 23:59:59'.'<hr/>'.  $tanggal_awal.'<hr/>'. $select2.'<hr/>'.$new_sum;
		}

		echo json_encode($get);
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
        $stok_opname_id = 1;
        $getOpname = $this->common_model->db_select("nd_stok_opname where tanggal <= '".$tanggal."' order by tanggal desc limit 1");
        // foreach ($getOpname as $row) {
        //     $tanggal_awal = $row->tanggal;
        //     $stok_opname_id = $row->id;
        // }

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
        	// $data['stok_barang'] = array();
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
	        $tanggal_awal = '2018-01-01';
	        $stok_opname_id = 0;
	        $getOpname = $this->common_model->db_select("nd_stok_opname where tanggal <= '".$tanggal."' order by tanggal desc limit 1");
	        foreach ($getOpname as $row) {
	            // $tanggal_awal = $row->tanggal;
	            $stok_opname_id = $row->id;
	        }
        //================================================================

    	$batch_id_list = array();
        if ($barang_id != '') {
        	$cond_barang = "WHERE barang_id = ".$barang_id;
	        $data['data_set'] = $this->inv_model->get_stok_ppo($barang_id, $tanggal, $tanggal_awal, $stok_opname_id, $cond_barang, ($ppo_lock_id != ''? $ppo_lock_id : 0 ));
	    	if (is_posisi_id() == 1) {
				$data['data_set'] = $this->inv_model->get_stok_ppo2($barang_id, $tanggal, $tanggal_awal, $stok_opname_id, $cond_barang, ($ppo_lock_id != ''? $ppo_lock_id : 0 ));
			}else{
				$data['data_set'] = $this->inv_model->get_stok_ppo($barang_id, $tanggal, $tanggal_awal, $stok_opname_id, $cond_barang, ($ppo_lock_id != ''? $ppo_lock_id : 0 ));
			}
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

			$cond_barang2 = "AND barang_id = $barang_id";
			// echo $select;
			// echo $cond_barang;
			// echo "<hr/>";
			
			$select = $this->generate_select_versi_2();
			$data['stok_barang'] = $this->inv_model->get_stok_barang_list_by_barang_temp_2_barang($tanggal_awal, $select, $tanggal, $cond_barang2, $stok_opname_id); 
			
        }else{
	        $data['data_set'] = array();
        	$data['batch_for_pre_po'] = array();
        	$data['stok_awal'] = array();
        	$data['jual'] = array();
        	$data['list_stok'] = array();
        	$data['stok_barang'] = array();
        	$data['list_stok_by_tanggal'] = array();
        	$data['ppo_lock_list'] = array();
        	$data['po_pembelian_untuk_ppo'] = array();
        }

        // if (is_posisi_id() == 1) {
        	$data['content'] = "admin/inventory/stok_barang_ppo_4";
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
            // $tanggal_awal = $row->tanggal;
            // $stok_opname_id = $row->id;
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
		// $getOpname = $this->common_model->db_select("nd_stok_opname where tanggal <= '".$tanggal."' order by tanggal desc limit 1");
		// foreach ($getOpname as $row) {
		// 	$tanggal_awal = $row->tanggal;
		// 	$stok_opname_id = $row->id;
		// }
		$tgl_lalu = '';
		$select = '';
		foreach ($this->gudang_list_aktif as $row) {
			$select .= ", SUM( if(gudang_id=".$row->id.", ifnull(qty_masuk,0), 0 ) ) - SUM( if(gudang_id=".$row->id.", ifnull(qty_keluar,0), 0 ) )  as gudang_".$row->id."_qty , if(tipe_qty != 3,SUM( if(gudang_id=".$row->id.", jumlah_roll_masuk, 0 ) ) - SUM( if(gudang_id=".$row->id.", jumlah_roll_keluar, 0 ) ),0)  as gudang_".$row->id."_roll ";
		}
		if ($print_mode == 1) {

			$select = '';
			$select_v2 = '';
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

			$select = $this->generate_select();

			// $select_v2 = $this->generate_select_versi_2();

			foreach ($this->gudang_list_aktif as $row) {

				$select_v2 .= ", ROUND(
					SUM( if(gudang_id=".$row->id.", 
							ifnull(
								if(tanggal_stok is not null, if(time_stamp >= tanggal_stok,qty_masuk,0) , qty_masuk  ),
							0) ,
						0 )
					),2) - 
					ROUND(SUM( if(gudang_id=".$row->id.", 
							ifnull( 
								if(tanggal_stok is not null, if(time_stamp >= tanggal_stok, qty_keluar,0 ), qty_keluar ) 
							,0), 
						0 )
					),
					2)  
					as gudang_".$row->id."_qty ,

					SUM( if(gudang_id=".$row->id.", 
							if(tanggal_stok is not null, 
								if(time_stamp >= tanggal_stok,jumlah_roll_masuk,0 ), 
							jumlah_roll_masuk), 
						0 ) 
					) -
					SUM( if(gudang_id=".$row->id.", 
							if(tanggal_stok is not null,
								if(time_stamp >= tanggal_stok, jumlah_roll_keluar, 0)
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
			
			//get_stok_barang_list($select, $tanggal, $tanggal_awal, $stok_opname_id); 
			$data['stok_barang'] = $this->inv_model->get_stok_barang_list_nonajax_new_2($select_v2, $tanggal.' 23:59:59', $stok_opname_id, $tanggal_awal, $select2, $tgl_lalu, $cond_qty, $cond_filter); 
			// $res = $this->common_model->db_free_query_superadmin("CALL get_stock('".$tanggal.' 23:59:59'."')");
			// $data['stok_barang'] = $res->result(); 

			$data['stok_opname_id'] = $stok_opname_id;
		}else{			
			// $data['stok_barang_total'] = $this->inv_model->get_stok_barang_list_total($select, $tanggal, $tanggal_awal, $stok_opname_id); 
			$data['stok_barang_total'] = array(); 
		}

		if (is_posisi_id() != 1) {
			$this->load->view('admin/template',$data);
		}else{
			
			if($this->input->get('vt') == 1){

				// echo $this->queryStok($select_v2, $tanggal.' 23:59:59', $stok_opname_id, $tanggal_awal, $select2, $tgl_lalu, $cond_qty, $cond_filter);
				// echo $select_v2.'<hr/>'. $tanggal.'<hr/>'. $stok_opname_id, $tanggal_awal, $select2, $tgl_lalu, $cond_qty, $cond_filter;
				echo "<hr/><table>";
				echo "<tr>";
				echo "<th></th>";
				echo "<th></th>";
				echo "<th></th>";
				foreach ($this->gudang_list_aktif as $row2) {
					echo "<th>".$row2->id.' '.$row2->nama."QTY </th>";
					echo "<th>".$row2->id.' '.$row2->nama."ROLL </th>";
				}
				echo "</tr>";
				foreach ($data['stok_barang'] as $row) {
					echo "<tr>";
					echo "<td>".$row->nama_barang_jual."</td>";
					echo "<td>".$row->nama_warna_jual."</td>";
					echo "<td>".$row->barang_id."</td>";
					echo "<td>".$row->warna_id."</td>";
					foreach ($this->gudang_list_aktif as $row2) {
						$qty = 'gudang_'.$row2->id.'_qty';
						$roll = 'gudang_'.$row2->id.'_roll';
						echo "<td>".$row->$qty."</td>";
						echo "<td>".$row->$roll."</td>";
					}
					echo "</tr>";
				};
				echo "</table>";
			}else{

				$this->load->view('admin/template',$data);
			}


		}
		
	}

	function queryStok($select, $tanggal_start,$stok_opname_id, $tanggal_awal, $select2, $tanggal_filter, $qty_cond, $cond_filter)
	{

		$tanggal_start = '$tanggal_start';
		echo  "SELECT CAST(concat(urutan_barang,LPAD(urutan,3,'0')) as UNSIGNED) as urutan,(tbl_b.nama_jual) as nama_barang_jual, if(tbl_b.status_aktif = 1,'aktif', 'non aktif') as status_barang, satuan_id, tbl_c.warna_jual as nama_warna_jual
			$select2
			,tbl_a.*
		FROM(
			SELECT barang_id, warna_id 
			$select
			,MAX(tanggal) as last_edit
			FROM (
				(
					SELECT barang_id, warna_id, t1.gudang_id, 0 as qty_masuk, 0 as jumlah_roll_masuk, subqty as qty_keluar, subjumlah_roll as jumlah_roll_keluar,3 as tipe, tanggal, t1.id, ifnull(closed_date, t2.created_at) as time_stamp
					FROM nd_penjualan_detail t1
					LEFT JOIN (
						SELECT *
						FROM nd_penjualan
						WHERE closed_date <= '$tanggal_start'
						AND closed_date >= '$tanggal_awal'
						AND status_aktif = 1
						) t2
					ON t1.penjualan_id = t2.id
					where t2.id is not null
				)UNION(
					SELECT barang_id, warna_id, t1.gudang_id, 0 as qty_masuk, 0 as jumlah_roll_masuk, qty as qty_keluar, jumlah_roll as jumlah_roll_keluar,20 as tipe, tanggal, t3.id, t2.created_at
					FROM nd_pengeluaran_stok_lain_detail t1
					LEFT JOIN (
						SELECT *
						FROM nd_pengeluaran_stok_lain
						WHERE created_at <= '$tanggal_start'
						AND created_at >= '$tanggal_awal'
						AND status_aktif = 1
						) t2
					ON t1.pengeluaran_stok_lain_id = t2.id
					LEFT JOIN (
						SELECT qty, jumlah_roll, pengeluaran_stok_lain_detail_id, id
						FROM nd_pengeluaran_stok_lain_qty_detail
						) t3
					ON t3.pengeluaran_stok_lain_detail_id = t1.id
					where t2.id is not null
				)UNION(
					SELECT barang_id, warna_id, t2.gudang_id, qty, jumlah_roll, 0, 0, 1, tanggal, qty_id, created_at
					FROM (
						SELECT qty, jumlah_roll, tA.id, barang_id, warna_id, pembelian_id, id as qty_id
						FROM nd_pembelian_detail tA
						ORDER BY pembelian_id
					) t1
					LEFT JOIN (
						SELECT *
						FROM nd_pembelian
						WHERE created_at <= '$tanggal_start'
						AND created_at >= '$tanggal_awal'
						AND status_aktif = 1
						) t2
					ON t1.pembelian_id = t2.id
					WHERE t2.id is not null
				)UNION(
					SELECT barang_id, warna_id, gudang_id_after, qty, jumlah_roll, 0, 0,2, tanggal, id, created_at
					FROM nd_mutasi_barang t1
					WHERE created_at <= '$tanggal_start'
					AND created_at >= '$tanggal_awal'
					AND status_aktif = 1
				)UNION(
					SELECT barang_id, warna_id, t1.gudang_id, qty, jumlah_roll, 0, 0, 4, tanggal, t3.id, created_at
					FROM nd_retur_jual_detail t1
					LEFT JOIN (
						SELECT *
						FROM nd_retur_jual
						WHERE created_at <= '$tanggal_start'
						AND created_at >= '$tanggal_awal'
						AND status_aktif = 1
						) t2
					ON t1.retur_jual_id = t2.id
					LEFT JOIN (
						SELECT sum(qty*if(jumlah_roll=0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, retur_jual_detail_id, id
						FROM nd_retur_jual_qty
						GROUP BY retur_jual_detail_id
						) t3
					ON t3.retur_jual_detail_id = t1.id
					WHERE t2.id is not null
				)UNION(
					SELECT barang_id, warna_id, t1.gudang_id, 0, 0,qty, jumlah_roll, 19 ,  tanggal, t1.id, created_at
					FROM nd_retur_beli_detail t1
					LEFT JOIN (
						SELECT *
						FROM nd_retur_beli
						WHERE created_at <= '$tanggal_start'
						AND created_at >= '$tanggal_awal'
						AND status_aktif = 1
						) t2
					ON t1.retur_beli_id = t2.id
					LEFT JOIN (
						SELECT sum(qty*if(jumlah_roll=0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, retur_beli_detail_id, id
						FROM nd_retur_beli_qty
						GROUP BY retur_beli_detail_id
						) t3
					ON t3.retur_beli_detail_id = t1.id
					WHERE t2.id is not null
				)UNION(
					SELECT barang_id, warna_id, gudang_id, qty, jumlah_roll, 0, 0,5, tanggal, id, created_at
					FROM nd_penyesuaian_stok
					WHERE tipe_transaksi = 0
					AND created_at <= '$tanggal_start'
					AND created_at >= '$tanggal_awal'
				)UNION(
					SELECT barang_id, warna_id, gudang_id, if(tipe_transaksi = 1,qty, 0 ), if(tipe_transaksi = 1,jumlah_roll,0 ), if(tipe_transaksi = 2,qty, 0 ), if(tipe_transaksi=2,jumlah_roll,0),6, tanggal, id, created_at
					FROM nd_penyesuaian_stok
					WHERE created_at <= '$tanggal_start'
					AND created_at >= '$tanggal_awal'
					AND tipe_transaksi != 0
				)UNION(
					SELECT barang_id, warna_id, gudang_id_before, 0, 0 , qty, jumlah_roll,8, tanggal, id, created_at
					FROM nd_mutasi_barang t1
					WHERE created_at <= '$tanggal_start'	
					AND created_at >= '$tanggal_awal'
					AND status_aktif = 1
				)UNION(
					SELECT barang_id, warna_id, gudang_id, qty, jumlah_roll, 0, 0, 9, tanggal, t1.id, created_at
					FROM (
						SELECT barang_id, warna_id, gudang_id, sum(qty * jumlah_roll) as qty, sum(jumlah_roll) jumlah_roll, stok_opname_id, id
						FROM nd_stok_opname_detail
						WHERE warna_id > 0
						GROUP BY barang_id, warna_id, gudang_id, stok_opname_id
						) t1 
					LEFT JOIN (
						SELECT *
						FROM nd_stok_opname
						WHERE created_at <= '$tanggal_start'	
						AND created_at >= '$tanggal_awal'
						AND status_aktif = 1
						) t2
					ON t1.stok_opname_id = t2.id
					WHERE t2.id is not null
				)
			)t1
			LEFT JOIN (
				SELECT barang_id as barang_id_stok, warna_id as warna_id_stok,gudang_id as gudang_id_stok, MAX(created_at) as tanggal_stok
				FROM (
					SELECT stok_opname_id, barang_id,warna_id, gudang_id
					FROM nd_stok_opname_detail
						WHERE warna_id > 0
					GROUP BY barang_id, warna_id, gudang_id, stok_opname_id
				) tA
				LEFT JOIN (
					SELECT *
					FROM nd_stok_opname
					WHERE status_aktif = 1
					AND created_at <= '$tanggal_start'
				) tB
				ON tA.stok_opname_id = tB.id
				WHERE tB.id is not null
				GROUP BY barang_id, warna_id, gudang_id
			) t2
			ON t1.barang_id = t2.barang_id_stok
			AND t1.warna_id = t2.warna_id_stok
			AND t1.gudang_id = t2.gudang_id_stok
			LEFT JOIN (
				SELECT barang_id as barang_id_penyesuaian, warna_id as warna_id_penyesuaian,gudang_id as gudang_id_penyesuaian, MAX(created_at) as tanggal_penyesuaian
				FROM nd_penyesuaian_stok
				WHERE created_at <= '$tanggal_start'
				AND tipe_transaksi != 3
				GROUP BY barang_id, warna_id, gudang_id
				) t3
			ON t1.barang_id = t3.barang_id_penyesuaian
			AND t1.warna_id = t3.warna_id_penyesuaian
			AND t1.gudang_id = t3.gudang_id_penyesuaian
			GROUP BY barang_id, warna_id
		) tbl_a
		LEFT JOIN (
			SELECT @rownum:=@rownum+1 urutan_barang,id, nama, nama_jual, status_aktif, satuan_id, tipe_qty
			FROM nd_barang, (SELECT @rownum:=0) r
			ORDER BY nama_jual asc
			) tbl_b
		ON tbl_a.barang_id = tbl_b.id
		LEFT JOIN (
			SELECT @rownum:=@rownum+1 urutan,id, warna_jual, warna_beli
			FROM nd_warna, (SELECT @rownum:=0) r
			ORDER BY warna_jual asc
			) tbl_c
		ON tbl_a.warna_id = tbl_c.id
		LEFT JOIN nd_satuan tbl_d
		ON tbl_b.satuan_id = tbl_d.id
		Where barang_id is not null
		AND last_edit >= '$tanggal_filter 00:00:00'
		$cond_filter
		ORDER BY urutan_barang, urutan";
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
				<br/>
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
				as gudang_".$row->id."_roll,<br/>
				concat('".$row->id."','/',barang_id,'/',warna_id) as gudang_".$row->id."_button,<br/>
				sum(if(gudang_id=".$row->id.",if(tanggal_stok is not null AND tanggal_penyesuaian is not null, if(tanggal_stok > tanggal_penyesuaian, 0 , 1 ) , if(tanggal_penyesuaian is not null, 1, 0) ),0)) as gudang_".$row->id."_status <br/>
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
		echo "<hr/>";
		echo $this->generate_select_versi_2();
		// print_r($this->common_model->db_select("blessingtdj_system.nd_barang"));

	}

	function data_select_inv2(){
		$select = '';
		$select_v2 = '';
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

		$select = $this->generate_select();

		// $select_v2 = $this->generate_select_versi_2();

		foreach ($this->gudang_list_aktif as $row) {

				$select_v2 .= ", ROUND(
				SUM( if(gudang_id=".$row->id.", 
						ifnull(
							if(tanggal_stok is not null, if(time_stamp >= tanggal_stok,qty_masuk,0) , qty_masuk  ),
						0) ,
					0 )
				),2) - 
				ROUND(SUM( if(gudang_id=".$row->id.", 
						ifnull( 
							if(tanggal_stok is not null, if(time_stamp >= tanggal_stok, qty_keluar,0 ), qty_keluar ) 
						,0), 
					0 )
				),
				2)  
				as gudang_".$row->id."_qty ,

				SUM( if(gudang_id=".$row->id.", 
						if(tanggal_stok is not null, 
							if(time_stamp >= tanggal_stok,jumlah_roll_masuk,0 ), 
						jumlah_roll_masuk), 
					0 ) 
				) -
				SUM( if(gudang_id=".$row->id.", 
						if(tanggal_stok is not null,
							if(time_stamp >= tanggal_stok, jumlah_roll_keluar, 0)
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
		
		echo $select_v2.'<hr/>'. $select2;
		
	}

	function generate_select(){
		$select = '';
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
			}

		return $select;

	}

	function generate_select_versi_2(){
		$select_v2 = '';
		foreach ($this->gudang_list_aktif as $row) {
			$select_v2 .= ", ROUND(
				SUM( if(gudang_id=".$row->id.", 
						ifnull(
							if(tanggal_stok is not null, if(time_stamp >= tanggal_stok,qty_masuk,0) , qty_masuk  ),
						0) ,
					0 )
				),2) - 
				ROUND(SUM( if(gudang_id=".$row->id.", 
						ifnull( 
							if(tanggal_stok is not null, if(time_stamp >= tanggal_stok, qty_keluar,0 ), qty_keluar ) 
						,0), 
					0 )
				),
				2)  
				as gudang_".$row->id."_qty ,

				SUM( if(gudang_id=".$row->id.", 
						if(tanggal_stok is not null, 
							if(time_stamp >= tanggal_stok,jumlah_roll_masuk,0 ), 
						jumlah_roll_masuk), 
					0 ) 
				) -
				SUM( if(gudang_id=".$row->id.", 
						if(tanggal_stok is not null,
							if(time_stamp >= tanggal_stok, jumlah_roll_keluar, 0)
						,jumlah_roll_keluar), 
					0 ) 
				)  
				as gudang_".$row->id."_roll,
				concat('".$row->id."','/',barang_id,'/',warna_id) as gudang_".$row->id."_button";
		}

		return $select_v2;
	}

	function generate_select_v3(){
		// $session_data = $this->session->userdata('do_filter');
		$select = '';
		$select_v2 = '';
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

		$select = $this->generate_select();

		// $select_v2 = $this->generate_select_versi_2();

		foreach ($this->gudang_list_aktif as $row) {

				$select_v2 .= ", ROUND(
				SUM( if(gudang_id=".$row->id.", 
						ifnull(
							if(tanggal_stok is not null, if(time_stamp >= tanggal_stok,qty_masuk,0) , qty_masuk  ),
						0) ,
					0 )
				),2) - 
				ROUND(SUM( if(gudang_id=".$row->id.", 
						ifnull( 
							if(tanggal_stok is not null, if(time_stamp >= tanggal_stok, qty_keluar,0 ), qty_keluar ) 
						,0), 
					0 )
				),
				2)  
				as gudang_".$row->id."_qty ,

				SUM( if(gudang_id=".$row->id.", 
						if(tanggal_stok is not null, 
							if(time_stamp >= tanggal_stok,jumlah_roll_masuk,0 ), 
						jumlah_roll_masuk), 
					0 ) 
				) -
				SUM( if(gudang_id=".$row->id.", 
						if(tanggal_stok is not null,
							if(time_stamp >= tanggal_stok, jumlah_roll_keluar, 0)
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

		echo $select;
		echo "<hr/>";
		echo $select2;

		/*SELECT *
					FROM (
						SELECT CAST(concat(urutan_barang,LPAD(urutan,3,'0')) as UNSIGNED) as urutan,concat(tbl_b.nama_jual,' ',warna_jual) as nama_barang_jual, if(tbl_b.status_aktif = 1,'aktif', 'non aktif') as status_aktif
						 , if(tipe_qty != 3,gudang_2_qty+gudang_1_qty+gudang_3_qty+gudang_5_qty+gudang_4_qty,"0") as qty_total, gudang_2_roll+gudang_1_roll+gudang_3_roll+gudang_5_roll+gudang_4_roll as roll_total,CONCAT(gudang_2_roll,'??',gudang_2_status) as gudang_2_data,CONCAT(gudang_1_roll,'??',gudang_1_status) as gudang_1_data,CONCAT(gudang_3_roll,'??',gudang_3_status) as gudang_3_data,CONCAT(gudang_5_roll,'??',gudang_5_status) as gudang_5_data,CONCAT(gudang_4_roll,'??',gudang_4_status) as gudang_4_data
						 ,tbl_a.*
						FROM(
							SELECT barang_id, warna_id 
							, ROUND(
				SUM( if(gudang_id=2, 
						ifnull(
							if(tanggal_stok is not null, if(time_stamp >= tanggal_stok,qty_masuk,0) , qty_masuk  ),
						0) ,
					0 )
				),2) - 
				ROUND(SUM( if(gudang_id=2, 
						ifnull( 
							if(tanggal_stok is not null, if(time_stamp >= tanggal_stok, qty_keluar,0 ), qty_keluar ) 
						,0), 
					0 )
				),
				2)  
				as gudang_2_qty ,

				SUM( if(gudang_id=2, 
						if(tanggal_stok is not null, 
							if(time_stamp >= tanggal_stok,jumlah_roll_masuk,0 ), 
						jumlah_roll_masuk), 
					0 ) 
				) -
				SUM( if(gudang_id=2, 
						if(tanggal_stok is not null,
							if(time_stamp >= tanggal_stok, jumlah_roll_keluar, 0)
						,jumlah_roll_keluar), 
					0 ) 
				)  
				as gudang_2_roll,
				concat('2','/',barang_id,'/',warna_id) as gudang_2_button,
				SUM(if(gudang_id=2,if(tanggal_stok is not null && tanggal_penyesuaian is not null, if(tanggal_stok > tanggal_penyesuaian, 0 , 1 ) , if(tanggal_penyesuaian is not null, 1, 0) ),0)) as gudang_2_status 
				, ROUND(
				SUM( if(gudang_id=1, 
						ifnull(
							if(tanggal_stok is not null, if(time_stamp >= tanggal_stok,qty_masuk,0) , qty_masuk  ),
						0) ,
					0 )
				),2) - 
				ROUND(SUM( if(gudang_id=1, 
						ifnull( 
							if(tanggal_stok is not null, if(time_stamp >= tanggal_stok, qty_keluar,0 ), qty_keluar ) 
						,0), 
					0 )
				),
				2)  
				as gudang_1_qty ,

				SUM( if(gudang_id=1, 
						if(tanggal_stok is not null, 
							if(time_stamp >= tanggal_stok,jumlah_roll_masuk,0 ), 
						jumlah_roll_masuk), 
					0 ) 
				) -
				SUM( if(gudang_id=1, 
						if(tanggal_stok is not null,
							if(time_stamp >= tanggal_stok, jumlah_roll_keluar, 0)
						,jumlah_roll_keluar), 
					0 ) 
				)  
				as gudang_1_roll,
				concat('1','/',barang_id,'/',warna_id) as gudang_1_button,
				SUM(if(gudang_id=1,if(tanggal_stok is not null && tanggal_penyesuaian is not null, if(tanggal_stok > tanggal_penyesuaian, 0 , 1 ) , if(tanggal_penyesuaian is not null, 1, 0) ),0)) as gudang_1_status 
				, ROUND(
				SUM( if(gudang_id=3, 
						ifnull(
							if(tanggal_stok is not null, if(time_stamp >= tanggal_stok,qty_masuk,0) , qty_masuk  ),
						0) ,
					0 )
				),2) - 
				ROUND(SUM( if(gudang_id=3, 
						ifnull( 
							if(tanggal_stok is not null, if(time_stamp >= tanggal_stok, qty_keluar,0 ), qty_keluar ) 
						,0), 
					0 )
				),
				2)  
				as gudang_3_qty ,

				SUM( if(gudang_id=3, 
						if(tanggal_stok is not null, 
							if(time_stamp >= tanggal_stok,jumlah_roll_masuk,0 ), 
						jumlah_roll_masuk), 
					0 ) 
				) -
				SUM( if(gudang_id=3, 
						if(tanggal_stok is not null,
							if(time_stamp >= tanggal_stok, jumlah_roll_keluar, 0)
						,jumlah_roll_keluar), 
					0 ) 
				)  
				as gudang_3_roll,
				concat('3','/',barang_id,'/',warna_id) as gudang_3_button,
				SUM(if(gudang_id=3,if(tanggal_stok is not null && tanggal_penyesuaian is not null, if(tanggal_stok > tanggal_penyesuaian, 0 , 1 ) , if(tanggal_penyesuaian is not null, 1, 0) ),0)) as gudang_3_status 
				, ROUND(
				SUM( if(gudang_id=5, 
						ifnull(
							if(tanggal_stok is not null, if(time_stamp >= tanggal_stok,qty_masuk,0) , qty_masuk  ),
						0) ,
					0 )
				),2) - 
				ROUND(SUM( if(gudang_id=5, 
						ifnull( 
							if(tanggal_stok is not null, if(time_stamp >= tanggal_stok, qty_keluar,0 ), qty_keluar ) 
						,0), 
					0 )
				),
				2)  
				as gudang_5_qty ,

				SUM( if(gudang_id=5, 
						if(tanggal_stok is not null, 
							if(time_stamp >= tanggal_stok,jumlah_roll_masuk,0 ), 
						jumlah_roll_masuk), 
					0 ) 
				) -
				SUM( if(gudang_id=5, 
						if(tanggal_stok is not null,
							if(time_stamp >= tanggal_stok, jumlah_roll_keluar, 0)
						,jumlah_roll_keluar), 
					0 ) 
				)  
				as gudang_5_roll,
				concat('5','/',barang_id,'/',warna_id) as gudang_5_button,
				SUM(if(gudang_id=5,if(tanggal_stok is not null && tanggal_penyesuaian is not null, if(tanggal_stok > tanggal_penyesuaian, 0 , 1 ) , if(tanggal_penyesuaian is not null, 1, 0) ),0)) as gudang_5_status 
				, ROUND(
				SUM( if(gudang_id=4, 
						ifnull(
							if(tanggal_stok is not null, if(time_stamp >= tanggal_stok,qty_masuk,0) , qty_masuk  ),
						0) ,
					0 )
				),2) - 
				ROUND(SUM( if(gudang_id=4, 
						ifnull( 
							if(tanggal_stok is not null, if(time_stamp >= tanggal_stok, qty_keluar,0 ), qty_keluar ) 
						,0), 
					0 )
				),
				2)  
				as gudang_4_qty ,

				SUM( if(gudang_id=4, 
						if(tanggal_stok is not null, 
							if(time_stamp >= tanggal_stok,jumlah_roll_masuk,0 ), 
						jumlah_roll_masuk), 
					0 ) 
				) -
				SUM( if(gudang_id=4, 
						if(tanggal_stok is not null,
							if(time_stamp >= tanggal_stok, jumlah_roll_keluar, 0)
						,jumlah_roll_keluar), 
					0 ) 
				)  
				as gudang_4_roll,
				concat('4','/',barang_id,'/',warna_id) as gudang_4_button,
				SUM(if(gudang_id=4,if(tanggal_stok is not null && tanggal_penyesuaian is not null, if(tanggal_stok > tanggal_penyesuaian, 0 , 1 ) , if(tanggal_penyesuaian is not null, 1, 0) ),0)) as gudang_4_status 
				
							,MAX(tanggal) as last_edit
							FROM (
								(
							        SELECT barang_id, warna_id, t1.gudang_id, 0 as qty_masuk, 0 as jumlah_roll_masuk, subqty as qty_keluar, subjumlah_roll as jumlah_roll_keluar,3 as tipe, tanggal, t1.id, ifnull(closed_date, t2.created_at) as time_stamp
							        FROM nd_penjualan_detail t1
							        LEFT JOIN (
							        	SELECT *
							        	FROM nd_penjualan
							        	WHERE created_at <= '2022-04-30 23:59:59'
							        	AND created_at >= '2018-01-01'
							        	AND status_aktif = 1
							        	) t2
							        ON t1.penjualan_id = t2.id
							        where t2.id is not null
							    )UNION(
							        SELECT barang_id, warna_id, t1.gudang_id, 0 as qty_masuk, 0 as jumlah_roll_masuk, qty as qty_keluar, jumlah_roll as jumlah_roll_keluar,20 as tipe, tanggal, t3.id, t2.created_at
							        FROM nd_pengeluaran_stok_lain_detail t1
							        LEFT JOIN (
							        	SELECT *
							        	FROM nd_pengeluaran_stok_lain
							        	WHERE created_at <= '2022-04-30 23:59:59'
							        	AND created_at >= '2018-01-01'
							        	AND status_aktif = 1
							        	) t2
							        ON t1.pengeluaran_stok_lain_id = t2.id
							        LEFT JOIN (
							            SELECT qty, jumlah_roll, pengeluaran_stok_lain_detail_id, id
							            FROM nd_pengeluaran_stok_lain_qty_detail
							            ) t3
							        ON t3.pengeluaran_stok_lain_detail_id = t1.id
							        where t2.id is not null
							    )UNION(
							        SELECT barang_id, warna_id, t2.gudang_id, qty, jumlah_roll, 0, 0, 1, tanggal, qty_id, created_at
							        FROM (
							        	SELECT qty, jumlah_roll, tA.id, barang_id, warna_id, pembelian_id, id as qty_id
							        	FROM nd_pembelian_detail tA
										ORDER BY pembelian_id
							        ) t1
							        LEFT JOIN (
							        	SELECT *
							        	FROM nd_pembelian
							        	WHERE created_at <= '2022-04-30 23:59:59'
							        	AND created_at >= '2018-01-01'
							        	AND status_aktif = 1
							        	) t2
							        ON t1.pembelian_id = t2.id
							        WHERE t2.id is not null
							    )UNION(
							    	SELECT barang_id, warna_id, gudang_id_after, qty, jumlah_roll, 0, 0,2, tanggal, id, created_at
						        	FROM nd_mutasi_barang t1
									WHERE created_at <= '2022-04-30 23:59:59'
						        	AND created_at >= '2018-01-01'
						        	AND status_aktif = 1
							    )UNION(
							    	SELECT barang_id, warna_id, t1.gudang_id, qty, jumlah_roll, 0, 0, 4, tanggal, t3.id, created_at
							        FROM nd_retur_jual_detail t1
							        LEFT JOIN (
							        	SELECT *
							        	FROM nd_retur_jual
							        	WHERE created_at <= '2022-04-30 23:59:59'
							        	AND created_at >= '2018-01-01'
							        	AND status_aktif = 1
							        	) t2
							        ON t1.retur_jual_id = t2.id
							        LEFT JOIN (
							            SELECT sum(qty*if(jumlah_roll=0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, retur_jual_detail_id, id
							            FROM nd_retur_jual_qty
							            GROUP BY retur_jual_detail_id
							            ) t3
							        ON t3.retur_jual_detail_id = t1.id
							        WHERE t2.id is not null
							    )UNION(
							    	SELECT barang_id, warna_id, t1.gudang_id, 0, 0,qty, jumlah_roll, 19 ,  tanggal, t1.id, created_at
							        FROM nd_retur_beli_detail t1
							        LEFT JOIN (
							        	SELECT *
							        	FROM nd_retur_beli
							        	WHERE created_at <= '2022-04-30 23:59:59'
							        	AND created_at >= '2018-01-01'
							        	AND status_aktif = 1
							        	) t2
							        ON t1.retur_beli_id = t2.id
							        LEFT JOIN (
							            SELECT sum(qty*if(jumlah_roll=0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, retur_beli_detail_id, id
							            FROM nd_retur_beli_qty
							            GROUP BY retur_beli_detail_id
							            ) t3
							        ON t3.retur_beli_detail_id = t1.id
							        WHERE t2.id is not null
							    )UNION(
							        SELECT barang_id, warna_id, gudang_id, qty, jumlah_roll, 0, 0,5, tanggal, id, created_at
						        	FROM nd_penyesuaian_stok
						        	WHERE tipe_transaksi = 0
						            AND created_at <= '2022-04-30 23:59:59'
						        	AND created_at >= '2018-01-01'
							    )UNION(
							        SELECT barang_id, warna_id, gudang_id, if(tipe_transaksi = 1,qty, 0 ), if(tipe_transaksi = 1,jumlah_roll,0 ), if(tipe_transaksi = 2,qty, 0 ), if(tipe_transaksi=2,jumlah_roll,0),6, tanggal, id, created_at
						        	FROM nd_penyesuaian_stok
						        	WHERE created_at <= '2022-04-30 23:59:59'
						        	AND created_at >= '2018-01-01'
						        	AND tipe_transaksi != 0
							    )UNION(
							    	SELECT barang_id, warna_id, gudang_id_before, 0, 0 , qty, jumlah_roll,8, tanggal, id, created_at
						        	FROM nd_mutasi_barang t1
									WHERE created_at <= '2022-04-30 23:59:59'	
								    AND created_at >= '2018-01-01'
						        	AND status_aktif = 1
							    )UNION(
							    	SELECT barang_id, warna_id, gudang_id, qty, jumlah_roll, 0, 0, 9, tanggal, t1.id, created_at
						        	FROM (
						        		SELECT barang_id, warna_id, gudang_id, sum(qty * jumlah_roll) as qty, sum(jumlah_roll) jumlah_roll, stok_opname_id, id
						        		FROM nd_stok_opname_detail
						        		WHERE warna_id > 0
						        		GROUP BY barang_id, warna_id, gudang_id, stok_opname_id
						        		) t1 
									LEFT JOIN (
										SELECT *
										FROM nd_stok_opname
										WHERE created_at <= '2022-04-30 23:59:59'	
									    AND created_at >= '2018-01-01'
							        	AND status_aktif = 1
										) t2
									ON t1.stok_opname_id = t2.id
									WHERE t2.id is not null
							    )
							)t1
							LEFT JOIN (
								SELECT barang_id as barang_id_stok, warna_id as warna_id_stok,gudang_id as gudang_id_stok, MAX(created_at) as tanggal_stok
							    FROM (
							    	SELECT stok_opname_id, barang_id,warna_id, gudang_id
							    	FROM nd_stok_opname_detail
						        		WHERE warna_id > 0
							    	GROUP BY barang_id, warna_id, gudang_id, stok_opname_id
								) tA
								LEFT JOIN (
									SELECT *
									FROM nd_stok_opname
									WHERE status_aktif = 1
									AND created_at <= '2022-04-30 23:59:59'
								) tB
								ON tA.stok_opname_id = tB.id
								WHERE tB.id is not null
								GROUP BY barang_id, warna_id, gudang_id
							) t2
							ON t1.barang_id = t2.barang_id_stok
							AND t1.warna_id = t2.warna_id_stok
							AND t1.gudang_id = t2.gudang_id_stok
							LEFT JOIN (
								SELECT barang_id as barang_id_penyesuaian, warna_id as warna_id_penyesuaian,gudang_id as gudang_id_penyesuaian, MAX(created_at) as tanggal_penyesuaian
							    FROM nd_penyesuaian_stok
								WHERE created_at <= '2022-04-30 23:59:59'
						    	AND tipe_transaksi != 3
								GROUP BY barang_id, warna_id, gudang_id
								) t3
							ON t1.barang_id = t3.barang_id_penyesuaian
							AND t1.warna_id = t3.warna_id_penyesuaian
							AND t1.gudang_id = t3.gudang_id_penyesuaian
							GROUP BY barang_id, warna_id
						) tbl_a
						LEFT JOIN (
							SELECT @rownum:=@rownum+1 urutan_barang,id, nama, nama_jual, status_aktif, satuan_id, tipe_qty
							FROM nd_barang, (SELECT @rownum:=0) r
							ORDER BY nama_jual asc
							) tbl_b
						ON tbl_a.barang_id = tbl_b.id
						LEFT JOIN (
							SELECT @rownum:=@rownum+1 urutan,id, warna_jual, warna_beli
							FROM nd_warna, (SELECT @rownum:=0) r
							ORDER BY warna_jual asc
							) tbl_c
						ON tbl_a.warna_id = tbl_c.id
						LEFT JOIN nd_satuan tbl_d
						ON tbl_b.satuan_id = tbl_d.id
						Where barang_id is not null
						AND last_edit >= '2022-03-09'
						OR (gudang_2_qty+gudang_1_qty+gudang_3_qty+gudang_5_qty+gudang_4_qty) > 0
						ORDER BY urutan_barang, urutan
				) A			
			
            -- ORDER BY nama_barang_jual, urutan asc
            LIMIT 100, 100
						*/

		/*
		SELECT *
					FROM (
						SELECT CAST(concat(urutan_barang,LPAD(urutan,3,'0')) as UNSIGNED) as urutan,concat(tbl_b.nama_jual,' ',warna_jual) as nama_barang_jual, if(tbl_b.status_aktif = 1,'aktif', 'non aktif') as status_aktif
						 , if(tipe_qty != 3,gudang_2_qty+gudang_1_qty+gudang_3_qty+gudang_5_qty+gudang_4_qty,"0") as qty_total, gudang_2_roll+gudang_1_roll+gudang_3_roll+gudang_5_roll+gudang_4_roll as roll_total,CONCAT(gudang_2_roll,'??',gudang_2_status) as gudang_2_data,CONCAT(gudang_1_roll,'??',gudang_1_status) as gudang_1_data,CONCAT(gudang_3_roll,'??',gudang_3_status) as gudang_3_data,CONCAT(gudang_5_roll,'??',gudang_5_status) as gudang_5_data,CONCAT(gudang_4_roll,'??',gudang_4_status) as gudang_4_data
						 ,tbl_a.*
						FROM(
							SELECT barang_id, warna_id 
							, ROUND(
				SUM( if(gudang_id=2, 
						ifnull(
							if(tanggal_stok is not null, if(time_stamp >= tanggal_stok,qty_masuk,0) , qty_masuk  ),
						0) ,
					0 )
				),2) - 
				ROUND(SUM( if(gudang_id=2, 
						ifnull( 
							if(tanggal_stok is not null, if(time_stamp >= tanggal_stok, qty_keluar,0 ), qty_keluar ) 
						,0), 
					0 )
				),
				2)  
				as gudang_2_qty ,

				SUM( if(gudang_id=2, 
						if(tanggal_stok is not null, 
							if(time_stamp >= tanggal_stok,jumlah_roll_masuk,0 ), 
						jumlah_roll_masuk), 
					0 ) 
				) -
				SUM( if(gudang_id=2, 
						if(tanggal_stok is not null,
							if(time_stamp >= tanggal_stok, jumlah_roll_keluar, 0)
						,jumlah_roll_keluar), 
					0 ) 
				)  
				as gudang_2_roll,
				concat('2','/',barang_id,'/',warna_id) as gudang_2_button,
				SUM(if(gudang_id=2,if(tanggal_stok is not null && tanggal_penyesuaian is not null, if(tanggal_stok > tanggal_penyesuaian, 0 , 1 ) , if(tanggal_penyesuaian is not null, 1, 0) ),0)) as gudang_2_status 
				, ROUND(
				SUM( if(gudang_id=1, 
						ifnull(
							if(tanggal_stok is not null, if(time_stamp >= tanggal_stok,qty_masuk,0) , qty_masuk  ),
						0) ,
					0 )
				),2) - 
				ROUND(SUM( if(gudang_id=1, 
						ifnull( 
							if(tanggal_stok is not null, if(time_stamp >= tanggal_stok, qty_keluar,0 ), qty_keluar ) 
						,0), 
					0 )
				),
				2)  
				as gudang_1_qty ,

				SUM( if(gudang_id=1, 
						if(tanggal_stok is not null, 
							if(time_stamp >= tanggal_stok,jumlah_roll_masuk,0 ), 
						jumlah_roll_masuk), 
					0 ) 
				) -
				SUM( if(gudang_id=1, 
						if(tanggal_stok is not null,
							if(time_stamp >= tanggal_stok, jumlah_roll_keluar, 0)
						,jumlah_roll_keluar), 
					0 ) 
				)  
				as gudang_1_roll,
				concat('1','/',barang_id,'/',warna_id) as gudang_1_button,
				SUM(if(gudang_id=1,if(tanggal_stok is not null && tanggal_penyesuaian is not null, if(tanggal_stok > tanggal_penyesuaian, 0 , 1 ) , if(tanggal_penyesuaian is not null, 1, 0) ),0)) as gudang_1_status 
				, ROUND(
				SUM( if(gudang_id=3, 
						ifnull(
							if(tanggal_stok is not null, if(time_stamp >= tanggal_stok,qty_masuk,0) , qty_masuk  ),
						0) ,
					0 )
				),2) - 
				ROUND(SUM( if(gudang_id=3, 
						ifnull( 
							if(tanggal_stok is not null, if(time_stamp >= tanggal_stok, qty_keluar,0 ), qty_keluar ) 
						,0), 
					0 )
				),
				2)  
				as gudang_3_qty ,

				SUM( if(gudang_id=3, 
						if(tanggal_stok is not null, 
							if(time_stamp >= tanggal_stok,jumlah_roll_masuk,0 ), 
						jumlah_roll_masuk), 
					0 ) 
				) -
				SUM( if(gudang_id=3, 
						if(tanggal_stok is not null,
							if(time_stamp >= tanggal_stok, jumlah_roll_keluar, 0)
						,jumlah_roll_keluar), 
					0 ) 
				)  
				as gudang_3_roll,
				concat('3','/',barang_id,'/',warna_id) as gudang_3_button,
				SUM(if(gudang_id=3,if(tanggal_stok is not null && tanggal_penyesuaian is not null, if(tanggal_stok > tanggal_penyesuaian, 0 , 1 ) , if(tanggal_penyesuaian is not null, 1, 0) ),0)) as gudang_3_status 
				, ROUND(
				SUM( if(gudang_id=5, 
						ifnull(
							if(tanggal_stok is not null, if(time_stamp >= tanggal_stok,qty_masuk,0) , qty_masuk  ),
						0) ,
					0 )
				),2) - 
				ROUND(SUM( if(gudang_id=5, 
						ifnull( 
							if(tanggal_stok is not null, if(time_stamp >= tanggal_stok, qty_keluar,0 ), qty_keluar ) 
						,0), 
					0 )
				),
				2)  
				as gudang_5_qty ,

				SUM( if(gudang_id=5, 
						if(tanggal_stok is not null, 
							if(time_stamp >= tanggal_stok,jumlah_roll_masuk,0 ), 
						jumlah_roll_masuk), 
					0 ) 
				) -
				SUM( if(gudang_id=5, 
						if(tanggal_stok is not null,
							if(time_stamp >= tanggal_stok, jumlah_roll_keluar, 0)
						,jumlah_roll_keluar), 
					0 ) 
				)  
				as gudang_5_roll,
				concat('5','/',barang_id,'/',warna_id) as gudang_5_button,
				SUM(if(gudang_id=5,if(tanggal_stok is not null && tanggal_penyesuaian is not null, if(tanggal_stok > tanggal_penyesuaian, 0 , 1 ) , if(tanggal_penyesuaian is not null, 1, 0) ),0)) as gudang_5_status 
				, ROUND(
				SUM( if(gudang_id=4, 
						ifnull(
							if(tanggal_stok is not null, if(time_stamp >= tanggal_stok,qty_masuk,0) , qty_masuk  ),
						0) ,
					0 )
				),2) - 
				ROUND(SUM( if(gudang_id=4, 
						ifnull( 
							if(tanggal_stok is not null, if(time_stamp >= tanggal_stok, qty_keluar,0 ), qty_keluar ) 
						,0), 
					0 )
				),
				2)  
				as gudang_4_qty ,

				SUM( if(gudang_id=4, 
						if(tanggal_stok is not null, 
							if(time_stamp >= tanggal_stok,jumlah_roll_masuk,0 ), 
						jumlah_roll_masuk), 
					0 ) 
				) -
				SUM( if(gudang_id=4, 
						if(tanggal_stok is not null,
							if(time_stamp >= tanggal_stok, jumlah_roll_keluar, 0)
						,jumlah_roll_keluar), 
					0 ) 
				)  
				as gudang_4_roll,
				concat('4','/',barang_id,'/',warna_id) as gudang_4_button,
				SUM(if(gudang_id=4,if(tanggal_stok is not null && tanggal_penyesuaian is not null, if(tanggal_stok > tanggal_penyesuaian, 0 , 1 ) , if(tanggal_penyesuaian is not null, 1, 0) ),0)) as gudang_4_status 
				
							,MAX(tanggal) as last_edit
							FROM (
								(
							        SELECT barang_id, warna_id, gudang_id, qty as qty_masuk, jumlah_roll as jumlah_roll_masuk, 0 as qty_keluar, 0 as jumlah_roll_keluar,'tb' as tipe, tanggal, id, 
									ifnull(time_stamp, t2.created_at) as time_stamp
							        FROM (
							        	SELECT id, tahun as tanggal,barang_id, warna_id, 06_qty as qty, 06_roll as jumlah_roll, concat(tahun,' ','23:59:59') as time_stamp, gudang_id
							        	FROM nd_tutup_buku_detail_gudang
							        	WHERE DATE_FORMAT(tahun,'%Y-%m') = '2022-06'
							        	) res
							        where barang_id is not null
							    )UNION(
							        SELECT barang_id, warna_id, t1.gudang_id, 0 as qty_masuk, 0 as jumlah_roll_masuk, subqty as qty_keluar, subjumlah_roll as jumlah_roll_keluar,3 as tipe, tanggal, t1.id, ifnull(closed_date, t2.created_at) as time_stamp
							        FROM nd_penjualan_detail t1
							        LEFT JOIN (
							        	SELECT *
							        	FROM nd_penjualan
							        	WHERE created_at <= '2022-06-30 23:59:59'
							        	AND created_at >= '2018-01-01'
							        	AND status_aktif = 1
							        	) t2
							        ON t1.penjualan_id = t2.id
							        where t2.id is not null
							    )UNION(
							        SELECT barang_id, warna_id, t1.gudang_id, 0 as qty_masuk, 0 as jumlah_roll_masuk, qty as qty_keluar, jumlah_roll as jumlah_roll_keluar,20 as tipe, tanggal, t3.id, t2.created_at
							        FROM nd_pengeluaran_stok_lain_detail t1
							        LEFT JOIN (
							        	SELECT *
							        	FROM nd_pengeluaran_stok_lain
							        	WHERE created_at <= '2022-06-30 23:59:59'
							        	AND created_at >= '2018-01-01'
							        	AND status_aktif = 1
							        	) t2
							        ON t1.pengeluaran_stok_lain_id = t2.id
							        LEFT JOIN (
							            SELECT qty, jumlah_roll, pengeluaran_stok_lain_detail_id, id
							            FROM nd_pengeluaran_stok_lain_qty_detail
							            ) t3
							        ON t3.pengeluaran_stok_lain_detail_id = t1.id
							        where t2.id is not null
							    )UNION(
							        SELECT barang_id, warna_id, t2.gudang_id, qty, jumlah_roll, 0, 0, 1, tanggal, qty_id, created_at
							        FROM (
							        	SELECT qty, jumlah_roll, tA.id, barang_id, warna_id, pembelian_id, id as qty_id
							        	FROM nd_pembelian_detail tA
										ORDER BY pembelian_id
							        ) t1
							        LEFT JOIN (
							        	SELECT *
							        	FROM nd_pembelian
							        	WHERE created_at <= '2022-06-30 23:59:59'
							        	AND created_at >= '2018-01-01'
							        	AND status_aktif = 1
							        	) t2
							        ON t1.pembelian_id = t2.id
							        WHERE t2.id is not null
							    )UNION(
							    	SELECT barang_id, warna_id, gudang_id_after, qty, jumlah_roll, 0, 0,2, tanggal, id, created_at
						        	FROM nd_mutasi_barang t1
									WHERE created_at <= '2022-06-30 23:59:59'
						        	AND created_at >= '2018-01-01'
						        	AND status_aktif = 1
							    )UNION(
							    	SELECT barang_id, warna_id, t1.gudang_id, qty, jumlah_roll, 0, 0, 4, tanggal, t3.id, created_at
							        FROM nd_retur_jual_detail t1
							        LEFT JOIN (
							        	SELECT *
							        	FROM nd_retur_jual
							        	WHERE created_at <= '2022-06-30 23:59:59'
							        	AND created_at >= '2018-01-01'
							        	AND status_aktif = 1
							        	) t2
							        ON t1.retur_jual_id = t2.id
							        LEFT JOIN (
							            SELECT sum(qty*if(jumlah_roll=0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, retur_jual_detail_id, id
							            FROM nd_retur_jual_qty
							            GROUP BY retur_jual_detail_id
							            ) t3
							        ON t3.retur_jual_detail_id = t1.id
							        WHERE t2.id is not null
							    )UNION(
							    	SELECT barang_id, warna_id, t1.gudang_id, 0, 0,qty, jumlah_roll, 19 ,  tanggal, t1.id, created_at
							        FROM nd_retur_beli_detail t1
							        LEFT JOIN (
							        	SELECT *
							        	FROM nd_retur_beli
							        	WHERE created_at <= '2022-06-30 23:59:59'
							        	AND created_at >= '2018-01-01'
							        	AND status_aktif = 1
							        	) t2
							        ON t1.retur_beli_id = t2.id
							        LEFT JOIN (
							            SELECT sum(qty*if(jumlah_roll=0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, retur_beli_detail_id, id
							            FROM nd_retur_beli_qty
							            GROUP BY retur_beli_detail_id
							            ) t3
							        ON t3.retur_beli_detail_id = t1.id
							        WHERE t2.id is not null
							    )UNION(
							        SELECT barang_id, warna_id, gudang_id, qty, jumlah_roll, 0, 0,5, tanggal, id, created_at
						        	FROM nd_penyesuaian_stok
						        	WHERE tipe_transaksi = 0
						            AND created_at <= '2022-06-30 23:59:59'
						        	AND created_at >= '2018-01-01'
							    )UNION(
							        SELECT barang_id, warna_id, gudang_id, if(tipe_transaksi = 1,qty, 0 ), if(tipe_transaksi = 1,jumlah_roll,0 ), if(tipe_transaksi = 2,qty, 0 ), if(tipe_transaksi=2,jumlah_roll,0),6, tanggal, id, created_at
						        	FROM nd_penyesuaian_stok
						        	WHERE created_at <= '2022-06-30 23:59:59'
						        	AND created_at >= '2018-01-01'
						        	AND tipe_transaksi != 0
							    )UNION(
							    	SELECT barang_id, warna_id, gudang_id_before, 0, 0 , qty, jumlah_roll,8, tanggal, id, created_at
						        	FROM nd_mutasi_barang t1
									WHERE created_at <= '2022-06-30 23:59:59'	
								    AND created_at >= '2018-01-01'
						        	AND status_aktif = 1
							    )UNION(
							    	SELECT barang_id, warna_id, gudang_id, qty, jumlah_roll, 0, 0, 9, tanggal, t1.id, created_at
						        	FROM (
						        		SELECT barang_id, warna_id, gudang_id, sum(qty * jumlah_roll) as qty, sum(jumlah_roll) jumlah_roll, stok_opname_id, id
						        		FROM nd_stok_opname_detail
						        		WHERE warna_id > 0
						        		GROUP BY barang_id, warna_id, gudang_id, stok_opname_id
						        		) t1 
									LEFT JOIN (
										SELECT *
										FROM nd_stok_opname
										WHERE created_at <= '2022-06-30 23:59:59'	
									    AND created_at >= '2018-01-01'
							        	AND status_aktif = 1
										) t2
									ON t1.stok_opname_id = t2.id
									WHERE t2.id is not null
							    )
							)t1
							LEFT JOIN (
								SELECT barang_id as barang_id_stok, warna_id as warna_id_stok,gudang_id as gudang_id_stok, MAX(created_at) as tanggal_stok
							    FROM (
							    	SELECT stok_opname_id, barang_id,warna_id, gudang_id
							    	FROM nd_stok_opname_detail
						        		WHERE warna_id > 0
							    	GROUP BY barang_id, warna_id, gudang_id, stok_opname_id
								) tA
								LEFT JOIN (
									SELECT *
									FROM nd_stok_opname
									WHERE status_aktif = 1
									AND created_at <= '2022-06-30 23:59:59'
								) tB
								ON tA.stok_opname_id = tB.id
								WHERE tB.id is not null
								GROUP BY barang_id, warna_id, gudang_id
							) t2
							ON t1.barang_id = t2.barang_id_stok
							AND t1.warna_id = t2.warna_id_stok
							AND t1.gudang_id = t2.gudang_id_stok
							LEFT JOIN (
								SELECT barang_id as barang_id_penyesuaian, warna_id as warna_id_penyesuaian,gudang_id as gudang_id_penyesuaian, MAX(created_at) as tanggal_penyesuaian
							    FROM nd_penyesuaian_stok
								WHERE created_at <= '2022-06-30 23:59:59'
						    	AND tipe_transaksi != 3
								GROUP BY barang_id, warna_id, gudang_id
								) t3
							ON t1.barang_id = t3.barang_id_penyesuaian
							AND t1.warna_id = t3.warna_id_penyesuaian
							AND t1.gudang_id = t3.gudang_id_penyesuaian
							GROUP BY barang_id, warna_id
						) tbl_a
						LEFT JOIN (
							SELECT @rownum:=@rownum+1 urutan_barang,id, nama, nama_jual, status_aktif, satuan_id, tipe_qty
							FROM nd_barang, (SELECT @rownum:=0) r
							ORDER BY nama_jual asc
							) tbl_b
						ON tbl_a.barang_id = tbl_b.id
						LEFT JOIN (
							SELECT @rownum:=@rownum+1 urutan,id, warna_jual, warna_beli
							FROM nd_warna, (SELECT @rownum:=0) r
							ORDER BY warna_jual asc
							) tbl_c
						ON tbl_a.warna_id = tbl_c.id
						LEFT JOIN nd_satuan tbl_d
						ON tbl_b.satuan_id = tbl_d.id
						Where barang_id is not null
						AND last_edit >= '2022-03-13'
						OR (gudang_2_qty+gudang_1_qty+gudang_3_qty+gudang_5_qty+gudang_4_qty) > 0
						ORDER BY urutan_barang, urutan
				) A			
			
            -- ORDER BY nama_barang_jual, urutan asc
            LIMIT 200, 100
		*/

	}

	function data_stok_barang(){

		// $session_data = $this->session->userdata('do_filter');
		$select = '';
		$select_v2 = '';
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

		$select = $this->generate_select();

		// $select_v2 = $this->generate_select_versi_2();

		foreach ($this->gudang_list_aktif as $row) {

				$select_v2 .= ", ROUND(
				SUM( if(gudang_id=".$row->id.", 
						ifnull(
							if(tanggal_stok is not null, if(time_stamp >= tanggal_stok,qty_masuk,0) , qty_masuk  ),
						0) ,
					0 )
				),2) - 
				ROUND(SUM( if(gudang_id=".$row->id.", 
						ifnull( 
							if(tanggal_stok is not null, if(time_stamp >= tanggal_stok, qty_keluar,0 ), qty_keluar ) 
						,0), 
					0 )
				),
				2)  
				as gudang_".$row->id."_qty ,

				SUM( if(gudang_id=".$row->id.", 
						if(tanggal_stok is not null, 
							if(time_stamp >= tanggal_stok,jumlah_roll_masuk,0 ), 
						jumlah_roll_masuk), 
					0 ) 
				) -
				SUM( if(gudang_id=".$row->id.", 
						if(tanggal_stok is not null,
							if(time_stamp >= tanggal_stok, jumlah_roll_keluar, 0)
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
		// foreach ($getOpname as $row) {
			// $tanggal_awal = $row->tanggal;
			// $stok_opname_id = $row->id;
		// }

		$tanggal_awal = '2018-01-01';
		$sOrder = "ORDER BY nama_barang_jual, urutan asc";
		if ($filter_type == 0) {
			// if (is_posisi_id()==1) {
			// 	$rResult = $this->inv_model->get_stok_barang_list_ajax_2($aColumns, $sWhere, "order by urutan asc", $sLimit, $select, $tanggal, $stok_opname_id, $tanggal_awal, $select2);
			// }else{
				$rResult = $this->inv_model->get_stok_barang_list_ajax_2($aColumns, $sWhere, "order by urutan asc", $sLimit, $select_v2, $tanggal.' 23:59:59', $stok_opname_id, $tanggal_awal, $select2);
			// }
		}else{
			// if (is_posisi_id()==1) {
				// echo $this->input->get("tanggal");
				$rResult = $this->inv_model->get_stok_barang_list_ajax_new_2($aColumns, $sWhere, $sOrder, $sLimit, $select_v2, $tanggal.' 23:59:59', $stok_opname_id, $tanggal_awal, $select2, $tgl_lalu, $cond_qty, $cond_filter);
				if (is_posisi_id() == 1) {
					// echo $rResult;
				}
				// }else{
			// 	$rResult = $this->inv_model->get_stok_barang_list_ajax_new($aColumns, $sWhere, $sOrder, $sLimit, $select, $tanggal, $stok_opname_id, $tanggal_awal, $select2, $tgl_lalu, $cond_qty, $cond_filter);
			// 	// $rResult = $this->inv_model->get_stok_barang_list_ajax_new($aColumns, $sWhere, $sOrder, $sLimit, $select, $tanggal, $stok_opname_id, $tanggal_awal, $select2, $tgl_lalu, $cond_qty, $cond_filter);
			// }
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

	function data_stok_barang_with_tutup_buku(){

		// $session_data = $this->session->userdata('do_filter');
		$select = '';
		$select_v2 = '';
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

		$select = $this->generate_select();

		// $select_v2 = $this->generate_select_versi_2();

		foreach ($this->gudang_list_aktif as $row) {

				$select_v2 .= ", ROUND(
				SUM( if(gudang_id=".$row->id.", 
						ifnull(
							if(tanggal_stok is not null, if(time_stamp >= tanggal_stok,qty_masuk,0) , qty_masuk  ),
						0) ,
					0 )
				),2) - 
				ROUND(SUM( if(gudang_id=".$row->id.", 
						ifnull( 
							if(tanggal_stok is not null, if(time_stamp >= tanggal_stok, qty_keluar,0 ), qty_keluar ) 
						,0), 
					0 )
				),
				2)  
				as gudang_".$row->id."_qty ,

				SUM( if(gudang_id=".$row->id.", 
						if(tanggal_stok is not null, 
							if(time_stamp >= tanggal_stok,jumlah_roll_masuk,0 ), 
						jumlah_roll_masuk), 
					0 ) 
				) -
				SUM( if(gudang_id=".$row->id.", 
						if(tanggal_stok is not null,
							if(time_stamp >= tanggal_stok, jumlah_roll_keluar, 0)
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
		$lmtStart = 0;
		$lmtEnd = 0;
        if ( isset( $_GET['iDisplayStart'] ) && $_GET['iDisplayLength'] != '-1' ){
            $sLimit = "LIMIT ".mysql_real_escape_string( $_GET['iDisplayStart'] ).", ".
                mysql_real_escape_string( $_GET['iDisplayLength'] );

			$lmtStart  = mysql_real_escape_string( $_GET['iDisplayStart'] );
			$lmtEnd  = mysql_real_escape_string( $_GET['iDisplayLength'] );
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
        if ( isset($_GET['sSearch']) && $_GET['sSearch'] != "" ){
            $sWhere = "WHERE (";
            for ( $i=0 ; $i<count($aColumns) ; $i++ ){
                $sWhere .= $aColumns[$i]." LIKE '%".mysql_real_escape_string( $_GET['sSearch'] )."%' OR ";
            }
            $sWhere = substr_replace( $sWhere, "", -3 );
            $sWhere .= ')';
        }
        
        // individual column filtering
        for ( $i=0 ; $i<count($aColumns) ; $i++ ){
            if ( isset($_GET['bSearchable_'.$i]) && $_GET['bSearchable_'.$i] == "true" && $_GET['sSearch_'.$i] != '' ){
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
		

		$tanggal_awal = '2018-01-01';

		$getTutupBuku = $this->common_model->db_select("nd_tutup_buku_stok where tanggal <= '".$tanggal." 23:59:59' order by tanggal desc limit 1");
		$tahun_tutup_buku = '2018';
		$bulan_qty = '01';
		foreach ($getTutupBuku as $row) {
			$tahun_tutup_buku = date('Y', strtotime($row->tanggal));
			$bulan_qty = date('m',strtotime($row->tanggal));
			$tanggal_awal = $row->tanggal;
			// $stok_opname_id = $row->id;
		}

		$sOrder = "ORDER BY nama_barang_jual, urutan asc";
		if ($filter_type == 0) {
				$rResult = $this->inv_model->get_stok_barang_list_ajax_2($aColumns, $sWhere, "order by urutan asc", $sLimit, $select_v2, $tanggal.' 23:59:59', $stok_opname_id, $tanggal_awal, $select2);
		}else{
			// if (is_posisi_id()==1) {
				// echo $this->input->get("tanggal");
				// if (is_posisi_id() == 1) {
					// echo $rResult;
					$rResult = $this->inv_model->get_stok_barang_list_ajax_with_tutup_buku($aColumns, $sWhere, $sOrder, "", $select_v2, $tanggal.' 23:59:59', $stok_opname_id, $tanggal_awal, $select2, $tgl_lalu, $cond_qty, $cond_filter, $tahun_tutup_buku, $bulan_qty);
				// }else{
				// 	$rResult = $this->inv_model->get_stok_barang_list_ajax_with_tutup_buku($aColumns, $sWhere, $sOrder, $sLimit, $select_v2, $tanggal.' 23:59:59', $stok_opname_id, $tanggal_awal, $select2, $tgl_lalu, $cond_qty, $cond_filter, $tahun_tutup_buku, $bulan_qty);
					
				// }
		}
        
        // $Totalan = $this->inv_model->get_stok_barang_list_ajax($aColumns,  '' , $sOrder, '', $select, $tanggal,$stok_opname_id, '2018-01-01', $select2);

        // $Filternya = $this->inv_model->get_stok_barang_list_ajax($aColumns,  $sWhere , $sOrder, '', $select, $tanggal,$stok_opname_id, '2018-01-01', $select2);

		$Totalan = $this->inv_model->get_stok_barang_list_ajax_with_tutup_buku($aColumns, '', $sOrder, $sLimit, $select, $tanggal.' 23:59:59', $stok_opname_id, $tanggal_awal, $select2, $tgl_lalu, $cond_qty, $cond_filter, $tahun_tutup_buku, $bulan_qty);

		if (is_posisi_id() == 1) {
			$Filternya = $rResult;
		}else{
			$Filternya = $rResult;
			// $Filternya = $this->inv_model->get_stok_barang_list_ajax_with_tutup_buku($aColumns, $sWhere, $sOrder, '', $select, $tanggal.' 23:59:59', $stok_opname_id, $tanggal_awal, $select2, $tgl_lalu, $cond_qty, $cond_filter, $tahun_tutup_buku, $bulan_qty);
		}

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
        
		$idx=1; 
		$f = $lmtStart + $lmtEnd;
        foreach ($rResult->result_array() as $aRow){

			// if (is_posisi_id()==1) {

				if ($idx > $lmtStart && $idx <= $f) {
					# code...
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
			// }else{
			// 	// echo print_r($aRow).'<hr/>';
			// 	$y = 0;
			// 	$row = array();
			// 	for ( $i=0 ; $i<count($aColumns) ; $i++ ){
			// 		$row[] = $aRow[ $aColumns[$i] ];
			// 	}
			// 	$y++;
			// 	$page++;
			// 	$output['aaData'][] = $row;
			// }
			$idx++; 
        }

		if (is_posisi_id() == 1) {
			// $this->output->enable_profiler(TRUE);
			
		}
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


		$select = ''; $select_all=''; $select_v2 = '';
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

			$select_v2 .= ", ROUND(
				SUM( if(gudang_id=".$row->id.", 
						ifnull(
							if(tanggal_stok is not null, if(time_stamp >= tanggal_stok,qty_masuk,0) , qty_masuk  ),
						0) ,
					0 )
				),2) - 
				ROUND(SUM( if(gudang_id=".$row->id.", 
						ifnull( 
							if(tanggal_stok is not null, if(time_stamp >= tanggal_stok, qty_keluar,0 ), qty_keluar ) 
						,0), 
					0 )
				),
				2)  
				as gudang_".$row->id."_qty ,

				SUM( if(gudang_id=".$row->id.", 
						if(tanggal_stok is not null, 
							if(time_stamp >= tanggal_stok,jumlah_roll_masuk,0 ), 
						jumlah_roll_masuk), 
					0 ) 
				) -
				SUM( if(gudang_id=".$row->id.", 
						if(tanggal_stok is not null,
							if(time_stamp >= tanggal_stok, jumlah_roll_keluar, 0)
						,jumlah_roll_keluar), 
					0 ) 
				)  
				as gudang_".$row->id."_roll,
				concat('".$row->id."','/',barang_id,'/',warna_id) as gudang_".$row->id."_button
				";


			


			$select_all .= ", SUM(gudang_".$row->id."_qty) as gudang_".$row->id."_qty, SUM(gudang_".$row->id."_roll) as gudang_".$row->id."_roll ";
			

		}

		$stok_opname_id = 0;
		$tanggal_awal = '2018-01-01';
		$getOpname = $this->common_model->db_select("nd_stok_opname where tanggal <= '".$tanggal."' order by tanggal desc limit 1");
		foreach ($getOpname as $row) {
			// $tanggal_awal = $row->tanggal;
			// $stok_opname_id = $row->id;
		}

		// echo $select."<hr/>". $select_all."<hr/>". $tanggal."<hr/>". $tanggal_awal."<hr/>". $stok_opname_id;

		// echo $select.'<br>';
		// $data['gudang_list'] = $this->common_model->db_select("nd_gudang where status_aktif = 1 ORDER BY id desc");
		// $data['stok_barang'] = $this->inv_model->get_stok_barang_list_rekap($select, $select_all, $tanggal, $tanggal_awal, $stok_opname_id); 
		$data['stok_barang'] = $this->inv_model->get_stok_barang_list_rekap($select_v2, $select_all, $tanggal, $tanggal_awal, $stok_opname_id); 
		// echo $data['stok_barang'];

		// echo $select_v2.'<hr>'. $select_all.'<hr>'. $tanggal.'<hr>'. $tanggal_awal.'<hr>'. $stok_opname_id;
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
			
			if (is_posisi_id() == 1) {
				$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$row->barang_id);
				$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
				$coll++;

				$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no,$row->warna_id);
				$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
				$coll++;
			}

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
		$view_type=2;

		// $tanggal_start = date("Y-m-1");
		// $tanggal_end = date("Y-m-t");

		$tanggal_end = date("Y-m-d");
		$tanggal_start = date("Y-m-d", strtotime('-7days'));
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

		if ($this->input->get('view_type') != '') {
			$view_type = $this->input->get('view_type');
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
			'barang_list' => $this->common_model->db_select("nd_barang"),
			'view_type' => $view_type,
			'warna_jual' => $warna_jual );

		
		$tanggal_awal = '2018-01-01';
		$tanggal_awal2 = '2018-01-01';
		$stok_opname_id = 0;
		$getOpname = $this->inv_model->get_last_opname($barang_id, $warna_id, $gudang_id, $tanggal_end.' 23:59:59');
		foreach ($getOpname as $row) {
			$tanggal_awal = $row->tanggal;
			$stok_opname_id = $row->id;
		}

		$getOpname2 = $this->common_model->get_last_opname($barang_id, $warna_id, $gudang_id, $tanggal_end.' 23:59:59');

		foreach ($getOpname2 as $row) {
			$tanggal_awal2 = $row->created_at;
			$stok_opname_id = $row->id;
		}

		if (is_posisi_id()==1) {
			// echo $tanggal_awal2;
		}

			// $data['stok_awal'] = $this->inv_model->get_stok_barang_satuan_awal($gudang_id, $barang_id, $warna_id, $tanggal_start, '2018-01-01', $stok_opname_id);
		if ($view_type == 2) {
			$data['stok_barang'] = $this->inv_model->get_stok_barang_satuan_2($gudang_id, $barang_id, $warna_id, $tanggal_start, $tanggal_end, '2018-01-01', $stok_opname_id); 
			$data['stok_barang_by_satuan'] = $this->inv_model->get_kartu_stok_barang_by_satuan_2($gudang_id, $barang_id, $warna_id, $tanggal_awal2, $tanggal_end, $tanggal_awal2, $stok_opname_id);
			$data['stok_awal'] = $this->inv_model->get_stok_barang_satuan_awal_2($gudang_id, $barang_id, $warna_id, $tanggal_start, '2018-01-01', $stok_opname_id);
		}else{
			$data['stok_barang'] = $this->inv_model->get_stok_barang_satuan($gudang_id, $barang_id, $warna_id, $tanggal_start, $tanggal_end, '2018-01-01', $stok_opname_id); 
			$data['stok_barang_by_satuan'] = $this->inv_model->get_kartu_stok_barang_by_satuan($gudang_id, $barang_id, $warna_id, $tanggal_start, $tanggal_end, $tanggal_awal2, $stok_opname_id);
			$data['stok_awal'] = $this->inv_model->get_stok_barang_satuan_awal($gudang_id, $barang_id, $warna_id, $tanggal_start, '2018-01-01', $stok_opname_id);
		}
		
		// $data['stok_barang_by_satuan'] = array(); 

		// $data['stok_barang'] = array();
		// $data['stok_awal'] = array();

		// echo $data['stok_barang'];
		$data['content'] = 'admin/inventory/kartu_stok_2';


		if (is_posisi_id() == 1) {
			echo $barang_id.' '.$warna_id.' <br/>';
			echo $tanggal_end.'-'.$tanggal_awal2.'-'.$stok_opname_id.'-'.$tanggal_start;
			$this->load->view('admin/template_no_sidebar',$data);
		}
		else{
			// $data['content'] = 'admin/inventory/kartu_stok_2';
			$this->load->view('admin/template_no_sidebar',$data);
		}

		if (is_posisi_id() == 1) {
			$this->output->enable_profiler(TRUE);
			
		}
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
		$select_v2 = '';
		$select2 = "";
		$select_all = '';
		$select_status = '';
		$columnSelect = array();
		$cond_filter = '';
		$idx = 4;
		$i = 0;
		$cond_qty = "";

		$select = $this->generate_select();

		// $select_v2 = $this->generate_select_versi_2();

		foreach ($this->gudang_list_aktif as $row) {

				$select_v2 .= ", ROUND(
				SUM( if(gudang_id=".$row->id.", 
						ifnull(
							if(tanggal_stok is not null, if(time_stamp >= tanggal_stok,qty_masuk,0) , qty_masuk  ),
						0) ,
					0 )
				),2) - 
				ROUND(SUM( if(gudang_id=".$row->id.", 
						ifnull( 
							if(tanggal_stok is not null, if(time_stamp >= tanggal_stok, qty_keluar,0 ), qty_keluar ) 
						,0), 
					0 )
				),
				2)  
				as gudang_".$row->id."_qty ,

				SUM( if(gudang_id=".$row->id.", 
						if(tanggal_stok is not null, 
							if(time_stamp >= tanggal_stok,jumlah_roll_masuk,0 ), 
						jumlah_roll_masuk), 
					0 ) 
				) -
				SUM( if(gudang_id=".$row->id.", 
						if(tanggal_stok is not null,
							if(time_stamp >= tanggal_stok, jumlah_roll_keluar, 0)
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


			// echo $select;
			// echo $cond_barang;
			// echo "<hr/>";

			$select = $this->generate_select_versi_2();
			// $data['stok_barang'] = $this->inv_model->get_stok_barang_list_by_barang_temp_2($tanggal_awal, $select, $tanggal, $cond_barang, $stok_opname_id); 
			// echo $data['stok_barang'];

			$stok_opname_id = 0;
				$getTutupBuku = $this->common_model->db_select("nd_tutup_buku_stok where tanggal <= '".$tanggal." 23:59:59' order by tanggal desc limit 1");
				$tahun_tutup_buku = '2018';
				$bulan_qty = '01';
				foreach ($getTutupBuku as $row) {
					$tahun_tutup_buku = date('Y', strtotime($row->tanggal));
					$bulan_qty = date('m',strtotime($row->tanggal));
					$tanggal_awal = $row->tanggal;
					$kolom_harga = str_pad($bulan_qty, 2,'0', STR_PAD_LEFT).'_harga';
					$kolom_qty = str_pad($bulan_qty, 2,'0', STR_PAD_LEFT).'_qty';
					// $stok_opname_id = $row->id;
				}

				$res = $this->inv_model->get_stok_with_tutup_buku($select_v2, $tanggal.' 23:59:59', $stok_opname_id, $tanggal_awal, $select2, "", $cond_qty, $tahun_tutup_buku, $bulan_qty, $cond_barang)->result();
				$data['stok_barang'] = $res; 
			if (is_posisi_id() != 1) {
				# code...
				$this->load->view('admin/template',$data);
				// echo "under_construction";
			}else{
				
		
				$this->load->view('admin/template',$data);
				$this->output->enable_profiler(TRUE);

			}

			// echo $data['stok_barang'];
		
		// echo $data['stok_barang'];
	}

//======================================stok barang all+ PO============================================

	function stok_barang_and_po(){
        $menu = is_get_url($this->uri->segment(1)) ;
        $tanggal = date('Y-m-d');
        $barang_id = '';
		$supplier_id = "";
		$cond_supplier = "";
        if ($this->input->get('barang_id') ) {
        	$barang_id = $this->input->get('barang_id');
        }

		if ($this->input->get('tanggal') != '') {
			$tanggal = is_date_formatter($this->input->get('tanggal'));
		}

		if($this->input->get('supplier_id') != ''){
			$supplier_id = $this->input->get('supplier_id');
			$cond_supplier = "WHERE  supplier_id = ".$supplier_id;
		}
		
        $cond_barang = '';
        $cond_barang2 = '';
        $data = array(
            'content' =>'admin/inventory/stok_barang_and_po',
            'breadcrumb_title' => 'Inventory',
            'breadcrumb_small' => 'Daftar Barang + PO ',
            'nama_menu' => $menu[0],
            'nama_submenu' => $menu[1],
            'common_data'=> $this->data,
            'barang_id' => $barang_id,
			'supplier_id' => $supplier_id,
			'tanggal' => is_reverse_date($tanggal),
            'data_isi'=> $this->data );

        $tanggal_awal = '2018-01-01';
        $stok_opname_id = 1;
        $getOpname = $this->common_model->db_select("nd_stok_opname where tanggal <= '".$tanggal."' order by tanggal desc limit 1");
        foreach ($getOpname as $row) { 
            // $tanggal_awal = $row->tanggal;
            // $stok_opname_id = $row->id;
        }

		if (is_posisi_id()==1) {
		}
		$getTutupBuku = $this->common_model->db_select("nd_tutup_buku_stok where tanggal <= '".$tanggal." 23:59:59' order by tanggal desc limit 1");
			$tahun_tutup_buku = '2018';
			$bulan_qty = '01';
			foreach ($getTutupBuku as $row) {
				$tahun_tutup_buku = date('Y', strtotime($row->tanggal));
				$bulan_qty = date('m',strtotime($row->tanggal));
				$tanggal_awal = $row->tanggal;
				// $stok_opname_id = $row->id;
			}

        if ($barang_id != '') {
        	$cond_barang = "WHERE barang_id = ".$barang_id;
        	$cond_barang2 = "AND barang_id = ".$barang_id;
			if (is_posisi_id()==1) {
			}else{
				// $data['barang_list_selected'] = $this->inv_model->get_stok_for_pre_po_legacy('', $tanggal /*as tanggal_akhir*/, $tanggal_awal, $stok_opname_id, $cond_barang);
			}
			$data['barang_list_selected'] = $this->inv_model->get_stok_for_pre_po('', $tanggal.' 23:59:59' /*as tanggal_akhir*/, $tanggal_awal, $stok_opname_id, $cond_barang, $tahun_tutup_buku, $bulan_qty, $cond_supplier);
        	$data['batch_for_pre_po'] = $this->inv_model->get_batch_for_pre_po($barang_id, $cond_supplier);
        }else{
	        $data['barang_list_selected'] = array();
        	$data['batch_for_pre_po'] = array();
        }

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
		
		$select = $this->generate_select_versi_2();
        if (is_posisi_id() == 1) {
			
			// $data['stok_barang'] = array();
		}else{
			// $data['stok_barang'] = $this->inv_model->get_stok_barang_list_by_barang_temp_2($tanggal_awal, $select, $tanggal, $cond_barang2, $stok_opname_id); 

		}
		$data['tanggal_list'] = [$tanggal_awal, $tanggal];
		

        // if (is_posisi_id() != 1) {
        // 	$data['content'] = 'admin/under_construction';
        // }
        // echo ''."<hr/>". $tanggal."<hr/>". $tanggal_awal."<hr/>". $stok_opname_id."<hr/>". $cond_barang;
		
        if (is_posisi_id() == 1) {
			$this->load->view('admin/template',$data);
			// echo $tanggal.' <br/>'. $tanggal_awal.' <br/>'.$stok_opname_id.'<br/> '. $cond_barang.'<br/> '. $tahun_tutup_buku.'<br/> '. $bulan_qty;
			$this->output->enable_profiler(TRUE);
		}else{
			$this->load->view('admin/template',$data);
            // $data['content'] = 'admin/inventory/stok_barang_and_po_legacy';
			
			// echo 'on progress..';
		}
    }

//======================================stok barang + HPP============================================
	function stok_barang_hpp_legacy(){
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

		
		$select = '';
		$select_v2 = '';
		$select2 = "";
		$select_all = '';
		$select_status = '';
		$columnSelect = array();
		$cond_filter = '';
		$idx = 4;
		$i = 0;
		$cond_qty = "";

		$select = $this->generate_select();

		// $select_v2 = $this->generate_select_versi_2();

		foreach ($this->gudang_list_aktif as $row) {

				$select_v2 .= ", ROUND(
				SUM( if(gudang_id=".$row->id.", 
						ifnull(
							if(tanggal_stok is not null, if(time_stamp >= tanggal_stok,qty_masuk,0) , qty_masuk  ),
						0) ,
					0 )
				),2) - 
				ROUND(SUM( if(gudang_id=".$row->id.", 
						ifnull( 
							if(tanggal_stok is not null, if(time_stamp >= tanggal_stok, qty_keluar,0 ), qty_keluar ) 
						,0), 
					0 )
				),
				2)  
				as gudang_".$row->id."_qty ,

				SUM( if(gudang_id=".$row->id.", 
						if(tanggal_stok is not null, 
							if(time_stamp >= tanggal_stok,jumlah_roll_masuk,0 ), 
						jumlah_roll_masuk), 
					0 ) 
				) -
				SUM( if(gudang_id=".$row->id.", 
						if(tanggal_stok is not null,
							if(time_stamp >= tanggal_stok, jumlah_roll_keluar, 0)
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
		
        $stok_opname_id = 0;
		$getTutupBuku = $this->common_model->db_select("nd_tutup_buku_stok where tanggal <= '".$tanggal." 23:59:59' order by tanggal desc limit 1");
		$tahun_tutup_buku = '2018';
		$bulan_qty = '01';
		foreach ($getTutupBuku as $row) {
			$tahun_tutup_buku = date('Y', strtotime($row->tanggal));
			$bulan_qty = date('m',strtotime($row->tanggal));
			$tanggal_awal = $row->tanggal;
			$kolom_harga = str_pad($bulan_qty, 2,'0', STR_PAD_LEFT).'_harga';
			$kolom_qty = str_pad($bulan_qty, 2,'0', STR_PAD_LEFT).'_qty';
			// $stok_opname_id = $row->id;
		}

		$res = $this->inv_model->get_stok_with_tutup_buku($select_v2, $tanggal.' 23:59:59', $stok_opname_id, $tanggal_awal, $select2, "", $cond_qty, $tahun_tutup_buku, $bulan_qty,"")->result();
		
		$data['gudang_list'] = $this->common_model->db_select("nd_gudang where status_aktif = 1 ORDER BY urutan desc");
		// $data['stok_barang'] = $this->inv_model->get_stok_barang_list_hpp($select2, $select, $tanggal, $kolom, $tgl_tutup_buku);
		$data['stok_barang'] = $res;
		$data['data_hpp'] = $this->inv_model->get_harga_hpp($kolom_harga, $kolom_qty, $tanggal_awal, $tahun_tutup_buku, $tanggal); 
		// echo $data['stok_barang'];
		if (is_posisi_id() != 1) {
			// $data['content'] = 'admin/under_construction';
			// echo "<h1>under_construction</h1>";
			$this->load->view('admin/template',$data);
			// $this->load->view('admin/template',$data);
		}else {
			$this->load->view('admin/template',$data);

			// echo $tanggal_awal;
		// 	echo $kolom;
		// 	echo $tgl_tutup_buku;
		// 	echo "<hr/>";
		// 	echo $select;
		// 	echo "<hr/>";
		// 	echo $select2;
		}
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

//================================penyesuaian stok split LEGACY ==================================================

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

	// function penyesuaian_stok_split_remove(){
	// 	$penyesuaian_stok_id = $this->input->get('penyesuaian_stok_id');
	// 	$get_data = $this->common_model->db_select("nd_penyesuaian_stok where id=".$penyesuaian_stok_id);
	// 	foreach ($get_data as $row) {
	// 		$gudang_id = $row->gudang_id;
	// 		$barang_id = $row->barang_id;
	// 		$warna_id = $row->warna_id;
	// 		$tanggal = $row->tanggal;
	// 	}

	// 	$tanggal_start = date("Y-m-1", strtotime($tanggal));
	// 	$tanggal_end = date("Y-m-t", strtotime($tanggal) );

	// 	$tanggal_start = is_reverse_date($tanggal_start);
	// 	$tanggal_end = is_reverse_date($tanggal_end);

	// 	$this->common_model->db_delete("nd_penyesuaian_stok",'id', $penyesuaian_stok_id);
	// 	$this->common_model->db_delete('nd_penyesuaian_stok_split','penyesuaian_stok_id', $penyesuaian_stok_id);
	// 	redirect(is_setting_link('inventory/kartu_stok').'/'.$gudang_id.'/'.$barang_id.'/'.$warna_id.'?tanggal_start='.$tanggal_start.'&tanggal_end='.$tanggal_end);
		
	// }

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
			'nama_kru' => $ini->post('nama_kru'),
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
			'qty' => $ini->post('qty') ,
			'jumlah_roll' => $ini->post('jumlah_roll'),
			'nama_kru' => $ini->post('nama_kru'),
			'user_id' => is_user_id() );
		$rekap_qty = explode('--', $this->input->post('rekap_qty'));

		if (is_posisi_id() <= 3) {
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
		}
		// print_r($data_detail);


		redirect(is_setting_link('inventory/mutasi_barang'));

	}


	function data_mutasi(){

		// $session_data = $this->session->userdata('do_filter');
		
		// $aColumns = array('status_aktif','tanggal','nama_barang','gudang_before','gudang_after','qty','jumlah_roll', 'data');
        if (is_posisi_id() <= 3) {
			$aColumns = array('status_aktif','tanggal','nama_barang','gudang_before','gudang_after','qty','jumlah_roll','username', 'nama_kru', 'data');
		}else{
			$aColumns = array('status_aktif','tanggal','nama_barang','gudang_before','gudang_after','qty','jumlah_roll', 'nama_kru' ,'data');
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
		$mutasi_barang_id = 0;
		if ($this->input->post('mutasi_barang_id') != '') {
			$mutasi_barang_id = $this->input->post('mutasi_barang_id');
		}
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


        if (is_posisi_id() == 1) {
			$data = $this->inv_model->cek_barang_qty_2($gudang_id, $barang_id,$warna_id, $tanggal_awal, $stok_opname_id, $cond_detail);
	        $stok_barang_by_satuan = $this->inv_model->get_kartu_stok_barang_by_satuan_2($gudang_id, $barang_id, $warna_id, $tanggal, $tanggal,  $tanggal_awal, $stok_opname_id); 
        }else{
			$data = $this->inv_model->cek_barang_qty_2($gudang_id, $barang_id,$warna_id, $tanggal_awal, $stok_opname_id, $cond_detail);
	        $stok_barang_by_satuan = $this->inv_model->get_kartu_stok_barang_by_satuan_2($gudang_id, $barang_id, $warna_id, $tanggal, $tanggal,  $tanggal_awal, $stok_opname_id); 
        }


		$return[0] = ($data);
        $return[1] = ($stok_barang_by_satuan);
        $return[2] = $gudang_id.','. $barang_id.','.$warna_id.','. $tanggal_awal.','. $stok_opname_id.','. $cond_detail;
        echo json_encode($return);

		if (is_posisi_id()==1) {
			// $this->output->enable_profiler(TRUE);
		}

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
		$kolom_qty = $bulan_tutup_buku.'_qty';
		$kolom_roll = $bulan_tutup_buku.'_roll';
		$tutup_buku_id_now = 0;
		// echo $tanggal_before;
		$get_tutup_buku = $this->common_model->db_select("nd_tutup_buku where MONTH(tanggal) ='".$bulan_tutup_buku."' AND YEAR(tanggal) ='".$tahun_tutup_buku."' ORDER BY updated DESC limit 1");
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

		$stok_opname_id = 1;
		$getOpname = $this->common_model->db_select("nd_stok_opname where barang_id_so is null AND warna_id_so is null AND gudang_id_so is null AND tanggal <= '".$tanggal_end."' order by tanggal desc limit 1");
		foreach ($getOpname as $row) {
			$tanggal_awal = $row->tanggal;
			$stok_opname_id = $row->id;
		}

		// echo $tanggal_start;
		$m_list = array();
		if ($gudang_id == 0) {
			// echo $tanggal_awal, $tanggal_end
			// $data['mutasi_barang_list'] = $this->inv_model->mutasi_persediaan_barang_global($tanggal_awal, $tanggal_start,$tanggal_end , $stok_opname_id, $tutup_buku_id, $kolom_harga); 
			// $data['mutasi_barang_list'] = $this->inv_model->mutasi_persediaan_barang_global_2($tanggal_awal, $tanggal_start,$tanggal_end , $stok_opname_id, $tutup_buku_id, $kolom_harga); 
			$data['mutasi_barang_list'] = $this->inv_model->mutasi_persediaan_barang_global_3($tanggal_awal, $tanggal_start,$tanggal_end , $stok_opname_id, $tutup_buku_id, $kolom_harga, $kolom_qty, $kolom_roll); 
		}else{
			$get_latest_tanggal = date("Y-m-d", strtotime($tanggal_start.' -1 month'));
			$stok_qty = date("m", strtotime($get_latest_tanggal)).'_qty';
			$stok_roll = date("m", strtotime($get_latest_tanggal)).'_roll';
			$tgl = date('Y-m-d', strtotime($tanggal));
			// echo $stok_qty, $stok_roll;
			// $data['mutasi_barang_list'] = $this->inv_model->mutasi_persediaan_temp($tanggal_awal, $tanggal_start,$tanggal_end, $gudang_id, $stok_opname_id); 
			// $data['mutasi_barang_list'] = $this->inv_model->mutasi_persediaan_pergudang($tanggal_before, $tanggal_start,$tanggal_end, $gudang_id, $stok_opname_id); 
			$data['mutasi_barang_list'] = $this->inv_model->mutasi_persediaan_pergudang_by_input($tanggal_before, $tgl,$tanggal_end, $gudang_id, $stok_opname_id); 
			$get_tutup_buku = $this->common_model->db_select("nd_tutup_buku_gudang where MONTH(tanggal) ='".$bulan_tutup_buku."' AND YEAR(tanggal) ='".$tahun_tutup_buku."' AND gudang_id=$gudang_id ORDER BY updated DESC limit 1");
			$data['tutup_buku_gudang_id'] = '';
			foreach ($get_tutup_buku as $row) {
				$data['tutup_buku_gudang_id'] = $row->id;
			}
			$get_tutup_buku_now = $this->inv_model->get_tutup_buku_gudang_now($tahun_tutup_buku_now, $bulan_tutup_buku_now, $gudang_id);
			// print_r($get_tutup_buku_now)
			$data['tutup_buku_gudang_id_now'] = '';
			$data['username'] = '';
			$data['updated_now'] = '';
			$data['tanggal_tutup_now'] = '';

			foreach ($get_tutup_buku_now as $row) {
				$data['tutup_buku_gudang_id_now'] = $row->id;
				$data['username'] = $row->username;
				$data['updated_now'] = $row->updated;
				$data['tanggal_tutup_now'] = $row->tanggal;
			}
			
		}

		$isAwalTahun = false;
		// echo "ya";
		// echo date('Y', strtotime($tanggal_start. "-1 month")) .'!='. date('Y', strtotime($tanggal_before));

		// $data_awal = array();
		// if (date('Y', strtotime($tanggal_start)) != date('Y', strtotime($tanggal_before)) ) {
		// 	// echo "ya";
		// 	$isAwalTahun = true;
		// 	$thn = date('Y', strtotime($tanggal_before));
		// 	$data_awal = array();
		// 	$data_awal = $this->common_model->db_select("nd_mutasi_persediaan_barang_tahunan WHERE YEAR(tanggal) = '$thn' ");
		// 	if (count($data_awal) == 0) {
		// 		$isAwalTahun = false;
		// 	}
		// }

		// $data['data_awal'] = $data_awal;
		// $data['isAwalTahun'] = $isAwalTahun;
		// echo $tanggal;
		if (is_posisi_id() == 1) {
			# code...
			// echo $tanggal_before.','. $tanggal_start.','.$tanggal_end .','. $stok_opname_id.','. $tutup_buku_id.','. $kolom_harga;
			// echo $tanggal_awal, $tanggal_start,$tanggal_end , $stok_opname_id, $tutup_buku_id, $kolom_harga, $kolom_qty, $kolom_roll;

			// $data['content'] = 'admin/inventory/mutasi_persediaan_barang';
			// print_r($data['mutasi_barang_list']);
			
			// print_r($data['data_awal']);
			$this->load->view('admin/template',$data);
			$this->output->enable_profiler(TRUE);
			
			// foreach ($data['mutasi_barang_list'] as $row) {
			// 	if ($row->barang_id == 10) {
			// 		print_r($row);
			// 		echo "<hr/>";
			// 	}
			// }

		}else{
			// $data['content'] = 'admin/inventory/mutasi_persediaan_barang_3';
			$this->load->view('admin/template',$data);
			
		}
	}

	function mutasi_persediaan_barang_2(){
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

		$stok_opname_id = 1;
		$getOpname = $this->common_model->db_select("nd_stok_opname where barang_id_so is null AND warna_id_so is null AND gudang_id_so is null AND tanggal <= '".$tanggal_end."' order by tanggal desc limit 1");
		foreach ($getOpname as $row) {
			$tanggal_awal = $row->tanggal;
			$stok_opname_id = $row->id;
		}

		// echo $tanggal_start;
		$m_list = array();
		if ($gudang_id == 0) {
			// $data['mutasi_barang_list'] = $this->inv_model->mutasi_persediaan_barang_global($tanggal_awal, $tanggal_start,$tanggal_end , $stok_opname_id, $tutup_buku_id, $kolom_harga); 
			$m_list = $this->inv_model->mutasi_persediaan_barang_global_2($tanggal_awal, $tanggal_start,$tanggal_end , $stok_opname_id, $tutup_buku_id, $kolom_harga); 
		}else{
			$m_list = $this->inv_model->mutasi_persediaan_temp($tanggal_awal, $tanggal_start,$tanggal_end, $gudang_id, $stok_opname_id); 
		}

		$data['mutasi_barang_list'] = $m_list;
		// echo $tanggal;
		if (is_posisi_id() == 1) {
			# code...
			echo $tanggal_awal.','. $tanggal_start.','.$tanggal_end .','. $stok_opname_id.','. $tutup_buku_id.','. $kolom_harga;


			// $this->load->view('admin/template',$data);

		}else{
			$this->load->view('admin/template',$data);
			
		}
	}

	function tutup_buku_insert(){
		$data_id = $this->input->post('data_id');
		$harga = $this->input->post('harga');
		$qty = $this->input->post('qty');
		$roll = $this->input->post('roll');

		$tanggal = is_date_formatter($this->input->post('tanggal'));
		$tanggal = date('Y-m-t', strtotime($tanggal));
		$tanggal_link = date('F+Y', strtotime($tanggal));
		$tahun = date('Y', strtotime($tanggal));
		$bulan = date('m', strtotime($tanggal));

		//initial case untuk mysql
		$case = "CASE
		";
		$case_qty = "CASE
		";
		$case_roll = "CASE
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

			$case_qty .="WHEN YEAR(tahun)='".$tahun."' and barang_id=".$data_brg[0]." and warna_id=".$data_brg[1]." THEN ".$qty[$value]."
			";

			$case_roll .="WHEN YEAR(tahun)='".$tahun."' and barang_id=".$data_brg[0]." and warna_id=".$data_brg[1]." THEN ".$roll[$value]."
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
			ELSE ".$bulan."_harga
			END
			, ".$bulan."_qty = 
			$case_qty
			ELSE ".$bulan."_qty
			END
			,".$bulan."_roll = 
			$case_roll
			ELSE ".$bulan."_roll
			END
			");

		redirect(is_setting_link('inventory/mutasi_persediaan_barang').'?tanggal='.$tanggal_link."&gudang_id=0" );

		// print_r($get_result);
		// print_r($data);

	}

	function tutup_buku_gudang_insert(){
		$data_id = $this->input->post('data_id');
		$harga = $this->input->post('harga');
		$qty = $this->input->post('qty');
		$roll = $this->input->post('roll');
		
		$tanggal = is_date_formatter($this->input->post('tanggal'));
		$gudang_id = $this->input->post('gudang_id');
		$tanggal = date('Y-m-t', strtotime($tanggal));
		$tanggal_link = date('F+Y', strtotime($tanggal));
		$tahun = date('Y', strtotime($tanggal));
		$bulan = date('m', strtotime($tanggal));

		//initial case untuk mysql
		$case = "CASE
		";
		$case_qty = "CASE
		";
		$case_roll = "CASE
		";

		$idx = 0;
		$data_tutup_buku = array(
			'tanggal' => $tanggal ,
			'gudang_id' => $gudang_id,
			'user_id' => is_user_id() );

		$this->common_model->db_insert('nd_tutup_buku_gudang', $data_tutup_buku);
		
		if($data_id !=''){

			foreach ($data_id as $key => $value) {
				$list_year = array();
				$data_brg = explode('--', $key);
				
				//data untuk tutup buku
				
	
				//data temp untuk fill temporary table untuk di compare dengan data yg sudah ada di tutup buku detail
				$data_temp[$idx] =  array(
					'barang_id' => $data_brg[0] ,
					'warna_id' => $data_brg[1] ,
				);
	
				//klo belum ada set tutup buku detail, pake ini
				$data_new[$idx] = array(
					'tahun' => $tanggal,
					'gudang_id' => $gudang_id, 
					'barang_id' => $data_brg[0] ,
					'warna_id' => $data_brg[1] ,
					);
	
				//create case untuk update
				$case_qty .="WHEN YEAR(tahun)='".$tahun."' and gudang_id=".$gudang_id." and barang_id=".$data_brg[0]." and warna_id=".$data_brg[1]." THEN ".$qty[$value]."
				";
	
				$case_roll .="WHEN YEAR(tahun)='".$tahun."' and gudang_id=".$gudang_id." and barang_id=".$data_brg[0]." and warna_id=".$data_brg[1]." THEN ".$roll[$value]."
				";
	
				$idx++;
			}
	
	
			//cek kalo data tahun tersebut tersedia/tidak
			$baris = $this->common_model->db_select_num_rows("nd_tutup_buku_detail_gudang where YEAR(tahun)='".$tahun."' AND gudang_id=$gudang_id");
			if ($baris == 0) {
				//klo tidak tersedia, insert new set
				$this->common_model->db_insert_batch("nd_tutup_buku_detail_gudang", $data_new);
			}else{
				//klo tersedia, cek set barang, takutnya ada barang/warna baru
				//bikin temp data in nd_barang_warna_temp
				$this->common_model->db_free_query_superadmin("TRUNCATE nd_barang_warna_temp");
				$this->common_model->db_insert_batch("nd_barang_warna_temp", $data_temp);
				//compare sama tabel tutup buku
				$get_result = $this->common_model->get_tutup_buku_gudang_non_barang($tahun, $gudang_id);
				$idx = 0;
				foreach ($get_result as $row) {
					$dt_bw_insert[$idx] = array(
						'tahun' => $tanggal,
						'gudang_id' => $gudang_id,
						'barang_id' => $row->barang_id,
						'warna_id' => $row->warna_id );
					$idx++;
				}
	
				//hasilnya insert
				if ($idx > 0) {
					$this->common_model->db_insert_batch('nd_tutup_buku_detail_gudang', $dt_bw_insert);
				}
	
				// jangan lupa truncate table temp
				$this->common_model->db_free_query_superadmin("TRUNCATE nd_barang_warna_temp");
				
			}
	
			//akhirnya update data tutup_buku_detail;
			$this->common_model->db_free_query_superadmin("UPDATE nd_tutup_buku_detail_gudang
				SET ".$bulan."_qty = 
				$case_qty
				ELSE ".$bulan."_qty
				END
				,".$bulan."_roll = 
				$case_roll
				ELSE ".$bulan."_roll
				END
				");
		}


		redirect(is_setting_link('inventory/mutasi_persediaan_barang').'?tanggal='.$tanggal_link."&gudang_id=$gudang_id" );

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
		$getOpname = $this->common_model->db_select("nd_stok_opname where barang_id_so is null AND warna_id_so is null AND gudang_id_so is null AND tanggal <= '".$tanggal_end."' order by tanggal desc limit 1");
		foreach ($getOpname as $row) {
			// $tanggal_awal = $row->tanggal;
			// $stok_opname_id = $row->id;
		}
		// $mutasi_barang_list = $this->inv_model->mutasi_persediaan_barang($tanggal_awal, $tanggal,$tanggal_end, $gudang_id, $stok_opname_id); 
		
		$tanggal_before = date("Y-m-d", strtotime($tanggal_start. "-1 month")); 
		$isAwalTahun = false;
		// echo "ya";
		// echo date('Y', strtotime($tanggal_start. "-1 month")) .'!='. date('Y', strtotime($tanggal_before));

		// if (date('Y', strtotime($tanggal_start)) != date('Y', strtotime($tanggal_before)) ) {
		// 	// echo "ya";
		// 	$isAwalTahun = true;
		// 	$thn = date('Y', strtotime($tanggal_before));
		// 	$data_awal = $this->common_model->db_select("nd_mutasi_persediaan_barang_tahunan WHERE YEAR(tanggal) = '$thn' ");
		// 	if (count($data_awal) == 0) {
		// 		$isAwalTahun = false;
		// 	}else{
		// 		foreach ($data_awal as $row) {
		// 			$hrg_awal[$row->barang_id][$row->warna_id] = $row->harga;
		// 			$qty_awal[$row->barang_id][$row->warna_id] = $row->qty;
		// 			$roll_awal[$row->barang_id][$row->warna_id] = $row->jumlah_roll;
		// 		}
		// 	}
		// }
		$bulan_tutup_buku = date("m", strtotime($tanggal_before)); 
		$tahun_tutup_buku = date("Y", strtotime($tanggal_before));
		$kolom_harga = $bulan_tutup_buku.'_harga';
		$kolom_qty = $bulan_tutup_buku.'_qty';
		$kolom_roll = $bulan_tutup_buku.'_roll';

		// echo $tanggal_before;
		$get_tutup_buku = $this->common_model->db_select("nd_tutup_buku where MONTH(tanggal) ='".$bulan_tutup_buku."' AND YEAR(tanggal) ='".$tahun_tutup_buku."'");
		$tutup_buku_id = 0;
		foreach ($get_tutup_buku as $row) {
			$tutup_buku_id = $row->id;
		}

		// $mutasi_barang_list = $this->inv_model->mutasi_persediaan_barang_global($tanggal_awal, $tanggal ,$tanggal_end , $stok_opname_id, $tutup_buku_id, $kolom_harga); 
		// $mutasi_barang_list = $this->inv_model->mutasi_persediaan_barang_global_2($tanggal_awal, $tanggal ,$tanggal_end , $stok_opname_id, $tutup_buku_id, $kolom_harga); 
		$mutasi_barang_list = $this->inv_model->mutasi_persediaan_barang_global_3($tanggal_awal, $tanggal ,$tanggal_end , $stok_opname_id, $tutup_buku_id, $kolom_harga, $kolom_qty, $kolom_roll); 

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
		$objPHPExcel->getActiveSheet()->mergeCells("AB4:AD4");

		
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
		->setCellValue('P4', 'Assembly')
		->setCellValue('S4', 'RETUR JUAL')
		->setCellValue('V4', 'RETUR BELI')
		->setCellValue('Y4', 'Pengeluaran Lain2')
		->setCellValue('AB4', 'Saldo Akhir')

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

		->setCellValue('AB5', 'Yard')
		->setCellValue('AC5', 'Roll')
		->setCellValue('AD5', 'Nilai')

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
			$hpp_berlaku = 0;
			if ($isAwalTahun) {

				$hpp_ = 0;
				$qty_ = 0;
				$roll_ = 0;

				if (isset($hrg_awal[$row->barang_id][$row->warna_id])) {
					// $hpp_ = $hrg_awal[$row->barang_id][$row->warna_id];
					// $qty_ = $qty_awal[$row->barang_id][$row->warna_id];
					// $roll_ = $roll_awal[$row->barang_id][$row->warna_id];
				}

				// if (is_posisi_id()==1) {
				// 	echo $isAwalTahun.'<br/>';
				// 	if ($row->barang_id==6 AND $row->warna_id ==21) {
				// 		echo $qty_.'<br/>';
				// 	}
				// }

				$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $hpp_);
				$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
				$coll++;
	
				$coll_qty = $coll;
				$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $qty_);
				$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
				$coll++;
				$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $roll_);
				$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
				$coll++;
				$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, "=".$coll_hpp.$row_no.'*'.$coll_qty.$row_no );
				$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
				$coll++;
	
	
				$total_nilai =($hpp_ * $qty_) + ($row->hpp_beli * $row->qty_beli);
				$total_qty_stock = $qty_ + $row->qty_beli;
				// if ($total_qty_stock == 0) {
				// 	$total_qty_stock = 1;
				// }
				$hpp_berlaku = $hpp_;

			}else{
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
				// if ($total_qty_stock == 0) {
				// 	 $total_qty_stock = 1;
				// }
				$hpp_berlaku = $row->hpp;
			}

			if ($total_qty_stock == 0) {
				$hpp_all = $hpp_berlaku;
			}else{
				$hpp_all = $total_nilai / $total_qty_stock;
			}
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

			//=====================================Assembly=============================

			$coll_qty = $coll;
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $row->qty_assembly);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $row->jumlah_roll_assembly);
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
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, round($row->qty_stock + $row->qty_beli - $row->qty_jual + $row->qty_mutasi_masuk - $row->qty_mutasi + $row->qty_retur - $row->qty_retur_beli - $row->qty_lain + $row->qty_penyesuaian,2));
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;

			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $row->jumlah_roll_stock + $row->jumlah_roll_beli - $row->jumlah_roll_jual + $row->jumlah_roll_mutasi_masuk -  $row->jumlah_roll_mutasi + $row->jumlah_roll_retur - $row->jumlah_roll_retur_beli + $row->jumlah_roll_penyesuaian + $row->jumlah_roll_assembly - $row->jumlah_roll_lain);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;

			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, "=(AB".$row_no."*".$hpp_all.")");
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;

			if (is_posisi_id() == 1) {
				$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $hpp_all );
				$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
				$coll++;

				$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $row->barang_id);
				$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(10);
				$coll++;
	
				$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $row->warna_id);
				$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(10);
				$coll++;
			}

			

			$coll++;
			$row_no++;
			$idx++;			

		}

		// $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		//ob_end_clean();
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		ob_end_clean();

		// if (is_posisi_id () != 1) {
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header("Content-Disposition: attachment;filename=mutasi_persediaan_barang_all_".$tanggal_print.".xls");
			header('Cache-Control: max-age=0');
			$objWriter->save('php://output');
			# code...
		// }
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

		$tanggal_before = date("Y-m-d", strtotime($tanggal. "-1 month"));
		$tgl = date('Y-m-d', strtotime($tanggal));
			// $mutasi_barang_list = $this->inv_model->mutasi_persediaan_pergudang($tanggal_before, $tanggal,$tanggal_end, $gudang_id, $stok_opname_id); 
			$mutasi_barang_list = $this->inv_model->mutasi_persediaan_pergudang_by_input($tanggal_before, $tgl,$tanggal_end, $gudang_id, $stok_opname_id); 
			// $mutasi_barang_list = $this->inv_model->mutasi_persediaan_barang($tanggal_awal, $tanggal,$tanggal_end, $gudang_id, $stok_opname_id); 
		
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
		$objPHPExcel->getActiveSheet()->mergeCells("W4:X4");

		
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
		->setCellValue('O4', 'Assembly')
		->setCellValue('Q4', 'RETUR Jual')
		->setCellValue('S4', 'RETUR Beli')
		->setCellValue('U4', 'Lain2')
		->setCellValue('W4', 'Sakdo Akhir')

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

		->setCellValue('W5', 'Yard')
		->setCellValue('X5', 'Roll')
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
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $row->qty_assembly);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $row->jumlah_roll_assembly);
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
			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $row->qty_stock + $row->qty_beli - $row->qty_jual + $row->qty_mutasi_masuk - $row->qty_mutasi + $row->qty_retur - $row->qty_retur_beli - $row->qty_lain + $row->qty_penyesuaian + $row->qty_assembly);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;

			$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $row->jumlah_roll_stock + $row->jumlah_roll_beli - $row->jumlah_roll_jual + $row->jumlah_roll_mutasi_masuk -  $row->jumlah_roll_mutasi + $row->jumlah_roll_retur - $row->jumlah_roll_retur_beli - $row->jumlah_roll_lain + $row->jumlah_roll_penyesuaian + $row->jumlah_roll_assembly);
			$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(15);
			$coll++;

			if (is_posisi_id()==1) {
				$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $row->barang_id);
				$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(10);
				$coll++;
	
				$objPHPExcel->getActiveSheet()->setCellValue($coll.$row_no, $row->warna_id);
				$objPHPExcel->getActiveSheet()->getStyle($coll.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getColumnDimension($coll)->setWidth(10);
				$coll++;
				# code...
			}
			

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
		$tanggal_start = date('Y-m-01');
		$tanggal_end = date('Y-m-t');
		
		$data = array(
			'content' =>'admin/inventory/stok_opname',
			'breadcrumb_title' => 'Stok',
			'breadcrumb_small' => 'Stok Opnames',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data'=> $this->data,
			);

		// $data['stok_opname_list'] = $this->common_model->db_select('nd_stok_opname');
		$data['stok_opname_list'] = $this->inv_model->get_stok_opname_list($tanggal_start, $tanggal_end);
		if (is_posisi_id()==1) {
			$this->load->view('admin/template',$data);
			# code...
		}else{
			redirect(is_setting_link('inventory/stok_opname_detail_2'));
		}
	}

	function stok_opname_insert(){
		$barang_id_so = $this->input->post('barang_id_so');
		$warna_id_so = $this->input->post('warna_id_so');
		$gudang_id_so = $this->input->post('gudang_id_so');

		
		
		$data = array(
			'tanggal' => is_date_formatter($this->input->post('tanggal')),
			'barang_id_so' =>($barang_id_so == '' ? null : $barang_id_so),
			'warna_id_so' => ($warna_id_so == '' ? null : $warna_id_so),
			'gudang_id_so' => ($gudang_id_so == '' ? null : $gudang_id_so),
			'user_id' => is_user_id(),
			'created_at' => date('Y-m-d H:i:s') );
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
		$stok_opname_data = array();
		$get = array();
		if ($id != '') {
			$get = $this->common_model->db_select("nd_stok_opname where id=$id");
			$stok_opname_data = $this->common_model->db_select('nd_stok_opname where id='.$id);
			# code...
		}
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
		foreach ($stok_opname_data as $row) {
			$tanggal_so = $row->tanggal;
		}
		$data = array(
			'content' =>'admin/inventory/stok_opname_detail',
			'breadcrumb_title' => 'Stok',
			'breadcrumb_small' => 'Daftar Barang Stok Opname',
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

	function stok_opname_detail_2(){
		$menu = is_get_url($this->uri->segment(1)) ;
		$id = '';
		$cond_barang = '';
		$barang_id_filter = '';

		$barang_id_so = '';
		$warna_id_so = '';
		$gudang_id_so = '';
		$stok_opname_data = array();
		$data = array(
			'content' =>'admin/inventory/stok_opname_detail_2',
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
			'tanggal_so' => '',
			'id' => $id
			);	

		if ($this->session->flashdata('data_double')) {
			$data["data_double"] = "benar";
		}else{
			$data["data_double"] = "tidak";
		}
		$data['nama_stok_barang'] = array();
		// $data['stok_opname_detail'] = $this->inv_model->get_stok_opname_detail($id, $select, $select_before, $tanggal_awal, $tanggal_so, $cond_barang);
		$this->load->view('admin/template',$data);
	}

	function stok_opname_lock(){
		$id = $this->input->post('id');
		$data = array(
			'status_aktif' => 1,
			'locked_by' => is_user_id(),
			'locked_date' => date('Y-m-d H:i:s') );
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

	function get_data_stok_opname_detail_by_tanggal(){
		$barang_id = $this->input->post('barang_id');
		$warna_id = $this->input->post('warna_id');
		$tanggal = is_date_formatter($this->input->post('tanggal'));

		$result[0] = $this->common_model->db_select("nd_stok_opname WHERE barang_id_so = $barang_id AND warna_id_so = $warna_id and tanggal = '$tanggal'");
		$result[1] = $this->inv_model->get_stok_opname_detail_by_barang_by_date($barang_id, $warna_id, $tanggal);
		
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

	function stok_opname_detail_insert_2(){
		$stok_opname_id = $this->input->post('stok_opname_id');
		$barang_id = $this->input->post('barang_id');
		$warna_id = $this->input->post('warna_id');
		$gudang_id = $this->input->post('gudang_id');
		$rekap = explode('--', $this->input->post('rekap_qty'));
		$created_at = $this->input->post('created_at');
		$tgl = is_date_formatter($this->input->post('tanggal'));
		
		if ($stok_opname_id == '') {
			$data = array(
			'tanggal' => $tgl,
			'barang_id_so' => $this->input->post('barang_id'),
			'warna_id_so' => $this->input->post('warna_id'),
			'gudang_id_so' => $this->input->post('gudang_id'),
			'user_id' => is_user_id(),
			'created_at' => $tgl.' '.$created_at.':00' );

			$stok_opname_id = $this->common_model->db_insert('nd_stok_opname',$data);
		}
		foreach ($rekap as $key => $value) {
			$dt = explode('??', $value);
			if (isset($dt[2]) && $dt[2] == 0) {
				if ($dt[0] != '' && $dt[0] != null) {
					$data_detail[$key] = array(
						'stok_opname_id' => $stok_opname_id ,
						'barang_id' => $barang_id,
						'warna_id' => $warna_id,
						'gudang_id' => $gudang_id,
						'qty' => $dt[0],
						'jumlah_roll' => $dt[1]
						);
				}
				echo 'dt1';
				print_r($data_detail);
			}else if(isset($dt[2]) && $dt[2] != 0){
				$data_update = array(
					'stok_opname_id' => $stok_opname_id ,
					'barang_id' => $barang_id,
					'warna_id' => $warna_id,
					'gudang_id' => $gudang_id,
					'qty' => $dt[0],
					'jumlah_roll' => $dt[1]
					);

				if($dt[0] != ''){
					$this->common_model->db_update('nd_stok_opname_detail',$data_update,'id', $dt[2]);
					echo 'dt2a';
					print_r($data_update);
				}else{
					$this->common_model->db_delete('nd_stok_opname_detail','id', $dt[2]);
					echo 'delete<hr/>';
					// print_r($data_detail);
				}
			}
		}

		if (isset($data_detail)) {
			// print_r($data);
			$this->common_model->db_insert_batch('nd_stok_opname_detail', $data_detail);
		}

		// $result = $this->common_model->db_select("nd_stok_opname_detail where stok_opname_id=$stok_opname_id and barang_id = $barang_id and warna_id = $warna_id and gudang_id=$gudang_id");

		// // print_r($data);
		// echo json_encode($result);
		print_r($rekap);
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

	function get_stok_opname_belum_lock()
	{

		$get = array();
		$select = '';
		$select_v2 = '';
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

			$select_v2 .= ", ROUND(
				SUM( if(gudang_id=".$row->id.", 
						ifnull(
							if(tanggal_stok is not null, if(time_stamp >= tanggal_stok,qty_masuk,0) , qty_masuk  ),
						0) ,
					0 )
				),2) - 
				ROUND(SUM( if(gudang_id=".$row->id.", 
						ifnull( 
							if(tanggal_stok is not null, if(time_stamp >= tanggal_stok, qty_keluar,0 ), qty_keluar ) 
						,0), 
					0 )
				),
				2)  
				as gudang_".$row->id."_qty ,

				SUM( if(gudang_id=".$row->id.", 
						if(tanggal_stok is not null, 
							if(time_stamp >= tanggal_stok,jumlah_roll_masuk,0 ), 
						jumlah_roll_masuk), 
					0 ) 
				) -
				SUM( if(gudang_id=".$row->id.", 
						if(tanggal_stok is not null,
							if(time_stamp >= tanggal_stok, jumlah_roll_keluar, 0)
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

		$select2 .= ', if(tipe_qty != 3,'.implode('+', $qty_add).',"0") as qty_total, '.implode('+', $roll_add).' as roll_total'.$select_status;


        $tanggal_awal = '2018-01-01';
		$barang_id_list = [];
		$warna_id_list = [];
		$get[1] = $this->inv_model->get_stok_opname_belum_lock();
		$so_up = array();
		$so_now = array();
		foreach ($get[1] as $row) {
			// array_push($barang_id_list, $row->barang_id_so);
			// array_push($warna_id_list, $row->warna_id_so);
			if ($row->created_at_ori != $row->stok_date) {
				if (is_posisi_id()==1) {
					// echo $row->barang_id_so."<br/>". $row->warna_id_so."<br/>". $select_v2."<br/>". $row->created_at_ori."<br/><br/>". $tanggal_awal."<br/>". $select2;
					$get_stok = $this->inv_model->get_stok_for_opname($row->barang_id_so, $row->warna_id_so, $select_v2, $row->created_at_ori, 0, $tanggal_awal, $select2);
				}else{
					$get_stok = $this->inv_model->get_stok_for_opname($row->barang_id_so, $row->warna_id_so, $select_v2, $row->created_at_ori, 0, $tanggal_awal, $select2);
				}
				$qty = 0;
				$jumlah_roll = 0;
				foreach ($get_stok as $row2) {
					$qty = $row2->{'gudang_'.$row->gudang_id_so.'_qty'};
					$jumlah_roll = $row2->{'gudang_'.$row->gudang_id_so.'_roll'};
				}

				array_push($so_up, array(
					'id' => $row->id ,
					'stok_current' => $qty ,
					'roll_current' => $jumlah_roll,
					'stok_date' => $row->created_at_ori)
				);
			}else{
				$qty = $row->stok_current;
				$jumlah_roll = $row->roll_current;
			}
				array_push($so_now, array(
					'barang_id' => $row->barang_id_so ,
					'warna_id' => $row->warna_id_so,
					'gudang_id' => $row->gudang_id_so,
					'qty' => $qty,
					'jumlah_roll' => $jumlah_roll ));
		}

		if (count($so_up) > 0) {
			$this->common_model->db_update_batch("nd_stok_opname", $so_up, "id");
		}
		$get[0] = $so_now;
		if (count($barang_id_list) > 0) {
			// $get[0] = $this->inv_model->get_stok_for_opname(implode(',', $barang_id_list), implode(',', $warna_id_list),$select_v2, date('Y-m-d'), 0, $tanggal_awal, $select2);
		}

		if (is_posisi_id()==1) {
			// echo $row->barang_id_so."<br/>". $row->warna_id_so."<br/>". $select_v2."<br/>". $row->created_at_ori."<br/><br/>". $tanggal_awal."<br/>". $select2;
		}
		echo json_encode($get);
	}

	function stok_opname_remove()
	{
		$id = $this->input->post('id');
		$this->common_model->db_delete("nd_stok_opname_detail", 'stok_opname_id', $id);
		$this->common_model->db_delete("nd_stok_opname", 'id', $id);
		echo "OK";
		
	}

	function stok_opname_lock_banyak(){
		
		$tgl = date('Y');
		$get = $this->common_model->db_select("nd_stok_opname_report WHERE YEAR(created_at) = '$tgl'
			 ORDER BY no_surat desc LIMIT 1");
		$no_surat = 1;
		foreach ($get as $row) {
			$no_surat = $row->no_surat + 1;
		}

		//iniate data no surat baru
		$keterangan = $this->input->post('keterangan');
		$data_new = array(
			'no_surat' => $no_surat,
			'closed_by' => is_user_id(),
			'keterangan' => $keterangan
			);


		$id_report = $this->common_model->db_insert("nd_stok_opname_report", $data_new);
		// iniate data untuk ubah status
		$data = array(
			'status_aktif' => 1,
			'stok_opname_report_id' => $id_report );

		$id_list = [];
		$keterangan_id = [];

		foreach ($this->input->post('data') as $key => $value) {
			$waktu = $value['created_at'];
			$tanggal = date("Y-m-d", strtotime($waktu." -2 minutes") );
			$created_at = date("Y-m-d H:i:s", strtotime($waktu." -2 minutes") );

			array_push($keterangan_id, $value['id']);		
		}
		
		$penyesuaian_stok_id_list = [];
		if (count($keterangan_id) > 0) {
			$keterangan_id_list = implode(',', $keterangan_id);
			$get = $this->common_model->db_select("nd_penyesuaian_stok WHERE tipe_transaksi = 4 AND id IN ($keterangan_id_list)");

			foreach ($get as $row) {
				$penyesuaian_stok_id_list[$row->keterangan] = $row->id;
			}
		}
		
		$data_penyesuaian = [];

		foreach ($this->input->post('data') as $key => $value) {
			$waktu = $value['created_at'];
			$tanggal = date("Y-m-d", strtotime($waktu." -2 minutes") );
			$created_at = date("Y-m-d H:i:s", strtotime($waktu." -2 minutes") );

			$data_set= array(
				'tipe_transaksi' => 4,
				'tanggal' => $tanggal,
				'qty' => $value['qty'],
				'jumlah_roll' => $value['jumlah_roll'],
				'created_at' => $created_at,
				'user_id' => is_user_id(),
				'barang_id' => $value['barang_id'],
				'warna_id' => $value['warna_id'],
				'gudang_id' => $value['gudang_id'],
				'keterangan' => $value['id']
				);
			array_push($id_list, $value['id']);
			if (!isset($penyesuaian_stok_id_list[$value['id']])) {
				array_push($data_penyesuaian, $data_set);
			}else{
				$this->common_model->db_update('nd_penyesuaian_stok', $data_set, 'id', $penyesuaian_stok_id_list[$value['id']]);
			}
			
		}

		

		$this->common_model->db_free_query_superadmin("UPDATE nd_stok_opname SET status_aktif=1 , stok_opname_report_id = $id_report WHERE ID IN (".implode(',', $id_list).")" );
		$this->common_model->db_insert_batch("nd_penyesuaian_stok", $data_penyesuaian);
		echo "OK";

	}
	
//=====================================assembly=============================================

	function assembly_list(){
		$gudang_id = "";
		$barang_id = "";
		$warna_id = "";

		$tanggal_end = date("Y-m-d");
		$tanggal_start = date("Y-m-d", strtotime('-3days'));
		$menu = is_get_url($this->uri->segment(1));

		if ($this->input->get('barang_id') != '') {
			$barang_id = $this->input->get('barang_id');
		}

		if ($this->input->get('warna_id') != '') {
			$warna_id = $this->input->get('warna_id');
		}

		if ($this->input->get('gudang_id') != '') {
			$gudang_id = $this->input->get('gudang_id');
		}

		$data = array(
			'content' =>'admin/inventory/assembly_list',
			'breadcrumb_title' => 'Stok',
			'breadcrumb_small' => 'Assembly',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data'=> $this->data,
			'barang_id' => $barang_id,
			'warna_id' => $warna_id,
			'gudang_id' => $gudang_id,
			'tanggal_start' => is_reverse_date($tanggal_start),
			'tanggal_end' => is_reverse_date($tanggal_end),
			);	
		
		$this->load->view('admin/template',$data);		
	}

	function penyesuaian_stok_split_data_json(){
		$data = json_decode($this->input->post('data'));
		// echo json_encode(json_decode($data));
		$penyesuaian_stok_id = $data->penyesuaian_stok_id;

		$ds = $data->splitData;
		$dt = array(
			'tanggal' => is_date_formatter($data->tanggal),
			'tipe_transaksi' => 3,
			'gudang_id' => $data->gudang_id,
			'barang_id' => $data->barang_id,
			'warna_id' => $data->warna_id,
			'qty' => $data->qty,
			'jumlah_roll' => 1,
			'keterangan' => $data->keterangan,
			'user_id' => is_user_id()
		);

		if ($penyesuaian_stok_id == '') {
			$penyesuaian_stok_id = $this->common_model->db_insert("nd_penyesuaian_stok", $dt);
		}else{
			$this->common_model->db_update("nd_penyesuaian_stok", $dt,'id', $penyesuaian_stok_id);	
		}
		$qty_split = array();
		$id_to_update = array();
		$id_to_delete = array();
		$data_detail_update = [];
		$data_detail_new = [];

		// echo json_encode($ds);

		foreach ($ds as $value) {
			if ($value->id != '' && $value->qty == '') {
				array_push($id_to_delete, $value->id);
			}else if($value->id != '' && $value->qty == 0){
				array_push($id_to_delete, array(
					'id='>$value->id));
			}else if($value->id != '' && $value->qty != ''){
				$data_detail_update[count($data_detail_update)] = array(
					'id'=> $value->id,
					'penyesuaian_stok_id' => $penyesuaian_stok_id,
					'qty' => $value->qty,
					'jumlah_roll' => $value->jumlah_roll
				);
			}else if($value->id == '' && $value->qty != ''){
				$data_detail_new[count($data_detail_new)] = array(
					'penyesuaian_stok_id' => $penyesuaian_stok_id,
					'qty' => $value->qty,
					'jumlah_roll' => $value->jumlah_roll
				);
			}
		}

		if (count($id_to_delete)) {
			$this->common_model->db_delete_batch("nd_penyesuaian_stok_split", "id", $id_to_delete);
		}
		if (count($data_detail_update)) {
			$res = $this->common_model->db_update_batch("nd_penyesuaian_stok_split", $data_detail_update, 'id');
			// echo json_encode($res);
		}
		if (count($data_detail_new)) {
			$this->common_model->db_insert_batch("nd_penyesuaian_stok_split", $data_detail_new );
		}
		echo json_encode("OK");
	}

	function data_penyesuaian(){
		$tanggal_start = is_date_formatter($this->input->post('tanggal_start'));
		$tanggal_end = is_date_formatter($this->input->post('tanggal_end'));
		$gudang_id = $this->input->post('gudang_id');
		$barang_id = $this->input->post('barang_id');
		$warna_id = $this->input->post('warna_id');

		$cond_gudang = ($gudang_id == '' ? '' : "AND gudang_id = '$gudang_id'");
		$cond_barang = ($barang_id == '' ? '' : "AND barang_id = '$barang_id'");
		$cond_warna = ($warna_id == '' ? '' : "AND warna_id = '$warna_id'");

		$result = $this->inv_model->get_data_penyesuaian_list($tanggal_start, $tanggal_end, $cond_gudang, $cond_barang, $cond_warna);
		// echo $tanggal_start, $tanggal_end, $cond_gudang, $cond_barang, $cond_warna;
		$resp = array();
		foreach ($result as $row) {
			$qty_data = explode(",",$row->qty_data);
			$roll_data = explode(",",$row->jumlah_roll_data);
			$split_id = explode(",",$row->split_id);
			$split_data = array();
			foreach ($qty_data as $key => $value) {
				array_push($split_data,array(
					'id'=>$split_id[$key],
					'penyesuaian_stok_id'=>$row->penyesuaian_stok_id,
					'qty'=>(float)$value,
					'jumlah_roll'=>$roll_data[$key],
				));
			}
			array_push($resp, array(
				'id'=>$row->id,
				'tanggal'=>is_reverse_date($row->tanggal),
				'gudang_id'=>$row->gudang_id,
				'barang_id'=>$row->barang_id,
				'warna_id'=>$row->warna_id,
				'nama_gudang'=>$row->nama_gudang,
				'nama_barang'=>$row->nama_barang,
				'nama_warna'=>$row->nama_warna,
				'qty'=>(float)$row->qty,
				'jumlah_roll'=>$row->jumlah_roll,
				'qty_data'=>array(
					'qty'=>$row->qty_data,
					'jumlah_roll'=>$row->jumlah_roll_data,
				),
				'keterangan'=>$row->keterangan,
				'user'=>array(
					'user_id'=>$row->user_id,
					'username'=>$row->username
				),
				'splitData'=>$split_data
			));
		}
		echo json_encode($resp);
	}

	function penyesuaian_stok_split_remove(){
		$pin = $this->input->post('pin');
		$id = $this->input->post('id');
		if ($this->cek_pin($pin)) {
			$this->common_model->db_delete("nd_penyesuaian_stok",'id', $id);
			$this->common_model->db_delete("nd_penyesuaian_stok_split",'penyesuaian_stok_id', $id);
			echo json_encode("OK");
		}else{
			echo json_encode("PIN Salah!");
		}
	}

	function penyesuaian_stok_split_remove_no_pin(){
		$id = $this->input->post('id');
		if ($id != '') {
			$this->common_model->db_delete("nd_penyesuaian_stok",'id', $id);
			$this->common_model->db_delete("nd_penyesuaian_stok_split",'penyesuaian_stok_id', $id);
			echo json_encode("OK");
		}else{
			echo json_encode("NO DATA");
		}
	}

//=====================================tutup buku=============================================


	// function generateMutasi53ke57()
	// {
	// 	// $get = $this->common_model->db_free_query_superadmin("SELECT barang_id, warna_id, sum(qty * if(jumlah_roll=0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll
	// 	// 	FROM nd_stok_opname_detail
	// 	// 	WHERE stok_opname_id=43
	// 	// 	GROUP BY barang_id, warna_id");


	// 	// $nData;
	// 	// $idx = 0;
	// 	// foreach ($get->result() as $row) {
	// 	// 	$nData[$idx] = array(
	// 	// 		'tanggal' => '2021-09-10',
	// 	// 		'barang_id' => $row->barang_id ,
	// 	// 		'warna_id' => $row->warna_id,
	// 	// 		'gudang_id_before' => 2,
	// 	// 		'gudang_id_after' =>4,
	// 	// 		'qty' => $row->qty,
	// 	// 		'jumlah_roll' => $row->jumlah_roll,
	// 	// 		'user_id' => 1,
	// 	// 		'created_at' => '2021-09-10 23:59:00'
	// 	// 	);
	// 	// 	$idx++;
	// 	// }

	// 	// $gDetail;
	// 	// $nDetail;

	// 	// foreach ($get_detail as $row) {
	// 	// 	if (!isset($nDetail[$row->barang_id])) {
	// 	// 		$gDetail[$row->barang_id][$row->warna_id] = array();
	// 	// 	}

	// 	// 	array_push($gDetail[$row->barang_id][$row->warna_id], $row);
	// 	// }

	// 	// $this->common_model->db_insert_batch("nd_mutasi_barang", $nData);

	// 	$get_detail = $this->common_model->db_select("nd_stok_opname_detail WHERE stok_opname_id=43");
	// 	// print_r($get_detail);

	// 	$gDetail;
	// 	$nDetail;

	// 	foreach ($get_detail as $row) {
	// 		if (!isset($gDetail[$row->barang_id][$row->warna_id])) {
	// 			$gDetail[$row->barang_id][$row->warna_id] = array();
	// 		}
	// 		array_push($gDetail[$row->barang_id][$row->warna_id], array(
	// 			'qty' => $row->qty,
	// 			'jumlah_roll' => $row->jumlah_roll
	// 		));
	// 	}

	// 	// print_r($gDetail);
	// 	// echo '<hr/>';	


	// 	$get_new = $this->common_model->db_select("nd_mutasi_barang WHERE created_at = '2021-09-10 23:59:00'");
	// 	$idx = 0;
	// 	foreach ($get_new as $row) {
	// 		foreach ($gDetail[$row->barang_id][$row->warna_id] as $value) {
	// 			$nDetail[$idx] = array(
	// 				'mutasi_barang_id' => $row->id ,
	// 				'qty' => $value['qty'],
	// 				'jumlah_roll' => $value['jumlah_roll']
	// 			);
	// 			$idx++;
	// 		}
	// 	}

	// 	// print_r($nDetail);

	// 	// $this->common_model->db_insert_batch("nd_mutasi_barang_qty", $nDetail);


	// 	// INSERT INTO nd_mutasi_barang(tanggal, barang_id, warna_id, gudang_id_before, gudang_id_after, qty, jumlah_roll, status_aktif, user_id, created_at)
	// 	// SELECT '2021-09-10', barang_id, warna_id, 2, 4, sum(qty * if(jumlah_roll=0,1,jumlah_roll)), sum(jumlah_roll),1,1,'2021-09-10 23:59:00'
	// 	// FROM nd_stok_opname_detail
	// 	// WHERE stok_opname_id=43
	// 	// GROUP BY barang_id, warna_id
	// }

//=====================================penerimaan barang=============================================

	function penerimaan_barang(){
		$menu = is_get_url($this->uri->segment(1)) ;

		if ($this->input->get('from')) {
			$from = is_date_formatter($this->input->get('from'));
			$to = is_date_formatter($this->input->get('to'));
		}else{
			$from = date("Y-m-d", strtotime('-30 day')); 
			$to = date("Y-m-d");
		}

		$data = array(
			'content' =>'admin/inventory/penerimaan_barang_by_admin',
			'breadcrumb_title' => 'Inventory',
			'breadcrumb_small' => 'Penerimaan Barang By Admin',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data'=> $this->data,
			'from' => $from,
			'to' => $to );


		$data['penerimaan_barang_unconfirmed'] = $this->inv_model->get_penerimaan_barang_unconfirmed(); 
		$data['penerimaan_barang'] = $this->inv_model->get_penerimaan_barang($from, $to); 
		
		$this->load->view('admin/template',$data);
		if(is_posisi_id() == 1){
			$this->output->enable_profiler(TRUE);
		}
	}

	function penerimaan_barang_insert(){
		$no_plat = $this->input->post('no_plat');
		$tanggal_input = $this->input->post('tanggal_input');

		if($no_plat == '' || $tanggal_input == ''){
			echo "Invalid data received.";
			return;
		}

		$tanggal_ex = explode('-', $this->input->post('tanggal_input'));
		$tanggal_input = date('Y-m-d', strtotime($tanggal_ex[0]))." ".$tanggal_ex[1].":00:00";

		$data_mobil = array(
			'no_plat' => $no_plat,
			'tanggal_input' => $tanggal_input,
			'user_id' => is_user_id()
		);

		
		$penerimaan_barang_id = $this->common_model->db_insert("nd_penerimaan_barang", $data_mobil);
		if($penerimaan_barang_id){
			$data_status = array(
				'penerimaan_barang_id' => $penerimaan_barang_id,
				'status_penerimaan' => "MENUNGGU_DATA_GUDANG",
			);
			$this->common_model->db_insert("nd_penerimaan_barang_status", $data_status);
		}
		

		redirect(is_setting_link('inventory/penerimaan_barang'));
		
	}

	function remove_penerimaan_barang(){

		$id = $this->input->post('id');
		if ($id != '') {
			
			$res = $this->common_model->db_free_query_superadmin("SELECT count(barang_id) as total_barang 
				FROM nd_pembelian tA
				LEFT JOIN nd_pembelian_detail tB ON tA.id = tB.pembelian_id
				WHERE tA.penerimaan_barang_id = $id");

			$total_barang = 0;
			foreach($res->result() as $row){
				$total_barang = $row->total_barang;
			}

			if($total_barang > 0){
				echo json_encode(["status" => "reject", "message" => "Penerimaan barang cannot be removed because it has items registered."]);
				return;
			}else{
				$this->common_model->db_free_query_superadmin("UPDATE nd_pembelian SET penerimaan_barang_id = null WHERE penerimaan_barang_id = $id");
				$this->common_model->db_delete("nd_penerimaan_barang", 'id', $id);
				echo json_encode(["status" => "success", "message" => "Penerimaan barang has been removed successfully."]);
			}

		} else {
			echo json_encode(["status" => "error", "message" => "Invalid data received."]);
		}
		
		/* $this->common_model->db_free_query_superadmin("UPDATE nd_pembelian SET penerimaan_barang_id = null WHERE penerimaan_barang_id = $id");
		$this->common_model->db_delete("nd_penerimaan_barang",'id', $id);
		echo json_encode(["status" => "success", "message" => "Penerimaan barang has been removed successfully."]); */
	}

	function update_status_penerimaan_barang(){
		$id = $this->input->post('id');
		$status_penerimaan = $this->input->post('status_penerimaan');

		if($id == '' || $status_penerimaan == ''){
			echo json_encode(["status" => "error", "message" => "Invalid data received."]);
			return;
		}
		$data = array(
			'penerimaan_barang_id' => $id,
			'status_penerimaan' => $status_penerimaan
			);
		$this->common_model->db_insert("nd_penerimaan_barang_status", $data);
		echo json_encode(["status" => "success", "message" => "Penerimaan barang has been removed successfully."]);

	}

}