SELECT *
FROM (
    (
        SELECT barang_id, request_barang_id,
            sum(qty) as qty_request, sum(ifnull(qty_datang,0)) as qty_datang, group_concat(warna_id) as warna_id, group_concat(qty) as qty_data_request,
            '' as warna_id_no_request, 0 as qty_datang_no_request,  '' as qty_data_datang_no_request
        FROM (
            SELECT t1.barang_id, t1.warna_id, t1.qty, sum(ifnull(t2.qty,0)) as qty_datang, request_barang_id, closed_date, t1.tanggal, max(created_at) as last_datang
            FROM (
                SELECT barang_id, tB.tanggal, warna_id, sum(qty) as qty, request_barang_batch_id, closed_date, request_barang_id
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
            AND ifnull(t1.closed_date,'2021-07-06 23:59:59') >= t2.created_at
            AND t1.tanggal <= t2.created_at
            GROUP BY t1.barang_id, t1.warna_id, request_barang_id 
        )result
        GROUP BY barang_id, request_barang_id
    )UNION(
        SELECT barang_id,request_barang_id,
            0, 0 ,'', '',
            group_concat(warna_id) as warna_id, sum(qty_datang), group_concat(qty_datang) as qty_data_datang, first_datang, last_datang
        FROM (
            SELECT t2.barang_id, t2.warna_id, sum(ifnull(t2.qty,0)) as qty_datang, min(created_at) as first_datang, max(created_at) as last_datang, t3.closed_date, t3.request_barang_id
                FROM (
                    SELECT barang_id, warna_id, qty, jumlah_roll, created_at
                    FROM nd_pembelian_detail tA
                    LEFT JOIN nd_pembelian tB
                    ON tA.pembelian_id = tB.id
                )t2
                LEFT JOIN (
                    SELECT barang_id, tB.tanggal, warna_id, sum(qty) as qty, request_barang_batch_id, closed_date, request_barang_id
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
                    GROUP BY barang_id, warna_id, request_barang_id
                )t1
                ON t1.barang_id = t2.barang_id
                AND t1.warna_id = t2.warna_id
                AND ifnull(t1.closed_date,'2021-07-06 23:59:59') >= t2.created_at
                AND t1.tanggal <= t2.created_at
                LEFT JOIN (
                    SELECT tB.tanggal, ifnull(closed_date,'2021-07-06 23:59:59') as closed_date, request_barang_id
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
                    GROUP BY  request_barang_id
                )t3
                ON t3.closed_date >= t2.created_at
                AND t3.tanggal <= t2.created_at
                WHERE t1.barang_id is null
                AND t1.warna_id is null
                AND t3.closed_date is not null
                GROUP BY t2.barang_id, t2.warna_id, t3.request_barang_id
        )result
        GROUP BY barang_id, request_barang_id
    )
)