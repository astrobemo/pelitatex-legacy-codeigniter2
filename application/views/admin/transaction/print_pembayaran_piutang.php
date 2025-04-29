<script>

function print_pembayaran_piutang(printer_name){
	

	// if (idx == 1) {
	// 	qz.websocket.connect().then(function() {
	// 	});
	// };

	// var config = qz.configs.create("Nota");             // Exact printer name from OS
  	var data = ['\x1B' + '\x40'+          // init
	   	'\x1B' + '\x21' + '\x39'+ // em mode on
	   	<?="'".sprintf('%-20.18s','TANDA TERIMA')."'";?>+
	   	'\x1B' + '\x21' + '\x15'+
	   	'\x09'+
	   	'\x1B' + '\x21' + '\x18'+ // em mode on
	   	<?="'".sprintf('%44.27s', 'BANDUNG, '.date('d F Y'))."'";?> + '\x0A'+

	   	'\x1B' + '\x21' + '\x18'+ // em mode on
	   	<?="'".sprintf('%-40.30s', '')."'";?>+
	   	'\x1B' + '\x21' + '\x15'+
	   	'\x09'+
	   	'\x1B' + '\x21' + '\x18'+ // em mode on
	   	<?="'".sprintf('%36.25s', 'Kepada Yth,')."'";?> + '\x0A'+

	   	'\x1B' + '\x21' + '\x18'+ // em mode on
	   	<?="'".sprintf('%-40.30s', 'CV. PELITA ABADI ')."'";?>+
	   	'\x1B' + '\x21' + '\x15'+
	   	'\x09'+
	   	'\x1B' + '\x21' + '\x18'+ // em mode on
	   	<?="'".sprintf('%36.25s', strtoupper($nama_customer) )."'";?> + '\x0A'+


	   	'\x1B' + '\x21' + '\x18'+ // em mode on
	   	<?="'".sprintf('%-30.30s', 'TAMIM NO. 53 BANDUNG ')."'";?>+
	   	'\x1B' + '\x21' + '\x15'+ //spacing
	   	'\x09'+
	   	'\x1B' + '\x21' + '\x18'+ // em mode on
	   	<?="'".sprintf('%47.47s', strtoupper(trim($alamat_customer)))."'";?> + '\x0A'+

	   	'\x1B' + '\x21' + '\x18'+ // em mode on
	   	<?="'".sprintf('%-30.30s', 'TELP: (022)4238165 ')."'";?>+
	   	'\x1B' + '\x21' + '\x15'+ //spacing
	   	'\x09'+
	   	'\x1B' + '\x21' + '\x18'+ // em mode on
	   	<?="'".sprintf('%47.47s','')."'";?> + '\x0A'+

   		'\x1B' + '\x21' + '\x18'+ // em mode on
	   	<?="'".sprintf('%-30.30s', 'FAX: (022)4218628 ')."'";?>+
	   	'\x1B' + '\x21' + '\x15'+ //spacing
	   	'\x09'+
	   	'\x1B' + '\x21' + '\x18'+ // em mode on
	   	<?="'".sprintf('%47.47s', strtoupper($kota))."'";?> + '\x0A'+


	   	'\x1B' + '\x21' + '\x18'+ // em mode on
	   	<?="'".sprintf("%'-80s", '')."'";?>+ '\x0A'+
	   	"Telah diterima dari <?=$nama_customer?> Rp. <?=number_format($total_bayar_piutang,'0',',','.');?>"+ '\x0A'+
	   	'\x1B' + '\x21' + '\x19'+ // em mode on
	   	"Terbilang : <?=is_number_write($total_bayar_piutang)?>"+ '\x0A'+
	   	"Perincian : "+ '\x0A'+

	   	'\x1B' + '\x21' + '\x18'+ // em mode on
	   	<?
	   	$idx = 1;
		foreach ($pembayaran_piutang_nilai as $row) { 
			$pembayaran_type_id = $row->pembayaran_type_id;
			if ($row->pembayaran_type_id == 1) { $title = 'TRANSFER'; }
			elseif ($row->pembayaran_type_id == 2) { $title = 'GIRO'; }
			elseif ($row->pembayaran_type_id == 3) { $title = 'CASH'; }
			elseif ($row->pembayaran_type_id == 4) { $title = 'EDC'; }
		?>
	   	<?="'".sprintf("%'-80s", '')."'";?>+ '\x0A'+
	   	'<?=$idx;?>. <?=$title;?>'+ '\x0A'+

		<?
			if ($pembayaran_type_id == 2 ) { $giro = $row->no_giro; }else{ $no_giro = ''; };

			if ($pembayaran_type_id != 3 && $pembayaran_type_id != 4 ) { ?>
			   	<?="'".sprintf('%-18.18s %-3.3s %-40.40s', "Bank",':',$row->nama_bank.' '.$no_giro )."'";?>+'\x0A'+
			<?};

			if ($pembayaran_type_id == 1 ) { ?>
			   	<?="'".sprintf('%-18.18s %-3.3s %-40.40s', "No Rek. ",':',$row->no_rek_bank )."'";?>+'\x0A'+
			<?};

			if ($pembayaran_type_id == 2 ) { ?>
			   	<?="'".sprintf('%-18.18s %-3.3s %-40.40s', "Jatuh Tempo",':', is_reverse_date($row->jatuh_tempo) )."'";?>+'\x0A'+
			<?};			

			if ($pembayaran_type_id == 3) { ?>
			   	<?="'".sprintf('%-18.18s %-3.3s %-40.40s', "Nama Penerima",':',$row->nama_penerima )."'";?>+'\x0A'+
			<?};

			echo "'".sprintf('%-18.18s %-3.3s %-40.40s', "Jumlah",':',number_format($row->amount,'0',',','.') )."'";?>+'\x0A'+
	
		   	<?="'".sprintf("%'-80s", '')."'";?>+ '\x0A'+

		<?$idx++;}?>

		'\x0A'+
		'\x0A'+
		'\x0A'+

		"Untuk Pembayaran " + '\x0A'+
	   	<?="'".sprintf("%'-80s", '')."'";?>+ '\x0A'+

		'\x1B' + '\x21' + '\x18'+ // em mode on
	   	<?="'".sprintf('%-10.12s', 'Tanggal ')."'";?>+
	   	'\x09'+
	   	<?="'".sprintf('%-20.20s', 'NO Faktur ')."'";?>+
	   	'\x09'+
	   	<?="'".sprintf('%-40.40s', 'Pembayaran ')."'";?>+
	   	'\x0A'+
	   	<?="'".sprintf("%'-80s", '')."'";?>+ '\x0A'+
	   	+ '\x0A'+

	   	<?
	   	foreach ($pembayaran_piutang_detail as $row) { ?>
			'\x1B' + '\x21' + '\x18'+ // em mode on
		   	<?="'".sprintf('%-10.12s', is_reverse_date($row->tanggal))."'";?>+
		   	'\x09'+
		   	<?="'".sprintf('%-20.20s', $row->no_faktur)."'";?>+
		   	'\x09'+
		   	<?="'".sprintf('%-40.40s', number_format($row->amount),'0',',','.')."'";?>+
		   	'\x0A'+
			<?
		}?>
	   	<?="'".sprintf("%'-80s", '')."'";?>+ '\x0A'+


		'\x0A'+
	   	'\x0A'+
	   	'\x0A'+
	   	'\x0A'+

	   	
	   	'\x1B' + '\x21' + '\x18'+ // em mode on

		<?echo "'".sprintf('%-1.0s %-12.12s %-5.4s %-12.12s %-5.5s %-15.15s ', '','Tanda Terima', '', 'Checker','','Hormat Kami')."'";?>+
	   	

	   	'\x0A'+
	   	'\x0A'+
	   	
	   	'\x1B' + '\x69',          // cut paper
	   	'\x10' + '\x14' + '\x01' + '\x00' + '\x05',  // Generate Pulse to kick-out cash drawer**
	                                                // **for legacy drawer cable CD-005A.  Research before using.
	                                                // see also http://keyhut.com/popopen4.htm
    ];
	// console.log(data);

    webprint.printRaw(data, printer_name);


	// qz.print(config, data).then(function() {
	//    // alert("Sent data to printer");
	// });
}

</script>