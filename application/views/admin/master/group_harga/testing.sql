INSERT INTO nd_penjualan_remap_list(penjualan_remap_id, penjualan_id_asal, no_faktur_asal, tanggal_asal, penjualan_id_tujuan, no_faktur_tujuan, tanggal_tujuan, user_id) 
VALUES
('1', '16364',263,'2022-05-23','16552',5,'2022-06-02','1'), 
('1', '16375',275,'2022-05-23','16561',16,'2022-06-02','1'), 
('1', '16376',276,'2022-05-23','16569',23,'2022-06-02','1'), 
('1', '16383',282,'2022-05-24','16572',27,'2022-06-02','1'), 
('1', '16395',295,'2022-05-24','16589',43,'2022-06-03','1'), 

('1', '16468',371,'2022-05-28','16590',44,'2022-06-03','1'), 
('1', '16480',380,'2022-05-28','16591',45,'2022-06-03','1'), 
('1', '16492',390,'2022-05-30','16598',53,'2022-06-03','1'), 
('1', '16507',406,'2022-05-30','16605',59,'2022-06-03','1'), 
('1', '16510',409,'2022-05-30','16607',61,'2022-06-03','1'), 

('1', '16521',429,'2022-05-31','16608',62,'2022-06-03','1'), 
('1', '16531',431,'2022-05-31','16609',64,'2022-06-03','1'), 
('1', '16532',434,'2022-05-31','16610',65,'2022-06-03','1'), 
('1', '16534',435,'2022-05-31','16611',66,'2022-06-03','1'), 
('1', '16536',442,'2022-05-31','16612',67,'2022-06-03','1');

SELECT penjualan_id_asal, tanggal_asal, no_faktur_asal, t2.id, tanggal, no_faktur
FROM nd_penjualan_remap_list t1 
LEFT JOIN nd_penjualan t2
ON t1.penjualan_id_asal = t2.id

UPDATE nd_penjualan t1, (SELECT * FROM nd_penjualan_remap_list WHERE penjualan_remap_id=1) t2 SET t1.no_faktur=t2.no_faktur_tujuan,t1.tanggal=t2.tanggal_tujuan 
WHERE t1.id = t2.penjualan_id_asal

UPDATE nd_penjualan t1, (SELECT * FROM nd_penjualan_remap_list WHERE penjualan_remap_id=1) t2 SET t1.no_faktur=t2.no_faktur_asal,t1.tanggal=t2.tanggal_asal 
WHERE t1.id = t2.penjualan_id_tujuan

INSERT INTO nd_penjualan_remap_list(penjualan_remap_id, penjualan_id_asal, no_faktur_asal, tanggal_asal, penjualan_id_tujuan, no_faktur_tujuan, tanggal_tujuan, user_id) 
VALUES
('1', '29440',428,'2022-05-24','29657',2,'2022-06-02','1'), 
('1', '29441',430,'2022-05-24','29659',5,'2022-06-02','1'), 
('1', '29455',452,'2022-05-24','29679',24,'2022-06-02','1'), 
('1', '29470',458,'2022-05-25','29686',31,'2022-06-02','1'), 
('1', '29519',510,'2022-05-27','29691',37,'2022-06-02','1'), 

('1', '29554',542,'2022-05-28','29693',39,'2022-06-02','1'), 
('1', '29557',545,'2022-05-28','29698',44,'2022-06-02','1'), 
('1', '29572',560,'2022-05-30','29699',45,'2022-06-02','1'), 
('1', '29577',565,'2022-05-30','29703',49,'2022-06-02','1'), 
('1', '29600',588,'2022-05-30','29708',53,'2022-06-03','1'), 

('1', '29602',590,'2022-05-31','29716',62,'2022-06-03','1'), 
('1', '29627',615,'2022-05-31','29722',68,'2022-06-03','1'), 
('1', '29648',635,'2022-05-31','29723',69,'2022-06-03','1');

