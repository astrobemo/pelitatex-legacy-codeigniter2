<script>

function print_faktur(printer_name){
	

	// if (idx == 1) {
	// 	qz.websocket.connect().then(function() {
	// 	});
	// };

	// var config = qz.configs.create("Nota");             // Exact printer name from OS
  	var data = ['\x1B' + '\x40'+// init
	   	'\x1B' + '\x21' + '\x18'+ // em mode on
	   	<?="'".sprintf('%-25.20s', strtoupper($nama_toko) )."'";?>+
	   	'\x1B' + '\x21' + '\x79'+ // em mode on
	   	<?="'".sprintf('%-15.15s','RETUR PENJUALAN')."'";?>+
	   	'\x1B' + '\x21' + '\x18'+ '\x09'+ // em mode on
	   	<?="'".sprintf('%24.24s', 'BANDUNG, '.$tanggal)."'";?> + '\x0A'+

	   	
	   	'\x1B' + '\x21' + '\x18'+ // em mode on
	   	<?="'".sprintf('%-35.35s', strtoupper($alamat_toko) )."'";?>+'\x09'+
	   	<?="'".sprintf('%40.40s', 'Kepada Yth,')."'";?> + '\x0A'+

	   	'\x1B' + '\x21' + '\x18'+ // em mode on
	   	<?="'".sprintf('%-35.35s', 'TELP:'.$telepon)."'";?>+'\x09'+
	   	<?="'".sprintf('%40.40s', strtoupper($nama_keterangan) )."'";?> + '\x0A'+


	   	'\x1B' + '\x21' + '\x18'+ // em mode on
	   	<?="'".sprintf('%-31.31s', ($fax != '' ? 'FAX:'.$fax : '') )."'";?>+'\x09'+
	   	<?="'".sprintf('%47.47s', $alamat1)."'";?> + '\x0A'+

	   	<?="'".sprintf('%-31.31s', 'NPWP : '.$npwp )."'";?>+'\x09'+
	   	<?="'".sprintf('%47.47s',$alamat2 )."'";?> + '\x0A'+

	   	'\x1B' + '\x21' + '\x18'+ // em mode on
	   	<?="'".sprintf('%-30.30s', '')."'";?>+
	   	'\x1B' + '\x21' + '\x15'+ //spacing
	   	'\x09'+
	   	'\x1B' + '\x21' + '\x18'+ // em mode on
	   	<?="'".sprintf('%47.47s', strtoupper($kota))."'";?> + '\x0A'+
	   	
	   	//==============================================================================
	   	<?="'".sprintf("%${'garis1'}80s", '')."'";?>+ '\x0A'+
	   	
	   	'\x1B' + '\x21' + '\x18'+ // em mode on
	   	<?="'".sprintf('%-47.47s', 'FAKTUR ASLI :'.$no_faktur_penjualan )."'";?>+
	   	'\x1B' + '\x21' + '\x15'+ //spacing
	   	'\x09'+
	   	'\x1B' + '\x21' + '\x18'+ // em mode on
	   	<?="'".sprintf('%30.30s', 'RETUR NO : '.$no_faktur_lengkap)."'";?> + '\x0A'+


	   	//==============================================================================
	   	'\	x1B' + '\x21' + '\x18'+ // em mode on
	   	<?="'".sprintf("%${'garis1'}80s", '')."'";?>+ '\x0A'+
	   	'\x1B' + '\x21' + '\x18'+ // em mode on
	   	<?="'".sprintf('%-6.6s', 'Jumlah ')."'";?>+
	   	'\x1B' + '\x21' + '\x15'+
	   	'\x09'+
	   	'\x1B' + '\x21' + '\x18'+ // em mode on
	   	<?="'".sprintf('%-6.6s', 'Satuan ')."'";?>+
	   	'\x1B' + '\x21' + '\x15'+
	   	'\x09'+
	   	'\x1B' + '\x21' + '\x18'+ // em mode on
	   	<?="'".sprintf('%-4.4s', 'Roll ')."'";?>+
	   	'\x1B' + '\x21' + '\x15'+
	   	'\x09'+
	   	'\x1B' + '\x21' + '\x18'+ // em mode on
	   	<?="'".sprintf('%-27.27s', 'Nama Barang ')."'";?>+
	   	'\x1B' + '\x21' + '\x15'+
	   	'\x09'+
	   	'\x1B' + '\x21' + '\x18'+ // em mode on
	   	<?="'".sprintf('%-12.12s', 'Harga ')."'";?>+
	   	'\x1B' + '\x21' + '\x15'+
	   	'\x09'+
	   	'\x1B' + '\x21' + '\x18'+ // em mode on
	   	<?="'".sprintf('%-9.9s', 'TOTAL ')."'";?> + '\x0A'+
	   	<?="'".sprintf("%${'garis1'}80s", '')."'";?>+ '\x0A'+
		

	   	//==============================================================================
	   	<?
	   	$total = 0; $total_roll = 0; $idx = 0;
	   	foreach ($penjualan_print as $row) {
	   		$total += $row->qty * $row->harga;
	   		$total_roll += $row->jumlah_roll;?>
	   			'\x1B' + '\x21' + '\x18'+ // em mode on
			   	<?="'".sprintf('%6.6s', is_qty_general($row->qty))."'";?>+
			   	'\x1B' + '\x21' + '\x15'+
			   	'\x09'+
			   	'\x1B' + '\x21' + '\x18'+ // em mode on
			   	<?="'".sprintf('%6.6s', $row->nama_satuan)."'";?>+
			   	'\x1B' + '\x21' + '\x15'+
			   	'\x09'+
			   	'\x1B' + '\x21' + '\x18'+ // em mode on
			   	<?="'".sprintf('%4.4s', $row->jumlah_roll)."'";?>+
			   	'\x1B' + '\x21' + '\x15'+
			   	'\x09'+
			   	'\x1B' + '\x21' + '\x18'+ // em mode on
			   	<?="'".sprintf('%-27.27s', $row->nama_barang)."'";?>+
			   	'\x1B' + '\x21' + '\x15'+
			   	'\x09'+
			   	'\x1B' + '\x21' + '\x18'+ // em mode on
			   	<?="'".sprintf('%-8.8s', number_format($row->harga,'0',',','.'))."'";?>+
			   	'\x1B' + '\x21' + '\x15'+
			   	'\x09'+
			   	'\x1B' + '\x21' + '\x18'+ // em mode on
			   	<?="'".sprintf('%13.13s', number_format($row->qty*$row->harga,'0',',','.'))."'";?> + '\x0A'+
	   	<?$idx++;}?>
	   	<?for ($i = $idx; $i < 10; $i++) {?>
	   		'\x0A'+
	   	<?};?>
	   	<?="'".sprintf("%${'garis1'}80s", '')."'";?>+ '\x0A'+
		
	   	'\x1B' + '\x21' + '\x18'+ // em mode on
	   	<?="'".sprintf('%12.12s', 'TOTAL ROLL')."'";?>+
	   	'\x1B' + '\x21' + '\x15'+
	   	'\x09'+
	   	'\x1B' + '\x21' + '\x18'+ // em mode on
	   	<?="'".sprintf('%4.4s', $total_roll)."'";?>+
	   	'\x1B' + '\x21' + '\x15'+
	   	'\x09'+
	   	'\x1B' + '\x21' + '\x18'+ // em mode on
	   	<?="'".sprintf('%-27.27s', '')."'";?>+
	   	'\x1B' + '\x21' + '\x15'+
	   	'\x09'+
	   	'\x1B' + '\x21' + '\x18'+ // em mode on
	   	<?="'".sprintf('%8.8s', 'TOTAL')."'";?>+
	   	'\x1B' + '\x21' + '\x15'+
	   	'\x09'+
	   	'\x1B' + '\x21' + '\x18'+ // em mode on
	   	<?="'".sprintf('%13.13s', number_format($total,'0',',','.'))."'";?> + '\x0A'+

	   	//==============================================================================

	   	<?foreach ($data_pembayaran as $row) {?>
		   	'\x1B' + '\x21' + '\x18'+ // em mode on
		   	<?="'".sprintf('%12.6s', '')."'";?>+
		   	'\x1B' + '\x21' + '\x15'+
		   	'\x09'+
		   	'\x1B' + '\x21' + '\x18'+ // em mode on
		   	<?="'".sprintf('%4.4s', '')."'";?>+
		   	'\x1B' + '\x21' + '\x15'+
		   	'\x09'+
		   	'\x1B' + '\x21' + '\x18'+ // em mode on
		   	<?="'".sprintf('%-27.27s', '')."'";?>+
		   	'\x1B' + '\x21' + '\x15'+
		   	'\x09'+
		   	'\x1B' + '\x21' + '\x18'+ // em mode on
		   	<?="'".sprintf('%8.8s', $row->nama_bayar)."'";?>+
		   	'\x1B' + '\x21' + '\x15'+
		   	'\x09'+
		   	'\x1B' + '\x21' + '\x18'+ // em mode on
		   	<?="'".sprintf('%13.13s', number_format($row->amount,'0',',','.'))."'";?> + '\x0A'+
	   	<?}?>

	   	//==============================================================================

	   	'\x1B' + '\x21' + '\x18'+ // em mode on
	   	<?="'".sprintf('%12.6s', '')."'";?>+
	   	'\x1B' + '\x21' + '\x15'+
	   	'\x09'+
	   	'\x1B' + '\x21' + '\x18'+ // em mode on
	   	<?="'".sprintf('%4.4s', '')."'";?>+
	   	'\x1B' + '\x21' + '\x15'+
	   	'\x09'+
	   	'\x1B' + '\x21' + '\x18'+ // em mode on
	   	<?="'".sprintf('%-27.27s', '')."'";?>+
	   	'\x1B' + '\x21' + '\x15'+
	   	'\x09'+
	   	'\x1B' + '\x21' + '\x18'+ // em mode on
	   	<?="'".sprintf("%'-28s",'')."'";?> + '\x0A'+

	   	'\x1B' + '\x21' + '\x18'+ // em mode on
	   	<?="'".sprintf('%12.6s', '')."'";?>+
	   	'\x1B' + '\x21' + '\x15'+
	   	'\x09'+
	   	'\x1B' + '\x21' + '\x18'+ // em mode on
	   	<?="'".sprintf('%4.4s', '')."'";?>+
	   	'\x1B' + '\x21' + '\x15'+
	   	'\x09'+
	   	'\x1B' + '\x21' + '\x18'+ // em mode on
	   	<?="'".sprintf('%-27.27s', '')."'";?>+
	   	'\x1B' + '\x21' + '\x15'+
	   	'\x09'+
	   	'\x1B' + '\x21' + '\x18'+ // em mode on
	   	<?="'".sprintf("%8.8s",'KEMBALI')."'";?>+
	   	'\x1B' + '\x21' + '\x15'+
	   	'\x09'+
	   	'\x1B' + '\x21' + '\x18'+ // em mode on
	   	<?="'".sprintf('%13.13s', number_format($kembali,'0',',','.'))."'";?> + '\x0A' +
	   	<?="'".sprintf("%' 60s", '')."'";?>+
	   	'\x1B' + '\x21' + '\x04'+ // em mode on
	   	<?="'".sprintf('%30.30s', '*harga sudah termasuk ppn')."'";?>+'\x0A'+
	   	'\x0A'+
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

    webprint.printRaw(data, printer_name);


	// qz.print(config, data).then(function() {
	//    // alert("Sent data to printer");
	// });
}

</script>