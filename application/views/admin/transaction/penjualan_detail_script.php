
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

var qtyListDetail = [];
var qtyListAmbil = [];
var bonBaru = true;

var ppn_tgl = [];
var ppn_val = [];
var ppn_berlaku = parseFloat(<?=$ppn_set;?>);
var ppn_edit_berlaku = parseFloat(<?=$ppn;?>);
var customer_harga_list = <?=json_encode($customer_harga);?>;
const tesCustomerId = "<?=$customer_id?>";
var idx_detail = 1;
var gudang_id_last = "";
var barang_id_last = "";
var harga_jual_last = "";


customer_harga_list.forEach((el, index) => {
	if (el.customer_id == tesCustomerId) {
		console.log(el);
	}
});
const barang_po = <?=json_encode($po_penjualan_barang);?>;

<?foreach ($ppn_list as $row) {?>
    ppn_tgl.push("<?=$row->tanggal;?>");
    ppn_val.push("<?=(float)$row->ppn;?>");
<?}?>

jQuery(document).ready(function() {
	

	//===========================general=================================


	<?if ($limit_sisa_atas < 0) {?>
		$(".warning-limit").show();
	<?}?>

		<?if($penjualan_id != '' && $status==0){?>

			var printer_default = "<?=$default_printer?>";

			// webprint = new WebPrint(true, {
		    //     relayHost: "127.0.0.1",
		    //     relayPort: "8080",
	        //     listPrinterCallback: populatePrinters,
		    //     readyCallback: function(){
	        //         webprint.requestPrinters();
	        //     }
		    // });

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

		<?}?>

		<?if ($penjualan_type_id != 3 && $penjualan_id != '') {
			if ($nik == '' && $npwp == '') {?>
				notific8('ruby',"Data customer kurang NIK / NPWP, mohon lengkapi !");
			<?}
		}?>

		FormNewPenjualanDetail.init();

		var form_group = {};
		var idx_gen = 0;
		var print_idx = 1;
	   	var penjualan_type_id = '<?=$penjualan_type_id;?>';


		$('[data-toggle="popover"]').popover();


	    $('#warna_id_select, #barang_id_select,#warna_id_select_edit, #barang_id_select_edit, #barangPOSelect').select2({
	        placeholder: "Pilih...",
	        allowClear: true
	    });

	    $('#customer_id_select, #customer_id_select_edit').select2({
	        allowClear: true
	    });

	//========================================================================================


	    <?if ($penjualan_id != '') { ?>
			$('.btn-print').click(function(){
		    });

		    $('#dp_list_table').on('click','.dp-more-info', function(){
		    	$(this).closest('td').find('ul').toggle();
		    });

		    $(".manage-dp").click(function(){
				var bayar = 0;
		    	$('#bayar-data tr td input').each(function(){
					if ($(this).attr('class') != 'keterangan_bayar' && $(this).attr('class') != 'dp-val') {
						bayar += reset_number_format($(this).val());			
					};
				});

				var g_total = reset_number_format($('.g_total').html());
				g_total = g_total - bayar;
				$('#dp_list_table .dp-nilai-bon-info').html(change_number_format(g_total));
				
		    	dp_update_bayar();
		    });
		<?}?>

	    $("#search_no_faktur").select2({
	        placeholder: "Select...",
	        allowClear: true,
	        minimumInputLength: 1,
	        query: function (query) {
	            var data = {
	                results: []
	            }, i, j, s;
	            var data_st = {};
				var url = "transaction/get_search_no_faktur_jual";
				data_st['no_faktur'] = query.term;
				
				ajax_data_sync(url,data_st).done(function(data_respond  /*,textStatus, jqXHR*/ ){
					// console.log(data_respond);
					$.each(JSON.parse(data_respond),function(k,v){
						data.results.push({
		                    id: v.id,
		                    text: v.no_faktur
		                });
					});
		            query.callback(data);
		   		});
	        }
	    });

	    $('.btn-search-faktur').click(function(){
	    	var id = $("#search_no_faktur").val();
	    	if (id != '' && typeof id !== 'undefined') {
	    		$('#form_search_faktur').submit();
	    	}else{
	    		alert(id);
	    	}
	    });

	    $('.btn-pin').click(function(){
	    	setTimeout(function(){
		    	$('#pin_user').focus();    		
	    	},700);
	    });

	    $('.btn-request-open').click(function(){
	    	if(cek_pin($('#form-request-open'), '.pin_user')){
				$('#form-request-open').submit();
	    	}
	    });

	    $('.pin_user').keypress(function (e) {
	    	var form = '#'+$(this).closest('form').attr('id');
	    	var obj_form = $(form);
	        if (e.which == 13) {
	        	if(cek_pin(obj_form, '.pin_user')){
					obj_form.submit();
	        	}
	        }
	    });

	    $('#pin-limit').change(function () {
			var data = {};
			data['pin'] = $(this).val();
			var url = 'transaction/cek_pin';
			ajax_data_sync(url,data).done(function(data_respond  /*,textStatus, jqXHR*/ ){
				if (data_respond == "OK") {
					$("#btn-lock-transaction").show();
				}
			});
	    });

	//====================================penjualan type=============================


		$('#form_edit_data [name=penjualan_type_id]').change(function(){
			if ($(this).val() == 1) {
				$('#form_edit_data .po_section').show();
				$('#form_edit_data .customer_section').show();
				$('#form_edit_data [name=alamat]').val('');
				$('#form_edit_data [name=alamat]').prop('readonly',true);
	   			penjualan_type_id = 1;
	   			// $('#customer_id_select_edit').select2("open");
	   			$('#edit-nama-keterangan').hide();
	   			$('.edit-alamat-keterangan').hide();
	   			$('#edit-select-customer').show();
	   			$('#fp_status_edit').prop('checked',true);
	   			$(".btn-edit-save").prop('disabled', false);
				$(".pin-special-edit").hide();
	   			$('#fp-edit-true').show();

			};

			if ($(this).val() == 2) {
	   			penjualan_type_id = 2;
				$('#form_edit_data .po_section').show();
				$('#form_edit_data .customer_section').show();
				$('#form_edit_data [name=alamat]').val('');
				$('#form_edit_data [name=alamat]').prop('readonly',true);
	   			// $('#customer_id_select_edit').select2("open");
	   			$('#edit-nama-keterangan').hide();
	   			$('.edit-alamat-keterangan').hide();
	   			$('#edit-select-customer').show();
	   			$('#fp_status_edit').prop('checked',true);
	   			$('#fp-edit-true').show();

				const customer_id = $('#customer_id_select_edit').val();
				if (!cek_customer_kredit_permit(customer_id)) {
					bootbox.alert("Fitur kredit tidak tersedia untuk customer ini");
				}
			};

			if ($(this).val() == 3) {
				$('#form_edit_data .po_section').hide();
				penjualan_type_id = 3;
	   			$('#customer_id_select_edit').val('');
				$('#form_edit_data [name=alamat]').val('');
				$('#form_edit_data [name=alamat]').prop('readonly',false);
	   			$('#edit-nama-keterangan').show();
	   			$('.edit-alamat-keterangan').show();
	   			$('#edit-select-customer').hide();
	   			$('#fp_status_edit').prop('checked',false);
	   			$(".btn-edit-save").prop('disabled', false);
				$(".pin-special-edit").hide();
	   			$('#fp-edit-true').hide();
			};

			$.uniform.update($('#fp_status_edit'));
			$('#customer_id_select_edit').change();
		});

	    $('.pin_special_edit').keyup(function (e) {
	    	if($(this).val().length > 5){
				$("#pinCheckingStatusEdit").text('checking...');
				if(cek_pin($("#form_edit_data"),'.pin_special_edit')){
					$('.pin_special_edit').css('color','#000');
					$("#pinCheckingStatusEdit").html(`<i style='color:green' class='fa fa-check'></i>`);
					$(".btn-edit-save").prop('disabled',false);
                    $("#ppn_list_edit").prop('disabled',false);
				}else if(penjualan_type_id == 2){
					$(".btn-edit-save").prop('disabled',true);
				}else{
					$("#pinCheckingStatusEdit").html(`<i style='color:red' class='fa fa-times'></i>`);
					$('.pin_special_edit').css('color','red');
				}
				$(".btn-edit-save").text('Save');
			}else{
				$("#pinCheckingStatusEdit").html(`<i style='color:green' class='fa fa-check'></i>`);
				$(".btn-edit-save").prop('disabled',true);
				$('.pin_special_edit').css('color','red');
			}
	    });

	    // $('.pin_special_edit').change(function () {
	    // 	if(cek_pin($("#form_edit_data"),'.pin_special_edit')){
	    // 		// alert('ok');
		// 		$(".btn-edit-save").prop('disabled',false);
        // 	}else if(penjualan_type_id == 2){
		// 		$(".btn-edit-save").prop('disabled',true);
        // 	}
	    // });

	    $('#customer_id_select_edit,#form_edit_data [name=penjualan_type_id]').change(function(){
			var penjualan_type_id = $("#form_edit_data [name=penjualan_type_id]").val();
			var customer_id = $('#customer_id_select_edit').val();
			$('#inputPOEdit').val("");
			if (penjualan_type_id == 1 || penjualan_type_id == 2) {
				if ($(this).val() == '') {
					var customer_id = $(this).val('');
					notific8('ruby', 'Customer harus dipilih');
		   			$('#customer_id_select_edit').select2("open");
					$(".pin-special-edit").hide();
					
				}else{

					getPOList(customer_id,"#po_penjualan_edit_select", "#inputPOEdit");

					var customer_id = $("#customer_id_select_edit").val();
					let status = 0;

					if (penjualan_type_id == 2) {
						var sisa = $("#customer_id_edit [value='"+customer_id+"']").text();
						console.log(sisa);
						if(sisa != '' || parseFloat(sisa) <= 0){
							$(".btn-edit-save").prop('disabled', true);
							$(".pin-special-edit").show();
							$(".pin_special_edit").focus();
							$('.limit-warning-edit').show();
							status = 1;
							// alert(customer_id);

						}else{
							$(".btn-edit-save").prop('disabled', false);
							$(".pin-special-edit").hide();
							$('.limit-warning').hide();
							$('.limit-warning-edit').hide();
						}

						var data_st = {};
			   			data_st['customer_id'] =  customer_id;
				    	var url = "admin/cek_customer_lewat_tempo_kredit";
			   			ajax_data_sync(url,data_st).done(function(data_respond  /*,textStatus, jqXHR*/ ){
							   console.log(JSON.parse(data_respond).length)
			   				if(JSON.parse(data_respond).length > 0){
			   					$("#jatuh-tempo-warning-edit").show();
			   					let list = "<table>";
			   					let total_jt = 0;
			   					let count_jt = 0;
								$.each(JSON.parse(data_respond),function(i,v){
									count_jt++;
									total_jt += parseFloat(v.amount);
									list += `<tr><td><b>${v.no_faktur_lengkap} </b></td>
										<td>${v.jatuh_tempo}</td>
										<td>${change_number_format(parseFloat(v.amount))}</td></tr>`;
								});
								list += "</table>";
								$("#jatuh-tempo-list-edit-jual tbody").html(list);
								$("#jatuh-tempo-rekap-edit").html(`<b>${count_jt} trx</b> : 
									${change_number_format(parseFloat(total_jt))}. <a class='jt-detail-edit' style="font-size:0.9em">Detail >></a>
									`).show();

								if(status == 0){
									$(".btn-edit-save").prop('disabled', true);
									$(".pin-special-edit").show();
									$(".pin_special_edit").focus();
								}
			   				}else{
								console.log('jt', status)

			   					$("#jatuh-tempo-warning-edit").hide();
			   					$("#jatuh-tempo-list-edit-jual").hide();
			   					$("#jatuh-tempo-rekap-edit").hide();
			   					if (status == 0) {
			   						$(".btn-edit-save").prop('disabled', false);
									$(".pin-special-edit").hide();
									$('.limit-warning').hide();
			   					};


			   				}
				   		});
					}else{
						$(".pin-special-edit").hide();
						$(".btn-edit-save").prop('disabled', false);
						$('.limit-warning-edit').hide();
						$("#jatuh-tempo-warning-edit").hide();
	   					$("#jatuh-tempo-list-edit-jual").hide();
	   					$("#jatuh-tempo-rekap-edit").hide();
					}
				}
			};
		});

		$('#portlet-config-edit').on("click", ".jt-detail-edit", function(){
			$("#jatuh-tempo-list-edit-jual").toggle('slow');
		});

		//==============================================================

		$('#form_add_data [name=penjualan_type_id]').change(function(){
			if ($(this).val() == 1) {
				$('#form_add_data .po_section').show();
				$('#form_add_data .customer_section').show();
	   			// $('#customer_id_select').select2("open");
	   			$('#add-nama-keterangan').hide();
	   			$('.add-alamat-keterangan').hide();
	   			$('#add-select-customer').show();
	   			$('#fp_status_add').prop('checked',true);
	   			$(".btn-save").prop('disabled', false);
	   			$('#fp-add-true').show();

				// $(".pin-special").hide();
			};

			if ($(this).val() == 2) {
				$('#form_add_data .po_section').show();
				$('#form_add_data .customer_section').show();
	   			// $('#customer_id_select').select2("open");
	   			$('#add-nama-keterangan').hide();
	   			$('.add-alamat-keterangan').hide();
	   			$('#add-select-customer').show();
	   			$('#fp_status_add').prop('checked',true);
	   			// alert($('#fp_status_add').is(':checked'));
	   			$('#fp-add-true').show();

			};

			if ($(this).val() == 3) {
				$('#form_add_data .po_section').hide();
				// $('#form_add_data .customer_section').hide();
	   			$('#customer_id_select').val('');
	   			$('#add-nama-keterangan').show();
	   			$('.add-alamat-keterangan').show();
	   			$('#add-select-customer').hide();
	   			$('#fp_status_add').prop('checked',false);
	   			$(".btn-save").prop('disabled', false);
	   			$('#fp-add-true').hide();
				// $(".pin-special").hide();
			};

			$('#customer_id_select').change();
			$.uniform.update($('#fp_status_add'));

		});

		//========================================================================================

		$('#customer_id_select, #form_add_data [name=penjualan_type_id]').change(function(){
			var penjualan_type_id = $("#form_add_data [name=penjualan_type_id]").val();
			var customer_id = $('#customer_id_select').val();
			let status = 0;
			$('#po_penjualan_select').html("");
			// alert(penjualan_type_id);
			if (penjualan_type_id == 1 || penjualan_type_id == 2) {
				document.querySelector("#po_penjualan_select").style.display = "block";
				document.querySelector("#inputPOAdd").style.display = "none";
				if (customer_id == '') {
					notific8('ruby', 'Customer harus dipilih');
		   			$('#customer_id_select').select2("open");
					$(".pin-special").hide();
					$(".btn-save").prop('disabled', false);
					
				}else{
					getPOList(customer_id,"#po_penjualan_select", "#inputPOAdd");

					if (penjualan_type_id == 2) {
						var sisa = $("#customer_id_add [value='"+customer_id+"']").text();
						if (!cek_customer_kredit_permit(customer_id)) {
							$(".btn-save").prop('disabled', true);
							bootbox.alert("Fitur kredit tidak tersedia untuk customer ini");
						}else{
							if(sisa != '-' && parseFloat(sisa) <= 0){
								$(".btn-save").prop('disabled', true);
								$(".pin-special").show();
								$(".pin_special").focus();
								$('.limit-warning').show();
								status = 1;
							}else{
								$(".btn-save").prop('disabled', false);
								$(".pin-special").hide();
								$('.limit-warning').hide();
							}
	
							var data_st = {};
							   data_st['customer_id'] =  customer_id;
							var url = "admin/cek_customer_lewat_tempo_kredit";
							   
							   ajax_data_sync(url,data_st).done(function(data_respond  /*,textStatus, jqXHR*/ ){
								   if(data_respond.length > 0){
									   $("#jatuh-tempo-warning").show();
									   let list = "<table>";
									   let total_jt = 0;
									   let count_jt = 0;
									$.each(JSON.parse(data_respond),function(i,v){
										count_jt++;
										total_jt += parseFloat(v.amount);
										list += `<tr><td><b>${v.no_faktur_lengkap} </b></td>
											<td>${v.jatuh_tempo}</td>
											<td>${change_number_format(parseFloat(v.amount))}</td></tr>`;
									});
									list += "</table>";
	
									$("#jatuh-tempo-list-jual tbody").html(list);
									$("#jatuh-tempo-rekap").html(`<b>${count_jt} trx</b> : 
										${change_number_format(parseFloat(total_jt))}. <a class='jt-detail' style="font-size:0.9em">Detail >></a>
										`).show();


									if(status == 0 && count_jt > 0){
										$(".btn-save").prop('disabled', true);
										$(".pin-special").show();
										$(".pin_special").focus();
									}else{
										$("#jatuh-tempo-warning").hide();
										   $("#jatuh-tempo-list-jual").hide();
										   $("#jatuh-tempo-rekap").hide();
									}
								   }else{
									   $("#jatuh-tempo-warning").hide();
									   $("#jatuh-tempo-list-jual").hide();
									   $("#jatuh-tempo-rekap").hide();
	
									   if (status == 0) {
										   $(".btn-save").prop('disabled', false);
										$(".pin-special").hide();
									   };
								   }
							   });
						}
					}else{
						$(".pin-special").hide();
						$(".btn-save").prop('disabled', false);
						$('.limit-warning').hide();
						$("#jatuh-tempo-warning").hide();
						$("#jatuh-tempo-list-jual").hide();
						$("#jatuh-tempo-rekap").hide();
					}
				}
			}else{
				document.querySelector("#po_penjualan_select").style.display = "none";
				document.querySelector("#inputPOAdd").style.display = "block";
			};
		});
		
		$('#portlet-config').on("click", ".jt-detail", function(){
			console.log($("#jatuh-tempo-list-jual").css('display'));
			$("#jatuh-tempo-list-jual").css('display','block');
			console.log($("#jatuh-tempo-list-jual").css('display'));
		});
		//========================================================================================

		$('.pin_special').keyup(function (e) {
			// console.log($('.pin_special').val(), $('.pin_special').val().length);
			if($(this).val().length > 5){
				$("#pinCheckingStatus").text('checking...');
				if(cek_pin($("#form_add_data"),'.pin_special')){
					$('.pin_special').css('color','#000');
					$("#pinCheckingStatus").html(`<i style='color:green' class='fa fa-check'></i>`);
					$(".btn-save").prop('disabled',false);
					$("#ppn_list_add").prop('disabled',false);
				}else if(penjualan_type_id == 2){
					$(".btn-save").prop('disabled',true);
				}else{
					$("#pinCheckingStatus").html(`<i style='color:red' class='fa fa-times'></i>`);
					$('.pin_special').css('color','red');
				}
				$(".btn-save").text('Save');
			}else{
				$("#pinCheckingStatus").html(`<i style='color:red' class='fa fa-times'></i>`);
				$(".btn-save").prop('disabled',true);
				$('.pin_special').css('color','red');
			}
	    });

	    // $('.pin_special').change(function () {
	    // 	if(cek_pin($("#form_add_data"),'.pin_special')){
		// 		$(".btn-save").prop('disabled',false);
        // 	}else if(penjualan_type_id == 2){
		// 		$(".btn-save").prop('disabled',true);
        // 	}
	    // });

	// ======================last input ================================================

			var barang_id = "<?=$barang_id;?>";
			barang_id_last = "<?=$barang_id;?>";
			gudang_id_last = "<?=($gudang_id_last == 0 ? $gudang_default : $gudang_id_last);?>";
			idx_detail = "<?=$idx;?>";
			harga_jual_last = "<?=number_format($harga_jual,'0',',','.');?>";
			var harga_jual = "<?=number_format($harga_jual,'0',',','.');?>";

	//====================================get harga jual barang====================================

	    $('#barang_id_select').change(function(){
	    	<?if (is_posisi_id()==1) {?>
	    		alert("<?=$customer_id;?>");
	    		<?}?>
	    	var barang_id = $('#barang_id_select').val();
	    	var nama_jual_tercetak = $("#barang_id_select [value='"+barang_id+"']").text();
	   		var data = $("#form_add_barang [name=data_barang] [value='"+barang_id+"']").text().split('??');
	   		var tipe_qty = data[2];
	   		if (tipe_qty == 3) {
	   			$("#qty-table [name=jumlah_roll]").prop('readonly',true).css('background','#ddd');
	   			$("#stok-info .stok-roll").hide();
	   			$("#qty-info-add .jumlah_roll_total").hide();
	   		}else{
	   			$("#qty-table [name=jumlah_roll]").prop('readonly',false).css('background','#fff');
	   			$("#stok-info .stok-roll").show();
	   			$("#qty-info-add .jumlah_roll_total").show();
	   		}

	   		if(parseFloat(barang_id_last) == parseFloat(barang_id) ){
   				$('#form_add_barang [name=harga_jual]').val(harga_jual);
   			}else{
				if (barang_id != barang_id_last) {
				$('#form_add_barang [name=harga_jual]').val(change_number_format(data[1]));
				}else{
				$('#form_add_barang [name=harga_jual]').val(change_number_format(harga_jual));
				}

		   		// if (penjualan_type_id == 3 || penjualan_type_id == 1) {
		   			


		   		// }else{
			    // 	var data_st = {};
		   		// 	data_st['barang_id'] = $('#form_add_barang [name=barang_id]').val();
			    // 	data_st['customer_id'] =  "<?=$customer_id;?>";
			    // 	var url = "transaction/get_latest_harga";
			    // 	console.log(data_st);
	   				
		   		// 	ajax_data_sync(url,data_st).done(function(data_respond  /*,textStatus, jqXHR*/ ){
		   		// 		if (data_respond > 0) {
				// 			$('#form_add_barang [name=harga_jual]').val(change_number_format(data_respond));
		   		// 		}else{
				// 			$('#form_add_barang [name=harga_jual]').val(change_number_format(data[1]));
		   		// 		}
			   	// 	});
		   		// }
   				
   			}

			$('#nama_jual_tercetak').val(nama_jual_tercetak);
			$('#form_add_barang [name=satuan]').val(data[0]);
			$('#warna_id_select').select2('open');
	    });

	    $('#warna_id_select').change(function(){
	    	$('#form_add_barang [name=harga_jual]').focus();
	    });

	    $('.btn-cek-harga').click(function(){
	    	var data = {};
	    	data['barang_id'] = $('#form_add_barang [name=barang_id]').val();
	    	var penjualan_type_id = parseInt("<?=$penjualan_type_id;?>");
	    	var customer_id = '';
	    	if (penjualan_type_id != 3) {
	    		customer_id = "<?=$customer_id;?>";
	    	};
	    	data['customer_id'] =  customer_id;
	    	var url = 'transaction/cek_history_harga';
	    	if (data['barang_id'] != '') {
	    		var tbl = '<table>';
		    	ajax_data_sync(url,data).done(function(data_respond  /*,textStatus, jqXHR*/ ){
		    		console.log(data_respond)
		    		var isi_tbl = '';
					$.each(JSON.parse(data_respond),function(i,v){
						// alert(i +" == "+v);
						isi_tbl += "<tr>"+
							"<td>"+date_formatter(v.tanggal)+"</td>"+
							"<td> : </td>"+
							"<td>"+change_number_format(v.harga_jual)+"</td>"+
							"</tr>";
					});

					if (isi_tbl !='') {
						tbl += isi_tbl + "</table>";
				    	$('#data-harga').html(tbl);			
					}else{
				    	$('#data-harga').html("no data");
					};

		   		});
	    	}else{
	    		$('#data-harga').html("no data");
	    	}
	    	
	    });

	//====================================modal barang=============================
	
		<?if ($status == 1 && is_posisi_id() != 6) {?>
			var map1 = {220: false};
			$(document).keydown(function(e) {
			    if (e.keyCode in map1) {
			        map1[e.keyCode] = true;
			        if (map1[220]) {
			        	<?if (is_posisi_id()==1) {?>
				        	// alert(idx);
		        		<?};?>
			            $('#portlet-config-detail').modal('toggle');
						<?if ($po_penjualan_id != '') {?>
							showBarangPO('baru');
						<?}else{?>
							hideBarangPO('baru');
						<?}?>
			            
			        }
			    }
			}).keyup(function(e) {
			    if (e.keyCode in map1) {
			        map1[e.keyCode] = false;
			    }
			});
		<?};?>

		// $('.btn-brg-add').click(function(){
	    // 	// var select2 = $(this).data('select2');
	    // 	// alert(harga_jual);
		//     if (idx == '1') {
	    //     	setTimeout(function(){
		//     		$('#barang_id_select').select2("open");
		//     	},700);
	    //     }else{
	    //     	cek_last_input(gudang_id_last,barang_id, harga_jual);
	    //     }
	    // });

	//====================================qty manage=============================    

	    $(".btn-add-qty-row").click(function(){
	    	var jml_baris = $('#qty-table tbody tr').length;
	    	let baris = `<tr><td><input name='qty' tabindex='${2*jml_baris+1}'></td>
									<td><input name='jumlah_roll'  tabindex='${2*jml_baris+2}'></td>
									<td style='padding:0 10px'></td>
									<td></td>
									</tr>`;
	    	$('#qty-table tbody').append(baris);
	    });

		var map = {13: false};
		$("#qty-table tbody").on("keydown",'[name=qty], [name=jumlah_roll]', function(e) {
			let ini = $(this);
			let tabindex = ini.attr('tabindex');
			// alert(e.keyCode);
		    if (e.keyCode in map) {
		        map[e.keyCode] = true;
		        if (map[13]) {
		        	if (ini.attr('name') == 'jumlah_roll') {
		        		if(cekStok(ini, 'qty-table')){
				        	$("#qty-table").find(`[tabindex = ${parseInt(tabindex)+1}]`).focus();
		        		}
		        	}else{
				        $("#qty-table").find(`[tabindex = ${parseInt(tabindex)+1}]`).focus();
		        	};
		        	// alert(ini);
		            
		        }
		    }
		}).keyup(function(e) {
		    if (e.keyCode in map) {
		        map[e.keyCode] = false;
		    }
		});
		
	    $("#qty-table").on('input','[name=qty]',function(){
	    	let ini = $(this);
	   		let qty = ini.val();
	   		qtyListAmbil = [];
	   		if (qty.length > 1) {
		   		// alert(qtyListDetail[`s-${parseFloat(qty).toString().replace('.','_')}`]);
		   		if (qtyListDetail[`s-${parseFloat(qty).toString().replace('.','_')}`] > 0) {
		   			$("#stok-roll-info").show();
		   			// ini.closest('tr').find('td').eq(2).html(qtyListDetail[`s-${parseFloat(qty).toString().replace('.','_')}`]);
		   		};
		   	}
	    });

	    $("#qty-table").on('change','[name=qty]',function(){
			// coonsole.log(qtyListDetail);
	   		let ini = $(this);
	   		let qty = ini.val();
			var barang_id = $('#barang_id_select').val();
			var data = $("#form_add_barang [name=data_barang] [value='"+barang_id+"']").text().split('??');
	   		var tipe_qty = data[2];
			
			
	   		if (qty.length > 1) {
		   		let stat = true;
		   		// alert(qtyListDetail[`s-${parseFloat(qty).toString().replace('.','_')}`]);
		   		<?//if (is_posisi_id()==1) {?>
					if(tipe_qty != 2){
						if (typeof qtyListDetail[`s-${parseFloat(qty).toString().replace('.','_')}`] === 'undefined' || qtyListDetail[`s-${parseFloat(qty).toString().replace('.','_')}`] == 0) {
							stat = false;
							alert("Rincian tidak ada");
						}else{
							cekStok(ini, 'qty-table');
						}

						if (stat==false) {
							ini.val('');
							ini.focus();
							// ini.closest('tr').find('td').eq(2).html('');
						}else{
							$("#stok-roll-info").show();
						};

						// console.log(qtyListAmbil);
					}
	   			<?//};?>
	   		};

	   		if (qty.length == 0) {
	   			// ini.closest('tr').find('td').eq(2).html('');
	   		};
	    });

	    $('#qty-table').on('change','[name=jumlah_roll]', function(){
	    	let ini = $(this);
	    	if (ini.closest('tr').find('[name=qty]').val() == '') {
	    		alert('Mohon isi qty');
	    		ini.closest('tr').find('[name=qty]').focus();
	    		return false;
	    	}else{
		   		cekStok(ini, 'qty-table');
	    	};

	    });


	    $("#qty-table").on('change','input',function(){
	    	generateAmbilQty();
	    });

	//====================================qty edit manage=============================   

		$('#barang_id_select_edit').change(function(){
	    	var barang_id = $('#barang_id_select_edit').val();
	   		var data = $("#form_edit_barang [name=data_barang] [value='"+barang_id+"']").text().split('??');
	   		var tipe_qty = data[2];
	   		if (tipe_qty == 3) {
	   			$("#qty-table-edit [name=jumlah_roll]").prop('readonly',true).css({'background':'#ddd','color':'#ddd'});
	   			$("#stok-info-edit .stok-roll").hide();
	   			$("#qty-info-edit .jumlah_roll_total").hide();
	   		}else{
	   			$("#qty-table-edit [name=jumlah_roll]").prop('readonly',false).css({'background':'#fff','color':'#000'});
	   			$("#stok-info-edit .stok-roll").show();
	   			$("#qty-info-edit .jumlah_roll_total").show();
	   		}
	    });
        
        $('#general_table').on('click','.btn-edit', function(){
        	var ini = $(this).closest('tr');
        	form = $('#form_edit_barang');
        	stok_div = $('#stok-info-edit');

			// $('#barangPOSelectEdit').value=
			<?if ($po_penjualan_id != '' && count($po_penjualan_barang) > 0) {?>
				$("#namaBarangPO").val(ini.find(".nama_jual").html());
			<?}?>
			
        	form.find("[name=penjualan_detail_id]").val(ini.find(".id").html());
        	form.find("[name=barang_id]").val(ini.find(".barang_id").html());
        	form.find("[name=kode_beli]").val(ini.find(".kode_beli").html());
        	form.find("[name=nama_jual_tercetak]").val(ini.find(".nama_jual_tercetak").html());
        	form.find("[name=warna_id]").val(ini.find(".warna_id").html());
        	form.find("[name=gudang_id_ori]").val(ini.find(".gudang_id").html());
        	form.find("[name=gudang_id]").val(ini.find(".gudang_id").html());
        	form.find("[name=harga_jual]").val(ini.find(".harga_jual").html());
        	form.find("[name=satuan]").val(ini.find(".nama_satuan").html());
        	form.find("[name=rekap_qty]").val(ini.find(".data_qty").html());
	   		$('.edit-alamat-keterangan').hide();


        	form.find("[name=barang_id]").change();
        	form.find("[name=warna_id]").change();

        	get_qty(form, stok_div);

        });
        // get_qty($("#form_add_barang"), $('#stok_info'));
		

		$(document).on("click",".btn-edit-qty-row",function(){
			var jml_baris = $('#qty-table-edit tbody tr').length;
	    	var baris = `<tr><td>${jml_baris+1}</td><td><input name='qty' tabindex='${2*jml_baris+1}'></td>
									<td><input name='jumlah_roll'  tabindex='${2*jml_baris+2}'></td>
									<td style='padding:0 10px'></td>
									<td></td>
									</tr>`;

	    	$('#qty-table-edit').append(baris);
	    });

		$('.btn-qty-edit').click(function(){
			var form = $('#form_edit_barang');
			stok_div = $('#stok-info-edit');
        	get_qty(form, stok_div);
			$('#qty-table-edit tbody').html('');
        	
			var data_qty = form.find('[name=rekap_qty]').val();
			// $('#form-qty-update [name=rekap_qty]').val(data_qty);
			// $('#form-qty-update [name=id]').val($(this).closest('tr').find('.id').html());
			var data_break  = data_qty.split('--');
			// console.log(data_break);
			
			var baris = '';
			var i = 0; var total = 0; var idx = 1;
			// console.log('qDL',qtyListDetail);
			$.each(data_break, function(k,v){
				var qty = v.split('??');
				if (qty[1] == null) {
					qty[1] = 0;
				};
				total += qty[0]*qty[1]; 
				if (qty[0] != '') {
					if (i == 0 ) {

						//${qtyListDetail['s-'+parseFloat(qty[0])]}
						var baris = `<tr>
							<td>${idx}</td>
							<td><input name='qty' value='${parseFloat(qty[0])}' class='input1' tabindex='1'></td>
							<td><input name='jumlah_roll' value='${qty[1]}' tabindex='2'></td>
							<td style='padding:0 10px'></td>
							<td><button tabindex='-1' class='btn btn-xs blue btn-edit-qty-row'><i class='fa fa-plus'></i></button></td>
							</tr>`;
						idx++;
						$('#qty-table-edit tbody').append(baris);

					}else{
						// ${qtyListDetail['s-'+parseFloat(qty[0])]}
						var baris = `<tr>
							<td>${idx}</td>
							<td><input name='qty' value='${parseFloat(qty[0])}' tabindex='${2*(parseInt(i)+1) + 1}' ></td>
							<td><input name='jumlah_roll' value='${qty[1]}'  tabindex='${2*(parseInt(i)+1) + 2}' ></td>
							<td style='padding:0 10px'></td>
							<td></td>
							</tr>`;
						idx++;

						$('#qty-table-edit tbody').append(baris);
					}
					
				};
				i++;
			});


			for (var j = i; j < parseInt(i)+5; j++) {
				var baris = `<tr>
						<td>${idx}</td>
						<td><input name='qty' value='' tabindex='${2*(parseInt(j)+1) + 1}' ></td>
						<td><input name='jumlah_roll' value='' tabindex='${2*(parseInt(j)+1) + 2}'  ></td>
						<td  style='text-align:center'></td>
						<td></td>
						</tr>`;
				idx++;
				$('#qty-table-edit tbody').append(baris);	
			};

			// console.log('test',baris);


			update_qty_edit();
        	$("#barang_id_select_edit").change();

		});

		
		$("#qty-table-edit").on('input','[name=qty]',function(){
	    	let ini = $(this);
	   		let qty = ini.val();
	   		qtyListAmbil = [];
	   		if (qty.length > 1) {
		   		// alert(qtyListDetail[`s-${parseFloat(qty).toString().replace('.','_')}`]);
				console.log('qd',qtyListDetail);
		   		if (qtyListDetail[`s-${parseFloat(qty).toString().replace('.','_')}`] > 0) {
		   			$("#stok-roll-info-edit").show();
		   			// ini.closest('tr').find('td').eq(3).html(qtyListDetail[`s-${parseFloat(qty).toString().replace('.','_')}`]);
		   		};
		   	}
	    });

	    $("#qty-table-edit").on('change','[name=qty]',function(){
	   		let ini = $(this);
	   		let qty = ini.val();
	   		if (qty.length > 0) {
		   		let stat = true;
		   		// alert(qtyListDetail[`s-${parseFloat(qty).toString().replace('.','_')}`]);
		   		if (typeof qtyListDetail[`s-${parseFloat(qty).toString().replace('.','_')}`] === 'undefined' || qtyListDetail[`s-${parseFloat(qty).toString().replace('.','_')}`] == 0) {
		   			stat = false;
		   			alert("Rincian tidak ada");
		   		}

				<?if(is_posisi_id() == 1){?>
					// alert('ccc'+qty);
				<?}?>

	   			if (stat==false) {
	   				ini.val('');
	   				ini.focus();
		   			// ini.closest('tr').find('td').eq(3).html('');
	   			}else{
		   			$("#stok-roll-info-edit").show();
		   			cekStok(ini, 'qty-table-edit');
	   			};

	   			console.log(qtyListAmbil);
	   		};

	   		if (qty.length == 0) {
	   			// ini.closest('tr').find('td').eq(3).html('');
	   		};
	    });

	    $('#qty-table-edit').on('change','[name=jumlah_roll]', function(){
	    	let ini = $(this);
	    	if (ini.closest('tr').find('[name=qty]').val() == '') {
	    		alert('Mohon isi qty');
	    		ini.closest('tr').find('[name=qty]').focus();
	    		return false;
	    	}else{
		   		cekStok(ini, 'qty-table-edit');
	    	};

	    });

	    // var map = {13: false};
		$("#qty-table-edit tbody").on("keydown",'[name=qty], [name=jumlah_roll]', function(e) {
			let ini = $(this);
			let tabindex = ini.attr('tabindex');
			// alert(e.keyCode);
		    if (e.keyCode in map) {
		        map[e.keyCode] = true;
		        if (map[13]) {
		        	if (ini.attr('name') == 'jumlah_roll') {
		        		if(cekStok(ini, 'qty-table-edit')){
				        	$("#qty-table-edit").find(`[tabindex = ${parseInt(tabindex)+1}]`).focus();
		        		}
		        	}else{
		        		// alert(tabindex);
				        $("#qty-table-edit").find(`[tabindex = ${parseInt(tabindex)+1}]`).focus();
		        	};
		        	// alert(ini);
		            
		        }
		    }
		}).keyup(function(e) {
		    if (e.keyCode in map) {
		        map[e.keyCode] = false;
		    }
		});




		$("#qty-table-edit").on('change',"input",function(){
	    	update_qty_edit();    	
	    });

		$(".btn-brg-edit-save").click(function(){
			// var data = {};
			// var id = $('#form-qty-update [name=id]').val();
			// var row = $('#id_'+id);
			// var ini = $('#id_'+id);
			// // alert(row.find('.jumlah_roll').html());
			// data['penjualan_detail_id'] = id;
			// data['rekap_qty'] = $('#form-qty-update [name=rekap_qty]').val();
			// var url = 'transaction/penjualan_qty_detail_update';
			// ajax_data_sync(url,data).done(function(data_respond  /*,textStatus, jqXHR*/ ){
			// 	// alert(data_respond);
			// 	if (data_respond == 'OK') {				
			// 		$('#portlet-config-qty-edit').modal('toggle');
						
			// 		var qty = $('#portlet-config-qty-edit .yard_total').html();
			// 		var total_roll = $('#portlet-config-qty-edit .jumlah_roll_total').html();

			// 		var harga = ini.find('[name=harga_jual]').val();
			// 		// alert(harga);
			// 		var subtotal = parseFloat(qty) * reset_number_format(harga);
			// 		// alert(subtotal);
			// 		ini.find('.subtotal').html(change_number_format(subtotal));
			// 		ini.find('.jumlah_roll').html(total_roll);
			// 		ini.find('.qty').html(qty);
			// 		ini.find('.data_qty').html(data['rekap_qty']);
			// 		update_table();
			// 	}else{
			// 		bootbox.confirm("Error, tolong muat ulang halaman", function(respond){
			// 			if(respond){
			// 				window.location.reload();
			// 			}
			// 		});
			// 	};
	  		//	});

			$('#form_edit_barang').submit();
			btn_disabled_load($(this));

		});

	//====================================update harga=============================    
	
		$('#general_table').on('change','[name=harga_jual]', function(){
			var ini = $(this).closest('tr');
			var data = {};
			data['id'] = ini.find('.id').html();
			data['harga_jual'] = $(this).val();
			var url = "transaction/update_penjualan_detail_harga";
			var qty = ini.find('.qty').html();
			ajax_data_sync(url,data).done(function(data_respond  /*,textStatus, jqXHR*/ ){
				if (data_respond == 'OK') {
					var subtotal = qty*data['harga_jual'];
					ini.find('.subtotal').html(change_number_format(subtotal));
					update_table();
				}else{
					bootbox.confirm("Error, tolong muat ulang halaman", function(respond){
						if(respond){
							window.location.reload();
						}
					});
				};
	   		});		
		});

	//====================================btn save=============================    


	    $('.btn-brg-save').click(function(){
	    	var ini = $(this);
	    	var yard = reset_number_format($('.yard_total').html());
	    	if( yard > 0){
	    		$('#form_add_barang').submit();
	            btn_disabled_load(ini);

	    	}
	    });


	    $('.btn-save').click(function(){
	    	var ini = $(this);
	    	var penjualan_type_id = $('#form_add_data [name=penjualan_type_id]').val();
	    	if ($('#form_add_data [name=tanggal]').val() != '') {
	    		if (penjualan_type_id == 1 || penjualan_type_id == 2 ) {
	    			if($('#form_add_data [name=customer_id]').val() != ''){
						const customer_id = $('#customer_id_select').val();

						if (penjualan_type_id == 2) {
							if (!cek_customer_kredit_permit(customer_id)) {
								$(".btn-save").prop('disabled', true);
								bootbox.alert("Fitur kredit tidak tersedia untuk customer ini");
							}else{
								$('#form_add_data').submit();
							}
						}else if (bonBaru) {
	    					bonBaru = false;
		    				$('#form_add_data').submit();
		    				setTimeout(function(){
		    					bonBaru = true;
		    				},10000);
	    				}else{
	    					alert("mohon tunggu 10 detik");
	    				};
	    			}else{
	    				notific8('ruby','Mohon pilih customer');
	    			}
	    		}else{
					if($('#form_add_data [name=nama_keterangan]').val() != ''){
						$('#form_add_data').removeAttr('target');
						if (bonBaru) {
							bonBaru = false;
							$('#form_add_data').submit();
							setTimeout(function(){
								bonBaru = true;
							},10000);
						}else{
							alert("mohon tunggu 10 detik");
						};
						btn_disabled_load(ini);
					}else{
						notific8('ruby','Mohon isi nama')
					}
	    		};
	    	}else{
				notific8('ruby','Mohon isi tanggal')
	    		// alert("Mohon isi tanggal !");
	    	};
	    });

	    var idx_submit = 1;
	    $('.btn-save-tab').click(function(){
	    	let ini = $(this);
	    	let penjualan_type_id = $('#form_add_data [name=penjualan_type_id]').val();
	    	if ($('#form_add_data [name=tanggal]').val() != '') {
	    		if (penjualan_type_id == 1 || penjualan_type_id == 2 ) {
	    			if($('#form_add_data [name=customer_id]').val() != ''){
	    				$('#form_add_data').submit();
	    			}else{
	    				notific8('ruby','Mohon pilih customer');
	    			}
	    		}else{
					idx++;
	    			$('#form_add_data').attr('target','_blank');
	    			$('#portlet-config').modal('toggle');
	    			$('#form_add_data [name=nama_keterangan]').val('');
	    			btn_disabled_load($('.btn-save-tab'));
	    			setTimeout(function(){
	    				if (idx_submit == 2) {
			    			$('#form_add_data').submit();
	    				}else{
	    					idx_submit = 2;
	    				};
		    			$(".btn-active").prop('disabled',false);
					    $('.btn-save-tab').html("Save & New Tab");
					    // alert(idx_submit);
	    			},2000);
	    		};
	    	}else{
	    		alert("Mohon isi tanggal !");
	    	};
	    });

	    $('.btn-edit-save').click(function(){
			const row = document.querySelectorAll("#general_table tbody tr");
			// console.log(row.length);
	    	var penjualan_type_id = $('#form_edit_data [name=penjualan_type_id]').val();
	    	if ($('#form_edit_data [name=tanggal]').val() != '') {
	    		if (penjualan_type_id == 1 || penjualan_type_id == 2 ) {
	    			if($('#form_edit_data [name=customer_id]').val() != ''){
	    				$('#form_edit_data').submit();
	    			}else{
	    				notific8('ruby','Mohon pilih customer');
	    			}
	    		}else{
	    			$('#form_edit_data').submit();
	    		};
	    	}else{
	    		alert("Mohon isi tanggal !");
	    	};
	    });

	//====================================bayar==========================================
		var saldo_awal ='<?=$saldo_awal;?>';
		<?if ($penjualan_id != '' && $status == 1) {?>

			$('.bayar-input').dblclick(function(){
				var id_data = $(this).attr('id').split('_');
				var penjualan_type_id = "<?=$penjualan_type_id?>";
				var ini = $(this);

				if ($(this).val() == 0 || $(this).val() == '' ) {
					var g_total = reset_number_format($('.g_total').html());
					var total_bayar = reset_number_format($('.total_bayar').html());
					var sisa = parseInt(g_total) - parseInt(total_bayar);

					if (sisa > 0) {
						if ($(this).hasClass('bayar-kredit') && penjualan_type_id != 2) {

						}else{
							$(this).val(change_number_format(sisa));
							var data = {};
							data['pembayaran_type_id'] = id_data[1];
							data['penjualan_id'] = '<?=$penjualan_id?>';
							data['amount'] = ini.val();
							var url = 'transaction/pembayaran_penjualan_update';
							update_db_bayar(url, data);
						};
					};
					
				};
			});

			var bayar = true;

			<?if($status==1){?>
				$('#bayar-data tr td').on('change','input', function(){
					var id_data = $(this).attr('id').split('_');
					if (id_data[1] == 1) {
						var s_awal = reset_number_format(saldo_awal);
						var isi = $(this).val();
						var dp_initial = reset_number_format($('.dp_copy').html());
						var sisa = parseInt(s_awal) + dp_initial - reset_number_format(isi);
						// alert(s_awal+'+'+dp_initial+'+'+isi);
						if (sisa >= 0) {
							// alert('true');
							bayar = true;
						}else{
							$(this).val(0);
							bayar == false;
							alert('Saldo Tidak Cukup');
						};
					};
	
					if (bayar) {
						var data = {};
						data['pembayaran_type_id'] = id_data[1];
						data['penjualan_id'] = '<?=$penjualan_id?>';
						data['amount'] = $(this).val();
						var penjualan_type_id = "<?=$penjualan_type_id?>";
						if (data['pembayaran_type_id'] == 5 && penjualan_type_id != 2 ) {
							data['amount'] = 0;
							$(this).val(0);
							alert("Tipe bukan kredit pelanggan");
						}
						var url = 'transaction/pembayaran_penjualan_update';
						update_db_bayar(url, data);
					};
				});			
			<?}?>

		<?};?>

		$(document).on('change', '.keterangan_bayar',function(){
			var data = {};
	    	data['penjualan_id'] =  "<?=$penjualan_id;?>";
	    	data['keterangan'] = $(this).val();
	    	var url = 'transaction/pembayaran_transfer_update';
	    	
	    	// alert(data['keterangan']);
	    	ajax_data_sync(url,data).done(function(data_respond  /*,textStatus, jqXHR*/ ){
				if (data_respond == 'OK') {
					// update_table();
				}else{
					bootbox.confirm("Error, tolong muat ulang halaman", function(respond){
						if(respond){
							window.location.reload();
						}
					});
				};
	   		});
		});

	//==============================================================================

	    <?if ($penjualan_id != '') { ?>
	    	$(document).on('change','.diskon, .ongkos_kirim, .keterangan ', function(){
	    		var value = $(this).val();
	    		if ($(this).attr('name') != 'keterangan') {
	    			value = reset_number_format(value);
	    		};
		    	var ini = $(this).closest('tr');
		    	var data = {};
		    	data['column'] = $(this).attr('name');
		    	data['penjualan_id'] =  "<?=$penjualan_id;?>";
		    	data['value'] = value;
		    	var url = 'transaction/penjualan_data_update';
		    	// update_table(ini);
		    	ajax_data_sync(url,data).done(function(data_respond  /*,textStatus, jqXHR*/ ){
					if (data_respond == 'OK') {
						update_table();
					}else{
						bootbox.confirm("Error, tolong muat ulang halaman", function(respond){
							if(respond){
								window.location.reload();
							}
						});
					};
		   		});
		    });
	    <?}?>

	    <?if ($penjualan_id != '') {?>
	    	$('.btn-close').click(function(){
	    		var kembali = reset_number_format($('.kembali').html());
	    		var g_total = reset_number_format($('.g_total').html());
	    		var tanggal = "<?=$ori_tanggal;?>";
	    		var id = "<?=$penjualan_id;?>";
	    		if (g_total <= 0) {
	    			// bootbox.alert("Error! Total tidak boleh 0");
    				bootbox.confirm("Total nota 0, Yakin untuk melanjutkan", function(respond){
    					if (respond) {
		    				$("#portlet-config-posisi-barang").modal("toggle");
		    				// window.location.replace(baseurl+'transaction/penjualan_list_close?id='+id+"&tanggal="+tanggal);
    					};
    				});
	    			// $("#portlet-config-sample").modal('toggle');
	    			// setTimeout(function(){
	    			// 	$("#form-sample").find('.pin_user').focus();
	    			// },700);
	    		}else if (penjualan_type_id != 2) {
	    			if (kembali >= 0 ) {
	    				$("#portlet-config-posisi-barang").modal("toggle");
	    				// window.location.replace(baseurl+'transaction/penjualan_list_close?id='+id+"&tanggal="+tanggal);
	    			}else{
	    				bootbox.alert('Kembali tidak boleh minus');
	    			}
	    		}else{
    				$("#portlet-config-posisi-barang").modal("toggle");
			    	// window.location.replace(baseurl+'transaction/penjualan_list_close?id='+id+"&tanggal="+tanggal);
	    		}
		    });
	    <?}?>

	    $(".btn-close-ok").click(function() {
	    	var id = "<?=$penjualan_id?>";
			var tanggal = "<?=$ori_tanggal;?>";
	    		var kembali = reset_number_format($('.kembali').html());

	    	// if(cek_pin($('#form-sample'))) {
				if (kembali < 0) {
    				bootbox.alert('Kembali tidak boleh minus');
				}else{
					$('#form-posisi-barang').submit();
					$('#form-posisi-barang').submit();
				};
	    	// }
	    })

	//=====================================remove barang=========================================
		$('#general_table').on('click','.btn-detail-remove', function(){
			var ini = $(this).closest('tr');
			bootbox.confirm("Yakin mengahpus item ini?", function(respond){
				if (respond) {
					var data = {};
					data['id'] = ini.find('.id').html();
					var url = 'transaction/penjualan_list_detail_remove';
					ajax_data_sync(url,data).done(function(data_respond  /*,textStatus, jqXHR*/ ){
						if (data_respond == "OK") {
							ini.remove();
							update_table();
						}else{
							alert("Error");
						}
					}); 
				};
			});
		}) ;  

	//=====================================bayar giro=========================================
		$(".btn-save-giro").click(function(){
			if ($('#form-data-giro [name=nama_bank]').val() != '' && $('#form-data-giro [name=no_rek_bank]').val() != '' && $('#form-data-giro [name=tanggal_giro]').val() != '' && $('#form-data-giro [name=jatuh_tempo]').val() != '' && $('#form-data-giro [name=no_akun]').val() != '' ) {
				$('#form-data-giro').submit();
			}else{
				alert("mohon lengkapi data giro")
			};
		});


	//=====================================bayar dp=========================================

		$('#dp_list_table').on('change','.dp-check', function(){
			let ini = $(this).closest('tr');
			var bon = reset_number_format($(".dp-nilai-bon-info").html());
			var bayar_dp = reset_number_format($('.dp-total').html());
			var sisa = bon - bayar_dp;
			// alert($(this).is(':checked'));
			if($(this).is(':checked')){
				let dp_nilai = reset_number_format(ini.find('.amount').html());
				ini.find('.amount-bayar').prop('readonly',false);
				if (sisa > dp_nilai) {
					ini.find('.amount-bayar').val(dp_nilai);
				}else{
					ini.find('.amount-bayar').val(sisa);
				}
			}else{
				ini.find('.amount-bayar').prop('readonly',true);
				ini.find('.amount-bayar').val(0);
			}
			dp_table_update();
		});

		$('#dp_list_table').on('change','.amount-bayar', function(){
			let ini = $(this).closest('tr');
			dp_table_update();
		});
		
		$('.btn-save-dp').click(function(){
			$('#form-dp').submit();
			btn_disabled_load($(this));
		});

	//========================================btn-detail============================

		$(".btn-detail-toggle").click(function(){
			$('#general-detail-table').toggle('slow');
		});

	//========================================btn-detail============================

    $("#ppn_list_add").change(function(){
        const ppn_v = $(this).val();
        $('#ppn_list_add_text').val(parseFloat(ppn_v))
        // $("#ppn_list_add").prop('readonly',true);
    });

    $("#ppn_list_edit").change(function(){
        const ppn_v = $(this).val();
        $('#ppn_list_edit_text').val(parseFloat(ppn_v))
        // $("#ppn_list_add").prop('readonly',true);
    });

	const inputTercetak = document.querySelectorAll(".nama-tercetak");
	inputTercetak.forEach(input => {
		input.addEventListener("dblclick", (e) =>{
			if (input.readOnly === true) {
				const pC = input.parentElement.querySelector(".pin-container")
				pC.style.display='block';
				pC.querySelector(".pin-nama-tercetak").focus();
			}
		});
	}); 

	const pinInputTercetak = document.querySelectorAll(".pin-nama-tercetak");
	pinInputTercetak.forEach(input => {
		input.addEventListener("keyup", (e) =>{
			const mainInputId = input.getAttribute("for-id");
			const mainInput = document.querySelector(`#${mainInputId}`);
			if (e.key === 'Enter' || e.keyCode == 13) {
				e.preventDefault();
				fetch(baseurl+`transaction/cek_pin_json`,{
					method: 'POST',
					headers: {
						'Content-Type': 'application/x-www-form-urlencoded',
					},
					body: `pin=${input.value}`
				})
				.then((response) => response.json())
				.then((data) => {
					if (data === "OK") {
						mainInput.readOnly = false
						mainInput.focus();
						input.parentElement.style.display = "none";
					}else{
						bootbox.alert(data);
					}
				});
			}
		});
	}); 

});

