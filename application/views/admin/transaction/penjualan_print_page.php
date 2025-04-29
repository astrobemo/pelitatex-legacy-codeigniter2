<link href="<?=base_url('assets/global/plugins/bootstrap-modal/css/bootstrap-modal.css');?>" rel="stylesheet" type="text/css"/>

<!-- <link href="<?=base_url('assets_noondev/css/bootstrap-modal-bs3patch.css');?>" rel="stylesheet" type="text/css"/> -->
<?php echo link_tag('assets/global/plugins/bootstrap-datepicker/css/datepicker3.css'); ?>

<style type="text/css">
#tbl-data input[type="text"], #tbl-data select{
	height: 25px;
	width: 50%;
	padding: 0 5px;
}

#qty-table input, #qty-table-edit input{
	width: 80px;
	padding: 5px;
}

#stok-info, #stok-info-edit{
	font-size: 1.5em;
	position: absolute;
	right: 50px;
	top: 30px;
}

.yard-info{
	font-size: 1.5em;
}

.no-faktur-lengkap{
	font-size: 2.5em;
	font-weight: bold;
}

.input-no-border{
	border: none;
}

.subtotal-data{
	font-size: 1.2em;
}

.textarea{
	resize:none;
}

#bayar-data tr td{
	font-size: 1.5em;
	font-weight: bold;
	padding: 0 10px 0 10px;
}

#bayar-data tr td input{
	padding: 0 5px 0 5px;
	border: 1px solid #ddd;
}

.alamat{
	overflow: hidden;
	text-overflow:ellipsis;
	width: 150px;
}

#jatuh-tempo-list, #jatuh-tempo-list-edit{
	display: none;
	max-height: 150px;
	overflow: auto;
	border: 1px solid #eee;
	padding: 3px;
	/*position: absolute;
	top: 0px;
	right: 0px;*/
}

#jatuh-tempo-list tr td, #jatuh-tempo-list-edit tr td{
	padding: 2px 5px;
}

#jatuh-tempo-rekap{
	display: none;
}

</style>
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
			$kembali = 0;

			$hidden_spv = '';
			if (is_posisi_id() != 1) {
				$hidden_spv = 'hidden';
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
				$get_jt = ($row->jatuh_tempo == '' || $status_cek == 1  ? date('Y-m-d',$dt) : $row->jatuh_tempo);
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

				if ($status_aktif == -1 ) {
					$note_info = 'note note-danger';
				}

			}

			$nama_bank = '';
			$no_rek_bank = '';
			$tanggal_giro = '';
			$jatuh_tempo_giro = '';
			$no_akun = '';

			
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
				$disabled = 'disabled';
				$readonly = 'readonly';
			}

			foreach ($printer_list as $row) { 
    			$default_printer = (get_default_printer() == $row->id ? $row->nama : '');
    		}
		?>



<script src="<?php echo base_url('assets/global/plugins/jquery.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/jquery-migrate.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/jquery-ui/jquery-ui.min.js'); ?>" type="text/javascript"></script>


<script src="<?php echo base_url('assets/global/plugins/bootstrap-modal/js/bootstrap-modalmanager.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/bootstrap-modal/js/bootstrap-modal.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/jquery-validation/js/jquery.validate.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/bootbox/bootbox.min.js'); ?>" type="text/javascript"></script>

<script src="<?php echo base_url('assets_noondev/js/qz-print/dependencies/rsvp-3.1.0.min.js');?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets_noondev/js/qz-print/dependencies/sha-256.min.js');?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets_noondev/js/qz-print/qz-tray.js');?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets_noondev/js/webprint.js'); ?>" type="text/javascript"></script>


<?
	$printer_marker = "";
	$headers = apache_request_headers();
	foreach ($headers as $header => $value) {
		if ($header == "Host") {
		    if ($value == 'sistem.blessingtdj.com') {
		    	$printer_marker = 3;
		    }
			# code...
		}
	}
?>

