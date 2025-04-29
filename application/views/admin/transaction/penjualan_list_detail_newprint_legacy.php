
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>PRINT</title>

	<style>
		@media print {
			body * {
				visibility: hidden;
			}
			#page2, #page2 *{
				visibility: visible;
			}

			#page2 {
				position: absolute;
				left: 0;
				top: 0;
			}
		}
	</style>

	
<script src="<?php echo base_url('assets/global/plugins/jquery.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/jquery-migrate.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/jquery-ui/jquery-ui.min.js'); ?>" type="text/javascript"></script>
</head>




<?

	$status_aktif  ='';
	$penjualan_id = '';
	$customer_id = '';
	$nama_customer = '';
	$gudang_id = '';
	$nama_gudang = '';
	$no_faktur = '';
	$tanggal = date('d/m/Y');
	$tanggal_print = '';
	$ori_tanggal = '';
	$po_number = '';
	$note_info = ''; 

	$jatuh_tempo = date('d/m/Y', strtotime("+60 days") );
	$ori_jatuh_tempo = '';
	$status = -99;

	$diskon = 0;
	$ongkos_kirim = 0;
	$nama_keterangan = '';
	$alamat_keterangan = '';
	$kota = '';
	$keterangan = '';
	$penjualan_type_id = 3;
	$tipe_penjualan = '';
	$customer_id = '';
	$no_faktur_lengkap = '';
	$no_surat_jalan = '';
	$no_packing_list = '';
	$fp_status = 1;

	$nik = '';
	$npwp = '';

	$g_total = 0;
	$readonly = '';
	$disabled = '';
	$alamat_customer = '';
	$disabled_status = '';
	$bg_info = '';
	$closed_date = '';
	$warning_type = 0;
	$limit_warning_amount = 0;
	$limit_amount = 0;
	$batas_atas = 0;
	$limit_sisa_atas = 0;
	$sisa_update = 0;
	$limit_sisa = 0;
	$ppn = $ppn_set;

	$hidden_spv = '';
	$fontFamily = '';

	$MAXROW = 8;

	if (is_posisi_id() != 1) {
		$hidden_spv = 'hidden';
		// $fontFamily = "font-family:calibri";
	}

	foreach ($penjualan_data as $row) {
		$tipe_penjualan = $row->tipe_penjualan;
		$penjualan_id = $row->id;
		$customer_id = $row->customer_id;
		$nama_customer = $row->nama_keterangan;
		$alamat_filter_0 = str_replace('BLOK - ', '', $row->alamat_keterangan);
		$alamat_filter_0_1 = str_replace('No.- ', '', $alamat_filter_0);
		$alamat_filter_1 = str_replace('RT:000 RW:000 ', '', $alamat_filter_0_1);
		$alamat_filter_2 = str_replace('Kel.-', '', $alamat_filter_1);
		$alamat_filter_3 = str_replace('Kel.-', ',', $alamat_filter_2);
		// $alamat_filter_4 = str_replace('Kec.', ',', $alamat_filter_3);
		$alamat_final = $alamat_filter_3;
		$alamat_customer = $alamat_final;
		// $gudang_id = $row->gudang_id;
		// $nama_gudang = $row->nama_gudang;
		$no_faktur = $row->no_faktur;
		$penjualan_type_id = $row->penjualan_type_id; 
		$po_number = $row->po_number;
		$fp_status = $row->fp_status;
		$iClass = '';

		$closed_date = $row->closed_date;
		
		$tanggal_print = date('d F Y', strtotime($row->tanggal));

		$tanggal = is_reverse_date($row->tanggal);
		$ori_tanggal = $row->tanggal;
		$status_cek = 0;

		if ($penjualan_type_id == 1) {
			$note_info = "note note-info";
			$bg_info = "background: #95a5a6";

		}elseif ($penjualan_type_id == 2) {
			$note_info = "note note-warning";
			$bg_info = "background: #d35400";
		}elseif ($penjualan_type_id == 3) {
			$note_info = "note note-success";
			$bg_info = "background: #2980b9";
		}


		if ($penjualan_type_id == 2) {
			$dt = strtotime(' +'.get_jatuh_tempo($customer_id).' days', strtotime($row->tanggal) );
			if ($row->jatuh_tempo == $row->tanggal) {
				$status_cek = 1;
			}
		}
		$get_jt = '';
		if (isset($dt)) {
			$get_jt = ($row->jatuh_tempo == '' || $status_cek == 1  ? date('Y-m-d',$dt) : $row->jatuh_tempo);
		}
		// print_r($get_jt);
		$jatuh_tempo = is_reverse_date($get_jt);
		$ori_jatuh_tempo = $row->jatuh_tempo;
		$status = $row->status;
		
		$diskon = $row->diskon;
		$ongkos_kirim = $row->ongkos_kirim;
		$status_aktif = $row->status_aktif;
		$nama_keterangan = $row->nama_keterangan;
		$alamat_keterangan = $alamat_customer;
		$kota = $row->kota;
		$keterangan = $row->keterangan;
		$customer_id = $row->customer_id;
		$no_faktur_lengkap = $row->no_faktur_lengkap;
		$no_surat_jalan = $row->no_surat_jalan;
		$no_packing_list = $row->no_packing_list;
		$ppn = $row->ppn;

		if ($status_aktif == -1 ) {
			$note_info = 'note note-danger';
		}

	}

	$ppn_fix = $ppn;

	$nama_bank = '';
	$no_rek_bank = '';
	$tanggal_giro = '';
	$jatuh_tempo_giro = '';
	$no_akun = '';

	foreach ($data_giro as $row) {
		$nama_bank = $row->nama_bank;
		$no_rek_bank = $row->no_rek_bank;
		$tanggal_giro =is_reverse_date($row->tanggal_giro) ;
		$jatuh_tempo_giro = is_reverse_date($row->jatuh_tempo);
		$no_akun = $row->no_akun;
	}

	$tanggal_ambil = '';
	$tipe_ambil_barang_id = '';
	$status_ambil = '';
	/*foreach ($penjualan_posisi_barang as $row) {
		$tipe_ambil_barang_id = $row->tipe_ambil_barang_id;
		$tanggal_ambil = is_reverse_date($row->tanggal_pengambilan);
		$status_ambil = $row->status;
	}*/

	if ($status != 1) {
		if ( is_posisi_id() != 1 ) {
			$readonly = 'readonly';
		}
	}

	foreach ($customer_data as $row) {
		$nik = $row->nik;
		$npwp = $row->npwp;
	}

	if ($penjualan_id == '') {
		$disabled = 'disabled';
	}

	if ($status != 0) {
		$disabled_status = 'disabled';
	}

	$lock_ = '';
	$read_ = '';
	if (is_posisi_id() == 6) {
		// $disabled = 'disabled';
		// $readonly = 'readonly';
	}

	$nama_toko = '';
	$alamat_toko = '';
	$telepon_toko = '';
	$fax = '';
	$npwp = '';
	$kota_toko = "";
	$npwp_toko = "";



	if ($penjualan_id != '') {

		foreach ($data_toko as $row) {
			$nama_toko = trim($row->nama);
			$alamat_toko = trim($row->alamat);
			$telepon_toko = trim($row->telepon);
			$fax = trim($row->fax);
			$npwp_toko = trim($row->NPWP);
			$kota_toko = trim($row->kota);

		}

		$garis1 = "'-";
		$garis2 = "=";

		$cek_alamat = preg_split('/\r\n|[\r\n]/', $alamat_keterangan);
		$array_rep = ["\n","\r"];
		$alamat_keterangan = str_replace($array_rep, ' ', $alamat_keterangan);

		$alamat1 = substr(strtoupper(trim($alamat_keterangan)), 0,47);
		$alamat2 = substr(strtoupper(trim($alamat_keterangan)), 47);
		$last_1 = substr($alamat1, -1,1);
		$last_2 = substr($alamat2, 0,1);

		$positions = array();
		$pos = -1;
		while (($pos = strpos(trim($alamat_keterangan)," ", $pos+1 )) !== false) {
			$positions[] = $pos;
		}

		$max = 47;
		if ($last_1 != '' && $last_2 != '') {
			$posisi =array_filter(array_reverse($positions),
				function($value) use ($max) {
					return $value <= $max;
				});

			$posisi = array_values($posisi);

			$alamat1 = substr(strtoupper(trim($alamat_keterangan)), 0,$posisi[0]);
			$alamat2 = substr(strtoupper(trim($alamat_keterangan)), $posisi[0]);
		}
	}
	
	
