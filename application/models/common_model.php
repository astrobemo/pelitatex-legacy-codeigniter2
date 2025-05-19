<?php

class Common_Model extends CI_Model
{

	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
		$this->db->query("SET SESSION time_zone = '+7:00'");
	}

	function db_free_query_superadmin($query)
	{
		// $this->db->close();
		// $this->db->initialize();
		$this->db->query("START TRANSACTION");
		$query = $this->db->query("$query");
		$this->db->query("COMMIT");
		if ($this->db->trans_status() === FALSE) {
			$this->db->query("ROLLBACK");
			return false;
		}
		// $this->db->close();
		// $this->db->initialize();
		return $query;
	}

	function db_custom_query($query){
		$query = $this->db->query("$query;");
		// $this->db->free_db_resource();
		return $query;
	}

	function db_select_raw($table)
	{
		$query = $this->db->query("SELECT * 
			FROM $table");
		return $query;
	}

	function db_select($table)
	{
		$query = $this->db->query("SELECT * 
			FROM $table");
		return $query->result();
	}

	function db_select_cond($table, $selector, $selector_value, $cond)
	{
		$query = $this->db->query("SELECT *
			FROM $table
			WHERE $selector = '$selector_value'
			$cond ");
		return $query->result();
	}

	function db_select_num_rows($table)
	{
		$query = $this->db->query("SELECT * 
			FROM $table");
		return $query->num_rows();
	}

	function db_select_array($table, $selector, $array, $order)
	{
		$this->db->select('*');
		$this->db->from($table);
		$this->db->where_in($selector, $array);
		$this->db->order_by($order);
		$query = $this->db->get();
		return $query->result();
	}

	function db_select_array_2($table, $array)
	{
		$this->db->select('*');
		$this->db->from($table);
		$this->db->where($array);
		$query = $this->db->get();
		return $query->result();
		// return $this->db->last_query();
	}

	function db_insert($table, $data)
	{

		$this->db->query("START TRANSACTION");
		$this->db->insert($table, $data);	
		$insert_id = $this->db->insert_id();
		$this->db->query("COMMIT");	
		if ($this->db->trans_status() === FALSE) {
			$this->db->query("ROLLBACK");
			return false;
		}
		return $insert_id;
	}

	function db_insert_batch($table, $data)
	{
		$this->db->query("START TRANSACTION");
		$this->db->insert_batch($table, $data);
		$this->db->query("COMMIT");
		if ($this->db->trans_status() === FALSE) {
			$this->db->query("ROLLBACK");
			return false;
		}
	}

	function db_update($table, $data, $column, $selector)
	{
		$this->db->query("START TRANSACTION");
		$this->db->where($column, $selector);
		$this->db->update($table, $data);
		$this->db->query("COMMIT");
		if ($this->db->trans_status() === FALSE) {
			$this->db->query("ROLLBACK");
			return false;
		}
		return $this->db->last_query();
	}

	//dipakai pada nd_gudang untuk update status_default = 0 (seluruh record)
	function db_update_all($table, $data)
	{
		$this->db->update($table, $data);
		return $this->db->last_query();
	}

	function db_update_batch($table, $data, $param)
	{
		$this->db->query("START TRANSACTION");
		$this->db->update_batch($table, $data, $param);
		$affectedRows = $this->db->affected_rows();
		$this->db->query("COMMIT");
		if ($this->db->trans_status() === FALSE) {
			$this->db->query("ROLLBACK");
			return false;
		}
		// return $this->db->last_query();
		return $affectedRows;
	}

	function db_update_multiple_cond($table, $data, $array)
	{
		$this->db->where($array);
		$this->db->update($table, $data);
		return $this->db->last_query();
	}

	function db_delete($table, $column, $selector)
	{
		$this->db->query("START TRANSACTION");
		$this->db->where($column, $selector);
		$this->db->delete($table);
		$this->db->query("COMMIT");
		if ($this->db->trans_status() === FALSE) {
			$this->db->query("ROLLBACK");
			return false;
		}
	}

	function db_delete_batch($table, $column, $array)
	{
		$this->db->query("START TRANSACTION");
		$this->db->where_in($column, $array);
		$this->db->delete($table);
		$this->db->query("COMMIT");
		if ($this->db->trans_status() === FALSE) {
			$this->db->query("ROLLBACK");
			return false;
		}
		return $this->db->last_query();
	}

	function db_delete_array($table, $array)
	{
		$this->db->where($array);
		$this->db->delete($table);
		return $this->db->last_query();
	}

	function get_barang_beli(){
		$query = $this->db->query("SELECT t1.*, t2.nama_jual
			FROM nd_barang_beli t1
			LEFT JOIN nd_barang t2
			ON t1.barang_id = t2.id
			WHERE t2.status_aktif = 1
				");
		return $query->result();
	}

	function data_customer_by_id($customer_id) {
		$query = $this->db->query("SELECT id, ifnull(npwp,'') as npwp, ifnull(nik,'') as nik, concat(if(tipe_company is not null,concat(tipe_company,' '),''),nama) as nama_customer, 
		concat(alamat,' ',
			concat('BLOK ', if(blok='' or blok is null,'-',blok)),' ',
			concat('NO.',if(no='' or no is null,'-',no)),' ',
			concat('RT:', if(rt='','000',LPAD(rt,3,'0') )),' ',
			concat('RW:', if(rw='','000',LPAD(rw,3,'0') )),' ',
			concat('Kel.',if(kelurahan='' or kelurahan is null,'-', kelurahan) ),' ', 
			concat('Kec.',if(kecamatan='' or kecamatan is null,'-',kecamatan) ),' ', 
			concat('Kota/Kab.',if(kota='' or kota is null ,'-',kota),' ' ),
			if(provinsi='' or provinsi is null,'',concat(provinsi,' ')),
			if(kode_pos='' or kode_pos is null,'00000',kode_pos) 
			) as alamat_lengkap, 
		concat(' ',alamat,' ', if(blok = '-' or blok='' or blok is null,'',blok),' ', 
				if(no='-' or no='' or no is null,'',concat('no ',no) ), 
				if(rt='0' or rt='' ,'000',concat(', RT',LPAD(rt,3,'0')) ),
				if(rw='0' or rw='' ,'000',concat(' RW',LPAD(rw,3,'0'))), 
				if(kelurahan='-' or kelurahan=''  or kelurahan is null,'', concat(', ',kelurahan) ), 
				if(kecamatan='-' or kecamatan='' or kecamatan is null,'',concat(', ',kecamatan) ),
				if(kota='-' or kota=''  or kota is null,'',concat(', ',kota) )
				 ) as alamat
		FROM nd_customer
		WHERE id= $customer_id");
		return $query->result();

	}

	function get_barang_jual_dan_harga_legacy($customer_id, $tipe){
		$query = $this->db->query("SELECT nd_barang.nama_jual, t2.barang_id, nd_barang.id as id, ifnull(t2.harga_berlaku + ifnull(selisih_harga,0)) as harga_jual,
		nd_satuan.nama as nama_satuan, tipe_qty,
        selisih_harga, t1.tipe
		FROM nd_barang
		LEFT JOIN nd_group_harga_berlaku t2
		ON t2.barang_id = nd_barang.id
		LEFT JOIN(
			SELECT *
			FROM nd_customer_harga
			WHERE customer_id = $customer_id
			AND tipe = $tipe
		) t1
		ON t2.group_harga_barang_id = t1.group_harga_barang_id
		LEFT JOIN nd_satuan 
		ON nd_barang.satuan_id = nd_satuan.id
		LEFT JOIN nd_customer_harga_detail t3
		ON t1.customer_id = t3.customer_id
        AND t1.tipe = t3.tipe
		AND t2.barang_id=t3.barang_id
		WHERE t1.id is not null

		");
		return $query->result();

	}

	function get_barang_jual_dan_harga($customer_id, $tipe){
		$query = $this->db->query("SELECT nd_barang.nama_jual, nd_barang.id as id, ifnull(if(harga_berlaku = 0, harga_default, harga_berlaku), harga_jual) as harga_jual,
			nd_satuan.nama as nama_satuan, tipe_qty,
			$tipe as tipe
			FROM nd_barang
			LEFT JOIN (
				SELECT barang_id, sum(ifnull(harga_berlaku,0)) as harga_berlaku, sum(ifnull(harga_default,0)) as harga_default 
				FROM ((
					SELECT t2.barang_id, (t2.harga_berlaku + ifnull(selisih_harga,0)) as harga_berlaku, 0 as harga_default
					FROM nd_group_harga_berlaku t2
					LEFT JOIN(
						SELECT *
						FROM nd_customer_harga
						WHERE customer_id = $customer_id
						AND tipe = $tipe
					) t1
					ON t2.group_harga_barang_id = t1.group_harga_barang_id
					LEFT JOIN nd_customer_harga_detail t3
					ON t1.customer_id = t3.customer_id
					AND t1.tipe = t3.tipe
					AND t2.barang_id=t3.barang_id
					WHERE t1.id is not null
				)UNION(
					SELECT barang_id,  0, harga_berlaku
					FROM (
							SELECT *
							FROM nd_group_harga_barang
							WHERE isDefault = 1
							AND tipe = $tipe
						)tA
					LEFT JOIN nd_group_harga_berlaku tB 
					ON tA.id = tB.group_harga_barang_id
				))res
				GROUP BY barang_id
			)res
			
				ON res.barang_id = nd_barang.id
				LEFT JOIN nd_satuan 
				ON nd_barang.satuan_id = nd_satuan.id

		");
		return $query->result();

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

	function get_warna_asosiasi($barang_id)
	{
		$query = $this->db->query("SELECT t1.*, warna_beli, warna_jual, kode_warna, ifnull(t3.status,1) as status_planner
			FROM (
				(
					SELECT barang_id, warna_id
					FROM nd_penyesuaian_stok
					WHERE barang_id = $barang_id
					GROUP BY barang_id, warna_id
				)UNION(
					SELECT barang_id, warna_id
					FROM nd_penjualan_detail
					WHERE barang_id = $barang_id
					GROUP BY barang_id, warna_id
				)UNION(
					SELECT barang_id, warna_id
					FROM nd_pembelian_detail
					WHERE barang_id = $barang_id
					GROUP BY barang_id, warna_id
				)UNION(
					SELECT barang_id_baru, warna_id
					FROM nd_po_pembelian_warna
					WHERE barang_id_baru = $barang_id
					AND barang_id_baru is not null
					GROUP BY barang_id_baru, warna_id
				)UNION (
					SELECT barang_id, warna_id
					FROM nd_po_pembelian_warna t1
					LEFT JOIN nd_po_pembelian_detail t2
					ON t1.po_pembelian_detail_id = t2.id
					WHERE tipe_barang = 1
					AND t2.barang_id = $barang_id
					GROUP BY barang_id, warna_id
				)
			)t1
			LEFT JOIN nd_warna t2
			ON t1.warna_id = t2.id
			LEFT JOIN (
				SELECT *
				FROM nd_planner_warna_status
				WHERE user_id = '".is_user_id()."'
				)  t3
			ON t1.warna_id = t3.warna_id
			AND t1.barang_id = t3.barang_id
			ORDER BY warna_jual asc
		");
		return $query->result();
	}

	function get_tutup_buku_non_barang($tahun)
	{
		$query = $this->db->query("SELECT t1.barang_id, t1.warna_id
			FROM  nd_barang_warna_temp t1
			LEFT JOIN (
				SELECT id, barang_id, warna_id
				FROM nd_tutup_buku_detail
				WHERE YEAR(tahun) = '$tahun'
				) t2
			ON t1.barang_id = t2.barang_id
			AND t1.warna_id = t2.warna_id
			WHERE t2.id is null
			AND t1.barang_id is not null
			AND t1.warna_id is not null
			
		");
		return $query->result();
	}

	function get_tutup_buku_gudang_non_barang($tahun, $gudang_id)
	{
		$query = $this->db->query("SELECT t1.barang_id, t1.warna_id
			FROM  nd_barang_warna_temp t1
			LEFT JOIN (
				SELECT id, barang_id, warna_id, gudang_id
				FROM nd_tutup_buku_detail_gudang
				WHERE YEAR(tahun) = '$tahun'
				AND gudang_id=$gudang_id
				) t2
			ON t1.barang_id = t2.barang_id
			AND t1.warna_id = t2.warna_id
			WHERE t2.id is null
			AND t1.barang_id is not null
			AND t1.warna_id is not null
			
		");
		return $query->result();
	}

	function get_unfinished_posisi_barang($tanggal)
	{
		$query = $this->db->query("SELECT t1.*, concat('FPJ', DATE_FORMAT(tanggal,'%d%m%y'),'-',LPAD(no_faktur,5,'0')) as no_faktur_lengkap
					FROM (
						SELECT *
						FROM nd_penjualan_posisi_barang
						WHERE status = 1
						AND tanggal_pengambilan <= '$tanggal'
						) t1
					LEFT JOIN nd_penjualan t2
					ON t1.penjualan_id = t2.id
			
		");
		return $query;
	}

	function get_history_harga_customer()
	{
		$query = $this->db->query("SELECT barang_id, warna_id, customer_id, harga_jual, MAX(tanggal) as tanggal, MAX(penjualan_id)
			FROM nd_penjualan_detail t1
			LEFT JOIN (
			    SELECT *
			    FROM nd_penjualan
			    WHERE penjualan_type_id = 2
			    AND YEAR(tanggal) >= '2019'
			) t2
			ON t1.penjualan_id = t2.id
			WHERE customer_id is not null
			AND barang_id != 0
			AND warna_id != 0
			GROUP BY barang_id, warna_id,harga_jual, customer_id
			ORDER BY customer_id, barang_id, warna_id
		");
		return $query->result();
	}

	function get_customer_with_limit()
	{
		$query = $this->db->query("SELECT t1.*, outstanding, sisa_piutang
			FROM nd_customer t1
			LEFT JOIN (
				SELECT outstanding, sum(sisa_piutang) as sisa_piutang, t_1.customer_id
	            FROM (
	                (
	                    SELECT tbl_a.status_aktif,sum(ifnull(g_total,0)) - sum(ifnull(diskon,0)) + sum(ongkos_kirim) - sum(ifnull(total_bayar,0)) - sum(ifnull(amount_bayar,0)) as sisa_piutang, concat_ws('??',tbl_a.id) as data, if(tbl_a.status = -1,-1,0) as status, customer_id, MIN(tanggal) as tanggal_start, MAX(tanggal) as tanggal_end, tbl_a.toko_id, 1 as tipe
	                    FROM (
	                        SELECT *
	                        FROM nd_penjualan 
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
	                        FROM (
	                            SELECT *
	                            FROM nd_pembayaran_piutang_detail
	                            WHERE data_status = 1
	                            ) a
	                        LEFT JOIN (
	                            SELECT *
	                            FROM nd_pembayaran_piutang
	                            WHERE status_aktif = 1
	                            ) b
	                        ON a.pembayaran_piutang_id = b.id
	                        WHERE b.id is not null
	                        GROUP BY penjualan_id
	                        ) as tbl_d
	                    ON tbl_d.penjualan_id = tbl_a.id
	                    LEFT JOIN (
	                        SELECT sum(amount) as amount_bayar, penjualan_id
	                        FROM nd_pembayaran_penjualan
	                        WHERE pembayaran_type_id != 5
	                        GROUP BY penjualan_id
	                    ) tbl_g
	                    ON tbl_a.id = tbl_g.penjualan_id
	                    WHERE ifnull(g_total,0) - ifnull(total_bayar,0) - ifnull(diskon,0) + ongkos_kirim - ifnull(amount_bayar,0) > 0
	                    group by customer_id, toko_id
	                )UNION(
	                    SELECT 1, sum(ifnull(amount,0) - ifnull(total_bayar,0)) as sisa_piutang, concat_ws('??',id,no_faktur) as data, 1, customer_id, MIN(tanggal) as tanggal_start, MAX(tanggal) as tanggal_end, toko_id, 2 as tipe
	                    FROM nd_piutang_awal a
	                    LEFT JOIN (
	                        SELECT penjualan_id, sum(amount) as total_bayar
	                        FROM (
	                            SELECT *
	                            FROM nd_pembayaran_piutang_detail
	                            WHERE data_status = 2
	                            ) a
	                        LEFT JOIN (
	                            SELECT *
	                            FROM nd_pembayaran_piutang
	                            WHERE status_aktif = 1
	                            ) b
	                        ON a.pembayaran_piutang_id = b.id
	                        WHERE b.id is not null
	                        GROUP BY penjualan_id
	                        ) b
	                    ON b.penjualan_id = a.id
	                    GROUP BY customer_id, toko_id
	                )UNION(
	                    SELECT tbl_a.status_aktif,(sum(ifnull(g_total,0)) - sum(ifnull(total_bayar,0)) - sum(ifnull(amount_bayar,0))) *-1 as sisa_piutang, concat_ws('??',tbl_a.id,no_faktur_lengkap) as data, if(tbl_a.status = -1,-1,0) as status, customer_id, MIN(tanggal) as tanggal_start, MAX(tanggal) as tanggal_end, tbl_a.toko_id, 3 as tipe
	                    FROM (
	                        SELECT *, concat('FRJ', DATE_FORMAT(tanggal,'%d%m%y'),'-',LPAD(no_faktur,3,'0')) as no_faktur_lengkap
	                        FROM nd_retur_jual 
	                        WHERE status_aktif = 1
	                        AND retur_type_id != 3
	                        AND no_faktur != ''
	                        ORDER BY tanggal desc
	                        )as tbl_a
	                    LEFT JOIN (
	                        SELECT sum(qty *t1.harga) as g_total, retur_jual_id 
	                        FROM nd_retur_jual_detail t1
	                        LEFT JOIN (
	                            SELECT sum(qty * if(jumlah_roll=0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, retur_jual_detail_id
	                            FROM nd_retur_jual_qty
	                            group by retur_jual_detail_id
	                            ) t2
	                        ON t2.retur_jual_detail_id = t1.id
	                        GROUP BY retur_jual_id
	                        ) as tbl_b
	                    ON tbl_b.retur_jual_id = tbl_a.id
	                    LEFT JOIN (
	                        SELECT penjualan_id, sum(amount) as total_bayar
	                        FROM (
	                            SELECT *
	                            FROM nd_pembayaran_piutang_detail
	                            WHERE data_status = 3
	                            ) a
	                        LEFT JOIN (
	                            SELECT *
	                            FROM nd_pembayaran_piutang
	                            WHERE status_aktif = 1
	                            ) b
	                        ON a.pembayaran_piutang_id = b.id
	                        WHERE b.id is not null
	                        GROUP BY penjualan_id
	                        ) as tbl_d
	                    ON tbl_d.penjualan_id = tbl_a.id
	                    LEFT JOIN (
	                        SELECT sum(amount) as amount_bayar, retur_jual_id
	                        FROM nd_pembayaran_retur
	                        WHERE pembayaran_type_id != 5
	                        GROUP BY retur_jual_id
	                    ) tbl_g
	                    ON tbl_a.id = tbl_g.retur_jual_id
	                    WHERE ifnull(total_bayar,0) - (ifnull(g_total,0) - ifnull(amount_bayar,0)) < 0
	                    group by customer_id, toko_id
	                )
	            ) t_1
	            LEFT JOIN (
	                SELECT customer_id, t2.pembayaran_piutang_id, sum(1)as qty_kontra, sum(ifnull(total_kontra_bon,0)) - sum(ifnull(amount_bayar_kontra,0)) - sum(ifnull(pembulatan,0)) as outstanding
	                FROM (
	                    SELECT *, sum(amount) as total_kontra_bon
	                    FROM nd_pembayaran_piutang_detail
	                    GROUP BY pembayaran_piutang_id
	                    ) t2
	                LEFT JOIN (
	                    SELECT *, sum(amount) as amount_bayar_kontra
	                    FROM nd_pembayaran_piutang_nilai
	                    GROUP BY pembayaran_piutang_id
	                    ) t1
	                ON t1.pembayaran_piutang_id = t2.pembayaran_piutang_id
	                LEFT JOIN nd_pembayaran_piutang t3
	                ON t2.pembayaran_piutang_id = t3.id
	                GROUP BY t3.customer_id
                ) t_4
	            ON t_1.customer_id = t_4.customer_id
	            WHERE sisa_piutang != 0
	            OR outstanding != 0
	            GROUP BY t_1.customer_id 
			)t2
			ON t1.id = t2.customer_id
			ORDER BY nama asc
		");
		return $query->result();
	}

	function get_customer_limit($customer_id){
		$query = $this->db->query("SELECT t1.*, if(limit_amount is not null AND limit_amount > 0,limit_amount,0) - sisa_piutang as sisa_limit, limit_amount
		FROM 
			(
				SELECT outstanding, sum(sisa_piutang) as sisa_piutang, t_1.customer_id
				FROM (
					(
						SELECT tbl_a.status_aktif,sum(ifnull(g_total,0)) - sum(ifnull(diskon,0)) + sum(ongkos_kirim) - sum(ifnull(total_bayar,0)) - sum(ifnull(amount_bayar,0)) as sisa_piutang, concat_ws('??',tbl_a.id) as data, if(tbl_a.status = -1,-1,0) as status, customer_id, MIN(tanggal) as tanggal_start, MAX(tanggal) as tanggal_end, tbl_a.toko_id, 1 as tipe
						FROM (
							SELECT *
							FROM nd_penjualan 
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
							FROM (
								SELECT *
								FROM nd_pembayaran_piutang_detail
								WHERE data_status = 1
								) a
							LEFT JOIN (
								SELECT *
								FROM nd_pembayaran_piutang
								WHERE status_aktif = 1
								) b
							ON a.pembayaran_piutang_id = b.id
							WHERE b.id is not null
							GROUP BY penjualan_id
							) as tbl_d
						ON tbl_d.penjualan_id = tbl_a.id
						LEFT JOIN (
							SELECT sum(amount) as amount_bayar, penjualan_id
							FROM nd_pembayaran_penjualan
							WHERE pembayaran_type_id != 5
							GROUP BY penjualan_id
						) tbl_g
						ON tbl_a.id = tbl_g.penjualan_id
						WHERE ifnull(g_total,0) - ifnull(total_bayar,0) - ifnull(diskon,0) + ongkos_kirim - ifnull(amount_bayar,0) > 0
						group by customer_id, toko_id
					)UNION(
						SELECT 1, sum(ifnull(amount,0) - ifnull(total_bayar,0)) as sisa_piutang, concat_ws('??',id,no_faktur) as data, 1, customer_id, MIN(tanggal) as tanggal_start, MAX(tanggal) as tanggal_end, toko_id, 2 as tipe
						FROM nd_piutang_awal a
						LEFT JOIN (
							SELECT penjualan_id, sum(amount) as total_bayar
							FROM (
								SELECT *
								FROM nd_pembayaran_piutang_detail
								WHERE data_status = 2
								) a
							LEFT JOIN (
								SELECT *
								FROM nd_pembayaran_piutang
								WHERE status_aktif = 1
								) b
							ON a.pembayaran_piutang_id = b.id
							WHERE b.id is not null
							GROUP BY penjualan_id
							) b
						ON b.penjualan_id = a.id
						GROUP BY customer_id, toko_id
					)UNION(
						SELECT tbl_a.status_aktif,(sum(ifnull(g_total,0)) - sum(ifnull(total_bayar,0)) - sum(ifnull(amount_bayar,0))) *-1 as sisa_piutang, concat_ws('??',tbl_a.id,no_faktur_lengkap) as data, if(tbl_a.status = -1,-1,0) as status, customer_id, MIN(tanggal) as tanggal_start, MAX(tanggal) as tanggal_end, tbl_a.toko_id, 3 as tipe
						FROM (
							SELECT *, concat('FRJ', DATE_FORMAT(tanggal,'%d%m%y'),'-',LPAD(no_faktur,3,'0')) as no_faktur_lengkap
							FROM nd_retur_jual 
							WHERE status_aktif = 1
							AND retur_type_id != 3
							AND no_faktur != ''
							ORDER BY tanggal desc
							)as tbl_a
						LEFT JOIN (
							SELECT sum(qty *t1.harga) as g_total, retur_jual_id 
							FROM nd_retur_jual_detail t1
							LEFT JOIN (
								SELECT sum(qty * if(jumlah_roll=0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, retur_jual_detail_id
								FROM nd_retur_jual_qty
								group by retur_jual_detail_id
								) t2
							ON t2.retur_jual_detail_id = t1.id
							GROUP BY retur_jual_id
							) as tbl_b
						ON tbl_b.retur_jual_id = tbl_a.id
						LEFT JOIN (
							SELECT penjualan_id, sum(amount) as total_bayar
							FROM (
								SELECT *
								FROM nd_pembayaran_piutang_detail
								WHERE data_status = 3
								) a
							LEFT JOIN (
								SELECT *
								FROM nd_pembayaran_piutang
								WHERE status_aktif = 1
								) b
							ON a.pembayaran_piutang_id = b.id
							WHERE b.id is not null
							GROUP BY penjualan_id
							) as tbl_d
						ON tbl_d.penjualan_id = tbl_a.id
						LEFT JOIN (
							SELECT sum(amount) as amount_bayar, retur_jual_id
							FROM nd_pembayaran_retur
							WHERE pembayaran_type_id != 5
							GROUP BY retur_jual_id
						) tbl_g
						ON tbl_a.id = tbl_g.retur_jual_id
						WHERE ifnull(total_bayar,0) - (ifnull(g_total,0) - ifnull(amount_bayar,0)) < 0
						group by customer_id, toko_id
					)
				) t_1
				LEFT JOIN (
					SELECT customer_id, t2.pembayaran_piutang_id, sum(1)as qty_kontra, sum(ifnull(total_kontra_bon,0)) - sum(ifnull(amount_bayar_kontra,0)) - sum(ifnull(pembulatan,0)) as outstanding
					FROM (
						SELECT *, sum(amount) as total_kontra_bon
						FROM nd_pembayaran_piutang_detail
						GROUP BY pembayaran_piutang_id
						) t2
					LEFT JOIN (
						SELECT *, sum(amount) as amount_bayar_kontra
						FROM nd_pembayaran_piutang_nilai
						GROUP BY pembayaran_piutang_id
						) t1
					ON t1.pembayaran_piutang_id = t2.pembayaran_piutang_id
					LEFT JOIN nd_pembayaran_piutang t3
					ON t2.pembayaran_piutang_id = t3.id
					GROUP BY t3.customer_id
				) t_4
				ON t_1.customer_id = t_4.customer_id
				WHERE t_1.customer_id = '$customer_id'
				AND (sisa_piutang != 0
				OR outstanding != 0)
			)t1
			LEFT JOIN nd_customer
			ON t1.customer_id = nd_customer.id 
		");
		return $query->result();
	}
	
	function get_po_customer($customer_id, $cond_po){
		$query = $this->db->query("SELECT t1.*, barang_data, nama_barang
			FROM nd_po_penjualan t1
			LEFT JOIN (
				SELECT group_concat(concat(barang_id,',',warna_id) SEPARATOR '??') as barang_data, 
				group_concat(qty SEPARATOR '??') as qty_data, 
				group_concat(concat(nama_jual,' ',warna_jual) SEPARATOR '??') as nama_barang, po_penjualan_id
				FROM nd_po_penjualan_detail t0
				LEFT JOIN nd_barang
				ON t0.barang_id = nd_barang.id
				LEFT JOIN nd_warna
				ON t0.warna_id = nd_warna.id
				GROUP BY po_penjualan_id
			) t2
			ON t1.id = t2.po_penjualan_id
			WHERE customer_id = $customer_id
			AND (status_po = 0 $cond_po)
		");
		return $query->result();
	}

	function get_limit_customer_harian($tanggal){
		$query = $this->db->query("SELECT customer_id as id, nama_customer as nama, sisa_limit, tipe_company, created_at
			FROM nd_warning_limit_belanja_harian
			WHERE tanggal_warning = '$tanggal'
		");
		return $query;
	}

	function get_limit_belanja_warning()
	{
		$query = $this->db->query("SELECT t1.*, outstanding, sisa_piutang, if(limit_warning_type is not null,limit_amount,0) - sisa_piutang as sisa_limit
			FROM nd_customer t1
			LEFT JOIN (
				SELECT outstanding, sum(sisa_piutang) as sisa_piutang, t_1.customer_id
	            FROM (
	                (
	                    SELECT tbl_a.status_aktif,sum(ifnull(g_total,0)) - sum(ifnull(diskon,0)) + sum(ongkos_kirim) - sum(ifnull(total_bayar,0)) - sum(ifnull(amount_bayar,0)) as sisa_piutang, concat_ws('??',tbl_a.id) as data, if(tbl_a.status = -1,-1,0) as status, customer_id, MIN(tanggal) as tanggal_start, MAX(tanggal) as tanggal_end, tbl_a.toko_id, 1 as tipe
	                    FROM (
	                        SELECT *
	                        FROM nd_penjualan 
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
	                        FROM (
	                            SELECT *
	                            FROM nd_pembayaran_piutang_detail
	                            WHERE data_status = 1
	                            ) a
	                        LEFT JOIN (
	                            SELECT *
	                            FROM nd_pembayaran_piutang
	                            WHERE status_aktif = 1
	                            ) b
	                        ON a.pembayaran_piutang_id = b.id
	                        WHERE b.id is not null
	                        GROUP BY penjualan_id
	                        ) as tbl_d
	                    ON tbl_d.penjualan_id = tbl_a.id
	                    LEFT JOIN (
	                        SELECT sum(amount) as amount_bayar, penjualan_id
	                        FROM nd_pembayaran_penjualan
	                        WHERE pembayaran_type_id != 5
	                        GROUP BY penjualan_id
	                    ) tbl_g
	                    ON tbl_a.id = tbl_g.penjualan_id
	                    WHERE ifnull(g_total,0) - ifnull(total_bayar,0) - ifnull(diskon,0) + ongkos_kirim - ifnull(amount_bayar,0) > 0
	                    group by customer_id, toko_id
	                )UNION(
	                    SELECT 1, sum(ifnull(amount,0) - ifnull(total_bayar,0)) as sisa_piutang, concat_ws('??',id,no_faktur) as data, 1, customer_id, MIN(tanggal) as tanggal_start, MAX(tanggal) as tanggal_end, toko_id, 2 as tipe
	                    FROM nd_piutang_awal a
	                    LEFT JOIN (
	                        SELECT penjualan_id, sum(amount) as total_bayar
	                        FROM (
	                            SELECT *
	                            FROM nd_pembayaran_piutang_detail
	                            WHERE data_status = 2
	                            ) a
	                        LEFT JOIN (
	                            SELECT *
	                            FROM nd_pembayaran_piutang
	                            WHERE status_aktif = 1
	                            ) b
	                        ON a.pembayaran_piutang_id = b.id
	                        WHERE b.id is not null
	                        GROUP BY penjualan_id
	                        ) b
	                    ON b.penjualan_id = a.id
	                    GROUP BY customer_id, toko_id
	                )UNION(
	                    SELECT tbl_a.status_aktif,(sum(ifnull(g_total,0)) - sum(ifnull(total_bayar,0)) - sum(ifnull(amount_bayar,0))) *-1 as sisa_piutang, concat_ws('??',tbl_a.id,no_faktur_lengkap) as data, if(tbl_a.status = -1,-1,0) as status, customer_id, MIN(tanggal) as tanggal_start, MAX(tanggal) as tanggal_end, tbl_a.toko_id, 3 as tipe
	                    FROM (
	                        SELECT *, concat('FRJ', DATE_FORMAT(tanggal,'%d%m%y'),'-',LPAD(no_faktur,3,'0')) as no_faktur_lengkap
	                        FROM nd_retur_jual 
	                        WHERE status_aktif = 1
	                        AND retur_type_id != 3
	                        AND no_faktur != ''
	                        ORDER BY tanggal desc
	                        )as tbl_a
	                    LEFT JOIN (
	                        SELECT sum(qty *t1.harga) as g_total, retur_jual_id 
	                        FROM nd_retur_jual_detail t1
	                        LEFT JOIN (
	                            SELECT sum(qty * if(jumlah_roll=0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, retur_jual_detail_id
	                            FROM nd_retur_jual_qty
	                            group by retur_jual_detail_id
	                            ) t2
	                        ON t2.retur_jual_detail_id = t1.id
	                        GROUP BY retur_jual_id
	                        ) as tbl_b
	                    ON tbl_b.retur_jual_id = tbl_a.id
	                    LEFT JOIN (
	                        SELECT penjualan_id, sum(amount) as total_bayar
	                        FROM (
	                            SELECT *
	                            FROM nd_pembayaran_piutang_detail
	                            WHERE data_status = 3
	                            ) a
	                        LEFT JOIN (
	                            SELECT *
	                            FROM nd_pembayaran_piutang
	                            WHERE status_aktif = 1
	                            ) b
	                        ON a.pembayaran_piutang_id = b.id
	                        WHERE b.id is not null
	                        GROUP BY penjualan_id
	                        ) as tbl_d
	                    ON tbl_d.penjualan_id = tbl_a.id
	                    LEFT JOIN (
	                        SELECT sum(amount) as amount_bayar, retur_jual_id
	                        FROM nd_pembayaran_retur
	                        WHERE pembayaran_type_id != 5
	                        GROUP BY retur_jual_id
	                    ) tbl_g
	                    ON tbl_a.id = tbl_g.retur_jual_id
	                    WHERE ifnull(total_bayar,0) - (ifnull(g_total,0) - ifnull(amount_bayar,0)) < 0
	                    group by customer_id, toko_id
	                )
	            ) t_1
	            LEFT JOIN (
	                SELECT customer_id, t2.pembayaran_piutang_id, sum(1)as qty_kontra, sum(ifnull(total_kontra_bon,0)) - sum(ifnull(amount_bayar_kontra,0)) - sum(ifnull(pembulatan,0)) as outstanding
	                FROM (
	                    SELECT *, sum(amount) as total_kontra_bon
	                    FROM nd_pembayaran_piutang_detail
	                    GROUP BY pembayaran_piutang_id
	                    ) t2
	                LEFT JOIN (
	                    SELECT *, sum(amount) as amount_bayar_kontra
	                    FROM nd_pembayaran_piutang_nilai
	                    GROUP BY pembayaran_piutang_id
	                    ) t1
	                ON t1.pembayaran_piutang_id = t2.pembayaran_piutang_id
	                LEFT JOIN nd_pembayaran_piutang t3
	                ON t2.pembayaran_piutang_id = t3.id
	                GROUP BY t3.customer_id
                ) t_4
	            ON t_1.customer_id = t_4.customer_id
	            WHERE sisa_piutang != 0
	            OR outstanding != 0
	            GROUP BY t_1.customer_id 
			)t2
			ON t1.id = t2.customer_id
			WHERE limit_warning_amount is not null
			AND limit_warning_amount != 0
			AND sisa_piutang - if(limit_warning_type is not null,if(limit_warning_type = 1,limit_amount * limit_warning_amount/100, limit_warning_amount  ),0) > 0
		");
		return $query;
	}

	function is_customer_limit($customer_id)
	{
		$query = $this->db->query("SELECT outstanding, sum(sisa_piutang) as sisa_piutang, 
		t_1.customer_id, limit_amount, limit_warning_amount, limit_warning_type
		FROM (
			(
				SELECT tbl_a.status_aktif,sum(ifnull(g_total,0)) - sum(ifnull(diskon,0)) + sum(ongkos_kirim) - sum(ifnull(total_bayar,0)) - sum(ifnull(amount_bayar,0)) as sisa_piutang, concat_ws('??',tbl_a.id) as data, if(tbl_a.status = -1,-1,0) as status, customer_id, MIN(tanggal) as tanggal_start, MAX(tanggal) as tanggal_end, tbl_a.toko_id, 1 as tipe
				FROM (
					SELECT *
					FROM nd_penjualan 
					WHERE status_aktif = 1
					AND penjualan_type_id != 3
					AND no_faktur != ''
					AND customer_id=$customer_id
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
					FROM (
						SELECT *
						FROM nd_pembayaran_piutang_detail
						WHERE data_status = 1
						) a
					LEFT JOIN (
						SELECT *
						FROM nd_pembayaran_piutang
						WHERE status_aktif = 1
						) b
					ON a.pembayaran_piutang_id = b.id
					WHERE b.id is not null
					GROUP BY penjualan_id
					) as tbl_d
				ON tbl_d.penjualan_id = tbl_a.id
				LEFT JOIN (
					SELECT sum(amount) as amount_bayar, penjualan_id
					FROM nd_pembayaran_penjualan
					WHERE pembayaran_type_id != 5
					GROUP BY penjualan_id
				) tbl_g
				ON tbl_a.id = tbl_g.penjualan_id
				WHERE ifnull(g_total,0) - ifnull(total_bayar,0) - ifnull(diskon,0) + ongkos_kirim - ifnull(amount_bayar,0) > 0
				group by customer_id, toko_id
			)UNION(
				SELECT 1, sum(ifnull(amount,0) - ifnull(total_bayar,0)) as sisa_piutang, concat_ws('??',id,no_faktur) as data, 1, customer_id, MIN(tanggal) as tanggal_start, MAX(tanggal) as tanggal_end, toko_id, 2 as tipe
				FROM nd_piutang_awal a
				LEFT JOIN (
					SELECT penjualan_id, sum(amount) as total_bayar
					FROM (
						SELECT *
						FROM nd_pembayaran_piutang_detail
						WHERE data_status = 2
						) a
					LEFT JOIN (
						SELECT *
						FROM nd_pembayaran_piutang
						WHERE status_aktif = 1
						) b
					ON a.pembayaran_piutang_id = b.id
					WHERE b.id is not null
					GROUP BY penjualan_id
					) b
				ON b.penjualan_id = a.id
				WHERE customer_id=$customer_id
				GROUP BY customer_id, toko_id
			)UNION(
				SELECT tbl_a.status_aktif,(sum(ifnull(g_total,0)) - sum(ifnull(total_bayar,0)) - sum(ifnull(amount_bayar,0))) *-1 as sisa_piutang, concat_ws('??',tbl_a.id,no_faktur_lengkap) as data, if(tbl_a.status = -1,-1,0) as status, customer_id, MIN(tanggal) as tanggal_start, MAX(tanggal) as tanggal_end, tbl_a.toko_id, 3 as tipe
				FROM (
					SELECT *, concat('FRJ', DATE_FORMAT(tanggal,'%d%m%y'),'-',LPAD(no_faktur,3,'0')) as no_faktur_lengkap
					FROM nd_retur_jual 
					WHERE status_aktif = 1
					AND retur_type_id != 3
					AND no_faktur != ''
					AND customer_id=$customer_id
					ORDER BY tanggal desc
					)as tbl_a
				LEFT JOIN (
					SELECT sum(qty *t1.harga) as g_total, retur_jual_id 
					FROM nd_retur_jual_detail t1
					LEFT JOIN (
						SELECT sum(qty * if(jumlah_roll=0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, retur_jual_detail_id
						FROM nd_retur_jual_qty
						group by retur_jual_detail_id
						) t2
					ON t2.retur_jual_detail_id = t1.id
					GROUP BY retur_jual_id
					) as tbl_b
				ON tbl_b.retur_jual_id = tbl_a.id
				LEFT JOIN (
					SELECT penjualan_id, sum(amount) as total_bayar
					FROM (
						SELECT *
						FROM nd_pembayaran_piutang_detail
						WHERE data_status = 3
						) a
					LEFT JOIN (
						SELECT *
						FROM nd_pembayaran_piutang
						WHERE status_aktif = 1
						) b
					ON a.pembayaran_piutang_id = b.id
					WHERE b.id is not null
					GROUP BY penjualan_id
					) as tbl_d
				ON tbl_d.penjualan_id = tbl_a.id
				LEFT JOIN (
					SELECT sum(amount) as amount_bayar, retur_jual_id
					FROM nd_pembayaran_retur
					WHERE pembayaran_type_id != 5
					GROUP BY retur_jual_id
				) tbl_g
				ON tbl_a.id = tbl_g.retur_jual_id
				WHERE ifnull(total_bayar,0) - (ifnull(g_total,0) - ifnull(amount_bayar,0)) < 0
				group by customer_id, toko_id
			)
		) t_1
		LEFT JOIN (
			SELECT customer_id, t2.pembayaran_piutang_id, sum(1)as qty_kontra, sum(ifnull(total_kontra_bon,0)) - sum(ifnull(amount_bayar_kontra,0)) - sum(ifnull(pembulatan,0)) as outstanding
			FROM (
				SELECT *, sum(amount) as total_kontra_bon
				FROM nd_pembayaran_piutang_detail
				GROUP BY pembayaran_piutang_id
				) t2
			LEFT JOIN (
				SELECT *, sum(amount) as amount_bayar_kontra
				FROM nd_pembayaran_piutang_nilai
				GROUP BY pembayaran_piutang_id
				) t1
			ON t1.pembayaran_piutang_id = t2.pembayaran_piutang_id
			LEFT JOIN nd_pembayaran_piutang t3
			ON t2.pembayaran_piutang_id = t3.id
			GROUP BY t3.customer_id
		) t_4
		ON t_1.customer_id = t_4.customer_id
		LEFT JOIN nd_customer
		ON t_1.customer_id = nd_customer.id
		WHERE sisa_piutang != 0
		OR outstanding != 0
		GROUP BY t_1.customer_id 
		");
		return $query->result();
	}


	// ==============================customer limit / tempo=========================================

	function cek_customer_lewat_tempo_kredit($customer_id, $limit, $tempo)
	{
		$query = $this->db->query("SELECT t1.*,
			concat('FPJ', DATE_FORMAT(tanggal,'%d%m%y'),'-',LPAD(no_faktur,5,'0')) as no_faktur_lengkap,
			DATE_FORMAT(DATE_ADD(tanggal, INTERVAL $tempo DAY),'%d/%m/%y') as jatuh_tempo, g_total as amount
			FROM (
				SELECT *
				FROM nd_penjualan
				WHERE customer_id = $customer_id
				AND penjualan_type_id = 2
				AND status_aktif = 1
				AND no_faktur != ''
				AND no_faktur is not null
				AND tanggal < NOW() - INTERVAL $limit DAY
				)t1
			LEFT JOIN (
				SELECT sum(qty *tA.harga_jual) as g_total, penjualan_id 
                FROM nd_penjualan_detail tA
                LEFT JOIN (
                    SELECT sum(qty * if(jumlah_roll=0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, penjualan_detail_id
                    FROM nd_penjualan_qty_detail
                    group by penjualan_detail_id
                    ) tB
                ON tB.penjualan_detail_id = tA.id
                GROUP BY penjualan_id
				) t2
			ON t1.id = t2.penjualan_id
			LEFT JOIN (
				SELECT sum(amount) as bayar_langsung, penjualan_id
				FROM nd_pembayaran_penjualan
				WHERE pembayaran_type_id != 5
				GROUP BY penjualan_id
				) t3
			ON t1.id = t3.penjualan_id
			LEFT JOIN (
				SELECT sum(amount) as amount_bayar_hutang, penjualan_id
				FROM (
					SELECT *
					FROM nd_pembayaran_piutang_detail
					WHERE data_status = 1
					) t_1
				LEFT JOIN (
					SELECT tA.id
					FROM (
						SELECT *
						FROM nd_pembayaran_piutang
						WHERE customer_id = $customer_id
						AND status_aktif = 1
						) tA
					LEFT JOIN (
						SELECT sum(amount) as amount_invoice, pembayaran_piutang_id 
						FROM nd_pembayaran_piutang_detail
						GROUP BY pembayaran_piutang_id
						) tB
					ON tA.id = tB.pembayaran_piutang_id
					LEFT JOIN (
						SELECT sum(amount) as amount_pelunasan, pembayaran_piutang_id
						FROM nd_pembayaran_piutang_nilai
						GROUP BY pembayaran_piutang_id
						) tC
					ON tA.id = tC.pembayaran_piutang_id
					WHERE (amount_invoice - ifnull(amount_pelunasan,0) - ifnull(pembulatan,0)) <= 0
					)t_2
				ON t_1.pembayaran_piutang_id = t_2.id
				WHERE t_2.id is not null
				GROUP BY penjualan_id
				) t4
			ON t1.id = t4.penjualan_id
			WHERE g_total - ifnull(bayar_langsung,0) - ifnull(amount_bayar_hutang,0) > 0
			");
		return $query;
	}

	function cek_all_customer_lewat_tempo_kredit()
	{
		$query = $this->db->query("SELECT nama,tipe_company, 
			group_concat(DATE_FORMAT(DATE_ADD(tanggal, INTERVAL tempo_kredit DAY),'%d/%m/%y')) as jatuh_tempo, 
			group_concat(amount) as amount_data, sum(amount) as amount, 
			group_concat(tanggal) as tanggal, 
			group_concat(ifnull(tempo_kredit,0) + ifnull(warning_kredit,0)) as intvl, group_concat(no_faktur_lengkap) as no_faktur, customer_id
			FROM (
				SELECT t1.*, g_total as amount
				FROM (
					SELECT *,concat('FPJ', DATE_FORMAT(tanggal,'%d%m%y'),'-',LPAD(no_faktur,5,'0')) as no_faktur_lengkap
					FROM nd_penjualan
					WHERE penjualan_type_id = 2
					AND status_aktif = 1
					AND no_faktur != ''
					)t1
				LEFT JOIN (
					SELECT sum(qty *tA.harga_jual) as g_total, penjualan_id 
	                FROM nd_penjualan_detail tA
	                LEFT JOIN (
	                    SELECT sum(qty * if(jumlah_roll=0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, penjualan_detail_id
	                    FROM nd_penjualan_qty_detail
	                    group by penjualan_detail_id
	                    ) tB
	                ON tB.penjualan_detail_id = tA.id
	                GROUP BY penjualan_id
					) t2
				ON t1.id = t2.penjualan_id
				LEFT JOIN (
					SELECT sum(amount) as bayar_langsung, penjualan_id
					FROM nd_pembayaran_penjualan
					WHERE pembayaran_type_id != 5
					GROUP BY penjualan_id
					) t3
				ON t1.id = t3.penjualan_id
				LEFT JOIN (
					SELECT sum(amount) as amount_bayar_hutang, penjualan_id
					FROM (
						SELECT *
						FROM nd_pembayaran_piutang_detail
						WHERE data_status = 1
						) t_1
					LEFT JOIN (
						SELECT tA.id
						FROM (
							SELECT *
							FROM nd_pembayaran_piutang
							WHERE status_aktif = 1
							) tA
						LEFT JOIN (
							SELECT sum(amount) as amount_invoice, pembayaran_piutang_id 
							FROM nd_pembayaran_piutang_detail
							GROUP BY pembayaran_piutang_id
							) tB
						ON tA.id = tB.pembayaran_piutang_id
						LEFT JOIN (
							SELECT sum(amount) as amount_pelunasan, pembayaran_piutang_id
							FROM nd_pembayaran_piutang_nilai
							GROUP BY pembayaran_piutang_id
							) tC
						ON tA.id = tC.pembayaran_piutang_id
						WHERE (amount_invoice - ifnull(amount_pelunasan,0) - ifnull(pembulatan,0)) <= 0
						)t_2
					ON t_1.pembayaran_piutang_id = t_2.id
					WHERE t_2.id is not null
					GROUP BY penjualan_id
					) t4
				ON t1.id = t4.penjualan_id
				WHERE g_total - ifnull(bayar_langsung,0) - ifnull(amount_bayar_hutang,0) > 0
			)t1
			LEFT JOIN nd_customer t2
			ON t1.customer_id = t2.id
			WHERE tanggal < NOW() - INTERVAL (ifnull(tempo_kredit,0) + ifnull(warning_kredit,0)) DAY
			AND ifnull(tempo_kredit,0) + ifnull(warning_kredit,0) > 0
			GROUP BY customer_id
			");
		return $query;
	}
// ==============================faktur related/others=========================================

	function get_all_po_pembelian()
	{
		$query = $this->db->query("SELECT t1.id, po_pembelian_id, concat(if(t2.tanggal >= '2019-09-27', concat(ifnull(pre_po,''),t4.kode,'/',DATE_FORMAT(t2.tanggal,'%y'),'/',LPAD(po_number,3,'0')) ,concat(LPAD(po_number,3,'0'),'/',DATE_FORMAT(t2.tanggal,'%m'),'/',DATE_FORMAT(t2.tanggal,'%y'))),'-',batch,if(revisi > 1,concat('R',revisi-1),'')) as po_number, revisi, supplier_id
			FROM (
				SELECT *
				FROM nd_po_pembelian_batch
				WHERE status != 0
				) t1
			LEFT JOIN nd_po_pembelian t2
			ON t1.po_pembelian_id = t2.id
			LEFT JOIN nd_toko t3
			ON t2.toko_id = t3.id
			LEFT JOIN nd_supplier t4
			ON t2.supplier_id = t4.id
			ORDER BY t1.id
		");
		return $query->result();
	}

	function get_po_pembelian_batch_by_supplier($supplier_id)
	{
		$query = $this->db->query("SELECT t1.id, po_pembelian_id, concat(if(t2.tanggal >= '2019-09-27', concat(ifnull(pre_po,''),t4.kode,'/',DATE_FORMAT(t2.tanggal,'%y'),'/',LPAD(po_number,3,'0')) ,concat(LPAD(po_number,3,'0'),'/',DATE_FORMAT(t2.tanggal,'%m'),'/',DATE_FORMAT(t2.tanggal,'%y'))),'-',batch,if(revisi > 1,concat('R',revisi-1),'')) as po_number, revisi, supplier_id
			FROM (
				SELECT *
				FROM nd_po_pembelian_batch
				WHERE status != 0
				) t1
			LEFT JOIN nd_po_pembelian t2
			ON t1.po_pembelian_id = t2.id
			LEFT JOIN nd_toko t3
			ON t2.toko_id = t3.id
			LEFT JOIN nd_supplier t4
			ON t2.supplier_id = t4.id
			WHERE supplier_id = $supplier_id
			ORDER BY t1.id
		");
		return $query->result();
	}


	function get_next_faktur($no_faktur)
	{
		$query = $this->db->query("SELECT id, concat('FPJ', DATE_FORMAT(tanggal,'%d%m%y'),'-',LPAD(no_faktur,5,'0')) as no_faktur_lengkap
			FROM nd_penjualan
			WHERE no_faktur > $no_faktur
			AND status_aktif = 1
			ORDER BY no_faktur asc
			LIMIT 1
		");
		return $query->result();
	}

	function get_po_pembelian_batch_aktif()
	{
		$query = $this->db->query("SELECT t1.id, concat(if(t2.tanggal >= '2019-09-27', concat(ifnull(pre_po,''),t4.kode,'/',DATE_FORMAT(t2.tanggal,'%y'),'/',LPAD(po_number,3,'0')) ,concat(LPAD(po_number,3,'0'),'/',DATE_FORMAT(t2.tanggal,'%m'),'/',DATE_FORMAT(t2.tanggal,'%y'))),'-',batch,if(revisi > 1,concat('R',revisi-1),'')) as po_number, 
			revisi, supplier_id
			FROM (
				SELECT *
				FROM nd_po_pembelian_batch
				WHERE status != 0
			) t1
			LEFT JOIN nd_po_pembelian t2
			ON t1.po_pembelian_id = t2.id
			LEFT JOIN nd_toko t3
			ON t2.toko_id = t3.id
			LEFT JOIN nd_supplier t4
			ON t2.supplier_id = t4.id
			WHERE t2.status_aktif != 0
			AND t2.id is not null
		");
		return $query->result();
	}

	function get_po_pembelian_batch_by_cond($cond)
	{
		$query = $this->db->query("SELECT t1.id, supplier_id, po_pembelian_id,concat(pre_po,LPAD(po_number,3,'0'),'/',DATE_FORMAT(t2.tanggal,'%m'),'/',DATE_FORMAT(t2.tanggal,'%y')) as po_number
			FROM nd_po_pembelian_batch t1
			LEFT JOIN nd_po_pembelian t2
			ON t1.po_pembelian_id = t2.id
			LEFT JOIN nd_toko t3
			ON t2.toko_id = t3.id
			$cond
		");
		return $query->result();
	}

	// TRUNCATE nd_giro_urutan
	function db_bersihkan_tabel($table)
	{
		$query = $this->db->query("TRUNCATE $table");
		return $query->result();
	}

	//ambil urutan giro terakhir 
	function get_last_urutan_giro($tahun)
	{
		$query = $this->db->query("SELECT *
				FROM (
				    (
				        SELECT id, pembayaran_type_id, tanggal_transfer as tanggal, urutan_giro, nama_bank, no_rek_bank, no_giro, jatuh_tempo, amount, 1 as tipe
				        FROM nd_pembayaran_piutang_nilai
				        WHERE YEAR(tanggal_transfer) = '$tahun'
				        AND pembayaran_type_id = 2
				        AND urutan_giro is not null
				    )UNION(
				        SELECT id, pembayaran_type_id, tanggal, urutan_giro, nama_bank, no_rek_bank, no_giro, jatuh_tempo, amount, 2
				        FROM nd_dp_masuk
				        WHERE YEAR(tanggal) >= '$tahun'
				        AND pembayaran_type_id = 6
				        AND urutan_giro is not null
				    )
				)result
				ORDER BY urutan_giro desc
				LIMIT 1");

		return $query->result();
	}

	function predictive_urutan_giro($tahun)
	{
		$query = $this->db->query("SELECT *
				FROM (
				    (
				        SELECT id, pembayaran_type_id, tanggal_transfer as tanggal, urutan_giro, nama_bank, no_rek_bank, no_giro, jatuh_tempo, amount, 1 as tipe
				        FROM nd_pembayaran_piutang_nilai
				        WHERE YEAR(tanggal_transfer) = '$tahun'
				        AND pembayaran_type_id = 2
				        AND urutan_giro is not null
				    )UNION(
				        SELECT id, pembayaran_type_id, tanggal, urutan_giro, nama_bank, no_rek_bank, no_giro, jatuh_tempo, amount, 2
				        FROM nd_dp_masuk
				        WHERE YEAR(tanggal) >= '$tahun'
				        AND pembayaran_type_id = 6
				        AND urutan_giro is not null
				    )
				)result
				ORDER BY urutan_giro desc
				LIMIT 1
			");

		return $query->result();
	}

	function get_giro_list_by_year($tahun)
	{
		$query = $this->db->query("SELECT *
				FROM (
				    (
				        SELECT id, pembayaran_type_id, tanggal_transfer as tanggal, urutan_giro, nama_bank, no_rek_bank, no_giro, jatuh_tempo, amount, 1 as tipe
				        FROM nd_pembayaran_piutang_nilai
				        WHERE YEAR(tanggal_transfer) = '$tahun'
				        AND pembayaran_type_id = 2
				    )UNION(
				        SELECT id, pembayaran_type_id, tanggal, urutan_giro, nama_bank, no_rek_bank, no_giro, jatuh_tempo, amount, 2
				        FROM nd_dp_masuk
				        WHERE YEAR(tanggal) >= '$tahun'
				        AND pembayaran_type_id = 6
				    )
				)result
				ORDER BY tanggal asc
			");

		return $query->result();
	}

	function get_giro_by_year($tahun)
	{
		$query = $this->db->query("SELECT tA.id, pembayaran_type_id , tB.nama as nama_customer, tanggal, urutan_giro, nama_bank, no_rek_bank, no_giro, jatuh_tempo, amount, tipe, pembayaran_piutang_id, customer_id
				FROM (
				    (
				        SELECT t1.id, pembayaran_type_id, tanggal_transfer as tanggal, urutan_giro, nama_bank, no_rek_bank, no_giro, jatuh_tempo, amount, 1 as tipe, customer_id, pembayaran_piutang_id
				        FROM nd_pembayaran_piutang_nilai t1
				        LEFT JOIN nd_pembayaran_piutang t2
				        ON t1.pembayaran_piutang_id = t2.id
				        WHERE YEAR(tanggal_transfer) = '$tahun'
				        AND pembayaran_type_id = 2
				    )UNION(
				        SELECT id, pembayaran_type_id, tanggal, urutan_giro, nama_bank, no_rek_bank, no_giro, jatuh_tempo, amount, 2, customer_id, ''
				        FROM nd_dp_masuk
				        WHERE YEAR(tanggal) >= '$tahun'
				        AND pembayaran_type_id = 6
				    )
				)tA
				LEFT JOIN nd_customer tB
				ON tA.customer_id = tB.id
				ORDER BY tanggal asc");
	}

	function get_unfinished_invoice($tgl)
	{
		$today = date("Y-m-d");
		$weekago = date('Y-m-d', strtotime($tgl,strtotime('-7 days')));
		$query = $this->db->query("SELECT t1.*, if(penjualan_type_id = 3, nama_keterangan, t3.nama) as nama_show
			FROM (
				SELECT *
				FROM nd_penjualan
				WHERE created_at >='$tgl'
				AND tanggal <= '$today'
				AND status_aktif = 1
				AND no_faktur is null
			)t1
			-- LEFT JOIN (
			-- 	SELECT penjualan_id, sum(1) as jml
			-- 	FROM nd_penjualan_detail
			-- 	GROUP BY penjualan_id
			-- 	) t2
			-- ON t2.penjualan_id = t1.id
			LEFT JOIN nd_customer t3
			ON t1.customer_id = t3.id
			");

		return $query->result();
	}

	//================================================================================================

	function get_user_list()
	{
		$query = $this->db->query("SELECT nd_user.id, username, time_start, time_end, posisi_id, 
			tA.name as posisi_name, nd_user.status_aktif
			FROM nd_user
			LEFT JOIN pelita_menu.nd_posisi tA
			ON nd_user.posisi_id = tA.id
			");

		return $query->result();
	}

	function get_barang_list()
	{
		$query = $this->db->query("SELECT tbl_a.*, tbl_b.nama as nama_satuan
			FROM nd_barang as tbl_a
			LEFT JOIN nd_satuan as tbl_b
			ON tbl_a.satuan_id = tbl_b.id
			ORDER By tbl_a.nama
			");

		return $query->result();
	}

	function get_barang_list_ajax($aColumns, $sWhere/*, $sOrder*/, $sLimit)
	{
		// $this->db->_protect_identifiers = false;

		$query = $this->db->query("SELECT *
			FROM (
				SELECT tbl_a.status_aktif, jenis_barang, nama_beli as nama, nama_jual, tbl_b.nama as nama_satuan, 
				harga_jual, harga_beli, concat_ws('??',tbl_a.id, satuan_id, ifnull(barang_beli_id,''), ifnull(status_aktif_beli,'') ) as status_barang
				FROM nd_barang as tbl_a
				LEFT JOIN nd_satuan as tbl_b
				ON tbl_a.satuan_id = tbl_b.id
				LEFT JOIN ( 
					SELECT group_concat(nama ORDER BY status_aktif desc,nama asc SEPARATOR '??'  ) as nama_beli, barang_id, group_concat(id ORDER BY status_aktif desc,nama asc) as barang_beli_id, group_concat(status_aktif  ORDER BY status_aktif desc,nama asc) as status_aktif_beli
					FROM nd_barang_beli
					GROUP BY barang_id
				) tbl_c
				ON tbl_a.id = tbl_c.barang_id
				ORDER BY tbl_a.nama asc
				) A
			$sWhere
            $sLimit
			", false);
		// $sOrder

		return $query;
	}

	function get_barang_list_aktif()
	{
		$query = $this->db->query("SELECT tbl_a.*, tbl_b.nama as nama_satuan
				FROM (
					SELECT *
					FROM nd_barang
					where status_aktif = 1
					) as tbl_a
				LEFT JOIN nd_satuan as tbl_b
				ON tbl_a.satuan_id = tbl_b.id
				ORDER BY tbl_a.nama_jual asc
			", false);

		return $query->result();
	}

	function get_barang_list_aktif_beli()
	{
		// $query = $this->db->query("SELECT tbl_a.*, tbl_b.nama as nama_satuan
		// 		FROM (
		// 			SELECT *
		// 			FROM nd_barang
		// 			where status_aktif = 1
		// 			) as tbl_a
		// 		LEFT JOIN nd_satuan as tbl_b
		// 		ON tbl_a.satuan_id = tbl_b.id
		// 		ORDER BY tbl_a.nama asc
		// 	", false);

		$query = $this->db->query("SELECT tA.*, tC.nama as nama_satuan, harga_beli
				FROM (
					SELECT *
					FROM nd_barang_beli
					where status_aktif = 1
					) tA
				LEFT JOIN nd_barang tB
				ON tA.barang_id = tB.id
				LEFT JOIN nd_satuan tC
				ON tB.satuan_id = tC.id
				ORDER BY tA.nama asc
			", false);

		return $query->result();
	}

	function get_barang_list_aktif_extra_po($supplier_id)
	{
		$query = $this->db->query("SELECT tbl_a.*, tbl_b.nama as nama_satuan, nama_tercetak
				FROM (
					SELECT *
					FROM nd_barang
					where status_aktif = 1
					) as tbl_a
				LEFT JOIN nd_satuan as tbl_b
				ON tbl_a.satuan_id = tbl_b.id
				LEFT JOIN (
					SELECT m1.barang_id, m1.nama_tercetak
					FROM nd_po_pembelian_detail m1 
					LEFT JOIN nd_po_pembelian_detail m2
					ON (m1.barang_id = m2.barang_id AND m1.id < m2.id)
					WHERE m2.id IS NULL
					)tC
				ON tbl_a.id = tC.barang_id
				ORDER BY tbl_a.nama asc
			", false);

		return $query->result();
	}

	function get_customer_list_ajax($aColumns, $sWhere, $sOrder, $sLimit)
	{
		$query = $this->db->query("SELECT *
			FROM (
				SELECT cust.*, CONCAT(ifnull(registered_date,'-'), '??',ifnull(source_type,'-'),'??', ifnull(source_detail,'')) as source_data
				FROM (
					SELECT nama, alias, id,
							if(customer_type_id = 1,'NON-KREDIT','KREDIT') as customer_type,
								customer_type_id, concat_ws('?',alamat,blok,no,LPAD(rt,3,'0'),LPAD(rw,3,'0'),kecamatan, kelurahan) alamat, 
								concat(ifnull(kota,''),'??',ifnull(provinsi,'')) as kota , telepon1, telepon2, npwp, nik, status_aktif,
								concat(tempo_kredit,'/',warning_kredit) as tempo_kredit, 
								concat_ws('-?-',
									ifnull(kode_pos,''),
									ifnull(email,''),
									ifnull(npwp,'0'),
									status_aktif,id, customer_type_id, ifnull(nik,''),'', ifnull(tipe_company,''),
									ifnull(medsos_link, ''), ifnull(npwp_link, ''), ifnull(ktp_link, ''), 1, ifnull(contact_person, '')
									) as other_data, 
								concat_ws(',',ifnull(limit_amount,'-'), 
									ifnull(limit_atas,'-'),
									ifnull(limit_warning_type,'-'), 
									ifnull(limit_warning_amount,''), '-'
									) as limit_data
					FROM nd_customer t1
				)cust
				LEFT JOIN (
					SELECT customer_id, registered_date, source_detail, source_type 
					FROM nd_customer_source
				) ncs
				ON cust.id = ncs.customer_id 
			) A
			$sWhere
            $sOrder
            $sLimit
			", false);

		return $query;
	}

//=======================================customer profile======================================

	function get_customer_profile_pembelian_barang_terbanyak($customer_id, $year)
	{
		$query = $this->db->query("SELECT concat_ws(' ',tbl_c.nama,tbl_d.warna_beli ) as barang, sum(qty) as qty 
			FROM (
				SELECT *
				FROM nd_penjualan
				WHERE YEAR(tanggal) = '$year'
				AND customer_id = $customer_id
				AND status_aktif = 1
				) as tbl_a
			LEFT JOIN (
				SELECT qty, penjualan_id, barang_id, warna_iddb_insert_batch 
				FROM nd_penjualan_detail
				LEFT JOIN (
					SELECT sum(qty*if(jumlah_roll=0,1,jumlah_roll)) as qty, penjualan_detail_id
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
			");
		return $query->result();
	}

	function get_customer_profile_pembelanjaan_tahun($customer_id, $date_start, $date_end)
	{
		$query = $this->db->query("SELECT MONTHNAME(tanggal) as tanggal, sum(amount)/1000 as amount
			FROM (
				SELECT *
				FROM nd_penjualan
				where status_aktif = 1
				AND customer_id = $customer_id
				and tanggal >= '$date_start'
				AND tanggal <= '$date_end'
				) as tbl_a
			LEFT JOIN (
				SELECT sum(harga_jual*qty) as amount, penjualan_id
				FROM nd_penjualan_detail
				LEFT JOIN (
					SELECT sum(qty*if(jumlah_roll=0,1,jumlah_roll)) as qty, penjualan_detail_id
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

	function get_customer_profile_piutang($customer_id)
	{
		$query = $this->db->query("SELECT tbl_a.status_aktif, ifnull(tbl_c.nama,'no name') as nama_customer, 
		sum((ifnull(g_total,0) - ifnull(diskon,0)) + ongkos_kirim) - ifnull(total_bayar,0) - sum(ifnull(amount_bayar_jual,0)) - ifnull(bayar_piutang,0) as sisa_piutang, 
		concat_ws('??',tbl_a.id,no_faktur) as data, if(tbl_a.status = -1,-1,0) as status, customer_id, 
		MIN(tanggal) as tanggal_start, MAX(tanggal) as tanggal_end
				FROM (
					SELECT *, concat('FPJ', DATE_FORMAT(tanggal,'%d%m%y'),'-',LPAD(no_faktur,5,'0')) as no_faktur_lengkap
					FROM nd_penjualan 
					WHERE status_aktif = 1
					AND customer_id = $customer_id
					ORDER BY tanggal desc
					)as tbl_a
				LEFT JOIN (
					SELECT sum(qty *nd_penjualan_detail.harga_jual) as g_total, penjualan_id 
					FROM nd_penjualan_detail
					LEFT JOIN (
						SELECT sum(qty*if(jumlah_roll=0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, penjualan_detail_id
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
					FROM nd_pembayaran_piutang_detail
					GROUP BY penjualan_id
					) as tbl_d
				ON tbl_d.penjualan_id = tbl_a.id
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
				LEFT JOIN (
					SELECT sum(amount) as amount_bayar_jual, penjualan_id
					FROM nd_pembayaran_penjualan
					WHERE pembayaran_type_id != 5
					GROUP BY penjualan_id
					) tbl_g
				ON tbl_a.id = tbl_g.penjualan_id
				WHERE ifnull(total_bayar,0) + ifnull(amount_bayar_jual,0) + ifnull(bayar_piutang,0) - (ifnull(g_total,0) - ifnull(diskon,0)) + ongkos_kirim < 0
				group by customer_id
			", false);

		return $query->result();
	}

	function get_data_penjualan($customer_id)
	{
		$query = $this->db->query("SELECT tbl_a.status_aktif, no_faktur_lengkap as no_faktur, tanggal, tbl_e.text as penjualan_type_id, ifnull(g_total,0) as g_total , ifnull(diskon,0) as diskon, ifnull(ongkos_kirim,0) as ongkos_kirim, if(penjualan_type_id = 3,if(nama_keterangan = '','no_name', nama_keterangan), tbl_c.nama) as nama_customer, (ifnull(total_bayar,0) - (ifnull(g_total,0) - ifnull(diskon,0)) + ongkos_kirim) as keterangan, concat_ws('??',tbl_a.id,no_faktur) as data, if(tbl_a.status = -1,-1,0) as status,tbl_e.text as tipe_penjualan, tbl_a.id as penjualan_id
				FROM (
					SELECT *, concat('FPJ', DATE_FORMAT(tanggal,'%d%m%y'),'-',LPAD(no_faktur,5,'0')) as no_faktur_lengkap
					FROM nd_penjualan 
					WHERE customer_id = $customer_id
					ORDER BY tanggal desc
					LIMIT 0,30
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
				LEFT JOIN nd_customer as tbl_c
				ON tbl_a.customer_id = tbl_c.id
				LEFT JOIN (
					SELECT penjualan_id, sum(amount) as total_bayar
					FROM nd_pembayaran_penjualan
					GROUP BY penjualan_id
					) as tbl_d
				ON tbl_d.penjualan_id = tbl_a.id
				LEFT JOIN nd_penjualan_type tbl_e
				ON tbl_a.penjualan_type_id = tbl_e.id
			", false);

		return $query->result();
	}

	function get_dp_by_customer($customer_id)
	{
		$query = $this->db->query("SELECT tbl_a.id, nama, status_aktif , ifnull(dp_masuk,0) - ifnull(dp_keluar,0) as saldo
			FROM (
				SELECT *
				FROM nd_customer
				WHERE id = $customer_id
				) as tbl_a
			LEFT JOIN (
				SELECT sum(ifnull(amount,0)) as dp_masuk, customer_id
				FROM nd_dp_masuk
				group by customer_id
				) as tbl_b
			ON tbl_a.id = tbl_b.customer_id
			LEFT JOIN (
				SELECT sum(ifnull(amount,0)) as dp_keluar, customer_id
				FROM (
					SELECT amount, penjualan_id 
					FROM nd_pembayaran_penjualan
					WHERE pembayaran_type_id = 1
					) as nd_pembayaran_penjualan
				LEFT JOIN nd_penjualan
				ON nd_pembayaran_penjualan.penjualan_id = nd_penjualan.id
				group by customer_id
				) as tbl_c
			ON tbl_c.customer_id = tbl_a.id
		");
		return $query->result();
	}

	function get_penjualan_report($cond, $cond_tanggal, $customer_id, $limit)
	{
		$query = $this->db->query("SELECT tbl_a.id, no_faktur as nf, tbl_a.status_aktif, no_faktur_lengkap as no_faktur, tanggal, qty, jumlah_roll, nama_barang, harga_jual, total, diskon, ongkos_kirim, if(customer_id is not null, tbl_c.nama, concat(nama_keterangan, ' (non-pelanggan)')) as nama_customer, (ifnull(total_bayar,0) + ifnull(bayar_piutang,0) - (ifnull(g_total,0) - ifnull(diskon,0)) + ifnull(ongkos_kirim,0)) as keterangan, tbl_a.id as data , jatuh_tempo, pembayaran_type_id, data_bayar
				FROM (
					SELECT *, concat('FPJ', DATE_FORMAT(tanggal,'%d%m%y'),'-',LPAD(no_faktur,5,'0')) as no_faktur_lengkap
					FROM nd_penjualan 
					WHERE status_aktif = 1
					AND customer_id = $customer_id
					$cond_tanggal
					ORDER BY tanggal desc
					$limit
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
					GROUP BY penjualan_id
					) as tbl_d
				ON tbl_d.penjualan_id = tbl_a.id
				LEFT JOIN (
					SELECT SUM(amount) as bayar_piutang, penjualan_id
					FROM nd_pembayaran_piutang_detail t1
					LEFT JOIN (
						SELECT * 
						FROM nd_penjualan
						WHERE status_aktif = 1
					) t2
					ON t1.penjualan_id = t2.id
					WHERE t2.id is not null
					GROUP BY penjualan_id
				) as tbl_f
				ON tbl_a.id = tbl_f.penjualan_id
				$cond
				ORDER BY tanggal desc, nf desc

			", false);

		return $query->result();
	}


	//=======================================cek harga barang===========================================

	function cek_harga_penjualan_barang($barang_id, $cond, $limit)
	{
		$query = $this->db->query("SELECT *, DATE_FORMAT(tgl,'%d-%b-%Y') as tanggal
			FROM (
				(
					SELECT penjualan_id, tanggal as tgl, no_faktur_lengkap as no_faktur, if(penjualan_type_id = 3,nama_keterangan,t3.nama) as nama_customer, 1 as tipe, harga_jual
					FROM (
						SELECT harga_jual, barang_id, penjualan_id
						FROM nd_penjualan_detail
						WHERE barang_id = $barang_id
						)t1
					LEFT JOIN (
						SELECT *,concat('FPJ', tanggal,'-',LPAD(no_faktur,5,'0')) as no_faktur_lengkap
						FROM nd_penjualan
						$cond
						) t2
					ON t1.penjualan_id = t2.id
					LEFT JOIN nd_customer t3
					ON t2.customer_id = t3.id
					WHERE t2.id is not null
					GROUP BY penjualan_id
					ORDER BY tanggal desc
				)
			)result
			LIMIT $limit
		");
		return $query->result();
	}

	//=======================================note_order===========================================

	function get_note_order()
	{
		$query = $this->db->query("SELECT *
			FROM nd_note_order");
		return $query;
	}

	function get_note_order_reminder()
	{
		$reminder = date('Y-m-d H:i');
		$user_id = is_user_id();
		$query = $this->db->query("SELECT *
			FROM nd_note_order");
		return $query;
		// return $this->db->last_query();
	}

	function get_note_order_target()
	{
		$query = $this->db->query("SELECT *
			FROM nd_note_order");
		return $query;
	}

	function get_note_order_pending()
	{
		$query = $this->db->query("SELECT *
			FROM nd_note_order");
		return $query;
	}

//=================================notifikasi akunting======================================================

	function get_notifikasi_akunting()
	{
		$query = $this->db->query("SELECT t1.*, t2.nama as nama_customer
			FROM nd_notifikasi_akunting t1
			LEFT JOIN nd_customer t2
			ON t1.customer_id = t2.id
			WHERE read_by is null
		");

		return $query;
	}



	function get_piutang_warn()
	{
		$today = date('Y-m-d');
		$query = $this->db->query("SELECT customer_id, t2.nama as nama_customer, t3.nama as nama_toko, sum(sisa_piutang) as sisa_piutang, 
		MIN(tanggal_start) as tanggal_start, MAX(tanggal_end) as tanggal_end, 
		toko_id, sum(counter_invoice) as counter_invoice
			FROM (
				(
					SELECT tbl_a.status_aktif,sum(ifnull(g_total,0)) - sum(ifnull(diskon,0)) + sum(ongkos_kirim) - sum(ifnull(total_bayar,0)) - sum(ifnull(amount_bayar,0)) as sisa_piutang, concat_ws('??',tbl_a.id,no_faktur_lengkap) as data, if(tbl_a.status = -1,-1,0) as status, customer_id, MIN(tanggal) as tanggal_start, MAX(tanggal) as tanggal_end, tbl_a.toko_id, sum(1) as counter_invoice
					FROM (
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
					LEFT JOIN (
						SELECT *, concat('FPJ', DATE_FORMAT(tanggal,'%d%m%y'),'-',LPAD(no_faktur,5,'0')) as no_faktur_lengkap, if(jatuh_tempo = tanggal, DATE_ADD(jatuh_tempo, INTERVAL 60 DAY), jatuh_tempo ) as new_jatuh_tempo
						FROM nd_penjualan 
						WHERE status_aktif = 1
						AND penjualan_type_id != 3
						AND no_faktur != ''
						ORDER BY tanggal desc
						)as tbl_a
					ON tbl_b.penjualan_id = tbl_a.id
					LEFT JOIN (
						SELECT penjualan_id, sum(amount) as total_bayar
						FROM (
							SELECT *
							FROM nd_pembayaran_piutang_detail
							WHERE data_status = 1
							) a
						LEFT JOIN (
							SELECT *
							FROM nd_pembayaran_piutang
							WHERE status_aktif = 1
							) b
						ON a.pembayaran_piutang_id = b.id
						WHERE b.id is not null
						GROUP BY penjualan_id
						) as tbl_d
					ON tbl_d.penjualan_id = tbl_a.id
					LEFT JOIN (
						SELECT sum(amount) as amount_bayar, penjualan_id
						FROM nd_pembayaran_penjualan
						WHERE pembayaran_type_id != 5
						GROUP BY penjualan_id
					) tbl_g
					ON tbl_a.id = tbl_g.penjualan_id
					WHERE ifnull(total_bayar,0) + ifnull(amount_bayar,0) - ifnull(g_total,0) - ifnull(diskon,0) - ifnull(ongkos_kirim,0)  < 0
					AND new_jatuh_tempo <= '$today'
					AND tbl_a.id is not null
					group by customer_id, toko_id
				)UNION(
					SELECT 1, sum(ifnull(amount,0) - ifnull(total_bayar,0)) as sisa_piutang, concat_ws('??',id,no_faktur) as data, 1, customer_id, MIN(tanggal) as tanggal_start, MAX(tanggal) as tanggal_end, 1 as toko_id, sum(1) as counter_invoice
					FROM nd_piutang_awal a
					LEFT JOIN (
						SELECT penjualan_id, sum(amount) as total_bayar
						FROM (
							SELECT *
							FROM nd_pembayaran_piutang_detail
							WHERE data_status = 2
							) a
						LEFT JOIN (
							SELECT *
							FROM nd_pembayaran_piutang
							WHERE status_aktif = 1
							) b
						ON a.pembayaran_piutang_id = b.id
						WHERE b.id is not null
						GROUP BY penjualan_id
						) b
					ON b.penjualan_id = a.id
					GROUP BY customer_id, toko_id
				)
			) t1
			LEFT JOIN nd_customer as t2
			ON t1.customer_id = t2.id
			LEFT JOIN nd_toko t3
			ON t1.toko_id = t3.id
			WHERE sisa_piutang > 0
			GROUP BY customer_id
			ORDER BY t2.nama asc", false);


		return $query;
	}


//=======================================barang profile======================================
	function get_penjualan_report_limit_by_barang($barang_id, $tahun)
	{
		$this->db->simple_query('SET SESSION group_concat_max_len=15000');

		$query = $this->db->query("SELECT sum(qty) as qty, group_concat(qty) as qty_data, 
		sum(jumlah_roll) as jumlah_roll, group_concat(jumlah_roll) as jumlah_roll_data, bulan_jual, 
		sum(penjualan) as penjualan, group_concat(penjualan) as penjualan_data, 
		group_concat(warna_id) as warna_id, group_concat(count_trx_data SEPARATOR '??') as count_trx_data,  
		group_concat(count_cust_trx_data SEPARATOR '??') as count_cust_trx_data, 
		group_concat(count_cust) as count_cust, sum(count_trx) as count_trx, sum(count_cust) as count_cust, 
		group_concat(count_cust) as count_cust_data, group_concat(penjualan_id SEPARATOR '=?=') as penjualan_id
			FROM (
				SELECT bulan_jual, sum(qty) as qty, sum(jumlah_roll) as jumlah_roll, sum(penjualan) as penjualan, warna_id, sum(count_trx) as count_trx, group_concat(count_trx) as count_trx_data, group_concat(count_cust_trx) as count_cust_trx_data, sum(1) as count_cust, group_concat(penjualan_id SEPARATOR '??') as penjualan_id
				FROM (
					SELECT DATE_FORMAT(t2.tanggal,'%Y-%m') as bulan_jual, sum(qty) as qty, sum(jumlah_roll) as jumlah_roll, sum(qty*harga_jual) as penjualan, warna_id, sum(1) as count_trx, sum(1) as count_cust_trx, group_concat(penjualan_id) as penjualan_id
					FROM (
						SELECT *
						FROM nd_penjualan_detail
						WHERE barang_id = $barang_id
						)t1
					LEFT JOIN (
						SELECT *
						FROM nd_penjualan
						WHERE YEAR(tanggal) = '$tahun'
						AND status_aktif = 1
						) t2
					ON t1.penjualan_id = t2.id
					LEFT JOIN (
						SELECT sum(qty*if(jumlah_roll=0,1, jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, penjualan_detail_id
						FROM nd_penjualan_qty_detail
						GROUP by penjualan_detail_id
						) t3
					ON t1.id = t3.penjualan_detail_id
					WHERE t2.id is not null
					GROUP BY warna_id, MONTH(tanggal), customer_id
				)result
				GROUP BY warna_id, bulan_jual
			)result
			GROUP BY bulan_jual
			ORDER BY bulan_jual asc
		");
		return $query->result();
	}

	function get_penjualan_for_forecast($barang_id, $tanggal_start, $tanggal_end)
	{
		$this->db->simple_query('SET SESSION group_concat_max_len=15000');

		$query = $this->db->query("SELECT group_concat(warna_id) as warna_id, sum(qty) as qty, 
		group_concat(qty) as qty_data, 
		sum(jumlah_roll) as jumlah_roll, 
		group_concat(jumlah_roll) as jumlah_roll_data, bulan_jual, 
		group_concat(qty_history) as qty_history, 
		group_concat(history_created) as history_created, 
		group_concat(keterangan SEPARATOR '=?=') as keterangan, 
		group_concat(history_id) as history_id
			FROM (
				SELECT tA.barang_id, tA.warna_id, bulan_jual, ifnull(qty_now,sum(qty)) as qty, sum(jumlah_roll) as jumlah_roll, concat(if(qty_now is not null,concat(sum(qty),'??'),''),ifnull(qty_history,0)) as qty_history, ifnull(history_created,'-' )as history_created, ifnull(if(keterangan = '','-',keterangan),'-') as keterangan, ifnull(history_id,'-') as history_id
				FROM (
					SELECT barang_id, warna_id, bulan_jual, sum(qty) as qty, sum(jumlah_roll) as jumlah_roll
					FROM (
						(
							SELECT barang_id, warna_id, DATE_FORMAT(t2.tanggal,'%Y-%m') as bulan_jual, sum(qty) as qty, sum(jumlah_roll) as jumlah_roll
							FROM (
								SELECT barang_id, warna_id, harga_jual, penjualan_id, id
								FROM nd_penjualan_detail
								WHERE barang_id = $barang_id
								)t1
							LEFT JOIN (
								SELECT *
								FROM nd_penjualan
								WHERE tanggal >= '$tanggal_start'
								AND tanggal <= '$tanggal_end'
								AND status_aktif = 1
								) t2
							ON t1.penjualan_id = t2.id
							LEFT JOIN (
								SELECT sum(qty*if(jumlah_roll=0,1, jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, penjualan_detail_id
								FROM nd_penjualan_qty_detail
								GROUP by penjualan_detail_id
								) t3
							ON t1.id = t3.penjualan_detail_id
							WHERE t2.id is not null
							GROUP BY warna_id, YEAR(tanggal), MONTH(tanggal)
						)UNION(
							SELECT barang_id, warna_id, DATE_FORMAT(period,'%Y-%m') as bulan_jual, 0,0
							FROM nd_barang_forecasting_data
							WHERE barang_id = $barang_id
							AND period >= '$tanggal_start'
							AND period <= '$tanggal_end'
							GROUP BY barang_id, warna_id, period
						)
					)result
					GROUP by barang_id, warna_id, bulan_jual
				)tA
				LEFT JOIN (
					SELECT barang_id, warna_id, DATE_FORMAT(period,'%Y-%m') as period , qty as qty_now
					FROM nd_barang_forecasting_data
					WHERE id IN (
						SELECT max(id)
						FROM nd_barang_forecasting_data
						WHERE barang_id = $barang_id
						AND period >= '$tanggal_start'
						AND period <= '$tanggal_end'
						GROUP BY barang_id, warna_id, YEAR(period), MONTH(period)
						)
					) tB
				ON tA.barang_id = tB.barang_id
				AND tA.warna_id = tB.warna_id
				AND tA.bulan_jual = tB.period
				LEFT JOIN (
					SELECT barang_id, warna_id, DATE_FORMAT(period,'%Y-%m') as period , group_concat(qty order by id desc SEPARATOR '??') as qty_history, group_concat(created_at order by id desc SEPARATOR '??') as history_created, group_concat(id order by id desc SEPARATOR '??') as history_id
					FROM nd_barang_forecasting_data
					WHERE barang_id = $barang_id
					AND period >= '$tanggal_start'
					AND period <= '$tanggal_end'
					GROUP BY barang_id, warna_id, YEAR(period), MONTH(period)
					) tC
				ON tA.barang_id = tC.barang_id
				AND tA.warna_id = tC.warna_id	
				AND tA.bulan_jual = tC.period
				LEFT JOIN (
					SELECT barang_id, warna_id, DATE_FORMAT(period,'%Y-%m') as period , keterangan
					FROM nd_barang_forecasting_keterangan
					) tD
				ON tA.barang_id = tD.barang_id
				AND tA.warna_id = tD.warna_id
				AND tA.bulan_jual = tD.period
				GROUP BY tA.warna_id, bulan_jual
			)result
			GROUP BY bulan_jual
			ORDER BY bulan_jual asc
		");
		return $query->result();
	}

	function get_penjualan_report_by_barang_warna($barang_id, $tahun)
	{
		$query = $this->db->query("SELECT sum(qty) as qty, sum(jumlah_roll) as jumlah_roll, sum(qty*harga_jual) as penjualan, warna_id, warna_jual as nama_warna
				FROM (
					SELECT *
					FROM nd_penjualan_detail
					WHERE barang_id = $barang_id
					)t1
				LEFT JOIN (
					SELECT *
					FROM nd_penjualan
					WHERE YEAR(tanggal) = '$tahun'
					AND status_aktif = 1
					) t2
				ON t1.penjualan_id = t2.id
				LEFT JOIN (
					SELECT sum(qty*if(jumlah_roll=0,1, jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, penjualan_detail_id
					FROM nd_penjualan_qty_detail
					GROUP by penjualan_detail_id
					) t3
				ON t1.id = t3.penjualan_detail_id
				LEFT JOIN nd_warna t4
				ON t1.warna_id = t4.id
				WHERE t2.id is not null
				GROUP BY warna_id
				ORDER BY penjualan desc
		");
		return $query->result();
	}

	function get_penjualan_report_by_barang_customer($barang_id, $tahun)
	{
		$query = $this->db->query("SELECT sum(qty) as qty, sum(jumlah_roll) as jumlah_roll, sum(qty*harga_jual) as penjualan, warna_id, if(customer_id = 0, 'non customer', t4.nama) as nama_customer
				FROM (
					SELECT *
					FROM nd_penjualan_detail
					WHERE barang_id = $barang_id
					)t1
				LEFT JOIN (
					SELECT id, tanggal, if(penjualan_type_id =3, 0,customer_id) as customer_id, penjualan_type_id, nama_keterangan
					FROM nd_penjualan
					WHERE YEAR(tanggal) = '$tahun'
					AND status_aktif = 1
					) t2
				ON t1.penjualan_id = t2.id
				LEFT JOIN (
					SELECT sum(qty*if(jumlah_roll=0,1, jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, penjualan_detail_id
					FROM nd_penjualan_qty_detail
					GROUP by penjualan_detail_id
					) t3
				ON t1.id = t3.penjualan_detail_id
				LEFT JOIN nd_customer t4
				ON t2.customer_id = t4.id
				WHERE t2.id is not null
				GROUP BY customer_id
				ORDER BY penjualan desc
		");
		return $query->result();
	}

//=====================================warna profile===========================
	function get_barang_asosiasi($warna_id)
	{
		$query = $this->db->query("SELECT t1.*, nama, nama_jual
			FROM (
				(
					SELECT barang_id, warna_id
					FROM nd_penyesuaian_stok
					WHERE warna_id = $warna_id
					GROUP BY barang_id, warna_id
				)UNION(
					SELECT barang_id, warna_id
					FROM nd_penjualan_detail
					WHERE warna_id = $warna_id
					GROUP BY barang_id, warna_id
				)UNION(
					SELECT barang_id, warna_id
					FROM nd_pembelian_detail
					WHERE warna_id = $warna_id
					GROUP BY barang_id, warna_id
				)
			)t1
			LEFT JOIN nd_barang t2
			ON t1.barang_id = t2.id
			ORDER BY nama_jual asc
		");
		return $query->result();
	}

	function get_penjualan_report_limit_by_warna($warna_id, $tahun)
	{
		$this->db->simple_query('SET SESSION group_concat_max_len=15000');

		$query = $this->db->query("SELECT sum(qty) as qty, group_concat(qty) as qty_data, sum(jumlah_roll) as jumlah_roll, group_concat(jumlah_roll) as jumlah_roll_data, bulan_jual, sum(penjualan) as penjualan, group_concat(penjualan) as penjualan_data, group_concat(barang_id) as barang_id, group_concat(count_trx_data SEPARATOR '??') as count_trx_data,  group_concat(count_cust_trx_data SEPARATOR '??') as count_cust_trx_data, group_concat(count_cust) as count_cust, sum(count_trx) as count_trx, sum(count_cust) as count_cust, group_concat(count_cust) as count_cust_data, group_concat(penjualan_id SEPARATOR '=?=') as penjualan_id
			FROM (
				SELECT bulan_jual, sum(qty) as qty, sum(jumlah_roll) as jumlah_roll, sum(penjualan) as penjualan, warna_id, sum(count_trx) as count_trx, group_concat(count_trx) as count_trx_data, group_concat(count_cust_trx) as count_cust_trx_data, sum(1) as count_cust, group_concat(penjualan_id SEPARATOR '??') as penjualan_id, barang_id
				FROM (
					SELECT MONTH(t2.tanggal) as bulan_jual, sum(qty) as qty, sum(jumlah_roll) as jumlah_roll, sum(qty*harga_jual) as penjualan, warna_id, sum(1) as count_trx, sum(1) as count_cust_trx, group_concat(penjualan_id) as penjualan_id, barang_id
					FROM (
						SELECT *
						FROM nd_penjualan_detail
						WHERE warna_id = $warna_id
						)t1
					LEFT JOIN (
						SELECT *
						FROM nd_penjualan
						WHERE YEAR(tanggal) = '$tahun'
						AND status_aktif = 1
						) t2
					ON t1.penjualan_id = t2.id
					LEFT JOIN (
						SELECT sum(qty*if(jumlah_roll=0,1, jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, penjualan_detail_id
						FROM nd_penjualan_qty_detail
						GROUP by penjualan_detail_id
						) t3
					ON t1.id = t3.penjualan_detail_id
					WHERE t2.id is not null
					GROUP BY barang_id, MONTH(tanggal), customer_id
				)result
				GROUP BY barang_id, bulan_jual
			)result
			GROUP BY bulan_jual
			ORDER BY bulan_jual asc
		");
		return $query->result();
	}

	function get_penjualan_report_by_warna_barang($warna_id, $tahun)
	{
		$query = $this->db->query("SELECT sum(qty) as qty, sum(jumlah_roll) as jumlah_roll, sum(qty*t1.harga_jual) as penjualan, barang_id, nama_jual as nama_barang
				FROM (
					SELECT *
					FROM nd_penjualan_detail
					WHERE warna_id = $warna_id
					)t1
				LEFT JOIN (
					SELECT *
					FROM nd_penjualan
					WHERE YEAR(tanggal) = '$tahun'
					AND status_aktif = 1
					) t2
				ON t1.penjualan_id = t2.id
				LEFT JOIN (
					SELECT sum(qty*if(jumlah_roll=0,1, jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, penjualan_detail_id
					FROM nd_penjualan_qty_detail
					GROUP by penjualan_detail_id
					) t3
				ON t1.id = t3.penjualan_detail_id
				LEFT JOIN nd_barang t4
				ON t1.barang_id = t4.id
				WHERE t2.id is not null
				GROUP BY barang_id
				ORDER BY penjualan desc
		");
		return $query->result();
	}

	function get_penjualan_report_by_warna_customer($warna_id, $tahun)
	{
		$query = $this->db->query("SELECT sum(qty) as qty, sum(jumlah_roll) as jumlah_roll, sum(qty*harga_jual) as penjualan, warna_id, if(customer_id = 0, 'non customer', t4.nama) as nama_customer
				FROM (
					SELECT *
					FROM nd_penjualan_detail
					WHERE warna_id = $warna_id
					)t1
				LEFT JOIN (
					SELECT id, tanggal, if(penjualan_type_id =3, 0,customer_id) as customer_id, penjualan_type_id, nama_keterangan
					FROM nd_penjualan
					WHERE YEAR(tanggal) = '$tahun'
					AND status_aktif = 1
					) t2
				ON t1.penjualan_id = t2.id
				LEFT JOIN (
					SELECT sum(qty*if(jumlah_roll=0,1, jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, penjualan_detail_id
					FROM nd_penjualan_qty_detail
					GROUP by penjualan_detail_id
					) t3
				ON t1.id = t3.penjualan_detail_id
				LEFT JOIN nd_customer t4
				ON t2.customer_id = t4.id
				WHERE t2.id is not null
				GROUP BY customer_id
				ORDER BY penjualan desc
		");
		return $query->result();
	}

//=======================================pembelian=====================================================================

	function get_data_pembelian_by_date($barang_id, $tanggal_start, $tanggal_end)
	{
		$query = $this->db->query("SELECT barang_id, warna_id, sum(qty) as qty, sum(jumlah_roll) as jumlah_roll, DATE_FORMAT(tanggal,'%Y-%m') as bulan_jual
				FROM (
					SELECT *
					FROM nd_pembelian_detail
					WHERE barang_id = $barang_id
					)t1
				LEFT JOIN (
					SELECT *
					FROM nd_pembelian
					WHERE tanggal >= '$tanggal_start'
					AND tanggal >= '$tanggal_end'
					AND status_aktif = 1
					) t2
				ON t1.pembelian_id = t2.id
				WHERE t2.id is not null
				GROUP BY barang_id, warna_id, YEAR(tanggal), MONTH(tanggal)
		");
		return $query->result();
	}

	function get_outstanding_barang($barang_id, $tanggal)
	{
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

	function get_penjualan_bulan_berjalan($barang_id, $tanggal_start, $tanggal_end)
	{
		$this->db->simple_query('SET SESSION group_concat_max_len=15000');

		$query = $this->db->query("SELECT sum(subqty) as qty, sum(subjumlah_roll) as jumlah_roll, warna_id, barang_id, 
		DATE_FORMAT(tanggal,'%Y-%m') as bulan_berjalan, COUNT(DISTINCT t2.id) as jml_trx
				FROM (
					SELECT *
					FROM nd_penjualan_detail
					WHERE barang_id = $barang_id
					)t1
				LEFT JOIN (
					SELECT *
					FROM nd_penjualan
					WHERE tanggal >= '$tanggal_start'
					AND tanggal <= '$tanggal_end'
					AND status_aktif = 1
					) t2
				ON t1.penjualan_id = t2.id
				WHERE t2.id is not null
				GROUP BY warna_id, YEAR(tanggal), MONTH(tanggal)
		");
		return $query->result();
	}

	function barang_history_harga_beli_by_po($cond_barang){
        // GROUP_CONCAT(DISTINCT JSON_OBJECT('harga_beli', brg.harga_beli, 'tanggal',DATE_FORMAT(tanggal,'%Y-%m') )) as data_json

		$query = $this->db->query("SELECT barang_id, brg.harga_beli, group_concat( brg.harga_beli ORDER BY tanggal ASC) as harga_beli_data,
        tahun_bulan, nd_barang.harga_beli as harga_beli_master,
        GROUP_CONCAT( tanggal ORDER BY tanggal ASC) as tanggal, group_concat(count_trx) as count_trx
		FROM (
			SELECT barang_id, harga_beli, tahun_bulan, group_concat(tanggal) as tanggal, sum(1) as count_trx
			FROM (
				SELECT if(tipe_barang = 1, t1.barang_id, t2.barang_id_baru) as  barang_id,  ifnull(t2.harga_baru,t1.harga) as harga_beli, 
					t4.tanggal, DATE_FORMAT(t4.tanggal, '%Y-%m') as tahun_bulan
				FROM nd_po_pembelian_warna t2
				LEFT JOIN nd_po_pembelian_detail t1
				ON t2.po_pembelian_detail_id = t1.id
				LEFT JOIN nd_po_pembelian t3
				ON t1.po_pembelian_id = t3.id
				LEFT JOIN nd_po_pembelian_batch t4
				ON t2.po_pembelian_batch_id = t4.id
				WHERE t3.status_aktif = 1
			)res
			$cond_barang
			GROUP BY barang_id, harga_beli, tahun_bulan
		)brg
		LEFT JOIN nd_barang 
		ON brg.barang_id = nd_barang.id
		GROUP BY barang_id, tahun_bulan");

		return $query->result();

	}


	//==============================================request=================================================================

	function get_active_request_barang($id)
	{
		$this->db->simple_query('SET SESSION group_concat_max_len=15000');

		$query = $this->db->query("SELECT t1.*, group_concat(request_barang_detail_id SEPARATOR '??') as request_barang_detail_id, group_concat(barang_id SEPARATOR '??') as barang_id, group_concat(warna_id SEPARATOR '??') as warna_id, group_concat(qty SEPARATOR '??') as qty, group_concat(po_number SEPARATOR '??') as po_number, group_concat(nama_barang SEPARATOR '??') as nama_barang, group_concat(nama_warna SEPARATOR '??') as nama_warna, group_concat(po_pembelian_batch_id SEPARATOR '??') as po_pembelian_batch_id, bulan_request
				FROM (
					SELECT *
					FROM nd_request_barang
					WHERE id=$id
					)t1
				LEFT JOIN (
					SELECT request_barang_id, barang_id, nama_barang, 
						group_concat(warna_id ORDER BY warna_beli) as warna_id, 
						group_concat(warna_beli ORDER BY warna_beli) as nama_warna, 
						group_concat(po_pembelian_batch_id ORDER BY warna_beli) as po_pembelian_batch_id, 
						group_concat(qty ORDER BY warna_beli) as qty, 
						group_concat(po_number ORDER BY warna_beli) as po_number, 
						group_concat(id ORDER BY warna_beli) as request_barang_detail_id,  bulan_request
					FROM (
						SELECT tA.id as id, if(tA.po_pembelian_batch_id != 0 ,po_number, 'po baru') as po_number, nama as nama_barang, warna_beli, request_barang_id, qty, tA.po_pembelian_batch_id, barang_id, warna_id, bulan_request
						FROM nd_request_barang_detail tA
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
				ON t1.id = t2.request_barang_id
				LEFT JOIN (
					SELECT id, request_barang_id, barang_id as barang_id_request, warna_id as warna_id_request, qty as qty_request, bulan_request as bulan_request_qty
					FROM nd_request_barang_qty
					WHERE request_barang_id = $id
					) t3
				ON t1.id = t3.request_barang_id
				AND t2.barang_id = t3.barang_id_request
				AND t2.warna_id = t3.warna_id_request
				AND t2.bulan_request = t3.bulan_request_qty
				WHERE t2.barang_id is not null
				GROUP BY bulan_request
		");
		return $query->result();
	}

	function get_request_barang_qty($barang_id, $batch_id, $request_barang_id)
	{
		$query = $this->db->query("SELECT bulan_request, barang_id, warna_id, sum(qty) as qty, sum(qty_before) as qty_before
					FROM (
							(
								SELECT bulan_request, barang_id, warna_id, qty, 0 as qty_before
								FROM nd_request_barang_qty
								WHERE request_barang_id = $id_request
								AND barang_id = $barang_id
							)UNION(
								SELECT bulan_request, barang_id, warna_id, 0, qty
								FROM nd_request_barang_qty
								WHERE request_barang_id = $id_request_before
								AND barang_id = $barang_id
							)
					)result
					GROUP BY bulan_request, barang_id, warna_id
		");
		return $query->result();
	}

	function get_request_barang_qty_aktif($id_request, $id_request_before)
	{
		$query = $this->db->query("SELECT bulan_request, barang_id, warna_id, sum(qty) as qty, sum(qty_before) as qty_before, nama as nama_barang, warna_jual as nama_warna, if(sum(qty) - sum(qty_before) != 0 , 1, 0) as tipe
					FROM (
							(
								SELECT bulan_request, barang_id, warna_id, qty, 0 as qty_before
								FROM nd_request_barang_qty
								WHERE request_barang_id = $id_request
							)UNION(
								SELECT bulan_request, barang_id, warna_id, 0, qty
								FROM nd_request_barang_qty
								WHERE request_barang_id = $id_request_before
							)
					)t1
					LEFT JOIN nd_barang 
					ON t1.barang_id = nd_barang.id
					LEFT JOIN nd_warna
					ON t1.warna_id = nd_warna.id
					GROUP BY bulan_request, barang_id, warna_id
		");
		return $query->result();
	}

	function remove_request_detail($id_list, $request_barang_id, $barang_id)
	{
		if ($this->db->simple_query("DELETE FROM nd_request_barang_detail 
				WHERE id NOT IN ($id_list) 
				AND request_barang_id = $request_barang_id 
				AND barang_id = $barang_id")) {
			return true;
		} else {
			return false;
		}
	}

	function remove_request_detail_beli($id_list, $request_barang_id, $barang_beli_id)
	{
		if ($this->db->simple_query("DELETE FROM nd_request_barang_detail 
				WHERE id NOT IN ($id_list) 
				AND request_barang_id = $request_barang_id 
				AND barang_beli_id = $barang_beli_id")) {
			return true;
		} else {
			return false;
		}
	}

	function remove_request_detail_of_null($id_list)
	{
		if ($this->db->simple_query("DELETE FROM nd_request_barang_detail 
				WHERE id IN ($id_list)")) {
			return true;
		} else {
			return false;
		}
	}

	function remove_request_qty($id_list, $request_barang_id, $barang_id)
	{
		if ($this->db->simple_query("DELETE FROM nd_request_barang_qty 
				WHERE id NOT IN ($id_list) 
				AND request_barang_id = $request_barang_id 
				AND barang_id = $barang_id")) {
			return true;
		} else {
			return false;
		}
	}

	function remove_request_qty_beli($id_list, $request_barang_id, $barang_id)
	{
		if ($this->db->simple_query("DELETE FROM nd_request_barang_qty 
				WHERE id NOT IN ($id_list) 
				AND request_barang_id = $request_barang_id 
				AND barang_id = $barang_id")) {
			return true;
		} else {
			return false;
		}
	}

	function get_barang_group_list()
	{
		$query = $this->db->query("SELECT t1.*, t2.nama as nama_barang, t3.nama as nama_barang_induk
			FROM nd_barang_group t1
			LEFT JOIN nd_barang t2
			ON t1.barang_id = t2.id
			LEFT JOIN nd_barang t3
			ON t1.barang_id_induk = t3.id
			WHERE t1.status_aktif = 1

		");
		return $query->result();
	}

	function barang_history_harga_jual_all($cond_barang, $cond){
		$this->db->simple_query('SET SESSION group_concat_max_len=15000');

		$query = $this->db->query("SELECT barang_id, GROUP_CONCAT(harga_jual ORDER BY harga_jual ASC) as harga_jual_data, 
			GROUP_CONCAT(count_trx ORDER BY harga_jual ASC) as count_trx, tahun_bulan, GROUP_CONCAT(harga_master) as harga_master, 
			GROUP_CONCAT(nama_customer SEPARATOR '??') as nama_customer
		FROM (
			SELECT barang_id, harga_jual, tahun_bulan, harga_master,sum(1) as count_trx, GROUP_CONCAT(if(harga_jual <> harga_master, nama_customer, null )) as nama_customer
			FROM (
				SELECT barang_id, harga_jual, tahun_bulan, nama_customer,
					COALESCE((
							SELECT t1.harga_jual
							FROM nd_barang_harga_history t1
							WHERE t1.barang_id = tA.barang_id
							AND t1.tanggal <= tA.tanggal
							ORDER BY t1.tanggal DESC
							LIMIT 1
						),0) as harga_master
				FROM (
					SELECT barang_id, harga_jual, tanggal, 
						DATE_FORMAT(tanggal, '%Y-%m') as tahun_bulan, IF(penjualan_type_id = 3, nama_keterangan, nd_customer.nama) as nama_customer
					FROM nd_penjualan_detail t2
					LEFT JOIN nd_penjualan t1
					ON t2.penjualan_id = t1.id
					LEFT JOIN nd_customer
					ON t1.customer_id = nd_customer.id
					WHERE t1.status_aktif = 1
					AND harga_jual > 100
					GROUP BY penjualan_id
				)tA
				$cond_barang
			)res
			GROUP BY barang_id, harga_jual, tahun_bulan, harga_master
		)res
		$cond
		GROUP BY barang_id, tahun_bulan");

		return $query->result();

	}

	function harga_history_master($cond_barang){
		$query = $this->db->query("SELECT barang_id, group_concat(tA.harga_jual) as harga_jual_data, tanggal, 
				DATE_FORMAT(tanggal, '%Y-%m') as tahun_bulan, nd_barang.harga_jual AS harga_master
			FROM nd_barang_harga_history tA
			LEFT JOIN nd_barang 
			ON tA.barang_id = nd_barang.id
			$cond_barang
			GROUP BY DATE_FORMAT(tanggal, '%Y-%m')");

		return $query->result();
	}

	function barang_history_harga_jual($cond_barang){
		// CONCAT('[', 
		// 				GROUP_CONCAT(DISTINCT JSON_OBJECT('harga_jual', brg.harga_jual, 'tanggal',DATE_FORMAT(tanggal,'%Y-%m') )),
		// 			']'
		// 			) as data_json
		$query = $this->db->query("SELECT barang_id, brg.harga_jual, group_concat( brg.harga_jual ORDER BY tanggal ASC) as harga_jual_data,
					GROUP_CONCAT( DATE_FORMAT(tanggal,'%Y-%m') ORDER BY tanggal ASC ) as tahun_bulan, nd_barang.harga_jual as harga_jual_master,
					GROUP_CONCAT( tanggal ORDER BY tanggal ASC) as tanggal
			FROM (
				SELECT barang_id, harga_jual, min(tanggal) as tanggal
				FROM (
					(
						SELECT barang_id, harga_jual, tanggal 
						FROM nd_penjualan_detail t2
						LEFT JOIN nd_penjualan t1
						ON t2.penjualan_id = t1.id
						WHERE t1.status_aktif = 1
						AND penjualan_type_id = 3
						AND harga_jual > 100
					)UNION(
						SELECT barang_id, harga_jual, tanggal
						FROM nd_barang_harga_history
					)
				)result
				GROUP BY barang_id, harga_jual
			)brg
			LEFT JOIN nd_barang 
			ON brg.barang_id = nd_barang.id
			$cond_barang
			GROUP BY barang_id");

		return $query->result();

	}

	function barang_history_harga_jual_credit($cond_barang){
		$query = $this->db->query("SELECT barang_id, brg.harga_jual, group_concat( brg.harga_jual ORDER BY tanggal ASC) as harga_jual_data,
		GROUP_CONCAT( DATE_FORMAT(tanggal,'%Y-%m') ORDER BY tanggal ASC ) as tahun_bulan, nd_barang.harga_jual as harga_jual_master,
		GROUP_CONCAT( tanggal ORDER BY tanggal ASC) as tanggal, GROUP_CONCAT(nama_customer) as nama_customer
		FROM (
			SELECT barang_id, harga_jual, min(tanggal) as tanggal, 
			GROUP_CONCAT(DISTINCT nd_customer.nama ORDER BY nd_customer.nama SEPARATOR '??') as nama_customer
			FROM nd_penjualan_detail t2
			LEFT JOIN nd_penjualan t1
			ON t2.penjualan_id = t1.id
			LEFT JOIN nd_customer 
			ON t1.customer_id = nd_customer.id
			WHERE t1.status_aktif = 1
			AND penjualan_type_id = 2
			AND harga_jual > 100
			GROUP BY barang_id, harga_jual
		)brg
		LEFT JOIN nd_barang 
		ON brg.barang_id = nd_barang.id
		$cond_barang
		GROUP BY barang_id");

		return $query->result();

	}

	function barang_history_harga_beli_by_sj($cond_barang){
        // GROUP_CONCAT(DISTINCT JSON_OBJECT('harga_beli', brg.harga_beli, 'tanggal',DATE_FORMAT(tanggal,'%Y-%m') )) as data_json

		$query = $this->db->query("SELECT barang_id, brg.harga_beli, group_concat( brg.harga_beli ORDER BY tanggal ASC) as harga_beli_data,
        GROUP_CONCAT( DATE_FORMAT(tanggal,'%Y-%m')  ORDER BY tanggal ASC) as tahun_bulan, nd_barang.harga_beli as harga_beli_master,
        GROUP_CONCAT( tanggal ORDER BY tanggal ASC) as tanggal
		FROM (
			SELECT barang_id, harga_beli, min(tanggal) as tanggal
			FROM (
				(
					SELECT barang_id, harga_beli, tanggal 
					FROM nd_pembelian_detail t2
					LEFT JOIN nd_pembelian t1
					ON t2.pembelian_id = t1.id
					WHERE t1.status_aktif = 1
					AND harga_beli > 100
				)UNION(
					SELECT barang_id, harga_beli, tanggal
					FROM nd_barang_harga_history
				)
			)result
			GROUP BY barang_id, harga_beli
		)brg
		LEFT JOIN nd_barang 
		ON brg.barang_id = nd_barang.id
		$cond_barang
		GROUP BY barang_id");

		return $query->result();

	}

	function get_latest_request_by_barang($barang_id, $tanggal_start)
	{
		$query = $this->db->query("SELECT *
				FROM (
					SELECT tA.*,
						COALESCE((
								SELECT request_barang_batch_id
								FROM (
									SELECT barang_id, warna_id, max(request_barang_batch_id) as request_barang_batch_id
									FROM nd_request_barang_qty
									GROUP BY barang_id, warna_id
								) t1
								WHERE t1.barang_id = tA.barang_id
								AND t1.warna_id <= tA.warna_id
								AND t1.request_barang_batch_id = tA.request_barang_batch_id
								ORDER BY request_barang_batch_id DESC
								LIMIT 1
							),0) as request_barang_batch_id_last
					FROM (
						SELECT barang_id, warna_id, sum(qty) as qty, group_concat(qty) as qty_data, 
						group_concat(bulan_request) as bulan_request, request_barang_batch_id, request_barang_id
						FROM nd_request_barang_qty t1
						LEFT JOIN nd_request_barang_batch t2
						ON t1.request_barang_batch_id = t2.id
						WHERE barang_id = $barang_id
						AND t1.bulan_request >= '$tanggal_start'
						GROUP BY barang_id, warna_id, request_barang_batch_id
					)tA
				)res
				WHERE request_barang_batch_id_last != 0
			");

		return $query->result();		
	}

// =========================================penerimaan barang=============================================

	function get_penerimaan_barang_suggestion()
	{
		$query = $this->db->query("SELECT t1.id as id, no_plat, tanggal_input
				FROM (
					SELECT *
					FROM nd_penerimaan_barang
					)t1
				LEFT JOIN (
					SELECT penerimaan_barang_id, status_penerimaan 
					FROM nd_penerimaan_barang_status
					WHERE id IN (
						SELECT max(id)
						FROM nd_penerimaan_barang_status
					) t2
				ON t1.id = t2.penerimaan_barang_id
				WHERE status_penerimaan != 'SUDAH_KONFIRMASI'
				
		");
		return $query->result();
	}

}