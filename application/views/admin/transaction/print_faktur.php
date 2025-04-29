<script>

function print_faktur(printer_name){
	
	// if (idx == 1) {
	// 	qz.websocket.connect().then(function() {
	// 	});
	// };

	// var config = qz.configs.create("Nota");             // Exact printer name from OS
  	var data = ['\x1B' + '\x40'+          // init
	   	'\x1B' + '\x21' + '\x39'+ // em mode on
	   	<?="'".sprintf('%-20.18s','FAKTUR PENJUALAN')."'";?>+
	   	'\x1B' + '\x21' + '\x15'+
	   	'\x09'+
	   	'\x1B' + '\x21' + '\x18'+ // em mode on
	   	<?="'".sprintf('%44.27s', 'BANDUNG, '.$tanggal_print)."'";?> + '\x0A'+

	   	'\x1B' + '\x21' + '\x18'+ // em mode on
	   	<?="'".sprintf('%-35.35s', strtoupper($nama_toko) )."'";?>+
	   	'\x1B' + '\x21' + '\x15'+
	   	'\x09'+
	   	'\x1B' + '\x21' + '\x18'+ // em mode on
	   	<?="'".sprintf('%42.40s', 'Kepada Yth,')."'";?> + '\x0A'+

	   	'\x1B' + '\x21' + '\x18'+ // em mode on
	   	<?="'".sprintf('%-35.35s', strtoupper($alamat_toko))."'";?>+
	   	'\x1B' + '\x21' + '\x15'+
	   	'\x09'+
	   	'\x1B' + '\x21' + '\x18'+ // em mode on
	   	<?='"'.sprintf('%42.42s', strtoupper($nama_keterangan) ).'"';?> + '\x0A'+


	   	'\x1B' + '\x21' + '\x18'+ // em mode on
	   	<?="'".sprintf('%-31.31s', 'TELP:'.$telepon)."'";?>+
	   	'\x1B' + '\x21' + '\x15'+ //spacing
	   	'\x09'+
	   	'\x1B' + '\x21' + '\x18'+ // em mode on
	   	<?="'".sprintf('%47.47s', $alamat1)."'";?> + '\x0A'+

	   	'\x1B' + '\x21' + '\x18'+ // em mode on
	   	<?="'".sprintf('%-35.35s', ($fax != '' ? 'FAX:'.$fax : '') )."'";?>+
	   	'\x1B' + '\x21' + '\x15'+ //spacing
	   	'\x09'+
	   	'\x1B' + '\x21' + '\x18'+ // em mode on
	   	<?="'".sprintf('%42.42s',$alamat2 )."'";?> + '\x0A'+

   		'\x1B' + '\x21' + '\x18'+ // em mode on
	   	<?="'".sprintf('%-30.30s', 'NPWP : '.$npwp)."'";?>+
	   	'\x1B' + '\x21' + '\x15'+ //spacing
	   	'\x09'+
	   	'\x1B' + '\x21' + '\x18'+ // em mode on
	   	<?="'".sprintf('%47.47s', strtoupper($kota))."'";?> + '\x0A'+


	   	'\x1B' + '\x21' + '\x18'+ // em mode on
	   	<?="'".sprintf("%${'garis1'}80s", '')."'";?>+ '\x0A'+
	   	
	   	'\x1B' + '\x21' + '\x18'+ // em mode on
	   	<?="'".sprintf('%-47.47s', ($po_number != '' ? "PO/Ket : ".$po_number : '') )."'";?>+
	   	'\x1B' + '\x21' + '\x15'+ //spacing
	   	'\x09'+
	   	'\x1B' + '\x21' + '\x18'+ // em mode on
	   	<?="'".sprintf('%30.30s', 'INVOICE NO : '.$no_faktur_lengkap)."'";?> + '\x0A'+


	   	//==============================================================================
	   	'\x1B' + '\x21' + '\x10'+
		<?="'".sprintf("%'=80s",'')."'";?> + '\x0A'+
		
		'\x1B' + '\x21' + '\x01'+
		<?="'".sprintf("%-1.2s",'NO')."'";?>+
		'\x1B' + '\x21' + '\x15'+ //spacing
		'\x09'+
		'\x1B' + '\x21' + '\x01'+
		<?="'".sprintf('%-10.10s','QTY')."'";?>+
		'\x1B' + '\x21' + '\x15'+ //spacing
		'\x09'+
		'\x1B' + '\x21' + '\x01'+
		<?="'".sprintf('%-12.12s','Detail')."'";?>+
		'\x1B' + '\x21' + '\x15'+ //spacing
		'\x09'+
		'\x1B' + '\x21' + '\x01'+
		<?="'".sprintf('%-35.35s','NAMA BARANG')."'";?>+
		'\x1B' + '\x21' + '\x15'+ //spacing
		'\x09'+
		'\x1B' + '\x21' + '\x01'+
		<?="'".sprintf('%-15.15s','Keterangan')."'";?>+ '\x0A'+

		'\x1B' + '\x21' + '\x10'+
		<?="'".sprintf("%'=80s",'')."'";?>+ '\x0A'+
		

	   	//==============================================================================
	   	<?
	   	$total = 0; $total_roll = 0; $idx = 0;
	   	foreach ($penjualan_print as $row) {
	   		$total += $row->qty * $row->harga_jual;
	   		$total_roll += $row->jumlah_roll;?>
	   			'\x1B' + '\x21' + '\x19'+ // em mode on
			   	<?="'".sprintf('%8.8s', is_qty_general($row->qty))."'";?>+
			   	<?="'".sprintf('%-1.1s', '')."'";?>+
			   	<?="'".sprintf('%-5.5s', strtoupper($row->nama_satuan))."'";?>+
			   	<?="'".sprintf('%4.4s', ($row->tipe_qty != 3 ? $row->jumlah_roll : '') )."'";?>+
			   	<?="'".sprintf('%2.2s', '')."'";?>+
			   	<?="'".sprintf('%-12.12s', strtoupper($row->jenis_barang))."'";?>+
			   	<?="'".sprintf('%-27.27s', strtoupper($row->nama_barang))."'";?>+'\x09'+
			   	<?="'".sprintf('%-8.8s', number_format($row->harga_jual,'0',',','.'))."'";?>+'\x09'+
			   	<?="'".sprintf('%13.13s', number_format($row->qty*$row->harga_jual,'0',',','.'))."'";?> + '\x0A'+
	   	<?$idx++;}?>
	   	<?for ($i = $idx; $i < 10; $i++) {?>
	   		'\x0A'+
	   	<?};?>
	   	'\x1B' + '\x21' + '\x18'+ // em mode on
	   	<?="'".sprintf("%${'garis1'}80s", '')."'";?>+ '\x0A'+
		<?="'".sprintf('%-12.12s', 'TOTAL ROLL')."'";?>+
	   	<?="'".sprintf('%4.4s', $total_roll)."'";?>+
	   	<?="'".sprintf('%-27.27s', '')."'";?>+'\x09'+
	   	<?="'".sprintf('%12.12s', 'TOTAL')."'";?>+'\x09'+
	   	<?="'".sprintf('%13.13s', number_format($total,'0',',','.'))."'";?> + '\x0A'+

	   	//==============================================================================

	   	<?foreach ($data_pembayaran as $row) {
	   		if ($row->amount != 0) {?>
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
	   	'\x0A'+
	   	
	   	'\x1D' + '\x56',          // cut paper
	   	
    ];
	console.log(data);

    webprint.printRaw(data, printer_name);


	// qz.print(config, data).then(function() {
	//    // alert("Sent data to printer");
	// });
}

</script>