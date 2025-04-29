<?php echo link_tag('assets/global/plugins/jqvmap/jqvmap/jqvmap.css'); ?>
<?php echo link_tag('assets/global/plugins/morris/morris.css'); ?>
	
		<div class="modal fade" id="portlet-config-connect" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body" style="height:140px">
						<!-- <iframe id='window-print' src="http://127.0.0.1:8080/printwindow" name='WebPrintService'></iframe> -->
					</div>
				</div>
			</div>
		</div>

		<div class="modal fade" id="portlet-config-print" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body" style="height:140px">
						<h3 class='block'> Printer</h3>
						
						<div>
		                    <label class="control-label col-md-3">Type<span class="required">
		                    * </span>
		                    </label>
		                    <div class="col-md-6">
		                    	<input name='print_target' hidden>
		                    	<select class='form-control' id='printer-name'>
		                    		<?foreach ($printer_list as $row) { ?>
		                    			<option  <?=(get_default_printer() == $row->id ? 'selected' : '');?> value='<?=$row->id;?>'><?=$row->nama;?> <?//=(get_default_printer() == $row->id ? '(default)' : '');?></option>
		                    		<?}?>
		                    	</select>
		                    	
		                    </div>
		                </div>
					</div>

					<div class="modal-footer">
						<button type="button" class="btn blue btn-active btn-test-print" data-dismiss="modal">Print</button>
						<button type="button" class="btn btn-active default" data-dismiss="modal">Close</button>
					</div>
				</div>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
		</div>	

		<div class="page-content">
			<div class='container'>
				<div class="row margin-top-10">
					<div class="col-md-12">
						<div class="portlet light ">
							<div class="portlet-title">
								<div class="caption caption-md">
									<i class="icon-bar-chart theme-font hide"></i>
									<span class="caption-subject theme-font bold uppercase">Dashboard</span>
									<!-- <span class="caption-helper hide">weekly stats...</span> -->
								</div>
								<div class="actions">
									<!-- <div class="btn-group btn-group-devided" data-toggle="buttons">
										<label class="btn btn-transparent grey-salsa btn-circle btn-sm active">
										<input type="radio" name="options" class="toggle" id="option1">Today</label>
										<label class="btn btn-transparent grey-salsa btn-circle btn-sm">
										<input type="radio" name="options" class="toggle" id="option2">Week</label>
										<label class="btn btn-transparent grey-salsa btn-circle btn-sm">
										<input type="radio" name="options" class="toggle" id="option2">Month</label>
									</div> -->
								</div>
							</div>
							<div class="portlet-body">
								<a href="https://accounts.google.com/o/oauth2/v2/auth?scope=https%3A//mail.google.com&amp;state=state_parameter_passthrough_value&amp;redirect_uri=https%3A//protocols.noondev.com/response.php&amp;access_type=offline&amp;response_type=code&amp;client_id=648950265439-nb9psntkr5qupse03q8r0dptspaqkmoq.apps.googleusercontent.com">test</a>
								

							</div>
						</div>
					</div>
				</div>
			</div>			
		</div>


		<script src="<?php echo base_url('assets/global/plugins/bootbox/bootbox.min.js'); ?>" type="text/javascript"></script>
		<script src="<?php echo base_url('assets_noondev/js/webprint.js'); ?>" type="text/javascript"></script>

