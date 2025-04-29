<link href="<?=base_url('assets/global/plugins/bootstrap-modal/css/bootstrap-modal.css');?>" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css'); ?>"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css'); ?>"/>

<!-- <link href="<?=base_url('assets_noondev/css/bootstrap-modal-bs3patch.css');?>" rel="stylesheet" type="text/css"/> -->
<?=link_tag('assets/global/plugins/bootstrap-datepicker/css/datepicker3.css'); ?>

<style type="text/css">

#form-get select{
	border:none; border-bottom:1px solid #ddd; width:150px;
}


#giro_table .add_item{
	background: #ffb6c1;
}


</style>

<div class="page-content">
	<div class='container'>
		<?
			$giro_setor_id = '';
			$keterangan = '';
			
			$tanggal_setor = date('d/m/Y');
			
			$g_total = 0;
			$readonly = '';
			$disabled = '';

			foreach ($giro_data as $row) {
				$giro_setor_id = $row->id;
				$keterangan = $row->keterangan;
				$tanggal_setor = is_reverse_date($row->tanggal);
			}

			if (is_posisi_id() == 6 ) {
				$readonly = 'readonly';
				$disabled = 'disabled';
			}
			// if ($status != 1 ) {
			// 	$readonly = 'readonly';
			// }

			// if ($penjualan_id == '') {
			// 	$disabled = 'disabled';
			// }
		?>
		<div class="modal fade " id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog ">
				<div class="modal-content">
					<div class="modal-body">
						<form action="<?=base_url('finance/giro_list_add')?>" class="form-horizontal" id="form_add_data" method="post">
							<h3 class='block'> <i class='fa fa-plus'></i> Add</h3>
							
			                <div class="form-group">
			                    <label class="control-label col-md-4">Tahun Terima<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<?$tahun = date('Y');?>
	                    			<select class='form-control' name='tahun-get'>
	                    				<?for ($i=2019; $i <=date('Y') ; $i++) { ?>
											<option value='<?=$i;?>' <?=($i == date('Y') ? 'selected' : '');?> ><?=$i;?></option>
	                    				<?}?>
									</select>
			                    </div>
			                </div>

							<div class="form-group">
			                    <label class="control-label col-md-4">Urutan<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<input name='giro_setor_id' value='<?=$giro_setor_id;?>' hidden>
			                    	<input name='giro_list_type' hidden>
			                    	<input name='giro_list_id' hidden>
			                    	<input class='form-control' name='urutan_giro'>
			                    </div>
			                </div>	

						</form>
					</div>

					<div class="modal-footer">
						<button type="button" class="btn blue btn-add-save">OK</button>
						<button type="button" class="btn btn-active default" data-dismiss="modal">Close</button>
					</div>
				</div>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
		</div>


		<div class="row margin-top-10">
			<div class="col-md-12">
				<div class="portlet light">
					<div class="portlet-body">
						<form action='' id='form-get' method='get'>
							<table id='tbl-form-get' width='50%'>
								<?if ($giro_setor_id == '') { ?>
									<tr hidden>
										<td>Tanggal: </td>
										<td class='padding-rl-5'> : </td>
										<td>
											<b>
												<input name='tanggal_start' class='date-picker' style='border:none; border-bottom:1px solid #ddd; width:100px; text-align:center' value='<?=$tanggal_start;?>'>
												s/d
												<input name='tanggal_end' class='date-picker ' style='border:none; border-bottom:1px solid #ddd; width:100px; text-align:center' value='<?=$tanggal_end;?>'> 
											</b>
										</td>
									</tr>
								<?}else{?>
									<tr hidden>
										<td>Tanggal: </td>
										<td class='padding-rl-5'> : </td>
										<td style='border-bottom:1px solid #ddd'>
											<b>
												<?=is_reverse_date($tanggal_start);?>
												s/d
												<?=is_reverse_date($tanggal_end);?>

											</b>
										</td>
									</tr>
								<?}?>
								<tr>
									<td>Toko </td>
									<td class='padding-rl-5'> : </td>
									<td>
										<b>
											<select <?if ($giro_setor_id != '') {?> disabled <?}?> name='toko_id' id="toko_id_select" style='width:100%;'>
												<option <?=($toko_id == 0 ? "selected" : "");?> value='0'>Pilih</option>
												<?foreach ($this->toko_list_aktif as $row) { ?>
													<option <?=($toko_id == $row->id ? "selected" : "");?> value='<?=$row->id;?>'><?=$row->nama;?></option>
												<?}?>
											</select>
										</b>
									</td>
								</tr>
							</table>
						</form>
						<?/*if ($giro_setor_id == '') {?>
							<button <?if ($toko_id == '' && $supplier_id == '') { ?> disabled <?}?> class='btn btn-xs default btn-form-get'><i class='fa fa-search'></i> Cari</button>
						<?}*/?>

						<!-- table-striped table-bordered  -->
						<hr/>
						<?if ($giro_setor_id != '') {?>
							<a href="#portlet-config" data-toggle='modal' class='btn btn-md blue btn-add'>+ GIRO</a>
						<?}?>

						<?//if ($giro_setor_id == '') { 
							$tahun = date('Y');?>
						FILTER : 
						<b><select name='tahun'>
							<?for ($i=2019; $i <=date('Y') ; $i++) { ?>
								<option value='<?=$i;?>' <?=($i == date('Y') ? 'selected' : '');?> ><?=$i;?></option>
            				<?}?>
						</select></b>
							<form method="post" action="<?=base_url()?>finance/giro_setor_insert" id='form-bayar'>
						<?//}?>
							<table class="table table-hover table-striped" id="general_table">
								<thead>
									<tr>
										<th scope="col" class='text-center'>
											Urutan
										</th>
										<th scope="col">
											Tanggal
										</th>
										<th scope="col">
											No Giro
										</th>
										<th scope="col">
											Nama Bank
										</th>
										<th scope="col">
											Jatuh Tempo
										</th>
										<th scope="col">
											Customer
										</th>
										<th scope="col">
											Nominal
										</th>
										<th scope="col" hidden>
											Tahun
										</th>
										<th scope="col" class='hidden-print'>
											Action
										</th>
									</tr>
								</thead>
								<tbody>
								<?
								$i =1; $total_nominal = 0;
								if ($giro_setor_id != '') {
									foreach ($giro_list_detail as $row) { ?>
									<tr>
										<td class='text-center'>
											<?if ($giro_setor_id != '' && $row->urutan_giro != '') {?>
												<span style='border:none; padding:5px 10px; border-bottom:1px solid #ccc; text-align:center; font-weight:bold; font-size:1.2em; background:#0ff '><?=$row->urutan_giro;?></span>
											<?}?>
										</td>
										<td>
											<?=is_reverse_date($row->tanggal_transfer);?>
										</td>
										<td>
											<?=$row->no_giro;?>
										</td>
										<td>
											<?=$row->nama_bank?>
										</td>
										<td>
											<?=is_reverse_date($row->jatuh_tempo);?>
										</td>
										<td>
											<?=$row->nama_customer;?>
										</td>
										<td>
											<?if ($giro_setor_id != '' && $row->amount != '') {
												$total_nominal += $row->amount;
												?>
												<span class='amount' hidden><?=$row->amount;?></span> <?=number_format($row->amount,'0',',','.');?>
												<?
											}?>
										</td>
										<td class='hidden-print'>
											<?if ($giro_setor_id != '') { ?>
												<span class='giro_setor_detail_id' hidden><?=$row->id;?></span>
											<?}else{?>
												<label><input type='checkbox' name='status_<?=$row->pembayaran_piutang_nilai_id?>' class='lunas-check' >
												pilih </label>
											<?}?>
											<input name='bayar_<?=$row->pembayaran_piutang_nilai_id;?>' hidden value="<?=$row->pembayaran_piutang_nilai_id;?>"> 
											<button type='button' tabindex='-1' class='btn btn-xs red btn-remove'><i class='fa fa-times'></i></button>
											<input name='urutan_giro[]' class='urutan-giro' hidden style='border:none; border-bottom:1px solid #ccc; width:50px;text-align:center; font-weight:bold; font-size:1.2em ' value=''>
											<input hidden name='giro_list_id[]' value="<?=$row->pembayaran_piutang_nilai_id;?>" class='giro-list-id' hidden>
											<input hidden name='giro_list_type[]' value="<?=$row->data_type;?>" class='giro-list-type' hidden>
											<input hidden name='detail_id[]' value="<?=$row->id;?>" class='giro-setor-detail-id' hidden>

										</td>
									</tr>
									<?
										$i++;
									}

									for ($j=0; $j < 2 ; $j++) { ?>
										<tr>
											<td class='text-center'>
												<input name='urutan_giro[]' class='urutan-giro' style='border:none; border-bottom:1px solid #ccc; width:50px;text-align:center; font-weight:bold; font-size:1.2em ' value=''>
												<input hidden name='giro_list_id[]' class='giro-list-id'>
												<input hidden name='giro_list_type[]' class='giro-list-type'>
												<input hidden name='detail_id[]' value="0" class='giro-setor-detail-id'>
											</td>
											<td class='tanggal-bayar'></td>
											<td class='no-giro'></td>
											<td class='nama-bank'></td>
											<td class='jatuh-tempo'></td>
											<td class='nama-customer'></td>
											<td class='tahun' hidden></td>
											<td class='amount'></td>
											<td class='action'></td>
										</tr>
									<?}

								
								}else{
									for ($i=0; $i < 10 ; $i++) { ?>
										<tr>
											<td class='text-center'>
												<input name='urutan_giro[]' class='urutan-giro' style='border:none; border-bottom:1px solid #ccc; width:50px;text-align:center; font-weight:bold; font-size:1.2em ' value=''>
												<input hidden name='giro_list_id[]' class='giro-list-id'>
												<input hidden name='giro_list_type[]' class='giro-list-type'>
												<input hidden name='detail_id[]' value="0" class='giro-setor-detail-id'>
											</td>
											<td class='tanggal-bayar'></td>
											<td class='no-giro'></td>
											<td class='nama-bank'></td>
											<td class='jatuh-tempo'></td>
											<td class='nama-customer'></td>
											<td class='tahun' hidden></td>
											<td class='amount'></td>
											<td class='action'></td>
										</tr>
									<?}
								} ?>
							</tbody>
							</table>
							<table class='table'>
								<tr style='font-size:1.2em; border-top:2px solid #ccc;border-bottom:2px solid #ccc; cursor:pointer' class='add_new_baris'>
									<td class='text-center'>
										<i class='fa fa-plus'></i> Baris
									</td>
								</tr>
								<tr style='font-size:1.2em; border-top:2px solid #ccc;border-bottom:2px solid #ccc;'>
									<td class='text-center'>TOTAL : <b><span class='total_setor'><?=number_format($total_nominal,'0',',','.');?></span></b></td>
								</tr>
							</table>
							<?/*if ($giro_setor_id != '') { ?>
								<form method="post" action="<?=base_url()?>finance/giro_setor_insert" id='form-bayar'>
							<?}*/?>
								<input name='toko_id' value="<?=$toko_id;?>" hidden>

								<input name='giro_setor_id' value="<?=$giro_setor_id;?>" hidden>

							<table>
								<tr>
									<td style='vertical-align:top;'>
										
										<?
										$idx = 1; $total_bayar_hutang = 0;?>
										<div class='list-group'>
											<?$title = '-';
											?>
											<div class='info-bayar-div' id='bayar-section'>
												<table class='bayar-info' width='100%;' style='margin-bottom:5px; font-size:1.4em'>
													<tr class='tanggal-transfer'>
														<td>Tanggal  Setor</td>
														<td class='padding-rl-5'> : </td>
														<td>
															<input name='tanggal' class='date-picker' value='<?=$tanggal_setor;?>'>
														</td>
													</tr>
													<tr>
														<td>Keterangan</td>
														<td class='padding-rl-5'> : </td>
														<td>
															<input name="keterangan" value="<?=$keterangan;?>">
														</td>
													</tr>
												</table>
												

											</div>
												
										</div>
									</td>
								</tr>
							</table>

						</form>
						

						<hr/>
						<div>
							<?if (is_posisi_id() != 6 ) { ?>
								
							<?}?>
							<button type='button' class='btn btn-lg green hidden-print btn-save-bayar'><i class='fa fa-save'></i> Simpan </button>
			                <a <?=$disabled;?> class='btn btn-lg blue btn-print hidden-print'><i class='fa fa-print'></i> Print </a>
						</div>
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
<script src="<?php echo base_url('assets/global/plugins/select2/select2.min.js'); ?>" type="text/javascript" ></script>
<script src="<?php echo base_url('assets/global/plugins/datatables/media/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js'); ?>" type="text/javascript"></script>


