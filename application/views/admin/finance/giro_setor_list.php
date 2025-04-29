<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css'); ?>"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css'); ?>"/>
<?php echo link_tag('assets/global/plugins/bootstrap-datepicker/css/datepicker3.css'); ?>

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
							<a href="<?=base_url().is_setting_link('finance/giro_setor_list_form');?>" class="btn btn-default btn-sm btn-form-add">
							<i class="fa fa-plus"></i> Tambah </a>
						</div>


					</div>

					<div class="portlet-body">
						<!--<form action='' method='get'>
							<h4>
								Tanggal Stok:
								<b>
									<input name='tanggal_start' readonly class='date-picker' style='border:none; border-bottom:1px solid #ddd; width:100px;' value='<?=$tanggal_start;?>'>
									s/d
									<input name='tanggal_end' readonly class='date-picker2 ' style='border:none; border-bottom:1px solid #ddd; width:100px;' value='<?=$tanggal_end;?>'> 
									<button class='btn btn-xs default'><i class='fa fa-search'></i></button>
								</b>
							</h4>
						</form>-->
						<hr/>
						<table class="table table-hover table-striped table-bordered" id="general_table">
							<thead>
								<tr>
									<th scope="col">
										Tanggal Setor
									</th>
									<th scope="col">
										Jumlah Giro
									</th>
									<th scope="col">
										Nominal Total
									</th>
									<th scope="col">
										Keterangan
									</th>
									<th scope="col">
										Actions
									</th>
								</tr>
							</thead>
							<tbody>
								<?foreach ($giro_setor_list as $row) { ?>
									<tr>
										<td>
											<?=is_reverse_date($row->tanggal);?>
										</td>
										<td>
											<?=$row->count_giro;?>
										</td>
										<td>
											<?=number_format($row->total_giro,'0',',','.');?>
										</td>
										<td>
											<?=$row->keterangan;?>
										</td>
										<td>
											<a href="<?=base_url().is_setting_link('finance/giro_setor_list_form').'/?id='.$row->id;?>" class="btn btn-xs blue"><i class='fa fa-search'></i></a>
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

	// dataTableTrue();

	// TableAdvanced.init();

	// oTable = $('#general_table').DataTable();
	// oTable.state.clear();
	// oTable.destroy();

	$("#general_table").DataTable({
		"ordering":false,
		// "bFilter":false
	});

	// $('.button')

	$('#general_table').on('change','[name=tanggal_setor]',function(){
		var ini = $(this).closest('tr');
		var data = {};
		data['pembayaran_piutang_id'] = ini.find('.pembayaran_piutang_id').html();
		data['tanggal_setor'] = $(this).val();
		var url = 'finance/update_setor_giro';
		ajax_data_sync(url,data).done(function(data_respond  /*,textStatus, jqXHR*/ ){
			if (data_respond == 'OK') {
				
			}else{
				$(this).val('');
				alert("error mohon refresh dengan menekan F5");
			}
   		});
	});

});
</script>
