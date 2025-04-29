SELECT tbl_b.nama as nama_barang, nama_jual, barang_id, tbl_b.status_aktif as status_barang, tbl_d.nama as nama_satuan  , SUM(gudang_2_qty) as gudang_2_qty, SUM(gudang_2_roll) as gudang_2_roll , SUM(gudang_1_qty) as gudang_1_qty, SUM(gudang_1_roll) as gudang_1_roll , SUM(gudang_3_qty) as gudang_3_qty, SUM(gudang_3_roll) as gudang_3_roll , SUM(gudang_5_qty) as gudang_5_qty, SUM(gudang_5_roll) as gudang_5_roll , SUM(gudang_4_qty) as gudang_4_qty, SUM(gudang_4_roll) as gudang_4_roll
	FROM(
		SELECT barang_id, warna_id , 
		ROUND( SUM( if(gudang_id=2, ifnull( if(tanggal_stok is not null, if(tanggal >= tanggal_stok,qty_masuk,0) , qty_masuk ), 0) , 0 ) ) - SUM( if(gudang_id=2, ifnull( if(tanggal_stok is not null, if(tanggal >= tanggal_stok, qty_keluar,0 ), qty_keluar ) ,0), 0 ) ), 2) as gudang_2_qty , 
		SUM( if(gudang_id=2, if(tanggal_stok is not null, if(tanggal >= tanggal_stok,jumlah_roll_masuk,0 ), jumlah_roll_masuk), 0 ) ) - SUM( if(gudang_id=2, if(tanggal_stok is not null, if(tanggal >= tanggal_stok, jumlah_roll_keluar, 0) ,jumlah_roll_keluar), 0 ) ) as gudang_2_roll, 

		ROUND( SUM( if(gudang_id=1, ifnull( if(tanggal_stok is not null, if(tanggal >= tanggal_stok,qty_masuk,0) , qty_masuk ), 0) , 0 ) ) - SUM( if(gudang_id=1, ifnull( if(tanggal_stok is not null, if(tanggal >= tanggal_stok, qty_keluar,0 ), qty_keluar ) ,0), 0 ) ), 2) as gudang_1_qty , 
		SUM( if(gudang_id=1, if(tanggal_stok is not null, if(tanggal >= tanggal_stok,jumlah_roll_masuk,0 ), jumlah_roll_masuk), 0 ) ) - SUM( if(gudang_id=1, if(tanggal_stok is not null, if(tanggal >= tanggal_stok, jumlah_roll_keluar, 0) ,jumlah_roll_keluar), 0 ) ) as gudang_1_roll, 

		ROUND( SUM( if(gudang_id=3, ifnull( if(tanggal_stok is not null, if(tanggal >= tanggal_stok,qty_masuk,0) , qty_masuk ), 0) , 0 ) ) - SUM( if(gudang_id=3, ifnull( if(tanggal_stok is not null, if(tanggal >= tanggal_stok, qty_keluar,0 ), qty_keluar ) ,0), 0 ) ), 2) as gudang_3_qty , 
		SUM( if(gudang_id=3, if(tanggal_stok is not null, if(tanggal >= tanggal_stok,jumlah_roll_masuk,0 ), jumlah_roll_masuk), 0 ) ) - SUM( if(gudang_id=3, if(tanggal_stok is not null, if(tanggal >= tanggal_stok, jumlah_roll_keluar, 0) ,jumlah_roll_keluar), 0 ) ) as gudang_3_roll, 

		ROUND( SUM( if(gudang_id=5, ifnull( if(tanggal_stok is not null, if(tanggal >= tanggal_stok,qty_masuk,0) , qty_masuk ), 0) , 0 ) ) - SUM( if(gudang_id=5, ifnull( if(tanggal_stok is not null, if(tanggal >= tanggal_stok, qty_keluar,0 ), qty_keluar ) ,0), 0 ) ), 2) as gudang_5_qty ,
		SUM( if(gudang_id=5, if(tanggal_stok is not null, if(tanggal >= tanggal_stok,jumlah_roll_masuk,0 ), jumlah_roll_masuk), 0 ) ) - SUM( if(gudang_id=5, if(tanggal_stok is not null, if(tanggal >= tanggal_stok, jumlah_roll_keluar, 0) ,jumlah_roll_keluar), 0 ) ) as gudang_5_roll, 

		ROUND( SUM( if(gudang_id=4, ifnull( if(tanggal_stok is not null, if(tanggal >= tanggal_stok,qty_masuk,0) , qty_masuk ), 0) , 0 ) ) - SUM( if(gudang_id=4, ifnull( if(tanggal_stok is not null, if(tanggal >= tanggal_stok, qty_keluar,0 ), qty_keluar ) ,0), 0 ) ), 2) as gudang_4_qty ,
		SUM( if(gudang_id=4, if(tanggal_stok is not null, if(tanggal >= tanggal_stok,jumlah_roll_masuk,0 ), jumlah_roll_masuk), 0 ) ) - SUM( if(gudang_id=4, if(tanggal_stok is not null, if(tanggal >= tanggal_stok, jumlah_roll_keluar, 0) ,jumlah_roll_keluar), 0 ) ) as gudang_4_roll

		FROM (
			(
		        SELECT barang_id, warna_id, t2.gudang_id, qty as qty_masuk, jumlah_roll as jumlah_roll_masuk, 0 as qty_keluar, 0 as jumlah_roll_keluar, 1 as tipe, t1.id, tanggal
		        FROM nd_pembelian_detail t1
		        LEFT JOIN (
		        	SELECT *
		        	FROM nd_pembelian
		        	WHERE tanggal <= '2021-01-31'
		        	AND tanggal >= '2020-01-01'
		        	AND status_aktif = 1
		        	) t2
		        ON t1.pembelian_id = t2.id
		        WHERE t2.id is not null
		    )UNION(
		    	SELECT barang_id, warna_id, gudang_id_after, qty as qty_masuk, jumlah_roll as jumlah_roll_masuk, CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar, 2, t1.id, tanggal
	        	FROM nd_mutasi_barang t1
				WHERE tanggal <= '2021-01-31'
	        	AND tanggal >= '2020-01-01'
	        	AND status_aktif = 1
	        )UNION(
		        SELECT barang_id, warna_id, t1.gudang_id, 0 as qty_masuk, 0 as jumlah_roll_masuk, (subqty) as qty_keluar, (subjumlah_roll) as jumlah_roll_keluar, 3, t1.id, tanggal
		        FROM nd_penjualan_detail t1
		        LEFT JOIN (
		        	SELECT *
		        	FROM nd_penjualan
		        	WHERE tanggal <= '2021-01-31'
		        	AND tanggal >= '2020-01-01'
		        	AND status_aktif = 1
		        	) t2
		        ON t1.penjualan_id = t2.id
		        where t2.id is not null
		    )UNION(
		        SELECT barang_id, warna_id, t1.gudang_id, CAST(0 as DECIMAL(15,2)) as qty_masuk, 0 as jumlah_roll_masuk, (qty) as qty_keluar, (jumlah_roll) as jumlah_roll_keluar, 4, t1.id, tanggal
		        FROM nd_pengeluaran_stok_lain_detail t1
		        LEFT JOIN (
		        	SELECT *
		        	FROM nd_pengeluaran_stok_lain
		        	WHERE tanggal <= '2021-01-31'
		        	AND tanggal >= '2020-01-01'
		        	AND status_aktif = 1
		        	) t2
		        ON t1.pengeluaran_stok_lain_id = t2.id
		        LEFT JOIN (
		            SELECT sum(qty*if(jumlah_roll=0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, pengeluaran_stok_lain_detail_id
		            FROM nd_pengeluaran_stok_lain_qty_detail
		            GROUP BY pengeluaran_stok_lain_detail_id
		            ) t3
		        ON t3.pengeluaran_stok_lain_detail_id = t1.id
		        where t2.id is not null
		    )UNION(
		    	SELECT barang_id, warna_id, t1.gudang_id, (qty) as qty_masuk, (jumlah_roll) as jumlah_roll_masuk, CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar, 5, t1.id, tanggal
		        FROM nd_retur_jual_detail t1
		        LEFT JOIN (
		        	SELECT *
		        	FROM nd_retur_jual
		        	WHERE tanggal <= '2021-01-31'
			        AND tanggal >= '2020-01-01'
		        	AND status_aktif = 1
		        	) t2
		        ON t1.retur_jual_id = t2.id
		        LEFT JOIN (
		            SELECT sum(qty*if(jumlah_roll=0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, retur_jual_detail_id
		            FROM nd_retur_jual_qty
		            GROUP BY retur_jual_detail_id
		            ) t3
		        ON t3.retur_jual_detail_id = t1.id
		        WHERE t2.id is not null
		    )UNION(
		    	SELECT barang_id, warna_id, t1.gudang_id,0,0, (qty), (jumlah_roll), 19, t1.id, tanggal
		        FROM nd_retur_beli_detail t1
		        LEFT JOIN (
		        	SELECT *
		        	FROM nd_retur_beli
		        	WHERE tanggal <= '2021-01-31'
			        AND tanggal >= '2020-01-01'
		        	AND status_aktif = 1
		        	) t2
		        ON t1.retur_beli_id = t2.id
		        LEFT JOIN (
		            SELECT sum(qty*if(jumlah_roll=0,1,jumlah_roll)) as qty, sum(jumlah_roll) as jumlah_roll, retur_beli_detail_id
		            FROM nd_retur_beli_qty
		            GROUP BY retur_beli_detail_id
		            ) t3
		        ON t3.retur_beli_detail_id = t1.id
		        WHERE t2.id is not null
		    )UNION(
		        SELECT barang_id, warna_id, gudang_id, (qty*if(jumlah_roll=0,1,jumlah_roll)) as qty_masuk, (jumlah_roll) as jumlah_roll_masuk, CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar, 6, id, tanggal
		        	FROM nd_penyesuaian_stok
		        	WHERE tipe_transaksi = 0
		        	AND tanggal <= '2021-01-31'
			        AND tanggal >= '2020-01-01'
		    )UNION(
		        SELECT  barang_id, warna_id, gudang_id, (qty*if(jumlah_roll=0,1,jumlah_roll)) as qty_masuk, (jumlah_roll) as jumlah_roll_masuk, CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar, 7, id, tanggal
	        	FROM nd_penyesuaian_stok
	        	WHERE tanggal <= '2021-01-31'
		        AND tanggal >= '2020-01-01'
	        	AND tipe_transaksi = 1
		    )UNION(
		        SELECT barang_id, warna_id, gudang_id, CAST(0 as DECIMAL(15,2)) as qty_masuk, 0 as jumlah_roll_masuk, (qty*if(jumlah_roll=0,1,jumlah_roll)) as qty_keluar, (jumlah_roll) as jumlah_roll_keluar, 8 , id, tanggal
	        	FROM nd_penyesuaian_stok
	        	WHERE tanggal <= '2021-01-31'
		        AND tanggal >= '2020-01-01'
	        	AND tipe_transaksi = 2
		    )UNION(
		    	SELECT barang_id, warna_id, gudang_id_before, CAST(0 as DECIMAL(15,2)) as qty_masuk, 0 as jumlah_roll_masuk, qty as qty_keluar, jumlah_roll as jumlah_roll_keluar, 9, t1.id, tanggal
	        	FROM nd_mutasi_barang t1
				WHERE tanggal <= '2021-01-31'
		        AND tanggal >= '2020-01-01'
	        	AND status_aktif = 1
	        )UNION(
		    	SELECT barang_id, warna_id, gudang_id, qty as qty_masuk, (jumlah_roll) as jumlah_roll_masuk, CAST(0 as DECIMAL(15,2)) as qty_keluar, 0 as jumlah_roll_keluar, 10, t1.id, t2.tanggal
	        	FROM (
	        		SELECT barang_id, warna_id, gudang_id, (qty*if(jumlah_roll=0,1,jumlah_roll)) as qty, (jumlah_roll) as jumlah_roll, stok_opname_id, id
	        		FROM nd_hasil_SO t1
	        		GROUP BY barang_id, warna_id, gudang_id,stok_opname_id
        		)t1
	        	LEFT JOIN nd_stok_opname t2
	        	ON t1.stok_opname_id = t2.id
		    )
		)t1
		LEFT JOIN (
			SELECT barang_id as barang_id_stok, warna_id as warna_id_stok,gudang_id as gudang_id_stok, MAX(tanggal) as tanggal_stok
		    FROM (
		    	SELECT stok_opname_id, barang_id,warna_id, gudang_id
		    	FROM nd_hasil_SO
		    	GROUP BY barang_id, warna_id, gudang_id, stok_opname_id
			) tA
			LEFT JOIN (
				SELECT *
				FROM nd_stok_opname
				WHERE status_aktif = 1
			) tB
			ON tA.stok_opname_id = tB.id
			WHERE tB.id is not null
			GROUP BY barang_id, warna_id, gudang_id
		) t2
		ON t1.barang_id = t2.barang_id_stok
		AND t1.warna_id = t2.warna_id_stok
		AND t1.gudang_id = t2.gudang_id_stok
		GROUP BY barang_id, warna_id
) tbl_a
LEFT JOIN nd_barang tbl_b
ON tbl_a.barang_id = tbl_b.id
LEFT JOIN nd_warna tbl_c
ON tbl_a.warna_id = tbl_c.id
LEFT JOIN nd_satuan tbl_d
ON tbl_b.satuan_id = tbl_d.id
Where barang_id =27
GROUP BY barang_id
ORDER BY nama_jual