//========================================end jquery ready============================

</script>


<?
$nama_toko = '';
$alamat_toko = '';
$telepon_toko = '';
$fax = '';
$npwp = '';
$kota_toko = "";



if ($penjualan_id != '') {

	foreach ($data_toko as $row) {
		$nama_toko = trim($row->nama);
		$alamat_toko = trim($row->alamat);
		$telepon_toko = trim($row->telepon);
		$fax = trim($row->fax);
		$npwp = trim($row->NPWP);
		$kota_toko = trim($row->kota);

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

	// if ($printer_marker == 3) {
	// 	include_once 'print/print_faktur_3.php';
	// 	include_once 'print/print_detail_3.php';
	// 	include_once 'print/print_faktur_detail_3.php';
	// 	include_once 'print/print_surat_jalan_3.php';
	// 	include_once 'print/print_surat_jalan_noharga_3.php';
	// }else{
	// 	include_once 'print_faktur_testing.php';
	// 	include_once 'print_faktur.php';
	// 	include_once 'print_detail.php';
	// 	include_once 'print_faktur_detail.php';

	// 	include_once 'print_surat_jalan.php';
	// 	include_once 'print_surat_jalan_noharga.php';
	// }

	// if (is_posisi_id() == 1) {
	// 	// include_once 'print_font_test.php';
	// 	// include_once 'print_testing.php';
	// }
}?>

<script>
function showBarangPO(ele){
	const brg = $(`#barang-group-${ele}`);
	const brgPo = $(`#barang-po-${ele}`);
	$('#gudang_id_select').val(gudang_id_last);


	brg.hide();
	brgPo.show();

	setTimeout(function(){
		$('#barangPOSelect').select2("open");
	},700);
}

function hideBarangPO(ele) {
	const brg = $(`#barang-group-${ele}`);
	const brgPo = $(`#barang-po-${ele}`);

	brg.show();
	brgPo.hide();

	if (parseInt(idx_detail) == 1) {
		setTimeout(function(){
			$('#barang_id_select').select2("open");
		},700);
	}else{
		// console.log(gudang_id_last,barang_id_last, harga_jual_last);
		cek_last_input(gudang_id_last,barang_id_last, harga_jual_last);
	}
}
	
function resetFormAdd(){
	$(".btn-save").prop('disabled',false);
	$(".pin_special").val('');
	$('.pin_special').css('color','#000');
	$('#po_penjualan_select').html("");
}

function dp_update_bayar(){
	var dp_total = reset_number_format($("#dp_list_table .dp-total").html());
	var g_total = $(".dp-nilai-bon-info").html();
	g_total = reset_number_format(g_total);
	$('#dp_list_table .dp-sisa-info').html(change_number_format(g_total - dp_total));
}

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

function update_db_bayar(url,data){
	ajax_data_sync(url,data).done(function(data_respond  ,textStatus, jqXHR ){
		console.log(jqXHR);
		if (data_respond == 'OK') {
			update_bayar();
			if (data['pembayaran_type_id'] == 6 ) {
				$("#portlet-config-giro").modal('toggle');
			};
		}else{
			bootbox.confirm("Error, tolong muat ulang halaman", function(respond){
				if(respond){
					window.location.reload();
				}
			});
		};
	});
}

function get_qty(form, stok_div){

    let nama_form = form.attr('id');

    var penjualan_detail_id = form.find(' [name=penjualan_detail_id]').val();
    if (penjualan_detail_id == '') {
		$('#qty-table-detail-edit').html(`<tr><td><p style='color:#ddd'><i>loading.....</i></p></td></tr>`);
    }else{
    	$('#qty-table-detail').html(`<tr><td><p style='color:#ddd'><i>loading.....</i></p></td></tr>`);
    }
    var data = {};
    data['gudang_id'] = form.find(' [name=gudang_id]').val();
    data['barang_id'] = form.find(' [name=barang_id]').val();
    data['warna_id'] = form.find(' [name=warna_id]').val();
    // var penjualan_detail_id = form.find(' [name=penjualan_detail_id]').val();
    data['penjualan_detail_id'] = penjualan_detail_id;
    <?if (is_posisi_id()==1) {?>
	    // alert(data['barang_id']+'=='+data['warna_id']);
	<?};?>
    // alert(form.find(' [name=penjualan_detail_id]').val());
    data['tanggal'] = form.find(' [name=tanggal]').val();
    // $('#qty-table-detail').empty();
    // $('#qty-table-detail-edit').empty();
    var url = "transaction/get_qty_stock_by_barang";
    // alert(data['gudang_id']);
	$('#overlay-div').show();
	let tblD = '';
    ajax_data_sync(url,data).done(function(data_respond  ,textStatus, jqXHR ){
        // alert(data_respond);
		$('#overlay-div').hide();
        // console.log(JSON.parse(data_respond));
        qtyTblDetail = [];
        $.each(JSON.parse(data_respond), function(k,v){
            if (k==0) {
			qtyListDetail = [];
			    $('#qty-table-detail').empty();
			    $('#qty-table-detail-edit').empty();
            	if (v[0].qty == 0) {
            		tblD = "<tr><td> Tidak ada stok </td></tr>";
			
            	};
            	// console.log(v);
	            stok_div.find('.stok-qty').html(parseFloat(v[0].qty));
	            stok_div.find('.stok-roll').html(v[0].jumlah_roll);
            }else if(k==1){
            	for (var i = 0; i < v.length; i++) {
								
					let sisa_roll = parseFloat(v[i].roll_stok_masuk) + parseFloat(v[i].jumlah_roll_masuk) - v[i].roll_stok_keluar - v[i].jumlah_roll_keluar;
            		if (v[i].qty == '100' || parseFloat(v[i].qty) == 100) {
    					// console.log('detail',parseFloat(v[i].roll_stok_masuk) +'+'+ parseFloat(v[i].jumlah_roll_masuk) +'-'+ v[i].roll_stok_keluar +'-'+ v[i].jumlah_roll_keluar);
	            		// console.log(v[i].qty+':', sisa_roll);
    				}
            		// console.log(v[i].qty+':', sisa_roll);
            		if (sisa_roll > 0) {
            			qtyListDetail[`s-${parseFloat(v[i].qty).toString().replace('.','_')}`] = sisa_roll;
            			for (var j = 0; j < sisa_roll; j++) {
            				if (v[i].qty != '100' && v[i].qty != 100) {
		            			qtyTblDetail.push(parseFloat(v[i].qty));
            				};
            			};
            		}else if(v[i].qty == 100){
						qtyListDetail[`s-100`] = 0;
					};
            	};

            	// console.log('si-100', qtyListDetail[`s-100`]);
            	let brs = Math.ceil((qtyTblDetail.length/5) );
            	// console.log('brs', brs);
            	// console.log('s-100', qtyListDetail[`s-100`]);
            	if (qtyListDetail[`s-100`] > 0) {
            		tblD += `<tr><td colspan='5' style='padding:5px; text-align:center' class='s-100'> 100 x ${qtyListDetail['s-100']}</td></tr>`
            	};
            	for (var i = 0; i < brs; i++) {
            		tblD += '<tr>';
	            	for (var j = 0; j < 5; j++) {
	            		if (typeof qtyTblDetail[(i*5) + j] !== 'undefined') {
		            		tblD += `<td class='s-${qtyTblDetail[(i*5) + j].toString().replace('.','_')}'>${qtyTblDetail[(i*5) + j]}</td>`
			            	// console.log('l',tblD);
	            			
	            		};
	            	};
            		tblD += '<tr>';
            	};
            };

			<?if (is_posisi_id() == 1) {?>
				// console.log(k);
				if (k == 5) {
					let qData = v;
					let ins_qty = [];
					// penjualan_detail_id
					// console.log(qData);

					for (let m = 0; m < qData.length; m++) {
						if (qData[m].jumlah_roll > 0) {
							ins_qty.push({
								'penjualan_detail_id' : penjualan_detail_id,
								'qty' :  qData[m].qty,
								'jumlah_roll' : qData[m].jumlah_roll
							});
						}	
					}

					// console.log(ins_qty);
				}
			<?}?>
        });

        qtyListAmbil = [];
    	if (penjualan_detail_id == '') {
	        $('#qty-table-detail').append(tblD);
        	cekStok('', 'qty-table-edit');
    	}else{
	    	// console.log('tb;D', tblD);
	        $('#qty-table-detail-edit').append(tblD);
        	$('#qty-table input').val('');
    	};
        
        // alert(data_respond);
        // console.log(data_respond);
    });
}

function dp_table_update(){
	let total_dp = 0;
	$('#dp_list_table .amount-bayar').each(function(){
		total_dp += reset_number_format($(this).val());
	});

	$('.dp-total').html(change_number_format(total_dp));

	dp_update_bayar();

}


function cek_last_input(gudang_id,barang_id, harga_jual){
	setTimeout(function(){
		// console.log(gudang_id);
		// $('#barang_id_select').select2("open");
		$('#gudang_id_select').val(gudang_id);
		$('#barang_id_select').val(barang_id);
    	$('#barang_id_select, #gudang_id_select').change();
    	setTimeout(function(){
        	$('#harga_jual_add').val(harga_jual);
    	},700);

	},650);
}

function print_try(idx){
	if (idx == 1) {
		qz.websocket.connect().then(function() {
		});
	};

	var config = qz.configs.create("Nota");             // Exact printer name from OS
  	var data = ['\x1B' + '\x40',
  		<?for ($i=0; $i <= 100 ; $i++) { ?>
  			'\x1B' + '\x21' + '\x<?=str_pad($i,2,"0",STR_PAD_LEFT);?>', // em mode on
		   	'<?=$i;?>'+' : 1234567890',
		   	'\x0A',
  		<?}?>


  	];

  	qz.print(config, data).then(function() {
	   // alert("Sent data to printer");
	});
}

function save_penjualan_baru(ini){
	ini.prop('disabled',true);
	// $('#form_add_data').submit();
	setTimeout(function(){
		ini.prop('disabled',false);
	},2000);
}

function startConnection(config) {
    qz.websocket.connect().then(function() {
	   	alert("Connected!");
		find_printer();
	});

}

function find_printer(){
	qz.printers.find("Nota").then(function(found) {
	   	alert("Printer: " + found);
	 //   	var config = qz.configs.create("Nota");             // Exact printer name from OS
		// var data = ['^XA^FO50,50^ADN,36,20^FDRAW ZPL EXAMPLE^FS^XZ'];   // Raw commands (ZPL provided)
		// qz.print(config, data).then(function() {
		//    alert("Sent data to printer");
		// });
	});
}


function cekCustomerLimit(){
	const customer_id = $('#customer_id_select').val();
    const typeId= $('#penjualan-type-id-add').val();
    let sisa_piutang = 0;
	if (customer_id != '' && typeId == 2) {
		const dialog = bootbox.dialog({
			message: '<p class="text-center mb-0"><i class="fa fa-spin fa-cog"></i> Checking Limit Customer.....</p>',
			closeButton: false
		});

        if (cekLimitDB(customer_id, dialog)){
            document.querySelector("#btnAddPenjualan").disabled = true;
            document.querySelector(".limit-warning").style.display = 'block';
        }else{
            document.querySelector("#btnAddPenjualan").disabled = false;
            document.querySelector(".limit-warning").style.display = 'none';
        };

		dialog.modal('hide');
	}else{
        document.querySelector("#btnAddPenjualan").disabled = false;
        document.querySelector(".limit-warning").style.display = 'none';
    }
	

}

function cekCustomerLimitEdit(){
	const customer_id = $('#customer_id_select_edit').val();
    const typeId= $('#penjualan-type-id-add').val()
    let sisa_piutang = 0;
	if (customer_id != '' && typeId == 2) {
		const dialog = bootbox.dialog({
			message: '<p class="text-center mb-0"><i class="fa fa-spin fa-cog"></i> Checking Limit Customer.....</p>',
			closeButton: false
		});

        if (cekLimitDB(customer_id, dialog)){
            document.querySelector("#btnAddPenjualan").disabled = true;
            document.querySelector(".limit-warning").style.display = 'block';
        }else{
            document.querySelector("#btnAddPenjualan").disabled = false;
            document.querySelector(".limit-warning").style.display = 'none';
        };

		dialog.modal('hide');

	}else{
        document.querySelector("#btnAddPenjualan").disabled = false;
        document.querySelector(".limit-warning").style.display = 'none';
    }
}

function cekLimitDB(customer_id, dialog){
    fetch(baseurl+`transaction/get_customer_limit?customer_id=${customer_id}`)
	.then((response) => response.json())
	.then((data) => {
		const dt = data[0];
		if(data[0].limit_amount != null && data[0].sisa_limit <= 0){
			return false;
		}else{
			return true;
		}
	});
}


function getPOList(customer_id, selectId, inputPO){
	
	const selectEl = document.querySelector(selectId);
	const po_penjualan_id = "<?=$po_penjualan_id?>";
	selectEl.innerHTML = "";
	const opt = document.createElement("option");
	opt.value = '';
	opt.text = "Non PO";
	selectEl.add(opt)
	const opt2 = document.createElement("option");
	opt2.value = 'manual';
	opt2.text = "SAMPLE/NOTE";
	selectEl.add(opt2)
    fetch(baseurl+`transaction/get_customer_po?customer_id=${customer_id}&po_penjualan_id=${po_penjualan_id}`)
	.then((response) => response.json())
	.then((data) => {
		console.log(data);
		data.forEach(list => {
			const opt = document.createElement("option");
			opt.value = list.id;
			opt.text = list.po_number;
			selectEl.add(opt)
		});
	});
	poEvt(selectId, inputPO);
}

function poEvt(selectId, inputId){
	const selectEl = document.querySelector(selectId);
	const inputPO = document.querySelector(inputId);
	const initVal = inputPO.value;

	console.log(selectId, inputId);
	selectEl.addEventListener("change", function(e){
		console.log(e.target.value);
		if (e.target.value == "manual") {
			if (initVal == '') {
				inputPO.value = 'SAMPLE';
			}else{
				inputPO.value = "<?=$po_number;?>";
			}
			inputPO.style.display='inline-block';
			inputPO.focus();
		}else if(e.target.value != ''){
			inputPO.style.display='none';
			const idx = e.target.selectedIndex;
			const txt = selectEl.options[idx].text;
			inputPO.value = txt;
			// document.querySelector("#inputPOAdd").text=e.target.text;
		}else{
			inputPO.value = '';
			inputPO.style.display='none';
		}
	})
}



function cek_pin(form, field_class){
	// alert('test');
	var data = {};
	data['pin'] = form.find(field_class).val();
	var url = 'transaction/cek_pin';
	// ajax_data_sync(url,data).done(function(data_respond  /*,textStatus, jqXHR*/ ){
	// 	if (data_respond == "OK") {
	// 		return true;
	// 	}else{
	// 		alert("PIN Invalid");
	// 	}
	// });

	var result = ajax_data(url,data);
	if (result == 'OK') {
		return true;
	};
}

function update_bayar(){
	var bayar = 0;
	var g_total = reset_number_format($('.g_total').html()) ;
	$('#bayar-data tr td input').each(function(){
		if ($(this).attr('class') != 'keterangan_bayar') {
			bayar += reset_number_format($(this).val());			
		};
	});

	var kembali = bayar - g_total ;
	// console.log(bayar, g_total);
	$('.total_bayar').html(change_number_format(bayar) );
	$('.kembali').html(change_number_format(kembali));

	if (kembali < 0) {
		$('.kembali').css('color','red');
	}else{
		$('.kembali').css('color','#333');
	}

}

function update_qty_edit(){
    var total = 0; var idx = 0; var rekap = [];
	var total_roll = 0;
	var total_stok = $('#stok-info-edit').find('.stok-qty').html();
	var barang_id = $('#barang_id_select_edit').val();
	var jumlah_roll_stok = $('#stok-info-edit').find('.stok-roll').html();
	var data = $("#form_edit_barang [name=data_barang] [value='"+barang_id+"']").text().split('??');
	var tipe_qty = data[2];
	   		

	$("#qty-table-edit [name=qty]").each(function(){
		var ini = $(this).closest('tr');
		var qty = $(this).val();
		var roll = ini.find('[name=jumlah_roll]').val();
		if (qty != '' && roll == '') {
			roll = 1;
		}else if(roll == 0){
			// alert('test');
			if (qty == '') {
				qty = 0;
			};
		}else if(qty == '' && roll == ''){
			roll = 0;
			qty = 0;
		}

		if (roll == 0) {
    		var subtotal = parseFloat(qty);
    		total_roll += 0;
		}else{
    		var subtotal = parseFloat(qty*roll);
    		// alert(qty+'*'+roll);
    		total_roll += parseInt(roll);
    		// console.log(subtotal);
		};

		if (qty != '' && roll != '') {
			rekap[idx] = qty+'??'+roll;
		};
		idx++; 
		subtotal = subtotal.toFixed(2)
		total += parseFloat(subtotal);

	});

	total = total.toFixed(2);
	total = parseFloat(total);

	// alert(total);
	console.log('cek',parseFloat(total) +'<='+ parseFloat(total_stok));
	if (total > 0 &&  parseFloat(total) <= parseFloat(total_stok) && total_roll <= jumlah_roll_stok) {
		$('.btn-brg-edit-save').attr('disabled',false);
	}else if(total > 0 &&  parseFloat(total) <= parseFloat(total_stok) && tipe_qty == 3){
		$('.btn-brg-edit-save').attr('disabled',false);
	}else{
		$('.btn-brg-edit-save').attr('disabled',true);
	}

	$('#portlet-config-qty-edit .jumlah_roll_total').html(total_roll);
	$('#portlet-config-qty-edit .yard_total').html(total.toFixed(2));

	$('#form-qty-update [name=rekap_qty]').val(rekap.join('--'));
	$('#form_edit_barang [name=rekap_qty]').val(rekap.join('--'));


}

function update_table(){
	subtotal = 0;
	$('.subtotal').each(function(){
		subtotal+= reset_number_format($(this).html());
	});

	$('.total').html(change_number_format(subtotal));
	var diskon = reset_number_format($('.diskon').val());
	var ongkir = reset_number_format($('.ongkos_kirim').val());
	var g_total = subtotal - parseInt(diskon) + parseInt(ongkir);
	$('.g_total').html(change_number_format(g_total));
	update_bayar();
}

function cekStok(ini, table){
	qtyListAmbil = [];
	let stat = true;
	$(`#${table} [name=qty]`).each(function(){
		let qty = $(this).val();
		let ini = $(this);
		if (qty != '') {
			if (qtyListDetail[`s-${parseFloat(qty).toString().replace('.','_')}`] > 0) {
				if (typeof qtyListAmbil[`s-${parseFloat(qty).toString().replace('.','_')}`] === 'undefined') {
					qtyListAmbil[`s-${parseFloat(qty).toString().replace('.','_')}`] = 0;
				};

				let jmlRoll= $(this).closest('tr').find('[name=jumlah_roll]').val();
				// console.log(qty, jmlRoll);
				if (jmlRoll == '' || typeof jmlRoll ==='undefined' || jmlRoll.length == 0 ) {
					jmlRoll = 1;
					$(this).closest('tr').find('[name=jumlah_roll]').val(jmlRoll);
				};
				qtyListAmbil[`s-${parseFloat(qty).toString().replace('.','_')}`]+= parseInt(jmlRoll);
				// console.log(`uus-${parseFloat(qty).toString().replace('.','_')}`,qtyListAmbil[`s-${parseFloat(qty).toString().replace('.','_')}`] +'<='+ qtyListDetail[`s-${parseFloat(qty).toString().replace('.','_')}`] );
				
				if (qtyListAmbil[`s-${parseFloat(qty).toString().replace('.','_')}`] <= qtyListDetail[`s-${parseFloat(qty).toString().replace('.','_')}`] ) {
					if (ini != '') {
		   				ini.css('color', 'green');
					}else{
						$(this).css('color','green');
					};
				}else{
					
					stat = false;
					alert('Jumlah roll tidak cukup');
	   				ini.val('');
	   				ini.css('color', 'red');
	   				ini.focus();
	   				return false;
				}
				
			};
		};
	});
	

	$('#qty-table-detail tr td').css('background','transparent');
	console.log('qtA',qtyListAmbil);
	for (var key in qtyListAmbil) {
		let q = qtyListAmbil[key];
		if (q > 0) {
			let idx = 0;
			if (key != 's-100') {
				$(`.${key}`).each(function(){
					// console.log('qB',q);
					// console.log(q+'>'+idx);
					if (q > idx) {
						// console.log('ya')
						$(this).css('background','yellow');
					}else{
						return false;
					};
					idx++;
				})	
			}else{
				$('.s-100').css('border','2px solid yellow');
			};
		};
	}


	if (stat) {
		return true;
	};
}

function setAlamat(tipe){
	$("#tipe_sj").val(tipe);
	$("#portlet-config-alamat").modal("toggle");
}

function print_surat_jalan(){
	let penjualan_id = "<?=$penjualan_id?>";
	let tipe_sj = $("#tipe_sj").val();
	let alamat_kirim_id = $("#alamat_kirim_id").val();
	let url=`<?=base_url()?>transaction/penjualan_print?penjualan_id=${penjualan_id}&type=sj&tipe_sj=${tipe_sj}&alamat_kirim_id=${alamat_kirim_id}`;
	window.open(url,'_blank').focus();
}

function printRAWPrint(tipe){
	let penjualan_id = "<?=$penjualan_id?>";
	let tipe_sj = $("#tipe_sj").val();
	let alamat_kirim_id = $("#alamat_kirim_id").val();

	tipe = tipe.toString();
	let url = 'transaction/penjualan_print?';
	if (tipe == '1' || tipe == '2' || tipe == '3') {
		url += `penjualan_id=${penjualan_id}&type=${tipe}`;
	}else if(tipe == 'sj'){
		url += `penjualan_id=${penjualan_id}&type=sj&tipe_sj=${tipe_sj}&alamat_kirim_id=${alamat_kirim_id}`;
	};

	// console.log('src',baseurl+url);

	$("#print-pdf-dynamic").attr('src',baseurl+url);
	console.log(url);
	$('#print-pdf-dynamic').load(function(){
        // print_faktur_frame('print-pdf-dynamic');
		print_faktur_frame('print-pdf-dynamic');
    });
}

function print_faktur_frame(print_frame){
	// alert(print_frame);
	// console.log(print_frame);
	var getMyFrame = document.getElementById(print_frame);
    getMyFrame.focus();
    getMyFrame.contentWindow.print();
	$('#overlay-div').hide();
}

</script>

<script>

$("#btn-sj").click(function(){
	$("#cetak-sj").css("display","block");
	$("#cetak-invoice-sj").css("display","none");
	$("#cetak-invoice-sj-no-harga").css("display","none");
})

$("#btn-invoice-sj").click(function(){
	$("#cetak-sj").css("display","none");
	$("#cetak-invoice-sj").css("display","block");
	$("#cetak-invoice-sj-no-harga").css("display","block");
});

function insertSJ() {
	let res = $.ajax({
		url: "<?= base_url('transaction/jual_kirim_insert') ?>",
		type:
		 "POST",
		data: $('#form_cetak_sj').serialize(),
		dataType: "JSON",
		success: function(data) {
			if (data.status && data.pengiriman_id != 0) {
				return true;
			}
		},
		error: function(jqXHR, textStatus, errorThrown) {
				alert('Error update data!');
		}
	});
	return res;
}
$('#cetak-sj').click(function() {
	insertSJ();
});

$('#cetak-invoice-sj').click(function() {
      $.ajax({
            url: "<?= base_url('transaction/jual_kirim_insert') ?>",
            type: "POST",
            data: $('#form_cetak_sj').serialize(),
            dataType: "JSON",
            success: function(data) {
                  if (data.status) {
						// console.log(data);
                        printInvoiceSJ(data.pengiriman_id);
                  }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                  alert('Error update data!');
            }
      });
});

$('#cetak-invoice-sj-no-harga').click(function() {
      $.ajax({
            url: "<?= base_url('transaction/jual_kirim_insert') ?>",
            type: "POST",
            data: $('#form_cetak_sj').serialize(),
            dataType: "JSON",
            success: function(data) {
                  if (data.status) {
                        printInvoiceSJNonHarga(data.pengiriman_id);
                  }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                  alert('Error update data!');
            }
      });
});

$('.btn-cetak-non').click(function() {
      $.ajax({
            url: "<?= base_url('transaction/jual_kirim_insert') ?>",
            type: "POST",
            data: $('#form_cetak_sj_non').serialize(),
            dataType: "JSON",
            success: function(data) {
                  if (data.status) {
                        printSJNon(data.pengiriman_id);
                  }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                  alert('Error update data!');
            }
      });
});

//direct print
function printInvoice(is_custom_print) {
    let penjualan_id = "<?= $penjualan_id ?>";
    let url = '<?= base_url('print/jual_invoice/index/'); ?>';
    <?if (is_posisi_id() <= 3) {?>
        if (is_custom_print==1) {
            url = '<?= base_url('print/jual_invoice_custom/index/'); ?>';
        }
    <?}?>
    url += `/${penjualan_id}`;

    $("#print-pdf-dynamic").attr('src', url);

    $('#print-pdf-dynamic').load(function() {
        print_faktur_frame('print-pdf-dynamic');
    });
}

function printInvoicePL(is_custom_print) {
    let penjualan_id = "<?= $penjualan_id ?>";
    let url = '<?= base_url('print/jual_invoice_pl/index/'); ?>';
    <?if (is_posisi_id() <= 3) {?>
        if (is_custom_print==1) {
            url = '<?= base_url('print/jual_invoice_pl_custom/index/'); ?>';
        }
    <?}?>
    url += `/${penjualan_id}`;

    $("#print-pdf-dynamic").attr('src', url);

    $('#print-pdf-dynamic').load(function() {
        print_faktur_frame('print-pdf-dynamic');
    });
}

function setPrintInvoiceSJ(is_custom_print){
    const form = document.querySelector("#form_cetak_sj");
    form.querySelector("#isCustomPrint").value=is_custom_print;
}

function printPL() {
      let penjualan_id = "<?= $penjualan_id ?>";
      let url = '<?= base_url('print/jual_pl/index/'); ?>';
      url += `/${penjualan_id}`;

      $("#print-pdf-dynamic").attr('src', url);

      $('#print-pdf-dynamic').load(function() {
            print_faktur_frame('print-pdf-dynamic');
      });
}

function printSJ(pengiriman_id) {
	let penjualan_id = "<?= $penjualan_id ?>";
	let url = '<?= base_url('print/jual_sj/index/'); ?>';
	url += `/${penjualan_id}`;
	url += `/${pengiriman_id}`;

	$("#print-pdf-dynamic").attr('src', url);

	$('#print-pdf-dynamic').load(function() {
		print_faktur_frame('print-pdf-dynamic');
	});
}

function printSJNon(pengiriman_id) {
      let penjualan_id = "<?= $penjualan_id ?>";
      let url = '<?= base_url('print/jual_sj_non/index/'); ?>';
      url += `/${penjualan_id}`;
      url += `/${pengiriman_id}`;

      $("#print-pdf-dynamic").attr('src', url);

      $('#print-pdf-dynamic').load(function() {
            print_faktur_frame('print-pdf-dynamic');
      });
}

function printInvoiceSJ(pengiriman_id) {
    let penjualan_id = "<?= $penjualan_id ?>";
    let url = '<?= base_url('print/jual_invoice_sj/index/'); ?>';
    const form = document.querySelector("#form_cetak_sj");
    const is_custom_print = form.querySelector("#isCustomPrint").value;
    <?if (is_posisi_id() <= 3) {?>
        if (is_custom_print==1) {
            url = '<?= base_url('print/jual_invoice_sj_custom/index/'); ?>';
        }
    <?}?>
    url += `/${penjualan_id}`;
	url += `/${pengiriman_id}`;

	console.log(url);
    $("#print-pdf-dynamic").attr('src', url);

    $('#print-pdf-dynamic').load(function() {
        print_faktur_frame('print-pdf-dynamic');
    });
}

function printInvoiceSJNonHarga(pengiriman_id) {
    let penjualan_id = "<?= $penjualan_id ?>";
    let url = '<?= base_url('print/jual_invoice_sj_non/index/'); ?>';
    const form = document.querySelector("#form_cetak_sj");
    const is_custom_print = form.querySelector("#isCustomPrint").value;
    <?if (is_posisi_id() <= 3) {?>
        if (is_custom_print==1) {
            url = '<?= base_url('print/jual_invoice_sj_non_custom/index/'); ?>';
        }
    <?}?>
    url += `/${penjualan_id}`;
	url += `/${pengiriman_id}`;

    $("#print-pdf-dynamic").attr('src', url);

    $('#print-pdf-dynamic').load(function() {
        print_faktur_frame('print-pdf-dynamic');
    });
}

// print preview
var myWindow;

function OpenMyWindow(url) {
      var width = 900;
      var height = 600;
      var left = parseInt((screen.availWidth / 2) - (width / 2));
      var top = parseInt((screen.availHeight / 2) - (height / 2));
      var windowFeatures = "width=" + width + ",height=" + height + ",status,resizable,left=" + left + ",top=" + top + "screenX=" + left + ",screenY=" + top;
      myWindow = window.open(url, "subWind", windowFeatures);
}

function ubahPPN(){
    $(".pin-special").show();
    setTimeout(() => {
        $(".pin_special").focus();
    }, 500);
}

function ubahPPNEdit(){
    $(".pin-special-edit").show();
    setTimeout(() => {
        $(".pin_special_edit").focus();
    }, 500);
}

function cekPPN(){
    var tgl = $("#tanggal_add").val();
    tgl = tgl.split("/").reverse().join("-");
    const tgl_1 = new Date(tgl);
    for (let i = 0; i < ppn_tgl.length; i++) {
        const tgl_2 = new Date(ppn_tgl[i]);
        if (tgl_1 >= tgl_2) {   
            //console.log(tgl_2," OKE ",tgl_1);
            //console.log(ppn_val[i]);
            const ppn_set = ppn_val[i];
            i = ppn_tgl.length;
            
            if (ppn_berlaku != ppn_set ) {
                if($('#fp_status_add').is(":checked")){
                    notific8("ruby", `PPN SET ke ${parseFloat(ppn_set)}%`);
                }
                ppn_berlaku = ppn_set;
                $("#ppn_list_add_text").val(ppn_berlaku);
                $("#ppn_list_add").val(ppn_berlaku);
            }
        }else{
            console.log(tgl_1," >= ",tgl_2);
        }
    }

}

function cekPPNEdit(){
    var tgl = $("#tanggal_edit").val();
    tgl = tgl.split("/").reverse().join("-");
    const tgl_1 = new Date(tgl);
    for (let i = 0; i < ppn_tgl.length; i++) {
        const tgl_2 = new Date(ppn_tgl[i]);
        if (tgl_1 >= tgl_2) {
            //console.log(tgl_2," OKE ",tgl_1);
            //console.log(ppn_val[i]);
            const ppn_set = ppn_val[i];
            i = ppn_tgl.length;
            
            if (ppn_edit_berlaku != ppn_set ) {
                if($('#fp_status_edit').is(":checked")){
                    notific8("ruby", `PPN SET ke ${parseFloat(ppn_set)}%`);
                }
                ppn_edit_berlaku = ppn_set;
                $("#ppn_list_edit_text").val(ppn_edit_berlaku);
                $("#ppn_list_edit").val(ppn_edit_berlaku);
            }
        }else{
            console.log(tgl_1," >= ",tgl_2);
        }
    }

}

function generateAmbilQty(){
	var total = 0; var idx = 0; var rekap = [];
	var total_roll = 0;
	var total_stok = $('#stok-info').find('.stok-qty').html();
	var jumlah_roll_stok = $('#stok-info').find('.stok-roll').html();
	var barang_id = $('#barang_id_select').val();
	var data = $("#form_add_barang [name=data_barang] [value='"+barang_id+"']").text().split('??');
	var tipe_qty = data[2];

	$("#qty-table [name=qty]").each(function(){
		var ini = $(this).closest('tr');
		var qty = $(this).val();
		var roll = ini.find('[name=jumlah_roll]').val();
		if (qty != '' && roll == '') {
			roll = 1;
		}else if(roll == 0){
			// alert('test');
			if (qty == '') {
				qty = 0;
			};
		}else if(qty == '' && roll == ''){
			roll = 0;
			qty = 0;
		};

		// alert(roll);

		if (roll == 0) {
			var subtotal = parseFloat(qty);
			total_roll += 0;
		}else{
			var subtotal = parseFloat(qty*roll);
			total_roll += parseInt(roll);
		};
		// alert(subtotal);
		if (qty != '' && roll != '') {
			rekap[idx] = qty+'??'+roll;
		};
		idx++; 
		// alert(total_roll);
		total += subtotal;
	});

	total = total.toFixed(2);
	total = parseFloat(total);

	if (total > 0 &&  total <= total_stok && total_roll <= jumlah_roll_stok) {
		$('.jumlah_roll_total').css('color','black');
		$('.yard_total').css('color','black');

		$('.btn-brg-save').attr('disabled',false);
	}else if(tipe_qty == 3){
		if (parseFloat(total) > 0 &&  parseFloat(total) <= parseFloat(total_stok) ) {
			console.log(total +'>'+ 0 +'&&'+  total +'<='+ total_stok +'&&'+ total_roll +'<='+ jumlah_roll_stok);
			$('.jumlah_roll_total').css('color','black');
			$('.yard_total').css('color','black');

			$('.btn-brg-save').attr('disabled',false);
		}else{
			$('.btn-brg-save').attr('disabled',true);
		}
	}else{
		if (total_roll > jumlah_roll_stok ) {
			$('.jumlah_roll_total').css('color','red');
			// alert('stok over : '+total_roll+' > '+jumlah_roll_stok);
		};
		if (total > total_stok) {
			$('.yard_total').css('color','red');
			// alert('stok over : '+total+' > '+total_stok);
		};
		$('.btn-brg-save').attr('disabled',true);
	}


	// alert(total);
	$('.yard_total').html(total.toFixed(2));
	$('.jumlah_roll_total').html(total_roll);
	$('[name=rekap_qty]').val(rekap.join('--'));
}

function getAllStock(){
	let baris = ``;
	
	const newList = Object.assign({},qtyListDetail);
	let idx = 0;
	for(item in newList){
		const qty = item.toString().replace("s-","").replace("_",".");
		const roll = newList[item];
		if (roll > 0) {
			if (idx == 0) {
				baris += `<tr>
							<td><input name='qty' class='input1' tabindex='1' value='${qty}'  autocomplete='off'></td>
							<td><input name='jumlah_roll'  tabindex='2' autocomplete='off' value='${roll}'></td>
							<td style='padding:0 10px'></td>
							<td><button tabindex='-1' class='btn btn-xs blue btn-add-qty-row'><i class='fa fa-plus'></i></button></td>
						</tr>`;
				
			}else{
				baris += `
				<tr>
					<td><input name='qty' tabindex='<?=2*($i+1) + 1;?>'  value='${qty}' autocomplete='off'></td>
					<td><input name='jumlah_roll'  tabindex='<?=2*($i+1) + 2;?>'  value='${roll}' autocomplete='off'></td>
					<td style='padding:0 10px'></td>
					<td></td>
				</tr>`;
			}
			idx++;
		}
	}

	for (let i=idx; i < (parseInt(idx)+8) ; i++) {
		baris += `
		<tr>
			<td><input name='qty' tabindex='${(2*(i+1) + 1)}' autocomplete='off'></td>
			<td><input name='jumlah_roll'  tabindex='${2*(i+1) + 2}' autocomplete='off'></td>
			<td style='padding:0 10px'></td>
			<td></td>
		</tr>`;
	}

	const table = document.querySelector("#qty-table tbody");
	table.innerHTML = baris;
	generateAmbilQty();
	
	const roll_cell = document.querySelector("#qty-table tbody tr");
	cekStok(ini, 'qty-table');

}

function cek_customer_kredit_permit(customer_id){
	if (customer_id == '') {
		customer_id = 0;
	}

	let isKredit = false;
	customer_harga_list.forEach(row=>{
		if (row.tipe == 2) {
			if (customer_id == row.customer_id) {
				isKredit = true;
			}
		}
	})

	return isKredit;
}

function webPrintHTML(tipe){
	let iframe;
    const penjualan_id = "<?= $penjualan_id ?>";
	iframe = document.querySelector("#frameprintHTML");
	iframe.setAttribute("sandbox", "allow-scripts allow-top-navigation allow-same-origin");
	if (tipe != 3 && tipe != 4) {
		iframe.src = baseurl+`transaction/penjualan_print_HTML?id=${penjualan_id}&print_type=${tipe}`;
	}else{
		if (insertSJ()) {
			const alamat_kirim_id = document.querySelector("#alamat_kirim_id").value;
			iframe.src = baseurl+`transaction/penjualan_print_HTML?id=${penjualan_id}&print_type=${tipe}&alamat_kirim_id=${alamat_kirim_id}`;
		}else{
			alert("error");
		}

		const alamat_kirim_id = document.querySelector("#alamat_kirim_id").value;
			iframe.src = baseurl+`transaction/penjualan_print_HTML?id=${penjualan_id}&print_type=${tipe}&alamat_kirim_id=${alamat_kirim_id}`;
	}

	// doc.getElementById("printBtn").addEventListener('click',function() {
	// 	//doStuffHere
	// 	alert("click");
	// });
}

function setBarangPO(tipe){
	let sel;
	let barangInput;
	let warnaInput;
	let hargaInput;
	
	
	if (tipe==1) {
		sel = document.querySelector('#barangPOSelect');
		barangInput = document.querySelector("#inputBarangPO");
		warnaInput = document.querySelector("#inputWarnaPO");
		hargaInput = document.querySelector("#harga_jual_add");
		satuanInput = document.querySelector("#satuan_add");
		namaTercetak = document.querySelector("#nama_jual_tercetak");
	}else{
		sel = document.querySelector('#barangPOSelectEdit');
		barangInput = document.querySelector("#inputBarangPOEdit");
		warnaInput = document.querySelector("#inputWarnaPOEdit");
		hargaInput = document.querySelector("#harga_jual_edit");
		satuanInput = document.querySelector("#satuan_edit");
		namaTercetak = document.querySelector("#nama_jual_tercetak_edit");
	}

	const dt = barang_po;
	const data_brg = dt.filter(item=>{
		if (item.id == sel.value) {
			return item;
		}
	});
	console.log(data_brg)
	const barang = data_brg[0];
	
	const barang_id = barang.barang_id;
	const warna_id = barang.warna_id;
	const harga = barang.harga_jual;
	const satuan = barang.nama_satuan;
	const nama_jual = barang.nama_barang+' '+barang.nama_warna;

	// barangInput.value=barang_id;
	// warnaInput.value=warna_id;

	$('#barang_id_select').val(barang_id);
	$('#warna_id_select').val(warna_id);
	satuanInput.value=satuan;
	if (tipe==1) {
		// namaTercetak.value=nama_jual;
	}
	hargaInput.value=harga.replace(".",",");
	
	
}

function closeParentPrintModal(){
	$('#portlet-config-printHTML').modal("hide");
}

function togglePOInfo(){
	$("#data-PO").toggle("fast");
}

</script>
