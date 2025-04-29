<?
//header('Access-Control-Allow-Origin:*');
if (isset($_SERVER['HTTP_ORIGIN'])) {
  $http_origin = $_SERVER['HTTP_ORIGIN'];


if ($http_origin == "https://sistem.favourtdj.com" || $http_origin == "https://sistem.blessingtdj.com/" || $http_origin == "https://sistem.gracetdj.com/")
{  
    header("Access-Control-Allow-Origin: $http_origin");
}
header('Access-Control-Allow-Headers:origin, x-requested-with, content-type');
header('Access-Control-Allow-Methods:PUT, GET, POST, DELETE, OPTIONS');
?>
<?
$data['message'] = array();
$data['satuan'] = array();
$data['po_pembelian_report_detail'] = array();
$key = $_GET['key'];
$batch_id = 0;
$tanggal_start = '';
$tanggal_end = '';
$test_id = 0;

if($key == 123){
  
  $batch_id = $_GET['batch_id'];
  $tanggal_start = $_GET['tanggal_start'];
  $tanggal_end = $_GET['tanggal_end'];
  $test_id = $_GET['test_id'];
	
  $data['message'] = array(
  "title" => "Welcome to Example.com!" ,
	"body" => "The example.com virtual host is working!" );


$link = mysql_connect('localhost', 'root', '4*;eZd');
if (!$link) {
    die('Could not connect: ' . mysql_error());
}

mysql_select_db('favourtdj_system2019');

/*===========================================================================*/

if ($tanggal_start != '' && $tanggal_end != '' ) {
  $hasil = mysql_query("select * from nd_penjualan where tanggal>='$tanggal_start' AND tanggal<='$tanggal_end'");
  //$data['satuan2']=$hasil->fetch_array();
  while($row = mysql_fetch_assoc($hasil)){
  	$data['satuan'][]=$row;
  }
}

/*===========================================================================*/

if ($batch_id != 0 && $batch_id != '') {
  $po_pembelian_report_detail = mysql_query("SELECT 
      if(a.tipe_barang = 1, ifnull(d.qty_beli,0), ifnull(tA.qty_beli,0))  as qty_beli, 
      if(a.tipe_barang = 1, ifnull(d.barang_id,0), ifnull(tA.barang_id_baru,0))  as barang_id, 
      if(a.tipe_barang = 1,d.pembelian_id, tA.pembelian_id) as pembelian_id, 
      if(a.tipe_barang = 1,d.no_faktur, tA.no_faktur) as no_faktur, 
      if(a.tipe_barang = 1,d.tanggal_beli, tA.tanggal_beli) as tanggal_beli, 
      if(a.tipe_barang = 1,d.tanggal_datang, tA.tanggal_datang) as tanggal_datang, 
      if(a.tipe_barang = 1, ifnull(d.harga_beli,0), ifnull(tA.harga_beli,0)) as harga_beli, 
      if(a.tipe_barang = 1, a.warna_id, tA.warna_id) as warna_id,
        b.id as batch_id , ifnull(a.qty,0) as po_qty, c.id as detail_id, po_number, nama_supplier, satuan_id, 
      f.nama as nama_satuan, nama_barang, e.warna_beli as nama_warna,
      tipe_barang, nama_baru, tipe_barang, a.id as po_pembelian_warna_id, 
      DATE_FORMAT(locked_date,'%Y-%m-%d') as locked_date, username, OCKH, 
      b.id as batch_id, if(a.tipe_barang = 1,c.harga, tA.harga) as harga_po
      FROM (
        SELECT t1.*, concat(if(t2.tanggal >= '2019-09-27', concat(ifnull(pre_po,''),t4.kode,'/',DATE_FORMAT(t2.tanggal,'%y'),'/',LPAD(po_number,3,'0')) ,concat(LPAD(po_number,3,'0'),'/',DATE_FORMAT(t2.tanggal,'%m'),'/',DATE_FORMAT(t2.tanggal,'%y'))),'-',batch,if(revisi > 1,concat('R',revisi-1),'') ) as po_number, t4.nama as nama_supplier
        FROM (
          SELECT *
          FROM nd_po_pembelian_batch
          WHERE id = $batch_id
          ) t1
        LEFT JOIN nd_po_pembelian t2
        ON t1.po_pembelian_id = t2.id
        LEFT JOIN nd_toko t3
        ON t2.toko_id = t3.id
        LEFT JOIN nd_supplier t4
        ON t2.supplier_id = t4.id
        ) b
      LEFT JOIN (
        SELECT tA.*, tB.nama as nama_baru, tC.username
        FROM nd_po_pembelian_warna tA
        LEFT JOIN nd_barang tB
        ON tA.barang_id_baru = tB.id
        LEFT JOIN nd_user tC
        ON tA.locked_by = tC.id
        ) a
      ON a.po_pembelian_batch_id = b.id
      LEFT JOIN (
        SELECT t1.*, satuan_id, t2.nama as nama_barang
        FROM nd_po_pembelian_detail t1
        LEFT JOIN nd_barang t2
        ON t1.barang_id = t2.id
        ) c
      ON a.po_pembelian_detail_id = c.id
      LEFT JOIN (
        SELECT barang_id, warna_id, group_concat(ifnull(qty,0) ORDER BY tanggal asc) as qty_beli, po_pembelian_batch_id, group_concat(t2.id ORDER BY tanggal asc) as pembelian_id, group_concat(no_faktur ORDER BY tanggal asc) as no_faktur, group_concat(tanggal ORDER BY tanggal asc) as tanggal_beli, group_concat(harga_beli ORDER BY tanggal asc) as harga_beli, group_concat(DATE_FORMAT(tanggal,'%d/%m/%y') ORDER BY tanggal asc) as tanggal_datang
        FROM nd_pembelian_detail t1
        LEFT JOIN nd_pembelian t2
        ON t1.pembelian_id = t2.id
        GROUP BY barang_id, warna_id, po_pembelian_batch_id
        ORDER BY tanggal ASC
        ) d
      ON a.warna_id = d.warna_id
      AND b.id = d.po_pembelian_batch_id
      AND d.barang_id = c.barang_id
      LEFT JOIN (
        SELECT tA.id, qty_beli, no_faktur, tanggal_beli, tanggal_datang, pembelian_id, harga_beli, tA.harga_baru as harga, tA.warna_id, barang_id_baru
        FROM (
          SELECT *
          FROM nd_po_pembelian_warna
          WHERE tipe_barang != 1
          ) tA
        LEFT JOIN (
          SELECT barang_id, warna_id, group_concat(ifnull(qty,0) ORDER BY tanggal asc) as qty_beli, po_pembelian_batch_id, group_concat(t2.id ORDER BY tanggal asc) as pembelian_id, group_concat(no_faktur ORDER BY tanggal asc) as no_faktur, group_concat(tanggal ORDER BY tanggal asc) as tanggal_beli, group_concat(harga_beli ORDER BY tanggal asc) as harga_beli, group_concat(DATE_FORMAT(tanggal,'%d/%m/%y') ORDER BY tanggal asc) as tanggal_datang
          FROM nd_pembelian_detail t1
          LEFT JOIN nd_pembelian t2
          ON t1.pembelian_id = t2.id
          GROUP BY barang_id, warna_id, po_pembelian_batch_id
          ORDER BY tanggal ASC
          ) tB
        ON tA.warna_id = tB.warna_id
        AND tA.po_pembelian_batch_id = tB.po_pembelian_batch_id
        AND tA.barang_id_baru = tB.barang_id
        ) tA
      ON a.id = tA.id
      LEFT JOIN nd_warna e
      ON a.warna_id = e.id
      LEFT JOIN nd_satuan f
      ON c.satuan_id = f.id
      ORDER BY nama_barang, warna_jual asc
  ");

  if ($test_id == 1) {
    echo $batch_id;
    mysql_fetch_array($po_pembelian_report_detail);
  }

  while($row = mysql_fetch_assoc($po_pembelian_report_detail)){
    $data['po_pembelian_report_detail'][]=$row;
  }
}

echo json_encode($data);

mysql_close($link);
}
}else{
echo "<h1>UNDER CONSTRUCTION</h1>";
}
?>