?>
<?

	$TOTALROW = 0;
	$TOTALPAGE = 1;
	$PAGEDETAIL = 1;
	$TOTALBARANG = 0;
	$row_detail_print = [];
	$is_packing_list = false;
	$is_sj = false;
	$is_print_harga = true;
	

	$bT =  'border-top:1px solid #ccc; ';
	$bL =  'border-left:1px solid #ccc; ';
	$bR =  'border-right:1px solid #ccc; ';
	$bB =  'border-bottom:1px solid #ccc; ';

	

	$bdT =  'border-top:1px dashed #ccc; ';
	$bdL =  'border-left:1px dashed #ccc; ';
	$bdR =  'border-right:1px dashed #ccc; ';
	$bdB =  'border-bottom:1px dashed #ccc; ';
	

	$pR1 = 'padding-right:1mm; ';
	$pR2 = 'padding-right:1.3mm; ';

	$row_sj = 0;



	include_once "print/printHTML/print_invoice_header.php";
	include_once "print/printHTML/print_invoice_body.php";
	
	// 1. print faktur
	// 2. print faktur detail
	// 3. print faktur detail+sj+packing list

	if($print_type == 3 || $print_type == 4){
		$is_sj = true;
		$is_packing_list = true;
		
		if ($print_type == 4) {
			$is_print_harga = false;
		}
		include_once "print/printHTML/print_sj_header.php";
		include_once "print/printHTML/print_sj_body.php";
		include_once "print/printHTML/print_packing_list_header.php";
		include_once "print/printHTML/print_packing_list_body.php";
	}else if ($print_type != 1) {
		if (is_posisi_id() != 1) {
			include_once "print/printHTML/print_packing_list_body.php";
		}else{
			include_once "print/printHTML/print_packing_list_body.php";
			// include_once "print/printHTML/print_packing_list_body_bydiv.php";
		}
	}

	// if ($TOTALBARANG > 9) {
	// 	$TOTALPAGE++;
	// }

	// if ($print_type == 1 || $TOTALROW < 8) {
	// 	$TOTALPAGE = 1;
	// }

