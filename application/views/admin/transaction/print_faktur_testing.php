<script>

function print_testing(printer_name){
	// if (idx == 1) {
	
	// 	qz.websocket.connect().then(function() {
	// 	});
	// };

	// var config = qz.configs.create("Nota");             // Exact printer name from OS
  	var data = ['\x1B' + '\x40'+          // init
	   	'\x1B' + '\x21' + '\x18'+ // Double Strike, proportional, 10 cpi
	   	<?="'".sprintf('%-21.21s', 'CV.PELITA SEJATI ')."'";?>+
	   	<?="'".sprintf('%-21.21s', 'PACKING LIST')."'";?>+
	   	<?="'".sprintf('%-38.38s', 'BANDUNG,02 SEPTEMBER 2020 ')."'";?>+'\x0A'+
	   	'\x1B' + '\x21' + '\x19'+ // Double Strike, proportional, 12 cpi
	   	<?="'".sprintf('%-26.26s', '')."'";?>+
	   	<?="'".sprintf('%-26.26s', 'NO:PL020920-2550')."'";?>+
	   	<?="'".sprintf('%-44.44s', 'Kepada Yth, ')."'";?>+'\x0A'+
	   	//baris 3
	   	<?="'".sprintf('%-26.26s', '')."'";?>+
	   	<?="'".sprintf('%-26.26s', 'SURAT JALAN:SJ020920-2550')."'";?>+
	   	<?="'".sprintf('%-44.44s', 'CV.ANDRE & SHINTA PRODUCTION (AS PRODUCTION)')."'";?>+'\x0A'+
	   	//baris 4
	   	'\x1B' + '\x21' + '\x18'+ // Double Strike, proportional, 10 cpi
	   	<?="'".sprintf("%'-80s", '')."'";?>+ '\x0A'+
	   	//BARIS 5
		'\x1B' + '\x21' + '\x14'+ // double strike, condensed dari 10 cpi -> 17.14 berarti sekitar 137 char
	   	'\x7C'+
	   	<?="'".sprintf('%-1.1s', '|')."'";?>+
		<?="'".sprintf('%-2.2s', 'NO')."'";?>+
	   	<?="'".sprintf('%-1.1s', '|')."'";?>+
	   	<?="'".sprintf('%-27.27s', 'KODE')."'";?>+
	   	<?="'".sprintf('%-1.1s', '|')."'";?>+
	   	<?="'".sprintf('%-21.21s', 'WARNA')."'";?>+
	   	<?="'".sprintf('%-1.1s', '|')."'";?>+
	   	<?="'".sprintf('%-4.4s', 'SAT')."'";?>+
	   	<?="'".sprintf('%-1.1s', '|')."'";?>+
	   	<?="'".sprintf('%-4.4s', 'ROLL')."'";?>+
	   	<?="'".sprintf('%-1.1s', '|')."'";?>+
	   	<?="'".sprintf('%-8.8s', 'TOTAL')."'";?>+
	   	<?="'".sprintf('%-1.1s', '|')."'";?>+
	   	<?="'".sprintf('%-60.60s', 'DETAIL')."'";?>+
	   	<?="'".sprintf('%-1.1s', '|')."'";?>+'\x0A'+
		//baris 6
	   	'\x1B' + '\x21' + '\x18'+ // Double Strike, proportional, 10 cpi
	   	<?="'".sprintf("%'-80s", '')."'";?>+ '\x0A'+

		'\x1B' + '\x21' + '\x14'+ // double strike, condensed dari 10 cpi -> 17.14 berarti sekitar 137 char
	   	'\x7C'+
	   	<?="'".sprintf('%2.2s', '1')."'";?>+
	   	<?="'".sprintf('%-1.1s', '|')."'";?>+
	   	<?="'".sprintf('%-27.27s', 'Nylon Taslan High WP 2000mm')."'";?>+
	   	<?="'".sprintf('%-1.1s', '|')."'";?>+
	   	<?="'".sprintf('%-21.21s', 'Loreng Digital Green')."'";?>+
	   	<?="'".sprintf('%-1.1s', '|')."'";?>+
	   	<?="'".sprintf('%-4.4s', 'YARD')."'";?>+
	   	<?="'".sprintf('%-1.1s', '|')."'";?>+
	   	<?="'".sprintf('%4.4s', '20')."'";?>+
	   	<?="'".sprintf('%-1.1s', '|')."'";?>+
	   	<?="'".sprintf('%8.8s', '2.000')."'";?>+
	   	<?="'".sprintf('%-1.1s', '|')."'";?>+
	   	'\x1B' + '\x21' + '\x11'+ // double strike, 12 cpi
	   	<?for ($i = 0; $i < 10; $i++) {?>
	   		<?="'".sprintf('%4.4s', '100')."'";?>+
	   	<?};?>
	   	//=============================================
	   	'\x1B' + '\x21' + '\x14'+ // double strike, condensed dari 10 cpi -> 17.14 berarti sekitar 137 char
	   	<?="'".sprintf('%3.3s', '')."'";?>+
	   	'\x7C'+'\x0A'+

	   	'\x7C'+
	   	<?="'".sprintf('%2.2s', '')."'";?>+
	   	<?="'".sprintf('%-1.1s', '|')."'";?>+
	   	<?="'".sprintf('%-27.27s', '')."'";?>+
	   	<?="'".sprintf('%-1.1s', '|')."'";?>+
	   	<?="'".sprintf('%-21.21s', '')."'";?>+
	   	<?="'".sprintf('%-1.1s', '|')."'";?>+
	   	<?="'".sprintf('%-4.4s', '')."'";?>+
	   	<?="'".sprintf('%-1.1s', '|')."'";?>+
	   	<?="'".sprintf('%4.4s', '')."'";?>+
	   	<?="'".sprintf('%-1.1s', '|')."'";?>+
	   	<?="'".sprintf('%8.8s', '')."'";?>+
	   	<?="'".sprintf('%-1.1s', '|')."'";?>+
	   	'\x1B' + '\x21' + '\x11'+ // double strike, 12 cpi
	   	<?for ($i = 0; $i < 10; $i++) {?>
	   		<?="'".sprintf('%4.4s', '100')."'";?>+
	   	<?};?>
	   	//=============================================
	   	'\x1B' + '\x21' + '\x14'+ // double strike, condensed dari 10 cpi -> 17.14 berarti sekitar 137 char
	   	<?="'".sprintf('%3.3s', '')."'";?>+
	   	'\x7C'+'\x0A'+

	   	'\x7C'+
	   	<?="'".sprintf('%2.2s', '2')."'";?>+
	   	<?="'".sprintf('%-1.1s', '|')."'";?>+
	   	<?="'".sprintf('%-27.27s', 'Nylon Taslan High WP 2000mm')."'";?>+
	   	<?="'".sprintf('%-1.1s', '|')."'";?>+
	   	<?="'".sprintf('%-21.21s', 'Loreng Digital Green')."'";?>+
	   	<?="'".sprintf('%-1.1s', '|')."'";?>+
	   	<?="'".sprintf('%-4.4s', 'YARD')."'";?>+
	   	<?="'".sprintf('%-1.1s', '|')."'";?>+
	   	<?="'".sprintf('%4.4s', '20')."'";?>+
	   	<?="'".sprintf('%-1.1s', '|')."'";?>+
	   	<?="'".sprintf('%8.8s', '2.000')."'";?>+
	   	<?="'".sprintf('%-1.1s', '|')."'";?>+
	   	'\x1B' + '\x21' + '\x11'+ // double strike, 12 cpi
	   	<?for ($i = 0; $i < 10; $i++) {?>
	   		<?="'".sprintf('%4.4s', '100')."'";?>+
	   	<?};?>
	   	//=============================================
	   	'\x1B' + '\x21' + '\x14'+ // double strike, condensed dari 10 cpi -> 17.14 berarti sekitar 137 char
	   	<?="'".sprintf('%3.3s', '')."'";?>+
	   	'\x7C'+'\x0A'+

	   	'\x7C'+
	   	<?="'".sprintf('%2.2s', '')."'";?>+
	   	<?="'".sprintf('%-1.1s', '|')."'";?>+
	   	<?="'".sprintf('%-27.27s', '')."'";?>+
	   	<?="'".sprintf('%-1.1s', '|')."'";?>+
	   	<?="'".sprintf('%-21.21s', '')."'";?>+
	   	<?="'".sprintf('%-1.1s', '|')."'";?>+
	   	<?="'".sprintf('%-4.4s', '')."'";?>+
	   	<?="'".sprintf('%-1.1s', '|')."'";?>+
	   	<?="'".sprintf('%4.4s', '')."'";?>+
	   	<?="'".sprintf('%-1.1s', '|')."'";?>+
	   	<?="'".sprintf('%8.8s', '')."'";?>+
	   	<?="'".sprintf('%-1.1s', '|')."'";?>+
	   	'\x1B' + '\x21' + '\x11'+ // double strike, 12 cpi
	   	<?for ($i = 0; $i < 10; $i++) {?>
	   		<?="'".sprintf('%4.4s', '100')."'";?>+
	   	<?};?>
	   	//=============================================
	   	'\x1B' + '\x21' + '\x14'+ // double strike, condensed dari 10 cpi -> 17.14 berarti sekitar 137 char
	   	<?="'".sprintf('%3.3s', '')."'";?>+
	   	'\x7C'+'\x0A'+
		
	   	'\x7C'+
	   	<?="'".sprintf('%2.2s', '3')."'";?>+
	   	<?="'".sprintf('%-1.1s', '|')."'";?>+
	   	<?="'".sprintf('%-27.27s', 'JALA')."'";?>+
	   	<?="'".sprintf('%-1.1s', '|')."'";?>+
	   	<?="'".sprintf('%-21.21s', 'Neon Volt')."'";?>+
	   	<?="'".sprintf('%-1.1s', '|')."'";?>+
	   	<?="'".sprintf('%-4.4s', 'KG')."'";?>+
	   	<?="'".sprintf('%-1.1s', '|')."'";?>+
	   	<?="'".sprintf('%4.4s', '10')."'";?>+
	   	<?="'".sprintf('%-1.1s', '|')."'";?>+
	   	<?="'".sprintf('%8.8s', '512,73')."'";?>+
	   	<?="'".sprintf('%-1.1s', '|')."'";?>+
	   	//=============================================1-5
	   	'\x1B' + '\x21' + '\x11'+ // double strike, 12 cpi
	   	<?="'".sprintf('%7.6s', '23.33')."'";?>+
	   	<?="'".sprintf('%7.6s', '27.72')."'";?>+
	   	<?="'".sprintf('%7.6s', '29.29')."'";?>+
	   	<?="'".sprintf('%7.6s', '29.99')."'";?>+
	   	<?="'".sprintf('%7.6s', '30.22')."'";?>+
	   	//=============================================
	   	<?="'".sprintf('%5.5s', '')."'";?>+
	   	'\x1B' + '\x21' + '\x14'+ // double strike, condensed dari 10 cpi -> 17.14 berarti sekitar 137 char
	   	<?="'".sprintf('%3.3s', '')."'";?>+
	   	'\x7C'+'\x0A'+

	   	'\x7C'+
	   	<?="'".sprintf('%2.2s', '')."'";?>+
	   	<?="'".sprintf('%-1.1s', '|')."'";?>+
	   	<?="'".sprintf('%-27.27s', '')."'";?>+
	   	<?="'".sprintf('%-1.1s', '|')."'";?>+
	   	<?="'".sprintf('%-21.21s', '')."'";?>+
	   	<?="'".sprintf('%-1.1s', '|')."'";?>+
	   	<?="'".sprintf('%-4.4s', '')."'";?>+
	   	<?="'".sprintf('%-1.1s', '|')."'";?>+
	   	<?="'".sprintf('%4.4s', '')."'";?>+
	   	<?="'".sprintf('%-1.1s', '|')."'";?>+
	   	<?="'".sprintf('%8.8s', '')."'";?>+
	   	<?="'".sprintf('%-1.1s', '|')."'";?>+
	   	//=============================================6-10
	   	'\x1B' + '\x21' + '\x11'+ // double strike, 12 cpi
	   	<?="'".sprintf('%7.6s', '31.11')."'";?>+
	   	<?="'".sprintf('%7.6s', '31.72')."'";?>+
	   	<?="'".sprintf('%7.6s', '40.00')."'";?>+
	   	<?="'".sprintf('%7.6s', '45.32')."'";?>+
	   	<?="'".sprintf('%7.6s', '52.72')."'";?>+
	   	//=============================================
	   	<?="'".sprintf('%5.5s', '')."'";?>+
	   	'\x1B' + '\x21' + '\x14'+ // double strike, condensed dari 10 cpi -> 17.14 berarti sekitar 137 char
	   	<?="'".sprintf('%3.3s', '')."'";?>+
	   	'\x7C'+'\x0A'+


	   	'\x7C'+
	   	<?="'".sprintf('%2.2s', '4')."'";?>+
	   	<?="'".sprintf('%-1.1s', '|')."'";?>+
	   	<?="'".sprintf('%-27.27s', 'DIADORA')."'";?>+
	   	<?="'".sprintf('%-1.1s', '|')."'";?>+
	   	<?="'".sprintf('%-21.21s', 'Hijau')."'";?>+
	   	<?="'".sprintf('%-1.1s', '|')."'";?>+
	   	<?="'".sprintf('%-4.4s', 'KG')."'";?>+
	   	<?="'".sprintf('%-1.1s', '|')."'";?>+
	   	<?="'".sprintf('%4.4s', '20')."'";?>+
	   	<?="'".sprintf('%-1.1s', '|')."'";?>+
	   	<?="'".sprintf('%8.8s', '868,80')."'";?>+
	   	<?="'".sprintf('%-1.1s', '|')."'";?>+
	   	//=============================================1-6
	   	'\x1B' + '\x21' + '\x11'+ // double strike, 12 cpi
	   	<?="'".sprintf('%7.6s', '23.33')."'";?>+
	   	<?="'".sprintf('%7.6s', '24.43')."'";?>+
	   	<?="'".sprintf('%7.6s', '25.03')."'";?>+
	   	<?="'".sprintf('%7.6s', '25.72')."'";?>+
	   	<?="'".sprintf('%7.6s', '27.72')."'";?>+
	   	//=============================================
	   	<?="'".sprintf('%5.5s', '')."'";?>+
	   	'\x1B' + '\x21' + '\x14'+ // double strike, condensed dari 10 cpi -> 17.14 berarti sekitar 137 char
	   	<?="'".sprintf('%3.3s', '')."'";?>+
	   	'\x7C'+'\x0A'+

	   	'\x7C'+
	   	<?="'".sprintf('%2.2s', '')."'";?>+
	   	<?="'".sprintf('%-1.1s', '|')."'";?>+
	   	<?="'".sprintf('%-27.27s', '')."'";?>+
	   	<?="'".sprintf('%-1.1s', '|')."'";?>+
	   	<?="'".sprintf('%-21.21s', '')."'";?>+
	   	<?="'".sprintf('%-1.1s', '|')."'";?>+
	   	<?="'".sprintf('%-4.4s', '')."'";?>+
	   	<?="'".sprintf('%-1.1s', '|')."'";?>+
	   	<?="'".sprintf('%4.4s', '')."'";?>+
	   	<?="'".sprintf('%-1.1s', '|')."'";?>+
	   	<?="'".sprintf('%8.8s', '')."'";?>+
	   	<?="'".sprintf('%-1.1s', '|')."'";?>+
	   	//=============================================6-10
	   	'\x1B' + '\x21' + '\x11'+ // double strike, 12 cpi
	   	<?="'".sprintf('%7.6s', '29.29')."'";?>+
	   	<?="'".sprintf('%7.6s', '29.99')."'";?>+
	   	<?="'".sprintf('%7.6s', '30.22')."'";?>+
	   	<?="'".sprintf('%7.6s', '30.62')."'";?>+
	   	<?="'".sprintf('%7.6s', '30.99')."'";?>+
	   	//=============================================
	   	<?="'".sprintf('%5.5s', '')."'";?>+
	   	'\x1B' + '\x21' + '\x14'+ // double strike, condensed dari 10 cpi -> 17.14 berarti sekitar 137 char
	   	<?="'".sprintf('%3.3s', '')."'";?>+
	   	'\x7C'+'\x0A'+

	   	'\x7C'+
	   	<?="'".sprintf('%2.2s', '')."'";?>+
	   	<?="'".sprintf('%-1.1s', '|')."'";?>+
	   	<?="'".sprintf('%-27.27s', '')."'";?>+
	   	<?="'".sprintf('%-1.1s', '|')."'";?>+
	   	<?="'".sprintf('%-21.21s', '')."'";?>+
	   	<?="'".sprintf('%-1.1s', '|')."'";?>+
	   	<?="'".sprintf('%-4.4s', '')."'";?>+
	   	<?="'".sprintf('%-1.1s', '|')."'";?>+
	   	<?="'".sprintf('%4.4s', '')."'";?>+
	   	<?="'".sprintf('%-1.1s', '|')."'";?>+
	   	<?="'".sprintf('%8.8s', '')."'";?>+
	   	<?="'".sprintf('%-1.1s', '|')."'";?>+
	   	//=============================================11-15
	   	'\x1B' + '\x21' + '\x11'+ // double strike, 12 cpi
	   	<?="'".sprintf('%7.6s', '31.11')."'";?>+
	   	<?="'".sprintf('%7.6s', '31.55')."'";?>+
	   	<?="'".sprintf('%7.6s', '31.72')."'";?>+
	   	<?="'".sprintf('%7.6s', '32.05')."'";?>+
	   	<?="'".sprintf('%7.6s', '34.50')."'";?>+
	   	//=============================================
	   	<?="'".sprintf('%5.5s', '')."'";?>+
	   	'\x1B' + '\x21' + '\x14'+ // double strike, condensed dari 10 cpi -> 17.14 berarti sekitar 137 char
	   	<?="'".sprintf('%3.3s', '')."'";?>+
	   	'\x7C'+'\x0A'+


	   	'\x7C'+
	   	<?="'".sprintf('%2.2s', '')."'";?>+
	   	<?="'".sprintf('%-1.1s', '|')."'";?>+
	   	<?="'".sprintf('%-27.27s', '')."'";?>+
	   	<?="'".sprintf('%-1.1s', '|')."'";?>+
	   	<?="'".sprintf('%-21.21s', '')."'";?>+
	   	<?="'".sprintf('%-1.1s', '|')."'";?>+
	   	<?="'".sprintf('%-4.4s', '')."'";?>+
	   	<?="'".sprintf('%-1.1s', '|')."'";?>+
	   	<?="'".sprintf('%4.4s', '')."'";?>+
	   	<?="'".sprintf('%-1.1s', '|')."'";?>+
	   	<?="'".sprintf('%8.8s', '')."'";?>+
	   	<?="'".sprintf('%-1.1s', '|')."'";?>+
	   	//=============================================16-20
	   	'\x1B' + '\x21' + '\x11'+ // double strike, 12 cpi
	   	<?="'".sprintf('%7.6s', '35.16')."'";?>+
	   	<?="'".sprintf('%7.6s', '37.06')."'";?>+
	   	<?="'".sprintf('%7.6s', '39.11')."'";?>+
	   	<?="'".sprintf('%7.6s', '45.32')."'";?>+
	   	<?="'".sprintf('%7.6s', '52.72')."'";?>+
	   	//=============================================
	   	<?="'".sprintf('%5.5s', '')."'";?>+
	   	'\x1B' + '\x21' + '\x14'+ // double strike, condensed dari 10 cpi -> 17.14 berarti sekitar 137 char
	   	<?="'".sprintf('%3.3s', '')."'";?>+
	   	'\x7C'+'\x0A'+

	   	//============================garis bawah=====================
	   	<?="'".sprintf("%'-134s", '')."'";?>+'\x0A'+
	   	//============================rekap=====================
	   	<?="'".sprintf('%-31.31s', '')."'";?>+
	   	<?="'".sprintf('%-1.1s', '|')."'";?>+
	   	<?="'".sprintf('%21.21s', 'TOTAL')."'";?>+
	   	<?="'".sprintf('%-1.1s', '|')."'";?>+
	   	<?="'".sprintf('%-4.4s', 'YARD')."'";?>+
	   	<?="'".sprintf('%-1.1s', '|')."'";?>+
	   	<?="'".sprintf('%4.4s', '60')."'";?>+
	   	<?="'".sprintf('%-1.1s', '|')."'";?>+
	   	<?="'".sprintf('%8.8s', '4.000')."'";?>+
	   	<?="'".sprintf('%-1.1s', '|')."'";?>+'\x0A'+

	   	<?="'".sprintf('%-31.31s', '')."'";?>+
	   	<?="'".sprintf('%-1.1s', '|')."'";?>+
	   	<?="'".sprintf('%21.21s', '')."'";?>+
	   	<?="'".sprintf('%-1.1s', '|')."'";?>+
	   	<?="'".sprintf('%-4.4s', 'KG')."'";?>+
	   	<?="'".sprintf('%-1.1s', '|')."'";?>+
	   	<?="'".sprintf('%4.4s', '30')."'";?>+
	   	<?="'".sprintf('%-1.1s', '|')."'";?>+
	   	<?="'".sprintf('%8.8s', '1.379,53')."'";?>+
	   	<?="'".sprintf('%-1.1s', '|')."'";?>+'\x0A'+

	   	<?="'".sprintf('%-31.31s', '')."'";?>+
	   	<?="'".sprintf("%'-42s",'')."'";?>+
	   	



	   	'\x0A'+
	   	'\x1B' + '\56',// cut paper
	   	
    ];
	console.log(data);

    webprint.printRaw(data, printer_name);


	// qz.print(config, data).then(function() {
	//    // alert("Sent data to printer");
	// });
}

</script>