<?

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
			// $this->output->enable_profiler(TRUE);
			echo $Totalan;
			
		}else{

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
				echo $Totalan;
				
			}else{
				echo json_encode( $output );
	
			}
		}
		



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
			// buat kartu stok
			$data['stok_barang'] = $this->inv_model->get_stok_barang_satuan_2($gudang_id, $barang_id, $warna_id, $tanggal_start, $tanggal_end, '2018-01-01', $stok_opname_id); 
			// buat rincian barang
			$data['stok_barang_by_satuan'] = $this->inv_model->get_kartu_stok_barang_by_satuan_2($gudang_id, $barang_id, $warna_id, $tanggal_awal2, $tanggal_end, $tanggal_awal2, $stok_opname_id);
			$data['stok_awal'] = $this->inv_model->get_stok_barang_satuan_awal_2($gudang_id, $barang_id, $warna_id, $tanggal_start, '2018-01-01', $stok_opname_id);
		}else{
			// buat kartu stok
			$data['stok_barang'] = $this->inv_model->get_stok_barang_satuan($gudang_id, $barang_id, $warna_id, $tanggal_start, $tanggal_end, '2018-01-01', $stok_opname_id); 
			// buat rincian barang
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

			}

			// echo $data['stok_barang'];
		
		// echo $data['stok_barang'];
	}


?>