?>
	
<body>
	<div style="width:10wv; height:100vh; padding:10px; background:#ddd; position:fixed; right:0">
		Printers: <?=(is_posisi_id()==1 ? $TOTALROW : "");?> <br/>
		<select style="width:150px" id="printerlist"></select><br/>
		<input style="width:150px" id="pageList" placeholder><br/>
		<button onclick="webprint.requestPrinters();">Refresh</button><br/>
		<!-- <button onclick="webprint.printRaw(esc_init+esc_p+esc_init, $('#printerlist').val());">Cash Draw</button><br/>
		<button onclick="webprint.printRaw(getEscSample($('#cutter').is(':checked'),$('#image').is(':checked')), $('#printerlist').val());">Print</button><br/> -->
		<button data-dismiss="modal" id="printBtn" style='margin-top:10px; height:50px;width:100%;' onclick="printFakturHTML()">Print</button><br/>
	</div>
	<div style="width:90wv;overflow:auto;">
		<div>
			<div style="width:650px; height:139mm; border:0.1mm solid #ccc; padding:1mm; position:relative">
				<div style="height:120mm">
					<?=$table_header_jual;?>		
					<?=$table_barang;?>
					<?if ($TOTALROW < $MAXROW && $print_type == 2) {
							echo $packing_list_start;
							echo $packing_list_title;
							echo $packing_list_body[0]."</table>";
					}?>
	
				</div>
	
				<div style="position:absolute; bottom:10mm;">
					<table style="font-size:3mm;"  width="600px" >
						<tr>
							<td style="text-align:center">TANDA TERIMA <?=(is_posisi_id()==1 ? $TOTALBARANG : '')?></td>
							<td style="text-align:center">CHECKER</td>
							<td style="text-align:center">HORMAT KAMI</td>
						</tr>
					</table>
					<div style="bottom:5mm; text-align:right; font-size:2mm">HAL 1/<?=($print_type==1 || $TOTALROW < $MAXROW ? '1' : $TOTALPAGE);?></div>
				</div>
			</div>
		</div>

		<?if ($TOTALBARANG > 9) {?>
			<div>
				<div style="width:650px; height:139mm; border:0.1mm solid #ccc; padding:1mm; position:relative">
					<div style="position:absolute; top:50mm;">
						<table style="font-size:3mm;"  width="600px" >
							<tr>
								<td style="text-align:center">TANDA TERIMA</td>
								<td style="text-align:center">CHECKER</td>
								<td style="text-align:center">HORMAT KAMI</td>
							</tr>
						</table>
						<div style="bottom:5mm; text-align:right; font-size:2mm">HAL 2/<?=($TOTALPAGE);?></div>
					</div>
				</div>
			</div>	
		<?}?>

	
		<?if ($print_type==3 || $print_type==4) { $row_sj = 1;?>
			<div>
				<div style="width:650px; height:139mm; border:0.1mm solid #ccc; padding:1mm; position:relative">
					<div style="height:120mm; overflow:hidden;">
						<?=$sj_header;?>	
						<?=$sj_body;?>
					</div>
					<div style="position:absolute; bottom:10mm; 600px; ">
						<table style="font-size:3mm; "  width="600px" >
							<tr>
								<td style="text-align:center; width:30mm; text-align:right">TANDA TERIMA</td>
								<td style="text-align:center; width:20mm; text-align:center">TANGGAL</td>
								<td style="text-align:center; width:45mm; text-align:right">CHECKER</td>
								<td style="text-align:center; width:20mm; text-align:center">EXPEDISI</td>
								<td style="text-align:center">HORMAT KAMI</td>
							</tr>
						</table>
						<div style="bottom:5mm; text-align:right; font-size:2mm">HAL <?=($TOTALBARANG > 9 ? 3 : 2)?>/<?=$TOTALPAGE;?></div>
					</div>
				</div>
			</div>
		<?}?>
		
	
		<?if ($TOTALROW >= 8  && $print_type != 1) {
			for ($pp=0; $pp < $PAGEDETAIL ; $pp++) { 
				if (isset($packing_list_body[$pp])) {
					$pgidx = $pp+($print_type == 2 ? ($TOTALBARANG > 9 ? 3 : 2) : 3 );?>
					<div>
						<div style="width:650px; height:139mm; border:0.1mm solid #ccc; padding:1mm; position:relative">
							<div style="height:120mm;  border:0.1mm solid #ccc; ">
								<?=($is_packing_list && $pp == 0 ? $packing_list_header : "");?>
								<?=$packing_list_start?>
								<?=($pp==0 ? $packing_list_title : "");?>
								<?=$packing_list_body[$pp]."</table>";?>
							</div>
							<div style="position:absolute; bottom:10mm; width:19.5cm; ">		
								<div style="bottom:5mm; text-align:right; font-size:2mm">HAL <?=$pgidx;?>/<?=$TOTALPAGE;?></div>
							</div>
						</div>
					</div>
				<?}?>
			<?}?>
		<?}?>
	</div>

	<div id="testTbl" <?=(is_posisi_id()!=1 ? 'hidden' : "");?>>
			<div id="page1">
				<div style="width:199mm; height:139mm; padding:1mm; position:relative">
					<div style="height:120mm">
						<?=$table_header_jual;?>
						<?=$table_barang;?>
						<?if ($TOTALROW < $MAXROW && $print_type == 2) {
								echo $packing_list_start;
								echo $packing_list_title;
								echo $packing_list_body[0]."</table>";
						}?>
		
					</div>
		
					<div style="position:absolute; bottom:10mm; width:19.5cm; ">
						<table style="font-size:3mm;"  width="100%" >
							<tr>
								<td style="text-align:center">TANDA TERIMA</td>
								<td style="text-align:center">CHECKER</td>
								<td style="text-align:center">HORMAT KAMI</td>
							</tr>
						</table>
						<div style="bottom:5mm; text-align:right; font-size:2mm">HAL 1/<?=$TOTALPAGE;?></div>
					</div>
				</div>
			</div>

			<?if ($TOTALBARANG > 9) {?>
				<div id="page2">
					<div style="width:650px; height:139mm; border:0.1mm solid #ccc; padding:1mm; position:relative">
						<div style="position:absolute; top:50mm; width:19.5cm; ">
							<table style="font-size:3mm;"  width="100%" >
								<tr>
									<td style="text-align:center">TANDA TERIMA</td>
									<td style="text-align:center">CHECKER</td>
									<td style="text-align:center">HORMAT KAMI</td>
								</tr>
							</table>
							<div style="bottom:5mm; text-align:right; font-size:2mm">HAL 2/<?=$TOTALPAGE;?></div>
						</div>
					</div>
				</div>	
			<?}?>
		
			<?if ($print_type==3 || $print_type==4) { $row_sj = 1;?>
				<div id="page<?=($TOTALBARANG > 9 ? 3 : 2)?>">
					<div style="width:199mm; height:139mm; position:relative">
						<div style="height:120mm; overflow:hidden;">
							<?=$sj_header;?>	
							<?=$sj_body;?>
						</div>
						<div style="position:absolute; bottom:10mm; width:19.5cm; ">
							<table style="font-size:3mm; "  width="100%" >
								<tr>
									<td style="text-align:center; width:30mm; text-align:right">TANDA TERIMA</td>
									<td style="text-align:center; width:20mm; text-align:center">TANGGAL</td>
									<td style="text-align:center; width:45mm; text-align:right">CHECKER</td>
									<td style="text-align:center; width:20mm; text-align:center">EXPEDISI</td>
									<td style="text-align:center">HORMAT KAMI</td>
								</tr>
							</table>
							<div style="bottom:5mm; text-align:right; font-size:2mm">HAL <?=($TOTALBARANG > 9 ? 3 : 2)?>/<?=$TOTALPAGE;?></div>
						</div>
					</div>
				</div>
			<?}?>
			
		
			<?if ($TOTALROW >= 8 && $print_type != 1) {
				
		
				// print_r($packing_list_body); 
		
				for ($pp=0; $pp < $PAGEDETAIL ; $pp++) { 
					if (isset($packing_list_body[$pp])) {
						$pgidx = $pp+($print_type == 2 ? ($TOTALBARANG > 9 ? 3 : 2) : 3 );?>
						<div id="page<?=$pgidx;?>">
							<div style="width:199mm; height:139mm; padding:1mm; position:relative">
								<div style="height:120mm;  ">
									<?=($is_packing_list && $pp == 0 ? $packing_list_header : "");?>
									<?=$packing_list_start?>
									<?=($pp==0 ? $packing_list_title : "");?>
									<?=$packing_list_body[$pp]."</table>";?>
								</div>
								<div style="position:absolute; bottom:10mm; width:19.5cm; ">		
									<div style="bottom:5mm; text-align:right; font-size:2mm">HAL <?=$pgidx;?>/<?=$TOTALPAGE;?></div>
								</div>
							</div>
						</div>
					<?}?>
				<?}?>
			<?}?>
		</div>
	


