<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css'); ?>"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css'); ?>"/>
<?php echo link_tag('assets/global/plugins/bootstrap-datepicker/css/datepicker3.css'); ?>

<style type="text/css">
.table-detail tr{
	border-bottom: 1px solid #ddd
}
.table-detail tr:last-child{
	border-bottom: none;
}
</style>

<div class="page-content">
	<div class='container'>

		<div class="modal fade" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body">
						<form action="<?=base_url('pajak/rekam_faktur_pajak_insert')?>" class="form-horizontal" id="form_add_data" method="post">
							<h3 class='block'> Nomer Baru</h3>

			               	<div class="form-group">
			                    <label class="control-label col-md-4">Tanggal Start<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<input class='form-control date-picker' name='tanggal_start' value="<?=is_reverse_date($last_date);?>" >
			                    </div>
			                </div>

			                <div class="form-group">
			                    <label class="control-label col-md-4">Tanggal Akhir<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<input class='form-control date-picker' name='tanggal_end' value="<?=date('d/m/Y');?>" >
			                    </div>
			                </div>

			                <div class="form-group">
			                    <label class="control-label col-md-4">Pembuat<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<input class='form-control' name='username' value='<?=is_username();?>'>
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

						<div>
							<div style='float:right'>
								<table>
									<tr>
										<td style='color:blue'><i class='fa fa-download'></i></td>
										<td style='padding:0 5px'> : </td>
										<td>Faktur <b>sudah</b> pernah di download</td>
									</tr>
									<tr>
										<td style='color:red'><i class='fa fa-download'></i></td>
										<td style='padding:0 5px'> : </td>
										<td>Faktur <b>belum</b> pernah di download</td>
									</tr>
								</table>
							</div>
							<form id='form-tahun-laporan'>
								<p>TAHUN : 
									<b>
										<select name='tahun' id="select-tahun-laporan">
											<option value="<?=date("Y") - 1;?>" <?=($tahun == (date("Y")-1) ? 'selected' : '' )?>><?=date("Y")-1;?></option>
											<option value="<?=date("Y")?>" <?=($tahun == date("Y") ? 'selected' : '' )?> ><?=date("Y");?></option>
											<option value="<?=date("Y")+1?>" <?=($tahun == (date("Y")+1) ? 'selected' : '' )?>><?=date("Y")+1;?></option>
										</select>
									</b>
									<i class='loading' hidden>loading.....</i> 
								</p>
							</form>

						</div>
						
						<table class="table table-hover table-striped table-bordered" id="general_table">
							<thead>
								<tr>
									<th scope="col">
										Bulan Faktur
									</th>
									<th scope="col">
										Jumlah Faktur (NPWP)
									</th>
									<th scope="col">
										<table>
											<tr>
												<th class='text-center' style='width:80px'>Tanggal</th>
												<th class='text-center' style='width:80px'>Faktur</th>
												<th class='text-center' style='width:50px'>Email</th>
												<th class='text-center' style='width:50px'>Kirim</th>
												<th class='text-center' style='width:50px'>Ambil</th>
												<th class='text-center' style='width:50px'>WA</th>
												<th class='text-center' style='width:50px'>Others</th>
											</tr>
										</table>
									</th>
									<th scope="col">
										Download
									</th>
								</tr>
							</thead>
							<tbody>
								<?foreach ($this->toko_list_aktif as $row) {
									$pre_po = $row->pre_po;
								}?>
								<?foreach ($laporan_fp as $row) { 
									unset($email);
									$faktur_pajak_id = explode(',', $row->faktur_pajak_id);
									$tanggal_start = explode(',', $row->tanggal_start);
									$tanggal_end = explode(',', $row->tanggal_end);
									$jumlah_faktur = explode(',', $row->jumlah_faktur);
									$jumlah_faktur_npwp = explode(',', $row->jml_npwp);
									$jumlah_customer = explode(',', $row->jml_cust);
									$jml_action_id = explode(',', $row->action_id);
									$email = explode(',', $row->email);
									$kirim = explode(',', $row->kirim);
									$ambil = explode(',', $row->ambil);
									$whatsapp = explode(',', $row->whatsapp);
									$others = explode(',', $row->others);

									$jml_email = explode(',', $row->jml_email);
									$jml_kirim = explode(',', $row->jml_kirim);
									$jml_ambil = explode(',', $row->jml_ambil);
									$jml_wa = explode(',', $row->jml_wa);
									$jml_others = explode(',', $row->jml_others);

									$no_surat = explode(',', $row->no_surat);
									$created_at = explode(',', $row->created_at);
									$nilai_download = 1;
									$total_faktur = 0;
									$tanggal_get = $tanggal_start[0];
									$tahun_set = date('Y', strtotime($tanggal_get));
									$bulan_set = date('m', strtotime($tanggal_get));
									unset($warning);
									foreach ($jumlah_faktur_npwp as $key => $value) {
										$total_faktur += $value;
									}
									?>
									<tr>
										<td><?=$row->tanggal_faktur?></td>
										<td><?=$total_faktur;?></td>
										<td><table class='table-detail'>
											<?foreach ($faktur_pajak_id as $key => $value) {?>
											<tr>
												<td style='width:80px' class='text-center'>
													<a href="<?=base_url().is_setting_link('pajak/rekam_faktur_pajak_detail')?>?id=<?=$value;?>" target="_blank"> <?=date("d", strtotime($tanggal_start[$key])).' - '.date("d", strtotime($tanggal_end[$key]));?> </a>
												</td>
												<td style='width:80px' class='text-center'><?=(isset($jumlah_faktur_npwp[$key]) ? $jumlah_faktur_npwp[$key] : 0 ) ?></td>
												<td style='width:50px' class='text-center'><?=(isset($jml_email[$key])  && $jml_email[$key] !=0  ? $jml_email[$key] : '-' )?></td>
												<td style='width:50px' class='text-center'><?=(isset($jml_kirim[$key]) && $jml_kirim[$key] !=0 ? $jml_kirim[$key] : '-' )?></td>
												<td style='width:50px' class='text-center'><?=(isset($jml_ambil[$key]) && $jml_ambil[$key] !=0 ? $jml_ambil[$key] : '-' )?></td>
												<td style='width:50px' class='text-center'><?=(isset($jml_wa[$key]) && $jml_wa[$key] !=0 ? $jml_wa[$key] : '-' )?></td>
												<td style='width:50px' class='text-center'><?=(isset($jml_others[$key]) && $jml_others[$key] !=0 ? $jml_others[$key] : '-' ) ?></td>
												<td>
													<a href="<?=base_url().is_setting_link('pajak/rekam_faktur_email_list')?>?id=<?=$value;?>" target="_blank">
													<?=($no_surat[$key] != 0 ? $pre_po.'-'.date('Y',strtotime($created_at[$key])).'/01/'.str_pad($no_surat[$key], 3,'0', STR_PAD_LEFT) : '' );?>
													<?$no_surat_download[$key] = ($no_surat[$key] != 0 ? $pre_po.'-'.date('Y',strtotime($created_at[$key])).'_01_'.str_pad($no_surat[$key], 3,'0', STR_PAD_LEFT).'.zip' : '');?>
													</a>
												</td>
												<td>
													<?if ($jml_action_id[$key] < $jumlah_customer[$key]) { $nilai_download--; ?>
														<b><i style='color:red' class='fa fa-times'></i></b>
													<?}else{?>
														<b><i style='color:green' class='fa fa-check'></i></b>
													<?}?>
													<?if ($nilai_download < 1) {
														$warning = "<p>Mohon selesaikan dahulu setiap batch</p>";
													}?>
												</td>
												<td>
													<?if ($no_surat_download[$key] != '' && $jml_action_id[$key] >= $jumlah_customer[$key] && $jumlah_customer[$key] > 0 ) {
														// echo $no_surat_download[$key];
														$class_color = 'red';
														if (file_exists("./fp_list/fp_".$faktur_pajak_id[$key]."/".$no_surat_download[$key])) {
															$class_color = 'blue';
														}?>
														<a href="<?=base_url()?>pajak/download_all_pajak_pdf?id=<?=$faktur_pajak_id[$key];?>" style='color:<?=$class_color;?>' class='download-pdf' ><i class='fa fa-download'></i></a>
													<?}?>

												</td>
											</tr>
											<?}?>
											</table>
										</td>
										<td style='width:100px'>
											<?if (isset($warning)) {
												echo $warning;
											}else{?>
												<a target='_blank' href="<?=base_url()?>pajak/rekap_pajak?tahun=<?=$tahun_set?>&bulan=<?=$bulan_set?>" class='btn btn-md green'>Rekap</a>
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
<script src="<?php echo base_url('assets_noondev/js/table-advanced.js'); ?>"></script>

<script>
jQuery(document).ready(function() {
	

	//==================================================================================

	$('.btn-test').click(function(){
		alert(fp_get_end($('#test1').html()));
	});


	$('#general_table').on('change','[name=tanggal_setor]',function(){
		var ini = $(this).closest('tr');
		var data = {};
		data['pembayaran_piutang_id'] = ini.find('.pembayaran_piutang_id').html();
		data['tanggal_setor'] = $(this).val();
		// var this = $(this);
		var url = 'finance/update_setor_fp';
		ajax_data_sync(url,data).done(function(data_respond  /*,textStatus, jqXHR*/ ){
			if (data_respond == 'OK') {
				
			}else{
				$(this).val('');
				alert("error mohon refresh dengan menekan F5");
			}
   		});
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
		var tgl_awal = $("#form_add_data [name=tanggal_start]").val();
		var tgl_akhir = $('#form_add_data [name=tanggal_end').val();
		var tahun_awal = tgl_awal.substring(6,10);
		var tahun_akhir = tgl_akhir.substring(6,10);
		if (tahun_awal != tahun_akhir) {
			alert("Tidak bisa beda tahun");
		}else{
			if ($('#form_add_data [name=no_fp_awal]').val() != '' && $('#form_add_data [name=jml_fp]').val() != '' ) {
				$('#form_add_data').submit();
			};
		}

	});

	//======================================================================

	$("#select-tahun-laporan").change(function(){
		$(".loading").show();
		$('#form-tahun-laporan').submit();
	});

	$("#general_table").on("click",'.download-pdf', function(){
		$(this).css('color','blue');
	});

});


</script>
