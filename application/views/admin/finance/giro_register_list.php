<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css'); ?>"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css'); ?>"/>
<?php echo link_tag('assets/global/plugins/bootstrap-datepicker/css/datepicker3.css'); ?>

<div class="page-content">
	<div class='container'>

		<div class="modal fade" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body">
						<form action="<?=base_url('finance/giro_register_insert')?>" class="form-horizontal" id="form_add_data" method="post">
							<h3 class='block'> Buku Baru</h3>
							

			                <div class="form-group">
			                    <label class="control-label col-md-4">Tipe<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<input name='id' hidden>
	                    			<select name='tipe_trx' class='form-control'>
	                    				<option selected value='1'>BUKU GIRO</option>
	                    				<option value='2'>BUKU CEK</option>
	                    			</select>
			                    </div>
			                </div>

			                <!-- <div class="form-group">
			                    <label class="control-label col-md-4">Bank<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<input readonly class='form-control' name='nama_bank' value='BCA'>
			                    </div>
			                </div>

			                <div class="form-group">
			                    <label class="control-label col-md-4">No Rek Bank<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<input class='form-control' name='no_rek_bank'>
			                    </div>
			                </div> -->

			                <div class="form-group">
			                    <label class="control-label col-md-4">Bank<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<input name='id' hidden>
	                    			<select name='bank_list_id' class='form-control'>
	                    				<?foreach ($this->bank_list_aktif as $row) {?>
	                    					<option value="<?=$row->id?>"><?=$row->nama_bank?> : <?=$row->no_rek_bank;?> </option>	
	                    				<?}?>
	                    			</select>
			                    </div>
			                </div>



			                <div class="form-group">
			                    <label class="control-label col-md-4">Banyak Lembar<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<input class='form-control' name='jml_giro' value='25'>
			                    </div>
			                </div>

			               	<div class="form-group">
			                    <label class="control-label col-md-4">No Giro<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<input class='form-control' name='no_giro_awal' >
			                    </div>
			                </div>

			                <div class="form-group">
			                    <label class="control-label col-md-4">No Giro Akhir<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<input class='form-control' name='no_giro_akhir' >
			                    </div>
			                </div>

						</form>
					</div>

					<div class="modal-footer">
						<button type="button" class="btn blue btn-save">Save</button>
						<button type="button" class="btn default" data-dismiss="modal">Close</button>
					</div>
				</div>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
		</div>

		<div class="row margin-top-10">
			<div class="col-md-12">
				<div class="portlet light">
					<div class="portlet-title">
						<div class="caption caption-md">
							<i class="icon-bar-chart theme-font hide"></i>
							<span class="caption-subject theme-font bold uppercase"><?=$breadcrumb_small;?></span>
						</div>
						<div class="actions">
							<a href="#portlet-config" data-toggle='modal' class="btn btn-default btn-sm btn-form-add">
							<i class="fa fa-plus"></i> Data Baru </a>
						</div>
					</div>
					<div class="portlet-body">
						
						<?
							function giro_numerator($number, $jml_giro){
								$giro_num = filter_var($number,FILTER_SANITIZE_NUMBER_INT);
								$pad_length = strlen(filter_var($giro_num,FILTER_SANITIZE_NUMBER_INT));
								$pre = str_replace($giro_num, '', $number);
								$giro_num_end=filter_var($giro_num,FILTER_SANITIZE_NUMBER_INT) + $jml_giro - 1;
								return $pre.str_pad($giro_num_end, $pad_length,'0', STR_PAD_LEFT);
							}
						?>
						<table class="table table-hover table-striped table-bordered" id="general_table">
							<thead>
								<tr>
									<th scope="col">
										Tipe
									</th>
									<th scope="col">
										No Giro
									</th>
									<th scope="col">
										Nama Bank
									</th>
									<th scope="col">
										Terpakai
									</th>
									<th scope="col">
										Belum Terpakai
									</th>
									<th scope="col" class="text-center">
										Batal
									</th>
									<th scope="col" class="text-center">
										Cair
									</th>
									<th scope="col" class="text-center">
										Dibuat
									</th>
									<th scope="col">
										Actions
									</th>
								</tr>
							</thead>
							<tbody>
								<?foreach ($giro_list as $row) { ?>
									<?$bg_color = $row->{'tipe_trx_'.$row->tipe_trx};?>
									<tr style="background-color:<?=$bg_color?>" >
										<td>
											<?=($row->tipe_trx == 1 ? 'BG' : 'CEK' );?>
										</td>
										<td>
											<b style='font-size:1.2em'>
											<?=$row->no_giro_awal;?> s/d <?=giro_numerator($row->no_giro_awal, $row->jml_giro);?> 
											</b>
										</td>
										<td><?=$row->nama_bank;?> / <?=$row->no_rek_bank;?></td>
										<td><?=$row->lbr?></td>
										<td><?=$row->jml_giro - $row->lbr;?></td>
										<td class="text-center"><?=$row->lbr_batal?></td>
										<td class="text-center"><?=$row->lbr_cair?></td>
										<td class="text-center"><?=date('d/m/Y', strtotime($row->created));?></td>
										<td><a href="<?=base_url().is_setting_link('finance/giro_register_list_detail');?>?id=<?=$row->id?>" class='btn btn-xs yellow-gold' target='_blank'><i class='fa fa-search'></i></a></td>
									</tr>
								<?}?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>			
