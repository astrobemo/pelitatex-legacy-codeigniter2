<script>

function print_testing(printer_name){
	
	// if (idx == 1) {
	// 	qz.websocket.connect().then(function() {
	// 	});
	// };

	// var config = qz.configs.create("Nota");             // Exact printer name from OS
  	var data = ['\x1B' + '\x40'+          // init
	   	'\x1B' + '\x21' + '\x18'+ // DRAFT
	   	<?="'".sprintf('%-29.25s', 'CV.PELITA SEJATI ')."'";?>+
	   	<?="'".sprintf('%-26.26s', 'SURAT JALAN')."'";?>+
	   	<?="'".sprintf('%25.25s', 'BANDUNG,02 SEPTEMBER 2020 ')."'";?>+'\x0A'+
	   	// baris 2
	   	'\x1B' + '\x21' + '\x19'+ // DRAFT
	   	<?="'".sprintf('%-35.35s', 'JL.MAYOR SUNARYA NO 22, ')."'";?>+
	   	<?="'".sprintf('%-30.30s', 'NO:SJ020920-02550')."'";?>+
	   	<?="'".sprintf('%-30.30s', 'NO.INVOICE FPJ020920-02550')."'";?>+'\x0A'+
	   	// baris 3
	   	<?="'".sprintf('%-35.35s', 'BANDUNG, INDONESIA, 40181')."'";?>+
	   	<?="'".sprintf('%-30.30s', '')."'";?>+
	   	<?="'".sprintf('%-30.30s', 'NO.PO:')."'";?>+'\x0A'+
	   	// baris 
	   	<?="'".sprintf('%-5.5s', 'NAMA:')."'";?>+
	   	<?="'".sprintf('%-45.45s', 'CV.ANDRE & SHINTA PRODUCTION (AS PRODUCTION)')."'";?>+
	   	<?="'".sprintf('%3.3s', '')."'";?>+
	   	<?="'".sprintf('%-18.18s', 'ALAMAT PENGIRIMAN')."'";?>+
	   	<?="'".sprintf('%-24.24s', '|VIA:')."'";?>+'\x0A'+
	   	// baris 
	   	<?="'".sprintf('%-7.7s', 'ALAMAT:')."'";?>+
	   	<?="'".sprintf('%-1.1s', '|')."'";?>+
	   	<?="'".sprintf('%-42.42s', 'GEDUNG KIRANA TWO, LANTAI 10-A')."'";?>+
	   	<?="'".sprintf('%-1.1s', '|')."'";?>+
	   	<?="'".sprintf('%-2.2s', '')."'";?>+
	   	<?="'".sprintf('%-1.1s', '|')."'";?>+
	   	<?="'".sprintf('%41.41s', 'JL. SMK PGRI I KAVLING BABAKAN BARU MARGA')."'";?>+
	   	<?="'".sprintf('%-1.1s', '|')."'";?>+'\x0A'+
	   	// baris 
	   	<?="'".sprintf('%-7.7s', '')."'";?>+
	   	<?="'".sprintf('%-1.1s', '|')."'";?>+
	   	<?="'".sprintf('%-42.42s', 'JL. BOULEVARD TIMUR - 88 JAKARTA UTARA RT:000')."'";?>+
	   	<?="'".sprintf('%-1.1s', '|')."'";?>+
	   	<?="'".sprintf('%-2.2s', '')."'";?>+
	   	<?="'".sprintf('%-1.1s', '|')."'";?>+
	   	<?="'".sprintf('%41.41s', 'ENDAH BLOK.40C NO.C-3 CIMAHI RT007 RW003')."'";?>+
	   	<?="'".sprintf('%-1.1s', '|')."'";?>+'\x0A'+
	   	// baris 8
	   	<?="'".sprintf('%-7.7s', '')."'";?>+
	   	<?="'".sprintf('%-1.1s', '|')."'";?>+
	   	<?="'".sprintf('%-42.42s', 'RW000 KELAPA GADING PEGANGSAAN DUA')."'";?>+
	   	<?="'".sprintf('%-1.1s', '|')."'";?>+
	   	<?="'".sprintf('%-2.2s', '')."'";?>+
	   	<?="'".sprintf('%-1.1s', '|')."'";?>+
	   	<?="'".sprintf('%41.41s', 'CIMAHI TENGAH CIMAHI')."'";?>+
	   	<?="'".sprintf('%-1.1s', '|')."'";?>+'\x0A'+

	   	// baris 
	   	<?="'".sprintf('%-7.7s', '')."'";?>+
	   	<?="'".sprintf("%'-44s", '')."'";?>+
	   	<?="'".sprintf('%-2.1s', '')."'";?>+
	   	<?="'".sprintf("%'-43s", '')."'";?>+'\x0A'+
	   	
	   	// baris 
	   	'\x1B' + '\x21' + '\x18'+ // em mode on
	   	<?="'".sprintf("%'-80s", '')."'";?>+ '\x0A'+
	   	'\x1B' + '\x21' + '\x19'+ // em mode on
	   	<?="'".sprintf('%3.3s', 'NO')."'";?>+
	   	<?="'".sprintf('%3.3s', '')."'";?>+

	   	<?="'".sprintf('%-10.10s', 'JUMLAH')."'";?>+
	   	<?="'".sprintf('%1.1s', '')."'";?>+

	   	<?="'".sprintf('%-7.7s', 'SATUAN')."'";?>+
	   	<?="'".sprintf('%3.3s', '')."'";?>+

		<?="'".sprintf('%4.4s', 'ROLL')."'";?>+
	   	<?="'".sprintf('%3.3s', '')."'";?>+

	   	<?="'".sprintf('%-9.9s', 'NAMA')."'";?>+
	   	<?="'".sprintf('%1.1s', '')."'";?>+
	   	
	   	<?="'".sprintf('%-26.26s', 'KODE')."'";?>+
	   	<?="'".sprintf('%-2.2s', '')."'";?>+

	   	<?="'".sprintf('%8.8s', 'HARGA')."'";?>+
	   	<?="'".sprintf('%1.1s', '')."'";?>+
	   	'\x0A'+

	   	// baris 10
	   	'\x1B' + '\x21' + '\x18'+ // em mode on
	   	<?="'".sprintf("%'-80s", '')."'";?>+ '\x0A'+

	   	// baris 11
	   	'\x1B' + '\x21' + '\x19'+ // em mode on
	   	<?="'".sprintf('%3.3s', '1')."'";?>+
	   	<?="'".sprintf('%3.3s', '')."'";?>+

	   	<?="'".sprintf('%10.10s', '200.00')."'";?>+
	   	<?="'".sprintf('%1.1s', '')."'";?>+

	   	<?="'".sprintf('%-7.7s', 'YARD')."'";?>+
	   	<?="'".sprintf('%3.1s', '')."'";?>+

		<?="'".sprintf('%4.4s', '2')."'";?>+
	   	<?="'".sprintf('%3.1s', '')."'";?>+

	   	<?="'".sprintf('%-9.9s', 'POLYESTER')."'";?>+
	   	<?="'".sprintf('%1.1s', '')."'";?>+
	   	
	   	<?="'".sprintf('%-26.26s', 'COLOMBIA WP GRADE B')."'";?>+
	   	<?="'".sprintf('%-2.2s', '')."'";?>+

	   	<?="'".sprintf('%8.8s', '14.000')."'";?>+
	   	<?="'".sprintf('%1.1s', '')."'";?>+
	   	'\x0A'+

	   	// baris 12
	   	<?="'".sprintf('%3.3s', '2')."'";?>+
	   	<?="'".sprintf('%3.3s', '')."'";?>+

	   	<?="'".sprintf('%10.10s', '300.00')."'";?>+
	   	<?="'".sprintf('%1.1s', '')."'";?>+

	   	<?="'".sprintf('%-7.7s', 'KG')."'";?>+
	   	<?="'".sprintf('%3.1s', '')."'";?>+

		<?="'".sprintf('%4.4s', '30')."'";?>+
	   	<?="'".sprintf('%3.1s', '')."'";?>+

	   	<?="'".sprintf('%-9.9s', 'POLYESTER')."'";?>+
	   	<?="'".sprintf('%1.1s', '')."'";?>+
	   	
	   	<?="'".sprintf('%-26.26s', 'JALA')."'";?>+
	   	<?="'".sprintf('%-2.2s', '')."'";?>+

	   	<?="'".sprintf('%8.8s', '70.000')."'";?>+
	   	<?="'".sprintf('%1.1s', '')."'";?>+
	   	'\x0A'+
	   	// baris 13 / ITEM 3
	   	'\x0A'+
	   	// baris 14 / ITEM 4
	   	'\x0A'+
	   	// baris 15 / ITEM 5
	   	'\x0A'+
	   	// baris 16 / ITEM 6
	   	'\x0A'+
	   	// baris 17 / ITEM 7
	   	'\x0A'+
	   	// baris 18 / ITEM 8
	   	'\x0A'+
	   	// baris 19
	   	'\x1B' + '\x21' + '\x18'+ // em mode on
	   	<?="'".sprintf("%'-80s", '')."'";?>+ '\x0A'+

	   	// baris 20
	   	'\x1B' + '\x21' + '\x19'+ // em mode on
	   	<?="'".sprintf('%5.5s', 'TOTAL')."'";?>+
	   	<?="'".sprintf('%1.1s', '')."'";?>+
	   	<?="'".sprintf('%10.10s', '200.00')."'";?>+
	   	<?="'".sprintf('%1.1s', '')."'";?>+
	   	<?="'".sprintf('%-7.7s', 'YARD')."'";?>+
	   	<?="'".sprintf('%3.1s', '')."'";?>+
		<?="'".sprintf('%4.4s', '2')."'";?>+
	   	<?="'".sprintf('%3.1s', '')."'";?>+
		'\x0A'+

	   	// baris 21
	   	<?="'".sprintf('%5.5s', '')."'";?>+
	   	<?="'".sprintf('%1.1s', '')."'";?>+
	   	<?="'".sprintf('%10.10s', '300.00')."'";?>+
	   	<?="'".sprintf('%1.1s', '')."'";?>+
	   	<?="'".sprintf('%-7.7s', 'KG')."'";?>+
	   	<?="'".sprintf('%1.1s', '')."'";?>+
		<?="'".sprintf('%4.4s', '30')."'";?>+
	   	<?="'".sprintf('%1.1s', '')."'";?>+
		'\x0A'+

	   	// baris 22
	   	'\x1B' + '\x21' + '\x18'+ // em mode on
	   	<?="'".sprintf('%-5.15s', 'Tanda Terima')."'";?>+
	   	'\x0A'+

	   	// baris 23
	   	'\x1B' + '\x21' + '\x19'+ // em mode on
	   	<?="'".sprintf('%-23.23s', 'TANGGAL:10/09/2020')."'";?>+
	   	<?="'".sprintf('%-5.5s', '')."'";?>+
	   	<?="'".sprintf('%-18.18s', 'Checker')."'";?>+
	   	<?="'".sprintf('%-5.5s', '')."'";?>+
	   	<?="'".sprintf('%-18.18s', 'Pengirim')."'";?>+
	   	<?="'".sprintf('%-5.5s', '')."'";?>+
	   	<?="'".sprintf('%-18.18s', 'Hormat Kami')."'";?>+
	   	'\x0A'+
	   	'\x0A'+
	   	'\x1B' + '\x56',          // cut paper
	   	
    ];
	console.log(data);

    webprint.printRaw(data, printer_name);


	// qz.print(config, data).then(function() {
	//    // alert("Sent data to printer");
	// });
}

</script>