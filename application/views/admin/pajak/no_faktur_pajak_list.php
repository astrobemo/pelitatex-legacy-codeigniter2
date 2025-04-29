<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css'); ?>"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css'); ?>"/>
<?php echo link_tag('assets/global/plugins/bootstrap-datepicker/css/datepicker3.css'); ?>

<div class="page-content">
	<div class='container'>

		<div class="modal fade" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body">
						<form action="<?=base_url('pajak/no_fp_insert')?>" class="form-horizontal" id="form_add_data" method="post">
							<h3 class='block'> Nomer Baru</h3>

							<div class="form-group">
			                    <label class="control-label col-md-4">Tanggal<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<input class='form-control date-picker' name='tanggal' value="<?=date('d/m/Y');?>" >
			                    </div>
			                </div>

			                <div class="form-group">
			                    <label class="control-label col-md-4">Tahun Pajak<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<select class='form-control' name='tahun_pajak' >
			                    		<?for ($i=date('Y'); $i <= (date('Y') + 1) ; $i++) {?> 
											<option <?=(date('Y') == $i ? 'selected' : '');?> value="<?=$i?>"><?=$i?></option>	
										<?}?>
			                    	</select>
			                    </div>
			                </div>

			               	<div class="form-group">
			                    <label class="control-label col-md-4">No Faktur Pajak<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<input class='form-control' name='no_fp_awal' >
			                    </div>
			                </div>

			                <div class="form-group">
			                    <label class="control-label col-md-4">No Faktur Pajak Akhir<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<input class='form-control' name='no_fp_akhir' >
			                    </div>
			                </div>

			                <div class="form-group">
			                    <label class="control-label col-md-4">Banyak Nomor<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<input class='form-control' name='jml_fp'>
			                    </div>
			                </div>

						</form>
					</div>

					<div class="modal-footer">
						<button type="button" class="btn btn-active blue btn-save">Save</button>
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
							function fp_get_lembar($number, $number_end){
								$fp_num = filter_var($number,FILTER_SANITIZE_NUMBER_INT);
								$fp_num_end=filter_var($number_end,FILTER_SANITIZE_NUMBER_INT);
								$pad_length = $fp_num_end - $fp_num + 1;
								return $pad_length;
							}
						?>
						<table class="table table-hover table-striped table-bordered" id="general_table">
							<thead>
								<tr>
									<th scope="col">
										Tanggal
									</th>
									<th scope="col">
										No Faktur Pajak
									</th>
									<th scope="col">
										TAHUN
									</th>
									<th scope="col">
										Jumlah No
									</th>
									<th scope="col">
										Terpakai
									</th>
									<th scope="col">
										Sisa
									</th>
									<th scope="col">
										Register
									</th>
									<th scope="col">
										Created
									</th>
									<th scope="col">
										Actions
									</th>
								</tr>
							</thead>
							<tbody>
								<?foreach ($fp_list as $row) { ?>
									<tr>
										<td><?=is_reverse_date($row->tanggal);?></td>
										<td>
											<b style='font-size:1.2em'>
											<?=$row->no_fp_awal;?> s/d <?=$row->no_fp_akhir;?> 
											</b>
										</td>
										<td><b style='font-size:1.1em'><?=date('Y', strtotime($row->tahun_pajak))?></b></td>
										<td><?=$lbr=fp_get_lembar($row->no_fp_awal, $row->no_fp_akhir)?></td>
										<td><?=$row->terpakai;?></td>
										<td><?=$lbr - $row->terpakai;?></td>
										<td><?=$row->username;?></td>
										<td><?=$row->created_at?></td>
										<td><a href="<?=base_url().is_setting_link('pajak/no_fp_list_detail');?>?id=<?=$row->id?>" class='btn btn-xs yellow-gold' target='_blank'><i class='fa fa-search'></i></a></td>
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
		alert(fp_get_end($('#test1').html()));
	});

//=======================================fp no===========================================

	$("[name=no_fp_awal], [name=no_fp_akhir] ").change(function(){
		var form = '#'+$(this).closest('form').attr('id');
		form = $(form);
		update_fp(form);
	});

	$('.btn-form-add').click(function(){
		$('#form_add_data [name=no_fp_awal]').val('');
		// $('#form_add_data [name=jml_fp]').val('');
		$('#form_add_data [name=no_fp_akhir]').val('');
	});

	$('.btn-save').click(function(){
		let no_fp_awal = $('#form_add_data [name=no_fp_awal]').val();
		let no_fp_akhir = $('#form_add_data [name=no_fp_akhir]').val()
		if ( no_fp_awal != '' && $('#form_add_data [name=jml_fp]').val() != '' ) {
			btn_disabled_load($(this));
			var data = {};
			data['no_fp_awal'] = no_fp_awal;
			data['no_fp_akhir'] = no_fp_akhir;
			// var this = $(this);
			var url = 'pajak/check_no_fp_baru';
			ajax_data_sync(url,data).done(function(data_respond  /*,textStatus, jqXHR*/ ){
				if (data_respond == 'OK') {
					$('#form_add_data').submit();
				}else{
					$(".btn-active").prop('disabled', false);
					$(".btn-save").html("Save");
					alert("ERORR atau Nomor sudah pernah di input");
				}
	   		});
		}else{
			alert('lengkapi data');
		};
	});


});

function update_fp(form){
	var no_fp = form.find('[name=no_fp_awal]').val();
	var no_fp_akhir = form.find('[name=no_fp_akhir]').val();
	var jml_fp = form.find('[name=jml_fp]').val();
	if(no_fp_akhir != '' && no_fp != ''){
		var jml_fp = jml_fp_get(no_fp, no_fp_akhir);
		form.find('[name=jml_fp]').val(jml_fp);	
	}
}

function jml_fp_get(fp_awal, fp_akhir){
	var num = fp_awal;
	num = $.trim(num);
	var number = num.replace(/[^0-9]/gi, '');
	number = parseInt(number);
	// alert(number);
	var number_awal = number;

	var num = fp_akhir;
	num = $.trim(num);
	var number = num.replace(/[^0-9]/g,'');
	number = parseInt(number);
	// alert(number);
	var number_akhir = number;
	return number_akhir - number_awal + 1;

}

</script>
