<script>

function print_testing(printer_name){
	
	var data = ['\x1B' + '\x40' +          // init	   	
	   	'\x1B' + '\x57'+ '\x31' + 
	   	<?
	   	$idx = 1;
	   	for ($i=1; $i < 10 ; $i++) { ?>
		   	'\x1B' + '\x21' + '\x0<?=$i?>'+ // em mode on
		   	'\x09'+'\x09'+'\x09'+'<?=$idx;?>'+
		   	'\x0A'+
	   	<?$idx++;}?>

	   	<?for ($i=10; $i < 65 ; $i++) { ?>
		   	'\x1B' + '\x21' + '\x<?=$i?>'+ // em mode on
		   	'\x09'+'\x09'+'\x09'+'<?=$idx;?>'+'\x0A'+
		   	<?$idx++;
		   	if ($i%31==0) {
		   		$idx = 1;
		   		?>
			   	'\x0A'+
	   		<?}
	   	}?>

	   	'\x1B' + '\x21' + '\x00'+ // em mode off

	   	'\x1B' + '\x2A' + '\x30' + '\x38' + '\x30' + '\x38'
	   	// '\x1B' + '\x4A' + '\x38'

	   	//'\x1B' + '\x2A' + '\x38' + '\x38' + '\x2A' + '60' + '66' + '165' + '129' + '153' + '66' + '60'

	   	,
	   	//'\x10' + '\x14' + '\x01' + '\x00' + '\x05',  // Generate Pulse to kick-out cash drawer**
	                                                // **for legacy drawer cable CD-005A.  Research before using.
	                                                // see also http://keyhut.com/popopen4.htm
    ];

	console.log(data);

	webprint.printRaw(data, printer_name);
	

	
}

</script>