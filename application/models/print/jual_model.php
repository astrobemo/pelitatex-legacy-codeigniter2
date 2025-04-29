<?php

class Jual_Model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->db->query("SET SESSION time_zone = '+7:00'");
	}

	// ambil toko
	function get_profile()
	{
		$query = $this->db->query("SELECT
								A.*
							FROM
								nd_toko A
							WHERE
								A.status_aktif = 1");

		return $query;
	}

	//==========================================================
	// penjualan : invoice, sj, sj non, packing list
	//==========================================================
	function get_header($id)
	{
		// $query = $this->db->query("SELECT
		// 						CONCAT('SJ', RIGHT(CONCAT('00', date_format(A.tanggal, '%d')), 2), RIGHT(CONCAT('00', date_format(A.tanggal, '%m')), 2), RIGHT(CONCAT('00', date_format(A.tanggal, '%y')), 2), '-', RIGHT(CONCAT('00000', A.no_faktur), 5)) AS no_sj,
		// 						CONCAT('FPJ', RIGHT(CONCAT('00', date_format(A.tanggal, '%d')), 2), RIGHT(CONCAT('00', date_format(A.tanggal, '%m')), 2), RIGHT(CONCAT('00', date_format(A.tanggal, '%y')), 2), '-', RIGHT(CONCAT('00000', A.no_faktur), 5)) AS no_invoice,
		// 						CONCAT('PL', RIGHT(CONCAT('00', date_format(A.tanggal, '%d')), 2), RIGHT(CONCAT('00', date_format(A.tanggal, '%m')), 2), RIGHT(CONCAT('00', date_format(A.tanggal, '%y')), 2), '-', RIGHT(CONCAT('00000', A.no_faktur), 5)) AS no_pl,
		// 						A.po_number,		
		// 						A.tanggal,
		// 						CONCAT(IFNULL(CONCAT(B.tipe_company,' '),''),COALESCE(UPPER(B.nama), '')) AS nama_customer,
		// 						UPPER(CONCAT(COALESCE(B.alamat, ''),if(B.Blok = '-','', CONCAT(' ',B.Blok)), if(B.no = '-','',CONCAT(' no.',B.No)) )) AS alamat_customer,
		// 						CONCAT(IF(B.alamat = '-', '', B.alamat),
		// 							 IF(B.blok = '-', '', CONCAT(' BLOK ', B.blok)),
		// 							 IF(B.no = '-', '', CONCAT(' NO.', B.no)),
		// 							 IF(B.rt = '000', '', CONCAT(' RT', B.rt)),
		// 							 IF(B.rw = '000', '', CoNCAT(' RW', B.rw)),
		// 							 IF(B.kelurahan = '-', '', CONCAT(' ,', B.kelurahan)),
		// 							 IF(B.kecamatan = '-', '', CONCAT(' ', B.kecamatan)),
		// 							 IF(B.kota = '-', '', CONCAT(', ', B.kota))
		// 							 ) AS alamat_customer_lengkap,

		// 						IF(B.rt = '000','', COALESCE(B.rt, '')) AS rt_customer,
		// 						IF(B.rw = '000','', COALESCE(B.rw, '')) AS rw_customer,
		// 						COALESCE(B.kota, '') AS kota_customer,
		// 						COALESCE(B.provinsi, '') AS provinsi_customer,
		// 						A.penjualan_type_id,
		// 						UPPER(A.nama_keterangan) as nama_keterangan,
		// 						UPPER(A.alamat_keterangan) as alamat_keterangan
		// 					FROM
		// 						nd_penjualan A LEFT JOIN
		// 						nd_customer B ON A.customer_id = B.id
		// 						LEFT JOIN nd_surat_jalan C
		// 						ON A.id = C.penjualan_id
		// 					WHERE
		// 						A.id = '" . $id . "'");

		// return $query;

		$query = $this->db->query("SELECT
								A.no_sj_lengkap AS no_sj,
								A.no_faktur_lengkap AS no_invoice,
								A.no_packing_list AS no_pl,
								A.po_number,		
								A.tanggal,
								A.keterangan,
								CONCAT(IFNULL(CONCAT(B.tipe_company,' '),''),COALESCE(UPPER(B.nama), '')) AS nama_customer,
								UPPER(CONCAT(COALESCE(B.alamat, ''),if(B.Blok = '-','', CONCAT(' ',B.Blok)), if(B.no = '-','',CONCAT(' no.',B.No)) )) AS alamat_customer,
								CONCAT(IF(B.alamat = '-', '', B.alamat),
									 IF(B.blok = '-', '', CONCAT(' BLOK ', B.blok)),
									 IF(B.no = '-', '', CONCAT(' NO.', B.no)),
									 IF(B.rt = '000', '', CONCAT(' RT', B.rt)),
									 IF(B.rw = '000', '', CoNCAT(' RW', B.rw)),
									 IF(B.kelurahan = '-', '', CONCAT(' ,', B.kelurahan)),
									 IF(B.kecamatan = '-', '', CONCAT(' ', B.kecamatan)),
									 IF(B.kota = '-', '', CONCAT(', ', B.kota))
									 ) AS alamat_customer_lengkap,

								IF(B.rt = '000','', COALESCE(B.rt, '')) AS rt_customer,
								IF(B.rw = '000','', COALESCE(B.rw, '')) AS rw_customer,
								COALESCE(B.kota, '') AS kota_customer,
								COALESCE(B.provinsi, '') AS provinsi_customer,
								A.penjualan_type_id,
								UPPER(A.nama_keterangan) as nama_keterangan,
								UPPER(A.alamat_keterangan) as alamat_keterangan
							FROM
								vw_penjualan_data A LEFT JOIN
								nd_customer B ON A.customer_id = B.id
							WHERE
								A.id = '" . $id . "'");

		return $query;
	}

	function get_pengiriman($pengiriman_id)
	{
		$query = $this->db->query("SELECT
								A.*
							FROM
								nd_customer_alamat_kirim A
							WHERE
								A.id = '" . $pengiriman_id . "'");

		return $query;
	}

	function get_barang($id)
	{
		$query = $this->db->query("SELECT
								SUM(A.subqty) AS qty,
								UPPER(C.nama) AS satuan,
								SUM(A.subjumlah_roll) AS roll,
								UPPER(B.nama_jual) AS nama_barang,
								UPPER(ifnull(nama_jual_tercetak,B.nama_jual)) AS nama_jual,
								UPPER(kode_beli) AS kode_beli,
								A.harga_jual AS harga,
								SUM(A.subqty * A.harga_jual) AS total_harga
							FROM
								nd_penjualan_detail A INNER JOIN
								nd_barang B ON A.barang_id = B.id INNER JOIN
								nd_satuan C ON B.satuan_id = C.id 
							WHERE
								A.penjualan_id = '" . $id . "'
							GROUP BY
								C.nama,
								B.nama_jual,
								A.harga_jual
							ORDER BY
								B.nama_jual");

		return $query;
	}

	function get_unit($id)
	{
		$query = $this->db->query("SELECT
								C.nama AS satuan,
								SUM(A.subqty) AS jumlah,
								SUM(A.subjumlah_roll) AS roll
							FROM
								nd_penjualan_detail A INNER JOIN
								nd_barang B ON A.barang_id = B.id INNER JOIN
								nd_satuan C ON B.satuan_id = C.id 
							WHERE
								A.penjualan_id = '" . $id . "'
							GROUP BY
								C.nama");

		return $query;
	}

	function get_bayar($id)
	{
		$query = $this->db->query("SELECT
								A.*,
								B.nama AS nama_tipe_pembayaran
							FROM
								nd_pembayaran_penjualan A INNER JOIN
								nd_pembayaran_type B ON A.pembayaran_type_id = B.id
							WHERE
								A.penjualan_id = '" . $id . "'
								AND amount > 0
							ORDER BY
								A.id");
		return $query;
	}

	function get_packing_list($id)
	{
		$query = $this->db->query("SELECT
								UPPER(C.nama_jual) AS kode,
								UPPER(ifnull(nama_jual_tercetak,C.nama_jual)) AS kode_jual,
								UPPER(kode_beli) AS kode_beli,
								UPPER(D.warna_jual) AS warna,
								UPPER(E.nama) AS satuan,
								F.roll,
								F.total,
								SUM(IF(A.jumlah_roll = 0, 1, A.jumlah_roll)) AS jumlah_roll,
								A.qty,
								IF(A.qty = 100, '1', '2') AS setor
							FROM
								nd_penjualan_qty_detail A INNER JOIN
								nd_penjualan_detail B ON A.penjualan_detail_id = B.id INNER JOIN
								nd_barang C ON B.barang_id = C.id INNER JOIN
								nd_warna D ON B.warna_id = D.id INNER JOIN
								nd_satuan E ON C.satuan_id = E.id iNNER JOIN
								(
									SELECT
										AB.penjualan_id,
										AB.barang_id,
										AB.warna_id,
										SUM(AA.jumlah_roll) AS roll,
										SUM(IF(AA.jumlah_roll = 0, 1, AA.jumlah_roll) * AA.qty) AS total

									FROM
										nd_penjualan_qty_detail AA INNER JOIN
										nd_penjualan_detail AB ON AA.penjualan_detail_id = AB.id
									WHERE
										AB.penjualan_id = '$id'
									GROUP BY
										AB.barang_id,
										AB.warna_id
								) AS F ON B.penjualan_id = F.penjualan_id AND B.barang_id = F.barang_id AND B.warna_id = F.warna_id
							WHERE
								B.penjualan_id = '$id'
							GROUP BY
								B.barang_id,
								B.warna_id,
								A.qty
							ORDER BY
								C.nama_jual,
								B.warna_id,
								IF(A.qty = 100, '1', '2'),
								A.qty ASC");

		return $query;
	}
}
