<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css'); ?>"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css'); ?>"/>
<?php echo link_tag('assets/global/plugins/bootstrap-datepicker/css/datepicker3.css'); ?>
<link href="<?=base_url('assets/global/plugins/bootstrap-modal/css/bootstrap-modal.css');?>" rel="stylesheet" type="text/css"/>

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
					</div>
					<div class="portlet-body">
						<form>
							<table>
								<tr>
									<td>Tanggal</td>
									<td class='padding-rl-5'>:</td>
									<td>
										<input name='tanggal_awal' class='text-center date-picker' value='<?=$tanggal_awal?>' style='width:110px' > s/d
										<input name='tanggal_akhir' class='text-center date-picker' value='<?=$tanggal_akhir?>' style='width:110px' >
									</td>
								</tr>
								<tr>
									<td></td>
									<td class='padding-rl-5'></td>
									<td><button class='btn btn-xs default' style='margin:10px 0px; width:100%'><i class='fa fa-search'></i></button></td>
								</tr>
							</table>
						</form>
						<hr/>
						<table class="table table-hover table-striped table-bordered" id="general_table">
							<thead>
								<tr>									
									<th scope="col">
										Tanggal
									</th>
									<th scope="col">
										Jatuh Tempo
									</th>
									<th scope="col">
										Supplier
									</th>
									<th scope="col">
										Tipe
									</th>
									<th scope="col">
										No Faktur Beli
									</th>
									<th scope="col">
										Nilai
									</th>						
									<th scope="col">
										Actions
									</th>
								</tr>
							</thead>
							<tbody>
								<?foreach ($pembayaran_list as $row) { ?>
									<tr>
										<td>
											<?=date('d F Y',strtotime($row->tanggal_transfer));?>
										</td>
										<td>
											<?=date('d F Y',strtotime($row->jatuh_tempo));?>
										</td>
										<td><?=$row->nama_supplier;?></td>
										<td>
											<?if ($row->pembayaran_type_id == 1) {?>
												Transfer
												<?=$row->nama_bank;?>
												<?=$row->no_rek_bank;?>
											<?}elseif ($row->pembayaran_type_id == 2) {?>
												GIRO : 
												<?=$row->no_giro;?>
												<?=$row->nama_bank;?>
												<?=$row->no_rek_bank;?>
											<?}elseif ($row->pembayaran_type_id == 3) {?>
												CASH
											<?}elseif ($row->pembayaran_type_id == 4) {?>
												
											<?}?>
										</td>
										<td>
											<div style='column-count:2'>
												<?=$row->no_faktur_beli;?>
											</div>
										</td>
										<td>
											<?=number_format($row->amount,'0',',','.');?>
										</td>
										<td>
											<a href="<?=base_url().is_setting_link('report/tutup_buku_list_detail');?>?id=<?=$row->id;?>" target="_blank"></a>
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

<script src="<?php echo base_url('assets/global/plugins/bootstrap-modal/js/bootstrap-modalmanager.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/bootstrap-modal/js/bootstrap-modal.js'); ?>" type="text/javascript"></script>

<script src="<?php echo base_url('assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/datatables/media/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/bootbox/bootbox.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets_noondev/js/table-advanced.js'); ?>"></script>

<script>
jQuery(document).ready(function() {

	// dataTableTrue();

	TableAdvanced.init();

	// oTable = $('#general_table').DataTable();
	// oTable.state.clear();
	// oTable.destroy();

	$('.btn-save').click(function(){
		if ($('#form_add_data [name=tanggal]').val() != '' ) {
			// $('#form_add_data').submit();
			if ($('#form_add_data [name=tanggal]').prop('selectedIndex') != 0) {
				bootbox.confirm("Disarankan untuk membuat penutupan <b>sesuai urutan bulan</b>. <br/>Yakin lanjutkan ?", function(respond){
					if (respond) {
						$('#form_add_data').submit();
					};
				});
			}else{
				$('#form_add_data').submit();
			};
		}else{
			alert('Tanggal harus diisi');
		}
	});

});
</script>
