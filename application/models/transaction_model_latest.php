<?php

class Transaction_Model extends CI_Model {

	function __construct()
    {
         // Call the Model constructor
        parent::__construct();
        $this->db->query("SET SESSION time_zone = '+7:00'");
		// $this->pre_faktur = get_pre_faktur();
    }


//=============================pre po list=======================================

	function get_pre_po_list_detail($id){
		$query = $this->db->query("SELECT t1.*, t2.nama as nama_barang
			FROM nd_pre_po_pembelian_detail t1
			LEFT JOIN nd_barang t2
			ON t1.barang_id = t2.id
			WHERE t1.pre_po_pembelian_id = $id

		");
		return $query->result();
	}

	function get_pre_po_list_warna($id){
		$query = $this->db->query("SELECT t1.*, t2.nama as nama_barang, t3.warna_beli as nama_warna
			FROM nd_pre_po_pembelian_detail_warna t1
			LEFT JOIN nd_barang t2
			ON t1.barang_id = t2.id
			LEFT JOIN nd_warna t3
			ON t1.warna_id = t3.id
			WHERE t1.pre_po_pembelian_id = $id

		");
		return $query->result();	
	}

	function get_stok_for_pre_po($barang_id_pool, $tanggal, $tanggal_awal, $stok_opname_id){
		$query = $this->db->query("SELECT barang_id, warna_id, sum(qty_stok) as qty_stok, sum(jumlah_roll_stok) as jumlah_roll_stok, sum(qty_po) as qty_po, group_concat(qty_po_data SEPARATOR '=?=') as qty_po_data, group_concat(batch SEPARATOR '=?=') as batch, group_concat(po_pembelian_id  SEPARATOR '=?=') as po_pembelian_id, b.nama as nama_barang, c.warna_beli as nama_warna, d.nama as nama_satuan
			FROM((
					SELECT barang_id, warna_id, SUM(ifnull(qty_masuk,0)) - SUM( ifnull(qty_keluar,0)) as qty_stok, SUM(ifnull(jumlah_roll_masuk,0)) - SUM( ifnull(jumlah_roll_keluar,0)) as jumlah_roll_stok , 0 as qty_po, 0 as qty_po_data, 0 as po_pembelian_id, 0 as batch
					FROM (
						(
					        SELECT barang_id, warna_id, nd_penjualan_detail.gudang_id, CAST(0 as DECIMAL(15,2)) as qty_masuk, 0 as jumlah_roll_masuk, sum(qty) as qty_keluar, sum(jumlah_roll) as jumlah_roll_keluar,3
					        FROM nd_penjualan_detail
					        LEFT JOIN (
					        	SELECT *
					        	FROM nd_penjualan
					        	WHERE tanggal <= '$tanggal'
					        	AND tanggal >= '$tanggal_awal'
					        	AND status_aktif = 1
					        	) nd_penjualan
					        ON nd_penjualan_detail.penjualan_id = nd_penjualan.id
					        LEFT JOIN (
					            SELECT sum(qty* if(jumlah_roll != 0, jumlah_roll,1) ) as qty, sum(jumlah_roll) as jumlah_roll, penjualan_detail_id
					            FROM nd_penjualan_qty_detail
					            GROUP BY penjualan_detail_id
					            ) nd_penjualan_qty_detail
					        ON nd_penjualan_qty_detail.penjualan_detail_id = nd_penjualan_detail.id
					        where nd_penjualan.id is not null
					        GROUP BY barang_id, warna_id, nd_penjualan_detail.gudang_id
					    )UNION(
					        SELECT barang_id, warna_id, t1.gudang_id, CAST(0 as DECIMAL(15,2)) as qty_masuk, 0 as jumlah_roll_masuk, sum(qty) as qty_keluar, sum(jumlah_roll) as jumlah_roll_keluar,3
					        FROM nd_pengeluaran_stok_lain_detail t1
					        LEFT JOIN (
					        	SELECT *
					        	FROM nd_pengeluaran_stok_lain
					        	WHERE tanggal <= '$tanggal'
					        	AND tanggal >= '$tanggal_awal'
					        	AND status_aktif = 1
					        	) t2
					        ON t1.pengeluaran_stok_lain_id = t1.id
					        LEFT JOIN (
					            SELECT sum(qty* if(jumlah_roll != 0, jumlah_roll,1) ) as qty, sum(jumlah_roll) as jumlah_roll, pengeluaran_stok_lain_detail_id
					            FROM nd_pengeluaran_stok_lain_qty_detail
					            GROUP BY pengeluaran_stok_lain_detail_id
					            ) t3
					        ON t3.pengeluaran_stok_lain_detail_id = t1.id
					        where t2.id is not null
					        GROUP BY barang_id, warna_id, t1.gudang_id
					    )UNION(
					        SELECT barang_id, warna_id, nd_pembelian.gudang_id, sum(qty) as qty_masuk, sum(jumlah_roll) as jumlah_roll_masuk, CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar, 1 as tipe
					        FROM (
					        	SELECT sum(qty*if(jumlah_roll = 0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, t1.id, barang_id, warna_id, pembelian_id
					        	FROM nd_pembelian_detail t1
					        	LEFT JOIN nd_pembelian_qty_detail t2
					        	ON t2.pembelian_detail_id = t1.id
					        	GROUP BY t1.id
					        	ORDER BY pembelian_id
					        ) nd_pembelian_detail
					        LEFT JOIN (
					        	SELECT *
					        	FROM nd_pembelian
					        	WHERE tanggal <= '$tanggal'
					        	AND tanggal >= '$tanggal_awal'
					        	AND status_aktif = 1
					        	) nd_pembelian
					        ON nd_pembelian_detail.pembelian_id = nd_pembelian.id
					        WHERE nd_pembelian.id is not null
					        GROUP BY barang_id, warna_id, nd_pembelian.gudang_id
					    )UNION(
					    	SELECT barang_id, warna_id, gudang_id_after, sum(qty) as qty_masuk, sum(jumlah_roll) as jumlah_roll_masuk, CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar,2
				        	FROM nd_mutasi_barang t1
				        	LEFT JOIN nd_mutasi_barang_qty t2
				        	ON t2.mutasi_barang_id = t1.id
				        	WHERE tanggal <= '$tanggal'
				        	AND tanggal >= '$tanggal_awal'
				        	AND status_aktif = 1
					        GROUP BY barang_id, warna_id, gudang_id_after
					    )UNION(
					    	SELECT barang_id, warna_id, nd_retur_jual_detail.gudang_id, sum(qty) as qty_masuk, sum(jumlah_roll) as jumlah_roll_masuk, CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar,4
					        FROM nd_retur_jual_detail
					        LEFT JOIN (
					        	SELECT *
					        	FROM nd_retur_jual
					        	WHERE tanggal <= '$tanggal'
					        	AND tanggal >= '$tanggal_awal'
					        	AND status_aktif = 1
					        	) nd_retur_jual
					        ON nd_retur_jual_detail.retur_jual_id = nd_retur_jual.id
					        LEFT JOIN (
					            SELECT sum(qty*jumlah_roll) as qty, sum(jumlah_roll) as jumlah_roll, retur_jual_detail_id
					            FROM nd_retur_jual_qty
					            GROUP BY retur_jual_detail_id
					            ) nd_penjualan_qty_detail
					        ON nd_penjualan_qty_detail.retur_jual_detail_id = nd_retur_jual_detail.id
					        WHERE nd_retur_jual.id is not null
					        GROUP BY barang_id, warna_id,nd_retur_jual_detail.gudang_id
					    )UNION(
					    	SELECT barang_id, warna_id, nd_retur_beli_detail.gudang_id, 0,0, sum(qty), sum(jumlah_roll),19
					        FROM nd_retur_beli_detail
					        LEFT JOIN (
					        	SELECT *
					        	FROM nd_retur_beli
					        	WHERE tanggal <= '$tanggal'
					        	AND tanggal >= '$tanggal_awal'
					        	AND status_aktif = 1
					        	) nd_retur_beli
					        ON nd_retur_beli_detail.retur_beli_id = nd_retur_beli.id
					        LEFT JOIN (
					            SELECT sum(qty*jumlah_roll) as qty, sum(jumlah_roll) as jumlah_roll, retur_beli_detail_id
					            FROM nd_retur_beli_qty
					            GROUP BY retur_beli_detail_id
					            ) nd_penjualan_qty_detail
					        ON nd_penjualan_qty_detail.retur_beli_detail_id = nd_retur_beli_detail.id
					        WHERE nd_retur_beli.id is not null
					        GROUP BY barang_id, warna_id,nd_retur_beli_detail.gudang_id
					    )UNION(
					        SELECT barang_id, warna_id, gudang_id, sum(qty) as qty_masuk, sum(jumlah_roll) as jumlah_roll_masuk, CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar,5
					        	FROM nd_penyesuaian_stok
					        	WHERE tipe_transaksi = 0
				                AND tanggal <= '$tanggal'
					        	AND tanggal >= '$tanggal_awal'
				                GROUP BY barang_id, warna_id, gudang_id
					    )UNION(
					        SELECT  barang_id, warna_id, gudang_id, sum(if(tipe_transaksi=1,qty*if(jumlah_roll = 0,1,jumlah_roll) ,0) ) as qty_masuk, sum(if(tipe_transaksi = 1,jumlah_roll,0)) as jumlah_roll_masuk, sum(if(tipe_transaksi=2,qty*if(jumlah_roll = 0,1,jumlah_roll) ,0) ) as qty_keluar, sum(if(tipe_transaksi = 2,jumlah_roll,0)) as jumlah_roll_keluar,6
				        	FROM nd_penyesuaian_stok
				        	WHERE tanggal <= '$tanggal'
				        	AND tanggal >= '$tanggal_awal'
				        	AND tipe_transaksi != 0
							GROUP BY barang_id, warna_id, gudang_id
					    )UNION(
					    	SELECT barang_id, warna_id, gudang_id_before, CAST(0 as DECIMAL(15,2)) as qty_masuk, 0 as jumlah_roll_masuk, sum(qty) as qty_keluar, sum(jumlah_roll) as jumlah_roll_keluar,8
				        	FROM nd_mutasi_barang t1
				        	LEFT JOIN nd_mutasi_barang_qty t2
				        	ON t2.mutasi_barang_id = t1.id
				        	WHERE tanggal <= '$tanggal'	
						    AND tanggal >= '$tanggal_awal'
				        	AND status_aktif = 1
							GROUP BY barang_id, warna_id, gudang_id_before
					    )UNION(
					    	SELECT barang_id, warna_id, gudang_id, sum(qty), sum(jumlah_roll) as jumlah_roll_masuk, 0 as qty_keluar, 0 as jumlah_roll_keluar,9
				        	FROM nd_stok_opname_detail t1 
							LEFT JOIN nd_stok_opname t2
							ON t1.stok_opname_id = t2.id
				        	WHERE stok_opname_id = $stok_opname_id
				        	GROUP BY barang_id, warna_id, gudang_id
					    )
					)result
					-- WHERE barang_id IN ($barang_id_pool)
					GROUP BY barang_id, warna_id
				)UNION(
					SELECT barang_id, warna_id, 0 ,0, sum(qty_sisa),  group_concat(qty_sisa), group_concat(po_pembelian_id) as po_pembelian_id, group_concat(batch) as batch
					FROM (
						SELECT sum(a.qty-qty_beli) as qty_sisa, c.barang_id, a.warna_id, c.po_pembelian_id, group_concat(concat(po_number,'-',batch) SEPARATOR '??') as batch
						FROM (
							SELECT *
							FROM nd_po_pembelian_warna
							) a
						LEFT JOIN nd_po_pembelian_batch b
						ON a.po_pembelian_batch_id = b.id
						LEFT JOIN (
							SELECT *
							FROM nd_po_pembelian_detail
							) c
						ON a.po_pembelian_detail_id = c.id
						LEFT JOIN (
							SELECT barang_id, warna_id, sum(qty) as qty_beli, po_pembelian_batch_id 
							FROM nd_pembelian_detail t1
							LEFT JOIN nd_pembelian t2
							ON t1.pembelian_id = t2.id
							LEFT JOIN nd_pembelian_qty_detail t3
							ON t1.id = t3.pembelian_detail_id
							GROUP BY barang_id, warna_id, po_pembelian_batch_id
							) d
						ON a.warna_id = d.warna_id
						AND b.id = d.po_pembelian_batch_id
						AND d.barang_id = c.barang_id
						LEFT JOIN nd_po_pembelian e
						ON c.po_pembelian_id = e.id
						WHERE a.qty-qty_beli >= 0
						GROUP BY barang_id, warna_id, c.po_pembelian_id
					)t1
					LEFT JOIN nd_po_pembelian t2
					ON t1.po_pembelian_id = t2.id
					-- WHERE barang_id IN ($barang_id_pool)
					GROUP BY barang_id, warna_id

				)
			) a
			LEFT JOIN nd_barang b
			ON a.barang_id = b.id
			LEFT JOIN nd_warna c
			ON a.warna_id = c.id
			LEFT JOIN nd_satuan d
			ON b.satuan_id = d.id
			GROUP BY barang_id, warna_id
			");

		return $query->result();

	}

//=============================ockh editor list=======================================

	function get_ockh_non_po($cond_supplier, $cond_ockh){

		$query = $this->db->query("SELECT t1.*, tanggal_formatted as tanggal, t2.nama as nama_supplier, t3.nama as nama_barang, t4.username, t6.nama as nama_satuan,group_concat(round(qty)) as qty, group_concat(warna_id) as warna_id, group_concat(ifnull(qty_masuk,'-')) as qty_masuk, group_concat(ifnull(harga_masuk,'-')) as harga_masuk, group_concat(ifnull(tanggal_masuk,'-')) tanggal_masuk, group_concat(ifnull(warna_id_masuk,'-')) warna_id_masuk, group_concat(ockh_warna_id) as ockh_warna_id, group_concat(warna_beli) as nama_warna, group_concat(ifnull(qty_masuk_jml,0)) as qty_masuk_jml
			FROM (
				SELECT *, DATE_FORMAT(tanggal, '%d/%m/%Y') as tanggal_formatted
				FROM nd_ockh_non_po
				$cond_supplier
				$cond_ockh
				) t1
			LEFT JOIN nd_supplier t2
			ON t1.supplier_id = t2.id
			LEFT JOIN nd_barang t3
			ON t1.barang_id = t3.id
			LEFT JOIN nd_user t4
			ON t1.user_id = t4.id
			LEFT JOIN (
				SELECT qty, warna_id, (t1.id) as ockh_warna_id, ockh_non_po_id
				FROM nd_ockh_non_po_warna t1
				) t5
			ON t1.id = t5.ockh_non_po_id
			LEFT JOIN nd_satuan t6
			ON t3.satuan_id = t6.id
			LEFT JOIN nd_warna t8
			ON t5.warna_id = t8.id
			LEFT JOIN (
				SELECT group_concat(ifnull(qty,0) SEPARATOR '??') as qty_masuk, group_concat(harga_beli SEPARATOR '??') as harga_masuk, group_concat(tanggal SEPARATOR '??') as tanggal_masuk, ockh, warna_id warna_id_masuk, sum(qty) as qty_masuk_jml
				FROM nd_pembelian_detail tA
				LEFT JOIN nd_pembelian tB
				ON tA.pembelian_id = tB.id
				LEFT JOIN (
					SELECT sum(qty* if(jumlah_roll=0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, pembelian_detail_id
					FROM nd_pembelian_qty_detail
					GROUP bY pembelian_detail_id
					) tC
				ON tC.pembelian_detail_id = tA.id
				WHERE ockh is not null
				AND ockh != ''
				GROUP BY ockh, warna_id
				) as t7
			ON t1.ockh = t7.ockh
			AND t5.warna_id = t7.warna_id_masuk
			GROUP BY t1.id

		");
		return $query->result();
	}

	function cek_ockh_registered($ockh,$cond){
		$query = $this->db->query("SELECT *
			FROM (
				(
					SELECT ockh, id, '' as po_pembelian_batch_id
					FROM nd_ockh_non_po
					WHERE ockh = '$ockh'
					$cond
				)UNION(
					SELECT a.ockh, a.id, po_pembelian_batch_id
					FROM (
						SELECT *
						FROM nd_pembelian_detail
						WHERE ockh = '$ockh'
						) a
					LEFT JOIN nd_pembelian b
					ON a.pembelian_id = b.id
					LEFT JOIN nd_ockh_non_po c
					ON a.ockh = c.ockh
					WHERE c.id is null
					AND po_pembelian_batch_id is not null
				)
			) result
		");
		return $query->num_rows();
	}

//===============================po pembelian===========================================
	function get_po_pembelian_list_ajax($aColumns, $sWhere, $sOrder, $sLimit){
		$extra_cond = '';
		if (is_posisi_id() != 1) {
			$extra_cond ='WHERE tbl_a.status_aktif = 1';
		}
		
		$query = $this->db->query("SET collation_connection = 'utf8_general_ci'");
		$query = $this->db->query("SELECT *
			FROM (
					SELECT tbl_a.status_aktif, tbl_b.nama as toko, 
					if(tanggal >= '2019-09-27', 
						concat(ifnull(pre_po,''),tbl_f.kode,'/',DATE_FORMAT(tanggal,'%y'),'/',LPAD(po_number,3,'0')) ,
						concat(LPAD(po_number,3,'0'),'/',DATE_FORMAT(tanggal,'%m'),'/',DATE_FORMAT(tanggal,'%y'))) as po_number,
						DATE_FORMAT(tanggal,'%d/%m/%Y') as tanggal, qty as jumlah, jumlah_roll, nama_barang, tbl_f.nama as supplier, 
						concat_ws('??',tbl_a.id, toko_id, supplier_id, ifnull(po_pembelian_batch_id,'-'), kode, 
						concat(ifnull(tbl_a.locked_by,'-'),'||',ifnull(tbl_a.released_by,'-')), 
						concat(ifnull(batch_locked_by,'-'),'||', ifnull(batch_released_by,'-'))
						) as status_data, 
						catatan as keterangan, po_batch, 
						concat(nama_barang,'=?=',ifnull(qty,0),'=?=',ifnull(qty_sisa,0),'=?=',ifnull(harga,0)) as barang_data, 
						tbl_a.id as id, ifnull(qty_sisa,0) as qty_sisa
					FROM nd_po_pembelian as tbl_a
					LEFT JOIN nd_toko as tbl_b
					ON tbl_a.toko_id = tbl_b.id
					LEFT JOIN (
						SELECT group_concat(nd_barang_beli.nama SEPARATOR '??') as nama_barang, group_concat(qty_sisa SEPARATOR '??') as qty_sisa , group_concat(qty SEPARATOR '??') as qty ,group_concat(jumlah_roll SEPARATOR '??') as jumlah_roll, po_pembelian_id, group_concat(harga SEPARATOR '??') as harga
						FROM (
							SELECT a.qty as qty, (a.qty - ifnull(qty_warna,0)) as qty_sisa, a.jumlah_roll, po_pembelian_id, 
							a.barang_id, harga, barang_beli_id
							FROM nd_po_pembelian_detail a
							LEFT JOIN (
								SELECT po_pembelian_detail_id, sum(qty) as qty_warna, warna_id
								FROM nd_po_pembelian_warna tA
								LEFT JOIN nd_po_pembelian_batch tB
								ON tA.po_pembelian_batch_id = tB.id
								WHERE tB.status != 0
								GROUP BY po_pembelian_detail_id
								) b
							ON b.po_pembelian_detail_id = a.id
							) t1
						LEFT JOIN nd_barang_beli
						ON t1.barang_beli_id = nd_barang_beli.id
						LEFT JOIN nd_barang
						ON t1.barang_id = nd_barang.id
						LEFT JOIN nd_satuan
						ON nd_barang.satuan_id = nd_satuan.id
						GROUP BY po_pembelian_id
					) as tbl_c
					ON tbl_c.po_pembelian_id = tbl_a.id
					LEFT JOIN nd_supplier as tbl_f
					ON tbl_f.id = tbl_a.supplier_id
					LEFT JOIN (
						SELECT group_concat(id ORDER BY id asc) as po_pembelian_batch_id, 
						group_concat(if(revisi>1,concat(batch,'R',(revisi-1)),batch)  ORDER BY id asc) as po_batch, 
						group_concat(ifnull(locked_by,'-')  ORDER BY id asc) as batch_locked_by, 
						group_concat(ifnull(released_by,'-')  ORDER BY id asc) as batch_released_by, 
						po_pembelian_id
						FROM nd_po_pembelian_batch
						WHERE status  != 0
						GROUP BY po_pembelian_id
						) tbl_g
					ON tbl_a.id = tbl_g.po_pembelian_id
				)A
			$sWhere
            ORDER BY id desc
            $sLimit
			", false);

		return $query;
	}

	function get_data_po_pembelian($id){
		$query = $this->db->query("SELECT tbl_a.*, tbl_b.nama as nama_supplier, tbl_d.nama as nama_toko, 
		tbl_b.telepon as telepon_supplier, username as release_master_person, released_date as release_master_date,
		if(tanggal >= '2019-09-27', concat(ifnull(pre_po,''),tbl_b.kode,'/',DATE_FORMAT(tanggal,'%y'),'/',LPAD(po_number,3,'0')) ,concat(LPAD(po_number,3,'0'),'/',DATE_FORMAT(tanggal,'%m'),'/',DATE_FORMAT(tanggal,'%y')))  as po_number, tbl_b.nama as nama_customer, tbl_b.alamat, up_person, tbl_b.kota, LPAD(po_number,3,'0') as po_number_raw
			FROM (
				SELECT *
				FROM nd_po_pembelian
				Where id = $id
			) as tbl_a
			LEFT JOIN nd_supplier as tbl_b
			ON tbl_a.supplier_id = tbl_b.id
			LEFT JOIN nd_toko as tbl_d
			ON tbl_a.toko_id = tbl_d.id
			LEFT JOIN nd_user 
			ON tbl_a.released_by = nd_user.id
		");
		return $query->result();
	}

	function get_data_po_pembelian_batch($id){
		$query = $this->db->query("SELECT tbl_a.*, username as release_person, released_date
			FROM (
				SELECT *
				FROM nd_po_pembelian_batch
				Where po_pembelian_id = $id
			) as tbl_a
			LEFT JOIN nd_user 
			ON  tbl_a.released_by = nd_user.id
		");
		return $query->result();
	
	}

	function get_data_po_pembelian_detail($po_pembelian_id, $group){
		$query = $this->db->query("SELECT id, nama_tercetak, barang_beli_id, barang_id, warna_id, harga, 
		nama_barang, nama_satuan, nama_show, 
		sum(qty) as qty, 
		sum(qty_warna_total) as qty_warna_total, 
		group_concat(nama_warna SEPARATOR '||') as nama_warna, 
		group_concat(warna_id SEPARATOR '||') as warna_id, 
		group_concat(qty_warna SEPARATOR '||') as qty_warna,
		group_concat(qty_warna_total SEPARATOR '||') as qty_warna_total_data
			FROM (
				SELECT tbl_a.id, nama_tercetak, barang_beli_id, tbl_a.barang_id, qty, harga, 
				tbl_b1.nama as nama_barang, tbl_c.nama as nama_satuan, 
				if(nama_tercetak = '' || nama_tercetak is null, tbl_b1.nama, nama_tercetak) as nama_show
				FROM (
					SELECT *
					FROM nd_po_pembelian_detail
					WHere po_pembelian_id = $po_pembelian_id
				) as tbl_a
				LEFT JOIN nd_barang_beli as tbl_b1
				ON tbl_a.barang_beli_id = tbl_b1.id
				LEFT JOIN nd_barang as tbl_b
				ON tbl_a.barang_id = tbl_b.id
				LEFT JOIN nd_satuan as tbl_c
				ON tbl_b.satuan_id = tbl_c.id
			) t1
			LEFT JOIN  (
				SELECT group_concat(c.warna_beli SEPARATOR '??') as nama_warna, po_pembelian_detail_id, 
				group_concat(warna_id) as warna_id, sum(qty) as qty_warna_total, 
				group_concat(qty) as qty_warna
				FROM nd_po_pembelian_warna a
				LEFT JOIN nd_warna c
				ON a.warna_id = c.id
				GROUP BY po_pembelian_detail_id
			) t2
			ON t2.po_pembelian_detail_id = t1.id
			WHERE barang_beli_id is not null
			$group
		");
		return $query->result();
	}

	function get_po_pembelian_by_supplier($supplier_id){
		$query = $this->db->query("SELECT a.id, if(tanggal >= '2019-09-27', concat(ifnull(pre_po,''),tbl_b.kode,'/',DATE_FORMAT(tanggal,'%y'),'/',LPAD(po_number,3,'0')) ,concat(LPAD(po_number,3,'0'),'/',DATE_FORMAT(tanggal,'%m'),'/',DATE_FORMAT(tanggal,'%y'))) as po_number
			FROM nd_po_pembelian_batch a
			LEFT JOIN (
				SELECT *
				FROM nd_po_pembelian
				WHERE supplier_id = $supplier_id
				) b
			ON a.po_pembelian_id = b.id
			LEFT JOIN nd_toko c
			ON b.toko_id = c.id
			LEFT JOIN nd_supplier d
			ON b.supplier_id = d.id
			where b.id is not null");
		return $query->result();
	}
	
	function get_data_po_pembelian_detail_info($po_pembelian_detail_id){
		
		$query = $this->db->query("SELECT t1.*, t2.*
			FROM (
				SELECT tbl_a.*, tbl_b1.nama as nama_barang, tbl_c.nama as nama_satuan
				FROM (
					SELECT *
					FROM nd_po_pembelian_detail
					WHere id = $po_pembelian_detail_id
				) as tbl_a
				LEFT JOIN nd_barang_beli as tbl_b1
				ON tbl_a.barang_beli_id = tbl_b1.id
				LEFT JOIN nd_barang as tbl_b
				ON tbl_a.barang_id = tbl_b.id
				LEFT JOIN nd_satuan as tbl_c
				ON tbl_b.satuan_id = tbl_c.id
			) t1
			LEFT JOIN  (
				SELECT group_concat(c.warna_beli SEPARATOR '??') as nama_warna, po_pembelian_detail_id, group_concat(warna_id) as warna_id, sum(qty) as qty_warna_total, group_concat(qty) as qty_warna
				FROM nd_po_pembelian_warna a
				LEFT JOIN nd_warna c
				ON a.warna_id = c.id
				GROUP BY po_pembelian_detail_id
			) t2
			ON t2.po_pembelian_detail_id = t1.id
		");
		return $query->result();
	}

	function get_data_po_pembelian_detail_warna($po_pembelian_detail_id){
		$query = $this->db->query("SELECT b1.nama as nama_barang_revisi , c.warna_beli as nama_warna, 
		po_pembelian_detail_id, d.barang_id as barang_id_revisi, a.warna_id, a.qty as qty_warna, 
		OCKH, a.id, qty_datang, f.tanggal, batch, f.id as po_pembelian_batch_id, d.po_pembelian_id
			FROM (
				SELECT *
				FROM nd_po_pembelian_warna
				WHERE po_pembelian_detail_id = $po_pembelian_detail_id
				) a
			LEFT JOIN nd_po_pembelian_detail d
			ON a.po_pembelian_detail_id = d.id
			LEFT JOIN nd_po_pembelian_batch f
			ON a.po_pembelian_batch_id = f.id
			LEFT JOIN (
				SELECT sum(qty) as qty_datang, po_pembelian_batch_id, barang_id, warna_id
				FROM nd_pembelian_detail t1
				LEFT JOIN nd_pembelian t2
				ON t1.pembelian_id = t2.id
				GROUP BY po_pembelian_batch_id, warna_id, barang_id
				) e
			ON a.po_pembelian_batch_id = e.po_pembelian_batch_id
			AND d.barang_id = e.barang_id
			AND e.warna_id = a.warna_id
			LEFT JOIN nd_barang b1
			ON d.barang_beli_id = b1.id
			LEFT JOIN nd_barang b
			ON d.barang_id = b.id
			LEFT JOIN nd_warna c
			ON a.warna_id = c.id
		");
		return $query->result();
	}

	function get_data_po_pembelian_warna($po_pembelian_detail_id){
		$query = $this->db->query("SELECT a.*, e.warna_beli as nama_warna, batch, if(a.tipe_barang = 1 , d.qty_beli, t0.qty_beli) as qty_beli, nama_beli_baru, b.tanggal, nama_direname
			FROM (
				SELECT *
				FROM nd_po_pembelian_warna
				WHERE po_pembelian_detail_id = $po_pembelian_detail_id
				) a
			LEFT JOIN (
				SELECT *
				FROM nd_po_pembelian_batch
				WHERE status != 0
				) b
			ON a.po_pembelian_batch_id = b.id
			LEFT JOIN (
				SELECT *
				FROM nd_po_pembelian_detail
				WHERE id = $po_pembelian_detail_id
				) c
			ON a.po_pembelian_detail_id = c.id
			LEFT JOIN (
				SELECT barang_id, warna_id, sum(qty) as qty_beli, po_pembelian_batch_id 
				FROM nd_pembelian_detail t1
				LEFT JOIN nd_pembelian t2
				ON t1.pembelian_id = t2.id
				GROUP BY barang_id, warna_id, po_pembelian_batch_id
				) d
			ON a.warna_id = d.warna_id
			AND b.id = d.po_pembelian_batch_id
			AND d.barang_id = c.barang_id
			LEFT JOIN (
				SELECT tA.id, qty_beli, tC.nama as nama_beli_baru, tD.nama as nama_direname, harga_baru
				FROM (
					SELECT *
					FROM nd_po_pembelian_warna
					WHERE po_pembelian_detail_id = $po_pembelian_detail_id
					AND tipe_barang != 1
					) tA
				LEFT JOIN (
					SELECT barang_id, warna_id, sum(qty) as qty_beli, po_pembelian_batch_id 
					FROM nd_pembelian_detail t1
					LEFT JOIN nd_pembelian t2
					ON t1.pembelian_id = t2.id
					GROUP BY barang_id, warna_id, po_pembelian_batch_id
					) tB
				ON tA.warna_id = tB.warna_id
				AND tA.po_pembelian_batch_id = tB.po_pembelian_batch_id
				AND tA.barang_id_baru = tB.barang_id
				LEFT JOIN nd_barang tC
				ON tA.barang_id_baru = tC.id
				LEFT JOIN nd_barang tD
				ON tA.barang_id_baru_rename = tD.id
				) t0
			ON a.id = t0.id
			LEFT JOIN nd_warna e
			ON a.warna_id = e.id
			WHERE b.id is not null
			ORDER BY batch, warna_jual ASC

			");

		return $query->result();
	}

	function po_pembelian_get_barang_list($po_pembelian_id){
		$query = $this->db->query("SELECT if(tipe_barang=1,t2.barang_id, barang_id_baru) as barang_id, ifnull(harga_baru, harga) as harga
				FROM nd_po_pembelian_warna t1
				LEFT JOIN nd_po_pembelian_detail t2
				ON t1.po_pembelian_detail_id = t2.id
				WHERE t1.id IN (
					SELECT max(id) as id
					FROM (
						SELECT t1.id, ifnull(harga_baru, t3.harga) as harga, if(tipe_barang = 1, t3.barang_id, barang_id_baru) as barang_id
						FROM nd_po_pembelian_warna t1
						LEFT JOIN (
							SELECT *
							FROM nd_po_pembelian_detail
							WHERE po_pembelian_id = $po_pembelian_id
							) t3
						ON t1.po_pembelian_detail_id = t3.id
						WHERE t3.id is not null
						ORDER BY t1.id desc
					) result
					GROUP BY barang_id
				)			
		");
		return $query->result();
	}


	function po_pembelian_get_history_harga($po_pembelian_id){
		$query = $this->db->query("SELECT if(tipe_barang=1,t2.barang_id, barang_id_baru) as barang_id, ifnull(harga_baru, harga) as harga, po_pembelian_batch_id
				FROM nd_po_pembelian_warna t1
				LEFT JOIN nd_po_pembelian_detail t2
				ON t1.po_pembelian_detail_id = t2.id
				WHERE t1.id IN (
					SELECT max(id) as id
					FROM (
						SELECT t1.id, ifnull(harga_baru, t3.harga) as harga, if(tipe_barang = 1, t3.barang_id, barang_id_baru) as barang_id
						FROM nd_po_pembelian_warna t1
						LEFT JOIN (
							SELECT *
							FROM nd_po_pembelian_detail
							WHERE po_pembelian_id = $po_pembelian_id
							) t3
						ON t1.po_pembelian_detail_id = t3.id
						WHERE t3.id is not null
						ORDER BY t1.id desc
					) result
					GROUP BY barang_id
				)
		");
		return $query->result();
	}

	function po_pembelian_get_latest_harga_barang($barang_id){
		$query = $this->db->query("SELECT tA.barang_id, harga as harga, DATE_FORMAT(tanggal, '%d/%m/%Y') as tanggal, po_number as po_number
						FROM (
							SELECT if(tipe_barang=1,t2.barang_id, barang_id_baru) as barang_id, max(ifnull(harga_baru, harga)) as harga, po_pembelian_batch_id
							FROM (
								SELECT tA.*, tanggal
								FROM nd_po_pembelian_warna tA
								LEFT JOIN nd_po_pembelian_batch tB
								ON tA.po_pembelian_batch_id = tB.id
							)t1
							LEFT JOIN nd_po_pembelian_detail t2
							ON t1.po_pembelian_detail_id = t2.id
							WHERE t1.tanggal IN (
								SELECT max(tanggal) as tanggal_last
								FROM (
									SELECT if(tipe_barang = 1, t3.barang_id, barang_id_baru) as barang_id, po_pembelian_batch_id
									FROM nd_po_pembelian_warna t1
									LEFT JOIN nd_po_pembelian_detail t3
									ON t1.po_pembelian_detail_id = t3.id
									WHERE if(tipe_barang = 1, t3.barang_id, barang_id_baru)  = $barang_id
									ORDER BY t1.id desc
								) tA
								LEFT JOIN nd_po_pembelian_batch tB
								ON tA.po_pembelian_batch_id = tB.id
								GROUP BY barang_id
							)
							AND if(tipe_barang = 1, t2.barang_id, barang_id_baru)  = $barang_id
						)tA
						LEFT JOIN (
							SELECT t1.id, t1.tanggal, concat(if(t2.tanggal >= '2019-09-27', concat(ifnull(pre_po,''),t4.kode,'/',DATE_FORMAT(t2.tanggal,'%y'),'/',LPAD(po_number,3,'0')) ,concat(LPAD(po_number,3,'0'),'/',DATE_FORMAT(t2.tanggal,'%m'),'/',DATE_FORMAT(t2.tanggal,'%y'))),'-',batch,if(revisi > 1,concat('R',revisi-1),'') ) as po_number 
							FROM nd_po_pembelian_batch t1
							LEFT JOIN nd_po_pembelian t2
							ON t1.po_pembelian_id = t2.id
							LEFT JOIN nd_toko t3
							ON t2.toko_id = t3.id
							LEFT JOIN nd_supplier t4
							ON t2.supplier_id=t4.id
						) tB
						ON tA.po_pembelian_batch_id = tB.id
						WHERE tanggal is not null
						AND po_number is not null
		");
		return $query->result();
	}

	function get_data_po_pembelian_detail_batch($po_pembelian_batch_id, $po_pembelian_detail_id, $tipe_barang){
		$query = $this->db->query("SELECT b1.nama as nama_barang , d.warna_beli as nama_warna, po_pembelian_detail_id, barang_beli_id,
		b.barang_id, a.warna_id, a.qty, OCKH, a.id, e.nama as nama_satuan, nama_tercetak, nama_baru, tipe_barang, 
		barang_id_baru, ifnull(harga_baru, b.harga) as harga, if(a.tipe_barang = 1, ifnull(f.qty_beli,0), ifnull(a.qty_beli,0) ) as qty_beli, 
		if(a.tipe_barang = 1,f.no_faktur, a.no_faktur) as no_faktur, if(a.tipe_barang = 1,f.tanggal_beli, a.tanggal_beli) as tanggal_beli, 
		locked_by, username, locked_date, nama_direname, barang_id_baru_rename, ifnull(ppo_qty,0) as ppo_qty, tanggal_ppo, ppo_lock_id
			FROM (
				SELECT t1.*, t21.nama as nama_baru, if(tipe_barang !=1, qty_beli, 0) as qty_beli, if(tipe_barang !=1, no_faktur, null) as no_faktur, 
				if(tipe_barang !=1, tanggal_beli, null) as tanggal_beli, username, if(tipe_barang = 4, t4.nama, null) as nama_direname
				FROM (
					SELECT *
					FROM nd_po_pembelian_warna
					WHERE po_pembelian_batch_id = $po_pembelian_batch_id
					AND po_pembelian_detail_id = $po_pembelian_detail_id
					$tipe_barang
					)t1
				LEFT JOIN nd_barang_beli t21
				ON t1.barang_beli_id_baru = t21.id
				LEFT JOIN nd_barang t2
				ON t1.barang_id_baru = t2.id
				LEFT JOIN nd_user t3
				ON t1.locked_by = t3.id
				LEFT JOIN nd_barang t4
				ON t1.barang_id_baru_rename = t4.id
				LEFT JOIN (
					SELECT barang_id, warna_id, group_concat(ifnull(qty,0) ORDER BY tanggal asc) as qty_beli, po_pembelian_batch_id, group_concat(t2.id ORDER BY tanggal asc) as pembelian_id, group_concat(no_faktur ORDER BY tanggal asc) as no_faktur, group_concat(tanggal ORDER BY tanggal asc) as tanggal_beli
					FROM nd_pembelian_detail t1
					LEFT JOIN nd_pembelian t2
					ON t1.pembelian_id = t2.id
					GROUP BY barang_id, warna_id, po_pembelian_batch_id
					ORDER BY tanggal ASC
					) tB
				ON t1.warna_id = tB.warna_id
				AND t1.po_pembelian_batch_id = tB.po_pembelian_batch_id
				AND t1.barang_id_baru = tB.barang_id
				) a
			LEFT JOIN nd_po_pembelian_detail b
			ON a.po_pembelian_detail_id = b.id
			LEFT JOIN (
				SELECT barang_id, warna_id, group_concat(ifnull(qty,0) ORDER BY tanggal asc) as qty_beli, po_pembelian_batch_id, group_concat(t2.id ORDER BY tanggal asc) as pembelian_id, group_concat(no_faktur ORDER BY tanggal asc) as no_faktur, group_concat(tanggal ORDER BY tanggal asc) as tanggal_beli
				FROM nd_pembelian_detail t1
				LEFT JOIN nd_pembelian t2
				ON t1.pembelian_id = t2.id
				GROUP BY barang_id, warna_id, po_pembelian_batch_id
				ORDER BY tanggal ASC
				) f
			ON a.warna_id = f.warna_id
			AND a.po_pembelian_batch_id = f.po_pembelian_batch_id
			AND b.barang_id = f.barang_id
			LEFT JOIN (
				SELECT po_pembelian_detail_id as po_pembelian_detail_id_ppo, warna_id as warna_id_ppo, t_2.qty as ppo_qty, t_3.tanggal as tanggal_ppo, t_1.ppo_lock_id
				FROM (
					SELECT *
					FROM nd_ppo_to_po
					WHERE po_pembelian_detail_id = $po_pembelian_detail_id
					AND po_pembelian_batch_id = $po_pembelian_batch_id
				)t_1
				LEFT JOIN nd_ppo_lock_detail t_2
				ON t_1.ppo_lock_id = t_2.ppo_lock_id
				LEFT JOIN nd_ppo_lock t_3
				ON t_2.ppo_lock_id = t_3.id
			) g
			ON b.id = g.po_pembelian_detail_id_ppo
			AND a.warna_id = g.warna_id_ppo
			LEFT JOIN nd_barang_beli b1
			ON b.barang_beli_id = b1.id
			LEFT JOIN nd_barang c
			ON b.barang_id = c.id
			LEFT JOIN nd_warna d
			ON a.warna_id = d.id
			LEFT JOIN nd_satuan e
			ON c.satuan_id = e.id
			ORDER BY d.warna_beli asc
			-- ORDER BY a.id asc
		");
		return $query->result();
	}

	function get_data_barang_po($po_pembelian_id){
		$query = $this->db->query("SELECT tbl_a.*, tbl_b1.nama as nama_barang, tbl_c.nama as nama_satuan, 
			qty - ifnull(qty_order,0) as sisa_kuota
				FROM (
					SELECT *
					FROM nd_po_pembelian_detail
					Where po_pembelian_id = $po_pembelian_id
				) as tbl_a
				LEFT JOIN nd_barang_beli as tbl_b1
				ON tbl_a.barang_beli_id = tbl_b1.id
				LEFT JOIN nd_barang as tbl_b
				ON tbl_a.barang_id = tbl_b.id
				LEFT JOIN nd_satuan as tbl_c
				ON tbl_b.satuan_id = tbl_c.id
				LEFT JOIN (
					SELECT sum(qty) as qty_order, po_pembelian_detail_id
					FROM nd_po_pembelian_warna tA
					LEFT JOIN nd_po_pembelian_batch tB
					ON tA.po_pembelian_batch_id = tB.id
					WHERE tB.status != 0
					GROUP BY po_pembelian_detail_id
					) tbl_d
				ON tbl_d.po_pembelian_detail_id = tbl_a.id
		");
		return $query->result();
	}

	function get_ockh($po_pembelian_batch_id, $barang_id, $warna_id){
		$query = $this->db->query("SELECT t1.*
			FROM (
				SELECT *
				FROM nd_po_pembelian_warna
				WHERE po_pembelian_batch_id = $po_pembelian_batch_id
				AND warna_id = $warna_id
				) t1
			LEFT JOIN (
				SELECT *
				FROM nd_po_pembelian_batch
				WHERE id = $po_pembelian_batch_id
				) t2
			ON t1.po_pembelian_batch_id = t2.id
			LEFT JOIN (
				SELECT *
				FROM nd_po_pembelian_detail
				WHERE barang_id = $barang_id
				) t3
			ON t2.po_pembelian_id = t3.po_pembelian_id
			WHERE t3.id is not null
			", false);

		return $query->result();
	}

	function get_ockh_tipe_beda($po_pembelian_batch_id, $barang_id, $warna_id, $tipe_barang){
		$query = $this->db->query("SELECT t1.*
			FROM (
				SELECT *
				FROM nd_po_pembelian_warna
				WHERE po_pembelian_batch_id = $po_pembelian_batch_id
				AND warna_id = $warna_id
				AND barang_id_baru = $barang_id
				AND tipe_barang = $tipe_barang
				) t1
			LEFT JOIN (
				SELECT *
				FROM nd_po_pembelian_batch
				WHERE id = $po_pembelian_batch_id
				) t2
			ON t1.po_pembelian_batch_id = t2.id
			
			", false);

		return $query->result();
	}

//===============================pembelian===========================================

	function cek_po_multiple_item($po_pembelian_batch_id){
		$query = $this->db->query("SELECT barang_id
			FROM (
				SELECT if(tipe_barang = 1, t2.barang_id, t1.barang_id_baru) as barang_id
				FROM nd_po_pembelian_warna t1
				LEFT JOIN nd_po_pembelian_detail t2
				ON t1.po_pembelian_detail_id = t2.id
				WHERE po_pembelian_batch_id = $po_pembelian_batch_id
				) result
				GROUP BY barang_id
			", false);

		return $query->num_rows();
	}

	function get_pembelian_list_ajax($aColumns, $sWhere, $sOrder, $sLimit){
		$tahun = date('Y');
		$query = $this->db->query("SELECT *
			FROM (
				SELECT tbl_a.status_aktif, tbl_b.nama as toko, no_faktur, tanggal, qty as jumlah, jumlah_roll, nama_barang, tbl_c.harga_beli, tbl_e.nama as gudang, 0 as harga, tbl_f.nama as supplier, concat_ws('??',tbl_a.id, toko_id, gudang_id, supplier_id, ifnull(kode,'-')) as status_data, total, (ifnull(total_bayar,0) - (ifnull(g_total,0) - ifnull(diskon,0))) as keterangan, ockh_info
				FROM (
					SELECT *
					FROM nd_pembelian
					WHERE YEAR(tanggal) >= $tahun ) as tbl_a
				LEFT JOIN nd_toko as tbl_b
				ON tbl_a.toko_id = tbl_b.id
				LEFT JOIN (
					SELECT group_concat(concat_ws(' ',nd_barang.nama,warna_beli) SEPARATOR '??') as nama_barang,  group_concat(nd_pembelian_detail.harga_beli SEPARATOR '??') as harga_beli, group_concat(qty SEPARATOR '??') as qty ,group_concat(jumlah_roll SEPARATOR '??') as jumlah_roll, group_concat((qty *nd_pembelian_detail.harga_beli) SEPARATOR '??') as total, sum(qty *nd_pembelian_detail.harga_beli) as g_total, pembelian_id 
					FROM nd_pembelian_detail 
					LEFT JOIN nd_barang
					ON nd_pembelian_detail.barang_id = nd_barang.id
					LEFT JOIN nd_warna
					ON nd_pembelian_detail.warna_id = nd_warna.id
					LEFT JOIN nd_satuan
					ON nd_barang.satuan_id = nd_satuan.id
					GROUP BY pembelian_id
				) as tbl_c
				ON tbl_c.pembelian_id = tbl_a.id
				LEFT JOIN nd_gudang as tbl_e
				ON tbl_a.gudang_id = tbl_e.id
				LEFT JOIN nd_supplier as tbl_f
				ON tbl_f.id = tbl_a.supplier_id
				
				LEFT JOIN (
					SELECT pembelian_id, sum(amount) as total_bayar
					FROM nd_pembayaran_hutang_detail
					GROUP BY pembelian_id
					) as tbl_d
				ON tbl_d.pembelian_id = tbl_a.id
				-- WHERE 

				) A
			$sWhere
            $sOrder
            $sLimit
			", false);

		return $query;
	}

	

	function data_pembelian_list($id){
		$query = $this->db->query("SELECT tbl_a.*, tbl_b.nama as nama_barang, warna_beli, tbl_d.nama as nama_satuan, tbl_e.nama as nama_gudang 
			FROM (
				SELECT *
				FROM nd_pembelian_barang_list
				WHere pembelian_id = $id
			) as tbl_a
			LEFT JOIN nd_barang as tbl_b
			ON tbl_a.barang_id = tbl_b.id
			LEFT JOIN nd_warna as tbl_c
			ON tbl_a.warna_id = tbl_c.id
			LEFT JOIN nd_satuan as tbl_d
			ON tbl_a.satuan_id = tbl_d.id
			LEFT JOIN nd_gudang as tbl_e
			ON tbl_a.gudang_id = tbl_e.id
		");
		return $query->result();
	}

	function get_data_pembelian($id){
		$query = $this->db->query("SELECT tbl_a.*, tbl_b.nama as nama_supplier, tbl_c.nama as nama_gudang, tbl_d.nama as nama_toko, tbl_b.telepon as telepon_supplier, LPAD(no_nota,4,'0') as no_nota_p, po_number
			FROM (
				SELECT *
				FROM nd_pembelian
				WHere id = $id
			) as tbl_a
			LEFT JOIN nd_supplier as tbl_b
			ON tbl_a.supplier_id = tbl_b.id
			LEFT JOIN nd_gudang as tbl_c
			ON tbl_a.gudang_id = tbl_c.id
			LEFT JOIN nd_toko as tbl_d
			ON tbl_a.toko_id = tbl_d.id
			LEFT JOIN (
				SELECT t1.id, concat(if(t2.tanggal >= '2019-09-27', concat(ifnull(pre_po,''),t4.kode,'/',DATE_FORMAT(t2.tanggal,'%y'),'/',LPAD(po_number,3,'0')) ,concat(LPAD(po_number,3,'0'),'/',DATE_FORMAT(t2.tanggal,'%m'),'/',DATE_FORMAT(t2.tanggal,'%y'))),'-',batch,if(revisi > 1,concat('R',revisi-1),'') ) as po_number, pre_po
				FROM nd_po_pembelian_batch t1
				LEFT JOIN nd_po_pembelian t2
				ON t1.po_pembelian_id = t2.id
				LEFT JOIN nd_toko t3
				ON t2.toko_id = t3.id
				LEFT JOIN nd_supplier t4
				ON t2.supplier_id = t4.id
				)tbl_e
			ON tbl_a.po_pembelian_batch_id = tbl_e.id
			
		");
		return $query->result();
	}

	function get_po_pembelian_data($po_pembelian_batch_id){
		$query = $this->db->query("SELECT *
			FROM nd_po_pembelian_batch
			WHERE id = $po_pembelian_batch_id
			
		");
		foreach ($query->result() as $row) {
			return $row->po_pembelian_id;
		}
	}

	function kartu_po_data($po_pembelian_batch_id, $barang_id, $warna_id){
		$query = $query = $this->db->query("SELECT if(a.tipe_barang = 1, ifnull(d.qty_beli,0), ifnull(tA.qty_beli,0))  as qty_beli, b.id as batch_id , 
		ifnull(a.qty,0) as po_qty, c.id as detail_id, satuan_id, if(a.tipe_barang = 1,d.pembelian_id, tA.pembelian_id) as pembelian_id, 
		if(a.tipe_barang = 1,d.tanggal_beli, tA.tanggal_beli) as tanggal_beli, tipe_barang, nama_baru, tipe_barang, a.id as po_pembelian_warna_id, 
		DATE_FORMAT(a.locked_date,'%Y-%m-%d') as locked_date, if(a.tipe_barang = 1,d.no_faktur, tA.no_faktur)  as no_faktur, a.locked_by, w_2019.qty as qty_2019
			FROM (
				SELECT *
				FROM nd_po_pembelian_batch
				WHERE id = $po_pembelian_batch_id
				) b
			LEFT JOIN (
				SELECT tA.*, tB.nama as nama_baru
				FROM nd_po_pembelian_warna tA
				LEFT JOIN nd_barang tB
				ON tA.barang_id_baru = tB.id
				WHERE warna_id = $warna_id
				) a
			ON a.po_pembelian_batch_id = b.id
			LEFT JOIN (
				SELECT t1.*, satuan_id, t2.nama as nama_barang
				FROM nd_po_pembelian_detail t1
				LEFT JOIN nd_barang t2
				ON t1.barang_id = t2.id
				WHERE barang_id = $barang_id
				) c
			ON a.po_pembelian_detail_id = c.id
			LEFT JOIN (
				SELECT barang_id, warna_id, qty as qty_beli, po_pembelian_batch_id, t2.id as pembelian_id, no_faktur, tanggal as tanggal_beli
				FROM nd_pembelian_detail t1
				LEFT JOIN nd_pembelian t2
				ON t1.pembelian_id = t2.id
				ORDER BY tanggal ASC
				) d
			ON a.warna_id = d.warna_id
			AND b.id = d.po_pembelian_batch_id
			AND d.barang_id = c.barang_id
			LEFT JOIN (
				SELECT tA.id, qty_beli, no_faktur, tanggal_beli, pembelian_id
				FROM (
					SELECT *
					FROM nd_po_pembelian_warna
					WHERE tipe_barang != 1
					AND warna_id = $warna_id
					AND po_pembelian_batch_id = $po_pembelian_batch_id
					) tA
				LEFT JOIN (
					SELECT barang_id, warna_id, ifnull(qty,0) as qty_beli, po_pembelian_batch_id, t2.id as pembelian_id, no_faktur, tanggal as tanggal_beli
					FROM nd_pembelian_detail t1
					LEFT JOIN nd_pembelian t2
					ON t1.pembelian_id = t2.id
					ORDER BY tanggal ASC
					) tB
				ON tA.warna_id = tB.warna_id
				AND tA.po_pembelian_batch_id = tB.po_pembelian_batch_id
				AND tA.barang_id_baru = tB.barang_id
				) tA
			ON a.id = tA.id
			LEFT JOIN nd_po_pembelian_before_qty w_2019
			ON a.id = w_2019.po_pembelian_warna_id
			AND b.id = w_2019.po_pembelian_batch_id
			WHERE a.id is not null
			order by tanggal_beli desc
		");
		return $query->result();
	}

	function kartu_ockh_data($ockh, $barang_id, $warna_id){
		$query = $this->db->query("SELECT t1.*, t2.tanggal as tanggal_beli, sum(ifnull(t1.qty,0)) as qty_beli, no_faktur
			FROM (
				SELECT *
				FROM nd_pembelian_detail
				WHERE ockh = '$ockh'
				AND barang_id = $barang_id
				AND warna_id = $warna_id
				) t1
			LEFT JOIN nd_pembelian t2
			ON t1.pembelian_id = t2.id
			LEFT JOIN (
				SELECT sum(qty*if(jumlah_roll = 0,1,jumlah_roll)) as qty,sum(jumlah_roll) as jumlah_roll, pembelian_detail_id, group_concat(concat(qty,'??',jumlah_roll,'??',id) SEPARATOR '--') as data_qty
				FROM nd_pembelian_qty_detail
				GROUP BY pembelian_detail_id
				) t3
			ON t1.id = t3.pembelian_detail_id
			ORDER BY tanggal, id asc
		");
		return $query->result();
	}

	function get_pembelian_barang_by_po($po_pembelian_batch_id, $OCKH){
		$cond = '';
		if ($OCKH != '') {
			$cond = "AND OCKH = ".$OCKH;
		}

		$query = $this->db->query("SELECT a.id, if(tipe_barang = 1,b.barang_id, barang_id_baru) as barang_id, warna_id, 
		if(tipe_barang =1, c1.nama, nama_baru) as nama , d.warna_beli as nama_warna, 
		ifnull(if(harga_baru = 0, b.harga, harga_baru), b.harga) as harga_beli , 
		tipe_barang, po_pembelian_id, a.qty, locked_by, if(tipe_barang = 1,b.barang_beli_id, barang_beli_id_baru) as barang_beli_id
			FROM (
				SELECT t1.*, t2.nama as nama_baru
				FROM (
					SELECT *
					FROM nd_po_pembelian_warna
					WHERE po_pembelian_batch_id = $po_pembelian_batch_id
					-- AND locked_by is null
					$cond 
					)t1
				LEFT JOIN nd_barang_beli t21
				ON t1.barang_beli_id_baru = t21.id
				LEFT JOIN nd_barang t2
				ON t1.barang_id_baru = t2.id
				) a
			LEFT JOIN nd_po_pembelian_detail b
			ON a.po_pembelian_detail_id = b.id
			LEFT JOIN nd_barang_beli c1
			ON b.barang_beli_id = c1.id
			LEFT JOIN nd_barang c
			ON b.barang_id = c.id
			LEFT JOIN nd_warna d
			ON a.warna_id = d.id
			WHERE b.barang_id is not null
		");
		return $query->result();
	}

	function get_pembelian_warna_by_po($po_pembelian_batch_id, $barang_beli_id, $pembelian_id){
		$query = $this->db->query("SELECT tA.*, warna_beli
			FROM ((
					SELECT warna_id
					FROM (
						SELECT *
						FROM nd_po_pembelian_warna
						WHERE po_pembelian_batch_id = $po_pembelian_batch_id
						AND tipe_barang = 1
						AND locked_by is null
						)t1
					LEFT JOIN (
						SELECT *
						FROM nd_po_pembelian_detail
						WHERE barang_beli_id = $barang_beli_id
						) t2
					ON t1.po_pembelian_detail_id = t2.id
					WHERE t2.id is not null
				)UNION(
					SELECT warna_id 
					FROM nd_po_pembelian_warna
					WHERE po_pembelian_batch_id = $po_pembelian_batch_id
					AND barang_beli_id_baru = $barang_beli_id
					AND tipe_barang != 1
				)UNION(
					SELECT warna_id
					FROM nd_pembelian_detail
					WHERE pembelian_id = $pembelian_id
					GROUP BY warna_id
				)
			)tA
			LEFT JOIN nd_warna tB
			on tA.warna_id = tB.id
			GROUP BY warna_id
		");
		return $query->result();
	}

	function get_pembelian_other($ockh){

		$query = $this->db->query("SELECT tA.*
			FROM (
				SELECT *
				FROM nd_pembelian
				WHERE ockh_info = '$ockh'
				AND ockh_info != ''
				AND ockh_info is not null
				) tA
			LEFT JOIN nd_pembelian_detail tB
			ON tB.pembelian_id = tA.id
			WHERE tB.id is not null
			GROUP BY barang_id
		");
		return $query->result();
	}

	function get_ockh_suggestion($ockh){
		$query = $this->db->query("SELECT *
			FROM (
				(
					SELECT ockh, id, '' as po_pembelian_batch_id
					FROM nd_ockh_non_po
					WHERE ockh LIKE '%$ockh%'
				)UNION(
					SELECT a.ockh, a.id, po_pembelian_batch_id
					FROM (
						SELECT *
						FROM nd_pembelian_detail
						WHERE ockh LIKE '%$ockh%'
						) a
					LEFT JOIN nd_pembelian b
					ON a.pembelian_id = b.id
					LEFT JOIN nd_ockh_non_po c
					ON a.ockh = c.ockh
					WHERE c.id is null
				)
			) result
		GROUP BY ockh
		");
		return $query->result();
	}

	function cek_po_batch_by_ockh($ockh){
		$query = $this->db->query("SELECT a.ockh, a.id, b.po_pembelian_batch_id, 
			concat(if(d.tanggal >= '2019-09-27', concat(ifnull(pre_po,''),e.kode,'/',DATE_FORMAT(d.tanggal,'%y'),'/',LPAD(po_number,3,'0')) ,concat(LPAD(po_number,3,'0'),'/',DATE_FORMAT(d.tanggal,'%m'),'/',DATE_FORMAT(d.tanggal,'%y'))),'-',batch,if(revisi > 1,concat('R',revisi-1),'') ) as po_number, b.supplier_id
					FROM (
						SELECT *
						FROM nd_pembelian_detail
						WHERE ockh = '$ockh'
						) a
					LEFT JOIN nd_pembelian b
					ON a.pembelian_id = b.id
					LEFT JOIN nd_po_pembelian_batch c
					ON b.po_pembelian_batch_id = c.id
					LEFT JOIN nd_po_pembelian d
					ON c.po_pembelian_id = d.id
					LEFT JOIN nd_supplier e
					ON b.supplier_id = e.id
					LEFT JOIN nd_toko f
					ON b.toko_id = f.id
		");
		return $query->result();
	}

//=====================================pembelian detail=====================================================

	function get_data_pembelian_detail($pembelian_id){
		$query = $this->db->query("SELECT a.*, b1.nama as nama_barang, c.nama as nama_satuan, d.warna_beli as nama_warna, data_qty, tipe_qty
			FROM (
				SELECT *
				FROM nd_pembelian_detail
				WHere pembelian_id = $pembelian_id
			) as a
			LEFT JOIN nd_barang_beli as b1
			ON a.barang_beli_id = b1.id
			LEFT JOIN nd_barang as b
			ON a.barang_id = b.id
			LEFT JOIN nd_satuan as c
			ON b.satuan_id = c.id
			LEFT JOIN nd_warna as d
			ON a.warna_id = d.id
			LEFT JOIN (
				SELECT pembelian_detail_id, group_concat(concat(qty,'??',jumlah_roll,'??',id) SEPARATOR '--') as data_qty
				FROM nd_pembelian_qty_detail
				GROUP BY pembelian_detail_id
				) e
			ON a.id = e.pembelian_detail_id
		");
		return $query->result();
	}

//=====================================pembelian lain=====================================================

	function get_data_pembelian_lain($id){
		$query = $this->db->query("SELECT tbl_a.*, tbl_b.nama as nama_supplier
			FROM (
				SELECT *
				FROM nd_pembelian_lain
				WHere id = $id
			) as tbl_a
			LEFT JOIN nd_supplier as tbl_b
			ON tbl_a.supplier_id = tbl_b.id
			
		");
		return $query->result();
	}

	function get_pembelian_lain(){
		$query = $this->db->query("SELECT t1.*, t2.nama as nama_supplier, total
			FROM nd_pembelian_lain t1
			LEFT JOIN nd_supplier as t2
			ON t1.supplier_id = t2.id
			LEFT JOIN (
				SELECT qty*harga_beli as total, pembelian_lain_id
				FROM nd_pembelian_lain_detail
				GROUP BY pembelian_lain_id
			) t3
			ON t3.pembelian_lain_id = t1.id
			
		");
		return $query->result();
	}
	
//==========================================PENJUALAN================================

	function get_penjualan_list_ajax($aColumns, $sWhere, $sOrder, $sLimit){
		$cond = 'WHERE YEAR(tanggal) >='.date('Y');
		if (is_posisi_id() > 3) {
			$cond .= " AND tanggal = '".date('Y-m-d')."'";  
		}
		
		$query = $this->db->query("SELECT *
			FROM (
				SELECT tbl_a.status_aktif, no_faktur as nf, no_faktur_lengkap as no_faktur, tanggal, 
				tbl_e.text as penjualan_type_id, ifnull(g_total,0) as g_total , ifnull(diskon,0) as diskon, 
				ifnull(ongkos_kirim,0) as ongkos_kirim, if(penjualan_type_id = 3,if(nama_keterangan = '','no_name', nama_keterangan), tbl_c.nama) as nama_customer, 
				(ifnull(total_bayar,0) + ifnull(bayar_piutang,0) - (ifnull(g_total,0) - ifnull(diskon,0)) + ongkos_kirim) as keterangan, 
				concat_ws('??',tbl_a.id,no_faktur) as data, if(tbl_a.status = -1,-1,0) as status, count_item
				FROM (
					select t1.id AS id,t1.toko_id ,t1.penjualan_type_id ,t1.no_faktur ,t1.po_number ,t1.tanggal ,
						t1.customer_id ,t1.ppn ,t1.gudang_id,t1.diskon ,t1.jatuh_tempo ,t1.ongkos_kirim,
						t1.keterangan ,t1.nama_keterangan ,t1.alamat_keterangan ,t1.status ,t1.status_aktif ,
						t1.closed_by ,t1.closed_date ,t1.user_id ,t1.created_at ,t1.revisi , t1.fp_status ,
						if((t1.tanggal < '2022-05-01 00:00:00'),concat('FPJ',convert(date_format(t1.tanggal,'%d%m%y') using latin1),'-',ifnull(t2.pre_faktur,''),convert(lpad(t1.no_faktur,5,'0') using latin1)),concat(t2.pre_po,':PJ01/',convert(date_format(t1.tanggal,'%y%m') using latin1),'/',convert(lpad(t1.no_faktur,4,'0') using latin1))) AS no_faktur_lengkap
					from nd_penjualan t1 
					left join nd_toko t2 
					on t1.toko_id = t2.id 
					$cond
					)as tbl_a
				LEFT JOIN (
					SELECT sum(qty *nd_penjualan_detail.harga_jual) as g_total, penjualan_id, sum(1) as count_item
					FROM nd_penjualan_detail
					LEFT JOIN (
						SELECT sum(qty * if(jumlah_roll = 0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, penjualan_detail_id
						FROM nd_penjualan_qty_detail
						group by penjualan_detail_id
						) as nd_penjualan_qty_detail
					ON nd_penjualan_qty_detail.penjualan_detail_id = nd_penjualan_detail.id
					GROUP BY penjualan_id
					) as tbl_b
				ON tbl_b.penjualan_id = tbl_a.id
				LEFT JOIN nd_customer as tbl_c
				ON tbl_a.customer_id = tbl_c.id
				LEFT JOIN (
					SELECT penjualan_id, sum(amount) as total_bayar
					FROM nd_pembayaran_penjualan
					WHERE pembayaran_type_id != 5
					AND pembayaran_type_id != 6
					GROUP BY penjualan_id
					) as tbl_d
				ON tbl_d.penjualan_id = tbl_a.id
				LEFT JOIN nd_penjualan_type tbl_e
				ON tbl_a.penjualan_type_id = tbl_e.id
				LEFT JOIN (
					SELECT SUM(amount) as bayar_piutang, penjualan_id
					FROM nd_pembayaran_piutang_detail t1
					LEFT JOIN (
						SELECT * 
						FROM nd_penjualan
						WHERE status_aktif = 1
					) t2
					ON t1.pembayaran_piutang_id = t2.id
					WHERE t2.id is not null
					GROUP BY penjualan_id
				) as tbl_f
				ON tbl_a.id = tbl_f.penjualan_id

				) A			
			$sWhere
            $sOrder
            $sLimit
			", false);

		return $query;
	}

	function get_kartu_stok_barang_by_satuan($gudang_id, $barang_id, $warna_id, $tanggal_start, $tanggal_end, $tanggal_awal, $stok_opname_id, $cond_detail){
		$query = $this->db->query("SELECT tbl_b.nama_jual as nama_barang, tbl_c.warna_beli as nama_warna, barang_id, warna_id, qty, sum(jumlah_roll_masuk) as jumlah_roll_masuk, sum(jumlah_roll_keluar) as jumlah_roll_keluar,sum(roll_stok_masuk) roll_stok_masuk, sum(roll_stok_keluar) roll_stok_keluar, tipe, 
			group_concat(if(tgl_recap_masuk != '', tgl_recap_masuk,null)) as tgl_recap_masuk, 
			group_concat(if(tgl_recap_keluar != '', tgl_recap_keluar,null)) as tgl_recap_keluar,
			group_concat(if(roll_recap_masuk != '', roll_recap_masuk,null)) as roll_recap_masuk, 
			group_concat(if(roll_recap_keluar != '', roll_recap_keluar,null)) as roll_recap_keluar,
			group_concat(if(ket_recap_masuk != '', ket_recap_masuk,null)) as ket_recap_masuk, 
			group_concat(if(ket_recap_keluar != '', ket_recap_keluar,null)) as ket_recap_keluar
				FROM((
						SELECT barang_id, warna_id, gudang_id, qty, 
						sum(if(tanggal_stok is not null,if(tanggal >= tanggal_stok, jumlah_roll_masuk,0),jumlah_roll_masuk)) as jumlah_roll_masuk, 
						sum(if(tanggal_stok is not null,if(tanggal >= tanggal_stok, jumlah_roll_keluar,0),jumlah_roll_keluar)) as jumlah_roll_keluar, 
						0 as roll_stok_masuk, 
						0 as roll_stok_keluar,'st' as tipe, 
							group_concat(if(jumlah_roll_masuk > 0, if(tanggal_stok is not null,if(tanggal >= tanggal_stok, tanggal,0),tanggal),null) ORDER BY tanggal asc ) as tgl_recap_masuk, 
							group_concat(if(jumlah_roll_keluar > 0, if(tanggal_stok is not null,if(tanggal >= tanggal_stok, tanggal,0),tanggal),null) ORDER BY tanggal asc ) as tgl_recap_keluar,
							
							group_concat(if(jumlah_roll_masuk > 0, jumlah_roll_masuk,null) ORDER BY tanggal asc ) as roll_recap_masuk, 
							group_concat(if(jumlah_roll_keluar > 0, jumlah_roll_keluar,null) ORDER BY tanggal asc ) as roll_recap_keluar,
							group_concat(if(jumlah_roll_masuk > 0, no_faktur,null) ORDER BY tanggal asc ) as ket_recap_masuk, 
							group_concat(if(jumlah_roll_keluar > 0, no_faktur,null) ORDER BY tanggal asc ) as ket_recap_keluar
						FROM (
							(
								SELECT barang_id, warna_id, t2.gudang_id, t3.qty, t3.jumlah_roll as jumlah_roll_masuk, 0 as jumlah_roll_keluar, 'a1' as tipe, t1.id, tanggal, no_faktur
						        FROM (
						        	SELECT id, pembelian_id, barang_id, warna_id
						        	FROM nd_pembelian_detail
						        	WHERE barang_id = $barang_id
						        	AND warna_id = $warna_id
						        	) t1
						        LEFT JOIN (
						        	SELECT *
						        	FROM nd_pembelian
						        	WHERE tanggal >= '$tanggal_start'
						        	AND tanggal <= '$tanggal_end'
						        	AND tanggal >= '$tanggal_awal'
						        	AND gudang_id = $gudang_id
						        	AND status_aktif = 1
						        	) t2
						        ON t1.pembelian_id = t2.id
								LEFT JOIN (
									SELECT pembelian_detail_id, qty as qty, sum(jumlah_roll) as jumlah_roll
									FROM nd_pembelian_qty_detail
									GROUP BY pembelian_detail_id, qty
								) t3
								ON t3.pembelian_detail_id = t1.id
						        WHERE barang_id is not null 
						        AND warna_id is not null
						        AND t2.id is not null
						    )UNION(
						        SELECT barang_id, warna_id, t1.gudang_id, qty , 0, if(jumlah_roll=0,1,jumlah_roll), 'a2', t1.id, tanggal, no_faktur_lengkap
						        FROM (
						        	SELECT *
						        	FROM nd_penjualan_detail 
						        	WHERE gudang_id = $gudang_id 
						        	AND barang_id = $barang_id
						        	AND warna_id = $warna_id
						        	$cond_detail
						        	) t1
						        LEFT JOIN (
						        	SELECT *
									FROM vw_penjualan_data
						        	WHERE tanggal >= '$tanggal_start'
						        	AND tanggal <= '$tanggal_end'
						        	AND tanggal >= '$tanggal_awal'
						        	AND status_aktif = 1
						        	) t2
						        ON t1.penjualan_id = t2.id
						        LEFT JOIN (
						            SELECT qty as qty, sum(jumlah_roll) as jumlah_roll, penjualan_detail_id
						            FROM nd_penjualan_qty_detail
						            GROUP BY penjualan_detail_id, qty
						            ) t3
						        ON t3.penjualan_detail_id = t1.id
						        WHERE barang_id is not null 
						        AND warna_id is not null
						        AND t2.id is not null
						    )UNION(
						        SELECT barang_id, warna_id, t1.gudang_id, qty , 0, if(jumlah_roll=0,1,jumlah_roll), 'a6', t1.id, tanggal, 'lain-lain'
						        FROM (
						        	SELECT *
						        	FROM nd_pengeluaran_stok_lain_detail 
						        	WHERE gudang_id = $gudang_id 
						        	AND barang_id = $barang_id
						        	AND warna_id = $warna_id
						        	) t1
						        LEFT JOIN (
						        	SELECT *, concat('FPJ', DATE_FORMAT(tanggal,'%d%m%y'),'-',LPAD(no_faktur,5,'0')) as no_faktur_lengkap
						        	FROM nd_pengeluaran_stok_lain
						        	WHERE tanggal >= '$tanggal_start'
						        	AND tanggal <= '$tanggal_end'
						        	AND tanggal >= '$tanggal_awal'
						        	AND status_aktif = 1
						        	) t2
						        ON t1.pengeluaran_stok_lain_id = t2.id
						        LEFT JOIN (
						            SELECT qty as qty, sum(jumlah_roll) as jumlah_roll, pengeluaran_stok_lain_detail_id
						            FROM nd_pengeluaran_stok_lain_qty_detail
						            GROUP BY pengeluaran_stok_lain_detail_id, qty
						            ) t3
						        ON t3.pengeluaran_stok_lain_detail_id = t1.id
						        WHERE barang_id is not null 
						        AND warna_id is not null
						        AND t2.id is not null
						    )UNION(
						    	SELECT barang_id, warna_id, t2.gudang_id, qty, jumlah_roll, 0, 'a3', t2.id, tanggal, no_faktur_lengkap
						        FROM (
						        	SELECT *, concat('FRJ', DATE_FORMAT(tanggal,'%d%m%y'),'-',LPAD(no_faktur,4,'0')) as no_faktur_lengkap
						        	FROM nd_retur_jual
						        	WHERE tanggal >= '$tanggal_start'
						        	AND tanggal <= '$tanggal_end'
						        	AND tanggal >= '$tanggal_awal'
						        	AND status_aktif = 1
						        	) t1
						        LEFT JOIN (
						        	SELECT *
						        	FROM nd_retur_jual_detail
						        	WHERE gudang_id = $gudang_id
						        	AND barang_id = $barang_id
						        	AND warna_id = $warna_id
						        	) t2
						        ON t2.retur_jual_id = t1.id
						        LEFT JOIN (
						            SELECT qty, sum(jumlah_roll) as jumlah_roll, retur_jual_detail_id
						            FROM nd_retur_jual_qty
						            GROUP BY retur_jual_detail_id, qty
						            ) t3
						        ON t3.retur_jual_detail_id = t2.id
						        WHERE barang_id is not null 
						        AND warna_id is not null
						    )UNION(
						    	SELECT barang_id, warna_id, t2.gudang_id, qty, 0, jumlah_roll, 'a3r', t2.id, tanggal, no_faktur_lengkap
						        FROM (
						        	SELECT t1.*, concat('SJ/R/',if(t2.kode is not null, concat(t2.kode,'/'),'LL/'),DATE_FORMAT(tanggal,'%y'),'-',no_sj ) as no_faktur_lengkap
						        	FROM nd_retur_beli t1
						        	LEFT JOIN nd_supplier t2
						        	ON t1.supplier_id = t2.id
						        	WHERE tanggal >= '$tanggal_start'
						        	AND tanggal <= '$tanggal_end'
						        	AND tanggal >= '$tanggal_awal'
						        	AND t1.status_aktif = 1
						        	) t1
						        LEFT JOIN (
						        	SELECT *
						        	FROM nd_retur_beli_detail
						        	WHERE gudang_id = $gudang_id
						        	AND barang_id = $barang_id
						        	AND warna_id = $warna_id
						        	) t2
						        ON t2.retur_beli_id = t1.id
						        LEFT JOIN (
						            SELECT qty, sum(jumlah_roll) as jumlah_roll, retur_beli_detail_id
						            FROM nd_retur_beli_qty
						            GROUP BY retur_beli_detail_id, qty
						            ) t3
						        ON t3.retur_beli_detail_id = t2.id
						        WHERE barang_id is not null 
						        AND warna_id is not null
						    )UNION(
						    	SELECT barang_id, warna_id, gudang_id, t2.qty, if(tipe_transaksi = 1,t2.jumlah_roll,0), if(tipe_transaksi = 2 || tipe_transaksi = 3,t2.jumlah_roll,0), 'p1', t2.id, tanggal, 'penyesuaian'
						    	FROM (
						    		SELECT *
						    		FROM nd_penyesuaian_stok
							    	WHERE barang_id = $barang_id
							    	AND warna_id = $warna_id
							    	AND gudang_id = $gudang_id
							    	AND tanggal >= '$tanggal_start'
							    	AND tanggal <= '$tanggal_end'
							    	AND tanggal >= '$tanggal_awal'
							    	AND tipe_transaksi != 0
						    	)t1
								LEFT JOIN 
									(
										SELECT qty, sum(jumlah_roll) as jumlah_roll, penyesuaian_stok_id, id
										FROM nd_penyesuaian_stok_qty 
										GROUP BY penyesuaian_stok_id, qty
									)t2
						    	ON t2.penyesuaian_stok_id = t1.id
						    )UNION(
						    	SELECT barang_id, warna_id, gudang_id_after, t1.qty, t1.jumlah_roll, 0, 'm1', id, tanggal, 'mutasi masuk'
						    	FROM (
						    		SELECT mutasi_barang_id, qty, sum(jumlah_roll) as jumlah_roll
						    		FROM nd_mutasi_barang_qty
						    		GROUP BY mutasi_barang_id, qty
						    		) t1
						    	LEFT JOIN (
						    		SELECT *
						    		FROM nd_mutasi_barang
							    	WHERE barang_id = $barang_id
							    	AND warna_id = $warna_id
							    	AND gudang_id_after = $gudang_id
							    	AND tanggal >= '$tanggal_start'
							    	AND tanggal <= '$tanggal_end'
							    	AND tanggal >= '$tanggal_awal'
						    		) t2
						    	ON t1.mutasi_barang_id = t2.id
						    	WHERE t2.id is not null
					    	)UNION(
						    	SELECT barang_id, warna_id, gudang_id_before, t1.qty, 0, t1.jumlah_roll, 'm2', id, tanggal, 'mutasi keluar'
						    	FROM (
						    		SELECT mutasi_barang_id, qty, sum(jumlah_roll) as jumlah_roll
						    		FROM nd_mutasi_barang_qty
						    		GROUP BY mutasi_barang_id, qty
						    		) t1
						    	LEFT JOIN (
						    		SELECT *
						    		FROM nd_mutasi_barang
							    	WHERE barang_id = $barang_id
							    	AND warna_id = $warna_id
							    	AND gudang_id_before = $gudang_id
							    	AND tanggal >= '$tanggal_start'
							    	AND tanggal <= '$tanggal_end'
							    	AND tanggal >= '$tanggal_awal'
						    		) t2
						    	ON t1.mutasi_barang_id = t2.id
						    	WHERE t2.id is not null						    	
					    	)UNION(
						    	SELECT barang_id, warna_id, gudang_id, qty, jumlah_roll,0,'s1', id, tanggal, 'SO'
						    	FROM (
							    	SELECT barang_id, warna_id, gudang_id, qty, sum(jumlah_roll) as jumlah_roll, stok_opname_id
							    	FROM nd_stok_opname_detail
							    	WHERE barang_id = $barang_id
							    	AND warna_id = $warna_id
							    	AND gudang_id = $gudang_id
							    	GROUP BY stok_opname_id, qty
						    	)t1
						    	LEFT JOIN (
						    		SELECT id, tanggal
						    		FROM nd_stok_opname
						    		WHERE tanggal >= '$tanggal_start'
							    	AND tanggal <= '$tanggal_end'
							    	AND tanggal >= '$tanggal_awal'
							    	AND status_aktif = 1
						    	) t2
						    	ON t1.stok_opname_id = t2.id
						    	WHERE t2.id is not null
						    )UNION(
						    	SELECT barang_id, warna_id, gudang_id, t1.qty, if(t1.jumlah_roll = 0,1,t1.jumlah_roll), 0, 'sp1' as tipe, t1.id, tanggal, 'Split Stok'
						    	FROM nd_penyesuaian_stok_split t1
						    	LEFT JOIN (
						    		SELECT *
						    		FROM nd_penyesuaian_stok
							    	WHERE barang_id = $barang_id
							    	AND warna_id = $warna_id
							    	AND tanggal >= '$tanggal_start'
							    	AND tanggal <= '$tanggal_end'
							    	AND tanggal >= '$tanggal_awal'
						        	AND gudang_id = $gudang_id
							    	AND tipe_transaksi = 3
					    		) t2
								ON t1.penyesuaian_stok_id = t2.id
								WHERE t2.id is not null
						    )
						)tA
						LEFT JOIN (
						    SELECT barang_id as barang_id_stok, warna_id as warna_id_stok, MAX(tanggal) as tanggal_stok
						    FROM (
						        SELECT stok_opname_id, barang_id,warna_id
						        FROM nd_stok_opname_detail
						        WHERE barang_id = $barang_id
						        AND warna_id = $warna_id
						        AND gudang_id = $gudang_id
						        GROUP BY stok_opname_id
						    ) tA
						    LEFT JOIN (
					    		SELECT id, tanggal
					    		FROM nd_stok_opname
					    		WHERE tanggal >= '$tanggal_start'
						    	AND tanggal <= '$tanggal_end'
						    	AND tanggal >= '$tanggal_awal'
						    	AND status_aktif = 1
					    	) tB
						    ON tA.stok_opname_id = tB.id
						    WHERE tB.id is not null
						    GROUP BY barang_id, warna_id
						) t2
						ON tA.barang_id = t2.barang_id_stok
						AND tA.warna_id = t2.warna_id_stok
						GROUP BY qty
					-- batas, di atas stok
					)UNION(
					-- batas, di bawah stok_awal
						SELECT barang_id, warna_id, gudang_id, qty, 0, 0, 
						sum(if(tanggal_stok is not null,if(tanggal >= tanggal_stok, roll_stok_masuk,0),roll_stok_masuk)) , 
						sum(if(tanggal_stok is not null,if(tanggal >= tanggal_stok, roll_stok_keluar,0),roll_stok_keluar)) ,
						'sta' as tipe,'','','','','',''
						FROM (
							(
						        SELECT barang_id, warna_id, t2.gudang_id, t3.qty, t3.jumlah_roll as roll_stok_masuk, 0 as roll_stok_keluar, 'a1' as tipe, t1.id, tanggal
						        FROM (
						        	SELECT *
						        	FROM nd_pembelian_detail
						        	WHERE barang_id = $barang_id
						        	AND warna_id = $warna_id
						        	) t1
						        LEFT JOIN (
						        	SELECT *
						        	FROM nd_pembelian
						        	WHERE tanggal < '$tanggal_start'
						        	AND tanggal >= '$tanggal_awal'
						        	AND gudang_id = $gudang_id
						        	AND status_aktif = 1
						        	) t2
						        ON t1.pembelian_id = t2.id
								LEFT JOIN (
									SELECT pembelian_detail_id, qty as qty, sum(jumlah_roll) as jumlah_roll
									FROM nd_pembelian_qty_detail
									GROUP BY pembelian_detail_id, qty
								) t3
								ON t3.pembelian_detail_id = t1.id
						        WHERE barang_id is not null 
						        AND warna_id is not null
						        AND t2.id is not null
						    )UNION(
						        SELECT barang_id, warna_id, t1.gudang_id, qty ,0, jumlah_roll, 'a2' as tipe, t1.id, tanggal
						        FROM (
						        	SELECT *
						        	FROM nd_penjualan_detail 
						        	WHERE gudang_id = $gudang_id 
						        	AND barang_id = $barang_id
						        	AND warna_id = $warna_id
						        	$cond_detail
						        	) t1
						        LEFT JOIN (
						        	SELECT *
									FROM vw_penjualan_data
						        	WHERE tanggal < '$tanggal_start'
						        	AND tanggal >= '$tanggal_awal'
						        	AND status_aktif = 1
						        	) t2
						        ON t1.penjualan_id = t2.id
						        LEFT JOIN (
						            SELECT qty as qty, sum(jumlah_roll) as jumlah_roll, penjualan_detail_id
						            FROM nd_penjualan_qty_detail
						            GROUP BY penjualan_detail_id, qty
						            ) t3
						        ON t3.penjualan_detail_id = t1.id
						        WHERE barang_id is not null 
						        AND warna_id is not null
						        AND t2.id is not null
						    )UNION(
						        SELECT barang_id, warna_id, t1.gudang_id, qty ,0, jumlah_roll, 'a6' as tipe, t1.id, tanggal
						        FROM (
						        	SELECT *
						        	FROM nd_pengeluaran_stok_lain_detail 
						        	WHERE gudang_id = $gudang_id 
						        	AND barang_id = $barang_id
						        	AND warna_id = $warna_id
						        	) t1
						        LEFT JOIN (
						        	SELECT *, concat('FPJ', DATE_FORMAT(tanggal,'%d%m%y'),'-',LPAD(no_faktur,5,'0')) as no_faktur_lengkap
						        	FROM nd_pengeluaran_stok_lain
						        	WHERE tanggal < '$tanggal_start'
						        	AND tanggal >= '$tanggal_awal'
						        	AND status_aktif = 1
						        	) t2
						        ON t1.pengeluaran_stok_lain_id = t2.id
						        LEFT JOIN (
						            SELECT qty as qty, sum(jumlah_roll) as jumlah_roll, pengeluaran_stok_lain_detail_id
						            FROM nd_pengeluaran_stok_lain_qty_detail
						            GROUP BY pengeluaran_stok_lain_detail_id, qty
						            ) t3
						        ON t3.pengeluaran_stok_lain_detail_id = t1.id
						        WHERE barang_id is not null 
						        AND warna_id is not null
						        AND t2.id is not null
						    )UNION(
						    	SELECT barang_id, warna_id, t2.gudang_id, qty, jumlah_roll, 0, 'a3' as tipe, t2.id, tanggal
						        FROM (
						        	SELECT *, concat('FRJ', DATE_FORMAT(tanggal,'%d%m%y'),'-',LPAD(no_faktur,4,'0')) as no_faktur_lengkap
						        	FROM nd_retur_jual
						        	WHERE tanggal < '$tanggal_start'
						        	AND tanggal >= '$tanggal_awal'
						        	AND status_aktif = 1
						        	) t1
						        LEFT JOIN (
						        	SELECT *
						        	FROM nd_retur_jual_detail
						        	WHERE gudang_id = $gudang_id
						        	AND barang_id = $barang_id
						        	AND warna_id = $warna_id
						        	) t2
						        ON t2.retur_jual_id = t1.id
						        LEFT JOIN (
						            SELECT qty, sum(jumlah_roll) as jumlah_roll, retur_jual_detail_id
						            FROM nd_retur_jual_qty
						            GROUP BY retur_jual_detail_id, qty
						            ) t3
						        ON t3.retur_jual_detail_id = t2.id
						        WHERE barang_id is not null 
						        AND warna_id is not null
							)UNION(
						    	SELECT barang_id, warna_id, t2.gudang_id, qty, 0, jumlah_roll, 'a3' as tipe, t2.id, tanggal
						        FROM (
						        	SELECT t1.*, concat('SJ/R/',if(t2.kode is not null, concat(t2.kode,'/'),'LL/'),DATE_FORMAT(tanggal,'%y'),'-',no_sj ) as no_faktur_lengkap
						        	FROM nd_retur_beli t1
						        	LEFT JOIN nd_supplier t2
						        	ON t1.supplier_id = t2.id
						        	WHERE tanggal < '$tanggal_start'
						        	AND tanggal >= '$tanggal_awal'
						        	AND t1.status_aktif = 1
						        	) t1
						        LEFT JOIN (
						        	SELECT *
						        	FROM nd_retur_beli_detail
						        	WHERE gudang_id = $gudang_id
						        	AND barang_id = $barang_id
						        	AND warna_id = $warna_id
						        	) t2
						        ON t2.retur_beli_id = t1.id
						        LEFT JOIN (
						            SELECT qty, sum(jumlah_roll) as jumlah_roll, retur_beli_detail_id
						            FROM nd_retur_beli_qty
						            GROUP BY retur_beli_detail_id, qty
						            ) t3
						        ON t3.retur_beli_detail_id = t2.id
						        WHERE barang_id is not null 
						        AND warna_id is not null
							)UNION(
						    	SELECT barang_id, warna_id, gudang_id, t2.qty, if(tipe_transaksi = 1,t2.jumlah_roll,0), if(tipe_transaksi = 2 || tipe_transaksi = 3 ,t2.jumlah_roll,0),'p1' as tipe, t2.id, tanggal
						    	FROM (
						    		SELECT *
						    		FROM nd_penyesuaian_stok
							    	WHERE barang_id = $barang_id
							    	AND warna_id = $warna_id
						        	AND gudang_id = $gudang_id
							    	AND tanggal < '$tanggal_start'
							    	AND tanggal >= '$tanggal_awal'
							    	AND tipe_transaksi != 0
						    	)t1
								LEFT JOIN 
									(
										SELECT qty, sum(jumlah_roll) as jumlah_roll, penyesuaian_stok_id, id
										FROM nd_penyesuaian_stok_qty 
										GROUP BY penyesuaian_stok_id, qty
									)t2
						    	ON t2.penyesuaian_stok_id = t1.id
						    )UNION(
						    	SELECT barang_id, warna_id, gudang_id_after, t1.qty, t1.jumlah_roll, 0, 'm1', id, tanggal
						    	FROM (
						    		SELECT mutasi_barang_id, qty, sum(jumlah_roll) as jumlah_roll
						    		FROM nd_mutasi_barang_qty
						    		GROUP BY mutasi_barang_id, qty
						    		) t1
						    	LEFT JOIN (
						    		SELECT *
						    		FROM nd_mutasi_barang
							    	WHERE barang_id = $barang_id
							    	AND warna_id = $warna_id
							    	AND gudang_id_after = $gudang_id
							    	AND tanggal < '$tanggal_start'
							    	AND tanggal >= '$tanggal_awal'
						    		) t2
						    	ON t1.mutasi_barang_id = t2.id
						    	WHERE t2.id is not null
					    	)UNION(
						    	SELECT barang_id, warna_id, gudang_id_before, t1.qty, 0, t1.jumlah_roll, 'm2', id, tanggal
						    	FROM (
						    		SELECT mutasi_barang_id, qty, sum(jumlah_roll) as jumlah_roll
						    		FROM nd_mutasi_barang_qty
						    		GROUP BY mutasi_barang_id, qty
						    		) t1
						    	LEFT JOIN (
						    		SELECT *
						    		FROM nd_mutasi_barang
							    	WHERE barang_id = $barang_id
							    	AND warna_id = $warna_id
							    	AND gudang_id_before = $gudang_id
							    	AND tanggal < '$tanggal_start'
							    	AND tanggal >= '$tanggal_awal'
						    		) t2
						    	ON t1.mutasi_barang_id = t2.id
						    	WHERE t2.id is not null		    	
					    	)UNION(
						    	SELECT barang_id, warna_id, gudang_id, qty, jumlah_roll,0,'s1', id, tanggal
						    	FROM (
							    	SELECT barang_id, warna_id, gudang_id, qty, sum(jumlah_roll) as jumlah_roll, stok_opname_id
							    	FROM nd_stok_opname_detail
							    	WHERE barang_id = $barang_id
							    	AND warna_id = $warna_id
							    	AND gudang_id = $gudang_id
							    	GROUP BY stok_opname_id, qty
						    	)t1
						    	LEFT JOIN (
						    		SELECT id, tanggal
						    		FROM nd_stok_opname
						    		WHERE tanggal < '$tanggal_start'
							    	AND tanggal >= '$tanggal_awal'
							    	AND status_aktif = 1
						    	) t2
						    	ON t1.stok_opname_id = t2.id
						    	WHERE t2.id is not null
						    )UNION(
						    	SELECT barang_id, warna_id, gudang_id, t1.qty, t1.jumlah_roll, 0, 'sp1' as tipe, t1.id, tanggal
						    	FROM nd_penyesuaian_stok_split t1
						    	LEFT JOIN (
						    		SELECT *
						    		FROM nd_penyesuaian_stok
							    	WHERE barang_id = $barang_id
							    	AND warna_id = $warna_id
							    	AND tanggal < '$tanggal_start'
							    	AND tanggal >= '$tanggal_awal'
						        	AND gudang_id = $gudang_id
							    	AND tipe_transaksi = 3
					    		) t2
								ON t1.penyesuaian_stok_id = t2.id
								WHERE t2.id is not null
						    )
						)tA
						LEFT JOIN (
						    SELECT barang_id as barang_id_stok, warna_id as warna_id_stok, MAX(tanggal) as tanggal_stok
						    FROM (
						        SELECT stok_opname_id, barang_id,warna_id
						        FROM nd_stok_opname_detail
						        WHERE barang_id = $barang_id
						        AND warna_id = $warna_id
						        AND gudang_id = $gudang_id
						        GROUP BY stok_opname_id
						    ) tA
						    LEFT JOIN (
					    		SELECT id, tanggal
					    		FROM nd_stok_opname
					    		WHERE tanggal <= '$tanggal_start'
						    	AND tanggal >= '$tanggal_awal'
						    	AND status_aktif = 1
					    	) tB
						    ON tA.stok_opname_id = tB.id
						    WHERE tB.id is not null
						    GROUP BY barang_id, warna_id
						) t2
						ON tA.barang_id = t2.barang_id_stok
						AND tA.warna_id = t2.warna_id_stok
						GROUP BY qty
					)
				) tbl_a
				LEFT JOIN nd_barang tbl_b
				ON tbl_a.barang_id = tbl_b.id
				LEFT JOIN nd_warna tbl_c
				ON tbl_a.warna_id = tbl_c.id
				Where barang_id is not null
				AND qty is not null
				GROUP BY qty
				ORDER BY qty asc
				");
		
		return $query->result();
		// return $this->db->last_query();
	}

	function get_kartu_stok_barang_by_satuan_2($gudang_id, $barang_id, $warna_id, $tanggal_start, $tanggal_end, $tanggal_awal, $stok_opname_id, $cond_detail){
		$tanggal_start = $tanggal_start.' 00:00:00';
		$tanggal_end = $tanggal_end.' 23:59:59';
		$query = $this->db->query("SELECT tbl_b.nama_jual as nama_barang, tbl_c.warna_beli as nama_warna, barang_id, warna_id, qty, 
			sum(jumlah_roll_masuk) as jumlah_roll_masuk, sum(jumlah_roll_keluar) as jumlah_roll_keluar,
			sum(roll_stok_masuk) roll_stok_masuk, sum(roll_stok_keluar) roll_stok_keluar, tipe, 
			group_concat(if(tgl_recap_masuk != '', tgl_recap_masuk,null)) as tgl_recap_masuk, 
			group_concat(if(tgl_recap_keluar != '', tgl_recap_keluar,null)) as tgl_recap_keluar,
			group_concat(if(roll_recap_masuk != '', roll_recap_masuk,null)) as roll_recap_masuk, 
			group_concat(if(roll_recap_keluar != '', roll_recap_keluar,null)) as roll_recap_keluar,
			group_concat(if(ket_recap_masuk != '', ket_recap_masuk,null)) as ket_recap_masuk, 
			group_concat(if(ket_recap_keluar != '', ket_recap_keluar,null)) as ket_recap_keluar
				FROM((
						SELECT barang_id, warna_id, gudang_id, qty, 
						sum(if(tanggal_stok is not null,if(time_stamp >= tanggal_stok, jumlah_roll_masuk,0),jumlah_roll_masuk)) as jumlah_roll_masuk, 
						sum(if(tanggal_stok is not null,if(time_stamp >= tanggal_stok, jumlah_roll_keluar,0),jumlah_roll_keluar)) as jumlah_roll_keluar, 
						0 as roll_stok_masuk, 
						0 as roll_stok_keluar,'st' as tipe, 
							group_concat(if(jumlah_roll_masuk > 0, if(tanggal_stok is not null,if(time_stamp >= tanggal_stok, time_stamp,0),time_stamp),null) ORDER BY time_stamp asc ) as tgl_recap_masuk, 
							group_concat(if(jumlah_roll_keluar > 0, if(tanggal_stok is not null,if(time_stamp >= tanggal_stok, time_stamp,0),time_stamp),null) ORDER BY time_stamp asc ) as tgl_recap_keluar,
							
							group_concat(if(jumlah_roll_masuk > 0, jumlah_roll_masuk,null) ORDER BY time_stamp asc ) as roll_recap_masuk, 
							group_concat(if(jumlah_roll_keluar > 0, jumlah_roll_keluar,null) ORDER BY time_stamp asc ) as roll_recap_keluar,
							group_concat(if(jumlah_roll_masuk > 0, no_faktur,null) ORDER BY time_stamp asc ) as ket_recap_masuk, 
							group_concat(if(jumlah_roll_keluar > 0, no_faktur,null) ORDER BY time_stamp asc ) as ket_recap_keluar
						FROM (
							(
								SELECT barang_id, warna_id, t2.gudang_id, t3.qty, t3.jumlah_roll as jumlah_roll_masuk, 0 as jumlah_roll_keluar, 'a1' as tipe, t1.id, tanggal, no_faktur, created_at as time_stamp
						        FROM (
						        	SELECT id, pembelian_id, barang_id, warna_id
						        	FROM nd_pembelian_detail
						        	WHERE barang_id = $barang_id
						        	AND warna_id = $warna_id
						        	) t1
						        LEFT JOIN (
						        	SELECT *
						        	FROM nd_pembelian
						        	WHERE created_at >= '$tanggal_start'
						        	AND created_at <= '$tanggal_end'
						        	AND created_at >= '$tanggal_awal'
						        	AND gudang_id = $gudang_id
						        	AND status_aktif = 1
						        	) t2
						        ON t1.pembelian_id = t2.id
								LEFT JOIN (
									SELECT pembelian_detail_id, qty as qty, sum(jumlah_roll) as jumlah_roll
									FROM nd_pembelian_qty_detail
									GROUP BY pembelian_detail_id, qty
								) t3
								ON t3.pembelian_detail_id = t1.id
						        WHERE barang_id is not null 
						        AND warna_id is not null
						        AND t2.id is not null
						    )UNION(
						        SELECT barang_id, warna_id, t1.gudang_id, qty , 0, if(jumlah_roll=0,1,jumlah_roll), 'a2', t1.id, tanggal, no_faktur, ifnull(closed_date, t2.created_at)  as time_stamp
						        FROM (
						        	SELECT *
						        	FROM nd_penjualan_detail 
						        	WHERE gudang_id = $gudang_id 
						        	AND barang_id = $barang_id
						        	AND warna_id = $warna_id
									$cond_detail
						        	) t1
						        LEFT JOIN (
						        	SELECT *
									FROM nd_penjualan
						        	WHERE ifnull(closed_date, created_at) >= '$tanggal_start'
						        	AND ifnull(closed_date, created_at) <= '$tanggal_end'
						        	AND ifnull(closed_date, created_at) >= '$tanggal_awal'
						        	AND status_aktif = 1
						        	) t2
						        ON t1.penjualan_id = t2.id
						        LEFT JOIN (
						            SELECT qty as qty, sum(jumlah_roll) as jumlah_roll, penjualan_detail_id
						            FROM nd_penjualan_qty_detail
						            GROUP BY penjualan_detail_id, qty
						            ) t3
						        ON t3.penjualan_detail_id = t1.id
						        WHERE barang_id is not null 
						        AND warna_id is not null
						        AND t2.id is not null
						    )UNION(
						        SELECT barang_id, warna_id, t1.gudang_id, qty , 0, if(jumlah_roll=0,1,jumlah_roll), 'a6', t1.id, tanggal, 'lain-lain', t2.created_at
						        FROM (
						        	SELECT *
						        	FROM nd_pengeluaran_stok_lain_detail 
						        	WHERE gudang_id = $gudang_id 
						        	AND barang_id = $barang_id
						        	AND warna_id = $warna_id
						        	) t1
						        LEFT JOIN (
						        	SELECT *, concat('FPJ', DATE_FORMAT(tanggal,'%d%m%y'),'-',LPAD(no_faktur,5,'0')) as no_faktur_lengkap
						        	FROM nd_pengeluaran_stok_lain
						        	WHERE created_at >= '$tanggal_start'
						        	AND created_at <= '$tanggal_end'
						        	AND created_at >= '$tanggal_awal'
						        	AND status_aktif = 1
						        	) t2
						        ON t1.pengeluaran_stok_lain_id = t2.id
						        LEFT JOIN (
						            SELECT qty as qty, sum(jumlah_roll) as jumlah_roll, pengeluaran_stok_lain_detail_id
						            FROM nd_pengeluaran_stok_lain_qty_detail
						            GROUP BY pengeluaran_stok_lain_detail_id, qty
						            ) t3
						        ON t3.pengeluaran_stok_lain_detail_id = t1.id
						        WHERE barang_id is not null 
						        AND warna_id is not null
						        AND t2.id is not null
						    )UNION(
						    	SELECT barang_id, warna_id, t2.gudang_id, qty, jumlah_roll, 0, 'a3', t2.id, tanggal, no_faktur_lengkap, created_at
						        FROM (
						        	SELECT *, concat('FRJ', DATE_FORMAT(tanggal,'%d%m%y'),'-',LPAD(no_faktur,4,'0')) as no_faktur_lengkap
						        	FROM nd_retur_jual
						        	WHERE created_at >= '$tanggal_start'
						        	AND created_at <= '$tanggal_end'
						        	AND created_at >= '$tanggal_awal'
						        	AND status_aktif = 1
						        	) t1
						        LEFT JOIN (
						        	SELECT *
						        	FROM nd_retur_jual_detail
						        	WHERE gudang_id = $gudang_id
						        	AND barang_id = $barang_id
						        	AND warna_id = $warna_id
						        	) t2
						        ON t2.retur_jual_id = t1.id
						        LEFT JOIN (
						            SELECT qty, sum(jumlah_roll) as jumlah_roll, retur_jual_detail_id
						            FROM nd_retur_jual_qty
						            GROUP BY retur_jual_detail_id, qty
						            ) t3
						        ON t3.retur_jual_detail_id = t2.id
						        WHERE barang_id is not null 
						        AND warna_id is not null
						    )UNION(
						    	SELECT barang_id, warna_id, t2.gudang_id, qty, 0, jumlah_roll, 'a3r', t2.id, tanggal, no_faktur_lengkap, created_at
						        FROM (
						        	SELECT t1.*, concat('SJ/R/',if(t2.kode is not null, concat(t2.kode,'/'),'LL/'),DATE_FORMAT(tanggal,'%y'),'-',no_sj ) as no_faktur_lengkap
						        	FROM nd_retur_beli t1
						        	LEFT JOIN nd_supplier t2
						        	ON t1.supplier_id = t2.id
						        	WHERE created_at >= '$tanggal_start'
						        	AND created_at <= '$tanggal_end'
						        	AND created_at >= '$tanggal_awal'
						        	AND t1.status_aktif = 1
						        	) t1
						        LEFT JOIN (
						        	SELECT *
						        	FROM nd_retur_beli_detail
						        	WHERE gudang_id = $gudang_id
						        	AND barang_id = $barang_id
						        	AND warna_id = $warna_id
						        	) t2
						        ON t2.retur_beli_id = t1.id
						        LEFT JOIN (
						            SELECT qty, sum(jumlah_roll) as jumlah_roll, retur_beli_detail_id
						            FROM nd_retur_beli_qty
						            GROUP BY retur_beli_detail_id, qty
						            ) t3
						        ON t3.retur_beli_detail_id = t2.id
						        WHERE barang_id is not null 
						        AND warna_id is not null
						    )UNION(
						    	SELECT barang_id, warna_id, gudang_id, 
								if(tipe_transaksi = 1 || tipe_transaksi = 2,t2.qty, t3.qty) , 
								if(tipe_transaksi = 1,t2.jumlah_roll,if(tipe_transaksi=3,t3.jumlah_roll,0)), 
								if(tipe_transaksi = 2, t2.jumlah_roll, if(tipe_transaksi = 3,t1.jumlah_roll,0)) , 
								'p1', t2.id, tanggal, 'penyesuaian', created_at
						    	FROM (
						    		SELECT *
						    		FROM nd_penyesuaian_stok
							    	WHERE barang_id = $barang_id
							    	AND warna_id = $warna_id
							    	AND gudang_id = $gudang_id
							    	AND created_at >= '$tanggal_start'
							    	AND created_at <= '$tanggal_end'
							    	AND created_at >= '$tanggal_awal'
							    	AND tipe_transaksi != 0
						    	)t1
								LEFT JOIN 
									(
										SELECT qty, sum(jumlah_roll) as jumlah_roll, penyesuaian_stok_id, id
										FROM nd_penyesuaian_stok_qty 
										GROUP BY penyesuaian_stok_id, qty
									)t2
						    	ON t2.penyesuaian_stok_id = t1.id
						    	LEFT JOIN 
									(
										SELECT sum(qty*if(jumlah_roll=0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, penyesuaian_stok_id, id
										FROM nd_penyesuaian_stok_split 
										GROUP BY penyesuaian_stok_id
									)t3
						    	ON t3.penyesuaian_stok_id = t1.id
						    )UNION(
						    	SELECT barang_id, warna_id, gudang_id_after, t1.qty, t1.jumlah_roll, 0, 'm1', id, tanggal, 'mutasi masuk', created_at
						    	FROM (
						    		SELECT mutasi_barang_id, qty, sum(jumlah_roll) as jumlah_roll
						    		FROM nd_mutasi_barang_qty
						    		GROUP BY mutasi_barang_id, qty
						    		) t1
						    	LEFT JOIN (
						    		SELECT *
						    		FROM nd_mutasi_barang
							    	WHERE barang_id = $barang_id
							    	AND warna_id = $warna_id
							    	AND gudang_id_after = $gudang_id
							    	AND created_at >= '$tanggal_start'
							    	AND created_at <= '$tanggal_end'
							    	AND created_at >= '$tanggal_awal'
							    	AND status_aktif = 1
						    		) t2
						    	ON t1.mutasi_barang_id = t2.id
						    	WHERE t2.id is not null
					    	)UNION(
						    	SELECT barang_id, warna_id, gudang_id_before, t1.qty, 0, t1.jumlah_roll, 'm2', id, tanggal, 'mutasi keluar', created_at
						    	FROM (
						    		SELECT mutasi_barang_id, qty, sum(jumlah_roll) as jumlah_roll
						    		FROM nd_mutasi_barang_qty
						    		GROUP BY mutasi_barang_id, qty
						    		) t1
						    	LEFT JOIN (
						    		SELECT *
						    		FROM nd_mutasi_barang
							    	WHERE barang_id = $barang_id
							    	AND warna_id = $warna_id
							    	AND gudang_id_before = $gudang_id
							    	AND created_at >= '$tanggal_start'
							    	AND created_at <= '$tanggal_end'
							    	AND created_at >= '$tanggal_awal'
							    	AND status_aktif = 1
						    		) t2
						    	ON t1.mutasi_barang_id = t2.id
						    	WHERE t2.id is not null						    	
					    	)UNION(
						    	SELECT barang_id, warna_id, gudang_id, qty, jumlah_roll,0,'s1', id, tanggal, 'SO', created_at
						    	FROM (
							    	SELECT barang_id, warna_id, gudang_id, qty, sum(jumlah_roll) as jumlah_roll, stok_opname_id
							    	FROM nd_stok_opname_detail
							    	WHERE barang_id = $barang_id
							    	AND warna_id = $warna_id
							    	AND gudang_id = $gudang_id
							    	GROUP BY stok_opname_id, qty
						    	)t1
						    	LEFT JOIN (
						    		SELECT id, tanggal, created_at
						    		FROM nd_stok_opname
						    		WHERE created_at >= '$tanggal_start'
							    	AND created_at <= '$tanggal_end'
							    	AND created_at >= '$tanggal_awal'
							    	AND status_aktif = 1
						    	) t2
						    	ON t1.stok_opname_id = t2.id
						    	WHERE t2.id is not null
						    )UNION(
						    	SELECT barang_id, warna_id, gudang_id, t1.qty, if(t1.jumlah_roll = 0,1,t1.jumlah_roll), 0, 'sp1' as tipe, t1.id, tanggal, 'Split Stok', created_at
						    	FROM nd_penyesuaian_stok_split t1
						    	LEFT JOIN (
						    		SELECT *
						    		FROM nd_penyesuaian_stok
							    	WHERE barang_id = $barang_id
							    	AND warna_id = $warna_id
							    	AND created_at >= '$tanggal_start'
							    	AND created_at <= '$tanggal_end'
							    	AND created_at >= '$tanggal_awal'
						        	AND gudang_id = $gudang_id
							    	AND tipe_transaksi = 3
									AND gudang_id=$gudang_id
					    		) t2
								ON t1.penyesuaian_stok_id = t2.id
								WHERE t2.id is not null
						    )
						)tA
						LEFT JOIN (
						    SELECT barang_id as barang_id_stok, warna_id as warna_id_stok, MAX(created_at) as tanggal_stok
						    FROM (
						        SELECT stok_opname_id, barang_id,warna_id
						        FROM nd_stok_opname_detail
						        WHERE barang_id = $barang_id
						        AND warna_id = $warna_id
						        AND gudang_id = $gudang_id
						        GROUP BY stok_opname_id
						    ) tA
						    LEFT JOIN (
					    		SELECT id, tanggal, created_at
					    		FROM nd_stok_opname
					    		WHERE tanggal >= '$tanggal_start'
						    	AND tanggal <= '$tanggal_end'
						    	AND tanggal >= '$tanggal_awal'
						    	AND status_aktif = 1
					    	) tB
						    ON tA.stok_opname_id = tB.id
						    WHERE tB.id is not null
						    GROUP BY barang_id, warna_id
						) t2
						ON tA.barang_id = t2.barang_id_stok
						AND tA.warna_id = t2.warna_id_stok
						GROUP BY qty
					-- batas, di atas stok
					)UNION(
					-- batas, di bawah stok_awal
						SELECT barang_id, warna_id, gudang_id, qty, 0, 0, 
						sum(if(tanggal_stok is not null,if(time_stamp >= tanggal_stok, roll_stok_masuk,0),roll_stok_masuk)) , 
						sum(if(tanggal_stok is not null,if(time_stamp >= tanggal_stok, roll_stok_keluar,0),roll_stok_keluar)) ,
						'sta' as tipe,'','','','','',''
						FROM (
							(
						        SELECT barang_id, warna_id, t2.gudang_id, t3.qty, t3.jumlah_roll as roll_stok_masuk, 0 as roll_stok_keluar, 'a1' as tipe, t1.id, tanggal, created_at as time_stamp
						        FROM (
						        	SELECT *
						        	FROM nd_pembelian_detail
						        	WHERE barang_id = $barang_id
						        	AND warna_id = $warna_id
						        	) t1
						        LEFT JOIN (
						        	SELECT *
						        	FROM nd_pembelian
						        	WHERE created_at < '$tanggal_start'
						        	AND created_at >= '$tanggal_awal'
						        	AND gudang_id = $gudang_id
						        	AND status_aktif = 1
						        	) t2
						        ON t1.pembelian_id = t2.id
								LEFT JOIN (
									SELECT pembelian_detail_id, qty as qty, sum(jumlah_roll) as jumlah_roll
									FROM nd_pembelian_qty_detail
									GROUP BY pembelian_detail_id, qty
								) t3
								ON t3.pembelian_detail_id = t1.id
						        WHERE barang_id is not null 
						        AND warna_id is not null
						        AND t2.id is not null
						    )UNION(
						        SELECT barang_id, warna_id, t1.gudang_id, qty ,0, jumlah_roll, 'a2' as tipe, t1.id, tanggal, ifnull(closed_date,t2.created_at)
						        FROM (
						        	SELECT *
						        	FROM nd_penjualan_detail 
						        	WHERE gudang_id = $gudang_id 
						        	AND barang_id = $barang_id
						        	AND warna_id = $warna_id
									$cond_detail
						        	) t1
						        LEFT JOIN (
						        	SELECT *
									FROM nd_penjualan
						        	WHERE ifnull(closed_date,created_at) < '$tanggal_start'
						        	AND ifnull(closed_date,created_at) >= '$tanggal_awal'
						        	AND status_aktif = 1
						        	) t2
						        ON t1.penjualan_id = t2.id
						        LEFT JOIN (
						            SELECT qty as qty, sum(jumlah_roll) as jumlah_roll, penjualan_detail_id
						            FROM nd_penjualan_qty_detail
						            GROUP BY penjualan_detail_id, qty
						            ) t3
						        ON t3.penjualan_detail_id = t1.id
						        WHERE barang_id is not null 
						        AND warna_id is not null
						        AND t2.id is not null
						    )UNION(
						        SELECT barang_id, warna_id, t1.gudang_id, qty ,0, jumlah_roll, 'a6' as tipe, t1.id, tanggal, t2.created_at
						        FROM (
						        	SELECT *
						        	FROM nd_pengeluaran_stok_lain_detail 
						        	WHERE gudang_id = $gudang_id 
						        	AND barang_id = $barang_id
						        	AND warna_id = $warna_id
						        	) t1
						        LEFT JOIN (
						        	SELECT *, concat('FPJ', DATE_FORMAT(tanggal,'%d%m%y'),'-',LPAD(no_faktur,5,'0')) as no_faktur_lengkap
						        	FROM nd_pengeluaran_stok_lain
						        	WHERE created_at < '$tanggal_start'
						        	AND created_at >= '$tanggal_awal'
						        	AND status_aktif = 1
						        	) t2
						        ON t1.pengeluaran_stok_lain_id = t2.id
						        LEFT JOIN (
						            SELECT qty as qty, sum(jumlah_roll) as jumlah_roll, pengeluaran_stok_lain_detail_id
						            FROM nd_pengeluaran_stok_lain_qty_detail
						            GROUP BY pengeluaran_stok_lain_detail_id, qty
						            ) t3
						        ON t3.pengeluaran_stok_lain_detail_id = t1.id
						        WHERE barang_id is not null 
						        AND warna_id is not null
						        AND t2.id is not null
						    )UNION(
						    	SELECT barang_id, warna_id, t2.gudang_id, qty, jumlah_roll, 0, 'a3' as tipe, t2.id, tanggal, created_at
						        FROM (
						        	SELECT *, concat('FRJ', DATE_FORMAT(tanggal,'%d%m%y'),'-',LPAD(no_faktur,4,'0')) as no_faktur_lengkap
						        	FROM nd_retur_jual
						        	WHERE created_at < '$tanggal_start'
						        	AND created_at >= '$tanggal_awal'
						        	AND status_aktif = 1
						        	) t1
						        LEFT JOIN (
						        	SELECT *
						        	FROM nd_retur_jual_detail
						        	WHERE gudang_id = $gudang_id
						        	AND barang_id = $barang_id
						        	AND warna_id = $warna_id
						        	) t2
						        ON t2.retur_jual_id = t1.id
						        LEFT JOIN (
						            SELECT qty, sum(jumlah_roll) as jumlah_roll, retur_jual_detail_id
						            FROM nd_retur_jual_qty
						            GROUP BY retur_jual_detail_id, qty
						            ) t3
						        ON t3.retur_jual_detail_id = t2.id
						        WHERE barang_id is not null 
						        AND warna_id is not null
							)UNION(
						    	SELECT barang_id, warna_id, t2.gudang_id, qty, 0, jumlah_roll, 'a3' as tipe, t2.id, tanggal, created_at
						        FROM (
						        	SELECT t1.*, concat('SJ/R/',if(t2.kode is not null, concat(t2.kode,'/'),'LL/'),DATE_FORMAT(tanggal,'%y'),'-',no_sj ) as no_faktur_lengkap
						        	FROM nd_retur_beli t1
						        	LEFT JOIN nd_supplier t2
						        	ON t1.supplier_id = t2.id
						        	WHERE created_at < '$tanggal_start'
						        	AND created_at >= '$tanggal_awal'
						        	AND t1.status_aktif = 1
						        	) t1
						        LEFT JOIN (
						        	SELECT *
						        	FROM nd_retur_beli_detail
						        	WHERE gudang_id = $gudang_id
						        	AND barang_id = $barang_id
						        	AND warna_id = $warna_id
						        	) t2
						        ON t2.retur_beli_id = t1.id
						        LEFT JOIN (
						            SELECT qty, sum(jumlah_roll) as jumlah_roll, retur_beli_detail_id
						            FROM nd_retur_beli_qty
						            GROUP BY retur_beli_detail_id, qty
						            ) t3
						        ON t3.retur_beli_detail_id = t2.id
						        WHERE barang_id is not null 
						        AND warna_id is not null
							)UNION(
						    	SELECT barang_id, warna_id, gudang_id, t2.qty, if(tipe_transaksi =1 ,t2.jumlah_roll,0 ), if(tipe_transaksi = 2  ,t2.jumlah_roll,0),'p1' as tipe, t2.id, tanggal, created_at
						    	FROM (
						    		SELECT *
						    		FROM nd_penyesuaian_stok
							    	WHERE barang_id = $barang_id
							    	AND warna_id = $warna_id
						        	AND gudang_id = $gudang_id
							    	AND created_at < '$tanggal_start'
							    	AND created_at >= '$tanggal_awal'
							    	AND tipe_transaksi != 0
						    	)t1
								LEFT JOIN 
									(
										SELECT qty, sum(jumlah_roll) as jumlah_roll, penyesuaian_stok_id, id
										FROM nd_penyesuaian_stok_qty 
										GROUP BY penyesuaian_stok_id, qty
									)t2
						    	ON t2.penyesuaian_stok_id = t1.id
						    )UNION(
						    	SELECT barang_id, warna_id, gudang_id, qty,0, jumlah_roll,'pSp1' as tipe, id, tanggal, created_at
					    		FROM nd_penyesuaian_stok
						    	WHERE barang_id = $barang_id
						    	AND warna_id = $warna_id
					        	AND gudang_id = $gudang_id
						    	AND created_at < '$tanggal_start'
						    	AND created_at >= '$tanggal_awal'
						    	AND tipe_transaksi = 3
						    )UNION(
						    	SELECT barang_id, warna_id, gudang_id, t2.qty, t2.jumlah_roll,0,'pSp2' as tipe, t2.id, tanggal, created_at
						    	FROM (
						    		SELECT *
						    		FROM nd_penyesuaian_stok
							    	WHERE barang_id = $barang_id
							    	AND warna_id = $warna_id
						        	AND gudang_id = $gudang_id
							    	AND created_at < '$tanggal_start'
							    	AND created_at >= '$tanggal_awal'
							    	AND tipe_transaksi = 3
						    	)t1
								LEFT JOIN 
									(
										SELECT qty, sum(jumlah_roll) as jumlah_roll, penyesuaian_stok_id, id
										FROM nd_penyesuaian_stok_split 
										GROUP BY penyesuaian_stok_id, qty
									)t2
						    	ON t2.penyesuaian_stok_id = t1.id
						    )UNION(
						    	SELECT barang_id, warna_id, gudang_id_after, t1.qty, t1.jumlah_roll, 0, 'm1', id, tanggal, created_at
						    	FROM (
						    		SELECT mutasi_barang_id, qty, sum(jumlah_roll) as jumlah_roll
						    		FROM nd_mutasi_barang_qty
						    		GROUP BY mutasi_barang_id, qty
						    		) t1
						    	LEFT JOIN (
						    		SELECT *
						    		FROM nd_mutasi_barang
							    	WHERE barang_id = $barang_id
							    	AND warna_id = $warna_id
							    	AND gudang_id_after = $gudang_id
							    	AND created_at < '$tanggal_start'
							    	AND created_at >= '$tanggal_awal'
							    	AND status_aktif = 1
						    		) t2
						    	ON t1.mutasi_barang_id = t2.id
						    	WHERE t2.id is not null
					    	)UNION(
						    	SELECT barang_id, warna_id, gudang_id_before, t1.qty, 0, t1.jumlah_roll, 'm2', id, tanggal, created_at
						    	FROM (
						    		SELECT mutasi_barang_id, qty, sum(jumlah_roll) as jumlah_roll
						    		FROM nd_mutasi_barang_qty
						    		GROUP BY mutasi_barang_id, qty
						    		) t1
						    	LEFT JOIN (
						    		SELECT *
						    		FROM nd_mutasi_barang
							    	WHERE barang_id = $barang_id
							    	AND warna_id = $warna_id
							    	AND gudang_id_before = $gudang_id
							    	AND created_at < '$tanggal_start'
							    	AND created_at >= '$tanggal_awal'
							    	AND status_aktif = 1
						    		) t2
						    	ON t1.mutasi_barang_id = t2.id
						    	WHERE t2.id is not null		    	
					    	)UNION(
						    	SELECT barang_id, warna_id, gudang_id, qty, jumlah_roll,0,'s1', id, tanggal, created_at
						    	FROM (
							    	SELECT barang_id, warna_id, gudang_id, qty, sum(jumlah_roll) as jumlah_roll, stok_opname_id
							    	FROM nd_stok_opname_detail
							    	WHERE barang_id = $barang_id
							    	AND warna_id = $warna_id
							    	AND gudang_id = $gudang_id
							    	GROUP BY stok_opname_id, qty
						    	)t1
						    	LEFT JOIN (
						    		SELECT id, tanggal, created_at
						    		FROM nd_stok_opname
						    		WHERE created_at < '$tanggal_start'
							    	AND created_at >= '$tanggal_awal'
							    	AND status_aktif = 1
						    	) t2
						    	ON t1.stok_opname_id = t2.id
						    	WHERE t2.id is not null
						    )
						)tA
						LEFT JOIN (
						    SELECT barang_id as barang_id_stok, warna_id as warna_id_stok, MAX(created_at) as tanggal_stok
						    FROM (
						        SELECT stok_opname_id, barang_id,warna_id
						        FROM nd_stok_opname_detail
						        WHERE barang_id = $barang_id
						        AND warna_id = $warna_id
						        AND gudang_id = $gudang_id
						        GROUP BY stok_opname_id
						    ) tA
						    LEFT JOIN (
					    		SELECT id, tanggal, created_at
					    		FROM nd_stok_opname
					    		WHERE created_at <= '$tanggal_start'
						    	AND created_at >= '$tanggal_awal'
						    	AND status_aktif = 1
					    	) tB
						    ON tA.stok_opname_id = tB.id
						    WHERE tB.id is not null
						    GROUP BY barang_id, warna_id
						) t2
						ON tA.barang_id = t2.barang_id_stok
						AND tA.warna_id = t2.warna_id_stok
						GROUP BY qty
					)
				) tbl_a
				LEFT JOIN nd_barang tbl_b
				ON tbl_a.barang_id = tbl_b.id
				LEFT JOIN nd_warna tbl_c
				ON tbl_a.warna_id = tbl_c.id
				Where barang_id is not null
				AND qty is not null
				GROUP BY qty
				ORDER BY qty asc
				");
		
		return $query->result();
		// return $this->db->last_query();
	}

	function cek_harga_jual($barang_id,$cond){
		$query = $this->db->query("SELECT tanggal, harga_jual
			FROM nd_penjualan_detail 
			LEFT JOIN nd_penjualan
			ON nd_penjualan_detail.penjualan_id = nd_penjualan.id
			where barang_id = $barang_id
			$cond
			GROUP BY tanggal, harga_jual
			limit 10
			", false);

		return $query->result();
	}

	function get_data_penjualan($id){
		
		$query = $this->db->query("SELECT tbl_a.id,penjualan_type_id, tanggal, customer_id, no_faktur as no_faktur_raw, 
				no_faktur_lengkap as no_faktur, no_surat_jalan, jatuh_tempo, diskon, 
				tbl_a.status_aktif, ongkos_kirim, tbl_a.keterangan, status , po_number, fp_status, 
				if(penjualan_type_id = 3,if(nama_keterangan = '','no_name', nama_keterangan), tbl_b.nama ) as nama_keterangan, 
				if(penjualan_type_id = 3,'',if(kota = '','-',kota)) as kota , tbl_d.text as tipe_penjualan, no_faktur_lengkap, 
				ifnull(tbl_e.amount,0) as bayar_dp, 
				if(penjualan_type_id = 3,ifnull(alamat_keterangan,'-') , 
				if (alamat_keterangan != '',alamat_keterangan, alamat)) as alamat_keterangan, 
				toko_id, closed_date, f.username as username, g.username as username_close, 
				'diambil_semua' as status_ambil, alamat_bon, kecamatan, kelurahan, no_packing_list, kode_pos, ppn, is_custom_view
			FROM (
				select t1.id, t1.toko_id, t1.penjualan_type_id, t1.no_faktur, t1.po_number, t1.tanggal, 
					t1.customer_id, t1.ppn, t1.gudang_id, t1.diskon, t1.jatuh_tempo, t1.ongkos_kirim, t1.keterangan, 
					t1.nama_keterangan, t1.alamat_keterangan, t1.status, t1.status_aktif, t1.closed_by,
					t1.closed_date ,t1.user_id ,t1.created_at, t1.revisi, t1.fp_status , is_custom_view,
					if((t1.tanggal < '2022-05-01 00:00:00'),
					concat('FPJ',convert(date_format(t1.tanggal,'%d%m%y') using latin1),'-',ifnull(t2.pre_faktur,''),convert(lpad(t1.no_faktur,5,'0') using latin1)),
					concat(t2.pre_po,':PJ01/',convert(date_format(t1.tanggal,'%y%m') using latin1),'/',convert(lpad(t1.no_faktur,4,'0') using latin1))) AS no_faktur_lengkap,
					group_concat(if((t1.tanggal < '2022-05-01 00:00:00'),

					concat('SJ',convert(date_format(t1.tanggal,'%d%m%y') using latin1),'-',ifnull(t2.pre_faktur,''),convert(lpad(t3.no_sj,4,'0') using latin1)),
					concat(t2.pre_po,':PJ02/',convert(date_format(t1.tanggal,'%y%m') using latin1),'/',convert(lpad(t3.no_sj,4,'0') using latin1))) separator ',') AS no_surat_jalan,
					group_concat(if((t1.tanggal < '2022-05-01 00:00:00'),
					
					concat('SJ',convert(date_format(t1.tanggal,'%d%m%y') using latin1),'-',ifnull(t2.pre_faktur,''),convert(lpad(t3.no_sj,4,'0') using latin1)),
					concat(t2.pre_po,':PJ03/',convert(date_format(t1.tanggal,'%y%m') using latin1),'/',convert(lpad(t3.no_sj,4,'0') using latin1))) separator ',') AS no_packing_list 
					from nd_penjualan t1
					left join nd_toko t2 
					on t1.toko_id = t2.id
					left join nd_surat_jalan t3 
					on t1.id = t3.penjualan_id
					WHERE t1.id = $id
				) as tbl_a
			LEFT JOIN (
				SELECT id, concat(ifnull(if(tipe_company ='','',concat(tipe_company,' ')),''),nama) as nama,kota, 
					concat(' ',alamat,' ', if(blok = '-' or blok='','',blok),' ', 
						if(no='-' or no='','',concat('no ',no) ), 
						if(rt='0' or rt='' ,'000',concat(', RT',LPAD(rt,3,'0')) ),
						if(rw='0' or rw='' ,'000',concat(' RW',LPAD(rw,3,'0'))), 
						if(kelurahan='-' or kelurahan='','', concat(', ',kelurahan) ), 
						if(kecamatan='-' or kecamatan='','',concat(', ',kecamatan) ),
						if(kota='-' or kota='','',concat(', ',kota) )
						 ) as alamat,  
					concat(' ',alamat,' ', if(blok = '-' or blok='','',blok),' ', if(no='-' or no='','',concat('no ',no) ), if(rt='0' or rt='' ,'000',concat(', RT',LPAD(rt,3,'0')) ),if(rw='0' or rw='' ,'000',concat(' RW',LPAD(rw,3,'0'))) ) as alamat_bon,
					kelurahan, kecamatan, kode_pos
				FROM nd_customer
				) as tbl_b
			ON tbl_a.customer_id = tbl_b.id
			LEFT JOIN nd_penjualan_type as tbl_d
			ON tbl_a.penjualan_type_id = tbl_d.id
			LEFT JOIN (
				SELECT *
				FROM nd_pembayaran_penjualan
				WHERE pembayaran_type_id = 1
				) tbl_e
			ON tbl_a.id = tbl_e.penjualan_id
			LEFT JOIN (
				SELECT id, username
				FROM nd_user) f
			ON tbl_a.user_id = f.id
			LEFT JOIN (SELECT id, username
				FROM nd_user) g
			ON tbl_a.closed_by = g.id
			", false);
		return $query->result();
	}

	function get_data_pembayaran($penjualan_id){
		$query = $this->db->query("SELECT a.*, b.nama as nama_bayar
			FROM (
				SELECT *
				FROM nd_pembayaran_penjualan
				WHERE penjualan_id = $penjualan_id
				) a
			LEFT JOIN nd_pembayaran_type b
			ON a.pembayaran_type_id = b.id
			", false);

		return $query->result();
	}

	function get_data_penjualan_detail($id, $cond_d){
		$this->db->simple_query('SET SESSION group_concat_max_len=15000');

		$query = $this->db->query("SELECT tbl_a.*,jenis_barang, nama_barang, nama_satuan, tbl_c.nama as nama_gudang, 
		tbl_d.warna_jual as nama_warna, tbl_e.qty as qty, tbl_e.jumlah_roll as jumlah_roll, data_qty, tipe_qty
			FROM (
				SELECT *
				FROM nd_penjualan_detail
				WHERE penjualan_id = $id
				) as tbl_a
			LEFT JOIN (
				SELECT nd_barang.id, nd_barang.nama_jual as nama_barang, nd_satuan.nama as nama_satuan, jenis_barang, tipe_qty
				FROM nd_barang
				LEFT JOIN nd_satuan
				ON nd_barang.satuan_id = nd_satuan.id
				) as tbl_b
			ON tbl_a.barang_id = tbl_b.id
			LEFT JOIN nd_gudang as tbl_c
			ON tbl_a.gudang_id = tbl_c.id
			LEFT JOIN nd_warna as tbl_d
			ON tbl_a.warna_id = tbl_d.id
			LEFT JOIN (
				SELECT sum(qty * if(jumlah_roll = 0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, penjualan_detail_id,  group_concat(concat_ws('??',qty,jumlah_roll) ORDER BY id asc SEPARATOR '--') as data_qty
				FROM nd_penjualan_qty_detail
				WHERE penjualan_detail_id IN (
					$cond_d
				) 
				group by penjualan_detail_id
				) as tbl_e
			ON tbl_e.penjualan_detail_id = tbl_a.id
			", false);

		return $query->result();
	}

	function get_data_penjualan_detail_group($id){
		$this->db->simple_query('SET SESSION group_concat_max_len=15000');
		
		$query = $this->db->query("SELECT tbl_a.*, jenis_barang, ifnull(nama_jual_tercetak, nama_barang) as nama_barang, nama_satuan, tbl_c.nama as nama_gudang, 
		group_concat(tbl_d.warna_jual SEPARATOR '--') as nama_warna, sum(tbl_e.qty) as qty, sum(tbl_e.jumlah_roll) as jumlah_roll, 
		group_concat(data_qty SEPARATOR '--') as data_qty, tipe_qty
			FROM (
				SELECT *
				FROM nd_penjualan_detail
				WHERE penjualan_id = $id
				) as tbl_a
			LEFT JOIN (
				SELECT nd_barang.id, concat(nd_barang.nama_jual) as nama_barang, nd_satuan.nama as nama_satuan, jenis_barang, tipe_qty
				FROM nd_barang
				LEFT JOIN nd_satuan
				ON nd_barang.satuan_id = nd_satuan.id
				) as tbl_b
			ON tbl_a.barang_id = tbl_b.id
			LEFT JOIN nd_gudang as tbl_c
			ON tbl_a.gudang_id = tbl_c.id
			LEFT JOIN nd_warna as tbl_d
			ON tbl_a.warna_id = tbl_d.id
			LEFT JOIN (
				SELECT sum(qty * if(jumlah_roll=0,1,jumlah_roll )) as qty, sum(jumlah_roll) as jumlah_roll, penjualan_detail_id,  
				group_concat(concat_ws('??',qty,jumlah_roll) SEPARATOR '--') as data_qty
				FROM nd_penjualan_qty_detail
				group by penjualan_detail_id
				) as tbl_e
			ON tbl_e.penjualan_detail_id = tbl_a.id
			GROUP BY barang_id, harga_jual
			", false);

		return $query->result();
	}

	function get_data_penjualan_detail_by_barang($id){
		$query = $this->db->query("SELECT  nama_barang, nama_satuan, barang_id,
		group_concat(tbl_d.warna_jual SEPARATOR '??') as nama_warna, 
		group_concat(tbl_e.qty SEPARATOR '??') as qty, 
		group_concat(warna_id SEPARATOR '??') as warna_id, 
		group_concat(tbl_e.jumlah_roll SEPARATOR '??') as jumlah_roll, 
		group_concat(data_qty SEPARATOR '??') as data_qty,
		group_concat(roll_qty SEPARATOR '??') as roll_qty,
		group_concat(data_all SEPARATOR '=??=') as data_all, tbl_b.satuan_id
			FROM (
				SELECT *
				FROM nd_penjualan_detail
				WHERE penjualan_id = $id
				) as tbl_a
			LEFT JOIN (
				SELECT nd_barang.id, nd_barang.nama_jual as nama_barang, nd_satuan.nama as nama_satuan,satuan_id, tipe_qty
				FROM nd_barang
				LEFT JOIN nd_satuan
				ON nd_barang.satuan_id = nd_satuan.id
				) as tbl_b
			ON tbl_a.barang_id = tbl_b.id
			LEFT JOIN nd_warna as tbl_d
			ON tbl_a.warna_id = tbl_d.id
			LEFT JOIN (
				SELECT sum(qty * if(jumlah_roll = 0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, group_concat(jumlah_roll) as roll_qty, 
				penjualan_detail_id,  group_concat(qty SEPARATOR ' ') as data_qty, group_concat(concat_ws('??',qty,jumlah_roll) SEPARATOR '--') as data_all
				FROM nd_penjualan_qty_detail
				group by penjualan_detail_id
				) as tbl_e
			ON tbl_e.penjualan_detail_id = tbl_a.id
			WHERE tipe_qty != 3
			GROUP BY barang_id
			", false);

		return $query->result();
	}

	function get_data_penjualan_detail_print($id){
		$query = $this->db->query("SELECT UPPER(ifnull(nama_jual_tercetak,nama_barang)) AS nama_barang,
				UPPER(nama_satuan) as nama_satuan, barang_id,
				group_concat(warna_id SEPARATOR '??') as warna_id, 
				group_concat(UPPER(tbl_d.warna_jual) SEPARATOR '??') as nama_warna, 
				group_concat(qty SEPARATOR '??') as qty, 
				group_concat(warna_id SEPARATOR '??') as warna_id, 
				group_concat(jumlah_roll SEPARATOR '??') as jumlah_roll,
				group_concat(total_qty SEPARATOR '??') as total_qty,
				group_concat(total_roll SEPARATOR '??') as total_roll
			FROM (
				SELECT barang_id, warna_id, 
					group_concat(qty) as qty, 
					group_concat(jumlah_roll) as jumlah_roll,
					sum(total_qty) as total_qty,
					sum(total_roll) as total_roll, nama_jual_tercetak
				FROM (
					SELECT *
					FROM nd_penjualan_detail
					WHERE penjualan_id = $id
					) as tA
				LEFT JOIN (
					SELECT group_concat(qty) as qty, group_concat(jumlah_roll) as jumlah_roll,penjualan_detail_id, 
					sum(qty * if(jumlah_roll = 0,1,jumlah_roll)) as total_qty,
					sum(jumlah_roll) as total_roll
					FROM nd_penjualan_qty_detail
					group by penjualan_detail_id
					) as tB
				ON tB.penjualan_detail_id = tA.id
				GROUP BY barang_id, warna_id
			) tbl_a
			LEFT JOIN (
				SELECT nd_barang.id, nd_barang.nama_jual as nama_barang, nd_satuan.nama as nama_satuan,satuan_id, tipe_qty
				FROM nd_barang
				LEFT JOIN nd_satuan
				ON nd_barang.satuan_id = nd_satuan.id
			) as tbl_b
			ON tbl_a.barang_id = tbl_b.id
			LEFT JOIN nd_warna as tbl_d
			ON tbl_a.warna_id = tbl_d.id
			WHERE tipe_qty != 3
			GROUP BY barang_id

			", false);

		return $query->result();
	}

	function get_lastest_harga($barang_id, $cond){
		// $latest_tanggal = date('Y-m-d', strtotime("-3 months"));
		$latest_tanggal = '2018-01-01';
		$query = $this->db->query("SELECT harga_jual
			FROM (
				(
					SELECT tanggal, harga_jual
					FROM (
						SELECT *
						FROM nd_penjualan_detail
						WHERE barang_id = $barang_id
						) nd_penjualan_detail 
					LEFT JOIN (
						SELECT *
						FROM nd_penjualan
						WHERE tanggal >= '$latest_tanggal'
						AND penjualan_type_id = 2
						$cond
						)nd_penjualan
					ON nd_penjualan_detail.penjualan_id = nd_penjualan.id
					WHERE nd_penjualan.id is not null
				)UNION(
					SELECT tanggal, harga_jual
					FROM nd_history_harga_customer
					WHERE barang_id = $barang_id
					$cond
				)
			)result
			ORDER BY tanggal desc
			limit 1
			", false);

		return $query->result();
	}

	function get_lastest_harga_non_customer($barang_id){
		$query = $this->db->query("SELECT nd_penjualan.id, harga_jual
			FROM (
				SELECT *
				FROM nd_penjualan_detail
				WHERE barang_id = $barang_id
				) nd_penjualan_detail 
			LEFT JOIN (
				SELECT *
				FROM nd_penjualan
				) nd_penjualan
			ON nd_penjualan_detail.penjualan_id = nd_penjualan.id
			where penjualan_type_id = 3
			ORDER BY tanggal desc
			limit 1
			", false);

		return $query->result();
	}

	function search_faktur_jual($no_faktur){
		$tgl = date('Y-m-d', strtotime('-6 months'));
		$query = $this->db->query("SELECT id, no_faktur_lengkap as no_faktur
			FROM (
				SELECT id, no_faktur_lengkap 
				FROM vw_penjualan_data
				WHERE tanggal >= '$tgl'
				)as tbl_a
			WHERE no_faktur_lengkap LIKE '%$no_faktur%'
			", false);

		return $query->result();
	}
	
	//harusnya sama kaya inventory kartu stok awal tapi tanggal nya bukan < tapi <=
	function get_qty_stok_by_barang($gudang_id, $barang_id,$warna_id, $tanggal_awal, $stok_opname_id, $cond_detail){
		$tanggal_start = date('Y-m-d');
		$query = $this->db->query("SELECT ifnull(ROUND(
			sum(if(tanggal_stok is not null,if(tanggal >= tanggal_stok, qty_masuk,0) ,qty_masuk) )  - sum(if(tanggal_stok is not null,if(tanggal >= tanggal_stok,qty_keluar,0), qty_keluar) )
		,2),0) as qty, 
			ifnull(
				sum(if(tanggal_stok is not null,if(tanggal >= tanggal_stok,jumlah_roll_masuk,0), jumlah_roll_masuk)) - sum(if(tanggal_stok is not null,if(tanggal >= tanggal_stok,jumlah_roll_keluar,0), jumlah_roll_keluar))
			,0) as jumlah_roll
		FROM(
		    (
		        SELECT barang_id, warna_id, t2.gudang_id, qty as qty_masuk, jumlah_roll as jumlah_roll_masuk, 0 as qty_keluar, 0 as jumlah_roll_keluar, tanggal, 1 as tipe, t1.id
		        FROM (
		            SELECT *
		            FROM nd_pembelian_detail
		            WHERE barang_id = $barang_id
		            AND warna_id = $warna_id
		            ) t1
		        LEFT JOIN (
		            SELECT *
		            FROM nd_pembelian
		            WHERE tanggal <= '$tanggal_start'
		            AND tanggal >= '$tanggal_awal'
		            AND gudang_id = $gudang_id
		            AND status_aktif = 1
		            ) t2
		        ON t1.pembelian_id = t2.id
		        WHERE t2.id is not null
		    )UNION(
		        SELECT barang_id, warna_id, gudang_id_after, qty , jumlah_roll , 0, 0, tanggal, 2, id
		        FROM nd_mutasi_barang
		            WHERE tanggal <= '$tanggal_start'
		            AND tanggal >= '$tanggal_awal'
		            AND gudang_id_after = $gudang_id
		            AND barang_id = $barang_id
		            AND warna_id = $warna_id
		            AND status_aktif = 1
		    )UNION(
		        SELECT barang_id, warna_id, t1.gudang_id, 0, 0, subqty, subjumlah_roll, tanggal, 3, t1.id
		        FROM (
		            SELECT *
		            FROM nd_penjualan_detail 
		            WHERE gudang_id = $gudang_id 
		            AND barang_id = $barang_id
		            AND warna_id = $warna_id
		            $cond_detail
		            ) t1
		        LEFT JOIN (
		            SELECT *
		            FROM nd_penjualan
		            WHERE tanggal <= '$tanggal_start'
		            AND tanggal >= '$tanggal_awal'
		            AND status_aktif = 1
		            ) t2
		        ON t1.penjualan_id = t2.id
		        WHERE t2.id is not null
		    )UNION(
		        SELECT barang_id, warna_id, t1.gudang_id, 0, 0, qty, jumlah_roll, tanggal, 3, t3.id
		        FROM (
		            SELECT *
		            FROM nd_pengeluaran_stok_lain_detail 
		            WHERE gudang_id = $gudang_id 
		            AND barang_id = $barang_id
		            AND warna_id = $warna_id
		            ) t1
		        LEFT JOIN (
		            SELECT *
		            FROM nd_pengeluaran_stok_lain
		            WHERE tanggal <= '$tanggal_start'
		            AND tanggal >= '$tanggal_awal'
		            AND status_aktif = 1
		            ) t2
		        ON t1.pengeluaran_stok_lain_id = t2.id
		        LEFT JOIN (
		            SELECT sum(qty * if(jumlah_roll=0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, pengeluaran_stok_lain_detail_id, id
		            FROM nd_pengeluaran_stok_lain_qty_detail
		            GROUP BY pengeluaran_stok_lain_detail_id
		            ) t3
		        ON t3.pengeluaran_stok_lain_detail_id = t1.id
		        WHERE t2.id is not null
		    )UNION(
		        SELECT barang_id, warna_id, t1.gudang_id, qty, jumlah_roll, 0, 0, tanggal, 4, t3.id
		        FROM (
		            SELECT *
		            FROM nd_retur_jual_detail
		            WHERE gudang_id = $gudang_id
		            AND barang_id = $barang_id
		            AND warna_id = $warna_id
		            ) t1
		        LEFT JOIN (
		            SELECT *, concat('FRJ', DATE_FORMAT(tanggal,'%d%m%y'),'-',LPAD(no_faktur,4,'0')) as no_faktur_lengkap
		            FROM nd_retur_jual
		            WHERE tanggal <= '$tanggal_start'
		            AND tanggal >= '$tanggal_awal'
		            AND status_aktif = 1
		            ) t2
		        ON t1.retur_jual_id = t2.id
		        LEFT JOIN (
		            SELECT sum(qty * if(jumlah_roll=0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, retur_jual_detail_id, id
		            FROM nd_retur_jual_qty
		            GROUP BY retur_jual_detail_id
		            ) t3
		        ON t3.retur_jual_detail_id = t1.id
		        WHERE barang_id is not null 
		        AND warna_id is not null
		        AND t2.id is not null
		    )UNION(
		        SELECT barang_id, warna_id, t1.gudang_id, 0, 0, qty, jumlah_roll, tanggal, 19, t3.id
		        FROM (
		            SELECT *
		            FROM nd_retur_beli_detail
		            WHERE gudang_id = $gudang_id
		            AND barang_id = $barang_id
		            AND warna_id = $warna_id
		            ) t1
		        LEFT JOIN (
		           SELECT t1.*, concat('SJ/R/',if(t2.kode is not null, concat(t2.kode,'/'),'LL/'),DATE_FORMAT(tanggal,'%y'),'-',no_sj ) as no_faktur_lengkap
		        	FROM nd_retur_beli t1
		        	LEFT JOIN nd_supplier t2
		        	ON t1.supplier_id = t2.id
		            WHERE tanggal <= '$tanggal_start'
		            AND tanggal >= '$tanggal_awal'
		            AND t1.status_aktif = 1
		            ) t2
		        ON t1.retur_beli_id = t2.id
		        LEFT JOIN (
		            SELECT sum(qty * if(jumlah_roll=0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, retur_beli_detail_id, id
		            FROM nd_retur_beli_qty
		            GROUP BY retur_beli_detail_id
		            ) t3
		        ON t3.retur_beli_detail_id = t1.id
		        WHERE barang_id is not null 
		        AND warna_id is not null
		        AND t2.id is not null
		    )UNION(
		        SELECT barang_id, warna_id, gudang_id_before, 0 , 0, qty, jumlah_roll, tanggal, 5, id
		        FROM nd_mutasi_barang
		            WHERE tanggal <= '$tanggal_start'
		            AND tanggal >= '$tanggal_awal'
		            AND gudang_id_before = $gudang_id
		            AND barang_id = $barang_id
		            AND warna_id = $warna_id
		            AND status_aktif = 1
		    )UNION(
		        SELECT barang_id, warna_id, gudang_id, qty, jumlah_roll, 0, 0,tanggal, 6, id
		        FROM (
		            SELECT *
		            FROM nd_penyesuaian_stok
		            WHERE tanggal <= '$tanggal_start'
		            AND tanggal >= '$tanggal_awal'
		            AND tipe_transaksi = 0
		            AND barang_id = $barang_id
		            AND warna_id = $warna_id
		            AND gudang_id = $gudang_id
		        ) nd_penyesuaian_stok
		    )UNION(
		        SELECT barang_id, warna_id, gudang_id, if(tipe_transaksi = 1,qty,0) , if(tipe_transaksi = 1,jumlah_roll,0), if(tipe_transaksi = 2,qty,0), if(tipe_transaksi = 2,jumlah_roll,0),tanggal, 7, id
		        FROM (
		            SELECT *
		            FROM nd_penyesuaian_stok
		            WHERE tanggal <= '$tanggal_start'
		            AND tanggal >= '$tanggal_awal'
		            AND tipe_transaksi != 0
		            AND barang_id = $barang_id
		            AND warna_id = $warna_id
		            AND gudang_id = $gudang_id
		        ) nd_penyesuaian_stok
		    )UNION(
		        SELECT barang_id, warna_id, gudang_id, qty, jumlah_roll, 0, 0, tanggal, 8, t1.id
		        FROM (
		            SELECT stok_opname_id, barang_id, gudang_id,warna_id, sum(qty * jumlah_roll) as qty, sum(jumlah_roll) as jumlah_roll, id
		            FROM nd_stok_opname_detail
		            WHERE barang_id = $barang_id
		            AND warna_id = $warna_id
		            AND gudang_id = $gudang_id
		            GROUP BY stok_opname_id
		        ) t1
		        LEFT JOIN (
		            SELECT *
		            FROM nd_stok_opname
		            WHERE status_aktif = 1
		        ) t2
		        ON t1.stok_opname_id = t2.id
		        WHERE t2.id is not null
		    )
		) tbl_a
		LEFT JOIN (
		    SELECT barang_id as barang_id_stok, warna_id as warna_id_stok, tanggal as tanggal_stok, gudang_id as gudang_id_stok
		    FROM (
		        SELECT stok_opname_id, barang_id,warna_id, gudang_id
		        FROM nd_stok_opname_detail
		        WHERE barang_id = $barang_id
		        AND warna_id = $warna_id
		        AND gudang_id = $gudang_id
		        GROUP BY stok_opname_id
		    ) tA
		    LEFT JOIN (
		        SELECT *
		        FROM nd_stok_opname
		        WHERE status_aktif = 1
		        AND tanggal <= '$tanggal_start'
		        AND tanggal >= '$tanggal_awal'
		    ) tB
		    ON tA.stok_opname_id = tB.id
		    WHERE tB.id is not null
		    GROUP BY barang_id, warna_id
		) t2
		ON tbl_a.barang_id = t2.barang_id_stok
		AND tbl_a.warna_id = t2.warna_id_stok
		LEFT JOIN nd_barang tbl_b
		ON tbl_a.barang_id = tbl_b.id
		LEFT JOIN nd_warna tbl_c
		ON tbl_a.warna_id = tbl_c.id
		Where barang_id is not null
				");
		
		return $query;
		// return $this->db->last_query();
	}

	function get_qty_stok_by_barang_2($gudang_id, $barang_id,$warna_id, $tanggal_awal, $stok_opname_id, $cond_detail){
		$tanggal_start = date('Y-m-d').' 23:59:59';
		$query = $this->db->query("SELECT ifnull(ROUND(
			sum(if(tanggal_stok is not null,if(time_stamp >= tanggal_stok, qty_masuk,0) ,qty_masuk) )  - sum(if(tanggal_stok is not null,if(time_stamp >= tanggal_stok,qty_keluar,0), qty_keluar) )
		,2),0) as qty, 
			ifnull(
				sum(if(tanggal_stok is not null,if(time_stamp >= tanggal_stok,jumlah_roll_masuk,0), jumlah_roll_masuk)) - sum(if(tanggal_stok is not null,if(time_stamp >= tanggal_stok,jumlah_roll_keluar,0), jumlah_roll_keluar))
			,0) as jumlah_roll
		FROM(
		    (
		        SELECT barang_id, warna_id, t2.gudang_id, qty as qty_masuk, jumlah_roll as jumlah_roll_masuk, 0 as qty_keluar, 0 as jumlah_roll_keluar, tanggal, 1 as tipe, t1.id, created_at as time_stamp
		        FROM (
		            SELECT *
		            FROM nd_pembelian_detail
		            WHERE barang_id = $barang_id
		            AND warna_id = $warna_id
		            ) t1
		        LEFT JOIN (
		            SELECT *
		            FROM nd_pembelian
		            WHERE created_at <= '$tanggal_start'
		            AND created_at >= '$tanggal_awal'
		            AND gudang_id = $gudang_id
		            AND status_aktif = 1
		            ) t2
		        ON t1.pembelian_id = t2.id
		        WHERE t2.id is not null
		    )UNION(
		        SELECT barang_id, warna_id, gudang_id_after, qty , jumlah_roll , 0, 0, tanggal, 2, id, created_at
		        FROM nd_mutasi_barang
		            WHERE created_at <= '$tanggal_start'
		            AND created_at >= '$tanggal_awal'
		            AND gudang_id_after = $gudang_id
		            AND barang_id = $barang_id
		            AND warna_id = $warna_id
		            AND status_aktif = 1
		    )UNION(
		        SELECT barang_id, warna_id, t1.gudang_id, 0, 0, subqty, subjumlah_roll, tanggal, 3, t1.id, ifnull(closed_date, t2.created_at)
		        FROM (
		            SELECT *
		            FROM nd_penjualan_detail 
		            WHERE gudang_id = $gudang_id 
		            AND barang_id = $barang_id
		            AND warna_id = $warna_id
		            $cond_detail
		            ) t1
		        LEFT JOIN (
		            SELECT *
		            FROM nd_penjualan
		            WHERE created_at <= '$tanggal_start'
		            AND created_at >= '$tanggal_awal'
		            AND status_aktif = 1
		            ) t2
		        ON t1.penjualan_id = t2.id
		        WHERE t2.id is not null
		    )UNION(
		        SELECT barang_id, warna_id, t1.gudang_id, 0, 0, qty, jumlah_roll, tanggal, 3, t3.id, t2.created_at
		        FROM (
		            SELECT *
		            FROM nd_pengeluaran_stok_lain_detail 
		            WHERE gudang_id = $gudang_id 
		            AND barang_id = $barang_id
		            AND warna_id = $warna_id
		            ) t1
		        LEFT JOIN (
		            SELECT *
		            FROM nd_pengeluaran_stok_lain
		            WHERE created_at <= '$tanggal_start'
		            AND created_at >= '$tanggal_awal'
		            AND status_aktif = 1
		            ) t2
		        ON t1.pengeluaran_stok_lain_id = t2.id
		        LEFT JOIN (
		            SELECT sum(qty * if(jumlah_roll=0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, pengeluaran_stok_lain_detail_id, id
		            FROM nd_pengeluaran_stok_lain_qty_detail
		            GROUP BY pengeluaran_stok_lain_detail_id
		            ) t3
		        ON t3.pengeluaran_stok_lain_detail_id = t1.id
		        WHERE t2.id is not null
		    )UNION(
		        SELECT barang_id, warna_id, t1.gudang_id, qty, jumlah_roll, 0, 0, tanggal, 4, t3.id, created_at
		        FROM (
		            SELECT *
		            FROM nd_retur_jual_detail
		            WHERE gudang_id = $gudang_id
		            AND barang_id = $barang_id
		            AND warna_id = $warna_id
		            ) t1
		        LEFT JOIN (
		            SELECT *, concat('FRJ', DATE_FORMAT(tanggal,'%d%m%y'),'-',LPAD(no_faktur,4,'0')) as no_faktur_lengkap
		            FROM nd_retur_jual
		            WHERE created_at <= '$tanggal_start'
		            AND created_at >= '$tanggal_awal'
		            AND status_aktif = 1
		            ) t2
		        ON t1.retur_jual_id = t2.id
		        LEFT JOIN (
		            SELECT sum(qty * if(jumlah_roll=0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, retur_jual_detail_id, id
		            FROM nd_retur_jual_qty
		            GROUP BY retur_jual_detail_id
		            ) t3
		        ON t3.retur_jual_detail_id = t1.id
		        WHERE barang_id is not null 
		        AND warna_id is not null
		        AND t2.id is not null
		    )UNION(
		        SELECT barang_id, warna_id, t1.gudang_id, 0, 0, qty, jumlah_roll, tanggal, 19, t3.id, created_at
		        FROM (
		            SELECT *
		            FROM nd_retur_beli_detail
		            WHERE gudang_id = $gudang_id
		            AND barang_id = $barang_id
		            AND warna_id = $warna_id
		            ) t1
		        LEFT JOIN (
		           SELECT t1.*, concat('SJ/R/',if(t2.kode is not null, concat(t2.kode,'/'),'LL/'),DATE_FORMAT(tanggal,'%y'),'-',no_sj ) as no_faktur_lengkap
		        	FROM nd_retur_beli t1
		        	LEFT JOIN nd_supplier t2
		        	ON t1.supplier_id = t2.id
		            WHERE created_at <= '$tanggal_start'
		            AND created_at >= '$tanggal_awal'
		            AND t1.status_aktif = 1
		            ) t2
		        ON t1.retur_beli_id = t2.id
		        LEFT JOIN (
		            SELECT sum(qty * if(jumlah_roll=0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, retur_beli_detail_id, id
		            FROM nd_retur_beli_qty
		            GROUP BY retur_beli_detail_id
		            ) t3
		        ON t3.retur_beli_detail_id = t1.id
		        WHERE barang_id is not null 
		        AND warna_id is not null
		        AND t2.id is not null
		    )UNION(
		        SELECT barang_id, warna_id, gudang_id_before, 0 , 0, qty, jumlah_roll, tanggal, 5, id, created_at
		        FROM nd_mutasi_barang
		            WHERE created_at <= '$tanggal_start'
		            AND created_at >= '$tanggal_awal'
		            AND gudang_id_before = $gudang_id
		            AND barang_id = $barang_id
		            AND warna_id = $warna_id
		            AND status_aktif = 1
		    )UNION(
		        SELECT barang_id, warna_id, gudang_id, qty, jumlah_roll, 0, 0,tanggal, 6, id, created_at
		        FROM (
		            SELECT *
		            FROM nd_penyesuaian_stok
		            WHERE created_at <= '$tanggal_start'
		            AND created_at >= '$tanggal_awal'
		            AND tipe_transaksi = 0
		            AND barang_id = $barang_id
		            AND warna_id = $warna_id
		            AND gudang_id = $gudang_id
		        ) nd_penyesuaian_stok
		    )UNION(
		        SELECT barang_id, warna_id, gudang_id, 
				if(tipe_transaksi = 1,qty, if(tipe_transaksi=3,qty,0) ) , 
				if(tipe_transaksi = 1,jumlah_roll, if(tipe_transaksi=3,jml_roll,0) ),
				if(tipe_transaksi = 2, qty, if(tipe_transaksi=3,qty,0) ), 
				if(tipe_transaksi = 2, jumlah_roll, if(tipe_transaksi=3,jumlah_roll,0)),
				tanggal, 7, id, created_at
		        FROM (
		            SELECT *
		            FROM nd_penyesuaian_stok
		            WHERE created_at <= '$tanggal_start'
		            AND created_at >= '$tanggal_awal'
		            AND tipe_transaksi != 0
		            AND barang_id = $barang_id
		            AND warna_id = $warna_id
		            AND gudang_id = $gudang_id
		        ) tA
				LEFT JOIN (
					SELECT sum(jumlah_roll) as jml_roll, penyesuaian_stok_id
					FROM nd_penyesuaian_stok_split
					GROUP BY penyesuaian_stok_id
					) tB
				ON tB.penyesuaian_stok_id = tA.id
		    )UNION(
		        SELECT barang_id, warna_id, gudang_id, qty, jumlah_roll, 0, 0, tanggal, 8, t1.id, created_at
		        FROM (
		            SELECT stok_opname_id, barang_id, gudang_id,warna_id, sum(qty * jumlah_roll) as qty, sum(jumlah_roll) as jumlah_roll, id
		            FROM nd_stok_opname_detail
		            WHERE barang_id = $barang_id
		            AND warna_id = $warna_id
		            AND gudang_id = $gudang_id
		            GROUP BY stok_opname_id
		        ) t1
		        LEFT JOIN (
		            SELECT *
		            FROM nd_stok_opname
		            WHERE status_aktif = 1
		            AND created_at <= '$tanggal_start'
		            AND created_at >= '$tanggal_awal'

		        ) t2
		        ON t1.stok_opname_id = t2.id
		        WHERE t2.id is not null
		    )
		) tbl_a
		LEFT JOIN (
		    SELECT barang_id as barang_id_stok, warna_id as warna_id_stok, MAX(created_at) as tanggal_stok, gudang_id as gudang_id_stok
		    FROM (
		        SELECT stok_opname_id, barang_id,warna_id, gudang_id
		        FROM nd_stok_opname_detail
		        WHERE barang_id = $barang_id
		        AND warna_id = $warna_id
		        AND gudang_id = $gudang_id
		        GROUP BY stok_opname_id
		    ) tA
		    LEFT JOIN (
		        SELECT *
		        FROM nd_stok_opname
		        WHERE status_aktif = 1
		        AND created_at <= '$tanggal_start'
		    ) tB
		    ON tA.stok_opname_id = tB.id
		    WHERE tB.id is not null
		    GROUP BY barang_id, warna_id
		) t2
		ON tbl_a.barang_id = t2.barang_id_stok
		AND tbl_a.warna_id = t2.warna_id_stok
		LEFT JOIN nd_barang tbl_b
		ON tbl_a.barang_id = tbl_b.id
		LEFT JOIN nd_warna tbl_c
		ON tbl_a.warna_id = tbl_c.id
		Where barang_id is not null
		");
		return $query;
	}

/**
==========================================pengambilan barang================================
**/
	function get_penjualan_pengambilan_data($penjualan_id){
		$query = $this->db->query("SELECT *
			FROM (
				(
					SELECT id, keterangan, DATE_FORMAT(created_at,'%d/%m/%y %H:%i:%s') as waktu_diambil, 1 as tipe, '' as no_sj
					FROM nd_penjualan_pengambilan
					WHERE penjualan_id = $penjualan_id
				)UNION(
					SELECT id, keterangan, concat(DATE_FORMAT(tanggal,'%d/%m/%y'),' ',DATE_FORMAT(created_at,'%H:%i:%s')), 2 as tipe,  concat('SJ', DATE_FORMAT(tanggal,'%d%m%y'),'-',LPAD(no,5,'0')) as no_surat_jalan
					FROM nd_surat_jalan
					WHERE transaction_id = $penjualan_id
				)
			)result
			ORDER BY waktu_diambil desc
				");

		return $query->result();
	}

	function get_penjualan_pengambilan_by_qty($penjualan_id){
		$query = $this->db->query("SELECT *
			FROM (
				(
					SELECT penjualan_detail_id, qty, sum(jumlah_roll) as jumlah_roll_ambil,
				    concat('[',group_concat(JSON_OBJECT('penjualan_pengambilan_detail_id',t1.id, 'penjualan_pengambilan_id',penjualan_pengambilan_id,'jumlah_roll',jumlah_roll,'tipe',1)),']') as pengambilan_list
				    FROM nd_penjualan_pengambilan_detail t1
				    LEFT JOIN (
				        SELECT qty, sum(jumlah_roll) as jumlah_roll, penjualan_pengambilan_detail_id
				        FROM nd_penjualan_pengambilan_qty
				        GROUP BY penjualan_pengambilan_detail_id, qty
				        ORDER BY qty,id
				        )t2
				    ON t2.penjualan_pengambilan_detail_id = t1.id
				    LEFT JOIN (
				        SELECT *
				        FROM nd_penjualan_pengambilan
				        WHERE penjualan_id = $penjualan_id
				        ) t3
				    ON t1.penjualan_pengambilan_id = t3.id
				    LEFT JOIN nd_user t4
				    ON t3.user_id = t4.id
				    WHERE t3.id is not null
				    GROUP BY penjualan_detail_id, qty
				    ORDER BY t1.id, qty
				)UNION(
					SELECT transaction_detail_id, qty, sum(jumlah_roll) as jumlah_roll_ambil,
				    concat('[',group_concat(JSON_OBJECT('penjualan_pengambilan_detail_id',t1.id, 'penjualan_pengambilan_id',surat_jalan_id,'jumlah_roll',jumlah_roll,'tipe',2)),']') as pengambilan_list
				    FROM nd_surat_jalan_detail t1
				    LEFT JOIN (
				        SELECT qty, sum(jumlah_roll) as jumlah_roll, surat_jalan_detail_id
				        FROM nd_surat_jalan_qty
				        GROUP BY surat_jalan_detail_id, qty
				        ORDER BY qty,id
				        )t2
				    ON t2.surat_jalan_detail_id = t1.id
				    LEFT JOIN (
				        SELECT *
				        FROM nd_surat_jalan
				        WHERE transaction_id = $penjualan_id
				        ) t3
				    ON t1.surat_jalan_id = t3.id
				    LEFT JOIN nd_user t4
				    ON t3.user_id = t4.id
				    WHERE t3.id is not null
				    GROUP BY transaction_detail_id, qty
				    ORDER BY t1.id, qty
				)
			)result
				");

		return $query->result();
	}

	function get_penjualan_pengiriman_by_qty($penjualan_id){
		$query = $this->db->query("SELECT transaction_detail_id, qty, sum(jumlah_roll) as jumlah_roll_ambil,
		    concat('[',group_concat(JSON_OBJECT('penjualan_pengambilan_detail_id',t1.id, 'penjualan_pengambilan_id',surat_jalan_id,'jumlah_roll',jumlah_roll)),']') as pengiriman_list
		    FROM nd_surat_jalan_detail t1
		    LEFT JOIN (
		        SELECT qty, sum(jumlah_roll) as jumlah_roll, surat_jalan_detail_id
		        FROM nd_surat_jalan_qty
		        GROUP BY surat_jalan_detail_id, qty
		        ORDER BY qty,id
		        )t2
		    ON t2.surat_jalan_detail_id = t1.id
		    LEFT JOIN (
		        SELECT *
		        FROM nd_surat_jalan
		        WHERE transaction_id = $penjualan_id
		        AND surat_jalan_type_id=1
		        ) t3
		    ON t1.surat_jalan_id = t3.id
		    LEFT JOIN nd_user t4
		    ON t3.user_id = t4.id
		    WHERE t3.id is not null
		    GROUP BY transaction_id, qty
		    ORDER BY t1.id, qty
				");

		return $query->result();
	}


	function alamat_kirim_non_cust($id){
		$query = $this->db->query("SELECT id, alamat_keterangan as alamat
			FROM nd_penjualan
			WHERE id = $id
				");

		return $query->result();
	}

	function get_data_penjualan_detail_kirim($id){
		$this->db->simple_query('SET SESSION group_concat_max_len=15000');

		$query = $this->db->query("SELECT tbl_a.*, nama_barang,jenis_barang, nama_satuan, tbl_c.nama as nama_gudang, tbl_d.warna_jual as nama_warna, tbl_e.qty as qty, tbl_e.jumlah_roll as jumlah_roll, data_qty, tbl_b.satuan_id
			FROM (
				SELECT *
				FROM nd_penjualan_detail
				WHERE penjualan_id = $id
				) as tbl_a
			LEFT JOIN (
				SELECT nd_barang.id, nd_barang.nama_jual as nama_barang, nd_satuan.nama as nama_satuan, jenis_barang , satuan_id
				FROM nd_barang
				LEFT JOIN nd_satuan
				ON nd_barang.satuan_id = nd_satuan.id
				) as tbl_b
			ON tbl_a.barang_id = tbl_b.id
			LEFT JOIN nd_gudang as tbl_c
			ON tbl_a.gudang_id = tbl_c.id
			LEFT JOIN nd_warna as tbl_d
			ON tbl_a.warna_id = tbl_d.id
			LEFT JOIN (
				SELECT sum(tA.qty * (if(tA.jumlah_roll = 0,1,tA.jumlah_roll) - ifnull(tB.jumlah_roll,0)) ) as qty, sum(tA.jumlah_roll - ifnull(tB.jumlah_roll,0)) as jumlah_roll, penjualan_detail_id,  group_concat(concat_ws('??',tA.qty,tA.jumlah_roll - ifnull(tB.jumlah_roll,0)) ORDER BY tA.id asc SEPARATOR '--') as data_qty
				FROM (
						SELECT t1.*, barang_id, warna_id
						FROM nd_penjualan_qty_detail t1
						LEFT JOIN nd_penjualan_detail t2
						ON t1.penjualan_detail_id = t2.id
					) tA
				LEFT JOIN (
					SELECT transaction_detail_id, qty, sum(jumlah_roll) as jumlah_roll 
					FROM (
						SELECT *
						FROM nd_surat_jalan 
						where transaction_id = $id 
						AND status_aktif = 1
						) t1
					LEFT JOIN nd_surat_jalan_detail t2
					ON t2.surat_jalan_id = t1.id
					LEFT JOIN nd_surat_jalan_qty t3
					ON t3.surat_jalan_detail_id = t2.id
					GROUP BY transaction_detail_id, qty
				)tB
				ON tA.penjualan_detail_id = tB.transaction_detail_id
				AND tA.qty = tB.qty
				group by penjualan_detail_id
				) as tbl_e
			ON tbl_e.penjualan_detail_id = tbl_a.id
			", false);

		return $query->result();
	}

	function get_penjualan_rinci($tanggal_start, $tanggal_end, $cond){
		$query = $this->db->query("SELECT t1.*, no_faktur_lengkap as no_faktur, tanggal, if(penjualan_type_id = 3, nama_keterangan, t3.nama) as nama_customer
			FROM (
				SELECT tA.*, tB.nama_jual as nama_barang, warna_jual as nama_warna, qty, jumlah_roll
				FROM nd_penjualan_detail tA
				LEFT JOIN (
					SELECT sum(qty*if(jumlah_roll = 0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, penjualan_detail_id
					FROM nd_penjualan_qty_detail
					GROUP BY penjualan_detail_id 
					) tQ
				ON tA.id = tQ.penjualan_detail_id
				LEFT JOIN nd_barang tB
				ON tA.barang_id = tB.id
				LEFT JOIN nd_warna tC
				ON tA.warna_id = tC.id
				)t1
			LEFT JOIN (
				SELECT *
				FROM vw_penjualan_data
				WHERE tanggal >= '$tanggal_start'
				AND tanggal <= '$tanggal_end'
				AND status_aktif = 1
				$cond
				)t2
			ON t1.penjualan_id = t2.id
			LEFT JOIN nd_customer t3
			ON t2.customer_id = t3.id
			WHERE t2.id is not null
				");

		return $query->result();
	}

//=============================pengeluaran_stok_lain================================

	function get_pengeluaran_stok_lain_list(){
		$query = $this->db->query("SELECT t1.status_aktif, no_faktur, tanggal, t1.id as pengeluaran_stok_lain_id, ifnull(g_total,0) as g_total, status, count_item, t1.keterangan, t1.id
				FROM nd_pengeluaran_stok_lain t1
				LEFT JOIN (
					SELECT sum(qty * tA.harga_jual) as g_total, pengeluaran_stok_lain_id, sum(1) as count_item
					FROM nd_pengeluaran_stok_lain_detail tA
					LEFT JOIN (
						SELECT sum(qty * if(jumlah_roll = 0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, pengeluaran_stok_lain_detail_id
						FROM nd_pengeluaran_stok_lain_qty_detail
						group by pengeluaran_stok_lain_detail_id
						) as tB
					ON tB.pengeluaran_stok_lain_detail_id = tA.id
					GROUP BY pengeluaran_stok_lain_id
					) t2
				ON t2.pengeluaran_stok_lain_id = t1.id
				LEFT JOIN (
					SELECT pengeluaran_stok_lain_id, sum(amount) as total_bayar
					FROM nd_pembayaran_pengeluaran_stok_lain
					WHERE pembayaran_type_id != 5
					AND pembayaran_type_id != 6
					GROUP BY pengeluaran_stok_lain_id
					) t3
				ON t3.pengeluaran_stok_lain_id = t1.id
				ORDER BY t1.tanggal desc, no_faktur desc, t1.id asc
			", false);

		return $query->result();
	}

	function get_data_pembayaran_pengeluaran_stok_lain($pengeluaran_stok_lain_id){
		$query = $this->db->query("SELECT a.*, b.nama as nama_bayar
			FROM (
				SELECT *
				FROM nd_pembayaran_pengeluaran_stok_lain
				WHERE pengeluaran_stok_lain_id = $pengeluaran_stok_lain_id
				) a
			LEFT JOIN nd_pembayaran_type b
			ON a.pembayaran_type_id = b.id
			", false);

		return $query->result();
	}

	function get_data_pengeluaran_stok_lain_detail($id){
		$this->db->simple_query('SET SESSION group_concat_max_len=15000');

		$query = $this->db->query("SELECT tbl_a.*, nama_barang, nama_satuan, tbl_c.nama as nama_gudang, tbl_d.warna_jual as nama_warna, tbl_e.qty as qty, tbl_e.jumlah_roll as jumlah_roll, data_qty
			FROM (
				SELECT *
				FROM nd_pengeluaran_stok_lain_detail
				WHERE pengeluaran_stok_lain_id = $id
				) as tbl_a
			LEFT JOIN (
				SELECT nd_barang.id, nd_barang.nama_jual as nama_barang, nd_satuan.nama as nama_satuan 
				FROM nd_barang
				LEFT JOIN nd_satuan
				ON nd_barang.satuan_id = nd_satuan.id
				) as tbl_b
			ON tbl_a.barang_id = tbl_b.id
			LEFT JOIN nd_gudang as tbl_c
			ON tbl_a.gudang_id = tbl_c.id
			LEFT JOIN nd_warna as tbl_d
			ON tbl_a.warna_id = tbl_d.id
			LEFT JOIN (
				SELECT sum(qty * if(jumlah_roll = 0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, pengeluaran_stok_lain_detail_id,  group_concat(concat_ws('??',qty,jumlah_roll) ORDER BY id asc SEPARATOR '--') as data_qty
				FROM nd_pengeluaran_stok_lain_qty_detail
				group by pengeluaran_stok_lain_detail_id
				) as tbl_e
			ON tbl_e.pengeluaran_stok_lain_detail_id = tbl_a.id
			", false);

		return $query->result();
	}

	function get_data_pengeluaran_stok_lain_detail_group($id){
		$this->db->simple_query('SET SESSION group_concat_max_len=15000');
		
		$query = $this->db->query("SELECT tbl_a.*,jenis_barang, nama_barang, nama_satuan, tbl_c.nama as nama_gudang, 
		group_concat(tbl_d.warna_jual SEPARATOR '--') as nama_warna, 
		sum(tbl_e.qty) as qty, sum(tbl_e.jumlah_roll) as jumlah_roll, group_concat(data_qty SEPARATOR '--') as data_qty
			FROM (
				SELECT *
				FROM nd_pengeluaran_stok_lain_detail
				WHERE pengeluaran_stok_lain_id = $id
				) as tbl_a
			LEFT JOIN (
				SELECT nd_barang.id,jenis_barang, concat(nd_barang.nama_jual) as nama_barang, nd_satuan.nama as nama_satuan 
				FROM nd_barang
				LEFT JOIN nd_satuan
				ON nd_barang.satuan_id = nd_satuan.id
				) as tbl_b
			ON tbl_a.barang_id = tbl_b.id
			LEFT JOIN nd_gudang as tbl_c
			ON tbl_a.gudang_id = tbl_c.id
			LEFT JOIN nd_warna as tbl_d
			ON tbl_a.warna_id = tbl_d.id
			LEFT JOIN (
				SELECT sum(qty * if(jumlah_roll=0,1,jumlah_roll )) as qty, sum(jumlah_roll) as jumlah_roll, pengeluaran_stok_lain_detail_id,  group_concat(concat_ws('??',qty,jumlah_roll) SEPARATOR '--') as data_qty
				FROM nd_pengeluaran_stok_lain_qty_detail
				group by pengeluaran_stok_lain_detail_id
				) as tbl_e
			ON tbl_e.pengeluaran_stok_lain_detail_id = tbl_a.id
			GROUP BY barang_id, harga_jual
			", false);

		return $query->result();
	}

	function get_data_pengeluaran_stok_lain_detail_by_barang($id){
		$query = $this->db->query("SELECT  nama_barang, nama_satuan, group_concat(tbl_d.warna_jual SEPARATOR '??') as nama_warna, group_concat(tbl_e.qty SEPARATOR '??') as qty, group_concat(tbl_e.jumlah_roll SEPARATOR '??') as jumlah_roll, group_concat(data_qty SEPARATOR '??') as data_qty, group_concat(roll_qty SEPARATOR '??') as roll_qty, group_concat(data_all SEPARATOR '=??=') as data_all, tbl_b.satuan_id
			FROM (
				SELECT *
				FROM nd_pengeluaran_stok_lain_detail
				WHERE pengeluaran_stok_lain_id = $id
				) as tbl_a
			LEFT JOIN (
				SELECT nd_barang.id, nd_barang.nama_jual as nama_barang, nd_satuan.nama as nama_satuan,satuan_id
				FROM nd_barang
				LEFT JOIN nd_satuan
				ON nd_barang.satuan_id = nd_satuan.id
				) as tbl_b
			ON tbl_a.barang_id = tbl_b.id
			LEFT JOIN nd_warna as tbl_d
			ON tbl_a.warna_id = tbl_d.id
			LEFT JOIN (
				SELECT sum(qty * if(jumlah_roll = 0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, group_concat(jumlah_roll) as roll_qty, pengeluaran_stok_lain_detail_id,  group_concat(qty SEPARATOR ' ') as data_qty, group_concat(concat_ws('??',qty,jumlah_roll) SEPARATOR '--') as data_all
				FROM nd_pengeluaran_stok_lain_qty_detail
				group by pengeluaran_stok_lain_detail_id
				) as tbl_e
			ON tbl_e.pengeluaran_stok_lain_detail_id = tbl_a.id
			GROUP BY barang_id
			", false);

		return $query->result();
	}

	function search_faktur_jual_lain($no_faktur){
		$query = $this->db->query("SELECT id, no_faktur_lengkap as no_faktur
			FROM (
				SELECT id, concat('FPJ', DATE_FORMAT(tanggal,'%d%m%y'),'-',$this->pre_faktur,LPAD(no_faktur,5,'0')) as no_faktur_lengkap
				FROM nd_pengeluaran_stok_lain
				WHERE tanggal >= '2019-01-01'
				)as tbl_a
			WHERE no_faktur_lengkap LIKE '%$no_faktur%'
			", false);

		return $query->result();
	}


//==========================================retur barang================================

	function get_retur_list(){
		$query = $this->db->query("SELECT tbl_a.*, if(tbl_a.nama_keterangan != '', tbl_a.nama_keterangan, if(tbl_a.retur_type_id = 1,'no name', tbl_c.nama )) as nama_customer, username, created_at, no_faktur_lengkap, group_concat(harga) as harga, group_concat(qty) as qty, group_concat(jumlah_roll) as jumlah_roll, nama_barang, group_concat(nama_gudang) as nama_gudang, group_concat(nama_barang) as nama_barang,group_concat(warna_jual) as nama_warna, group_concat(nama_gudang) as nama_gudang
			FROM (
				SELECT t1.*, 
				t2.no_faktur_lengkap as no_faktur_penjualan
				FROM (
					SELECT *, concat('FRJ', DATE_FORMAT(tanggal,'%d%m%y'),'-',LPAD(no_faktur,4,'0')) as no_faktur_lengkap
					FROM nd_retur_jual
					) t1
				LEFT JOIN vw_penjualan_data t2
				ON t1.penjualan_id = t2.id
			) tbl_a
			LEFT JOIN (
				SELECT nd_retur_jual_detail.*, qty, jumlah_roll, retur_jual_detail_id, nd_barang.nama_jual as nama_barang, nd_gudang.nama as nama_gudang, warna_jual
				FROM nd_retur_jual_detail
				LEFT JOIN (
					SELECT sum(qty*jumlah_roll) as qty, sum(jumlah_roll) as jumlah_roll, retur_jual_detail_id
					FROM nd_retur_jual_qty
					GROUP BY retur_jual_detail_id 
					) nd_retur_jual_qty
				ON nd_retur_jual_detail.id = nd_retur_jual_qty.retur_jual_detail_id
				LEFT JOIN nd_barang
				ON nd_retur_jual_detail.barang_id = nd_barang.id
				LEFT JOIN nd_gudang
				ON nd_retur_jual_detail.gudang_id = nd_gudang.id
				LEFT JOIN nd_warna
				ON nd_retur_jual_detail.warna_id = nd_warna.id
			) tbl_b
			ON tbl_a.id = tbl_b.retur_jual_id
			LEFT JOIN nd_customer tbl_c
			ON tbl_a.customer_id = tbl_c.id
			LEFT JOIN nd_user tbl_d
			ON tbl_a.user_id = tbl_d.id
			GROUP BY tbl_a.id
		");
		return $query->result();
	}

	function get_retur_data($id){
		$query = $this->db->query("SELECT tbl_a.*, if(tbl_a.penjualan_type_id != 3, tbl_c.nama, nama_keterangan) as nama_keterangan, username, created_at, no_faktur_lengkap
			FROM (
				SELECT t1.*, no_faktur_penjualan, alamat_keterangan, kota, penjualan_type_id
				FROM (
					SELECT *, concat('FRJ', DATE_FORMAT(tanggal,'%d%m%y'),'-',LPAD(no_faktur,4,'0')) as no_faktur_lengkap
					FROM nd_retur_jual
					WHERE id = $id
					) t1
				LEFT JOIN (
					SELECT t_a.id,if(penjualan_type_id = 3,ifnull(alamat_keterangan,'-') , if (alamat_keterangan != '',alamat_keterangan,alamat)) as alamat_keterangan, 
					no_faktur_lengkap as no_faktur_penjualan, 
					kota, penjualan_type_id
					FROM vw_penjualan_data t_a
					LEFT JOIN nd_customer t_b
					ON t_a.customer_id = t_b.id) t2
				ON t1.penjualan_id = t2.id
			) tbl_a
			LEFT JOIN (
				SELECT *
				FROM nd_retur_jual_qty
				) tbl_b
			ON tbl_a.id = tbl_b.retur_jual_detail_id
			LEFT JOIN nd_customer tbl_c
			ON tbl_a.customer_id = tbl_c.id
			LEFT JOIN nd_user tbl_d
			ON tbl_a.user_id = tbl_d.id
		");
		return $query->result();
	}

	function get_retur_detail($id){
		$query = $this->db->query("SELECT tbl_a.*, jumlah_roll, tbl_c.nama_jual as nama_barang, tbl_d.warna_jual as nama_warna, tbl_e.nama as nama_satuan, data_qty, qty, jumlah_roll
			FROM (
				SELECT *
				FROM nd_retur_jual_detail 
				WHERE retur_jual_id = $id
			) tbl_a
			LEFT JOIN (
				SELECT retur_jual_detail_id, sum(qty * jumlah_roll) as qty, sum(jumlah_roll) as jumlah_roll, group_concat(concat_ws('??',qty, jumlah_roll) SEPARATOR '--') as data_qty 
				FROM nd_retur_jual_qty
				GROUP BY retur_jual_detail_id
				) tbl_b
			ON tbl_a.id = tbl_b.retur_jual_detail_id
			LEFT JOIN nd_barang tbl_c
			ON tbl_a.barang_id = tbl_c.id
			LEFT JOIN nd_warna tbl_d
			ON tbl_a.warna_id = tbl_d.id
			LEFT JOIN nd_satuan tbl_e
			ON tbl_c.satuan_id = tbl_e.id
		");
		return $query->result();
	}

	function get_retur_jual_detail($id){
		$query = $this->db->query("SELECT tbl_a.*, nama_barang, nama_satuan, tbl_c.nama as nama_gudang, tbl_d.warna_jual as nama_warna, tbl_e.qty as qty, tbl_e.jumlah_roll as jumlah_roll, data_qty
			FROM (
				SELECT *
				FROM nd_retur_jual_detail
				WHERE retur_jual_id = $id
				) as tbl_a
			LEFT JOIN (
				SELECT nd_barang.id, nd_barang.nama_jual as nama_barang, nd_satuan.nama as nama_satuan 
				FROM nd_barang
				LEFT JOIN nd_satuan
				ON nd_barang.satuan_id = nd_satuan.id
				) as tbl_b
			ON tbl_a.barang_id = tbl_b.id
			LEFT JOIN nd_gudang as tbl_c
			ON tbl_a.gudang_id = tbl_c.id
			LEFT JOIN nd_warna as tbl_d
			ON tbl_a.warna_id = tbl_d.id
			LEFT JOIN (
				SELECT sum(qty * jumlah_roll) as qty, sum(jumlah_roll) as jumlah_roll, retur_jual_detail_id,  group_concat(concat_ws('??',qty,jumlah_roll) SEPARATOR '--') as data_qty
				FROM nd_retur_jual_qty
				group by retur_jual_detail_id
				) as tbl_e
			ON tbl_e.retur_jual_detail_id = tbl_a.id
			", false);

		return $query->result();
	}

	function get_data_retur_detail_group($id){
		$this->db->simple_query('SET SESSION group_concat_max_len=15000');
		
		$query = $this->db->query("SELECT tbl_a.*, nama_barang, nama_satuan, tbl_c.nama as nama_gudang, group_concat(tbl_d.warna_jual SEPARATOR '--') as nama_warna, sum(tbl_e.qty) as qty, sum(tbl_e.jumlah_roll) as jumlah_roll, group_concat(data_qty SEPARATOR '--') as data_qty
			FROM (
				SELECT *
				FROM nd_retur_jual_detail
				WHERE retur_jual_id = $id
				) as tbl_a
			LEFT JOIN (
				SELECT nd_barang.id, concat(nd_barang.nama_jual) as nama_barang, nd_satuan.nama as nama_satuan 
				FROM nd_barang
				LEFT JOIN nd_satuan
				ON nd_barang.satuan_id = nd_satuan.id
				) as tbl_b
			ON tbl_a.barang_id = tbl_b.id
			LEFT JOIN nd_gudang as tbl_c
			ON tbl_a.gudang_id = tbl_c.id
			LEFT JOIN nd_warna as tbl_d
			ON tbl_a.warna_id = tbl_d.id
			LEFT JOIN (
				SELECT sum(qty * if(jumlah_roll=0,1,jumlah_roll )) as qty, sum(jumlah_roll) as jumlah_roll, retur_jual_detail_id,  group_concat(concat_ws('??',qty,jumlah_roll) SEPARATOR '--') as data_qty
				FROM nd_retur_jual_qty
				group by retur_jual_detail_id
				) as tbl_e
			ON tbl_e.retur_jual_detail_id = tbl_a.id
			GROUP BY barang_id, harga
			", false);


		return $query->result();
	}

	function get_data_pembayaran_retur($retur_jual_id){
		$query = $this->db->query("SELECT a.*, b.nama as nama_bayar
			FROM (
				SELECT *
				FROM nd_pembayaran_retur
				WHERE retur_jual_id = $retur_jual_id
				) a
			LEFT JOIN nd_pembayaran_type b
			ON a.pembayaran_type_id = b.id
			", false);

		return $query->result();
	}

	function get_data_retur_detail_by_barang($id){
		$query = $this->db->query("SELECT  nama_barang, nama_satuan, group_concat(tbl_d.warna_jual SEPARATOR '??') as nama_warna, group_concat(tbl_e.qty SEPARATOR '??') as qty, group_concat(tbl_e.jumlah_roll SEPARATOR '??') as jumlah_roll, group_concat(data_qty SEPARATOR '??') as data_qty, group_concat(roll_qty SEPARATOR '??') as roll_qty, group_concat(data_all SEPARATOR '=??=') as data_all, tbl_b.satuan_id
			FROM (
				SELECT *
				FROM nd_retur_jual_detail
				WHERE retur_jual_id = $id
				) as tbl_a
			LEFT JOIN (
				SELECT nd_barang.id, nd_barang.nama_jual as nama_barang, nd_satuan.nama as nama_satuan,satuan_id
				FROM nd_barang
				LEFT JOIN nd_satuan
				ON nd_barang.satuan_id = nd_satuan.id
				) as tbl_b
			ON tbl_a.barang_id = tbl_b.id
			LEFT JOIN nd_warna as tbl_d
			ON tbl_a.warna_id = tbl_d.id
			LEFT JOIN (
				SELECT sum(qty * if(jumlah_roll = 0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, group_concat(jumlah_roll) as roll_qty, retur_jual_detail_id,  group_concat(qty SEPARATOR ' ') as data_qty, group_concat(concat_ws('??',qty,jumlah_roll) SEPARATOR '--') as data_all
				FROM nd_retur_jual_qty
				group by retur_jual_detail_id
				) as tbl_e
			ON tbl_e.retur_jual_detail_id = tbl_a.id
			GROUP BY barang_id
			", false);

		return $query->result();
	}

//==========================================retur beli================================

	function get_retur_beli_list(){
		$query = $this->db->query("SELECT tA.*, tC.nama as nama_supplier, username, created_at as created_date, group_concat(harga) as harga, group_concat(qty) as qty, group_concat(jumlah_roll) as jumlah_roll, nama_barang, group_concat(nama_gudang) as nama_gudang, group_concat(nama_barang) as nama_barang,group_concat(warna_beli) as nama_warna, group_concat(nama_gudang) as nama_gudang, concat('SJ/R/',if(tC.kode is not null, concat(tC.kode,'/'),'LL/'),DATE_FORMAT(tA.tanggal,'%y'),'-',no_sj ) as no_sj_lengkap, po_number
			FROM (
				SELECT *
				FROM nd_retur_beli t1
			) tA
			LEFT JOIN (
				SELECT t1.*, nd_barang.nama as nama_barang, nd_gudang.nama as nama_gudang, warna_beli, sum(qty * if(jumlah_roll=0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll
				FROM nd_retur_beli_detail t1
				LEFT JOIN nd_retur_beli_qty t2
				ON t2.retur_beli_detail_id = t1.id
				LEFT JOIN nd_barang
				ON t1.barang_id = nd_barang.id
				LEFT JOIN nd_gudang
				ON t1.gudang_id = nd_gudang.id
				LEFT JOIN nd_warna
				ON t1.warna_id = nd_warna.id
				group by t1.id
			) tB
			ON tA.id = tB.retur_beli_id
			LEFT JOIN nd_supplier tC
			ON tA.supplier_id = tC.id
			LEFT JOIN nd_user tD
			ON tA.user_id = tD.id
			LEFT JOIN (
				SELECT t1.id, concat(if(t2.tanggal >= '2019-09-27', concat(ifnull(pre_po,''),t4.kode,'/',DATE_FORMAT(t2.tanggal,'%y'),'/',LPAD(po_number,3,'0')) ,concat(LPAD(po_number,3,'0'),'/',DATE_FORMAT(t2.tanggal,'%m'),'/',DATE_FORMAT(t2.tanggal,'%y'))),'-',batch,if(revisi > 1,concat('R',revisi-1),'') ) as po_number, po_pembelian_id
				FROM nd_po_pembelian_batch t1
				LEFT JOIN nd_po_pembelian t2
				ON t1.po_pembelian_id = t2.id
				LEFT JOIN nd_toko t3
				ON t2.toko_id = t3.id
				LEFT JOIN nd_supplier t4
				ON t2.supplier_id = t4.id
				WHERE t2.status_aktif = 1
				) tPO
			ON tA.po_pembelian_batch_id = tPO.id
			GROUP BY tA.id
		");
		return $query->result();
	}

	function get_retur_beli_data($id){
		$query = $this->db->query("SELECT tA.*, tC.nama as nama_supplier, username, created_at, po_number, concat('SJ/R/',if(tC.kode is not null, concat(tC.kode,'/'),'LL/'),DATE_FORMAT(tA.tanggal,'%y'),'-',no_sj ) as no_sj_lengkap, po_pembelian_id, tPO.id as batch_id
			FROM (
				SELECT *
				FROM nd_retur_beli
				WHERE id = $id
			) tA
			LEFT JOIN (
				SELECT *
				FROM nd_retur_beli_detail
			) tB
			ON tA.id = tB.retur_beli_id
			LEFT JOIN (
				SELECT t1.id, concat(if(t2.tanggal >= '2019-09-27', concat(ifnull(pre_po,''),t4.kode,'/',DATE_FORMAT(t2.tanggal,'%y'),'/',LPAD(po_number,3,'0')) ,concat(LPAD(po_number,3,'0'),'/',DATE_FORMAT(t2.tanggal,'%m'),'/',DATE_FORMAT(t2.tanggal,'%y'))),'-',batch,if(revisi > 1,concat('R',revisi-1),'') ) as po_number, po_pembelian_id
				FROM nd_po_pembelian_batch t1
				LEFT JOIN nd_po_pembelian t2
				ON t1.po_pembelian_id = t2.id
				LEFT JOIN nd_toko t3
				ON t2.toko_id = t3.id
				LEFT JOIN nd_supplier t4
				ON t2.supplier_id = t4.id
				WHERE t2.status_aktif = 1
				) tPO
			ON tA.po_pembelian_batch_id = tPO.id
			LEFT JOIN nd_supplier tC
			ON tA.supplier_id = tC.id
			LEFT JOIN nd_user tD
			ON tA.user_id = tD.id
		");
		return $query->result();
	}

	function get_retur_beli_detail($id){
		$query = $this->db->query("SELECT t1.*, nama_barang, nama_satuan, t3.nama as nama_gudang, t4.warna_beli as nama_warna, qty, jumlah_roll, data_qty
			FROM (
				SELECT *
				FROM nd_retur_beli_detail
				WHERE retur_beli_id = $id
				) t1
			LEFT JOIN (
				SELECT nd_barang.id, nd_barang.nama as nama_barang, nd_satuan.nama as nama_satuan 
				FROM nd_barang
				LEFT JOIN nd_satuan
				ON nd_barang.satuan_id = nd_satuan.id
				) t2
			ON t1.barang_id = t2.id
			LEFT JOIN nd_gudang t3
			ON t1.gudang_id = t3.id
			LEFT JOIN nd_warna t4
			ON t1.warna_id = t4.id
			LEFT JOIN (
	            SELECT sum(qty*if(jumlah_roll = 0,1,jumlah_roll) ) as qty, sum(jumlah_roll) as jumlah_roll, retur_beli_detail_id, group_concat(concat_ws('??',qty, jumlah_roll) SEPARATOR '--') as data_qty 
	            FROM nd_retur_beli_qty
	            GROUP BY retur_beli_detail_id
	            ) t5
	        ON t5.retur_beli_detail_id = t1.id
			", false);

		return $query->result();
	}

	function get_data_retur_beli_detail_group($id){
		$this->db->simple_query('SET SESSION group_concat_max_len=15000');
		
		$query = $this->db->query("SELECT t1.*, nama_barang, nama_satuan, t3.nama as nama_gudang, group_concat(t4.warna_beli SEPARATOR '--') as nama_warna, sum(qty) as qty, sum(jumlah_roll) as jumlah_roll, group_concat(data_qty SEPARATOR '??') as data_qty, group_concat(roll_qty SEPARATOR '??') as roll_qty, group_concat(data_all SEPARATOR '=??=') as data_all
			FROM (
				SELECT *
				FROM nd_retur_beli_detail
				WHERE retur_beli_id = $id
				) t1
			LEFT JOIN (
				SELECT nd_barang.id, concat(nd_barang.nama) as nama_barang, nd_satuan.nama as nama_satuan 
				FROM nd_barang
				LEFT JOIN nd_satuan
				ON nd_barang.satuan_id = nd_satuan.id
				) t2
			ON t1.barang_id = t2.id
			LEFT JOIN nd_gudang t3
			ON t1.gudang_id = t3.id
			LEFT JOIN nd_warna t4
			ON t1.warna_id = t4.id
			LEFT JOIN (
	            SELECT sum(qty*if(jumlah_roll = 0,1,jumlah_roll) ) as qty, sum(jumlah_roll) as jumlah_roll, retur_beli_detail_id, group_concat(jumlah_roll) as roll_qty, group_concat(qty SEPARATOR ' ') as data_qty, group_concat(concat_ws('??',qty,jumlah_roll) SEPARATOR '--') as data_all
	            FROM nd_retur_beli_qty
	            GROUP BY retur_beli_detail_id
	            ) t5
	        ON t5.retur_beli_detail_id = t1.id
			GROUP BY barang_id, warna_id, harga
			", false);


		return $query->result();
	}


//==========================================dp_list================================

	function get_dp_list(){
		$query = $this->db->query("SELECT tbl_a.id, nama, status_aktif , ifnull(dp_masuk,0) - ifnull(dp_keluar,0) - ifnull(dp_on_piutang,0) as saldo
			FROM (
				SELECT *
				FROM nd_customer
				WHERE status_aktif = 1
				) as tbl_a
			LEFT JOIN (
				SELECT sum(dp_masuk) as dp_masuk, customer_id
				FROM (
					SELECT amount - ifnull(dp_keluar,0) as dp_masuk, customer_id
					FROM nd_dp_masuk
					LEFT JOIN (
						SELECT sum(amount) as dp_keluar, dp_masuk_id
						FROM nd_dp_keluar
						GROUP BY dp_masuk_id
						)nd_dp_keluar
					ON nd_dp_masuk.id = nd_dp_keluar.dp_masuk_id
					)result
					group by customer_id
				) as tbl_b
			ON tbl_a.id = tbl_b.customer_id
			LEFT JOIN (
				SELECT sum(ifnull(amount,0)) as dp_keluar, customer_id
				FROM (
					SELECT amount, penjualan_id 
					FROM nd_pembayaran_penjualan
					WHERE pembayaran_type_id = 1
					AND dp_masuk_id is not null
					) as nd_pembayaran_penjualan
				LEFT JOIN nd_penjualan
				ON nd_pembayaran_penjualan.penjualan_id = nd_penjualan.id
				group by customer_id
				) as tbl_c
			ON tbl_c.customer_id = tbl_a.id
			LEFT JOIN (
				SELECT sum(amount) as dp_on_piutang, customer_id
				FROM (
					SELECT *
					FROM nd_pembayaran_piutang_nilai
					WHERE pembayaran_type_id = 5
					AND dp_masuk_id is not null
					) t1
				LEFT JOIN (
					SELECT *
					FROM nd_pembayaran_piutang
					WHERE status_aktif = 1
					) t2
				ON t1.pembayaran_piutang_id = t2.id
				WHERE t2.id is not null
				GROUP BY customer_id
				) tbl_d
			ON tbl_d.customer_id = tbl_a.id
		");
		return $query->result();
	}

	function get_dp_awal($customer_id, $from){
		$query = $this->db->query("SELECT ifnull(dp_masuk,0) - ifnull(dp_keluar,0) - ifnull(dp_on_piutang,0) as saldo
			FROM (
				SELECT sum(ifnull(amount,0)) as dp_masuk, customer_id
				FROM nd_dp_masuk
				WHERE customer_id = $customer_id
				AND tanggal < '$from'
				group by customer_id
				) as tbl_a
			LEFT JOIN (
				SELECT sum(ifnull(amount,0)) as dp_keluar, customer_id
				FROM (
					SELECT *
					FROM nd_penjualan
					WHERE customer_id = $customer_id
					AND tanggal < '$from'
					) as nd_penjualan
				LEFT JOIN (
					SELECT amount, penjualan_id 
					FROM nd_pembayaran_penjualan
					WHERE pembayaran_type_id = 1
					) as nd_pembayaran_penjualan
				ON nd_pembayaran_penjualan.penjualan_id = nd_penjualan.id
				group by customer_id
				) as tbl_b
			ON tbl_b.customer_id = tbl_a.customer_id
			LEFT JOIN (
				SELECT sum(ifnull(amount,0)) as dp_on_piutang, customer_id
				FROM (
					SELECT *
					FROM nd_pembayaran_piutang_nilai
					WHERE pembayaran_type_id = 5
					AND tanggal_transfer < '$from'
					AND dp_masuk_id is not null
					) t1
				LEFT JOIN (
					SELECT *
					FROM nd_pembayaran_piutang
					WHERE status_aktif = 1
					AND customer_id = $customer_id
					) t2
				ON t1.pembayaran_piutang_id = t2.id
				WHERE t2.id is not null
				GROUP BY customer_id
				) tbl_d
			ON tbl_d.customer_id = tbl_a.customer_id
		");
		return $query->result();
	}

	function get_dp_detail($customer_id, $from, $to){
		$query = $this->db->query("SELECT *
			FROM
			(
				(
					SELECT a.id, dp_masuk, dp_keluar, tanggal, a.keterangan,no_faktur_lengkap, pembayaran_data, pembayaran_type_id, b.nama as bayar_dp, 'i' as type, urutan_giro
					FROM (
						SELECT id,ifnull(amount,0) as dp_masuk, 0 as dp_keluar, tanggal, keterangan, concat('DP', DATE_FORMAT(tanggal,'%d%m%y'),'-',LPAD(no_dp,4,'0')) as no_faktur_lengkap, concat(ifnull(nama_penerima,'-'),'??',ifnull(nama_bank,'-'),'??',ifnull(no_rek_bank,'-'),'??', ifnull(no_giro,'-'),'??',ifnull(jatuh_tempo,'-')) as pembayaran_data, pembayaran_type_id, urutan_giro
						FROM nd_dp_masuk
						WHERE customer_id = $customer_id
						AND tanggal >= '$from'
						AND tanggal <= '$to'
					) a
					LEFT JOIN nd_pembayaran_type b
					ON a.pembayaran_type_id = b.id
				)UNION(
					SELECT t2.id, 0 as dp_masuk, amount, t2.tanggal, t3.pembayaran_data as keterangan, no_faktur_lengkap, t2.pembayaran_data as pembayaran_data, pembayaran_type_id, b.nama as bayar_dp, 'pj',''
					FROM (
						SELECT *
						FROM nd_pembayaran_penjualan
						WHERE pembayaran_type_id = 1
						) t1
					LEFT JOIN (
						SELECT *, no_faktur_lengkap as pembayaran_data
						FROM vw_penjualan_data
						WHERE status_aktif = 1
						AND tanggal >= '$from'
						AND tanggal <= '$to'
						AND customer_id = $customer_id
						) t2
					ON t1.penjualan_id = t2.id
					LEFT JOIN nd_pembayaran_type b
					ON t1.pembayaran_type_id = b.id
					LEFT JOIN (
						SELECT a.id,ifnull(amount,0) as dp_masuk, 0 as dp_keluar, tanggal, keterangan, concat('DP', DATE_FORMAT(tanggal,'%d%m%y'),'-',LPAD(no_dp,4,'0')) as no_faktur_lengkap, concat(ifnull(nama_penerima,'-'),'??',ifnull(nama_bank,'-'),'??',ifnull(no_rek_bank,'-'),'??', ifnull(no_giro,'-'),'??',ifnull(jatuh_tempo,'-'),'??',b.nama,'??',pembayaran_type_id,'??',amount,'??', tanggal,'??',a.id) as pembayaran_data
						FROM nd_dp_masuk a
						LEFT JOIN nd_pembayaran_type b
						ON a.pembayaran_type_id = b.id
						) t3
					ON t1.dp_masuk_id = t3.id
					WHERE t2.id is not null
				)UNION(
					SELECT t2.id, 0 as dp_masuk, amount, t1.tanggal_transfer, t3.pembayaran_data as keterangan, no_faktur_lengkap, t2.pembayaran_data as pembayaran_data, pembayaran_type_id, b.nama as bayar_dp, 'pp',''
					FROM (
						SELECT tanggal_transfer,amount, pembayaran_piutang_id, dp_masuk_id, 1 as pembayaran_type_id
						FROM nd_pembayaran_piutang_nilai
						WHERE pembayaran_type_id = 5
						AND tanggal_transfer >= '$from'
						AND tanggal_transfer <= '$to'
						) t1
					LEFT JOIN (
						SELECT *, 'Pelunasan Piutang' as pembayaran_data
						FROM nd_pembayaran_piutang
						WHERE status_aktif = 1
						AND customer_id = $customer_id
						) t2
					ON t1.pembayaran_piutang_id = t2.id
					LEFT JOIN nd_pembayaran_type b
					ON t1.pembayaran_type_id = b.id
					LEFT JOIN (
						SELECT a.id,ifnull(amount,0) as dp_masuk, 0 as dp_keluar, tanggal, keterangan, concat('DP', DATE_FORMAT(tanggal,'%d%m%y'),'-',LPAD(no_dp,4,'0')) as no_faktur_lengkap, concat(ifnull(nama_penerima,'-'),'??',ifnull(nama_bank,'-'),'??',ifnull(no_rek_bank,'-'),'??', ifnull(no_giro,'-'),'??',ifnull(jatuh_tempo,'-'),'??',b.nama,'??',pembayaran_type_id,'??',amount,'??', tanggal,'??',a.id) as pembayaran_data
						FROM nd_dp_masuk a
						LEFT JOIN nd_pembayaran_type b
						ON a.pembayaran_type_id = b.id
						) t3
					ON t1.dp_masuk_id = t3.id
					WHERE t2.id is not null
				) 
			) A
			order by tanggal asc
		");
		return $query->result();
	}

	function get_dp_detail_quota($customer_id, $cond){
		$query = $this->db->query("SELECT *
			FROM
			(
				Select tA.*, tB.*
				FROM(
					SELECT a.*, b.nama as bayar_dp, 'i' as type
					FROM (
						SELECT id,ifnull(amount,0) as dp_masuk, tanggal, keterangan, concat('DP', DATE_FORMAT(tanggal,'%d%m%y'),'-',LPAD(no_dp,4,'0')) as no_faktur_lengkap, nama_penerima,nama_bank,no_rek_bank, no_giro, jatuh_tempo, pembayaran_type_id, urutan_giro
						FROM nd_dp_masuk
						WHERE customer_id = $customer_id
					) a
					LEFT JOIN nd_pembayaran_type b
					ON a.pembayaran_type_id = b.id
				)tA
				LEFT JOIN (
					SELECT group_concat(id) as trx_id, group_concat(ifnull(amount,0)) as dp_keluar_amount_data, sum(ifnull(amount,0)) as dp_keluar, group_concat(tanggal_jual) as tanggal_keluar, group_concat(pembayaran_data) as data_keluar, dp_masuk_id, group_concat(detail_id) as detail_id  , group_concat(type_out) as type_out
					FROM (
						(
							SELECT t2.id, amount, t2.tanggal as tanggal_jual, t2.pembayaran_data as pembayaran_data, 'pj' as type_out, dp_masuk_id, t1.id as detail_id
							FROM (
								SELECT *
								FROM nd_pembayaran_penjualan
								WHERE pembayaran_type_id = 1
								) t1
							LEFT JOIN (
								SELECT *, no_faktur_lengkap as pembayaran_data
								FROM vw_penjualan_data
								WHERE status_aktif = 1
								AND customer_id = $customer_id
								) t2
							ON t1.penjualan_id = t2.id
							WHERE t2.id is not null
						)UNION(
							SELECT t2.id, amount, tanggal_kontra, t2.pembayaran_data as pembayaran_data, 'pp', dp_masuk_id, t1.id as detail_id
							FROM (
								SELECT tanggal_transfer,amount, pembayaran_piutang_id, dp_masuk_id, 1 as pembayaran_type_id, id
								FROM nd_pembayaran_piutang_nilai
								WHERE pembayaran_type_id = 5
								) t1
							LEFT JOIN (
								SELECT *, 'Pelunasan Piutang' as pembayaran_data
								FROM nd_pembayaran_piutang
								WHERE status_aktif = 1
								AND customer_id = $customer_id
								) t2
							ON t1.pembayaran_piutang_id = t2.id
							WHERE t2.id is not null
						)UNION(
							SELECT id, amount, tanggal, concat(ifnull(nama_penerima,'-'),'??',ifnull(nama_bank,'-'),'??',ifnull(no_rek_bank,'-'),'??',ifnull(keterangan,'-'),'??',pembayaran_type_id,'??',amount) as pembayaran_data, 'dpk',dp_masuk_id, pembayaran_type_id
							FROM nd_dp_keluar
						)
					)result
					GROUP BY dp_masuk_id 
				)tB
				ON tA.id = tB.dp_masuk_id
				$cond
			) A
			order by tanggal asc
		");
		return $query->result();
	}

	function get_dp_detail_by_dp($customer_id, $dp_masuk_id){
		$query = $this->db->query("SELECT *
			FROM
			(
				(
					SELECT a.id, dp_masuk, dp_keluar, tanggal, a.keterangan,no_faktur_lengkap, pembayaran_data, pembayaran_type_id, b.nama as bayar_dp, 'i' as type, urutan_giro
					FROM (
						SELECT id,ifnull(amount,0) as dp_masuk, 0 as dp_keluar, tanggal, keterangan, concat('DP', DATE_FORMAT(tanggal,'%d%m%y'),'-',LPAD(no_dp,4,'0')) as no_faktur_lengkap, concat(ifnull(nama_penerima,'-'),'??',ifnull(nama_bank,'-'),'??',ifnull(no_rek_bank,'-'),'??', ifnull(no_giro,'-'),'??',ifnull(jatuh_tempo,'-')) as pembayaran_data, pembayaran_type_id, urutan_giro
						FROM nd_dp_masuk
						WHERE id = $dp_masuk_id
					) a
					LEFT JOIN nd_pembayaran_type b
					ON a.pembayaran_type_id = b.id
				)UNION(
					SELECT t2.id, 0 as dp_masuk, amount, t2.tanggal, t3.pembayaran_data as keterangan, no_faktur_lengkap, t2.pembayaran_data as pembayaran_data, pembayaran_type_id, b.nama as bayar_dp, 'pj', ''
					FROM (
						SELECT *
						FROM nd_pembayaran_penjualan
						WHERE dp_masuk_id = $dp_masuk_id
						) t1
					LEFT JOIN (
						SELECT *, no_faktur_lengkap as pembayaran_data
						FROM vw_penjualan_data
						WHERE status_aktif = 1
						AND customer_id = $customer_id
						) t2
					ON t1.penjualan_id = t2.id
					LEFT JOIN nd_pembayaran_type b
					ON t1.pembayaran_type_id = b.id
					LEFT JOIN (
						SELECT a.id,ifnull(amount,0) as dp_masuk, 0 as dp_keluar, tanggal, keterangan, concat('DP', DATE_FORMAT(tanggal,'%d%m%y'),'-',LPAD(no_dp,4,'0')) as no_faktur_lengkap, concat(ifnull(nama_penerima,'-'),'??',ifnull(nama_bank,'-'),'??',ifnull(no_rek_bank,'-'),'??', ifnull(no_giro,'-'),'??',ifnull(jatuh_tempo,'-'),'??',b.nama,'??',pembayaran_type_id,'??',amount,'??', tanggal,'??',a.id) as pembayaran_data
						FROM nd_dp_masuk a
						LEFT JOIN nd_pembayaran_type b
						ON a.pembayaran_type_id = b.id
						) t3
					ON t1.dp_masuk_id = t3.id
					WHERE t2.id is not null
				)UNION(
					SELECT t2.id, 0 as dp_masuk, amount, t2.tanggal, t3.pembayaran_data as keterangan, no_faktur_lengkap, t2.pembayaran_data as pembayaran_data, pembayaran_type_id, b.nama as bayar_dp, 'pp', ''
					FROM (
						SELECT amount, pembayaran_piutang_id, dp_masuk_id, 1 as pembayaran_type_id
						FROM nd_pembayaran_piutang_nilai
						WHERE pembayaran_type_id = 5
						AND dp_masuk_id = $dp_masuk_id
						) t1
					LEFT JOIN (
						SELECT *, 'Pelunasan Piutang' as pembayaran_data
						FROM nd_penjualan
						WHERE status_aktif = 1
						AND customer_id = $customer_id
						) t2
					ON t1.pembayaran_piutang_id = t2.id
					LEFT JOIN nd_pembayaran_type b
					ON t1.pembayaran_type_id = b.id
					LEFT JOIN (
						SELECT a.id,ifnull(amount,0) as dp_masuk, 0 as dp_keluar, tanggal, keterangan, concat('DP', DATE_FORMAT(tanggal,'%d%m%y'),'-',LPAD(no_dp,4,'0')) as no_faktur_lengkap, concat(ifnull(nama_penerima,'-'),'??',ifnull(nama_bank,'-'),'??',ifnull(no_rek_bank,'-'),'??', ifnull(no_giro,'-'),'??',ifnull(jatuh_tempo,'-'),'??',b.nama,'??',pembayaran_type_id,'??',amount,'??', tanggal,'??',a.id) as pembayaran_data
						FROM nd_dp_masuk a
						LEFT JOIN nd_pembayaran_type b
						ON a.pembayaran_type_id = b.id
						) t3
					ON t1.dp_masuk_id = t3.id
					WHERE t2.id is not null
				) 
			) A
			order by tanggal asc
		");
		return $query->result();
	}

	function get_dp_berlaku($customer_id, $penjualan_id){
		$query = $this->db->query("SELECT a.*, c.nama as bayar_dp, b.amount as amount_bayar
				FROM (
					SELECT t1.id,amount - ifnull(amount_use,0) - ifnull(amount_piutang,0) - ifnull(amount_keluar,0) as amount, tanggal, keterangan, no_faktur_lengkap, nama_penerima, nama_bank, no_rek_bank, no_giro, jatuh_tempo, pembayaran_type_id
					FROM (
						SELECT id,amount, tanggal, keterangan, concat('DP', DATE_FORMAT(tanggal,'%d%m%y'),'-',LPAD(no_dp,4,'0')) as no_faktur_lengkap, nama_penerima, nama_bank, no_rek_bank, no_giro, jatuh_tempo, pembayaran_type_id
						FROM nd_dp_masuk
						WHERE customer_id = $customer_id
					)t1
					LEFT JOIN (
						SELECT dp_masuk_id, sum(amount) as amount_use
						FROM nd_pembayaran_penjualan
						WHERE pembayaran_type_id = 1
						AND penjualan_id != $penjualan_id
						AND amount != 0
						GROUP BY dp_masuk_id
					)t2
					ON t1.id = t2.dp_masuk_id
					LEFT JOIN (
						SELECT dp_masuk_id, sum(amount) as amount_piutang
						FROM nd_pembayaran_piutang_nilai
						WHERE pembayaran_type_id = 5
						AND dp_masuk_id is not null
						GROUP by dp_masuk_id
						) t3
					ON t1.id = t3.dp_masuk_id
					LEFT JOIN (
                        SELECT sum(amount) as amount_keluar, dp_masuk_id
                        FROM nd_dp_keluar
                        GROUP BY dp_masuk_id
                        ) t4
                    ON t1.id = t4.dp_masuk_id
                    WHERE amount - ifnull(amount_use,0) - ifnull(amount_piutang,0) - ifnull(amount_keluar,0) > 0
				) a
				LEFT JOIN (
					SELECT *
					FROM nd_pembayaran_penjualan
					WHERE penjualan_id = $penjualan_id
					AND pembayaran_type_id = 1
					) b
				ON a.id = b.dp_masuk_id
				LEFT JOIN nd_pembayaran_type c
				ON a.pembayaran_type_id = c.id
		");
		return $query->result();
	}

	function get_data_dp($dp_id){
		$query = $this->db->query("SELECT tbl_a.*, tbl_b.nama as nama_customer, no_faktur_lengkap as no_faktur, tbl_b.alamat, tbl_c.nama as bayar_dp
			FROM (
				SELECT *,concat('DP', DATE_FORMAT(tanggal,'%d%m%y'),'-',LPAD(no_dp,4,'0')) as no_faktur_lengkap
				FROM nd_dp_masuk
				WHERE id = $dp_id
			) tbl_a
			LEFT JOIN nd_customer tbl_b 
			ON tbl_a.customer_id = tbl_b.id
			LEFT JOIN nd_pembayaran_type tbl_c
			ON tbl_a.pembayaran_type_id = tbl_c.id
		");
		return $query->result();
	}

//==========================================history list================================

	function get_pembelian_history($from, $to){
		$query = $this->db->query("SELECT tbl_a.id, tbl_a.status_aktif, tbl_b.nama as toko, no_faktur, tanggal, qty as jumlah, 
			jumlah_roll, tbl_d.nama as nama_barang, tbl_c.harga_beli, tbl_e.nama as gudang, 0 as harga, tbl_f.nama as supplier, total, 
			tbl_a.created_at as created , username
				FROM (
					SELECT *
					FROM nd_pembelian
					WHERE DATE(created_at) >= '$from'
					AND DATE(created_at) <= '$to'
					ORDER BY created_at desc
					) as tbl_a
				LEFT JOIN nd_toko as tbl_b
				ON tbl_a.toko_id = tbl_b.id
				LEFT JOIN (
					SELECT id, sum(t1.qty) as qty, sum(t1.jumlah_roll) as jumlah_roll, sum(t1.qty*harga_beli) as total, harga_beli, pembelian_id, barang_id, satuan_id
					FROM nd_pembelian_detail t1
					LEFT JOIN (
						SELECT sum(qty * if(jumlah_roll = 0,1,jumlah_roll) ) as qty, sum(jumlah_roll) as jumlah_roll, pembelian_detail_id
						FROM nd_pembelian_qty_detail
						group by pembelian_detail_id
						) t2
					ON t2.pembelian_detail_id = t1.id
					group by pembelian_id
					) as tbl_c
				ON tbl_c.pembelian_id = tbl_a.id
				LEFT JOIN nd_barang as tbl_d
				ON tbl_c.barang_id = tbl_d.id
				LEFT JOIN nd_gudang as tbl_e
				ON tbl_a.gudang_id = tbl_e.id
				LEFT JOIN nd_supplier as tbl_f
				ON tbl_f.id = tbl_a.supplier_id
				LEFT JOIN nd_satuan as tbl_g
				ON tbl_c.satuan_id = tbl_g.id
				LEFT JOIN nd_user tbl_h
				ON tbl_a.user_id = tbl_h.id
		");
		return $query->result();
	}

	function get_penjualan_history($from, $to){
		$query = $this->db->query("SELECT tbl_a.id, tbl_a.status_aktif, no_faktur_lengkap as no_faktur, tanggal, ifnull(g_total,0) as g_total , ifnull(diskon,0) as diskon, ifnull(ongkos_kirim,0) as ongkos_kirim, if (penjualan_type_id = 3, concat(' ',nama_keterangan, ' (non-pelanggan) '), tbl_c.nama) as nama_customer, (ifnull(total_bayar,0) - (ifnull(g_total,0) - ifnull(diskon,0)) + ongkos_kirim) as keterangan, concat_ws('??',tbl_a.id,no_faktur) as data, created_at as created , tbl_e.username, tbl_f.username as username_close, closed_date
				FROM (
					SELECT t1.*,
						if((t1.tanggal < '2022-05-01 00:00:00'),
						concat('FPJ',convert(date_format(t1.tanggal,'%d%m%y') using latin1),'-',ifnull(t2.pre_faktur,''),convert(lpad(t1.no_faktur,5,'0') using latin1)),
						concat(t2.pre_po,':PJ01/',convert(date_format(t1.tanggal,'%y%m') using latin1),'/',convert(lpad(t1.no_faktur,4,'0') using latin1))) AS no_faktur_lengkap
					from nd_penjualan t1 
					left join nd_toko t2 
					on t1.toko_id = t2.id
					WHERE DATE(created_at) >= '$from'
					AND DATE(created_at) <= '$to'
					ORDER BY created_at desc
					)as tbl_a
				LEFT JOIN (
					SELECT sum(qty *nd_penjualan_detail.harga_jual) as g_total, penjualan_id 
					FROM nd_penjualan_detail
					LEFT JOIN (
						SELECT sum(qty * if(jumlah_roll = 0,1,jumlah_roll) ) as qty, sum(jumlah_roll) as jumlah_roll, penjualan_detail_id
						FROM nd_penjualan_qty_detail
						group by penjualan_detail_id
						) as nd_penjualan_qty_detail
					ON nd_penjualan_qty_detail.penjualan_detail_id = nd_penjualan_detail.id
					GROUP BY penjualan_id
					) as tbl_b
				ON tbl_b.penjualan_id = tbl_a.id
				LEFT JOIN nd_customer as tbl_c
				ON tbl_a.customer_id = tbl_c.id
				LEFT JOIN (
					SELECT penjualan_id, sum(amount) as total_bayar
					FROM nd_pembayaran_penjualan
					GROUP BY penjualan_id
					) as tbl_d
				ON tbl_d.penjualan_id = tbl_a.id
				LEFT JOIN nd_user tbl_e
				ON tbl_a.user_id = tbl_e.id
				LEFT JOIN nd_user tbl_f
				ON tbl_a.closed_by = tbl_f.id
		");
		return $query->result();
	}


//==============================piutang=========================================================

	function get_piutang_list(){
		$query = $this->db->query("SELECT tbl_a.status_aktif, ifnull(tbl_c.nama,'no name') as nama_customer, sum(ifnull(total_bayar,0) - (ifnull(g_total,0) - ifnull(diskon,0)) + ongkos_kirim) as sisa_piutang, concat_ws('??',tbl_a.id,no_faktur) as data, if(tbl_a.status = -1,-1,0) as status, customer_id, MIN(tanggal) as tanggal_start, MAX(tanggal) as tanggal_end
				FROM (
					SELECT *
					FROM vw_penjualan_data 
					WHERE status_aktif = 1
					AND customer_id != 0
					ORDER BY tanggal desc
					)as tbl_a
				LEFT JOIN (
					SELECT sum(qty *nd_penjualan_detail.harga_jual) - ifnull(total_bayar,0) as g_total, nd_penjualan_detail.penjualan_id 
					FROM nd_penjualan_detail
					LEFT JOIN (
						SELECT sum(qty * if(jumlah_roll=0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, penjualan_detail_id
						FROM nd_penjualan_qty_detail
						group by penjualan_detail_id
						) as nd_penjualan_qty_detail
					ON nd_penjualan_qty_detail.penjualan_detail_id = nd_penjualan_detail.id
					LEFT JOIN (
						SELECT sum(amount) as total_bayar, penjualan_id
						FROM nd_pembayaran_penjualan
						WHERE pembayaran_type_id != 5
						GROUP by penjualan_id
					) nd_pembayaran_penjualan
					ON nd_penjualan_detail.penjualan_id = nd_pembayaran_penjualan.penjualan_id
					GROUP BY penjualan_id
					) as tbl_b
				ON tbl_b.penjualan_id = tbl_a.id
				LEFT JOIN nd_customer as tbl_c
				ON tbl_a.customer_id = tbl_c.id
				LEFT JOIN (
					SELECT penjualan_id, sum(amount) as total_bayar
					FROM nd_pembayaran_piutang_temp_detail
					GROUP BY penjualan_id
					) as tbl_d
				ON tbl_d.penjualan_id = tbl_a.id
				WHERE ifnull(total_bayar,0) - (ifnull(g_total,0) - ifnull(diskon,0)) + ongkos_kirim < 0
				group by customer_id
			", false);

		return $query->result();
	}

	function get_piutang_list_all(){
		
		$query = $this->db->query(" SELECT customer_id, t2.nama as nama_customer, t3.nama as nama_toko, sum(sisa_piutang) as sisa_piutang, MIN(tanggal_start) as tanggal_start, MAX(tanggal_end) as tanggal_end, toko_id
			FROM (
				(
					SELECT tbl_a.status_aktif, sum((ifnull(g_total,0) - ifnull(diskon,0)) + ongkos_kirim - ifnull(total_bayar,0)) as sisa_piutang, concat_ws('??',tbl_a.id,no_faktur_lengkap) as data, if(tbl_a.status = -1,-1,0) as status, customer_id, MIN(tanggal) as tanggal_start, MAX(tanggal) as tanggal_end, tbl_a.toko_id
					FROM (
						SELECT *
						FROM vw_penjualan_data 
						WHERE status_aktif = 1
						AND penjualan_type_id != 3
						AND no_faktur != ''
						ORDER BY tanggal desc
						)as tbl_a
					LEFT JOIN (
						SELECT sum(qty *nd_penjualan_detail.harga_jual) as g_total, penjualan_id 
						FROM nd_penjualan_detail
						LEFT JOIN (
							SELECT sum(qty * if(jumlah_roll=0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, penjualan_detail_id
							FROM nd_penjualan_qty_detail
							group by penjualan_detail_id
							) as nd_penjualan_qty_detail
						ON nd_penjualan_qty_detail.penjualan_detail_id = nd_penjualan_detail.id
						GROUP BY penjualan_id
						) as tbl_b
					ON tbl_b.penjualan_id = tbl_a.id
					LEFT JOIN (
						SELECT penjualan_id, sum(amount) as total_bayar
						FROM nd_pembayaran_piutang_temp_detail
						GROUP BY penjualan_id
						) as tbl_d
					ON tbl_d.penjualan_id = tbl_a.id
					WHERE ifnull(total_bayar,0) - (ifnull(g_total,0) - ifnull(diskon,0)) + ongkos_kirim < 0
					group by customer_id, toko_id

				)UNION(
					SELECT 1, sum(ifnull(amount,0) - 0) as sisa_piutang, concat_ws('??',id,no_faktur) as data, 1, customer_id, MIN(tanggal) as tanggal_start, MAX(tanggal) as tanggal_end, toko_id
					FROM nd_piutang_awal
					GROUP BY customer_id, toko_id
				)
			) t1
			LEFT JOIN nd_customer as t2
			ON t1.customer_id = t2.id
			LEFT JOIN nd_toko t3
			ON t1.toko_id = t3.id
			GROUP BY customer_id
			ORDER BY t2.nama asc
			", false);

		return $query->result();
	}

	function get_pembayaran_piutang_unbalance(){
		$query = $this->db->query("SELECT tbl_a.*, (ifnull(bayar,0)+ifnull(pembulatan,0)) - amount as balance
				FROM (
					SELECT a.id, b.nama as nama_customer, c.nama as nama_toko, customer_id, toko_id, pembulatan
					FROM nd_pembayaran_piutang_temp a
					LEFT JOIN nd_customer b
					ON a.customer_id = b.id
					LEFT JOIN nd_toko c
					ON a.toko_id = c.id
					) tbl_a
				LEFT JOIN (
					SELECT pembayaran_piutang_id, sum(amount) as amount
					FROM nd_pembayaran_piutang_temp_detail
					GROUP BY pembayaran_piutang_id
					) tbl_b
				ON tbl_a.id = tbl_b.pembayaran_piutang_id
				LEFT JOIN (
					SELECT sum(amount) as bayar, pembayaran_piutang_id
					FROM nd_pembayaran_piutang_temp_nilai
					GROUP BY pembayaran_piutang_id
					) tbl_c
				ON tbl_a.id = tbl_c.pembayaran_piutang_id
				WHERE  ifnull(bayar,0)+ifnull(pembulatan,0) - amount != 0

			", false);

		return $query->result();
	}


	function get_piutang_list_by_date($tanggal_start, $tanggal_end, $toko_id, $customer_id){
		$query = $this->db->query("SELECT tbl_a.status_aktif, no_faktur_lengkap as no_faktur, tbl_e.nama as customer, customer_id, total as total_jual, amount, ifnull(total,0) - ifnull(amount,0) as sisa_piutang, tbl_a.id as penjualan_id, jatuh_tempo
				FROM (
					SELECT *
					FROM vw_penjualan_data
					WHERE tanggal >= '$tanggal_start'
					AND tanggal <= '$tanggal_end'
					AND customer_id = $customer_id
					AND toko_id = $toko_id
					AND status_aktif = 1
					AND penjualan_type_id != 3
					AND no_faktur != ''
					) as tbl_a
				LEFT JOIN nd_toko as tbl_b
				ON tbl_a.toko_id = tbl_b.id
				LEFT JOIN (
					SELECT id, sum(qty) as qty, sum(jumlah_roll) as jumlah_roll, sum(qty*harga_jual) as total, harga_jual, penjualan_id, barang_id, satuan_id
					FROM nd_penjualan_detail
					LEFT JOIN (
						SELECT sum(qty*if(jumlah_roll=0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, penjualan_detail_id
						FROM nd_penjualan_qty_detail
						GROUP BY penjualan_detail_id
					) nd_penjualan_qty_detail
					ON nd_penjualan_detail.id = nd_penjualan_qty_detail.penjualan_detail_id
					group by penjualan_id
					) as tbl_c
				ON tbl_c.penjualan_id = tbl_a.id
				LEFT JOIN nd_gudang as tbl_d
				ON tbl_a.gudang_id = tbl_d.id
				LEFT JOIN nd_customer as tbl_e
				ON tbl_e.id = tbl_a.customer_id
				LEFT JOIN (
					SELECT sum(amount) as amount, penjualan_id
					FROM nd_pembayaran_piutang_temp_detail
					WHERE data_status = 1
					GROUP BY penjualan_id
					) tbl_f
				ON tbl_a.id = tbl_f.penjualan_id
				WHERE ifnull(total,0) - ifnull(amount,0) > 0
			", false);

		return $query->result();
	}

	function get_piutang_awal_by_date($tanggal_start, $tanggal_end, $toko_id, $customer_id){
		$query = $this->db->query("SELECT 1, no_faktur, nama as customer, customer_id, amount as total_jual, 0 as amount, ifnull(amount,0) - 0 as sisa_piutang, a.id as penjualan_id, jatuh_tempo
				FROM (
					SELECT *, concat('FPJ', DATE_FORMAT(tanggal,'%d%m%y'),'-',$this->pre_faktur,LPAD(no_faktur,5,'0')) as no_faktur_lengkap
					FROM nd_piutang_awal
					WHERE tanggal >= '$tanggal_start'
					AND tanggal <= '$tanggal_end'
					AND customer_id = $customer_id
					AND toko_id = $toko_id
				) a
				LEFT JOIN nd_customer b
				ON a.customer_id = b.id
				LEFT JOIN (
					SELECT sum(amount) as bayar, penjualan_id
					FROM nd_pembayaran_piutang_temp_detail
					WHERE data_status = 2
					GROUP BY penjualan_id
					) c
				ON a.id = c.penjualan_id
				WHERE ifnull(amount,0) - ifnull(bayar,0) > 0
					
			", false);

		return $query->result();
	}


//===============================piutang payment=============================

	function get_customer_bank_bayar_history($customer_id){
		$query = $this->db->query("SELECT nama_bank, no_rek_bank
				FROM nd_pembayaran_piutang_temp_nilai t1
				LEFT JOIN nd_pembayaran_piutang_temp t2
				ON t1.pembayaran_piutang_id = t2.id
				WHERE nama_bank is not null
				AND nama_bank != ''
				AND customer_id = $customer_id
				GROUP BY nama_bank, no_rek_bank, customer_id
				");

		return $query->result();
	}


	function get_pembayaran_piutang($tanggal_start, $tanggal_end, $cond){
		$query = $this->db->query("SELECT a.id, b.nama as nama_customer, c.nama as nama_toko, customer_id, toko_id, pembulatan
				FROM (
					SELECT id, customer_id, toko_id, pembulatan
					FROM (
						(
							SELECT id, customer_id, toko_id, pembulatan
							FROM nd_pembayaran_piutang_temp
							$cond
							AND DATE(created) >= '$tanggal_start'
							AND DATE(created) <= '$tanggal_end'
						)UNION(
							SELECT tbl_b.id, customer_id, toko_id, 0
							FROM (
								SELECT *
								FROM nd_pembayaran_piutang_temp_nilai
								WHERE tanggal_transfer >= '$tanggal_start'
								AND tanggal_transfer <= '$tanggal_end'
								
								)tbl_a
							LEFT JOIN nd_pembayaran_piutang_temp tbl_b
							ON tbl_a.pembayaran_piutang_id = tbl_b.id
							$cond
							GROUP BY pembayaran_piutang_id, toko_id, customer_id
						)
					) a
					GROUP BY id,customer_id,toko_id
				)a
				LEFT JOIN nd_customer b
				ON a.customer_id = b.id
				LEFT JOIN nd_toko c
				ON a.toko_id = c.id
			
			", false);

		return $query->result();
		// return $this->db->last_query();
	}

	function get_periode_penjualan($pembayaran_piutang_id){
		$query = $this->db->query("SELECT MIN(tanggal) as tanggal_start, MAX(tanggal) as tanggal_end
			FROM nd_penjualan
			WHERE id in (
				SELECT penjualan_id
				FROM nd_pembayaran_piutang_temp_detail
				WHERE pembayaran_piutang_id = $pembayaran_piutang_id
				)
			", false);

		return $query->result();
	}

	function get_pembayaran_piutang_data($id){
		$query = $this->db->query("SELECT tbl_a.*, tbl_c.nama as nama_customer, tbl_d.nama as nama_toko, tbl_c.alamat as alamat_customer, tbl_c.kota
			FROM (
				SELECT *
				FROM nd_pembayaran_piutang_temp
				WHERE id = $id
				) tbl_a
			LEFT JOIN nd_customer tbl_c
			ON tbl_a.customer_id = tbl_c.id
			LEFT JOIN nd_toko tbl_d
			ON tbl_a.toko_id = tbl_d.id
			", false);

		return $query->result();
	}

	function get_pembayaran_piutang_awal_detail($id){
		$query = $this->db->query("SELECT tbl_a.*, tbl_b.no_faktur, ifnull(tbl_b.amount,0) - ifnull(total_bayar,0) as sisa_piutang, jatuh_tempo, no_faktur, tbl_b.amount as total_jual, tbl_b.tanggal
			FROM (
				SELECT *
				FROM nd_pembayaran_piutang_temp_detail
				WHERE pembayaran_piutang_id = $id
				AND data_status = 2
				) tbl_a
			LEFT JOIN nd_piutang_awal tbl_b
			ON tbl_a.penjualan_id = tbl_b.id
			LEFT JOIN (
				SELECT sum(amount) as total_bayar, penjualan_id
				FROM nd_pembayaran_piutang_temp_detail
				WHERE pembayaran_piutang_id != $id
				AND data_status = 2
				GROUP BY penjualan_id
				) tbl_c
			ON tbl_c.penjualan_id = tbl_b.id
			", false);

		return $query->result();
	}

	

	function get_pembayaran_piutang_detail($id){
		$query = $this->db->query("SELECT tbl_a.*, tbl_b.no_faktur, ifnull(sisa_piutang,0) - ifnull(total_bayar,0) as sisa_piutang, jatuh_tempo, 
		no_faktur_lengkap as no_faktur, 
		total_jual, tbl_b.tanggal
			FROM (
				SELECT *
				FROM nd_pembayaran_piutang_temp_detail
				WHERE pembayaran_piutang_id = $id
				AND data_status = 1
				) tbl_a
			LEFT JOIN vw_penjualan_data tbl_b
			ON tbl_a.penjualan_id = tbl_b.id
			LEFT JOIN (
				SELECT sum(qty*harga_jual) as sisa_piutang, penjualan_id, sum(qty*harga_jual) as total_jual
				FROM nd_penjualan_detail
				LEFT JOIN (
					SELECT sum(qty*if(jumlah_roll=0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, penjualan_detail_id
					FROM nd_penjualan_qty_detail
					GROUP BY penjualan_detail_id
					) nd_penjualan_qty_detail
				ON nd_penjualan_qty_detail.penjualan_detail_id = nd_penjualan_detail.id
				GROUP BY penjualan_id
				) tbl_c
			ON tbl_a.penjualan_id = tbl_c.penjualan_id
			LEFT JOIN (
				SELECT sum(amount) as total_bayar, penjualan_id
				FROM nd_pembayaran_piutang_temp_detail
				WHERE pembayaran_piutang_id != $id
				AND data_status = 1
				GROUP BY penjualan_id
				) tbl_d
			ON tbl_c.penjualan_id = tbl_d.penjualan_id
			", false);

		return $query->result();
	}

/**
===========================================request barang============================
**/

	function getBulanRequestAwal($request_barang_id)
	{
		$query = $this->db->query("SELECT min(DATE_ADD(bulan_request,interval 1 year)) as bulan_request
			FROM nd_request_barang_qty tA
			LEFT JOIN nd_request_barang_batch tB
			ON tA.request_barang_batch_id = tB.id
			WHERE request_barang_id = $request_barang_id
			AND tB.id is not null
			AND status_aktif = 1
			GROUP BY request_barang_id
			", false);

		return $query->result();
	}

	function get_outstanding_barang_for_request($request_barang_id, $barang_id, $tanggal){
		$query = $this->db->query("SELECT t1.barang_id, t1.warna_id, 
		sum(if(po_qty - ifnull(qty,0) - ifnull(qty_before,0) > 0 , po_qty - ifnull(qty,0) - ifnull(qty_before,0) ,0))as qty_outstanding, 
		group_concat(if(po_qty - ifnull(qty,0) - ifnull(qty_before,0) > 0 , po_qty - ifnull(qty,0) - ifnull(qty_before,0) ,0)  order by batch_tanggal,batch asc) as qty_data, 
		group_concat(po_qty order by batch_tanggal,batch asc) as po_qty, 
		group_concat(po_number order by batch_tanggal,batch asc  SEPARATOR '??' ) as po_number, 
		group_concat(batch_tanggal  order by batch_tanggal,batch asc) as batch_tanggal,
		group_concat(t1.po_pembelian_batch_id order by batch_tanggal,batch asc) as po_pembelian_batch_id
		FROM (
			SELECT barang_id, warna_id, sum(qty) as po_qty, po_pembelian_batch_id, 
				concat(if(tB.tanggal >= '2019-09-27', concat(ifnull(pre_po,''),tD.kode,'/',DATE_FORMAT(tB.tanggal,'%y'),'/',LPAD(po_number,3,'0')) ,concat(LPAD(po_number,3,'0'),'/',DATE_FORMAT(tB.tanggal,'%m'),'/',DATE_FORMAT(tB.tanggal,'%y'))),'-',batch,if(revisi > 1,concat('R',revisi-1),'') ) as po_number, 
				batch_tanggal, batch, po_pembelian_warna_id, po_pembelian_id
			FROM (
				SELECT if(t1.tipe_barang!=1,barang_id_baru,barang_id) as barang_id, warna_id, po_pembelian_batch_id, 
					t1.locked_by, t1.qty, t2.po_pembelian_id, batch, revisi, t2.tanggal as batch_tanggal, t1.id as po_pembelian_warna_id
				FROM nd_po_pembelian_warna t1
				LEFT JOIN nd_po_pembelian_batch t2
				ON t1.po_pembelian_batch_id = t2.id
				LEFT JOIN nd_po_pembelian_detail t3
				ON t1.po_pembelian_detail_id = t3.id
				WHERE t2.status != 0
				AND (
					t1.locked_date is null
					OR t1.locked_date >= '$tanggal'
				)
				)tA
			LEFT JOIN (
				SELECT *
				FROM nd_po_pembelian
				) tB
			ON tA.po_pembelian_id = tB.id
			LEFT JOIN nd_toko tC
			ON tB.toko_id = tC.id
			LEFT JOIN nd_supplier tD
			ON tB.supplier_id=tD.id
			WHERE barang_id = $barang_id
			AND tB.status_aktif = 1
			GROUP BY barang_id, warna_id, po_pembelian_batch_id
			)t1
		LEFT JOIN (
			SELECT barang_id, warna_id, sum(qty) as qty, po_pembelian_batch_id
			FROM nd_pembelian_detail tA
			LEFT JOIN (
				SELECT *
				FROM nd_pembelian
				WHERE tanggal <= '$tanggal'
				AND status_aktif = 1
				AND po_pembelian_batch_id is not null
				)tB
			ON tA.pembelian_id = tB.id
			WHERE barang_id = $barang_id
			GROUP BY barang_id, warna_id, po_pembelian_batch_id
			) t2
		ON t1.po_pembelian_batch_id = t2.po_pembelian_batch_id
		AND t1.barang_id = t2.barang_id
		AND t1.warna_id = t2.warna_id
		LEFT JOIN (
			SELECT po_pembelian_id, po_pembelian_batch_id, po_pembelian_warna_id, qty as qty_before
			FROM nd_po_pembelian_before_qty
		) t3
		ON t1.po_pembelian_id = t3.po_pembelian_id
		AND t1.po_pembelian_batch_id = t3.po_pembelian_batch_id
		AND t1.po_pembelian_warna_id = t3.po_pembelian_warna_id
		GROUP BY t1.barang_id, t1.warna_id
		");
		return $query->result();		
	}

	function get_outstanding_barang_for_request_2($request_barang_id, $barang_id, $tanggal){
		$query = $this->db->query("SELECT t1.barang_id, t1.warna_id, 
		sum(if(po_qty - ifnull(qty,0) - ifnull(qty_before,0) > 0 , po_qty - ifnull(qty,0) - ifnull(qty_before,0) ,0))as qty_outstanding, 
		group_concat(if(po_qty - ifnull(qty,0) - ifnull(qty_before,0) > 0 , po_qty - ifnull(qty,0) - ifnull(qty_before,0) ,0)  order by batch_tanggal,batch asc) as qty_data, 
		group_concat(po_qty order by batch_tanggal,batch asc) as po_qty, 
		group_concat(po_number order by batch_tanggal,batch asc  SEPARATOR '??' ) as po_number, 
		group_concat(batch_tanggal  order by batch_tanggal,batch asc) as batch_tanggal,
		group_concat(t1.po_pembelian_batch_id order by batch_tanggal,batch asc) as po_pembelian_batch_id
		FROM (
			SELECT barang_id, warna_id, sum(qty) as po_qty, po_pembelian_batch_id, 
				concat(if(tB.tanggal >= '2019-09-27', concat(ifnull(pre_po,''),tD.kode,'/',DATE_FORMAT(tB.tanggal,'%y'),'/',LPAD(po_number,3,'0')) ,concat(LPAD(po_number,3,'0'),'/',DATE_FORMAT(tB.tanggal,'%m'),'/',DATE_FORMAT(tB.tanggal,'%y'))),'-',batch,if(revisi > 1,concat('R',revisi-1),'') ) as po_number, 
				batch_tanggal, batch, po_pembelian_warna_id, po_pembelian_id
			FROM (
				SELECT *
				FROM (
					SELECT if(t1.tipe_barang!=1,barang_id_baru,barang_id) as barang_id, warna_id, po_pembelian_batch_id, 
						t1.locked_by, t1.qty, t2.po_pembelian_id, batch, revisi, t2.tanggal as batch_tanggal, t1.id as po_pembelian_warna_id, t1.locked_date
					FROM nd_po_pembelian_warna t1
					LEFT JOIN nd_po_pembelian_batch t2
					ON t1.po_pembelian_batch_id = t2.id
					LEFT JOIN nd_po_pembelian_detail t3
					ON t1.po_pembelian_detail_id = t3.id
					WHERE t2.status != 0
				)result
				WHERE barang_id = $barang_id
			)tA
			LEFT JOIN (
				SELECT *
				FROM nd_po_pembelian
			) tB
			ON tA.po_pembelian_id = tB.id
			LEFT JOIN (
				SELECT barang_id as bidr, warna_id as widr, min(tanggal) as tanggal_request 
				FROM nd_request_barang_qty t1
				LEFT JOIN nd_request_barang_batch t2
				ON t1.request_barang_batch_id = t2.id
				WHERE request_barang_id = '$request_barang_id'
				GROUP BY barang_id, warna_id
			) tR
			ON tA.barang_id = tR.bidr
			AND tA.warna_id = tR.widr
			LEFT JOIN nd_toko tC
			ON tB.toko_id = tC.id
			LEFT JOIN nd_supplier tD
			ON tB.supplier_id=tD.id
			WHERE tB.status_aktif = 1
			AND (
				tA.locked_date is null
				OR tA.locked_date >= ifnull(tanggal_request,'$tanggal')
			)
			GROUP BY barang_id, warna_id, po_pembelian_batch_id
			)t1
		LEFT JOIN (
			SELECT barang_id, warna_id, sum(qty) as qty, po_pembelian_batch_id
			FROM (
				SELECT barang_id, warna_id, qty, po_pembelian_batch_id, tanggal
				FROM nd_pembelian_detail tA
				LEFT JOIN (
					SELECT *
					FROM nd_pembelian
					WHERE status_aktif = 1
					AND po_pembelian_batch_id is not null
					)tB
				ON tA.pembelian_id = tB.id
				WHERE barang_id = $barang_id
			)result 
			LEFT JOIN (
				SELECT barang_id as bidr, warna_id as widr, min(tanggal) as tanggal_request 
				FROM nd_request_barang_qty t1
				LEFT JOIN nd_request_barang_batch t2
				ON t1.request_barang_batch_id = t2.id
				WHERE request_barang_id = '$request_barang_id'
				GROUP BY barang_id, warna_id
			) tR
			ON result.barang_id = tR.bidr
			AND result.warna_id = tR.widr
			WHERE result.tanggal < ifnull(tanggal_request, '$tanggal') 
			GROUP BY barang_id, warna_id, po_pembelian_batch_id
		) t2
		ON t1.po_pembelian_batch_id = t2.po_pembelian_batch_id
		AND t1.barang_id = t2.barang_id
		AND t1.warna_id = t2.warna_id
		LEFT JOIN (
			SELECT po_pembelian_id, po_pembelian_batch_id, po_pembelian_warna_id, qty as qty_before
			FROM nd_po_pembelian_before_qty
		) t3
		ON t1.po_pembelian_id = t3.po_pembelian_id
		AND t1.po_pembelian_batch_id = t3.po_pembelian_batch_id
		AND t1.po_pembelian_warna_id = t3.po_pembelian_warna_id
		GROUP BY t1.barang_id, t1.warna_id
		");
		return $query->result();		
	}

	function get_request_barang_data($batch_id){
		$query = $this->db->query("SELECT t1.id as id, t1.tanggal as tanggal, batch, no_request,  
				t2.tanggal as tanggal_request, supplier_id, t4.nama as nama_supplier, 
				concat(pre_po,'-', DATE_FORMAT(t2.tanggal, '%Y'),'/31/', LPAD(no_request,3,'0'),'-',batch) as no_request_lengkap, 
				locked_by, locked_date, request_barang_id, attn, closed_date, tanggal_awal
				FROM (
					SELECT *
					FROM nd_request_barang_batch
					WHERE id = $batch_id
				) t1
				LEFT JOIN (
					SELECT tA.*, tB.tanggal as tanggal_awal
					FROM nd_request_barang tA
					LEFT JOIN (
						SELECT *
						FROM nd_request_barang_batch
						WHERE batch = 1
						AND status_aktif = 1
					)tB
					ON tA.id = tB.request_barang_id
					) t2
				ON t1.request_barang_id = t2.id
				LEFT JOIN nd_toko t3
				ON t2.toko_id = t3.id
				LEFT JOIN nd_supplier t4
				ON t2.supplier_id = t4.id
				");

		return $query->result();
	}

	function get_active_request_barang($batch_id, $no_request, $tahun){
		$this->db->simple_query('SET SESSION group_concat_max_len=15000');

		$query = $this->db->query("SELECT t1.*, group_concat(request_barang_detail_id SEPARATOR '??') as request_barang_detail_id, group_concat(barang_id SEPARATOR '??') as barang_id, group_concat(warna_id SEPARATOR '??') as warna_id, group_concat(qty SEPARATOR '??') as qty, group_concat(po_number SEPARATOR '??') as po_number, group_concat(nama_barang SEPARATOR '??') as nama_barang, group_concat(nama_warna SEPARATOR '??') as nama_warna, group_concat(po_pembelian_batch_id SEPARATOR '??') as po_pembelian_batch_id, bulan_request
				FROM (
					SELECT request_barang_batch_id, request_barang_id, barang_id, nama_barang, 
						group_concat(warna_id ORDER BY warna_beli) as warna_id, 
						group_concat(warna_beli ORDER BY warna_beli) as nama_warna, 
						group_concat(po_pembelian_batch_id ORDER BY warna_beli) as po_pembelian_batch_id, 
						group_concat(qty ORDER BY warna_beli) as qty, 
						group_concat(po_number ORDER BY warna_beli) as po_number, 
						group_concat(id ORDER BY warna_beli) as request_barang_detail_id,  bulan_request
					FROM (
						SELECT tA.id as id, if(tA.po_pembelian_batch_id != 0 ,po_number, 'PO BARU') as po_number, nama as nama_barang, warna_beli, request_barang_id, qty, tA.po_pembelian_batch_id, barang_id, warna_id, bulan_request, tAA.id as request_barang_batch_id
						FROM (
							SELECT *
							FROM nd_request_barang_batch
							WHERE id= $batch_id
							)t0
						LEFT JOIN nd_request_barang_detail tA
						ON tA.request_barang_batch_id = t0.id
						LEFT JOIN (
							SELECT t1.id, concat(if(t2.tanggal >= '2019-09-27', concat(ifnull(pre_po,''),t4.kode,'/',DATE_FORMAT(t2.tanggal,'%y'),'/',LPAD(po_number,3,'0')) ,concat(LPAD(po_number,3,'0'),'/',DATE_FORMAT(t2.tanggal,'%m'),'/',DATE_FORMAT(t2.tanggal,'%y'))),'-',batch,if(revisi > 1,concat('R',revisi-1),'')) as po_number
							FROM nd_po_pembelian_batch t1
							LEFT JOIN nd_po_pembelian t2
							ON t1.po_pembelian_id = t2.id
							LEFT JOIN nd_toko t3
							ON t2.toko_id = t3.id
							LEFT JOIN nd_supplier t4
							ON t2.supplier_id = t4.id
							) tB
						ON tA.po_pembelian_batch_id = tB.id
						LEFT JOIN nd_barang
						ON tA.barang_id = nd_barang.id
						LEFT JOIN nd_warna
						ON tA.warna_id = nd_warna.id
						WHERE tA.qty > 0
						ORDER bY nd_barang.nama ASC
						)result
					GROUP BY request_barang_id, barang_id, bulan_request
					) t2
				LEFT JOIN(
					SELECT tA.*, tB.tanggal as tanggal_request, supplier_id
					FROM (
						SELECT *
						FROM nd_request_barang_batch
						WHERE id=$batch_id
						)tA
					LEFT JOIN nd_request_barang tB
					ON tA.request_barang_id = tB.id
					)t1
				ON t1.id = t2.request_barang_id
				LEFT JOIN (
					SELECT id, request_barang_batch_id, barang_id as barang_id_request, warna_id as warna_id_request, qty as qty_request, bulan_request as bulan_request_qty
					FROM nd_request_barang_qty
					WHERE request_barang_batch_id = $batch_id
					) t3
				ON t1.id = t3.request_barang_batch_id
				AND t2.barang_id = t3.barang_id_request
				AND t2.warna_id = t3.warna_id_request
				AND t2.bulan_request = t3.bulan_request_qty
				WHERE t2.barang_id is not null
				GROUP BY bulan_request
		");
		return $query->result();			
	}

	function get_active_request_barang_by_request($request_barang_id, $request_barang_batch_id){
		$this->db->simple_query('SET SESSION group_concat_max_len=15000');
		$latest_date = date('Y-12-31');

		$query = $this->db->query("SELECT t1.*, 
				group_concat(request_barang_detail_id ORDER BY nama_barang  SEPARATOR '??') as request_barang_detail_id, 
				group_concat(barang_id ORDER BY nama_barang ASC SEPARATOR '??') as barang_id, 
				group_concat(warna_id ORDER BY nama_barang ASC SEPARATOR '??') as warna_id, 
				group_concat(qty ORDER BY nama_barang ASC SEPARATOR '??') as qty, 
				group_concat(po_number ORDER BY nama_barang ASC SEPARATOR '??') as po_number, 
				group_concat(nama_barang ORDER BY nama_barang ASC SEPARATOR '??') as nama_barang, 
				group_concat(nama_warna ORDER BY nama_barang ASC SEPARATOR '??') as nama_warna, 
				group_concat(po_pembelian_batch_id ORDER BY nama_barang ASC SEPARATOR '??') as po_pembelian_batch_id, 
				group_concat(status_urgent ORDER BY nama_barang SEPARATOR '??') as status_urgent, 
				group_concat(qty_total ORDER BY nama_barang ) as qty_total, bulan_request
				FROM (
					SELECT request_barang_batch_id, request_barang_id, barang_id, nama_barang, 
						group_concat(warna_id ORDER BY warna_beli, tanggal_po_batch asc) as warna_id, 
						group_concat(warna_beli ORDER BY warna_beli, tanggal_po_batch asc) as nama_warna, 
						group_concat(po_pembelian_batch_id ORDER BY warna_beli, tanggal_po_batch asc) as po_pembelian_batch_id, 
						group_concat(qty ORDER BY warna_beli, tanggal_po_batch asc) as qty, 
						group_concat(ifnull(po_number,'deleted') ORDER BY warna_beli, tanggal_po_batch asc) as po_number, 
						group_concat(id ORDER BY warna_beli, tanggal_po_batch asc) as request_barang_detail_id, 
						group_concat(status_urgent ORDER BY warna_beli, tanggal_po_batch asc) as status_urgent, 
						bulan_request, sum(qty) as qty_total
					FROM (
						SELECT tA.id as id, if(tA.po_pembelian_batch_id != 0 ,po_number, 'PO BARU') as po_number, nama as nama_barang, warna_beli, request_barang_id, qty, tA.po_pembelian_batch_id, barang_id, warna_id, bulan_request, tA.request_barang_batch_id, tanggal, if(tA.po_pembelian_batch_id != 0 ,tanggal_po_batch, '$latest_date') as tanggal_po_batch, status_urgent
						FROM (
							SELECT tX.*
							FROM (
								SELECT tJ.*, tanggal, request_barang_id
								FROM nd_request_barang_detail tJ
								LEFT JOIN (
									SELECT *
									FROM nd_request_barang_batch
									WHERE request_barang_id= $request_barang_id
									AND status_aktif = 1
									)tK
								ON tJ.request_barang_batch_id = tK.id
								WHERE tK.id is not null
							)tX
							LEFT JOIN (
								SELECT tJ.*, tanggal, request_barang_id
								FROM nd_request_barang_detail tJ
								LEFT JOIN (
									SELECT *
									FROM nd_request_barang_batch
									WHERE request_barang_id= $request_barang_id
									AND status_aktif = 1
									)tK
								ON tJ.request_barang_batch_id = tK.id
								WHERE tK.id is not null
							) tY
							ON tX.barang_id = tY.barang_id
							AND tX.warna_id = tY.warna_id
							AND tX.bulan_request = tY.bulan_request
							AND tX.tanggal < tY.tanggal
							WHERE tY.id is null
						) tA
						LEFT JOIN (
							SELECT t1.id, concat(if(t2.tanggal >= '2019-09-27', concat(ifnull(pre_po,''),t4.kode,'/',DATE_FORMAT(t2.tanggal,'%y'),'/',LPAD(po_number,3,'0')) ,concat(LPAD(po_number,3,'0'),'/',DATE_FORMAT(t2.tanggal,'%m'),'/',DATE_FORMAT(t2.tanggal,'%y'))),'-',batch,if(revisi > 1,concat('R',revisi-1),'')) as po_number, t1.tanggal as tanggal_po_batch
							FROM nd_po_pembelian_batch t1
							LEFT JOIN nd_po_pembelian t2
							ON t1.po_pembelian_id = t2.id
							LEFT JOIN nd_toko t3
							ON t2.toko_id = t3.id
							LEFT JOIN nd_supplier t4
							ON t2.supplier_id = t4.id
							) tB
						ON tA.po_pembelian_batch_id = tB.id
						LEFT JOIN nd_barang
						ON tA.barang_id = nd_barang.id
						LEFT JOIN nd_warna
						ON tA.warna_id = nd_warna.id
						WHERE tA.qty > 0
						ORDER bY nd_barang.nama, warna_beli, bulan_request ASC
						)result
					GROUP BY request_barang_id, barang_id, bulan_request
					) t2
				LEFT JOIN(
					SELECT tA.*, tB.tanggal as tanggal_request, supplier_id
					FROM (
						SELECT *
						FROM nd_request_barang_batch
						WHERE id=$request_barang_batch_id
						)tA
					LEFT JOIN nd_request_barang tB
					ON tA.request_barang_id = tB.id
					)t1
				ON t1.id = t2.request_barang_id
				WHERE t2.barang_id is not null
				GROUP BY bulan_request
				ORDER BY bulan_request ASC
		");
		return $query->result();			
	}

	function get_active_request_barang_by_request_2($request_barang_id, $request_barang_batch_id){
		$this->db->simple_query('SET SESSION group_concat_max_len=15000');
		$latest_date = date('Y-12-31');

		$query = $this->db->query("SELECT t1.*, 
				group_concat(request_barang_detail_id ORDER BY nama_barang  SEPARATOR '??') as request_barang_detail_id, 
				group_concat(barang_id ORDER BY nama_barang ASC SEPARATOR '??') as barang_id, 
				group_concat(warna_id ORDER BY nama_barang ASC SEPARATOR '??') as warna_id, 
				group_concat(qty ORDER BY nama_barang ASC SEPARATOR '??') as qty, 
				group_concat(po_number ORDER BY nama_barang ASC SEPARATOR '??') as po_number, 
				group_concat(nama_barang ORDER BY nama_barang ASC SEPARATOR '??') as nama_barang, 
				group_concat(nama_warna ORDER BY nama_barang ASC SEPARATOR '??') as nama_warna, 
				group_concat(po_pembelian_batch_id ORDER BY nama_barang ASC SEPARATOR '??') as po_pembelian_batch_id, 
				group_concat(status_urgent ORDER BY nama_barang SEPARATOR '??') as status_urgent, 
				group_concat(qty_total ORDER BY nama_barang ) as qty_total, bulan_request
				FROM (
					SELECT rbid as request_barang_batch_id, rid as request_barang_id, bid as barang_id, nama as nama_barang, 
						group_concat(wid ORDER BY warna_beli, tanggal_po_batch asc) as warna_id, 
						group_concat(warna_beli ORDER BY warna_beli, tanggal_po_batch asc) as nama_warna, 
						group_concat(ifnull(po_pembelian_batch_id,0) ORDER BY warna_beli, tanggal_po_batch asc) as po_pembelian_batch_id, 
						group_concat(ifnull(qty,qty_r) ORDER BY warna_beli, tanggal_po_batch asc) as qty, 
						group_concat(ifnull(po_number, if(qty_r = 0,'-','deleted') ) ORDER BY warna_beli, tanggal_po_batch asc) as po_number, 
						group_concat(ifnull(result.id,0) ORDER BY warna_beli, tanggal_po_batch asc) as request_barang_detail_id, 
						group_concat(ifnull(status_urgent,0) ORDER BY warna_beli, tanggal_po_batch asc) as status_urgent, 
						breq as bulan_request, sum(ifnull(qty,qty_r)) as qty_total
						FROM (
							SELECT barang_id as bid, warna_id as wid, request_barang_batch_id as rbid, request_barang_id as rid, qty as qty_r, bulan_request as breq
							FROM (
								SELECT *
								FROM nd_request_barang_qty
								WHERE id IN (
									SELECT max(tA.id)
									FROM nd_request_barang_qty tA
									LEFT JOIN nd_request_barang_batch tB
									ON tA.request_barang_batch_id = tB.id
									WHERE request_barang_id = $request_barang_id
									AND status_aktif = 1
									GROUP BY barang_id, warna_id, bulan_request
								)
							) tA
							LEFT JOIN nd_request_barang_batch tB
							ON tA.request_barang_batch_id = tB.id
						) rs
						LEFT JOIN (
							SELECT tA.id as id, if(tA.po_pembelian_batch_id != 0 ,po_number, 'PO BARU') as po_number, request_barang_id, qty, tA.po_pembelian_batch_id, barang_id, warna_id, bulan_request, tA.request_barang_batch_id, tanggal, if(tA.po_pembelian_batch_id != 0 ,tanggal_po_batch, '$latest_date') as tanggal_po_batch, status_urgent
							FROM (
								SELECT tX.*
								FROM (
									SELECT tJ.*, tanggal, request_barang_id
									FROM nd_request_barang_detail tJ
									LEFT JOIN (
										SELECT *
										FROM nd_request_barang_batch
										WHERE request_barang_id= $request_barang_id
										AND status_aktif = 1
										)tK
									ON tJ.request_barang_batch_id = tK.id
									WHERE tK.id is not null
								)tX
								LEFT JOIN (
									SELECT tJ.*, tanggal, request_barang_id
									FROM nd_request_barang_detail tJ
									LEFT JOIN (
										SELECT *
										FROM nd_request_barang_batch
										WHERE request_barang_id= $request_barang_id
										AND status_aktif = 1
										)tK
									ON tJ.request_barang_batch_id = tK.id
									WHERE tK.id is not null
								) tY
								ON tX.barang_id = tY.barang_id
								AND tX.warna_id = tY.warna_id
								AND tX.bulan_request = tY.bulan_request
								AND tX.tanggal < tY.tanggal
								WHERE tY.id is null
							) tA
							LEFT JOIN (
								SELECT t1.id, concat(if(t2.tanggal >= '2019-09-27', concat(ifnull(pre_po,''),t4.kode,'/',DATE_FORMAT(t2.tanggal,'%y'),'/',LPAD(po_number,3,'0')) ,concat(LPAD(po_number,3,'0'),'/',DATE_FORMAT(t2.tanggal,'%m'),'/',DATE_FORMAT(t2.tanggal,'%y'))),'-',batch,if(revisi > 1,concat('R',revisi-1),'')) as po_number, t1.tanggal as tanggal_po_batch
								FROM nd_po_pembelian_batch t1
								LEFT JOIN nd_po_pembelian t2
								ON t1.po_pembelian_id = t2.id
								LEFT JOIN nd_toko t3
								ON t2.toko_id = t3.id
								LEFT JOIN nd_supplier t4
								ON t2.supplier_id = t4.id
								) tB
							ON tA.po_pembelian_batch_id = tB.id
							WHERE tA.qty > 0
						)result
						ON rs.bid = result.barang_id
						AND rs.wid = result.warna_id
						AND rs.rbid = result.request_barang_batch_id
						AND rs.breq = result.bulan_request
						LEFT JOIN nd_barang
						ON rs.bid = nd_barang.id
						LEFT JOIN nd_warna
						ON rs.wid = nd_warna.id
						GROUP BY rid, bid, breq
                        ORDER BY nd_barang.nama, warna_beli, breq ASC

					) t2
				LEFT JOIN(
					SELECT tA.*, tB.tanggal as tanggal_request, supplier_id
					FROM (
						SELECT *
						FROM nd_request_barang_batch
						WHERE id=$request_barang_batch_id
						)tA
					LEFT JOIN nd_request_barang tB
					ON tA.request_barang_id = tB.id
					)t1
				ON t1.id = t2.request_barang_id
				WHERE t2.barang_id is not null
				GROUP BY bulan_request
				ORDER BY bulan_request ASC
		");
		if (is_posisi_id() == 1) {
			# code...
		}else{
			
		}
		return $query->result();			
	}

	function get_request_barang_qty($barang_id, $request_barang_batch_id, $request_barang_id){
		$query = $this->db->query("SELECT tJ.* 
					FROM (
						SELECT tA.id, request_barang_batch_id, barang_id , warna_id , qty, bulan_request, tanggal
						FROM (
							SELECT *
							FROM nd_request_barang_qty
							WHERE request_barang_batch_id = $request_barang_batch_id
						) tA
						LEFT JOIN (
							SELECT *
							FROM nd_request_barang_batch
							WHERE request_barang_id = $request_barang_id
							AND status_aktif = 1
						)tB
						ON tA.request_barang_batch_id = tB.id
						WHERE tB.id is not null
					)tJ
					LEFT JOIN (
						SELECT  tA.id, request_barang_batch_id, barang_id , warna_id , qty, bulan_request, tanggal
						FROM nd_request_barang_qty tA
						LEFT JOIN (
							SELECT *
							FROM nd_request_barang_batch
							WHERE request_barang_id = $request_barang_id
							AND status_aktif = 1
						)tB
						ON tA.request_barang_batch_id = tB.id
						WHERE tB.id is not null
					)tK
					ON tJ.barang_id = tK.barang_id
					AND tJ.warna_id = tK.warna_id
					AND tJ.bulan_request = tK.bulan_request
					AND tJ.tanggal < tK.tanggal
					WHERE tK.id is null
		");
		return $query->result();	
	}

	function get_request_barang_qty2($barang_id, $request_barang_batch_id, $request_barang_id){
		$query = $this->db->query("SELECT *
			FROM (
				(
					SELECT tA.id, request_barang_batch_id, barang_id , warna_id , qty, bulan_request, tanggal
					FROM (
						SELECT *
						FROM nd_request_barang_qty
						WHERE request_barang_batch_id = $request_barang_batch_id
					) tA
					LEFT JOIN (
						SELECT *
						FROM nd_request_barang_batch
						WHERE request_barang_id = $request_barang_id
						AND status_aktif = 1
					)tB
					ON tA.request_barang_batch_id = tB.id
					WHERE tB.id is not null
				)UNION(
					SELECT tA.id, tA.request_barang_batch_id, tA.barang_id , tA.warna_id , tA.qty, tA.bulan_request, tanggal
					FROM (
						SELECT *
						FROM nd_request_barang_qty
						WHERE request_barang_batch_id != $request_barang_batch_id
					) tA
					LEFT JOIN (
						SELECT *
						FROM nd_request_barang_batch
						WHERE request_barang_id = $request_barang_id
						AND status_aktif = 1
					)tB
					ON tA.request_barang_batch_id = tB.id
					LEFT JOIN (
						SELECT *
						FROM nd_request_barang_qty
						WHERE request_barang_batch_id = $request_barang_batch_id
					)tC
					ON tA.barang_id = tC.barang_id
					AND tA.warna_id = tC.warna_id
					WHERE tB.id is not null
					AND tC.barang_id is null
					AND tC.warna_id is null
				)
			)result
		");
		return $query->result();	
	}

	function get_request_barang_qty_aktif($request_barang_batch_id, $request_barang_id){
		$query = $this->db->query("SELECT t1.bulan_request, t1.barang_id, t1.warna_id, t1.qty, sum(ifnull(t2.qty,0)) as qty_before, 
				nama as nama_barang, warna_jual as nama_warna, 
				if(t1.qty != ifnull(t2.qty,0) , 1, 0) as tipe
					FROM (
						SELECT tA.*, batch
						FROM (
							SELECT bulan_request, barang_id, warna_id, qty, request_barang_batch_id
							FROM nd_request_barang_qty
							WHERE request_barang_batch_id = $request_barang_batch_id
							)tA
							LEFT JOIN nd_request_barang_batch tB
							ON tA.request_barang_batch_id = tB.id
					)t1
					LEFT JOIN (
						SELECT tA.*, batch
						FROM nd_request_barang_qty tA
						LEFT JOIN nd_request_barang_batch tB
						ON tA.request_barang_batch_id = tB.id
						WHERE request_barang_id = $request_barang_id
						AND request_barang_batch_id != $request_barang_batch_id
						AND status_aktif = 1
					) t2
					ON t1.barang_id = t2.barang_id
					ANd t1.warna_id = t2.warna_id
					AND t1.batch > t2.batch
					AND t1.bulan_request = t2.bulan_request
					LEFT JOIN nd_barang 
					ON t1.barang_id = nd_barang.id
					LEFT JOIN nd_warna
					ON t1.warna_id = nd_warna.id
					GROUP BY bulan_request, barang_id, warna_id
		");
		return $query->result();	
	}

	function get_request_barang_qty_status($request_barang_id){
		$query = $this->db->query("SELECT t1.*, username, DATE_ADD(t1.bulan_request, INTERVAL 1 YEAR) as bln_request
					FROM (
						SELECT tA.*, batch 
						FROM nd_request_barang_qty tA
						LEFT JOIN (
								SELECT *
								FROM nd_request_barang_batch
								WHERE request_barang_id = $request_barang_id
								AND status_aktif = 1
						)tB
						ON tA.request_barang_batch_id = tB.id
						WHERE tB.id is not null
					)t1
					LEFT JOIN (
						SELECT tA.*, batch 
						FROM nd_request_barang_qty tA
						LEFT JOIN (
								SELECT *
								FROM nd_request_barang_batch
								WHERE request_barang_id = $request_barang_id
								AND status_aktif = 1
						)tB
						ON tA.request_barang_batch_id = tB.id
						WHERE tB.id is not null
					)t2
					ON t1.id = t2.id
					AND t1.batch > t2.batch
					LEFT JOIN nd_user
					ON t1.finished_by = nd_user.id
					WHERE t2.id is null
		");
		return $query->result();	
	}

	function get_request_barang_terkirim($request_barang_id, $closed_date){
		$this->db->simple_query('SET SESSION group_concat_max_len=15000');
		$query = $this->db->query("SELECT t1.barang_id, t1.warna_id, tanggal, tanggal_masuk, po_pembelian_batch_id as po_pembelian_batch_id, 
			qty as qty_masuk,qty_request, bulan_request, latest, qty_data
			FROM (
				SELECT barang_id, warna_id, min(tanggal) as tanggal, max(DATE_FORMAT(bulan_request,'%Y-%m-31')) as latest, 
					group_concat(DATE_ADD(bulan_request,interval 1 year) order by bulan_request asc) as bulan_request, 
					group_concat(qty  order by bulan_request asc) as qty_request
				FROM  (
					SELECT *
					FROM nd_request_barang_qty
					WHERE id IN (
						SELECT max(tA.id)
						FROM nd_request_barang_qty tA
						LEFT JOIN nd_request_barang_batch tB
						ON tA.request_barang_batch_id = tB.id
						WHERE request_barang_id = $request_barang_id
						AND status_aktif = 1
						GROUP BY barang_id, warna_id, bulan_request
					)
				) t1
				LEFT JOIN nd_request_barang_batch t2
				ON t1.request_barang_batch_id = t2.id
				WHERE request_barang_id = $request_barang_id
				GROUP BY barang_id, warna_id
				)t1
			LEFT JOIN (
				SELECT group_concat(qty) as qty_data, sum(qty) as qty, t1.barang_id, t1.warna_id, 
				group_concat(DATE_FORMAT(created_at, '%Y-%m-%d')) as tanggal_masuk, 
				group_concat(po_pembelian_batch_id) as po_pembelian_batch_id
				FROM nd_pembelian_detail t1
				LEFT JOIN nd_pembelian t2
				ON t1.pembelian_id = t2.id
				LEFT JOIN (
					SELECT barang_id, warna_id, concat(min(tanggal),' 00:00:00') as tanggal_request,'$closed_date' as latest, 
						concat(min( if(tanggal > brq, tanggal, brq)), ' 00:00:00')  as tanggal_awal
					FROM (
						SELECT *, DATE_ADD(bulan_request, INTERVAL 1 YEAR) as brq
						FROM nd_request_barang_qty
						WHERE id IN (
							SELECT min(tX.id)
							FROM nd_request_barang_qty tX
							LEFT JOIN nd_request_barang_batch tY
							ON tX.request_barang_batch_id = tY.id
							WHERE request_barang_id = $request_barang_id
							AND status_aktif = 1
							GROUP BY barang_id, warna_id
						)
					)tA
					LEFT JOIN nd_request_barang_batch tB
					ON tB.id = tA.request_barang_batch_id
					GROUP BY barang_id, warna_id
				)t3
				ON t1.barang_id = t3.barang_id
				AND t1.warna_id = t3.warna_id
				AND t2.created_at >= t3.tanggal_awal
				AND t2.created_at <= t3.latest
				WHERE t3.tanggal_request is not null
				GROUP BY barang_id, warna_id
			)t2
			ON t1.barang_id = t2.barang_id
			AND t1.warna_id = t2.warna_id
			");

		return $query->result();
	}

	function get_request_barang_terkirim_2($request_barang_id, $closed_date){
		$this->db->simple_query('SET SESSION group_concat_max_len=15000');
		$query = $this->db->query("SELECT t1.barang_id, t1.warna_id, tanggal, tanggal_masuk, po_pembelian_batch_id as po_pembelian_batch_id, 
			qty as qty_masuk,qty_request, bulan_request, latest, qty_data
			FROM (
				SELECT barang_id, warna_id, min(tanggal) as tanggal, max(DATE_FORMAT(bulan_request,'%Y-%m-31')) as latest, 
					group_concat(DATE_ADD(bulan_request,interval 1 year) order by bulan_request asc) as bulan_request, 
					group_concat(qty  order by bulan_request asc) as qty_request
				FROM  (
					SELECT *
					FROM nd_request_barang_qty
					WHERE id IN (
						SELECT max(tA.id)
						FROM nd_request_barang_qty tA
						LEFT JOIN nd_request_barang_batch tB
						ON tA.request_barang_batch_id = tB.id
						WHERE request_barang_id = $request_barang_id
						AND status_aktif = 1
						GROUP BY barang_id, warna_id, bulan_request
					)
				) t1
				LEFT JOIN nd_request_barang_batch t2
				ON t1.request_barang_batch_id = t2.id
				WHERE request_barang_id = $request_barang_id
				GROUP BY barang_id, warna_id
				)t1
			LEFT JOIN (
				SELECT group_concat(qty) as qty_data, sum(qty) as qty, t1.barang_id, t1.warna_id, 
				group_concat(DATE_FORMAT(t2.tanggal, '%Y-%m-%d')) as tanggal_masuk, 
				group_concat(po_pembelian_batch_id) as po_pembelian_batch_id
				FROM nd_pembelian_detail t1
				LEFT JOIN nd_pembelian t2
				ON t1.pembelian_id = t2.id
				LEFT JOIN (
					SELECT barang_id, warna_id, concat(min(tanggal),' 00:00:00') as tanggal_request,'$closed_date' as latest, 
						concat(min(brq), ' 00:00:00')  as tanggal_awal
					FROM (
						SELECT *, DATE_ADD(bulan_request, INTERVAL 1 YEAR) as brq
						FROM nd_request_barang_qty
						WHERE id IN (
							SELECT min(tX.id)
							FROM nd_request_barang_qty tX
							LEFT JOIN nd_request_barang_batch tY
							ON tX.request_barang_batch_id = tY.id
							WHERE request_barang_id = $request_barang_id
							AND status_aktif = 1
							GROUP BY barang_id, warna_id
						)
					)tA
					LEFT JOIN nd_request_barang_batch tB
					ON tB.id = tA.request_barang_batch_id
					GROUP BY barang_id, warna_id
				)t3
				ON t1.barang_id = t3.barang_id
				AND t1.warna_id = t3.warna_id
				AND t2.tanggal >= t3.tanggal_awal
				AND t2.tanggal <= t3.latest
				WHERE t3.tanggal_request is not null
				GROUP BY barang_id, warna_id
			)t2
			ON t1.barang_id = t2.barang_id
			AND t1.warna_id = t2.warna_id
			");

		return $query->result();
	}

	function get_request_barang_terkirim_3($request_barang_id, $closed_date){
		$this->db->simple_query('SET SESSION group_concat_max_len=15000');
		$query = $this->db->query("SELECT t1.barang_id, t1.warna_id, tanggal, tanggal_masuk, po_pembelian_batch_id as po_pembelian_batch_id, 
			qty as qty_masuk,qty_request, bulan_request, latest, qty_data
			FROM (
				SELECT barang_id, warna_id, min(tanggal) as tanggal, max(DATE_FORMAT(bulan_request,'%Y-%m-31')) as latest, 
					group_concat(DATE_ADD(bulan_request,interval 1 year) order by bulan_request asc) as bulan_request, 
					group_concat(qty  order by bulan_request asc) as qty_request
				FROM  (
					SELECT *
					FROM nd_request_barang_qty
					WHERE id IN (
						SELECT max(tA.id)
						FROM nd_request_barang_qty tA
						LEFT JOIN nd_request_barang_batch tB
						ON tA.request_barang_batch_id = tB.id
						WHERE request_barang_id = $request_barang_id
						AND status_aktif = 1
						GROUP BY barang_id, warna_id, bulan_request
					)
				) t1
				LEFT JOIN nd_request_barang_batch t2
				ON t1.request_barang_batch_id = t2.id
				WHERE request_barang_id = $request_barang_id
				GROUP BY barang_id, warna_id
				)t1
			LEFT JOIN (
				SELECT group_concat(qty) as qty_data, sum(qty) as qty, t1.barang_id, t1.warna_id, 
				group_concat(DATE_FORMAT(t2.tanggal, '%Y-%m-%d')) as tanggal_masuk, 
				group_concat(po_pembelian_batch_id) as po_pembelian_batch_id
				FROM nd_pembelian_detail t1
				LEFT JOIN nd_pembelian t2
				ON t1.pembelian_id = t2.id
				LEFT JOIN (
					SELECT barang_id, warna_id, concat(min(tanggal),' 00:00:00') as tanggal_request,'$closed_date' as latest, 
						concat(min(brq), ' 00:00:00')  as tanggal_awal
					FROM (
						SELECT *, DATE_ADD(bulan_request, INTERVAL 1 YEAR) as brq
						FROM nd_request_barang_qty
						WHERE id IN (
							SELECT min(tX.id)
							FROM nd_request_barang_qty tX
							LEFT JOIN nd_request_barang_batch tY
							ON tX.request_barang_batch_id = tY.id
							WHERE request_barang_id = $request_barang_id
							AND status_aktif = 1
							GROUP BY barang_id, warna_id
						)
					)tA
					LEFT JOIN nd_request_barang_batch tB
					ON tB.id = tA.request_barang_batch_id
					GROUP BY barang_id, warna_id
				)t3
				ON t1.barang_id = t3.barang_id
				AND t1.warna_id = t3.warna_id
				AND t2.tanggal >= t3.tanggal_awal
				AND t2.tanggal <= t3.latest
				WHERE t3.tanggal_request is not null
				GROUP BY barang_id, warna_id
			)t2
			ON t1.barang_id = t2.barang_id
			AND t1.warna_id = t2.warna_id
			");

		return $query->result();
	}

	function get_request_barang_qty_data($request_barang_id, $request_barang_batch_id, $barang_id){
		$query = $this->db->query("SELECT t1.*
		FROM (
				(
					SELECT tA.*, if(other_request_batch_id is null, 2, 1) as tipe
					FROM (
						SELECT request_barang_batch_id, barang_id, warna_id, bulan_request, sum(qty) as qty, group_concat(qty) as qty_data
						FROM nd_request_barang_detail
						WHERE request_barang_batch_id = $request_barang_batch_id
						GROUP BY barang_id, warna_id, bulan_request
					)tA
                    LEFT JOIN (
                        SELECT barang_id, warna_id, bulan_request, request_barang_batch_id, tY.id as other_request_batch_id
                        FROM (
                            SELECT *
                            FROM nd_request_barang_batch
                            WHERE request_barang_id = $request_barang_id
							AND status_aktif = 1
                        )tX
                        LEFT JOIN (
                            SELECT *
                            FROM nd_request_barang_detail
                            WHERE request_barang_batch_id != $request_barang_batch_id
                        )tY
                        ON tY.request_barang_batch_id = tX.id
                    )tB
                    ON tA.barang_id = tB.barang_id
                    AND tA.warna_id = tB.warna_id
                    AND tA.request_barang_batch_id = tB.request_barang_batch_id
				)UNION(
					SELECT tA.*, 'b' as tipe
					FROM (
						SELECT request_barang_batch_id, barang_id, warna_id, bulan_request, sum(qty) as qty, group_concat(qty) as qty_data
						FROM nd_request_barang_detail
						WHERE request_barang_batch_id != $request_barang_batch_id
						GROUP BY barang_id, warna_id, bulan_request
					)tA
					LEFT JOIN (
						SELECT *
						FROM nd_request_barang_batch
						WHERE request_barang_id = $request_barang_id
							AND status_aktif = 1
					) tB
					ON tA.request_barang_batch_id = tB.id
					LEFT JOIN (
						SELECT request_barang_batch_id, barang_id, warna_id, bulan_request, sum(qty) as qty, group_concat(qty) as qty_data
						FROM nd_request_barang_detail
						WHERE request_barang_batch_id = $request_barang_batch_id
						GROUP BY barang_id, warna_id, bulan_request
					)tC
					ON tA.barang_id = tC.barang_id
					AND tA.warna_id = tC.warna_id
					AND tA.bulan_request = tC.bulan_request
					WHERE tB.id is not null
					AND tC.warna_id is null
				)
			)t1
		");

		return $query->result();	
	}

	function get_request_barang_qty_keterangan($request_barang_id){
		$query = $this->db->query("SELECT barang_id, warna_id, bulan_request, keterangan
			FROM nd_request_barang_qty
			WHERE id IN (
				SELECT max(tA.id) as id
				FROM nd_request_barang_qty tA
				LEFT JOIN nd_request_barang_batch tB
				ON tA.request_barang_batch_id = tB.id
				WHERE request_barang_id = $request_barang_id
				AND keterangan is not null
				GROUP BY barang_id, warna_id, bulan_request
			)
			");
		return $query->result();
		
	}

	function get_po_pembelian_for_po_baru($tanggal, $tanggal_end, $supplier_id, $cond, $request_barang_batch_id, $request_barang_id){
		$query = $this->db->query("SELECT tA.*
				FROM (
					SELECT *
					FROM (
						SELECT t1.po_pembelian_batch_id, t2.tanggal, 
							ifnull(barang_id_baru, t3.barang_id) as barang_id, 
							warna_id,
							t1.qty,  
							concat(if(t4.tanggal >= '2019-09-27', 
							concat(ifnull(pre_po,''),tbl_f.kode,'/',DATE_FORMAT(t4.tanggal,'%y'),'/',LPAD(po_number,3,'0')) ,
							concat(LPAD(po_number,3,'0'),'/',DATE_FORMAT(t4.tanggal,'%m'),'/',DATE_FORMAT(t4.tanggal,'%y'))),'-',batch,if(revisi > 1,concat('R',revisi-1),'') ) as po_number
						FROM nd_po_pembelian_warna t1
						LEFT JOIN (
							SELECT *
							FROM nd_po_pembelian_batch
							WHERE tanggal >='$tanggal'
							AND tanggal <= '$tanggal_end'
						) t2
						ON t1.po_pembelian_batch_id = t2.id
						LEFT JOIN nd_po_pembelian_detail t3
						ON t1.po_pembelian_detail_id = t3.id
						LEFT JOIN nd_po_pembelian t4
						ON t2.po_pembelian_id = t4.id
						LEFT JOIN nd_supplier tbl_f
						ON t4.supplier_id = tbl_f.id
						LEFT JOIN nd_toko t5 
						ON t4.toko_id = t5.id
                        WHERE t2.id is not null
						AND supplier_id = $supplier_id
					) result
					$cond
                    ORDER BY tanggal, po_pembelian_batch_id ASC
				)tA
				LEFT JOIN (
                    SELECT t1.*
                    FROM (
                        SELECT barang_id, warna_id, po_pembelian_batch_id, request_barang_batch_id
                        FROM nd_request_barang_detail
                        GROUP BY barang_id, warna_id, po_pembelian_batch_id
                    )t1
					LEFT JOIN nd_request_barang_batch t2
                    ON t1.request_barang_batch_id = t2.id
                    WHERE request_barang_id = $request_barang_id 
                    AND t2.id is not null
				)tB
				ON tA.barang_id = tB.barang_id
				AND tA.warna_id = tB.warna_id
				AND tA.po_pembelian_batch_id = tB.po_pembelian_batch_id
				WHERE tB.po_pembelian_batch_id is null
		");
		return $query->result();	
	}

	function get_po_request_urgent_status($request_barang_id){
		$query = $this->db->query("SELECT *
			FROM nd_request_barang_detail
			WHERE status_urgent = 1
			AND id IN (
				SELECT max(tA.id)
				FROM nd_request_barang_qty tA
				LEFT JOIN nd_request_barang_batch tB
				ON tA.request_barang_batch_id = tB.id
				WHERE tB.request_barang_id = $request_barang_id
				AND tB.id is not null
				AND status_aktif = 1
				GROUP BY barang_id, warna_id, request_barang_id, bulan_request
			)
		");
		return $query->result();	
	}
	
	function get_updated_locked_po_request($request_barang_id, $tanggal_start){
		$query = $this->db->query("SELECT tM.*, nama as nama_barang, warna_jual as nama_warna,
			group_concat(tN.po_number ORDER BY tN.po_pembelian_batch_id) as po_number_baru, 
			group_concat(ceil(qty_outstanding/100) * 100 ORDER BY tN.po_pembelian_batch_id) as qty_outstanding, 
			group_concat(po_qty ORDER BY tN.po_pembelian_batch_id) as po_qty, 
			group_concat(tN.po_pembelian_batch_id ORDER BY tN.po_pembelian_batch_id) as po_pembelian_batch_id_baru
			FROM (
				SELECT tX.*, tY.locked_by, tY.batch, tY.locked_date, po_number
				FROM(
					SELECT t1.*, t2.tanggal
					FROM (
						SELECT tA.*, tB.id as request_barang_qty_id, tB.qty as qty_request
						FROM nd_request_barang_detail tA
						LEFT JOIN (
							SELECT *
							FROM nd_request_barang_qty
							WHERE id IN (
								SELECT max(tA.id)
								FROM nd_request_barang_qty tA
								LEFT JOIN nd_request_barang_batch tB
								ON tA.request_barang_batch_id = tB.id
								WHERE request_barang_id = $request_barang_id
								AND tB.id is not null
								AND status_aktif = 1
								GROUP BY barang_id, warna_id, request_barang_id
							)
						) tB
						ON tA.request_barang_batch_id = tB.request_barang_batch_id
						AND tA.barang_id = tB.barang_id
						AND tA.warna_id = tB.warna_id
						WHERE tB.request_barang_batch_id is not null
						AND tA.po_pembelian_batch_id != 0
					)t1
					LEFT JOIN (
						SELECT barang_id, warna_id, min(tanggal) as tanggal
						FROM nd_request_barang_qty tA
						LEFT JOIN nd_request_barang_batch tB
						ON tA.request_barang_batch_id = tB.id
						WHERE request_barang_id = $request_barang_id
						AND status_aktif = 1
						GROUP BY barang_id, warna_id
					) t2
					ON t1.barang_id = t2.barang_id
					AND t1.warna_id = t2.warna_id
				)tX
				LEFT JOIN (
					SELECT if(t1.tipe_barang!=1,barang_id_baru,barang_id) as barang_id, warna_id, po_pembelian_batch_id, 
						locked_by, t1.qty, t2.po_pembelian_id, batch, revisi, t2.tanggal as batch_tanggal, locked_date, 
						concat(if(tB.tanggal >= '2019-09-27', concat(ifnull(pre_po,''),tD.kode,'/',DATE_FORMAT(tB.tanggal,'%y'),'/',LPAD(po_number,3,'0')) ,
						concat(LPAD(po_number,3,'0'),'/',DATE_FORMAT(tB.tanggal,'%m'),'/',DATE_FORMAT(tB.tanggal,'%y'))),'-',batch,if(revisi > 1,
						concat('R',revisi-1),'') ) as po_number
					FROM nd_po_pembelian_warna t1
					LEFT JOIN nd_po_pembelian_batch t2
					ON t1.po_pembelian_batch_id = t2.id
					LEFT JOIN nd_po_pembelian_detail t3
					ON t1.po_pembelian_detail_id = t3.id
					LEFT JOIN nd_po_pembelian tB
					ON t2.po_pembelian_id = tB.id
					LEFT JOIN nd_supplier tD
					ON tB.supplier_id = tD.id
					LEFT JOIN nd_toko
					ON tB.toko_id = nd_toko.id
					WHERE t2.status != 0
				) tY
				ON tX.po_pembelian_batch_id = tY.po_pembelian_batch_id
				AND tX.barang_id = tY.barang_id
				AND tX.warna_id = tY.warna_id
				WHERE locked_by is not null
				AND tX.tanggal >= tY.locked_date
			)tM
			LEFT JOIN (
				SELECT t1.barang_id, t1.warna_id, (if(po_qty - ifnull(qty,0) > 0 , po_qty - ifnull(qty,0) ,0))as qty_outstanding, po_qty, po_number, batch_tanggal, t1.po_pembelian_batch_id, locked_date
				FROM (
					SELECT barang_id, warna_id, sum(qty) as po_qty, po_pembelian_batch_id, concat(if(tB.tanggal >= '2019-09-27', concat(ifnull(pre_po,''),tD.kode,'/',DATE_FORMAT(tB.tanggal,'%y'),'/',LPAD(po_number,3,'0')) ,concat(LPAD(po_number,3,'0'),'/',DATE_FORMAT(tB.tanggal,'%m'),'/',DATE_FORMAT(tB.tanggal,'%y'))),'-',batch,if(revisi > 1,concat('R',revisi-1),'') ) as po_number, batch_tanggal, batch, locked_date
					FROM (
						SELECT if(t1.tipe_barang!=1,barang_id_baru,barang_id) as barang_id, warna_id, po_pembelian_batch_id, locked_by, t1.qty, t2.po_pembelian_id, batch, revisi, t2.tanggal as batch_tanggal, locked_date
						FROM nd_po_pembelian_warna t1
						LEFT JOIN nd_po_pembelian_batch t2
						ON t1.po_pembelian_batch_id = t2.id
						LEFT JOIN nd_po_pembelian_detail t3
						ON t1.po_pembelian_detail_id = t3.id
						WHERE t2.status != 0
						AND (
							locked_date is null
							OR locked_date >= '$tanggal_start'
						)
						)tA
					LEFT JOIN (
						SELECT *
						FROM nd_po_pembelian
						) tB
					ON tA.po_pembelian_id = tB.id
					LEFT JOIN nd_toko tC
					ON tB.toko_id = tC.id
					LEFT JOIN nd_supplier tD
					ON tB.supplier_id=tD.id
					WHERE tB.status_aktif = 1
					GROUP BY barang_id, warna_id, po_pembelian_batch_id
					)t1
				LEFT JOIN (
					SELECT t1.barang_id, t1.warna_id, sum(qty) as qty, po_pembelian_batch_id
					FROM (
						SELECT barang_id, warna_id, qty, tA.id, created_at, po_pembelian_batch_id
						FROM nd_pembelian_detail tA
						LEFT JOIN (
							SELECT *
							FROM nd_pembelian
							WHERE status_aktif = 1
							AND po_pembelian_batch_id is not null
							)tB
						ON tA.pembelian_id = tB.id
						WHERE tB.id is not null
					)t1
					LEFT JOIN (
						SELECT t11.id,barang_id, warna_id, concat(t12.tanggal,' 23:59:59') as tanggal_akhir
						FROM (
							SELECT *
							FROM nd_request_barang_qty
							WHERE id IN (
								SELECT min(tA.id)
								FROM nd_request_barang_qty tA
								LEFT JOIN nd_request_barang_batch tB
								ON tA.request_barang_batch_id = tB.id
								WHERE request_barang_id = $request_barang_id
								AND tB.id is not null
								AND status_aktif = 1
								GROUP BY barang_id, warna_id, request_barang_id
							)
						)t11
						LEFT JOIN nd_request_barang_batch t12
						ON t11.request_barang_batch_id = t12.id
					) t2
					ON t1.barang_id = t2.barang_id
					AND t1.warna_id = t2.warna_id
					AND t1.created_at <= t2.tanggal_akhir
					WHERE t2.id is not null
					GROUP BY barang_id, warna_id, po_pembelian_batch_id
					) t2
				ON t1.po_pembelian_batch_id = t2.po_pembelian_batch_id
				AND t1.barang_id = t2.barang_id
				AND t1.warna_id = t2.warna_id
			)tN
			ON tM.barang_id = tN.barang_id
			AND tM.warna_id = tN.warna_id
			LEFT JOIN nd_barang
			ON tM.barang_id = nd_barang.id
			LEFT JOIN nd_warna
			ON tM.warna_id = nd_warna.id
			WHERE qty_outstanding > 0
			AND (
				tN.locked_date is null 
				OR tN.locked_date > tM.locked_date
			)
			GROUP BY tM.barang_id, tM.warna_id, bulan_request
		");
		return $query->result();
	}

	function get_updated_revisi_po_request($request_barang_id, $tanggal_start){
		$query = $this->db->query("SELECT tM.*, nama as nama_barang, warna_jual as nama_warna,
			group_concat(tN.po_number ORDER BY tN.po_pembelian_batch_id) as po_number_baru, 
			group_concat(ceil(qty_outstanding/100) * 100 ORDER BY tN.po_pembelian_batch_id) as qty_outstanding, 
			group_concat(po_qty ORDER BY tN.po_pembelian_batch_id) as po_qty, 
			group_concat(tN.po_pembelian_batch_id ORDER BY tN.po_pembelian_batch_id) as po_pembelian_batch_id_baru
			FROM (
				SELECT tX.*, tanggal_revisi, tY.batch, po_number
				FROM(
					SELECT t1.*, t2.tanggal
					FROM (
						SELECT tA.*, tB.id as request_barang_qty_id, tB.qty as qty_request
						FROM nd_request_barang_detail tA
						LEFT JOIN (
							SELECT *
							FROM nd_request_barang_qty
							WHERE id IN (
								SELECT max(tA.id)
								FROM nd_request_barang_qty tA
								LEFT JOIN nd_request_barang_batch tB
								ON tA.request_barang_batch_id = tB.id
								WHERE request_barang_id = $request_barang_id
								AND tB.id is not null
								AND status_aktif = 1
								GROUP BY barang_id, warna_id, request_barang_id
							)
						) tB
						ON tA.request_barang_batch_id = tB.request_barang_batch_id
						AND tA.barang_id = tB.barang_id
						AND tA.warna_id = tB.warna_id
						WHERE tB.request_barang_batch_id is not null
						AND tA.po_pembelian_batch_id != 0
					)t1
					LEFT JOIN (
						SELECT barang_id, warna_id, min(tanggal) as tanggal
						FROM nd_request_barang_qty tA
						LEFT JOIN nd_request_barang_batch tB
						ON tA.request_barang_batch_id = tB.id
						WHERE request_barang_id = $request_barang_id
						AND status_aktif = 1
						GROUP BY barang_id, warna_id
					) t2
					ON t1.barang_id = t2.barang_id
					AND t1.warna_id = t2.warna_id
				)tX
				LEFT JOIN (
					SELECT tA.*, tA.revisi_date as tanggal_revisi, po_number
					FROM (
						SELECT  *
						FROM nd_po_pembelian_batch
						WHERE status = 0 
					) tA
					LEFT JOIN nd_po_pembelian tC
					ON tA.po_pembelian_id = tC.id
				) tY
				ON tX.po_pembelian_batch_id = tY.id
				WHERE tanggal_revisi is not null
			)tM
			LEFT JOIN (
				SELECT t1.barang_id, t1.warna_id, (if(po_qty - ifnull(qty,0) > 0 , po_qty - ifnull(qty,0) ,0))as qty_outstanding, po_qty, po_number, batch_tanggal, t1.po_pembelian_batch_id, locked_date
				FROM (
					SELECT barang_id, warna_id, sum(qty) as po_qty, po_pembelian_batch_id, concat(if(tB.tanggal >= '2019-09-27', concat(ifnull(pre_po,''),tD.kode,'/',DATE_FORMAT(tB.tanggal,'%y'),'/',LPAD(po_number,3,'0')) ,concat(LPAD(po_number,3,'0'),'/',DATE_FORMAT(tB.tanggal,'%m'),'/',DATE_FORMAT(tB.tanggal,'%y'))),'-',batch,if(revisi > 1,concat('R',revisi-1),'') ) as po_number, batch_tanggal, batch, locked_date
					FROM (
						SELECT if(t1.tipe_barang!=1,barang_id_baru,barang_id) as barang_id, warna_id, po_pembelian_batch_id, locked_by, t1.qty, t2.po_pembelian_id, batch, revisi, t2.tanggal as batch_tanggal, locked_date
						FROM nd_po_pembelian_warna t1
						LEFT JOIN nd_po_pembelian_batch t2
						ON t1.po_pembelian_batch_id = t2.id
						LEFT JOIN nd_po_pembelian_detail t3
						ON t1.po_pembelian_detail_id = t3.id
						WHERE t2.status != 0
						AND (
							locked_date is null
							OR locked_date >= '$tanggal_start'
						)
						)tA
					LEFT JOIN (
						SELECT *
						FROM nd_po_pembelian
						) tB
					ON tA.po_pembelian_id = tB.id
					LEFT JOIN nd_toko tC
					ON tB.toko_id = tC.id
					LEFT JOIN nd_supplier tD
					ON tB.supplier_id=tD.id
					WHERE tB.status_aktif = 1
					GROUP BY barang_id, warna_id, po_pembelian_batch_id
					)t1
				LEFT JOIN (
					SELECT t1.barang_id, t1.warna_id, sum(qty) as qty, po_pembelian_batch_id
					FROM (
						SELECT barang_id, warna_id, qty, tA.id, created_at, po_pembelian_batch_id
						FROM nd_pembelian_detail tA
						LEFT JOIN (
							SELECT *
							FROM nd_pembelian
							WHERE status_aktif = 1
							AND po_pembelian_batch_id is not null
							)tB
						ON tA.pembelian_id = tB.id
						WHERE tB.id is not null
					)t1
					LEFT JOIN (
						SELECT t11.id,barang_id, warna_id, concat(t12.tanggal,' 23:59:59') as tanggal_akhir
						FROM (
							SELECT *
							FROM nd_request_barang_qty
							WHERE id IN (
								SELECT min(tA.id)
								FROM nd_request_barang_qty tA
								LEFT JOIN nd_request_barang_batch tB
								ON tA.request_barang_batch_id = tB.id
								WHERE request_barang_id = $request_barang_id
								AND tB.id is not null
								AND status_aktif = 1
								GROUP BY barang_id, warna_id, request_barang_id
							)
						)t11
						LEFT JOIN nd_request_barang_batch t12
						ON t11.request_barang_batch_id = t12.id
					) t2
					ON t1.barang_id = t2.barang_id
					AND t1.warna_id = t2.warna_id
					AND t1.created_at <= t2.tanggal_akhir
					WHERE t2.id is not null
					GROUP BY barang_id, warna_id, po_pembelian_batch_id
					) t2
				ON t1.po_pembelian_batch_id = t2.po_pembelian_batch_id
				AND t1.barang_id = t2.barang_id
				AND t1.warna_id = t2.warna_id
			)tN
			ON tM.barang_id = tN.barang_id
			AND tM.warna_id = tN.warna_id
			LEFT JOIN nd_barang
			ON tM.barang_id = nd_barang.id
			LEFT JOIN nd_warna
			ON tM.warna_id = nd_warna.id
			WHERE qty_outstanding > 0
			AND tN.locked_date is null
			GROUP BY tM.barang_id, tM.warna_id, bulan_request
		");
		return $query->result();
	}

	function get_po_revisi_request($request_barang_id, $tanggal, $tanggal_end){
		$query = $this->db->query("SELECT t1.*
			FROM (
				SELECT tA.*, tB.tanggal
				FROM (
					SELECT *
					FROM nd_request_barang_detail
					WHERE id IN (
						SELECT max(t1.id) 
						FROM nd_request_barang_detail t1
						LEFT JOIN nd_request_barang_batch t2
						ON t1.request_barang_batch_id = t2.id
						WHERE request_barang_id = $request_barang_id
						GROUP BY barang_id, warna_id, bulan_request, po_pembelian_batch_id
					)
				)tA
				LEFT JOIN nd_request_barang_batch tB
				ON tA.request_barang_batch_id = tB.id
			)t1
			LEFT JOIN (
				SELECT *
				FROM nd_po_pembelian_batch
				WHERE status = 0 
				AND revisi_date <= '$tanggal'
			) t2
			ON t1.po_pembelian_batch_id = t2.id
			WHERE t2.id is null
			AND t1.po_pembelian_batch_id != 0

		");
		return $query->result();
	}

	function get_last_request_rekap($request_barang_id, $bulan_request)
	{
		$query = $this->db->query("SELECT barang_id, warna_id, bulan_request, qty, finished_date, finished_by
			FROM nd_request_barang_qty
			WHERE id IN (
				SELECT max(tA.id)
				FROM nd_request_barang_qty tA
				LEFT JOIN nd_request_barang_batch tB
				ON tA.request_barang_batch_id = tB.id
				WHERE request_barang_id = $request_barang_id
				AND tB.id is not null
				AND status_aktif = 1
				AND bulan_request >= '$bulan_request'
				GROUP BY barang_id, warna_id, request_barang_id, bulan_request
			)");
		return $query->result();
	}

	function get_latest_request_qty_by_barang($request_barang_id, $barang_id, $warna_id, $bulan_request){
		$query = $this->db->query("SELECT max(tA.id) as id, barang_id, warna_id, bulan_request
			FROM nd_request_barang_qty tA
			LEFT JOIN nd_request_barang_batch tB
			ON tA.request_barang_batch_id = tB.id
			WHERE request_barang_id = $request_barang_id
			AND bulan_request = '$bulan_request'
			AND barang_id = $barang_id
			AND warna_id = $warna_id
			GROUP BY barang_id, warna_id, bulan_request
			");
		return $query->result();
	}

	function get_po_outstanding_for_new_request($barang_id_list)
	{
		$query = $this->db->query("SELECT t1.barang_id, t1.warna_id, 
			sum(if(po_qty - ifnull(qty,0) - ifnull(qty_before,0) > 0 , po_qty - ifnull(qty,0) - ifnull(qty_before,0) ,0))as qty_outstanding, 
			group_concat(if(po_qty - ifnull(qty,0) - ifnull(qty_before,0) > 0 , po_qty - ifnull(qty,0) - ifnull(qty_before,0) ,0)  order by batch_tanggal,batch asc) as qty_data, 
			group_concat(po_qty order by batch_tanggal,batch asc) as po_qty, 
			group_concat(po_number order by batch_tanggal,batch asc  SEPARATOR '??' ) as po_number, 
			group_concat(batch_tanggal  order by batch_tanggal,batch asc) as batch_tanggal,
			group_concat(t1.po_pembelian_batch_id order by batch_tanggal,batch asc) as po_pembelian_batch_id,
			nama, warna_beli, nama_jual
			FROM (
				SELECT barang_id, warna_id, sum(qty) as po_qty, po_pembelian_batch_id, 
					concat(if(tB.tanggal >= '2019-09-27', concat(ifnull(pre_po,''),tD.kode,'/',DATE_FORMAT(tB.tanggal,'%y'),'/',LPAD(po_number,3,'0')) ,concat(LPAD(po_number,3,'0'),'/',DATE_FORMAT(tB.tanggal,'%m'),'/',DATE_FORMAT(tB.tanggal,'%y'))),'-',batch,if(revisi > 1,concat('R',revisi-1),'') ) as po_number, 
					batch_tanggal, batch, po_pembelian_warna_id, po_pembelian_id
				FROM (
					SELECT *
					FROM (
						SELECT if(t1.tipe_barang!=1,barang_id_baru,barang_id) as barang_id, warna_id, po_pembelian_batch_id, 
							t1.locked_by, t1.qty, t2.po_pembelian_id, batch, revisi, t2.tanggal as batch_tanggal, t1.id as po_pembelian_warna_id, t1.locked_date
						FROM nd_po_pembelian_warna t1
						LEFT JOIN nd_po_pembelian_batch t2
						ON t1.po_pembelian_batch_id = t2.id
						LEFT JOIN nd_po_pembelian_detail t3
						ON t1.po_pembelian_detail_id = t3.id
						WHERE t2.status != 0
						AND t1.locked_date is null
					)result
					WHERE barang_id IN ($barang_id_list)
				)tA
				LEFT JOIN (
					SELECT *
					FROM nd_po_pembelian
				) tB
				ON tA.po_pembelian_id = tB.id
				LEFT JOIN nd_toko tC
				ON tB.toko_id = tC.id
				LEFT JOIN nd_supplier tD
				ON tB.supplier_id=tD.id
				
				GROUP BY barang_id, warna_id, po_pembelian_batch_id
				)t1
			LEFT JOIN (
				SELECT barang_id, warna_id, sum(qty) as qty, po_pembelian_batch_id
				FROM (
					SELECT barang_id, warna_id, qty, po_pembelian_batch_id, tanggal
					FROM nd_pembelian_detail tA
					LEFT JOIN (
						SELECT *
						FROM nd_pembelian
						WHERE status_aktif = 1
						AND po_pembelian_batch_id is not null
						)tB
					ON tA.pembelian_id = tB.id
					WHERE barang_id IN ($barang_id_list)
				)result 
				GROUP BY barang_id, warna_id, po_pembelian_batch_id
			) t2
			ON t1.po_pembelian_batch_id = t2.po_pembelian_batch_id
			AND t1.barang_id = t2.barang_id
			AND t1.warna_id = t2.warna_id
			LEFT JOIN (
				SELECT po_pembelian_id, po_pembelian_batch_id, po_pembelian_warna_id, qty as qty_before
				FROM nd_po_pembelian_before_qty
			) t3
			ON t1.po_pembelian_id = t3.po_pembelian_id
			AND t1.po_pembelian_batch_id = t3.po_pembelian_batch_id
			AND t1.po_pembelian_warna_id = t3.po_pembelian_warna_id
			LEFT JOIN nd_barang 
			ON t1.barang_id = nd_barang.id
			LEFT JOIN nd_warna
			ON t1.warna_id = nd_warna.id
			GROUP BY t1.barang_id, t1.warna_id");

		return $query->result();

	}

}