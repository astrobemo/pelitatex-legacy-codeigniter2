<?php

class Finance_Model extends CI_Model {

    function __construct()
    {
         // Call the Model constructor
        parent::__construct();
        $this->db->query("SET SESSION time_zone = '+7:00'");
    }

//====================================bukur giro keluar======================================

   function get_buku_giro($tipe_trx, $cond_bank_list, $giro_cek_type, $pembayaran_hutang_id){
        $query = $this->db->query("SELECT tA.*, tC.nama_bank, tC.no_rek_bank, tipe_trx_1, tipe_trx_2
            FROM nd_giro_list tA
            LEFT JOIN (
                SELECT sum(lbr) as lbr, giro_list_id, group_concat(lbr) as lbr_data, group_concat(tipe) as tipe
                FROM (
                        (
                        SELECT sum(1) as lbr, 1 as tipe, giro_list_id
                        FROM nd_giro_list_detail
                        GROUP BY giro_list_id
                        )UNION(
                        SELECT sum(if(pembayaran_hutang_id != $pembayaran_hutang_id,1,0)) as lbr, 2 as tipe, giro_register_id as giro_list_id
                        FROM nd_pembayaran_hutang_nilai
                        WHERE pembayaran_type_id = $giro_cek_type
                        GROUP BY giro_register_id
                        )
                    )result
                GROUP BY giro_list_id
            ) tB
            ON tB.giro_list_id = tA.id
            LEFT JOIN nd_bank_list tC
            ON tA.bank_list_id = tC.id
            WHERE tA.tipe_trx = $tipe_trx
            AND jml_giro > ifnull(lbr,0)
            $cond_bank_list
            ", false);

        return $query->result();
    }

    function giro_register_detail($giro_list_id, $pembayaran_hutang_id, $pembayaran_type_id, $cond_nilai_id){
        $query = $this->db->query("SELECT *
                FROM (
                        (
                            SELECT no_giro, 1 as tipe, id, 'a' as tipe_detail
                            FROM nd_giro_list_detail
                            -- GROUP BY giro_list_id
                            WHERE giro_list_id = $giro_list_id
                        )UNION(
                            SELECT no_giro, 1 as tipe, id, 'b'
                            FROM nd_pembayaran_hutang_nilai
                            WHERE pembayaran_type_id = $pembayaran_type_id
                            AND giro_register_id = $giro_list_id
                            AND pembayaran_hutang_id != $pembayaran_hutang_id
                        )UNION(
                            SELECT no_giro, 2, id, 'c'
                            FROM nd_pembayaran_hutang_nilai
                            WHERE pembayaran_type_id = $pembayaran_type_id
                            AND giro_register_id = $giro_list_id
                            AND pembayaran_hutang_id = $pembayaran_hutang_id
                            $cond_nilai_id
                        )
                    )result
            ", false);

        return $query->result();
    }

//====================================hutang awal======================================
    
    function get_hutang_awal(){
        $query = $this->db->query("SELECT tbl_a.*, tbl_b.*
                FROM nd_supplier as tbl_a
                LEFT JOIN (
                    SELECT supplier_id, sum(1) as jumlah_nota, sum(ifnull(amount,0)) as amount
                    FROM nd_hutang_awal
                    GROUP BY supplier_id
                    ) as tbl_b
                ON tbl_b.supplier_id = tbl_a.id
                ORDER BY supplier_id asc
            ", false);

        return $query->result();
    }

    function get_hutang_awal_detail($supplier_id){
        $query = $this->db->query("SELECT tA.*, tanggal_bayar, jatuh_tempo_giro, tB.pembayaran_hutang_id, no_giro
                FROM (
                    SELECT *
                    FROM nd_hutang_awal
                    WHERE supplier_id = $supplier_id
                    ) tA
                LEFT JOIN (
                    SELECT *
                    FROM nd_pembayaran_hutang_detail
                    WHERE data_status = 2
                )tB
                ON tA.id = tB.pembelian_id
                LEFT JOIN (
                    SELECT t1.id, t2.*
                    FROM nd_pembayaran_hutang t1
                    LEFT JOIN (
                        SELECT pembayaran_hutang_id, tanggal_transfer as tanggal_bayar,group_concat(no_giro) as no_giro, group_concat(jatuh_tempo) as jatuh_tempo_giro
                        FROM nd_pembayaran_hutang_nilai
                        WHERE amount > 0
                        GROUP BY pembayaran_hutang_id
                    ) t2
                    ON t1.id = t2.pembayaran_hutang_id
                ) tC
                ON tC.id = tB.pembayaran_hutang_id
            ", false);

        return $query->result();
        
    }

//====================================hutang======================================

    function get_hutang_list($tanggal){
        $query = $this->db->query("SELECT a.nama as nama_supplier, b.*, amount_outstanding, jml, sisa_hutang_awal, sisa_retur
            FROM nd_supplier a 
            LEFT JOIN (
                SELECT status_aktif, supplier_id, sum(total_beli) as total_beli, sum(ifnull(total_bayar,0)) as total_bayar, sum(sisa_hutang) as sisa_hutang
                FROM (
                    (
                        SELECT status_aktif, tA.supplier_id, total_beli, total_bayar_nilai as total_bayar , 
                        (total_beli + ifnull(awal,0) - ifnull(total_bayar_nilai,0) - ifnull(pembulatan,0)  - ifnull(total_bayar_retur,0) ) as sisa_hutang
                        FROM (
                            SELECT tbl_a.status_aktif, supplier_id, sum(ifnull(round(total,0),0)) as total_beli, sum(total_bayar) as total_bayar, 
                            sum(ifnull(round(total,0),0)) - sum(ifnull(total_bayar,0)) as sisa_hutang
                            FROM (
                                SELECT t1.*, total, pembelian_id
                                FROM (
                                    SELECT *
                                    FROM nd_pembelian
                                    WHERE status_aktif = 1
                                    AND tanggal <= '$tanggal'
                                    )t1
                                    LEFT JOIN (
                                        SELECT id, sum(qty*harga_beli) as total, harga_beli, pembelian_id
                                        FROM nd_pembelian_detail t1
                                        group by pembelian_id
                                    ) t2
                                    ON t1.id = t2.pembelian_id
                                ) as tbl_a
                            LEFT JOIN nd_supplier as tbl_c
                            ON tbl_c.id = tbl_a.supplier_id
                            LEFT JOIN (
                                SELECT sum(ifnull(amount,0)) as total_bayar, pembelian_id
                                FROM nd_pembayaran_hutang_detail
                                WHERE data_status = 1
                                GROUP BY pembelian_id
                                ) tbl_d
                            ON tbl_a.id = tbl_d.pembelian_id
                            GROUP BY supplier_id
                        )tA
                        LEFT JOIN (
                            SELECT sum(total_bayar_nilai) as total_bayar_nilai, sum(ifnull(pembulatan,0)) as pembulatan, supplier_id
                            FROM nd_pembayaran_hutang tY
                            LEFT JOIN (
                                SELECT sum(amount) as total_bayar_nilai, pembayaran_hutang_id
                                FROM nd_pembayaran_hutang_nilai
                                GROUP BY pembayaran_hutang_id
                            ) tX
                            ON tX.pembayaran_hutang_id = tY.id
                            WHERE tY.created <= '$tanggal 23:59:59'
                            GROUP BY supplier_id
                        )tB
                        ON tA.supplier_id = tB.supplier_id
                        LEFT JOIN (
                            SELECT supplier_id, sum(total_bayar) as total_bayar_retur
                            FROM (
                                SELECT t1.*, sum(qty*harga_beli) as total, retur_beli_detail_id
                                FROM (
                                    SELECT *
                                    FROM nd_retur_beli
                                    WHERE status_aktif = 1
                                    AND tanggal <= '$tanggal'
                                    )t1
                                    LEFT JOIN (
                                        SELECT id, harga as harga_beli, retur_beli_id
                                        FROM nd_retur_beli_detail
                                    ) t2
                                    ON t1.id = t2.retur_beli_id
                                    LEFT JOIN (
                                        SELECT retur_beli_detail_id, sum(qty*if(jumlah_roll=0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll
                                        FROM nd_retur_beli_qty
                                        GROUP BY retur_beli_detail_id
                                    )t3
                                    ON t2.id = t3.retur_beli_detail_id
                                    GROUP BY t1.id
                                ) as tbl_a
                            LEFT JOIN nd_supplier as tbl_c
                            ON tbl_c.id = tbl_a.supplier_id
                            LEFT JOIN (
                                SELECT sum(ifnull(amount,0)) as total_bayar, pembelian_id
                                FROM nd_pembayaran_hutang_detail
                                WHERE data_status = 4
                                GROUP BY pembelian_id
                                ) tbl_d
                            ON tbl_a.id = tbl_d.pembelian_id
                            GROUP BY supplier_id
                        )tC
                        ON tA.supplier_id = tC.supplier_id
                        LEFT JOIN (
                            SELECT sum(amount) as awal, supplier_id
                            FROM nd_hutang_awal
                            GROUP BY supplier_id
                        ) tD
                        ON tA.supplier_id = tD.supplier_id
                    )UNION(
                        SELECT tbl_a.status_aktif, supplier_id, sum(total) as total_beli, sum(ifnull(total_bayar,0)) as total_bayar, sum(ifnull(total,0)) - sum(ifnull(total_bayar,0)) as sisa_hutang
                        FROM (
                            SELECT t1.*, total, pembelian_lain_id
                            FROM (
                                SELECT *
                                FROM nd_pembelian_lain
                                WHERE status_aktif = 1
                                )t1
                                LEFT JOIN (
                                    SELECT id, sum(qty*harga_beli) as total, harga_beli, pembelian_lain_id
                                    FROM nd_pembelian_lain_detail
                                    group by pembelian_lain_id
                                ) t2
                                ON t1.id = t2.pembelian_lain_id
                            ) as tbl_a
                        LEFT JOIN nd_supplier as tbl_c
                        ON tbl_c.id = tbl_a.supplier_id
                        LEFT JOIN (
                            SELECT sum(ifnull(amount,0)) as total_bayar, pembelian_id
                            FROM nd_pembayaran_hutang_detail
                            WHERE data_status = 11
                            GROUP BY pembelian_id
                            ) tbl_d
                        ON tbl_a.id = tbl_d.pembelian_id
                        GROUP BY supplier_id
                    )
                ) t1
                GROUP BY supplier_id
            ) b
            ON b.supplier_id = a.id
            LEFT JOIN (
                SELECT t2.supplier_id, sum(amount) as amount_outstanding, sum(jml) as jml
                FROM ((
                        SELECT sum(amount) as amount , pembayaran_hutang_id, sum(1) as jml, pembayaran_type_id
                        FROM nd_pembayaran_hutang_nilai
                        WHERE pembayaran_type_id = 2
                        AND created <= '$tanggal 23:59:59'
                        AND jatuh_tempo >= '$tanggal'
                        GROUP BY pembayaran_hutang_id
                    )UNION(
                        SELECT sum(amount) as amount , pembayaran_hutang_id, sum(1) as jml, pembayaran_type_id
                        FROM nd_pembayaran_hutang_nilai
                        WHERE pembayaran_type_id = 5
                        AND created <= '$tanggal 23:59:59'
                        AND jatuh_tempo >= '$tanggal'
                        GROUP BY pembayaran_hutang_id
                    )
                    ) t1
                LEFT JOIN nd_pembayaran_hutang t2
                ON t1.pembayaran_hutang_id = t2.id
                GROUP BY supplier_id
            ) c
            ON a.id = c.supplier_id
            LEFT JOIN (
                SELECT supplier_id, sum(amount) as total_beli, sum(total_bayar) as total_bayar, sum(ifnull(amount,0)) - sum(ifnull(total_bayar,0)) as sisa_hutang_awal
                FROM nd_hutang_awal t1
                LEFT JOIN (
                    SELECT sum(ifnull(amount,0)) as total_bayar, pembelian_id
                    FROM nd_pembayaran_hutang_detail
                    WHERE data_status = 2
                    GROUP BY pembelian_id
                    ) t2
                ON t1.id = t2.pembelian_id
                GROUP BY supplier_id
                ) d
            ON a.id = d.supplier_id
            LEFT JOIN (
                SELECT tbl_a.status_aktif, supplier_id, sum(total) as total_retur, sum(total_bayar) as total_bayar_retur, sum(ifnull(total,0)) - sum(ifnull(total_bayar,0)) as sisa_retur
                FROM (
                    SELECT t1.*, sum(qty*harga_beli) as total, retur_beli_detail_id
                    FROM (
                        SELECT *
                        FROM nd_retur_beli
                        WHERE status_aktif = 1
                        )t1
                        LEFT JOIN (
                            SELECT id, harga as harga_beli, retur_beli_id
                            FROM nd_retur_beli_detail
                        ) t2
                        ON t1.id = t2.retur_beli_id
                        LEFT JOIN (
                            SELECT retur_beli_detail_id, sum(qty*if(jumlah_roll=0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll
                            FROM nd_retur_beli_qty
                            GROUP BY retur_beli_detail_id
                        )t3
                        ON t2.id = t3.retur_beli_detail_id
                        GROUP BY t1.id
                    ) as tbl_a
                LEFT JOIN nd_supplier as tbl_c
                ON tbl_c.id = tbl_a.supplier_id
                LEFT JOIN (
                    SELECT sum(ifnull(amount,0)) as total_bayar, pembelian_id
                    FROM nd_pembayaran_hutang_detail
                    WHERE data_status = 4
                    GROUP BY pembelian_id
                    ) tbl_d
                ON tbl_a.id = tbl_d.pembelian_id
                GROUP BY supplier_id
                ) e
            ON e.supplier_id = a.id
            ", false);

        return $query->result();
    }

    function get_hutang_list_by_tanggal_cair($tanggal){
        $query = $this->db->query("SELECT a.nama as nama_supplier, b.*, amount_outstanding, jml, sisa_hutang_awal, sisa_retur
            FROM nd_supplier a 
            LEFT JOIN (
                SELECT status_aktif, supplier_id, sum(total_beli) as total_beli, sum(ifnull(total_bayar,0)) as total_bayar, sum(sisa_hutang) as sisa_hutang
                FROM (
                    (
                        SELECT status_aktif, tA.supplier_id, total_beli, total_bayar_nilai as total_bayar , 
                        (total_beli + ifnull(awal,0) - ifnull(total_bayar_nilai,0) - ifnull(pembulatan,0)  - ifnull(total_bayar_retur,0) ) as sisa_hutang
                        FROM (
                            SELECT tbl_a.status_aktif, supplier_id, sum(ifnull(round(total,0),0)) as total_beli, sum(total_bayar) as total_bayar, 
                            sum(ifnull(round(total,0),0)) - sum(ifnull(total_bayar,0)) as sisa_hutang
                            FROM (
                                SELECT t1.*, total, pembelian_id
                                FROM (
                                    SELECT *
                                    FROM nd_pembelian
                                    WHERE status_aktif = 1
                                    AND tanggal <= '$tanggal'
                                    )t1
                                    LEFT JOIN (
                                        SELECT id, sum(qty*harga_beli) as total, harga_beli, pembelian_id
                                        FROM nd_pembelian_detail t1
                                        group by pembelian_id
                                    ) t2
                                    ON t1.id = t2.pembelian_id
                                ) as tbl_a
                            LEFT JOIN nd_supplier as tbl_c
                            ON tbl_c.id = tbl_a.supplier_id
                            LEFT JOIN (
                                SELECT sum(ifnull(amount,0)) as total_bayar, pembelian_id
                                FROM nd_pembayaran_hutang_detail
                                WHERE data_status = 1
                                GROUP BY pembelian_id
                                ) tbl_d
                            ON tbl_a.id = tbl_d.pembelian_id
                            GROUP BY supplier_id
                        )tA
                        LEFT JOIN (
                            SELECT sum(total_bayar_nilai) as total_bayar_nilai, sum(ifnull(pembulatan,0)) as pembulatan, supplier_id
                            FROM nd_pembayaran_hutang tY
                            LEFT JOIN (
                                SELECT sum(amount) as total_bayar_nilai, pembayaran_hutang_id
                                FROM nd_pembayaran_hutang_nilai
                                GROUP BY pembayaran_hutang_id
                            ) tX
                            ON tX.pembayaran_hutang_id = tY.id
                            WHERE tY.created <= '$tanggal 23:59:59'
                            GROUP BY supplier_id
                        )tB
                        ON tA.supplier_id = tB.supplier_id
                        LEFT JOIN (
                            SELECT supplier_id, sum(total_bayar) as total_bayar_retur
                            FROM (
                                SELECT t1.*, sum(qty*harga_beli) as total, retur_beli_detail_id
                                FROM (
                                    SELECT *
                                    FROM nd_retur_beli
                                    WHERE status_aktif = 1
                                    AND tanggal <= '$tanggal'
                                    )t1
                                    LEFT JOIN (
                                        SELECT id, harga as harga_beli, retur_beli_id
                                        FROM nd_retur_beli_detail
                                    ) t2
                                    ON t1.id = t2.retur_beli_id
                                    LEFT JOIN (
                                        SELECT retur_beli_detail_id, sum(qty*if(jumlah_roll=0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll
                                        FROM nd_retur_beli_qty
                                        GROUP BY retur_beli_detail_id
                                    )t3
                                    ON t2.id = t3.retur_beli_detail_id
                                    GROUP BY t1.id
                                ) as tbl_a
                            LEFT JOIN nd_supplier as tbl_c
                            ON tbl_c.id = tbl_a.supplier_id
                            LEFT JOIN (
                                SELECT sum(ifnull(amount,0)) as total_bayar, pembelian_id
                                FROM nd_pembayaran_hutang_detail
                                WHERE data_status = 4
                                GROUP BY pembelian_id
                                ) tbl_d
                            ON tbl_a.id = tbl_d.pembelian_id
                            GROUP BY supplier_id
                        )tC
                        ON tA.supplier_id = tC.supplier_id
                        LEFT JOIN (
                            SELECT sum(amount) as awal, supplier_id
                            FROM nd_hutang_awal
                            GROUP BY supplier_id
                        ) tD
                        ON tA.supplier_id = tD.supplier_id
                    )UNION(
                        SELECT tbl_a.status_aktif, supplier_id, sum(total) as total_beli, sum(ifnull(total_bayar,0)) as total_bayar, sum(ifnull(total,0)) - sum(ifnull(total_bayar,0)) as sisa_hutang
                        FROM (
                            SELECT t1.*, total, pembelian_lain_id
                            FROM (
                                SELECT *
                                FROM nd_pembelian_lain
                                WHERE status_aktif = 1
                                )t1
                                LEFT JOIN (
                                    SELECT id, sum(qty*harga_beli) as total, harga_beli, pembelian_lain_id
                                    FROM nd_pembelian_lain_detail
                                    group by pembelian_lain_id
                                ) t2
                                ON t1.id = t2.pembelian_lain_id
                            ) as tbl_a
                        LEFT JOIN nd_supplier as tbl_c
                        ON tbl_c.id = tbl_a.supplier_id
                        LEFT JOIN (
                            SELECT sum(ifnull(amount,0)) as total_bayar, pembelian_id
                            FROM nd_pembayaran_hutang_detail
                            WHERE data_status = 11
                            GROUP BY pembelian_id
                            ) tbl_d
                        ON tbl_a.id = tbl_d.pembelian_id
                        GROUP BY supplier_id
                    )
                ) t1
                GROUP BY supplier_id
            ) b
            ON b.supplier_id = a.id
            LEFT JOIN (
                SELECT t2.supplier_id, sum(amount) as amount_outstanding, sum(jml) as jml
                FROM ((
                        SELECT sum(amount) as amount , pembayaran_hutang_id, sum(1) as jml, pembayaran_type_id
                        FROM nd_pembayaran_hutang_nilai
                        WHERE pembayaran_type_id = 2
                        AND created <= '$tanggal 23:59:59'
                        AND (
                            tanggal_cair > '$tanggal'
                            OR tanggal_cair is null
                        )
                        GROUP BY pembayaran_hutang_id
                    )UNION(
                        SELECT sum(amount) as amount , pembayaran_hutang_id, sum(1) as jml, pembayaran_type_id
                        FROM nd_pembayaran_hutang_nilai
                        WHERE pembayaran_type_id = 5
                        AND tanggal_cair is null
                        GROUP BY pembayaran_hutang_id
                    )
                    ) t1
                LEFT JOIN nd_pembayaran_hutang t2
                ON t1.pembayaran_hutang_id = t2.id
                GROUP BY supplier_id
            ) c
            ON a.id = c.supplier_id
            LEFT JOIN (
                SELECT supplier_id, sum(amount) as total_beli, sum(total_bayar) as total_bayar, sum(ifnull(amount,0)) - sum(ifnull(total_bayar,0)) as sisa_hutang_awal
                FROM nd_hutang_awal t1
                LEFT JOIN (
                    SELECT sum(ifnull(amount,0)) as total_bayar, pembelian_id
                    FROM nd_pembayaran_hutang_detail
                    WHERE data_status = 2
                    GROUP BY pembelian_id
                    ) t2
                ON t1.id = t2.pembelian_id
                GROUP BY supplier_id
                ) d
            ON a.id = d.supplier_id
            LEFT JOIN (
                SELECT tbl_a.status_aktif, supplier_id, sum(total) as total_retur, sum(total_bayar) as total_bayar_retur, sum(ifnull(total,0)) - sum(ifnull(total_bayar,0)) as sisa_retur
                FROM (
                    SELECT t1.*, sum(qty*harga_beli) as total, retur_beli_detail_id
                    FROM (
                        SELECT *
                        FROM nd_retur_beli
                        WHERE status_aktif = 1
                        )t1
                        LEFT JOIN (
                            SELECT id, harga as harga_beli, retur_beli_id
                            FROM nd_retur_beli_detail
                        ) t2
                        ON t1.id = t2.retur_beli_id
                        LEFT JOIN (
                            SELECT retur_beli_detail_id, sum(qty*if(jumlah_roll=0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll
                            FROM nd_retur_beli_qty
                            GROUP BY retur_beli_detail_id
                        )t3
                        ON t2.id = t3.retur_beli_detail_id
                        GROUP BY t1.id
                    ) as tbl_a
                LEFT JOIN nd_supplier as tbl_c
                ON tbl_c.id = tbl_a.supplier_id
                LEFT JOIN (
                    SELECT sum(ifnull(amount,0)) as total_bayar, pembelian_id
                    FROM nd_pembayaran_hutang_detail
                    WHERE data_status = 4
                    GROUP BY pembelian_id
                    ) tbl_d
                ON tbl_a.id = tbl_d.pembelian_id
                GROUP BY supplier_id
                ) e
            ON e.supplier_id = a.id
            ", false);

        return $query->result();
    }

    function get_hutang_list_detail($supplier_id, $tanggal){
        $query = $this->db->query("SELECT t1.*, t2.nama as supplier
            FROM (
                (
                    SELECT tanggal, no_faktur, supplier_id, total as total_beli, total_bayar, ROUND(ifnull(total,0),0) - ifnull(total_bayar,0) as sisa_hutang, tbl_a.id, 1 as tipe, ockh_info as OCKH
                    FROM (
                        SELECT *
                        FROM nd_pembelian
                        WHERE supplier_id = $supplier_id
                        AND tanggal <= '$tanggal'
                        AND status_aktif = 1
                        ) as tbl_a
                    LEFT JOIN nd_toko as tbl_b
                    ON tbl_a.toko_id = tbl_b.id
                    LEFT JOIN (
                        SELECT id, sum(qty) as qty, sum(jumlah_roll) as jumlah_roll, sum(qty*harga_beli) as total, harga_beli, pembelian_id, barang_id, satuan_id
                        FROM nd_pembelian_detail t1
                        group by pembelian_id
                        ) as tbl_c
                    ON tbl_c.pembelian_id = tbl_a.id
                    LEFT JOIN nd_gudang as tbl_d
                    ON tbl_a.gudang_id = tbl_d.id
                    LEFT JOIN (
                        SELECT sum(amount) as total_bayar, pembelian_id
                        FROM (
                            SELECT tA.*
                            FROM nd_pembayaran_hutang_detail tA
                            LEFT JOIN (
                                SELECT beli - ifnull(retur,0) - ifnull(retur,0) as sisa_sisa, tX.pembayaran_hutang_id
                                FROM (
                                    SELECT sum(if(data_status=1,amount,0)) as beli, sum(if(data_status=4, amount,0)) as retur, pembayaran_hutang_id
                                    FROM nd_pembayaran_hutang_detail
                                    GROUP BY pembayaran_hutang_id
                                )tX
                                LEFT JOIN (
                                    SELECT sum(amount) as bayar, pembayaran_hutang_id
                                    FROM nd_pembayaran_hutang_nilai
                                    WHERE created <= '$tanggal 23:59:59'
                                    GROUP BY pembayaran_hutang_id
                                ) tY
                                ON tX.pembayaran_hutang_id = tY.pembayaran_hutang_id
                                WHERE beli - ifnull(retur,0) - ifnull(retur,0) > 0
                                AND tY.pembayaran_hutang_id is not null
                            ) tB
                            ON tA.pembayaran_hutang_id = tB.pembayaran_hutang_id
                            WHERE tB.pembayaran_hutang_id is not null
                        )tA
                        WHERE data_status = 1
                        GROUP BY pembelian_id
                        ) tbl_f
                    ON tbl_a.id = tbl_f.pembelian_id
                    WHERE ifnull(total_bayar,0) - ROUND(ifnull(total,0),0) < 0

                )UNION(
                    SELECT tanggal, concat(no_faktur,' (hutang awal)'), supplier_id, amount, 0, ifnull(amount,0) as sisa_hutang,id, 2, ''
                    FROM nd_hutang_awal tA
                    LEFT JOIN (
                        SELECT sum(amount) as total_bayar, pembelian_id
                        FROM nd_pembayaran_hutang_detail
                        WHERE data_status = 2
                        GROUP BY pembelian_id
                    ) tB
                    ON tA.id = tB.pembelian_id
                    WHERE supplier_id = $supplier_id
                    AND ifnull(total_bayar,0) - ifnull(amount,0) < 0
                )
            ) t1
            LEFT JOIN nd_supplier as t2
            ON t2.id = t1.supplier_id
            ", false);

        return $query->result();
    }

    function get_hutang_lain_detail($supplier_id){
        $query = $this->db->query("SELECT t1.*, t2.nama as supplier, 0 as jumlah_roll
            FROM (
                (
                    SELECT tanggal, no_faktur, supplier_id, total as total_beli, total_bayar, ifnull(total,0) - ifnull(total_bayar,0) as sisa_hutang
                    FROM (
                        SELECT *
                        FROM nd_pembelian_lain
                        WHERE supplier_id = $supplier_id
                        AND status_aktif = 1
                        ) as tbl_a
                    LEFT JOIN nd_toko as tbl_b
                    ON tbl_a.toko_id = tbl_b.id
                    LEFT JOIN (
                        SELECT id, sum(qty) as qty, sum(qty*harga_beli) as total, harga_beli, pembelian_lain_id
                        FROM nd_pembelian_lain_detail
                        group by pembelian_lain_id
                        ) as tbl_c
                    ON tbl_c.pembelian_lain_id = tbl_a.id
                    LEFT JOIN (
                        SELECT sum(amount) as total_bayar, pembelian_id
                        FROM nd_pembayaran_hutang_detail
                        WHERE data_status = 11
                        GROUP BY pembelian_id
                        ) tbl_f
                    ON tbl_a.id = tbl_f.pembelian_id
                    WHERE ifnull(total_bayar,0) - ifnull(total,0) < 0
                )
            ) t1
            LEFT JOIN nd_supplier as t2
            ON t2.id = t1.supplier_id
            ", false);

        return $query->result();
    }

    function get_hutang_list_by_date($from, $to, $toko_id, $supplier_id){
        $query = $this->db->query("SELECT tbl_a.status_aktif, no_faktur, supplier_id, 
        total as total_beli, total_bayar, ifnull(total,0) - ifnull(total_bayar,0) as sisa_hutang, 
        tbl_a.id as pembelian_id, jatuh_tempo, jumlah_roll, tanggal, 1 as data_status, po_number_batch
                FROM (
                    SELECT *
                    FROM nd_pembelian
                    WHERE tanggal >= '$from'
                    AND tanggal <= '$to'
                    AND supplier_id = $supplier_id
                    AND toko_id = $toko_id
                    AND status_aktif = 1
                    ) as tbl_a
                LEFT JOIN nd_toko as tbl_b
                ON tbl_a.toko_id = tbl_b.id
                LEFT JOIN (
                    SELECT id, sum(qty) as qty, sum(jumlah_roll) as jumlah_roll, sum(qty*harga_beli) as total, harga_beli, pembelian_id, barang_id, satuan_id
                    FROM nd_pembelian_detail t1
                    group by pembelian_id
                    ) as tbl_c
                ON tbl_c.pembelian_id = tbl_a.id
                LEFT JOIN nd_gudang as tbl_d
                ON tbl_a.gudang_id = tbl_d.id
                LEFT JOIN nd_supplier as tbl_e
                ON tbl_e.id = tbl_a.supplier_id
                LEFT JOIN (
                    SELECT sum(amount) as total_bayar, pembelian_id
                    FROM nd_pembayaran_hutang_detail
                    WHERE data_status = 1
                    GROUP BY pembelian_id
                    ) tbl_f
                ON tbl_a.id = tbl_f.pembelian_id
                LEFT JOIN (
                    SELECT t2.id as po_pembelian_batch_id, po_pembelian_id, concat(po_number_lengkap,'-',batch,if(revisi > 1,concat('R',revisi-1),'')) as po_number_batch
                    FROM view_po_pembelian t1
                    LEFT JOIN nd_po_pembelian_batch t2
                    ON t1.id = t2.po_pembelian_id
                    ) tD
                ON tbl_a.po_pembelian_batch_id = tD.po_pembelian_batch_id
                WHERE ifnull(total,0) - ifnull(total_bayar,0) > 0
                ORDER by tanggal, no_faktur asc
            ", false);

        return $query->result();
    }

    function get_hutang_list_lain_by_date($from, $to, $toko_id, $supplier_id){
        $query = $this->db->query("SELECT tbl_a.status_aktif, no_faktur, supplier_id, total as total_beli, total_bayar, ifnull(total,0) - ifnull(total_bayar,0) as sisa_hutang, tbl_a.id as pembelian_id, jatuh_tempo, 0 as jumlah_roll, tanggal, 11 as data_status
                FROM (
                    SELECT *
                    FROM nd_pembelian_lain
                    WHERE tanggal >= '$from'
                    AND tanggal <= '$to'
                    AND supplier_id = $supplier_id
                    AND toko_id = $toko_id
                    AND status_aktif = 1
                    ) as tbl_a
                LEFT JOIN nd_toko as tbl_b
                ON tbl_a.toko_id = tbl_b.id
                LEFT JOIN (
                    SELECT id, sum(qty) as qty, sum(qty*harga_beli) as total, harga_beli, pembelian_lain_id
                    FROM nd_pembelian_lain_detail
                    group by pembelian_lain_id
                    ) as tbl_c
                ON tbl_c.pembelian_lain_id = tbl_a.id
                LEFT JOIN nd_supplier as tbl_e
                ON tbl_e.id = tbl_a.supplier_id
                LEFT JOIN (
                    SELECT sum(amount) as total_bayar, pembelian_id
                    FROM nd_pembayaran_hutang_detail
                    WHERE data_status = 11
                    GROUP BY pembelian_id
                    ) tbl_f
                ON tbl_a.id = tbl_f.pembelian_id
                WHERE ifnull(total,0) - ifnull(total_bayar,0) > 0
                ORDER by tanggal, no_faktur asc
            ", false);

        return $query->result();
    }

    function get_hutang_awal_by_date($from, $to, $toko_id, $supplier_id){
        $query = $this->db->query("SELECT 1, concat(no_faktur,' (hutang awal)') as no_faktur, supplier_id, amount as total_beli,0 as total_bayar , ifnull(amount,0) - 0 as sisa_hutang, id as pembelian_id, jatuh_tempo, jumlah_roll, 2 as data_status
                FROM nd_hutang_awal
                WHERE supplier_id = $supplier_id
                AND tanggal >= '$from'
                AND tanggal <= '$to'
                AND toko_id = $toko_id
                ORDER BY tanggal asc
                ");
        return $query->result();

    }

    function get_giro_keluar_belum_cair($supplier_id, $tanggal){
        $query = $this->db->query("SELECT t1.*
            FROM (
                SELECT *
                FROM nd_pembayaran_hutang_nilai
                WHERE pembayaran_type_id = 2
                AND created <= '$tanggal 23:59:59'
                AND jatuh_tempo >= '$tanggal'
                ) t1
            LEFT JOIN (
                SELECT *
                FROM nd_pembayaran_hutang
                WHERE supplier_id = $supplier_id
                ) t2
            ON t1.pembayaran_hutang_id = t2.id
            WHERE t2.id is not null
                ");
        return $query->result();
    }

    function get_giro_keluar_belum_cair_by_tanggal_cair($supplier_id, $tanggal){
        $query = $this->db->query("SELECT t1.*
            FROM (
                SELECT *
                FROM nd_pembayaran_hutang_nilai
                WHERE pembayaran_type_id = 2
                AND created <= '$tanggal 23:59:59'
                AND ( 
                    tanggal_cair > '$tanggal'
                    OR tanggal_cair is null
                    )
                ) t1
            LEFT JOIN (
                SELECT *
                FROM nd_pembayaran_hutang
                WHERE supplier_id = $supplier_id
                ) t2
            ON t1.pembayaran_hutang_id = t2.id
            WHERE t2.id is not null
                ");
        return $query->result();
    }

//========================================hutang payment============================

    function get_bank_bayar_history(){
        $query = $this->db->query("SELECT nama_bank, no_rek_bank
                FROM nd_pembayaran_hutang_nilai
                WHERE nama_bank is not null
                AND nama_bank != ''
                GROUP BY nama_bank, no_rek_bank
                    ");

        return $query->result();
    }

    function get_pembayaran_hutang($tanggal_start, $tanggal_end, $cond){
        $query = $this->db->query("SELECT a.id, b.nama as nama_supplier, c.nama as nama_toko, supplier_id, 
                toko_id, pembulatan, amount_bayar, tanggal_bayar
                FROM (
                    SELECT id, supplier_id, toko_id, pembulatan, tanggal_bayar
                    FROM (
                        (
                            SELECT id, supplier_id, toko_id, pembulatan, DATE_FORMAT(created,'%Y-%m-%d') as tanggal_bayar
                            FROM nd_pembayaran_hutang
                            $cond
                            AND DATE(created) >= '$tanggal_start'
                            AND DATE(created) <= '$tanggal_end'
                        )UNION(
                            SELECT tbl_b.id, supplier_id, toko_id, 0, min(tanggal_transfer) 
                            FROM (
                                SELECT *
                                FROM nd_pembayaran_hutang_nilai
                                WHERE tanggal_transfer >= '$tanggal_start'
                                AND tanggal_transfer <= '$tanggal_end'
                                
                                )tbl_a
                            LEFT JOIN nd_pembayaran_hutang tbl_b
                            ON tbl_a.pembayaran_hutang_id = tbl_b.id
                            $cond
                            GROUP BY pembayaran_hutang_id, toko_id, supplier_id
                        )
                    ) a
                    GROUP BY id,supplier_id,toko_id
                )a
                LEFT JOIN nd_supplier b
                ON a.supplier_id = b.id
                LEFT JOIN nd_toko c
                ON a.toko_id = c.id
                LEFT JOIN (
                    SELECT pembayaran_hutang_id, sum(amount * if(data_status != 4,1,-1)) as amount_bayar
                    FROM nd_pembayaran_hutang_detail
                    GROUP BY pembayaran_hutang_id
                    )d
                ON a.id = d.pembayaran_hutang_id 
                ORDER BY tanggal_bayar ASC
            ", false);

        return $query->result();
    }

    function get_pembayaran_hutang_data($id){
        $query = $this->db->query("SELECT tbl_a.*, tbl_c.nama as nama_supplier, tbl_d.nama as nama_toko
            FROM (
                SELECT *
                FROM nd_pembayaran_hutang
                WHERE id = $id
                ) tbl_a
            LEFT JOIN nd_supplier tbl_c
            ON tbl_a.supplier_id = tbl_c.id
            LEFT JOIN nd_toko tbl_d
            ON tbl_a.toko_id = tbl_d.id
            ", false);

        return $query->result();
    }

    function get_pembayaran_hutang_detail($id){
        $query = $this->db->query("SELECT tbl_a.*, tbl_b.no_faktur, sisa_hutang, jatuh_tempo, 
        tanggal, jumlah_roll, po_number_batch
            FROM (      
                SELECT *
                FROM nd_pembayaran_hutang_detail
                WHERE pembayaran_hutang_id = $id
                AND data_status = 1
                ) tbl_a
            LEFT JOIN nd_pembelian tbl_b
            ON tbl_a.pembelian_id = tbl_b.id
            LEFT JOIN (
                SELECT sum(qty*harga_beli) as sisa_hutang, sum(jumlah_roll) as jumlah_roll, pembelian_id
                FROM nd_pembelian_detail t1
                GROUP BY pembelian_id
                ) tbl_c
            ON tbl_a.pembelian_id = tbl_c.pembelian_id
            LEFT JOIN (
                SELECT t2.id as po_pembelian_batch_id, po_pembelian_id, concat(po_number_lengkap,'-',batch,if(revisi > 1,concat('R',revisi-1),'')) as po_number_batch
                FROM view_po_pembelian t1
                LEFT JOIN nd_po_pembelian_batch t2
                ON t1.id = t2.po_pembelian_id
                ) tD
            ON tbl_b.po_pembelian_batch_id = tD.po_pembelian_batch_id
            ORDER BY tanggal, tbl_b.no_faktur 
            ", false);

        return $query->result();
    }

    function get_pembayaran_hutang_lain_detail($id){
        $query = $this->db->query("SELECT tbl_a.*, tbl_b.no_faktur, sisa_hutang, jatuh_tempo, tanggal, 0 as jumlah_roll
            FROM (
                SELECT *
                FROM nd_pembayaran_hutang_detail
                WHERE pembayaran_hutang_id = $id
                AND data_status = 11
                ) tbl_a
            LEFT JOIN nd_pembelian_lain tbl_b
            ON tbl_a.pembelian_id = tbl_b.id
            LEFT JOIN (
                SELECT sum(qty*harga_beli) as sisa_hutang, pembelian_lain_id
                FROM nd_pembelian_lain_detail
                GROUP BY pembelian_lain_id
                ) tbl_c
            ON tbl_a.pembelian_id = tbl_c.pembelian_lain_id
            ", false);

        return $query->result();
    }

    function get_pembayaran_hutang_awal_detail($id){
        $query = $this->db->query("SELECT tbl_a.*, tbl_b.no_faktur, tbl_b.amount as sisa_hutang, jatuh_tempo, tanggal, jumlah_roll
            FROM (
                SELECT *
                FROM nd_pembayaran_hutang_detail
                WHERE pembayaran_hutang_id = $id
                AND data_status = 2
                ) tbl_a
            LEFT JOIN nd_hutang_awal tbl_b
            ON tbl_a.pembelian_id = tbl_b.id
            ORDER BY tanggal, no_faktur asc
            ", false);

        return $query->result();
    }

    function get_pembayaran_hutang_nilai_info($pembayaran_hutang_id){
        $query = $this->db->query("SELECT t1.*, t3.nama_bank as nama_bank_giro, t3.no_rek_bank as no_rek_bank_giro
                FROM (
                    SELECT *
                    FROM nd_pembayaran_hutang_nilai
                    WHERE pembayaran_hutang_id = $pembayaran_hutang_id
                    ) t1
                LEFT JOIN nd_giro_list t2
                ON t1.giro_register_id = t2.id
                LEFT JOIN nd_bank_list t3
                ON t2.bank_list_id = t3.id
            ", false);

        return $query->result();
    }

    function get_periode_pembelian($pembayaran_hutang_id){
        $query = $this->db->query("SELECT MIN(tanggal) as tanggal_start, MAX(tanggal) as tanggal_end
            FROM nd_pembelian
            WHERE id in (
                SELECT pembelian_id
                FROM nd_pembayaran_hutang_detail
                WHERE pembayaran_hutang_id = $pembayaran_hutang_id
                )
            ", false);

        return $query->result();
    }

    function get_pembayaran_hutang_unbalance(){
        $query = $this->db->query("SELECT tbl_a.*, (ifnull(bayar,0)+ifnull(pembulatan,0)) - amount as balance, bayar, pembulatan, amount, tbl_b.amount as amount_bayar
                FROM (
                    SELECT a.id, b.nama as nama_supplier, c.nama as nama_toko, supplier_id, toko_id, pembulatan, DATE_FORMAT(created,'%Y-%m-%d') as tanggal_bayar
                    FROM nd_pembayaran_hutang a
                    LEFT JOIN nd_supplier b
                    ON a.supplier_id = b.id
                    LEFT JOIN nd_toko c
                    ON a.toko_id = c.id
                    ) tbl_a
                LEFT JOIN (
                    SELECT pembayaran_hutang_id, sum( amount * IF( data_status !=4, 1 , -1 )) as amount
                    FROM nd_pembayaran_hutang_detail
                    GROUP BY pembayaran_hutang_id
                    ) tbl_b
                ON tbl_a.id = tbl_b.pembayaran_hutang_id
                LEFT JOIN (
                    SELECT sum(amount) as bayar, pembayaran_hutang_id
                    FROM nd_pembayaran_hutang_nilai
                    GROUP BY pembayaran_hutang_id
                    ) tbl_c
                ON tbl_a.id = tbl_c.pembayaran_hutang_id
                WHERE  ifnull(bayar,0)+ifnull(pembulatan,0) - amount != 0

            ", false);

        return $query->result();
    }

     function get_pembayaran_hutang_unbalance_by_supplier($supplier_id){
        $query = $this->db->query("SELECT tbl_a.*, (ifnull(bayar,0)+ifnull(pembulatan,0)) - amount as balance, bayar, pembulatan, amount, tbl_b.amount as amount_bayar, tanggal_awal, tanggal_akhir, jml_faktur
                FROM (
                    SELECT a.id, b.nama as nama_supplier, c.nama as nama_toko, supplier_id, toko_id, pembulatan
                    FROM nd_pembayaran_hutang a
                    LEFT JOIN nd_supplier b
                    ON a.supplier_id = b.id
                    LEFT JOIN nd_toko c
                    ON a.toko_id = c.id
                    ) tbl_a
                LEFT JOIN (
                    SELECT pembayaran_hutang_id, sum(amount) as amount, sum(1) as jml_faktur, min(t2.tanggal) as tanggal_awal, max(t2.tanggal) as tanggal_akhir
                    FROM nd_pembayaran_hutang_detail t1
                    LEFT JOIN nd_pembelian t2
                    ON t1.pembelian_id = t2.id
                    GROUP BY pembayaran_hutang_id
                    ) tbl_b
                ON tbl_a.id = tbl_b.pembayaran_hutang_id
                LEFT JOIN (
                    SELECT sum(amount) as bayar, pembayaran_hutang_id
                    FROM nd_pembayaran_hutang_nilai
                    GROUP BY pembayaran_hutang_id
                    ) tbl_c
                ON tbl_a.id = tbl_c.pembayaran_hutang_id
                WHERE  ifnull(bayar,0)+ifnull(pembulatan,0) - amount != 0
                AND tbl_a.supplier_id = $supplier_id

            ", false);

        return $query->result();
    }

    function get_pembayaran_nilai($pembayaran_hutang_id){
        $query = $this->db->query("SELECT t1.*, if(giro_register_id != 0, t1.nama_bank, t1.nama_bank) as nama_bank, if(giro_register_id != 0, t3.no_rek_bank, t3.no_rek_bank) as no_rek_bank 
            FROM (
                SELECT *
                FROM nd_pembayaran_hutang_nilai
                WHERE pembayaran_hutang_id = $pembayaran_hutang_id
                ) t1
            LEFT JOIN nd_giro_list t2
            ON t1.giro_register_id = t2.id
            LEFT JOIN nd_bank_list t3
            ON t2.bank_list_id = t3.id
        ");
        return $query->result();
    }

    function get_pembayaran_hutang_nilai_last_10(){
        $query = $this->db->query("SELECT t1.*, t2.nama_bank as nama_bank_asal, t2.no_rek_bank as no_rek_bank_asal, tipe_trx_1, tipe_trx_2, t4.nama as nama_supplier, t5.nama as nama_toko, amount as amount_bayar
                FROM nd_pembayaran_hutang_nilai t1
                LEFT JOIN nd_bank_list t2
                ON t1.bank_list_id = t2.id
                LEFT JOIN nd_pembayaran_hutang t3
                ON t1.pembayaran_hutang_id = t3.id
                LEFT JOIN nd_supplier t4
                ON t3.supplier_id = t4.id
                LEFT JOIN nd_toko t5 
                ON t3.toko_id = t4.id
                ORDER BY t1.created desc
                LIMIT 10

            ", false);

        return $query->result();
    }

//=======================================retur beli=====================================

    function get_retur_list_detail($supplier_id){
        $query = $this->db->query("SELECT t1.*, t2.nama as supplier, concat('SJ/R/',if(t2.kode is not null, concat(t2.kode,'/'),'LL/'),DATE_FORMAT(t1.tanggal,'%y'),'-',no_sj ) as no_sj_lengkap
            FROM (
                (
                    SELECT tanggal, no_sj, supplier_id, total as total_beli, total_bayar, ifnull(total,0) - ifnull(total_bayar,0) as sisa_hutang, tbl_a.id, 1 as tipe, ockh_info as OCKH, keterangan1, keterangan2
                    FROM (
                        SELECT *
                        FROM nd_retur_beli
                        WHERE supplier_id = $supplier_id
                        AND status_aktif = 1
                        ) as tbl_a
                    LEFT JOIN nd_toko as tbl_b
                    ON tbl_a.toko_id = tbl_b.id
                    LEFT JOIN (
                        SELECT id, sum(qty) as qty, sum(jumlah_roll) as jumlah_roll, sum(qty*harga) as total, harga as harga_beli, retur_beli_id, barang_id, gudang_id
                        FROM nd_retur_beli_detail t1
                        LEFT JOIN (
                            SELECT sum(qty*if(jumlah_roll = 0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, retur_beli_detail_id
                            FROM nd_retur_beli_qty
                            GROUP BY retur_beli_detail_id    
                        ) t2
                        ON t2.retur_beli_detail_id = t1.id
                        group by retur_beli_id
                        ) as tbl_c
                    ON tbl_c.retur_beli_id = tbl_a.id
                    LEFT JOIN nd_gudang as tbl_d
                    ON tbl_c.gudang_id = tbl_d.id
                    LEFT JOIN (
                        SELECT sum(amount) as total_bayar, pembelian_id
                        FROM nd_pembayaran_hutang_detail
                        WHERE data_status = 4
                        GROUP BY pembelian_id
                        ) tbl_f
                    ON tbl_a.id = tbl_f.pembelian_id
                    WHERE ifnull(total_bayar,0) - ifnull(total,0) < 0
                )
            ) t1
            LEFT JOIN nd_supplier as t2
            ON t2.id = t1.supplier_id
            ", false);

        return $query->result();
    }

    function get_pembayaran_retur_beli_detail($id){
        $query = $this->db->query("SELECT tA.*, concat('SJ/R/',if(tD.kode is not null, concat(tD.kode,'/'),'LL/'),DATE_FORMAT(tB.tanggal,'%y'),'-',no_sj ) as no_sj_lengkap, tanggal, jumlah_roll, total as sisa_hutang, keterangan2
            FROM (
                SELECT *
                FROM nd_pembayaran_hutang_detail
                WHERE pembayaran_hutang_id = $id
                AND data_status = 4
                ) tA
            LEFT JOIN nd_retur_beli tB
            ON tA.pembelian_id = tB.id
            LEFT JOIN (
                SELECT harga as harga_beli ,id, retur_beli_id, retur_beli_detail_id, sum(qty * harga) as total, jumlah_roll, qty
                FROM nd_retur_beli_detail t1
                LEFT JOIN (
                    SELECT sum(qty * if(jumlah_roll = 0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, retur_beli_detail_id
                    FROM nd_retur_beli_qty
                    GROUP BY retur_beli_detail_id
                    ) t2
                ON t1.id = t2.retur_beli_detail_id 
                GROUP BY retur_beli_id
                ) tC
            ON tC.retur_beli_id = tB.id
            LEFT JOIN nd_supplier tD
            ON tB.supplier_id = tD.id
            order by tanggal asc
            ", false);

        return $query->result();
    }

    function get_retur_beli_list_by_date($from, $to, $toko_id, $supplier_id){
        $query = $this->db->query("SELECT tbl_a.status_aktif, concat('SJ/R/',if(tbl_e.kode is not null, concat(tbl_e.kode,'/'),'LL/'),DATE_FORMAT(tbl_a.tanggal,'%y'),'-',no_sj ) as no_sj_lengkap , supplier_id, total as total_beli, total_bayar, ifnull(total,0) - ifnull(total_bayar,0) as sisa_hutang, tbl_a.id as pembelian_id, jumlah_roll, tanggal, 4 as data_status, keterangan2
                FROM (
                    SELECT *
                    FROM nd_retur_beli
                    WHERE supplier_id = $supplier_id
                    AND toko_id = $toko_id
                    AND status_aktif = 1
                    ) as tbl_a
                LEFT JOIN nd_toko as tbl_b
                ON tbl_a.toko_id = tbl_b.id
                LEFT JOIN (
                    SELECT id, harga as harga_beli, retur_beli_id, barang_id, sum(qty * harga) as total, jumlah_roll, gudang_id
                    FROM nd_retur_beli_detail t1
                    LEFT JOIN (
                        SELECT sum(qty*if(jumlah_roll=0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, retur_beli_detail_id
                        FROM nd_retur_beli_qty
                        GROUP BY retur_beli_detail_id
                        ) t2
                    ON t1.id = t2.retur_beli_detail_id
                    group by retur_beli_id
                    ) as tbl_c
                ON tbl_c.retur_beli_id = tbl_a.id
                LEFT JOIN nd_gudang as tbl_d
                ON tbl_c.gudang_id = tbl_d.id
                LEFT JOIN nd_supplier as tbl_e
                ON tbl_e.id = tbl_a.supplier_id
                LEFT JOIN (
                    SELECT sum(amount) as total_bayar, pembelian_id
                    FROM nd_pembayaran_hutang_detail
                    WHERE data_status = 4
                    GROUP BY pembelian_id
                    ) tbl_f
                ON tbl_a.id = tbl_f.pembelian_id
                WHERE ifnull(total,0) - ifnull(total_bayar,0) > 0
                ORDER by tanggal asc
            ", false);

        return $query->result();
    }

//====================================piutang awal======================================
    
    function get_piutang_awal(){
        $query = $this->db->query("SELECT tA.*, amount_kontra, amount_bayar, pembayaran_type_id, tanggal_bayar
            FROM (
                SELECT tbl_a.*, amount, jumlah_nota
                    FROM nd_customer as tbl_a
                    LEFT JOIN (
                        SELECT customer_id, sum(1) as jumlah_nota, sum(ifnull(amount,0)) as amount, id
                        FROM nd_piutang_awal
                        GROUP BY customer_id
                        ) as tbl_b
                    ON tbl_b.customer_id = tbl_a.id
                    WHERE tbl_b.id is not null
            )tA
            LEFT JOIN (
                SELECT customer_id, sum(amount_kontra) as amount_kontra, group_concat(amount_bayar) as amount_bayar, group_concat(pembayaran_type_id) as pembayaran_type_id, penjualan_id, group_concat(tanggal_bayar) as tanggal_bayar
                FROM (
                    SELECT sum(amount) as amount_kontra, penjualan_id, pembayaran_piutang_id
                    FROM nd_pembayaran_piutang_detail
                    WHERE data_status = 2
                    GROUP BY pembayaran_piutang_id
                    ) t1
                LEFT JOIN nd_pembayaran_piutang t2
                ON t1.pembayaran_piutang_id = t2.id
                LEFT JOIN (
                    SELECT group_concat(pembayaran_type_id) as pembayaran_type_id, group_concat(amount) as amount_bayar, pembayaran_piutang_id, group_concat(tanggal_transfer) as tanggal_bayar
                    FROM nd_pembayaran_piutang_nilai
                    GROUP BY pembayaran_piutang_id
                    ) t3
                ON t3.pembayaran_piutang_id = t1.pembayaran_piutang_id
                WHERE t2.id is not null
                AND status_aktif = 1
                GROUP BY customer_id
            )tB
            ON tA.id = tB.customer_id
            ", false);

        return $query->result();
    }

    function get_piutang_awal_detail($customer_id){
        $query = $this->db->query("SELECT tA.*, amount_kontra, pembayaran_piutang_id, tanggal_bayar, tanggal_bayar, amount_bayar, pembayaran_type_id
            FROM (
                SELECT *
                FROM nd_piutang_awal
                WHERE customer_id = $customer_id
            )tA
            LEFT JOIN (
                SELECT customer_id, amount as amount_kontra, amount_bayar, pembayaran_type_id, penjualan_id, t1.pembayaran_piutang_id, tanggal_bayar
                FROM (
                    SELECT *
                    FROM nd_pembayaran_piutang_detail
                    WHERE data_status = 2
                    GROUP BY pembayaran_piutang_id
                    ) t1
                LEFT JOIN (
                    SELECT *
                    FROM nd_pembayaran_piutang
                    WHERE customer_id = $customer_id
                    AND status_aktif = 1
                    ) t2
                ON t1.pembayaran_piutang_id = t2.id
                LEFT JOIN (
                    SELECT group_concat(pembayaran_type_id) as pembayaran_type_id, group_concat(amount) as amount_bayar, pembayaran_piutang_id, group_concat(tanggal_transfer) as tanggal_bayar
                    FROM nd_pembayaran_piutang_nilai
                    GROUP BY pembayaran_piutang_id
                    ) t3
                ON t3.pembayaran_piutang_id = t1.pembayaran_piutang_id
                WHERE t2.id is not null
            )tB
            ON tA.id = tB.penjualan_id
            ", false);

        return $query->result();}

//==============================piutang=========================================================

    function get_piutang_list(){
        $query = $this->db->query("SELECT tbl_a.status_aktif, ifnull(tbl_c.nama,'no name') as nama_customer, sum(ifnull(total_bayar,0) - (ifnull(g_total,0) - ifnull(diskon,0)) + ongkos_kirim) as sisa_piutang, concat_ws('??',tbl_a.id,no_faktur) as data, if(tbl_a.status = -1,-1,0) as status, customer_id, MIN(tanggal) as tanggal_start, MAX(tanggal) as tanggal_end
                FROM (
                    SELECT *
                    FROM vw_penjualan_data 
                    WHERE status_aktif = 1
                    AND customer_id is not null
                    ORDER BY tanggal desc
                    )as tbl_a
                LEFT JOIN (
                    SELECT sum(qty *nd_penjualan_detail.harga_jual) - ifnull(total_bayar,0) as g_total, nd_penjualan_detail.penjualan_id 
                    FROM nd_penjualan_detail
                    LEFT JOIN (
                        SELECT sum(qty * if(jumlah_roll=0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, penjualan_detail_id
                        FROM nd_penjualan_qty_detail
                        group by penjualan_detail_id
                        ) as nd_penjualan_qty_detail
                    ON nd_penjualan_qty_detail.penjualan_detail_id = nd_penjualan_detail.id
                    LEFT JOIN (
                        SELECT sum(amount) as total_bayar, penjualan_id
                        FROM nd_pembayaran_penjualan
                        WHERE pembayaran_type_id != 5
                        GROUP by penjualan_id
                    ) nd_pembayaran_penjualan
                    ON nd_penjualan_detail.penjualan_id = nd_pembayaran_penjualan.penjualan_id
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
                WHERE ifnull(total_bayar,0) - (ifnull(g_total,0) - ifnull(diskon,0)) + ongkos_kirim < 0
                group by customer_id
            ", false);

        return $query->result();
    }

    function get_piutang_list_by_customer($customer_id){
        
        $query = $this->db->query("SELECT t1.customer_id, t2.nama as nama_customer, t3.nama as nama_toko, sum(sisa_piutang) as sisa_piutang, MIN(tanggal_start) as tanggal_start, MAX(tanggal_end) as tanggal_end, toko_id, null as jml_trx
            FROM (
                (
                    SELECT tbl_a.status_aktif,sum(ifnull(g_total,0)) - sum(ifnull(diskon,0)) + sum(ongkos_kirim) - sum(ifnull(total_bayar,0)) - sum(ifnull(amount_bayar,0)) as sisa_piutang, concat_ws('??',tbl_a.id,no_faktur_lengkap) as data, if(tbl_a.status = -1,-1,0) as status, customer_id, MIN(tanggal) as tanggal_start, MAX(tanggal) as tanggal_end, tbl_a.toko_id, 1 as tipe
                    FROM (
                        SELECT *
                        FROM vw_penjualan_data 
                        WHERE status_aktif = 1
                        AND penjualan_type_id != 3
                        AND no_faktur != ''
                        AND customer_id = $customer_id
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
                    WHERE customer_id = $customer_id
                    GROUP BY customer_id, toko_id
                )UNION(
                    SELECT tbl_a.status_aktif,(sum(ifnull(g_total,0)) - sum(ifnull(total_bayar,0)) - sum(ifnull(amount_bayar,0))) *-1 as sisa_piutang, concat_ws('??',tbl_a.id,no_faktur_lengkap) as data, if(tbl_a.status = -1,-1,0) as status, customer_id, MIN(tanggal) as tanggal_start, MAX(tanggal) as tanggal_end, tbl_a.toko_id, 3 as tipe
                    FROM (
                        SELECT *, concat('FRJ', DATE_FORMAT(tanggal,'%d%m%y'),'-',LPAD(no_faktur,3,'0')) as no_faktur_lengkap
                        FROM nd_retur_jual 
                        WHERE status_aktif = 1
                        AND retur_type_id != 3
                        AND no_faktur != ''
                        AND customer_id = $customer_id
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
            ) t1
            LEFT JOIN nd_customer as t2
            ON t1.customer_id = t2.id
            LEFT JOIN nd_toko t3
            ON t1.toko_id = t3.id
            WHERE sisa_piutang != 0
            GROUP BY t1.customer_id
            ORDER BY t2.nama asc"
            , false);

        return $query->result();
    }

    function get_piutang_list_all_legacy(){
        
        $query = $this->db->query("SELECT outstanding, t1.customer_id, t2.nama as nama_customer, t3.nama as nama_toko, 
            sum(ifnull(sisa_piutang,0)) as sisa_piutang, sum(ifnull(sisa_kontra_bon,0)) as kontra_bon, MIN(tanggal_start) as tanggal_start, 
            MAX(tanggal_end) as tanggal_end, toko_id, null as jml_trx
            FROM (
                (
                    SELECT tbl_a.status_aktif,
                        sum(ifnull(g_total,0)) - sum(ifnull(diskon,0)) + sum(ongkos_kirim) - sum(ifnull(total_bayar,0)) - sum(ifnull(amount_bayar,0)) as sisa_piutang, 
                        0 as sisa_kontra_bon,
                        concat_ws('??',tbl_a.id,no_faktur_lengkap) as data, if(tbl_a.status = -1,-1,0) as status, 
                        customer_id, MIN(tanggal) as tanggal_start, MAX(tanggal) as tanggal_end, tbl_a.toko_id, 1 as tipe
                    FROM (
                        SELECT *, no_faktur as no_faktur_lengkap
                        -- FROM vw_penjualan_data 
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
                    SELECT 1, sum(ifnull(amount,0) - ifnull(total_bayar,0)) as sisa_piutang, 0,
                    concat_ws('??',id,no_faktur) as data, 1, customer_id, MIN(tanggal) as tanggal_start, MAX(tanggal) as tanggal_end, toko_id, 2 as tipe
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
                    SELECT tbl_a.status_aktif,(sum(ifnull(g_total,0)) - sum(ifnull(total_bayar,0)) - sum(ifnull(amount_bayar,0))) *-1 as sisa_piutang,0,
                    concat_ws('??',tbl_a.id,no_faktur_lengkap) as data, if(tbl_a.status = -1,-1,0) as status, customer_id, MIN(tanggal) as tanggal_start, MAX(tanggal) as tanggal_end, tbl_a.toko_id, 3 as tipe
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
                )UNION(
                    SELECT tA.status_aktif, 0, piutang - ifnull(bayar,0) - ifnull(pembulatan,0) as sisa_kontra_bon, 
                    tA.id, 2 as status, customer_id, (tanggal_kontra), (tanggal_kontra), 1 as toko_id, 4 as tipe
                    FROM nd_pembayaran_piutang tA
                    LEFT JOIN (
                        SELECT sum(amount) as piutang, pembayaran_piutang_id
                        FROM nd_pembayaran_piutang_detail
                        GROUP BY pembayaran_piutang_id
                    )tB
                    ON tA.id = tB.pembayaran_piutang_id
                    LEFT JOIN (
                        SELECT sum(amount) as bayar, pembayaran_piutang_id
                        FROM nd_pembayaran_piutang_nilai
                        GROUP BY pembayaran_piutang_id
                    )tC
                    ON tA.id = tC.pembayaran_piutang_id
                    WHERE piutang - ifnull(bayar,0) > 0
                )
            ) t1
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
                ) t4
            ON t1.customer_id = t4.customer_id
            LEFT JOIN nd_customer as t2
            ON t1.customer_id = t2.id
            LEFT JOIN nd_toko t3
            ON t1.toko_id = t3.id
            WHERE sisa_piutang != 0
            OR outstanding != 0
            OR sisa_kontra_bon != 0
            GROUP BY t1.customer_id
            ORDER BY t2.nama asc", false);

        return $query->result();
    }

    function get_piutang_list_all(){
        
        $query = $this->db->query("SELECT outstanding, t1.customer_id, t2.nama as nama_customer, t3.nama as nama_toko, 
            sum(ifnull(sisa_piutang,0)) as sisa_piutang, sum(ifnull(sisa_kontra_bon,0)) as kontra_bon, MIN(tanggal_start) as tanggal_start, 
            MAX(tanggal_end) as tanggal_end, toko_id, null as jml_trx
            FROM (
                (
                    SELECT tbl_a.status_aktif,
                        sum(ifnull(g_total,0)) - sum(ifnull(diskon,0)) + sum(ongkos_kirim) - sum(ifnull(total_bayar,0)) - sum(ifnull(amount_bayar,0)) as sisa_piutang, 
                        0 as sisa_kontra_bon,
                        concat_ws('??',tbl_a.id,no_faktur_lengkap) as data, if(tbl_a.status = -1,-1,0) as status, 
                        customer_id, MIN(tanggal) as tanggal_start, MAX(tanggal) as tanggal_end, tbl_a.toko_id, 1 as tipe
                    FROM (
                        SELECT *, no_faktur as no_faktur_lengkap
                        -- FROM vw_penjualan_data 
                        FROM nd_penjualan 
                        WHERE status_aktif = 1
                        AND penjualan_type_id != 3
                        AND no_faktur != ''
                        ORDER BY tanggal desc
                        )as tbl_a
                    LEFT JOIN (
                        SELECT sum(subqty *nd_penjualan_detail.harga_jual) as g_total, penjualan_id 
                        FROM nd_penjualan_detail
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
                    SELECT 1, sum(ifnull(amount,0) - ifnull(total_bayar,0)) as sisa_piutang, 0,
                    concat_ws('??',id,no_faktur) as data, 1, customer_id, MIN(tanggal) as tanggal_start, MAX(tanggal) as tanggal_end, toko_id, 2 as tipe
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
                    SELECT tbl_a.status_aktif,(sum(ifnull(g_total,0)) - sum(ifnull(total_bayar,0)) - sum(ifnull(amount_bayar,0))) *-1 as sisa_piutang,0,
                    concat_ws('??',tbl_a.id,no_faktur_lengkap) as data, if(tbl_a.status = -1,-1,0) as status, customer_id, MIN(tanggal) as tanggal_start, MAX(tanggal) as tanggal_end, tbl_a.toko_id, 3 as tipe
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
                )UNION(
                    SELECT tA.status_aktif, 0, piutang - ifnull(bayar,0) - ifnull(pembulatan,0) as sisa_kontra_bon, 
                    tA.id, 2 as status, customer_id, (tanggal_kontra), (tanggal_kontra), 1 as toko_id, 4 as tipe
                    FROM nd_pembayaran_piutang tA
                    LEFT JOIN (
                        SELECT sum(amount) as piutang, pembayaran_piutang_id
                        FROM nd_pembayaran_piutang_detail
                        GROUP BY pembayaran_piutang_id
                    )tB
                    ON tA.id = tB.pembayaran_piutang_id
                    LEFT JOIN (
                        SELECT sum(amount) as bayar, pembayaran_piutang_id
                        FROM nd_pembayaran_piutang_nilai
                        GROUP BY pembayaran_piutang_id
                    )tC
                    ON tA.id = tC.pembayaran_piutang_id
                    WHERE piutang - ifnull(bayar,0) > 0
                )
            ) t1
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
                ) t4
            ON t1.customer_id = t4.customer_id
            LEFT JOIN nd_customer as t2
            ON t1.customer_id = t2.id
            LEFT JOIN nd_toko t3
            ON t1.toko_id = t3.id
            WHERE sisa_piutang != 0
            OR outstanding != 0
            OR sisa_kontra_bon != 0
            GROUP BY t1.customer_id
            ORDER BY t2.nama asc", false);

        return $query->result();
    }

    function get_piutang_other($toko_id, $customer_id, $pembayaran_piutang_id){

        $query = $this->db->query("SELECT tbl_a.status_aktif, no_faktur_lengkap as no_faktur, customer_id, total as total_jual, 0 as amount, amount_bayar, ifnull(total,0) - ifnull(amount,0) - ifnull(amount_bayar, 0) as sisa_piutang, tbl_a.id as penjualan_id, new_jatuh_tempo as jatuh_tempo
                FROM (
                    SELECT *, 
                    if(jatuh_tempo = tanggal, DATE_ADD(jatuh_tempo, INTERVAL 60 DAY), jatuh_tempo ) as new_jatuh_tempo
                    FROM vw_penjualan_data
                    WHERE customer_id = $customer_id
                    AND toko_id = $toko_id
                    AND status_aktif = 1
                    AND penjualan_type_id != 3
                    AND no_faktur != ''
                    ) as tbl_a
                LEFT JOIN (
                    SELECT id, sum(qty) as qty, sum(jumlah_roll) as jumlah_roll, sum(qty*harga_jual) as total, harga_jual, penjualan_id, barang_id, satuan_id
                    FROM nd_penjualan_detail
                    LEFT JOIN (
                        SELECT sum(qty*if(jumlah_roll=0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, penjualan_detail_id
                        FROM nd_penjualan_qty_detail
                        GROUP BY penjualan_detail_id
                    ) nd_penjualan_qty_detail
                    ON nd_penjualan_detail.id = nd_penjualan_qty_detail.penjualan_detail_id
                    group by penjualan_id
                    ) as tbl_c
                ON tbl_c.penjualan_id = tbl_a.id
                LEFT JOIN (
                    SELECT sum(amount) as amount, penjualan_id
                    FROM nd_pembayaran_piutang_detail t1
                    LEFT JOIN nd_pembayaran_piutang t2
                    ON t1.pembayaran_piutang_id = t2.id
                    WHERE data_status = 1
                    AND status_aktif = 1
                    GROUP BY penjualan_id
                    ) tbl_f
                ON tbl_a.id = tbl_f.penjualan_id
                LEFT JOIN (
                    SELECT sum(amount) as amount_bayar, penjualan_id
                    FROM nd_pembayaran_penjualan
                    WHERE pembayaran_type_id != 5
                    GROUP BY penjualan_id
                ) tbl_g
                ON tbl_a.id = tbl_g.penjualan_id
                LEFT JOIN (
                    SELECT penjualan_id, pembayaran_piutang_id, id
                    FROM nd_pembayaran_piutang_detail
                    WHERE pembayaran_piutang_id = $pembayaran_piutang_id
                    ) tbl_h
                ON tbl_a.id = tbl_h.penjualan_id
                WHERE ifnull(total,0) - ifnull(amount,0) - ifnull(amount_bayar, 0) > 0
                AND tbl_h.id is null
            ", false);

        return $query->result();
    }

    function get_pembayaran_piutang_unbalance(){
        $query = $this->db->query("SELECT tbl_a.*, (ifnull(bayar,0)+ifnull(pembulatan,0)) - amount + minus_amount as balance
                FROM (
                    SELECT a.id, b.nama as nama_customer, c.nama as nama_toko, customer_id, toko_id, pembulatan
                    FROM nd_pembayaran_piutang a
                    LEFT JOIN nd_customer b
                    ON a.customer_id = b.id
                    LEFT JOIN nd_toko c
                    ON a.toko_id = c.id
                    ) tbl_a
                LEFT JOIN (
                    SELECT pembayaran_piutang_id, sum(if(data_status != 3 , amount,0)) as amount, sum(if(data_status = 3 , amount,0)) as minus_amount
                    FROM nd_pembayaran_piutang_detail
                    GROUP BY pembayaran_piutang_id
                    ) tbl_b
                ON tbl_a.id = tbl_b.pembayaran_piutang_id
                LEFT JOIN (
                    SELECT sum(amount) as bayar, pembayaran_piutang_id
                    FROM nd_pembayaran_piutang_nilai
                    GROUP BY pembayaran_piutang_id
                    ) tbl_c
                ON tbl_a.id = tbl_c.pembayaran_piutang_id
                WHERE  ifnull(bayar,0)+ifnull(pembulatan,0) - amount + minus_amount != 0

            ", false);

        return $query->result();
    }


    function get_piutang_list_by_date($tanggal_start, $tanggal_end, $toko_id, $customer_id, $cond_jt){
        $query = $this->db->query("SELECT tbl_a.status_aktif, no_faktur_lengkap as no_faktur, 
            tbl_e.nama as customer, customer_id, total as total_jual, 0 as amount, amount_bayar, 
            ifnull(total,0) - ifnull(amount,0) - ifnull(amount_bayar, 0) as sisa_piutang, 
            tbl_a.id as penjualan_id, new_jatuh_tempo as jatuh_tempo, tanggal
                FROM (
                    SELECT t1.*, 
                    if(jatuh_tempo = tanggal, DATE_ADD(jatuh_tempo, INTERVAL 60 DAY), jatuh_tempo ) as new_jatuh_tempo,
                    if((t1.tanggal < '2022-05-01 00:00:00'),concat('FPJ',convert(date_format(t1.tanggal,'%d%m%y') using latin1),'-',ifnull(t2.pre_faktur,''),
                    convert(lpad(t1.no_faktur,5,'0') using latin1)),concat(t2.pre_po,':PJ01/',
                    convert(date_format(t1.tanggal,'%y%m') using latin1),'/',
                    convert(lpad(t1.no_faktur,4,'0') using latin1))) AS no_faktur_lengkap
                    FROM (
                        SELECT *
                        FROM nd_penjualan
                        WHERE tanggal >= '$tanggal_start'
                        AND tanggal <= '$tanggal_end'
                        AND customer_id = $customer_id
                        AND toko_id = $toko_id
                        AND status_aktif = 1
                        AND penjualan_type_id != 3
                        AND no_faktur != ''
                    )t1
                    LEFT JOIN nd_toko t2
                    ON t1.toko_id = t2.id
                    ) as tbl_a
                LEFT JOIN nd_toko as tbl_b
                ON tbl_a.toko_id = tbl_b.id
                LEFT JOIN (
                    SELECT id, sum(qty) as qty, sum(jumlah_roll) as jumlah_roll, sum(qty*harga_jual) as total, harga_jual, penjualan_id, barang_id, satuan_id
                    FROM nd_penjualan_detail
                    LEFT JOIN (
                        SELECT sum(qty*if(jumlah_roll=0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, penjualan_detail_id
                        FROM nd_penjualan_qty_detail
                        GROUP BY penjualan_detail_id
                    ) nd_penjualan_qty_detail
                    ON nd_penjualan_detail.id = nd_penjualan_qty_detail.penjualan_detail_id
                    group by penjualan_id
                    ) as tbl_c
                ON tbl_c.penjualan_id = tbl_a.id
                LEFT JOIN nd_gudang as tbl_d
                ON tbl_a.gudang_id = tbl_d.id
                LEFT JOIN nd_customer as tbl_e
                ON tbl_e.id = tbl_a.customer_id
                LEFT JOIN (
                    SELECT sum(amount) as amount, penjualan_id
                    FROM nd_pembayaran_piutang_detail
                    WHERE data_status = 1
                    GROUP BY penjualan_id
                    ) tbl_f
                ON tbl_a.id = tbl_f.penjualan_id
                LEFT JOIN (
                    SELECT sum(amount) as amount_bayar, penjualan_id
                    FROM nd_pembayaran_penjualan
                    WHERE pembayaran_type_id != 5
                    GROUP BY penjualan_id
                ) tbl_g
                ON tbl_a.id = tbl_g.penjualan_id
                WHERE ifnull(total,0) - ifnull(amount,0) - ifnull(amount_bayar, 0) > 0
                $cond_jt
                ORDER BY tbl_a.tanggal, no_faktur
            ", false);

        return $query->result();
    }

    function get_piutang_awal_by_date($tanggal_start, $tanggal_end, $toko_id, $customer_id){
        $query = $this->db->query("SELECT 1, no_faktur, nama as customer, customer_id, amount as total_jual, 0 as amount, ifnull(amount,0) - 0 as sisa_piutang, a.id as penjualan_id, jatuh_tempo, tanggal
                FROM (
                    SELECT *, concat('FPJ', DATE_FORMAT(tanggal,'%d%m%y'),'-',$this->pre_faktur,LPAD(no_faktur,5,'0')) as no_faktur_lengkap
                    FROM nd_piutang_awal
                    WHERE tanggal >= '$tanggal_start'
                    AND tanggal <= '$tanggal_end'
                    AND customer_id = $customer_id
                    -- AND toko_id = $toko_id
                ) a
                LEFT JOIN nd_customer b
                ON a.customer_id = b.id
                LEFT JOIN (
                    SELECT sum(amount) as bayar, penjualan_id
                    FROM nd_pembayaran_piutang_detail
                    WHERE data_status = 2
                    GROUP BY penjualan_id
                    ) c
                ON a.id = c.penjualan_id
                WHERE ifnull(amount,0) - ifnull(bayar,0) > 0
                ORDER BY tanggal
                    
            ", false);

        return $query->result();
    }

    function get_retur_list_by_date($tanggal_start, $tanggal_end, $toko_id, $customer_id, $cond_jt){
        $query = $this->db->query("SELECT tbl_a.status_aktif, no_faktur_lengkap as no_faktur,no_faktur_penjualan, tbl_e.nama as customer, customer_id, total as total_jual, 0 as amount, amount_bayar, ifnull(total,0) - ifnull(amount,0) - ifnull(amount_bayar, 0) as sisa_piutang, tbl_a.id as penjualan_id, tanggal as jatuh_tempo, tbl_a.id as penjualan_id
                FROM (
                    SELECT t1.*, t2.no_faktur_lengkap as no_faktur_penjualan
                    FROM (
                        SELECT *, concat('FRJ', DATE_FORMAT(tanggal,'%d%m%y'),'-',LPAD(no_faktur,4,'0')) as no_faktur_lengkap
                        FROM nd_retur_jual
                        WHERE tanggal >= '$tanggal_start'
                        AND tanggal <= '$tanggal_end'
                        AND customer_id = $customer_id
                        AND toko_id = $toko_id
                        AND status_aktif = 1
                        AND retur_type_id != 3
                        AND no_faktur != ''
                        ) t1
                    LEFT JOIN vw_penjualan_data t2
                    ON t1.penjualan_id = t2.id
                    ) as tbl_a
                LEFT JOIN nd_toko as tbl_b
                ON tbl_a.toko_id = tbl_b.id
                LEFT JOIN (
                    SELECT id, sum(qty) as qty, sum(jumlah_roll) as jumlah_roll, sum(qty*harga) as total, harga as harga_jual, retur_jual_id, barang_id
                    FROM nd_retur_jual_detail t1
                    LEFT JOIN (
                        SELECT sum(qty*if(jumlah_roll=0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, retur_jual_detail_id
                        FROM nd_retur_jual_qty
                        GROUP BY retur_jual_detail_id
                    ) t2
                    ON t1.id = t2.retur_jual_detail_id
                    group by retur_jual_id
                    ) as tbl_c
                ON tbl_c.retur_jual_id = tbl_a.id
                LEFT JOIN nd_customer as tbl_e
                ON tbl_e.id = tbl_a.customer_id
                LEFT JOIN (
                    SELECT sum(amount) as amount, penjualan_id
                    FROM nd_pembayaran_piutang_detail
                    WHERE data_status = 3
                    GROUP BY penjualan_id
                    ) tbl_f
                ON tbl_a.id = tbl_f.penjualan_id
                LEFT JOIN (
                    SELECT sum(amount) as amount_bayar, retur_jual_id
                    FROM nd_pembayaran_retur
                    WHERE pembayaran_type_id != 5
                    GROUP BY retur_jual_id
                ) tbl_g
                ON tbl_a.id = tbl_g.retur_jual_id
                WHERE ifnull(total,0) - ifnull(amount,0) - ifnull(amount_bayar, 0) > 0
            ", false);

        return $query->result();
    }

    function get_piutang_list_by_tgl_trx($tanggal){
        $query = $this->db->query("SELECT *
            FROM (
                SELECT outstanding, t1.customer_id, t2.nama as nama_customer, t3.nama as nama_toko, sum(sisa_piutang) - ifnull(total_bayar,0) as sisa_piutang, MIN(tanggal_start) as tanggal_start, MAX(tanggal_end) as tanggal_end, toko_id, sum(1) as jml_trx
                FROM (
                    (
                        SELECT status_aktif, sisa_piutang, data, status, t1.customer_id, tanggal_start, tanggal_end, toko_id, tipe
                        FROM( 
                            SELECT tbl_a.status_aktif,sum(ifnull(g_total,0)) - sum(ifnull(diskon,0)) + sum(ongkos_kirim) - sum(ifnull(amount_bayar,0)) as sisa_piutang, concat_ws('??',tbl_a.id,no_faktur_lengkap) as data, if(tbl_a.status = -1,-1,0) as status, customer_id, MIN(tanggal) as tanggal_start, MAX(tanggal) as tanggal_end, tbl_a.toko_id, 1 as tipe
                            FROM (
                                SELECT *
                                FROM vw_penjualan_data 
                                WHERE status_aktif = 1
                                AND penjualan_type_id = 2
                                AND no_faktur != ''
                                AND tanggal <= '$tanggal'
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
                                SELECT sum(amount) as amount_bayar, penjualan_id
                                FROM nd_pembayaran_penjualan
                                WHERE pembayaran_type_id != 5
                                GROUP BY penjualan_id
                            ) tbl_g
                            ON tbl_a.id = tbl_g.penjualan_id
                            WHERE ifnull(g_total,0) - ifnull(diskon,0) + ongkos_kirim - ifnull(amount_bayar,0) > 0
                            group by customer_id, toko_id
                        )t1            
                    )UNION(
                        SELECT 1, sum(ifnull(amount,0)) as sisa_piutang, concat_ws('??',id,no_faktur) as data, 1, customer_id, MIN(tanggal) as tanggal_start, MAX(tanggal) as tanggal_end, toko_id, 2 as tipe
                        FROM nd_piutang_awal a
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
                ) t1
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
                    ) t4
                ON t1.customer_id = t4.customer_id
                LEFT JOIN nd_customer as t2
                ON t1.customer_id = t2.id
                LEFT JOIN nd_toko t3
                ON t1.toko_id = t3.id
                LEFT JOIN (
                    SELECT customer_id, sum(amount + pembulatan) as total_bayar
                    FROM (
                        SELECT pembayaran_piutang_id, sum(amount) as amount
                        FROM nd_pembayaran_piutang_nilai
                        WHERE tanggal_transfer <= '$tanggal'
                        GROUP BY pembayaran_piutang_id
                        ) a
                    LEFT JOIN (
                        SELECT *
                        FROM nd_pembayaran_piutang
                        WHERE status_aktif = 1
                        ) b
                    ON a.pembayaran_piutang_id = b.id
                    WHERE b.id is not null
                    GROUP BY customer_id
                    ) as t5
                ON t1.customer_id = t5.customer_id
                WHERE sisa_piutang != 0
                OR outstanding != 0
                GROUP BY t1.customer_id
            )result
            WHERE sisa_piutang > 0
            ORDER BY nama_customer asc
            ", false);

        return $query->result();
    }

    function get_piutang_list_detail($customer_id){
        $query = $this->db->query("SELECT tResult.*
            FROM (
                (
                    SELECT tA.id, no_faktur, tA.tanggal, g_total as total, pembayaran_piutang_id
                    FROM (
                        SELECT t1.id, if (penjualan_type_id = 0,'',no_faktur_lengkap) as no_faktur, tanggal, g_total
                        FROM (
                            SELECT *
                            FROM nd_penjualan
                            WHERE customer_id=$customer_id
                        ) t1
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
                        ) t2
                        ON t2.penjualan_id = t1.id                 
                    )tA
                    LEFT JOIN (
                            SELECT pembayaran_piutang_id, penjualan_id, amount
                            FROM nd_pembayaran_piutang_detail t1
                            LEFT JOIN (
                                SELECT *
                                FROM nd_pembayaran_piutang
                                WHERE customer_id = $customer_id
                                AND status_aktif = 1
                                ) t2
                            ON t1.pembayaran_piutang_id = t2.id
                            WHERE t2.id is not null
                            AND data_status = 1
                        )tB
                    ON tB.penjualan_id = tA.id
                )UNION(
                    SELECT tA.id, no_faktur, tA.tanggal, tA.amount, pembayaran_piutang_id
                    FROM (
                        SELECT *
                        FROM nd_piutang_awal
                        WHERE customer_id=$customer_id
                        ) tA
                    LEFT JOIN (
                        SELECT pembayaran_piutang_id, penjualan_id, amount
                        FROM nd_pembayaran_piutang_detail t1
                        LEFT JOIN (
                            SELECT *
                            FROM nd_pembayaran_piutang
                            WHERE customer_id = $customer_id
                            AND status_aktif = 1
                            ) t2
                        ON t1.pembayaran_piutang_id = t2.id
                        WHERE t2.id is not null
                        AND data_status = 2
                    )tB
                    ON tB.penjualan_id = tA.id
                )
            )tResult
            LEFT JOIN (
                SELECT t1.pembayaran_piutang_id, amount_piutang - ifnull(amount_bayar,0) - ifnull(pembulatan,0) as sisa_piutang
                FROM (
                    SELECT sum(amount) as amount_piutang, pembayaran_piutang_id
                    FROM nd_pembayaran_piutang_detail
                    GROUP BY pembayaran_piutang_id
                    )t1
                LEFT JOIN nd_pembayaran_piutang t2
                ON t1.pembayaran_piutang_id = t2.id
                LEFT JOIN (
                    SELECT sum(amount) as amount_bayar, pembayaran_piutang_id
                    FROM nd_pembayaran_piutang_nilai
                    GROUP BY pembayaran_piutang_id
                    )t3
                ON t1.pembayaran_piutang_id = t3.pembayaran_piutang_id
                WHERE amount_piutang - ifnull(amount_bayar,0) - ifnull(pembulatan,0) = 0
                AND customer_id = $customer_id
            )tB
            ON tResult.pembayaran_piutang_id = tB.pembayaran_piutang_id
            WHERE tB.pembayaran_piutang_id is null
            ORDER BY tanggal asc
            ");

        return $query->result();
    }

    function get_piutang_balance($customer_id, $tanggal){

        $query = $this->db->query("SELECT tbl_a.*, (ifnull(bayar,0)+ifnull(pembulatan,0)) , amount 
                FROM (
                    SELECT a.id, b.nama as nama_customer, c.nama as nama_toko, customer_id, toko_id, pembulatan
                    FROM nd_pembayaran_piutang a
                    LEFT JOIN nd_customer b
                    ON a.customer_id = b.id
                    LEFT JOIN nd_toko c
                    ON a.toko_id = c.id
                    WHERE customer_id = $customer_id
                    ) tbl_a
                LEFT JOIN (
                    SELECT pembayaran_piutang_id, sum(amount) as amount
                    FROM nd_pembayaran_piutang_detail
                    GROUP BY pembayaran_piutang_id
                    ) tbl_b
                ON tbl_a.id = tbl_b.pembayaran_piutang_id
                LEFT JOIN (
                    SELECT sum(amount) as bayar, pembayaran_piutang_id
                    FROM (
                        (
                            SELECT t1.*, customer_id, toko_id
                            FROM (
                                SELECT sum(amount) as amount, pembayaran_piutang_id
                                FROM nd_pembayaran_piutang_nilai
                                WHERE tanggal_transfer <= '$tanggal'
                                AND pembayaran_type_id != 2
                                AND pembayaran_type_id != 5
                                GROUP BY pembayaran_piutang_id
                                ) t1
                            LEFT JOIN nd_pembayaran_piutang t2
                            ON t1.pembayaran_piutang_id = t2.id
                            WHERE t2.status_aktif = 1
                        )UNION(
                            SELECT t1.*, customer_id, toko_id
                            FROM (
                                SELECT sum(amount) as amount, pembayaran_piutang_id
                                FROM nd_pembayaran_piutang_nilai
                                WHERE jatuh_tempo <= '$tanggal'
                                AND pembayaran_type_id = 2
                                GROUP BY pembayaran_piutang_id
                                ) t1
                            LEFT JOIN nd_pembayaran_piutang t2
                            ON t1.pembayaran_piutang_id = t2.id
                            WHERE t2.status_aktif = 1
                        )UNION(
                            SELECT t1.amount, pembayaran_piutang_id, t2.customer_id, t2.toko_id
                            FROM (
                                SELECT amount, pembayaran_piutang_id, dp_masuk_id
                                FROM nd_pembayaran_piutang_nilai
                                WHERE tanggal_transfer <= '$tanggal'
                                AND pembayaran_type_id = 5
                                GROUP BY pembayaran_piutang_id
                                ) t1
                            LEFT JOIN nd_pembayaran_piutang t2
                            ON t1.pembayaran_piutang_id = t2.id
                            LEFT JOIN (
                                SELECT *
                                FROM nd_dp_masuk
                                WHERE jatuh_tempo <= '$tanggal'
                                AND pembayaran_type_id = 6
                            ) t3
                            ON t1.dp_masuk_id = t3.id
                            WHERE t2.status_aktif = 1
                            AND t3.id is not null
                        )
                    ) result
                    GROUP BY pembayaran_piutang_id
                    ) tbl_c
                ON tbl_a.id = tbl_c.pembayaran_piutang_id
                WHERE  ifnull(bayar,0)+ifnull(pembulatan,0) - amount = 0
                AND tbl_c.pembayaran_piutang_id is not null", false);

        return $query->result();
    }

    function get_piutang_unbalance_customer($customer_id, $tanggal){

        $query = $this->db->query("SELECT tbl_a.*, (ifnull(bayar,0)+ifnull(pembulatan,0)) as total_bayar , amount 
                FROM (
                    SELECT a.id, b.nama as nama_customer, c.nama as nama_toko, customer_id, toko_id, pembulatan
                    FROM nd_pembayaran_piutang a
                    LEFT JOIN nd_customer b
                    ON a.customer_id = b.id
                    LEFT JOIN nd_toko c
                    ON a.toko_id = c.id
                    WHERE customer_id = $customer_id
                    ) tbl_a
                LEFT JOIN (
                    SELECT pembayaran_piutang_id, sum(amount) as amount
                    FROM nd_pembayaran_piutang_detail
                    GROUP BY pembayaran_piutang_id
                    ) tbl_b
                ON tbl_a.id = tbl_b.pembayaran_piutang_id
                LEFT JOIN (
                    SELECT sum(amount) as bayar, pembayaran_piutang_id
                    FROM (
                        (
                            SELECT t1.*, customer_id, toko_id
                            FROM (
                                SELECT sum(amount) as amount, pembayaran_piutang_id
                                FROM nd_pembayaran_piutang_nilai
                                WHERE tanggal_transfer <= '$tanggal'
                                AND pembayaran_type_id != 2
                                AND pembayaran_type_id != 5
                                GROUP BY pembayaran_piutang_id
                                ) t1
                            LEFT JOIN nd_pembayaran_piutang t2
                            ON t1.pembayaran_piutang_id = t2.id
                            WHERE t2.status_aktif = 1
                        )UNION(
                            SELECT t1.*, customer_id, toko_id
                            FROM (
                                SELECT sum(amount) as amount, pembayaran_piutang_id
                                FROM nd_pembayaran_piutang_nilai
                                WHERE jatuh_tempo <= '$tanggal'
                                AND pembayaran_type_id = 2
                                GROUP BY pembayaran_piutang_id
                                ) t1
                            LEFT JOIN nd_pembayaran_piutang t2
                            ON t1.pembayaran_piutang_id = t2.id
                            WHERE t2.status_aktif = 1
                        )UNION(
                            SELECT t1.amount, pembayaran_piutang_id, t2.customer_id, t2.toko_id
                            FROM (
                                SELECT amount, pembayaran_piutang_id, dp_masuk_id
                                FROM nd_pembayaran_piutang_nilai
                                WHERE tanggal_transfer <= '$tanggal'
                                AND pembayaran_type_id = 5
                                GROUP BY pembayaran_piutang_id
                                ) t1
                            LEFT JOIN nd_pembayaran_piutang t2
                            ON t1.pembayaran_piutang_id = t2.id
                            LEFT JOIN (
                                SELECT *
                                FROM nd_dp_masuk
                                WHERE jatuh_tempo <= '$tanggal'
                                AND pembayaran_type_id = 6
                            ) t3
                            ON t1.dp_masuk_id = t3.id
                            WHERE t2.status_aktif = 1
                            AND t3.id is not null
                        )
                    ) result
                    GROUP BY pembayaran_piutang_id
                    ) tbl_c
                ON tbl_a.id = tbl_c.pembayaran_piutang_id
                WHERE  ifnull(bayar,0)+ifnull(pembulatan,0) - amount != 0
                AND tbl_c.pembayaran_piutang_id is not null", false);

        return $query->result();
    }

    function get_penjualan_belum_lunas($tanggal, $pembayaran_piutang_id_list, $customer_id){
        $query = $this->db->query("SELECT t1.*, total_penjualan - ifnull(amount_bayar_jual,0) - ifnull(total_bayar,0) as sisa_piutang
            FROM (
                SELECT *
                FROM vw_penjualan_data
                WHERE tanggal <= '$tanggal'
                AND customer_id = $customer_id
                AND penjualan_type_id = 2
                ) t1
            LEFT JOIN (
                SELECT sum(qty*harga_jual) as total_penjualan, penjualan_id
                FROM nd_penjualan_detail
                LEFT JOIN (
                    SELECT sum(qty*if(jumlah_roll=0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, penjualan_detail_id
                    FROM nd_penjualan_qty_detail
                    GROUP BY penjualan_detail_id
                    ) nd_penjualan_qty_detail
                ON nd_penjualan_qty_detail.penjualan_detail_id = nd_penjualan_detail.id
                GROUP BY penjualan_id
                ) t2
            ON t2.penjualan_id = t1.id
            LEFT JOIN (
                SELECT sum(amount) as amount_bayar_jual, penjualan_id
                FROM nd_pembayaran_penjualan
                WHERE pembayaran_type_id != 5
                GROUP BY penjualan_id
            ) t3
            ON t3.penjualan_id = t1.id
            LEFT JOIN  (
                SELECT sum(amount)as total_bayar, penjualan_id
                FROM nd_pembayaran_piutang_detail
                WHERE pembayaran_piutang_id in ($pembayaran_piutang_id_list)
                GROUP BY penjualan_id
            )t4
            ON t4.penjualan_id = t1.id
            WHERE total_penjualan - ifnull(amount_bayar_jual,0) - ifnull(total_bayar,0) > 0

            ", false);

        return $query->result();
    }

    function get_pembayaran_detail_belum_lunas($pembayaran_piutang_id_list, $customer_id){
        $query = $this->db->query("SELECT sum(amount)as total_bayar, penjualan_id, group_concat(pembayaran_piutang_id) as pembayaran_piutang_id
                FROM nd_pembayaran_piutang_detail
                WHERE pembayaran_piutang_id in ($pembayaran_piutang_id_list)
                GROUP BY penjualan_id
            
            ", false);

        return $query->result();
    }

//===============================piutang payment=============================

    function get_customer_bank_bayar_history($customer_id){
        $query = $this->db->query("SELECT nama_bank, no_rek_bank
                FROM nd_pembayaran_piutang_nilai t1
                LEFT JOIN nd_pembayaran_piutang t2
                ON t1.pembayaran_piutang_id = t2.id
                WHERE nama_bank is not null
                AND nama_bank != ''
                AND customer_id = $customer_id
                GROUP BY nama_bank, no_rek_bank, customer_id
                ");

        return $query->result();
    }


    function get_pembayaran_piutang($tanggal_start, $tanggal_end, $cond){
        $query = $this->db->query("SELECT a.id, b.nama as nama_customer, c.nama as nama_toko, customer_id, toko_id, pembulatan, tanggal
                FROM (
                    SELECT id, customer_id, toko_id, pembulatan, tanggal
                    FROM (
                        (
                            SELECT id, customer_id, toko_id, pembulatan, tanggal_kontra as tanggal
                            FROM nd_pembayaran_piutang
                            $cond
                            AND tanggal_kontra >= '$tanggal_start'
                            AND tanggal_kontra <= '$tanggal_end'
                        )UNION(
                            SELECT tbl_b.id, customer_id, toko_id, 0, tanggal_kontra
                            FROM (
                                SELECT *
                                FROM nd_pembayaran_piutang_nilai
                                WHERE tanggal_transfer >= '$tanggal_start'
                                AND tanggal_transfer <= '$tanggal_end'
                                
                                )tbl_a
                            LEFT JOIN nd_pembayaran_piutang tbl_b
                            ON tbl_a.pembayaran_piutang_id = tbl_b.id
                            $cond
                            GROUP BY pembayaran_piutang_id, toko_id, customer_id
                        )
                    ) a
                    GROUP BY id,customer_id,toko_id
                )a
                LEFT JOIN nd_customer b
                ON a.customer_id = b.id
                LEFT JOIN nd_toko c
                ON a.toko_id = c.id
            
            ", false);

        return $query->result();
        // return $this->db->last_query();
    }

    function get_periode_penjualan($pembayaran_piutang_id){
        $query = $this->db->query("SELECT MIN(tanggal) as tanggal_start, MAX(tanggal) as tanggal_end
            FROM nd_penjualan
            WHERE id in (
                SELECT penjualan_id
                FROM nd_pembayaran_piutang_detail
                WHERE pembayaran_piutang_id = $pembayaran_piutang_id
                )
            ", false);

        return $query->result();
    }

    function get_pembayaran_piutang_data($id){
        $query = $this->db->query("SELECT tbl_a.*, tbl_c.nama as nama_customer, tbl_d.nama as nama_toko
            FROM (
                SELECT *
                FROM nd_pembayaran_piutang
                WHERE id = $id
                ) tbl_a
            LEFT JOIN nd_customer tbl_c
            ON tbl_a.customer_id = tbl_c.id
            LEFT JOIN nd_toko tbl_d
            ON tbl_a.toko_id = tbl_d.id
            ", false);

        return $query->result();
    }

    function get_pembayaran_piutang_awal_detail_legacy($id_list){
        $query = $this->db->query("SELECT tbl_a.*, tbl_b.no_faktur, jatuh_tempo, tbl_b.tanggal,
        ifnull(tbl_b.amount,0) - ifnull(total_bayar,0) as sisa_piutang,  tbl_b.amount as total_jual
            FROM (
                SELECT *
                FROM nd_pembayaran_piutang_detail
                WHERE pembayaran_piutang_id = $id
                AND data_status = 2
                ) tbl_a
            LEFT JOIN nd_piutang_awal tbl_b
            ON tbl_a.penjualan_id = tbl_b.id
            LEFT JOIN (
                SELECT sum(amount) as total_bayar, penjualan_id
                FROM nd_pembayaran_piutang_detail
                WHERE pembayaran_piutang_id != $id
                AND data_status = 2
                GROUP BY penjualan_id
                ) tbl_c
            ON tbl_c.penjualan_id = tbl_b.id
            ", false);

        return $query->result();
    }

    function get_pembayaran_piutang_awal_detail($id_list){
        $query = $this->db->query("SELECT tbl_a.*, tbl_b.no_faktur, jatuh_tempo, tbl_b.tanggal,
        ifnull(tbl_b.amount,0) - ifnull(total_bayar,0) as sisa_piutang,  tbl_b.amount as total_jual
            FROM (
                SELECT *
                FROM nd_pembayaran_piutang_detail
                WHERE pembayaran_piutang_id IN ($id_list)
                AND data_status = 2
                ) tbl_a
            LEFT JOIN nd_piutang_awal tbl_b
            ON tbl_a.penjualan_id = tbl_b.id
            LEFT JOIN (
                SELECT sum(amount) as total_bayar, penjualan_id
                FROM nd_pembayaran_piutang_detail
                WHERE pembayaran_piutang_id NOT IN ($id_list)
                AND data_status = 2
                GROUP BY penjualan_id
                ) tbl_c
            ON tbl_c.penjualan_id = tbl_b.id
            ", false);

        return $query->result();
    }

    function get_pembayaran_piutang_detail_legacy($id){
        $query = $this->db->query("SELECT tbl_a.*, tbl_b.no_faktur, jatuh_tempo, 
        ifnull(sisa_piutang,0) - ifnull(total_bayar,0) - ifnull(total_bayar_jual,0) as sisa_piutang, 
        total_jual , tbl_b.tanggal, nilai_info,
        if((tbl_b.tanggal < '2022-05-01 00:00:00'),concat('FPJ',convert(date_format(tbl_b.tanggal,'%d%m%y') using latin1),'-',
        ifnull(nd_toko.pre_faktur,''),convert(lpad(tbl_b.no_faktur,5,'0') using latin1)),concat(nd_toko.pre_po,':PJ01/',
        convert(date_format(tbl_b.tanggal,'%y%m') using latin1),'/',convert(lpad(tbl_b.no_faktur,4,'0') using latin1))) AS no_faktur
            FROM (
                SELECT t1.*, t2.id as nilai_info
                FROM (
                    SELECT *
                    FROM nd_pembayaran_piutang_detail
                    WHERE pembayaran_piutang_id = $id
                    AND data_status = 1
                    )t1
                LEFT JOIN (
                    SELECT group_concat(id) as id, pembayaran_piutang_detail_id
                    FROM nd_pembayaran_piutang_nilai_info
                    GROUP BY pembayaran_piutang_detail_id
                    ) t2
                ON t1.id = t2.pembayaran_piutang_detail_id
                ) tbl_a
            LEFT JOIN nd_penjualan tbl_b
            ON tbl_a.penjualan_id = tbl_b.id
            LEFT JOIN (
                SELECT sum(subqty*harga_jual) as sisa_piutang, penjualan_id, sum(subqty*harga_jual) as total_jual
                FROM nd_penjualan_detail t1
                GROUP BY penjualan_id
                ) tbl_c
            ON tbl_a.penjualan_id = tbl_c.penjualan_id
            LEFT JOIN (
                SELECT sum(amount) as total_bayar, penjualan_id
                FROM nd_pembayaran_piutang_detail
                WHERE pembayaran_piutang_id != $id
                AND data_status = 1
                GROUP BY penjualan_id
                ) tbl_d
            ON tbl_c.penjualan_id = tbl_d.penjualan_id
            LEFT JOIN (
                SELECT sum(amount) as total_bayar_jual, penjualan_id
                FROM nd_pembayaran_penjualan
                WHERE pembayaran_type_id != 5
                GROUP BY penjualan_id
                ) tbl_e
            ON tbl_c.penjualan_id = tbl_e.penjualan_id
            LEFT JOIN nd_toko
            ON tbl_b.toko_id = nd_toko.id
            ORDER BY tbl_a.id ASC
            ", false);

        return $query->result();
    }

    function get_pembayaran_piutang_detail($id_list){
        $query = $this->db->query("SELECT tbl_a.*, tbl_b.no_faktur, jatuh_tempo, 
        ifnull(sisa_piutang,0) - ifnull(total_bayar,0) - ifnull(total_bayar_jual,0) as sisa_piutang, 
        total_jual , tbl_b.tanggal, nilai_info,
        if((tbl_b.tanggal < '2022-05-01 00:00:00'),concat('FPJ',convert(date_format(tbl_b.tanggal,'%d%m%y') using latin1),'-',
        ifnull(nd_toko.pre_faktur,''),convert(lpad(tbl_b.no_faktur,5,'0') using latin1)),concat(nd_toko.pre_po,':PJ01/',
        convert(date_format(tbl_b.tanggal,'%y%m') using latin1),'/',convert(lpad(tbl_b.no_faktur,4,'0') using latin1))) AS no_faktur
            FROM (
                SELECT t1.*, t2.id as nilai_info
                FROM (
                    SELECT *
                    FROM nd_pembayaran_piutang_detail
                    WHERE pembayaran_piutang_id IN ($id_list)
                    AND data_status = 1
                    )t1
                LEFT JOIN (
                    SELECT group_concat(id) as id, pembayaran_piutang_detail_id
                    FROM nd_pembayaran_piutang_nilai_info
                    GROUP BY pembayaran_piutang_detail_id
                    ) t2
                ON t1.id = t2.pembayaran_piutang_detail_id
                ) tbl_a
            LEFT JOIN nd_penjualan tbl_b
            ON tbl_a.penjualan_id = tbl_b.id
            LEFT JOIN (
                SELECT sum(subqty*harga_jual) as sisa_piutang, penjualan_id, sum(subqty*harga_jual) as total_jual
                FROM nd_penjualan_detail t1
                GROUP BY penjualan_id
                ) tbl_c
            ON tbl_a.penjualan_id = tbl_c.penjualan_id
            LEFT JOIN (
                SELECT sum(amount) as total_bayar, penjualan_id
                FROM nd_pembayaran_piutang_detail
                WHERE pembayaran_piutang_id NOT IN ($id_list)
                AND data_status = 1
                GROUP BY penjualan_id
                ) tbl_d
            ON tbl_c.penjualan_id = tbl_d.penjualan_id
            LEFT JOIN (
                SELECT sum(amount) as total_bayar_jual, penjualan_id
                FROM nd_pembayaran_penjualan
                WHERE pembayaran_type_id != 5
                GROUP BY penjualan_id
                ) tbl_e
            ON tbl_c.penjualan_id = tbl_e.penjualan_id
            LEFT JOIN nd_toko
            ON tbl_b.toko_id = nd_toko.id
            ORDER BY tanggal, no_faktur ASC
            ", false);

        return $query->result();
    }

    function get_pembayaran_piutang_detail_all($id){
        $query = $this->db->query("SELECT tbl_a.*, tbl_b.no_faktur, 
        ifnull(sisa_piutang,0) - ifnull(total_bayar,0) - ifnull(total_bayar_jual,0) as sisa_piutang, jatuh_tempo, 
        tbl_b.no_faktur_lengkap as no_faktur, total_jual , tbl_b.tanggal, nilai_info
            FROM (
                SELECT t1.*, t2.id as nilai_info
                FROM (
                    SELECT *
                    FROM nd_pembayaran_piutang_detail
                    WHERE pembayaran_piutang_id IN ($id)
                    AND data_status = 1
                    )t1
                LEFT JOIN (
                    SELECT group_concat(id) as id, pembayaran_piutang_detail_id
                    FROM nd_pembayaran_piutang_nilai_info
                    GROUP BY pembayaran_piutang_detail_id
                    ) t2
                ON t1.id = t2.pembayaran_piutang_detail_id
                ) tbl_a
            LEFT JOIN vw_penjualan_data tbl_b
            ON tbl_a.penjualan_id = tbl_b.id
            LEFT JOIN (
                SELECT sum(qty*harga_jual) as sisa_piutang, penjualan_id, sum(qty*harga_jual) as total_jual
                FROM nd_penjualan_detail t1
                LEFT JOIN (
                    SELECT sum(qty*if(jumlah_roll=0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, penjualan_detail_id
                    FROM nd_penjualan_qty_detail
                    GROUP BY penjualan_detail_id
                    ) t2
                ON t2.penjualan_detail_id = t1.id
                GROUP BY penjualan_id
                ) tbl_c
            ON tbl_a.penjualan_id = tbl_c.penjualan_id
            LEFT JOIN (
                SELECT sum(amount) as total_bayar, penjualan_id
                FROM nd_pembayaran_piutang_detail
                WHERE pembayaran_piutang_id != $id
                AND data_status = 1
                GROUP BY penjualan_id
                ) tbl_d
            ON tbl_c.penjualan_id = tbl_d.penjualan_id
            LEFT JOIN (
                SELECT sum(amount) as total_bayar_jual, penjualan_id
                FROM nd_pembayaran_penjualan
                WHERE pembayaran_type_id != 5
                GROUP BY penjualan_id
                ) tbl_e
            ON tbl_c.penjualan_id = tbl_e.penjualan_id
            ORDER BY tbl_a.id ASC
            ", false);

        return $query->result();
    }

    function get_pembayaran_nilai_detail_info($pembayaran_piutang_id)
    {
        $query = $this->db->query("SELECT t1.*, t2.pembayaran_piutang_detail_id, tipe.nama as tipe_dp, dp.tanggal as tanggal_dp
            FROM (
                SELECT t1.*, t2.id as giro_tolakan_id, t2.keterangan as keterangan_tolakan, t2.tanggal as tanggal_tolakan
                FROM (
                    SELECT *
                    FROM nd_pembayaran_piutang_nilai
                    where pembayaran_piutang_id=$pembayaran_piutang_id
                    ) t1
                LEFT JOIN nd_giro_tolakan t2
                ON t1.id = t2.pembayaran_piutang_nilai_id
                ) t1
            LEFT JOIN (
                SELECT group_concat(pembayaran_piutang_detail_id) as pembayaran_piutang_detail_id, pembayaran_piutang_nilai_id
                FROM nd_pembayaran_piutang_nilai_info
                GROUP BY pembayaran_piutang_nilai_id
                ) t2
            ON t1.id = t2.pembayaran_piutang_nilai_id
            LEFT JOIN nd_dp_masuk dp
            ON t1.dp_masuk_id = dp.id
            LEFT JOIN nd_pembayaran_type as tipe
            ON dp.pembayaran_type_id = tipe.id
            ", false);

        return $query->result();
    }

    function get_pembayaran_retur_detail_legacy($id){
        $query = $this->db->query("SELECT tbl_a.*, tbl_b.no_faktur, '' as jatuh_tempo, 
        ifnull(sisa_piutang,0) - ifnull(total_bayar,0) as sisa_piutang, 
        concat('FRJ', DATE_FORMAT(tanggal,'%d%m%y'),'-',LPAD(no_faktur,4,'0')) as no_faktur, 
        total_jual, tbl_b.tanggal
            FROM (
                SELECT *
                FROM nd_pembayaran_piutang_detail
                WHERE pembayaran_piutang_id = $id
                AND data_status = 3
                ) tbl_a
            LEFT JOIN nd_retur_jual tbl_b
            ON tbl_a.penjualan_id = tbl_b.id
            LEFT JOIN (
                SELECT sum(qty*harga) as sisa_piutang, retur_jual_id, sum(qty*harga) as total_jual
                FROM nd_retur_jual_detail t1
                LEFT JOIN (
                    SELECT sum(qty*if(jumlah_roll=0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, retur_jual_detail_id
                    FROM nd_retur_jual_qty
                    GROUP BY retur_jual_detail_id
                    ) t2
                ON t2.retur_jual_detail_id = t1.id
                GROUP BY retur_jual_id
                ) tbl_c
            ON tbl_a.penjualan_id = tbl_c.retur_jual_id
            LEFT JOIN (
                SELECT sum(amount) as total_bayar, penjualan_id
                FROM nd_pembayaran_piutang_detail
                WHERE pembayaran_piutang_id != $id
                AND data_status = 3
                GROUP BY penjualan_id
                ) tbl_d
            ON tbl_c.retur_jual_id = tbl_d.penjualan_id
            ", false);

        return $query->result();
    }

    function get_pembayaran_retur_detail($id_list){
        $query = $this->db->query("SELECT tbl_a.*, tbl_b.no_faktur, '' as jatuh_tempo, 
        ifnull(sisa_piutang,0) - ifnull(total_bayar,0) as sisa_piutang, 
        concat('FRJ', DATE_FORMAT(tanggal,'%d%m%y'),'-',LPAD(no_faktur,4,'0')) as no_faktur, 
        total_jual, tbl_b.tanggal
            FROM (
                SELECT *
                FROM nd_pembayaran_piutang_detail
                WHERE pembayaran_piutang_id IN ($id_list)
                AND data_status = 3
                ) tbl_a
            LEFT JOIN nd_retur_jual tbl_b
            ON tbl_a.penjualan_id = tbl_b.id
            LEFT JOIN (
                SELECT sum(qty*harga) as sisa_piutang, retur_jual_id, sum(qty*harga) as total_jual
                FROM nd_retur_jual_detail t1
                LEFT JOIN (
                    SELECT sum(qty*if(jumlah_roll=0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, retur_jual_detail_id
                    FROM nd_retur_jual_qty
                    GROUP BY retur_jual_detail_id
                    ) t2
                ON t2.retur_jual_detail_id = t1.id
                GROUP BY retur_jual_id
                ) tbl_c
            ON tbl_a.penjualan_id = tbl_c.retur_jual_id
            LEFT JOIN (
                SELECT sum(amount) as total_bayar, penjualan_id
                FROM nd_pembayaran_piutang_detail
                WHERE pembayaran_piutang_id NOT IN ($id_list)
                AND data_status = 3
                GROUP BY penjualan_id
                ) tbl_d
            ON tbl_c.retur_jual_id = tbl_d.penjualan_id
            ", false);

        return $query->result();
    }

    function get_dp_berlaku($customer_id, $pembayaran_piutang_id){
        $query = $this->db->query("SELECT a.*, c.nama as bayar_dp, b.amount as amount_bayar
                FROM (
                    SELECT t1.id,amount - ifnull(amount_use,0) - ifnull(amount_bayar,0) - ifnull(amount_keluar,0)  as amount, tanggal, keterangan, no_faktur_lengkap, nama_penerima, nama_bank, no_rek_bank, no_giro, jatuh_tempo, pembayaran_type_id
                    FROM (
                        SELECT id,amount, tanggal, keterangan, concat('DP', DATE_FORMAT(tanggal,'%d%m%y'),'-',LPAD(no_dp,4,'0')) as no_faktur_lengkap, nama_penerima, nama_bank, no_rek_bank, no_giro, jatuh_tempo, pembayaran_type_id
                        FROM nd_dp_masuk
                        WHERE customer_id = $customer_id
                    )t1
                    LEFT JOIN (
                        SELECT dp_masuk_id, sum(amount) as amount_use
                        FROM nd_pembayaran_penjualan
                        WHERE pembayaran_type_id = 1
                        GROUP BY dp_masuk_id
                    )t2
                    ON t1.id = t2.dp_masuk_id
                    LEFT JOIN (
                        SELECT sum(amount) as amount_bayar, dp_masuk_id
                        FROM nd_pembayaran_piutang_nilai
                        WHERE pembayaran_piutang_id != $pembayaran_piutang_id
                        GROUP BY dp_masuk_id
                        ) t3
                    ON t1.id = t3.dp_masuk_id
                    LEFT JOIN (
                        SELECT sum(amount) as amount_keluar, dp_masuk_id
                        FROM nd_dp_keluar
                        GROUP BY dp_masuk_id
                        ) t4
                    ON t1.id = t4.dp_masuk_id
                    WHERE amount - ifnull(amount_use,0) - ifnull(amount_bayar,0) - ifnull(amount_keluar,0) > 0
                ) a
                LEFT JOIN (
                    SELECT *
                    FROM nd_pembayaran_piutang_nilai
                    WHERE pembayaran_piutang_id = $pembayaran_piutang_id
                    AND pembayaran_type_id = 5
                    ) b
                ON a.id = b.dp_masuk_id
                LEFT JOIN nd_pembayaran_type c
                ON a.pembayaran_type_id = c.id
        ");
        return $query->result();
    }

//===============================daftar giro=============================

    function get_giro_setor_list(){
        $query = $this->db->query("SELECT t_0.*, sum(ifnull(amount,0)) as total_giro, sum(1) as count_giro
            FROM (
                SELECT *
                FROM nd_giro_setor
                WHERE YEAR(tanggal) >= '2019') t_0
            LEFT JOIN nd_giro_setor_detail t_a
            ON t_0.id = t_a.giro_setor_id
            LEFT JOIN(
                (
                    SELECT t1.*
                    FROM (
                        SELECT id, tanggal_transfer as tanggal, no_giro, nama_bank, jatuh_tempo, pembayaran_piutang_id, amount, 1 as data_type, pembayaran_type_id
                        FROM nd_pembayaran_piutang_nilai
                        WHERE pembayaran_type_id = 2
                        ) t1
                    LEFT JOIN nd_giro_urutan t2
                    ON t1.id = t2.source_table_id
                    AND t1.data_type = t2.data_type
                    WHERE t2.id is null
                )UNION(
                    SELECT t1.*
                    FROM (
                        SELECT id, tanggal, no_giro, nama_bank, jatuh_tempo,id as refered_id,amount, 2 as data_type, pembayaran_type_id
                        FROM nd_dp_masuk
                        WHERE pembayaran_type_id = 6
                        ) t1
                    LEFT JOIN nd_giro_urutan t2
                    ON t1.id = t2.source_table_id
                    AND t1.data_type = t2.data_type
                    WHERE t2.id is null
                )UNION(
                    SELECT id, tanggal, no_giro, nama_bank, jatuh_tempo,id as refered_id,amount, 3 as data_type, pembayaran_type_id
                    FROM nd_giro_terima_before
                )
            ) t_b
            ON t_a.pembayaran_piutang_nilai_id = t_b.id
            AND t_a.data_type = t_b.data_type
            GROUP BY t_0.id
            ORDER BY tanggal desc
            ");

        return $query->result();
    }

    function get_giro_setor_not_indexed($tahun){
        $query = $this->db->query("SELECT a.*
            FROM  (
                (
                    SELECT t1.*
                    FROM (
                        SELECT id, tanggal_transfer as tanggal, no_giro, nama_bank, jatuh_tempo, pembayaran_piutang_id, amount, 1 as data_type, pembayaran_type_id
                        FROM nd_pembayaran_piutang_nilai
                        WHERE pembayaran_type_id = 2
                        AND YEAR(created)='$tahun'
                        ) t1
                    LEFT JOIN nd_giro_urutan t2
                    ON t1.id = t2.source_table_id
                    AND t1.data_type = t2.data_type
                    WHERE t2.id is null
                )UNION(
                    SELECT t1.*
                    FROM (
                        SELECT id, tanggal, no_giro, nama_bank, jatuh_tempo,id as refered_id,amount, 2 as data_type, pembayaran_type_id
                        FROM nd_dp_masuk
                        WHERE pembayaran_type_id = 6
                        AND YEAR(created)='$tahun'
                        ) t1
                    LEFT JOIN nd_giro_urutan t2
                    ON t1.id = t2.source_table_id
                    AND t1.data_type = t2.data_type
                    WHERE t2.id is null
                )
            ) a
            ");

        return $query->result();
    }

    function get_daftar_giro($tanggal_start, $tanggal_end, $tanggal_awal, $cond){
        $query = $this->db->query("SELECT a.*, c.nama as nama_customer, amount, urutan_giro as urutan
            FROM  (
                (
                    SELECT t1.*, customer_id, tanggal_setor,1 as data_type
                    FROM (
                        SELECT id, tanggal_transfer as tanggal, LPAD(urutan_giro,3,'0') as urutan_giro, no_giro, nama_bank, jatuh_tempo, pembayaran_piutang_id, amount
                        FROM nd_pembayaran_piutang_nilai
                        WHERE pembayaran_type_id = 2
                        AND tanggal_transfer >= '$tanggal_awal'
                        AND tanggal_transfer >= '$tanggal_start'
                        AND tanggal_transfer <= '$tanggal_end'
                        ) t1
                    LEFT JOIN (
                        SELECT pembayaran_piutang_nilai_id, tanggal as tanggal_setor, data_type
                        FROM nd_giro_setor_detail
                        LEFT JOIN nd_giro_setor
                        ON nd_giro_setor_detail.giro_setor_id = nd_giro_setor.id
                        WHERE data_type = 1
                        ) t2
                    ON t1.id = t2.pembayaran_piutang_nilai_id
                    LEFT JOIN nd_pembayaran_piutang b
                    ON t1.pembayaran_piutang_id = b.id
                )UNION(
                    SELECT t1.*, tanggal_setor,2 as data_type
                    FROM (
                        SELECT id, tanggal, LPAD(urutan_giro,3,'0') as urutan_giro,  no_giro, nama_bank, jatuh_tempo,id as refered_id,amount, customer_id
                        FROM nd_dp_masuk
                        WHERE pembayaran_type_id = 6
                        AND tanggal >= '$tanggal_awal'
                        AND tanggal >= '$tanggal_start'
                        AND tanggal <= '$tanggal_end'
                        ) t1
                    LEFT JOIN (
                        SELECT pembayaran_piutang_nilai_id, tanggal as tanggal_setor, data_type
                        FROM nd_giro_setor_detail
                        LEFT JOIN nd_giro_setor
                        ON nd_giro_setor_detail.giro_setor_id = nd_giro_setor.id
                        WHERE data_type = 2
                        ) t2
                    ON t1.id = t2.pembayaran_piutang_nilai_id
                )UNION(
                    SELECT t1.*, tanggal_setor,3 as data_type
                    FROM (
                        SELECT id, tanggal, LPAD(urutan_giro,3,'0') as urutan_giro,  no_giro, nama_bank, jatuh_tempo,id as refered_id,amount, customer_id
                        FROM nd_giro_terima_before
                        WHERE pembayaran_type_id = 2
                        ) t1
                    LEFT JOIN (
                        SELECT pembayaran_piutang_nilai_id, tanggal as tanggal_setor, data_type
                        FROM nd_giro_setor_detail
                        LEFT JOIN nd_giro_setor
                        ON nd_giro_setor_detail.giro_setor_id = nd_giro_setor.id
                        WHERE data_type = 3
                        ) t2
                    ON t1.id = t2.pembayaran_piutang_nilai_id
                )
                ) a
            LEFT JOIN nd_giro_urutan b
            ON a.id = b.source_table_id
            AND a.data_type = b.data_type
            LEFT JOIN nd_customer c
            ON a.customer_id = c.id
            $cond
            ORDER BY tanggal,urutan asc
            ");

        return $query->result();
    }

    function get_daftar_giro_mentah($cond, $cond2){
        $query = $this->db->query("SELECT a.*, d.nama as nama_customer, amount, a.id as pembayaran_piutang_nilai_id
            FROM  (
                SELECT *
                FROM (
                    (
                        SELECT id, pembayaran_type_id, tanggal_transfer, urutan_giro, nama_bank, no_rek_bank, no_giro, jatuh_tempo, amount, 1 as tipe, pembayaran_piutang_id
                        FROM nd_pembayaran_piutang_nilai
                        WHERE pembayaran_type_id = 2
                        AND YEAR(tanggal_transfer) >= '2019'
                        $cond
                    )UNION(
                        SELECT id, pembayaran_type_id, tanggal, urutan_giro, nama_bank, no_rek_bank, no_giro, jatuh_tempo, amount, 2, ''
                        FROM nd_dp_masuk
                        WHERE YEAR(tanggal) >= '2019'
                        AND pembayaran_type_id = 6
                        AND urutan_giro is not null
                    )
                )result
            ) a
            LEFT JOIN nd_giro_setor_detail b
            ON a.id = b.pembayaran_piutang_nilai_id
            LEFT JOIN nd_pembayaran_piutang c 
            ON a.pembayaran_piutang_id = c.id
            LEFT JOIN nd_customer d
            ON c.customer_id = d.id
            WHERE b.id is null
            ");

        return $query->result();
    }

    function get_daftar_giro_setor($giro_setor_id){
        $query = $this->db->query("SELECT a.*,b.*, d.nama as nama_customer, amount, a.id as id
            FROM  (
                SELECT *
                FROM nd_giro_setor_detail
                WHERE giro_setor_id = $giro_setor_id
                ) a
            LEFT JOIN (
                (
                    SELECT t1.*, customer_id
                    FROM (
                        SELECT id, pembayaran_type_id, DATE_FORMAT(tanggal_transfer,'%d/%m/%Y') as tanggal_transfer, LPAD(urutan_giro,3,'0') as urutan_giro, nama_bank, no_rek_bank, no_giro, DATE_FORMAT(jatuh_tempo,'%d/%m/%Y') as jatuh_tempo, amount, 1 as tipe, pembayaran_piutang_id
                        FROM nd_pembayaran_piutang_nilai
                        WHERE pembayaran_type_id = 2
                        AND urutan_giro is not null
                    )t1
                    LEFT JOIN nd_pembayaran_piutang t2
                    ON t1.pembayaran_piutang_id = t2.id
                )UNION(
                    SELECT id, pembayaran_type_id, DATE_FORMAT(jatuh_tempo,'%d/%m/%Y'), LPAD(urutan_giro,3,'0') as urutan_giro, nama_bank, no_rek_bank, no_giro, jatuh_tempo, amount, 2, '', customer_id
                    FROM nd_dp_masuk
                    WHERE pembayaran_type_id = 6
                    AND urutan_giro is not null
                )UNION(
                    SELECT id, pembayaran_type_id, DATE_FORMAT(jatuh_tempo,'%d/%m/%Y'), LPAD(urutan_giro,3,'0') as urutan_giro, nama_bank, no_rek_bank, no_giro, jatuh_tempo, amount, 3, '', customer_id
                    FROM nd_giro_terima_before
                    WHERE YEAR(tanggal) >= '2019'
                    AND pembayaran_type_id = 2
                    AND urutan_giro is not null
                )
            ) b
            ON a.pembayaran_piutang_nilai_id = b.id
            LEFT JOIN nd_customer d
            ON b.customer_id = d.id
            -- ORDER BY tanggal_transfer asc
            ");

        return $query->result();
    }

    function get_daftar_giro_search($urutan_giro, $tahun){
        $query = $this->db->query("SELECT a.*, b.nama as nama_customer
            FROM (
                (
                    SELECT t1.*, customer_id
                    FROM (
                        SELECT id, pembayaran_type_id, DATE_FORMAT(tanggal_transfer,'%d/%m/%Y') as tanggal, urutan_giro, nama_bank, no_rek_bank, no_giro, DATE_FORMAT(jatuh_tempo,'%d/%m/%Y') as jatuh_tempo, amount, 1 as tipe, pembayaran_piutang_id
                        FROM nd_pembayaran_piutang_nilai
                        WHERE YEAR(tanggal_transfer) = '$tahun'
                        AND pembayaran_type_id = 2
                        AND urutan_giro is not null
                    )t1
                    LEFT JOIN nd_pembayaran_piutang t2
                    ON t1.pembayaran_piutang_id = t2.id
                )UNION(
                    SELECT id, pembayaran_type_id, DATE_FORMAT(jatuh_tempo,'%d/%m/%Y'), urutan_giro, nama_bank, no_rek_bank, no_giro, jatuh_tempo, amount, 2, '', customer_id
                    FROM nd_dp_masuk
                    WHERE YEAR(tanggal) >= '$tahun'
                    AND pembayaran_type_id = 6
                    AND urutan_giro is not null
                )UNION(
                    SELECT id, pembayaran_type_id, DATE_FORMAT(jatuh_tempo,'%d/%m/%Y'), urutan_giro, nama_bank, no_rek_bank, no_giro, jatuh_tempo, amount, 3, '', customer_id
                    FROM nd_giro_terima_before
                    WHERE YEAR(tanggal) >= '$tahun'
                    AND pembayaran_type_id = 2
                    AND urutan_giro is not null
                )
            ) a
            LEFT JOIN nd_customer b
            ON a.customer_id = b.id
            LEFT JOIN nd_giro_setor_detail c
            ON c.pembayaran_piutang_nilai_id = a.id
            AND a.tipe = c.data_type
            WHERE urutan_giro = $urutan_giro
            AND c.id is null
            ");

        return $query;
    }

//===============================daftar mutasi hutang=============================
    function get_mutasi_hutang($tanggal_start, $tanggal_end, $toko_id){
        // $tanggal = date('Y-m-01', strtotime($tanggal));
        // $tanggal_end = date('Y-m-t', strtotime($tanggal));
        $query = $this->db->query("SELECT amount, amount_bayar, a.nama as nama_supplier, a.id as supplier_id, amount_beli, amount_retur, amount_retur_belum
            FROM nd_supplier a 
            LEFT JOIN (
                SELECT supplier_id, sum(ceil(amount)) as amount, sum(amount_bayar) as amount_bayar
                FROM (
                    (
                        SELECT supplier_id, sum(amount_beli) as amount, 0 as amount_bayar, 1 as tipe
                        FROM (
                            SELECT *
                            FROM nd_pembelian
                            WHERE tanggal < '$tanggal_start'
                            AND toko_id = $toko_id
                            AND status_aktif = 1
                            ) nd_pembelian
                        LEFT JOIN (
                            SELECT sum(qty * harga_beli) as amount_beli, pembelian_id
                            FROM nd_pembelian_detail t1
                            GROUP BY pembelian_id
                        ) nd_pembelian_detail
                        ON nd_pembelian.id = nd_pembelian_detail.pembelian_id
                        GROUP BY supplier_id
                    )UNION(
                        SELECT *,2
                        FROM (
                            SELECT supplier_id,0, sum(ifnull(amount_bayar,0)) + sum(pembulatan)
                            FROM (
                                SELECT *
                                FROM nd_pembayaran_hutang
                                WHERE toko_id = 1
                            ) nd_pembayaran_hutang
                            LEFT JOIN (
                                SELECT *, sum(amount) as amount_bayar
                                FROM nd_pembayaran_hutang_nilai 
                                WHERE tanggal_transfer < '$tanggal_start'
                                GROUP BY pembayaran_hutang_id
                                )nd_pembayaran_hutang_nilai
                            ON nd_pembayaran_hutang.id = nd_pembayaran_hutang_nilai.pembayaran_hutang_id
                            WHERE nd_pembayaran_hutang_nilai.id is not null
                            GROUP BY supplier_id
                        )t1
                    )UNION(
                        SELECT *,2
                        FROM (
                            SELECT supplier_id,0, sum(pembulatan)
                            FROM (
                                SELECT *
                                FROM nd_pembayaran_hutang
                                WHERE toko_id = 1
                                AND DATE(created) < '$tanggal_start'
                            ) nd_pembayaran_hutang
                            LEFT JOIN (
                                SELECT *, sum(amount) as amount_bayar
                                FROM nd_pembayaran_hutang_nilai 
                                GROUP BY pembayaran_hutang_id
                                )nd_pembayaran_hutang_nilai
                            ON nd_pembayaran_hutang.id = nd_pembayaran_hutang_nilai.pembayaran_hutang_id
                            WHERE nd_pembayaran_hutang_nilai.id is null
                            GROUP BY supplier_id
                        )t1
                    )UNION(
                        SELECT supplier_id, sum(amount) as amount, 0 as amount_bayar, 3
                        FROM nd_hutang_awal
                        WHERE tanggal < '$tanggal_start'
                        AND toko_id = $toko_id
                        GROUP BY supplier_id
                    )UNION(
                        SELECT *
                        FROM (
                            SELECT supplier_id,0, sum(ifnull(amount_bayar,0)) , 4
                            FROM (
                                SELECT *
                                FROM nd_pembayaran_hutang
                                WHERE toko_id = 1
                                AND DATE(created) < '$tanggal_start'
                            ) t1 
                            LEFT JOIN (
                                SELECT *, sum(amount) as amount_bayar
                                FROM nd_pembayaran_hutang_detail
                                WHERE data_status = 4 
                                GROUP BY pembayaran_hutang_id
                            )t2
                            ON t1.id = t2.pembayaran_hutang_id
                            WHERE t2.id is not null
                            GROUP BY supplier_id
                        )t1
                    )
                    ) t1
                GROUP BY supplier_id
            )b
            ON b.supplier_id = a.id
            LEFT JOIN (
                SELECT supplier_id, sum(ceil(amount_beli)) as amount_beli
                FROM (
                    SELECT *
                    FROM nd_pembelian
                    WHERE tanggal >= '$tanggal_start'
                    AND tanggal <= '$tanggal_end'
                    AND toko_id = $toko_id
                    AND status_aktif = 1
                    ) nd_pembelian
                LEFT JOIN (
                    SELECT sum(qty * harga_beli) as amount_beli, pembelian_id
                    FROM nd_pembelian_detail t1
                    GROUP BY pembelian_id
                ) nd_pembelian_detail
                ON nd_pembelian.id = nd_pembelian_detail.pembelian_id
                GROUP BY supplier_id
            ) c
            ON a.id = c.supplier_id
            LEFT JOIN (
                SELECT supplier_id, sum(amount_retur) as amount_retur
                FROM (
                    SELECT *
                    FROM nd_retur_beli
                    WHERE toko_id = $toko_id
                    AND status_aktif = 1
                    ) t1
                LEFT JOIN (
                    SELECT sum((qty * if(jumlah_roll=0,1,jumlah_roll) ) * harga) as amount_retur, retur_beli_id
                    FROM nd_retur_beli_detail t1
                    LEFT JOIN nd_retur_beli_qty t2
                    ON t2.retur_beli_detail_id = t1.id 
                    GROUP BY retur_beli_id
                ) t2
                ON t1.id = t2.retur_beli_id
                LEFT JOIN (
                    SELECT pembelian_id
                    FROM (
                        SELECT *
                        FROM nd_pembayaran_hutang_detail
                        WHERE data_status = 4
                        )t1
                        LEFT JOIN nd_pembayaran_hutang t2
                        ON t1.pembayaran_hutang_id = t2.id
                        WHERE DATE(created) <= '$tanggal_end'
                        AND DATE(created) >= '$tanggal_start'
                    ) t3
                ON t1.id=t3.pembelian_id
                WHERE t3.pembelian_id is not null
                GROUP BY supplier_id
            ) d
            ON a.id = d.supplier_id
            LEFT JOIN (
                SELECT supplier_id, sum(amount_retur) as amount_retur_belum
                FROM (
                    SELECT *
                    FROM nd_retur_beli
                    -- WHERE tanggal >= '$tanggal_start'
                    WHERE tanggal <= '$tanggal_end'
                    AND toko_id = $toko_id
                    AND status_aktif = 1
                    ) t1
                LEFT JOIN (
                    SELECT sum((qty * if(jumlah_roll=0,1,jumlah_roll) ) * harga) as amount_retur, retur_beli_id
                    FROM nd_retur_beli_detail t1
                    LEFT JOIN nd_retur_beli_qty t2
                    ON t2.retur_beli_detail_id = t1.id 
                    GROUP BY retur_beli_id
                ) t2
                ON t1.id = t2.retur_beli_id
                LEFT JOIN (
                    SELECT pembelian_id
                    FROM (
                        SELECT *
                        FROM nd_pembayaran_hutang_detail
                        WHERE data_status = 4
                        )t1
                        LEFT JOIN nd_pembayaran_hutang t2
                        ON t1.pembayaran_hutang_id = t2.id
                    WHERE DATE(created) <= '$tanggal_end'

                    ) t3
                ON t1.id=t3.pembelian_id
                WHERE t3.pembelian_id is null
                GROUP BY supplier_id
            ) e
            ON a.id = e.supplier_id
            WHERE a.status_aktif = 1
            ORDER BY a.nama

            ");

        return $query->result();
    }

    function get_mutasi_hutang_bayar($supplier_id, $toko_id, $tanggal_start,$tanggal_end){
        $tanggal_before = $tanggal_start;
        $tanggal_end = $tanggal_end;
        $query = $this->db->query("SELECT sum(amount) as bayar, pembayaran_type_id
            FROM (
                (
                    SELECT pembayaran_type_id, amount, pembayaran_hutang_id, id
                    FROM nd_pembayaran_hutang_nilai
                    WHERE tanggal_transfer >= '$tanggal_before'
                    AND tanggal_transfer <= '$tanggal_end'
                )UNION(
                    SELECT 99, pembulatan, t1.id, t2.id
                    FROM nd_pembayaran_hutang t1
                    LEFT JOIN (
                        SELECT pembayaran_hutang_id, min(tanggal_transfer), id
                        FROM nd_pembayaran_hutang_nilai
                        WHERE tanggal_transfer >= '$tanggal_before'
                        AND tanggal_transfer <= '$tanggal_end'
                        GROUP BY pembayaran_hutang_id
                        )t2
                    ON t2.pembayaran_hutang_id = t1.id
                    WHERE t2.pembayaran_hutang_id is not null
                )UNION(
                    SELECT 99, pembulatan, t1.id, t2.id
                    FROM (
                        SELECT *
                        FROM nd_pembayaran_hutang
                        WHERE DATE(created) >= '$tanggal_before'
                        AND DATE(created) <= '$tanggal_end'
                        ) t1
                    LEFT JOIN (
                        SELECT pembayaran_hutang_id, min(tanggal_transfer), id
                        FROM nd_pembayaran_hutang_nilai
                        GROUP BY pembayaran_hutang_id
                        )t2
                    ON t2.pembayaran_hutang_id = t1.id
                    WHERE t2.pembayaran_hutang_id is null
                    AND pembulatan > 0
                )
            ) a
            LEFT JOIN nd_pembayaran_hutang b
            ON a.pembayaran_hutang_id = b.id
            WHERE supplier_id = $supplier_id
            AND toko_id = $toko_id
            GROUP BY pembayaran_type_id
            ");

        return $query->result();
        // return $this->db->last_query();
    }

    function get_mutasi_hutang_all($tanggal_start, $tanggal_end, $toko_id){
        // $tanggal = date('Y-m-01', strtotime($tanggal));
        // $tanggal_end = date('Y-m-t', strtotime($tanggal));
        $query = $this->db->query("SELECT amount, amount_bayar, a.nama as nama_supplier, a.id as supplier_id, amount_beli, amount_retur, amount_retur_belum
            FROM nd_supplier a 
            LEFT JOIN (
                SELECT supplier_id, sum(ceil(amount)) as amount, sum(amount_bayar) as amount_bayar
                FROM (
                    (
                        SELECT supplier_id, sum(amount_beli) as amount, 0 as amount_bayar, 1 as tipe
                        FROM (
                            SELECT *
                            FROM nd_pembelian
                            WHERE tanggal < '$tanggal_start'
                            AND toko_id = $toko_id
                            AND status_aktif = 1
                            ) nd_pembelian
                        LEFT JOIN (
                            SELECT sum(qty * harga_beli) as amount_beli, pembelian_id
                            FROM nd_pembelian_detail t1
                            GROUP BY pembelian_id
                        ) nd_pembelian_detail
                        ON nd_pembelian.id = nd_pembelian_detail.pembelian_id
                        GROUP BY supplier_id
                    )UNION(
                        SELECT *,2
                        FROM (
                            SELECT supplier_id,0, sum(ifnull(amount_bayar,0)) + sum(pembulatan)
                            FROM (
                                SELECT *
                                FROM nd_pembayaran_hutang
                                WHERE toko_id = 1
                            ) nd_pembayaran_hutang
                            LEFT JOIN (
                                SELECT *, sum(amount) as amount_bayar
                                FROM nd_pembayaran_hutang_nilai 
                                WHERE tanggal_transfer < '$tanggal_start'
                                GROUP BY pembayaran_hutang_id
                                )nd_pembayaran_hutang_nilai
                            ON nd_pembayaran_hutang.id = nd_pembayaran_hutang_nilai.pembayaran_hutang_id
                            WHERE nd_pembayaran_hutang_nilai.id is not null
                            GROUP BY supplier_id
                        )t1
                    )UNION(
                        SELECT *,2
                        FROM (
                            SELECT supplier_id,0, sum(pembulatan)
                            FROM (
                                SELECT *
                                FROM nd_pembayaran_hutang
                                WHERE toko_id = 1
                                AND DATE(created) < '$tanggal_start'
                            ) nd_pembayaran_hutang
                            LEFT JOIN (
                                SELECT *, sum(amount) as amount_bayar
                                FROM nd_pembayaran_hutang_nilai 
                                GROUP BY pembayaran_hutang_id
                                )nd_pembayaran_hutang_nilai
                            ON nd_pembayaran_hutang.id = nd_pembayaran_hutang_nilai.pembayaran_hutang_id
                            WHERE nd_pembayaran_hutang_nilai.id is null
                            GROUP BY supplier_id
                        )t1
                    )UNION(
                        SELECT supplier_id, sum(amount) as amount, 0 as amount_bayar, 3
                        FROM nd_hutang_awal
                        WHERE tanggal < '$tanggal_start'
                        AND toko_id = $toko_id
                        GROUP BY supplier_id
                    )UNION(
                        SELECT *
                        FROM (
                            SELECT supplier_id,0, sum(ifnull(amount_bayar,0)) , 4
                            FROM (
                                SELECT *
                                FROM nd_pembayaran_hutang
                                WHERE toko_id = 1
                                AND DATE(created) < '$tanggal_start'
                            ) t1 
                            LEFT JOIN (
                                SELECT *, sum(amount) as amount_bayar
                                FROM nd_pembayaran_hutang_detail
                                WHERE data_status = 4 
                                GROUP BY pembayaran_hutang_id
                            )t2
                            ON t1.id = t2.pembayaran_hutang_id
                            WHERE t2.id is not null
                            GROUP BY supplier_id
                        )t1
                    )
                    ) t1
                GROUP BY supplier_id
            )b
            ON b.supplier_id = a.id
            LEFT JOIN (
                SELECT supplier_id, sum(ceil(amount_beli)) as amount_beli
                FROM (
                    SELECT *
                    FROM nd_pembelian
                    WHERE tanggal >= '$tanggal_start'
                    AND tanggal <= '$tanggal_end'
                    AND toko_id = $toko_id
                    AND status_aktif = 1
                    ) nd_pembelian
                LEFT JOIN (
                    SELECT sum(qty * harga_beli) as amount_beli, pembelian_id
                    FROM nd_pembelian_detail t1
                    GROUP BY pembelian_id
                ) nd_pembelian_detail
                ON nd_pembelian.id = nd_pembelian_detail.pembelian_id
                GROUP BY supplier_id
            ) c
            ON a.id = c.supplier_id
            LEFT JOIN (
                SELECT supplier_id, sum(amount_retur) as amount_retur
                FROM (
                    SELECT *
                    FROM nd_retur_beli
                    WHERE toko_id = $toko_id
                    AND status_aktif = 1
                    ) t1
                LEFT JOIN (
                    SELECT sum((qty * if(jumlah_roll=0,1,jumlah_roll) ) * harga) as amount_retur, retur_beli_id
                    FROM nd_retur_beli_detail t1
                    LEFT JOIN nd_retur_beli_qty t2
                    ON t2.retur_beli_detail_id = t1.id 
                    GROUP BY retur_beli_id
                ) t2
                ON t1.id = t2.retur_beli_id
                LEFT JOIN (
                    SELECT pembelian_id
                    FROM (
                        SELECT *
                        FROM nd_pembayaran_hutang_detail
                        WHERE data_status = 4
                        )t1
                        LEFT JOIN nd_pembayaran_hutang t2
                        ON t1.pembayaran_hutang_id = t2.id
                        WHERE DATE(created) <= '$tanggal_end'
                        AND DATE(created) >= '$tanggal_start'
                    ) t3
                ON t1.id=t3.pembelian_id
                WHERE t3.pembelian_id is not null
                GROUP BY supplier_id
            ) d
            ON a.id = d.supplier_id
            LEFT JOIN (
                SELECT supplier_id, sum(amount_retur) as amount_retur_belum
                FROM (
                    SELECT *
                    FROM nd_retur_beli
                    -- WHERE tanggal >= '$tanggal_start'
                    WHERE tanggal <= '$tanggal_end'
                    AND toko_id = $toko_id
                    AND status_aktif = 1
                    ) t1
                LEFT JOIN (
                    SELECT sum((qty * if(jumlah_roll=0,1,jumlah_roll) ) * harga) as amount_retur, retur_beli_id
                    FROM nd_retur_beli_detail t1
                    LEFT JOIN nd_retur_beli_qty t2
                    ON t2.retur_beli_detail_id = t1.id 
                    GROUP BY retur_beli_id
                ) t2
                ON t1.id = t2.retur_beli_id
                LEFT JOIN (
                    SELECT pembelian_id
                    FROM (
                        SELECT *
                        FROM nd_pembayaran_hutang_detail
                        WHERE data_status = 4
                        )t1
                        LEFT JOIN nd_pembayaran_hutang t2
                        ON t1.pembayaran_hutang_id = t2.id
                    WHERE DATE(created) <= '$tanggal_end'

                    ) t3
                ON t1.id=t3.pembelian_id
                WHERE t3.pembelian_id is null
                GROUP BY supplier_id
            ) e
            ON a.id = e.supplier_id
            WHERE a.status_aktif = 1
            ORDER BY a.nama

            ");

        return $query->result();
    }

    function get_mutasi_hutang_bayar_all($toko_id, $tanggal_start,$tanggal_end){
        $query = $this->db->query("SELECT sum(amount) as bayar, pembayaran_type_id, LPAD(MONTH(tanggal),2,'0') as bulan, 
        YEAR(tanggal) as tahun, a.supplier_id, nd_supplier.nama as nama_supplier
        FROM (
            (
                SELECT pembayaran_type_id, amount, pembayaran_hutang_id, tA.id, tanggal_transfer as tanggal, supplier_id
                FROM nd_pembayaran_hutang_nilai tA
                LEFT JOIN nd_pembayaran_hutang tB
                ON tA.pembayaran_hutang_id = tB.id
                WHERE tanggal_transfer >= '$tanggal_start'
                AND tanggal_transfer <= '$tanggal_end'
            )UNION(
                SELECT 99, pembulatan, t1.id, t2.id, tanggal_transfer as tanggal, t1.supplier_id
                FROM nd_pembayaran_hutang t1
                LEFT JOIN (
                    SELECT pembayaran_hutang_id, min(tanggal_transfer) as tanggal_transfer, id
                    FROM nd_pembayaran_hutang_nilai
                    WHERE tanggal_transfer >= '$tanggal_start'
                    AND tanggal_transfer <= '$tanggal_end'
                    GROUP BY pembayaran_hutang_id
                    )t2
                ON t2.pembayaran_hutang_id = t1.id
                WHERE t2.pembayaran_hutang_id is not null
            )UNION(
                SELECT 99, pembulatan, t1.id, t2.id, created as tanggal, t1.supplier_id
                FROM (
                    SELECT *
                    FROM nd_pembayaran_hutang
                    WHERE DATE(created) >= '$tanggal_start'
                    AND DATE(created) <= '$tanggal_end'
                    ) t1
                LEFT JOIN (
                    SELECT pembayaran_hutang_id, min(tanggal_transfer), id
                    FROM nd_pembayaran_hutang_nilai
                    GROUP BY pembayaran_hutang_id
                    )t2
                ON t2.pembayaran_hutang_id = t1.id
                WHERE t2.pembayaran_hutang_id is null
                AND pembulatan > 0
            )
        ) a
        LEFT JOIN nd_pembayaran_hutang b
        ON a.pembayaran_hutang_id = b.id
        LEFT JOIN nd_supplier 
        ON a.supplier_id = nd_supplier.id
        WHERE toko_id = $toko_id
        GROUP BY pembayaran_type_id, supplier_id, MONTH(tanggal), YEAR(tanggal)");

        return $query->result();

    }

    function get_mutasi_hutang_list_detail($supplier_id, $toko_id, $tanggal_start, $tanggal_end){
        // tipe
        // 1 data pembelian
        // 2 pembayaran hutang
        // 3 data retur
        // 4 pembulatan
        // 5 awal
        $query = $this->db->query("SELECT *
            FROM  (
            (
                SELECT nd_pembelian.tanggal, no_faktur, ceil(amount_beli) as amount_beli, 0 as amount_bayar, if(amount_beli - bayar = 0, 1, 2) as status_lunas, 
                nd_pembelian.id as pembelian_id, '' as detail_id,'' as data_status, 1 as tipe
                FROM (
                    SELECT *
                    FROM nd_pembelian
                    WHERE supplier_id = $supplier_id 
                    AND tanggal >= '$tanggal_start'
                    AND tanggal <= '$tanggal_end'
                    AND toko_id = $toko_id
                    AND status_aktif = 1
                    ) nd_pembelian
                LEFT JOIN (
                    SELECT sum(qty * harga_beli) as amount_beli, pembelian_id
                    FROM nd_pembelian_detail t1
                    GROUP BY pembelian_id
                ) nd_pembelian_detail
                ON nd_pembelian.id = nd_pembelian_detail.pembelian_id
                LEFT JOIN (
                    SELECT SUM(amount) as bayar, pembelian_id
                    FROM nd_pembayaran_hutang_detail
                    GROUP BY pembelian_id
                    ) nd_pembayaran_hutang_detail
                ON nd_pembelian.id = nd_pembayaran_hutang_detail.pembelian_id
            )UNION (
                SELECT tanggal_transfer,no_faktur,0, amount, 0 as status_lunas, t1.id, t2.id, data_status, 2 as tipe
                FROM (
                    SELECT *
                    FROM nd_pembayaran_hutang_nilai 
                    WHERE tanggal_transfer >= '$tanggal_start'
                    AND tanggal_transfer <= '$tanggal_end'
                    )t1
                LEFT JOIN (
                    SELECT *
                    FROM nd_pembayaran_hutang
                    WHERE supplier_id = $supplier_id
                    AND toko_id = $toko_id
                ) t2
                ON t2.id = t1.pembayaran_hutang_id
                LEFT JOIN (
                    SELECT pembayaran_hutang_id, group_concat(no_faktur ORDER BY no_faktur asc SEPARATOR', ') as no_faktur, group_concat(data_status) as data_status
                    FROM (
                        (
                            SELECT pembayaran_hutang_id, no_faktur, data_status
                            FROM nd_pembayaran_hutang_detail
                            LEFT JOIN nd_pembelian
                            ON nd_pembayaran_hutang_detail.pembelian_id = nd_pembelian.id 
                            WHERE data_status = 1
                            )UNION(
                            SELECT pembayaran_hutang_id, no_faktur, data_status
                            FROM nd_pembayaran_hutang_detail
                            LEFT JOIN nd_hutang_awal
                            ON nd_pembayaran_hutang_detail.pembelian_id = nd_hutang_awal.id 
                            WHERE data_status = 2
                            )
                        )result
                    GROUP BY pembayaran_hutang_id
                    ) t3
                ON t3.pembayaran_hutang_id = t2.id
                WHERE t2.id is not null
            )UNION (
                SELECT tanggal_transfer,no_faktur,0,amount_retur , 0 as status_lunas, '', t2.id, data_status, 3 as tipe
                FROM (
                    SELECT *
                    FROM nd_pembayaran_hutang_nilai 
                    WHERE tanggal_transfer >= '$tanggal_start'
                    AND tanggal_transfer <= '$tanggal_end'
                    )t1
                LEFT JOIN (
                    SELECT *
                    FROM nd_pembayaran_hutang
                    WHERE supplier_id = $supplier_id
                    AND toko_id = $toko_id
                ) t2
                ON t2.id = t1.pembayaran_hutang_id
                LEFT JOIN (
                    SELECT pembayaran_hutang_id, group_concat(no_faktur ORDER BY no_faktur asc SEPARATOR', ') as no_faktur, group_concat(data_status) as data_status, sum(amount) as amount_retur
                    FROM (
                        SELECT pembayaran_hutang_id, concat('SJ/R/',if(t3.kode is not null, concat(t3.kode,'/'),'LL/'),DATE_FORMAT(t2.tanggal,'%y'),'-',no_sj ) as no_faktur, data_status, t1.amount
                        FROM nd_pembayaran_hutang_detail t1
                        LEFT JOIN nd_retur_beli t2
                        ON t1.pembelian_id = t2.id 
                        LEFT JOIN nd_supplier t3
                        ON t2.supplier_id = t3.id
                        WHERE data_status = 4
                        )result
                    GROUP BY pembayaran_hutang_id
                    ) t3
                ON t3.pembayaran_hutang_id = t2.id
                WHERE t2.id is not null
                AND t3.pembayaran_hutang_id is not null
            )UNION(
                SELECT tanggal_transfer,'pembulatan',0, pembulatan, 0 as status_lunas, '', t1.id, '', 4 as tipe
                FROM (
                    SELECT *
                    FROM nd_pembayaran_hutang
                    WHERE supplier_id = $supplier_id
                    AND toko_id = $toko_id
                ) t1
                LEFT JOIN (
                    SELECT pembayaran_hutang_id, min(tanggal_transfer) as tanggal_transfer, 5 as tipe
                    FROM nd_pembayaran_hutang_nilai 
                    WHERE tanggal_transfer >= '$tanggal_start'
                    AND tanggal_transfer <= '$tanggal_end'
                    GROUP BY pembayaran_hutang_id
                    )t2
                ON t1.id = t2.pembayaran_hutang_id
                WHERE pembulatan != 0
                AND t2.pembayaran_hutang_id is not null
            )UNION(
                SELECT DATE(created),'pembulatan',0, pembulatan, 0 as status_lunas, '', t1.id, '', 6 as tipe
                FROM (
                    SELECT *
                    FROM nd_pembayaran_hutang
                    WHERE supplier_id = $supplier_id
                    AND DATE(created) >= '$tanggal_start'
                    AND DATE(created) <= '$tanggal_end'
                    AND toko_id = $toko_id
                ) t1
                LEFT JOIN (
                    SELECT pembayaran_hutang_id, min(tanggal_transfer) as tanggal_transfer
                    FROM nd_pembayaran_hutang_nilai 
                    GROUP BY pembayaran_hutang_id
                    )t2
                ON t1.id = t2.pembayaran_hutang_id
                WHERE pembulatan > 0
                AND t2.pembayaran_hutang_id is null
            )UNION(
                SELECT tanggal, concat(no_faktur,' (hutang awal)'), amount, 0 as amount_bayar, 0 as status_lunas,'','', '', 7 as tipe
                FROM nd_hutang_awal
                WHERE supplier_id = $supplier_id
                AND toko_id = $toko_id
                AND tanggal >= '$tanggal_start'
                AND tanggal <= '$tanggal_end'      
            ) 
        )a
        ORDER by tanggal, no_faktur asc
            ");

        return $query->result();
    }

    function get_mutasi_hutang_detail_saldo_awal($supplier_id, $toko_id, $tanggal_start){
        $query = $this->db->query("SELECT sum(ifnull(amount_beli,0)) - sum(ifnull(amount_bayar,0)) - sum(ifnull(pembulatan,0)) as saldo_awal
            FROM  (
                (
                    SELECT t1.tanggal, no_faktur, ROUND(amount_beli,0) amount_beli, 0 as amount_bayar, 0 as pembulatan, t1.id
                    FROM (
                        SELECT *
                        FROM nd_pembelian
                        WHERE supplier_id = $supplier_id 
                        AND tanggal < '$tanggal_start'
                        AND toko_id = $toko_id
                        AND status_aktif = 1
                        ) t1
                    LEFT JOIN (
                        SELECT sum(qty * harga_beli) as amount_beli, pembelian_id
                        FROM nd_pembelian_detail t1
                        GROUP BY pembelian_id
                    ) t2
                    ON t1.id = t2.pembelian_id
                ) UNION (
                    SELECT tanggal_transfer,t1.id,0, sum(amount),pembulatan, t2.id
                    FROM (
                        SELECT *
                        FROM nd_pembayaran_hutang
                        WHERE supplier_id = $supplier_id
                        AND toko_id = $toko_id
                    ) t1
                    LEFT JOIN (
                        SELECT *
                        FROM nd_pembayaran_hutang_nilai 
                        WHERE tanggal_transfer < '$tanggal_start'
                        )t2
                    ON t1.id = t2.pembayaran_hutang_id
                    WHERE t2.id is not null
                    GROUP BY pembayaran_hutang_id
                ) UNION (
                    SELECT tanggal, concat(no_faktur, '(hutang awal)') , amount, 0 as amount_bayar, 0, t1.id
                    FROM nd_hutang_awal t1
                    WHERE supplier_id = $supplier_id
                    AND toko_id = $toko_id
                    AND tanggal < '$tanggal_start'
                )UNION(
                    SELECT t1.tanggal, concat('SJ/R/',if(t4.kode is not null, concat(t4.kode,'/'),'LL/'),DATE_FORMAT(t1.tanggal,'%y'),'-',no_sj ), 
                    amount_beli*-1, 0, 0,t1.id
                    FROM (
                        SELECT pembelian_id, t1.amount as amount_beli, t3.tanggal_transfer as tanggal, t1.pembayaran_hutang_id as id, supplier_id
                        FROM (
                            SELECT *
                            FROM nd_pembayaran_hutang_detail
                            WHERE data_status = 4
                            ) t1
                        LEFT JOIN (
                            SELECT *
                            FROM nd_pembayaran_hutang
                            WHERE supplier_id = $supplier_id
                        ) t2
                        ON t1.pembayaran_hutang_id=t2.id
                        LEFT JOIN nd_pembayaran_hutang_nilai t3
                        ON t2.id = t3.pembayaran_hutang_id
                        WHERE  t2.id is not null
                    ) t1
                    LEFT JOIN(
                        SELECT *
                        FROM nd_retur_beli
                        WHERE supplier_id = $supplier_id
                        AND tanggal < '$tanggal_start'
                        AND toko_id = $toko_id
                        AND status_aktif = 1
                    ) t2
                    ON t1.pembelian_id = t2.id
                    LEFT JOIN nd_supplier t4
                    ON t1.supplier_id=t4.id
                    WHERE  t2.id is not null
                )
            )a
            ");

        return $query->result();
    }

//===============================daftar mutasi piutang=============================

    function get_mutasi_piutang($tanggal_start,$tanggal_end, $toko_id){
        // $tanggal = date('Y-m-01', strtotime($tanggal));
        // $tanggal_end = date('Y-m-t', strtotime($tanggal));

        $query = $this->db->query("SELECT sum(amount) as amount, sum(amount_bayar) as amount_bayar, 
            a.nama as nama_customer, a.id as customer_id, sum(penjualan) as penjualan,
            sum(retur_jual) as retur_jual, sum(giro_tolakan) as giro_tolakan, 
            sum(pembayaran_penjualan) as pembayaran_penjualan
            FROM ((
                SELECT customer_id, sum(amount) as amount, sum(amount_bayar) as amount_bayar, 0 as penjualan, 0 as retur_jual, 0 as giro_tolakan, 0 as pembayaran_penjualan
                FROM (
                    (
                        SELECT customer_id, sum(if(tipe != 'c', ifnull(amount,0), (amount*-1) )) as amount, amount_bayar
                        FROM (
                            (
                                SELECT customer_id, sum(ifnull(amount_jual,0))  as amount, sum(ifnull(pembayaran,0)) as amount_bayar, 'a' as tipe
                                FROM (
                                    SELECT *
                                    FROM nd_penjualan
                                    WHERE tanggal < '$tanggal_start'
                                    AND toko_id = $toko_id
                                    AND penjualan_type_id != 3
                                    AND status_aktif = 1
                                    ) nd_penjualan
                                LEFT JOIN (
                                    SELECT sum(qty * harga_jual) as amount_jual, penjualan_id
                                    FROM nd_penjualan_detail t1
                                    LEFT JOIN (
                                        SELECT sum(qty * if(jumlah_roll = 0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, penjualan_detail_id
                                        FROM nd_penjualan_qty_detail
                                        GROUP BY penjualan_detail_id
                                    ) t2
                                    ON t2.penjualan_detail_id = t1.id
                                    GROUP BY penjualan_id
                                ) nd_penjualan_detail
                                ON nd_penjualan.id = nd_penjualan_detail.penjualan_id
                                LEFT JOIN (
                                    SELECT sum(amount) as pembayaran, penjualan_id
                                    FROM nd_pembayaran_penjualan
                                    WHERE pembayaran_type_id != 5
                                    AND amount != 0
                                    GROUP BY penjualan_id
                                    ) nd_pembayaran_penjualan
                                ON nd_penjualan.id = nd_pembayaran_penjualan.penjualan_id
                                WHERE amount_jual - ifnull(pembayaran,0) > 0
                                GROUP BY customer_id
                            )UNION(
                                SELECT customer_id, sum(amount), 0, 'b'
                                FROM nd_piutang_awal
                                WHERE tanggal < '$tanggal_start'
                                AND toko_id = $toko_id
                                GROUP BY customer_id                              
                            )UNION(
                                SELECT customer_id, sum(ifnull(amount_jual,0))  as amount, sum(pembayaran) as amount_bayar, 'c'
                                FROM (
                                    SELECT *
                                    FROM nd_retur_jual
                                    WHERE tanggal < '$tanggal_start'
                                    AND toko_id = $toko_id
                                    AND retur_type_id != 3
                                    AND status_aktif = 1
                                    ) nd_retur_jual
                                LEFT JOIN (
                                    SELECT sum(qty * harga) as amount_jual, retur_jual_id
                                    FROM nd_retur_jual_detail t1
                                    LEFT JOIN (
                                        SELECT sum(qty * if(jumlah_roll = 0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, retur_jual_detail_id
                                        FROM nd_retur_jual_qty
                                        GROUP BY retur_jual_detail_id
                                    ) t2
                                    ON t2.retur_jual_detail_id = t1.id
                                    GROUP BY retur_jual_id
                                ) nd_retur_jual_detail
                                ON nd_retur_jual.id = nd_retur_jual_detail.retur_jual_id
                                LEFT JOIN (
                                    SELECT sum(amount) as pembayaran, retur_jual_id
                                    FROM nd_pembayaran_retur
                                    WHERE pembayaran_type_id != 5
                                    AND amount != 0
                                    GROUP BY retur_jual_id
                                    ) nd_pembayaran_retur
                                ON nd_retur_jual.id = nd_pembayaran_retur.retur_jual_id
                                GROUP BY customer_id
                            )UNION(
                                SELECT customer_id, sum(amount)  as amount, 0 as amount_bayar, 'd' as tipe
                                FROM nd_giro_tolakan tA
                                LEFT JOIN nd_pembayaran_piutang_nilai tB
                                ON tA.pembayaran_piutang_nilai_id = tB.id
                                WHERE tB.created < '$tanggal_start 00:00:00' 
                                GROUP BY customer_id
                            )
                        ) t1
                        GROUP BY customer_id
                    )UNION (
                        SELECT customer_id,0, sum(amount)
                        FROM (
                            SELECT *
                            FROM nd_pembayaran_piutang
                            WHERE toko_id = $toko_id
                            AND status_aktif = 1
                        ) tA
                        LEFT JOIN (
                            SELECT *
                            FROM nd_pembayaran_piutang_nilai 
                            WHERE created < '$tanggal_start 00:00:00'
                            )tB
                        ON tA.id = tB.pembayaran_piutang_id
                        WHERE tB.id is not null
                        GROUP BY customer_id
                    )UNION (
                        SELECT customer_id, 0, sum(pembulatan)
                        FROM (
                            SELECT *
                            FROM nd_pembayaran_piutang_nilai
                            WHERE created < '$tanggal_start 00:00:00'
                            GROUP BY pembayaran_piutang_id
                        ) t1
                        LEFT JOIN nd_pembayaran_piutang t2
                        ON t1.pembayaran_piutang_id = t2.id
                        WHERE status_aktif = 1
                        GROUP BY customer_id
                    )
                ) result
                GROUP BY customer_id
                )UNION(
                    SELECT customer_id, 0, 0,  sum(ifnull(amount_jual,0)) as penjualan, 0, 0, sum(pembayaran)
                    FROM (
                        SELECT *
                        FROM nd_penjualan
                        WHERE tanggal >= '$tanggal_start'
                        AND tanggal <= '$tanggal_end'
                        AND toko_id = $toko_id
                        AND penjualan_type_id != 3
                        AND status_aktif = 1
                        ) nd_penjualan
                    LEFT JOIN (
                        SELECT sum(qty * harga_jual) as amount_jual, penjualan_id
                        FROM nd_penjualan_detail t1
                        LEFT JOIN (
                            SELECT sum(qty * if(jumlah_roll=0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, penjualan_detail_id
                            FROM nd_penjualan_qty_detail
                            GROUP BY penjualan_detail_id
                        ) t2
                        ON t2.penjualan_detail_id = t1.id
                        GROUP BY penjualan_id
                    ) nd_penjualan_detail
                    ON nd_penjualan.id = nd_penjualan_detail.penjualan_id
                    LEFT JOIN (
                        SELECT sum(amount) as pembayaran, penjualan_id
                        FROM nd_pembayaran_penjualan
                        WHERE pembayaran_type_id != 5
                        AND amount != 0
                        GROUP BY penjualan_id
                        ) nd_pembayaran_penjualan
                    ON nd_penjualan.id = nd_pembayaran_penjualan.penjualan_id
                    -- WHERE amount_jual - ifnull(pembayaran,0) > 0
                    GROUP BY customer_id
                )UNION(
                    SELECT customer_id, 0, 0, 0, sum(ifnull(amount_jual,0)), 0, 0
                    FROM (
                        SELECT *
                        FROM nd_retur_jual
                        WHERE tanggal >= '$tanggal_start'
                        AND tanggal <= '$tanggal_end'
                        AND toko_id = $toko_id
                        AND retur_type_id != 3
                        
                        AND status_aktif = 1
                        ) nd_retur_jual
                    LEFT JOIN (
                        SELECT sum(qty * harga) as amount_jual, retur_jual_id
                        FROM nd_retur_jual_detail t1
                        LEFT JOIN (
                            SELECT sum(qty * if(jumlah_roll=0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, retur_jual_detail_id
                            FROM nd_retur_jual_qty
                            GROUP BY retur_jual_detail_id
                        ) t2
                        ON t2.retur_jual_detail_id = t1.id
                        GROUP BY retur_jual_id
                    ) nd_retur_jual_detail
                    ON nd_retur_jual.id = nd_retur_jual_detail.retur_jual_id
                    GROUP BY customer_id
                )UNION(
                    SELECT customer_id, 0, 0, 0, 0, sum(ifnull(amount,0)), 0
                    FROM (
                        SELECT *
                        FROM nd_giro_tolakan
                        WHERE tanggal >= '$tanggal_start'
                        AND tanggal <= '$tanggal_end'
                        ) tA
                    LEFT JOIN nd_pembayaran_piutang_nilai tB
                    ON tA.pembayaran_piutang_nilai_id = tB.id
                    GROUP BY customer_id
                )
            )b
            LEFT JOIN nd_customer a
            ON b.customer_id = a.id
            GROUP BY customer_id
            ORDER BY a.nama asc

            ");

        return $query->result();
    }

    function get_mutasi_piutang_2($tanggal_start,$tanggal_end, $toko_id){
        // $tanggal = date('Y-m-01', strtotime($tanggal));
        // $tanggal_end = date('Y-m-t', strtotime($tanggal));

        $query = $this->db->query("SELECT sum(amount) as amount, sum(amount_bayar) as amount_bayar, 
            a.nama as nama_customer, a.id as customer_id, sum(penjualan) as penjualan,
            sum(retur_jual) as retur_jual, sum(giro_tolakan) as giro_tolakan, 
            sum(pembayaran_penjualan) as pembayaran_penjualan
            FROM (
                (
                    SELECT customer_id, sum(amount) as amount, sum(amount_bayar) as amount_bayar, 0 as penjualan, 0 as retur_jual, 0 as giro_tolakan, 0 as pembayaran_penjualan
                    FROM (
                        (
                            SELECT customer_id, sum(if(tipe != 'c', ifnull(amount,0), (amount*-1) )) as amount, amount_bayar
                            FROM (
                                (
                                    SELECT customer_id, sum(ifnull(amount_jual,0))  as amount, sum(ifnull(pembayaran,0)) as amount_bayar, 'a' as tipe
                                    FROM (
                                        SELECT *
                                        FROM nd_penjualan
                                        WHERE tanggal < '$tanggal_start'
                                        AND toko_id = $toko_id
                                        AND penjualan_type_id = 2
                                        AND status_aktif = 1
                                        ) nd_penjualan
                                    LEFT JOIN (
                                        SELECT sum(qty * harga_jual) as amount_jual, penjualan_id
                                        FROM nd_penjualan_detail t1
                                        LEFT JOIN (
                                            SELECT sum(qty * if(jumlah_roll = 0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, penjualan_detail_id
                                            FROM nd_penjualan_qty_detail
                                            GROUP BY penjualan_detail_id
                                        ) t2
                                        ON t2.penjualan_detail_id = t1.id
                                        GROUP BY penjualan_id
                                    ) nd_penjualan_detail
                                    ON nd_penjualan.id = nd_penjualan_detail.penjualan_id
                                    LEFT JOIN (
                                        SELECT sum(amount) as pembayaran, penjualan_id
                                        FROM nd_pembayaran_penjualan
                                        WHERE pembayaran_type_id != 5
                                        AND amount != 0
                                        GROUP BY penjualan_id
                                        ) nd_pembayaran_penjualan
                                    ON nd_penjualan.id = nd_pembayaran_penjualan.penjualan_id
                                    WHERE amount_jual - ifnull(pembayaran,0) >= 0
                                    GROUP BY customer_id
                                )UNION(
                                    SELECT customer_id, sum(amount), 0, 'b'
                                    FROM nd_piutang_awal
                                    WHERE tanggal < '$tanggal_start'
                                    AND toko_id = $toko_id
                                    GROUP BY customer_id                              
                                )UNION(
                                    SELECT customer_id, sum(ifnull(amount_jual,0))  as amount, sum(pembayaran) as amount_bayar, 'c'
                                    FROM (
                                        SELECT *
                                        FROM nd_retur_jual
                                        WHERE tanggal < '$tanggal_start'
                                        AND toko_id = $toko_id
                                        AND retur_type_id != 3
                                        AND status_aktif = 1
                                        ) nd_retur_jual
                                    LEFT JOIN (
                                        SELECT sum(qty * harga) as amount_jual, retur_jual_id
                                        FROM nd_retur_jual_detail t1
                                        LEFT JOIN (
                                            SELECT sum(qty * if(jumlah_roll = 0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, retur_jual_detail_id
                                            FROM nd_retur_jual_qty
                                            GROUP BY retur_jual_detail_id
                                        ) t2
                                        ON t2.retur_jual_detail_id = t1.id
                                        GROUP BY retur_jual_id
                                    ) nd_retur_jual_detail
                                    ON nd_retur_jual.id = nd_retur_jual_detail.retur_jual_id
                                    LEFT JOIN (
                                        SELECT sum(amount) as pembayaran, retur_jual_id
                                        FROM nd_pembayaran_retur
                                        WHERE pembayaran_type_id != 5
                                        AND amount != 0
                                        GROUP BY retur_jual_id
                                        ) nd_pembayaran_retur
                                    ON nd_retur_jual.id = nd_pembayaran_retur.retur_jual_id
                                    GROUP BY customer_id
                                )UNION(
                                    SELECT customer_id, sum(amount)  as amount, 0 as amount_bayar, 'd' as tipe
                                    FROM nd_giro_tolakan tA
                                    LEFT JOIN nd_pembayaran_piutang_nilai tB
                                    ON tA.pembayaran_piutang_nilai_id = tB.id
                                    WHERE tB.created < '$tanggal_start 00:00:00' 
                                    GROUP BY customer_id
                                )
                            ) t1
                            GROUP BY customer_id
                        )UNION (
                            SELECT customer_id,0, sum(amount)
                            FROM (
                                SELECT *
                                FROM nd_pembayaran_piutang
                                WHERE toko_id = $toko_id
                                AND status_aktif = 1
                            ) tA
                            LEFT JOIN (
                                SELECT *
                                FROM nd_pembayaran_piutang_nilai 
                                WHERE created < '$tanggal_start 00:00:00'
                                )tB
                            ON tA.id = tB.pembayaran_piutang_id
                            WHERE tB.id is not null
                            GROUP BY customer_id
                        )UNION (
                            SELECT customer_id, 0, sum(pembulatan)
                            FROM (
                                SELECT *
                                FROM nd_pembayaran_piutang_nilai
                                WHERE created < '$tanggal_start 00:00:00'
                                GROUP BY pembayaran_piutang_id
                            ) t1
                            LEFT JOIN nd_pembayaran_piutang t2
                            ON t1.pembayaran_piutang_id = t2.id
                            WHERE status_aktif = 1
                            GROUP BY customer_id
                        )
                    ) result
                    GROUP BY customer_id
                )UNION(
                    SELECT customer_id, 0, 0,  sum(ifnull(amount_jual,0) - ifnull(pembayaran,0)) as penjualan, 0, 0, 0
                    FROM (
                        SELECT *
                        FROM nd_penjualan
                        WHERE tanggal >= '$tanggal_start'
                        AND tanggal <= '$tanggal_end'
                        AND toko_id = $toko_id
                        AND penjualan_type_id = 2
                        AND status_aktif = 1
                        ) nd_penjualan
                    LEFT JOIN (
                        SELECT sum(qty * harga_jual) as amount_jual, penjualan_id
                        FROM nd_penjualan_detail t1
                        LEFT JOIN (
                            SELECT sum(qty * if(jumlah_roll=0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, penjualan_detail_id
                            FROM nd_penjualan_qty_detail
                            GROUP BY penjualan_detail_id
                        ) t2
                        ON t2.penjualan_detail_id = t1.id
                        GROUP BY penjualan_id
                    ) nd_penjualan_detail
                    ON nd_penjualan.id = nd_penjualan_detail.penjualan_id
                    LEFT JOIN (
                        SELECT sum(amount) as pembayaran, penjualan_id
                        FROM nd_pembayaran_penjualan
                        WHERE pembayaran_type_id != 5
                        GROUP BY penjualan_id
                        ) nd_pembayaran_penjualan
                    ON nd_penjualan.id = nd_pembayaran_penjualan.penjualan_id
                    GROUP BY customer_id
                )UNION(
                    SELECT customer_id, 0, 0, 0, sum(ifnull(amount_jual,0)), 0, 0
                    FROM (
                        SELECT *
                        FROM nd_retur_jual
                        WHERE tanggal >= '$tanggal_start'
                        AND tanggal <= '$tanggal_end'
                        AND toko_id = $toko_id
                        AND retur_type_id != 3
                        
                        AND status_aktif = 1
                        ) nd_retur_jual
                    LEFT JOIN (
                        SELECT sum(qty * harga) as amount_jual, retur_jual_id
                        FROM nd_retur_jual_detail t1
                        LEFT JOIN (
                            SELECT sum(qty * if(jumlah_roll=0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, retur_jual_detail_id
                            FROM nd_retur_jual_qty
                            GROUP BY retur_jual_detail_id
                        ) t2
                        ON t2.retur_jual_detail_id = t1.id
                        GROUP BY retur_jual_id
                    ) nd_retur_jual_detail
                    ON nd_retur_jual.id = nd_retur_jual_detail.retur_jual_id
                    GROUP BY customer_id
                )UNION(
                    SELECT customer_id, 0, 0, 0, 0, sum(ifnull(amount,0)), 0
                    FROM (
                        SELECT *
                        FROM nd_giro_tolakan
                        WHERE tanggal >= '$tanggal_start'
                        AND tanggal <= '$tanggal_end'
                        ) tA
                    LEFT JOIN nd_pembayaran_piutang_nilai tB
                    ON tA.pembayaran_piutang_nilai_id = tB.id
                    GROUP BY customer_id
                )
            )b
            LEFT JOIN nd_customer a
            ON b.customer_id = a.id
            GROUP BY customer_id
            ORDER BY a.nama asc

            ");

        return $query->result();
    }


    function get_mutasi_piutang_bayar($customer_id, $toko_id, $tanggal_start, $tanggal_end){
        $tanggal_before = $tanggal_start;
        // $tanggal_end = date('Y-m-t', strtotime($tanggal));
        $query = $this->db->query("SELECT sum(amount) as bayar, pembayaran_type_id
            FROM (
                SELECT *  
                FROM nd_pembayaran_piutang_nilai
                WHERE tanggal_transfer >= '$tanggal_before'
                AND tanggal_transfer <= '$tanggal_end'
                ) a
            LEFT JOIN nd_pembayaran_piutang b
            ON a.pembayaran_piutang_id = b.id
            WHERE customer_id = $customer_id
            AND toko_id = $toko_id
            AND status_aktif = 1
            GROUP BY pembayaran_type_id
            ");

        return $query->result();
        // return $this->db->last_query();
    }

    function get_bayar_penjualan($customer_id, $toko_id, $tanggal_start, $tanggal_end){
        $tanggal_before = $tanggal_start;
        // $tanggal_end = date('Y-m-t', strtotime($tanggal));
        $query = $this->db->query("SELECT sum(amount) as bayar, pembayaran_type_id, customer_id
            FROM (
                SELECT *  
                FROM nd_penjualan
                WHERE customer_id = $customer_id
                AND penjualan_type_id != 3
                AND toko_id = $toko_id
                AND tanggal >= '$tanggal_before'
                AND tanggal <= '$tanggal_end'
                AND status_aktif = 1
                ) a
            LEFT JOIN (
                SELECT sum(amount) as amount, penjualan_id, pembayaran_type_id
                FROM nd_pembayaran_penjualan
                WHERE amount != 0
                GROUP BY penjualan_id, pembayaran_type_id
                ) b
            ON a.id = b.penjualan_id
            WHERE pembayaran_type_id != 5
            GROUP BY pembayaran_type_id
            ");

        return $query->result();
        // return $this->db->last_query();
    }

    function get_mutasi_piutang_list_detail($customer_id, $toko_id, $tanggal_start, $tanggal_end){
        $query = $this->db->query("SELECT *
            FROM  (
            (
                SELECT t1.tanggal,t1.id, no_faktur_lengkap as no_faktur, amount_jual, 0 as amount_bayar,'jual' as ket, (amount_jual - ifnull(amount_bayar,0) - ifnull(amount_lunas,0)) as ket_lunas
                FROM (
                    SELECT *
                    FROM vw_penjualan_data
                    WHERE customer_id = $customer_id 
                    AND tanggal >= '$tanggal_start'
                    AND tanggal <= '$tanggal_end'
                    AND toko_id = $toko_id
                    AND penjualan_type_id != 3
                    AND status_aktif = 1
                    ) t1
                LEFT JOIN (
                    SELECT sum(qty * harga_jual) as amount_jual, penjualan_id
                    FROM nd_penjualan_detail
                    LEFT JOIN (
                        SELECT sum(qty* if(jumlah_roll = 0,1, jumlah_roll) ) as qty, penjualan_detail_id
                        FROM nd_penjualan_qty_detail
                        GROUP BY penjualan_detail_id
                        ) nd_penjualan_qty_detail
                    ON nd_penjualan_qty_detail.penjualan_detail_id = nd_penjualan_detail.id
                    GROUP BY penjualan_id
                ) t2
                ON t1.id = t2.penjualan_id
                LEFT JOIN (
                    SELECT sum(amount) as amount_bayar, penjualan_id
                    FROM nd_pembayaran_penjualan
                    WHERE pembayaran_type_id != 5 
                    GROUP BY penjualan_id
                    ) t3
                ON t1.id = t3.penjualan_id
                LEFT JOIN (
                    SELECT sum(amount) as amount_lunas, penjualan_id
                    FROM (
                        SELECT *
                        FROM nd_pembayaran_piutang_detail
                        ) a
                    LEFT JOIN nd_pembayaran_piutang b
                    ON a.pembayaran_piutang_id = b.id
                    WHERE b.status_aktif = 1
                    GROUP BY penjualan_id
                    ) t4 
                ON t1.id = t4.penjualan_id
            )UNION(
                SELECT tanggal, nd_penjualan.id, no_faktur_lengkap,0, bayar, 'bayar jual', 0
                FROM (
                    SELECT sum(amount) as bayar, penjualan_id
                    FROM nd_pembayaran_penjualan
                    WHERE pembayaran_type_id != 5
                    AND amount != 0
                    GROUP BY penjualan_id
                ) nd_pembayaran_penjualan
                LEFT JOIN (
                    SELECT *
                    FROM vw_penjualan_data
                    WHERE customer_id = $customer_id 
                    AND tanggal >= '$tanggal_start'
                    AND tanggal <= '$tanggal_end'
                    AND toko_id = $toko_id
                    AND penjualan_type_id != 3
                    AND status_aktif = 1
                    ) nd_penjualan
                ON nd_penjualan.id = nd_pembayaran_penjualan.penjualan_id
                WHERE nd_penjualan.id is not null
            )UNION(
                SELECT DATE_FORMAT(t2.created,'%Y-%m-%d'),t1.id,no_faktur,0, amount, 'bayar piutang', t2.id
                FROM (
                    SELECT *
                    FROM nd_pembayaran_piutang
                    WHERE customer_id = $customer_id
                    AND toko_id = $toko_id
                    AND status_aktif = 1
                    ) t1
                LEFT JOIN (
                    SELECT *
                    FROM nd_pembayaran_piutang_nilai
                    WHERE created >= '$tanggal_start 00:00:00'
                    AND created <= '$tanggal_end 23:59:59'
                    ) t2
                ON t1.id = t2.pembayaran_piutang_id
                LEFT JOIN (
                    SELECT pembayaran_piutang_id, group_concat(no_faktur SEPARATOR', ') as no_faktur
                    FROM (
                        (
                            SELECT pembayaran_piutang_id, group_concat(no_faktur_lengkap ORDER BY no_faktur SEPARATOR', ' ) as no_faktur
                            FROM (
                                SELECT tA.*
                                FROM nd_pembayaran_piutang_detail tA
                                LEFT JOIN nd_pembayaran_piutang tB
                                ON tA.pembayaran_piutang_id = tB.id
                                WHERE data_status = 1
                                AND status_aktif = 1
                            ) nd_pembayaran_piutang_detail
                            LEFT JOIN (
                                SELECT *
                                FROM vw_penjualan_data
                                ) nd_penjualan
                            ON nd_pembayaran_piutang_detail.penjualan_id = nd_penjualan.id 
                            GROUP BY pembayaran_piutang_id
                        )UNION(
                            SELECT pembayaran_piutang_id, group_concat(no_faktur_lengkap SEPARATOR ' ,')
                            FROM (
                                SELECT tA.*
                                FROM nd_pembayaran_piutang_detail tA
                                LEFT JOIN nd_pembayaran_piutang tB
                                ON tA.pembayaran_piutang_id = tB.id
                                WHERE data_status = 2
                                AND status_aktif = 1
                            ) nd_pembayaran_piutang_detail
                            LEFT JOIN (
                                SELECT *, concat(no_faktur,' (piutang awal)') as no_faktur_lengkap
                                FROM nd_piutang_awal
                                ) nd_penjualan
                            ON nd_pembayaran_piutang_detail.penjualan_id = nd_penjualan.id 
                            GROUP BY pembayaran_piutang_id
                        )UNION(
                            SELECT pembayaran_piutang_id, group_concat(no_faktur_lengkap ORDER BY no_faktur SEPARATOR', ' ) as no_faktur
                            FROM (
                                SELECT tA.*
                                FROM nd_pembayaran_piutang_detail tA
                                LEFT JOIN nd_pembayaran_piutang tB
                                ON tA.pembayaran_piutang_id = tB.id
                                WHERE data_status = 3
                                AND status_aktif = 1
                            ) nd_pembayaran_piutang_detail
                            LEFT JOIN (
                                SELECT *, concat('FRJ', DATE_FORMAT(tanggal,'%d%m%y'),'-',LPAD(no_faktur,3,'0'),'(retur)') as no_faktur_lengkap
                                FROM nd_retur_jual
                                ) nd_retur_jual
                            ON nd_pembayaran_piutang_detail.penjualan_id = nd_retur_jual.id 
                            GROUP BY pembayaran_piutang_id
                        )UNION(
                            SELECT pembayaran_piutang_id, concat('Tolakan Giro ', group_concat(no_giro ORDER BY no_giro SEPARATOR', ' ) ) as no_faktur
                            FROM (
                                SELECT tA.*
                                FROM nd_pembayaran_piutang_detail tA
                                LEFT JOIN nd_pembayaran_piutang tB
                                ON tA.pembayaran_piutang_id = tB.id
                                WHERE data_status = 4
                                AND status_aktif = 1
                            ) tA
                            LEFT JOIN (
                                SELECT t1.*, no_giro
                                FROM nd_giro_tolakan t1
                                LEFT JOIN nd_pembayaran_piutang_nilai t2
                                ON t1.pembayaran_piutang_nilai_id = t2.id
                            ) tB
                            ON tA.penjualan_id = tB.id 
                            GROUP BY pembayaran_piutang_id
                        )
                    ) a
                    GROUP BY pembayaran_piutang_id
                ) t3
                ON t3.pembayaran_piutang_id = t1.id
                WHERE t2.id is not null
            )UNION(
                SELECT tanggal,id, concat(no_faktur,' (piutang awal)'), amount, 0 as amount_bayar,'piutang awal',0
                FROM nd_piutang_awal
                WHERE customer_id = $customer_id
                AND toko_id = $toko_id
                AND tanggal >= '$tanggal_start'
                AND tanggal <= '$tanggal_end'               
            )UNION(
                SELECT DATE(created), b.id, 'Pembulatan', 0, pembulatan, '' , 0
                FROM nd_pembayaran_piutang b
                WHERE customer_id = $customer_id
                AND toko_id = $toko_id
                AND pembulatan != 0
                AND status_aktif = 1
                AND DATE(created) >= '$tanggal_start'
                AND DATE(created) <= '$tanggal_end'
            )UNION(
                SELECT tanggal_transfer, b.id, 'Lain2', 0, lain2, '' , 0
                FROM (
                    SELECT MAX(tanggal_transfer) as tanggal_transfer, pembayaran_piutang_id
                    FROM nd_pembayaran_piutang_nilai
                    WHERE tanggal_transfer >= '$tanggal_start'
                    AND tanggal_transfer <= '$tanggal_end'
                    GROUP BY pembayaran_piutang_id
                    ) a
                LEFT JOIN nd_pembayaran_piutang b
                ON a.pembayaran_piutang_id = b.id
                WHERE customer_id = $customer_id
                AND toko_id = $toko_id
                AND lain2 != 0
                AND status_aktif = 1
            )UNION(
                SELECT t1.tanggal,t1.id, no_faktur_lengkap as no_faktur, amount_jual * -1, 0 as amount_bayar,'jual' as ket, (amount_jual - ifnull(amount_bayar,0) - ifnull(amount_lunas,0)) as ket_lunas
                FROM (
                    SELECT *, concat('FRJ', DATE_FORMAT(tanggal,'%d%m%y'),'-',LPAD(no_faktur,4,'0')) as no_faktur_lengkap
                    FROM nd_retur_jual
                    WHERE customer_id = $customer_id 
                    AND tanggal >= '$tanggal_start'
                    AND tanggal <= '$tanggal_end'
                    AND toko_id = $toko_id
                    AND retur_type_id != 3
                    AND status_aktif = 1
                    ) t1
                LEFT JOIN (
                    SELECT sum(qty * harga) as amount_jual, retur_jual_id
                    FROM nd_retur_jual_detail
                    LEFT JOIN (
                        SELECT sum(qty* if(jumlah_roll = 0,1, jumlah_roll) ) as qty, retur_jual_detail_id
                        FROM nd_retur_jual_qty
                        GROUP BY retur_jual_detail_id
                        ) nd_retur_jual_qty_detail
                    ON nd_retur_jual_qty_detail.retur_jual_detail_id = nd_retur_jual_detail.id
                    GROUP BY retur_jual_id
                ) t2
                ON t1.id = t2.retur_jual_id
                LEFT JOIN (
                    SELECT sum(amount) as amount_bayar, retur_jual_id
                    FROM nd_pembayaran_retur
                    WHERE pembayaran_type_id != 5 
                    GROUP BY retur_jual_id
                    ) t3
                ON t1.id = t3.retur_jual_id
                LEFT JOIN (
                    SELECT sum(amount) as amount_lunas, penjualan_id
                    FROM (
                        SELECT *
                        FROM nd_pembayaran_piutang_detail
                        WHERE data_status = 3
                        ) a
                    LEFT JOIN nd_pembayaran_piutang b
                    ON a.pembayaran_piutang_id = b.id
                    WHERE b.status_aktif = 1
                    GROUP BY penjualan_id
                    ) t4 
                ON t1.id = t4.penjualan_id
            )UNION(
                SELECT t1.tanggal,t1.id, no_giro, t2.amount, 0 as amount_bayar,'tolakan_giro' as ket, ifnull((t4.amount_detail - t4.amount_bayar),-100) as ket_lunas
                FROM (
                    SELECT *
                    FROM nd_giro_tolakan
                    WHERE customer_id = $customer_id 
                    AND tanggal >= '$tanggal_start'
                    AND tanggal <= '$tanggal_end'
                ) t1
                LEFT JOIN nd_pembayaran_piutang_nilai t2
                ON t1.pembayaran_piutang_nilai_id = t2.id
                LEFT JOIN (
                    SELECT penjualan_id, amount, pembayaran_piutang_id
                    FROM (
                        SELECT *
                        FROM nd_pembayaran_piutang_detail
                        WHERE data_status = 4
                        ) t_1
                    LEFT JOIN nd_pembayaran_piutang t_2
                    ON t_1.pembayaran_piutang_id = t_2.id
                    WHERE status_aktif = 1
                )t3
                ON t1.id=t3.penjualan_id
                LEFT JOIN (
                    SELECT amount_bayar + pembulatan as amount_bayar, amount_detail, t_1.id
                    FROM (
                        SELECT *
                        FROM nd_pembayaran_piutang
                        WHERE customer_id = $customer_id
                        )t_1
                    LEFT JOIN (
                        SELECT sum(amount) as amount_detail, pembayaran_piutang_id
                        FROM nd_pembayaran_piutang_detail
                        GROUP BY pembayaran_piutang_id
                        )t_2
                    ON t_2.pembayaran_piutang_id = t_1.id
                    LEFT JOIN(
                        SELECT sum(amount) as amount_bayar, pembayaran_piutang_id
                        FROM nd_pembayaran_piutang_nilai tA
                        LEFT JOIN nd_giro_tolakan tB
                        ON tA.id=tB.pembayaran_piutang_nilai_id
                        WHERE tB.id is null
                        GROUP BY pembayaran_piutang_id
                        )t_3
                    ON t_1.id = t_3.pembayaran_piutang_id
                ) t4
                ON t3.pembayaran_piutang_id = t4.id

            )
        )a
        ORDER by tanggal, no_faktur asc
        ");

        return $query->result();
        // return $this->db->last_query();

    }

    function get_mutasi_piutang_list_detail_2($customer_id, $toko_id, $tanggal_start, $tanggal_end){
        $query = $this->db->query("SELECT *
            FROM  (
            (
                SELECT t1.tanggal,t1.id, no_faktur_lengkap as no_faktur, amount_jual, amount_bayar as amount_bayar,'jual' as ket, (amount_jual - ifnull(amount_bayar,0) - ifnull(amount_lunas,0)) as ket_lunas
                FROM (
                    SELECT *
                    FROM vw_penjualan_data
                    WHERE customer_id = $customer_id 
                    AND tanggal >= '$tanggal_start'
                    AND tanggal <= '$tanggal_end'
                    AND toko_id = $toko_id
                    AND penjualan_type_id = 2
                    AND status_aktif = 1
                    ) t1
                LEFT JOIN (
                    SELECT sum(qty * harga_jual) as amount_jual, penjualan_id
                    FROM nd_penjualan_detail
                    LEFT JOIN (
                        SELECT sum(qty* if(jumlah_roll = 0,1, jumlah_roll) ) as qty, penjualan_detail_id
                        FROM nd_penjualan_qty_detail
                        GROUP BY penjualan_detail_id
                        ) nd_penjualan_qty_detail
                    ON nd_penjualan_qty_detail.penjualan_detail_id = nd_penjualan_detail.id
                    GROUP BY penjualan_id
                ) t2
                ON t1.id = t2.penjualan_id
                LEFT JOIN (
                    SELECT sum(amount) as amount_bayar, penjualan_id
                    FROM nd_pembayaran_penjualan
                    WHERE pembayaran_type_id != 5 
                    GROUP BY penjualan_id
                    ) t3
                ON t1.id = t3.penjualan_id
                LEFT JOIN (
                    SELECT sum(amount) as amount_lunas, penjualan_id
                    FROM (
                        SELECT *
                        FROM nd_pembayaran_piutang_detail
                        ) a
                    LEFT JOIN nd_pembayaran_piutang b
                    ON a.pembayaran_piutang_id = b.id
                    WHERE b.status_aktif = 1
                    GROUP BY penjualan_id
                    ) t4 
                ON t1.id = t4.penjualan_id
                WHERE amount_jual - ifnull(amount_bayar,0) > 0

            )UNION(
                SELECT DATE_FORMAT(t2.created,'%Y-%m-%d'),t1.id,no_faktur,0, amount, 'bayar piutang', t2.id
                FROM (
                    SELECT *
                    FROM nd_pembayaran_piutang
                    WHERE customer_id = $customer_id
                    AND toko_id = $toko_id
                    AND status_aktif = 1
                    ) t1
                LEFT JOIN (
                    SELECT *
                    FROM nd_pembayaran_piutang_nilai
                    WHERE created >= '$tanggal_start 00:00:00'
                    AND created <= '$tanggal_end 23:59:59'
                    ) t2
                ON t1.id = t2.pembayaran_piutang_id
                LEFT JOIN (
                    SELECT pembayaran_piutang_id, group_concat(no_faktur SEPARATOR', ') as no_faktur
                    FROM (
                        (
                            SELECT pembayaran_piutang_id, group_concat(no_faktur_lengkap ORDER BY no_faktur SEPARATOR', ' ) as no_faktur
                            FROM (
                                SELECT tA.*
                                FROM nd_pembayaran_piutang_detail tA
                                LEFT JOIN nd_pembayaran_piutang tB
                                ON tA.pembayaran_piutang_id = tB.id
                                WHERE data_status = 1
                                AND status_aktif = 1
                            ) nd_pembayaran_piutang_detail
                            LEFT JOIN (
                                SELECT *
                                FROM vw_penjualan_data
                                ) nd_penjualan
                            ON nd_pembayaran_piutang_detail.penjualan_id = nd_penjualan.id 
                            GROUP BY pembayaran_piutang_id
                        )UNION(
                            SELECT pembayaran_piutang_id, group_concat(no_faktur_lengkap SEPARATOR ' ,')
                            FROM (
                                SELECT tA.*
                                FROM nd_pembayaran_piutang_detail tA
                                LEFT JOIN nd_pembayaran_piutang tB
                                ON tA.pembayaran_piutang_id = tB.id
                                WHERE data_status = 2
                                AND status_aktif = 1
                            ) nd_pembayaran_piutang_detail
                            LEFT JOIN (
                                SELECT *, concat(no_faktur,' (piutang awal)') as no_faktur_lengkap
                                FROM nd_piutang_awal
                                ) nd_penjualan
                            ON nd_pembayaran_piutang_detail.penjualan_id = nd_penjualan.id 
                            GROUP BY pembayaran_piutang_id
                        )UNION(
                            SELECT pembayaran_piutang_id, group_concat(no_faktur_lengkap ORDER BY no_faktur SEPARATOR', ' ) as no_faktur
                            FROM (
                                SELECT tA.*
                                FROM nd_pembayaran_piutang_detail tA
                                LEFT JOIN nd_pembayaran_piutang tB
                                ON tA.pembayaran_piutang_id = tB.id
                                WHERE data_status = 3
                                AND status_aktif = 1
                            ) nd_pembayaran_piutang_detail
                            LEFT JOIN (
                                SELECT *, concat('FRJ', DATE_FORMAT(tanggal,'%d%m%y'),'-',LPAD(no_faktur,3,'0'),'(retur)') as no_faktur_lengkap
                                FROM nd_retur_jual
                                ) nd_retur_jual
                            ON nd_pembayaran_piutang_detail.penjualan_id = nd_retur_jual.id 
                            GROUP BY pembayaran_piutang_id
                        )UNION(
                            SELECT pembayaran_piutang_id, concat('Tolakan Giro ', group_concat(no_giro ORDER BY no_giro SEPARATOR', ' ) ) as no_faktur
                            FROM (
                                SELECT tA.*
                                FROM nd_pembayaran_piutang_detail tA
                                LEFT JOIN nd_pembayaran_piutang tB
                                ON tA.pembayaran_piutang_id = tB.id
                                WHERE data_status = 4
                                AND status_aktif = 1
                            ) tA
                            LEFT JOIN (
                                SELECT t1.*, no_giro
                                FROM nd_giro_tolakan t1
                                LEFT JOIN nd_pembayaran_piutang_nilai t2
                                ON t1.pembayaran_piutang_nilai_id = t2.id
                            ) tB
                            ON tA.penjualan_id = tB.id 
                            GROUP BY pembayaran_piutang_id
                        )
                    ) a
                    GROUP BY pembayaran_piutang_id
                ) t3
                ON t3.pembayaran_piutang_id = t1.id
                WHERE t2.id is not null
            )UNION(
                SELECT tanggal,id, concat(no_faktur,' (piutang awal)'), amount, 0 as amount_bayar,'piutang awal',0
                FROM nd_piutang_awal
                WHERE customer_id = $customer_id
                AND toko_id = $toko_id
                AND tanggal >= '$tanggal_start'
                AND tanggal <= '$tanggal_end'               
            )UNION(
                SELECT DATE(created), b.id, 'Pembulatan', 0, pembulatan, '' , 0
                FROM nd_pembayaran_piutang b
                WHERE customer_id = $customer_id
                AND toko_id = $toko_id
                AND pembulatan != 0
                AND status_aktif = 1
                AND DATE(created) >= '$tanggal_start'
                AND DATE(created) <= '$tanggal_end'
            )UNION(
                SELECT tanggal_transfer, b.id, 'Lain2', 0, lain2, '' , 0
                FROM (
                    SELECT MAX(tanggal_transfer) as tanggal_transfer, pembayaran_piutang_id
                    FROM nd_pembayaran_piutang_nilai
                    WHERE tanggal_transfer >= '$tanggal_start'
                    AND tanggal_transfer <= '$tanggal_end'
                    GROUP BY pembayaran_piutang_id
                    ) a
                LEFT JOIN nd_pembayaran_piutang b
                ON a.pembayaran_piutang_id = b.id
                WHERE customer_id = $customer_id
                AND toko_id = $toko_id
                AND lain2 != 0
                AND status_aktif = 1
            )UNION(
                SELECT t1.tanggal,t1.id, no_faktur_lengkap as no_faktur, amount_jual * -1, 0 as amount_bayar,'jual' as ket, (amount_jual - ifnull(amount_bayar,0) - ifnull(amount_lunas,0)) as ket_lunas
                FROM (
                    SELECT *, concat('FRJ', DATE_FORMAT(tanggal,'%d%m%y'),'-',LPAD(no_faktur,4,'0')) as no_faktur_lengkap
                    FROM nd_retur_jual
                    WHERE customer_id = $customer_id 
                    AND tanggal >= '$tanggal_start'
                    AND tanggal <= '$tanggal_end'
                    AND toko_id = $toko_id
                    AND retur_type_id != 3
                    AND status_aktif = 1
                    ) t1
                LEFT JOIN (
                    SELECT sum(qty * harga) as amount_jual, retur_jual_id
                    FROM nd_retur_jual_detail
                    LEFT JOIN (
                        SELECT sum(qty* if(jumlah_roll = 0,1, jumlah_roll) ) as qty, retur_jual_detail_id
                        FROM nd_retur_jual_qty
                        GROUP BY retur_jual_detail_id
                        ) nd_retur_jual_qty_detail
                    ON nd_retur_jual_qty_detail.retur_jual_detail_id = nd_retur_jual_detail.id
                    GROUP BY retur_jual_id
                ) t2
                ON t1.id = t2.retur_jual_id
                LEFT JOIN (
                    SELECT sum(amount) as amount_bayar, retur_jual_id
                    FROM nd_pembayaran_retur
                    WHERE pembayaran_type_id != 5 
                    GROUP BY retur_jual_id
                    ) t3
                ON t1.id = t3.retur_jual_id
                LEFT JOIN (
                    SELECT sum(amount) as amount_lunas, penjualan_id
                    FROM (
                        SELECT *
                        FROM nd_pembayaran_piutang_detail
                        WHERE data_status = 3
                        ) a
                    LEFT JOIN nd_pembayaran_piutang b
                    ON a.pembayaran_piutang_id = b.id
                    WHERE b.status_aktif = 1
                    GROUP BY penjualan_id
                    ) t4 
                ON t1.id = t4.penjualan_id
            )UNION(
                SELECT t1.tanggal,t1.id, no_giro, t2.amount, 0 as amount_bayar,'tolakan_giro' as ket, ifnull((t4.amount_detail - t4.amount_bayar),-100) as ket_lunas
                FROM (
                    SELECT *
                    FROM nd_giro_tolakan
                    WHERE customer_id = $customer_id 
                    AND tanggal >= '$tanggal_start'
                    AND tanggal <= '$tanggal_end'
                ) t1
                LEFT JOIN nd_pembayaran_piutang_nilai t2
                ON t1.pembayaran_piutang_nilai_id = t2.id
                LEFT JOIN (
                    SELECT penjualan_id, amount, pembayaran_piutang_id
                    FROM (
                        SELECT *
                        FROM nd_pembayaran_piutang_detail
                        WHERE data_status = 4
                        ) t_1
                    LEFT JOIN nd_pembayaran_piutang t_2
                    ON t_1.pembayaran_piutang_id = t_2.id
                    WHERE status_aktif = 1
                )t3
                ON t1.id=t3.penjualan_id
                LEFT JOIN (
                    SELECT amount_bayar + pembulatan as amount_bayar, amount_detail, t_1.id
                    FROM (
                        SELECT *
                        FROM nd_pembayaran_piutang
                        WHERE customer_id = $customer_id
                        )t_1
                    LEFT JOIN (
                        SELECT sum(amount) as amount_detail, pembayaran_piutang_id
                        FROM nd_pembayaran_piutang_detail
                        GROUP BY pembayaran_piutang_id
                        )t_2
                    ON t_2.pembayaran_piutang_id = t_1.id
                    LEFT JOIN(
                        SELECT sum(amount) as amount_bayar, pembayaran_piutang_id
                        FROM nd_pembayaran_piutang_nilai tA
                        LEFT JOIN nd_giro_tolakan tB
                        ON tA.id=tB.pembayaran_piutang_nilai_id
                        WHERE tB.id is null
                        GROUP BY pembayaran_piutang_id
                        )t_3
                    ON t_1.id = t_3.pembayaran_piutang_id
                ) t4
                ON t3.pembayaran_piutang_id = t4.id

            )
        )a
        ORDER by tanggal, no_faktur asc
        ");

        return $query->result();
        // return $this->db->last_query();

    }

    function get_mutasi_piutang_saldo_awal($customer_id, $toko_id, $tanggal){
        $query = $this->db->query("SELECT sum(ifnull(amount_beli,0)) - sum(ifnull(amount_bayar,0)) as saldo_awal
            FROM  (
                (
                    SELECT nd_penjualan.tanggal, no_faktur_lengkap as no_faktur, amount_beli, pembayaran as amount_bayar, 'a' as tipe
                    FROM (
                        SELECT *
                        FROM vw_penjualan_data
                        WHERE customer_id = $customer_id
                        AND tanggal < '$tanggal'
                        AND toko_id = $toko_id
                        AND status_aktif = 1
                        AND penjualan_type_id = 2
                    ) nd_penjualan
                    LEFT JOIN (
                        SELECT sum(qty * harga_jual) as amount_beli, penjualan_id
                        FROM nd_penjualan_detail
                        LEFT JOIN (
                            SELECT sum(qty*if(jumlah_roll = 0,1, jumlah_roll)) as qty, penjualan_detail_id
                            FROM nd_penjualan_qty_detail
                            GROUP BY penjualan_detail_id
                        ) nd_penjualan_qty_detail
                        ON nd_penjualan_qty_detail.penjualan_detail_id = nd_penjualan_detail.id
                        GROUP BY penjualan_id
                    ) nd_penjualan_detail
                    ON nd_penjualan.id = nd_penjualan_detail.penjualan_id
                    LEFT JOIN (
                        SELECT sum(amount) as pembayaran, penjualan_id
                        FROM nd_pembayaran_penjualan
                        WHERE pembayaran_type_id != 5
                        GROUP BY penjualan_id
                        ) nd_pembayaran_penjualan
                    ON nd_penjualan.id = nd_pembayaran_penjualan.penjualan_id
                )UNION(
                    SELECT tanggal_transfer,nd_pembayaran_piutang.id,0, amount_bayar, 'b'
                    FROM (
                        SELECT *
                        FROM nd_pembayaran_piutang
                        WHERE customer_id = $customer_id
                        AND toko_id = $toko_id
                        AND status_aktif = 1
                        ) nd_pembayaran_piutang
                    LEFT JOIN (
                        SELECT sum(amount) as amount_bayar, pembayaran_piutang_id, tanggal_transfer
                        FROM nd_pembayaran_piutang_nilai
                        WHERE created < '$tanggal 00:00:00'
                        GROUP BY pembayaran_piutang_id
                        ) nd_pembayaran_piutang_nilai
                    ON nd_pembayaran_piutang.id = nd_pembayaran_piutang_nilai.pembayaran_piutang_id
                )UNION(
                    SELECT tanggal, no_faktur, amount, 0 as amount_bayar, 'c'
                    FROM nd_piutang_awal
                    WHERE customer_id = $customer_id 
                    AND tanggal < '$tanggal'
                    AND toko_id = $toko_id
                )UNION(
                    SELECT tanggal_transfer, b.id, 0, pembulatan,'d'
                    FROM (
                        SELECT MAX(tanggal_transfer) as tanggal_transfer, pembayaran_piutang_id
                        FROM nd_pembayaran_piutang_nilai
                        WHERE created < '$tanggal 00:00:00'
                        GROUP BY pembayaran_piutang_id
                        ) a
                    LEFT JOIN nd_pembayaran_piutang b
                    ON a.pembayaran_piutang_id = b.id
                    WHERE customer_id = $customer_id
                    AND toko_id = $toko_id
                    AND status_aktif = 1
                )UNION(
                    SELECT nd_retur_jual.tanggal, no_faktur_lengkap as no_faktur, amount_beli*-1, pembayaran*-1 as amount_bayar, 'e'
                    FROM (
                        SELECT *, concat('FRJ', DATE_FORMAT(tanggal,'%d%m%y'),'-',$this->pre_faktur,LPAD(no_faktur,4,'0')) as no_faktur_lengkap
                        FROM nd_retur_jual
                        WHERE customer_id = $customer_id 
                        AND tanggal < '$tanggal'
                        AND toko_id = $toko_id
                        AND status_aktif = 1
                        AND retur_type_id !=3
                    ) nd_retur_jual
                    LEFT JOIN (
                        SELECT sum(qty * harga) as amount_beli, retur_jual_id
                        FROM nd_retur_jual_detail
                        LEFT JOIN (
                            SELECT sum(qty*jumlah_roll) as qty, retur_jual_detail_id
                            FROM nd_retur_jual_qty
                            GROUP BY retur_jual_detail_id
                        ) nd_retur_jual_qty_detail
                        ON nd_retur_jual_qty_detail.retur_jual_detail_id = nd_retur_jual_detail.id
                        GROUP BY retur_jual_id
                    ) nd_retur_jual_detail
                    ON nd_retur_jual.id = nd_retur_jual_detail.retur_jual_id
                    LEFT JOIN (
                        SELECT sum(amount) as pembayaran, retur_jual_id
                        FROM nd_pembayaran_retur
                        WHERE pembayaran_type_id != 5
                        GROUP BY retur_jual_id
                        ) nd_pembayaran_retur
                    ON nd_retur_jual.id = nd_pembayaran_retur.retur_jual_id 
                )UNION(
                    SELECT tanggal, no_giro as no_faktur, amount, 0, 'f' as tipe
                    FROM (
                        SELECT *
                        FROM nd_giro_tolakan
                        WHERE tanggal < '$tanggal'
                    ) t1
                    LEFT JOIN nd_pembayaran_piutang_nilai t2
                    ON t1.pembayaran_piutang_nilai_id = t2.id 
                )
            )a
            ");

        return $query->result();
    }

    function get_pembulatan_piutang($customer_id, $toko_id, $tanggal_start, $tanggal_end){
        $tanggal_before = $tanggal_start;
        // $tanggal_end = date('Y-m-t', strtotime($tanggal));
        $query = $this->db->query("SELECT sum(pembulatan) as pembulatan 
            FROM (
                SELECT *  
                FROM nd_pembayaran_piutang_nilai
                WHERE tanggal_transfer >= '$tanggal_before'
                AND tanggal_transfer <= '$tanggal_end'
                GROUP BY pembayaran_piutang_id
                ) a
            LEFT JOIN nd_pembayaran_piutang b
            ON a.pembayaran_piutang_id = b.id
            WHERE customer_id = $customer_id
            AND toko_id = $toko_id
            ");

        return $query->result();
        // return $this->db->last_query();
    }

    //=================================================================================================

    function get_mutasi_piutang_bayar_all($customer_id, $toko_id, $tanggal_start, $tanggal_end){
        $tanggal_before = $tanggal_start;
        // $tanggal_end = date('Y-m-t', strtotime($tanggal));
        $query = $this->db->query("SELECT sum(amount) as bayar, pembayaran_type_id, customer_id
            FROM (
                SELECT *  
                FROM nd_pembayaran_piutang_nilai
                WHERE created >= '$tanggal_before 00:00:00'
                AND created <= '$tanggal_end 23:59:59'
                ) a
            LEFT JOIN nd_pembayaran_piutang b
            ON a.pembayaran_piutang_id = b.id
            WHERE customer_id IN( $customer_id )
            AND toko_id = $toko_id
            AND status_aktif = 1
            GROUP BY pembayaran_type_id, customer_id
            ");

        return $query->result();
        // return $this->db->last_query();
    }

    function get_bayar_penjualan_by_created($customer_id, $toko_id, $tanggal_start, $tanggal_end){
        $tanggal_before = $tanggal_start;
		$this->db->simple_query('SET SESSION group_concat_max_len=15000');
        // $tanggal_end = date('Y-m-t', strtotime($tanggal));
        $query = $this->db->query("SELECT sum(amount) as bayar, pembayaran_type_id, customer_id
            FROM (
                SELECT *  
                FROM nd_penjualan
                WHERE customer_id IN ($customer_id)
                AND penjualan_type_id != 3
                AND toko_id = $toko_id
                AND tanggal >= '$tanggal_before'
                AND tanggal <= '$tanggal_end'
                AND status_aktif = 1
                ) a
            LEFT JOIN (
                SELECT sum(amount) as amount, penjualan_id, pembayaran_type_id
                FROM nd_pembayaran_penjualan
                WHERE amount != 0
                GROUP BY penjualan_id, pembayaran_type_id
                ) b
            ON a.id = b.penjualan_id
            WHERE pembayaran_type_id != 5
            GROUP BY pembayaran_type_id, customer_id
            ");

        return $query->result();
        // return $this->db->last_query();
    }

    function get_mutasi_piutang_list_detail_by_created($customer_id, $toko_id, $tanggal_start, $tanggal_end){
        $query = $this->db->query("SELECT *
            FROM  (
            (
                SELECT t1.tanggal,t1.id, no_faktur_lengkap as no_faktur, amount_jual, 0 as amount_bayar,'jual' as ket, (amount_jual - ifnull(amount_bayar,0) - ifnull(amount_lunas,0)) as ket_lunas
                FROM (
                    SELECT *
                    FROM vw_penjualan_data
                    WHERE customer_id = $customer_id 
                    AND tanggal >= '$tanggal_start'
                    AND tanggal <= '$tanggal_end'
                    AND toko_id = $toko_id
                    AND penjualan_type_id != 3
                    AND status_aktif = 1
                    ) t1
                LEFT JOIN (
                    SELECT sum(qty * harga_jual) as amount_jual, penjualan_id
                    FROM nd_penjualan_detail
                    LEFT JOIN (
                        SELECT sum(qty* if(jumlah_roll = 0,1, jumlah_roll) ) as qty, penjualan_detail_id
                        FROM nd_penjualan_qty_detail
                        GROUP BY penjualan_detail_id
                        ) nd_penjualan_qty_detail
                    ON nd_penjualan_qty_detail.penjualan_detail_id = nd_penjualan_detail.id
                    GROUP BY penjualan_id
                ) t2
                ON t1.id = t2.penjualan_id
                LEFT JOIN (
                    SELECT sum(amount) as amount_bayar, penjualan_id
                    FROM nd_pembayaran_penjualan
                    WHERE pembayaran_type_id != 5 
                    GROUP BY penjualan_id
                    ) t3
                ON t1.id = t3.penjualan_id
                LEFT JOIN (
                    SELECT sum(amount) as amount_lunas, penjualan_id
                    FROM (
                        SELECT *
                        FROM nd_pembayaran_piutang_detail
                        ) a
                    LEFT JOIN nd_pembayaran_piutang b
                    ON a.pembayaran_piutang_id = b.id
                    WHERE b.status_aktif = 1
                    GROUP BY penjualan_id
                    ) t4 
                ON t1.id = t4.penjualan_id
            )UNION(
                SELECT tanggal, nd_penjualan.id, no_faktur_lengkap,0, bayar, 'bayar jual', 0
                FROM (
                    SELECT sum(amount) as bayar, penjualan_id
                    FROM nd_pembayaran_penjualan
                    WHERE pembayaran_type_id != 5
                    AND amount != 0
                    GROUP BY penjualan_id
                ) nd_pembayaran_penjualan
                LEFT JOIN (
                    SELECT *
                    FROM vw_penjualan_data
                    WHERE customer_id = $customer_id 
                    AND tanggal >= '$tanggal_start'
                    AND tanggal <= '$tanggal_end'
                    AND toko_id = $toko_id
                    AND penjualan_type_id != 3
                    AND status_aktif = 1
                    ) nd_penjualan
                ON nd_penjualan.id = nd_pembayaran_penjualan.penjualan_id
                WHERE nd_penjualan.id is not null
            )UNION(
                SELECT tanggal_transfer,nd_pembayaran_piutang.id,no_faktur,0, amount, 'bayar piutang', nd_pembayaran_piutang_nilai.id
                FROM (
                    SELECT *
                    FROM nd_pembayaran_piutang
                    WHERE customer_id = $customer_id
                    AND toko_id = $toko_id
                    AND status_aktif = 1
                    ) nd_pembayaran_piutang
                LEFT JOIN (
                    SELECT *
                    FROM nd_pembayaran_piutang_nilai
                    WHERE DATE_FORMAT(created, '%Y-%m-%d') >= '$tanggal_start'
                    AND  DATE_FORMAT(created, '%Y-%m-%d') <= '$tanggal_end'
                    ) nd_pembayaran_piutang_nilai
                ON nd_pembayaran_piutang.id = nd_pembayaran_piutang_nilai.pembayaran_piutang_id
                LEFT JOIN (
                    SELECT pembayaran_piutang_id, group_concat(no_faktur SEPARATOR', ') as no_faktur
                    FROM (
                        (
                            SELECT pembayaran_piutang_id, group_concat(no_faktur_lengkap ORDER BY no_faktur SEPARATOR', ' ) as no_faktur
                            FROM (
                                SELECT tA.*
                                FROM nd_pembayaran_piutang_detail tA
                                LEFT JOIN nd_pembayaran_piutang tB
                                ON tA.pembayaran_piutang_id = tB.id
                                WHERE data_status = 1
                                AND status_aktif = 1
                            ) nd_pembayaran_piutang_detail
                            LEFT JOIN (
                                SELECT *
                                FROM vw_penjualan_data
                                ) nd_penjualan
                            ON nd_pembayaran_piutang_detail.penjualan_id = nd_penjualan.id 
                            GROUP BY pembayaran_piutang_id
                        )UNION(
                            SELECT pembayaran_piutang_id, group_concat(no_faktur_lengkap SEPARATOR ' ,')
                            FROM (
                                SELECT tA.*
                                FROM nd_pembayaran_piutang_detail tA
                                LEFT JOIN nd_pembayaran_piutang tB
                                ON tA.pembayaran_piutang_id = tB.id
                                WHERE data_status = 2
                                AND status_aktif = 1
                            ) nd_pembayaran_piutang_detail
                            LEFT JOIN (
                                SELECT *, concat(no_faktur,' (piutang awal)') as no_faktur_lengkap
                                FROM nd_piutang_awal
                                ) nd_penjualan
                            ON nd_pembayaran_piutang_detail.penjualan_id = nd_penjualan.id 
                            GROUP BY pembayaran_piutang_id
                        )UNION(
                            SELECT pembayaran_piutang_id, group_concat(no_faktur_lengkap ORDER BY no_faktur SEPARATOR', ' ) as no_faktur
                            FROM (
                                SELECT tA.*
                                FROM nd_pembayaran_piutang_detail tA
                                LEFT JOIN nd_pembayaran_piutang tB
                                ON tA.pembayaran_piutang_id = tB.id
                                WHERE data_status = 3
                                AND status_aktif = 1
                            ) nd_pembayaran_piutang_detail
                            LEFT JOIN (
                                SELECT *, concat('FRJ', DATE_FORMAT(tanggal,'%d%m%y'),'-',LPAD(no_faktur,3,'0'),'(retur)') as no_faktur_lengkap
                                FROM nd_retur_jual
                                ) nd_retur_jual
                            ON nd_pembayaran_piutang_detail.penjualan_id = nd_retur_jual.id 
                            GROUP BY pembayaran_piutang_id
                        )UNION(
                            SELECT pembayaran_piutang_id, concat('Tolakan Giro ', group_concat(no_giro ORDER BY no_giro SEPARATOR', ' ) ) as no_faktur
                            FROM (
                                SELECT tA.*
                                FROM nd_pembayaran_piutang_detail tA
                                LEFT JOIN nd_pembayaran_piutang tB
                                ON tA.pembayaran_piutang_id = tB.id
                                WHERE data_status = 4
                                AND status_aktif = 1
                            ) tA
                            LEFT JOIN (
                                SELECT t1.*, no_giro
                                FROM nd_giro_tolakan t1
                                LEFT JOIN nd_pembayaran_piutang_nilai t2
                                ON t1.pembayaran_piutang_nilai_id = t2.id
                            ) tB
                            ON tA.penjualan_id = tB.id 
                            GROUP BY pembayaran_piutang_id
                        )
                    ) a
                    GROUP BY pembayaran_piutang_id
                ) nd_pembayaran_piutang_detail
                ON nd_pembayaran_piutang_detail.pembayaran_piutang_id = nd_pembayaran_piutang.id
                WHERE nd_pembayaran_piutang_nilai.id is not null
            )UNION(
                SELECT tanggal,id, concat(no_faktur,' (piutang awal)'), amount, 0 as amount_bayar,'piutang awal',0
                FROM nd_piutang_awal
                WHERE customer_id = $customer_id
                AND toko_id = $toko_id
                AND tanggal >= '$tanggal_start'
                AND tanggal <= '$tanggal_end'               
            )UNION(
                SELECT DATE(created), b.id, 'Pembulatan', 0, pembulatan, '' , 0
                FROM nd_pembayaran_piutang b
                WHERE customer_id = $customer_id
                AND toko_id = $toko_id
                AND pembulatan != 0
                AND status_aktif = 1
                AND DATE(created) >= '$tanggal_start'
                AND DATE(created) <= '$tanggal_end'
            )UNION(
                SELECT tanggal_transfer, b.id, 'Lain2', 0, lain2, '' , 0
                FROM (
                    SELECT MAX(tanggal_transfer) as tanggal_transfer, pembayaran_piutang_id
                    FROM nd_pembayaran_piutang_nilai
                    WHERE  DATE_FORMAT(created, '%Y-%m-%d') >= '$tanggal_start'
                    AND  DATE_FORMAT(created, '%Y-%m-%d') <= '$tanggal_end'
                    GROUP BY pembayaran_piutang_id
                    ) a
                LEFT JOIN nd_pembayaran_piutang b
                ON a.pembayaran_piutang_id = b.id
                WHERE customer_id = $customer_id
                AND toko_id = $toko_id
                AND lain2 != 0
                AND status_aktif = 1
            )UNION(
                SELECT t1.tanggal,t1.id, no_faktur_lengkap as no_faktur, amount_jual * -1, 0 as amount_bayar,'jual' as ket, (amount_jual - ifnull(amount_bayar,0) - ifnull(amount_lunas,0)) as ket_lunas
                FROM (
                    SELECT *, concat('FRJ', DATE_FORMAT(tanggal,'%d%m%y'),'-',LPAD(no_faktur,4,'0')) as no_faktur_lengkap
                    FROM nd_retur_jual
                    WHERE customer_id = $customer_id 
                    AND tanggal >= '$tanggal_start'
                    AND tanggal <= '$tanggal_end'
                    AND toko_id = $toko_id
                    AND retur_type_id != 3
                    AND status_aktif = 1
                    ) t1
                LEFT JOIN (
                    SELECT sum(qty * harga) as amount_jual, retur_jual_id
                    FROM nd_retur_jual_detail
                    LEFT JOIN (
                        SELECT sum(qty* if(jumlah_roll = 0,1, jumlah_roll) ) as qty, retur_jual_detail_id
                        FROM nd_retur_jual_qty
                        GROUP BY retur_jual_detail_id
                        ) nd_retur_jual_qty_detail
                    ON nd_retur_jual_qty_detail.retur_jual_detail_id = nd_retur_jual_detail.id
                    GROUP BY retur_jual_id
                ) t2
                ON t1.id = t2.retur_jual_id
                LEFT JOIN (
                    SELECT sum(amount) as amount_bayar, retur_jual_id
                    FROM nd_pembayaran_retur
                    WHERE pembayaran_type_id != 5 
                    GROUP BY retur_jual_id
                    ) t3
                ON t1.id = t3.retur_jual_id
                LEFT JOIN (
                    SELECT sum(amount) as amount_lunas, penjualan_id
                    FROM (
                        SELECT *
                        FROM nd_pembayaran_piutang_detail
                        WHERE data_status = 3
                        ) a
                    LEFT JOIN nd_pembayaran_piutang b
                    ON a.pembayaran_piutang_id = b.id
                    WHERE b.status_aktif = 1
                    GROUP BY penjualan_id
                    ) t4 
                ON t1.id = t4.penjualan_id
            )UNION(
                SELECT t1.tanggal,t1.id, no_giro, t2.amount, 0 as amount_bayar,'tolakan_giro' as ket, ifnull((t4.amount_detail - t4.amount_bayar),-100) as ket_lunas
                FROM (
                    SELECT *
                    FROM nd_giro_tolakan
                    WHERE customer_id = $customer_id 
                    AND tanggal >= '$tanggal_start'
                    AND tanggal <= '$tanggal_end'
                ) t1
                LEFT JOIN nd_pembayaran_piutang_nilai t2
                ON t1.pembayaran_piutang_nilai_id = t2.id
                LEFT JOIN (
                    SELECT penjualan_id, amount, pembayaran_piutang_id
                    FROM (
                        SELECT *
                        FROM nd_pembayaran_piutang_detail
                        WHERE data_status = 4
                        ) t_1
                    LEFT JOIN nd_pembayaran_piutang t_2
                    ON t_1.pembayaran_piutang_id = t_2.id
                    WHERE status_aktif = 1
                )t3
                ON t1.id=t3.penjualan_id
                LEFT JOIN (
                    SELECT amount_bayar + pembulatan as amount_bayar, amount_detail, t_1.id
                    FROM (
                        SELECT *
                        FROM nd_pembayaran_piutang
                        WHERE customer_id = $customer_id
                        )t_1
                    LEFT JOIN (
                        SELECT sum(amount) as amount_detail, pembayaran_piutang_id
                        FROM nd_pembayaran_piutang_detail
                        GROUP BY pembayaran_piutang_id
                        )t_2
                    ON t_2.pembayaran_piutang_id = t_1.id
                    LEFT JOIN(
                        SELECT sum(amount) as amount_bayar, pembayaran_piutang_id
                        FROM nd_pembayaran_piutang_nilai tA
                        LEFT JOIN nd_giro_tolakan tB
                        ON tA.id=tB.pembayaran_piutang_nilai_id
                        WHERE tB.id is null
                        GROUP BY pembayaran_piutang_id
                        )t_3
                    ON t_1.id = t_3.pembayaran_piutang_id
                ) t4
                ON t3.pembayaran_piutang_id = t4.id

            )
        )a
        ORDER by tanggal, no_faktur asc
        ");

        return $query->result();
        // return $this->db->last_query();

    }

    function get_mutasi_piutang_saldo_awal_by_created($customer_id, $toko_id, $tanggal){
        $query = $this->db->query("SELECT sum(ifnull(amount_beli,0)) - sum(ifnull(amount_bayar,0)) as saldo_awal, 
        customer_id
            FROM  (
                (
                    SELECT nd_penjualan.tanggal, no_faktur_lengkap as no_faktur, amount_beli, pembayaran as amount_bayar, 'a' as tipe, customer_id
                    FROM (
                        SELECT *
                        FROM vw_penjualan_data
                        WHERE customer_id IN ($customer_id)
                        AND tanggal < '$tanggal'
                        AND toko_id = $toko_id
                        AND status_aktif = 1
                        AND penjualan_type_id !=3
                    ) nd_penjualan
                    LEFT JOIN (
                        SELECT sum(qty * harga_jual) as amount_beli, penjualan_id
                        FROM nd_penjualan_detail
                        LEFT JOIN (
                            SELECT sum(qty*if(jumlah_roll = 0,1, jumlah_roll)) as qty, penjualan_detail_id
                            FROM nd_penjualan_qty_detail
                            GROUP BY penjualan_detail_id
                        ) nd_penjualan_qty_detail
                        ON nd_penjualan_qty_detail.penjualan_detail_id = nd_penjualan_detail.id
                        GROUP BY penjualan_id
                    ) nd_penjualan_detail
                    ON nd_penjualan.id = nd_penjualan_detail.penjualan_id
                    LEFT JOIN (
                        SELECT sum(amount) as pembayaran, penjualan_id
                        FROM nd_pembayaran_penjualan
                        WHERE pembayaran_type_id != 5
                        GROUP BY penjualan_id
                        ) nd_pembayaran_penjualan
                    ON nd_penjualan.id = nd_pembayaran_penjualan.penjualan_id
                )UNION(
                    SELECT tanggal_transfer,nd_pembayaran_piutang.id,0, amount_bayar, 'b', customer_id
                    FROM (
                        SELECT *
                        FROM nd_pembayaran_piutang
                        WHERE customer_id = $customer_id
                        AND toko_id = $toko_id
                        AND status_aktif = 1
                        ) nd_pembayaran_piutang
                    LEFT JOIN (
                        SELECT sum(amount) as amount_bayar, pembayaran_piutang_id, tanggal_transfer
                        FROM nd_pembayaran_piutang_nilai
                        WHERE  DATE_FORMAT(created, '%Y-%m-%d') < '$tanggal'
                        GROUP BY pembayaran_piutang_id
                        ) nd_pembayaran_piutang_nilai
                    ON nd_pembayaran_piutang.id = nd_pembayaran_piutang_nilai.pembayaran_piutang_id
                )UNION(
                    SELECT tanggal, no_faktur, amount, 0 as amount_bayar, 'c', customer_id
                    FROM nd_piutang_awal
                    WHERE customer_id = $customer_id 
                    AND tanggal < '$tanggal'
                    AND toko_id = $toko_id
                )UNION(
                    SELECT tanggal_transfer, b.id, 0, pembulatan,'d', customer_id
                    FROM (
                        SELECT MAX(tanggal_transfer) as tanggal_transfer, pembayaran_piutang_id
                        FROM nd_pembayaran_piutang_nilai
                        WHERE  DATE_FORMAT(created, '%Y-%m-%d') < '$tanggal'
                        GROUP BY pembayaran_piutang_id
                        ) a
                    LEFT JOIN nd_pembayaran_piutang b
                    ON a.pembayaran_piutang_id = b.id
                    WHERE customer_id = $customer_id
                    AND toko_id = $toko_id
                    AND status_aktif = 1
                )UNION(
                    SELECT nd_retur_jual.tanggal, no_faktur_lengkap as no_faktur, amount_beli*-1, pembayaran*-1 as amount_bayar, 
                    'e', customer_id
                    FROM (
                        SELECT *, concat('FRJ', DATE_FORMAT(tanggal,'%d%m%y'),'-',$this->pre_faktur,LPAD(no_faktur,4,'0')) as no_faktur_lengkap
                        FROM nd_retur_jual
                        WHERE customer_id = $customer_id 
                        AND tanggal < '$tanggal'
                        AND toko_id = $toko_id
                        AND status_aktif = 1
                        AND retur_type_id !=3
                    ) nd_retur_jual
                    LEFT JOIN (
                        SELECT sum(qty * harga) as amount_beli, retur_jual_id
                        FROM nd_retur_jual_detail
                        LEFT JOIN (
                            SELECT sum(qty*jumlah_roll) as qty, retur_jual_detail_id
                            FROM nd_retur_jual_qty
                            GROUP BY retur_jual_detail_id
                        ) nd_retur_jual_qty_detail
                        ON nd_retur_jual_qty_detail.retur_jual_detail_id = nd_retur_jual_detail.id
                        GROUP BY retur_jual_id
                    ) nd_retur_jual_detail
                    ON nd_retur_jual.id = nd_retur_jual_detail.retur_jual_id
                    LEFT JOIN (
                        SELECT sum(amount) as pembayaran, retur_jual_id
                        FROM nd_pembayaran_retur
                        WHERE pembayaran_type_id != 5
                        GROUP BY retur_jual_id
                        ) nd_pembayaran_retur
                    ON nd_retur_jual.id = nd_pembayaran_retur.retur_jual_id 
                )UNION(
                    SELECT tanggal, no_giro as no_faktur, amount, 0, 'f' as tipe, customer_id
                    FROM (
                        SELECT *
                        FROM nd_giro_tolakan
                        WHERE tanggal < '$tanggal'
                    ) t1
                    LEFT JOIN nd_pembayaran_piutang_nilai t2
                    ON t1.pembayaran_piutang_nilai_id = t2.id 
                )
            )a
            ");

        return $query->result();
    }

    function get_pembulatan_piutang_by_created($customer_id, $toko_id, $tanggal_start, $tanggal_end){
        $tanggal_before = $tanggal_start;
        // $tanggal_end = date('Y-m-t', strtotime($tanggal));
        // $query = $this->db->query("SELECT sum(pembulatan) as pembulatan, customer_id
        //     FROM (
        //         SELECT *  
        //         FROM nd_pembayaran_piutang_nilai
        //         WHERE  DATE_FORMAT(created, '%Y-%m-%d') >= '$tanggal_before'
        //         AND  DATE_FORMAT(created, '%Y-%m-%d') <= '$tanggal_end'
        //         GROUP BY pembayaran_piutang_id
        //         ) a
        //     LEFT JOIN nd_pembayaran_piutang b
        //     ON a.pembayaran_piutang_id = b.id
        //     WHERE customer_id IN ($customer_id)
        //     AND toko_id = $toko_id
        //     GROUP BY customer_id
        //     ");

        $query = $this->db->query("SELECT sum(pembulatan) as pembulatan, customer_id
            FROM nd_pembayaran_piutang
            WHERE  DATE_FORMAT(created, '%Y-%m-%d') >= '$tanggal_before'
            AND  DATE_FORMAT(created, '%Y-%m-%d') <= '$tanggal_end'
            AND customer_id IN ($customer_id)
            GROUP BY customer_id
            ");

        return $query->result();
        // return $this->db->last_query();
    }

//===============================giro register list=============================

    function get_giro_register(){
        $query = $this->db->query("SELECT t1.*, lbr, ifnull(lbr_batal,0) as lbr_batal, nama_bank, no_rek_bank, tipe_trx_1, tipe_trx_2, lbr_cair
            FROM nd_giro_list t1
            LEFT JOIN (
                SELECT sum(lbr) as lbr, giro_list_id, group_concat(lbr) as lbr_data, group_concat(tipe) as tipe, sum(if(tipe=3,lbr,0)) as lbr_batal, sum(lbr_cair) as lbr_cair
                FROM (
                        (
                            SELECT sum(1) as lbr, 1 as tipe, giro_list_id, sum(if(tanggal_cair is null,0,1)) as lbr_cair
                            FROM nd_giro_list_detail
                            WHERE status != 0
                            GROUP BY giro_list_id
                        )UNION(
                            SELECT sum(1) as lbr, 3 as tipe, giro_list_id, sum(if(tanggal_cair is null,0,1)) as lbr_cair
                            FROM nd_giro_list_detail
                            WHERE status = 0
                            GROUP BY giro_list_id
                        )UNION(
                            SELECT sum(1) as lbr, 4 as tipe, giro_register_id as giro_list_id, sum(if(tanggal_cair is null,0,1)) as lbr_cair
                            FROM nd_pembayaran_hutang_nilai
                            WHERE pembayaran_type_id = 5
                            GROUP BY giro_register_id
                        )UNION(
                            SELECT sum(1) as lbr, 2 as tipe, giro_register_id as giro_list_id, sum(if(tanggal_cair is null,0,1)) as lbr_cair
                            FROM nd_pembayaran_hutang_nilai
                            WHERE pembayaran_type_id = 2
                            GROUP BY giro_register_id
                        )
                    )result
                GROUP BY giro_list_id
                ) t2
            ON t2.giro_list_id = t1.id
            LEFT JOIN nd_bank_list t3
            ON t1.bank_list_id=t3.id
            ORDER BY t1.id desc
            ", false);

        return $query->result();
    }


    function get_giro_register_detail($id, $pembayaran_type_id){
        $query = $this->db->query("SELECT t1.*, username
            FROM (
                (
                    SELECT pembayaran_hutang_id, giro_register_id, amount, jatuh_tempo, tanggal_transfer as tanggal, tA.id, 1 as tipe, 
                    no_giro, tA.user_id, tA.created, concat(tC.nama, if(tA.keterangan != '',concat(' ,', keterangan),'' ) ) as keterangan, 
                    tC.nama as penerima,3 as status, tanggal_cair
                    FROM (
                        SELECT *
                        FROM nd_pembayaran_hutang_nilai 
                        WHERE giro_register_id = $id
                        AND pembayaran_type_id = $pembayaran_type_id
                        AND giro_register_id is not null
                    ) tA
                    LEFT JOIN nd_pembayaran_hutang tB
                    ON tA.pembayaran_hutang_id = tB.id
                    LEFT JOIN nd_supplier tC
                    ON tB.supplier_id = tC.id
                )UNION(
                    SELECT '',giro_list_id, amount, jatuh_tempo, tanggal, t1.id, 2 as tipe, no_giro, user_id, updated, keterangan, penerima,status, tanggal_cair
                    FROM nd_giro_list_detail t1
                    WHERE giro_list_id = $id
                )
            )t1
            LEFT JOIN nd_user t2
            ON t1.user_id = t2.id
            ", false);

        return $query->result();
    }

    function get_giro_register_other(){
        $query = $this->db->query("SELECT *
            FROM (
                (
                    SELECT pembayaran_hutang_id, giro_register_id, amount, jatuh_tempo, tanggal_transfer as tanggal, tA.id, 1 as tipe, no_giro, tA.user_id, tA.created, concat(tC.nama, if(tA.keterangan != '',concat(' ,', keterangan),'' ) ) as keterangan, tC.nama as penerima, tA.id as detail_id
                    FROM (
                        SELECT *
                        FROM nd_pembayaran_hutang_nilai
                        AND pembayaran_type_id = 2
                        AND giro_register_id is not null
                    ) tA
                    LEFT JOIN nd_pembayaran_hutang tB
                    ON tA.pembayaran_hutang_id = tB.id
                    LEFT JOIN nd_supplier tC
                    ON tB.supplier_id = tC.id
                    )UNION(
                    SELECT '',giro_list_id, amount, jatuh_tempo, tanggal, id, 2 as tipe, no_giro, user_id, updated, keterangan, penerima, ''
                    FROM nd_giro_list_detail
                )
            )t1
            ", false);

        return $query->result();
    }

//==============================================outstanding piutang===============================

    function get_kontra_belum_lunas($customer_id){
        $query = $this->db->query("SELECT total_kontra_bon , amount_bayar_kontra, t1.*, ifnull(total_kontra_bon,0) - ifnull(amount_bayar_kontra,0) - ifnull(pembulatan,0) as sisa_outstanding, no_faktur, data_kontra_bon, pembayaran_piutang_detail_id_info, piutang_detail_id
                FROM (
                    SELECT *
                    FROM nd_pembayaran_piutang
                    WHERE customer_id = $customer_id
                    ) t1
                LEFT JOIN (
                    SELECT pembayaran_piutang_id, sum(amount) as total_kontra_bon, 
                    group_concat(no_faktur_lengkap SEPARATOR '??') as no_faktur, 
                    group_concat(amount) as data_kontra_bon, group_concat(t1.id) as piutang_detail_id
                    FROM nd_pembayaran_piutang_detail t1
                    LEFT JOIN vw_penjualan_data t2
                    ON t1.penjualan_id = t2.id
                    GROUP BY pembayaran_piutang_id
                    ) t2
                ON t1.id = t2.pembayaran_piutang_id
                LEFT JOIN (
                    SELECT pembayaran_piutang_id, sum(amount) as amount_bayar_kontra, group_concat(pembayaran_piutang_detail_id_info) as pembayaran_piutang_detail_id_info
                    FROM nd_pembayaran_piutang_nilai tA
                    LEFT JOIN (
                        SELECT group_concat(pembayaran_piutang_detail_id) as pembayaran_piutang_detail_id_info, pembayaran_piutang_nilai_id
                        FROM nd_pembayaran_piutang_nilai_info
                        GROUP BY pembayaran_piutang_nilai_id
                        ) tB
                    ON tA.id = tB.pembayaran_piutang_nilai_id
                    GROUP BY pembayaran_piutang_id
                    ) t3
                ON t3.pembayaran_piutang_id = t1.id
                WHERE ifnull(total_kontra_bon,0) - ifnull(amount_bayar_kontra,0) - ifnull(pembulatan,0) > 0
            ", false);

        return $query->result();
    }

//====================================giro tolakan======================================

    function giro_tolakan_list(){
        $query = $this->db->query("SELECT t1.*, no_giro, tanggal_transfer, urutan_giro, jatuh_tempo, nama_bank, no_rek_bank, t2.keterangan as keterangan_pembayaran, t2.amount, t4.nama as nama_customer, t2.pembayaran_piutang_id, tanggal_setor, ifnull(sisa_piutang,0) as sisa_piutang, t6.pembayaran_piutang_id as pelunasan_id
            FROM nd_giro_tolakan t1
            LEFT JOIN (
                SELECT *
                FROM nd_pembayaran_piutang_nilai
                WHERE pembayaran_type_id = 2
            ) t2
            ON t1.pembayaran_piutang_nilai_id = t2.id
            LEFT JOIN (
                SELECT *
                FROM nd_pembayaran_piutang
                WHERE status_aktif = 1
                ) t3
            ON t2.pembayaran_piutang_id = t3.id
            LEFT JOIN nd_customer t4
            ON t3.customer_id = t4.id
            LEFT JOIN (
                SELECT pembayaran_piutang_nilai_id, tanggal as tanggal_setor
                FROM nd_giro_setor_detail tA
                LEFT JOIN nd_giro_setor tB
                ON tA.giro_setor_id = tB.id
                ) t5
            ON t1.id = t5.pembayaran_piutang_nilai_id
            LEFT JOIN (
                SELECT tA.*, tB.sisa_piutang
                FROM (
                    SELECT t_1.*
                    FROM nd_pembayaran_piutang_detail t_1
                    LEFT JOIN nd_pembayaran_piutang t_2
                    ON t_1.pembayaran_piutang_id = t_2.id
                    WHERE data_status = 4
                    AND status_aktif = 1
                    )tA
                LEFT JOIN (
                    SELECT total_amount - ifnull(total_bayar,0) as sisa_piutang, t_1.pembayaran_piutang_id
                    FROM (
                        SELECT sum(amount) as total_amount , pembayaran_piutang_id
                        FROM nd_pembayaran_piutang_detail
                        GROUP BY pembayaran_piutang_id
                        ) t_1
                        LEFT JOIN (
                            SELECT sum(amount) as total_bayar, pembayaran_piutang_id
                            FROM nd_pembayaran_piutang_nilai tX
                            LEFT JOIN nd_giro_tolakan tY
                            ON tY.pembayaran_piutang_nilai_id = tX.id
                            WHERE tY.id is null
                            GROUP BY pembayaran_piutang_id
                        )t_2
                        ON t_1.pembayaran_piutang_id = t_2.pembayaran_piutang_id
                    )tB
                ON tA.pembayaran_piutang_id = tB.pembayaran_piutang_id
                )t6
            ON t1.id = t6.penjualan_id
            WHERE t3.customer_id is not null
            ", false);

        return $query->result();
    }

    function get_giro_list_by_customer($cond){
        $bulan_lalu = date("Y-m-d", strtotime("-1 year"));
        $query = $this->db->query("SELECT t1.id, no_giro, nama_bank, no_rek_bank, jatuh_tempo, t2.customer_id, urutan_giro
            FROM (
                SELECT *
                FROM nd_pembayaran_piutang_nilai
                WHERE pembayaran_type_id = 2
                AND tanggal_transfer >= '$bulan_lalu'
            )t1
            LEFT JOIN (
                SELECT *
                FROM nd_pembayaran_piutang 
                WHERE status_aktif=1
                $cond
                )t2
            ON t1.pembayaran_piutang_id = t2.id
            LEFT JOIN nd_giro_tolakan t3
            ON t1.id = t3.pembayaran_piutang_nilai_id
            WHERE t2.id is not null
            AND t3.id is null
            ORDER BY urutan_giro asc
            ", false);

        return $query->result();
    }

    function get_giro_tolakan_piutang($pembayaran_piutang_id){
        $query = $this->db->query("SELECT t1.*, tanggal_transfer, t2.tanggal as tanggal_tolakan, no_giro, jatuh_tempo, nama_bank, no_rek_bank, t3.amount as amount_giro, t3.amount - ifnull(t4.amount,0) as sisa_piutang
                FROM (
                    SELECT *
                    FROM nd_pembayaran_piutang_detail
                    WHERE pembayaran_piutang_id = $pembayaran_piutang_id
                    AND data_status = 4
                    )t1
                LEFT JOIN nd_giro_tolakan t2
                ON t2.id = t1.penjualan_id
                LEFT JOIN nd_pembayaran_piutang_nilai t3
                ON t2.pembayaran_piutang_nilai_id = t3.id
                LEFT JOIN (
                    SELECT *
                    FROM nd_pembayaran_piutang_detail
                    WHERE pembayaran_piutang_id != $pembayaran_piutang_id
                    AND data_status = 4
                ) t4
                ON t2.id = t4.penjualan_id
            ", false);

        return $query->result();
    }

    
    function get_giro_tolakan_sisa($customer_id){
        $query = $this->db->query("SELECT t1.*, tanggal_transfer, t1.tanggal as tanggal_tolakan, no_giro, jatuh_tempo, nama_bank, no_rek_bank, t3.amount as amount_giro, t1.id as penjualan_id, t2.amount, t3.amount - ifnull(t2.amount, 0) as sisa_piutang
                FROM (
                    SELECT *
                    FROM nd_giro_tolakan
                    WHERE customer_id = $customer_id
                    ) t1
                LEFT JOIN (
                    SELECT *
                    FROM nd_pembayaran_piutang_detail
                    WHERE data_status = 4
                    ) t2
                ON t1.id = t2.penjualan_id
                LEFT JOIN nd_pembayaran_piutang_nilai t3
                ON t1.pembayaran_piutang_nilai_id = t3.id
                WHERE t3.amount - ifnull(t2.amount,0) > 0
            ", false);

        return $query->result();
    }


//====================================giro tolakan======================================


}