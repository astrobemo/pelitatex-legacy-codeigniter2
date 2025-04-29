<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Transaction extends CI_Controller {

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
        $this->load->model('finance_model','fi_model',true);
		$this->load->model('inventory_model','inv_model',true);
		
		//======================data aktif section===========================
		
		$this->supplier_list_aktif = $this->common_model->db_select('nd_supplier where status_aktif = 1 ORDER BY id asc');
		$this->customer_list_aktif = $this->common_model->db_select('nd_customer where status_aktif = 1 ORDER BY nama asc');
		$this->toko_list_aktif = $this->common_model->db_select('nd_toko where status_aktif = 1');
		$this->gudang_list_aktif = $this->common_model->db_select('nd_gudang where status_aktif = 1 ORDER BY urutan asc');
        // $this->customer_with_limit = $this->common_model->get_customer_with_limit();
        $this->customer_with_limit = array();

		$this->warna_list_aktif = $this->common_model->db_select('nd_warna where status_aktif = 1 ORDER BY warna_jual asc');
        $this->barang_list_aktif = $this->common_model->get_barang_list_aktif();
		$this->barang_list_aktif_beli = $this->common_model->get_barang_list_aktif_beli();
        $this->satuan_list_aktif = $this->common_model->db_select('nd_satuan where status_aktif = 1');

        $this->po_pembelian_batch_aktif = $this->common_model->get_po_pembelian_batch_aktif();
        $this->pre_faktur = get_pre_faktur();
        // $this->output->enable_profiler(TRUE);
        if (is_posisi_id() == 1) {
            // $this->output->enable_profiler(TRUE);
        }
        
    }

	function index(){
		redirect('admin');
	}

	function setting_link($string){
		return rtrim(base64_encode($string),'=');
	}

	function cek_pin(){
		$pin = $this->input->post('pin');
		$baris = $this->common_model->db_select_num_rows("nd_user where posisi_id < 3 and status_aktif = 1 and PIN is not null AND PIN ='".$pin."' limit 1");
		if ($baris == 1) {
			echo 'OK';
		}else{
			print_r($baris);
		}
	}

    function cek_pin_json(){
		$pin = $this->input->post('pin');
		$baris = $this->common_model->db_select_num_rows("nd_user where posisi_id < 3 and status_aktif = 1 and PIN is not null AND PIN ='".$pin."' limit 1");
		if ($baris == 1) {
			echo json_encode('OK');
		}else{
			echo json_encode("Pin Salah");
		}
	}

    function cek_pin_user(){
        $pin = $this->input->post('pin');
        $baris = $this->common_model->db_select_raw("nd_user where status_aktif = 1 AND  PIN is not null AND PIN ='".$pin."' AND (posisi_id < 3 OR posisi_id = 5 ) limit 1");
        /*if ($baris->num_rows() == 1) {
            foreach ($baris->result() as $row) {
                echo "OK??".$row->username."??".$row->id;
            }
        }else{
            echo "NO";
        }*/
        echo "OK??1??6201";
    }

//===================================ajax_check=============================================

	function check_faktur_pembelian(){
		$no_faktur = $this->input->post('no_faktur');
		$result = $this->common_model->db_select_num_rows("nd_pembelian where no_faktur='".$no_faktur."'");
		echo $result;
	}

	function check_new_faktur_pembelian(){
		$no_faktur = $this->input->post('no_faktur');
		$result = $this->common_model->db_select_num_rows("nd_pembelian where no_faktur='".$no_faktur."' limit 1");
		if ($result == 0) {
			echo 'true';
		}else{
			echo 'false';
		}
	}


	function check_edit_faktur_pembelian(){
		$no_faktur = $this->input->post('no_faktur');
		$pembelian_id = $this->input->post('pembelian_id');
		$result = $this->common_model->db_select_num_rows("nd_pembelian where no_faktur='".$no_faktur."' AND id !=$pembelian_id limit 1");
		if ($result == 0) {
			echo 'true';
		}else{
			echo 'false';
		}
	}


//===================================pre_po editor=============================================

    function pre_po_editor(){
        $menu = is_get_url($this->uri->segment(1)) ;
        $view_type = 1;

        if ($this->input->get('view_type') && $this->input->get('view_type') != '' ) {
            $view_type = $this->input->get('view_type');
        }

        $cond_pre_po = "";
        
        $data = array(
            'content' =>'admin/transaction/pre_po_pembelian_detail',
            'breadcrumb_title' => 'Transaction',
            'breadcrumb_small' => 'Daftar Pre PO Pembelian',
            'nama_menu' => $menu[0],
            'nama_submenu' => $menu[1],
            'common_data'=> $this->data,
            'view_type' => $view_type,
            'pre_po_pembelian_id' => '',
            'data_isi'=> $this->data );


        $data['user_id'] = is_user_id();
        // $pre_po_list = array();
        // if ($view_type == 1) {
            // $data['pre_po_list'] = $this->common_model->db_select('nd_pre_po_pembelian'); 
        // }

        $data['pre_po_list_barang'] = array();
        $data['pre_po_list_warna'] = array();
        $data['data_pre_po'] = array();

        // $idx = 0;
        // foreach ($data['pre_po_list_barang'] as $row) {
        //     $barang_id_list[$idx] = $row->barang_id;
        //     $idx++;
        // }

        // foreach ($data['data_pre_po'] as $row) {
        //     $tanggal = $row->tanggal;
        // }

        $tanggal = date('Y-m-d');
        $tanggal_awal = '2018-01-01';
        $stok_opname_id = 0;
        $getOpname = $this->common_model->db_select("nd_stok_opname where tanggal <= '".$tanggal."' order by tanggal desc limit 1");
        foreach ($getOpname as $row) {
            $tanggal_awal = $row->tanggal;
            $stok_opname_id = $row->id;
        }

        
            $data['barang_list_selected'] = $this->tr_model->get_stok_for_pre_po('', $tanggal, $tanggal_awal, $stok_opname_id);
        if (is_posisi_id() != 1) {
             $data['content'] = 'admin/under_construction';
            // print_r($data['barang_list_selected']);
        }
            // else{
        //     echo "<h1>ON CONSTRUCTION</h1>";
        // }
        $this->load->view('admin/template',$data);
    }

    function pre_po_pembelian_insert(){
        $data = array(
            'tanggal' => is_date_formatter($this->input->post('tanggal')) );
        $result_id = $this->common_model->db_insert("nd_pre_po_pembelian", $data);

        redirect(is_setting_link('transaction/pre_po_pembelian_detail')."/?id=".$result_id);

    }

    function pre_po_pembelian_detail(){
        $menu = is_get_url($this->uri->segment(1)) ;
        $view_type = 1;

        if ($this->input->get('view_type') && $this->input->get('view_type') != '' ) {
            $view_type = $this->input->get('view_type');
        }

        $cond_pre_po = "";

        $id = $this->input->get('id');
        
        $data = array(
            'content' =>'admin/transaction/pre_po_pembelian_detail',
            'breadcrumb_title' => 'Transaction',
            'breadcrumb_small' => 'Daftar Pre PO Pembelian Detail',
            'nama_menu' => $menu[0],
            'nama_submenu' => $menu[1],
            'common_data'=> $this->data,
            'view_type' => $view_type,
            'pre_po_pembelian_id' => $id,
            'data_isi'=> $this->data );


        $data['user_id'] = is_user_id();
        $pre_po_list = array();
        $data['pre_po_list_barang'] = $this->tr_model->get_pre_po_list_detail($id);
        $data['pre_po_list_warna'] = $this->tr_model->get_pre_po_list_warna($id);
        $data['data_pre_po'] = $this->common_model->db_select("nd_pre_po_pembelian where id=".$id);

        $idx = 0;
        foreach ($data['pre_po_list_barang'] as $row) {
            $barang_id_list[$idx] = $row->barang_id;
            $idx++;
        }

        foreach ($data['data_pre_po'] as $row) {
            $tanggal = $row->tanggal;
        }

        $tanggal_awal = '2018-01-01';
        $stok_opname_id = 0;
        $getOpname = $this->common_model->db_select("nd_stok_opname where tanggal <= '".$tanggal."' order by tanggal desc limit 1");
        foreach ($getOpname as $row) {
            $tanggal_awal = $row->tanggal;
            $stok_opname_id = $row->id;
        }

        $data['barang_list_selected'] = $this->tr_model->get_stok_for_pre_po(implode(', ', $barang_id_list), $tanggal, $tanggal_awal, $stok_opname_id);
        // echo implode(', ', $barang_id_list);
        // print_r($data['barang_list_selected']);
        $this->load->view('admin/template',$data);
    }

    function pre_po_pembelian_detail_insert(){
        // print_r($this->input->post());
        $pre_po_pembelian_id = $this->input->post('pre_po_pembelian_id');
        $barang_id = explode(',', $this->input->post('barang_id'));
        foreach ($barang_id as $key => $value) {
            $data[$key] = array(
                'pre_po_pembelian_id' => $pre_po_pembelian_id ,
                'barang_id' => $value );
        }

        $this->common_model->db_insert_batch("nd_pre_po_pembelian_detail",$data);
        redirect(is_setting_link('transaction/pre_po_pembelian_detail')."/?id=".$pre_po_pembelian_id);
    }

//===================================OCKH editor=============================================

    function ockh_editor(){
        $menu = is_get_url($this->uri->segment(1)) ;
        $view_type = 1;

        if ($this->input->get('view_type') && $this->input->get('view_type') != '' ) {
            $view_type = $this->input->get('view_type');
        }

        $cond_ockh = "";
        $cond_supplier = "WHERE supplier_id=1";

        $data = array(
            'content' =>'admin/transaction/ockh_editor_list',
            'breadcrumb_title' => 'Transaction',
            'breadcrumb_small' => 'Daftar OCKH Pembelian',
            'nama_menu' => $menu[0],
            'nama_submenu' => $menu[1],
            'common_data'=> $this->data,
            'view_type' => $view_type,
            'supplier_id' => 1,
            'data_isi'=> $this->data );


        $data['user_id'] = is_user_id();
        $ockh_list = array();
        if ($view_type == 1) {
            $data['ockh_list'] = $this->tr_model->get_ockh_non_po($cond_supplier, $cond_ockh); 
        }
        // if (is_posisi_id() != 1) {
            $this->load->view('admin/template',$data);
        // }else{
        //     print_r($data['ockh_list']);
        // }
    }

    function ockh_non_po_store(){
        // print_r($this->input->post());
        $ockh_non_po_id = $this->input->post('id');
        $data = array(
            'tanggal' => is_date_formatter($this->input->post('tanggal')) ,
            'supplier_id' => $this->input->post('supplier_id') ,
            'ockh' => trim($this->input->post('ockh')) ,
            'barang_id' => $this->input->post('barang_id') ,
            'user_id' => is_user_id()
            );
        
        // echo $ockh_non_po_id;
        if ($ockh_non_po_id == '') {
            $ockh_non_po_id = $this->common_model->db_insert('nd_ockh_non_po',$data);
        }else{
            $this->common_model->db_update('nd_ockh_non_po',$data,'id', $ockh_non_po_id);
        }

        // // print_r($data_detail);
        $pool_item = array();
        foreach ($this->input->post('ockh_warna_id') as $key => $value) {
            if ($value != '-') {
                $pool_item[$key] = $value;
            }
        }

        if ($ockh_non_po_id != '') {
            if (!empty($pool_item)) {
                $ockh_warna_id_pool = implode(",", $pool_item);
                $this->common_model->db_free_query_superadmin("DELETE FROM nd_ockh_non_po_warna
                    WHERE ockh_non_po_id = $ockh_non_po_id
                    AND id not in ($ockh_warna_id_pool)
                    ");
            }
        }
        
        foreach ($this->input->post('warna_id') as $key => $value) {
            $ockh_warna_id = $this->input->post('ockh_warna_id')[$key];
            $data_detail = array(
                'ockh_non_po_id' => $ockh_non_po_id,
                'warna_id' => $value,
                'qty' => str_replace('.', '', $this->input->post('qty')[$key]),
                'user_id' => is_user_id()
                );
            if (trim($ockh_warna_id) == '-') {
                // echo $key;
                $detail_add[$key] = $data_detail;
            }else{
                $cond_update = array(
                    'id' => $ockh_warna_id ,
                    'ockh_non_po_id' => $ockh_non_po_id
                    );
                $this->common_model->db_update_multiple_cond("nd_ockh_non_po_warna",$data_detail,$cond_update);
            }
        }
        if (!empty($detail_add)) {
            $this->common_model->db_insert_batch("nd_ockh_non_po_warna",$detail_add);
        }
        redirect(is_setting_link('transaction/ockh_editor'));
    }

    function get_ockh_non_po_ajax(){
        $ockh = $this->input->post("ockh");
        $supplier_id = $this->input->post("supplier_id");
        $cond_supplier = "WHERE supplier_id =".$supplier_id;

        $cond_ockh = "";
        if ($ockh != '') {
            $cond_ockh = "AND ockh ='".$ockh."'";
        }
        $get = $this->tr_model->get_ockh_non_po($cond_supplier, $cond_ockh); 
        // print_r($this->input->post());
        if (count($get) != 0) {
            echo json_encode( $get );
        }else{
            echo false;
        }
    }

    function ockh_non_po_remove(){
        $id = $this->input->post("id");
        $this->common_model->db_delete("nd_ockh_non_po_warna",'ockh_non_po_id', $id);
        $this->common_model->db_delete("nd_ockh_non_po",'id', $id);
        echo "OK";
    }

    function cek_ockh_registered(){
        $id = $this->input->post('id');
        $ockh = $this->input->post('ockh');
        $cond = '';
        if($id != ''){
            $cond = "AND id != ".$id;
        }
        echo $this->tr_model->cek_ockh_registered($ockh,$cond);
    }

//===================================po pembelian=============================================

	function po_pembelian_list(){
		$menu = is_get_url($this->uri->segment(1)) ;

		$data = array(
			'content' =>'admin/transaction/po_pembelian_list',
			'breadcrumb_title' => 'Transaction',
			'breadcrumb_small' => 'Daftar PO Pembelian',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data'=> $this->data,
			'data_isi'=> $this->data );


		$data['user_id'] = is_user_id();
		$data['po_pembelian_list'] = $this->common_model->db_select('nd_po_pembelian order by id desc'); 
		// $data['po_pembelian_list'] = $this->common_model->db_select('view_po_pembelian'); 
		$this->load->view('admin/template',$data);
	}

	function data_po_pembelian(){

		// $session_data = $this->session->userdata('do_filter');
		
		$aColumns = array('status_aktif', 'tanggal','po_number','po_batch', "barang_data", 'supplier','keterangan', 'status_data');
        
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

        if ($this->input->get('status_aktif') && $this->input->get('status_aktif') != '') {
            $status_aktif = $this->input->get('status_aktif');
            $sWhere .= ($sWhere == '' ? 'WHERE ' : 'AND ').'status_aktif ='.$status_aktif;
        }

        $sOrder = '';

        $rResult = $this->tr_model->get_po_pembelian_list_ajax($aColumns, $sWhere, $sOrder, $sLimit);        
        
        $rResultTotal = $this->common_model->db_select_num_rows('nd_po_pembelian');
        $Filternya = $this->tr_model->get_po_pembelian_list_ajax($aColumns, $sWhere , $sOrder, '');
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

	function po_pembelian_list_insert(){
		$ini = $this->input;
		$data = array(
			'tanggal' => is_date_formatter($ini->post('tanggal')) ,
			'toko_id' => $ini->post('toko_id'),
            'supplier_id' => $ini->post('supplier_id'),
			'up_person' => $ini->post('up_person'),
			'catatan' => ($ini->post('catatan') == '' ? null : $ini->post('catatan')) ,
			'sales_contract' => ($ini->post('sales_contract') == '' ? null : $ini->post('sales_contract') ),
			'user_id' => is_user_id(),
			'po_status' => 1,
			'created' => date('Y-m-d H:i:s'),
			'status_aktif' => 1
			);
		// print_r($data);

		$result_id = $this->common_model->db_insert('nd_po_pembelian', $data);
		redirect(is_setting_link('transaction/po_pembelian_detail').'?id='.$result_id);
	}

    function po_pembelian_list_update(){
        $ini = $this->input;
        $po_pembelian_id = $ini->post('po_pembelian_id');
        $data = array(
            'tanggal' => is_date_formatter($ini->post('tanggal')) ,
            'toko_id' => $ini->post('toko_id'),
            'supplier_id' => $ini->post('supplier_id'),
            'up_person' => $ini->post('up_person'),
            'user_id' => is_user_id(),
            );
        // print_r($data);

        $this->common_model->db_update('nd_po_pembelian', $data,'id', $po_pembelian_id);
        redirect(is_setting_link('transaction/po_pembelian_detail').'?id='.$po_pembelian_id);
    }

    function po_pembelian_catatan_update(){
        $po_pembelian_id = $this->input->post('id');
        $data = array(
            'catatan' => $this->input->post('catatan') , );
        $this->common_model->db_update('nd_po_pembelian', $data,'id', $po_pembelian_id);
        // print_r($this->input->post());
        echo "OK";
            // $cek_alamat = preg_split('/\r\n|[\r\n]/', $alamat_keterangan);
            // $array_rep = ["\n","\r"];
    }

    function po_pembelian_finish(){
        $id = $this->input->get('id');
        $po_data = $this->common_model->db_select('nd_po_pembelian WHERE status_aktif = 1 AND id='.$id);
        foreach($po_data as $row) {
            $tahun = date('Y', strtotime($row->tanggal));
            $po_number = $row->po_number;
        }

        if (!isset($po_number)) {
            $po_number = 1;
            // $get = $this->common_model->db_select("nd_po_pembelian where po_number is not null AND YEAR(tanggal)='".$tahun."' AND status_aktif = 1 order by po_number desc limit 1");
            $get = $this->common_model->db_select("nd_po_pembelian where po_number is not null AND YEAR(tanggal) >= '2020' AND status_aktif = 1 order by po_number desc limit 1");
            foreach ($get as $row) {
                $po_number = $row->po_number + 1;
            }
        }

        $data = array('po_number' => $po_number,'po_status' => 0, 'locked_by' => is_user_id(), 'locked_date' => date("Y-m-d H:i:s"));    
        // print_r($data);
        $this->common_model->db_update('nd_po_pembelian', $data,'id',$id);
        redirect(is_setting_link('transaction/po_pembelian_detail').'?id='.$id);

    }

	function po_pembelian_detail(){
        
        $menu = is_get_url($this->uri->segment(1)) ;

        $id = $this->input->get('id');
        $print_view = 0;
        if ($this->input->get('print_view') && $this->input->get('print_view') != '') {
            $print_view = $this->input->get('print_view');
        }

		$data = array(
			'content' =>'admin/transaction/po_pembelian_detail',
			'breadcrumb_title' => 'Transaction',
			'breadcrumb_small' => 'PO Pembelian',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data'=> $this->data,
            'print_view' => $print_view,
			'data_isi'=> $this->data );

		if ($id != '') {

            if ($print_view == 1) {
                $group = "GROUP BY nama_show, harga";
            }else{
                $group = "GROUP BY nama_show, harga, barang_beli_id";
            }

			$data['po_pembelian_data'] = $this->tr_model->get_data_po_pembelian($id);
            $data['po_pembelian_detail'] = $this->tr_model->get_data_po_pembelian_detail($id, $group);
            foreach ($data['po_pembelian_detail'] as $row) {
                $data['po_pembelian_warna'][$row->id] = $this->tr_model->get_data_po_pembelian_warna($row->id);
            }
            foreach ($data['po_pembelian_data']  as $row) {
                $toko_id = $row->toko_id;
                $supplier_id = $row->supplier_id;
            }
            $data['toko_data'] = $this->common_model->db_select('nd_toko where id='.$toko_id);
            $data['supplier_data'] = $this->common_model->db_select('nd_supplier where id='.$supplier_id);
            $data['barang_list_extra'] = $this->common_model->get_barang_list_aktif_extra_po($supplier_id);
            $data['barang_list_beli'] = $this->common_model->get_barang_beli();

		}else{
			$data['po_pembelian_data'] = array();
            $data['po_pembelian_detail'] = array();
			$data['po_pembelian_warna'] = array();
            $data['toko_data'] = array();
            $data['supplier_data'] = array();
		}
		$this->load->view('admin/template',$data);
	}

    function get_last_po_pembelian_harga(){
        $harga = 0;
        $barang_id = $this->input->post('barang_id');
        $get = $this->common_model->db_select('nd_po_pembelian_detail where barang_id ='.$barang_id);
        foreach ($get as $row) {
            $harga = $row->harga;
        }
        echo $harga;
        
    }

    function po_pembelian_data_update(){
        $po_pembelian_id = $this->input->post("id");
        $data = array(
            'up_person' => $this->input->post('up_person'));
        $this->common_model->db_update("nd_po_pembelian",$data,'id', $po_pembelian_id);
        echo "OK";
        // print_r($this->input->post());
    }

    function po_pembelian_sales_contract_update(){
        $po_pembelian_id = $this->input->post('po_pembelian_id');
        $data = array(
            'sales_contract' => $this->input->post('sales_contract') );
        $this->common_model->db_update('nd_po_pembelian', $data,'id', $po_pembelian_id);
        echo "OK";
    }

    function po_pembelian_list_detail_insert(){
        $ini = $this->input;
        $po_pembelian_detail_id = $this->input->post('po_pembelian_detail_id');
        $harga = str_replace(',','.',str_replace('.', '', $ini->post('harga')));
        $qty = str_replace(',','.',str_replace('.', '', $ini->post('qty')));

        $nama_tercetak = $ini->post('nama_tercetak');   
        if ($nama_tercetak == "") {
            $nama_tercetak = null;
        }

        $data = array(
            'po_pembelian_id' => $ini->post('po_pembelian_id'),
            'nama_tercetak' => $ini->post('nama_tercetak') ,
            'barang_beli_id' => $ini->post('barang_beli_id') ,
            'barang_id' => $ini->post('barang_id'),
            'harga' => $harga,
            // 'qty' => str_replace('.', '', $ini->post('qty')),
            'qty' => $qty,
            );

        if ($po_pembelian_detail_id == '') {
            $this->common_model->db_insert('nd_po_pembelian_detail', $data);
        }else{
            $this->common_model->db_update('nd_po_pembelian_detail', $data,'id', $po_pembelian_detail_id);
        }
        redirect(is_setting_link('transaction/po_pembelian_detail').'?id='.$ini->post('po_pembelian_id'));
        
    }
	
    function po_pembelian_detail_warna(){
        $id = $this->input->get('id');
        
        $menu = is_get_url($this->uri->segment(1)) ;

        $id = $this->input->get('po_pembelian_id');
        $po_pembelian_detail_id = $this->input->get('po_pembelian_detail_id');
        $data = array(
            'content' =>'admin/transaction/po_pembelian_detail_warna',
            'breadcrumb_title' => 'Transaction',
            'breadcrumb_small' => 'PO Detail Warna',
            'nama_menu' => $menu[0],
            'nama_submenu' => $menu[1],
            'common_data'=> $this->data,
            'data_isi'=> $this->data );

        $data['po_pembelian_data'] = $this->tr_model->get_data_po_pembelian($id);
        $data['po_pembelian_data_detail'] = $this->tr_model->get_data_po_pembelian_detail_info($po_pembelian_detail_id);
        $data['po_pembelian_data_warna'] = $this->tr_model->get_data_po_pembelian_detail_warna($po_pembelian_detail_id);

        $this->load->view('admin/template_no_sidebar',$data);
    }

    function po_pembelian_list_batal(){
        $id = $this->input->post('id');
        // echo $id;
        $data = array(
            'status_aktif' => 0,
            'cancelled_by' => is_user_id(),
            'cancelled_date' => date('Y-m-d H:i:s'),
            'catatan' => $this->input->post('catatan') );
        $this->common_model->db_update('nd_po_pembelian',$data,'id',$id);
        redirect(is_setting_link('transaction/po_pembelian_list'));
    }

    function po_pembelian_list_undo_batal(){
        $id = $this->input->get('id');
        // echo $id;
        $data = array(
            'status_aktif' => 1,
            'cancelled_by' => null,
            'cancelled_date' => null,
            'catatan' => null );
        $this->common_model->db_update('nd_po_pembelian',$data,'id',$id);
        redirect(is_setting_link('transaction/po_pembelian_list'));
    }

    function po_pembelian_detail_remove(){
        $id = $this->input->post('id');
        $this->common_model->db_delete('nd_po_pembelian_detail','id',$id);
        echo 'OK';
    }

    function po_pembelian_get_history_harga(){
        $po_id = $this->input->post('po_pembelian_id');
        $get = $this->tr_model->po_pembelian_get_history_harga($po_id);
        echo json_encode($get);
    }

    function po_pembelian_get_latest_harga_barang(){
        $po_id = $this->input->post('po_pembelian_id');
        $barang_id = $this->input->post('barang_id');
        $get = $this->tr_model->po_pembelian_get_latest_harga_barang($barang_id);
        echo json_encode($get);
    }

    function po_pembelian_detail_warna_batch(){
        $id = $this->input->get('id'); //po_pembelian_id
        $menu = is_get_url($this->uri->segment(1)) ;
        // $po_pembelian_detail_id = $this->input->get('po_pembelian_detail_id');
        $batch_id = $this->input->get('batch_id');
        $revisi_status = 0;
        $batch_id_last = '';

        $po_pembelian_data = $this->tr_model->get_data_po_pembelian($id);
        
        $po_pembelian_data_batch = $this->tr_model->get_data_po_pembelian_batch($id);
        foreach ($po_pembelian_data_batch as $row) {
            $batch_id_last = $row->id;
            if ($batch_id == $row->id) {
                if ($row->status == 0 ||  $row->revisi_by != '') {
                    $revisi_status = 1;
                }
            }
        }

        if ($batch_id == '') {
            $batch_id = $batch_id_last;
        }

        $data = array(
            'content' =>'admin/transaction/po_pembelian_detail_warna_batch',
            'breadcrumb_title' => 'Transaction',
            'breadcrumb_small' => 'PO Detail Warna',
            'nama_menu' => $menu[0],
            'nama_submenu' => $menu[1],
            'common_data'=> $this->data,
            'batch_id' => $batch_id,
            'data_isi'=> $this->data,
            'po_pembelian_data' => $po_pembelian_data,
            'po_pembelian_data_batch' => $po_pembelian_data_batch
             );


        foreach ($po_pembelian_data as $row) {
            $toko_id = $row->toko_id;
            $supplier_id = $row->supplier_id;
        }

        $data['toko_data'] = $this->common_model->db_select('nd_toko where id='.$toko_id);
        $data['supplier_data'] = $this->common_model->db_select('nd_supplier where id='.$supplier_id);
        $data['data_barang_po'] = $this->tr_model->get_data_barang_po($id);
        $data['barang_list_beli'] = $this->common_model->get_barang_beli();

        
        
        $data['po_pembelian_data_warna'] = array();
        if ($batch_id != '') {
            foreach ($data['data_barang_po'] as $row) {
                // echo $row->id.'--'.$batch_id;
                // echo '<hr/>';
                $data['po_pembelian_data_warna'][$row->id] = $this->tr_model->get_data_po_pembelian_detail_batch($batch_id, $row->id, 'AND tipe_barang !=3 AND tipe_barang !=4');
                $data['po_pembelian_data_warna_split'][$row->id] = $this->tr_model->get_data_po_pembelian_detail_batch($batch_id, $row->id, 'AND tipe_barang !=1 AND tipe_barang != 2');
                // echo $batch_id.','.$row->id.'<br/>';
            }
        }
        

        $data['batch_id'] = $batch_id;
        $data['supplier_id'] = $supplier_id;
        $data['po_pembelian_detail_last'] = '';
        $data['tipe_barang_last'] = '';
        $data['barang_id_baru_last'] = '';
        $data['barang_beli_id_baru_last'] = '';
        $data['barang_id_baru_rename_last'] = '';

        $data['harga_baru_last'] = '';

        if ($this->session->flashdata('po_detail_last')) {
            $data_break = explode('??', $this->session->flashdata('po_detail_last'));
            $data['po_pembelian_detail_last'] = $data_break[0];
            $data['tipe_barang_last'] = $data_break[1];
            $data['barang_id_baru_last'] = $data_break[2];
            $data['harga_baru_last'] = $data_break[3];
            $data['barang_beli_id_baru_last'] = $data_break[4];
            $data['barang_id_baru_rename_last'] = $data_break[5];

        }

        $po_revisi_result = array(); 
        if ($revisi_status == 1) {
            $po_revisi_result = $this->common_model->db_select("nd_po_pembelian_batch where revisi_ori_id = ".$batch_id);
        }

        $data['po_revisi_result'] = $po_revisi_result;

        // if (is_posisi_id() == 1) {
        //     echo $batch_id;
        //     // print_r($data['po_pembelian_data']);
        //     echo "<hr/>";
        //     print_r($data['po_pembelian_data_batch']);
        // }else{
        // }
        if (is_posisi_id() == 1) {
            $data['content'] ='admin/transaction/po_pembelian_detail_warna_batch_2';

            // echo $batch_id;
            // print_r($po_revisi_result);
            // $this->output->enable_profiler(TRUE);
        }else{
            $data['content'] ='admin/transaction/po_pembelian_detail_warna_batch_2';
            
        }
        $this->load->view('admin/template_no_sidebar',$data);

    }

    function po_pembelian_detail_warna_batch_remove(){
        $po_pembelian_id = $this->input->get('id');
        $batch_id = $this->input->get('batch_id');
        $this->common_model->db_delete("nd_po_pembelian_batch",'id', $batch_id);
        $this->common_model->db_delete("nd_po_pembelian_warna",'po_pembelian_batch_id', $batch_id);
        redirect(is_setting_link('transaction/po_pembelian_detail').'?id='.$po_pembelian_id);
    }

    function po_pembelian_batch_insert()
    {
        $po_pembelian_id = $this->input->post('po_pembelian_id');
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
        redirect(is_setting_link('transaction/po_pembelian_detail_warna_batch').'?id='.$po_pembelian_id."&batch_id=".$result_id);
    }

    function po_pembelian_batch_update()
    {
        $po_pembelian_id = $this->input->post('po_pembelian_id');
        $batch_id = $this->input->post('batch_id');
        
        $data = array(
            'tanggal' => is_date_formatter($this->input->post('tanggal')),
            );

        $this->common_model->db_update('nd_po_pembelian_batch', $data,'id',$batch_id);
        redirect(is_setting_link('transaction/po_pembelian_detail_warna_batch').'?id='.$po_pembelian_id."&batch_id=".$batch_id);
    }

    function po_batch_close(){
        $po_pembelian_id = $this->input->get('po_pembelian_id');
        $batch_id = $this->input->get('batch_id');
        $status = $this->input->get('status');
        $data_close = array(
            'status' => $status,
            'locked_by' => ($status == 2 ? is_user_id() : null),
            'locked_date' => ($status == 2 ? date('Y-m-d H:i:s') : null),
            'released_by' => null,
            'released_date' => null
        );
        $this->common_model->db_update("nd_po_pembelian_batch",$data_close,'id',$batch_id);
        redirect(is_setting_link('transaction/po_pembelian_detail_warna_batch').'?id='.$po_pembelian_id."&batch_id=".$batch_id);
    }

    function po_pembelian_batch_release(){
        $batch_id = $this->input->get('id');
        $data = array(
            'released_by' => is_user_id(),
            'released_date' => date('Y-m-d H:i:s')
        );

        $this->common_model->db_update('nd_po_pembelian_batch', $data,'id', $batch_id);
        echo json_encode("OK");

    }

    function po_pembelian_release(){
        $batch_id = $this->input->get('id');
        $data = array(
            'released_by' => is_user_id(),
            'released_date' => date('Y-m-d H:i:s')
        );

        $this->common_model->db_update('nd_po_pembelian', $data,'id', $batch_id);
        echo json_encode("OK");

    }

    function po_pembelian_batch_revisi()
    {
        $po_pembelian_id = $this->input->post('po_pembelian_id');
        $batch_id = $this->input->post('batch_id');
        $tanggal = is_date_formatter($this->input->post('tanggal'));

        //ambil data dari batch lama
        $get_batch = $this->common_model->db_select("nd_po_pembelian_batch where id=".$batch_id);
        //get data revisi dari objek
        $data_revisi = array(
            'status' => 0,
            'revisi_by' => is_user_id(),
            'revisi_date' => date('Y-m-d') );

        //set data revisi buat batch lama
        foreach ($get_batch as $row) {
            $revisi_ori_id = $row->id;
            $data = array(
                'po_pembelian_id' => $po_pembelian_id ,
                'tanggal' => $tanggal,
                'revisi_ori_id' => $row->id,
                'revisi' => 2,
                'batch' => $row->batch );
        }

        // update data batch lama
        $this->common_model->db_update('nd_po_pembelian_batch', $data_revisi,'id',$batch_id);

        // insert batch baru sebagai batch revisi
        $result_id = $this->common_model->db_insert('nd_po_pembelian_batch', $data);

        // duplicate data dari detail warna yang dulu
        // 1. get old data
        $get_warna = $this->common_model->db_select("nd_po_pembelian_warna where po_pembelian_batch_id=".$batch_id);
        // 2 set new data
        $idx= 0 ;
        foreach ($get_warna as $row) {
            $data_detail_new[$idx] = array(
                'po_pembelian_batch_id' => $result_id ,
                'po_pembelian_detail_id' => $row->po_pembelian_detail_id,
                'tipe_barang' => $row->tipe_barang,
                'barang_id_baru' => $row->barang_id_baru,
                'warna_id' => $row->warna_id,
                'harga_baru' => $row->harga_baru,
                'qty' => $row->qty,
                'OCKH' => $row->OCKH,
                'locked_by' => $row->locked_by,
                'locked_date' => $row->locked_date,
                'locked_keterangan' => $row->locked_keterangan
                 );
            $idx++;
        }

        $this->common_model->db_insert_batch('nd_po_pembelian_warna', $data_detail_new);

        // 3.update pembelian
        // set data update 
        $data_beli = array(
            'po_pembelian_batch_id' => $result_id , );
        $this->common_model->db_update('nd_pembelian', $data_beli,'po_pembelian_batch_id',$batch_id);


        redirect(is_setting_link('transaction/po_pembelian_detail_warna_batch').'?id='.$po_pembelian_id."&batch_id=".$result_id);
    }

    function po_pembelian_batch_qty_update(){
        $qty = str_replace(',','.',str_replace('.', '', $this->input->post('qty')));
        $id = $this->input->post('id');
        $data = array('qty' => $qty, );
        $this->common_model->db_update("nd_po_pembelian_warna", $data,'id',$id);
        echo "OK";
    }

    function po_pembelian_update_harga_baru(){
        $id_list = $this->input->post('id_list');
        $harga = str_replace(".", "", $this->input->post('harga'));

        $this->common_model->db_free_query_superadmin("UPDATE nd_po_pembelian_warna set harga_baru=$harga WHERE id in ($id_list)");
        echo "OK";
    }

    function po_pembelian_warna_insert(){
        $ini = $this->input;
        $po_pembelian_id = $this->input->post('po_pembelian_id');
        $po_pembelian_detail_warna_id = $this->input->post('po_pembelian_detail_warna_id');
        $batch_id = $ini->post('batch_id');
        $tipe_barang = $this->input->post('tipe_barang');
        $barang_id_baru = null;
        $barang_beli_id_baru = null;
        $barang_id_baru_rename = ($tipe_barang == 4 ? $this->input->post('barang_id_baru_rename') : null);
        $barang_id_baru_rename = ($barang_id_baru_rename == '' ? null : $barang_id_baru_rename );
        $harga_baru = null;
        if ($tipe_barang != 1) {
            $barang_id_baru = $this->input->post('barang_id_baru');
            $barang_beli_id_baru = $this->input->post('barang_beli_id_baru');
        }
        $harga_baru = str_replace('.', '', $this->input->post('harga_baru'));
        $qty = str_replace(',','.',str_replace('.', '', $ini->post('qty')));


        $data = array(
            'po_pembelian_detail_id' => $ini->post('po_pembelian_detail_id'),
            'po_pembelian_batch_id' => $batch_id,
            'tipe_barang' => $tipe_barang,
            'barang_beli_id_baru' => $barang_beli_id_baru,
            'barang_id_baru' => $barang_id_baru,
            'barang_id_baru_rename' => $barang_id_baru_rename,
            'warna_id' => $ini->post('warna_id'),
            'qty' => $qty,
            'harga_baru' => ($harga_baru == 0 ? null : $harga_baru),
            'OCKH' => $ini->post('OCKH'),
        );

        if ($po_pembelian_detail_warna_id == '') {
            $this->common_model->db_insert('nd_po_pembelian_warna', $data);
        }else{
            $this->common_model->db_update('nd_po_pembelian_warna', $data,'id', $po_pembelian_detail_warna_id);
        }
        $this->session->set_flashdata('po_detail_last', $ini->post('po_pembelian_detail_id').'??'.$tipe_barang.'??'.($barang_id_baru=="" ? '-' : $barang_id_baru).'??'.$harga_baru.'??'.($barang_beli_id_baru=="" ? '-' : $barang_beli_id_baru).'??'.($barang_id_baru_rename=="" ? '-' : $barang_id_baru_rename));

        // echo $ini->post('po_pembelian_detail_id').'??'.$tipe_barang.'??'.($barang_id_baru=="" ? '-' : $barang_id_baru).'??'.$harga_baru;
        redirect(is_setting_link('transaction/po_pembelian_detail_warna_batch').'?id='.$po_pembelian_id.'&batch_id='.$batch_id);
    }

    function po_pembelian_OCKH_update(){
        $id = $this->input->post("id");
        $data = array(
            'OCKH' => $this->input->post("OCKH") );
        $this->common_model->db_update('nd_po_pembelian_warna',$data,'id',$id);
        echo "OK";
    }

    function pembelian_detail_warna_remove(){
        $id = $this->input->get('id');
        $po_pembelian_id = $this->input->get('po_pembelian_id');
        $batch_id = $this->input->get('batch_id');
        $this->common_model->db_delete('nd_po_pembelian_warna','id',$id);
        redirect(is_setting_link('transaction/po_pembelian_detail_warna_batch').'?id='.$po_pembelian_id.'&batch_id='.$batch_id);

    }

    function po_ockh_update(){
        $id = $this->input->post('id');
        $data = array(
            'OCKH' => $this->input->post('OCKH') );
        $this->common_model->db_update('nd_po_pembelian_warna', $data,'id', $id);
        echo "OK"; 
    }

    function po_pembelian_open(){
        $id = $this->input->get('id');
        $data = array(
            'po_status' => 1,
            'locked_by' => null,
            'locked_date' => null,
            'released_by' => null,
            'released_date' => null
        );
        $this->common_model->db_update('nd_po_pembelian',$data,'id',$id);
        redirect(is_setting_link('transaction/po_pembelian_detail').'?id='.$id);
    }

//===================================pembelian lain2=============================================
    function pembelian_lain(){
        $menu = is_get_url($this->uri->segment(1)) ;

        $data = array(
            'content' =>'admin/transaction/pembelian_lain',
            'breadcrumb_title' => 'Transaction',
            'breadcrumb_small' => 'Daftar Pembelian Lain-lain',
            'nama_menu' => $menu[0],
            'nama_submenu' => $menu[1],
            'common_data'=> $this->data,
            'data_isi'=> $this->data );


        $data['user_id'] = is_user_id();
        $data['pembelian_list'] = $this->tr_model->get_pembelian_lain(); 
        $this->load->view('admin/template',$data);
    }

    function pembelian_lain_insert(){
        $ini = $this->input;
        $tanggal = is_date_formatter($ini->post('tanggal'));
        $data_pembelian = array(
            'no_faktur' => $ini->post('no_faktur') ,
            'tanggal'=>is_date_formatter($ini->post('tanggal')),
            'supplier_id' => $ini->post('supplier_id'),
            'jatuh_tempo' => is_date_formatter($ini->post('tanggal')),
            'keterangan' => $ini->post('keterangan'),
            'user_id' => is_user_id()
             );
        
        $result_id = $this->common_model->db_insert('nd_pembelian_lain',$data_pembelian);
        redirect(is_setting_link('transaction/pembelian_lain_detail').'/'.$result_id);
    }

    function pembelian_lain_update(){

        $ini = $this->input;
        $pembelian_id = $ini->post('pembelian_id');
        $data = array(
            'no_faktur' => $ini->post('no_faktur') ,
            'tanggal'=>is_date_formatter($ini->post('tanggal')),
            'jatuh_tempo' => is_date_formatter($ini->post('tanggal')),
            'supplier_id' => $ini->post('supplier_id'),
            'keterangan' => $ini->post('keterangan'),
            'user_id' => is_user_id()
            );

        $this->common_model->db_update('nd_pembelian_lain',$data,'id', $pembelian_id);

        redirect(is_setting_link('transaction/pembelian_lain_detail').'/'.$pembelian_id);
    }
    
    function pembelian_lain_list_batal(){
        $id = $this->input->get('id');
        // echo $id;
        $data = array(
            'status_aktif' => 0,
            'cancelled_by' => is_user_id(),
            'cancelled_date' => date('Y-m-d H:i:s') );
        $this->common_model->db_update('nd_pembelian_lain',$data,'id',$id);
        redirect($this->setting_link('transaction/pembelian_lain_list'));
    }

    function pembelian_lain_undo_batal(){
        $id = $this->input->get('id');
        // echo $id;
        $data = array(
            'status_aktif' => 1,
            );
        $this->common_model->db_update('nd_pembelian_lain',$data,'id',$id);
        redirect($this->setting_link('transaction/pembelian_lain_list'));
    }

    function pembelian_lain_detail(){
        $menu = is_get_url($this->uri->segment(1)) ;

        $id = $this->uri->segment(2);
        $data = array(
            'content' =>'admin/transaction/pembelian_lain_detail',
            'breadcrumb_title' => 'Transaction',
            'breadcrumb_small' => 'Pembelian Lain-lain',
            'nama_menu' => $menu[0],
            'nama_submenu' => $menu[1],
            'common_data'=> $this->data,
            'data_isi'=> $this->data );

        $po_pembelian_batch_id = '';
        if ($id != '') {
            $data['pembelian_data'] = $this->tr_model->get_data_pembelian_lain($id);
            $data['pembelian_detail'] = $this->common_model->db_select("nd_pembelian_lain_detail where pembelian_lain_id=".$id);
            
        }else{
            $data['pembelian_data'] = array();
            $data['pembelian_detail'] = array();
        }
        // if (is_posisi_id() == 1) {
        //     print_r($data_other);
        //     # code...
        // }else{
        // print_r($data['kartu_po']);
            $this->load->view('admin/template',$data);
            
        // }
    }


    function pembelian_lain_detail_insert(){
        $ini = $this->input;
        $pembelian_id = $ini->post('pembelian_id');
        $pembelian_detail_id = $ini->post('pembelian_detail_id');

        //siapin data untuk insert ke pembelian
        $data = array(
            'pembelian_lain_id' => $pembelian_id ,
            'keterangan_barang' => $this->input->post('keterangan_barang'),
            'qty' => $this->input->post('qty') ,
            'harga_beli' => str_replace('.', '', $this->input->post('harga_beli')) 
            );

        // print_r($this->input->post());


        if ($pembelian_detail_id == '') {
            $this->common_model->db_insert('nd_pembelian_lain_detail', $data);
        }else{
            $this->common_model->db_update('nd_pembelian_lain_detail', $data,'id', $pembelian_detail_id);
        }
        redirect($this->setting_link('transaction/pembelian_lain_detail').'/'.$pembelian_id);
            
    }

    function pembelian_lain_detail_remove(){
        $id = $this->input->get('id');
        $pembelian_id = $this->input->get('pembelian_id');
        $this->common_model->db_delete('nd_pembelian_lain_detail','id',$id);
        redirect($this->setting_link('transaction/pembelian_lain_detail').'/'.$pembelian_id);
    }


    function pembelian_lain_jatuh_tempo_update(){
        $id = $this->input->post('pembelian_id');
        $ori_tanggal = strtotime($this->input->post('ori_tanggal'));
        $ori_jatuh_tempo = strtotime(is_date_formatter($this->input->post('jatuh_tempo')));
        
        $data = array(
            'jatuh_tempo' => is_date_formatter($this->input->post('jatuh_tempo')) );

        $this->common_model->db_update('nd_pembelian_lain', $data,'id',$id);


        $diff = $ori_jatuh_tempo - $ori_tanggal;
        $diff = $diff/(60*60*24);
        if ($diff >= 0) {
            echo 'OK';
        }elseif ($diff < 0) {
            echo 'FALSE';
        }else{
            echo 'ERROR';
        };
    }


//===================================pembelian=============================================
	function pembelian_list(){
		$menu = is_get_url($this->uri->segment(1));
        $tanggal_start = date('Y-m-d', strtotime('-6 month'));
        $tanggal_end = date('Y-m-d');

        if ($this->input->get('tanggal_start') != '' && $this->input->get('tanggal_end') != '') {
            $tanggal_start = $this->input->get('tanggal_start');
            $tanggal_end = $this->input->get('tanggal_end');
        }

		$data = array(
			'content' =>'admin/transaction/pembelian_list_slim',
			'breadcrumb_title' => 'Transaction',
			'breadcrumb_small' => 'Daftar Pembelian',
            'tanggal_start' => is_reverse_date($tanggal_start) ,
            'tanggal_end' => is_reverse_date($tanggal_end),
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data'=> $this->data,
			'data_isi'=> $this->data );


		$data['user_id'] = is_user_id();
		$data['pembelian_list'] = $this->common_model->db_select('nd_pembelian order by tanggal desc'); 
		$this->load->view('admin/template',$data);
	}

	function data_pembelian(){

		// $session_data = $this->session->userdata('do_filter');
		
		$aColumns = array('status_aktif','toko', 'no_faktur', 'ockh_info','tanggal', 'jumlah','jumlah_roll','nama_barang','gudang', 'harga_beli', 'total','supplier','keterangan', 'status_data');
        
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

        $rResult = $this->tr_model->get_pembelian_list_ajax($aColumns, $sWhere, $sOrder, $sLimit);        
        
        $rResultTotal = $this->common_model->db_select_num_rows('nd_pembelian');
        $Filternya = $this->tr_model->get_pembelian_list_ajax($aColumns, $sWhere, $sOrder, '');
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

	function data_pembelian_slim(){

		// $session_data = $this->session->userdata('do_filter');
		
		$aColumns = array('status_aktif','toko', 'no_faktur', 'ockh_info','tanggal', 'gudang', 'total','supplier','keterangan', 'status_data');
        $tanggal_start = $this->input->get('tanggal_start');
        $tanggal_end = $this->input->get('tanggal_end');

        $tanggal_start = is_date_formatter($tanggal_start);
        $tanggal_end = is_date_formatter($tanggal_end);
        
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

        $rResult = $this->tr_model->get_pembelian_list_ajax($aColumns, $sWhere, $sOrder, $sLimit, $tanggal_start, $tanggal_end);        
        
        $rResultTotal = $this->common_model->db_select_num_rows('nd_pembelian');
        $Filternya = $this->tr_model->get_pembelian_list_ajax($aColumns, $sWhere , $sOrder, '', $tanggal_start, $tanggal_end);
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

    function get_ockh_suggestion(){
        $ockh_input = $this->input->post('ockh_input');
        $get_data = $this->tr_model->get_ockh_suggestion($ockh_input);
        echo json_encode($get_data) ;
    }

    function get_po_batch_by_ockh(){
        $ockh = $this->input->post('ockh');
        $get_data = $this->tr_model->cek_po_batch_by_ockh($ockh);
        echo json_encode($get_data);
    }

    function get_po_pembelian_by_supplier(){
        $supplier_id = $this->input->post('supplier_id');
        $get_po_list = $this->tr_model->get_po_pembelian_by_supplier($supplier_id);
        echo json_encode($get_po_list) ;
    }

	function pembelian_list_insert(){
		$ini = $this->input;
		$no_nota = 1;
		$tanggal = is_date_formatter($ini->post('tanggal'));
		$year = date('Y', strtotime($tanggal));
		$data_last = $this->common_model->db_select("nd_pembelian WHERE YEAR(tanggal) ='$year' order by no_nota desc limit 1");
		foreach ($data_last as $row) {
			$no_nota = $row->no_nota + 1;
		}
        $ockh_info = $ini->post('ockh_info');
        $po_pembelian_batch_id = $ini->post('po_pembelian_batch_id');
        if($ockh_info != '' && $po_pembelian_batch_id != ''){
            $get_data = $this->tr_model->cek_po_batch_by_ockh($ockh_info);
            foreach ($get_data as $row) {
                $po_pembelian_batch_id = $row->po_pembelian_batch_id;
            }
        }

        $po_pembelian_batch_id = ($po_pembelian_batch_id == '' ? null : $po_pembelian_batch_id);

        $no_plat = $this->input->post('no_plat');
        $jam_input = $this->input->post('jam_input');

        $data_mobil = array(
            'no_plat' => $no_plat,
            'jam_input' => $jam_input,
            'user_id' => is_user_id()
        );

        $get_penerimaan = $this->common_model->db_select("nd_penerimaan_barang where no_plat ='$no_plat' AND jam_input='$jam_input'");

        $penerimaan_barang_id = 0;
        foreach($get_penerimaan as $row){
            $penerimaan_barang_id = $row->id;
        }

        if($penerimaan_barang_id == 0){
            $penerimaan_barang_id = $this->common_model->db_insert('nd_penerimaan_barang',$data_mobil);
        }

		
		$data_pembelian = array(
            'penerimaan_barang_id' => $penerimaan_barang_id,
			'no_nota' => $no_nota,
            'no_faktur' => $ini->post('no_faktur') ,
			'ockh_info' => ($ockh_info == '' ? null : $ockh_info) ,
			'tanggal'=>is_date_formatter($ini->post('tanggal')),
			'gudang_id'=>$ini->post('gudang_id'),
			'toko_id'=>$ini->post('toko_id'),
			'supplier_id' => $ini->post('supplier_id'),
            'po_pembelian_batch_id' => $po_pembelian_batch_id,
			'diskon' => ($ini->post('diskon') != '' ? str_replace('.', '', $ini->post('diskon')) : 0 ),
			'jatuh_tempo' => is_date_formatter($ini->post('tanggal')),
			'keterangan' => $ini->post('keterangan'),
			'user_id' => is_user_id()
			 );
        // print_r($data_pembelian);

        // print_r($this->input->post());

		$result_id = $this->common_model->db_insert('nd_pembelian',$data_pembelian);
		redirect(is_setting_link('transaction/pembelian_list_detail').'/'.$result_id);

	}

	function pembelian_list_update(){

		$ini = $this->input;
		$pembelian_id = $ini->post('pembelian_id');
		$data = array(
			'no_faktur' => $ini->post('no_faktur') ,
            'ockh_info' => $ini->post('ockh_info') ,
			'gudang_id'=>$ini->post('gudang_id'),
			'tanggal'=>is_date_formatter($ini->post('tanggal')),
            'jatuh_tempo' => is_date_formatter($ini->post('tanggal')),
			'toko_id'=>$ini->post('toko_id'),
			'supplier_id' => $ini->post('supplier_id'),
            'po_pembelian_batch_id' => ($ini->post('po_pembelian_batch_id') == '' ? null : $ini->post('po_pembelian_batch_id')),
            'user_id' => is_user_id()
            );

		$this->common_model->db_update('nd_pembelian',$data,'id', $pembelian_id);

		redirect(is_setting_link('transaction/pembelian_list_detail').'/'.$pembelian_id);

	}
    

	function pembelian_list_batal(){
		$id = $this->input->get('id');
		// echo $id;
		$data = array(
			'status_aktif' => 0,
			'cancelled_by' => is_user_id(),
			'cancelled_date' => date('Y-m-d H:i:s') );
		$this->common_model->db_update('nd_pembelian',$data,'id',$id);
		redirect($this->setting_link('transaction/pembelian_list'));
	}

	function pembelian_list_undo_batal(){
		$id = $this->input->get('id');
		// echo $id;
		$data = array(
			'status_aktif' => 1,
			);
		$this->common_model->db_update('nd_pembelian',$data,'id',$id);
		redirect($this->setting_link('transaction/pembelian_list'));
	}

	function pembelian_list_edit(){
		$id = $this->input->get('id');
		$data['pembelian_list'] = $this->tr_model->data_pembelian_list($id);
		$this->load->view('admin/transaction/pembelian_list_edit',$data);	
	}

	function pembelian_list_detail(){
        $menu = is_get_url($this->uri->segment(1)) ;
        $id = $this->uri->segment(2);
        $data = array(
            'content' =>'admin/transaction/pembelian_list_detail_2',
            'breadcrumb_title' => 'Transaction',
            'breadcrumb_small' => 'Pembelian',
            'nama_menu' => $menu[0],
            'nama_submenu' => $menu[1],
            'common_data'=> $this->data,
            'barang_id_before' => '',
            'barang_beli_id_before' => '',
            'harga_id_before' => '',
            'data_isi'=> $this->data );

        $data['pembelian_data'] = array();
        $data['pembelian_detail'] = array();
        $data['barang_list'] = array();
        $data['kartu_po'] = array();
        $data['po_pembelian_batch_id'] = '';
        $data['barang_id_detail'] = '';
        $data['ockh_detail'] = '';
        $data['ockh_info'] = '';
        $data['warna_list'] = array();
        $data['rekap_qty'] = array();
        $is_multiple_item = false;
        $data['is_multiple_item'] = false;

        $po_pembelian_batch_id = '';
        $supplier_id = 0;
        if ($id != '') {

            $data['pembelian_data'] = $this->tr_model->get_data_pembelian($id);
            foreach ($data['pembelian_data'] as $row) {
                $ockh = trim($row->ockh_info);
                $data_ockh_terdaftar = $this->common_model->db_select("nd_ockh_non_po where ockh ='".$ockh."'");
                $supplier_id = $row->supplier_id;
                $data_other = $this->tr_model->get_pembelian_other($ockh, $supplier_id);
            }

            //set nama ockh berdasarkan list di menu --ockh non PO--
            foreach ($data_ockh_terdaftar as $row) {
                // $data['barang_id_before'] = $row->barang_id;
            }

            //Jika sudah pernah membeli dengan OCKH yang sama, maka otomatis akan nyari di barang di pembelian lain
            //Otomatis override barang yang terdaftar di menu OCKH untuk skalian cek barang
            foreach ($data_other as $row) {
                //get barang id dari pembelian sebelumnya
                foreach ($this->common_model->db_select('nd_pembelian_detail where pembelian_id='.$row->id." GROUP BY barang_id") as $row2) {
                    if ($data['barang_id_before'] == '') {
                        $data['barang_id_before'] = $row2->barang_id;
                        $data['barang_beli_id_before'] = $row2->barang_beli_id;
                    }
                    $data['harga_id_before'] = $row2->harga_beli;
                }
            }

            $data['pembelian_detail'] = $this->tr_model->get_data_pembelian_detail($id);
            $OCKH = '';
            foreach ($data['pembelian_data'] as $row) {
                $po_pembelian_batch_id = $row->po_pembelian_batch_id;
                $ockh_info = ($row->ockh_info == 0 ? '' : $row->ockh_info);
            }

            $barang_id_detail = '';
            $warna_id_detail = '';
            $ockh = '';
            
            foreach ($data['pembelian_detail'] as $row) {
                $barang_id_detail = $row->barang_id;
                $warna_id_detail = $row->warna_id;
                $ockh = $row->ockh;
                $data['rekap_qty'][$row->id] = $this->common_model->db_select("nd_pembelian_qty_detail where pembelian_detail_id = ".$row->id);
            }

            if ($po_pembelian_batch_id != '' && $po_pembelian_batch_id != 0) {
                $get = $this->tr_model->cek_po_multiple_item($po_pembelian_batch_id);
                if ($get > 1) {
                    $is_multiple_item = true;
                    $data['barang_id_before'] = '';
                    $data['barang_beli_id_before'] = '';
                    $data['warna_list'] = array();
                }
            }

            // echo $is_multiple_item;

            $data['is_multiple_item'] = false;

            $warna_list = array();
            if ($po_pembelian_batch_id != '' && $po_pembelian_batch_id != 0 && $data['barang_beli_id_before'] == '') {
                $cond_ockh = $OCKH;
                if ($is_multiple_item) {
                    $cond_ockh = '';
                }
                $barang_list = $this->tr_model->get_pembelian_barang_by_po($po_pembelian_batch_id, $cond_ockh);
            }else {
                $barang_list = $this->common_model->get_barang_beli();
                if ($data['barang_beli_id_before'] != '' &&  $po_pembelian_batch_id ) {
                    $warna_list = $this->tr_model->get_pembelian_warna_by_po($po_pembelian_batch_id, $data['barang_beli_id_before'], $id);
                }
            }

            if ($po_pembelian_batch_id != '' && $po_pembelian_batch_id != 0 && $barang_id_detail != '' ) {
                $data['kartu_po'] = $this->tr_model->kartu_po_data($po_pembelian_batch_id,$barang_id_detail, $warna_id_detail);
            }else if($barang_id_detail != '' && $ockh != ''){
                $data['kartu_po'] = $this->tr_model->kartu_ockh_data($ockh,$barang_id_detail, $warna_id_detail);
            }else{
                $data['kartu_po'] = array();
            }
            $data['barang_list'] = $barang_list;
            $data['po_pembelian_batch_id'] = $po_pembelian_batch_id;
            $data['barang_id_detail'] = $barang_id_detail;
            $data['ockh_detail'] = $ockh;
            $data['ockh_info'] = $ockh_info;
            $data['warna_list'] = $warna_list;
            
            
        }

        $data['penerimaan_barang_before'] = $this->common_model->get_penerimaan_barang_suggestion();

        if (is_posisi_id() == 1) {
            $data['content'] = "admin/transaction/pembelian_list_detail_3";
            $this->load->view('admin/template',$data);
        }else{
        // print_r($data['kartu_po']);

        // $data['barang_beli_id_before'] = '';
        
        $data['content'] = "admin/transaction/pembelian_list_detail_3";

        // $this->output->enable_profiler(TRUE);
        // if (is_posisi_id() != 1) {
            $this->load->view('admin/template',$data);
        // }else{
        //     echo $po_pembelian_batch_id.','.$cond_ockh;

        }
            
        // }

    }
    function po_pembelian_warna_lock(){
        $po_pembelian_warna_id = $this->input->post('po_pembelian_warna_id');
        $data = array(
            'locked_date' => date('Y-m-d H:i:s') ,
            'locked_by' => is_user_id(),
            'locked_keterangan' => null );
        $this->common_model->db_update("nd_po_pembelian_warna", $data,'id', $po_pembelian_warna_id);
        echo "OK";
    }

    function get_ockh(){
        $po_pembelian_batch_id = $this->input->post('po_pembelian_batch_id');
        $barang_data = explode('??', $this->input->post('barang_id'));
        $barang_id = $barang_data[0];
        $warna_id = $barang_data[1];
        $tipe_barang = $barang_data[2];
        // $ockh = '';

        if($tipe_barang == 1){
            $get_data =  $this->tr_model->get_ockh($po_pembelian_batch_id, $barang_id, $warna_id);
        }else{
            $get_data =  $this->tr_model->get_ockh_tipe_beda($po_pembelian_batch_id, $barang_id, $warna_id, $tipe_barang);
        }
        foreach ($get_data as $row) {
            $ockh = $row->OCKH;
        }


        echo $ockh;
    }

    function get_po_pembelian_data(){
        $po_pembelian_batch_id = $this->input->post('po_pembelian_batch_id');
        $result = $this->common_model->get_po_pembelian_batch_by_cond("WHERE t1.id=".$po_pembelian_batch_id);
        echo json_encode($result);
    }

	function get_search_no_faktur(){
		$no_faktur = $this->input->post('no_faktur');
		$result = $this->common_model->db_select("nd_pembelian where no_faktur LIKE '%$no_faktur%' ");
		echo json_encode($result);
	}

	function pembelian_list_detail_insert(){
        $ini = $this->input;
        $pembelian_id = $ini->post('pembelian_id');
        $pembelian_detail_id = $ini->post('pembelian_detail_id');
        $po_pembelian_batch_id = $ini->post('po_pembelian_batch_id');
        $ockh = ($ini->post('ockh') == '' || $ini->post('ockh') == 0  ? null : $ini->post('ockh'));
        $barang_beli_id_before = $ini->post('barang_beli_id_before');
        $barang_beli_id = $ini->post('barang_beli_id');
        $barang_id = $this->input->post('barang_id');


        
            //get data dari form
            if ($po_pembelian_batch_id != '' && $barang_beli_id_before == '' ) {
                $data_barang = $this->input->post('barang_id');
                $data_barang = explode('??', $data_barang);
                $barang_id = $data_barang[0];
                $barang_beli_id = $data_barang[3];
                $warna_id = (isset($data_barang[1]) ? $data_barang[1] : $this->input->post('warna_id'));
                $tipe_barang = (isset($data_barang[2]) ? $data_barang[2] : '');
            }else{
                $barang_beli_id = $this->input->post('barang_beli_id');
                $barang_id = $this->input->post('barang_id');
                $warna_id = $this->input->post('warna_id');
                $tipe_barang = 0;
            }

            if ($barang_id == '') {
                $data_barang = explode('??', $this->input->post('data_barang'));
                $barang_id = $data_barang[1];
            }
            
            $harga = str_replace('.', '', $ini->post('harga_beli'));
            $harga = str_replace(',', '.', $harga);
    
            //siapin data untuk insert ke pembelian
            $data = array(
                'pembelian_id' => $pembelian_id ,
                'ockh' => ($ini->post('ockh') == '' ? null : $ini->post('ockh')) ,
                'barang_id' => $barang_id,
                'barang_beli_id' => $barang_beli_id,
                'warna_id' => $warna_id ,
                'harga_beli' =>  $harga,
                'qty' => str_replace(',', '', $ini->post('qty')),
                // 'qty' => str_replace(',','.', str_replace('.', '', $ini->post('qty')) ) ,
                'jumlah_roll' => $ini->post('jumlah_roll') );

            // if (is_posisi_id()==1) {

                // echo $po_pembelian_batch_id.' '.$barang_beli_id_before.'<br/>';
                // echo $barang_id;
                // echo ":warna_id = ".$warna_id;
                // echo strlen($barang_id);
                // print_r($data);
                // print_r($this->input->post());
            // }else{

                // print_r($data);
                // echo $po_pembelian_batch_id;
                $ockh_info = ($this->input->post('ockh_info') =='' || $this->input->post('ockh_info') == 0 ? null : $this->input->post('ockh_info'));
                if ($ockh_info == '' || $ockh_info == 0) {
                    if ($ockh != '' && $ockh != 0) {
                        $ockh_info = $ockh;
                        $ockh_info = array('ockh_info' => $ockh );
                        $this->common_model->db_update("nd_pembelian", $ockh_info, 'id', $pembelian_id);
                    }
                }
        
        
        
                // echo "<hr/>";
        
                //klo ada link ke po tapi tipe barang normal
                if ($tipe_barang == 1) {
                    $get_detail = $this->common_model->db_free_query_superadmin("SELECT t1.* 
                        FROM (
                            SELECT *
                            FROM nd_po_pembelian_warna
                            WHERE warna_id = $warna_id
                            AND po_pembelian_batch_id = $po_pembelian_batch_id
                            AND tipe_barang = 1
                        ) t1
                        LEFT JOIN (
                            SELECT *
                            FROM nd_po_pembelian_detail
                            WHERE barang_id = $barang_id
                        ) t2
                        ON t1.po_pembelian_detail_id = t2.id
                        WHERE t2.id is not null");
                }//else kalo tipe barang beda sama po detail id
                elseif ($tipe_barang == 2 || $tipe_barang == 3) {
                    $get_detail = $this->common_model->db_free_query_superadmin("SELECT *
                            FROM nd_po_pembelian_warna
                            WHERE warna_id = $warna_id
                            AND tipe_barang = $tipe_barang
                            AND barang_id_baru = $barang_id
                            AND po_pembelian_batch_id = $po_pembelian_batch_id");
                }elseif ($po_pembelian_batch_id != '') {
                    $get_detail = $this->common_model->db_free_query_superadmin("SELECT t1.* 
                        FROM (
                                (  
                                    SELECT tB.barang_id, warna_id, tA.id as id, OCKH
                                    FROM (
                                        SELECT *
                                        FROM nd_po_pembelian_warna
                                        WHERE warna_id = $warna_id
                                        AND po_pembelian_batch_id = $po_pembelian_batch_id
                                        AND tipe_barang = 1
                                    ) tA
                                    LEFT JOIN (
                                        SELECT *
                                        FROM nd_po_pembelian_detail
                                        WHERE barang_id = $barang_id
                                    ) tB
                                    ON tA.po_pembelian_detail_id = tB.id
                                    WHERE tB.id is not null
                                )UNION(
                                    SELECT barang_id_baru, warna_id, id, OCKH
                                    FROM nd_po_pembelian_warna
                                    WHERE warna_id = $warna_id
                                    AND barang_id_baru = $barang_id
                                    AND tipe_barang != 1
                                )
                        ) t1
                        ");
                }
        
                //cek klo ockh udh registered ato belum
                // print_r($get_detail->result());
                // echo $ockh;
                $ockh_registered = '';
                if (isset($get_detail)) {
                    foreach ($get_detail->result() as $row) {
                        $po_pembelian_warna_id = $row->id;
                        $ockh_registered = $row->OCKH;
                    }
                    //update ockh pembelian
                    $ockh_info = array('ockh_info' => $ockh );
                    $ockh_po = array('OCKH' => $ockh );
                    if ($ockh != '') {
                        $this->common_model->db_update("nd_pembelian", $ockh_info, 'id', $pembelian_id);
                        $this->common_model->db_update("nd_po_pembelian_warna", $ockh_po, 'id', $po_pembelian_warna_id);
                    }else if ($ockh == '' && $ockh_registered != '') {
                        $ockh_info = array('ockh_info' => $ockh_registered );
                        $this->common_model->db_update("nd_pembelian", $ockh_info, 'id', $pembelian_id);
                        $data['ockh'] = $ockh_registered;
                    }
        
                }
        
        
                // print_r($get_detail->result());
                // echo $po_pembelian_warna_id;
                if ($pembelian_detail_id == '') {
                    $pembelian_detail_id = $this->common_model->db_insert('nd_pembelian_detail',$data);
                }else{
                    $this->common_model->db_update('nd_pembelian_detail',$data,'id',$pembelian_detail_id);
                }
                $this->common_model->db_delete("nd_pembelian_qty_detail", "pembelian_detail_id", $pembelian_detail_id);
        
                $qty_list = array();
                foreach (explode("--", $this->input->post('rekap_qty')) as $key => $value) {
                    $br = explode('??', $value);
                    if ($br[0] != '' && $br[0] != 0) {
                        $qty_list[$key] = array(
                            'pembelian_detail_id' => $pembelian_detail_id ,
                            'qty' => $br[0],
                            'jumlah_roll' => $br[1] 
                        );
                    }
                }
        
                if (count($qty_list) > 0) {
                    $this->common_model->db_insert_batch("nd_pembelian_qty_detail",$qty_list);
                }
        
                redirect($this->setting_link('transaction/pembelian_list_detail').'/'.$pembelian_id);
            // }
    
            
    
        
        
            
    }

    function pembelian_detail_update(){
        $id = $this->input->post('id');
        $data = array(
            $this->input->post('column') => $this->input->post('value') );
        $this->common_model->db_update('nd_pembelian_detail', $data,'id',$id);
        echo 'OK';
    }

	function pembelian_detail_remove(){
		$id = $this->input->post('id');
		$this->common_model->db_delete('nd_pembelian_detail','id',$id);
		echo 'OK';
	}



	function pembelian_data_update(){
		$id = $this->input->post('pembelian_id');
        $kolom = $this->input->post('column');
        $value = ($kolom == 'diskon' ? ($this->input->post('value') == '' ? 0 : $this->input->post('value'))  : $this->input->post('value')) ;
		$data = array(
			$kolom => $value  );
		$this->common_model->db_update('nd_pembelian', $data,'id',$id);
		echo 'OK';
	}

	function pembelian_jatuh_tempo_update(){
		$id = $this->input->post('pembelian_id');
		$ori_tanggal = strtotime($this->input->post('ori_tanggal'));
		$ori_jatuh_tempo = strtotime(is_date_formatter($this->input->post('jatuh_tempo')));
		
		$data = array(
			'jatuh_tempo' => is_date_formatter($this->input->post('jatuh_tempo')) );

		$this->common_model->db_update('nd_pembelian', $data,'id',$id);


		$diff = $ori_jatuh_tempo - $ori_tanggal;
		$diff = $diff/(60*60*24);
		if ($diff >= 0) {
			echo 'OK';
		}elseif ($diff < 0) {
			echo 'FALSE';
		}else{
			echo 'ERROR';
		};
	}

	function data_pembelian_list_edit(){
		$id = $this->input->post('id');

		$result = $this->tr_model->data_pembelian_list($id);
		// print_r($result);
		$baris = '';
		foreach ($result as $row) {
			$baris .= "<tr>";
			$baris .= "<td> <input name='barang_id[]' hidden='hidden' value='".$row->barang_id."'>".$row->nama_barang."<input name='warna_id' hidden='hidden' value='".$row->warna_id."'>".$row->warna_beli."</td>";
    		$baris .= "<td> <input name='satuan_id[]' hidden='hidden' value='".$row->satuan_id."'>".$row->nama_satuan."</td>";
			// $baris .= "<td> <input name='gudang_id' hidden='hidden' value='".$row->gudang_id."'>".$row->nama_gudang."</td>";
			$baris .= "<td> <input name='qty[]' value='".$row->qty."'></td>";
			$baris .= "<td> <input name='jumlah_roll[]' value='".$row->jumlah_roll."'></td>";
			$baris .= "<td> <input name='harga_beli[]' class='amount_number' value='".is_rupiah_format($row->harga_beli)."'></td>";
			$baris .= "<td> <span class='total'>".is_rupiah_format($row->qty * $row->harga_beli)."</span></td>";
			$baris .= "<td> <button type='button' class='btn btn-xs red btn-remove-list'><i class='fa fa-times'></i></button></td>";
			$baris .= "</tr>";
		}

		echo $baris;
	}

	function testing_print2(){
		$var = 'testing print yess';
		echo $var;
	}

	function testing_print(){
        /*$data = array(
            'content' =>'admin/transaction/testing_print',
            'breadcrumb_title' => 'Transaction',
            'breadcrumb_small' => 'Testing',
            'nama_menu' => 'testing',
            'nama_submenu' => 'testing',
            'common_data'=> $this->data,
            'data_isi'=> $this->data );
        $this->load->view('admin/template',$data);*/

		$this->load->view('admin/transaction/testing_print');
	}

    function testing_print_mike42(){
        $this->load->view('admin/transaction/testing_print_mike42');
    }

	function testing_pdf()
	{ 
		//load mPDF library
		$this->load->library('m_pdf');
		//load mPDF library

		//now pass the data//
		$this->data['title']="MY PDF TITLE 1.";
		$this->data['description']="";
		// $this->data['description']=$this->official_copies;
		//now pass the data //

			
		$html=$this->load->view('admin/transaction/testing_pdf',$this->data, true); //load the pdf_output.php by passing our data and get all data in $html varriable.
	 	 
		//this the the PDF filename that user will get to download
		$pdfFilePath ="mypdfName-".time()."-download.pdf";

			
		//actually, you can pass mPDF parameter on this load() function
		$pdf = $this->m_pdf->load();
		//generate the PDF!
		$pdf->WriteHTML($html,2);
		//offer it to user via browser download! (The PDF won't be saved on your server HDD)
		$pdf->Output($pdfFilePath, "D");
			 
			 	
	}
	

	function pembelian_print(){

		$pembelian_id = $this->input->get('pembelian_id');
		$nama_supplier = '';
		$telepon_supplier = '';
		$nama_gudang = '';
		$no_faktur = '';
		$ockh = '';
		$tanggal = '';
		$nama_toko = '';
		$jatuh_tempo = '';
		$no_nota = '';
		
		$data_pembelian = $this->tr_model->get_data_pembelian($pembelian_id);
		$data_pembelian_detail = $this->tr_model->get_data_pembelian_detail($pembelian_id);

		foreach ($data_pembelian as $row) {
			$nama_supplier = $row->nama_supplier;
			$telepon_supplier = $row->telepon_supplier;
			$no_faktur = $row->no_faktur;
			$tanggal_nota = date('dmy', strtotime($row->tanggal));
			$no_nota = 'FPB'.$tanggal_nota.'-'.$row->no_nota_p;
			$tanggal = is_reverse_date($row->tanggal);
			$jatuh_tempo = is_reverse_date($row->jatuh_tempo);
		}

		$this->load->library('fpdf17/fpdf_css');
		$this->load->library('fpdf17/fpdf');

		$pdf = new FPDF( 'L', 'mm', array(225 ,135 ) );
		
		$pdf->AddPage();
		$pdf->SetMargins(15,0,10);
		$pdf->SetTextColor( 0,0,0 );

		$font_name = 'Arial';
		
		$pdf->SetFont( $font_name, '', 9 );
		//1x3
		$pdf->Cell( 0, 3, 'From Seller :', 0, 1, 'R' );
		$pdf->Cell( 0, 3, $nama_supplier, 0, 1, 'R' );
		$pdf->Cell( 0, 3, ',telp : '.$telepon_supplier, 0, 1, 'R' );
		$pdf->Ln();

		$pdf->Cell( 0, 0, '', 1, 1, 'R' );

		$pdf->SetFont( $font_name, '', 11 );

		//1x5
		$pdf->Cell( 0, 5, strtoupper('Invoice No '.$no_faktur.' / Barang Jadi'), 0, 1, 'C' );
		$pdf->Ln();
		$pdf->Cell( 0, 5, strtoupper('Tanggal Pembelian : '.$tanggal), 0, 1, 'L' );
		$pdf->Cell( 0, 5, strtoupper('Supplier Invoice Number : '.$no_faktur), 0, 1, 'L' );
		$pdf->Ln();

		//1x8
		$pdf->Cell( 10, 8, strtoupper('No'), 1, 0, 'C' );
		$pdf->Cell( 50, 8, strtoupper('Nama Barang'), 1, 0, 'C' );
		$pdf->Cell( 20, 8, strtoupper('Satuan'), 1, 0, 'C' );
		$pdf->Cell( 25, 8, strtoupper('Jumlah'), 1, 0, 'C' );
		$pdf->Cell( 20, 8, strtoupper('Roll'), 1, 0, 'C' );
		$pdf->Cell( 35, 8, strtoupper('Harga/Yard'), 1, 0, 'C' );
		$pdf->Cell( 40, 8, strtoupper('Total'), 1, 1, 'C' );

		$baris = 16;
		$i = 1; $g_total = 0;
		foreach ($data_pembelian_detail as $row) {
			//1x7
			$pdf->Cell( 10, 7, $i, 1, 0, 'C' );
			$pdf->Cell( 50, 7, strtoupper($row->nama_barang), 1, 0, 'C' );
			$pdf->Cell( 20, 7, strtoupper($row->nama_satuan), 1, 0, 'C' );
			$pdf->Cell( 25, 7, $row->qty, 1, 0, 'C' );
			$pdf->Cell( 20, 7, $row->jumlah_roll, 1, 0, 'C' );
			$pdf->Cell( 35, 7, 'Rp'.number_format($row->harga_beli,'2',',','.'), 1, 0, 'C' );
			$pdf->Cell( 40, 7, 'Rp'.number_format($row->harga_beli*$row->qty,'2',',','.'), 1, 1, 'R' );
			$g_total += $row->harga_beli*$row->qty; 
			$i++;
			$baris += 7;
		}
		// $pdf->Cell( 10, 5, '1', 1, 0, 'C' );
		// $pdf->Cell( 40, 5, '9992 WR/CIRE Jade', 1, 0, 'C' );
		// $pdf->Cell( 20, 5, 'yard', 1, 0, 'C' );
		// $pdf->Cell( 20, 5, '2229', 1, 0, 'C' );
		// $pdf->Cell( 20, 5, '24', 1, 0, 'C' );
		// $pdf->Cell( 35, 5, 'Rp10.500,00', 1, 0, 'C' );
		// $pdf->Cell( 35, 5, 'Rp23.404,500,00', 1, 1, 'R' );

		// $pdf->Cell( 110, 5, '', 0, 0, 'C' );
		// $pdf->Cell( 35, 5, 'Subtotal', 1, 0, 'C' );
		// $pdf->Cell( 35, 5, 'Rp23.404,500,00', 1, 1, 'R' );

		//1x7
		$pdf->Cell( 125, 7, '', 0, 0, 'C' );
		$pdf->Cell( 35, 7, 'Total', 1, 0, 'C' );
		$pdf->Cell( 40, 7, 'Rp'.number_format($g_total,'2',',','.'), 1, 1, 'R' );

		// $pdf->Ln(10);

		$pdf->SetFont( $font_name, '', 9 );
		//Sisain 28mm
		//1x4
		$pdf->Cell( 0, 4, is_number_write($g_total), 0, 1, 'L' );
		$pdf->Cell( 0, 4, "Jatuh Tempo : ".$jatuh_tempo, 0, 1, 'L' );


		$pdf->Ln(5);

		$baris += 26;

		//===========================================================
		$sisa = 80 - $baris - 24;
		if ( $sisa > 0) {
			$pdf->Ln($sisa);
		}
		//1x4
		$pdf->Cell( 25, 4, 'Kepala Gudang', 1, 0, 'C' );
		$pdf->Cell( 25, 4, 'Pengirim', 1, 0, 'C' );
		$pdf->Cell( 25, 4, 'Penerima', 1, 1, 'C' );

		//1x20
		$pdf->Cell( 25, 20, '', 1, 0, 'C' );
		$pdf->Cell( 25, 20, '', 1, 0, 'C' );
		$pdf->Cell( 25, 20, '', 1, 0, 'C' );

		//=============================================================

		$pdf->SetAutoPageBreak(false);
		// $pdf->AddPage();

		$pdf->Output( 'testing', "I" );
		// echo $sisa;
		
	}


//===================================penjualan=============================================
	function penjualan_list(){
		$menu = is_get_url($this->uri->segment(1)) ;
        $status_aktif = 1;
        if ($this->input->get('status_aktif')) {
            $status_aktif = $this->input->get('status_aktif');
        }

		$data = array(
			'content' =>'admin/transaction/penjualan_list',
			'breadcrumb_title' => 'Transaction',
			'breadcrumb_small' => 'Daftar Penjualan',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data'=> $this->data,
            'status_aktif' => $status_aktif,
			'data_isi'=> $this->data );


		$data['user_id'] = is_user_id();
		// $data['penjualan_list'] = $this->common_model->db_select('nd_penjualan order by tanggal desc'); 
        $data['penjualan_list'] = array();
		$this->load->view('admin/template',$data);
	}

	function data_penjualan(){

		$aColumns = array('status_aktif', 'nf', 'no_faktur','tanggal','penjualan_type_id','g_total','diskon', 'ongkos_kirim','nama_customer','keterangan', 'data', 'status', 'count_item');
        
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

        $cond_limit_user = '';
        /*if (is_posisi_id() > 3) {
            $cond_limit_user =" AND tanggal = '".date('Y-m-d')."'";
        }*/

        if ($this->input->get('status_aktif')) {
            $status_aktif = $this->input->get('status_aktif');
            if ($status_aktif != 2) {
                if ( $sWhere == "" ){
                    $sWhere = "WHERE status_aktif =".$status_aktif.$cond_limit_user;
                }
                else{
                    $sWhere .= " AND status_aktif =".$status_aktif.$cond_limit_user;
                }
            }else{
                if ( $sWhere == "" ){
                    $sWhere = "WHERE no_faktur is null AND status_aktif = 1";
                }
                else{
                    $sWhere .= " AND no_faktur is null AND status_aktif = 1";
                }
            }
        }
        $rResult = $this->tr_model->get_penjualan_list_ajax($aColumns, $sWhere, $sOrder, $sLimit)['result'];
        
        $rResultTotal = $this->common_model->db_select_num_rows('nd_penjualan ');
        $Filternya = $this->tr_model->get_penjualan_list_ajax($aColumns, $sWhere, $sOrder, '')['result'];
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

	function penjualan_list_insert(){
		$tanggal = is_date_formatter($this->input->post('tanggal'));
        $po_penjualan_id = $this->input->post('po_penjualan_id');
        $tipe_po = null;
        $po_penjualan_id = ($po_penjualan_id == 'manual' || $po_penjualan_id == "Non PO" ? '' : $po_penjualan_id);
        $fp_status =  $this->input->post('fp_status');
        if($po_penjualan_id != '' && $po_penjualan_id != 0 ){
            $get = $this->common_model->db_select("nd_po_penjualan WHERE id=$po_penjualan_id");
            foreach ($get as $row) {
                $tipe_po = $row->tipe;
            }
            $fp_status = 1;
        }else{
            $po_penjualan_id = null;
        }
		$tahun = date('Y', strtotime($tanggal));
		$no_faktur = 1;
		$penjualan_type_id = $this->input->post('penjualan_type_id');
        $customer_id = ($penjualan_type_id != 3 ? $this->input->post('customer_id') : 0) ;

        /* $customer_id_central = $this->input->post('customer_id_central');
        if ($customer_id_central != '') {
            $get_customer=$this->common_model->db_select("nd_customer WHERE customer_id_central=$customer_id_central");
            $customer_id_get = "";
            foreach ($get_customer as $row) {
                $customer_id = $row->id;
                $customer_id_get = $row->id;
            }

            if($customer_id_get == ""){
                $customer_new = $this->get_customer_list_master($customer_id_central);
                $row = $customer_new;
                $new_data = array(
                    'nama' => $row->nama,
                    'tipe_company' => $row->tipe_company,
                    'alamat' => $row->alamat,
                    'no'=>$row->no,
                    'rt'=>$row->rt,
                    'rw'=>$row->rw,
                    'kelurahan'=>$row->kelurahan,
                    'kecamatan'=>$row->kecamatan,
                    'kota'=>$row->kota,
                    'kode_pos'=>$row->kode_pos,
                    'provinsi'=>$row->provinsi,
                    'npwp' => $row->npwp,
                    'customer_id_central' => $row->customer_id_central,
                    'nik' => $row->nik,
                    'user_id' => is_user_id()
                );
                

                if (empty($customer_new)) {
                    echo "Get Customer errorr. Mohon hubungi IT.";
                    return;
                }
                $customer_id = $this->common_model->db_insert('nd_customer',$new_data);
            }
        } */


        $data_get = $this->common_model->db_select("nd_penjualan where YEAR(tanggal)='".$tahun."' order by no_faktur desc limit 1 ");
		foreach ($data_get as $row) {
			$no_faktur = $row->no_faktur + 1;
		}

        $jt = is_date_formatter($this->input->post('jatuh_tempo'));

        if ($penjualan_type_id == 2 || $jt == '') {
    		$dt = strtotime(' +'.get_jatuh_tempo($customer_id).' days', strtotime($row->tanggal) );
    		$jt = date('Y-m-d', $dt);
        }

        // echo $jt;
        $data = array(
			'toko_id' => 1,
			'penjualan_type_id' => $this->input->post('penjualan_type_id'),
			'tanggal' => $tanggal,
			'customer_id' => $customer_id,
            'tipe_po' => $tipe_po,
            'po_penjualan_id' => $po_penjualan_id,
			'closed_by' => 0,
			'po_number' => $this->input->post('po_number'),
			'keterangan' => $this->input->post('keterangan'),
			'jatuh_tempo' => $jt,
			'user_id' => is_user_id(),
			'nama_keterangan' => $this->input->post('nama_keterangan'),
			'alamat_keterangan' => $this->input->post('alamat_keterangan'),
			'fp_status' => $fp_status,
            'ppn' => get_ppn_now($tanggal)
			);

            // print_r($this->input->post());
		// print_r($data);

        // if (is_posisi_id() != 1) {
            $result_id = $this->common_model->db_insert('nd_penjualan',$data);
            redirect($this->setting_link('transaction/penjualan_list_detail').'/?id='.$result_id);
            # code...
        // }else{
        //     print_r($this->input->post());
        //     print_r($data);
        // }

	}

	function penjualan_list_update(){
		$tanggal = is_date_formatter($this->input->post('tanggal'));

        $po_penjualan_id = $this->input->post('po_penjualan_id');
        $tipe_po = null;
        $po_penjualan_id = ($po_penjualan_id == 'manual' || $po_penjualan_id == "Non PO" ? '' : $po_penjualan_id);
        if($po_penjualan_id != '' && $po_penjualan_id != 0 ){
            $get = $this->common_model->db_select("nd_po_penjualan WHERE id=$po_penjualan_id");
            foreach ($get as $row) {
                $tipe_po = $row->tipe;
            }
            $fp_status = 1;
        }else{
            $po_penjualan_id = null;
        }


        $nama = $this->input->post('nama_keterangan');
		$id = $this->input->post('id');
        $customer_id = $this->input->post('customer_id');
        $alamat = $this->input->post('alamat_keterangan');
        $cek = $this->common_model->db_select("nd_penjualan where id=".$id);
        $penjualan_type_id = $this->input->post('penjualan_type_id');

        $getData = $this->common_model->db_select("nd_penjualan WHERE id='$id'");
        $status_penjualan = 1;
        $customer_id_legacy = 0;
        foreach ($getData as $row) {
            $status_penjualan = $row->status;
            $penjualan_type_id_legacy = $row->penjualan_type_id;
            $customer_id_legacy = $row->customer_id;
        }

        
        if ($status_penjualan == 1) {

            if($customer_id != $customer_id_legacy){

                if($penjualan_type_id != 3){
                    $update_bayar = array(
                        'amount' => 0,
                        'user_id' => is_user_id() 
                    );
        
                    $this->common_model->db_update('nd_pembayaran_penjualan', $update_bayar ,'id',$id);
        
                    $get_new_address = $this->common_model->db_select('nd_customer where id='.$customer_id);
                    foreach ($get_new_address as $row) {
                        $nama = $row->nama;
                        $alamat = '';
                    }
                }else{
                    $customer_id=0;
                }
            }
    
            if($penjualan_type_id != $penjualan_type_id_legacy){
                $this->common_model->db_delete_array("nd_pembayaran_penjualan","id", $id);
            }
    
            $jatuh_tempo = ($this->input->post('jatuh_tempo') != '' ? is_date_formatter($this->input->post('jatuh_tempo')) : null);
            
            $data = array(
                'penjualan_type_id' => $this->input->post('penjualan_type_id') ,
                'tanggal' => $tanggal,
                'customer_id' => $customer_id ,
                'tipe_po' => $tipe_po,
                'po_penjualan_id' => $po_penjualan_id,
                'po_number' => $this->input->post('po_number'),
                'keterangan' => $this->input->post('keterangan'),
                'jatuh_tempo' => $jatuh_tempo,
                'nama_keterangan' => $nama,
                'alamat_keterangan' => $alamat,
                'fp_status' => $this->input->post('fp_status'),
                'user_id' => is_user_id(),
                'ppn' => $this->input->post('ppn')
            );

            // print_r($data);
    
            $this->common_model->db_update('nd_penjualan',$data,'id',$id);
            redirect($this->setting_link('transaction/penjualan_list_detail').'/?id='.$id);
        }else if($status == 0){
            echo "Penjualan Sudah di Lock, tidak bisa di edit";
        }
	}
	
//==========================penjualan_detail=================================

    function penjualan_list_detail_js(){
        $menu = is_get_url($this->uri->segment(1)) ;
        // $id = $this->uri->segment(2);
        $id = $this->input->get('id');
        $toko_id = 1;

        $data = array(
            'content' =>'admin/transaction/penjualan_list_detail_js',
            'breadcrumb_title' => 'Transaction',
            'breadcrumb_small' => 'Formulir Penjualan',
            'nama_menu' => $menu[0],
            'nama_submenu' => $menu[1],
            'common_data'=> $this->data,
            'data_isi'=> $this->data,
            'penjualan_type' => $this->common_model->db_select('nd_penjualan_type'),
            'pembayaran_type' => $this->common_model->db_select('nd_pembayaran_type'),
            'printer_list' => $this->common_model->db_select('nd_printer_list')
            );


        if ($id != '') {
            $penjualan_data = $this->tr_model->get_data_penjualan($id);
            // print_r($penjualan_data);
            $data['penjualan_data'] = $penjualan_data;
            foreach ($penjualan_data as $row) {
                $penjualan_type_id = $row->penjualan_type_id;
                $customer_id = $row->customer_id;
                $toko_id = $row->toko_id;
                $no_faktur = $row->no_faktur_raw;
            }
            $data['penjualan_detail'] = $this->tr_model->get_data_penjualan_detail($id);
            $total_jual = 0;
            foreach ($data['penjualan_detail'] as $row) {
                $total_jual += $row->qty * $row->harga_jual;
            }
            $data['total_jual'] = $total_jual;
            $result = $this->common_model->db_select('nd_pembayaran_penjualan where penjualan_id='.$id);
            foreach ($result as $row) {
                $data['pembayaran_penjualan'][$row->pembayaran_type_id] = $row->amount; 
                $data['pembayaran_keterangan'][$row->pembayaran_type_id] = $row->keterangan;
            }

            $data['data_giro'] = $this->common_model->db_select('nd_pembayaran_penjualan_giro where penjualan_id='.$id);

            $data['saldo_awal'] = 0;
            if ($penjualan_type_id != 3) {
                $result = $this->tr_model->get_dp_awal($customer_id, date('Y-m-d'));
                foreach ($result as $row) {
                    $data['saldo_awal'] = $row->saldo;
                }
            }

            if ($customer_id != '') {
                $data['dp_list_detail'] = $this->tr_model->get_dp_berlaku($customer_id, $id); 
            }else{
                $data['dp_list_detail'] = array(); 
            }
            $data['penjualan_print'] = $this->tr_model->get_data_penjualan_detail_group($id);
            $data['data_pembayaran'] = $this->tr_model->get_data_pembayaran($id);
            $data['data_penjualan_detail_group'] = $this->tr_model->get_data_penjualan_detail_by_barang($id);
            $data['data_toko'] = $this->common_model->db_select('nd_toko where id = '.$toko_id);

            $get_next_bon = array();
            if ($no_faktur != '') {
                $get_next_bon = $this->common_model->get_next_faktur($no_faktur);
            }
            $data['faktur_link'] = $get_next_bon;

        }else{
            $data['dp_list_detail'] = array(); 
            $data['penjualan_data'] = array();
            $data['penjualan_detail'] = array();
            $data['total_jual'] = 0;
            $data['pembayaran_penjualan'] = array();
            $data['saldo_awal'] = 0;
            $data['data_giro'] = array();
            $data['penjualan_print'] = array();
            $data['data_toko'] = $this->common_model->db_select('nd_toko where id = '.$toko_id);
            $data['faktur_link'] = array();

        }

        $this->load->view('admin/template_blank',$data);
    }

    function get_customer_list_master($param = "all"){

        /* $url = 'http://localhost:3000/customers/customer-central/'.$param;
        
        $response = file_get_contents($url);
        $customers = json_decode($response);
        return ($customers); */
        return null;
    }

	function penjualan_list_detail(){
        // if (is_posisi_id() == 1) {
        //     $this->penjualan_list_detail_2();
        //     # code...
        // }else{
            
    		$menu = is_get_url($this->uri->segment(1)) ;
            $today = date("Y-m-d");
    		// $id = $this->uri->segment(2);
    		$id = $this->input->get('id');
            $ppn_data = $this->common_model->db_select("nd_ppn WHERE tanggal <='$today' ORDER BY tanggal desc limit 1");
            $ppn_set = 10;

            foreach ($ppn_data as $row) {
                $ppn_set = $row->ppn;
            }
            $ppn_list = $this->common_model->db_select("nd_ppn ORDER BY tanggal desc");

            $customer_central = $this->get_customer_list_master("all");
            
    		$data = array(
    			'content' =>'admin/transaction/penjualan_list_detail_new',
    			'breadcrumb_title' => 'Transaction',
    			'breadcrumb_small' => 'Formulir Penjualan',
    			'nama_menu' => $menu[0],
    			'nama_submenu' => $menu[1],
    			'common_data'=> $this->data,
    			'data_isi'=> $this->data,
    			'penjualan_type' => $this->common_model->db_select('nd_penjualan_type'),
    			'pembayaran_type' => $this->common_model->db_select('nd_pembayaran_type'),
    			'printer_list' => $this->common_model->db_select('nd_printer_list'),
                'ppn_list' => $ppn_list,
                'customer_central' => $customer_central
            );

            $data['customer_with_limit'] = array();

            $data['customer_harga'] = $this->common_model->db_select("nd_customer_harga");
            if (is_posisi_id() != 1) {
                // $data['customer_with_limit'] = $this->common_model->get_customer_with_limit();
            }
            
            $po_penjualan_id = "";

            $data['alamat_kirim'] = array();
            $data['dp_list_detail'] = array(); 
            $data['penjualan_data'] = array();
            $data['penjualan_detail'] = array();
            $data['total_jual'] = 0;
            $data['pembayaran_penjualan'] = array();
            $data['saldo_awal'] = 0;
            $data['data_giro'] = array();
            $data['penjualan_print'] = array();
            $data['data_toko'] = array();
            $data['faktur_link'] = array();
            $data['penjualan_posisi_barang'] = array();
            $data['data_penjualan_detail_group'] = array();
            $data['customer_data'] = array();
            $data['customer_id'] = 0;
            $data['po_penjualan_barang'] = array();
            $data['po_penjualan_list'] = array();
            $data['po_penjualan_id'] = "";
            $data['tipe_po'] = "";
            $is_custom_view = 0;
            $barang_list = array();

            $status = 0;
    		if ($id != '') {
    			// $penjualan_data = $this->tr_model->get_data_penjualan($id);
    			$penjualan_data = $this->tr_model->get_data_penjualan($id);
                // print_r($penjualan_data);
    			$data['penjualan_data'] = $penjualan_data;
    			foreach ($penjualan_data as $row) {
                    $po_penjualan_id = $row->po_penjualan_id;
                    $tipe_po = $row->tipe_po;
    				$penjualan_type_id = $row->penjualan_type_id;
    				$customer_id = $row->customer_id;
    				$toko_id = $row->toko_id;
                    $no_faktur = $row->no_faktur_raw;
                    $status = $row->status;
                    $is_custom_view = $row->is_custom_view;
    			}

                $data['po_penjualan_id'] = $po_penjualan_id;
                $data['tipe_po'] = $tipe_po;
                
                if ($penjualan_type_id != 3) {
                    $cond_po = '';
                    if ($po_penjualan_id != '') {
                        $cond_po = " OR po_penjualan_id = $po_penjualan_id";
                    }
                    $data['po_penjualan_list'] = $this->common_model->get_po_customer($customer_id, $cond_po);
                }

                $get_de = $this->common_model->db_select("nd_penjualan_detail WHERE penjualan_id = '$id'");
                $res_detail_id = [];
                foreach ($get_de as $rw) {
                    array_push($res_detail_id, $rw->id);
                }
                
                $cond_d = (count($res_detail_id) > 0 ? implode(',',$res_detail_id) : "0");
    			$data['penjualan_detail'] = $this->tr_model->get_data_penjualan_detail($id, $cond_d);
    			$total_jual = 0;
    			foreach ($data['penjualan_detail'] as $row) {
    				$total_jual += $row->qty * $row->harga_jual;
    			}
    			$data['total_jual'] = $total_jual;
    			$result = $this->common_model->db_select('nd_pembayaran_penjualan where penjualan_id='.$id." AND amount != 0");
    			foreach ($result as $row) {
    				$data['pembayaran_penjualan'][$row->pembayaran_type_id] = $row->amount; 
    				$data['pembayaran_keterangan'][$row->pembayaran_type_id] = $row->keterangan;
    			}

    			$data['data_giro'] = $this->common_model->db_select('nd_pembayaran_penjualan_giro where penjualan_id='.$id);

    			$data['saldo_awal'] = 0;
    			if ($penjualan_type_id != 3) {
    				$result = $this->tr_model->get_dp_awal($customer_id, date('Y-m-d'));
    				foreach ($result as $row) {
    					$data['saldo_awal'] = $row->saldo;
    				}
    			}

                if ($customer_id != '') {
                    $data['dp_list_detail'] = $this->tr_model->get_dp_berlaku($customer_id, $id);
                    $data['alamat_kirim'] = $this->common_model->db_select("nd_customer_alamat_kirim where customer_id=".$customer_id.' AND status_aktif=1');
                }else{
                    $data['dp_list_detail'] = array(); 
                }
                // $data['penjualan_print'] = array();
    			$data['data_pembayaran'] = $this->tr_model->get_data_pembayaran($id);
    			// $data['data_penjualan_detail_group'] = $this->tr_model->get_data_penjualan_detail_by_barang($id);
    			$data['data_penjualan_detail_group'] = array();
    			$data['data_toko'] = $this->common_model->db_select('nd_toko where id = '.$toko_id);

                $get_next_bon = array();
                if ($no_faktur != '') {
                    $get_next_bon = $this->common_model->get_next_faktur($no_faktur);
                }
                $data['faktur_link'] = $get_next_bon;
                $data['penjualan_posisi_barang'] = $this->common_model->db_select('nd_penjualan_posisi_barang where penjualan_id ='.$id);
                $data['customer_data'] = array();
                if ($penjualan_type_id != 3) {
                    $data['customer_data'] = $this->common_model->db_select("nd_customer where id=".$customer_id);
                }
                $data['customer_id'] = $customer_id;
                
                $tipe_harga = ($penjualan_type_id == 2 ? 2 : 1 );
                if ($tipe_harga == 1) {
                    $customer_harga_id = '';
                if ($customer_id != "" && $customer_id != 0) {
                        $customer_id_get_harga = $customer_id;
                        $get_c = $this->common_model->db_select("nd_customer_harga WHERE tipe = 1 AND customer_id = $customer_id");
                        foreach ($get_c as $row) {
                            $customer_harga_id = $id;
                        }
                    }

                    if ($customer_harga_id == '') {
                        $customer_id_get_harga = 0;
                    }
                    $barang_list = $this->common_model->get_barang_jual_dan_harga($customer_id_get_harga, $tipe_harga);
                }else{
                    if (is_posisi_id() == 1) {
                        echo $customer_id.'<hr/> '. $tipe_harga;
                        // $this->output->enable_profiler(TRUE);
                    }
                    $barang_list = $this->common_model->get_barang_jual_dan_harga($customer_id, $tipe_harga);
                }
                
                $data['po_penjualan_barang'] = array();
                if ($po_penjualan_id != '') {
                    $data['po_penjualan_barang'] = $this->tr_model->get_barang_po($po_penjualan_id);
                }
    		}


            $data['barang_list'] = $barang_list;

            $data['ppn_set'] = $ppn_set;
            $data['is_custom_view'] = $is_custom_view;
            

            if (is_posisi_id() != 1) {
                if ($is_custom_view && ( is_posisi_id() <= 3 || is_posisi_id() == 6) ) {
                    $data['content'] = 'admin/transaction/penjualan_list_detail_new5';
                    $this->load->view("admin/template",$data);
                }else{
                    $data['content'] = 'admin/transaction/penjualan_list_detail_new4';
                    $this->load->view("admin/template",$data);
                }
            }

            if (is_posisi_id() == 1) {
                    $data['content'] = 'admin/transaction/penjualan_list_detail_new4';
                    $this->load->view("admin/template",$data);
                    // print_r($barang_list);
                // echo $customer_id_get_harga, $tipe_harga;
                // $this->output->enable_profiler(TRUE);
            }
        // }
	}

    function penjualan_print_HTML(){

        $id = $this->input->get("id");
        $print_type = $this->input->get("print_type");
        $alamat_kirim_id = $this->input->get("alamat_kirim_id");
        $today = date("Y-m-d");
        

        if ($print_type=='') {
            $print_type=2;
        }


        $ppn_data = $this->common_model->db_select("nd_ppn WHERE tanggal <='$today' ORDER BY tanggal desc limit 1");
        $ppn_set = 10;

        foreach ($ppn_data as $row) {
            $ppn_set = $row->ppn;
        }
        $ppn_list = $this->common_model->db_select("nd_ppn ORDER BY tanggal desc");
        // 1. print faktur
        // 2. print faktur detail
        // 3. print faktur detail+sj+packing list
        // 3. print faktur detail+sj non harga+packing list
        
        $penjualan_data = $this->tr_model->get_data_penjualan($id);
        $data['penjualan_data'] = $penjualan_data;
        foreach ($penjualan_data as $row) {
            $penjualan_type_id = $row->penjualan_type_id;
            $customer_id = $row->customer_id;
            $toko_id = $row->toko_id;
            $no_faktur = $row->no_faktur_raw;
            $status = $row->status;
            $is_custom_view = $row->is_custom_view;
        }
        $get_de = $this->common_model->db_select("nd_penjualan_detail WHERE penjualan_id = '$id'");
        $res_detail_id = [];
        foreach ($get_de as $rw) {
            array_push($res_detail_id, $rw->id);
        }
        $cond_d = (count($res_detail_id) > 0 ? implode(',',$res_detail_id) : "0");
        $data['penjualan_detail'] = $this->tr_model->get_data_penjualan_detail($id, $cond_d);
        $total_jual = 0;
        foreach ($data['penjualan_detail'] as $row) {
            $total_jual += $row->qty * $row->harga_jual;
        }
        $data['penjualan_print'] = $this->tr_model->get_data_penjualan_detail_group($id);
        $data['data_penjualan_detail_group'] = $this->tr_model->get_data_penjualan_detail_print($id);
        $total_jual = 0;
        foreach ($data['penjualan_detail'] as $row) {
            $total_jual += $row->qty * $row->harga_jual;
        }
        $data['total_jual'] = $total_jual;
        $result = $this->common_model->db_select('nd_pembayaran_penjualan where penjualan_id='.$id." AND amount != 0");
        foreach ($result as $row) {
            $data['pembayaran_penjualan'][$row->pembayaran_type_id] = $row->amount; 
            $data['pembayaran_keterangan'][$row->pembayaran_type_id] = $row->keterangan;
        }

        $data['data_giro'] = $this->common_model->db_select('nd_pembayaran_penjualan_giro where penjualan_id='.$id);

        $data['saldo_awal'] = 0;
        if ($penjualan_type_id != 3) {
            $result = $this->tr_model->get_dp_awal($customer_id, date('Y-m-d'));
            foreach ($result as $row) {
                $data['saldo_awal'] = $row->saldo;
            }
        }

        $data['alamat_kirim'] = array();
        if ($alamat_kirim_id != '') {
            $data['alamat_kirim'] = $this->common_model->db_select("nd_customer_alamat_kirim where id=".$alamat_kirim_id);
        }
        // $data['penjualan_print'] = array();
        $data['data_pembayaran'] = $this->tr_model->get_data_pembayaran($id);
        $data['data_toko'] = $this->common_model->db_select('nd_toko where id = '.$toko_id);

        $get_next_bon = array();
        if ($no_faktur != '') {
            $get_next_bon = $this->common_model->get_next_faktur($no_faktur);
        }
        $data['faktur_link'] = $get_next_bon;
        $data['penjualan_posisi_barang'] = $this->common_model->db_select('nd_penjualan_posisi_barang where penjualan_id ='.$id);
        $data['customer_data'] = array();
        if ($penjualan_type_id != 3) {
            $data['customer_data'] = $this->common_model->db_select("nd_customer where id=".$customer_id);
        }
        $data['customer_id'] = $customer_id;

        $data['content'] = 'admin/transaction/penjualan_list_detail_newprint';
        $data['print_type'] = $print_type;
        $data['ppn_set'] = $ppn_set;

        $this->load->view($data['content'],$data);


    }
        
    function get_customer_limit(){
        $customer_id = $this->input->get('customer_id');
        $res = $this->common_model->get_customer_limit($customer_id);
        echo json_encode($res);
    }

    function penjualan_detail_update_view(){
        // echo json_encode($this->input->get());
        $is_custom_view = $this->input->get('is_custom_view');
        $penjualan_id = $this->input->get('id');
        $data = array("is_custom_view"=>$is_custom_view);
        $this->common_model->db_update("nd_penjualan", $data,"id", $penjualan_id);
        echo json_encode("OK");

    }

    function penjualan_list_detail_2(){
        $menu = is_get_url($this->uri->segment(1)) ;
        // $id = $this->uri->segment(2);
        $id = $this->input->get('id');
        $print_now = $this->input->get("print_now");

        $print_now = ($print_now == '' ? 0 : 1);
        $customer_id = '';
        $page_type='_4';
        if ($this->input->get('page_type') !='') {
            $page_type = "_".$this->input->get("page_type");
        }

        $data = array(
            'content' =>'admin/transaction/penjualan_list_detail'.$page_type,
            'breadcrumb_title' => 'Transaction',
            'breadcrumb_small' => 'Formulir Penjualan',
            'nama_menu' => $menu[0],
            'nama_submenu' => $menu[1],
            'common_data'=> $this->data,
            'data_isi'=> $this->data,
            'penjualan_type' => $this->common_model->db_select('nd_penjualan_type'),
            'pembayaran_type' => $this->common_model->db_select('nd_pembayaran_type'),
            'printer_list' => $this->common_model->db_select('nd_printer_list'),
            'customer_data' => array(),
            'customer_alamat_kirim' => array(),
            'print_now' => $print_now
            );

        $data['dp_list_detail'] = array(); 
        $data['penjualan_data'] = array();
        // $data['penjualan_detail'] = array();
        $data['total_jual'] = 0;
        $data['pembayaran_penjualan'] = array();
        $data['saldo_awal'] = 0;
        $data['data_giro'] = array();
        $data['penjualan_print'] = array();
        $data['data_toko'] = array();
        $data['faktur_link'] = array();
        $data['customer_data'] = array();
        $data['customer_alamat_kirim'] = array();
        $data['surat_jalan_list'] = array();
        $data['penjualan_kirim_detail'] = array();


        if ($id != '') {
            $penjualan_data = $this->tr_model->get_data_penjualan($id);
            // print_r($penjualan_data);
            $data['penjualan_data'] = $penjualan_data;
            foreach ($penjualan_data as $row) {
                $penjualan_type_id = $row->penjualan_type_id;
                $customer_id = $row->customer_id;
                $toko_id = $row->toko_id;
                $no_faktur = $row->no_faktur_raw;
            }
            // $data['penjualan_detail'] = $this->tr_model->get_data_penjualan_detail($id);
            /*$total_jual = 0;
            foreach ($data['penjualan_detail'] as $row) {
                $total_jual += $row->qty * $row->harga_jual;
            }*/
            /*$data['total_jual'] = $total_jual;*/
            $result = $this->common_model->db_select('nd_pembayaran_penjualan where penjualan_id='.$id." AND amount != 0");
            foreach ($result as $row) {
                $data['pembayaran_penjualan'][$row->pembayaran_type_id] = $row->amount; 
                $data['pembayaran_keterangan'][$row->pembayaran_type_id] = $row->keterangan;
            }

            // $data['data_giro'] = $this->common_model->db_select('nd_pembayaran_penjualan_giro where penjualan_id='.$id);

            $data['saldo_awal'] = 0;
            if ($penjualan_type_id != 3) {
                $result = $this->tr_model->get_dp_awal($customer_id, date('Y-m-d'));
                foreach ($result as $row) {
                    $data['saldo_awal'] = $row->saldo;
                }
            }

            if ($customer_id != '') {
                $data['dp_list_detail'] = $this->tr_model->get_dp_berlaku($customer_id, $id); 
            }else{
                $data['dp_list_detail'] = array(); 
            }
            $data['penjualan_print'] = $this->tr_model->get_data_penjualan_detail_group($id);
            $data['data_pembayaran'] = $this->tr_model->get_data_pembayaran($id);
            $data['data_penjualan_detail_group'] = $this->tr_model->get_data_penjualan_detail_by_barang($id);
            $data['data_toko'] = $this->common_model->db_select('nd_toko where id = '.$toko_id);
            if ($penjualan_type_id != 3) {
                $data['customer_data'] = $this->common_model->db_select("nd_customer where id=".$customer_id);
                $data['customer_alamat_kirim'] = $this->common_model->db_select("nd_customer_alamat_kirim where customer_id=".$customer_id);
            }else{
                $data['customer_alamat_kirim'] = $this->tr_model->alamat_kirim_non_cust($id);
            }

            $get_next_bon = array();
            if ($no_faktur != '') {
                $get_next_bon = $this->common_model->get_next_faktur($no_faktur);
            }
            $data['faktur_link'] = $get_next_bon;
            // $data['surat_jalan_list'] = $this->tr_model->get_surat_jalan_penjualan($id);
            $data['penjualan_kirim_detail'] = $this->tr_model->get_data_penjualan_detail_kirim($id);

        }

        $data['customer_id'] = $customer_id;
        $this->load->view('admin/template',$data);
        if (is_posisi_id() == 1) {
            // $this->output->enable_profiler(TRUE);
        }
    }

    function penjualan_print_page(){
        $id = $this->input->get('id');
        $action = $this->input->get('action');
        $toko_id=1;

        $data = array(
            'penjualan_data'=> $this->tr_model->get_data_penjualan($id),
            'penjualan_type' => $this->common_model->db_select('nd_penjualan_type'),
            'pembayaran_type' => $this->common_model->db_select('nd_pembayaran_type'),
            'printer_list' => $this->common_model->db_select('nd_printer_list'),
            'action' => $action,
            'penjualan_id' => $id
            );

        foreach ($data['penjualan_data'] as $row) {
            $penjualan_type_id = $row->penjualan_type_id;
            $customer_id = $row->customer_id;
        }
        $data['penjualan_print'] = $this->tr_model->get_data_penjualan_detail_group($id);
        $data['data_pembayaran'] = $this->tr_model->get_data_pembayaran($id);
        $data['data_penjualan_detail_group'] = $this->tr_model->get_data_penjualan_detail_by_barang($id);
        $data['data_toko'] = $this->common_model->db_select('nd_toko where id = '.$toko_id);

        $data['customer_data'] = array();
        if ($penjualan_type_id != 3) {
            $data['customer_data'] = $this->common_model->db_select("nd_customer where id=".$customer_id);
        }
        $data['customer_id'] = $customer_id;

        $this->load->view('admin/transaction/penjualan_print_page',$data);
    }

    function get_penjualan_detail_item(){
        $id = $this->input->post("penjualan_id");
        echo json_encode($this->tr_model->get_data_penjualan_detail($id)) ;
    }

    /*function get_qty_stock_by_barang_detail(){
        $gudang_id = $this->input->post('gudang_id');
        $barang_id = $this->input->post('barang_id');
        $warna_id = $this->input->post('warna_id');
        $tanggal = is_date_formatter($this->input->post('tanggal'));
        // $get_stok_opname = $this->common_model->db_select("nd_stok_opname where tanggal <= '".$tanggal."' ORDER BY tanggal desc LIMIT 1");
        $tanggal_awal = '2018-01-01';
        $stok_opname_id = 0;
        // foreach ($get_stok_opname as $row) {
        //     $tanggal_awal = $row->tanggal;
        //     $stok_opname_id = $row->id;
        // }
        $result = $this->tr_model->get_qty_stok_by_barang_detail($gudang_id, $barang_id,$warna_id, $tanggal_awal, $stok_opname_id);
        
        // print_r($this->input->post());
        // print_r($this->input->post());
        echo json_encode($result->result());
        
        // print_r($result);
    }*/

	function cek_history_harga(){
		$customer_id = $this->input->post('customer_id');
		$cond = '';
		if ($customer_id != '') {
			$cond = ' AND customer_id ='.$customer_id;
		}
		$barang_id = $this->input->post('barang_id');

		$result = $this->tr_model->cek_harga_jual($barang_id,$cond);
		echo json_encode($result);

	}

	function get_latest_harga(){
		$customer_id = $this->input->post('customer_id');
		$barang_id = $this->input->post('barang_id');
		$cond = ' AND customer_id ='.$customer_id;

		$harga = 0;
    	$result = $this->tr_model->get_lastest_harga($barang_id, $cond);
		foreach ($result as $row) {
			$harga = $row->harga_jual;
		}
		echo $harga;
	}

	function get_latest_harga_non_customer(){
		$customer_id = $this->input->post('customer_id');
		$barang_id = $this->input->post('barang_id');
		$cond = ' AND customer_id ='.$customer_id;

		$harga = 0;
		$result = $this->tr_model->get_lastest_harga_non_customer($barang_id, $cond);
		foreach ($result as $row) {
			$harga = $row->harga_jual;
			$id = $row->id;
		}
		echo $harga;
	}

    function penjualan_list_detail_insert_2(){
        // print_r($this->input->post());
        $ini = $this->input;
        $penjualan_id =  $ini->post('penjualan_id');
        $data = array(
            'penjualan_id' => $penjualan_id,
            'gudang_id' => $ini->post('gudang_id'),
            'barang_id' => $ini->post('barang_id'),
            'warna_id' => $ini->post('warna_id'),
            'harga_jual' => str_replace('.', '', $ini->post('harga_jual')) );

        $result_id = $this->common_model->db_insert('nd_penjualan_detail', $data);
        $i = 0;
        $qty_list = explode('--', $ini->post('data_qty'));
        foreach ($qty_list as $key => $value) {
            $qty = explode('??', $value);
            $jumlah_roll = (!isset($qty[1]) && $qty[1] == '' && $qty[1]  ? 0 : $qty[1]) ;
            $data_qty[$i] = array(
                'penjualan_detail_id' => $result_id,
                'qty' => $qty[0],
                'jumlah_roll' => $jumlah_roll
                );
            $i++;
        }

        $this->common_model->db_insert_batch('nd_penjualan_qty_detail',$data_qty);
        
        echo "success??".$result_id;
    }

    function penjualan_list_detail_update_2(){
        // print_r($this->input->post());
        $ini = $this->input;
        $penjualan_id =  $ini->post('penjualan_id');
        $penjualan_detail_id =  $ini->post('id');
        $data = array(
            'gudang_id' => $ini->post('gudang_id'),
            'barang_id' => $ini->post('barang_id'),
            'warna_id' => $ini->post('warna_id'),
            'harga_jual' => str_replace('.', '', $ini->post('harga_jual')) );

        $this->common_model->db_update('nd_penjualan_detail', $data,'id', $penjualan_detail_id);
        
        $get_qty = $this->common_model->db_select("nd_penjualan_qty_detail WHERE penjualan_detail_id=".$penjualan_detail_id);
        $max = count($get_qty);
        $idx_qty = 0;
        foreach ($get_qty as $row) {
            $qty_id[$idx_qty] = $row->id;
            $idx_qty++;
        }

        $i = 0;
        $qty_list = explode('--', $ini->post('data_qty'));
        $data_insert = array();
        foreach ($qty_list as $key => $value) {
            $qty = explode('??', $value);
            if ($qty[0] != 0 || $qty[1] != 0) {
                if ($i < $max ) {
                    $data_upd[$i] = array(
                        'penjualan_detail_id' => $penjualan_detail_id,
                        'qty' => $qty[0],
                        'jumlah_roll' => $qty[1] );
                    $this->common_model->db_update('nd_penjualan_qty_detail', $data_upd[$i],'id', $qty_id[$i]);
                }else{
                    $data_insert[$i] = array(
                        'penjualan_detail_id' => $penjualan_detail_id,
                        'qty' => $qty[0],
                        'jumlah_roll' => $qty[1] );
                }
                $i++;
            }
        }

        for ($j=$i; $j < $max ; $j++) { 
            $this->common_model->db_delete('nd_penjualan_qty_detail', 'id', $qty_id[$j]);
        }

        if (count($data_insert) > 0) {
            $this->common_model->db_insert_batch('nd_penjualan_qty_detail',$data_insert);
        }
    }


	function penjualan_list_detail_insert(){
        // print_r($this->input->post());
        $ini = $this->input;
        $penjualan_id =  $ini->post('penjualan_id');
        $po_penjualan_id =  $ini->post('po_penjualan_id');

        $harga_jual = str_replace('.', '', $ini->post('harga_jual'));

        $qty_list = explode('--', $ini->post('rekap_qty'));
        $subqty = 0;
        $subjumlah_roll = 0;
        foreach ($qty_list as $key => $value) {
            $qty = explode('??', $value);
            $jumlah_roll = (!isset($qty[1]) || $qty[1] == '' ? 0 : $qty[1]) ;
            $subqty += $qty[0]*($jumlah_roll == 0 ? 1 : $jumlah_roll);
            $subjumlah_roll += $jumlah_roll;
        } 

        $nama_jual_tercetak = $this->input->post("nama_jual_tercetak");
        $kode_beli = $this->input->post("kode_beli");
        $data = array(
            'penjualan_id' => $penjualan_id,
            'gudang_id' => $ini->post('gudang_id'),
            'barang_id' => $ini->post('barang_id'),
            'nama_jual_tercetak' => ($nama_jual_tercetak == '' || $nama_jual_tercetak == 0 ? null : $nama_jual_tercetak),
            'kode_beli' => ($kode_beli == '' || $kode_beli == 0 ? null : $kode_beli ),
            'warna_id' => $ini->post('warna_id'),
            'harga_jual' => str_replace(',00', '', $harga_jual),
            'subqty' => $subqty,
            'subjumlah_roll' => $subjumlah_roll );

        $result_id = $this->common_model->db_insert('nd_penjualan_detail', $data);
        $i = 0;
        foreach ($qty_list as $key => $value) {
            $qty = explode('??', $value);
            $jumlah_roll = (!isset($qty[1]) && $qty[1] == '' && $qty[1]  ? 0 : $qty[1]) ;
            if ($jumlah_roll != null) {
                $data_qty[$i] = array(
                    'penjualan_detail_id' => $result_id,
                    'qty' => $qty[0],
                    'jumlah_roll' => $jumlah_roll
                    );
                $i++;
            }
        }

        $this->common_model->db_insert_batch('nd_penjualan_qty_detail',$data_qty);
        redirect($this->setting_link('transaction/penjualan_list_detail').'/?id='.$penjualan_id);
    }

    function penjualan_list_detail_update(){
        // print_r($this->input->post());
        $ini = $this->input;
        $penjualan_id =  $ini->post('penjualan_id');
        $penjualan_detail_id =  $ini->post('penjualan_detail_id');

        $qty_list = explode('--', $ini->post('rekap_qty'));
        $subqty = 0;
        $subjumlah_roll = 0;
        foreach ($qty_list as $key => $value) {
            $qty = explode('??', $value);
            $jumlah_roll = (!isset($qty[1]) || $qty[1] == '' ? 0 : $qty[1]) ;
            $subqty += $qty[0]*($jumlah_roll == 0 ? 1 : $jumlah_roll);
            $subjumlah_roll += $jumlah_roll;
        } 
        $nama_jual_tercetak = $this->input->post("nama_jual_tercetak");
        $kode_beli = $this->input->post("kode_beli");
        $data = array(
            'gudang_id' => $ini->post('gudang_id'),
            'barang_id' => $ini->post('barang_id'),
            'nama_jual_tercetak' => ($nama_jual_tercetak == '' || $nama_jual_tercetak == 0 ? null : $nama_jual_tercetak),
            'kode_beli' => ($kode_beli == '' || $kode_beli == 0 ? null : $kode_beli ),
            'warna_id' => $ini->post('warna_id'),
            'harga_jual' => str_replace('.', '', $ini->post('harga_jual')),
            'subqty' => $subqty,
            'subjumlah_roll' => $subjumlah_roll  );

        // echo "<hr/>";
        // print_r($data);

        $this->common_model->db_update('nd_penjualan_detail', $data,'id', $penjualan_detail_id);
        
        $get_qty = $this->common_model->db_select("nd_penjualan_qty_detail WHERE penjualan_detail_id=".$penjualan_detail_id);
        // echo "<hr/>";
        // print_r($get_qty);
        // echo "<hr/>";
        
        $max = count($get_qty);
        $idx_qty = 0;
        foreach ($get_qty as $row) {
            $qty_id[$idx_qty] = $row->id;
            // echo $qty_id[$idx_qty];
            $idx_qty++;
        }

        $i = 0;
        $qty_list = explode('--', $ini->post('rekap_qty'));
        $data_insert = array();
        foreach ($qty_list as $key => $value) {
            $qty = explode('??', $value);
            if ($qty[0] != 0 || $qty[1] != 0) {
                if ($i < $max ) {
                    $data_upd[$i] = array(
                        'penjualan_detail_id' => $penjualan_detail_id,
                        'qty' => $qty[0],
                        'jumlah_roll' => $qty[1] );
                    $this->common_model->db_update('nd_penjualan_qty_detail', $data_upd[$i],'id', $qty_id[$i]);
                }else{
                    $data_insert[$i] = array(
                        'penjualan_detail_id' => $penjualan_detail_id,
                        'qty' => $qty[0],
                        'jumlah_roll' => $qty[1] );
                }
                $i++;
            }
        }

        // echo '<hr/>';
        // if (count($qty_list) < $max) {
        //     echo $i;
        //     echo $max;
        //     echo count($qty_list) - $max;
        // }
        // echo '<hr/>';

        for ($j=$i; $j < $max ; $j++) { 
            $this->common_model->db_delete('nd_penjualan_qty_detail', 'id', $qty_id[$j]);
        }

        if (count($data_insert) > 0) {
            $this->common_model->db_insert_batch('nd_penjualan_qty_detail',$data_insert);
        }
        redirect(is_setting_link('transaction/penjualan_list_detail').'/?id='.$penjualan_id);
    }

	function penjualan_list_detail_remove(){
		$id = $this->input->post('id');
		$this->common_model->db_delete('nd_penjualan_detail','id',$id);
		$this->common_model->db_delete('nd_penjualan_qty_detail','penjualan_detail_id',$id);
		echo 'OK';

	}

	function update_penjualan_detail_harga(){
		$id = $this->input->post('id');
		$data = array(
			'harga_jual' => $this->input->post('harga_jual') );
		$this->common_model->db_update('nd_penjualan_detail',$data,'id',$id);
		// print_r($this->input->post());
		echo 'OK';

	}

	function penjualan_qty_detail_update(){
		$penjualan_detail_id = $this->input->post('penjualan_detail_id');
		$qty = $this->input->post('rekap_qty');

		$qty_list = explode('--', $qty);
		foreach ($qty_list as $key => $value) {
			$qty = explode('??', $value);
			$data_qty[$key] = array(
				'penjualan_detail_id' => $penjualan_detail_id,
				'qty' => $qty[0] ,
				'jumlah_roll' => $qty[1] );
		}

		$this->common_model->db_delete('nd_penjualan_qty_detail','penjualan_detail_id',$penjualan_detail_id);
		$this->common_model->db_insert_batch('nd_penjualan_qty_detail',$data_qty);
		// print_r($this->input->post());
		echo 'OK';
	}

	function pembayaran_penjualan_update(){
		$penjualan_id = $this->input->post('penjualan_id');
		$pembayaran_type_id = $this->input->post('pembayaran_type_id');
		$data = array(
			'penjualan_id' => $penjualan_id,
			'pembayaran_type_id' => $pembayaran_type_id,
			'amount' => ($this->input->post('amount') != '' ? str_replace('.', '', $this->input->post('amount')) : 0),
            'user_id' => is_user_id() );
		
		$result = $this->common_model->db_select('nd_pembayaran_penjualan where penjualan_id='.$penjualan_id." AND pembayaran_type_id=".$pembayaran_type_id);
		$id = '';
		foreach ($result as $row) {
			$id = $row->id;
		}
		$status = 1;
        $get = $this->common_model->db_select("nd_penjualan where id=".$penjualan_id);
        if (is_posisi_id() > 3) {
            foreach ($get as $row) {
                $status = $row->status;
            }
        }
        if ($status == 1) {
            if ($id == '') {
                if ($this->input->post('amount') != 0 && $this->input->post('amount') != '') {
                    $this->common_model->db_insert('nd_pembayaran_penjualan', $data);
                }
            }else{
                
                $this->common_model->db_update('nd_pembayaran_penjualan', $data,'id', $id);
            }
        }

        if ($status == 1) {
            echo 'OK';
        }else{
            echo "NO";
        }
	}

    function pembayaran_penjualan_dp_update(){
        $penjualan_id = $this->input->post('penjualan_id');
        $post = (array)$this->input->post();
        $idx = 0;
        foreach ($post as $key => $value) {
            if (strpos($key, 'amount_') !== false) {
                // echo $key.'-->'.$value.'<br/>';
                $data_get = explode('_', $key);
                $dp_masuk_id[$idx] = $data_get[1];
                $isi[$idx] = str_replace('.', '', $value);
                $idx++;
            }            
        }

        foreach ($dp_masuk_id as $key => $value) {
            $data = array(
                'penjualan_id' => $penjualan_id ,
                'pembayaran_type_id' => 1,
                'dp_masuk_id' => $dp_masuk_id[$key],
                'amount' => $isi[$key],
                );

            $id = '';
            $get_id = $this->common_model->db_select("nd_pembayaran_penjualan where penjualan_id =".$penjualan_id." AND pembayaran_type_id = 1 AND dp_masuk_id =".$value);
            foreach ($get_id as $row) {
                $id = $row->id;
            }
            if ($id == '') {
                if ($isi[$key] != 0) {
                    $this->common_model->db_insert('nd_pembayaran_penjualan', $data);
                }
            }else{
                if ($isi[$key] != 0){
                    $this->common_model->db_update('nd_pembayaran_penjualan',$data, 'id', $id);
                }else{
                    $this->common_model->db_delete('nd_pembayaran_penjualan','id', $id);
                }
            }

        }

        redirect($this->setting_link('transaction/penjualan_list_detail').'/?id='.$penjualan_id);
    }

	function penjualan_data_update(){
		$penjualan_id = $this->input->post('penjualan_id');
		$data = array(
			$this->input->post('column') => $this->input->post('value') );
		$this->common_model->db_update('nd_penjualan', $data,'id', $penjualan_id);
		echo 'OK';
	}

	function penjualan_list_batal(){
		$id = $this->input->get('id');
		$data = array(
			'status_aktif' => -1,
			'closed_by' => is_user_id(),
			'closed_date' => date('Y-m-d H:i:s') );
		// print_r($id);
		$this->common_model->db_update('nd_penjualan',$data,'id',$id);
		redirect($this->setting_link('transaction/penjualan_list'));
	}

    function penjualan_list_batal_ajax(){
		$id = $this->input->post('id');
		$data = array(
			'status_aktif' => -1,
			'closed_by' => is_user_id(),
			'closed_date' => date('Y-m-d H:i:s') );
		// print_r($id);
		$this->common_model->db_update('nd_penjualan',$data,'id',$id);
        echo "OK";
	}

	function penjualan_list_undo_batal(){
		$id = $this->input->get('id');
		echo $id;
		$data = array(
			'status_aktif' => 1,
			'closed_by' => is_user_id(),
			'closed_date' => date('Y-m-d H:i:s') );
		$this->common_model->db_update('nd_penjualan',$data,'id',$id);
		redirect($this->setting_link('transaction/penjualan_list'));
	}

	function get_search_no_faktur_jual(){
		$no_faktur = $this->input->post('no_faktur');
		$result = $this->tr_model->search_faktur_jual($no_faktur);
		echo json_encode($result);
	}

	function penjualan_list_close()
	{  
        // print_r($this->input->post());
		$id = $this->input->post('id');
		$no_faktur = 1;
        // tanggal udah format yyyy-mm-dd
		$tanggal = $this->input->post('tanggal');
		$tahun = date('Y', strtotime($tanggal));
		$tahun_pendek = date('y', strtotime($tanggal));
		$bulan = date('m', strtotime($tanggal));
        $revisi = 1;
        $batas_tanggal = '2022-04-01';
        $penjualan_type_id = '';
        $nama_keterangan_history = '';
        $alamat_keterangan_history = '';
        $customer_id = '';

        $nama_cust_fp = '';
        $alamat_cust_fp = '';
        $npwp_cust_fp = '';
        $nik_cust_fp = '';
        $alamat_keterangan = '';

        $no_faktur_lengkap = '';
        $data_track = array();
        $id_track = '';
        $data_track_action = "";


        foreach ($this->toko_list_aktif as $row) {
            $toko_kode = $row->pre_po;
            $no_faktur_lengkap = $toko_kode.":PJ01/".$tahun_pendek.$bulan."/";
        }

		$get = $this->common_model->db_select("nd_penjualan where id=".$id." FOR UPDATE");
		foreach ($get as $row) {
			$no_faktur = $row->no_faktur;
			$revisi = $row->revisi+1;
            $penjualan_type_id = $row->penjualan_type_id;
            $customer_id = $row->customer_id;
            $status_aktif = $row->status_aktif;
            $nama_keterangan_history = $row->nama_keterangan;
            $alamat_keterangan_history = $row->alamat_keterangan;
		}


        if ($penjualan_type_id != 3) {
        $get_customer_data = $this->common_model->data_customer_by_id($customer_id);
            foreach ($get_customer_data as $row) {
                $nama_cust_fp = trim($row->nama_customer);
                $nama_keterangan = $row->nama_customer;
                $alamat_cust_fp = $row->alamat_lengkap;
                $alamat_keterangan = $row->alamat;
                $npwp_cust_fp = $row->npwp;
                $nik_cust_fp = $row->nik;
            }
        }else{
            $nama_cust_fp = trim($nama_keterangan_history);
            $nama_keterangan = $nama_keterangan_history;
            $alamat_cust_fp = $alamat_keterangan_history;
            $alamat_keterangan = $alamat_keterangan_history;
            $npwp_cust_fp = "000000000000000";  
            $nik_cust_fp =  "0000000000000000";
        }

        
		if ($no_faktur == '') {
            
			// $data_get = $this->common_model->db_select("nd_penjualan where YEAR(tanggal)='".$tahun."' order by no_faktur desc limit 1 ");
			if ($tanggal < $batas_tanggal) {
                $data_get = $this->common_model->db_select("nd_penjualan order by no_faktur desc limit 1 ");
                foreach ($data_get as $row) {
                    $no_faktur = $row->no_faktur + 1;
                }
            }else{
                $data_get = $this->common_model->db_select("nd_number_tracker where YEAR(tanggal) = '$tahun' AND MONTH(tanggal) = '$bulan' order by number desc limit 1");
                $nf_now = 1;
                if (count($data_get) > 0) {
                    foreach ($data_get as $row) {
                        $id_track = $row->id;
                        $no_faktur = $row->number + 1;
                        $nf_now = $row->number;
                    }
                }else{
                    $no_faktur = 1; 
                }

                $data_get2 = $this->common_model->db_select("nd_penjualan where YEAR(tanggal) = '$tahun' AND MONTH(tanggal) = '$bulan' AND status_aktif != -1 AND no_faktur = $no_faktur");
                foreach ($data_get2 as $row) {
                    if ($no_faktur == $row->no_faktur) {
                        redirect('transaction/penjualan_close_warning');
                    }
                }

                // $data_get3 = $this->common_model->db_select("nd_penjualan where YEAR(tanggal) = '$tahun' AND MONTH(tanggal) = '$bulan' AND no_faktur = $nf_now");

                // if ($no_faktur > 1) {
                //     $id_latest = '';
                //     foreach ($data_get3 as $row) {
                //         $id_latest = $row->id;
                //     }
    
                //     if ($id_latest == '') {
                //         $no_faktur = $nf_now;
                //     }
                // }

                foreach ($data_get2 as $row) {
                    if ($no_faktur == $row->no_faktur) {
                        redirect('transaction/penjualan_close_warning');
                    }
                }

                $data_track = array(
                    'number' => $no_faktur,
                    'tanggal' => $tanggal
                );


                if ($no_faktur == 1 && $id_track == '') {
                    $data_track_action = "insert";
                }else if($id_track == ''){
                    redirect('transaction/penjualan_close_warning');
                }else{
                    $data_track_action = "update";
                    $this->common_model->db_update("nd_number_tracker", $data_track,'id', $id_track);
                }
                
            }

            $nf = str_pad($no_faktur,4,"0", STR_PAD_LEFT);
            $no_faktur_lengkap .= $nf;

            // if
			// echo $id;
			$data = array(
				'closed_by' => is_user_id() ,
				'no_faktur' => $no_faktur,
				'closed_date' => date('Y-m-d H:i:s'),
				'revisi' => $revisi,
                'no_faktur_fp' => $no_faktur_lengkap,
                'nama_cust_fp' => trim($nama_cust_fp),
                'alamat_cust_fp' => $alamat_cust_fp,
                'alamat_keterangan' => $alamat_keterangan,
                'npwp_cust_fp' => $npwp_cust_fp,
                'nik_cust_fp' => $nik_cust_fp,
				'status' => 0 );
			// print_r($data);

            $this->common_model->db_custom_query("START TRANSACTION");

            if ($data_track_action == "insert") {
                $this->common_model->db_insert("nd_number_tracker", $data_track);
            }else if($data_track_action == "update"){
                $this->common_model->db_update("nd_number_tracker", $data_track,'id', $id_track);
            }
    		$this->common_model->db_update('nd_penjualan',$data,'id',$id);

            $this->common_model->db_custom_query("COMMIT;");


		}else{
			
			$data = array(
				'closed_by' => is_user_id() ,
				'closed_date' => date('Y-m-d H:i:s'),
				'revisi' => $revisi,
                'nama_cust_fp' => trim($nama_cust_fp),
                'alamat_cust_fp' => $alamat_cust_fp,
                'alamat_keterangan' => $alamat_keterangan,
                'npwp_cust_fp' => $npwp_cust_fp,
                'nik_cust_fp' => $nik_cust_fp,
				'status' => 0 );

            $tgl = date('Y-m', strtotime($tanggal));
            if ($tgl != date('Y-m')) {
                $tgl = date('Y-m-t H:i:s', strtotime($tanggal));
                $data = array(
                    'closed_by' => is_user_id() ,
                    'closed_date' => $tgl,
                    'revisi' => $revisi,
                    'nama_cust_fp' => trim($nama_cust_fp),
                    'alamat_cust_fp' => $alamat_cust_fp,
                    'alamat_keterangan' => $alamat_keterangan,
                    'npwp_cust_fp' => $npwp_cust_fp,
                    'nik_cust_fp' => $nik_cust_fp,
                    'status' => 0 );
            }

            $this->common_model->db_update('nd_penjualan',$data,'id',$id);
		}

        if ($status_aktif != -1) {
            $tipe_ambil_barang_id = $this->input->post('tipe_ambil_barang_id');
            if ($tipe_ambil_barang_id == 2) {
                $tanggal_pengambilan = date('Y-m-d');
            }elseif ($tipe_ambil_barang_id == 3) {
                $tanggal_pengambilan = date('Y-m-d', strtotime('+1 day'));
            }elseif ($tipe_ambil_barang_id == 4) {
                $tanggal_pengambilan = is_date_formatter($this->input->post('tanggal_ambil'));
                # code...
            }elseif ($tipe_ambil_barang_id == 5) {
                $tanggal_pengambilan = is_date_formatter($this->input->post('tanggal_kirim'));
            }

            if ($tipe_ambil_barang_id != 1) {
                $data_ambil = array(
                    'penjualan_id' => $id ,
                    'tipe_ambil_barang_id' => $tipe_ambil_barang_id,
                    'tanggal_pengambilan' => $tanggal_pengambilan,
                    'alamat_pengiriman' => $this->input->post('alamat_pengiriman'),
                    'user_id' => is_user_id() );

                $this->common_model->db_insert('nd_penjualan_posisi_barang', $data_ambil);
            }
        }

		redirect($this->setting_link('transaction/penjualan_list_detail').'/?id='.$id);
	}
    
    function penjualan_close_warning(){
        echo "<h2>Error, Mohon fotokan halaman error pada IT dept</h2>";
    }
    

    function penjualan_list_close_2()
    {
        $id = $this->input->post('id');
        $no_faktur = 1;
        $tanggal = $this->input->post('tanggal');
        $pre_faktur = "FPJ".date('dmy', strtotime($tanggal)).'-';
        $tahun = date('Y', strtotime($tanggal));
        $revisi = 1;

        $get = $this->common_model->db_select("nd_penjualan where id=".$id);
        foreach ($get as $row) {
            $no_faktur = $row->no_faktur;
            $revisi = $row->revisi+1;
            $status_aktif = $row->status_aktif;
        }

        if ($no_faktur == '') {
            $data_get = $this->common_model->db_select("nd_penjualan where YEAR(tanggal)='".$tahun."' order by no_faktur desc limit 1 ");
            foreach ($data_get as $row) {
                $no_faktur = $row->no_faktur + 1;
            }
            // echo $id;
            $data = array(
                'closed_by' => is_user_id() ,
                'no_faktur' => $no_faktur,
                'closed_date' => date('Y-m-d H:i:s'),
                'revisi' => $revisi,
                'status' => 0 );
            // print_r($data);
        }else{
            $data = array(
                'closed_by' => is_user_id() ,
                'closed_date' => date('Y-m-d H:i:s'),
                'revisi' => $revisi,
                'status' => 0 );
        }

        if ($status_aktif != -1) {
            $this->common_model->db_update('nd_penjualan',$data,'id',$id);
        }

        echo $pre_faktur.str_pad($no_faktur, 5,"0", STR_PAD_LEFT);
        // redirect($this->setting_link('transaction/penjualan_list_detail').'/?id='.$id."&print_now=true");
    }

	function penjualan_request_open(){
		$penjualan_id = $this->input->post('penjualan_id');
		$data = array(
			'status' => 1 );
		$this->common_model->db_update('nd_penjualan',$data,'id',$penjualan_id);
		redirect($this->setting_link('transaction/penjualan_list_detail').'/?id='.$penjualan_id);

	}

	function get_qty_stock_by_barang(){
		$gudang_id = $this->input->post('gudang_id');
		$barang_id = $this->input->post('barang_id');
        $warna_id = $this->input->post('warna_id');
		$penjualan_detail_id = $this->input->post('penjualan_detail_id');
        $cond_detail = '';
        $cond_detail_lain = '';
        if ($penjualan_detail_id != '') {
            $cond_detail = 'AND id != '.$penjualan_detail_id;
        }

		$pengeluaran_stok_lain_detail_id = $this->input->post('pengeluaran_stok_lain_detail_id');
        if ($pengeluaran_stok_lain_detail_id != '') {
            $cond_detail_lain = 'AND id != '.$pengeluaran_stok_lain_detail_id;
        }
        
        $tanggal = is_date_formatter($this->input->post('tanggal'));
        $tanggal = ($tanggal == '' ? date('Y-m-d') : $tanggal);
        $get_stok_opname = $this->common_model->get_last_opname($barang_id, $warna_id, $gudang_id,$tanggal);
        $tanggal_awal = '2018-01-01';
        $stok_opname_id = 0;
        foreach ($get_stok_opname as $row) {
            $tanggal_awal = $row->tanggal;
            $stok_opname_id = $row->id;
        }

        // $tanggal_awal = '2019-01-01';
		// $tanggal_awal = '2019-01-01';
        // if (is_posisi_id() == 1) {
        //     $result = $this->tr_model->get_qty_stok_by_barang($gudang_id, $barang_id,$warna_id, $tanggal_awal, $stok_opname_id, $cond_detail);
        //     $stok_barang_by_satuan = $this->tr_model->get_kartu_stok_barang_by_satuan($gudang_id, $barang_id, $warna_id, date('Y-m-d'), date('Y-m-d'), $tanggal_awal, $stok_opname_id, $cond_detail); 
        // }else{
            $stok_barang_by_satuan = $this->tr_model->get_kartu_stok_barang_by_satuan_2($gudang_id, $barang_id, $warna_id, date('Y-m-d'), date('Y-m-d'), $tanggal_awal, $stok_opname_id, $cond_detail, $cond_detail_lain); 
            $result = $this->tr_model->get_qty_stok_by_barang_2($gudang_id, $barang_id,$warna_id, $tanggal_awal, $stok_opname_id, $cond_detail, $cond_detail_lain);
        // }
        
        

        $return[0] = ($result->result());
        $return[1] = ($stok_barang_by_satuan);
        $return[2] = ($cond_detail);
        $return[3] = $gudang_id.','. $barang_id.','.$warna_id.','. $tanggal_awal.','. $stok_opname_id.','. $cond_detail;
        $return[4] = $tanggal_awal;
        $return[5] = array();
        if (is_posisi_id() == 1) {
            foreach ($stok_barang_by_satuan as $row) {
                array_push($return[5],array(
                    'qty' => $row->qty,
                    'jumlah_roll' => ($row->jumlah_roll_masuk - $row->jumlah_roll_keluar )  + ($row->roll_stok_masuk - $row->roll_stok_keluar)
                ));
            }
            // echo $gudang_id, $barang_id,$warna_id, $tanggal_awal, $stok_opname_id, $cond_detail;
            // echo $this->input->post('tanggal');
        }
        echo json_encode($return);
		
		// print_r($result);
	}

	function pembayaran_transfer_update(){
		$penjualan_id = $this->input->post('penjualan_id');
		$pembayaran_type_id = 4;

		$cond = array(
			'penjualan_id' => $penjualan_id ,
			'pembayaran_type_id' => 4 );
		$data = array(
			'penjualan_id' => $penjualan_id ,
			'pembayaran_type_id' => 4,
			'keterangan' => $this->input->post('keterangan') );

		$result = $this->common_model->db_select('nd_pembayaran_penjualan where penjualan_id='.$penjualan_id." AND pembayaran_type_id=".$pembayaran_type_id);
		$id = '';
		foreach ($result as $row) {
			$id = $row->id;
		}
		if ($id == '') {
			$this->common_model->db_insert('nd_pembayaran_penjualan', $data);
		}else{
			$this->common_model->db_update('nd_pembayaran_penjualan', $data,'id', $id);
		}

		echo 'OK';

	}

	function penjualan_detail_giro(){
		$ini = $this->input;
		$penjualan_id = $ini->post('penjualan_id');
		$data = array(
			'penjualan_id' => $penjualan_id ,
			'nama_bank' => $ini->post('nama_bank') ,
			'no_rek_bank' => $ini->post('no_rek_bank') ,
			'no_akun' => $ini->post('no_akun') ,
			'tanggal_giro' => is_date_formatter($ini->post('tanggal_giro')) ,
			'jatuh_tempo' => is_date_formatter($ini->post('jatuh_tempo')) );
		$result = $this->common_model->db_select('nd_pembayaran_penjualan_giro where penjualan_id = '.$penjualan_id);
		$id = '';
		foreach ($result_id as $row) {
			$id = $row->id;
		}
		if ($id == '') {
			$this->common_model->db_insert('nd_pembayaran_penjualan_giro', $data);
		}else{
			$this->common_model->db_update('nd_pembayaran_penjualan_giro', $data,'id', $id);
		}
		redirect($this->setting_link('transaction/penjualan_list_detail').'/?id='.$penjualan_id);

	}

//==========================penjualan_rinci=================================

    function penjualan_rinci(){
        $menu = is_get_url($this->uri->segment(1)) ;
        $get_status = 0;

        $tanggal_start = '';
        $tanggal_end = '';

        if ($this->input->get('tanggal_start') && $this->input->get('tanggal_start') != '' && $this->input->get('tanggal_end') != '') {
            $tanggal_start = $this->input->get('tanggal_start');
            $tanggal_end = $this->input->get('tanggal_end'); 
            $date_start = is_date_formatter($tanggal_start);
            $date_end = is_date_formatter($tanggal_end);
            $get_status = 1;
        }

        $data = array(
            'content' =>'admin/transaction/penjualan_rinci',
            'breadcrumb_title' => 'Transaction',
            'breadcrumb_small' => 'Daftar Penjualan',
            'nama_menu' => $menu[0],
            'nama_submenu' => $menu[1],
            'common_data'=> $this->data,
            'tanggal_start' => $tanggal_start,
            'tanggal_end' => $tanggal_end,
            'data_isi'=> $this->data );


        $data['user_id'] = is_user_id();
        $data['penjualan_list_detail'] = array();
        if ($get_status == 1) {
            $data['penjualan_list_detail'] = $this->tr_model->get_penjualan_rinci($date_start, $date_end,''); 
        }
        $this->load->view('admin/template',$data);
    }
//==========================surat jalan & pengambilan=================================

    function get_penjualan_pengambilan_data(){
        $penjualan_id = $this->input->post('penjualan_id');
        $get = $this->tr_model->get_penjualan_pengambilan_data($penjualan_id);
        header('Content-Type: application/json'); 
        echo json_encode($get);
    }

    function get_penjualan_pengambilan_data_by_qty(){
        $penjualan_id = $this->input->post('penjualan_id');
        $get = $this->tr_model->get_penjualan_pengambilan_by_qty($penjualan_id);
        header('Content-Type: application/json'); 
        $idx = 0;
        $args = array();
        foreach ($get as $row) {
            $args[$idx] = array(
                'penjualan_detail_id' => $row->penjualan_detail_id,
                'qty' => json_decode($row->qty) ,
                'jumlah_roll_ambil' => $row->jumlah_roll_ambil,
                'pengambilan_list' => json_decode($row->pengambilan_list) ,
                );
            $idx++;
        }
        echo json_encode($args, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }

    function penjualan_pengambilan_update(){
        $ini = $this->input;
        //surat jalan id
        $id = $ini->post('id'); 
        $detail_id_list = array();
        $pengambilan_data = array();
        $barang_list = array();
        $penjualan_id = $ini->post('penjualan_id');
        $status_ambil = $ini->post('statusAmbil');
        // print_r($ini->post());
        $data = array(
            'tanggal' =>  date('Y-m-d'),
            'penjualan_id' => $penjualan_id,
            'user_id' => $ini->post('user_id'),
            'keterangan' => $ini->post('keterangan')
            );

        $data_jual = array(
            'status_ambil' => $status_ambil );
        $this->common_model->db_update("nd_penjualan", $data_jual,'id', $penjualan_id);
        if ($id == '') {
            $id = $this->common_model->db_insert("nd_penjualan_pengambilan", $data);
        }else{
            $this->common_model->db_update("nd_penjualan_pengambilan", $data,'id', $id);
        }
        $pengambilan_data['id'] = $id;
        $pengambilan_data['penjualan_id'] = $penjualan_id;

        // print_r($ini->post("barang_list"));
        // wipe data before
        $get_detail_data = $this->common_model->db_select("nd_penjualan_pengambilan_detail where penjualan_pengambilan_id = $id");
        $detail_id_list = [];
        foreach ($get_detail_data as $row) {
            array_push($detail_id_list, $row->id);
        }

        $detail_id_del = implode(',', $detail_id_list);
        if ($detail_id_del != '') {
            $this->common_model->db_free_query_superadmin("DELETE from nd_penjualan_pengambilan_qty WHERE penjualan_pengambilan_detail_id in (".$detail_id_del.")");
        }
        $this->common_model->db_delete("nd_penjualan_pengambilan_detail", 'penjualan_pengambilan_id', $id);

        //======================barang qty==============================
        foreach ($ini->post('barang_list') as $key => $value) {
            $data_qty = array();
            $data_detail = array(
                'penjualan_pengambilan_id' => $id,
                'penjualan_detail_id' => $value['penjualan_detail_id']
                );
            $detail_id = $this->common_model->db_insert("nd_penjualan_pengambilan_detail", $data_detail);
            
            foreach ($value['qtyList'] as $idx => $isi) {
                if ($isi['qty'] != 0 && $isi['jumlah_roll'] != 0) {
                    $data_qty[$idx] = array(
                        'penjualan_pengambilan_detail_id'=>$detail_id,
                        'qty' => $isi['qty'] ,
                        'jumlah_roll' => $isi['jumlah_roll'] );
                }
            }

            $barang_list['detail_id'] = $detail_id;
            $barang_list['penjualan_detail_id'] = $value['penjualan_detail_id'];
            $barang_list['qtyList'] = $data_qty;

            $this->common_model->db_insert_batch("nd_penjualan_pengambilan_qty",$data_qty);
            // array_push($detail_id_list, $detail_id);
        }
        // echo $id."||".implode("??", $detail_id_list);
        $pengambilan_data['barang_list'] = $barang_list;
        $pengambilan_data['statusAmbil'] = $status_ambil;
        // echo json_encode($pengambilan_data);

        $get = $this->tr_model->get_penjualan_pengambilan_by_qty($penjualan_id);
        header('Content-Type: application/json'); 
        $idx = 0;
        $args = array();
        foreach ($get as $row) {
            $args[$idx] = array(
                'penjualan_detail_id' => $row->penjualan_detail_id,
                'qty' => json_decode($row->qty) ,
                'jumlah_roll_ambil' => $row->jumlah_roll_ambil,
                'pengambilan_list' => json_decode($row->pengambilan_list) ,
                );
            $idx++;
        }
        echo json_encode($args, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }

    function surat_jalan_insert(){
        // tipe- tipe surat jalan
        // 1. surat jalan penjualan
        // 2. retur

        // print_r($this->input->post());
        $surat_jalan_id = $this->input->post('surat_jalan_id');
        $surat_jalan_type_id = $this->input->post('surat_jalan_type_id');
        $alamat_id = $this->input->post('alamat_id');
        $tanggal = is_date_formatter($this->input->post('tanggal'));
        $tahun = date("Y", strtotime($tanggal));
        $transaction_id = $this->input->post('transaction_id');
        $no = 1;
        foreach ($this->common_model->db_select("nd_surat_jalan where YEAR(tanggal)='".$tahun."' order by no desc limit 1") as $row) {
            $no = $row->no+1;
        }

        if ($surat_jalan_type_id == 1) {
            $get = $this->common_model->db_select('nd_penjualan where id='.$transaction_id);
            foreach ($get as $row) {
                $customer_id = $row->customer_id;
            }
            if ($alamat_id == 0) {
                foreach ($this->common_model->db_select('nd_customer where id='.$customer_id) as $row) {
                    $alamat_log = $row->alamat.
                        ($row->blok!=''?' blok.'.$row->blok : '').
                        ($row->no!=''?' NO.'.$row->no : '').
                        ($row->rt!='000'?' RT.'.$row->rt : '').
                        ($row->rw!='000'?' RW.'.$row->blok : '').
                        ($row->kecamatan!=''?' Kec.'.$row->kecamatan : '').
                        ($row->kelurahan!=''?' Kel.'.$row->kelurahan : '').
                        ($row->kota!=''?' '.$row->kota : '').
                        ($row->provinsi!=''?' '.$row->provinsi : '')." Indonesia".
                        ($row->kode_pos!='00000'?' '.$row->kode_pos : '');

                }
            }else{
                foreach ($this->common_model->db_select("nd_customer_alamat_kirim where id=".$alamat_id) as $row) {
                    $alamat_log = $row->alamat;
                }
            }
        }elseif ($surat_jalan_type_id == 2) {
            
        }


        $data = array(
            'tanggal' => $tanggal,
            'transaction_id' => $transaction_id,
            'surat_jalan_type_id' => $this->input->post('surat_jalan_type_id'),
            'user_id' => is_user_id(),
            'no' => $no,
            'alamat_id' => $alamat_id,
            'alamat_log' => $alamat_log
            );

        // print_r($data);

        if ($surat_jalan_id == '') {
            $result_id = $this->common_model->db_insert("nd_surat_jalan",$data);
            $surat_jalan_id = $result_id;
        }else{
            $this->common_model->db_update("nd_surat_jalan",$data,'id', $surat_jalan_id);
            $detail_data = $this->common_model->db_select("nd_surat_jalan_detail where surat_jalan_id=".$surat_jalan_id);
            foreach ($detail_data as $row) {
                $detail_sj_id[$row->transaction_detail_id] = $row->id;
            }
        }

        foreach ($this->input->post('qty') as $key => $value) {
            if ($value != '') {
                $qty_data = explode("||", $value);
                $transaction_detail_id = $qty_data[0];

                foreach ($this->common_model->db_select('nd_penjualan_detail where id='.$transaction_detail_id) as $row) {
                    $barang_id = $row->barang_id;
                    $warna_id = $row->warna_id;
                }

                $qty_break = explode("--", $qty_data[1]);
                foreach ($qty_break as $key => $value) {
                    $qty_get = explode('??', $value);
                }

                $dt = array(
                    'surat_jalan_id' => $surat_jalan_id,
                    'transaction_detail_id' => $transaction_detail_id,
                    'barang_id' => $barang_id,
                    'warna_id' => $warna_id,
                    'user_id' => is_user_id()
                    );

                if (!isset($detail_sj_id[$transaction_detail_id])) {
                    $detail_sj_id[$transaction_detail_id] = $this->common_model->db_insert("nd_surat_jalan_detail",$dt);
                }else{
                    $this->common_model->db_update("nd_surat_jalan_detail",$dt, 'id', $detail_sj_id[$transaction_detail_id]);
                    $this->common_model->db_delete("nd_surat_jalan_qty","surat_jalan_detail_id", $detail_sj_id[$transaction_detail_id]);
                }
                
                $dt_qty = array();
                $qty_break = explode("--", $qty_data[1]);
                foreach ($qty_break as $key => $value) {
                    $qty_get = explode('??', $value);
                        $dt_qty[$key] = array(
                        'surat_jalan_detail_id' => $detail_sj_id[$transaction_detail_id] ,
                        'qty'=>(float)$qty_get[0],
                        'jumlah_roll'=> $qty_get[1] );
                }

                $this->common_model->db_insert_batch("nd_surat_jalan_qty", $dt_qty);
            }

        }
        if ($surat_jalan_type_id == 1) {
            redirect(is_setting_link('transaction/penjualan_list_detail_2').'?id=6201');
        }


    }

    function surat_jalan_insert_ajax(){
        // tipe- tipe surat jalan
        // 1. surat jalan penjualan
        // 2. retur

        // print_r($this->input->post());
        $id = $this->input->post('id');
        $surat_jalan_type_id = $this->input->post('surat_jalan_type_id');
        $alamat_id = $this->input->post('alamat_id');
        $keterangan = $this->input->post('keterangan');
        $tanggal = is_date_formatter($this->input->post('tanggal'));
        $tahun = date("Y", strtotime($tanggal));
        $transaction_id = $this->input->post('transaction_id');

        $detail_id_list = array();
        $pengambilan_data = array();
        $barang_list = array();
        $penjualan_id = $this->input->post('penjualan_id');
        $status_ambil = $this->input->post('statusAmbil');

        $no = 1;
        foreach ($this->common_model->db_select("nd_surat_jalan where YEAR(tanggal)='".$tahun."' order by no desc limit 1") as $row) {
            $no = $row->no+1;
        }

        if ($surat_jalan_type_id == 1) {
            $alamat_log = $this->input->post('alamat_log');
        }elseif ($surat_jalan_type_id == 2) {
            
        }


        $data = array(
            'tanggal' => $tanggal,
            'transaction_id' => $transaction_id,
            'surat_jalan_type_id' => $this->input->post('surat_jalan_type_id'),
            'user_id' => is_user_id(),
            'no' => $no,
            'alamat_id' => $alamat_id,
            'alamat_log' => $alamat_log
            );

        // print_r($data);

        if ($id == '') {
            $result_id = $this->common_model->db_insert("nd_surat_jalan",$data);
            $id = $result_id;
        }else{
            $this->common_model->db_update("nd_surat_jalan",$data,'id', $id);
            // $detail_data = $this->common_model->db_select("nd_surat_jalan_detail where surat_jalan_id=".$surat_jalan_id);
            // foreach ($detail_data as $row) {
            //     $this->common_model->db_delete('nd_surat_jalan_qty','surat_jalan_detail_id',$row->id);
            // }
            // $this->common_model->db_delete("nd_surat_jalan_detail",'surat_jalan_id',$surat_jalan_id);
        }

        foreach ($this->input->post('barang_list') as $key => $value) {
            $data_qty = array();
            $detail_id = $value['penjualan_pengambilan_detail_id'];
            $data_detail = array(
                'surat_jalan_id' => $id,
                'transaction_detail_id' => $value['penjualan_detail_id']
                );
            if ($detail_id == '') {
                $detail_id = $this->common_model->db_insert("nd_surat_jalan_detail", $data_detail);
            }else{
                $this->common_model->db_update("nd_surat_jalan_detail", $data_detail,'id', $detail_id);
            }

            foreach ($value['qtyList'] as $idx => $isi) {
                if ($isi['qty'] != 0 && $isi['jumlah_roll'] != 0) {
                    $data_qty[$idx] = array(
                        'surat_jalan_detail_id'=>$detail_id,
                        'qty' => $isi['qty'] ,
                        'jumlah_roll' => $isi['jumlah_roll'] );

                    $data_qty_retur[$idx] = array(
                        'penjualan_pengambilan_detail_id'=>$detail_id,
                        'qty' => $isi['qty'] ,
                        'jumlah_roll' => $isi['jumlah_roll'] );
                }
            }

            $barang_list['detail_id'] = $detail_id;
            $barang_list['penjualan_detail_id'] = $value['penjualan_detail_id'];
            $barang_list['qtyList'] = $data_qty_retur;

            $this->common_model->db_delete("nd_surat_jalan_qty", 'surat_jalan_detail_id', $detail_id);
            $this->common_model->db_insert_batch("nd_surat_jalan_qty",$data_qty);
            // array_push($detail_id_list, $detail_id);
        }

        $pengambilan_data['barang_list'] = $barang_list;
        $pengambilan_data['statusAmbil'] = $status_ambil;
        // echo json_encode($pengambilan_data);

        /*$get = $this->tr_model->get_penjualan_pengiriman_by_qty($penjualan_id);
        header('Content-Type: application/json'); 
        $idx = 0;
        $args = array();
        foreach ($get as $row) {
            $args[$idx] = array(
                'penjualan_detail_id' => $row->transaction_detail_id,
                'qty' => json_decode($row->qty) ,
                'jumlah_roll_ambil' => $row->jumlah_roll_ambil,
                'pengambilan_list' => json_decode($row->pengriman_list) ,
                );
            $idx++;
        }
        echo json_encode($args, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);*/
        echo $id;       


    }

    function pengambilan_remove()
    {
        $id = $this->input->post('id');
        $detail_id = [];
        foreach ($this->common_model->db_select("nd_penjualan_pengambilan_detail where penjualan_pengambilan_id=$id") as $row) {
            array_push($detail_id, $row->id);
        }

        $this->common_model->db_free_query_superadmin("DELETE from nd_penjualan_pengambilan_qty WHERE penjualan_pengambilan_detail_id in (".implode(",", $detail_id).")");

        $this->common_model->db_delete("nd_penjualan_pengambilan_detail","penjualan_pengambilan_id", $id);

        $this->common_model->db_delete("nd_penjualan_pengambilan","id", $id);
        echo "OK";
    }

//=======================================print========================================


	function test_print(){

		echo "'\x1B' '\x45' '\x0D', ".// bold on
		"'\x1B' '\x61' '\x30', ".// left align
		"FA. CHEMICAL '\x0A',".
		   	"TAMIM NO. 53 BANDUNG  '\x0A'";
	}

	function penjualan_print(){

        $penjualan_id = $this->input->get('penjualan_id');
        // echo $penjualan_id;
        $nama_customer = '';
        $tanggal = '';
        $no_faktur = '';
        $toko_id = 1;
        $data = '';

        $alamat1 = '';
        $alamat2 = '';
        
        $data['data_penjualan'] = $this->tr_model->get_data_penjualan($penjualan_id);
        // $sj_list = $this->tr_model->get_surat_jalan_data_by_penjualan($penjualan_id);
        $sj_list = array();
        $sj_baris = [];
        $sj_pre = [];
        $sj_pre[0]=1;
        foreach ($sj_list as $row) {
            array_push($sj_baris, $row->no_surat_jalan);
            array_push($sj_pre, "");
            $sj_pre[0]="SJ  ";
        }
        // print_r($data['sj_list']);
        foreach ($data['data_penjualan'] as $row) {
            $toko_id = $row->toko_id;
            $po_number = $row->po_number;
            $customer_id = $row->customer_id;

            $alamat_keterangan = $row->alamat_bon;
            $cek_alamat = preg_split('/\r\n|[\r\n]/', $alamat_keterangan);
            $array_rep = ["\n","\r"];
            $alamat_keterangan = str_replace($array_rep, ' ', $alamat_keterangan);

            $alamat1 = substr(strtoupper(trim($alamat_keterangan)), 0,40);
            $alamat2 = substr(strtoupper(trim($alamat_keterangan)), 40);
            $last_1 = substr($alamat1, -1,1);
            $last_2 = substr($alamat2, 0,1);

            $positions = array();
            $pos = -1;
            while (($pos = strpos(trim($alamat_keterangan)," ", $pos+1 )) !== false) {
                $positions[] = $pos;
            }

            $max = 40;
            if ($last_1 != '' && $last_2 != '') {
                $posisi =array_filter(array_reverse($positions),
                    function($value) use ($max) {
                        return $value <= $max;
                    });

                $posisi = array_values($posisi);

                $alamat1 = substr(strtoupper(trim($alamat_keterangan)), 0,$posisi[0]);
                $alamat2 = substr(strtoupper(trim($alamat_keterangan)), $posisi[0]);
            }

            $baris3 = [];
            // echo strlen($alamat2);
            if ($alamat2 == '' || (strlen($alamat2) + strlen($row->kecamatan)) <= 38) {
                
                if ($alamat2 != '') {
                    $alamat2 .= ($row->kecamatan != '' && $row->kecamatan != '-' ? ", ".$row->kecamatan : '');
                    
                    if (strlen($alamat2) +  strlen($row->kelurahan) <= 38) {
                        $alamat2 .= ($row->kelurahan != '' && $row->kelurahan != '-' ? ", ".$row->kelurahan : '');
                        $alamat3 = $row->kota;
                    }else{
                        $alamat3 = ($row->kelurahan != '' && $row->kelurahan != '-' ? $row->kelurahan.", ".$row->kota : $row->kota);
                    }
                }else{
                    $alamat2 = $row->kecamatan.($row->kelurahan != '' && $row->kelurahan != '-' ? ", ".$row->kelurahan : '');;
                    $alamat3 = $row->kota;
                }

                if ($alamat2 == '') {
                    $alamat2 = $alamat3;
                    $alamat3 = '';
                }

            }else if(strlen($alamat2) > 38){
                $alamat2_1 = substr(strtoupper(trim($alamat2)), 0,38);
                $alamat2_2 = substr(strtoupper(trim($alamat2)), 40);
                $last_21 = substr($alamat2_1, -1,1);
                $last_22 = substr($alamat2_2, 0,1);

                $positions = array();
                $pos = -1;
                while (($pos = strpos(trim($alamat2)," ", $pos+1 )) !== false) {
                    $positions[] = $pos;
                }

                $max = 40;
                if ($last_21 != '' && $last_22 != '') {
                    $posisi =array_filter(array_reverse($positions),
                        function($value) use ($max) {
                            return $value <= $max;
                        });

                    $posisi = array_values($posisi);

                    $alamat2_1 = substr(strtoupper(trim($alamat2)), 0,$posisi[0]);
                    $alamat2_2 = substr(strtoupper(trim($alamat2)), $posisi[0]);
                }

                $alamat2 = $alamat2_1;

                if(strlen($alamat2_2) + (strlen($row->kecamatan) + strlen($row->kelurahan) + strlen($row->kota)) <= 35) {
                    if ($row->kecamatan != '' && $row->kecamatan != '' ) {
                        array_push($baris3, $row->kecamatan);
                    }

                    if ($row->kelurahan != '' && $row->kelurahan != '' ) {
                        array_push($baris3, $row->kelurahan);
                    }

                    if ($row->kota != '' && $row->kota != '' ) {
                        array_push($baris3, $row->kota);
                    }
                    $alamat3 = implode(", ", $baris3);
                    $alamat3 = $alamat2_2.','.$alamat3;
                }else{
                    $alamat3 = $alamat2_2.', '.$row->kota;
                }

            }
        }

        
        $st = count($sj_baris);
        $data['sj_inline'] = implode(", ", $sj_baris);
        for ($i=$st; $i < 4  ; $i++) { 
            if ($i == $st && $po_number != '' && $po_number != 0) {
                $sj_pre[$i] = "PO";
                $sj_baris[$i] = $po_number;
            }else{
                $sj_pre[$i] = "";
                $sj_baris[$i] = '';
            }
        }

        if ($st == 0) {
            $sj_pre[0] = "PO";
            $sj_baris[0] = $po_number;
        }


        $data['sj_baris'] = $sj_baris;
        $data['sj_pre'] = $sj_pre;
        $data['alamat1'] = $alamat1;
        $data['alamat2'] = $alamat2;
        $data['alamat3'] = $alamat3;
        $font_alamat = 9;
        if (strlen($alamat1) > 40 || strlen($alamat2) > 40 || strlen($alamat3) > 40) {
            $font_alamat = 9;
        }

        $data['font_alamat'] = $font_alamat;

        $data['toko_data'] = $this->common_model->db_select('nd_toko where id=1');
        $data['data_pembayaran'] = $this->tr_model->get_data_pembayaran($penjualan_id);
        $data['data_penjualan_detail'] = $this->tr_model->get_data_penjualan_detail_group($penjualan_id);
        $data['data_penjualan_detail_group'] = $this->tr_model->get_data_penjualan_detail_by_barang($penjualan_id);
        
        $this->load->library('fpdf17/fpdf_css');
        $this->load->library('fpdf17/fpdf');

        $total_baris_faktur = 0;

        foreach ($data['data_penjualan_detail'] as $row) {
            $total_baris_faktur++;
        }

        $total_baris_warna = 0;
        foreach ($data['data_penjualan_detail_group'] as $row) {
            $nama_warna = explode('??', $row->nama_warna);
            $roll_qty = explode('??', $row->roll_qty);
            $j_roll = explode("??", $row->jumlah_roll);
            
            foreach ($nama_warna as $key => $value) {
                $total_baris_warna += ceil($j_roll[$key]/10);
                if (is_posisi_id()==1) {
                    // echo $total_baris_warna."<br/>";
                    # code...
                }
            }
            
        }

        $data['total_baris_faktur'] = $total_baris_faktur;
        $data['total_baris_warna'] = $total_baris_warna;


        // print_r($sj_baris);
        $type = 3;
        // echo  $this->input->get('type');
        $data['tFungsi'] = function($tanggal) {
            $tanggal = str_replace("JANUARY", "JANUARI", strtoupper($tanggal));
            $tanggal = str_replace("FEBRUARY", "FEBRUARI", strtoupper($tanggal));
            $tanggal = str_replace("MARCH", "MARET", strtoupper($tanggal));
            $tanggal = str_replace("APRIL", "APRIL", strtoupper($tanggal));

            $tanggal = str_replace("MAY", "MEI", strtoupper($tanggal));
            $tanggal = str_replace("JUNE", "JUNI", strtoupper($tanggal));
            $tanggal = str_replace("JULY", "JULI", strtoupper($tanggal));
            $tanggal = str_replace("AUGUST", "AGUSTUS", strtoupper($tanggal));

            $tanggal = str_replace("SEPTEMBER", "SEPTEMBER", strtoupper($tanggal));
            $tanggal = str_replace("OCTOBER", "OKTOBER", strtoupper($tanggal));
            $tanggal = str_replace("NOVEMBER", "NOPEMBER", strtoupper($tanggal));
            $tanggal = str_replace("DECEMBER", "DESEMBER", strtoupper($tanggal));

            return $tanggal;

        };
        $print_sequence = [];
        if ($this->input->get('type') !='' ) {
            $type=$this->input->get('type');
            // echo $type;
                $data['tipe'] = $type;
                if ($type != 'sj' && is_posisi_id() != 1 ){
                    array_push($print_sequence, "print_faktur_header");
                }else if($type != 'sj'){
                    array_push($print_sequence, "print_faktur_header");
                }
            if ($type=='1') {
                // array_push($print_sequence, "print_faktur_header");
                // array_push($print_sequence, "print_faktur_header3");
                array_push($print_sequence, "print_faktur");
                array_push($print_sequence, "print_faktur_footer");
                array_push($print_sequence, "print_faktur_closing");
                $data['print_sequence'] = $print_sequence;
                $this->load->view('admin/transaction/print/faktur/print_generate',$data);
            }else if ($type=='1a') {
                // array_push($print_sequence, "print_faktur_header_baru");
                array_push($print_sequence, "print_faktur");
                array_push($print_sequence, "print_faktur_footer");
                array_push($print_sequence, "print_faktur_closing");
                $data['print_sequence'] = $print_sequence;
                $this->load->view('admin/transaction/print/faktur/print_generate',$data);
            }else if ($type=='1b') {
                // array_push($print_sequence, "print_faktur_header3");
                array_push($print_sequence, "print_faktur");
                array_push($print_sequence, "print_faktur_footer");
                array_push($print_sequence, "print_faktur_closing");
                $data['print_sequence'] = $print_sequence;
                $this->load->view('admin/transaction/print/faktur/print_generate',$data);
            }else if ($type==2) {
                // array_push($print_sequence, "print_faktur_header_baru");
                array_push($print_sequence, "print_detail");
                array_push($print_sequence, "print_faktur_footer");
                array_push($print_sequence, "print_faktur_closing");
                $data['print_sequence'] = $print_sequence;
                $this->load->view('admin/transaction/print/faktur/print_generate',$data);
            }else if ($type==3) {
                // array_push($print_sequence, "print_faktur_header_baru");
                array_push($print_sequence, "print_faktur");

                if ($data['total_baris_warna'] + $data['total_baris_faktur'] > 14) {
                    array_push($print_sequence, "print_faktur_footer");
                    array_push($print_sequence, "print_detail");
                }else{
                    array_push($print_sequence, "print_detail");    
                    array_push($print_sequence, "print_faktur_footer");
                }
                array_push($print_sequence, "print_faktur_closing");
                $data['print_sequence'] = $print_sequence;
                
                // $this->load->view('admin/transaction/print/faktur/print_generate',$data);
                if (is_posisi_id() == 1) {
                    // echo 'y<hr/>';
                    // echo $data['total_baris_warna'].' <br/> '.$data['total_baris_faktur'];
                    $this->load->view('admin/transaction/print/faktur/print_generate',$data);
                }else{       
                    $this->load->view('admin/transaction/print/faktur/print_generate',$data);
                }
                // $this->load->view('admin/transaction/print/faktur/print_faktur_detail',$data);
            }else if ($type==4) {
                $this->surat_jalan_print(8);
            }elseif ($type==99) {

                $surat_jalan_id = $this->input->get('surat_jalan_id');
                // $data['data_surat_jalan'] =$this->common_model->db_select("nd_surat_jalan where id=".$surat_jalan_id);
                $data['data_customer'] = $this->common_model->db_select("nd_customer where id = ".$customer_id);
                // $data['data_barang'] = $this->tr_model->get_data_barang_pengiriman($surat_jalan_id);
                // $data['data_barang_qty'] = $this->tr_model->get_data_barang_pengiriman_detail($surat_jalan_id);
                
                $data['data_surat_jalan'] = array();
                $data['data_barang'] = array();
                $data['data_barang_qty'] = array();
                $this->load->view('admin/transaction/print/faktur/print_faktur_custom',$data);

            }else if($type == 'sj'){
                // echo $type;
                $alamat_kirim_id = $this->input->get('alamat_kirim_id');
                $data['alamat_kirim'] = array();
                if ($alamat_kirim_id != 0 ) {
                    $data['alamat_kirim'] = $this->common_model->db_select("nd_customer_alamat_kirim WHERE id=".$alamat_kirim_id); 
                }
                $tipe_sj = 1;
                if ($this->input->get('tipe_sj') != '') {
                    $tipe_sj = $this->input->get('tipe_sj');
                }

                if (is_posisi_id() != 1) {
                    array_push($print_sequence, "print_surat_jalan_header");
                }else{
                    array_push($print_sequence, "print_surat_jalan_header");
                    // array_push($print_sequence, "print_surat_jalan_header_baru");
                }
                if ($tipe_sj == 1) {
                    array_push($print_sequence, "print_surat_jalan_harga");
                }else{
                    array_push($print_sequence, "print_surat_jalan_noharga");
                }

                if (is_posisi_id() != 1) {
                    array_push($print_sequence, "print_surat_jalan_footer");
                }else{
                    array_push($print_sequence, "print_surat_jalan_footer");
                    // array_push($print_sequence, "print_surat_jalan_footer_baru");    
                }

                if (is_posisi_id() != 1) {
                    array_push($print_sequence, "print_packing_list_header");
                }else{
                    array_push($print_sequence, "print_packing_list_header");
                    // array_push($print_sequence, "print_packing_list_header_baru");                    
                }
                
                array_push($print_sequence, "print_detail");


                array_push($print_sequence, "print_surat_jalan_closing");
                $data['print_sequence'] = $print_sequence;
                $this->load->view('admin/transaction/print/surat_jalan/print_generate',$data);
                // $this->load->view('admin/transaction/print/surat_jalan/print_surat_jalan_pdf',$data);
            }else{
                echo $type;
            }
        }else{
            echo $this->input->get('type');
        }
    }

	function penjualan_print_langsung(){

		$this->load->library('blade');

		$this->blade->set('foo', 'bar')
				->set('an_array', array(1, 2, 3, 4))
				->append('an_array', 5)
				->set_data(array('more' => 'data', 'other' => 'data'))
				->render('test', array('message' => 'Hello World!'));

		

		// $this->load->view('admin/transaction/penjualan_print_2',$data);
		
		
	}

	function penjualan_detail_print(){

		$penjualan_id = $this->input->get('penjualan_id');
		
		
		$data['data_penjualan'] = $this->tr_model->get_data_penjualan($penjualan_id);
		$data['toko_data'] = $this->common_model->db_select('nd_toko where id=1');
		
		$data['data_penjualan_detail'] = $this->tr_model->get_data_penjualan_detail_group($penjualan_id);
		$data['data_penjualan_detail_group'] = $this->tr_model->get_data_penjualan_detail_by_barang($penjualan_id);

		$this->load->library('fpdf17/fpdf_css');
		$this->load->library('fpdf17/fpdf');

		$this->load->view('admin/transaction/penjualan_detail_print_3',$data);
		
	}

	function penjualan_print_kombinasi(){

		$penjualan_id = $this->input->get('penjualan_id');
		$nama_customer = '';
		$tanggal = '';
		$no_faktur = '';
		
		$data['data_penjualan_detail'] = $this->tr_model->get_data_penjualan_detail_group($penjualan_id);
		$data['toko_data'] = $this->common_model->db_select('nd_toko where id=1');
		$data['data_pembayaran'] = $this->tr_model->get_data_pembayaran($penjualan_id);
		$data['data_penjualan'] = $this->tr_model->get_data_penjualan($penjualan_id);
		// $data['data_penjualan_detail'] = $this->tr_model->get_data_penjualan_detail($penjualan_id);
		$data['data_penjualan_detail_group'] = $this->tr_model->get_data_penjualan_detail_by_barang($penjualan_id);

		$this->load->library('fpdf17/fpdf_css');
		$this->load->library('fpdf17/fpdf');

		$this->load->view('admin/transaction/penjualan_kombinasi_print_4',$data);
		
	}

	function penjualan_sj_print(){

		$penjualan_id = $this->input->get('penjualan_id');
		$data['harga_status'] = $this->input->get('harga');
		$nama_customer = '';
		$tanggal = '';
		$no_faktur = '';
		
		$data['data_penjualan'] = $this->tr_model->get_data_penjualan($penjualan_id);
		// $data['data_penjualan_detail'] = $this->tr_model->get_data_penjualan_detail($penjualan_id);
		$data['toko_data'] = $this->common_model->db_select('nd_toko where id=1');
		$data['data_penjualan_detail'] = $this->tr_model->get_data_penjualan_detail_group($penjualan_id);
		$data['data_penjualan_detail_group'] = $this->tr_model->get_data_penjualan_detail_by_barang($penjualan_id);

		$this->load->library('fpdf17/fpdf_css');
		$this->load->library('fpdf17/fpdf');

		$this->load->view('admin/transaction/penjualan_sj_print',$data);
		
	}

//===================================pengeluaran_stok_lain=============================================
    function pengeluaran_stok_lain_list(){
        $menu = is_get_url($this->uri->segment(1)) ;
        $status_aktif = 1;
        if ($this->input->get('status_aktif')) {
            $status_aktif = $this->input->get('status_aktif');
        }

        $data = array(
            'content' =>'admin/transaction/pengeluaran_stok_lain_list',
            'breadcrumb_title' => 'Transaction',
            'breadcrumb_small' => 'Daftar pengeluaran stok lain',
            'nama_menu' => $menu[0],
            'nama_submenu' => $menu[1],
            'common_data'=> $this->data,
            'status_aktif' => $status_aktif,
            'data_isi'=> $this->data );


        $data['user_id'] = is_user_id();
        $data['pengeluaran_stok_lain_list'] = $this->tr_model->get_pengeluaran_stok_lain_list(); 
        $this->load->view('admin/template',$data);
    }

    function pengeluaran_stok_lain_list_insert(){
        $tanggal = is_date_formatter($this->input->post('tanggal'));
        $tahun = date('Y', strtotime($tanggal));
        
        $data = array(
            'toko_id' => 1,
            'tanggal' => $tanggal,
            'user_id' => is_user_id(), 
            'keterangan' => $this->input->post('keterangan'),
            );

        // print_r($data);
        $result_id = $this->common_model->db_insert('nd_pengeluaran_stok_lain',$data);
        redirect($this->setting_link('transaction/pengeluaran_stok_lain_list_detail').'/?id='.$result_id);
    }

    function pengeluaran_stok_lain_list_update(){
        $tanggal = is_date_formatter($this->input->post('tanggal'));
        $tahun = date('Y', strtotime($tanggal));
        $pengeluaran_stok_lain_id = $this->input->post('pengeluaran_stok_lain_id');

        $data = array(
            'toko_id' => 1,
            'tanggal' => $tanggal,
            'user_id' => is_user_id(), 
            'keterangan' => $this->input->post('keterangan'),
            );

        $this->common_model->db_update('nd_pengeluaran_stok_lain',$data,'id',$pengeluaran_stok_lain_id);

        redirect($this->setting_link('transaction/pengeluaran_stok_lain_list_detail').'/?id='.$pengeluaran_stok_lain_id);
    }

    function pengeluaran_stok_lain_remove(){
        $id = $this->input->post('id');
        $this->common_model->db_delete("nd_pengeluaran_stok_lain","id", $id);
        foreach ($this->common_model->db_select('nd_pengeluaran_stok_lain_detail where pengeluaran_stok_lain_id='.$id) as $row) {
            $this->common_model->db_delete("nd_pengeluaran_stok_lain_qty_detail","pengeluaran_stok_lain_detail_id", $row->id);
        }
        $this->common_model->db_delete("nd_pengeluaran_stok_lain_detail","pengeluaran_stok_lain_id", $id);

        echo "OK";

    }

    function pengeluaran_stok_lain_list_detail(){
        $menu = is_get_url($this->uri->segment(1)) ;
        // $id = $this->uri->segment(2);
        $id = $this->input->get('id');

        $data = array(
            'content' =>'admin/transaction/pengeluaran_stok_lain_list_detail',
            'breadcrumb_title' => 'Transaction',
            'breadcrumb_small' => 'Formulir pengeluaran_stok_lain',
            'nama_menu' => $menu[0],
            'nama_submenu' => $menu[1],
            'common_data'=> $this->data,
            'data_isi'=> $this->data,
            'pembayaran_type' => $this->common_model->db_select('nd_pembayaran_type'),
            'printer_list' => $this->common_model->db_select('nd_printer_list')
            );

        if ($id != '') {
            $pengeluaran_stok_lain_data = $this->common_model->db_select("nd_pengeluaran_stok_lain where id=".$id);
            // print_r($pengeluaran_stok_lain_data);
            $data['pengeluaran_stok_lain_data'] = $pengeluaran_stok_lain_data;
            foreach ($pengeluaran_stok_lain_data as $row) {
                $toko_id = $row->toko_id;
            }
            $data['pengeluaran_stok_lain_detail'] = $this->tr_model->get_data_pengeluaran_stok_lain_detail($id);
            $total_jual = 0;
            foreach ($data['pengeluaran_stok_lain_detail'] as $row) {
                $total_jual += $row->qty * $row->harga_jual;
            }
            $data['total_jual'] = $total_jual;
            $result = $this->common_model->db_select('nd_pembayaran_pengeluaran_stok_lain where pengeluaran_stok_lain_id='.$id." AND amount != 0");
            foreach ($result as $row) {
                $data['pembayaran_pengeluaran_stok_lain'][$row->pembayaran_type_id] = $row->amount; 
            }

            $data['pengeluaran_stok_lain_print'] = $this->tr_model->get_data_pengeluaran_stok_lain_detail_group($id);
            $data['data_pembayaran'] = $this->tr_model->get_data_pembayaran_pengeluaran_stok_lain($id);
            $data['data_pengeluaran_stok_lain_detail_group'] = $this->tr_model->get_data_pengeluaran_stok_lain_detail_by_barang($id);
            $data['data_toko'] = $this->common_model->db_select('nd_toko where id = '.$toko_id);


        }else{
            $data['pengeluaran_stok_lain_data'] = array();
            $data['pengeluaran_stok_lain_detail'] = array();
            $data['total_jual'] = 0;
            $data['pembayaran_pengeluaran_stok_lain'] = array();
            $data['saldo_awal'] = 0;
            $data['pengeluaran_stok_lain_print'] = array();
            $data['data_toko'] = array();

        }

        $this->load->view('admin/template',$data);
    }

    function pengeluaran_stok_lain_list_detail_insert(){
        // print_r($this->input->post());
        $ini = $this->input;
        $pengeluaran_stok_lain_id =  $ini->post('pengeluaran_stok_lain_id');
        $data = array(
            'pengeluaran_stok_lain_id' => $pengeluaran_stok_lain_id,
            'gudang_id' => $ini->post('gudang_id'),
            'barang_id' => $ini->post('barang_id'),
            'warna_id' => $ini->post('warna_id'),
            'harga_jual' => str_replace('.', '', $ini->post('harga_jual')) );

        $result_id = $this->common_model->db_insert('nd_pengeluaran_stok_lain_detail', $data);
        $i = 0;
        $qty_list = explode('--', $ini->post('rekap_qty'));
        foreach ($qty_list as $key => $value) {
            $qty = explode('??', $value);
            $data_qty[$i] = array(
                'pengeluaran_stok_lain_detail_id' => $result_id,
                'qty' => $qty[0],
                'jumlah_roll' => $qty[1] );
            $i++;
        }

        $this->common_model->db_insert_batch('nd_pengeluaran_stok_lain_qty_detail',$data_qty);
        redirect($this->setting_link('transaction/pengeluaran_stok_lain_list_detail').'/?id='.$pengeluaran_stok_lain_id);
    }

    function pengeluaran_stok_lain_list_detail_update(){
        // print_r($this->input->post());
        $ini = $this->input;
        $pengeluaran_stok_lain_id =  $ini->post('pengeluaran_stok_lain_id');
        $pengeluaran_stok_lain_detail_id =  $ini->post('pengeluaran_stok_lain_detail_id');
        $data = array(
            'gudang_id' => $ini->post('gudang_id'),
            'barang_id' => $ini->post('barang_id'),
            'warna_id' => $ini->post('warna_id'),
            'harga_jual' => str_replace('.', '', $ini->post('harga_jual')) );

        $this->common_model->db_update('nd_pengeluaran_stok_lain_detail', $data,'id', $pengeluaran_stok_lain_detail_id);
        
        $get_qty = $this->common_model->db_select("nd_pengeluaran_stok_lain_qty_detail WHERE pengeluaran_stok_lain_detail_id=".$pengeluaran_stok_lain_detail_id);

        $max = count($get_qty);
        $idx_qty = 0;
        foreach ($get_qty as $row) {
            $qty_id[$idx_qty] = $row->id;
            // echo $qty_id[$idx_qty];
            $idx_qty++;
        }

        $i = 0;
        $qty_list = explode('--', $ini->post('rekap_qty'));
        $data_insert = array();
        foreach ($qty_list as $key => $value) {
            $qty = explode('??', $value);
            if ($qty[0] != 0 || $qty[1] != 0) {
                if ($i < $max ) {
                    $data_upd[$i] = array(
                        'pengeluaran_stok_lain_detail_id' => $pengeluaran_stok_lain_detail_id,
                        'qty' => $qty[0],
                        'jumlah_roll' => $qty[1] );
                    $this->common_model->db_update('nd_pengeluaran_stok_lain_qty_detail', $data_upd[$i],'id', $qty_id[$i]);
                }else{
                    $data_insert[$i] = array(
                        'pengeluaran_stok_lain_detail_id' => $pengeluaran_stok_lain_detail_id,
                        'qty' => $qty[0],
                        'jumlah_roll' => $qty[1] );
                }
                $i++;
            }
        }


        for ($j=$i; $j < $max ; $j++) { 
            $this->common_model->db_delete('nd_pengeluaran_stok_lain_qty_detail', 'id', $qty_id[$j]);
        }

        if (count($data_insert) > 0) {
            $this->common_model->db_insert_batch('nd_pengeluaran_stok_lain_qty_detail',$data_insert);
        }
        redirect(is_setting_link('transaction/pengeluaran_stok_lain_list_detail').'/?id='.$pengeluaran_stok_lain_id);
    }

    function pengeluaran_stok_lain_list_detail_remove(){
        $id = $this->input->post('id');
        $this->common_model->db_delete('nd_pengeluaran_stok_lain_detail','id',$id);
        $this->common_model->db_delete('nd_pengeluaran_stok_lain_qty_detail','pengeluaran_stok_lain_detail_id',$id);
        echo 'OK';

    }

    function pengeluaran_stok_lain_request_open(){
        $pengeluaran_stok_lain_id = $this->input->post('pengeluaran_stok_lain_id');
        $data = array(
                'closed_by' => null ,
                'closed_date' => null,
                'status' => 1 );

        $this->common_model->db_update('nd_pengeluaran_stok_lain', $data, 'id', $pengeluaran_stok_lain_id);
            
        redirect(is_setting_link('transaction/pengeluaran_stok_lain_list_detail').'/?id='.$pengeluaran_stok_lain_id);
    }

    function pengeluaran_stok_lain_list_close()
    {
        $id = $this->input->get('id');
        $no_faktur = 1;
        $tanggal = $this->input->get('tanggal');
        $tahun = date('Y', strtotime($tanggal));
        $revisi = 1;

        $get = $this->common_model->db_select("nd_pengeluaran_stok_lain where id=".$id);
        foreach ($get as $row) {
            $no_faktur = $row->no_faktur;
            $status_aktif = $row->status_aktif;
        }

        if ($no_faktur == '') {
            $data_get = $this->common_model->db_select("nd_pengeluaran_stok_lain where YEAR(tanggal)='".$tahun."' order by no_faktur desc limit 1 ");
            foreach ($data_get as $row) {
                $no_faktur = $row->no_faktur + 1;
            }
            // echo $id;
            $data = array(
                'closed_by' => is_user_id() ,
                'no_faktur' => $no_faktur,
                'closed_date' => date('Y-m-d H:i:s'),
                'status' => 0 );
            // print_r($data);
        }else{
            $data = array(
                'closed_by' => is_user_id() ,
                'closed_date' => date('Y-m-d H:i:s'),
                'status' => 0 );
        }

        if ($status_aktif != -1) {
            $this->common_model->db_update('nd_pengeluaran_stok_lain',$data,'id',$id);
        }
        redirect($this->setting_link('transaction/pengeluaran_stok_lain_list_detail').'/?id='.$id);
    }



//===================================Retur=============================================

	function retur_jual_list(){
		$menu = is_get_url($this->uri->segment(1)) ;

		$data = array(
			'content' =>'admin/transaction/retur_jual_list',
			'breadcrumb_title' => 'Transaction',
			'breadcrumb_small' => 'Daftar Retur',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data'=> $this->data,
			'data_isi'=> $this->data );


		$data['retur_list'] = $this->tr_model->get_retur_list(); 
		$this->load->view('admin/template',$data);
	}

	function penjualan_list_retur(){
		$id = $this->input->get('id');
		$data_jual = $this->common_model->db_select('nd_penjualan where id='.$id);

		$tanggal = is_date_formatter($this->input->post('tanggal'));
		$tahun = date('Y', strtotime($tanggal));
		$no_faktur = 1;
		$data_get = $this->common_model->db_select("nd_retur_jual where YEAR(tanggal)='".$tahun."' order by no_faktur desc limit 1 ");
		foreach ($data_get as $row) {
			$no_faktur = $row->no_faktur + 1;
		}

		foreach ($data_jual as $row) {
			$data = array(
				'retur_type_id' => $row->penjualan_type_id ,
                'penjualan_id' => $id,
				'tanggal' => date('Y-m-d'),
				'no_faktur' => $no_faktur,
				'customer_id' => $row->customer_id ,
				'nama_keterangan' => $this->input->post('nama_keterangan') ,
				'user_id' => is_user_id(),
				);

		}
		$result_id = $this->common_model->db_insert('nd_retur_jual', $data);

		redirect($this->setting_link('transaction/retur_jual_detail').'/?id='.$result_id);

	}

	function retur_jual_list_insert(){
		$tanggal = is_date_formatter($this->input->post('tanggal'));
		$tahun = date('Y', strtotime($tanggal));
		$no_faktur = 1;
		$data_get = $this->common_model->db_select("nd_retur_jual where YEAR(tanggal)='".$tahun."' order by no_faktur desc limit 1 ");
		foreach ($data_get as $row) {
			$no_faktur = ($row->no_faktur == 'null' ? 0 : $row->no_faktur) + 1;
		}

		$data = array(
            'retur_type_id' => $this->input->post('retur_type_id'),
			'toko_id' => ($this->input->post('toko_id') == 0 || $this->input->post('toko_id') == ''  ? 1 : $this->input->post('toko_id')),
			'tanggal' => $tanggal,
			'no_faktur' => $no_faktur,
			'customer_id' => ($this->input->post('customer_id') != '' ? $this->input->post('customer_id') : 0) ,
			'nama_keterangan' => $this->input->post('nama_keterangan') ,
			'user_id' => is_user_id(),
			);

		// print_r($data);

		$result_id = $this->common_model->db_insert('nd_retur_jual',$data);
		redirect($this->setting_link('transaction/retur_jual_detail').'/?id='.$result_id);

	}

	function retur_jual_list_update(){
        $penjualan_id = $this->input->post('penjualan_id');
        $tanggal = is_date_formatter($this->input->post('tanggal'));
		$retur_jual_id = $this->input->post('retur_jual_id');
        $data_jual = $this->common_model->db_select('nd_penjualan where id='.$penjualan_id);
        $no_faktur = 1;
        if ($retur_jual_id != '') {
            $retur_data = $this->common_model->db_select('nd_retur_jual where id='.$retur_jual_id);
            foreach ($retur_data as $row) {
                $no_faktur = $row->no_faktur;
            }
        }
        
        foreach ($data_jual as $row) {
            $data = array(
                'retur_type_id' => $row->penjualan_type_id ,
                'toko_id' => 1,
                'penjualan_id' => $row->id,
                'tanggal' => $tanggal,
                'no_faktur' => $no_faktur,
                'customer_id' => $row->customer_id ,
                'nama_keterangan' => $row->nama_keterangan ,
                'user_id' => is_user_id(),
                'status' => 1,
                'closed_by' => null,
                'closed_date' => null
                );
        }

        // print_r($data);

        if ($retur_jual_id == '') {
            // echo 'insert';
            $retur_jual_id = $this->common_model->db_insert('nd_retur_jual', $data);
        }else{
            // echo 'update';
            $this->common_model->db_update('nd_retur_jual',$data,'id', $retur_jual_id);
        }
		
		redirect($this->setting_link('transaction/retur_jual_detail').'/?id='.$retur_jual_id);

	}

    function retur_list_detail_remove(){
        $id = $this->input->post('id');
        $this->common_model->db_delete('nd_retur_jual_detail','id', $id);
        $this->common_model->db_delete('nd_retur_jual_qty','retur_jual_detail_id',$id);
        echo "OK";
    }

	function retur_jual_detail(){
		$menu = is_get_url($this->uri->segment(1)) ;
		$id = $this->input->get('id');

		$data = array(
			'content' =>'admin/transaction/retur_jual_detail',
			'breadcrumb_title' => 'Transaction',
			'breadcrumb_small' => 'Retur Jual',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
            'pembayaran_type' => $this->common_model->db_select('nd_pembayaran_type'),
			'common_data'=> $this->data,
			'data_isi'=> $this->data,
            'printer_list' => $this->common_model->db_select('nd_printer_list')
            );

		if ($id != '') {
			$data['retur_data'] = $this->tr_model->get_retur_data($id);
            $data['retur_detail'] = $this->tr_model->get_retur_detail($id);
            $data['data_pembayaran'] = $this->tr_model->get_data_pembayaran_retur($id);

            foreach ($data['retur_data'] as $row) {
                $penjualan_id = $row->penjualan_id;
                $toko_id = $row->toko_id;
            }
            $data['penjualan_list_detail'] = $this->tr_model->get_data_penjualan_detail($penjualan_id);
            $result = $this->common_model->db_select('nd_pembayaran_retur where retur_jual_id='.$id);
            foreach ($result as $row) {
                $data['pembayaran_retur'][$row->pembayaran_type_id] = $row->amount; 
                $data['pembayaran_keterangan'][$row->pembayaran_type_id] = $row->keterangan;
            }
            $data['penjualan_print'] = $this->tr_model->get_data_retur_detail_group($id);
            $data['data_toko'] = $this->common_model->db_select('nd_toko where id = '.$toko_id);
            $data['data_penjualan_detail_group'] = $this->tr_model->get_data_retur_detail_by_barang($id);

		}else{
			$data['retur_data'] = array();
			$data['retur_detail'] = array(); 
            $data['penjualan_list_detail'] = array();
            $data['penjualan_print'] = array();
            $data['data_toko'] = array();
            $data['data_penjualan_detail_group'] = array();

		}
		$this->load->view('admin/template',$data);
	}

	function retur_jual_list_detail_insert(){

		$retur_jual_id = $this->input->post('retur_jual_id');
        $get_data = $this->common_model->db_select('nd_penjualan_detail where id='.$this->input->post('penjualan_list_detail_id'));
        foreach ($get_data as $row) {
            $barang_id = $row->barang_id;
            $warna_id = $row->warna_id;
        }

		$data = array(
			'retur_jual_id' => $retur_jual_id ,
			'gudang_id' => $this->input->post('gudang_id'),
			'barang_id' => $barang_id,
			'warna_id' => $warna_id ,
			'harga' => str_replace('.', '', $this->input->post('harga')),
            'keterangan' => null
			);
		$result_id = $this->common_model->db_insert('nd_retur_jual_detail',$data);

		$rekap = explode('--', $this->input->post('rekap_qty'));
		foreach ($rekap as $key => $value) {
			$qty = explode('??', $value);
			$data_qty[$key] = array(
				'retur_jual_detail_id' => $result_id,
				'qty' => $qty[0],
				'jumlah_roll' => $qty[1] ); 
		}

		$this->common_model->db_insert_batch('nd_retur_jual_qty',$data_qty);

		// print_r($data);

		// $result_id = $this->common_model->db_insert('nd_retur_jual',$data);
		redirect($this->setting_link('transaction/retur_jual_detail').'/?id='.$retur_jual_id);
	}

	function retur_jual_qty_update(){
        $retur_jual_detail_id = $this->input->post('id');
		$retur_jual_id = $this->input->post('retur_jual_id');
		$qty = $this->input->post('rekap_qty');
        // print_r($this->input->post());

		$qty_list = explode('--', $qty);
		foreach ($qty_list as $key => $value) {
			$qty = explode('??', $value);
			$data_qty[$key] = array(
				'retur_jual_detail_id' => $retur_jual_detail_id,
				'qty' => $qty[0] ,
				'jumlah_roll' => $qty[1] );
		}

		$this->common_model->db_delete('nd_retur_jual_qty','retur_jual_detail_id',$retur_jual_detail_id);
		$this->common_model->db_insert_batch('nd_retur_jual_qty',$data_qty);
        redirect(is_setting_link('transaction/retur_jual_detail')."?id=".$retur_jual_id);
		// // print_r($this->input->post());
		// echo 'OK';
	}

    function pembayaran_retur_update(){
        $retur_jual_id = $this->input->post('retur_jual_id');
        $pembayaran_type_id = $this->input->post('pembayaran_type_id');
        $data = array(
            'retur_jual_id' => $retur_jual_id,
            'pembayaran_type_id' => $pembayaran_type_id,
            'amount' => ($this->input->post('amount') != '' ? str_replace('.', '', $this->input->post('amount')) : 0) );
        
        $result = $this->common_model->db_select('nd_pembayaran_retur where retur_jual_id='.$retur_jual_id." AND pembayaran_type_id=".$pembayaran_type_id);
        $id = '';
        foreach ($result as $row) {
            $id = $row->id;
        }
        if ($id == '') {
            $this->common_model->db_insert('nd_pembayaran_retur', $data);
        }else{
            $this->common_model->db_update('nd_pembayaran_retur', $data,'id', $id);
        }

        echo 'OK';
    }

	function retur_jual_request_open(){
		// print_r($this->input->post());
		$retur_id = $this->input->post('retur_jual_id');
		$data = array(
			'status' => 1 );
		$this->common_model->db_update('nd_retur_jual',$data,'id',$retur_id);
		redirect(is_setting_link('transaction/retur_jual_detail').'?id='.$retur_id);
	}

	function retur_jual_print(){

		$retur_jual_id = $this->input->get('retur_jual_id');
		$nama_customer = '';
		$tanggal = '';
		$no_faktur = '';
		
		$data['data_retur'] = $this->tr_model->get_retur_data($retur_jual_id);
		$data['data_retur_detail'] = $this->tr_model->get_retur_jual_detail($retur_jual_id);

		$this->load->library('fpdf17/fpdf_css');
		$this->load->library('fpdf17/fpdf');

		$this->load->view('admin/transaction/retur_jual_print',$data);
		
	}

	function retur_jual_list_close()
	{
		$id = $this->input->get('id');
		// $no_faktur = 1;
        $get = $this->common_model->db_select("nd_retur_jual where id=".$id);
        foreach ($get as $row) {
            $no_faktur = $row->no_faktur;
            $tanggal = $row->tanggal;
        }

        $tahun = date('Y', strtotime($tanggal));

        if ($no_faktur == '') {
            $data_get = $this->common_model->db_select("nd_retur_jual where YEAR(tanggal)='".$tahun."' AND no_faktur is not null order by no_faktur desc limit 1 ");
            foreach ($data_get as $row) {
                $no_faktur = $row->no_faktur + 1;
            }
        }

        // echo $no_faktur;
        $data = array(
            'closed_by' => is_user_id() ,
            'no_faktur' => ($no_faktur != '' ? $no_faktur : 1),
            'closed_date' => date('Y-m-d H:i:s'),
            'status' => 0 );

		$this->common_model->db_update('nd_retur_jual',$data,'id',$id);
		redirect($this->setting_link('transaction/retur_jual_detail')."?id=".$id);
	}

//===================================Retur Beli=============================================

    function retur_beli_list(){
        $menu = is_get_url($this->uri->segment(1)) ;

        $data = array(
            'content' =>'admin/transaction/retur_beli_list',
            'breadcrumb_title' => 'Transaction',
            'breadcrumb_small' => 'Daftar Retur Beli',
            'nama_menu' => $menu[0],
            'nama_submenu' => $menu[1],
            'common_data'=> $this->data,
            'data_isi'=> $this->data );


        $data['po_pembelian_batch'] = $this->common_model->get_all_po_pembelian();
        $data['retur_list'] = $this->tr_model->get_retur_beli_list(); 
        $this->load->view('admin/template',$data);
    }

    
    function retur_beli_list_insert(){
        $tanggal = is_date_formatter($this->input->post('tanggal'));
        $tahun = date('Y', strtotime($tanggal));
        $data_get = $this->common_model->db_select("nd_retur_beli where YEAR(tanggal)='".$tahun."' order by no_faktur desc limit 1 ");
        $po_batch_id = $this->input->post('po_pembelian_batch_id');
        $po_batch_id = ($po_batch_id == '' ? 0 : $po_batch_id);

        $data = array(
            'retur_type_id' => $this->input->post('retur_type_id'),
            'toko_id' =>  1,
            'tanggal' => $tanggal,
            'supplier_id' => $this->input->post('supplier_id'),
            'keterangan1' => $this->input->post('keterangan1') ,
            'keterangan2' => $this->input->post('keterangan2') ,
            'user_id' => is_user_id(),
            );

        print_r($data);

        // $result_id = $this->common_model->db_insert('nd_retur_beli',$data);
        // redirect($this->setting_link('transaction/retur_beli_detail').'/?id='.$result_id);

    }

    function get_po_batch_by_supplier() {
        $supplier_id = $this->input->post('supplier_id');
        $get_data = $this->common_model->get_po_pembelian_batch_by_supplier($supplier_id);
        echo json_encode($get_data) ;
    }

    function retur_beli_list_update(){
        $pembelian_id = $this->input->post('pembelian_id');
        $tanggal = is_date_formatter($this->input->post('tanggal'));
        $retur_beli_id = $this->input->post('retur_beli_id');
        // $get_data = $this->common_model->db_select
        $po_batch_id = $this->input->post('po_pembelian_batch_id');
        $po_batch_id = ($po_batch_id == '' ? 0 : $po_batch_id);
        
        $data = array(
            'toko_id' => 1,
            'tanggal' => $tanggal,
            'supplier_id' => $this->input->post('supplier_id'),
            'ockh_info' => $this->input->post('ockh_info'),
            'keterangan1' => $this->input->post('keterangan1'),
            'keterangan2' => $this->input->post('keterangan2'),
            'po_pembelian_batch_id' => $po_batch_id,
            'user_id' => is_user_id(),
            'status' => 1,
            );

        // print_r($data);

        if ($retur_beli_id == '') {
            // echo 'insert';
            $retur_beli_id = $this->common_model->db_insert('nd_retur_beli', $data);
        }else{
            // echo 'update';
            $this->common_model->db_update('nd_retur_beli',$data,'id', $retur_beli_id);
        }
        
        redirect($this->setting_link('transaction/retur_beli_detail').'/?id='.$retur_beli_id);

    }

    function retur_beli_list_detail_remove(){
        $id = $this->input->post('id');
        $this->common_model->db_delete('nd_retur_beli_detail','id', $id);
        $this->common_model->db_delete('nd_retur_beli_qty','retur_beli_detail_id',$id);
        echo "OK";
    }

    function retur_beli_detail(){
        $menu = is_get_url($this->uri->segment(1)) ;
        $id = $this->input->get('id');

        $data = array(
            'content' =>'admin/transaction/retur_beli_detail',
            'breadcrumb_title' => 'Transaction',
            'breadcrumb_small' => 'Retur BELI',
            'nama_menu' => $menu[0],
            'nama_submenu' => $menu[1],
            'common_data'=> $this->data,
            'data_isi'=> $this->data,
            'printer_list' => $this->common_model->db_select('nd_printer_list')
            );

        $data['data_barang'] = array();
        $data['data_warna'] = array();

        if ($id != '') {
            $data['retur_data'] = $this->tr_model->get_retur_beli_data($id);
            $data['retur_detail'] = $this->tr_model->get_retur_beli_detail($id);

            foreach ($data['retur_data'] as $row) {
                $toko_id = $row->toko_id;
                $po_pembelian_batch_id = $row->po_pembelian_batch_id;
                $supplier_id = $row->supplier_id;
                $ockh_info = $row->ockh_info;
                $po_pembelian_id = $row->po_pembelian_id;
            }

            if($po_pembelian_batch_id != 0 && $po_pembelian_batch_id != ''){
                   
            }
            $data['pembelian_print'] = $this->tr_model->get_data_retur_beli_detail_group($id);
            $data['data_toko'] = $this->common_model->db_select('nd_toko where id = '.$toko_id);
            $data['supplier_data'] = $this->common_model->db_select("nd_supplier where id=".$supplier_id);
            $data['po_pembelian_batch'] = $this->common_model->get_all_po_pembelian();
            $data['data_penjualan_detail_group'] = $this->tr_model->get_data_retur_beli_detail_group($id);

            $data['barang_id_list'] = array();
            if ($po_pembelian_batch_id != '') {
                $cond_ockh = '';
                if ($ockh_info != '') {
                    $cond_ockh = "AND OCKH = ".$ockh_info;
                }
                $data['barang_id_list'] = $this->tr_model->get_pembelian_barang_by_po($po_pembelian_batch_id, $cond_ockh);
                if (count($data['barang_id_list']) == 0) {
                    $data['barang_id_list'] = $this->tr_model->get_pembelian_barang_by_po($po_pembelian_batch_id, "");
                }
            }
            
        }else{
            $data['retur_data'] = array();
            $data['retur_detail'] = array(); 
            $data['pembelian_list_detail'] = array();
            $data['pembelian_print'] = array();
            $data['data_toko'] = array();
            $data['supplier_data'] = array();
            $data['po_pembelian_batch'] = $this->common_model->get_all_po_pembelian();
            $data['barang_id_list'] = array();
            
        }
        $this->load->view('admin/template',$data);
    }

    function retur_beli_list_detail_insert(){

        $retur_beli_id = $this->input->post('retur_beli_id');
        $retur_beli_detail_id = $this->input->post('retur_beli_detail_id');
        $po_pembelian_batch_id = $this->input->post('po_pembelian_batch_id');
        $barang_id = $this->input->post('barang_id');
        $warna_id = $this->input->post('warna_id');

        if ($po_pembelian_batch_id != '') {
            $barang_set = explode('??', $this->input->post('barang_id'));
            $barang_id = $barang_set[0];
            $warna_id = $barang_set[1];
        }

        $data = array(
            'retur_beli_id' => $retur_beli_id ,
            'gudang_id' => $this->input->post('gudang_id'),
            'barang_id' => $barang_id,
            'warna_id' => $warna_id ,
            'harga' => str_replace('.', '', $this->input->post('harga')),
            'keterangan' => null
            );

        if ($retur_beli_detail_id == '') {
            $result_id = $this->common_model->db_insert('nd_retur_beli_detail',$data);
            $retur_beli_detail_id = $result_id;
        }else{
            $this->common_model->db_update('nd_retur_beli_detail',$data, 'id', $retur_beli_detail_id);
        }

        $this->common_model->db_delete("nd_retur_beli_qty", "retur_beli_detail_id", $retur_beli_detail_id );

        $rekap = explode('--', $this->input->post('rekap_qty'));
        foreach ($rekap as $key => $value) {
            $qty = explode('??', $value);
            $data_qty[$key] = array(
                'retur_beli_detail_id' => $retur_beli_detail_id,
                'qty' => $qty[0],
                'jumlah_roll' => $qty[1] 
            ); 
        }
        

        $this->common_model->db_insert_batch('nd_retur_beli_qty',$data_qty);

        // print_r($data);

        // $result_id = $this->common_model->db_insert('nd_retur_beli',$data);
        redirect($this->setting_link('transaction/retur_beli_detail').'/?id='.$retur_beli_id);
    }

    function retur_beli_qty_update(){
        $retur_beli_detail_id = $this->input->post('id');
        $retur_beli_id = $this->input->post('retur_beli_id');
        $qty = $this->input->post('rekap_qty');
        // print_r($this->input->post());

        $qty_list = explode('--', $qty);
        foreach ($qty_list as $key => $value) {
            $qty = explode('??', $value);
            $data_qty[$key] = array(
                'retur_beli_detail_id' => $retur_beli_detail_id,
                'qty' => $qty[0] ,
                'jumlah_roll' => $qty[1] );
        }

        $this->common_model->db_delete('nd_retur_beli_qty','retur_beli_detail_id',$retur_beli_detail_id);
        $this->common_model->db_insert_batch('nd_retur_beli_qty',$data_qty);
        redirect(is_setting_link('transaction/retur_beli_detail')."?id=".$retur_beli_id);
        // // print_r($this->input->post());
        // echo 'OK';
    }

    function pembayaran_retur_beli_update(){
        $retur_beli_id = $this->input->post('retur_beli_id');
        $pembayaran_type_id = $this->input->post('pembayaran_type_id');
        $data = array(
            'retur_beli_id' => $retur_beli_id,
            'pembayaran_type_id' => $pembayaran_type_id,
            'amount' => ($this->input->post('amount') != '' ? str_replace('.', '', $this->input->post('amount')) : 0) );
        
        $result = $this->common_model->db_select('nd_pembayaran_retur where retur_beli_id='.$retur_beli_id." AND pembayaran_type_id=".$pembayaran_type_id);
        $id = '';
        foreach ($result as $row) {
            $id = $row->id;
        }
        if ($id == '') {
            $this->common_model->db_insert('nd_pembayaran_retur', $data);
        }else{
            $this->common_model->db_update('nd_pembayaran_retur', $data,'id', $id);
        }

        echo 'OK';
    }

    function retur_beli_request_open(){
        // print_r($this->input->post());
        $retur_id = $this->input->post('retur_beli_id');
        $data = array(
            'status' => 1 );
        $this->common_model->db_update('nd_retur_beli',$data,'id',$retur_id);
        redirect(is_setting_link('transaction/retur_beli_detail').'?id='.$retur_id);
    }

    function retur_beli_print(){

        $retur_beli_id = $this->input->get('retur_beli_id');
        $nama_customer = '';
        $tanggal = '';
        $no_faktur = '';
        
        $data['data_retur'] = $this->tr_model->get_retur_data($retur_beli_id);
        $data['data_retur_detail'] = $this->tr_model->get_retur_beli_detail($retur_beli_id);

        $this->load->library('fpdf17/fpdf_css');
        $this->load->library('fpdf17/fpdf');

        $this->load->view('admin/transaction/retur_beli_print',$data);
        
    }

    function retur_beli_list_close()
    {
        $id = $this->input->get('id');
        // $no_faktur = 1;
        $get = $this->common_model->db_select("nd_retur_beli where id=".$id);
        foreach ($get as $row) {
            $no_sj = $row->no_sj;
            $tanggal = $row->tanggal;
        }

        $tahun = date('Y', strtotime($tanggal));

        if ($no_sj == '') {
            $data_get = $this->common_model->db_select("nd_retur_beli where YEAR(tanggal)='".$tahun."' AND no_sj is not null order by no_sj desc limit 1 ");
            foreach ($data_get as $row) {
                $no_sj = $row->no_sj + 1;
            }
        }

        // echo $no_faktur;
        $data = array(
            'closed_by' => is_user_id() ,
            'no_sj' => ($no_sj != '' ? $no_sj : 1),
            'closed_date' => date('Y-m-d H:i:s'),
            'status' => 0 );

        $this->common_model->db_update('nd_retur_beli',$data,'id',$id);
        redirect($this->setting_link('transaction/retur_beli_detail')."?id=".$id);
    }


//===================================Dp=============================================
	function dp_list(){
		$menu = is_get_url($this->uri->segment(1)) ;

		$data = array(
			'content' =>'admin/transaction/dp_list',
			'breadcrumb_title' => 'Transaction',
			'breadcrumb_small' => 'Uang Muka (DP)',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data'=> $this->data );


		$data['user_id'] = is_user_id();
		$data['dp_list'] = $this->tr_model->get_dp_list(); 
		$this->load->view('admin/template',$data);
	}

	function dp_list_detail(){

		$menu = is_get_url($this->uri->segment(1)) ;
		$customer_id = $this->uri->segment(2);
        $view_type = 1;

		if ($this->input->get('from') && $this->input->get('from') != '' && $this->input->get('to')) {
			$from = is_date_formatter($this->input->get('from'));
			$to = is_date_formatter($this->input->get('to'));
		}else{
			$from = date("Y-m-01"); 
			$to = date("Y-m-t");
		}

        if ($this->input->get('view_type')) {
            $view_type = $this->input->get('view_type');
        }

        $dp_masuk_id_group = '';
        if ($this->input->get('dp_masuk_id')) {
            $dp_masuk_id_group = $this->input->get('dp_masuk_id');
        }

		$data = array(
			'content' =>'admin/transaction/dp_list_detail',
			'breadcrumb_title' => 'Transaction',
			'breadcrumb_small' => 'Kartu DP Customer',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data'=> $this->data,
			'from' => $from,
			'to' => $to,
			'customer_id' => $customer_id,
            'dp_masuk_id_group' => $dp_masuk_id_group,
            'view_type' => $view_type,
            'printer_list' => $this->common_model->db_select('nd_printer_list')
            );

		// $data['bayar_dp_list'] = $this->common_model->db_select('nd_bayar_dp'); 
        if ($customer_id != '') {
    		$get = $this->common_model->db_select('nd_customer where id='.$customer_id);
            # code...
        }
		foreach ($get as $row) {
			$data['nama_customer'] = $row->nama;
		}
		$data['pembayaran_type_list'] = $this->common_model->db_select('nd_pembayaran_type where id != 1 AND id != 5');
		if ($dp_masuk_id_group != '') {
            $data['saldo_awal'] = 0;
            $data['dp_list_detail'] = $this->tr_model->get_dp_detail_by_dp($customer_id, $dp_masuk_id_group); 
        }else{
            if ($view_type == 1) {
                $data['dp_list_detail'] = $this->tr_model->get_dp_detail_quota($customer_id,''); 
            }elseif ($view_type == 2) {
                $data['dp_list_detail'] = $this->tr_model->get_dp_detail_quota($customer_id,'WHERE dp_masuk - ifnull(dp_keluar,0) > 0'); 
            }else{
        		$result = $this->tr_model->get_dp_awal($customer_id, $from);
        		$data['saldo_awal'] = 0;
        		foreach ($result as $row) {
        			$data['saldo_awal'] = $row->saldo;
        		}                
            }
        }



        $data['toko_data'] = $this->common_model->db_select('nd_toko WHERE id=1');
        $data['customer_data'] = $this->common_model->db_select('nd_customer WHERE id='.$customer_id);

        // if(is_posisi_id() != 1 ){
    		$this->load->view('admin/template',$data);
            
        // }
	}

    function print_bukti_dp(){
        $this->load->view('admin/transaction/print_bukti_dp',$data);
    }

	function dp_masuk_insert(){
        $ini = $this->input;
		$customer_id = $ini->post('customer_id');
		$tanggal = is_date_formatter($this->input->post('tanggal'));
		$year = date('Y', strtotime($tanggal));
		$data_get = $this->common_model->db_select("nd_dp_masuk where YEAR(tanggal) ='".$year."' order by no_dp desc limit 1");
		$no_dp = 1;
		foreach ($data_get as $row) {
			$no_dp  = $row->no_dp + 1;
		}

        $pembayaran_piutang_id = $ini->post('pembayaran_piutang_id');
        $pembayaran_type_id = $ini->post('pembayaran_type_id');
        $urutan_giro = null;
        $tahun = date('Y', strtotime($tanggal));

        if ($pembayaran_type_id == 6) {
            $urutan_giro = 1;
            $dt_last_giro = $this->common_model->get_last_urutan_giro($tahun);
            foreach ($dt_last_giro as $row) {
                $urutan_giro = $row->urutan_giro + 1;
            }
        }

		$data = array(
			'no_dp' => $no_dp,
			'customer_id' => $customer_id ,
			'pembayaran_type_id' => $ini->post('pembayaran_type_id'),
            'urutan_giro' => $urutan_giro,
            'tanggal' => is_date_formatter($this->input->post('tanggal')),
            'nama_bank' => ($ini->post('nama_bank') != '' ? $ini->post('nama_bank') : null),
            'no_rek_bank'=> ($ini->post('no_rek_bank') != '' ?  $ini->post('no_rek_bank') : null),
            'no_giro' => ($ini->post('no_giro') != '' ? $ini->post('no_giro') : null) ,
            'nama_penerima' => ($ini->post('nama_penerima') != '' ? $ini->post('nama_penerima') : null),
            'jatuh_tempo' => ($ini->post('jatuh_tempo') !='' ? is_date_formatter($ini->post('jatuh_tempo')) : null),
            'amount' => str_replace('.', '', $ini->post('amount')),
            'keterangan' => $ini->post('keterangan'),
			'user_id' => is_user_id(),
            'created' => date('Y-m-d H:i:s') );

		// print_r($data);
		$this->common_model->db_insert('nd_dp_masuk', $data);
		redirect(is_setting_link('transaction/dp_list_detail').'/'.$customer_id);
	}

	function dp_masuk_update(){
        $ini = $this->input;

		$dp_masuk_id = $ini->post('dp_masuk_id');
		$customer_id = $ini->post('customer_id');
		$tanggal = is_date_formatter($this->input->post('tanggal'));

        $pembayaran_type_id = $ini->post('pembayaran_type_id');
        $tanggal = is_date_formatter($ini->post('tanggal_transfer'));
        $tahun = date('Y', strtotime($tanggal));
        $urutan_giro = ($ini->post('urutan_giro') == '' ? null : $ini->post('urutan_giro'));

        if ($pembayaran_type_id == 6) {
            $get_before_data = $this->common_model->db_select('nd_dp_masuk WHERE id ='.$dp_masuk_id);
            foreach ($get_before_data as $row) {
                if ($row->pembayaran_type_id != 6) {
                    $urutan_giro = 1;
                    $dt_last_giro = $this->common_model->get_last_urutan_giro($tahun);
                    foreach ($dt_last_giro as $row2) {
                        $urutan_giro = $row2->urutan_giro + 1;
                    }
                }
            }

        }

		$data = array(
			'pembayaran_type_id' => $ini->post('pembayaran_type_id'),
            'urutan_giro' => $urutan_giro,
            'tanggal' => is_date_formatter($this->input->post('tanggal')),
            'nama_bank' => ($ini->post('nama_bank') != '' ? $ini->post('nama_bank') : null),
            'no_rek_bank'=> ($ini->post('no_rek_bank') != '' ?  $ini->post('no_rek_bank') : null),
            'no_giro' => ($ini->post('no_giro') != '' ? $ini->post('no_giro') : null) ,
            'nama_penerima' => ($ini->post('nama_penerima') != '' ? $ini->post('nama_penerima') : null),
            'jatuh_tempo' => ($ini->post('jatuh_tempo') !='' ? is_date_formatter($ini->post('jatuh_tempo')) : null),
            'amount' => str_replace('.', '', $ini->post('amount')),
            'keterangan' => $ini->post('keterangan'),
            'user_id' => is_user_id(),
			 );

		// print_r($data);
		$this->common_model->db_update('nd_dp_masuk', $data,'id', $dp_masuk_id);
		redirect(is_setting_link('transaction/dp_list_detail').'/'.$customer_id);
	}

    function dp_masuk_delete(){
        $dp_masuk_id = $this->input->post('dp_masuk_id');
        $this->common_model->db_delete('nd_dp_masuk','id', $dp_masuk_id);
        echo 'OK';
    }

    function dp_keluar_update(){
        $dp_id = $this->input->post('dp_masuk_id');
        $dp_keluar_id = $this->input->post('dp_keluar_id');
        $customer_id = $this->input->post('customer_id');
        $data = array(
            'dp_masuk_id' => $dp_id ,
            'tanggal' => is_date_formatter($this->input->post('tanggal')),
            'pembayaran_type_id' => $this->input->post('pembayaran_type_id'),
            'amount' => str_replace(".", "", $this->input->post('amount')),
            'keterangan' => $this->input->post('keterangan'),
            'nama_penerima' => $this->input->post('nama_penerima'),
            'nama_bank' => $this->input->post('nama_bank'),
            'no_rek_bank' => $this->input->post('no_rek_bank'),
            'user_id' => is_user_id()
            );

        if ($dp_keluar_id != '') {
            $this->common_model->db_update("nd_dp_keluar", $data,'id', $dp_keluar_id);
        }else{
            $this->common_model->db_insert("nd_dp_keluar", $data);
        }
        redirect(is_setting_link('transaction/dp_list_detail').'/'.$customer_id);

        # code...
    }

	function dp_print(){

		$dp_id = $this->input->get('id');
		$nama_customer = '';
		$tanggal = '';
		$no_faktur = '';
		
		$data['data_dp'] = $this->tr_model->get_data_dp($dp_id);
		
		$this->load->library('fpdf17/fpdf_css');
		$this->load->library('fpdf17/fpdf');

		$this->load->view('admin/transaction/dp_print',$data);
		
		
	}

//===================================History Pembelian=============================================

	function pembelian_input_history(){
		$menu = is_get_url($this->uri->segment(1)) ;

		if ($this->input->get('from')) {
			$from = is_date_formatter($this->input->get('from'));
			$to = is_date_formatter($this->input->get('to'));
		}else{
			$from = date("Y-m-d"); 
			$to = date("Y-m-d");
		}

		$data = array(
			'content' =>'admin/transaction/pembelian_input_history',
			'breadcrumb_title' => 'Transaction',
			'breadcrumb_small' => 'Pembelian Input History',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data'=> $this->data,
			'from' => $from,
			'to' => $to );


		$data['history'] = $this->tr_model->get_pembelian_history($from, $to); 
		
		$this->load->view('admin/template',$data);
	}

	function penjualan_input_history(){
		$menu = is_get_url($this->uri->segment(1)) ;

		if ($this->input->get('from')) {
			$from = is_date_formatter($this->input->get('from'));
			$to = is_date_formatter($this->input->get('to'));
		}else{
			$from = date("Y-m-d"); 
			$to = date("Y-m-d");
		}

		$data = array(
			'content' =>'admin/transaction/penjualan_input_history',
			'breadcrumb_title' => 'Transaction',
			'breadcrumb_small' => 'Penjualan Input History',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data'=> $this->data,
			'from' => $from,
			'to' => $to );


		$data['history'] = $this->tr_model->get_penjualan_history($from, $to); 
		
		$this->load->view('admin/template',$data);
	}

//===================================Penerimaan Pembelian=============================================

	// function penerimaan_harian_penjualan(){
	// 	$menu = is_get_url($this->uri->segment(1)) ;
	// 	if($this->input->get('tanggal')){
	// 		$tanggal = is_date_formatter($this->input->get('tanggal'));
	// 	}else{
	// 		$tanggal = date('Y-m-d');
	// 	}

	// 	$data = array(
	// 		'content' =>'admin/transaction/penerimaan_harian_penjualan',
	// 		'breadcrumb_title' => 'Transaction',
	// 		'breadcrumb_small' => 'Penerimaan Harian Penjualan',
	// 		'tanggal' => $tanggal,
	// 		'nama_menu' => $menu[0],
	// 		'nama_submenu' => $menu[1],
	// 		'common_data'=> $this->data,
	// 		'data_isi'=> $this->data );

	// 	$data['penjualan_list'] = $this->tr_model->get_penjualan_bayar_by_date($tanggal);
	// 	$data['retur_list'] = $this->tr_model->get_retur_jual_by_date($tanggal);
	// 	$data['pembayaran_type'] = $this->common_model->db_select("nd_pembayaran_type");
	// 	$this->load->view('admin/template',$data);

	// }


//============================piutang temp section=================================================

	function piutang_list(){
		$menu = is_get_url($this->uri->segment(1)) ;

		$data = array(
			'content' =>'admin/transaction/piutang_list',
			'breadcrumb_title' => 'Transaction',
			'breadcrumb_small' => 'Daftar Piutang',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data'=> $this->data,
			'data_isi'=> $this->data );

		$data['piutang_list'] = $this->tr_model->get_piutang_list_all(); 
		$this->load->view('admin/template',$data);
	}

	function piutang_list_detail(){
		$menu = is_get_url($this->uri->segment(1)) ;
		$customer_id = $this->input->get('customer_id');

		$data = array(
			'content' =>'admin/transaction/piutang_list_detail',
			'breadcrumb_title' => 'Transaction',
			'breadcrumb_small' => 'Daftar Piutang Detil',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data'=> $this->data,
			'data_isi'=> $this->data,
			'customer_id' => $customer_id );

		$data['piutang_list_detail'] = $this->tr_model->get_piutang_list_detail($customer_id); 
		$this->load->view('admin/template',$data);
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
			'content' =>'admin/transaction/pembayaran_piutang',
			'breadcrumb_title' => 'Transaction',
			'breadcrumb_small' => 'Daftar Pembayaran Piutang',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data'=> $this->data,
			'data_isi'=> $this->data,
			'customer_id' => $customer_id,
			'toko_id' => $toko_id,
			'tanggal_start' => is_reverse_date($tanggal_start),
			'tanggal_end' => is_reverse_date($tanggal_end) );


		if ($this->input->get('tanggal_start') && $this->input->get('tanggal_start') != ''&& $this->input->get('tanggal_end') != '') {
			$data['pembayaran_piutang_list'] = $this->tr_model->get_pembayaran_piutang($tanggal_start, $tanggal_end, $cond);
			// echo $data['pembayaran_piutang_list'];
			foreach ($data['pembayaran_piutang_list'] as $row) {

				$periode = $this->tr_model->get_periode_penjualan($row->id);
				foreach ($periode as $row2) {
					$data['periode'][$row->id]['tanggal_start'] = is_reverse_date($row2->tanggal_start);
					$data['periode'][$row->id]['tanggal_end'] = is_reverse_date($row2->tanggal_end);
				}
				$data['pembayaran_piutang_awal_detail'][$row->id] = $this->tr_model->get_pembayaran_piutang_awal_detail($row->id);
				$data['pembayaran_piutang_detail'][$row->id] = $this->tr_model->get_pembayaran_piutang_detail($row->id);
				$data['pembayaran_piutang_nilai'][$row->id] = $this->common_model->db_select("nd_pembayaran_piutang_temp_nilai WHERE pembayaran_piutang_id=".$row->id);

			}
		}else{
			$data['pembayaran_piutang_list'] = $this->tr_model->get_pembayaran_piutang_unbalance();
			foreach ($data['pembayaran_piutang_list'] as $row) {

				$periode = $this->tr_model->get_periode_penjualan($row->id);
				foreach ($periode as $row2) {
					$data['periode'][$row->id]['tanggal_start'] = is_reverse_date($row2->tanggal_start);
					$data['periode'][$row->id]['tanggal_end'] = is_reverse_date($row2->tanggal_end);
				}
				$data['pembayaran_piutang_awal_detail'][$row->id] = $this->tr_model->get_pembayaran_piutang_awal_detail($row->id);
				$data['pembayaran_piutang_detail'][$row->id] = $this->tr_model->get_pembayaran_piutang_detail($row->id);
				$data['pembayaran_piutang_nilai'][$row->id] = $this->common_model->db_select("nd_pembayaran_piutang_temp_nilai WHERE pembayaran_piutang_id=".$row->id);
			}

			$data['status_view'] = 0;
		}

		$this->load->view('admin/template',$data);
	}

	function piutang_payment_form(){
		$menu = is_get_url($this->uri->segment(1));

		if ($this->input->get('tanggal_start')) {
			$tanggal_start = is_date_formatter($this->input->get('tanggal_start'));
			$tanggal_end = is_date_formatter($this->input->get('tanggal_end'));
			$toko_id = $this->input->get('toko_id');
			$customer_id = $this->input->get('customer_id');

		}else{
			$tanggal_start = date("Y-m-01"); 
			$tanggal_end = date("Y-m-t");
			$toko_id = 1;
			$customer_id = '';
		}

		$pembayaran_piutang_id = '';
		if ($this->input->get('id')) {
			$pembayaran_piutang_id = $this->input->get('id');
		}

		$data = array(
			'content' =>'admin/transaction/pembayaran_piutang_form',
			'breadcrumb_title' => 'Transaction',
			'breadcrumb_small' => 'Formulir Pembayaran Piutang',
			'nama_menu' => $menu[0],
			'nama_submenu' => $menu[1],
			'common_data'=> $this->data,
			'data_isi'=> $this->data,
			'tanggal_start' => is_reverse_date($tanggal_start),
			'tanggal_end' => is_reverse_date($tanggal_end),
			'toko_id' => $toko_id,
			'customer_id' => $customer_id );


		if ($pembayaran_piutang_id != '') {
			$data['pembayaran_piutang_data'] = $this->tr_model->get_pembayaran_piutang_data($pembayaran_piutang_id);
			$periode = $this->tr_model->get_periode_penjualan($pembayaran_piutang_id);
			foreach ($periode as $row) {
				$data['tanggal_start'] = $row->tanggal_start;
				$data['tanggal_end'] = $row->tanggal_end;
			}

			foreach ($data['pembayaran_piutang_data'] as $row) {
				$customer_id = $row->customer_id;
			}
			
			$data['pembayaran_piutang_awal_detail'] = $this->tr_model->get_pembayaran_piutang_awal_detail($pembayaran_piutang_id); 
			$data['pembayaran_piutang_detail'] = $this->tr_model->get_pembayaran_piutang_detail($pembayaran_piutang_id); 
			$data['pembayaran_piutang_nilai'] = $this->common_model->db_select("nd_pembayaran_piutang_temp_nilai where pembayaran_piutang_id=".$pembayaran_piutang_id);
			$data['bank_history'] = $this->tr_model->get_customer_bank_bayar_history($customer_id);
			
		}elseif ($toko_id != '' && $customer_id != '') {
			$data['pembayaran_piutang_data'] = array();
			$data['pembayaran_piutang_awal_detail'] = $this->fi_model->get_piutang_awal_by_date($tanggal_start, $tanggal_end, $toko_id, $customer_id); 
			$data['pembayaran_piutang_detail'] = $this->fi_model->get_piutang_list_by_date($tanggal_start, $tanggal_end, $toko_id, $customer_id); 
			$data['pembayaran_hutang_nilai'] = array();
			$data['bank_history'] = array();
			

		}else{
			$data['pembayaran_piutang_awal_detail'] = array(); 
			$data['pembayaran_piutang_data'] = array();
			$data['pembayaran_piutang_detail'] = array(); 
			$data['bank_history'] = array();
		}
		$data['printer_list'] = $this->common_model->db_select('nd_printer_list');

		$this->load->view('admin/template',$data);
	}

	function pembayaran_piutang_insert(){
		$ini = $this->input;
		$pembayaran_piutang_id = $this->input->post('pembayaran_piutang_id');
		// echo $pembayaran_piutang_id;
		

		if ($pembayaran_piutang_id == '') {

			$data = array(
			'customer_id' => $ini->post('customer_id'),
			'toko_id' => $ini->post('toko_id'),
			'user_id' => is_user_id() );

			$result_id = $this->common_model->db_insert('nd_pembayaran_piutang_temp',$data);

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

			
			$this->common_model->db_insert_batch('nd_pembayaran_piutang_temp_detail',$data_detail);	
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

			$this->common_model->db_update('nd_pembayaran_piutang_temp',$data,'id',$pembayaran_piutang_id);
					
		}
		
		redirect(is_setting_link('transaction/piutang_payment_form').'/?id='.$pembayaran_piutang_id);

	}

	function update_bayar_piutang_detail(){
		$id = $this->input->post('id');
		$data = array(
			'amount' => $this->input->post('amount') );
		$this->common_model->db_update('nd_pembayaran_piutang_temp_detail',$data,'id',$id);
		echo "OK";
	}

	function pembayaran_piutang_nilai_insert(){
		// print_r($this->input->post());
		$ini = $this->input;
		$pembayaran_piutang_id = $ini->post('pembayaran_piutang_id');
		$data = array(
			'pembayaran_piutang_id' =>  $pembayaran_piutang_id,
			'pembayaran_type_id' =>  $ini->post('pembayaran_type_id'),
			'tanggal_transfer' => is_date_formatter($ini->post('tanggal_transfer')),
			'nama_penerima' => ($ini->post('nama_penerima') != '' ? $ini->post('nama_penerima') : null),
			'nama_bank' => ($ini->post('nama_bank') != '' ? $ini->post('nama_bank') : null),
			'no_rek_bank'=> ($ini->post('no_rek_bank') != '' ?  $ini->post('no_rek_bank') : null),
			'no_giro' => ($ini->post('no_giro') != '' ? $ini->post('no_giro') : null) ,
			'no_akun_giro'=> ($ini->post('no_akun_giro') != '' ? $ini->post('no_akun_giro') : null),
			// 'tanggal_giro' => is_date_formatter($ini->post('tanggal_giro')),
			'jatuh_tempo' => ($ini->post('jatuh_tempo') !=''  && is_date_formatter($ini->post('jatuh_tempo')) != '0000-00-00' ? is_date_formatter($ini->post('jatuh_tempo')) : null),
			'amount' => str_replace('.', '', $ini->post('amount')),
			'keterangan' => $ini->post('keterangan') );

		// print_r($data);
		$this->common_model->db_insert('nd_pembayaran_piutang_temp_nilai', $data);

		redirect(is_setting_link('transaction/piutang_payment_form').'/?id='.$pembayaran_piutang_id.'#bayar-section');

	}

	function pembayaran_piutang_nilai_update(){
		// print_r($this->input->post());
		$ini = $this->input;
		$pembayaran_piutang_id = $ini->post('pembayaran_piutang_id');
		$id = $ini->post('pembayaran_piutang_nilai_id');
		$data = array(
			'pembayaran_piutang_id' =>  $pembayaran_piutang_id,
			'pembayaran_type_id' =>  $ini->post('pembayaran_type_id'),
			'tanggal_transfer' => is_date_formatter($ini->post('tanggal_transfer')),
			'nama_penerima' => ($ini->post('nama_penerima') != '' ? $ini->post('nama_penerima') : null),
			'nama_bank' => ($ini->post('nama_bank') != '' ? $ini->post('nama_bank') : null),
			'no_rek_bank'=> ($ini->post('no_rek_bank') != '' ?  $ini->post('no_rek_bank') : null),
			'no_giro' => ($ini->post('no_giro') != '' ? $ini->post('no_giro') : null) ,
			'no_akun_giro'=> ($ini->post('no_akun_giro') != '' ? $ini->post('no_akun_giro') : null),
			// 'tanggal_giro' => is_date_formatter($ini->post('tanggal_giro')),
			'jatuh_tempo' => ($ini->post('jatuh_tempo') !='' && is_date_formatter($ini->post('jatuh_tempo')) != '0000-00-00' ? is_date_formatter($ini->post('jatuh_tempo')) : null),
			'amount' => str_replace('.', '', $ini->post('amount')),
			'keterangan' => $ini->post('keterangan') );

		// print_r($data);
		$this->common_model->db_update("nd_pembayaran_piutang_temp_nilai", $data,'id', $id);
		redirect(is_setting_link('transaction/piutang_payment_form').'/?id='.$pembayaran_piutang_id);
	}

	function update_pembulatan_piutang(){
		$id = $this->input->post('id');
		$data = array(
			'pembulatan' => $this->input->post('pembulatan') );
		$this->common_model->db_update("nd_pembayaran_piutang_temp", $data, "id", $id);
		echo "OK";

	}

/**
//================================supplier list================================================
**/

    function request_barang_legacy(){
        $menu = is_get_url($this->uri->segment(1));
        $no_batch = 0; $no_request = 0;
        $request_barang_batch_id = 0;
        $request_barang_id = 0;
        $request_batch = array();

        //==============================request section=======================================
        if ($this->input->get('request_barang_batch_id') != '') {
            $request_barang_batch_id = $this->input->get('request_barang_batch_id');
        }
        
        if ($request_barang_batch_id == 0) {
            if($this->input->get('request_barang_id') != ''){
                $request_barang_id = $this->input->get('request_barang_id');
                $request_batch = $this->common_model->db_select('nd_request_barang_batch WHERE request_barang_id='.$request_barang_id." ORDER BY batch desc LIMIT 1");
            }else{
                $request_batch = $this->common_model->db_select("nd_request_barang_batch WHERE status = 1 AND status_aktif = 1");
            }
        }else if ($request_barang_batch_id != ''){
            $request_batch = $this->common_model->db_select("nd_request_barang_batch WHERE id=".$request_barang_batch_id);
        }
        foreach ($request_batch as $row) {
            $request_barang_batch_id = $row->id;
            $request_barang_id = $row->request_barang_id;
        }

        $isLatest = false;
        $latest_batch = $this->common_model->db_select("nd_request_barang_batch WHERE status_aktif = 1 ORDER by id DESC LIMIT 1");
        foreach ($latest_batch as $row) {
            if ($request_barang_batch_id == $row->id) {
                $isLatest = true;
            }
            $request_barang_id = $request_barang_id;
        }

        // echo $request_barang_batch_id.' '.$request_barang_id;
        if ($request_barang_batch_id == 0 && $request_barang_id == 0) {
            $get_latest = $this->common_model->db_select("nd_request_barang_batch ORDER by id DESC LIMIT 1");
            foreach ($get_latest as $row) {
                $request_barang_batch_id = $row->id;
                $request_barang_id = $row->request_barang_id;
            }
        }
        
        
        $tanggal_request = date('Y-m-d');
        $tahun = date('Y');
        $request_barang_data = $this->tr_model->get_request_barang_data($request_barang_batch_id);
        $no_batch = 1;
        $supplier_id = '';
        $closed_date = date('Y-m-d 23:59:59');
        foreach ($request_barang_data as $row) {
            $tanggal_request = $row->tanggal;
            $supplier_id=$row->supplier_id;
            $tahun = substr($row->tanggal, 0,4);
            $no_batch = $row->batch;
            if ($row->closed_date != '') {
                $closed_date = $row->closed_date;
            }
        }

        if ($this->input->get('supplier_id_filter') != '') {
            $supplier_id = $this->input->get('supplier_id_filter');
        }
        
        $cond_supplier = "";
        if ($supplier_id != '') {
            # code...
            $cond_supplier = "WHERE supplier_id= $supplier_id";
        }

        //==============================barang section=======================================
        $barang_id = $this->input->get('id');
        $barang_beli_id = $this->input->get('barang_beli_id');
        // $tahun = date('Y') - 1;
        
        $barang_id = ($barang_id == '' ? 0 : $barang_id);
        $tanggal_start = date('Y-m-01');
        $tanggal_end = date('Y-m-01', strtotime('+5 months'));
        // $tanggal_end = date('Y-12-31');
        
        $tanggal_start_forecast = date('Y-m-01',strtotime($tanggal_start.' -1 year'));
        $tanggal_end_forecast = date('Y-m-t',strtotime($tanggal_end.' -1 year'));
        
        if($this->input->get('tanggal_start') != '' && $this->input->get('tanggal_end') != ''){
            $tanggal_start = date('Y-m-01', strtotime($this->input->get('tanggal_start')));
            $tanggal_end = date('Y-m-t', strtotime($this->input->get('tanggal_end')));
            
            // perbedaan tanggal start / forecast, yaitu tggl start itu tahun ini, 
            // sedangkan tgl forecast get data 1 tahun before dari tgl start
            $tanggal_start_forecast = date('Y-m-01',strtotime($tanggal_start.' -1 year'));;
            $tanggal_end_forecast = date('Y-m-t',strtotime($tanggal_end.' -1 year'));
        }else if($request_barang_id != ''){
            $first_batch = $this->tr_model->getBulanRequestAwal($request_barang_id);
            foreach ($first_batch as $row) {
                $tanggal_start = $row->bulan_request;
            }
            $tanggal_end = date('Y-m-01', strtotime($tanggal_start.' +5 months'));

            $tanggal_start_forecast = date('Y-m-01',strtotime($tanggal_start.' -1 year'));;
            $tanggal_end_forecast = date('Y-m-t',strtotime($tanggal_end.' -1 year'));
        }

        $data = array(
            'content' =>'admin/transaction/barang_request_2' ,
            'breadcrumb_title' => 'Transaction',
            'breadcrumb_small' => 'Request Barang',
            'nama_menu' => $menu[0],
            'nama_submenu' => $menu[1],
            'isLatest' => $isLatest,
            'barang_id' => $barang_id,
            'barang_beli_id' => $barang_beli_id,
            'supplier_id_filter'=>$supplier_id,
            'common_data'=> $this->data,
            'tanggal_start' => $tanggal_start,
            'tanggal_end' => $tanggal_end,
            'tanggal_start_forecast' => $tanggal_start_forecast,
            'request_barang_data' => $request_barang_data,
            'tanggal_end_forecast' => $tanggal_end_forecast);
        $data['user_id'] = is_user_id();
        $data['data_barang'] = $this->common_model->db_select('nd_barang where id='.$barang_id); 
        $data['data_warna'] = $this->common_model->get_warna_asosiasi($barang_id);
        $data['data_forecast'] = $this->common_model->get_penjualan_for_forecast($barang_id, $tanggal_start_forecast, $tanggal_request);
        // $data['data_pembelian_now'] = $this->common_model->get_data_pembelian_by_date($barang_id, $tanggal_start, $tanggal_end);
        // $data['penjualan_berjalan'] = $this->common_model->get_penjualan_bulan_berjalan($barang_id, $tanggal_start, $tanggal_end);

        $tanggal_awal = '2018-01-01';
        $stok_opname_id = 1;
        $getOpname = $this->common_model->db_select("nd_stok_opname where tanggal <= '".date('Y-m-d')."' order by tanggal desc limit 1");
        foreach ($getOpname as $row) {
            // $tanggal_awal = $row->tanggal;
            // $stok_opname_id = $row->id;
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

        $select = $this->generate_select_versi_2();
        $data['penjualan_berjalan'] = $this->common_model->get_penjualan_bulan_berjalan($barang_id, $tanggal_start, $tanggal_end);
        

        $request_barang_id_before = 0; $data['request_barang_qty_all'] = array();
        $data['request_barang_qty_all'] = array();
        if ($no_batch > 1 ) {
            $data['request_barang_qty_all'] = $this->tr_model->get_request_barang_qty_aktif($request_barang_batch_id,$request_barang_id);
        }

        $data['request_barang_status'] = $this->tr_model->get_request_barang_qty_status($request_barang_id);
        
        $data['data_outstanding_po'] = $this->tr_model->get_outstanding_barang_for_request_2($request_barang_id, $barang_id, $tanggal_request, $supplier_id);
        $data['stok_barang'] = $this->inv_model->get_stok_barang_list_by_barang_temp_2($tanggal_awal, $select, $tanggal_request, "AND barang_id=$barang_id", $stok_opname_id); 

        $data['data_outstanding_update'] = array();
        $data['stok_barang_update'] = array();

        $data['request_barang_qty'] = $this->tr_model->get_request_barang_qty2($barang_id, $request_barang_batch_id, $request_barang_id);
        // $data['request_barang_detail'] = $this->tr_model->get_active_request_barang_by_request($request_barang_id, $request_barang_batch_id);
        if (is_posisi_id()==1) {
            // echo $request_barang_batch_id;
            // echo $request_barang_id;
            $data['request_barang_detail'] = $this->tr_model->get_active_request_barang_by_request_2($request_barang_id, $request_barang_batch_id, $cond_supplier, $supplier_id);
        }else{
            $data['request_barang_detail'] = $this->tr_model->get_active_request_barang_by_request_2($request_barang_id, $request_barang_batch_id, $cond_supplier, $supplier_id);
        }
        $data['barang_terkirim'] = array();
        if ($no_batch > 1 ) {
            // echo $request_barang_id, $closed_date.'<br/>';
            //by tanggal input
            // $data['barang_terkirim'] = $this->tr_model->get_request_barang_terkirim($request_barang_id, $closed_date);
            //by surat_jalan
            $data['barang_terkirim'] = $this->tr_model->get_request_barang_terkirim_2($request_barang_id, $closed_date);
        }

        $data['barang_terkirim_pertoday'] = $this->tr_model->get_request_barang_terkirim_2($request_barang_id, date('Y-m-d'));


        $get_request = $this->common_model->db_select('nd_request_barang_batch where id='.$request_barang_batch_id);
        foreach ($get_request as $row) {
            $request_id = $row->request_barang_id;
        }
        $data['request_barang_qty_data'] = $this->tr_model->get_request_barang_qty_data($request_barang_id, $request_barang_batch_id, $barang_id);
        $data['request_barang_qty_keterangan'] = $this->tr_model->get_request_barang_qty_keterangan($request_barang_id);

        if (is_posisi_id() == 1) {
            $data['content'] = 'admin/transaction/barang_request_5';
            $this->load->view('admin/template',$data);
            $this->output->enable_profiler(TRUE);
            // echo $request_barang_id, $barang_id, $tanggal_request;

        }else{
            $data['content'] = 'admin/transaction/barang_request_5';

            $this->load->view('admin/template',$data);
            
        }
    }

    function request_barang(){
        $barang_beli = $this->common_model->get_barang_beli();

        

        $menu = is_get_url($this->uri->segment(1));
        $no_batch = 0; $no_request = 0;
        $request_barang_batch_id = 0;
        $request_barang_id = 0;
        $request_batch = array();

        //==============================request section=======================================
        if ($this->input->get('request_barang_batch_id') != '') {
            $request_barang_batch_id = $this->input->get('request_barang_batch_id');
        }
        
        if ($request_barang_batch_id == 0) {
            if($this->input->get('request_barang_id') != ''){
                $request_barang_id = $this->input->get('request_barang_id');
                $request_batch = $this->common_model->db_select('nd_request_barang_batch WHERE request_barang_id='.$request_barang_id." ORDER BY batch desc LIMIT 1");
            }else{
                $request_batch = $this->common_model->db_select("nd_request_barang_batch WHERE status = 1 AND status_aktif = 1");
            }
        }else if ($request_barang_batch_id != ''){
            $request_batch = $this->common_model->db_select("nd_request_barang_batch WHERE id=".$request_barang_batch_id);
        }
        foreach ($request_batch as $row) {
            $request_barang_batch_id = $row->id;
            $request_barang_id = $row->request_barang_id;
        }

        $isLatest = false;
        $latest_batch = $this->common_model->db_select("nd_request_barang_batch WHERE status_aktif = 1 ORDER by id DESC LIMIT 1");
        foreach ($latest_batch as $row) {
            if ($request_barang_batch_id == $row->id) {
                $isLatest = true;
            }
            $request_barang_id = $request_barang_id;
        }

        // echo $request_barang_batch_id.' '.$request_barang_id;
        if ($request_barang_batch_id == 0 && $request_barang_id == 0) {
            $get_latest = $this->common_model->db_select("nd_request_barang_batch ORDER by id DESC LIMIT 1");
            foreach ($get_latest as $row) {
                $request_barang_batch_id = $row->id;
                $request_barang_id = $row->request_barang_id;
            }
        }
        
        
        $tanggal_request = date('Y-m-d');
        $tahun = date('Y');
        $request_barang_data = $this->tr_model->get_request_barang_data($request_barang_batch_id);
        $no_batch = 1;
        
        $supplier_id = 0;
        $cond_supplier = "";
        if ($this->input->get('supplier_id_filter') != '') {
            $supplier_id = $this->input->get('supplier_id_filter');
            if ($supplier_id != 0 && $supplier_id != "") {
                $cond_supplier = "WHERE supplier_id = $supplier_id";
            }
        }
        $closed_date = date('Y-m-d 23:59:59');
        foreach ($request_barang_data as $row) {
            $tanggal_request = $row->tanggal;
            $tahun = substr($row->tanggal, 0,4);
            $no_batch = $row->batch;
            if ($row->closed_date != '') {
                $closed_date = $row->closed_date;
            }
        }

        // if ($this->input->get('supplier_id_filter') != '') {
        //     $supplier_id = $this->input->get('supplier_id_filter');
        // }

        //==============================barang section=======================================
        $barang_id = $this->input->get('id');
        $barang_beli_id = $this->input->get('barang_beli_id');
        // $tahun = date('Y') - 1;
        
        $barang_id = ($barang_id == '' ? 0 : $barang_id);
        $barang_beli_id = ($barang_beli_id == '' ? 0 : $barang_beli_id);
        $tanggal_start = date('Y-m-01');
        $tanggal_end = date('Y-m-01', strtotime('+5 months'));
        // $tanggal_end = date('Y-12-31');
        
        $tanggal_start_forecast = date('Y-m-01',strtotime($tanggal_start.' -1 year'));
        $tanggal_end_forecast = date('Y-m-t',strtotime($tanggal_end.' -1 year'));
        
        if($this->input->get('tanggal_start') != '' && $this->input->get('tanggal_end') != ''){
            $tanggal_start = date('Y-m-01', strtotime($this->input->get('tanggal_start')));
            $tanggal_end = date('Y-m-t', strtotime($this->input->get('tanggal_end')));
            
            // perbedaan tanggal start / forecast, yaitu tggl start itu tahun ini, 
            // sedangkan tgl forecast get data 1 tahun before dari tgl start
            $tanggal_start_forecast = date('Y-m-01',strtotime($tanggal_start.' -1 year'));;
            $tanggal_end_forecast = date('Y-m-t',strtotime($tanggal_end.' -1 year'));
        }else if($request_barang_id != ''){
            $first_batch = $this->tr_model->getBulanRequestAwal($request_barang_id);
            foreach ($first_batch as $row) {
                $tanggal_start = $row->bulan_request;
            }
            $tanggal_end = date('Y-m-01', strtotime($tanggal_start.' +5 months'));

            $tanggal_start_forecast = date('Y-m-01',strtotime($tanggal_start.' -1 year'));;
            $tanggal_end_forecast = date('Y-m-t',strtotime($tanggal_end.' -1 year'));
        }

        $data = array(
            'content' =>'admin/transaction/barang_request_2' ,
            'breadcrumb_title' => 'Transaction',
            'breadcrumb_small' => 'Request Barang',
            'nama_menu' => $menu[0],
            'nama_submenu' => $menu[1],
            'isLatest' => $isLatest,
            'barang_id' => $barang_id,
            'barang_beli_id' => $barang_beli_id,
            'supplier_id_filter'=>$supplier_id,
            'common_data'=> $this->data,
            'tanggal_start' => $tanggal_start,
            'tanggal_end' => $tanggal_end,
            'tanggal_start_forecast' => $tanggal_start_forecast,
            'request_barang_data' => $request_barang_data,
            'supplier_data' => $this->common_model->db_select("nd_supplier WHERE id = $supplier_id"),
            'tanggal_end_forecast' => $tanggal_end_forecast);
        $data['user_id'] = is_user_id();
        $data['data_barang'] = $this->common_model->db_select('nd_barang where id='.$barang_id); 
        // $data['data_warna'] = $this->common_model->get_warna_asosiasi($barang_id);
        $data['data_forecast'] = $this->common_model->get_penjualan_for_forecast($barang_id, $tanggal_start_forecast, $tanggal_request);
        // $data['data_pembelian_now'] = $this->common_model->get_data_pembelian_by_date($barang_id, $tanggal_start, $tanggal_end);
        // $data['penjualan_berjalan'] = $this->common_model->get_penjualan_bulan_berjalan($barang_id, $tanggal_start, $tanggal_end);

        
        $data['data_barang'] = $this->common_model->db_select('nd_barang where id='.$barang_id); 
        $data['data_barang_beli'] = $this->common_model->db_select('nd_barang_beli where id='.$barang_beli_id); 
        $data['data_warna'] = $this->common_model->get_warna_asosiasi($barang_id);

        $tanggal_awal = '2018-01-01';
        $stok_opname_id = 1;
        $getOpname = $this->common_model->db_select("nd_stok_opname where tanggal <= '".date('Y-m-d')."' order by tanggal desc limit 1");
        foreach ($getOpname as $row) {
            // $tanggal_awal = $row->tanggal;
            // $stok_opname_id = $row->id;
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

        $select = $this->generate_select_versi_2();
        $data['penjualan_berjalan'] = $this->common_model->get_penjualan_bulan_berjalan($barang_id, $tanggal_start, $tanggal_end);
        

        $request_barang_id_before = 0; $data['request_barang_qty_all'] = array();
        $data['request_barang_qty_all'] = array();
        if ($no_batch > 1 ) {
            $data['request_barang_qty_all'] = $this->tr_model->get_request_barang_qty_aktif($request_barang_batch_id,$request_barang_id);
        }

        $data['request_barang_status'] = $this->tr_model->get_request_barang_qty_status($request_barang_id);
        
        // $data['data_outstanding_po'] = $this->tr_model->get_outstanding_barang_for_request_2($request_barang_id, $barang_id, $tanggal_request, $supplier_id);
        $data['data_outstanding_po'] = $this->tr_model->get_outstanding_barang_for_request_2_bybeli($request_barang_id, $barang_beli_id, $tanggal_request, $supplier_id);
        $data['stok_barang'] = $this->inv_model->get_stok_barang_list_by_barang_temp_2($tanggal_awal, $select, $tanggal_request, "AND barang_id=$barang_id", $stok_opname_id); 

        $data['data_outstanding_update'] = array();
        $data['stok_barang_update'] = array();

        $data['request_barang_qty'] = $this->tr_model->get_request_barang_qty2($barang_id, $request_barang_batch_id, $request_barang_id);
        // $data['request_barang_detail'] = $this->tr_model->get_active_request_barang_by_request($request_barang_id, $request_barang_batch_id);
        if (is_posisi_id()==1) {
            // echo $request_barang_batch_id;
            // echo $request_barang_id;
            $data['request_barang_detail'] = $this->tr_model->get_active_request_barang_by_request_2($request_barang_id, $request_barang_batch_id, $cond_supplier, $supplier_id);
        }else{
            $data['request_barang_detail'] = $this->tr_model->get_active_request_barang_by_request_2($request_barang_id, $request_barang_batch_id, $cond_supplier, $supplier_id);
        }
        $data['barang_terkirim'] = array();
        if ($no_batch > 1 ) {
            // echo $request_barang_id, $closed_date.'<br/>';
            //by tanggal input
            // $data['barang_terkirim'] = $this->tr_model->get_request_barang_terkirim($request_barang_id, $closed_date);
            //by surat_jalan

            // $data['barang_terkirim'] = $this->tr_model->get_request_barang_terkirim_2($request_barang_id, $closed_date);
            $data['barang_terkirim'] = $this->tr_model->get_request_barang_terkirim_2_bybeli($request_barang_id, $closed_date);
        }

        // $data['barang_terkirim_pertoday'] = $this->tr_model->get_request_barang_terkirim_2($request_barang_id, date('Y-m-d'));
        $data['barang_terkirim_pertoday'] = $this->tr_model->get_request_barang_terkirim_2_bybeli($request_barang_id, date('Y-m-d'));


        $get_request = $this->common_model->db_select('nd_request_barang_batch where id='.$request_barang_batch_id);
        foreach ($get_request as $row) {
            $request_id = $row->request_barang_id;
        }
        $data['request_barang_qty_data'] = $this->tr_model->get_request_barang_qty_data($request_barang_id, $request_barang_batch_id, $barang_id);
        $data['request_barang_qty_keterangan'] = $this->tr_model->get_request_barang_qty_keterangan($request_barang_id);
        $data['barang_beli_list'] = $barang_beli;

        if (is_posisi_id() == 1) {
            // echo  $request_barang_id.' '. $request_barang_batch_id;
            // print_r($data['request_barang_detail']);
            $data['content'] = 'admin/transaction/barang_request_6';
            $this->load->view('admin/template',$data);
            $this->output->enable_profiler(TRUE);
            // echo $request_barang_id, $barang_id, $tanggal_request;
            // print_r($data['request_barang_detail']);

            // echo $request_barang_id.' -- '. $request_barang_batch_id.' -- '. $cond_supplier.' -- '. $supplier_id;

        }else{
            $data['content'] = 'admin/transaction/barang_request_6';

            $this->load->view('admin/template',$data);
            
        }
    
    }
    

    function new_request_insert(){
        // print_r($this->input->post());


        $dt_update = array(
            'status' => 0,
        );

        $request_barang_id = $this->input->post('request_barang_id');
        $request_barang_batch_id = $this->input->post('request_barang_batch_id');
        $bulan_awal_request = $this->input->post('bulan_awal_request');

        // foreach ($this->common_model->db_select() as $row) {
        //     # code...
            
        // }

        $this->common_model->db_update("nd_request_barang_batch",$dt_update,"id",$request_barang_batch_id);
        $type = $this->input->post('type');
        
        $no_batch = 1;
        $attn = '';
        
        $get_batch = $this->common_model->db_select("nd_request_barang_batch where id = ".$request_barang_batch_id);
        foreach ($get_batch as $row) {
            $attn = $row->attn;
        }

        //====asign baru====
        $no_request = 1;
        $tanggal =  is_date_formatter($this->input->post('tanggal'));
        $supplier_id = $this->input->post('supplier_id');
        $year =  date('Y' , strtotime($tanggal));
        if ($type == 2) {
            $get_batch = $this->common_model->db_select("nd_request_barang_batch where request_barang_id = ".$request_barang_id." ORDER BY batch desc LIMIT 1");
            foreach ($get_batch as $row) {
                $no_batch = $row->batch+1;
            }

            if ($this->input->post('po_baru_assign') != '') {
                // echo '1';
                $this->assign_po_baru_to_po_number($this->input->post('po_baru_assign')); 
                // print_r($this->input->post('po_lock_assign'));  
            }

            if ($this->input->post('po_lock_assign') != '') {
                // echo '2';
                $this->assign_penyesuaian_po_lock($this->input->post('po_lock_assign'));   
                // print_r($this->input->post('po_lock_assign'));  
            }

        }else{
            // $get_data = $this->common_model->db_select("nd_request_barang where id = ".$request_barang_id);
            $get_data = $this->common_model->db_select("nd_request_barang where YEAR(tanggal) ='".$year."' ORDER BY no_request DESC LIMIT 1");
            foreach ($get_data as $row) {
                $no_request = $row->no_request + 1;
            }

            $data_closed = array(
                'closed_date' => date('Y-m-d H:i:s'));
            $this->common_model->db_update("nd_request_barang", $data_closed, 'id', $request_barang_id);

            $data = array(
                'no_request' => $no_request ,
                'tanggal' => $tanggal,
                'supplier_id' => $supplier_id,
                'user_id' => is_user_id()
            );
            $request_barang_id = $this->common_model->db_insert("nd_request_barang", $data);
        }


        $data_batch = array(
            'request_barang_id' => $request_barang_id,
            'batch' => $no_batch,
            'tanggal' => $tanggal,
            'user_id' => is_user_id(),
            'attn' => $attn
            );
        
        

        // print_r($data_batch);

        $request_barang_batch_id = $this->common_model->db_insert("nd_request_barang_batch", $data_batch);
        redirect(is_setting_link('transaction/request_barang')."?id=".$barang_id."&request_barang_batch_id=".$request_barang_batch_id);
    }

    function new_request_insert2(){
        $request_barang_id = $this->input->post('request_barang_id');
        $request_barang_batch_id = $this->input->post('request_barang_batch_id');
        $bulan_awal_request = $this->input->post('bulan_awal_request');

        $data_before = $this->get_last_request = $this->tr_model->get_last_request_rekap($request_barang_id, $bulan_awal_request);

        $barang_id_list = array();
        $barang_beli_id_list = array();
        $req_list = array();
        foreach ($data_before as $row) {
            array_push($barang_id_list, $row->barang_id);
            $req_list[$row->barang_id][$row->warna_id][$row->bulan_request] = $row;
        }

        
        $barang_id_list =array_unique($barang_id_list); 
        // echo implode(",",$barang_id_list);
        $get_po = array();
        if (count($barang_id_list) > 0) {
            $get_po = $this->tr_model->get_po_outstanding_for_new_request(implode(",",$barang_id_list));
        }
        // print_r();

        $outs_list = array();
        foreach ($get_po as $row) {
            if (isset($req_list[$row->barang_id][$row->warna_id])) {

                $qty_data = explode(",",$row->qty_data);
                $po_qty = explode(",",$row->po_qty);
                $po_number = explode("??",$row->po_number);
                $po_pembelian_batch_id = explode(",",$row->po_pembelian_batch_id);
                
                $qty_list = array();
                foreach ($qty_data as $key => $value) {
                    array_push($qty_list, array(
                        "qty" => $value,
                        "po_pembelian_batch_id" => $po_pembelian_batch_id[$key],
                        'po_qty' => $po_qty[$key],
                        'po_number' => $po_number[$key],
                        'tamb' => ceil($po_qty[$key] * 0.1),
                        'sisa' => (CEIL($value/100) * 100)
                    ));
                }
                $outs_list[$row->barang_id][$row->warna_id] = $qty_list;
            }
        }

        $po_get_list = array();
        foreach ($req_list as $i1 => $v1) {
            //i1 barang_id
            foreach ($v1 as $i2 => $v2) {
                //i2 warna_id
                foreach ($v2 as $key => $value) {
                    $po_baru = 0;
                    $qty_req = $value->qty;
                    // $set_po[$i1][$i2][$value->bulan_request] = array();
                    $set_po = array();
                    if (isset($outs_list[$i1][$i2])) {
                        foreach ($outs_list[$i1][$i2] as $x => $y) {
                            $qty_ambil = 0;
                            if ($qty_req > 0 && $y['sisa'] > 0) {
                                if ($y['sisa'] >= $qty_req) {
                                    $qty_ambil = $qty_req;
                                    $y['sisa'] -= $qty_req;
                                    $qty_req = 0;
                                }else{
                                    $qty_ambil = $y['sisa'];
                                    $qty_req -= $y['sisa'] ;
                                    $y['sisa'] = 0;
                                }
                            }
                            
                            if ($x == count($outs_list[$i1][$i2])-1 && $y['sisa'] > 0 && $qty_req > 0 ) {
                                if ($y['tamb'] >= $qty_req) {
                                    $qty_ambil += $qty_req;
                                    $qty_req = 0;
                                }
                            }

                            if ($qty_ambil > 0 ) {
                                array_push($set_po,array(
                                    "po_pembelian_batch_id" => $y['po_pembelian_batch_id'],
                                    "po_number" => $y["po_number"],
                                    'qty' => $qty_ambil
                                ));
                            }
                        }
                    }

                    if ($qty_req > 0){
                        array_push($set_po,array(
                            "po_pembelian_batch_id" => 0,
                            "po_number" => "PO BARU",
                            'qty' => $qty_req
                        ));
                    }

                    $po_get_list[$i1][$i2][$value->bulan_request] = $set_po;
                }

            }
        }


        $no_request = 1;
        $get_latest = $this->common_model->db_select("nd_request_barang ORDER BY no_request DESC LIMIT 1");
        foreach ($get_latest as $row) {
            $no_request = $row->no_request + 1;
        }

        $new_request = array(
            'tanggal' => is_date_formatter($this->input->post('tanggal')) ,
            'supplier_id' => $this->input->post('supplier_id'),
            'no_request' => $no_request,
            'user_id' => is_user_id()
        );

        $request_barang_id_new = $this->common_model->db_insert("nd_request_barang", $new_request);

        $new_request_batch = array(
            'tanggal' => is_date_formatter($this->input->post('tanggal')) ,
            'request_barang_id' => $request_barang_id_new,
            'batch' => 1,
            'user_id' => is_user_id()
        );
        $request_barang_batch_id_new = $this->common_model->db_insert("nd_request_barang_batch", $new_request_batch);

        $data_request_qty = array();
        $data_request_detail = array();
        // echo "<hr/>";   
        
        $idx2 = 0;
        foreach($req_list as $barang_id => $v1){
            foreach ($v1 as $warna_id => $v2) {
                if (!isset($po_get_list[$barang_id][$warna_id])) {
                    foreach ($v2 as $bulan_request => $value) {
                        // echo $barang_id.' '.$warna_id.' '.$bulan_request.' '.$value;
                        $set_po = array(
                            "po_pembelian_batch_id" => 0,
                            "po_number" => "PO BARU",
                            'qty' => $value
                        );
                        $po_get_list[$barang_id][$warna_id][$bulan_request] = $set_po;
                        // echo ' <hr/>';
                    }
                }

                foreach ($v2 as $bulan_request => $value) {
                    $data_request_qty[$idx2] = array(
                        "request_barang_batch_id" => $request_barang_batch_id_new,
                        "bulan_request" => $bulan_request,
                        "barang_id" => $barang_id,   
                        "warna_id" => $warna_id,
                        "qty" => $value->qty,
                        "finished_date" => $value->finished_date,
                        "finished_by" => $value->finished_by
                    );
                    $idx2++;
                }
            }
        }

        if (count($data_request_qty) > 0) {
            $this->common_model->db_insert_batch("nd_request_barang_qty",$data_request_qty);
        }

        
        $idx = 0;
        foreach ($po_get_list as $barang_id => $v) {
            foreach ($v as $warna_id => $v2) {
                
                foreach ($v2 as $bulan_request => $v3) {
                    foreach ($v3 as $index => $val) {
                        $data_request_detail[$idx] = array(
                            "request_barang_batch_id" => $request_barang_batch_id_new, 
                            "po_pembelian_batch_id" => $val['po_pembelian_batch_id'],
                            "barang_id" => $barang_id,
                            "warna_id" => $warna_id,
                            "qty" => $val['qty'],
                            "bulan_request" => $bulan_request,
                            "is_po_baru" => ($val['po_pembelian_batch_id'] == 0 ? '1' : '0')
                        );
                        $idx++;
                    }
                }
            }
        }
        
        if (count($data_request_detail) > 0) {
            $this->common_model->db_insert_batch("nd_request_barang_detail",$data_request_detail);
        }
        redirect(is_setting_link('transaction/request_barang'));

        // $data['data_outstanding_po'] = $this->tr_model->get_outstanding_barang_for_request_2($request_barang_id, $barang_id, $tanggal_request);
    }

    function new_request_insert_bybeli(){
        // print_r($this->input->post());


        $dt_update = array(
            'status' => 0,
        );

        $request_barang_id = $this->input->post('request_barang_id');
        $request_barang_batch_id = $this->input->post('request_barang_batch_id');
        $bulan_awal_request = $this->input->post('bulan_awal_request');

        // foreach ($this->common_model->db_select() as $row) {
        //     # code...
            
        // }

        $this->common_model->db_update("nd_request_barang_batch",$dt_update,"id",$request_barang_batch_id);
        $type = $this->input->post('type');
        
        $no_batch = 1;
        $attn = '';
        
        $get_batch = $this->common_model->db_select("nd_request_barang_batch where id = ".$request_barang_batch_id);
        foreach ($get_batch as $row) {
            $attn = $row->attn;
        }

        //====asign baru====
        $no_request = 1;
        $tanggal =  is_date_formatter($this->input->post('tanggal'));
        $supplier_id = $this->input->post('supplier_id');
        $year =  date('Y' , strtotime($tanggal));
        if ($type == 2) {
            $get_batch = $this->common_model->db_select("nd_request_barang_batch where request_barang_id = ".$request_barang_id." ORDER BY batch desc LIMIT 1");
            foreach ($get_batch as $row) {
                $no_batch = $row->batch+1;
            }

            if ($this->input->post('po_baru_assign') != '') {
                // echo '1';
                $this->assign_po_baru_to_po_number($this->input->post('po_baru_assign')); 
                // print_r($this->input->post('po_lock_assign'));  
            }

            if ($this->input->post('po_lock_assign') != '') {
                // echo '2';
                $this->assign_penyesuaian_po_lock($this->input->post('po_lock_assign'));   
                // print_r($this->input->post('po_lock_assign'));  
            }

        }else{
            // $get_data = $this->common_model->db_select("nd_request_barang where id = ".$request_barang_id);
            $get_data = $this->common_model->db_select("nd_request_barang where YEAR(tanggal) ='".$year."' ORDER BY no_request DESC LIMIT 1");
            foreach ($get_data as $row) {
                $no_request = $row->no_request + 1;
            }

            $data_closed = array(
                'closed_date' => date('Y-m-d H:i:s'));
            $this->common_model->db_update("nd_request_barang", $data_closed, 'id', $request_barang_id);

            $data = array(
                'no_request' => $no_request ,
                'tanggal' => $tanggal,
                'supplier_id' => $supplier_id,
                'user_id' => is_user_id()
            );
            $request_barang_id = $this->common_model->db_insert("nd_request_barang", $data);
        }


        $data_batch = array(
            'request_barang_id' => $request_barang_id,
            'batch' => $no_batch,
            'tanggal' => $tanggal,
            'user_id' => is_user_id(),
            'attn' => $attn
            );
        
        

        // print_r($data_batch);

        $request_barang_batch_id = $this->common_model->db_insert("nd_request_barang_batch", $data_batch);
        redirect(is_setting_link('transaction/request_barang')."?id=".$barang_beli_id."&request_barang_batch_id=".$request_barang_batch_id);
    }

    function new_request_insert2_bybeli(){
        $request_barang_id = $this->input->post('request_barang_id');
        $request_barang_batch_id = $this->input->post('request_barang_batch_id');
        $bulan_awal_request = $this->input->post('bulan_awal_request');

        $data_before = $this->get_last_request = $this->tr_model->get_last_request_rekap($request_barang_id, $bulan_awal_request);

        $barang_id_list = array();
        $barang_beli_id_list = array();
        $req_list = array();
        $req_list_beli = array();
        foreach ($data_before as $row) {
            // array_push($barang_id_list, $row->barang_id);
            // $req_list[$row->barang_id][$row->warna_id][$row->bulan_request] = $row;

            array_push($barang_beli_id_list, $row->barang_beli_id);
            $req_list_beli[$row->barang_beli_id][$row->warna_id][$row->bulan_request] = $row;
        }

        $barang_beli_id_list =array_unique($barang_beli_id_list); 
        $get_po_beli = array();
        if (count($barang_beli_id_list) > 0) {
            $get_po_beli = $this->tr_model->get_po_outstanding_for_new_request_beli(implode(",",$barang_beli_id_list));
            
        }
        
        // print_r($get_po_beli);

        if (is_posisi_id() == 1) {
            
            $outs_list_beli = array();
            foreach ($get_po_beli as $row) {
                if (isset($req_list_beli[$row->barang_beli_id][$row->warna_id])) {
    
                    $qty_data = explode(",",$row->qty_data);
                    $po_qty = explode(",",$row->po_qty);
                    $po_number = explode("??",$row->po_number);
                    $po_pembelian_batch_id = explode(",",$row->po_pembelian_batch_id);
                    
                    $qty_list = array();
                    foreach ($qty_data as $key => $value) {
                        array_push($qty_list, array(
                            "qty" => $value,
                            "po_pembelian_batch_id" => $po_pembelian_batch_id[$key],
                            'po_qty' => $po_qty[$key],
                            'po_number' => (isset($po_number[$key]) ? $po_number[$key] : null),
                            'tamb' => ceil($po_qty[$key] * 0.1),
                            'sisa' => (CEIL($value/100) * 100)
                        ));
                    }
                    $outs_list_beli[$row->barang_beli_id][$row->warna_id] = $qty_list;
                }
            }

            // foreach($outs_list_beli as $key => $value){
            //     echo $key.'<br/>';
            //     foreach($value as $k2 => $v2){
            //         echo $k2.'<br/>';
            //         echo json_encode($v2);
            //         echo $k2.'<br/>';

            //     }
            //     echo '<hr/> ';
            // };
    
            $po_get_list_beli = array();
            foreach ($req_list_beli as $i1 => $v1) {
                //i1 barang_beli_id
                foreach ($v1 as $i2 => $v2) {
                    //i2 warna_id
                    foreach ($v2 as $key => $value) {
                        $po_baru = 0;
                        $qty_req = $value->qty;
                        // $set_po[$i1][$i2][$value->bulan_request] = array();
                        $set_po = array();
                        if (isset($outs_list_beli[$i1][$i2])) {
                            foreach ($outs_list_beli[$i1][$i2] as $x => $y) {
                                $qty_ambil = 0;
                                if ($qty_req > 0 && $y['sisa'] > 0) {
                                    if ($y['sisa'] >= $qty_req) {
                                        $qty_ambil = $qty_req;
                                        $y['sisa'] -= $qty_req;
                                        $qty_req = 0;
                                    }else{
                                        $qty_ambil = $y['sisa'];
                                        $qty_req -= $y['sisa'] ;
                                        $y['sisa'] = 0;
                                    }
                                }
                                
                                if ($x == count($outs_list_beli[$i1][$i2])-1 && $y['sisa'] > 0 && $qty_req > 0 ) {
                                    if ($y['tamb'] >= $qty_req) {
                                        $qty_ambil += $qty_req;
                                        $qty_req = 0;
                                    }
                                }
    
                                if ($qty_ambil > 0 ) {
                                    array_push($set_po,array(
                                        "po_pembelian_batch_id" => $y['po_pembelian_batch_id'],
                                        "po_number" => $y["po_number"],
                                        "barang_id" => $value->barang_id, 
                                        'qty' => $qty_ambil
                                    ));
                                }
                            }
                        }
    
                        if ($qty_req > 0){
                            array_push($set_po,array(
                                "po_pembelian_batch_id" => 0,
                                "po_number" => "PO BARU",
                                "barang_id" => $value->barang_id, 
                                'qty' => $qty_req
                            ));
                        }
    
                        $po_get_list_beli[$i1][$i2][$value->bulan_request] = $set_po;
                    }
    
                }
            }

            // foreach($po_get_list_beli as $key => $value){
            //     echo $key.'<br/>';
            //     foreach($value as $k2 => $v2){
            //         echo $k2.'<br/>';
            //         echo json_encode($v2);
            //     }
            //     echo '<hr/> ';
            // };

    
    
            $no_request = 1;
            $get_latest = $this->common_model->db_select("nd_request_barang ORDER BY no_request DESC LIMIT 1");
            foreach ($get_latest as $row) {
                $no_request = $row->no_request + 1;
            }
    
            $new_request = array(
                'tanggal' => is_date_formatter($this->input->post('tanggal')) ,
                'supplier_id' => $this->input->post('supplier_id'),
                'no_request' => $no_request,
                'user_id' => is_user_id()
            );
    
            $request_barang_id_new = $this->common_model->db_insert("nd_request_barang", $new_request);
            // $request_barang_id_new = 37;
    
            $new_request_batch = array(
                'tanggal' => is_date_formatter($this->input->post('tanggal')) ,
                'request_barang_id' => $request_barang_id_new,
                'batch' => 1,
                'user_id' => is_user_id()
            );
            $request_barang_batch_id_new = $this->common_model->db_insert("nd_request_barang_batch", $new_request_batch);
            // $request_barang_batch_id_new=72;
    
            $data_request_qty = array();
            $data_request_detail = array();
            // echo "<hr/>";   
            
            $data_request_qty_beli = array();
            $data_request_detail_beli = array();
            
    
            $idx2 = 0;
            foreach($req_list_beli as $barang_beli_id => $v1){
                foreach ($v1 as $warna_id => $v2) {
                    if (!isset($po_get_list_beli[$barang_beli_id][$warna_id])) {
                        foreach ($v2 as $bulan_request => $value) {
                            // echo $barang_id.' '.$warna_id.' '.$bulan_request.' '.$value;
                            $set_po = array(
                                "po_pembelian_batch_id" => 0,
                                "barang_id" => $barang_id, 
                                "po_number" => "PO BARU",
                                'qty' => $value
                            );
                            $po_get_list_beli[$barang_beli_id][$warna_id][$bulan_request] = $set_po;
                            // echo ' <hr/>';
                        }
                    }
    
                    foreach ($v2 as $bulan_request => $value) {
                        $data_request_qty_beli[$idx2] = array(
                            "request_barang_batch_id" => $request_barang_batch_id_new,
                            "bulan_request" => $bulan_request,
                            "barang_beli_id" => $barang_beli_id,   
                            "barang_id" => $value->barang_id,   
                            "warna_id" => $warna_id,
                            "qty" => $value->qty,
                            "finished_date" => $value->finished_date,
                            "finished_by" => $value->finished_by
                        );
                        $idx2++;
                    }
                }
            }
    
            if (count($data_request_qty_beli) > 0) {
                $this->common_model->db_insert_batch("nd_request_barang_qty",$data_request_qty_beli);
            }
    
          
    
            $idx = 0;
            foreach ($po_get_list_beli as $barang_beli_id => $v) {
                foreach ($v as $warna_id => $v2) {
                    
                    foreach ($v2 as $bulan_request => $v3) {
                        foreach ($v3 as $index => $val) {
                            $data_request_detail[$idx] = array(
                                "request_barang_batch_id" => $request_barang_batch_id_new, 
                                "po_pembelian_batch_id" => $val['po_pembelian_batch_id'],
                                "barang_beli_id" => $barang_beli_id,
                                "barang_id" => $val['barang_id'],
                                "warna_id" => $warna_id,
                                "qty" => $val['qty'],
                                "bulan_request" => $bulan_request,
                                "is_po_baru" => ($val['po_pembelian_batch_id'] == 0 ? '1' : '0')
                            );
                            $idx++;
                        }
                    }
                }
            }
            
            // echo json_encode($po_get_list_beli);
            if (count($data_request_detail) > 0) {
                $this->common_model->db_insert_batch("nd_request_barang_detail",$data_request_detail);
            }
            
            redirect(is_setting_link('transaction/request_barang'));

        }else if (is_posisi_id() != 1) {
            
            $outs_list_beli = array();
            foreach ($get_po_beli as $row) {
                if (isset($req_list_beli[$row->barang_beli_id][$row->warna_id])) {
    
                    $qty_data = explode(",",$row->qty_data);
                    $po_qty = explode(",",$row->po_qty);
                    $po_number = explode("??",$row->po_number);
                    $po_pembelian_batch_id = explode(",",$row->po_pembelian_batch_id);
                    
                    $qty_list = array();
                    foreach ($qty_data as $key => $value) {
                        array_push($qty_list, array(
                            "qty" => $value,
                            "po_pembelian_batch_id" => $po_pembelian_batch_id[$key],
                            'po_qty' => $po_qty[$key],
                            'po_number' => (isset($po_number[$key]) ? $po_number[$key] : null),
                            'tamb' => ceil($po_qty[$key] * 0.1),
                            'sisa' => (CEIL($value/100) * 100)
                        ));
                    }
                    $outs_list_beli[$row->barang_beli_id][$row->warna_id] = $qty_list;
                }
            }
    
            $po_get_list_beli = array();
            foreach ($req_list_beli as $i1 => $v1) {
                //i1 barang_id
                foreach ($v1 as $i2 => $v2) {
                    //i2 warna_id
                    foreach ($v2 as $key => $value) {
                        $po_baru = 0;
                        $qty_req = $value->qty;
                        // $set_po[$i1][$i2][$value->bulan_request] = array();
                        $set_po = array();
                        if (isset($outs_list[$i1][$i2])) {
                            foreach ($outs_list[$i1][$i2] as $x => $y) {
                                $qty_ambil = 0;
                                if ($qty_req > 0 && $y['sisa'] > 0) {
                                    if ($y['sisa'] >= $qty_req) {
                                        $qty_ambil = $qty_req;
                                        $y['sisa'] -= $qty_req;
                                        $qty_req = 0;
                                    }else{
                                        $qty_ambil = $y['sisa'];
                                        $qty_req -= $y['sisa'] ;
                                        $y['sisa'] = 0;
                                    }
                                }
                                
                                if ($x == count($outs_list[$i1][$i2])-1 && $y['sisa'] > 0 && $qty_req > 0 ) {
                                    if ($y['tamb'] >= $qty_req) {
                                        $qty_ambil += $qty_req;
                                        $qty_req = 0;
                                    }
                                }
    
                                if ($qty_ambil > 0 ) {
                                    array_push($set_po,array(
                                        "po_pembelian_batch_id" => $y['po_pembelian_batch_id'],
                                        "po_number" => $y["po_number"],
                                        'qty' => $qty_ambil
                                    ));
                                }
                            }
                        }
    
                        if ($qty_req > 0){
                            array_push($set_po,array(
                                "po_pembelian_batch_id" => 0,
                                "po_number" => "PO BARU",
                                "barang_id" => $value->barang_id, 
                                'qty' => $qty_req
                            ));
                        }
    
                        $po_get_list_beli[$i1][$i2][$value->bulan_request] = $set_po;
                    }
    
                }
            }
    
    
            $no_request = 1;
            $get_latest = $this->common_model->db_select("nd_request_barang ORDER BY no_request DESC LIMIT 1");
            foreach ($get_latest as $row) {
                $no_request = $row->no_request + 1;
            }
    
            $new_request = array(
                'tanggal' => is_date_formatter($this->input->post('tanggal')) ,
                'supplier_id' => $this->input->post('supplier_id'),
                'no_request' => $no_request,
                'user_id' => is_user_id()
            );
    
            $request_barang_id_new = $this->common_model->db_insert("nd_request_barang", $new_request);
    
            $new_request_batch = array(
                'tanggal' => is_date_formatter($this->input->post('tanggal')) ,
                'request_barang_id' => $request_barang_id_new,
                'batch' => 1,
                'user_id' => is_user_id()
            );
            $request_barang_batch_id_new = $this->common_model->db_insert("nd_request_barang_batch", $new_request_batch);
    
            $data_request_qty = array();
            $data_request_detail = array();
            // echo "<hr/>";   
            
            $data_request_qty_beli = array();
            $data_request_detail_beli = array();
            
    
            $idx2 = 0;
            foreach($req_list_beli as $barang_beli_id => $v1){
                foreach ($v1 as $warna_id => $v2) {
                    if (!isset($po_get_list_beli[$barang_beli_id][$warna_id])) {
                        foreach ($v2 as $bulan_request => $value) {
                            // echo $barang_id.' '.$warna_id.' '.$bulan_request.' '.$value;
                            $set_po = array(
                                "po_pembelian_batch_id" => 0,
                                "barang_id" => $barang_id, 
                                "po_number" => "PO BARU",
                                'qty' => $value
                            );
                            $po_get_list_beli[$barang_beli_id][$warna_id][$bulan_request] = $set_po;
                            // echo ' <hr/>';
                        }
                    }
    
                    foreach ($v2 as $bulan_request => $value) {
                        $data_request_qty_beli[$idx2] = array(
                            "request_barang_batch_id" => $request_barang_batch_id_new,
                            "bulan_request" => $bulan_request,
                            "barang_beli_id" => $barang_beli_id,   
                            "barang_id" => $value->barang_id,   
                            "warna_id" => $warna_id,
                            "qty" => $value->qty,
                            "finished_date" => $value->finished_date,
                            "finished_by" => $value->finished_by
                        );
                        $idx2++;
                    }
                }
            }
    
            if (count($data_request_qty_beli) > 0) {
                $this->common_model->db_insert_batch("nd_request_barang_qty",$data_request_qty_beli);
            }
    
          
    
            $idx = 0;
            foreach ($po_get_list_beli as $barang_beli_id => $v) {
                foreach ($v as $warna_id => $v2) {
                    
                    foreach ($v2 as $bulan_request => $v3) {
                        foreach ($v3 as $index => $val) {
                            $data_request_detail[$idx] = array(
                                "request_barang_batch_id" => $request_barang_batch_id_new, 
                                "po_pembelian_batch_id" => $val['po_pembelian_batch_id'],
                                "barang_beli_id" => $barang_beli_id,
                                "barang_id" => $val['barang_id'],
                                "warna_id" => $warna_id,
                                "qty" => $val['qty'],
                                "bulan_request" => $bulan_request,
                                "is_po_baru" => ($val['po_pembelian_batch_id'] == 0 ? '1' : '0')
                            );
                            $idx++;
                        }
                    }
                }
            }
            
            if (count($data_request_detail) > 0) {
                $this->common_model->db_insert_batch("nd_request_barang_detail",$data_request_detail);
            }
            redirect(is_setting_link('transaction/request_barang'));
        }


        // $data['data_outstanding_po'] = $this->tr_model->get_outstanding_barang_for_request_2($request_barang_id, $barang_id, $tanggal_request);
    }

    function update_keterangan_request_barang(){
        $barang_id = $this->input->post('barang_id');
        $warna_id = $this->input->post('warna_id');
        $bulan_request = $this->input->post('bulan_request');
        $request_barang_batch_id = $this->input->post('request_barang_batch_id');
        $keterangan = $this->input->post('keterangan');
        $request_barang_id = $this->input->post('request_barang_id');
        // print_r($this->input->post());

        $get = $this->tr_model->get_latest_request_qty_by_barang($request_barang_id, $barang_id, $warna_id, $bulan_request);
        $id = '';
        foreach ($get as $row) {
            $id = $row->id;
        }
        $data = array(
            'keterangan' => $keterangan, );

        if ($id != '') {
            $res = $this->common_model->db_update("nd_request_barang_qty", $data,'id', $id);
            # code...
            echo "OK";
        }else{
            echo "FAILED";
        }
        


    }

    function request_barang_update_tanggal(){
        $tanggal = is_date_formatter($this->input->post('tanggal'));
        $request_barang_id = $this->input->post('request_barang_id');
        $request_barang_batch_id = $this->input->post('request_barang_batch_id');
        $no_batch = $this->input->post('no_batch');
        $no_request = $this->input->post('no_request');
        $data = array(
            'tanggal' => $tanggal , );
        
        $time = date('H:i:s');
        $data_close = array(
            'closed_date' => $tanggal.' '.$time , );
        
        if ($no_batch == 1) {
            $no_request = $no_request - 1;
            $get = $this->common_model->db_select("nd_request_barang WHERE no_request=".$no_request);
            foreach ($get as $row) {
                $id_before = $row->id;
                $this->common_model->db_update('nd_request_barang', $data_close,'id', $id_before);
            }
        }
        $this->common_model->db_update("nd_request_barang_batch",$data,'id', $request_barang_batch_id);
        echo "OK";
    }

    function request_barang_submit_legacy(){
        // print_r($this->input->post('data'));
        // $data = array();
        $batch_id = '';
        $batch_id = $this->input->post('request_barang_batch_id');
        $barang_id = $this->input->post('barang_id');
        // echo count($this->input->post('data'));
        
        $get_data_batch = $this->common_model->db_select('nd_request_barang_batch WHERE id='.$batch_id);
        foreach ($get_data_batch as $row) {
            $no_batch = $row->batch;
            $request_barang_id = $row->request_barang_id;
        }
        $request_urgent = array();
        if ($no_batch > 1) {
            $request_urgent = $this->tr_model->get_po_request_urgent_status($request_barang_id);
            foreach ($request_urgent as $row) {
                $urg[$row->barang_id][$row->warna_id][$row->po_pembelian_batch_id][$row->bulan_request] = 1;
            }
        }
        
        if ($batch_id != '') {
            $id_filter = array();
            $get_detail = $this->common_model->db_select('nd_request_barang_detail WHERE request_barang_batch_id = '.$batch_id." AND barang_id=".$barang_id);
            foreach ($get_detail as $row) {
                $id_filter[$row->id] = $row->barang_id.' '.$row->warna_id;
            }
    
            $id_request_filter = array();
            $get_request = $this->common_model->db_select("nd_request_barang_qty WHERE request_barang_batch_id=".$batch_id." AND barang_id=".$barang_id);
            foreach ($get_request as $row) {
                $id_request_filter[$row->id] = $row->barang_id.' '.$row->warna_id;
            }
            
            $idx = 0;
            foreach ($this->input->post('data') as $key => $value) {
                
                $data_detail = array();
                $barang_id = '';
                $id_detail_list = array();
                $id_detail_delete = array();
                foreach ($value['data_barang'] as $key2 => $value2) {
                    $bulan = $key2.'-01';
                    foreach ($value2 as $key3 => $value3) {
                        if ($barang_id == '') {
                            $barang_id = $value3['barang_id'];
                        }

                        $is_po_baru = 0;
                        if ($value3['po_pembelian_batch_id'] == 0) {
                            $is_po_baru = 1;
                        }

                        $dt = array(
                            'request_barang_batch_id' => $batch_id,
                            'po_pembelian_batch_id' => $value3['po_pembelian_batch_id'],
                            'bulan_request' => $bulan,
                            'barang_id' => $value3['barang_id'],
                            'warna_id'=> $value3['warna_id'],
                            'qty' => $value3['qty'],
                            'is_po_baru' => $is_po_baru
                            );

                        
                            
                        $is_po_baru = 0;
                        if ($value3['po_pembelian_batch_id'] == 0) {
                            $is_po_baru = 1;
                        }
                        $id_detail = '';
                        foreach ($get_detail as $row) {
                            // echo $row->po_pembelian_batch_id .'=='. $value3['po_pembelian_batch_id'] .'&&'. $row->barang_id .'=='. $value3['barang_id'] .'&&'. $row->warna_id .'=='. $value3['warna_id'] .'&&'. $row->bulan_request .'=='. $value3['bulan_request'].'<br/>';
                            if ($row->po_pembelian_batch_id == $value3['po_pembelian_batch_id'] && $row->barang_id == $value3['barang_id'] && $row->warna_id == $value3['warna_id'] && $row->bulan_request == $value3['bulan_request'].'-01' ) {
                                $id_detail= $row->id;
                                array_push($id_detail_list, $id_detail);
                                unset($id_filter[$id_detail]);
                            }
                        }
                        if ($id_detail != '') {
                            $this->common_model->db_update("nd_request_barang_detail", $dt,'id', $id_detail);
                        }else{
                            if ($value3['qty'] > 0) {
                                if ($no_batch > 1) {
                                    if (isset($urg[$value3['barang_id']][$value3['warna_id']][$value3['po_pembelian_batch_id']])) {
                                        $dt['status_urgent'] = 1;
                                    }
                                }

                                $data_detail[$idx] = array(
                                    'request_barang_batch_id' => $batch_id,
                                    'po_pembelian_batch_id' => $value3['po_pembelian_batch_id'],
                                    'bulan_request' => $bulan,
                                    'barang_id' => $value3['barang_id'],
                                    'warna_id'=> $value3['warna_id'],
                                    'qty' => $value3['qty'],
                                    'is_po_baru' => $is_po_baru
                                    );
                                $idx++;
                            }
                        }
                    }
                }
    
                // print_r($data_detail);
    
                if (count($id_detail_delete) > 0) {
                    $this->common_model->remove_request_detail_of_null(implode(",", $id_detail_delete));
                }
    
                if (count($id_detail_list) > 0) {
                    $this->common_model->remove_request_detail(implode(",", $id_detail_list), $batch_id, $barang_id);
                }
    
                if (count($data_detail) > 0) {
                    // print_r($data_detail)
                    $this->common_model->db_insert_batch('nd_request_barang_detail', $data_detail);
                }
    
                $data_detail_request = array();
                $id_request_list = array();
                foreach (json_decode(stripslashes($value['data_request']),true) as $key2 => $value2) {
                    $bulan = $value2['bulan_request'].'-01'; 
    
                    $dt = array(
                        'request_barang_batch_id' => $batch_id ,
                        'bulan_request' => $bulan,
                        'barang_id' => $value2['barang_id'],
                        'warna_id' => $value2['warna_id'],
                        'qty' => $value2['qty'] 
                    );

                    $id_request = '';
                    foreach ($get_request as $row) {
                        if ($row->barang_id == $value2['barang_id'] && $row->warna_id == $value2['warna_id'] && $row->bulan_request == $bulan) {
                            $id_request = $row->id;
                            array_push($id_request_list, $id_request);
                            unset($id_request_filter[$id_request]);
                        }
                    }

                    if ($id_request != '') {
                        $this->common_model->db_update('nd_request_barang_qty', $dt, 'id', $id_request);
                    }else{
                        array_push($data_detail_request, $dt);
                    }
    
                }
    
                if (count($id_request_list) > 0) {
                    $this->common_model->remove_request_qty(implode(",", $id_request_list), $batch_id, $barang_id);
                }
    
                if (count($data_detail_request) > 0) {
                    $this->common_model->db_insert_batch("nd_request_barang_qty",$data_detail_request);
                }
            }

            // print_r($id_filter);

            foreach ($id_filter as $key => $value) {
                $this->common_model->db_delete("nd_request_barang_detail",'id', $key);
            }

            // print_r($id_request_filter);
            foreach ($id_request_filter as $key => $value) {
                $this->common_model->db_delete("nd_request_barang_qty",'id', $key);
            }
            
    
            echo "OK";
            # code...
        }else{
            echo 'ERROR';
        }
        
    }

    function request_barang_submit_beli(){
        // print_r($this->input->post('data'));
        // $data = array();
        $batch_id = '';
        $batch_id = $this->input->post('request_barang_batch_id');
        $barang_id = $this->input->post('barang_id');
        $barang_beli_id = $this->input->post('barang_beli_id');
        // echo count($this->input->post('data'));
        
        $get_data_batch = $this->common_model->db_select('nd_request_barang_batch WHERE id='.$batch_id);
        foreach ($get_data_batch as $row) {
            $no_batch = $row->batch;
            $request_barang_id = $row->request_barang_id;
        }
        $request_urgent = array();
        if ($no_batch > 1) {
            $request_urgent = $this->tr_model->get_po_request_urgent_status($request_barang_id);
            foreach ($request_urgent as $row) {
                $urg[$row->barang_beli_id][$row->warna_id][$row->po_pembelian_batch_id][$row->bulan_request] = 1;
            }
        }
        
        if ($batch_id != '') {
            $id_filter = array();
            $get_detail = $this->common_model->db_select('nd_request_barang_detail WHERE request_barang_batch_id = '.$batch_id." AND barang_beli_id=".$barang_beli_id);
            foreach ($get_detail as $row) {
                $id_filter[$row->id] = $row->barang_beli_id.' '.$row->warna_id;
            }
    
            $id_request_filter = array();
            $get_request = $this->common_model->db_select("nd_request_barang_qty WHERE request_barang_batch_id=".$batch_id." AND barang_beli_id=".$barang_beli_id);
            foreach ($get_request as $row) {
                $id_request_filter[$row->id] = $row->barang_beli_id.' '.$row->warna_id;
            }
            
            $idx = 0;
            foreach ($this->input->post('data') as $key => $value) {
                
                $data_detail = array();
                $barang_id = '';
                $barang_beli_id = '';
                $id_detail_list = array();
                $id_detail_delete = array();
                foreach ($value['data_barang'] as $key2 => $value2) {
                    $bulan = $key2.'-01';
                    foreach ($value2 as $key3 => $value3) {
                        if ($barang_beli_id == '') {
                            $barang_beli_id = $value3['barang_beli_id'];
                        }

                        $is_po_baru = 0;
                        if ($value3['po_pembelian_batch_id'] == 0) {
                            $is_po_baru = 1;
                        }

                        $dt = array(
                            'request_barang_batch_id' => $batch_id,
                            'po_pembelian_batch_id' => $value3['po_pembelian_batch_id'],
                            'bulan_request' => $bulan,
                            'barang_beli_id' => $value3['barang_beli_id'],
                            'barang_id' => $value3['barang_id'],
                            'warna_id'=> $value3['warna_id'],
                            'qty' => $value3['qty'],
                            'is_po_baru' => $is_po_baru
                            );

                        
                            
                        $is_po_baru = 0;
                        if ($value3['po_pembelian_batch_id'] == 0) {
                            $is_po_baru = 1;
                        }
                        $id_detail = '';
                        foreach ($get_detail as $row) {
                            // echo $row->po_pembelian_batch_id .'=='. $value3['po_pembelian_batch_id'] .'&&'. $row->barang_id .'=='. $value3['barang_id'] .'&&'. $row->warna_id .'=='. $value3['warna_id'] .'&&'. $row->bulan_request .'=='. $value3['bulan_request'].'<br/>';
                            if ($row->po_pembelian_batch_id == $value3['po_pembelian_batch_id'] && $row->barang_beli_id == $value3['barang_beli_id'] && $row->warna_id == $value3['warna_id'] && $row->bulan_request == $value3['bulan_request'].'-01' ) {
                                $id_detail= $row->id;
                                array_push($id_detail_list, $id_detail);
                                unset($id_filter[$id_detail]);
                            }
                        }
                        if ($id_detail != '') {
                            $this->common_model->db_update("nd_request_barang_detail", $dt,'id', $id_detail);
                        }else{
                            if ($value3['qty'] > 0) {
                                if ($no_batch > 1) {
                                    if (isset($urg[$value3['barang_beli_id']][$value3['warna_id']][$value3['po_pembelian_batch_id']])) {
                                        $dt['status_urgent'] = 1;
                                    }
                                }

                                $data_detail[$idx] = array(
                                    'request_barang_batch_id' => $batch_id,
                                    'po_pembelian_batch_id' => $value3['po_pembelian_batch_id'],
                                    'bulan_request' => $bulan,
                                    'barang_beli_id' => $value3['barang_beli_id'],
                                    'barang_id' => $value3['barang_id'],
                                    'warna_id'=> $value3['warna_id'],
                                    'qty' => $value3['qty'],
                                    'is_po_baru' => $is_po_baru
                                    );
                                $idx++;
                            }
                        }
                    }
                }
    
                // print_r($data_detail);
    
                if (count($id_detail_delete) > 0) {
                    $this->common_model->remove_request_detail_of_null(implode(",", $id_detail_delete));
                }
    
                if (count($id_detail_list) > 0) {
                    $this->common_model->remove_request_detail_beli(implode(",", $id_detail_list), $batch_id, $barang_beli_id);
                }
    
                if (count($data_detail) > 0) {
                    // print_r($data_detail)
                    $this->common_model->db_insert_batch('nd_request_barang_detail', $data_detail);
                }
    
                $data_detail_request = array();
                $id_request_list = array();
                foreach (json_decode(stripslashes($value['data_request']),true) as $key2 => $value2) {
                    $bulan = $value2['bulan_request'].'-01'; 
    
                    $dt = array(
                        'request_barang_batch_id' => $batch_id ,
                        'bulan_request' => $bulan,
                        'barang_beli_id' => $value2['barang_beli_id'],
                        'barang_id' => $value2['barang_id'],
                        'warna_id' => $value2['warna_id'],
                        'qty' => $value2['qty'] 
                    );

                    $id_request = '';
                    foreach ($get_request as $row) {
                        if ($row->barang_beli_id == $value2['barang_beli_id'] && $row->warna_id == $value2['warna_id'] && $row->bulan_request == $bulan) {
                            $id_request = $row->id;
                            array_push($id_request_list, $id_request);
                            unset($id_request_filter[$id_request]);
                        }
                    }

                    if ($id_request != '') {
                        $this->common_model->db_update('nd_request_barang_qty', $dt, 'id', $id_request);
                    }else{
                        array_push($data_detail_request, $dt);
                    }
    
                }
    
                if (count($id_request_list) > 0) {
                    $this->common_model->remove_request_qty_beli(implode(",", $id_request_list), $batch_id, $barang_beli_id);
                }
    
                if (count($data_detail_request) > 0) {
                    $this->common_model->db_insert_batch("nd_request_barang_qty",$data_detail_request);
                }
            }

            // print_r($id_filter);

            foreach ($id_filter as $key => $value) {
                $this->common_model->db_delete("nd_request_barang_detail",'id', $key);
            }

            // print_r($id_request_filter);
            foreach ($id_request_filter as $key => $value) {
                $this->common_model->db_delete("nd_request_barang_qty",'id', $key);
            }
            
    
            echo "OK";
            # code...
        }else{
            echo 'ERROR';
        }
        
    }

    function request_barang_lock(){
        $request_barang_batch_id = $this->input->post('request_barang_batch_id');
        $tipe = $this->input->post('tipe');
        if ($tipe == 1) {
            $data = array(
                'locked_by' => is_user_id() ,
                'locked_date' => date("Y-m-d H:i:s") );
        }else if ($tipe == 2) {
            $data = array(
                'locked_by' => null ,
                'locked_date' => null );
        }

        $this->common_model->db_update("nd_request_barang_batch", $data, 'id', $request_barang_batch_id);
        echo "OK";

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

    function update_status_urget_request_barang(){
        $request_barang_detail_id = $this->input->post('request_barang_detail_id');
        $data = array(
            'status_urgent' => $this->input->post('status_urgent') , );
        // $get=$this->common_model->db_select("nd_request_barang_detail WHERE");
        $this->common_model->db_update("nd_request_barang_detail",$data,'id',$request_barang_detail_id);
        echo 'OK';
    }

    function update_status_finished_request_barang(){
        $request_barang_qty_id = $this->input->post('request_barang_qty_id');
        $unfinished = $this->input->post('unfinished');
        if ($unfinished == '') {
            $data = array(
                'finished_by' => is_user_id() ,
                'finished_date' => date('Y-m-d H:i:s'));
        }else{
            $data = array(
                'finished_by' => null,
                'finished_date' => null);
        }
        $this->common_model->db_update("nd_request_barang_qty",$data,'id',$request_barang_qty_id);
        echo 'OK';
    }

    function generate_request_barang_pdf(){

        $no_batch = 0; 
        $no_request = 0;
        $request_barang_batch_id = 0;
        $request_barang_id = 0;
        $request_batch = array();

        //==============================request section=======================================
        if ($this->input->get('request_barang_batch_id') != '') {
            $request_barang_batch_id = $this->input->get('request_barang_batch_id');
        }
        if ($request_barang_batch_id == 0) {
            $request_batch = $this->common_model->db_select("nd_request_barang_batch WHERE status = 1");
        }else{
            $request_batch = $this->common_model->db_select("nd_request_barang_batch WHERE id=".$request_barang_batch_id);
        }
        foreach ($request_batch as $row) {
            $request_barang_batch_id = $row->id;
            $request_barang_id = $row->request_barang_id;
        }
        
        $tanggal_request = date('Y-m-d');
        $tahun = date('Y');
        $request_barang_data = $this->tr_model->get_request_barang_data($request_barang_batch_id);
        
        $no_batch = 1;
        $closed_date = date('Y-m-d 23:59:59');
        foreach ($request_barang_data as $row) {
            $tanggal_request = $row->tanggal;
            $supplier_id=  $row->supplier_id;
            $tahun = substr($row->tanggal, 0,4);
            $no_batch = $row->batch;
            if ($row->closed_date != '') {
                $closed_date = $row->closed_date;
            }
        }

        $supplier_id = $this->input->get('supplier_id');
        $supplier_data = $this->common_model->db_select("nd_supplier where id=$supplier_id");
        $cond_supplier = "WHERE supplier_id = $supplier_id";
        
        //==============================barang section=======================================
        $barang_id = $this->input->get('id');
        // $tahun = date('Y') - 1;
        
        $barang_id = ($barang_id == '' ? 0 : $barang_id);
        $tanggal_start = date('Y-m-01');
        $tanggal_end = date('Y-m-01', strtotime('+5 months'));
        // $tanggal_end = date('Y-12-31');
        
        $tanggal_start_forecast = date('Y-m-01',strtotime($tanggal_start.' -1 year'));
        $tanggal_end_forecast = date('Y-m-t',strtotime($tanggal_end.' -1 year'));
        
        if($this->input->get('tanggal_start') != '' && $this->input->get('tanggal_end') != ''){
            $tanggal_start = date('Y-m-01', strtotime($this->input->get('tanggal_start')));
            $tanggal_end = date('Y-m-t', strtotime($this->input->get('tanggal_end')));
            
            $tanggal_start_forecast = date('Y-m-01',strtotime($tanggal_start.' -1 year'));;
            $tanggal_end_forecast = date('Y-m-t',strtotime($tanggal_end.' -1 year'));
        }

        $data = array(
            'content' =>'admin/transaction/barang_request_pdf' ,
            'barang_id' => $barang_id,
            'tanggal_start' => $tanggal_start,
            'tanggal_end' => $tanggal_end,
            'tanggal_start_forecast' => $tanggal_start_forecast,
            'request_barang_data' => $request_barang_data,
            'supplier_data' => $supplier_data,
            'tanggal_end_forecast' => $tanggal_end_forecast);
        $data['user_id'] = is_user_id();
        $data['data_barang'] = $this->common_model->db_select('nd_barang where id='.$barang_id); 
        $data['data_warna'] = $this->common_model->get_warna_asosiasi($barang_id);
        $data['data_penjualan'] = $this->common_model->get_penjualan_for_forecast($barang_id, $tanggal_start_forecast, $tanggal_request);
        // $data['data_pembelian_now'] = $this->common_model->get_data_pembelian_by_date($barang_id, $tanggal_start, $tanggal_end);
        // $data['penjualan_berjalan'] = $this->common_model->get_penjualan_bulan_berjalan($barang_id, $tanggal_start, $tanggal_end);

        $tanggal_awal = '2018-01-01';
        $stok_opname_id = 1;
        $getOpname = $this->common_model->db_select("nd_stok_opname where tanggal <= '".date('Y-m-d')."' order by tanggal desc limit 1");
        foreach ($getOpname as $row) {
            // $tanggal_awal = $row->tanggal;
            // $stok_opname_id = $row->id;
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
        $select = $this->generate_select_versi_2();
        $data['penjualan_berjalan'] = $this->common_model->get_penjualan_bulan_berjalan($barang_id, $tanggal_start, $tanggal_end);
        $data['request_barang_status'] = $this->tr_model->get_request_barang_qty_status($request_barang_id);
        

        $request_barang_id_before = 0; $data['request_barang_qty_all'] = array();
        $data['request_barang_qty_all'] = array();
        if ($no_batch > 1 ) {
            $data['request_barang_qty_all'] = $this->tr_model->get_request_barang_qty_aktif($request_barang_batch_id,$request_barang_id);
        }

        
        $data['data_outstanding_po'] = $this->common_model->get_outstanding_barang($barang_id, $tanggal_request);
        $data['stok_barang'] = $this->inv_model->get_stok_barang_list_by_barang_temp_2($tanggal_awal, $select, $tanggal_request, "AND barang_id=$barang_id", $stok_opname_id); 

        $data['data_outstanding_update'] = array();
        $data['stok_barang_update'] = array();
        // if ($tanggal_request != date('Y-m-d')) {
        //     $data['data_outstanding_update'] = $this->common_model->get_outstanding_barang($barang_id, date('Y-m-d'));
        //     $data['stok_barang_update'] = $this->inv_model->get_stok_barang_list_by_barang_temp_2($tanggal_awal, $select, date('Y-m-d'), "AND barang_id=$barang_id", $stok_opname_id); 
        // }else{
        //     $data['data_outstanding_update'] = $data['data_outstanding_po'];
        //     $data['stok_barang_update'] = $data['stok_barang'];
        // }
        

        $data['request_barang_qty'] = $this->tr_model->get_request_barang_qty($barang_id, $request_barang_batch_id, $request_barang_id);
        
        // $cond_supplier = "";
        // $supplier_id = 0;
        $data['request_barang_detail'] = $this->tr_model->get_active_request_barang_by_request_2($request_barang_id, $request_barang_batch_id, $cond_supplier, $supplier_id);
        $data['barang_terkirim'] = array();
        if ($no_batch > 1 ) {
            // $data['barang_terkirim'] = $this->tr_model->get_request_barang_terkirim($request_barang_id, $closed_date);
            $data['barang_terkirim'] = $this->tr_model->get_request_barang_terkirim_2($request_barang_id, $closed_date);
        }
        
        $get_request = $this->common_model->db_select('nd_request_barang_batch where id='.$request_barang_batch_id);
        foreach ($get_request as $row) {
            $request_id = $row->request_barang_id;
        }
        $data['request_barang_qty_data'] = $this->tr_model->get_request_barang_qty_data($request_id,$request_barang_batch_id, $barang_id);
        $data['toko_data'] = $this->common_model->db_select('nd_toko where id=1');
        $data['barang_group'] = $this->common_model->get_barang_group_list();
        $data['request_barang_qty_keterangan'] = $this->tr_model->get_request_barang_qty_keterangan($request_barang_id);
        // $data['barang_group'] = array();

        $this->load->library('fpdf17/fpdf_css');
        $this->load->library('fpdf17/fpdf');

        $data['data_view'] = 0;
        if ($this->input->get('data_view') != '') {
            $data['data_view'] = $this->input->get('data_view');
        }

        if(is_posisi_id() == 1){
            // print_r($data['barang_terkirim']);
            $this->load->view('admin/transaction/barang_request_pdf_2',$data);
            // echo $cond_supplier;
            // print_r($data['request_barang_detail']);
            // $this->output->enable_profiler(TRUE);
        }else{
            $this->load->view('admin/transaction/barang_request_pdf_2',$data);
        }


        // else{
        //  echo $tanggal_start_forecast, $tanggal_end_forecast;
        //  print_r($data['data_penjualan']);
        // }
    }

    function po_baru_list_manage_info(){
        $po_baru_list = $this->input->post('po_baru_list');
        $supplier_id = $this->input->post('supplier_id');
        $tanggal = $this->input->post('tanggal');
        $tanggal_end = is_date_formatter($tanggal);   
        $tipe = $this->input->post('tipe');
        $request_barang_batch_id = $this->input->post('request_barang_batch_id');
        $request_barang_id = $this->input->post('request_barang_id');
        $get = $this->common_model->db_select("nd_request_barang_batch WHERE id < $request_barang_batch_id ORDER BY id DESC LIMIT 1");
        foreach ($get as $row) {
            $tanggal = $row->tanggal;
        }    

        if ($tipe == 1) {
            $tanggal_end = date('Y-m-d');
            // $request_barang_batch_id = 0;
        }
        
        $cond = "WHERE";
        $cond_list = array();
        foreach ($po_baru_list as $key => $value) {
            array_push($cond_list, " (barang_id = ".$value['barang_id']." AND warna_id = ".$value['warna_id'] .")");
        }

        $cond .= " ( ".implode(" OR ", $cond_list)." )";
        $get_po_pembelian_baru = $this->tr_model->get_po_pembelian_for_po_baru($tanggal, $tanggal_end, $supplier_id, $cond, $request_barang_batch_id, $request_barang_id);

        $return['tanggal'] = date('d F Y',strtotime($tanggal));
        $return['data'] = $get_po_pembelian_baru; 
        $return['count'] = count($get_po_pembelian_baru); 
        // echo $cond;
        echo json_encode($return);
    }

    function update_request_barang_attn()
    {
        $request_barang_batch_id = $this->input->post('request_barang_batch_id');
        $data = array(
            'attn' => $this->input->post('attn') );
        $this->common_model->db_update("nd_request_barang_batch",$data,'id', $request_barang_batch_id);
        echo "OK";
    }

    function assign_po_locked_to_new_po(){
        $request_barang_id = $this->input->post('request_barang_id');
        $tanggal_start = $this->input->post('tanggal_awal');
        $get_locked_po = $this->tr_model->get_updated_locked_po_request($request_barang_id, $tanggal_start);
        $req_detail_id = array();
        $req_batch_id = array();
        $set = array();
        $nrd = array();
        $text = '';
        $idx_all = count($get_locked_po);
        $return = array();
        foreach ($get_locked_po as $row) {
            if (!isset($set[$row->request_barang_batch_id])) {
                $set[$row->request_barang_batch_id] = array();
            }
            // $idx_all++;
            array_push($set[$row->request_barang_batch_id], array(
                'barang_id' => $row->barang_id,
                'warna_id'=> $row->warna_id,
                'data'=> $row));
            
            if (!isset($updated[$row->request_barang_batch_id][$row->barang_id][$row->warna_id])) {
                # code...
                $g = $this->common_model->db_select("nd_request_barang_detail WHERE request_barang_batch_id=".$row->request_barang_batch_id." AND barang_id = ".$row->barang_id." AND warna_id = ".$row->warna_id.' ORDER BY bulan_request ASC ');
                $r = $this->common_model->db_select("nd_request_barang_qty WHERE request_barang_batch_id=".$row->request_barang_batch_id." AND barang_id = ".$row->barang_id." AND warna_id = ".$row->warna_id.' ORDER BY bulan_request ASC ');
                // print_r($gdata);
                // echo '<hr/>';
                // print_r($rdata);
                // echo '<hr/>';
                // print_r($value[$i]['data']);

                foreach ($g as $row2) {
                    $qb[$row2->po_pembelian_batch_id][$row2->bulan_request] = $row2->qty;
                    $idb[$row2->po_pembelian_batch_id][$row2->bulan_request] = $row2->id;
                }
                $npo = explode(',', $row->po_number_baru);
                $qou = explode(',', $row->qty_outstanding);
                $pqty = explode(',', $row->po_qty);
                $pbid = explode(',', $row->po_pembelian_batch_id_baru);
                // foreach ($g as $row2) {
                //     $gdata[$row2->bulan_request][$row2->po_pembelian_batch_id] = $row2->qty;
                // }
                
                $text .= "<span style='font-size:1.1em'>LOCK PO <b>".$row->nama_barang.' '.$row->nama_warna.'</b> PO: '.$row->po_number."</span><br/>";
                $idx = 0;
                $text .= '<ul>';
                $newSet = array();
                foreach ($r as $row2) {
                    $qty_request = $row2->qty;
                    if ($row->bulan_request == $row2->bulan_request || $idx == 1) {
                        $sisa_value = 0;
                        foreach ($qou as $key => $value) {
                            if ($value > 0) {
                                // echo $row->nama_barang.' '.$row->nama_warna.$qty_request;
                                $idb2 = '';
                                if ($qty_request - $value >= 0 ) {
                                    // echo 1;
                                    $nrd[$row->request_barang_batch_id][$row->barang_id][$row->warna_id][$row->bulan_request][$pbid[$key]]['qty'] = $value;
                                    $nrd[$row->request_barang_batch_id][$row->barang_id][$row->warna_id][$row->bulan_request][$pbid[$key]]['po_number'] = $npo[$key];
                                    $qty_request -= $value;
                                    $qbefore = ' 0 =>';
                                    $qbe = 0;
                                    if (isset($qb[$pbid[$key]][$row2->bulan_request])) {
                                        $qbe= (float)$qb[$pbid[$key]][$row2->bulan_request];
                                        $idb2 = $idb[$pbid[$key]][$row2->bulan_request];
                                        $qbefore = (float)$qb[$pbid[$key]][$row2->bulan_request].' =>';
                                    }
                                    $t = date('F Y', strtotime('+1 year', strtotime($row2->bulan_request)) );
                                    $text.="<li>";
                                    $text.= $t.' : '.$npo[$key]." : ".$qbefore.(float)$value;
                                    $text.="</li>";
                                    array_push($newSet,array(
                                        'tipe' => 1,
                                        'request_barang_detail_id'=> $idb2,
                                        'bulan_request' => $row2->bulan_request,
                                        'bulan_request_n' => $t,
                                        'qou' => $qou[$key],
                                        'qty_before' => $qbe,
                                        'qty' => (float)$value,
                                        'po_pembelian_batch_id' => $pbid[$key],
                                        'po_number' => $npo[$key],
                                    ));
                                    $qou[$key] = 0;
                                }else if($qty_request > 0 && $qty_request - $value < 0){
                                    // echo 2;
                                    $nrd[$row->request_barang_batch_id][$row->barang_id][$row->warna_id][$row->bulan_request][$pbid[$key]]['qty'] = $qty_request;
                                    $nrd[$row->request_barang_batch_id][$row->barang_id][$row->warna_id][$row->bulan_request][$pbid[$key]]['po_number'] = $npo[$key];
                                    // $qou[$key] = $value - $qty_request;
                                    // if($qou[$key] < 800){
                                    //     // $qou[$key] = 0;
                                    // }
                                    $qbefore = ' 0 =>';
                                    $qbe = 0;
                                    if (isset($qb[$pbid[$key]][$row2->bulan_request])) {
                                        $qbe= (float)$qb[$pbid[$key]][$row2->bulan_request];
                                        $qbefore = (float)$qb[$pbid[$key]][$row2->bulan_request].' =>';
                                        $idb2 = $idb[$pbid[$key]][$row2->bulan_request];
                                    }
                                    $text.="<li>";
                                    $t = date('F Y', strtotime('+1 year', strtotime($row2->bulan_request)) );
                                    $text.= $t.' : '.$npo[$key]." : ".$qbefore.(float)$qty_request; 
                                    $text.="</li>";
                                    array_push($newSet,array(
                                        'tipe' => 2,
                                        'request_barang_detail_id'=> $idb2,
                                        'bulan_request' => $row2->bulan_request,
                                        'bulan_request_n' => $t,
                                        'qou' => $qou[$key],
                                        'qty_before' => $qbe,
                                        'qty' => (float)$qty_request,
                                        'po_pembelian_batch_id' => $pbid[$key],
                                        'po_number' => $npo[$key],
                                    ));
                                    $qou[$key] = $value - $qty_request;
                                    if($qou[$key] < 800){
                                        $qou[$key] = 0;
                                    }
                                    $qty_request = 0;
                                }
                                // echo ' ==> '.$value.'<hr/>';
                            }else if(isset($qb[$pbid[$key]][$row2->bulan_request])){
                                // echo $value.'<hr/>';
                                $qbe= (float)$qb[$pbid[$key]][$row2->bulan_request];
                                $t = date('F Y', strtotime('+1 year', strtotime($row2->bulan_request)) );
                                $idb2 = $idb[$pbid[$key]][$row2->bulan_request];

                                array_push($newSet,array(
                                    'tipe' => 3,
                                    'request_barang_detail_id'=> $idb2,
                                    'bulan_request' => $idb2,
                                    'bulan_request_n' => $t,
                                    'qou' => $qou[$key],
                                    'qty_before' => $qbe,
                                    'qty' => 0,
                                    'po_pembelian_batch_id' => $pbid[$key],
                                    'po_number' => $npo[$key],
                                ));
                            }

                        }
                    }
                    $idx++;
                }
                $text .= '</ul>';
                // $t = date('Y-m-d', strtotime('+1 year', strtotime($row->bulan_request)) );

                if ($row->qty > 0) {
                    array_push($return,array(
                        'id_detail' => $row->id,
                        'barang_id' => $row->barang_id,
                        'warna_id' => $row->warna_id,
                        'nama_barang' => $row->nama_barang,
                        'nama_warna' => $row->nama_warna,
                        'bulan_request' => $row->bulan_request,
                        'qty' => (float)$row->qty,
                        'po_number' => $row->po_number,
                        'po_pembelian_batch_id' => $row->po_pembelian_batch_id,
                        'data' => $newSet
                    ));
    
                    $updated[$row->request_barang_batch_id][$row->barang_id][$row->warna_id] = true;
                    
                }
            }
        }

        $respond['text'] = $text;
        $respond['idx'] = $idx_all;
        $respond['data'] = $return;
        echo json_encode($respond);

        
        // $request_detail_list = array();
        // $barang_id_list = array();
        // $warna_id_list = array();
        
    }

    function request_barang_batch_remove(){
        // print_r($this->input->post());
        $request_barang_batch_id = $this->input->post('request_barang_batch_id');
        $data = array(
            'status_aktif' => 0 , );
        $this->common_model->db_update('nd_request_barang_batch', $data,'id', $request_barang_batch_id);
        
        $data_aktif = array(
            'status' => 1 , );
        $latest_batch = $this->common_model->db_select("nd_request_barang_batch WHERE status_aktif = 1 ORDER by id DESC LIMIT 1");
        foreach ($latest_batch as $row) {
            $id = $row->id;
            $this->common_model->db_update('nd_request_barang_batch', $data_aktif,'id', $id);
        }

        redirect(is_setting_link('transaction/request_barang'));

        
    }

    function assign_po_baru_to_po_number($po_baru_list){
        $request_barang_detail_id_list = array();
        foreach (json_decode($po_baru_list)  as $key => $value) {
            foreach ($value as $index => $data) {
                if($index > 0){
                    $request_barang_detail_id = $data->request_barang_detail_id;
                    array_push($request_barang_detail_id_list, $request_barang_detail_id);
                    if (!isset($po_baru_update[$request_barang_detail_id])) {
                        $po_baru_update[$request_barang_detail_id] = array();
                    }

                    array_push($po_baru_update[$request_barang_detail_id],array(
                        'request_barang_detail_id' => $request_barang_detail_id,
                        'po_pembelian_batch_id' => $data->po_pembelian_batch_id ,
                        'qty' => $data->qty ));
                }
            }
        }

        if (count(array_unique($request_barang_detail_id_list)) > 0) {
            $get_data = $this->common_model->db_select("nd_request_barang_detail WHERE id IN(".implode(',',array_unique($request_barang_detail_id_list)).")");
            foreach ($get_data as $row) {
                $data_list[$row->id] = $row;
            }
            $dt_insert = array();
            foreach ($po_baru_update as $request_barang_detail_id => $data) {
                for ($i=0; $i < count($data) ; $i++) {
                    if ($i==0) {
                        $request_id = $data[$i]['request_barang_detail_id'];
                        $dt_update = array(
                            'po_pembelian_batch_id' =>$data[$i]['po_pembelian_batch_id'],
                            'qty' => $data[$i]['qty']
                        );
                        $this->common_model->db_update("nd_request_barang_detail", $dt_update,'id', $request_id);
                    }else{
                        // print_r($data[$i]['po_pembelian_batch_id']);
                        array_push($dt_insert, array(
                            'request_barang_batch_id' => $data_list[$request_barang_detail_id]->request_barang_batch_id,
                            'po_pembelian_batch_id' => $data[$i]['po_pembelian_batch_id'],
                            'bulan_request' => $data_list[$request_barang_detail_id]->bulan_request,
                            'barang_id' => $data_list[$request_barang_detail_id]->barang_id,
                            'warna_id'=> $data_list[$request_barang_detail_id]->warna_id,
                            'qty' => $data[$i]['qty'],
                            'is_po_baru' => 1
                            ));
                    }
                }
            }

            if (count($dt_insert) > 0) {
                $this->common_model->db_insert_batch("nd_request_barang_detail", $dt_insert);
            }
        }
    }

    function assign_penyesuaian_po_lock($po_lock){
        $request_barang_detail_id_list = array();
        $dt_notes = array();
        foreach (json_decode($po_lock)  as $key => $value) {
            if ($value->id != '') {
                array_push($request_barang_detail_id_list, $value->id);
            }
        }

        if (count($request_barang_detail_id_list) > 0) {
            $get = $this->common_model->db_select("nd_request_barang_detail WHERE id IN (".implode(',', $request_barang_detail_id_list).")");
            foreach (json_decode($po_lock)  as $key => $value) {
                $request_barang_detail_id = $value->id;
                $dt_update = array(
                    'qty' => $value->qty );
    
                $request_barang_batch_id = '';
                foreach ($get as $row) {
                    if($row->id == $value->id){
                        $request_barang_batch_id = $row->request_barang_batch_id;
                    }
                }
                
                if ($request_barang_batch_id != '') {
                    array_push($dt_notes,array(
                        'po_pembelian_batch_id' => $value->po_pembelian_batch_id,
                        'request_barang_detail_id' => $value->id,
                        'request_barang_batch_id' => $request_barang_batch_id,
                        'qty' => $value->qty_before
                    ));
                }
    
                // echo $request_barang_detail_id;
                // echo $value->status;
                // print_r($dt_update);
                if($value->status == 0){
                    // echo 'delete'.$request_barang_detail_id.'<br/>';
                    $this->common_model->db_delete('nd_request_barang_detail','id', $request_barang_detail_id);
                }else{
                    // echo $request_barang_detail_id;
                    // print_r($dt_update);
                    // echo 'update'.$request_barang_detail_id.'<br/>';
                    $this->common_model->db_update('nd_request_barang_detail',$dt_update,'id', $request_barang_detail_id);
                }
                // echo '<hr/>';
            }
    
            if(count($dt_notes)>0){
                $this->common_model->db_insert_batch('nd_request_update_lock',$dt_notes);
            }
        }

    }

    function penyesuaian_po_barang_request(){
        // print_r($this->input->post());
        if ($this->input->post('po_baru_assign') != '') {
            // echo 1;
            $this->assign_po_baru_to_po_number($this->input->post('po_baru_assign')); 
            // print_r($this->input->post('po_baru_assign'));  
        }

        if ($this->input->post('po_lock_assign') != '') {
            // echo 2;
            $this->assign_penyesuaian_po_lock($this->input->post('po_lock_assign'));   
            // print_r($this->input->post('po_lock_assign'));  
        }
        redirect(is_setting_link('transaction/request_barang'));
        
    }

    function po_revisi_list_manage_info(){
        $supplier_id = $this->input->post('supplier_id');
        $tanggal = $this->input->post('tanggal_awal');
        $tanggal_end = is_date_formatter($tanggal);   
        $tipe = $this->input->post('tipe');
        $request_barang_batch_id = $this->input->post('request_barang_batch_id');
        $request_barang_id = $this->input->post('request_barang_id');
        $return = array();
        $text = '';
        
        // $get = $this->transaction_model->get_po_revisi_request($request_barang_id, $tanggal, $tanggal_end);
        $get_revisi_po = $this->tr_model->get_updated_revisi_po_request($request_barang_id, $tanggal);

        foreach ($get_revisi_po as $row) {
            if (!isset($set[$row->request_barang_batch_id])) {
                $set[$row->request_barang_batch_id] = array();
            }
            // $idx_all++;
            array_push($set[$row->request_barang_batch_id], array(
                'barang_id' => $row->barang_id,
                'warna_id'=> $row->warna_id,
                'data'=> $row));
                
            if (!isset($updated[$row->request_barang_batch_id][$row->barang_id][$row->warna_id])) {
                # code...
                $g = $this->common_model->db_select("nd_request_barang_detail WHERE request_barang_batch_id=".$row->request_barang_batch_id." AND barang_id = ".$row->barang_id." AND warna_id = ".$row->warna_id.' ORDER BY bulan_request ASC ');
                $r = $this->common_model->db_select("nd_request_barang_qty WHERE request_barang_batch_id=".$row->request_barang_batch_id." AND barang_id = ".$row->barang_id." AND warna_id = ".$row->warna_id.' ORDER BY bulan_request ASC ');
                // print_r($gdata);
                // echo '<hr/>';
                // print_r($rdata);
                // echo '<hr/>';
                // print_r($value[$i]['data']);

                foreach ($g as $row2) {
                    $qb[$row2->po_pembelian_batch_id][$row2->bulan_request] = $row2->qty;
                    $idb[$row2->po_pembelian_batch_id][$row2->bulan_request] = $row2->id;
                }
                $npo = explode(',', $row->po_number_baru);
                $qou = explode(',', $row->qty_outstanding);
                $pqty = explode(',', $row->po_qty);
                $pbid = explode(',', $row->po_pembelian_batch_id_baru);
                // foreach ($g as $row2) {
                //     $gdata[$row2->bulan_request][$row2->po_pembelian_batch_id] = $row2->qty;
                // }
                
                $text .= "<span style='font-size:1.1em'>LOCK PO <b>".$row->nama_barang.' '.$row->nama_warna.'</b> PO: '.$row->po_number."</span><br/>";
                $idx = 0;
                $text .= '<ul>';
                $newSet = array();
                foreach ($r as $row2) {
                    $qty_request = $row2->qty;
                    if ($row->bulan_request == $row2->bulan_request || $idx == 1) {
                        $sisa_value = 0;
                        foreach ($qou as $key => $value) {
                            if ($value > 0) {
                                // echo $row->nama_barang.' '.$row->nama_warna.$qty_request;
                                $idb2 = '';
                                if ($qty_request - $value >= 0 ) {
                                    // echo 1;
                                    $nrd[$row->request_barang_batch_id][$row->barang_id][$row->warna_id][$row->bulan_request][$pbid[$key]]['qty'] = $value;
                                    $nrd[$row->request_barang_batch_id][$row->barang_id][$row->warna_id][$row->bulan_request][$pbid[$key]]['po_number'] = $npo[$key];
                                    $qty_request -= $value;
                                    $qbefore = ' 0 =>';
                                    $qbe = 0;
                                    if (isset($qb[$pbid[$key]][$row2->bulan_request])) {
                                        $qbe= (float)$qb[$pbid[$key]][$row2->bulan_request];
                                        $idb2 = $idb[$pbid[$key]][$row2->bulan_request];
                                        $qbefore = (float)$qb[$pbid[$key]][$row2->bulan_request].' =>';
                                    }
                                    $t = date('F Y', strtotime('+1 year', strtotime($row2->bulan_request)) );
                                    $text.="<li>";
                                    $text.= $t.' : '.$npo[$key]." : ".$qbefore.(float)$value;
                                    $text.="</li>";
                                    array_push($newSet,array(
                                        'tipe' => 1,
                                        'request_barang_detail_id'=> $idb2,
                                        'bulan_request' => $row2->bulan_request,
                                        'bulan_request_n' => $t,
                                        'qou' => $qou[$key],
                                        'qty_before' => $qbe,
                                        'qty' => (float)$value,
                                        'po_pembelian_batch_id' => $pbid[$key],
                                        'po_number' => $npo[$key],
                                    ));
                                    $qou[$key] = 0;
                                }else if($qty_request > 0 && $qty_request - $value < 0){
                                    // echo 2;
                                    $nrd[$row->request_barang_batch_id][$row->barang_id][$row->warna_id][$row->bulan_request][$pbid[$key]]['qty'] = $qty_request;
                                    $nrd[$row->request_barang_batch_id][$row->barang_id][$row->warna_id][$row->bulan_request][$pbid[$key]]['po_number'] = $npo[$key];
                                    // $qou[$key] = $value - $qty_request;
                                    // if($qou[$key] < 800){
                                    //     // $qou[$key] = 0;
                                    // }
                                    $qbefore = ' 0 =>';
                                    $qbe = 0;
                                    if (isset($qb[$pbid[$key]][$row2->bulan_request])) {
                                        $qbe= (float)$qb[$pbid[$key]][$row2->bulan_request];
                                        $qbefore = (float)$qb[$pbid[$key]][$row2->bulan_request].' =>';
                                        $idb2 = $idb[$pbid[$key]][$row2->bulan_request];
                                    }
                                    $text.="<li>";
                                    $t = date('F Y', strtotime('+1 year', strtotime($row2->bulan_request)) );
                                    $text.= $t.' : '.$npo[$key]." : ".$qbefore.(float)$qty_request; 
                                    $text.="</li>";
                                    array_push($newSet,array(
                                        'tipe' => 2,
                                        'request_barang_detail_id'=> $idb2,
                                        'bulan_request' => $row2->bulan_request,
                                        'bulan_request_n' => $t,
                                        'qou' => $qou[$key],
                                        'qty_before' => $qbe,
                                        'qty' => (float)$qty_request,
                                        'po_pembelian_batch_id' => $pbid[$key],
                                        'po_number' => $npo[$key],
                                    ));
                                    $qou[$key] = $value - $qty_request;
                                    if($qou[$key] < 800){
                                        $qou[$key] = 0;
                                    }
                                    $qty_request = 0;
                                }
                                // echo ' ==> '.$value.'<hr/>';
                            }else if(isset($qb[$pbid[$key]][$row2->bulan_request])){
                                // echo $value.'<hr/>';
                                $qbe= (float)$qb[$pbid[$key]][$row2->bulan_request];
                                $t = date('F Y', strtotime('+1 year', strtotime($row2->bulan_request)) );
                                $idb2 = $idb[$pbid[$key]][$row2->bulan_request];

                                array_push($newSet,array(
                                    'tipe' => 3,
                                    'request_barang_detail_id'=> $idb2,
                                    'bulan_request' => $idb2,
                                    'bulan_request_n' => $t,
                                    'qou' => $qou[$key],
                                    'qty_before' => $qbe,
                                    'qty' => 0,
                                    'po_pembelian_batch_id' => $pbid[$key],
                                    'po_number' => $npo[$key],
                                ));
                            }

                        }
                    }
                    $idx++;
                }
                $text .= '</ul>';
                // $t = date('Y-m-d', strtotime('+1 year', strtotime($row->bulan_request)) );

                if ($row->qty > 0) {
                    array_push($return,array(
                        'id_detail' => $row->id,
                        'barang_id' => $row->barang_id,
                        'warna_id' => $row->warna_id,
                        'nama_barang' => $row->nama_barang,
                        'nama_warna' => $row->nama_warna,
                        'bulan_request' => $row->bulan_request,
                        'qty' => (float)$row->qty,
                        'po_number' => $row->po_number,
                        'po_pembelian_batch_id' => $row->po_pembelian_batch_id,
                        'data' => $newSet
                    ));
    
                    $updated[$row->request_barang_batch_id][$row->barang_id][$row->warna_id] = true;
                    
                }
            }
        }

        $idx_all = count($return);
        // echo $cond;
        $respond['text'] = $request_barang_id.' '.$tanggal;
        $respond['idx'] = $idx_all;
        $respond['data'] = $return;

        echo json_encode($respond);
    }

/**
//==============================================================================================
**/

    ///////////////////////////////////////////////////////////////////////
    // insert alamat kirim
    ///////////////////////////////////////////////////////////////////////
    function jual_kirim_insert()
    {
        $penjualan_id = $this->input->post('penjualan_id');
        $alamat_kirim_id = $this->input->post('alamat_kirim_id');
        $tgl_print = date('Y-m-d H:i:s');
        $tanggal = is_date_formatter($this->input->post('tanggal'));
        $bulan = date("m", strtotime($tanggal));
        $tahun = date("Y", strtotime($tanggal));
        $sj_id = '';

        if ($alamat_kirim_id=='') {
            $alamat_kirim_id = 0;
        }
        $data = array(
            'penjualan_id' => $penjualan_id,
            'pengiriman_id' => ($alamat_kirim_id != '' ? $alamat_kirim_id : 0),
            'tgl_print' => $tgl_print
        );

        if ($tanggal > '2022-05-01') {
            $this->common_model->db_insert("nd_penjualan_log", $data, 'id');
            
            $no_sj = 1;
            foreach ($this->common_model->db_select("nd_surat_jalan WHERE YEAR(tanggal) = '$tahun' AND MONTH(tanggal)='$bulan' ") as $row) {
                $no_sj = $row->no_sj +1;
            }
            $get_data = $this->common_model->db_select("nd_surat_jalan WHERE tanggal ='$tanggal' AND penjualan_id = $penjualan_id");
            foreach ($get_data as $row) {
                $sj_id = $row->id;
                $no_sj = $row->no_sj;
            }
    
            $data_sj = array(
                'penjualan_id' => $penjualan_id ,
                'alamat_kirim_id' => $alamat_kirim_id,
                'tanggal' => $tanggal,
                'no_sj' => $no_sj,
                'user_id' => is_user_id(),
                'updated_at' => date("Y-m-d H:i:s")
            );
    
            if ($sj_id == '') {
                $sj_id  = $this->common_model->db_insert("nd_surat_jalan", $data_sj);
            }else{
                $this->common_model->db_update("nd_surat_jalan", $data_sj,'id', $sj_id);
            }
        }


        echo json_encode(array("status" => TRUE, "pengiriman_id" => $alamat_kirim_id, "sj_id" => $sj_id, "dtsj"=> $data_sj,"tgl"=> $tanggal));
    }



/**
//==============================================================================================
**/

    function po_penjualan_list(){

        $tanggal_end = date("Y-m-d"); 
        $tanggal_start = date("Y-m-d");
        $customer_id= "";
        $barang_id = "";
        $warna_id = "";

        if ($this->input->get("tanggal_start") && $this->input->get("tanggal_start") != '' && $this->input->get("tanggal_end") != '') {
            $tanggal_start = is_date_formatter($this->input->get("tanggal_start"));
            $tanggal_end = is_date_formatter($this->input->get("tanggal_end"));
        }

        if ($this->input->get("customer_id") && $this->input->get("customer_id") != '') {
            $customer_id = $this->input->get("customer_id");
        }

        if ($this->input->get("barang_id") && $this->input->get("barang_id") != '') {
            $barang_id = $this->input->get("barang_id");
        }

        if ($this->input->get("warna_id") && $this->input->get("warna_id") != '') {
            $warna_id = $this->input->get("warna_id");
        }

        $data = array(
            'content' =>'admin/transaction/po_penjualan_list' ,
            'breadcrumb_title' => 'Transaction',
            'breadcrumb_small' => 'Daftar PO Penjualan',
            'nama_menu' => "",
            'nama_submenu' => "",
            'common_data'=> $this->data,
            'barang_id' => $barang_id,
            'warna_id' => $warna_id,
            'customer_id' => $customer_id,
            'tanggal_start' => is_reverse_date($tanggal_start),
            'tanggal_end' => is_reverse_date($tanggal_end),
        );

        $cond = "";
        if ($customer_id != '') {
            $cond .= " AND customer_id=$customer_id";
        }

        if ($barang_id != '') {
            $cond .= " AND barang_id='$barang_id'";
        }

        if ($warna_id != '') {
            $cond .= " AND warna_id='$warna_id'";
        }
        // echo $cond;

        // $data['po_penjualan_list'] = $this->tr_model->get_po_penjualan($cond);
        $data['po_penjualan_list'] = array();
        
        $this->load->view('admin/template',$data);
    }


    function po_penjualan_insert(){
        $tanggal = is_date_formatter($this->input->post('tanggal'));
        $tanggal_kirim = $this->input->post('tanggal_kirim');
        $tanggal_kirim = ($tanggal_kirim == '' ? null : $tanggal_kirim);
        $ppn_include_status = $this->input->post('ppn_include_status');
        $ppn_include_status = ($ppn_include_status == '' ? 0 : 1);
        $ppn = get_ppn_now($tanggal);
        $data = array(
            "tanggal" => $tanggal,
            "tanggal_kirim" => $tanggal_kirim,
            "customer_id" => $this->input->post('customer_id'),
            "alamat_kirim" =>$this->input->post('alamat_kirim'),
            "contact_person" =>$this->input->post('contact_person'),
            "po_number" =>$this->input->post('po_number'),
            "tipe" =>$this->input->post('tipe'),
            "diskon" => 0 ,
            "ppn_include_status" => $ppn_include_status,
            "keterangan" =>$this->input->post('keterangan'),
            "ppn_value" => $ppn,
            "user_id" => is_user_id()
        );

        $id = $this->common_model->db_insert("nd_po_penjualan", $data);
        if ($this->input->post('penjualan_id') != '') {
            $penjualan_id = $this->input->post('penjualan_id');
            $this->penjualan_to_po($penjualan_id, $id);
        }

        redirect(is_setting_link('transaction/po_penjualan_detail')."?id=$id");
    }

    function penjualan_to_po($penjualan_id, $po_penjualan_id){
        $barang = $this->common_model->db_select("nd_penjualan_detail WHERE penjualan_id=$penjualan_id");
        $new_list = array();
        foreach ($barang as $row) {
            array_push($new_list, array(
                "po_penjualan_id" => $po_penjualan_id,
                "barang_id" => $row->barang_id,
                "warna_id" => $row->warna_id,
                "qty" => $row->subqty,
                "harga" => $row->harga_jual,
                "keterangan" => null,
                "user_id" => is_user_id(),
            ));
        }
        
        $this->common_model->db_insert_batch("nd_po_penjualan_detail", $new_list);
        $up_data = array(
            'po_penjualan_id' => $po_penjualan_id );
        $this->common_model->db_update("nd_penjualan", $up_data,'id', $penjualan_id);
    }

    function po_penjualan_update(){

        $id = $this->input->post('po_penjualan_id');
        $tanggal = is_date_formatter($this->input->post('tanggal'));
        $tanggal_kirim = $this->input->post('tanggal_kirim');
        $tanggal_kirim = ($tanggal_kirim == '' ? null : $tanggal_kirim);
        $ppn_include_status = $this->input->post('ppn_include_status');
        $ppn_include_status = ($ppn_include_status == '' ? 0 : 1);
        $ppn = get_ppn_now($tanggal);
        $data = array(
            "tanggal" => $tanggal,
            "tanggal_kirim" => $tanggal_kirim,
            "customer_id" => $this->input->post('customer_id'),
            "alamat_kirim" =>$this->input->post('alamat_kirim'),
            "contact_person" =>$this->input->post('contact_person'),
            "tipe" =>$this->input->post('tipe'),
            "po_number" =>$this->input->post('po_number'),
            "ppn_include_status" => $ppn_include_status,
            "keterangan" =>$this->input->post('keterangan'),
            "ppn_value" => $ppn,
            "user_id" => is_user_id()
        );

        $this->common_model->db_update("nd_po_penjualan", $data,'id', $id);
        redirect(is_setting_link('transaction/po_penjualan_detail')."?id=$id");
    }

    function po_penjualan_batal(){
        $id = $this->input->get('id');
        $data = array(
            'status_aktif' => -1
        );
        $this->common_model->db_update("nd_po_penjualan", $data,'id', $id);
        echo "OK";
    }

    function po_penjualan_detail(){

        $id = $this->input->get("id");
        $data_header = array();
        $data_barang = array();
        $data_invoice = array();
        $data_barang_invoice = array();
        $view_type = '';
        if ($id != '') {
            $data_header = $this->tr_model->get_po_penjualan_header($id);
            $data_barang = $this->tr_model->get_po_penjualan_barang($id);
            $data_invoice = $this->tr_model->get_invoice_list_for_po($id);
            $data_barang_invoice = $this->tr_model->get_barang_invoice_by_po($id);
            // redirect(is_setting_link('transaction/po_penjualan'));
        }

        if ($this->input->get('view_type') != '') {
            $view_type = $this->input->get('view_type');
        }
        
        $data = array(
            'content' =>'admin/transaction/po_penjualan_detail' ,
            'breadcrumb_title' => 'Transaction',
            'breadcrumb_small' => 'PO Penjualan Edit',
            'nama_menu' => "PO",
            'nama_submenu' => "PO Jual",
            'common_data'=> $this->data,
            'id' => $id,
            'view_type' => $view_type,
            'data_header' => $data_header,
            'data_barang' => $data_barang,
            'data_invoice' => $data_invoice,
            'data_barang_invoice' => $data_barang_invoice
        );

        if ($view_type == 2) {
            $this->load->view('admin/template_no_sidebar',$data);
        }else{
            $this->load->view('admin/template',$data);
        }
    }

    function po_penjualan_detail_insert(){
        $po_penjualan_id = $this->input->post('po_penjualan_id');
        $harga = $this->input->post("harga");
        $keterangan = $this->input->post('keterangan');
        $keterangan = ($keterangan == '' ? null : $keterangan);

        // pastikan dari front end format harga nya adalah 
        // ribuan menggunakan titik ('.')
        // decimal menggunakan koma (',')

        $harga = str_replace(".","",$harga);
        $harga = str_replace(",",".",$harga);
        $data = array(
            "po_penjualan_id" => $po_penjualan_id,
            "barang_id" => $this->input->post("barang_id"),
            "warna_id" => $this->input->post("warna_id"),
            "qty" => $this->input->post("qty"),
            "harga" => $harga,
            "keterangan" => $keterangan,
            "user_id" => is_user_id(),
        );

        $this->common_model->db_insert("nd_po_penjualan_detail", $data);
        redirect(is_setting_link('transaction/po_penjualan_detail')."?id=$po_penjualan_id");
    }

    function po_penjualan_detail_update(){
        $id = $this->input->post('id');
        $po_penjualan_id = $this->input->post('po_penjualan_id');
        $harga = $this->input->post("harga");
        $harga = str_replace(".","",$harga);
        $harga = str_replace(",",".",$harga);

        $keterangan = $this->input->post('keterangan');
        $keterangan = ($keterangan == '' ? null : $keterangan);

        $data = array(
            "po_penjualan_id" => $po_penjualan_id,
            "barang_id" => $this->input->post("barang_id"),
            "warna_id" => $this->input->post("warna_id"),
            "qty" => $this->input->post("qty"),
            "harga" => $harga,
            "keterangan" => $keterangan,
            "user_id" => is_user_id(),
        );

        $this->common_model->db_update("nd_po_penjualan_detail", $data, 'id', $id);
        redirect(is_setting_link('transaction/po_penjualan_detail')."?id=$po_penjualan_id");
    }

    function po_penjualan_remove_item(){
        $id = $this->input->get('id');
        $this->common_model->db_delete("nd_po_penjualan_detail", 'id', $id);
        echo json_encode("OK");

    }

    function po_penjualan_lock_status(){
        $id = $this->input->post('po_penjualan_id');
        $status_po = $this->input->post("status_po");
        $tanggal = $this->input->post("tanggal");
        $po_number = $this->input->post("po_number");
        $tipe = $this->input->post("tipe");
        $data = array(
            'locked_by' => ($status_po == 0 ? is_user_id() : null) ,
            'locked_date' => ($status_po == 0 ? date("Y-m-d H:i:s") : null),
            'locked_by' => null ,
            'locked_date' => null,
            'status_po' => $status_po
        );

        if($tipe == 2 && $po_number == ''  ){
            $tahun = date("Y", strtotime($tanggal));
            $bulan = date("m", strtotime($tanggal));

            $new_number = $this->get_po_request_number($tahun, $bulan);
            $data['request_index'] = $new_number['request_index'];
            $data['po_number'] = $new_number['po_number'];

        }

        // print_r($data);
        // print_r($data);
        $this->common_model->db_update("nd_po_penjualan", $data,'id', $id);
        redirect(is_setting_link('transaction/po_penjualan_detail')."?id=$id");
    }


    function get_po_request_number($tahun, $bulan){

        $last_number = $this->common_model->db_select("nd_po_penjualan WHERE YEAR(tanggal)='$tahun' AND MONTH(tanggal)='$bulan' AND tipe=2 ORDER BY request_index DESC LIMIT 1");
        $new_index = 1;
        foreach ($last_number as $row) {
            if ($row->request_index != '') {
                $new_index = $row->request_index + 1;
            }
        }

        $kode_toko = "";
        foreach ($this->toko_list_aktif as $row) {
            $kode_toko = $row->pre_po;
        }

        $full_number = $kode_toko.":NOTA-REQUEST/$tahun".$bulan."-$new_index";
        return array(
            "request_index" => $new_index,
            "po_number" => $full_number
        );
    }




    function po_penjualan_finish(){
        $id = $this->input->post('po_penjualan_id');
        $status_po = 2;
        $data = array(
            'closed_by' => is_user_id() ,
            'closed_date' => date("Y-m-d H:i:s"),
            'status_po' => 2
        );

        $this->common_model->db_update("nd_po_penjualan", $data,'id', $id);
        echo "OK";
        // redirect(is_setting_link('transaction/po_penjualan_detail')."?id=$id");

    }

    function po_penjualan_changeppnstatus(){
        $id = $this->input->get('id');
        $status = $this->input->get('status');

        $data = array(
            'ppn_include_status' => $status );

        $id = $this->common_model->db_update("nd_po_penjualan", $data,'id', $id);
        echo json_encode("OK");
    }

    function po_penjualan_update_others(){
        $po_penjualan_id = $this->input->post('po_penjualan_id');
        $col = $this->input->post("column");
        $val = $this->input->post("value");

        $data = array(
            $col => $val );

        $id = $this->common_model->db_update("nd_po_penjualan", $data,'id', $po_penjualan_id);
        echo json_encode("OK");
    }

    function get_customer_po(){
        $customer_id = $this->input->get('customer_id');
        $po_penjualan_id = $this->input->get('po_penjualan_id');
        $cond_po = '';
        if ($po_penjualan_id != '') {
            $cond_po = " OR po_penjualan_id = $po_penjualan_id";
        }
        $res = $this->common_model->get_po_customer($customer_id, $cond_po);
        echo json_encode($res);
    }

    function get_po_penjualan_list(){
        $tanggal_end = date("Y-m-d"); 
        $tanggal_start = date("Y-m-d");
        $customer_id= "";
        $barang_id = "";
        $warna_id = "";

        if ($this->input->get("tanggal_start") && $this->input->get("tanggal_start") != '' && $this->input->get("tanggal_end") != '') {
            $tanggal_start = is_date_formatter($this->input->get("tanggal_start"));
            $tanggal_end = is_date_formatter($this->input->get("tanggal_end"));
        }

        if ($this->input->get("customer_id") && $this->input->get("customer_id") != '') {
            $customer_id = $this->input->get("customer_id");
        }

        if ($this->input->get("barang_id") && $this->input->get("barang_id") != '') {
            $barang_id = $this->input->get("barang_id");
        }

        if ($this->input->get("warna_id") && $this->input->get("warna_id") != '') {
            $warna_id = $this->input->get("warna_id");
        }

        $cond = "";
        if ($customer_id != '') {
            $cond .= " AND customer_id=$customer_id";
        }

        if ($barang_id != '') {
            $cond .= " AND barang_id='$barang_id'";
        }

        if ($warna_id != '') {
            $cond .= " AND warna_id='$warna_id'";
        }
        // echo $cond;

        $res = $this->tr_model->get_po_penjualan($cond);
        echo json_encode($res);
    }

    function po_penjualan_upload_image(){
        
        $po_penjualan_id = $this->input->post('po_penjualan_id');
        $customer_id = $this->input->post('customer_id');

        
        // echo json_encode($this->input->post());

        $count = 1;
        // print_r($_FILES);
        if(!empty($_FILES)){
            $cust_dir = './po_list/cust_'.$customer_id;
            $dir = $cust_dir.'/po_'.$po_penjualan_id;
            
            $uploadedFile = $_FILES['compressedImage'];
            $originalExtension = pathinfo($uploadedFile['name'], PATHINFO_EXTENSION);


            if (!is_dir($cust_dir)) {
                mkdir($cust_dir, 0777, TRUE);
                mkdir($dir, 0777, TRUE);
                $count=1;

            }else if(!is_dir($dir)) {
                mkdir($dir, 0777, TRUE);
                $count=1;
            }else{
                $filesAndDirs = scandir($dir);
                // Initialize a variable to count the files
                // Loop through the list and count only the files (excluding directories)
                foreach ($filesAndDirs  as $item) {
                    // Exclude the current directory (.) and parent directory (..)
                    if ($item !== '.' && $item !== '..') {
                        // Check if the item is a file (not a directory)
                        if (is_file($dir . '/' . $item)) {
                            $count++;
                        }
                    }
                }
            }

            $filter = [',','.',' '];
            $config['upload_path'] = $dir;	
            $config['allowed_types'] = 'gif|jpg|png';
            $config['file_name'] = "image_".$customer_id.'_'.$po_penjualan_id."_".$count.".".$originalExtension;
            $this->load->library('upload',$config);
            // print_r($config);
            //$this->upload->initialize($config);
            if(!$this->upload->do_upload('compressedImage')){
                $error = array('error' => $this->upload->display_errors());
                print_r($error);
            }else{
                $data = array('upload_data' => $this->upload->data() );
                print_r($data);
            }

        }
    }

}