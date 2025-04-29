<?php

class Report_Model extends CI_Model {
	function get_penjualan_report_ajax($aColumns, $sWhere, $sOrder, $sLimit){
		$query = $this->db->query("SELECT *
			FROM (
				SELECT tbl_a.status_aktif, no_faktur_lengkap as no_faktur, tanggal, qty, jumlah_roll, nama_barang, harga_jual, total, diskon, ongkos_kirim, ifnull(tbl_c.nama,'no name') as nama_customer, (total_bayar - (g_total - diskon) + ongkos_kirim) as keterangan, tbl_a.id as data 
				FROM (
					SELECT *
					FROM vw_penjualan_data 
					WHERE status_aktif = 1
					ORDER BY tanggal desc
					)as tbl_a
				LEFT JOIN (
					SELECT group_concat(nama SEPARATOR '??') as nama_barang, group_concat(nd_penjualan_detail.harga_jual SEPARATOR '??') as harga_jual, group_concat(qty SEPARATOR '??') as qty ,group_concat(jumlah_roll SEPARATOR '??') as jumlah_roll, group_concat((qty *nd_penjualan_detail.harga_jual) SEPARATOR '??') as total, sum(qty *nd_penjualan_detail.harga_jual) as g_total, penjualan_id 
					FROM nd_penjualan_detail
					LEFT JOIN nd_barang
					ON nd_penjualan_detail.barang_id = nd_barang.id
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
					WHERE pembayaran_type_id !=5
					GROUP BY penjualan_id
					) as tbl_d
				ON tbl_d.penjualan_id = tbl_a.id
				

				) A			
			$sWhere
            $sOrder
            $sLimit
			", false);

		return $query;
	}

	function get_penjualan_report($cond,$gudang_cond, $barang_id_cond, $warna_id_cond, $id_list){
		$c='';
		if (is_posisi_id() == 1) {
			// $c = " AND closed_date <= '$to 13:00:00'";
		}
		$query = $this->db->query("SELECT *
			FROM (
				SELECT tbl_a.id, no_faktur as nf, tbl_a.status_aktif,  
					tanggal, qty, jumlah_roll, nama_barang, nama_barang_data, nama_jual, harga_jual, total, diskon, 
					ongkos_kirim,nama_gudang, if(customer_id != 0, tbl_c.nama, concat(nama_keterangan, ' (non-pelanggan)')) as nama_customer, 
					(ifnull(total_bayar,0) + ifnull(total_lunas,0) - (ifnull(g_total,0) - ifnull(diskon,0)) + ifnull(ongkos_kirim,0)) as keterangan, 
					tbl_a.id as data , jatuh_tempo, pembayaran_type_id, data_bayar, nama_satuan, pembayaran_piutang_id, tbl_b.satuan_id, 
					ifnull(
						ifnull(
							if(g_total - ifnull(amount_kontra_tanggung,0) = 0, 'lunas1',null),
							if(total_lunas =0, 'belum lunas',ket_lunas)
						), 
						if((ifnull(total_bayar,0) + ifnull(total_lunas,0) - (ifnull(g_total,0) - ifnull(diskon,0)) + ifnull(ongkos_kirim,0)) >= 0,'lunas1','belum lunas' ) 
					) as ket_lunas, 
					amount_kontra_tanggung, customer_id, closed_date,
					if((tbl_a.tanggal < '2022-05-01 00:00:00'),concat('FPJ',convert(date_format(tbl_a.tanggal,'%d%m%y') using latin1),'-',
					ifnull(nd_toko.pre_faktur,''),convert(lpad(tbl_a.no_faktur,5,'0') using latin1)),concat(nd_toko.pre_po,':PJ01/',
					convert(date_format(tbl_a.tanggal,'%y%m') using latin1),'/',convert(lpad(tbl_a.no_faktur,4,'0') using latin1))) AS no_faktur
					FROM (
						SELECT group_concat(concat_ws(' ',nd_barang.nama_jual,warna_jual) ORDER BY t1.id SEPARATOR '??' ) as nama_barang, 
						group_concat(concat(nd_barang.nama_jual,'||',warna_jual) ORDER BY t1.id SEPARATOR '??' ) as nama_barang_data, 

						group_concat(t1.harga_jual ORDER BY t1.id SEPARATOR '??') as harga_jual, group_concat(qty ORDER BY t1.id SEPARATOR '??') as qty ,
						group_concat(if(nd_barang.satuan_id != 3 , jumlah_roll,0) ORDER BY t1.id SEPARATOR '??') as jumlah_roll, 
						group_concat((qty * t1.harga_jual)  ORDER BY t1.id SEPARATOR '??') as total, sum(qty * t1.harga_jual) as g_total, penjualan_id, 
						-- group_concat(concat_ws(' ',nama_jual,warna_jual)  ORDER BY t1.id SEPARATOR '??') as nama_jual, 
						group_concat(nd_satuan.nama ORDER BY t1.id SEPARATOR '??') as nama_satuan, 
						group_concat(nd_gudang.nama ORDER BY t1.id SEPARATOR '??') as nama_gudang, 
						group_concat(nd_barang.satuan_id ORDER BY t1.id SEPARATOR '??') as satuan_id,
						group_concat(concat(ifnull(nama_jual_tercetak, nd_barang.nama_jual),' ',warna_jual) ORDER BY t1.id SEPARATOR '??') as nama_jual
						FROM (
							SELECT *
							FROM nd_penjualan_detail
							WHERE penjualan_id IN ( $id_list )
							$gudang_cond
							$barang_id_cond
							$warna_id_cond
							) t1
						LEFT JOIN nd_barang
						ON t1.barang_id = nd_barang.id
						LEFT JOIN nd_warna
						ON t1.warna_id = nd_warna.id
						LEFT JOIN nd_satuan
						ON nd_barang.satuan_id = nd_satuan.id
						LEFT JOIN (
							SELECT sum(qty * if(jumlah_roll = 0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, penjualan_detail_id
							FROM nd_penjualan_qty_detail
							group by penjualan_detail_id
							) t2
						ON t2.penjualan_detail_id = t1.id
						LEFT JOIN nd_gudang 
						ON t1.gudang_id = nd_gudang.id
						GROUP BY penjualan_id
					) as tbl_b
					LEFT JOIN nd_penjualan tbl_a
					ON tbl_b.penjualan_id = tbl_a.id
					LEFT JOIN (
						SELECT t2.penjualan_id, sum(ifnull(t2.amount,0)) as amount_kontra_tanggung
						FROM nd_pembayaran_piutang_detail t2
						LEFT JOIN nd_pembayaran_piutang_nilai_info t1
						ON t1.penjualan_id = t2.penjualan_id
						AND t2.id = t1.pembayaran_piutang_detail_id
						WHERE t1.id is not null
						GROUP BY penjualan_id 
						) t_add1
					ON tbl_b.penjualan_id = t_add1.penjualan_id
					LEFT JOIN nd_customer as tbl_c
					ON tbl_a.customer_id = tbl_c.id
					LEFT JOIN (
						SELECT penjualan_id, sum(if(pembayaran_type_id=5,0,amount)) as total_bayar, group_concat(amount_dt) as data_bayar, group_concat(pembayaran_type_id) as pembayaran_type_id
						FROM (
							SELECT id, penjualan_id, pembayaran_type_id, group_concat(amount SEPARATOR '??') as amount_dt, sum(amount) as amount
							FROM nd_pembayaran_penjualan
							GROUP BY penjualan_id, pembayaran_type_id
							)result
						GROUP BY penjualan_id
						) as tbl_d
					ON tbl_d.penjualan_id = tbl_a.id
					LEFT JOIN (
						SELECT penjualan_id, sum(amount) as total_lunas, group_concat(amount) as data_lunas, group_concat(pembayaran_piutang_id) as pembayaran_piutang_id, group_concat(ket_lunas) as ket_lunas
						FROM nd_pembayaran_piutang_detail t1
						LEFT JOIN (
							SELECT tA.*, if(pembayaran_detail - ifnull(pembayaran_nilai, 0) - ifnull(pembulatan,0) > 0, 'kontra', 'lunas') as ket_lunas 
							FROM nd_pembayaran_piutang tA
							LEFT JOIN (
								SELECT sum(amount) as pembayaran_nilai, pembayaran_piutang_id
								FROM nd_pembayaran_piutang_nilai
								GROUP BY pembayaran_piutang_id
								) tB
							ON tA.id = tB.pembayaran_piutang_id
							LEFT JOIN (
								SELECT pembayaran_piutang_id, sum(amount) as pembayaran_detail
								FROM nd_pembayaran_piutang_detail
								GROUP BY pembayaran_piutang_id
							)tC
							ON tA.id = tC.pembayaran_piutang_id
							) t2
						ON t1.pembayaran_piutang_id = t2.id
						WHERE t2.status_aktif != 0
						AND data_status = 1
						GROUP BY penjualan_id
						) tbl_e
					ON tbl_e.penjualan_id = tbl_a.id
					LEFT JOIN nd_toko 
					ON tbl_a.toko_id = nd_toko.id
					WHERE tbl_a.id is not null
					ORDER BY tanggal, nf asc
				)t1
				WHERE status_aktif = 1
				$cond
			

			", false);

		return $query->result();
	}

	function get_penjualan_report_pelunasan($from, $to, $cond, $customer_cond, $gudang_cond, $tipe_penjualan_cond){
		$query = $this->db->query("SELECT *
			FROM (
				SELECT tB.*,username, dp.tanggal as tanggal_dp
				FROM 
				(
						SELECT *
						FROM nd_penjualan 
						WHERE tanggal >= '$from'
						AND tanggal <= '$to'
						AND status_aktif = 1
						$customer_cond
						$tipe_penjualan_cond
						ORDER BY tanggal desc
					)tA
				LEFT JOIN (
					SELECT t3.*, penjualan_id
					FROM nd_pembayaran_piutang_detail t1
					LEFT JOIN nd_pembayaran_piutang t2
					ON t1.pembayaran_piutang_id = t2.id
					LEFT JOIN  nd_pembayaran_piutang_nilai t3
					ON t3.pembayaran_piutang_id = t2.id
					WHERE t3.amount != 0 
					) tB
				ON tA.id = tB.penjualan_id
				LEFT JOIN nd_dp_masuk dp
				ON tB.dp_masuk_id = dp.id
				LEFT JOIN nd_user tC
				ON tB.user_id = tC.id
			)t1
			WHERE penjualan_id is not null
			$cond
			

			", false);

		return $query->result();
	}

	function get_retur_report($from, $to, $customer_cond, $gudang_cond){
		$query = $this->db->query("SELECT tbl_a.id, no_faktur as nf, tbl_a.status_aktif, no_faktur_lengkap as no_faktur, tanggal, qty, jumlah_roll, nama_barang, nama_jual, harga_jual, total, nama_gudang, if(customer_id != 0, tbl_c.nama, concat(nama_keterangan, ' (non-pelanggan)')) as nama_customer, (ifnull(total_bayar,0) + ifnull(total_lunas,0) - ifnull(g_total,0)) as keterangan, tbl_a.id as data , pembayaran_type_id, data_bayar, nama_satuan, pembayaran_piutang_id, satuan_id
				FROM (
					SELECT group_concat(concat_ws(' ',nd_barang.nama,warna_jual) SEPARATOR '??') as nama_barang, group_concat(nd_retur_jual_detail.harga SEPARATOR '??') as harga_jual, group_concat(qty SEPARATOR '??') as qty ,group_concat(jumlah_roll SEPARATOR '??') as jumlah_roll, group_concat((qty *nd_retur_jual_detail.harga) SEPARATOR '??') as total, sum(qty *nd_retur_jual_detail.harga) as g_total, retur_jual_id, group_concat(concat_ws(' ',nama_jual,warna_jual) SEPARATOR '??') as nama_jual, nd_satuan.nama as nama_satuan, group_concat(nd_gudang.nama SEPARATOR '??') as nama_gudang, group_concat(nd_barang.satuan_id SEPARATOR '??') as satuan_id
					FROM (
						SELECT *
						FROM nd_retur_jual_detail
						WHERE gudang_id is not null
						$gudang_cond 
						) nd_retur_jual_detail
					LEFT JOIN nd_barang
					ON nd_retur_jual_detail.barang_id = nd_barang.id
					LEFT JOIN nd_warna
					ON nd_retur_jual_detail.warna_id = nd_warna.id
					LEFT JOIN nd_satuan
					ON nd_barang.satuan_id = nd_satuan.id
					LEFT JOIN (
						SELECT sum(qty * if(jumlah_roll = 0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, retur_jual_detail_id
						FROM nd_retur_jual_qty
						group by retur_jual_detail_id
						) as nd_retur_jual_qty
					ON nd_retur_jual_qty.retur_jual_detail_id = nd_retur_jual_detail.id
					LEFT JOIN nd_gudang 
					ON nd_retur_jual_detail.gudang_id = nd_gudang.id
					GROUP BY retur_jual_id
				) as tbl_b
				LEFT JOIN (
					SELECT t1.*
					FROM (
						SELECT *, concat('FRJ', DATE_FORMAT(tanggal,'%d%m%y'),'-',LPAD(no_faktur,5,'0')) as no_faktur_lengkap
						FROM nd_retur_jual
						WHERE tanggal >= '$from'
						AND tanggal <= '$to'
						AND status_aktif = 1
						ORDER BY tanggal desc
						) t1
					LEFT JOIN (SELECT id, fp_status from nd_penjualan) t2
					ON t1.penjualan_id = t2.id
					$customer_cond
					)as tbl_a
				ON tbl_b.retur_jual_id = tbl_a.id
				LEFT JOIN nd_customer as tbl_c
				ON tbl_a.customer_id = tbl_c.id
				LEFT JOIN (
					SELECT retur_jual_id, sum(if(pembayaran_type_id=5,0,amount)) as total_bayar, group_concat(amount) as data_bayar, group_concat(pembayaran_type_id) as pembayaran_type_id
					FROM nd_pembayaran_retur
					GROUP BY retur_jual_id
					) as tbl_d
				ON tbl_d.retur_jual_id = tbl_a.id
				LEFT JOIN (
					SELECT penjualan_id as retur_jual_id, sum(amount) as total_lunas, group_concat(amount) as data_lunas, group_concat(pembayaran_piutang_id) as pembayaran_piutang_id
					FROM nd_pembayaran_piutang_detail t1
					LEFT JOIN nd_pembayaran_piutang t2
					ON t1.pembayaran_piutang_id = t2.id
					WHERE t2.status_aktif != 0
					AND data_status = 1
					GROUP BY penjualan_id
					) tbl_e
				ON tbl_e.retur_jual_id = tbl_a.id
				WHERE tbl_a.id is not null
				ORDER BY tanggal, nf asc

			", false);

		return $query->result();
	}

	function get_penjualan_report_excel($from, $to, $cond, $customer_cond){
		$query = $this->db->query("SELECT tbl_a.status_aktif, no_faktur_lengkap as no_faktur, tanggal, qty, jumlah_roll, nama_barang, nama_jual, harga_jual, total, diskon, ongkos_kirim,  if(customer_id != 0, tbl_c.nama, concat(nama_keterangan, ' (non-pelanggan)')) as nama_customer, (ifnull(total_bayar,0) - (ifnull(g_total,0) - ifnull(diskon,0)) + ifnull(ongkos_kirim,0)) as keterangan, data_bayar, pembayaran_type_id, tbl_b.satuan_id
				FROM (
					SELECT *
					FROM vw_penjualan_data 
					WHERE tanggal >= '$from'
					AND tanggal <= '$to'
					AND status_aktif = 1
					$customer_cond
					ORDER BY tanggal desc
					)as tbl_a
				LEFT JOIN (
					SELECT group_concat(concat_ws(' ',nama,warna_beli) SEPARATOR '??') as nama_barang, group_concat(nd_penjualan_detail.harga_jual SEPARATOR '??') as harga_jual, group_concat(qty SEPARATOR '??') as qty ,group_concat(jumlah_roll SEPARATOR '??') as jumlah_roll, group_concat((qty *nd_penjualan_detail.harga_jual) SEPARATOR '??') as total, sum(qty *nd_penjualan_detail.harga_jual) as g_total, penjualan_id, group_concat(concat_ws(' ',nama_jual,warna_jual) SEPARATOR '??') as nama_jual, group_concat(nd_barang.satuan_id SEPARATOR '??') as satuan_id
					FROM nd_penjualan_detail
					LEFT JOIN nd_barang
					ON nd_penjualan_detail.barang_id = nd_barang.id
					LEFT JOIN nd_warna
					ON nd_penjualan_detail.warna_id = nd_warna.id
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
					SELECT penjualan_id, sum(amount) as total_bayar, group_concat(amount) as data_bayar, group_concat(pembayaran_type_id) as pembayaran_type_id
					FROM nd_pembayaran_penjualan
					GROUP BY penjualan_id
					) as tbl_d
				ON tbl_d.penjualan_id = tbl_a.id
				$cond
				ORDER BY no_faktur
			", false);

		return $query->result();
	}

	function get_penjualan_laba_report($from, $to, $cond, $customer_cond, $tanggal_awal, $bulan_harga){
		$tahun = date("Y", strtotime($tanggal_awal));
		$bulan = date("m", strtotime($tanggal_awal));
		$query = $this->db->query("SELECT tbl_a.id, tbl_a.status_aktif, no_faktur_lengkap as no_faktur, tanggal, qty, jumlah_roll, 
			nama_barang, harga_jual, total, diskon, ongkos_kirim, 
			if(customer_id != 0, tbl_c.nama, concat(nama_keterangan, ' (non-pelanggan)')) as nama_customer, 
			(ifnull(total_bayar,0) - (ifnull(g_total,0) - ifnull(diskon,0)) + ifnull(ongkos_kirim,0)) as keterangan, 
			tbl_a.id as data , jatuh_tempo, hpp, barang_id, warna_id, nama_barang_jual, barang_id, warna_id, ppn_berlaku
				FROM (
					SELECT t1.*, 
					(SELECT ppn FROM nd_ppn tX WHERE tX.tanggal <= t1.tanggal ORDER BY tX.tanggal DESC LIMIT 1 ) as ppn_berlaku
					FROM vw_penjualan_data t1
					WHERE tanggal >= '$from'
					AND tanggal <= '$to'
					AND status_aktif = 1
					AND no_faktur != ''
					$customer_cond
					ORDER BY tanggal desc
					)as tbl_a
				LEFT JOIN (
					SELECT group_concat(concat_ws(' ',nama,warna_jual) SEPARATOR '??') as nama_barang,
						group_concat(concat_ws(' ',nama_jual,warna_jual) SEPARATOR '??') as nama_barang_jual, 
						group_concat(t1.harga_jual SEPARATOR '??') as harga_jual, 
						group_concat(qty SEPARATOR '??') as qty ,
						group_concat(t1.barang_id SEPARATOR '??') as barang_id ,
						group_concat(t1.warna_id SEPARATOR '??') as warna_id ,
						group_concat(jumlah_roll SEPARATOR '??') as jumlah_roll, 
						group_concat((qty *t1.harga_jual) SEPARATOR '??') as total, 
						sum(qty *t1.harga_jual) as g_total, penjualan_id, 
						group_concat(concat_ws(' ',nama_jual,warna_jual) SEPARATOR '??') as nama_jual, 
						group_concat(ifnull(hpp,0) SEPARATOR '??') as hpp
					FROM nd_penjualan_detail t1
					LEFT JOIN nd_barang
					ON t1.barang_id = nd_barang.id
					LEFT JOIN nd_warna
					ON t1.warna_id = nd_warna.id
					LEFT JOIN (
						SELECT sum(qty * if(jumlah_roll = 0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, penjualan_detail_id
						FROM nd_penjualan_qty_detail
						group by penjualan_detail_id
						) as nd_penjualan_qty_detail
					ON nd_penjualan_qty_detail.penjualan_detail_id = t1.id
					LEFT JOIN (
						SELECT sum(qty_beli) as qty_beli, sum(total_beli) as total_beli, barang_id, warna_id, sum(total_beli)/sum(qty_beli) as hpp
						FROM (
							(
								SELECT sum(qty) as qty_beli, sum(qty*harga_beli) as total_beli, barang_id, warna_id
								FROM (
									SELECT *
									FROM nd_pembelian
									WHERE tanggal <= '$to'
									AND status_aktif = 1
									) t2
								LEFT JOIN nd_pembelian_detail t1
								ON t2.id = t1.pembelian_id
								-- LEFT JOIN (
								-- 	SELECT pembelian_detail_id, sum(qty * if(jumlah_roll = 0,1, jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll
								-- 	FROM nd_pembelian_qty_detail
								-- 	WHERE qty != 0
								-- 	GROUP BY pembelian_detail_id
								-- 	) t3
								-- ON t3.pembelian_detail_id = t1.id
								WHERE barang_id is not null
								GROUP BY YEAR(tanggal) , MONTH(tanggal), barang_id, warna_id
							)UNION(
								SELECT sum(qty) as qty_beli, ROUND(sum(qty*ifnull(harga_stok_awal,0)*1.1),0) as total_beli, stok_awal.barang_id, stok_awal.warna_id
								FROM (
									SELECT sum(qty) as qty, barang_id, warna_id
									FROM nd_penyesuaian_stok
									WHERE tipe_transaksi = 0
									GROUP BY barang_id, warna_id
									) stok_awal 
								LEFT JOIN nd_stok_awal_item_harga
								ON stok_awal.barang_id = nd_stok_awal_item_harga.barang_id
								GROUP BY barang_id, warna_id
							)UNION(
								SELECT sum(qty) as qty, sum(qty * (ifnull(harga,0) * (1 + (ppn_berlaku/100) ) ) ), t1.barang_id, t1.warna_id
								FROM (
									SELECT sum(qty * if(jumlah_roll = 0,1, jumlah_roll)) as qty, barang_id, warna_id, ppn_berlaku
									FROM nd_stok_opname_detail tA
									LEFT JOIN (
										SELECT *, 
										(SELECT ppn FROM nd_ppn tX WHERE tX.tanggal <= nd_stok_opname.tanggal ORDER BY tX.tanggal DESC LIMIT 1 ) as ppn_berlaku
										FROM nd_stok_opname
										WHERE YEAR(tanggal) = '$tahun'
										AND MONTH(tanggal) = '$bulan'
									) tB
									ON tA.stok_opname_id = tB.id
									WHERE tB.id is not null
									GROUP BY barang_id, warna_id, stok_opname_id, ppn_berlaku
								) t1
								LEFT JOIN (
									SELECT tahun, barang_id, warna_id, $bulan_harga as harga
									FROM nd_tutup_buku_detail
									WHERE YEAR(tahun) = '$tahun'
								)t2
								ON t1.barang_id = t2.barang_id
								AND t1.warna_id = t2.warna_id
								GROUP BY barang_id, warna_id
							) 
						)a
						GROUP BY barang_id, warna_id
					) tbl_hpp
					ON t1.barang_id = tbl_hpp.barang_id
					AND t1.warna_id = tbl_hpp.warna_id
					GROUP BY penjualan_id
					) as tbl_b
				ON tbl_b.penjualan_id = tbl_a.id
				LEFT JOIN nd_customer as tbl_c
				ON tbl_a.customer_id = tbl_c.id
				LEFT JOIN (
					SELECT penjualan_id, sum(if(pembayaran_type_id=5,0,amount)) as total_bayar, group_concat(pembayaran_type_id) as pembayaran_type_id
					FROM nd_pembayaran_penjualan
					GROUP BY penjualan_id
					) as tbl_d
				ON tbl_d.penjualan_id = tbl_a.id
				$cond
				ORDER BY no_faktur

			", false);

		return $query->result();
	}

	function get_hpp_by_mutasi($tahun, $bulan, $bulan_qty, $bulan_harga, $tanggal_start, $idx, $tgl_start_ambil){
		
		$tanggal_awal = $tahun.'-'.$bulan.'-01';
		$tanggal_awal = date("Y-m-d", strtotime($tanggal_awal)); 
		$tanggal_akhir = date("Y-m-t", strtotime($tanggal_awal)); 

		
		//=========================================================
		$tanggal_awal = $tanggal_start;
		$tanggal_akhir = date("Y-m-t", strtotime($tanggal_awal)); 
		
		$bulan_harga = date("m", strtotime($tanggal_start)).'_harga';
		$bulan_qty = date("m", strtotime($tanggal_start)).'_qty';
		$tahun = date("Y", strtotime($tanggal_start));
		
		
		
		$query = $this->db->query("SELECT res.barang_id, res.warna_id, if(hpp > 0, hpp, res2.latest_harga) as hpp
		FROM (	
			SELECT t1.barang_id, t1.warna_id, sum(ifnull(total_awal,0)) / sum(ifnull(qty_awal,0)) as hpp, sum(latest_harga) as latest_harga
				FROM (
					(
						SELECT barang_id, warna_id, $bulan_qty as qty_awal, ($bulan_harga * $bulan_qty) as total_awal, $bulan_harga as latest_harga
						FROM nd_tutup_buku_detail
						WHERE YEAR(tahun) = '$tahun'
					)
				) t1
				GROUP BY barang_id, warna_id
			)res
			LEFT JOIN (
				SELECT t1.barang_id, t1.warna_id, harga as latest_harga
				FROM vw_tutup_buku_list t1
				LEFT JOIN (
					SELECT barang_id, warna_id, max(idx) as idx
					FROM vw_tutup_buku_list
					WHERE harga > 0
					AND idx <= $idx
					GROUP BY barang_id, warna_id
				)t2
				ON t1.barang_id = t2.barang_id
				AND t1.warna_id = t2.warna_id
				AND t1.idx = t2.idx
				WHERE t2.idx is not null
			) res2
			ON res.barang_id = res2.barang_id
			AND res.warna_id = res2.warna_id

			", false);

		return $query->result();
	}

	function get_hpp_by_mutasi2($tahun, $bulan, $bulan_qty, $bulan_harga, $tanggal_start, $idx, $tgl_start_ambil){
		
		// $tanggal_awal = $tahun.'-'.$bulan.'-01';
		// $tanggal_awal = date("Y-m-d", strtotime($tanggal_awal)); 
		// $tanggal_akhir = date("Y-m-t", strtotime($tanggal_awal)); 

		$tanggal_awal = $tanggal_start;
		$tanggal_akhir = date("Y-m-t", strtotime($tanggal_awal)); 
		
		// $bulan_harga = date("m", strtotime($tanggal_start)).'_harga';
		// $bulan_qty = date("m", strtotime($tanggal_start)).'_qty';
		// $tahun = date("Y", strtotime($tanggal_start));
		
		
		
		$query = $this->db->query("SELECT res.barang_id, res.warna_id, if(hpp > 0, hpp, res2.latest_harga) as hpp
		FROM (	
			SELECT t1.barang_id, t1.warna_id, sum(ifnull(total_awal,0)) / sum(ifnull(qty_awal,0)) as hpp, sum(latest_harga) as latest_harga
				FROM (
					(
						SELECT barang_id, warna_id, $bulan_qty as qty_awal, ($bulan_harga * $bulan_qty) as total_awal, $bulan_harga as latest_harga
						FROM nd_tutup_buku_detail
						WHERE YEAR(tahun) = '$tahun'
					)UNION(
						SELECT barang_id, warna_id, sum(qty) as qty_awal, sum(qty * (harga_beli/ (1 + (ppn_berlaku/100) ) )), 0 as latest_harga
						FROM (
							SELECT *,
							(SELECT ppn FROM nd_ppn tX WHERE tX.tanggal <= nd_pembelian.tanggal ORDER BY tX.tanggal DESC LIMIT 1 ) as ppn_berlaku
							FROM nd_pembelian
							WHERE created_at >= '$tgl_start_ambil 00:00:00'	
							AND created_at <= '$tanggal_akhir 23:59:59'
							) tA
						LEFT JOIN nd_pembelian_detail tB
						ON tA.id = tB.pembelian_id
						GROUP BY barang_id, warna_id
					)
				) t1
				GROUP BY barang_id, warna_id
			)res
			LEFT JOIN (
				SELECT t1.barang_id, t1.warna_id, harga as latest_harga
				FROM vw_tutup_buku_list t1
				LEFT JOIN (
					SELECT barang_id, warna_id, max(idx) as idx
					FROM vw_tutup_buku_list
					WHERE harga > 0
					AND idx <= $idx
					GROUP BY barang_id, warna_id
				)t2
				ON t1.barang_id = t2.barang_id
				AND t1.warna_id = t2.warna_id
				AND t1.idx = t2.idx
				WHERE t2.idx is not null
			) res2
			ON res.barang_id = res2.barang_id
			AND res.warna_id = res2.warna_id

			", false);

		return $query->result();
	}

	

//==================================pembelian list==========================================

	function get_pembelian_report($cond_tanggal, $cond, $cond_barang, $cond_warna, $cond_gudang, $order_column, $cond_barang_beli){
		$query = $this->db->query("SELECT tbl_a.id, tbl_a.status_aktif, no_faktur, tanggal, qty, jumlah_roll, 
			nama_barang, nama_jual, harga_beli, total, diskon, ifnull(tbl_c.nama,'no name') as nama_supplier, 
			(ifnull(total_bayar,0) - (ifnull(g_total,0) - ifnull(diskon,0))) as keterangan, tbl_a.id as data , 
			jatuh_tempo, tbl_e.nama as nama_gudang, pembayaran_hutang_id, tanggal_bayar, supplier_id, 
			tbl_b.satuan_id, po_pembelian_batch_id, po_number, created_at, ockh, tgl_jt, amount_jt, tbl_c.kode as kode_supplier
				FROM (
					SELECT *
					FROM nd_pembelian 
					WHERE status_aktif = 1
					$cond_tanggal
					$cond
					$cond_gudang
					ORDER BY tanggal desc
					)as tbl_a
				LEFT JOIN (
					SELECT group_concat(concat_ws(' ',nd_barang_beli.nama,warna_beli) SEPARATOR '??') as nama_barang, 
					group_concat(concat_ws(' ',nama_jual,warna_jual) SEPARATOR '??') as nama_jual, 
					group_concat(t1.harga_beli SEPARATOR '??') as harga_beli, 
					group_concat(qty SEPARATOR '??') as qty ,
					group_concat(jumlah_roll SEPARATOR '??') as jumlah_roll, 
					group_concat((qty *t1.harga_beli) SEPARATOR '??') as total, 
					sum(qty *t1.harga_beli) as g_total, pembelian_id, 
					group_concat(nd_barang.satuan_id SEPARATOR '??') as satuan_id,
					group_concat(ockh SEPARATOR '??') as ockh
					FROM (
						SELECT qty, jumlah_roll, pembelian_id, barang_id, warna_id, harga_beli, ockh, barang_beli_id
						FROM (
							SELECT *
							FROM nd_pembelian_detail
							WHERE harga_beli is not null
							$cond_barang
							$cond_barang_beli
							$cond_warna
						)t1
						-- LEFT JOIN (
						-- 	SELECT pembelian_detail_id, sum(qty * if(jumlah_roll = 0,1, jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll
						-- 	FROM nd_pembelian_qty_detail
						-- 	WHERE qty != 0
						-- 	GROUP BY pembelian_detail_id
						-- 	) t2
						-- ON t2.pembelian_detail_id = t1.id
					) t1
					LEFT JOIN nd_barang
					ON t1.barang_id = nd_barang.id
					LEFT JOIN nd_warna
					ON t1.warna_id = nd_warna.id
					LEFT JOIN nd_barang_beli
					ON t1.barang_beli_id = nd_barang_beli.id
					GROUP BY pembelian_id
					) as tbl_b
				ON tbl_b.pembelian_id = tbl_a.id
				LEFT JOIN nd_supplier as tbl_c
				ON tbl_a.supplier_id = tbl_c.id
				LEFT JOIN (
					SELECT pembelian_id, sum(t1.amount) as total_bayar, group_concat(t1.pembayaran_hutang_id) as pembayaran_hutang_id, 
					group_concat(tanggal_transfer) as tanggal_bayar, 
					group_concat(tgl_jt ) as tgl_jt,
					group_concat(amount_jt) as amount_jt
					FROM (
						SELECT *
						FROM nd_pembayaran_hutang_detail
						WHERE data_status = 1
						) t1
					LEFT JOIN (
						SELECT MAX(tanggal_transfer) as tanggal_transfer, pembayaran_hutang_id, 
						group_concat(jatuh_tempo) as tgl_jt,
						group_concat(amount) as amount_jt
						FROM nd_pembayaran_hutang_nilai
						GROUP BY pembayaran_hutang_id
						) t2
					ON t1.pembayaran_hutang_id = t2.pembayaran_hutang_id
					GROUP BY pembelian_id
					) as tbl_d
				ON tbl_d.pembelian_id = tbl_a.id
				LEFT JOIN nd_gudang tbl_e
				ON tbl_a.gudang_id = tbl_e.id
				LEFT JOIN (
					SELECT concat(if(t2.tanggal >= '2019-09-27', concat(ifnull(pre_po,''),t4.kode,'/',DATE_FORMAT(t2.tanggal,'%y'),'/',LPAD(po_number,3,'0')) ,concat(LPAD(po_number,3,'0'),'/',DATE_FORMAT(t2.tanggal,'%m'),'/',DATE_FORMAT(t2.tanggal,'%y'))),'-',batch,if(revisi > 1,concat('R',revisi-1),'') ) as po_number, po_pembelian_id, t1.id 
					FROM nd_po_pembelian_batch t1
					LEFT JOIN nd_po_pembelian t2
					ON t1.po_pembelian_id = t2.id
					LEFT JOIN nd_toko t3
					ON t2.toko_id = t3.id
					LEFT JOIN nd_supplier t4
					ON t2.supplier_id = t4.id
					)tbl_f
				ON tbl_a.po_pembelian_batch_id = tbl_f.id
				WHERE tbl_a.id is not null
				AND tbl_b.pembelian_id is not null
				ORDER BY $order_column asc, no_faktur asc
			", false);

		return $query->result();
	}

	function get_pembelian_report_excel($cond_tanggal, $cond, $cond_barang, $cond_warna, $cond_gudang, $order_column){
		$query = $this->db->query("SELECT no_faktur, tanggal, qty, jumlah_roll, nama_barang, nama_jual, harga_beli, total, diskon, 
			ifnull(tbl_c.nama,'no name') as nama_supplier, (ifnull(total_bayar,0) - (ifnull(g_total,0) - ifnull(diskon,0))) as keterangan, 
			tbl_a.id as data , jatuh_tempo, tbl_e.nama as nama_gudang, satuan_id, ifnull(po_number, ockh_info ) as po_number, created_at
				FROM (
					SELECT group_concat(concat_ws(' ',nd_barang_beli.nama,warna_beli) SEPARATOR '??') as nama_barang, 
						group_concat(concat_ws(' ',nama_jual,warna_jual) SEPARATOR '??') as nama_jual,  
						group_concat(nd_pembelian_detail.harga_beli SEPARATOR '??') as harga_beli, group_concat(qty SEPARATOR '??') as qty ,
						group_concat(jumlah_roll SEPARATOR '??') as jumlah_roll, 
						group_concat((qty *nd_pembelian_detail.harga_beli) SEPARATOR '??') as total, 
						sum(qty *nd_pembelian_detail.harga_beli) as g_total, pembelian_id, group_concat(nd_barang.satuan_id SEPARATOR '??') as satuan_id
					FROM (
						SELECT *
						FROM nd_pembelian_detail
						$cond_barang
						$cond_warna
						) as nd_pembelian_detail
					LEFT JOIN nd_barang
					ON nd_pembelian_detail.barang_id = nd_barang.id
					LEFT JOIN nd_barang_beli
					ON nd_pembelian_detail.barang_beli_id = nd_barang_beli.id
					LEFT JOIN nd_warna
					ON nd_pembelian_detail.warna_id = nd_warna.id
					GROUP BY pembelian_id
					) as tbl_b
				LEFT JOIN (
					SELECT *
					FROM nd_pembelian 
					WHERE status_aktif = 1
					$cond_tanggal
					$cond
					$cond_gudang
					ORDER BY tanggal desc
					)as tbl_a
				ON tbl_b.pembelian_id = tbl_a.id
				LEFT JOIN nd_supplier as tbl_c
				ON tbl_a.supplier_id = tbl_c.id
				LEFT JOIN (
					SELECT pembelian_id, sum(t1.amount) as total_bayar, group_concat(t1.pembayaran_hutang_id) as pembayaran_hutang_id, group_concat(tanggal_transfer) as tanggal_bayar
					FROM nd_pembayaran_hutang_detail t1
					LEFT JOIN (
						SELECT MAX(tanggal_transfer) as tanggal_transfer, pembayaran_hutang_id
						FROM nd_pembayaran_hutang_nilai
						GROUP BY pembayaran_hutang_id
						) t2
					ON t1.pembayaran_hutang_id = t2.pembayaran_hutang_id
					GROUP BY pembelian_id
				) as tbl_d
				ON tbl_d.pembelian_id = tbl_a.id
				LEFT JOIN (
					SELECT concat(if(t2.tanggal >= '2019-09-27', concat(ifnull(pre_po,''),t4.kode,'/',DATE_FORMAT(t2.tanggal,'%y'),'/',LPAD(po_number,3,'0')) ,concat(LPAD(po_number,3,'0'),'/',DATE_FORMAT(t2.tanggal,'%m'),'/',DATE_FORMAT(t2.tanggal,'%y'))),'-',batch,if(revisi > 1,concat('R',revisi-1),'') ) as po_number, po_pembelian_id, t1.id 
					FROM nd_po_pembelian_batch t1
					LEFT JOIN nd_po_pembelian t2
					ON t1.po_pembelian_id = t2.id
					LEFT JOIN nd_toko t3
					ON t2.toko_id = t3.id
					LEFT JOIN nd_supplier t4
					ON t2.supplier_id = t4.id
					)tbl_f
				ON tbl_a.po_pembelian_batch_id = tbl_f.id
				LEFT JOIN nd_gudang tbl_e
				ON tbl_a.gudang_id = tbl_e.id
				WHERE tbl_a.id is not null
				ORDER BY $order_column asc, no_faktur asc
			", false);

		return $query->result();
	}


//==================================pembelian lain list==========================================

	function get_pembelian_lain_report($from, $to, $cond){
		$query = $this->db->query("SELECT tbl_a.id, no_faktur, tanggal, qty, keterangan_barang, harga_beli, total, tbl_c.nama as nama_supplier, (ifnull(total_bayar,0) - (ifnull(g_total,0) )) as keterangan, tbl_a.id as data , jatuh_tempo, supplier_id
				FROM (
					SELECT group_concat(concat_ws(' ',keterangan_barang) SEPARATOR '??') as keterangan_barang, 
						group_concat(harga_beli SEPARATOR '??') as harga_beli,
						group_concat(qty SEPARATOR '??') as qty , 
						group_concat((qty * harga_beli) SEPARATOR '??') as total, 
						sum(qty * harga_beli) as g_total, pembelian_lain_id 
					FROM nd_pembelian_lain_detail
					GROUP BY pembelian_lain_id
					) as tbl_b
				LEFT JOIN (
					SELECT *
					FROM nd_pembelian_lain
					WHERE tanggal >= '$from'
					AND tanggal <= '$to'
					AND status_aktif = 1
					$cond
					ORDER BY tanggal desc
					)as tbl_a
				ON tbl_b.pembelian_lain_id = tbl_a.id
				LEFT JOIN nd_supplier as tbl_c
				ON tbl_a.supplier_id = tbl_c.id
				LEFT JOIN (
					SELECT pembelian_id, sum(t1.amount) as total_bayar, group_concat(t1.pembayaran_hutang_id) as pembayaran_hutang_id, group_concat(tanggal_transfer) as tanggal_bayar
					FROM (
						SELECT *
						FROM nd_pembayaran_hutang_detail
						WHERE data_status = 11
						) t1
					LEFT JOIN (
						SELECT MAX(tanggal_transfer) as tanggal_transfer, pembayaran_hutang_id
						FROM nd_pembayaran_hutang_nilai
						GROUP BY pembayaran_hutang_id
						) t2
					ON t1.pembayaran_hutang_id = t2.pembayaran_hutang_id
					GROUP BY pembelian_id
					) as tbl_d
				ON tbl_d.pembelian_id = tbl_a.id
				
				WHERE tbl_a.id is not null
				ORDER BY tanggal asc, no_faktur asc
			", false);

		return $query->result();
	}

//==========================================penerimaan list================================
	function get_penjualan_bayar_by_date($tanggal_start, $tanggal_end){
		$query = $this->db->query("SELECT tbl_a.status_aktif, tbl_a.tanggal, no_faktur_lengkap as no_faktur, 
		no_faktur as faktur, group_concat(pembayaran_type_id) as pembayaran_type_id, 
		group_concat(amount) as bayar , g_total as amount, if (penjualan_type_id = 3, nama_keterangan, 
		concat(ifnull(tipe_company,''),' ',tbl_c.nama) ) as nama_customer, 
		tbl_b.keterangan as keterangan_transfer, tbl_a.id, sum(ifnull(amount,0)) - g_total as kembali, fp_status
			FROM (
				SELECT nd_penjualan.*, g_total
				FROM (
					select t1.id AS id,t1.toko_id ,t1.penjualan_type_id ,t1.no_faktur ,t1.po_number ,t1.tanggal ,
						t1.customer_id ,t1.ppn ,t1.gudang_id,t1.diskon ,t1.jatuh_tempo ,t1.ongkos_kirim,
						t1.keterangan ,t1.nama_keterangan ,t1.alamat_keterangan ,t1.status ,t1.status_aktif ,
						t1.closed_by ,t1.closed_date ,t1.user_id ,t1.created_at ,t1.revisi , t1.fp_status ,
						if((t1.tanggal < '2022-05-01 00:00:00'),concat('FPJ',convert(date_format(t1.tanggal,'%d%m%y') using latin1),'-',ifnull(t2.pre_faktur,''),convert(lpad(t1.no_faktur,5,'0') using latin1)),
						concat(t2.pre_po,':PJ01/',convert(date_format(t1.tanggal,'%y%m') using latin1),'/',convert(lpad(t1.no_faktur,4,'0') using latin1))) AS no_faktur_lengkap
					from nd_penjualan t1 
					left join nd_toko t2 
					on t1.toko_id = t2.id 
					WHERE tanggal >= '$tanggal_start'
					AND tanggal <= '$tanggal_end'
					AND (
						t1.status_aktif = 1
						OR no_faktur is not null
						)
					) as nd_penjualan
					LEFT JOIN (
						SELECT sum(subqty *nd_penjualan_detail.harga_jual) as g_total, penjualan_id 
						FROM nd_penjualan_detail
						GROUP BY penjualan_id
					) nd_penjualan_detail
					ON nd_penjualan.id = nd_penjualan_detail.penjualan_id
				) tbl_a
			LEFT JOIN (
				SELECT penjualan_id, pembayaran_type_id, sum(amount) as amount, keterangan
				FROM nd_pembayaran_penjualan
				GROUP BY penjualan_id, pembayaran_type_id
				) tbl_b
			ON tbl_a.id = tbl_b.penjualan_id
			LEFT JOIN nd_customer as tbl_c
			ON tbl_c.id = tbl_a.customer_id
			GROUP BY tbl_a.id
			ORDER BY tanggal, faktur asc
		");
		return $query->result();
	}

	function get_retur_jual_by_date($tanggal_start, $tanggal_end){
		$query = $this->db->query("SELECT tbl_a.tanggal, no_faktur_lengkap as no_faktur, group_concat(pembayaran_type_id) as pembayaran_type_id, group_concat(amount) as bayar , g_total as amount, if (retur_type_id = 3, nama_keterangan, tbl_c.nama) as nama_customer, tbl_b.keterangan as keterangan_transfer, no_faktur_penjualan, tbl_a.id
			FROM (
				SELECT nd_retur_jual.*, g_total
				FROM (
					SELECT *, concat('FRJ', DATE_FORMAT(tanggal,'%d%m%y'),'-',LPAD(no_faktur,4,'0')) as no_faktur_lengkap
					FROM nd_retur_jual 
					WHERE tanggal >= '$tanggal_start'
					AND tanggal <= '$tanggal_end'
					AND status_aktif = 1
					) as nd_retur_jual
					LEFT JOIN (
						SELECT sum(qty *nd_retur_jual_detail.harga) as g_total, retur_jual_id 
						FROM nd_retur_jual_detail
						LEFT JOIN (
							SELECT sum(qty * if(jumlah_roll = 0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, retur_jual_detail_id
							FROM nd_retur_jual_qty
							group by retur_jual_detail_id
							) as nd_retur_jual_qty_detail
						ON nd_retur_jual_qty_detail.retur_jual_detail_id = nd_retur_jual_detail.id
						GROUP BY retur_jual_id
					) nd_retur_jual_detail
					ON nd_retur_jual.id = nd_retur_jual_detail.retur_jual_id
				) tbl_a
			LEFT JOIN nd_pembayaran_retur tbl_b
			ON tbl_a.id = tbl_b.retur_jual_id
			LEFT JOIN nd_customer as tbl_c
			ON tbl_c.id = tbl_a.customer_id
			LEFT JOIN (
				SELECT id, concat('FPJ', DATE_FORMAT(tanggal,'%d%m%y'),'-',$this->pre_faktur,LPAD(no_faktur,5,'0'))  as no_faktur_penjualan
				FROM nd_penjualan
				) tbl_d
			ON tbl_a.penjualan_id = tbl_d.id
			GROUP BY retur_jual_id
			ORDER BY no_faktur asc
		");
		return $query->result();
	}

	function get_dp_by_date($tanggal_start, $tanggal_end){
		$query = $this->db->query("SELECT t2.nama as nama_customer,group_concat(ifnull(pembayaran_type_id,'-') SEPARATOR '??') as pembayaran_type_id ,group_concat(ifnull(nama_bank,'-') SEPARATOR '??') as nama_bank ,group_concat(ifnull(no_rek_bank,'-') SEPARATOR '??') as no_rek_bank ,group_concat(ifnull(urutan_giro,'-') SEPARATOR '??') as urutan_giro ,group_concat(ifnull(no_giro,'-') SEPARATOR '??') as no_giro ,group_concat(ifnull(no_akun_giro,'-') SEPARATOR '??') as no_akun_giro ,group_concat(ifnull(tanggal_giro,'-') SEPARATOR '??') as tanggal_giro ,group_concat(ifnull(jatuh_tempo,'-') SEPARATOR '??') as jatuh_tempo ,group_concat(ifnull(nama_penerima,'-') SEPARATOR '??') as nama_penerima ,group_concat(ifnull(amount,'-') SEPARATOR '??') as amount ,group_concat(ifnull(keterangan,'-') SEPARATOR '??') as keterangan 
			FROM (
				SELECT customer_id, pembayaran_type_id ,group_concat(nama_bank SEPARATOR '||') as nama_bank ,group_concat(no_rek_bank SEPARATOR '||') as no_rek_bank ,group_concat(ifnull(urutan_giro,0) SEPARATOR '||') as urutan_giro ,group_concat(no_giro SEPARATOR '||') as no_giro ,group_concat(no_akun_giro SEPARATOR '||') as no_akun_giro ,group_concat(tanggal_giro SEPARATOR '||') as tanggal_giro ,group_concat(ifnull(jatuh_tempo,'0') SEPARATOR '||') as jatuh_tempo ,group_concat(nama_penerima SEPARATOR '||') as nama_penerima ,group_concat(amount SEPARATOR '||') as amount ,group_concat(keterangan SEPARATOR '||') as keterangan
				FROM nd_dp_masuk
				WHERE tanggal >= '$tanggal_start'
				AND tanggal <= '$tanggal_end'
				GROUP BY customer_id, pembayaran_type_id, tanggal
				) t1
			LEFT JOIN nd_customer t2
			ON t1.customer_id = t2.id
			GROUP BY customer_id
		");
		return $query->result();
	}

//==================================pembelian list==========================================

	function get_barang_masuk_report_2($tanggal_start, $tanggal_end, $cond, $cond2, $group_by){
		$query = $this->db->query("SELECT GROUP_CONCAT(ifnull(qty,0) SEPARATOR '??') as qty, GROUP_CONCAT(ifnull(jumlah_roll,0) SEPARATOR '??') as jumlah_roll, GROUP_CONCAT(ifnull(count ,0) SEPARATOR '??') as count, GROUP_CONCAT(ifnull(bulan,0)  SEPARATOR '??') as bulan, nama_beli, nama_warna, GROUP_CONCAT(ifnull(harga_rata,0)  SEPARATOR '??') as harga_rata
			FROM (
					SELECT sum(qty) as qty, sum(jumlah_roll) as jumlah_roll, c.nama as nama_beli, d.warna_beli as nama_warna, sum(1) as count,barang_id, warna_id, sum(b.harga_beli*qty) / sum(qty) as harga_rata, MONTH(a.created_at) as bulan
					FROM (
						SELECT *
						FROM nd_pembelian 
						WHERE created_at >= '$tanggal_start 00:00:00'
						AND created_at <= '$tanggal_end 23:59:59'
						AND status_aktif = 1
						$cond
						ORDER BY tanggal desc
						)as a
					LEFT JOIN (
						SELECT t1.*
						FROM nd_pembelian_detail t1
						$cond2
						) b
					ON b.pembelian_id = a.id
					LEFT JOIN nd_barang c
					ON b.barang_id = c.id
					LEFT JOIN nd_warna d
					ON b.warna_id = d.id
					WHERE b.barang_id is not null
					AND b.warna_id is not null
					$group_by, MONTH(a.created_at)
					ORDER BY c.nama, d.warna_beli asc
				)result
				$group_by
			", false);

		return $query->result();
	}

	function get_barang_masuk_report($tanggal_start, $tanggal_end, $cond, $cond2, $group_by){
		$query = $this->db->query("SELECT GROUP_CONCAT(ifnull(qty,0) SEPARATOR '??') as qty, 
			GROUP_CONCAT(ifnull(jumlah_roll,0) SEPARATOR '??') as jumlah_roll, 
			GROUP_CONCAT(ifnull(count ,0) SEPARATOR '??') as count, 
			GROUP_CONCAT(ifnull(bulan,0)  SEPARATOR '??') as bulan, nama_beli, nama_warna, 
			GROUP_CONCAT(ifnull(harga_rata,0)  SEPARATOR '??') as harga_rata
			FROM (
					SELECT sum(qty) as qty, sum(jumlah_roll) as jumlah_roll, c.nama as nama_beli, d.warna_beli as nama_warna, 
					sum(1) as count,barang_id, warna_id, sum(b.harga_beli*qty) / sum(qty) as harga_rata, MONTH(tanggal_beli) as bulan
					FROM (
						SELECT *, tanggal as tanggal_beli
						FROM nd_pembelian 
						WHERE tanggal >= '$tanggal_start'
						AND tanggal <= '$tanggal_end'
						AND status_aktif = 1
						$cond
						ORDER BY tanggal desc
						)as a
					LEFT JOIN (
						SELECT t1.*
						FROM nd_pembelian_detail t1
						$cond2
						) b
					ON b.pembelian_id = a.id
					LEFT JOIN nd_barang c
					ON b.barang_id = c.id
					LEFT JOIN nd_warna d
					ON b.warna_id = d.id
					WHERE b.barang_id is not null
					AND b.warna_id is not null
					$group_by, MONTH(tanggal_beli)
				)result
				$group_by
				ORDER BY concat(nama_beli,' ', nama_warna) asc
			", false);

		return $query->result();
	}

	function get_barang_masuk_detail_report($tanggal_start, $tanggal_end, $cond, $cond2){
		$query = $this->db->query("SELECT tanggal, qty, jumlah_roll, c.nama as nama_beli, d.warna_beli as nama_warna, b.harga_beli, no_faktur
				FROM (
					SELECT *
					FROM nd_pembelian 
					WHERE tanggal >= '$tanggal_start'
					AND tanggal <= '$tanggal_end'
					AND status_aktif = 1
					$cond
					ORDER BY tanggal desc
					)as a
				LEFT JOIN (
					SELECT *
					FROM nd_pembelian_detail
					$cond2
					) b
				ON b.pembelian_id = a.id
				LEFT JOIN nd_barang c
				ON b.barang_id = c.id
				LEFT JOIN nd_warna d
				ON b.warna_id = d.id
				WHERE b.barang_id is not null
				AND b.warna_id is not null
			", false);

		return $query->result();
	}

//=======================================retur beli=======================

	function get_pembelian_retur($cond_tanggal, $cond, $cond_barang, $cond_warna, $cond_gudang){
		$query = $this->db->query("SELECT tbl_a.id, tbl_a.status_aktif, no_sj_lengkap, tanggal, qty, jumlah_roll, nama_barang, nama_jual, harga_beli, total, ifnull(tbl_c.nama,'no name') as nama_supplier, (ifnull(total_bayar,0) - (ifnull(g_total,0) )) as keterangan, tbl_a.id as data, nama_gudang, pembayaran_hutang_id, tanggal_bayar, supplier_id, satuan_id, barang_id, warna_id, keterangan1, keterangan2, po_number
				FROM (
					SELECT group_concat(concat_ws(' ',nama,warna_beli) SEPARATOR '??') as nama_barang, 
					group_concat(concat_ws(' ',nama_jual,warna_jual) SEPARATOR '??') as nama_jual,  
					group_concat(nd_retur_beli_detail.harga_beli SEPARATOR '??') as harga_beli,
					group_concat(nama_gudang SEPARATOR '??') as nama_gudang,
					group_concat(qty SEPARATOR '??') as qty ,
					group_concat(jumlah_roll SEPARATOR '??') as jumlah_roll, 
					group_concat((qty *nd_retur_beli_detail.harga_beli) SEPARATOR '??') as total, 
					sum(qty *nd_retur_beli_detail.harga_beli) as g_total, 
					retur_beli_id, group_concat(nd_barang.satuan_id SEPARATOR '??') as satuan_id, group_concat(barang_id SEPARATOR '??') as barang_id, group_concat(warna_id SEPARATOR '??') as warna_id
					FROM (
						SELECT qty, jumlah_roll, retur_beli_id, barang_id, warna_id, harga as harga_beli, t3.nama as nama_gudang, gudang_id
						FROM nd_retur_beli_detail t1
						LEFT JOIN (
							SELECT sum(qty*if(jumlah_roll=0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, retur_beli_detail_id
							FROM nd_retur_beli_qty
							GROUP BY retur_beli_detail_id) t2
						ON t2.retur_beli_detail_id = t1.id
						LEFT JOIN nd_gudang t3
						ON t1.gudang_id = t3.id
						WHERE qty != 0
						$cond_barang
						$cond_warna
						$cond_gudang
						) as nd_retur_beli_detail
					LEFT JOIN nd_barang
					ON nd_retur_beli_detail.barang_id = nd_barang.id
					LEFT JOIN nd_warna
					ON nd_retur_beli_detail.warna_id = nd_warna.id
					GROUP BY retur_beli_id
					) as tbl_b
				LEFT JOIN (
					SELECT t1.*, concat('SJ/R/',if(t2.kode is not null, concat(t2.kode,'/'),'LL/'),DATE_FORMAT(tanggal,'%y'),'-',no_sj ) as no_sj_lengkap, po_number
		        	FROM nd_retur_beli t1
		        	LEFT JOIN nd_supplier t2
		        	ON t1.supplier_id = t2.id
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
					ON t1.po_pembelian_batch_id = tPO.id
					WHERE t1.status_aktif = 1
		            $cond_tanggal
					$cond
					ORDER BY tanggal desc
					)as tbl_a
				ON tbl_b.retur_beli_id = tbl_a.id
				LEFT JOIN nd_supplier as tbl_c
				ON tbl_a.supplier_id = tbl_c.id
				LEFT JOIN (
					SELECT pembelian_id, sum(t1.amount) as total_bayar, group_concat(t1.pembayaran_hutang_id) as pembayaran_hutang_id, group_concat(tanggal_transfer) as tanggal_bayar
					FROM (
						SELECT *
						FROM nd_pembayaran_hutang_detail
						WHERE data_status = 4) t1
					LEFT JOIN (
						SELECT MAX(tanggal_transfer) as tanggal_transfer, pembayaran_hutang_id
						FROM nd_pembayaran_hutang_nilai
						GROUP BY pembayaran_hutang_id
						) t2
					ON t1.pembayaran_hutang_id = t2.pembayaran_hutang_id
					GROUP BY pembelian_id
					) as tbl_d
				ON tbl_d.pembelian_id = tbl_a.id
				WHERE tbl_a.id is not null
				ORDER BY tanggal asc
			", false);

		return $query->result();
	}


//==================================barang keluar list==========================================

	function get_barang_keluar_report($tanggal_start, $tanggal_end, $cond, $cond2, $group_by){
		$query = $this->db->query("SELECT GROUP_CONCAT(ifnull(qty,0) SEPARATOR '??') as qty, 
		GROUP_CONCAT(ifnull(jumlah_roll,0) SEPARATOR '??') as jumlah_roll, 
		GROUP_CONCAT(ifnull(count ,0) SEPARATOR '??') as count, 
		GROUP_CONCAT(ifnull(bulan,0)  SEPARATOR '??') as bulan, 
		GROUP_CONCAT(ifnull(harga_rata,0)  SEPARATOR '??') as harga_rata,
		nama_warna, nama_jual , barang_id, warna_id
			FROM (
					(
						SELECT sum(qty) as qty, sum(jumlah_roll) as jumlah_roll, c.nama_jual as nama_jual, d.warna_jual as nama_warna, 
						count(distinct(a.id)) as count,barang_id, warna_id, sum(b.harga_jual*qty) / sum(qty) as harga_rata, MONTH(a.tanggal) as bulan, 1 as tipe
						FROM (
							SELECT *
							FROM nd_penjualan 
							WHERE tanggal >= '$tanggal_start'
							AND tanggal <= '$tanggal_end'
							AND status_aktif = 1
							$cond
							ORDER BY tanggal desc
							)as a
						LEFT JOIN (
							SELECT t1.*
							FROM nd_penjualan_detail t1
							$cond2
							) b
						ON b.penjualan_id = a.id
						LEFT JOIN (
							SELECT sum(qty * if(jumlah_roll = 0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, penjualan_detail_id
							FROM nd_penjualan_qty_detail
							GROUP BY penjualan_detail_id
							)bDetail
						ON bDetail.penjualan_detail_id = b.id
						LEFT JOIN nd_barang c
						ON b.barang_id = c.id
						LEFT JOIN nd_warna d
						ON b.warna_id = d.id
						WHERE b.barang_id is not null
						AND b.warna_id is not null
						$group_by, MONTH(a.tanggal)
						ORDER BY c.nama, d.warna_beli asc
					)UNION(
						SELECT sum(qty) as qty, sum(jumlah_roll) as jumlah_roll, c.nama_jual as nama_jual, d.warna_jual as nama_warna, 
						count(distinct(a.id)) as count,barang_id, warna_id, sum(b.harga_jual*qty) / sum(qty) as harga_rata, MONTH(a.tanggal) as bulan, 2
						FROM (
							SELECT *
							FROM nd_pengeluaran_stok_lain 
							WHERE tanggal >= '$tanggal_start'
							AND tanggal <= '$tanggal_end'
							AND status_aktif = 1
							$cond
							ORDER BY tanggal desc
							)as a
						LEFT JOIN (
							SELECT t1.*
							FROM nd_pengeluaran_stok_lain_detail t1
							$cond2
							) b
						ON b.pengeluaran_stok_lain_id = a.id
						LEFT JOIN (
							SELECT sum(qty * if(jumlah_roll = 0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, pengeluaran_stok_lain_detail_id
							FROM nd_pengeluaran_stok_lain_qty_detail
							GROUP BY pengeluaran_stok_lain_detail_id
							)bDetail
						ON bDetail.pengeluaran_stok_lain_detail_id = b.id
						LEFT JOIN nd_barang c
						ON b.barang_id = c.id
						LEFT JOIN nd_warna d
						ON b.warna_id = d.id
						WHERE b.barang_id is not null
						AND b.warna_id is not null
						$group_by, MONTH(a.tanggal)
						ORDER BY c.nama, d.warna_beli asc
					)
				)
				result
				$group_by
			", false);

		return $query->result();
	}

	function get_barang_keluar_report_rekap($tanggal_start, $tanggal_end, $cond, $cond2, $sum, $sum_group){
		$query = $this->db->query("SELECT bulan, nama_bulan, tahun, 
			$sum_group
			group_concat(jml_trx ORDER BY tipe asc) as jml_trx_data,
			group_concat(tipe ORDER BY tipe asc) as tipe_data
			FROM (
				
					(
						SELECT MONTH(a.tanggal) as bulan, YEAR(a.tanggal) as tahun, $sum
						count(distinct(a.id)) as jml_trx, MONTHNAME(tanggal) as nama_bulan, 1 as tipe
						FROM (
							SELECT *
							FROM nd_penjualan 
							WHERE tanggal >= '$tanggal_start'
							AND tanggal <= '$tanggal_end'
							AND status_aktif = 1
							$cond
							ORDER BY tanggal desc
							)as a
						LEFT JOIN (
							SELECT t1.*
							FROM nd_penjualan_detail t1
							$cond2
							) b
						ON b.penjualan_id = a.id
						LEFT JOIN (
							SELECT sum(qty * if(jumlah_roll = 0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, penjualan_detail_id
							FROM nd_penjualan_qty_detail
							GROUP BY penjualan_detail_id
							)bDetail
						ON bDetail.penjualan_detail_id = b.id
						LEFT JOIN nd_barang c
						ON b.barang_id = c.id
						LEFT JOIN nd_warna d
						ON b.warna_id = d.id
						WHERE b.barang_id is not null
						AND b.warna_id is not null
						GROUP BY MONTH(a.tanggal), YEAR(a.tanggal)
						ORDER BY MONTH(a.tanggal), YEAR(a.tanggal) asc
					)UNION(
						SELECT MONTH(a.tanggal) as bulan, YEAR(a.tanggal), $sum
						count(distinct(a.id)) , MONTHNAME(tanggal), 2
						FROM (
							SELECT *
							FROM nd_pengeluaran_stok_lain 
							WHERE tanggal >= '$tanggal_start'
							AND tanggal <= '$tanggal_end'
							AND status_aktif = 1
							$cond
							ORDER BY tanggal desc
							)as a
						LEFT JOIN (
							SELECT t1.*
							FROM nd_pengeluaran_stok_lain_detail t1
							$cond2
							) b
						ON b.pengeluaran_stok_lain_id = a.id
						LEFT JOIN (
							SELECT sum(qty * if(jumlah_roll = 0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, pengeluaran_stok_lain_detail_id
							FROM nd_pengeluaran_stok_lain_qty_detail
							GROUP BY pengeluaran_stok_lain_detail_id
							)bDetail
						ON bDetail.pengeluaran_stok_lain_detail_id = b.id
						LEFT JOIN nd_barang c
						ON b.barang_id = c.id
						LEFT JOIN nd_warna d
						ON b.warna_id = d.id
						WHERE b.barang_id is not null
						AND b.warna_id is not null
						GROUP BY MONTH(a.tanggal), YEAR(a.tanggal), satuan_id
						ORDER BY MONTH(a.tanggal), YEAR(a.tanggal) asc
					)
				)res
				GROUP BY bulan, tahun
				ORDER BY tahun, bulan asc
			", false);

		return $query->result();
	}

//================================buku laporan piutang=============================

	function buku_laporan_piutang($aColumns, $sWhere, $sOrder, $sLimit, $cond_date, $customer_cond){
		$query = $this->db->query("SELECT *
			FROM (
				SELECT tbl_a.id, no_faktur as nf, tbl_a.status_aktif, no_faktur_lengkap as no_faktur, tanggal, qty, jumlah_roll, nama_barang, harga_jual, total, diskon, ongkos_kirim, if(customer_id != 0, tbl_c.nama, concat(nama_keterangan, ' (non-pelanggan)')) as nama_customer, ( (ifnull(total_bayar,0) + ifnull(total_lunas,0) ) - (ifnull(g_total,0) - ifnull(diskon,0)) + ifnull(ongkos_kirim,0)) as keterangan, tbl_a.id as data , jatuh_tempo, concat(DATE_FORMAT(tanggal,'%d-%b-%y'),'??',pembayaran_type_id,'??',data_bayar) as pembayaran_data, pelunasan_data, tbl_a.id as penjualan_id
					FROM (
						SELECT *
						FROM vw_penjualan_data 
						$cond_date
						AND status_aktif = 1
						$customer_cond
						ORDER BY tanggal desc
						)as tbl_a
					LEFT JOIN (
						SELECT group_concat(concat_ws(' ',nama_jual,warna_jual) SEPARATOR '??') as nama_barang, group_concat(nd_penjualan_detail.harga_jual SEPARATOR '??') as harga_jual, group_concat(qty SEPARATOR '??') as qty ,group_concat(jumlah_roll SEPARATOR '??') as jumlah_roll, group_concat((qty *nd_penjualan_detail.harga_jual) SEPARATOR '??') as total, sum(qty *nd_penjualan_detail.harga_jual) as g_total, penjualan_id, group_concat(concat_ws(' ',nama_jual,warna_jual) SEPARATOR '??') as nama_jual
						FROM nd_penjualan_detail
						LEFT JOIN nd_barang
						ON nd_penjualan_detail.barang_id = nd_barang.id
						LEFT JOIN nd_warna
						ON nd_penjualan_detail.warna_id = nd_warna.id
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
						SELECT penjualan_id, sum(if(pembayaran_type_id=5,0,amount)) as total_bayar, group_concat(amount) as data_bayar, group_concat(pembayaran_type_id) as pembayaran_type_id
						FROM nd_pembayaran_penjualan
						WHERE pembayaran_type_id != 5
						GROUP BY penjualan_id
						) as tbl_d
					ON tbl_d.penjualan_id = tbl_a.id
					LEFT JOIN (
						SELECT penjualan_id, sum(t1.amount) as total_lunas, group_concat(t2.id,'--',t1.amount,'--',tanggal_transfer,'--',pembayaran_type_id SEPARATOR '??') as pelunasan_data
						FROM nd_pembayaran_piutang_detail t1
						LEFT JOIN nd_pembayaran_piutang t2
						ON t1.pembayaran_piutang_id = t2.id
						LEFT JOIN (
							SELECT group_concat(pembayaran_type_id) as pembayaran_type_id, group_concat(DATE_FORMAT(tanggal_transfer,'%d-%b-%y') ) as tanggal_transfer, pembayaran_piutang_id
							FROM (
								SELECT *
								FROM nd_pembayaran_piutang_nilai
								GROUP BY pembayaran_type_id, pembayaran_piutang_id, tanggal_transfer
								)nd_pembayaran_piutang_nilai
							GROUP BY pembayaran_piutang_id
							) t3
						ON t3.pembayaran_piutang_id = t2.id
						WHERE t2.status_aktif != 0
						AND data_status = 1
						AND tanggal_transfer is not null
						GROUP BY penjualan_id
						) tbl_e
					ON tbl_e.penjualan_id = tbl_a.id
					ORDER BY nf asc
				) A
			$sWhere
            $sOrder
            $sLimit

				

			", false);

		return $query;
	}


//================================buku laporan penyesuaian stok=============================

	function get_penyesuaian_stok($select, $tanggal_start, $tanggal_end, $cond_gudang, $cond_barang, $cond_warna){

		$query = $this->db->query("SELECT t2.nama_jual as nama_barang,t2.nama_jual as nama_jual, t3.warna_beli as nama_warna, t4.nama as nama_satuan, 
		sum(qty_masuk) as qty_masuk, sum(qty_keluar) as qty_keluar, 
		sum(jumlah_roll_masuk) as jumlah_roll_masuk, sum(jumlah_roll_keluar) as jumlah_roll_keluar
			FROM (
				(
					select id, barang_id, gudang_id, warna_id, qty as qty_masuk, 0 as qty_keluar, jumlah_roll as jumlah_roll_masuk, 0 as jumlah_roll_keluar
					from nd_penyesuaian_stok
					WHERE tipe_transaksi = 1
					AND tanggal >= '$tanggal_start'
					AND tanggal <= '$tanggal_end'
					$cond_gudang
					$cond_barang
					$cond_warna
				)UNION(
					select id, barang_id, gudang_id, warna_id, 0, qty, 0, jumlah_roll
					from nd_penyesuaian_stok
					WHERE tipe_transaksi = 2
					AND tanggal >= '$tanggal_start'
					AND tanggal <= '$tanggal_end'
					$cond_gudang
					$cond_barang
					$cond_warna
				)UNION(
					select id, barang_id, gudang_id, warna_id, if(qty < 0, 0,qty)  as qty_masuk, if(qty < 0, qty * -1,0) as qty_keluar, if(jumlah_roll < 0, 0,jumlah_roll) as jumlah_roll_masuk, if(jumlah_roll < 0,jumlah_roll* -1, 0) as jumlah_roll_keluar
					from nd_penyesuaian_stok
					WHERE tipe_transaksi = 4
					AND tanggal >= '$tanggal_start'
					AND tanggal <= '$tanggal_end'
					$cond_gudang
					$cond_barang
					$cond_warna
				)
			) t1
			LEFT JOIN nd_barang t2
			ON t1.barang_id = t2.id
			LEFT JOIN nd_warna t3
			ON t1.warna_id = t3.id
			LEFT JOIN nd_satuan t4
			ON t2.satuan_id = t4.id
			GROUP BY barang_id, warna_id

			", false);

		return $query->result();
	}

	function get_penyesuaian_stok_by_gudang($select, $tanggal){

		$query = $this->db->query("SELECT t2.nama as nama_barang,t2.nama_jual as nama_jual, t3.warna_beli as nama_warna, t4.nama as nama_satuan $select
			FROM (
				(
					select barang_id, gudang_id, warna_id, qty as qty_masuk, 0 as qty_keluar, jumlah_roll as jumlah_roll_masuk, 0 as jumlah_roll_keluar
					from nd_penyesuaian_stok
					WHERE tipe_transaksi = 1
					AND tanggal <= '$tanggal'
				)UNION(
					select barang_id, gudang_id, warna_id, 0, qty, 0, jumlah_roll
					from nd_penyesuaian_stok
					WHERE tipe_transaksi = 2
					AND tanggal <= '$tanggal'
				)
			) t1
			LEFT JOIN nd_barang t2
			ON t1.barang_id = t2.id
			LEFT JOIN nd_warna t3
			ON t1.warna_id = t3.id
			LEFT JOIN nd_satuan t4
			ON t2.satuan_id = t4.id
			GROUP BY barang_id, gudang_id

			", false);

		return $query->result();
	}


//=======================================get PO Gantung=============================

	function get_po_gantung(){
		$query = $this->db->query("SELECT t1.*, concat(if(t2.tanggal >= '2019-09-27', concat(ifnull(pre_po,''),t4.kode,'/',DATE_FORMAT(t2.tanggal,'%y'),'/',LPAD(po_number,3,'0')) ,concat(LPAD(po_number,3,'0'),'/',DATE_FORMAT(t2.tanggal,'%m'),'/',DATE_FORMAT(t2.tanggal,'%y'))),'-',batch,if(revisi > 1,concat('R',revisi-1),'') ) as po_number, t4.nama as nama_supplier, qty_sisa + ifnull(qty_retur,0) as qty_sisa, qty_retur
			FROM (	
				SELECT sum(a.qty-qty_beli) as qty_sisa,a.qty as po_qty, e.warna_beli as nama_warna, b.*
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
					-- LEFT JOIN (
					-- 	SELECT pembelian_detail_id, sum(qty * if(jumlah_roll = 0,1, jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll
					-- 	FROM nd_pembelian_qty_detail
					-- 	WHERE qty != 0
					-- 	GROUP BY pembelian_detail_id
					-- 	) t3
					-- ON t3.pembelian_detail_id = t1.id
					GROUP BY barang_id, warna_id, po_pembelian_batch_id
					) d
				ON a.warna_id = d.warna_id
				AND b.id = d.po_pembelian_batch_id
				AND d.barang_id = c.barang_id
				LEFT JOIN nd_warna e
				ON a.warna_id = e.id
				WHERE a.qty-qty_beli >= 0
				GROUP BY b.id
				)t1
			LEFT JOIN nd_po_pembelian t2
			ON t1.po_pembelian_id = t2.id
			LEFT JOIN nd_toko t3
			ON t2.toko_id = t3.id
			LEFT JOIN nd_supplier t4
			ON t2.supplier_id = t4.id
			LEFT JOIN (
				SELECT sum(qty) as qty_retur, sum(jumlah_roll) as jumlah_roll_retur, po_pembelian_batch_id
				FROM (
					SELECT sum(qty*if(jumlah_roll=0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, retur_beli_detail_id
					FROM nd_retur_beli_qty
					GROUP BY retur_beli_detail_id
					) tA
				LEFT JOIN nd_retur_beli_detail tB
				ON tA.retur_beli_detail_id = tB.id
				LEFT JOIN nd_retur_beli tC
				ON tB.retur_beli_id = tC.id
				WHERE status_aktif = 1
				GROUP BY po_pembelian_batch_id
				)t5
			ON t5.po_pembelian_batch_id = t1.id
			ORDER BY t1.tanggal desc

			");
		return $query->result();
	}

	function get_po_gantung_detail($po_pembelian_batch_id){
		$query = $this->db->query("SELECT a.qty, qty_beli, f.nama as nama_barang, e.warna_beli as nama_warna, b.*, f.nama as nama_jual, g.nama as nama_satuan, qty_retur
				FROM (
					SELECT *
					FROM nd_po_pembelian_warna
					WHERE po_pembelian_batch_id = $po_pembelian_batch_id
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
					-- LEFT JOIN (
					-- 		SELECT pembelian_detail_id, sum(qty * if(jumlah_roll = 0,1, jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll
					-- 		FROM nd_pembelian_qty_detail
					-- 		WHERE qty != 0
					-- 		GROUP BY pembelian_detail_id
					-- 		) t3
					-- 	ON t3.pembelian_detail_id = t1.id
					GROUP BY barang_id, warna_id, po_pembelian_batch_id
					) d
				ON a.warna_id = d.warna_id
				AND b.id = d.po_pembelian_batch_id
				AND d.barang_id = c.barang_id
				LEFT JOIN (
					SELECT barang_id, warna_id, sum(qty) as qty_retur, po_pembelian_batch_id 
					FROM nd_retur_beli_detail t1
					LEFT JOIN nd_retur_beli t2
					ON t1.retur_beli_id = t2.id
					LEFT JOIN (
							SELECT retur_beli_detail_id, sum(qty * if(jumlah_roll = 0,1, jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll
							FROM nd_retur_beli_qty
							WHERE qty != 0
							GROUP BY retur_beli_detail_id
							) t3
						ON t3.retur_beli_detail_id = t1.id
					GROUP BY barang_id, warna_id, po_pembelian_batch_id
					) h
				ON a.warna_id = h.warna_id
				AND b.id = h.po_pembelian_batch_id
				AND c.barang_id = h.barang_id
				LEFT JOIN nd_warna e
				ON a.warna_id = e.id
				LEFT JOIN nd_barang f
				ON d.barang_id = f.id
				LEFT JOIN nd_satuan g
				ON f.satuan_id = g.id
				WHERE a.qty-qty_beli >= 0

			");
		return $query->result();
	}	


	function get_data_po_pembelian($id){
		$query = $this->db->query("SELECT tbl_a.*, tbl_b.nama as nama_supplier, tbl_d.nama as nama_toko, tbl_b.telepon as telepon_supplier, concat(ifnull(pre_po,''),LPAD(po_number,3,'0'),'/',DATE_FORMAT(tanggal,'%m'),'/',DATE_FORMAT(tanggal,'%y')) as po_number, tbl_b.nama as nama_customer, tbl_b.alamat, up_person, tbl_b.kota
			FROM (
				SELECT *
				FROM nd_po_pembelian
				Where id = $id
			) as tbl_a
			LEFT JOIN nd_supplier as tbl_b
			ON tbl_a.supplier_id = tbl_b.id
			LEFT JOIN nd_toko as tbl_d
			ON tbl_a.toko_id = tbl_d.id
		");
		return $query->result();
	}

	function po_pembelian_report($cond, $cond_lock){
		$query = $this->db->query("SELECT po_number, keterangan_batch, nama_supplier, tanggal, result.po_pembelian_id, 
		result.id,supplier_id, batch_id,
			sum(qty_beli) as qty_beli, 
			sum(po_qty) as po_qty, 
			group_concat(nama_satuan ORDER BY nama_barang, nama_warna) as nama_satuan, 
			group_concat(data_qty_beli ORDER BY nama_barang, nama_warna) as data_qty_beli, 
			group_concat(data_po_qty ORDER BY nama_barang, nama_warna) as data_po_qty, 
			group_concat(data_qty_retur ORDER BY nama_barang, nama_warna) as data_qty_retur, 
			group_concat(nama_barang ORDER BY nama_barang, nama_warna) as nama_barang, 
			group_concat(nama_warna ORDER BY nama_barang, nama_warna) as nama_warna,
			group_concat(locked_tgl ORDER BY nama_barang, nama_warna) as locked_po,
			group_concat(po_warna_id ORDER BY nama_barang, nama_warna) as po_warna_id,
			group_concat(ifnull(t2.qty,0) ORDER BY nama_barang, nama_warna) as qty_2019,
			group_concat(warna_id ORDER BY nama_barang, nama_warna) as warna_id,
			group_concat(barang_id ORDER BY nama_barang, nama_warna) as barang_id,
			group_concat(barang_beli_id ORDER BY nama_barang, nama_warna) as barang_beli_id,
			group_concat(harga_barang ORDER BY nama_barang, nama_warna) as harga_barang,
			group_concat(ifnull(last_datang, 'kosong')  ORDER BY nama_barang, nama_warna) as last_datang
			FROM (
				SELECT b.*, b.id as batch_id ,c.id as detail_id, satuan_id, 
				ifnull(a.qty,0) as po_qty,
					if(a.tipe_barang = 1, ifnull(d.qty_beli,0), ifnull(tA.qty_beli,0))  as qty_beli, 
					if(a.tipe_barang = 1, ifnull(d.qty_beli,0), ifnull(tA.qty_beli,0)) as data_qty_beli,
					if(a.tipe_barang = 1, ifnull(g.qty_retur,0), ifnull(tA.qty_retur,0))  as qty_retur, 
					if(a.tipe_barang = 1, ifnull(g.qty_retur,0), ifnull(tA.qty_retur,0)) as data_qty_retur,
					if(a.tipe_barang!=1,ifnull(tA.barang_id_baru,'-'),ifnull(c.barang_id,'-')) as barang_id,
					if(a.tipe_barang!=1,ifnull(tA.barang_beli_id_baru,'-'),ifnull(c.barang_beli_id,'-')) as barang_beli_id,
					a.warna_id , a.qty as data_po_qty, 
					f.nama as nama_satuan, 
					if(a.tipe_barang!=1,ifnull(tA.nama_barang_baru,'-'),ifnull(c.nama_barang,'-')) as nama_barang, 
					warna_beli as nama_warna, ifnull(a.locked_date,'-') as locked_tgl, a.id as po_warna_id,
					ifnull(a.harga_baru, c.harga) as harga_barang, last_datang
					 
				FROM (
					SELECT t1.*, concat(if(t2.tanggal >= '2019-09-27', 
					concat(ifnull(pre_po,''),t4.kode,'/',DATE_FORMAT(t2.tanggal,'%y'),'/',LPAD(po_number,3,'0')) ,
					concat(LPAD(po_number,3,'0'),'/',DATE_FORMAT(t2.tanggal,'%m'),'/',DATE_FORMAT(t2.tanggal,'%y'))),'-',batch,if(revisi > 1,concat('R',revisi-1),'') ) as po_number, 
					t4.nama as nama_supplier, supplier_id
					FROM (
						SELECT *
						FROM nd_po_pembelian_batch
						WHERE status !=0
					) t1
					LEFT JOIN nd_po_pembelian t2
					ON t1.po_pembelian_id = t2.id
					LEFT JOIN nd_toko t3
					ON t2.toko_id = t3.id
					LEFT JOIN nd_supplier t4
					ON t2.supplier_id = t4.id
					) b
				LEFT JOIN nd_po_pembelian_warna a
				ON a.po_pembelian_batch_id = b.id
				LEFT JOIN (
					SELECT t1.*, satuan_id, t21.nama as nama_barang
					FROM nd_po_pembelian_detail t1
					LEFT JOIN nd_barang_beli t21
					ON t1.barang_beli_id = t21.id
					LEFT JOIN nd_barang t2
					ON t1.barang_id = t2.id
					) c
				ON a.po_pembelian_detail_id = c.id
				LEFT JOIN (
					SELECT barang_id, warna_id, barang_beli_id, sum(ifnull(qty,0) ) as qty_beli, po_pembelian_batch_id, max(t2.created_at) as last_datang
					FROM nd_pembelian_detail t1
					LEFT JOIN nd_pembelian t2
					ON t1.pembelian_id = t2.id
					-- LEFT JOIN nd_pembelian_qty_detail t3
					-- ON t1.id=t3.pembelian_detail_id
					GROUP BY barang_beli_id, warna_id, po_pembelian_batch_id
					) d
				ON a.warna_id = d.warna_id
				AND b.id = d.po_pembelian_batch_id
				AND d.barang_beli_id = c.barang_beli_id
				LEFT JOIN (
					SELECT barang_id, warna_id, sum(ifnull(qty,0) * if(jumlah_roll = 0,1,jumlah_roll)) as qty_retur, po_pembelian_batch_id
					FROM nd_retur_beli_detail t1
					LEFT JOIN nd_retur_beli t2
					ON t1.retur_beli_id = t2.id
					LEFT JOIN nd_retur_beli_qty t3
					ON t1.id=t3.retur_beli_detail_id
					GROUP BY barang_id, warna_id, po_pembelian_batch_id
					) g
				ON a.warna_id = g.warna_id
				AND b.id = g.po_pembelian_batch_id
				AND g.barang_id = c.barang_id
				LEFT JOIN (
					SELECT tA.id, qty_beli,qty_retur, tC1.nama as nama_barang_baru, tA.warna_id, tA.tipe_barang, barang_id_baru, barang_beli_id_baru
					FROM (
						SELECT *
						FROM nd_po_pembelian_warna
						WHERE tipe_barang != 1
						) tA
					LEFT JOIN (
						SELECT barang_id, warna_id, sum(ifnull(qty,0)) as qty_beli, po_pembelian_batch_id 
						FROM nd_pembelian_detail t1
						LEFT JOIN nd_pembelian t2
						ON t1.pembelian_id = t2.id
						-- LEFT JOIN nd_pembelian_qty_detail t3
						-- ON t1.id=t3.pembelian_detail_id
						GROUP BY barang_id, warna_id, po_pembelian_batch_id
						) tB
					ON tA.warna_id = tB.warna_id
					AND tA.po_pembelian_batch_id = tB.po_pembelian_batch_id
					AND tA.barang_id_baru = tB.barang_id
					LEFT JOIN (
						SELECT barang_id, warna_id, sum(ifnull(qty,0) * if(jumlah_roll = 0,1,jumlah_roll)) as qty_retur, po_pembelian_batch_id 
						FROM nd_retur_beli_detail t1
						LEFT JOIN nd_retur_beli t2
						ON t1.retur_beli_id = t2.id
						LEFT JOIN nd_retur_beli_qty t3
						ON t1.id=t3.retur_beli_detail_id
						GROUP BY barang_id, warna_id, po_pembelian_batch_id
						) tD
					ON tA.warna_id = tD.warna_id
					AND tA.po_pembelian_batch_id = tD.po_pembelian_batch_id
					AND tA.barang_id_baru = tD.barang_id
					LEFT JOIN nd_barang_beli tC1
					ON tA.barang_beli_id_baru = tC1.id
					LEFT JOIN nd_barang tC
					ON tA.barang_id_baru = tC.id
					) tA
				ON a.id = tA.id
				LEFT JOIN nd_warna e
				ON a.warna_id = e.id
				LEFT JOIN nd_satuan f
				ON c.satuan_id = f.id
				$cond_lock
			)result
		LEFT JOIN nd_po_pembelian_before_qty t2
		ON result.batch_id = t2.po_pembelian_batch_id
		AND result.po_warna_id = t2.po_pembelian_warna_id
		$cond
		GROUP BY id
		ORDER BY tanggal desc
		");
		return $query->result();
	}

	function po_pembelian_detail_report($id){
			$query = $this->db->query("SELECT if(a.tipe_barang = 1, ifnull(d.qty_beli,0), ifnull(tA.qty_beli,0))  as qty_beli, 
				if(a.tipe_barang = 1, ifnull(d.barang_id,0), ifnull(tA.barang_id_baru,0))  as barang_id, 
				if(a.tipe_barang = 1, ifnull(d.barang_beli_id,0), ifnull(tA.barang_beli_id_baru,0))  as barang_beli_id, 
				if(a.tipe_barang = 1,d.pembelian_id, tA.pembelian_id) as pembelian_id, 
				if(a.tipe_barang = 1,d.no_faktur, tA.no_faktur) as no_faktur, 
				if(a.tipe_barang = 1,d.tanggal_beli, tA.tanggal_beli) as tanggal_beli, 
				if(a.tipe_barang = 1,d.tanggal_datang, tA.tanggal_datang) as tanggal_datang, 
				if(a.tipe_barang = 1, ifnull(d.harga_beli,0), ifnull(tA.harga_beli,0)) as harga_beli,
				if(a.tipe_barang = 1, a.warna_id, tA.warna_id) as warna_id,
				if(a.tipe_barang = 1, a.warna_beli, e.warna_beli) as nama_warna, 
					ifnull(h.qty_retur,0) as qty_retur,  
					b.id as batch_id , ifnull(a.qty,0) as po_qty, c.id as detail_id, po_number, nama_supplier, satuan_id, 
				f.nama as nama_satuan, nama_barang, 
				tipe_barang, nama_baru, tipe_barang, a.id as po_pembelian_warna_id, 
				DATE_FORMAT(a.locked_date,'%Y-%m-%d') as locked_date, username, OCKH, 
				b.id as batch_id, if(a.tipe_barang = 1,c.harga, tA.harga) as harga_po, g.qty as qty_2019, if(a.tipe_barang = 1, d.last_datang, tA.last_datang) as last_datang

				FROM (
					SELECT t1.*, concat(if(t2.tanggal >= '2019-09-27', concat(ifnull(pre_po,''),t4.kode,'/',DATE_FORMAT(t2.tanggal,'%y'),'/',LPAD(po_number,3,'0')) ,concat(LPAD(po_number,3,'0'),'/',DATE_FORMAT(t2.tanggal,'%m'),'/',DATE_FORMAT(t2.tanggal,'%y'))),'-',batch,if(revisi > 1,concat('R',revisi-1),'') ) as po_number, t4.nama as nama_supplier
					FROM (
						SELECT *
						FROM nd_po_pembelian_batch
						WHERE id = $id
						) t1
					LEFT JOIN nd_po_pembelian t2
					ON t1.po_pembelian_id = t2.id
					LEFT JOIN nd_toko t3
					ON t2.toko_id = t3.id
					LEFT JOIN nd_supplier t4
					ON t2.supplier_id = t4.id
				) b
				LEFT JOIN (
					SELECT tA.*, tB1.nama as nama_baru, tC.username, warna_beli
					FROM nd_po_pembelian_warna tA
					LEFT JOIN nd_barang_beli tB1
					ON tA.barang_beli_id_baru = tB1.id
					LEFT JOIN nd_barang tB
					ON tA.barang_id_baru = tB.id
					LEFT JOIN nd_user tC
					ON tA.locked_by = tC.id
					LEFT JOIN nd_warna tD
					ON tA.warna_id = tD.id
				) a
				ON a.po_pembelian_batch_id = b.id
				LEFT JOIN (
					SELECT t1.*, satuan_id, t21.nama as nama_barang
					FROM nd_po_pembelian_detail t1
					LEFT JOIN nd_barang_beli t21
					ON t1.barang_beli_id = t21.id
					LEFT JOIN nd_barang t2
					ON t1.barang_id = t2.id
				) c
				ON a.po_pembelian_detail_id = c.id
				LEFT JOIN 
				(
					SELECT barang_id, warna_id, barang_beli_id, po_pembelian_batch_id, 
					group_concat(ifnull(qty,0) ORDER BY tanggal, t1.id asc) as qty_beli, 
					group_concat(t2.id ORDER BY tanggal, t1.id asc) as pembelian_id, 
					group_concat(no_faktur ORDER BY tanggal, t1.id asc) as no_faktur, 
					group_concat(tanggal ORDER BY tanggal, t1.id asc) as tanggal_beli, 
					group_concat(harga_beli ORDER BY tanggal, t1.id asc) as harga_beli, 
					group_concat(DATE_FORMAT(tanggal,'%d/%m/%y') ORDER BY tanggal, t1.id asc) as tanggal_datang, max(created_at) as last_datang
					FROM nd_pembelian_detail t1
					LEFT JOIN nd_pembelian t2
					ON t1.pembelian_id = t2.id
					GROUP BY barang_beli_id, barang_id, warna_id, po_pembelian_batch_id
					ORDER BY tanggal ASC
				) d
				ON a.warna_id = d.warna_id
				AND b.id = d.po_pembelian_batch_id
				AND d.barang_beli_id = c.barang_beli_id
				LEFT JOIN (
					SELECT barang_id, warna_id, sum(ifnull(qty,0)) as qty_retur, po_pembelian_batch_id,
					group_concat(ifnull(qty,0) ORDER BY tanggal, t1.id asc) as data_qty_retur,  
					group_concat(t2.id ORDER BY tanggal, t1.id asc) as retur_beli_id, 
					group_concat(no_sj ORDER BY tanggal, t1.id asc) as no_sj, 
					group_concat(tanggal ORDER BY tanggal, t1.id asc) as tanggal_retur
					FROM nd_retur_beli_detail t1
					LEFT JOIN nd_retur_beli t2
					ON t1.retur_beli_id = t2.id
					LEFT JOIN (
						SELECT retur_beli_detail_id, sum(qty * if(jumlah_roll = 0,1, jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll
						FROM nd_retur_beli_qty
						WHERE qty != 0
						GROUP BY retur_beli_detail_id
						) t3
					ON t3.retur_beli_detail_id = t1.id
					GROUP BY barang_id, warna_id, po_pembelian_batch_id
					ORDER BY tanggal ASC
				) h
				ON a.warna_id = h.warna_id
				AND b.id = h.po_pembelian_batch_id
				AND h.barang_id = c.barang_id
				LEFT JOIN (
					SELECT tA.id, qty_beli,qty_retur, no_faktur, tanggal_beli, tanggal_datang, pembelian_id, harga_beli, barang_id_baru, barang_beli_id_baru, 
					tA.harga_baru as harga, tA.warna_id, last_datang
					FROM (
						SELECT *
						FROM nd_po_pembelian_warna
						WHERE tipe_barang != 1
						) tA
					LEFT JOIN (
						SELECT barang_id, warna_id, po_pembelian_batch_id, 
						group_concat(ifnull(qty,0) ORDER BY tanggal, t1.id asc) as qty_beli, 
						group_concat(t2.id ORDER BY tanggal, t1.id asc) as pembelian_id, 
						group_concat(no_faktur ORDER BY tanggal, t1.id asc) as no_faktur, 
						group_concat(tanggal ORDER BY tanggal, t1.id asc) as tanggal_beli, 
						group_concat(harga_beli ORDER BY tanggal, t1.id asc) as harga_beli, 
						group_concat(DATE_FORMAT(tanggal,'%d/%m/%y') ORDER BY tanggal, t1.id asc) as tanggal_datang, max(created_at) as last_datang
						FROM nd_pembelian_detail t1
						LEFT JOIN nd_pembelian t2
						ON t1.pembelian_id = t2.id
						GROUP BY barang_id, warna_id, po_pembelian_batch_id
						ORDER BY tanggal ASC
						) tB
					ON tA.warna_id = tB.warna_id
					AND tA.po_pembelian_batch_id = tB.po_pembelian_batch_id
					AND tA.barang_id_baru = tB.barang_id
					LEFT JOIN (
						SELECT barang_id, warna_id,sum(ifnull(qty,0)) as qty_retur, po_pembelian_batch_id, 
						group_concat(ifnull(qty,0) ORDER BY tanggal, t1.id asc) as qty_retur_data, 
						group_concat(t2.id ORDER BY tanggal, t1.id asc) as retur_beli_id, 
						group_concat(no_sj ORDER BY tanggal, t1.id asc) as no_sj, 
						group_concat(tanggal ORDER BY tanggal, t1.id asc) as tanggal_retur
						FROM nd_retur_beli_detail t1
						LEFT JOIN nd_retur_beli t2
						ON t1.retur_beli_id = t2.id
						LEFT JOIN (
							SELECT retur_beli_detail_id, sum(qty * if(jumlah_roll = 0,1, jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll
							FROM nd_retur_beli_qty
							WHERE qty != 0
							GROUP BY retur_beli_detail_id
							) t3
						ON t3.retur_beli_detail_id = t1.id
						GROUP BY barang_id, warna_id, po_pembelian_batch_id
						ORDER BY tanggal ASC
						) tC
					ON tA.warna_id = tC.warna_id
					AND tA.po_pembelian_batch_id = tC.po_pembelian_batch_id
					AND tA.barang_id_baru = tC.barang_id
				) tA
				ON a.id = tA.id
				LEFT JOIN nd_warna e
				ON a.warna_id = e.id
				LEFT JOIN nd_satuan f
				ON c.satuan_id = f.id
				LEFT JOIN nd_po_pembelian_before_qty g
				ON a.id = g.po_pembelian_warna_id
				ORDER BY nama_barang, warna_jual asc
			");
		return $query->result();
	}

//================================get laporan pembayaran hutang==========================

	function get_laporan_hutang($tanggal_awal, $tanggal_akhir){
		$query = $this->db->query("SELECT t1.*, t3.nama as nama_supplier, t4.no_faktur_beli
			FROM (
				SELECT *
				FROM nd_pembayaran_hutang_nilai
				WHERE tanggal_transfer >='$tanggal_awal'
				AND tanggal_transfer <='$tanggal_akhir'
				) t1
			LEFT JOIN nd_pembayaran_hutang t2
			ON t1.pembayaran_hutang_id = t2.id
			LEFT JOIN nd_supplier t3
			ON t2.supplier_id = t3.id
			LEFT JOIN (
				SELECT GROUP_CONCAT(no_faktur SEPARATOR ', ') as no_faktur_beli, pembayaran_hutang_id
				FROM nd_pembayaran_hutang_detail tA
				LEFT JOIN nd_pembelian tB
				ON tA.pembelian_id = tB.id
				GROUP BY pembayaran_hutang_id
				) t4
			ON t1.pembayaran_hutang_id = t4.pembayaran_hutang_id
			");
		return $query->result();

	}

//================================get laporan u tutup buku=============================

	function data_tutup_buku_initial($tanggal_awal, $tanggal,$tanggal_end, $stok_opname_id){
		
		$query = $this->db->query("SELECT t1.barang_id, t1.warna_id, 
			ROUND(sum(qty_stock),2) as qty_stock, sum(jumlah_roll_stock) as jumlah_roll_stock, 
			ROUND(sum(qty_beli),2) as qty_beli, sum(jumlah_roll_beli) as jumlah_roll_beli, 
			ROUND(sum(qty_mutasi),2) as qty_mutasi, sum(jumlah_roll_mutasi) as jumlah_roll_mutasi, 
			ROUND(sum(qty_jual),2) as qty_jual, sum(jumlah_roll_jual) as jumlah_roll_jual, 
			ROUND(sum(qty_mutasi_masuk),2) as qty_mutasi_masuk, sum(jumlah_roll_mutasi_masuk) as jumlah_roll_mutasi_masuk, 
			ROUND(sum(qty_penyesuaian),2) as qty_penyesuaian, sum(jumlah_roll_penyesuaian) as jumlah_roll_penyesuaian, 
			ROUND(sum(qty_retur),2) as qty_retur, sum(jumlah_roll_retur) as jumlah_roll_retur ,
			hpp, hpp_beli, hpp_jual, t3.nama as nama_barang, nama_jual, warna_jual, t3.satuan_id, t1.gudang_id
			FROM (
				(
					SELECT 
						barang_id, 
						warna_id , 
						sum(ifnull(qty_masuk,0)) - sum(ifnull(qty_keluar,0)) as qty_stock, 
							sum(ifnull(jumlah_roll_masuk,0)) -sum(ifnull(jumlah_roll_keluar,0)) as jumlah_roll_stock, 
						0.00 as qty_beli, 0 as jumlah_roll_beli, 
						0.00 as qty_mutasi, 0 as jumlah_roll_mutasi, 
						0.00 as qty_jual, 0 as jumlah_roll_jual, 
						0.00 as qty_mutasi_masuk, 0 as jumlah_roll_mutasi_masuk, 
						0.00 as qty_penyesuaian, 0 as jumlah_roll_penyesuaian, 
						0 as jumlah_roll_retur, 0.00 as qty_retur,gudang_id
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
					        GROUP BY barang_id, warna_id, gudang_id
					    )UNION(
					    	SELECT barang_id, warna_id, gudang_id_after, sum(qty) as qty_masuk, sum(jumlah_roll) as jumlah_roll_masuk, CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar, 2 as tipe, t1.id
				        	FROM nd_mutasi_barang t1
				        	WHERE tanggal < '$tanggal'
				        	AND tanggal >= '$tanggal_awal'
				        	AND status_aktif = 1
					        GROUP BY barang_id, warna_id, gudang_id_after
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
					        GROUP BY barang_id, warna_id, gudang_id
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
					        GROUP BY barang_id, warna_id, gudang_id
					    )UNION(
					        SELECT barang_id, warna_id, gudang_id, sum(qty) as qty_masuk, sum(jumlah_roll) as jumlah_roll_masuk, CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar, 5 as tipe, t1.id
					        	FROM nd_penyesuaian_stok t1
					        	WHERE tipe_transaksi = 0
					        	AND tanggal < '$tanggal'
					        	AND tanggal >= '$tanggal_awal'
		                        GROUP BY barang_id, warna_id, gudang_id
					    )UNION(
					        SELECT  barang_id, warna_id, gudang_id, sum(qty) as qty_masuk, sum(jumlah_roll) as jumlah_roll_masuk, CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar, 6 as tipe, t1.id
				        	FROM nd_penyesuaian_stok t1
				        	WHERE tanggal < '$tanggal'
				        	AND tanggal >= '$tanggal_awal'
				        	AND tipe_transaksi = 1
				        	GROUP BY barang_id, warna_id, gudang_id
					    )UNION(
					        SELECT barang_id, warna_id, gudang_id, CAST(0 as DECIMAL(15,2)) as qty_masuk, 0 as jumlah_roll_masuk, sum(qty) as qty_keluar, sum(jumlah_roll) , 7 as tipe, t1.id
				        	FROM nd_penyesuaian_stok t1
				        	WHERE tanggal < '$tanggal'
				        	AND tanggal >= '$tanggal_awal'
				        	AND tipe_transaksi = 2
							GROUP BY barang_id, warna_id, gudang_id
					    )UNION(
					    	SELECT barang_id, warna_id, gudang_id_before, CAST(0 as DECIMAL(15,2)) as qty_masuk, 0 as jumlah_roll_masuk, sum(qty) as qty_keluar, sum(jumlah_roll) as jumlah_roll_keluar, 8 as tipe, t1.id
				        	FROM nd_mutasi_barang t1
				        	WHERE tanggal < '$tanggal'
				        	AND tanggal >= '$tanggal_awal'
				        	AND status_aktif = 1
							GROUP BY barang_id, warna_id, gudang_id_before
					    )UNION(
					    	SELECT barang_id, warna_id, gudang_id, sum(qty), sum(jumlah_roll) as jumlah_roll_masuk, 0 as qty_keluar, 0 as jumlah_roll_keluar, 9 as tipe, t1.id
				        	FROM nd_stok_opname_detail t1
				        	WHERE stok_opname_id = $stok_opname_id
				        	GROUP BY barang_id, warna_id, gudang_id
					    )
					) a
					GROUP BY barang_id, warna_id, gudang_id
				)UNION (
					SELECT barang_id, warna_id, 
						0.00 qty_stock, 0, 
						sum(qty) as qty_beli, sum(jumlah_roll) as jumlah_roll_beli, 
						0.00 as qty_mutasi , 0, 
						0.00 as qty_jual, 0 , 
						0.00 as qty_mutasi_masuk, 0,
						0.00 as qty_penyesuaian, 0,
						0 as jumlah_roll_retur, 0.00 as qty_retur,gudang_id
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
			        GROUP BY barang_id, warna_id, gudang_id
				)UNION(
			        SELECT barang_id, warna_id, 0.00, 0, 0.00, 0, 0.00,0, sum(qty), sum(jumlah_roll), 0.00, 0,0.00 ,0, 0 as jumlah_roll_retur, 0.00 as qty_retur,nd_penjualan_detail.gudang_id
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
				        GROUP BY barang_id, warna_id, gudang_id
			    )UNION (
					SELECT barang_id, warna_id, 0.00, 0, 0.00 , 0 , sum(qty) , sum(jumlah_roll),0.00 , 0, 0.00, 0,0.00 ,0, 0 as jumlah_roll_retur, 0.00 as qty_retur,gudang_id_before
			        	FROM nd_mutasi_barang
			        	WHERE tanggal >= '$tanggal'
			        	AND tanggal <= '$tanggal_end'
			        	AND status_aktif = 1
				        GROUP BY barang_id, warna_id, gudang_id_before
				)UNION (
					SELECT barang_id, warna_id, 0.00, 0, 0.00 , 0 , 0.00,0 ,0.00 ,0, sum(qty) , sum(jumlah_roll),0.00 ,0, 0 as jumlah_roll_retur, 0.00 as qty_retur,gudang_id_after
			        	FROM nd_mutasi_barang
			        	WHERE tanggal >= '$tanggal'
			        	AND tanggal <= '$tanggal_end'
			        	AND status_aktif = 1
				        GROUP BY barang_id, warna_id, gudang_id_after
				)UNION(
					SELECT barang_id, warna_id, 0.00, 0, 0.00 , 0 , 0.00,0 ,0.00 ,0, 0.00 , 0, sum(qty_masuk) - sum(qty_keluar), sum(jumlah_roll_masuk) - sum(jumlah_roll_keluar), 0 as jumlah_roll_retur, 0.00 as qty_retur,gudang_id
						FROM (
							(
						        SELECT  barang_id, warna_id, gudang_id, sum(qty) as qty_masuk, sum(jumlah_roll) as jumlah_roll_masuk, CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar
					        	FROM nd_penyesuaian_stok
					        	WHERE tanggal >= '$tanggal'
					        	AND tanggal <= '$tanggal_end'
					        	AND tipe_transaksi = 1
					        	GROUP BY barang_id, warna_id, gudang_id
						    )UNION(
						        SELECT barang_id, warna_id, gudang_id, CAST(0 as DECIMAL(15,2)) as qty_masuk, 0 as jumlah_roll_masuk, sum(qty) as qty_keluar, sum(jumlah_roll) as jumlah_roll_keluar
					        	FROM nd_penyesuaian_stok
					        	WHERE tanggal >= '$tanggal'
					        	AND tanggal <= '$tanggal_end'
					        	AND tipe_transaksi = 2
								GROUP BY barang_id, warna_id, gudang_id
							    )
							)a
				        GROUP BY barang_id, warna_id, gudang_id
				)UNION(
			    	SELECT barang_id, warna_id, 0.00, 0, 0.00 , 0 , 0.00,0 ,0.00 ,0, 0.00 , 0,0.00 ,0, jumlah_roll as jumlah_roll_retur, qty as qty_retur,gudang_id
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
			        GROUP BY barang_id, warna_id, gudang_id
			    )
			)t1
			LEFT JOIN (
				SELECT barang_id, warna_id, ROUND(sum(total_beli)/sum(qty_beli),2) as hpp, gudang_id
				FROM (
					(
						SELECT sum(qty) as qty_beli, sum(qty*harga_beli) as total_beli, barang_id, warna_id, nd_pembelian.gudang_id
						FROM (
							SELECT *
							FROM nd_pembelian
							WHERE tanggal < '$tanggal'
							) nd_pembelian
						LEFT JOIN nd_pembelian_detail
						ON nd_pembelian.id = nd_pembelian_detail.pembelian_id
						WHERE barang_id is not null
						GROUP BY YEAR(tanggal) , MONTH(tanggal), barang_id, warna_id, nd_pembelian.gudang_id
					)UNION(
						SELECT sum(qty) as qty_beli, sum(qty*harga_stok_awal) as total_beli, stok_awal.barang_id, stok_awal.warna_id, gudang_id
						FROM (
							SELECT sum(qty) as qty, barang_id, warna_id, gudang_id
							FROM nd_penyesuaian_stok
							WHERE tipe_transaksi = 0
							GROUP BY barang_id, warna_id, gudang_id
							) stok_awal 
						LEFT JOIN nd_stok_awal_item_harga
						ON stok_awal.barang_id = nd_stok_awal_item_harga.barang_id
						GROUP BY barang_id, warna_id, gudang_id
					) 
				)a
				GROUP BY barang_id, warna_id, gudang_id
			) t2
			ON t1.barang_id = t2.barang_id
			AND t1.warna_id = t2.warna_id
			AND t1.gudang_id = t2.gudang_id
			LEFT JOIN (
				SELECT barang_id, warna_id,sum(qty*harga_beli)/sum(qty) as hpp_beli, nd_pembelian.gudang_id
				FROM (
					SELECT *
					FROM nd_pembelian
					WHERE tanggal >= '$tanggal'
					AND tanggal <= '$tanggal_end'
					) nd_pembelian
				LEFT JOIN nd_pembelian_detail
				ON nd_pembelian.id = nd_pembelian_detail.pembelian_id
				WHERE barang_id is not null
				GROUP BY YEAR(tanggal) , MONTH(tanggal), barang_id, warna_id, nd_pembelian.gudang_id
			)t5
			ON t1.barang_id = t5.barang_id
			AND t1.warna_id = t5.warna_id 
			AND t1.gudang_id = t5.gudang_id 
			LEFT JOIN (
				SELECT barang_id, warna_id,sum(qty*harga_jual) as hpp_jual, nd_penjualan_detail.gudang_id
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
		        GROUP BY barang_id, warna_id, gudang_id
			)t6
			ON t1.barang_id = t6.barang_id
			AND t1.warna_id = t6.warna_id 
			AND t1.gudang_id = t6.gudang_id 
			LEFT JOIN nd_barang as t3
			ON t1.barang_id = t3.id
			LEFT JOIN nd_warna as t4
			ON t1.warna_id = t4.id
			GROUP BY barang_id, warna_id, t1.gudang_id
			ORDER BY t4.warna_jual asc

			", false);

		return $query->result();
	}

//================================so report======================================

	function get_so_report($tanggal_start, $tanggal_end, $barang_cond, $warna_cond, $gudang_cond){

		$query = $this->db->query("SELECT t1.*, t2.*, 
			concat(pre_po,'-',YEAR(t1.created_at),'/41/',LPAD(no_surat,3,'0') ) as no_so
			FROM (
				SELECT group_concat(nama_jual order by nama_jual, warna_jual asc) as nama_barang, 
				group_concat(barang_id order by nama_jual, warna_jual asc) as barang_id, 
				group_concat(warna_jual order by nama_jual, warna_jual asc) as nama_warna, 
				group_concat(warna_id order by nama_jual, warna_jual asc) as warna_id, 
				group_concat(stok_opname_id order by nama_jual, warna_jual asc) as stok_opname_id, 
				group_concat(penyesuaian_stok_id order by nama_jual, warna_jual asc) as penyesuaian_stok_id, 
				group_concat(t2.status_aktif order by nama_jual, warna_jual asc) as status_aktif, 
				group_concat(nd_gudang.nama order by nama_jual, warna_jual asc) as nama_gudang, 
				group_concat(gudang_id order by nama_jual, warna_jual asc) as gudang_id, 
				group_concat(t1.qty order by nama_jual, warna_jual asc) as qty_data, 	
				group_concat(t1.jumlah_roll order by nama_jual, warna_jual asc) as jumlah_roll_data, 
				group_concat(t2.stok_current order by nama_jual, warna_jual asc) as qty_current, 	
				group_concat(t2.roll_current order by nama_jual, warna_jual asc) as jumlah_roll_current, 
				group_concat(t3.qty order by nama_jual, warna_jual asc) as qty_data_penyesuaian, 	
				group_concat(t3.jumlah_roll order by nama_jual, warna_jual asc) as jumlah_roll_data_penyesuaian, 
				stok_opname_report_id, 
				GROUP_CONCAT(DATE_FORMAT(t2.created_at,'%d/%m/%Y %H:%i') order by nama_jual, warna_jual asc) as tanggal_so, pre_po
				FROM (
					SELECT barang_id, warna_id, gudang_id, sum(qty * jumlah_roll) as qty, sum(jumlah_roll) as jumlah_roll, stok_opname_id
					FROM nd_stok_opname_detail
					GROUP BY barang_id, warna_id, gudang_id, stok_opname_id
					)t1
				LEFT JOIN (
					SELECT *, 1 as toko_id
					FROM nd_stok_opname
					WHERE created_at >= '$tanggal_start'
					AND created_at <= '$tanggal_end'
					AND barang_id_so is not null
					AND warna_id_so is not null
					AND gudang_id_so is not null
					AND status_aktif = 1
					) t2
				ON t1.stok_opname_id = t2.id
				LEFT JOIN (
					SELECT keterangan, qty, jumlah_roll, id as penyesuaian_stok_id
					FROM nd_penyesuaian_stok
					WHERE tipe_transaksi = 4
				) t3
				ON t2.id = t3.keterangan
				LEFT JOIN nd_barang
				ON t1.barang_id = nd_barang.id
				LEFT JOIN nd_warna
				ON t1.warna_id = nd_warna.id
				LEFT JOIN nd_gudang
				ON t1.gudang_id = nd_gudang.id
				LEFT JOIN nd_toko
				ON t2.toko_id = nd_toko.id
				WHERE stok_opname_report_id is not null
				AND t2.id is not null
				$barang_cond
				$warna_cond
				$gudang_cond
				GROUP BY stok_opname_report_id
				) t2
			LEFT JOIN nd_stok_opname_report t1
			ON t2.stok_opname_report_id = t1.id 

			", false);

		return $query->result();
	}

//================================po request report======================================
	
	function get_po_request_report(){
		$this->db->simple_query('SET SESSION group_concat_max_len=15000');
		$tgl= date('Y-m-d H:i:s');
		$query = $this->db->query("SELECT request_barang_id, no_request_lengkap, 
			bulan_request_awal, tanggal_request_awal,
			group_concat(barang_id ORDER BY nama_barang asc SEPARATOR '??') as barang_id,
			group_concat(warna_id ORDER BY nama_barang asc SEPARATOR '??') as warna_id,
			group_concat(nama_barang ORDER BY nama_barang asc SEPARATOR '??') as nama_barang,
			group_concat(nama_warna ORDER BY nama_barang asc SEPARATOR '??') as nama_warna,
			group_concat(qty_request ORDER BY nama_barang asc SEPARATOR '??') as qty_request,
			group_concat(qty_datang ORDER BY nama_barang asc SEPARATOR '??') as qty_datang,
			group_concat(qty_data_request ORDER BY nama_barang asc SEPARATOR '??') as qty_data_request,
			group_concat(qty_data_datang ORDER BY nama_barang asc SEPARATOR '??') as qty_data_datang,
			group_concat(qty_data_request_perbulan ORDER BY nama_barang asc SEPARATOR '??') as qty_data_request_perbulan,
			group_concat(bulan_request ORDER BY nama_barang asc SEPARATOR '??') as bulan_request,
			group_concat(qty_datang_detail ORDER BY nama_barang asc SEPARATOR '??') as qty_datang_detail,
			group_concat(bulan_datang ORDER BY nama_barang asc SEPARATOR '??') as bulan_datang,
			group_concat(ifnull(warna_id_no_request,0)ORDER BY nama_barang asc  SEPARATOR '-?-') as warna_id_no_request,
			group_concat(nama_warna_no_request ORDER BY nama_barang asc SEPARATOR '??') as nama_warna_no_request,
			group_concat(qty_datang_no_request ORDER BY nama_barang asc SEPARATOR '??') as qty_datang_no_request,
			group_concat(qty_data_datang_no_request ORDER BY nama_barang asc SEPARATOR '??') as qty_data_datang_no_request
		FROM (
			SELECT supplier_id, request_barang_id, barang_id, DATE_ADD(group_concat(bulan_request_awal SEPARATOR ''),interval 1 year) as bulan_request_awal, tanggal_request_awal,
			sum(qty_request) as qty_request, 
			sum(qty_datang) as qty_datang, 
			group_concat(warna_id SEPARATOR '') as warna_id, 
			group_concat(qty_data_request  SEPARATOR '') as qty_data_request, 
			group_concat(qty_data_datang  SEPARATOR '') as qty_data_datang, 
			group_concat(qty_data  SEPARATOR '') as qty_data_request_perbulan,
			group_concat(bulan_request  SEPARATOR '') as bulan_request,
			group_concat(qty_datang_detail  SEPARATOR '') as qty_datang_detail,
			group_concat(bulan_datang  SEPARATOR '') as bulan_datang,
			group_concat(warna_id_no_request SEPARATOR '') as warna_id_no_request, 
			group_concat(qty_data_datang_no_request SEPARATOR '') as qty_data_datang_no_request, 
			group_concat(nama_warna_no_request SEPARATOR '') as nama_warna_no_request,
			sum(qty_datang_no_request) as qty_datang_no_request, 
			nama_barang, group_concat(nama_warna SEPARATOR '')  as nama_warna
			FROM (
				(
					SELECT supplier_id, barang_id, nama_barang, t1.request_barang_id,
						qty_request, qty_datang, warna_id, nama_warna,
						qty_data_request, qty_data_datang, 
						qty_data, bulan_request,
						qty_datang_detail, bulan_datang, bulan_request_awal, tanggal_request_awal,
						'' as warna_id_no_request, 0 as qty_datang_no_request,  
						'' as qty_data_datang_no_request,
						'' as nama_warna_no_request
					FROM (
						SELECT supplier_id, barang_id, nama as nama_barang, request_barang_id,
							sum(qty) as qty_request, 
							sum(ifnull(qty_datang,0)) as qty_datang, 
							group_concat(warna_id) as warna_id, 
							group_concat(warna_jual) as nama_warna, 
							group_concat(qty) as qty_data_request, 
							group_concat(qty_datang) as qty_data_datang,
							group_concat(bulan_request) as bulan_request, 
							group_concat(ifnull(qty_datang_detail,0)) as qty_datang_detail,
							group_concat(ifnull(bulan_datang,'x')) as bulan_datang, 
							group_concat(qty_data) as qty_data
						FROM (
							SELECT t1.barang_id, t1.warna_id, t1.qty, 
							sum(ifnull(t2.qty,0)) as qty_datang, 
							group_concat(ifnull(t2.qty,0) order by tanggal_beli ASC SEPARATOR '||' ) as qty_datang_detail, 
							group_concat(ifnull(DATE_FORMAT(tanggal_beli, '%Y-%m-%d'),'x') order by tanggal_beli ASC SEPARATOR '||') as bulan_datang, 
							request_barang_id, 
							closed_date, t1.tanggal, max(tanggal_beli) as last_datang, supplier_id, qty_data, bulan_request
							FROM (
								SELECT barang_id, min(tB.tanggal) as tanggal, warna_id, sum(qty) as qty, group_concat(qty ORDER BY bulan_request ASC SEPARATOR '||') as qty_data, request_barang_batch_id, 
								closed_date, tB.request_barang_id, supplier_id, group_concat(DATE_ADD(bulan_request,interval 1 year) ORDER BY bulan_request ASC SEPARATOR '||') as bulan_request, concat(DATE_FORMAT(max_bulan_request, '%Y-%m-31')) as max_tanggal
								FROM (
									SELECT *
									FROM nd_request_barang_qty
									WHERE id in (
										SELECT max(tA.id)
										FROM nd_request_barang_qty tA
										LEFT JOIN nd_request_barang_batch tB
										ON tA.request_barang_batch_id = tB.id
										GROUP BY barang_id, warna_id, request_barang_id, bulan_request
										)
								)tA
								LEFT JOIN nd_request_barang_batch tB
								ON tA.request_barang_batch_id = tB.id
								LEFT JOIN nd_request_barang tC
								ON tB.request_barang_id = tC.id
								LEFT JOIN (
									SELECT request_barang_id, DATE_ADD(min(bulan_request),interval 1 year) as max_bulan_request
									FROM nd_request_barang_qty tX
									LEFT JOIN nd_request_barang_batch tY
									ON tX.request_barang_batch_id = tY.id
									GROUP BY request_barang_id
								) tD
								ON tD.request_barang_id = tB.request_barang_id
								GROUP BY barang_id, warna_id, request_barang_id
							)t1
							LEFT JOIN (
								SELECT barang_id, warna_id, qty, jumlah_roll, created_at, tanggal as tanggal_beli
								FROM nd_pembelian_detail tA
								LEFT JOIN nd_pembelian tB
								ON tA.pembelian_id = tB.id
							)t2
							ON t1.barang_id = t2.barang_id
							AND t1.warna_id = t2.warna_id
							AND max_tanggal >= t2.tanggal_beli
							AND t1.tanggal <= t2.tanggal_beli
							AND t1.bulan_request <= t2.tanggal_beli
							GROUP BY t1.barang_id, t1.warna_id, request_barang_id
						)result
						LEFT JOIN nd_barang
						ON result.barang_id = nd_barang.id 
						LEFT JOIN nd_warna 
						ON result.warna_id = nd_warna.id
						GROUP BY barang_id, request_barang_id, supplier_id
						ORDER BY nd_barang.nama asc
					)t1
					LEFT JOIN (
						SELECT request_barang_id, t12.request_barang_batch_id, min(bulan_request) as bulan_request_awal, min(t12.tanggal) as tanggal_request_awal
						FROM (
							SELECT min(bulan_request) as bulan_request, request_barang_batch_id
							FROM nd_request_barang_qty
							GROUP BY request_barang_batch_id
						)t11
						LEFT JOIN (
							SELECT request_barang_id, tanggal, id as request_barang_batch_id
							FROM nd_request_barang_batch
							WHERE id IN (
								SELECT id
								FROM nd_request_barang_batch
								WHERE batch = 1
							)
						)t12
						ON t11.request_barang_batch_id = t12.request_barang_batch_id
						WHERE t12.request_barang_batch_id is not null
						GROUP BY request_barang_id
					)t2
					ON t1.request_barang_id = t2.request_barang_id
					
				)UNION(
					SELECT supplier_id, barang_id, nama as nama_barang, request_barang_id,
						0, 0 ,'', '',
						'', '','','',
						'','','', '',
						group_concat(warna_id) as warna_id, sum(qty_datang), group_concat(qty_datang), group_concat(warna_jual)
					FROM (
						SELECT t2.barang_id, t2.warna_id, sum(ifnull(t2.qty,0)) as qty_datang, min(tanggal_beli) as first_datang, max(tanggal_beli) as last_datang, t3.closed_date, t3.request_barang_id, t2.supplier_id
						FROM (
                            SELECT barang_id, warna_id, sum(qty) as qty, sum(jumlah_roll) as jumlah_roll,  DATE_FORMAT(created_at, '%Y-%m-%d') , supplier_id, tanggal as tanggal_beli
                            FROM nd_pembelian_detail tA
                            LEFT JOIN nd_pembelian tB
                            ON tA.pembelian_id = tB.id
                            GROUP BY pembelian_id
                        )t2
                        LEFT JOIN (
							SELECT barang_id, if(min(tB.tanggal) > DATE_ADD(tB.bulan_request,interval 1 year), tB.tanggal, DATE_ADD(tB.bulan_request,interval 1 year)) as tanggal, 
                            warna_id, sum(qty) as qty, request_barang_batch_id, request_barang_id, tB.bulan_request, 
                            DATE_FORMAT(DATE_ADD(tB.bulan_request,interval 1 year), '%Y-%m-31') as closed_date
                            FROM (
                                SELECT *
                                FROM nd_request_barang_qty
                                WHERE id in (
                                    SELECT max(tA.id)
                                    FROM nd_request_barang_qty tA
                                    LEFT JOIN nd_request_barang_batch tB
                                    ON tA.request_barang_batch_id = tB.id
                                    GROUP BY barang_id, warna_id, request_barang_id, bulan_request
                                    )
                            )tA
                            LEFT JOIN (
                                SELECT t1.*, bulan_request
                                FROM nd_request_barang_batch t1
                                LEFT JOIN (
                                    SELECT min(bulan_request) as bulan_request, request_barang_batch_id
                                    FROM nd_request_barang_qty
                                    GROUP BY request_barang_batch_id
                                ) t2
                                ON t2.request_barang_batch_id = t1.id
                            ) tB
                            ON tA.request_barang_batch_id = tB.id
                            AND tA.bulan_request=tB.bulan_request
                            LEFT JOIN nd_request_barang tC
                            ON tB.request_barang_id = tC.id
                            GROUP BY barang_id, warna_id, request_barang_id
                        )t1
                        ON t1.barang_id = t2.barang_id
                        AND t1.warna_id = t2.warna_id
                        AND t1.closed_date >= t2.tanggal_beli
                        AND t1.tanggal <= t2.tanggal_beli
						LEFT JOIN (
							SELECT bulan_request, bulan_closed_date as closed_date, request_barang_id, supplier_id,
                                if(tB.tanggal > bulan_request, tB.tanggal, bulan_request) as tanggal
							FROM (
                                SELECT min(DATE_ADD(bulan_request,interval 1 year))  as bulan_request, DATE_FORMAT(min(DATE_ADD(bulan_request,interval 1 year)), '%Y-%m-31') as bulan_closed_date,request_barang_batch_id
                                FROM nd_request_barang_qty
                                GROUP BY request_barang_batch_id
                            ) tA
							LEFT JOIN nd_request_barang_batch tB
							ON tA.request_barang_batch_id = tB.id
							LEFT JOIN nd_request_barang tC
							ON tB.request_barang_id = tC.id
							GROUP BY  request_barang_id
						)t3
						ON t3.closed_date >= t2.tanggal_beli
						AND t3.tanggal <= t2.tanggal_beli
						AND t2.supplier_id = t3.supplier_id
						WHERE t3.request_barang_id is not null
                        AND t1.request_barang_id is null
						GROUP BY t2.barang_id, t2.warna_id, t3.request_barang_id	
					)result
					LEFT JOIN nd_barang
					ON result.barang_id = nd_barang.id 
					LEFT JOIN nd_warna 
					ON result.warna_id = nd_warna.id
					GROUP BY barang_id, request_barang_id, supplier_id
					ORDER BY nd_barang.nama asc

				)
			)result
			GROUP BY barang_id, request_barang_id, supplier_id
			ORDER BY nama_barang, nama_warna asc
		)result
		LEFT JOIN (
			SELECT tA.id, concat(pre_po,'-', DATE_FORMAT(tA.tanggal, '%Y'),'/31/', LPAD(no_request,3,'0')) as no_request_lengkap
			FROM nd_request_barang tA
			LEFT JOIN nd_toko tB
			ON tA.toko_id = tB.id
		)request
		ON result.request_barang_id = request.id
		GROUP BY request_barang_id, supplier_id
		ORDER BY request_barang_id desc
		", false);

		return $query->result();
	}

	function get_po_request_report_by_input(){
		$this->db->simple_query('SET SESSION group_concat_max_len=15000');
		$tgl= date('Y-m-d H:i:s');
		$query = $this->db->query("SELECT request_barang_id, no_request_lengkap, 
			bulan_request_awal, tanggal_request_awal,
			group_concat(barang_id ORDER BY nama_barang asc SEPARATOR '??') as barang_id,
			group_concat(warna_id ORDER BY nama_barang asc SEPARATOR '??') as warna_id,
			group_concat(nama_barang ORDER BY nama_barang asc SEPARATOR '??') as nama_barang,
			group_concat(nama_warna ORDER BY nama_barang asc SEPARATOR '??') as nama_warna,
			group_concat(qty_request ORDER BY nama_barang asc SEPARATOR '??') as qty_request,
			group_concat(qty_datang ORDER BY nama_barang asc SEPARATOR '??') as qty_datang,
			group_concat(qty_data_request ORDER BY nama_barang asc SEPARATOR '??') as qty_data_request,
			group_concat(qty_data_datang ORDER BY nama_barang asc SEPARATOR '??') as qty_data_datang,
			group_concat(qty_data_request_perbulan ORDER BY nama_barang asc SEPARATOR '??') as qty_data_request_perbulan,
			group_concat(bulan_request ORDER BY nama_barang asc SEPARATOR '??') as bulan_request,
			group_concat(qty_datang_detail ORDER BY nama_barang asc SEPARATOR '??') as qty_datang_detail,
			group_concat(bulan_datang ORDER BY nama_barang asc SEPARATOR '??') as bulan_datang,
			group_concat(ifnull(warna_id_no_request,0)ORDER BY nama_barang asc  SEPARATOR '-?-') as warna_id_no_request,
			group_concat(nama_warna_no_request ORDER BY nama_barang asc SEPARATOR '??') as nama_warna_no_request,
			group_concat(qty_datang_no_request ORDER BY nama_barang asc SEPARATOR '??') as qty_datang_no_request,
			group_concat(qty_data_datang_no_request ORDER BY nama_barang asc SEPARATOR '??') as qty_data_datang_no_request
		FROM (
			SELECT supplier_id, request_barang_id, barang_id, DATE_ADD(group_concat(bulan_request_awal SEPARATOR ''),interval 1 year) as bulan_request_awal, tanggal_request_awal,
			sum(qty_request) as qty_request, 
			sum(qty_datang) as qty_datang, 
			group_concat(warna_id SEPARATOR '') as warna_id, 
			group_concat(qty_data_request  SEPARATOR '') as qty_data_request, 
			group_concat(qty_data_datang  SEPARATOR '') as qty_data_datang, 
			group_concat(qty_data  SEPARATOR '') as qty_data_request_perbulan,
			group_concat(bulan_request  SEPARATOR '') as bulan_request,
			group_concat(qty_datang_detail  SEPARATOR '') as qty_datang_detail,
			group_concat(bulan_datang  SEPARATOR '') as bulan_datang,
			group_concat(warna_id_no_request SEPARATOR '') as warna_id_no_request, 
			group_concat(qty_data_datang_no_request SEPARATOR '') as qty_data_datang_no_request, 
			group_concat(nama_warna_no_request SEPARATOR '') as nama_warna_no_request,
			sum(qty_datang_no_request) as qty_datang_no_request, 
			nama_barang, group_concat(nama_warna SEPARATOR '')  as nama_warna
			FROM (
				(
					SELECT supplier_id, barang_id, nama_barang, t1.request_barang_id,
						qty_request, qty_datang, warna_id, nama_warna,
						qty_data_request, qty_data_datang, 
						qty_data, bulan_request,
						qty_datang_detail, bulan_datang, bulan_request_awal, tanggal_request_awal,
						'' as warna_id_no_request, 0 as qty_datang_no_request,  
						'' as qty_data_datang_no_request,
						'' as nama_warna_no_request
					FROM (
						SELECT supplier_id, barang_id, nama as nama_barang, request_barang_id,
							sum(qty) as qty_request, 
							sum(ifnull(qty_datang,0)) as qty_datang, 
							group_concat(warna_id) as warna_id, 
							group_concat(warna_jual) as nama_warna, 
							group_concat(qty) as qty_data_request, 
							group_concat(qty_datang) as qty_data_datang,
							group_concat(bulan_request) as bulan_request, 
							group_concat(ifnull(qty_datang_detail,0)) as qty_datang_detail,
							group_concat(ifnull(bulan_datang,'x')) as bulan_datang, 
							group_concat(qty_data) as qty_data
						FROM (
							SELECT t1.barang_id, t1.warna_id, t1.qty, 
							sum(ifnull(t2.qty,0)) as qty_datang, 
							group_concat(ifnull(t2.qty,0) order by tanggal_beli ASC SEPARATOR '||' ) as qty_datang_detail, 
							group_concat(ifnull(DATE_FORMAT(tanggal_beli, '%Y-%m-%d'),'x') order by tanggal_beli ASC SEPARATOR '||') as bulan_datang, 
							request_barang_id, 
							closed_date, t1.tanggal, max(tanggal_beli) as last_datang, supplier_id, qty_data, bulan_request
							FROM (
								SELECT barang_id, min(tB.tanggal) as tanggal, warna_id, sum(qty) as qty, group_concat(qty ORDER BY bulan_request ASC SEPARATOR '||') as qty_data, request_barang_batch_id, 
								closed_date, tB.request_barang_id, supplier_id, group_concat(DATE_ADD(bulan_request,interval 1 year) ORDER BY bulan_request ASC SEPARATOR '||') as bulan_request, concat(DATE_FORMAT(max_bulan_request, '%Y-%m-31')) as max_tanggal
								FROM (
									SELECT *
									FROM nd_request_barang_qty
									WHERE id in (
										SELECT max(tA.id)
										FROM nd_request_barang_qty tA
										LEFT JOIN nd_request_barang_batch tB
										ON tA.request_barang_batch_id = tB.id
										GROUP BY barang_id, warna_id, request_barang_id, bulan_request
										)
								)tA
								LEFT JOIN nd_request_barang_batch tB
								ON tA.request_barang_batch_id = tB.id
								LEFT JOIN nd_request_barang tC
								ON tB.request_barang_id = tC.id
								LEFT JOIN (
									SELECT request_barang_id, DATE_ADD(min(bulan_request),interval 1 year) as max_bulan_request
									FROM nd_request_barang_qty tX
									LEFT JOIN nd_request_barang_batch tY
									ON tX.request_barang_batch_id = tY.id
									GROUP BY request_barang_id
								) tD
								ON tD.request_barang_id = tB.request_barang_id
								GROUP BY barang_id, warna_id, request_barang_id
							)t1
							LEFT JOIN (
								SELECT barang_id, warna_id, qty, jumlah_roll, created_at, DATE_FORMAT(created_at, '%Y-%m-%d') as tanggal_beli
								FROM nd_pembelian_detail tA
								LEFT JOIN nd_pembelian tB
								ON tA.pembelian_id = tB.id
							)t2
							ON t1.barang_id = t2.barang_id
							AND t1.warna_id = t2.warna_id
							AND max_tanggal >= t2.tanggal_beli
							AND t1.tanggal <= t2.tanggal_beli
							AND t1.bulan_request <= t2.tanggal_beli
							GROUP BY t1.barang_id, t1.warna_id, request_barang_id
						)result
						LEFT JOIN nd_barang
						ON result.barang_id = nd_barang.id 
						LEFT JOIN nd_warna 
						ON result.warna_id = nd_warna.id
						GROUP BY barang_id, request_barang_id, supplier_id
						ORDER BY nd_barang.nama asc
					)t1
					LEFT JOIN (
						SELECT request_barang_id, t12.request_barang_batch_id, min(bulan_request) as bulan_request_awal, min(t12.tanggal) as tanggal_request_awal
						FROM (
							SELECT min(bulan_request) as bulan_request, request_barang_batch_id
							FROM nd_request_barang_qty
							GROUP BY request_barang_batch_id
						)t11
						LEFT JOIN (
							SELECT request_barang_id, tanggal, id as request_barang_batch_id
							FROM nd_request_barang_batch
							WHERE id IN (
								SELECT id
								FROM nd_request_barang_batch
								WHERE batch = 1
							)
						)t12
						ON t11.request_barang_batch_id = t12.request_barang_batch_id
						WHERE t12.request_barang_batch_id is not null
						GROUP BY request_barang_id
					)t2
					ON t1.request_barang_id = t2.request_barang_id
					
				)UNION(
					SELECT supplier_id, barang_id, nama as nama_barang, request_barang_id,
						0, 0 ,'', '',
						'', '','','',
						'','','', '',
						group_concat(warna_id) as warna_id, sum(qty_datang), group_concat(qty_datang), group_concat(warna_jual)
					FROM (
						SELECT t2.barang_id, t2.warna_id, sum(ifnull(t2.qty,0)) as qty_datang, min(tanggal_beli) as first_datang, max(tanggal_beli) as last_datang, t3.closed_date, t3.request_barang_id, t2.supplier_id
						FROM (
                            SELECT barang_id, warna_id, sum(qty) as qty, sum(jumlah_roll) as jumlah_roll,  DATE_FORMAT(created_at, '%Y-%m-%d') as tanggal_beli, supplier_id, tanggal 
                            FROM nd_pembelian_detail tA
                            LEFT JOIN nd_pembelian tB
                            ON tA.pembelian_id = tB.id
                            GROUP BY pembelian_id
                        )t2
                        LEFT JOIN (
							SELECT barang_id, if(min(tB.tanggal) > DATE_ADD(tB.bulan_request,interval 1 year), tB.tanggal, DATE_ADD(tB.bulan_request,interval 1 year)) as tanggal, 
                            warna_id, sum(qty) as qty, request_barang_batch_id, request_barang_id, tB.bulan_request, 
                            DATE_FORMAT(DATE_ADD(tB.bulan_request,interval 1 year), '%Y-%m-31') as closed_date
                            FROM (
                                SELECT *
                                FROM nd_request_barang_qty
                                WHERE id in (
                                    SELECT max(tA.id)
                                    FROM nd_request_barang_qty tA
                                    LEFT JOIN nd_request_barang_batch tB
                                    ON tA.request_barang_batch_id = tB.id
                                    GROUP BY barang_id, warna_id, request_barang_id, bulan_request
                                    )
                            )tA
                            LEFT JOIN (
                                SELECT t1.*, bulan_request
                                FROM nd_request_barang_batch t1
                                LEFT JOIN (
                                    SELECT min(bulan_request) as bulan_request, request_barang_batch_id
                                    FROM nd_request_barang_qty
                                    GROUP BY request_barang_batch_id
                                ) t2
                                ON t2.request_barang_batch_id = t1.id
                            ) tB
                            ON tA.request_barang_batch_id = tB.id
                            AND tA.bulan_request=tB.bulan_request
                            LEFT JOIN nd_request_barang tC
                            ON tB.request_barang_id = tC.id
                            GROUP BY barang_id, warna_id, request_barang_id
                        )t1
                        ON t1.barang_id = t2.barang_id
                        AND t1.warna_id = t2.warna_id
                        AND t1.closed_date >= t2.tanggal_beli
                        AND t1.tanggal <= t2.tanggal_beli
						LEFT JOIN (
							SELECT bulan_request, bulan_closed_date as closed_date, request_barang_id, supplier_id,
                                if(tB.tanggal > bulan_request, tB.tanggal, bulan_request) as tanggal
							FROM (
                                SELECT min(DATE_ADD(bulan_request,interval 1 year))  as bulan_request, DATE_FORMAT(min(DATE_ADD(bulan_request,interval 1 year)), '%Y-%m-31') as bulan_closed_date,request_barang_batch_id
                                FROM nd_request_barang_qty
                                GROUP BY request_barang_batch_id
                            ) tA
							LEFT JOIN nd_request_barang_batch tB
							ON tA.request_barang_batch_id = tB.id
							LEFT JOIN nd_request_barang tC
							ON tB.request_barang_id = tC.id
							GROUP BY  request_barang_id
						)t3
						ON t3.closed_date >= t2.tanggal_beli
						AND t3.tanggal <= t2.tanggal_beli
						AND t2.supplier_id = t3.supplier_id
						WHERE t3.request_barang_id is not null
                        AND t1.request_barang_id is null
						GROUP BY t2.barang_id, t2.warna_id, t3.request_barang_id	
				
					)result
					LEFT JOIN nd_barang
					ON result.barang_id = nd_barang.id 
					LEFT JOIN nd_warna 
					ON result.warna_id = nd_warna.id
					GROUP BY barang_id, request_barang_id, supplier_id
					ORDER BY nd_barang.nama asc

				)
			)result
			GROUP BY barang_id, request_barang_id, supplier_id
			ORDER BY nama_barang, nama_warna asc
		)result
		LEFT JOIN (
			SELECT tA.id, concat(pre_po,'-', DATE_FORMAT(tA.tanggal, '%Y'),'/31/', LPAD(no_request,3,'0')) as no_request_lengkap
			FROM nd_request_barang tA
			LEFT JOIN nd_toko tB
			ON tA.toko_id = tB.id
		)request
		ON result.request_barang_id = request.id
		GROUP BY request_barang_id, supplier_id
		ORDER BY request_barang_id desc
		", false);

		return $query->result();
	}

	function get_request_barang_data($request_barang_id){
		$query = $this->db->query("SELECT t2.id as id, no_request, supplier_id, t4.nama as nama_supplier, bulan_awal as bulan_request_awal, 
				concat(pre_po,'-', DATE_FORMAT(t2.tanggal, '%Y'),'/31/', LPAD(no_request,3,'0')) as no_request_lengkap, closed_date
				FROM nd_request_barang t2
				LEFT JOIN nd_toko t3
				ON t2.toko_id = t3.id
				LEFT JOIN nd_supplier t4
				ON t2.supplier_id = t4.id
				LEFT JOIN (
					SELECT DATE_ADD(min(bulan_request),interval 1 year) as bulan_awal, request_barang_id
					FROM nd_request_barang_qty tA
					LEFT JOIN nd_request_barang_batch tB
					ON tA.request_barang_batch_id = tB.id
					GROUP BY request_barang_id
				) t5
				ON t2.id = t5.request_barang_id
				WHERE t2.id = $request_barang_id
				");

		return $query->result();
	}

	function get_po_request_report_detail($request_barang_id){
		$tgl= date('Y-m-d H:i:s');
		$query = $this->db->query("SELECT supplier_id, request_barang_id, barang_id, 
		sum(qty_request) as qty_request, 
		sum(qty_datang) as qty_datang, group_concat(warna_id) as warna_id, 
		group_concat(qty_data_request) as qty_data_request, 
		group_concat(qty_data_datang) as qty_data_datang, 
		nama_barang, nama_warna, warna_id, 
		group_concat(warna_id_no_request) as warna_id_no_request, 
		sum(qty_datang_no_request) as qty_datang_no_request, 
		group_concat(qty_data_datang_no_request) as qty_data_datang_no_request, 
		group_concat(nama_warna_no_request) as nama_warna_no_request
		FROM (
			(
				SELECT supplier_id, barang_id,nama as nama_barang,  request_barang_id,
					sum(qty) as qty_request, sum(ifnull(qty_datang,0)) as qty_datang, group_concat(warna_id ORDER BY warna_jual ASC) as warna_id, group_concat(warna_jual ORDER BY warna_jual ASC) as nama_warna, group_concat(qty ORDER BY warna_jual ASC) as qty_data_request, group_concat(qty_datang ORDER BY warna_jual ASC) as qty_data_datang,
					'' as warna_id_no_request, 0 as qty_datang_no_request,  '' as qty_data_datang_no_request, '' as nama_warna_no_request
				FROM (
					SELECT t1.barang_id, t1.warna_id, t1.qty, sum(ifnull(t2.qty,0)) as qty_datang, request_barang_id, closed_date, t1.tanggal, max(created_at) as last_datang, supplier_id
					FROM (
						SELECT barang_id, min(tB.tanggal) as tanggal, warna_id, sum(qty) as qty, request_barang_batch_id, closed_date, request_barang_id, supplier_id
						FROM (
							SELECT *
							FROM nd_request_barang_qty
							WHERE id in (
								SELECT max(tA.id)
								FROM nd_request_barang_qty tA
								LEFT JOIN nd_request_barang_batch tB
								ON tA.request_barang_batch_id = tB.id
								WHERE tB.request_barang_id = $request_barang_id
								AND tB.id is not null
								GROUP BY barang_id, warna_id, request_barang_id, bulan_request
								)
						)tA
						LEFT JOIN nd_request_barang_batch tB
						ON tA.request_barang_batch_id = tB.id
						LEFT JOIN nd_request_barang tC
						ON tB.request_barang_id = tC.id
						GROUP BY barang_id, warna_id, request_barang_id
					)t1
					LEFT JOIN (
						SELECT barang_id, warna_id, qty, jumlah_roll, created_at
						FROM nd_pembelian_detail tA
						LEFT JOIN nd_pembelian tB
						ON tA.pembelian_id = tB.id
					)t2
					ON t1.barang_id = t2.barang_id
					AND t1.warna_id = t2.warna_id
					AND ifnull(t1.closed_date,'$tgl') >= nama_jual, warna_jual
					AND t1.tanggal <= t2.created_at
					GROUP BY t1.barang_id, t1.warna_id, request_barang_id 
				)result
				LEFT JOIN nd_barang
				ON result.barang_id = nd_barang.id 
				LEFT JOIN nd_warna 
				ON result.warna_id = nd_warna.id
				GROUP BY barang_id, request_barang_id, supplier_id
				ORDER BY nd_barang.nama asc
			)UNION(
				SELECT supplier_id, barang_id, nama as nama_barang, request_barang_id,
					0, 0 ,'', '','', '',
					group_concat(warna_id) as warna_id, sum(qty_datang), group_concat(qty_datang), group_concat(warna_jual)
				FROM (
					SELECT t2.barang_id, t2.warna_id, sum(ifnull(t2.qty,0)) as qty_datang, min(created_at) as first_datang, max(created_at) as last_datang, t3.closed_date, t3.request_barang_id, t2.supplier_id
						FROM (
							SELECT barang_id, warna_id, qty, jumlah_roll, created_at, supplier_id
							FROM nd_pembelian_detail tA
							LEFT JOIN nd_pembelian tB
							ON tA.pembelian_id = tB.id
						)t2
						LEFT JOIN (
							SELECT barang_id, min(tB.tanggal) as tanggal, warna_id, sum(qty) as qty, request_barang_batch_id, closed_date, request_barang_id
							FROM (
								SELECT *
								FROM nd_request_barang_qty
								WHERE id in (
									SELECT max(tA.id)
									FROM nd_request_barang_qty tA
									LEFT JOIN nd_request_barang_batch tB
									ON tA.request_barang_batch_id = tB.id
									WHERE tB.request_barang_id = $request_barang_id
									AND tB.id is not null
									GROUP BY barang_id, warna_id, request_barang_id, bulan_request
								)
							)tA
							LEFT JOIN nd_request_barang_batch tB
							ON tA.request_barang_batch_id = tB.id
							LEFT JOIN nd_request_barang tC
							ON tB.request_barang_id = tC.id
							GROUP BY barang_id, warna_id, request_barang_id
						)t1
						ON t1.barang_id = t2.barang_id
						AND t1.warna_id = t2.warna_id
						AND ifnull(t1.closed_date,'$tgl') >= t2.created_at
						AND t1.tanggal <= t2.created_at
						LEFT JOIN (
							SELECT min(tB.tanggal) as tanggal, ifnull(closed_date,'$tgl') as closed_date, request_barang_id, supplier_id
							FROM (
								SELECT *
								FROM nd_request_barang_qty
								WHERE id in (
									SELECT max(tA.id)
									FROM nd_request_barang_qty tA
									LEFT JOIN nd_request_barang_batch tB
									ON tA.request_barang_batch_id = tB.id
									WHERE tB.request_barang_id = $request_barang_id
									AND tB.id is not null
									GROUP BY barang_id, warna_id, request_barang_id, bulan_request
								)
							)tA
							LEFT JOIN nd_request_barang_batch tB
							ON tA.request_barang_batch_id = tB.id
							LEFT JOIN nd_request_barang tC
							ON tB.request_barang_id = tC.id
							GROUP BY  request_barang_id
						)t3
						ON t3.closed_date >= t2.created_at
						AND t3.tanggal <= t2.created_at
						AND t2.supplier_id = t3.supplier_id
						WHERE t1.barang_id is null
						AND t1.warna_id is null
						AND t3.closed_date is not null
						GROUP BY t2.barang_id, t2.warna_id, t3.request_barang_id
				)result
				LEFT JOIN nd_barang
				ON result.barang_id = nd_barang.id 
				LEFT JOIN nd_warna 
				ON result.warna_id = nd_warna.id
				GROUP BY barang_id, request_barang_id, supplier_id
				ORDER BY nd_barang.nama asc

			)
		)result
		GROUP BY barang_id, request_barang_id, supplier_id
		ORDER BY nama_barang asc
		", false);

		return $query->result();
	}

	function get_bulan_request_by_batch($request_barang_id){
		$tgl= date('Y-m-d H:i:s');
		$query = $this->db->query("SELECT group_concat(bulan_request ORDER BY bulan_request ASC) as bulan_request, request_barang_batch_id, group_concat(DATE_ADD(bulan_request,interval 1 year) ORDER BY bulan_request ASC) as bln_request
			FROM (
				SELECT bulan_request, request_barang_batch_id
				FROM nd_request_barang_qty t1
				LEFT JOIN nd_request_barang_batch t2
				ON t1.request_barang_batch_id = t2.id
				WHERE request_barang_id = $request_barang_id
				GROUP by request_barang_batch_id, bulan_request
				) result
			GROUP BY request_barang_batch_id
			ORDER BY request_barang_batch_id ASC
		");
		return $query->result();
	}

	function get_all_bulan_request($request_barang_id){
		$tgl= date('Y-m-d H:i:s');
		$query = $this->db->query("SELECT DATE_ADD(bulan_request,interval 1 year) as bulan_request
			FROM nd_request_barang_qty t1
			LEFT JOIN nd_request_barang_batch t2
			ON t1.request_barang_batch_id = t2.id
			WHERE request_barang_id = $request_barang_id
			GROUP by request_barang_id, bulan_request
		");
		return $query->result();
	}

	/* function get_po_request_urgent_detail($request_barang_id){
		$tgl= date('Y-m-d H:i:s');
		$query = $this->db->query("SELECT t1.*, sum(qty_datang) as qty_datang, group_concat(qty_datang) as qty_datang_data, group_concat(t4.created_at) as tgl_datang, group_concat(t4.po_pembelian_batch_id) as po_pembelian_batch_id_datang
			FROM (
				SELECT *
				FROM nd_request_barang_detail
				WHERE status_urgent = 1
				AND id IN (
					SELECT max(tA.id)
					FROM nd_request_barang_detail tA
					LEFT JOIN nd_request_barang_batch tB
					ON tA.request_barang_batch_id = tB.id
					WHERE tB.request_barang_id = $request_barang_id
					AND tB.id is not null
					GROUP BY barang_id, warna_id, request_barang_id, bulan_request
				)
			)t1
			LEFT JOIN nd_request_barang_batch t2
			ON t1.request_barang_batch_id = t2.id
			LEFT JOIN nd_request_barang t3
			ON t2.request_barang_id = t3.id
			LEFT JOIN (
				SELECT barang_id, warna_id, qty as qty_datang, created_at, po_pembelian_batch_id
				FROM nd_pembelian_detail tA
				LEFT JOIN nd_pembelian tB
				ON tA.pembelian_id = tB.id
			)t4
			ON t1.barang_id = t4.barang_id
			AND t1.warna_id = t4.warna_id
			AND ifnull(t3.closed_date,'$tgl') >= t4.created_at
			AND min(t2.tanggal <= t4.created_at
            GROUP BY barang_id, warna_id, bulan_request
		", false);

		return $query->result();
	}

	function get_po_request_datang_detail($request_barang_id){
		$tgl= date('Y-m-d H:i:s');
		$query = $this->db->query("SELECT t1.*, sum(qty_datang) as qty_datang, group_concat(qty_datang) as qty_datang_data, group_concat(t4.created_at) as tgl_datang, group_concat(t4.po_pembelian_batch_id) as po_pembelian_batch_id_datang
			FROM (
				SELECT *
				FROM nd_request_barang_detail
				WHERE status_urgent = 1
				AND id IN (
					SELECT max(tA.id)
					FROM nd_request_barang_detail tA
					LEFT JOIN nd_request_barang_batch tB
					ON tA.request_barang_batch_id = tB.id
					WHERE tB.request_barang_id = $request_barang_id
					AND tB.id is not null
					GROUP BY barang_id, warna_id, request_barang_id, bulan_request
				)
			)t1
			LEFT JOIN nd_request_barang_batch t2
			ON t1.request_barang_batch_id = t2.id
			LEFT JOIN nd_request_barang t3
			ON t2.request_barang_id = t3.id
			LEFT JOIN (
				SELECT barang_id, warna_id, qty as qty_datang, created_at, po_pembelian_batch_id
				FROM nd_pembelian_detail tA
				LEFT JOIN nd_pembelian tB
				ON tA.pembelian_id = tB.id
			)t4
			ON t1.barang_id = t4.barang_id
			AND t1.warna_id = t4.warna_id
			AND ifnull(t3.closed_date,'$tgl') >= t4.created_at
			AND min(t2.tanggal) <= t4.created_at
            AND t1.po_pembelian_batch_id = t4.po_pembelian_batch_id
            GROUP BY barang_id, warna_id, bulan_request
		", false);

		return $query->result();
	} */

	function get_po_request_datang_data($request_barang_batch_id,$bulan_request,  $tgl_start, $tgl_end, $closed_date){
		$tgl= date('Y-m-d H:i:s');
		$query = $this->db->query("SELECT t1.*, sum(qty_datang) as qty_datang, group_concat(qty_datang ORDER BY t4.created_at ASC) as qty_datang_data, group_concat(t4.created_at ORDER BY t4.created_at ASC) as tgl_datang, group_concat(t4.po_pembelian_batch_id ORDER BY t4.created_at ASC) as po_pembelian_batch_id_datang, nama as nama_barang, warna_beli as nama_warna, min(t4.created_at) as first_datang, max(t4.created_at) as last_datang, t2.batch as no_batch, t2.tanggal as tanggal_batch
			FROM (
				SELECT *
				FROM nd_request_barang_qty
				WHERE request_barang_batch_id = $request_barang_batch_id
				AND DATE_ADD(bulan_request,interval 1 year) = '$bulan_request'
			)t1
			LEFT JOIN nd_request_barang_batch t2
			ON t1.request_barang_batch_id = t2.id
			LEFT JOIN (
				SELECT barang_id, warna_id, qty as qty_datang, created_at, po_pembelian_batch_id
				FROM nd_pembelian_detail tA
				LEFT JOIN nd_pembelian tB
				ON tA.pembelian_id = tB.id
				WHERE created_at >= '$tgl_start'
				AND created_at <= '$tgl_end'
				AND created_at <= '$closed_date'
			)t4
			ON t1.barang_id = t4.barang_id
			AND t1.warna_id = t4.warna_id
			LEFT JOIN nd_barang
			ON t1.barang_id = nd_barang.id
			LEFT JOIN nd_warna
			ON t1.warna_id = nd_warna.id
			GROUP BY barang_id, warna_id
		", false);

		return $query->result();
	}

	function get_po_request_report_detail_2($request_barang_id){
		$this->db->simple_query('SET SESSION group_concat_max_len=15000');
		$tgl= date('Y-m-d H:i:s');
		$query = $this->db->query("SELECT supplier_id, request_barang_id, barang_id, 
			DATE_ADD(group_concat(bulan_request_awal SEPARATOR ''),interval 1 year) as bulan_request_awal, tanggal_request_awal,
			sum(qty_request) as qty_request,
			sum(qty_datang) as qty_datang, 
			group_concat(warna_id SEPARATOR '') as warna_id, 
			group_concat(qty_data_request  SEPARATOR '') as qty_data_request, 
			group_concat(qty_data_datang  SEPARATOR '') as qty_data_datang, 
			group_concat(qty_data  SEPARATOR '') as qty_data_request_perbulan,
			group_concat(bulan_request  SEPARATOR '') as bulan_request,
			group_concat(qty_datang_detail  SEPARATOR '') as qty_datang_detail,
			group_concat(bulan_datang  SEPARATOR '') as bulan_datang,
			group_concat(warna_id_no_request SEPARATOR '') as warna_id_no_request, 
			group_concat(qty_data_datang_no_request SEPARATOR '') as qty_data_datang_no_request, 
			group_concat(nama_warna_no_request SEPARATOR '') as nama_warna_no_request,
			sum(qty_datang_no_request) as qty_datang_no_request, 
			nama_barang, group_concat(nama_warna SEPARATOR '')  as nama_warna
			FROM (
				(
					SELECT supplier_id, barang_id, nama_barang, t1.request_barang_id,
						qty_request, qty_datang, warna_id, nama_warna,
						qty_data_request, qty_data_datang, 
						qty_data, bulan_request,
						qty_datang_detail, bulan_datang, bulan_request_awal, tanggal_request_awal,
						'' as warna_id_no_request, 0 as qty_datang_no_request,  
						'' as qty_data_datang_no_request,
						'' as nama_warna_no_request
					FROM (
						SELECT supplier_id, barang_id, nama as nama_barang, request_barang_id,
							sum(qty) as qty_request, 
							sum(ifnull(qty_datang,0)) as qty_datang, 
							group_concat(warna_id) as warna_id, 
							group_concat(warna_jual) as nama_warna, 
							group_concat(qty) as qty_data_request, 
							group_concat(qty_datang) as qty_data_datang,
							group_concat(bulan_request) as bulan_request, 
							group_concat(ifnull(qty_datang_detail,0)) as qty_datang_detail,
							group_concat(ifnull(bulan_datang,'x')) as bulan_datang, 
							group_concat(qty_data) as qty_data
						FROM (
							SELECT t1.barang_id, t1.warna_id, t1.qty, 
							sum(ifnull(t2.qty,0)) as qty_datang, 
							group_concat(ifnull(t2.qty,0) order by tanggal_beli ASC SEPARATOR '||' ) as qty_datang_detail, 
							group_concat(ifnull(DATE_FORMAT(tanggal_beli, '%Y-%m-%d'),'x') order by tanggal_beli ASC SEPARATOR '||') as bulan_datang, 
							request_barang_id, 
							closed_date, t1.tanggal, max(tanggal_beli) as last_datang, supplier_id, qty_data, bulan_request
							FROM (
								SELECT t11.*, t12.tanggal
								FROM (
									SELECT barang_id, min(tB.tanggal) as tgl, warna_id, sum(qty) as qty, group_concat(qty ORDER BY bulan_request ASC SEPARATOR '||') as qty_data, request_barang_batch_id, 
									closed_date, tB.request_barang_id, supplier_id, group_concat(DATE_ADD(bulan_request,interval 1 year) ORDER BY bulan_request ASC SEPARATOR '||') as bulan_request, concat(DATE_FORMAT(max_bulan_request, '%Y-%m-31')) as max_tanggal
									FROM (
										SELECT *
										FROM nd_request_barang_qty
										WHERE id in (
											SELECT max(tA.id)
											FROM nd_request_barang_qty tA
											LEFT JOIN nd_request_barang_batch tB
											ON tA.request_barang_batch_id = tB.id
											GROUP BY barang_id, warna_id, request_barang_id, bulan_request
											)
									)tA
									LEFT JOIN nd_request_barang_batch tB
									ON tA.request_barang_batch_id = tB.id
									LEFT JOIN nd_request_barang tC
									ON tB.request_barang_id = tC.id
									LEFT JOIN (
										SELECT request_barang_id, DATE_ADD(min(bulan_request),interval 1 year) as max_bulan_request
										FROM nd_request_barang_qty tX
										LEFT JOIN nd_request_barang_batch tY
										ON tX.request_barang_batch_id = tY.id
										GROUP BY request_barang_id
									) tD
									ON tD.request_barang_id = tB.request_barang_id
									GROUP BY barang_id, warna_id, request_barang_id
								)t11
								LEFT JOIN (
									SELECT if(tanggal > min(DATE_ADD(bulan_request,interval 1 year)), tanggal, bulan_request) as tanggal, 
									request_barang_id, barang_id, warna_id 
									FROM (
										SELECT *
										FROM nd_request_barang_qty
										WHERE id in (
											SELECT min(tA.id)
											FROM nd_request_barang_qty tA
											LEFT JOIN nd_request_barang_batch tB
											ON tA.request_barang_batch_id = tB.id
											GROUP BY barang_id, warna_id, request_barang_id, bulan_request
											)
									) t1
									LEFT JOIN nd_request_barang_batch t2
									ON t1.request_barang_batch_id = t2.id
									GROUP BY barang_id, warna_id, request_barang_id
								) t12
								ON t11.barang_id = t12.barang_id
								AND t11.warna_id = t12.warna_id
								AND t11.request_barang_id = t12.request_barang_id

							)t1
							LEFT JOIN (
								SELECT barang_id, warna_id, qty, jumlah_roll, created_at, tanggal as tanggal_beli
								FROM nd_pembelian_detail tA
								LEFT JOIN nd_pembelian tB
								ON tA.pembelian_id = tB.id
							)t2
							ON t1.barang_id = t2.barang_id
							AND t1.warna_id = t2.warna_id
							AND max_tanggal >= t2.tanggal_beli
							AND t1.tanggal <= t2.tanggal_beli
							AND t1.bulan_request <= t2.tanggal_beli
							GROUP BY t1.barang_id, t1.warna_id, request_barang_id
						)result
						LEFT JOIN nd_barang
						ON result.barang_id = nd_barang.id 
						LEFT JOIN nd_warna 
						ON result.warna_id = nd_warna.id
						GROUP BY barang_id, request_barang_id, supplier_id
						ORDER BY nd_barang.nama asc
					)t1
					LEFT JOIN (
						SELECT request_barang_id, t12.request_barang_batch_id, min(bulan_request) as bulan_request_awal, min(t12.tanggal) as tanggal_request_awal
						FROM (
							SELECT min(bulan_request) as bulan_request, request_barang_batch_id
							FROM nd_request_barang_qty
							GROUP BY request_barang_batch_id
						)t11
						LEFT JOIN (
							SELECT request_barang_id, tanggal, id as request_barang_batch_id
							FROM nd_request_barang_batch
							WHERE id IN (
								SELECT id
								FROM nd_request_barang_batch
								WHERE batch = 1
							)
						)t12
						ON t11.request_barang_batch_id = t12.request_barang_batch_id
						WHERE t12.request_barang_batch_id is not null
						GROUP BY request_barang_id
					)t2
					ON t1.request_barang_id = t2.request_barang_id
					
				)UNION(
					SELECT supplier_id, barang_id, nama as nama_barang, request_barang_id,
						0, 0 ,'', '',
						'', '','','',
						'','','', '',
						group_concat(warna_id) as warna_id, sum(qty_datang), group_concat(qty_datang), group_concat(warna_jual)
					FROM (
						SELECT t2.barang_id, t2.warna_id, sum(ifnull(t2.qty,0)) as qty_datang, min(tanggal_beli) as first_datang, max(tanggal_beli) as last_datang, t3.closed_date, t3.request_barang_id, t2.supplier_id
							FROM (
								SELECT barang_id, warna_id, sum(qty) as qty, sum(jumlah_roll) as jumlah_roll,  DATE_FORMAT(created_at, '%Y-%m-%d') , supplier_id, tanggal as tanggal_beli
								FROM nd_pembelian_detail tA
								LEFT JOIN nd_pembelian tB
								ON tA.pembelian_id = tB.id
								GROUP BY pembelian_id
							)t2
							LEFT JOIN (
								SELECT t1.*, tanggal
								FROM (
									SELECT barang_id, if(min(tB.tanggal) > DATE_ADD(tB.bulan_request,interval 1 year), tB.tanggal, DATE_ADD(tB.bulan_request,interval 1 year)) as tgl, 
									warna_id, sum(qty) as qty, request_barang_batch_id, request_barang_id, tB.bulan_request, 
									DATE_FORMAT(DATE_ADD(tB.bulan_request,interval 1 year), '%Y-%m-31') as closed_date
									FROM (
										SELECT *
										FROM nd_request_barang_qty
										WHERE id in (
											SELECT max(tA.id)
											FROM nd_request_barang_qty tA
											LEFT JOIN nd_request_barang_batch tB
											ON tA.request_barang_batch_id = tB.id
											GROUP BY barang_id, warna_id, request_barang_id, bulan_request
											)
									)tA
									LEFT JOIN (
										SELECT t1.*, bulan_request
										FROM nd_request_barang_batch t1
										LEFT JOIN (
											SELECT min(bulan_request) as bulan_request, request_barang_batch_id
											FROM nd_request_barang_qty
											GROUP BY request_barang_batch_id
										) t2
										ON t2.request_barang_batch_id = t1.id
									) tB
									ON tA.request_barang_batch_id = tB.id
									AND tA.bulan_request=tB.bulan_request
									LEFT JOIN nd_request_barang tC
									ON tB.request_barang_id = tC.id
									WHERE request_barang_id = $request_barang_id
									GROUP BY barang_id, warna_id, request_barang_id
								)t1
								LEFT JOIN (
									SELECT if(tanggal > min(DATE_ADD(bulan_request,interval 1 year)), tanggal, bulan_request) as tanggal, 
									request_barang_id, barang_id, warna_id 
									FROM (
										SELECT *
										FROM nd_request_barang_qty
										WHERE id in (
											SELECT min(tA.id)
											FROM nd_request_barang_qty tA
											LEFT JOIN nd_request_barang_batch tB
											ON tA.request_barang_batch_id = tB.id
											GROUP BY barang_id, warna_id, request_barang_id, bulan_request
											)
									) t1
									LEFT JOIN nd_request_barang_batch t2
									ON t1.request_barang_batch_id = t2.id
									GROUP BY barang_id, warna_id, request_barang_id
								)t2
								ON t1.barang_id = t2.barang_id
								AND t1.warna_id = t2.warna_id
								AND t1.request_barang_id = t2.request_barang_id
							)t1
							ON t1.barang_id = t2.barang_id
							AND t1.warna_id = t2.warna_id
							AND t1.closed_date >= t2.tanggal_beli
							AND t1.tanggal <= t2.tanggal_beli
							LEFT JOIN (
								SELECT bulan_request, bulan_closed_date as closed_date, request_barang_id, supplier_id,
									if(tB.tanggal > bulan_request, tB.tanggal, bulan_request) as tanggal
								FROM (
									SELECT min(DATE_ADD(bulan_request,interval 1 year))  as bulan_request, DATE_FORMAT(min(DATE_ADD(bulan_request,interval 1 year)), '%Y-%m-31') as bulan_closed_date,request_barang_batch_id
									FROM nd_request_barang_qty
									GROUP BY request_barang_batch_id
								) tA
								LEFT JOIN nd_request_barang_batch tB
								ON tA.request_barang_batch_id = tB.id
								LEFT JOIN nd_request_barang tC
								ON tB.request_barang_id = tC.id
								GROUP BY  request_barang_id
							)t3
							ON t3.closed_date >= t2.tanggal_beli
							AND t3.tanggal <= t2.tanggal_beli
							AND t2.supplier_id = t3.supplier_id
							WHERE t3.request_barang_id is not null
							AND t1.request_barang_id is null
							GROUP BY t2.barang_id, t2.warna_id, t3.request_barang_id	
					)result
					LEFT JOIN nd_barang
					ON result.barang_id = nd_barang.id 
					LEFT JOIN nd_warna 
					ON result.warna_id = nd_warna.id
					GROUP BY barang_id, request_barang_id, supplier_id
					ORDER BY nd_barang.nama asc

				)
			)result
			WHERE request_barang_id = $request_barang_id
			GROUP BY barang_id, request_barang_id, supplier_id
			ORDER BY nama_barang, nama_warna asc
		", false);

		return $query->result();
	}

	function get_po_request_report_detail_by_input_2($request_barang_id){
		$this->db->simple_query('SET SESSION group_concat_max_len=15000');
		$tgl= date('Y-m-d H:i:s');
		$query = $this->db->query("SELECT supplier_id, request_barang_id, barang_id, 
		DATE_ADD(group_concat(bulan_request_awal SEPARATOR ''),interval 1 year) as bulan_request_awal, tanggal_request_awal,
		sum(qty_request) as qty_request, 
		sum(qty_datang) as qty_datang, 
		group_concat(warna_id SEPARATOR '') as warna_id, 
		group_concat(qty_data_request  SEPARATOR '') as qty_data_request, 
		group_concat(qty_data_datang  SEPARATOR '') as qty_data_datang, 
		group_concat(qty_data  SEPARATOR '') as qty_data_request_perbulan,
		group_concat(bulan_request  SEPARATOR '') as bulan_request,
		group_concat(qty_datang_detail  SEPARATOR '') as qty_datang_detail,
		group_concat(bulan_datang  SEPARATOR '') as bulan_datang,
		group_concat(warna_id_no_request SEPARATOR '') as warna_id_no_request, 
		group_concat(qty_data_datang_no_request SEPARATOR '') as qty_data_datang_no_request, 
		group_concat(nama_warna_no_request SEPARATOR '') as nama_warna_no_request,
		sum(qty_datang_no_request) as qty_datang_no_request, 
		nama_barang, group_concat(nama_warna SEPARATOR '')  as nama_warna
		FROM (
			(
				SELECT supplier_id, barang_id, nama_barang, t1.request_barang_id,
					qty_request, qty_datang, warna_id, nama_warna,
					qty_data_request, qty_data_datang, 
					qty_data, bulan_request,
					qty_datang_detail, bulan_datang, bulan_request_awal, tanggal_request_awal,
					'' as warna_id_no_request, 0 as qty_datang_no_request,  
					'' as qty_data_datang_no_request,
					'' as nama_warna_no_request
				FROM (
					SELECT supplier_id, barang_id, nama as nama_barang, request_barang_id,
						sum(qty) as qty_request, 
						sum(ifnull(qty_datang,0)) as qty_datang, 
						group_concat(warna_id) as warna_id, 
						group_concat(warna_jual) as nama_warna, 
						group_concat(qty) as qty_data_request, 
						group_concat(qty_datang) as qty_data_datang,
						group_concat(bulan_request) as bulan_request, 
						group_concat(ifnull(qty_datang_detail,0)) as qty_datang_detail,
						group_concat(ifnull(bulan_datang,'x')) as bulan_datang, 
						group_concat(qty_data) as qty_data
					FROM (
						SELECT t1.barang_id, t1.warna_id, t1.qty, 
						sum(ifnull(t2.qty,0)) as qty_datang, 
						group_concat(ifnull(t2.qty,0) order by tanggal_beli ASC SEPARATOR '||' ) as qty_datang_detail, 
						group_concat(ifnull(DATE_FORMAT(tanggal_beli, '%Y-%m-%d'),'x') order by tanggal_beli ASC SEPARATOR '||') as bulan_datang, 
						request_barang_id, 
						closed_date, t1.tanggal, max(tanggal_beli) as last_datang, supplier_id, qty_data, bulan_request
						FROM (
							SELECT barang_id, min(tB.tanggal) as tanggal, warna_id, sum(qty) as qty, 
							group_concat(qty ORDER BY bulan_request ASC SEPARATOR '||') as qty_data, request_barang_batch_id, 
							closed_date, tB.request_barang_id, supplier_id, 
							group_concat(DATE_ADD(bulan_request,interval 1 year) ORDER BY bulan_request ASC SEPARATOR '||') as bulan_request, 
							concat(DATE_FORMAT(max_bulan_request, '%Y-%m-31')) as max_tanggal
							FROM (
								SELECT *
								FROM nd_request_barang_qty
								WHERE id in (
									SELECT max(tA.id)
									FROM nd_request_barang_qty tA
									LEFT JOIN nd_request_barang_batch tB
									ON tA.request_barang_batch_id = tB.id
									GROUP BY barang_id, warna_id, request_barang_id, bulan_request
									)
							)tA
							LEFT JOIN nd_request_barang_batch tB
							ON tA.request_barang_batch_id = tB.id
							LEFT JOIN nd_request_barang tC
							ON tB.request_barang_id = tC.id
							LEFT JOIN (
								SELECT request_barang_id, DATE_ADD(min(bulan_request),interval 1 year) as max_bulan_request
								FROM nd_request_barang_qty tX
								LEFT JOIN nd_request_barang_batch tY
								ON tX.request_barang_batch_id = tY.id
								GROUP BY request_barang_id
							) tD
							ON tD.request_barang_id = tB.request_barang_id
							GROUP BY barang_id, warna_id, request_barang_id
						)t1
						LEFT JOIN (
							SELECT barang_id, warna_id, qty, jumlah_roll, created_at, DATE_FORMAT(created_at, '%Y-%m-%d') as tanggal_beli
							FROM nd_pembelian_detail tA
							LEFT JOIN nd_pembelian tB
							ON tA.pembelian_id = tB.id
						)t2
						ON t1.barang_id = t2.barang_id
						AND t1.warna_id = t2.warna_id
						AND max_tanggal >= t2.tanggal_beli
						AND t1.tanggal <= t2.tanggal_beli
                        AND t1.bulan_request <= t2.tanggal_beli
						GROUP BY t1.barang_id, t1.warna_id, request_barang_id
					)result
					LEFT JOIN nd_barang
					ON result.barang_id = nd_barang.id 
					LEFT JOIN nd_warna 
					ON result.warna_id = nd_warna.id
					GROUP BY barang_id, request_barang_id, supplier_id
					ORDER BY nd_barang.nama asc
				)t1
				LEFT JOIN (
					SELECT request_barang_id, t12.request_barang_batch_id, min(bulan_request) as bulan_request_awal, min(t12.tanggal) as tanggal_request_awal
					FROM (
						SELECT min(bulan_request) as bulan_request, request_barang_batch_id
						FROM nd_request_barang_qty
						GROUP BY request_barang_batch_id
					)t11
					LEFT JOIN (
						SELECT request_barang_id, tanggal, id as request_barang_batch_id
						FROM nd_request_barang_batch
						WHERE id IN (
							SELECT id
							FROM nd_request_barang_batch
							WHERE batch = 1
						)
					)t12
					ON t11.request_barang_batch_id = t12.request_barang_batch_id
					WHERE t12.request_barang_batch_id is not null
					GROUP BY request_barang_id
				)t2
				ON t1.request_barang_id = t2.request_barang_id
				
			)UNION(
				SELECT supplier_id, barang_id, nama as nama_barang, request_barang_id,
					0, 0 ,'', '',
					'', '','','',
					'','','', '',
					group_concat(warna_id) as warna_id, sum(qty_datang), group_concat(qty_datang), group_concat(warna_jual)
				FROM (
					SELECT t2.barang_id, t2.warna_id, sum(ifnull(t2.qty,0)) as qty_datang, min(tanggal_beli) as first_datang, max(tanggal_beli) as last_datang, t3.closed_date, t3.request_barang_id, t2.supplier_id
						FROM (
                            SELECT barang_id, warna_id, sum(qty) as qty, sum(jumlah_roll) as jumlah_roll,  DATE_FORMAT(created_at, '%Y-%m-%d') as tanggal_beli, supplier_id, tanggal
                            FROM nd_pembelian_detail tA
                            LEFT JOIN nd_pembelian tB
                            ON tA.pembelian_id = tB.id
                            GROUP BY pembelian_id
                        )t2
                        LEFT JOIN (
							SELECT barang_id, if(min(tB.tanggal) > DATE_ADD(tB.bulan_request,interval 1 year), tB.tanggal, DATE_ADD(tB.bulan_request,interval 1 year)) as tanggal, 
                            warna_id, sum(qty) as qty, request_barang_batch_id, request_barang_id, tB.bulan_request, 
                            DATE_FORMAT(DATE_ADD(tB.bulan_request,interval 1 year), '%Y-%m-31') as closed_date
                            FROM (
                                SELECT *
                                FROM nd_request_barang_qty
                                WHERE id in (
                                    SELECT max(tA.id)
                                    FROM nd_request_barang_qty tA
                                    LEFT JOIN nd_request_barang_batch tB
                                    ON tA.request_barang_batch_id = tB.id
                                    GROUP BY barang_id, warna_id, request_barang_id, bulan_request
                                    )
                            )tA
                            LEFT JOIN (
                                SELECT t1.*, bulan_request
                                FROM nd_request_barang_batch t1
                                LEFT JOIN (
                                    SELECT min(bulan_request) as bulan_request, request_barang_batch_id
                                    FROM nd_request_barang_qty
                                    GROUP BY request_barang_batch_id
                                ) t2
                                ON t2.request_barang_batch_id = t1.id
                            ) tB
                            ON tA.request_barang_batch_id = tB.id
                            AND tA.bulan_request=tB.bulan_request
                            LEFT JOIN nd_request_barang tC
                            ON tB.request_barang_id = tC.id
                            GROUP BY barang_id, warna_id, request_barang_id
                        )t1
                        ON t1.barang_id = t2.barang_id
                        AND t1.warna_id = t2.warna_id
                        AND t1.closed_date >= t2.tanggal_beli
                        AND t1.tanggal <= t2.tanggal_beli
						LEFT JOIN (
							SELECT bulan_request, bulan_closed_date as closed_date, request_barang_id, supplier_id,
                                if(tB.tanggal > bulan_request, tB.tanggal, bulan_request) as tanggal
							FROM (
                                SELECT min(DATE_ADD(bulan_request,interval 1 year))  as bulan_request, DATE_FORMAT(min(DATE_ADD(bulan_request,interval 1 year)), '%Y-%m-31') as bulan_closed_date,request_barang_batch_id
                                FROM nd_request_barang_qty
                                GROUP BY request_barang_batch_id
                            ) tA
							LEFT JOIN nd_request_barang_batch tB
							ON tA.request_barang_batch_id = tB.id
							LEFT JOIN nd_request_barang tC
							ON tB.request_barang_id = tC.id
							GROUP BY  request_barang_id
						)t3
						ON t3.closed_date >= t2.tanggal_beli
						AND t3.tanggal <= t2.tanggal_beli
						AND t2.supplier_id = t3.supplier_id
						WHERE t3.request_barang_id is not null
                        AND t1.request_barang_id is null
						GROUP BY t2.barang_id, t2.warna_id, t3.request_barang_id	
				)result
				LEFT JOIN nd_barang
				ON result.barang_id = nd_barang.id 
				LEFT JOIN nd_warna 
				ON result.warna_id = nd_warna.id
				GROUP BY barang_id, request_barang_id, supplier_id
				ORDER BY nd_barang.nama asc

			)
		)result
		WHERE request_barang_id = $request_barang_id
		GROUP BY barang_id, request_barang_id, supplier_id
		ORDER BY nama_barang, nama_warna asc
		", false);

		return $query->result();
	}

	function get_po_request_report_detail_2_ingnore_date($request_barang_id){
		$this->db->simple_query('SET SESSION group_concat_max_len=15000');
		$tgl= date('Y-m-d H:i:s');
		$query = $this->db->query("SELECT supplier_id, request_barang_id, barang_id, 
			DATE_ADD(group_concat(bulan_request_awal SEPARATOR ''),interval 1 year) as bulan_request_awal, tanggal_request_awal,
			sum(qty_request) as qty_request,
			sum(qty_datang) as qty_datang, 
			group_concat(warna_id SEPARATOR '') as warna_id, 
			group_concat(qty_data_request  SEPARATOR '') as qty_data_request, 
			group_concat(qty_data_datang  SEPARATOR '') as qty_data_datang, 
			group_concat(qty_data  SEPARATOR '') as qty_data_request_perbulan,
			group_concat(bulan_request  SEPARATOR '') as bulan_request,
			group_concat(qty_datang_detail  SEPARATOR '') as qty_datang_detail,
			group_concat(bulan_datang  SEPARATOR '') as bulan_datang,
			group_concat(warna_id_no_request SEPARATOR '') as warna_id_no_request, 
			group_concat(qty_data_datang_no_request SEPARATOR '') as qty_data_datang_no_request, 
			group_concat(nama_warna_no_request SEPARATOR '') as nama_warna_no_request,
			sum(qty_datang_no_request) as qty_datang_no_request, 
			nama_barang, group_concat(nama_warna SEPARATOR '')  as nama_warna
			FROM (
				(
					SELECT supplier_id, barang_id, nama_barang, t1.request_barang_id,
						qty_request, qty_datang, warna_id, nama_warna,
						qty_data_request, qty_data_datang, 
						qty_data, bulan_request,
						qty_datang_detail, bulan_datang, bulan_request_awal, tanggal_request_awal,
						'' as warna_id_no_request, 0 as qty_datang_no_request,  
						'' as qty_data_datang_no_request,
						'' as nama_warna_no_request
					FROM (
						SELECT supplier_id, barang_id, nama as nama_barang, request_barang_id,
							sum(qty) as qty_request, 
							sum(ifnull(qty_datang,0)) as qty_datang, 
							group_concat(warna_id) as warna_id, 
							group_concat(warna_jual) as nama_warna, 
							group_concat(qty) as qty_data_request, 
							group_concat(qty_datang) as qty_data_datang,
							group_concat(bulan_request) as bulan_request, 
							group_concat(ifnull(qty_datang_detail,0)) as qty_datang_detail,
							group_concat(ifnull(bulan_datang,'x')) as bulan_datang, 
							group_concat(qty_data) as qty_data
						FROM (
							SELECT t1.barang_id, t1.warna_id, t1.qty, 
							sum(ifnull(t2.qty,0)) as qty_datang, 
							group_concat(ifnull(t2.qty,0) order by tanggal_beli ASC SEPARATOR '||' ) as qty_datang_detail, 
							group_concat(ifnull(DATE_FORMAT(tanggal_beli, '%Y-%m-%d'),'x') order by tanggal_beli ASC SEPARATOR '||') as bulan_datang, 
							request_barang_id, 
							closed_date, t1.tanggal, max(tanggal_beli) as last_datang, supplier_id, qty_data, bulan_request
							FROM (
								SELECT t11.*, t12.tanggal
								FROM (
									SELECT barang_id, min(tB.tanggal) as tgl, warna_id, sum(qty) as qty, group_concat(qty ORDER BY bulan_request ASC SEPARATOR '||') as qty_data, request_barang_batch_id, 
									closed_date, tB.request_barang_id, supplier_id, group_concat(DATE_ADD(bulan_request,interval 1 year) ORDER BY bulan_request ASC SEPARATOR '||') as bulan_request, concat(DATE_FORMAT(max_bulan_request, '%Y-%m-31')) as max_tanggal
									FROM (
										SELECT *
										FROM nd_request_barang_qty
										WHERE id in (
											SELECT max(tA.id)
											FROM nd_request_barang_qty tA
											LEFT JOIN nd_request_barang_batch tB
											ON tA.request_barang_batch_id = tB.id
											GROUP BY barang_id, warna_id, request_barang_id, bulan_request
											)
									)tA
									LEFT JOIN nd_request_barang_batch tB
									ON tA.request_barang_batch_id = tB.id
									LEFT JOIN nd_request_barang tC
									ON tB.request_barang_id = tC.id
									LEFT JOIN (
										SELECT request_barang_id, DATE_ADD(min(bulan_request),interval 1 year) as max_bulan_request
										FROM nd_request_barang_qty tX
										LEFT JOIN nd_request_barang_batch tY
										ON tX.request_barang_batch_id = tY.id
										GROUP BY request_barang_id
									) tD
									ON tD.request_barang_id = tB.request_barang_id
									GROUP BY barang_id, warna_id, request_barang_id
								)t11
								LEFT JOIN (
									SELECT if(tanggal > min(DATE_ADD(bulan_request,interval 1 year)), tanggal, bulan_request) as tanggal, 
									request_barang_id, barang_id, warna_id 
									FROM (
										SELECT *
										FROM nd_request_barang_qty
										WHERE id in (
											SELECT min(tA.id)
											FROM nd_request_barang_qty tA
											LEFT JOIN nd_request_barang_batch tB
											ON tA.request_barang_batch_id = tB.id
											GROUP BY barang_id, warna_id, request_barang_id, bulan_request
											)
									) t1
									LEFT JOIN nd_request_barang_batch t2
									ON t1.request_barang_batch_id = t2.id
									GROUP BY barang_id, warna_id, request_barang_id
								) t12
								ON t11.barang_id = t12.barang_id
								AND t11.warna_id = t12.warna_id
								AND t11.request_barang_id = t12.request_barang_id

							)t1
							LEFT JOIN (
								SELECT barang_id, warna_id, qty, jumlah_roll, created_at, tanggal as tanggal_beli
								FROM nd_pembelian_detail tA
								LEFT JOIN nd_pembelian tB
								ON tA.pembelian_id = tB.id
							)t2
							ON t1.barang_id = t2.barang_id
							AND t1.warna_id = t2.warna_id
							AND max_tanggal >= t2.tanggal_beli
							AND t1.tanggal <= t2.tanggal_beli
							AND t1.bulan_request <= t2.tanggal_beli
							GROUP BY t1.barang_id, t1.warna_id, request_barang_id
						)result
						LEFT JOIN nd_barang
						ON result.barang_id = nd_barang.id 
						LEFT JOIN nd_warna 
						ON result.warna_id = nd_warna.id
						GROUP BY barang_id, request_barang_id, supplier_id
						ORDER BY nd_barang.nama asc
					)t1
					LEFT JOIN (
						SELECT request_barang_id, t12.request_barang_batch_id, min(bulan_request) as bulan_request_awal, min(t12.tanggal) as tanggal_request_awal
						FROM (
							SELECT min(bulan_request) as bulan_request, request_barang_batch_id
							FROM nd_request_barang_qty
							GROUP BY request_barang_batch_id
						)t11
						LEFT JOIN (
							SELECT request_barang_id, tanggal, id as request_barang_batch_id
							FROM nd_request_barang_batch
							WHERE id IN (
								SELECT id
								FROM nd_request_barang_batch
								WHERE batch = 1
							)
						)t12
						ON t11.request_barang_batch_id = t12.request_barang_batch_id
						WHERE t12.request_barang_batch_id is not null
						GROUP BY request_barang_id
					)t2
					ON t1.request_barang_id = t2.request_barang_id
					
				)UNION(
					SELECT supplier_id, barang_id, nama as nama_barang, request_barang_id,
						0, 0 ,'', '',
						'', '','','',
						'','','', '',
						group_concat(warna_id) as warna_id, sum(qty_datang), group_concat(qty_datang), group_concat(warna_jual)
					FROM (
						SELECT t2.barang_id, t2.warna_id, sum(ifnull(t2.qty,0)) as qty_datang, min(tanggal_beli) as first_datang, max(tanggal_beli) as last_datang, t3.closed_date, t3.request_barang_id, t2.supplier_id
							FROM (
								SELECT barang_id, warna_id, sum(qty) as qty, sum(jumlah_roll) as jumlah_roll,  DATE_FORMAT(created_at, '%Y-%m-%d') , supplier_id, tanggal as tanggal_beli
								FROM nd_pembelian_detail tA
								LEFT JOIN nd_pembelian tB
								ON tA.pembelian_id = tB.id
								GROUP BY pembelian_id
							)t2
							LEFT JOIN (
								SELECT t1.*, tanggal
								FROM (
									SELECT barang_id, if(min(tB.tanggal) > DATE_ADD(tB.bulan_request,interval 1 year), tB.tanggal, DATE_ADD(tB.bulan_request,interval 1 year)) as tgl, 
									warna_id, sum(qty) as qty, request_barang_batch_id, request_barang_id, tB.bulan_request, 
									DATE_FORMAT(DATE_ADD(tB.bulan_request,interval 1 year), '%Y-%m-31') as closed_date
									FROM (
										SELECT *
										FROM nd_request_barang_qty
										WHERE id in (
											SELECT max(tA.id)
											FROM nd_request_barang_qty tA
											LEFT JOIN nd_request_barang_batch tB
											ON tA.request_barang_batch_id = tB.id
											GROUP BY barang_id, warna_id, request_barang_id, bulan_request
											)
									)tA
									LEFT JOIN (
										SELECT t1.*, bulan_request
										FROM nd_request_barang_batch t1
										LEFT JOIN (
											SELECT min(bulan_request) as bulan_request, request_barang_batch_id
											FROM nd_request_barang_qty
											GROUP BY request_barang_batch_id
										) t2
										ON t2.request_barang_batch_id = t1.id
									) tB
									ON tA.request_barang_batch_id = tB.id
									AND tA.bulan_request=tB.bulan_request
									LEFT JOIN nd_request_barang tC
									ON tB.request_barang_id = tC.id
									WHERE request_barang_id = $request_barang_id
									GROUP BY barang_id, warna_id, request_barang_id
								)t1
								LEFT JOIN (
									SELECT if(tanggal > min(DATE_ADD(bulan_request,interval 1 year)), tanggal, bulan_request) as tanggal, 
									request_barang_id, barang_id, warna_id 
									FROM (
										SELECT *
										FROM nd_request_barang_qty
										WHERE id in (
											SELECT min(tA.id)
											FROM nd_request_barang_qty tA
											LEFT JOIN nd_request_barang_batch tB
											ON tA.request_barang_batch_id = tB.id
											GROUP BY barang_id, warna_id, request_barang_id, bulan_request
											)
									) t1
									LEFT JOIN nd_request_barang_batch t2
									ON t1.request_barang_batch_id = t2.id
									GROUP BY barang_id, warna_id, request_barang_id
								)t2
								ON t1.barang_id = t2.barang_id
								AND t1.warna_id = t2.warna_id
								AND t1.request_barang_id = t2.request_barang_id
							)t1
							ON t1.barang_id = t2.barang_id
							AND t1.warna_id = t2.warna_id
							AND t1.closed_date >= t2.tanggal_beli
							AND t1.tanggal <= t2.tanggal_beli
							LEFT JOIN (
								SELECT bulan_request, bulan_closed_date as closed_date, request_barang_id, supplier_id,
									if(tB.tanggal > bulan_request, tB.tanggal, bulan_request) as tanggal
								FROM (
									SELECT min(DATE_ADD(bulan_request,interval 1 year))  as bulan_request, DATE_FORMAT(min(DATE_ADD(bulan_request,interval 1 year)), '%Y-%m-31') as bulan_closed_date,request_barang_batch_id
									FROM nd_request_barang_qty
									GROUP BY request_barang_batch_id
								) tA
								LEFT JOIN nd_request_barang_batch tB
								ON tA.request_barang_batch_id = tB.id
								LEFT JOIN nd_request_barang tC
								ON tB.request_barang_id = tC.id
								GROUP BY  request_barang_id
							)t3
							ON t3.closed_date >= t2.tanggal_beli
							AND t3.tanggal <= t2.tanggal_beli
							AND t2.supplier_id = t3.supplier_id
							WHERE t3.request_barang_id is not null
							AND t1.request_barang_id is null
							GROUP BY t2.barang_id, t2.warna_id, t3.request_barang_id	
					)result
					LEFT JOIN nd_barang
					ON result.barang_id = nd_barang.id 
					LEFT JOIN nd_warna 
					ON result.warna_id = nd_warna.id
					GROUP BY barang_id, request_barang_id, supplier_id
					ORDER BY nd_barang.nama asc

				)
			)result
			WHERE request_barang_id = $request_barang_id
			GROUP BY barang_id, request_barang_id, supplier_id
			ORDER BY nama_barang, nama_warna asc
		", false);

		return $query->result();
	}

	function get_po_request_report_detail_by_input_2_ingnore_date($request_barang_id){
		$this->db->simple_query('SET SESSION group_concat_max_len=15000');
		$tgl= date('Y-m-d H:i:s');
		$query = $this->db->query("SELECT supplier_id, request_barang_id, barang_id, 
		DATE_ADD(group_concat(bulan_request_awal SEPARATOR ''),interval 1 year) as bulan_request_awal, tanggal_request_awal,
		sum(qty_request) as qty_request, 
		sum(qty_datang) as qty_datang, 
		group_concat(warna_id SEPARATOR '') as warna_id, 
		group_concat(qty_data_request  SEPARATOR '') as qty_data_request, 
		group_concat(qty_data_datang  SEPARATOR '') as qty_data_datang, 
		group_concat(qty_data  SEPARATOR '') as qty_data_request_perbulan,
		group_concat(bulan_request  SEPARATOR '') as bulan_request,
		group_concat(qty_datang_detail  SEPARATOR '') as qty_datang_detail,
		group_concat(bulan_datang  SEPARATOR '') as bulan_datang,
		group_concat(warna_id_no_request SEPARATOR '') as warna_id_no_request, 
		group_concat(qty_data_datang_no_request SEPARATOR '') as qty_data_datang_no_request, 
		group_concat(nama_warna_no_request SEPARATOR '') as nama_warna_no_request,
		sum(qty_datang_no_request) as qty_datang_no_request, 
		nama_barang, group_concat(nama_warna SEPARATOR '')  as nama_warna
		FROM (
			(
				SELECT supplier_id, barang_id, nama_barang, t1.request_barang_id,
					qty_request, qty_datang, warna_id, nama_warna,
					qty_data_request, qty_data_datang, 
					qty_data, bulan_request,
					qty_datang_detail, bulan_datang, bulan_request_awal, tanggal_request_awal,
					'' as warna_id_no_request, 0 as qty_datang_no_request,  
					'' as qty_data_datang_no_request,
					'' as nama_warna_no_request
				FROM (
					SELECT supplier_id, barang_id, nama as nama_barang, request_barang_id,
						sum(qty) as qty_request, 
						sum(ifnull(qty_datang,0)) as qty_datang, 
						group_concat(warna_id) as warna_id, 
						group_concat(warna_jual) as nama_warna, 
						group_concat(qty) as qty_data_request, 
						group_concat(qty_datang) as qty_data_datang,
						group_concat(bulan_request) as bulan_request, 
						group_concat(ifnull(qty_datang_detail,0)) as qty_datang_detail,
						group_concat(ifnull(bulan_datang,'x')) as bulan_datang, 
						group_concat(qty_data) as qty_data
					FROM (
						SELECT t1.barang_id, t1.warna_id, t1.qty, 
						sum(ifnull(t2.qty,0)) as qty_datang, 
						group_concat(ifnull(t2.qty,0) order by tanggal_beli ASC SEPARATOR '||' ) as qty_datang_detail, 
						group_concat(ifnull(DATE_FORMAT(tanggal_beli, '%Y-%m-%d'),'x') order by tanggal_beli ASC SEPARATOR '||') as bulan_datang, 
						request_barang_id, 
						closed_date, t1.tanggal, max(tanggal_beli) as last_datang, supplier_id, qty_data, bulan_request
						FROM (
							SELECT barang_id, min(tB.tanggal) as tanggal, warna_id, sum(qty) as qty, 
							group_concat(qty ORDER BY bulan_request ASC SEPARATOR '||') as qty_data, request_barang_batch_id, 
							closed_date, tB.request_barang_id, supplier_id, 
							group_concat(DATE_ADD(bulan_request,interval 1 year) ORDER BY bulan_request ASC SEPARATOR '||') as bulan_request, 
							concat(DATE_FORMAT(max_bulan_request, '%Y-%m-31')) as max_tanggal
							FROM (
								SELECT *
								FROM nd_request_barang_qty
								WHERE id in (
									SELECT max(tA.id)
									FROM nd_request_barang_qty tA
									LEFT JOIN nd_request_barang_batch tB
									ON tA.request_barang_batch_id = tB.id
									GROUP BY barang_id, warna_id, request_barang_id, bulan_request
									)
							)tA
							LEFT JOIN nd_request_barang_batch tB
							ON tA.request_barang_batch_id = tB.id
							LEFT JOIN nd_request_barang tC
							ON tB.request_barang_id = tC.id
							LEFT JOIN (
								SELECT request_barang_id, DATE_ADD(min(bulan_request),interval 1 year) as max_bulan_request
								FROM nd_request_barang_qty tX
								LEFT JOIN nd_request_barang_batch tY
								ON tX.request_barang_batch_id = tY.id
								GROUP BY request_barang_id
							) tD
							ON tD.request_barang_id = tB.request_barang_id
							GROUP BY barang_id, warna_id, request_barang_id
						)t1
						LEFT JOIN (
							SELECT barang_id, warna_id, qty, jumlah_roll, created_at, DATE_FORMAT(created_at, '%Y-%m-%d') as tanggal_beli
							FROM nd_pembelian_detail tA
							LEFT JOIN nd_pembelian tB
							ON tA.pembelian_id = tB.id
						)t2
						ON t1.barang_id = t2.barang_id
						AND t1.warna_id = t2.warna_id
						AND max_tanggal >= t2.tanggal_beli
						AND t1.tanggal <= t2.tanggal_beli
                        AND t1.bulan_request <= t2.tanggal_beli
						GROUP BY t1.barang_id, t1.warna_id, request_barang_id
					)result
					LEFT JOIN nd_barang
					ON result.barang_id = nd_barang.id 
					LEFT JOIN nd_warna 
					ON result.warna_id = nd_warna.id
					GROUP BY barang_id, request_barang_id, supplier_id
					ORDER BY nd_barang.nama asc
				)t1
				LEFT JOIN (
					SELECT request_barang_id, t12.request_barang_batch_id, min(bulan_request) as bulan_request_awal, min(t12.tanggal) as tanggal_request_awal
					FROM (
						SELECT min(bulan_request) as bulan_request, request_barang_batch_id
						FROM nd_request_barang_qty
						GROUP BY request_barang_batch_id
					)t11
					LEFT JOIN (
						SELECT request_barang_id, tanggal, id as request_barang_batch_id
						FROM nd_request_barang_batch
						WHERE id IN (
							SELECT id
							FROM nd_request_barang_batch
							WHERE batch = 1
						)
					)t12
					ON t11.request_barang_batch_id = t12.request_barang_batch_id
					WHERE t12.request_barang_batch_id is not null
					GROUP BY request_barang_id
				)t2
				ON t1.request_barang_id = t2.request_barang_id
				
			)UNION(
				SELECT supplier_id, barang_id, nama as nama_barang, request_barang_id,
					0, 0 ,'', '',
					'', '','','',
					'','','', '',
					group_concat(warna_id) as warna_id, sum(qty_datang), group_concat(qty_datang), group_concat(warna_jual)
				FROM (
					SELECT t2.barang_id, t2.warna_id, sum(ifnull(t2.qty,0)) as qty_datang, min(tanggal_beli) as first_datang, max(tanggal_beli) as last_datang, t3.closed_date, t3.request_barang_id, t2.supplier_id
						FROM (
                            SELECT barang_id, warna_id, sum(qty) as qty, sum(jumlah_roll) as jumlah_roll,  DATE_FORMAT(created_at, '%Y-%m-%d') as tanggal_beli, supplier_id, tanggal
                            FROM nd_pembelian_detail tA
                            LEFT JOIN nd_pembelian tB
                            ON tA.pembelian_id = tB.id
                            GROUP BY pembelian_id
                        )t2
                        LEFT JOIN (
							SELECT barang_id, if(min(tB.tanggal) > DATE_ADD(tB.bulan_request,interval 1 year), tB.tanggal, DATE_ADD(tB.bulan_request,interval 1 year)) as tanggal, 
                            warna_id, sum(qty) as qty, request_barang_batch_id, request_barang_id, tB.bulan_request, 
                            DATE_FORMAT(DATE_ADD(tB.bulan_request,interval 1 year), '%Y-%m-31') as closed_date
                            FROM (
                                SELECT *
                                FROM nd_request_barang_qty
                                WHERE id in (
                                    SELECT max(tA.id)
                                    FROM nd_request_barang_qty tA
                                    LEFT JOIN nd_request_barang_batch tB
                                    ON tA.request_barang_batch_id = tB.id
                                    GROUP BY barang_id, warna_id, request_barang_id, bulan_request
                                    )
                            )tA
                            LEFT JOIN (
                                SELECT t1.*, bulan_request
                                FROM nd_request_barang_batch t1
                                LEFT JOIN (
                                    SELECT min(bulan_request) as bulan_request, request_barang_batch_id
                                    FROM nd_request_barang_qty
                                    GROUP BY request_barang_batch_id
                                ) t2
                                ON t2.request_barang_batch_id = t1.id
                            ) tB
                            ON tA.request_barang_batch_id = tB.id
                            AND tA.bulan_request=tB.bulan_request
                            LEFT JOIN nd_request_barang tC
                            ON tB.request_barang_id = tC.id
                            GROUP BY barang_id, warna_id, request_barang_id
                        )t1
                        ON t1.barang_id = t2.barang_id
                        AND t1.warna_id = t2.warna_id
                        AND t1.closed_date >= t2.tanggal_beli
                        AND t1.tanggal <= t2.tanggal_beli
						LEFT JOIN (
							SELECT bulan_request, bulan_closed_date as closed_date, request_barang_id, supplier_id,
                                if(tB.tanggal > bulan_request, tB.tanggal, bulan_request) as tanggal
							FROM (
                                SELECT min(DATE_ADD(bulan_request,interval 1 year))  as bulan_request, DATE_FORMAT(min(DATE_ADD(bulan_request,interval 1 year)), '%Y-%m-31') as bulan_closed_date,request_barang_batch_id
                                FROM nd_request_barang_qty
                                GROUP BY request_barang_batch_id
                            ) tA
							LEFT JOIN nd_request_barang_batch tB
							ON tA.request_barang_batch_id = tB.id
							LEFT JOIN nd_request_barang tC
							ON tB.request_barang_id = tC.id
							GROUP BY  request_barang_id
						)t3
						ON t3.closed_date >= t2.tanggal_beli
						AND t3.tanggal <= t2.tanggal_beli
						AND t2.supplier_id = t3.supplier_id
						WHERE t3.request_barang_id is not null
                        AND t1.request_barang_id is null
						GROUP BY t2.barang_id, t2.warna_id, t3.request_barang_id	
				)result
				LEFT JOIN nd_barang
				ON result.barang_id = nd_barang.id 
				LEFT JOIN nd_warna 
				ON result.warna_id = nd_warna.id
				GROUP BY barang_id, request_barang_id, supplier_id
				ORDER BY nd_barang.nama asc

			)
		)result
		WHERE request_barang_id = $request_barang_id
		GROUP BY barang_id, request_barang_id, supplier_id
		ORDER BY nama_barang, nama_warna asc
		", false);

		return $query->result();
	}

//================================po penjualan report======================================

	function po_penjualan_report($cond, $cond_customer){

		$query = $this->db->query("SELECT t1.*, concat(tipe_company,' ',nd_customer.nama) as nama_customer,
			GROUP_CONCAT(concat(nama_jual,' ',warna_jual) SEPARATOR '??') AS nama_barang,
			GROUP_CONCAT(nd_satuan.nama SEPARATOR '??') AS nama_satuan,
			GROUP_CONCAT(ifnull(nd_user.username,'-') SEPARATOR '??') AS closed_by,
			GROUP_CONCAT(ifnull(t2.closed_date,'-') SEPARATOR '??') AS closed_date,
			GROUP_CONCAT(qty_po SEPARATOR '??') as data_qty_po,
			GROUP_CONCAT(ifnull(qty_invoice,0) SEPARATOR '??') as data_qty_invoice,
			GROUP_CONCAT(harga SEPARATOR '??') as data_harga,
			sum(ifnull(qty_po,0)) as qty_po_total,
			sum(ifnull(qty_invoice,0)) as qty_invoice_total
			FROM (
				SELECT *
				FROM nd_po_penjualan
				$cond_customer
				) t1
			LEFT JOIN (
				SELECT barang_id, warna_id, sum(qty) as qty_po, po_penjualan_id, harga, closed_by, closed_date
				FROM nd_po_penjualan_detail
				GROUP BY barang_id, warna_id, po_penjualan_id, harga
			)t2
			ON t2.po_penjualan_id = t1.id
			LEFT JOIN (
				SELECT barang_id as barang_id_inv,warna_id as warna_id_inv, sum(subqty) as qty_invoice, po_penjualan_id
				FROM nd_penjualan_detail tA
				LEFT JOIN nd_penjualan tB
				ON tA.penjualan_id = tB.id
				GROUP BY barang_id, warna_id, po_penjualan_id
			) t3
			ON t2.barang_id = t3.barang_id_inv
			AND t2.warna_id = t3.warna_id_inv
			AND t2.po_penjualan_id = t3.po_penjualan_id
			LEFT JOIN nd_barang
			ON t2.barang_id = nd_barang.id 
			LEFT JOIN nd_warna 
			ON t2.warna_id = nd_warna.id
			LEFT JOIN nd_satuan
			ON nd_barang.satuan_id = nd_satuan.id 
			LEFT JOIN nd_customer  
			ON t1.customer_id = nd_customer.id
			LEFT JOIN nd_user 
			ON t2.closed_by = nd_user.id
			$cond
			GROUP BY t1.id
			
			", false);

		return $query->result();
	}
	
	function get_po_penjualan_header($id){
		$query = $this->db->query("SELECT t1.*, nd_customer.nama as nama_customer
			FROM (
				SELECT *
				FROM nd_po_penjualan
				WHERE id=$id
				) t1
			LEFT JOIN nd_customer ON t1.customer_id = nd_customer.id
		");

		return $query->result();
	}

	function get_po_penjualan_barang($id){
		$query = $this->db->query("SELECT t2.*, 
			if(ppn_include_status=1, harga, ceil(harga * (1+(ppn_value/100)))) as harga_jual,
			nama_jual as nama_barang,
			warna_jual as nama_warna,
			nd_satuan.nama as nama_satuan,
			sum(qty) as qty_po,
			ifnull(nd_user.username,'-') as closed_user
			FROM (
				SELECT *
				FROM nd_po_penjualan_detail
				WHERE po_penjualan_id=$id
				) t2
			LEFT JOIN nd_po_penjualan t1
			ON t2.po_penjualan_id = t1.id
			LEFT JOIN nd_barang ON t2.barang_id = nd_barang.id
			LEFT JOIN nd_warna ON t2.warna_id = nd_warna.id
			LEFT JOIN nd_satuan ON nd_barang.satuan_id = nd_satuan.id
			LEFT JOIN nd_user
			ON t2.closed_by = nd_user.id
			GROUP BY barang_id, warna_id
		");

		return $query->result();
	}

	function po_penjualan_invoices($po_penjualan_id){
		$query = $this->db->query("SELECT barang_id, warna_id, group_concat(penjualan_id) as penjualan_id,
			group_concat(no_faktur_lengkap) as no_faktur_lengkap, 
			group_concat(subqty) as qty_invoice,
			group_concat(id) as penjualan_id,
			group_concat(tanggal) as tanggal_invoice,
			group_concat(harga_jual) as harga_invoice
			FROM (
				select t1.id AS id,t1.toko_id ,t1.penjualan_type_id ,t1.no_faktur ,t1.po_number ,t1.tanggal ,
					t1.customer_id ,t1.ppn ,t1.gudang_id,t1.diskon ,t1.jatuh_tempo ,t1.ongkos_kirim,
					t1.keterangan ,t1.nama_keterangan ,t1.alamat_keterangan ,t1.status ,t1.status_aktif ,
					t1.closed_by ,t1.closed_date ,t1.user_id ,t1.created_at ,t1.revisi , t1.fp_status ,
					if((t1.tanggal < '2022-05-01 00:00:00'),concat('FPJ',convert(date_format(t1.tanggal,'%d%m%y') using latin1),'-',
					ifnull(t2.pre_faktur,''),convert(lpad(t1.no_faktur,5,'0') using latin1)),
					concat(t2.pre_po,':PJ01/',convert(date_format(t1.tanggal,'%y%m') using latin1),'/',convert(lpad(t1.no_faktur,4,'0') using latin1))
					) AS no_faktur_lengkap
				from nd_penjualan t1 
				left join nd_toko t2 
				on t1.toko_id = t2.id 
				WHERE po_penjualan_id = $po_penjualan_id
				AND no_faktur != ''
				AND no_faktur is not null
				) t1
			LEFT JOIN (
				SELECT penjualan_id, barang_id, warna_id, sum(subqty) as subqty, harga_jual
				FROM nd_penjualan_detail
				GROUP BY barang_id, warna_id, penjualan_id
				) t2
			ON t2.penjualan_id = t1.id
			GROUP BY barang_id, warna_id
		");

		return $query->result();
	}

	function po_penjualan_retur($po_penjualan_id){
		$query = $this->db->query("SELECT barang_id, warna_id, group_concat(retur_jual_id) as retur_jual_id,
			group_concat(no_faktur_lengkap) as no_faktur_lengkap, 
			group_concat(subqty) as qty_retur,
			group_concat(retur_jual_id) as penjualan_id,
			group_concat(tanggal) as tanggal_retur
			FROM (
				select t1.id AS id,t1.toko_id , t1.no_faktur ,t1.po_number ,t1.tanggal ,
					t1.customer_id,t1.nama_keterangan ,t1.status ,t1.status_aktif ,
					t1.closed_by ,t1.closed_date ,t1.user_id ,t1.created_at ,
					if((t1.tanggal < '2022-05-01 00:00:00'),concat('FPJ',convert(date_format(t1.tanggal,'%d%m%y') using latin1),'-',
					ifnull(t2.pre_faktur,''),convert(lpad(t1.no_faktur,5,'0') using latin1)),
					concat(t2.pre_po,':PJ04/',convert(date_format(t1.tanggal,'%y%m') using latin1),'/',convert(lpad(t1.no_faktur,4,'0') using latin1))
					) AS no_faktur_lengkap
				from nd_retur_jual t1 
				left join nd_toko t2 
				on t1.toko_id = t2.id 
				WHERE po_penjualan_id = $po_penjualan_id
				AND no_faktur != ''
				AND no_faktur is not null
				) t1
			LEFT JOIN (
				SELECT retur_jual_id, barang_id, warna_id, sum(qty) as subqty
				FROM nd_retur_jual_detail
				LEFT JOIN (
					SELECT sum(qty * if(jumlah_roll = 0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, retur_jual_detail_id
					FROM nd_retur_jual_qty
					group by retur_jual_detail_id
					) as nd_retur_jual_qty_detail
				ON nd_retur_jual_qty_detail.retur_jual_detail_id = nd_retur_jual_detail.id
				GROUP BY barang_id, warna_id, retur_jual_id
				) t2
			ON t2.retur_jual_id = t1.id
			GROUP BY barang_id, warna_id
		");

		return $query->result();
	}
	
	function po_penjualan_report_percustomer($cond_barang,$cond_customer){
		$query = $this->db->query("SELECT customer_id, nd_customer.nama as nama_customer,
			(sum(jml_warna) + COUNT(DISTINCT(barang_id))) as jml_baris,
			COUNT(DISTINCT(barang_id)) as jml_barang,
			GROUP_CONCAT( jml_warna SEPARATOR '||') as jml_warna,
			GROUP_CONCAT( barang_id SEPARATOR '||') as barang_id,
			GROUP_CONCAT( warna_id SEPARATOR '||') as warna_id,
			GROUP_CONCAT( satuan_id SEPARATOR '||') as satuan_id,

			GROUP_CONCAT( nama_barang SEPARATOR '||') as nama_barang,
			GROUP_CONCAT( nama_warna SEPARATOR '||') as nama_warna,
			GROUP_CONCAT( nama_satuan SEPARATOR '||') as nama_satuan,
			GROUP_CONCAT( harga SEPARATOR '||'  ) as harga_po,

			GROUP_CONCAT( po_number SEPARATOR '||' ) as po_number ,
			GROUP_CONCAT( po_penjualan_id SEPARATOR '||' ) as po_penjualan_id ,
			GROUP_CONCAT( po_tanggal SEPARATOR '||' ) as po_tanggal ,

			GROUP_CONCAT( qtyPoData SEPARATOR '||'  ) as qtyPoData ,
			GROUP_CONCAT( qtyJualData SEPARATOR '||'  ) as qtyJualData ,
			GROUP_CONCAT( qtyPo SEPARATOR '||'  ) as qtyPo ,
			GROUP_CONCAT( qtyJual SEPARATOR '||'  ) as qtyJual,
			GROUP_CONCAT( tglJual SEPARATOR '||'  ) as tglJual


			FROM (
				SELECT customer_id, 
				ifnull(t2.barang_id,'-') as barang_id, 
				ifnull(nd_barang.satuan_id,'-') as satuan_id,
				ifnull(nama_jual,'-') as nama_barang, 
				ifnull(nd_satuan.nama,'-') as nama_satuan,
				COUNT(DISTINCT(t2.warna_id)) as jml_warna,
				GROUP_CONCAT(t1.id SEPARATOR '??' ) as po_penjualan_id, 
				GROUP_CONCAT(t1.tanggal SEPARATOR '??' ) as po_tanggal, 
				GROUP_CONCAT(if(po_number is null OR po_number = '', '-', po_number)  SEPARATOR '??' ) as po_number,
				GROUP_CONCAT(tipe SEPARATOR '??' ) as tipe_po,
				GROUP_CONCAT(ifnull(harga,0) SEPARATOR '??' ) as harga,
				GROUP_CONCAT(ifnull(t2.warna_id,0) SEPARATOR '??' ) as warna_id, 
				GROUP_CONCAT(ifnull(warna_jual,'-') SEPARATOR '??' ) as nama_warna, 
				GROUP_CONCAT(ifnull(qty,0) SEPARATOR '??' ) as qtyPoData,
				GROUP_CONCAT(ifnull(subQty,0) SEPARATOR '??' ) as qtyJualData,
				sum(ifnull(qty,0)) as qtyPo, 
				sum(ifnull(if(subqty > t2.qty,t2.qty, subqty),0)) as qtyJual,
				GROUP_CONCAT(ifnull(last_jual,'-') SEPARATOR '??' ) as tglJual

				FROM (
					SELECT *
					FROM nd_po_penjualan
					WHERE status_po != 2
					$cond_customer
				) t1
				LEFT JOIN nd_po_penjualan_detail t2
				ON t1.id = t2.po_penjualan_id
				LEFT JOIN (
					SELECT barang_id as bid, warna_id as wid, po_penjualan_id as pid,
						sum(subqty) as subqty, max(tanggal) as last_jual 
					FROM nd_penjualan tA
					LEFT JOIN nd_penjualan_detail tB
					ON tB.penjualan_id = tA.id
					WHERE po_penjualan_id is not null
					AND po_penjualan_id != ''
					AND subqty > 0 
					GROUP BY barang_id, warna_id, po_penjualan_id
				)t4
				ON t2.barang_id = t4.bid
				AND t2.warna_id = t4.wid
				AND t2.po_penjualan_id=t4.pid
				LEFT JOIN nd_barang ON t2.barang_id = nd_barang.id
				LEFT JOIN nd_warna ON t2.warna_id = nd_warna.id
				LEFT JOIN nd_satuan ON nd_barang.satuan_id = nd_satuan.id
				WHERE t2.id is not null
				$cond_barang
				GROUP BY t2.barang_id, customer_id
			) res
			LEFT JOIN nd_customer ON res.customer_id = nd_customer.id
			GROUP BY customer_id
		");

		return $query->result();
	}

	function po_penjualan_report_perbarang($cond_barang, $cond_tipe){
		$query = $this->db->query("SELECT nama_barang, barang_id, nama_satuan,
			GROUP_CONCAT(warna_id SEPARATOR '||') as warna_id,
			GROUP_CONCAT(po_qty SEPARATOR '||') as po_qty,
			GROUP_CONCAT(po_qty_sisa SEPARATOR '||') as po_qty_sisa,
			GROUP_CONCAT(po_tanggal SEPARATOR '||') as po_tanggal,
			GROUP_CONCAT(nama_warna SEPARATOR '||') as nama_warna,
			GROUP_CONCAT(po_qty_data SEPARATOR '||') as po_qty_data,
			GROUP_CONCAT(po_qty_sisa_data SEPARATOR '||') as po_qty_sisa_data,
			GROUP_CONCAT(po_number SEPARATOR '||') as po_number,
			GROUP_CONCAT(nama_customer SEPARATOR '||') as nama_customer,
			GROUP_CONCAT(customer_id SEPARATOR '||') as customer_id,
			GROUP_CONCAT(po_penjualan_id SEPARATOR '||' ) as po_penjualan_id ,

			GROUP_CONCAT(jml_po SEPARATOR '||') as jml_po,
			GROUP_CONCAT(jml_customer SEPARATOR '||') as jml_customer,
			COUNT(DISTINCT(warna_id)) as jml_warna
			
		FROM (
			SELECT barang_id, warna_id, nd_satuan.nama as nama_satuan,
			sum(po_qty) as po_qty, 
			sum(qty_sisa) as po_qty_sisa,
			nama_jual as nama_barang, warna_jual as nama_warna,
			COUNT(DISTINCT(po_penjualan_id)) as jml_po,
			COUNT(DISTINCT(customer_id)) as jml_customer,

			GROUP_CONCAT(po_qty order by po_tanggal asc  SEPARATOR '??') as po_qty_data,
			GROUP_CONCAT(qty_sisa order by po_tanggal asc SEPARATOR '??') as po_qty_sisa_data,
			GROUP_CONCAT(nd_customer.nama order by po_tanggal asc SEPARATOR '??') as nama_customer,
			GROUP_CONCAT(customer_id order by po_tanggal asc SEPARATOR '??') as customer_id,
			GROUP_CONCAT(po_number order by po_tanggal asc SEPARATOR '??') as po_number,
			GROUP_CONCAT(po_tanggal order by po_tanggal asc SEPARATOR '??') as po_tanggal,
			GROUP_CONCAT(po_penjualan_id order by po_tanggal asc SEPARATOR '??') as po_penjualan_id
			
			FROM (
				SELECT t2.barang_id, t2.warna_id, customer_id,
					t1.id as po_penjualan_id, po_number, t1.tanggal as po_tanggal,
					t2.qty as po_qty, (t2.qty - if(subqty > t2.qty, t2.qty, ifnull(subqty,0))) as qty_sisa
				FROM (
					SELECT *
					FROM nd_po_penjualan
					WHERE status_po != 2
					$cond_tipe
				) t1
				LEFT JOIN nd_po_penjualan_detail t2
				ON t1.id = t2.po_penjualan_id
				LEFT JOIN (
					SELECT barang_id as bid, warna_id as wid, po_penjualan_id as pid,
						sum(subqty) as subqty, max(tanggal) as last_jual 
					FROM nd_penjualan tA
					LEFT JOIN nd_penjualan_detail tB
					ON tB.penjualan_id = tA.id
					WHERE po_penjualan_id is not null
					AND po_penjualan_id != ''
					AND subqty > 0 
					GROUP BY barang_id, warna_id, po_penjualan_id
				)t4
				ON t2.barang_id = t4.bid
				AND t2.warna_id = t4.wid
				AND t2.po_penjualan_id = t4.pid
				WHERE t2.id is not null
				$cond_barang
			) tbl
			LEFT JOIN nd_barang ON tbl.barang_id = nd_barang.id
			LEFT JOIN nd_warna ON tbl.warna_id = nd_warna.id
			LEFT JOIN nd_satuan ON nd_barang.satuan_id = nd_satuan.id
			LEFT JOIN nd_customer ON tbl.customer_id = nd_customer.id
			WHERE qty_sisa > 0
			GROUP BY barang_id, warna_id
			ORDER BY warna_jual asc
		)res
		GROUP BY barang_id
		ORDER BY nama_barang ASC
		");

		return $query->result();
	}

//=====================================mutasi barang==================================================


	function get_mutasi_barang_list_report($tanggal_awal, $tanggal_akhir, $cond_barang, $cond_warna, 
		$cond_gudang, $cond_gudang_masuk, $cond_gudang_keluar){
		$query = $this->db->query("SELECT tanggal, barang_id, warna_id, gudang_id, nd_barang.nama_jual as nama_barang, nd_warna.warna_jual as nama_warna, 
			no_dokumen, nd_satuan.nama as nama_satuan, nd_gudang.nama as nama_gudang, qty, jumlah_roll
			FROM (
				(
					SELECT tanggal, barang_id, warna_id, tB.gudang_id, qty, jumlah_roll, no_faktur_fp as no_dokumen, '1' as tipe
					FROM (
						SELECT *
						FROM nd_penjualan
						WHERE tanggal >= '$tanggal_awal'
						AND tanggal <= '$tanggal_akhir'
					) tA
					LEFT JOIN (
						SELECT barang_id, warna_id, gudang_id, penjualan_id, sum(subqty) as qty, sum(subjumlah_roll) as jumlah_roll
						FROM nd_penjualan_detail
						WHERE subqty > 0
						$cond_barang
						$cond_warna
						$cond_gudang
						GROUP BY barang_id, warna_id, gudang_id, penjualan_id
					) tB
					ON tB.penjualan_id = tA.id
					WHERE tB.barang_id is not null
				)
				UNION(
					SELECT tanggal, barang_id, warna_id, tA.gudang_id, qty, jumlah_roll, no_faktur, '2' as tipe
					FROM (
						SELECT *
						FROM nd_pembelian
						WHERE tanggal >= '$tanggal_awal'
						AND tanggal <= '$tanggal_akhir'
					) tA
					LEFT JOIN (
						SELECT barang_id, warna_id, pembelian_id, sum(qty) as qty, sum(jumlah_roll) as jumlah_roll
						FROM nd_pembelian_detail
						WHERE qty > 0
						$cond_barang
						$cond_warna
						$cond_gudang
						GROUP BY barang_id, warna_id, gudang_id, pembelian_id
					) tB
					ON tB.pembelian_id = tA.id
					WHERE tB.barang_id is not null
				)
				UNION(
					SELECT tanggal, barang_id, warna_id, gudang_id_before, qty, jumlah_roll,no_mutasi_lengkap, '3' as tipe
					FROM nd_mutasi_barang
					WHERE tanggal >= '$tanggal_awal'
					AND tanggal <= '$tanggal_akhir'
					$cond_barang
					$cond_warna
					$cond_gudang_keluar
				)
				UNION(
					SELECT tanggal, barang_id, warna_id, gudang_id_after, qty, jumlah_roll,no_mutasi_lengkap, '4' as tipe
					FROM nd_mutasi_barang
					WHERE tanggal >= '$tanggal_awal'
					AND tanggal <= '$tanggal_akhir'
					$cond_barang
					$cond_warna
					$cond_gudang_masuk
				)
			) res
			LEFT JOIN nd_barang 
			ON res.barang_id = nd_barang.id
			LEFT JOIN nd_warna 
			ON res.warna_id = nd_warna.id
			LEFT JOIN nd_gudang 
			ON res.gudang_id = nd_gudang.id
			LEFT JOIN nd_satuan 
			ON nd_barang.satuan_id = nd_satuan.id

			
		");

		return $query->result();
	}

	function get_penjualan_faktur_pajak_report($tanggal_start, $tanggal_end, $cond_customer){
		$query = $this->db->query("SELECT t1.tanggal as tanggal, t2.total_jual, 
			nama_cust_fp as nama_customer, no_faktur_pajak, no_faktur_fp as no_faktur_jual, t3.total_jual as total_jual_fp, ppn_berlaku
			FROM (
				SELECT id, tanggal, no_faktur_fp,nama_cust_fp
				FROM nd_penjualan
				WHERE status_aktif = 1 
				AND no_faktur_fp is not null
				AND tanggal >= '$tanggal_start'
				AND tanggal <= '$tanggal_end'
				$cond_customer
			)t1
			LEFT JOIN (
				SELECT sum(subqty * harga_jual) as total_jual, penjualan_id
				FROM nd_penjualan_detail
				GROUP BY penjualan_id
			) t2
			ON t1.id = t2.penjualan_id
			LEFT JOIN nd_rekam_faktur_pajak_detail t3
			ON t1.id = t3.penjualan_id

			
		");

		return $query->result();
	}

}
