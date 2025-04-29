<?php

class Migration_Model extends CI_Model {

//===============================Get Hutang Awal======================================

	function generate_hutang_awal($tanggal){
		$query = $this->db->query("SELECT t1.pembelian_id, tanggal, total, no_faktur, amount_bayar, jatuh_tempo, jumlah_roll, supplier_id, pembayaran_hutang_id
			FROM (
				SELECT tA.id as pembelian_id, tanggal, total, no_faktur, supplier_id, tanggal as jatuh_tempo, jumlah_roll
				FROM (
					SELECT *
					FROM nd_pembelian
					WHERE status_aktif = 1
					AND tanggal <= '$tanggal'
					)tA
				LEFT JOIN (
					SELECT pembelian_id, sum(qty * harga_beli) as total, sum(jumlah_roll) as jumlah_roll
					FROM nd_pembelian_detail
					GROUP BY pembelian_id
					) tB
				ON tA.id = tB.pembelian_id
				WHERE total > 0
				)t1
			LEFT JOIN (
				SELECT tA.id, tC.pembayaran_hutang_id, pembelian_id, tA.amount as amount_bayar
				FROM nd_pembayaran_hutang_detail tA
				LEFT JOIN nd_pembayaran_hutang tB
				ON tA.pembayaran_hutang_id = tB.id
				LEFT JOIN (
					SELECT pembayaran_hutang_id, amount, MIN(tanggal_transfer)
					FROM nd_pembayaran_hutang_nilai
					WHERE tanggal_transfer <= '$tanggal'
					GROUP BY pembayaran_hutang_id
					) tC
				ON tA.pembayaran_hutang_id = tC.pembayaran_hutang_id
				WHERE tC.pembayaran_hutang_id is not null
				) t2
			ON t1.pembelian_id = t2.pembelian_id
			WHERE total - ifnull(amount_bayar,0) > 0
			");
		return $query->result();
	}

	function generate_hutang_awal_terbayar($tanggal)
	{
		$query = $this->db->query("SELECT t1.pembelian_id, tanggal, total, no_faktur, ifnull(amount_bayar,0) as amount_bayar, jatuh_tempo, jumlah_roll, supplier_id
			FROM (
				SELECT tA.id as pembelian_id, tanggal, total, no_faktur, supplier_id, tanggal as jatuh_tempo, jumlah_roll
				FROM (
					SELECT *
					FROM nd_pembelian
					WHERE status_aktif = 1
					AND tanggal <= '$tanggal'
					)tA
				LEFT JOIN (
					SELECT pembelian_id, sum(qty * harga_beli) as total, sum(jumlah_roll) as jumlah_roll
					FROM nd_pembelian_detail
					GROUP BY pembelian_id
					) tB
				ON tA.id = tB.pembelian_id
				WHERE total > 0
				)t1
			LEFT JOIN (
				SELECT tA.id, tC.pembayaran_hutang_id, pembelian_id, tA.amount as amount_bayar
				FROM nd_pembayaran_hutang_detail tA
				LEFT JOIN nd_pembayaran_hutang tB
				ON tA.pembayaran_hutang_id = tB.id
				LEFT JOIN (
					SELECT pembayaran_hutang_id, amount, MIN(tanggal_transfer)
					FROM nd_pembayaran_hutang_nilai
					WHERE tanggal_transfer > '$tanggal'
					GROUP BY pembayaran_hutang_id
					) tC
				ON tA.pembayaran_hutang_id = tC.pembayaran_hutang_id
				WHERE tC.pembayaran_hutang_id is not null
				) t2
			ON t1.pembelian_id = t2.pembelian_id
			WHERE total - ifnull(amount_bayar,0) = 0
			AND pembayaran_hutang_id is not null");

		return $query->result();
	}


	function get_link_pembayaran_hutang_awal(){
		$query = $this->db->query("UPDATE nd_pembayaran_hutang_detail t1, nd_hutang_awal t2
			SET t1.data_status = 2, t1.pembelian_id = t2.id
			WHERE t2.pembelian_id_before = t1.pembelian_id
			AND t1.data_status = 1
			");
	}

//===========================================================================================

	function generate_piutang_awal($tanggal){
		$query = $this->db->query("SELECT t1.penjualan_id, tanggal, total, no_faktur, amount_bayar, jatuh_tempo, jumlah_roll, customer_id, pembayaran_piutang_id
			FROM (
				SELECT tA.id as penjualan_id, tanggal, total - ifnull(amount_bayar,0) as total, no_faktur, customer_id, tanggal as jatuh_tempo, jumlah_roll
				FROM (
					SELECT *
					FROM nd_penjualan
					WHERE status_aktif = 1
					AND tanggal <= '$tanggal'
					)tA
				LEFT JOIN (
					SELECT penjualan_id, sum(qty*if(jumlah_roll = 0 ,1,jumlah_roll) * harga_jual) as total, sum(jumlah_roll) as jumlah_roll
					FROM nd_penjualan_detail t1
					LEFT JOIN nd_penjualan_qty_detail t2
					ON t2.penjualan_detail_id = t1.id
					GROUP BY penjualan_id
					) tB
				ON tA.id = tB.penjualan_id
				LEFT JOIN (
					SELECT sum(amount) as amount_bayar, penjualan_id
					FROM nd_pembayaran_penjualan
					WHERE pembayaran_type_id != 5
					GROUP BY penjualan_id
					) tC
				ON tA.id = tC.penjualan_id
				WHERE total - ifnull(amount_bayar,0) > 0
				)t1
			LEFT JOIN (
				SELECT tA.id, tC.pembayaran_piutang_id, penjualan_id, tA.amount as amount_bayar
				FROM nd_pembayaran_piutang_detail tA
				LEFT JOIN nd_pembayaran_piutang tB
				ON tA.pembayaran_piutang_id = tB.id
				LEFT JOIN (
					SELECT pembayaran_piutang_id, amount, MIN(tanggal_transfer)
					FROM nd_pembayaran_piutang_nilai
					WHERE tanggal_transfer <= '$tanggal'
					GROUP BY pembayaran_piutang_id
					) tC
				ON tA.pembayaran_piutang_id = tC.pembayaran_piutang_id
				WHERE tC.pembayaran_piutang_id is not null
				) t2
			ON t1.penjualan_id = t2.penjualan_id
			WHERE total - ifnull(amount_bayar,0) > 0
			");
		return $query->result();
	}

	function get_link_pembayaran_piutang_awal(){
		$query = $this->db->query("UPDATE nd_pembayaran_piutang_detail t1, nd_piutang_awal t2
			SET t1.data_status = 2, t1.penjualan_id = t2.id
			WHERE t2.penjualan_id_before = t1.penjualan_id
			AND t1.data_status = 1
			");
	}

//============================================================================================

	function get_rekam_faktur_pajak_tutup_tahun($tahun){
		$query = $this->db->query("SELECT t1.*
			FROM (
				SELECT rekam_faktur_pajak_id, no_faktur_pajak_id
				FROM nd_rekam_faktur_pajak_detail
				GROUP BY rekam_faktur_pajak_id, no_faktur_pajak_id
				) t1
			LEFT JOIN nd_rekam_faktur_pajak t3
			ON t1.rekam_faktur_pajak_id = t3.id
			LEFT JOIN (
				SELECT *
				FROM nd_no_faktur_pajak
				WHERE YEAR(tahun_pajak) = '$tahun'
				)t2
			ON t1.no_faktur_pajak_id = t2.id
			WHERE t3.id is not null
			AND t2.id is not null
			");
		return $query->result();
	}

}