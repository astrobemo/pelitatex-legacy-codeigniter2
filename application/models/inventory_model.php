<?php

class Inventory_Model extends CI_Model {

	function __construct()
    {
         // Call the Model constructor
        parent::__construct();
        $this->db->query("SET SESSION time_zone = '+7:00'");
    }

//=====================================================Stok inventory======================================

	function get_batch_for_pre_po($barang_id, $cond_supplier){
		$query = $this->db->query("SELECT po_number, batch, po_pembelian_id, po_pembelian_batch_id, barang_id, tanggal, t1.supplier_id, nama_beli
			FROM (
				SELECT (a.qty - ifnull(h.qty,0)-ifnull(qty_beli,0)) as qty_sisa, a.barang_id , a.warna_id, b.po_pembelian_id, 
				concat(if(e.tanggal >= '2019-09-27', concat(ifnull(pre_po,''),g.kode,'/',DATE_FORMAT(e.tanggal,'%y'),'/',LPAD(po_number,3,'0')) ,concat(LPAD(po_number,3,'0'),'/',DATE_FORMAT(e.tanggal,'%m'),'/',DATE_FORMAT(e.tanggal,'%y'))),'-',batch,if(revisi > 1,concat('R',revisi-1),'') ) as batch, 
				e.po_number, a.po_pembelian_batch_id, b.tanggal, e.supplier_id, nama_beli
				FROM (
					SELECT *
					FROM (
						SELECT t1.id, po_pembelian_detail_id, t1.qty, if(tipe_barang = 1, t2.barang_id, barang_id_baru) as barang_id, 
						po_pembelian_id, warna_id, po_pembelian_batch_id, if(tipe_barang = 1, t3.nama, t4.nama) as nama_beli
						FROM nd_po_pembelian_warna t1
						LEFT JOIN nd_po_pembelian_detail t2
						ON t1.po_pembelian_detail_id = t2.id
						LEFT JOIN nd_barang_beli t3
						ON t2.barang_beli_id = t3.id
						LEFT JOIN nd_barang_beli t4
						ON t1.barang_beli_id_baru = t4.id
						WHERE locked_by is null
					)result
					WHERE barang_id=$barang_id
				) a
				LEFT JOIN (
					SELECT *
					FROM nd_po_pembelian_batch
					WHERE status != 0
					) b
				ON a.po_pembelian_batch_id = b.id
				LEFT JOIN (
					SELECT barang_id, warna_id, sum(qty) as qty_beli, po_pembelian_batch_id 
					FROM nd_pembelian_detail t1
					LEFT JOIN nd_pembelian t2
					ON t1.pembelian_id = t2.id
					GROUP BY barang_id, warna_id, po_pembelian_batch_id
					) d
				ON a.warna_id = d.warna_id
				AND b.id = d.po_pembelian_batch_id
				AND d.barang_id = a.barang_id
				LEFT JOIN (
					SELECT *
					FROM nd_po_pembelian
					$cond_supplier
				) e
				ON b.po_pembelian_id = e.id
				LEFT JOIN nd_toko f
				ON e.toko_id = f.id
				LEFT JOIN nd_supplier g
				ON e.supplier_id = g.id
				LEFT JOIN nd_po_pembelian_before_qty h
				ON a.id = h.po_pembelian_warna_id
				WHERE (a.qty - ifnull(h.qty,0)-ifnull(qty_beli,0)) >= 0
				AND b.id is not null
				AND e.id is not null
				GROUP BY a.po_pembelian_batch_id
			)t1
			WHERE barang_id = $barang_id
			GROUP BY po_pembelian_batch_id");
		return $query->result();
	}

	function get_stok_for_pre_po_legacy($barang_id_pool, $tanggal_akhir, $tanggal_awal, $stok_opname_id, $cond_barang){
		$query = $this->db->query("SELECT barang_id, warna_id, sum(qty_stok) as qty_stok, sum(jumlah_roll_stok) as jumlah_roll_stok, sum(qty_po) as qty_po, 
			sum(if(qty_sisa_po < 0, 0, qty_sisa_po) ) as qty_sisa, 
			b.nama as nama_barang, c.warna_beli as nama_warna, d.nama as nama_satuan, 
			group_concat(qty_po_data ) as qty_po_data, 
			group_concat(qty_sisa_data ) as qty_sisa_data, 
			group_concat(batch_id ) as batch_id, 
			group_concat(po_pembelian_id  ) as po_pembelian_id, 
			group_concat(ifnull(harga,0)) as harga_po, group_concat(ifnull(OCKH,if(OCKH != '',OCKH,0)) SEPARATOR '??') as OCKH, 
			group_concat(locked_by) as locked_by
				FROM(
					(
						SELECT barang_id, warna_id, ROUND(
								sum(if(tanggal_stok is not null,if(tanggal >= tanggal_stok, qty_masuk,0) ,qty_masuk))  - sum(if(tanggal_stok is not null,if(tanggal >= tanggal_stok,qty_keluar,0), qty_keluar))
							,2) as qty_stok,
		 					sum(if(tanggal_stok is not null,if(tanggal >= tanggal_stok,jumlah_roll_masuk,0), jumlah_roll_masuk)) - sum(if(tanggal_stok is not null,if(tanggal >= tanggal_stok,jumlah_roll_keluar,0), jumlah_roll_keluar)) as jumlah_roll_stok,
		 					0 as qty_po, 0 as qty_po_data, 0 as qty_sisa_po, 0 as qty_sisa_data, 0 as po_pembelian_id, 0 as batch_id, 0 as harga,0 as OCKH, 0 as locked_by
						FROM(
							(
						        SELECT barang_id, warna_id, t2.gudang_id, qty as qty_masuk, jumlah_roll as jumlah_roll_masuk, 0 as qty_keluar, 0 as jumlah_roll_keluar, tanggal, 1 as tipe, t1.id
						        FROM (
						            SELECT *
						            FROM nd_pembelian_detail
						            ) t1
						        LEFT JOIN (
						            SELECT *
						            FROM nd_pembelian
						            WHERE tanggal <= '$tanggal_akhir'
						            AND tanggal >= '$tanggal_awal'
						            AND status_aktif = 1
						            ) t2
						        ON t1.pembelian_id = t2.id
						        WHERE t2.id is not null
						    )UNION(
						        SELECT barang_id, warna_id, gudang_id_after, qty , jumlah_roll , 0, 0, tanggal, 2, id
						        FROM nd_mutasi_barang
					            WHERE tanggal <= '$tanggal_akhir'
					            AND tanggal >= '$tanggal_awal'
					            AND status_aktif = 1
						    )UNION(
						        SELECT barang_id, warna_id, t1.gudang_id, 0, 0, subqty, subjumlah_roll, tanggal, 3, t1.id
						        FROM nd_penjualan_detail t1
						        LEFT JOIN (
						            SELECT *
						            FROM nd_penjualan
						            WHERE tanggal <= '$tanggal_akhir'
						            AND tanggal >= '$tanggal_awal'
						            AND status_aktif = 1
						            ) t2
						        ON t1.penjualan_id = t2.id
						        WHERE t2.id is not null
						    )UNION(
						        SELECT barang_id, warna_id, t1.gudang_id, 0, 0, qty, jumlah_roll, tanggal, 3, t3.id
						        FROM nd_pengeluaran_stok_lain_detail t1
						        LEFT JOIN (
						            SELECT *
						            FROM nd_pengeluaran_stok_lain
						            WHERE tanggal <= '$tanggal_akhir'
						            AND tanggal >= '$tanggal_awal'
						            AND status_aktif = 1
						            ) t2
						        ON t1.pengeluaran_stok_lain_id = t2.id
						        LEFT JOIN (
						            SELECT sum(qty  * if(jumlah_roll =0,1, jumlah_roll)  )as qty , sum(jumlah_roll) as jumlah_roll, pengeluaran_stok_lain_detail_id, id
						            FROM nd_pengeluaran_stok_lain_qty_detail
						            GROUP BY pengeluaran_stok_lain_detail_id
						            ) t3
						        ON t3.pengeluaran_stok_lain_detail_id = t1.id
						        WHERE t2.id is not null
						    )UNION(
						        SELECT barang_id, warna_id, t1.gudang_id, qty, jumlah_roll, 0, 0, tanggal, 4, t3.id
						        FROM nd_retur_jual_detail t1
						        LEFT JOIN (
						            SELECT *, concat('FRJ', DATE_FORMAT(tanggal,'%d%m%y'),'-',LPAD(no_faktur,4,'0')) as no_faktur_lengkap
						            FROM nd_retur_jual
						            WHERE tanggal <= '$tanggal_akhir'
						            AND tanggal >= '$tanggal_awal'
						            AND status_aktif = 1
						            ) t2
						        ON t1.retur_jual_id = t2.id
						        LEFT JOIN (
						            SELECT sum(qty  * if(jumlah_roll =0,1, jumlah_roll)  )as qty , sum(jumlah_roll) as jumlah_roll, retur_jual_detail_id, id
						            FROM nd_retur_jual_qty
						            gROUP BY retur_jual_detail_id
						            ) t3
						        ON t3.retur_jual_detail_id = t1.id
						        WHERE barang_id is not null 
						        AND warna_id is not null
						        AND t2.id is not null
						    )UNION(
						        SELECT barang_id, warna_id, t1.gudang_id, 0, 0, qty, jumlah_roll, tanggal, 19, t3.id
						        FROM nd_retur_beli_detail t1
						        LEFT JOIN (
						            SELECT t1.*, concat('SJ/R/',if(t2.kode is not null, concat(t2.kode,'/'),'LL/'),DATE_FORMAT(tanggal,'%y'),'-',no_sj ) as no_faktur_lengkap
						        	FROM nd_retur_beli t1
						        	LEFT JOIN nd_supplier t2
						        	ON t1.supplier_id = t2.id
						            WHERE tanggal <= '$tanggal_akhir'
						            AND tanggal >= '$tanggal_awal'
						            AND t1.status_aktif = 1
						            ) t2
						        ON t1.retur_beli_id = t2.id
						        LEFT JOIN (
						            SELECT sum(qty  * if(jumlah_roll =0,1, jumlah_roll)  ) as qty , sum(jumlah_roll) as jumlah_roll, retur_beli_detail_id, id
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
					            WHERE tanggal <= '$tanggal_akhir'
					            AND tanggal >= '$tanggal_awal'
					            AND status_aktif = 1
						    )UNION(
						        SELECT barang_id, warna_id, gudang_id, qty, jumlah_roll, 0, 0,tanggal, 6, id
						        FROM (
						            SELECT *
						            FROM nd_penyesuaian_stok
						            WHERE tanggal <= '$tanggal_akhir'
						            AND tanggal >= '$tanggal_awal'
						            AND tipe_transaksi = 0
						        ) nd_penyesuaian_stok
						    )UNION(
						        SELECT barang_id, warna_id, gudang_id, if(tipe_transaksi = 1,qty,0) , if(tipe_transaksi = 1,jumlah_roll,0), if(tipe_transaksi = 2,qty,0), if(tipe_transaksi = 2,jumlah_roll,0),tanggal, 7, id
						        FROM (
						            SELECT *
						            FROM nd_penyesuaian_stok
						            WHERE tanggal <= '$tanggal_akhir'
						            AND tanggal >= '$tanggal_awal'
						            AND tipe_transaksi != 0
						        ) nd_penyesuaian_stok
						    )UNION(
						        SELECT barang_id, warna_id, gudang_id, qty, jumlah_roll, 0, 0, tanggal, 8, t1.id
						        FROM (
						            SELECT sum(qty  * jumlah_roll) as qty , sum(jumlah_roll) as jumlah_roll, stok_opname_id, barang_id, gudang_id,warna_id, id
						            FROM nd_stok_opname_detail
					        		WHERE warna_id > 0
						            GROUP BY stok_opname_id, barang_id, warna_id, gudang_id 
						        ) t1
						        LEFT JOIN (
						            SELECT *
						            FROM nd_stok_opname
						            WHERE status_aktif = 1
						            AND tanggal <= '$tanggal_akhir'
					            	AND tanggal >= '$tanggal_awal'
						        ) t2
						        ON t1.stok_opname_id = t2.id
						        WHERE t2.id is not null
						    )
						)tbl_1
						LEFT JOIN (
						    SELECT barang_id as barang_id_stok, warna_id as warna_id_stok, MAX(tanggal) as tanggal_stok, gudang_id as gudang_id_stok
						    FROM (
						        SELECT stok_opname_id, barang_id,warna_id, gudang_id
						        FROM nd_stok_opname_detail
				        		WHERE warna_id > 0
						        GROUP BY stok_opname_id, barang_id, warna_id, gudang_id
						    ) tA
						    LEFT JOIN (
						        SELECT *
						        FROM nd_stok_opname
						        WHERE status_aktif = 1
						        AND tanggal <= '$tanggal_akhir'
				            	AND tanggal >= '$tanggal_awal'
						    ) tB
						    ON tA.stok_opname_id = tB.id
						    WHERE tB.id is not null
							GROUP BY barang_id, warna_id, gudang_id
						) tbl_2
						ON tbl_1.barang_id = tbl_2.barang_id_stok
						AND tbl_1.warna_id = tbl_2.warna_id_stok
						AND tbl_1.gudang_id = tbl_2.gudang_id_stok
						GROUP BY barang_id, warna_id, gudang_id
					)UNION(
						SELECT barang_id, warna_id, 0 ,0, sum(qty_po), group_concat(qty_po) ,sum(qty_sisa), 
						group_concat(qty_sisa_data), 
						group_concat(po_pembelian_id) as po_pembelian_id, 
						group_concat(batch_id) as batch_id, 
						group_concat(harga), 
						group_concat(ifnull(OCKH,0)SEPARATOR '??' ), 
						group_concat(t1.locked_by) as locked_by
						FROM (
							SELECT sum(a.qty-ifnull(qty_beli,0) - ifnull(j.qty,0) ) as qty_sisa, a.barang_id as barang_id, a.warna_id, a.po_pembelian_id, group_concat(a.po_pembelian_batch_id) as batch_id, 
							group_concat(if(a.locked_by is null,a.qty-ifnull(qty_beli,0) + ifnull(qty_retur,0) - ifnull(j.qty,0) ,0)) as qty_sisa_data, 
							group_concat(harga) as harga, group_concat(a.qty) as qty_po, 
							group_concat(ifnull(OCKH,0) SEPARATOR '??') as OCKH, group_concat(ifnull(qty_beli,0)) as qty_beli, 
							group_concat(ifnull(a.locked_by,0)) as locked_by, ifnull(j.qty,0) as qty_2019
							FROM (
								SELECT id, po_pembelian_detail_id, po_pembelian_batch_id, barang_id, po_pembelian_id, sum(qty) as qty, warna_id, harga, OCKH, locked_by
								FROM (
									SELECT t1.id as id, po_pembelian_detail_id, po_pembelian_batch_id, 
									if(tipe_barang = 1, t2.barang_id, barang_id_baru) as barang_id, 
									po_pembelian_id, t1.qty, warna_id, if(tipe_barang != 1 AND tipe_barang != 2, harga_baru, harga) as harga, OCKH, if(locked_date <= '$tanggal_akhir',locked_by,null) as locked_by
									FROM (
										SELECT *
										FROM nd_po_pembelian_warna
										) t1
									LEFT JOIN nd_po_pembelian_detail t2
									ON t1.po_pembelian_detail_id = t2.id
									)result
								GROUP BY barang_id, warna_id, po_pembelian_batch_id
								) a
							LEFT JOIN (
								SELECT *
								FROM nd_po_pembelian_batch
								WHERE status != 0
								) b
							ON a.po_pembelian_batch_id = b.id
							LEFT JOIN (
								SELECT barang_id, warna_id, sum(qty) as qty_beli, po_pembelian_batch_id 
								FROM nd_pembelian_detail t1
								LEFT JOIN nd_pembelian t2
								ON t1.pembelian_id = t2.id
								GROUP BY barang_id, warna_id, po_pembelian_batch_id
								) d
							ON a.warna_id = d.warna_id
							AND b.id = d.po_pembelian_batch_id
							AND d.barang_id = a.barang_id
							LEFT JOIN nd_po_pembelian e
							ON a.po_pembelian_id = e.id
							LEFT JOIN (
								SELECT barang_id, warna_id, sum(qty*if(jumlah_roll = 0 ,1, jumlah_roll)) as qty_retur, po_pembelian_batch_id 
								FROM nd_retur_beli_detail t1
								LEFT JOIN nd_retur_beli t2
								ON t1.retur_beli_id = t2.id
								LEFT JOIN nd_retur_beli_qty t3
								ON t1.id = t3.retur_beli_detail_id
								GROUP BY barang_id, warna_id, po_pembelian_batch_id
								) i
							ON a.warna_id = i.warna_id
							AND b.id = i.po_pembelian_batch_id
							AND i.barang_id = a.barang_id
							LEFT JOIN nd_po_pembelian_before_qty j
							ON a.id = j.po_pembelian_warna_id
							WHERE batch != 0
							AND b.id is not null
							GROUP BY a.barang_id, warna_id, a.po_pembelian_id
						)t1
						LEFT JOIN nd_po_pembelian t2
						ON t1.po_pembelian_id = t2.id
						GROUP BY barang_id, warna_id
					)
				) a
				LEFT JOIN nd_barang b
				ON a.barang_id = b.id
				LEFT JOIN nd_warna c
				ON a.warna_id = c.id
				LEFT JOIN nd_satuan d
				ON b.satuan_id = d.id
				$cond_barang
				GROUP BY barang_id, warna_id
				ORDER BY c.warna_jual
			");

		return $query->result();

	}

	function get_stok_for_pre_po($barang_id_pool, $tanggal_akhir, $tanggal_awal, $stok_opname_id, $cond_barang, $tahun_tutup_buku, $bulan_qty, $cond_supplier){
		$this->db->simple_query('SET SESSION group_concat_max_len=15000');

		$qty_bulan = $bulan_qty.'_qty';
		$roll_bulan = $bulan_qty.'_roll';
		$query = $this->db->query("SELECT barang_id, warna_id, sum(qty_stok) as qty_stok, sum(jumlah_roll_stok) as jumlah_roll_stok, sum(qty_po) as qty_po, 
			sum(if(qty_sisa_po < 0, 0, qty_sisa_po) ) as qty_sisa, 
			b.nama as nama_barang, c.warna_beli as nama_warna, d.nama as nama_satuan, 
			group_concat(qty_po_data ) as qty_po_data, 
			group_concat(qty_sisa_data ) as qty_sisa_data, 
			group_concat(batch_id ) as batch_id, 
			group_concat(po_pembelian_id  ) as po_pembelian_id, 
			group_concat(ifnull(harga,0)) as harga_po, group_concat(ifnull(OCKH,if(OCKH != '',OCKH,0)) SEPARATOR '??') as OCKH, 
			group_concat(locked_by) as locked_by
				FROM(
					(
						SELECT barang_id, warna_id, ROUND(
								sum(if(tanggal_stok is not null,if(tanggal >=  tanggal_stok, qty_masuk,0) ,qty_masuk))  - 
								sum(if(tanggal_stok is not null,if(tanggal >=  tanggal_stok,qty_keluar,0), qty_keluar))
							,2) as qty_stok,
								sum(if(tanggal_stok is not null,if(tanggal >=  tanggal_stok,jumlah_roll_masuk,0), jumlah_roll_masuk)) - 
								sum(if(tanggal_stok is not null,if(tanggal >=  tanggal_stok,jumlah_roll_keluar,0), jumlah_roll_keluar)) as jumlah_roll_stok,
								0 as qty_po, 0 as qty_po_data, 0 as qty_sisa_po, 0 as qty_sisa_data, 0 as po_pembelian_id, 0 as batch_id, 0 as harga,0 as OCKH, 0 as locked_by
						FROM(
							(
								SELECT barang_id, warna_id, t2.gudang_id, 
								qty as qty_masuk, jumlah_roll as jumlah_roll_masuk, 
								0 as qty_keluar, 0 as jumlah_roll_keluar, created_at as tanggal, 1 as tipe, t1.id
								FROM (
									SELECT *
									FROM nd_pembelian_detail
									) t1
								LEFT JOIN (
									SELECT *
									FROM nd_pembelian
									WHERE created_at <=  '$tanggal_akhir'
									AND created_at >=  '$tanggal_awal'
									AND status_aktif = 1
									) t2
								ON t1.pembelian_id = t2.id
								WHERE t2.id is not null
							)UNION(
								SELECT barang_id, warna_id, gudang_id_after, qty , jumlah_roll , 0, 0, created_at as tanggal, 2, id
								FROM nd_mutasi_barang
								WHERE created_at <=  '$tanggal_akhir'
								AND created_at >=  '$tanggal_awal'
								AND status_aktif = 1
							)UNION(
								SELECT barang_id, warna_id, t1.gudang_id, 0, 0, subqty, subjumlah_roll, ifnull(closed_date, created_at) as tanggal, 3, t1.id
								FROM nd_penjualan_detail t1
								LEFT JOIN (
									SELECT *
									FROM nd_penjualan
									WHERE created_at <=  '$tanggal_akhir'
									AND created_at >=  '$tanggal_awal'
									AND status_aktif = 1
									) t2
								ON t1.penjualan_id = t2.id
								WHERE t2.id is not null
							)UNION(
								SELECT barang_id, warna_id, t1.gudang_id, 0, 0, qty, jumlah_roll, created_at as tanggal, 3, t3.id
								FROM nd_pengeluaran_stok_lain_detail t1
								LEFT JOIN (
									SELECT *
									FROM nd_pengeluaran_stok_lain
									WHERE created_at <=  '$tanggal_akhir'
									AND created_at >=  '$tanggal_awal'
									AND status_aktif = 1
									) t2
								ON t1.pengeluaran_stok_lain_id = t2.id
								LEFT JOIN (
									SELECT sum(qty  * if(jumlah_roll =0,1, jumlah_roll)  )as qty , sum(jumlah_roll) as jumlah_roll, pengeluaran_stok_lain_detail_id, id
									FROM nd_pengeluaran_stok_lain_qty_detail
									GROUP BY pengeluaran_stok_lain_detail_id
									) t3
								ON t3.pengeluaran_stok_lain_detail_id = t1.id
								WHERE t2.id is not null
							)UNION(
								SELECT barang_id, warna_id, t1.gudang_id, qty, jumlah_roll, 0, 0, created_at as tanggal, 4, t3.id
								FROM nd_retur_jual_detail t1
								LEFT JOIN (
									SELECT *, concat('FRJ', DATE_FORMAT(tanggal,'%d%m%y'),'-',LPAD(no_faktur,4,'0')) as no_faktur_lengkap
									FROM nd_retur_jual
									WHERE created_at <=  '$tanggal_akhir'
									AND created_at >=  '$tanggal_awal'
									AND status_aktif = 1
									) t2
								ON t1.retur_jual_id = t2.id
								LEFT JOIN (
									SELECT sum(qty  * if(jumlah_roll =0,1, jumlah_roll)  )as qty , sum(jumlah_roll) as jumlah_roll, retur_jual_detail_id, id
									FROM nd_retur_jual_qty
									gROUP BY retur_jual_detail_id
									) t3
								ON t3.retur_jual_detail_id = t1.id
								WHERE barang_id is not null 
								AND warna_id is not null
								AND t2.id is not null
							)UNION(
								SELECT barang_id, warna_id, t1.gudang_id, 0, 0, qty, jumlah_roll, created_at as tanggal, 19, t3.id
								FROM nd_retur_beli_detail t1
								LEFT JOIN (
									SELECT t1.*, concat('SJ/R/',if(t2.kode is not null, concat(t2.kode,'/'),'LL/'),DATE_FORMAT(tanggal,'%y'),'-',no_sj ) as no_faktur_lengkap
									FROM nd_retur_beli t1
									LEFT JOIN nd_supplier t2
									ON t1.supplier_id = t2.id
									WHERE created_at <=  '$tanggal_akhir'
									AND created_at >=  '$tanggal_awal'
									AND t1.status_aktif = 1
									) t2
								ON t1.retur_beli_id = t2.id
								LEFT JOIN (
									SELECT sum(qty  * if(jumlah_roll =0,1, jumlah_roll)  ) as qty , sum(jumlah_roll) as jumlah_roll, retur_beli_detail_id, id
									FROM nd_retur_beli_qty
									GROUP BY retur_beli_detail_id
									) t3
								ON t3.retur_beli_detail_id = t1.id
								WHERE barang_id is not null 
								AND warna_id is not null
								AND t2.id is not null
							)UNION(
								SELECT barang_id, warna_id, gudang_id_before, 0 , 0, qty, jumlah_roll, created_at as tanggal, 5, id
								FROM nd_mutasi_barang
								WHERE created_at <=  '$tanggal_akhir'
								AND created_at >=  '$tanggal_awal'
								AND status_aktif = 1
							)UNION(
								SELECT barang_id, warna_id, gudang_id, qty, jumlah_roll, 0, 0, created_at as tanggal, 6, id
								FROM (
									SELECT *
									FROM nd_penyesuaian_stok
									WHERE created_at <=  '$tanggal_akhir'
									AND created_at >=  '$tanggal_awal'
									AND tipe_transaksi = 0
								) nd_penyesuaian_stok
							)UNION(
								SELECT barang_id, warna_id, gudang_id, 
								if(tipe_transaksi = 1 OR tipe_transaksi = 3,qty,0) , if(tipe_transaksi = 1,jumlah_roll,if(tipe_transaksi=3,jml_roll,0)), 
								if(tipe_transaksi = 2 OR tipe_transaksi =3,qty,0), if(tipe_transaksi = 2,jumlah_roll,if(tipe_transaksi=3,jumlah_roll,0)), 
								created_at as tanggal, 7, id
								FROM (
									SELECT *
									FROM nd_penyesuaian_stok
									WHERE created_at <=  '$tanggal_akhir'
									AND created_at >=  '$tanggal_awal'
									AND tipe_transaksi != 0
								) t1
								LEFT JOIN (
									SELECT sum(jumlah_roll) as jml_roll, penyesuaian_stok_id
									FROM nd_penyesuaian_stok_split
									GROUP BY penyesuaian_stok_id
								) t2
								ON t1.id=t2.penyesuaian_stok_id
							)UNION(
								SELECT barang_id, warna_id, gudang_id, qty, jumlah_roll, 0, 0, created_at as tanggal, 8, t1.id
								FROM (
									SELECT sum(qty  * jumlah_roll) as qty , sum(jumlah_roll) as jumlah_roll, stok_opname_id, barang_id, gudang_id,warna_id, id
									FROM nd_stok_opname_detail
									WHERE warna_id > 0
									GROUP BY stok_opname_id, barang_id, warna_id, gudang_id 
								) t1
								LEFT JOIN (
									SELECT *
									FROM nd_stok_opname
									WHERE status_aktif = 1
									AND created_at <=  '$tanggal_akhir'
									AND created_at >=  '$tanggal_awal'
								) t2
								ON t1.stok_opname_id = t2.id
								WHERE t2.id is not null
							)UNION(
								SELECT barang_id, warna_id, gudang_id, $qty_bulan as qty, $roll_bulan as jumlah_roll, 0, 0, '$tanggal_awal' as tanggal, 8, id
								FROM nd_tutup_buku_detail_gudang
								WHERE YEAR(tahun) = '$tahun_tutup_buku'
								
							)
						)tbl_1
						LEFT JOIN (
							SELECT barang_id as barang_id_stok, warna_id as warna_id_stok, MAX(created_at) as tanggal_stok, gudang_id as gudang_id_stok
							FROM (
								SELECT stok_opname_id, barang_id,warna_id, gudang_id
								FROM nd_stok_opname_detail
								WHERE warna_id > 0
								GROUP BY stok_opname_id, barang_id, warna_id, gudang_id
							) tA
							LEFT JOIN (
								SELECT *
								FROM nd_stok_opname
								WHERE status_aktif = 1
								AND created_at <=  '$tanggal_akhir'
								AND created_at >=  '$tanggal_awal'
							) tB
							ON tA.stok_opname_id = tB.id
							WHERE tB.id is not null
							GROUP BY barang_id, warna_id, gudang_id
						) tbl_2
						ON tbl_1.barang_id = tbl_2.barang_id_stok
						AND tbl_1.warna_id = tbl_2.warna_id_stok
						AND tbl_1.gudang_id = tbl_2.gudang_id_stok
						GROUP BY barang_id, warna_id, gudang_id
					)UNION(
						SELECT barang_id, warna_id, 0 ,0, sum(qty_po), group_concat(qty_po) ,sum(qty_sisa), 
						group_concat(qty_sisa_data), 
						group_concat(po_pembelian_id) as po_pembelian_id, 
						group_concat(batch_id) as batch_id, 
						group_concat(harga), 
						group_concat(ifnull(OCKH,0)SEPARATOR '??' ), 
						group_concat(t1.locked_by) as locked_by
						FROM (
							SELECT sum(a.qty-ifnull(qty_beli,0) - ifnull(j.qty,0) ) as qty_sisa, a.barang_id as barang_id, a.warna_id, a.po_pembelian_id, group_concat(a.po_pembelian_batch_id) as batch_id, 
							group_concat(if(a.locked_by is null,a.qty-ifnull(qty_beli,0) + ifnull(qty_retur,0) - ifnull(j.qty,0) ,0)) as qty_sisa_data, 
							group_concat(harga) as harga, group_concat(a.qty) as qty_po, 
							group_concat(ifnull(OCKH,0) SEPARATOR '??') as OCKH, group_concat(ifnull(qty_beli,0)) as qty_beli, 
							group_concat(ifnull(a.locked_by,0)) as locked_by, ifnull(j.qty,0) as qty_2019
							FROM (
								SELECT id, po_pembelian_detail_id, po_pembelian_batch_id, barang_id, po_pembelian_id, sum(qty) as qty, warna_id, harga, OCKH, locked_by
								FROM (
									SELECT t1.id as id, po_pembelian_detail_id, po_pembelian_batch_id, 
									if(tipe_barang = 1, t2.barang_id, barang_id_baru) as barang_id, 
									po_pembelian_id, t1.qty, warna_id, if(tipe_barang != 1 AND tipe_barang != 2, harga_baru, harga) as harga, OCKH, if(locked_date <= '$tanggal_akhir',locked_by,null) as locked_by
									FROM (
										SELECT *
										FROM nd_po_pembelian_warna
										) t1
									LEFT JOIN nd_po_pembelian_detail t2
									ON t1.po_pembelian_detail_id = t2.id
									)result
								GROUP BY barang_id, warna_id, po_pembelian_batch_id
								) a
							LEFT JOIN (
								SELECT *
								FROM nd_po_pembelian_batch
								WHERE status != 0
								) b
							ON a.po_pembelian_batch_id = b.id
							LEFT JOIN (
								SELECT barang_id, warna_id, sum(qty) as qty_beli, po_pembelian_batch_id 
								FROM nd_pembelian_detail t1
								LEFT JOIN nd_pembelian t2
								ON t1.pembelian_id = t2.id
								GROUP BY barang_id, warna_id, po_pembelian_batch_id
								) d
							ON a.warna_id = d.warna_id
							AND b.id = d.po_pembelian_batch_id
							AND d.barang_id = a.barang_id
							LEFT JOIN nd_po_pembelian e
							ON a.po_pembelian_id = e.id
							LEFT JOIN (
								SELECT barang_id, warna_id, sum(qty*if(jumlah_roll = 0 ,1, jumlah_roll)) as qty_retur, po_pembelian_batch_id 
								FROM nd_retur_beli_detail t1
								LEFT JOIN nd_retur_beli t2
								ON t1.retur_beli_id = t2.id
								LEFT JOIN nd_retur_beli_qty t3
								ON t1.id = t3.retur_beli_detail_id
								GROUP BY barang_id, warna_id, po_pembelian_batch_id
								) i
							ON a.warna_id = i.warna_id
							AND b.id = i.po_pembelian_batch_id
							AND i.barang_id = a.barang_id
							LEFT JOIN nd_po_pembelian_before_qty j
							ON a.id = j.po_pembelian_warna_id
							WHERE batch != 0
							AND b.id is not null
							GROUP BY a.barang_id, warna_id, a.po_pembelian_id
						)t1
						LEFT JOIN nd_po_pembelian t2
						ON t1.po_pembelian_id = t2.id
						$cond_supplier
						GROUP BY barang_id, warna_id
					)
				) a
				LEFT JOIN nd_barang b
				ON a.barang_id = b.id
				LEFT JOIN nd_warna c
				ON a.warna_id = c.id
				LEFT JOIN nd_satuan d
				ON b.satuan_id = d.id
				$cond_barang
				GROUP BY barang_id, warna_id
				ORDER BY c.warna_jual
			");

		return $query->result();

	}

//=====================================================PPO======================================

	function get_batch_for_ppo($barang_id, $tanggal){
		$this->db->simple_query('SET SESSION group_concat_max_len=15000');

		$query = $this->db->query("SELECT po_number, batch, po_pembelian_id, t1.po_pembelian_batch_id, t1.barang_id, tanggal, batch_raw, ifnull(status_include,1) as status_include, ifnull(status_show,1) as status_show
			FROM (
				SELECT (a.qty-ifnull(qty_beli,0)) as qty_sisa, a.barang_id , a.warna_id, a.po_pembelian_id, concat(if(e.tanggal >= '2019-09-27', concat(ifnull(pre_po,''),ifnull(g.kode,''),'/',DATE_FORMAT(e.tanggal,'%y'),'/',LPAD(po_number,3,'0')) ,concat(LPAD(po_number,3,'0'),'/',DATE_FORMAT(e.tanggal,'%m'),'/',DATE_FORMAT(e.tanggal,'%y'))),'-',batch,if(revisi > 1,concat('R',revisi-1),'') ) as batch, e.po_number, a.po_pembelian_batch_id, b.tanggal, b.batch as batch_raw
				FROM (
					SELECT t1.id, po_pembelian_detail_id, t1.qty, if(tipe_barang = 1, t2.barang_id, barang_id_baru) as barang_id, po_pembelian_id, warna_id, po_pembelian_batch_id
					FROM nd_po_pembelian_warna t1
					LEFT JOIN nd_po_pembelian_detail t2
					ON t1.po_pembelian_detail_id = t2.id
					WHERE if(tipe_barang = 1, t2.barang_id, barang_id_baru) = $barang_id
					) a
				LEFT JOIN (
					SELECT *
					FROM nd_po_pembelian_batch
					WHERE status != 0
					AND batch != ''
					) b
				ON a.po_pembelian_batch_id = b.id
				LEFT JOIN (
					SELECT barang_id, warna_id, sum(qty) as qty_beli, po_pembelian_batch_id 
					FROM nd_pembelian_detail t1
					LEFT JOIN nd_pembelian t2
					ON t1.pembelian_id = t2.id
					GROUP BY barang_id, warna_id, po_pembelian_batch_id
					) d
				ON a.warna_id = d.warna_id
				AND b.id = d.po_pembelian_batch_id
				AND d.barang_id = a.barang_id
				LEFT JOIN nd_po_pembelian e
				ON a.po_pembelian_id = e.id
				LEFT JOIN nd_toko f
				ON e.toko_id = f.id
				LEFT JOIN nd_supplier g
				ON e.supplier_id = g.id
				WHERE b.id is not null
				GROUP BY a.po_pembelian_batch_id
			)t1
			LEFT JOIN (
				SELECT *
				FROM nd_ppo_table_setting
				WHERE barang_id = $barang_id
				) t2
			ON t1.po_pembelian_batch_id = t2.po_pembelian_batch_id
			WHERE t1.barang_id = $barang_id
			GROUP BY po_pembelian_batch_id
			ORDER BY tanggal,po_pembelian_id,batch_raw asc
			");
		return $query->result();
	}

	function get_stok_ppo($barang_id_pool, $tanggal_akhir, $tanggal_awal, $stok_opname_id, $cond_barang, $lock_po_id){
		$query = $this->db->query("SELECT a.barang_id, a.warna_id, sum(qty_stok) as qty_stok, sum(jumlah_roll_stok) as jumlah_roll_stok, sum(qty_po) as qty_po, 
		group_concat(qty_po_data ) as qty_po_data, sum(qty_sisa_po) as qty_sisa, 
		group_concat(qty_sisa_data ) as qty_sisa_data , group_concat(batch_id ) as batch_id, 
		group_concat(po_pembelian_id ) as po_pembelian_id, b.nama as nama_barang, c.warna_beli as nama_warna, d.nama as nama_satuan, 
		group_concat(harga) as harga_po, group_concat(ifnull(OCKH,0)) as OCKH, group_concat(locked_by) as locked_by, 
		group_concat(qty_beli) as qty_beli, d.nama as nama_satuan, 
		ifnull(t2.qty,0) as qty_ppo, ifnull(t1.qty,0) as qty_current
				FROM((
						SELECT barang_id, warna_id,  SUM(ifnull(if(tanggal_stok is not null,if(tanggal >= tanggal_stok,qty_masuk,0),qty_masuk),0)) - SUM(ifnull(if(tanggal_stok is not null,if(tanggal >= tanggal_stok, qty_keluar, 0),qty_keluar) ,0)) as qty_stok, SUM(ifnull(jumlah_roll_masuk,0)) - SUM( ifnull(jumlah_roll_keluar,0)) as jumlah_roll_stok,0 as qty_po, 0 as qty_po_data, 0 as qty_sisa_po, 0 as qty_sisa_data, 0 as po_pembelian_id, 0 as batch_id, 0 as harga,0 as OCKH,0 as locked_by, 0 as qty_beli
						FROM (
							(
						        SELECT barang_id, warna_id, nd_penjualan_detail.gudang_id, CAST(0 as DECIMAL(15,2)) as qty_masuk, 0 as jumlah_roll_masuk, sum(subqty) as qty_keluar, sum(subjumlah_roll) as jumlah_roll_keluar,3 as tipe, tanggal
						        FROM nd_penjualan_detail
						        LEFT JOIN (
						        	SELECT *
						        	FROM nd_penjualan
						        	WHERE tanggal <= '$tanggal_akhir'
						        	AND tanggal >= '$tanggal_awal'
						        	AND status_aktif = 1
						        	) nd_penjualan
						        ON nd_penjualan_detail.penjualan_id = nd_penjualan.id
						        where nd_penjualan.id is not null
						        GROUP BY barang_id, warna_id, nd_penjualan_detail.gudang_id, tanggal
						    )UNION(
						        SELECT barang_id, warna_id, nd_pengeluaran_stok_lain_detail.gudang_id, CAST(0 as DECIMAL(15,2)) as qty_masuk, 0 as jumlah_roll_masuk, sum(qty) as qty_keluar, sum(jumlah_roll) as jumlah_roll_keluar,9, tanggal
						        FROM nd_pengeluaran_stok_lain_detail
						        LEFT JOIN (
						        	SELECT *
						        	FROM nd_pengeluaran_stok_lain
						        	WHERE tanggal <= '$tanggal_akhir'
						        	AND tanggal >= '$tanggal_awal'
						        	AND status_aktif = 1
						        	) nd_pengeluaran_stok_lain
						        ON nd_pengeluaran_stok_lain_detail.pengeluaran_stok_lain_id = nd_pengeluaran_stok_lain.id
						        LEFT JOIN (
						            SELECT sum(qty* if(jumlah_roll != 0, jumlah_roll,1) ) as qty, sum(jumlah_roll) as jumlah_roll, pengeluaran_stok_lain_detail_id
						            FROM nd_pengeluaran_stok_lain_qty_detail
						            GROUP BY pengeluaran_stok_lain_detail_id
						            ) nd_pengeluaran_stok_lain_qty_detail
						        ON nd_pengeluaran_stok_lain_qty_detail.pengeluaran_stok_lain_detail_id = nd_pengeluaran_stok_lain_detail.id
						        where nd_pengeluaran_stok_lain.id is not null
						        GROUP BY barang_id, warna_id, nd_pengeluaran_stok_lain_detail.gudang_id, tanggal
						    )UNION(
						        SELECT barang_id, warna_id, nd_pembelian.gudang_id, sum(qty) as qty_masuk, sum(jumlah_roll) as jumlah_roll_masuk, CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar, 1 as tipe, tanggal
						        FROM (
						        	SELECT CAST(qty as DECIMAL(15,2)) as qty, jumlah_roll, id, barang_id, warna_id, pembelian_id
						        	FROM nd_pembelian_detail
						        	ORDER BY pembelian_id
						        ) nd_pembelian_detail
						        LEFT JOIN (
						        	SELECT *
						        	FROM nd_pembelian
						        	WHERE tanggal <= '$tanggal_akhir'
						        	AND tanggal >= '$tanggal_awal'
						        	AND status_aktif = 1
						        	) nd_pembelian
						        ON nd_pembelian_detail.pembelian_id = nd_pembelian.id
						        WHERE nd_pembelian.id is not null
						        GROUP BY barang_id, warna_id, nd_pembelian.gudang_id, tanggal
						    )UNION(
						    	SELECT barang_id, warna_id, gudang_id_after, sum(qty) as qty_masuk, sum(jumlah_roll) as jumlah_roll_masuk, CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar,2, tanggal
					        	FROM nd_mutasi_barang
					        	WHERE tanggal <= '$tanggal_akhir'
					        	AND tanggal >= '$tanggal_awal'
					        	AND status_aktif = 1
						        GROUP BY barang_id, warna_id, gudang_id_after, tanggal
						    )UNION(
						    	SELECT barang_id, warna_id, nd_retur_jual_detail.gudang_id, sum(qty) as qty_masuk, sum(jumlah_roll) as jumlah_roll_masuk, CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar,4, tanggal
						        FROM nd_retur_jual_detail
						        LEFT JOIN (
						        	SELECT *
						        	FROM nd_retur_jual
						        	WHERE tanggal <= '$tanggal_akhir'
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
						        GROUP BY barang_id, warna_id,nd_retur_jual_detail.gudang_id, tanggal
						    )UNION(
						        SELECT barang_id, warna_id, gudang_id, sum(qty) as qty_masuk, sum(jumlah_roll) as jumlah_roll_masuk, CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar,5, tanggal
						        	FROM nd_penyesuaian_stok
						        	WHERE tipe_transaksi = 0
					                AND tanggal <= '$tanggal_akhir'
						        	AND tanggal >= '$tanggal_awal'
					                GROUP BY barang_id, warna_id, gudang_id, tanggal
						    )UNION(
						        SELECT  barang_id, warna_id, gudang_id, sum(qty) as qty_masuk, sum(jumlah_roll) as jumlah_roll_masuk, CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar,6, tanggal
					        	FROM nd_penyesuaian_stok
					        	WHERE tanggal <= '$tanggal_akhir'
					        	AND tanggal >= '$tanggal_awal'
					        	AND tipe_transaksi = 1
					        	GROUP BY barang_id, warna_id, gudang_id, tanggal
						    )UNION(
						        SELECT barang_id, warna_id, gudang_id, CAST(0 as DECIMAL(15,2)) as qty_masuk, 0 as jumlah_roll_masuk, sum(qty) as qty_keluar, sum(jumlah_roll) as jumlah_roll_keluar,7, tanggal
					        	FROM nd_penyesuaian_stok
					        	WHERE tanggal <= '$tanggal_akhir'
					        	AND tanggal >= '$tanggal_awal'
					        	AND tipe_transaksi = 2
								GROUP BY barang_id, warna_id, gudang_id, tanggal
						    )UNION(
						    	SELECT barang_id, warna_id, gudang_id_before, CAST(0 as DECIMAL(15,2)) as qty_masuk, 0 as jumlah_roll_masuk, sum(qty) as qty_keluar, sum(jumlah_roll) as jumlah_roll_keluar,8, tanggal
					        	FROM nd_mutasi_barang
					        	WHERE tanggal <= '$tanggal_akhir'	
							    AND tanggal >= '$tanggal_awal'
					        	AND status_aktif = 1
								GROUP BY barang_id, warna_id, gudang_id_before, tanggal
						    )UNION(
						    	SELECT barang_id, warna_id, gudang_id, sum(qty*jumlah_roll), sum(jumlah_roll) as jumlah_roll_masuk, 0 as qty_keluar, 0 as jumlah_roll_keluar,10, tanggal
					        	FROM nd_stok_opname_detail t1 
								LEFT JOIN (
									SELECT *
									FROM nd_stok_opname 
									WHERE tanggal <= '$tanggal_akhir'	
								    AND tanggal >= '$tanggal_awal'
								    AND status_aktif = 1
									)t2
								ON t1.stok_opname_id = t2.id
								WHERE t2.id is not null
				        		AND warna_id > 0
					        	GROUP BY barang_id, warna_id, gudang_id, t2.id
						    )
						)t1
						LEFT JOIN (
							SELECT barang_id as barang_id_stok, warna_id as warna_id_stok,gudang_id as gudang_id_stok, MAX(tanggal) as tanggal_stok
						    FROM (
						    	SELECT stok_opname_id, barang_id,warna_id, gudang_id
						    	FROM nd_stok_opname_detail
				        		WHERE warna_id > 0
						    	GROUP BY barang_id, warna_id, gudang_id, stok_opname_id
							) tA
							LEFT JOIN (
								SELECT *
								FROM nd_stok_opname
								WHERE tanggal <= '$tanggal_akhir'	
							    AND tanggal >= '$tanggal_awal'
								AND status_aktif = 1
							) tB
							ON tA.stok_opname_id = tB.id
							WHERE tB.id is not null
							GROUP BY barang_id, warna_id, gudang_id
						) t2
						ON t1.barang_id = t2.barang_id_stok
						AND t1.warna_id = t2.warna_id_stok
						AND t1.gudang_id = t2.gudang_id_stok
						GROUP BY barang_id, warna_id
					)UNION(
						SELECT barang_id, warna_id, 0 ,0, sum(qty_po), group_concat(qty_po) ,sum(qty_sisa), group_concat(qty_sisa_data), group_concat(po_pembelian_id) as po_pembelian_id, group_concat(batch_id) as batch_id, group_concat(harga), group_concat(ifnull(OCKH,0)), group_concat(locked_by) , group_concat(qty_beli)
						FROM (
							SELECT sum(a.qty-ifnull(qty_beli,0)) as qty_sisa, a.barang_id as barang_id, a.warna_id, a.po_pembelian_id, group_concat(a.po_pembelian_batch_id) as batch_id, group_concat(if(locked_by is null,a.qty-ifnull(qty_beli,0),0)) as qty_sisa_data, group_concat(harga) as harga, group_concat(a.qty) as qty_po, group_concat(ifnull(OCKH,0) ) as OCKH, group_concat(ifnull(qty_beli,0)) as qty_beli, group_concat(ifnull(locked_by,0)) as locked_by
							FROM (
								SELECT t1.id, po_pembelian_detail_id, po_pembelian_batch_id, if(tipe_barang = 1, t2.barang_id, barang_id_baru) as barang_id, po_pembelian_id, t1.qty, warna_id, if(tipe_barang != 1 AND tipe_barang != 2, harga_baru, harga) as harga, OCKH, locked_by
								FROM (
									SELECT *
									FROM nd_po_pembelian_warna
									) t1
								LEFT JOIN nd_po_pembelian_detail t2
								ON t1.po_pembelian_detail_id = t2.id
								) a
							LEFT JOIN (
								SELECT *
								FROM nd_po_pembelian_batch
								WHERE status != 0
								) b
							ON a.po_pembelian_batch_id = b.id
							LEFT JOIN (
								SELECT barang_id, warna_id, sum(qty) as qty_beli, po_pembelian_batch_id 
								FROM nd_pembelian_detail t1
								LEFT JOIN nd_pembelian t2
								ON t1.pembelian_id = t2.id
								GROUP BY barang_id, warna_id, po_pembelian_batch_id
								) d
							ON a.warna_id = d.warna_id
							AND b.id = d.po_pembelian_batch_id
							AND d.barang_id = a.barang_id
							LEFT JOIN nd_po_pembelian e
							ON a.po_pembelian_id = e.id
							WHERE batch != 0
							AND b.id is not null
							GROUP BY a.barang_id, warna_id, a.po_pembelian_id
						)t1
						LEFT JOIN nd_po_pembelian t2
						ON t1.po_pembelian_id = t2.id
						GROUP BY barang_id, warna_id
					)UNION(
						SELECT barang_id, warna_id, 0 ,0, 0, '' ,0, '', '', '', '', '', '' ,''
						FROM nd_ppo_qty_current t1
						WHERE barang_id IN ($barang_id_pool)
					)
				) a
				LEFT JOIN (
					SELECT barang_id as barang_id_ppo, warna_id as warna_id_ppo, qty
					FROM nd_ppo_qty_current
					) t1
				ON a.barang_id = t1.barang_id_ppo
				AND a.warna_id = t1.warna_id_ppo
				LEFT JOIN (
					SELECT t2.id, qty, warna_id as warna_id_ppo, barang_id as barang_id_ppo, tanggal
					FROM nd_ppo_lock_detail t1
					LEFT JOIN nd_ppo_lock t2
					ON t1.ppo_lock_id = t2.id
					WHERE ppo_lock_id = $lock_po_id
					) t2
				ON a.barang_id = t2.barang_id_ppo
				AND a.warna_id = t2.warna_id_ppo
				LEFT JOIN nd_barang b
				ON a.barang_id = b.id
				LEFT JOIN nd_warna c
				ON a.warna_id = c.id
				LEFT JOIN nd_satuan d
				ON b.satuan_id = d.id
				$cond_barang
				GROUP BY barang_id, warna_id
				ORDER BY c.warna_jual
			");

		return $query->result();
	}

	function get_stok_ppo2($barang_id_pool, $tanggal_akhir, $tanggal_awal, $stok_opname_id, $cond_barang, $lock_po_id){
		$tanggal_akhir = $tanggal_akhir.' 23:59:59';
		$tanggal_awal = $tanggal_awal.' 00:00:00';
		$query = $this->db->query("SELECT a.barang_id, a.warna_id, sum(qty_stok) as qty_stok, sum(jumlah_roll_stok) as jumlah_roll_stok, sum(qty_po) as qty_po, group_concat(qty_po_data ) as qty_po_data, sum(qty_sisa_po) as qty_sisa, group_concat(qty_sisa_data ) as qty_sisa_data , group_concat(batch_id ) as batch_id, group_concat(po_pembelian_id ) as po_pembelian_id, b.nama as nama_barang, c.warna_beli as nama_warna, d.nama as nama_satuan, group_concat(harga) as harga_po, group_concat(ifnull(OCKH,0)) as OCKH, group_concat(locked_by) as locked_by, group_concat(qty_beli) as qty_beli, d.nama as nama_satuan, ifnull(t2.qty,0) as qty_ppo, ifnull(t1.qty,0) as qty_current
				FROM((
						SELECT barang_id, warna_id,  
							SUM(ifnull(if(tanggal_stok is not null,if(tanggal >= tanggal_stok,qty_masuk,0),qty_masuk),0)) - 
							SUM(ifnull(if(tanggal_stok is not null,if(tanggal >= tanggal_stok, qty_keluar, 0),qty_keluar) ,0)) as qty_stok, 
							SUM(ifnull(jumlah_roll_masuk,0)) - SUM( ifnull(jumlah_roll_keluar,0)) as jumlah_roll_stok,
							0 as qty_po, 0 as qty_po_data, 0 as qty_sisa_po, 0 as qty_sisa_data, 
							0 as po_pembelian_id, 0 as batch_id, 0 as harga,0 as OCKH,0 as locked_by, 0 as qty_beli
						FROM (
							(
						        SELECT barang_id, warna_id, nd_penjualan_detail.gudang_id, CAST(0 as DECIMAL(15,2)) as qty_masuk, 0 as jumlah_roll_masuk, 
									sum(subqty) as qty_keluar, sum(subjumlah_roll) as jumlah_roll_keluar,3 as tipe, closed_date as tanggal
						        FROM nd_penjualan_detail
						        LEFT JOIN (
						        	SELECT *
						        	FROM nd_penjualan
						        	WHERE closed_date <= '$tanggal_akhir'
						        	AND closed_date >= '$tanggal_awal'
						        	AND status_aktif = 1
						        	) nd_penjualan
						        ON nd_penjualan_detail.penjualan_id = nd_penjualan.id
						        where nd_penjualan.id is not null
						        GROUP BY barang_id, warna_id, nd_penjualan_detail.gudang_id, tanggal
						    )UNION(
						        SELECT barang_id, warna_id, nd_pengeluaran_stok_lain_detail.gudang_id, CAST(0 as DECIMAL(15,2)) as qty_masuk, 0 as jumlah_roll_masuk, sum(qty) as qty_keluar, sum(jumlah_roll) as jumlah_roll_keluar,9,nd_pengeluaran_stok_lain.created_at as tanggal
						        FROM nd_pengeluaran_stok_lain_detail
						        LEFT JOIN (
						        	SELECT *
						        	FROM nd_pengeluaran_stok_lain
						        	WHERE created_at <= '$tanggal_akhir'
						        	AND created_at >= '$tanggal_awal'
						        	AND status_aktif = 1
						        	) nd_pengeluaran_stok_lain
						        ON nd_pengeluaran_stok_lain_detail.pengeluaran_stok_lain_id = nd_pengeluaran_stok_lain.id
						        LEFT JOIN (
						            SELECT sum(qty* if(jumlah_roll != 0, jumlah_roll,1) ) as qty, sum(jumlah_roll) as jumlah_roll, pengeluaran_stok_lain_detail_id
						            FROM nd_pengeluaran_stok_lain_qty_detail
						            GROUP BY pengeluaran_stok_lain_detail_id
						            ) nd_pengeluaran_stok_lain_qty_detail
						        ON nd_pengeluaran_stok_lain_qty_detail.pengeluaran_stok_lain_detail_id = nd_pengeluaran_stok_lain_detail.id
						        where nd_pengeluaran_stok_lain.id is not null
						        GROUP BY barang_id, warna_id, nd_pengeluaran_stok_lain_detail.gudang_id, tanggal
						    )UNION(
						        SELECT barang_id, warna_id, nd_pembelian.gudang_id, sum(qty) as qty_masuk, sum(jumlah_roll) as jumlah_roll_masuk, CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar, 1 as tipe, created_at as tanggal
						        FROM (
						        	SELECT CAST(qty as DECIMAL(15,2)) as qty, jumlah_roll, id, barang_id, warna_id, pembelian_id
						        	FROM nd_pembelian_detail
						        	ORDER BY pembelian_id
						        ) nd_pembelian_detail
						        LEFT JOIN (
						        	SELECT *
						        	FROM nd_pembelian
						        	WHERE created_at <= '$tanggal_akhir'
						        	AND created_at >= '$tanggal_awal'
						        	AND status_aktif = 1
						        	) nd_pembelian
						        ON nd_pembelian_detail.pembelian_id = nd_pembelian.id
						        WHERE nd_pembelian.id is not null
						        GROUP BY barang_id, warna_id, nd_pembelian.gudang_id, tanggal
						    )UNION(
						    	SELECT barang_id, warna_id, gudang_id_after, sum(qty) as qty_masuk, sum(jumlah_roll) as jumlah_roll_masuk, CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar,2, created_at as tanggal
					        	FROM nd_mutasi_barang
					        	WHERE created_at <= '$tanggal_akhir'
					        	AND created_at >= '$tanggal_awal'
					        	AND status_aktif = 1
						        GROUP BY barang_id, warna_id, gudang_id_after, tanggal
						    )UNION(
						    	SELECT barang_id, warna_id, nd_retur_jual_detail.gudang_id, sum(qty) as qty_masuk, sum(jumlah_roll) as jumlah_roll_masuk, CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar,4,created_at as tanggal
						        FROM nd_retur_jual_detail
						        LEFT JOIN (
						        	SELECT *
						        	FROM nd_retur_jual
						        	WHERE created_at <= '$tanggal_akhir'
						        	AND created_at >= '$tanggal_awal'
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
						        GROUP BY barang_id, warna_id,nd_retur_jual_detail.gudang_id, tanggal
						    )UNION(
						        SELECT barang_id, warna_id, gudang_id, sum(qty) as qty_masuk, sum(jumlah_roll) as jumlah_roll_masuk, CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar,5,created_at as tanggal
						        	FROM nd_penyesuaian_stok
						        	WHERE tipe_transaksi = 0
					                AND created_at <= '$tanggal_akhir'
						        	AND created_at >= '$tanggal_awal'
					                GROUP BY barang_id, warna_id, gudang_id, tanggal
						    )UNION(
						        SELECT  barang_id, warna_id, gudang_id, sum(qty) as qty_masuk, sum(jumlah_roll) as jumlah_roll_masuk, CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar,6, created_at as tanggal
					        	FROM nd_penyesuaian_stok
					        	WHERE created_at <= '$tanggal_akhir'
					        	AND created_at >= '$tanggal_awal'
					        	AND tipe_transaksi = 1
					        	GROUP BY barang_id, warna_id, gudang_id, tanggal
						    )UNION(
						        SELECT barang_id, warna_id, gudang_id, CAST(0 as DECIMAL(15,2)) as qty_masuk, 0 as jumlah_roll_masuk, sum(qty) as qty_keluar, sum(jumlah_roll) as jumlah_roll_keluar,7,created_at as tanggal
					        	FROM nd_penyesuaian_stok
					        	WHERE created_at <= '$tanggal_akhir'
					        	AND created_at >= '$tanggal_awal'
					        	AND tipe_transaksi = 2
								GROUP BY barang_id, warna_id, gudang_id, tanggal
						    )UNION(
						    	SELECT barang_id, warna_id, gudang_id_before, CAST(0 as DECIMAL(15,2)) as qty_masuk, 0 as jumlah_roll_masuk, sum(qty) as qty_keluar, sum(jumlah_roll) as jumlah_roll_keluar,8,created_at as tanggal
					        	FROM nd_mutasi_barang
					        	WHERE created_at <= '$tanggal_akhir'	
							    AND created_at >= '$tanggal_awal'
					        	AND status_aktif = 1
								GROUP BY barang_id, warna_id, gudang_id_before, tanggal
						    )UNION(
						    	SELECT barang_id, warna_id, gudang_id, sum(qty*jumlah_roll), sum(jumlah_roll) as jumlah_roll_masuk, 0 as qty_keluar, 0 as jumlah_roll_keluar,10,created_at as tanggal
					        	FROM nd_stok_opname_detail t1 
								LEFT JOIN (
									SELECT *
									FROM nd_stok_opname 
									WHERE created_at <= '$tanggal_akhir'	
								    AND created_at >= '$tanggal_awal'
								    AND status_aktif = 1
									)t2
								ON t1.stok_opname_id = t2.id
								WHERE t2.id is not null
				        		AND warna_id > 0
					        	GROUP BY barang_id, warna_id, gudang_id, t2.id
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
								WHERE created_at <= '$tanggal_akhir'	
							    AND created_at >= '$tanggal_awal'
								AND status_aktif = 1
							) tB
							ON tA.stok_opname_id = tB.id
							WHERE tB.id is not null
							GROUP BY barang_id, warna_id, gudang_id
						) t2
						ON t1.barang_id = t2.barang_id_stok
						AND t1.warna_id = t2.warna_id_stok
						AND t1.gudang_id = t2.gudang_id_stok
						GROUP BY barang_id, warna_id
					)UNION(
						SELECT barang_id, warna_id, 0 ,0, sum(qty_po), group_concat(qty_po) ,sum(qty_sisa), group_concat(qty_sisa_data), group_concat(po_pembelian_id) as po_pembelian_id, group_concat(batch_id) as batch_id, group_concat(harga), group_concat(ifnull(OCKH,0)), group_concat(locked_by) , group_concat(qty_beli)
						FROM (
							SELECT sum(a.qty-ifnull(qty_beli,0)) as qty_sisa, a.barang_id as barang_id, a.warna_id, a.po_pembelian_id, group_concat(a.po_pembelian_batch_id) as batch_id, group_concat(if(locked_by is null,a.qty-ifnull(qty_beli,0),0)) as qty_sisa_data, group_concat(harga) as harga, group_concat(a.qty) as qty_po, group_concat(ifnull(OCKH,0) ) as OCKH, group_concat(ifnull(qty_beli,0)) as qty_beli, group_concat(ifnull(locked_by,0)) as locked_by
							FROM (
								SELECT t1.id, po_pembelian_detail_id, po_pembelian_batch_id, if(tipe_barang = 1, t2.barang_id, barang_id_baru) as barang_id, po_pembelian_id, t1.qty, warna_id, if(tipe_barang != 1 AND tipe_barang != 2, harga_baru, harga) as harga, OCKH, locked_by
								FROM (
									SELECT *
									FROM nd_po_pembelian_warna
									) t1
								LEFT JOIN nd_po_pembelian_detail t2
								ON t1.po_pembelian_detail_id = t2.id
								) a
							LEFT JOIN (
								SELECT *
								FROM nd_po_pembelian_batch
								WHERE status != 0
								) b
							ON a.po_pembelian_batch_id = b.id
							LEFT JOIN (
								SELECT barang_id, warna_id, sum(qty) as qty_beli, po_pembelian_batch_id 
								FROM nd_pembelian_detail t1
								LEFT JOIN nd_pembelian t2
								ON t1.pembelian_id = t2.id
								GROUP BY barang_id, warna_id, po_pembelian_batch_id
								) d
							ON a.warna_id = d.warna_id
							AND b.id = d.po_pembelian_batch_id
							AND d.barang_id = a.barang_id
							LEFT JOIN nd_po_pembelian e
							ON a.po_pembelian_id = e.id
							WHERE batch != 0
							AND b.id is not null
							GROUP BY a.barang_id, warna_id, a.po_pembelian_id
						)t1
						LEFT JOIN nd_po_pembelian t2
						ON t1.po_pembelian_id = t2.id
						GROUP BY barang_id, warna_id
					)UNION(
						SELECT barang_id, warna_id, 0 ,0, 0, '' ,0, '', '', '', '', '', '' ,''
						FROM nd_ppo_qty_current t1
						WHERE barang_id IN ($barang_id_pool)
					)
				) a
				LEFT JOIN (
					SELECT barang_id as barang_id_ppo, warna_id as warna_id_ppo, qty
					FROM nd_ppo_qty_current
					) t1
				ON a.barang_id = t1.barang_id_ppo
				AND a.warna_id = t1.warna_id_ppo
				LEFT JOIN (
					SELECT t2.id, qty, warna_id as warna_id_ppo, barang_id as barang_id_ppo, tanggal
					FROM nd_ppo_lock_detail t1
					LEFT JOIN nd_ppo_lock t2
					ON t1.ppo_lock_id = t2.id
					WHERE ppo_lock_id = $lock_po_id
					) t2
				ON a.barang_id = t2.barang_id_ppo
				AND a.warna_id = t2.warna_id_ppo
				LEFT JOIN nd_barang b
				ON a.barang_id = b.id
				LEFT JOIN nd_warna c
				ON a.warna_id = c.id
				LEFT JOIN nd_satuan d
				ON b.satuan_id = d.id
				$cond_barang
				GROUP BY barang_id, warna_id
				ORDER BY c.warna_jual
			");

		return $query->result();
	}

	function get_penjualan_by_barang($barang_id, $tanggal_awal, $tanggal_akhir){
		$query = $this->db->query("SELECT barang_id, warna_id, tanggal, no_faktur, harga_jual, qty, jumlah_roll, penjualan_id, customer_id
			FROM (
				SELECT *
				FROM nd_penjualan_detail
				WHERE barang_id = $barang_id
			)t1
			LEFT JOIN (
				SELECT *
				FROM nd_penjualan
				WHERE tanggal >= '$tanggal_awal'
				AND tanggal <= '$tanggal_akhir'
				AND status_aktif = 1
			)t2
			ON t1.penjualan_id = t2.id
			LEFT JOIN (
				SELECT sum(qty*jumlah_roll) as qty, sum(jumlah_roll) as jumlah_roll, penjualan_detail_id
				FROM nd_penjualan_qty_detail
				GROUP BY penjualan_detail_id
				)t3
			ON t1.id = t3.penjualan_detail_id
			WHERE t2.id is not null
			ORDER BY tanggal asc
		");
		
		return $query->result();
	}

	function stok_by_po($po_pembelian_batch_id, $barang_id, $tanggal_akhir){
		$query = $this->db->query("SELECT tanggal, sum(qty) as qty, sum(jumlah_roll) as jumlah_roll, barang_id, warna_id, po_pembelian_batch_id, MIN(tanggal) as tanggal_first, MAX(tanggal) as tanggal_last
			FROM (
				SELECT *
				FROM nd_pembelian_detail
				WHERE barang_id = $barang_id
				)t1
			LEFT JOIN(
				SELECT *
				FROM nd_pembelian
				WHERE po_pembelian_batch_id IN ($po_pembelian_batch_id)
				AND status_aktif = 1
				AND tanggal <= '$tanggal_akhir'
			)t2
			ON t1.pembelian_id = t2.id
			WHERE t2.id is not null
			GROUP BY po_pembelian_batch_id, barang_id, warna_id
			ORDER BY tanggal, t2.id asc
		");
		return $query->result();
	}

	function stok_by_po_by_tanggal($po_pembelian_batch_id, $barang_id, $tanggal_akhir){
		$query = $this->db->query("SELECT tanggal, sum(qty) as qty, sum(jumlah_roll) as jumlah_roll, barang_id, warna_id, po_pembelian_batch_id, MIN(tanggal) as tanggal_first, MAX(tanggal) as tanggal_last, group_concat(pembelian_id) as pembelian_id, group_concat(no_faktur) as no_faktur, group_concat(qty) as qty_data
			FROM (
				SELECT *
				FROM nd_pembelian_detail
				WHERE barang_id = $barang_id
				)t1
			LEFT JOIN(
				SELECT *
				FROM nd_pembelian
				WHERE po_pembelian_batch_id IN ($po_pembelian_batch_id)
				AND status_aktif = 1
				AND tanggal <= '$tanggal_akhir'
			)t2
			ON t1.pembelian_id = t2.id
			WHERE t2.id is not null
			GROUP BY po_pembelian_batch_id, barang_id, warna_id, tanggal
			ORDER BY tanggal, t2.id asc
		");
		return $query->result();
	}

	function get_penyesuaian_stok_awal($barang_id){
		$query = $this->db->query("SELECT barang_id, warna_id, sum(ifnull(qty,0)) as qty, sum(ifnull(jumlah_roll,0) ) as jumlah_roll
			FROM ((
					SELECT barang_id, warna_id, sum(qty) as qty, sum(jumlah_roll) as jumlah_roll
					FROM nd_penyesuaian_stok
					WHERE barang_id = $barang_id
					AND tipe_transaksi = 0
					GROUP BY barang_id, warna_id
				)UNION(
					SELECT barang_id, warna_id, sum(qty) as qty, sum(jumlah_roll) as jumlah_roll
					FROM (
						SELECT * 
						FROM nd_pembelian_detail
						WHERE barang_id = $barang_id
					)t1
					LEFT JOIN (
						SELECT *
						FROM nd_pembelian
						WHERE status_aktif = 1
						AND (po_pembelian_batch_id = ''
						OR po_pembelian_batch_id is null
						OR po_pembelian_batch_id = 0)
					)t2
					ON t1.pembelian_id = t2.id
					WHERE t2.id is not null
					GROUP BY barang_id, warna_id
				)
			) result
			GROUP BY barang_id, warna_id
		");
		return $query->result();
	}

	function get_stok_by_opname($stok_opname_id, $barang_id){
		$query = $this->db->query("SELECT barang_id, warna_id, sum(qty) as qty, sum(jumlah_roll) as jumlah_roll
			FROM nd_stok_opname_detail
			WHERE barang_id = $barang_id
			AND stok_opname_id = $stok_opname_id
    		AND warna_id > 0
			GROUP BY barang_id, warna_id
		");
		return $query->result();
	}

	function po_pembelian_untuk_ppo($barang_id, $ppo_lock_id){

		$query = $this->db->query("SELECT t1.*, ppo_qty
			FROM (
				SELECT tC.po_number, po_pembelian_id, sum(tA.qty) - sum(ifnull(tB.qty,0)) as sisa_qty, tanggal, barang_id, batch, tA.harga, tA.id as po_pembelian_detail_id
				FROM (
					SELECT *
					FROM nd_po_pembelian_detail
					WHERE barang_id = $barang_id
					)tA
				LEFT JOIN (
					SELECT sum(qty) as qty, po_pembelian_detail_id
					FROM nd_po_pembelian_warna
					GROUP BY po_pembelian_detail_id
					)tB
				ON tB.po_pembelian_detail_id = tA.id
				LEFT JOIN (
					SELECT concat(if(t_1.tanggal >= '2019-09-27', concat(ifnull(pre_po,''),t_2.kode,'/',DATE_FORMAT(t_1.tanggal,'%y'),'/',LPAD(po_number,3,'0')) ,concat(LPAD(po_number,3,'0'),'/',DATE_FORMAT(t_1.tanggal,'%m'),'/',DATE_FORMAT(t_1.tanggal,'%y'))) ) as po_number, t_1.id, t_1.tanggal, batch
					FROM nd_po_pembelian t_1
					LEFT JOIN nd_supplier t_2
					ON t_1.supplier_id = t_2.id
					LEFT JOIN nd_toko t_3
					ON t_1.toko_id = t_3.id
					LEFT JOIN (
						SELECT po_pembelian_id, max(batch) as batch, tanggal
						FROM nd_po_pembelian_batch
						GROUP BY po_pembelian_id
						) t_4
					ON t_4.po_pembelian_id = t_1.id
					WHERE t_1.po_number != ''
					AND t_1.po_number is not null
					AND t_1.status_aktif = 1
				) tC
				ON tA.po_pembelian_id = tC.id
				WHERE tA.qty - ifnull(tB.qty,0) > 0
				AND tC.id is not null
				GROUP BY po_pembelian_id, barang_id, harga
			) t1
			LEFT JOIN (
				SELECT sum(t_1.qty)as ppo_qty, t_2.barang_id
				FROM nd_ppo_lock_detail t_1
				LEFT JOIN nd_ppo_lock t_2
				ON t_1.ppo_lock_id = t_2.id
				WHERE ppo_lock_id = $ppo_lock_id 
				GROUP BY ppo_lock_id
				) t2
			ON t1.barang_id = t2.barang_id
			ORDER BY (t1.sisa_qty - ppo_qty) asc
		");
		return $query->result();
	}

//==============================Stok & Kartu Stok======================================

	function get_stok_barang_list($select, $tanggal, $tanggal_awal, $stok_opname_id){
		$query = $this->db->query("SELECT urutan,tbl_b.nama as nama_barang,tbl_b.nama_jual as nama_barang_jual, tbl_c.warna_beli as nama_warna,tbl_c.warna_jual as nama_warna_jual, barang_id, warna_id, tbl_b.status_aktif as status_barang, tbl_d.nama as nama_satuan, satuan_id
				$select
				FROM(
								(
							        SELECT barang_id, warna_id, nd_penjualan_detail.gudang_id, CAST(0 as DECIMAL(15,2)) as qty_masuk, 0 as jumlah_roll_masuk, sum(qty) as qty_keluar, sum(jumlah_roll) as jumlah_roll_keluar,3, MAX(tanggal) as last_edit
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
							        SELECT barang_id, warna_id, nd_pengeluaran_stok_lain_detail.gudang_id, CAST(0 as DECIMAL(15,2)) as qty_masuk, 0 as jumlah_roll_masuk, sum(qty) as qty_keluar, sum(jumlah_roll) as jumlah_roll_keluar,20, MAX(tanggal) as last_edit
							        FROM nd_pengeluaran_stok_lain_detail
							        LEFT JOIN (
							        	SELECT *
							        	FROM nd_pengeluaran_stok_lain
							        	WHERE tanggal <= '$tanggal'
							        	AND tanggal >= '$tanggal_awal'
							        	AND status_aktif = 1
							        	) nd_pengeluaran_stok_lain
							        ON nd_pengeluaran_stok_lain_detail.pengeluaran_stok_lain_id = nd_pengeluaran_stok_lain.id
							        LEFT JOIN (
							            SELECT sum(qty* if(jumlah_roll != 0, jumlah_roll,1) ) as qty, sum(jumlah_roll) as jumlah_roll, pengeluaran_stok_lain_detail_id
							            FROM nd_pengeluaran_stok_lain_qty_detail
							            GROUP BY pengeluaran_stok_lain_detail_id
							            ) nd_pengeluaran_stok_lain_qty_detail
							        ON nd_pengeluaran_stok_lain_qty_detail.pengeluaran_stok_lain_detail_id = nd_pengeluaran_stok_lain_detail.id
							        where nd_pengeluaran_stok_lain.id is not null
							        GROUP BY barang_id, warna_id, nd_pengeluaran_stok_lain_detail.gudang_id
							    )UNION(
								        SELECT barang_id, warna_id, nd_pembelian.gudang_id, sum(qty) as qty_masuk, sum(jumlah_roll) as jumlah_roll_masuk, CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar, 1 as tipe, tanggal as last_edit
								        FROM (
								        	SELECT CAST(qty as DECIMAL(15,2)) as qty, jumlah_roll, id, barang_id, warna_id, pembelian_id
								        	FROM nd_pembelian_detail
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
							    	SELECT barang_id, warna_id, gudang_id_after, sum(qty) as qty_masuk, sum(jumlah_roll) as jumlah_roll_masuk, CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar,2, tanggal as last_edit
						        	FROM nd_mutasi_barang
						        	WHERE tanggal <= '$tanggal'
						        	AND tanggal >= '$tanggal_awal'
						        	AND status_aktif = 1
							        GROUP BY barang_id, warna_id, gudang_id_after
							    )UNION(
							    	SELECT barang_id, warna_id, nd_retur_jual_detail.gudang_id, sum(qty) as qty_masuk, sum(jumlah_roll) as jumlah_roll_masuk, CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar,4, tanggal as last_edit
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
							    	SELECT barang_id, warna_id, nd_retur_beli_detail.gudang_id,0,0, sum(qty) , sum(jumlah_roll) ,19, tanggal as last_edit
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
							        SELECT barang_id, warna_id, gudang_id, sum(qty) as qty_masuk, sum(jumlah_roll) as jumlah_roll_masuk, CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar,5, tanggal as last_edit
							        	FROM nd_penyesuaian_stok
							        	WHERE tipe_transaksi = 0
				                        AND tanggal <= '$tanggal'
							        	AND tanggal >= '$tanggal_awal'
				                        GROUP BY barang_id, warna_id, gudang_id
							    )UNION(
							        SELECT  barang_id, warna_id, gudang_id, sum(qty) as qty_masuk, sum(jumlah_roll) as jumlah_roll_masuk, CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar,6, tanggal as last_edit
						        	FROM nd_penyesuaian_stok
						        	WHERE tanggal <= '$tanggal'
						        	AND tanggal >= '$tanggal_awal'
						        	AND tipe_transaksi = 1
						        	GROUP BY barang_id, warna_id, gudang_id
							    )UNION(
							        SELECT barang_id, warna_id, gudang_id, CAST(0 as DECIMAL(15,2)) as qty_masuk, 0 as jumlah_roll_masuk, sum(qty) as qty_keluar, sum(jumlah_roll) as jumlah_roll_keluar,7, tanggal as last_edit
						        	FROM nd_penyesuaian_stok
						        	WHERE tanggal <= '$tanggal'
						        	AND tanggal >= '$tanggal_awal'
						        	AND tipe_transaksi = 2
									GROUP BY barang_id, warna_id, gudang_id
							    )UNION(
							    	SELECT barang_id, warna_id, gudang_id_before, CAST(0 as DECIMAL(15,2)) as qty_masuk, 0 as jumlah_roll_masuk, sum(qty) as qty_keluar, sum(jumlah_roll) as jumlah_roll_keluar,8, tanggal as last_edit
						        	FROM nd_mutasi_barang
						        	WHERE tanggal <= '$tanggal'	
								    AND tanggal >= '$tanggal_awal'
						        	AND status_aktif = 1
									GROUP BY barang_id, warna_id, gudang_id_before
							    )UNION(
							    	SELECT barang_id, warna_id, gudang_id, sum(qty), sum(jumlah_roll) as jumlah_roll_masuk, 0 as qty_keluar, 0 as jumlah_roll_keluar,9, tanggal as last_edit 
						        	FROM nd_stok_opname_detail t1 
									LEFT JOIN nd_stok_opname t2
									ON t1.stok_opname_id = t2.id
						        	WHERE stok_opname_id = $stok_opname_id
					        		AND warna_id > 0
						        	GROUP BY barang_id, warna_id, gudang_id
							    )
				) tbl_a
				LEFT JOIN nd_barang tbl_b
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
				GROUP BY barang_id, warna_id
				ORDER BY nama_jual, urutan asc");
		
		return $query->result();
		// return $this->db->last_query();
	}

	function get_stok_barang_list_temp($select, $tanggal, $stok_opname_id, $tanggal_awal, $select2){
		$query = $this->db->query("SELECT CAST(concat(urutan_barang,LPAD(urutan,3,'0')) as UNSIGNED) as urutan,concat(tbl_b.nama_jual,' ',warna_jual) as nama_barang_jual, if(tbl_b.status_aktif = 1,'aktif', 'non aktif') as status_aktif $select2, tbl_a.*, warna_jual as nama_warna_jual
						FROM(
							SELECT barang_id, warna_id $select,MAX(last_edit) as last_edit 
							FROM (
								(
							        SELECT barang_id, warna_id, nd_penjualan_detail.gudang_id, CAST(0 as DECIMAL(15,2)) as qty_masuk, 0 as jumlah_roll_masuk, sum(qty) as qty_keluar, sum(jumlah_roll) as jumlah_roll_keluar,3, MAX(tanggal) as last_edit
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
							        SELECT barang_id, warna_id, nd_pengeluaran_stok_lain_detail.gudang_id, CAST(0 as DECIMAL(15,2)) as qty_masuk, 0 as jumlah_roll_masuk, sum(qty) as qty_keluar, sum(jumlah_roll) as jumlah_roll_keluar,20, MAX(tanggal) as last_edit
							        FROM nd_pengeluaran_stok_lain_detail
							        LEFT JOIN (
							        	SELECT *
							        	FROM nd_pengeluaran_stok_lain
							        	WHERE tanggal <= '$tanggal'
							        	AND tanggal >= '$tanggal_awal'
							        	AND status_aktif = 1
							        	) nd_pengeluaran_stok_lain
							        ON nd_pengeluaran_stok_lain_detail.pengeluaran_stok_lain_id = nd_pengeluaran_stok_lain.id
							        LEFT JOIN (
							            SELECT sum(qty* if(jumlah_roll != 0, jumlah_roll,1) ) as qty, sum(jumlah_roll) as jumlah_roll, pengeluaran_stok_lain_detail_id
							            FROM nd_pengeluaran_stok_lain_qty_detail
							            GROUP BY pengeluaran_stok_lain_detail_id
							            ) nd_pengeluaran_stok_lain_qty_detail
							        ON nd_pengeluaran_stok_lain_qty_detail.pengeluaran_stok_lain_detail_id = nd_pengeluaran_stok_lain_detail.id
							        where nd_pengeluaran_stok_lain.id is not null
							        GROUP BY barang_id, warna_id, nd_pengeluaran_stok_lain_detail.gudang_id
							    )UNION(
								        SELECT barang_id, warna_id, nd_pembelian.gudang_id, sum(qty) as qty_masuk, sum(jumlah_roll) as jumlah_roll_masuk, CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar, 1 as tipe, tanggal as last_edit
								        FROM (
								        	SELECT CAST(qty as DECIMAL(15,2)) as qty, jumlah_roll, id, barang_id, warna_id, pembelian_id
								        	FROM nd_pembelian_detail
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
							    	SELECT barang_id, warna_id, gudang_id_after, sum(qty) as qty_masuk, sum(jumlah_roll) as jumlah_roll_masuk, CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar,2, tanggal as last_edit
						        	FROM nd_mutasi_barang
						        	WHERE tanggal <= '$tanggal'
						        	AND tanggal >= '$tanggal_awal'
						        	AND status_aktif = 1
							        GROUP BY barang_id, warna_id, gudang_id_after
							    )UNION(
							    	SELECT barang_id, warna_id, nd_retur_jual_detail.gudang_id, sum(qty) as qty_masuk, sum(jumlah_roll) as jumlah_roll_masuk, CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar,4, tanggal as last_edit
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
							    	SELECT barang_id, warna_id, nd_retur_beli_detail.gudang_id,0,0, sum(qty) , sum(jumlah_roll) ,19, tanggal as last_edit
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
							        SELECT barang_id, warna_id, gudang_id, sum(qty) as qty_masuk, sum(jumlah_roll) as jumlah_roll_masuk, CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar,5, tanggal as last_edit
							        	FROM nd_penyesuaian_stok
							        	WHERE tipe_transaksi = 0
				                        AND tanggal <= '$tanggal'
							        	AND tanggal >= '$tanggal_awal'
				                        GROUP BY barang_id, warna_id, gudang_id
							    )UNION(
							        SELECT  barang_id, warna_id, gudang_id, sum(qty) as qty_masuk, sum(jumlah_roll) as jumlah_roll_masuk, CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar,6, tanggal as last_edit
						        	FROM nd_penyesuaian_stok
						        	WHERE tanggal <= '$tanggal'
						        	AND tanggal >= '$tanggal_awal'
						        	AND tipe_transaksi = 1
						        	GROUP BY barang_id, warna_id, gudang_id
							    )UNION(
							        SELECT barang_id, warna_id, gudang_id, CAST(0 as DECIMAL(15,2)) as qty_masuk, 0 as jumlah_roll_masuk, sum(qty) as qty_keluar, sum(jumlah_roll) as jumlah_roll_keluar,7, tanggal as last_edit
						        	FROM nd_penyesuaian_stok
						        	WHERE tanggal <= '$tanggal'
						        	AND tanggal >= '$tanggal_awal'
						        	AND tipe_transaksi = 2
									GROUP BY barang_id, warna_id, gudang_id
							    )UNION(
							    	SELECT barang_id, warna_id, gudang_id_before, CAST(0 as DECIMAL(15,2)) as qty_masuk, 0 as jumlah_roll_masuk, sum(qty) as qty_keluar, sum(jumlah_roll) as jumlah_roll_keluar,8, tanggal as last_edit
						        	FROM nd_mutasi_barang
						        	WHERE tanggal <= '$tanggal'	
								    AND tanggal >= '$tanggal_awal'
						        	AND status_aktif = 1
									GROUP BY barang_id, warna_id, gudang_id_before
							    )UNION(
							    	SELECT barang_id, warna_id, gudang_id, sum(qty), sum(jumlah_roll) as jumlah_roll_masuk, 0 as qty_keluar, 0 as jumlah_roll_keluar,9, tanggal as last_edit 
						        	FROM nd_stok_opname_detail t1 
									LEFT JOIN nd_stok_opname t2
									ON t1.stok_opname_id = t2.id
						        	WHERE stok_opname_id = $stok_opname_id
					        		AND warna_id > 0
						        	GROUP BY barang_id, warna_id, gudang_id
							    )
							)result
							GROUP BY barang_id, warna_id
						) tbl_a
						LEFT JOIN (
							SELECT @rownum:=@rownum+1 urutan_barang,id, nama, nama_jual, status_aktif, satuan_id
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
						ORDER BY urutan_barang, urutan");

			return $query->result();

	}

	function get_stok_barang_list_total($select, $tanggal, $tanggal_awal, $stok_opname_id){
		$query = $this->db->query("SELECT tbl_d.nama as nama_satuan, satuan_id
				$select
				FROM(
					(
					        SELECT barang_id, warna_id, nd_pembelian.gudang_id, sum(qty) as qty_masuk, sum(jumlah_roll) as jumlah_roll_masuk, CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar, 1 as tipe
					        FROM (
					        	SELECT CAST(qty as DECIMAL(15,2)) as qty, jumlah_roll, id, barang_id, warna_id, pembelian_id
					        	FROM nd_pembelian_detail
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
			        	FROM nd_mutasi_barang
			        	WHERE tanggal <= '$tanggal'
			        	AND tanggal >= '$tanggal_awal'
			        	AND status_aktif = 1
				        GROUP BY barang_id, warna_id, gudang_id_after
				    )UNION(
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
				        SELECT barang_id, warna_id, nd_pengeluaran_stok_lain_detail.gudang_id, CAST(0 as DECIMAL(15,2)) as qty_masuk, 0 as jumlah_roll_masuk, sum(qty) as qty_keluar, sum(jumlah_roll) as jumlah_roll_keluar,20
				        FROM nd_pengeluaran_stok_lain_detail
				        LEFT JOIN (
				        	SELECT *
				        	FROM nd_pengeluaran_stok_lain
				        	WHERE tanggal <= '$tanggal'
				        	AND tanggal >= '$tanggal_awal'
				        	AND status_aktif = 1
				        	) nd_pengeluaran_stok_lain
				        ON nd_pengeluaran_stok_lain_detail.pengeluaran_stok_lain_id = nd_pengeluaran_stok_lain.id
				        LEFT JOIN (
				            SELECT sum(qty* if(jumlah_roll != 0, jumlah_roll,1) ) as qty, sum(jumlah_roll) as jumlah_roll, pengeluaran_stok_lain_detail_id
				            FROM nd_pengeluaran_stok_lain_qty_detail
				            GROUP BY pengeluaran_stok_lain_detail_id
				            ) nd_pengeluaran_stok_lain_qty_detail
				        ON nd_pengeluaran_stok_lain_qty_detail.pengeluaran_stok_lain_detail_id = nd_pengeluaran_stok_lain_detail.id
				        where nd_pengeluaran_stok_lain.id is not null
				        GROUP BY barang_id, warna_id, nd_pengeluaran_stok_lain_detail.gudang_id
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
				    	SELECT barang_id, warna_id, nd_retur_beli_detail.gudang_id,0,0, sum(qty), sum(jumlah_roll),19
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
				        SELECT  barang_id, warna_id, gudang_id, sum(qty) as qty_masuk, sum(jumlah_roll) as jumlah_roll_masuk, CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar,6
			        	FROM nd_penyesuaian_stok
			        	WHERE tanggal <= '$tanggal'
			        	AND tanggal >= '$tanggal_awal'
			        	AND tipe_transaksi = 1
			        	GROUP BY barang_id, warna_id, gudang_id
				    )UNION(
				        SELECT barang_id, warna_id, gudang_id, CAST(0 as DECIMAL(15,2)) as qty_masuk, 0 as jumlah_roll_masuk, sum(qty) as qty_keluar, sum(jumlah_roll) as jumlah_roll_keluar,7
			        	FROM nd_penyesuaian_stok
			        	WHERE tanggal <= '$tanggal'
			        	AND tanggal >= '$tanggal_awal'
			        	AND tipe_transaksi = 2
						GROUP BY barang_id, warna_id, gudang_id
				    )UNION(
				    	SELECT barang_id, warna_id, gudang_id_before, CAST(0 as DECIMAL(15,2)) as qty_masuk, 0 as jumlah_roll_masuk, sum(qty) as qty_keluar, sum(jumlah_roll) as jumlah_roll_keluar,8
			        	FROM nd_mutasi_barang
			        	WHERE tanggal <= '$tanggal'	
					    AND tanggal >= '$tanggal_awal'
			        	AND status_aktif = 1
						GROUP BY barang_id, warna_id, gudang_id_before
				    )UNION(
				    	SELECT barang_id, warna_id, gudang_id, sum(qty), sum(jumlah_roll) as jumlah_roll_masuk, 0 as qty_keluar, 0 as jumlah_roll_keluar,9
			        	FROM nd_stok_opname_detail
			        	WHERE stok_opname_id = $stok_opname_id
		        		AND warna_id > 0
			        	GROUP BY barang_id, warna_id, gudang_id
				    )
				) tbl_a
				LEFT JOIN nd_barang tbl_b
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
				GROUP BY satuan_id
				ORDER BY nama_jual, urutan asc");
		
		return $query->result();
		// return $this->db->last_query();
	}

	function get_stok_barang_list_ajax($aColumns, $sWhere, $sOrder, $sLimit, $select, $tanggal_start,$stok_opname_id, $tanggal_awal, $select2){
		$query = $this->db->query("SELECT *
					FROM (SELECT CAST(concat(urutan_barang,LPAD(urutan,3,'0')) as UNSIGNED) as urutan,concat(tbl_b.nama_jual,' ',warna_jual) as nama_barang_jual, if(tbl_b.status_aktif = 1,'aktif', 'non aktif') as status_aktif
					 $select2
					 ,tbl_a.*
					FROM(
						SELECT barang_id, warna_id 			
						$select
						,MAX(tanggal) as last_edit 
						FROM (
								(
							        SELECT barang_id, warna_id, t1.gudang_id, 0 as qty_masuk, 0 as jumlah_roll_masuk, subqty as qty_keluar, subjumlah_roll as jumlah_roll_keluar,3 as tipe, tanggal, t2.id
							        FROM nd_penjualan_detail t1
							        LEFT JOIN (
							        	SELECT *
							        	FROM nd_penjualan
							        	WHERE tanggal <= '$tanggal_start'
							        	AND tanggal >= '$tanggal_awal'
							        	AND status_aktif = 1
							        	) t2
							        ON t1.penjualan_id = t2.id
							        where t2.id is not null
							    )UNION(
							        SELECT barang_id, warna_id, t1.gudang_id, 0 as qty_masuk, 0 as jumlah_roll_masuk, qty as qty_keluar, jumlah_roll as jumlah_roll_keluar,20 as tipe, tanggal, t3.id
							        FROM nd_pengeluaran_stok_lain_detail t1
							        LEFT JOIN (
							        	SELECT *
							        	FROM nd_pengeluaran_stok_lain
							        	WHERE tanggal <= '$tanggal_start'
							        	AND tanggal >= '$tanggal_awal'
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
							        SELECT barang_id, warna_id, t2.gudang_id, qty, jumlah_roll, 0, 0, 1, tanggal, qty_id
							        FROM (
							        	SELECT qty, jumlah_roll, tA.id, barang_id, warna_id, pembelian_id, id as qty_id
							        	FROM nd_pembelian_detail tA
										ORDER BY pembelian_id
							        ) t1
							        LEFT JOIN (
							        	SELECT *
							        	FROM nd_pembelian
							        	WHERE tanggal <= '$tanggal_start'
							        	AND tanggal >= '$tanggal_awal'
							        	AND status_aktif = 1
							        	) t2
							        ON t1.pembelian_id = t2.id
							        WHERE t2.id is not null
							    )UNION(
							    	SELECT barang_id, warna_id, gudang_id_after, qty, jumlah_roll, 0, 0,2, tanggal, id
						        	FROM nd_mutasi_barang t1
									WHERE tanggal <= '$tanggal_start'
						        	AND tanggal >= '$tanggal_awal'
						        	AND status_aktif = 1
							    )UNION(
							    	SELECT barang_id, warna_id, t1.gudang_id, qty, jumlah_roll, 0, 0, 4, tanggal, t3.id
							        FROM nd_retur_jual_detail t1
							        LEFT JOIN (
							        	SELECT *
							        	FROM nd_retur_jual
							        	WHERE tanggal <= '$tanggal_start'
							        	AND tanggal >= '$tanggal_awal'
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
							    	SELECT barang_id, warna_id, t1.gudang_id, 0, 0,qty, jumlah_roll, 19 ,  tanggal, t1.id
							        FROM nd_retur_beli_detail t1
							        LEFT JOIN (
							        	SELECT *
							        	FROM nd_retur_beli
							        	WHERE tanggal <= '$tanggal_start'
							        	AND tanggal >= '$tanggal_awal'
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
							        SELECT barang_id, warna_id, gudang_id, qty, jumlah_roll, 0, 0,5, tanggal, id
						        	FROM nd_penyesuaian_stok
						        	WHERE tipe_transaksi = 0
						            AND tanggal <= '$tanggal_start'
						        	AND tanggal >= '$tanggal_awal'
							    )UNION(
							        SELECT barang_id, warna_id, gudang_id, if(tipe_transaksi = 1,qty, 0 ), if(tipe_transaksi = 1,jumlah_roll,0 ), if(tipe_transaksi = 2,qty, 0 ), if(tipe_transaksi=2,jumlah_roll,0),6, tanggal, id
						        	FROM nd_penyesuaian_stok
						        	WHERE tanggal <= '$tanggal_start'
						        	AND tanggal >= '$tanggal_awal'
						        	AND tipe_transaksi != 0
							    )UNION(
							    	SELECT barang_id, warna_id, gudang_id_before, 0, 0 , qty, jumlah_roll,8, tanggal, id
						        	FROM nd_mutasi_barang t1
									WHERE tanggal <= '$tanggal_start'	
								    AND tanggal >= '$tanggal_awal'
						        	AND status_aktif = 1
							    )UNION(
							    	SELECT barang_id, warna_id, gudang_id, qty, jumlah_roll, 0, 0, 9, tanggal, t1.id
						        	FROM (
						        		SELECT barang_id, warna_id, gudang_id, sum(qty * jumlah_roll) as qty, sum(jumlah_roll) jumlah_roll, stok_opname_id, id
						        		FROM nd_stok_opname_detail
						        		WHERE warna_id > 0
						        		GROUP BY barang_id, warna_id, gudang_id, stok_opname_id
						        		) t1 
									LEFT JOIN (
										SELECT *
										FROM nd_stok_opname
										WHERE tanggal <= '$tanggal_start'	
									    AND tanggal >= '$tanggal_awal'
							        	AND status_aktif = 1
										) t2
									ON t1.stok_opname_id = t2.id
									WHERE t2.id is not null
							    )
							)t1
						LEFT JOIN (
							SELECT barang_id as barang_id_stok, warna_id as warna_id_stok,gudang_id as gudang_id_stok, MAX(tanggal) as tanggal_stok
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
							) tB
							ON tA.stok_opname_id = tB.id
							WHERE tB.id is not null
							GROUP BY barang_id, warna_id, gudang_id
						) t2
						ON t1.barang_id = t2.barang_id_stok
						AND t1.warna_id = t2.warna_id_stok
						AND t1.gudang_id = t2.gudang_id_stok
						LEFT JOIN (
							SELECT barang_id as barang_id_penyesuaian, warna_id as warna_id_penyesuaian,gudang_id as gudang_id_penyesuaian, MAX(tanggal) as tanggal_penyesuaian
						    FROM nd_penyesuaian_stok
						    WHERE tipe_transaksi != 3
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
					ORDER BY urutan_barang, urutan

				) A			
			$sWhere
            -- $sOrder
            $sLimit
			", false);

		return $query;
	// return $this->db->last_query();
	}

	function get_stok_barang_list_ajax_2($aColumns, $sWhere, $sOrder, $sLimit, $select, $tanggal_start,$stok_opname_id, $tanggal_awal, $select2){
		$query = $this->db->query("SELECT *
					FROM (SELECT CAST(concat(urutan_barang,LPAD(urutan,3,'0')) as UNSIGNED) as urutan,concat(tbl_b.nama_jual,' ',warna_jual) as nama_barang_jual, if(tbl_b.status_aktif = 1,'aktif', 'non aktif') as status_aktif
					 $select2
					 ,tbl_a.*
					FROM(
						SELECT barang_id, warna_id
						$select
						,MAX(tanggal) as last_edit
						FROM (
								(
							        SELECT barang_id, warna_id, t1.gudang_id, 0 as qty_masuk, 0 as jumlah_roll_masuk, subqty as qty_keluar, subjumlah_roll as jumlah_roll_keluar,3 as tipe, tanggal, t2.id, ifnull(closed_date, t2.created_at) as time_stamp
							        FROM nd_penjualan_detail t1
							        LEFT JOIN (
							        	SELECT *
							        	FROM nd_penjualan
							        	WHERE created_at <= '$tanggal_start'
							        	AND created_at >= '$tanggal_awal'
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
						    WHERE tipe_transaksi != 3
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
					ORDER BY urutan_barang, urutan

				) A			
			$sWhere
            -- $sOrder
            $sLimit
			", false);

		return $query;
	// return $this->db->last_query();
	}

	function get_stok_barang_list_ajax_new($aColumns, $sWhere, $sOrder, $sLimit, $select, $tanggal_start,$stok_opname_id, $tanggal_awal, $select2, $tanggal_filter, $qty_cond, $cond_filter){
		$query = $this->db->query("SELECT *
					FROM (
						SELECT CAST(concat(urutan_barang,LPAD(urutan,3,'0')) as UNSIGNED) as urutan,concat(tbl_b.nama_jual,' ',warna_jual) as nama_barang_jual, if(tbl_b.status_aktif = 1,'aktif', 'non aktif') as status_aktif
						 $select2
						 ,tbl_a.*
						FROM(
							SELECT barang_id, warna_id 
							$select
							,MAX(tanggal) as last_edit
							FROM (
								(
							        SELECT barang_id, warna_id, t1.gudang_id, 0 as qty_masuk, 0 as jumlah_roll_masuk, subqty as qty_keluar, subjumlah_roll as jumlah_roll_keluar,3 as tipe, tanggal, t2.id
							        FROM nd_penjualan_detail t1
							        LEFT JOIN (
							        	SELECT *
							        	FROM nd_penjualan
							        	WHERE tanggal <= '$tanggal_start'
							        	AND tanggal >= '$tanggal_awal'
							        	AND status_aktif = 1
							        	) t2
							        ON t1.penjualan_id = t2.id
							        where t2.id is not null
							    )UNION(
							        SELECT barang_id, warna_id, t1.gudang_id, 0 as qty_masuk, 0 as jumlah_roll_masuk, qty as qty_keluar, jumlah_roll as jumlah_roll_keluar,20 as tipe, tanggal, t3.id
							        FROM nd_pengeluaran_stok_lain_detail t1
							        LEFT JOIN (
							        	SELECT *
							        	FROM nd_pengeluaran_stok_lain
							        	WHERE tanggal <= '$tanggal_start'
							        	AND tanggal >= '$tanggal_awal'
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
							        SELECT barang_id, warna_id, t2.gudang_id, qty, jumlah_roll, 0, 0, 1, tanggal, qty_id
							        FROM (
							        	SELECT qty, jumlah_roll, tA.id, barang_id, warna_id, pembelian_id, id as qty_id
							        	FROM nd_pembelian_detail tA
										ORDER BY pembelian_id
							        ) t1
							        LEFT JOIN (
							        	SELECT *
							        	FROM nd_pembelian
							        	WHERE tanggal <= '$tanggal_start'
							        	AND tanggal >= '$tanggal_awal'
							        	AND status_aktif = 1
							        	) t2
							        ON t1.pembelian_id = t2.id
							        WHERE t2.id is not null
							    )UNION(
							    	SELECT barang_id, warna_id, gudang_id_after, qty, jumlah_roll, 0, 0,2, tanggal, id
						        	FROM nd_mutasi_barang t1
									WHERE tanggal <= '$tanggal_start'
						        	AND tanggal >= '$tanggal_awal'
						        	AND status_aktif = 1
							    )UNION(
							    	SELECT barang_id, warna_id, t1.gudang_id, qty, jumlah_roll, 0, 0, 4, tanggal, t3.id
							        FROM nd_retur_jual_detail t1
							        LEFT JOIN (
							        	SELECT *
							        	FROM nd_retur_jual
							        	WHERE tanggal <= '$tanggal_start'
							        	AND tanggal >= '$tanggal_awal'
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
							    	SELECT barang_id, warna_id, t1.gudang_id, 0, 0,qty, jumlah_roll, 19 ,  tanggal, t1.id
							        FROM nd_retur_beli_detail t1
							        LEFT JOIN (
							        	SELECT *
							        	FROM nd_retur_beli
							        	WHERE tanggal <= '$tanggal_start'
							        	AND tanggal >= '$tanggal_awal'
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
							        SELECT barang_id, warna_id, gudang_id, qty, jumlah_roll, 0, 0,5, tanggal, id
						        	FROM nd_penyesuaian_stok
						        	WHERE tipe_transaksi = 0
						            AND tanggal <= '$tanggal_start'
						        	AND tanggal >= '$tanggal_awal'
							    )UNION(
							        SELECT barang_id, warna_id, gudang_id, 
									if(tipe_transaksi = 1,qty, if(tipe_transaksi=3,qty,0) ), 
									if(tipe_transaksi = 1,jumlah_roll,if(tipe_transaksi=3,jml_roll,0) ), 
									if(tipe_transaksi = 2,qty, if(tipe_transaksi=3,qty,0) ), 
									if(tipe_transaksi=2,jumlah_roll,if(tipe_transaksi=3,jumlah_roll,0)),
									6, tanggal, id, created_at
						        	FROM nd_penyesuaian_stok tA
									LEFT JOIN (
										SELECT sum(jumlah_roll) as jml_roll, penyesuaian_stok_id
										FROM nd_penyesuaian_stok_split
										GROUP BY penyesuaian_stok_id
										) tB
									ON tB.penyesuaian_stok_id = tA.id
						        	WHERE created_at <= '$tanggal_start'
						        	AND created_at >= '$tanggal_awal'
						        	AND tipe_transaksi != 0
							    )UNION(
							    	SELECT barang_id, warna_id, gudang_id_before, 0, 0 , qty, jumlah_roll,8, tanggal, id
						        	FROM nd_mutasi_barang t1
									WHERE tanggal <= '$tanggal_start'	
								    AND tanggal >= '$tanggal_awal'
						        	AND status_aktif = 1
							    )UNION(
							    	SELECT barang_id, warna_id, gudang_id, qty, jumlah_roll, 0, 0, 9, tanggal, t1.id
						        	FROM (
						        		SELECT barang_id, warna_id, gudang_id, sum(qty * jumlah_roll) as qty, sum(jumlah_roll) jumlah_roll, stok_opname_id, id
						        		FROM nd_stok_opname_detail
						        		WHERE warna_id > 0
						        		GROUP BY barang_id, warna_id, gudang_id, stok_opname_id
						        		) t1 
									LEFT JOIN (
										SELECT *
										FROM nd_stok_opname
										WHERE tanggal <= '$tanggal_start'	
									    AND tanggal >= '$tanggal_awal'
							        	AND status_aktif = 1
										) t2
									ON t1.stok_opname_id = t2.id
									WHERE t2.id is not null
							    )
							)t1
							LEFT JOIN (
								SELECT barang_id as barang_id_stok, warna_id as warna_id_stok,gudang_id as gudang_id_stok, MAX(tanggal) as tanggal_stok
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
									AND tanggal <= '$tanggal_start'
								) tB
								ON tA.stok_opname_id = tB.id
								WHERE tB.id is not null
								GROUP BY barang_id, warna_id, gudang_id
							) t2
							ON t1.barang_id = t2.barang_id_stok
							AND t1.warna_id = t2.warna_id_stok
							AND t1.gudang_id = t2.gudang_id_stok
							LEFT JOIN (
								SELECT barang_id as barang_id_penyesuaian, warna_id as warna_id_penyesuaian,gudang_id as gudang_id_penyesuaian, MAX(tanggal) as tanggal_penyesuaian
							    FROM nd_penyesuaian_stok
								WHERE tanggal <= $tanggal_start
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
						AND last_edit >= '$tanggal_filter'
						$cond_filter
						ORDER BY urutan_barang, urutan
				) A			
			$sWhere
            -- $sOrder
            $sLimit
			", false);
		return $query;
	}

	function get_stok_barang_list_ajax_new_2($aColumns, $sWhere, $sOrder, $sLimit, $select, $tanggal_start,$stok_opname_id, $tanggal_awal, $select2, $tanggal_filter, $qty_cond, $cond_filter){
		$query = $this->db->query("SELECT *
					FROM (
						SELECT CAST(concat(urutan_barang,LPAD(urutan,3,'0')) as UNSIGNED) as urutan,concat(tbl_b.nama_jual,' ',warna_jual) as nama_barang_jual, if(tbl_b.status_aktif = 1,'aktif', 'non aktif') as status_aktif
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
							        	WHERE created_at <= '$tanggal_start'
							        	AND created_at >= '$tanggal_awal'
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
							        SELECT barang_id, warna_id, gudang_id, if(tipe_transaksi = 1,qty, if(tipe_transaksi=3,qty,0) ), if(tipe_transaksi = 1,jumlah_roll,if(tipe_transaksi=3,jml_roll,0) ), 
									if(tipe_transaksi = 2,qty, if(tipe_transaksi=3,qty,0) ), if(tipe_transaksi=2,jumlah_roll,if(tipe_transaksi=3,jumlah_roll,0)),6, tanggal, id, created_at
						        	FROM nd_penyesuaian_stok tA
									LEFT JOIN (
										SELECT sum(jumlah_roll) as jml_roll, penyesuaian_stok_id
										FROM nd_penyesuaian_stok_split
										GROUP BY penyesuaian_stok_id
										) tB
									ON tB.penyesuaian_stok_id = tA.id
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
						AND last_edit >= '$tanggal_filter'
						$cond_filter
						ORDER BY urutan_barang, urutan
				) A			
			$sWhere
            -- $sOrder
            $sLimit
			", false);

		return $query;
	}

	//=================================================================with tutup buku======================================================================

	function get_stok_barang_list_ajax_with_tutup_buku($aColumns, $sWhere, $sOrder, $sLimit, $select, $tanggal_start,$stok_opname_id, $tanggal_awal, $select2,
		$tanggal_filter, $qty_cond, $cond_filter, $tahun_tutup_buku, $bulan_qty){
		$bulan_start = $bulan_qty.'_qty';
		$bulan_roll = $bulan_qty.'_roll';
		$timestamp = $tahun_tutup_buku.'-'.$bulan_qty;
		$timestamp = date("Y-m-t", strtotime($timestamp)).' 23:59:59';
		$query = $this->db->query("SELECT *
					FROM (
						SELECT CAST(concat(urutan_barang,LPAD(urutan,3,'0')) as UNSIGNED) as urutan,concat(tbl_b.nama_jual,' ',warna_jual) as nama_barang_jual, if(tbl_b.status_aktif = 1,'aktif', 'non aktif') as status_aktif
						 $select2
						 ,tbl_a.*
						FROM(
							SELECT barang_id, warna_id 
							$select
							,MAX(tanggal) as last_edit
							FROM (
								(
							        SELECT barang_id, warna_id, gudang_id, qty as qty_masuk, jumlah_roll as jumlah_roll_masuk, 0 as qty_keluar, 0 as jumlah_roll_keluar,'tb' as tipe, tanggal, id, 
									ifnull(time_stamp, '00:00:00') as time_stamp
							        FROM (
							        	SELECT id, tahun as tanggal,barang_id, warna_id, $bulan_start as qty, $bulan_roll as jumlah_roll, '$timestamp' as time_stamp, gudang_id
							        	FROM nd_tutup_buku_detail_gudang
							        	WHERE YEAR(tahun) = '$tahun_tutup_buku'
							        	) res
							        where barang_id is not null
							    )UNION(
							        SELECT barang_id, warna_id, t1.gudang_id, 0 as qty_masuk, 0 as jumlah_roll_masuk, subqty as qty_keluar, 
									subjumlah_roll as jumlah_roll_keluar,3 as tipe, tanggal, t1.id, ifnull(closed_date, t2.created_at) as time_stamp
							        FROM nd_penjualan_detail t1
							        LEFT JOIN (
							        	SELECT *
							        	FROM nd_penjualan
							        	WHERE created_at <= '$tanggal_start'
							        	AND created_at >= '$tanggal_awal'
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
							        FROM  (
							        	SELECT *
							        	FROM nd_pembelian
							        	WHERE created_at <= '$tanggal_start'
							        	AND created_at >= '$tanggal_awal'
							        	AND status_aktif = 1
							        	) t2
							        LEFT JOIN (
							        	SELECT qty, jumlah_roll, tA.id, barang_id, warna_id, pembelian_id, id as qty_id
							        	FROM nd_pembelian_detail tA
										ORDER BY pembelian_id
							        ) t1
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
							        SELECT barang_id, warna_id, gudang_id, if(tipe_transaksi = 1,qty, if(tipe_transaksi=3,qty,0) ), if(tipe_transaksi = 1,jumlah_roll,if(tipe_transaksi=3,jml_roll,0) ), 
									if(tipe_transaksi = 2,qty, if(tipe_transaksi=3,qty,0) ), if(tipe_transaksi=2,jumlah_roll,if(tipe_transaksi=3,jumlah_roll,0)),6, tanggal, id, created_at
						        	FROM nd_penyesuaian_stok tA
									LEFT JOIN (
										SELECT sum(jumlah_roll) as jml_roll, penyesuaian_stok_id
										FROM nd_penyesuaian_stok_split
										GROUP BY penyesuaian_stok_id
										) tB
									ON tB.penyesuaian_stok_id = tA.id
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
						        		WHERE warna_id != 0
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
						        		WHERE warna_id != 0
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
						ORDER BY urutan_barang, urutan
				) A			
			$sWhere
            -- $sOrder
            $sLimit
			", false);

		return $query;
	}

	function get_stok_with_tutup_buku($select, $tanggal_start,$stok_opname_id, $tanggal_awal, $select2,
		$tanggal_filter, $qty_cond, $tahun_tutup_buku, $bulan_qty, $cond_barang){
		$bulan_start = $bulan_qty.'_qty';
		$bulan_roll = $bulan_qty.'_roll';
		$timestamp = $tahun_tutup_buku.'-'.$bulan_qty;
		$timestamp = date("Y-m-t", strtotime($timestamp)).' 23:59:59';
		$query = $this->db->query("SELECT CAST(concat(urutan_barang,LPAD(urutan,3,'0')) as UNSIGNED) as urutan,
					tbl_b.nama_jual as nama_barang_jual, warna_jual as nama_warna_jual, tipe_qty,
					tbl_b.status_aktif as status_aktif, satuan_id, tbl_d.nama as nama_satuan,
					tbl_b.status_aktif as status_barang
					$select2
					,tbl_a.*
				FROM(
					SELECT barang_id, warna_id 
					$select
					,MAX(tanggal) as last_edit
					FROM (
						(
							SELECT barang_id, warna_id, gudang_id, qty as qty_masuk, jumlah_roll as jumlah_roll_masuk, 0 as qty_keluar, 0 as jumlah_roll_keluar,'tb' as tipe, tanggal, id, 
							ifnull(time_stamp, '00:00:00') as time_stamp
							FROM (
								SELECT id, tahun as tanggal,barang_id, warna_id, $bulan_start as qty, $bulan_roll as jumlah_roll, '$timestamp' as time_stamp, gudang_id
								FROM nd_tutup_buku_detail_gudang
								WHERE YEAR(tahun) = '$tahun_tutup_buku'
								) res
							where barang_id is not null
						)UNION(
							SELECT barang_id, warna_id, t1.gudang_id, 0 as qty_masuk, 0 as jumlah_roll_masuk, subqty as qty_keluar, subjumlah_roll as jumlah_roll_keluar,3 as tipe, tanggal, t1.id, ifnull(closed_date, t2.created_at) as time_stamp
							FROM nd_penjualan_detail t1
							LEFT JOIN (
								SELECT *
								FROM nd_penjualan
								WHERE created_at <= '$tanggal_start'
								AND created_at >= '$tanggal_awal'
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
							FROM  (
								SELECT *
								FROM nd_pembelian
								WHERE created_at <= '$tanggal_start'
								AND created_at >= '$tanggal_awal'
								AND status_aktif = 1
								) t2
							LEFT JOIN (
								SELECT qty, jumlah_roll, tA.id, barang_id, warna_id, pembelian_id, id as qty_id
								FROM nd_pembelian_detail tA
								ORDER BY pembelian_id
							) t1
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
							SELECT barang_id, warna_id, gudang_id, if(tipe_transaksi = 1,qty, if(tipe_transaksi=3,qty,0) ), if(tipe_transaksi = 1,jumlah_roll,if(tipe_transaksi=3,jml_roll,0) ), 
							if(tipe_transaksi = 2,qty, if(tipe_transaksi=3,qty,0) ), if(tipe_transaksi=2,jumlah_roll,if(tipe_transaksi=3,jumlah_roll,0)),6, tanggal, id, created_at
							FROM nd_penyesuaian_stok tA
							LEFT JOIN (
								SELECT sum(jumlah_roll) as jml_roll, penyesuaian_stok_id
								FROM nd_penyesuaian_stok_split
								GROUP BY penyesuaian_stok_id
								) tB
							ON tB.penyesuaian_stok_id = tA.id
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
								WHERE warna_id != 0
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
								WHERE warna_id != 0
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
				$cond_barang
				ORDER BY urutan_barang, urutan
			", false);

		return $query;
	}

	function get_stok_barang_list_nonajax_new_2($select, $tanggal_start,$stok_opname_id, $tanggal_awal, $select2, $tanggal_filter, $qty_cond, $cond_filter){
		$query = $this->db->query("SELECT CAST(concat(urutan_barang,LPAD(urutan,3,'0')) as UNSIGNED) as urutan,(tbl_b.nama_jual) as nama_barang_jual, if(tbl_b.status_aktif = 1,'aktif', 'non aktif') as status_barang, satuan_id, tbl_c.warna_jual as nama_warna_jual
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
						-- AND last_edit >= '$tanggal_filter'
						$cond_filter
						ORDER BY urutan_barang, urutan
			", false);

		return $query->result();
	}


	function get_total_stok_legacy($select, $tanggal_start, $tanggal_awal, $select2, $new_sum){
		$query = $this->db->query("SELECT satuan_id, nama_satuan $new_sum
			FROM (
				SELECT if(tbl_b.status_aktif = 1,'aktif', 'non aktif') as status_aktif, tbl_d.nama as nama_satuan, satuan_id
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
									WHERE created_at <= '$tanggal_start'
									AND created_at >= '$tanggal_awal'
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
				LEFT JOIN nd_barang tbl_b
				ON tbl_a.barang_id = tbl_b.id
				LEFT JOIN nd_satuan tbl_d
				ON tbl_b.satuan_id = tbl_d.id
				Where barang_id is not null
			) result
			GROUP BY satuan_id
			", false);

		return $query->result();
	}

	function get_total_stok($select, $tanggal_start, $tanggal_awal, $select2, $new_sum, $tahun_tutup_buku, $bulan_qty){
		$bulan_start = $bulan_qty.'_qty';
		$bulan_roll = $bulan_qty.'_roll';
		$timestamp = $tahun_tutup_buku.'-'.$bulan_qty;
		$timestamp = date("Y-m-t", strtotime($timestamp)).' 23:59:59';
		$query = $this->db->query("SELECT satuan_id, nama_satuan $new_sum
			FROM (
				SELECT CAST(concat(urutan_barang,LPAD(urutan,3,'0')) as UNSIGNED) as urutan,
					tbl_b.nama_jual as nama_barang_jual, warna_jual as nama_warna_jual, tipe_qty,
					tbl_b.status_aktif as status_aktif, satuan_id, tbl_d.nama as nama_satuan
					$select2
					,tbl_a.*
				FROM(
					SELECT barang_id, warna_id 
					$select
					,MAX(tanggal) as last_edit
					FROM (
						(
							SELECT barang_id, warna_id, gudang_id, qty as qty_masuk, jumlah_roll as jumlah_roll_masuk, 0 as qty_keluar, 0 as jumlah_roll_keluar,'tb' as tipe, tanggal, id, 
							ifnull(time_stamp, '00:00:00') as time_stamp
							FROM (
								SELECT id, tahun as tanggal,barang_id, warna_id, $bulan_start as qty, $bulan_roll as jumlah_roll, '$timestamp' as time_stamp, gudang_id
								FROM nd_tutup_buku_detail_gudang
								WHERE YEAR(tahun) = '$tahun_tutup_buku'
								) res
							where barang_id is not null
						)UNION(
							SELECT barang_id, warna_id, t1.gudang_id, 0 as qty_masuk, 0 as jumlah_roll_masuk, subqty as qty_keluar, subjumlah_roll as jumlah_roll_keluar,3 as tipe, tanggal, t1.id, ifnull(closed_date, t2.created_at) as time_stamp
							FROM nd_penjualan_detail t1
							LEFT JOIN (
								SELECT *
								FROM nd_penjualan
								WHERE created_at <= '$tanggal_start'
								AND created_at >= '$tanggal_awal'
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
							FROM  (
								SELECT *
								FROM nd_pembelian
								WHERE created_at <= '$tanggal_start'
								AND created_at >= '$tanggal_awal'
								AND status_aktif = 1
								) t2
							LEFT JOIN (
								SELECT qty, jumlah_roll, tA.id, barang_id, warna_id, pembelian_id, id as qty_id
								FROM nd_pembelian_detail tA
								ORDER BY pembelian_id
							) t1
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
							SELECT barang_id, warna_id, gudang_id, if(tipe_transaksi = 1,qty, if(tipe_transaksi=3,qty,0) ), if(tipe_transaksi = 1,jumlah_roll,if(tipe_transaksi=3,jml_roll,0) ), 
							if(tipe_transaksi = 2,qty, if(tipe_transaksi=3,qty,0) ), if(tipe_transaksi=2,jumlah_roll,if(tipe_transaksi=3,jumlah_roll,0)),6, tanggal, id, created_at
							FROM nd_penyesuaian_stok tA
							LEFT JOIN (
								SELECT sum(jumlah_roll) as jml_roll, penyesuaian_stok_id
								FROM nd_penyesuaian_stok_split
								GROUP BY penyesuaian_stok_id
								) tB
							ON tB.penyesuaian_stok_id = tA.id
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
								WHERE warna_id != 0
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
								WHERE warna_id != 0
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
			) result
			GROUP BY satuan_id
			", false);

		return $query->result();
	}

	function get_stok_barang_list_by_barang_temp($tanggal_awal, $select, $tanggal_start, $cond_barang, $stok_opname_id){
		$query = $this->db->query("SELECT tbl_b.nama as nama_barang,tbl_b.nama_jual as nama_barang_jual, tbl_c.warna_beli as nama_warna,tbl_c.warna_jual as nama_warna_jual, barang_id, warna_id, tbl_b.status_aktif as status_barang, tbl_d.nama as nama_satuan
				$select
				FROM (
					(
				        SELECT barang_id, warna_id, t1.gudang_id, 0 as qty_masuk, 0 as jumlah_roll_masuk, subqty as qty_keluar, subjumlah_roll as jumlah_roll_keluar,3 as tipe, tanggal, t1.id
				        FROM nd_penjualan_detail t1
				        LEFT JOIN (
				        	SELECT *
				        	FROM nd_penjualan
				        	WHERE tanggal <= '$tanggal_start'
				        	AND tanggal >= '$tanggal_awal'
				        	AND status_aktif = 1
				        	) t2
				        ON t1.penjualan_id = t2.id
				        where t2.id is not null
				        $cond_barang
				    )UNION(
				        SELECT barang_id, warna_id, t1.gudang_id, 0 as qty_masuk, 0 as jumlah_roll_masuk, qty as qty_keluar, jumlah_roll as jumlah_roll_keluar,20 as tipe, tanggal, t3.id
				        FROM nd_pengeluaran_stok_lain_detail t1
				        LEFT JOIN (
				        	SELECT *
				        	FROM nd_pengeluaran_stok_lain
				        	WHERE tanggal <= '$tanggal_start'
				        	AND tanggal >= '$tanggal_awal'
				        	AND status_aktif = 1
				        	) t2
				        ON t1.pengeluaran_stok_lain_id = t2.id
				        LEFT JOIN (
				            SELECT sum(qty*if(jumlah_roll=0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, pengeluaran_stok_lain_detail_id, id
				            FROM nd_pengeluaran_stok_lain_qty_detail
				            GROUP BY pengeluaran_stok_lain_detail_id
				            ) t3
				        ON t3.pengeluaran_stok_lain_detail_id = t1.id
				        where t2.id is not null
				        $cond_barang
				    )UNION(
				        SELECT barang_id, warna_id, t2.gudang_id, qty, jumlah_roll, 0, 0, 1, tanggal, t1.id
				        FROM nd_pembelian_detail t1
				        LEFT JOIN (
				        	SELECT *
				        	FROM nd_pembelian
				        	WHERE tanggal <= '$tanggal_start'
				        	AND tanggal >= '$tanggal_awal'
				        	AND status_aktif = 1
				        	) t2
				        ON t1.pembelian_id = t2.id
				        WHERE t2.id is not null
				        $cond_barang
				    )UNION(
				    	SELECT barang_id, warna_id, gudang_id_after, qty, jumlah_roll, 0, 0,2, tanggal, id
			        	FROM nd_mutasi_barang t1
						WHERE tanggal <= '$tanggal_start'
			        	AND tanggal >= '$tanggal_awal'
			        	AND status_aktif = 1
				        $cond_barang
				    )UNION(
				    	SELECT barang_id, warna_id, t1.gudang_id, qty, jumlah_roll, 0, 0, 4, tanggal, t3.id
				        FROM nd_retur_jual_detail t1
				        LEFT JOIN (
				        	SELECT *
				        	FROM nd_retur_jual
				        	WHERE tanggal <= '$tanggal_start'
				        	AND tanggal >= '$tanggal_awal'
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
				        $cond_barang
				    )UNION(
				    	SELECT barang_id, warna_id, t2.gudang_id,0,0, qty, jumlah_roll, 19, tanggal, t1.id
				        FROM nd_retur_beli_detail t2
				        LEFT JOIN (
				        	SELECT *
				        	FROM nd_retur_beli
				        	WHERE tanggal <= '$tanggal_start'
				        	AND tanggal >= '$tanggal_awal'
				        	AND status_aktif = 1
				        	) t1
				        ON t2.retur_beli_id = t1.id
				        LEFT JOIN (
				            SELECT sum(qty*if(jumlah_roll=0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, retur_beli_detail_id
				            FROM nd_retur_beli_qty
				            GROUP BY retur_beli_detail_id
				            ) t3
				        ON t3.retur_beli_detail_id = t2.id
				        WHERE t1.status_aktif = 1
				        $cond_barang
				    )UNION(
				        SELECT barang_id, warna_id, gudang_id, qty, jumlah_roll, 0, 0,5, tanggal, id
			        	FROM nd_penyesuaian_stok
			        	WHERE tipe_transaksi = 0
			            AND tanggal <= '$tanggal_start'
			        	AND tanggal >= '$tanggal_awal'
				        $cond_barang
				    )UNION(
				        SELECT barang_id, warna_id, gudang_id, if(tipe_transaksi = 1,qty, 0 ), if(tipe_transaksi = 1,jumlah_roll,0 ), if(tipe_transaksi = 2,qty, 0 ), if(tipe_transaksi=2,jumlah_roll,0),6, tanggal, id
			        	FROM nd_penyesuaian_stok
			        	WHERE tanggal <= '$tanggal_start'
			        	AND tanggal >= '$tanggal_awal'
			        	AND tipe_transaksi != 0
				        $cond_barang
				    )UNION(
				    	SELECT barang_id, warna_id, gudang_id_before, 0, 0 , qty, jumlah_roll,8, tanggal, id
			        	FROM nd_mutasi_barang t1
						WHERE tanggal <= '$tanggal_start'	
					    AND tanggal >= '$tanggal_awal'
			        	AND status_aktif = 1
				        $cond_barang
				    )UNION(
				    	SELECT barang_id, warna_id, gudang_id, qty, jumlah_roll, 0, 0, 9, tanggal, t1.id
			        	FROM (
			        		SELECT barang_id, warna_id, gudang_id, sum(qty*jumlah_roll) as qty, sum(jumlah_roll) as jumlah_roll, stok_opname_id, id
			        			FROM nd_stok_opname_detail
						        		WHERE warna_id > 0
			        			GROUP BY barang_id, warna_id, gudang_id, stok_opname_id
			        			) t1 
						LEFT JOIN (
							SELECT *
							FROM nd_stok_opname
							WHERE tanggal <= '$tanggal_start'	
						    AND tanggal >= '$tanggal_awal'
				        	AND status_aktif = 1
							) t2
						ON t1.stok_opname_id = t2.id
						WHERE status_aktif=1
				        $cond_barang
				    )
				)tbl_a
				LEFT JOIN (
					SELECT barang_id as barang_id_stok, warna_id as warna_id_stok,gudang_id as gudang_id_stok, MAX(tanggal) as tanggal_stok
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
					) tB
					ON tA.stok_opname_id = tB.id
					WHERE tB.id is not null
			        $cond_barang
					GROUP BY barang_id, warna_id, gudang_id
				) t2
				ON tbl_a.barang_id = t2.barang_id_stok
				AND tbl_a.warna_id = t2.warna_id_stok
				AND tbl_a.gudang_id = t2.gudang_id_stok
				LEFT JOIN nd_barang tbl_b
				ON tbl_a.barang_id = tbl_b.id
				LEFT JOIN nd_warna tbl_c
				ON tbl_a.warna_id = tbl_c.id
				LEFT JOIN nd_satuan tbl_d
				ON tbl_b.satuan_id = tbl_d.id
				Where barang_id is not null
				GROUP BY barang_id, warna_id
				ORDER BY warna_jual asc");
		
		// if (is_posisi_id() == 1) {
			// return $this->db->last_query();
		// }else{
		// }
		return $query->result();

	}

	function get_stok_barang_list_by_barang_temp_2($tanggal_awal, $select, $tanggal_start, $cond_barang, $stok_opname_id){
		$tanggal_start .= ' 23:59:59'; 
		$query = $this->db->query("SELECT tbl_b.nama as nama_barang,tbl_b.nama_jual as nama_barang_jual, tbl_c.warna_beli as nama_warna,
							tbl_c.warna_jual as nama_warna_jual, barang_id, warna_id, 
							tbl_b.status_aktif as status_barang, tbl_d.nama as nama_satuan
				$select
				FROM (					
						(
					        SELECT barang_id, warna_id, t1.gudang_id, 0 as qty_masuk, 0 as jumlah_roll_masuk, subqty as qty_keluar, +
							subjumlah_roll as jumlah_roll_keluar,3 as tipe, tanggal, t2.id, ifnull(closed_date, t2.created_at) as time_stamp
					        FROM nd_penjualan_detail t1
					        LEFT JOIN (
					        	SELECT *
					        	FROM nd_penjualan
					        	WHERE ifnull(closed_date, created_at) <= '$tanggal_start'
				        		AND ifnull(closed_date, created_at) >= '$tanggal_awal'
				        		AND status_aktif = 1
					        	) t2
					        ON t1.penjualan_id = t2.id
					        where t2.id is not null
					        $cond_barang
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
					        $cond_barang
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
					        $cond_barang
					    )UNION(
					    	SELECT barang_id, warna_id, gudang_id_after, qty, jumlah_roll, 0, 0,2, tanggal, id, created_at
				        	FROM nd_mutasi_barang t1
							WHERE created_at <= '$tanggal_start'
				        	AND created_at >= '$tanggal_awal'
				        	AND status_aktif = 1
					        $cond_barang
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
					        $cond_barang
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
					        $cond_barang
					    )UNION(
					        SELECT barang_id, warna_id, gudang_id, qty, jumlah_roll, 0, 0,5, tanggal, id, created_at
				        	FROM nd_penyesuaian_stok
				        	WHERE tipe_transaksi = 0
				            AND created_at <= '$tanggal_start'
				        	AND created_at >= '$tanggal_awal'
					        $cond_barang
					    )UNION(
					        SELECT barang_id, warna_id, gudang_id, q_in, if(tipe_transaksi = 3, jml_roll, j_in), 
								q_out, j_out, 6, tanggal, id, created_at
							FROM (
								SELECT barang_id, warna_id, gudang_id, if(tipe_transaksi = 1,qty, 0 ) q_in, 
								if(tipe_transaksi = 1,jumlah_roll,0 ) j_in, 
								if(tipe_transaksi = 2,qty, 0 ) q_out, 
								if(tipe_transaksi=2 || tipe_transaksi = 3,jumlah_roll,0) j_out,tipe_transaksi, tanggal, id, created_at
								FROM nd_penyesuaian_stok
								WHERE created_at <= '$tanggal_start'
								AND created_at >= '$tanggal_awal'
								AND tipe_transaksi != 0
								$cond_barang
								)t1
								LEFT JOIN (
									SELECT sum(jumlah_roll) as jml_roll, penyesuaian_stok_id
									FROM nd_penyesuaian_stok_split
									GROUP BY penyesuaian_stok_id
									) t2
								ON t1.id = t2.penyesuaian_stok_id
					    )UNION(
					    	SELECT barang_id, warna_id, gudang_id_before, 0, 0 , qty, jumlah_roll,8, tanggal, id, created_at
				        	FROM nd_mutasi_barang t1
							WHERE created_at <= '$tanggal_start'	
						    AND created_at >= '$tanggal_awal'
				        	AND status_aktif = 1
					        $cond_barang
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
					        $cond_barang
					    )	
					)tbl_a
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
					) tB
					ON tA.stok_opname_id = tB.id
					WHERE tB.id is not null
			        $cond_barang
					GROUP BY barang_id, warna_id, gudang_id
				) t2
				ON tbl_a.barang_id = t2.barang_id_stok
				AND tbl_a.warna_id = t2.warna_id_stok
				AND tbl_a.gudang_id = t2.gudang_id_stok
				LEFT JOIN nd_barang tbl_b
				ON tbl_a.barang_id = tbl_b.id
				LEFT JOIN nd_warna tbl_c
				ON tbl_a.warna_id = tbl_c.id
				LEFT JOIN nd_satuan tbl_d
				ON tbl_b.satuan_id = tbl_d.id
				Where barang_id is not null
				GROUP BY barang_id, warna_id
				ORDER BY warna_jual asc");
		
		// if (is_posisi_id() == 1) {
			// return $this->db->last_query();
		// }else{
		// }
		return $query->result();

	}

	function get_stok_barang_list_by_barang_temp_2_barang($tanggal_awal, $select, $tanggal_start, $cond_barang, $stok_opname_id){
		$tanggal_start .= ' 23:59:59'; 
		$query = $this->db->query("SELECT tbl_b.nama as nama_barang,tbl_b.nama_jual as nama_barang_jual, tbl_c.warna_beli as nama_warna,
							tbl_c.warna_jual as nama_warna_jual, barang_id, warna_id, 
							tbl_b.status_aktif as status_barang, tbl_d.nama as nama_satuan
				$select
				FROM (					
						(
					        SELECT barang_id, warna_id, t1.gudang_id, 0 as qty_masuk, 0 as jumlah_roll_masuk, subqty as qty_keluar, subjumlah_roll as jumlah_roll_keluar,3 as tipe, tanggal, t2.id, ifnull(closed_date, t2.created_at) as time_stamp
					        FROM nd_penjualan_detail t1
					        LEFT JOIN (
					        	SELECT *
					        	FROM nd_penjualan
					        	WHERE ifnull(closed_date, created_at) <= '$tanggal_start'
				        		AND ifnull(closed_date, created_at) >= '$tanggal_awal'
				        		AND status_aktif = 1
					        	) t2
					        ON t1.penjualan_id = t2.id
					        where t2.id is not null
					        $cond_barang
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
					        $cond_barang
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
					        $cond_barang
					    )UNION(
					    	SELECT barang_id, warna_id, gudang_id_after, qty, jumlah_roll, 0, 0,2, tanggal, id, created_at
				        	FROM nd_mutasi_barang t1
							WHERE created_at <= '$tanggal_start'
				        	AND created_at >= '$tanggal_awal'
				        	AND status_aktif = 1
					        $cond_barang
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
					        $cond_barang
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
					        $cond_barang
					    )UNION(
					        SELECT barang_id, warna_id, gudang_id, qty, jumlah_roll, 0, 0,5, tanggal, id, created_at
				        	FROM nd_penyesuaian_stok
				        	WHERE tipe_transaksi = 0
				            AND created_at <= '$tanggal_start'
				        	AND created_at >= '$tanggal_awal'
					        $cond_barang
					    )UNION(
					        SELECT barang_id, warna_id, gudang_id, if(tipe_transaksi = 1,qty, 0 ), if(tipe_transaksi = 1,jumlah_roll,0 ), if(tipe_transaksi = 2,qty, 0 ), if(tipe_transaksi=2,jumlah_roll,0),6, tanggal, id, created_at
				        	FROM nd_penyesuaian_stok
				        	WHERE created_at <= '$tanggal_start'
				        	AND created_at >= '$tanggal_awal'
				        	AND tipe_transaksi != 0
					        $cond_barang
					    )UNION(
					    	SELECT barang_id, warna_id, gudang_id_before, 0, 0 , qty, jumlah_roll,8, tanggal, id, created_at
				        	FROM nd_mutasi_barang t1
							WHERE created_at <= '$tanggal_start'	
						    AND created_at >= '$tanggal_awal'
				        	AND status_aktif = 1
					        $cond_barang
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
					        $cond_barang
					    )	
					)tbl_a
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
					) tB
					ON tA.stok_opname_id = tB.id
					WHERE tB.id is not null
			        $cond_barang
					GROUP BY barang_id, warna_id, gudang_id
				) t2
				ON tbl_a.barang_id = t2.barang_id_stok
				AND tbl_a.warna_id = t2.warna_id_stok
				AND tbl_a.gudang_id = t2.gudang_id_stok
				LEFT JOIN nd_barang tbl_b
				ON tbl_a.barang_id = tbl_b.id
				LEFT JOIN nd_warna tbl_c
				ON tbl_a.warna_id = tbl_c.id
				LEFT JOIN nd_satuan tbl_d
				ON tbl_b.satuan_id = tbl_d.id
				Where barang_id is not null
				GROUP BY barang_id, warna_id
				ORDER BY warna_jual asc");
		
		// if (is_posisi_id() == 1) {
			// return $this->db->last_query();
		// }else{
		// }
		return $query->result();

	}


	function get_stok_barang_list_by_barang($tanggal_awal, $select, $tanggal_start, $cond_barang, $stok_opname_id){
				$query = $this->db->query("SELECT tbl_b.nama as nama_barang,tbl_b.nama_jual as nama_barang_jual, tbl_c.warna_beli as nama_warna,tbl_c.warna_jual as nama_warna_jual, barang_id, warna_id, tbl_b.status_aktif as status_barang, tbl_d.nama as nama_satuan
						$select
						FROM (
							(
						        SELECT barang_id, warna_id, t1.gudang_id, 0 as qty_masuk, 0 as jumlah_roll_masuk, qty as qty_keluar, jumlah_roll as jumlah_roll_keluar,3 as tipe, tanggal, t3.id
						        FROM nd_penjualan_detail t1
						        LEFT JOIN (
						        	SELECT *
						        	FROM nd_penjualan
						        	WHERE tanggal <= '$tanggal_start'
						        	AND tanggal >= '$tanggal_awal'
						        	AND status_aktif = 1
						        	) t2
						        ON t1.penjualan_id = t2.id
						        LEFT JOIN (
						            SELECT qty, jumlah_roll, penjualan_detail_id, id
						            FROM nd_penjualan_qty_detail
						            ) t3
						        ON t3.penjualan_detail_id = t1.id
						        where t2.id is not null
						        $cond_barang
						    )UNION(
						        SELECT barang_id, warna_id, t1.gudang_id, 0 as qty_masuk, 0 as jumlah_roll_masuk, qty as qty_keluar, jumlah_roll as jumlah_roll_keluar,20 as tipe, tanggal, t3.id
						        FROM nd_pengeluaran_stok_lain_detail t1
						        LEFT JOIN (
						        	SELECT *
						        	FROM nd_pengeluaran_stok_lain
						        	WHERE tanggal <= '$tanggal_start'
						        	AND tanggal >= '$tanggal_awal'
						        	AND status_aktif = 1
						        	) t2
						        ON t1.pengeluaran_stok_lain_id = t2.id
						        LEFT JOIN (
						            SELECT qty, jumlah_roll, pengeluaran_stok_lain_detail_id, id
						            FROM nd_pengeluaran_stok_lain_qty_detail
						            ) t3
						        ON t3.pengeluaran_stok_lain_detail_id = t1.id
						        where t2.id is not null
						        $cond_barang
						    )UNION(
						        SELECT barang_id, warna_id, t2.gudang_id, qty, jumlah_roll, 0, 0, 1, tanggal, t1.id
						        FROM nd_pembelian_detail t1
						        LEFT JOIN (
						        	SELECT *
						        	FROM nd_pembelian
						        	WHERE tanggal <= '$tanggal_start'
						        	AND tanggal >= '$tanggal_awal'
						        	AND status_aktif = 1
						        	) t2
						        ON t1.pembelian_id = t2.id
						        WHERE t2.id is not null
						        $cond_barang
						    )UNION(
						    	SELECT barang_id, warna_id, gudang_id_after, qty, jumlah_roll, 0, 0,2, tanggal, id
					        	FROM nd_mutasi_barang t1
								WHERE tanggal <= '$tanggal_start'
					        	AND tanggal >= '$tanggal_awal'
					        	AND status_aktif = 1
						        $cond_barang
						    )UNION(
						    	SELECT barang_id, warna_id, t1.gudang_id, qty, jumlah_roll, 0, 0, 4, tanggal, t3.id
						        FROM nd_retur_jual_detail t1
						        LEFT JOIN (
						        	SELECT *
						        	FROM nd_retur_jual
						        	WHERE tanggal <= '$tanggal_start'
						        	AND tanggal >= '$tanggal_awal'
						        	AND status_aktif = 1
						        	) t2
						        ON t1.retur_jual_id = t2.id
						        LEFT JOIN (
						            SELECT qty, jumlah_roll, retur_jual_detail_id, id
						            FROM nd_retur_jual_qty
						            ) t3
						        ON t3.retur_jual_detail_id = t1.id
						        WHERE t2.id is not null
						        $cond_barang
						    )UNION(
						    	SELECT barang_id, warna_id, t2.gudang_id,0,0, qty, jumlah_roll, 19, tanggal, t1.id
						        FROM (
						        	SELECT *
						        	FROM nd_retur_beli
						        	WHERE tanggal <= '$tanggal_start'
						        	AND tanggal >= '$tanggal_awal'
						        	AND status_aktif = 1
						        	) t1
						        LEFT JOIN nd_retur_beli_detail t2
						        ON t2.retur_beli_id = t1.id
						        LEFT JOIN (
						            SELECT qty, jumlah_roll, retur_beli_detail_id
						            FROM nd_retur_beli_qty
						            ) t3
						        ON t3.retur_beli_detail_id = t2.id
						        $cond_barang
						    )UNION(
						        SELECT barang_id, warna_id, gudang_id, qty, jumlah_roll, 0, 0,5, tanggal, id
					        	FROM nd_penyesuaian_stok
					        	WHERE tipe_transaksi = 0
					            AND tanggal <= '$tanggal_start'
					        	AND tanggal >= '$tanggal_awal'
						        $cond_barang
						    )UNION(
						        SELECT barang_id, warna_id, gudang_id, if(tipe_transaksi = 1,qty, 0 ), if(tipe_transaksi = 1,jumlah_roll,0 ), if(tipe_transaksi = 2,qty, 0 ), if(tipe_transaksi=2,jumlah_roll,0),6, tanggal, id
					        	FROM nd_penyesuaian_stok
					        	WHERE tanggal <= '$tanggal_start'
					        	AND tanggal >= '$tanggal_awal'
					        	AND tipe_transaksi != 0
						        $cond_barang
						    )UNION(
						    	SELECT barang_id, warna_id, gudang_id_before, 0, 0 , qty, jumlah_roll,8, tanggal, id
					        	FROM nd_mutasi_barang t1
								WHERE tanggal <= '$tanggal_start'	
							    AND tanggal >= '$tanggal_awal'
					        	AND status_aktif = 1
						        $cond_barang
						    )UNION(
						    	SELECT barang_id, warna_id, gudang_id, qty, jumlah_roll, 0, 0, 9, tanggal, t1.id
					        	FROM nd_hasil_SO t1 
								LEFT JOIN (
									SELECT *
									FROM nd_stok_opname
									WHERE tanggal <= '$tanggal_start'	
								    AND tanggal >= '$tanggal_awal'
						        	AND status_aktif = 1
									) t2
								ON t1.stok_opname_id = t2.id
								WHERE status_aktif=1
						        $cond_barang
						    )
						)tbl_a
						LEFT JOIN (
							SELECT barang_id as barang_id_stok, warna_id as warna_id_stok,gudang_id as gudang_id_stok, MAX(tanggal) as tanggal_stok
						    FROM (
						    	SELECT stok_opname_id, barang_id,warna_id, gudang_id
						    	FROM nd_hasil_SO
						    	GROUP BY barang_id, warna_id, gudang_id, stok_opname_id
							) tA
							LEFT JOIN (
								SELECT *
								FROM nd_stok_opname
								WHERE status_aktif = 1
							) tB
							ON tA.stok_opname_id = tB.id
							WHERE tB.id is not null
					        $cond_barang
							GROUP BY barang_id, warna_id, gudang_id
						) t2
						ON tbl_a.barang_id = t2.barang_id_stok
						AND tbl_a.warna_id = t2.warna_id_stok
						AND tbl_a.gudang_id = t2.gudang_id_stok
						LEFT JOIN nd_barang tbl_b
						ON tbl_a.barang_id = tbl_b.id
						LEFT JOIN nd_warna tbl_c
						ON tbl_a.warna_id = tbl_c.id
						LEFT JOIN nd_satuan tbl_d
						ON tbl_b.satuan_id = tbl_d.id
						Where barang_id is not null
						GROUP BY barang_id, warna_id
						ORDER BY warna_jual asc");
				
				// if (is_posisi_id() == 1) {
				// 	return $this->db->last_query();
				// }else{
				// }
				return $query->result();

			}

	function get_stok_barang_satuan($gudang_id, $barang_id, $warna_id, $tanggal_start, $tanggal_end, $tanggal_awal, $stok_opname_id){
		$query = $this->db->query("SELECT tbl_b.nama as nama_barang, tanggal, tbl_c.warna_beli as nama_warna, barang_id, warna_id, qty_masuk, qty_keluar, jumlah_roll_masuk, jumlah_roll_keluar, no_faktur, tipe, trx_id, tbl_a.id, qty_data, roll_data
				FROM(
					(
				        SELECT barang_id, warna_id, t2.gudang_id, 
				        qty as qty_masuk, jumlah_roll as jumlah_roll_masuk, 
				        CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar, 
				        tanggal, concat(no_faktur,if(qty <= 5, concat(' (<b>',ockh_info,'</b>)'), '' )) as no_faktur, 'a1' as tipe, t2.id as trx_id, t1.id, qty_data, roll_data
				        FROM (
				        	SELECT *
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
				        	 SELECT pembelian_detail_id, group_concat(qty) as qty_data, group_concat(jumlah_roll) as roll_data
				            FROM nd_pembelian_qty_detail
				            GROUP BY pembelian_detail_id
				        	) t3
				        ON t3.pembelian_detail_id = t1.id
				        WHERE barang_id is not null 
				        AND warna_id is not null
				        AND t2.id is not null
				    )UNION(
				        SELECT barang_id, warna_id, t1.gudang_id, CAST(0 as DECIMAL(15,2)) as qty_masuk, 0 as jumlah_roll_masuk, qty as qty_keluar, jumlah_roll as jumlah_roll_keluar, tanggal, no_faktur_lengkap, 'a2' as tipe, t2.id as trx_id, t1.id, qty_data, roll_data
				        FROM (
				        	SELECT *
				        	FROM nd_penjualan_detail 
				        	WHERE gudang_id = $gudang_id 
				        	AND barang_id = $barang_id
				        	AND warna_id = $warna_id
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
				            SELECT sum(qty * if(jumlah_roll = 0,1,jumlah_roll) ) as qty, sum(jumlah_roll) as jumlah_roll, penjualan_detail_id, group_concat(qty) as qty_data, group_concat(jumlah_roll) as roll_data
				            FROM nd_penjualan_qty_detail
				            GROUP BY penjualan_detail_id
				            ) t3
				        ON t3.penjualan_detail_id = t1.id
				        WHERE barang_id is not null 
				        AND warna_id is not null
				        AND t2.id is not null
				    )UNION(
				        SELECT barang_id, warna_id, t1.gudang_id, CAST(0 as DECIMAL(15,2)) as qty_masuk, 0 as jumlah_roll_masuk, qty as qty_keluar, jumlah_roll as jumlah_roll_keluar, tanggal, no_faktur_lengkap, 'a6' as tipe, t2.id as trx_id, t1.id, qty_data, roll_data
				        FROM (
				        	SELECT *
				        	FROM nd_pengeluaran_stok_lain_detail 
				        	WHERE gudang_id = $gudang_id 
				        	AND barang_id = $barang_id
				        	AND warna_id = $warna_id
				        	) t1
				        LEFT JOIN (
				        	SELECT *, concat(if(no_faktur is not null, concat('RR', DATE_FORMAT(tanggal,'%y/%m'),'-',LPAD(no_faktur,3,'0'),if(keterangan != '', '<br/>','')),''), keterangan) as no_faktur_lengkap
				        	FROM nd_pengeluaran_stok_lain
				        	WHERE tanggal >= '$tanggal_start'
				        	AND tanggal <= '$tanggal_end'
				        	AND tanggal >= '$tanggal_awal'
				        	AND status_aktif = 1
				        	) t2
				        ON t1.pengeluaran_stok_lain_id = t2.id
				        LEFT JOIN (
				            SELECT sum(qty * if(jumlah_roll = 0,1,jumlah_roll) ) as qty, sum(jumlah_roll) as jumlah_roll, pengeluaran_stok_lain_detail_id, group_concat(qty) as qty_data, group_concat(jumlah_roll) as roll_data
				            FROM nd_pengeluaran_stok_lain_qty_detail
				            GROUP BY pengeluaran_stok_lain_detail_id
				            ) t3
				        ON t3.pengeluaran_stok_lain_detail_id = t1.id
				        WHERE barang_id is not null 
				        AND warna_id is not null
				        AND t2.id is not null
				    )UNION(
				    	SELECT barang_id, warna_id, t2.gudang_id, CAST(qty as DECIMAL(15,2)) as qty_masuk, jumlah_roll as jumlah_roll_masuk, CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar, tanggal, no_faktur_lengkap, 'a3' as tipe, t1.id as trx_id, t2.id, qty_data, roll_data
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
				            SELECT sum(qty * if(jumlah_roll != 0, jumlah_roll,1) ) as qty, sum(jumlah_roll) as jumlah_roll, retur_jual_detail_id, group_concat(qty) as qty_data, group_concat(jumlah_roll) as roll_data
				            FROM nd_retur_jual_qty
				            GROUP BY retur_jual_detail_id
				            ) t3
				        ON t3.retur_jual_detail_id = t2.id
				        WHERE barang_id is not null 
				        AND warna_id is not null
				    )UNION(
				    	SELECT barang_id, warna_id, t2.gudang_id, 0,0, CAST(qty as DECIMAL(15,2)) , jumlah_roll , tanggal, no_faktur_lengkap, 'a3r' as tipe, t1.id as trx_id, t2.id, qty_data, roll_data
				        FROM (
				        	SELECT t1.*, concat('SJ/R/',if(t2.kode is not null, concat(t2.kode,'/'),'LL/'),DATE_FORMAT(tanggal,'%y'),'-',no_sj, ' (<b>',keterangan2,'</b>)' ) as no_faktur_lengkap
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
				            SELECT sum(qty * if(jumlah_roll != 0, jumlah_roll,1) ) as qty, sum(jumlah_roll) as jumlah_roll, retur_beli_detail_id, group_concat(qty) as qty_data, group_concat(jumlah_roll) as roll_data
				            FROM nd_retur_beli_qty
				            GROUP BY retur_beli_detail_id
				            ) t3
				        ON t3.retur_beli_detail_id = t2.id
				        WHERE barang_id is not null 
				        AND warna_id is not null
				    )UNION(
				        SELECT barang_id, warna_id, gudang_id, qty as qty_masuk, jumlah_roll as jumlah_roll_masuk, CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar, tanggal, concat_ws('??', nd_user.username, user_id, nd_penyesuaian_stok.id  ), 0 as tipe, nd_penyesuaian_stok.id, '', qty, jumlah_roll
				        FROM (
				        	SELECT *
				        	FROM nd_penyesuaian_stok
				        	WHERE tanggal >= '$tanggal_start'
				        	AND tanggal <= '$tanggal_end'
				        	AND tanggal >= '$tanggal_awal'
				        	AND barang_id = $barang_id
				        	AND warna_id = $warna_id
				        	AND gudang_id = $gudang_id
				        	AND tipe_transaksi = 0
				    	) nd_penyesuaian_stok
						LEFT JOIN nd_user
						ON nd_penyesuaian_stok.user_id = nd_user.id
						WHERE barang_id is not null 
				        AND warna_id is not null
				    )UNION(
				        SELECT barang_id, warna_id, gudang_id, qty as qty_masuk, jumlah_roll as jumlah_roll_masuk, CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar, tanggal, concat_ws('??', ifnull(keterangan,concat('Pemutihan oleh: ',nd_user.username)), user_id, t1.id  ), 1 as tipe,t1.id,detail_id, qty_data, jumlah_roll_data
				        FROM (
				        	SELECT *
				        	FROM nd_penyesuaian_stok
				        	WHERE tanggal >= '$tanggal_start'
				        	AND tanggal <= '$tanggal_end'
				        	AND tanggal >= '$tanggal_awal'
				        	AND barang_id = $barang_id
				        	AND warna_id = $warna_id
				        	AND gudang_id = $gudang_id
				        	AND tipe_transaksi = 1
				    	) t1
						LEFT JOIN (
							SELECT group_concat(qty)as qty_data, group_concat(jumlah_roll) as jumlah_roll_data, penyesuaian_stok_id, group_concat(id) as detail_id
							FROM nd_penyesuaian_stok_qty
							GROUP BY penyesuaian_stok_id
							) t2
						ON t1.id = t2.penyesuaian_stok_id						
						LEFT JOIN nd_user
						ON t1.user_id = nd_user.id
						WHERE barang_id is not null 
				        AND warna_id is not null
				    )UNION(
				        SELECT barang_id, warna_id, gudang_id, CAST(0 as DECIMAL(15,2)) as qty_masuk, 0 , qty as qty_keluar, jumlah_roll, tanggal, concat_ws('??', ifnull(keterangan,concat('Pemutihan oleh: ',nd_user.username)), user_id, nd_penyesuaian_stok.id  ), 2 as tipe,nd_penyesuaian_stok.id,'', qty, jumlah_roll
				        FROM (
				        	SELECT *
				        	FROM nd_penyesuaian_stok
				        	WHERE tanggal >= '$tanggal_start'
				        	AND tanggal <= '$tanggal_end'
				        	AND tanggal >= '$tanggal_awal'
				        	AND barang_id = $barang_id
				        	AND warna_id = $warna_id
				        	AND gudang_id = $gudang_id
				        	AND tipe_transaksi = 2
				    	) nd_penyesuaian_stok
						LEFT JOIN nd_user
						ON nd_penyesuaian_stok.user_id = nd_user.id
						WHERE barang_id is not null 
				        AND warna_id is not null
				    )UNION(
				    	SELECT barang_id, warna_id, gudang_id_after, t1.qty as qty_masuk, t1.jumlah_roll as jumlah_roll_masuk, CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar, tanggal ,nd_gudang.nama, 'b1' as tipe,t1.id,'', qty_data, roll_data
				    	FROM (
				        	SELECT *
				        	FROM nd_mutasi_barang
				        	WHERE tanggal >= '$tanggal_start'
				        	AND tanggal <= '$tanggal_end'
				        	AND tanggal >= '$tanggal_awal'
				        	AND gudang_id_after = $gudang_id
				        	AND barang_id = $barang_id
				        	AND warna_id = $warna_id
				        	AND status_aktif = 1
				        	) t1
						LEFT JOIN (
							SELECT group_concat(qty) as qty_data, group_concat(jumlah_roll) as roll_data, mutasi_barang_id
							FROM nd_mutasi_barang_qty
							GROUP BY mutasi_barang_id
							) t2
						ON t1.id = t2.mutasi_barang_id
						LEFT JOIN nd_gudang
						ON t1.gudang_id_before = nd_gudang.id
						WHERE barang_id is not null 
				        AND warna_id is not null
				    )UNION(
				    	SELECT barang_id, warna_id, gudang_id_before, CAST(0 as DECIMAL(15,2)) as qty_masuk, 0 as jumlah_roll_masuk, t1.qty as qty_keluar, t1.jumlah_roll as jumlah_roll_keluar, tanggal,nd_gudang.nama, 'b2' as tipe, t1.id, '',qty_data, roll_data
				    	FROM (
				        	SELECT *
				        	FROM nd_mutasi_barang
				        	WHERE tanggal >= '$tanggal_start'
				        	AND tanggal <= '$tanggal_end'
				        	AND tanggal >= '$tanggal_awal'
				        	AND gudang_id_before = $gudang_id
				        	AND barang_id = $barang_id
				        	AND warna_id = $warna_id
				        	AND status_aktif = 1
				        	) t1
						LEFT JOIN (
							SELECT group_concat(qty) as qty_data, group_concat(jumlah_roll) as roll_data, mutasi_barang_id
							FROM nd_mutasi_barang_qty
							GROUP BY mutasi_barang_id
							) t2
						ON t1.id = t2.mutasi_barang_id
						LEFT JOIN nd_gudang
						ON t1.gudang_id_after = nd_gudang.id
						WHERE barang_id is not null 
				        AND warna_id is not null
				    )UNION(
				    	SELECT barang_id, warna_id, gudang_id, qty , jumlah_roll, CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar, tanggal, '', 'z1' as tipe, a.id as trx_id,'', qty_data, roll_data
				    	FROM (
				    		SELECT stok_opname_id, barang_id, warna_id, gudang_id,  sum(qty*jumlah_roll) as qty, sum(jumlah_roll) as jumlah_roll, id,group_concat(qty) as qty_data, group_concat(jumlah_roll) as roll_data
				    		FROM nd_stok_opname_detail	
				    		WHERE barang_id = $barang_id
				        	AND warna_id = $warna_id
				        	AND gudang_id = $gudang_id
							GROUP BY stok_opname_id
				        	) a
				    	LEFT JOIN (
				    		SELECT *
				    		FROM nd_stok_opname
				    		WHERE tanggal >= '$tanggal_start'
				        	AND tanggal >= '$tanggal_awal'
				        	AND tanggal <= '$tanggal_end'
				        	AND status_aktif = 1
				    		) b
						ON a.stok_opname_id = b.id
						WHERE b.id is not null
				    )UNION(
				        SELECT barang_id, warna_id, gudang_id, t2.qty, t2.jumlah_roll , t1.qty as qty_keluar, t1.jumlah_roll, tanggal, concat_ws('??', t1.keterangan, t2.qty_data, t2.roll_data  ), 3 as tipe,t1.id, split_id, qty_data, roll_data
				        FROM (
				        	SELECT *
				        	FROM nd_penyesuaian_stok
				        	WHERE tanggal >= '$tanggal_start'
				        	AND tanggal <= '$tanggal_end'
				        	AND tanggal >= '$tanggal_awal'
				        	AND barang_id = $barang_id
				        	AND warna_id = $warna_id
				        	AND gudang_id = $gudang_id
				        	AND tipe_transaksi = 3
				    	) t1
						LEFT JOIN (
							SELECT sum(qty * if(jumlah_roll = 0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, group_concat(qty)as qty_data, group_concat(jumlah_roll) as roll_data, penyesuaian_stok_id, group_concat(id) as split_id
							FROM nd_penyesuaian_stok_split
							GROUP BY penyesuaian_stok_id) t2
						ON t1.id = t2.penyesuaian_stok_id
						WHERE barang_id is not null 
				        AND warna_id is not null
				    )
				) tbl_a
				LEFT JOIN nd_barang tbl_b
				ON tbl_a.barang_id = tbl_b.id
				LEFT JOIN nd_warna tbl_c
				ON tbl_a.warna_id = tbl_c.id
				Where barang_id is not null
				ORDER BY tanggal asc,id asc, qty_masuk desc
				");
		
		return $query->result();
		// return $this->db->last_query();
	}

	function get_stok_barang_satuan_2($gudang_id, $barang_id, $warna_id, $tanggal_start, $tanggal_end, $tanggal_awal, $stok_opname_id){
		// $tanggal_awal = $tanggal_start;
		$query = $this->db->query("SELECT tbl_b.nama as nama_barang, tanggal, tbl_c.warna_beli as nama_warna, barang_id, warna_id, qty_masuk, qty_keluar, jumlah_roll_masuk, jumlah_roll_keluar, no_faktur, tipe, trx_id, tbl_a.id, qty_data, roll_data, time_stamp
				FROM(
					(
				        SELECT barang_id, warna_id, t2.gudang_id, 
				        qty as qty_masuk, jumlah_roll as jumlah_roll_masuk, 
				        CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar, 
				        tanggal, concat(no_faktur,if(qty <= 5, concat(' (<b>',ockh_info,'</b>)'), '' )) as no_faktur, 'a1' as tipe, t2.id as trx_id, t1.id, qty_data, roll_data, created_at as time_stamp
				        FROM (
				        	SELECT *
				        	FROM nd_pembelian_detail
				        	WHERE barang_id = $barang_id
				        	AND warna_id = $warna_id
				        	) t1
				        LEFT JOIN (
				        	SELECT *
				        	FROM nd_pembelian
				        	WHERE created_at >= '$tanggal_start'
				        	AND created_at <= '$tanggal_end 23:59:59'
				        	AND created_at >= '$tanggal_awal'
				        	AND gudang_id = $gudang_id
				        	AND status_aktif = 1
				        	) t2
				        ON t1.pembelian_id = t2.id
				        LEFT JOIN (
				        	 SELECT pembelian_detail_id, group_concat(qty) as qty_data, group_concat(jumlah_roll) as roll_data
				            FROM nd_pembelian_qty_detail
				            GROUP BY pembelian_detail_id
				        	) t3
				        ON t3.pembelian_detail_id = t1.id
				        WHERE barang_id is not null 
				        AND warna_id is not null
				        AND t2.id is not null
				    )UNION(
				        SELECT barang_id, warna_id, t1.gudang_id, CAST(0 as DECIMAL(15,2)) as qty_masuk, 0 as jumlah_roll_masuk, qty as qty_keluar, 
						jumlah_roll as jumlah_roll_keluar, tanggal, no_faktur_lengkap, 'a2' as tipe, t2.id as trx_id, t1.id, qty_data, roll_data, 
						ifnull(closed_date, t2.created_at)
				        FROM (
				        	SELECT *
				        	FROM nd_penjualan_detail 
				        	WHERE gudang_id = $gudang_id 
				        	AND barang_id = $barang_id
				        	AND warna_id = $warna_id
				        	) t1
				        LEFT JOIN (
				        	-- SELECT *
							-- FROM vw_penjualan_data
							SELECT tA.*, concat(IF(penjualan_type_id != 3, concat(ifnull(tipe_company,''),tB.nama), nama_keterangan),' / ', no_faktur_pad) as no_faktur_lengkap
							FROM (
								SELECT *, LPAD(no_faktur,4,'0') as no_faktur_pad
								FROM nd_penjualan
								WHERE ifnull(closed_date, created_at) >= '$tanggal_start'
								AND ifnull(closed_date, created_at) <= '$tanggal_end 23:59:59'
								AND ifnull(closed_date, created_at) >= '$tanggal_awal'
								AND status_aktif = 1
								)tA
								LEFT JOIN nd_customer tB
								ON tA.customer_id = tB.id 
				        	) t2
				        ON t1.penjualan_id = t2.id
				        LEFT JOIN (
				            SELECT sum(qty * if(jumlah_roll = 0,1,jumlah_roll) ) as qty, sum(jumlah_roll) as jumlah_roll, penjualan_detail_id, group_concat(qty) as qty_data, group_concat(jumlah_roll) as roll_data
				            FROM nd_penjualan_qty_detail
				            GROUP BY penjualan_detail_id
				            ) t3
				        ON t3.penjualan_detail_id = t1.id
				        WHERE barang_id is not null 
				        AND warna_id is not null
				        AND t2.id is not null
				    )UNION(
				        SELECT barang_id, warna_id, t1.gudang_id, CAST(0 as DECIMAL(15,2)) as qty_masuk, 0 as jumlah_roll_masuk, qty as qty_keluar, jumlah_roll as jumlah_roll_keluar, tanggal, no_faktur_lengkap, 'a6' as tipe, t2.id as trx_id, t1.id, qty_data, roll_data, t2.created_at
				        FROM (
				        	SELECT *
				        	FROM nd_pengeluaran_stok_lain_detail 
				        	WHERE gudang_id = $gudang_id 
				        	AND barang_id = $barang_id
				        	AND warna_id = $warna_id
				        	) t1
				        LEFT JOIN (
				        	SELECT *, concat(if(no_faktur is not null, concat('RR', DATE_FORMAT(tanggal,'%y/%m'),'-',LPAD(no_faktur,3,'0'),if(keterangan != '', '<br/>','')),''), keterangan) as no_faktur_lengkap
				        	FROM nd_pengeluaran_stok_lain
				        	WHERE created_at >= '$tanggal_start'
				        	AND created_at <= '$tanggal_end 23:59:59'
				        	AND created_at >= '$tanggal_awal'
				        	AND status_aktif = 1
				        	) t2
				        ON t1.pengeluaran_stok_lain_id = t2.id
				        LEFT JOIN (
				            SELECT sum(qty * if(jumlah_roll = 0,1,jumlah_roll) ) as qty, sum(jumlah_roll) as jumlah_roll, pengeluaran_stok_lain_detail_id, group_concat(qty) as qty_data, group_concat(jumlah_roll) as roll_data
				            FROM nd_pengeluaran_stok_lain_qty_detail
				            GROUP BY pengeluaran_stok_lain_detail_id
				            ) t3
				        ON t3.pengeluaran_stok_lain_detail_id = t1.id
				        WHERE barang_id is not null 
				        AND warna_id is not null
				        AND t2.id is not null
				    )UNION(
				    	SELECT barang_id, warna_id, t2.gudang_id, CAST(qty as DECIMAL(15,2)) as qty_masuk, jumlah_roll as jumlah_roll_masuk, CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar, tanggal, no_faktur_lengkap, 'a3' as tipe, t1.id as trx_id, t2.id, qty_data, roll_data, created_at
				        FROM (
				        	SELECT *, concat('FRJ', DATE_FORMAT(tanggal,'%d%m%y'),'-',LPAD(no_faktur,4,'0')) as no_faktur_lengkap
				        	FROM nd_retur_jual
				        	WHERE created_at >= '$tanggal_start'
				        	AND created_at <= '$tanggal_end 23:59:59'
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
				            SELECT sum(qty * if(jumlah_roll != 0, jumlah_roll,1) ) as qty, sum(jumlah_roll) as jumlah_roll, retur_jual_detail_id, group_concat(qty) as qty_data, group_concat(jumlah_roll) as roll_data
				            FROM nd_retur_jual_qty
				            GROUP BY retur_jual_detail_id
				            ) t3
				        ON t3.retur_jual_detail_id = t2.id
				        WHERE barang_id is not null 
				        AND warna_id is not null
				    )UNION(
				    	SELECT barang_id, warna_id, t2.gudang_id, 0,0, CAST(qty as DECIMAL(15,2)) , jumlah_roll , tanggal, no_faktur_lengkap, 'a3r' as tipe, t1.id as trx_id, t2.id, qty_data, roll_data, created_at
				        FROM (
				        	SELECT t1.*, concat('SJ/R/',if(t2.kode is not null, concat(t2.kode,'/'),'LL/'),DATE_FORMAT(tanggal,'%y'),'-',no_sj, ' (<b>',keterangan2,'</b>)' ) as no_faktur_lengkap
				        	FROM nd_retur_beli t1
				        	LEFT JOIN nd_supplier t2
				        	ON t1.supplier_id = t2.id
				        	WHERE created_at >= '$tanggal_start'
				        	AND created_at <= '$tanggal_end 23:59:59'
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
				            SELECT sum(qty * if(jumlah_roll != 0, jumlah_roll,1) ) as qty, sum(jumlah_roll) as jumlah_roll, retur_beli_detail_id, group_concat(qty) as qty_data, group_concat(jumlah_roll) as roll_data
				            FROM nd_retur_beli_qty
				            GROUP BY retur_beli_detail_id
				            ) t3
				        ON t3.retur_beli_detail_id = t2.id
				        WHERE barang_id is not null 
				        AND warna_id is not null
				    )UNION(
				        SELECT barang_id, warna_id, gudang_id, qty as qty_masuk, jumlah_roll as jumlah_roll_masuk, CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar, tanggal, concat_ws('??', nd_user.username, user_id, nd_penyesuaian_stok.id  ), 0 as tipe, nd_penyesuaian_stok.id, '', qty, jumlah_roll, created_at
				        FROM (
				        	SELECT *
				        	FROM nd_penyesuaian_stok
				        	WHERE created_at >= '$tanggal_start'
				        	AND created_at <= '$tanggal_end 23:59:59'
				        	AND created_at >= '$tanggal_awal'
				        	AND barang_id = $barang_id
				        	AND warna_id = $warna_id
				        	AND gudang_id = $gudang_id
				        	AND tipe_transaksi = 0
				    	) nd_penyesuaian_stok
						LEFT JOIN nd_user
						ON nd_penyesuaian_stok.user_id = nd_user.id
						WHERE barang_id is not null 
				        AND warna_id is not null
				    )UNION(
				        SELECT barang_id, warna_id, gudang_id, qty as qty_masuk, jumlah_roll as jumlah_roll_masuk, CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar, tanggal, concat_ws('??', ifnull(keterangan,if(tipe_transaksi = 1 ,concat('Pemutihan oleh: ',nd_user.username), 'stok opname' ) ), user_id, t1.id  ), if(tipe_transaksi=1,1,11) as tipe,t1.id,detail_id, qty_data, jumlah_roll_data, created_at
				        FROM (
				        	SELECT *
				        	FROM nd_penyesuaian_stok
				        	WHERE created_at >= '$tanggal_start'
				        	AND created_at <= '$tanggal_end 23:59:59'
				        	AND created_at >= '$tanggal_awal'
				        	AND barang_id = $barang_id
				        	AND warna_id = $warna_id
				        	AND gudang_id = $gudang_id
				        	AND (
				        		tipe_transaksi = 1 OR tipe_transaksi = 4 
				        	 )
				    	) t1
						LEFT JOIN (
							SELECT group_concat(qty)as qty_data, group_concat(jumlah_roll) as jumlah_roll_data, penyesuaian_stok_id, group_concat(id) as detail_id
							FROM nd_penyesuaian_stok_qty
							GROUP BY penyesuaian_stok_id
							) t2
						ON t1.id = t2.penyesuaian_stok_id						
						LEFT JOIN nd_user
						ON t1.user_id = nd_user.id
						WHERE barang_id is not null 
				        AND warna_id is not null
				    )UNION(
				        SELECT barang_id, warna_id, gudang_id, CAST(0 as DECIMAL(15,2)) as qty_masuk, 0 , qty as qty_keluar, jumlah_roll, tanggal, concat_ws('??', ifnull(keterangan,concat('Pemutihan oleh: ',nd_user.username)), user_id, nd_penyesuaian_stok.id  ), 2 as tipe,nd_penyesuaian_stok.id,'', qty, jumlah_roll, created_at
				        FROM (
				        	SELECT *
				        	FROM nd_penyesuaian_stok
				        	WHERE created_at >= '$tanggal_start'
				        	AND created_at <= '$tanggal_end 23:59:59'
				        	AND created_at >= '$tanggal_awal'
				        	AND barang_id = $barang_id
				        	AND warna_id = $warna_id
				        	AND gudang_id = $gudang_id
				        	AND tipe_transaksi = 2
				    	) nd_penyesuaian_stok
						LEFT JOIN nd_user
						ON nd_penyesuaian_stok.user_id = nd_user.id
						WHERE barang_id is not null 
				        AND warna_id is not null
				    )UNION(
				    	SELECT barang_id, warna_id, gudang_id_after, t1.qty as qty_masuk, t1.jumlah_roll as jumlah_roll_masuk, CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar, tanggal ,nd_gudang.nama, 'b1' as tipe,t1.id,'', qty_data, roll_data, created_at
				    	FROM (
				        	SELECT *
				        	FROM nd_mutasi_barang
				        	WHERE created_at >= '$tanggal_start'
				        	AND created_at <= '$tanggal_end 23:59:59'
				        	AND created_at >= '$tanggal_awal'
				        	AND gudang_id_after = $gudang_id
				        	AND barang_id = $barang_id
				        	AND warna_id = $warna_id
				        	AND status_aktif = 1
				        	) t1
						LEFT JOIN (
							SELECT group_concat(qty) as qty_data, group_concat(jumlah_roll) as roll_data, mutasi_barang_id
							FROM nd_mutasi_barang_qty
							GROUP BY mutasi_barang_id
							) t2
						ON t1.id = t2.mutasi_barang_id
						LEFT JOIN nd_gudang
						ON t1.gudang_id_before = nd_gudang.id
						WHERE barang_id is not null 
				        AND warna_id is not null
				    )UNION(
				    	SELECT barang_id, warna_id, gudang_id_before, CAST(0 as DECIMAL(15,2)) as qty_masuk, 0 as jumlah_roll_masuk, t1.qty as qty_keluar, t1.jumlah_roll as jumlah_roll_keluar, tanggal,nd_gudang.nama, 'b2' as tipe, t1.id, '',qty_data, roll_data, created_at
				    	FROM (
				        	SELECT *
				        	FROM nd_mutasi_barang
				        	WHERE created_at >= '$tanggal_start'
				        	AND created_at <= '$tanggal_end 23:59:59'
				        	AND created_at >= '$tanggal_awal'
				        	AND gudang_id_before = $gudang_id
				        	AND barang_id = $barang_id
				        	AND warna_id = $warna_id
				        	AND status_aktif = 1
				        	) t1
						LEFT JOIN (
							SELECT group_concat(qty) as qty_data, group_concat(jumlah_roll) as roll_data, mutasi_barang_id
							FROM nd_mutasi_barang_qty
							GROUP BY mutasi_barang_id
							) t2
						ON t1.id = t2.mutasi_barang_id
						LEFT JOIN nd_gudang
						ON t1.gudang_id_after = nd_gudang.id
						WHERE barang_id is not null 
				        AND warna_id is not null
				    )UNION(
				    	SELECT barang_id, warna_id, gudang_id, qty , jumlah_roll, CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar, tanggal, '', 'z1' as tipe, a.id as trx_id,'', qty_data, roll_data, created_at
				    	FROM (
				    		SELECT stok_opname_id, barang_id, warna_id, gudang_id,  sum(qty*jumlah_roll) as qty, sum(jumlah_roll) as jumlah_roll, id,group_concat(qty) as qty_data, group_concat(jumlah_roll) as roll_data
				    		FROM nd_stok_opname_detail	
				    		WHERE barang_id = $barang_id
				        	AND warna_id = $warna_id
				        	AND gudang_id = $gudang_id
							GROUP BY stok_opname_id
				        	) a
				    	LEFT JOIN (
				    		SELECT *
				    		FROM nd_stok_opname
				    		WHERE created_at >= '$tanggal_start'
				        	AND created_at >= '$tanggal_awal'
				        	AND created_at <= '$tanggal_end 23:59:59'
				        	AND status_aktif = 1
				    		) b
						ON a.stok_opname_id = b.id
						WHERE b.id is not null
				    )UNION(
				        SELECT barang_id, warna_id, gudang_id, t2.qty, t2.jumlah_roll , t1.qty as qty_keluar, t1.jumlah_roll, tanggal, concat_ws('??', t1.keterangan, t2.qty_data, t2.roll_data  ), 3 as tipe,t1.id, split_id, qty_data, roll_data, created_at
				        FROM (
				        	SELECT *
				        	FROM nd_penyesuaian_stok
				        	WHERE created_at >= '$tanggal_start'
				        	AND created_at <= '$tanggal_end 23:59:59'
				        	AND created_at >= '$tanggal_awal'
				        	AND barang_id = $barang_id
				        	AND warna_id = $warna_id
				        	AND gudang_id = $gudang_id
				        	AND tipe_transaksi = 3
				    	) t1
						LEFT JOIN (
							SELECT sum(qty * if(jumlah_roll = 0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, group_concat(qty)as qty_data, group_concat(jumlah_roll) as roll_data, penyesuaian_stok_id, group_concat(id) as split_id
							FROM nd_penyesuaian_stok_split
							GROUP BY penyesuaian_stok_id) t2
						ON t1.id = t2.penyesuaian_stok_id
						WHERE barang_id is not null 
				        AND warna_id is not null
				    )
				) tbl_a
				LEFT JOIN nd_barang tbl_b
				ON tbl_a.barang_id = tbl_b.id
				LEFT JOIN nd_warna tbl_c
				ON tbl_a.warna_id = tbl_c.id
				Where barang_id is not null
				ORDER BY time_stamp asc
				");
		
		return $query->result();
		// return $this->db->last_query();
	}

	function get_stok_barang_satuan_awal($gudang_id, $barang_id, $warna_id, $tanggal_start, $tanggal_awal, $stok_opname_id){
		$query = $this->db->query("SELECT tbl_b.nama as nama_barang,nama_jual, tanggal, tbl_c.warna_beli as nama_warna, barang_id, warna_id, sum(if(tanggal_stok is not null,if(tanggal >= tanggal_stok, qty_masuk,0) ,qty_masuk) ) as qty_masuk, 
			ROUND(sum(if(tanggal_stok is not null,if(tanggal >= tanggal_stok,qty_keluar,0), qty_keluar) ),3) qty_keluar, 
			sum(if(tanggal_stok is not null,if(tanggal >= tanggal_stok,jumlah_roll_masuk,0), jumlah_roll_masuk)) as jumlah_roll_masuk, 
			sum(if(tanggal_stok is not null,if(tanggal >= tanggal_stok,jumlah_roll_keluar,0), jumlah_roll_keluar)) jumlah_roll_keluar, if(tanggal_stok is not null,tanggal_stok,0), 
			sum(if(tanggal >= tanggal_stok, 1,0))
				FROM(
				    (
				        SELECT barang_id, warna_id, t2.gudang_id, 
				        qty as qty_masuk, jumlah_roll as jumlah_roll_masuk, 
				        0 as qty_keluar, 0 as jumlah_roll_keluar, 
				        tanggal, 1 as tipe,t1.id
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
				        WHERE t2.id is not null
				        AND qty > if(barang_id = 101 ,0,1)
				    )UNION(
				        SELECT barang_id, warna_id, gudang_id_after, qty , jumlah_roll , 0, 0, tanggal, 2, id
				        FROM nd_mutasi_barang
				            WHERE tanggal < '$tanggal_start'
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
				            ) t1
				        LEFT JOIN (
				            SELECT *
				            FROM nd_penjualan
				            WHERE tanggal < '$tanggal_start'
				            AND tanggal >= '$tanggal_awal'
				            AND status_aktif = 1
				            ) t2
				        ON t1.penjualan_id = t2.id
				        WHERE t2.id is not null
				    )UNION(
				        SELECT barang_id, warna_id, t1.gudang_id, 0, 0, qty, jumlah_roll, tanggal, 20, t3.id
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
				            WHERE tanggal < '$tanggal_start'
				            AND tanggal >= '$tanggal_awal'
				            AND status_aktif = 1
				            ) t2
				        ON t1.pengeluaran_stok_lain_id = t2.id
				        LEFT JOIN (
				            SELECT sum(qty * if(jumlah_roll = 0,1, jumlah_roll)) as qty , sum(jumlah_roll) as jumlah_roll, pengeluaran_stok_lain_detail_id,id
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
				            WHERE tanggal < '$tanggal_start'
				            AND tanggal >= '$tanggal_awal'
				            AND status_aktif = 1
				            ) t2
				        ON t1.retur_jual_id = t2.id
				        LEFT JOIN (
				            SELECT sum(qty * if(jumlah_roll = 0,1, jumlah_roll)) as qty , sum(jumlah_roll) as jumlah_roll, retur_jual_detail_id,id
				            FROM nd_retur_jual_qty
				            GROUP BY retur_jual_detail_id
				            ) t3
				        ON t3.retur_jual_detail_id = t1.id
				        WHERE barang_id is not null 
				        AND warna_id is not null
				        AND t2.id is not null
				    )UNION(
				    	SELECT barang_id, warna_id, t1.gudang_id,0,0, qty, jumlah_roll, tanggal,19, t1.id
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
				        	WHERE tanggal < '$tanggal_start'
				        	AND tanggal >= '$tanggal_awal'
				        	AND t1.status_aktif = 1
				        	) t2
				        ON t1.retur_beli_id = t2.id
				        LEFT JOIN (
				            SELECT sum(qty * if(jumlah_roll = 0,1, jumlah_roll)) as qty , sum(jumlah_roll) as jumlah_roll, retur_beli_detail_id
				            FROM nd_retur_beli_qty
				            GROUP BY retur_beli_detail_id
				            ) t3
				        ON t3.retur_beli_detail_id = t1.id
				        WHERE barang_id is not null 
				        AND warna_id is not null
				        AND t2.id is not null
				    )UNION(
				        SELECT barang_id, warna_id, gudang_id_before, 0 , 0, qty, jumlah_roll, tanggal, 5,id
				        FROM nd_mutasi_barang
				            WHERE tanggal < '$tanggal_start'
				            AND tanggal >= '$tanggal_awal'
				            AND gudang_id_before = $gudang_id
				            AND barang_id = $barang_id
				            AND warna_id = $warna_id
				            AND status_aktif = 1
				    )UNION(
				        SELECT barang_id, warna_id, gudang_id, qty, jumlah_roll, 0, 0,tanggal, 6,id
				        FROM (
				            SELECT *
				            FROM nd_penyesuaian_stok
				            WHERE tanggal < '$tanggal_start'
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
				            WHERE tanggal < '$tanggal_start'
				            AND tanggal >= '$tanggal_awal'
				            AND tipe_transaksi != 0
				            AND barang_id = $barang_id
				            AND warna_id = $warna_id
				            AND gudang_id = $gudang_id
				        ) nd_penyesuaian_stok
				    )UNION(
				        SELECT barang_id, warna_id, gudang_id, qty, jumlah_roll, 0, 0, tanggal, 8, t1.id
				        FROM (
				            SELECT stok_opname_id, barang_id, gudang_id,warna_id,sum(qty * jumlah_roll) as qty , sum(jumlah_roll) as jumlah_roll,id
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
					        AND tanggal >= '$tanggal_awal'
					        AND tanggal < '$tanggal_start'
				        ) t2
				        ON t1.stok_opname_id = t2.id
				        WHERE t2.id is not null
				    )
				) tbl_a
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
				        SELECT *
				        FROM nd_stok_opname
				        WHERE status_aktif = 1
				        AND tanggal >= '$tanggal_awal'
				        AND tanggal < '$tanggal_start'
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
				");
		
		return $query->result();
		// return $this->db->last_query();
	}

	function get_stok_barang_satuan_awal_2($gudang_id, $barang_id, $warna_id, $tanggal_start, $tanggal_awal, $stok_opname_id){
		$query = $this->db->query("SELECT tbl_b.nama as nama_barang,nama_jual, tanggal, tbl_c.warna_beli as nama_warna, barang_id, warna_id, sum(if(tanggal_stok is not null,if(time_stamp >= tanggal_stok, qty_masuk,0) ,qty_masuk) ) as qty_masuk, 
			ROUND(sum(if(tanggal_stok is not null,if(time_stamp >= tanggal_stok,qty_keluar,0), qty_keluar) ),3) qty_keluar, 
			sum(if(tanggal_stok is not null,if(time_stamp >= tanggal_stok,jumlah_roll_masuk,0), jumlah_roll_masuk)) as jumlah_roll_masuk, 
			sum(if(tanggal_stok is not null,if(time_stamp >= tanggal_stok,jumlah_roll_keluar,0), jumlah_roll_keluar)) jumlah_roll_keluar, if(tanggal_stok is not null,tanggal_stok,0), 
			sum(if(time_stamp >= tanggal_stok, 1,0))
				FROM(
				    (
				        SELECT barang_id, warna_id, t2.gudang_id, 
				        qty as qty_masuk, jumlah_roll as jumlah_roll_masuk, 
				        0 as qty_keluar, 0 as jumlah_roll_keluar,
				        tanggal, 1 as tipe,t1.id,  created_at as time_stamp
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
				        WHERE t2.id is not null
				        -- AND qty > if(barang_id = 101 ,0,1)
				    )UNION(
				        SELECT barang_id, warna_id, gudang_id_after, qty , jumlah_roll , 0, 0, tanggal, 2, id, created_at
				        FROM nd_mutasi_barang
				            WHERE created_at < '$tanggal_start'
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
				            ) t1
				        LEFT JOIN (
				            SELECT *
				            FROM nd_penjualan
				            WHERE ifnull(closed_date, created_at) < '$tanggal_start'
				            AND ifnull(closed_date, created_at) >= '$tanggal_awal'
				            AND status_aktif = 1
				            ) t2
				        ON t1.penjualan_id = t2.id
				        WHERE t2.id is not null
				    )UNION(
				        SELECT barang_id, warna_id, t1.gudang_id, 0, 0, qty, jumlah_roll, tanggal, 20, t3.id, t2.created_at
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
				            WHERE created_at < '$tanggal_start'
				            AND created_at >= '$tanggal_awal'
				            AND status_aktif = 1
				            ) t2
				        ON t1.pengeluaran_stok_lain_id = t2.id
				        LEFT JOIN (
				            SELECT sum(qty * if(jumlah_roll = 0,1, jumlah_roll)) as qty , sum(jumlah_roll) as jumlah_roll, pengeluaran_stok_lain_detail_id,id
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
				            WHERE created_at < '$tanggal_start'
				            AND created_at >= '$tanggal_awal'
				            AND status_aktif = 1
				            ) t2
				        ON t1.retur_jual_id = t2.id
				        LEFT JOIN (
				            SELECT sum(qty * if(jumlah_roll = 0,1, jumlah_roll)) as qty , sum(jumlah_roll) as jumlah_roll, retur_jual_detail_id,id
				            FROM nd_retur_jual_qty
				            GROUP BY retur_jual_detail_id
				            ) t3
				        ON t3.retur_jual_detail_id = t1.id
				        WHERE barang_id is not null 
				        AND warna_id is not null
				        AND t2.id is not null
				    )UNION(
				    	SELECT barang_id, warna_id, t1.gudang_id,0,0, qty, jumlah_roll, tanggal,19, t1.id, created_at
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
				        	WHERE created_at < '$tanggal_start'
				        	AND created_at >= '$tanggal_awal'
				        	AND t1.status_aktif = 1
				        	) t2
				        ON t1.retur_beli_id = t2.id
				        LEFT JOIN (
				            SELECT sum(qty * if(jumlah_roll = 0,1, jumlah_roll)) as qty , sum(jumlah_roll) as jumlah_roll, retur_beli_detail_id
				            FROM nd_retur_beli_qty
				            GROUP BY retur_beli_detail_id
				            ) t3
				        ON t3.retur_beli_detail_id = t1.id
				        WHERE barang_id is not null 
				        AND warna_id is not null
				        AND t2.id is not null
				    )UNION(
				        SELECT barang_id, warna_id, gudang_id_before, 0 , 0, qty, jumlah_roll, tanggal, 5,id, created_at
				        FROM nd_mutasi_barang
				            WHERE created_at < '$tanggal_start'
				            AND created_at >= '$tanggal_awal'
				            AND gudang_id_before = $gudang_id
				            AND barang_id = $barang_id
				            AND warna_id = $warna_id
				            AND status_aktif = 1
				    )UNION(
				        SELECT barang_id, warna_id, gudang_id, qty, jumlah_roll, 0, 0,tanggal, 6,id, created_at
				        FROM (
				            SELECT *
				            FROM nd_penyesuaian_stok
				            WHERE created_at < '$tanggal_start'
				            AND created_at >= '$tanggal_awal'
				            AND tipe_transaksi = 0
				            AND barang_id = $barang_id
				            AND warna_id = $warna_id
				            AND gudang_id = $gudang_id
				        ) nd_penyesuaian_stok
				    )UNION(
						SELECT barang_id, warna_id, gudang_id, q_in , if(tipe_transaksi = 3, jml_roll, j_in ) as j_in, 
							q_out, j_out,
							tanggal, 7, id, created_at
						FROM (
							SELECT barang_id, warna_id, gudang_id, if(tipe_transaksi = 1,qty,0) as q_in , if(tipe_transaksi = 1,jumlah_roll,0) as j_in, 
							if(tipe_transaksi = 2,qty,0)q_out, if(tipe_transaksi = 2 || tipe_transaksi = 3,jumlah_roll,0) as j_out,
							tanggal, id, created_at, tipe_transaksi
							FROM (
								SELECT *
								FROM nd_penyesuaian_stok
								WHERE created_at < '$tanggal_start'
								AND created_at >= '$tanggal_awal'
								AND tipe_transaksi != 0
								AND barang_id = $barang_id
								AND warna_id = $warna_id
								AND gudang_id = $gudang_id
							) nd_penyesuaian_stok
						)t1
						LEFT JOIN (
							SELECT sum(jumlah_roll) jml_roll, penyesuaian_stok_id
							FROM nd_penyesuaian_stok_split
							GROUP BY penyesuaian_stok_id

							)t2
							ON t1.id = t2.penyesuaian_stok_id
				    )UNION(
				        SELECT barang_id, warna_id, gudang_id, qty, jumlah_roll, 0, 0, tanggal, 8, t1.id, created_at
				        FROM (
				            SELECT stok_opname_id, barang_id, gudang_id,warna_id,sum(qty * jumlah_roll) as qty , sum(jumlah_roll) as jumlah_roll,id
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
					        AND created_at >= '$tanggal_awal'
					        AND created_at < '$tanggal_start'
				        ) t2
				        ON t1.stok_opname_id = t2.id
				        WHERE t2.id is not null
				    )
				) tbl_a
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
				        SELECT *
				        FROM nd_stok_opname
				        WHERE status_aktif = 1
				        AND created_at >= '$tanggal_awal'
				        AND created_at < '$tanggal_start'
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
				");
		
		return $query->result();
		// return $this->db->last_query();
	}

	function get_kartu_stok_barang_by_satuan($gudang_id, $barang_id, $warna_id, $tanggal_start, $tanggal_end, $tanggal_awal, $stok_opname_id){
		$query = $this->db->query("SELECT tbl_b.nama_jual as nama_barang, tbl_c.warna_beli as nama_warna, barang_id, warna_id, qty, 
			sum(jumlah_roll_masuk) as jumlah_roll_masuk, sum(jumlah_roll_keluar) as jumlah_roll_keluar,
			sum(roll_stok_masuk) roll_stok_masuk, sum(roll_stok_keluar) roll_stok_keluar, tipe, 
			group_concat(if(tgl_recap_masuk != '', tgl_recap_masuk,null)) as tgl_recap_masuk, 
			group_concat(if(tgl_recap_keluar != '', tgl_recap_keluar,null)) as tgl_recap_keluar,
			group_concat(if(roll_recap_masuk != '', roll_recap_masuk,null)) as roll_recap_masuk, 
			group_concat(if(roll_recap_keluar != '', roll_recap_keluar,null)) as roll_recap_keluar,
			group_concat(if(ket_recap_masuk != '', ket_recap_masuk,null)) as ket_recap_masuk, 
			group_concat(if(ket_recap_keluar != '', ket_recap_keluar,null)) as ket_recap_keluar
				FROM(
					(
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
						        	) t1
						        LEFT JOIN (
						        	SELECT *
									FROM nd_penjualan
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
						        	SELECT *, concat('FPJ', DATE_FORMAT(tanggal,'%d%m%y'),'-',$this->pre_faktur,LPAD(no_faktur,5,'0')) as no_faktur_lengkap
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
						    	SELECT barang_id, warna_id, gudang_id, if(tipe_transaksi = 1 || tipe_transaksi = 2,t2.qty, t3.qty) , if(tipe_transaksi = 1,t2.jumlah_roll,0), if(tipe_transaksi = 2, t2.jumlah_roll, if(tipe_transaksi = 3,t3.jumlah_roll,0)) , 'p1', t2.id, tanggal, 'penyesuaian'
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
						    	LEFT JOIN 
									(
										SELECT sum(qty*if(jumlah_roll=0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, penyesuaian_stok_id, id
										FROM nd_penyesuaian_stok_split 
										GROUP BY penyesuaian_stok_id
									)t3
						    	ON t3.penyesuaian_stok_id = t1.id

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
							    	AND status_aktif = 1
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
						        	) t1
						        LEFT JOIN (
						        	SELECT *
									FROM nd_penjualan
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
						        	SELECT *, concat('FPJ', DATE_FORMAT(tanggal,'%d%m%y'),'-',$this->pre_faktur,LPAD(no_faktur,5,'0')) as no_faktur_lengkap
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
						    	SELECT barang_id, warna_id, gudang_id, t2.qty, if(tipe_transaksi =1 ,t2.jumlah_roll,0 ), if(tipe_transaksi = 2  ,t2.jumlah_roll,0),'p1' as tipe, t2.id, tanggal
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
						    	SELECT barang_id, warna_id, gudang_id, qty,0, jumlah_roll,'pSp1' as tipe, id, tanggal
					    		FROM nd_penyesuaian_stok
						    	WHERE barang_id = $barang_id
						    	AND warna_id = $warna_id
					        	AND gudang_id = $gudang_id
						    	AND tanggal < '$tanggal_start'
						    	AND tanggal >= '$tanggal_awal'
						    	AND tipe_transaksi = 3
						    )UNION(
						    	SELECT barang_id, warna_id, gudang_id, t2.qty, t2.jumlah_roll,0,'pSp2' as tipe, t2.id, tanggal
						    	FROM (
						    		SELECT *
						    		FROM nd_penyesuaian_stok
							    	WHERE barang_id = $barang_id
							    	AND warna_id = $warna_id
						        	AND gudang_id = $gudang_id
							    	AND tanggal < '$tanggal_start'
							    	AND tanggal >= '$tanggal_awal'
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
							    	AND status_aktif = 1
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
							    	AND status_aktif = 1
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

	function get_kartu_stok_barang_by_satuan_2($gudang_id, $barang_id, $warna_id, $tanggal_start, $tanggal_end, $tanggal_awal, $stok_opname_id){
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
								SELECT barang_id, warna_id, t2.gudang_id, t3.qty, 
								t3.jumlah_roll as jumlah_roll_masuk, 
								0 as jumlah_roll_keluar, 'a1' as tipe, t1.id, tanggal, no_faktur, created_at as time_stamp
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
						        	SELECT *, concat('FPJ', DATE_FORMAT(tanggal,'%d%m%y'),'-',$this->pre_faktur,LPAD(no_faktur,5,'0')) as no_faktur_lengkap
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
						    	SELECT barang_id, warna_id, gudang_id, if(tipe_transaksi = 1 || tipe_transaksi = 2,t2.qty, t3.qty) , 
								if(tipe_transaksi = 1,t2.jumlah_roll,0), 
								if(tipe_transaksi = 2, t2.jumlah_roll, 
								if(tipe_transaksi = 3,t1.jumlah_roll,0)) , 'p1', t2.id, tanggal, 'penyesuaian', created_at
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
										SELECT qty, sum(if(jumlah_roll=0,1,jumlah_roll)) as jumlah_roll, penyesuaian_stok_id, id
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
						    	SELECT barang_id, warna_id, gudang_id, t1.qty, 
								if(t1.jumlah_roll = 0,1,t1.jumlah_roll), 0, 'sp1' as tipe, t1.id, tanggal, 'Split Stok', created_at
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
						            SELECT qty as qty,  sum(if(jumlah_roll=0,1,jumlah_roll)) as jumlah_roll, penjualan_detail_id
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
						        	SELECT *, concat('FPJ', DATE_FORMAT(tanggal,'%d%m%y'),'-',$this->pre_faktur,LPAD(no_faktur,5,'0')) as no_faktur_lengkap
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
						    	SELECT barang_id, warna_id, gudang_id, t2.qty, 
								if(t2.jumlah_roll = 0,1, t2.jumlah_roll),0,'pSp2' as tipe, t2.id, tanggal, created_at
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


	function get_last_opname($barang_id, $warna_id,$gudang_id,  $tanggal)
	{

		$query = $this->db->query("SELECT *
			FROM nd_stok_opname
			WHERE created_at = (
				SELECT max(created_at) 
				FROM (
					SELECT *
					FROM nd_stok_opname_detail
					WHERE barang_id = $barang_id
					AND warna_id = $warna_id
					AND gudang_id = $gudang_id
					) t1
				LEFT JOIN (
					SELECT *
					FROM nd_stok_opname
					WHERE created_at <= '$tanggal 23:59:59'
					AND status_aktif = 1
					) t2
				ON t1.stok_opname_id = t2.id
				WHERE t2.id is not null
				ORDER BY t2.created_at desc
				LIMIT 1
			)
				");
		return $query->result();
	}

//==========================================rekap=========================================================

	function get_stok_barang_list_rekap($select, $select_all, $tanggal_start, $tanggal_awal, $stok_opname_id){
		$tanggal_start .=' 23:59:59';
		$query = $this->db->query("SELECT tbl_b.nama as nama_barang,tbl_b.nama_jual as nama_barang_jual, tbl_c.warna_beli as nama_warna,tbl_c.warna_jual as nama_warna_jual, barang_id, warna_id, tbl_b.status_aktif as status_barang, tbl_d.nama as nama_satuan $select_all
				FROM(
					SELECT barang_id, warna_id $select
					FROM (
						(
							SELECT barang_id, warna_id, t1.gudang_id, 0 as qty_masuk, 0 as jumlah_roll_masuk, subqty as qty_keluar, subjumlah_roll as jumlah_roll_keluar,3 as tipe, tanggal, t2.id, ifnull(closed_date, t2.created_at) as time_stamp
							FROM nd_penjualan_detail t1
							LEFT JOIN (
								SELECT *
								FROM nd_penjualan
								WHERE created_at <= '$tanggal_start'
								AND created_at >= '$tanggal_awal'
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
						) tB
						ON tA.stok_opname_id = tB.id
						WHERE tB.id is not null
						GROUP BY barang_id, warna_id, gudang_id
					) t2
					ON t1.barang_id = t2.barang_id_stok
					AND t1.warna_id = t2.warna_id_stok
					AND t1.gudang_id = t2.gudang_id_stok
					GROUP BY barang_id, warna_id
			) tbl_a
			LEFT JOIN nd_barang tbl_b
			ON tbl_a.barang_id = tbl_b.id
			LEFT JOIN nd_warna tbl_c
			ON tbl_a.warna_id = tbl_c.id
			LEFT JOIN nd_satuan tbl_d
			ON tbl_b.satuan_id = tbl_d.id
			Where barang_id is not null
			AND tbl_b.status_aktif = 1
			GROUP BY barang_id
			ORDER BY nama_jual");
		
		
		
		return $query->result();
		// return $this->db->last_query();
	}


//=====================================================Stok + HPP======================================

	function get_stok_barang_list_hpp($select2, $select, $tanggal_start, $kolom, $tgl_tutup_buku, $kolom_qty){
		$tanggal_awal = '2018-01-01';
		$tahun = date("Y", strtotime($tgl_tutup_buku));
		$query = $this->db->query("SELECT tbl_b.nama as nama_barang,tbl_b.nama_jual as nama_barang_jual, tbl_c.warna_beli as nama_warna,tbl_c.warna_jual as nama_warna_jual, tbl_a.barang_id, tbl_a.warna_id, tbl_b.status_aktif as status_barang, tbl_d.nama as nama_satuan,hpp, satuan_id, tipe_qty
				 $select2
				 ,tbl_a.*
				FROM(
					SELECT barang_id, warna_id 
					$select
					,MAX(tanggal) as last_edit 
					FROM (
								(
							        SELECT barang_id, warna_id, t1.gudang_id, 0 as qty_masuk, 0 as jumlah_roll_masuk, subqty as qty_keluar, subjumlah_roll as jumlah_roll_keluar,3 as tipe, tanggal, t2.id
							        FROM nd_penjualan_detail t1
							        LEFT JOIN (
							        	SELECT *
							        	FROM nd_penjualan
							        	WHERE tanggal <= '$tanggal_start'
							        	AND tanggal >= '$tanggal_awal'
							        	AND status_aktif = 1
							        	) t2
							        ON t1.penjualan_id = t2.id
							        where t2.id is not null
							    )UNION(
							        SELECT barang_id, warna_id, t1.gudang_id, 0 as qty_masuk, 0 as jumlah_roll_masuk, qty as qty_keluar, jumlah_roll as jumlah_roll_keluar,20 as tipe, tanggal, t3.id
							        FROM nd_pengeluaran_stok_lain_detail t1
							        LEFT JOIN (
							        	SELECT *
							        	FROM nd_pengeluaran_stok_lain
							        	WHERE tanggal <= '$tanggal_start'
							        	AND tanggal >= '$tanggal_awal'
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
							        SELECT barang_id, warna_id, t2.gudang_id, qty, jumlah_roll, 0, 0, 1, tanggal, qty_id
							        FROM (
							        	SELECT qty, jumlah_roll, tA.id, barang_id, warna_id, pembelian_id, id as qty_id
							        	FROM nd_pembelian_detail tA
										ORDER BY pembelian_id
							        ) t1
							        LEFT JOIN (
							        	SELECT *
							        	FROM nd_pembelian
							        	WHERE tanggal <= '$tanggal_start'
							        	AND tanggal >= '$tanggal_awal'
							        	AND status_aktif = 1
							        	) t2
							        ON t1.pembelian_id = t2.id
							        WHERE t2.id is not null
							    )UNION(
							    	SELECT barang_id, warna_id, gudang_id_after, qty, jumlah_roll, 0, 0,2, tanggal, id
						        	FROM nd_mutasi_barang t1
									WHERE tanggal <= '$tanggal_start'
						        	AND tanggal >= '$tanggal_awal'
						        	AND status_aktif = 1
							    )UNION(
							    	SELECT barang_id, warna_id, t1.gudang_id, qty, jumlah_roll, 0, 0, 4, tanggal, t3.id
							        FROM nd_retur_jual_detail t1
							        LEFT JOIN (
							        	SELECT *
							        	FROM nd_retur_jual
							        	WHERE tanggal <= '$tanggal_start'
							        	AND tanggal >= '$tanggal_awal'
							        	AND status_aktif = 1
							        	) t2
							        ON t1.retur_jual_id = t2.id
							        LEFT JOIN (
							            SELECT qty, jumlah_roll, retur_jual_detail_id, id
							            FROM nd_retur_jual_qty
							            ) t3
							        ON t3.retur_jual_detail_id = t1.id
							        WHERE t2.id is not null
							    )UNION(
							    	SELECT barang_id, warna_id, t1.gudang_id, 0, 0,qty, jumlah_roll, 19 ,  tanggal, t1.id
							        FROM nd_retur_beli_detail t1
							        LEFT JOIN (
							        	SELECT *
							        	FROM nd_retur_beli
							        	WHERE tanggal <= '$tanggal_start'
							        	AND tanggal >= '$tanggal_awal'
							        	AND status_aktif = 1
							        	) t2
							        ON t1.retur_beli_id = t2.id
							        LEFT JOIN nd_retur_beli_qty t3
							        ON t3.retur_beli_detail_id = t1.id
							        WHERE t2.id is not null
							    )UNION(
							        SELECT barang_id, warna_id, gudang_id, qty, jumlah_roll, 0, 0,5, tanggal, id
						        	FROM nd_penyesuaian_stok
						        	WHERE tipe_transaksi = 0
						            AND tanggal <= '$tanggal_start'
						        	AND tanggal >= '$tanggal_awal'
							    )UNION(
							        SELECT barang_id, warna_id, gudang_id, if(tipe_transaksi = 1,qty, 0 ), if(tipe_transaksi = 1,jumlah_roll,0 ), if(tipe_transaksi = 2,qty, 0 ), if(tipe_transaksi=2,jumlah_roll,0),6, tanggal, id
						        	FROM nd_penyesuaian_stok
						        	WHERE tanggal <= '$tanggal_start'
						        	AND tanggal >= '$tanggal_awal'
						        	AND tipe_transaksi != 0
							    )UNION(
							    	SELECT barang_id, warna_id, gudang_id_before, 0, 0 , qty, jumlah_roll,8, tanggal, id
						        	FROM nd_mutasi_barang t1
									WHERE tanggal <= '$tanggal_start'	
								    AND tanggal >= '$tanggal_awal'
						        	AND status_aktif = 1
							    )UNION(
							    	SELECT barang_id, warna_id, gudang_id, qty, jumlah_roll, 0, 0, 9, tanggal, t1.id
						        	FROM (
						        		SELECT barang_id, warna_id, gudang_id, sum(qty * jumlah_roll) as qty, sum(jumlah_roll) jumlah_roll, stok_opname_id, id
						        		FROM nd_stok_opname_detail
						        		WHERE warna_id > 0
						        		GROUP BY barang_id, warna_id, gudang_id, stok_opname_id
						        		) t1 
									LEFT JOIN (
										SELECT *
										FROM nd_stok_opname
										WHERE tanggal <= '$tanggal_start'	
									    AND tanggal >= '$tanggal_awal'
							        	AND status_aktif = 1
										) t2
									ON t1.stok_opname_id = t2.id
									WHERE t2.id is not null
							    )
							)t1
							LEFT JOIN (
								SELECT barang_id as barang_id_stok, warna_id as warna_id_stok,gudang_id as gudang_id_stok, MAX(tanggal) as tanggal_stok
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
								) tB
								ON tA.stok_opname_id = tB.id
								WHERE tB.id is not null
								GROUP BY barang_id, warna_id, gudang_id
							) t2
							ON t1.barang_id = t2.barang_id_stok
							AND t1.warna_id = t2.warna_id_stok
							AND t1.gudang_id = t2.gudang_id_stok
							GROUP BY barang_id, warna_id
				) tbl_a
				LEFT JOIN nd_barang tbl_b
				ON tbl_a.barang_id = tbl_b.id
				LEFT JOIN nd_warna tbl_c
				ON tbl_a.warna_id = tbl_c.id
				LEFT JOIN nd_satuan tbl_d
				ON tbl_b.satuan_id = tbl_d.id
				LEFT JOIN (
					SELECT sum(qty_beli) as qty_beli, sum(total_beli) as total_beli, barang_id, warna_id, ROUND(sum(total_beli)/sum(qty_beli),2) as hpp
					FROM (
						(
							SELECT sum(qty) as qty_beli, sum(qty*(harga_beli/1.1)) as total_beli, barang_id, warna_id
							FROM (
								SELECT *
								FROM nd_pembelian
								WHERE tanggal > '$tgl_tutup_buku'
								) t1
							LEFT JOIN nd_pembelian_detail t2
							ON t1.id = t2.pembelian_id
							WHERE barang_id is not null
							GROUP BY YEAR(tanggal) , MONTH(tanggal), barang_id, warna_id
						)UNION(
							SELECT $kolom as harga_stok_awal, ($kolom"."*"." $kolom_qty), barang_id, warna_id
							FROM nd_tutup_buku_detail tA
							WHERE YEAR(tahun) = '$tahun'
						) 
					)a
					GROUP BY barang_id, warna_id
				) tbl_e
				ON tbl_a.barang_id = tbl_e.barang_id
				AND tbl_a.warna_id = tbl_e.warna_id
				Where tbl_a.barang_id is not null
				GROUP BY barang_id, warna_id
				ORDER BY nama_jual");
		
		// return $query->result();
		return $this->db->last_query();

	}

	function get_harga_hpp($kolom_harga, $kolom_qty, $tanggal_start, $tahun, $tanggal_akhir){
		$query = $this->db->query("SELECT barang_id, warna_id, ROUND(sum(total)/sum(qty),2) as hpp, sum(total), sum(qty), tipe, group_concat(harga)
				FROM ((
					SELECT barang_id, warna_id, $kolom_harga as harga, $kolom_qty as qty, ($kolom_harga * $kolom_qty) as total, 1 as tipe
					FROM nd_tutup_buku_detail
					WHERE YEAR(tahun) = '$tahun'
				)UNION(
					SELECT barang_id, warna_id, ROUND(sum(total_beli)/sum(qty_beli),2) as hpp, sum(qty_beli), sum(total_beli), 2 as tipe
					FROM (
						(
							SELECT sum(qty) as qty_beli, sum(qty*(harga_beli/(1+(ppn_berlaku/100)))) as total_beli, barang_id, warna_id, (harga_beli/(1+(ppn_berlaku/100))) as hpp, (1+(ppn_berlaku/100)) as ppn_berlaku
							FROM (
								SELECT *, (SELECT ppn FROM nd_ppn tX WHERE tX.tanggal <= nd_pembelian.tanggal ORDER BY tX.tanggal DESC LIMIT 1 ) as ppn_berlaku
								FROM nd_pembelian
								WHERE tanggal > '$tanggal_start'
								AND tanggal <= '$tanggal_akhir'
								) t1
							LEFT JOIN nd_pembelian_detail t2
							ON t1.id = t2.pembelian_id
							WHERE barang_id is not null
							GROUP BY barang_id, warna_id
						)
					)a
					GROUP BY barang_id, warna_id
				))res
				GROUP BY barang_id, warna_id

		");
		return $query->result();

	}

//=======================================================mutasi================================================


	function cek_barang_qty($gudang_id, $barang_id,$warna_id, $tanggal_awal, $stok_opname_id, $cond_detail){
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
		            -- $cond_detail
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
		            GROUP by stok_opname_id
		        ) t1
		        LEFT JOIN (
		            SELECT *
		            FROM nd_stok_opname
		            WHERE status_aktif = 1
		            AND tanggal <= '$tanggal_start'
		            AND tanggal >= '$tanggal_awal'

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
		    ) tA
		    LEFT JOIN (
		        SELECT *
		        FROM nd_stok_opname
		        WHERE status_aktif = 1
		        AND tanggal <= '$tanggal_start'
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
		return $query->result();
	}

	function cek_barang_qty_2($gudang_id, $barang_id,$warna_id, $tanggal_awal, $stok_opname_id, $cond_detail){
		$tanggal_start = date('Y-m-d 23:59:59');
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
		        SELECT barang_id, warna_id, gudang_id, if(tipe_transaksi = 1,qty,0), 
				if(tipe_transaksi = 1,jumlah_roll,if(tipe_transaksi=3,roll_split,0)), if(tipe_transaksi = 2,qty,0), 
				if(tipe_transaksi = 2 || tipe_transaksi = 3,jumlah_roll,0),tanggal, 7, id, created_at
		        FROM (
		            SELECT *
		            FROM nd_penyesuaian_stok
		            WHERE created_at <= '$tanggal_start'
		            AND created_at >= '$tanggal_awal'
		            AND tipe_transaksi != 0
		            AND barang_id = $barang_id
		            AND warna_id = $warna_id
		            AND gudang_id = $gudang_id
		        ) t1
				LEFT JOIN (
					SELECT penyesuaian_stok_id, sum(jumlah_roll) as roll_split
					FROM nd_penyesuaian_stok_split
					GROUP BY penyesuaian_stok_id
				) t2
				ON t1.id = t2.penyesuaian_stok_id 
		    )UNION(
		        SELECT barang_id, warna_id, gudang_id, qty, jumlah_roll, 0, 0, tanggal, 8, t1.id, created_at
		        FROM (
		            SELECT stok_opname_id, barang_id, gudang_id,warna_id, sum(qty * jumlah_roll) as qty, sum(jumlah_roll) as jumlah_roll, id
		            FROM nd_stok_opname_detail
		            WHERE barang_id = $barang_id
		            AND warna_id = $warna_id
		            AND gudang_id = $gudang_id
		            GROUP BY barang_id, warna_id, gudang_id, stok_opname_id
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
		return $query->result();
	}




	function get_mutasi_list_detail($tanggal){
		$query = $this->db->query("SELECT tbl_a.id, tbl_b.nama as nama_gudang_before, tbl_c.nama as nama_gudang_after,  tbl_d.nama as nama_barang, tbl_e.warna_beli as nama_warna 
			FROM (
				SELECT *
				FROM nd_mutasi_barang
				WHERE tanggal = '$tanggal'
				) tbl_a
			LEFT JOIN nd_gudang as tbl_b
			ON tbl_a.gudang_id_before = tbl_b.id
			LEFT JOIN nd_gudang as tbl_c
			ON tbl_a.gudang_id_after = tbl_c.id
			LEFT JOIN nd_barang as tbl_d
			ON tbl_a.barang_id = tbl_d.id
			LEFT JOIN nd_warna as tbl_e
			ON tbl_a.warna_id = tbl_e.id
		");
		return $query->result();
	}

	function get_mutasi_list_detail_with_detail($tanggal){
		$query = $this->db->query("SELECT tbl_a.id, tbl_b.nama as nama_gudang_before, tbl_c.nama as nama_gudang_after,  tbl_d.nama as nama_barang, tbl_e.warna_beli as nama_warna, rekap_qty
			FROM (
				SELECT *
				FROM nd_mutasi_barang
				WHERE tanggal = '$tanggal'
		    	AND status_aktif = 1
				) tbl_a
			LEFT JOIN (
				SELECT group_concat(concat(qty,'??',jumlah_roll,'??',id) SEPARATOR '--' ) as rekap_qty, mutasi_barang_id
				FROM nd_mutasi_barang_qty
				GROUP BY mutasi_barang_id
				) qty_detail
			ON tbl_a.id = qty_detail.mutasi_barang_id
			LEFT JOIN nd_gudang as tbl_b
			ON tbl_a.gudang_id_before = tbl_b.id
			LEFT JOIN nd_gudang as tbl_c
			ON tbl_a.gudang_id_after = tbl_c.id
			LEFT JOIN nd_barang as tbl_d
			ON tbl_a.barang_id = tbl_d.id
			LEFT JOIN nd_warna as tbl_e
			ON tbl_a.warna_id = tbl_e.id
		");
		return $query->result();
	}

	function get_mutasi_barang_ajax($aColumns, $sWhere/*, $sOrder*/, $sLimit, $cond){
		$query = $this->db->query("SELECT *
			FROM (
				-- @row := @row + 1 as idx, 
				SELECT a.status_aktif, tanggal, concat_ws(' ',e.nama_jual, f.warna_jual)  as nama_barang , 
				c.nama as gudang_before, d.nama as gudang_after, a.qty, a.jumlah_roll, nama_kru,
				concat_ws('??',a.id,gudang_id_before, gudang_id_after, barang_id,warna_id, qty_data, jumlah_roll_data, detail_id) as data, username
				FROM (
					SELECT * 
					-- , (SELECT @row := 0)
					FROM nd_mutasi_barang
					$cond
					) a
				LEFT JOIN (
					SELECT sum(qty * if(jumlah_roll = 0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, 
					group_concat(qty) as qty_data, group_concat(jumlah_roll) as jumlah_roll_data, mutasi_barang_id, group_concat(id) as detail_id
					FROM nd_mutasi_barang_qty
					GROUP BY mutasi_barang_id
					) g
				ON g.mutasi_barang_id = a.id
				LEFT JOIN nd_gudang c
				ON a.gudang_id_before = c.id
				LEFT JOIN nd_gudang d
				ON a.gudang_id_after = d.id 
				LEFT JOIN nd_barang e
				ON a.barang_id = e.id
				LEFT JOIN nd_warna f
				ON a.warna_id = f.id
				LEFT JOIN nd_user g
				ON a.user_id = g.id
				order by a.id desc
				) A			
			$sWhere
            $sLimit
			", false);

		return $query;
	}


	function get_mutasi_barang($cond){
		$query = $this->db->query("SELECT a.status_aktif, tanggal, concat_ws(' ',e.nama_jual, f.warna_jual)  as nama_barang , c.nama as gudang_before, d.nama as gudang_after, qty, jumlah_roll
				FROM (
					SELECT * 
					-- , (SELECT @row := 0)
					FROM nd_mutasi_barang
					$cond
					) a
				LEFT JOIN nd_gudang c
				ON a.gudang_id_before = c.id
				LEFT JOIN nd_gudang d
				ON a.gudang_id_after = d.id 
				LEFT JOIN nd_barang e
				ON a.barang_id = e.id
				LEFT JOIN nd_warna f
				ON a.warna_id = f.id
				order by tanggal desc
				
			", false);

		return $query->result();
		// return $this->db->last_query();
	}

//==================================mutasi stok awal==================

	function get_stok_awal(){
		$query = $this->db->query("SELECT a.*, e.nama_jual as nama_barang, f.warna_jual as nama_warna, g.nama as nama_satuan, h.nama as nama_gudang
				FROM (
					SELECT * 
					FROM nd_penyesuaian_stok
					WHERE tipe_transaksi = 0
					) a
				LEFT JOIN nd_barang e
				ON a.barang_id = e.id
				LEFT JOIN nd_warna f
				ON a.warna_id = f.id
				LEFT JOIN nd_satuan g
				ON e.satuan_id = g.id
				LEFT JOIN nd_gudang h
				ON a.gudang_id = h.id
				-- order by tanggal desc
				ORDER BY h.nama, e.nama_jual, f.warna_jual
				
			", false);

		return $query->result();
	}

	function get_harga_stok_awal(){
		$query = $this->db->query("SELECT a.*, e.harga_stok_awal, b.nama_jual as nama_barang, b.nama as nama_beli, c.warna_jual as nama_warna, d.nama as nama_satuan
				FROM (
					SELECT * 
					FROM nd_penyesuaian_stok
					WHERE tipe_transaksi = 0
					GROUP BY barang_id, warna_id
					) a
				LEFT JOIN nd_stok_awal_item_harga e
				ON a.barang_id = e.barang_id
				AND a.warna_id = e.warna_id
				LEFT JOIN nd_barang b
				ON a.barang_id = b.id
				LEFT JOIN nd_warna c
				ON a.warna_id = c.id
				LEFT JOIN nd_satuan d
				ON b.satuan_id = d.id
				order by b.nama_jual asc, c.warna_beli asc
				
			", false);

		return $query->result();
	}

	function get_harga_stok_by_barang(){
		$query = $this->db->query("SELECT a.*, e.harga_stok_awal, b.nama_jual as nama_barang, b.nama as nama_beli, c.warna_jual as nama_warna, d.nama as nama_satuan
				FROM (
					SELECT * 
					FROM nd_penyesuaian_stok
					WHERE tipe_transaksi = 0
					GROUP BY barang_id, warna_id
					) a
				LEFT JOIN nd_stok_awal_item_harga e
				ON a.barang_id = e.barang_id
				AND a.warna_id = e.warna_id
				LEFT JOIN nd_barang b
				ON a.barang_id = b.id
				LEFT JOIN nd_warna c
				ON a.warna_id = c.id
				LEFT JOIN nd_satuan d
				ON b.satuan_id = d.id
				GROUP BY barang_id, warna_id
				order by b.nama_jual asc, c.warna_beli asc
				
			", false);

		return $query->result();
	}




//==================================mutasi persediaan barang==================

	function mutasi_persediaan_barang($tanggal_awal, $tanggal,$tanggal_end, $gudang_id, $stok_opname_id){
		
		$query = $this->db->query("SELECT t1.barang_id, t1.warna_id, 
			sum(qty_stock) as qty_stock, sum(jumlah_roll_stock) as jumlah_roll_stock, 
			sum(qty_beli) as qty_beli, sum(jumlah_roll_beli) as jumlah_roll_beli, 
			sum(qty_mutasi) as qty_mutasi, sum(jumlah_roll_mutasi) as jumlah_roll_mutasi, 
			sum(qty_jual) as qty_jual,  sum(jumlah_roll_jual) as jumlah_roll_jual, 
			sum(qty_mutasi_masuk) as qty_mutasi_masuk, sum(jumlah_roll_mutasi_masuk) as jumlah_roll_mutasi_masuk, 
			sum(qty_penyesuaian) as qty_penyesuaian, sum(jumlah_roll_penyesuaian) as jumlah_roll_penyesuaian, 
			sum(qty_retur) as qty_retur, sum(jumlah_roll_retur) as jumlah_roll_retur ,
			sum(qty_retur_beli) as qty_retur_beli, sum(jumlah_roll_retur_beli) as jumlah_roll_retur_beli ,
			sum(qty_lain) as qty_lain, sum(jumlah_roll_lain) as jumlah_roll_lain ,
			hpp, hpp_beli, hpp_jual, t3.nama as nama_barang, nama_jual, warna_jual, t3.satuan_id
			FROM (
				(
					SELECT barang_id, warna_id , 
					sum(ifnull(qty_masuk,0)) - sum(ifnull(qty_keluar,0)) as qty_stock, sum(ifnull(jumlah_roll_masuk,0)) -sum(ifnull(jumlah_roll_keluar,0)) as jumlah_roll_stock, 
					0.00 as qty_beli, 0 as jumlah_roll_beli, 
					0.00 as qty_mutasi, 0 as jumlah_roll_mutasi, 
					0.00 as qty_jual, 0 as jumlah_roll_jual, 
					0.00 as qty_mutasi_masuk, 0 as jumlah_roll_mutasi_masuk, 
					0.00 as qty_penyesuaian, 0 as jumlah_roll_penyesuaian, 
					0.00 as qty_retur, 0 as jumlah_roll_retur,
					0.00 as qty_retur_beli, 0 as jumlah_roll_retur_beli,
					0.00 as qty_lain, 0 as jumlah_roll_lain
					FROM (
						(
					        SELECT barang_id, warna_id, t2.gudang_id, sum(qty) as qty_masuk, sum(jumlah_roll) as jumlah_roll_masuk, CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar, 1 as tipe, t1.id
					        FROM (
					        	SELECT qty, jumlah_roll, id, barang_id, warna_id, pembelian_id
					        	FROM nd_pembelian_detail
					        	ORDER BY pembelian_id
					        ) t1
					        LEFT JOIN (
					        	SELECT *
					        	FROM nd_pembelian
					        	WHERE tanggal < '$tanggal'
					        	AND tanggal >= '$tanggal_awal'
					        	AND gudang_id = $gudang_id
					        	AND status_aktif = 1
					        	) t2
					        ON t1.pembelian_id = t2.id
					        WHERE t2.id is not null
					        GROUP BY barang_id, warna_id
					    )UNION(
					    	SELECT barang_id, warna_id, gudang_id_after, sum(qty) as qty_masuk, sum(jumlah_roll) as jumlah_roll_masuk, CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar, 2 as tipe, t1.id
				        	FROM nd_mutasi_barang t1
				        	WHERE tanggal < '$tanggal'
				        	AND tanggal >= '$tanggal_awal'
				        	AND status_aktif = 1
				        	AND gudang_id_after = $gudang_id
					        GROUP BY barang_id, warna_id
					    )UNION(
					        SELECT barang_id, warna_id, t1.gudang_id, CAST(0 as DECIMAL(15,2)) as qty_masuk, 0 as jumlah_roll_masuk, sum(qty) as qty_keluar, sum(jumlah_roll) as jumlah_roll_keluar, 3 as tipe, t1.id
					        FROM (
					        	SELECT *
					        	FROM nd_penjualan_detail
					        	WHERE gudang_id = $gudang_id
					        ) t1
					        LEFT JOIN (
					        	SELECT *
					        	FROM nd_penjualan
					        	WHERE tanggal < '$tanggal'
					        	AND tanggal >= '$tanggal_awal'
					        	AND status_aktif = 1
					        	) t2
					        ON t1.penjualan_id = t2.id
					        LEFT JOIN (
					            SELECT sum(qty*if(jumlah_roll=0,1, jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, penjualan_detail_id
					            FROM nd_penjualan_qty_detail
					            GROUP BY penjualan_detail_id
					            ) t3
					        ON t3.penjualan_detail_id = t1.id
					        where t2.id is not null
					        GROUP BY barang_id, warna_id
					    )UNION(
					        SELECT barang_id, warna_id, t1.gudang_id, CAST(0 as DECIMAL(15,2)) as qty_masuk, 0 as jumlah_roll_masuk, sum(qty) as qty_keluar, sum(jumlah_roll) as jumlah_roll_keluar, 20 as tipe, t1.id
					        FROM (
					        	SELECT *
					        	FROM nd_pengeluaran_stok_lain_detail
					        	WHERE gudang_id = $gudang_id
					        ) t1
					        LEFT JOIN (
					        	SELECT *
					        	FROM nd_pengeluaran_stok_lain
					        	WHERE tanggal < '$tanggal'
					        	AND tanggal >= '$tanggal_awal'
					        	AND status_aktif = 1
					        	) t2
					        ON t1.pengeluaran_stok_lain_id = t2.id
					        LEFT JOIN (
					            SELECT sum(qty*if(jumlah_roll=0,1, jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, pengeluaran_stok_lain_detail_id
					            FROM nd_pengeluaran_stok_lain_qty_detail
					            GROUP BY pengeluaran_stok_lain_detail_id
					            ) t3
					        ON t3.pengeluaran_stok_lain_detail_id = t1.id
					        where t2.id is not null
					        GROUP BY barang_id, warna_id
					    )UNION(
					    	SELECT barang_id, warna_id, t1.gudang_id, sum(qty) as qty_masuk, sum(jumlah_roll) as jumlah_roll_masuk, CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar, 4 as tipe, t1.id
					        FROM (
					        	SELECT *
					        	FROM nd_retur_jual_detail
					        	WHERE gudang_id = $gudang_id
					        ) t1
					        LEFT JOIN (
					        	SELECT *
					        	FROM nd_retur_jual
					        	WHERE tanggal < '$tanggal'
					        	AND tanggal >= '$tanggal_awal'
					        	AND status_aktif = 1
					        	) t2
					        ON t1.retur_jual_id = t2.id
					        LEFT JOIN (
					            SELECT sum(qty*if(jumlah_roll=0,1, jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, retur_jual_detail_id
					            FROM nd_retur_jual_qty
					            GROUP BY retur_jual_detail_id
					            ) t3
					        ON t3.retur_jual_detail_id = t1.id
					        WHERE t2.id is not null
					        GROUP BY barang_id, warna_id
					    )UNION(
					    	SELECT barang_id, warna_id, t1.gudang_id,0,0, sum(qty), sum(jumlah_roll), 19 as tipe, t1.id
					        FROM (
					        	SELECT *
					        	FROM nd_retur_beli_detail
					        	WHERE gudang_id = $gudang_id
					        ) t1
					        LEFT JOIN (
					        	SELECT *
					        	FROM nd_retur_beli
					        	WHERE tanggal < '$tanggal'
					        	AND tanggal >= '$tanggal_awal'
					        	AND status_aktif = 1
					        	) t2
					        ON t1.retur_beli_id = t2.id
					        LEFT JOIN (
					            SELECT sum(qty*if(jumlah_roll=0,1, jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, retur_beli_detail_id
					            FROM nd_retur_beli_qty
					            GROUP BY retur_beli_detail_id
					            ) t3
					        ON t3.retur_beli_detail_id = t1.id
					        WHERE t2.id is not null
					        GROUP BY barang_id, warna_id
					    )UNION(
					        SELECT barang_id, warna_id, gudang_id, sum(qty) as qty_masuk, sum(jumlah_roll) as jumlah_roll_masuk, CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar, 5 as tipe, t1.id
					        	FROM nd_penyesuaian_stok t1
					        	WHERE tipe_transaksi = 0
					        	AND gudang_id = $gudang_id
					        	AND tanggal < '$tanggal'
					        	AND tanggal >= '$tanggal_awal'
		                        GROUP BY barang_id, warna_id
					    )UNION(
					        SELECT  barang_id, warna_id, gudang_id, sum(qty) as qty_masuk, sum(jumlah_roll) as jumlah_roll_masuk, CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar, 6 as tipe, t1.id
				        	FROM nd_penyesuaian_stok t1
				        	WHERE tanggal < '$tanggal'
				        	AND tanggal >= '$tanggal_awal'
				        	AND gudang_id = $gudang_id
				        	AND tipe_transaksi = 1
				        	GROUP BY barang_id, warna_id
					    )UNION(
					        SELECT barang_id, warna_id, gudang_id, CAST(0 as DECIMAL(15,2)) as qty_masuk, 0 as jumlah_roll_masuk, sum(qty) as qty_keluar, sum(jumlah_roll) , 7 as tipe, t1.id
				        	FROM nd_penyesuaian_stok t1
				        	WHERE tanggal < '$tanggal'
				        	AND tanggal >= '$tanggal_awal'
				        	AND gudang_id = $gudang_id
				        	AND tipe_transaksi = 2
							GROUP BY barang_id, warna_id
					    )UNION(
					    	SELECT barang_id, warna_id, gudang_id_before, CAST(0 as DECIMAL(15,2)) as qty_masuk, 0 as jumlah_roll_masuk, sum(qty) as qty_keluar, sum(jumlah_roll) as jumlah_roll_keluar, 8 as tipe, t1.id
				        	FROM nd_mutasi_barang t1
				        	WHERE tanggal < '$tanggal'
				        	AND tanggal >= '$tanggal_awal'
				        	AND gudang_id_before = $gudang_id
				        	AND status_aktif = 1
							GROUP BY barang_id, warna_id
					    )UNION(
					    	SELECT barang_id, warna_id, gudang_id, sum(qty), sum(jumlah_roll) as jumlah_roll_masuk, 0 as qty_keluar, 0 as jumlah_roll_keluar, 9 as tipe, t1.id
				        	FROM nd_stok_opname_detail t1
				        	WHERE stok_opname_id = $stok_opname_id
				        	AND gudang_id = $gudang_id
			        		AND warna_id > 0
				        	GROUP BY barang_id, warna_id, gudang_id
					    )
					) a
					GROUP BY barang_id, warna_id
				)UNION (
					SELECT barang_id, warna_id, 
					0.00, 0, 
					sum(qty) as qty_beli, sum(jumlah_roll) as jumlah_roll_beli, 
					0.00, 0, 
					0.00, 0 , 
					0.00, 0,
					0.00 ,0, 
					0.00 as qty_retur, 0 as jumlah_roll_retur,
					0.00 as qty_retur_beli, 0 as jumlah_roll_retur_beli,
					0.00 as qty_lain, 0 as jumlah_roll_lain
			        FROM (
			        	SELECT qty, jumlah_roll, id, barang_id, warna_id, pembelian_id
			        	FROM nd_pembelian_detail
			        	ORDER BY pembelian_id
			        ) nd_pembelian_detail
			        LEFT JOIN (
			        	SELECT *
			        	FROM nd_pembelian
			        	WHERE tanggal >= '$tanggal'
			        	AND tanggal <= '$tanggal_end'
			        	AND gudang_id = $gudang_id
			        	AND status_aktif = 1
			        	) nd_pembelian
			        ON nd_pembelian_detail.pembelian_id = nd_pembelian.id
			        WHERE nd_pembelian.id is not null
			        GROUP BY barang_id, warna_id
				)UNION(
			        SELECT barang_id, warna_id, 
			        0.00, 0, 
			        0.00, 0, 
			        0.00, 0, 
			        sum(qty), sum(jumlah_roll), 
			        0.00, 0,
			        0.00, 0, 
			        0.00 as qty_retur, 0 as jumlah_roll_retur,
			        0.00 as qty_retur_beli, 0 as jumlah_roll_retur_beli,
			        0.00 as qty_lain, 0 as jumlah_roll_lain
				        FROM (
				        	SELECT *
				        	FROM nd_penjualan_detail
				        	WHERE gudang_id = $gudang_id
				        ) nd_penjualan_detail
				        LEFT JOIN (
				        	SELECT *
				        	FROM nd_penjualan
				        	WHERE tanggal >= '$tanggal'
				        	AND tanggal <= '$tanggal_end'
				        	AND status_aktif = 1
				        	) nd_penjualan
				        ON nd_penjualan_detail.penjualan_id = nd_penjualan.id
				        LEFT JOIN (
				            SELECT sum(qty*if(jumlah_roll=0,1, jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, penjualan_detail_id
				            FROM nd_penjualan_qty_detail
				            GROUP BY penjualan_detail_id
				            ) nd_penjualan_qty_detail
				        ON nd_penjualan_qty_detail.penjualan_detail_id = nd_penjualan_detail.id
				        where nd_penjualan.id is not null
				        GROUP BY barang_id, warna_id
			    )UNION(
			        SELECT barang_id, warna_id, 
			        0.00, 0, 
			        0.00, 0, 
			        0.00, 0, 
			        0.00, 0,
			        0.00, 0, 
			        0.00, 0, 
			        0.00 as qty_retur,0 as jumlah_roll_retur, 
			        0.00 as qty_retur_beli,0 as jumlah_roll_retur_beli, 
			        sum(qty), sum(jumlah_roll)
				        FROM (
				        	SELECT *
				        	FROM nd_pengeluaran_stok_lain_detail
				        	WHERE gudang_id = $gudang_id
				        ) nd_pengeluaran_stok_lain_detail
				        LEFT JOIN (
				        	SELECT *
				        	FROM nd_pengeluaran_stok_lain
				        	WHERE tanggal >= '$tanggal'
				        	AND tanggal <= '$tanggal_end'
				        	AND status_aktif = 1
				        	) nd_pengeluaran_stok_lain
				        ON nd_pengeluaran_stok_lain_detail.pengeluaran_stok_lain_id = nd_pengeluaran_stok_lain.id
				        LEFT JOIN (
				            SELECT sum(qty*if(jumlah_roll=0,1, jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, pengeluaran_stok_lain_detail_id
				            FROM nd_pengeluaran_stok_lain_qty_detail
				            GROUP BY pengeluaran_stok_lain_detail_id
				            ) nd_pengeluaran_stok_lain_qty_detail
				        ON nd_pengeluaran_stok_lain_qty_detail.pengeluaran_stok_lain_detail_id = nd_pengeluaran_stok_lain_detail.id
				        where nd_pengeluaran_stok_lain.id is not null
				        GROUP BY barang_id, warna_id
			    )UNION(
					SELECT barang_id, warna_id, 
					0.00, 0, 
					0.00, 0, 
					sum(qty), sum(jumlah_roll),
					0.00, 0,
					0.00, 0,
					0.00 ,0, 
					0.00 as qty_retur, 0 as jumlah_roll_retur,
					0.00 as qty_retur_beli, 0 as jumlah_roll_retur_beli,
					0.00, 0
			        	FROM nd_mutasi_barang
			        	WHERE tanggal >= '$tanggal'
			        	AND tanggal <= '$tanggal_end'
			        	AND status_aktif = 1
			        	AND gudang_id_before = $gudang_id
				        GROUP BY barang_id, warna_id
				)UNION(
					SELECT barang_id, warna_id, 
					0.00, 0, 
					0.00, 0, 
					0.00, 0,
					0.00 ,0, 
					sum(qty) , sum(jumlah_roll),
					0.00, 0, 
					0.00 as qty_retur, 0 as jumlah_roll_retur, 
					0.00 as qty_retur_beli, 0 as jumlah_roll_retur_beli, 
					0.00 as qty_lain, 0 as jumlah_roll_lain
			        	FROM nd_mutasi_barang
			        	WHERE tanggal >= '$tanggal'
			        	AND tanggal <= '$tanggal_end'
			        	AND status_aktif = 1
			        	AND gudang_id_after = $gudang_id
				        GROUP BY barang_id, warna_id
				)UNION(
					SELECT barang_id, warna_id, 
					0.00, 0, 
					0.00, 0, 
					0.00, 0,
					0.00, 0, 
					0.00, 0, 
					sum(qty_masuk) - sum(qty_keluar), sum(jumlah_roll_masuk) - sum(jumlah_roll_keluar), 
					0.00 as qty_retur, 0 as jumlah_roll_retur, 
					0.00 as qty_retur_beli, 0 as jumlah_roll_retur_beli, 
					0.00, 0
						FROM (
							(
						        SELECT  barang_id, warna_id, gudang_id, sum(qty) as qty_masuk, sum(jumlah_roll) as jumlah_roll_masuk, CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar
					        	FROM nd_penyesuaian_stok
					        	WHERE tanggal >= '$tanggal'
					        	AND tanggal <= '$tanggal_end'
					        	AND gudang_id = $gudang_id
					        	AND tipe_transaksi = 1
					        	GROUP BY barang_id, warna_id
						    )UNION(
						        SELECT barang_id, warna_id, gudang_id, CAST(0 as DECIMAL(15,2)) as qty_masuk, 0 as jumlah_roll_masuk, sum(qty) as qty_keluar, sum(jumlah_roll) as jumlah_roll_keluar
					        	FROM nd_penyesuaian_stok
					        	WHERE tanggal >= '$tanggal'
					        	AND tanggal <= '$tanggal_end'
					        	AND gudang_id = $gudang_id
					        	AND tipe_transaksi = 2
								GROUP BY barang_id, warna_id
							    )
							)a
				        GROUP BY barang_id, warna_id
				)UNION(
			    	SELECT barang_id, warna_id, 
			    	0.00, 0, 
			    	0.00, 0, 
			    	0.00, 0,
			    	0.00, 0, 
			    	0.00, 0,
			    	0.00 ,0, 
			    	qty as qty_retur, jumlah_roll as jumlah_roll_retur,
			    	0.00 ,0, 
			    	0.00 ,0
			        FROM (
			        	SELECT *
			        	FROM nd_retur_jual_detail
			        	WHERE gudang_id = $gudang_id
			        ) nd_retur_jual_detail
			        LEFT JOIN (
			        	SELECT *
			        	FROM nd_retur_jual
			        	WHERE tanggal >= '$tanggal'
			        	AND tanggal <= '$tanggal_end'
			        	AND status_aktif = 1
			        	) nd_retur_jual
			        ON nd_retur_jual_detail.retur_jual_id = nd_retur_jual.id
			        LEFT JOIN (
			            SELECT sum(qty*if(jumlah_roll=0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, retur_jual_detail_id
			            FROM nd_retur_jual_qty
			            GROUP BY retur_jual_detail_id
			            ) nd_penjualan_qty_detail
			        ON nd_penjualan_qty_detail.retur_jual_detail_id = nd_retur_jual_detail.id
			        WHERE nd_retur_jual.id is not null
			        GROUP BY barang_id, warna_id
			    )UNION(
			    	SELECT barang_id, warna_id, 
			    	0.00, 0, 
			    	0.00, 0, 
			    	0.00, 0,
			    	0.00, 0, 
			    	0.00, 0,
			    	0.00 ,0, 
			    	0.00 ,0, 
			    	qty as qty_retur_beli, jumlah_roll as jumlah_roll_retur_beli,
			    	0.00 ,0
			        FROM (
			        	SELECT *
			        	FROM nd_retur_beli_detail
			        	WHERE gudang_id = $gudang_id
			        ) nd_retur_beli_detail
			        LEFT JOIN (
			        	SELECT *
			        	FROM nd_retur_beli
			        	WHERE tanggal >= '$tanggal'
			        	AND tanggal <= '$tanggal_end'
			        	AND status_aktif = 1
			        	) nd_retur_beli
			        ON nd_retur_beli_detail.retur_beli_id = nd_retur_beli.id
			        LEFT JOIN (
			            SELECT sum(qty*if(jumlah_roll=0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, retur_beli_detail_id
			            FROM nd_retur_beli_qty
			            GROUP BY retur_beli_detail_id
			            ) nd_beli_qty_detail
			        ON nd_beli_qty_detail.retur_beli_detail_id = nd_retur_beli_detail.id
			        WHERE nd_retur_beli.id is not null
			        GROUP BY barang_id, warna_id
			    )
			)t1
			LEFT JOIN (
				SELECT barang_id, warna_id, ROUND(sum(total_beli)/sum(qty_beli),2) as hpp
				FROM (
					(
						SELECT sum(qty) as qty_beli, sum(qty*harga_beli) as total_beli, barang_id, warna_id
						FROM (
							SELECT *
							FROM nd_pembelian
							WHERE tanggal < '$tanggal'
							) nd_pembelian
						LEFT JOIN nd_pembelian_detail
						ON nd_pembelian.id = nd_pembelian_detail.pembelian_id
						WHERE barang_id is not null
						GROUP BY YEAR(tanggal) , MONTH(tanggal), barang_id, warna_id
					)UNION(
						SELECT sum(qty) as qty_beli, sum(qty*harga_stok_awal) as total_beli, stok_awal.barang_id, stok_awal.warna_id
						FROM (
							SELECT sum(qty) as qty, barang_id, warna_id
							FROM nd_penyesuaian_stok
							WHERE tipe_transaksi = 0
							GROUP BY barang_id, warna_id
							) stok_awal 
						LEFT JOIN nd_stok_awal_item_harga
						ON stok_awal.barang_id = nd_stok_awal_item_harga.barang_id
						GROUP BY barang_id, warna_id
					) 
				)a
				GROUP BY barang_id, warna_id
			) t2
			ON t1.barang_id = t2.barang_id
			AND t1.warna_id = t2.warna_id
			LEFT JOIN (
				SELECT barang_id, warna_id,sum(qty*ROUND(harga_beli/(1 + (ppn_berlaku/100) ),2))/sum(qty) as hpp_beli 
				FROM (
					SELECT *,
					(SELECT ppn FROM nd_ppn tX WHERE tX.tanggal <= nd_pembelian.tanggal ORDER BY tX.tanggal DESC LIMIT 1 ) as ppn_berlaku
					FROM nd_pembelian
					WHERE tanggal >= '$tanggal'
					AND tanggal <= '$tanggal_end'
					) nd_pembelian
				LEFT JOIN nd_pembelian_detail
				ON nd_pembelian.id = nd_pembelian_detail.pembelian_id
				WHERE barang_id is not null
				GROUP BY YEAR(tanggal) , MONTH(tanggal), barang_id, warna_id
			)t5
			ON t1.barang_id = t5.barang_id
			AND t1.warna_id = t5.warna_id 
			LEFT JOIN (
				SELECT barang_id, warna_id,sum(qty*harga_jual) as hpp_jual
				FROM (
		        	SELECT *
		        	FROM nd_penjualan_detail
		        	WHERE gudang_id = $gudang_id
		        ) nd_penjualan_detail
		        LEFT JOIN (
		        	SELECT *
		        	FROM nd_penjualan
		        	WHERE tanggal >= '$tanggal'
		        	AND tanggal <= '$tanggal_end'
		        	AND status_aktif = 1
		        	) nd_penjualan
		        ON nd_penjualan_detail.penjualan_id = nd_penjualan.id
		        LEFT JOIN (
		            SELECT sum(qty*jumlah_roll) as qty, sum(jumlah_roll) as jumlah_roll, penjualan_detail_id
		            FROM nd_penjualan_qty_detail
		            GROUP BY penjualan_detail_id
		            ) nd_penjualan_qty_detail
		        ON nd_penjualan_qty_detail.penjualan_detail_id = nd_penjualan_detail.id
		        where nd_penjualan.id is not null
		        GROUP BY barang_id, warna_id
			)t6
			ON t1.barang_id = t6.barang_id
			AND t1.warna_id = t6.warna_id 
			LEFT JOIN nd_barang as t3
			ON t1.barang_id = t3.id
			LEFT JOIN nd_warna as t4
			ON t1.warna_id = t4.id
			GROUP BY barang_id, warna_id

			", false);

		return $query->result();
	}

	function mutasi_persediaan_pergudang($tanggal_awal, $tanggal_start, $tanggal_end, $gudang_id, $stok_opname_id)
	{	
		$tahun_awal = date("Y", strtotime($tanggal_awal));
		$bulan_awal = date("m", strtotime($tanggal_awal));
		$stok_qty = date("m", strtotime($tanggal_awal)).'_qty';
		$stok_roll = date("m", strtotime($tanggal_awal)).'_roll';
		$query = $this->db->query("SELECT t1.barang_id, t1.warna_id, 
			sum(qty_stock) as qty_stock, sum(jumlah_roll_stock) as jumlah_roll_stock, 
			sum(qty_beli) as qty_beli, sum(jumlah_roll_beli) as jumlah_roll_beli, 
			sum(qty_mutasi) as qty_mutasi, sum(jumlah_roll_mutasi) as jumlah_roll_mutasi, 
			sum(qty_jual) as qty_jual,  sum(jumlah_roll_jual) as jumlah_roll_jual, 
			sum(qty_mutasi_masuk) as qty_mutasi_masuk, sum(jumlah_roll_mutasi_masuk) as jumlah_roll_mutasi_masuk, 
			sum(qty_penyesuaian) as qty_penyesuaian, sum(jumlah_roll_penyesuaian) as jumlah_roll_penyesuaian, 
			sum(qty_retur) as qty_retur, sum(jumlah_roll_retur) as jumlah_roll_retur ,
			sum(qty_retur_beli) as qty_retur_beli, sum(jumlah_roll_retur_beli) as jumlah_roll_retur_beli ,
			sum(qty_lain) as qty_lain, sum(jumlah_roll_lain) as jumlah_roll_lain ,
			t2.nama as nama_barang, nama_jual, warna_jual, ifnull(t2.satuan_id,1) as satuan_id
			FROM (
				(
					
					SELECT barang_id, warna_id,
					$stok_qty as qty_stock, $stok_roll as jumlah_roll_stock, 
					0 as qty_beli, 0 as jumlah_roll_beli, 
					0.00 as qty_mutasi, 0 as jumlah_roll_mutasi, 
					0.00 as qty_jual, 0 as jumlah_roll_jual, 
					0.00 as qty_mutasi_masuk, 0 as jumlah_roll_mutasi_masuk, 
					0.00 as qty_penyesuaian, 0 as jumlah_roll_penyesuaian, 
					0.00 as qty_retur, 0 as jumlah_roll_retur,
					0.00 as qty_retur_beli, 0 as jumlah_roll_retur_beli,
					0.00 as qty_lain, 0 as jumlah_roll_lain					
			        FROM nd_tutup_buku_detail_gudang
					WHERE YEAR(tahun) = '$tahun_awal'
					AND gudang_id = $gudang_id
				)UNION(
					SELECT barang_id, warna_id, 
					0 as qty_stock, 0 as jumlah_roll_stock, 
					sum(qty) as qty_beli, sum(jumlah_roll) as jumlah_roll_beli, 
					0.00 as qty_mutasi, 0 as jumlah_roll_mutasi, 
					0.00 as qty_jual, 0 as jumlah_roll_jual, 
					0.00 as qty_mutasi_masuk, 0 as jumlah_roll_mutasi_masuk, 
					0.00 as qty_penyesuaian, 0 as jumlah_roll_penyesuaian, 
					0.00 as qty_retur, 0 as jumlah_roll_retur,
					0.00 as qty_retur_beli, 0 as jumlah_roll_retur_beli,
					0.00 as qty_lain, 0 as jumlah_roll_lain
					
			        FROM (
			        	SELECT qty, jumlah_roll, id, barang_id, warna_id, pembelian_id
			        	FROM nd_pembelian_detail
			        	ORDER BY pembelian_id
			        ) nd_pembelian_detail
			        LEFT JOIN (
			        	SELECT *
			        	FROM nd_pembelian
			        	WHERE tanggal >= '$tanggal_start'
			        	AND tanggal <= '$tanggal_end'
			        	AND gudang_id = $gudang_id
			        	AND status_aktif = 1
			        	) nd_pembelian
			        ON nd_pembelian_detail.pembelian_id = nd_pembelian.id
			        WHERE nd_pembelian.id is not null
			        GROUP BY barang_id, warna_id
				)UNION(
			        SELECT barang_id, warna_id, 
			        0.00, 0, 
			        0.00, 0, 
			        0.00, 0, 
			        sum(qty), sum(jumlah_roll), 
			        0.00, 0,
			        0.00, 0, 
			        0.00 as qty_retur, 0 as jumlah_roll_retur,
			        0.00 as qty_retur_beli, 0 as jumlah_roll_retur_beli,
			        0.00 as qty_lain, 0 as jumlah_roll_lain
				        FROM (
				        	SELECT *
				        	FROM nd_penjualan_detail
				        	WHERE gudang_id = $gudang_id
				        ) nd_penjualan_detail
				        LEFT JOIN (
				        	SELECT *
				        	FROM nd_penjualan
				        	WHERE tanggal >= '$tanggal_start'
				        	AND tanggal <= '$tanggal_end'
				        	AND status_aktif = 1
				        	) nd_penjualan
				        ON nd_penjualan_detail.penjualan_id = nd_penjualan.id
				        LEFT JOIN (
				            SELECT sum(qty*if(jumlah_roll=0,1, jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, penjualan_detail_id
				            FROM nd_penjualan_qty_detail
				            GROUP BY penjualan_detail_id
				            ) nd_penjualan_qty_detail
				        ON nd_penjualan_qty_detail.penjualan_detail_id = nd_penjualan_detail.id
				        where nd_penjualan.id is not null
				        GROUP BY barang_id, warna_id
			    )UNION(
			        SELECT barang_id, warna_id, 
			        0.00, 0, 
			        0.00, 0, 
			        0.00, 0, 
			        0.00, 0,
			        0.00, 0, 
			        0.00, 0, 
			        0.00 as qty_retur,0 as jumlah_roll_retur, 
			        0.00 as qty_retur_beli,0 as jumlah_roll_retur_beli, 
			        sum(qty), sum(jumlah_roll)
				        FROM (
				        	SELECT *
				        	FROM nd_pengeluaran_stok_lain_detail
				        	WHERE gudang_id = $gudang_id
				        ) nd_pengeluaran_stok_lain_detail
				        LEFT JOIN (
				        	SELECT *
				        	FROM nd_pengeluaran_stok_lain
				        	WHERE tanggal >= '$tanggal_start'
				        	AND tanggal <= '$tanggal_end'
				        	AND status_aktif = 1
				        	) nd_pengeluaran_stok_lain
				        ON nd_pengeluaran_stok_lain_detail.pengeluaran_stok_lain_id = nd_pengeluaran_stok_lain.id
				        LEFT JOIN (
				            SELECT sum(qty*if(jumlah_roll=0,1, jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, pengeluaran_stok_lain_detail_id
				            FROM nd_pengeluaran_stok_lain_qty_detail
				            GROUP BY pengeluaran_stok_lain_detail_id
				            ) nd_pengeluaran_stok_lain_qty_detail
				        ON nd_pengeluaran_stok_lain_qty_detail.pengeluaran_stok_lain_detail_id = nd_pengeluaran_stok_lain_detail.id
				        where nd_pengeluaran_stok_lain.id is not null
				        GROUP BY barang_id, warna_id
			    )UNION(
					SELECT barang_id, warna_id, 
					0.00, 0, 
					0.00, 0, 
					sum(qty), sum(jumlah_roll),
					0.00, 0,
					0.00, 0,
					0.00 ,0, 
					0.00 as qty_retur, 0 as jumlah_roll_retur,
					0.00 as qty_retur_beli, 0 as jumlah_roll_retur_beli,
					0.00, 0
			        	FROM nd_mutasi_barang
			        	WHERE tanggal >= '$tanggal_start'
			        	AND tanggal <= '$tanggal_end'
			        	AND status_aktif = 1
			        	AND gudang_id_before = $gudang_id
				        GROUP BY barang_id, warna_id
				)UNION(
					SELECT barang_id, warna_id, 
					0.00, 0, 
					0.00, 0, 
					0.00, 0,
					0.00 ,0, 
					sum(qty) , sum(jumlah_roll),
					0.00, 0, 
					0.00 as qty_retur, 0 as jumlah_roll_retur, 
					0.00 as qty_retur_beli, 0 as jumlah_roll_retur_beli, 
					0.00 as qty_lain, 0 as jumlah_roll_lain
			        	FROM nd_mutasi_barang
			        	WHERE tanggal >= '$tanggal_start'
			        	AND tanggal <= '$tanggal_end'
			        	AND status_aktif = 1
			        	AND gudang_id_after = $gudang_id
				        GROUP BY barang_id, warna_id
				)UNION(
					SELECT barang_id, warna_id, 
					0.00, 0, 
					0.00, 0, 
					0.00, 0,
					0.00, 0, 
					0.00, 0, 
					sum(qty_masuk) - sum(qty_keluar), sum(jumlah_roll_masuk) - sum(jumlah_roll_keluar), 
					0.00 as qty_retur, 0 as jumlah_roll_retur, 
					0.00 as qty_retur_beli, 0 as jumlah_roll_retur_beli, 
					0.00, 0
						FROM (
							(
						        SELECT  barang_id, warna_id, gudang_id, sum(qty) as qty_masuk, sum(jumlah_roll) as jumlah_roll_masuk, CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar
					        	FROM nd_penyesuaian_stok
					        	WHERE tanggal >= '$tanggal_start'
					        	AND tanggal <= '$tanggal_end'
					        	AND gudang_id = $gudang_id
					        	AND tipe_transaksi = 1
					        	GROUP BY barang_id, warna_id
						    )UNION(
						        SELECT barang_id, warna_id, gudang_id, CAST(0 as DECIMAL(15,2)) as qty_masuk, 0 as jumlah_roll_masuk, sum(qty) as qty_keluar, sum(jumlah_roll) as jumlah_roll_keluar
					        	FROM nd_penyesuaian_stok
					        	WHERE tanggal >= '$tanggal_start'
					        	AND tanggal <= '$tanggal_end'
					        	AND gudang_id = $gudang_id
					        	AND tipe_transaksi = 2
								GROUP BY barang_id, warna_id
							)UNION(
						        SELECT  barang_id, warna_id, gudang_id, sum(qty) as qty_masuk, sum(jumlah_roll) as jumlah_roll_masuk, CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar
					        	FROM nd_penyesuaian_stok
					        	WHERE tanggal >= '$tanggal_start'
					        	AND tanggal <= '$tanggal_end'
					        	AND gudang_id = $gudang_id
					        	AND tipe_transaksi = 4
					        	GROUP BY barang_id, warna_id
						    )
							)a
				        GROUP BY barang_id, warna_id
				)UNION(
			    	SELECT barang_id, warna_id, 
			    	0.00, 0, 
			    	0.00, 0, 
			    	0.00, 0,
			    	0.00, 0, 
			    	0.00, 0,
			    	0.00 ,0, 
			    	qty as qty_retur, jumlah_roll as jumlah_roll_retur,
			    	0.00 ,0, 
			    	0.00 ,0
			        FROM (
			        	SELECT *
			        	FROM nd_retur_jual_detail
			        	WHERE gudang_id = $gudang_id
			        ) nd_retur_jual_detail
			        LEFT JOIN (
			        	SELECT *
			        	FROM nd_retur_jual
			        	WHERE tanggal >= '$tanggal_start'
			        	AND tanggal <= '$tanggal_end'
			        	AND status_aktif = 1
			        	) nd_retur_jual
			        ON nd_retur_jual_detail.retur_jual_id = nd_retur_jual.id
			        LEFT JOIN (
			            SELECT sum(qty*if(jumlah_roll=0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, retur_jual_detail_id
			            FROM nd_retur_jual_qty
			            GROUP BY retur_jual_detail_id
			            ) nd_penjualan_qty_detail
			        ON nd_penjualan_qty_detail.retur_jual_detail_id = nd_retur_jual_detail.id
			        WHERE nd_retur_jual.id is not null
			        GROUP BY barang_id, warna_id
			    )UNION(
			    	SELECT barang_id, warna_id, 
			    	0.00, 0, 
			    	0.00, 0, 
			    	0.00, 0,
			    	0.00, 0, 
			    	0.00, 0,
			    	0.00 ,0, 
			    	0.00 ,0, 
			    	qty as qty_retur_beli, jumlah_roll as jumlah_roll_retur_beli,
			    	0.00 ,0
			        FROM (
			        	SELECT *
			        	FROM nd_retur_beli_detail
			        	WHERE gudang_id = $gudang_id
			        ) nd_retur_beli_detail
			        LEFT JOIN (
			        	SELECT *
			        	FROM nd_retur_beli
			        	WHERE tanggal >= '$tanggal_start'
			        	AND tanggal <= '$tanggal_end'
			        	AND status_aktif = 1
			        	) nd_retur_beli
			        ON nd_retur_beli_detail.retur_beli_id = nd_retur_beli.id
			        LEFT JOIN (
			            SELECT sum(qty*if(jumlah_roll=0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, retur_beli_detail_id
			            FROM nd_retur_beli_qty
			            GROUP BY retur_beli_detail_id
			            ) nd_beli_qty_detail
			        ON nd_beli_qty_detail.retur_beli_detail_id = nd_retur_beli_detail.id
			        WHERE nd_retur_beli.id is not null
			        GROUP BY barang_id, warna_id
			    )
			)t1
			LEFT JOIN nd_barang t2
			ON t1.barang_id = t2.id
			LEFT JOIN nd_warna t3
			ON t1.warna_id = t3.id
			GROUP BY barang_id, warna_id
			");
		return $query->result();
		// return $this->db->last_query();
	}

	function mutasi_persediaan_pergudang_by_input($tanggal_awal, $tanggal_start, $tanggal_end, $gudang_id, $stok_opname_id)
	{	
		$tahun_awal = date("Y", strtotime($tanggal_awal));
		$bulan_awal = date("m", strtotime($tanggal_awal));
		$stok_qty = date("m", strtotime($tanggal_awal)).'_qty';
		$stok_roll = date("m", strtotime($tanggal_awal)).'_roll';
		$tanggal_start = $tanggal_start.' 00:00:00';
		$tanggal_end = $tanggal_end.' 23:59:00';
		$query = $this->db->query("SELECT t1.barang_id, t1.warna_id, 
			sum(qty_stock) as qty_stock, sum(jumlah_roll_stock) as jumlah_roll_stock, 
			sum(ifnull(qty_beli,0)) as qty_beli, sum(jumlah_roll_beli) as jumlah_roll_beli, 
			sum(qty_mutasi) as qty_mutasi, sum(jumlah_roll_mutasi) as jumlah_roll_mutasi, 
			sum(qty_jual) as qty_jual,  sum(jumlah_roll_jual) as jumlah_roll_jual, 
			sum(qty_mutasi_masuk) as qty_mutasi_masuk, sum(jumlah_roll_mutasi_masuk) as jumlah_roll_mutasi_masuk, 
			sum(qty_penyesuaian) as qty_penyesuaian, sum(jumlah_roll_penyesuaian) as jumlah_roll_penyesuaian, 
			sum(qty_retur) as qty_retur, sum(jumlah_roll_retur) as jumlah_roll_retur ,
			sum(qty_retur_beli) as qty_retur_beli, sum(jumlah_roll_retur_beli) as jumlah_roll_retur_beli ,
			sum(qty_lain) as qty_lain, sum(jumlah_roll_lain) as jumlah_roll_lain ,
			sum(qty_assembly) as qty_assembly, sum(jumlah_roll_assembly) as jumlah_roll_assembly,
			t2.nama as nama_barang, nama_jual, warna_jual, ifnull(t2.satuan_id,1) as satuan_id
			FROM (
				(
					
					SELECT barang_id, warna_id,
					$stok_qty as qty_stock, $stok_roll as jumlah_roll_stock, 
					0 as qty_beli, 0 as jumlah_roll_beli, 
					0.00 as qty_mutasi, 0 as jumlah_roll_mutasi, 
					0.00 as qty_jual, 0 as jumlah_roll_jual, 
					0.00 as qty_mutasi_masuk, 0 as jumlah_roll_mutasi_masuk, 
					0.00 as qty_penyesuaian, 0 as jumlah_roll_penyesuaian, 
					0.00 as qty_retur, 0 as jumlah_roll_retur,
					0.00 as qty_retur_beli, 0 as jumlah_roll_retur_beli,
					0.00 as qty_lain, 0 as jumlah_roll_lain,	
					0.00 as qty_assembly, 0 as jumlah_roll_assembly
			        FROM nd_tutup_buku_detail_gudang
					WHERE YEAR(tahun) = '$tahun_awal'
					AND gudang_id = $gudang_id
				)UNION(
					SELECT barang_id, warna_id, 
					0 as qty_stock, 0 as jumlah_roll_stock, 
					sum(qty) as qty_beli, sum(jumlah_roll) as jumlah_roll_beli, 
					0.00 as qty_mutasi, 0 as jumlah_roll_mutasi, 
					0.00 as qty_jual, 0 as jumlah_roll_jual, 
					0.00 as qty_mutasi_masuk, 0 as jumlah_roll_mutasi_masuk, 
					0.00 as qty_penyesuaian, 0 as jumlah_roll_penyesuaian, 
					0.00 as qty_retur, 0 as jumlah_roll_retur,
					0.00 as qty_retur_beli, 0 as jumlah_roll_retur_beli,
					0.00 as qty_lain, 0 as jumlah_roll_lain,
					0.00 as qty_assembly, 0 as jumlah_roll_assembly
					
			        FROM (
			        	SELECT qty, jumlah_roll, id, barang_id, warna_id, pembelian_id
			        	FROM nd_pembelian_detail
			        	ORDER BY pembelian_id
			        ) nd_pembelian_detail
			        LEFT JOIN (
			        	SELECT *
			        	FROM nd_pembelian
			        	WHERE created_at >= '$tanggal_start'
			        	AND created_at <= '$tanggal_end'
			        	AND gudang_id = $gudang_id
			        	AND status_aktif = 1
			        	) nd_pembelian
			        ON nd_pembelian_detail.pembelian_id = nd_pembelian.id
			        WHERE nd_pembelian.id is not null
			        GROUP BY barang_id, warna_id
				)UNION(
			        SELECT barang_id, warna_id, 
			        0.00, 0, 
			        0.00, 0, 
			        0.00, 0, 
			        sum(qty), sum(jumlah_roll), 
			        0.00, 0,
			        0.00, 0, 
			        0.00 as qty_retur, 0 as jumlah_roll_retur,
			        0.00 as qty_retur_beli, 0 as jumlah_roll_retur_beli,
			        0.00 as qty_lain, 0 as jumlah_roll_lain,
					0.00 as qty_assembly, 0 as jumlah_roll_assembly
				        FROM (
				        	SELECT *
				        	FROM nd_penjualan_detail
				        	WHERE gudang_id = $gudang_id
				        ) nd_penjualan_detail
				        LEFT JOIN (
				        	SELECT *
				        	FROM nd_penjualan
				        	WHERE closed_date >= '$tanggal_start'
				        	AND closed_date <= '$tanggal_end'
				        	AND status_aktif = 1
				        	) nd_penjualan
				        ON nd_penjualan_detail.penjualan_id = nd_penjualan.id
				        LEFT JOIN (
				            SELECT sum(qty*if(jumlah_roll=0,1, jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, penjualan_detail_id
				            FROM nd_penjualan_qty_detail
				            GROUP BY penjualan_detail_id
				            ) nd_penjualan_qty_detail
				        ON nd_penjualan_qty_detail.penjualan_detail_id = nd_penjualan_detail.id
				        where nd_penjualan.id is not null
				        GROUP BY barang_id, warna_id
			    )UNION(
			        SELECT barang_id, warna_id, 
			        0.00, 0, 
			        0.00, 0, 
			        0.00, 0, 
			        0.00, 0,
			        0.00, 0, 
			        0.00, 0, 
			        0.00, 0, 
			        0.00, 0, 
			        sum(qty), sum(jumlah_roll),
			        0.00, 0 
				        FROM (
				        	SELECT *
				        	FROM nd_pengeluaran_stok_lain_detail
				        	WHERE gudang_id = $gudang_id
				        ) nd_pengeluaran_stok_lain_detail
				        LEFT JOIN (
				        	SELECT *
				        	FROM nd_pengeluaran_stok_lain
				        	WHERE created_at >= '$tanggal_start'
				        	AND created_at <= '$tanggal_end'
				        	AND status_aktif = 1
				        	) nd_pengeluaran_stok_lain
				        ON nd_pengeluaran_stok_lain_detail.pengeluaran_stok_lain_id = nd_pengeluaran_stok_lain.id
				        LEFT JOIN (
				            SELECT sum(qty*if(jumlah_roll=0,1, jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, pengeluaran_stok_lain_detail_id
				            FROM nd_pengeluaran_stok_lain_qty_detail
				            GROUP BY pengeluaran_stok_lain_detail_id
				            ) nd_pengeluaran_stok_lain_qty_detail
				        ON nd_pengeluaran_stok_lain_qty_detail.pengeluaran_stok_lain_detail_id = nd_pengeluaran_stok_lain_detail.id
				        where nd_pengeluaran_stok_lain.id is not null
				        GROUP BY barang_id, warna_id
			    )UNION(
					SELECT barang_id, warna_id, 
					0.00, 0, 
					0.00, 0, 
					sum(qty), sum(jumlah_roll),
					0.00, 0,
					0.00, 0,
					0.00 ,0, 
					0.00 as qty_retur, 0 as jumlah_roll_retur,
					0.00 as qty_retur_beli, 0 as jumlah_roll_retur_beli,
			        0.00, 0, 
					0.00, 0
			        	FROM nd_mutasi_barang
			        	WHERE created_at >= '$tanggal_start'
			        	AND created_at <= '$tanggal_end'
			        	AND status_aktif = 1
			        	AND gudang_id_before = $gudang_id
				        GROUP BY barang_id, warna_id
				)UNION(
					SELECT barang_id, warna_id, 
					0.00, 0, 
					0.00, 0, 
					0.00, 0,
					0.00 ,0, 
					sum(qty) , sum(jumlah_roll),
					0.00, 0, 
					0.00 as qty_retur, 0 as jumlah_roll_retur, 
					0.00 as qty_retur_beli, 0 as jumlah_roll_retur_beli, 
					0.00 as qty_lain, 0 as jumlah_roll_lain,
			        0.00, 0
			        	FROM nd_mutasi_barang
			        	WHERE created_at >= '$tanggal_start'
			        	AND created_at <= '$tanggal_end'
			        	AND status_aktif = 1
			        	AND gudang_id_after = $gudang_id
				        GROUP BY barang_id, warna_id
				)UNION(
					SELECT barang_id, warna_id, 
					0.00, 0, 
					0.00, 0, 
					0.00, 0,
					0.00, 0, 
					0.00, 0, 
					sum(qty_masuk) - sum(qty_keluar), sum(jumlah_roll_masuk) - sum(jumlah_roll_keluar), 
					0.00 as qty_retur, 0 as jumlah_roll_retur, 
					0.00 as qty_retur_beli, 0 as jumlah_roll_retur_beli, 
			        0.00, 0, 
					0.00, 0
						FROM (
							(
						        SELECT  barang_id, warna_id, gudang_id, sum(qty) as qty_masuk, sum(jumlah_roll) as jumlah_roll_masuk, CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar, created_at
					        	FROM nd_penyesuaian_stok
					        	WHERE created_at >= '$tanggal_start'
					        	AND created_at <= '$tanggal_end'
					        	AND gudang_id = $gudang_id
					        	AND tipe_transaksi = 1
					        	GROUP BY barang_id, warna_id
						    )UNION(
						        SELECT barang_id, warna_id, gudang_id, CAST(0 as DECIMAL(15,2)) as qty_masuk, 0 as jumlah_roll_masuk, sum(qty) as qty_keluar, sum(jumlah_roll) as jumlah_roll_keluar, created_at
					        	FROM nd_penyesuaian_stok
					        	WHERE created_at >= '$tanggal_start'
					        	AND created_at <= '$tanggal_end'
					        	AND gudang_id = $gudang_id
					        	AND tipe_transaksi = 2
								GROUP BY barang_id, warna_id
							)UNION(
						        SELECT  barang_id, warna_id, gudang_id, sum(qty) as qty_masuk, sum(jumlah_roll) as jumlah_roll_masuk, CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar, created_at
					        	FROM nd_penyesuaian_stok
					        	WHERE created_at >= '$tanggal_start'
					        	AND created_at <= '$tanggal_end'
					        	AND gudang_id = $gudang_id
					        	AND tipe_transaksi = 4
					        	GROUP BY barang_id, warna_id
						    )
							)a
				        GROUP BY barang_id, warna_id
				)UNION(
					SELECT barang_id, warna_id, 
					0.00, 0, 
					0.00, 0, 
					0.00, 0,
					0.00, 0, 
					0.00, 0, 
					0.00, 0,  
					0.00, 0, 
					0.00, 0, 
			        0.00, 0, 
					sum(a.qty) - sum(a.qty) AS qty_assembly, sum(b.jumlah_roll) - sum(a.jumlah_roll) as jumlah_roll_assembly
					FROM (
							SELECT *
							FROM nd_penyesuaian_stok
							WHERE created_at >= '$tanggal_start'
							AND created_at <= '$tanggal_end'
							AND gudang_id = $gudang_id
							AND tipe_transaksi = 3
					)a
					LEFT JOIN (
						SELECT sum(jumlah_roll) as jumlah_roll, penyesuaian_stok_id
						FROM nd_penyesuaian_stok_split
						GROUP BY penyesuaian_stok_id
						)b
					ON a.id = b.penyesuaian_stok_id
					GROUP BY barang_id, warna_id
				)UNION(
			    	SELECT barang_id, warna_id, 
			    	0.00, 0, 
			    	0.00, 0, 
			    	0.00, 0,
			    	0.00, 0, 
			    	0.00, 0,
			    	0.00 ,0, 
			    	qty as qty_retur, jumlah_roll as jumlah_roll_retur,
			    	0.00 ,0, 
			        0.00, 0, 
			    	0.00 ,0
			        FROM (
			        	SELECT *
			        	FROM nd_retur_jual_detail
			        	WHERE gudang_id = $gudang_id
			        ) nd_retur_jual_detail
			        LEFT JOIN (
			        	SELECT *
			        	FROM nd_retur_jual
			        	WHERE created_at >= '$tanggal_start'
			        	AND created_at <= '$tanggal_end'
			        	AND status_aktif = 1
			        	) nd_retur_jual
			        ON nd_retur_jual_detail.retur_jual_id = nd_retur_jual.id
			        LEFT JOIN (
			            SELECT sum(qty*if(jumlah_roll=0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, retur_jual_detail_id
			            FROM nd_retur_jual_qty
			            GROUP BY retur_jual_detail_id
			            ) nd_penjualan_qty_detail
			        ON nd_penjualan_qty_detail.retur_jual_detail_id = nd_retur_jual_detail.id
			        WHERE nd_retur_jual.id is not null
			        GROUP BY barang_id, warna_id
			    )UNION(
			    	SELECT barang_id, warna_id, 
			    	0.00, 0, 
			    	0.00, 0, 
			    	0.00, 0,
			    	0.00, 0, 
			    	0.00, 0,
			    	0.00 ,0, 
			    	0.00 ,0, 
			    	sum(qty) as qty_retur_beli, sum(jumlah_roll) as jumlah_roll_retur_beli,
			        0.00, 0, 
			    	0.00 ,0
			        FROM (
			        	SELECT *
			        	FROM nd_retur_beli_detail
			        	WHERE gudang_id = $gudang_id
			        ) nd_retur_beli_detail
			        LEFT JOIN (
			        	SELECT *
			        	FROM nd_retur_beli
			        	WHERE created_at >= '$tanggal_start'
			        	AND created_at <= '$tanggal_end'
			        	AND status_aktif = 1
			        	) nd_retur_beli
			        ON nd_retur_beli_detail.retur_beli_id = nd_retur_beli.id
			        LEFT JOIN (
			            SELECT sum(qty*if(jumlah_roll=0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, retur_beli_detail_id
			            FROM nd_retur_beli_qty
			            GROUP BY retur_beli_detail_id
			            ) nd_beli_qty_detail
			        ON nd_beli_qty_detail.retur_beli_detail_id = nd_retur_beli_detail.id
			        WHERE nd_retur_beli.id is not null
			        GROUP BY barang_id, warna_id
			    )
			)t1
			LEFT JOIN nd_barang t2
			ON t1.barang_id = t2.id
			LEFT JOIN nd_warna t3
			ON t1.warna_id = t3.id
			GROUP BY barang_id, warna_id
			");
		return $query->result();
		// return $this->db->last_query();
	}

	function mutasi_persediaan_barang_global($tanggal_awal, $tanggal,$tanggal_end, $stok_opname_id, $tutup_buku_id, $kolom_harga){

		$query = $this->db->query("SELECT t1.barang_id, t1.warna_id, 
			sum(qty_stock) as qty_stock, sum(jumlah_roll_stock) as jumlah_roll_stock, 
			sum(qty_beli) as qty_beli, sum(jumlah_roll_beli) as jumlah_roll_beli, 
			sum(qty_mutasi) as qty_mutasi, sum(jumlah_roll_mutasi) as jumlah_roll_mutasi, 
			sum(qty_jual) as qty_jual, sum(jumlah_roll_jual) as jumlah_roll_jual, 
			sum(qty_mutasi_masuk) as qty_mutasi_masuk, 
			sum(jumlah_roll_mutasi_masuk) as jumlah_roll_mutasi_masuk, 
			sum(qty_penyesuaian) as qty_penyesuaian, sum(jumlah_roll_penyesuaian) as jumlah_roll_penyesuaian, 
			sum(qty_retur) as qty_retur, sum(jumlah_roll_retur) as jumlah_roll_retur,
			sum(qty_retur_beli) as qty_retur_beli, sum(jumlah_roll_retur_beli) as jumlah_roll_retur_beli,
			sum(qty_lain) as qty_lain, sum(jumlah_roll_lain) as jumlah_roll_lain,
			hpp, hpp_beli, hpp_jual, t3.nama as nama_barang, nama_jual, warna_jual, t3.satuan_id, tipe_qty
			FROM (
				(
					SELECT barang_id, warna_id , 
						sum(ifnull(qty_masuk,0)) - sum(ifnull(qty_keluar,0)) as qty_stock, 
						sum(ifnull(jumlah_roll_masuk,0)) -sum(ifnull(jumlah_roll_keluar,0)) as jumlah_roll_stock, 
						0.00 as qty_beli, 0 as jumlah_roll_beli, 
						0.00 as qty_mutasi, 0 as jumlah_roll_mutasi, 
						0.00 as qty_jual, 0 as jumlah_roll_jual, 
						0.00 as qty_mutasi_masuk, 0 as jumlah_roll_mutasi_masuk, 
						0.00 as qty_penyesuaian, 0 as jumlah_roll_penyesuaian, 
						0.00 as qty_retur, 0 as jumlah_roll_retur, 
						0.00 as qty_retur_beli, 0 as jumlah_roll_retur_beli, 
						0.00 as qty_lain, 0 as jumlah_roll_lain 
					FROM (
						(
					        SELECT barang_id, warna_id, t2.gudang_id, sum(qty) as qty_masuk, sum(jumlah_roll) as jumlah_roll_masuk, CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar, 1 as tipe, t1.id
					        FROM (
					        	SELECT qty, jumlah_roll, id, barang_id, warna_id, pembelian_id
					        	FROM nd_pembelian_detail
					        	ORDER BY pembelian_id
					        ) t1
					        LEFT JOIN (
					        	SELECT *
					        	FROM nd_pembelian
					        	WHERE tanggal < '$tanggal'
					        	AND tanggal >= '$tanggal_awal'
					        	AND status_aktif = 1
					        	) t2
					        ON t1.pembelian_id = t2.id
					        WHERE t2.id is not null
					        GROUP BY barang_id, warna_id
					    )UNION(
					    	SELECT barang_id, warna_id, gudang_id_after, sum(qty) as qty_masuk, sum(jumlah_roll) as jumlah_roll_masuk, CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar, 2 as tipe, t1.id
				        	FROM nd_mutasi_barang t1
				        	WHERE tanggal < '$tanggal'
				        	AND tanggal >= '$tanggal_awal'
				        	AND status_aktif = 1
					        GROUP BY barang_id, warna_id
					    )UNION(
					        SELECT barang_id, warna_id, t1.gudang_id, CAST(0 as DECIMAL(15,2)) as qty_masuk, 0 as jumlah_roll_masuk, sum(qty) as qty_keluar, sum(jumlah_roll) as jumlah_roll_keluar, 3 as tipe, t1.id
					        FROM (
					        	SELECT *
					        	FROM nd_penjualan_detail
					        ) t1
					        LEFT JOIN (
					        	SELECT *
					        	FROM nd_penjualan
					        	WHERE tanggal < '$tanggal'
					        	AND tanggal >= '$tanggal_awal'
					        	AND status_aktif = 1
					        	) t2
					        ON t1.penjualan_id = t2.id
					        LEFT JOIN (
					            SELECT sum(qty*if(jumlah_roll=0,1, jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, penjualan_detail_id
					            FROM nd_penjualan_qty_detail
					            GROUP BY penjualan_detail_id
					            ) t3
					        ON t3.penjualan_detail_id = t1.id
					        where t2.id is not null
					        GROUP BY barang_id, warna_id
					    )UNION(
					        SELECT barang_id, warna_id, t1.gudang_id, CAST(0 as DECIMAL(15,2)) as qty_masuk, 0 as jumlah_roll_masuk, sum(qty) as qty_keluar, sum(jumlah_roll) as jumlah_roll_keluar, 20 as tipe, t1.id
					        FROM (
					        	SELECT *
					        	FROM nd_pengeluaran_stok_lain_detail
					        ) t1
					        LEFT JOIN (
					        	SELECT *
					        	FROM nd_pengeluaran_stok_lain
					        	WHERE tanggal < '$tanggal'
					        	AND tanggal >= '$tanggal_awal'
					        	AND status_aktif = 1
					        	) t2
					        ON t1.pengeluaran_stok_lain_id = t2.id
					        LEFT JOIN (
					            SELECT sum(qty*if(jumlah_roll=0,1, jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, pengeluaran_stok_lain_detail_id
					            FROM nd_pengeluaran_stok_lain_qty_detail
					            GROUP BY pengeluaran_stok_lain_detail_id
					            ) t3
					        ON t3.pengeluaran_stok_lain_detail_id = t1.id
					        where t2.id is not null
					        GROUP BY barang_id, warna_id
					    )UNION(
					    	SELECT barang_id, warna_id, t1.gudang_id, sum(qty) as qty_masuk, sum(jumlah_roll) as jumlah_roll_masuk, CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar, 4 as tipe, t1.id
					        FROM (
					        	SELECT *
					        	FROM nd_retur_jual_detail
					        ) t1
					        LEFT JOIN (
					        	SELECT *
					        	FROM nd_retur_jual
					        	WHERE tanggal < '$tanggal'
					        	AND tanggal >= '$tanggal_awal'
					        	AND status_aktif = 1
					        	) t2
					        ON t1.retur_jual_id = t2.id
					        LEFT JOIN (
					            SELECT sum(qty*if(jumlah_roll=0,1, jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, retur_jual_detail_id
					            FROM nd_retur_jual_qty
					            GROUP BY retur_jual_detail_id
					            ) t3
					        ON t3.retur_jual_detail_id = t1.id
					        WHERE t2.id is not null
					        GROUP BY barang_id, warna_id
					    )UNION(
					        SELECT barang_id, warna_id, gudang_id, sum(qty) as qty_masuk, sum(jumlah_roll) as jumlah_roll_masuk, CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar, 5 as tipe, t1.id
					        	FROM nd_penyesuaian_stok t1
					        	WHERE tipe_transaksi = 0
					        	AND tanggal < '$tanggal'
					        	AND tanggal >= '$tanggal_awal'
		                        GROUP BY barang_id, warna_id
					    )UNION(
					        SELECT  barang_id, warna_id, gudang_id, sum(qty) as qty_masuk, sum(jumlah_roll) as jumlah_roll_masuk, CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar, 6 as tipe, t1.id
				        	FROM nd_penyesuaian_stok t1
				        	WHERE tanggal < '$tanggal'
				        	AND tanggal >= '$tanggal_awal'
				        	AND tipe_transaksi = 1
				        	GROUP BY barang_id, warna_id
					    )UNION(
					        SELECT barang_id, warna_id, gudang_id, CAST(0 as DECIMAL(15,2)) as qty_masuk, 0 as jumlah_roll_masuk, sum(qty) as qty_keluar, sum(jumlah_roll) , 7 as tipe, t1.id
				        	FROM nd_penyesuaian_stok t1
				        	WHERE tanggal < '$tanggal'
				        	AND tanggal >= '$tanggal_awal'
				        	AND tipe_transaksi = 2
							GROUP BY barang_id, warna_id
					    )UNION(
					    	SELECT barang_id, warna_id, gudang_id_before, CAST(0 as DECIMAL(15,2)) as qty_masuk, 0 as jumlah_roll_masuk, sum(qty) as qty_keluar, sum(jumlah_roll) as jumlah_roll_keluar, 8 as tipe, t1.id
				        	FROM nd_mutasi_barang t1
				        	WHERE tanggal < '$tanggal'
				        	AND tanggal >= '$tanggal_awal'
				        	AND status_aktif = 1
							GROUP BY barang_id, warna_id
					    )UNION(
					    	SELECT barang_id, warna_id, gudang_id, sum(qty*jumlah_roll), sum(jumlah_roll) as jumlah_roll_masuk, 0 as qty_keluar, 0 as jumlah_roll_keluar, 9 as tipe, t1.id
				        	FROM nd_stok_opname_detail t1
				        	WHERE stok_opname_id = $stok_opname_id
			        		AND warna_id > 0
				        	GROUP BY barang_id, warna_id
					    )
					) a
					GROUP BY barang_id, warna_id
				)UNION (
					SELECT barang_id, warna_id, 
					0.00, 0, 
					sum(qty) as qty_beli, sum(jumlah_roll) as jumlah_roll_beli, 
					0.00 , 0, 
					0.00, 0 , 
					0.00, 0,
					0.00 ,0, 
					0.00, 0 as jumlah_roll_retur, 
					0.00, 0 as jumlah_roll_retur, 
					0.00, 0 as jumlah_roll_lain 
			        FROM (
			        	SELECT qty, jumlah_roll, id, barang_id, warna_id, pembelian_id
			        	FROM nd_pembelian_detail
			        	ORDER BY pembelian_id
			        ) nd_pembelian_detail
			        LEFT JOIN (
			        	SELECT *
			        	FROM nd_pembelian
			        	WHERE tanggal >= '$tanggal'
			        	AND tanggal <= '$tanggal_end'
			        	AND status_aktif = 1
			        	) nd_pembelian
			        ON nd_pembelian_detail.pembelian_id = nd_pembelian.id
			        WHERE nd_pembelian.id is not null
			        GROUP BY barang_id, warna_id
				)UNION(
			        SELECT barang_id, warna_id, 
			        0.00, 0, 
			        0.00, 0, 
			        0.00,0, 
			        sum(qty), sum(jumlah_roll), 
			        0.00, 0,
			        0.00 ,0, 
			        0.00, 0,  
			        0.00, 0,  
			        0.00, 0 
				        FROM (
				        	SELECT *
				        	FROM nd_penjualan_detail
				        ) nd_penjualan_detail
				        LEFT JOIN (
				        	SELECT *
				        	FROM nd_penjualan
				        	WHERE tanggal >= '$tanggal'
				        	AND tanggal <= '$tanggal_end'
				        	AND status_aktif = 1
				        	) nd_penjualan
				        ON nd_penjualan_detail.penjualan_id = nd_penjualan.id
				        LEFT JOIN (
				            SELECT sum(qty*if(jumlah_roll=0,1, jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, penjualan_detail_id
				            FROM nd_penjualan_qty_detail
				            GROUP BY penjualan_detail_id
				            ) nd_penjualan_qty_detail
				        ON nd_penjualan_qty_detail.penjualan_detail_id = nd_penjualan_detail.id
				        where nd_penjualan.id is not null
				        GROUP BY barang_id, warna_id
			    )UNION(
			        SELECT barang_id, warna_id, 
			        0.00, 0, 
			        0.00, 0, 
			        0.00, 0, 
			        0.00, 0, 
			        0.00, 0,
			        0.00 ,0, 
			        0.00, 0, 
			        0.00, 0, 
			        sum(qty), sum(jumlah_roll)
				        FROM (
				        	SELECT *
				        	FROM nd_pengeluaran_stok_lain_detail
				        ) nd_pengeluaran_stok_lain_detail
				        LEFT JOIN (
				        	SELECT *
				        	FROM nd_pengeluaran_stok_lain
				        	WHERE tanggal >= '$tanggal'
				        	AND tanggal <= '$tanggal_end'
				        	AND status_aktif = 1
				        	) nd_pengeluaran_stok_lain
				        ON nd_pengeluaran_stok_lain_detail.pengeluaran_stok_lain_id = nd_pengeluaran_stok_lain.id
				        LEFT JOIN (
				            SELECT sum(qty*if(jumlah_roll=0,1, jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, pengeluaran_stok_lain_detail_id
				            FROM nd_pengeluaran_stok_lain_qty_detail
				            GROUP BY pengeluaran_stok_lain_detail_id
				            ) nd_pengeluaran_stok_lain_qty_detail
				        ON nd_pengeluaran_stok_lain_qty_detail.pengeluaran_stok_lain_detail_id = nd_pengeluaran_stok_lain_detail.id
				        where nd_pengeluaran_stok_lain.id is not null
				        GROUP BY barang_id, warna_id
			    )UNION(
					SELECT barang_id, warna_id, 
					0.00, 0, 
					0.00 , 0 , 
					sum(qty) , sum(jumlah_roll),
					0.00, 0, 
					0.00, 0,
					0.00, 0, 
					0.00, 0,
					0.00, 0,
					0.00, 0
			        	FROM nd_mutasi_barang
			        	WHERE tanggal >= '$tanggal'
			        	AND tanggal <= '$tanggal_end'
			        	AND status_aktif = 1
				        GROUP BY barang_id, warna_id
				)UNION(
					SELECT barang_id, warna_id, 
					0.00, 0, 
					0.00, 0, 
					0.00, 0, 
					0.00, 0,
					sum(qty) as qty_mutasi_masuk , sum(jumlah_roll) as jumlah_roll_mutasi_masuk,
					0.00, 0,
					0.00, 0, 
					0.00, 0, 
					0.00, 0 
			        	FROM nd_mutasi_barang
			        	WHERE tanggal >= '$tanggal'
			        	AND tanggal <= '$tanggal_end'
			        	AND status_aktif = 1
				        GROUP BY barang_id, warna_id
				)UNION(
					SELECT barang_id, warna_id, 
					0.00, 0, 
					0.00, 0, 
					0.00, 0,
					0.00, 0, 
					0.00, 0, 
					sum(qty_masuk) - sum(qty_keluar) AS qty_penyesuaian, sum(jumlah_roll_masuk) - sum(jumlah_roll_keluar) as jumlah_roll_penyesuaian, 
					0.00, 0, 
					0.00, 0, 
					0.00, 0
						FROM (
							(
						        SELECT  barang_id, warna_id, gudang_id, sum(qty) as qty_masuk, sum(jumlah_roll) as jumlah_roll_masuk, CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar
					        	FROM nd_penyesuaian_stok
					        	WHERE tanggal >= '$tanggal'
					        	AND tanggal <= '$tanggal_end'
					        	AND tipe_transaksi = 1
					        	GROUP BY barang_id, warna_id
						    )UNION(
						        SELECT barang_id, warna_id, gudang_id, CAST(0 as DECIMAL(15,2)) as qty_masuk, 0 as jumlah_roll_masuk, sum(qty) as qty_keluar, sum(jumlah_roll) as jumlah_roll_keluar
					        	FROM nd_penyesuaian_stok
					        	WHERE tanggal >= '$tanggal'
					        	AND tanggal <= '$tanggal_end'
					        	AND tipe_transaksi = 2
								GROUP BY barang_id, warna_id
							    )
							)a
				        GROUP BY barang_id, warna_id
				)UNION(
			    	SELECT barang_id, warna_id, 
			    	0.00, 0, 
			    	0.00, 0, 
			    	0.00, 0,
			    	0.00, 0, 
			    	0.00, 0,
			    	0.00, 0, 
			    	qty as qty_retur, jumlah_roll as jumlah_roll_retur, 
			    	0.00, 0,
			    	0.00, 0
			        FROM (
			        	SELECT *
			        	FROM nd_retur_jual_detail
			        ) nd_retur_jual_detail
			        LEFT JOIN (
			        	SELECT *
			        	FROM nd_retur_jual
			        	WHERE tanggal >= '$tanggal'
			        	AND tanggal <= '$tanggal_end'
			        	AND status_aktif = 1
			        	) nd_retur_jual
			        ON nd_retur_jual_detail.retur_jual_id = nd_retur_jual.id
			        LEFT JOIN (
			            SELECT sum(qty*if(jumlah_roll=0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, retur_jual_detail_id
			            FROM nd_retur_jual_qty
			            GROUP BY retur_jual_detail_id
			            ) nd_penjualan_qty_detail
			        ON nd_penjualan_qty_detail.retur_jual_detail_id = nd_retur_jual_detail.id
			        WHERE nd_retur_jual.id is not null
			        GROUP BY barang_id, warna_id
			    )UNION(
			    	SELECT barang_id, warna_id, 
			    	0.00, 0, 
			    	0.00, 0, 
			    	0.00, 0,
			    	0.00, 0, 
			    	0.00, 0,
			    	0.00, 0, 
			    	0.00, 0,
			    	qty as qty_retur_beli, jumlah_roll as jumlah_roll_retur_beli, 
			    	0.00, 0
			        FROM (
			        	SELECT *
			        	FROM nd_retur_beli_detail
			        ) t1
			        LEFT JOIN (
			        	SELECT *
			        	FROM nd_retur_beli
			        	WHERE tanggal >= '$tanggal'
			        	AND tanggal <= '$tanggal_end'
			        	AND status_aktif = 1
			        	) t2
			        ON t1.retur_beli_id = t2.id
			        LEFT JOIN (
			            SELECT sum(qty*if(jumlah_roll=0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, retur_beli_detail_id
			            FROM nd_retur_beli_qty
			            GROUP BY retur_beli_detail_id
			        ) t3
			        ON t3.retur_beli_detail_id = t1.id
			        WHERE t2.id is not null
			        GROUP BY barang_id, warna_id
			    )
			)t1
			LEFT JOIN (
				SELECT barang_id, warna_id, harga as hpp
				FROM (
					SELECT *, YEAR(tanggal) as tahun
					FROM nd_tutup_buku
					WHERE id = $tutup_buku_id
					) t1
				LEFT JOIN (
					SELECT barang_id, warna_id, YEAR(tahun) as tahun, $kolom_harga as harga
					FROM nd_tutup_buku_detail
					) t2
				ON t1.tahun = t2.tahun
			) t2
			ON t1.barang_id = t2.barang_id
			AND t1.warna_id = t2.warna_id
			LEFT JOIN (
				SELECT barang_id, warna_id,sum(qty*ROUND(harga_beli/(1 + (ppn_berlaku/100) ),2))/sum(qty) as hpp_beli 
				FROM (
					SELECT *,
					(SELECT ppn FROM nd_ppn tX WHERE tX.tanggal <= nd_pembelian.tanggal ORDER BY tX.tanggal DESC LIMIT 1 ) as ppn_berlaku
					FROM nd_pembelian
					WHERE tanggal >= '$tanggal'
					AND tanggal <= '$tanggal_end'
					AND status_aktif = 1
					) nd_pembelian
				LEFT JOIN nd_pembelian_detail
				ON nd_pembelian.id = nd_pembelian_detail.pembelian_id
				WHERE barang_id is not null
				GROUP BY YEAR(tanggal) , MONTH(tanggal), barang_id, warna_id
			)t5
			ON t1.barang_id = t5.barang_id
			AND t1.warna_id = t5.warna_id 
			LEFT JOIN (
				SELECT barang_id, warna_id,sum(qty*harga_jual) as hpp_jual
				FROM (
		        	SELECT *
		        	FROM nd_penjualan_detail
		        ) nd_penjualan_detail
		        LEFT JOIN (
		        	SELECT *
		        	FROM nd_penjualan
		        	WHERE tanggal >= '$tanggal'
		        	AND tanggal <= '$tanggal_end'
		        	AND status_aktif = 1
		        	) nd_penjualan
		        ON nd_penjualan_detail.penjualan_id = nd_penjualan.id
		        LEFT JOIN (
		            SELECT sum(qty*jumlah_roll) as qty, sum(jumlah_roll) as jumlah_roll, penjualan_detail_id
		            FROM nd_penjualan_qty_detail
		            GROUP BY penjualan_detail_id
		            ) nd_penjualan_qty_detail
		        ON nd_penjualan_qty_detail.penjualan_detail_id = nd_penjualan_detail.id
		        where nd_penjualan.id is not null
		        GROUP BY barang_id, warna_id
			)t6
			ON t1.barang_id = t6.barang_id
			AND t1.warna_id = t6.warna_id 
			LEFT JOIN nd_barang as t3
			ON t1.barang_id = t3.id
			LEFT JOIN nd_warna as t4
			ON t1.warna_id = t4.id
			GROUP BY barang_id, warna_id

			", false);

		return $query->result();
	}

	function mutasi_persediaan_barang_global_2($tanggal_awal, $tanggal,$tanggal_end, $stok_opname_id, $tutup_buku_id, $kolom_harga){

		$query = $this->db->query("SELECT t1.barang_id, t1.warna_id, 
			sum(qty_stock) as qty_stock, sum(jumlah_roll_stock) as jumlah_roll_stock, 
			sum(qty_beli) as qty_beli, sum(jumlah_roll_beli) as jumlah_roll_beli, 
			sum(qty_mutasi) as qty_mutasi, sum(jumlah_roll_mutasi) as jumlah_roll_mutasi, 
			sum(qty_jual) as qty_jual, sum(jumlah_roll_jual) as jumlah_roll_jual, 
			sum(qty_mutasi_masuk) as qty_mutasi_masuk, 
			sum(jumlah_roll_mutasi_masuk) as jumlah_roll_mutasi_masuk, 
			sum(qty_penyesuaian) as qty_penyesuaian, sum(jumlah_roll_penyesuaian) as jumlah_roll_penyesuaian, 
			sum(qty_retur) as qty_retur, sum(jumlah_roll_retur) as jumlah_roll_retur,
			sum(qty_retur_beli) as qty_retur_beli, sum(jumlah_roll_retur_beli) as jumlah_roll_retur_beli,
			sum(qty_lain) as qty_lain, sum(jumlah_roll_lain) as jumlah_roll_lain,
			hpp, hpp_beli, hpp_jual, t3.nama as nama_barang, nama_jual, warna_jual, t3.satuan_id, tipe_qty
			FROM (
				(
					SELECT barang_id, warna_id , 
						sum(ifnull(qty_masuk,0)) - sum(ifnull(qty_keluar,0)) as qty_stock, 
						sum(ifnull(jumlah_roll_masuk,0)) -sum(ifnull(jumlah_roll_keluar,0)) as jumlah_roll_stock, 
						0.00 as qty_beli, 0 as jumlah_roll_beli, 
						0.00 as qty_mutasi, 0 as jumlah_roll_mutasi, 
						0.00 as qty_jual, 0 as jumlah_roll_jual, 
						0.00 as qty_mutasi_masuk, 0 as jumlah_roll_mutasi_masuk, 
						0.00 as qty_penyesuaian, 0 as jumlah_roll_penyesuaian, 
						0.00 as qty_retur, 0 as jumlah_roll_retur, 
						0.00 as qty_retur_beli, 0 as jumlah_roll_retur_beli, 
						0.00 as qty_lain, 0 as jumlah_roll_lain 
					FROM (
						(
					        SELECT barang_id, warna_id, t2.gudang_id, sum(qty) as qty_masuk, sum(jumlah_roll) as jumlah_roll_masuk, CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar, 1 as tipe, t1.id
					        FROM (
					        	SELECT qty, jumlah_roll, id, barang_id, warna_id, pembelian_id
					        	FROM nd_pembelian_detail
					        	ORDER BY pembelian_id
					        ) t1
					        LEFT JOIN (
					        	SELECT *
					        	FROM nd_pembelian
					        	WHERE created_at < '$tanggal'
					        	AND created_at >= '$tanggal_awal'
					        	AND status_aktif = 1
					        	) t2
					        ON t1.pembelian_id = t2.id
					        WHERE t2.id is not null
					        GROUP BY barang_id, warna_id
					    )UNION(
					    	SELECT barang_id, warna_id, gudang_id_after, sum(qty) as qty_masuk, sum(jumlah_roll) as jumlah_roll_masuk, CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar, 2 as tipe, t1.id
				        	FROM nd_mutasi_barang t1
				        	WHERE created_at < '$tanggal'
				        	AND created_at >= '$tanggal_awal'
				        	AND status_aktif = 1
					        GROUP BY barang_id, warna_id
					    )UNION(
					        SELECT barang_id, warna_id, t1.gudang_id, CAST(0 as DECIMAL(15,2)) as qty_masuk, 0 as jumlah_roll_masuk, sum(qty) as qty_keluar, sum(jumlah_roll) as jumlah_roll_keluar, 3 as tipe, t1.id
					        FROM (
					        	SELECT *
					        	FROM nd_penjualan_detail
					        ) t1
					        LEFT JOIN (
					        	SELECT *
					        	FROM nd_penjualan
					        	WHERE closed_date < '$tanggal'
					        	AND closed_date >= '$tanggal_awal'
					        	AND status_aktif = 1
					        	) t2
					        ON t1.penjualan_id = t2.id
					        LEFT JOIN (
					            SELECT sum(qty*if(jumlah_roll=0,1, jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, penjualan_detail_id
					            FROM nd_penjualan_qty_detail
					            GROUP BY penjualan_detail_id
					            ) t3
					        ON t3.penjualan_detail_id = t1.id
					        where t2.id is not null
					        GROUP BY barang_id, warna_id
					    )UNION(
					        SELECT barang_id, warna_id, t1.gudang_id, CAST(0 as DECIMAL(15,2)) as qty_masuk, 0 as jumlah_roll_masuk, sum(qty) as qty_keluar, sum(jumlah_roll) as jumlah_roll_keluar, 20 as tipe, t1.id
					        FROM (
					        	SELECT *
					        	FROM nd_pengeluaran_stok_lain_detail
					        ) t1
					        LEFT JOIN (
					        	SELECT *
					        	FROM nd_pengeluaran_stok_lain
					        	WHERE created_at < '$tanggal'
					        	AND created_at >= '$tanggal_awal'
					        	AND status_aktif = 1
					        	) t2
					        ON t1.pengeluaran_stok_lain_id = t2.id
					        LEFT JOIN (
					            SELECT sum(qty*if(jumlah_roll=0,1, jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, pengeluaran_stok_lain_detail_id
					            FROM nd_pengeluaran_stok_lain_qty_detail
					            GROUP BY pengeluaran_stok_lain_detail_id
					            ) t3
					        ON t3.pengeluaran_stok_lain_detail_id = t1.id
					        where t2.id is not null
					        GROUP BY barang_id, warna_id
					    )UNION(
					    	SELECT barang_id, warna_id, t1.gudang_id, sum(qty) as qty_masuk, sum(jumlah_roll) as jumlah_roll_masuk, CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar, 4 as tipe, t1.id
					        FROM (
					        	SELECT *
					        	FROM nd_retur_jual_detail
					        ) t1
					        LEFT JOIN (
					        	SELECT *
					        	FROM nd_retur_jual
					        	WHERE created_at < '$tanggal'
					        	AND created_at >= '$tanggal_awal'
					        	AND status_aktif = 1
					        	) t2
					        ON t1.retur_jual_id = t2.id
					        LEFT JOIN (
					            SELECT sum(qty*if(jumlah_roll=0,1, jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, retur_jual_detail_id
					            FROM nd_retur_jual_qty
					            GROUP BY retur_jual_detail_id
					            ) t3
					        ON t3.retur_jual_detail_id = t1.id
					        WHERE t2.id is not null
					        GROUP BY barang_id, warna_id
					    )UNION(
					        SELECT barang_id, warna_id, gudang_id, sum(qty) as qty_masuk, sum(jumlah_roll) as jumlah_roll_masuk, CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar, 5 as tipe, t1.id
					        	FROM nd_penyesuaian_stok t1
					        	WHERE tipe_transaksi = 0
					        	AND created_at < '$tanggal'
					        	AND created_at >= '$tanggal_awal'
		                        GROUP BY barang_id, warna_id
					    )UNION(
					        SELECT  barang_id, warna_id, gudang_id, sum(qty) as qty_masuk, sum(jumlah_roll) as jumlah_roll_masuk, CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar, 6 as tipe, t1.id
				        	FROM nd_penyesuaian_stok t1
				        	WHERE created_at < '$tanggal'
				        	AND created_at >= '$tanggal_awal'
				        	AND tipe_transaksi = 1
				        	GROUP BY barang_id, warna_id
					    )UNION(
					        SELECT barang_id, warna_id, gudang_id, CAST(0 as DECIMAL(15,2)) as qty_masuk, 0 as jumlah_roll_masuk, sum(qty) as qty_keluar, sum(jumlah_roll) , 7 as tipe, t1.id
				        	FROM nd_penyesuaian_stok t1
				        	WHERE created_at < '$tanggal'
				        	AND created_at >= '$tanggal_awal'
				        	AND tipe_transaksi = 2
							GROUP BY barang_id, warna_id
					    )UNION(
					    	SELECT barang_id, warna_id, gudang_id_before, CAST(0 as DECIMAL(15,2)) as qty_masuk, 0 as jumlah_roll_masuk, sum(qty) as qty_keluar, sum(jumlah_roll) as jumlah_roll_keluar, 8 as tipe, t1.id
				        	FROM nd_mutasi_barang t1
				        	WHERE created_at < '$tanggal'
				        	AND created_at >= '$tanggal_awal'
				        	AND status_aktif = 1
							GROUP BY barang_id, warna_id
					    )UNION(
					    	SELECT barang_id, warna_id, gudang_id, sum(qty*jumlah_roll), sum(jumlah_roll) as jumlah_roll_masuk, 0 as qty_keluar, 0 as jumlah_roll_keluar, 9 as tipe, t1.id
				        	FROM nd_stok_opname_detail t1
				        	WHERE stok_opname_id = 1
			        		AND warna_id > 0
				        	GROUP BY barang_id, warna_id
					    )
					) a
					GROUP BY barang_id, warna_id
				)UNION(
			        SELECT barang_id, warna_id, 
			        0.00 as qty_stock, 0 as jumlah_roll_stock, 
			        0.00 as qty_beli, 0 as jumlah_roll_beli, 
					0.00 as qty_mutasi, 0 as jumlah_roll_mutasi, 
					0.00 as qty_jual, 0 as jumlah_roll_jual, 
					0.00 as qty_mutasi_masuk, 0 as jumlah_roll_mutasi_masuk, 
					0.00 as qty_penyesuaian, 0 as jumlah_roll_penyesuaian, 
					0.00 as qty_retur, 0 as jumlah_roll_retur, 
					0.00 as qty_retur_beli, 0 as jumlah_roll_retur_beli, 
			        sum(qty), sum(jumlah_roll)
				        FROM (
				        	SELECT *
				        	FROM nd_pengeluaran_stok_lain_detail
				        ) nd_pengeluaran_stok_lain_detail
				        LEFT JOIN (
				        	SELECT *
				        	FROM nd_pengeluaran_stok_lain
				        	WHERE created_at >= '$tanggal'
				        	AND created_at <= '$tanggal_end'
				        	AND status_aktif = 1
				        	) nd_pengeluaran_stok_lain
				        ON nd_pengeluaran_stok_lain_detail.pengeluaran_stok_lain_id = nd_pengeluaran_stok_lain.id
				        LEFT JOIN (
				            SELECT sum(qty*if(jumlah_roll=0,1, jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, pengeluaran_stok_lain_detail_id
				            FROM nd_pengeluaran_stok_lain_qty_detail
				            GROUP BY pengeluaran_stok_lain_detail_id
				            ) nd_pengeluaran_stok_lain_qty_detail
				        ON nd_pengeluaran_stok_lain_qty_detail.pengeluaran_stok_lain_detail_id = nd_pengeluaran_stok_lain_detail.id
				        where nd_pengeluaran_stok_lain.id is not null
				        GROUP BY barang_id, warna_id
			    )UNION(
					SELECT barang_id, warna_id, 
					0.00, 0, 
					sum(qty) , sum(jumlah_roll),
					0.00 , 0 , 
					0.00, 0, 
					0.00, 0,
					0.00, 0, 
					0.00, 0,
					0.00, 0,
					0.00, 0
			        	FROM (
                            SELECT *
                            FROM nd_pembelian
                            WHERE created_at >= '$tanggal'
                            AND created_at <= '$tanggal_end'
                            AND status_aktif = 1
                        )t1
                        LEFT JOIN (
                            SELECT sum(qty) as qty, sum(jumlah_roll) as jumlah_roll, pembelian_id, barang_id, warna_id
                            FROM nd_pembelian_detail
                            GROUP BY pembelian_id, barang_id, warna_id
                        )t2
                        ON t2.pembelian_id = t1.id
				        GROUP BY barang_id, warna_id
				)UNION(
					SELECT barang_id, warna_id, 
					0.00, 0, 
					0.00 , 0 , 
					0.00, 0, 
					sum(qty) , sum(jumlah_roll),
					0.00, 0,
					0.00, 0, 
					0.00, 0,
					0.00, 0,
					0.00, 0
			        	FROM (
                            SELECT *
                            FROM nd_penjualan
                            WHERE closed_date >= '$tanggal 00:00:00'
                            AND closed_date <= '$tanggal_end 23:59:59'
                            AND status_aktif = 1
                        )t1
                        LEFT JOIN (
                            SELECT subqty as qty, subjumlah_roll as jumlah_roll, penjualan_id, barang_id, warna_id
                            FROM nd_penjualan_detail
                        )t2
                        ON t2.penjualan_id = t1.id
				        GROUP BY barang_id, warna_id
				)UNION(
					SELECT barang_id, warna_id, 
					0.00, 0, 
					0.00 , 0 , 
					sum(qty) , sum(jumlah_roll),
					0.00, 0, 
					0.00, 0,
					0.00, 0, 
					0.00, 0,
					0.00, 0,
					0.00, 0
			        	FROM nd_mutasi_barang
			        	WHERE created_at >= '$tanggal'
			        	AND created_at <= '$tanggal_end'
			        	AND status_aktif = 1
				        GROUP BY barang_id, warna_id
				)UNION(
					SELECT barang_id, warna_id, 
					0.00, 0, 
					0.00, 0, 
					0.00, 0, 
					0.00, 0,
					sum(qty) as qty_mutasi_masuk , sum(jumlah_roll) as jumlah_roll_mutasi_masuk,
					0.00, 0,
					0.00, 0, 
					0.00, 0, 
					0.00, 0 
			        	FROM nd_mutasi_barang
			        	WHERE created_at >= '$tanggal'
			        	AND created_at <= '$tanggal_end'
			        	AND status_aktif = 1
				        GROUP BY barang_id, warna_id
				)UNION(
					SELECT barang_id, warna_id, 
					0.00, 0, 
					0.00, 0, 
					0.00, 0,
					0.00, 0, 
					0.00, 0, 
					sum(qty_masuk) - sum(qty_keluar) AS qty_penyesuaian, sum(jumlah_roll_masuk) - sum(jumlah_roll_keluar) as jumlah_roll_penyesuaian, 
					0.00, 0, 
					0.00, 0, 
					0.00, 0
						FROM (
							(
						        SELECT  barang_id, warna_id, gudang_id, sum(qty) as qty_masuk, sum(jumlah_roll) as jumlah_roll_masuk, CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar
					        	FROM nd_penyesuaian_stok
					        	WHERE created_at >= '$tanggal'
					        	AND created_at <= '$tanggal_end'
					        	AND tipe_transaksi = 1
					        	GROUP BY barang_id, warna_id
						    )UNION(
						        SELECT barang_id, warna_id, gudang_id, CAST(0 as DECIMAL(15,2)) as qty_masuk, 0 as jumlah_roll_masuk, sum(qty) as qty_keluar, sum(jumlah_roll) as jumlah_roll_keluar
					        	FROM nd_penyesuaian_stok
					        	WHERE created_at >= '$tanggal'
					        	AND created_at <= '$tanggal_end'
					        	AND tipe_transaksi = 2
								GROUP BY barang_id, warna_id
							    )
							)a
				        GROUP BY barang_id, warna_id
				)UNION(
			    	SELECT barang_id, warna_id, 
			    	0.00, 0, 
			    	0.00, 0, 
			    	0.00, 0,
			    	0.00, 0, 
			    	0.00, 0,
			    	0.00, 0, 
			    	qty as qty_retur, jumlah_roll as jumlah_roll_retur, 
			    	0.00, 0,
			    	0.00, 0
			        FROM (
			        	SELECT *
			        	FROM nd_retur_jual_detail
			        ) nd_retur_jual_detail
			        LEFT JOIN (
			        	SELECT *
			        	FROM nd_retur_jual
			        	WHERE created_at >= '$tanggal'
			        	AND created_at <= '$tanggal_end'
			        	AND status_aktif = 1
			        	) nd_retur_jual
			        ON nd_retur_jual_detail.retur_jual_id = nd_retur_jual.id
			        LEFT JOIN (
			            SELECT sum(qty*if(jumlah_roll=0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, retur_jual_detail_id
			            FROM nd_retur_jual_qty
			            GROUP BY retur_jual_detail_id
			            ) nd_penjualan_qty_detail
			        ON nd_penjualan_qty_detail.retur_jual_detail_id = nd_retur_jual_detail.id
			        WHERE nd_retur_jual.id is not null
			        GROUP BY barang_id, warna_id
			    )UNION(
			    	SELECT barang_id, warna_id, 
			    	0.00, 0, 
			    	0.00, 0, 
			    	0.00, 0,
			    	0.00, 0, 
			    	0.00, 0,
			    	0.00, 0, 
			    	0.00, 0,
			    	qty as qty_retur_beli, jumlah_roll as jumlah_roll_retur_beli, 
			    	0.00, 0
			        FROM (
			        	SELECT *
			        	FROM nd_retur_beli_detail
			        ) t1
			        LEFT JOIN (
			        	SELECT *
			        	FROM nd_retur_beli
			        	WHERE created_at >= '$tanggal'
			        	AND created_at <= '$tanggal_end'
			        	AND status_aktif = 1
			        	) t2
			        ON t1.retur_beli_id = t2.id
			        LEFT JOIN (
			            SELECT sum(qty*if(jumlah_roll=0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, retur_beli_detail_id
			            FROM nd_retur_beli_qty
			            GROUP BY retur_beli_detail_id
			        ) t3
			        ON t3.retur_beli_detail_id = t1.id
			        WHERE t2.id is not null
			        GROUP BY barang_id, warna_id
			    )
			)t1
			LEFT JOIN (
				SELECT barang_id, warna_id, harga as hpp
				FROM (
					SELECT *, YEAR(tanggal) as tahun
					FROM nd_tutup_buku
					WHERE id = $tutup_buku_id
					) t1
				LEFT JOIN (
					SELECT barang_id, warna_id, YEAR(tahun) as tahun, $kolom_harga as harga
					FROM nd_tutup_buku_detail
					) t2
				ON t1.tahun = t2.tahun
			) t2
			ON t1.barang_id = t2.barang_id
			AND t1.warna_id = t2.warna_id
			LEFT JOIN (
				SELECT barang_id, warna_id,sum(qty*ROUND(harga_beli/(1+(ppn_berlaku/100)),2))/sum(qty) as hpp_beli 
				FROM (
					SELECT *,
					(SELECT ppn FROM nd_ppn tX WHERE tX.tanggal <= nd_pembelian.tanggal ORDER BY tX.tanggal DESC LIMIT 1 ) as ppn_berlaku
					FROM nd_pembelian
					WHERE created_at >= '$tanggal'
					AND created_at <= '$tanggal_end'
					AND status_aktif = 1
					) nd_pembelian
				LEFT JOIN nd_pembelian_detail
				ON nd_pembelian.id = nd_pembelian_detail.pembelian_id
				WHERE barang_id is not null
				GROUP BY YEAR(tanggal) , MONTH(tanggal), barang_id, warna_id
			)t5
			ON t1.barang_id = t5.barang_id
			AND t1.warna_id = t5.warna_id 
			LEFT JOIN (
				SELECT barang_id, warna_id,sum(qty*harga_jual) as hpp_jual
				FROM (
		        	SELECT *
		        	FROM nd_penjualan_detail
		        ) nd_penjualan_detail
		        LEFT JOIN (
		        	SELECT *
		        	FROM nd_penjualan
		        	WHERE closed_date >= '$tanggal'
		        	AND closed_date <= '$tanggal_end'
		        	AND status_aktif = 1
		        	) nd_penjualan
		        ON nd_penjualan_detail.penjualan_id = nd_penjualan.id
		        LEFT JOIN (
		            SELECT sum(qty*jumlah_roll) as qty, sum(jumlah_roll) as jumlah_roll, penjualan_detail_id
		            FROM nd_penjualan_qty_detail
		            GROUP BY penjualan_detail_id
		            ) nd_penjualan_qty_detail
		        ON nd_penjualan_qty_detail.penjualan_detail_id = nd_penjualan_detail.id
		        where nd_penjualan.id is not null
		        GROUP BY barang_id, warna_id
			)t6
			ON t1.barang_id = t6.barang_id
			AND t1.warna_id = t6.warna_id 
			LEFT JOIN nd_barang as t3
			ON t1.barang_id = t3.id
			LEFT JOIN nd_warna as t4
			ON t1.warna_id = t4.id
			GROUP BY barang_id, warna_id
			ORDER BY barang_id, warna_id ASC

			", false);

		return $query->result();
	}

	function mutasi_persediaan_barang_global_3_legacy($tanggal_awal, $tanggal,$tanggal_end, $stok_opname_id, $tutup_buku_id, $kolom_harga, $kolom_qty, $kolom_roll){

		$tanggal = $tanggal.' 00:00:00';
		$tanggal_end = $tanggal_end.' 23:59:59';
		$query = $this->db->query("SELECT t1.barang_id, t1.warna_id, 
			sum(qty_stock) as qty_stock, sum(jumlah_roll_stock) as jumlah_roll_stock, 
			sum(qty_beli) as qty_beli, sum(jumlah_roll_beli) as jumlah_roll_beli, 
			sum(qty_mutasi) as qty_mutasi, sum(jumlah_roll_mutasi) as jumlah_roll_mutasi, 
			sum(qty_jual) as qty_jual, sum(jumlah_roll_jual) as jumlah_roll_jual, 
			sum(qty_mutasi_masuk) as qty_mutasi_masuk, 
			sum(jumlah_roll_mutasi_masuk) as jumlah_roll_mutasi_masuk, 
			sum(qty_penyesuaian) as qty_penyesuaian, sum(jumlah_roll_penyesuaian) as jumlah_roll_penyesuaian, 
			sum(qty_retur) as qty_retur, sum(jumlah_roll_retur) as jumlah_roll_retur,
			sum(qty_retur_beli) as qty_retur_beli, sum(jumlah_roll_retur_beli) as jumlah_roll_retur_beli,
			sum(qty_lain) as qty_lain, sum(jumlah_roll_lain) as jumlah_roll_lain,
			hpp, hpp_beli, hpp_jual, t3.nama as nama_barang, nama_jual, warna_jual, t3.satuan_id, tipe_qty
			FROM (
				(
					SELECT barang_id, warna_id , 
						qty as qty_stock, 
						roll as jumlah_roll_stock, 
						0.00 as qty_beli, 0 as jumlah_roll_beli, 
						0.00 as qty_mutasi, 0 as jumlah_roll_mutasi, 
						0.00 as qty_jual, 0 as jumlah_roll_jual, 
						0.00 as qty_mutasi_masuk, 0 as jumlah_roll_mutasi_masuk, 
						0.00 as qty_penyesuaian, 0 as jumlah_roll_penyesuaian, 
						0.00 as qty_retur, 0 as jumlah_roll_retur, 
						0.00 as qty_retur_beli, 0 as jumlah_roll_retur_beli, 
						0.00 as qty_lain, 0 as jumlah_roll_lain 
					FROM (
						SELECT barang_id, warna_id, qty, roll
						FROM (
							SELECT *, YEAR(tanggal) as tahun
							FROM nd_tutup_buku
							WHERE id = $tutup_buku_id
							) t1
						LEFT JOIN (
							SELECT barang_id, warna_id, YEAR(tahun) as tahun, $kolom_qty as qty, $kolom_roll as roll
							FROM nd_tutup_buku_detail
							) t2
						ON t1.tahun = t2.tahun
					) a
					GROUP BY barang_id, warna_id
				)UNION(
			        SELECT barang_id, warna_id, 
			        0.00 as qty_stock, 0 as jumlah_roll_stock, 
			        0.00 as qty_beli, 0 as jumlah_roll_beli, 
					0.00 as qty_mutasi, 0 as jumlah_roll_mutasi, 
					0.00 as qty_jual, 0 as jumlah_roll_jual, 
					0.00 as qty_mutasi_masuk, 0 as jumlah_roll_mutasi_masuk, 
					0.00 as qty_penyesuaian, 0 as jumlah_roll_penyesuaian, 
					0.00 as qty_retur, 0 as jumlah_roll_retur, 
					0.00 as qty_retur_beli, 0 as jumlah_roll_retur_beli, 
			        sum(qty), sum(jumlah_roll)
				        FROM (
				        	SELECT *
				        	FROM nd_pengeluaran_stok_lain_detail
				        ) nd_pengeluaran_stok_lain_detail
				        LEFT JOIN (
				        	SELECT *
				        	FROM nd_pengeluaran_stok_lain
				        	WHERE created_at >= '$tanggal'
				        	AND created_at <= '$tanggal_end'
				        	AND status_aktif = 1
				        	) nd_pengeluaran_stok_lain
				        ON nd_pengeluaran_stok_lain_detail.pengeluaran_stok_lain_id = nd_pengeluaran_stok_lain.id
				        LEFT JOIN (
				            SELECT sum(qty*if(jumlah_roll=0,1, jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, pengeluaran_stok_lain_detail_id
				            FROM nd_pengeluaran_stok_lain_qty_detail
				            GROUP BY pengeluaran_stok_lain_detail_id
				            ) nd_pengeluaran_stok_lain_qty_detail
				        ON nd_pengeluaran_stok_lain_qty_detail.pengeluaran_stok_lain_detail_id = nd_pengeluaran_stok_lain_detail.id
				        where nd_pengeluaran_stok_lain.id is not null
				        GROUP BY barang_id, warna_id
			    )UNION(
					SELECT barang_id, warna_id, 
					0.00, 0, 
					sum(qty) , sum(jumlah_roll),
					0.00 , 0 , 
					0.00, 0, 
					0.00, 0,
					0.00, 0, 
					0.00, 0,
					0.00, 0,
					0.00, 0
			        	FROM (
                            SELECT *
                            FROM nd_pembelian
                            WHERE created_at >= '$tanggal'
                            AND created_at <= '$tanggal_end'
                            AND status_aktif = 1
                        )t1
                        LEFT JOIN (
                            SELECT sum(qty) as qty, sum(jumlah_roll) as jumlah_roll, pembelian_id, barang_id, warna_id
                            FROM nd_pembelian_detail
                            GROUP BY pembelian_id, barang_id, warna_id
                        )t2
                        ON t2.pembelian_id = t1.id
				        GROUP BY barang_id, warna_id
				)UNION(
					SELECT barang_id, warna_id, 
					0.00, 0, 
					0.00 , 0 , 
					0.00, 0, 
					sum(qty) , sum(jumlah_roll),
					0.00, 0,
					0.00, 0, 
					0.00, 0,
					0.00, 0,
					0.00, 0
			        	FROM (
                            SELECT *
                            FROM nd_penjualan
                            WHERE closed_date >= '$tanggal'
                            AND closed_date <= '$tanggal_end'
                            AND status_aktif = 1
                        )t1
                        LEFT JOIN (
                            SELECT subqty as qty, subjumlah_roll as jumlah_roll, penjualan_id, barang_id, warna_id
                            FROM nd_penjualan_detail
                        )t2
                        ON t2.penjualan_id = t1.id
				        GROUP BY barang_id, warna_id
				)UNION(
					SELECT barang_id, warna_id, 
					0.00, 0, 
					0.00 , 0 , 
					sum(qty) , sum(jumlah_roll),
					0.00, 0, 
					0.00, 0,
					0.00, 0, 
					0.00, 0,
					0.00, 0,
					0.00, 0
			        	FROM nd_mutasi_barang
			        	WHERE created_at >= '$tanggal'
			        	AND created_at <= '$tanggal_end'
			        	AND status_aktif = 1
				        GROUP BY barang_id, warna_id
				)UNION(
					SELECT barang_id, warna_id, 
					0.00, 0, 
					0.00, 0, 
					0.00, 0, 
					0.00, 0,
					sum(qty) as qty_mutasi_masuk , sum(jumlah_roll) as jumlah_roll_mutasi_masuk,
					0.00, 0,
					0.00, 0, 
					0.00, 0, 
					0.00, 0 
			        	FROM nd_mutasi_barang
			        	WHERE created_at >= '$tanggal'
			        	AND created_at <= '$tanggal_end'
			        	AND status_aktif = 1
				        GROUP BY barang_id, warna_id
				)UNION(
					SELECT barang_id, warna_id, 
					0.00, 0, 
					0.00, 0, 
					0.00, 0,
					0.00, 0, 
					0.00, 0, 
					sum(qty_masuk) - sum(qty_keluar) AS qty_penyesuaian, sum(jumlah_roll_masuk) - sum(jumlah_roll_keluar) as jumlah_roll_penyesuaian, 
					0.00, 0, 
					0.00, 0, 
					0.00, 0
						FROM (
							(
						        SELECT  barang_id, warna_id, gudang_id, sum(qty) as qty_masuk, sum(jumlah_roll) as jumlah_roll_masuk, CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar, 1 as tipe
					        	FROM nd_penyesuaian_stok
					        	WHERE created_at >= '$tanggal'
					        	AND created_at <= '$tanggal_end'
					        	AND tipe_transaksi = 1
					        	GROUP BY barang_id, warna_id
						    )UNION(
						        SELECT barang_id, warna_id, gudang_id, CAST(0 as DECIMAL(15,2)) as qty_masuk, 0 as jumlah_roll_masuk, sum(qty) as qty_keluar, sum(jumlah_roll) as jumlah_roll_keluar, 2
					        	FROM nd_penyesuaian_stok
					        	WHERE created_at >= '$tanggal'
					        	AND created_at <= '$tanggal_end'
					        	AND tipe_transaksi = 2
								GROUP BY barang_id, warna_id
							)UNION(
						        SELECT  barang_id, warna_id, gudang_id, sum(qty) as qty_masuk, sum(jumlah_roll) as jumlah_roll_masuk, CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar, 3
					        	FROM nd_penyesuaian_stok
								WHERE created_at >= '$tanggal'
					        	AND created_at <= '$tanggal_end'
					        	AND tipe_transaksi = 4
					        	GROUP BY barang_id, warna_id
						    )
							)a
				        GROUP BY barang_id, warna_id
				)UNION(
			    	SELECT barang_id, warna_id, 
			    	0.00, 0, 
			    	0.00, 0, 
			    	0.00, 0,
			    	0.00, 0, 
			    	0.00, 0,
			    	0.00, 0, 
			    	qty as qty_retur, jumlah_roll as jumlah_roll_retur, 
			    	0.00, 0,
			    	0.00, 0
			        FROM (
			        	SELECT *
			        	FROM nd_retur_jual_detail
			        ) nd_retur_jual_detail
			        LEFT JOIN (
			        	SELECT *
			        	FROM nd_retur_jual
			        	WHERE created_at >= '$tanggal'
			        	AND created_at <= '$tanggal_end'
			        	AND status_aktif = 1
			        	) nd_retur_jual
			        ON nd_retur_jual_detail.retur_jual_id = nd_retur_jual.id
			        LEFT JOIN (
			            SELECT sum(qty*if(jumlah_roll=0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, retur_jual_detail_id
			            FROM nd_retur_jual_qty
			            GROUP BY retur_jual_detail_id
			            ) nd_penjualan_qty_detail
			        ON nd_penjualan_qty_detail.retur_jual_detail_id = nd_retur_jual_detail.id
			        WHERE nd_retur_jual.id is not null
			        GROUP BY barang_id, warna_id
			    )UNION(
			    	SELECT barang_id, warna_id, 
			    	0.00, 0, 
			    	0.00, 0, 
			    	0.00, 0,
			    	0.00, 0, 
			    	0.00, 0,
			    	0.00, 0, 
			    	0.00, 0,
			    	qty as qty_retur_beli, jumlah_roll as jumlah_roll_retur_beli, 
			    	0.00, 0
			        FROM (
			        	SELECT *
			        	FROM nd_retur_beli_detail
			        ) t1
			        LEFT JOIN (
			        	SELECT *
			        	FROM nd_retur_beli
			        	WHERE created_at >= '$tanggal'
			        	AND created_at <= '$tanggal_end'
			        	AND status_aktif = 1
			        	) t2
			        ON t1.retur_beli_id = t2.id
			        LEFT JOIN (
			            SELECT sum(qty*if(jumlah_roll=0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, retur_beli_detail_id
			            FROM nd_retur_beli_qty
			            GROUP BY retur_beli_detail_id
			        ) t3
			        ON t3.retur_beli_detail_id = t1.id
			        WHERE t2.id is not null
			        GROUP BY barang_id, warna_id
			    )
			)t1
			LEFT JOIN (
				SELECT barang_id, warna_id, harga as hpp
				FROM (
					SELECT *, YEAR(tanggal) as tahun
					FROM nd_tutup_buku
					WHERE id = $tutup_buku_id
					) t1
				LEFT JOIN (
					SELECT barang_id, warna_id, YEAR(tahun) as tahun, $kolom_harga as harga
					FROM nd_tutup_buku_detail
					) t2
				ON t1.tahun = t2.tahun
			) t2
			ON t1.barang_id = t2.barang_id
			AND t1.warna_id = t2.warna_id
			LEFT JOIN (
				SELECT barang_id, warna_id,sum(qty*ROUND(harga_beli/(1+(ppn_berlaku/100)),2))/sum(qty) as hpp_beli 
				FROM (
					SELECT *,
					(SELECT ppn FROM nd_ppn tX WHERE tX.tanggal <= nd_pembelian.tanggal ORDER BY tX.tanggal DESC LIMIT 1 ) as ppn_berlaku
					FROM nd_pembelian
					WHERE created_at >= '$tanggal'
					AND created_at <= '$tanggal_end'
					AND status_aktif = 1
					) nd_pembelian
				LEFT JOIN nd_pembelian_detail
				ON nd_pembelian.id = nd_pembelian_detail.pembelian_id
				WHERE barang_id is not null
				GROUP BY YEAR(created_at) , MONTH(created_at), barang_id, warna_id
			)t5
			ON t1.barang_id = t5.barang_id
			AND t1.warna_id = t5.warna_id 
			LEFT JOIN (
				SELECT barang_id, warna_id,sum(qty*harga_jual) as hpp_jual
				FROM (
		        	SELECT *
		        	FROM nd_penjualan_detail
		        ) nd_penjualan_detail
		        LEFT JOIN (
		        	SELECT *
		        	FROM nd_penjualan
		        	WHERE closed_date >= '$tanggal'
		        	AND closed_date <= '$tanggal_end'
		        	AND status_aktif = 1
		        	) nd_penjualan
		        ON nd_penjualan_detail.penjualan_id = nd_penjualan.id
		        LEFT JOIN (
		            SELECT sum(qty*jumlah_roll) as qty, sum(jumlah_roll) as jumlah_roll, penjualan_detail_id
		            FROM nd_penjualan_qty_detail
		            GROUP BY penjualan_detail_id
		            ) nd_penjualan_qty_detail
		        ON nd_penjualan_qty_detail.penjualan_detail_id = nd_penjualan_detail.id
		        where nd_penjualan.id is not null
		        GROUP BY barang_id, warna_id
			)t6
			ON t1.barang_id = t6.barang_id
			AND t1.warna_id = t6.warna_id 
			LEFT JOIN nd_barang as t3
			ON t1.barang_id = t3.id
			LEFT JOIN nd_warna as t4
			ON t1.warna_id = t4.id
			WHERE t1.barang_id is not null
			AND t1.warna_id is not null
			GROUP BY barang_id, warna_id
			ORDER BY barang_id, warna_id ASC

			", false);

		return $query->result();
	}

	function mutasi_persediaan_barang_global_3($tanggal_awal, $tanggal,$tanggal_end, $stok_opname_id, $tutup_buku_id, $kolom_harga, $kolom_qty, $kolom_roll){

		$tanggal = $tanggal.' 00:00:00';
		$tanggal_end = $tanggal_end.' 23:59:59';
		$query = $this->db->query("SELECT t1.barang_id, t1.warna_id, 
		sum(qty_stock) as qty_stock, sum(jumlah_roll_stock) as jumlah_roll_stock, 
		sum(qty_beli) as qty_beli, sum(jumlah_roll_beli) as jumlah_roll_beli, 
		sum(qty_mutasi) as qty_mutasi, sum(jumlah_roll_mutasi) as jumlah_roll_mutasi, 
		sum(qty_jual) as qty_jual, sum(jumlah_roll_jual) as jumlah_roll_jual, 
		sum(qty_mutasi_masuk) as qty_mutasi_masuk, 
		sum(jumlah_roll_mutasi_masuk) as jumlah_roll_mutasi_masuk, 
		sum(qty_penyesuaian) as qty_penyesuaian, sum(jumlah_roll_penyesuaian) as jumlah_roll_penyesuaian, 
		sum(qty_retur) as qty_retur, sum(jumlah_roll_retur) as jumlah_roll_retur,
		sum(qty_retur_beli) as qty_retur_beli, sum(jumlah_roll_retur_beli) as jumlah_roll_retur_beli,
		sum(qty_lain) as qty_lain, sum(jumlah_roll_lain) as jumlah_roll_lain,
		sum(qty_assembly) as qty_assembly, sum(jumlah_roll_assembly) as jumlah_roll_assembly,
		hpp, hpp_beli, hpp_jual, t3.nama as nama_barang, nama_jual, warna_jual, t3.satuan_id, tipe_qty
		FROM (
			(
				SELECT barang_id, warna_id , 
					qty as qty_stock, 
					roll as jumlah_roll_stock, 
					0.00 as qty_beli, 0 as jumlah_roll_beli, 
					0.00 as qty_mutasi, 0 as jumlah_roll_mutasi, 
					0.00 as qty_jual, 0 as jumlah_roll_jual, 
					0.00 as qty_mutasi_masuk, 0 as jumlah_roll_mutasi_masuk, 
					0.00 as qty_penyesuaian, 0 as jumlah_roll_penyesuaian, 
					0.00 as qty_retur, 0 as jumlah_roll_retur, 
					0.00 as qty_retur_beli, 0 as jumlah_roll_retur_beli, 
					0.00 as qty_lain, 0 as jumlah_roll_lain ,
					0.00 as qty_assembly, 0 as jumlah_roll_assembly
				FROM (
					SELECT barang_id, warna_id, qty, roll
					FROM (
						SELECT *, YEAR(tanggal) as tahun
						FROM nd_tutup_buku
						WHERE id = $tutup_buku_id
						) t1
					LEFT JOIN (
						SELECT barang_id, warna_id, YEAR(tahun) as tahun, $kolom_qty as qty, $kolom_roll as roll
						FROM nd_tutup_buku_detail
						) t2
					ON t1.tahun = t2.tahun
				) a
				GROUP BY barang_id, warna_id
			)UNION(
				SELECT barang_id, warna_id, 
				0.00 as qty_stock, 0 as jumlah_roll_stock, 
				0.00 as qty_beli, 0 as jumlah_roll_beli, 
				0.00 as qty_mutasi, 0 as jumlah_roll_mutasi, 
				0.00 as qty_jual, 0 as jumlah_roll_jual, 
				0.00 as qty_mutasi_masuk, 0 as jumlah_roll_mutasi_masuk, 
				0.00 as qty_penyesuaian, 0 as jumlah_roll_penyesuaian, 
				0.00 as qty_retur, 0 as jumlah_roll_retur, 
				0.00 as qty_retur_beli, 0 as jumlah_roll_retur_beli, 
				sum(qty), sum(jumlah_roll),
				0.00 as qty_assembly, 0 as jumlah_roll_assembly
					FROM (
						SELECT *
						FROM nd_pengeluaran_stok_lain_detail
					) nd_pengeluaran_stok_lain_detail
					LEFT JOIN (
						SELECT *
						FROM nd_pengeluaran_stok_lain
						WHERE created_at >= '$tanggal'
						AND created_at <= '$tanggal_end'
						AND status_aktif = 1
						) nd_pengeluaran_stok_lain
					ON nd_pengeluaran_stok_lain_detail.pengeluaran_stok_lain_id = nd_pengeluaran_stok_lain.id
					LEFT JOIN (
						SELECT sum(qty*if(jumlah_roll=0,1, jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, pengeluaran_stok_lain_detail_id
						FROM nd_pengeluaran_stok_lain_qty_detail
						GROUP BY pengeluaran_stok_lain_detail_id
						) nd_pengeluaran_stok_lain_qty_detail
					ON nd_pengeluaran_stok_lain_qty_detail.pengeluaran_stok_lain_detail_id = nd_pengeluaran_stok_lain_detail.id
					where nd_pengeluaran_stok_lain.id is not null
					GROUP BY barang_id, warna_id
			)UNION(
				SELECT barang_id, warna_id, 
				0.00, 0, 
				sum(qty) , sum(jumlah_roll),
				0.00 , 0 , 
				0.00, 0, 
				0.00, 0,
				0.00, 0, 
				0.00, 0,
				0.00, 0,
				0.00, 0,
				0.00, 0
					FROM (
						SELECT *
						FROM nd_pembelian
						WHERE created_at >= '$tanggal'
						AND created_at <= '$tanggal_end'
						AND status_aktif = 1
					)t1
					LEFT JOIN (
						SELECT sum(qty) as qty, sum(jumlah_roll) as jumlah_roll, pembelian_id, barang_id, warna_id
						FROM nd_pembelian_detail
						GROUP BY pembelian_id, barang_id, warna_id
					)t2
					ON t2.pembelian_id = t1.id
					GROUP BY barang_id, warna_id
			)UNION(
				SELECT barang_id, warna_id, 
				0.00, 0, 
				0.00 , 0 , 
				0.00, 0, 
				sum(qty) , sum(jumlah_roll),
				0.00, 0,
				0.00, 0, 
				0.00, 0,
				0.00, 0,
				0.00, 0,
				0.00, 0
					FROM (
						SELECT *
						FROM nd_penjualan
						WHERE closed_date >= '$tanggal'
						AND closed_date <= '$tanggal_end'
						AND status_aktif = 1
					)t1
					LEFT JOIN (
						SELECT subqty as qty, subjumlah_roll as jumlah_roll, penjualan_id, barang_id, warna_id
						FROM nd_penjualan_detail
					)t2
					ON t2.penjualan_id = t1.id
					GROUP BY barang_id, warna_id
			)UNION(
				SELECT barang_id, warna_id, 
				0.00, 0, 
				0.00 , 0 , 
				sum(qty) , sum(jumlah_roll),
				0.00, 0, 
				0.00, 0,
				0.00, 0, 
				0.00, 0,
				0.00, 0,
				0.00, 0,
				0.00, 0
					FROM nd_mutasi_barang
					WHERE created_at >= '$tanggal'
					AND created_at <= '$tanggal_end'
					AND status_aktif = 1
					GROUP BY barang_id, warna_id
			)UNION(
				SELECT barang_id, warna_id, 
				0.00, 0, 
				0.00, 0, 
				0.00, 0, 
				0.00, 0,
				sum(qty) as qty_mutasi_masuk , sum(jumlah_roll) as jumlah_roll_mutasi_masuk,
				0.00, 0,
				0.00, 0, 
				0.00, 0, 
				0.00, 0, 
				0.00, 0 
					FROM nd_mutasi_barang
					WHERE created_at >= '$tanggal'
					AND created_at <= '$tanggal_end'
					AND status_aktif = 1
					GROUP BY barang_id, warna_id
			)UNION(
				SELECT barang_id, warna_id, 
				0.00, 0, 
				0.00, 0, 
				0.00, 0,
				0.00, 0, 
				0.00, 0, 
				sum(qty_masuk) - sum(qty_keluar) AS qty_penyesuaian, sum(jumlah_roll_masuk) - sum(jumlah_roll_keluar) as jumlah_roll_penyesuaian, 
				0.00, 0, 
				0.00, 0, 
				0.00, 0, 
				0.00, 0
					FROM (
						(
							SELECT  barang_id, warna_id, gudang_id, sum(qty) as qty_masuk, sum(jumlah_roll) as jumlah_roll_masuk, CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar, 1 as tipe
							FROM nd_penyesuaian_stok
							WHERE created_at >= '$tanggal'
							AND created_at <= '$tanggal_end'
							AND tipe_transaksi = 1
							GROUP BY barang_id, warna_id
						)UNION(
							SELECT barang_id, warna_id, gudang_id, CAST(0 as DECIMAL(15,2)) as qty_masuk, 0 as jumlah_roll_masuk, sum(qty) as qty_keluar, sum(jumlah_roll) as jumlah_roll_keluar, 2
							FROM nd_penyesuaian_stok
							WHERE created_at >= '$tanggal'
							AND created_at <= '$tanggal_end'
							AND tipe_transaksi = 2
							GROUP BY barang_id, warna_id
						)UNION(
							SELECT  barang_id, warna_id, gudang_id, sum(qty) as qty_masuk, sum(jumlah_roll) as jumlah_roll_masuk, CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar, 3
							FROM nd_penyesuaian_stok
							WHERE created_at >= '$tanggal'
							AND created_at <= '$tanggal_end'
							AND tipe_transaksi = 4
							GROUP BY barang_id, warna_id
						)
					)a
					GROUP BY barang_id, warna_id
			)UNION(
				SELECT barang_id, warna_id, 
				0.00, 0, 
				0.00, 0, 
				0.00, 0,
				0.00, 0, 
				0.00, 0, 
				0.00, 0, 
				0.00, 0, 
				0.00, 0, 
				0.00, 0,
				sum(a.qty) - sum(a.qty) AS qty_assembly, sum(b.jumlah_roll) - sum(a.jumlah_roll) as jumlah_roll_assembly
					FROM (
							SELECT *
							FROM nd_penyesuaian_stok
							WHERE created_at >= '$tanggal'
							AND created_at <= '$tanggal_end'
							AND tipe_transaksi = 3
					)a
					LEFT JOIN (
						SELECT sum(jumlah_roll) as jumlah_roll, penyesuaian_stok_id
						FROM nd_penyesuaian_stok_split
						GROUP BY penyesuaian_stok_id
						)b
					ON a.id = b.penyesuaian_stok_id
					GROUP BY barang_id, warna_id
			)UNION(
				SELECT barang_id, warna_id, 
				0.00, 0, 
				0.00, 0, 
				0.00, 0,
				0.00, 0, 
				0.00, 0,
				0.00, 0, 
				qty as qty_retur, jumlah_roll as jumlah_roll_retur, 
				0.00, 0,
				0.00, 0,
				0.00, 0
				FROM (
					SELECT *
					FROM nd_retur_jual_detail
				) nd_retur_jual_detail
				LEFT JOIN (
					SELECT *
					FROM nd_retur_jual
					WHERE created_at >= '$tanggal'
					AND created_at <= '$tanggal_end'
					AND status_aktif = 1
					) nd_retur_jual
				ON nd_retur_jual_detail.retur_jual_id = nd_retur_jual.id
				LEFT JOIN (
					SELECT sum(qty*if(jumlah_roll=0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, retur_jual_detail_id
					FROM nd_retur_jual_qty
					GROUP BY retur_jual_detail_id
					) nd_penjualan_qty_detail
				ON nd_penjualan_qty_detail.retur_jual_detail_id = nd_retur_jual_detail.id
				WHERE nd_retur_jual.id is not null
				GROUP BY barang_id, warna_id
			)UNION(
				SELECT barang_id, warna_id, 
				0.00, 0, 
				0.00, 0, 
				0.00, 0,
				0.00, 0, 
				0.00, 0,
				0.00, 0, 
				0.00, 0,
				sum(qty) as qty_retur_beli, sum(jumlah_roll) as jumlah_roll_retur_beli, 
				0.00, 0,
				0.00, 0
				FROM (
					SELECT *
					FROM nd_retur_beli_detail
				) t1
				LEFT JOIN (
					SELECT *
					FROM nd_retur_beli
					WHERE created_at >= '$tanggal'
					AND created_at <= '$tanggal_end'
					AND status_aktif = 1
					) t2
				ON t1.retur_beli_id = t2.id
				LEFT JOIN (
					SELECT sum(qty*if(jumlah_roll=0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, retur_beli_detail_id
					FROM nd_retur_beli_qty
					GROUP BY retur_beli_detail_id
				) t3
				ON t3.retur_beli_detail_id = t1.id
				WHERE t2.id is not null
				GROUP BY barang_id, warna_id
			)
		)t1
		LEFT JOIN (
			SELECT barang_id, warna_id, harga as hpp
			FROM (
				SELECT *, YEAR(tanggal) as tahun
				FROM nd_tutup_buku
				WHERE id = $tutup_buku_id
				) t1
			LEFT JOIN (
				SELECT barang_id, warna_id, YEAR(tahun) as tahun, $kolom_harga as harga
				FROM nd_tutup_buku_detail
				) t2
			ON t1.tahun = t2.tahun
		) t2
		ON t1.barang_id = t2.barang_id
		AND t1.warna_id = t2.warna_id
		LEFT JOIN (
			SELECT barang_id, warna_id,sum(qty*ROUND(harga_beli/(1+(ppn_berlaku/100)),2))/sum(qty) as hpp_beli 
			FROM (
				SELECT *,
				(SELECT ppn FROM nd_ppn tX WHERE tX.tanggal <= nd_pembelian.tanggal ORDER BY tX.tanggal DESC LIMIT 1 ) as ppn_berlaku
				FROM nd_pembelian
				WHERE created_at >= '$tanggal'
				AND created_at <= '$tanggal_end'
				AND status_aktif = 1
				) nd_pembelian
			LEFT JOIN nd_pembelian_detail
			ON nd_pembelian.id = nd_pembelian_detail.pembelian_id
			WHERE barang_id is not null
			GROUP BY YEAR(created_at) , MONTH(created_at), barang_id, warna_id
		)t5
		ON t1.barang_id = t5.barang_id
		AND t1.warna_id = t5.warna_id 
		LEFT JOIN (
			SELECT barang_id, warna_id,sum(qty*harga_jual) as hpp_jual
			FROM (
				SELECT *
				FROM nd_penjualan_detail
			) nd_penjualan_detail
			LEFT JOIN (
				SELECT *
				FROM nd_penjualan
				WHERE closed_date >= '$tanggal'
				AND closed_date <= '$tanggal_end'
				AND status_aktif = 1
				) nd_penjualan
			ON nd_penjualan_detail.penjualan_id = nd_penjualan.id
			LEFT JOIN (
				SELECT sum(qty*jumlah_roll) as qty, sum(jumlah_roll) as jumlah_roll, penjualan_detail_id
				FROM nd_penjualan_qty_detail
				GROUP BY penjualan_detail_id
				) nd_penjualan_qty_detail
			ON nd_penjualan_qty_detail.penjualan_detail_id = nd_penjualan_detail.id
			where nd_penjualan.id is not null
			GROUP BY barang_id, warna_id
		)t6
		ON t1.barang_id = t6.barang_id
		AND t1.warna_id = t6.warna_id 
		LEFT JOIN nd_barang as t3
		ON t1.barang_id = t3.id
		LEFT JOIN nd_warna as t4
		ON t1.warna_id = t4.id
		WHERE t1.barang_id is not null
		AND t1.warna_id is not null
		GROUP BY barang_id, warna_id
		ORDER BY barang_id, warna_id ASC

			", false);

		return $query->result();
	}

	function mutasi_persediaan_barang_global_no_warna($tanggal_awal, $tanggal,$tanggal_end, $stok_opname_id, $tutup_buku_id, $kolom_harga){

		$query = $this->db->query("SELECT t1.barang_id, t1.warna_id, sum(qty_stock) as qty_stock, sum(jumlah_roll_stock) as jumlah_roll_stock, sum(qty_beli) as qty_beli, sum(jumlah_roll_beli) as jumlah_roll_beli, sum(qty_mutasi) as qty_mutasi, sum(jumlah_roll_mutasi) as jumlah_roll_mutasi, sum(qty_jual) as qty_jual, sum(jumlah_roll_jual) as jumlah_roll_jual, sum(qty_mutasi_masuk) as qty_mutasi_masuk, sum(jumlah_roll_mutasi_masuk) as jumlah_roll_mutasi_masuk, sum(qty_penyesuaian) as qty_penyesuaian, sum(jumlah_roll_penyesuaian) as jumlah_roll_penyesuaian, sum(qty_retur) as qty_retur, sum(jumlah_roll_retur) as jumlah_roll_retur ,hpp, hpp_beli, t3.nama as nama_barang, nama_jual, warna_jual, t3.satuan_id
			FROM (
				(
					SELECT barang_id, warna_id , sum(ifnull(qty_masuk,0)) - sum(ifnull(qty_keluar,0)) as qty_stock, sum(ifnull(jumlah_roll_masuk,0)) -sum(ifnull(jumlah_roll_keluar,0)) as jumlah_roll_stock, 0.00 as qty_beli, 0 as jumlah_roll_beli, 0.00 as qty_mutasi, 0 as jumlah_roll_mutasi, 0.00 as qty_jual, 0 as jumlah_roll_jual, 0.00 as qty_mutasi_masuk, 0 as jumlah_roll_mutasi_masuk, 0.00 as qty_penyesuaian, 0 as jumlah_roll_penyesuaian, 0 as jumlah_roll_retur, 0.00 as qty_retur
					FROM (
						(
					        SELECT barang_id, warna_id, t2.gudang_id, sum(qty) as qty_masuk, sum(jumlah_roll) as jumlah_roll_masuk, CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar, 1 as tipe, t1.id
					        FROM (
					        	SELECT qty, jumlah_roll, id, barang_id, warna_id, pembelian_id
					        	FROM nd_pembelian_detail
					        	ORDER BY pembelian_id
					        ) t1
					        LEFT JOIN (
					        	SELECT *
					        	FROM nd_pembelian
					        	WHERE tanggal < '$tanggal'
					        	AND tanggal >= '$tanggal_awal'
					        	AND status_aktif = 1
					        	) t2
					        ON t1.pembelian_id = t2.id
					        WHERE t2.id is not null
					        GROUP BY barang_id
					    )UNION(
					    	SELECT barang_id, warna_id, gudang_id_after, sum(qty) as qty_masuk, sum(jumlah_roll) as jumlah_roll_masuk, CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar, 2 as tipe, t1.id
				        	FROM nd_mutasi_barang t1
				        	WHERE tanggal < '$tanggal'
				        	AND tanggal >= '$tanggal_awal'
				        	AND status_aktif = 1
					        GROUP BY barang_id
					    )UNION(
					        SELECT barang_id, warna_id, t1.gudang_id, CAST(0 as DECIMAL(15,2)) as qty_masuk, 0 as jumlah_roll_masuk, sum(qty) as qty_keluar, sum(jumlah_roll) as jumlah_roll_keluar, 3 as tipe, t1.id
					        FROM (
					        	SELECT *
					        	FROM nd_penjualan_detail
					        ) t1
					        LEFT JOIN (
					        	SELECT *
					        	FROM nd_penjualan
					        	WHERE tanggal < '$tanggal'
					        	AND tanggal >= '$tanggal_awal'
					        	AND status_aktif = 1
					        	) t2
					        ON t1.penjualan_id = t2.id
					        LEFT JOIN (
					            SELECT sum(qty*if(jumlah_roll=0,1, jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, penjualan_detail_id
					            FROM nd_penjualan_qty_detail
					            GROUP BY penjualan_detail_id
					            ) t3
					        ON t3.penjualan_detail_id = t1.id
					        where t2.id is not null
					        GROUP BY barang_id
					    )UNION(
					        SELECT barang_id, warna_id, t1.gudang_id, CAST(0 as DECIMAL(15,2)) as qty_masuk, 0 as jumlah_roll_masuk, sum(qty) as qty_keluar, sum(jumlah_roll) as jumlah_roll_keluar, 20 as tipe, t1.id
					        FROM (
					        	SELECT *
					        	FROM nd_pengeluaran_stok_lain_detail
					        ) t1
					        LEFT JOIN (
					        	SELECT *
					        	FROM nd_pengeluaran_stok_lain
					        	WHERE tanggal < '$tanggal'
					        	AND tanggal >= '$tanggal_awal'
					        	AND status_aktif = 1
					        	) t2
					        ON t1.pengeluaran_stok_lain_id = t2.id
					        LEFT JOIN (
					            SELECT sum(qty*if(jumlah_roll=0,1, jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, pengeluaran_stok_lain_detail_id
					            FROM nd_pengeluaran_stok_lain_qty_detail
					            GROUP BY pengeluaran_stok_lain_detail_id
					            ) t3
					        ON t3.pengeluaran_stok_lain_detail_id = t1.id
					        where t2.id is not null
					        GROUP BY barang_id
					    )UNION(
					    	SELECT barang_id, warna_id, t1.gudang_id, sum(qty) as qty_masuk, sum(jumlah_roll) as jumlah_roll_masuk, CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar, 4 as tipe, t1.id
					        FROM (
					        	SELECT *
					        	FROM nd_retur_jual_detail
					        ) t1
					        LEFT JOIN (
					        	SELECT *
					        	FROM nd_retur_jual
					        	WHERE tanggal < '$tanggal'
					        	AND tanggal >= '$tanggal_awal'
					        	AND status_aktif = 1
					        	) t2
					        ON t1.retur_jual_id = t2.id
					        LEFT JOIN (
					            SELECT sum(qty*if(jumlah_roll=0,1, jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, retur_jual_detail_id
					            FROM nd_retur_jual_qty
					            GROUP BY retur_jual_detail_id
					            ) t3
					        ON t3.retur_jual_detail_id = t1.id
					        WHERE t2.id is not null
					        GROUP BY barang_id
					    )UNION(
					        SELECT barang_id, warna_id, gudang_id, sum(qty) as qty_masuk, sum(jumlah_roll) as jumlah_roll_masuk, CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar, 5 as tipe, t1.id
					        	FROM nd_penyesuaian_stok t1
					        	WHERE tipe_transaksi = 0
					        	AND tanggal < '$tanggal'
					        	AND tanggal >= '$tanggal_awal'
		                        GROUP BY barang_id
					    )UNION(
					        SELECT  barang_id, warna_id, gudang_id, sum(qty) as qty_masuk, sum(jumlah_roll) as jumlah_roll_masuk, CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar, 6 as tipe, t1.id
				        	FROM nd_penyesuaian_stok t1
				        	WHERE tanggal < '$tanggal'
				        	AND tanggal >= '$tanggal_awal'
				        	AND tipe_transaksi = 1
				        	GROUP BY barang_id
					    )UNION(
					        SELECT barang_id, warna_id, gudang_id, CAST(0 as DECIMAL(15,2)) as qty_masuk, 0 as jumlah_roll_masuk, sum(qty) as qty_keluar, sum(jumlah_roll) , 7 as tipe, t1.id
				        	FROM nd_penyesuaian_stok t1
				        	WHERE tanggal < '$tanggal'
				        	AND tanggal >= '$tanggal_awal'
				        	AND tipe_transaksi = 2
							GROUP BY barang_id
					    )UNION(
					    	SELECT barang_id, warna_id, gudang_id_before, CAST(0 as DECIMAL(15,2)) as qty_masuk, 0 as jumlah_roll_masuk, sum(qty) as qty_keluar, sum(jumlah_roll) as jumlah_roll_keluar, 8 as tipe, t1.id
				        	FROM nd_mutasi_barang t1
				        	WHERE tanggal < '$tanggal'
				        	AND tanggal >= '$tanggal_awal'
				        	AND status_aktif = 1
							GROUP BY barang_id
					    )UNION(
					    	SELECT barang_id, warna_id, gudang_id, sum(qty), sum(jumlah_roll) as jumlah_roll_masuk, 0 as qty_keluar, 0 as jumlah_roll_keluar, 9 as tipe, t1.id
				        	FROM nd_stok_opname_detail t1
				        	WHERE stok_opname_id = $stok_opname_id
			        		AND warna_id > 0
				        	GROUP BY barang_id
					    )
					) a
					GROUP BY barang_id
				)UNION (
					SELECT barang_id, warna_id, 0.00, 0, sum(qty) as qty_beli, sum(jumlah_roll) as jumlah_roll_beli, 0.00 , 0, 0.00, 0 , 0.00, 0,0.00 ,0, 0 as jumlah_roll_retur, 0.00 as qty_retur
			        FROM (
			        	SELECT qty, jumlah_roll, id, barang_id, warna_id, pembelian_id
			        	FROM nd_pembelian_detail
			        	ORDER BY pembelian_id
			        ) nd_pembelian_detail
			        LEFT JOIN (
			        	SELECT *
			        	FROM nd_pembelian
			        	WHERE tanggal >= '$tanggal'
			        	AND tanggal <= '$tanggal_end'
			        	AND status_aktif = 1
			        	) nd_pembelian
			        ON nd_pembelian_detail.pembelian_id = nd_pembelian.id
			        WHERE nd_pembelian.id is not null
			        GROUP BY barang_id
				)UNION(
			        SELECT barang_id, warna_id, 0.00, 0, 0.00, 0, 0.00,0, sum(qty), sum(jumlah_roll), 0.00, 0,0.00 ,0, 0 as jumlah_roll_retur, 0.00 as qty_retur
				        FROM (
				        	SELECT *
				        	FROM nd_penjualan_detail
				        ) nd_penjualan_detail
				        LEFT JOIN (
				        	SELECT *
				        	FROM nd_penjualan
				        	WHERE tanggal >= '$tanggal'
				        	AND tanggal <= '$tanggal_end'
				        	AND status_aktif = 1
				        	) nd_penjualan
				        ON nd_penjualan_detail.penjualan_id = nd_penjualan.id
				        LEFT JOIN (
				            SELECT sum(qty*if(jumlah_roll=0,1, jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, penjualan_detail_id
				            FROM nd_penjualan_qty_detail
				            GROUP BY penjualan_detail_id
				            ) nd_penjualan_qty_detail
				        ON nd_penjualan_qty_detail.penjualan_detail_id = nd_penjualan_detail.id
				        where nd_penjualan.id is not null
				        GROUP BY barang_id
			    )UNION(
			        SELECT barang_id, warna_id, 0.00, 0, 0.00, 0, 0.00,0, sum(qty), sum(jumlah_roll), 0.00, 0,0.00 ,0, 0 as jumlah_roll_retur, 0.00 as qty_retur
				        FROM (
				        	SELECT *
				        	FROM nd_pengeluaran_stok_lain_detail
				        ) nd_pengeluaran_stok_lain_detail
				        LEFT JOIN (
				        	SELECT *
				        	FROM nd_pengeluaran_stok_lain
				        	WHERE tanggal >= '$tanggal'
				        	AND tanggal <= '$tanggal_end'
				        	AND status_aktif = 1
				        	) nd_pengeluaran_stok_lain
				        ON nd_pengeluaran_stok_lain_detail.pengeluaran_stok_lain_id = nd_pengeluaran_stok_lain.id
				        LEFT JOIN (
				            SELECT sum(qty*if(jumlah_roll=0,1, jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, pengeluaran_stok_lain_detail_id
				            FROM nd_pengeluaran_stok_lain_qty_detail
				            GROUP BY pengeluaran_stok_lain_detail_id
				            ) nd_pengeluaran_stok_lain_qty_detail
				        ON nd_pengeluaran_stok_lain_qty_detail.pengeluaran_stok_lain_detail_id = nd_pengeluaran_stok_lain_detail.id
				        where nd_pengeluaran_stok_lain.id is not null
				        GROUP BY barang_id
			    )UNION (
					SELECT barang_id, warna_id, 0.00, 0, 0.00 , 0 , sum(qty) , sum(jumlah_roll),0.00 , 0, 0.00, 0,0.00 ,0, 0 as jumlah_roll_retur, 0.00 as qty_retur
			        	FROM nd_mutasi_barang
			        	WHERE tanggal >= '$tanggal'
			        	AND tanggal <= '$tanggal_end'
			        	AND status_aktif = 1
				        GROUP BY barang_id
				)UNION (
					SELECT barang_id, warna_id, 0.00, 0, 0.00 , 0 , 0.00,0 ,0.00 ,0, sum(qty) , sum(jumlah_roll),0.00 ,0, 0 as jumlah_roll_retur, 0.00 as qty_retur
			        	FROM nd_mutasi_barang
			        	WHERE tanggal >= '$tanggal'
			        	AND tanggal <= '$tanggal_end'
			        	AND status_aktif = 1
				        GROUP BY barang_id
				)UNION(
					SELECT barang_id, warna_id, 0.00, 0, 0.00 , 0 , 0.00,0 ,0.00 ,0, 0.00 , 0, sum(qty_masuk) - sum(qty_keluar), sum(jumlah_roll_masuk) - sum(jumlah_roll_keluar), 0 as jumlah_roll_retur, 0.00 as qty_retur
						FROM (
							(
						        SELECT  barang_id, warna_id, gudang_id, sum(qty) as qty_masuk, sum(jumlah_roll) as jumlah_roll_masuk, CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar
					        	FROM nd_penyesuaian_stok
					        	WHERE tanggal >= '$tanggal'
					        	AND tanggal <= '$tanggal_end'
					        	AND tipe_transaksi = 1
					        	GROUP BY barang_id
						    )UNION(
						        SELECT barang_id, warna_id, gudang_id, CAST(0 as DECIMAL(15,2)) as qty_masuk, 0 as jumlah_roll_masuk, sum(qty) as qty_keluar, sum(jumlah_roll) as jumlah_roll_keluar
					        	FROM nd_penyesuaian_stok
					        	WHERE tanggal >= '$tanggal'
					        	AND tanggal <= '$tanggal_end'
					        	AND tipe_transaksi = 2
								GROUP BY barang_id
							    )
							)a
				        GROUP BY barang_id
				)UNION(
			    	SELECT barang_id, warna_id, 0.00, 0, 0.00 , 0 , 0.00,0 ,0.00 ,0, 0.00 , 0,0.00 ,0, jumlah_roll as jumlah_roll_retur, qty as qty_retur
			        FROM (
			        	SELECT *
			        	FROM nd_retur_jual_detail
			        ) nd_retur_jual_detail
			        LEFT JOIN (
			        	SELECT *
			        	FROM nd_retur_jual
			        	WHERE tanggal >= '$tanggal'
			        	AND tanggal <= '$tanggal_end'
			        	AND status_aktif = 1
			        	) nd_retur_jual
			        ON nd_retur_jual_detail.retur_jual_id = nd_retur_jual.id
			        LEFT JOIN (
			            SELECT sum(qty*if(jumlah_roll=0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, retur_jual_detail_id
			            FROM nd_retur_jual_qty
			            GROUP BY retur_jual_detail_id
			            ) nd_penjualan_qty_detail
			        ON nd_penjualan_qty_detail.retur_jual_detail_id = nd_retur_jual_detail.id
			        WHERE nd_retur_jual.id is not null
			        GROUP BY barang_id
			    )
			)t1
			LEFT JOIN (
				SELECT barang_id, warna_id, harga as hpp
				FROM (
					SELECT *, YEAR(tanggal) as tahun
					FROM nd_tutup_buku
					WHERE id = $tutup_buku_id
					) t1
				LEFT JOIN (
					SELECT barang_id, warna_id, YEAR(tahun) as tahun, $kolom_harga as harga
					FROM nd_tutup_buku_detail
					) t2
				ON t1.tahun = t2.tahun
				GROUP BY barang_id
			) t2
			ON t1.barang_id = t2.barang_id
			LEFT JOIN (
				SELECT barang_id, warna_id,sum(qty*(harga_beli/(1+(ppn_berlaku/100)) ))/sum(qty) as hpp_beli 
				FROM (
					SELECT *,
					(SELECT ppn FROM nd_ppn tX WHERE tX.tanggal <= nd_pembelian.tanggal ORDER BY tX.tanggal DESC LIMIT 1 ) as ppn_berlaku
					FROM nd_pembelian
					WHERE tanggal >= '$tanggal'
					AND tanggal <= '$tanggal_end'
					) nd_pembelian
				LEFT JOIN nd_pembelian_detail
				ON nd_pembelian.id = nd_pembelian_detail.pembelian_id
				WHERE barang_id is not null
				GROUP BY YEAR(tanggal) , MONTH(tanggal), barang_id
			)t5
			ON t1.barang_id = t5.barang_id
			LEFT JOIN nd_barang as t3
			ON t1.barang_id = t3.id
			LEFT JOIN nd_warna as t4
			ON t1.warna_id = t4.id
			GROUP BY barang_id

			", false);

		return $query->result();
	}

	function get_tutup_buku_now($tahun, $bulan){
		$query = $this->db->query("SELECT t1.*, username
			FROM (
				SELECT * 
				FROM nd_tutup_buku 
				where MONTH(tanggal) ='$bulan' 
				AND YEAR(tanggal) ='$tahun' 
				ORDER BY updated desc
				LIMIT 1
				) t1
			LEFT JOIN nd_user t2
			ON t1.user_id = t2.id
			", false);

		return $query->result();
	}

	function get_tutup_buku_gudang_now($tahun, $bulan, $gudang_id){
		$query = $this->db->query("SELECT t1.*, username
			FROM (
				SELECT * 
				FROM nd_tutup_buku_gudang
				where MONTH(tanggal) ='$bulan' 
				AND YEAR(tanggal) ='$tahun'
				AND gudang_id = $gudang_id 
				ORDER BY updated desc 
				LIMIT 1
				) t1
			LEFT JOIN nd_user t2
			ON t1.user_id = t2.id
			", false);

		return $query->result();
	}

//===========================================stok opname=======================================

	function get_stok_opname_list($tanggal_start, $tanggal_end){
		$query = $this->db->query("SELECT t1.*, nama_jual, warna_jual, qty, jumlah_roll, t5.nama as nama_gudang
			FROM nd_stok_opname t1
			LEFT JOIN (
				SELECT sum(qty * jumlah_roll) as qty, sum(jumlah_roll) as jumlah_roll, barang_id, warna_id, stok_opname_id
				FROM nd_stok_opname_detail
        		WHERE warna_id > 0
				GROUP BY stok_opname_id
				) t2
			ON t1.id = t2.stok_opname_id
			LEFT JOIN nd_barang t3
			ON t1.barang_id_so = t3.id
			LEFT JOIN nd_warna t4
			ON t1.warna_id_so = t4.id
			LEFT JOIN nd_gudang t5
			ON t1.gudang_id_so = t5.id
			", false);

		return $query->result();
	}

	function get_stok_opname_detail_by_barang_by_date($barang_id, $warna_id, $tanggal)
	{
		$query = $this->db->query("SELECT t2.*
			FROM (
				SELECT *
				FROM nd_stok_opname_detail
				WHERE barang_id = $barang_id
				AND warna_id = $warna_id
				) t2
			LEFT JOIN (
				SELECT *
				FROM nd_stok_opname
				WHERE tanggal = '$tanggal'
				) t1 
			ON t1.id = t2.stok_opname_id
			WHERE t1.id is not null
			", false);

		return $query->result();
	}

	function get_stok_opname_detail($stok_opname_id, $select, $select_before, $tanggal_awal, $tanggal, $cond_barang){
		$query = $this->db->query("SELECT t3.*, t2.*, t2.id as stok_opname_detail_id, t1.*
			FROM (
				SELECT b.nama as nama_barang, b.nama_jual as nama_barang_jual, c.warna_beli as nama_warna, c.warna_jual as nama_warna_jual, b.status_aktif as status_barang, e.nama as nama_satuan,  barang_id, warna_id, satuan_id 
				FROM (
					(
						SELECT barang_id, warna_id
						FROM nd_pembelian_detail
					)UNION(
						SELECT barang_id, warna_id
						FROM nd_penjualan_detail
					)UNION(
						SELECT barang_id, warna_id
						FROM nd_penyesuaian_stok
					)UNION(
						SELECT barang_id, warna_id
						FROM nd_mutasi_barang
					)UNION(
						SELECT barang_id, warna_id
						FROM nd_stok_opname_detail
						WHERE warna_id > 0
					)
				)a
				LEFT JOIN nd_barang b
				ON a.barang_id = b.id
				LEFT JOIN nd_warna c
				ON a.warna_id = c.id
				LEFT JOIN nd_satuan e
				ON b.satuan_id = e.id
			) t1
			LEFT JOIN(
				SELECT id, barang_id, warna_id $select, gudang_id
				FROM nd_stok_opname_detail
				WHERE stok_opname_id = $stok_opname_id
        		AND warna_id > 0
				GROUP BY barang_id, warna_id
			) t2
			ON t1.barang_id = t2.barang_id
			AND t1.warna_id = t2.warna_id
			LEFT JOIN (
				SELECT barang_id, warna_id $select_before
				FROM(
					(
					        SELECT barang_id, warna_id, nd_pembelian.gudang_id, sum(qty) as qty_masuk, sum(jumlah_roll) as jumlah_roll_masuk, CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar
					        FROM (
					        	SELECT CAST(qty as DECIMAL(15,2)) as qty, jumlah_roll, tA.id, barang_id, warna_id, pembelian_id
					        	FROM nd_pembelian_detail tA
								GROUP BY tA.id
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
				    	SELECT barang_id, warna_id, gudang_id_after, sum(qty*if(jumlah_roll=0,1,jumlah_roll)) as qty_masuk, sum(jumlah_roll) as jumlah_roll_masuk, CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar
			        	FROM nd_mutasi_barang t1
						WHERE tanggal <= '$tanggal'
			        	AND tanggal >= '$tanggal_awal'
			        	AND status_aktif = 1
				        GROUP BY barang_id, warna_id, gudang_id_after
				    )UNION(
				        SELECT barang_id, warna_id, nd_penjualan_detail.gudang_id, CAST(0 as DECIMAL(15,2)) as qty_masuk, 0 as jumlah_roll_masuk, sum(qty) as qty_keluar, sum(jumlah_roll) as jumlah_roll_keluar
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
				        SELECT barang_id, warna_id, nd_pengeluaran_stok_lain_detail.gudang_id, CAST(0 as DECIMAL(15,2)) as qty_masuk, 0 as jumlah_roll_masuk, sum(qty) as qty_keluar, sum(jumlah_roll) as jumlah_roll_keluar
				        FROM nd_pengeluaran_stok_lain_detail
				        LEFT JOIN (
				        	SELECT *
				        	FROM nd_pengeluaran_stok_lain
				        	WHERE tanggal <= '$tanggal'
				        	AND tanggal >= '$tanggal_awal'
				        	AND status_aktif = 1
				        	) nd_pengeluaran_stok_lain
				        ON nd_pengeluaran_stok_lain_detail.pengeluaran_stok_lain_id = nd_pengeluaran_stok_lain.id
				        LEFT JOIN (
				            SELECT sum(qty* if(jumlah_roll != 0, jumlah_roll,1) ) as qty, sum(jumlah_roll) as jumlah_roll, pengeluaran_stok_lain_detail_id
				            FROM nd_pengeluaran_stok_lain_qty_detail
				            GROUP BY pengeluaran_stok_lain_detail_id
				            ) nd_pengeluaran_stok_lain_qty_detail
				        ON nd_pengeluaran_stok_lain_qty_detail.pengeluaran_stok_lain_detail_id = nd_pengeluaran_stok_lain_detail.id
				        where nd_pengeluaran_stok_lain.id is not null
				        GROUP BY barang_id, warna_id, nd_pengeluaran_stok_lain_detail.gudang_id
				    )UNION(
				    	SELECT barang_id, warna_id, nd_retur_jual_detail.gudang_id, sum(qty) as qty_masuk, sum(jumlah_roll) as jumlah_roll_masuk, CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar
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
				    	SELECT barang_id, warna_id, nd_retur_beli_detail.gudang_id,0,0, sum(qty), sum(jumlah_roll)
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
				        SELECT barang_id, warna_id, gudang_id, sum(qty) as qty_masuk, sum(jumlah_roll) as jumlah_roll_masuk, CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar
				        	FROM nd_penyesuaian_stok
				        	WHERE tipe_transaksi = 0
	                        AND tanggal <= '$tanggal'
				        	AND tanggal >= '$tanggal_awal'
	                        GROUP BY barang_id, warna_id, gudang_id
				    )UNION(
				        SELECT  barang_id, warna_id, gudang_id, sum(qty*if(jumlah_roll=0,1,jumlah_roll)) as qty_masuk, sum(jumlah_roll) as jumlah_roll_masuk, CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar
			        	FROM nd_penyesuaian_stok
			        	WHERE tanggal <= '$tanggal'
			        	AND tanggal >= '$tanggal_awal'
			        	AND tipe_transaksi = 1
			        	GROUP BY barang_id, warna_id, gudang_id
				    )UNION(
				        SELECT barang_id, warna_id, gudang_id, CAST(0 as DECIMAL(15,2)) as qty_masuk, 0 as jumlah_roll_masuk, sum(qty*if(jumlah_roll=0,1,jumlah_roll)) as qty_keluar, sum(jumlah_roll) as jumlah_roll_keluar
			        	FROM nd_penyesuaian_stok
			        	WHERE tanggal <= '$tanggal'
			        	AND tanggal >= '$tanggal_awal'
			        	AND tipe_transaksi = 2
						GROUP BY barang_id, warna_id, gudang_id
				    )UNION(
				    	SELECT barang_id, warna_id, gudang_id_before, CAST(0 as DECIMAL(15,2)) as qty_masuk, 0 as jumlah_roll_masuk, sum(qty*if(jumlah_roll=0,1,jumlah_roll)) as qty_keluar, sum(jumlah_roll) as jumlah_roll_keluar
			        	FROM nd_mutasi_barang t1
						WHERE tanggal <= '$tanggal'	
					    AND tanggal >= '$tanggal_awal'
			        	AND status_aktif = 1
						GROUP BY barang_id, warna_id, gudang_id_before
				    )UNION(
				    	SELECT barang_id, warna_id, gudang_id, sum(qty*jumlah_roll), sum(jumlah_roll) as jumlah_roll_masuk, 0 as qty_keluar, 0 as jumlah_roll_keluar
			        	FROM nd_stok_opname_detail
			        	WHERE stok_opname_id != $stok_opname_id
		        		AND warna_id > 0
			        	GROUP BY barang_id, warna_id, gudang_id
				    )
				) tbl_a
				GROUP by barang_id, warna_id
			) t3
			ON t1.barang_id = t3.barang_id
			AND t1.warna_id = t3.warna_id
			WHERE t1.barang_id != 0
			$cond_barang
			ORDER BY nama_barang_jual, nama_warna_jual

			", false);

		return $query->result();
	}


	function get_nama_stok_barang(){
		$query = $this->db->query("SELECT b.nama as nama_barang, b.nama_jual as nama_barang_jual, b.status_aktif as status_barang, barang_id 
				FROM (
					(
						SELECT barang_id
						FROM nd_pembelian_detail
					)UNION(
						SELECT barang_id
						FROM nd_penjualan_detail
					)UNION(
						SELECT barang_id
						FROM nd_penyesuaian_stok
					)UNION(
						SELECT barang_id
						FROM nd_mutasi_barang
					)UNION(
						SELECT barang_id
						FROM nd_stok_opname_detail
						WHERE warna_id > 0
					)
				)a
				LEFT JOIN nd_barang b
				ON a.barang_id = b.id
				ORDER BY nama_jual desc
			", false);

		return $query->result();
	}

	function get_stok_opname_detail_with_before($stok_opname_id, $select, $select_before, $cond_barang){
		$query = $this->db->query("SELECT satuan_id, nama_jual, warna_jual, barang_id, warna_id $select $select_before
			FROM (
				(
					SELECT barang_id, warna_id, gudang_id, sum(qty*if(jumlah_roll=0,1,jumlah_roll) ) as qty, sum(jumlah_roll) as jumlah_roll, 0 as qty_before, 0 as jumlah_roll_before
					FROM nd_stok_opname_detail
					WHERE stok_opname_id = $stok_opname_id
	        		AND warna_id > 0
					GROUP BY barang_id, warna_id, gudang_id
				)UNION(
					SELECT barang_id, warna_id, gudang_id, 0, 0, qty, jumlah_roll
					FROM nd_stok_before
					GROUP BY barang_id, warna_id, gudang_id
				)
			) t1
			LEFT JOIN nd_barang 
			ON t1.barang_id = nd_barang.id
			LEFT JOIN nd_warna 
			ON t1.warna_id = nd_warna.id
			$cond_barang
			GROUP BY barang_id,warna_id
			ORDER BY nama_jual, warna_jual
			", false);

		return $query->result();
	}

	function get_stok_opname_sebelum(){
		$query = $this->db->query("SELECT nama_jual, warna_jual, barang_id, warna_id, gudang_id, qty, jumlah_roll
				FROM nd_stok_before t1
				LEFT JOIN nd_barang 
				ON t1.barang_id = nd_barang.id
				LEFT JOIN nd_warna 
				ON t1.warna_id = nd_warna.id
				ORDER BY gudang_id, barang_id, warna_id
			", false);

		return $query->result();
	}

	function get_stok_opname_belum_lock()
	{
		$query = $this->db->query("SELECT nd_barang.nama_jual as nama_barang, nd_warna.warna_jual as nama_warna, nd_gudang.nama as nama_gudang, 
					t1.id, t2.*,DATE_FORMAT(t1.created_at, '%w') as hari, DATE_FORMAT(t1.created_at,'%d/%m/%Y %H:%i') as created_at,
					t1.barang_id_so, t1.warna_id_so, t1.gudang_id_so, DATE_FORMAT(t1.created_at,'%d/%m/%Y') as tanggal_ori, 
					t1.created_at as created_at_ori, stok_current, stok_date, roll_current
				FROM (
					SELECT *
					FROM nd_stok_opname
					WHERE created_at IN (
						SELECT max(created_at)
						FROM nd_stok_opname
						WHERE barang_id_so is not null
						AND warna_id_so is not null
						AND gudang_id_so is not null
						AND status_aktif = 0
						AND stok_opname_report_id is null
						GROUP BY barang_id_so, warna_id_so, gudang_id_so
						)
						AND status_aktif = 0
					) t1
				LEFT JOIN (
					SELECT stok_opname_id, barang_id, warna_id, gudang_id, sum(qty*jumlah_roll) as qty, sum(jumlah_roll) as jumlah_roll, group_concat(qty SEPARATOR '??') as qty_data, group_concat(jumlah_roll SEPARATOR '??') as jumlah_roll_data
					FROM (
						SELECT stok_opname_id, barang_id, warna_id, gudang_id, qty, sum(jumlah_roll) as jumlah_roll
						FROM nd_stok_opname_detail
		        		WHERE warna_id > 0
						GROUP BY barang_id, warna_id, gudang_id, stok_opname_id, qty
						)tbl
					GROUP BY barang_id, warna_id, gudang_id, stok_opname_id
					)t2
				ON t1.id = t2.stok_opname_id
				LEFT JOIN (
					SELECT barang_id_so, warna_id_so, gudang_id_so, max(created_at) as created_at
					FROM nd_stok_opname
					WHERE barang_id_so is not null
					AND warna_id_so is not null
					AND gudang_id_so is not null
					AND status_aktif = 1
					GROUP BY barang_id_so, warna_id_so, gudang_id_so
					) t3
				ON t3.created_at > t1.created_at
				AND t1.barang_id_so = t3.barang_id_so
				AND t1.warna_id_so = t3.warna_id_so
				AND t1.gudang_id_so = t3.gudang_id_so
				LEFT JOIN nd_barang 
				ON t1.barang_id_so = nd_barang.id
				LEFT JOIN nd_warna 
				ON t1.warna_id_so = nd_warna.id
				LEFT JOIN nd_gudang
				ON t1.gudang_id_so = nd_gudang.id
				WHERE t3.created_at is null
				ORDER BY t1.created_at
			", false);

		return $query->result();
	}

	function get_stok_for_opname($barang_id_list, $warna_id_list, $select, $tanggal_start,$stok_opname_id, $tanggal_awal, $select2)
	{
		$query = $this->db->query("SELECT CAST(concat(urutan_barang,LPAD(urutan,3,'0')) as UNSIGNED) as urutan,concat(tbl_b.nama_jual,' ',warna_jual) as nama_barang_jual, if(tbl_b.status_aktif = 1,'aktif', 'non aktif') as status_aktif
					 $select2
					 ,tbl_a.*
					FROM(
						SELECT barang_id, warna_id
						$select
						,MAX(tanggal) as last_edit 
						FROM (
								(
							        SELECT barang_id, warna_id, t1.gudang_id, 0 as qty_masuk, 0 as jumlah_roll_masuk, subqty as qty_keluar, subjumlah_roll as jumlah_roll_keluar,3 as tipe, tanggal, t2.id, ifnull(closed_date, created_at) as time_stamp
							        FROM nd_penjualan_detail t1
							        LEFT JOIN (
							        	SELECT *
							        	FROM nd_penjualan
							        	WHERE tanggal <= '$tanggal_start'
							        	AND tanggal >= '$tanggal_awal'
							        	AND status_aktif = 1
							        	) t2
							        ON t1.penjualan_id = t2.id
							        where t2.id is not null
							    )UNION(
							        SELECT barang_id, warna_id, t1.gudang_id, 0 as qty_masuk, 0 as jumlah_roll_masuk, qty as qty_keluar, jumlah_roll as jumlah_roll_keluar,20 as tipe, tanggal, t3.id, ifnull(closed_date, t2.created_at)
							        FROM nd_pengeluaran_stok_lain_detail t1
							        LEFT JOIN (
							        	SELECT *
							        	FROM nd_pengeluaran_stok_lain
							        	WHERE tanggal <= '$tanggal_start'
							        	AND tanggal >= '$tanggal_awal'
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
							        	WHERE tanggal <= '$tanggal_start'
							        	AND tanggal >= '$tanggal_awal'
							        	AND status_aktif = 1
							        	) t2
							        ON t1.pembelian_id = t2.id
							        WHERE t2.id is not null
							    )UNION(
							    	SELECT barang_id, warna_id, gudang_id_after, qty, jumlah_roll, 0, 0,2, tanggal, id, created_at
						        	FROM nd_mutasi_barang t1
									WHERE tanggal <= '$tanggal_start'
						        	AND tanggal >= '$tanggal_awal'
						        	AND status_aktif = 1
							    )UNION(
							    	SELECT barang_id, warna_id, t1.gudang_id, qty, jumlah_roll, 0, 0, 4, tanggal, t3.id, created_at
							        FROM nd_retur_jual_detail t1
							        LEFT JOIN (
							        	SELECT *
							        	FROM nd_retur_jual
							        	WHERE tanggal <= '$tanggal_start'
							        	AND tanggal >= '$tanggal_awal'
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
							        	WHERE tanggal <= '$tanggal_start'
							        	AND tanggal >= '$tanggal_awal'
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
						            AND tanggal <= '$tanggal_start'
						        	AND tanggal >= '$tanggal_awal'
							    )UNION(
							        SELECT barang_id, warna_id, gudang_id, 
									if(tipe_transaksi = 1 OR tipe_transaksi = 3,qty,0), if(tipe_transaksi = 1,jumlah_roll,if(tipe_transaksi=3,jml_roll,0)), 
									if(tipe_transaksi = 2 OR tipe_transaksi =3,qty,0), if(tipe_transaksi = 2,jumlah_roll,if(tipe_transaksi=3,jumlah_roll,0)),6, created_at as tanggal, id, created_at
						        	FROM (
										SELECT *
										FROM nd_penyesuaian_stok
										WHERE tanggal <= '$tanggal_start'
										AND tanggal >= '$tanggal_awal'
										AND tipe_transaksi != 0
									)t1
									LEFT JOIN (
										SELECT sum(jumlah_roll) as jml_roll, penyesuaian_stok_id
										FROM nd_penyesuaian_stok_split
										GROUP BY penyesuaian_stok_id
									) t2
									ON t1.id=t2.penyesuaian_stok_id
							    )UNION(
							    	SELECT barang_id, warna_id, gudang_id_before, 0, 0 , qty, jumlah_roll,8, tanggal, id, created_at
						        	FROM nd_mutasi_barang t1
									WHERE tanggal <= '$tanggal_start'	
								    AND tanggal >= '$tanggal_awal'
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
										WHERE tanggal <= '$tanggal_start'	
									    AND tanggal >= '$tanggal_awal'
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
						    WHERE tipe_transaksi != 3
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
					Where barang_id IN ($barang_id_list)
					AND warna_id IN ($warna_id_list)
					ORDER BY urutan_barang, urutan
			", false);

		return $query->result();
	}

	function get_stok_for_opname_2($barang_id_list, $warna_id_list, $select, $tanggal_start,$stok_opname_id, $tanggal_awal, $select2)
	{
		$query = $this->db->query("SELECT CAST(concat(urutan_barang,LPAD(urutan,3,'0')) as UNSIGNED) as urutan,concat(tbl_b.nama_jual,' ',warna_jual) as nama_barang_jual, if(tbl_b.status_aktif = 1,'aktif', 'non aktif') as status_aktif
					 $select2
					 ,tbl_a.*
					FROM(
						SELECT barang_id, warna_id
						$select
						,MAX(tanggal) as last_edit 
						FROM (
								(
							        SELECT barang_id, warna_id, t1.gudang_id, 0 as qty_masuk, 0 as jumlah_roll_masuk, subqty as qty_keluar, subjumlah_roll as jumlah_roll_keluar,3 as tipe, tanggal, t2.id, ifnull(closed_date, created_at) as time_stamp
							        FROM nd_penjualan_detail t1
							        LEFT JOIN (
							        	SELECT *
							        	FROM nd_penjualan
							        	WHERE tanggal <= '$tanggal_start'
							        	AND tanggal >= '$tanggal_awal'
							        	AND status_aktif = 1
							        	) t2
							        ON t1.penjualan_id = t2.id
							        where t2.id is not null
							    )UNION(
							        SELECT barang_id, warna_id, t1.gudang_id, 0 as qty_masuk, 0 as jumlah_roll_masuk, qty as qty_keluar, jumlah_roll as jumlah_roll_keluar,20 as tipe, tanggal, t3.id, ifnull(closed_date, t2.created_at)
							        FROM nd_pengeluaran_stok_lain_detail t1
							        LEFT JOIN (
							        	SELECT *
							        	FROM nd_pengeluaran_stok_lain
							        	WHERE tanggal <= '$tanggal_start'
							        	AND tanggal >= '$tanggal_awal'
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
							        	WHERE tanggal <= '$tanggal_start'
							        	AND tanggal >= '$tanggal_awal'
							        	AND status_aktif = 1
							        	) t2
							        ON t1.pembelian_id = t2.id
							        WHERE t2.id is not null
							    )UNION(
							    	SELECT barang_id, warna_id, gudang_id_after, qty, jumlah_roll, 0, 0,2, tanggal, id, created_at
						        	FROM nd_mutasi_barang t1
									WHERE tanggal <= '$tanggal_start'
						        	AND tanggal >= '$tanggal_awal'
						        	AND status_aktif = 1
							    )UNION(
							    	SELECT barang_id, warna_id, t1.gudang_id, qty, jumlah_roll, 0, 0, 4, tanggal, t3.id, created_at
							        FROM nd_retur_jual_detail t1
							        LEFT JOIN (
							        	SELECT *
							        	FROM nd_retur_jual
							        	WHERE tanggal <= '$tanggal_start'
							        	AND tanggal >= '$tanggal_awal'
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
							        	WHERE tanggal <= '$tanggal_start'
							        	AND tanggal >= '$tanggal_awal'
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
						            AND tanggal <= '$tanggal_start'
						        	AND tanggal >= '$tanggal_awal'
							    )UNION(
							        SELECT barang_id, warna_id, gudang_id, if(tipe_transaksi = 1,qty, 0 ), if(tipe_transaksi = 1,jumlah_roll,0 ), if(tipe_transaksi = 2,qty, 0 ), if(tipe_transaksi=2,jumlah_roll,0),6, tanggal, id, created_at
						        	FROM nd_penyesuaian_stok
						        	WHERE tanggal <= '$tanggal_start'
						        	AND tanggal >= '$tanggal_awal'
						        	AND tipe_transaksi != 0
							    )UNION(
							    	SELECT barang_id, warna_id, gudang_id_before, 0, 0 , qty, jumlah_roll,8, tanggal, id, created_at
						        	FROM nd_mutasi_barang t1
									WHERE tanggal <= '$tanggal_start'	
								    AND tanggal >= '$tanggal_awal'
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
										WHERE tanggal <= '$tanggal_start'	
									    AND tanggal >= '$tanggal_awal'
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
						    WHERE tipe_transaksi != 3
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
					Where barang_id IN ($barang_id_list)
					AND warna_id IN ($warna_id_list)
					ORDER BY urutan_barang, urutan
			", false);

		return $query->result();
	}

//===========================================stok split======================================

	function get_data_penyesuaian_list($tanggal_start, $tanggal_end, $cond_gudang, $cond_barang, $cond_warna){
		$query = $this->db->query("SELECT t1.*, t2.*, nd_gudang.nama as nama_gudang, 
			nama_jual as nama_barang, warna_jual as nama_warna, username 
			FROM (
				SELECT *
				FROM nd_penyesuaian_stok
				WHERE tipe_transaksi=3 
				AND tanggal >= '$tanggal_start'
				AND tanggal <= '$tanggal_end'
				$cond_gudang
				$cond_barang
				$cond_warna
				)t1
			LEFT JOIN (
				SELECT penyesuaian_stok_id, group_concat(id) as split_id, group_concat(qty) as qty_data, group_concat(jumlah_roll) as jumlah_roll_data
				FROM nd_penyesuaian_stok_split
				GROUP BY penyesuaian_stok_id
			)t2
			ON t1.id = penyesuaian_stok_id
			LEFT JOIN nd_gudang 
			ON t1.gudang_id = nd_gudang.id
			LEFT JOIN nd_barang 
			ON t1.barang_id = nd_barang.id
			LEFT JOIN nd_warna 
			ON t1.warna_id = nd_warna.id
			LEFT JOIN nd_user 
			ON t1.user_id = nd_user.id
				
			");
		return $query->result();
	}

//===========================================penerimaan barang======================================

	function get_penerimaan_barang($from, $to){
		$query = $this->db->query("SELECT t1.*,tanggal, 
			group_concat(nama_barang SEPARATOR '??') as nama_barang,
			group_concat(nama_warna SEPARATOR '??') as nama_warna,
			group_concat(qty SEPARATOR '??') as qty_data,
			group_concat(jumlah_roll SEPARATOR '??') as jumlah_roll_data
			FROM (
				SELECT *
				FROM nd_penerimaan_barang
				)t1
			LEFT JOIN (
				SELECT penerimaan_barang_id, tanggal, 
				(nama_jual) as nama_barang, 
				(warna_jual) as nama_warna, 
				sum(qty) as qty, sum(jumlah_roll) as jumlah_roll
				FROM (
					SELECT *
					FROM nd_pembelian
					WHERE tanggal >= '$from'
					AND tanggal <= '$to'
					AND status_aktif = 1
				) tA
				LEFT JOIN (
					SELECT pembelian_id, sum(qty) as qty, sum(jumlah_roll) as jumlah_roll, barang_id, warna_id
					FROM nd_pembelian_detail
					GROUP BY barang_id, warna_id, pembelian_id
				) tB
				ON tA.id = tB.pembelian_id
				LEFT JOIN nd_barang
				ON tB.barang_id = nd_barang.id
				LEFT JOIN nd_warna
				ON tB.warna_id = nd_warna.id
				GROUP BY barang_id, warna_id, penerimaan_barang_id
			)t2
			ON t1.id = t2.penerimaan_barang_id
			GROUP BY penerimaan_barang_id
				
			");
		return $query->result();
	}

}