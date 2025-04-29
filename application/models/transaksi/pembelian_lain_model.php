<?php

class Pembelian_lain_model extends CI_Model
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
								A.status_aktif,
								A.id,
								A.tanggal,
								A.no_faktur,
								IF(A.supplier_id = '', A.supplier_nama, B.nama) AS supplier,
								A.keterangan,
								D.grand_total,
								A.status,
								concat_ws('??', A.id, A.no_faktur) as status_data
							FROM
								nd_pembelian_lain A LEFT JOIN
								nd_supplier B ON A.supplier_id = B.id LEFT JOIN
								(
									SELECT
										AA.pembelian_lain_id,
										SUM(AA.qty * AA.harga) AS grand_total
									FROM
										nd_pembelian_lain_detail AA
									GROUP BY
										AA.pembelian_lain_id
								) AS D ON A.id = D.pembelian_lain_id
							WHERE
								A.status = 1
							ORDER BY
								A.id");

		return $query->result();
	}

	function get_list_ajax($aColumns, $sWhere, $sOrder, $sLimit)
	{

		$query = $this->db->query("SELECT
								A.status_aktif,
								A.id,
								A.tanggal,
								A.no_faktur,
								IF(A.supplier_id = '', A.supplier_nama, B.nama) AS supplier,
								A.keterangan,
								D.grand_total,
								A.status,
								concat_ws('??', A.id, A.no_faktur) as status_data
							FROM
								nd_pembelian_lain A LEFT JOIN
								nd_supplier B ON A.supplier_id = B.id LEFT JOIN
								(
									SELECT
										AA.pembelian_lain_id,
										SUM(AA.qty * AA.harga) AS grand_total
									FROM
										nd_pembelian_lain_detail AA
									GROUP BY
										AA.pembelian_lain_id
								) AS D ON A.id = D.pembelian_lain_id
							$sWhere
							ORDER BY
								A.id DESC
							$sLimit");

		return $query;
	}

	function get_header($id)
	{
		$query = $this->db->query("SELECT
								A.*,
								IF(A.supplier_id = '', A.supplier_nama, B.nama) AS supplier_nama,
								IF(A.supplier_id = '', A.supplier_alamat, B.alamat) AS supplier_alamat,
								IF(A.supplier_id = '', A.supplier_telepon, B.telepon) AS supplier_telepon
							FROM
								nd_pembelian_lain A LEFT JOIN
								nd_supplier B ON A.supplier_id = B.id LEFT JOIN
								(
									SELECT
										AA.pembelian_lain_id,
										SUM(AA.qty * AA.harga) AS grand_total
									FROM
										nd_pembelian_lain_detail AA
									GROUP BY
										AA.pembelian_lain_id
								) AS D ON A.id = D.pembelian_lain_id
							WHERE
								A.id = '" . $id . "'");

		return $query->result();
	}

	// barang
	function get_barang($id)
	{
		$query = $this->db->query("SELECT
								A.*
							FROM
								nd_pembelian_lain_detail A
							WHERE
								A.pembelian_lain_id = '" . $id . "'");

		return $query->result();
	}
}