<script>

function print_faktur_3(printer_name){
	

	// if (idx == 1) {
	// 	qz.websocket.connect().then(function() {
	// 	});
	// };

	// var config = qz.configs.create("Nota");             // Exact printer name from OS
  	var data = ['\x1B' + '\x40'+          // init
	   	'\x1B' + '\x21' + '\x18'+ // em mode on
	   	<?="'".sprintf('%38.27s', '')."'";?> + 
	   	'\x1B' + '\x21' + '\x15'+ //spacing
	   	'\x09'+
	   	'\x1B' + '\x21' + '\x39'+ // em mode on
	   	
	   	//1
	   	<?="'".sprintf('%24.18s','FAKTUR PENJUALAN')."'";?>+
	   	
	   	//2
	   	'\x0A'+
	   	'\x1B' + '\x21' + '\x18'+ // em mode on
	   	<?="'".sprintf('%-33.18s',strtoupper($nama_toko))."'";?>+
	   	'\x1B' + '\x21' + '\x15'+ //spacing
	   	'\x09'+
	   	'\x1B' + '\x21' + '\x18'+ // em mode on
	   	<?="'".sprintf('%44.27s', 'BANDUNG, '.$tanggal_print)."'";?> + 
	   	
	   	//3
	   	'\x0A'+
	   	'\x1B' + '\x21' + '\x18'+ // em mode on
	   	<?="'".sprintf('%-35.35s', strtoupper($alamat_toko) )."'";?>+
	   	'\x1B' + '\x21' + '\x15'+
	   	'\x09'+
	   	'\x1B' + '\x21' + '\x18'+ // em mode on
	   	<?="'".sprintf('%42.40s', 'Bill To:')."'";?> + 

	   	//4
	   	'\x0A'+
	   	'\x1B' + '\x21' + '\x18'+ // em mode on
	   	<?="'".sprintf('%-4.4s','TELP' )."'";?>+
	   	<?="'".sprintf('%-2.2s',':' )."'";?>+
	   	<?="'".sprintf('%-29.29s',$telepon )."'";?>+
	   	'\x1B' + '\x21' + '\x15'+
	   	'\x09'+
	   	'\x1B' + '\x21' + '\x18'+ // em mode on
	   	<?='"'.sprintf('%42.42s', strtoupper($nama_keterangan) ).'"';?> + 

	   	//5
	   	'\x0A'+
	   	'\x1B' + '\x21' + '\x18'+ // em mode on
	   	<?="'".sprintf('%-4.4s', ($fax != '' ? 'FAX' : ''))."'";?>+
	   	<?="'".sprintf('%-2.2s', ($fax != '' ? ':' : ''))."'";?>+
	   	<?="'".sprintf('%-25.25s', ($fax != '' ? $fax : ''))."'";?>+
	   	'\x1B' + '\x21' + '\x15'+ //spacing
	   	'\x09'+
	   	'\x1B' + '\x21' + '\x18'+ // em mode on
	   	<?='"'.sprintf('%47.47s', $alamat1).'"';?> + 

	   	//6
	   	'\x0A'+
	   	'\x1B' + '\x21' + '\x18'+ // em mode on
	   	<?="'".sprintf('%-4.4s', 'NPWP' )."'";?>+
	   	<?="'".sprintf('%-2.2s', ': ' )."'";?>+
	   	<?="'".sprintf('%-29.29s', $npwp )."'";?>+
	   	'\x1B' + '\x21' + '\x15'+ //spacing
	   	'\x09'+
	   	'\x1B' + '\x21' + '\x18'+ // em mode on
	   	<?='"'.sprintf('%42.42s',$alamat2 ).'"';?> + 

	   	//7
	   	'\x0A'+
   		'\x1B' + '\x21' + '\x18'+ // em mode on
	   	<?="'".sprintf('%-30.30s', '')."'";?>+
	   	'\x1B' + '\x21' + '\x15'+ //spacing
	   	'\x09'+
	   	'\x1B' + '\x21' + '\x18'+ // em mode on
	   	<?="'".sprintf('%47.47s', strtoupper($kota))."'";?> + 

	   	//8
	   	'\x0A'+
	   	'\x1B' + '\x21' + '\x18'+ // em mode on
	   	<?="'".sprintf("%${'garis1'}80s", '')."'";?>+ 

	   	//9
	   	'\x0A'+
	   	'\x1B' + '\x21' + '\x18'+ // em mode on
	   	<?="'".sprintf('%-47.47s', ($po_number != '' ? "PO/Ket : ".$po_number : '') )."'";?>+
	   	'\x1B' + '\x21' + '\x15'+ //spacing
	   	'\x09'+
	   	'\x1B' + '\x21' + '\x18'+ // em mode on
	   	<?="'".sprintf('%30.30s', 'INVOICE NO : '.$no_faktur_lengkap)."'";?> + 

	   	//==============================================================================
	   	//10
	   	'\x0A'+
	   	'\x1B' + '\x21' + '\x18'+ // em mode on
	   	<?="'".sprintf("%${'garis1'}80s", '')."'";?>+ 

	   	//11
	   	'\x0A'+
	   	'\x1B' + '\x21' + '\x18'+ // em mode on
	   	<?="'".sprintf('%-8.8s', 'Jumlah ')."'";?>+
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
	   	<?="'".sprintf('%-25.25s', 'Nama Barang ')."'";?>+
	   	'\x1B' + '\x21' + '\x15'+
	   	'\x09'+
	   	'\x1B' + '\x21' + '\x18'+ // em mode on
	   	<?="'".sprintf('%-11.11s', 'Harga ')."'";?>+
	   	'\x1B' + '\x21' + '\x15'+
	   	'\x09'+
	   	'\x1B' + '\x21' + '\x18'+ // em mode on
	   	<?="'".sprintf('%-9.9s', 'TOTAL ')."'";?> + 

	   	//12
	   	'\x0A'+
	   	<?="'".sprintf("%${'garis1'}80s", '')."'";?>+ 

	   	//==============================================================================
	   	//13
	   	'\x0A'+
	   	<?
	   	$total = 0; $total_roll = 0; $idx = 0;
	   	foreach ($penjualan_print as $row) {
	   		$total += $row->qty * $row->harga_jual;
	   		$total_roll += $row->jumlah_roll;?>
	   			'\x1B' + '\x21' + '\x18'+ // em mode on
			   	<?="'".sprintf('%8.8s', is_qty_general($row->qty))."'";?>+
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
			   	<?="'".sprintf('%-25.25s', $row->nama_barang)."'";?>+
			   	'\x1B' + '\x21' + '\x15'+
			   	'\x09'+
			   	'\x1B' + '\x21' + '\x18'+ // em mode on
			   	<?="'".sprintf('%-8.8s', number_format($row->harga_jual,'0',',','.'))."'";?>+
			   	'\x1B' + '\x21' + '\x15'+
			   	'\x09'+
			   	'\x1B' + '\x21' + '\x18'+ // em mode on
			   	<?="'".sprintf('%11.11s', number_format($row->qty*$row->harga_jual,'0',',','.'))."'";?> + '\x0A'+
	   	<?$idx++;}?>
	   	<?for ($i = $idx; $i < 10; $i++) {?>
	   		'\x0A'+
	   	<?};?>
	   	<?="'".sprintf("%${'garis1'}80s", '')."'";?>+ 

	   	//23
	   	'\x0A'+
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
	   	<?="'".sprintf('%13.13s', number_format($total,'0',',','.'))."'";?> + 

	   	//==============================================================================
	   	//24
	   	'\x0A'+
	   	<?$idx_bayar = 0;
	   	foreach ($data_pembayaran as $row) {
	   		if ($row->amount != 0) { $idx_bayar++;?>
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

	   	//26 == kasih space 2 soalnya max 2 pembayaran
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
	   	<?="'".sprintf("%'-28s",'')."'";?> + 

	   	//27
	   	'\x0A'+
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
	   	<?="'".sprintf('%13.13s', number_format($kembali,'0',',','.'))."'";?> + 

	   	//28
	   	'\x0A' +
	   	<?="'".sprintf("%' 60s", '')."'";?>+
	   	'\x1B' + '\x21' + '\x04'+ // em mode on
	   	<?="'".sprintf('%30.30s', '*harga sudah termasuk ppn')."'";?>+

	   	//29
	   	'\x0A'+
	   	//30
	   	'\x0A'+
	   	//31
	   	'\x0A'+

	   	<?if ($idx_bayar == 1) {?>
		   	//31+ kalau tipe bayar cmn 1
		   	'\x0A'+
	   	<?}?>
	   	'\x1B' + '\x21' + '\x18'+ // em mode on
	   	
		<?echo "'".sprintf('%-1.0s %-12.12s %-5.4s %-12.12s %-5.5s %-15.15s ', '','Tanda Terima', '', 'Checker','','Hormat Kami')."'";?>+
	   	

	   	'\x0D'+    // cut paper
	   	'\x0C',    // cut paper
	   	
    ];
	console.log(data);
	
    webprint.printRaw(data, printer_name);


	// qz.print(config, data).then(function() {
	//    // alert("Sent data to printer");
	// });
}

</script>