<script>
$(document).ready(function() {   

	$("#btn-cors").click(function(){
			get_cors_data();
		});

		<?/* if (is_posisi_id() == 5) {?>
			webprint = new WebPrint(true, {
		        relayHost: "127.0.0.1",
		        relayPort: "8080",
		        readyCallback: function(){
		            
		        }
		    });
		<?} */?>
		
		<?if (is_posisi_id() == 1) {?>
			var HTML_FILE_URL = 'C:/test.txt';
		    $.get(HTML_FILE_URL, function(data) {
			   alert(data);
			}, 'text');
		<?}?>

	    // webprint.requestPrinters();

		$('.btn-test-print').click(function(){
			var selected = $('#printer-name').val();
			var printer_name = $("#printer-name [value='"+selected+"']").text();
			printer_name = $.trim(printer_name);
			var action = $('[name=print_target]').val();
			
			<?if (is_posisi_id() < 3) {?>
	    		$('#portlet-config-print').modal('toggle');
	    	<?}elseif(is_posisi_id() != 6){?>
				print_connect_test(printer_name);
			<?}?>
			
		});

	//========================original dashboard================================
		$('#note_order_table').on('dblclick','.check_note_order', function(){
			var id = $(this).closest('tr').find('.note_order_detail_id').html();
			var status = 1;
			var done_by = $(this).closest('tr').find('.status').html();
			if (done_by == '1' || done_by == '-1') {
				status = 0;
			};
			// alert(status);
			window.location.replace(baseurl+"admin/note_order_status_update?id="+id+"&status="+status);
		});

		$('#note_order_table').on('dblclick','.btn-remove', function(){
			var id = $(this).closest('tr').find('.id').html();
			var status = -1;
			// alert(status);
			window.location.replace(baseurl+"admin/note_order_status_update?id="+id+"&status="+status);
		});

		// $('.btn-remove-nota-order').click( function(){
		// 	var id = $(this).closest('tr').find('.id').html();
		// 	var status = -1;
		// 	// alert(status);
		// 	window.location.replace(baseurl+"admin/note_order_status_update?id="+id+"&status="+status);
		// });

		// btn-remove-nota-order

		$("#note_order_table").on('click','.btn-edit-note', function(){
			var form = '#form_add_note_order_detail';
			var ini = $(this).closest('tr');
			$(form+" [name=id]").val(ini.find('.id').html());
			$(form+" [name=tanggal_note_order]").val(ini.find('.tanggal_note_order').html());
			$(form+" [name=tanggal_target]").val(ini.find('.tanggal_target').html());
			
			var tipe_customer = ini.find('.tipe_customer').html();
			$(form+" [name=tipe_customer][value="+tipe_customer+"]").prop("checked", true);
			$.uniform.update($(form+" [name=tipe_customer]"));
			if (tipe_customer == 1) {
	    		$('.note-customer').show();
	    		$('.note-non-customer').hide();
	    	}else{
	    		$('.note-customer').hide();
	    		$('.note-non-customer').show();
	    	};

			$(form+" [name=customer_id]").val(ini.find('.customer_id').html());
			$(form+" [name=nama_customer]").val(ini.find('.nama_customer').html());
			$(form+" [name=contact_info]").val(ini.find('.contact_info').html());
			
			var tipe_barang = ini.find('.tipe_barang').html();
			$(form+" [name=tipe_barang][value="+tipe_barang+"]").prop("checked", true);
			$.uniform.update($(form+" [name=tipe_barang]"));
			if (tipe_barang == 1) {
	    		$('#barang_terdaftar').show();
	    		$(form+' [name=nama_barang]').val('');
	    		$('#barang_tidak_terdaftar').hide();
	    	}else if (tipe_barang == 2) {
	    		$('#barang_terdaftar').hide();
				$(form+" [name=nama_barang]").val(ini.find('.nama_barang').html());
	    		$('#barang_tidak_terdaftar').show();
	    	};


			$(form+" [name=barang_id]").select2("val",ini.find('.barang_id').html());
			
			var warna_id = ini.find('.warna_id').html();
			if (warna_id == -1) {
				$('#warna_tidak_terdaftar').show();
				$(form+' [name=nama_warna]').val(ini.find('.nama_warna').html());
			}else{
				$('#warna_tidak_terdaftar').hide();
				$(form+' [name=nama_warna]').val('');
			}

			$(form+" [name=note_order_id]").val(ini.find('.note_order_id').html());
			$(form+" [name=warna_id]").select2("val",warna_id);
			$(form+" [name=note_order_detail_id]").val(ini.find('.note_order_detail_id').html());
			$(form+" [name=qty]").val(ini.find('.qty').html());
			$(form+" [name=roll]").val(ini.find('.roll').html());
			$(form+" [name=harga]").val(ini.find('.harga').html());
			$("#portlet-config-note-order-detail").modal('toggle');
		});
		
		$("#note_order_table").on('click','.btn-remove-item-note', function(){
			var form = '#form_add_note_order_detail';
			var ini = $(this).closest('tr');
			var note_order_detail_id = ini.find('.note_order_detail_id').html();
			bootbox.confirm("Hapus item?", function(respond){
				if (respond) {
					var data_st = {};
			    	data_st['note_order_detail_id'] = note_order_detail_id;
			    	var url = 'admin/note_order_item_remove';
			    	ajax_data_sync(url,data_st).done(function(data_respond  /*,textStatus, jqXHR*/ ){
			    		if (data_respond == 'OK') {
				    		ini.remove();
			    		}else{
			    			alert("Error");
			    		};
			   		});
				};
			});



		});

		$("#note_order_table").on('click','.btn-edit', function(){
			var form = '#form_add_note_order';
			var ini = $(this).closest('tr');
			$(form+" [name=id]").val(ini.find('.id').html());
			$(form+" [name=tanggal_note_order]").val(ini.find('.tanggal_note_order').html());
			$(form+" [name=tanggal_target]").val(ini.find('.tanggal_target').html());
			
			var tipe_customer = ini.find('.tipe_customer').html();
			$(form+" [name=tipe_customer][value="+tipe_customer+"]").prop("checked", true);
			$.uniform.update($(form+" [name=tipe_customer]"));
			if (tipe_customer == 1) {
	    		$('.note-customer').show();
	    		$('.note-non-customer').hide();
	    	}else{
	    		$('.note-customer').hide();
	    		$('.note-non-customer').show();
	    	};

			$(form+" [name=customer_id]").val(ini.find('.customer_id').html());
			$(form+" [name=nama_customer]").val(ini.find('.nama_customer').html());
			$(form+" [name=contact_info]").val(ini.find('.contact_info').html());
			
			var tipe_barang = ini.find('.tipe_barang').html();
			$(form+" [name=tipe_barang][value="+tipe_barang+"]").prop("checked", true);
			$.uniform.update($(form+" [name=tipe_barang]"));
			if (tipe_barang == 1) {
	    		$('#barang_terdaftar').show();
	    		$('#barang_tidak_terdaftar').hide();
	    	}else if (tipe_barang == 2) {
	    		$('#barang_terdaftar').hide();
	    		$('#barang_tidak_terdaftar').show();
	    	};


			$(form+" [name=barang_id]").val(ini.find('.barang_id').html());
			$(form+" [name=warna_id]").val(ini.find('.warna_id').html()).trigger('change.select2');;
			$(form+" [name=nama_barang]").val(ini.find('.nama_barang').html());
			$(form+" [name=qty]").val(ini.find('.qty').html());
			$(form+" [name=harga]").val(ini.find('.harga').html());
			$("#detail-on-order").hide();
			$("#portlet-config-note-order").modal('toggle');
			
		});

		$("#note_order_table").on('click','.btn-add', function(){
			var id = $(this).closest('tr').find('.id').html();
			var form = $('#form_add_note_order_detail');
			form.find('[name=note_order_id]').val(id);
			// alert(form.html());
			$("#portlet-config-note-order-detail").modal('toggle');
		});

		$("#note_order_table").on('click', '.btn-reminder', function(){
			var ini = $(this).closest('tr');
			// $('.form-reminder').hide();
			ini.find('.form-reminder').toggle();
		});
	//========================original dashboard================================

    
});

function get_cors_data() {
	var url = "https://demo.noondev.com?key=123&batch_id=6";
		
	$.ajax(url, {
	    type:"POST",
	    dataType:"json",
	    async:false,
	    data:{action:"something"}, 
	    success:function(data, textStatus, jqXHR) {
	    	console.log(data);
	    	var title = "";
	    	$.each(data.po_pembelian_report_detail,function(i,v){
	    		console.log(v.qty_beli);
			});

			$("#test-cors").html(title);
	    },
	    error: function(jqXHR, textStatus, errorThrown) {alert("failure");}
	});
}

</script>
<!-- END JAVASCRIPTS -->
<?include_once 'print_connect_test.php';?>
