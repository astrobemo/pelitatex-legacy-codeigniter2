<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css'); ?>"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css'); ?>"/>
<?=link_tag('assets/global/plugins/bootstrap-datepicker/css/datepicker3.css'); ?>
<link href="<?=base_url('assets/global/plugins/bootstrap-modal/css/bootstrap-modal.css');?>" rel="stylesheet" type="text/css"/>

<?=link_tag('assets/global/plugins/select2/select2.css'); ?>

<style type="text/css">
#general_table tr th{
	vertical-align: middle;
	/*text-align: center;*/
}

#general_table tr td{
	color:#000;
}

.batal{
	background: #ccc;
}
</style>

<div class="page-content">
	<div class='container'>
		
		<div class="row margin-top-10">
			<div class="col-md-12">
				<div class="portlet light">
					<div class="portlet-title">
						<div class="caption caption-md">
							<i class="icon-bar-chart theme-font hide"></i>
							<span class="caption-subject theme-font bold uppercase"><?=$breadcrumb_small;?></span>
						</div>
						<div class="actions">
							<!-- <select class='btn btn-sm btn-default' name='status_select' id='status_select'>
								<option value="1" selected>Aktif</option>
								<option value="2">Batal</option>

							</select> -->
						</div>
					</div>
					<div class="portlet-body">
						<table>
							<tr>
								<td>Filter</td>
								<td class='padding-rl-5'> : </td>
								<td>
									<select name='status_aktif' id="status_select">
										<option <?=($status_aktif==1 ? 'selected' : '');?> value='1'>Aktif</option>
										<option <?=($status_aktif==-1 ? 'selected' : '');?> value='-1'>Batal</option>
										<option <?=($status_aktif==2 ? 'selected' : '');?> value='2'>Kosong/Belum Register</option>
									</select>
								</td>
							</tr>
						</table>
						<hr>
						<!-- table-striped table-bordered  -->
						<table class="table table-hover table-bordered" id="general_table">
							<thead>
								<tr>
									<th scope="col" class='status_column'>
										Status Aktif
									</th>
									<th scope="col">
										No Faktur
									</th>
									<th scope="col" class='status_column'>
										No Faktur Lengkap
									</th>
									<th scope="col">
										Tanggal
									</th>
									<th scope="col">
										Tipe
									</th>
									<th scope="col">
										Total
									</th>
									<th scope="col" class='status_column'>
										Diskon
									</th>
									<th scope="col" class='status_column'>
										Ongkir
									</th>
									<th scope="col">
										Nama Customer
									</th>
									<th scope="col" class='text-center'>
										Keterangan
									</th>
									<th scope="col">
										Actions
									</th>
									<th scope="col" class='status_column'>
										Status 
									</th>
									<th scope="col" class="<?=(is_posisi_id() != 1 ? 'status_column' : '');?>">
										Count Item 
									</th>
								</tr>
							</thead>
							<tbody>
							</tbody>
						</table>
						<!-- <button class='btn blue btn-test'>test</button> -->
					</div>
				</div>
			</div>
		</div>
	</div>			
</div>

<script src="<?php echo base_url('assets/global/plugins/bootstrap-modal/js/bootstrap-modalmanager.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/bootstrap-modal/js/bootstrap-modal.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/jquery-validation/js/jquery.validate.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/bootbox/bootbox.min.js'); ?>" type="text/javascript"></script>

<script src="<?php echo base_url('assets/global/plugins/datatables/media/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets_noondev/js/table-advanced.js'); ?>"></script>
<script src="<?php echo base_url('assets_noondev/js/fnReload.js');?>"></script>


