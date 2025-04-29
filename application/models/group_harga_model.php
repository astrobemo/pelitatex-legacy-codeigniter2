<?php

class Group_Harga_Model extends CI_Model {

    function get_all_item_last_price()
    {
        // $this->db->simple_query('SET SESSION group_concat_max_len=15000');

		$query = $this->db->query(" SELECT t1.*, ifnull(t2.harga, t3.harga) as harga, if(t2.harga is null, t3.tanggal, t2.tanggal) as tanggal
            FROM nd_barang t1
            LEFT JOIN (
                SELECT if(tipe_barang = 1, barang_id, barang_id_baru) as barang_id, 
                ifnull(harga_baru, tB.harga) harga, tC.tanggal
                FROM nd_po_pembelian_warna tA
                LEFT JOIN nd_po_pembelian_detail tB
                ON tA.po_pembelian_detail_id = tB.id
                LEFT JOIN nd_po_pembelian_batch tC
                ON tA.po_pembelian_batch_id = tC.id
                WHERE tA.id iN (
                    SELECT max(id)
                    FROM (
                        SELECT tA.id as id, 
                        if(tipe_barang = 1, barang_id, barang_id_baru) as barang_id 
                        FROM nd_po_pembelian_warna tA
                        LEFT JOIN nd_po_pembelian_detail tB
                        ON tA.po_pembelian_detail_id = tB.id
                    )res
                    GROUP BY barang_id
                )
            ) t2
            ON t1.id = t2.barang_id
            LEFT JOIN (
                SELECT tanggal , barang_id, harga_beli/(1+(ifnull(ppn_berlaku,10)/100)) as harga
                FROM (
                    SELECT *
                    FROM nd_pembelian_detail
                    WHERE id iN (
                        SELECT max(id) as id
                        FROM nd_pembelian_detail
                        GROUP BY barang_id
                    )
                ) tA
                LEFT JOIN (
                    SELECT t1.*, 
					(SELECT ppn FROM nd_ppn tX WHERE tX.tanggal <= t1.tanggal ORDER BY tX.tanggal DESC LIMIT 1 ) as ppn_berlaku
                    FROM nd_pembelian t1
                ) tB
                ON tA.pembelian_id = tB.id
            ) t3 
            ON t1.id = t3.barang_id
		");
		return $query->result();
    }

    function get_all_item_last_purchased()
    {
        // $this->db->simple_query('SET SESSION group_concat_max_len=15000');

		$query = $this->db->query(" SELECT t1.*, t3.harga as harga, t3.tanggal as tanggal, ppn_berlaku
            FROM nd_barang t1
            LEFT JOIN (
                SELECT tanggal , barang_id, harga_beli/(1+(ifnull(ppn_berlaku,10)/100)) as harga, ppn_berlaku
                FROM (
                    SELECT *
                    FROM nd_pembelian_detail
                    WHERE id iN (
                        SELECT max(id) as id
                        FROM nd_pembelian_detail
                        GROUP BY barang_id
                    )
                ) tA
                LEFT JOIN (
                    SELECT t1.*, 
					(SELECT ppn FROM nd_ppn tX WHERE tX.tanggal <= t1.tanggal ORDER BY tX.tanggal DESC LIMIT 1 ) as ppn_berlaku
                    FROM nd_pembelian t1
                ) tB
                ON tA.pembelian_id = tB.id
            ) t3 
            ON t1.id = t3.barang_id
		");
		return $query->result();
    }

    function get_group_harga_all(){
        $query = $this->db->query("SELECT t1.*, jml_data
        FROM nd_group_harga_barang t1 
        LEFT JOIN (
            SELECT count(id) as jml_data, group_harga_barang_id
            FROM nd_group_harga_berlaku
            GROUP BY group_harga_barang_id
            ) t2
        ON t2.group_harga_barang_id = t1.id
        ORDER BY id, tipe
		");
		return $query->result();
    }


    function get_harga_berlaku($id)
    {
        // $this->db->simple_query('SET SESSION group_concat_max_len=15000');

		$query = $this->db->query("SELECT nd_barang.id as barang_id, tipe, harga_berlaku    , 
            group_harga_barang_id, ifnull(t1.id, 'harga_master') as tipe
            FROM nd_barang 
            LEFT JOIN (
                SELECT * 
                FROM nd_group_harga_berlaku
                WHERE group_harga_barang_id = $id
                ) t1
            ON nd_barang.id = t1.barang_id
            LEFT JOIN nd_group_harga_barang t2
            ON t1.group_harga_barang_id = t2.id
		");
		return $query->result();
    }

    function get_harga_berlaku_all()
    {
        // $this->db->simple_query('SET SESSION group_concat_max_len=15000');

		$query = $this->db->query("SELECT nd_barang.id as barang_id, tipe, if(tipe=1,harga_cash, harga_kredit) as harga, 
            group_harga_barang_id
            FROM nd_barang 
            LEFT JOIN nd_group_harga_berlaku t1
            ON nd_barang.id = t1.barang_id
            LEFT JOIN nd_group_harga_barang t2
            ON t1.group_harga_barang_id = t2.id
            WHERE tipe is not null
		");
		return $query->result();
    }

    function get_harga_berlaku_percustomer($customer_id, $tipe)
    {
        // $this->db->simple_query('SET SESSION group_concat_max_len=15000');

		$query = $this->db->query("SELECT t1.tipe, t3.barang_id, t1.group_harga_barang_id, 
        if(t1.tipe=1,t3.harga_cash,harga_kredit) as harga_berlaku, selisih_harga, harga_total
            FROM (
                SELECT *
                FROM nd_customer_harga
                WHERE customer_id = '$customer_id'
                AND tipe = '$tipe'
                ) t1
            LEFT JOIN nd_customer_harga_detail t2
            ON t1.id = t2.customer_harga_id
            LEFT JOIN nd_group_harga_berlaku t3
            ON t1.group_harga_barang_id = t3.group_harga_barang_id
		");

		return $query->result();
    
    }

    function get_harga_customer()
    {
        // $this->db->simple_query('SET SESSION group_concat_max_len=15000');

		$query = $this->db->query("SELECT t1.*, if(customer_id=0,'DEFAULT',t2.nama) as nama_customer
            FROM (
                SELECT customer_id, 
                group_concat(ghb_id_cash) as ghb_id_cash, 
                group_concat(nama_cash) as nama_cash,
                group_concat(ghb_id_kredit) as ghb_id_kredit,
                group_concat(nama_kredit) as nama_kredit
                FROM (
                    (
                        SELECT customer_id, tB.nama as nama_cash, group_harga_barang_id as ghb_id_cash, 
                        null as nama_kredit, null as ghb_id_kredit
                        FROM nd_customer_harga tA
                        LEFT JOIN nd_group_harga_barang tB
                        ON tA.group_harga_barang_id = tB.id
                        WHERE tA.tipe = 1
                    )UNION(
                        SELECT customer_id, null, null, 
                        tB.nama, group_harga_barang_id 
                        FROM nd_customer_harga tA
                        LEFT JOIN nd_group_harga_barang tB
                        ON tA.group_harga_barang_id = tB.id
                        WHERE tA.tipe = 2
                    )
                )res
                GROUP BY customer_id
            ) t1
            LEFT JOIN nd_customer t2
            ON t1.customer_id = t2.id
            ORDER BY t2.nama 
		");
		return $query->result();
    }

    function get_harga_list_berlaku(){
        $query = $this->db->query("SELECT t1.*, jml_data, jml_data_barang, 
        if(t1.tipe = 1, ifnull(jml_cash_baru,0), ifnull(jml_kredit_baru,0)) as data_baru
            FROM nd_group_harga_barang t1 
            LEFT JOIN (
                SELECT count(id) as jml_data, group_harga_barang_id
                FROM nd_group_harga_berlaku
                GROUP BY group_harga_barang_id
                ) t2
            ON t2.group_harga_barang_id = t1.id
            LEFT JOIN (
                SELECT count(id) as jml_data_barang
                FROM nd_barang
                WHERE status_aktif = 1
            ) t3
            ON t2.jml_data = t3.jml_data_barang
            LEFT JOIN (
                SELECT group_harga_barang_id, 
                sum(if(harga_cash != 0 AND harga_cash is not null,1,0)) as jml_cash_baru, 
                sum(if(harga_kredit != 0 AND harga_kredit is not null,1,0)) as jml_kredit_baru 
                FROM nd_group_harga_baru
                GROUP BY group_harga_barang_id
            )t4
            ON t1.id = t4.group_harga_barang_id
            -- WHERE jml_data is not null
            -- AND jml_data_barang is not null
            ORDER BY tipe, t1.nama
		");
		return $query->result();
    }

    function get_harga_baru_launch($id){
        $query = $this->db->query("SELECT t1.barang_id as barang_id, t2.id as berlaku_id, tipe,
        harga_baru, harga_berlaku
        FROM (
            SELECT id, barang_id, harga_baru, group_harga_barang_id
            FROM nd_group_harga_baru
            WHERE group_harga_barang_id = $id
        ) t1
        LEFT JOIN (
            SELECT id, barang_id, harga_berlaku
            FROM nd_group_harga_berlaku
            WHERE group_harga_barang_id = $id
        ) t2
        ON t1.barang_id = t2.barang_id
        LEFT JOIN nd_group_harga_barang t3
        ON t1.group_harga_barang_id = t3.id
        WHERE harga_baru > 0
		");
		return $query->result();
    }
}