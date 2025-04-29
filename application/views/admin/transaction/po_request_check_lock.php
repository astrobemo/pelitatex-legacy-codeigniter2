SELECT tM.*, tN.po_number as po_number_baru, qty_outstanding, po_qty
FROM (
    SELECT tX.*, tY.locked_by, warna_jual, tY.batch, tY.locked_date, po_number
    FROM(
        SELECT t1.*, t2.tanggal
        FROM (
            SELECT tA.*
            FROM nd_request_barang_detail tA
            LEFT JOIN (
                SELECT *
                FROM nd_request_barang_qty
                WHERE id IN (
                    SELECT max(tA.id)
                    FROM nd_request_barang_qty tA
                    LEFT JOIN nd_request_barang_batch tB
                    ON tA.request_barang_batch_id = tB.id
                    WHERE request_barang_id = 2
                    AND tB.id is not null
                    GROUP BY barang_id, warna_id, request_barang_id
                )
            ) tB
            ON tA.request_barang_batch_id = tB.request_barang_batch_id
            AND tA.barang_id = tB.barang_id
            AND tA.warna_id = tB.warna_id
            WHERE tB.request_barang_batch_id is not null
            AND tA.po_pembelian_batch_id != 0
        )t1
        LEFT JOIN nd_request_barang_batch t2
        ON t1.request_barang_batch_id = t2.id
    )tX
    LEFT JOIN (
        SELECT if(t1.tipe_barang!=1,barang_id_baru,barang_id) as barang_id, warna_id, po_pembelian_batch_id, locked_by, t1.qty, t2.po_pembelian_id, batch, revisi, t2.tanggal as batch_tanggal, locked_date, concat(if(tB.tanggal >= '2019-09-27', concat(ifnull(pre_po,''),tD.kode,'/',DATE_FORMAT(tB.tanggal,'%y'),'/',LPAD(po_number,3,'0')) ,concat(LPAD(po_number,3,'0'),'/',DATE_FORMAT(tB.tanggal,'%m'),'/',DATE_FORMAT(tB.tanggal,'%y'))),'-',batch,if(revisi > 1,concat('R',revisi-1),'') ) as po_number
        FROM nd_po_pembelian_warna t1
        LEFT JOIN nd_po_pembelian_batch t2
        ON t1.po_pembelian_batch_id = t2.id
        LEFT JOIN nd_po_pembelian_detail t3
        ON t1.po_pembelian_detail_id = t3.id
        LEFT JOIN nd_po_pembelian tB
        ON t2.po_pembelian_id = tB.id
        LEFT JOIN nd_supplier tD
        ON tB.supplier_id = tD.id
        LEFT JOIN nd_toko
        ON tB.toko_id = nd_toko.id
        WHERE t2.status != 0
    ) tY
    ON tX.po_pembelian_batch_id = tY.po_pembelian_batch_id
    AND tX.barang_id = tY.barang_id
    AND tX.warna_id = tY.warna_id
    LEFT JOIN nd_warna
    ON tX.warna_id = nd_warna.id
    WHERE locked_by is not null
    AND tX.tanggal >= tY.locked_date
)tM
LEFT JOIN (
    SELECT t1.barang_id, t1.warna_id, (if(po_qty - ifnull(qty,0) > 0 , po_qty - ifnull(qty,0) ,0))as qty_outstanding, po_qty, po_number, batch_tanggal, t1.po_pembelian_batch_id, locked_date
    FROM (
        SELECT barang_id, warna_id, sum(qty) as po_qty, po_pembelian_batch_id, concat(if(tB.tanggal >= '2019-09-27', concat(ifnull(pre_po,''),tD.kode,'/',DATE_FORMAT(tB.tanggal,'%y'),'/',LPAD(po_number,3,'0')) ,concat(LPAD(po_number,3,'0'),'/',DATE_FORMAT(tB.tanggal,'%m'),'/',DATE_FORMAT(tB.tanggal,'%y'))),'-',batch,if(revisi > 1,concat('R',revisi-1),'') ) as po_number, batch_tanggal, batch, locked_date
        FROM (
            SELECT if(t1.tipe_barang!=1,barang_id_baru,barang_id) as barang_id, warna_id, po_pembelian_batch_id, locked_by, t1.qty, t2.po_pembelian_id, batch, revisi, t2.tanggal as batch_tanggal, locked_date
            FROM nd_po_pembelian_warna t1
            LEFT JOIN nd_po_pembelian_batch t2
            ON t1.po_pembelian_batch_id = t2.id
            LEFT JOIN nd_po_pembelian_detail t3
            ON t1.po_pembelian_detail_id = t3.id
            WHERE t2.status != 0
            AND (
                locked_date is null
                OR locked_date >= '2021-06-30'
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
        WHERE tB.status_aktif = 1
        GROUP BY barang_id, warna_id, po_pembelian_batch_id
        )t1
    LEFT JOIN (
        SELECT t1.barang_id, t1.warna_id, sum(qty) as qty, po_pembelian_batch_id
        FROM (
            SELECT barang_id, warna_id, qty, tA.id, created_at, po_pembelian_batch_id
            FROM nd_pembelian_detail tA
            LEFT JOIN (
                SELECT *
                FROM nd_pembelian
                WHERE status_aktif = 1
                AND po_pembelian_batch_id is not null
                )tB
            ON tA.pembelian_id = tB.id
            WHERE tB.id is not null
        )t1
        LEFT JOIN (
            SELECT t11.id,barang_id, warna_id, concat(t12.tanggal,' 23:59:59') as tanggal_akhir
                FROM (
                    SELECT *
                    FROM nd_request_barang_qty
                    WHERE id IN (
                        SELECT min(tA.id)
                        FROM nd_request_barang_qty tA
                        LEFT JOIN nd_request_barang_batch tB
                        ON tA.request_barang_batch_id = tB.id
                        WHERE request_barang_id = 2
                        AND tB.id is not null
                        GROUP BY barang_id, warna_id, request_barang_id
                    )
                )t11
                LEFT JOIN nd_request_barang_batch t12
                ON t11.request_barang_batch_id = t12.id
        ) t2
        ON t1.barang_id = t2.barang_id
        AND t1.warna_id = t2.warna_id
        AND t1.created_at <= t2.tanggal_akhir
        WHERE t2.id is not null
        GROUP BY barang_id, warna_id, po_pembelian_batch_id
        ) t2
    ON t1.po_pembelian_batch_id = t2.po_pembelian_batch_id
    AND t1.barang_id = t2.barang_id
    AND t1.warna_id = t2.warna_id
)tN
ON tM.barang_id = tN.barang_id
AND tM.warna_id = tN.warna_id
WHERE qty_outstanding > 0
AND (
    tN.locked_date is null 
    OR tN.locked_date > tM.locked_date
)