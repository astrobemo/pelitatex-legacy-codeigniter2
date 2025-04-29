<?php

class Pengeluaran_rupa_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->db->query("SET SESSION time_zone = '+7:00'");
	}

	// list
	function get_list()
	{
		$query = $this->db->query("SELECT
								AA.*,
								COALESCE(AB.grand_total, 0) AS grand_total
							FROM
								nd_pengeluaran_stok_lain AA LEFT JOIN
								(
									SELECT
										A.pengeluaran_stok_lain_id,
										A.harga_jual * (B.qty * IF(B.jumlah_roll = 0, 1, B.jumlah_roll)) AS grand_total
									FROM
										nd_pengeluaran_stok_lain_detail A INNER JOIN
										nd_pengeluaran_stok_lain_qty_detail B ON A.id = B.pengeluaran_stok_lain_detail_id
									GROUP BY
										A.pengeluaran_stok_lain_id
								) AS AB ON AA.id = AB.pengeluaran_stok_lain_id
							ORDER BY
								AA.id");

		return $query->result();
	}

	function get_list_ajax($aColumns, $sWhere, $sOrder, $sLimit)
	{

		$query = $this->db->query("SELECT
								AA.*,
								COALESCE(AB.grand_total, 0) AS grand_total,
								concat_ws('??', AA.id, AA.no_faktur) as status_data
							FROM
								nd_pengeluaran_stok_lain AA LEFT JOIN
								(
									SELECT
										A.pengeluaran_stok_lain_id,
										A.harga_jual * (B.qty * IF(B.jumlah_roll = 0, 1, B.jumlah_roll)) AS grand_total
									FROM
										nd_pengeluaran_stok_lain_detail A INNER JOIN
										nd_pengeluaran_stok_lain_qty_detail B ON A.id = B.pengeluaran_stok_lain_detail_id
									GROUP BY
										A.pengeluaran_stok_lain_id
								) AS AB ON AA.id = AB.pengeluaran_stok_lain_id
							$sWhere
							ORDER BY
								AA.id DESC
							$sLimit");

		return $query;
	}

	// barang
	function get_barang($id)
	{
		$query = $this->db->query("SELECT
								A.*,
								B.nama_jual AS nama_barang,
								C.nama AS nama_satuan,
								D.nama AS nama_gudang,
								E.warna_jual AS nama_warna,
								COALESCE(F.total_qty, 0) AS qty,
								COALESCE(F.total_roll, 0) AS jumlah_roll
							FROM
								nd_pengeluaran_stok_lain_detail A INNER JOIN
								nd_barang B ON A.barang_id = B.id INNER JOIN
								nd_satuan C ON B.satuan_id = C.id INNER JOIN
								nd_gudang D ON A.gudang_id = D.id INNER JOIN
								nd_warna E ON A.warna_id = E.id LEFT JOIN
								(
									SELECT
										AA.pengeluaran_stok_lain_detail_id,
										SUM(AA.qty * IF(AA.jumlah_roll = 0, 1, AA.jumlah_roll)) AS total_qty,
										SUM(IF(AA.jumlah_roll = 0, 1, AA.jumlah_roll)) AS total_roll
									FROM
										nd_pengeluaran_stok_lain_qty_detail AA INNER JOIN
										nd_pengeluaran_stok_lain_detail AB ON AA.pengeluaran_stok_lain_detail_id = AB.id 
									GROUP BY
										AA.pengeluaran_stok_lain_detail_id
								) AS F ON A.id = F.pengeluaran_stok_lain_detail_id
							WHERE
								A.pengeluaran_stok_lain_id = '" . $id . "'");

		return $query->result();
	}

	// packing list
	function get_pl($detail_id)
	{
		$query = $this->db->query("SELECT
								A.*
							FROM
								nd_pengeluaran_stok_lain_qty_detail A INNER JOIN
								nd_pengeluaran_stok_lain_detail B ON A.pengeluaran_stok_lain_detail_id = B.id
							WHERE
								A.pengeluaran_stok_lain_detail_id = '" . $detail_id . "'
							ORDER BY
								IF(A.qty = 100, 1, 2) ASC,
								A.qty DESC");

		return $query->result_array();
	}
}