<script>
jQuery(document).ready(function() {
	// Metronic.init(); // init metronic core components
	// Layout.init(); // init current layout
	// TableAdvanced.init();

	// oTable = $('#general_table').dataTable();
	// oTable.state.clear();
	// oTable.destroy();

	var oTable =  $("#general_table").dataTable({
   		"fnCreatedRow": function( nRow, aData, iDataIndex ) {
            
            var status_aktif = $('td:eq(0)', nRow).text();
            var tgl = $('td:eq(3)', nRow).text();
            var tanggal = date_formatter($('td:eq(3)', nRow).text());
            var tahun = $('td:eq(2)', nRow).text().substring(0,4);
            var total = $('td:eq(5)', nRow).text();
            var status = $('td:eq(11)', nRow).text();
            if (true) {};
            var faktur_only = $('td:eq(1)', nRow).text().split('-');
            

            var ket = parseInt($('td:eq(9)', nRow).text());
            if (status == -1) {
            	var stat = "<span style='color:#000'>batal</span>";
            }else if (ket >= 0) {
            	var stat = "<span style='color:green'>lunas</span>";
            }else{
            	var stat = "<span style='color:red'>belum lunas</span>";
            }
            var data = $('td:eq(10)', nRow).text().split('??'); 
            var id = data[0];
            var no_faktur = data[1];
            var count_item = $('td:eq(12)', nRow).text();
            var no_faktur_lengkap = $('td:eq(2)', nRow).text();

            var url_print = "<?=base_url();?>transaction/penjualan_print?penjualan_id="+id;
            var url_print_detail = "<?=base_url();?>transaction/penjualan_detail_print?penjualan_id="+id;
            var url_print_kombinasi = "<?=base_url();?>transaction/penjualan_print_kombinasi?penjualan_id="+id;

            var span_id = "<span class='id' hidden>"+id+"</span>";
            var link_detail = "<?=base_url().rtrim(base64_encode('transaction/penjualan_list_detail'),'=').'/?id=';?>" + id;
            var btn_view = "<a href='"+link_detail+"' class='btn btn-xs yellow-gold'><i class='fa fa-search'></i></a>";
            //var button_print = '<a href="'+url_print+'" class="btn-xs btn blue btn-print" onclick="window.open(this.href, \'newwindow\', \'width=1250, height=650\'); return false;"><i class="fa fa-print"></i> </a>';
            //var button_print_detail = '<a href="'+url_print_detail+'" class="btn-xs btn blue-steel btn-print" onclick="window.open(this.href, \'newwindow\', \'width=1250, height=650\'); return false;"><i class="fa fa-print"></i><i class="fa fa-list"></i> </a>';
            //var button_print_kombinasi = '<a href="'+url_print_kombinasi+'" class="btn-xs btn purple btn-print" onclick="window.open(this.href, \'newwindow\', \'width=1250, height=650\'); return false;"><i class="fa fa-print"></i><i class="fa fa-file-text-o"></i> </a>';
            
            var button_print = '';
            var button_print_detail = '';
            var button_print_kombinasi = '';
            var btn_batal = '';
            var btn_retur = '';

            var btn_batal = '';
            <?if (is_posisi_id() == 1 ) { ?>
            	if (status_aktif != -1) {
		            btn_batal = "<button class='btn btn-xs red btn-batal'><i class='fa fa-times'></i></button>";            		
            	}else{
		            btn_batal = "<button class='btn btn-xs blue btn-activate'><i class='fa fa-play'></i></button>";
            	}

            	if (status_aktif != -1 && no_faktur_lengkap != '') {
            		btn_retur = "<button class='btn btn-xs red btn-retur'><i class='fa fa-reply'></i></button>";
            	};
            <?};?>

            $('td:eq(0)', nRow).addClass('status_column');
            $('td:eq(1)', nRow).addClass('status_column');
            $('td:eq(6)', nRow).addClass('status_column');
            $('td:eq(7)', nRow).addClass('status_column');
            $('td:eq(10)', nRow).addClass('status_column');
            <?if (is_posisi_id() != 1) {?>
	            $('td:eq(10)', nRow).addClass('status_column');
            <?}?>

            $('td:eq(2)', nRow).html("<span class='no_faktur'>"+$('td:eq(2)', nRow).text() + "</span>" );
            $('td:eq(2)', nRow).attr('data-order',tahun+no_faktur);
            $('td:eq(3)', nRow).html("<span hidden>"+tgl+"</span>"+tanggal);
            var total = $('td:eq(5)', nRow).text();
            if (total != 0 && total != '') {
            	total = change_number_format2($('td:eq(5)', nRow).text());
            };
            $('td:eq(5)', nRow).html(total);
            $('td:eq(6)', nRow).html(change_number_format($('td:eq(6)', nRow).text()));
            $('td:eq(9)', nRow).html(stat);
            $('td:eq(10)', nRow).addClass('text-center');
            $('td:eq(11)', nRow).html(span_id+btn_view+btn_batal+btn_retur+button_print+button_print_detail+button_print_kombinasi);
            // $('td:eq(11)', nRow).addClass('status_column');

            if (status == '-1') {
            	$(nRow).addClass('batal');
            }else if (total == '' || total == 0) {
            	if (count_item == 0) {
	            	$(nRow).addClass('caution');
            	};
            }else if (total != '' && no_faktur_lengkap == '') {
            	$(nRow).addClass('warning');
            };

        },
		"lengthMenu": [100, 50, 25, "All"],
        "bStateSave" :true,
		"bProcessing": true,
		"bServerSide": true,
		"order":[[1, 'desc']],
		"sAjaxSource": baseurl + "transaction/data_penjualan?status_aktif=<?=$status_aktif;?>"
	});

	// var oTable;
 	// oTable = $('#general_table').dataTable();
    
	$('#status_select').change(function(){
		let status_aktif = $(this).val();
		// alert(status_aktif);
		oTable.fnReloadAjax(baseurl + "transaction/data_penjualan?status_aktif="+status_aktif);
		// alert('test');
	});

	$('.supplier-input, .gudang-input').click(function(){
		$('#form_add_data .supplier-input').removeClass('supplier-input');
	})

   	$('.btn-edit-save').click(function(){
   		if( $('#form_edit_data [name=nama]').val() != ''){
   			$('#form_edit_data').submit();
   		}
   	});

   	$('#form-add-list [name=barang_id]').change(function(){
   		var barang_id = $('#form-add-list [name=barang_id]').val();
   		var harga = $("#form-add-list [name=harga] [value='"+barang_id+"']").text();
   		// alert(harga);
		$('#form-add-list [name=harga_beli]').val(change_number_format(harga));
   	});

   	$('#form-add-list [name=qty], #form-add-list [name=harga_beli]').change(function(){
   		var harga = $('#form-add-list [name=harga_beli]').val();
   		var qty = $('#form-add-list [name=qty]').val();

   		if (harga == '' || $.isNumeric(harga) == false) {
   			harga = 0;
   			$('#form-add-list [name=harga_beli]').val(harga);
   		};

   		if ($.isNumeric(qty) == false) {qty = 0;};
   		var total = reset_number_format(harga) * qty;
   		if (total > 0) {total = change_number_format(total);};
   		$('#form-add-list .total').html(total);

   	});


	$('#rekap_barang_list').on('click','.btn-remove-list',function(){
		$(this).closest('tr').remove();
	});

	$('#rekap_barang_list').on('change','[name="qty[]"], [name="harga_beli[]"]',function(){
		var qty = $('#rekap_barang_list [name="qty[]"]').val();
		var harga = reset_number_format($('#rekap_barang_list [name="harga_beli[]"]').val());

		// alert(qty);
		var total = qty * harga;
		total = change_number_format(total);
		$('#rekap_barang_list .total').html(total);

		var subtotal = 0;
		$('#rekap_barang_list .total').each(function(){
			subtotal += reset_number_format($(this).html());
		});

		$('#rekap_harga .subtotal').html(change_number_format(subtotal));
		var diskon = reset_number_format($('#rekap_harga .diskon').val());
		var grand_total = parseInt(subtotal) - parseInt(diskon);
		$('#rekap_harga .grand_total').html(change_number_format(grand_total));

	});

	$('#rekap_harga [name=diskon]').change(function(){
		var diskon = reset_number_format($(this).val());
		var subtotal = reset_number_format($('#rekap_harga .subtotal').html());
		var grand_total = subtotal - diskon;
		$('#rekap_harga .grand_total').html(change_number_format(grand_total));
	});

	<?if (is_posisi_id() < 3) {?>
		$('#general_table').on('click','.btn-batal',function(){
			var faktur = $(this).closest('tr').find('.no_faktur').html();
			var id = $(this).closest('tr').find('.id').html();
			bootbox.confirm("Yakin <b style='color:red'>MEMBATALKAN</b> penjualan "+faktur+" ? ", function(respond){
				if (respond) {
					window.location.replace(baseurl+'transaction/penjualan_list_batal?id='+id);
				};
			});
		});

		$('#general_table').on('click','.btn-activate',function(){
			var faktur = $(this).closest('tr').find('.no_faktur').html();
			var id = $(this).closest('tr').find('.id').html();
			bootbox.confirm("Yakin <b style='color:blue'>MENGAKTIVASI</b> penjualan ini ? ", function(respond){
				if (respond) {
					window.location.replace(baseurl+'transaction/penjualan_list_undo_batal?id='+id);
				};
			});
		});


	<?};?>

	$('#general_table').on('click','.btn-retur',function(){
		var faktur = $(this).closest('tr').find('.no_faktur').html();
		var id = $(this).closest('tr').find('.id').html();
		bootbox.confirm("Yakin membuat <b style='color:red'>NOTA RETUR</b> berdasarkan penjualan "+faktur+" ? ", function(respond){
			if (respond) {
				window.location.replace(baseurl+'transaction/penjualan_list_retur?id='+id);
			};
		});
	});

	// $('.btn-save').click(function(){
	// 	var data = {};
	// 	data['no_faktur'] = $('#form_add_data [name=no_faktur]').val();
	// 	data['tanggal'] = $('#form_add_data [name=no_faktur]').val();
	// 	$.each( data, function( key, value ) {
	// 	  if (value == '') {
	// 	  	notific8('ruby', 'Mohon isi data '+key);
	// 	  }else{
	// 	  	if ($('.no_faktur_status').html() == 'true') {
	// 	  		$('#form_add_data').submit();
	// 	  	}else{
	// 	  		notific8('ruby', 'No Faktur invalid.');
	// 	  	};
	// 	  };
	// 	});
	// });

	

});
</script>