</div>

<script src="<?php echo base_url('assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/datatables/media/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets_noondev/js/table-advanced.js'); ?>"></script>

<script>
jQuery(document).ready(function() {

	// dataTableTrue();

	// TableAdvanced.init();

	// oTable = $('#general_table').DataTable();
	// oTable.state.clear();
	// oTable.destroy();

	
	$("#general_table").DataTable({
		"ordering":false,
		// "bFilter":false
	});

//==================================================================================

	$('.btn-test').click(function(){
		alert(giro_get_end($('#test1').html()));
	});


	$('#general_table').on('change','[name=tanggal_setor]',function(){
		var ini = $(this).closest('tr');
		var data = {};
		data['pembayaran_piutang_id'] = ini.find('.pembayaran_piutang_id').html();
		data['tanggal_setor'] = $(this).val();
		// var this = $(this);
		var url = 'finance/update_setor_giro';
		ajax_data_sync(url,data).done(function(data_respond  /*,textStatus, jqXHR*/ ){
			if (data_respond == 'OK') {
				
			}else{
				$(this).val('');
				alert("error mohon refresh dengan menekan F5");
			}
   		});
	});

//=======================================giro no===========================================

	$("[name=no_giro_awal], [name=jml_giro], [name=no_giro_akhir] ").change(function(){
		var form = '#'+$(this).closest('form').attr('id');
		form = $(form);
		update_giro(form);
	});

	$('.btn-form-add').click(function(){
		$('#form_add_data [name=no_giro_awal]').val('');
		// $('#form_add_data [name=jml_giro]').val('');
		$('#form_add_data [name=no_giro_akhir]').val('');
	});

	$('.btn-save').click(function(){
		if ($('#form_add_data [name=no_giro_awal]').val() != '' && $('#form_add_data [name=jml_giro]').val() != '' ) {
			$('#form_add_data').submit();
		};
	});


});

function update_giro(form){
	var no_giro = form.find('[name=no_giro_awal]').val();
	var no_giro_akhir = form.find('[name=no_giro_akhir]').val();
	var jml_giro = form.find('[name=jml_giro]').val();
	if (no_giro != '' && jml_giro != '' && jml_giro != 0) {
		var giro_akhir = no_giro_get(no_giro, jml_giro ,1);
		form.find('[name=no_giro_akhir]').val(giro_akhir);	
	} else if (no_giro_akhir != '' && jml_giro != '') {
		var giro_awal = no_giro_get(no_giro, jml_giro, 2);
		form.find('[name=no_giro_awal]').val(giro_awal);	
	}else if(no_giro_akhir != '' && no_giro != ''){
		var jml_giro = jml_giro_get(no_giro, no_giro_akhir);
		form.find('[name=jml_giro]').val(jml_giro);	
	}
}

function jml_giro_get(giro_awal, giro_akhir){
	var num = giro_awal;
	num = $.trim(num);
	var number = num.replace(/[^0-9\.]/g,'');
	var max = number.length;
	var pre = num.replace(number,'');
	var number_awal = parseInt(number, 10);

	var num = giro_akhir;
	num = $.trim(num);
	var number = num.replace(/[^0-9\.]/g,'');
	var max = number.length;
	var pre = num.replace(number,'');
	var number_akhir = parseInt(number, 10);
	return number_akhir - number_awal + 1;

}

function no_giro_get(giro_num, jml_giro, tipe){
	var num = giro_num;
	num = $.trim(num);
	var number = num.replace(/[^0-9\.]/g,'');
	var max = number.length;
	var pre = num.replace(number,'');
	number = parseInt(number, 10);
	if (tipe==1) {
		var new_number = number +parseInt(jml_giro) - 1;
	}else{
		var new_number = number -parseInt(jml_giro) + 1;
	}
	return pre+left_pad(new_number,max);
}

function left_pad (str, max) {
  str = str.toString();
  return str.length < max ? left_pad("0" + str, max) : str;
}
</script>