<iframe id="webPrintFrame" name="webPrintFrame"  src="http://127.0.0.1:8080/printwindow" hidden></iframe>


</body>
</html>
<?//if (is_posisi_id()==1) {?>


<script src="<?php echo base_url('assets_noondev/js/webprint.js'); ?>" type="text/javascript"></script>

<script>
var esc_init = "\x1B" + "\x40"; // initialize printer
var esc_p = "\x1B" + "\x70" + "\x30"; // open drawer
var gs_cut = "\x1D" + "\x56" + "\x4E"; // cut paper
var esc_a_l = "\x1B" + "\x61" + "\x30"; // align left
var esc_a_c = "\x1B" + "\x61" + "\x31"; // align center
var esc_a_r = "\x1B" + "\x61" + "\x32"; // align right
var esc_double = "\x1B" + "\x21" + "\x31"; // heading
var font_reset = "\x1B" + "\x21" + "\x02"; // styles off
var esc_ul_on = "\x1B" + "\x2D" + "\x31"; // underline on
var esc_bold_on = "\x1B" + "\x45" + "\x31"; // emphasis on
var esc_bold_off = "\x1B" + "\x45" + "\x30"; // emphasis off


var printerSelected = "";

jQuery(document).ready(function() {
	

	//===========================print=================================

	var populatePrinters = function(printers){
		var printerlist = $("#printerlist");
		const cookies = document.cookie;
		if (cookies.length > 0) {
			const cookieList = cookies.split(';');
			for (let item of cookieList) {
				const n = item.toString().trim().split('=');
				// console.log(n);
				if (n[0] == 'printerdefault') {
					printerSelected = n[1];
				}
			}
		}
		printerlist.html('');
		for (var i in printers){
			if (!printers[i].includes("OneNote") && !printers[i].includes("XPS") && !printers[i].includes("PDF") && !printers[i].includes("Fax") ) {
				const selected = (printerSelected == printers[i] ? 'selected' : "");
				printerlist.append(`<option ${selected} value="${printers[i]}">${printers[i]}</option>`);
			}
		}
	};

	webprint = new WebPrint(true, {
		relayHost: "127.0.0.1",
		relayPort: "8080",
		listPrinterCallback: populatePrinters,
		readyCallback: function(){
			webprint.requestPrinters();
		}
	});
});


