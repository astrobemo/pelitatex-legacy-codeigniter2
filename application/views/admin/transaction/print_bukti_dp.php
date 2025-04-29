<?

$nama_toko = '';
$alamat_toko = '';
$telepon = '';
$fax = '';
$npwp = '';

foreach ($dp_data as $row) {
	$nama_customer = $row->nama_customer;
	$alamat_customer = $row->alamat_customer;
}

$alamat1 = substr(strtoupper(trim($alamat_customer)), 0,47);
	$alamat2 = substr(strtoupper(trim($alamat_customer)), 47);
$last_1 = substr($alamat1, -1,1);
$last_2 = substr($alamat2, 0,1);

$positions = array();
$pos = -1;
while (($pos = strpos(trim($alamat_customer)," ", $pos+1 )) !== false) {
	$positions[] = $pos;
}

$max = 47;
if ($last_1 != '' && $last_2 != '') {
	$posisi =array_filter(array_reverse($positions),
		function($value) use ($max) {
			return $value <= $max;
		});

	$posisi = array_values($posisi);

	$alamat1 = substr(strtoupper(trim($alamat_customer)), 0,$posisi[0]);
   	$alamat2 = substr(strtoupper(trim($alamat_customer)), $posisi[0]);
}
?>

<script>

function print_bukti_dp(printer_name){
	
	// var config = qz.configs.create("Nota");             // Exact printer name from OS
  	var data = ['\x1B' + '\x40'+          // init
	   	'\x1B' + '\x21' + '\x39'+ // em mode on
	   	<?="'".sprintf('%-20.18s','PENERIMAAN DP')."'";?>+'\x09'+

	   	'\x1B' + '\x21' + '\x18'+ // em mode on
	   	<?="'".sprintf('%44.27s', 'BANDUNG, '.$tanggal_print)."'";?> + '\x0A'+
	   	<?="'".sprintf('%-35.35s', strtoupper($nama_toko) )."'";?>+
	   	<?="'".sprintf('%42.40s', 'Kepada Yth,')."'";?> + '\x0A'+
	   	<?="'".sprintf('%-35.35s', strtoupper($alamat_toko))."'";?>+'\x09'+
	   	<?='"'.sprintf('%42.42s', strtoupper($nama_keterangan) ).'"';?> + '\x0A'+

	   	'\x1B' + '\x21' + '\x18'+ // em mode on
	   	<?="'".sprintf('%-31.31s', 'TELP:'.$telepon)."'";?>+'\x09'+
	   	<?="'".sprintf('%47.47s', $alamat1)."'";?> + '\x0A'+

	   	<?="'".sprintf('%-35.35s', ($fax != '' ? 'FAX:'.$fax : '') )."'";?>+'\x09'+
	   	<?="'".sprintf('%42.42s',$alamat2 )."'";?> + '\x0A'+
	   	<?="'".sprintf('%-30.30s', 'NPWP : '.$npwp)."'";?>+'\x09'+
	   	<?="'".sprintf('%47.47s', strtoupper($kota))."'";?> + '\x0A'+


	   	'\x1B' + '\x21' + '\x18'+ // em mode on
	   	<?="'".sprintf("%${'garis1'}80s", '')."'";?>+ '\x0A'+
	   	<?="'".sprintf('%-47.47s', ($po_number != '' ? "PO/Ket : ".$po_number : '') )."'";?>+'\x09'+
	   	<?="'".sprintf('%30.30s', 'INVOICE NO : '.$no_faktur_lengkap)."'";?> + '\x0A'+
	   	
	   	'\x1B' + '\x21' + '\x18'+ // em mode on
		<?echo "'".sprintf('%-1.0s %-12.12s %-5.4s %-12.12s %-5.5s %-15.15s ', '','Tanda Terima', '', 'Checker','','Hormat Kami')."'";?>+

	   	'\x1B' + '\x21' + '\x04'+ // em mode on
	   	'\x0A'+
	   	'\x0A'+
	   	'\x0A'+
	   	'\x0A'+
	   	'\x0A'+'.'+
	   	
	   	'\x1D' + '\x56',          // cut paper
	   	
    ];
	console.log(data);

	// return data;

	// qz.print(config, data).then(function() {
	//    // alert("Sent data to printer");
	// });
}

</script>