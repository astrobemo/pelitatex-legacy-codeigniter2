<?php

class Pajak_Model extends CI_Model {

//====================================daftar no faktur pajak======================================

	function get_no_faktur_pajak_list(){
		$query = $this->db->query("SELECT t1.*, username, ifnull(terpakai,0) as terpakai
			FROM nd_no_faktur_pajak	t1	
			LEFT JOIN (
				SELECT sum(1) as terpakai, no_faktur_pajak_id
				FROM nd_rekam_faktur_pajak_detail
				GROUP BY no_faktur_pajak_id
				) t11
			ON t1.id = t11.no_faktur_pajak_id	
			LEFT JOIN nd_user t2
			ON t1.user_id = t2.id
			", false);

		return $query->result();
	}

	function get_no_faktur_pajak_list_detail($id){
		$query = $this->db->query("SELECT *
			FROM nd_no_faktur_pajak
			WHERE id = $id
			", false);

		return $query->result();
	}

	function get_rekam_faktur_pajak_detail_by_no($id)
	{
		$query = $this->db->query("SELECT t1.*,nama_customer, tanggal, no_invoice, g_total, count_item, g_total_raw, customer_id
			FROM (
				SELECT *
				FROM nd_rekam_faktur_pajak_detail
				WHERE no_faktur_pajak_id = $id
				)t1
			LEFT JOIN (
				SELECT tA.*,nama_cust_fp as nama_customer, 
				g_total, count_item, g_total_raw
				no_faktur_lengkap as no_invoice, 
				FROM nd_penjualan tA
				LEFT JOIN (
					SELECT sum(qty * nd_penjualan_detail.harga_jual) as g_total,sum(qty * (nd_penjualan_detail.harga_jual/1.1) ) as g_total_raw, penjualan_id, sum(1) as count_item
					FROM nd_penjualan_detail
					LEFT JOIN (
						SELECT sum(qty * if(jumlah_roll = 0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, penjualan_detail_id
						FROM nd_penjualan_qty_detail
						group by penjualan_detail_id
						) as nd_penjualan_qty_detail
					ON nd_penjualan_qty_detail.penjualan_detail_id = nd_penjualan_detail.id
					GROUP BY penjualan_id
					) tB
				ON tB.penjualan_id = tA.id
				LEFT JOIN nd_customer tC
				ON tA.customer_id = tC.id
				) t2
			ON t1.penjualan_id = t2.id

			", false);

		return $query->result();
	}

//====================================daftar no rekam faktur pajak======================================

	function get_data_rekam_faktur($rekam_faktur_pajak_id){
		$query = $this->db->query("SELECT t1.*, tanggal_start, tanggal_end
			FROM (
				SELECT * 
				FROM nd_rekam_faktur_pajak 
				where id=$rekam_faktur_pajak_id
				)t1
			LEFT JOIN (
				SELECT min(tanggal) as tanggal_start, max(tanggal) as tanggal_end, rekam_faktur_pajak_id
				FROM nd_rekam_faktur_pajak_detail tA
				LEFT JOIN nd_penjualan tB
				ON tA.penjualan_id = tB.id
				GROUP BY rekam_faktur_pajak_id
				)t2
			ON t1.id = t2.rekam_faktur_pajak_id
			", false);

		return $query->result();
	}

	function get_rekam_faktur_pajak_list_legacy($limit){
		$query = $this->db->query("SELECT tA.*, tB.*, tC.no_faktur_pajak as no_fp_awal, tD.no_faktur_pajak as no_fp_akhir
			FROM nd_rekam_faktur_pajak tA
			LEFT JOIN (
				SELECT sum(if(status = 1,1,0)) as jml_faktur, sum(if(status=0,1,0)) as jml_faktur_batal, 
				sum(if(status != 0 , CEIL(g_total/1.1) ,0 )) as g_total_raw, 
				sum(if(status != 0 ,g_total-CEIL(g_total/1.1) ,0 )) as g_total_ppn, rekam_faktur_pajak_id, 
				min(tanggal) as tanggal_start, 
				max(tanggal) as tanggal_end
				FROM nd_rekam_faktur_pajak_detail t1
				LEFT JOIN (
					SELECT tA.id, g_total, tanggal
					FROM nd_penjualan tA
					LEFT JOIN (
						SELECT sum(subqty *nd_penjualan_detail.harga_jual) as g_total, penjualan_id, sum(1) as count_item
						FROM nd_penjualan_detail
						GROUP BY penjualan_id
						) tB
					ON tB.penjualan_id = tA.id
					) t2
				ON t1.penjualan_id = t2.id
				GROUP BY rekam_faktur_pajak_id
				)tB
			ON tB.rekam_faktur_pajak_id = tA.id
			LEFT JOIN (
				SELECT rekam_faktur_pajak_id, no_faktur_pajak
				FROM nd_rekam_faktur_pajak_detail t1
				WHERE id in (
					SELECT min(id)
					FROM nd_rekam_faktur_pajak_detail
					GROUP BY rekam_faktur_pajak_id
					)
				) tC
			ON tA.id = tC.rekam_faktur_pajak_id
			LEFT JOIN (
				SELECT rekam_faktur_pajak_id, no_faktur_pajak
				FROM nd_rekam_faktur_pajak_detail t1
				WHERE id in (
					SELECT max(id)
					FROM nd_rekam_faktur_pajak_detail
					GROUP BY rekam_faktur_pajak_id
					)
				) tD
			ON tA.id = tD.rekam_faktur_pajak_id
			ORDER BY tA.id desc 
			$limit
			", false);

		return $query->result();
	}

	function get_rekam_faktur_pajak_list($limit){
		$query = $this->db->query("SELECT tA.*
			FROM nd_rekam_faktur_pajak tA
			ORDER BY tA.id desc 
			$limit
			", false);

		return $query->result();
	}

	function get_rekam_faktur_pajak_detail($id, $cond)
	{
		$query = $this->db->query("SELECT t1.*,nama_customer, tanggal, no_invoice, g_total, count_item, 
		g_total_raw, npwp, nik, customer_id, alamat, kota, provinsi, kode_pos, locked_status as locked_status_cust, 
		email as email_customer, ppn_berlaku_now as ppn_berlaku
			FROM (
				SELECT *
				FROM nd_rekam_faktur_pajak_detail
				WHERE rekam_faktur_pajak_id = $id
				)t1
			LEFT JOIN (
				SELECT tA.*,tC.nama as nama, 
				no_faktur_lengkap as no_invoice, 
				g_total, count_item, FLOOR(g_total_raw/(1+(ppn_berlaku_now/100))) as g_total_raw, npwp, nik, alamat, kota, provinsi, kode_pos, 
				locked_status, email
				FROM (
					SELECT *,
					(SELECT ppn FROM nd_ppn tX WHERE tX.tanggal <= t1.tanggal ORDER BY tX.tanggal DESC LIMIT 1 ) as ppn_berlaku_now
					FROM (
						select t1.id, t1.toko_id, t1.penjualan_type_id, t1.no_faktur, t1.po_number, t1.tanggal, 
						t1.customer_id, t1.ppn, t1.gudang_id, t1.diskon, t1.jatuh_tempo, t1.ongkos_kirim, t1.keterangan, 
						t1.nama_keterangan, t1.alamat_keterangan, t1.status, t1.status_aktif, t1.closed_by,
						t1.closed_date ,t1.user_id ,t1.created_at, t1.revisi, t1.fp_status , is_custom_view,
						if((t1.tanggal < '2022-05-01 00:00:00'),
						concat('FPJ',convert(date_format(t1.tanggal,'%d%m%y') using latin1),'-',ifnull(t2.pre_faktur,''),convert(lpad(t1.no_faktur,5,'0') using latin1)),
						concat(t2.pre_po,':PJ01/',convert(date_format(t1.tanggal,'%y%m') using latin1),'/',convert(lpad(t1.no_faktur,4,'0') using latin1))) AS no_faktur_lengkap
						from nd_penjualan t1
						left join nd_toko t2 
						on t1.toko_id = t2.id
					) t1
					) tA
				LEFT JOIN (
					SELECT sum(qty * nd_penjualan_detail.harga_jual) as g_total,sum(ROUND((qty * nd_penjualan_detail.harga_jual),2) ) as g_total_raw, penjualan_id, sum(1) as count_item
					FROM nd_penjualan_detail
					LEFT JOIN (
						SELECT sum(qty * if(jumlah_roll = 0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, penjualan_detail_id
						FROM nd_penjualan_qty_detail
						group by penjualan_detail_id
						) as nd_penjualan_qty_detail
					ON nd_penjualan_qty_detail.penjualan_detail_id = nd_penjualan_detail.id
					GROUP BY penjualan_id
					) tB
				ON tB.penjualan_id = tA.id
				LEFT JOIN (
					SELECT id, ifnull(npwp,'') as npwp, ifnull(nik,'') as nik, 
					concat(if(tipe_company != '' OR tipe_company != null,concat(tipe_company,' '),''),nama) as nama, 
					if(kode_pos='0' or kode_pos='','00000', kode_pos) as kode_pos, kota,  provinsi, 
					concat(alamat,' ',
						concat('BLOK ', if(blok='','-',blok)),' ',
						concat('NO.',if(no='','-',no)),' ',
						concat('RT:', if(rt='','000',LPAD(rt,3,'0') )),' ',
						concat('RW:', if(rw='','000',LPAD(rw,3,'0') )),' ',
						concat('Kel.',if(kelurahan='','-', kelurahan) ),' ', 
						concat('Kec.',if(kecamatan='','-',kecamatan) ) 
						) as alamat, locked_status, email
					FROM nd_customer
					) tC
				ON tA.customer_id = tC.id
				) t2
			ON t1.penjualan_id = t2.id
			$cond

			", false);

		return $query->result();
	}

	function update_rekam_faktur_pajak_detail($id){
		$query = $this->db->query("UPDATE nd_rekam_faktur_pajak_detail t1,
		(
			SELECT tA.id as penjualan_id, alamat, nama as nama_customer, no_faktur_lengkap
			FROM (
				select t1.id as id, customer_id, 
				if((t1.tanggal < '2022-05-01 00:00:00'),
				concat('FPJ',convert(date_format(t1.tanggal,'%d%m%y') using latin1),'-',ifnull(t2.pre_faktur,''),convert(lpad(t1.no_faktur,5,'0') using latin1)),
				concat(t2.pre_po,':PJ01/',convert(date_format(t1.tanggal,'%y%m') using latin1),'/',convert(lpad(t1.no_faktur,4,'0') using latin1))) AS no_faktur_lengkap
				from nd_penjualan t1
				left join nd_toko t2 
				on t1.toko_id = t2.id
			) tA
			LEFT JOIN (
				SELECT id, ifnull(npwp,'') as npwp, ifnull(nik,'') as nik, concat(if(tipe_company is not null,concat(tipe_company,' '),''),nama) as nama_customer, 
				concat(alamat,' ',
					concat('BLOK ', if(blok='','-',blok)),' ',
					concat('NO.',if(no='','-',no)),' ',
					concat('RT:', if(rt='','000',LPAD(rt,3,'0') )),' ',
					concat('RW:', if(rw='','000',LPAD(rw,3,'0') )),' ',
					concat('Kel.',if(kelurahan='','-', kelurahan) ),' ', 
					concat('Kec.',if(kecamatan='','-',kecamatan) ),' ', 
					concat('Kota/Kab.',if(kota='','-',kota),' ' ),
					if(provinsi='','',concat(provinsi,' ')),
					if(kode_pos='','00000',kode_pos) 
					) as alamat, locked_status, email
				FROM nd_customer
				) tC
			ON tA.customer_id = tC.id
		) t2
		SET t1.alamat_lengkap=t2.alamat, t1.no_npwp=t2.npwp, t1.no_nik=t2.nik , t1.no_faktur_jual = t2.no_faktur_lengkap, t1.nama_customer = t2.nama_customer
		WHERE t1.penjualan_id = t2.penjualan_id
		AND t1.rekam_faktur_pajak_id=$id");

		return $this->db->last_query();
	}
	

	function get_range_faktur($id)
	{
		$query = $this->db->query("SELECT min(tanggal) as tanggal_start, max(tanggal) as tanggal_end
			FROM nd_rekam_faktur_pajak_detail t1
			LEFT JOIN nd_penjualan t2
			ON t1.penjualan_id = t2.id
			", false);

		return $query->result();
	}

	function get_rekam_faktur_lain($id, $tanggal_start, $tanggal_end){
		$query = $this->db->query("SELECT t1.id, t3.nama as nama_customer, no_invoice as no_faktur
			FROM (
				SELECT *, no_faktur_lengkap as no_invoice
				FROM (
					select t1.id, t1.toko_id, t1.penjualan_type_id, t1.no_faktur, t1.po_number, t1.tanggal, 
					t1.customer_id, t1.ppn, t1.gudang_id, t1.diskon, t1.jatuh_tempo, t1.ongkos_kirim, t1.keterangan, 
					t1.nama_keterangan, t1.alamat_keterangan, t1.status, t1.status_aktif, t1.closed_by,
					t1.closed_date ,t1.user_id ,t1.created_at, t1.revisi, t1.fp_status , is_custom_view,
					if((t1.tanggal < '2022-05-01 00:00:00'),
					concat('FPJ',convert(date_format(t1.tanggal,'%d%m%y') using latin1),'-',ifnull(t2.pre_faktur,''),convert(lpad(t1.no_faktur,5,'0') using latin1)),
					concat(t2.pre_po,':PJ01/',convert(date_format(t1.tanggal,'%y%m') using latin1),'/',convert(lpad(t1.no_faktur,4,'0') using latin1))) AS no_faktur_lengkap
					from nd_penjualan t1
					left join nd_toko t2 
					on t1.toko_id = t2.id
				)res
				WHERE tanggal >= '$tanggal_start'
				AND tanggal <= '$tanggal_end'
				AND status_aktif = 1
				AND penjualan_type_id != 3
				AND no_faktur != ''
			) t1
			LEFT JOIN nd_rekam_faktur_pajak_detail t2
			ON t2.penjualan_id = t1.id
			LEFT JOIN nd_customer t3
			ON t1.customer_id = t3.id
			WHERE t2.id is null
			", false);

		return $query->result();
	}

	function get_faktur_to_rekam_legacy($tanggal_start, $tanggal_end){
		$query = $this->db->query("SELECT t1.*, t3.nama_customer, g_total as total_jual, g_total*(ppn_berlaku_now/100) as total_ppn
		FROM (
			SELECT t1.*, g_total,
				(SELECT ppn FROM nd_ppn tX WHERE tX.tanggal <= t1.tanggal ORDER BY tX.tanggal DESC LIMIT 1 ) as ppn_berlaku_now
				FROM (
				select t1.id, t1.toko_id, t1.penjualan_type_id, t1.no_faktur, t1.po_number, t1.tanggal, 
				t1.customer_id, t1.ppn, t1.gudang_id, t1.diskon, t1.jatuh_tempo, t1.ongkos_kirim, t1.keterangan, 
				t1.nama_keterangan, t1.alamat_keterangan, t1.status, t1.status_aktif, t1.closed_by,
				t1.closed_date ,t1.user_id ,t1.created_at, t1.revisi, t1.fp_status , is_custom_view,
				if((t1.tanggal < '2022-05-01 00:00:00'),
				concat('FPJ',convert(date_format(t1.tanggal,'%d%m%y') using latin1),'-',ifnull(t2.pre_faktur,''),convert(lpad(t1.no_faktur,5,'0') using latin1)),
				concat(t2.pre_po,':PJ01/',convert(date_format(t1.tanggal,'%y%m') using latin1),'/',convert(lpad(t1.no_faktur,4,'0') using latin1))) AS no_faktur_lengkap
				FROM nd_penjualan t1
				left join nd_toko t2 
				on t1.toko_id = t2.id
				WHERE fp_status = 1
				AND tanggal >= '$tanggal_start'
				AND tanggal <= '$tanggal_end'
				AND t1.status_aktif = 1
				AND no_faktur is not null
				AND no_faktur != ''
				ORDER BY no_faktur asc
			) t1
			LEFT JOIN (
				SELECT sum(subqty * harga_jual) as g_total, penjualan_id
				FROM nd_penjualan_detail
				GROUP BY penjualan_id
			)t2
			ON t1.id = t2.penjualan_id
		)t1
		LEFT JOIN (
		SELECT id, ifnull(npwp,'') as npwp, ifnull(nik,'') as nik, concat(if(tipe_company is not null,concat(tipe_company,' '),''),nama) as nama_customer, 
		concat(alamat,' ',
			concat('BLOK ', if(blok='','-',blok)),' ',
			concat('NO.',if(no='','-',no)),' ',
			concat('RT:', if(rt='','000',LPAD(rt,3,'0') )),' ',
			concat('RW:', if(rw='','000',LPAD(rw,3,'0') )),' ',
			concat('Kel.',if(kelurahan='','-', kelurahan) ),' ', 
			concat('Kec.',if(kecamatan='','-',kecamatan) ),' ', 
			concat('Kota/Kab.',if(kota='','-',kota),' ' ),
			if(provinsi='','',concat(provinsi,' ')),
			if(kode_pos='','00000',kode_pos) 
			) as alamat, locked_status, email
		FROM nd_customer
		) t3
		ON t1.customer_id = t3.id
		LEFT JOIN nd_rekam_faktur_pajak_detail t2
		ON t1.id = t2.penjualan_id
		WHERE t2.id is null
		ORDER BY tanggal, no_faktur asc
			", false);

		return $query->result();	
	}

	function get_faktur_to_rekam($tanggal_start, $tanggal_end){
		$query = $this->db->query("SELECT t1.*, nama_cust_fp as nama_customer, g_total as total_jual, g_total*(ppn_berlaku_now/100) as total_ppn
		FROM (
			SELECT t1.*, g_total,
				(SELECT ppn FROM nd_ppn tX WHERE tX.tanggal <= t1.tanggal ORDER BY tX.tanggal DESC LIMIT 1 ) as ppn_berlaku_now
				FROM (
				select t1.id, t1.toko_id, t1.penjualan_type_id, t1.no_faktur, t1.po_number, t1.tanggal, 
				t1.customer_id, t1.ppn, t1.gudang_id, t1.diskon, t1.jatuh_tempo, t1.ongkos_kirim, t1.keterangan, 
				t1.nama_keterangan, t1.alamat_keterangan, t1.status, t1.status_aktif, t1.closed_by,
				t1.closed_date ,t1.user_id ,t1.created_at, t1.revisi, t1.fp_status , is_custom_view,
				no_faktur_fp AS no_faktur_lengkap,
				nama_cust_fp, alamat_cust_fp, npwp_cust_fp, nik_cust_fp
				FROM nd_penjualan t1
				left join nd_toko t2
				on t1.toko_id = t2.id
				WHERE fp_status = 1
				AND tanggal >= '$tanggal_start'
				AND tanggal <= '$tanggal_end'
				AND t1.status_aktif = 1
				AND no_faktur is not null
				AND no_faktur != ''
				ORDER BY no_faktur asc
			) t1
			LEFT JOIN (
				SELECT sum(subqty * harga_jual) as g_total, penjualan_id
				FROM nd_penjualan_detail
				GROUP BY penjualan_id
			)t2
			ON t1.id = t2.penjualan_id
		)t1
		LEFT JOIN nd_rekam_faktur_pajak_detail t2
		ON t1.id = t2.penjualan_id
		WHERE t2.id is null
		ORDER BY tanggal, no_faktur asc
			", false);

		return $query->result();	
	}

	function get_available_no_faktur_pajak($tahun_pajak){
		$query = $this->db->query("SELECT t1.*, (substring(no_fp_akhir,'-7') + 1 -  substring(no_fp_awal,'-7')) - ifnull(jml,0) as sisa, ifnull(jml,0) as jml
			FROM (
				SELECT *
				FROM nd_no_faktur_pajak
				WHERE YEAR(tahun_pajak) = '$tahun_pajak'
				) t1
			LEFT JOIN (
				SELECT *, sum(1) as jml
				FROM nd_rekam_faktur_pajak_detail
				GROUP BY no_faktur_pajak_id
				) t2
			ON t2.no_faktur_pajak_id = t1.id
			WHERE (substring(no_fp_akhir,'-7') + 1 -  substring(no_fp_awal,'-7')) - ifnull(jml,0) > 0
			ORDER BY tanggal asc
			", false);

		return $query->result();	
	}

//====================================data download faktur pajak=======================

	function get_item_penjualan_list($penjualan_id_list){
		$query = $this->db->query("SELECT sum(qty) as qty, sum(jumlah_roll) as jumlah_roll,t1.harga_jual,
		penjualan_id,jenis_barang as nama_barang, if(t1.nama_jual_tercetak is null OR t1.nama_jual_tercetak = '' OR t1.nama_jual_tercetak = '0',t3.nama_jual, nama_jual_tercetak) as kode_barang,
		barang_id, ppn_berlaku
			FROM nd_penjualan_detail t1
			LEFT JOIN (
				SELECT sum(qty * if(jumlah_roll = 0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, penjualan_detail_id
				FROM nd_penjualan_qty_detail
				group by penjualan_detail_id
				) t2
			ON t2.penjualan_detail_id = t1.id
			LEFT JOIN nd_barang t3
			ON t1.barang_id = t3.id
			LEFT JOIN (
				SELECT *,
				(SELECT ppn FROM nd_ppn tX WHERE tX.tanggal <= nd_penjualan.tanggal ORDER BY tX.tanggal DESC LIMIT 1 ) as ppn_berlaku
				FROM nd_penjualan
				) tA
			ON t1.penjualan_id = tA.id
			WHERE t1.penjualan_id in ($penjualan_id_list)
			GROUP BY penjualan_id, barang_id, t1.harga_jual
			", false);

		return $query->result();	
	}

	function get_data_penjualan_list_legacy($penjualan_id_list){
		$query = $this->db->query("SELECT no_faktur_pajak, MONTH(t1.tanggal) as MASA_PAJAK, YEAR(t1.tanggal) as TAHUN_PAJAK, 
		DATE_FORMAT(t1.tanggal,'%d/%m/%Y') as TANGGAL_FAKTUR, if(penjualan_type_id != 3 , t2.npwp ,'ERROR') as NPWP, 
		if(penjualan_type_id != 3 , t2.nama ,'ERROR') as NAMA, if(penjualan_type_id != 3 , t2.alamat ,'ERROR') as ALAMAT_LENGKAP, 
		t1.id, t3.nama as NAMA_TOKO, t3.alamat as ALAMAT_TOKO, t1.id as penjualan_id, 
		no_faktur_lengkap as no_faktur, nama_customer,
		nik as NIK, g_total, t2.kota as kota, provinsi, ifnull(t2.kode_pos,'00000') as kode_pos,
		(SELECT ppn FROM nd_ppn tX WHERE tX.tanggal <= t1.tanggal ORDER BY tX.tanggal DESC LIMIT 1 ) as ppn_berlaku
			FROM (
				SELECT tA.*,g_total
				FROM (
					SELECT *
					FROM vw_penjualan_data
					WHERE id in ($penjualan_id_list)
					) tA
				LEFT JOIN (
					SELECT sum(qty * nd_penjualan_detail.harga_jual) as g_total,sum(qty * (nd_penjualan_detail.harga_jual/1.1) ) as g_total_raw, penjualan_id, sum(1) as count_item
					FROM nd_penjualan_detail
					LEFT JOIN (
						SELECT sum(qty * if(jumlah_roll = 0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, penjualan_detail_id
						FROM nd_penjualan_qty_detail
						group by penjualan_detail_id
						) as nd_penjualan_qty_detail
					ON nd_penjualan_qty_detail.penjualan_detail_id = nd_penjualan_detail.id
					GROUP BY penjualan_id
					) tB
				ON tB.penjualan_id = tA.id
				) t1
			LEFT JOIN (
				SELECT id, ifnull(npwp,'') as npwp, ifnull(nik,'') as nik, 
				concat(if(tipe_company != '' OR tipe_company != null,
				concat(tipe_company,' '),''),nama) as nama_customer, 
				if(kode_pos='0' or kode_pos='',null, kode_pos) as kode_pos, kota,  provinsi, 
					concat(alamat,' ',
						concat('BLOK ', if(blok='','-',blok)),' ',
						concat('NO.',if(no='','-',no)),' ',
						concat('RT:', if(rt='','000',LPAD(rt,3,'0') )),' ',
						concat('RW:', if(rw='','000',LPAD(rw,3,'0') )),' ',
						concat('Kel.',if(kelurahan='','-', kelurahan) ),' ', 
						concat('Kec.',if(kecamatan='','-',kecamatan) ) 
						) as alamat
				FROM nd_customer
				) t2
			ON t1.customer_id = t2.id
			LEFT JOIN nd_toko t3
			ON t1.toko_id = t3.id
			LEFT JOIN nd_rekam_faktur_pajak_detail t4
			ON t4.penjualan_id = t1.id
			", false);

		return $query->result();	
	}

	function get_data_penjualan_list($penjualan_id_list){
		$query = $this->db->query("SELECT no_faktur_pajak, MONTH(t1.tanggal) as MASA_PAJAK, YEAR(t1.tanggal) as TAHUN_PAJAK, 
		DATE_FORMAT(t1.tanggal,'%d/%m/%Y') as TANGGAL_FAKTUR, if(penjualan_type_id != 3 , t2.npwp ,'ERROR') as NPWP, 
		if(penjualan_type_id != 3 , t2.nama ,'ERROR') as NAMA, if(penjualan_type_id != 3 , t2.alamat ,'ERROR') as ALAMAT_LENGKAP, 
		t1.id, t3.nama as NAMA_TOKO, alamat_toko as ALAMAT_TOKO, t1.id as penjualan_id, 
		no_faktur_lengkap as no_faktur, 
		nik as NIK, g_total, t2.kota as kota, provinsi, ifnull(t2.kode_pos,'00000') as kode_pos,
		(SELECT ppn FROM nd_ppn tX WHERE tX.tanggal <= t1.tanggal ORDER BY tX.tanggal DESC LIMIT 1 ) as ppn_berlaku
			FROM (
				SELECT tA.*,g_total
				FROM (
					select t1.id AS id,t1.toko_id ,t1.penjualan_type_id ,t1.no_faktur ,t1.po_number ,t1.tanggal ,
						t1.customer_id ,t1.ppn ,t1.gudang_id,t1.diskon ,t1.jatuh_tempo ,t1.ongkos_kirim,
						t1.keterangan ,t1.nama_keterangan ,t1.alamat_keterangan ,t1.status ,t1.status_aktif ,
						t1.closed_by ,t1.closed_date ,t1.user_id ,t1.created_at ,t1.revisi , t1.fp_status ,
						t1.alamat_toko,
						if((t1.tanggal < '2022-05-01 00:00:00'),concat('FPJ',convert(date_format(t1.tanggal,'%d%m%y') using latin1),'-',ifnull(t2.pre_faktur,''),convert(lpad(t1.no_faktur,5,'0') using latin1)),concat(t2.pre_po,':PJ01/',convert(date_format(t1.tanggal,'%y%m') using latin1),'/',convert(lpad(t1.no_faktur,4,'0') using latin1))) AS no_faktur_lengkap
					from nd_penjualan t1 
					left join nd_toko t2 
					on t1.toko_id = t2.id 
					WHERE t1.id in ($penjualan_id_list)
					) tA
				LEFT JOIN (
					SELECT sum(qty * nd_penjualan_detail.harga_jual) as g_total,sum(qty * (nd_penjualan_detail.harga_jual/1.1) ) as g_total_raw, penjualan_id, sum(1) as count_item
					FROM nd_penjualan_detail
					LEFT JOIN (
						SELECT sum(qty * if(jumlah_roll = 0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, penjualan_detail_id
						FROM nd_penjualan_qty_detail
						group by penjualan_detail_id
						) as nd_penjualan_qty_detail
					ON nd_penjualan_qty_detail.penjualan_detail_id = nd_penjualan_detail.id
					GROUP BY penjualan_id
					) tB
				ON tB.penjualan_id = tA.id
				) t1
			LEFT JOIN (
				SELECT id, ifnull(npwp,'') as npwp, ifnull(nik,'') as nik, concat(if(tipe_company != '' OR tipe_company != null,concat(tipe_company,' '),''),nama) as nama, if(kode_pos='0' or kode_pos='',null, kode_pos) as kode_pos, kota,  provinsi, 
					concat(alamat,' ',
						concat('BLOK ', if(blok='','-',blok)),' ',
						concat('NO.',if(no='','-',no)),' ',
						concat('RT:', if(rt='','000',LPAD(rt,3,'0') )),' ',
						concat('RW:', if(rw='','000',LPAD(rw,3,'0') )),' ',
						concat('Kel.',if(kelurahan='','-', kelurahan) ),' ', 
						concat('Kec.',if(kecamatan='','-',kecamatan) ) 
						) as alamat
				FROM nd_customer
				) t2
			ON t1.customer_id = t2.id
			LEFT JOIN nd_toko t3
			ON t1.toko_id = t3.id
			LEFT JOIN nd_rekam_faktur_pajak_detail t4
			ON t4.penjualan_id = t1.id
			", false);

		return $query->result();	
	}

//====================================data email=============================================

	function get_data_dari_no_faktur($id, $no_faktur){
		$query = $this->db->query("SELECT concat(if(tipe_company != '' OR tipe_company != null,concat(tipe_company,' '),''),nama) as nama, email, npwp, nik, group_concat(no_faktur_pajak SEPARATOR '??') as no_faktur_pajak, t2.customer_id, t2.tanggal as tanggal_invoice
			FROM (
				SELECT *
				FROM nd_rekam_faktur_pajak_detail
				WHERE rekam_faktur_pajak_id = $id
				AND no_faktur_pajak like '%$no_faktur%'
				)t1
			LEFT JOIN nd_penjualan t2
			ON t1.penjualan_id = t2.id 
			LEFT JOIN nd_customer t3
			ON t2.customer_id = t3.id

			", false);

		return $query->result();	
	}

	function get_rekam_faktur_pajak_email_legacy($id, $cond){
		$query = $this->db->query("SELECT concat(if(tipe_company != '' OR tipe_company != null,concat(tipe_company,' '),''),nama) as nama, email, npwp, nik, 
		group_concat(no_faktur_pajak SEPARATOR '??') as no_faktur_pajak, t2.customer_id, t4.updated_at as waktu_kirim, t4.id as kirim_id , group_concat(t2.tanggal) as tanggal_invoice, 
		status_1,status_2,status_3,status_4,t4.keterangan, email_stat, group_concat(penjualan_id) as penjualan_id, t4.id as rekam_faktur_pajak_email_id,
		message_id, thread_id, label_id
			FROM (
				SELECT *
				FROM nd_rekam_faktur_pajak_detail
				WHERE rekam_faktur_pajak_id = $id
				AND status = 1
				)t1
			LEFT JOIN nd_penjualan t2
			ON t1.penjualan_id = t2.id 
			LEFT JOIN nd_customer t3
			ON t2.customer_id = t3.id
			LEFT JOIN nd_rekam_faktur_pajak_email t4
			ON t1.rekam_faktur_pajak_id = t4.rekam_faktur_pajak_id
			AND t2.customer_id = t4.customer_id
			$cond
 			GROUP BY customer_id
			ORDER BY t3.nama
			", false);

		return $query->result();	
	}

	function get_rekam_faktur_pajak_email($id, $cond){
		$query = $this->db->query("SELECT t1.nama_customer as nama, email, t1.no_npwp as npwp, t1.no_nik as nik, 
		group_concat(no_faktur_pajak SEPARATOR '??') as no_faktur_pajak, t2.customer_id, t4.updated_at as waktu_kirim, t4.id as kirim_id , group_concat(t2.tanggal) as tanggal_invoice, 
		status_1,status_2,status_3,status_4,t4.keterangan, email_stat, group_concat(penjualan_id) as penjualan_id, t4.id as rekam_faktur_pajak_email_id,
		message_id, thread_id, label_id
			FROM (
				SELECT *
				FROM nd_rekam_faktur_pajak_detail
				WHERE rekam_faktur_pajak_id = $id
				AND status = 1
				)t1
			LEFT JOIN nd_penjualan t2
			ON t1.penjualan_id = t2.id 
			LEFT JOIN nd_customer t3
			ON t2.customer_id = t3.id
			LEFT JOIN nd_rekam_faktur_pajak_email t4
			ON t1.rekam_faktur_pajak_id = t4.rekam_faktur_pajak_id
			AND t2.customer_id = t4.customer_id
			$cond
 			GROUP BY customer_id
			ORDER BY t3.nama
			", false);

		return $query->result();	
	}

	function get_rekam_faktur_pajak_filter_email($id){
		$query = $this->db->query("SELECT *
			FROM (
				SELECT concat(if(tipe_company != '' OR tipe_company != null,concat(tipe_company,' '),''),nama) as nama, ifnull(email,'') as email, npwp, nik, group_concat(no_faktur_pajak SEPARATOR '??') as no_faktur_pajak, t2.customer_id, t4.updated_at as waktu_kirim, t4.id as kirim_id , group_concat(t2.tanggal) as tanggal_invoice, status_1,status_2,status_3,status_4,t4.keterangan, email_stat, group_concat(penjualan_id) as penjualan_id
				FROM (
					SELECT *
					FROM nd_rekam_faktur_pajak_detail
					WHERE rekam_faktur_pajak_id = $id
					AND status = 1
					)t1
				LEFT JOIN nd_penjualan t2
				ON t1.penjualan_id = t2.id 
				LEFT JOIN nd_customer t3
				ON t2.customer_id = t3.id
				LEFT JOIN nd_rekam_faktur_pajak_email t4
				ON t1.rekam_faktur_pajak_id = t4.rekam_faktur_pajak_id
				AND t2.customer_id = t4.customer_id
				WHERE email != ''
				GROUP BY customer_id
				ORDER BY t3.nama
			) result
			WHERE kirim_id is null
			OR email_stat = 0
			", false);

		return $query->result();	
	}

//====================================laporan pajak email=============================================

	function get_laporan_pajak($cond){
		$query = $this->db->query("SELECT concat(DATE_FORMAT(tanggal_start,'%M %Y')) as tanggal_faktur, 
			group_concat(tanggal_start order by id asc) as tanggal_start, 
			group_concat(tanggal_end order by id asc) as tanggal_end, 
			group_concat(jumlah_faktur order by id asc) as jumlah_faktur, 
			group_concat(id order by id asc) as faktur_pajak_id, 
			sum(jumlah_faktur) as jumlah_faktur_total, 
			group_concat(ifnull(jml_npwp,0) order by id asc) as jml_npwp, 
			group_concat(ifnull(jml_cust,0) order by id asc) as jml_cust, 
			
			group_concat(ifnull(email,0) order by id asc) as email , 
			group_concat(ifnull(kirim,0) order by id asc) as kirim , 
			group_concat(ifnull(ambil,0)  order by id asc) as ambil, 
			group_concat(ifnull(whatsapp,0) order by id asc) as whatsapp, 
			group_concat(ifnull(others,0) order by id asc) as others,
			
			group_concat(ifnull(jml_email,0) order by id asc) as jml_email , 
			group_concat(ifnull(jml_kirim,0) order by id asc) as jml_kirim , 
			group_concat(ifnull(jml_ambil,0)  order by id asc) as jml_ambil, 
			group_concat(ifnull(jml_wa,0) order by id asc) as jml_wa, 
			group_concat(ifnull(jml_others,0) order by id asc) as jml_others, 
			
			group_concat(created_at order by id asc) as created_at, 
			group_concat(ifnull(locked_date,0) order by id asc) as locked_date, 
			group_concat(ifnull(no_surat,0) order by id asc) as no_surat, 
			group_concat(ifnull(action_id,0) order by id asc) as action_id
			FROM(
				SELECT tA.*, jml_npwp, tC.*, action_id, jml_cust, jml_email, jml_kirim, jml_ambil, jml_wa, jml_others
				FROM (
					SELECT sum(1) as jumlah_faktur, t2.id, t2.created_at, no_surat, MIN(tanggal) as tanggal_start, max(tanggal) as tanggal_end, locked_date
					FROM nd_rekam_faktur_pajak_detail t1
					LEFT JOIN nd_rekam_faktur_pajak t2
					ON t1.rekam_faktur_pajak_id = t2.id
					LEFT JOIN nd_penjualan t3
					ON t1.penjualan_id = t3.id
					$cond
					GROUP BY t2.id
				)tA
				LEFT JOIN (
					SELECT rekam_faktur_pajak_id, sum(1) as jml_cust, sum(jml_npwp) as jml_npwp, sum(ifnull(action_id,0)) as action_id, sum(jml_email) as jml_email, sum(jml_kirim) as jml_kirim, sum(jml_ambil) as jml_ambil, sum(jml_wa) as jml_wa, sum(jml_others) as jml_others
					FROM (
						SELECT tbl_1.*, if(tbl_2.id is null,0,1) as action_id, if(email_stat=1,jml_npwp,0) as jml_email, if(status_1=1,jml_npwp,0) as jml_kirim, if(status_2=1,jml_npwp,0) as jml_ambil, if(status_3=1,jml_npwp,0) as jml_wa, if(status_4=1 ,jml_npwp,0) as jml_others
						FROM (
							SELECT customer_id, rekam_faktur_pajak_id, sum(1) as jml_npwp
							FROM nd_rekam_faktur_pajak_detail t1
							LEFT JOIN nd_penjualan t2
							ON t1.penjualan_id = t2.id
							WHERE penjualan_type_id != 3
							GROUP BY customer_id, rekam_faktur_pajak_id
						)tbl_1
						LEFT JOIN nd_rekam_faktur_pajak_email tbl_2
						ON tbl_1.rekam_faktur_pajak_id = tbl_2.rekam_faktur_pajak_id
						AND tbl_1.customer_id = tbl_2.customer_id
					)t_1
					LEFT JOIN nd_customer t_2
					ON t_1.customer_id = t_2.id
					WHERE npwp != ''
					AND npwp  != 0
					AND jml_npwp > 0
					GROUP BY rekam_faktur_pajak_id
				) tB
				ON tA.id = tB.rekam_faktur_pajak_id
				LEFT JOIN (
					SELECT sum(email_stat) as email, sum(status_1) as kirim, sum(status_2) as ambil, sum(status_3) as whatsapp, sum(status_4) as others, rekam_faktur_pajak_id
					FROM nd_rekam_faktur_pajak_email
					GROUP BY rekam_faktur_pajak_id
				) tC
				ON tA.id = tC.rekam_faktur_pajak_id
				WHERE tB.rekam_faktur_pajak_id is not null
				GROUP BY tA.id
			)result
			GROUP BY MONTH(tanggal_start), YEAR(tanggal_start)
			ORDER BY tanggal_start desc
			", false);

		return $query->result();	
	}

	function get_customer_by_status_surat_pajak($list_id){
		$query = $this->db->query("SELECT tipe_company, nama, group_concat(tanggal SEPARATOR '??') as tanggal, group_concat(no_fp SEPARATOR '??') as no_fp, group_concat(ifnull(email_stat,0)) as status_email, group_concat(ifnull(status_1,0)) as status_kirim, group_concat(ifnull(status_2,0)) as status_ambil, group_concat(ifnull(status_3,0)) as status_wa, group_concat(ifnull(status_4,0)) as status_others, group_concat(rekam_faktur_pajak_id) as rekam_faktur_pajak_id, customer_id
			FROM (
				SELECT tipe_company, group_concat(no_faktur_pajak order by t1.id asc) as no_fp, t4.nama, group_concat(t2.tanggal order by t1.id asc) as tanggal, email_stat, status_1, status_2, status_3, status_4, t1.rekam_faktur_pajak_id, t3.customer_id
				FROM (
					SELECT *
					FROM nd_rekam_faktur_pajak_detail
					WHERE rekam_faktur_pajak_id in ($list_id)
				)t1
				LEFT JOIN nd_penjualan t2
				ON t1.penjualan_id = t2.id
				LEFT JOIN nd_rekam_faktur_pajak_email t3
				ON t1.rekam_faktur_pajak_id = t3.rekam_faktur_pajak_id
				AND t2.customer_id = t3.customer_id
				LEFT JOIN nd_customer t4
				ON t3.customer_id = t4.id
				WHERE t3.id is not null
				GROUP BY t3.id
			)tA
			GROUP BY customer_id
			ORDER BY nama asc
			
			", false);

		return $query->result();
	}

	function get_rekam_faktur_pajak_list_by_id_legacy($id){
		$query = $this->db->query("SELECT tA.*, tB.*, tC.no_faktur_pajak as no_fp_awal, tD.no_faktur_pajak as no_fp_akhir
			FROM (
				SELECT *
				FROM nd_rekam_faktur_pajak
				WHERE id = $id
				) tA
			LEFT JOIN (
				SELECT sum(if(status = 1,1,0)) as jml_faktur, sum(if(status=0,1,0)) as jml_faktur_batal, sum(if(status != 0 , CEIL(g_total/1.1) ,0 )) as g_total_raw, sum(if(status != 0 ,g_total-CEIL(g_total/1.1) ,0 )) as g_total_ppn, rekam_faktur_pajak_id, min(tanggal) as tanggal_start, max(tanggal) as tanggal_end
				FROM (
					SELECT *
					FROM nd_rekam_faktur_pajak_detail
					) t1
				LEFT JOIN (
					SELECT tA.id, g_total, tanggal
					FROM nd_penjualan tA
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
						) tB
					ON tB.penjualan_id = tA.id
					) t2
				ON t1.penjualan_id = t2.id
				GROUP BY rekam_faktur_pajak_id
				)tB
			ON tB.rekam_faktur_pajak_id = tA.id
			LEFT JOIN (
				SELECT rekam_faktur_pajak_id, no_faktur_pajak
				FROM nd_rekam_faktur_pajak_detail t1
				WHERE id in (
					SELECT min(id)
					FROM nd_rekam_faktur_pajak_detail
					GROUP BY rekam_faktur_pajak_id
					)
				) tC
			ON tA.id = tC.rekam_faktur_pajak_id
			LEFT JOIN (
				SELECT rekam_faktur_pajak_id, no_faktur_pajak
				FROM nd_rekam_faktur_pajak_detail t1
				WHERE id in (
					SELECT max(id)
					FROM nd_rekam_faktur_pajak_detail
					GROUP BY rekam_faktur_pajak_id
					)
				) tD
			ON tA.id = tD.rekam_faktur_pajak_id
			", false);

		return $query->result();
	}

	function get_rekam_faktur_pajak_list_by_id($id){
		$query = $this->db->query("SELECT tA.*, tB.*
			FROM (
				SELECT *
				FROM nd_rekam_faktur_pajak
				WHERE id = $id
				) tA
			LEFT JOIN (
				SELECT sum(if(status = 1,1,0)) as jml_faktur, sum(if(status=0,1,0)) as jml_faktur_batal, 
				sum(if(status != 0 , CEIL(g_total/1.1) ,0 )) as g_total_raw, sum(if(status != 0 ,g_total-CEIL(g_total/1.1) ,0 )) as g_total_ppn, 
				rekam_faktur_pajak_id, min(tanggal) as tanggal_start, max(tanggal) as tanggal_end
				FROM (
					SELECT *
					FROM nd_rekam_faktur_pajak_detail
					) t1
				LEFT JOIN (
					SELECT tA.id, g_total, tanggal
					FROM nd_penjualan tA
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
						) tB
					ON tB.penjualan_id = tA.id
					) t2
				ON t1.penjualan_id = t2.id
				GROUP BY rekam_faktur_pajak_id
				)tB
			ON tB.rekam_faktur_pajak_id = tA.id
			", false);

		return $query->result();
	}

	function get_draft_list($rekam_faktur_pajak_id){
		$query = $this->db->query("SELECT t1.*, nd_customer.nama as nama_customer
			FROM (
				SELECT *
				FROM nd_rekam_faktur_pajak_email 
				WHERE rekam_faktur_pajak_id = '$rekam_faktur_pajak_id' 
				AND label_id = 'DRAFT' 
				AND draft_id is not null 
				AND draft_id != '' 
			) t1
			LEFT JOIN nd_customer
			ON t1.customer_id = nd_customer.id
			");
		return $query->result();

	}

}