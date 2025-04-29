<?php

class Admin_Model extends CI_Model {

	function __construct()
    {
         // Call the Model constructor
        parent::__construct();
        $this->db->query("SET SESSION time_zone = '+7:00'");
    }

//=============================recap dashboard==================
	function recap_pembelian_bulanan($tanggal_start, $tanggal_end)
	{
		$query = $this->db->query("SELECT sum(amount) as amount 
			FROM (
				SELECT *
				FROM nd_pembelian
				WHERE tanggal >= '$tanggal_start'
				AND tanggal <= '$tanggal_end'
				AND status_aktif = 1
				) as tbl_a
			LEFT JOIN (
				SELECT (harga_beli*qty) as amount, pembelian_id
				FROM nd_pembelian_detail t1
				-- LEFT JOIN (
				-- 	SELECT pembelian_detail_id, sum(qty * if(jumlah_roll = 0,1, jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll
				-- 	FROM nd_pembelian_qty_detail
				-- 	WHERE qty != 0
				-- 	GROUP BY pembelian_detail_id
				-- 	) t2
				-- ON t2.pembelian_detail_id = t1.id
				) as tbl_b
			ON tbl_a.id = tbl_b.pembelian_id

			");
		return $query->result();
	}

	function recap_penjualan_bulanan($tanggal_start, $tanggal_end)
	{
		$query = $this->db->query("SELECT sum(amount) as amount 
			FROM (
				SELECT *
				FROM nd_penjualan
				WHERE tanggal >= '$tanggal_start'
				AND tanggal <= '$tanggal_end'
				AND status_aktif = 1
				) as tbl_a
			LEFT JOIN (
				SELECT (harga_jual*qty) as amount, penjualan_id
				FROM nd_penjualan_detail
				LEFT JOIN (
					SELECT sum(qty*if(jumlah_roll = 0,1,jumlah_roll) ) as qty, penjualan_detail_id
					FROM nd_penjualan_qty_detail
					GROUP BY penjualan_detail_id
					) as nd_penjualan_qty_detail
				ON nd_penjualan_detail.id = nd_penjualan_qty_detail.penjualan_detail_id
				) as tbl_b
			ON tbl_a.id = tbl_b.penjualan_id
			");
		return $query->result();
	}

	function get_list_penjualan_barang_by_date($barang_id,$cond_warna, $date_start, $date_end){
		$query = $this->db->query("SELECT tanggal, sum(amount) as amount, warna_id, qty
			FROM (
				SELECT *
				FROM nd_penjualan
				where status_aktif = 1
				and tanggal >= '$date_start'
				AND tanggal <= '$date_end'
				) as tbl_a
			LEFT JOIN (
				SELECT sum(harga_jual*qty) as amount, sum(qty) as qty, penjualan_id, barang_id, group_concat(warna_id) as warna_id
				FROM nd_penjualan_detail
				LEFT JOIN (
					SELECT sum(qty *if(jumlah_roll = 0,1,jumlah_roll) ) as qty, penjualan_detail_id
					FROM nd_penjualan_qty_detail
					GROUP BY penjualan_detail_id
					) as nd_penjualan_qty_detail
				ON nd_penjualan_detail.id = nd_penjualan_qty_detail.penjualan_detail_id
				WHERE barang_id = $barang_id
				$cond_warna
				group by penjualan_id
				) as tbl_b
			ON tbl_a.id = tbl_b.penjualan_id
			WHERE tbl_b.penjualan_id is not null
			group by DATE(tanggal)
			");
		return $query->result();
	}

	function get_list_penjualan_barang_by_tanggal($barang_id,$cond_warna, $date_start, $date_end){
		$query = $this->db->query("SELECT DAY(tanggal) as tanggal, sum(amount) as amount, warna_id, qty
			FROM (
				SELECT *
				FROM nd_penjualan
				where status_aktif = 1
				and tanggal >= '$date_start'
				AND tanggal <= '$date_end'
				) as tbl_a
			LEFT JOIN (
				SELECT sum(harga_jual*qty) as amount, sum(qty) as qty, penjualan_id, barang_id, group_concat(warna_id) as warna_id
				FROM nd_penjualan_detail
				LEFT JOIN (
					SELECT sum(qty *if(jumlah_roll = 0,1,jumlah_roll) ) as qty, penjualan_detail_id
					FROM nd_penjualan_qty_detail
					GROUP BY penjualan_detail_id
					) as nd_penjualan_qty_detail
				ON nd_penjualan_detail.id = nd_penjualan_qty_detail.penjualan_detail_id
				WHERE barang_id = $barang_id
				$cond_warna
				group by penjualan_id
				) as tbl_b
			ON tbl_a.id = tbl_b.penjualan_id
			WHERE tbl_b.penjualan_id is not null
			group by DAY(tanggal), MONTH(tanggal)
			");
		return $query->result();
	}

	function get_list_penjualan_by_date($date_start, $date_end){
		$query = $this->db->query("SELECT tanggal, sum(amount) as amount
			FROM (
				SELECT *
				FROM nd_penjualan
				where status_aktif = 1
				and DATE(tanggal) >= '$date_start'
				AND DATE(tanggal) <= '$date_end'
				) as tbl_a
			LEFT JOIN (
				SELECT sum(harga_jual*qty) as amount, penjualan_id
				FROM nd_penjualan_detail
				LEFT JOIN (
					SELECT sum(qty *if(jumlah_roll = 0,1,jumlah_roll) ) as qty, penjualan_detail_id
					FROM nd_penjualan_qty_detail
					GROUP BY penjualan_detail_id
					) as nd_penjualan_qty_detail
				ON nd_penjualan_detail.id = nd_penjualan_qty_detail.penjualan_detail_id
				group by penjualan_id
				) as tbl_b
			ON tbl_a.id = tbl_b.penjualan_id
			group by DATE(tanggal)
			");
		return $query->result();
	}

	function get_list_penjualan_tahunan($date_start, $date_end){
		$query = $this->db->query("SELECT MONTHNAME(tanggal) as tanggal, sum(amount)/1000 as amount
			FROM (
				SELECT *
				FROM nd_penjualan
				where status_aktif = 1
				and tanggal >= '$date_start'
				AND tanggal <= '$date_end'
				) as tbl_a
			LEFT JOIN (
				SELECT sum(harga_jual*qty) as amount, penjualan_id
				FROM nd_penjualan_detail
				LEFT JOIN (
					SELECT sum(qty * if(jumlah_roll = 0,1,jumlah_roll)) as qty, penjualan_detail_id
					FROM nd_penjualan_qty_detail
					GROUP BY penjualan_detail_id
					) as nd_penjualan_qty_detail
				ON nd_penjualan_detail.id = nd_penjualan_qty_detail.penjualan_detail_id
				group by penjualan_id
				) as tbl_b
			ON tbl_a.id = tbl_b.penjualan_id
			group by MONTH(tanggal)
			");
		return $query->result();
	}

	function get_list_penjualan_pembelian_tahunan($date_start, $date_end){
		$query = $this->db->query("SELECT bulan, tanggal, sum(amount_jual) as amount_jual, sum(amount_beli) as amount_beli
			FROM (
				(
					SELECT MONTH(tanggal) as bulan, MONTHNAME(tanggal) as tanggal, sum(amount)/1000 as amount_jual, 0 as amount_beli
					FROM (
						SELECT *
						FROM nd_penjualan
						where status_aktif = 1
						and tanggal >= '$date_start'
						AND tanggal <= '$date_end'
						) as tbl_a
					LEFT JOIN (
						SELECT sum(harga_jual*qty) as amount, penjualan_id
						FROM nd_penjualan_detail
						LEFT JOIN (
							SELECT sum(qty * if(jumlah_roll = 0,1,jumlah_roll)) as qty, penjualan_detail_id
							FROM nd_penjualan_qty_detail
							GROUP BY penjualan_detail_id
							) as nd_penjualan_qty_detail
						ON nd_penjualan_detail.id = nd_penjualan_qty_detail.penjualan_detail_id
						group by penjualan_id
						) as tbl_b
					ON tbl_a.id = tbl_b.penjualan_id
					group by MONTH(tanggal)
				)UNION(
					SELECT MONTH(tanggal) as bulan, MONTHNAME(tanggal) as tanggal, 0, sum(amount)/1000
					FROM (
						SELECT *
						FROM nd_pembelian
						where status_aktif = 1
						and tanggal >= '$date_start'
						AND tanggal <= '$date_end'
						) as tbl_a
					LEFT JOIN (
						SELECT sum(harga_beli*qty) as amount, pembelian_id
						FROM nd_pembelian_detail
						-- LEFT JOIN (
						-- 	SELECT sum(qty * if(jumlah_roll = 0,1,jumlah_roll)) as qty, pembelian_detail_id
						-- 	FROM nd_pembelian_qty_detail
						-- 	GROUP BY pembelian_detail_id
						-- 	) as nd_pembelian_qty_detail
						-- ON nd_pembelian_detail.id = nd_pembelian_qty_detail.pembelian_detail_id
						group by pembelian_id
						) as tbl_b
					ON tbl_a.id = tbl_b.pembelian_id
					group by MONTH(tanggal)
				)
			)result
			GROUP BY tanggal
			ORDER BY bulan asc
			");
		return $query->result();
	}

//===========================================best seller===========================================

	function get_barang_jual_terbanyak($year)
	{
		$query = $this->db->query("SELECT *
			FROM (
				(
					SELECT concat_ws(' ',tbl_c.nama_jual ) as barang, sum(qty) as qty 
					FROM (
						SELECT *
						FROM nd_penjualan
						WHERE YEAR(tanggal) = '$year'
						AND status_aktif = 1
						) as tbl_a
					LEFT JOIN (
						SELECT qty, penjualan_id, barang_id, warna_id 
						FROM nd_penjualan_detail
						LEFT JOIN (
							SELECT sum(qty * if(jumlah_roll = 0,1,jumlah_roll)) as qty, penjualan_detail_id
							FROM nd_penjualan_qty_detail
							GROUP BY penjualan_detail_id
							) as nd_penjualan_qty_detail
						ON nd_penjualan_detail.id = nd_penjualan_qty_detail.penjualan_detail_id
						) as tbl_b
					ON tbl_a.id = tbl_b.penjualan_id
					LEFT JOIN nd_barang tbl_c
					ON tbl_b.barang_id = tbl_c.id
					LEFT JOIN nd_warna tbl_d
					ON tbl_b.warna_id = tbl_d.id
					WHERE barang_id is not null
					group by barang_id
					order by qty desc
					limit 10
				)UNION(
					SELECT 'lain-lain', sum(qty)
					FROM (
						SELECT concat_ws(' ',tbl_c.nama ) as barang, sum(qty) as qty 
						FROM (
							SELECT *
							FROM nd_penjualan
							WHERE YEAR(tanggal) = '$year'
							AND status_aktif = 1
							) as tbl_a
						LEFT JOIN (
							SELECT qty, penjualan_id, barang_id, warna_id 
							FROM nd_penjualan_detail
							LEFT JOIN (
								SELECT sum(qty * if(jumlah_roll = 0,1,jumlah_roll)) as qty, penjualan_detail_id
								FROM nd_penjualan_qty_detail
								GROUP BY penjualan_detail_id
								) as nd_penjualan_qty_detail
							ON nd_penjualan_detail.id = nd_penjualan_qty_detail.penjualan_detail_id
							) as tbl_b
						ON tbl_a.id = tbl_b.penjualan_id
						LEFT JOIN nd_barang tbl_c
						ON tbl_b.barang_id = tbl_c.id
						LEFT JOIN nd_warna tbl_d
						ON tbl_b.warna_id = tbl_d.id
						WHERE barang_id is not null
						group by barang_id
						order by qty desc
						limit 1000
						OFFSET 10
					)tA
				)
			)result

			");
		return $query->result();
	}

	function get_barang_jual_terbanyak_all($year)
	{
		$query = $this->db->query("SELECT barang, sum(qty) as qty, sum(jml_transaksi) as jml_transaksi, barang_id
			FROM (
					SELECT concat_ws(' ',tbl_c.nama_jual) as barang, sum(qty) as qty, 1 as jml_transaksi, barang_id
					FROM (
						SELECT *
						FROM nd_penjualan
						WHERE YEAR(tanggal) = '$year'
						AND status_aktif = 1
						) as tbl_a
					LEFT JOIN (
						SELECT qty, penjualan_id, barang_id, warna_id 
						FROM nd_penjualan_detail
						LEFT JOIN (
							SELECT sum(qty * if(jumlah_roll = 0,1,jumlah_roll)) as qty, penjualan_detail_id
							FROM nd_penjualan_qty_detail
							GROUP BY penjualan_detail_id
							) as nd_penjualan_qty_detail
						ON nd_penjualan_detail.id = nd_penjualan_qty_detail.penjualan_detail_id
						) as tbl_b
					ON tbl_a.id = tbl_b.penjualan_id
					LEFT JOIN nd_barang tbl_c
					ON tbl_b.barang_id = tbl_c.id
					LEFT JOIN nd_warna tbl_d
					ON tbl_b.warna_id = tbl_d.id
					WHERE barang_id is not null
					group by barang_id, penjualan_id
				) result
				group by barang_id
				order by qty desc

			");
		return $query->result();
	}

	function get_barang_warna_jual_terbanyak($year)
	{
		$query = $this->db->query("SELECT *
			FROM (
				(
					SELECT concat_ws(' ',tbl_c.nama_jual,warna_beli ) as barang, sum(qty) as qty 
					FROM (
						SELECT *
						FROM nd_penjualan
						WHERE YEAR(tanggal) = '$year'
						AND status_aktif = 1
						) as tbl_a
					LEFT JOIN (
						SELECT qty, penjualan_id, barang_id, warna_id 
						FROM nd_penjualan_detail
						LEFT JOIN (
							SELECT sum(qty * if(jumlah_roll = 0,1,jumlah_roll)) as qty, penjualan_detail_id
							FROM nd_penjualan_qty_detail
							GROUP BY penjualan_detail_id
							) as nd_penjualan_qty_detail
						ON nd_penjualan_detail.id = nd_penjualan_qty_detail.penjualan_detail_id
						) as tbl_b
					ON tbl_a.id = tbl_b.penjualan_id
					LEFT JOIN nd_barang tbl_c
					ON tbl_b.barang_id = tbl_c.id
					LEFT JOIN nd_warna tbl_d
					ON tbl_b.warna_id = tbl_d.id
					WHERE barang_id is not null
					group by barang_id, warna_id
					order by qty desc
					limit 10
				)UNION(
					SELECT 'lain-lain', sum(qty)
					FROM (
						SELECT concat_ws(' ',tbl_c.nama,warna_beli ) as barang, sum(qty) as qty 
						FROM (
							SELECT *
							FROM nd_penjualan
							WHERE YEAR(tanggal) = '$year'
							AND status_aktif = 1
							) as tbl_a
						LEFT JOIN (
							SELECT qty, penjualan_id, barang_id, warna_id 
							FROM nd_penjualan_detail
							LEFT JOIN (
								SELECT sum(qty * if(jumlah_roll = 0,1,jumlah_roll)) as qty, penjualan_detail_id
								FROM nd_penjualan_qty_detail
								GROUP BY penjualan_detail_id
								) as nd_penjualan_qty_detail
							ON nd_penjualan_detail.id = nd_penjualan_qty_detail.penjualan_detail_id
							) as tbl_b
						ON tbl_a.id = tbl_b.penjualan_id
						LEFT JOIN nd_barang tbl_c
						ON tbl_b.barang_id = tbl_c.id
						LEFT JOIN nd_warna tbl_d
						ON tbl_b.warna_id = tbl_d.id
						WHERE barang_id is not null
						group by barang_id, warna_id
						order by qty desc
						limit 1000
						OFFSET 10
					)tA
				)
			)result

			");
		return $query->result();
	}

	function get_barang_warna_jual_terbanyak_all($year)
	{
		$query = $this->db->query("SELECT barang, sum(qty) as qty, sum(jml_transaksi) as jml_transaksi,concat(barang_id,'??',warna_id) as barang_data
			FROM (
					SELECT concat_ws(' ',tbl_c.nama_jual, warna_beli) as barang, sum(qty) as qty, 1 as jml_transaksi, barang_id, warna_id
					FROM (
						SELECT *
						FROM nd_penjualan
						WHERE YEAR(tanggal) = '$year'
						AND status_aktif = 1
						) as tbl_a
					LEFT JOIN (
						SELECT qty, penjualan_id, barang_id, warna_id 
						FROM nd_penjualan_detail
						LEFT JOIN (
							SELECT sum(qty * if(jumlah_roll = 0,1,jumlah_roll)) as qty, penjualan_detail_id
							FROM nd_penjualan_qty_detail
							GROUP BY penjualan_detail_id
							) as nd_penjualan_qty_detail
						ON nd_penjualan_detail.id = nd_penjualan_qty_detail.penjualan_detail_id
						) as tbl_b
					ON tbl_a.id = tbl_b.penjualan_id
					LEFT JOIN nd_barang tbl_c
					ON tbl_b.barang_id = tbl_c.id
					LEFT JOIN nd_warna tbl_d
					ON tbl_b.warna_id = tbl_d.id
					WHERE barang_id is not null
					group by barang_id, warna_id, penjualan_id
				) result
				group by barang_id, warna_id
				order by qty desc

			");
		return $query->result();
	}

	function get_barang_jual_warna_terbanyak($year)
	{
		$query = $this->db->query("SELECT *
			FROM (
				(
					SELECT tbl_d.warna_beli as barang, sum(qty) as qty,'' as warna_list, '' as qty_data
					FROM (
						SELECT *
						FROM nd_penjualan
						WHERE YEAR(tanggal) = '$year'
						AND status_aktif = 1
						) as tbl_a
					LEFT JOIN (
						SELECT qty, penjualan_id, barang_id, warna_id 
						FROM nd_penjualan_detail
						LEFT JOIN (
							SELECT sum(qty * if(jumlah_roll = 0,1,jumlah_roll)) as qty, penjualan_detail_id
							FROM nd_penjualan_qty_detail
							GROUP BY penjualan_detail_id
							) as nd_penjualan_qty_detail
						ON nd_penjualan_detail.id = nd_penjualan_qty_detail.penjualan_detail_id
						) as tbl_b
					ON tbl_a.id = tbl_b.penjualan_id
					LEFT JOIN nd_barang tbl_c
					ON tbl_b.barang_id = tbl_c.id
					LEFT JOIN nd_warna tbl_d
					ON tbl_b.warna_id = tbl_d.id
					WHERE barang_id is not null
					group by warna_id
					order by qty desc
					limit 10
				)UNION(
					SELECT 'lain-lain', sum(qty) as qty, group_concat(barang ORDER BY qty desc) as warna_list, group_concat(qty ORDER BY qty desc)
					FROM (
						SELECT tbl_d.warna_beli as barang, sum(qty) as qty 
						FROM (
							SELECT *
							FROM nd_penjualan
							WHERE YEAR(tanggal) = '$year'
							AND status_aktif = 1
							) as tbl_a
						LEFT JOIN (
							SELECT qty, penjualan_id, barang_id, warna_id 
							FROM nd_penjualan_detail
							LEFT JOIN (
								SELECT sum(qty * if(jumlah_roll = 0,1,jumlah_roll)) as qty, penjualan_detail_id
								FROM nd_penjualan_qty_detail
								GROUP BY penjualan_detail_id
								) as nd_penjualan_qty_detail
							ON nd_penjualan_detail.id = nd_penjualan_qty_detail.penjualan_detail_id
							) as tbl_b
						ON tbl_a.id = tbl_b.penjualan_id
						LEFT JOIN nd_barang tbl_c
						ON tbl_b.barang_id = tbl_c.id
						LEFT JOIN nd_warna tbl_d
						ON tbl_b.warna_id = tbl_d.id
						WHERE barang_id is not null
						group by warna_id
						order by qty desc
						LIMIT 1000
						OFFSET 10
					)tA
				)
			)result
			");
		return $query->result();
	}

	function get_barang_jual_warna_terbanyak_all($year)
	{
		$query = $this->db->query("SELECT barang, sum(qty) as qty, sum(jml_transaksi) as jml_transaksi, warna_id
				FROM (
					SELECT tbl_d.warna_beli as barang, sum(qty) as qty, 1 as jml_transaksi, warna_id
					FROM (
						SELECT *
						FROM nd_penjualan
						WHERE YEAR(tanggal) = '$year'
						AND status_aktif = 1
						) as tbl_a
					LEFT JOIN (
						SELECT qty, penjualan_id, barang_id, warna_id 
						FROM nd_penjualan_detail
						LEFT JOIN (
							SELECT sum(qty * if(jumlah_roll = 0,1,jumlah_roll)) as qty, penjualan_detail_id
							FROM nd_penjualan_qty_detail
							GROUP BY penjualan_detail_id
							) as nd_penjualan_qty_detail
						ON nd_penjualan_detail.id = nd_penjualan_qty_detail.penjualan_detail_id
						) as tbl_b
					ON tbl_a.id = tbl_b.penjualan_id
					LEFT JOIN nd_barang tbl_c
					ON tbl_b.barang_id = tbl_c.id
					LEFT JOIN nd_warna tbl_d
					ON tbl_b.warna_id = tbl_d.id
					WHERE barang_id is not null
					group by warna_id, tbl_a.id
				)t1
				group by warna_id
				order by qty desc
			");
		return $query->result();
	}

//================================================detail by=======================================

	function get_warna_terbanyak_by_barang($year, $barang_id)
	{
		$query = $this->db->query("SELECT barang, sum(qty) as qty, sum(jml_transaksi) as jml_transaksi, warna_id
				FROM (
					SELECT tbl_d.warna_beli as barang, sum(qty) as qty, 1 as jml_transaksi, warna_id
					FROM (
						SELECT *
						FROM nd_penjualan
						WHERE YEAR(tanggal) = '$year'
						AND status_aktif = 1
						) as tbl_a
					LEFT JOIN (
						SELECT qty, penjualan_id, barang_id, warna_id 
						FROM nd_penjualan_detail
						LEFT JOIN (
							SELECT sum(qty * if(jumlah_roll = 0,1,jumlah_roll)) as qty, penjualan_detail_id
							FROM nd_penjualan_qty_detail
							GROUP BY penjualan_detail_id
							) as nd_penjualan_qty_detail
						ON nd_penjualan_detail.id = nd_penjualan_qty_detail.penjualan_detail_id
						WHERE  barang_id = $barang_id
						) as tbl_b
					ON tbl_a.id = tbl_b.penjualan_id
					LEFT JOIN nd_barang tbl_c
					ON tbl_b.barang_id = tbl_c.id
					LEFT JOIN nd_warna tbl_d
					ON tbl_b.warna_id = tbl_d.id
					WHERE barang_id is not null
					AND tbl_b.penjualan_id is not null
					group by warna_id, tbl_a.id
				)t1
				group by warna_id
				order by qty desc
			");
		return $query->result();
	}

	function get_barang_terbanyak_by_warna($year, $warna_id)
	{
		$query = $this->db->query("SELECT nama_barang, sum(qty) as qty, sum(jml_transaksi) as jml_transaksi, barang_id
			FROM (
					SELECT concat_ws(' ',tbl_c.nama_jual) as nama_barang, sum(qty) as qty, 1 as jml_transaksi, barang_id
					FROM (
						SELECT *
						FROM nd_penjualan
						WHERE YEAR(tanggal) = '$year'
						AND status_aktif = 1
						) as tbl_a
					LEFT JOIN (
						SELECT qty, penjualan_id, barang_id, warna_id 
						FROM nd_penjualan_detail
						LEFT JOIN (
							SELECT sum(qty * if(jumlah_roll = 0,1,jumlah_roll)) as qty, penjualan_detail_id
							FROM nd_penjualan_qty_detail
							GROUP BY penjualan_detail_id
							) as nd_penjualan_qty_detail
						ON nd_penjualan_detail.id = nd_penjualan_qty_detail.penjualan_detail_id
						WHERE warna_id = $warna_id
						) as tbl_b
					ON tbl_a.id = tbl_b.penjualan_id
					LEFT JOIN nd_barang tbl_c
					ON tbl_b.barang_id = tbl_c.id
					LEFT JOIN nd_warna tbl_d
					ON tbl_b.warna_id = tbl_d.id
					WHERE tbl_b.penjualan_id is not null
					group by barang_id, penjualan_id
				) result
				group by barang_id
				order by qty desc

			");
		return $query->result();
	}

	function get_customer_terbanyak_by_barang_warna($year, $barang_id, $warna_id)
	{
		$query = $this->db->query("SELECT  @rownum:=@rownum+1 urutan, nama_customer, amount, jml_transaksi
			FROM (
					SELECT if(penjualan_type_id=3,'non customer',concat(tbl_c.nama,' ',ifnull(tipe_company,''))) as nama_customer, sum(amount) as amount, sum(1) as jml_transaksi
					FROM (
						SELECT *
						FROM nd_penjualan
						WHERE YEAR(tanggal) = '$year'
						AND status_aktif = 1
						) as tbl_a
					LEFT JOIN (
						SELECT sum(qty*harga_jual) as amount, penjualan_id
						FROM nd_penjualan_detail
						LEFT JOIN (
							SELECT sum(qty*if(jumlah_roll=0,1,jumlah_roll)) as qty, penjualan_detail_id
							FROM nd_penjualan_qty_detail
							GROUP BY penjualan_detail_id
							) as nd_penjualan_qty_detail
						ON nd_penjualan_detail.id = nd_penjualan_qty_detail.penjualan_detail_id
						WHERE barang_id = $barang_id
						AND warna_id=$warna_id
						GROUP BY penjualan_id
						) as tbl_b
					ON tbl_a.id = tbl_b.penjualan_id
					LEFT JOIN nd_customer as tbl_c
					ON tbl_a.customer_id = tbl_c.id
					WHERE customer_id is not null
					AND tbl_b.penjualan_id is not null
					group by customer_id
				)result, (SELECT @rownum:=0) r
				order by amount desc
			");
		return $query->result();
	}


//===========================================best buyer===========================================

	function get_customer_beli_terbanyak($year)
	{
		$query = $this->db->query("SELECT *
			FROM (
				(
					SELECT tbl_c.nama as nama_customer, sum(amount) as amount
					FROM (
						SELECT *
						FROM nd_penjualan
						WHERE YEAR(tanggal) = '$year'
						AND status_aktif = 1
						) as tbl_a
					LEFT JOIN (
						SELECT sum(qty*harga_jual) as amount, penjualan_id
						FROM nd_penjualan_detail
						LEFT JOIN (
							SELECT sum(qty*if(jumlah_roll=0,1,jumlah_roll)) as qty, penjualan_detail_id
							FROM nd_penjualan_qty_detail
							GROUP BY penjualan_detail_id
							) as nd_penjualan_qty_detail
						ON nd_penjualan_detail.id = nd_penjualan_qty_detail.penjualan_detail_id
						GROUP BY penjualan_id
						) as tbl_b
					ON tbl_a.id = tbl_b.penjualan_id
					LEFT JOIN nd_customer as tbl_c
					ON tbl_a.customer_id = tbl_c.id
					WHERE customer_id is not null
					AND customer_id is not null
					AND penjualan_type_id != 3
					group by customer_id
					order by amount desc
					limit 10
				)UNION(
					SELECT 'lain-lain', sum(amount)
					FROM (
						SELECT tbl_c.nama as nama_customer, sum(amount) as amount, sum(1) as jml_transaksi
						FROM (
							SELECT *
							FROM nd_penjualan
							WHERE YEAR(tanggal) = '$year'
							AND status_aktif = 1
							) as tbl_a
						LEFT JOIN (
							SELECT sum(qty*harga_jual) as amount, penjualan_id
							FROM nd_penjualan_detail
							LEFT JOIN (
								SELECT sum(qty*if(jumlah_roll=0,1,jumlah_roll)) as qty, penjualan_detail_id
								FROM nd_penjualan_qty_detail
								GROUP BY penjualan_detail_id
								) as nd_penjualan_qty_detail
							ON nd_penjualan_detail.id = nd_penjualan_qty_detail.penjualan_detail_id
							GROUP BY penjualan_id
							) as tbl_b
						ON tbl_a.id = tbl_b.penjualan_id
						LEFT JOIN nd_customer as tbl_c
						ON tbl_a.customer_id = tbl_c.id
						WHERE customer_id is not null
						AND customer_id is not null
						AND penjualan_type_id != 3
						group by customer_id
						order by amount desc
						limit 1000
						OFFSET 10
					)tA
				)
			)result
			");
		return $query->result();
	}

	function get_customer_beli_terbanyak_all($year)
	{
		$query = $this->db->query("SELECT  @rownum:=@rownum+1 urutan, nama_customer, amount, jml_transaksi, customer_id
			FROM (
					SELECT concat(tbl_c.nama,' ',ifnull(tipe_company,'')) as nama_customer, sum(amount) as amount, sum(1) as jml_transaksi, customer_id
					FROM (
						SELECT *
						FROM nd_penjualan
						WHERE YEAR(tanggal) = '$year'
						AND status_aktif = 1
						) as tbl_a
					LEFT JOIN (
						SELECT sum(qty*harga_jual) as amount, penjualan_id
						FROM nd_penjualan_detail
						LEFT JOIN (
							SELECT sum(qty*if(jumlah_roll=0,1,jumlah_roll)) as qty, penjualan_detail_id
							FROM nd_penjualan_qty_detail
							GROUP BY penjualan_detail_id
							) as nd_penjualan_qty_detail
						ON nd_penjualan_detail.id = nd_penjualan_qty_detail.penjualan_detail_id
						GROUP BY penjualan_id
						) as tbl_b
					ON tbl_a.id = tbl_b.penjualan_id
					LEFT JOIN nd_customer as tbl_c
					ON tbl_a.customer_id = tbl_c.id
					WHERE customer_id is not null
					AND customer_id is not null
					AND penjualan_type_id != 3
					group by customer_id
				)result, (SELECT @rownum:=0) r
				order by amount desc
			");
		return $query->result();
	}

	function get_customer_beli_terbanyak_pie($year)
	{
		$query = $this->db->query("SELECT *
			FROM(
			(
				SELECT tbl_c.nama as nama_customer, sum(amount) as amount 
				FROM (
					SELECT *
					FROM nd_penjualan
					WHERE YEAR(tanggal) = '$year'
					AND status_aktif = 1
					) as tbl_a
				LEFT JOIN (
					SELECT sum(qty*harga_jual) as amount, penjualan_id
					FROM nd_penjualan_detail
					LEFT JOIN (
						SELECT sum(qty*if(jumlah_roll=0,1,jumlah_roll)) as qty, penjualan_detail_id
						FROM nd_penjualan_qty_detail
						GROUP BY penjualan_detail_id
						) as nd_penjualan_qty_detail
					ON nd_penjualan_detail.id = nd_penjualan_qty_detail.penjualan_detail_id
					GROUP BY penjualan_id
					) as tbl_b
				ON tbl_a.id = tbl_b.penjualan_id
				LEFT JOIN nd_customer as tbl_c
				ON tbl_a.customer_id = tbl_c.id
				WHERE customer_id is not null
				AND customer_id is not null
				group by customer_id
				order by amount desc
				limit 10

				)UNION(
				SELECT 'other' as nama_customer, sum(amount)
				FROM(
					SELECT tbl_c.nama as nama_customer, sum(amount) as amount 
					FROM (
						SELECT *
						FROM nd_penjualan
						WHERE YEAR(tanggal) = '$year'
						AND status_aktif = 1
						) as tbl_a
					LEFT JOIN (
						SELECT sum(qty*harga_jual) as amount, penjualan_id
						FROM nd_penjualan_detail
						LEFT JOIN (
							SELECT sum(qty*if(jumlah_roll=0,1,jumlah_roll)) as qty, penjualan_detail_id
							FROM nd_penjualan_qty_detail
							GROUP BY penjualan_detail_id
							) as nd_penjualan_qty_detail
						ON nd_penjualan_detail.id = nd_penjualan_qty_detail.penjualan_detail_id
						GROUP BY penjualan_id
						) as tbl_b
					ON tbl_a.id = tbl_b.penjualan_id
					LEFT JOIN nd_customer as tbl_c
					ON tbl_a.customer_id = tbl_c.id
					WHERE customer_id is not null
					AND customer_id is not null
					group by customer_id
					order by amount desc
					limit 10, 10000000
					) tbl_a
				)
			) A
			");
		return $query->result();
	}

	function get_noncustomer_totalbeli($year){
		$query = $this->db->query("SELECT sum(amount) as amount, sum(1) as jml_transaksi
					FROM (
						SELECT *
						FROM nd_penjualan
						WHERE YEAR(tanggal) = '$year'
						AND status_aktif = 1
						AND closed_by is not null
						AND penjualan_type_id = 3
						) as tbl_a
					LEFT JOIN (
						SELECT sum(qty*harga_jual) as amount, penjualan_id
						FROM nd_penjualan_detail
						LEFT JOIN (
							SELECT sum(qty*if(jumlah_roll=0,1,jumlah_roll)) as qty, penjualan_detail_id
							FROM nd_penjualan_qty_detail
							GROUP BY penjualan_detail_id
							) as nd_penjualan_qty_detail
						ON nd_penjualan_detail.id = nd_penjualan_qty_detail.penjualan_detail_id
						GROUP BY penjualan_id
						) as tbl_b
					ON tbl_a.id = tbl_b.penjualan_id
					
			");
		return $query->result();
	}

//==============================================================================

	function get_notifikasi_akunting_report(){
		$query = $this->db->query("SELECT t1.*, t2.nama as nama_customer, username
			FROM nd_notifikasi_akunting t1
			LEFT JOIN nd_customer t2
			ON t1.customer_id = t2.id
			LEFT JOIN nd_user t3
			ON t1.read_by = t3.id
			");
		return $query->result();
	}

//============================faktur kosong=====================================

	function get_notifikasi_faktur_kosong(){
		$today = date('Y-m-d');
		$twodaysago = date('Y-m-d', strtotime('-2 days ago'));

		$query = $this->db->query("SELECT *
		FROM (
				SELECT *
				FROM nd_penjualan
				WHERE tanggal < '$today'
				AND tanggal >= '$twodaysago'
				AND no_faktur is null
				AND status_aktif = 1
				) t1
			LEFT JOIN (
			SELECT sum(1) as count_data, penjualan_id
				FROM nd_penjualan_detail
				GROUP BY penjualan_id
				) t2
			
			ON t2.penjualan_id = t1.id
			WHERE count_data is not null
			");
		return $query->result();
	}

	//=====================================================Stok inventory======================================

	function get_batch_for_pre_po($barang_id){
		$query = $this->db->query("SELECT po_number, batch, po_pembelian_id, po_pembelian_batch_id, barang_id, tanggal
			FROM (
				SELECT (a.qty - ifnull(h.qty,0)-ifnull(qty_beli,0)) as qty_sisa, a.barang_id , a.warna_id, b.po_pembelian_id, concat(if(e.tanggal >= '2019-09-27', concat(ifnull(pre_po,''),g.kode,'/',DATE_FORMAT(e.tanggal,'%y'),'/',LPAD(po_number,3,'0')) ,concat(LPAD(po_number,3,'0'),'/',DATE_FORMAT(e.tanggal,'%m'),'/',DATE_FORMAT(e.tanggal,'%y'))),'-',batch,if(revisi > 1,concat('R',revisi-1),'') ) as batch, e.po_number, a.po_pembelian_batch_id, b.tanggal
				FROM (
					SELECT *
					FROM (
						SELECT t1.id, po_pembelian_detail_id, t1.qty, if(tipe_barang = 1, t2.barang_id, barang_id_baru) as barang_id, po_pembelian_id, warna_id, po_pembelian_batch_id
						FROM nd_po_pembelian_warna t1
						LEFT JOIN nd_po_pembelian_detail t2
						ON t1.po_pembelian_detail_id = t2.id
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
				LEFT JOIN nd_po_pembelian e
				ON b.po_pembelian_id = e.id
				LEFT JOIN nd_toko f
				ON e.toko_id = f.id
				LEFT JOIN nd_supplier g
				ON e.supplier_id = g.id
				LEFT JOIN nd_po_pembelian_before_qty h
				ON a.id = h.po_pembelian_warna_id
				WHERE (a.qty - ifnull(h.qty,0)-ifnull(qty_beli,0)) >= 0
				AND b.id is not null
				GROUP BY a.po_pembelian_batch_id
			)t1
			WHERE barang_id = $barang_id
			GROUP BY po_pembelian_batch_id");
		return $query->result();
	}

	function get_stok_for_pre_po($barang_id_pool, $tanggal_akhir, $tanggal_awal, $stok_opname_id, $cond_barang){
		$query = $this->db->query("SELECT barang_id, warna_id, sum(qty_stok) as qty_stok, sum(jumlah_roll_stok) as jumlah_roll_stok, sum(qty_po) as qty_po, group_concat(qty_po_data ) as qty_po_data, sum(if(qty_sisa_po < 0, 0, qty_sisa_po) ) as qty_sisa, group_concat(qty_sisa_data ) as qty_sisa_data, group_concat(batch_id ) as batch_id, group_concat(po_pembelian_id  ) as po_pembelian_id, b.nama as nama_barang, c.warna_beli as nama_warna, d.nama as nama_satuan, group_concat(ifnull(harga,0)) as harga_po, group_concat(ifnull(OCKH,if(OCKH != '',OCKH,0))) as OCKH, group_concat(locked_by) as locked_by
				FROM((
						SELECT barang_id, warna_id, ROUND(
								sum(if(tanggal_stok is not null,if(tanggal >= tanggal_stok, qty_masuk,0) ,qty_masuk))  - sum(if(tanggal_stok is not null,if(tanggal >= tanggal_stok,qty_keluar,0), qty_keluar))
							,2) as qty_stok,
		 					sum(if(tanggal_stok is not null,if(tanggal >= tanggal_stok,jumlah_roll_masuk,0), jumlah_roll_masuk)) - sum(if(tanggal_stok is not null,if(tanggal >= tanggal_stok,jumlah_roll_keluar,0), jumlah_roll_keluar)) as jumlah_roll_stok,
		 					0 as qty_po, 0 as qty_po_data, 0 as qty_sisa_po, 0 as qty_sisa_data, 0 as po_pembelian_id, 0 as batch_id, 0 as harga,0 as OCKH,0 as locked_by
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
						SELECT barang_id, warna_id, 0 ,0, sum(qty_po), group_concat(qty_po) ,sum(qty_sisa), group_concat(qty_sisa_data), group_concat(po_pembelian_id) as po_pembelian_id, group_concat(batch_id) as batch_id, group_concat(harga), group_concat(ifnull(OCKH,0)), group_concat(locked_by) as locked_by
						FROM (
							SELECT sum(a.qty-ifnull(qty_beli,0)) as qty_sisa, a.barang_id as barang_id, a.warna_id, a.po_pembelian_id, group_concat(a.po_pembelian_batch_id) as batch_id, group_concat(if(locked_by is null,a.qty-ifnull(qty_beli,0) + ifnull(qty_retur,0) ,0)) as qty_sisa_data, group_concat(harga) as harga, group_concat(a.qty) as qty_po, group_concat(ifnull(OCKH,0) ) as OCKH, group_concat(ifnull(qty_beli,0)) as qty_beli, group_concat(ifnull(locked_by,0)) as locked_by
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


}

?>