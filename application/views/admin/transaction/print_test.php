<script>

function print_test(printer_name){
	
	var data = ['\x1B' + '\x40',          // init
	   	'\x1B' + '\x21' + '\x39'+ // em mode on
		'\x1B' + '\x21' + '\x18'+ // em mode on

	<?
	for ($i=70; $i < 90 ; $i++) { ?>
	   	'\x1B' + '\x21' + '\x<?=$i?>'+ // em mode on
		'\x09'+"<?=$i?>. FAKTUR PENJUALAN" + '\x0A'+
	<?}
	?>

	'\x1B' + '\x69',          // cut paper
	   	'\x10' + '\x14' + '\x01' + '\x00' + '\x05',  // Generate Pulse to kick-out cash drawer**
	                                                // **for legacy drawer cable CD-005A.  Research before using.
	                                                // see also http://keyhut.com/popopen4.htm
    ];
	
	console.log(data);

	webprint.printRaw(data, printer_name);
	
}

</script>