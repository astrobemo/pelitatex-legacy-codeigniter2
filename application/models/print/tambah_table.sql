CREATE TABLE IF NOT EXISTS `nd_penjualan_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `penjualan_id` int(11) DEFAULT NULL,
  `pengiriman_id` int(11) DEFAULT NULL,
  `tgl_print` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=latin1;