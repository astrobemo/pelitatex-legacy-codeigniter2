<script>

function print_connect_test(printer_name){
	
	var data = ['\x1B' + '\x40',          // init
	   	'\x1B' + '\x21' + '\x39'+ // em mode on
		'\x1B' + '\x21' + '\x18'+ // em mode on
		'.'+
		'\x1B' + '\x69',          // cut paper
	];
	
	console.log(data);

	webprint.printRaw(data, printer_name);
	
}

</script>