function print_faktur(printer_name){

	// if (idx == 1) {
	// 	qz.websocket.connect().then(function() {
	// 	});
	// };

	// var config = qz.configs.create("Nota");             // Exact printer name from OS
	// var data = ['\x1B' + '\x40'+          // init
		
	// ];
	// console.log(data);
	// webprint.printRaw(data, printer_name);

	


	// qz.print(config, data).then(function() {
	//    // alert("Sent data to printer");
	// });
}

<?//=(is_posisi_id()==1 ? $TOTALPAGE : "")?>
function printFakturHTML(){
	const printerUsed = document.querySelector("#printerlist").value;
	const pageSel = document.querySelector("#pageList").value;
	
	if (printerSelected != printerUsed) {
		document.cookie = `printerdefault=${printerUsed}; max-age=3600; SameSite=None; Secure`;
	}

	const printer_name = $('#printerlist').val();
	let data = [];
	let dataPrint = '';
	const main = document.querySelector('#testTbl');
	const TOTALPAGE = "<?=$TOTALPAGE?>";
	if (pageSel == '') {
		for (x=0; x < TOTALPAGE ; x++) { 
			const a = main.querySelector(`#page${x+1}`);
			if (a) {
				dataPrint += `<html>
					<body style="width:195mm; height:139mm; <?=$fontFamily;?>">${a.innerHTML}</body>
					</html>`;
				data.push(`<html>
					<body style="width:195mm; height:139mm;">${a.innerHTML}</body>
					</html>`);
			}
		}
	}else{
		const a = main.querySelector(`#page${pageSel}`);
		if (a) {
			dataPrint += `<html>
						<body style="width:195mm; height:139mm; <?=$fontFamily;?>">${a.innerHTML}</body>
						</html>`;
		}

	}

	// var dataFinalPrint = `<html>
	// 			<body>${dataPrint}</body>
	// 			</html>`;


	// const a = main.querySelector(`#page2`);
	// if (a) {
	// 	data.push(`<html>
	// 		<body style="width:195mm; height:139mm;">${a.innerHTML}</body>
	// 		</html>`);
	// }

	<?if (is_posisi_id() == 1) {?>
		
	<?}?>
		webprint.printHtml(dataPrint, printer_name);

	<?if (is_posisi_id() == 1) {?>
		// for (let x = 0; x < data.length; x++) {
			// webprint.printHtml(data[1], printer_name);
		// }
	<?}?>

	parent.closeParentPrintModal();

}

</script>
<?//}?>





