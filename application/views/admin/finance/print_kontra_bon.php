<script>
function kontra_bon(printer_name){
	
    var data = ['\x1B' + '\x40'+
		   '\x1B' + '\x21' + '\x39'+ // em mode on
	   	<?="'".sprintf('%-20.18s','KONTRA BON')."'";?>+'\x09'+

	   	'\x1B' + '\x21' + '\x18'+ // em mode on
	   	<?="'".sprintf('%40.27s', 'BANDUNG, '.$tanggal_kontra)."'";?> + '\x0A'+

	   	<?="'".sprintf('%-35.35s', strtoupper($nama_toko) )."'";?>+
	   	<?="'".sprintf('%45.45s', 'Kepada Yth,')."'";?> + '\x0A'+
	   	
	   	<?="'".sprintf('%-35.35s', strtoupper($alamat_toko))."'";?>+'\x09'+
	   	<?='"'.sprintf('%40.40s', strtoupper($nama_customer) ).'"';?> + '\x0A'+

	   	'\x1B' + '\x21' + '\x18'+ // em mode on
	   	<?="'".sprintf('%-30.30s', 'TELP:'.$telepon)."'";?>+'\x09'+
	   	<?="'".sprintf('%48.48s', trim($alamat1))."'";?> + '\x0A'+

	   	<?="'".sprintf('%-30.30s', ($fax != '' ? 'FAX:'.$fax : '') )."'";?>+'\x09'+
	   	<?="'".sprintf('%48.48s', trim($alamat2) )."'";?> + '\x0A'+

	   	<?="'".sprintf('%-30.30s', 'NPWP : '.$npwp)."'";?>+'\x09'+
	   	<?="'".sprintf('%48.48s', strtoupper($kota))."'";?> + '\x0A'+


	   	'\x1B' + '\x21' + '\x18'+ // em mode on
	   	<?="'".sprintf("%'-80s", '')."'";?>+ '\x0A'+

	   	//=============================================================
	   	<?="'".sprintf('%-2.2s','NO')."'";?>+'\x09'+
	   	<?="'".sprintf('%-20.20s','Tanggal')."'";?>+'\x0A'+
	   	<?="'".sprintf('%-20.20s','No Faktur')."'";?>+'\x09'+
	   	<?="'".sprintf('%-20.20s','Nilai Faktur')."'";?>+'\x09'+
	   	<?="'".sprintf("%'-80s", '')."'";?>+ '\x0A'+
	   	//=============================================================

		   	
	   	<?$total = 0;
   		$idx = 1;
   		$total_faktur = 0; $total_piutang = 0;
	   	foreach ($pembayaran_piutang_detail as $row) {
	   		$total_faktur += $row->total_jual;
	   		$total_piutang += $row->sisa_piutang;
	   		?>
			<?="'".sprintf('%-2.2s',$idx)."'";?>+'\x09'+
			<?="'".sprintf('%-20.20s',is_reverse_date($row->tanggal) )."'";?>+'\x09'+
			<?="'".sprintf('%-20.20s',$row->no_faktur)."'";?>+'\x09'+
			<?="'".sprintf('%-20.20s',number_format($row->total_jual,'0',',','.'))."'";?>+'\x0A'+
	   	<?$idx++;}?>

	   	<?="'".sprintf("%'-80s", '')."'";?>+ '\x0A'+
	   	//=============================================================
	   	<?="'".sprintf('%-2.2s','')."'";?>+'\x09'+
		<?="'".sprintf('%-20.20s','' )."'";?>+'\x09'+
	   	<?="'".sprintf('%-20.20s','TOTAL')."'";?>+'\x09'+
		<?="'".sprintf('%-20.20s',number_format($total_faktur,'0',',','.'))."'";?>+'\x0A',
	   	//=============================================================
	   	// Generate Pulse to kick-out cash drawer**
        // **for legacy drawer cable CD-005A.  Research before using.
	    // see also http://keyhut.com/popopen4.htm
    ];

    console.log(data);

    webprint.printRaw(data, printer_name);

 	// qz.print(config, data).then(function() {
	//    alert("Sent data to printer");
	// });
}
</script>