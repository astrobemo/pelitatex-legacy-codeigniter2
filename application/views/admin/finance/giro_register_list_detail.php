<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css'); ?>"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css'); ?>"/>
<?php echo link_tag('assets/global/plugins/bootstrap-datepicker/css/datepicker3.css'); ?>

<style>

input.picker[type="date"] {
  position: relative;
}

input.picker[type="date"]::-webkit-calendar-picker-indicator {
  position: absolute;
  top: 0;
  right: 0;
  width: 100%;
  height: 100%;
  padding: 0;
  color: transparent;
  background: transparent;
	cursor: pointer;

}
</style>

<div class="page-content">
	<div class='container'>
		<?foreach ($giro_data->result() as $row) {
			$giro_list_id = $row->id;
		}?>

		<div class="modal fade" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body">
						<form action="<?=base_url('finance/giro_register_detail_insert')?>" class="form-horizontal" id="form_add_data" method="post">
							<h3 class='block'> Trx Baru</h3>
							
			                <div class="form-group">
			                    <label class="control-label col-md-4">Tanggal<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<input name='id' hidden>
			                    	<input name='giro_list_id' value="<?=$giro_list_id;?>" hidden>
			                    	<input readonly class='form-control date-picker' name='tanggal' value="<?=date('d/m/Y')?>" >
			                    </div>
			                </div>

			                <div class="form-group">
			                    <label class="control-label col-md-4">No Giro<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<input readonly style="font-weight:bold; font-size:1.2em; letter-spacing:2px;" class='form-control' name='no_giro'>
			                    </div>
			                </div>

			                <div class="form-group">
			                    <label class="control-label col-md-4">Penerima<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<input class='form-control' name='penerima'>
			                    </div>
			                </div>

			                <div class="form-group">
			                    <label class="control-label col-md-4">Amount<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<input class='form-control amount_number' name='amount'>
			                    </div>
			                </div>

			               	<div class="form-group">
			                    <label class="control-label col-md-4">Jatuh Tempo<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<input readonly class='form-control date-picker' name='jatuh_tempo' >
			                    </div>
			                </div>

			                <div class="form-group">
			                    <label class="control-label col-md-4">Keterangan<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<input class='form-control' name='keterangan' >
			                    </div>
			                </div>

						</form>
					</div>

					<div class="modal-footer">
						<button type="button" class="btn blue btn-active btn-save">Save</button>
						<button type="button" class="btn default" data-dismiss="modal">Close</button>
					</div>
				</div>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
		</div>

		<div class="modal fade" id="portlet-config-batal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body">
						<form action="<?=base_url('finance/giro_register_detail_batal')?>" class="form-horizontal" id="form_batal_data" method="post">
							<h3 class='block'> Trx <b style="color:red">BATAL</b></h3>
							
			                <div class="form-group">
			                    <label class="control-label col-md-4">Tanggal Batal<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<input name='id' hidden>
			                    	<input name='status' value="0" hidden>
			                    	<input name='tipe' hidden>
			                    	<input name='giro_list_id' value="<?=$giro_list_id;?>" hidden>
			                    	<input readonly class='form-control date-picker' name='tanggal' value="<?=date('d/m/Y');?>">
			                    </div>
			                </div>

			                <div class="form-group">
			                    <label class="control-label col-md-4">NO GIRO<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<input readonly style="font-weight:bold; font-size:1.2em; letter-spacing:2px; " class='form-control' name='no_giro'>
			                    </div>
			                </div>

			                <div class="form-group">
			                    <label class="control-label col-md-4">Keterangan Awal<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<textarea readonly class='form-control' name='penerima'></textarea>
			                    </div>
			                </div>

			                <div class="form-group">
			                    <label class="control-label col-md-4">Amount<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<input readonly class='form-control amount_number' name='amount'>
			                    </div>
			                </div>

			                <div class="form-group">
			                    <label class="control-label col-md-4">Keterangan Batal<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<input class='form-control' name='keterangan' >
			                    </div>
			                </div>

						</form>
					</div>

					<div class="modal-footer">
						<button type="button" class="btn blue btn-save-batal">Save</button>
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

							function left_pad($number, $pad_length ){
								return str_pad($number, $pad_length,'0', STR_PAD_LEFT);
							}

							$giro_list_detail = (array)$giro_list_detail;
							foreach ($giro_list_detail as $key => $value) {
								$giro_list_new[$value->no_giro] = $giro_list_detail[$key];
							}
							// print_r($giro_list_new);
						?>
						<?$jml_giro = 0;
						foreach ($giro_data->result() as $row) {
							$giro_awal = filter_var($row->no_giro_awal,FILTER_SANITIZE_NUMBER_INT);
							$pad_length = strlen($giro_awal);
							$jml_giro = $row->jml_giro;
							$pre = $pre = str_replace($giro_awal, '', $row->no_giro_awal);
							$bg_color = ($row->tipe_trx == 1 ? '#c5eff7' : '#ff9478');
							$tipe_trx_write = ($row->tipe_trx == 1 ? 'BG' : 'CEK');

							?>
							<div style='background-color:<?=$bg_color;?>; padding:5px 20px'>
								<h2>
									<table>
										<tr>
											<td>TIPE</td>
											<td class='padding-rl-5'> : </td>
											<td>
												<b>
													<?=$tipe_trx_write;?>
												</b>
											</td>
										</tr>
										<tr>
											<td>No GIRO</td>
											<td class='padding-rl-5'> : </td>
											<td>
												<b>
												<?=$row->no_giro_awal;?> s/d <?=giro_numerator($row->no_giro_awal, $row->jml_giro)?>
												</b>
											</td>
										</tr>
										<tr>
											<td>BANK</td>
											<td class='padding-rl-5'> : </td>
											<td>
												<b>
												<?=$row->nama_bank;?> [ rek:<?=$row->no_rek_bank;?> ]
												</b>
											</td>
										</tr>
										<tr>
											<td>Jumlah Giro</td>
											<td class='padding-rl-5'> : </td>
											<td>
												<b>
													<?=$row->jml_giro;?> lbr
												</b>
											</td>
										</tr>
									</table>
								</h2>
							</div>
						<?}?>
						<hr/>
						<table class="table table-hover table-striped table-bordered" id="general_table">
							<thead>
								<tr>									
									<th scope="col">
										No Giro
									</th>
									<th scope="col">
										Tanggal
									</th>
									<th scope="col">
										Jatuh Tempo
									</th>
									<th scope="col">
										Penerima
									</th>
									<th scope="col">
										Keterangan
									</th>
									<th scope="col">
										Amount
									</th>
									<th scope="col">
										Tanggal Cair
									</th>
									<th scope="col">
										Actions
									</th>
								</tr>
							</thead>
							<tbody>
								<?for ($i=0; $i < $jml_giro ; $i++) {
									$idx = $pre.left_pad($i+$giro_awal, $pad_length ) ;
									$pembayaran_hutang_id = '';
									$amount = '';
									$tipe = '';
									$tanggal = '';
									$jatuh_tempo = '';
									$penerima = '';
									$bg_color = '';
									if (isset($giro_list_new[$idx])) {
										$pembayaran_hutang_id = $giro_list_new[$idx]->pembayaran_hutang_id;
										$tipe = $giro_list_new[$idx]->tipe;
										$amount = number_format($giro_list_new[$idx]->amount,'0',',','.');
										$tanggal = $giro_list_new[$idx]->tanggal;
										$jatuh_tempo = ($giro_list_new[$idx]->status == 0 ? "-" :is_reverse_date($giro_list_new[$idx]->jatuh_tempo) );
										$penerima = $giro_list_new[$idx]->penerima;
										$status = $giro_list_new[$idx]->status;
										$giro_list_detail_id = $giro_list_new[$idx]->id;
										$bg_color = ($status != 0 ? '' : 'background-color:yellow');
									}?>
									<tr style="<?=$bg_color;?>">
										<td><span class='no_giro'><?=$pre?><?=left_pad($i+$giro_awal, $pad_length );?></span></td>
										<td><span class='tanggal'><?=is_reverse_date($tanggal);?></span></td>
										<td><span class='jatuh_tempo'><?=$jatuh_tempo;?></span></td>
										<td><span class='penerima'><?=$penerima;?></span></td>
										<td>
											<?if ($pembayaran_hutang_id != '' && $tipe == 1 ) {?>
												<a class='keterangan' href="<?=base_url().is_setting_link('finance/hutang_payment_form');?>?id=<?=$pembayaran_hutang_id;?>" target='_blank'>Untuk Pembayaran Hutang <?=$giro_list_new[$idx]->keterangan;?></a>
											<?}elseif ($tipe == 2) {
												echo "<span class='keterangan'>".$giro_list_new[$idx]->keterangan."</span>";
											}elseif ($tipe == 3){?>
												<?=$giro_list_new[$idx]->keterangan;?> o/ <?=$giro_list_new[$idx]->username;?>
											<?}?>
										</td>
										<td><span class='amount'><?=($amount == 0 ? '-' : $amount);?></span> </td>
										<td>
											<?if ($pembayaran_hutang_id != '' && $tipe == 1 ) {
												$tanggal_cair = $giro_list_new[$idx]->tanggal_cair;
												$bg=($tanggal_cair == '' ? 'white' : 'lightgreen')?>
												<input class="date-picker-cair text-center"
												data-id="<?=$giro_list_new[$idx]->id?>"
												style="border: none;background:<?=$bg?>; width:100px;" 
												value="<?=($tanggal_cair == '' ? '' : (is_reverse_date($tanggal_cair)))?>"
												max="<?=date('Y-m-d')?>"/>
											<?}?>
										</td>
										<td>
											<span class='giro-list-id' <?=(is_posisi_id() != 1 ? 'hidden' : '');?>><?=$row->id;?></span>
											<span class='tipe' <?=(is_posisi_id() != 1 ? 'hidden' : '');?>><?=$tipe?></span>
											<?if ($tipe != 1) {
												if ($tipe == 2) {
													if (isset($status) && $status != 0) {?>
														<span class='id' <?=(is_posisi_id() != 1 ? 'hidden' : '');?>><?=$giro_list_detail_id?></span>
														<a href="#portlet-config" data-toggle="modal" class='btn btn-xs green btn-edit'><i class='fa fa-edit'></i></a>
														<a href="#portlet-config-batal" data-toggle="modal" class='btn btn-xs yellow-gold btn-batal' ><i class='fa fa-warning'></i> Batal</a>
														<a class='btn btn-xs red btn-remove' ><i class='fa fa-times'></i></a>
													<?}?>
												<?}elseif($tipe == 3){?>
												<?}else{?>
													<a href="#portlet-config" data-toggle="modal" class='btn btn-xs blue btn-add' ><i class='fa fa-plus'></i></a>
													<a href="#portlet-config-batal" data-toggle="modal" class='btn btn-xs yellow-gold btn-batal-langsung' ><i class='fa fa-warning'></i> Batal</a>
												<?}
											}else{?>
												<a href="#portlet-config-batal" data-toggle="modal" class='btn btn-xs yellow-gold btn-batal' ><i class='fa fa-warning'></i> Batal</a>
											<?}?>
										</td>
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
<script src="<?php echo base_url('assets/global/plugins/bootbox/bootbox.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets_noondev/js/table-advanced.js'); ?>"></script>

<script>
jQuery(document).ready(function() {

	// dataTableTrue();
	$('.date-picker-cair').datepicker({
        autoclose : true,
        format: "dd/mm/yyyy"
    }).on('changeDate', function(ev) {
		const ini = ev.currentTarget;
		const id = ini.getAttribute("data-id");
		//Functionality to be called whenever the date is changed
		registerDateCair(ini,id )

		// onchange="registerDateCair(this,'')"

	});
	
	$("#general_table").DataTable({
		"ordering":false,
		// "bFilter":false
	});

	$('.btn-save').click(function(){
		if($("#form_add_data[name=tanggal]").val() != ''){
			btn_disabled_load($('.btn-save'));
			$('#form_add_data').submit();
		}else{
			alert("Tanggal Wajib diisi");
		}
	});

	$('#general_table').on('click','.btn-add',function(){
		$('#form_add_data [name=no_giro]').val($(this).closest('tr').find('.no_giro').html());
		$('#form_add_data [name=id]').val('');
	});

	$('#general_table').on('click','.btn-edit',function(){
		var form = $("#form_add_data");
		var ini = $(this).closest('tr');
		form.find('[name=no_giro]').val(ini.find('.no_giro').html());
		form.find('[name=id]').val(ini.find('.id').html());
		form.find('[name=tanggal]').val(ini.find('.tanggal').html());
		form.find('[name=jatuh_tempo]').val(ini.find('.jatuh_tempo').html());
		form.find('[name=penerima]').val(ini.find('.penerima').html());
		form.find('[name=amount]').val(ini.find('.amount').html());
		form.find('[name=keterangan]').val(ini.find('.keterangan').html());
	});

	$("#general_table").on('click','.btn-remove', function(){
		var id = $(this).closest('tr').find('.id').html();
		alert(id);
		bootbox.confirm("Yakin hapus data giro ini ? ", function(respond){
			window.location.replace("finance/giro_register_list_detail_remove?id="+id+"&giro_list_id=<?=$giro_list_id;?>");
		});

	});

	$('#general_table').on('click','.btn-batal',function(){
		var form = $("#form_batal_data");
		var ini = $(this).closest('tr');
		form.find('[name=no_giro]').val(ini.find('.no_giro').html());
		form.find('[name=id]').val(ini.find('.id').html());
		form.find('[name=tipe]').val(ini.find('.tipe').html());
		form.find('[name=tanggal]').val(ini.find('.tanggal').html());
		form.find('[name=jatuh_tempo]').val(ini.find('.jatuh_tempo').html());
		form.find('[name=penerima]').val(ini.find('.keterangan').html());
		form.find('[name=amount]').val(ini.find('.amount').html());
		form.find('[name=keterangan]').val(ini.find().html());
	});

	$('#general_table').on('click','.btn-batal-langsung',function(){
		var form = $("#form_batal_data");
		var ini = $(this).closest('tr');
		form.find('[name=no_giro]').val(ini.find('.no_giro').html());
		form.find('[name=tipe]').val(3);
		form.find('[name=tanggal]').val("<?=date('d/m/Y');?>");
		form.find('[name=jatuh_tempo]').val('');
		form.find('[name=penerima]').val('');
		form.find('[name=amount]').val(0);
		form.find('[name=keterangan]').val("BATAL");
	});

	$(".btn-save-batal").click(function(){
		var form = $("#form_batal_data");
		if (form.find("[name=keterangan]").val() == '') {
			alert("mohon isi keterangan mengapa dibatalkan");
		}else{
			form.submit();
		}

	});

});

function showCalendar(ini){
	const cell = ini.closest('td');
	const inputDate = cell.querySelector(".picker");
	// inputDate.show();
	$(cell).find(".picker").show().click();
	console.log($(cell));
}

function left_pad (str, max) {
  str = str.toString();
  return str.length < max ? left_pad("0" + str, max) : str;
}

async function registerDateCair(ini, id){
	let insertDate = '';
	if (ini.value != '') {
		insertDate = ini.value.split("/").reverse().join("-");
	}

	const response = await fetch(baseurl+"finance/register_giro_cair", {
      method: "POST",
	  body:`tanggal=${insertDate}&id=${id}`,
	  headers: {
		'Content-Type': 'application/x-www-form-urlencoded',
		},
    });
    const result_data = await response.json();
	const bg = (insertDate != '' ? 'lightgreen' : 'white')
	if (result_data == "OK") {
		ini.style.backgroundColor  = bg;
		notific8("lime","Tanggal Cair updated");
	}
}
</script>