<script>
jQuery(document).ready(function() {

	// FormNewPenjualanDetail.init();

	$("#search_giro").select2();
	
	$('#giro_table').dataTable({
        "bStateSave" :true,
    });

   
	var form_group = {};
	var idx_gen = 0;

	<?if ($giro_setor_id == '') { ?>
		update_total_bayar();
	<?}?>

	$('.btn-reset-form, .btn-reset-bayar').click(function(e){
		e.preventDefault();
	});

	$('[data-toggle="popover"]').popover();

	$("#toko_id_select").change(function(){
		if ($(this).val() != 0 && $("#supplier_id_select").val() != 0) {
			$('.btn-form-get').prop("disabled",false);
		}else{
			$('.btn-form-get').prop("disabled",true);
		};
	});

	$("#supplier_id_select").change(function(){
		// alert($(this).val());
		if ($(this).val() != 0 && $("#toko_id_select").val() != 0) {
			$('.btn-form-get').prop('disabled',false);
		}else{
			$('.btn-form-get').prop('disabled',true);
		};
	});

	$('.btn-form-get').click(function(){
		var date_start = $('[name=tanggal_start]').val();
		var date = date_start.split('/');
		var start = date[1]+'/'+date[0]+'/'+date[2];

		var date_end = $('[name=tanggal_end]').val();
		var date = date_end.split('/');
		var end = date[1]+'/'+date[0]+'/'+date[2];;

		var start = new Date(start);
		var end = new Date(end);
		// alert(start);

		// end - start returns difference in milliseconds 
		var diff = new Date(end - start);

		// get days
		var days = diff/1000/60/60/24;
		// alert(days);
		if (days < 0) {
			alert("Tanggal awal lebih besar dari tanggal akhir ")
			$('[nama=tanggal_start]').val(date_end);
		}else{
			if (days >= 15) {
				bootbox.confirm("Memanggil data lebih dari 15 hari <br/> "+
					"( tergantung dari banyaknya data ) mungkin membuat halaman menjadi lama dimuat <br/> atau bahkan error. "+
					"Anda ingin melanjutkan ? ", function(respond){
						if (respond) {	
							$('#form-get').submit();				
						};
					});
			}else{
				$('#form-get').submit();				
			};
		}
	});

	/*$('#general_table').on('change','.lunas-check', function(){
		var ini = $(this).closest('tr');
		update_total_bayar();
	});*/

	$('#giro_table').on('change','.lunas-check', function(){
		var ini = $(this).closest('tr').html();
		$('#general_table tbody').append("<tr>"+ini+"</tr>");
		update_total_bayar();
	});

	$('#general_table').on('change','.bayar-hutang', function(){
		var ini = $(this).closest('tr');
		var hutang = reset_number_format(ini.find('.hutang').html());
		var bayar = reset_number_format($(this).val());
		var sisa_hutang = hutang - bayar;
		// alert(hutang+' - '+bayar);
		ini.find('.sisa-hutang').html(change_number_format(sisa_hutang));

		if (sisa_hutang != 0) {
			ini.find('.lunas-check').prop('checked',false);
		}else{
			ini.find('.lunas-check').prop('checked',true);
		};
		$.uniform.update('.lunas-check');


		<?if ($giro_setor_id != '') { ?>
			var data = {};
			data['id'] = ini.find('.pembayaran_hutang_detail_id').html();
			data['amount'] = reset_number_format(bayar);
			var url = 'finance/update_bayar_hutang_detail';
			ajax_data_sync(url,data).done(function(data_respond  /*,textStatus, jqXHR*/ ){
				// alert(data_respond);
				if (data_respond == 'OK') {
					
				}else{
					alert("error mohon refresh dengan menekan F5");
				}
	   		});
		<?};?>
		update_total_bayar();
	});

	$('#check_all').change(function(){
		if($(this).is(':checked')){
			$("#general_table .lunas-check").each(function(){
				$(this).prop('checked',true);
				var ini = $(this).closest('tr');
			});
		}else{
			$("#general_table .lunas-check").each(function(){
				$(this).prop('checked',false);
				var ini = $(this).closest('tr');
			});
		}
		$.uniform.update('.lunas-check');
		update_total_bayar();
	});
    
    $('.btn-save-bayar').click(function(){
    	// $('#form-bayar').submit();
    	var ini = $(this);
    	var bayar_id = $('[name=pembayaran_type_id]:checked').val();
    	var total_setor = reset_number_format($('.total_setor').html());
    	// alert(total_bayar);

    	$('#form-bayar').submit();    		
    	// if (total_setor != 0 && $('[name=tanggal]').val() != '') {
    	// }else{
    	// 	alert("Total Nota Tidak bisa 0/ Data Tidak lengkap");
    	// };
    });

    $('.btn-add-save').click(function(){
    	var form = $('#form_add_data');
    	var tahun = form.find('[name=tahun-get]').val();
    	var urutan_giro = form.find('[name=urutan_giro]').val();

    	var data = {};
		data['urutan_giro'] = urutan_giro;
		data['tahun'] = tahun;
    	var url = 'finance/get_giro_data_search';
		ajax_data_sync(url,data).done(function(data_respond  /*,textStatus, jqXHR*/ ){
			if (data_respond == 'no data') {
    			alert('no data / sudah disetor');
    		}else{
    			$.each(JSON.parse(data_respond),function(i,v){
	    			form.find("[name=giro_list_id]").val(v.id);
	    			form.find("[name=giro_list_type]").val(v.tipe);
				});
				form.submit();
    		}
   		});
    });

    var urutan_giro_array = [];
    $('#form-bayar').on('change','.urutan-giro', function () {
    	var ini = $(this).closest('tr');
    	var pointer = $(this);
    	var urutan_giro = $(this).val();
    	var tahun = $('[name=tahun]').val();
    	<?if (is_posisi_id() == 1) {?>
    		alert(tahun);
    	<?}?>
    	
    	var data = {};
		data['id'] = "<?=$giro_setor_id?>";
		data['urutan_giro'] = urutan_giro;
		data['tahun'] = tahun;
    	
    	if (urutan_giro != '') {
    		var index = urutan_giro_array.indexOf(tahun.toString() + urutan_giro.toString());
    		if (index == -1) {
				var url = 'finance/get_giro_data_search';
				var btn_remove = "<button type='button' tabindex='-1' class='btn btn-xs red btn-remove'><i class='fa fa-times'></i></button>"
		    	ajax_data_sync(url, data).done(function(data_respond  /*,textStatus, jqXHR*/ ){
		    		if (data_respond == 'no data') {
		    			alert('no data / sudah disetor');
		    			pointer.val('');
		    			pointer.focus();
		    			pointer.css('background','#fff');
		    		}else{
			    		$.each(JSON.parse(data_respond),function(i,v){
			    			ini.find("[name='giro_list_id[]']").val(v.id);
			    			ini.find("[name='giro_list_type[]']").val(v.tipe);
							ini.find('.nama-customer').html(v.nama_customer);
							ini.find('.tanggal-bayar').html(v.tanggal);
							ini.find('.nama-bank').html(v.nama_bank);
							ini.find('.no-giro').html(v.no_giro);
							ini.find('.jatuh-tempo').html(v.jatuh_tempo);
							ini.find('.amount').html(change_number_format(v.amount));
							ini.find('.action').html(btn_remove);
							ini.find('.tahun').html(tahun);
						});
		    			ini.next('tr').find('.urutan-giro').focus();
		    			// alert(ini.next('tr').html())
						urutan_giro_array.push(tahun.toString()+urutan_giro.toString());
						console.log(urutan_giro_array);
		    			pointer.css('background','#0ff');
		    			update_total_bayar();
		    		}
		   		});
    		}else{
    			alert('data double');
    			pointer.val('');
    			pointer.focus();
    			pointer.css('background','#fff');
    		}
    	};
		
    })


	<?if ($giro_setor_id != '') { ?>
		$("[name=pembulatan]").change(function(){
			var data = {};
			data['id'] = "<?=$giro_setor_id?>";
			data['pembulatan'] = $(this).val();
			var url = 'finance/update_pembulatan_hutang';
			ajax_data_sync(url,data).done(function(data_respond  /*,textStatus, jqXHR*/ ){
				if (data_respond == 'OK') {				
					update_total_bayar();
				}else{
					alert("error mohon refresh dengan menekan F5");
				}
	   		});
		});
	<?}?>

   

	$('#general_table').on('click','.btn-remove', function(){
		var ini = $(this);
		
		<?if ($giro_setor_id == '') {?>
			var tahun = ini.closest('tr').find('.tahun').html();
			var urutan_giro = ini.closest('tr').find('.urutan-giro').val();
			ini.closest('tr').remove();
    		var index = urutan_giro_array.indexOf(tahun.toString() + urutan_giro.toString());
    		urutan_giro_array.splice(index,1);

		<?}else{?>
			var id = ini.closest('tr').find('.giro_setor_detail_id').html();
			var giro_setor_id = "<?=$giro_setor_id?>";
			// var giro_setor_detail_id = "<?//=$giro_setor_detail_id?>";
			bootbox.confirm("Yakin mengeluarkan dari daftar ini ?", function(respond){
				if (respond) {
					var data = {};
					data['giro_setor_id'] = giro_setor_id;
					data['id'] = id;
					var url = 'finance/giro_setor_detail_remove';
					ajax_data_sync(url,data).done(function(data_respond  /*,textStatus, jqXHR*/ ){
						// alert(data_respond);
						if (data_respond == 'OK') {
							ini.closest('tr').remove();
						}else{
							alert("error mohon refresh dengan menekan F5");
						}
			   		});
					// window.location.replace(baseurl+"finance/giro_setor_detail_remove?id="+id+'&giro_setor_id='+giro_setor_id);
					// ini.closest('tr').find('.urutan-giro').val(0);
				};
			});
		<?}?>
	});

	$('.add_new_baris').click(function(){
		var new_baris = `<tr>
							<td class='text-center'>
								<input name='urutan_giro[]' class='urutan-giro' style='border:none; border-bottom:1px solid #ccc; width:50px;text-align:center; font-weight:bold; font-size:1.2em ' value=''>
								<input hidden name='giro_list_id[]' class='giro-list-id'>
								<input hidden name='giro_list_type[]' class='giro-list-type'>
								<input hidden name='detail_id[]' value="0" class='giro-setor-detail-id'>
							</td>
							<td class='nama-customer'></td>
							<td class='tanggal-bayar'></td>
							<td class='nama-bank'></td>
							<td class='no-giro'></td>
							<td class='jatuh-tempo'></td>
							<td class='amount'></td>
							<td class='action'></td>
						</tr>`;

		$('#general_table tbody').append(new_baris);
	});
});

function update_total_bayar(){
	var total_setor = 0;
	$("#general_table .amount").each(function(){
		total_setor += parseInt(reset_number_format($(this).html()));
	});
	$('.total_setor').html(change_number_format(total_setor));
}



</script>
