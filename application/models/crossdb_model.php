<?php

class Crossdb_Model extends CI_Model {
	function get_pembelian_lain_report($from, $to, $cond){
		$query = $this->db->query("SELECT 
      if(a.tipe_barang = 1, ifnull(d.qty_beli,0), ifnull(tA.qty_beli,0))  as qty_beli, 
      if(a.tipe_barang = 1, ifnull(d.barang_id,0), ifnull(tA.barang_id_baru,0))  as barang_id, 
      if(a.tipe_barang = 1,d.pembelian_id, tA.pembelian_id) as pembelian_id, 
      if(a.tipe_barang = 1,d.no_faktur, tA.no_faktur) as no_faktur, 
      if(a.tipe_barang = 1,d.tanggal_beli, tA.tanggal_beli) as tanggal_beli, 
      if(a.tipe_barang = 1,d.tanggal_datang, tA.tanggal_datang) as tanggal_datang, 
      if(a.tipe_barang = 1, ifnull(d.harga_beli,0), ifnull(tA.harga_beli,0)) as harga_beli, 
      if(a.tipe_barang = 1, a.warna_id, tA.warna_id) as warna_id,
        b.id as batch_id , ifnull(a.qty,0) as po_qty, c.id as detail_id, po_number, nama_supplier, satuan_id, 
      f.nama as nama_satuan, nama_barang, e.warna_beli as nama_warna,
      tipe_barang, nama_baru, tipe_barang, a.id as po_pembelian_warna_id, 
      DATE_FORMAT(locked_date,'%Y-%m-%d') as locked_date, username, OCKH, 
      b.id as batch_id, if(a.tipe_barang = 1,c.harga, tA.harga) as harga_po
      FROM (
        SELECT t1.*, concat(if(t2.tanggal >= '2019-09-27', concat(ifnull(pre_po,''),t4.kode,'/',DATE_FORMAT(t2.tanggal,'%y'),'/',LPAD(po_number,3,'0')) ,concat(LPAD(po_number,3,'0'),'/',DATE_FORMAT(t2.tanggal,'%m'),'/',DATE_FORMAT(t2.tanggal,'%y'))),'-',batch,if(revisi > 1,concat('R',revisi-1),'') ) as po_number, t4.nama as nama_supplier
        FROM (
          SELECT *
          FROM favourtdj_system2019.nd_po_pembelian_batch
          WHERE id = $batch_id
          ) t1
        LEFT JOIN favourtdj_system2019.nd_po_pembelian t2
        ON t1.po_pembelian_id = t2.id
        LEFT JOIN nd_toko t3
        ON t2.toko_id = t3.id
        LEFT JOIN nd_supplier t4
        ON t2.supplier_id = t4.id
        ) b
      LEFT JOIN (
        SELECT tA.*, tB.nama as nama_baru, tC.username
        FROM favourtdj_system2019.nd_po_pembelian_warna tA
        LEFT JOIN favourtdj_system2019.nd_barang tB
        ON tA.barang_id_baru = tB.id
        LEFT JOIN favourtdj_system2019.nd_user tC
        ON tA.locked_by = tC.id
        ) a
      ON a.po_pembelian_batch_id = b.id
      LEFT JOIN (
        SELECT t1.*, satuan_id, t2.nama as nama_barang
        FROM favourtdj_system2019.nd_po_pembelian_detail t1
        LEFT JOIN favourtdj_system2019.nd_barang t2
        ON t1.barang_id = t2.id
        ) c
      ON a.po_pembelian_detail_id = c.id
      LEFT JOIN (
        SELECT barang_id, warna_id, group_concat(ifnull(qty,0) ORDER BY tanggal asc) as qty_beli, po_pembelian_batch_id, group_concat(t2.id ORDER BY tanggal asc) as pembelian_id, group_concat(no_faktur ORDER BY tanggal asc) as no_faktur, group_concat(tanggal ORDER BY tanggal asc) as tanggal_beli, group_concat(harga_beli ORDER BY tanggal asc) as harga_beli, group_concat(DATE_FORMAT(tanggal,'%d/%m/%y') ORDER BY tanggal asc) as tanggal_datang
        FROM favourtdj_system2019.nd_pembelian_detail t1
        LEFT JOIN favourtdj_system2019.nd_pembelian t2
        ON t1.pembelian_id = t2.id
        GROUP BY barang_id, warna_id, po_pembelian_batch_id
        ORDER BY tanggal ASC
        ) d
      ON a.warna_id = d.warna_id
      AND b.id = d.po_pembelian_batch_id
      AND d.barang_id = c.barang_id
      LEFT JOIN (
        SELECT tA.id, qty_beli, no_faktur, tanggal_beli, tanggal_datang, pembelian_id, harga_beli, tA.harga_baru as harga, tA.warna_id, barang_id_baru
        FROM (
          SELECT *
          FROM favourtdj_system2019.nd_po_pembelian_warna
          WHERE tipe_barang != 1
          ) tA
        LEFT JOIN (
          SELECT barang_id, warna_id, group_concat(ifnull(qty,0) ORDER BY tanggal asc) as qty_beli, po_pembelian_batch_id, group_concat(t2.id ORDER BY tanggal asc) as pembelian_id, group_concat(no_faktur ORDER BY tanggal asc) as no_faktur, group_concat(tanggal ORDER BY tanggal asc) as tanggal_beli, group_concat(harga_beli ORDER BY tanggal asc) as harga_beli, group_concat(DATE_FORMAT(tanggal,'%d/%m/%y') ORDER BY tanggal asc) as tanggal_datang
          FROM favourtdj_system2019.nd_pembelian_detail t1
          LEFT JOIN favourtdj_system2019.nd_pembelian t2
          ON t1.pembelian_id = t2.id
          GROUP BY barang_id, warna_id, po_pembelian_batch_id
          ORDER BY tanggal ASC
          ) tB
        ON tA.warna_id = tB.warna_id
        AND tA.po_pembelian_batch_id = tB.po_pembelian_batch_id
        AND tA.barang_id_baru = tB.barang_id
        ) tA
      ON a.id = tA.id
      LEFT JOIN favourtdj_system2019.nd_warna e
      ON a.warna_id = e.id
      LEFT JOIN favourtdj_system2019.nd_satuan f
      ON c.satuan_id = f.id
      ORDER BY nama_barang, warna_jual asc");
		return $query->result();
	}
}