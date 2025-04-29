<script>

function print_testing(printer_name){
	
	// if (idx == 1) {
	// 	qz.websocket.connect().then(function() {
	// 	});
	// };

	// var config = qz.configs.create("Nota");             // Exact printer name from OS
  	var data = ['\x1B' + '\x40'+          // init
	   	'\x1B' + '\x21' + '\x39'+ // em mode on
	   	<?="'".sprintf("%'-48s", '')."'";?>+ '\x0A'+
	   	'\x1B' + '\x21' + '\x18'+ // em mode on
	   	<?="'".sprintf("%'-80s", '')."'";?>+ '\x0A'+
	   	<?="'".sprintf("%'-80s", '')."'";?>+ '\x0A'+
	   	<?="'".sprintf("%'-80s", '')."'";?>+ '\x0A'+
	   	<?="'".sprintf("%'-80s", '')."'";?>+ '\x0A'+
	   	<?="'".sprintf("%'-80s", '')."'";?>+ '\x0A'+
	   	<?="'".sprintf("%'-80s", '')."'";?>+ '\x0A'+

	   	'\x1B' + '\x21' + '\x19'+ // em mode on
	   	<?="'".sprintf("%'-95s", '')."'";?>+ '\x0A'+
	   	<?="'".sprintf("%'-95s", '')."'";?>+ '\x0A'+
	   	<?="'".sprintf("%'-95s", '')."'";?>+ '\x0A'+
	   	'\x0A'+
	   	'\x0A'+
	   	'\x0A'+
	   	'\x0A'+
	   	<?="'".sprintf("%'-95s", '')."'";?>+ '\x0A'+
	   	<?="'".sprintf("%'-95s", '')."'";?>+ '\x0A'+
	   	<?="'".sprintf("%'-95s", '')."'";?>+ '\x0A'+
	   	<?="'".sprintf("%'-95s", '')."'";?>+ '\x0A'+
	   	+ '\x0A'+

	   	'\x1B' + '\x21' + '\x18'+ // em mode on
	   	<?="'".sprintf("%'-80s", '')."'";?>+ '\x0A'+
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