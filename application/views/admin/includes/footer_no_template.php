
<style type="text/css">
.notifikasi-akunting{
	position: fixed;
	right: 0;
	top: 126px;
	padding: 20px;
	background: orange;
}

.notifikasi-akunting hr{
	padding: 2px 0 ;
	margin: 2px 0;
}

</style>

<?if (is_maintenance_on()) {?>
	<div style='width:200px; position:fixed; top:20%; left:0px; background:red; color:white; padding:20px; opacity:0.7; ';>
		JANGAN LUPA OFF MAINTENANCE
	</div>
<?}?>

<!-- END CONTAINER -->
<!-- BEGIN FOOTER -->

<!-- END FOOTER -->
<!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
<!-- BEGIN CORE PLUGINS -->
<!--[if lt IE 9]>
<script src="../assets/global/plugins/respond.min.js"></script>
<script src="../assets/global/plugins/excanvas.min.js"></script> 
<![endif]-->
</body>
<!-- END BODY -->
</html>
<script src="<?php echo base_url('assets/global/plugins/select2/select2.min.js'); ?>" type="text/javascript" ></script>
<script src="<?php echo base_url('assets_noondev/js/page_common.js'); ?>"></script>
<script>
	$(document).ready(function() {
	   	// $('.tanggal_target')

	   	$(".btn-hide-warn-invoice").click(function(){
	   		$('#invoice-warn-wrap').animate({
            	'right':'-200px'
	   		},'slow');
	   	});

	    $('.btn-note-order').click(function(){
	    	var form = "#form_add_note_order";
	    	var currentdate = new Date(); 
	    	var date = two_digit(currentdate.getDate());
	    	var month = two_digit((currentdate.getMonth()+1));
	    	var hour = two_digit(currentdate.getHours());
	    	var min = two_digit(currentdate.getMinutes());
	    	// alert(currentdate.getDate());

		    var datetime = date + "/"
                + month  + "/" 
                + currentdate.getFullYear() + " - "  
                + hour + ":"  
                + min;

            $(form+" [name=tanggal_note_order]").val(datetime);

			$("#detail-on-order").show();

	    });

	    $('.btn-send-notif').click(function(){
	    	$('#form_add_notif').submit();
	    });

	    $("#barang_id_select_global, #barang_id_select_global_2, #warna_id_select_global, #customer_id_select_global, #customer_id_select_global_2, #customer_id_select_global_3").select2();
	    $("#form_add_note_order_detail [name=tipe_barang]").change(function(){
	    	var tipe_barang = $(this).val();
	    	if (tipe_barang == 1) {
	    		$('#barang_terdaftar').show();
	    		$('#barang_tidak_terdaftar').hide();
	    	}else if (tipe_barang == 2) {
	    		$('#barang_terdaftar').hide();
	    		$('#barang_tidak_terdaftar').show();
	    	};
	    });

	    $('.btn-note-order-save').click(function(){
	    	var form = "#form_add_note_order";
	    	$(form).submit();
	    	// $('#form_add_note_order').submit();
	    });

	    $('.btn-note-order-detail-save').click(function(){
	    	var form = "#form_add_note_order_detail";
	    	var tipe_barang = $(form+" [name=tipe_barang]:checked").val(); 
	    	var barang_id = $(form+" [name=barang_id]").val();
	    	var nama_barang = $(form+" [name=nama_barang]").val();
	    	var warna_id = $(form+" [name=warna_id]").val();
	    	var qty = $(form+" [name=qty]").val();
	    	var check = 0;

	    	$(form).submit();

	    	// $('#form_add_note_order').submit();
	    });

	    $('.note-type-customer').change(function(){
	    	// alert($(this).val());
	    	var tipe_customer = $(this).val();
	    	if (tipe_customer == 1) {
	    		$('.note-customer').show();
	    		$('.note-non-customer').hide();
	    	}else{
	    		$('.note-customer').hide();
	    		$('.note-non-customer').show();
	    	};
	    });

	    $('#form_add_note_order_detail [name=warna_id]').change(function(){
	    	if ($(this).val() == -1) {
	    		$('#warna_tidak_terdaftar').show();
	    	}else{
	    		$('#warna_tidak_terdaftar').hide();
	    	}
	    });

	    $('#reminder_table').on('click', '.btn-remove-reminder', function(){
	    	var data_st = {};
	    	var ini = $(this).closest('tr');
	    	data_st['reminder_id'] = ini.find('.reminder_id').html();
	    	var url = 'admin/reminder_remove';
	    	ajax_data_sync(url,data_st).done(function(data_respond  /*,textStatus, jqXHR*/ ){
	    		var reminder_count = $('#reminder_count').html();
	    		reminder_count--;
				ini.remove();
				$('#reminder_count').html(reminder_count);
	   		});
	    });

	    $('.btn-cari-harga').click(function(){
	    	var form = $('#form_cek_harga_barang');
	    	var data_st = {};
	    	data_st['barang_id'] = form.find('[name=barang_id]').val();
	    	data_st['customer_id'] = form.find('[name=customer_id]').val();
	    	data_st['limit'] = form.find('[name=limit]').val();
	    	var url = 'admin/cek_harga_barang';
	    	ajax_data_sync(url,data_st).done(function(data_respond  /*,textStatus, jqXHR*/ ){
	    		var data_show = "";
	    		$.each(JSON.parse(data_respond) , function(i,v){
	    			data_show += "<tr>";
	    			data_show += "<td>"+v.tanggal+"</td>";
	    			data_show += "<td>"+change_number_format(v.harga_jual)+"</td>";
	    			data_show += "</tr>";
	    		});

	    		$('#hasil_cek_harga_barang tbody').html(data_show);
	   		});
	    });

	    $('.btn-dismiss-notif').click(function(){
	    	var ini = $(this);
	    	var data_st = {};
	    	data_st['notifikasi_akunting_id'] = ini.closest('tr').find('.notifikasi_akunting_id').html();
	    	var url = 'admin/dismiss_notifikasi_akunting';
	    	ajax_data_sync(url,data_st).done(function(data_respond  /*,textStatus, jqXHR*/ ){
	    		if(data_respond == 'OK'){
	    			ini.closest('div').remove();
			    	if($('.notifikasi-akunting div').length == 0){
			    		$('.notifikasi-akunting').toggle('slow');
			    	};
	    		}
	   		});
	    });

	    //=================================================================================

	    $('.dropdown-quick-sidebar-toggler a').click(function () {
            $('.page-quick-sidebar-wrapper').animate({
            	'right':'0px'
            }, 'slow'); 
        });

        $('.page-quick-sidebar-back-to-list').click(function () {
            $('.page-quick-sidebar-wrapper').animate({
            	'right':'-270px'
            }, 'slow'); 
        });
	});

	function two_digit(number){
		var pjg = number.toString().length;
		// alert(pjg);
		if (pjg == 1) {
			number = '0'+number;
		};
		return number;
	}

</script>

