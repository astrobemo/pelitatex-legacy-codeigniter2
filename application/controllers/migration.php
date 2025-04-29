<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration extends CI_Controller {

	private $data = [];

	function __construct() 
	{
		parent:: __construct();
		
		is_logged_in();
		if(is_username() == ''){
			redirect('home');
		}elseif (is_user_time() == false || is_user_session() == false) {
			redirect('home');
		}
		$this->data['username'] = is_username();
		$this->data['user_menu_list'] = is_user_menu(is_posisi_id());
		$this->load->model('inventory_model','inv_model',true);
		$this->load->model('migration_model','mig_model',true);

		
		$this->supplier_list_aktif = $this->common_model->db_select('nd_supplier where status_aktif = 1');
		$this->customer_list_aktif = $this->common_model->db_select('nd_customer where status_aktif = 1');
		$this->toko_list_aktif = $this->common_model->db_select('nd_toko where status_aktif = 1');
		
		$this->gudang_list_aktif = $this->common_model->db_select('nd_gudang where status_aktif = 1  ORDER BY urutan asc');

		$this->warna_list_aktif = $this->common_model->db_select('nd_warna where status_aktif = 1 order by warna_jual asc');
		$this->barang_list_aktif_beli = $this->common_model->get_barang_list_aktif_beli();
		$this->barang_list_aktif = $this->common_model->get_barang_list_aktif();
		$this->satuan_list_aktif = $this->common_model->db_select('nd_satuan where status_aktif = 1');

		// $this->output->enable_profiler(TRUE);
	   	$this->pre_faktur = get_pre_faktur();
	   	$this->piutang_warn = get_piutang_warn();

		date_default_timezone_set("Asia/Jakarta");		


	}

	function migration_data_center(){

		// ALTER TABLE `nd_barang` ADD `tipe_qty` TINYINT(1) NOT NULL DEFAULT '1' AFTER `status_aktif`;
		/*CREATE TABLE `nd_penjualan_posisi_barang` (
		  `id` int(11) AUTO_INCREMENT PRIMARY KEY,
		  `penjualan_id` int(11) DEFAULT NULL,
		  `tipe_ambil_barang_id` tinyint(4) DEFAULT NULL,
		  `tanggal_pengambilan` date DEFAULT NULL,
		  `alamat_pengiriman` varchar(500) DEFAULT '',
		  `user_id` tinyint(4) DEFAULT NULL,
		  `status` tinyint(4) NOT NULL DEFAULT '1',
		  `closed_by` tinyint(4) DEFAULT NULL,
		  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
		  `updated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
		) ENGINE=InnoDB DEFAULT CHARSET=latin1;*/
		
		// $this->data_stok_barang('2019-12-31');
		// ==JANGAN LUPA BACKUP DULU YA GOBLOOOOOKKKK
		// ==JANGAN LUPA GENERATE DULU SEMUA SQL NYA YA GOBLOOOKKK, NANTI GA BISA DAPET HUTANG/PIUTANG AWAL
		
		// ==generate stok 
		// $this->generate_stok_opname_detail('2019');

		// get ppo report
		// $this->get_ppo_report('2019');


		// ==GENERATE hutang awal
		// $this->generate_hutang_awal('2019');
		// ==GENERATE pembayaran_hutang_awal
		// $this->update_pembayaran_hutang_awal();
		// == HAPUS PEMBELIAN
		// $id_beli_list = $this->hapus_pembelian_tahun_tutup('2018');
		// == HAPUS PEMBAYARAN PEMBELIAN
		// $this->hapus_pembayaran_pembelian_tahun_tutup($id_beli_list);

		// == RETUR BELI/PEMBELIAN LAIN-LAIN manual
		// HAPUS GIRO tahun tutup manual

		// ==GENERATE piutang awal
		// $this->generate_piutang_awal('2019');
		// ==GENERATE pembayaran_piutang_awal
		// $this->generate_pembayaran_piutang_awal();
		// ==HAPUS PENJUALAN
		// $id_jual_list = $this->hapus_penjualan_tahun_tutup('2018');
		// ==HAPUS PEMBAYARAN PENJUALAN
		// $this->hapus_pembayaran_penjualan_tahun_tutup($id_jual_list);
		// ==RETUR JUAL/PENJUALAN LAIN-LAIN manual
		// ==HAPUS PENERIMAAN GIRO ( hapus by tanggal transfer)


		//delete rekam faktur pajak 
		// $this->hapus_rekam_faktur_pajak('2019');

		// $this->hapus_migrasi_barang('2018');


		
		// INI MAH TERAKHIR PISAN KLO UDAH GENERATE SQL NYA
		//$this->delete_transaction('2019');

	}

	function data_stok_barang($tahun){

		// $session_data = $this->session->userdata('do_filter');
		$tanggal = $tahun.'-12-31';
		$select = '';
		$select2 = "";
		$columnSelect = array();
		$dt[0] = 'urutan';
		$dt[1] = 'nama_barang_jual';
		$dt[2] = 'status_aktif';
		$dt[3] = 'last_edit';
		$idx = 4;
		$i = 0;
		$cond_qty = "";
		foreach ($this->gudang_list_aktif as $row) {
			${'total_'.$row->id} = 0;
			$select .= ", ROUND(SUM( if(gudang_id=".$row->id.", ifnull(qty_masuk,0), 0 ) ) - SUM( if(gudang_id=".$row->id.", ifnull(qty_keluar,0), 0 ) ),2)  as gudang_".$row->id."_qty , SUM( if(gudang_id=".$row->id.", jumlah_roll_masuk, 0 ) ) - SUM( if(gudang_id=".$row->id.", jumlah_roll_keluar, 0 ) )  as gudang_".$row->id."_roll, concat('".$row->id."','/',barang_id,'/',warna_id) as gudang_".$row->id."_button";

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

		$select2 .= ', '.implode('+', $qty_add).' as qty_total, '.implode('+', $roll_add).' as roll_total';

		$aColumns = $dt;
		// $aColumns = array('urutan','nama_barang_jual','status_aktif','gudang_2_qty','gudang_2_roll','gudang_1_qty','gudang_1_roll','gudang_3_qty','gudang_3_roll');

        
        $sIndexColumn = "urutan";
        
        // paging
        
        // filtering
        

        $where_add = '';
        
        $stok_opname_id = 0;
        // $tanggal = is_date_formatter($this->input->get("tanggal"));
        $tanggal_awal = '2018-01-01';
		$getOpname = $this->common_model->db_select("nd_stok_opname where tanggal <= '".$tanggal."' order by tanggal desc limit 1");
		foreach ($getOpname as $row) {
			$tanggal_awal = $row->tanggal;
			$stok_opname_id = $row->id;
		}

		$sOrder = "ORDER BY nama_barang_jual, urutan asc";
		// if ($filter_type == 0) {
		// 	$rResult = $this->inv_model->get_stok_barang_list_ajax($aColumns, $sWhere, "order by urutan asc", $sLimit, $select, $tanggal, $stok_opname_id, $tanggal_awal, $select2);
		// }else{
			$result = $this->inv_model->get_stok_barang_list_ajax_new('*', '', '', '', $select, $tanggal, $stok_opname_id, $tanggal_awal, $select2, '', $cond_qty,'');
		// }

		return $result;

	}

	function generate_sql_file($table)
	{
		$return = '';
		$result = mysql_query('SELECT * FROM '.$table);
		$num_fields = mysql_num_fields($result);
		
		$return.= 'DROP TABLE IF EXISTS '.$table.';';
		$row2 = mysql_fetch_row(mysql_query('SHOW CREATE TABLE '.$table));
		$return.= "\n\n".$row2[1].";\n\n";
		
		for ($i = 0; $i < $num_fields; $i++) 
		{
			while($row = mysql_fetch_row($result))
			{
				$return.= 'INSERT INTO '.$table.' VALUES(';
				for($j=0; $j < $num_fields; $j++) 
				{
					$row[$j] = addslashes($row[$j]);
					$row[$j] = preg_replace("/\n/","/\\n/",$row[$j]);
					if (isset($row[$j])) { $return.= '"'.$row[$j].'"' ; } else { $return.= '""'; }
					if ($j < ($num_fields-1)) { $return.= ','; }
				}
				$return.= ");\n";
			}
		}
		$return.="\n\n\n";

		// $handle = fopen('db-backup-'.$table.'.sql','w+');
		// fwrite($handle,$return);
		// fclose($handle);
	}

	function generate_stok_opname_detail($tahun)
	{
		// GENERATE stok opname detail
		// 1. ambil stok akhir taun convert jadi penyesuaian stok tipe trans = 0 
		// 2. ambil penyesuaian stok taun berjalan
		// 3. add no 1 + no 2 
		// 4 drop table  penyesuaian stok
		// insert ulang
		
		$table = 'nd_penyesuaian_stok';
		$tanggal = $tahun.'-12-31';
		$return = '';
		
		$return.= 'DROP TABLE IF EXISTS '.$table.';';
		$row2 = mysql_fetch_row(mysql_query('SHOW CREATE TABLE '.$table));
		$return.= "\n\n".$row2[1].";\n\n";
		
		$i = 1;
		foreach ($this->data_stok_barang($tanggal)->result() as $row) {
			foreach ($this->gudang_list_aktif as $row2) {
				$qty_name = "gudang_".$row2->id.'_qty';
				$roll_name = "gudang_".$row2->id.'_roll';
				if ($row->$qty_name > 0) {
					$return.= 'INSERT INTO '.$table.' VALUES('.$i.',"0",';
					$return.= '"'.$tanggal.'",';
					$return.= '"'.$row2->id.'",';
					$return.= '"'.$row->barang_id.'",';
					$return.= '"'.$row->warna_id.'",';
					$return.= '"'.$row->$qty_name.'",';
					$return.= '"'.$row->$roll_name.'",';
					$return.= '"",';
					$return.= '"1"';
					$return.= ");\n";
					$i++;
				}
			}

		}
		$return.="\n\n\n";

		$result = $this->common_model->db_select($table." WHERE tipe_transaksi != 0 AND tanggal > '".$tanggal."'");
		foreach ($result as $row) {
			$return.= 'INSERT INTO '.$table.' VALUES('.$i.',';
				$return.= '"'.$row->tipe_transaksi.'",';
				$return.= '"'.$row->tanggal.'",';
				$return.= '"'.$row->gudang_id.'",';
				$return.= '"'.$row->barang_id.'",';
				$return.= '"'.$row->warna_id.'",';
				$return.= '"'.$row->qty.'",';
				$return.= '"'.$row->jumlah_roll.'",';
				$return.= '"",';
				$return.= '"'.$row->user_id.'"';
				$return.= ");\n";
				$i++;
		}
		$return.="\n\n\n";

		// echo $return;
		$file = 'table-'.$table.'.sql';
		$handle = fopen($file,'w+');
		fwrite($handle,$return);
		fclose($handle);

		header('Content-Description: File Transfer');
		header('Content-Disposition: attachment; filename='.basename($file));
		header('Expires: 0');
		header('Cache-Control: must-revalidate');
		header('Pragma: public');
		header('Content-Length: ' . filesize($file));
		header("Content-Type: application/sql");
		readfile($file);

	}

	function generate_stok_opname_detail_temp(){
		$get = $this->common_model->db_free_query_superadmin("SELECT *
			FROM (
				SELECT barang_id,warna_id, gudang_id, qty, sum(ifnull(jumlah_roll_masuk,0)) as jumlah_roll_masuk, sum(ifnull(jumlah_roll_keluar,0)) as jumlah_roll_keluar, sum(ifnull(jumlah_roll_masuk,0)) - sum(ifnull(jumlah_roll_keluar,0)) as selisih
				FROM (
					(
						SELECT barang_id,warna_id,gudang_id, qty, sum(jumlah_roll) as jumlah_roll_masuk, 0 as jumlah_roll_keluar, 1 as tipe
						FROM nd_stok_opname_transaksi
						WHERE status = 1
						OR status = 3
						GROUP BY barang_id,warna_id, gudang_id, qty
					)UNION(
						SELECT barang_id,warna_id,gudang_id, qty, 0, sum(jumlah_roll) as jumlah_roll_keluar, 2 as tipe
						FROM nd_stok_opname_transaksi
						WHERE status = 2
						OR status = 4
						GROUP BY barang_id,warna_id, gudang_id, qty
					)UNION(
						SELECT barang_id,warna_id, gudang_id, qty, sum(jumlah_roll) as jumlah_roll_masuk, 0, 3 as tipe
						FROM nd_stok_opname_detail_temp
						GROUP BY barang_id,warna_id, gudang_id, qty
					)
				)result
				WHERE selisih > 0
				GROUP BY barang_id, gudang_id,warna_id, qty
			)result");
	
		$i=1 ;
		foreach ($get as $row) {
			echo "($i,$row->barang_id,$row->warna_id,$row->qty,$row->jumlah_roll,1)<br/>";
		}
			
	}


//============================pembelian + hutang================================
	function generate_hutang_awal($tahun){

		$table = "nd_hutang_awal";
		$tanggal = $tahun.'-12-31';
		$return = '';
		$pembayaran_hutang_id = array();
		$result = mysql_query('SELECT * FROM '.$table);
		$num_fields = mysql_num_fields($result);
		
		$return.= 'DROP TABLE IF EXISTS '.$table.';';
		// $row2 = mysql_fetch_row(mysql_query('SHOW CREATE TABLE '.$table));
		// $return.= "\n\n".$row2[1].";\n\n";

		$table_field = "CREATE TABLE `nd_hutang_awal` ( 
			`id` int(11) NOT NULL AUTO_INCREMENT, 
			`toko_id` tinyint(4) NOT NULL DEFAULT '1', 
			`supplier_id` tinyint(11) DEFAULT NULL, 
			`tanggal` date DEFAULT NULL,
			`pembelian_id_before` smallint(6) DEFAULT NULL,
			`no_faktur` varchar(40) DEFAULT NULL, 
			`amount` int(11) DEFAULT NULL, 
			`jatuh_tempo` date NOT NULL, 
			`jumlah_roll` smallint(11) NOT NULL, 
			`user_id` tinyint(11) NOT NULL, PRIMARY KEY (`id`) 
			) ENGINE=InnoDB AUTO_INCREMENT=429 DEFAULT CHARSET=latin1;";
		$return.= "\n\n".$table_field.";\n\n";

		$i = 1;
		$get_hutang = $this->mig_model->generate_hutang_awal($tanggal);
		foreach ($get_hutang as $row) {
			$return.= 'INSERT INTO '.$table.' VALUES('.$i.',"1",';
			$return.= '"'.$row->supplier_id.'",';
			$return.= '"'.$row->tanggal.'",';
			$return.= '"'.$row->pembelian_id.'",';
			$return.= '"'.$row->no_faktur.'",';
			$return.= '"'.$row->total.'",';
			$return.= '"'.$row->jatuh_tempo.'",';
			$return.= '"'.$row->total.'",';
			$return.= '"1"';
			$return.= ");\n";
			$i++;
		}

		$return.="\n\n\n";

		$file = 'hutang_awal_'.$tahun.'.sql';
		$handle = fopen($file,'w+');
		fwrite($handle,$return);
		fclose($handle);

		header('Content-Description: File Transfer');
		header('Content-Disposition: attachment; filename='.basename($file));
		header('Expires: 0');
		header('Cache-Control: must-revalidate');
		header('Pragma: public');
		header('Content-Length: ' . filesize($file));
		header("Content-Type: application/sql");
		readfile($file);
	}

	function update_pembayaran_hutang_awal(){
		// == hapus dlu pembayaran hutang awal sebelumnya klo ada == 
		$this->common_model->db_delete("nd_pembayaran_hutang_detail","data_status","2");
		$this->mig_model->get_link_pembayaran_hutang_awal();

	}

	function hapus_pembelian_tahun_tutup($tahun)
	{

		// ==hapus pembelian tahun sebelum
		// ==hapus barang pembelian tahun sebelum
		$tanggal = $tahun.'-12-31';
		$beli_id = array();
		$id_beli_list = "";
		// == get dlu pembelian tahun tutup, populate it
		$get_beli = $this->common_model->db_select("nd_pembelian where tanggal <='".$tanggal."'");
		foreach ($get_beli as $row) {
			array_push($beli_id, $row->id);
		}
		if (count($beli_id) > 0) {
			$id_beli_list = implode(",", $beli_id);
			$this->common_model->db_free_query_superadmin("DELETE FROM nd_pembelian_detail WHERE pembelian_id IN (".$id_beli_list.")");
			$this->common_model->db_free_query_superadmin("DELETE FROM nd_pembelian WHERE id IN (".$id_beli_list.")");
		}

		return $id_beli_list;

	}

	function hapus_pembayaran_pembelian_tahun_tutup($id_beli_list)
	{
		// ==hapus pembayaran hutang sebelum tahun berjalan
		$bayar_beli_id = array();
		$id_bayar_list = '';
		if ($id_beli_list != '') {
			$get_bayar_hutang = $this->common_model->db_select("nd_pembayaran_hutang_detail WHERE data_status = 1 AND pembelian_id IN(".$id_beli_list.")");
			foreach ($get_bayar_hutang as $row) {
				array_push($bayar_beli_id, $row->pembelian_id);
			}
		
			array_unique($bayar_beli_id);
			$id_bayar_list = implode(",", $bayar_beli_id);
			if ($id_bayar_list != '') {
				$this->common_model->db_free_query_superadmin("DELETE FROM nd_pembayaran_hutang_detail WHERE pembayaran_hutang_id IN (".$id_bayar_list.")");
				$this->common_model->db_free_query_superadmin("DELETE FROM nd_pembayaran_hutang WHERE id IN (".$id_bayar_list.")");
				$this->common_model->db_free_query_superadmin("DELETE FROM nd_pembayaran_hutang_nilai WHERE pembayaran_hutang_id IN (".$id_bayar_list.")");
			}
		}
		// hapus giro keluar pembayaran hutang sebelum, mungkin harus manual
	}

//=================================penjualan + piutang ===============================

	function generate_piutang_awal($tahun){


		$table = "nd_piutang_awal";
		$tanggal = $tahun.'-12-31';
		$return = '';
		$pembayaran_piutang_id = array();
		$result = mysql_query('SELECT * FROM '.$table);
		$num_fields = mysql_num_fields($result);
		
		$return.= 'DROP TABLE IF EXISTS '.$table.';';
		// $row2 = mysql_fetch_row(mysql_query('SHOW CREATE TABLE '.$table));
		// $return.= "\n\n".$row2[1].";\n\n";

		$table_field = "CREATE TABLE `nd_piutang_awal` ( 
			`id` int(11) NOT NULL AUTO_INCREMENT, 
			`toko_id` tinyint(4) NOT NULL DEFAULT '1', 
			`customer_id` tinyint(11) DEFAULT NULL, 
			`tanggal` date DEFAULT NULL,
			`penjualan_id_before` smallint(6) DEFAULT NULL,
			`no_faktur` varchar(40) DEFAULT NULL, 
			`amount` int(11) DEFAULT NULL, 
			`jatuh_tempo` date NOT NULL, 
			`jumlah_roll` smallint(11) NOT NULL, 
			`user_id` tinyint(11) NOT NULL, PRIMARY KEY (`id`) 
			) ENGINE=InnoDB AUTO_INCREMENT=429 DEFAULT CHARSET=latin1;";
		$return.= "\n\n".$table_field.";\n\n";

		$i = 1;
		$get_piutang = $this->mig_model->generate_piutang_awal($tanggal);
		foreach ($get_piutang as $row) {
			$return.= 'INSERT INTO '.$table.' VALUES('.$i.',"1",';
			$return.= '"'.$row->customer_id.'",';
			$return.= '"'.$row->tanggal.'",';
			$return.= '"'.$row->penjualan_id.'",';
			$return.= '"'.$row->no_faktur.'",';
			$return.= '"'.$row->total.'",';
			$return.= '"'.$row->jatuh_tempo.'",';
			$return.= '"'.$row->jumlah_roll.'",';
			$return.= '"1"';
			$return.= ");\n";
			$i++;

		}

		$return.="\n\n\n";

		$file = 'piutang_awal_'.$tahun.'.sql';
		$handle = fopen($file,'w+');
		fwrite($handle,$return);
		fclose($handle);

		header('Content-Description: File Transfer');
		header('Content-Disposition: attachment; filename='.basename($file));
		header('Expires: 0');
		header('Cache-Control: must-revalidate');
		header('Pragma: public');
		header('Content-Length: ' . filesize($file));
		header("Content-Type: application/sql");
		readfile($file);
	}

	function generate_pembayaran_piutang_awal(){

		// == hapus dlu pembayaran piutang awal sebelumnya klo ada == 
		$this->common_model->db_delete("nd_pembayaran_piutang_detail","data_status","2");
		$this->mig_model->get_link_pembayaran_piutang_awal();

	}

	function hapus_penjualan_tahun_tutup($tahun)
	{

		// ==hapus penjualan tahun sebelum
		// ==hapus barang penjualan tahun sebelum
		$tanggal = $tahun.'-12-31';
		$jual_id = array();
		$id_jual_list = "";
		// == get dlu penjualan tahun tutup, populate it
		$get_jual = $this->common_model->db_select("nd_penjualan where tanggal <='".$tanggal."'");
		foreach ($get_jual as $row) {
			array_push($jual_id, $row->id);
		}
		if (count($jual_id) > 0) {
			$id_jual_list = implode(",", $jual_id);
			$detail_id = array();
			$get_detail = $this->common_model->db_select("nd_penjualan_detail WHERE penjualan_id IN (".$id_jual_list.")");
			foreach ($get_detail as $row) {
				array_push($detail_id, $row->id);
			}
			$id_jual_detail = implode(",", $detail_id);
			if ($id_jual_detail != '') {
				$this->common_model->db_free_query_superadmin("DELETE FROM nd_penjualan_qty_detail WHERE penjualan_detail_id IN (".$id_jual_detail.")");
				$this->common_model->db_free_query_superadmin("DELETE FROM nd_penjualan_detail WHERE penjualan_id IN (".$id_jual_list.")");
			}
			$this->common_model->db_free_query_superadmin("DELETE FROM nd_penjualan WHERE id IN (".$id_jual_list.")");
		}

		return $id_jual_list;
	}

	function hapus_pembayaran_penjualan_tahun_tutup($id_jual_list)
	{
		// ==hapus pembayaran hutang sebelum tahun berjalan
		$bayar_jual_id = array();
		$id_bayar_list = '';
		if ($id_jual_list != '') {
			$this->common_model->db_free_query_superadmin("DELETE FROM nd_pembayaran_penjualan WHERE penjualan_id IN (".$id_jual_list.")");
			$get_bayar_piutang = $this->common_model->db_select("nd_pembayaran_piutang_detail WHERE data_status = 1 AND penjualan_id IN(".$id_jual_list.")");
			foreach ($get_bayar_piutang as $row) {
				array_push($bayar_jual_id, $row->penjualan_id);
			}
		
			array_unique($bayar_jual_id);
			$id_bayar_list = implode(",", $bayar_jual_id);
			if ($id_bayar_list != '') {
				$this->common_model->db_free_query_superadmin("DELETE FROM nd_pembayaran_piutang_detail WHERE pembayaran_piutang_id IN (".$id_bayar_list.")");
				$this->common_model->db_free_query_superadmin("DELETE FROM nd_pembayaran_piutang WHERE id IN (".$id_bayar_list.")");
				$this->common_model->db_free_query_superadmin("DELETE FROM nd_pembayaran_piutang_nilai WHERE pembayaran_piutang_id IN (".$id_bayar_list.")");
			}
		}
		// hapus giro keluar pembayaran hutang sebelum, mungkin harus manual
	}

//=================================rekam faktur pajak ===============================
	function hapus_rekam_faktur_pajak($tahun){
		// cek dlu faktur pajak nya 
		$tanggal = $tahun."-12-31";
		$no_rekam_id = array();
		$no_faktur_id = array();
		$get_rekam_data = $this->mig_model->get_rekam_faktur_pajak_tutup_tahun($tahun);
		foreach ($get_rekam_data as $row) {
			array_push($no_rekam_id, $row->rekam_faktur_pajak_id);
			array_push($no_faktur_id, $row->no_faktur_pajak_id);
		}

		$list_rekam_id = implode(",", $no_rekam_id);
		$list_faktur_id = implode(",", $no_faktur_id);

		$this->common_model->db_free_query_superadmin("DELETE FROM nd_rekam_faktur_pajak_detail WHERE rekam_faktur_pajak_id IN (".$list_rekam_id.")");
		$this->common_model->db_free_query_superadmin("DELETE FROM nd_rekam_faktur_pajak WHERE id IN (".$list_rekam_id.")");
		$this->common_model->db_free_query_superadmin("DELETE FROM nd_rekam_faktur_pajak_email WHERE rekam_faktur_pajak_id IN (".$list_rekam_id.")");
		$this->common_model->db_free_query_superadmin("DELETE FROM nd_no_faktur_pajak WHERE tahun_pajak='$tahun' ");
		

	}

	function hapus_migrasi_barang($tahun){
		$this->common_model->db_free_query_superadmin("DELETE FROM nd_mutasi_barang WHERE YEAR(tanggal)='".$tahun."'");
	}


//==========================================get ppo report===============================

	function get_ppo_report($tahun){
		$data = $this->mig_model->get_ppo_barang_list($tahun);
		$idx= 1;
		// print_r($data);
		foreach ($data as $row) {
			// $get_barang = $this->common_model->db_select('nd_po_pembelian_warna where po_pembelian_batch_id='.$row->id);
			// foreach ($get_barang as $row2) {
			// 	echo $idx.'. ';
			// 	print_r($row2);
			// 	echo '<hr/>';
			// }
			echo $row->barang_id.'<br/>';
		}
	}

}