SELECT *
FROM nd_penjualan_detail
WHERE penjualan_id=23551
OR penjualan_id = 23552
OR penjualan_id = 23553
OR penjualan_id = 23554
OR penjualan_id = 23555
OR penjualan_id = 23556

SELECT *
FROM nd_penjualan_detail
WHERE penjualan_id = 23466
OR penjualan_id = 23520
OR penjualan_id = 23524
OR penjualan_id = 23542
OR penjualan_id = 23544
OR penjualan_id = 23548

SELECT *
FROM nd_penjualan_detail
WHERE penjualan_id = 23551
OR penjualan_id = 23553
OR penjualan_id = 23554
OR penjualan_id = 23556

SELECT *
FROM nd_penjualan_qty_detail
WHERE penjualan_detail_id = 64981
OR penjualan_detail_id = 65137
OR penjualan_detail_id = 65181
OR penjualan_detail_id = 65192

SELECT 124, barang_id, warna_id, gudang_id, qty, jumlah_roll,1
FROM nd_penjualan_qty_detail t1
LEFT JOIN nd_penjualan_detail t2
ON t1.penjualan_detail_id = t2.id 
WHERE penjualan_detail_id=35102


ALTER TABLE `nd_group_harga_baru` CHANGE `harga` `harga_cash` DECIMAL(10,2) NULL DEFAULT NULL;
ALTER TABLE `nd_group_harga_baru` ADD `harga_kredit` DECIMAL(15,2) NULL DEFAULT NULL AFTER `harga_cash`;

ALTER TABLE `nd_group_harga_history` CHANGE `harga` `harga_cash` DECIMAL(10,2) NULL DEFAULT NULL;
ALTER TABLE `nd_group_harga_history` ADD `harga_kredit` DECIMAL(15,2) NULL DEFAULT NULL AFTER `harga_cash`;

ALTER TABLE `nd_group_harga_berlaku` CHANGE `harga` `harga_cash` DECIMAL(10,2) NULL DEFAULT NULL;
ALTER TABLE `nd_group_harga_berlaku` ADD `harga_kredit` DECIMAL(15,2) NULL DEFAULT NULL AFTER `harga_cash`;


INSERT INTO nd_penjualan_remap_list(penjualan_remap_id, penjualan_id_asal, no_faktur_asal, tanggal_asal, penjualan_id_tujuan, no_faktur_tujuan, tanggal_tujuan, user_id) 
VALUES
('1', '23176',427,'2022-05-23','23455',6,'2022-06-02','1'), 
('1', '23214',463,'2022-05-24','23466',18,'2022-06-02','1'), 
('1', '23218',469,'2022-05-24','23476',28,'2022-06-02','1'), 
('1', '23225',476,'2022-05-24','23489',40,'2022-06-02','1'), 
('1', '23524',505,'2022-05-24','23494',46,'2022-06-02','1'), 

('1', '23264',515,'2022-05-25','23497',49,'2022-06-03','1'), 
('1', '23270',521,'2022-05-25','23505',57,'2022-06-03','1'), 
('1', '23286',537,'2022-05-25','23510',62,'2022-06-03','1'), 
('1', '23287',538,'2022-05-25','23520',72,'2022-06-03','1'), 
('1', '23301',552,'2022-05-25','23524',76,'2022-06-03','1'), 

('1', '23317',568,'2022-05-27','23542',94,'2022-06-04','1'), 
('1', '23330',581,'2022-05-27','23544',96,'2022-06-04','1'), 
('1', '23348',599,'2022-05-28','23548',100,'2022-06-04','1'), 
('1', '23379',630,'2022-05-30','23551',103,'2022-06-04','1'), 
('1', '23387',638,'2022-05-30','23552',104,'2022-06-04','1'),

('1', '23396',647,'2022-05-30','23553',105,'2022-06-04','1'), 
('1', '23410',661,'2022-05-31','23554',106,'2022-06-04','1'), 
('1', '23429',681,'2022-05-31','23555',107,'2022-06-04','1'), 
('1', '23448',699,'2022-05-31','23556',108,'2022-06-04','1');