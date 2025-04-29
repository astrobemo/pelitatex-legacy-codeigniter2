<script>

function print_testing(printer_name){
	
	// if (idx == 1) {
	// 	qz.websocket.connect().then(function() {
	// 	});
	// };

	// var config = qz.configs.create("Nota");             // Exact printer name from OS
  	var data = ['\x1B' + '\x40'+          // init
	   	'\x1B' + '\x21' + '\x18'+ // em mode on
	   	<?="'".sprintf('%-25.25s', 'CV.PELITA SEJATI ')."'";?>+
	   	<?="'".sprintf('%-30.30s', 'FAKTUR PENJUALAN ')."'";?>+
	   	<?="'".sprintf('%25.25s', 'BANDUNG,02 SEPTEMBER 2020 ')."'";?>+'\x0A'+
	   	'\x1B' + '\x21' + '\x19'+ // baris 2
	   	<?="'".sprintf('%-31.31s', 'JL.MAYOR SUNARYA NO 22, ')."'";?>+
	   	<?="'".sprintf('%-3.3s', 'NO:')."'";?>+
	   	<?="'".sprintf('%-15.15s', 'FPJ020920-02550')."'";?>+
	   	<?="'".sprintf('%-3.3s', '')."'";?>+
	   	<?="'".sprintf('%44.44s', 'Kepada Yth,')."'";?>+'\x0A'+
	   	// baris 3
	   	<?="'".sprintf('%-31.31s', 'BANDUNG, INDONESIA, 40181')."'";?>+
	   	<?="'".sprintf('%-3.3s', 'PO:')."'";?>+
	   	<?="'".sprintf('%-15.15s', 'max 22 char ')."'";?>+
	   	<?="'".sprintf('%-3.3s', '')."'";?>+
	   	<?="'".sprintf('%44.44s', 'CV.ANDRE & SHINTA PRODUCTION (AS PRODUCTION)')."'";?>+'\x0A'+
	   	// baris 4
	   	<?="'".sprintf('%-31.31s', 'TELP:022 2052 6351')."'";?>+
	   	<?="'".sprintf('%-3.3s', 'SJ:')."'";?>+
	   	<?="'".sprintf('%-15.15s', 'SJ020920-02550 ')."'";?>+
	   	<?="'".sprintf('%-3.3s', '')."'";?>+
	   	<?="'".sprintf('%44.44s', 'KOMP TAMAN KOPO INDAH III RUKO BLOK D')."'";?>+'\x0A'+
	   	// baris 5
	   	<?="'".sprintf('%-31.31s', 'NPWP:84.570.005.3-428.000')."'";?>+
	   	<?="'".sprintf('%-3.3s', '')."'";?>+
	   	<?="'".sprintf('%-15.15s', 'SJ020920-02551 ')."'";?>+
	   	<?="'".sprintf('%-3.3s', '')."'";?>+
	   	<?="'".sprintf('%44.44s', 'NO: 57 RT/RW:000/000 KEL.RAHAYU')."'";?>+'\x0A'+
	   	// baris 6
	   	<?="'".sprintf('%-31.31s', '')."'";?>+
	   	<?="'".sprintf('%-3.3s', '')."'";?>+
	   	<?="'".sprintf('%-15.15s', 'SJ020920-02552 ')."'";?>+
	   	<?="'".sprintf('%-3.3s', '')."'";?>+
	   	<?="'".sprintf('%44.44s', 'KEC.MARGAASIH KAB/KOTA. BANDUNG')."'";?>+'\x0A'+
	   	
	   	// baris 7
	   	'\x1B' + '\x21' + '\x18'+ // em mode on
	   	<?="'".sprintf("%'-80s", '')."'";?>+ '\x0A'+
	   	'\x1B' + '\x21' + '\x19'+ // em mode on
	   	<?="'".sprintf('%3.3s', 'NO')."'";?>+
	   	<?="'".sprintf('%3.3s', '')."'";?>+

	   	<?="'".sprintf('%-10.10s', 'JUMLAH')."'";?>+
	   	<?="'".sprintf('%1.1s', '')."'";?>+

	   	<?="'".sprintf('%-7.7s', 'SATUAN')."'";?>+
	   	<?="'".sprintf('%1.1s', '')."'";?>+

		<?="'".sprintf('%4.4s', 'ROLL')."'";?>+
	   	<?="'".sprintf('%1.1s', '')."'";?>+

	   	<?="'".sprintf('%-9.9s', 'NAMA')."'";?>+
	   	<?="'".sprintf('%1.1s', '')."'";?>+
	   	
	   	<?="'".sprintf('%-26.26s', 'KODE')."'";?>+
	   	<?="'".sprintf('%-2.2s', '')."'";?>+

	   	<?="'".sprintf('%8.8s', 'HARGA')."'";?>+
	   	<?="'".sprintf('%1.1s', '')."'";?>+

	   	<?="'".sprintf('%16.16s', 'TOTAL')."'";?>+
	   	'\x0A'+

	   	// baris 8
	   	'\x1B' + '\x21' + '\x18'+ // em mode on
	   	<?="'".sprintf("%'-80s", '')."'";?>+ '\x0A'+

	   	// baris 9
	   	'\x1B' + '\x21' + '\x19'+ // em mode on
	   	<?="'".sprintf('%3.3s', '1')."'";?>+
	   	<?="'".sprintf('%3.3s', '')."'";?>+

	   	<?="'".sprintf('%10.10s', '200.00')."'";?>+
	   	<?="'".sprintf('%1.1s', '')."'";?>+

	   	<?="'".sprintf('%-7.7s', 'YARD')."'";?>+
	   	<?="'".sprintf('%1.1s', '')."'";?>+

		<?="'".sprintf('%4.4s', '2')."'";?>+
	   	<?="'".sprintf('%1.1s', '')."'";?>+

	   	<?="'".sprintf('%-9.9s', 'POLYESTER')."'";?>+
	   	<?="'".sprintf('%1.1s', '')."'";?>+
	   	
	   	<?="'".sprintf('%-26.26s', 'COLOMBIA WP GRADE B')."'";?>+
	   	<?="'".sprintf('%-2.2s', '')."'";?>+

	   	<?="'".sprintf('%8.8s', '14.000')."'";?>+
	   	<?="'".sprintf('%1.1s', '')."'";?>+

	   	<?="'".sprintf('%16.16s', '2.800.000')."'";?>+
	   	'\x0A'+

	   	// baris 10
	   	<?="'".sprintf('%3.3s', '2')."'";?>+
	   	<?="'".sprintf('%3.3s', '')."'";?>+

	   	<?="'".sprintf('%10.10s', '300.00')."'";?>+
	   	<?="'".sprintf('%1.1s', '')."'";?>+

	   	<?="'".sprintf('%-7.7s', 'KG')."'";?>+
	   	<?="'".sprintf('%1.1s', '')."'";?>+

		<?="'".sprintf('%4.4s', '30')."'";?>+
	   	<?="'".sprintf('%1.1s', '')."'";?>+

	   	<?="'".sprintf('%-9.9s', 'POLYESTER')."'";?>+
	   	<?="'".sprintf('%1.1s', '')."'";?>+
	   	
	   	<?="'".sprintf('%-26.26s', 'JALA')."'";?>+
	   	<?="'".sprintf('%-2.2s', '')."'";?>+

	   	<?="'".sprintf('%8.8s', '70.000')."'";?>+
	   	<?="'".sprintf('%1.1s', '')."'";?>+

	   	<?="'".sprintf('%16.16s', '21.000.000')."'";?>+
	   	'\x0A'+
	   	// baris 11
	   	'\x0A'+
	   	// baris 12
	   	'\x0A'+
	   	// baris 13
	   	'\x0A'+
	   	// baris 14
	   	<?="'".sprintf('%5.5s', 'TOTAL')."'";?>+
	   	<?="'".sprintf('%1.1s', '')."'";?>+
	   	<?="'".sprintf('%10.10s', '200.00')."'";?>+
	   	<?="'".sprintf('%1.1s', '')."'";?>+
	   	<?="'".sprintf('%-7.7s', 'YARD')."'";?>+
	   	<?="'".sprintf('%1.1s', '')."'";?>+
		<?="'".sprintf('%4.4s', '2')."'";?>+
	   	<?="'".sprintf('%1.1s', '')."'";?>+

	   	<?="'".sprintf('%-38.38s', '')."'";?>+
	   	
	   	<?="'".sprintf('%-8.8s', 'TOTAL')."'";?>+
	   	<?="'".sprintf('%1.1s', '')."'";?>+
	   	<?="'".sprintf('%16.16s', '23.800.000')."'";?>+
	   	'\x0A'+

	   	// baris 14
	   	<?="'".sprintf('%5.5s', '')."'";?>+
	   	<?="'".sprintf('%1.1s', '')."'";?>+
	   	<?="'".sprintf('%10.10s', '300.00')."'";?>+
	   	<?="'".sprintf('%1.1s', '')."'";?>+
	   	<?="'".sprintf('%-7.7s', 'KG')."'";?>+
	   	<?="'".sprintf('%1.1s', '')."'";?>+
		<?="'".sprintf('%4.4s', '30')."'";?>+
	   	<?="'".sprintf('%1.1s', '')."'";?>+

	   	<?="'".sprintf('%-35.35s', '')."'";?>+
	   	
	   	'\x1B' + '\x21' + '\x93'+ // em mode on
	   	<?="'".sprintf('%-3.3s', '')."'";?>+
	   	<?="'".sprintf('%-8.8s', 'TRANSFER')."'";?>+
	   	<?="'".sprintf('%1.1s', '')."'";?>+
	   	<?="'".sprintf('%16.16s', '20.000.000')."'";?>+
	   	'\x0A'+

	   	// baris 15
	   	'\x1B' + '\x21' + '\x18'+ // em mode on
	   	<?="'".sprintf('%53.53s', '')."'";?>+
	   	<?="'".sprintf("%'-27s", '')."'";?>+
	   	'\x0A'+

	   	// baris 16
	   	'\x1B' + '\x21' + '\x19'+ // em mode on
	   	<?="'".sprintf('%6.6s', '')."'";?>+
	   	<?="'".sprintf('%11.11s', '')."'";?>+
	   	<?="'".sprintf('%8.8s', '')."'";?>+
		<?="'".sprintf('%5.5s', '')."'";?>+
	   	<?="'".sprintf('%-38.38s', '')."'";?>+
	   	<?="'".sprintf('%-8.8s', 'KEMBALI')."'";?>+
	   	<?="'".sprintf('%1.1s', '')."'";?>+
	   	<?="'".sprintf('%16.16s', '0')."'";?>+
	   	'\x0A'+

	   	// baris 17
	   	<?="'".sprintf('%70.70s', '')."'";?>+
	   	'\x1B' + '\x21' + '\x01'+ // em mode on
	   	<?="'".sprintf('%25.25s', '*harga sudah termasuk ppn')."'";?>+
	   	'\x0A'+

	   	// baris 19
	   	'\x1B' + '\x21' + '\x18'+ // em mode on
	   	<?="'".sprintf('%15.15s', 'Tanda Terima')."'";?>+
	   	<?="'".sprintf('%15.15s', 'Checker')."'";?>+
	   	<?="'".sprintf('%34.34s', 'Hormat Kami')."'";?>+
	   	'\x0A'+

	   	// baris 20
	   	'\x0A'+
	   	'\x1B' + '\x69',          // cut paper
	   	
    ];
	console.log(data);

    webprint.printRaw(data, printer_name);


	// qz.print(config, data).then(function() {
	//    // alert("Sent data to printer");
	// });
}

</script>