<script>
jQuery(document).ready(function() {

	//===========================general=================================
	const action = "<?=$action?>";

	webprint = new WebPrint(true, {
        relayHost: "127.0.0.1",
        relayPort: "8080",
        listPrinterCallback: populatePrinters,
        readyCallback: function(){
            webprint.requestPrinters();
        }
    });

    $('.btn-faktur-print').click(function(){
		$('[name=print_target]').val('1');
	});

	$('.btn-print-detail').click(function(){
		$('[name=print_target]').val('2');
		// print_detail();
	});

	$('.btn-print-kombi').click(function(){
		$('[name=print_target]').val('3');
		// print_detail();
	});

	$('.btn-surat-jalan').click(function(){
		$('[name=print_target]').val('4');
		// print_detail();
	});

	$('.btn-surat-jalan-noharga').click(function(){
		$('[name=print_target]').val('5');
		// print_detail();
	});

	$('.print-testing').click(function(){
		$('[name=print_target]').val('99');
		// print_detail();
	});

    $('.btn-print').dblclick(function(){
		notific8("lime", "print...");
    	$('.btn-print-action').click();
    	// console.log(webprint.requestPrinters());
    });

	$('.btn-print').click(function(){
		setTimeout(function(){
    		$('#portlet-config-print').modal('toggle');
		},200);
    	// console.log(webprint.requestPrinters());
    });


    $('.btn-print-action').click(function(){
		printer_marker = "<?=$printer_marker?>";
		var selected = $('#printer-name').val();
		var printer_name = $("#printer-name [value='"+selected+"']").text();
		printer_name = $.trim(printer_name);
		var action = $('[name=print_target]').val();
		if (action == 1 ) {
			// print_faktur(printer_name);
			// print_font_test(printer_name);
			printer_marker == 3 ? print_faktur_3(printer_name) : print_faktur(printer_name);
			console.log(action);

		}else if(action == 2){
			// print_detail(printer_name);
			printer_marker == 3 ? print_detail_3(printer_name) : print_detail(printer_name);
			console.log(action);
		}
		else if(action == 3){
			// print_kombinasi(printer_name);
			printer_marker == 3 ? print_kombinasi_3(printer_name) : print_kombinasi(printer_name);
			console.log(action);
		}else if(action == 4){
			// print_surat_jalan(printer_name);
			printer_marker == 3 ? print_surat_jalan_3(printer_name) : print_surat_jalan(printer_name);
			console.log(action);
		}else if(action == 5){
			// print_surat_jalan_noharga(printer_name);
			printer_marker == 3 ? print_surat_jalan_noharga_3(printer_name) : print_surat_jalan_noharga(printer_name);
			console.log(action);
		}else{
			// print_surat_jalan_noharga(printer_name);
			print_testing(printer_name);
			console.log(action);
		}
		// alert(printer_name);
	});

	<?if ($limit_sisa_atas < 0) {?>
		$(".warning-limit").show();
	<?}?>

		<?if($penjualan_id != '' && $status==0){?>

		var printer_default = "<?=$default_printer?>";
		<?}?>
});
</script>

<?
$nama_toko = '';
$alamat_toko = '';
$telepon = '';
$fax = '';
$npwp = '';



if ($penjualan_id != '') {

	foreach ($data_toko as $row) {
		$nama_toko = trim($row->nama);
		$alamat_toko = trim($row->alamat.' '.$row->kota);
		$telepon = trim($row->telepon);
		$fax = trim($row->fax);
		$npwp = trim($row->NPWP);

	}

	$garis1 = "'-";
	$garis2 = "=";

	// include_once 'print_faktur.php';
	// include_once 'print_detail.php';
	// include_once 'print_faktur_detail.php';

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

	if ($printer_marker == 3) {
		include_once 'print/print_faktur_3.php';
		include_once 'print/print_detail_3.php';
		include_once 'print/print_faktur_detail_3.php';
		include_once 'print/print_surat_jalan_3.php';
		include_once 'print/print_surat_jalan_noharga_3.php';
	}else{
		// include_once 'print_faktur_testing.php';
		include_once 'print_faktur.php';
		include_once 'print_detail.php';
		include_once 'print_faktur_detail.php';

		include_once 'print_surat_jalan.php';
		include_once 'print_surat_jalan_noharga.php';
	}

	if (is_posisi_id() == 1) {
		// include_once 'print_font_test.php';
		// include_once 'print_testing.php';
	}
}?>

var populatePrinters = function(printers){
    var printerlist = $("#printer-name");
    var test = [];
    // printerlist.html('');
    for (var i in printers){
    	test.push('<option value="'+printers[i]+'">'+printers[i]+'</option>');
        // printerlist.append('<option value="'+printers[i]+'">'+printers[i]+'</option>');
    }
    // return test.join('||');
};



</script>
