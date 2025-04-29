//=========================po pembelian========================

CREATE VIEW po_pembelian AS
    SELECT 
        t1.*, t2.nama as nama_supplier,
        concat(if(tanggal >= '2019-09-27', concat(ifnull(pre_po,''),t2.kode,'/',DATE_FORMAT(tanggal,'%y'),'/',LPAD(po_number,3,'0')) ,concat(LPAD(po_number,3,'0'),'/',DATE_FORMAT(tanggal,'%m'),'/',DATE_FORMAT(tanggal,'%y')))) as po_number_lengkap
    FROM nd_po_pembelian t1
        LEFT JOIN nd_supplier t2
        ON t1.supplier_id = t2.id
        LEFT JOIN nd_toko t3
        ON t1.toko_id = t3.id
        ;