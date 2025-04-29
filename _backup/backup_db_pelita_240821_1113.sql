-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.1.13-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win32
-- HeidiSQL Version:             9.4.0.5125
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Dumping structure for table favourtdj_system.customer_email
DROP TABLE IF EXISTS `customer_email`;
CREATE TABLE IF NOT EXISTS `customer_email` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `nama` varchar(200) DEFAULT NULL,
  `email` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table favourtdj_system.customer_email: ~0 rows (approximately)
DELETE FROM `customer_email`;
/*!40000 ALTER TABLE `customer_email` DISABLE KEYS */;
/*!40000 ALTER TABLE `customer_email` ENABLE KEYS */;

-- Dumping structure for table favourtdj_system.customer_npwp
DROP TABLE IF EXISTS `customer_npwp`;
CREATE TABLE IF NOT EXISTS `customer_npwp` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_type_id` int(1) NOT NULL DEFAULT '1',
  `nama` varchar(70) DEFAULT NULL,
  `alias` varchar(300) DEFAULT NULL,
  `alamat` varchar(1000) DEFAULT NULL,
  `telepon1` varchar(500) DEFAULT NULL,
  `telepon2` varchar(50) DEFAULT NULL,
  `npwp` varchar(200) DEFAULT NULL,
  `nik` varchar(16) DEFAULT NULL,
  `kota` varchar(200) DEFAULT NULL,
  `provinsi` varchar(100) DEFAULT NULL,
  `kode_pos` varchar(20) DEFAULT NULL,
  `email` varchar(200) DEFAULT NULL,
  `tempo_kredit` int(2) DEFAULT NULL,
  `warning_kredit` int(2) DEFAULT '0',
  `limit_warning_type` tinyint(4) DEFAULT NULL,
  `limit_amount` int(11) DEFAULT NULL,
  `limit_warning_amount` int(11) DEFAULT NULL,
  `status_aktif` int(1) NOT NULL DEFAULT '1',
  `img_link` varchar(500) DEFAULT NULL,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `tipe_company` varchar(3) DEFAULT NULL,
  `blok` varchar(100) NOT NULL DEFAULT '-',
  `no` varchar(100) DEFAULT '-',
  `rt` varchar(4) NOT NULL DEFAULT '000',
  `rw` varchar(4) NOT NULL DEFAULT '000',
  `kecamatan` varchar(100) NOT NULL DEFAULT '-',
  `kelurahan` varchar(100) NOT NULL DEFAULT '-',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table favourtdj_system.customer_npwp: ~0 rows (approximately)
DELETE FROM `customer_npwp`;
/*!40000 ALTER TABLE `customer_npwp` DISABLE KEYS */;
/*!40000 ALTER TABLE `customer_npwp` ENABLE KEYS */;

-- Dumping structure for table favourtdj_system.nd_bank_list
DROP TABLE IF EXISTS `nd_bank_list`;
CREATE TABLE IF NOT EXISTS `nd_bank_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_bank` varchar(100) DEFAULT NULL,
  `no_rek_bank` varchar(100) DEFAULT NULL,
  `tipe_trx_1` varchar(7) DEFAULT NULL,
  `tipe_trx_2` varchar(7) DEFAULT NULL,
  `status_default` tinyint(1) DEFAULT NULL,
  `status_aktif` tinyint(1) DEFAULT '1',
  `user_id` smallint(6) NOT NULL,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

-- Dumping data for table favourtdj_system.nd_bank_list: ~6 rows (approximately)
DELETE FROM `nd_bank_list`;
/*!40000 ALTER TABLE `nd_bank_list` DISABLE KEYS */;
INSERT INTO `nd_bank_list` (`id`, `nama_bank`, `no_rek_bank`, `tipe_trx_1`, `tipe_trx_2`, `status_default`, `status_aktif`, `user_id`, `updated_at`) VALUES
	(1, 'BCA', '2436001000', '#9bfccf', '#fffbda', 1, 1, 1, '2021-08-20 09:22:29'),
	(2, 'UOB', '5243008000', '#f5e8d4', '#abe7fa', NULL, 1, 10, '2021-02-24 04:58:56'),
	(3, 'BRI', 'BRI', '#926fc5', '#0a00ff', NULL, 1, 1, '2021-08-11 10:37:21'),
	(4, 'UOB', '5243008000', '#ff0000', '#501b1b', NULL, 1, 1, '2021-08-11 10:43:40'),
	(5, 'BCA', '293994', '#fb1a1a', '#501b1b', NULL, 1, 1, '2021-08-20 09:22:26'),
	(6, 'BRI', 'BRI', '', '', NULL, 1, 1, '2021-08-20 09:39:51');
/*!40000 ALTER TABLE `nd_bank_list` ENABLE KEYS */;

-- Dumping structure for table favourtdj_system.nd_barang
DROP TABLE IF EXISTS `nd_barang`;
CREATE TABLE IF NOT EXISTS `nd_barang` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `jenis_barang` varchar(100) DEFAULT 'Polyester',
  `nama` varchar(100) DEFAULT NULL,
  `nama_jual` varchar(100) DEFAULT NULL,
  `harga_jual` int(6) DEFAULT NULL,
  `harga_beli` int(6) DEFAULT NULL,
  `satuan_id` int(11) DEFAULT NULL,
  `qty_warning` int(11) NOT NULL DEFAULT '500',
  `deskripsi_info` varchar(500) DEFAULT NULL,
  `status_aktif` int(1) DEFAULT '1',
  `tipe_qty` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

-- Dumping data for table favourtdj_system.nd_barang: ~5 rows (approximately)
DELETE FROM `nd_barang`;
/*!40000 ALTER TABLE `nd_barang` DISABLE KEYS */;
INSERT INTO `nd_barang` (`id`, `jenis_barang`, `nama`, `nama_jual`, `harga_jual`, `harga_beli`, `satuan_id`, `qty_warning`, `deskripsi_info`, `status_aktif`, `tipe_qty`) VALUES
	(5, '0', 'KEYBOARD', 'KEYBOARD', 200000, 180000, 1, 500, NULL, 1, 1),
	(6, '0', 'MOUSE', 'MOUSE', 55000, 50000, 1, 500, NULL, 1, 1),
	(7, '0', 'MOUSE', 'MOUSE 1', 1100000, 945000, 1, 500, NULL, 1, 1),
	(8, 'POLYESTER', 'MOUSE PAD', 'MOUSE PAD', 19000, 12000, 1, 500, NULL, 1, 1),
	(9, '0', 'FLASHDISK 1', 'FLASHDISK 2', 200000, 180000, 1, 500, NULL, 1, 1),
	(10, '0', 'KEYBOARD 1', 'MONITOR 1', 0, 0, 1, 500, NULL, 1, 1),
	(11, '0', 'keyboard x', 'mouse x', 31000, 30000, 1, 500, NULL, 1, 1);
/*!40000 ALTER TABLE `nd_barang` ENABLE KEYS */;

-- Dumping structure for table favourtdj_system.nd_barang_forecasting_data
DROP TABLE IF EXISTS `nd_barang_forecasting_data`;
CREATE TABLE IF NOT EXISTS `nd_barang_forecasting_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `barang_id` smallint(6) DEFAULT NULL,
  `warna_id` smallint(6) DEFAULT NULL,
  `period` date DEFAULT NULL,
  `qty` mediumint(9) DEFAULT NULL,
  `user_id` smallint(6) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table favourtdj_system.nd_barang_forecasting_data: ~0 rows (approximately)
DELETE FROM `nd_barang_forecasting_data`;
/*!40000 ALTER TABLE `nd_barang_forecasting_data` DISABLE KEYS */;
/*!40000 ALTER TABLE `nd_barang_forecasting_data` ENABLE KEYS */;

-- Dumping structure for table favourtdj_system.nd_barang_forecasting_keterangan
DROP TABLE IF EXISTS `nd_barang_forecasting_keterangan`;
CREATE TABLE IF NOT EXISTS `nd_barang_forecasting_keterangan` (
  `id` int(11) NOT NULL,
  `barang_id` smallint(6) DEFAULT NULL,
  `warna_id` smallint(6) DEFAULT NULL,
  `period` date DEFAULT NULL,
  `keterangan` varchar(200) DEFAULT NULL,
  `user_id` smallint(6) DEFAULT NULL,
  `created` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table favourtdj_system.nd_barang_forecasting_keterangan: ~0 rows (approximately)
DELETE FROM `nd_barang_forecasting_keterangan`;
/*!40000 ALTER TABLE `nd_barang_forecasting_keterangan` DISABLE KEYS */;
/*!40000 ALTER TABLE `nd_barang_forecasting_keterangan` ENABLE KEYS */;

-- Dumping structure for table favourtdj_system.nd_barang_group
DROP TABLE IF EXISTS `nd_barang_group`;
CREATE TABLE IF NOT EXISTS `nd_barang_group` (
  `id` int(11) NOT NULL,
  `barang_id` smallint(6) DEFAULT NULL,
  `barang_id_induk` smallint(6) DEFAULT NULL,
  `user_id` smallint(6) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status_aktif` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table favourtdj_system.nd_barang_group: ~1 rows (approximately)
DELETE FROM `nd_barang_group`;
/*!40000 ALTER TABLE `nd_barang_group` DISABLE KEYS */;
/*!40000 ALTER TABLE `nd_barang_group` ENABLE KEYS */;

-- Dumping structure for table favourtdj_system.nd_barang_warna_temp
DROP TABLE IF EXISTS `nd_barang_warna_temp`;
CREATE TABLE IF NOT EXISTS `nd_barang_warna_temp` (
  `id` int(11) NOT NULL,
  `barang_id` int(11) DEFAULT NULL,
  `warna_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table favourtdj_system.nd_barang_warna_temp: ~0 rows (approximately)
DELETE FROM `nd_barang_warna_temp`;
/*!40000 ALTER TABLE `nd_barang_warna_temp` DISABLE KEYS */;
/*!40000 ALTER TABLE `nd_barang_warna_temp` ENABLE KEYS */;

-- Dumping structure for table favourtdj_system.nd_bayar_dp
DROP TABLE IF EXISTS `nd_bayar_dp`;
CREATE TABLE IF NOT EXISTS `nd_bayar_dp` (
  `id` int(11) NOT NULL,
  `nama` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table favourtdj_system.nd_bayar_dp: ~0 rows (approximately)
DELETE FROM `nd_bayar_dp`;
/*!40000 ALTER TABLE `nd_bayar_dp` DISABLE KEYS */;
/*!40000 ALTER TABLE `nd_bayar_dp` ENABLE KEYS */;

-- Dumping structure for table favourtdj_system.nd_bday_user
DROP TABLE IF EXISTS `nd_bday_user`;
CREATE TABLE IF NOT EXISTS `nd_bday_user` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `ulang_tahun` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table favourtdj_system.nd_bday_user: ~0 rows (approximately)
DELETE FROM `nd_bday_user`;
/*!40000 ALTER TABLE `nd_bday_user` DISABLE KEYS */;
/*!40000 ALTER TABLE `nd_bday_user` ENABLE KEYS */;

-- Dumping structure for table favourtdj_system.nd_close_day
DROP TABLE IF EXISTS `nd_close_day`;
CREATE TABLE IF NOT EXISTS `nd_close_day` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tanggal_start` date DEFAULT NULL,
  `tanggal_end` date DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- Dumping data for table favourtdj_system.nd_close_day: ~0 rows (approximately)
DELETE FROM `nd_close_day`;
/*!40000 ALTER TABLE `nd_close_day` DISABLE KEYS */;
INSERT INTO `nd_close_day` (`id`, `tanggal_start`, `tanggal_end`, `user_id`) VALUES
	(1, '2020-10-28', '2020-10-31', 1);
/*!40000 ALTER TABLE `nd_close_day` ENABLE KEYS */;

-- Dumping structure for table favourtdj_system.nd_controller
DROP TABLE IF EXISTS `nd_controller`;
CREATE TABLE IF NOT EXISTS `nd_controller` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

-- Dumping data for table favourtdj_system.nd_controller: ~8 rows (approximately)
DELETE FROM `nd_controller`;
/*!40000 ALTER TABLE `nd_controller` DISABLE KEYS */;
INSERT INTO `nd_controller` (`id`, `name`) VALUES
	(1, 'admin'),
	(2, 'delegate'),
	(3, 'master'),
	(4, 'transaction'),
	(5, 'inventory'),
	(6, 'report'),
	(7, 'finance'),
	(8, 'pajak');
/*!40000 ALTER TABLE `nd_controller` ENABLE KEYS */;

-- Dumping structure for table favourtdj_system.nd_customer
DROP TABLE IF EXISTS `nd_customer`;
CREATE TABLE IF NOT EXISTS `nd_customer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_type_id` int(1) NOT NULL DEFAULT '1',
  `nama` varchar(70) DEFAULT NULL,
  `alias` varchar(300) NOT NULL,
  `alamat` varchar(1000) DEFAULT NULL,
  `telepon1` varchar(500) DEFAULT NULL,
  `telepon2` varchar(50) DEFAULT NULL,
  `npwp` varchar(200) DEFAULT NULL,
  `nik` varchar(16) DEFAULT NULL,
  `kota` varchar(200) DEFAULT NULL,
  `provinsi` varchar(100) DEFAULT NULL,
  `kode_pos` varchar(20) DEFAULT NULL,
  `email` varchar(200) DEFAULT NULL,
  `tempo_kredit` int(2) DEFAULT NULL,
  `warning_kredit` int(2) DEFAULT '0',
  `limit_warning_type` tinyint(4) DEFAULT NULL,
  `limit_amount` int(11) DEFAULT NULL,
  `limit_atas` int(11) DEFAULT NULL,
  `limit_warning_amount` int(11) DEFAULT NULL,
  `status_aktif` int(1) NOT NULL DEFAULT '1',
  `npwp_link` varchar(500) DEFAULT NULL,
  `ktp_link` varchar(500) DEFAULT NULL,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `tipe_company` varchar(3) DEFAULT NULL,
  `blok` varchar(100) NOT NULL DEFAULT '-',
  `no` varchar(100) DEFAULT '-',
  `rt` varchar(4) NOT NULL DEFAULT '000',
  `rw` varchar(4) NOT NULL DEFAULT '000',
  `kecamatan` varchar(100) NOT NULL DEFAULT '-',
  `kelurahan` varchar(100) NOT NULL DEFAULT '-',
  `locked_status` tinyint(1) NOT NULL DEFAULT '1',
  `user_id` tinyint(4) NOT NULL DEFAULT '0',
  `medsos_link` varchar(1000) NOT NULL DEFAULT '-',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=latin1;

-- Dumping data for table favourtdj_system.nd_customer: ~16 rows (approximately)
DELETE FROM `nd_customer`;
/*!40000 ALTER TABLE `nd_customer` DISABLE KEYS */;
INSERT INTO `nd_customer` (`id`, `customer_type_id`, `nama`, `alias`, `alamat`, `telepon1`, `telepon2`, `npwp`, `nik`, `kota`, `provinsi`, `kode_pos`, `email`, `tempo_kredit`, `warning_kredit`, `limit_warning_type`, `limit_amount`, `limit_atas`, `limit_warning_amount`, `status_aktif`, `npwp_link`, `ktp_link`, `updated_at`, `tipe_company`, `blok`, `no`, `rt`, `rw`, `kecamatan`, `kelurahan`, `locked_status`, `user_id`, `medsos_link`) VALUES
	(1, 1, 'DINA', '', '-', '-', '0', '91.928.388.3-883.838', '0293092000200222', '-', '-', '@oke', '-', NULL, 0, NULL, NULL, NULL, NULL, 1, 'customer_1.jpeg', NULL, '2021-08-22 15:06:02', '', '-', '-', '003', '004', '-', '-', 1, 1, 'tessss'),
	(2, 2, 'DINA', '', '-', '-', '0', '20.029.393.8-848.848', '0939488484884848', '-', '-', '@oke', '-', 14, 2, NULL, 100000000, 100000000, 1, 1, NULL, NULL, '2021-08-22 15:06:04', '', '-', '-', '001', '001', '-', '-', 1, 1, 'tessss'),
	(3, 1, 'OKE', '', '-', '-', '0', '99.283.883.8-383.838', '', '-', '-', '@oke', '-', NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, '2021-08-22 15:06:05', '', '-', '-', '002', '002', '-', '-', 1, 1, 'tessss'),
	(4, 1, 'oke', '-', '-', '', '0', '', '2234234243234234', '111', '111', '@oke', '', NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, '2021-08-22 15:06:05', '', '01', '01', '001', '001', '111', '111', 1, 1, 'tessss'),
	(5, 1, 'AAA BBB', 'BBB', 'CCC', '-', '0', '19.288.388.3-843.848', '', '-', '-', '@oke', '-', NULL, 0, NULL, NULL, NULL, NULL, 1, '0', '0', '2021-08-23 10:02:55', '', 'A', 'B', '001', '001', '-', '-', 1, 1, 'tessss'),
	(6, 1, 'TES', 'A', 'A', '-', '0', '19.283.884.8-488.484', '', '-', '-', '@oke', '-', NULL, 0, NULL, NULL, NULL, NULL, 1, 'customer_6.jpeg', NULL, '2021-08-22 15:06:06', '', 'A', 'A', '001', '001', '-', '-', 1, 1, 'tessss'),
	(7, 1, 'DKKDKDK', '-', '-', '-', '0', '81.818.818.1-818.188', '', '-', '-', '@oke', '-', NULL, 0, NULL, NULL, NULL, NULL, 1, 'customer_7.jpeg', NULL, '2021-08-22 15:06:06', '', '-', '-', '001', '000', '-', '-', 1, 1, 'tessss'),
	(8, 1, 'aaaa', 'd', 'd', '--', '0', '19.292.888.8-383.883', '', '-', '-', '@oke', '-', NULL, 0, NULL, NULL, NULL, NULL, 1, 'customer_8.jpeg', NULL, '2021-08-22 15:06:07', '', '001', '001', '001', '001', '-', '-', 1, 1, 'tessss'),
	(9, 1, 'AAA BBB CCC', 'A', 'A', '-', '0', '91.928.838.3-848.477', '', '-', '-', '@oke', '-', NULL, 0, NULL, NULL, NULL, NULL, 1, '', '', '2021-08-23 10:04:54', '', 'A', 'A', '000', '000', '-', '-', 1, 1, 'tessss'),
	(10, 1, 'TESTUM', 'DSDSFS', 'SDFSDF', '0', '0', '89.293.883.8-383.838', '', '0', '0', '@oke', '0', NULL, 0, NULL, NULL, NULL, NULL, 1, 'npwp_10_20210823101245.jpeg', '', '2021-08-23 10:12:45', '', 'S', 'DSF', '000', '000', '0', '0', 1, 1, 'tessss'),
	(11, 1, 'ASDASDAS', 'ASDASD', 'ASDASD', '-', '0', '99.999.999.9-999.999', '', '-', '-', '@oke', '-', NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, '2021-08-22 15:06:09', '', 'ASDIJ', '0ASK', '000', '000', '-', '-', 1, 1, 'tessss'),
	(12, 1, 'WEQWE', 'WQEQWEEW', 'WQEQWE', '1', '0', '11.111.111.1-111.111', '', '1', '111', '@oke', '1', NULL, 0, NULL, NULL, NULL, NULL, 0, NULL, NULL, '2021-08-22 15:06:09', '', 'EE', 'EEE', '111', '111', '1', '1', 1, 1, 'tessss'),
	(13, 1, 'kaodkao', '', '-', '-', '0', '82.882.828.2-828.888', '9299288888222222', '-', '-', '@oke', '-', NULL, 0, NULL, NULL, NULL, NULL, 0, NULL, NULL, '2021-08-22 15:06:10', '', '-', '-', '000', '000', '-', '-', 1, 1, 'tessss'),
	(14, 1, 'AAAA', 'FFF GGG HHH', 'SDFSDF', '-', '0', '01.010.100.1-010.002', '', '-', '-', '@oke', '-', NULL, 0, NULL, NULL, NULL, NULL, 1, 'npwp_14_20210823103910.jpeg', 'ktp_14_20210823103934.jpeg', '2021-08-24 07:19:39', '', 'SFSF', 'SDFSFD', '111', '111', '-', '-', 1, 1, 'tessss'),
	(15, 1, 'aaa', 'sadasd', 'asdasd', '0', '0', '', '', '0', '0', '@oke', '0', NULL, 0, NULL, NULL, NULL, NULL, 1, 'npwp_15.jpeg', NULL, '2021-08-22 15:06:11', '', 'ads', 'asd', '000', '000', '0', '0', 1, 1, 'tessss'),
	(16, 1, 'AABBCC', 'A', 'A', '0', '0', '', '', '-', '-', '@oke', '0', NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, '2021-08-22 15:06:12', '', 'A', 'A', '000', '000', '-', '-', 1, 1, 'tessss'),
	(17, 1, 'KOKO', 'ASDSA', '-', '-', '0', '', '', '-', '-', '@oke', '-', NULL, 0, NULL, NULL, NULL, NULL, 1, 'npwp_17.jpeg', 'ktp_17.jpeg', '2021-08-22 15:06:12', '', '-', '-', '000', '000', '-', '-', 1, 1, 'tessss'),
	(18, 1, 'TESTING', '-', '-', '-', '0', '', '', '-', '-', '@oke', '--', NULL, 0, NULL, NULL, NULL, NULL, 1, 'npwp_18_20210821120308.jpeg', 'ktp_18_20210821120308.jpeg', '2021-08-22 15:06:12', '', '-', '-', '000', '000', '-', '-', 1, 1, 'tessss'),
	(19, 1, 'tester', '-', '-', '0', '0', '', '0', '0', '0', '@oke', '0', NULL, 0, NULL, NULL, NULL, NULL, 1, 'npwp_19_20210821142801.jpeg', 'ktp_19_20210821142801.jpeg', '2021-08-22 15:06:13', '', '-', '-', '000', '000', '0', '0', 1, 1, 'tessss');
/*!40000 ALTER TABLE `nd_customer` ENABLE KEYS */;

-- Dumping structure for table favourtdj_system.nd_customer_alamat_kirim
DROP TABLE IF EXISTS `nd_customer_alamat_kirim`;
CREATE TABLE IF NOT EXISTS `nd_customer_alamat_kirim` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` smallint(5) unsigned DEFAULT NULL,
  `alamat` varchar(500) DEFAULT NULL,
  `catatan` varchar(100) DEFAULT NULL,
  `user_id` tinyint(2) unsigned DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status_aktif` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=latin1;

-- Dumping data for table favourtdj_system.nd_customer_alamat_kirim: ~8 rows (approximately)
DELETE FROM `nd_customer_alamat_kirim`;
/*!40000 ALTER TABLE `nd_customer_alamat_kirim` DISABLE KEYS */;
INSERT INTO `nd_customer_alamat_kirim` (`id`, `customer_id`, `alamat`, `catatan`, `user_id`, `created_at`, `updated_at`, `status_aktif`) VALUES
	(1, 2, 'JL. CITRA NO.10', '-', 1, '2021-08-11 13:38:38', '2021-08-19 11:38:31', 1),
	(2, 2, 'jl. citra no.10', '-', 1, '2021-08-11 13:39:15', '2021-08-19 11:43:51', 1),
	(3, 2, 'JL. CITRA NO.10', '-', 1, '2021-08-11 13:39:33', '2021-08-19 11:43:39', 1),
	(18, 2, '1', '1', 1, '2021-08-19 09:31:07', '2021-08-19 11:43:57', 1),
	(19, 2, '2', '2', 1, '2021-08-19 09:32:56', '2021-08-19 11:43:55', 1),
	(20, 2, '2', '2', 1, '2021-08-19 09:33:11', '2021-08-19 11:43:48', 1),
	(21, 2, 'aaaa aaaa', 'tes 123', 1, '2021-08-19 09:33:17', '2021-08-19 13:42:25', 1),
	(22, 15, 'sdasdad', 'asdads', 1, '2021-08-24 10:40:13', '2021-08-24 10:40:13', 1);
/*!40000 ALTER TABLE `nd_customer_alamat_kirim` ENABLE KEYS */;

-- Dumping structure for table favourtdj_system.nd_dp_keluar
DROP TABLE IF EXISTS `nd_dp_keluar`;
CREATE TABLE IF NOT EXISTS `nd_dp_keluar` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dp_masuk_id` smallint(6) DEFAULT NULL,
  `pembayaran_type_id` tinyint(1) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `amount` decimal(15,2) DEFAULT NULL,
  `keterangan` varchar(200) DEFAULT NULL,
  `user_id` tinyint(4) DEFAULT NULL,
  `nama_bank` varchar(100) DEFAULT NULL,
  `no_rek_bank` varchar(100) DEFAULT NULL,
  `nama_penerima` varchar(100) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table favourtdj_system.nd_dp_keluar: ~0 rows (approximately)
DELETE FROM `nd_dp_keluar`;
/*!40000 ALTER TABLE `nd_dp_keluar` DISABLE KEYS */;
/*!40000 ALTER TABLE `nd_dp_keluar` ENABLE KEYS */;

-- Dumping structure for table favourtdj_system.nd_dp_masuk
DROP TABLE IF EXISTS `nd_dp_masuk`;
CREATE TABLE IF NOT EXISTS `nd_dp_masuk` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `no_dp` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `pembayaran_type_id` int(2) DEFAULT NULL,
  `nama_bank` varchar(100) DEFAULT NULL,
  `no_rek_bank` varchar(100) DEFAULT NULL,
  `urutan_giro` int(5) DEFAULT NULL,
  `no_giro` varchar(50) DEFAULT NULL,
  `no_akun_giro` varchar(50) DEFAULT NULL,
  `tanggal_giro` date DEFAULT NULL,
  `jatuh_tempo` date DEFAULT NULL,
  `nama_penerima` varchar(100) DEFAULT NULL,
  `amount` int(7) NOT NULL DEFAULT '0',
  `keterangan` varchar(100) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `created` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table favourtdj_system.nd_dp_masuk: ~0 rows (approximately)
DELETE FROM `nd_dp_masuk`;
/*!40000 ALTER TABLE `nd_dp_masuk` DISABLE KEYS */;
/*!40000 ALTER TABLE `nd_dp_masuk` ENABLE KEYS */;

-- Dumping structure for table favourtdj_system.nd_dp_masuk_
DROP TABLE IF EXISTS `nd_dp_masuk_`;
CREATE TABLE IF NOT EXISTS `nd_dp_masuk_` (
  `id` int(11) NOT NULL,
  `no_dp` int(11) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `penyerah` varchar(200) NOT NULL,
  `penerima` varchar(200) NOT NULL,
  `tanggal` date DEFAULT NULL,
  `amount` int(11) DEFAULT NULL,
  `pembayaran_type_id` int(2) NOT NULL DEFAULT '1',
  `keterangan` varchar(100) DEFAULT NULL,
  `user_id` int(3) DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table favourtdj_system.nd_dp_masuk_: ~0 rows (approximately)
DELETE FROM `nd_dp_masuk_`;
/*!40000 ALTER TABLE `nd_dp_masuk_` DISABLE KEYS */;
/*!40000 ALTER TABLE `nd_dp_masuk_` ENABLE KEYS */;

-- Dumping structure for table favourtdj_system.nd_giro_list
DROP TABLE IF EXISTS `nd_giro_list`;
CREATE TABLE IF NOT EXISTS `nd_giro_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bank_list_id` tinyint(4) NOT NULL,
  `tipe_trx` tinyint(4) NOT NULL DEFAULT '1',
  `no_giro_awal` varchar(100) DEFAULT NULL,
  `jml_giro` smallint(6) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- Dumping data for table favourtdj_system.nd_giro_list: ~2 rows (approximately)
DELETE FROM `nd_giro_list`;
/*!40000 ALTER TABLE `nd_giro_list` DISABLE KEYS */;
INSERT INTO `nd_giro_list` (`id`, `bank_list_id`, `tipe_trx`, `no_giro_awal`, `jml_giro`, `user_id`, `created`) VALUES
	(1, 1, 1, '1101', 10, 1, '2021-08-13 11:20:31'),
	(2, 1, 2, '2201', 2, 1, '2021-08-13 11:25:20');
/*!40000 ALTER TABLE `nd_giro_list` ENABLE KEYS */;

-- Dumping structure for table favourtdj_system.nd_giro_list_detail
DROP TABLE IF EXISTS `nd_giro_list_detail`;
CREATE TABLE IF NOT EXISTS `nd_giro_list_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `giro_list_id` int(11) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `no_giro` varchar(20) DEFAULT NULL,
  `amount` bigint(11) DEFAULT NULL,
  `jatuh_tempo` date DEFAULT NULL,
  `penerima` varchar(300) DEFAULT NULL,
  `keterangan` varchar(500) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `updated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table favourtdj_system.nd_giro_list_detail: ~0 rows (approximately)
DELETE FROM `nd_giro_list_detail`;
/*!40000 ALTER TABLE `nd_giro_list_detail` DISABLE KEYS */;
/*!40000 ALTER TABLE `nd_giro_list_detail` ENABLE KEYS */;

-- Dumping structure for table favourtdj_system.nd_giro_setor
DROP TABLE IF EXISTS `nd_giro_setor`;
CREATE TABLE IF NOT EXISTS `nd_giro_setor` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `toko_id` int(11) NOT NULL DEFAULT '1',
  `tanggal` date DEFAULT NULL,
  `keterangan` varchar(200) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table favourtdj_system.nd_giro_setor: ~0 rows (approximately)
DELETE FROM `nd_giro_setor`;
/*!40000 ALTER TABLE `nd_giro_setor` DISABLE KEYS */;
/*!40000 ALTER TABLE `nd_giro_setor` ENABLE KEYS */;

-- Dumping structure for table favourtdj_system.nd_giro_setor_detail
DROP TABLE IF EXISTS `nd_giro_setor_detail`;
CREATE TABLE IF NOT EXISTS `nd_giro_setor_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `numerator` int(11) DEFAULT NULL,
  `giro_setor_id` int(11) DEFAULT NULL,
  `pembayaran_piutang_nilai_id` int(11) DEFAULT NULL,
  `data_type` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table favourtdj_system.nd_giro_setor_detail: ~0 rows (approximately)
DELETE FROM `nd_giro_setor_detail`;
/*!40000 ALTER TABLE `nd_giro_setor_detail` DISABLE KEYS */;
/*!40000 ALTER TABLE `nd_giro_setor_detail` ENABLE KEYS */;

-- Dumping structure for table favourtdj_system.nd_giro_terima_before
DROP TABLE IF EXISTS `nd_giro_terima_before`;
CREATE TABLE IF NOT EXISTS `nd_giro_terima_before` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pembayaran_type_id` tinyint(4) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `urutan_giro` smallint(6) DEFAULT NULL,
  `nama_bank` varchar(100) DEFAULT NULL,
  `no_rek_bank` varchar(100) DEFAULT NULL,
  `no_giro` varchar(20) DEFAULT NULL,
  `jatuh_tempo` date DEFAULT NULL,
  `amount` int(11) DEFAULT NULL,
  `pembayaran_piutang_id_before` smallint(6) DEFAULT NULL,
  `pembayaran_piutang_detail_id_before` mediumint(9) DEFAULT NULL,
  `customer_id` smallint(6) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table favourtdj_system.nd_giro_terima_before: ~0 rows (approximately)
DELETE FROM `nd_giro_terima_before`;
/*!40000 ALTER TABLE `nd_giro_terima_before` DISABLE KEYS */;
/*!40000 ALTER TABLE `nd_giro_terima_before` ENABLE KEYS */;

-- Dumping structure for table favourtdj_system.nd_giro_tolakan
DROP TABLE IF EXISTS `nd_giro_tolakan`;
CREATE TABLE IF NOT EXISTS `nd_giro_tolakan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` smallint(6) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `pembayaran_piutang_nilai_id` smallint(6) DEFAULT NULL,
  `keterangan` varchar(200) DEFAULT NULL,
  `user_id` smallint(6) DEFAULT NULL,
  `created` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table favourtdj_system.nd_giro_tolakan: ~0 rows (approximately)
DELETE FROM `nd_giro_tolakan`;
/*!40000 ALTER TABLE `nd_giro_tolakan` DISABLE KEYS */;
/*!40000 ALTER TABLE `nd_giro_tolakan` ENABLE KEYS */;

-- Dumping structure for table favourtdj_system.nd_giro_urutan
DROP TABLE IF EXISTS `nd_giro_urutan`;
CREATE TABLE IF NOT EXISTS `nd_giro_urutan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `source_table_id` int(11) DEFAULT NULL,
  `data_type` int(1) DEFAULT '1',
  `urutan` int(5) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table favourtdj_system.nd_giro_urutan: ~0 rows (approximately)
DELETE FROM `nd_giro_urutan`;
/*!40000 ALTER TABLE `nd_giro_urutan` DISABLE KEYS */;
/*!40000 ALTER TABLE `nd_giro_urutan` ENABLE KEYS */;

-- Dumping structure for table favourtdj_system.nd_gudang
DROP TABLE IF EXISTS `nd_gudang`;
CREATE TABLE IF NOT EXISTS `nd_gudang` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(70) DEFAULT NULL,
  `lokasi` varchar(20) DEFAULT NULL,
  `status_default` int(1) NOT NULL DEFAULT '0',
  `visible` tinyint(1) NOT NULL DEFAULT '1',
  `urutan` int(11) NOT NULL,
  `status_aktif` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

-- Dumping data for table favourtdj_system.nd_gudang: ~7 rows (approximately)
DELETE FROM `nd_gudang`;
/*!40000 ALTER TABLE `nd_gudang` DISABLE KEYS */;
INSERT INTO `nd_gudang` (`id`, `nama`, `lokasi`, `status_default`, `visible`, `urutan`, `status_aktif`) VALUES
	(1, 'GUDANG', 'DRSD', 1, 1, 2, 1),
	(2, 'TOKO', 'TAMIM', 0, 1, 1, 1),
	(3, 'LNGKNG', 'LNGKNG', 0, 1, 3, 1),
	(4, 'PHRJ', 'PHRJ', 0, 1, 5, 1),
	(5, 'PKR', 'PKR', 0, 1, 4, 1),
	(6, 'TOKO', 'TAMIM', 0, 1, 6, 1),
	(7, 'PKR', 'TAMIM', 0, 0, 6, 1);
/*!40000 ALTER TABLE `nd_gudang` ENABLE KEYS */;

-- Dumping structure for table favourtdj_system.nd_hasil_so
DROP TABLE IF EXISTS `nd_hasil_so`;
CREATE TABLE IF NOT EXISTS `nd_hasil_so` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `stok_opname_id` mediumint(9) DEFAULT NULL,
  `barang_id` smallint(6) DEFAULT NULL,
  `warna_id` smallint(6) DEFAULT NULL,
  `gudang_id` tinyint(4) DEFAULT NULL,
  `qty` decimal(10,2) DEFAULT NULL,
  `jumlah_roll` smallint(6) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table favourtdj_system.nd_hasil_so: ~0 rows (approximately)
DELETE FROM `nd_hasil_so`;
/*!40000 ALTER TABLE `nd_hasil_so` DISABLE KEYS */;
/*!40000 ALTER TABLE `nd_hasil_so` ENABLE KEYS */;

-- Dumping structure for table favourtdj_system.nd_history_harga_customer
DROP TABLE IF EXISTS `nd_history_harga_customer`;
CREATE TABLE IF NOT EXISTS `nd_history_harga_customer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `barang_id` smallint(6) DEFAULT NULL,
  `warna_id` smallint(6) DEFAULT NULL,
  `customer_id` smallint(6) DEFAULT NULL,
  `harga_jual` mediumint(9) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `penjualan_id` mediumint(9) DEFAULT NULL,
  `no_faktur` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table favourtdj_system.nd_history_harga_customer: ~0 rows (approximately)
DELETE FROM `nd_history_harga_customer`;
/*!40000 ALTER TABLE `nd_history_harga_customer` DISABLE KEYS */;
/*!40000 ALTER TABLE `nd_history_harga_customer` ENABLE KEYS */;

-- Dumping structure for table favourtdj_system.nd_hutang_awal
DROP TABLE IF EXISTS `nd_hutang_awal`;
CREATE TABLE IF NOT EXISTS `nd_hutang_awal` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `toko_id` tinyint(1) NOT NULL DEFAULT '1',
  `supplier_id` tinyint(2) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `no_faktur` varchar(40) DEFAULT NULL,
  `amount` int(11) DEFAULT NULL,
  `jatuh_tempo` date NOT NULL,
  `jumlah_roll` smallint(4) NOT NULL,
  `user_id` tinyint(2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table favourtdj_system.nd_hutang_awal: ~0 rows (approximately)
DELETE FROM `nd_hutang_awal`;
/*!40000 ALTER TABLE `nd_hutang_awal` DISABLE KEYS */;
/*!40000 ALTER TABLE `nd_hutang_awal` ENABLE KEYS */;

-- Dumping structure for table favourtdj_system.nd_maintenance_list
DROP TABLE IF EXISTS `nd_maintenance_list`;
CREATE TABLE IF NOT EXISTS `nd_maintenance_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `start_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `end_time` datetime DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table favourtdj_system.nd_maintenance_list: ~0 rows (approximately)
DELETE FROM `nd_maintenance_list`;
/*!40000 ALTER TABLE `nd_maintenance_list` DISABLE KEYS */;
/*!40000 ALTER TABLE `nd_maintenance_list` ENABLE KEYS */;

-- Dumping structure for table favourtdj_system.nd_menu
DROP TABLE IF EXISTS `nd_menu`;
CREATE TABLE IF NOT EXISTS `nd_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status_aktif` int(1) DEFAULT '1',
  `icon_class` varchar(100) NOT NULL,
  `nama_id` varchar(50) NOT NULL,
  `text` varchar(200) NOT NULL,
  `urutan` int(3) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `status_aktif` (`status_aktif`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

-- Dumping data for table favourtdj_system.nd_menu: ~8 rows (approximately)
DELETE FROM `nd_menu`;
/*!40000 ALTER TABLE `nd_menu` DISABLE KEYS */;
INSERT INTO `nd_menu` (`id`, `status_aktif`, `icon_class`, `nama_id`, `text`, `urutan`) VALUES
	(1, 1, 'fa fa-gears', 'menu_setting', 'Settings', 10),
	(2, 1, 'fa fa-users', 'menu_delegate', 'Delegate', 9),
	(3, 1, 'icon-docs', 'menu_master', 'Master', 0),
	(4, 1, 'fa fa-shopping-cart', 'transaction', 'Transaksi', 1),
	(5, 1, 'fa fa-cubes', 'menu_inventory', 'Inventory', 4),
	(6, 1, 'icon-graph', 'menu_report', 'Laporan', 4),
	(7, 1, 'icon-calculator', 'menu_finance', 'Hutang/Piutang', 5),
	(8, 1, 'fa fa-bank', 'menu_pajak', 'Pajak', 6);
/*!40000 ALTER TABLE `nd_menu` ENABLE KEYS */;

-- Dumping structure for table favourtdj_system.nd_menu_detail
DROP TABLE IF EXISTS `nd_menu_detail`;
CREATE TABLE IF NOT EXISTS `nd_menu_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `menu_id` int(11) NOT NULL,
  `controller` varchar(50) DEFAULT NULL,
  `page_link` varchar(50) DEFAULT NULL,
  `status_aktif` int(1) DEFAULT '1',
  `text` varchar(100) NOT NULL,
  `urutan` int(2) NOT NULL DEFAULT '1',
  `level` int(1) NOT NULL DEFAULT '1',
  `parent_id` int(5) DEFAULT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3002 DEFAULT CHARSET=latin1;

-- Dumping data for table favourtdj_system.nd_menu_detail: ~124 rows (approximately)
DELETE FROM `nd_menu_detail`;
/*!40000 ALTER TABLE `nd_menu_detail` DISABLE KEYS */;
INSERT INTO `nd_menu_detail` (`id`, `menu_id`, `controller`, `page_link`, `status_aktif`, `text`, `urutan`, `level`, `parent_id`, `created`) VALUES
	(1, 1, 'admin', 'setting_change_password', 1, 'Ganti Password', 3, 1, 0, '2018-07-29 19:41:01'),
	(2, 2, 'delegate', 'posisi_list', 1, 'Atur User Level', 2, 1, 0, '2018-07-29 19:41:01'),
	(3, 2, 'delegate', 'menu_list', 1, 'Daftar Menu', 3, 1, 0, '2018-07-29 19:41:01'),
	(5, 3, 'master', 'barang_list', 1, 'Barang', 1, 1, 0, '2018-07-29 19:41:01'),
	(7, 3, 'master', 'supplier_list', 1, 'Supplier', 2, 1, 0, '2018-07-29 19:41:01'),
	(8, 3, 'master', 'customer_list', 1, 'Customer', 3, 1, 0, '2018-07-29 19:41:01'),
	(10, 3, 'master', 'toko_list', 1, 'Profile', 4, 1, 0, '2018-07-29 19:41:01'),
	(12, 4, 'transaction', 'pembelian_list', 1, 'Pembelian', 2, 2, 102, '2018-07-29 19:41:01'),
	(13, 4, 'transaction', 'pembelian_list_detail', 1, 'Input Pembelian', 1, 2, 102, '2018-07-29 19:41:01'),
	(14, 4, 'transaction', 'penjualan_list', 1, 'Penjualan', 2, 2, 103, '2018-07-29 19:41:01'),
	(15, 4, 'transaction', 'penjualan_list_detail', 1, 'Input Penjualan', 1, 2, 103, '2018-07-29 19:41:01'),
	(16, 4, 'transaction', 'dp_list', 1, 'Uang Muka DP', 11, 1, NULL, '2018-07-29 19:41:01'),
	(17, 4, 'transaction', 'dp_list_detail', 1, 'DP Detail', 99, 1, 0, '2018-07-29 19:41:01'),
	(18, 5, 'inventory', 'mutasi_barang', 1, 'Mutasi Barang', 1, 2, 107, '2018-07-29 19:41:01'),
	(19, 5, 'inventory', 'mutasi_barang_detail', 1, 'Mutasi Barang Detail', 99, 99, 107, '2018-07-29 19:41:01'),
	(20, 5, 'inventory', 'stok_barang', 1, 'Stok Barang Old', 99, 99, NULL, '2018-07-29 19:41:01'),
	(21, 5, 'inventory', 'kartu_stok', 1, 'Kartu Stok', 99, 99, NULL, '2018-07-29 19:41:01'),
	(22, 6, 'report', 'penjualan_list_report', 1, 'Penjualan', 1, 2, 110, '2018-07-29 19:41:01'),
	(23, 6, 'report', 'pembelian_list_report', 1, 'Pembelian', 1, 2, 109, '2018-07-29 19:41:01'),
	(24, 4, 'transaction', 'pembelian_input_history', 1, 'Pembelian History', 4, 2, 102, '2018-07-29 19:41:01'),
	(25, 4, 'transaction', 'penjualan_input_history', 1, 'Penjualan History', 3, 2, 103, '2018-07-29 19:41:01'),
	(26, 7, 'finance', 'piutang_list', 1, 'Daftar Piutang', 2, 2, 114, '2018-07-29 19:41:01'),
	(27, 7, 'finance', 'piutang_payment', 1, 'Daftar Pembayaran Piutang', 3, 2, 114, '2018-07-29 19:41:01'),
	(28, 7, 'finance', 'hutang_list', 1, 'Daftar Hutang', 2, 2, 113, '2018-07-29 19:41:01'),
	(29, 7, 'finance', 'hutang_payment', 1, 'Daftar Pembayaran Hutang', 3, 2, 113, '2018-07-29 19:41:01'),
	(30, 7, 'finance', 'piutang_list_detail', 1, 'Piutang Detail', 99, 1, 0, '2018-07-29 19:41:01'),
	(31, 7, 'finance', 'hutang_list_detail', 1, 'Hutang Detail', 99, 99, 113, '2018-07-29 19:41:01'),
	(32, 7, 'finance', 'hutang_payment_form', 1, 'Input Pembayaran Hutang', 1, 2, 113, '2018-07-29 19:41:01'),
	(33, 7, 'finance', 'piutang_payment_form', 1, 'Input Pembayaran Piutang', 1, 2, 114, '2018-07-29 19:41:01'),
	(35, 6, 'report', 'penerimaan_harian_penjualan', 1, 'Laporan Penerimaan', 2, 2, 110, '2018-07-29 19:41:01'),
	(36, 4, 'transaction', 'retur_jual_list', 1, 'Retur Penjualan', 2, 2, 104, '2018-07-29 19:41:01'),
	(37, 4, 'transaction', 'retur_jual_detail', 1, 'Retur Jual Detail', 99, 1, 0, '2018-07-29 19:41:01'),
	(38, 1, 'admin', 'change_pin', 1, 'Setting PIN', 4, 1, 0, '2018-07-29 19:41:01'),
	(39, 5, 'inventory', 'mutasi_stok_awal', 1, 'Mutasi Stok Awal', 3, 2, 107, '2018-07-29 19:41:01'),
	(40, 5, 'inventory', 'stok_barang_by_barang', 1, 'Stok Per Barang', 2, 2, 106, '2018-07-29 19:41:01'),
	(41, 6, 'report', 'penjualan_general_report', 1, 'Penjualan General', 3, 2, 110, '2018-07-29 19:41:01'),
	(42, 5, 'inventory', 'stok_awal_harga', 1, 'Harga Stok Awal', 2, 2, 108, '2018-07-29 19:41:01'),
	(43, 5, 'inventory', 'stok_barang_hpp', 1, 'Stok Barang + HPP', 5, 1, 0, '2018-07-29 19:41:01'),
	(44, 6, 'report', 'penjualan_laba_list_report', 1, 'Penjualan GP', 2, 2, 110, '2018-07-29 19:41:01'),
	(45, 7, 'finance', 'mutasi_hutang_list', 1, 'Mutasi Hutang', 1, 2, 115, '2018-07-29 19:41:01'),
	(46, 7, 'finance', 'mutasi_piutang_list', 1, 'Mutasi Piutang', 2, 2, 115, '2018-07-29 19:41:01'),
	(47, 6, 'report', 'barang_masuk_list_report', 1, 'Barang Masuk', 3, 2, 111, '2018-07-29 19:41:01'),
	(48, 6, 'report', 'barang_masuk_list_detail_report', 1, 'Barang Masuk Detail', 99, 1, 0, '2018-07-29 19:41:01'),
	(49, 7, 'finance', 'giro_list', 1, 'Daftar GIRO Masuk', 1, 2, 116, '2018-07-29 19:41:01'),
	(50, 7, 'finance', 'giro_setor_list', 1, 'Daftar Setoran Giro', 3, 2, 116, '2018-07-29 19:41:01'),
	(51, 7, 'finance', 'giro_setor_list_form', 1, 'Input Setoran Giro', 3, 2, 116, '2018-07-29 19:41:01'),
	(52, 7, 'finance', 'mutasi_hutang_list_detail', 1, 'Mutasi Hutang Detail', 99, 1, 0, '2018-07-29 19:41:01'),
	(53, 7, 'finance', 'mutasi_piutang_list_detail', 1, 'Mutasi Piutang Detail', 99, 1, 0, '2018-07-29 19:41:01'),
	(54, 5, 'inventory', 'mutasi_persediaan_barang', 1, 'Mutasi Persediaan Barang', 2, 2, 107, '2018-07-29 19:41:01'),
	(55, 7, 'finance', 'hutang_awal', 1, 'Daftar Hutang Awal', 3, 2, 115, '2018-07-29 19:41:01'),
	(56, 7, 'finance', 'hutang_awal_detail', 1, 'Hutang Awal Detail', 99, 1, 0, '2018-07-29 19:41:01'),
	(57, 7, 'finance', 'piutang_awal', 1, 'Daftar Piutang Awal', 4, 2, 115, '2018-07-29 19:41:01'),
	(58, 7, 'finance', 'piutang_awal_detail', 1, 'Piutang Awal Detail', 99, 1, 0, '2018-07-29 19:41:01'),
	(59, 5, 'inventory', 'stok_barang_rekap', 1, 'Stok Barang (no warna)', 3, 2, 106, '2018-07-29 19:41:01'),
	(60, 6, 'report', 'buku_laporan_piutang', 1, 'Buku Laporan Piutang', 6, 1, 0, '2018-07-29 19:41:01'),
	(62, 6, 'report', 'laporan_penyesuaian_stok', 1, 'Rekap Penyesuaian Stok', 3, 2, 111, '2018-09-10 03:25:22'),
	(63, 5, 'inventory', 'stok_opname', 1, 'Stok Opname', 1, 2, 108, '2019-01-01 11:28:53'),
	(64, 5, 'inventory', 'stok_opname_detail', 1, 'Stok Opname Detail', 99, 1, 0, '2019-01-01 11:29:22'),
	(65, 1, 'admin', 'change_default_printer', 1, 'Ubah Default Printer', 2, 1, 0, '2019-01-19 10:06:37'),
	(66, 5, 'inventory', 'stok_barang_ajax', 1, 'Stok Barang', 1, 2, 106, '2019-02-28 09:15:39'),
	(67, 4, 'transaction', 'po_pembelian_list', 1, 'PO Pembelian', 0, 1, 0, '2019-03-14 04:44:46'),
	(68, 4, 'transaction', 'po_pembelian_detail', 1, 'PO Detail', 99, 1, 0, '2019-03-14 04:45:24'),
	(69, 4, 'transaction', 'po_pembelian_detail_warna', 1, 'PO Detail Warna', 99, 1, NULL, '2019-03-14 04:45:56'),
	(70, 4, 'transaction', 'po_pembelian_detail_warna_batch', 1, 'PO Detail Batch', 99, 1, 0, '2019-03-14 04:45:56'),
	(71, 6, 'report', 'po_gantung_list', 1, 'PO Gantung', 1, 2, 112, '2019-04-29 04:18:15'),
	(72, 6, 'report', 'po_gantung_list_detail', 1, 'PO Gantung Detail', 99, 1, 0, '2019-04-29 04:18:15'),
	(73, 6, 'report', 'po_pembelian_report', 1, 'PO Pembelian', 1, 2, 112, '2019-04-29 04:19:59'),
	(74, 6, 'report', 'po_pembelian_report_detail', 1, 'PO Pembelian Detail', 99, 1, NULL, '2019-04-29 04:19:59'),
	(75, 6, 'report', 'tutup_buku_list', 1, 'Tutup Buku', 4, 2, 111, '2019-05-02 02:27:24'),
	(76, 7, 'finance', 'giro_register_list', 1, 'Giro Register', 0, 1, 0, '2019-05-22 03:49:21'),
	(77, 7, 'finance', 'giro_register_list_detail', 1, 'Giro Register Detail', 99, 1, 0, '2019-05-22 03:52:23'),
	(79, 7, 'finance', 'outstanding_list_detail', 1, 'Outstanding Detail', 99, 1, 0, '2019-06-18 03:45:39'),
	(80, 6, 'report', 'barang_keluar_list_report', 1, 'Barang Keluar', 2, 2, 111, '2019-07-23 09:25:49'),
	(81, 7, 'finance', 'outstanding_hutang_list_detail', 1, 'Outstanding Hutang Detail', 99, 1, 0, '2019-07-29 06:35:23'),
	(83, 8, 'pajak', 'no_fp_list', 1, 'Daftar Nomor', 0, 1, 0, '2019-09-09 07:18:14'),
	(84, 8, 'pajak', 'no_fp_list_detail', 1, 'Daftar Nomor Faktur Pajak Detail', 99, 1, 0, '2019-09-09 07:18:15'),
	(85, 8, 'pajak', 'rekam_faktur_pajak_list', 1, 'Rekam Faktur', 1, 1, 0, '2019-09-09 07:20:05'),
	(86, 8, 'pajak', 'rekam_faktur_pajak_detail', 1, 'Rekam Faktur Pajak Detail', 99, 1, 0, '2019-09-09 07:20:42'),
	(87, 4, 'transaction', 'ockh_editor', 1, 'OCKH Non PO', 12, 1, NULL, '2019-09-17 01:27:11'),
	(88, 4, 'transaction', 'pre_po_editor', 1, 'Pre PO List', 12, 1, NULL, '2019-09-20 08:59:19'),
	(89, 4, 'transaction', 'pre_po_editor_detail', 1, 'Pre PO Detail', 99, 1, 0, '2019-09-20 09:00:02'),
	(90, 5, 'inventory', 'stok_barang_and_po', 1, 'Stok Barang + PO', 4, 2, 106, '2019-09-23 10:00:18'),
	(91, 5, 'inventory', 'stok_opname_overview_with_before', 1, 'Stok Link Before', 99, 1, 0, '2020-01-02 08:50:17'),
	(92, 5, 'inventory', 'stok_opname_overview', 1, 'SO Overview', 99, 1, 0, '2020-01-03 02:33:01'),
	(93, 8, 'pajak', 'rekam_faktur_email_list', 1, 'Rekam Faktur Pajak Email', 99, 1, NULL, '2020-02-15 03:47:08'),
	(94, 8, 'pajak', 'laporan_faktur_pajak', 1, 'Laporan Faktur Pajak', 3, 1, 0, '2020-02-22 03:12:39'),
	(95, 4, 'transaction', 'pembelian_lain', 1, 'Pembelian Lain2', 1, 2, 105, '2020-02-25 06:43:31'),
	(96, 4, 'transaction', 'pembelian_lain_detail', 1, 'Input Pembelian Lain', 2, 2, 105, '2020-02-25 06:44:01'),
	(97, 4, 'transaction', 'pengeluaran_stok_lain_list', 1, 'Pengeluaran Rupa2', 2, 2, 105, '2020-03-03 05:21:20'),
	(98, 4, 'transaction', 'pengeluaran_stok_lain_list_detail', 1, 'Input Pengeluaran Rupa2', 2, 2, 105, '2020-03-03 05:21:46'),
	(99, 4, 'transaction', 'retur_beli_list', 1, 'Retur Pembelian', 1, 2, 104, '2020-03-20 07:54:26'),
	(100, 4, 'transaction', 'retur_beli_detail', 1, 'Retur Beli Detail', 99, 1, 0, '2020-03-20 07:54:46'),
	(101, 2, 'delegate', 'menu_detail_list', 1, 'Menu Detail', 99, 1, NULL, '2020-04-02 03:21:42'),
	(102, 4, 'transaction', '', 1, 'PEMBELIAN', 1, 3, 0, '2020-04-02 03:24:39'),
	(103, 4, 'transaction', '', 1, 'PENJUALAN', 2, 3, 0, '2020-04-02 03:25:02'),
	(104, 4, 'transaction', '', 1, 'RETUR', 3, 3, 0, '2020-04-02 03:25:31'),
	(105, 4, 'transaction', '', 1, 'LAIN-LAIN', 4, 3, 0, '2020-04-02 03:29:02'),
	(106, 5, 'inventory', '', 1, 'STOK', 1, 3, NULL, '2020-04-02 04:26:30'),
	(107, 5, 'inventory', '', 1, 'MUTASI', 2, 3, 0, '2020-04-02 04:28:12'),
	(108, 5, 'inventory', '', 1, 'LAIN-LAIN', 3, 3, 0, '2020-04-02 04:35:03'),
	(109, 6, 'report', '', 1, 'PEMBELIAN', 1, 3, NULL, '2020-04-02 04:40:22'),
	(110, 6, 'report', '', 1, 'PENJUALAN', 2, 3, 0, '2020-04-02 04:42:20'),
	(111, 6, 'report', '', 1, 'BARANG/STOK', 3, 3, NULL, '2020-04-02 04:42:43'),
	(112, 6, 'report', '', 1, 'PO PEMBELIAN', 0, 3, 0, '2020-04-02 04:43:28'),
	(113, 7, 'finance', '', 1, 'HUTANG', 1, 3, 0, '2020-04-02 04:50:39'),
	(114, 7, 'finance', '', 1, 'PIUTANG', 2, 3, 0, '2020-04-02 04:50:55'),
	(115, 7, 'finance', '', 1, 'MUTASI', 3, 3, 0, '2020-04-02 04:51:21'),
	(116, 7, 'finance', '', 1, 'GIRO MASUK', 4, 3, 0, '2020-04-02 04:57:50'),
	(117, 7, 'finance', 'giro_tolakan_list', 1, 'Giro Tolakan', 1, 2, 116, '2020-04-14 02:11:00'),
	(118, 5, 'inventory', 'stok_barang_ppo_2', 1, 'STOK Barang (PPO)', 5, 2, 106, '2020-11-19 08:03:48'),
	(122, 5, 'inventory', 'stok_opname_detail_2', 1, 'Stok Opname Detail', 99, 99, 0, '2019-01-01 11:29:22'),
	(123, 6, 'report', 'stok_opname_report', 1, 'Laporan Stok Opname', 5, 2, 111, '2021-04-06 10:20:07'),
	(124, 4, 'transaction', 'request_barang', 1, 'PO Request Barang', 2, 2, 125, '2021-06-06 23:34:16'),
	(125, 4, 'transaction', '', 1, 'PO PEMBELIAN', 2, 3, NULL, '2021-06-06 23:34:37'),
	(127, 6, 'report', 'po_request_report', 1, 'Po Request', 2, 2, 112, '2021-07-06 14:23:40'),
	(128, 6, 'report', 'po_request_report_detail', 1, 'PO Request Detail', 99, 99, 0, '2021-07-15 23:46:03'),
	(1000, 3, 'master', 'warna_list', 1, 'Warna', 999, 1, 0, '2018-07-29 19:41:01'),
	(1001, 3, 'master', 'satuan_list', 1, 'Satuan', 999, 1, 0, '2018-07-29 19:41:01'),
	(1002, 3, 'master', 'bank_list', 1, 'Bank', 999, 1, 0, '2018-07-29 19:41:01'),
	(1003, 3, 'master', 'gudang_list', 1, 'Gudang', 999, 1, 0, '2018-07-29 19:41:01'),
	(1004, 3, 'master', 'printer_list', 1, 'Printer', 999, 1, 0, '2018-07-29 19:41:01'),
	(1005, 3, 'master', 'user_list', 1, 'User', 999, 1, 0, '2018-07-29 19:41:01'),
	(3001, 3, 'master', 'pengiriman_list', 1, 'Pengiriman', 999, 1, 0, '2018-07-29 19:41:01');
/*!40000 ALTER TABLE `nd_menu_detail` ENABLE KEYS */;

-- Dumping structure for table favourtdj_system.nd_menu_posisi
DROP TABLE IF EXISTS `nd_menu_posisi`;
CREATE TABLE IF NOT EXISTS `nd_menu_posisi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `posisi_id` int(11) DEFAULT NULL,
  `menu_id` varchar(10000) DEFAULT NULL,
  `menu_detail_id` varchar(10000) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

-- Dumping data for table favourtdj_system.nd_menu_posisi: ~5 rows (approximately)
DELETE FROM `nd_menu_posisi`;
/*!40000 ALTER TABLE `nd_menu_posisi` DISABLE KEYS */;
INSERT INTO `nd_menu_posisi` (`id`, `posisi_id`, `menu_id`, `menu_detail_id`) VALUES
	(1, 1, '??1??2??3??4??5??6??7??8', '??2??1??3??4??5??6??7??8??9??10??11??12??13??14??15??16??18??20??22??23??24??25??26??27??29??28??32???32??33??35??36??38??39??40??41??42??43??44??45??46??47??49??51??50??54??55??57??59??60??61??62??63??65??66??67??71??73??75??76??79??80??82??83??85??87??88??90??72??94??95??96??97??98??99??102??103??104??105??106??107??108??109??110??111??112??113??114??115??116??19??117??118??123??125??124??126??127??2000??3000'),
	(4, 2, '??1??3??4??7??6??5??8', '??2??1??3??4??5??6??7??8??9??10??11??12??13??14??15??16??18??22??23??24??25??26??27??29??28??32???32??33??35??36??38??39??40??41??42??43??44??45??46??47??49??51??50??54??55??57??59??60??61??63??65??66??67??71??73??76??80??82??87??88??90??72??83??85??94??95??96??97??98??102??103??104??105??106??107??108??109??110??111??112??113??114??115??116??117??99??118'),
	(5, 5, '??4??5??6??1??3', '??14??15??16??25??36??35??18??40??65??66??8??103??105??106??107??110??90'),
	(6, 6, '??4??5??7??6??8??3', '??35??26??27??28??29??20??40??12??13??14??15??16??24??25??36??22??23??18??32??33??45??46??54??49??50??51??55??57??62??76??85??83??8??94??102??113??114??115??116??109??110??111??106??107??108??63??103??104??105??97??95??99??117??90'),
	(7, 7, '??4??6??1', '??12??13??24??23??1');
/*!40000 ALTER TABLE `nd_menu_posisi` ENABLE KEYS */;

-- Dumping structure for table favourtdj_system.nd_mutasi_barang
DROP TABLE IF EXISTS `nd_mutasi_barang`;
CREATE TABLE IF NOT EXISTS `nd_mutasi_barang` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tanggal` date DEFAULT NULL,
  `barang_id` int(11) DEFAULT NULL,
  `warna_id` int(11) NOT NULL,
  `gudang_id_before` int(11) NOT NULL,
  `gudang_id_after` int(11) DEFAULT NULL,
  `qty` decimal(15,2) DEFAULT NULL,
  `jumlah_roll` int(4) DEFAULT NULL,
  `status_aktif` int(1) NOT NULL DEFAULT '1',
  `user_id` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table favourtdj_system.nd_mutasi_barang: ~0 rows (approximately)
DELETE FROM `nd_mutasi_barang`;
/*!40000 ALTER TABLE `nd_mutasi_barang` DISABLE KEYS */;
/*!40000 ALTER TABLE `nd_mutasi_barang` ENABLE KEYS */;

-- Dumping structure for table favourtdj_system.nd_mutasi_barang_qty
DROP TABLE IF EXISTS `nd_mutasi_barang_qty`;
CREATE TABLE IF NOT EXISTS `nd_mutasi_barang_qty` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mutasi_barang_id` mediumint(9) DEFAULT NULL,
  `qty` decimal(15,2) DEFAULT NULL,
  `jumlah_roll` smallint(6) DEFAULT NULL,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `mutasi_barang_id` (`mutasi_barang_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table favourtdj_system.nd_mutasi_barang_qty: ~0 rows (approximately)
DELETE FROM `nd_mutasi_barang_qty`;
/*!40000 ALTER TABLE `nd_mutasi_barang_qty` DISABLE KEYS */;
/*!40000 ALTER TABLE `nd_mutasi_barang_qty` ENABLE KEYS */;

-- Dumping structure for table favourtdj_system.nd_note_order
DROP TABLE IF EXISTS `nd_note_order`;
CREATE TABLE IF NOT EXISTS `nd_note_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tipe_customer` int(1) NOT NULL DEFAULT '1',
  `customer_id` int(11) DEFAULT NULL,
  `nama_customer` varchar(100) DEFAULT NULL,
  `contact_person` varchar(200) DEFAULT NULL,
  `contact_info` varchar(100) DEFAULT NULL,
  `catatan` varchar(250) DEFAULT NULL,
  `tanggal_note_order` datetime DEFAULT NULL,
  `tanggal_target` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table favourtdj_system.nd_note_order: ~0 rows (approximately)
DELETE FROM `nd_note_order`;
/*!40000 ALTER TABLE `nd_note_order` DISABLE KEYS */;
/*!40000 ALTER TABLE `nd_note_order` ENABLE KEYS */;

-- Dumping structure for table favourtdj_system.nd_note_order_detail
DROP TABLE IF EXISTS `nd_note_order_detail`;
CREATE TABLE IF NOT EXISTS `nd_note_order_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `note_order_id` int(11) DEFAULT NULL,
  `tipe_barang` int(1) NOT NULL DEFAULT '1',
  `barang_id` int(11) DEFAULT NULL,
  `nama_barang` varchar(100) DEFAULT NULL,
  `warna_id` int(11) DEFAULT NULL,
  `nama_warna` varchar(100) DEFAULT NULL,
  `roll` int(11) DEFAULT NULL,
  `qty` decimal(15,2) DEFAULT NULL,
  `harga` int(11) DEFAULT NULL,
  `status` int(1) NOT NULL DEFAULT '0',
  `done_by` int(11) DEFAULT NULL,
  `done_time` datetime DEFAULT NULL,
  `cancel_note` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table favourtdj_system.nd_note_order_detail: ~0 rows (approximately)
DELETE FROM `nd_note_order_detail`;
/*!40000 ALTER TABLE `nd_note_order_detail` DISABLE KEYS */;
/*!40000 ALTER TABLE `nd_note_order_detail` ENABLE KEYS */;

-- Dumping structure for table favourtdj_system.nd_notifikasi_akunting
DROP TABLE IF EXISTS `nd_notifikasi_akunting`;
CREATE TABLE IF NOT EXISTS `nd_notifikasi_akunting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `read_by` int(11) DEFAULT NULL,
  `read_time` datetime DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `amount` int(11) DEFAULT NULL,
  `keterangan` varchar(10000) DEFAULT NULL,
  `verified_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table favourtdj_system.nd_notifikasi_akunting: ~0 rows (approximately)
DELETE FROM `nd_notifikasi_akunting`;
/*!40000 ALTER TABLE `nd_notifikasi_akunting` DISABLE KEYS */;
/*!40000 ALTER TABLE `nd_notifikasi_akunting` ENABLE KEYS */;

-- Dumping structure for table favourtdj_system.nd_no_faktur_pajak
DROP TABLE IF EXISTS `nd_no_faktur_pajak`;
CREATE TABLE IF NOT EXISTS `nd_no_faktur_pajak` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tanggal` date DEFAULT NULL,
  `tahun_pajak` date DEFAULT NULL,
  `no_fp_awal` varchar(20) DEFAULT NULL,
  `no_fp_akhir` varchar(20) DEFAULT NULL,
  `user_id` smallint(6) DEFAULT NULL,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table favourtdj_system.nd_no_faktur_pajak: ~0 rows (approximately)
DELETE FROM `nd_no_faktur_pajak`;
/*!40000 ALTER TABLE `nd_no_faktur_pajak` DISABLE KEYS */;
/*!40000 ALTER TABLE `nd_no_faktur_pajak` ENABLE KEYS */;

-- Dumping structure for table favourtdj_system.nd_ockh_non_po
DROP TABLE IF EXISTS `nd_ockh_non_po`;
CREATE TABLE IF NOT EXISTS `nd_ockh_non_po` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tanggal` date DEFAULT NULL,
  `supplier_id` smallint(6) DEFAULT NULL,
  `ockh` varchar(20) NOT NULL,
  `harga` mediumint(9) DEFAULT NULL,
  `barang_id` int(11) NOT NULL,
  `user_id` smallint(6) DEFAULT NULL,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table favourtdj_system.nd_ockh_non_po: ~0 rows (approximately)
DELETE FROM `nd_ockh_non_po`;
/*!40000 ALTER TABLE `nd_ockh_non_po` DISABLE KEYS */;
/*!40000 ALTER TABLE `nd_ockh_non_po` ENABLE KEYS */;

-- Dumping structure for table favourtdj_system.nd_ockh_non_po_warna
DROP TABLE IF EXISTS `nd_ockh_non_po_warna`;
CREATE TABLE IF NOT EXISTS `nd_ockh_non_po_warna` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ockh_non_po_id` int(11) DEFAULT NULL,
  `warna_id` smallint(6) NOT NULL,
  `qty` mediumint(9) NOT NULL,
  `user_id` smallint(6) DEFAULT NULL,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table favourtdj_system.nd_ockh_non_po_warna: ~0 rows (approximately)
DELETE FROM `nd_ockh_non_po_warna`;
/*!40000 ALTER TABLE `nd_ockh_non_po_warna` DISABLE KEYS */;
/*!40000 ALTER TABLE `nd_ockh_non_po_warna` ENABLE KEYS */;

-- Dumping structure for table favourtdj_system.nd_pembayaran_hutang
DROP TABLE IF EXISTS `nd_pembayaran_hutang`;
CREATE TABLE IF NOT EXISTS `nd_pembayaran_hutang` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `supplier_id` int(11) NOT NULL,
  `toko_id` int(11) NOT NULL,
  `pembulatan` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `created` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- Dumping data for table favourtdj_system.nd_pembayaran_hutang: ~0 rows (approximately)
DELETE FROM `nd_pembayaran_hutang`;
/*!40000 ALTER TABLE `nd_pembayaran_hutang` DISABLE KEYS */;
INSERT INTO `nd_pembayaran_hutang` (`id`, `supplier_id`, `toko_id`, `pembulatan`, `user_id`, `created`) VALUES
	(1, 3, 1, 0, 1, '2021-08-13 11:03:12');
/*!40000 ALTER TABLE `nd_pembayaran_hutang` ENABLE KEYS */;

-- Dumping structure for table favourtdj_system.nd_pembayaran_hutang_detail
DROP TABLE IF EXISTS `nd_pembayaran_hutang_detail`;
CREATE TABLE IF NOT EXISTS `nd_pembayaran_hutang_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pembayaran_hutang_id` int(11) DEFAULT NULL,
  `pembelian_id` int(11) DEFAULT NULL,
  `amount` int(11) DEFAULT NULL,
  `data_status` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `pembayaran_hutang_id` (`pembayaran_hutang_id`),
  KEY `pembelian_id` (`pembelian_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- Dumping data for table favourtdj_system.nd_pembayaran_hutang_detail: ~0 rows (approximately)
DELETE FROM `nd_pembayaran_hutang_detail`;
/*!40000 ALTER TABLE `nd_pembayaran_hutang_detail` DISABLE KEYS */;
INSERT INTO `nd_pembayaran_hutang_detail` (`id`, `pembayaran_hutang_id`, `pembelian_id`, `amount`, `data_status`) VALUES
	(1, 1, 6, 37800000, 1);
/*!40000 ALTER TABLE `nd_pembayaran_hutang_detail` ENABLE KEYS */;

-- Dumping structure for table favourtdj_system.nd_pembayaran_hutang_nilai
DROP TABLE IF EXISTS `nd_pembayaran_hutang_nilai`;
CREATE TABLE IF NOT EXISTS `nd_pembayaran_hutang_nilai` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pembayaran_hutang_id` int(11) NOT NULL,
  `pembayaran_type_id` tinyint(2) DEFAULT NULL,
  `giro_register_id` mediumint(11) unsigned DEFAULT NULL,
  `bank_list_id` tinyint(1) DEFAULT NULL,
  `nama_bank` varchar(100) DEFAULT NULL,
  `no_rek_bank` varchar(100) DEFAULT NULL,
  `tanggal_transfer` date DEFAULT NULL,
  `no_giro` varchar(50) DEFAULT NULL,
  `tanggal_giro` date DEFAULT NULL,
  `jatuh_tempo` date DEFAULT NULL,
  `nama_penerima` varchar(100) DEFAULT NULL,
  `amount` int(7) NOT NULL DEFAULT '0',
  `keterangan` varchar(100) DEFAULT NULL,
  `user_id` tinyint(2) DEFAULT NULL,
  `created` datetime DEFAULT CURRENT_TIMESTAMP,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `tanggal_transfer` (`tanggal_transfer`),
  KEY `tanggal_giro` (`tanggal_giro`),
  KEY `pembayaran_hutang_id` (`pembayaran_hutang_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

-- Dumping data for table favourtdj_system.nd_pembayaran_hutang_nilai: ~4 rows (approximately)
DELETE FROM `nd_pembayaran_hutang_nilai`;
/*!40000 ALTER TABLE `nd_pembayaran_hutang_nilai` DISABLE KEYS */;
INSERT INTO `nd_pembayaran_hutang_nilai` (`id`, `pembayaran_hutang_id`, `pembayaran_type_id`, `giro_register_id`, `bank_list_id`, `nama_bank`, `no_rek_bank`, `tanggal_transfer`, `no_giro`, `tanggal_giro`, `jatuh_tempo`, `nama_penerima`, `amount`, `keterangan`, `user_id`, `created`, `status`) VALUES
	(1, 1, 2, 1, 1, NULL, NULL, '2021-08-13', '1101', NULL, '2021-08-01', NULL, 800000, 'tes', 1, '2021-08-13 11:22:29', 1),
	(2, 1, 5, 2, 1, NULL, NULL, '2021-08-13', '2201', NULL, '2021-08-31', NULL, 7000000, 'tes', 1, '2021-08-13 11:25:54', 1),
	(3, 1, 1, NULL, 1, 'BRI', '82839884477q', '2021-08-13', NULL, NULL, NULL, NULL, 15000000, 'tes', 1, '2021-08-13 11:29:26', 1),
	(4, 1, 1, NULL, 1, 'UOB', '8827337737737', '2021-08-13', NULL, NULL, NULL, NULL, 5000000, 'tes', 1, '2021-08-13 11:33:11', 1),
	(5, 1, 3, NULL, 1, NULL, NULL, '2021-08-13', NULL, NULL, NULL, NULL, 10000000, '', 1, '2021-08-13 11:41:34', 1);
/*!40000 ALTER TABLE `nd_pembayaran_hutang_nilai` ENABLE KEYS */;

-- Dumping structure for table favourtdj_system.nd_pembayaran_pembelian
DROP TABLE IF EXISTS `nd_pembayaran_pembelian`;
CREATE TABLE IF NOT EXISTS `nd_pembayaran_pembelian` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pembelian_id` int(11) DEFAULT NULL,
  `pembayaran_type_id` int(11) DEFAULT NULL,
  `amount` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table favourtdj_system.nd_pembayaran_pembelian: ~0 rows (approximately)
DELETE FROM `nd_pembayaran_pembelian`;
/*!40000 ALTER TABLE `nd_pembayaran_pembelian` DISABLE KEYS */;
/*!40000 ALTER TABLE `nd_pembayaran_pembelian` ENABLE KEYS */;

-- Dumping structure for table favourtdj_system.nd_pembayaran_pengeluaran_stok_lain
DROP TABLE IF EXISTS `nd_pembayaran_pengeluaran_stok_lain`;
CREATE TABLE IF NOT EXISTS `nd_pembayaran_pengeluaran_stok_lain` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pengeluaran_stok_lain_id` smallint(11) DEFAULT NULL,
  `pembayaran_type_id` int(11) DEFAULT NULL,
  `dp_masuk_id` int(11) DEFAULT NULL,
  `amount` int(11) NOT NULL,
  `keterangan` varchar(150) DEFAULT NULL,
  `user_id` smallint(6) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table favourtdj_system.nd_pembayaran_pengeluaran_stok_lain: ~0 rows (approximately)
DELETE FROM `nd_pembayaran_pengeluaran_stok_lain`;
/*!40000 ALTER TABLE `nd_pembayaran_pengeluaran_stok_lain` DISABLE KEYS */;
/*!40000 ALTER TABLE `nd_pembayaran_pengeluaran_stok_lain` ENABLE KEYS */;

-- Dumping structure for table favourtdj_system.nd_pembayaran_penjualan
DROP TABLE IF EXISTS `nd_pembayaran_penjualan`;
CREATE TABLE IF NOT EXISTS `nd_pembayaran_penjualan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `penjualan_id` int(11) DEFAULT NULL,
  `pembayaran_type_id` int(11) DEFAULT NULL,
  `dp_masuk_id` int(11) DEFAULT NULL,
  `amount` int(11) NOT NULL,
  `keterangan` varchar(150) DEFAULT NULL,
  `user_id` smallint(6) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table favourtdj_system.nd_pembayaran_penjualan: ~0 rows (approximately)
DELETE FROM `nd_pembayaran_penjualan`;
/*!40000 ALTER TABLE `nd_pembayaran_penjualan` DISABLE KEYS */;
/*!40000 ALTER TABLE `nd_pembayaran_penjualan` ENABLE KEYS */;

-- Dumping structure for table favourtdj_system.nd_pembayaran_penjualan_giro
DROP TABLE IF EXISTS `nd_pembayaran_penjualan_giro`;
CREATE TABLE IF NOT EXISTS `nd_pembayaran_penjualan_giro` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `penjualan_id` int(11) NOT NULL,
  `nama_bank` varchar(100) NOT NULL,
  `no_rek_bank` varchar(100) DEFAULT NULL,
  `tanggal_giro` date NOT NULL,
  `jatuh_tempo` date NOT NULL,
  `no_akun` varchar(50) NOT NULL,
  `keterangan` varchar(100) DEFAULT NULL,
  `tanggal_setor` date DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `created` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table favourtdj_system.nd_pembayaran_penjualan_giro: ~0 rows (approximately)
DELETE FROM `nd_pembayaran_penjualan_giro`;
/*!40000 ALTER TABLE `nd_pembayaran_penjualan_giro` DISABLE KEYS */;
/*!40000 ALTER TABLE `nd_pembayaran_penjualan_giro` ENABLE KEYS */;

-- Dumping structure for table favourtdj_system.nd_pembayaran_piutang
DROP TABLE IF EXISTS `nd_pembayaran_piutang`;
CREATE TABLE IF NOT EXISTS `nd_pembayaran_piutang` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) NOT NULL,
  `tanggal_kontra` date DEFAULT NULL,
  `toko_id` int(11) NOT NULL,
  `pembulatan` int(4) NOT NULL,
  `lain2` mediumint(9) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `created` datetime DEFAULT CURRENT_TIMESTAMP,
  `status_aktif` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table favourtdj_system.nd_pembayaran_piutang: ~0 rows (approximately)
DELETE FROM `nd_pembayaran_piutang`;
/*!40000 ALTER TABLE `nd_pembayaran_piutang` DISABLE KEYS */;
/*!40000 ALTER TABLE `nd_pembayaran_piutang` ENABLE KEYS */;

-- Dumping structure for table favourtdj_system.nd_pembayaran_piutang_detail
DROP TABLE IF EXISTS `nd_pembayaran_piutang_detail`;
CREATE TABLE IF NOT EXISTS `nd_pembayaran_piutang_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pembayaran_piutang_id` int(11) DEFAULT NULL,
  `penjualan_id` int(11) DEFAULT NULL,
  `amount` int(11) DEFAULT NULL,
  `data_status` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `penjualan_id` (`penjualan_id`),
  KEY `data_status` (`data_status`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table favourtdj_system.nd_pembayaran_piutang_detail: ~0 rows (approximately)
DELETE FROM `nd_pembayaran_piutang_detail`;
/*!40000 ALTER TABLE `nd_pembayaran_piutang_detail` DISABLE KEYS */;
/*!40000 ALTER TABLE `nd_pembayaran_piutang_detail` ENABLE KEYS */;

-- Dumping structure for table favourtdj_system.nd_pembayaran_piutang_nilai
DROP TABLE IF EXISTS `nd_pembayaran_piutang_nilai`;
CREATE TABLE IF NOT EXISTS `nd_pembayaran_piutang_nilai` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pembayaran_piutang_id` int(11) NOT NULL,
  `pembayaran_type_id` int(2) DEFAULT NULL,
  `dp_masuk_id` int(11) DEFAULT NULL,
  `nama_bank` varchar(100) DEFAULT NULL,
  `no_rek_bank` varchar(100) DEFAULT NULL,
  `tanggal_transfer` date DEFAULT NULL,
  `no_giro` varchar(50) DEFAULT NULL,
  `urutan_giro` int(5) DEFAULT NULL,
  `no_akun_giro` varchar(50) DEFAULT NULL,
  `tanggal_giro` date DEFAULT NULL,
  `jatuh_tempo` date DEFAULT NULL,
  `nama_penerima` varchar(100) DEFAULT NULL,
  `amount` int(7) NOT NULL DEFAULT '0',
  `keterangan` varchar(100) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `created` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table favourtdj_system.nd_pembayaran_piutang_nilai: ~0 rows (approximately)
DELETE FROM `nd_pembayaran_piutang_nilai`;
/*!40000 ALTER TABLE `nd_pembayaran_piutang_nilai` DISABLE KEYS */;
/*!40000 ALTER TABLE `nd_pembayaran_piutang_nilai` ENABLE KEYS */;

-- Dumping structure for table favourtdj_system.nd_pembayaran_piutang_nilai_info
DROP TABLE IF EXISTS `nd_pembayaran_piutang_nilai_info`;
CREATE TABLE IF NOT EXISTS `nd_pembayaran_piutang_nilai_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pembayaran_piutang_nilai_id` int(11) DEFAULT NULL,
  `pembayaran_piutang_detail_id` int(11) DEFAULT NULL,
  `penjualan_id` int(11) DEFAULT NULL,
  `updated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table favourtdj_system.nd_pembayaran_piutang_nilai_info: ~0 rows (approximately)
DELETE FROM `nd_pembayaran_piutang_nilai_info`;
/*!40000 ALTER TABLE `nd_pembayaran_piutang_nilai_info` DISABLE KEYS */;
/*!40000 ALTER TABLE `nd_pembayaran_piutang_nilai_info` ENABLE KEYS */;

-- Dumping structure for table favourtdj_system.nd_pembayaran_retur
DROP TABLE IF EXISTS `nd_pembayaran_retur`;
CREATE TABLE IF NOT EXISTS `nd_pembayaran_retur` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `retur_jual_id` int(11) DEFAULT NULL,
  `pembayaran_type_id` int(2) DEFAULT NULL,
  `amount` int(5) DEFAULT NULL,
  `keterangan` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table favourtdj_system.nd_pembayaran_retur: ~0 rows (approximately)
DELETE FROM `nd_pembayaran_retur`;
/*!40000 ALTER TABLE `nd_pembayaran_retur` DISABLE KEYS */;
/*!40000 ALTER TABLE `nd_pembayaran_retur` ENABLE KEYS */;

-- Dumping structure for table favourtdj_system.nd_pembayaran_type
DROP TABLE IF EXISTS `nd_pembayaran_type`;
CREATE TABLE IF NOT EXISTS `nd_pembayaran_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table favourtdj_system.nd_pembayaran_type: ~0 rows (approximately)
DELETE FROM `nd_pembayaran_type`;
/*!40000 ALTER TABLE `nd_pembayaran_type` DISABLE KEYS */;
/*!40000 ALTER TABLE `nd_pembayaran_type` ENABLE KEYS */;

-- Dumping structure for table favourtdj_system.nd_pembelian
DROP TABLE IF EXISTS `nd_pembelian`;
CREATE TABLE IF NOT EXISTS `nd_pembelian` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `no_nota` int(11) NOT NULL,
  `ockh_info` varchar(100) DEFAULT NULL,
  `no_faktur` varchar(50) DEFAULT NULL,
  `po_pembelian_batch_id` int(11) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `toko_id` tinyint(1) DEFAULT '1',
  `supplier_id` smallint(2) DEFAULT NULL,
  `gudang_id` tinyint(2) DEFAULT NULL,
  `diskon` int(6) DEFAULT NULL,
  `jatuh_tempo` date DEFAULT NULL,
  `keterangan` varchar(100) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `status_aktif` tinyint(1) NOT NULL DEFAULT '1',
  `user_id` tinyint(2) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `cancelled_by` tinyint(2) DEFAULT NULL,
  `cancelled_date` datetime DEFAULT NULL,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `tanggal` (`tanggal`),
  KEY `status_aktif` (`status_aktif`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

-- Dumping data for table favourtdj_system.nd_pembelian: ~6 rows (approximately)
DELETE FROM `nd_pembelian`;
/*!40000 ALTER TABLE `nd_pembelian` DISABLE KEYS */;
INSERT INTO `nd_pembelian` (`id`, `no_nota`, `ockh_info`, `no_faktur`, `po_pembelian_batch_id`, `tanggal`, `toko_id`, `supplier_id`, `gudang_id`, `diskon`, `jatuh_tempo`, `keterangan`, `status`, `status_aktif`, `user_id`, `created_at`, `cancelled_by`, `cancelled_date`, `updated_at`) VALUES
	(1, 1, '888999000', '989898', 1, '2021-08-12', 1, 1, 1, 0, '2021-08-12', '0', 0, 1, 1, '2021-08-12 15:46:09', NULL, NULL, '2021-08-12 15:46:09'),
	(2, 2, NULL, '8889384888', 1, '2021-08-13', 1, 1, 1, 0, '2021-08-13', '0', 0, 1, 1, '2021-08-13 08:35:44', NULL, NULL, '2021-08-13 08:35:44'),
	(3, 3, NULL, '88829388843', 1, '2021-08-13', 1, 1, 1, 0, '2021-08-13', '0', 0, 1, 1, '2021-08-13 08:38:37', NULL, NULL, '2021-08-13 08:38:37'),
	(4, 4, NULL, '888938488', 2, '2021-08-13', 1, 3, 1, 0, '2021-08-13', '0', 0, 1, 1, '2021-08-13 08:39:00', NULL, NULL, '2021-08-13 08:39:00'),
	(5, 5, NULL, 'yyuyuuyyuyuyy', 3, '2021-08-13', 1, 3, 1, 890000, '2021-08-13', 'testing', 0, 1, 1, '2021-08-13 08:39:44', NULL, NULL, '2021-08-13 08:44:45'),
	(6, 6, '', 'AAA-001', 3, '2021-08-13', 1, 3, 1, 0, '2021-08-13', 'tes', 0, 1, 1, '2021-08-13 10:25:24', NULL, NULL, '2021-08-13 10:39:18');
/*!40000 ALTER TABLE `nd_pembelian` ENABLE KEYS */;

-- Dumping structure for table favourtdj_system.nd_pembelian_barang_list
DROP TABLE IF EXISTS `nd_pembelian_barang_list`;
CREATE TABLE IF NOT EXISTS `nd_pembelian_barang_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pembelian_id` int(11) DEFAULT NULL,
  `barang_id` int(11) DEFAULT NULL,
  `warna_id` int(11) DEFAULT NULL,
  `satuan_id` int(11) DEFAULT NULL,
  `qty` float DEFAULT NULL,
  `harga_beli` int(6) DEFAULT NULL,
  `jumlah_roll` float DEFAULT NULL,
  `gudang_id` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table favourtdj_system.nd_pembelian_barang_list: ~0 rows (approximately)
DELETE FROM `nd_pembelian_barang_list`;
/*!40000 ALTER TABLE `nd_pembelian_barang_list` DISABLE KEYS */;
/*!40000 ALTER TABLE `nd_pembelian_barang_list` ENABLE KEYS */;

-- Dumping structure for table favourtdj_system.nd_pembelian_detail
DROP TABLE IF EXISTS `nd_pembelian_detail`;
CREATE TABLE IF NOT EXISTS `nd_pembelian_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pembelian_id` int(11) DEFAULT NULL,
  `ockh` varchar(100) DEFAULT NULL,
  `barang_id` smallint(4) unsigned DEFAULT NULL,
  `warna_id` smallint(4) unsigned DEFAULT NULL,
  `satuan_id` tinyint(2) unsigned DEFAULT NULL,
  `qty` decimal(15,2) DEFAULT NULL,
  `harga_beli` int(11) DEFAULT NULL,
  `jumlah_roll` float DEFAULT NULL,
  `gudang_id` varchar(50) DEFAULT NULL,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `pembelian_id` (`pembelian_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- Dumping data for table favourtdj_system.nd_pembelian_detail: ~2 rows (approximately)
DELETE FROM `nd_pembelian_detail`;
/*!40000 ALTER TABLE `nd_pembelian_detail` DISABLE KEYS */;
INSERT INTO `nd_pembelian_detail` (`id`, `pembelian_id`, `ockh`, `barang_id`, `warna_id`, `satuan_id`, `qty`, `harga_beli`, `jumlah_roll`, `gudang_id`, `updated_at`) VALUES
	(1, 5, 'uuut', 7, 50, NULL, 2.00, 945000, 6, NULL, '2021-08-13 08:46:41'),
	(2, 6, NULL, 7, 50, NULL, 40.00, 945000, 3, NULL, '2021-08-13 10:33:49');
/*!40000 ALTER TABLE `nd_pembelian_detail` ENABLE KEYS */;

-- Dumping structure for table favourtdj_system.nd_pembelian_lain
DROP TABLE IF EXISTS `nd_pembelian_lain`;
CREATE TABLE IF NOT EXISTS `nd_pembelian_lain` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tanggal` date DEFAULT NULL,
  `no_faktur` varchar(20) DEFAULT NULL,
  `supplier_id` tinyint(4) DEFAULT NULL,
  `supplier_lain_text` varchar(100) DEFAULT NULL,
  `toko_id` tinyint(1) DEFAULT '1',
  `user_id` smallint(6) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status_aktif` tinyint(1) DEFAULT '1',
  `cancelled_by` tinyint(1) DEFAULT NULL,
  `canceled_date` datetime DEFAULT NULL,
  `jatuh_tempo` date DEFAULT NULL,
  `keterangan` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table favourtdj_system.nd_pembelian_lain: ~0 rows (approximately)
DELETE FROM `nd_pembelian_lain`;
/*!40000 ALTER TABLE `nd_pembelian_lain` DISABLE KEYS */;
/*!40000 ALTER TABLE `nd_pembelian_lain` ENABLE KEYS */;

-- Dumping structure for table favourtdj_system.nd_pembelian_lain_detail
DROP TABLE IF EXISTS `nd_pembelian_lain_detail`;
CREATE TABLE IF NOT EXISTS `nd_pembelian_lain_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pembelian_lain_id` smallint(6) DEFAULT NULL,
  `keterangan_barang` varchar(500) DEFAULT NULL,
  `qty` smallint(6) DEFAULT NULL,
  `harga_beli` int(11) DEFAULT NULL,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table favourtdj_system.nd_pembelian_lain_detail: ~0 rows (approximately)
DELETE FROM `nd_pembelian_lain_detail`;
/*!40000 ALTER TABLE `nd_pembelian_lain_detail` DISABLE KEYS */;
/*!40000 ALTER TABLE `nd_pembelian_lain_detail` ENABLE KEYS */;

-- Dumping structure for table favourtdj_system.nd_pembelian_qty_detail
DROP TABLE IF EXISTS `nd_pembelian_qty_detail`;
CREATE TABLE IF NOT EXISTS `nd_pembelian_qty_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pembelian_detail_id` mediumint(11) DEFAULT NULL,
  `qty` decimal(10,2) DEFAULT NULL,
  `jumlah_roll` smallint(4) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `pembelian_detail_id` (`pembelian_detail_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

-- Dumping data for table favourtdj_system.nd_pembelian_qty_detail: ~6 rows (approximately)
DELETE FROM `nd_pembelian_qty_detail`;
/*!40000 ALTER TABLE `nd_pembelian_qty_detail` DISABLE KEYS */;
INSERT INTO `nd_pembelian_qty_detail` (`id`, `pembelian_detail_id`, `qty`, `jumlah_roll`) VALUES
	(4, 1, 1.00, 2),
	(5, 1, 2.00, 1),
	(6, 1, 2.00, 1),
	(7, 2, 10.00, 1),
	(8, 2, 20.00, 1),
	(9, 2, 10.00, 1);
/*!40000 ALTER TABLE `nd_pembelian_qty_detail` ENABLE KEYS */;

-- Dumping structure for table favourtdj_system.nd_pengeluaran_stok_lain
DROP TABLE IF EXISTS `nd_pengeluaran_stok_lain`;
CREATE TABLE IF NOT EXISTS `nd_pengeluaran_stok_lain` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tanggal` date DEFAULT NULL,
  `no_faktur` smallint(6) DEFAULT NULL,
  `keterangan` varchar(300) DEFAULT NULL,
  `toko_id` tinyint(4) NOT NULL DEFAULT '1',
  `user_id` smallint(6) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status_aktif` tinyint(4) NOT NULL DEFAULT '1',
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `closed_by` smallint(6) DEFAULT NULL,
  `closed_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table favourtdj_system.nd_pengeluaran_stok_lain: ~0 rows (approximately)
DELETE FROM `nd_pengeluaran_stok_lain`;
/*!40000 ALTER TABLE `nd_pengeluaran_stok_lain` DISABLE KEYS */;
/*!40000 ALTER TABLE `nd_pengeluaran_stok_lain` ENABLE KEYS */;

-- Dumping structure for table favourtdj_system.nd_pengeluaran_stok_lain_detail
DROP TABLE IF EXISTS `nd_pengeluaran_stok_lain_detail`;
CREATE TABLE IF NOT EXISTS `nd_pengeluaran_stok_lain_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pengeluaran_stok_lain_id` smallint(6) DEFAULT NULL,
  `barang_id` smallint(6) DEFAULT NULL,
  `gudang_id` tinyint(4) DEFAULT NULL,
  `warna_id` smallint(6) DEFAULT NULL,
  `harga_jual` mediumint(9) DEFAULT NULL,
  `user_id` smallint(6) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table favourtdj_system.nd_pengeluaran_stok_lain_detail: ~0 rows (approximately)
DELETE FROM `nd_pengeluaran_stok_lain_detail`;
/*!40000 ALTER TABLE `nd_pengeluaran_stok_lain_detail` DISABLE KEYS */;
/*!40000 ALTER TABLE `nd_pengeluaran_stok_lain_detail` ENABLE KEYS */;

-- Dumping structure for table favourtdj_system.nd_pengeluaran_stok_lain_qty_detail
DROP TABLE IF EXISTS `nd_pengeluaran_stok_lain_qty_detail`;
CREATE TABLE IF NOT EXISTS `nd_pengeluaran_stok_lain_qty_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pengeluaran_stok_lain_detail_id` smallint(6) DEFAULT NULL,
  `qty` smallint(6) DEFAULT NULL,
  `jumlah_roll` smallint(6) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table favourtdj_system.nd_pengeluaran_stok_lain_qty_detail: ~0 rows (approximately)
DELETE FROM `nd_pengeluaran_stok_lain_qty_detail`;
/*!40000 ALTER TABLE `nd_pengeluaran_stok_lain_qty_detail` DISABLE KEYS */;
/*!40000 ALTER TABLE `nd_pengeluaran_stok_lain_qty_detail` ENABLE KEYS */;

-- Dumping structure for table favourtdj_system.nd_penjualan
DROP TABLE IF EXISTS `nd_penjualan`;
CREATE TABLE IF NOT EXISTS `nd_penjualan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `toko_id` tinyint(4) NOT NULL DEFAULT '1',
  `penjualan_type_id` tinyint(1) NOT NULL DEFAULT '3',
  `no_faktur` smallint(6) DEFAULT NULL,
  `po_number` varchar(50) NOT NULL,
  `tanggal` date DEFAULT NULL,
  `customer_id` smallint(6) DEFAULT NULL,
  `gudang_id` tinyint(1) DEFAULT NULL,
  `diskon` mediumint(6) DEFAULT '0',
  `jatuh_tempo` date DEFAULT NULL,
  `ongkos_kirim` mediumint(11) NOT NULL DEFAULT '0',
  `keterangan` varchar(100) DEFAULT NULL,
  `nama_keterangan` varchar(200) NOT NULL,
  `alamat_keterangan` varchar(200) NOT NULL,
  `status_ambil` varchar(50) NOT NULL DEFAULT 'Diambil Semua',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `status_aktif` tinyint(1) NOT NULL DEFAULT '1',
  `closed_by` tinyint(4) NOT NULL,
  `closed_date` datetime DEFAULT NULL,
  `user_id` tinyint(4) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `revisi` tinyint(3) NOT NULL DEFAULT '0',
  `fp_status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `tanggal` (`tanggal`),
  KEY `penjualan_type_id` (`penjualan_type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- Dumping data for table favourtdj_system.nd_penjualan: ~0 rows (approximately)
DELETE FROM `nd_penjualan`;
/*!40000 ALTER TABLE `nd_penjualan` DISABLE KEYS */;
INSERT INTO `nd_penjualan` (`id`, `toko_id`, `penjualan_type_id`, `no_faktur`, `po_number`, `tanggal`, `customer_id`, `gudang_id`, `diskon`, `jatuh_tempo`, `ongkos_kirim`, `keterangan`, `nama_keterangan`, `alamat_keterangan`, `status_ambil`, `status`, `status_aktif`, `closed_by`, `closed_date`, `user_id`, `created_at`, `revisi`, `fp_status`) VALUES
	(1, 1, 1, NULL, 'sdd', '2021-08-13', 1, NULL, 0, '2021-08-13', 0, NULL, '', '', 'Diambil Semua', 1, 1, 0, NULL, 1, '2021-08-13 16:06:24', 0, 1);
/*!40000 ALTER TABLE `nd_penjualan` ENABLE KEYS */;

-- Dumping structure for table favourtdj_system.nd_penjualan_detail
DROP TABLE IF EXISTS `nd_penjualan_detail`;
CREATE TABLE IF NOT EXISTS `nd_penjualan_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `penjualan_id` smallint(11) DEFAULT NULL,
  `barang_id` smallint(11) DEFAULT NULL,
  `warna_id` smallint(11) DEFAULT NULL,
  `satuan_id` tinyint(4) DEFAULT NULL,
  `harga_jual` int(6) DEFAULT NULL,
  `gudang_id` varchar(50) DEFAULT NULL,
  `subqty` decimal(10,2) DEFAULT NULL,
  `subjumlah_roll` smallint(6) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `penjualan_id` (`penjualan_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table favourtdj_system.nd_penjualan_detail: ~0 rows (approximately)
DELETE FROM `nd_penjualan_detail`;
/*!40000 ALTER TABLE `nd_penjualan_detail` DISABLE KEYS */;
/*!40000 ALTER TABLE `nd_penjualan_detail` ENABLE KEYS */;

-- Dumping structure for table favourtdj_system.nd_penjualan_posisi_barang
DROP TABLE IF EXISTS `nd_penjualan_posisi_barang`;
CREATE TABLE IF NOT EXISTS `nd_penjualan_posisi_barang` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `penjualan_id` int(11) DEFAULT NULL,
  `tipe_ambil_barang_id` tinyint(4) DEFAULT NULL,
  `tanggal_pengambilan` date DEFAULT NULL,
  `alamat_pengiriman` varchar(500) DEFAULT '',
  `user_id` tinyint(4) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `closed_by` tinyint(4) DEFAULT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table favourtdj_system.nd_penjualan_posisi_barang: ~0 rows (approximately)
DELETE FROM `nd_penjualan_posisi_barang`;
/*!40000 ALTER TABLE `nd_penjualan_posisi_barang` DISABLE KEYS */;
/*!40000 ALTER TABLE `nd_penjualan_posisi_barang` ENABLE KEYS */;

-- Dumping structure for table favourtdj_system.nd_penjualan_qty_detail
DROP TABLE IF EXISTS `nd_penjualan_qty_detail`;
CREATE TABLE IF NOT EXISTS `nd_penjualan_qty_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `penjualan_detail_id` mediumint(11) DEFAULT NULL,
  `qty` decimal(6,2) DEFAULT NULL,
  `jumlah_roll` smallint(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `penjualan_detail_id` (`penjualan_detail_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table favourtdj_system.nd_penjualan_qty_detail: ~0 rows (approximately)
DELETE FROM `nd_penjualan_qty_detail`;
/*!40000 ALTER TABLE `nd_penjualan_qty_detail` DISABLE KEYS */;
/*!40000 ALTER TABLE `nd_penjualan_qty_detail` ENABLE KEYS */;

-- Dumping structure for table favourtdj_system.nd_penjualan_type
DROP TABLE IF EXISTS `nd_penjualan_type`;
CREATE TABLE IF NOT EXISTS `nd_penjualan_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `text` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- Dumping data for table favourtdj_system.nd_penjualan_type: ~3 rows (approximately)
DELETE FROM `nd_penjualan_type`;
/*!40000 ALTER TABLE `nd_penjualan_type` DISABLE KEYS */;
INSERT INTO `nd_penjualan_type` (`id`, `text`) VALUES
	(1, 'Cash Pelanggan'),
	(2, 'Kredit Pelanggan'),
	(3, 'Cash / Non Pelanggan');
/*!40000 ALTER TABLE `nd_penjualan_type` ENABLE KEYS */;

-- Dumping structure for table favourtdj_system.nd_penyesuaian_stok
DROP TABLE IF EXISTS `nd_penyesuaian_stok`;
CREATE TABLE IF NOT EXISTS `nd_penyesuaian_stok` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tipe_transaksi` tinyint(2) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `gudang_id` tinyint(2) DEFAULT NULL,
  `barang_id` smallint(4) unsigned DEFAULT NULL,
  `warna_id` smallint(4) unsigned DEFAULT NULL,
  `qty` decimal(15,2) DEFAULT NULL,
  `jumlah_roll` smallint(4) NOT NULL,
  `keterangan` varchar(300) DEFAULT NULL,
  `user_id` tinyint(2) unsigned NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table favourtdj_system.nd_penyesuaian_stok: ~0 rows (approximately)
DELETE FROM `nd_penyesuaian_stok`;
/*!40000 ALTER TABLE `nd_penyesuaian_stok` DISABLE KEYS */;
/*!40000 ALTER TABLE `nd_penyesuaian_stok` ENABLE KEYS */;

-- Dumping structure for table favourtdj_system.nd_penyesuaian_stok_qty
DROP TABLE IF EXISTS `nd_penyesuaian_stok_qty`;
CREATE TABLE IF NOT EXISTS `nd_penyesuaian_stok_qty` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `penyesuaian_stok_id` int(11) DEFAULT NULL,
  `qty` decimal(10,2) DEFAULT NULL,
  `jumlah_roll` smallint(6) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table favourtdj_system.nd_penyesuaian_stok_qty: ~0 rows (approximately)
DELETE FROM `nd_penyesuaian_stok_qty`;
/*!40000 ALTER TABLE `nd_penyesuaian_stok_qty` DISABLE KEYS */;
/*!40000 ALTER TABLE `nd_penyesuaian_stok_qty` ENABLE KEYS */;

-- Dumping structure for table favourtdj_system.nd_penyesuaian_stok_split
DROP TABLE IF EXISTS `nd_penyesuaian_stok_split`;
CREATE TABLE IF NOT EXISTS `nd_penyesuaian_stok_split` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `penyesuaian_stok_id` mediumint(9) DEFAULT NULL,
  `qty` decimal(10,2) DEFAULT NULL,
  `jumlah_roll` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table favourtdj_system.nd_penyesuaian_stok_split: ~0 rows (approximately)
DELETE FROM `nd_penyesuaian_stok_split`;
/*!40000 ALTER TABLE `nd_penyesuaian_stok_split` DISABLE KEYS */;
/*!40000 ALTER TABLE `nd_penyesuaian_stok_split` ENABLE KEYS */;

-- Dumping structure for table favourtdj_system.nd_piutang_awal
DROP TABLE IF EXISTS `nd_piutang_awal`;
CREATE TABLE IF NOT EXISTS `nd_piutang_awal` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `toko_id` int(4) NOT NULL DEFAULT '1',
  `customer_id` int(11) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `no_faktur` varchar(40) DEFAULT NULL,
  `amount` int(11) DEFAULT NULL,
  `jatuh_tempo` date NOT NULL,
  `jumlah_roll` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table favourtdj_system.nd_piutang_awal: ~0 rows (approximately)
DELETE FROM `nd_piutang_awal`;
/*!40000 ALTER TABLE `nd_piutang_awal` DISABLE KEYS */;
/*!40000 ALTER TABLE `nd_piutang_awal` ENABLE KEYS */;

-- Dumping structure for table favourtdj_system.nd_posisi
DROP TABLE IF EXISTS `nd_posisi`;
CREATE TABLE IF NOT EXISTS `nd_posisi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

-- Dumping data for table favourtdj_system.nd_posisi: ~4 rows (approximately)
DELETE FROM `nd_posisi`;
/*!40000 ALTER TABLE `nd_posisi` DISABLE KEYS */;
INSERT INTO `nd_posisi` (`id`, `name`) VALUES
	(1, 'Super Admin'),
	(2, 'Admin'),
	(5, 'Penjualan'),
	(6, 'Akunting');
/*!40000 ALTER TABLE `nd_posisi` ENABLE KEYS */;

-- Dumping structure for table favourtdj_system.nd_po_pembelian
DROP TABLE IF EXISTS `nd_po_pembelian`;
CREATE TABLE IF NOT EXISTS `nd_po_pembelian` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tanggal` date DEFAULT NULL,
  `po_number` int(11) DEFAULT NULL,
  `toko_id` int(5) DEFAULT '1',
  `supplier_id` int(11) DEFAULT NULL,
  `up_person` varchar(200) DEFAULT NULL,
  `sales_contract` varchar(100) DEFAULT NULL,
  `catatan` varchar(2000) DEFAULT NULL,
  `po_status` int(1) DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `status_aktif` int(1) DEFAULT '1',
  `cancelled_by` smallint(6) DEFAULT NULL,
  `cancelled_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

-- Dumping data for table favourtdj_system.nd_po_pembelian: ~7 rows (approximately)
DELETE FROM `nd_po_pembelian`;
/*!40000 ALTER TABLE `nd_po_pembelian` DISABLE KEYS */;
INSERT INTO `nd_po_pembelian` (`id`, `tanggal`, `po_number`, `toko_id`, `supplier_id`, `up_person`, `sales_contract`, `catatan`, `po_status`, `created`, `user_id`, `status_aktif`, `cancelled_by`, `cancelled_date`) VALUES
	(1, '2021-08-11', 2, 3, 1, 'andika', 'asdasdasd', NULL, 0, '2021-08-11 16:25:23', 1, 1, NULL, NULL),
	(2, '2021-08-11', NULL, 3, 1, '', NULL, '', 1, '2021-08-11 16:32:06', 1, 0, 1, '2021-08-11 21:25:13'),
	(3, '2021-08-11', NULL, 3, 1, '', NULL, NULL, 1, '2021-08-11 16:32:11', 1, 1, NULL, NULL),
	(4, '2021-08-12', NULL, 3, 1, 'andika', 'oke pisan', NULL, 1, '2021-08-12 10:07:41', 1, 1, NULL, NULL),
	(5, '2021-08-13', 1, 1, 3, 'asdasdasd', 'asdasdasd', NULL, 1, '2021-08-12 10:08:27', 1, 1, NULL, NULL),
	(6, '2021-08-13', 3, 1, 1, 'JOKO WIDODO', 'JK001', NULL, 0, '2021-08-13 09:09:39', 1, 1, NULL, NULL),
	(7, '2021-08-19', NULL, 1, 1, 'andika', NULL, NULL, 1, '2021-08-19 15:02:11', 1, 1, NULL, NULL),
	(8, '2021-08-21', NULL, 1, 1, '', NULL, NULL, 1, '2021-08-21 06:50:35', 1, 1, NULL, NULL);
/*!40000 ALTER TABLE `nd_po_pembelian` ENABLE KEYS */;

-- Dumping structure for table favourtdj_system.nd_po_pembelian_batch
DROP TABLE IF EXISTS `nd_po_pembelian_batch`;
CREATE TABLE IF NOT EXISTS `nd_po_pembelian_batch` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tanggal` date DEFAULT NULL,
  `po_pembelian_id` int(11) DEFAULT NULL,
  `batch` tinyint(3) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `revisi` tinyint(4) NOT NULL DEFAULT '1',
  `revisi_by` tinyint(4) DEFAULT NULL,
  `revisi_ori_id` mediumint(9) DEFAULT NULL,
  `revisi_date` date DEFAULT NULL,
  `updated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

-- Dumping data for table favourtdj_system.nd_po_pembelian_batch: ~5 rows (approximately)
DELETE FROM `nd_po_pembelian_batch`;
/*!40000 ALTER TABLE `nd_po_pembelian_batch` DISABLE KEYS */;
INSERT INTO `nd_po_pembelian_batch` (`id`, `tanggal`, `po_pembelian_id`, `batch`, `status`, `revisi`, `revisi_by`, `revisi_ori_id`, `revisi_date`, `updated`) VALUES
	(1, '2021-08-12', 1, 1, 1, 1, NULL, NULL, NULL, '2021-08-12 10:27:54'),
	(2, '2021-08-12', 5, 1, 1, 1, NULL, NULL, NULL, '2021-08-12 13:10:48'),
	(3, '2021-08-12', 5, 2, 1, 1, NULL, NULL, NULL, '2021-08-12 13:10:50'),
	(4, '2021-08-13', 6, 1, 0, 1, 1, NULL, '2021-08-13', '2021-08-13 10:15:00'),
	(5, '2021-08-13', 6, 1, 2, 2, NULL, 4, NULL, '2021-08-13 10:20:29');
/*!40000 ALTER TABLE `nd_po_pembelian_batch` ENABLE KEYS */;

-- Dumping structure for table favourtdj_system.nd_po_pembelian_before_qty
DROP TABLE IF EXISTS `nd_po_pembelian_before_qty`;
CREATE TABLE IF NOT EXISTS `nd_po_pembelian_before_qty` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `po_pembelian_id` smallint(9) DEFAULT NULL,
  `po_pembelian_batch_id` smallint(6) NOT NULL,
  `po_pembelian_warna_id` smallint(6) DEFAULT NULL,
  `qty` mediumint(9) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table favourtdj_system.nd_po_pembelian_before_qty: ~0 rows (approximately)
DELETE FROM `nd_po_pembelian_before_qty`;
/*!40000 ALTER TABLE `nd_po_pembelian_before_qty` DISABLE KEYS */;
/*!40000 ALTER TABLE `nd_po_pembelian_before_qty` ENABLE KEYS */;

-- Dumping structure for table favourtdj_system.nd_po_pembelian_detail
DROP TABLE IF EXISTS `nd_po_pembelian_detail`;
CREATE TABLE IF NOT EXISTS `nd_po_pembelian_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `po_pembelian_id` int(11) DEFAULT NULL,
  `nama_tercetak` varchar(500) DEFAULT NULL,
  `barang_id` int(11) DEFAULT NULL,
  `harga` int(6) DEFAULT NULL,
  `qty` int(11) DEFAULT NULL,
  `jumlah_roll` int(11) DEFAULT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

-- Dumping data for table favourtdj_system.nd_po_pembelian_detail: ~4 rows (approximately)
DELETE FROM `nd_po_pembelian_detail`;
/*!40000 ALTER TABLE `nd_po_pembelian_detail` DISABLE KEYS */;
INSERT INTO `nd_po_pembelian_detail` (`id`, `po_pembelian_id`, `nama_tercetak`, `barang_id`, `harga`, `qty`, `jumlah_roll`, `created`) VALUES
	(1, 1, '', 5, 180000, 2, NULL, '2021-08-11 16:25:37'),
	(2, 5, '', 7, 945000, 1, NULL, '2021-08-12 10:13:33'),
	(3, 5, '', 7, 945000, 2, NULL, '2021-08-12 10:13:40'),
	(4, 6, '', 7, 945000, 100, NULL, '2021-08-13 09:14:23');
/*!40000 ALTER TABLE `nd_po_pembelian_detail` ENABLE KEYS */;

-- Dumping structure for table favourtdj_system.nd_po_pembelian_warna
DROP TABLE IF EXISTS `nd_po_pembelian_warna`;
CREATE TABLE IF NOT EXISTS `nd_po_pembelian_warna` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `po_pembelian_detail_id` int(11) DEFAULT NULL,
  `po_pembelian_batch_id` int(11) DEFAULT NULL,
  `tipe_barang` smallint(6) NOT NULL DEFAULT '1',
  `barang_id_baru` int(11) DEFAULT NULL,
  `barang_id_baru_rename` mediumint(9) DEFAULT NULL,
  `warna_id` int(11) DEFAULT NULL,
  `harga_baru` int(11) DEFAULT NULL,
  `qty` int(11) DEFAULT NULL,
  `OCKH` varchar(500) DEFAULT NULL,
  `locked_date` date DEFAULT NULL,
  `locked_by` smallint(6) DEFAULT NULL,
  `locked_keterangan` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

-- Dumping data for table favourtdj_system.nd_po_pembelian_warna: ~9 rows (approximately)
DELETE FROM `nd_po_pembelian_warna`;
/*!40000 ALTER TABLE `nd_po_pembelian_warna` DISABLE KEYS */;
INSERT INTO `nd_po_pembelian_warna` (`id`, `po_pembelian_detail_id`, `po_pembelian_batch_id`, `tipe_barang`, `barang_id_baru`, `barang_id_baru_rename`, `warna_id`, `harga_baru`, `qty`, `OCKH`, `locked_date`, `locked_by`, `locked_keterangan`) VALUES
	(1, 2, 3, 1, NULL, NULL, 50, 945000, 33, '', NULL, NULL, NULL),
	(2, 4, 4, 1, NULL, NULL, 50, 945000, 10, '', NULL, NULL, NULL),
	(3, 4, 4, 2, 8, NULL, 79, 945000, 20, '', NULL, NULL, NULL),
	(4, 4, 4, 3, 8, NULL, 79, 945000, 30, '', NULL, NULL, NULL),
	(6, 4, 4, 4, 6, 8, 62, 945000, 30, '', NULL, NULL, NULL),
	(7, 4, 5, 1, NULL, NULL, 50, 945000, 10, '', NULL, NULL, NULL),
	(8, 4, 5, 2, 8, NULL, 79, 945000, 20, '', NULL, NULL, NULL),
	(9, 4, 5, 3, 8, NULL, 79, 945000, 30, '', NULL, NULL, NULL),
	(10, 4, 5, 4, 6, NULL, 62, 945000, 30, '', NULL, NULL, NULL);
/*!40000 ALTER TABLE `nd_po_pembelian_warna` ENABLE KEYS */;

-- Dumping structure for table favourtdj_system.nd_ppo_lock
DROP TABLE IF EXISTS `nd_ppo_lock`;
CREATE TABLE IF NOT EXISTS `nd_ppo_lock` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tanggal` date DEFAULT NULL,
  `barang_id` smallint(5) unsigned DEFAULT NULL,
  `po_pembelian_batch_id_aktif` varchar(300) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `locked_by` tinyint(3) unsigned DEFAULT NULL,
  `downloaded` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table favourtdj_system.nd_ppo_lock: ~0 rows (approximately)
DELETE FROM `nd_ppo_lock`;
/*!40000 ALTER TABLE `nd_ppo_lock` DISABLE KEYS */;
/*!40000 ALTER TABLE `nd_ppo_lock` ENABLE KEYS */;

-- Dumping structure for table favourtdj_system.nd_ppo_lock_detail
DROP TABLE IF EXISTS `nd_ppo_lock_detail`;
CREATE TABLE IF NOT EXISTS `nd_ppo_lock_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ppo_lock_id` smallint(5) unsigned DEFAULT NULL,
  `warna_id` smallint(5) unsigned DEFAULT NULL,
  `qty` mediumint(5) unsigned DEFAULT NULL,
  `user_id` tinyint(3) unsigned DEFAULT NULL,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=239 DEFAULT CHARSET=latin1;

-- Dumping data for table favourtdj_system.nd_ppo_lock_detail: ~218 rows (approximately)
DELETE FROM `nd_ppo_lock_detail`;
/*!40000 ALTER TABLE `nd_ppo_lock_detail` DISABLE KEYS */;
INSERT INTO `nd_ppo_lock_detail` (`id`, `ppo_lock_id`, `warna_id`, `qty`, `user_id`, `updated_at`) VALUES
	(1, 1, 79, 5000, 9, '2020-12-16 04:22:37'),
	(2, 1, 16, 5000, 9, '2020-12-16 04:22:37'),
	(3, 1, 45, 5000, 9, '2020-12-16 04:22:37'),
	(4, 1, 54, 5000, 9, '2020-12-16 04:22:37'),
	(5, 1, 83, 0, 9, '2020-12-16 04:22:37'),
	(6, 1, 46, 5000, 9, '2020-12-16 04:22:37'),
	(7, 1, 77, 2500, 9, '2020-12-16 04:22:37'),
	(8, 1, 32, 5000, 9, '2020-12-16 04:22:37'),
	(9, 1, 13, 2500, 9, '2020-12-16 04:22:37'),
	(10, 1, 78, 2500, 9, '2020-12-16 04:22:37'),
	(11, 1, 15, 5000, 9, '2020-12-16 04:22:37'),
	(12, 1, 71, 5000, 9, '2020-12-16 04:22:37'),
	(33, 6, 50, 10000, 9, '2020-12-16 04:57:28'),
	(34, 6, 12, 5000, 9, '2020-12-16 04:57:28'),
	(35, 6, 3, 175000, 9, '2020-12-16 04:57:28'),
	(36, 6, 2, 2500, 9, '2020-12-16 04:57:28'),
	(37, 6, 41, 5000, 9, '2020-12-16 04:57:28'),
	(38, 7, 66, 2500, 9, '2020-12-16 05:01:12'),
	(39, 7, 3, 15000, 9, '2020-12-16 05:01:12'),
	(40, 8, 62, 5000, 9, '2020-12-16 05:45:35'),
	(41, 8, 12, 12500, 9, '2020-12-16 05:45:35'),
	(42, 8, 3, 30000, 9, '2020-12-16 05:45:35'),
	(43, 8, 6, 7500, 9, '2020-12-16 05:45:35'),
	(44, 8, 4, 5000, 9, '2020-12-16 05:45:35'),
	(45, 8, 41, 5000, 9, '2020-12-16 05:45:35'),
	(46, 8, 42, 7500, 9, '2020-12-16 05:45:35'),
	(47, 9, 54, 2500, 9, '2020-12-16 05:52:34'),
	(48, 9, 12, 2500, 9, '2020-12-16 05:52:34'),
	(49, 9, 3, 15000, 9, '2020-12-16 05:52:34'),
	(50, 9, 41, 2500, 9, '2020-12-16 05:52:34'),
	(51, 1, 30, 2500, 1, '2020-12-16 07:45:04'),
	(52, 1, 89, 5000, 1, '2020-12-16 07:45:04'),
	(53, 10, 79, 5000, 9, '2021-03-03 08:00:56'),
	(54, 10, 16, 10000, 9, '2021-03-03 08:00:56'),
	(55, 10, 45, 10000, 9, '2021-03-03 08:00:56'),
	(56, 10, 54, 10000, 9, '2021-03-03 08:00:56'),
	(57, 10, 83, 5000, 9, '2021-03-03 08:00:56'),
	(58, 10, 46, 12500, 9, '2021-03-03 08:00:56'),
	(59, 10, 77, 5000, 9, '2021-03-03 08:00:56'),
	(60, 10, 32, 5000, 9, '2021-03-03 08:00:56'),
	(61, 10, 13, 15000, 9, '2021-03-03 08:00:56'),
	(62, 10, 78, 0, 9, '2021-03-03 08:00:56'),
	(63, 10, 15, 5000, 9, '2021-03-03 08:00:56'),
	(64, 10, 71, 10000, 9, '2021-03-03 08:00:56'),
	(65, 10, 30, 5000, 9, '2021-03-03 08:00:56'),
	(66, 10, 89, 0, 9, '2021-03-03 08:00:56'),
	(67, 10, 36, 10000, 9, '2021-03-03 08:00:56'),
	(68, 10, 3, 15000, 9, '2021-03-03 08:00:56'),
	(69, 10, 14, 7500, 9, '2021-03-03 08:00:56'),
	(70, 10, 31, 10000, 9, '2021-03-03 08:00:56'),
	(71, 10, 69, 0, 9, '2021-03-03 08:00:56'),
	(72, 10, 9, 12500, 9, '2021-03-03 08:00:56'),
	(73, 10, 41, 0, 9, '2021-03-03 08:00:56'),
	(74, 10, 58, 2500, 9, '2021-03-03 08:00:56'),
	(75, 10, 7, 5000, 9, '2021-03-03 08:00:56'),
	(76, 10, 67, 2500, 9, '2021-03-03 08:00:56'),
	(77, 10, 44, 2500, 9, '2021-03-03 08:00:56'),
	(78, 10, 17, 2500, 9, '2021-03-03 08:00:56'),
	(79, 10, 42, 15000, 9, '2021-03-03 08:00:56'),
	(80, 10, 33, 2500, 9, '2021-03-03 08:00:56'),
	(81, 10, 43, 5000, 9, '2021-03-03 08:00:56'),
	(82, 11, 56, 5000, 9, '2021-03-23 03:53:52'),
	(83, 11, 9, 5000, 9, '2021-03-23 07:18:38'),
	(84, 11, 7, 12500, 9, '2021-03-23 07:18:44'),
	(85, 11, 5, 5000, 9, '2021-03-23 03:53:52'),
	(86, 11, 15, 5000, 9, '2021-03-23 03:53:52'),
	(87, 11, 42, 2500, 9, '2021-03-23 03:53:52'),
	(88, 11, 13, 2500, 9, '2021-03-23 03:53:52'),
	(89, 11, 87, 2500, 9, '2021-03-23 03:53:52'),
	(90, 11, 93, 2500, 9, '2021-03-23 03:53:52'),
	(91, 11, 92, 2500, 9, '2021-03-23 03:53:52'),
	(92, 11, 70, 2500, 9, '2021-03-23 03:53:52'),
	(93, 11, 90, 2500, 9, '2021-03-23 03:53:52'),
	(94, 12, 54, 5000, 9, '2021-03-23 04:24:34'),
	(95, 12, 12, 0, 9, '2021-03-23 04:24:34'),
	(96, 12, 3, 5000, 9, '2021-03-23 04:24:34'),
	(97, 12, 41, 2500, 9, '2021-03-23 04:24:34'),
	(98, 12, 51, 5000, 9, '2021-03-23 07:31:53'),
	(99, 12, 61, 5000, 9, '2021-03-23 04:24:34'),
	(100, 12, 6, 10000, 9, '2021-03-23 07:30:36'),
	(101, 12, 4, 10000, 9, '2021-03-23 07:29:27'),
	(102, 12, 63, 0, 9, '2021-03-23 07:27:32'),
	(103, 12, 64, 2500, 9, '2021-03-23 04:24:34'),
	(104, 12, 9, 10000, 9, '2021-03-23 07:30:02'),
	(105, 13, 50, 0, 9, '2021-03-23 07:36:37'),
	(106, 13, 12, 0, 9, '2021-03-23 06:12:44'),
	(107, 13, 3, 150000, 9, '2021-03-23 07:38:46'),
	(108, 13, 2, 0, 9, '2021-03-23 06:12:44'),
	(109, 13, 41, 10000, 9, '2021-03-23 06:12:44'),
	(110, 13, 54, 2500, 9, '2021-03-23 06:12:44'),
	(111, 13, 22, 2500, 9, '2021-03-23 06:12:44'),
	(112, 13, 9, 30000, 9, '2021-03-23 06:12:44'),
	(113, 13, 60, 5000, 9, '2021-03-23 06:12:44'),
	(114, 14, 14, 0, 9, '2021-03-23 07:42:41'),
	(115, 14, 48, 0, 9, '2021-03-23 07:43:07'),
	(116, 14, 7, 10000, 9, '2021-03-23 06:23:42'),
	(117, 14, 15, 2500, 9, '2021-03-23 06:23:42'),
	(118, 15, 62, 2500, 9, '2021-03-23 07:05:55'),
	(119, 15, 12, 0, 9, '2021-03-23 06:43:01'),
	(120, 15, 3, 0, 9, '2021-03-23 06:43:01'),
	(121, 15, 6, 15000, 9, '2021-03-23 06:43:01'),
	(122, 15, 4, 40000, 9, '2021-03-23 07:08:45'),
	(123, 15, 41, 7500, 9, '2021-03-23 07:09:16'),
	(124, 15, 42, 0, 9, '2021-03-23 07:09:25'),
	(125, 15, 22, 12500, 9, '2021-03-23 06:43:01'),
	(126, 15, 9, 17500, 9, '2021-03-23 06:43:01'),
	(127, 15, 7, 20000, 9, '2021-03-23 06:43:01'),
	(128, 15, 15, 5000, 9, '2021-03-23 07:09:30'),
	(129, 15, 51, 7500, 9, '2021-03-23 07:06:02'),
	(130, 16, 62, 2500, 9, '2021-06-14 13:54:51'),
	(131, 16, 12, 17500, 9, '2021-06-14 13:54:51'),
	(132, 16, 3, 50000, 9, '2021-06-14 13:54:51'),
	(133, 16, 6, 20000, 9, '2021-06-14 13:54:51'),
	(134, 16, 4, 20000, 9, '2021-06-14 13:54:51'),
	(135, 16, 41, 2500, 9, '2021-06-14 13:54:51'),
	(136, 16, 42, 0, 9, '2021-06-14 13:54:51'),
	(137, 16, 22, 20000, 9, '2021-06-14 13:54:51'),
	(138, 16, 9, 5000, 9, '2021-06-14 13:54:51'),
	(139, 16, 7, 7500, 9, '2021-06-14 13:54:51'),
	(140, 16, 15, 12500, 9, '2021-06-14 13:54:51'),
	(141, 16, 54, 2500, 9, '2021-06-14 13:54:51'),
	(142, 16, 47, 7500, 9, '2021-06-14 13:54:51'),
	(143, 16, 13, 0, 9, '2021-06-14 13:54:51'),
	(144, 17, 50, 0, 9, '2021-06-14 15:10:55'),
	(145, 17, 12, 0, 9, '2021-06-14 15:10:55'),
	(146, 17, 3, 10000, 9, '2021-06-14 15:10:55'),
	(147, 17, 2, 0, 9, '2021-06-14 15:10:55'),
	(148, 17, 41, 0, 9, '2021-06-14 15:10:55'),
	(149, 17, 54, 0, 9, '2021-06-14 15:10:55'),
	(150, 17, 22, 0, 9, '2021-06-14 15:10:55'),
	(151, 17, 9, 0, 9, '2021-06-14 15:10:55'),
	(152, 17, 60, 0, 9, '2021-06-14 15:10:55'),
	(153, 17, 4, 22500, 9, '2021-06-14 15:10:55'),
	(154, 17, 7, 35000, 9, '2021-06-14 15:10:55'),
	(155, 17, 16, 2500, 9, '2021-06-14 15:10:55'),
	(156, 18, 79, 20000, 9, '2021-06-17 14:03:23'),
	(157, 18, 16, 10000, 9, '2021-06-17 14:03:23'),
	(158, 18, 45, 12500, 9, '2021-06-17 14:03:23'),
	(159, 18, 54, 12500, 9, '2021-06-17 14:03:23'),
	(160, 18, 83, 2500, 9, '2021-06-17 14:03:23'),
	(161, 18, 46, 10000, 9, '2021-06-17 14:03:23'),
	(162, 18, 77, 10000, 9, '2021-06-17 14:03:23'),
	(163, 18, 32, 7500, 9, '2021-06-17 14:03:23'),
	(164, 18, 13, 7500, 9, '2021-06-17 14:03:23'),
	(165, 18, 78, 7500, 9, '2021-06-17 14:03:23'),
	(166, 18, 15, 0, 9, '2021-06-17 14:03:23'),
	(167, 18, 71, 7500, 9, '2021-06-17 14:03:23'),
	(168, 18, 30, 7500, 9, '2021-06-17 14:03:23'),
	(169, 18, 89, 5000, 9, '2021-06-17 14:03:23'),
	(170, 18, 36, 7500, 9, '2021-06-17 14:03:23'),
	(171, 18, 3, 0, 9, '2021-06-17 14:03:23'),
	(172, 18, 14, 5000, 9, '2021-06-17 14:03:23'),
	(173, 18, 31, 0, 9, '2021-06-17 14:03:23'),
	(174, 18, 69, 0, 9, '2021-06-17 14:03:23'),
	(175, 18, 9, 0, 9, '2021-06-17 14:03:23'),
	(176, 18, 41, 0, 9, '2021-06-17 14:03:23'),
	(177, 18, 58, 0, 9, '2021-06-17 14:03:23'),
	(178, 18, 7, 0, 9, '2021-06-17 14:03:23'),
	(179, 18, 67, 5000, 9, '2021-06-17 14:03:23'),
	(180, 18, 44, 0, 9, '2021-06-17 14:03:23'),
	(181, 18, 17, 0, 9, '2021-06-17 14:03:23'),
	(182, 18, 42, 5000, 9, '2021-06-17 14:03:23'),
	(183, 18, 33, 2500, 9, '2021-06-17 14:03:23'),
	(184, 18, 43, 0, 9, '2021-06-17 14:03:23'),
	(185, 18, 21, 2500, 9, '2021-06-17 14:03:23'),
	(186, 18, 19, 2500, 9, '2021-06-17 14:03:23'),
	(187, 18, 84, 5000, 9, '2021-06-17 14:03:23'),
	(188, 18, 70, 2500, 9, '2021-06-17 14:03:23'),
	(189, 18, 20, 5000, 9, '2021-06-17 14:03:23'),
	(190, 19, 50, 0, 9, '2021-06-17 15:04:55'),
	(191, 19, 12, 0, 9, '2021-06-17 15:04:55'),
	(192, 19, 3, 60000, 9, '2021-06-17 15:04:55'),
	(193, 19, 2, 0, 9, '2021-06-17 15:04:55'),
	(194, 19, 41, 0, 9, '2021-06-17 15:04:55'),
	(195, 19, 54, 0, 9, '2021-06-17 15:04:55'),
	(196, 19, 22, 2500, 9, '2021-06-17 15:04:55'),
	(197, 19, 9, 0, 9, '2021-06-17 15:04:55'),
	(198, 19, 60, 10000, 9, '2021-06-17 15:04:55'),
	(199, 19, 4, 20000, 9, '2021-06-17 15:04:55'),
	(200, 19, 7, 40000, 9, '2021-06-17 15:04:55'),
	(201, 19, 16, 5000, 9, '2021-06-17 15:04:55'),
	(202, 19, 61, 2500, 9, '2021-06-17 15:04:55'),
	(203, 20, 54, 2500, 9, '2021-06-17 15:38:20'),
	(204, 20, 12, 5000, 9, '2021-06-17 15:38:20'),
	(205, 20, 3, 30000, 9, '2021-06-17 15:38:20'),
	(206, 20, 41, 0, 9, '2021-06-17 15:38:20'),
	(207, 20, 51, 0, 9, '2021-06-17 15:38:20'),
	(208, 20, 61, 2500, 9, '2021-06-17 15:38:20'),
	(209, 20, 6, 2500, 9, '2021-06-17 15:38:20'),
	(210, 20, 4, 0, 9, '2021-06-17 15:38:20'),
	(211, 20, 63, 0, 9, '2021-06-17 15:38:20'),
	(212, 20, 64, 2500, 9, '2021-06-17 15:38:20'),
	(213, 20, 9, 2500, 9, '2021-06-17 15:38:20'),
	(214, 20, 7, 2500, 9, '2021-06-17 15:38:20'),
	(215, 21, 50, 0, 9, '2021-07-05 12:31:01'),
	(216, 21, 12, 0, 9, '2021-07-05 12:31:01'),
	(217, 21, 3, 30000, 9, '2021-07-05 12:31:01'),
	(218, 21, 2, 0, 9, '2021-07-05 12:31:01'),
	(219, 21, 41, 0, 9, '2021-07-05 12:31:01'),
	(220, 21, 54, 5000, 9, '2021-07-05 12:31:01'),
	(221, 21, 22, 10000, 9, '2021-07-05 12:31:01'),
	(222, 21, 9, 0, 9, '2021-07-05 12:31:01'),
	(223, 21, 60, 10000, 9, '2021-07-05 12:31:01'),
	(224, 21, 4, 0, 9, '2021-07-05 12:31:01'),
	(225, 21, 7, 0, 9, '2021-07-05 12:31:01'),
	(226, 21, 16, 5000, 9, '2021-07-05 12:31:01'),
	(227, 21, 61, 10000, 9, '2021-07-05 12:31:01'),
	(228, 21, 13, 5000, 9, '2021-07-05 12:31:01'),
	(229, 22, 14, 2000, 9, '2021-07-06 12:22:52'),
	(230, 22, 48, 0, 9, '2021-07-06 12:22:52'),
	(231, 22, 7, 20000, 9, '2021-07-06 12:22:52'),
	(232, 22, 15, 20000, 9, '2021-07-06 12:22:52'),
	(233, 22, 63, 2000, 9, '2021-07-06 12:22:52'),
	(234, 22, 47, 10000, 9, '2021-07-06 12:22:52'),
	(235, 22, 64, 2000, 9, '2021-07-06 12:22:52'),
	(236, 22, 41, 0, 9, '2021-07-06 12:22:52'),
	(237, 22, 70, 2000, 9, '2021-07-06 12:22:52'),
	(238, 22, 5, 20000, 9, '2021-07-06 12:22:52');
/*!40000 ALTER TABLE `nd_ppo_lock_detail` ENABLE KEYS */;

-- Dumping structure for table favourtdj_system.nd_ppo_qty_current
DROP TABLE IF EXISTS `nd_ppo_qty_current`;
CREATE TABLE IF NOT EXISTS `nd_ppo_qty_current` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `barang_id` smallint(6) DEFAULT NULL,
  `warna_id` smallint(6) DEFAULT NULL,
  `qty` mediumint(9) DEFAULT NULL,
  `user_id` smallint(6) DEFAULT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=103 DEFAULT CHARSET=latin1;

-- Dumping data for table favourtdj_system.nd_ppo_qty_current: ~102 rows (approximately)
DELETE FROM `nd_ppo_qty_current`;
/*!40000 ALTER TABLE `nd_ppo_qty_current` DISABLE KEYS */;
INSERT INTO `nd_ppo_qty_current` (`id`, `barang_id`, `warna_id`, `qty`, `user_id`, `created`, `updated`) VALUES
	(1, 1, 79, 0, 9, '2020-12-16 04:15:16', '2021-06-17 14:03:23'),
	(2, 1, 16, 0, 9, '2020-12-16 04:16:05', '2021-06-17 14:03:23'),
	(3, 1, 45, 0, 9, '2020-12-16 04:16:13', '2021-06-17 14:03:23'),
	(4, 1, 54, 0, 9, '2020-12-16 04:16:52', '2021-06-17 14:03:23'),
	(5, 1, 83, 0, 9, '2020-12-16 04:17:45', '2021-06-17 14:03:23'),
	(6, 1, 46, 0, 9, '2020-12-16 04:19:06', '2021-06-17 14:03:23'),
	(7, 1, 77, 0, 9, '2020-12-16 04:19:49', '2021-06-17 14:03:23'),
	(8, 1, 32, 0, 9, '2020-12-16 04:20:38', '2021-06-17 14:03:23'),
	(9, 1, 13, 0, 9, '2020-12-16 04:21:10', '2021-06-17 14:03:23'),
	(10, 1, 78, 0, 9, '2020-12-16 04:21:33', '2021-06-17 14:03:23'),
	(11, 1, 15, 0, 9, '2020-12-16 04:22:15', '2021-03-03 08:00:56'),
	(12, 1, 71, 0, 9, '2020-12-16 04:22:24', '2021-06-17 14:03:23'),
	(13, 1, 30, 0, 9, '2020-12-16 04:35:11', '2021-06-17 14:03:23'),
	(14, 13, 50, 0, 9, '2020-12-16 04:46:30', '2021-03-23 06:12:44'),
	(15, 13, 12, 0, 9, '2020-12-16 04:47:14', '2020-12-16 04:57:28'),
	(16, 13, 3, 0, 9, '2020-12-16 04:48:54', '2021-07-05 12:31:01'),
	(17, 13, 2, 0, 9, '2020-12-16 04:50:11', '2020-12-16 04:57:28'),
	(18, 13, 41, 0, 9, '2020-12-16 04:51:02', '2021-03-23 06:12:44'),
	(19, 7, 66, 0, 9, '2020-12-16 04:59:55', '2020-12-16 05:01:12'),
	(20, 7, 3, 0, 9, '2020-12-16 05:00:10', '2020-12-16 05:01:12'),
	(21, 10, 14, 0, 9, '2020-12-16 05:27:11', '2021-07-06 12:22:52'),
	(22, 6, 62, 0, 9, '2020-12-16 05:37:12', '2021-06-14 13:54:51'),
	(23, 6, 12, 0, 9, '2020-12-16 05:37:40', '2021-06-14 13:54:51'),
	(24, 6, 3, 0, 9, '2020-12-16 05:38:44', '2021-06-14 13:54:51'),
	(25, 6, 6, 0, 9, '2020-12-16 05:39:11', '2021-06-14 13:54:51'),
	(26, 6, 4, 0, 9, '2020-12-16 05:39:34', '2021-06-14 13:54:51'),
	(27, 6, 41, 0, 9, '2020-12-16 05:39:45', '2021-06-14 13:54:51'),
	(28, 6, 42, 0, 9, '2020-12-16 05:42:23', '2021-03-23 06:43:01'),
	(29, 4, 54, 0, 9, '2020-12-16 05:50:23', '2021-06-17 15:38:20'),
	(30, 4, 12, 2500, 9, '2020-12-16 05:50:32', '2021-07-05 12:54:23'),
	(31, 4, 3, 50000, 9, '2020-12-16 05:50:58', '2021-07-05 12:57:56'),
	(32, 4, 41, 2500, 9, '2020-12-16 05:51:34', '2021-07-05 12:57:22'),
	(33, 1, 89, 0, 9, '2020-12-16 05:59:57', '2021-06-17 14:03:23'),
	(34, 1, 36, 0, 9, '2021-03-03 07:01:00', '2021-06-17 14:03:23'),
	(35, 1, 3, 0, 9, '2021-03-03 07:01:30', '2021-03-03 08:00:56'),
	(36, 1, 14, 0, 9, '2021-03-03 07:35:17', '2021-06-17 14:03:23'),
	(37, 1, 31, 0, 9, '2021-03-03 07:38:00', '2021-03-03 08:00:56'),
	(38, 1, 69, 0, 9, '2021-03-03 07:38:13', '2021-03-03 07:53:07'),
	(39, 1, 9, 0, 9, '2021-03-03 07:46:25', '2021-03-03 08:00:56'),
	(40, 1, 41, 0, 9, '2021-03-03 07:46:36', '2021-03-03 07:55:19'),
	(41, 1, 58, 0, 9, '2021-03-03 07:47:24', '2021-03-03 08:00:56'),
	(42, 1, 7, 0, 9, '2021-03-03 07:47:47', '2021-03-03 08:00:56'),
	(43, 1, 67, 0, 9, '2021-03-03 07:48:14', '2021-06-17 14:03:23'),
	(44, 1, 44, 0, 9, '2021-03-03 07:48:43', '2021-03-03 08:00:56'),
	(45, 1, 17, 0, 9, '2021-03-03 07:48:59', '2021-03-03 08:00:56'),
	(46, 1, 42, 0, 9, '2021-03-03 07:49:27', '2021-06-17 14:03:23'),
	(47, 1, 33, 0, 9, '2021-03-03 07:50:23', '2021-06-17 14:03:23'),
	(48, 1, 43, 0, 9, '2021-03-03 07:55:29', '2021-03-03 08:00:56'),
	(49, 27, 56, 0, 9, '2021-03-22 09:12:05', '2021-03-23 03:53:52'),
	(50, 27, 9, 0, 9, '2021-03-22 09:12:40', '2021-03-23 03:53:52'),
	(51, 27, 7, 0, 9, '2021-03-22 09:12:52', '2021-03-23 03:53:52'),
	(52, 27, 5, 0, 9, '2021-03-22 09:13:12', '2021-03-23 03:53:52'),
	(53, 27, 15, 0, 9, '2021-03-22 09:13:44', '2021-03-23 03:53:52'),
	(54, 27, 42, 0, 9, '2021-03-22 09:13:46', '2021-03-23 03:53:52'),
	(55, 27, 13, 0, 9, '2021-03-22 09:13:47', '2021-03-23 03:53:52'),
	(56, 27, 87, 0, 9, '2021-03-22 09:13:51', '2021-03-23 03:53:52'),
	(57, 27, 93, 0, 9, '2021-03-22 09:13:53', '2021-03-23 03:53:52'),
	(58, 27, 92, 0, 9, '2021-03-22 09:13:55', '2021-03-23 03:53:52'),
	(59, 27, 70, 0, 9, '2021-03-22 09:13:58', '2021-03-23 03:53:52'),
	(60, 27, 90, 0, 9, '2021-03-22 09:14:14', '2021-03-23 03:53:52'),
	(61, 4, 51, 0, 9, '2021-03-23 04:16:44', '2021-03-23 04:24:34'),
	(62, 4, 61, 0, 9, '2021-03-23 04:19:00', '2021-06-17 15:38:20'),
	(63, 4, 6, 2500, 9, '2021-03-23 04:19:28', '2021-07-05 12:56:43'),
	(64, 4, 4, 7500, 9, '2021-03-23 04:19:46', '2021-07-05 12:55:43'),
	(65, 4, 63, 0, 9, '2021-03-23 04:21:05', '2021-03-23 04:24:34'),
	(66, 4, 64, 0, 9, '2021-03-23 04:22:48', '2021-06-17 15:38:20'),
	(67, 4, 9, 2500, 9, '2021-03-23 04:23:27', '2021-07-05 12:57:08'),
	(68, 13, 54, 0, 9, '2021-03-23 04:40:47', '2021-07-05 12:31:01'),
	(69, 13, 22, 0, 9, '2021-03-23 04:41:55', '2021-07-05 12:31:01'),
	(70, 13, 9, 0, 9, '2021-03-23 04:42:31', '2021-03-23 06:12:44'),
	(71, 13, 60, 0, 9, '2021-03-23 06:12:34', '2021-07-05 12:31:01'),
	(72, 10, 48, 0, 9, '2021-03-23 06:23:12', '2021-03-23 06:23:42'),
	(73, 10, 7, 0, 9, '2021-03-23 06:23:25', '2021-07-06 12:22:52'),
	(74, 10, 15, 0, 9, '2021-03-23 06:23:39', '2021-07-06 12:22:52'),
	(75, 6, 22, 0, 9, '2021-03-23 06:36:47', '2021-06-14 13:54:51'),
	(76, 6, 9, 0, 9, '2021-03-23 06:38:01', '2021-06-14 13:54:51'),
	(77, 6, 7, 0, 9, '2021-03-23 06:39:14', '2021-06-14 13:54:51'),
	(78, 6, 15, 0, 9, '2021-03-23 06:39:30', '2021-06-14 13:54:51'),
	(79, 6, 54, 0, 9, '2021-06-14 13:41:30', '2021-06-14 13:54:51'),
	(80, 6, 47, 0, 9, '2021-06-14 13:42:21', '2021-06-14 13:54:51'),
	(81, 6, 13, 0, 9, '2021-06-14 13:44:51', '2021-06-14 13:44:59'),
	(82, 13, 4, 0, 9, '2021-06-14 15:08:53', '2021-06-17 15:04:55'),
	(83, 13, 7, 0, 9, '2021-06-14 15:09:28', '2021-06-17 15:04:55'),
	(84, 13, 16, 0, 9, '2021-06-14 15:10:44', '2021-07-05 12:31:01'),
	(85, 1, 21, 0, 9, '2021-06-17 13:43:56', '2021-06-17 14:03:23'),
	(86, 1, 19, 0, 9, '2021-06-17 13:45:04', '2021-06-17 14:03:23'),
	(87, 1, 84, 0, 9, '2021-06-17 13:45:29', '2021-06-17 14:03:23'),
	(88, 1, 70, 0, 9, '2021-06-17 13:47:07', '2021-06-17 14:03:23'),
	(89, 1, 20, 0, 9, '2021-06-17 13:48:22', '2021-06-17 14:03:23'),
	(90, 13, 61, 0, 9, '2021-06-17 15:03:16', '2021-07-05 12:31:01'),
	(91, 4, 7, 2500, 9, '2021-06-17 15:37:49', '2021-07-05 12:57:40'),
	(92, 13, 13, 0, 9, '2021-07-05 12:29:30', '2021-07-05 12:31:01'),
	(93, 11, 24, 15000, 9, '2021-07-05 13:13:49', '2021-07-05 13:13:49'),
	(94, 11, 8, 5000, 9, '2021-07-05 13:13:51', '2021-07-05 13:13:51'),
	(95, 10, 63, 0, 9, '2021-07-06 12:00:42', '2021-07-06 12:22:52'),
	(96, 10, 47, 0, 9, '2021-07-06 12:01:07', '2021-07-06 12:22:52'),
	(97, 10, 64, 0, 9, '2021-07-06 12:01:52', '2021-07-06 12:22:52'),
	(98, 10, 41, 0, 9, '2021-07-06 12:02:21', '2021-07-06 12:06:02'),
	(99, 10, 70, 0, 9, '2021-07-06 12:02:25', '2021-07-06 12:22:52'),
	(100, 10, 5, 0, 9, '2021-07-06 12:02:49', '2021-07-06 12:22:52'),
	(101, 29, 24, 3000, 9, '2021-07-06 12:09:21', '2021-07-06 12:09:21'),
	(102, 29, 8, 8000, 9, '2021-07-06 12:09:23', '2021-07-06 12:09:23');
/*!40000 ALTER TABLE `nd_ppo_qty_current` ENABLE KEYS */;

-- Dumping structure for table favourtdj_system.nd_ppo_table_setting
DROP TABLE IF EXISTS `nd_ppo_table_setting`;
CREATE TABLE IF NOT EXISTS `nd_ppo_table_setting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `po_pembelian_batch_id` mediumint(9) DEFAULT NULL,
  `barang_id` smallint(6) DEFAULT '1',
  `status_include` tinyint(4) DEFAULT '1',
  `status_show` tinyint(4) DEFAULT '1',
  `user_id` smallint(6) DEFAULT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=58 DEFAULT CHARSET=latin1;

-- Dumping data for table favourtdj_system.nd_ppo_table_setting: ~57 rows (approximately)
DELETE FROM `nd_ppo_table_setting`;
/*!40000 ALTER TABLE `nd_ppo_table_setting` DISABLE KEYS */;
INSERT INTO `nd_ppo_table_setting` (`id`, `po_pembelian_batch_id`, `barang_id`, `status_include`, `status_show`, `user_id`, `created`, `updated`) VALUES
	(1, 4, 11, 0, 1, 9, '2021-03-23 04:02:58', '2021-07-05 13:02:23'),
	(2, 9, 11, 0, 1, 9, '2021-03-23 04:03:02', '2021-07-05 13:02:24'),
	(3, 29, 11, 0, 1, 9, '2021-03-23 04:03:03', '2021-07-05 13:02:24'),
	(4, 41, 4, 0, 1, 9, '2021-03-23 04:03:11', '2021-03-23 04:03:11'),
	(5, 1, 13, 0, 1, 9, '2021-03-23 04:39:11', '2021-03-23 04:39:11'),
	(6, 7, 13, 0, 1, 9, '2021-03-23 04:39:14', '2021-03-23 04:39:14'),
	(7, 3, 13, 0, 1, 9, '2021-03-23 04:39:18', '2021-03-23 04:39:18'),
	(8, 10, 13, 0, 1, 9, '2021-03-23 04:39:20', '2021-03-23 04:39:20'),
	(9, 16, 13, 0, 1, 9, '2021-03-23 04:39:28', '2021-03-23 04:39:28'),
	(10, 20, 13, 0, 1, 9, '2021-03-23 04:39:33', '2021-03-23 04:39:33'),
	(11, 22, 13, 0, 1, 9, '2021-03-23 04:39:38', '2021-03-23 04:39:38'),
	(12, 24, 13, 0, 1, 9, '2021-03-23 04:39:46', '2021-03-23 04:39:46'),
	(13, 27, 13, 0, 1, 9, '2021-03-23 04:39:53', '2021-03-23 04:39:53'),
	(14, 31, 13, 0, 1, 9, '2021-03-23 04:39:56', '2021-03-23 04:39:56'),
	(15, 11, 6, 0, 1, 9, '2021-03-23 06:35:31', '2021-03-23 06:35:31'),
	(16, 18, 6, 0, 1, 9, '2021-03-23 06:35:33', '2021-03-23 06:35:33'),
	(17, 26, 6, 0, 1, 9, '2021-03-23 06:35:38', '2021-03-23 06:35:38'),
	(18, 32, 6, 0, 1, 9, '2021-03-23 06:35:50', '2021-03-23 06:35:50'),
	(19, 45, 6, 0, 1, 9, '2021-06-14 13:39:37', '2021-06-14 13:39:37'),
	(20, 47, 6, 0, 1, 9, '2021-06-14 13:39:38', '2021-06-14 13:39:38'),
	(21, 54, 6, 0, 1, 9, '2021-06-14 13:39:43', '2021-06-14 13:39:43'),
	(22, 60, 6, 0, 1, 9, '2021-06-14 13:39:45', '2021-06-14 13:39:45'),
	(23, 37, 13, 0, 1, 9, '2021-06-14 15:07:07', '2021-06-14 15:07:07'),
	(24, 44, 13, 0, 1, 9, '2021-06-14 15:07:07', '2021-06-14 15:07:07'),
	(25, 50, 13, 0, 1, 9, '2021-06-14 15:07:09', '2021-06-14 15:07:09'),
	(26, 52, 13, 0, 1, 9, '2021-06-14 15:07:09', '2021-06-14 15:07:09'),
	(27, 55, 13, 0, 1, 9, '2021-06-14 15:07:17', '2021-06-14 15:07:17'),
	(28, 62, 13, 0, 1, 9, '2021-06-14 15:07:18', '2021-06-14 15:07:18'),
	(29, 64, 13, 0, 1, 9, '2021-06-14 15:07:19', '2021-06-14 15:07:19'),
	(30, 80, 13, 0, 1, 9, '2021-06-14 15:07:20', '2021-06-14 15:07:20'),
	(31, 90, 13, 0, 1, 9, '2021-06-14 15:07:20', '2021-06-14 15:07:20'),
	(32, 6, 1, 0, 1, 9, '2021-06-17 13:37:38', '2021-06-17 13:37:38'),
	(33, 14, 1, 0, 1, 9, '2021-06-17 13:37:38', '2021-06-17 13:37:38'),
	(34, 23, 1, 0, 1, 9, '2021-06-17 13:37:39', '2021-06-17 13:37:39'),
	(35, 42, 1, 0, 1, 9, '2021-06-17 13:37:40', '2021-06-17 13:37:40'),
	(36, 56, 1, 0, 1, 9, '2021-06-17 13:37:42', '2021-06-17 13:37:42'),
	(37, 71, 1, 0, 1, 9, '2021-06-17 13:37:43', '2021-06-17 13:37:43'),
	(38, 91, 1, 0, 1, 9, '2021-06-17 13:37:43', '2021-06-17 13:37:43'),
	(39, 116, 1, 0, 1, 9, '2021-06-17 13:37:45', '2021-06-17 13:37:45'),
	(40, 135, 1, 0, 1, 9, '2021-06-17 13:37:46', '2021-06-17 13:37:46'),
	(41, 139, 1, 0, 1, 9, '2021-06-17 13:37:47', '2021-06-17 13:37:47'),
	(42, 146, 1, 0, 1, 9, '2021-06-17 13:37:47', '2021-06-17 13:37:47'),
	(43, 95, 13, 0, 1, 9, '2021-06-17 14:58:13', '2021-06-17 14:58:13'),
	(44, 106, 13, 0, 1, 9, '2021-06-17 14:58:14', '2021-06-17 14:58:14'),
	(45, 109, 13, 0, 1, 9, '2021-06-17 14:58:15', '2021-06-17 14:58:15'),
	(46, 111, 13, 0, 1, 9, '2021-06-17 14:58:15', '2021-06-17 14:58:15'),
	(47, 122, 13, 0, 1, 9, '2021-06-17 14:58:15', '2021-06-17 14:58:15'),
	(48, 124, 13, 0, 1, 9, '2021-06-17 14:58:16', '2021-06-17 14:58:16'),
	(49, 125, 13, 0, 1, 9, '2021-06-17 14:58:17', '2021-06-17 14:58:17'),
	(50, 48, 11, 0, 1, 9, '2021-06-17 15:35:10', '2021-07-05 13:02:25'),
	(51, 58, 4, 0, 1, 9, '2021-06-17 15:35:10', '2021-06-17 15:35:10'),
	(52, 67, 4, 0, 1, 9, '2021-06-17 15:35:11', '2021-06-17 15:35:11'),
	(53, 75, 4, 0, 1, 9, '2021-06-17 15:35:12', '2021-06-17 15:35:12'),
	(54, 78, 4, 0, 1, 9, '2021-06-17 15:35:13', '2021-06-17 15:35:13'),
	(55, 142, 13, 0, 1, 9, '2021-07-05 12:03:42', '2021-07-05 12:03:42'),
	(56, 143, 13, 0, 1, 9, '2021-07-05 12:03:43', '2021-07-05 12:03:43'),
	(57, 5, 11, 0, 1, 9, '2021-07-05 13:02:23', '2021-07-05 13:02:23');
/*!40000 ALTER TABLE `nd_ppo_table_setting` ENABLE KEYS */;

-- Dumping structure for table favourtdj_system.nd_ppo_to_po
DROP TABLE IF EXISTS `nd_ppo_to_po`;
CREATE TABLE IF NOT EXISTS `nd_ppo_to_po` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ppo_lock_id` smallint(5) unsigned DEFAULT NULL,
  `po_pembelian_detail_id` smallint(5) unsigned DEFAULT NULL,
  `po_pembelian_batch_id` smallint(5) unsigned DEFAULT NULL,
  `user_id` smallint(5) unsigned DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;

-- Dumping data for table favourtdj_system.nd_ppo_to_po: ~12 rows (approximately)
DELETE FROM `nd_ppo_to_po`;
/*!40000 ALTER TABLE `nd_ppo_to_po` DISABLE KEYS */;
INSERT INTO `nd_ppo_to_po` (`id`, `ppo_lock_id`, `po_pembelian_detail_id`, `po_pembelian_batch_id`, `user_id`, `created_at`, `updated_at`) VALUES
	(1, 8, 31, 174, 9, '2020-12-16 05:46:48', '2020-12-16 05:46:48'),
	(2, 9, 38, 175, 9, '2020-12-16 05:52:51', '2020-12-16 05:52:51'),
	(3, 1, 42, 176, 9, '2020-12-16 06:02:12', '2020-12-16 06:02:12'),
	(4, 6, 41, 177, 9, '2020-12-16 06:13:12', '2020-12-16 06:13:12'),
	(5, 7, 37, 178, 9, '2020-12-16 06:16:33', '2020-12-16 06:16:33'),
	(6, 10, 45, 209, 9, '2021-03-03 08:01:15', '2021-03-03 08:01:15'),
	(7, 16, 46, 238, 9, '2021-06-14 13:55:06', '2021-06-14 13:55:06'),
	(8, 18, 56, 242, 9, '2021-06-17 14:03:37', '2021-06-17 14:03:37'),
	(9, 19, 54, 243, 9, '2021-06-17 15:05:09', '2021-06-17 15:05:09'),
	(10, 20, 55, 244, 9, '2021-06-17 15:38:46', '2021-06-17 15:38:46'),
	(11, 21, 54, 248, 9, '2021-07-05 12:31:37', '2021-07-05 12:31:37'),
	(12, 22, 48, 250, 9, '2021-07-06 12:23:12', '2021-07-06 12:23:12');
/*!40000 ALTER TABLE `nd_ppo_to_po` ENABLE KEYS */;

-- Dumping structure for table favourtdj_system.nd_printer_list
DROP TABLE IF EXISTS `nd_printer_list`;
CREATE TABLE IF NOT EXISTS `nd_printer_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) DEFAULT NULL,
  `status_aktif` int(1) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

-- Dumping data for table favourtdj_system.nd_printer_list: 8 rows
DELETE FROM `nd_printer_list`;
/*!40000 ALTER TABLE `nd_printer_list` DISABLE KEYS */;
INSERT INTO `nd_printer_list` (`id`, `nama`, `status_aktif`) VALUES
	(1, 'EPSON_LQ_310', 1),
	(2, 'EPSON LX-310', 1),
	(3, 'EPSON_LX_310', 1),
	(4, 'EPSON_LX_310_3', 1),
	(5, 'EPSON_LX_310_2', 1),
	(6, 'EPSON LX-310 ESC/P', 1),
	(7, 'tes', 0),
	(8, 'TES123', 0),
	(9, 'epson_lq_310', 1),
	(10, 'epson_lq_310', 1),
	(11, 'epson_lq_310', 1);
/*!40000 ALTER TABLE `nd_printer_list` ENABLE KEYS */;

-- Dumping structure for table favourtdj_system.nd_rekam_faktur_pajak
DROP TABLE IF EXISTS `nd_rekam_faktur_pajak`;
CREATE TABLE IF NOT EXISTS `nd_rekam_faktur_pajak` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` smallint(6) DEFAULT NULL,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `status_email` tinyint(1) NOT NULL DEFAULT '1',
  `no_surat` smallint(11) DEFAULT NULL,
  `locked_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=106 DEFAULT CHARSET=latin1;

-- Dumping data for table favourtdj_system.nd_rekam_faktur_pajak: ~103 rows (approximately)
DELETE FROM `nd_rekam_faktur_pajak`;
/*!40000 ALTER TABLE `nd_rekam_faktur_pajak` DISABLE KEYS */;
INSERT INTO `nd_rekam_faktur_pajak` (`id`, `user_id`, `updated_at`, `created_at`, `status`, `status_email`, `no_surat`, `locked_date`) VALUES
	(1, 1, '2019-08-14 04:13:36', '2019-08-14 04:10:17', 1, 1, 0, NULL),
	(2, 1, '2019-08-26 04:21:21', '2019-08-26 04:21:21', 1, 1, 0, NULL),
	(3, 1, '2019-08-26 04:22:31', '2019-08-26 04:22:31', 1, 1, 0, NULL),
	(4, 1, '2019-09-02 03:26:58', '2019-09-02 03:26:58', 1, 1, 0, NULL),
	(5, 19, '2019-09-02 05:06:07', '2019-09-02 05:00:27', 0, 1, 0, NULL),
	(7, 1, '2020-02-15 06:04:35', '2019-09-07 02:22:30', 0, 1, 0, NULL),
	(8, 19, '2019-09-14 03:40:11', '2019-09-14 03:26:57', 0, 1, 0, NULL),
	(9, 19, '2019-09-23 06:38:43', '2019-09-23 06:37:20', 0, 1, 0, NULL),
	(10, 19, '2019-10-01 04:08:26', '2019-10-01 04:07:34', 0, 1, 0, NULL),
	(11, 19, '2019-10-07 03:07:10', '2019-10-07 03:05:16', 0, 1, 0, NULL),
	(12, 19, '2019-10-12 04:18:50', '2019-10-12 04:18:31', 0, 1, 0, NULL),
	(14, 19, '2019-10-21 04:12:11', '2019-10-21 04:11:13', 0, 1, 0, NULL),
	(15, 19, '2019-10-28 03:38:02', '2019-10-28 03:36:57', 0, 1, 0, NULL),
	(16, 19, '2019-11-08 05:47:01', '2019-11-08 05:00:37', 0, 1, 0, NULL),
	(17, 19, '2019-11-08 06:30:14', '2019-11-08 06:30:01', 0, 1, 0, NULL),
	(18, 19, '2019-11-19 05:39:53', '2019-11-19 05:39:30', 0, 1, 0, NULL),
	(19, 19, '2019-11-25 03:12:06', '2019-11-25 03:04:27', 0, 1, 0, NULL),
	(20, 19, '2019-12-02 03:28:42', '2019-12-02 03:28:31', 0, 1, 0, NULL),
	(21, 19, '2019-12-09 03:38:28', '2019-12-09 03:13:46', 0, 1, 0, NULL),
	(22, 19, '2019-12-16 03:18:16', '2019-12-16 03:16:10', 0, 1, 0, NULL),
	(23, 19, '2020-02-18 03:38:49', '2020-01-02 04:58:49', 0, 1, 1, NULL),
	(24, 19, '2020-02-18 03:38:51', '2020-01-13 02:59:56', 0, 1, 2, NULL),
	(25, 19, '2020-02-18 03:38:53', '2020-01-22 05:08:32', 0, 1, 3, NULL),
	(26, 19, '2020-02-18 03:38:55', '2020-01-29 04:09:06', 0, 1, 4, NULL),
	(27, 19, '2020-02-18 03:38:57', '2020-02-03 04:33:36', 0, 1, 5, NULL),
	(28, 19, '2020-02-21 08:08:42', '2020-02-08 03:41:58', 0, 0, 6, '2020-02-21 15:08:42'),
	(29, 19, '2020-02-18 03:39:09', '2020-02-10 02:12:49', 0, 1, 7, NULL),
	(30, 19, '2020-02-19 08:51:00', '2020-02-17 07:29:15', 0, 0, 8, '2020-02-19 15:51:00'),
	(31, 19, '2020-02-27 07:57:46', '2020-02-24 04:01:15', 0, 0, 9, '2020-02-27 14:57:46'),
	(32, 19, '2020-03-16 04:29:40', '2020-02-25 02:30:56', 0, 0, 10, '2020-03-16 11:29:40'),
	(33, 19, '2020-03-03 04:53:32', '2020-03-02 02:56:40', 0, 0, 11, '2020-03-03 11:53:32'),
	(34, 19, '2020-03-10 09:28:50', '2020-03-09 02:00:49', 0, 0, 12, '2020-03-10 16:28:50'),
	(35, 19, '2020-03-17 05:44:46', '2020-03-16 06:30:57', 0, 0, 13, '2020-03-17 12:44:46'),
	(36, 19, '2020-04-06 03:12:43', '2020-03-23 04:45:55', 0, 0, 14, '2020-04-06 10:12:43'),
	(37, 19, '2020-04-25 07:41:43', '2020-03-31 04:18:53', 0, 0, 15, '2020-04-25 14:41:43'),
	(38, 19, '2020-03-31 06:38:25', '2020-03-31 06:37:32', 0, 1, 16, NULL),
	(39, 19, '2020-06-03 04:18:29', '2020-04-13 02:58:06', 0, 0, 17, '2020-06-03 11:18:29'),
	(40, 19, '2020-04-25 06:57:27', '2020-04-20 06:13:25', 0, 0, 18, '2020-04-25 13:57:27'),
	(41, 19, '2020-04-30 03:39:56', '2020-04-29 03:00:59', 0, 0, 19, '2020-04-30 10:39:56'),
	(42, 19, '2020-05-05 06:49:48', '2020-05-04 02:56:19', 0, 0, 20, '2020-05-05 13:49:48'),
	(43, 19, '2020-08-05 09:50:40', '2020-05-11 03:13:24', 0, 1, 21, '2020-05-12 11:48:29'),
	(44, 19, '2020-05-16 02:46:27', '2020-05-15 05:25:36', 0, 0, 22, '2020-05-16 09:46:27'),
	(45, 19, '2020-05-26 06:40:11', '2020-05-16 04:06:26', 0, 0, 23, '2020-05-26 13:40:11'),
	(46, 19, '2020-05-16 07:41:21', '2020-05-16 07:41:08', 0, 1, 24, NULL),
	(47, 9, '2020-05-26 06:40:37', '2020-05-26 06:09:15', 0, 0, 25, '2020-05-26 13:40:37'),
	(48, 19, '2020-06-09 07:52:28', '2020-06-08 07:14:49', 0, 0, 26, '2020-06-09 14:52:28'),
	(49, 19, '2020-06-17 06:57:15', '2020-06-15 06:28:28', 0, 0, 27, '2020-06-17 13:57:15'),
	(50, 19, '2020-06-17 03:57:35', '2020-06-16 03:35:39', 0, 0, 28, '2020-06-17 10:57:35'),
	(51, 19, '2020-06-26 03:43:01', '2020-06-22 04:36:06', 0, 0, 29, '2020-06-26 10:43:01'),
	(52, 19, '2020-07-04 04:48:30', '2020-06-29 06:22:07', 0, 0, 30, '2020-07-04 11:48:30'),
	(53, 19, '2020-07-04 04:49:20', '2020-07-01 06:40:31', 0, 0, 31, '2020-07-04 11:49:20'),
	(54, 19, '2020-07-08 06:04:55', '2020-07-07 02:10:07', 0, 0, 32, '2020-07-08 13:04:55'),
	(55, 19, '2020-07-15 02:02:39', '2020-07-13 06:42:21', 0, 0, 33, '2020-07-15 09:02:39'),
	(56, 19, '2020-07-22 02:55:38', '2020-07-20 08:04:45', 0, 0, 34, '2020-07-22 09:55:38'),
	(57, 19, '2020-07-29 03:23:23', '2020-07-28 02:53:04', 0, 0, 35, '2020-07-29 10:23:23'),
	(58, 19, '2020-08-06 06:19:26', '2020-08-04 06:14:04', 0, 0, 36, '2020-08-06 13:19:26'),
	(59, 19, '2020-08-12 05:57:30', '2020-08-10 08:37:45', 0, 0, 37, '2020-08-12 12:57:30'),
	(60, 19, '2020-08-22 02:00:17', '2020-08-18 03:44:21', 0, 0, 38, '2020-08-22 09:00:17'),
	(61, 19, '2020-08-26 05:59:25', '2020-08-24 04:47:48', 0, 0, 39, '2020-08-26 12:59:25'),
	(62, 10, '2020-09-04 04:30:15', '2020-09-01 06:59:17', 0, 0, 40, '2020-09-04 11:30:15'),
	(63, 19, '2020-09-10 06:44:21', '2020-09-07 07:01:52', 0, 0, 41, '2020-09-10 13:44:21'),
	(64, 19, '2020-09-16 03:13:54', '2020-09-14 06:06:35', 0, 0, 42, '2020-09-16 10:13:54'),
	(65, 19, '2020-09-22 06:43:11', '2020-09-21 04:31:50', 0, 0, 43, '2020-09-22 13:43:11'),
	(66, 19, '2020-10-15 03:11:37', '2020-09-28 05:02:33', 0, 0, 44, '2020-10-15 10:11:37'),
	(67, 19, '2020-10-15 03:12:27', '2020-10-01 04:17:16', 0, 0, 45, '2020-10-15 10:12:27'),
	(68, 19, '2020-10-15 04:57:40', '2020-10-12 04:50:04', 0, 0, 46, '2020-10-15 11:57:40'),
	(69, 19, '2020-10-23 07:29:11', '2020-10-21 06:13:55', 0, 0, 47, '2020-10-23 14:29:11'),
	(70, 19, '2020-10-28 07:09:57', '2020-10-26 02:25:07', 0, 0, 48, '2020-10-28 14:09:57'),
	(71, 19, '2020-11-05 07:58:46', '2020-11-02 06:23:16', 0, 0, 49, '2020-11-05 14:58:45'),
	(72, 19, '2020-11-12 08:25:25', '2020-11-09 02:24:09', 0, 0, 50, '2020-11-12 15:25:25'),
	(73, 19, '2020-11-18 04:04:41', '2020-11-16 08:02:32', 0, 0, 51, '2020-11-18 11:04:41'),
	(74, 19, '2020-11-25 02:59:26', '2020-11-24 03:03:01', 0, 0, 52, '2020-11-25 09:59:26'),
	(75, 19, '2020-12-04 04:50:42', '2020-12-01 07:02:37', 0, 0, 53, '2020-12-04 11:50:42'),
	(76, 19, '2020-12-11 04:32:36', '2020-12-07 06:26:58', 0, 0, 54, '2020-12-11 11:32:36'),
	(77, 19, '2020-12-17 08:25:14', '2020-12-14 06:19:03', 0, 0, 55, '2020-12-17 15:25:14'),
	(78, 19, '2021-01-05 02:55:43', '2020-12-22 02:42:12', 0, 0, 56, '2021-01-05 09:55:43'),
	(79, 19, '2021-01-07 06:45:49', '2021-01-05 03:00:07', 0, 0, 1, '2021-01-07 13:45:49'),
	(80, 19, '2021-02-17 07:10:57', '2021-01-11 05:13:50', 0, 1, 2, '2021-01-13 09:25:26'),
	(81, 19, '2021-01-21 02:51:29', '2021-01-18 06:43:22', 0, 0, 3, '2021-01-21 09:51:29'),
	(82, 19, '2021-01-27 07:33:06', '2021-01-26 03:16:07', 0, 0, 4, '2021-01-27 14:33:06'),
	(83, 19, '2021-02-02 08:53:09', '2021-02-01 08:35:30', 0, 0, 5, '2021-02-02 15:53:09'),
	(84, 19, '2021-02-10 06:29:17', '2021-02-08 06:13:13', 0, 0, 6, '2021-02-10 13:29:17'),
	(85, 19, '2021-02-18 08:13:02', '2021-02-16 04:29:08', 0, 0, 7, '2021-02-18 15:13:02'),
	(86, 19, '2021-02-25 02:42:47', '2021-02-22 07:25:06', 0, 0, 8, '2021-02-25 09:42:47'),
	(87, 19, '2021-03-02 08:51:59', '2021-03-01 03:11:48', 0, 0, 9, '2021-03-02 15:51:59'),
	(88, 19, '2021-03-10 03:36:41', '2021-03-08 06:30:34', 0, 0, 10, '2021-03-10 10:36:41'),
	(89, 19, '2021-03-20 06:01:27', '2021-03-16 03:44:50', 0, 0, 11, '2021-03-20 13:01:27'),
	(90, 19, '2021-03-25 14:33:53', '2021-03-22 04:08:36', 0, 0, 12, '2021-03-25 14:33:53'),
	(91, 19, '2021-05-05 15:42:54', '2021-03-29 14:26:17', 0, 0, 13, '2021-05-05 15:42:54'),
	(92, 19, '2021-04-03 13:09:41', '2021-04-01 09:45:29', 0, 0, 14, '2021-04-03 13:09:41'),
	(93, 19, '2021-04-15 09:26:38', '2021-04-12 10:12:46', 0, 0, 15, '2021-04-15 09:26:38'),
	(94, 19, '2021-05-05 15:39:11', '2021-04-19 11:12:06', 0, 0, 16, '2021-05-05 15:39:11'),
	(95, 19, '2021-04-29 09:56:50', '2021-04-27 10:07:15', 0, 0, 17, '2021-04-29 09:56:50'),
	(96, 10, '2021-05-05 11:00:50', '2021-05-03 11:21:50', 0, 0, 18, '2021-05-05 11:00:50'),
	(97, 19, '2021-05-28 12:40:06', '2021-05-27 12:37:02', 0, 0, 19, '2021-05-28 12:40:06'),
	(98, 19, '2021-06-04 09:41:47', '2021-06-02 12:48:24', 0, 0, 20, '2021-06-04 09:41:47'),
	(99, 19, '2021-06-09 09:34:17', '2021-06-07 11:19:04', 0, 0, 21, '2021-06-09 09:34:17'),
	(100, 19, '2021-06-16 09:32:34', '2021-06-14 11:34:31', 0, 0, 22, '2021-06-16 09:32:34'),
	(101, 19, '2021-06-23 11:56:08', '2021-06-21 13:07:42', 0, 0, 23, '2021-06-23 11:56:08'),
	(102, 19, '2021-06-29 11:57:13', '2021-06-28 11:11:53', 0, 0, 24, '2021-06-29 11:57:13'),
	(103, 19, '2021-07-10 11:44:14', '2021-07-05 11:42:48', 0, 0, 25, '2021-07-10 11:44:14'),
	(104, 19, '2021-07-10 11:45:04', '2021-07-05 11:59:13', 0, 0, 26, '2021-07-10 11:45:04'),
	(105, 19, '2021-08-09 12:02:13', '2021-07-16 11:17:27', 0, 1, 27, '2021-07-17 07:30:41');
/*!40000 ALTER TABLE `nd_rekam_faktur_pajak` ENABLE KEYS */;

-- Dumping structure for table favourtdj_system.nd_rekam_faktur_pajak_detail
DROP TABLE IF EXISTS `nd_rekam_faktur_pajak_detail`;
CREATE TABLE IF NOT EXISTS `nd_rekam_faktur_pajak_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rekam_faktur_pajak_id` int(11) DEFAULT NULL,
  `penjualan_id` int(11) DEFAULT NULL,
  `no_faktur_pajak` varchar(20) DEFAULT NULL,
  `no_faktur_pajak_id` int(11) DEFAULT NULL,
  `keterangan` varchar(200) DEFAULT NULL,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table favourtdj_system.nd_rekam_faktur_pajak_detail: ~0 rows (approximately)
DELETE FROM `nd_rekam_faktur_pajak_detail`;
/*!40000 ALTER TABLE `nd_rekam_faktur_pajak_detail` DISABLE KEYS */;
/*!40000 ALTER TABLE `nd_rekam_faktur_pajak_detail` ENABLE KEYS */;

-- Dumping structure for table favourtdj_system.nd_rekam_faktur_pajak_email
DROP TABLE IF EXISTS `nd_rekam_faktur_pajak_email`;
CREATE TABLE IF NOT EXISTS `nd_rekam_faktur_pajak_email` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rekam_faktur_pajak_id` mediumint(9) DEFAULT NULL,
  `customer_id` smallint(6) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `email_stat` tinyint(1) NOT NULL DEFAULT '0',
  `status_1` tinyint(4) NOT NULL DEFAULT '0',
  `status_2` tinyint(1) NOT NULL DEFAULT '0',
  `status_3` tinyint(1) NOT NULL DEFAULT '0',
  `status_4` tinyint(1) NOT NULL DEFAULT '0',
  `keterangan` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=962 DEFAULT CHARSET=latin1;

-- Dumping data for table favourtdj_system.nd_rekam_faktur_pajak_email: ~896 rows (approximately)
DELETE FROM `nd_rekam_faktur_pajak_email`;
/*!40000 ALTER TABLE `nd_rekam_faktur_pajak_email` DISABLE KEYS */;
INSERT INTO `nd_rekam_faktur_pajak_email` (`id`, `rekam_faktur_pajak_id`, `customer_id`, `created_at`, `updated_at`, `email_stat`, `status_1`, `status_2`, `status_3`, `status_4`, `keterangan`) VALUES
	(1, 28, 60, '2020-02-17 04:54:35', '2020-02-17 04:54:35', 0, 0, 1, 0, 0, ''),
	(2, 28, 16, '2020-02-17 04:54:38', '2020-02-17 04:54:38', 0, 0, 1, 0, 0, ''),
	(3, 28, 25, '2020-02-17 04:54:44', '2020-02-17 04:54:44', 0, 0, 1, 0, 0, ''),
	(4, 28, 35, '2020-02-17 04:54:57', '2020-02-17 04:54:57', 0, 0, 0, 1, 0, ''),
	(5, 28, 3, '2020-02-17 04:54:59', '2020-02-19 09:38:20', 1, 0, 1, 0, 0, ''),
	(6, 28, 208, '2020-02-17 05:30:48', '2020-02-17 05:30:48', 1, 0, 0, 0, 0, NULL),
	(7, 28, 50, '2020-02-17 05:30:49', '2020-02-17 05:30:49', 1, 0, 0, 0, 0, NULL),
	(8, 28, 196, '2020-02-17 05:30:49', '2020-02-17 05:30:49', 1, 0, 0, 0, 0, NULL),
	(9, 28, 118, '2020-02-17 05:30:49', '2020-02-17 05:30:49', 1, 0, 0, 0, 0, NULL),
	(10, 28, 61, '2020-02-17 05:30:49', '2020-02-17 05:30:49', 1, 0, 0, 0, 0, NULL),
	(11, 28, 130, '2020-02-17 05:30:49', '2020-02-17 05:30:49', 1, 0, 0, 0, 0, NULL),
	(12, 28, 189, '2020-02-17 05:30:49', '2020-02-17 05:30:49', 1, 0, 0, 0, 0, NULL),
	(13, 28, 89, '2020-02-17 05:30:49', '2020-02-17 05:30:49', 1, 0, 0, 0, 0, NULL),
	(14, 28, 33, '2020-02-17 05:30:49', '2020-02-17 05:30:49', 1, 0, 0, 0, 0, NULL),
	(15, 28, 66, '2020-02-17 05:58:20', '2020-02-17 05:58:20', 1, 0, 0, 0, 0, NULL),
	(16, 30, 16, '2020-02-19 08:49:50', '2020-02-19 08:49:50', 0, 0, 1, 0, 0, ''),
	(17, 30, 25, '2020-02-19 08:49:55', '2020-02-19 08:49:55', 0, 0, 1, 0, 0, ''),
	(18, 30, 13, '2020-02-19 08:50:04', '2020-02-19 08:50:04', 0, 0, 1, 0, 0, ''),
	(19, 30, 43, '2020-02-19 08:51:04', '2020-02-19 08:51:04', 1, 0, 0, 0, 0, NULL),
	(20, 30, 50, '2020-02-19 08:51:04', '2020-02-19 08:51:04', 1, 0, 0, 0, 0, NULL),
	(21, 30, 196, '2020-02-19 08:51:05', '2020-02-19 08:51:05', 1, 0, 0, 0, 0, NULL),
	(22, 30, 15, '2020-02-19 08:51:05', '2020-02-19 08:51:05', 1, 0, 0, 0, 0, NULL),
	(23, 30, 100, '2020-02-19 08:51:05', '2020-02-19 08:51:05', 1, 0, 0, 0, 0, NULL),
	(24, 30, 130, '2020-02-19 08:51:05', '2020-02-19 08:51:05', 1, 0, 0, 0, 0, NULL),
	(25, 30, 32, '2020-02-19 08:51:05', '2020-02-19 08:51:05', 1, 0, 0, 0, 0, NULL),
	(26, 30, 126, '2020-02-19 08:51:05', '2020-02-19 08:51:05', 1, 0, 0, 0, 0, NULL),
	(27, 30, 189, '2020-02-19 08:51:05', '2020-02-19 08:51:05', 1, 0, 0, 0, 0, NULL),
	(28, 30, 42, '2020-02-19 08:51:05', '2020-02-19 08:51:05', 1, 0, 0, 0, 0, NULL),
	(29, 30, 190, '2020-02-19 08:51:05', '2020-02-19 08:51:05', 1, 0, 0, 0, 0, NULL),
	(30, 30, 48, '2020-02-19 08:51:05', '2020-02-19 08:51:05', 1, 0, 0, 0, 0, NULL),
	(31, 30, 38, '2020-02-19 08:51:05', '2020-02-19 08:51:05', 1, 0, 0, 0, 0, NULL),
	(32, 31, 60, '2020-02-27 07:57:08', '2020-02-27 07:57:08', 0, 1, 0, 0, 0, ''),
	(33, 31, 25, '2020-02-27 07:57:13', '2020-02-27 07:57:13', 0, 0, 1, 0, 0, ''),
	(34, 31, 35, '2020-02-27 07:57:23', '2020-02-27 07:57:23', 0, 0, 0, 1, 0, ''),
	(35, 31, 208, '2020-02-27 07:57:53', '2020-02-27 07:57:53', 1, 0, 0, 0, 0, NULL),
	(36, 31, 43, '2020-02-27 07:57:54', '2020-02-27 07:57:54', 1, 0, 0, 0, 0, NULL),
	(37, 31, 100, '2020-02-27 07:57:54', '2020-02-27 07:57:54', 1, 0, 0, 0, 0, NULL),
	(38, 31, 32, '2020-02-27 07:57:54', '2020-02-27 07:57:54', 1, 0, 0, 0, 0, NULL),
	(39, 31, 129, '2020-02-27 07:57:54', '2020-02-27 07:57:54', 1, 0, 0, 0, 0, NULL),
	(40, 31, 24, '2020-02-27 07:57:54', '2020-02-27 07:57:54', 1, 0, 0, 0, 0, NULL),
	(41, 31, 46, '2020-02-27 07:57:54', '2020-02-27 07:57:54', 1, 0, 0, 0, 0, NULL),
	(42, 31, 48, '2020-02-27 07:57:54', '2020-02-27 07:57:54', 1, 0, 0, 0, 0, NULL),
	(43, 33, 60, '2020-03-03 04:27:10', '2020-03-03 04:27:10', 0, 0, 1, 0, 0, ''),
	(44, 33, 25, '2020-03-03 04:27:18', '2020-03-03 04:27:18', 0, 0, 1, 0, 0, ''),
	(45, 33, 169, '2020-03-03 04:53:40', '2020-03-03 04:53:40', 1, 0, 0, 0, 0, NULL),
	(46, 33, 297, '2020-03-03 04:53:40', '2020-03-03 04:53:40', 1, 0, 0, 0, 0, NULL),
	(47, 33, 208, '2020-03-03 04:53:40', '2020-03-03 04:53:40', 1, 0, 0, 0, 0, NULL),
	(48, 33, 50, '2020-03-03 04:53:40', '2020-03-03 04:53:40', 1, 0, 0, 0, 0, NULL),
	(49, 33, 91, '2020-03-03 04:53:40', '2020-03-03 04:53:40', 1, 0, 0, 0, 0, NULL),
	(50, 33, 46, '2020-03-03 04:53:40', '2020-03-03 04:53:40', 1, 0, 0, 0, 0, NULL),
	(51, 33, 124, '2020-03-03 04:53:40', '2020-03-03 04:53:40', 1, 0, 0, 0, 0, NULL),
	(52, 33, 151, '2020-03-03 04:53:40', '2020-03-03 04:53:40', 1, 0, 0, 0, 0, NULL),
	(53, 33, 48, '2020-03-03 04:53:40', '2020-03-03 04:53:40', 1, 0, 0, 0, 0, NULL),
	(54, 34, 60, '2020-03-10 09:23:50', '2020-03-10 09:23:50', 0, 1, 0, 0, 0, ''),
	(55, 34, 16, '2020-03-10 09:23:56', '2020-03-10 09:23:56', 0, 0, 1, 0, 0, ''),
	(56, 34, 25, '2020-03-10 09:24:00', '2020-03-10 09:24:00', 0, 0, 1, 0, 0, ''),
	(57, 34, 98, '2020-03-10 09:24:08', '2020-03-10 09:24:08', 0, 0, 1, 0, 0, ''),
	(58, 34, 208, '2020-03-10 09:29:02', '2020-03-10 09:29:02', 1, 0, 0, 0, 0, NULL),
	(59, 34, 156, '2020-03-10 09:29:02', '2020-03-10 09:29:02', 1, 0, 0, 0, 0, NULL),
	(60, 34, 312, '2020-03-10 09:29:02', '2020-03-10 09:29:02', 1, 0, 0, 0, 0, NULL),
	(61, 34, 303, '2020-03-10 09:29:02', '2020-03-10 09:29:02', 1, 0, 0, 0, 0, NULL),
	(62, 34, 100, '2020-03-10 09:29:02', '2020-03-10 09:29:02', 1, 0, 0, 0, 0, NULL),
	(63, 34, 130, '2020-03-10 09:29:02', '2020-03-10 09:29:02', 1, 0, 0, 0, 0, NULL),
	(64, 34, 129, '2020-03-10 09:29:02', '2020-03-10 09:29:02', 1, 0, 0, 0, 0, NULL),
	(65, 34, 138, '2020-03-10 09:29:02', '2020-03-10 09:29:02', 1, 0, 0, 0, 0, NULL),
	(66, 34, 189, '2020-03-10 09:29:02', '2020-03-10 09:29:02', 1, 0, 0, 0, 0, NULL),
	(67, 34, 3, '2020-03-10 09:29:02', '2020-03-10 09:29:02', 1, 0, 0, 0, 0, NULL),
	(68, 32, 25, '2020-03-16 04:29:06', '2020-03-16 04:29:14', 0, 0, 0, 0, 1, 'late upload'),
	(69, 32, 94, '2020-03-16 04:29:51', '2020-03-16 04:29:51', 1, 0, 0, 0, 0, NULL),
	(70, 35, 25, '2020-03-17 05:44:07', '2020-03-17 05:44:07', 0, 0, 1, 0, 0, ''),
	(71, 35, 60, '2020-03-17 05:44:17', '2020-03-17 05:44:17', 0, 1, 0, 0, 0, ''),
	(72, 35, 312, '2020-03-17 05:44:54', '2020-03-17 05:44:54', 1, 0, 0, 0, 0, NULL),
	(73, 35, 163, '2020-03-17 05:44:55', '2020-03-17 05:44:55', 1, 0, 0, 0, 0, NULL),
	(74, 35, 303, '2020-03-17 05:44:55', '2020-03-17 05:44:55', 1, 0, 0, 0, 0, NULL),
	(75, 35, 130, '2020-03-17 05:44:56', '2020-03-17 05:44:56', 1, 0, 0, 0, 0, NULL),
	(76, 35, 74, '2020-03-17 05:44:56', '2020-03-17 05:44:56', 1, 0, 0, 0, 0, NULL),
	(77, 35, 202, '2020-03-17 05:44:57', '2020-03-17 05:44:57', 1, 0, 0, 0, 0, NULL),
	(78, 35, 190, '2020-03-17 05:44:57', '2020-03-17 05:44:57', 1, 0, 0, 0, 0, NULL),
	(79, 35, 124, '2020-03-17 05:44:58', '2020-03-17 05:44:58', 1, 0, 0, 0, 0, NULL),
	(80, 36, 98, '2020-03-26 06:37:09', '2020-03-26 06:37:09', 0, 0, 1, 0, 0, ''),
	(81, 36, 60, '2020-03-26 06:37:13', '2020-03-26 06:37:13', 0, 1, 0, 0, 0, ''),
	(82, 36, 327, '2020-03-26 06:37:18', '2020-03-26 06:37:18', 0, 0, 1, 0, 0, ''),
	(83, 36, 25, '2020-03-26 06:37:23', '2020-03-26 06:37:23', 0, 0, 1, 0, 0, ''),
	(84, 36, 35, '2020-03-26 06:37:28', '2020-03-26 06:37:28', 0, 0, 0, 1, 0, ''),
	(85, 36, 331, '2020-04-06 03:16:53', '2020-04-06 03:16:53', 1, 0, 0, 0, 0, NULL),
	(86, 36, 312, '2020-04-06 03:16:53', '2020-04-06 03:16:53', 1, 0, 0, 0, 0, NULL),
	(87, 36, 163, '2020-04-06 03:16:53', '2020-04-06 03:16:53', 1, 0, 0, 0, 0, NULL),
	(88, 36, 91, '2020-04-06 03:16:53', '2020-04-06 03:16:53', 1, 0, 0, 0, 0, NULL),
	(89, 36, 327, '2020-04-06 03:16:53', '2020-04-06 03:16:53', 1, 0, 0, 0, 0, NULL),
	(90, 36, 94, '2020-04-06 03:16:53', '2020-04-06 03:16:53', 1, 0, 0, 0, 0, NULL),
	(91, 36, 130, '2020-04-06 03:16:53', '2020-04-06 03:16:53', 1, 0, 0, 0, 0, NULL),
	(92, 36, 32, '2020-04-06 03:16:53', '2020-04-06 03:16:53', 1, 0, 0, 0, 0, NULL),
	(93, 36, 24, '2020-04-06 03:16:53', '2020-04-06 03:16:53', 1, 0, 0, 0, 0, NULL),
	(94, 36, 42, '2020-04-06 03:16:53', '2020-04-06 03:16:53', 1, 0, 0, 0, 0, NULL),
	(95, 36, 190, '2020-04-06 03:16:53', '2020-04-06 03:16:53', 1, 0, 0, 0, 0, NULL),
	(96, 37, 2, '2020-04-06 03:21:06', '2020-04-06 03:21:16', 0, 0, 0, 0, 1, 'tidak diambil'),
	(97, 37, 25, '2020-04-06 03:21:34', '2020-04-06 03:21:34', 0, 0, 1, 0, 0, ''),
	(98, 37, 353, '2020-04-06 03:44:21', '2020-04-06 03:44:41', 0, 0, 0, 0, 1, 'belum diketahui'),
	(99, 37, 35, '2020-04-06 03:47:44', '2020-04-06 03:47:52', 0, 0, 0, 0, 1, 'nunggu email'),
	(100, 37, 250, '2020-04-06 03:48:11', '2020-04-06 03:48:11', 1, 0, 0, 0, 0, NULL),
	(101, 37, 118, '2020-04-06 03:48:11', '2020-04-06 03:48:11', 1, 0, 0, 0, 0, NULL),
	(102, 37, 172, '2020-04-06 03:48:11', '2020-04-06 03:48:11', 1, 0, 0, 0, 0, NULL),
	(103, 37, 74, '2020-04-06 03:48:11', '2020-04-06 03:48:11', 1, 0, 0, 0, 0, NULL),
	(104, 37, 46, '2020-04-06 03:48:12', '2020-04-06 03:48:12', 1, 0, 0, 0, 0, NULL),
	(105, 37, 48, '2020-04-06 03:48:12', '2020-04-06 03:48:12', 1, 0, 0, 0, 0, NULL),
	(106, 39, 2, '2020-04-17 06:27:34', '2020-04-17 06:27:41', 0, 0, 0, 0, 1, 'tidak diambil'),
	(107, 39, 25, '2020-04-17 06:27:48', '2020-04-17 06:27:48', 0, 0, 1, 0, 0, ''),
	(108, 39, 364, '2020-04-17 06:27:57', '2020-04-17 06:28:01', 0, 0, 0, 0, 1, 'belum tahu'),
	(109, 39, 35, '2020-04-17 06:28:06', '2020-04-17 06:28:06', 0, 0, 1, 0, 0, ''),
	(110, 39, 43, '2020-04-17 06:31:14', '2020-04-17 06:31:14', 1, 0, 0, 0, 0, NULL),
	(111, 39, 91, '2020-04-17 06:31:14', '2020-04-17 06:31:14', 1, 0, 0, 0, 0, NULL),
	(112, 39, 54, '2020-04-17 06:31:14', '2020-04-17 06:31:14', 1, 0, 0, 0, 0, NULL),
	(113, 39, 61, '2020-04-17 06:31:14', '2020-04-17 06:31:14', 1, 0, 0, 0, 0, NULL),
	(114, 39, 386, '2020-04-17 06:31:14', '2020-04-17 06:31:14', 1, 0, 0, 0, 0, NULL),
	(115, 39, 187, '2020-04-17 06:31:14', '2020-04-17 06:31:14', 1, 0, 0, 0, 0, NULL),
	(116, 39, 189, '2020-04-17 06:31:14', '2020-04-17 06:31:14', 1, 0, 0, 0, 0, NULL),
	(117, 39, 89, '2020-04-17 06:31:14', '2020-04-17 06:31:14', 1, 0, 0, 0, 0, NULL),
	(118, 40, 25, '2020-04-24 04:45:45', '2020-04-24 04:45:45', 0, 0, 1, 0, 0, ''),
	(119, 40, 364, '2020-04-24 04:45:49', '2020-04-24 04:45:49', 0, 0, 1, 0, 0, ''),
	(120, 40, 353, '2020-04-24 04:45:52', '2020-04-24 04:46:01', 0, 0, 0, 0, 1, 'belum tahu'),
	(121, 40, 430, '2020-04-24 04:46:04', '2020-04-24 04:46:04', 0, 0, 1, 0, 0, ''),
	(122, 40, 35, '2020-04-24 04:46:07', '2020-04-24 04:46:19', 0, 0, 0, 1, 0, ''),
	(123, 40, 297, '2020-04-25 02:16:38', '2020-04-25 02:16:38', 1, 0, 0, 0, 0, NULL),
	(124, 40, 171, '2020-04-25 02:16:38', '2020-04-25 02:16:38', 1, 0, 0, 0, 0, NULL),
	(125, 40, 100, '2020-04-25 02:16:38', '2020-04-25 02:16:38', 1, 0, 0, 0, 0, NULL),
	(126, 40, 118, '2020-04-25 02:16:38', '2020-04-25 02:16:38', 1, 0, 0, 0, 0, NULL),
	(127, 40, 386, '2020-04-25 02:16:38', '2020-04-25 02:16:38', 1, 0, 0, 0, 0, NULL),
	(128, 40, 187, '2020-04-25 02:16:38', '2020-04-25 02:16:38', 1, 0, 0, 0, 0, NULL),
	(129, 40, 130, '2020-04-25 02:16:38', '2020-04-25 02:16:38', 1, 0, 0, 0, 0, NULL),
	(130, 40, 353, '2020-04-25 06:57:34', '2020-04-25 06:57:34', 1, 0, 0, 0, 0, NULL),
	(131, 37, 353, '2020-04-25 07:41:48', '2020-04-25 07:41:48', 1, 0, 0, 0, 0, NULL),
	(132, 37, 353, '2020-04-25 07:42:00', '2020-04-25 07:42:00', 1, 0, 0, 0, 0, NULL),
	(133, 41, 25, '2020-04-30 03:39:42', '2020-04-30 03:39:42', 0, 0, 1, 0, 0, ''),
	(134, 41, 364, '2020-04-30 03:39:45', '2020-04-30 03:39:46', 0, 0, 1, 0, 0, ''),
	(135, 41, 430, '2020-04-30 03:39:48', '2020-04-30 03:39:48', 0, 0, 1, 0, 0, ''),
	(136, 41, 43, '2020-04-30 03:40:02', '2020-04-30 03:40:02', 1, 0, 0, 0, 0, NULL),
	(137, 41, 468, '2020-04-30 03:40:02', '2020-04-30 03:40:02', 1, 0, 0, 0, 0, NULL),
	(138, 41, 83, '2020-04-30 03:40:02', '2020-04-30 03:40:02', 1, 0, 0, 0, 0, NULL),
	(139, 41, 175, '2020-04-30 03:40:02', '2020-04-30 03:40:02', 1, 0, 0, 0, 0, NULL),
	(140, 41, 130, '2020-04-30 03:40:02', '2020-04-30 03:40:02', 1, 0, 0, 0, 0, NULL),
	(141, 41, 129, '2020-04-30 03:40:02', '2020-04-30 03:40:02', 1, 0, 0, 0, 0, NULL),
	(142, 41, 189, '2020-04-30 03:40:02', '2020-04-30 03:40:02', 1, 0, 0, 0, 0, NULL),
	(143, 41, 48, '2020-04-30 03:40:02', '2020-04-30 03:40:02', 1, 0, 0, 0, 0, NULL),
	(144, 42, 485, '2020-05-05 06:49:29', '2020-05-05 06:49:35', 0, 0, 0, 0, 1, 'nanti ya'),
	(145, 42, 129, '2020-05-05 06:49:52', '2020-05-05 06:49:52', 1, 0, 0, 0, 0, NULL),
	(146, 43, 60, '2020-05-12 04:23:21', '2020-05-12 04:23:21', 0, 1, 0, 0, 0, ''),
	(147, 43, 297, '2020-05-12 04:48:37', '2020-05-12 04:48:37', 1, 0, 0, 0, 0, NULL),
	(148, 43, 100, '2020-05-12 04:48:37', '2020-05-12 04:48:37', 1, 0, 0, 0, 0, NULL),
	(149, 43, 83, '2020-05-12 04:48:38', '2020-05-12 04:48:38', 1, 0, 0, 0, 0, NULL),
	(150, 43, 485, '2020-05-12 04:48:38', '2020-05-12 04:48:38', 1, 0, 0, 0, 0, NULL),
	(151, 43, 498, '2020-05-12 04:48:38', '2020-05-12 04:48:38', 1, 0, 0, 0, 0, NULL),
	(152, 43, 189, '2020-05-12 04:48:38', '2020-05-12 04:48:38', 1, 0, 0, 0, 0, NULL),
	(153, 43, 48, '2020-05-12 04:48:38', '2020-05-12 04:48:38', 1, 0, 0, 0, 0, NULL),
	(154, 44, 60, '2020-05-16 02:45:26', '2020-05-16 02:45:26', 0, 1, 0, 0, 0, ''),
	(155, 44, 25, '2020-05-16 02:45:34', '2020-05-16 02:45:34', 0, 1, 0, 0, 0, ''),
	(156, 44, 16, '2020-05-16 02:46:13', '2020-05-16 02:46:13', 0, 1, 0, 0, 0, ''),
	(157, 44, 148, '2020-05-16 02:46:30', '2020-05-16 02:46:30', 1, 0, 0, 0, 0, NULL),
	(158, 44, 100, '2020-05-16 02:46:30', '2020-05-16 02:46:30', 1, 0, 0, 0, 0, NULL),
	(159, 44, 54, '2020-05-16 02:46:30', '2020-05-16 02:46:30', 1, 0, 0, 0, 0, NULL),
	(160, 44, 32, '2020-05-16 02:46:30', '2020-05-16 02:46:30', 1, 0, 0, 0, 0, NULL),
	(161, 44, 485, '2020-05-16 02:46:30', '2020-05-16 02:46:30', 1, 0, 0, 0, 0, NULL),
	(162, 45, 187, '2020-05-26 06:40:14', '2020-05-26 06:40:14', 1, 0, 0, 0, 0, NULL),
	(163, 45, 130, '2020-05-26 06:40:14', '2020-05-26 06:40:14', 1, 0, 0, 0, 0, NULL),
	(164, 45, 498, '2020-05-26 06:40:14', '2020-05-26 06:40:14', 1, 0, 0, 0, 0, NULL),
	(165, 47, 525, '2020-05-26 06:40:52', '2020-05-26 06:40:52', 1, 0, 0, 0, 0, NULL),
	(166, 39, 35, '2020-06-03 04:18:38', '2020-06-03 04:18:38', 1, 0, 0, 0, 0, NULL),
	(167, 39, 35, '2020-06-03 04:18:52', '2020-06-03 04:18:52', 1, 0, 0, 0, 0, NULL),
	(168, 40, 35, '2020-06-03 04:19:24', '2020-06-03 04:19:24', 1, 0, 0, 0, 0, NULL),
	(169, 48, 529, '2020-06-09 07:27:56', '2020-06-09 07:28:01', 0, 0, 0, 0, 1, 'belum tahu'),
	(170, 48, 16, '2020-06-09 07:28:05', '2020-06-09 07:28:05', 0, 1, 0, 0, 0, ''),
	(171, 48, 25, '2020-06-09 07:52:20', '2020-06-09 07:52:20', 0, 0, 1, 0, 0, ''),
	(172, 48, 297, '2020-06-09 07:52:34', '2020-06-09 07:52:34', 1, 0, 0, 0, 0, NULL),
	(173, 48, 551, '2020-06-09 07:52:34', '2020-06-09 07:52:34', 1, 0, 0, 0, 0, NULL),
	(174, 48, 100, '2020-06-09 07:52:34', '2020-06-09 07:52:34', 1, 0, 0, 0, 0, NULL),
	(175, 48, 130, '2020-06-09 07:52:34', '2020-06-09 07:52:34', 1, 0, 0, 0, 0, NULL),
	(176, 48, 220, '2020-06-09 07:52:34', '2020-06-09 07:52:34', 1, 0, 0, 0, 0, NULL),
	(177, 48, 53, '2020-06-09 07:52:34', '2020-06-09 07:52:34', 1, 0, 0, 0, 0, NULL),
	(178, 48, 35, '2020-06-09 07:52:34', '2020-06-09 07:52:34', 1, 0, 0, 0, 0, NULL),
	(179, 48, 151, '2020-06-09 07:52:34', '2020-06-09 07:52:34', 1, 0, 0, 0, 0, NULL),
	(180, 42, 485, '2020-06-15 07:05:56', '2020-06-15 07:05:56', 1, 0, 0, 0, 0, NULL),
	(181, 42, 485, '2020-06-15 07:06:03', '2020-06-15 07:06:03', 1, 0, 0, 0, 0, NULL),
	(182, 50, 144, '2020-06-17 03:57:14', '2020-06-17 03:57:21', 0, 0, 0, 0, 1, 'belum diketahui'),
	(183, 50, 551, '2020-06-17 03:57:39', '2020-06-17 03:57:39', 1, 0, 0, 0, 0, NULL),
	(184, 50, 83, '2020-06-17 03:57:39', '2020-06-17 03:57:39', 1, 0, 0, 0, 0, NULL),
	(185, 50, 61, '2020-06-17 03:57:39', '2020-06-17 03:57:39', 1, 0, 0, 0, 0, NULL),
	(186, 50, 607, '2020-06-17 03:57:39', '2020-06-17 03:57:39', 1, 0, 0, 0, 0, NULL),
	(187, 49, 60, '2020-06-17 04:03:33', '2020-06-17 04:03:39', 0, 1, 0, 0, 0, ''),
	(188, 49, 25, '2020-06-17 04:03:46', '2020-06-17 04:03:46', 0, 0, 1, 0, 0, ''),
	(189, 49, 598, '2020-06-17 06:57:00', '2020-06-17 06:57:00', 0, 0, 0, 0, 1, NULL),
	(190, 49, 331, '2020-06-17 06:57:26', '2020-06-17 06:57:26', 1, 0, 0, 0, 0, NULL),
	(191, 49, 148, '2020-06-17 06:57:26', '2020-06-17 06:57:26', 1, 0, 0, 0, 0, NULL),
	(192, 49, 91, '2020-06-17 06:57:26', '2020-06-17 06:57:26', 1, 0, 0, 0, 0, NULL),
	(193, 49, 327, '2020-06-17 06:57:26', '2020-06-17 06:57:26', 1, 0, 0, 0, 0, NULL),
	(194, 49, 83, '2020-06-17 06:57:26', '2020-06-17 06:57:26', 1, 0, 0, 0, 0, NULL),
	(195, 49, 94, '2020-06-17 06:57:26', '2020-06-17 06:57:26', 1, 0, 0, 0, 0, NULL),
	(196, 49, 130, '2020-06-17 06:57:26', '2020-06-17 06:57:26', 1, 0, 0, 0, 0, NULL),
	(197, 49, 578, '2020-06-17 06:57:26', '2020-06-17 06:57:26', 1, 0, 0, 0, 0, NULL),
	(198, 49, 53, '2020-06-17 06:57:26', '2020-06-17 06:57:26', 1, 0, 0, 0, 0, NULL),
	(199, 49, 525, '2020-06-17 06:57:26', '2020-06-17 06:57:26', 1, 0, 0, 0, 0, NULL),
	(200, 49, 35, '2020-06-17 06:57:26', '2020-06-17 06:57:26', 1, 0, 0, 0, 0, NULL),
	(201, 49, 124, '2020-06-17 06:57:26', '2020-06-17 06:57:26', 1, 0, 0, 0, 0, NULL),
	(202, 49, 48, '2020-06-17 06:57:26', '2020-06-17 06:57:26', 1, 0, 0, 0, 0, NULL),
	(203, 51, 529, '2020-06-26 03:42:47', '2020-06-26 03:42:47', 0, 0, 1, 0, 0, ''),
	(204, 51, 60, '2020-06-26 03:42:51', '2020-06-26 03:42:51', 0, 1, 0, 0, 0, ''),
	(205, 51, 16, '2020-06-26 03:42:54', '2020-06-26 03:42:54', 0, 0, 1, 0, 0, ''),
	(206, 51, 331, '2020-06-26 03:43:05', '2020-06-26 03:43:05', 1, 0, 0, 0, 0, NULL),
	(207, 51, 83, '2020-06-26 03:43:05', '2020-06-26 03:43:05', 1, 0, 0, 0, 0, NULL),
	(208, 51, 130, '2020-06-26 03:43:05', '2020-06-26 03:43:05', 1, 0, 0, 0, 0, NULL),
	(209, 51, 578, '2020-06-26 03:43:05', '2020-06-26 03:43:05', 1, 0, 0, 0, 0, NULL),
	(210, 51, 48, '2020-06-26 03:43:05', '2020-06-26 03:43:05', 1, 0, 0, 0, 0, NULL),
	(211, 52, 25, '2020-06-30 07:46:44', '2020-06-30 07:46:44', 0, 0, 1, 0, 0, ''),
	(212, 52, 312, '2020-07-04 04:48:35', '2020-07-04 04:48:35', 1, 0, 0, 0, 0, NULL),
	(213, 52, 100, '2020-07-04 04:48:35', '2020-07-04 04:48:35', 1, 0, 0, 0, 0, NULL),
	(214, 52, 83, '2020-07-04 04:48:35', '2020-07-04 04:48:35', 1, 0, 0, 0, 0, NULL),
	(215, 52, 94, '2020-07-04 04:48:35', '2020-07-04 04:48:35', 1, 0, 0, 0, 0, NULL),
	(216, 52, 498, '2020-07-04 04:48:35', '2020-07-04 04:48:35', 1, 0, 0, 0, 0, NULL),
	(217, 52, 35, '2020-07-04 04:48:35', '2020-07-04 04:48:35', 1, 0, 0, 0, 0, NULL),
	(218, 53, 196, '2020-07-04 04:49:23', '2020-07-04 04:49:23', 1, 0, 0, 0, 0, NULL),
	(219, 53, 130, '2020-07-04 04:49:23', '2020-07-04 04:49:23', 1, 0, 0, 0, 0, NULL),
	(220, 53, 220, '2020-07-04 04:49:23', '2020-07-04 04:49:23', 1, 0, 0, 0, 0, NULL),
	(221, 54, 16, '2020-07-08 06:03:24', '2020-07-08 06:03:24', 0, 0, 1, 0, 0, ''),
	(222, 54, 529, '2020-07-08 06:03:33', '2020-07-08 06:04:10', 0, 0, 0, 0, 1, 'TIDAK BUTUH FAKTUR'),
	(223, 54, 148, '2020-07-08 06:05:00', '2020-07-08 06:05:00', 1, 0, 0, 0, 0, NULL),
	(224, 54, 312, '2020-07-08 06:05:00', '2020-07-08 06:05:00', 1, 0, 0, 0, 0, NULL),
	(225, 54, 174, '2020-07-08 06:05:00', '2020-07-08 06:05:00', 1, 0, 0, 0, 0, NULL),
	(226, 54, 61, '2020-07-08 06:05:00', '2020-07-08 06:05:00', 1, 0, 0, 0, 0, NULL),
	(227, 54, 130, '2020-07-08 06:05:00', '2020-07-08 06:05:00', 1, 0, 0, 0, 0, NULL),
	(228, 54, 53, '2020-07-08 06:05:00', '2020-07-08 06:05:00', 1, 0, 0, 0, 0, NULL),
	(229, 54, 498, '2020-07-08 06:05:00', '2020-07-08 06:05:00', 1, 0, 0, 0, 0, NULL),
	(230, 54, 35, '2020-07-08 06:05:00', '2020-07-08 06:05:00', 1, 0, 0, 0, 0, NULL),
	(231, 54, 151, '2020-07-08 06:05:00', '2020-07-08 06:05:00', 1, 0, 0, 0, 0, NULL),
	(232, 54, 48, '2020-07-08 06:05:00', '2020-07-08 06:05:00', 1, 0, 0, 0, 0, NULL),
	(233, 55, 148, '2020-07-15 02:02:43', '2020-07-15 02:02:43', 1, 0, 0, 0, 0, NULL),
	(234, 55, 91, '2020-07-15 02:02:43', '2020-07-15 02:02:43', 1, 0, 0, 0, 0, NULL),
	(235, 55, 83, '2020-07-15 02:02:43', '2020-07-15 02:02:43', 1, 0, 0, 0, 0, NULL),
	(236, 55, 736, '2020-07-15 02:02:43', '2020-07-15 02:02:43', 1, 0, 0, 0, 0, NULL),
	(237, 55, 130, '2020-07-15 02:02:43', '2020-07-15 02:02:43', 1, 0, 0, 0, 0, NULL),
	(238, 55, 32, '2020-07-15 02:02:43', '2020-07-15 02:02:43', 1, 0, 0, 0, 0, NULL),
	(239, 55, 190, '2020-07-15 02:02:43', '2020-07-15 02:02:43', 1, 0, 0, 0, 0, NULL),
	(240, 55, 35, '2020-07-15 02:02:43', '2020-07-15 02:02:43', 1, 0, 0, 0, 0, NULL),
	(241, 55, 48, '2020-07-15 02:02:43', '2020-07-15 02:02:43', 1, 0, 0, 0, 0, NULL),
	(242, 56, 60, '2020-07-22 02:55:06', '2020-07-22 02:55:06', 0, 1, 0, 0, 0, ''),
	(243, 56, 16, '2020-07-22 02:55:10', '2020-07-22 02:55:10', 0, 0, 1, 0, 0, ''),
	(244, 56, 208, '2020-07-22 02:55:43', '2020-07-22 02:55:43', 1, 0, 0, 0, 0, NULL),
	(245, 56, 54, '2020-07-22 02:55:43', '2020-07-22 02:55:43', 1, 0, 0, 0, 0, NULL),
	(246, 56, 94, '2020-07-22 02:55:43', '2020-07-22 02:55:43', 1, 0, 0, 0, 0, NULL),
	(247, 56, 130, '2020-07-22 02:55:43', '2020-07-22 02:55:43', 1, 0, 0, 0, 0, NULL),
	(248, 56, 48, '2020-07-22 02:55:43', '2020-07-22 02:55:43', 1, 0, 0, 0, 0, NULL),
	(249, 57, 529, '2020-07-29 03:22:41', '2020-07-29 03:22:51', 0, 0, 0, 0, 1, 'tidak butuh faktur'),
	(250, 57, 60, '2020-07-29 03:22:58', '2020-07-29 03:22:58', 0, 1, 0, 0, 0, ''),
	(251, 57, 148, '2020-07-29 03:23:26', '2020-07-29 03:23:26', 1, 0, 0, 0, 0, NULL),
	(252, 57, 196, '2020-07-29 03:23:26', '2020-07-29 03:23:26', 1, 0, 0, 0, 0, NULL),
	(253, 57, 91, '2020-07-29 03:23:26', '2020-07-29 03:23:26', 1, 0, 0, 0, 0, NULL),
	(254, 57, 175, '2020-07-29 03:23:26', '2020-07-29 03:23:26', 1, 0, 0, 0, 0, NULL),
	(255, 57, 130, '2020-07-29 03:23:26', '2020-07-29 03:23:26', 1, 0, 0, 0, 0, NULL),
	(256, 57, 129, '2020-07-29 03:23:26', '2020-07-29 03:23:26', 1, 0, 0, 0, 0, NULL),
	(257, 57, 123, '2020-07-29 03:23:26', '2020-07-29 03:23:26', 1, 0, 0, 0, 0, NULL),
	(258, 57, 485, '2020-07-29 03:23:26', '2020-07-29 03:23:26', 1, 0, 0, 0, 0, NULL),
	(259, 57, 48, '2020-07-29 03:23:26', '2020-07-29 03:23:26', 1, 0, 0, 0, 0, NULL),
	(260, 58, 331, '2020-08-06 06:19:32', '2020-08-06 06:19:32', 1, 0, 0, 0, 0, NULL),
	(261, 58, 148, '2020-08-06 06:19:32', '2020-08-06 06:19:32', 1, 0, 0, 0, 0, NULL),
	(262, 58, 118, '2020-08-06 06:19:32', '2020-08-06 06:19:32', 1, 0, 0, 0, 0, NULL),
	(263, 58, 172, '2020-08-06 06:19:32', '2020-08-06 06:19:32', 1, 0, 0, 0, 0, NULL),
	(264, 58, 35, '2020-08-06 06:19:32', '2020-08-06 06:19:32', 1, 0, 0, 0, 0, NULL),
	(265, 59, 25, '2020-08-12 05:57:17', '2020-08-12 05:57:17', 0, 0, 1, 0, 0, ''),
	(266, 59, 148, '2020-08-12 05:57:35', '2020-08-12 05:57:35', 1, 0, 0, 0, 0, NULL),
	(267, 59, 223, '2020-08-12 05:57:35', '2020-08-12 05:57:35', 1, 0, 0, 0, 0, NULL),
	(268, 59, 61, '2020-08-12 05:57:35', '2020-08-12 05:57:35', 1, 0, 0, 0, 0, NULL),
	(269, 59, 130, '2020-08-12 05:57:35', '2020-08-12 05:57:35', 1, 0, 0, 0, 0, NULL),
	(270, 59, 32, '2020-08-12 05:57:35', '2020-08-12 05:57:35', 1, 0, 0, 0, 0, NULL),
	(271, 59, 829, '2020-08-12 05:57:35', '2020-08-12 05:57:35', 1, 0, 0, 0, 0, NULL),
	(272, 59, 837, '2020-08-12 05:57:35', '2020-08-12 05:57:35', 1, 0, 0, 0, 0, NULL),
	(273, 59, 190, '2020-08-12 05:57:35', '2020-08-12 05:57:35', 1, 0, 0, 0, 0, NULL),
	(274, 59, 151, '2020-08-12 05:57:35', '2020-08-12 05:57:35', 1, 0, 0, 0, 0, NULL),
	(275, 60, 60, '2020-08-22 01:58:11', '2020-08-22 01:58:11', 0, 1, 0, 0, 0, ''),
	(276, 60, 25, '2020-08-22 01:58:17', '2020-08-22 01:58:17', 0, 0, 1, 0, 0, ''),
	(277, 60, 529, '2020-08-22 01:58:22', '2020-08-22 01:58:27', 0, 0, 0, 0, 1, 'tidak butuh faktur'),
	(278, 60, 98, '2020-08-22 01:59:51', '2020-08-22 01:59:51', 0, 0, 1, 0, 0, ''),
	(279, 60, 148, '2020-08-22 02:00:23', '2020-08-22 02:00:23', 1, 0, 0, 0, 0, NULL),
	(280, 60, 91, '2020-08-22 02:00:23', '2020-08-22 02:00:23', 1, 0, 0, 0, 0, NULL),
	(281, 60, 130, '2020-08-22 02:00:23', '2020-08-22 02:00:23', 1, 0, 0, 0, 0, NULL),
	(282, 60, 32, '2020-08-22 02:00:23', '2020-08-22 02:00:23', 1, 0, 0, 0, 0, NULL),
	(283, 60, 220, '2020-08-22 02:00:23', '2020-08-22 02:00:23', 1, 0, 0, 0, 0, NULL),
	(284, 60, 485, '2020-08-22 02:00:23', '2020-08-22 02:00:23', 1, 0, 0, 0, 0, NULL),
	(285, 60, 126, '2020-08-22 02:00:23', '2020-08-22 02:00:23', 1, 0, 0, 0, 0, NULL),
	(286, 60, 132, '2020-08-22 02:00:23', '2020-08-22 02:00:23', 1, 0, 0, 0, 0, NULL),
	(287, 60, 837, '2020-08-22 02:00:23', '2020-08-22 02:00:23', 1, 0, 0, 0, 0, NULL),
	(288, 60, 35, '2020-08-22 02:00:23', '2020-08-22 02:00:23', 1, 0, 0, 0, 0, NULL),
	(289, 60, 48, '2020-08-22 02:00:23', '2020-08-22 02:00:23', 1, 0, 0, 0, 0, NULL),
	(290, 61, 25, '2020-08-26 05:59:21', '2020-08-26 05:59:21', 0, 0, 1, 0, 0, ''),
	(291, 61, 331, '2020-08-26 05:59:30', '2020-08-26 05:59:30', 1, 0, 0, 0, 0, NULL),
	(292, 61, 172, '2020-08-26 05:59:30', '2020-08-26 05:59:30', 1, 0, 0, 0, 0, NULL),
	(293, 61, 48, '2020-08-26 05:59:30', '2020-08-26 05:59:30', 1, 0, 0, 0, 0, NULL),
	(294, 62, 16, '2020-09-04 04:30:02', '2020-09-04 04:30:02', 0, 1, 0, 0, 0, ''),
	(295, 62, 331, '2020-09-04 04:30:21', '2020-09-04 04:30:21', 1, 0, 0, 0, 0, NULL),
	(296, 62, 148, '2020-09-04 04:30:21', '2020-09-04 04:30:21', 1, 0, 0, 0, 0, NULL),
	(297, 62, 156, '2020-09-04 04:30:21', '2020-09-04 04:30:21', 1, 0, 0, 0, 0, NULL),
	(298, 62, 104, '2020-09-04 04:30:21', '2020-09-04 04:30:21', 1, 0, 0, 0, 0, NULL),
	(299, 62, 223, '2020-09-04 04:30:21', '2020-09-04 04:30:21', 1, 0, 0, 0, 0, NULL),
	(300, 62, 91, '2020-09-04 04:30:21', '2020-09-04 04:30:21', 1, 0, 0, 0, 0, NULL),
	(301, 62, 118, '2020-09-04 04:30:21', '2020-09-04 04:30:21', 1, 0, 0, 0, 0, NULL),
	(302, 62, 187, '2020-09-04 04:30:21', '2020-09-04 04:30:21', 1, 0, 0, 0, 0, NULL),
	(303, 62, 910, '2020-09-04 04:30:21', '2020-09-04 04:30:21', 1, 0, 0, 0, 0, NULL),
	(304, 62, 837, '2020-09-04 04:30:21', '2020-09-04 04:30:21', 1, 0, 0, 0, 0, NULL),
	(305, 62, 190, '2020-09-04 04:30:21', '2020-09-04 04:30:21', 1, 0, 0, 0, 0, NULL),
	(306, 62, 35, '2020-09-04 04:30:21', '2020-09-04 04:30:21', 1, 0, 0, 0, 0, NULL),
	(307, 62, 151, '2020-09-04 04:30:21', '2020-09-04 04:30:21', 1, 0, 0, 0, 0, NULL),
	(308, 62, 48, '2020-09-04 04:30:21', '2020-09-04 04:30:21', 1, 0, 0, 0, 0, NULL),
	(309, 63, 529, '2020-09-10 06:44:10', '2020-09-10 06:44:15', 0, 0, 0, 0, 1, 'tidak butuh faktur'),
	(310, 63, 156, '2020-09-10 06:45:08', '2020-09-10 06:45:08', 1, 0, 0, 0, 0, NULL),
	(311, 63, 196, '2020-09-10 06:45:08', '2020-09-10 06:45:08', 1, 0, 0, 0, 0, NULL),
	(312, 63, 223, '2020-09-10 06:45:08', '2020-09-10 06:45:08', 1, 0, 0, 0, 0, NULL),
	(313, 63, 130, '2020-09-10 06:45:08', '2020-09-10 06:45:08', 1, 0, 0, 0, 0, NULL),
	(314, 63, 126, '2020-09-10 06:45:08', '2020-09-10 06:45:08', 1, 0, 0, 0, 0, NULL),
	(315, 63, 124, '2020-09-10 06:45:08', '2020-09-10 06:45:08', 1, 0, 0, 0, 0, NULL),
	(316, 63, 48, '2020-09-10 06:45:08', '2020-09-10 06:45:08', 1, 0, 0, 0, 0, NULL),
	(317, 64, 60, '2020-09-16 03:12:57', '2020-09-16 03:12:57', 0, 1, 0, 0, 0, ''),
	(318, 64, 144, '2020-09-16 03:13:26', '2020-09-16 03:13:30', 0, 0, 0, 0, 1, 'belum diketahui'),
	(319, 64, 43, '2020-09-16 03:14:00', '2020-09-16 03:14:00', 1, 0, 0, 0, 0, NULL),
	(320, 64, 331, '2020-09-16 03:14:00', '2020-09-16 03:14:00', 1, 0, 0, 0, 0, NULL),
	(321, 64, 104, '2020-09-16 03:14:00', '2020-09-16 03:14:00', 1, 0, 0, 0, 0, NULL),
	(322, 64, 16, '2020-09-16 03:14:00', '2020-09-16 03:14:00', 1, 0, 0, 0, 0, NULL),
	(323, 64, 172, '2020-09-16 03:14:00', '2020-09-16 03:14:00', 1, 0, 0, 0, 0, NULL),
	(324, 64, 94, '2020-09-16 03:14:00', '2020-09-16 03:14:00', 1, 0, 0, 0, 0, NULL),
	(325, 64, 130, '2020-09-16 03:14:00', '2020-09-16 03:14:00', 1, 0, 0, 0, 0, NULL),
	(326, 64, 32, '2020-09-16 03:14:00', '2020-09-16 03:14:00', 1, 0, 0, 0, 0, NULL),
	(327, 64, 124, '2020-09-16 03:14:00', '2020-09-16 03:14:00', 1, 0, 0, 0, 0, NULL),
	(328, 65, 529, '2020-09-22 06:42:59', '2020-09-22 06:43:07', 0, 0, 0, 0, 1, 'tidak diketahui'),
	(329, 65, 91, '2020-09-22 06:43:15', '2020-09-22 06:43:15', 1, 0, 0, 0, 0, NULL),
	(330, 65, 172, '2020-09-22 06:43:15', '2020-09-22 06:43:15', 1, 0, 0, 0, 0, NULL),
	(331, 65, 94, '2020-09-22 06:43:15', '2020-09-22 06:43:15', 1, 0, 0, 0, 0, NULL),
	(332, 65, 130, '2020-09-22 06:43:15', '2020-09-22 06:43:15', 1, 0, 0, 0, 0, NULL),
	(333, 65, 220, '2020-09-22 06:43:15', '2020-09-22 06:43:15', 1, 0, 0, 0, 0, NULL),
	(334, 65, 126, '2020-09-22 06:43:15', '2020-09-22 06:43:15', 1, 0, 0, 0, 0, NULL),
	(335, 65, 48, '2020-09-22 06:43:15', '2020-09-22 06:43:15', 1, 0, 0, 0, 0, NULL),
	(336, 28, 16, '2020-10-03 03:33:36', '2020-10-03 03:33:36', 1, 0, 0, 0, 0, NULL),
	(337, 28, 35, '2020-10-03 03:33:36', '2020-10-03 03:33:36', 1, 0, 0, 0, 0, NULL),
	(338, 31, 35, '2020-10-03 03:33:57', '2020-10-03 03:33:57', 1, 0, 0, 0, 0, NULL),
	(395, 67, 175, '2020-10-05 03:45:42', '2020-10-05 03:45:42', 1, 0, 0, 0, 0, NULL),
	(396, 67, 130, '2020-10-05 03:45:43', '2020-10-05 03:45:43', 1, 0, 0, 0, 0, NULL),
	(397, 67, 190, '2020-10-05 03:45:43', '2020-10-05 03:45:43', 1, 0, 0, 0, 0, NULL),
	(398, 67, 35, '2020-10-05 03:45:43', '2020-10-05 03:45:43', 1, 0, 0, 0, 0, NULL),
	(399, 67, 3, '2020-10-05 03:45:43', '2020-10-05 03:45:43', 1, 0, 0, 0, 0, NULL),
	(400, 66, 331, '2020-10-05 03:45:54', '2020-10-05 03:45:54', 1, 0, 0, 0, 0, NULL),
	(401, 66, 148, '2020-10-05 03:45:54', '2020-10-05 03:45:54', 1, 0, 0, 0, 0, NULL),
	(402, 66, 196, '2020-10-05 03:45:54', '2020-10-05 03:45:54', 1, 0, 0, 0, 0, NULL),
	(403, 66, 104, '2020-10-05 03:45:54', '2020-10-05 03:45:54', 1, 0, 0, 0, 0, NULL),
	(404, 66, 91, '2020-10-05 03:45:54', '2020-10-05 03:45:54', 1, 0, 0, 0, 0, NULL),
	(405, 66, 61, '2020-10-05 03:45:54', '2020-10-05 03:45:54', 1, 0, 0, 0, 0, NULL),
	(406, 66, 22, '2020-10-05 03:45:54', '2020-10-05 03:45:54', 1, 0, 0, 0, 0, NULL),
	(407, 66, 910, '2020-10-05 03:45:54', '2020-10-05 03:45:54', 1, 0, 0, 0, 0, NULL),
	(408, 66, 94, '2020-10-05 03:45:55', '2020-10-05 03:45:55', 1, 0, 0, 0, 0, NULL),
	(409, 66, 130, '2020-10-05 03:45:55', '2020-10-05 03:45:55', 1, 0, 0, 0, 0, NULL),
	(410, 66, 220, '2020-10-05 03:45:55', '2020-10-05 03:45:55', 1, 0, 0, 0, 0, NULL),
	(411, 66, 1005, '2020-10-05 03:45:55', '2020-10-05 03:45:55', 1, 0, 0, 0, 0, NULL),
	(412, 66, 35, '2020-10-05 03:45:55', '2020-10-05 03:45:55', 1, 0, 0, 0, 0, NULL),
	(413, 66, 124, '2020-10-05 03:45:55', '2020-10-05 03:45:55', 1, 0, 0, 0, 0, NULL),
	(414, 66, 3, '2020-10-05 03:45:55', '2020-10-05 03:45:55', 1, 0, 0, 0, 0, NULL),
	(415, 66, 98, '2020-10-15 03:11:22', '2020-10-15 03:11:22', 0, 0, 1, 0, 0, ''),
	(416, 66, 25, '2020-10-15 03:11:32', '2020-10-15 03:11:32', 0, 0, 1, 0, 0, ''),
	(417, 67, 529, '2020-10-15 03:12:19', '2020-10-15 03:12:19', 0, 0, 1, 0, 0, ''),
	(418, 68, 98, '2020-10-15 04:52:41', '2020-10-15 04:52:41', 0, 0, 1, 0, 0, ''),
	(419, 68, 529, '2020-10-15 04:56:15', '2020-10-15 04:56:21', 0, 0, 0, 1, 1, 'tidak butuh faktur'),
	(420, 68, 60, '2020-10-15 04:56:29', '2020-10-15 04:56:29', 0, 1, 0, 0, 0, ''),
	(421, 68, 25, '2020-10-15 04:56:34', '2020-10-15 04:56:34', 0, 0, 1, 0, 0, ''),
	(422, 68, 43, '2020-10-15 04:57:45', '2020-10-15 04:57:45', 1, 0, 0, 0, 0, NULL),
	(423, 68, 331, '2020-10-15 04:57:45', '2020-10-15 04:57:45', 1, 0, 0, 0, 0, NULL),
	(424, 68, 196, '2020-10-15 04:57:45', '2020-10-15 04:57:45', 1, 0, 0, 0, 0, NULL),
	(425, 68, 61, '2020-10-15 04:57:45', '2020-10-15 04:57:45', 1, 0, 0, 0, 0, NULL),
	(426, 68, 172, '2020-10-15 04:57:45', '2020-10-15 04:57:45', 1, 0, 0, 0, 0, NULL),
	(427, 68, 910, '2020-10-15 04:57:45', '2020-10-15 04:57:45', 1, 0, 0, 0, 0, NULL),
	(428, 68, 94, '2020-10-15 04:57:45', '2020-10-15 04:57:45', 1, 0, 0, 0, 0, NULL),
	(429, 68, 130, '2020-10-15 04:57:45', '2020-10-15 04:57:45', 1, 0, 0, 0, 0, NULL),
	(430, 68, 32, '2020-10-15 04:57:45', '2020-10-15 04:57:45', 1, 0, 0, 0, 0, NULL),
	(431, 68, 53, '2020-10-15 04:57:45', '2020-10-15 04:57:45', 1, 0, 0, 0, 0, NULL),
	(432, 68, 3, '2020-10-15 04:57:45', '2020-10-15 04:57:45', 1, 0, 0, 0, 0, NULL),
	(433, 69, 1055, '2020-10-23 07:27:11', '2020-10-23 07:32:08', 1, 0, 0, 0, 1, 'belum tahu'),
	(434, 69, 60, '2020-10-23 07:27:20', '2020-10-23 07:27:20', 0, 1, 0, 0, 0, ''),
	(435, 69, 25, '2020-10-23 07:27:24', '2020-10-23 07:27:24', 0, 0, 1, 0, 0, ''),
	(436, 69, 331, '2020-10-23 07:32:08', '2020-10-23 07:32:08', 1, 0, 0, 0, 0, NULL),
	(437, 69, 16, '2020-10-23 07:32:08', '2020-10-23 07:32:08', 1, 0, 0, 0, 0, NULL),
	(438, 69, 91, '2020-10-23 07:32:08', '2020-10-23 07:32:08', 1, 0, 0, 0, 0, NULL),
	(439, 69, 172, '2020-10-23 07:32:08', '2020-10-23 07:32:08', 1, 0, 0, 0, 0, NULL),
	(440, 69, 94, '2020-10-23 07:32:08', '2020-10-23 07:32:08', 1, 0, 0, 0, 0, NULL),
	(441, 69, 130, '2020-10-23 07:32:08', '2020-10-23 07:32:08', 1, 0, 0, 0, 0, NULL),
	(442, 69, 1075, '2020-10-23 07:32:08', '2020-10-23 07:32:08', 1, 0, 0, 0, 0, NULL),
	(443, 69, 74, '2020-10-23 07:32:08', '2020-10-23 07:32:08', 1, 0, 0, 0, 0, NULL),
	(444, 69, 35, '2020-10-23 07:32:08', '2020-10-23 07:32:08', 1, 0, 0, 0, 0, NULL),
	(445, 69, 3, '2020-10-23 07:32:08', '2020-10-23 07:32:08', 1, 0, 0, 0, 0, NULL),
	(446, 69, 48, '2020-10-23 07:32:08', '2020-10-23 07:32:08', 1, 0, 0, 0, 0, NULL),
	(447, 69, 1059, '2020-10-23 07:32:08', '2020-10-23 07:32:08', 1, 0, 0, 0, 0, NULL),
	(448, 70, 529, '2020-10-28 07:08:20', '2020-10-28 07:08:26', 0, 0, 0, 0, 1, 'tidak butuh faktur'),
	(449, 70, 60, '2020-10-28 07:08:31', '2020-10-28 07:08:31', 0, 1, 0, 0, 0, ''),
	(450, 70, 25, '2020-10-28 07:08:37', '2020-10-28 07:08:43', 0, 0, 1, 0, 0, ''),
	(451, 70, 1055, '2020-10-28 07:10:05', '2020-10-28 07:10:05', 1, 0, 0, 0, 0, NULL),
	(452, 70, 43, '2020-10-28 07:10:05', '2020-10-28 07:10:05', 1, 0, 0, 0, 0, NULL),
	(453, 70, 331, '2020-10-28 07:10:06', '2020-10-28 07:10:06', 1, 0, 0, 0, 0, NULL),
	(454, 70, 1094, '2020-10-28 07:10:06', '2020-10-28 07:10:06', 1, 0, 0, 0, 0, NULL),
	(455, 70, 16, '2020-10-28 07:10:06', '2020-10-28 07:10:06', 1, 0, 0, 0, 0, NULL),
	(456, 70, 91, '2020-10-28 07:10:06', '2020-10-28 07:10:06', 1, 0, 0, 0, 0, NULL),
	(457, 70, 61, '2020-10-28 07:10:06', '2020-10-28 07:10:06', 1, 0, 0, 0, 0, NULL),
	(458, 70, 130, '2020-10-28 07:10:06', '2020-10-28 07:10:06', 1, 0, 0, 0, 0, NULL),
	(459, 70, 57, '2020-10-28 07:10:06', '2020-10-28 07:10:06', 1, 0, 0, 0, 0, NULL),
	(460, 70, 123, '2020-10-28 07:10:06', '2020-10-28 07:10:06', 1, 0, 0, 0, 0, NULL),
	(461, 70, 66, '2020-10-28 07:10:06', '2020-10-28 07:10:06', 1, 0, 0, 0, 0, NULL),
	(462, 70, 53, '2020-10-28 07:10:06', '2020-10-28 07:10:06', 1, 0, 0, 0, 0, NULL),
	(463, 70, 74, '2020-10-28 07:10:06', '2020-10-28 07:10:06', 1, 0, 0, 0, 0, NULL),
	(464, 70, 35, '2020-10-28 07:10:06', '2020-10-28 07:10:06', 1, 0, 0, 0, 0, NULL),
	(465, 70, 3, '2020-10-28 07:10:06', '2020-10-28 07:10:06', 1, 0, 0, 0, 0, NULL),
	(466, 70, 1059, '2020-10-28 07:10:06', '2020-10-28 07:10:06', 1, 0, 0, 0, 0, NULL),
	(467, 71, 98, '2020-11-05 07:58:40', '2020-11-05 07:58:40', 0, 0, 1, 0, 0, ''),
	(468, 71, 1055, '2020-11-05 07:58:49', '2020-11-05 07:58:49', 1, 0, 0, 0, 0, NULL),
	(469, 71, 130, '2020-11-05 07:58:49', '2020-11-05 07:58:49', 1, 0, 0, 0, 0, NULL),
	(470, 71, 32, '2020-11-05 07:58:49', '2020-11-05 07:58:49', 1, 0, 0, 0, 0, NULL),
	(471, 71, 53, '2020-11-05 07:58:49', '2020-11-05 07:58:49', 1, 0, 0, 0, 0, NULL),
	(472, 71, 42, '2020-11-05 07:58:49', '2020-11-05 07:58:49', 1, 0, 0, 0, 0, NULL),
	(473, 72, 529, '2020-11-12 08:21:37', '2020-11-12 08:21:42', 0, 0, 0, 0, 1, 'tidak butuh faktur'),
	(474, 72, 60, '2020-11-12 08:21:47', '2020-11-12 08:21:47', 0, 1, 0, 0, 0, ''),
	(475, 72, 201, '2020-11-12 08:25:13', '2020-11-12 08:25:16', 0, 0, 0, 0, 1, 'nanti'),
	(476, 72, 331, '2020-11-12 08:25:32', '2020-11-12 08:25:32', 1, 0, 0, 0, 0, NULL),
	(477, 72, 148, '2020-11-12 08:25:32', '2020-11-12 08:25:32', 1, 0, 0, 0, 0, NULL),
	(478, 72, 50, '2020-11-12 08:25:32', '2020-11-12 08:25:32', 1, 0, 0, 0, 0, NULL),
	(479, 72, 174, '2020-11-12 08:25:32', '2020-11-12 08:25:32', 1, 0, 0, 0, 0, NULL),
	(480, 72, 16, '2020-11-12 08:25:32', '2020-11-12 08:25:32', 1, 0, 0, 0, 0, NULL),
	(481, 72, 91, '2020-11-12 08:25:32', '2020-11-12 08:25:32', 1, 0, 0, 0, 0, NULL),
	(482, 72, 118, '2020-11-12 08:25:33', '2020-11-12 08:25:33', 1, 0, 0, 0, 0, NULL),
	(483, 72, 187, '2020-11-12 08:25:33', '2020-11-12 08:25:33', 1, 0, 0, 0, 0, NULL),
	(484, 72, 172, '2020-11-12 08:25:33', '2020-11-12 08:25:33', 1, 0, 0, 0, 0, NULL),
	(485, 72, 94, '2020-11-12 08:25:33', '2020-11-12 08:25:33', 1, 0, 0, 0, 0, NULL),
	(486, 72, 130, '2020-11-12 08:25:33', '2020-11-12 08:25:33', 1, 0, 0, 0, 0, NULL),
	(487, 72, 57, '2020-11-12 08:25:33', '2020-11-12 08:25:33', 1, 0, 0, 0, 0, NULL),
	(488, 72, 24, '2020-11-12 08:25:33', '2020-11-12 08:25:33', 1, 0, 0, 0, 0, NULL),
	(489, 72, 53, '2020-11-12 08:25:33', '2020-11-12 08:25:33', 1, 0, 0, 0, 0, NULL),
	(490, 72, 42, '2020-11-12 08:25:33', '2020-11-12 08:25:33', 1, 0, 0, 0, 0, NULL),
	(491, 72, 35, '2020-11-12 08:25:33', '2020-11-12 08:25:33', 1, 0, 0, 0, 0, NULL),
	(492, 72, 3, '2020-11-12 08:25:33', '2020-11-12 08:25:33', 1, 0, 0, 0, 0, NULL),
	(493, 72, 151, '2020-11-12 08:25:33', '2020-11-12 08:25:33', 1, 0, 0, 0, 0, NULL),
	(494, 72, 48, '2020-11-12 08:25:33', '2020-11-12 08:25:33', 1, 0, 0, 0, 0, NULL),
	(495, 72, 1059, '2020-11-12 08:25:33', '2020-11-12 08:25:33', 1, 0, 0, 0, 0, NULL),
	(496, 73, 60, '2020-11-18 04:00:23', '2020-11-18 04:00:23', 0, 1, 0, 0, 0, ''),
	(497, 73, 25, '2020-11-18 04:00:28', '2020-11-18 04:00:28', 0, 0, 1, 0, 0, ''),
	(498, 73, 1055, '2020-11-18 04:04:47', '2020-11-18 04:04:47', 1, 0, 0, 0, 0, NULL),
	(499, 73, 331, '2020-11-18 04:04:47', '2020-11-18 04:04:47', 1, 0, 0, 0, 0, NULL),
	(500, 73, 91, '2020-11-18 04:04:47', '2020-11-18 04:04:47', 1, 0, 0, 0, 0, NULL),
	(501, 73, 61, '2020-11-18 04:04:47', '2020-11-18 04:04:47', 1, 0, 0, 0, 0, NULL),
	(502, 73, 187, '2020-11-18 04:04:47', '2020-11-18 04:04:47', 1, 0, 0, 0, 0, NULL),
	(503, 73, 94, '2020-11-18 04:04:47', '2020-11-18 04:04:47', 1, 0, 0, 0, 0, NULL),
	(504, 73, 130, '2020-11-18 04:04:47', '2020-11-18 04:04:47', 1, 0, 0, 0, 0, NULL),
	(505, 73, 32, '2020-11-18 04:04:47', '2020-11-18 04:04:47', 1, 0, 0, 0, 0, NULL),
	(506, 73, 53, '2020-11-18 04:04:47', '2020-11-18 04:04:47', 1, 0, 0, 0, 0, NULL),
	(507, 73, 74, '2020-11-18 04:04:47', '2020-11-18 04:04:47', 1, 0, 0, 0, 0, NULL),
	(508, 73, 42, '2020-11-18 04:04:47', '2020-11-18 04:04:47', 1, 0, 0, 0, 0, NULL),
	(509, 73, 837, '2020-11-18 04:04:47', '2020-11-18 04:04:47', 1, 0, 0, 0, 0, NULL),
	(510, 73, 3, '2020-11-18 04:04:47', '2020-11-18 04:04:47', 1, 0, 0, 0, 0, NULL),
	(511, 73, 48, '2020-11-18 04:04:47', '2020-11-18 04:04:47', 1, 0, 0, 0, 0, NULL),
	(512, 73, 1059, '2020-11-18 04:04:47', '2020-11-18 04:04:47', 1, 0, 0, 0, 0, NULL),
	(513, 74, 529, '2020-11-25 02:59:00', '2020-11-25 02:59:07', 0, 0, 0, 0, 1, 'belum diketahui'),
	(514, 74, 60, '2020-11-25 02:59:13', '2020-11-25 02:59:13', 0, 1, 0, 0, 0, ''),
	(515, 74, 430, '2020-11-25 02:59:19', '2020-11-25 02:59:19', 0, 0, 1, 0, 0, ''),
	(516, 74, 169, '2020-11-25 02:59:33', '2020-11-25 02:59:33', 1, 0, 0, 0, 0, NULL),
	(517, 74, 331, '2020-11-25 02:59:33', '2020-11-25 02:59:33', 1, 0, 0, 0, 0, NULL),
	(518, 74, 148, '2020-11-25 02:59:33', '2020-11-25 02:59:33', 1, 0, 0, 0, 0, NULL),
	(519, 74, 50, '2020-11-25 02:59:33', '2020-11-25 02:59:33', 1, 0, 0, 0, 0, NULL),
	(520, 74, 187, '2020-11-25 02:59:33', '2020-11-25 02:59:33', 1, 0, 0, 0, 0, NULL),
	(521, 74, 910, '2020-11-25 02:59:33', '2020-11-25 02:59:33', 1, 0, 0, 0, 0, NULL),
	(522, 74, 66, '2020-11-25 02:59:33', '2020-11-25 02:59:33', 1, 0, 0, 0, 0, NULL),
	(523, 74, 74, '2020-11-25 02:59:33', '2020-11-25 02:59:33', 1, 0, 0, 0, 0, NULL),
	(524, 74, 42, '2020-11-25 02:59:33', '2020-11-25 02:59:33', 1, 0, 0, 0, 0, NULL),
	(525, 74, 190, '2020-11-25 02:59:33', '2020-11-25 02:59:33', 1, 0, 0, 0, 0, NULL),
	(526, 74, 124, '2020-11-25 02:59:33', '2020-11-25 02:59:33', 1, 0, 0, 0, 0, NULL),
	(527, 74, 3, '2020-11-25 02:59:33', '2020-11-25 02:59:33', 1, 0, 0, 0, 0, NULL),
	(528, 74, 1059, '2020-11-25 02:59:33', '2020-11-25 02:59:33', 1, 0, 0, 0, 0, NULL),
	(529, 75, 529, '2020-12-04 04:49:15', '2020-12-04 04:49:22', 0, 0, 0, 0, 1, 'tidak butuh faktur'),
	(530, 75, 25, '2020-12-04 04:49:26', '2020-12-04 04:49:26', 0, 0, 1, 0, 0, ''),
	(531, 75, 201, '2020-12-04 04:49:36', '2020-12-04 04:49:36', 0, 0, 1, 0, 0, ''),
	(532, 75, 43, '2020-12-04 04:50:47', '2020-12-04 04:50:47', 1, 0, 0, 0, 0, NULL),
	(533, 75, 50, '2020-12-04 04:50:47', '2020-12-04 04:50:47', 1, 0, 0, 0, 0, NULL),
	(534, 75, 91, '2020-12-04 04:50:47', '2020-12-04 04:50:47', 1, 0, 0, 0, 0, NULL),
	(535, 75, 910, '2020-12-04 04:50:47', '2020-12-04 04:50:47', 1, 0, 0, 0, 0, NULL),
	(536, 75, 130, '2020-12-04 04:50:47', '2020-12-04 04:50:47', 1, 0, 0, 0, 0, NULL),
	(537, 75, 32, '2020-12-04 04:50:47', '2020-12-04 04:50:47', 1, 0, 0, 0, 0, NULL),
	(538, 75, 220, '2020-12-04 04:50:47', '2020-12-04 04:50:47', 1, 0, 0, 0, 0, NULL),
	(539, 75, 74, '2020-12-04 04:50:47', '2020-12-04 04:50:47', 1, 0, 0, 0, 0, NULL),
	(540, 75, 35, '2020-12-04 04:50:47', '2020-12-04 04:50:47', 1, 0, 0, 0, 0, NULL),
	(541, 75, 1059, '2020-12-04 04:50:47', '2020-12-04 04:50:47', 1, 0, 0, 0, 0, NULL),
	(542, 76, 25, '2020-12-11 04:32:23', '2020-12-11 04:32:23', 0, 0, 1, 0, 0, ''),
	(543, 76, 331, '2020-12-11 04:32:45', '2020-12-11 04:32:45', 1, 0, 0, 0, 0, NULL),
	(544, 76, 148, '2020-12-11 04:32:45', '2020-12-11 04:32:45', 1, 0, 0, 0, 0, NULL),
	(545, 76, 50, '2020-12-11 04:32:45', '2020-12-11 04:32:45', 1, 0, 0, 0, 0, NULL),
	(546, 76, 61, '2020-12-11 04:32:45', '2020-12-11 04:32:45', 1, 0, 0, 0, 0, NULL),
	(547, 76, 94, '2020-12-11 04:32:45', '2020-12-11 04:32:45', 1, 0, 0, 0, 0, NULL),
	(548, 76, 130, '2020-12-11 04:32:45', '2020-12-11 04:32:45', 1, 0, 0, 0, 0, NULL),
	(549, 76, 32, '2020-12-11 04:32:45', '2020-12-11 04:32:45', 1, 0, 0, 0, 0, NULL),
	(550, 76, 220, '2020-12-11 04:32:45', '2020-12-11 04:32:45', 1, 0, 0, 0, 0, NULL),
	(551, 76, 74, '2020-12-11 04:32:45', '2020-12-11 04:32:45', 1, 0, 0, 0, 0, NULL),
	(552, 76, 498, '2020-12-11 04:32:45', '2020-12-11 04:32:45', 1, 0, 0, 0, 0, NULL),
	(553, 76, 126, '2020-12-11 04:32:45', '2020-12-11 04:32:45', 1, 0, 0, 0, 0, NULL),
	(554, 76, 3, '2020-12-11 04:32:45', '2020-12-11 04:32:45', 1, 0, 0, 0, 0, NULL),
	(555, 76, 1059, '2020-12-11 04:32:45', '2020-12-11 04:32:45', 1, 0, 0, 0, 0, NULL),
	(556, 77, 98, '2020-12-17 08:24:09', '2020-12-17 08:24:09', 0, 0, 1, 0, 0, ''),
	(557, 77, 529, '2020-12-17 08:24:12', '2020-12-17 08:24:17', 0, 0, 0, 0, 1, 'tidak butuh faktur'),
	(558, 77, 144, '2020-12-17 08:25:00', '2020-12-17 08:25:04', 0, 0, 0, 0, 1, 'belum diketahui'),
	(559, 77, 43, '2020-12-17 08:25:30', '2020-12-17 08:25:30', 1, 0, 0, 0, 0, NULL),
	(560, 77, 331, '2020-12-17 08:25:30', '2020-12-17 08:25:30', 1, 0, 0, 0, 0, NULL),
	(561, 77, 148, '2020-12-17 08:25:30', '2020-12-17 08:25:30', 1, 0, 0, 0, 0, NULL),
	(562, 77, 468, '2020-12-17 08:25:30', '2020-12-17 08:25:30', 1, 0, 0, 0, 0, NULL),
	(563, 77, 91, '2020-12-17 08:25:30', '2020-12-17 08:25:30', 1, 0, 0, 0, 0, NULL),
	(564, 77, 130, '2020-12-17 08:25:30', '2020-12-17 08:25:30', 1, 0, 0, 0, 0, NULL),
	(565, 77, 220, '2020-12-17 08:25:30', '2020-12-17 08:25:30', 1, 0, 0, 0, 0, NULL),
	(566, 77, 74, '2020-12-17 08:25:30', '2020-12-17 08:25:30', 1, 0, 0, 0, 0, NULL),
	(567, 77, 46, '2020-12-17 08:25:30', '2020-12-17 08:25:30', 1, 0, 0, 0, 0, NULL),
	(568, 77, 42, '2020-12-17 08:25:30', '2020-12-17 08:25:30', 1, 0, 0, 0, 0, NULL),
	(569, 77, 3, '2020-12-17 08:25:30', '2020-12-17 08:25:30', 1, 0, 0, 0, 0, NULL),
	(570, 78, 98, '2021-01-05 02:50:35', '2021-01-05 02:50:35', 0, 0, 1, 0, 0, ''),
	(571, 78, 60, '2021-01-05 02:50:40', '2021-01-05 02:50:40', 0, 1, 0, 0, 0, ''),
	(572, 78, 1225, '2021-01-05 02:53:43', '2021-01-05 02:55:19', 0, 0, 0, 0, 1, 'belum tahu'),
	(573, 78, 1055, '2021-01-05 02:55:48', '2021-01-05 04:07:41', 1, 0, 0, 0, 0, NULL),
	(574, 78, 43, '2021-01-05 02:55:48', '2021-01-05 04:07:41', 1, 0, 0, 0, 0, NULL),
	(575, 78, 331, '2021-01-05 02:55:48', '2021-01-05 04:07:41', 1, 0, 0, 0, 0, NULL),
	(576, 78, 174, '2021-01-05 02:55:48', '2021-01-05 04:07:41', 1, 0, 0, 0, 0, NULL),
	(577, 78, 468, '2021-01-05 02:55:48', '2021-01-05 04:07:41', 1, 0, 0, 0, 0, NULL),
	(578, 78, 91, '2021-01-05 02:55:48', '2021-01-05 04:07:41', 1, 0, 0, 0, 0, NULL),
	(579, 78, 94, '2021-01-05 02:55:48', '2021-01-05 04:07:41', 1, 0, 0, 0, 0, NULL),
	(580, 78, 130, '2021-01-05 02:55:48', '2021-01-05 04:07:41', 1, 0, 0, 0, 0, NULL),
	(581, 78, 32, '2021-01-05 02:55:48', '2021-01-05 04:07:41', 1, 0, 0, 0, 0, NULL),
	(582, 78, 74, '2021-01-05 02:55:48', '2021-01-05 04:07:41', 1, 0, 0, 0, 0, NULL),
	(583, 78, 498, '2021-01-05 02:55:48', '2021-01-05 04:07:41', 1, 0, 0, 0, 0, NULL),
	(584, 78, 46, '2021-01-05 02:55:48', '2021-01-05 04:07:41', 1, 0, 0, 0, 0, NULL),
	(585, 78, 190, '2021-01-05 02:55:48', '2021-01-05 04:07:41', 1, 0, 0, 0, 0, NULL),
	(586, 78, 151, '2021-01-05 02:55:48', '2021-01-05 04:07:41', 1, 0, 0, 0, 0, NULL),
	(587, 78, 1059, '2021-01-05 02:55:48', '2021-01-05 04:07:41', 1, 0, 0, 0, 0, NULL),
	(588, 79, 529, '2021-01-07 06:41:59', '2021-01-07 06:42:04', 0, 0, 0, 0, 1, 'tidak butuh faktur'),
	(589, 79, 25, '2021-01-07 06:42:07', '2021-01-07 06:42:07', 0, 0, 1, 0, 0, ''),
	(590, 79, 331, '2021-01-07 06:45:58', '2021-01-07 06:45:58', 1, 0, 0, 0, 0, NULL),
	(591, 79, 148, '2021-01-07 06:45:58', '2021-01-07 06:45:58', 1, 0, 0, 0, 0, NULL),
	(592, 79, 50, '2021-01-07 06:45:58', '2021-01-07 06:45:58', 1, 0, 0, 0, 0, NULL),
	(593, 79, 94, '2021-01-07 06:45:58', '2021-01-07 06:45:58', 1, 0, 0, 0, 0, NULL),
	(594, 79, 130, '2021-01-07 06:45:58', '2021-01-07 06:45:58', 1, 0, 0, 0, 0, NULL),
	(595, 79, 32, '2021-01-07 06:45:58', '2021-01-07 06:45:58', 1, 0, 0, 0, 0, NULL),
	(596, 79, 53, '2021-01-07 06:45:58', '2021-01-07 06:45:58', 1, 0, 0, 0, 0, NULL),
	(597, 79, 35, '2021-01-07 06:45:58', '2021-01-07 06:45:58', 1, 0, 0, 0, 0, NULL),
	(598, 79, 3, '2021-01-07 06:45:58', '2021-01-07 06:45:58', 1, 0, 0, 0, 0, NULL),
	(599, 80, 529, '2021-01-13 02:23:11', '2021-01-13 02:23:14', 0, 0, 0, 0, 1, 'tidak butuh faktur'),
	(600, 80, 60, '2021-01-13 02:23:17', '2021-01-13 02:23:17', 0, 1, 0, 0, 0, ''),
	(601, 80, 10, '2021-01-13 02:25:33', '2021-01-13 02:25:33', 1, 0, 0, 0, 0, NULL),
	(602, 80, 43, '2021-01-13 02:25:33', '2021-01-13 02:25:33', 1, 0, 0, 0, 0, NULL),
	(603, 80, 331, '2021-01-13 02:25:33', '2021-01-13 02:25:33', 1, 0, 0, 0, 0, NULL),
	(604, 80, 148, '2021-01-13 02:25:33', '2021-01-13 02:25:33', 1, 0, 0, 0, 0, NULL),
	(605, 80, 50, '2021-01-13 02:25:33', '2021-01-13 02:25:33', 1, 0, 0, 0, 0, NULL),
	(606, 80, 1094, '2021-01-13 02:25:33', '2021-01-13 02:25:33', 1, 0, 0, 0, 0, NULL),
	(607, 80, 468, '2021-01-13 02:25:34', '2021-01-13 02:25:34', 1, 0, 0, 0, 0, NULL),
	(608, 80, 91, '2021-01-13 02:25:34', '2021-01-13 02:25:34', 1, 0, 0, 0, 0, NULL),
	(609, 80, 61, '2021-01-13 02:25:34', '2021-01-13 02:25:34', 1, 0, 0, 0, 0, NULL),
	(610, 80, 910, '2021-01-13 02:25:34', '2021-01-13 02:25:34', 1, 0, 0, 0, 0, NULL),
	(611, 80, 130, '2021-01-13 02:25:34', '2021-01-13 02:25:34', 1, 0, 0, 0, 0, NULL),
	(612, 80, 32, '2021-01-13 02:25:34', '2021-01-13 02:25:34', 1, 0, 0, 0, 0, NULL),
	(613, 80, 1254, '2021-01-13 02:25:34', '2021-01-13 02:25:34', 1, 0, 0, 0, 0, NULL),
	(614, 80, 220, '2021-01-13 02:25:34', '2021-01-13 02:25:34', 1, 0, 0, 0, 0, NULL),
	(615, 80, 190, '2021-01-13 02:25:34', '2021-01-13 02:25:34', 1, 0, 0, 0, 0, NULL),
	(616, 80, 35, '2021-01-13 02:25:34', '2021-01-13 02:25:34', 1, 0, 0, 0, 0, NULL),
	(617, 80, 3, '2021-01-13 02:25:34', '2021-01-13 02:25:34', 1, 0, 0, 0, 0, NULL),
	(618, 80, 1059, '2021-01-13 02:25:34', '2021-01-13 02:25:34', 1, 0, 0, 0, 0, NULL),
	(619, 81, 60, '2021-01-21 02:50:59', '2021-01-21 02:50:59', 0, 1, 0, 0, 0, ''),
	(620, 81, 43, '2021-01-21 02:51:36', '2021-01-21 02:51:36', 1, 0, 0, 0, 0, NULL),
	(621, 81, 331, '2021-01-21 02:51:36', '2021-01-21 02:51:36', 1, 0, 0, 0, 0, NULL),
	(622, 81, 468, '2021-01-21 02:51:37', '2021-01-21 02:51:37', 1, 0, 0, 0, 0, NULL),
	(623, 81, 91, '2021-01-21 02:51:37', '2021-01-21 02:51:37', 1, 0, 0, 0, 0, NULL),
	(624, 81, 200, '2021-01-21 02:51:37', '2021-01-21 02:51:37', 1, 0, 0, 0, 0, NULL),
	(625, 81, 172, '2021-01-21 02:51:37', '2021-01-21 02:51:37', 1, 0, 0, 0, 0, NULL),
	(626, 81, 130, '2021-01-21 02:51:37', '2021-01-21 02:51:37', 1, 0, 0, 0, 0, NULL),
	(627, 81, 220, '2021-01-21 02:51:37', '2021-01-21 02:51:37', 1, 0, 0, 0, 0, NULL),
	(628, 81, 35, '2021-01-21 02:51:37', '2021-01-21 02:51:37', 1, 0, 0, 0, 0, NULL),
	(629, 81, 3, '2021-01-21 02:51:37', '2021-01-21 02:51:37', 1, 0, 0, 0, 0, NULL),
	(630, 81, 48, '2021-01-21 02:51:37', '2021-01-21 02:51:37', 1, 0, 0, 0, 0, NULL),
	(631, 81, 1059, '2021-01-21 02:51:37', '2021-01-21 02:51:37', 1, 0, 0, 0, 0, NULL),
	(632, 82, 60, '2021-01-27 07:05:38', '2021-01-27 07:05:38', 0, 1, 0, 0, 0, ''),
	(633, 82, 529, '2021-01-27 07:05:40', '2021-01-27 07:05:44', 0, 0, 0, 0, 1, 'tidak butuh faktur'),
	(634, 82, 99, '2021-01-27 07:32:43', '2021-01-27 09:05:38', 1, 0, 0, 0, 1, 'menunggu kabar'),
	(635, 82, 331, '2021-01-27 07:33:11', '2021-01-27 07:33:11', 1, 0, 0, 0, 0, NULL),
	(636, 82, 148, '2021-01-27 07:33:11', '2021-01-27 07:33:11', 1, 0, 0, 0, 0, NULL),
	(637, 82, 53, '2021-01-27 07:33:11', '2021-01-27 07:33:11', 1, 0, 0, 0, 0, NULL),
	(638, 82, 1059, '2021-01-27 07:33:11', '2021-01-27 07:33:11', 1, 0, 0, 0, 0, NULL),
	(639, 83, 60, '2021-02-02 08:52:18', '2021-02-02 08:52:18', 0, 1, 0, 0, 0, ''),
	(640, 83, 144, '2021-02-02 08:52:59', '2021-02-02 08:53:04', 0, 0, 0, 0, 1, 'belum diketahui'),
	(641, 83, 1055, '2021-02-02 08:53:13', '2021-02-02 08:53:13', 1, 0, 0, 0, 0, NULL),
	(642, 83, 331, '2021-02-02 08:53:13', '2021-02-02 08:53:13', 1, 0, 0, 0, 0, NULL),
	(643, 83, 148, '2021-02-02 08:53:13', '2021-02-02 08:53:13', 1, 0, 0, 0, 0, NULL),
	(644, 83, 50, '2021-02-02 08:53:13', '2021-02-02 08:53:13', 1, 0, 0, 0, 0, NULL),
	(645, 83, 468, '2021-02-02 08:53:13', '2021-02-02 08:53:13', 1, 0, 0, 0, 0, NULL),
	(646, 83, 118, '2021-02-02 08:53:13', '2021-02-02 08:53:13', 1, 0, 0, 0, 0, NULL),
	(647, 83, 22, '2021-02-02 08:53:13', '2021-02-02 08:53:13', 1, 0, 0, 0, 0, NULL),
	(648, 83, 187, '2021-02-02 08:53:13', '2021-02-02 08:53:13', 1, 0, 0, 0, 0, NULL),
	(649, 83, 1314, '2021-02-02 08:53:14', '2021-02-02 08:53:14', 1, 0, 0, 0, 0, NULL),
	(650, 83, 94, '2021-02-02 08:53:14', '2021-02-02 08:53:14', 1, 0, 0, 0, 0, NULL),
	(651, 83, 130, '2021-02-02 08:53:14', '2021-02-02 08:53:14', 1, 0, 0, 0, 0, NULL),
	(652, 83, 1254, '2021-02-02 08:53:14', '2021-02-02 08:53:14', 1, 0, 0, 0, 0, NULL),
	(653, 83, 53, '2021-02-02 08:53:14', '2021-02-02 08:53:14', 1, 0, 0, 0, 0, NULL),
	(654, 83, 3, '2021-02-02 08:53:14', '2021-02-02 08:53:14', 1, 0, 0, 0, 0, NULL),
	(655, 83, 1059, '2021-02-02 08:53:14', '2021-02-02 08:53:14', 1, 0, 0, 0, 0, NULL),
	(656, 84, 331, '2021-02-10 06:29:21', '2021-02-10 06:29:21', 1, 0, 0, 0, 0, NULL),
	(657, 84, 174, '2021-02-10 06:29:21', '2021-02-10 06:29:21', 1, 0, 0, 0, 0, NULL),
	(658, 84, 468, '2021-02-10 06:29:21', '2021-02-10 06:29:21', 1, 0, 0, 0, 0, NULL),
	(659, 84, 91, '2021-02-10 06:29:21', '2021-02-10 06:29:21', 1, 0, 0, 0, 0, NULL),
	(660, 84, 118, '2021-02-10 06:29:21', '2021-02-10 06:29:21', 1, 0, 0, 0, 0, NULL),
	(661, 84, 54, '2021-02-10 06:29:21', '2021-02-10 06:29:21', 1, 0, 0, 0, 0, NULL),
	(662, 84, 61, '2021-02-10 06:29:21', '2021-02-10 06:29:21', 1, 0, 0, 0, 0, NULL),
	(663, 84, 910, '2021-02-10 06:29:21', '2021-02-10 06:29:21', 1, 0, 0, 0, 0, NULL),
	(664, 84, 130, '2021-02-10 06:29:21', '2021-02-10 06:29:21', 1, 0, 0, 0, 0, NULL),
	(665, 84, 32, '2021-02-10 06:29:21', '2021-02-10 06:29:21', 1, 0, 0, 0, 0, NULL),
	(666, 84, 46, '2021-02-10 06:29:21', '2021-02-10 06:29:21', 1, 0, 0, 0, 0, NULL),
	(667, 84, 35, '2021-02-10 06:29:22', '2021-02-10 06:29:22', 1, 0, 0, 0, 0, NULL),
	(668, 84, 3, '2021-02-10 06:29:22', '2021-02-10 06:29:22', 1, 0, 0, 0, 0, NULL),
	(669, 84, 1059, '2021-02-10 06:29:22', '2021-02-10 06:29:22', 1, 0, 0, 0, 0, NULL),
	(679, 85, 43, '2021-02-17 07:26:05', '2021-02-17 07:26:05', 1, 0, 0, 0, 0, NULL),
	(680, 85, 331, '2021-02-17 07:26:05', '2021-02-17 07:26:05', 1, 0, 0, 0, 0, NULL),
	(681, 85, 148, '2021-02-17 07:26:05', '2021-02-17 07:26:05', 1, 0, 0, 0, 0, NULL),
	(682, 85, 1094, '2021-02-17 07:26:05', '2021-02-17 07:26:05', 1, 0, 0, 0, 0, NULL),
	(683, 85, 468, '2021-02-17 07:26:05', '2021-02-17 07:26:05', 1, 0, 0, 0, 0, NULL),
	(684, 85, 3, '2021-02-17 07:26:05', '2021-02-17 07:26:05', 1, 0, 0, 0, 0, NULL),
	(685, 85, 1352, '2021-02-17 07:26:05', '2021-02-17 07:26:05', 1, 0, 0, 0, 0, NULL),
	(686, 85, 1059, '2021-02-17 07:26:05', '2021-02-17 07:26:05', 1, 0, 0, 0, 0, NULL),
	(687, 85, 60, '2021-02-18 08:12:54', '2021-02-18 08:12:54', 0, 1, 0, 0, 0, ''),
	(688, 86, 25, '2021-02-25 02:40:50', '2021-02-25 02:40:50', 0, 0, 1, 0, 0, ''),
	(689, 86, 98, '2021-02-25 02:41:37', '2021-02-25 02:41:37', 0, 0, 1, 0, 0, ''),
	(690, 86, 43, '2021-02-25 02:42:54', '2021-02-25 02:42:54', 1, 0, 0, 0, 0, NULL),
	(691, 86, 331, '2021-02-25 02:42:54', '2021-02-25 02:42:54', 1, 0, 0, 0, 0, NULL),
	(692, 86, 91, '2021-02-25 02:42:54', '2021-02-25 02:42:54', 1, 0, 0, 0, 0, NULL),
	(693, 86, 1366, '2021-02-25 02:42:54', '2021-02-25 02:42:54', 1, 0, 0, 0, 0, NULL),
	(694, 86, 22, '2021-02-25 02:42:54', '2021-02-25 02:42:54', 1, 0, 0, 0, 0, NULL),
	(695, 86, 94, '2021-02-25 02:42:54', '2021-02-25 02:42:54', 1, 0, 0, 0, 0, NULL),
	(696, 86, 130, '2021-02-25 02:42:54', '2021-02-25 02:42:54', 1, 0, 0, 0, 0, NULL),
	(697, 86, 53, '2021-02-25 02:42:54', '2021-02-25 02:42:54', 1, 0, 0, 0, 0, NULL),
	(698, 86, 126, '2021-02-25 02:42:54', '2021-02-25 02:42:54', 1, 0, 0, 0, 0, NULL),
	(699, 86, 122, '2021-02-25 02:42:54', '2021-02-25 02:42:54', 1, 0, 0, 0, 0, NULL),
	(700, 86, 35, '2021-02-25 02:42:54', '2021-02-25 02:42:54', 1, 0, 0, 0, 0, NULL),
	(701, 86, 1059, '2021-02-25 02:42:54', '2021-02-25 02:42:54', 1, 0, 0, 0, 0, NULL),
	(702, 87, 1055, '2021-03-02 08:53:37', '2021-03-02 08:53:37', 1, 0, 0, 0, 0, NULL),
	(703, 87, 43, '2021-03-02 08:53:37', '2021-03-02 08:53:37', 1, 0, 0, 0, 0, NULL),
	(704, 87, 331, '2021-03-02 08:53:37', '2021-03-02 08:53:37', 1, 0, 0, 0, 0, NULL),
	(705, 87, 1392, '2021-03-02 08:53:37', '2021-03-02 08:53:37', 1, 0, 0, 0, 0, NULL),
	(706, 87, 1389, '2021-03-02 08:53:37', '2021-03-02 08:53:37', 1, 0, 0, 0, 0, NULL),
	(707, 87, 54, '2021-03-02 08:53:37', '2021-03-02 08:53:37', 1, 0, 0, 0, 0, NULL),
	(708, 87, 1366, '2021-03-02 08:53:37', '2021-03-02 08:53:37', 1, 0, 0, 0, 0, NULL),
	(709, 87, 22, '2021-03-02 08:53:37', '2021-03-02 08:53:37', 1, 0, 0, 0, 0, NULL),
	(710, 87, 172, '2021-03-02 08:53:37', '2021-03-02 08:53:37', 1, 0, 0, 0, 0, NULL),
	(711, 87, 130, '2021-03-02 08:53:37', '2021-03-02 08:53:37', 1, 0, 0, 0, 0, NULL),
	(712, 87, 129, '2021-03-02 08:53:37', '2021-03-02 08:53:37', 1, 0, 0, 0, 0, NULL),
	(713, 87, 193, '2021-03-02 08:53:37', '2021-03-02 08:53:37', 1, 0, 0, 0, 0, NULL),
	(714, 87, 42, '2021-03-02 08:53:37', '2021-03-02 08:53:37', 1, 0, 0, 0, 0, NULL),
	(715, 87, 190, '2021-03-02 08:53:37', '2021-03-02 08:53:37', 1, 0, 0, 0, 0, NULL),
	(716, 87, 35, '2021-03-02 08:53:37', '2021-03-02 08:53:37', 1, 0, 0, 0, 0, NULL),
	(717, 87, 3, '2021-03-02 08:53:37', '2021-03-02 08:53:37', 1, 0, 0, 0, 0, NULL),
	(718, 87, 1059, '2021-03-02 08:53:37', '2021-03-02 08:53:37', 1, 0, 0, 0, 0, NULL),
	(719, 88, 98, '2021-03-10 03:36:29', '2021-03-10 03:36:29', 0, 0, 1, 0, 0, ''),
	(720, 88, 60, '2021-03-10 03:36:31', '2021-03-10 03:36:34', 0, 1, 0, 0, 0, ''),
	(721, 88, 331, '2021-03-10 03:36:46', '2021-03-10 03:36:46', 1, 0, 0, 0, 0, NULL),
	(722, 88, 1094, '2021-03-10 03:36:46', '2021-03-10 03:36:46', 1, 0, 0, 0, 0, NULL),
	(723, 88, 468, '2021-03-10 03:36:46', '2021-03-10 03:36:46', 1, 0, 0, 0, 0, NULL),
	(724, 88, 91, '2021-03-10 03:36:46', '2021-03-10 03:36:46', 1, 0, 0, 0, 0, NULL),
	(725, 88, 200, '2021-03-10 03:36:46', '2021-03-10 03:36:46', 1, 0, 0, 0, 0, NULL),
	(726, 88, 1314, '2021-03-10 03:36:46', '2021-03-10 03:36:46', 1, 0, 0, 0, 0, NULL),
	(727, 88, 910, '2021-03-10 03:36:46', '2021-03-10 03:36:46', 1, 0, 0, 0, 0, NULL),
	(728, 88, 130, '2021-03-10 03:36:46', '2021-03-10 03:36:46', 1, 0, 0, 0, 0, NULL),
	(729, 88, 32, '2021-03-10 03:36:46', '2021-03-10 03:36:46', 1, 0, 0, 0, 0, NULL),
	(730, 88, 129, '2021-03-10 03:36:46', '2021-03-10 03:36:46', 1, 0, 0, 0, 0, NULL),
	(731, 88, 46, '2021-03-10 03:36:46', '2021-03-10 03:36:46', 1, 0, 0, 0, 0, NULL),
	(732, 88, 3, '2021-03-10 03:36:46', '2021-03-10 03:36:46', 1, 0, 0, 0, 0, NULL),
	(733, 88, 1059, '2021-03-10 03:36:46', '2021-03-10 03:36:46', 1, 0, 0, 0, 0, NULL),
	(734, 89, 13, '2021-03-20 06:00:25', '2021-03-20 06:00:25', 0, 0, 1, 0, 0, ''),
	(735, 89, 98, '2021-03-20 06:00:52', '2021-03-20 06:00:52', 0, 0, 1, 0, 0, ''),
	(736, 89, 331, '2021-03-20 06:01:32', '2021-03-20 06:01:32', 1, 0, 0, 0, 0, NULL),
	(737, 89, 50, '2021-03-20 06:01:32', '2021-03-20 06:01:32', 1, 0, 0, 0, 0, NULL),
	(738, 89, 61, '2021-03-20 06:01:32', '2021-03-20 06:01:32', 1, 0, 0, 0, 0, NULL),
	(739, 89, 1314, '2021-03-20 06:01:32', '2021-03-20 06:01:32', 1, 0, 0, 0, 0, NULL),
	(740, 89, 130, '2021-03-20 06:01:32', '2021-03-20 06:01:32', 1, 0, 0, 0, 0, NULL),
	(741, 89, 1409, '2021-03-20 06:01:32', '2021-03-20 06:01:32', 1, 0, 0, 0, 0, NULL),
	(742, 89, 24, '2021-03-20 06:01:32', '2021-03-20 06:01:32', 1, 0, 0, 0, 0, NULL),
	(743, 89, 123, '2021-03-20 06:01:32', '2021-03-20 06:01:32', 1, 0, 0, 0, 0, NULL),
	(744, 89, 42, '2021-03-20 06:01:32', '2021-03-20 06:01:32', 1, 0, 0, 0, 0, NULL),
	(745, 89, 35, '2021-03-20 06:01:32', '2021-03-20 06:01:32', 1, 0, 0, 0, 0, NULL),
	(746, 89, 3, '2021-03-20 06:01:32', '2021-03-20 06:01:32', 1, 0, 0, 0, 0, NULL),
	(747, 89, 1352, '2021-03-20 06:01:32', '2021-03-20 06:01:32', 1, 0, 0, 0, 0, NULL),
	(748, 89, 1059, '2021-03-20 06:01:32', '2021-03-20 06:01:32', 1, 0, 0, 0, 0, NULL),
	(749, 90, 60, '2021-03-25 07:32:55', '2021-03-25 07:32:55', 0, 1, 0, 0, 0, ''),
	(750, 90, 1427, '2021-03-25 14:34:13', '2021-03-25 14:34:13', 1, 0, 0, 0, 0, NULL),
	(751, 90, 331, '2021-03-25 14:34:13', '2021-03-25 14:34:13', 1, 0, 0, 0, 0, NULL),
	(752, 90, 148, '2021-03-25 14:34:13', '2021-03-25 14:34:13', 1, 0, 0, 0, 0, NULL),
	(753, 90, 1392, '2021-03-25 14:34:14', '2021-03-25 14:34:14', 1, 0, 0, 0, 0, NULL),
	(754, 90, 468, '2021-03-25 14:34:14', '2021-03-25 14:34:14', 1, 0, 0, 0, 0, NULL),
	(755, 90, 91, '2021-03-25 14:34:14', '2021-03-25 14:34:14', 1, 0, 0, 0, 0, NULL),
	(756, 90, 1314, '2021-03-25 14:34:14', '2021-03-25 14:34:14', 1, 0, 0, 0, 0, NULL),
	(757, 90, 910, '2021-03-25 14:34:14', '2021-03-25 14:34:14', 1, 0, 0, 0, 0, NULL),
	(758, 90, 130, '2021-03-25 14:34:14', '2021-03-25 14:34:14', 1, 0, 0, 0, 0, NULL),
	(759, 90, 32, '2021-03-25 14:34:14', '2021-03-25 14:34:14', 1, 0, 0, 0, 0, NULL),
	(760, 90, 24, '2021-03-25 14:34:14', '2021-03-25 14:34:14', 1, 0, 0, 0, 0, NULL),
	(761, 90, 220, '2021-03-25 14:34:14', '2021-03-25 14:34:14', 1, 0, 0, 0, 0, NULL),
	(762, 90, 124, '2021-03-25 14:34:14', '2021-03-25 14:34:14', 1, 0, 0, 0, 0, NULL),
	(763, 90, 3, '2021-03-25 14:34:14', '2021-03-25 14:34:14', 1, 0, 0, 0, 0, NULL),
	(764, 90, 48, '2021-03-25 14:34:14', '2021-03-25 14:34:14', 1, 0, 0, 0, 0, NULL),
	(765, 90, 1059, '2021-03-25 14:34:14', '2021-03-25 14:34:14', 1, 0, 0, 0, 0, NULL),
	(766, 91, 201, '2021-04-03 11:44:47', '2021-04-03 11:44:47', 0, 0, 1, 0, 0, ''),
	(767, 91, 1055, '2021-04-03 11:55:43', '2021-04-03 11:55:43', 1, 0, 0, 0, 0, NULL),
	(768, 91, 43, '2021-04-03 11:55:43', '2021-04-03 11:55:43', 1, 0, 0, 0, 0, NULL),
	(769, 91, 331, '2021-04-03 11:55:43', '2021-04-03 11:55:43', 1, 0, 0, 0, 0, NULL),
	(770, 91, 1094, '2021-04-03 11:55:43', '2021-04-03 11:55:43', 1, 0, 0, 0, 0, NULL),
	(771, 91, 196, '2021-04-03 11:55:43', '2021-04-03 11:55:43', 1, 0, 0, 0, 0, NULL),
	(772, 91, 1389, '2021-04-03 11:55:43', '2021-04-03 11:55:43', 1, 0, 0, 0, 0, NULL),
	(773, 91, 200, '2021-04-03 11:55:43', '2021-04-03 11:55:43', 1, 0, 0, 0, 0, NULL),
	(774, 91, 22, '2021-04-03 11:55:43', '2021-04-03 11:55:43', 1, 0, 0, 0, 0, NULL),
	(775, 91, 910, '2021-04-03 11:55:43', '2021-04-03 11:55:43', 1, 0, 0, 0, 0, NULL),
	(776, 91, 94, '2021-04-03 11:55:43', '2021-04-03 11:55:43', 1, 0, 0, 0, 0, NULL),
	(777, 91, 130, '2021-04-03 11:55:43', '2021-04-03 11:55:43', 1, 0, 0, 0, 0, NULL),
	(778, 91, 1409, '2021-04-03 11:55:43', '2021-04-03 11:55:43', 1, 0, 0, 0, 0, NULL),
	(779, 91, 122, '2021-04-03 11:55:43', '2021-04-03 11:55:43', 1, 0, 0, 0, 0, NULL),
	(780, 91, 3, '2021-04-03 11:55:43', '2021-04-03 11:55:43', 1, 0, 0, 0, 0, NULL),
	(781, 91, 1443, '2021-04-03 11:55:43', '2021-04-03 11:55:43', 1, 0, 0, 0, 0, NULL),
	(782, 91, 1059, '2021-04-03 11:55:43', '2021-04-03 11:55:43', 1, 0, 0, 0, 0, NULL),
	(783, 92, 98, '2021-04-03 13:08:15', '2021-04-03 13:08:15', 0, 0, 1, 0, 0, ''),
	(784, 92, 1055, '2021-04-03 13:09:47', '2021-04-03 13:09:47', 1, 0, 0, 0, 0, NULL),
	(785, 92, 1427, '2021-04-03 13:09:47', '2021-04-03 13:09:47', 1, 0, 0, 0, 0, NULL),
	(786, 92, 43, '2021-04-03 13:09:47', '2021-04-03 13:09:47', 1, 0, 0, 0, 0, NULL),
	(787, 92, 148, '2021-04-03 13:09:47', '2021-04-03 13:09:47', 1, 0, 0, 0, 0, NULL),
	(788, 92, 50, '2021-04-03 13:09:47', '2021-04-03 13:09:47', 1, 0, 0, 0, 0, NULL),
	(789, 92, 1094, '2021-04-03 13:09:47', '2021-04-03 13:09:47', 1, 0, 0, 0, 0, NULL),
	(790, 92, 15, '2021-04-03 13:09:47', '2021-04-03 13:09:47', 1, 0, 0, 0, 0, NULL),
	(791, 92, 1314, '2021-04-03 13:09:47', '2021-04-03 13:09:47', 1, 0, 0, 0, 0, NULL),
	(792, 92, 910, '2021-04-03 13:09:47', '2021-04-03 13:09:47', 1, 0, 0, 0, 0, NULL),
	(793, 92, 32, '2021-04-03 13:09:47', '2021-04-03 13:09:47', 1, 0, 0, 0, 0, NULL),
	(794, 92, 24, '2021-04-03 13:09:47', '2021-04-03 13:09:47', 1, 0, 0, 0, 0, NULL),
	(795, 92, 1059, '2021-04-03 13:09:47', '2021-04-03 13:09:47', 1, 0, 0, 0, 0, NULL),
	(796, 93, 98, '2021-04-15 09:24:38', '2021-04-15 09:24:38', 0, 0, 1, 0, 0, ''),
	(797, 93, 60, '2021-04-15 09:25:01', '2021-04-15 09:25:01', 0, 1, 0, 0, 0, ''),
	(798, 93, 1055, '2021-04-15 09:26:43', '2021-04-15 09:26:43', 1, 0, 0, 0, 0, NULL),
	(799, 93, 331, '2021-04-15 09:26:43', '2021-04-15 09:26:43', 1, 0, 0, 0, 0, NULL),
	(800, 93, 148, '2021-04-15 09:26:43', '2021-04-15 09:26:43', 1, 0, 0, 0, 0, NULL),
	(801, 93, 1392, '2021-04-15 09:26:43', '2021-04-15 09:26:43', 1, 0, 0, 0, 0, NULL),
	(802, 93, 91, '2021-04-15 09:26:43', '2021-04-15 09:26:43', 1, 0, 0, 0, 0, NULL),
	(803, 93, 327, '2021-04-15 09:26:43', '2021-04-15 09:26:43', 1, 0, 0, 0, 0, NULL),
	(804, 93, 1314, '2021-04-15 09:26:43', '2021-04-15 09:26:43', 1, 0, 0, 0, 0, NULL),
	(805, 93, 910, '2021-04-15 09:26:43', '2021-04-15 09:26:43', 1, 0, 0, 0, 0, NULL),
	(806, 93, 130, '2021-04-15 09:26:43', '2021-04-15 09:26:43', 1, 0, 0, 0, 0, NULL),
	(807, 93, 32, '2021-04-15 09:26:43', '2021-04-15 09:26:43', 1, 0, 0, 0, 0, NULL),
	(808, 93, 35, '2021-04-15 09:26:43', '2021-04-15 09:26:43', 1, 0, 0, 0, 0, NULL),
	(809, 93, 3, '2021-04-15 09:26:43', '2021-04-15 09:26:43', 1, 0, 0, 0, 0, NULL),
	(810, 93, 1352, '2021-04-15 09:26:43', '2021-04-15 09:26:43', 1, 0, 0, 0, 0, NULL),
	(811, 93, 48, '2021-04-15 09:26:43', '2021-04-15 09:26:43', 1, 0, 0, 0, 0, NULL),
	(812, 93, 1059, '2021-04-15 09:26:43', '2021-04-15 09:26:43', 1, 0, 0, 0, 0, NULL),
	(813, 94, 60, '2021-04-23 11:24:31', '2021-04-23 11:24:31', 0, 1, 0, 0, 0, ''),
	(814, 94, 201, '2021-04-23 11:24:34', '2021-04-23 11:24:34', 0, 0, 1, 0, 0, ''),
	(815, 94, 43, '2021-04-23 11:25:53', '2021-04-23 11:25:53', 1, 0, 0, 0, 0, NULL),
	(816, 94, 331, '2021-04-23 11:25:53', '2021-04-23 11:25:53', 1, 0, 0, 0, 0, NULL),
	(817, 94, 1094, '2021-04-23 11:25:53', '2021-04-23 11:25:53', 1, 0, 0, 0, 0, NULL),
	(818, 94, 468, '2021-04-23 11:25:53', '2021-04-23 11:25:53', 1, 0, 0, 0, 0, NULL),
	(819, 94, 15, '2021-04-23 11:25:53', '2021-04-23 11:25:53', 1, 0, 0, 0, 0, NULL),
	(820, 94, 91, '2021-04-23 11:25:53', '2021-04-23 11:25:53', 1, 0, 0, 0, 0, NULL),
	(821, 94, 130, '2021-04-23 11:25:53', '2021-04-23 11:25:53', 1, 0, 0, 0, 0, NULL),
	(822, 94, 125, '2021-04-23 11:25:53', '2021-04-23 11:25:53', 1, 0, 0, 0, 0, NULL),
	(823, 94, 122, '2021-04-23 11:25:53', '2021-04-23 11:25:53', 1, 0, 0, 0, 0, NULL),
	(824, 94, 1480, '2021-04-23 11:25:53', '2021-04-23 11:25:53', 1, 0, 0, 0, 0, NULL),
	(825, 94, 3, '2021-04-23 11:25:53', '2021-04-23 11:25:53', 1, 0, 0, 0, 0, NULL),
	(826, 94, 1059, '2021-04-23 11:25:53', '2021-04-23 11:25:53', 1, 0, 0, 0, 0, NULL),
	(827, 95, 60, '2021-04-29 09:56:04', '2021-04-29 09:56:04', 0, 1, 0, 0, 0, ''),
	(828, 95, 1055, '2021-04-29 09:57:00', '2021-04-29 09:57:00', 1, 0, 0, 0, 0, NULL),
	(829, 95, 1392, '2021-04-29 09:57:00', '2021-04-29 09:57:00', 1, 0, 0, 0, 0, NULL),
	(830, 95, 174, '2021-04-29 09:57:00', '2021-04-29 09:57:00', 1, 0, 0, 0, 0, NULL),
	(831, 95, 35, '2021-04-29 09:57:00', '2021-04-29 09:57:00', 1, 0, 0, 0, 0, NULL),
	(832, 95, 124, '2021-04-29 09:57:00', '2021-04-29 09:57:00', 1, 0, 0, 0, 0, NULL),
	(833, 95, 3, '2021-04-29 09:57:00', '2021-04-29 09:57:00', 1, 0, 0, 0, 0, NULL),
	(834, 95, 1352, '2021-04-29 09:57:00', '2021-04-29 09:57:00', 1, 0, 0, 0, 0, NULL),
	(835, 95, 1059, '2021-04-29 09:57:00', '2021-04-29 09:57:00', 1, 0, 0, 0, 0, NULL),
	(836, 95, 1491, '2021-04-29 09:57:00', '2021-04-29 09:57:00', 1, 0, 0, 0, 0, NULL),
	(837, 96, 60, '2021-05-05 10:59:51', '2021-05-05 10:59:51', 0, 1, 0, 0, 0, ''),
	(838, 96, 10, '2021-05-05 11:00:55', '2021-05-05 11:00:55', 1, 0, 0, 0, 0, NULL),
	(839, 96, 331, '2021-05-05 11:00:55', '2021-05-05 11:00:55', 1, 0, 0, 0, 0, NULL),
	(840, 96, 148, '2021-05-05 11:00:55', '2021-05-05 11:00:55', 1, 0, 0, 0, 0, NULL),
	(841, 96, 174, '2021-05-05 11:00:55', '2021-05-05 11:00:55', 1, 0, 0, 0, 0, NULL),
	(842, 96, 91, '2021-05-05 11:00:55', '2021-05-05 11:00:55', 1, 0, 0, 0, 0, NULL),
	(843, 96, 54, '2021-05-05 11:00:55', '2021-05-05 11:00:55', 1, 0, 0, 0, 0, NULL),
	(844, 96, 130, '2021-05-05 11:00:55', '2021-05-05 11:00:55', 1, 0, 0, 0, 0, NULL),
	(845, 96, 129, '2021-05-05 11:00:55', '2021-05-05 11:00:55', 1, 0, 0, 0, 0, NULL),
	(846, 96, 1409, '2021-05-05 11:00:55', '2021-05-05 11:00:55', 1, 0, 0, 0, 0, NULL),
	(847, 96, 124, '2021-05-05 11:00:55', '2021-05-05 11:00:55', 1, 0, 0, 0, 0, NULL),
	(848, 96, 3, '2021-05-05 11:00:55', '2021-05-05 11:00:55', 1, 0, 0, 0, 0, NULL),
	(849, 96, 1059, '2021-05-05 11:00:55', '2021-05-05 11:00:55', 1, 0, 0, 0, 0, NULL),
	(850, 94, 201, '2021-05-05 15:38:38', '2021-05-05 15:38:38', 1, 0, 0, 0, 0, NULL),
	(851, 94, 201, '2021-05-05 15:38:48', '2021-05-05 15:38:48', 1, 0, 0, 0, 0, NULL),
	(852, 91, 201, '2021-05-05 15:42:58', '2021-05-05 15:42:58', 1, 0, 0, 0, 0, NULL),
	(853, 97, 60, '2021-05-28 12:39:58', '2021-05-28 12:39:58', 0, 1, 0, 0, 0, ''),
	(854, 97, 169, '2021-05-28 12:40:12', '2021-05-28 12:40:12', 1, 0, 0, 0, 0, NULL),
	(855, 97, 43, '2021-05-28 12:40:12', '2021-05-28 12:40:12', 1, 0, 0, 0, 0, NULL),
	(856, 97, 196, '2021-05-28 12:40:12', '2021-05-28 12:40:12', 1, 0, 0, 0, 0, NULL),
	(857, 97, 15, '2021-05-28 12:40:12', '2021-05-28 12:40:12', 1, 0, 0, 0, 0, NULL),
	(858, 97, 61, '2021-05-28 12:40:12', '2021-05-28 12:40:12', 1, 0, 0, 0, 0, NULL),
	(859, 97, 910, '2021-05-28 12:40:12', '2021-05-28 12:40:12', 1, 0, 0, 0, 0, NULL),
	(860, 97, 130, '2021-05-28 12:40:12', '2021-05-28 12:40:12', 1, 0, 0, 0, 0, NULL),
	(861, 97, 1517, '2021-05-28 12:40:12', '2021-05-28 12:40:12', 1, 0, 0, 0, 0, NULL),
	(862, 97, 53, '2021-05-28 12:40:12', '2021-05-28 12:40:12', 1, 0, 0, 0, 0, NULL),
	(863, 97, 122, '2021-05-28 12:40:12', '2021-05-28 12:40:12', 1, 0, 0, 0, 0, NULL),
	(864, 97, 3, '2021-05-28 12:40:12', '2021-05-28 12:40:12', 1, 0, 0, 0, 0, NULL),
	(865, 97, 1059, '2021-05-28 12:40:12', '2021-05-28 12:40:12', 1, 0, 0, 0, 0, NULL),
	(866, 98, 144, '2021-06-04 09:41:15', '2021-06-04 09:41:19', 0, 0, 0, 0, 1, 'belum diketahui'),
	(867, 98, 1055, '2021-06-04 09:41:51', '2021-06-04 09:41:51', 1, 0, 0, 0, 0, NULL),
	(868, 98, 331, '2021-06-04 09:41:51', '2021-06-04 09:41:51', 1, 0, 0, 0, 0, NULL),
	(869, 98, 174, '2021-06-04 09:41:51', '2021-06-04 09:41:51', 1, 0, 0, 0, 0, NULL),
	(870, 98, 91, '2021-06-04 09:41:51', '2021-06-04 09:41:51', 1, 0, 0, 0, 0, NULL),
	(871, 98, 910, '2021-06-04 09:41:51', '2021-06-04 09:41:51', 1, 0, 0, 0, 0, NULL),
	(872, 98, 32, '2021-06-04 09:41:51', '2021-06-04 09:41:51', 1, 0, 0, 0, 0, NULL),
	(873, 98, 1517, '2021-06-04 09:41:51', '2021-06-04 09:41:51', 1, 0, 0, 0, 0, NULL),
	(874, 98, 220, '2021-06-04 09:41:51', '2021-06-04 09:41:51', 1, 0, 0, 0, 0, NULL),
	(875, 98, 46, '2021-06-04 09:41:51', '2021-06-04 09:41:51', 1, 0, 0, 0, 0, NULL),
	(876, 98, 35, '2021-06-04 09:41:51', '2021-06-04 09:41:51', 1, 0, 0, 0, 0, NULL),
	(877, 98, 124, '2021-06-04 09:41:51', '2021-06-04 09:41:51', 1, 0, 0, 0, 0, NULL),
	(878, 98, 3, '2021-06-04 09:41:51', '2021-06-04 09:41:51', 1, 0, 0, 0, 0, NULL),
	(879, 98, 1352, '2021-06-04 09:41:51', '2021-06-04 09:41:51', 1, 0, 0, 0, 0, NULL),
	(880, 98, 1443, '2021-06-04 09:41:51', '2021-06-04 09:41:51', 1, 0, 0, 0, 0, NULL),
	(881, 98, 1059, '2021-06-04 09:41:51', '2021-06-04 09:41:51', 1, 0, 0, 0, 0, NULL),
	(882, 99, 10, '2021-06-09 09:34:22', '2021-06-09 09:34:22', 1, 0, 0, 0, 0, NULL),
	(883, 99, 1055, '2021-06-09 09:34:22', '2021-06-09 09:34:22', 1, 0, 0, 0, 0, NULL),
	(884, 99, 331, '2021-06-09 09:34:22', '2021-06-09 09:34:22', 1, 0, 0, 0, 0, NULL),
	(885, 99, 196, '2021-06-09 09:34:22', '2021-06-09 09:34:22', 1, 0, 0, 0, 0, NULL),
	(886, 99, 91, '2021-06-09 09:34:22', '2021-06-09 09:34:22', 1, 0, 0, 0, 0, NULL),
	(887, 99, 54, '2021-06-09 09:34:22', '2021-06-09 09:34:22', 1, 0, 0, 0, 0, NULL),
	(888, 99, 130, '2021-06-09 09:34:22', '2021-06-09 09:34:22', 1, 0, 0, 0, 0, NULL),
	(889, 99, 125, '2021-06-09 09:34:22', '2021-06-09 09:34:22', 1, 0, 0, 0, 0, NULL),
	(890, 99, 3, '2021-06-09 09:34:22', '2021-06-09 09:34:22', 1, 0, 0, 0, 0, NULL),
	(891, 99, 1059, '2021-06-09 09:34:22', '2021-06-09 09:34:22', 1, 0, 0, 0, 0, NULL),
	(892, 93, 98, '2021-06-09 11:56:26', '2021-06-09 11:56:26', 1, 0, 0, 0, 0, NULL),
	(893, 93, 98, '2021-06-09 11:56:38', '2021-06-09 11:56:38', 1, 0, 0, 0, 0, NULL),
	(894, 100, 60, '2021-06-16 09:31:30', '2021-06-16 09:31:30', 0, 1, 0, 0, 0, ''),
	(895, 100, 43, '2021-06-16 09:32:39', '2021-06-16 09:32:39', 1, 0, 0, 0, 0, NULL),
	(896, 100, 331, '2021-06-16 09:32:39', '2021-06-16 09:32:39', 1, 0, 0, 0, 0, NULL),
	(897, 100, 148, '2021-06-16 09:32:39', '2021-06-16 09:32:39', 1, 0, 0, 0, 0, NULL),
	(898, 100, 130, '2021-06-16 09:32:39', '2021-06-16 09:32:39', 1, 0, 0, 0, 0, NULL),
	(899, 100, 129, '2021-06-16 09:32:39', '2021-06-16 09:32:39', 1, 0, 0, 0, 0, NULL),
	(900, 100, 220, '2021-06-16 09:32:39', '2021-06-16 09:32:39', 1, 0, 0, 0, 0, NULL),
	(901, 100, 53, '2021-06-16 09:32:39', '2021-06-16 09:32:39', 1, 0, 0, 0, 0, NULL),
	(902, 100, 498, '2021-06-16 09:32:39', '2021-06-16 09:32:39', 1, 0, 0, 0, 0, NULL),
	(903, 100, 189, '2021-06-16 09:32:39', '2021-06-16 09:32:39', 1, 0, 0, 0, 0, NULL),
	(904, 100, 46, '2021-06-16 09:32:39', '2021-06-16 09:32:39', 1, 0, 0, 0, 0, NULL),
	(905, 100, 35, '2021-06-16 09:32:39', '2021-06-16 09:32:39', 1, 0, 0, 0, 0, NULL),
	(906, 100, 3, '2021-06-16 09:32:39', '2021-06-16 09:32:39', 1, 0, 0, 0, 0, NULL),
	(907, 100, 1059, '2021-06-16 09:32:39', '2021-06-16 09:32:39', 1, 0, 0, 0, 0, NULL),
	(908, 101, 60, '2021-06-23 11:56:01', '2021-06-23 11:56:01', 0, 1, 0, 0, 0, ''),
	(909, 101, 1055, '2021-06-23 11:56:14', '2021-06-23 11:56:14', 1, 0, 0, 0, 0, NULL),
	(910, 101, 43, '2021-06-23 11:56:14', '2021-06-23 11:56:14', 1, 0, 0, 0, 0, NULL),
	(911, 101, 331, '2021-06-23 11:56:14', '2021-06-23 11:56:14', 1, 0, 0, 0, 0, NULL),
	(912, 101, 148, '2021-06-23 11:56:14', '2021-06-23 11:56:14', 1, 0, 0, 0, 0, NULL),
	(913, 101, 98, '2021-06-23 11:56:14', '2021-06-23 11:56:14', 1, 0, 0, 0, 0, NULL),
	(914, 101, 61, '2021-06-23 11:56:14', '2021-06-23 11:56:14', 1, 0, 0, 0, 0, NULL),
	(915, 101, 130, '2021-06-23 11:56:14', '2021-06-23 11:56:14', 1, 0, 0, 0, 0, NULL),
	(916, 101, 32, '2021-06-23 11:56:14', '2021-06-23 11:56:14', 1, 0, 0, 0, 0, NULL),
	(917, 101, 1572, '2021-06-23 11:56:14', '2021-06-23 11:56:14', 1, 0, 0, 0, 0, NULL),
	(918, 101, 189, '2021-06-23 11:56:14', '2021-06-23 11:56:14', 1, 0, 0, 0, 0, NULL),
	(919, 101, 42, '2021-06-23 11:56:14', '2021-06-23 11:56:14', 1, 0, 0, 0, 0, NULL),
	(920, 101, 3, '2021-06-23 11:56:14', '2021-06-23 11:56:14', 1, 0, 0, 0, 0, NULL),
	(921, 101, 1352, '2021-06-23 11:56:14', '2021-06-23 11:56:14', 1, 0, 0, 0, 0, NULL),
	(922, 101, 1059, '2021-06-23 11:56:14', '2021-06-23 11:56:14', 1, 0, 0, 0, 0, NULL),
	(923, 101, 1491, '2021-06-23 11:56:14', '2021-06-23 11:56:14', 1, 0, 0, 0, 0, NULL),
	(924, 102, 331, '2021-06-29 11:57:21', '2021-06-29 11:57:21', 1, 0, 0, 0, 0, NULL),
	(925, 102, 50, '2021-06-29 11:57:21', '2021-06-29 11:57:21', 1, 0, 0, 0, 0, NULL),
	(926, 102, 91, '2021-06-29 11:57:21', '2021-06-29 11:57:21', 1, 0, 0, 0, 0, NULL),
	(927, 102, 1590, '2021-06-29 11:57:21', '2021-06-29 11:57:21', 1, 0, 0, 0, 0, NULL),
	(928, 102, 94, '2021-06-29 11:57:21', '2021-06-29 11:57:21', 1, 0, 0, 0, 0, NULL),
	(929, 102, 130, '2021-06-29 11:57:21', '2021-06-29 11:57:21', 1, 0, 0, 0, 0, NULL),
	(930, 102, 129, '2021-06-29 11:57:21', '2021-06-29 11:57:21', 1, 0, 0, 0, 0, NULL),
	(931, 102, 220, '2021-06-29 11:57:21', '2021-06-29 11:57:21', 1, 0, 0, 0, 0, NULL),
	(932, 102, 46, '2021-06-29 11:57:21', '2021-06-29 11:57:21', 1, 0, 0, 0, 0, NULL),
	(933, 102, 35, '2021-06-29 11:57:21', '2021-06-29 11:57:21', 1, 0, 0, 0, 0, NULL),
	(934, 102, 3, '2021-06-29 11:57:21', '2021-06-29 11:57:21', 1, 0, 0, 0, 0, NULL),
	(935, 102, 1059, '2021-06-29 11:57:21', '2021-06-29 11:57:21', 1, 0, 0, 0, 0, NULL),
	(936, 102, 1491, '2021-06-29 11:57:21', '2021-06-29 11:57:21', 1, 0, 0, 0, 0, NULL),
	(937, 103, 1605, '2021-07-10 11:44:05', '2021-07-10 11:44:07', 0, 0, 0, 0, 1, 'hold'),
	(938, 103, 1594, '2021-07-10 11:44:08', '2021-07-10 11:44:10', 0, 0, 0, 0, 1, 'hold'),
	(939, 103, 43, '2021-07-10 11:44:19', '2021-07-10 11:44:19', 1, 0, 0, 0, 0, NULL),
	(940, 103, 331, '2021-07-10 11:44:19', '2021-07-10 11:44:19', 1, 0, 0, 0, 0, NULL),
	(941, 103, 1600, '2021-07-10 11:44:20', '2021-07-10 11:44:20', 1, 0, 0, 0, 0, NULL),
	(942, 103, 130, '2021-07-10 11:44:20', '2021-07-10 11:44:20', 1, 0, 0, 0, 0, NULL),
	(943, 103, 498, '2021-07-10 11:44:20', '2021-07-10 11:44:20', 1, 0, 0, 0, 0, NULL),
	(944, 103, 189, '2021-07-10 11:44:20', '2021-07-10 11:44:20', 1, 0, 0, 0, 0, NULL),
	(945, 103, 46, '2021-07-10 11:44:20', '2021-07-10 11:44:20', 1, 0, 0, 0, 0, NULL),
	(946, 104, 60, '2021-07-10 11:44:38', '2021-07-10 11:44:38', 0, 1, 0, 0, 0, ''),
	(947, 104, 1055, '2021-07-10 11:45:09', '2021-07-10 11:45:09', 1, 0, 0, 0, 0, NULL),
	(948, 104, 130, '2021-07-10 11:45:09', '2021-07-10 11:45:09', 1, 0, 0, 0, 0, NULL),
	(949, 104, 3, '2021-07-10 11:45:09', '2021-07-10 11:45:09', 1, 0, 0, 0, 0, NULL),
	(950, 104, 1059, '2021-07-10 11:45:09', '2021-07-10 11:45:09', 1, 0, 0, 0, 0, NULL),
	(951, 105, 1055, '2021-07-17 07:30:45', '2021-07-17 07:30:45', 1, 0, 0, 0, 0, NULL),
	(952, 105, 331, '2021-07-17 07:30:45', '2021-07-17 07:30:45', 1, 0, 0, 0, 0, NULL),
	(953, 105, 468, '2021-07-17 07:30:45', '2021-07-17 07:30:45', 1, 0, 0, 0, 0, NULL),
	(954, 105, 15, '2021-07-17 07:30:46', '2021-07-17 07:30:46', 1, 0, 0, 0, 0, NULL),
	(955, 105, 129, '2021-07-17 07:30:46', '2021-07-17 07:30:46', 1, 0, 0, 0, 0, NULL),
	(956, 105, 46, '2021-07-17 07:30:46', '2021-07-17 07:30:46', 1, 0, 0, 0, 0, NULL),
	(957, 105, 132, '2021-07-17 07:30:46', '2021-07-17 07:30:46', 1, 0, 0, 0, 0, NULL),
	(958, 105, 202, '2021-07-17 07:30:46', '2021-07-17 07:30:46', 1, 0, 0, 0, 0, NULL),
	(959, 105, 3, '2021-07-17 07:30:46', '2021-07-17 07:30:46', 1, 0, 0, 0, 0, NULL),
	(960, 105, 1059, '2021-07-17 07:30:46', '2021-07-17 07:30:46', 1, 0, 0, 0, 0, NULL),
	(961, 105, 1491, '2021-07-17 07:30:46', '2021-07-17 07:30:46', 1, 0, 0, 0, 0, NULL);
/*!40000 ALTER TABLE `nd_rekam_faktur_pajak_email` ENABLE KEYS */;

-- Dumping structure for table favourtdj_system.nd_reminder
DROP TABLE IF EXISTS `nd_reminder`;
CREATE TABLE IF NOT EXISTS `nd_reminder` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `note_order_id` int(11) DEFAULT NULL,
  `reminder` datetime DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `status_on` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table favourtdj_system.nd_reminder: ~0 rows (approximately)
DELETE FROM `nd_reminder`;
/*!40000 ALTER TABLE `nd_reminder` DISABLE KEYS */;
/*!40000 ALTER TABLE `nd_reminder` ENABLE KEYS */;

-- Dumping structure for table favourtdj_system.nd_request_barang
DROP TABLE IF EXISTS `nd_request_barang`;
CREATE TABLE IF NOT EXISTS `nd_request_barang` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `toko_id` tinyint(4) NOT NULL DEFAULT '1',
  `tanggal` date DEFAULT NULL,
  `no_request` smallint(6) DEFAULT NULL,
  `supplier_id` smallint(6) DEFAULT NULL,
  `closed_date` datetime DEFAULT NULL,
  `user_id` tinyint(4) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- Dumping data for table favourtdj_system.nd_request_barang: ~2 rows (approximately)
DELETE FROM `nd_request_barang`;
/*!40000 ALTER TABLE `nd_request_barang` DISABLE KEYS */;
INSERT INTO `nd_request_barang` (`id`, `toko_id`, `tanggal`, `no_request`, `supplier_id`, `closed_date`, `user_id`, `created_at`, `updated_at`) VALUES
	(1, 1, '2021-06-17', 1, 1, NULL, 9, '2021-06-17 14:21:14', '2021-06-17 14:21:14'),
	(2, 1, '2021-06-29', 2, 1, NULL, 9, '2021-06-29 14:00:00', '2021-06-29 14:00:00');
/*!40000 ALTER TABLE `nd_request_barang` ENABLE KEYS */;

-- Dumping structure for table favourtdj_system.nd_request_barang_batch
DROP TABLE IF EXISTS `nd_request_barang_batch`;
CREATE TABLE IF NOT EXISTS `nd_request_barang_batch` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tanggal` date DEFAULT NULL,
  `attn` varchar(50) DEFAULT NULL,
  `request_barang_id` smallint(5) unsigned DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `batch` tinyint(2) DEFAULT NULL,
  `user_id` tinyint(2) DEFAULT NULL,
  `locked_by` tinyint(4) DEFAULT NULL,
  `locked_date` datetime DEFAULT NULL,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status_aktif` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- Dumping data for table favourtdj_system.nd_request_barang_batch: ~3 rows (approximately)
DELETE FROM `nd_request_barang_batch`;
/*!40000 ALTER TABLE `nd_request_barang_batch` DISABLE KEYS */;
INSERT INTO `nd_request_barang_batch` (`id`, `tanggal`, `attn`, `request_barang_id`, `status`, `batch`, `user_id`, `locked_by`, `locked_date`, `updated_at`, `created_at`, `status_aktif`) VALUES
	(1, '2021-06-25', 'Uki', 1, 0, 1, 9, 9, '2021-06-29 13:22:12', '2021-07-05 02:48:50', '2021-06-17 14:21:14', 1),
	(2, '2021-06-30', 'Uki', 2, 0, 1, 9, 9, '2021-06-30 12:24:54', '2021-07-05 11:29:01', '2021-06-29 14:00:00', 1),
	(3, '2021-07-23', 'Uki', 2, 1, 2, 9, 1, '2021-08-03 12:08:54', '2021-08-03 12:08:54', '2021-07-05 11:29:01', 1);
/*!40000 ALTER TABLE `nd_request_barang_batch` ENABLE KEYS */;

-- Dumping structure for table favourtdj_system.nd_request_barang_detail
DROP TABLE IF EXISTS `nd_request_barang_detail`;
CREATE TABLE IF NOT EXISTS `nd_request_barang_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `request_barang_batch_id` int(11) DEFAULT NULL,
  `po_pembelian_batch_id` int(11) DEFAULT NULL,
  `bulan_request` date DEFAULT NULL,
  `barang_id` int(11) DEFAULT NULL,
  `warna_id` int(11) DEFAULT NULL,
  `qty` decimal(15,2) DEFAULT NULL,
  `status_urgent` tinyint(1) NOT NULL DEFAULT '0',
  `is_po_baru` tinyint(4) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=366 DEFAULT CHARSET=latin1;

-- Dumping data for table favourtdj_system.nd_request_barang_detail: ~329 rows (approximately)
DELETE FROM `nd_request_barang_detail`;
/*!40000 ALTER TABLE `nd_request_barang_detail` DISABLE KEYS */;
INSERT INTO `nd_request_barang_detail` (`id`, `request_barang_batch_id`, `po_pembelian_batch_id`, `bulan_request`, `barang_id`, `warna_id`, `qty`, `status_urgent`, `is_po_baru`, `created_at`, `updated_at`) VALUES
	(1, 1, 207, '2020-06-01', 1, 30, 5000.00, 1, 0, '2021-06-17 15:10:25', '2021-06-17 16:22:13'),
	(2, 1, 209, '2020-06-01', 1, 30, 2500.00, 1, 0, '2021-06-17 15:10:25', '2021-06-17 16:22:13'),
	(3, 1, 242, '2020-06-01', 1, 79, 7500.00, 1, 0, '2021-06-17 15:10:25', '2021-06-17 16:22:16'),
	(4, 1, 209, '2020-06-01', 1, 14, 2500.00, 1, 0, '2021-06-17 15:10:25', '2021-06-17 16:22:17'),
	(5, 1, 242, '2020-06-01', 1, 54, 2500.00, 0, 0, '2021-06-17 15:10:25', '2021-06-17 15:10:25'),
	(6, 1, 217, '2020-06-01', 1, 69, 2500.00, 0, 0, '2021-06-17 15:10:25', '2021-06-17 15:17:48'),
	(7, 1, 207, '2020-06-01', 1, 36, 2200.00, 1, 0, '2021-06-17 15:10:25', '2021-06-17 16:22:34'),
	(8, 1, 209, '2020-06-01', 1, 36, 5300.00, 1, 0, '2021-06-17 15:10:25', '2021-06-17 16:22:35'),
	(9, 1, 189, '2020-06-01', 1, 73, 2200.00, 1, 0, '2021-06-17 15:10:25', '2021-06-17 16:22:42'),
	(10, 1, 217, '2020-06-01', 1, 83, 5000.00, 1, 0, '2021-06-17 15:10:25', '2021-06-17 16:22:44'),
	(11, 1, 242, '2020-06-01', 1, 19, 2500.00, 0, 0, '2021-06-17 15:10:25', '2021-06-17 15:17:48'),
	(12, 1, 242, '2020-06-01', 1, 89, 2500.00, 0, 0, '2021-06-17 15:10:25', '2021-06-17 15:17:48'),
	(13, 1, 242, '2020-06-01', 1, 84, 2500.00, 0, 0, '2021-06-17 15:10:25', '2021-06-17 15:17:48'),
	(14, 1, 207, '2020-06-01', 1, 46, 2600.00, 1, 0, '2021-06-17 15:10:25', '2021-06-17 16:22:46'),
	(15, 1, 217, '2020-06-01', 1, 37, 2500.00, 0, 0, '2021-06-17 15:10:25', '2021-06-17 15:17:48'),
	(16, 1, 189, '2020-06-01', 1, 9, 1800.00, 1, 0, '2021-06-17 15:10:25', '2021-06-17 16:22:55'),
	(17, 1, 209, '2020-06-01', 1, 9, 3200.00, 1, 0, '2021-06-17 15:10:25', '2021-06-17 16:22:54'),
	(18, 1, 209, '2020-06-01', 1, 77, 2500.00, 0, 0, '2021-06-17 15:10:25', '2021-06-17 15:10:25'),
	(19, 1, 242, '2020-06-01', 1, 70, 2500.00, 0, 0, '2021-06-17 15:10:25', '2021-06-17 15:17:48'),
	(20, 1, 209, '2020-06-01', 1, 7, 5000.00, 1, 0, '2021-06-17 15:10:25', '2021-06-17 16:23:04'),
	(21, 1, 227, '2020-06-01', 1, 32, 5000.00, 1, 0, '2021-06-17 15:10:25', '2021-06-17 16:23:08'),
	(22, 1, 209, '2020-06-01', 1, 67, 2500.00, 1, 0, '2021-06-17 15:10:25', '2021-06-17 16:23:09'),
	(23, 1, 217, '2020-06-01', 1, 67, 5000.00, 1, 0, '2021-06-17 15:10:25', '2021-06-17 16:23:10'),
	(24, 1, 209, '2020-06-01', 1, 42, 7500.00, 1, 0, '2021-06-17 15:10:25', '2021-06-17 16:23:15'),
	(25, 1, 217, '2020-06-01', 1, 78, 5000.00, 1, 0, '2021-06-17 15:10:25', '2021-06-17 16:23:16'),
	(27, 1, 209, '2020-06-01', 1, 71, 5000.00, 0, 0, '2021-06-17 15:10:25', '2021-06-17 15:17:48'),
	(28, 1, 242, '2020-07-01', 1, 30, 5000.00, 0, 0, '2021-06-17 15:10:25', '2021-06-17 15:17:48'),
	(29, 1, 242, '2020-07-01', 1, 79, 5000.00, 0, 0, '2021-06-17 15:10:25', '2021-06-17 15:10:25'),
	(30, 1, 242, '2020-07-01', 1, 16, 5000.00, 0, 0, '2021-06-17 15:10:25', '2021-06-17 15:17:48'),
	(31, 1, 242, '2020-07-01', 1, 45, 7500.00, 0, 0, '2021-06-17 15:10:25', '2021-06-17 15:10:25'),
	(32, 1, 209, '2020-07-01', 1, 14, 5000.00, 0, 0, '2021-06-17 15:10:25', '2021-06-17 15:10:25'),
	(34, 1, 242, '2020-07-01', 1, 54, 5000.00, 0, 0, '2021-06-17 15:10:25', '2021-06-17 15:10:25'),
	(35, 1, 242, '2020-07-01', 1, 21, 2500.00, 0, 0, '2021-06-17 15:10:25', '2021-06-17 15:17:48'),
	(36, 1, 207, '2020-07-01', 1, 31, 2500.00, 0, 0, '2021-06-17 15:10:25', '2021-06-17 15:17:48'),
	(38, 1, 209, '2020-07-01', 1, 36, 4700.00, 0, 0, '2021-06-17 15:10:25', '2021-06-17 15:17:48'),
	(40, 1, 217, '2020-07-01', 1, 73, 4700.00, 0, 0, '2021-06-17 15:10:25', '2021-06-17 15:17:48'),
	(42, 1, 209, '2020-07-01', 1, 3, 2500.00, 0, 0, '2021-06-17 15:10:25', '2021-06-17 15:17:48'),
	(45, 1, 227, '2020-07-01', 1, 46, 300.00, 0, 0, '2021-06-17 15:10:25', '2021-06-17 15:17:48'),
	(46, 1, 242, '2020-07-01', 1, 46, 7200.00, 0, 0, '2021-06-17 15:10:25', '2021-06-17 15:17:48'),
	(47, 1, 209, '2020-07-01', 1, 9, 5000.00, 0, 0, '2021-06-17 15:10:25', '2021-06-17 15:17:48'),
	(48, 1, 242, '2020-07-01', 1, 77, 7500.00, 0, 0, '2021-06-17 15:10:25', '2021-06-17 15:10:25'),
	(49, 1, 217, '2020-07-01', 1, 7, 5000.00, 0, 0, '2021-06-17 15:10:25', '2021-06-17 15:10:25'),
	(51, 1, 242, '2020-07-01', 1, 32, 5000.00, 0, 0, '2021-06-17 15:10:25', '2021-06-17 15:17:48'),
	(52, 1, 242, '2020-07-01', 1, 67, 5000.00, 0, 0, '2021-06-17 15:10:25', '2021-06-17 15:17:48'),
	(53, 1, 209, '2020-07-01', 1, 44, 2500.00, 0, 0, '2021-06-17 15:10:25', '2021-06-17 15:17:48'),
	(54, 1, 209, '2020-07-01', 1, 17, 2500.00, 0, 0, '2021-06-17 15:10:25', '2021-06-17 15:10:25'),
	(55, 1, 217, '2020-07-01', 1, 17, 2500.00, 0, 0, '2021-06-17 15:10:25', '2021-06-17 15:17:48'),
	(56, 1, 242, '2020-07-01', 1, 20, 7500.00, 0, 0, '2021-06-17 15:10:25', '2021-06-17 15:10:25'),
	(58, 1, 209, '2020-07-01', 1, 13, 2300.00, 0, 0, '2021-06-17 15:10:25', '2021-06-17 15:10:25'),
	(59, 1, 242, '2020-07-01', 1, 13, 2700.00, 0, 0, '2021-06-17 15:10:25', '2021-06-17 15:17:48'),
	(61, 1, 209, '2020-07-01', 1, 42, 7500.00, 0, 0, '2021-06-17 15:10:25', '2021-06-17 15:17:48'),
	(62, 1, 242, '2020-07-01', 1, 78, 5000.00, 0, 0, '2021-06-17 15:10:25', '2021-06-17 15:17:48'),
	(63, 1, 166, '2020-07-01', 1, 15, 2900.00, 0, 0, '2021-06-17 15:10:25', '2021-06-17 15:10:25'),
	(64, 1, 217, '2020-07-01', 1, 15, 2100.00, 0, 0, '2021-06-17 15:10:25', '2021-06-17 15:17:48'),
	(65, 1, 202, '2020-07-01', 1, 33, 2600.00, 0, 0, '2021-06-17 15:10:25', '2021-06-17 15:10:25'),
	(66, 1, 209, '2020-07-01', 1, 33, 2400.00, 0, 0, '2021-06-17 15:10:25', '2021-06-17 15:17:48'),
	(68, 1, 242, '2020-07-01', 1, 71, 5000.00, 0, 0, '2021-06-17 15:10:25', '2021-06-17 15:17:48'),
	(69, 1, 218, '2020-07-01', 1, 39, 2500.00, 0, 0, '2021-06-17 15:10:25', '2021-06-17 15:17:48'),
	(70, 1, 229, '2020-06-01', 13, 61, 2500.00, 0, 0, '2021-06-17 15:13:41', '2021-06-17 15:13:41'),
	(71, 1, 222, '2020-06-01', 13, 4, 5100.00, 1, 0, '2021-06-17 15:13:41', '2021-06-17 16:21:16'),
	(72, 1, 243, '2020-06-01', 13, 4, 24900.00, 1, 0, '2021-06-17 15:13:41', '2021-06-17 16:21:15'),
	(73, 1, 224, '2020-06-01', 13, 9, 5000.00, 0, 0, '2021-06-17 15:13:41', '2021-06-17 15:13:41'),
	(74, 1, 243, '2020-06-01', 13, 7, 35000.00, 1, 0, '2021-06-17 15:13:41', '2021-06-17 16:21:18'),
	(75, 1, 229, '2020-06-01', 13, 60, 5000.00, 0, 0, '2021-06-17 15:13:41', '2021-06-17 15:13:41'),
	(76, 1, 229, '2020-07-01', 13, 61, 2500.00, 0, 0, '2021-06-17 15:13:41', '2021-06-17 15:13:41'),
	(77, 1, 243, '2020-07-01', 13, 61, 2500.00, 0, 0, '2021-06-17 15:13:41', '2021-06-17 15:13:41'),
	(78, 1, 243, '2020-07-01', 13, 22, 2500.00, 0, 0, '2021-06-17 15:13:41', '2021-06-17 15:13:41'),
	(79, 1, 243, '2020-07-01', 13, 4, 30000.00, 0, 0, '2021-06-17 15:13:41', '2021-06-17 15:29:53'),
	(80, 1, 224, '2020-07-01', 13, 9, 5000.00, 0, 0, '2021-06-17 15:13:41', '2021-06-17 15:13:41'),
	(81, 1, 243, '2020-07-01', 13, 7, 20000.00, 0, 0, '2021-06-17 15:13:41', '2021-06-17 15:28:32'),
	(82, 1, 229, '2020-07-01', 13, 60, 5000.00, 0, 0, '2021-06-17 15:13:41', '2021-06-17 15:13:41'),
	(83, 1, 217, '2020-06-01', 1, 73, 300.00, 0, 0, '2021-06-17 15:17:48', '2021-06-17 15:17:48'),
	(84, 1, 209, '2020-06-01', 1, 46, 2700.00, 1, 0, '2021-06-17 15:17:48', '2021-06-17 16:22:47'),
	(85, 1, 227, '2020-06-01', 1, 46, 4700.00, 1, 0, '2021-06-17 15:17:48', '2021-06-17 16:22:47'),
	(86, 1, 217, '2020-06-01', 1, 7, 2500.00, 1, 0, '2021-06-17 15:17:48', '2021-06-17 16:23:04'),
	(87, 1, 242, '2020-06-01', 1, 32, 2500.00, 1, 0, '2021-06-17 15:17:48', '2021-06-17 16:23:09'),
	(88, 1, 242, '2020-07-01', 1, 36, 2800.00, 0, 0, '2021-06-17 15:17:48', '2021-06-17 15:17:48'),
	(89, 1, 227, '2020-07-01', 1, 73, 300.00, 0, 0, '2021-06-17 15:17:48', '2021-06-17 15:17:48'),
	(90, 1, 242, '2020-07-01', 1, 83, 5000.00, 0, 0, '2021-06-17 15:17:48', '2021-06-17 15:17:48'),
	(91, 1, 228, '2020-06-01', 7, 47, 2500.00, 0, 0, '2021-06-17 15:24:06', '2021-06-17 15:24:06'),
	(92, 1, 223, '2020-06-01', 7, 2, 10000.00, 0, 0, '2021-06-17 15:24:06', '2021-06-17 15:24:06'),
	(93, 1, 223, '2020-06-01', 7, 4, 60500.00, 1, 0, '2021-06-17 15:24:06', '2021-06-17 16:21:04'),
	(94, 1, 228, '2020-06-01', 7, 4, 4500.00, 1, 0, '2021-06-17 15:24:06', '2021-06-17 16:21:02'),
	(95, 1, 223, '2020-06-01', 7, 7, 61900.00, 1, 0, '2021-06-17 15:24:06', '2021-06-17 16:21:05'),
	(96, 1, 228, '2020-06-01', 7, 7, 3100.00, 1, 0, '2021-06-17 15:24:06', '2021-06-17 16:21:06'),
	(97, 1, 228, '2020-07-01', 7, 47, 7500.00, 0, 0, '2021-06-17 15:24:06', '2021-06-17 15:24:06'),
	(98, 1, 228, '2020-07-01', 7, 2, 10000.00, 0, 0, '2021-06-17 15:24:06', '2021-06-17 15:24:06'),
	(99, 1, 228, '2020-07-01', 7, 4, 75000.00, 0, 0, '2021-06-17 15:24:06', '2021-06-17 15:24:06'),
	(100, 1, 228, '2020-07-01', 7, 7, 75000.00, 0, 0, '2021-06-17 15:24:06', '2021-06-17 15:24:06'),
	(103, 1, 226, '2020-07-01', 6, 54, 2500.00, 0, 0, '2021-06-17 15:33:46', '2021-06-17 15:33:46'),
	(104, 1, 238, '2020-07-01', 6, 12, 2500.00, 0, 0, '2021-06-17 15:33:46', '2021-06-17 15:33:46'),
	(105, 1, 203, '2020-07-01', 6, 3, 1100.00, 0, 0, '2021-06-17 15:33:46', '2021-06-17 15:33:46'),
	(106, 1, 226, '2020-07-01', 6, 3, 13900.00, 0, 0, '2021-06-17 15:33:46', '2021-06-17 15:33:46'),
	(107, 1, 238, '2020-07-01', 6, 22, 7500.00, 0, 0, '2021-06-17 15:33:46', '2021-06-17 15:33:46'),
	(108, 1, 226, '2020-07-01', 6, 6, 5100.00, 0, 0, '2021-06-17 15:33:46', '2021-06-17 15:33:46'),
	(109, 1, 238, '2020-07-01', 6, 6, 4900.00, 0, 0, '2021-06-17 15:33:46', '2021-06-17 15:33:46'),
	(110, 1, 238, '2020-07-01', 6, 7, 7500.00, 0, 0, '2021-06-17 15:33:46', '2021-06-17 15:33:46'),
	(111, 1, 238, '2020-07-01', 6, 15, 2500.00, 0, 0, '2021-06-17 15:33:46', '2021-06-17 15:33:46'),
	(112, 1, 238, '2020-06-01', 6, 22, 7500.00, 0, 0, '2021-06-17 15:33:46', '2021-06-17 15:33:46'),
	(113, 1, 238, '2020-06-01', 6, 15, 2500.00, 0, 0, '2021-06-17 15:33:46', '2021-06-17 15:33:46'),
	(114, 1, 244, '2020-07-01', 4, 3, 10000.00, 0, 0, '2021-06-17 15:42:23', '2021-06-17 15:42:23'),
	(115, 1, 244, '2020-07-01', 4, 61, 2500.00, 0, 0, '2021-06-17 15:42:23', '2021-06-17 15:42:23'),
	(116, 1, 244, '2020-07-01', 4, 6, 2500.00, 0, 0, '2021-06-17 15:42:23', '2021-06-17 15:42:23'),
	(117, 1, 215, '2020-07-01', 4, 4, 2300.00, 0, 0, '2021-06-17 15:42:23', '2021-06-17 15:42:23'),
	(118, 1, 0, '2020-07-01', 4, 4, 200.00, 0, 1, '2021-06-17 15:42:23', '2021-06-17 15:42:23'),
	(119, 1, 244, '2020-07-01', 4, 7, 2500.00, 0, 0, '2021-06-17 15:42:23', '2021-06-17 15:42:23'),
	(120, 1, 244, '2020-06-01', 4, 3, 10000.00, 1, 0, '2021-06-17 15:42:23', '2021-06-17 16:23:26'),
	(121, 1, 244, '2020-06-01', 4, 64, 2500.00, 0, 0, '2021-06-17 15:42:23', '2021-06-17 15:42:23'),
	(122, 1, 215, '2020-06-01', 4, 6, 5000.00, 0, 0, '2021-06-17 15:42:23', '2021-06-17 15:42:23'),
	(123, 1, 215, '2020-06-01', 4, 41, 2500.00, 0, 0, '2021-06-17 15:42:23', '2021-06-17 15:42:23'),
	(124, 1, 165, '2020-06-01', 27, 91, 2000.00, 0, 0, '2021-06-17 15:45:43', '2021-06-17 15:45:43'),
	(125, 1, 216, '2020-06-01', 27, 56, 2500.00, 0, 0, '2021-06-17 15:45:43', '2021-06-17 15:45:43'),
	(126, 1, 151, '2020-06-01', 27, 3, 3900.00, 0, 0, '2021-06-17 15:45:43', '2021-06-17 15:45:43'),
	(127, 1, 172, '2020-06-01', 27, 3, 1100.00, 0, 0, '2021-06-17 15:45:43', '2021-06-17 15:45:43'),
	(128, 1, 216, '2020-06-01', 27, 9, 2500.00, 0, 0, '2021-06-17 15:45:43', '2021-06-17 15:45:43'),
	(129, 1, 216, '2020-06-01', 27, 5, 2400.00, 0, 0, '2021-06-17 15:45:43', '2021-06-17 15:45:43'),
	(130, 1, 230, '2020-06-01', 27, 5, 2600.00, 0, 0, '2021-06-17 15:45:43', '2021-06-17 15:45:43'),
	(131, 1, 216, '2020-06-01', 27, 92, 3000.00, 0, 0, '2021-06-17 15:45:43', '2021-06-17 15:45:43'),
	(132, 1, 172, '2020-06-01', 27, 93, 1500.00, 0, 0, '2021-06-17 15:45:43', '2021-06-17 15:45:43'),
	(133, 1, 230, '2020-06-01', 27, 93, 1000.00, 0, 0, '2021-06-17 15:45:43', '2021-06-17 15:45:43'),
	(134, 1, 216, '2020-06-01', 27, 13, 3000.00, 0, 0, '2021-06-17 15:45:43', '2021-06-17 15:45:43'),
	(135, 1, 216, '2020-06-01', 27, 42, 2500.00, 0, 0, '2021-06-17 15:45:43', '2021-06-17 15:45:43'),
	(136, 1, 216, '2020-07-01', 27, 56, 2500.00, 0, 0, '2021-06-17 15:45:43', '2021-06-17 15:45:43'),
	(137, 1, 172, '2020-07-01', 27, 3, 3500.00, 0, 0, '2021-06-17 15:45:43', '2021-06-17 15:45:43'),
	(138, 1, 216, '2020-07-01', 27, 3, 4000.00, 0, 0, '2021-06-17 15:45:43', '2021-06-17 15:45:43'),
	(139, 1, 216, '2020-07-01', 27, 9, 2500.00, 0, 0, '2021-06-17 15:45:43', '2021-06-17 15:45:43'),
	(140, 1, 230, '2020-07-01', 27, 5, 2500.00, 0, 0, '2021-06-17 15:45:43', '2021-06-17 15:45:43'),
	(141, 1, 236, '2020-07-01', 12, 12, 10000.00, 0, 0, '2021-06-17 15:48:38', '2021-06-17 15:48:38'),
	(142, 1, 236, '2020-07-01', 12, 31, 10000.00, 0, 0, '2021-06-17 15:48:38', '2021-06-17 15:48:38'),
	(143, 1, 196, '2020-07-01', 12, 3, 10700.00, 0, 0, '2021-06-17 15:48:38', '2021-06-17 15:48:38'),
	(144, 1, 197, '2020-07-01', 12, 3, 4300.00, 0, 0, '2021-06-17 15:48:38', '2021-06-17 15:48:38'),
	(145, 1, 236, '2020-07-01', 12, 3, 15000.00, 0, 0, '2021-06-17 15:48:38', '2021-06-17 15:48:38'),
	(146, 1, 196, '2020-06-01', 12, 3, 10000.00, 0, 0, '2021-06-17 15:48:38', '2021-06-17 15:48:38'),
	(147, 1, 211, '2020-07-01', 15, 3, 50000.00, 0, 0, '2021-06-17 15:49:11', '2021-06-17 15:49:11'),
	(148, 2, 165, '2020-07-01', 27, 91, 2000.00, 0, 0, '2021-06-29 14:03:15', '2021-06-29 14:03:15'),
	(149, 2, 151, '2020-07-01', 27, 3, 3900.00, 0, 0, '2021-06-29 14:03:15', '2021-06-29 14:03:15'),
	(150, 2, 172, '2020-07-01', 27, 3, 3600.00, 0, 0, '2021-06-29 14:03:15', '2021-06-29 14:03:15'),
	(151, 2, 216, '2020-07-01', 27, 9, 3000.00, 0, 0, '2021-06-29 14:03:15', '2021-06-29 14:03:15'),
	(152, 2, 216, '2020-07-01', 27, 5, 2400.00, 0, 0, '2021-06-29 14:03:15', '2021-06-29 14:03:15'),
	(153, 2, 230, '2020-07-01', 27, 5, 2600.00, 0, 0, '2021-06-29 14:03:15', '2021-06-29 14:03:15'),
	(155, 2, 172, '2020-07-01', 27, 93, 1500.00, 0, 0, '2021-06-29 14:03:15', '2021-06-29 14:03:15'),
	(156, 2, 230, '2020-07-01', 27, 93, 1000.00, 0, 0, '2021-06-29 14:03:15', '2021-06-29 14:03:15'),
	(157, 2, 216, '2020-08-01', 27, 56, 2700.00, 0, 0, '2021-06-29 14:03:15', '2021-06-29 14:03:15'),
	(158, 2, 230, '2020-08-01', 27, 56, 2300.00, 0, 0, '2021-06-29 14:03:15', '2021-06-29 14:03:15'),
	(159, 2, 172, '2020-08-01', 27, 3, 1000.00, 0, 0, '2021-06-29 14:03:15', '2021-06-29 14:03:15'),
	(160, 2, 216, '2020-08-01', 27, 3, 5100.00, 0, 0, '2021-06-29 14:03:15', '2021-06-29 14:03:15'),
	(161, 2, 230, '2020-08-01', 27, 3, 1400.00, 0, 0, '2021-06-29 14:03:15', '2021-06-29 14:03:15'),
	(162, 2, 216, '2020-08-01', 27, 9, 200.00, 0, 0, '2021-06-29 14:03:15', '2021-06-29 14:03:15'),
	(163, 2, 0, '2020-08-01', 27, 9, 4800.00, 0, 1, '2021-06-29 14:03:15', '2021-06-29 14:03:15'),
	(164, 2, 230, '2020-08-01', 27, 93, 2500.00, 0, 0, '2021-06-29 14:03:15', '2021-06-29 14:03:15'),
	(165, 2, 216, '2020-08-01', 28, 24, 5000.00, 0, 0, '2021-06-29 14:05:11', '2021-06-29 14:05:11'),
	(166, 2, 245, '2020-07-01', 28, 8, 20000.00, 1, 0, '2021-06-29 14:05:11', '2021-06-30 12:18:23'),
	(167, 2, 246, '2020-07-01', 28, 8, 30000.00, 1, 0, '2021-06-29 14:05:11', '2021-06-30 12:18:23'),
	(170, 2, 207, '2020-07-01', 1, 30, 2500.00, 0, 0, '2021-06-29 15:19:57', '2021-06-29 15:19:57'),
	(171, 2, 242, '2020-07-01', 1, 79, 7500.00, 1, 0, '2021-06-29 15:19:57', '2021-06-30 12:08:40'),
	(172, 2, 207, '2020-08-01', 1, 30, 2500.00, 0, 0, '2021-06-29 15:22:15', '2021-06-29 15:22:15'),
	(173, 2, 242, '2020-08-01', 1, 79, 7500.00, 0, 0, '2021-06-29 15:22:15', '2021-06-29 15:22:15'),
	(174, 2, 211, '2020-07-01', 15, 3, 58500.00, 0, 0, '2021-06-29 15:50:11', '2021-06-29 15:50:11'),
	(175, 2, 231, '2020-07-01', 15, 3, 91200.00, 0, 0, '2021-06-29 15:50:11', '2021-06-29 15:50:11'),
	(176, 2, 232, '2020-07-01', 15, 3, 300.00, 0, 0, '2021-06-29 15:50:11', '2021-06-29 15:50:11'),
	(177, 2, 232, '2020-08-01', 15, 3, 99700.00, 0, 0, '2021-06-29 15:50:11', '2021-06-29 15:50:11'),
	(178, 2, 233, '2020-08-01', 15, 3, 50300.00, 0, 0, '2021-06-29 15:50:11', '2021-06-29 15:50:11'),
	(179, 2, 236, '2020-07-01', 12, 12, 10000.00, 0, 0, '2021-06-29 15:53:50', '2021-06-29 15:53:50'),
	(180, 2, 236, '2020-07-01', 12, 31, 7500.00, 0, 0, '2021-06-29 15:53:50', '2021-06-29 15:53:50'),
	(181, 2, 196, '2020-07-01', 12, 3, 20700.00, 1, 0, '2021-06-29 15:53:50', '2021-06-30 12:19:12'),
	(182, 2, 197, '2020-07-01', 12, 3, 4300.00, 1, 0, '2021-06-29 15:53:50', '2021-06-30 12:19:11'),
	(183, 2, 236, '2020-07-01', 12, 3, 25000.00, 1, 0, '2021-06-29 15:53:50', '2021-06-30 12:19:13'),
	(184, 2, 236, '2020-08-01', 12, 12, 15000.00, 0, 0, '2021-06-29 15:53:50', '2021-06-29 15:53:50'),
	(185, 2, 236, '2020-08-01', 12, 31, 7500.00, 0, 0, '2021-06-29 15:53:50', '2021-06-29 15:53:50'),
	(186, 2, 236, '2020-08-01', 12, 3, 50000.00, 0, 0, '2021-06-29 15:53:50', '2021-06-29 15:53:50'),
	(189, 2, 248, '2020-08-01', 13, 3, 10000.00, 0, 0, '2021-06-29 15:56:42', '2021-07-23 09:22:55'),
	(192, 2, 248, '2020-08-01', 13, 61, 5000.00, 0, 0, '2021-06-29 15:56:42', '2021-07-23 11:39:47'),
	(197, 2, 222, '2020-07-01', 13, 4, 5100.00, 1, 0, '2021-06-29 15:56:42', '2021-06-30 12:20:48'),
	(200, 2, 242, '2020-07-01', 1, 16, 10000.00, 1, 0, '2021-06-29 15:57:22', '2021-06-30 12:09:54'),
	(201, 2, 242, '2020-07-01', 1, 45, 5000.00, 0, 0, '2021-06-29 15:57:22', '2021-06-29 15:57:22'),
	(202, 2, 209, '2020-07-01', 1, 14, 3200.00, 0, 0, '2021-06-29 15:57:22', '2021-06-29 15:57:22'),
	(203, 2, 242, '2020-07-01', 1, 14, 1800.00, 0, 0, '2021-06-29 15:57:22', '2021-06-29 15:57:22'),
	(204, 2, 242, '2020-07-01', 1, 54, 5000.00, 1, 0, '2021-06-29 15:57:22', '2021-06-30 12:11:14'),
	(205, 2, 242, '2020-07-01', 1, 21, 2500.00, 0, 0, '2021-06-29 15:57:22', '2021-06-29 15:57:22'),
	(206, 2, 217, '2020-07-01', 1, 69, 2500.00, 0, 0, '2021-06-29 15:57:22', '2021-06-29 15:57:22'),
	(207, 2, 207, '2020-07-01', 1, 36, 2200.00, 0, 0, '2021-06-29 15:57:22', '2021-07-22 12:19:50'),
	(208, 2, 209, '2020-07-01', 1, 36, 5000.00, 0, 0, '2021-06-29 15:57:22', '2021-07-22 12:19:47'),
	(209, 2, 242, '2020-07-01', 1, 36, 300.00, 0, 0, '2021-06-29 15:57:22', '2021-07-22 12:19:48'),
	(210, 2, 189, '2020-07-01', 1, 73, 2200.00, 0, 0, '2021-06-29 15:57:22', '2021-06-29 15:57:22'),
	(211, 2, 217, '2020-07-01', 1, 73, 300.00, 0, 0, '2021-06-29 15:57:22', '2021-06-29 15:57:22'),
	(212, 2, 217, '2020-07-01', 1, 83, 2500.00, 0, 0, '2021-06-29 15:57:22', '2021-06-29 15:57:22'),
	(213, 2, 242, '2020-07-01', 1, 19, 2500.00, 0, 0, '2021-06-29 15:57:22', '2021-06-29 15:57:22'),
	(214, 2, 242, '2020-07-01', 1, 89, 2500.00, 0, 0, '2021-06-29 15:57:22', '2021-06-29 15:57:22'),
	(215, 2, 242, '2020-07-01', 1, 84, 2500.00, 0, 0, '2021-06-29 15:57:22', '2021-06-29 15:57:22'),
	(216, 2, 209, '2020-07-01', 1, 3, 5000.00, 0, 0, '2021-06-29 15:57:22', '2021-06-29 15:57:22'),
	(217, 2, 242, '2020-08-01', 1, 16, 0.00, 0, 0, '2021-06-29 15:57:22', '2021-06-30 12:09:54'),
	(218, 2, 242, '2020-08-01', 1, 45, 2500.00, 0, 0, '2021-06-29 15:57:22', '2021-06-29 15:57:22'),
	(219, 2, 242, '2020-08-01', 1, 54, 5000.00, 0, 0, '2021-06-29 15:57:22', '2021-06-29 15:57:22'),
	(220, 2, 217, '2020-08-01', 1, 12, 2500.00, 0, 0, '2021-06-29 15:57:22', '2021-06-29 15:57:22'),
	(221, 2, 207, '2020-08-01', 1, 31, 2700.00, 0, 0, '2021-06-29 15:57:22', '2021-06-29 15:57:22'),
	(222, 2, 209, '2020-08-01', 1, 31, 4800.00, 0, 0, '2021-06-29 15:57:22', '2021-06-29 15:57:22'),
	(223, 2, 242, '2020-08-01', 1, 36, 7200.00, 0, 0, '2021-06-29 15:57:22', '2021-06-29 15:57:22'),
	(224, 2, 0, '2020-08-01', 1, 36, 300.00, 0, 1, '2021-06-29 15:57:22', '2021-06-29 15:57:22'),
	(225, 2, 217, '2020-08-01', 1, 73, 2500.00, 0, 0, '2021-06-29 15:57:22', '2021-06-29 15:57:22'),
	(226, 2, 217, '2020-08-01', 1, 83, 2500.00, 0, 0, '2021-06-29 15:57:22', '2021-06-29 15:57:22'),
	(227, 2, 242, '2020-08-01', 1, 83, 2500.00, 0, 0, '2021-06-29 15:57:22', '2021-06-29 15:57:22'),
	(228, 2, 209, '2020-08-01', 1, 3, 2600.00, 0, 0, '2021-06-29 15:57:22', '2021-06-29 15:57:22'),
	(229, 2, 0, '2020-08-01', 1, 3, 2400.00, 0, 1, '2021-06-29 15:57:22', '2021-06-29 15:57:22'),
	(230, 2, 228, '2020-07-01', 7, 12, 5000.00, 0, 0, '2021-06-29 16:01:42', '2021-06-29 16:01:42'),
	(231, 2, 228, '2020-07-01', 7, 47, 10000.00, 1, 0, '2021-06-29 16:01:42', '2021-06-30 12:16:58'),
	(233, 2, 148, '2020-07-01', 7, 61, 2400.00, 0, 0, '2021-06-29 16:01:42', '2021-06-29 16:01:42'),
	(234, 2, 228, '2020-07-01', 7, 61, 2600.00, 0, 0, '2021-06-29 16:01:42', '2021-06-29 16:01:42'),
	(236, 2, 228, '2020-07-01', 7, 2, 5000.00, 0, 0, '2021-06-29 16:01:42', '2021-07-23 09:21:19'),
	(237, 2, 223, '2020-07-01', 7, 4, 4400.00, 1, 0, '2021-06-29 16:01:42', '2021-06-30 12:16:42'),
	(238, 2, 228, '2020-07-01', 7, 4, 75400.00, 1, 0, '2021-06-29 16:01:42', '2021-08-09 11:13:16'),
	(239, 2, 228, '2020-07-01', 7, 7, 60000.00, 1, 0, '2021-06-29 16:01:42', '2021-06-30 12:16:44'),
	(241, 2, 228, '2020-08-01', 7, 47, 5000.00, 0, 0, '2021-06-29 16:01:42', '2021-07-30 12:41:46'),
	(242, 2, 228, '2020-08-01', 7, 66, 2500.00, 0, 0, '2021-06-29 16:01:42', '2021-06-29 16:01:42'),
	(244, 2, 228, '2020-08-01', 7, 61, 2500.00, 0, 0, '2021-06-29 16:01:42', '2021-07-30 12:41:46'),
	(245, 2, 0, '2020-08-01', 7, 61, 0.00, 0, 1, '2021-06-29 16:01:42', '2021-07-30 12:41:46'),
	(246, 2, 228, '2020-08-01', 7, 2, 5000.00, 0, 0, '2021-06-29 16:01:42', '2021-06-29 16:01:42'),
	(247, 2, 228, '2020-08-01', 7, 4, 0.00, 0, 0, '2021-06-29 16:01:42', '2021-08-09 11:13:16'),
	(249, 2, 228, '2020-08-01', 7, 7, 4900.00, 0, 0, '2021-06-29 16:01:42', '2021-07-23 09:21:19'),
	(251, 2, 256, '2020-08-01', 7, 15, 0.00, 0, 0, '2021-06-29 16:01:42', '2021-08-09 11:13:16'),
	(252, 2, 207, '2020-07-01', 1, 46, 2600.00, 1, 0, '2021-06-29 16:03:09', '2021-06-30 12:11:47'),
	(254, 2, 227, '2020-07-01', 1, 46, 4900.00, 1, 0, '2021-06-29 16:03:09', '2021-07-23 08:47:10'),
	(255, 2, 217, '2020-07-01', 1, 37, 2500.00, 0, 0, '2021-06-29 16:03:09', '2021-06-29 16:03:09'),
	(256, 2, 189, '2020-07-01', 1, 9, 1800.00, 1, 0, '2021-06-29 16:03:09', '2021-06-30 12:11:49'),
	(257, 2, 209, '2020-07-01', 1, 9, 5700.00, 1, 0, '2021-06-29 16:03:09', '2021-06-30 12:11:50'),
	(258, 2, 209, '2020-07-01', 1, 77, 2500.00, 0, 0, '2021-06-29 16:03:09', '2021-06-29 16:03:09'),
	(259, 2, 242, '2020-07-01', 1, 77, 2500.00, 0, 0, '2021-06-29 16:03:09', '2021-06-29 16:03:09'),
	(260, 2, 209, '2020-07-01', 1, 7, 2500.00, 0, 0, '2021-06-29 16:03:09', '2021-06-29 16:03:09'),
	(261, 2, 227, '2020-07-01', 1, 32, 5000.00, 1, 0, '2021-06-29 16:03:09', '2021-06-30 12:12:34'),
	(262, 2, 209, '2020-07-01', 1, 17, 2500.00, 0, 0, '2021-06-29 16:03:09', '2021-06-29 16:03:09'),
	(263, 2, 242, '2020-07-01', 1, 20, 7500.00, 0, 0, '2021-06-29 16:03:09', '2021-07-22 12:20:42'),
	(264, 2, 209, '2020-07-01', 1, 42, 5000.00, 1, 0, '2021-06-29 16:03:09', '2021-06-30 12:13:41'),
	(265, 2, 217, '2020-07-01', 1, 78, 2500.00, 0, 0, '2021-06-29 16:03:09', '2021-06-29 16:03:09'),
	(266, 2, 166, '2020-07-01', 1, 15, 2500.00, 0, 0, '2021-06-29 16:03:09', '2021-06-29 16:03:09'),
	(267, 2, 202, '2020-07-01', 1, 33, 2500.00, 0, 0, '2021-06-29 16:03:09', '2021-06-29 16:03:09'),
	(268, 2, 209, '2020-07-01', 1, 71, 2400.00, 0, 0, '2021-06-29 16:03:09', '2021-06-29 16:03:09'),
	(269, 2, 242, '2020-07-01', 1, 71, 100.00, 0, 0, '2021-06-29 16:03:09', '2021-06-29 16:03:09'),
	(270, 2, 218, '2020-07-01', 1, 39, 5200.00, 0, 0, '2021-06-29 16:03:09', '2021-06-29 16:03:09'),
	(271, 2, 247, '2020-07-01', 1, 39, 2300.00, 0, 0, '2021-06-29 16:03:09', '2021-06-29 16:03:09'),
	(272, 2, 238, '2020-07-01', 6, 12, 5000.00, 1, 0, '2021-06-29 16:05:15', '2021-06-30 12:16:18'),
	(273, 2, 203, '2020-07-01', 6, 3, 1100.00, 0, 0, '2021-06-29 16:05:15', '2021-06-29 16:05:15'),
	(274, 2, 238, '2020-07-01', 6, 3, 8900.00, 0, 0, '2021-06-29 16:05:15', '2021-06-29 16:05:15'),
	(275, 2, 238, '2020-07-01', 6, 22, 5000.00, 1, 0, '2021-06-29 16:05:15', '2021-06-30 12:16:09'),
	(276, 2, 238, '2020-07-01', 6, 6, 2500.00, 0, 0, '2021-06-29 16:05:15', '2021-06-29 16:05:15'),
	(277, 2, 238, '2020-07-01', 6, 15, 2500.00, 1, 0, '2021-06-29 16:05:15', '2021-06-30 12:16:17'),
	(278, 2, 238, '2020-08-01', 6, 47, 2500.00, 0, 0, '2021-06-29 16:05:15', '2021-06-29 16:05:15'),
	(279, 2, 238, '2020-08-01', 6, 3, 20000.00, 0, 0, '2021-06-29 16:05:15', '2021-06-29 16:05:15'),
	(280, 2, 238, '2020-08-01', 6, 22, 7500.00, 0, 0, '2021-06-29 16:05:15', '2021-06-29 16:05:15'),
	(281, 2, 238, '2020-08-01', 6, 6, 5000.00, 0, 0, '2021-06-29 16:05:15', '2021-06-29 16:05:15'),
	(282, 2, 238, '2020-08-01', 6, 7, 0.00, 0, 0, '2021-06-29 16:05:15', '2021-07-23 09:19:46'),
	(283, 2, 254, '2020-08-01', 6, 7, 7500.00, 0, 0, '2021-06-29 16:05:15', '2021-07-23 09:19:46'),
	(284, 2, 238, '2020-08-01', 6, 15, 2500.00, 0, 0, '2021-06-29 16:05:15', '2021-06-29 16:05:15'),
	(285, 2, 238, '2020-07-01', 9, 24, 7500.00, 0, 0, '2021-06-29 16:05:45', '2021-06-29 16:05:45'),
	(286, 2, 238, '2020-08-01', 9, 24, 5000.00, 0, 0, '2021-06-29 16:05:45', '2021-06-29 16:05:45'),
	(287, 2, 254, '2020-08-01', 9, 24, 5000.00, 0, 1, '2021-06-29 16:05:45', '2021-07-22 12:19:06'),
	(288, 2, 227, '2020-08-01', 1, 46, 100.00, 0, 0, '2021-06-29 16:07:40', '2021-07-23 08:47:10'),
	(289, 2, 242, '2020-08-01', 1, 46, 9900.00, 0, 0, '2021-06-29 16:07:40', '2021-07-23 08:47:10'),
	(290, 2, 209, '2020-08-01', 1, 9, 1800.00, 0, 0, '2021-06-29 16:07:40', '2021-06-29 16:07:40'),
	(291, 2, 247, '2020-08-01', 1, 9, 5700.00, 0, 0, '2021-06-29 16:07:40', '2021-06-29 17:29:17'),
	(292, 2, 209, '2020-08-01', 1, 43, 5000.00, 0, 0, '2021-06-29 16:07:40', '2021-06-29 17:29:17'),
	(293, 2, 217, '2020-08-01', 1, 41, 2500.00, 0, 0, '2021-06-29 16:07:40', '2021-06-29 17:29:17'),
	(294, 2, 227, '2020-08-01', 1, 82, 2500.00, 0, 0, '2021-06-29 16:07:40', '2021-06-29 17:29:17'),
	(295, 2, 242, '2020-08-01', 1, 77, 5000.00, 0, 0, '2021-06-29 16:07:40', '2021-06-29 16:07:40'),
	(296, 2, 209, '2020-08-01', 1, 58, 2500.00, 0, 0, '2021-06-29 16:07:40', '2021-06-29 16:07:40'),
	(298, 2, 242, '2020-08-01', 1, 70, 2500.00, 0, 0, '2021-06-29 16:07:40', '2021-06-29 17:29:17'),
	(299, 2, 244, '2020-07-01', 4, 3, 20000.00, 1, 0, '2021-06-29 16:08:08', '2021-06-30 12:14:44'),
	(300, 2, 244, '2020-07-01', 4, 64, 2500.00, 0, 0, '2021-06-29 16:08:08', '2021-06-29 16:08:08'),
	(301, 2, 215, '2020-07-01', 4, 4, 2300.00, 1, 0, '2021-06-29 16:08:08', '2021-06-30 12:15:39'),
	(302, 2, 249, '2020-07-01', 4, 4, 200.00, 0, 1, '2021-06-29 16:08:08', '2021-07-23 10:00:50'),
	(303, 2, 215, '2020-07-01', 4, 41, 2500.00, 0, 0, '2021-06-29 16:08:08', '2021-06-29 16:08:08'),
	(304, 2, 244, '2020-08-01', 4, 3, 10000.00, 0, 0, '2021-06-29 16:08:08', '2021-06-29 16:08:08'),
	(305, 2, 249, '2020-08-01', 4, 3, 10000.00, 0, 1, '2021-06-29 16:08:08', '2021-07-22 12:19:06'),
	(306, 2, 244, '2020-08-01', 4, 61, 2500.00, 0, 0, '2021-06-29 16:08:08', '2021-06-29 16:08:08'),
	(307, 2, 249, '2020-08-01', 4, 4, 2500.00, 0, 1, '2021-06-29 16:08:08', '2021-07-22 12:19:06'),
	(308, 2, 217, '2020-08-01', 1, 7, 5000.00, 0, 0, '2021-06-29 16:08:26', '2021-06-29 16:08:26'),
	(309, 2, 242, '2020-08-01', 1, 32, 5000.00, 0, 0, '2021-06-29 16:08:26', '2021-06-29 17:29:17'),
	(311, 2, 217, '2020-08-01', 1, 67, 2500.00, 0, 0, '2021-06-29 16:08:26', '2021-06-29 17:29:17'),
	(312, 2, 217, '2020-08-01', 1, 44, 2500.00, 0, 0, '2021-06-29 16:08:26', '2021-06-29 17:29:17'),
	(313, 2, 244, '2020-07-01', 11, 24, 5000.00, 0, 0, '2021-06-29 16:08:41', '2021-06-29 16:08:41'),
	(314, 2, 249, '2020-08-01', 11, 24, 5000.00, 0, 1, '2021-06-29 16:08:41', '2021-07-22 12:19:06'),
	(315, 2, 217, '2020-08-01', 1, 17, 2500.00, 0, 0, '2021-06-29 16:10:08', '2021-06-29 16:10:08'),
	(316, 2, 209, '2020-07-01', 1, 13, 2300.00, 0, 0, '2021-06-29 16:33:42', '2021-07-22 12:20:43'),
	(317, 2, 242, '2020-07-01', 1, 13, 2700.00, 0, 0, '2021-06-29 16:33:42', '2021-07-22 12:20:44'),
	(318, 2, 242, '2020-08-01', 1, 13, 4800.00, 0, 0, '2021-06-29 16:33:42', '2021-06-29 16:33:42'),
	(319, 2, 247, '2020-08-01', 1, 13, 200.00, 0, 0, '2021-06-29 16:33:42', '2021-06-29 16:33:42'),
	(320, 2, 202, '2020-08-01', 1, 33, 100.00, 0, 0, '2021-06-29 17:24:53', '2021-06-29 17:24:53'),
	(321, 2, 209, '2020-08-01', 1, 33, 2400.00, 0, 0, '2021-06-29 17:24:53', '2021-06-29 17:29:17'),
	(323, 2, 242, '2020-08-01', 1, 71, 5000.00, 0, 0, '2021-06-29 17:24:53', '2021-06-29 17:29:17'),
	(324, 2, 247, '2020-08-01', 1, 39, 5000.00, 0, 0, '2021-06-29 17:24:53', '2021-06-29 17:29:17'),
	(325, 2, 209, '2020-07-01', 1, 67, 2500.00, 0, 0, '2021-06-29 17:29:17', '2021-07-22 12:20:34'),
	(326, 2, 217, '2020-07-01', 1, 67, 2500.00, 0, 0, '2021-06-29 17:29:17', '2021-07-22 12:20:35'),
	(327, 2, 242, '2020-08-01', 1, 67, 2500.00, 0, 0, '2021-06-29 17:29:17', '2021-06-29 17:29:17'),
	(328, 2, 209, '2020-08-01', 1, 42, 2500.00, 0, 0, '2021-06-29 17:31:28', '2021-06-29 17:31:28'),
	(329, 2, 217, '2020-08-01', 1, 78, 2500.00, 0, 0, '2021-06-29 17:31:28', '2021-06-29 17:31:28'),
	(330, 2, 247, '2020-07-01', 1, 16, 2500.00, 1, 0, '2021-06-30 12:09:54', '2021-06-30 12:10:31'),
	(331, 2, 247, '2020-08-01', 1, 16, 7500.00, 0, 0, '2021-06-30 12:09:54', '2021-06-30 12:09:54'),
	(332, 2, 256, '2020-07-01', 7, 15, 5000.00, 0, 0, '2021-07-22 12:19:06', '2021-08-09 11:13:16'),
	(333, 2, 256, '2020-08-01', 7, 3, 25000.00, 0, 0, '2021-07-22 12:19:06', '2021-08-09 11:13:16'),
	(334, 2, 0, '2020-08-01', 7, 4, 20200.00, 0, 1, '2021-07-22 12:19:06', '2021-08-09 11:13:16'),
	(335, 2, 256, '2020-08-01', 7, 7, 50000.00, 0, 0, '2021-07-22 12:19:06', '2021-08-09 11:13:16'),
	(336, 3, 249, '2020-07-01', 4, 3, 30000.00, 0, 0, '2021-07-22 13:23:27', '2021-07-22 20:09:06'),
	(337, 3, 0, '2020-07-01', 4, 3, 0.00, 0, 1, '2021-07-22 13:23:27', '2021-07-22 20:09:06'),
	(338, 3, 0, '2020-08-01', 4, 3, 20000.00, 0, 1, '2021-07-22 13:25:09', '2021-07-23 10:02:22'),
	(339, 3, 244, '2020-07-01', 4, 3, 30000.00, 0, 0, '2021-07-22 20:09:06', '2021-07-22 20:09:06'),
	(340, 3, 249, '2020-08-01', 4, 3, 20000.00, 0, 0, '2021-07-22 20:09:06', '2021-07-22 20:09:06'),
	(341, 2, 0, '2020-07-01', 27, 92, 3000.00, 0, 1, '2021-07-22 22:14:35', '2021-07-22 22:14:35'),
	(342, 2, 0, '2020-08-01', 7, 47, 0.00, 0, 1, '2021-07-23 09:21:19', '2021-07-30 12:41:46'),
	(343, 3, 215, '2020-08-01', 4, 4, 2300.00, 0, 0, '2021-07-23 10:01:15', '2021-07-23 10:01:15'),
	(344, 3, 249, '2020-08-01', 4, 4, 5200.00, 0, 0, '2021-07-23 10:01:15', '2021-07-23 10:01:15'),
	(345, 3, 244, '2020-08-01', 4, 7, 2500.00, 0, 0, '2021-07-23 10:01:56', '2021-07-23 10:01:56'),
	(346, 3, 249, '2020-08-01', 4, 7, 2500.00, 0, 0, '2021-07-23 10:01:56', '2021-07-23 10:01:56'),
	(347, 3, 238, '2020-08-01', 6, 6, 5000.00, 0, 0, '2021-07-23 10:50:09', '2021-07-23 10:52:59'),
	(348, 3, 254, '2020-08-01', 6, 41, 2500.00, 0, 0, '2021-07-23 10:50:09', '2021-07-23 10:50:09'),
	(349, 2, 248, '2020-08-01', 13, 16, 5000.00, 0, 0, '2021-07-23 11:39:47', '2021-07-23 11:39:47'),
	(350, 2, 255, '2020-08-01', 13, 3, 40000.00, 0, 0, '2021-07-23 11:39:47', '2021-07-23 11:39:47'),
	(351, 2, 0, '2020-08-01', 13, 4, 20000.00, 0, 1, '2021-07-23 11:39:47', '2021-07-23 11:39:47'),
	(352, 2, 0, '2020-08-01', 13, 7, 20000.00, 0, 1, '2021-07-23 11:39:47', '2021-07-23 11:39:47'),
	(353, 2, 248, '2020-08-01', 13, 60, 5000.00, 0, 0, '2021-07-23 11:39:47', '2021-07-23 11:39:47'),
	(354, 2, 248, '2020-07-01', 13, 3, 20000.00, 0, 0, '2021-07-23 11:39:47', '2021-07-23 11:39:47'),
	(355, 2, 255, '2020-07-01', 13, 4, 10000.00, 0, 0, '2021-07-23 11:39:47', '2021-07-23 11:39:47'),
	(356, 2, 0, '2020-07-01', 13, 4, 24900.00, 0, 1, '2021-07-23 11:39:47', '2021-07-23 11:39:47'),
	(357, 2, 255, '2020-07-01', 13, 7, 30000.00, 0, 0, '2021-07-23 11:39:47', '2021-07-23 11:39:47'),
	(358, 2, 0, '2020-07-01', 13, 7, 5000.00, 0, 1, '2021-07-23 11:39:47', '2021-07-23 11:39:47'),
	(359, 2, 255, '2020-08-01', 7, 4, 19800.00, 0, 0, '2021-07-30 12:41:46', '2021-08-09 11:13:16'),
	(360, 2, 256, '2020-07-01', 7, 3, 35000.00, 0, 0, '2021-08-09 11:13:16', '2021-08-09 11:13:16'),
	(361, 2, 255, '2020-07-01', 7, 4, 20200.00, 0, 0, '2021-08-09 11:13:16', '2021-08-09 11:13:16'),
	(362, 2, 257, '2020-08-01', 7, 3, 10000.00, 0, 0, '2021-08-09 11:13:16', '2021-08-09 11:13:16'),
	(363, 2, 257, '2020-08-01', 7, 4, 30000.00, 0, 0, '2021-08-09 11:13:16', '2021-08-09 11:13:16'),
	(364, 2, 257, '2020-08-01', 7, 7, 5100.00, 0, 0, '2021-08-09 11:13:16', '2021-08-09 11:13:16'),
	(365, 2, 257, '2020-08-01', 7, 15, 2500.00, 0, 0, '2021-08-09 11:13:16', '2021-08-09 11:13:16');
/*!40000 ALTER TABLE `nd_request_barang_detail` ENABLE KEYS */;

-- Dumping structure for table favourtdj_system.nd_request_barang_qty
DROP TABLE IF EXISTS `nd_request_barang_qty`;
CREATE TABLE IF NOT EXISTS `nd_request_barang_qty` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `request_barang_batch_id` int(11) DEFAULT NULL,
  `bulan_request` date DEFAULT NULL,
  `barang_id` int(11) DEFAULT NULL,
  `warna_id` int(11) DEFAULT NULL,
  `qty` decimal(15,2) DEFAULT NULL,
  `finished_date` datetime DEFAULT NULL,
  `finished_by` tinyint(4) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `isFinished` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=242 DEFAULT CHARSET=latin1;

-- Dumping data for table favourtdj_system.nd_request_barang_qty: ~239 rows (approximately)
DELETE FROM `nd_request_barang_qty`;
/*!40000 ALTER TABLE `nd_request_barang_qty` DISABLE KEYS */;
INSERT INTO `nd_request_barang_qty` (`id`, `request_barang_batch_id`, `bulan_request`, `barang_id`, `warna_id`, `qty`, `finished_date`, `finished_by`, `created_at`, `updated_at`, `isFinished`) VALUES
	(1, 1, '2020-06-01', 1, 30, 7500.00, NULL, NULL, '2021-06-29 13:24:01', '2021-06-29 13:24:01', 0),
	(2, 1, '2020-07-01', 1, 30, 5000.00, NULL, NULL, '2021-06-29 13:24:01', '2021-06-29 13:24:01', 0),
	(3, 1, '2020-06-01', 1, 79, 7500.00, NULL, NULL, '2021-06-29 13:24:01', '2021-06-29 13:24:01', 0),
	(4, 1, '2020-07-01', 1, 79, 5000.00, NULL, NULL, '2021-06-29 13:24:01', '2021-06-29 13:24:01', 0),
	(5, 1, '2020-07-01', 1, 16, 5000.00, NULL, NULL, '2021-06-29 13:24:01', '2021-06-29 13:24:01', 0),
	(6, 1, '2020-07-01', 1, 45, 7500.00, NULL, NULL, '2021-06-29 13:24:01', '2021-06-29 13:24:01', 0),
	(7, 1, '2020-06-01', 1, 14, 2500.00, NULL, NULL, '2021-06-29 13:24:01', '2021-06-29 13:24:01', 0),
	(8, 1, '2020-07-01', 1, 14, 5000.00, NULL, NULL, '2021-06-29 13:24:01', '2021-06-29 13:24:01', 0),
	(9, 1, '2020-06-01', 1, 54, 2500.00, NULL, NULL, '2021-06-29 13:24:01', '2021-06-29 13:24:01', 0),
	(10, 1, '2020-07-01', 1, 54, 5000.00, NULL, NULL, '2021-06-29 13:24:01', '2021-06-29 13:24:01', 0),
	(11, 1, '2020-07-01', 1, 21, 2500.00, NULL, NULL, '2021-06-29 13:24:01', '2021-06-29 13:24:01', 0),
	(12, 1, '2020-07-01', 1, 31, 2500.00, NULL, NULL, '2021-06-29 13:24:01', '2021-06-29 13:24:01', 0),
	(13, 1, '2020-06-01', 1, 69, 2500.00, NULL, NULL, '2021-06-29 13:24:01', '2021-06-29 13:24:01', 0),
	(14, 1, '2020-06-01', 1, 36, 7500.00, NULL, NULL, '2021-06-29 13:24:01', '2021-06-29 13:24:01', 0),
	(15, 1, '2020-07-01', 1, 36, 7500.00, NULL, NULL, '2021-06-29 13:24:01', '2021-06-29 13:24:01', 0),
	(16, 1, '2020-06-01', 1, 73, 2500.00, NULL, NULL, '2021-06-29 13:24:01', '2021-06-29 13:24:01', 0),
	(17, 1, '2020-07-01', 1, 73, 5000.00, NULL, NULL, '2021-06-29 13:24:01', '2021-06-29 13:24:01', 0),
	(18, 1, '2020-06-01', 1, 83, 5000.00, NULL, NULL, '2021-06-29 13:24:01', '2021-06-29 13:24:01', 0),
	(19, 1, '2020-07-01', 1, 83, 5000.00, NULL, NULL, '2021-06-29 13:24:01', '2021-06-29 13:24:01', 0),
	(20, 1, '2020-06-01', 1, 19, 2500.00, NULL, NULL, '2021-06-29 13:24:01', '2021-06-29 13:24:01', 0),
	(21, 1, '2020-06-01', 1, 89, 2500.00, NULL, NULL, '2021-06-29 13:24:01', '2021-06-29 13:24:01', 0),
	(22, 1, '2020-06-01', 1, 84, 2500.00, NULL, NULL, '2021-06-29 13:24:01', '2021-06-29 13:24:01', 0),
	(23, 1, '2020-07-01', 1, 3, 2500.00, NULL, NULL, '2021-06-29 13:24:01', '2021-06-29 13:24:01', 0),
	(24, 1, '2020-06-01', 1, 46, 10000.00, NULL, NULL, '2021-06-29 13:24:01', '2021-06-29 13:24:01', 0),
	(25, 1, '2020-07-01', 1, 46, 7500.00, NULL, NULL, '2021-06-29 13:24:01', '2021-06-29 13:24:01', 0),
	(26, 1, '2020-06-01', 1, 37, 2500.00, NULL, NULL, '2021-06-29 13:24:01', '2021-06-29 13:24:01', 0),
	(27, 1, '2020-06-01', 1, 9, 5000.00, NULL, NULL, '2021-06-29 13:24:01', '2021-06-29 13:24:01', 0),
	(28, 1, '2020-07-01', 1, 9, 5000.00, NULL, NULL, '2021-06-29 13:24:01', '2021-06-29 13:24:01', 0),
	(29, 1, '2020-06-01', 1, 77, 2500.00, NULL, NULL, '2021-06-29 13:24:01', '2021-06-29 13:24:01', 0),
	(30, 1, '2020-07-01', 1, 77, 7500.00, NULL, NULL, '2021-06-29 13:24:01', '2021-06-29 13:24:01', 0),
	(31, 1, '2020-06-01', 1, 70, 2500.00, NULL, NULL, '2021-06-29 13:24:01', '2021-06-29 13:24:01', 0),
	(32, 1, '2020-06-01', 1, 7, 7500.00, NULL, NULL, '2021-06-29 13:24:01', '2021-06-29 13:24:01', 0),
	(33, 1, '2020-07-01', 1, 7, 5000.00, NULL, NULL, '2021-06-29 13:24:01', '2021-06-29 13:24:01', 0),
	(34, 1, '2020-06-01', 1, 32, 7500.00, NULL, NULL, '2021-06-29 13:24:01', '2021-06-29 13:24:01', 0),
	(35, 1, '2020-07-01', 1, 32, 5000.00, NULL, NULL, '2021-06-29 13:24:01', '2021-06-29 13:24:01', 0),
	(36, 1, '2020-06-01', 1, 67, 7500.00, NULL, NULL, '2021-06-29 13:24:01', '2021-06-29 13:24:01', 0),
	(37, 1, '2020-07-01', 1, 67, 5000.00, NULL, NULL, '2021-06-29 13:24:01', '2021-06-29 13:24:01', 0),
	(38, 1, '2020-07-01', 1, 44, 2500.00, NULL, NULL, '2021-06-29 13:24:01', '2021-06-29 13:24:01', 0),
	(39, 1, '2020-07-01', 1, 17, 5000.00, NULL, NULL, '2021-06-29 13:24:01', '2021-06-29 13:24:01', 0),
	(40, 1, '2020-07-01', 1, 20, 7500.00, NULL, NULL, '2021-06-29 13:24:01', '2021-06-29 13:24:01', 0),
	(41, 1, '2020-07-01', 1, 13, 5000.00, NULL, NULL, '2021-06-29 13:24:01', '2021-06-29 13:24:01', 0),
	(42, 1, '2020-06-01', 1, 42, 7500.00, NULL, NULL, '2021-06-29 13:24:01', '2021-06-29 13:24:01', 0),
	(43, 1, '2020-07-01', 1, 42, 7500.00, NULL, NULL, '2021-06-29 13:24:01', '2021-06-29 13:24:01', 0),
	(44, 1, '2020-06-01', 1, 78, 5000.00, NULL, NULL, '2021-06-29 13:24:01', '2021-06-29 13:24:01', 0),
	(45, 1, '2020-07-01', 1, 78, 5000.00, NULL, NULL, '2021-06-29 13:24:01', '2021-06-29 13:24:01', 0),
	(46, 1, '2020-07-01', 1, 15, 5000.00, NULL, NULL, '2021-06-29 13:24:01', '2021-06-29 13:24:01', 0),
	(47, 1, '2020-07-01', 1, 33, 5000.00, NULL, NULL, '2021-06-29 13:24:01', '2021-06-29 13:24:01', 0),
	(48, 1, '2020-06-01', 1, 71, 5000.00, NULL, NULL, '2021-06-29 13:24:01', '2021-06-29 13:24:01', 0),
	(49, 1, '2020-07-01', 1, 71, 5000.00, NULL, NULL, '2021-06-29 13:24:01', '2021-06-29 13:24:01', 0),
	(50, 1, '2020-07-01', 1, 39, 2500.00, NULL, NULL, '2021-06-29 13:24:01', '2021-06-29 13:24:01', 0),
	(51, 1, '2020-06-01', 13, 61, 2500.00, NULL, NULL, '2021-06-29 13:24:01', '2021-06-29 13:24:01', 0),
	(52, 1, '2020-07-01', 13, 61, 5000.00, NULL, NULL, '2021-06-29 13:24:01', '2021-06-29 13:24:01', 0),
	(53, 1, '2020-07-01', 13, 22, 2500.00, NULL, NULL, '2021-06-29 13:24:01', '2021-06-29 13:24:01', 0),
	(54, 1, '2020-06-01', 13, 4, 30000.00, NULL, NULL, '2021-06-29 13:24:01', '2021-06-29 13:24:01', 0),
	(55, 1, '2020-07-01', 13, 4, 30000.00, NULL, NULL, '2021-06-29 13:24:01', '2021-06-29 13:24:01', 0),
	(56, 1, '2020-06-01', 13, 9, 5000.00, NULL, NULL, '2021-06-29 13:24:01', '2021-06-29 13:24:01', 0),
	(57, 1, '2020-07-01', 13, 9, 5000.00, NULL, NULL, '2021-06-29 13:24:01', '2021-06-29 13:24:01', 0),
	(58, 1, '2020-06-01', 13, 7, 35000.00, NULL, NULL, '2021-06-29 13:24:01', '2021-06-29 13:24:01', 0),
	(59, 1, '2020-07-01', 13, 7, 20000.00, NULL, NULL, '2021-06-29 13:24:01', '2021-06-29 13:24:01', 0),
	(60, 1, '2020-06-01', 13, 60, 5000.00, NULL, NULL, '2021-06-29 13:24:01', '2021-06-29 13:24:01', 0),
	(61, 1, '2020-07-01', 13, 60, 5000.00, NULL, NULL, '2021-06-29 13:24:01', '2021-06-29 13:24:01', 0),
	(62, 1, '2020-06-01', 7, 47, 2500.00, NULL, NULL, '2021-06-29 13:24:01', '2021-06-29 13:24:01', 0),
	(63, 1, '2020-07-01', 7, 47, 7500.00, NULL, NULL, '2021-06-29 13:24:01', '2021-06-29 13:24:01', 0),
	(64, 1, '2020-06-01', 7, 2, 10000.00, NULL, NULL, '2021-06-29 13:24:01', '2021-06-29 13:24:01', 0),
	(65, 1, '2020-07-01', 7, 2, 10000.00, NULL, NULL, '2021-06-29 13:24:01', '2021-06-29 13:24:01', 0),
	(66, 1, '2020-06-01', 7, 4, 65000.00, NULL, NULL, '2021-06-29 13:24:01', '2021-06-29 13:24:01', 0),
	(67, 1, '2020-07-01', 7, 4, 75000.00, NULL, NULL, '2021-06-29 13:24:01', '2021-06-29 13:24:01', 0),
	(68, 1, '2020-06-01', 7, 7, 65000.00, NULL, NULL, '2021-06-29 13:24:01', '2021-06-29 13:24:01', 0),
	(69, 1, '2020-07-01', 7, 7, 75000.00, NULL, NULL, '2021-06-29 13:24:01', '2021-06-29 13:24:01', 0),
	(70, 1, '2020-07-01', 6, 54, 2500.00, NULL, NULL, '2021-06-29 13:24:01', '2021-06-29 13:24:01', 0),
	(71, 1, '2020-07-01', 6, 12, 2500.00, NULL, NULL, '2021-06-29 13:24:01', '2021-06-29 13:24:01', 0),
	(72, 1, '2020-07-01', 6, 3, 15000.00, NULL, NULL, '2021-06-29 13:24:01', '2021-06-29 13:24:01', 0),
	(73, 1, '2020-06-01', 6, 22, 7500.00, NULL, NULL, '2021-06-29 13:24:01', '2021-06-29 13:24:01', 0),
	(74, 1, '2020-07-01', 6, 22, 7500.00, NULL, NULL, '2021-06-29 13:24:01', '2021-06-29 13:24:01', 0),
	(75, 1, '2020-07-01', 6, 6, 10000.00, NULL, NULL, '2021-06-29 13:24:01', '2021-06-29 13:24:01', 0),
	(76, 1, '2020-07-01', 6, 7, 7500.00, NULL, NULL, '2021-06-29 13:24:01', '2021-06-29 13:24:01', 0),
	(77, 1, '2020-06-01', 6, 15, 2500.00, NULL, NULL, '2021-06-29 13:24:01', '2021-06-29 13:24:01', 0),
	(78, 1, '2020-07-01', 6, 15, 2500.00, NULL, NULL, '2021-06-29 13:24:01', '2021-06-29 13:24:01', 0),
	(79, 1, '2020-06-01', 4, 3, 10000.00, NULL, NULL, '2021-06-29 13:24:01', '2021-06-29 13:24:01', 0),
	(80, 1, '2020-07-01', 4, 3, 10000.00, NULL, NULL, '2021-06-29 13:24:01', '2021-06-29 13:24:01', 0),
	(81, 1, '2020-07-01', 4, 61, 2500.00, NULL, NULL, '2021-06-29 13:24:01', '2021-06-29 13:24:01', 0),
	(82, 1, '2020-06-01', 4, 64, 2500.00, NULL, NULL, '2021-06-29 13:24:01', '2021-06-29 13:24:01', 0),
	(83, 1, '2020-06-01', 4, 6, 5000.00, NULL, NULL, '2021-06-29 13:24:01', '2021-06-29 13:24:01', 0),
	(84, 1, '2020-07-01', 4, 6, 2500.00, NULL, NULL, '2021-06-29 13:24:01', '2021-06-29 13:24:01', 0),
	(85, 1, '2020-07-01', 4, 4, 2500.00, NULL, NULL, '2021-06-29 13:24:01', '2021-06-29 13:24:01', 0),
	(86, 1, '2020-06-01', 4, 41, 2500.00, NULL, NULL, '2021-06-29 13:24:01', '2021-06-29 13:24:01', 0),
	(87, 1, '2020-07-01', 4, 7, 2500.00, NULL, NULL, '2021-06-29 13:24:01', '2021-06-29 13:24:01', 0),
	(88, 1, '2020-06-01', 27, 91, 2000.00, NULL, NULL, '2021-06-29 13:24:01', '2021-06-29 13:24:01', 0),
	(89, 1, '2020-06-01', 27, 56, 2500.00, NULL, NULL, '2021-06-29 13:24:01', '2021-06-29 13:24:01', 0),
	(90, 1, '2020-07-01', 27, 56, 2500.00, NULL, NULL, '2021-06-29 13:24:01', '2021-06-29 13:24:01', 0),
	(91, 1, '2020-06-01', 27, 3, 5000.00, NULL, NULL, '2021-06-29 13:24:01', '2021-06-29 13:24:01', 0),
	(92, 1, '2020-07-01', 27, 3, 7500.00, NULL, NULL, '2021-06-29 13:24:01', '2021-06-29 13:24:01', 0),
	(93, 1, '2020-06-01', 27, 9, 2500.00, NULL, NULL, '2021-06-29 13:24:01', '2021-06-29 13:24:01', 0),
	(94, 1, '2020-07-01', 27, 9, 2500.00, NULL, NULL, '2021-06-29 13:24:01', '2021-06-29 13:24:01', 0),
	(95, 1, '2020-06-01', 27, 5, 5000.00, NULL, NULL, '2021-06-29 13:24:01', '2021-06-29 13:24:01', 0),
	(96, 1, '2020-07-01', 27, 5, 2500.00, NULL, NULL, '2021-06-29 13:24:01', '2021-06-29 13:24:01', 0),
	(97, 1, '2020-06-01', 27, 92, 3000.00, NULL, NULL, '2021-06-29 13:24:01', '2021-06-29 13:24:01', 0),
	(98, 1, '2020-06-01', 27, 93, 2500.00, NULL, NULL, '2021-06-29 13:24:01', '2021-06-29 13:24:01', 0),
	(99, 1, '2020-06-01', 27, 13, 3000.00, NULL, NULL, '2021-06-29 13:24:01', '2021-06-29 13:24:01', 0),
	(100, 1, '2020-06-01', 27, 42, 2500.00, NULL, NULL, '2021-06-29 13:24:01', '2021-06-29 13:24:01', 0),
	(101, 1, '2020-07-01', 12, 12, 10000.00, NULL, NULL, '2021-06-29 13:24:01', '2021-06-29 13:24:01', 0),
	(102, 1, '2020-07-01', 12, 31, 10000.00, NULL, NULL, '2021-06-29 13:24:01', '2021-06-29 13:24:01', 0),
	(103, 1, '2020-06-01', 12, 3, 10000.00, NULL, NULL, '2021-06-29 13:24:01', '2021-06-29 13:24:01', 0),
	(104, 1, '2020-07-01', 12, 3, 30000.00, NULL, NULL, '2021-06-29 13:24:01', '2021-06-29 13:24:01', 0),
	(105, 1, '2020-07-01', 15, 3, 50000.00, NULL, NULL, '2021-06-29 13:24:01', '2021-06-29 13:24:01', 0),
	(106, 2, '2020-07-01', 27, 91, 2000.00, NULL, NULL, '2021-06-29 14:03:15', '2021-06-29 14:03:15', 0),
	(107, 2, '2020-08-01', 27, 56, 5000.00, NULL, NULL, '2021-06-29 14:03:15', '2021-06-29 14:03:15', 0),
	(108, 2, '2020-07-01', 27, 3, 7500.00, NULL, NULL, '2021-06-29 14:03:15', '2021-06-29 14:03:15', 0),
	(109, 2, '2020-08-01', 27, 3, 7500.00, NULL, NULL, '2021-06-29 14:03:15', '2021-06-29 14:03:15', 0),
	(110, 2, '2020-07-01', 27, 9, 3000.00, NULL, NULL, '2021-06-29 14:03:15', '2021-06-29 14:03:15', 0),
	(111, 2, '2020-08-01', 27, 9, 5000.00, NULL, NULL, '2021-06-29 14:03:15', '2021-06-29 14:03:15', 0),
	(112, 2, '2020-07-01', 27, 5, 5000.00, NULL, NULL, '2021-06-29 14:03:15', '2021-06-29 14:03:15', 0),
	(113, 2, '2020-07-01', 27, 92, 3000.00, '2021-07-23 08:58:04', 9, '2021-06-29 14:03:15', '2021-07-23 08:58:04', 0),
	(114, 2, '2020-07-01', 27, 93, 2500.00, NULL, NULL, '2021-06-29 14:03:15', '2021-06-29 14:03:15', 0),
	(115, 2, '2020-08-01', 27, 93, 2500.00, NULL, NULL, '2021-06-29 14:03:15', '2021-06-29 14:03:15', 0),
	(116, 2, '2020-08-01', 28, 24, 5000.00, '2021-07-23 08:57:58', 9, '2021-06-29 14:05:11', '2021-07-23 08:57:58', 0),
	(117, 2, '2020-07-01', 28, 8, 50000.00, NULL, NULL, '2021-06-29 14:05:11', '2021-06-29 14:05:11', 0),
	(119, 2, '2020-07-01', 1, 30, 2500.00, '2021-07-22 12:19:11', 9, '2021-06-29 15:19:57', '2021-07-22 12:19:11', 0),
	(121, 2, '2020-07-01', 1, 79, 7500.00, NULL, NULL, '2021-06-29 15:19:57', '2021-06-29 15:19:57', 0),
	(122, 2, '2020-08-01', 1, 30, 2500.00, '2021-07-23 08:57:05', 9, '2021-06-29 15:22:15', '2021-07-23 08:57:05', 0),
	(123, 2, '2020-08-01', 1, 79, 7500.00, NULL, NULL, '2021-06-29 15:22:15', '2021-06-29 15:22:15', 0),
	(124, 2, '2020-07-01', 15, 3, 150000.00, NULL, NULL, '2021-06-29 15:50:11', '2021-06-29 15:50:11', 0),
	(125, 2, '2020-08-01', 15, 3, 150000.00, NULL, NULL, '2021-06-29 15:50:11', '2021-06-29 15:50:11', 0),
	(126, 2, '2020-07-01', 12, 12, 10000.00, NULL, NULL, '2021-06-29 15:53:50', '2021-06-29 15:53:50', 0),
	(127, 2, '2020-08-01', 12, 12, 15000.00, NULL, NULL, '2021-06-29 15:53:50', '2021-06-29 15:53:50', 0),
	(128, 2, '2020-07-01', 12, 31, 7500.00, NULL, NULL, '2021-06-29 15:53:50', '2021-06-29 15:53:50', 0),
	(129, 2, '2020-08-01', 12, 31, 7500.00, NULL, NULL, '2021-06-29 15:53:50', '2021-06-29 15:53:50', 0),
	(130, 2, '2020-07-01', 12, 3, 50000.00, NULL, NULL, '2021-06-29 15:53:50', '2021-06-29 15:53:50', 0),
	(131, 2, '2020-08-01', 12, 3, 50000.00, NULL, NULL, '2021-06-29 15:53:50', '2021-06-29 15:53:50', 0),
	(132, 2, '2020-08-01', 13, 16, 5000.00, NULL, NULL, '2021-06-29 15:56:42', '2021-06-29 15:56:42', 0),
	(133, 2, '2020-07-01', 13, 3, 20000.00, NULL, NULL, '2021-06-29 15:56:42', '2021-06-29 15:56:42', 0),
	(134, 2, '2020-08-01', 13, 3, 50000.00, NULL, NULL, '2021-06-29 15:56:42', '2021-06-29 15:56:42', 0),
	(135, 2, '2020-08-01', 13, 61, 5000.00, NULL, NULL, '2021-06-29 15:56:42', '2021-06-29 15:56:42', 0),
	(136, 2, '2020-07-01', 13, 4, 40000.00, NULL, NULL, '2021-06-29 15:56:42', '2021-06-29 15:56:42', 0),
	(137, 2, '2020-08-01', 13, 4, 20000.00, NULL, NULL, '2021-06-29 15:56:42', '2021-06-29 15:56:42', 0),
	(138, 2, '2020-07-01', 13, 7, 35000.00, NULL, NULL, '2021-06-29 15:56:42', '2021-06-29 15:56:42', 0),
	(139, 2, '2020-08-01', 13, 7, 20000.00, NULL, NULL, '2021-06-29 15:56:42', '2021-06-29 15:56:42', 0),
	(140, 2, '2020-08-01', 13, 60, 5000.00, NULL, NULL, '2021-06-29 15:56:42', '2021-06-29 15:56:42', 0),
	(141, 2, '2020-07-01', 1, 16, 12500.00, NULL, NULL, '2021-06-29 15:57:22', '2021-06-30 12:09:54', 0),
	(142, 2, '2020-08-01', 1, 16, 7500.00, NULL, NULL, '2021-06-29 15:57:22', '2021-06-29 15:57:22', 0),
	(143, 2, '2020-07-01', 1, 45, 5000.00, NULL, NULL, '2021-06-29 15:57:22', '2021-06-29 15:57:22', 0),
	(144, 2, '2020-08-01', 1, 45, 2500.00, NULL, NULL, '2021-06-29 15:57:22', '2021-06-29 15:57:22', 0),
	(145, 2, '2020-07-01', 1, 14, 5000.00, NULL, NULL, '2021-06-29 15:57:22', '2021-06-29 15:57:22', 0),
	(146, 2, '2020-07-01', 1, 54, 5000.00, NULL, NULL, '2021-06-29 15:57:22', '2021-06-29 15:57:22', 0),
	(147, 2, '2020-08-01', 1, 54, 5000.00, NULL, NULL, '2021-06-29 15:57:22', '2021-06-29 15:57:22', 0),
	(148, 2, '2020-08-01', 1, 12, 2500.00, '2021-07-23 08:57:08', 9, '2021-06-29 15:57:22', '2021-07-23 08:57:08', 0),
	(149, 2, '2020-07-01', 1, 21, 2500.00, NULL, NULL, '2021-06-29 15:57:22', '2021-06-29 15:57:22', 0),
	(150, 2, '2020-08-01', 1, 31, 7500.00, '2021-07-23 08:57:10', 9, '2021-06-29 15:57:22', '2021-07-23 08:57:10', 0),
	(151, 2, '2020-07-01', 1, 69, 2500.00, '2021-07-22 12:19:14', 9, '2021-06-29 15:57:22', '2021-07-22 12:19:14', 0),
	(152, 2, '2020-07-01', 1, 36, 7500.00, NULL, NULL, '2021-06-29 15:57:22', '2021-06-29 15:57:22', 0),
	(153, 2, '2020-08-01', 1, 36, 7500.00, NULL, NULL, '2021-06-29 15:57:22', '2021-06-29 15:57:22', 0),
	(154, 2, '2020-07-01', 1, 73, 2500.00, '2021-07-23 08:55:43', 9, '2021-06-29 15:57:22', '2021-07-23 08:55:43', 0),
	(155, 2, '2020-08-01', 1, 73, 2500.00, NULL, NULL, '2021-06-29 15:57:22', '2021-06-29 15:57:22', 0),
	(156, 2, '2020-07-01', 1, 83, 2500.00, NULL, NULL, '2021-06-29 15:57:22', '2021-06-29 15:57:22', 0),
	(157, 2, '2020-08-01', 1, 83, 5000.00, NULL, NULL, '2021-06-29 15:57:22', '2021-06-29 15:57:22', 0),
	(158, 2, '2020-07-01', 1, 19, 2500.00, NULL, NULL, '2021-06-29 15:57:22', '2021-06-29 15:57:22', 0),
	(159, 2, '2020-07-01', 1, 89, 2500.00, NULL, NULL, '2021-06-29 15:57:22', '2021-06-29 15:57:22', 0),
	(160, 2, '2020-07-01', 1, 84, 2500.00, NULL, NULL, '2021-06-29 15:57:22', '2021-06-29 15:57:22', 0),
	(161, 2, '2020-07-01', 1, 3, 5000.00, NULL, NULL, '2021-06-29 15:57:22', '2021-06-29 15:57:22', 0),
	(162, 2, '2020-08-01', 1, 3, 5000.00, NULL, NULL, '2021-06-29 15:57:22', '2021-06-29 15:57:22', 0),
	(163, 2, '2020-07-01', 7, 12, 5000.00, '2021-07-23 08:56:01', 9, '2021-06-29 16:01:42', '2021-07-23 08:56:01', 0),
	(164, 2, '2020-07-01', 7, 47, 10000.00, NULL, NULL, '2021-06-29 16:01:42', '2021-06-29 16:01:42', 0),
	(165, 2, '2020-08-01', 7, 47, 5000.00, NULL, NULL, '2021-06-29 16:01:42', '2021-06-29 16:01:42', 0),
	(166, 2, '2020-08-01', 7, 66, 2500.00, NULL, NULL, '2021-06-29 16:01:42', '2021-06-29 16:01:42', 0),
	(167, 2, '2020-07-01', 7, 3, 35000.00, NULL, NULL, '2021-06-29 16:01:42', '2021-06-29 16:01:42', 0),
	(168, 2, '2020-08-01', 7, 3, 35000.00, NULL, NULL, '2021-06-29 16:01:42', '2021-06-29 16:01:42', 0),
	(169, 2, '2020-07-01', 7, 61, 5000.00, NULL, NULL, '2021-06-29 16:01:42', '2021-06-29 16:01:42', 0),
	(170, 2, '2020-08-01', 7, 61, 2500.00, NULL, NULL, '2021-06-29 16:01:42', '2021-06-29 16:01:42', 0),
	(171, 2, '2020-07-01', 7, 2, 5000.00, '2021-07-23 08:56:03', 9, '2021-06-29 16:01:42', '2021-07-23 08:56:03', 0),
	(172, 2, '2020-08-01', 7, 2, 5000.00, '2021-07-23 08:57:47', 9, '2021-06-29 16:01:42', '2021-07-23 08:57:47', 0),
	(173, 2, '2020-07-01', 7, 4, 100000.00, '2021-07-23 10:50:32', 9, '2021-06-29 16:01:42', '2021-08-09 11:13:16', 0),
	(174, 2, '2020-08-01', 7, 4, 70000.00, NULL, NULL, '2021-06-29 16:01:42', '2021-08-09 11:13:16', 0),
	(175, 2, '2020-07-01', 7, 7, 60000.00, '2021-07-23 10:50:34', 9, '2021-06-29 16:01:42', '2021-07-23 10:50:34', 0),
	(176, 2, '2020-08-01', 7, 7, 60000.00, NULL, NULL, '2021-06-29 16:01:42', '2021-06-29 16:01:42', 0),
	(177, 2, '2020-07-01', 7, 15, 5000.00, NULL, NULL, '2021-06-29 16:01:42', '2021-06-29 16:01:42', 0),
	(178, 2, '2020-08-01', 7, 15, 2500.00, NULL, NULL, '2021-06-29 16:01:42', '2021-06-29 16:01:42', 0),
	(179, 2, '2020-07-01', 1, 46, 7500.00, NULL, NULL, '2021-06-29 16:03:09', '2021-06-29 16:03:09', 0),
	(180, 2, '2020-07-01', 1, 37, 2500.00, '2021-07-23 08:53:23', 9, '2021-06-29 16:03:09', '2021-07-23 08:53:23', 0),
	(181, 2, '2020-07-01', 1, 9, 7500.00, '2021-07-05 11:29:15', 9, '2021-06-29 16:03:09', '2021-07-05 11:29:15', 0),
	(182, 2, '2020-07-01', 1, 77, 5000.00, NULL, NULL, '2021-06-29 16:03:09', '2021-06-29 16:03:09', 0),
	(183, 2, '2020-07-01', 1, 7, 2500.00, '2021-07-22 12:20:24', 9, '2021-06-29 16:03:09', '2021-07-22 12:20:24', 0),
	(184, 2, '2020-07-01', 1, 32, 5000.00, NULL, NULL, '2021-06-29 16:03:09', '2021-06-29 16:03:09', 0),
	(185, 2, '2020-07-01', 1, 17, 2500.00, '2021-07-23 08:55:49', 9, '2021-06-29 16:03:09', '2021-07-23 08:55:49', 0),
	(186, 2, '2020-07-01', 1, 20, 7500.00, NULL, NULL, '2021-06-29 16:03:09', '2021-06-29 16:03:09', 0),
	(187, 2, '2020-07-01', 1, 42, 5000.00, '2021-07-22 12:20:47', 9, '2021-06-29 16:03:09', '2021-07-22 12:20:47', 0),
	(188, 2, '2020-07-01', 1, 78, 2500.00, NULL, NULL, '2021-06-29 16:03:09', '2021-06-29 16:03:09', 0),
	(189, 2, '2020-07-01', 1, 15, 2500.00, '2021-07-23 08:55:52', 9, '2021-06-29 16:03:09', '2021-07-23 08:55:52', 0),
	(190, 2, '2020-07-01', 1, 33, 2500.00, '2021-07-23 08:55:53', 9, '2021-06-29 16:03:09', '2021-07-23 08:55:53', 0),
	(191, 2, '2020-07-01', 1, 71, 2500.00, NULL, NULL, '2021-06-29 16:03:09', '2021-06-29 16:03:09', 0),
	(192, 2, '2020-07-01', 1, 39, 7500.00, NULL, NULL, '2021-06-29 16:03:09', '2021-06-29 16:03:09', 0),
	(193, 2, '2020-07-01', 6, 12, 5000.00, '2021-07-23 10:02:41', 9, '2021-06-29 16:05:15', '2021-07-23 10:02:41', 0),
	(194, 2, '2020-08-01', 6, 47, 2500.00, '2021-07-23 08:57:30', 9, '2021-06-29 16:05:15', '2021-07-23 08:57:30', 0),
	(195, 2, '2020-07-01', 6, 3, 10000.00, '2021-07-23 08:55:58', 9, '2021-06-29 16:05:15', '2021-07-23 08:55:58', 0),
	(196, 2, '2020-08-01', 6, 3, 20000.00, '2021-07-23 08:57:42', 9, '2021-06-29 16:05:15', '2021-07-23 08:57:42', 0),
	(197, 2, '2020-07-01', 6, 22, 5000.00, '2021-07-23 10:02:44', 9, '2021-06-29 16:05:15', '2021-07-23 10:02:44', 0),
	(198, 2, '2020-08-01', 6, 22, 7500.00, '2021-07-23 08:57:44', 9, '2021-06-29 16:05:15', '2021-07-23 08:57:44', 0),
	(199, 2, '2020-07-01', 6, 6, 2500.00, '2021-07-23 10:51:33', 9, '2021-06-29 16:05:15', '2021-07-23 10:51:33', 0),
	(200, 2, '2020-08-01', 6, 6, 5000.00, NULL, NULL, '2021-06-29 16:05:15', '2021-06-29 16:05:15', 0),
	(201, 2, '2020-08-01', 6, 7, 7500.00, NULL, NULL, '2021-06-29 16:05:15', '2021-06-29 16:05:15', 0),
	(202, 2, '2020-07-01', 6, 15, 2500.00, NULL, NULL, '2021-06-29 16:05:15', '2021-06-29 16:05:15', 0),
	(203, 2, '2020-08-01', 6, 15, 2500.00, NULL, NULL, '2021-06-29 16:05:15', '2021-06-29 16:05:15', 0),
	(204, 2, '2020-07-01', 9, 24, 7500.00, '2021-07-23 08:56:06', 9, '2021-06-29 16:05:45', '2021-07-23 08:56:06', 0),
	(205, 2, '2020-08-01', 9, 24, 10000.00, NULL, NULL, '2021-06-29 16:05:45', '2021-06-29 16:05:45', 0),
	(206, 2, '2020-08-01', 1, 46, 10000.00, NULL, NULL, '2021-06-29 16:07:40', '2021-06-29 17:29:17', 0),
	(207, 2, '2020-08-01', 1, 9, 7500.00, NULL, NULL, '2021-06-29 16:07:40', '2021-06-29 17:29:17', 0),
	(208, 2, '2020-08-01', 1, 43, 5000.00, '2021-07-23 08:57:16', 9, '2021-06-29 16:07:40', '2021-07-23 08:57:16', 0),
	(209, 2, '2020-08-01', 1, 41, 2500.00, '2021-07-23 08:57:18', 9, '2021-06-29 16:07:40', '2021-07-23 08:57:18', 0),
	(210, 2, '2020-08-01', 1, 82, 2500.00, NULL, NULL, '2021-06-29 16:07:40', '2021-06-29 17:29:17', 0),
	(211, 2, '2020-08-01', 1, 77, 5000.00, NULL, NULL, '2021-06-29 16:07:40', '2021-06-29 16:07:40', 0),
	(212, 2, '2020-08-01', 1, 58, 2500.00, NULL, NULL, '2021-06-29 16:07:40', '2021-06-29 17:29:17', 0),
	(213, 2, '2020-08-01', 1, 70, 2500.00, NULL, NULL, '2021-06-29 16:07:40', '2021-06-29 17:29:17', 0),
	(214, 2, '2020-07-01', 4, 3, 20000.00, NULL, NULL, '2021-06-29 16:08:08', '2021-06-29 16:08:08', 0),
	(215, 2, '2020-08-01', 4, 3, 20000.00, NULL, NULL, '2021-06-29 16:08:08', '2021-06-29 16:08:08', 0),
	(216, 2, '2020-08-01', 4, 61, 2500.00, NULL, NULL, '2021-06-29 16:08:08', '2021-06-29 16:08:08', 0),
	(217, 2, '2020-07-01', 4, 64, 2500.00, '2021-07-23 08:55:56', 9, '2021-06-29 16:08:08', '2021-07-23 08:55:56', 0),
	(218, 2, '2020-07-01', 4, 4, 2500.00, '2021-07-23 10:00:45', 9, '2021-06-29 16:08:08', '2021-07-23 10:00:45', 0),
	(219, 2, '2020-08-01', 4, 4, 2500.00, NULL, NULL, '2021-06-29 16:08:08', '2021-06-29 16:08:08', 0),
	(220, 2, '2020-07-01', 4, 41, 2500.00, NULL, NULL, '2021-06-29 16:08:08', '2021-06-29 16:08:08', 0),
	(221, 2, '2020-08-01', 1, 7, 5000.00, NULL, NULL, '2021-06-29 16:08:26', '2021-06-29 16:08:26', 0),
	(222, 2, '2020-08-01', 1, 32, 5000.00, NULL, NULL, '2021-06-29 16:08:26', '2021-06-29 17:29:17', 0),
	(223, 2, '2020-08-01', 1, 67, 5000.00, NULL, NULL, '2021-06-29 16:08:26', '2021-06-29 17:29:17', 0),
	(224, 2, '2020-08-01', 1, 44, 2500.00, '2021-07-23 08:57:22', 9, '2021-06-29 16:08:26', '2021-07-23 08:57:22', 0),
	(225, 2, '2020-07-01', 11, 24, 5000.00, NULL, NULL, '2021-06-29 16:08:41', '2021-06-29 16:08:41', 0),
	(226, 2, '2020-08-01', 11, 24, 5000.00, NULL, NULL, '2021-06-29 16:08:41', '2021-06-29 16:08:41', 0),
	(227, 2, '2020-08-01', 1, 17, 2500.00, '2021-07-23 08:57:24', 9, '2021-06-29 16:10:08', '2021-07-23 08:57:24', 0),
	(228, 2, '2020-07-01', 1, 13, 5000.00, NULL, NULL, '2021-06-29 16:33:42', '2021-06-29 16:33:42', 0),
	(229, 2, '2020-08-01', 1, 13, 5000.00, NULL, NULL, '2021-06-29 16:33:42', '2021-06-29 16:33:42', 0),
	(230, 2, '2020-08-01', 1, 33, 2500.00, NULL, NULL, '2021-06-29 17:24:53', '2021-06-29 17:29:17', 0),
	(231, 2, '2020-08-01', 1, 71, 5000.00, NULL, NULL, '2021-06-29 17:24:53', '2021-06-29 17:29:17', 0),
	(232, 2, '2020-08-01', 1, 39, 5000.00, NULL, NULL, '2021-06-29 17:24:53', '2021-06-29 17:29:17', 0),
	(233, 2, '2020-07-01', 1, 67, 5000.00, NULL, NULL, '2021-06-29 17:29:17', '2021-06-29 17:29:17', 0),
	(234, 2, '2020-08-01', 1, 42, 2500.00, '2021-07-23 08:57:27', 9, '2021-06-29 17:31:28', '2021-07-23 08:57:27', 0),
	(235, 2, '2020-08-01', 1, 78, 2500.00, NULL, NULL, '2021-06-29 17:31:28', '2021-06-29 17:31:28', 0),
	(236, 3, '2020-07-01', 4, 3, 60000.00, NULL, NULL, '2021-07-22 13:23:27', '2021-07-22 17:08:32', 0),
	(237, 3, '2020-08-01', 4, 3, 40000.00, NULL, NULL, '2021-07-22 13:25:09', '2021-07-23 10:02:22', 0),
	(238, 3, '2020-08-01', 4, 4, 7500.00, NULL, NULL, '2021-07-23 10:01:15', '2021-07-23 10:01:15', 0),
	(239, 3, '2020-08-01', 4, 7, 5000.00, NULL, NULL, '2021-07-23 10:01:56', '2021-07-23 10:01:56', 0),
	(240, 3, '2020-08-01', 6, 6, 5000.00, '2021-07-23 12:01:19', 9, '2021-07-23 10:50:09', '2021-07-23 12:01:19', 0),
	(241, 3, '2020-08-01', 6, 41, 2500.00, NULL, NULL, '2021-07-23 10:50:09', '2021-07-23 10:50:09', 0);
/*!40000 ALTER TABLE `nd_request_barang_qty` ENABLE KEYS */;

-- Dumping structure for table favourtdj_system.nd_request_update_lock
DROP TABLE IF EXISTS `nd_request_update_lock`;
CREATE TABLE IF NOT EXISTS `nd_request_update_lock` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `po_pembelian_batch_id` int(11) DEFAULT NULL,
  `request_barang_detail_id` int(11) DEFAULT NULL,
  `qty` decimal(15,2) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=latin1;

-- Dumping data for table favourtdj_system.nd_request_update_lock: ~20 rows (approximately)
DELETE FROM `nd_request_update_lock`;
/*!40000 ALTER TABLE `nd_request_update_lock` DISABLE KEYS */;
INSERT INTO `nd_request_update_lock` (`id`, `po_pembelian_batch_id`, `request_barang_detail_id`, `qty`, `created_at`) VALUES
	(1, NULL, 253, 300.00, '2021-07-28 12:29:19'),
	(2, NULL, 179, 4600.00, '2021-07-28 12:29:19'),
	(3, NULL, 206, 400.00, '2021-07-28 12:29:19'),
	(4, NULL, 206, 9600.00, '2021-07-28 12:29:19'),
	(5, NULL, 282, 1300.00, '2021-07-28 12:29:19'),
	(6, NULL, 201, 6200.00, '2021-07-28 12:29:19'),
	(7, NULL, 190, 1600.00, '2021-07-28 12:29:19'),
	(8, NULL, 135, 900.00, '2021-07-28 12:29:19'),
	(13, 209, 253, 300.00, '2021-07-28 12:51:54'),
	(14, 227, 179, 4600.00, '2021-07-28 12:51:54'),
	(15, 227, 206, 400.00, '2021-07-28 12:51:54'),
	(16, 242, 206, 9600.00, '2021-07-28 12:51:54'),
	(17, 209, 253, 300.00, '2021-07-28 13:05:31'),
	(18, 227, 254, 4600.00, '2021-07-28 13:05:31'),
	(19, 227, 206, 400.00, '2021-07-28 13:05:31'),
	(20, 242, 289, 9600.00, '2021-07-28 13:05:31'),
	(21, 209, 253, 300.00, '2021-07-28 13:33:15'),
	(22, 227, 254, 4600.00, '2021-07-28 13:33:15'),
	(23, 227, 288, 400.00, '2021-07-28 13:33:15'),
	(24, 242, 289, 9600.00, '2021-07-28 13:33:15');
/*!40000 ALTER TABLE `nd_request_update_lock` ENABLE KEYS */;

-- Dumping structure for table favourtdj_system.nd_retur_beli
DROP TABLE IF EXISTS `nd_retur_beli`;
CREATE TABLE IF NOT EXISTS `nd_retur_beli` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ockh_info` varchar(100) DEFAULT NULL,
  `no_sj` smallint(6) DEFAULT NULL,
  `po_pembelian_batch_id` int(11) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `toko_id` int(11) DEFAULT NULL,
  `supplier_id` int(11) DEFAULT NULL,
  `keterangan1` varchar(90) DEFAULT NULL,
  `keterangan2` varchar(90) DEFAULT NULL,
  `status` int(1) NOT NULL DEFAULT '0',
  `status_aktif` int(1) NOT NULL DEFAULT '1',
  `closed_by` tinyint(4) DEFAULT NULL,
  `closed_date` datetime DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- Dumping data for table favourtdj_system.nd_retur_beli: ~2 rows (approximately)
DELETE FROM `nd_retur_beli`;
/*!40000 ALTER TABLE `nd_retur_beli` DISABLE KEYS */;
INSERT INTO `nd_retur_beli` (`id`, `ockh_info`, `no_sj`, `po_pembelian_batch_id`, `tanggal`, `toko_id`, `supplier_id`, `keterangan1`, `keterangan2`, `status`, `status_aktif`, `closed_by`, `closed_date`, `user_id`, `created_at`, `updated_at`) VALUES
	(1, '', 1, 3, '2021-08-13', 1, 0, 'wewre', '', 0, 1, 1, '2021-08-13 13:23:03', 1, '2021-08-13 13:22:29', '2021-08-13 13:23:03'),
	(2, '', NULL, 3, '2021-08-13', 1, 3, 'AAA', 'AAA', 1, 1, NULL, NULL, 1, '2021-08-13 13:40:46', '2021-08-13 13:40:46');
/*!40000 ALTER TABLE `nd_retur_beli` ENABLE KEYS */;

-- Dumping structure for table favourtdj_system.nd_retur_beli_detail
DROP TABLE IF EXISTS `nd_retur_beli_detail`;
CREATE TABLE IF NOT EXISTS `nd_retur_beli_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `retur_beli_id` smallint(6) DEFAULT NULL,
  `gudang_id` int(11) DEFAULT NULL,
  `barang_id` int(11) DEFAULT NULL,
  `warna_id` int(11) DEFAULT NULL,
  `harga` int(6) DEFAULT NULL,
  `keterangan` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- Dumping data for table favourtdj_system.nd_retur_beli_detail: ~2 rows (approximately)
DELETE FROM `nd_retur_beli_detail`;
/*!40000 ALTER TABLE `nd_retur_beli_detail` DISABLE KEYS */;
INSERT INTO `nd_retur_beli_detail` (`id`, `retur_beli_id`, `gudang_id`, `barang_id`, `warna_id`, `harga`, `keterangan`) VALUES
	(1, 1, 1, 7, 50, 945000, NULL),
	(2, 2, 1, 7, 50, 945000, NULL);
/*!40000 ALTER TABLE `nd_retur_beli_detail` ENABLE KEYS */;

-- Dumping structure for table favourtdj_system.nd_retur_beli_qty
DROP TABLE IF EXISTS `nd_retur_beli_qty`;
CREATE TABLE IF NOT EXISTS `nd_retur_beli_qty` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `retur_beli_detail_id` smallint(6) DEFAULT NULL,
  `qty` float(15,2) DEFAULT NULL,
  `jumlah_roll` int(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- Dumping data for table favourtdj_system.nd_retur_beli_qty: ~2 rows (approximately)
DELETE FROM `nd_retur_beli_qty`;
/*!40000 ALTER TABLE `nd_retur_beli_qty` DISABLE KEYS */;
INSERT INTO `nd_retur_beli_qty` (`id`, `retur_beli_detail_id`, `qty`, `jumlah_roll`) VALUES
	(1, 1, 10.00, 1),
	(2, 2, 10.00, 2);
/*!40000 ALTER TABLE `nd_retur_beli_qty` ENABLE KEYS */;

-- Dumping structure for table favourtdj_system.nd_retur_jual
DROP TABLE IF EXISTS `nd_retur_jual`;
CREATE TABLE IF NOT EXISTS `nd_retur_jual` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tanggal` date NOT NULL,
  `toko_id` int(2) NOT NULL DEFAULT '1',
  `penjualan_id` int(11) NOT NULL,
  `no_faktur` int(11) NOT NULL,
  `retur_type_id` int(2) DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `nama_keterangan` varchar(100) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` int(1) NOT NULL DEFAULT '1',
  `closed_by` int(11) DEFAULT NULL,
  `closed_date` datetime DEFAULT NULL,
  `status_aktif` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- Dumping data for table favourtdj_system.nd_retur_jual: ~2 rows (approximately)
DELETE FROM `nd_retur_jual`;
/*!40000 ALTER TABLE `nd_retur_jual` DISABLE KEYS */;
INSERT INTO `nd_retur_jual` (`id`, `tanggal`, `toko_id`, `penjualan_id`, `no_faktur`, `retur_type_id`, `customer_id`, `nama_keterangan`, `user_id`, `created_at`, `status`, `closed_by`, `closed_date`, `status_aktif`) VALUES
	(1, '2019-03-30', 1, 2388, 1, 3, 0, 'NANI', 17, '2019-04-02 05:48:43', 0, 17, '2019-04-02 12:51:13', 1),
	(2, '2019-07-17', 1, 3405, 2, 2, 84, '', 1, '2019-07-17 05:37:00', 0, 1, '2019-07-17 12:39:39', 1);
/*!40000 ALTER TABLE `nd_retur_jual` ENABLE KEYS */;

-- Dumping structure for table favourtdj_system.nd_retur_jual_detail
DROP TABLE IF EXISTS `nd_retur_jual_detail`;
CREATE TABLE IF NOT EXISTS `nd_retur_jual_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `retur_jual_id` int(11) DEFAULT NULL,
  `gudang_id` int(11) DEFAULT NULL,
  `barang_id` int(11) DEFAULT NULL,
  `warna_id` int(11) DEFAULT NULL,
  `harga` int(6) DEFAULT NULL,
  `keterangan` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- Dumping data for table favourtdj_system.nd_retur_jual_detail: ~3 rows (approximately)
DELETE FROM `nd_retur_jual_detail`;
/*!40000 ALTER TABLE `nd_retur_jual_detail` DISABLE KEYS */;
INSERT INTO `nd_retur_jual_detail` (`id`, `retur_jual_id`, `gudang_id`, `barang_id`, `warna_id`, `harga`, `keterangan`) VALUES
	(1, 1, 1, 4, 51, 9500, NULL),
	(2, 2, 1, 10, 41, 9750, NULL),
	(3, 2, 1, 10, 9, 9750, NULL);
/*!40000 ALTER TABLE `nd_retur_jual_detail` ENABLE KEYS */;

-- Dumping structure for table favourtdj_system.nd_retur_jual_qty
DROP TABLE IF EXISTS `nd_retur_jual_qty`;
CREATE TABLE IF NOT EXISTS `nd_retur_jual_qty` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `retur_jual_detail_id` int(11) DEFAULT NULL,
  `qty` float(15,2) DEFAULT NULL,
  `jumlah_roll` int(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

-- Dumping data for table favourtdj_system.nd_retur_jual_qty: ~7 rows (approximately)
DELETE FROM `nd_retur_jual_qty`;
/*!40000 ALTER TABLE `nd_retur_jual_qty` DISABLE KEYS */;
INSERT INTO `nd_retur_jual_qty` (`id`, `retur_jual_detail_id`, `qty`, `jumlah_roll`) VALUES
	(2, 1, 88.00, 1),
	(3, 1, 95.00, 1),
	(4, 1, 92.00, 1),
	(5, 1, 94.00, 1),
	(6, 2, 100.00, 8),
	(7, 2, 65.00, 1),
	(8, 3, 100.00, 10);
/*!40000 ALTER TABLE `nd_retur_jual_qty` ENABLE KEYS */;

-- Dumping structure for table favourtdj_system.nd_satuan
DROP TABLE IF EXISTS `nd_satuan`;
CREATE TABLE IF NOT EXISTS `nd_satuan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) DEFAULT NULL,
  `status_aktif` int(1) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=latin1;

-- Dumping data for table favourtdj_system.nd_satuan: ~2 rows (approximately)
DELETE FROM `nd_satuan`;
/*!40000 ALTER TABLE `nd_satuan` DISABLE KEYS */;
INSERT INTO `nd_satuan` (`id`, `nama`, `status_aktif`) VALUES
	(1, 'Yard', 1),
	(2, 'Kg', 1),
	(3, 'DUS', 1),
	(22, 'sasda', 1),
	(23, 'asasdadsasd', 1),
	(24, 'yard', 1),
	(25, '', 1),
	(26, 'dsasdas', 1),
	(27, 'asdasd', 1),
	(28, 'ssadad', 1);
/*!40000 ALTER TABLE `nd_satuan` ENABLE KEYS */;

-- Dumping structure for table favourtdj_system.nd_stok_awal_item_harga
DROP TABLE IF EXISTS `nd_stok_awal_item_harga`;
CREATE TABLE IF NOT EXISTS `nd_stok_awal_item_harga` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `barang_id` int(11) DEFAULT NULL,
  `warna_id` int(11) DEFAULT NULL,
  `harga_stok_awal` decimal(15,2) NOT NULL DEFAULT '0.00',
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=202 DEFAULT CHARSET=latin1;

-- Dumping data for table favourtdj_system.nd_stok_awal_item_harga: ~201 rows (approximately)
DELETE FROM `nd_stok_awal_item_harga`;
/*!40000 ALTER TABLE `nd_stok_awal_item_harga` DISABLE KEYS */;
INSERT INTO `nd_stok_awal_item_harga` (`id`, `barang_id`, `warna_id`, `harga_stok_awal`, `user_id`) VALUES
	(1, 1, 30, 9400.00, 1),
	(2, 1, 16, 9400.00, 1),
	(3, 1, 45, 9400.00, 1),
	(4, 1, 14, 9400.00, 1),
	(5, 1, 12, 9400.00, 1),
	(6, 1, 21, 9400.00, 1),
	(7, 1, 72, 9400.00, 1),
	(8, 1, 31, 9400.00, 1),
	(9, 1, 36, 9400.00, 1),
	(10, 1, 19, 9400.00, 1),
	(11, 1, 3, 9400.00, 1),
	(12, 1, 2, 9400.00, 1),
	(13, 1, 35, 9400.00, 1),
	(14, 1, 1, 9400.00, 1),
	(15, 1, 18, 9400.00, 1),
	(16, 1, 46, 9400.00, 1),
	(17, 1, 4, 9400.00, 1),
	(18, 1, 37, 9400.00, 1),
	(19, 1, 9, 9400.00, 1),
	(20, 1, 43, 9400.00, 1),
	(21, 1, 41, 9400.00, 1),
	(22, 1, 7, 9400.00, 1),
	(23, 1, 32, 9400.00, 1),
	(24, 1, 67, 9400.00, 1),
	(25, 1, 44, 9400.00, 1),
	(26, 1, 17, 9400.00, 1),
	(27, 1, 20, 9400.00, 1),
	(28, 1, 10, 9400.00, 1),
	(29, 1, 13, 9400.00, 1),
	(30, 1, 42, 9400.00, 1),
	(31, 1, 15, 9400.00, 1),
	(32, 1, 33, 9400.00, 1),
	(33, 1, 71, 9400.00, 1),
	(34, 1, 39, 9400.00, 1),
	(35, 3, 30, 58000.00, 1),
	(36, 3, 16, 58000.00, 1),
	(37, 3, 54, 63000.00, 1),
	(38, 3, 12, 54000.00, 1),
	(39, 3, 21, 54000.00, 1),
	(40, 3, 31, 58000.00, 1),
	(41, 3, 47, 70000.00, 1),
	(42, 3, 59, 58000.00, 1),
	(43, 3, 3, 61000.00, 1),
	(44, 3, 61, 56000.00, 1),
	(45, 3, 2, 58000.00, 1),
	(46, 3, 64, 58000.00, 1),
	(47, 3, 22, 58000.00, 1),
	(48, 3, 4, 61095.00, 1),
	(49, 3, 9, 58750.00, 1),
	(50, 3, 41, 58000.00, 1),
	(51, 3, 58, 54000.00, 1),
	(52, 3, 7, 58000.00, 1),
	(53, 3, 5, 58000.00, 1),
	(54, 3, 10, 58000.00, 1),
	(55, 3, 13, 60400.00, 1),
	(56, 3, 42, 54000.00, 1),
	(57, 3, 15, 68000.00, 1),
	(58, 3, 39, 56667.00, 1),
	(59, 15, 3, 4100.00, 1),
	(60, 12, 54, 4000.00, 1),
	(61, 12, 12, 4000.00, 1),
	(62, 12, 31, 4000.00, 1),
	(63, 12, 3, 4000.00, 1),
	(64, 12, 39, 4000.00, 1),
	(65, 13, 16, 5000.00, 1),
	(66, 13, 12, 5000.00, 1),
	(67, 13, 47, 5000.00, 1),
	(68, 13, 3, 5000.00, 1),
	(69, 13, 61, 5000.00, 1),
	(70, 13, 2, 5000.00, 1),
	(71, 13, 22, 5000.00, 1),
	(72, 13, 4, 5000.00, 1),
	(73, 13, 9, 5000.00, 1),
	(74, 13, 41, 5000.00, 1),
	(75, 13, 7, 5000.00, 1),
	(76, 13, 60, 5000.00, 1),
	(77, 13, 13, 5000.00, 1),
	(78, 13, 39, 5000.00, 1),
	(79, 16, 3, 60000.00, 1),
	(80, 16, 9, 60000.00, 1),
	(81, 16, 7, 60000.00, 1),
	(82, 18, 54, 10300.00, 1),
	(83, 18, 65, 10300.00, 1),
	(84, 18, 63, 10300.00, 1),
	(85, 18, 3, 10300.00, 1),
	(86, 18, 52, 10300.00, 1),
	(87, 18, 64, 10300.00, 1),
	(88, 18, 22, 10300.00, 1),
	(89, 18, 6, 10300.00, 1),
	(90, 18, 4, 10300.00, 1),
	(91, 18, 9, 10300.00, 1),
	(92, 18, 41, 10300.00, 1),
	(93, 18, 7, 10300.00, 1),
	(94, 7, 54, 6100.00, 1),
	(95, 7, 12, 6100.00, 1),
	(96, 7, 31, 6100.00, 1),
	(97, 7, 47, 6100.00, 1),
	(98, 7, 66, 6100.00, 1),
	(99, 7, 3, 6051.00, 1),
	(100, 7, 52, 5500.00, 1),
	(101, 7, 61, 5500.00, 1),
	(102, 7, 2, 6100.00, 1),
	(103, 7, 4, 5977.00, 1),
	(104, 7, 9, 5500.00, 1),
	(105, 7, 41, 6100.00, 1),
	(106, 7, 68, 6100.00, 1),
	(107, 7, 58, 6100.00, 1),
	(108, 7, 7, 6053.00, 1),
	(109, 7, 5, 6100.00, 1),
	(110, 7, 13, 6100.00, 1),
	(111, 7, 15, 6000.00, 1),
	(112, 7, 39, 6000.00, 1),
	(113, 19, 24, 6000.00, 1),
	(114, 19, 8, 6500.00, 1),
	(115, 8, 50, 6300.00, 1),
	(116, 8, 30, 6300.00, 1),
	(117, 8, 54, 6300.00, 1),
	(118, 8, 51, 6300.00, 1),
	(119, 8, 12, 6300.00, 1),
	(120, 8, 21, 6300.00, 1),
	(121, 8, 57, 6300.00, 1),
	(122, 8, 65, 6300.00, 1),
	(123, 8, 53, 6300.00, 1),
	(124, 8, 73, 6300.00, 1),
	(125, 8, 56, 6300.00, 1),
	(126, 8, 47, 6300.00, 1),
	(127, 8, 3, 6300.00, 1),
	(128, 8, 52, 6300.00, 1),
	(129, 8, 2, 6300.00, 1),
	(130, 8, 64, 6300.00, 1),
	(131, 8, 22, 6300.00, 1),
	(132, 8, 4, 6500.00, 1),
	(133, 8, 9, 6300.00, 1),
	(134, 8, 48, 6300.00, 1),
	(135, 8, 41, 6300.00, 1),
	(136, 8, 7, 6300.00, 1),
	(137, 8, 5, 6300.00, 1),
	(138, 8, 55, 6300.00, 1),
	(139, 8, 13, 6300.00, 1),
	(140, 14, 12, 6800.00, 1),
	(141, 14, 3, 6800.00, 1),
	(142, 14, 2, 6800.00, 1),
	(143, 14, 22, 6800.00, 1),
	(144, 14, 4, 6800.00, 1),
	(145, 14, 15, 6800.00, 1),
	(146, 20, 12, 6800.00, 1),
	(147, 20, 3, 6800.00, 1),
	(148, 21, 8, 6800.00, 1),
	(149, 17, 30, 10700.00, 1),
	(150, 17, 12, 10700.00, 1),
	(151, 17, 3, 10700.00, 1),
	(152, 17, 9, 10700.00, 1),
	(153, 17, 41, 10700.00, 1),
	(154, 17, 13, 10700.00, 1),
	(155, 10, 12, 8100.00, 1),
	(156, 10, 63, 8100.00, 1),
	(157, 10, 3, 8250.00, 1),
	(158, 10, 64, 8500.00, 1),
	(159, 10, 4, 8500.00, 1),
	(160, 10, 9, 8500.00, 1),
	(161, 10, 48, 8100.00, 1),
	(162, 10, 7, 8500.00, 1),
	(163, 10, 5, 8100.00, 1),
	(164, 10, 15, 8100.00, 1),
	(165, 2, 30, 55000.00, 1),
	(166, 2, 12, 55000.00, 1),
	(167, 2, 3, 60000.00, 1),
	(168, 2, 64, 55000.00, 1),
	(169, 2, 9, 55000.00, 1),
	(170, 2, 41, 55000.00, 1),
	(171, 2, 7, 55000.00, 1),
	(172, 2, 42, 55000.00, 1),
	(173, 6, 62, 6200.00, 1),
	(174, 6, 47, 6200.00, 1),
	(175, 6, 3, 6326.00, 1),
	(176, 6, 61, 6200.00, 1),
	(177, 6, 4, 6314.00, 1),
	(178, 6, 9, 6200.00, 1),
	(179, 6, 13, 6200.00, 1),
	(180, 9, 24, 7000.00, 1),
	(181, 9, 8, 7000.00, 1),
	(182, 4, 62, 8300.00, 1),
	(183, 4, 54, 8300.00, 1),
	(184, 4, 51, 8300.00, 1),
	(185, 4, 12, 8300.00, 1),
	(186, 4, 63, 8300.00, 1),
	(187, 4, 3, 8300.00, 1),
	(188, 4, 61, 8300.00, 1),
	(189, 4, 64, 8300.00, 1),
	(190, 4, 4, 8447.00, 1),
	(191, 4, 9, 8300.00, 1),
	(192, 4, 7, 8300.00, 1),
	(193, 11, 24, 9300.00, 1),
	(194, 11, 8, 9300.00, 1),
	(195, 5, 12, 7700.00, 1),
	(196, 5, 47, 7700.00, 1),
	(197, 5, 3, 7700.00, 1),
	(198, 5, 61, 7700.00, 1),
	(199, 5, 4, 7900.00, 1),
	(200, 5, 9, 7700.00, 1),
	(201, 5, 13, 7700.00, 1);
/*!40000 ALTER TABLE `nd_stok_awal_item_harga` ENABLE KEYS */;

-- Dumping structure for table favourtdj_system.nd_stok_opname
DROP TABLE IF EXISTS `nd_stok_opname`;
CREATE TABLE IF NOT EXISTS `nd_stok_opname` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tanggal` date DEFAULT NULL,
  `stok_opname_report_id` int(11) DEFAULT NULL,
  `barang_id_so` smallint(6) DEFAULT NULL,
  `warna_id_so` smallint(6) DEFAULT NULL,
  `gudang_id_so` smallint(6) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `stok_current` decimal(11,2) DEFAULT NULL,
  `roll_current` smallint(6) DEFAULT NULL,
  `stok_date` datetime DEFAULT NULL,
  `status_aktif` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=50 DEFAULT CHARSET=latin1;

-- Dumping data for table favourtdj_system.nd_stok_opname: ~46 rows (approximately)
DELETE FROM `nd_stok_opname`;
/*!40000 ALTER TABLE `nd_stok_opname` DISABLE KEYS */;
INSERT INTO `nd_stok_opname` (`id`, `tanggal`, `stok_opname_report_id`, `barang_id_so`, `warna_id_so`, `gudang_id_so`, `user_id`, `created_at`, `stok_current`, `roll_current`, `stok_date`, `status_aktif`) VALUES
	(1, '2021-01-01', 1, NULL, NULL, NULL, 1, '2021-01-01 08:15:44', NULL, NULL, NULL, 1),
	(2, '2021-02-13', 2, 15, 3, 1, 1, '2021-02-13 08:15:33', NULL, NULL, NULL, 1),
	(3, '2021-02-13', 2, 15, 3, 2, 1, '2021-02-13 08:15:19', NULL, NULL, NULL, 1),
	(4, '2021-02-13', 2, 6, 47, 2, 1, '2021-02-13 08:15:32', NULL, NULL, NULL, 1),
	(5, '2021-02-15', 3, 1, 36, 1, 1, '2021-02-15 08:15:01', NULL, NULL, NULL, 1),
	(6, '2021-03-01', 4, 3, 3, 1, 1, '2021-03-01 08:15:41', NULL, NULL, NULL, 1),
	(7, '2021-03-03', 5, 1, 77, 1, 18, '2021-03-03 08:11:19', NULL, NULL, NULL, 1),
	(8, '2021-03-12', 6, 13, 3, 2, 1, '2021-03-12 08:13:48', NULL, NULL, NULL, 1),
	(9, '2021-04-07', 12, 6, 7, 2, 1, '2021-04-07 07:36:12', NULL, NULL, NULL, 1),
	(10, '2021-04-07', 12, 6, 7, 1, 1, '2021-04-07 07:30:00', NULL, NULL, NULL, 1),
	(11, '2021-04-07', 12, 1, 54, 1, 1, '2021-04-07 07:35:00', NULL, NULL, NULL, 1),
	(12, '2021-04-07', 12, 1, 9, 1, 1, '2021-04-07 07:40:00', NULL, NULL, NULL, 1),
	(13, '2021-04-07', 12, 1, 77, 1, 1, '2021-04-07 07:50:00', NULL, NULL, NULL, 1),
	(14, '2021-04-07', 12, 1, 13, 1, 1, '2021-04-07 07:45:00', NULL, NULL, NULL, 1),
	(15, '2021-04-07', 12, 1, 39, 1, 1, '2021-04-07 07:45:00', NULL, NULL, NULL, 1),
	(16, '2021-04-07', 13, 27, 56, 1, 1, '2021-04-07 10:43:43', NULL, NULL, NULL, 1),
	(17, '2021-04-23', 14, 12, 3, 2, 1, '2021-04-23 12:24:02', NULL, NULL, NULL, 1),
	(18, '2021-04-23', 16, 12, 54, 2, 1, '2021-04-23 14:09:32', NULL, NULL, NULL, 1),
	(19, '2021-04-23', 16, 12, 12, 2, 1, '2021-04-23 13:30:00', NULL, NULL, NULL, 1),
	(20, '2021-04-23', 17, 7, 12, 2, 1, '2021-04-23 15:55:00', NULL, NULL, NULL, 1),
	(21, '2021-04-23', 17, 7, 41, 2, 1, '2021-04-23 15:55:00', NULL, NULL, NULL, 1),
	(22, '2021-04-23', 17, 7, 3, 2, 1, '2021-04-23 15:55:00', NULL, NULL, NULL, 1),
	(23, '2021-04-23', 17, 13, 12, 2, 1, '2021-04-23 15:55:00', NULL, NULL, NULL, 1),
	(24, '2021-04-23', 17, 13, 7, 2, 1, '2021-04-23 16:00:00', NULL, NULL, NULL, 1),
	(25, '2021-04-23', 17, 13, 60, 2, 1, '2021-04-23 16:00:00', NULL, NULL, NULL, 1),
	(26, '2021-04-23', 17, 14, 7, 2, 1, '2021-04-23 16:00:00', NULL, NULL, NULL, 1),
	(27, '2021-04-23', 17, 14, 3, 2, 1, '2021-04-23 16:00:00', NULL, NULL, NULL, 1),
	(28, '2021-04-23', 17, 10, 12, 2, 1, '2021-04-23 16:00:00', NULL, NULL, NULL, 1),
	(29, '2021-04-23', 17, 10, 41, 2, 1, '2021-04-23 16:00:00', NULL, NULL, NULL, 1),
	(30, '2021-04-23', 17, 1, 41, 2, 1, '2021-04-23 16:00:00', NULL, NULL, NULL, 1),
	(31, '2021-04-23', 17, 1, 2, 2, 1, '2021-04-23 16:00:00', NULL, NULL, NULL, 1),
	(32, '2021-04-23', 17, 1, 12, 2, 1, '2021-04-23 16:00:00', NULL, NULL, NULL, 1),
	(33, '2021-04-23', 17, 27, 3, 2, 1, '2021-04-23 16:00:00', NULL, NULL, NULL, 1),
	(34, '2021-04-23', 17, 27, 12, 2, 1, '2021-04-23 16:00:00', NULL, NULL, NULL, 1),
	(35, '2021-04-23', 17, 27, 7, 2, 1, '2021-04-23 16:00:00', NULL, NULL, NULL, 1),
	(36, '2021-04-23', 17, 27, 9, 2, 1, '2021-04-23 16:00:00', NULL, NULL, NULL, 1),
	(37, '2021-04-24', 18, 13, 68, 2, 1, '2021-04-24 10:30:00', NULL, NULL, NULL, 1),
	(38, '2021-04-26', 20, 3, 5, 5, 1, '2021-04-26 09:54:00', NULL, NULL, NULL, 1),
	(42, '2021-05-28', 22, 12, 3, 1, 1, '2021-05-28 18:00:00', -294.00, -3, '2021-05-28 18:00:00', 1),
	(43, '2021-06-03', 23, 15, 3, 5, 18, '2021-06-03 13:05:00', 276088.00, 2761, '2021-06-03 13:05:00', 1),
	(44, '2021-06-02', 23, 15, 3, 1, 18, '2021-06-02 17:00:00', 2700.00, 27, '2021-06-02 17:00:00', 1),
	(45, '2021-06-03', 24, 15, 3, 2, 18, '2021-06-03 13:19:00', 3200.00, 32, '2021-06-03 13:19:00', 1),
	(46, '2021-06-07', 25, 6, 6, 1, 18, '2021-06-07 11:32:00', 2772.00, 28, '2021-06-07 11:32:00', 1),
	(47, '2021-06-11', 26, 15, 3, 2, 18, '2021-06-11 09:33:00', 5500.00, 55, '2021-06-11 09:33:00', 1),
	(48, '2021-06-23', 27, 7, 47, 1, 18, '2021-06-23 15:30:00', 263.00, 3, '2021-06-23 15:30:00', 1),
	(49, '2021-06-26', 28, 26, 7, 2, 18, '2021-06-26 10:44:00', 0.00, 0, '2021-06-26 10:44:00', 1);
/*!40000 ALTER TABLE `nd_stok_opname` ENABLE KEYS */;

-- Dumping structure for table favourtdj_system.nd_stok_opname_detail
DROP TABLE IF EXISTS `nd_stok_opname_detail`;
CREATE TABLE IF NOT EXISTS `nd_stok_opname_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `stok_opname_id` int(11) DEFAULT NULL,
  `barang_id` int(11) DEFAULT NULL,
  `warna_id` int(11) DEFAULT NULL,
  `gudang_id` int(11) DEFAULT NULL,
  `qty` decimal(10,2) DEFAULT NULL,
  `jumlah_roll` smallint(6) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table favourtdj_system.nd_stok_opname_detail: ~0 rows (approximately)
DELETE FROM `nd_stok_opname_detail`;
/*!40000 ALTER TABLE `nd_stok_opname_detail` DISABLE KEYS */;
/*!40000 ALTER TABLE `nd_stok_opname_detail` ENABLE KEYS */;

-- Dumping structure for table favourtdj_system.nd_stok_opname_detail_temp
DROP TABLE IF EXISTS `nd_stok_opname_detail_temp`;
CREATE TABLE IF NOT EXISTS `nd_stok_opname_detail_temp` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `stok_opname_id` int(11) DEFAULT NULL,
  `barang_id` int(11) DEFAULT NULL,
  `warna_id` int(11) DEFAULT NULL,
  `gudang_id` int(11) DEFAULT NULL,
  `qty` decimal(10,2) DEFAULT NULL,
  `jumlah_roll` int(4) DEFAULT NULL,
  `status` tinyint(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table favourtdj_system.nd_stok_opname_detail_temp: ~0 rows (approximately)
DELETE FROM `nd_stok_opname_detail_temp`;
/*!40000 ALTER TABLE `nd_stok_opname_detail_temp` DISABLE KEYS */;
/*!40000 ALTER TABLE `nd_stok_opname_detail_temp` ENABLE KEYS */;

-- Dumping structure for table favourtdj_system.nd_stok_opname_report
DROP TABLE IF EXISTS `nd_stok_opname_report`;
CREATE TABLE IF NOT EXISTS `nd_stok_opname_report` (
  `id` int(11) NOT NULL,
  `no_surat` smallint(6) DEFAULT NULL,
  `closed_by` tinyint(4) DEFAULT NULL,
  `keterangan` varchar(200) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table favourtdj_system.nd_stok_opname_report: ~22 rows (approximately)
DELETE FROM `nd_stok_opname_report`;
/*!40000 ALTER TABLE `nd_stok_opname_report` DISABLE KEYS */;
INSERT INTO `nd_stok_opname_report` (`id`, `no_surat`, `closed_by`, `keterangan`, `created_at`, `updated_at`) VALUES
	(1, 1, 1, NULL, '2021-01-01 08:30:00', '2021-04-06 16:16:11'),
	(2, 2, 1, NULL, '2021-02-13 08:30:00', '2021-04-06 16:18:00'),
	(3, 3, 1, NULL, '2021-02-15 08:30:00', '2021-04-06 16:18:00'),
	(4, 4, 1, NULL, '2021-03-01 08:30:00', '2021-04-06 16:18:00'),
	(5, 5, 1, NULL, '2021-03-03 08:30:00', '2021-04-06 16:18:00'),
	(6, 6, 1, NULL, '2021-03-12 08:30:00', '2021-04-06 16:18:00'),
	(12, 7, 1, '-', '2021-04-07 10:11:28', '2021-04-07 10:11:28'),
	(13, 8, 1, '-', '2021-04-07 10:43:50', '2021-04-07 10:43:50'),
	(14, 9, 1, 'Ibu Merry, Didin', '2021-04-23 12:27:21', '2021-04-23 12:27:21'),
	(16, 10, 1, 'Nata', '2021-04-23 14:44:56', '2021-04-23 14:44:56'),
	(17, 11, 1, 'Nata', '2021-04-24 09:43:06', '2021-04-24 09:43:06'),
	(18, 12, 1, 'Nata', '2021-04-24 10:31:08', '2021-04-24 10:31:08'),
	(19, 13, 1, '', '2021-04-26 09:54:50', '2021-04-26 09:54:50'),
	(20, 14, 1, 'Nata', '2021-04-26 09:55:01', '2021-04-26 09:55:01'),
	(21, 15, 1, 'Ajid, Wanda, Diman', '2021-05-29 14:55:34', '2021-05-29 14:55:34'),
	(22, 16, 1, 'Ajid, Wanda, Diman', '2021-05-29 15:08:25', '2021-05-29 15:08:25'),
	(23, 17, 18, 'Diman', '2021-06-03 13:44:41', '2021-06-03 13:44:41'),
	(24, 18, 18, 'wanda', '2021-06-03 13:51:07', '2021-06-03 13:51:07'),
	(25, 19, 18, 'wanda wawan', '2021-06-07 11:32:40', '2021-06-07 11:32:40'),
	(26, 20, 18, 'diman', '2021-06-11 09:33:52', '2021-06-11 09:33:52'),
	(27, 21, 18, 'dayat', '2021-06-23 15:30:41', '2021-06-23 15:30:41'),
	(28, 22, 18, 'DIDIN', '2021-06-26 10:44:20', '2021-06-26 10:44:20');
/*!40000 ALTER TABLE `nd_stok_opname_report` ENABLE KEYS */;

-- Dumping structure for table favourtdj_system.nd_stok_opname_temp
DROP TABLE IF EXISTS `nd_stok_opname_temp`;
CREATE TABLE IF NOT EXISTS `nd_stok_opname_temp` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tanggal` date DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `status_aktif` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- Dumping data for table favourtdj_system.nd_stok_opname_temp: ~0 rows (approximately)
DELETE FROM `nd_stok_opname_temp`;
/*!40000 ALTER TABLE `nd_stok_opname_temp` DISABLE KEYS */;
INSERT INTO `nd_stok_opname_temp` (`id`, `tanggal`, `user_id`, `created`, `status_aktif`) VALUES
	(1, '2021-01-01', 1, '2020-12-14 15:30:44', 1);
/*!40000 ALTER TABLE `nd_stok_opname_temp` ENABLE KEYS */;

-- Dumping structure for table favourtdj_system.nd_stok_opname_transaksi
DROP TABLE IF EXISTS `nd_stok_opname_transaksi`;
CREATE TABLE IF NOT EXISTS `nd_stok_opname_transaksi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `stok_opname_id` int(11) DEFAULT NULL,
  `barang_id` int(11) DEFAULT NULL,
  `warna_id` int(11) DEFAULT NULL,
  `gudang_id` int(11) DEFAULT NULL,
  `qty` decimal(10,2) DEFAULT NULL,
  `jumlah_roll` int(4) DEFAULT NULL,
  `status` tinyint(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1725 DEFAULT CHARSET=latin1;

-- Dumping data for table favourtdj_system.nd_stok_opname_transaksi: ~992 rows (approximately)
DELETE FROM `nd_stok_opname_transaksi`;
/*!40000 ALTER TABLE `nd_stok_opname_transaksi` DISABLE KEYS */;
INSERT INTO `nd_stok_opname_transaksi` (`id`, `stok_opname_id`, `barang_id`, `warna_id`, `gudang_id`, `qty`, `jumlah_roll`, `status`) VALUES
	(1, 1, 1, 2, 1, 54.00, 1, 1),
	(2, 1, 1, 2, 1, 68.00, 1, 1),
	(3, 1, 1, 2, 1, 81.00, 1, 1),
	(4, 1, 1, 2, 1, 97.00, 1, 1),
	(5, 1, 1, 2, 1, 100.00, 41, 1),
	(6, 1, 1, 2, 1, 104.00, 1, 1),
	(7, 1, 1, 16, 1, 100.00, 25, 1),
	(8, 1, 1, 16, 1, 118.00, 1, 1),
	(13, 1, 1, 43, 1, 71.00, 1, 1),
	(14, 1, 1, 43, 1, 84.00, 1, 1),
	(15, 1, 1, 43, 1, 91.00, 1, 1),
	(16, 1, 1, 43, 1, 98.00, 1, 1),
	(17, 1, 1, 43, 1, 100.00, 71, 1),
	(18, 1, 1, 43, 1, 101.00, 1, 1),
	(25, 1, 1, 73, 1, 66.00, 1, 1),
	(26, 1, 1, 73, 1, 80.00, 1, 1),
	(27, 1, 1, 73, 1, 91.00, 1, 1),
	(28, 1, 1, 73, 1, 95.00, 1, 1),
	(29, 1, 1, 73, 1, 100.00, 56, 1),
	(34, 1, 1, 89, 1, 64.00, 1, 1),
	(35, 1, 1, 89, 1, 69.00, 1, 1),
	(36, 1, 1, 89, 1, 78.00, 1, 1),
	(37, 1, 1, 89, 1, 88.00, 1, 1),
	(38, 1, 1, 89, 1, 98.00, 1, 1),
	(39, 1, 1, 89, 1, 100.00, 34, 1),
	(40, 1, 3, 3, 1, 28.33, 1, 1),
	(41, 1, 3, 3, 1, 29.16, 1, 1),
	(42, 1, 3, 3, 1, 29.17, 1, 1),
	(43, 1, 3, 3, 1, 29.22, 2, 1),
	(44, 1, 3, 3, 1, 29.34, 1, 1),
	(45, 1, 3, 3, 1, 29.51, 1, 1),
	(46, 1, 3, 3, 1, 29.65, 1, 1),
	(50, 1, 7, 66, 1, 100.00, 26, 1),
	(77, 1, 10, 7, 1, 68.00, 1, 1),
	(78, 1, 10, 7, 1, 74.00, 1, 1),
	(79, 1, 10, 7, 1, 87.00, 1, 1),
	(80, 1, 10, 7, 1, 100.00, 23, 1),
	(95, 1, 12, 12, 1, 57.00, 1, 1),
	(96, 1, 12, 12, 1, 62.00, 1, 1),
	(97, 1, 12, 12, 1, 67.00, 1, 1),
	(98, 1, 12, 12, 1, 78.00, 1, 1),
	(99, 1, 12, 12, 1, 79.00, 1, 1),
	(100, 1, 12, 12, 1, 87.00, 1, 1),
	(101, 1, 12, 12, 1, 88.00, 2, 1),
	(102, 1, 12, 12, 1, 89.00, 2, 1),
	(103, 1, 12, 12, 1, 92.00, 2, 1),
	(104, 1, 12, 12, 1, 97.00, 1, 1),
	(105, 1, 12, 12, 1, 99.00, 1, 1),
	(106, 1, 12, 12, 1, 100.00, 90, 1),
	(107, 1, 12, 12, 1, 133.00, 1, 1),
	(108, 1, 12, 31, 1, 65.00, 1, 1),
	(109, 1, 12, 31, 1, 70.00, 1, 1),
	(110, 1, 12, 31, 1, 71.00, 1, 1),
	(111, 1, 12, 31, 1, 82.00, 2, 1),
	(112, 1, 12, 31, 1, 95.00, 1, 1),
	(113, 1, 12, 31, 1, 96.00, 1, 1),
	(114, 1, 12, 31, 1, 100.00, 39, 1),
	(115, 1, 12, 31, 1, 120.00, 1, 1),
	(116, 1, 12, 39, 1, 90.00, 1, 1),
	(117, 1, 12, 39, 1, 94.00, 1, 1),
	(118, 1, 12, 39, 1, 100.00, 36, 1),
	(119, 1, 12, 54, 1, 91.00, 2, 1),
	(120, 1, 12, 54, 1, 100.00, 48, 1),
	(121, 1, 12, 54, 1, 140.00, 1, 1),
	(122, 1, 12, 54, 1, 145.00, 1, 1),
	(155, 1, 13, 4, 1, 59.00, 1, 1),
	(156, 1, 13, 4, 1, 64.00, 1, 1),
	(157, 1, 13, 4, 1, 73.00, 1, 1),
	(158, 1, 13, 4, 1, 74.00, 1, 1),
	(159, 1, 13, 4, 1, 78.00, 2, 1),
	(160, 1, 13, 4, 1, 80.00, 2, 1),
	(161, 1, 13, 4, 1, 88.00, 1, 1),
	(162, 1, 13, 4, 1, 92.00, 1, 1),
	(163, 1, 13, 4, 1, 93.00, 1, 1),
	(164, 1, 13, 4, 1, 94.00, 1, 1),
	(165, 1, 13, 4, 1, 96.00, 1, 1),
	(166, 1, 13, 4, 1, 98.00, 1, 1),
	(167, 1, 13, 4, 1, 100.00, 47, 1),
	(168, 1, 13, 4, 1, 102.00, 1, 1),
	(169, 1, 13, 4, 1, 105.00, 1, 1),
	(170, 1, 14, 3, 1, 51.00, 1, 1),
	(171, 1, 14, 3, 1, 58.00, 1, 1),
	(172, 1, 14, 3, 1, 62.00, 1, 1),
	(173, 1, 14, 3, 1, 77.00, 1, 1),
	(174, 1, 14, 3, 1, 81.00, 1, 1),
	(175, 1, 14, 3, 1, 83.00, 1, 1),
	(176, 1, 14, 3, 1, 86.00, 1, 1),
	(177, 1, 14, 3, 1, 87.00, 1, 1),
	(178, 1, 14, 3, 1, 88.00, 1, 1),
	(179, 1, 14, 3, 1, 90.00, 1, 1),
	(180, 1, 14, 3, 1, 92.00, 1, 1),
	(181, 1, 14, 3, 1, 100.00, 85, 1),
	(182, 1, 14, 3, 1, 103.00, 2, 1),
	(183, 1, 14, 3, 1, 105.00, 1, 1),
	(184, 1, 14, 3, 1, 108.00, 1, 1),
	(185, 1, 14, 3, 1, 109.00, 1, 1),
	(186, 1, 14, 3, 1, 111.00, 1, 1),
	(187, 1, 14, 4, 1, 68.00, 1, 1),
	(188, 1, 14, 4, 1, 82.00, 1, 1),
	(189, 1, 14, 4, 1, 89.00, 1, 1),
	(190, 1, 14, 4, 1, 90.00, 1, 1),
	(191, 1, 14, 4, 1, 98.00, 1, 1),
	(192, 1, 14, 4, 1, 99.00, 1, 1),
	(193, 1, 14, 4, 1, 100.00, 45, 1),
	(194, 1, 14, 5, 1, 71.00, 1, 1),
	(195, 1, 14, 5, 1, 90.00, 1, 1),
	(196, 1, 14, 5, 1, 100.00, 18, 1),
	(197, 1, 14, 5, 1, 103.00, 1, 1),
	(198, 1, 14, 56, 1, 70.00, 1, 1),
	(199, 1, 14, 56, 1, 95.00, 1, 1),
	(200, 1, 14, 56, 1, 98.00, 1, 1),
	(201, 1, 14, 56, 1, 100.00, 17, 1),
	(328, 1, 27, 12, 1, 52.00, 1, 1),
	(329, 1, 27, 12, 1, 57.00, 1, 1),
	(330, 1, 27, 12, 1, 65.00, 1, 1),
	(331, 1, 27, 12, 1, 71.00, 1, 1),
	(332, 1, 27, 12, 1, 73.00, 1, 1),
	(333, 1, 27, 12, 1, 74.00, 1, 1),
	(334, 1, 27, 12, 1, 79.00, 1, 1),
	(335, 1, 27, 12, 1, 84.00, 1, 1),
	(336, 1, 27, 12, 1, 85.00, 2, 1),
	(337, 1, 27, 12, 1, 92.00, 2, 1),
	(338, 1, 27, 12, 1, 93.00, 1, 1),
	(339, 1, 27, 12, 1, 95.00, 1, 1),
	(340, 1, 27, 12, 1, 96.00, 1, 1),
	(341, 1, 27, 12, 1, 100.00, 49, 1),
	(342, 1, 27, 12, 1, 110.00, 1, 1),
	(343, 1, 27, 12, 1, 122.00, 1, 1),
	(344, 1, 27, 58, 1, 76.00, 1, 1),
	(345, 1, 27, 58, 1, 80.00, 1, 1),
	(346, 1, 27, 58, 1, 88.00, 1, 1),
	(347, 1, 27, 58, 1, 94.00, 1, 1),
	(348, 1, 27, 58, 1, 100.00, 27, 1),
	(349, 1, 27, 70, 1, 70.00, 1, 1),
	(350, 1, 27, 70, 1, 73.00, 1, 1),
	(351, 1, 27, 70, 1, 98.00, 1, 1),
	(352, 1, 27, 70, 1, 99.00, 1, 1),
	(353, 1, 27, 70, 1, 100.00, 8, 1),
	(354, 1, 27, 70, 1, 103.00, 1, 1),
	(355, 1, 27, 70, 1, 114.00, 1, 1),
	(512, 1, 1, 9, 1, 100.00, 10, 2),
	(513, 1, 1, 13, 1, 100.00, 22, 2),
	(514, 1, 1, 16, 1, 100.00, 10, 2),
	(515, 1, 1, 22, 1, 100.00, 10, 2),
	(516, 1, 1, 30, 1, 100.00, 10, 2),
	(517, 1, 1, 36, 1, 100.00, 10, 2),
	(518, 1, 1, 43, 1, 100.00, 3, 2),
	(519, 1, 1, 46, 1, 100.00, 10, 2),
	(520, 1, 1, 71, 1, 100.00, 21, 2),
	(521, 1, 1, 73, 1, 100.00, 10, 2),
	(522, 1, 1, 79, 1, 95.00, 1, 2),
	(523, 1, 1, 79, 1, 97.00, 1, 2),
	(524, 1, 1, 79, 1, 100.00, 7, 2),
	(525, 1, 1, 79, 1, 149.00, 1, 2),
	(526, 1, 3, 3, 1, 26.70, 1, 2),
	(527, 1, 3, 3, 1, 28.33, 1, 2),
	(528, 1, 3, 3, 1, 28.91, 1, 2),
	(529, 1, 3, 3, 1, 28.93, 2, 2),
	(530, 1, 3, 3, 1, 28.95, 1, 2),
	(531, 1, 3, 3, 1, 29.08, 1, 2),
	(532, 1, 3, 3, 1, 29.09, 1, 2),
	(533, 1, 3, 3, 1, 29.11, 1, 2),
	(534, 1, 3, 3, 1, 29.12, 1, 2),
	(535, 1, 3, 3, 1, 29.16, 1, 2),
	(536, 1, 3, 3, 1, 29.17, 1, 2),
	(537, 1, 3, 3, 1, 29.22, 2, 2),
	(538, 1, 3, 3, 1, 29.25, 1, 2),
	(539, 1, 3, 3, 1, 29.27, 1, 2),
	(540, 1, 3, 3, 1, 29.28, 3, 2),
	(541, 1, 3, 3, 1, 29.31, 2, 2),
	(542, 1, 3, 3, 1, 29.33, 1, 2),
	(543, 1, 3, 3, 1, 29.34, 2, 2),
	(544, 1, 3, 3, 1, 29.51, 1, 2),
	(545, 1, 3, 3, 1, 29.52, 1, 2),
	(546, 1, 3, 3, 1, 29.53, 2, 2),
	(547, 1, 3, 3, 1, 29.60, 1, 2),
	(548, 1, 3, 3, 1, 29.62, 1, 2),
	(549, 1, 3, 3, 1, 29.65, 1, 2),
	(550, 1, 3, 3, 1, 29.66, 1, 2),
	(551, 1, 3, 3, 1, 29.78, 1, 2),
	(552, 1, 3, 3, 1, 29.82, 1, 2),
	(553, 1, 3, 3, 1, 30.18, 1, 2),
	(554, 1, 3, 4, 1, 26.28, 1, 2),
	(555, 1, 3, 4, 1, 28.52, 1, 2),
	(556, 1, 3, 4, 1, 28.66, 1, 2),
	(557, 1, 3, 4, 1, 28.88, 1, 2),
	(558, 1, 3, 4, 1, 28.92, 1, 2),
	(559, 1, 3, 4, 1, 28.99, 1, 2),
	(560, 1, 3, 4, 1, 29.48, 1, 2),
	(561, 1, 3, 5, 1, 28.27, 1, 2),
	(562, 1, 3, 5, 1, 28.66, 1, 2),
	(563, 1, 3, 5, 1, 29.13, 1, 2),
	(564, 1, 3, 5, 1, 29.28, 1, 2),
	(565, 1, 3, 9, 1, 28.32, 1, 2),
	(566, 1, 3, 9, 1, 28.40, 1, 2),
	(567, 1, 3, 9, 1, 28.44, 2, 2),
	(568, 1, 3, 9, 1, 28.51, 1, 2),
	(569, 1, 3, 9, 1, 28.88, 1, 2),
	(570, 1, 3, 9, 1, 28.90, 1, 2),
	(571, 1, 3, 9, 1, 28.96, 1, 2),
	(572, 1, 3, 12, 1, 28.56, 1, 2),
	(573, 1, 3, 12, 1, 29.71, 1, 2),
	(574, 1, 3, 13, 1, 28.04, 1, 2),
	(575, 1, 3, 13, 1, 28.43, 1, 2),
	(576, 1, 3, 13, 1, 28.68, 1, 2),
	(577, 1, 3, 30, 1, 21.60, 1, 2),
	(578, 1, 3, 30, 1, 27.73, 1, 2),
	(579, 1, 3, 30, 1, 28.09, 1, 2),
	(580, 1, 3, 30, 1, 28.52, 1, 2),
	(581, 1, 3, 30, 1, 28.61, 1, 2),
	(582, 1, 3, 30, 1, 28.70, 1, 2),
	(583, 1, 3, 30, 1, 28.82, 1, 2),
	(584, 1, 3, 30, 1, 28.83, 1, 2),
	(585, 1, 3, 30, 1, 29.35, 1, 2),
	(586, 1, 3, 30, 1, 29.81, 1, 2),
	(587, 1, 3, 42, 1, 27.74, 1, 2),
	(588, 1, 3, 42, 1, 28.07, 1, 2),
	(589, 1, 3, 42, 1, 28.21, 1, 2),
	(590, 1, 3, 42, 1, 28.51, 1, 2),
	(591, 1, 3, 42, 1, 28.55, 1, 2),
	(592, 1, 3, 42, 1, 28.59, 1, 2),
	(593, 1, 3, 42, 1, 28.75, 1, 2),
	(594, 1, 3, 42, 1, 28.81, 1, 2),
	(595, 1, 3, 42, 1, 28.87, 1, 2),
	(596, 1, 3, 42, 1, 29.13, 1, 2),
	(597, 1, 4, 3, 1, 96.00, 1, 2),
	(598, 1, 4, 3, 1, 98.00, 1, 2),
	(599, 1, 4, 3, 1, 100.00, 15, 2),
	(600, 1, 4, 3, 1, 102.00, 3, 2),
	(601, 1, 4, 4, 1, 100.00, 20, 2),
	(602, 1, 4, 6, 1, 100.00, 12, 2),
	(603, 1, 4, 12, 1, 100.00, 8, 2),
	(604, 1, 4, 51, 1, 100.00, 10, 2),
	(605, 1, 4, 61, 1, 100.00, 3, 2),
	(606, 1, 6, 3, 1, 100.00, 41, 2),
	(607, 1, 6, 4, 1, 100.00, 8, 2),
	(608, 1, 6, 6, 1, 51.00, 1, 2),
	(609, 1, 6, 6, 1, 60.00, 1, 2),
	(610, 1, 6, 6, 1, 66.00, 1, 2),
	(611, 1, 6, 6, 1, 71.00, 1, 2),
	(612, 1, 6, 6, 1, 75.00, 1, 2),
	(613, 1, 6, 6, 1, 86.00, 1, 2),
	(614, 1, 6, 6, 1, 91.00, 1, 2),
	(615, 1, 6, 6, 1, 92.00, 1, 2),
	(616, 1, 6, 6, 1, 93.00, 1, 2),
	(617, 1, 6, 6, 1, 100.00, 19, 2),
	(618, 1, 6, 6, 1, 107.00, 1, 2),
	(619, 1, 6, 6, 1, 108.00, 1, 2),
	(620, 1, 6, 6, 1, 117.00, 1, 2),
	(621, 1, 6, 6, 1, 119.00, 1, 2),
	(622, 1, 6, 7, 1, 100.00, 15, 2),
	(623, 1, 6, 9, 1, 48.00, 1, 2),
	(624, 1, 6, 9, 1, 64.00, 1, 2),
	(625, 1, 6, 9, 1, 65.00, 1, 2),
	(626, 1, 6, 9, 1, 68.00, 1, 2),
	(627, 1, 6, 9, 1, 69.00, 1, 2),
	(628, 1, 6, 9, 1, 88.00, 1, 2),
	(629, 1, 6, 9, 1, 90.00, 1, 2),
	(630, 1, 6, 9, 1, 100.00, 20, 2),
	(631, 1, 6, 9, 1, 123.00, 1, 2),
	(632, 1, 6, 15, 1, 100.00, 5, 2),
	(633, 1, 6, 22, 1, 91.00, 1, 2),
	(634, 1, 6, 22, 1, 100.00, 7, 2),
	(635, 1, 6, 41, 1, 100.00, 3, 2),
	(636, 1, 6, 47, 1, 100.00, 2, 2),
	(637, 1, 6, 61, 1, 100.00, 1, 2),
	(638, 1, 6, 62, 1, 65.00, 1, 2),
	(639, 1, 6, 62, 1, 75.00, 1, 2),
	(640, 1, 6, 62, 1, 78.00, 1, 2),
	(641, 1, 6, 62, 1, 79.00, 1, 2),
	(642, 1, 6, 62, 1, 85.00, 1, 2),
	(643, 1, 6, 62, 1, 87.00, 2, 2),
	(644, 1, 6, 62, 1, 89.00, 1, 2),
	(645, 1, 6, 62, 1, 91.00, 1, 2),
	(646, 1, 6, 62, 1, 92.00, 1, 2),
	(647, 1, 6, 62, 1, 95.00, 2, 2),
	(648, 1, 6, 62, 1, 97.00, 1, 2),
	(649, 1, 6, 62, 1, 98.00, 1, 2),
	(650, 1, 6, 62, 1, 100.00, 49, 2),
	(651, 1, 6, 62, 1, 101.00, 2, 2),
	(652, 1, 6, 62, 1, 102.00, 1, 2),
	(653, 1, 6, 62, 1, 103.00, 1, 2),
	(654, 1, 7, 3, 1, 51.00, 1, 2),
	(655, 1, 7, 3, 1, 68.00, 1, 2),
	(656, 1, 7, 3, 1, 74.00, 1, 2),
	(657, 1, 7, 3, 1, 83.00, 1, 2),
	(658, 1, 7, 3, 1, 94.00, 1, 2),
	(659, 1, 7, 3, 1, 95.00, 1, 2),
	(660, 1, 7, 3, 1, 96.00, 1, 2),
	(661, 1, 7, 3, 1, 97.00, 1, 2),
	(662, 1, 7, 3, 1, 98.00, 1, 2),
	(663, 1, 7, 3, 1, 99.00, 1, 2),
	(664, 1, 7, 3, 1, 100.00, 74, 2),
	(665, 1, 7, 3, 1, 148.00, 1, 2),
	(666, 1, 7, 5, 1, 100.00, 4, 2),
	(667, 1, 7, 9, 1, 100.00, 8, 2),
	(668, 1, 7, 13, 1, 100.00, 10, 2),
	(669, 1, 7, 16, 1, 100.00, 4, 2),
	(670, 1, 7, 41, 1, 100.00, 5, 2),
	(671, 1, 7, 52, 1, 100.00, 4, 2),
	(672, 1, 7, 61, 1, 100.00, 4, 2),
	(673, 1, 7, 68, 1, 100.00, 9, 2),
	(674, 1, 8, 3, 1, 61.00, 1, 2),
	(675, 1, 8, 3, 1, 75.00, 1, 2),
	(676, 1, 8, 3, 1, 76.00, 1, 2),
	(677, 1, 8, 3, 1, 85.00, 1, 2),
	(678, 1, 8, 3, 1, 100.00, 6, 2),
	(679, 1, 8, 3, 1, 101.00, 1, 2),
	(680, 1, 8, 3, 1, 126.00, 1, 2),
	(681, 1, 8, 3, 1, 130.00, 1, 2),
	(682, 1, 8, 3, 1, 146.00, 1, 2),
	(683, 1, 9, 8, 1, 100.00, 15, 2),
	(684, 1, 9, 24, 1, 100.00, 10, 2),
	(685, 1, 10, 3, 1, 69.00, 2, 2),
	(686, 1, 10, 3, 1, 70.00, 1, 2),
	(687, 1, 10, 3, 1, 74.00, 1, 2),
	(688, 1, 10, 3, 1, 78.00, 1, 2),
	(689, 1, 10, 3, 1, 79.00, 2, 2),
	(690, 1, 10, 3, 1, 85.00, 1, 2),
	(691, 1, 10, 3, 1, 86.00, 1, 2),
	(692, 1, 10, 3, 1, 93.00, 1, 2),
	(693, 1, 10, 3, 1, 95.00, 1, 2),
	(694, 1, 10, 3, 1, 97.00, 2, 2),
	(695, 1, 10, 3, 1, 100.00, 12, 2),
	(696, 1, 10, 3, 1, 102.00, 1, 2),
	(697, 1, 10, 3, 1, 133.00, 1, 2),
	(698, 1, 10, 4, 1, 100.00, 20, 2),
	(699, 1, 10, 7, 1, 100.00, 10, 2),
	(700, 1, 10, 41, 1, 100.00, 3, 2),
	(701, 1, 10, 47, 1, 68.00, 1, 2),
	(702, 1, 10, 47, 1, 72.00, 1, 2),
	(703, 1, 10, 47, 1, 75.00, 1, 2),
	(704, 1, 10, 47, 1, 88.00, 1, 2),
	(705, 1, 10, 47, 1, 93.00, 2, 2),
	(706, 1, 10, 47, 1, 97.00, 1, 2),
	(707, 1, 10, 47, 1, 98.00, 2, 2),
	(708, 1, 10, 47, 1, 100.00, 27, 2),
	(709, 1, 10, 63, 1, 100.00, 4, 2),
	(710, 1, 10, 64, 1, 72.00, 1, 2),
	(711, 1, 10, 64, 1, 79.00, 1, 2),
	(712, 1, 10, 64, 1, 85.00, 1, 2),
	(713, 1, 10, 64, 1, 87.00, 1, 2),
	(714, 1, 10, 64, 1, 89.00, 2, 2),
	(715, 1, 10, 64, 1, 96.00, 1, 2),
	(716, 1, 10, 64, 1, 100.00, 23, 2),
	(717, 1, 10, 64, 1, 104.00, 1, 2),
	(718, 1, 11, 8, 1, 100.00, 13, 2),
	(719, 1, 11, 24, 1, 73.00, 1, 2),
	(720, 1, 11, 24, 1, 86.00, 1, 2),
	(721, 1, 11, 24, 1, 87.00, 1, 2),
	(722, 1, 11, 24, 1, 99.00, 1, 2),
	(723, 1, 11, 24, 1, 100.00, 18, 2),
	(724, 1, 11, 24, 1, 103.00, 1, 2),
	(725, 1, 12, 3, 1, 65.00, 1, 2),
	(726, 1, 12, 3, 1, 75.00, 1, 2),
	(727, 1, 12, 3, 1, 85.00, 1, 2),
	(728, 1, 12, 3, 1, 86.00, 1, 2),
	(729, 1, 12, 3, 1, 93.00, 1, 2),
	(730, 1, 12, 3, 1, 100.00, 114, 2),
	(731, 1, 12, 3, 1, 104.00, 1, 2),
	(732, 1, 12, 54, 1, 100.00, 1, 2),
	(733, 1, 13, 3, 1, 57.00, 1, 2),
	(734, 1, 13, 3, 1, 64.00, 1, 2),
	(735, 1, 13, 3, 1, 69.00, 1, 2),
	(736, 1, 13, 3, 1, 76.00, 1, 2),
	(737, 1, 13, 3, 1, 83.00, 1, 2),
	(738, 1, 13, 3, 1, 85.00, 2, 2),
	(739, 1, 13, 3, 1, 89.00, 1, 2),
	(740, 1, 13, 3, 1, 90.00, 1, 2),
	(741, 1, 13, 3, 1, 91.00, 2, 2),
	(742, 1, 13, 3, 1, 93.00, 3, 2),
	(743, 1, 13, 3, 1, 94.00, 2, 2),
	(744, 1, 13, 3, 1, 95.00, 3, 2),
	(745, 1, 13, 3, 1, 96.00, 1, 2),
	(746, 1, 13, 3, 1, 97.00, 1, 2),
	(747, 1, 13, 3, 1, 98.00, 3, 2),
	(748, 1, 13, 3, 1, 99.00, 1, 2),
	(749, 1, 13, 3, 1, 100.00, 103, 2),
	(750, 1, 13, 3, 1, 102.00, 1, 2),
	(751, 1, 13, 3, 1, 103.00, 1, 2),
	(752, 1, 13, 3, 1, 104.00, 1, 2),
	(753, 1, 13, 3, 1, 105.00, 1, 2),
	(754, 1, 13, 3, 1, 106.00, 1, 2),
	(755, 1, 13, 3, 1, 108.00, 1, 2),
	(756, 1, 13, 3, 1, 110.00, 1, 2),
	(757, 1, 13, 3, 1, 114.00, 1, 2),
	(758, 1, 13, 3, 1, 115.00, 1, 2),
	(759, 1, 13, 3, 1, 117.00, 1, 2),
	(760, 1, 13, 3, 1, 123.00, 1, 2),
	(761, 1, 13, 3, 1, 133.00, 1, 2),
	(762, 1, 13, 3, 1, 142.00, 1, 2),
	(763, 1, 13, 4, 1, 100.00, 5, 2),
	(764, 1, 13, 7, 1, 100.00, 21, 2),
	(765, 1, 13, 9, 1, 76.00, 1, 2),
	(766, 1, 13, 9, 1, 81.00, 1, 2),
	(767, 1, 13, 9, 1, 84.00, 1, 2),
	(768, 1, 13, 9, 1, 92.00, 1, 2),
	(769, 1, 13, 9, 1, 93.00, 1, 2),
	(770, 1, 13, 9, 1, 94.00, 1, 2),
	(771, 1, 13, 9, 1, 96.00, 1, 2),
	(772, 1, 13, 9, 1, 97.00, 2, 2),
	(773, 1, 13, 9, 1, 98.00, 1, 2),
	(774, 1, 13, 9, 1, 100.00, 30, 2),
	(775, 1, 13, 12, 1, 100.00, 5, 2),
	(776, 1, 13, 13, 1, 100.00, 7, 2),
	(777, 1, 13, 22, 1, 100.00, 1, 2),
	(778, 1, 13, 41, 1, 100.00, 5, 2),
	(779, 1, 13, 60, 1, 100.00, 32, 2),
	(780, 1, 15, 3, 1, 66.00, 2, 2),
	(781, 1, 15, 3, 1, 67.00, 1, 2),
	(782, 1, 15, 3, 1, 69.00, 1, 2),
	(783, 1, 15, 3, 1, 70.00, 1, 2),
	(784, 1, 15, 3, 1, 72.00, 1, 2),
	(785, 1, 15, 3, 1, 75.00, 1, 2),
	(786, 1, 15, 3, 1, 76.00, 1, 2),
	(787, 1, 15, 3, 1, 77.00, 2, 2),
	(788, 1, 15, 3, 1, 78.00, 1, 2),
	(789, 1, 15, 3, 1, 79.00, 3, 2),
	(790, 1, 15, 3, 1, 81.00, 1, 2),
	(791, 1, 15, 3, 1, 82.00, 3, 2),
	(792, 1, 15, 3, 1, 84.00, 3, 2),
	(793, 1, 15, 3, 1, 85.00, 2, 2),
	(794, 1, 15, 3, 1, 86.00, 4, 2),
	(795, 1, 15, 3, 1, 87.00, 2, 2),
	(796, 1, 15, 3, 1, 90.00, 5, 2),
	(797, 1, 15, 3, 1, 91.00, 3, 2),
	(798, 1, 15, 3, 1, 92.00, 2, 2),
	(799, 1, 15, 3, 1, 93.00, 6, 2),
	(800, 1, 15, 3, 1, 94.00, 4, 2),
	(801, 1, 15, 3, 1, 95.00, 2, 2),
	(802, 1, 15, 3, 1, 96.00, 2, 2),
	(803, 1, 15, 3, 1, 97.00, 2, 2),
	(804, 1, 15, 3, 1, 98.00, 1, 2),
	(805, 1, 15, 3, 1, 99.00, 4, 2),
	(806, 1, 15, 3, 1, 100.00, 284, 2),
	(807, 1, 15, 3, 1, 102.00, 5, 2),
	(808, 1, 15, 3, 1, 103.00, 3, 2),
	(809, 1, 15, 3, 1, 104.00, 2, 2),
	(810, 1, 15, 3, 1, 107.00, 2, 2),
	(811, 1, 15, 3, 1, 108.00, 1, 2),
	(812, 1, 15, 3, 1, 109.00, 1, 2),
	(813, 1, 15, 3, 1, 110.00, 1, 2),
	(814, 1, 15, 3, 1, 111.00, 3, 2),
	(815, 1, 15, 3, 1, 112.00, 1, 2),
	(816, 1, 15, 3, 1, 113.00, 3, 2),
	(817, 1, 15, 3, 1, 115.00, 2, 2),
	(818, 1, 15, 3, 1, 116.00, 1, 2),
	(819, 1, 15, 3, 1, 117.00, 1, 2),
	(820, 1, 15, 3, 1, 118.00, 1, 2),
	(821, 1, 15, 3, 1, 120.00, 3, 2),
	(822, 1, 15, 3, 1, 122.00, 3, 2),
	(823, 1, 15, 3, 1, 123.00, 2, 2),
	(824, 1, 15, 3, 1, 124.00, 2, 2),
	(825, 1, 15, 3, 1, 125.00, 1, 2),
	(826, 1, 15, 3, 1, 126.00, 1, 2),
	(827, 1, 15, 3, 1, 127.00, 1, 2),
	(828, 1, 15, 3, 1, 128.00, 1, 2),
	(829, 1, 15, 3, 1, 130.00, 1, 2),
	(830, 1, 15, 3, 1, 131.00, 2, 2),
	(831, 1, 15, 3, 1, 132.00, 2, 2),
	(832, 1, 15, 3, 1, 133.00, 1, 2),
	(833, 1, 15, 3, 1, 140.00, 1, 2),
	(834, 1, 15, 3, 1, 142.00, 1, 2),
	(835, 1, 15, 3, 1, 143.00, 1, 2),
	(836, 1, 15, 3, 1, 144.00, 1, 2),
	(837, 1, 15, 3, 1, 145.00, 1, 2),
	(838, 1, 15, 3, 1, 146.00, 2, 2),
	(839, 1, 15, 3, 1, 148.00, 2, 2),
	(840, 1, 15, 3, 1, 149.00, 2, 2),
	(841, 1, 16, 3, 1, 20.61, 1, 2),
	(842, 1, 16, 3, 1, 22.69, 1, 2),
	(843, 1, 16, 3, 1, 22.95, 1, 2),
	(844, 1, 16, 3, 1, 23.13, 1, 2),
	(845, 1, 16, 3, 1, 23.95, 1, 2),
	(846, 1, 16, 3, 1, 24.25, 1, 2),
	(847, 1, 16, 3, 1, 26.91, 1, 2),
	(848, 1, 16, 3, 1, 26.98, 1, 2),
	(849, 1, 16, 7, 1, 21.40, 1, 2),
	(850, 1, 16, 7, 1, 22.13, 1, 2),
	(851, 1, 16, 7, 1, 22.83, 1, 2),
	(852, 1, 16, 7, 1, 24.51, 1, 2),
	(853, 1, 16, 7, 1, 24.62, 1, 2),
	(854, 1, 16, 7, 1, 24.78, 1, 2),
	(855, 1, 16, 7, 1, 24.94, 1, 2),
	(856, 1, 16, 7, 1, 25.53, 1, 2),
	(857, 1, 27, 3, 1, 94.00, 1, 2),
	(858, 1, 27, 3, 1, 95.00, 1, 2),
	(859, 1, 27, 3, 1, 97.00, 1, 2),
	(860, 1, 27, 3, 1, 100.00, 22, 2),
	(861, 1, 27, 3, 1, 102.00, 1, 2),
	(862, 1, 27, 3, 1, 103.00, 1, 2),
	(863, 1, 27, 3, 1, 109.00, 1, 2),
	(864, 1, 27, 5, 1, 89.00, 1, 2),
	(865, 1, 27, 5, 1, 91.00, 1, 2),
	(866, 1, 27, 5, 1, 94.00, 1, 2),
	(867, 1, 27, 5, 1, 100.00, 4, 2),
	(868, 1, 27, 5, 1, 112.00, 1, 2),
	(869, 1, 27, 7, 1, 65.00, 1, 2),
	(870, 1, 27, 7, 1, 82.00, 1, 2),
	(871, 1, 27, 7, 1, 94.00, 1, 2),
	(872, 1, 27, 7, 1, 96.00, 1, 2),
	(873, 1, 27, 7, 1, 100.00, 39, 2),
	(874, 1, 27, 9, 1, 83.00, 1, 2),
	(875, 1, 27, 9, 1, 96.00, 2, 2),
	(876, 1, 27, 9, 1, 98.00, 1, 2),
	(877, 1, 27, 9, 1, 99.00, 1, 2),
	(878, 1, 27, 12, 1, 100.00, 8, 2),
	(879, 1, 27, 13, 1, 100.00, 4, 2),
	(880, 1, 27, 15, 1, 97.00, 1, 2),
	(881, 1, 27, 15, 1, 100.00, 4, 2),
	(882, 1, 27, 56, 1, 100.00, 16, 2),
	(883, 1, 27, 58, 1, 100.00, 3, 2),
	(884, 1, 27, 70, 1, 100.00, 5, 2),
	(885, 1, 28, 8, 1, 99.00, 1, 2),
	(886, 1, 28, 8, 1, 100.00, 3, 2),
	(887, 1, 28, 8, 1, 107.00, 1, 2),
	(888, 1, 29, 24, 1, 69.00, 1, 2),
	(889, 1, 29, 24, 1, 91.00, 1, 2),
	(890, 1, 29, 24, 1, 100.00, 2, 2),
	(891, 1, 29, 24, 1, 140.00, 1, 2),
	(1023, 1, 1, 2, 2, 100.00, 1, 2),
	(1024, 1, 1, 3, 2, 100.00, 3, 2),
	(1025, 1, 1, 4, 2, 100.00, 1, 2),
	(1026, 1, 1, 7, 2, 100.00, 2, 2),
	(1027, 1, 1, 9, 2, 100.00, 2, 2),
	(1028, 1, 1, 16, 2, 100.00, 1, 2),
	(1029, 1, 1, 17, 2, 100.00, 4, 2),
	(1030, 1, 1, 20, 2, 97.00, 1, 2),
	(1031, 1, 1, 21, 2, 100.00, 1, 2),
	(1032, 1, 1, 32, 2, 100.00, 3, 2),
	(1033, 1, 1, 36, 2, 100.00, 2, 2),
	(1034, 1, 1, 42, 2, 100.00, 2, 2),
	(1035, 1, 1, 43, 2, 100.00, 1, 2),
	(1036, 1, 1, 45, 2, 100.00, 4, 2),
	(1037, 1, 1, 46, 2, 100.00, 1, 2),
	(1038, 1, 1, 67, 2, 100.00, 1, 2),
	(1039, 1, 1, 69, 2, 100.00, 2, 2),
	(1040, 1, 1, 71, 2, 100.00, 3, 2),
	(1041, 1, 1, 73, 2, 100.00, 2, 2),
	(1042, 1, 1, 77, 2, 100.00, 1, 2),
	(1043, 1, 1, 78, 2, 100.00, 1, 2),
	(1044, 1, 1, 79, 2, 100.00, 1, 2),
	(1045, 1, 1, 82, 2, 100.00, 1, 2),
	(1046, 1, 1, 84, 2, 100.00, 2, 2),
	(1047, 1, 3, 3, 2, 29.11, 1, 2),
	(1048, 1, 4, 3, 2, 100.00, 4, 2),
	(1049, 1, 4, 4, 2, 100.00, 3, 2),
	(1050, 1, 4, 6, 2, 100.00, 2, 2),
	(1051, 1, 4, 9, 2, 100.00, 5, 2),
	(1052, 1, 4, 12, 2, 100.00, 1, 2),
	(1053, 1, 4, 63, 2, 100.00, 1, 2),
	(1054, 1, 5, 13, 2, 99.00, 1, 2),
	(1055, 1, 5, 13, 2, 100.00, 3, 2),
	(1056, 1, 6, 3, 2, 100.00, 3, 2),
	(1057, 1, 6, 4, 2, 100.00, 11, 2),
	(1058, 1, 6, 6, 2, 100.00, 5, 2),
	(1059, 1, 6, 12, 2, 63.00, 1, 2),
	(1060, 1, 6, 12, 2, 65.00, 1, 2),
	(1061, 1, 6, 12, 2, 75.00, 2, 2),
	(1062, 1, 6, 12, 2, 97.00, 1, 2),
	(1063, 1, 6, 12, 2, 102.00, 2, 2),
	(1064, 1, 6, 12, 2, 103.00, 1, 2),
	(1065, 1, 6, 13, 2, 100.00, 8, 2),
	(1066, 1, 6, 15, 2, 100.00, 6, 2),
	(1067, 1, 6, 22, 2, 100.00, 1, 2),
	(1068, 1, 6, 41, 2, 100.00, 3, 2),
	(1069, 1, 6, 47, 2, 100.00, 12, 2),
	(1070, 1, 6, 62, 2, 100.00, 3, 2),
	(1071, 1, 7, 3, 2, 100.00, 35, 2),
	(1072, 1, 7, 4, 2, 100.00, 1, 2),
	(1073, 1, 7, 15, 2, 100.00, 1, 2),
	(1074, 1, 7, 16, 2, 100.00, 1, 2),
	(1075, 1, 7, 41, 2, 100.00, 1, 2),
	(1076, 1, 7, 54, 2, 100.00, 2, 2),
	(1077, 1, 7, 58, 2, 100.00, 2, 2),
	(1078, 1, 7, 68, 2, 100.00, 3, 2),
	(1079, 1, 8, 3, 2, 100.00, 1, 2),
	(1080, 1, 8, 56, 2, 100.00, 1, 2),
	(1081, 1, 9, 8, 2, 100.00, 2, 2),
	(1082, 1, 9, 24, 2, 100.00, 1, 2),
	(1083, 1, 10, 3, 2, 94.00, 1, 2),
	(1084, 1, 10, 3, 2, 99.00, 3, 2),
	(1085, 1, 10, 3, 2, 100.00, 1, 2),
	(1086, 1, 10, 3, 2, 101.00, 1, 2),
	(1087, 1, 10, 3, 2, 103.00, 1, 2),
	(1088, 1, 10, 4, 2, 100.00, 1, 2),
	(1089, 1, 10, 5, 2, 100.00, 1, 2),
	(1090, 1, 10, 7, 2, 100.00, 3, 2),
	(1091, 1, 10, 9, 2, 100.00, 1, 2),
	(1092, 1, 10, 12, 2, 100.00, 2, 2),
	(1093, 1, 10, 15, 2, 100.00, 1, 2),
	(1094, 1, 10, 41, 2, 100.00, 3, 2),
	(1095, 1, 10, 47, 2, 100.00, 1, 2),
	(1096, 1, 10, 64, 2, 100.00, 1, 2),
	(1097, 1, 11, 8, 2, 100.00, 2, 2),
	(1098, 1, 12, 3, 2, 100.00, 114, 2),
	(1099, 1, 12, 12, 2, 100.00, 22, 2),
	(1100, 1, 12, 31, 2, 100.00, 3, 2),
	(1101, 1, 12, 54, 2, 100.00, 3, 2),
	(1102, 1, 13, 2, 2, 100.00, 1, 2),
	(1103, 1, 13, 3, 2, 100.00, 93, 2),
	(1104, 1, 13, 4, 2, 100.00, 4, 2),
	(1105, 1, 13, 7, 2, 100.00, 55, 2),
	(1106, 1, 13, 9, 2, 100.00, 3, 2),
	(1107, 1, 13, 12, 2, 100.00, 5, 2),
	(1108, 1, 13, 22, 2, 100.00, 4, 2),
	(1109, 1, 13, 30, 2, 100.00, 8, 2),
	(1110, 1, 13, 41, 2, 100.00, 7, 2),
	(1111, 1, 13, 47, 2, 100.00, 2, 2),
	(1112, 1, 13, 60, 2, 100.00, 5, 2),
	(1113, 1, 13, 61, 2, 100.00, 1, 2),
	(1114, 1, 14, 3, 2, 100.00, 2, 2),
	(1115, 1, 14, 7, 2, 100.00, 1, 2),
	(1116, 1, 14, 9, 2, 100.00, 10, 2),
	(1117, 1, 14, 12, 2, 100.00, 6, 2),
	(1118, 1, 14, 56, 2, 100.00, 2, 2),
	(1119, 1, 15, 3, 2, 1.00, 0, 2),
	(1120, 1, 15, 3, 2, 100.00, 139, 2),
	(1121, 1, 18, 9, 2, 2.00, 0, 2),
	(1122, 1, 19, 24, 2, 100.00, 2, 2),
	(1123, 1, 26, 7, 2, 83.00, 1, 2),
	(1124, 1, 26, 7, 2, 85.00, 1, 2),
	(1125, 1, 26, 7, 2, 90.00, 1, 2),
	(1126, 1, 26, 7, 2, 91.00, 1, 2),
	(1127, 1, 26, 7, 2, 94.00, 1, 2),
	(1128, 1, 26, 7, 2, 100.00, 2, 2),
	(1129, 1, 26, 7, 2, 102.00, 1, 2),
	(1130, 1, 27, 3, 2, 100.00, 3, 2),
	(1131, 1, 27, 5, 2, 100.00, 3, 2),
	(1132, 1, 27, 7, 2, 100.00, 1, 2),
	(1133, 1, 27, 9, 2, 2.00, 0, 2),
	(1134, 1, 27, 9, 2, 100.00, 1, 2),
	(1135, 1, 27, 42, 2, 86.00, 1, 2),
	(1136, 1, 27, 42, 2, 100.00, 1, 2),
	(1137, 1, 27, 42, 2, 113.00, 1, 2),
	(1138, 1, 27, 56, 2, 100.00, 1, 2),
	(1139, 1, 28, 8, 2, 100.00, 2, 2),
	(1140, 1, 28, 24, 2, 100.00, 2, 2),
	(1150, 1, 1, 71, 1, 100.00, 1, 3),
	(1151, 1, 27, 9, 1, 98.00, 1, 3),
	(1153, 1, 1, 3, 2, 100.00, 3, 3),
	(1154, 1, 1, 4, 2, 100.00, 3, 3),
	(1155, 1, 1, 9, 2, 100.00, 3, 3),
	(1156, 1, 1, 16, 2, 100.00, 3, 3),
	(1157, 1, 1, 32, 2, 100.00, 3, 3),
	(1158, 1, 1, 36, 2, 100.00, 3, 3),
	(1159, 1, 1, 43, 2, 100.00, 3, 3),
	(1160, 1, 1, 46, 2, 100.00, 3, 3),
	(1161, 1, 1, 73, 2, 100.00, 3, 3),
	(1162, 1, 1, 82, 2, 97.00, 1, 3),
	(1163, 1, 1, 82, 2, 100.00, 2, 3),
	(1164, 1, 1, 83, 2, 100.00, 3, 3),
	(1165, 1, 4, 3, 2, 100.00, 8, 3),
	(1166, 1, 4, 4, 2, 100.00, 3, 3),
	(1167, 1, 4, 9, 2, 97.00, 1, 3),
	(1168, 1, 4, 9, 2, 100.00, 2, 3),
	(1169, 1, 5, 13, 2, 99.00, 1, 3),
	(1170, 1, 5, 13, 2, 100.00, 3, 3),
	(1171, 1, 6, 4, 2, 100.00, 10, 3),
	(1172, 1, 6, 12, 2, 65.00, 1, 3),
	(1173, 1, 6, 12, 2, 97.00, 1, 3),
	(1174, 1, 6, 12, 2, 102.00, 2, 3),
	(1175, 1, 6, 12, 2, 103.00, 1, 3),
	(1176, 1, 6, 13, 2, 100.00, 5, 3),
	(1177, 1, 6, 15, 2, 100.00, 5, 3),
	(1178, 1, 6, 22, 2, 100.00, 3, 3),
	(1179, 1, 6, 41, 2, 100.00, 5, 3),
	(1180, 1, 6, 47, 2, 100.00, 15, 3),
	(1181, 1, 7, 3, 2, 100.00, 35, 3),
	(1182, 1, 7, 4, 2, 100.00, 8, 3),
	(1183, 1, 7, 12, 2, 100.00, 3, 3),
	(1184, 1, 7, 54, 2, 100.00, 3, 3),
	(1185, 1, 7, 68, 2, 100.00, 5, 3),
	(1186, 1, 9, 8, 2, 100.00, 5, 3),
	(1187, 1, 9, 24, 2, 100.00, 5, 3),
	(1188, 1, 10, 3, 2, 95.00, 1, 3),
	(1189, 1, 10, 3, 2, 100.00, 3, 3),
	(1190, 1, 10, 3, 2, 102.00, 1, 3),
	(1191, 1, 10, 3, 2, 103.00, 1, 3),
	(1192, 1, 10, 4, 2, 100.00, 3, 3),
	(1193, 1, 10, 5, 2, 95.00, 1, 3),
	(1194, 1, 10, 5, 2, 100.00, 2, 3),
	(1195, 1, 10, 41, 2, 100.00, 3, 3),
	(1196, 1, 10, 64, 2, 100.00, 3, 3),
	(1197, 1, 12, 3, 2, 100.00, 105, 3),
	(1198, 1, 12, 12, 2, 100.00, 30, 3),
	(1199, 1, 12, 39, 2, 100.00, 10, 3),
	(1200, 1, 12, 54, 2, 100.00, 10, 3),
	(1201, 1, 13, 3, 2, 100.00, 100, 3),
	(1202, 1, 13, 4, 2, 100.00, 10, 3),
	(1203, 1, 13, 7, 2, 100.00, 51, 3),
	(1204, 1, 13, 9, 2, 100.00, 3, 3),
	(1205, 1, 13, 12, 2, 99.00, 1, 3),
	(1206, 1, 13, 12, 2, 100.00, 1, 3),
	(1207, 1, 13, 12, 2, 102.00, 1, 3),
	(1208, 1, 13, 12, 2, 104.00, 1, 3),
	(1209, 1, 13, 12, 2, 107.00, 1, 3),
	(1210, 1, 13, 13, 2, 100.00, 5, 3),
	(1211, 1, 13, 22, 2, 100.00, 5, 3),
	(1212, 1, 13, 30, 2, 100.00, 5, 3),
	(1213, 1, 13, 41, 2, 100.00, 5, 3),
	(1214, 1, 13, 60, 2, 100.00, 5, 3),
	(1215, 1, 14, 3, 2, 100.00, 5, 3),
	(1216, 1, 14, 4, 2, 100.00, 5, 3),
	(1217, 1, 14, 5, 2, 100.00, 5, 3),
	(1218, 1, 14, 9, 2, 100.00, 5, 3),
	(1219, 1, 14, 12, 2, 100.00, 5, 3),
	(1220, 1, 14, 56, 2, 100.00, 5, 3),
	(1221, 1, 15, 3, 2, 100.00, 155, 3),
	(1222, 1, 18, 9, 2, 100.00, 4, 3),
	(1223, 1, 19, 24, 2, 100.00, 5, 3),
	(1224, 1, 26, 7, 2, 87.00, 1, 3),
	(1225, 1, 26, 7, 2, 92.00, 1, 3),
	(1226, 1, 26, 7, 2, 93.00, 1, 3),
	(1227, 1, 26, 7, 2, 94.00, 2, 3),
	(1228, 1, 26, 7, 2, 95.00, 1, 3),
	(1229, 1, 26, 7, 2, 100.00, 3, 3),
	(1230, 1, 26, 7, 2, 102.00, 1, 3),
	(1231, 1, 27, 3, 2, 100.00, 3, 3),
	(1232, 1, 27, 5, 2, 100.00, 5, 3),
	(1233, 1, 27, 56, 2, 100.00, 2, 3),
	(1234, 1, 27, 70, 2, 100.00, 3, 3),
	(1235, 1, 28, 8, 2, 100.00, 4, 3),
	(1280, 1, 1, 3, 1, 100.00, 3, 4),
	(1281, 1, 1, 4, 1, 100.00, 3, 4),
	(1282, 1, 1, 9, 1, 100.00, 3, 4),
	(1283, 1, 1, 16, 1, 100.00, 3, 4),
	(1284, 1, 1, 32, 1, 100.00, 3, 4),
	(1285, 1, 1, 36, 1, 100.00, 3, 4),
	(1286, 1, 1, 43, 1, 100.00, 3, 4),
	(1287, 1, 1, 46, 1, 100.00, 3, 4),
	(1288, 1, 1, 73, 1, 100.00, 3, 4),
	(1289, 1, 1, 82, 1, 97.00, 1, 4),
	(1290, 1, 1, 82, 1, 100.00, 2, 4),
	(1291, 1, 1, 83, 1, 100.00, 3, 4),
	(1292, 1, 4, 3, 1, 100.00, 8, 4),
	(1293, 1, 4, 4, 1, 100.00, 3, 4),
	(1294, 1, 4, 9, 1, 97.00, 1, 4),
	(1295, 1, 4, 9, 1, 100.00, 2, 4),
	(1296, 1, 5, 13, 1, 99.00, 1, 4),
	(1297, 1, 5, 13, 1, 100.00, 3, 4),
	(1298, 1, 6, 4, 1, 100.00, 10, 4),
	(1299, 1, 6, 12, 1, 65.00, 1, 4),
	(1300, 1, 6, 12, 1, 97.00, 1, 4),
	(1301, 1, 6, 12, 1, 102.00, 2, 4),
	(1302, 1, 6, 12, 1, 103.00, 1, 4),
	(1303, 1, 6, 13, 1, 100.00, 5, 4),
	(1304, 1, 6, 15, 1, 100.00, 5, 4),
	(1305, 1, 6, 22, 1, 100.00, 3, 4),
	(1306, 1, 6, 41, 1, 100.00, 5, 4),
	(1307, 1, 6, 47, 1, 100.00, 15, 4),
	(1308, 1, 7, 3, 1, 100.00, 35, 4),
	(1309, 1, 7, 4, 1, 100.00, 8, 4),
	(1310, 1, 7, 12, 1, 100.00, 3, 4),
	(1311, 1, 7, 54, 1, 100.00, 3, 4),
	(1312, 1, 7, 68, 1, 100.00, 5, 4),
	(1313, 1, 9, 8, 1, 100.00, 5, 4),
	(1314, 1, 9, 24, 1, 100.00, 5, 4),
	(1315, 1, 10, 3, 1, 95.00, 1, 4),
	(1316, 1, 10, 3, 1, 100.00, 3, 4),
	(1317, 1, 10, 3, 1, 102.00, 1, 4),
	(1318, 1, 10, 3, 1, 103.00, 1, 4),
	(1319, 1, 10, 4, 1, 100.00, 3, 4),
	(1320, 1, 10, 5, 1, 95.00, 1, 4),
	(1321, 1, 10, 5, 1, 100.00, 2, 4),
	(1322, 1, 10, 41, 1, 100.00, 3, 4),
	(1323, 1, 10, 64, 1, 100.00, 3, 4),
	(1324, 1, 12, 3, 1, 100.00, 105, 4),
	(1325, 1, 12, 12, 1, 100.00, 30, 4),
	(1326, 1, 12, 39, 1, 100.00, 10, 4),
	(1327, 1, 12, 54, 1, 100.00, 10, 4),
	(1328, 1, 13, 3, 1, 100.00, 100, 4),
	(1329, 1, 13, 4, 1, 100.00, 10, 4),
	(1330, 1, 13, 7, 1, 100.00, 51, 4),
	(1331, 1, 13, 9, 1, 100.00, 3, 4),
	(1332, 1, 13, 12, 1, 99.00, 1, 4),
	(1333, 1, 13, 12, 1, 100.00, 1, 4),
	(1334, 1, 13, 12, 1, 102.00, 1, 4),
	(1335, 1, 13, 12, 1, 104.00, 1, 4),
	(1336, 1, 13, 12, 1, 107.00, 1, 4),
	(1337, 1, 13, 13, 1, 100.00, 5, 4),
	(1338, 1, 13, 22, 1, 100.00, 5, 4),
	(1339, 1, 13, 30, 1, 100.00, 5, 4),
	(1340, 1, 13, 41, 1, 100.00, 5, 4),
	(1341, 1, 13, 60, 1, 100.00, 5, 4),
	(1342, 1, 14, 3, 1, 100.00, 5, 4),
	(1343, 1, 14, 4, 1, 100.00, 5, 4),
	(1344, 1, 14, 5, 1, 100.00, 5, 4),
	(1345, 1, 14, 9, 1, 100.00, 5, 4),
	(1346, 1, 14, 12, 1, 100.00, 5, 4),
	(1347, 1, 14, 56, 1, 100.00, 5, 4),
	(1348, 1, 15, 3, 1, 100.00, 155, 4),
	(1349, 1, 18, 9, 1, 100.00, 4, 4),
	(1350, 1, 19, 24, 1, 100.00, 5, 4),
	(1351, 1, 26, 7, 1, 87.00, 1, 4),
	(1352, 1, 26, 7, 1, 92.00, 1, 4),
	(1353, 1, 26, 7, 1, 93.00, 1, 4),
	(1354, 1, 26, 7, 1, 94.00, 2, 4),
	(1355, 1, 26, 7, 1, 95.00, 1, 4),
	(1356, 1, 26, 7, 1, 100.00, 3, 4),
	(1357, 1, 26, 7, 1, 102.00, 1, 4),
	(1358, 1, 27, 3, 1, 100.00, 3, 4),
	(1359, 1, 27, 5, 1, 100.00, 5, 4),
	(1360, 1, 27, 56, 1, 100.00, 2, 4),
	(1361, 1, 27, 70, 1, 100.00, 3, 4),
	(1362, 1, 28, 8, 1, 100.00, 4, 4),
	(1407, 1, 1, 71, 2, 100.00, 1, 4),
	(1408, 1, 27, 9, 2, 98.00, 1, 4),
	(1409, 1, 1, 32, 1, 100.00, 23, 1),
	(1410, 1, 1, 32, 1, 102.00, 1, 1),
	(1411, 1, 1, 32, 1, 133.00, 1, 1),
	(1412, 1, 1, 41, 1, 90.00, 1, 1),
	(1413, 1, 1, 41, 1, 91.00, 1, 1),
	(1414, 1, 1, 41, 1, 92.00, 1, 1),
	(1415, 1, 1, 41, 1, 95.00, 1, 1),
	(1416, 1, 1, 41, 1, 96.00, 1, 1),
	(1417, 1, 1, 41, 1, 97.00, 1, 1),
	(1418, 1, 1, 41, 1, 100.00, 25, 1),
	(1419, 1, 1, 54, 1, 77.00, 1, 1),
	(1420, 1, 1, 54, 1, 100.00, 24, 1),
	(1421, 1, 1, 54, 1, 103.00, 1, 1),
	(1422, 1, 1, 71, 1, 72.00, 1, 1),
	(1423, 1, 1, 71, 1, 80.00, 1, 1),
	(1424, 1, 1, 71, 1, 94.00, 1, 1),
	(1425, 1, 1, 71, 1, 99.00, 1, 1),
	(1426, 1, 1, 71, 1, 100.00, 23, 1),
	(1429, 1, 1, 79, 1, 74.00, 1, 1),
	(1430, 1, 1, 79, 1, 89.00, 1, 1),
	(1431, 1, 1, 79, 1, 100.00, 46, 1),
	(1432, 1, 1, 79, 1, 101.00, 1, 1),
	(1433, 1, 1, 79, 1, 111.00, 1, 1),
	(1436, 1, 13, 2, 1, 75.00, 1, 1),
	(1437, 1, 13, 2, 1, 95.00, 1, 1),
	(1438, 1, 13, 2, 1, 97.00, 2, 1),
	(1439, 1, 13, 2, 1, 98.00, 1, 1),
	(1440, 1, 13, 2, 1, 100.00, 23, 1),
	(1443, 1, 13, 12, 1, 74.00, 1, 1),
	(1444, 1, 13, 12, 1, 90.00, 1, 1),
	(1445, 1, 13, 12, 1, 96.00, 1, 1),
	(1446, 1, 13, 12, 1, 100.00, 49, 1),
	(1447, 1, 13, 12, 1, 106.00, 1, 1),
	(1450, 1, 13, 41, 1, 56.00, 1, 1),
	(1451, 1, 13, 41, 1, 81.00, 1, 1),
	(1452, 1, 13, 41, 1, 92.00, 1, 1),
	(1453, 1, 13, 41, 1, 93.00, 1, 1),
	(1454, 1, 13, 41, 1, 97.00, 1, 1),
	(1455, 1, 13, 41, 1, 100.00, 44, 1),
	(1456, 1, 13, 41, 1, 104.00, 1, 1),
	(1457, 1, 13, 41, 1, 111.00, 1, 1),
	(1465, 1, 13, 50, 1, 80.00, 1, 1),
	(1466, 1, 13, 50, 1, 96.00, 1, 1),
	(1467, 1, 13, 50, 1, 98.00, 2, 1),
	(1468, 1, 13, 50, 1, 100.00, 47, 1),
	(1469, 1, 13, 50, 1, 110.00, 1, 1),
	(1470, 1, 13, 50, 1, 115.00, 1, 1),
	(1472, 1, 19, 8, 1, 70.00, 1, 1),
	(1473, 1, 19, 8, 1, 74.00, 1, 1),
	(1474, 1, 19, 8, 1, 88.00, 1, 1),
	(1475, 1, 19, 8, 1, 89.00, 1, 1),
	(1476, 1, 19, 8, 1, 90.00, 2, 1),
	(1477, 1, 19, 8, 1, 94.00, 2, 1),
	(1478, 1, 19, 8, 1, 98.00, 1, 1),
	(1479, 1, 19, 8, 1, 100.00, 63, 1),
	(1480, 1, 19, 8, 1, 102.00, 1, 1),
	(1481, 1, 19, 8, 1, 108.00, 1, 1),
	(1482, 1, 19, 8, 1, 119.00, 1, 1),
	(1487, 1, 27, 7, 1, 80.00, 1, 1),
	(1488, 1, 27, 7, 1, 85.00, 1, 1),
	(1489, 1, 27, 7, 1, 98.00, 1, 1),
	(1490, 1, 27, 7, 1, 100.00, 40, 1),
	(1494, 1, 27, 15, 1, 50.00, 1, 1),
	(1495, 1, 27, 15, 1, 59.00, 1, 1),
	(1496, 1, 27, 15, 1, 66.00, 1, 1),
	(1497, 1, 27, 15, 1, 82.00, 1, 1),
	(1498, 1, 27, 15, 1, 90.00, 1, 1),
	(1499, 1, 27, 15, 1, 91.00, 1, 1),
	(1500, 1, 27, 15, 1, 98.00, 1, 1),
	(1501, 1, 27, 15, 1, 100.00, 13, 1),
	(1502, 1, 27, 15, 1, 146.00, 1, 1),
	(1509, 1, 28, 8, 1, 69.00, 1, 1),
	(1510, 1, 28, 8, 1, 90.00, 1, 1),
	(1511, 1, 28, 8, 1, 91.00, 1, 1),
	(1512, 1, 28, 8, 1, 92.00, 1, 1),
	(1513, 1, 28, 8, 1, 98.00, 1, 1),
	(1514, 1, 28, 8, 1, 100.00, 48, 1),
	(1516, 1, 1, 36, 1, 89.00, 1, 1),
	(1517, 1, 1, 36, 1, 100.00, 28, 1),
	(1518, 1, 1, 36, 1, 113.00, 1, 1),
	(1522, 1, 1, 77, 1, 63.00, 1, 1),
	(1523, 1, 1, 77, 1, 83.00, 1, 1),
	(1524, 1, 1, 77, 1, 100.00, 24, 1),
	(1542, 1, 1, 46, 1, 96.00, 1, 1),
	(1543, 1, 1, 46, 1, 100.00, 24, 1),
	(1544, 1, 1, 46, 1, 138.00, 1, 1),
	(1552, 1, 10, 4, 1, 74.00, 1, 1),
	(1553, 1, 10, 4, 1, 78.00, 1, 1),
	(1554, 1, 10, 4, 1, 79.00, 2, 1),
	(1555, 1, 10, 4, 1, 84.00, 1, 1),
	(1556, 1, 10, 4, 1, 91.00, 1, 1),
	(1557, 1, 10, 4, 1, 94.00, 1, 1),
	(1558, 1, 10, 4, 1, 96.00, 1, 1),
	(1559, 1, 10, 4, 1, 97.00, 2, 1),
	(1560, 1, 10, 4, 1, 98.00, 2, 1),
	(1561, 1, 10, 4, 1, 100.00, 91, 1),
	(1562, 1, 10, 4, 1, 103.00, 2, 1),
	(1567, 1, 10, 81, 1, 88.00, 1, 1),
	(1568, 1, 10, 81, 1, 100.00, 25, 1),
	(1577, 1, 7, 3, 1, 78.00, 1, 1),
	(1578, 1, 7, 3, 1, 82.00, 1, 1),
	(1579, 1, 7, 3, 1, 86.00, 1, 1),
	(1580, 1, 7, 3, 1, 95.00, 1, 1),
	(1581, 1, 7, 3, 1, 98.00, 2, 1),
	(1582, 1, 7, 3, 1, 100.00, 92, 1),
	(1583, 1, 7, 3, 1, 114.00, 1, 1),
	(1647, 1, 15, 3, 1, 53.00, 1, 1),
	(1648, 1, 15, 3, 1, 60.00, 1, 1),
	(1649, 1, 15, 3, 1, 61.00, 1, 1),
	(1650, 1, 15, 3, 1, 70.00, 1, 1),
	(1651, 1, 15, 3, 1, 71.00, 1, 1),
	(1652, 1, 15, 3, 1, 72.00, 1, 1),
	(1653, 1, 15, 3, 1, 80.00, 1, 1),
	(1654, 1, 15, 3, 1, 83.00, 1, 1),
	(1655, 1, 15, 3, 1, 88.00, 3, 1),
	(1656, 1, 15, 3, 1, 90.00, 2, 1),
	(1657, 1, 15, 3, 1, 93.00, 1, 1),
	(1658, 1, 15, 3, 1, 94.00, 2, 1),
	(1659, 1, 15, 3, 1, 96.00, 1, 1),
	(1660, 1, 15, 3, 1, 97.00, 2, 1),
	(1661, 1, 15, 3, 1, 98.00, 2, 1),
	(1662, 1, 15, 3, 1, 99.00, 1, 1),
	(1663, 1, 15, 3, 1, 100.00, 332, 1),
	(1664, 1, 15, 3, 1, 101.00, 1, 1),
	(1665, 1, 15, 3, 1, 102.00, 1, 1),
	(1666, 1, 15, 3, 1, 103.00, 1, 1),
	(1667, 1, 15, 3, 1, 105.00, 1, 1),
	(1668, 1, 15, 3, 1, 106.00, 3, 1),
	(1669, 1, 15, 3, 1, 108.00, 1, 1),
	(1670, 1, 15, 3, 1, 109.00, 2, 1),
	(1671, 1, 15, 3, 1, 112.00, 1, 1),
	(1672, 1, 13, 3, 1, 51.00, 1, 1),
	(1673, 1, 13, 3, 1, 52.00, 1, 1),
	(1674, 1, 13, 3, 1, 54.00, 1, 1),
	(1675, 1, 13, 3, 1, 55.00, 2, 1),
	(1676, 1, 13, 3, 1, 56.00, 1, 1),
	(1677, 1, 13, 3, 1, 59.00, 1, 1),
	(1678, 1, 13, 3, 1, 60.00, 1, 1),
	(1679, 1, 13, 3, 1, 61.00, 1, 1),
	(1680, 1, 13, 3, 1, 63.00, 2, 1),
	(1681, 1, 13, 3, 1, 65.00, 1, 1),
	(1682, 1, 13, 3, 1, 66.00, 2, 1),
	(1683, 1, 13, 3, 1, 67.00, 2, 1),
	(1684, 1, 13, 3, 1, 69.00, 1, 1),
	(1685, 1, 13, 3, 1, 70.00, 1, 1),
	(1686, 1, 13, 3, 1, 71.00, 3, 1),
	(1687, 1, 13, 3, 1, 73.00, 1, 1),
	(1688, 1, 13, 3, 1, 75.00, 2, 1),
	(1689, 1, 13, 3, 1, 76.00, 3, 1),
	(1690, 1, 13, 3, 1, 77.00, 2, 1),
	(1691, 1, 13, 3, 1, 78.00, 2, 1),
	(1692, 1, 13, 3, 1, 79.00, 1, 1),
	(1693, 1, 13, 3, 1, 80.00, 6, 1),
	(1694, 1, 13, 3, 1, 81.00, 2, 1),
	(1695, 1, 13, 3, 1, 82.00, 5, 1),
	(1696, 1, 13, 3, 1, 83.00, 3, 1),
	(1697, 1, 13, 3, 1, 84.00, 4, 1),
	(1698, 1, 13, 3, 1, 85.00, 2, 1),
	(1699, 1, 13, 3, 1, 86.00, 2, 1),
	(1700, 1, 13, 3, 1, 87.00, 3, 1),
	(1701, 1, 13, 3, 1, 88.00, 4, 1),
	(1702, 1, 13, 3, 1, 89.00, 3, 1),
	(1703, 1, 13, 3, 1, 90.00, 5, 1),
	(1704, 1, 13, 3, 1, 91.00, 5, 1),
	(1705, 1, 13, 3, 1, 92.00, 6, 1),
	(1706, 1, 13, 3, 1, 93.00, 7, 1),
	(1707, 1, 13, 3, 1, 94.00, 4, 1),
	(1708, 1, 13, 3, 1, 95.00, 10, 1),
	(1709, 1, 13, 3, 1, 96.00, 8, 1),
	(1710, 1, 13, 3, 1, 97.00, 9, 1),
	(1711, 1, 13, 3, 1, 98.00, 9, 1),
	(1712, 1, 13, 3, 1, 99.00, 4, 1),
	(1713, 1, 13, 3, 1, 100.00, 1142, 1),
	(1714, 1, 13, 3, 1, 101.00, 3, 1),
	(1715, 1, 13, 3, 1, 102.00, 5, 1),
	(1716, 1, 13, 3, 1, 103.00, 8, 1),
	(1717, 1, 13, 3, 1, 104.00, 1, 1),
	(1718, 1, 13, 3, 1, 105.00, 1, 1),
	(1719, 1, 13, 3, 1, 110.00, 3, 1),
	(1720, 1, 13, 3, 1, 123.00, 1, 1),
	(1721, 1, 13, 3, 1, 124.00, 2, 1),
	(1722, 1, 13, 3, 1, 127.00, 1, 1),
	(1723, 1, 13, 3, 1, 129.00, 1, 1),
	(1724, 1, 13, 3, 1, 132.00, 1, 1);
/*!40000 ALTER TABLE `nd_stok_opname_transaksi` ENABLE KEYS */;

-- Dumping structure for table favourtdj_system.nd_supplier
DROP TABLE IF EXISTS `nd_supplier`;
CREATE TABLE IF NOT EXISTS `nd_supplier` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kode` varchar(2) DEFAULT NULL,
  `nama` varchar(70) DEFAULT NULL,
  `alamat` varchar(100) DEFAULT NULL,
  `telepon` varchar(50) DEFAULT NULL,
  `kota` varchar(100) DEFAULT NULL,
  `fax` varchar(50) DEFAULT NULL,
  `kode_pos` varchar(20) DEFAULT NULL,
  `nama_bank` varchar(200) DEFAULT NULL,
  `no_rek_bank` varchar(200) DEFAULT NULL,
  `email` varchar(70) DEFAULT NULL,
  `website` varchar(50) DEFAULT NULL,
  `status_aktif` int(1) NOT NULL DEFAULT '1',
  `faktur_inisial` varchar(2) DEFAULT NULL,
  `tipe_supplier` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

-- Dumping data for table favourtdj_system.nd_supplier: ~7 rows (approximately)
DELETE FROM `nd_supplier`;
/*!40000 ALTER TABLE `nd_supplier` DISABLE KEYS */;
INSERT INTO `nd_supplier` (`id`, `kode`, `nama`, `alamat`, `telepon`, `kota`, `fax`, `kode_pos`, `nama_bank`, `no_rek_bank`, `email`, `website`, `status_aktif`, `faktur_inisial`, `tipe_supplier`) VALUES
	(1, '11', 'KAHATEX, PT. ( CIJERAH )', 'JL. CIJERAH CIGONDEWAH GIRANG 16, RT 001 RW 032', '', 'CIMAHI, JAWA BARAT', '', '', 'BCA', '0083041631', '', '', 1, 'Y', 1),
	(2, '22', 'KAHATEX, PT. (RANCAEKEK)', 'JL. CIJERAH CIGONDEWAH GIRANG 16, RT 001 RW 032', '022 - 7792221', 'CIMAHI', '', '', 'BCA', '0083041631', '', '', 0, 'N', 1),
	(3, '33', 'ADMIRALINDO BINTANG TERANG, PT', 'JL. INDUSTRI IV, NO 11 CIBALIGO', '022-6035649', 'CIMAHI', '', '', '', '', '', '', 1, NULL, 1),
	(4, '-', 'MAHAKARYA', '-', '-', '-', '-', '-', '-', '-', '-', '-', 0, NULL, 1),
	(5, '1', 'MAHAKARYA', '-', '-', '-', '-', '-', '-', '-', '-', '-', 1, NULL, 1),
	(6, 'ok', 'oke', '-', '-', '-', '-', '-', '-', '-', '-', '-', 0, NULL, 1),
	(7, 'OK', 'OKE', '-', '-', '-', '-', '-', '-', '-', '-', '-', 0, NULL, 1),
	(8, '', 'askdjsadkj', '', '', '', '', '', '', '', '', '', 1, NULL, 1);
/*!40000 ALTER TABLE `nd_supplier` ENABLE KEYS */;

-- Dumping structure for table favourtdj_system.nd_toko
DROP TABLE IF EXISTS `nd_toko`;
CREATE TABLE IF NOT EXISTS `nd_toko` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(70) DEFAULT NULL,
  `alamat` varchar(100) DEFAULT NULL,
  `telepon` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `fax` varchar(100) DEFAULT NULL,
  `kota` varchar(20) DEFAULT NULL,
  `kode_pos` varchar(20) DEFAULT NULL,
  `NPWP` varchar(100) DEFAULT NULL,
  `pre_faktur` varchar(2) DEFAULT NULL,
  `pre_po` varchar(2) DEFAULT NULL,
  `status_aktif` int(1) NOT NULL DEFAULT '1',
  `host` varchar(100) DEFAULT NULL,
  `relay_mail` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- Dumping data for table favourtdj_system.nd_toko: ~1 rows (approximately)
DELETE FROM `nd_toko`;
/*!40000 ALTER TABLE `nd_toko` DISABLE KEYS */;
INSERT INTO `nd_toko` (`id`, `nama`, `alamat`, `telepon`, `email`, `fax`, `kota`, `kode_pos`, `NPWP`, `pre_faktur`, `pre_po`, `status_aktif`, `host`, `relay_mail`) VALUES
	(1, 'CV. PELITA LESTARI', 'JL. TAMIM 60', '022 - 4238965', 'pelita.lestari@outlook.com', '022 - 87805063', 'BANDUNG', '', '21.146.537.2-428.000', '25', 'PL', 1, 'sistem.blessingtdj.com', 'pajak.pelitalestari@gmail.com');
/*!40000 ALTER TABLE `nd_toko` ENABLE KEYS */;

-- Dumping structure for table favourtdj_system.nd_tutup_buku
DROP TABLE IF EXISTS `nd_tutup_buku`;
CREATE TABLE IF NOT EXISTS `nd_tutup_buku` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tanggal` date DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `updated` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=latin1;

-- Dumping data for table favourtdj_system.nd_tutup_buku: ~43 rows (approximately)
DELETE FROM `nd_tutup_buku`;
/*!40000 ALTER TABLE `nd_tutup_buku` DISABLE KEYS */;
INSERT INTO `nd_tutup_buku` (`id`, `tanggal`, `user_id`, `updated`) VALUES
	(1, '2018-12-31', 1, '2019-07-11 08:26:31'),
	(2, '2019-01-31', 1, '2019-07-11 08:30:15'),
	(3, '2019-02-28', 19, '2019-07-12 03:03:27'),
	(4, '2019-03-31', 19, '2019-09-30 03:29:03'),
	(5, '2019-04-30', 19, '2019-09-30 03:29:41'),
	(6, '2019-05-31', 19, '2019-09-30 03:29:56'),
	(7, '2019-06-30', 19, '2019-09-30 03:30:35'),
	(8, '2019-07-31', 19, '2019-09-30 03:30:51'),
	(9, '2019-08-31', 19, '2019-09-30 03:31:06'),
	(10, '2019-01-31', 1, '2019-10-09 08:19:46'),
	(11, '2019-02-28', 1, '2019-10-09 08:24:48'),
	(12, '2019-03-31', 1, '2019-10-09 08:33:24'),
	(13, '2019-04-30', 1, '2019-10-09 08:38:50'),
	(14, '2019-05-31', 1, '2019-10-09 08:45:23'),
	(15, '2019-06-30', 1, '2019-10-09 08:48:33'),
	(16, '2019-07-31', 1, '2019-10-09 08:56:16'),
	(17, '2019-08-31', 1, '2019-10-09 09:06:57'),
	(18, '2019-01-31', 19, '2019-11-11 03:37:44'),
	(19, '2019-02-28', 19, '2019-11-11 03:38:13'),
	(20, '2019-01-31', 1, '2019-11-11 04:36:43'),
	(21, '2019-02-28', 19, '2019-11-12 04:34:28'),
	(22, '2019-03-31', 19, '2019-11-14 08:41:55'),
	(23, '2019-04-30', 19, '2019-11-26 07:41:04'),
	(24, '2019-05-31', 19, '2019-11-28 06:57:50'),
	(25, '2019-06-30', 19, '2019-11-29 08:14:47'),
	(26, '2019-07-31', 19, '2019-12-03 07:29:00'),
	(27, '2019-08-31', 19, '2019-12-03 08:33:40'),
	(28, '2019-09-30', 19, '2019-12-05 04:00:52'),
	(29, '2019-10-31', 19, '2019-12-07 04:38:02'),
	(30, '2019-11-30', 19, '2020-01-16 02:39:11'),
	(31, '2019-12-31', 19, '2020-01-16 08:47:57'),
	(32, '2020-01-31', 19, '2021-03-23 07:50:33'),
	(33, '2020-02-29', 19, '2021-03-23 07:50:58'),
	(34, '2020-03-31', 19, '2021-03-23 07:51:24'),
	(35, '2020-04-30', 19, '2021-03-23 07:51:48'),
	(36, '2020-05-31', 19, '2021-03-23 07:52:16'),
	(37, '2020-06-30', 19, '2021-03-23 07:56:06'),
	(38, '2020-07-31', 19, '2021-03-23 07:56:44'),
	(39, '2020-08-31', 19, '2021-03-23 07:57:11'),
	(40, '2020-09-30', 19, '2021-03-23 07:57:47'),
	(41, '2020-10-31', 19, '2021-03-23 07:58:09'),
	(42, '2020-11-30', 19, '2021-03-23 07:58:30'),
	(43, '2020-12-31', 19, '2021-03-23 07:58:48');
/*!40000 ALTER TABLE `nd_tutup_buku` ENABLE KEYS */;

-- Dumping structure for table favourtdj_system.nd_tutup_buku_detail
DROP TABLE IF EXISTS `nd_tutup_buku_detail`;
CREATE TABLE IF NOT EXISTS `nd_tutup_buku_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tahun` date DEFAULT NULL,
  `barang_id` smallint(6) DEFAULT NULL,
  `warna_id` smallint(6) DEFAULT NULL,
  `01_harga` decimal(15,2) DEFAULT NULL,
  `02_harga` decimal(15,2) DEFAULT NULL,
  `03_harga` decimal(15,2) DEFAULT NULL,
  `04_harga` decimal(15,2) DEFAULT NULL,
  `05_harga` decimal(15,2) DEFAULT NULL,
  `06_harga` decimal(15,2) DEFAULT NULL,
  `07_harga` decimal(15,2) DEFAULT NULL,
  `08_harga` decimal(15,2) DEFAULT NULL,
  `09_harga` decimal(15,2) DEFAULT NULL,
  `10_harga` decimal(15,2) DEFAULT NULL,
  `11_harga` decimal(15,2) DEFAULT NULL,
  `12_harga` decimal(15,2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=859 DEFAULT CHARSET=latin1;

-- Dumping data for table favourtdj_system.nd_tutup_buku_detail: ~768 rows (approximately)
DELETE FROM `nd_tutup_buku_detail`;
/*!40000 ALTER TABLE `nd_tutup_buku_detail` DISABLE KEYS */;
INSERT INTO `nd_tutup_buku_detail` (`id`, `tahun`, `barang_id`, `warna_id`, `01_harga`, `02_harga`, `03_harga`, `04_harga`, `05_harga`, `06_harga`, `07_harga`, `08_harga`, `09_harga`, `10_harga`, `11_harga`, `12_harga`) VALUES
	(1, '2018-12-31', 1, 30, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(2, '2018-12-31', 1, 16, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(3, '2018-12-31', 1, 45, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(4, '2018-12-31', 1, 14, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(5, '2018-12-31', 1, 12, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(6, '2018-12-31', 1, 21, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(7, '2018-12-31', 1, 72, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(8, '2018-12-31', 1, 31, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(9, '2018-12-31', 1, 36, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(10, '2018-12-31', 1, 19, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(11, '2018-12-31', 1, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(12, '2018-12-31', 1, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(13, '2018-12-31', 1, 35, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(14, '2018-12-31', 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(15, '2018-12-31', 1, 18, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(16, '2018-12-31', 1, 46, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(17, '2018-12-31', 1, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(18, '2018-12-31', 1, 37, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(19, '2018-12-31', 1, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(20, '2018-12-31', 1, 43, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(21, '2018-12-31', 1, 41, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(22, '2018-12-31', 1, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(23, '2018-12-31', 1, 32, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(24, '2018-12-31', 1, 67, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(25, '2018-12-31', 1, 44, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(26, '2018-12-31', 1, 17, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(27, '2018-12-31', 1, 20, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(28, '2018-12-31', 1, 10, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(29, '2018-12-31', 1, 13, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(30, '2018-12-31', 1, 42, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(31, '2018-12-31', 1, 15, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(32, '2018-12-31', 1, 33, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(33, '2018-12-31', 1, 71, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(34, '2018-12-31', 1, 39, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(35, '2018-12-31', 3, 30, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(36, '2018-12-31', 3, 16, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(37, '2018-12-31', 3, 54, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(38, '2018-12-31', 3, 12, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(39, '2018-12-31', 3, 21, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(40, '2018-12-31', 3, 31, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(41, '2018-12-31', 3, 47, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(42, '2018-12-31', 3, 59, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(43, '2018-12-31', 3, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(44, '2018-12-31', 3, 61, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(45, '2018-12-31', 3, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(46, '2018-12-31', 3, 64, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(47, '2018-12-31', 3, 22, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(48, '2018-12-31', 3, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(49, '2018-12-31', 3, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(50, '2018-12-31', 3, 41, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(51, '2018-12-31', 3, 58, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(52, '2018-12-31', 3, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(53, '2018-12-31', 3, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(54, '2018-12-31', 3, 10, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(55, '2018-12-31', 3, 13, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(56, '2018-12-31', 3, 42, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(57, '2018-12-31', 3, 15, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(58, '2018-12-31', 3, 39, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(59, '2018-12-31', 15, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(60, '2018-12-31', 12, 54, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(61, '2018-12-31', 12, 12, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(62, '2018-12-31', 12, 31, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(63, '2018-12-31', 12, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(64, '2018-12-31', 12, 39, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(65, '2018-12-31', 13, 16, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(66, '2018-12-31', 13, 12, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(67, '2018-12-31', 13, 47, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(68, '2018-12-31', 13, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(69, '2018-12-31', 13, 61, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(70, '2018-12-31', 13, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(71, '2018-12-31', 13, 22, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(72, '2018-12-31', 13, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(73, '2018-12-31', 13, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(74, '2018-12-31', 13, 41, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(75, '2018-12-31', 13, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(76, '2018-12-31', 13, 60, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(77, '2018-12-31', 13, 13, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(78, '2018-12-31', 13, 39, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(79, '2018-12-31', 16, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(80, '2018-12-31', 16, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(81, '2018-12-31', 16, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(82, '2018-12-31', 18, 54, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(83, '2018-12-31', 18, 65, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(84, '2018-12-31', 18, 63, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(85, '2018-12-31', 18, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(86, '2018-12-31', 18, 52, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(87, '2018-12-31', 18, 64, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(88, '2018-12-31', 18, 22, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(89, '2018-12-31', 18, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(90, '2018-12-31', 18, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(91, '2018-12-31', 18, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(92, '2018-12-31', 18, 41, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(93, '2018-12-31', 18, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(94, '2018-12-31', 7, 54, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(95, '2018-12-31', 7, 12, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(96, '2018-12-31', 7, 31, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(97, '2018-12-31', 7, 47, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(98, '2018-12-31', 7, 66, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(99, '2018-12-31', 7, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(100, '2018-12-31', 7, 52, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(101, '2018-12-31', 7, 61, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(102, '2018-12-31', 7, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(103, '2018-12-31', 7, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(104, '2018-12-31', 7, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(105, '2018-12-31', 7, 41, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(106, '2018-12-31', 7, 68, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(107, '2018-12-31', 7, 58, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(108, '2018-12-31', 7, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(109, '2018-12-31', 7, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(110, '2018-12-31', 7, 13, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(111, '2018-12-31', 7, 15, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(112, '2018-12-31', 7, 39, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(113, '2018-12-31', 19, 24, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(114, '2018-12-31', 19, 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(115, '2018-12-31', 8, 50, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(116, '2018-12-31', 8, 30, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(117, '2018-12-31', 8, 54, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(118, '2018-12-31', 8, 51, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(119, '2018-12-31', 8, 12, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(120, '2018-12-31', 8, 21, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(121, '2018-12-31', 8, 57, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(122, '2018-12-31', 8, 65, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(123, '2018-12-31', 8, 53, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(124, '2018-12-31', 8, 73, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(125, '2018-12-31', 8, 56, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(126, '2018-12-31', 8, 47, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(127, '2018-12-31', 8, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(128, '2018-12-31', 8, 52, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(129, '2018-12-31', 8, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(130, '2018-12-31', 8, 64, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(131, '2018-12-31', 8, 22, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(132, '2018-12-31', 8, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(133, '2018-12-31', 8, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(134, '2018-12-31', 8, 48, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(135, '2018-12-31', 8, 41, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(136, '2018-12-31', 8, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(137, '2018-12-31', 8, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(138, '2018-12-31', 8, 55, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(139, '2018-12-31', 8, 13, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(140, '2018-12-31', 14, 12, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(141, '2018-12-31', 14, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(142, '2018-12-31', 14, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(143, '2018-12-31', 14, 22, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(144, '2018-12-31', 14, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(145, '2018-12-31', 14, 15, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(146, '2018-12-31', 20, 12, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(147, '2018-12-31', 20, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(148, '2018-12-31', 21, 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(149, '2018-12-31', 17, 30, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(150, '2018-12-31', 17, 12, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(151, '2018-12-31', 17, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(152, '2018-12-31', 17, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(153, '2018-12-31', 17, 41, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(154, '2018-12-31', 17, 13, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(155, '2018-12-31', 10, 12, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(156, '2018-12-31', 10, 63, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(157, '2018-12-31', 10, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(158, '2018-12-31', 10, 64, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(159, '2018-12-31', 10, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(160, '2018-12-31', 10, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(161, '2018-12-31', 10, 48, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(162, '2018-12-31', 10, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(163, '2018-12-31', 10, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(164, '2018-12-31', 10, 15, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(165, '2018-12-31', 2, 30, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(166, '2018-12-31', 2, 12, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(167, '2018-12-31', 2, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(168, '2018-12-31', 2, 64, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(169, '2018-12-31', 2, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(170, '2018-12-31', 2, 41, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(171, '2018-12-31', 2, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(172, '2018-12-31', 2, 42, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(173, '2018-12-31', 6, 62, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(174, '2018-12-31', 6, 47, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(175, '2018-12-31', 6, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(176, '2018-12-31', 6, 61, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(177, '2018-12-31', 6, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(178, '2018-12-31', 6, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(179, '2018-12-31', 6, 13, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(180, '2018-12-31', 9, 24, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(181, '2018-12-31', 9, 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(182, '2018-12-31', 4, 62, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(183, '2018-12-31', 4, 54, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(184, '2018-12-31', 4, 51, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(185, '2018-12-31', 4, 12, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(186, '2018-12-31', 4, 63, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(187, '2018-12-31', 4, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(188, '2018-12-31', 4, 61, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(189, '2018-12-31', 4, 64, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(190, '2018-12-31', 4, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(191, '2018-12-31', 4, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(192, '2018-12-31', 4, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(193, '2018-12-31', 11, 24, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(194, '2018-12-31', 11, 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(195, '2018-12-31', 5, 12, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(196, '2018-12-31', 5, 47, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(197, '2018-12-31', 5, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(198, '2018-12-31', 5, 61, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(199, '2018-12-31', 5, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(200, '2018-12-31', 5, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(201, '2018-12-31', 5, 13, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(256, '2019-01-31', 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(257, '2019-01-31', 1, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(258, '2019-01-31', 1, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(259, '2019-01-31', 1, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(260, '2019-01-31', 1, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(261, '2019-01-31', 1, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(262, '2019-01-31', 1, 10, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(263, '2019-01-31', 1, 12, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(264, '2019-01-31', 1, 13, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(265, '2019-01-31', 1, 14, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(266, '2019-01-31', 1, 15, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(267, '2019-01-31', 1, 16, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(268, '2019-01-31', 1, 17, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(269, '2019-01-31', 1, 18, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(270, '2019-01-31', 1, 19, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(271, '2019-01-31', 1, 20, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(272, '2019-01-31', 1, 21, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(273, '2019-01-31', 1, 30, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(274, '2019-01-31', 1, 31, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(275, '2019-01-31', 1, 32, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(276, '2019-01-31', 1, 33, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(277, '2019-01-31', 1, 35, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(278, '2019-01-31', 1, 36, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(279, '2019-01-31', 1, 37, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(280, '2019-01-31', 1, 39, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(281, '2019-01-31', 1, 41, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(282, '2019-01-31', 1, 42, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(283, '2019-01-31', 1, 43, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(284, '2019-01-31', 1, 44, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(285, '2019-01-31', 1, 45, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(286, '2019-01-31', 1, 46, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(287, '2019-01-31', 1, 54, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(288, '2019-01-31', 1, 58, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(289, '2019-01-31', 1, 67, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(290, '2019-01-31', 1, 70, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(291, '2019-01-31', 1, 71, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(292, '2019-01-31', 1, 72, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(293, '2019-01-31', 1, 73, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(294, '2019-01-31', 2, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(295, '2019-01-31', 2, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(296, '2019-01-31', 2, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(297, '2019-01-31', 2, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(298, '2019-01-31', 2, 12, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(299, '2019-01-31', 2, 15, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(300, '2019-01-31', 2, 30, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(301, '2019-01-31', 2, 41, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(302, '2019-01-31', 2, 42, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(303, '2019-01-31', 2, 64, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(304, '2019-01-31', 3, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(305, '2019-01-31', 3, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(306, '2019-01-31', 3, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(307, '2019-01-31', 3, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(308, '2019-01-31', 3, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(309, '2019-01-31', 3, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(310, '2019-01-31', 3, 10, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(311, '2019-01-31', 3, 12, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(312, '2019-01-31', 3, 13, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(313, '2019-01-31', 3, 15, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(314, '2019-01-31', 3, 16, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(315, '2019-01-31', 3, 21, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(316, '2019-01-31', 3, 22, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(317, '2019-01-31', 3, 30, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(318, '2019-01-31', 3, 31, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(319, '2019-01-31', 3, 39, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(320, '2019-01-31', 3, 41, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(321, '2019-01-31', 3, 42, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(322, '2019-01-31', 3, 47, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(323, '2019-01-31', 3, 54, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(324, '2019-01-31', 3, 58, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(325, '2019-01-31', 3, 59, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(326, '2019-01-31', 3, 61, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(327, '2019-01-31', 3, 64, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(328, '2019-01-31', 4, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(329, '2019-01-31', 4, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(330, '2019-01-31', 4, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(331, '2019-01-31', 4, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(332, '2019-01-31', 4, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(333, '2019-01-31', 4, 12, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(334, '2019-01-31', 4, 51, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(335, '2019-01-31', 4, 54, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(336, '2019-01-31', 4, 61, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(337, '2019-01-31', 4, 62, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(338, '2019-01-31', 4, 63, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(339, '2019-01-31', 4, 64, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(340, '2019-01-31', 4, 75, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(341, '2019-01-31', 5, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(342, '2019-01-31', 5, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(343, '2019-01-31', 5, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(344, '2019-01-31', 5, 12, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(345, '2019-01-31', 5, 13, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(346, '2019-01-31', 5, 47, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(347, '2019-01-31', 5, 61, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(348, '2019-01-31', 5, 62, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(349, '2019-01-31', 5, 75, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(350, '2019-01-31', 6, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(351, '2019-01-31', 6, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(352, '2019-01-31', 6, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(353, '2019-01-31', 6, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(354, '2019-01-31', 6, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(355, '2019-01-31', 6, 12, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(356, '2019-01-31', 6, 13, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(357, '2019-01-31', 6, 15, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(358, '2019-01-31', 6, 41, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(359, '2019-01-31', 6, 47, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(360, '2019-01-31', 6, 60, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(361, '2019-01-31', 6, 61, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(362, '2019-01-31', 6, 62, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(363, '2019-01-31', 7, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(364, '2019-01-31', 7, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(365, '2019-01-31', 7, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(366, '2019-01-31', 7, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(367, '2019-01-31', 7, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(368, '2019-01-31', 7, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(369, '2019-01-31', 7, 12, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(370, '2019-01-31', 7, 13, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(371, '2019-01-31', 7, 15, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(372, '2019-01-31', 7, 16, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(373, '2019-01-31', 7, 22, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(374, '2019-01-31', 7, 31, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(375, '2019-01-31', 7, 39, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(376, '2019-01-31', 7, 41, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(377, '2019-01-31', 7, 47, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(378, '2019-01-31', 7, 52, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(379, '2019-01-31', 7, 54, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(380, '2019-01-31', 7, 58, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(381, '2019-01-31', 7, 61, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(382, '2019-01-31', 7, 66, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(383, '2019-01-31', 7, 68, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(384, '2019-01-31', 8, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(385, '2019-01-31', 8, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(386, '2019-01-31', 8, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(387, '2019-01-31', 8, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(388, '2019-01-31', 8, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(389, '2019-01-31', 8, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(390, '2019-01-31', 8, 12, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(391, '2019-01-31', 8, 13, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(392, '2019-01-31', 8, 21, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(393, '2019-01-31', 8, 22, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(394, '2019-01-31', 8, 30, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(395, '2019-01-31', 8, 41, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(396, '2019-01-31', 8, 47, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(397, '2019-01-31', 8, 48, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(398, '2019-01-31', 8, 50, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(399, '2019-01-31', 8, 51, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(400, '2019-01-31', 8, 52, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(401, '2019-01-31', 8, 53, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(402, '2019-01-31', 8, 54, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(403, '2019-01-31', 8, 55, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(404, '2019-01-31', 8, 56, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(405, '2019-01-31', 8, 57, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(406, '2019-01-31', 8, 64, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(407, '2019-01-31', 8, 65, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(408, '2019-01-31', 8, 73, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(409, '2019-01-31', 9, 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(410, '2019-01-31', 9, 24, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(411, '2019-01-31', 10, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(412, '2019-01-31', 10, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(413, '2019-01-31', 10, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(414, '2019-01-31', 10, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(415, '2019-01-31', 10, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(416, '2019-01-31', 10, 12, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(417, '2019-01-31', 10, 15, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(418, '2019-01-31', 10, 48, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(419, '2019-01-31', 10, 63, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(420, '2019-01-31', 10, 64, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(421, '2019-01-31', 11, 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(422, '2019-01-31', 11, 24, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(423, '2019-01-31', 12, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(424, '2019-01-31', 12, 12, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(425, '2019-01-31', 12, 31, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(426, '2019-01-31', 12, 39, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(427, '2019-01-31', 12, 54, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(428, '2019-01-31', 13, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(429, '2019-01-31', 13, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(430, '2019-01-31', 13, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(431, '2019-01-31', 13, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(432, '2019-01-31', 13, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(433, '2019-01-31', 13, 12, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(434, '2019-01-31', 13, 13, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(435, '2019-01-31', 13, 16, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(436, '2019-01-31', 13, 22, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(437, '2019-01-31', 13, 39, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(438, '2019-01-31', 13, 41, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(439, '2019-01-31', 13, 47, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(440, '2019-01-31', 13, 60, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(441, '2019-01-31', 13, 61, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(442, '2019-01-31', 14, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(443, '2019-01-31', 14, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(444, '2019-01-31', 14, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(445, '2019-01-31', 14, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(446, '2019-01-31', 14, 12, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(447, '2019-01-31', 14, 15, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(448, '2019-01-31', 14, 22, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(449, '2019-01-31', 14, 41, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(450, '2019-01-31', 15, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(451, '2019-01-31', 16, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(452, '2019-01-31', 16, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(453, '2019-01-31', 16, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(454, '2019-01-31', 17, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(455, '2019-01-31', 17, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(456, '2019-01-31', 17, 12, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(457, '2019-01-31', 17, 13, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(458, '2019-01-31', 17, 30, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(459, '2019-01-31', 17, 41, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(460, '2019-01-31', 18, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(461, '2019-01-31', 18, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(462, '2019-01-31', 18, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(463, '2019-01-31', 18, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(464, '2019-01-31', 18, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(465, '2019-01-31', 18, 22, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(466, '2019-01-31', 18, 41, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(467, '2019-01-31', 18, 52, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(468, '2019-01-31', 18, 54, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(469, '2019-01-31', 18, 63, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(470, '2019-01-31', 18, 64, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(471, '2019-01-31', 18, 65, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(472, '2019-01-31', 19, 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(473, '2019-01-31', 19, 24, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(474, '2019-01-31', 20, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(475, '2019-01-31', 20, 12, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(476, '2019-01-31', 21, 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(477, '2019-01-31', 23, 21, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(514, '2019-02-28', 1, 77, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(515, '2019-02-28', 1, 78, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(516, '2019-02-28', 3, 76, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(517, '2019-02-28', 6, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(518, '2019-02-28', 6, 24, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(519, '2019-02-28', 6, 56, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(520, '2019-02-28', 13, 54, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(521, '2019-02-28', 14, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(522, '2019-02-28', 18, 12, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(523, '2019-02-28', 20, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(524, '2019-02-28', 20, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(525, '2019-02-28', 20, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(526, '2019-03-31', 1, 79, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(527, '2019-03-31', 5, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(528, '2019-03-31', 6, 22, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(529, '2019-03-31', 13, 50, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(530, '2019-03-31', 14, 56, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(531, '2019-03-31', 21, 24, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(532, '2019-04-30', 1, 22, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(533, '2019-04-30', 1, 69, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(534, '2019-04-30', 4, 19, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(535, '2019-04-30', 6, 51, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(536, '2019-04-30', 10, 41, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(537, '2019-04-30', 10, 80, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(538, '2019-04-30', 14, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(539, '2019-05-31', 10, 81, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(540, '2019-06-30', 16, 30, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(541, '2019-07-31', 4, 41, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(542, '2019-08-31', 1, 82, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(543, '2019-08-31', 1, 83, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(544, '2019-08-31', 1, 84, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(545, '2019-08-31', 24, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(546, '2019-08-31', 24, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(547, '2019-08-31', 24, 12, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(548, '2019-09-30', 6, 30, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(549, '2019-09-30', 24, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(550, '2019-10-31', 10, 47, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(551, '2019-10-31', 18, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(552, '2019-11-30', 4, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(553, '2019-11-30', 15, 61, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(554, '2019-12-31', 10, 14, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(555, '2019-12-31', 12, 64, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(556, '2020-01-31', 1, 1, 8325.74, 8181.18, 8181.18, 8181.18, 8181.18, 8181.18, 8181.18, 8181.18, 8181.18, 8181.18, 8181.18, 8181.18),
	(557, '2020-01-31', 1, 2, 8545.45, 8313.37, 8313.37, 8313.37, 8313.37, 8313.37, 8313.37, 8313.37, 8313.37, 8313.37, 8313.37, 8186.94),
	(558, '2020-01-31', 1, 3, 8091.01, 8091.01, 8091.01, 8091.01, 8091.01, 8091.01, 8091.01, 8090.94, 8090.93, 8090.91, 8090.91, 8090.91),
	(559, '2020-01-31', 1, 4, 8572.47, 8572.47, 8572.47, 8572.47, 8572.47, 8572.47, 8572.47, 8572.47, 8572.47, 8572.47, 8572.47, 8572.47),
	(560, '2020-01-31', 1, 7, 8125.31, 8095.16, 8095.16, 8095.16, 8094.44, 8094.44, 8094.44, 8094.44, 8094.44, 8092.92, 8092.92, 8092.92),
	(561, '2020-01-31', 1, 9, 8124.56, 8124.56, 8113.53, 8113.53, 8113.53, 8113.53, 8113.53, 8113.53, 8103.48, 8094.42, 8093.34, 8091.63),
	(562, '2020-01-31', 1, 10, 8343.06, 8343.06, 8343.06, 8343.06, 8343.06, 8343.06, 8343.06, 8343.06, 8343.06, 8343.06, 8343.06, 8343.06),
	(563, '2020-01-31', 1, 12, 8298.88, 8298.88, 8298.88, 8298.88, 8298.88, 8298.88, 8298.88, 8298.88, 8298.88, 8298.88, 8169.14, 8123.15),
	(564, '2020-01-31', 1, 13, 8138.70, 8116.15, 8091.41, 8091.41, 8091.41, 8091.41, 8091.41, 8091.41, 8091.41, 8090.98, 8164.76, 8110.98),
	(565, '2020-01-31', 1, 14, 8095.45, 8093.47, 8093.47, 8093.47, 8093.47, 8093.47, 8093.47, 8093.47, 8093.47, 8093.47, 8092.23, 8091.68),
	(566, '2020-01-31', 1, 15, 8090.91, 8090.91, 8090.91, 8090.91, 8090.91, 8090.91, 8090.91, 8090.91, 8090.91, 8090.91, 8202.91, 8202.91),
	(567, '2020-01-31', 1, 16, 8135.71, 8135.71, 8091.77, 8091.77, 8091.77, 8091.77, 8091.77, 8091.77, 8091.77, 8091.21, 8369.00, 8290.91),
	(568, '2020-01-31', 1, 17, 8374.64, 8192.76, 8192.76, 8192.76, 8192.76, 8192.76, 8192.76, 8192.76, 8192.76, 8192.76, 8135.40, 8135.40),
	(569, '2020-01-31', 1, 18, 8263.31, 8263.31, 8263.31, 8263.31, 8263.31, 8263.31, 8263.31, 8263.31, 8263.31, 8263.31, 8263.31, 8263.31),
	(570, '2020-01-31', 1, 19, 8194.75, 8124.31, 8124.31, 8124.31, 8124.31, 8124.31, 8124.31, 8124.31, 8124.31, 8124.31, 8124.31, 8124.31),
	(571, '2020-01-31', 1, 20, 8325.06, 8192.44, 8192.44, 8192.44, 8192.44, 8192.44, 8192.44, 8192.44, 8192.44, 8192.44, 8192.44, 8192.44),
	(572, '2020-01-31', 1, 21, 8174.86, 8174.86, 8174.86, 8174.86, 8174.86, 8174.86, 8174.86, 8174.86, 8174.86, 8174.86, 8174.86, 8174.86),
	(573, '2020-01-31', 1, 22, 8545.45, 8545.45, 8220.90, 8220.90, 8220.90, 8220.90, 8220.90, 8220.90, 8220.90, 8220.90, 8220.90, 8220.90),
	(574, '2020-01-31', 1, 30, 8155.95, 8155.95, 8155.95, 8155.95, 8124.88, 8124.88, 8124.88, 8102.55, 8102.55, 8102.55, 8261.08, 8203.26),
	(575, '2020-01-31', 1, 31, 8091.06, 8091.06, 8091.06, 8091.06, 8090.95, 8090.95, 8090.95, 8090.95, 8090.95, 8090.92, 8327.90, 8191.58),
	(576, '2020-01-31', 1, 32, 8099.13, 8094.89, 8094.89, 8094.89, 8092.14, 8092.14, 8092.14, 8092.14, 8092.14, 8092.14, 8092.14, 8091.32),
	(577, '2020-01-31', 1, 33, 8152.54, 8152.54, 8152.54, 8152.54, 8152.54, 8152.54, 8152.54, 8152.54, 8152.54, 8152.54, 8152.54, 8152.54),
	(578, '2020-01-31', 1, 35, 8346.37, 8346.37, 8346.37, 8346.37, 8346.37, 8346.37, 8346.37, 8346.37, 8346.37, 8346.37, 8346.37, 8346.37),
	(579, '2020-01-31', 1, 36, 8090.91, 8090.91, 8090.91, 8090.91, 8090.91, 8090.91, 8090.91, 8090.91, 8090.91, 8090.91, 8190.93, 8123.88),
	(580, '2020-01-31', 1, 37, 8109.73, 8103.36, 8103.36, 8103.36, 8103.36, 8103.36, 8103.36, 8103.36, 8103.36, 8103.36, 8103.36, 8103.36),
	(581, '2020-01-31', 1, 39, 8144.34, 8122.87, 8122.87, 8122.87, 8122.87, 8122.87, 8122.87, 8122.87, 8122.87, 8122.87, 8119.37, 8102.90),
	(582, '2020-01-31', 1, 41, 8090.91, 8090.91, 8090.91, 8090.91, 8090.91, 8090.91, 8090.91, 8090.91, 8090.91, 8090.91, 8090.91, 8090.91),
	(583, '2020-01-31', 1, 42, 8226.63, 8116.97, 8098.06, 8098.06, 8098.06, 8098.06, 8098.06, 8098.06, 8098.06, 8093.41, 8093.41, 8092.64),
	(584, '2020-01-31', 1, 43, 8189.68, 8117.22, 8117.22, 8117.22, 8103.07, 8103.07, 8103.07, 8103.07, 8103.07, 8095.63, 8092.23, 8091.25),
	(585, '2020-01-31', 1, 44, 8116.41, 8101.89, 8101.89, 8101.89, 8101.89, 8101.89, 8101.89, 8094.46, 8094.46, 8094.46, 8092.51, 8091.79),
	(586, '2020-01-31', 1, 45, 8119.05, 8119.05, 8090.91, 8090.91, 8090.91, 8090.91, 8090.91, 8090.91, 8090.91, 8090.91, 8299.30, 8299.30),
	(587, '2020-01-31', 1, 46, 8090.91, 8090.91, 8090.91, 8090.91, 8090.91, 8090.91, 8090.91, 8090.91, 8090.91, 8090.91, 8090.91, 8090.91),
	(588, '2020-01-31', 1, 54, 8090.91, 8090.91, 8090.91, 8090.91, 8090.91, 8090.91, 8090.91, 8090.91, 8090.91, 8090.91, 8185.96, 8141.40),
	(589, '2020-01-31', 1, 58, 8092.42, 8092.13, 8092.13, 8092.13, 8092.13, 8092.13, 8092.13, 8092.13, 8092.13, 8092.13, 8092.13, 8092.13),
	(590, '2020-01-31', 1, 67, 8094.72, 8093.53, 8093.53, 8093.53, 8093.53, 8093.53, 8093.53, 8093.53, 8093.53, 8093.53, 8093.53, 8093.53),
	(591, '2020-01-31', 1, 69, 8098.07, 8098.07, 8098.07, 8098.07, 8092.48, 8092.48, 8092.48, 8092.48, 8092.48, 8092.48, 8092.48, 8092.48),
	(592, '2020-01-31', 1, 70, 8202.93, 8202.93, 8202.93, 8202.93, 8202.93, 8202.93, 8202.93, 8202.93, 8202.93, 8202.93, 8202.93, 8202.93),
	(593, '2020-01-31', 1, 71, 8098.67, 8098.67, 8098.67, 8098.67, 8093.41, 8093.41, 8093.41, 8093.41, 8091.16, 8090.93, 8090.92, 8090.91),
	(594, '2020-01-31', 1, 72, 8136.97, 8136.97, 8136.97, 8136.97, 8136.97, 8136.97, 8136.97, 8136.97, 8136.97, 8136.97, 8136.97, 8136.97),
	(595, '2020-01-31', 1, 73, 8105.65, 8105.65, 8090.91, 8090.91, 8090.91, 8090.91, 8090.91, 8090.91, 8090.91, 8090.91, 8090.91, 8090.91),
	(596, '2020-01-31', 1, 77, 8097.43, 8090.96, 8090.96, 8090.96, 8090.94, 8090.93, 8090.93, 8090.93, 8090.93, 8090.93, 8090.92, 8090.92),
	(597, '2020-01-31', 1, 78, 8196.32, 8123.02, 8123.02, 8123.02, 8123.02, 8123.02, 8123.02, 8123.02, 8123.02, 8123.02, 8123.02, 8103.57),
	(598, '2020-01-31', 1, 79, 8204.26, 8204.26, 8090.91, 8090.91, 8090.91, 8090.91, 8090.91, 8090.91, 8090.91, 8090.91, 8090.91, 8090.91),
	(599, '2020-01-31', 1, 82, 8095.64, 8093.63, 8093.63, 8093.63, 8092.59, 8092.59, 8092.59, 8092.59, 8092.59, 8092.59, 8092.59, 8092.59),
	(600, '2020-01-31', 1, 83, 8121.74, 8121.74, 8121.74, 8121.74, 8121.74, 8121.74, 8121.74, 8121.74, 0.00, 8090.91, 8090.91, 8090.91),
	(601, '2020-01-31', 1, 84, 8204.72, 8124.48, 8124.48, 8124.48, 8101.59, 8101.59, 8101.59, 8101.59, 8101.59, 8101.59, 8101.59, 8101.59),
	(602, '2020-01-31', 2, 3, 54545.45, 54545.45, 54545.45, 54545.45, 54545.45, 54545.45, 54545.45, 54545.45, 54545.45, 54545.45, 54545.45, 54545.45),
	(603, '2020-01-31', 2, 4, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00),
	(604, '2020-01-31', 2, 7, 50000.00, 50000.00, 50000.00, 50000.00, 50000.00, 50000.00, 50000.00, 50000.00, 50000.00, 50000.00, 50000.00, 50000.00),
	(605, '2020-01-31', 2, 9, 50000.00, 50000.00, 50000.00, 50000.00, 50000.00, 50000.00, 50000.00, 50000.00, 50000.00, 50000.00, 50000.00, 50000.00),
	(606, '2020-01-31', 2, 12, 50000.00, 50000.00, 50000.00, 50000.00, 50000.00, 50000.00, 50000.00, 50000.00, 50000.00, 50000.00, 50000.00, 50000.00),
	(607, '2020-01-31', 2, 15, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00),
	(608, '2020-01-31', 2, 30, 50000.00, 50000.00, 50000.00, 50000.00, 50000.00, 50000.00, 50000.00, 50000.00, 50000.00, 50000.00, 50000.00, 50000.00),
	(609, '2020-01-31', 2, 41, 50000.00, 50000.00, 50000.00, 50000.00, 50000.00, 50000.00, 50000.00, 50000.00, 50000.00, 50000.00, 50000.00, 50000.00),
	(610, '2020-01-31', 2, 42, 50000.00, 50000.00, 50000.00, 50000.00, 50000.00, 50000.00, 50000.00, 50000.00, 50000.00, 50000.00, 50000.00, 50000.00),
	(611, '2020-01-31', 2, 64, 50000.00, 50000.00, 50000.00, 50000.00, 50000.00, 50000.00, 50000.00, 50000.00, 50000.00, 50000.00, 50000.00, 50000.00),
	(612, '2020-01-31', 3, 2, 47669.78, 46525.09, 46525.09, 46525.09, 46525.09, 46525.09, 46525.09, 46525.09, 46525.09, 46525.09, 46525.09, 46525.09),
	(613, '2020-01-31', 3, 3, 46501.32, 45759.00, 45500.56, 45759.07, 45969.24, 45969.24, 46283.85, 46314.24, 46314.24, 46314.24, 44495.10, 43873.73),
	(614, '2020-01-31', 3, 4, 46742.16, 46236.13, 46236.13, 46236.13, 46236.13, 46236.13, 46236.13, 46236.13, 46236.13, 46236.13, 45019.76, 45019.76),
	(615, '2020-01-31', 3, 5, 45539.16, 45485.01, 45475.61, 45473.50, 45473.50, 45473.50, 45473.50, 45473.50, 45473.50, 45473.50, 45473.50, 45473.50),
	(616, '2020-01-31', 3, 7, 45681.36, 45595.29, 45545.72, 45545.72, 45545.72, 45545.72, 45545.72, 45545.72, 45545.72, 45545.72, 44324.78, 44324.78),
	(617, '2020-01-31', 3, 9, 46591.91, 46276.46, 46276.46, 46276.46, 46276.46, 46276.46, 46324.86, 46336.84, 46336.84, 46336.84, 44237.03, 44237.03),
	(618, '2020-01-31', 3, 10, 47740.80, 47740.80, 47740.80, 47740.80, 47740.80, 46680.63, 46680.63, 46680.63, 46680.63, 46680.63, 46680.63, 46680.63),
	(619, '2020-01-31', 3, 12, 46785.19, 46188.10, 46071.85, 46071.85, 46071.85, 46071.85, 46071.85, 46207.46, 46207.46, 46207.46, 46207.46, 46207.46),
	(620, '2020-01-31', 3, 13, 47761.84, 47761.84, 45718.98, 45718.98, 45718.98, 45718.98, 45718.98, 46160.43, 46160.43, 46160.43, 43706.69, 43706.69),
	(621, '2020-01-31', 3, 15, 47645.21, 45454.55, 45454.55, 45454.55, 46207.32, 46207.32, 46207.32, 46207.32, 46207.32, 46207.32, 43709.40, 43709.40),
	(622, '2020-01-31', 3, 16, 47609.00, 47609.00, 47609.00, 47609.00, 47609.00, 47609.00, 47609.00, 46685.76, 46685.76, 46685.76, 46685.76, 43875.81),
	(623, '2020-01-31', 3, 21, 48407.56, 48407.56, 48407.56, 48407.56, 48407.56, 48407.56, 48407.56, 48407.56, 48407.56, 48407.56, 48407.56, 48407.56),
	(624, '2020-01-31', 3, 22, 48475.84, 48475.84, 48475.84, 48475.84, 48475.84, 48475.84, 48475.84, 48475.84, 48475.84, 48475.84, 48475.84, 48475.84),
	(625, '2020-01-31', 3, 30, 47827.15, 47827.15, 47827.15, 45614.93, 45614.93, 45614.93, 45614.93, 46091.59, 46091.59, 46091.59, 46091.59, 46091.59),
	(626, '2020-01-31', 3, 31, 47423.75, 47423.75, 47423.75, 47423.75, 47423.75, 47423.75, 46919.09, 46919.09, 46919.09, 46919.09, 43989.26, 43980.95),
	(627, '2020-01-31', 3, 39, 47272.73, 47272.73, 45775.58, 45775.58, 45775.58, 45775.58, 45775.58, 45775.58, 45775.58, 45775.58, 45775.58, 45775.58),
	(628, '2020-01-31', 3, 41, 46518.06, 46518.06, 46518.06, 46518.06, 46518.06, 46518.06, 46518.06, 46518.06, 46518.06, 46518.06, 46518.06, 46518.06),
	(629, '2020-01-31', 3, 42, 53269.79, 53269.79, 51501.84, 51501.84, 51501.84, 51501.84, 51501.84, 51188.72, 51188.72, 51188.72, 51188.72, 51188.72),
	(630, '2020-01-31', 3, 47, 53207.81, 53207.81, 53207.81, 53207.81, 53207.81, 53207.81, 53207.81, 53207.81, 53207.81, 53207.81, 53207.81, 53207.81),
	(631, '2020-01-31', 3, 54, 47594.86, 47594.86, 45900.98, 45900.98, 45900.98, 45900.98, 46052.55, 46217.61, 46217.61, 46217.61, 46217.61, 46217.61),
	(632, '2020-01-31', 3, 58, 45932.63, 45932.63, 45932.63, 45932.63, 45932.63, 45932.63, 45932.63, 45932.63, 45932.63, 45932.63, 45932.63, 45932.63),
	(633, '2020-01-31', 3, 59, 48723.34, 48723.34, 46032.48, 46032.48, 46032.48, 46032.48, 46032.48, 46032.48, 46032.48, 46032.48, 46032.48, 46032.48),
	(634, '2020-01-31', 3, 61, 47816.87, 47816.87, 47816.87, 47816.87, 47816.87, 47816.87, 47816.87, 46990.92, 46990.92, 46990.92, 46990.92, 46990.92),
	(635, '2020-01-31', 3, 64, 48827.20, 48827.20, 48827.20, 48827.20, 48827.20, 48827.20, 48827.20, 48827.20, 48827.20, 48827.20, 48827.20, 48827.20),
	(636, '2020-01-31', 3, 76, 53108.48, 53108.48, 53108.48, 53108.48, 53108.48, 53108.48, 53108.48, 53108.48, 53108.48, 53108.48, 53108.48, 53108.48),
	(637, '2020-01-31', 4, 2, 7363.64, 0.00, 0.00, 0.00, 0.00, 7363.64, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00),
	(638, '2020-01-31', 4, 3, 7363.68, 7363.65, 7363.64, 7363.64, 7363.64, 7363.64, 7363.64, 7363.64, 7363.64, 7363.64, 7363.64, 7363.64),
	(639, '2020-01-31', 4, 4, 7368.85, 7365.80, 7365.80, 7365.80, 7365.80, 7365.80, 7365.80, 7365.80, 7365.80, 7365.80, 7365.80, 7365.80),
	(640, '2020-01-31', 4, 6, 7545.45, 7365.41, 7364.64, 7364.08, 7363.64, 7363.64, 7363.64, 7363.64, 7363.64, 7363.64, 7363.64, 7363.64),
	(641, '2020-01-31', 4, 7, 7407.23, 7377.52, 7367.12, 7364.45, 7720.42, 7720.42, 7720.42, 7375.78, 7375.78, 7363.87, 7363.87, 7363.87),
	(642, '2020-01-31', 4, 9, 7430.96, 7430.96, 7366.77, 7366.24, 7365.10, 7364.32, 7364.28, 7364.28, 7364.28, 7364.28, 7364.28, 7364.28),
	(643, '2020-01-31', 4, 12, 7367.31, 7367.31, 7367.31, 7366.48, 7366.48, 7366.48, 7363.69, 7363.69, 7363.69, 7363.65, 7363.65, 7363.65),
	(644, '2020-01-31', 4, 19, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00),
	(645, '2020-01-31', 4, 41, 7414.21, 7384.55, 7384.55, 7384.55, 7384.55, 7384.55, 7384.55, 7384.55, 7384.55, 7384.55, 7384.55, 7384.55),
	(646, '2020-01-31', 4, 51, 7397.44, 7388.71, 7369.71, 7369.71, 7363.64, 7363.64, 7363.64, 7363.64, 7363.64, 7363.64, 7363.64, 7363.64),
	(647, '2020-01-31', 4, 54, 7407.98, 7407.98, 7407.98, 7407.98, 7407.98, 7376.31, 7376.31, 7363.64, 7363.64, 7363.64, 7363.64, 7363.64),
	(648, '2020-01-31', 4, 61, 7438.10, 7438.10, 7365.78, 7365.78, 7365.78, 7365.78, 7365.78, 7365.78, 7365.78, 7365.78, 7365.78, 7365.78),
	(649, '2020-01-31', 4, 62, 7545.45, 7545.45, 7545.45, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00),
	(650, '2020-01-31', 4, 63, 7545.45, 7545.45, 7365.34, 7365.34, 7365.34, 7365.34, 7365.34, 7365.34, 7365.34, 7363.64, 7363.64, 7363.64),
	(651, '2020-01-31', 4, 64, 7411.27, 7381.89, 7381.89, 7381.89, 7365.12, 7365.12, 7365.12, 7365.12, 7365.12, 7365.12, 7365.12, 7365.12),
	(652, '2020-01-31', 4, 75, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00),
	(653, '2020-01-31', 5, 3, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00),
	(654, '2020-01-31', 5, 4, 7181.82, 7181.82, 7181.82, 7181.82, 7181.82, 7181.82, 7181.82, 0.00, 0.00, 0.00, 0.00, 0.00),
	(655, '2020-01-31', 5, 7, 7000.00, 7000.00, 7000.00, 6545.45, 6545.45, 6545.45, 6545.45, 6545.45, 6545.45, 6545.45, 6545.45, 6545.45),
	(656, '2020-01-31', 5, 9, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00),
	(657, '2020-01-31', 5, 12, 7000.00, 7000.00, 7000.00, 7000.00, 7000.00, 7000.00, 7000.00, 7000.00, 0.00, 0.00, 0.00, 0.00),
	(658, '2020-01-31', 5, 13, 7000.00, 7000.00, 7000.00, 7000.00, 7000.00, 7000.00, 7000.00, 7000.00, 7000.00, 7000.00, 7000.00, 7000.00),
	(659, '2020-01-31', 5, 39, 6545.45, 6545.45, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00),
	(660, '2020-01-31', 5, 47, 7000.00, 7000.00, 7000.00, 7000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00),
	(661, '2020-01-31', 5, 61, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00),
	(662, '2020-01-31', 5, 62, 7000.00, 7000.00, 7000.00, 7000.00, 7000.00, 7000.00, 7000.00, 7000.00, 7000.00, 7000.00, 7000.00, 7000.00),
	(663, '2020-01-31', 5, 75, 7000.00, 7000.00, 7000.00, 7000.00, 7000.00, 7000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00),
	(664, '2020-01-31', 6, 3, 5909.09, 5909.09, 5909.09, 5909.09, 5909.09, 5909.09, 5909.09, 5909.09, 5909.09, 5909.09, 5909.09, 5909.09),
	(665, '2020-01-31', 6, 4, 5910.10, 5909.13, 5909.11, 5909.10, 5909.10, 5909.10, 5909.09, 5909.09, 5909.09, 5909.09, 5909.09, 5909.09),
	(666, '2020-01-31', 6, 5, 6012.90, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00),
	(667, '2020-01-31', 6, 6, 5909.09, 5909.09, 5909.09, 5909.09, 5909.09, 5909.09, 5909.09, 5909.09, 5909.09, 5909.09, 5909.09, 5909.09),
	(668, '2020-01-31', 6, 7, 5950.47, 5950.47, 5950.47, 5909.09, 5909.09, 5909.09, 5909.09, 5909.09, 5909.09, 5909.09, 5909.09, 5909.09),
	(669, '2020-01-31', 6, 9, 6183.67, 6183.67, 6183.67, 6183.67, 6183.67, 6183.67, 6183.67, 5955.25, 5955.25, 5955.25, 5955.25, 5955.25),
	(670, '2020-01-31', 6, 12, 5909.09, 5909.09, 5909.09, 5909.09, 5909.09, 5909.09, 5909.09, 5909.09, 5909.09, 5909.09, 5909.09, 5909.09),
	(671, '2020-01-31', 6, 13, 6120.70, 6041.71, 6041.71, 6041.71, 6041.71, 6041.71, 6041.71, 6041.71, 6041.71, 5909.09, 5909.09, 5909.09),
	(672, '2020-01-31', 6, 15, 6091.97, 6091.97, 6091.97, 6091.97, 0.00, 5909.09, 5909.09, 5909.09, 5909.09, 5909.09, 5909.09, 5909.09),
	(673, '2020-01-31', 6, 22, 5938.18, 5938.18, 5931.59, 5931.59, 0.00, 5909.09, 5909.09, 5909.09, 5909.09, 5909.09, 5909.09, 5909.09),
	(674, '2020-01-31', 6, 24, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00),
	(675, '2020-01-31', 6, 30, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00),
	(676, '2020-01-31', 6, 41, 5909.09, 5909.09, 5909.09, 5909.09, 5909.09, 5909.09, 5909.09, 5909.09, 5909.09, 5909.09, 5909.09, 5909.09),
	(677, '2020-01-31', 6, 47, 5941.06, 5941.06, 5941.06, 5941.06, 5941.06, 5909.40, 5909.40, 5909.40, 5909.40, 5909.16, 5909.16, 5909.16),
	(678, '2020-01-31', 6, 51, 6062.27, 5915.77, 5911.42, 5910.89, 5909.09, 5909.09, 5909.09, 5909.09, 5909.09, 5909.09, 5909.09, 5909.09),
	(679, '2020-01-31', 6, 56, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00),
	(680, '2020-01-31', 6, 60, 6363.64, 6363.64, 6363.64, 6363.64, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00),
	(681, '2020-01-31', 6, 61, 5909.39, 5909.39, 5909.39, 5909.39, 5909.39, 5909.39, 5909.39, 5909.39, 5909.39, 5909.17, 5909.17, 5909.17),
	(682, '2020-01-31', 6, 62, 6280.56, 6280.56, 6280.56, 6280.56, 6280.56, 6280.56, 6280.56, 6280.56, 6280.56, 5935.48, 5929.06, 5929.06),
	(683, '2020-01-31', 7, 2, 5048.59, 5017.40, 5017.40, 5017.40, 5017.40, 5017.40, 5002.88, 5000.98, 5000.52, 5000.29, 5000.29, 5000.29),
	(684, '2020-01-31', 7, 3, 5037.60, 5033.24, 5014.21, 5004.97, 5001.95, 5001.77, 5001.77, 5000.22, 5000.21, 5000.18, 5000.06, 5000.01),
	(685, '2020-01-31', 7, 4, 5027.79, 5007.65, 5003.28, 5000.82, 5000.44, 5000.39, 5000.10, 5000.00, 5000.00, 5000.00, 5000.00, 5000.00),
	(686, '2020-01-31', 7, 5, 5545.45, 5545.45, 5545.45, 5545.45, 5545.45, 5545.45, 5545.45, 5545.45, 5545.45, 5545.45, 5545.45, 5545.45),
	(687, '2020-01-31', 7, 7, 5034.60, 5002.23, 5000.74, 5000.24, 5000.21, 5000.21, 5000.03, 5000.00, 5000.00, 5000.00, 5000.00, 5000.00),
	(688, '2020-01-31', 7, 9, 5070.20, 5040.88, 5040.88, 5040.88, 5040.88, 5040.88, 5020.07, 5009.23, 5006.77, 5006.05, 5006.05, 5006.05),
	(689, '2020-01-31', 7, 12, 5064.34, 5029.93, 5027.31, 5027.31, 5027.31, 5027.31, 5005.05, 5002.43, 5001.51, 5000.85, 5000.85, 5000.85),
	(690, '2020-01-31', 7, 13, 5475.32, 5475.32, 5475.32, 5475.32, 5475.32, 5475.32, 5475.32, 5475.32, 5475.32, 5026.16, 5026.16, 5026.16),
	(691, '2020-01-31', 7, 15, 5056.91, 5056.91, 5056.91, 5056.91, 5056.91, 5056.91, 5015.05, 5003.53, 5001.01, 5000.38, 5000.33, 5000.33),
	(692, '2020-01-31', 7, 16, 5053.21, 5053.21, 5053.21, 5053.21, 5053.21, 5053.21, 5053.21, 5053.21, 5053.21, 5053.21, 5053.21, 5053.21),
	(693, '2020-01-31', 7, 22, 5545.45, 5545.45, 5545.45, 5545.45, 5545.45, 5545.45, 5545.45, 5545.45, 5545.45, 5212.54, 5212.54, 5212.54),
	(694, '2020-01-31', 7, 31, 5127.83, 5127.83, 5127.83, 5127.83, 5127.83, 5127.83, 5127.83, 5127.83, 5127.83, 5127.83, 5127.83, 5052.80),
	(695, '2020-01-31', 7, 39, 5089.92, 5089.92, 5089.92, 5089.92, 5089.92, 5089.92, 5089.92, 5089.92, 5056.77, 5010.36, 5010.36, 5010.36),
	(696, '2020-01-31', 7, 41, 5082.01, 5082.01, 5082.01, 5082.01, 5082.01, 5082.01, 5082.01, 5082.01, 5032.48, 5011.10, 5011.10, 5011.10),
	(697, '2020-01-31', 7, 47, 5033.86, 5014.74, 5014.74, 5014.74, 5014.74, 5014.74, 5014.74, 5006.24, 5004.78, 5002.54, 5002.54, 5002.54),
	(698, '2020-01-31', 7, 52, 5024.60, 5024.60, 5024.60, 5024.60, 5024.60, 5024.60, 5024.60, 5002.02, 5000.68, 5000.68, 5000.51, 5000.51),
	(699, '2020-01-31', 7, 54, 5053.93, 5053.93, 5053.93, 5053.93, 5053.93, 5053.93, 5053.93, 5053.93, 5025.24, 5025.24, 5025.24, 5025.24),
	(700, '2020-01-31', 7, 58, 5115.12, 5115.12, 5115.12, 5115.12, 5115.12, 5115.12, 5115.12, 5115.12, 5115.12, 5115.12, 5115.12, 5115.12),
	(701, '2020-01-31', 7, 61, 5330.42, 5040.73, 5040.73, 5040.73, 5040.73, 5025.40, 5007.50, 5007.50, 0.00, 5000.00, 5000.00, 5000.00),
	(702, '2020-01-31', 7, 66, 5112.42, 5112.42, 5112.42, 5112.42, 5112.42, 5112.42, 5112.42, 5112.42, 5112.42, 5112.42, 5112.42, 5055.45),
	(703, '2020-01-31', 7, 68, 5066.67, 5066.67, 5066.67, 5066.67, 5066.67, 5066.67, 5066.67, 5000.00, 5000.00, 5000.00, 5000.00, 5000.00),
	(704, '2020-01-31', 8, 2, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27),
	(705, '2020-01-31', 8, 3, 5727.27, 5727.27, 5332.09, 5332.09, 5332.09, 5332.09, 5274.94, 5273.54, 5273.42, 5273.42, 5273.42, 5273.42),
	(706, '2020-01-31', 8, 4, 5909.09, 5909.09, 5909.09, 5909.09, 5909.09, 5909.09, 5909.09, 5909.09, 5909.09, 5909.09, 5909.09, 5909.09),
	(707, '2020-01-31', 8, 5, 5046.31, 5046.31, 5240.52, 5240.52, 5240.52, 5240.52, 5240.52, 5240.52, 5240.52, 5240.52, 5240.52, 5240.52),
	(708, '2020-01-31', 8, 7, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27),
	(709, '2020-01-31', 8, 9, 5540.14, 5540.14, 5540.14, 5540.14, 5540.14, 5540.14, 5540.14, 5540.14, 5540.14, 5540.14, 5540.14, 5300.95),
	(710, '2020-01-31', 8, 12, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27),
	(711, '2020-01-31', 8, 13, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27),
	(712, '2020-01-31', 8, 21, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27),
	(713, '2020-01-31', 8, 22, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27),
	(714, '2020-01-31', 8, 30, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27),
	(715, '2020-01-31', 8, 41, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27),
	(716, '2020-01-31', 8, 47, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27),
	(717, '2020-01-31', 8, 48, 5482.41, 5482.41, 5482.41, 5482.41, 5482.41, 5482.41, 5482.41, 5482.41, 5482.41, 5482.41, 5482.41, 5482.41),
	(718, '2020-01-31', 8, 50, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27),
	(719, '2020-01-31', 8, 51, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27),
	(720, '2020-01-31', 8, 52, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27),
	(721, '2020-01-31', 8, 53, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27),
	(722, '2020-01-31', 8, 54, 5537.68, 5537.68, 5537.68, 5537.68, 5537.68, 5537.68, 5537.68, 5537.68, 5537.68, 5537.68, 5537.68, 5537.68),
	(723, '2020-01-31', 8, 55, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27),
	(724, '2020-01-31', 8, 56, 5085.59, 5085.59, 5085.59, 5085.59, 5085.59, 5085.59, 5085.59, 5085.59, 5085.59, 5085.59, 5085.59, 5085.59),
	(725, '2020-01-31', 8, 57, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27),
	(726, '2020-01-31', 8, 64, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27),
	(727, '2020-01-31', 8, 65, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27),
	(728, '2020-01-31', 8, 73, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27, 5727.27),
	(729, '2020-01-31', 9, 8, 6108.87, 6011.58, 6011.58, 6011.58, 6011.58, 6011.58, 6011.58, 5957.46, 5955.58, 5955.58, 5939.64, 5939.64),
	(730, '2020-01-31', 9, 24, 6363.64, 6363.64, 6363.64, 6363.64, 6363.64, 6363.64, 6363.64, 6363.64, 6363.64, 6363.64, 5980.73, 5980.73),
	(731, '2020-01-31', 10, 3, 7727.16, 7727.26, 7727.27, 7727.27, 7727.27, 7727.27, 7727.27, 7727.27, 7727.27, 7727.27, 7727.27, 7727.27),
	(732, '2020-01-31', 10, 4, 7909.09, 7848.03, 0.00, 7727.27, 7727.27, 7727.27, 7727.27, 7727.27, 7727.27, 7727.27, 7727.27, 7727.27),
	(733, '2020-01-31', 10, 5, 7727.27, 7727.27, 7727.27, 7727.27, 7727.27, 7727.27, 7727.27, 7727.27, 7727.27, 7727.27, 7727.27, 7727.27),
	(734, '2020-01-31', 10, 7, 7727.27, 7727.27, 7727.27, 7727.27, 7727.27, 7727.27, 7727.27, 7727.27, 7727.27, 7727.27, 7727.27, 7727.27),
	(735, '2020-01-31', 10, 9, 7727.27, 7727.27, 7727.27, 7727.27, 7727.27, 7727.27, 7727.27, 7727.27, 7727.27, 7727.27, 7727.27, 7727.27),
	(736, '2020-01-31', 10, 12, 7727.25, 7727.25, 7727.27, 7727.27, 7727.27, 7727.27, 7727.27, 7727.27, 7727.27, 7727.27, 7727.27, 7727.27),
	(737, '2020-01-31', 10, 14, 7727.27, 7727.27, 7727.27, 7727.27, 0.00, 0.00, 7727.27, 7727.27, 7727.27, 7727.27, 7727.27, 7727.27),
	(738, '2020-01-31', 10, 15, 7727.27, 7727.27, 7727.27, 7727.27, 7727.27, 7727.27, 7727.27, 7727.27, 7727.27, 7727.27, 7727.27, 7727.27),
	(739, '2020-01-31', 10, 41, 7727.27, 7727.27, 7727.27, 7727.27, 7727.27, 7727.27, 7727.27, 7727.27, 7727.27, 7727.27, 7727.27, 7727.27),
	(740, '2020-01-31', 10, 47, 7727.27, 7727.27, 7727.27, 7727.27, 7727.27, 7727.27, 7727.27, 7727.27, 7727.27, 7727.27, 7727.27, 7727.27),
	(741, '2020-01-31', 10, 48, 7727.27, 7727.27, 7727.27, 7727.27, 7727.27, 7727.27, 7727.27, 7727.27, 0.00, 0.00, 0.00, 0.00),
	(742, '2020-01-31', 10, 63, 7727.27, 7727.27, 7727.27, 7727.27, 7727.27, 7727.27, 7727.27, 7727.27, 7727.27, 7727.27, 7727.27, 7727.27),
	(743, '2020-01-31', 10, 64, 7727.27, 7727.27, 7727.27, 7727.27, 7727.27, 7727.27, 7727.27, 7727.27, 7727.27, 7727.27, 7727.27, 7727.27),
	(744, '2020-01-31', 10, 80, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00),
	(745, '2020-01-31', 10, 81, 0.00, 0.00, 7727.27, 7727.27, 7727.27, 0.00, 7727.27, 0.00, 7727.27, 7727.27, 7727.27, 7727.27),
	(746, '2020-01-31', 11, 8, 7364.93, 7364.39, 7364.39, 7364.39, 7364.39, 7364.39, 7364.39, 7364.39, 7364.39, 7363.88, 7363.88, 7363.88),
	(747, '2020-01-31', 11, 24, 7364.36, 7363.95, 7363.95, 7363.95, 7363.95, 7363.95, 7363.70, 7363.64, 7363.64, 7363.64, 7363.64, 7363.64),
	(748, '2020-01-31', 12, 3, 3636.36, 3636.36, 3636.36, 3636.36, 3636.36, 3636.36, 3636.36, 3636.36, 3636.36, 3636.36, 3636.36, 3636.36),
	(749, '2020-01-31', 12, 12, 3636.36, 3636.36, 3636.36, 3636.36, 3636.36, 3636.36, 3636.36, 3636.36, 3636.36, 3636.36, 3636.36, 3636.36),
	(750, '2020-01-31', 12, 31, 3636.36, 3636.36, 3636.36, 3636.36, 3636.36, 3636.36, 3636.36, 3636.36, 3636.36, 3636.36, 3636.36, 3636.36),
	(751, '2020-01-31', 12, 39, 3636.36, 3636.36, 3636.36, 3636.36, 3636.36, 3636.36, 3636.36, 3636.36, 3636.36, 3636.36, 3636.36, 3636.36),
	(752, '2020-01-31', 12, 54, 3636.36, 3636.36, 3636.36, 3636.36, 3636.36, 3636.36, 3636.36, 3636.36, 3636.36, 3636.36, 3636.36, 3636.36),
	(753, '2020-01-31', 12, 64, 3636.36, 0.00, 0.00, 0.00, 0.00, 3636.36, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00),
	(754, '2020-01-31', 13, 2, 4403.80, 4403.80, 4403.80, 4403.80, 4403.80, 4403.80, 4403.80, 4377.17, 4377.17, 4377.17, 4377.17, 4366.77),
	(755, '2020-01-31', 13, 3, 4363.78, 4363.67, 4363.64, 4363.64, 4363.64, 4363.64, 4363.64, 4363.64, 4363.64, 4363.64, 4363.64, 4363.64),
	(756, '2020-01-31', 13, 4, 4452.04, 4452.04, 4452.04, 4452.04, 4452.04, 4452.04, 4452.04, 4375.22, 4363.66, 4363.64, 4363.64, 4363.64),
	(757, '2020-01-31', 13, 7, 4366.40, 4366.40, 4363.64, 0.00, 0.00, 4363.64, 4363.64, 4363.64, 4363.64, 4363.64, 4363.64, 4363.64),
	(758, '2020-01-31', 13, 9, 4363.64, 4363.64, 4363.64, 4363.64, 4363.64, 4363.64, 4363.64, 4363.64, 4363.64, 4363.64, 4363.64, 4363.64),
	(759, '2020-01-31', 13, 12, 4387.48, 4387.48, 4387.48, 4387.48, 4387.48, 4387.48, 4377.48, 4377.48, 4369.43, 4365.63, 4365.63, 4364.94),
	(760, '2020-01-31', 13, 13, 4545.45, 4545.45, 4545.45, 4545.45, 4545.45, 4410.13, 4410.13, 4368.91, 4368.91, 4363.68, 4363.66, 4363.65),
	(761, '2020-01-31', 13, 16, 4545.45, 4369.18, 4365.13, 4365.13, 4365.13, 4365.13, 4365.13, 4365.13, 4363.64, 4363.64, 4363.64, 4363.64),
	(762, '2020-01-31', 13, 22, 4421.23, 4421.23, 4421.23, 4421.23, 4421.23, 4421.23, 4380.15, 4371.42, 4371.42, 4365.26, 4364.09, 4364.09),
	(763, '2020-01-31', 13, 39, 4369.09, 4369.09, 4369.09, 4369.09, 4369.09, 4369.09, 4369.09, 4369.09, 4363.89, 4363.89, 4363.89, 4363.89),
	(764, '2020-01-31', 13, 41, 4364.04, 4364.04, 4363.77, 4363.77, 4363.67, 4363.67, 4363.67, 4363.65, 4363.64, 4363.64, 4363.64, 4363.64),
	(765, '2020-01-31', 13, 47, 4426.51, 4426.51, 4426.51, 4426.51, 4426.51, 4426.51, 4370.46, 4367.19, 4367.19, 4365.31, 4365.31, 4365.31),
	(766, '2020-01-31', 13, 50, 4487.35, 4487.35, 4487.35, 4487.35, 4487.35, 4487.35, 4487.35, 4487.35, 4487.35, 4487.35, 0.00, 4363.64),
	(767, '2020-01-31', 13, 54, 4409.20, 4409.20, 4367.73, 4367.73, 4367.73, 4365.19, 4365.19, 4365.19, 4363.95, 4363.69, 4363.69, 4363.69),
	(768, '2020-01-31', 13, 60, 4379.78, 4366.75, 4366.75, 4366.75, 4363.87, 4363.83, 4363.83, 4363.66, 4363.64, 4363.64, 4363.64, 4363.64),
	(769, '2020-01-31', 13, 61, 4449.38, 4400.32, 4400.32, 4400.32, 4400.32, 4400.32, 4400.32, 4376.95, 4376.95, 4366.03, 4363.72, 4363.72),
	(770, '2020-01-31', 14, 2, 6181.82, 6181.82, 6181.82, 6181.82, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00),
	(771, '2020-01-31', 14, 3, 6181.82, 6181.82, 6181.82, 6181.82, 6181.82, 6181.82, 5910.08, 5909.69, 5909.59, 5909.59, 5909.59, 5909.29),
	(772, '2020-01-31', 14, 4, 6181.82, 6181.82, 6181.82, 6181.82, 6181.82, 6181.82, 6181.82, 6181.82, 6181.82, 6181.82, 6181.82, 5909.09),
	(773, '2020-01-31', 14, 5, 6181.82, 6181.82, 6181.82, 6181.82, 0.00, 0.00, 5909.09, 5909.09, 5909.09, 5909.09, 5909.09, 5909.09),
	(774, '2020-01-31', 14, 7, 6181.82, 6181.82, 6181.82, 0.00, 0.00, 0.00, 5909.09, 5909.09, 5909.09, 5909.09, 5909.09, 5909.09),
	(775, '2020-01-31', 14, 9, 6181.82, 6181.82, 6181.82, 6181.82, 6181.82, 6181.82, 5933.80, 5920.72, 5919.71, 5919.71, 5919.71, 5919.71),
	(776, '2020-01-31', 14, 12, 6181.82, 6181.82, 6181.82, 0.00, 0.00, 0.00, 5909.09, 5909.09, 5909.09, 5909.09, 5909.09, 5909.09),
	(777, '2020-01-31', 14, 15, 6181.82, 6181.82, 6181.82, 0.00, 0.00, 0.00, 5909.09, 5909.09, 5909.09, 5909.09, 5909.09, 5909.09),
	(778, '2020-01-31', 14, 22, 6181.82, 6181.82, 6181.82, 6181.82, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00),
	(779, '2020-01-31', 14, 41, 6181.82, 6181.82, 6181.82, 6181.82, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00),
	(780, '2020-01-31', 14, 56, 6181.82, 6181.82, 6181.82, 6181.82, 6181.82, 6181.82, 6181.82, 6181.82, 6181.82, 6181.82, 6181.82, 5909.09),
	(781, '2020-01-31', 15, 3, 3727.27, 3727.27, 3727.27, 3727.27, 3727.27, 3727.27, 3727.27, 3727.27, 3727.27, 3727.27, 3727.27, 3727.27),
	(782, '2020-01-31', 15, 61, 3727.27, 3727.27, 3727.27, 3727.27, 3727.27, 3727.27, 3727.27, 3727.27, 3727.27, 3727.27, 0.00, 0.00),
	(783, '2020-01-31', 16, 3, 50000.09, 50000.09, 50000.09, 50000.09, 50000.09, 50000.09, 50000.00, 50000.00, 50000.00, 50000.00, 50000.00, 50000.00),
	(784, '2020-01-31', 16, 7, 50037.00, 50008.17, 50005.96, 50005.96, 50005.96, 50005.96, 50005.47, 50005.47, 50002.00, 50002.00, 50002.00, 50002.00),
	(785, '2020-01-31', 16, 9, 52552.58, 50854.25, 50854.25, 50854.25, 50854.25, 50854.25, 50854.25, 50854.25, 50854.25, 50854.25, 50854.25, 50854.25),
	(786, '2020-01-31', 16, 30, 50000.00, 50000.00, 50000.00, 50000.00, 50000.00, 50000.00, 50000.00, 50000.00, 50000.00, 50000.00, 50000.00, 50000.00),
	(787, '2020-01-31', 17, 3, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00),
	(788, '2020-01-31', 17, 9, 9727.27, 9727.27, 9727.27, 9727.27, 9727.27, 9727.27, 9727.27, 9727.27, 9727.27, 9727.27, 9727.27, 9727.27),
	(789, '2020-01-31', 17, 12, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00),
	(790, '2020-01-31', 17, 13, 9727.27, 9727.27, 9727.27, 9727.27, 9727.27, 9727.27, 9727.27, 9727.27, 9727.27, 9727.27, 9727.27, 9727.27),
	(791, '2020-01-31', 17, 30, 9727.27, 9727.27, 9727.27, 9727.27, 9727.27, 9727.27, 9727.27, 9727.27, 9727.27, 9727.27, 9727.27, 9727.27),
	(792, '2020-01-31', 17, 41, 9727.27, 9727.27, 9727.27, 9727.27, 9727.27, 9727.27, 9727.27, 9727.27, 9727.27, 9727.27, 9727.27, 9727.27),
	(793, '2020-01-31', 18, 3, 9727.27, 9727.27, 9727.27, 9727.27, 9727.27, 9727.27, 9727.27, 9727.27, 9727.27, 9727.27, 9727.27, 9727.27),
	(794, '2020-01-31', 18, 4, 9363.64, 9363.64, 9363.64, 9363.64, 9363.64, 9363.64, 9363.64, 9363.64, 9363.64, 9363.64, 9363.64, 9363.64),
	(795, '2020-01-31', 18, 5, 9727.27, 9727.27, 9727.27, 9727.27, 9727.27, 9727.27, 9727.27, 9727.27, 9727.27, 9727.27, 9727.27, 9727.27),
	(796, '2020-01-31', 18, 6, 9363.64, 9363.64, 9363.64, 9363.64, 9363.64, 9363.64, 9363.64, 9363.64, 9363.64, 9363.64, 9363.64, 9363.64),
	(797, '2020-01-31', 18, 7, 9720.54, 9721.51, 9721.51, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00),
	(798, '2020-01-31', 18, 9, 9546.40, 9546.40, 9546.40, 9546.40, 9546.40, 9546.40, 9546.40, 9546.40, 9546.40, 9546.40, 9546.40, 9546.40),
	(799, '2020-01-31', 18, 12, 9727.27, 9727.27, 9727.27, 9727.27, 9727.27, 9727.27, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00),
	(800, '2020-01-31', 18, 22, 9363.64, 9363.64, 9363.64, 9363.64, 9363.64, 9363.64, 9363.64, 9363.64, 9363.64, 9363.64, 9363.64, 9363.64),
	(801, '2020-01-31', 18, 41, 9727.27, 9727.27, 9727.27, 9727.27, 9727.27, 9727.27, 9727.27, 9727.27, 9727.27, 9727.27, 9727.27, 9727.27),
	(802, '2020-01-31', 18, 52, 9363.64, 9363.64, 9363.64, 9363.64, 9363.64, 9363.64, 9363.64, 9363.64, 9363.64, 9363.64, 9363.64, 9363.64),
	(803, '2020-01-31', 18, 54, 9363.64, 9363.64, 9363.64, 9363.64, 9363.64, 9363.64, 9363.64, 9363.64, 9363.64, 9363.64, 9363.64, 9363.64),
	(804, '2020-01-31', 18, 63, 9363.64, 9363.64, 9363.64, 9363.64, 9363.64, 9363.64, 9363.64, 9363.64, 9363.64, 9363.64, 9363.64, 9363.64),
	(805, '2020-01-31', 18, 64, 9567.71, 9567.71, 9567.71, 9567.71, 9567.71, 9567.71, 9567.71, 9567.71, 9567.71, 9567.71, 0.00, 0.00),
	(806, '2020-01-31', 18, 65, 9363.64, 9363.64, 9363.64, 9363.64, 9363.64, 9363.64, 9363.64, 9363.64, 9363.64, 9363.64, 9363.64, 9363.64),
	(807, '2020-01-31', 19, 8, 5268.03, 5268.03, 5268.03, 5268.03, 5268.03, 5268.03, 5268.03, 5268.03, 5268.03, 5072.13, 5072.13, 5035.90),
	(808, '2020-01-31', 19, 24, 5454.55, 5454.55, 5454.55, 5454.55, 5454.55, 5454.55, 5123.74, 5123.74, 5123.74, 5001.84, 5001.84, 5001.84),
	(809, '2020-01-31', 20, 3, 6181.82, 6181.82, 6181.82, 6181.82, 6181.82, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00),
	(810, '2020-01-31', 20, 4, 4327.27, 4327.27, 4327.27, 4327.27, 4327.27, 4327.27, 4327.27, 4327.27, 4327.27, 4327.27, 4327.27, 4327.27),
	(811, '2020-01-31', 20, 7, 4327.27, 4327.27, 4327.27, 4327.27, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00),
	(812, '2020-01-31', 20, 9, 4327.27, 4327.27, 4327.27, 4327.27, 4327.27, 4327.27, 4327.27, 0.00, 0.00, 0.00, 0.00, 0.00),
	(813, '2020-01-31', 20, 12, 6181.82, 6181.82, 6181.82, 6181.82, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00),
	(814, '2020-01-31', 21, 8, 6181.82, 6181.82, 6181.82, 6181.82, 6181.82, 6181.82, 6181.82, 0.00, 0.00, 0.00, 0.00, 0.00),
	(815, '2020-01-31', 21, 24, 6181.82, 6181.82, 6181.82, 6181.82, 6181.82, 6181.82, 6181.82, 6181.82, 6181.82, 6181.82, 6181.82, 0.00),
	(816, '2020-01-31', 23, 21, 27272.73, 27272.73, 27272.73, 27272.73, 27272.73, 27272.73, 27272.73, 27272.73, 27272.73, 27272.73, 27272.73, 27272.73),
	(817, '2020-01-31', 24, 3, 45454.55, 45454.55, 45454.55, 0.00, 0.00, 0.00, 46363.64, 0.00, 0.00, 0.00, 43636.36, 43636.36),
	(818, '2020-01-31', 24, 4, 0.00, 45454.55, 45454.55, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00),
	(819, '2020-01-31', 24, 5, 45454.55, 45454.55, 45454.55, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00),
	(820, '2020-01-31', 24, 7, 45454.55, 45454.55, 45454.55, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 43636.36, 0.00),
	(821, '2020-01-31', 24, 9, 45454.55, 45454.55, 45454.55, 0.00, 0.00, 0.00, 46363.64, 0.00, 0.00, 0.00, 43636.36, 0.00),
	(822, '2020-01-31', 24, 10, 45454.55, 45454.55, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00),
	(823, '2020-01-31', 24, 12, 45454.55, 45454.55, 45454.55, 0.00, 0.00, 0.00, 46363.64, 0.00, 0.00, 0.00, 43636.36, 0.00),
	(824, '2020-01-31', 24, 16, 45454.55, 45454.55, 45454.55, 0.00, 0.00, 0.00, 46363.64, 0.00, 0.00, 0.00, 0.00, 0.00),
	(825, '2020-01-31', 24, 31, 50909.09, 50909.09, 0.00, 0.00, 0.00, 0.00, 46363.64, 0.00, 0.00, 0.00, 43636.36, 0.00),
	(826, '2020-01-31', 24, 42, 50909.09, 50909.09, 50909.09, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00),
	(827, '2020-01-31', 24, 47, 50909.09, 50909.09, 0.00, 0.00, 0.00, 0.00, 50909.09, 0.00, 0.00, 0.00, 0.00, 0.00),
	(828, '2020-01-31', 24, 54, 45454.55, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00),
	(829, '2020-01-31', 24, 76, 50909.09, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00),
	(830, '2020-01-31', 24, 86, 45454.55, 0.00, 0.00, 0.00, 0.00, 0.00, 46363.64, 0.00, 0.00, 0.00, 0.00, 0.00),
	(831, '2020-03-31', 12, 60, NULL, NULL, 3636.36, 3636.36, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00),
	(832, '2020-04-30', 13, 68, NULL, NULL, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00),
	(833, '2020-04-30', 26, 7, NULL, NULL, NULL, 5636.36, 5636.36, 5636.36, 5636.36, 5636.36, 5636.36, 5636.36, 5636.36, 5636.36),
	(834, '2020-07-31', 6, 42, NULL, NULL, NULL, NULL, NULL, NULL, 5909.09, 5909.09, 5909.09, 5909.09, 5909.09, 0.00),
	(835, '2020-07-31', 24, 22, NULL, NULL, NULL, NULL, NULL, NULL, 46363.64, 0.00, 0.00, 0.00, 0.00, 0.00),
	(836, '2020-07-31', 24, 41, NULL, NULL, NULL, NULL, NULL, NULL, 46363.64, 0.00, 0.00, 0.00, 0.00, 0.00),
	(837, '2020-07-31', 27, 3, NULL, NULL, NULL, NULL, NULL, NULL, 8636.36, 8636.36, 8636.36, 8636.36, 8636.36, 8636.36),
	(838, '2020-07-31', 27, 7, NULL, NULL, NULL, NULL, NULL, NULL, 8636.36, 8636.36, 8636.36, 8636.36, 8636.36, 8636.36),
	(839, '2020-07-31', 27, 12, NULL, NULL, NULL, NULL, NULL, NULL, 8636.36, 8636.36, 8636.36, 8636.36, 8636.36, 8636.36),
	(840, '2020-07-31', 28, 8, NULL, NULL, NULL, NULL, NULL, NULL, 8636.36, 8636.36, 8636.36, 8636.36, 8636.36, 8636.36),
	(841, '2020-07-31', 28, 24, NULL, NULL, NULL, NULL, NULL, NULL, 8636.36, 8636.36, 8636.36, 8636.36, 8636.36, 8636.36),
	(842, '2020-07-31', 29, 8, NULL, NULL, NULL, NULL, NULL, NULL, 7727.27, 7727.27, 7727.27, 7727.27, 7727.27, 7727.27),
	(843, '2020-07-31', 29, 24, NULL, NULL, NULL, NULL, NULL, NULL, 7727.27, 7727.27, 7727.27, 7727.27, 7727.27, 7727.27),
	(844, '2020-08-31', 13, 15, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4363.64, 4363.64, 4363.64, 4363.64, 4363.64),
	(845, '2020-08-31', 27, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 8636.36, 8636.36, 8636.36, 8636.36, 8636.36),
	(846, '2020-09-30', 10, 70, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 7727.27, 7727.27, 7727.27, 7727.27),
	(847, '2020-09-30', 10, 88, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 7727.27, 7727.27, 7727.27, 7727.27),
	(848, '2020-09-30', 27, 42, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 8636.36, 8636.36, 8636.36, 8636.36),
	(849, '2020-09-30', 27, 56, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 8636.36, 8636.36, 8636.36, 8636.36),
	(850, '2020-09-30', 27, 70, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 8636.36, 8636.36, 8636.36, 8636.36),
	(851, '2020-09-30', 27, 87, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 8636.36, 8636.36, 8636.36, 8636.36),
	(852, '2020-10-31', 13, 30, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4363.64, 4363.64, 4363.64),
	(853, '2020-10-31', 27, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 8636.36, 8636.36, 8636.36),
	(854, '2020-10-31', 27, 15, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 8636.36, 8636.36, 8636.36),
	(855, '2020-10-31', 27, 58, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 8636.36, 8636.36, 8636.36),
	(856, '2020-11-30', 1, 89, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 8545.45, 8090.91),
	(857, '2020-11-30', 24, 75, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 43636.36, 0.00),
	(858, '2020-11-30', 27, 13, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 8636.36, 8636.36);
/*!40000 ALTER TABLE `nd_tutup_buku_detail` ENABLE KEYS */;

-- Dumping structure for table favourtdj_system.nd_user
DROP TABLE IF EXISTS `nd_user`;
CREATE TABLE IF NOT EXISTS `nd_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(70) DEFAULT NULL,
  `nama` varchar(70) DEFAULT NULL,
  `alamat` varchar(100) DEFAULT NULL,
  `telepon` varchar(50) DEFAULT NULL,
  `posisi_id` int(11) DEFAULT NULL,
  `time_start` time NOT NULL DEFAULT '07:00:00',
  `time_end` time NOT NULL DEFAULT '18:00:00',
  `printer_list_id` int(3) DEFAULT NULL,
  `status_aktif` int(1) DEFAULT NULL,
  `PIN` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

-- Dumping data for table favourtdj_system.nd_user: ~3 rows (approximately)
DELETE FROM `nd_user`;
/*!40000 ALTER TABLE `nd_user` DISABLE KEYS */;
INSERT INTO `nd_user` (`id`, `username`, `password`, `nama`, `alamat`, `telepon`, `posisi_id`, `time_start`, `time_end`, `printer_list_id`, `status_aktif`, `PIN`) VALUES
	(1, 'Testing', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, NULL, 1, '02:30:00', '23:29:00', 1, 1, '123456'),
	(4, 'andi', '554f1bf7798a776c40432626510765b5', NULL, NULL, NULL, 2, '02:30:00', '17:30:00', NULL, 1, NULL),
	(5, 'verza', '554f1bf7798a776c40432626510765b5', NULL, NULL, NULL, 2, '02:30:00', '17:30:00', NULL, 1, NULL);
/*!40000 ALTER TABLE `nd_user` ENABLE KEYS */;

-- Dumping structure for table favourtdj_system.nd_user_access_time
DROP TABLE IF EXISTS `nd_user_access_time`;
CREATE TABLE IF NOT EXISTS `nd_user_access_time` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `time_start` time DEFAULT NULL,
  `time_end` time DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table favourtdj_system.nd_user_access_time: ~0 rows (approximately)
DELETE FROM `nd_user_access_time`;
/*!40000 ALTER TABLE `nd_user_access_time` DISABLE KEYS */;
/*!40000 ALTER TABLE `nd_user_access_time` ENABLE KEYS */;

-- Dumping structure for table favourtdj_system.nd_warna
DROP TABLE IF EXISTS `nd_warna`;
CREATE TABLE IF NOT EXISTS `nd_warna` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `warna_beli` varchar(50) DEFAULT NULL,
  `warna_jual` varchar(50) DEFAULT NULL,
  `kode_warna` varchar(7) DEFAULT NULL,
  `status_aktif` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=97 DEFAULT CHARSET=latin1;

-- Dumping data for table favourtdj_system.nd_warna: ~87 rows (approximately)
DELETE FROM `nd_warna`;
/*!40000 ALTER TABLE `nd_warna` DISABLE KEYS */;
INSERT INTO `nd_warna` (`id`, `warna_beli`, `warna_jual`, `kode_warna`, `status_aktif`) VALUES
	(1, 'LAKE GREEN', 'LAKE GREEN', '#008264', 1),
	(2, 'KUNING MAS', 'KUNING MAS', '#FF983E', 1),
	(3, 'HITAM PEKAT', 'HITAM PEKAT', '#000000', 1),
	(4, 'MERAH', 'MERAH', '#C02926', 1),
	(5, 'SILVER', 'SILVER', '#BFC2CB', 1),
	(6, 'LT GREY', 'LT GREY', '#D4D8DE', 1),
	(7, 'PUTIH', 'PUTIH', '#FFFFFF', 1),
	(8, 'VOLT', 'VOLT', '#CEF300', 1),
	(9, 'NAVY', 'NAVY', '#2C394B', 1),
	(10, 'SKY BLUE', 'SKY BLUE', '#4490B9', 1),
	(12, 'BENHUR', 'BENHUR', '#015AAD', 1),
	(13, 'TNI', 'TNI', '#49523C', 1),
	(14, 'AQUA', 'AQUA', '#1DC3CD', 1),
	(15, 'TURQUOISE', 'TURQUOISE', '#008AA0', 1),
	(16, 'ABU TUA', 'ABU TUA', '#494D4E', 1),
	(17, 'SILVER GOLD', 'SILVER GOLD', '#786D4B', 1),
	(18, 'LIME', 'LIME', '#6A4E39', 1),
	(19, 'CREAM MUDA', 'CREAM MUDA', '#DDC694', 1),
	(20, 'SILVER GREEN', 'SILVER GREEN', '#7D7F65', 1),
	(21, 'BIRU MUDA', 'BIRU MUDA', '#CAE1E7', 1),
	(22, 'KUNING TERANG', 'KUNING TERANG', '#F3D93D', 1),
	(24, 'FLASH ORANGE', 'FLASH ORANGE', '#F03D23', 1),
	(29, 'ATI', 'ATI', NULL, 0),
	(30, 'ABU MUDA', 'ABU MUDA', '#849597', 1),
	(31, 'BOTOL', 'BOTOL', '#33574E', 1),
	(32, 'ROSE MUDA', 'ROSE MUDA', '#DFB4B4', 1),
	(33, 'VIOLET MUDA', 'VIOLET MUDA', '#C6B6CF', 1),
	(34, 'ROSE', 'ROSE', '#FFE4E1', 1),
	(35, 'KUNING MUDA', 'KUNING MUDA', '#E0E0AA', 1),
	(36, 'COKELAT SUSU', 'COKELAT SUSU', '#BEAC95', 1),
	(37, 'MINT', 'MINT', '#ADCAC5', 1),
	(38, 'VIOLET', 'VIOLET', '#EE82EE', 0),
	(39, 'VIOLET TUA', 'VIOLET TUA', '#575081', 1),
	(40, 'BIRU', 'BIRU', '#0000FF', 0),
	(41, 'ORANGE', 'ORANGE', '#BD3F10', 1),
	(42, 'TOSCA', 'TOSCA', '#01756D', 1),
	(43, 'NAVY MUDA', 'NAVY MUDA', '#1D4562', 1),
	(44, 'SALEM', 'SALEM', '#ECDCB3', 1),
	(45, 'ANGGUR', 'ANGGUR', '#61314D', 1),
	(46, 'MAGENTA', 'MAGENTA', '#BB7880', 1),
	(47, 'FUJI', 'FUJI', '#515549', 1),
	(48, 'OLIVE', 'OLIVE', '#383B34', 1),
	(49, 'KENARI', 'KUNING TERANG', '#FFFF00', 0),
	(50, 'ABU MK', 'ABU MK', '#808080', 1),
	(51, 'BEIGE', 'BEIGE', '#807D5D', 1),
	(52, 'KHAKI', 'KHAKI', '#4C4E42', 1),
	(53, 'COKELAT TANAH', 'COKELAT TANAH', '#9b7653', 1),
	(54, 'ATI', 'ATI', '#86272D', 1),
	(55, 'TAUPE', 'TAUPE', '#483C32', 1),
	(56, 'DK GREY', 'DK GREY', '#A9A9A9', 1),
	(57, 'BLACK TEA', 'BLACK TEA', '#3C3635', 1),
	(58, 'PINK', 'PINK', '#D9628D', 1),
	(59, 'HIJAU MUDA', 'HIJAU MUDA', '#51FF0D', 1),
	(60, 'SAND', 'SAND', '#c2b280', 1),
	(61, 'KOPI', 'KOPI', '#6f4e37', 1),
	(62, 'ANTRA', 'ANTRA', '#383E42', 1),
	(63, 'EBONY', 'EBONY', '#3E424D', 1),
	(64, 'KUNING SEDANG', 'KUNING SEDANG', '#EBC36A', 1),
	(65, 'BROWN', 'BROWN', '#A52A2A', 1),
	(66, 'HIJAU TERANG', 'HIJAU TERANG', '#00FF00', 1),
	(67, 'ROSE SEDANG', 'ROSE SEDANG', '#ECABB4', 1),
	(68, 'PEMDA', 'PEMDA', '#473328', 1),
	(69, 'BROKEN WHITE', 'BROKEN WHITE', '#E0E8DC', 1),
	(70, 'PURPLE', 'PURPLE', '#4F4880', 1),
	(71, 'VIOLET SEDANG', 'VIOLET SEDANG', '#947F95', 1),
	(72, 'BIRU SEDANG', 'BIRU SEDANG', '#64A0AC', 1),
	(73, 'COKELAT TUA', 'COKELAT TUA', '#6A4E39', 1),
	(74, 'SILVER MUDA', 'SILVER MUDA', '#AAA9AD', 0),
	(75, 'UNGU', 'UNGU', '#BF00FF', 1),
	(76, 'HIJAU SUPER', 'HIJAU SUPER', '#2E8B57', 1),
	(77, 'PELITUR', 'PELITUR', '#997E41', 1),
	(78, 'TOSCA MUDA', 'TOSCA MUDA', '#3DB3A6', 1),
	(79, 'ABU SEMEN', 'ABU SEMEN', '#5E796D', 1),
	(80, 'OLIVE CONTOH', 'OLIVE CONTOH', '#909000', 1),
	(81, 'ARMY CKJ', 'ARMY CKJ', '#63563B', 1),
	(82, 'PEACH', 'PEACH', '#C86A59', 1),
	(83, 'COKLAT MUDA', 'COKLAT MUDA', '#8E795C', 1),
	(84, 'HIJAU PUPUS', 'HIJAU PUPUS', '#A9B154', 1),
	(85, 'PEACH', 'PEACH', NULL, 0),
	(86, 'KUNING', 'KUNING', '#FFFF00', 1),
	(87, 'SUNKIST', 'SUNKIST', '#E4D100', 1),
	(88, 'KHAKI CKJ', 'KHAKI CKJ', NULL, 1),
	(89, 'HIJAU DAUN ', 'HIJAU DAUN', NULL, 1),
	(90, 'GO GREEN', 'GO GREEN', '#5CC12F', 1),
	(91, 'DEEP MAROON', 'DEEP MAROON', '#422331', 1),
	(92, 'SPORT GREEN', 'SPORT GREEN', '#1C793E', 1),
	(93, 'SPORT RED', 'SPORT RED', '#B22534', 1),
	(94, 'TES', 'TES', 'A', 0),
	(95, 'TES', 'TES', 'A', 0),
	(96, 'ASDSAD', 'ASD', 'ASD', 1);
/*!40000 ALTER TABLE `nd_warna` ENABLE KEYS */;

-- Dumping structure for table favourtdj_system.temp_customer_updgrade
DROP TABLE IF EXISTS `temp_customer_updgrade`;
CREATE TABLE IF NOT EXISTS `temp_customer_updgrade` (
  `id` int(11) NOT NULL,
  `customer_id` smallint(6) NOT NULL,
  `alamat` varchar(1000) DEFAULT NULL,
  `npwp` varchar(200) DEFAULT NULL,
  `nik` varchar(16) DEFAULT NULL,
  `kota` varchar(200) DEFAULT NULL,
  `provinsi` varchar(100) DEFAULT NULL,
  `kode_pos` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table favourtdj_system.temp_customer_updgrade: ~121 rows (approximately)
DELETE FROM `temp_customer_updgrade`;
/*!40000 ALTER TABLE `temp_customer_updgrade` DISABLE KEYS */;
INSERT INTO `temp_customer_updgrade` (`id`, `customer_id`, `alamat`, `npwp`, `nik`, `kota`, `provinsi`, `kode_pos`) VALUES
	(1, 24, 'TAMAN PALEM LESTARI RUKO FANTASY Blok Z2 No.51 RT:000 RW:000 Kel.CENGKARENG BARAT Kec.CENGKARENG', '02.885.576.5-034.000', '', 'Jakarta Barat', 'DKI JAKARTA RAYA ', '11730'),
	(2, 23, 'JL. KH. ZAINUL ARIFIN KOMP.KTPG INDAH Blok B.II No.21 RT:000 RW:000 Kel.KRUKUT Kec.TAMANSARI', '01.539.941.3-038.000', '', 'JAKARTA BARAT', 'DKI JAKARTA RAYA ', '0'),
	(3, 89, 'JL. BATIK YONAS I BLOK B.11 PERUM MITRA BATIK Blok - No.- RT:002 RW:013 Kel.MULYASARI Kec.TAMANSARI', '21.068.121.9-425.000', '', 'TASIKMALAYA', 'JAWA BARAT', '0'),
	(4, 50, 'LINGK. MUDING KAJA, JL. MUDING BATU SANGIAN Blok - No.10 RT:000 RW:000 Kel.KEROBOKAN KAJA Kec.KUTA UTARA', '02.151.977.2-059.000', '', 'BADUNG', 'BALI', '80361'),
	(5, 132, 'JL JABABEKA IIA KW IND JABABEKA Blok C No.11G/H RT:000 RW:000 Kel.PASIR GOMBONG Kec.CIKARANG UTARA', '21.046.919.3-414.000', '', 'BEKASI', 'JAWA BARAT', '0'),
	(6, 33, 'RUKO CBD, JL NIAGA RAYA, JABABEKA II Blok B No.5 RT:000 RW:000 Kel.PASIRSARI Kec.CIKARANG SELATAN', '21.144.677.8-413.000', '', 'BEKASI', 'JAWA BARAT', '0'),
	(7, 85, 'JL. PASAR PAGI I Blok - No.19 RT:000 RW:000 Kel.ROA MALAKA Kec.TAMBORA', '06.717.456.5-033.001', '', 'JAKARTA BARAT', 'DAERAH KHUSUS IBUKOTA JAKARTA', '0'),
	(8, 42, 'JL. H. KURDI SELATAN II Blok - No.9 RT:006 RW:006 Kel.PELINDUNG HEWAN Kec.ASTANA ANYAR', '31.712.612.6-422.000', '', 'KOTA BANDUNG', ' JAWA BARAT', '0'),
	(9, 61, 'PUSAT NINAGA CIBODAS, JL RAYA SERANG KM 3 Blok D No.11 RT:001 RW:009 Kel.CIBODAS Kec.CIBODAS', '21.137.107.5-402.000', '', 'TANGERANG', 'BANTEN', '0'),
	(10, 57, 'JL. BATUNUNGGAL INDAH VIII Blok - No.88 RT:003 RW:006 Kel.MENGGER Kec.BANDUNG KIDUL', '76.301.877.7-424.000', '', 'KOTA BANDUNG', 'JAWA BARAT', '0'),
	(11, 84, 'KOMP. KETAPANG INDAH Blok B2/22 No.- RT:000 RW:000 Kel.KRUKUT Kec.TAMANSARI', '01.319.105.1-038.000', '', 'JAKARTA BARAT', ' DKI JAKARTA RAYA', '0'),
	(12, 97, 'RUKO PESING KONENG Blok - No.17/G RT:014 RW:008 Kel.KEDOYA UTARA Kec.KEBON JERUK', '02.248.377.0-039.000', '', 'JAKARTA BARAT', ' DKI JAKARTA', '11520'),
	(13, 43, 'JL. BABAKAN TAROGONG Blok - No.196B RT:008 RW:004 Kel.BABAKAN ASIH Kec.BOJONGLOA KALER', '75.071.449.5-422.000', '', 'BANDUNG', 'JAWA BARAT', '0'),
	(14, 44, 'JL. RAYA CITAPEN KP. GERANG Blok - No.- RT:001 RW:002 Kel.CITAPEN Kec.CIHAMPELAS', '76.065.581.1-421.000', '', 'BANDUNG BARAT', 'JAWA BARAT', '0'),
	(15, 62, 'JL. INHOFTANK KOMPLEK JATI PERMAI Blok - No.56 RT:000 RW:000 Kel.- Kec.PELINDUNG HEWAN', '01.666.032.6-422.000', '', 'BANDUNG', ' JAWA BARAT', '0'),
	(16, 104, 'TAMAN CIBADUYUT INDAH Blok G No.108 RT:004 RW:019 Kel.CANGKUANG KULON Kec.DAYEUHKOLOT', '31.682.891.2-445.000', '', 'BANDUNG ', 'JAWA BARAT', '0'),
	(17, 15, 'KOMP TAMAN KOPO INDAH III RUKO Blok D No.57 RT:008 RW:009 Kel.RAHAYU Kec.MARGAASIH', '02.563.966.7-445.000', '', 'BANDUNG', 'JAWA BARAT', '0'),
	(18, 94, 'TAMAN KOTA Blok - No.32 RT:001 RW:008 Kel.KEDAUNG KALI ANGKE Kec.CENGKARENG ', '82.685.650.2-034.000', '', 'JAKARTA BARAT', ' DKI JAKARTA', '0'),
	(19, 46, 'JL. H KURDI BARAT Blok I No.3A RT:004 RW:006 Kel.PELINDUNG HEWAN Kec.ASTANA ANYAR', '21.071.895.3-422.000', '', 'BANDUNG', 'JAWA BARAT ', '0'),
	(20, 96, 'JL.MUARA KARANG Blok F4 No.26 RT:000 RW:000 Kel.PLUIT Kec.PENJARINGAN', '01.837.356.3-046.000', '', 'JAKARTA UTARA', 'DKI JAKARTA RAYA', '0'),
	(21, 25, 'JL. OTTO ISKANDARDINATA Blok - No.126 RT:000 RW:000 Kel.KEBON JERUK Kec.ANDIR', '06.588.812.5-428.000', '', 'BANDUNG', 'JAWA BARAT', '0'),
	(22, 60, 'JL. KAPTEN KASIHIN Blok - No.09 RT:001 RW:001 Kel.PLANDAAN Kec.KEDUNGWARU', '34.168.891.9-629.000', '', 'TULUNGAGUNG', 'Jawa Timur', '0'),
	(23, 76, 'JL. DIAN ELOK RAYA Blok - No.28 RT:003 RW:012 Kel.BABAKAN Kec.BABAKAN CIPARAY', '01.772.454.3-422.000', '', 'BANDUNG', 'JAWA BARAT', '0'),
	(24, 56, 'JL. KEUTAMAAN DALAM Blok - No.22 B RT:000 RW:000 Kel.KRUKUT Kec.TAMANSARI', '49.180.205.4-037.000', '', 'JAKARTA BARAT', 'JAKARTA', '0'),
	(25, 8, 'JL. KETAPANG Blok 3 No.15 RT:000 RW:000 Kel.NYAMPLUNGAN Kec.PABEAN CANTIAN', '06.137.501.0-613.000', '', 'SURABAYA', 'JAWA TIMUR', '0'),
	(26, 34, 'JL. GUNUNG KELIR Blok - No.7 RT:000 RW:000 Kel.PANGLAYUNGAN Kec.CIPEDES', '02.587.159.1-425.000', '', 'TASIKMALAYA', 'JAWA BARAT', '0'),
	(27, 36, 'JL. BIHBUL RAYA KM. 6,7 Blok - No.70 RT:000 RW:000 Kel.SAYATI Kec.MARGAHAYU', '06.315.705.1-445.000', '', 'BANDUNG', 'JAWA BARAT', '40228'),
	(28, 138, 'JL. TERS. MARTANEGARA Blok - No.7 RT:000 RW:000 Kel.GUMURUH Kec.BATUNUNGGAL', '01.822.916.1-424.000', '', 'BANDUNG', 'JAWA BARAT', '0'),
	(29, 74, 'JL. CRISANT II Blok - No.41 RT:003 RW:012 Kel.CENGKARENG', '02.670.965.9-034.000', '', 'JAKARTA BARAT', 'DKI JAKARTA RAYA', '11750'),
	(30, 98, 'JL. SITU INDAH Blok - No.11 RT:006 RW:007 Kel.SUKAHAJI Kec.BABAKAN CIPARAY', '15.194.878.3-722.000', '', 'BANDUNG', 'JAWA BARAT', '0'),
	(31, 48, 'OTTO ISKANDARDINATA Blok - No.164 RT:008 RW:001 Kel.KEBON JERUK Kec.ANDIR', '84.700.013.0-428.000', '', 'BANDUNG', 'JAWA BARAT', '0'),
	(32, 64, 'DUSUN GLAGAH LOR Blok - No.- RT:001 RW:000 Kel.TAMANAN Kec.BANGUNTAPAN', '31.402.617.0-543.000', '', 'BANTUL', 'DI. YOGYAKARTA', '0'),
	(33, 32, 'RAYA CITAPEN Blok - No.- RT:001 RW:002 Kel.CITAPEN Kec.CIHAMPELAS', '31.487.863.8.421.000', '', 'BANDUNG', 'JAWA BARAT', '0'),
	(34, 35, 'BUNGURAN Blok - No.63H RT:000 RW:000 Kel.BONGKARAN Kec.PABEAN CANTIAN', '04.024.528.4-613.001', '', 'SURABAYA', 'JAWA TIMUR', '0'),
	(35, 39, 'JL. AGRONOMI Blok A-4 No.15B RT:001 RW:015 Kel.TANIMULYA Kec.NGAMPRAH', '02.761.102.9-421.000', '', 'BANDUNG', 'JAWA BARAT', '0'),
	(36, 125, 'JL. JAMIKA Blok - No.96 RT:005 RW:007 Kel.JAMIKA BOJONG Kec.LOA KALER', '31.491.350.0-422.000', '', 'BANDUNG', 'JAWA BARAT', '0'),
	(37, 100, 'JL. BKR LANTAI II Blok - No.76 RT:000 RW:000 Kel.PASIRLUYU Kec.REGOL', '02.084.344.7-424.000', '', 'BANDUNG', 'JAWA BARAT', '0'),
	(38, 13, 'JL. MALEBER BARAT Blok - No.105 RT:001 RW:006 Kel.MALEBER Kec.ANDIR', '09.432.867.1-428.000', '', 'BANDUNG', 'JAWA BARAT', '0'),
	(39, 16, 'KP. PANYIRAPAN Blok - No.- RT:001 RW:002 Kel.PANYIRAPAN Kec.SOREANG ', '66.164.927.7-445.000', '', 'BANDUNG', 'JAWA BARAT', '0'),
	(40, 53, 'KAMPUNG GANDOANG Blok - No.- RT:002 RW:004 Kel.GANDOANG Kec.CILEUNGSI', '02.171.127.0-436.000', '', 'BOGOR', 'JAWA BARAT', '16820'),
	(41, 90, 'KP. PARUNG HALANG Blok - No.- RT:006 RW:001 Kel.ANDIR Kec.BALEENDAH', '64.341.540.9-445.000', '', 'BANDUNG', 'JAWA BARAT', '0'),
	(42, 91, 'GG. WINATU Blok - No.83 RT:002 RW:001 Kel.LEMBANG Kec.LEMBANG', '44.280.962.0-421.000', '', 'BANDUNG', 'JAWA BARAT', '0'),
	(43, 118, 'JL. KARANG SARI Blok - No.11 RT:000 RW:000 Kel.PASTEUR Kec.SUKAJADI', '31.197.524.7-428.000', '', 'BANDUNG', 'JAWA BARAT', '0'),
	(44, 2, 'KP BURUJUL Blok - No.- RT:004 RW:005 Kel.MEKARRAHAYU Kec.MARGAASIH', '24.091.808.6-455.000', '', 'BANDUNG', 'JAWA BARAT', '0'),
	(45, 83, 'KP. GANDASOLI Blok - No.- RT:004 RW:007 Kel.GANDASARI Kec.KATAPANG', '73.240.833.1-445.000', '', 'BANDUNG', 'JAWA BARAT', '0'),
	(46, 123, 'JL. SARIJADI Blok 03 No.60 RT:006 RW:009 Kel.SARIJADI Kec.SUKASARI', '02.244.129.9-428.000', '', 'BANDUNG', 'JAWA BARAT', '0'),
	(47, 3, 'KEMBAR TENGAH Blok - No.28 RT:000 RW:000 Kel.CIGERELENG Kec.REGOL', '19.983.380.7-424.000', '', 'BANDUNG', 'JAWA BARAT', '0'),
	(48, 99, 'JL.SUKARAJA 2 Blok - No.15 RT:005 RW:006 Kel.SUKARAJA Kec.CICENDO', '80.363.956.6-428.000', '', 'BANDUNG', 'JAWA BARAT', '0'),
	(49, 54, 'JAGERAN Blok - No.- RT:003 RW:005 Kel.KETELAN Kec.BANJARSARI', '76.615.820.8-526.000', '', 'SURAKARTA', 'JAWA TENGAH', '0'),
	(50, 66, 'JL. PETITENGET Blok - No.22 RT:000 RW:000 Kel.KEROBOKAN Kec.KUTA UTARA', '02.152.644.7-906.000', '', 'BADUNG', 'BALI', '0'),
	(51, 10, 'JL. AKSAN Blok - No.24 RT:006 RW:010 Kel.BABAKAN CIPARAY', '31.197.083.4-422.000', '', 'BANDUNG', 'JAWA BARAT', '40221'),
	(52, 38, 'JL. CIPAMOKOLAN Blok - No.05 RT:005 RW:001 Kel.RANCASARI', '02.568.467.1-429.000', '', 'BANDUNG', 'JAWA BARAT', ''),
	(53, 79, 'JL. PUSPITA Blok - No.10 RT:000 RW:000 Kel.GUNDIH Kec.BUBUTAN', '01.677.679.1-614.000', '', 'SURABAYA', 'JAWA TIMUR', '60172'),
	(54, 5, '', '', '3205090911750002', '', '', ''),
	(55, 7, '', '', '3204101510590005', '', '', ''),
	(56, 9, '', '', '3573012501680005', '', '', ''),
	(57, 70, '', '', '3326163006370121', '', '', ''),
	(58, 81, '', '', '3273052306860005', '', '', ''),
	(59, 59, '', '', '3278014203730028', '', '', ''),
	(60, 11, '', '', '3273191110890002', '', '', ''),
	(61, 113, '', '', '3205095105740001', '', '', ''),
	(62, 128, '', '', '3273140605920004', '', '', ''),
	(63, 134, '', '', '3309090405900003', '', '', ''),
	(64, 12, '', '', '3204100510760012', '', '', ''),
	(65, 102, '', '', '3327130405830015', '', '', ''),
	(66, 4, '', '', '3205090203710001', '', '', ''),
	(67, 49, '', '', '3271041301850008', '', '', ''),
	(68, 47, '', '', '3278060503940003', '', '', ''),
	(69, 139, '', '', '3205091404750002', '', '', ''),
	(70, 95, '', '', '3604110702710002', '', '', ''),
	(71, 117, '', '', '3578212711540001', '', '', ''),
	(72, 146, '', '', '3517015010810001', '', '', ''),
	(73, 17, '', '', '3273150607940002', '', '', ''),
	(74, 67, '', '', '3201010309780006', '', '', ''),
	(75, 1, '', '', '3278071701140005', '', '', ''),
	(76, 131, '', '', '3578086707890004', '', '', ''),
	(77, 18, '', '', '3278032208810005', '', '', ''),
	(78, 19, '', '', '3374010612520001', '', '', ''),
	(79, 6, '', '', '3278070101740006', '', '', ''),
	(80, 14, '', '', '3278030604560006', '', '', ''),
	(81, 69, '', '', '3278070310730001', '', '', ''),
	(82, 114, '', '', '3276056808720001', '', '', ''),
	(83, 21, '', '', '3171071611760003', '', '', ''),
	(84, 140, '', '', '3205096308660003', '', '', ''),
	(85, 30, '', '', '3278074611550004', '', '', ''),
	(86, 106, '', '', '3275046302570005', '', '', ''),
	(87, 72, '', '', '3576022507800004', '', '', ''),
	(88, 143, '', '', '6371020201830009', '', '', ''),
	(89, 28, '', '', '3578081001780005', '', '', ''),
	(90, 41, '', '', '3173060403750016', '', '', ''),
	(91, 55, '', '', '3327110308960009', '', '', ''),
	(92, 73, '', '', '9301021503840009', '', '', ''),
	(93, 109, '', '', '3273132711540001', '', '', ''),
	(94, 121, '', '', '3311094811710001', '', '', ''),
	(95, 26, '', '', '3372041001560001', '', '', ''),
	(96, 88, '', '', '3326161510770001', '', '', ''),
	(97, 135, '', '', '3271044206990018', '', '', ''),
	(98, 136, '', '', '3204106705720006', '', '', ''),
	(99, 27, '', '', '3204067005590001', '', '', ''),
	(100, 58, '', '', '5171031112660020', '', '', ''),
	(101, 71, '', '', '3374092908800003', '', '', ''),
	(102, 145, '', '', '3205090408920003', '', '', ''),
	(103, 45, '', '', '3173042009810006', '', '', ''),
	(104, 51, '', '', '3404031906920002', '', '', ''),
	(105, 37, '', '', '3578275308780001', '', '', ''),
	(106, 82, '', '', '3278065901950002', '', '', ''),
	(107, 141, '', '', '3205093004650001', '', '', ''),
	(108, 86, '', '', '3573030506640004', '', '', ''),
	(109, 68, '', '', '3204324909950001', '', '', ''),
	(110, 133, '', '', '3273305004560004', '', '', ''),
	(111, 29, '', '', '3327131103810009', '', '', ''),
	(112, 137, '', '', '3375041707700003', '', '', ''),
	(113, 80, '', '', '5171040911810001', '', '', ''),
	(114, 116, '', '', '5171040103520001', '', '', ''),
	(115, 65, '', '', '3171082204730002', '', '', ''),
	(116, 63, '', '', '3278012203720003', '', '', ''),
	(117, 152, '', '', '3202160803670004', '', '', ''),
	(118, 31, '', '', '3171071407680001', '', '', ''),
	(119, 20, '', '', '3311092211580002', '', '', ''),
	(120, 78, '', '', '3273162903640002', '', '', ''),
	(121, 75, '', '', '3175030812670010', '', '', '');
/*!40000 ALTER TABLE `temp_customer_updgrade` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
