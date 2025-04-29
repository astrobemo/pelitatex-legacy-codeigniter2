<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css'); ?>"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css'); ?>"/>
<?php echo link_tag('assets/global/plugins/bootstrap-datepicker/css/datepicker3.css'); ?>

<div class="page-content">
	<div class='container'>

		<div class="modal fade" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body">
						<form action="<?=base_url('pajak/insert_fp_manual')?>" class="form-horizontal" id="form-fp-manual" method="post">
							<h3 class='block'> Cari Faktur</h3>
							
							<div class="form-group">
			                    <label class="control-label col-md-3">No Faktur<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
									<input name='id' hidden>
									<input name='no_faktur_pajak_id' hidden value='<?=$no_faktur_pajak_id?>'>
									<input type="text" readonly name='no_faktur_pajak' class="form-control">
			                    </div>
			                </div>

			                <div class="form-group">
			                    <label class="control-label col-md-3">Keterangan<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
									<input type="text" name='keterangan' class="form-control">
			                    </div>
			                </div>
		                </form>                
					</div>

					<div class="modal-footer">
						<button type="button" class="btn blue btn-save-faktur">SAVE</button>
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
							function fp_get_lembar($number, $number_end){
								$fp_num = filter_var($number,FILTER_SANITIZE_NUMBER_INT);
								$fp_num_end=filter_var($number_end,FILTER_SANITIZE_NUMBER_INT);
								$pad_length = $fp_num_end - $fp_num + 1;
								return $pad_length;
							}

							$jml_lmbr = 0;
							$no_fp_awal = 0;
							$no_fp_akhir = 0;
							$fp_terpakai=0;
							$fp_terpakai_manual=0;
							$fp_kosong=0;
							foreach ($fp_list as $row) {
								$dt_fp_awal = explode('.', $row->no_fp_awal);
								$dt_fp_akhir = explode('.', $row->no_fp_akhir);
								$pre_no = str_replace('.'.end($dt_fp_awal), '', $row->no_fp_awal);
							}
							$jml_lmbr = fp_get_lembar(end($dt_fp_awal), end($dt_fp_akhir));

							foreach ($fp_link_list as $row) {
								$nf[$row->no_faktur_pajak] = $row;
								if ($row->rekam_faktur_pajak_id == 0) {
									$fp_terpakai_manual++;
								}else{
									$fp_terpakai++;
								}
							}
						?>
						<p>INFO</p>
						<table>
							<tr>
								<td>Terpakai</td>
								<td style='padding:0 5px;'>:</td>
								<td><?=$fp_terpakai;?></td>
							</tr>
							<tr>
								<td>Manual</td>
								<td style='padding:0 5px;'>:</td>
								<td><?=$fp_terpakai_manual;?></td>
							</tr>
							<tr>
								<td>Kosong</td>
								<td style='padding:0 5px;'>:</td>
								<td><?=$jml_lmbr - $fp_terpakai_manual - $fp_terpakai;?></td>
							</tr>
						</table>
						<hr>
						<table class="table table-hover table-striped table-bordered" id="general_table">
							<thead>
								<tr>
									<th scope="col">
										No Faktur Pajak
									</th>
									<th scope="col">
										No Invoice
									</th>
									<th scope="col">
										Customer
									</th>
									<th scope="col">
										Actions
									</th>
								</tr>
							</thead>
							<tbody>
								<? for ($i=0; $i < $jml_lmbr ; $i++) { 
									$index = $pre_no.'.'.(end($dt_fp_awal)+$i) ?>
									<tr>
										<td>
											<span class='no-faktur-pajak'><?=$index;?></span>
										</td>
										<?if (isset($nf[$index]) ) {
											$id = $nf[$index]->id;
											$keterangan = $nf[$index]->keterangan;
											$rekam_faktur_pajak_id = $nf[$index]->rekam_faktur_pajak_id;?>
											<?if ($nf[$index]->rekam_faktur_pajak_id != 0) {?>
												<td><?=$nf[$index]->no_invoice;?></td>
												<td><?=$nf[$index]->nama_customer?></td>
											<?}else{?>
												<td colspan='2' style='text-align:center; background:#ffe3bd'><?=$keterangan;?></td>
												<td hidden></td>
											<?}?>
										<?}else{
											$id='';
											$keterangan = ''?>
											<td></td>
											<td></td>
										<?}?>
										<td>
											<span class='id' hidden><?=$id;?></span>
											<span class='keterangan' hidden><?=$keterangan;?></span>
											<?if (!isset($nf[$index]) || $rekam_faktur_pajak_id == 0 ) {?>
												<a href="#portlet-config" data-toggle="modal" class='btn btn-xs green btn-edit'><i class='fa fa-edit'></i> Manual</a>
											<?}else{?>
											<i class='fa fa-ban'></i>
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
<script src="https://cdn.datatables.net/plug-ins/1.10.21/pagination/input.js"></script>


<script>
jQuery(document).ready(function() {
	$("#general_table, #general_table-1").DataTable({
		"ordering":false,
		"pagingType": "input"
		// "bFilter":false
	});

	$("#general_table").on('click','.btn-edit', function() {
		let ini = $(this).closest('tr');
		let form = $("#form-fp-manual");
		form.find("[name=id]").val(ini.find('.id').html());
		form.find("[name=no_faktur_pajak]").val(ini.find('.no-faktur-pajak').html());
		form.find("[name=keterangan]").val(ini.find('.keterangan').html());
	});

	$(".btn-save-faktur").click(function (argument) {
		let form = $("#form-fp-manual");
		let keterangan = form.find("[name=keterangan]").val();
		if (keterangan != '') {
			btn_disabled_load($('.btn-save-faktur'));
			form.submit();
		}else{
			alert('Mohon isi keterangan');
		}

	})
});

</script>
