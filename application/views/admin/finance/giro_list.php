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
						<!-- <div class="actions">
							<a href="#portlet-config" class="btn btn-default btn-sm btn-form-add">
							<i class="fa fa-plus"></i> Data Baru </a>
						</div> -->
					</div>
					<div class="portlet-body">
						<form action='' method='get'>
							<h4>
								<table>
									<tr>
										<td>Tanggal Terima:</td>
										<td class='padding-rl-10'>:</td>
										<td>
											<b>
												<input name='tanggal_start' readonly class='date-picker' style='border:none; border-bottom:1px solid #ddd; width:100px;' value='<?=$tanggal_start;?>'>
												s/d
												<input name='tanggal_end' readonly class='date-picker' style='border:none; border-bottom:1px solid #ddd; width:100px;' value='<?=$tanggal_end;?>'>
											</b>
										</td>
									</tr>
									<tr>
										<td>Tipe:</td>
										<td class='padding-rl-10'>:</td>
										<td>
											<b>
												<select name='filter_type'>
													<option value=''>All</option>
													<option value='a1' <?=($filter_type == 'a1' ? 'selected' : '');?> >Belum Setor</option>
													<option value='a2' <?=($filter_type == 'a2' ? 'selected' : '');?> >Sudah Setor</option>
												</select>
											</b>
										</td>
									</tr>
									<tr>
										<td></td>
										<td></td>
										<td>
											<button style='margin-top:5px; width:100%' class='btn btn-xs default'><i class='fa fa-search'></i></button>
										</td>
									</tr>
								</table>
							</h4>
						</form>
						<hr/>
						<table class="table table-hover table-striped table-bordered" id="general_table">
							<thead>
								<tr>
									<th scope="col">
										Urutan
									</th>
									<th scope="col">
										Tanggal
									</th>
									<th scope="col">
										No Giro
									</th>
									<!-- <th scope="col">
										No Akun Giro
									</th> -->
									<th scope="col">
										Nama Bank
									</th>
									<th scope="col">
										Jatuh Tempo
									</th>
									<th scope="col">
										Nama Customer
									</th>
									<th scope="col">
										Nominal
									</th>
									<th scope="col">
										Tanggal Setor
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
										<td class='text-center'>
											<b><span style='background: #0ff; padding: 1px 5px'><?=$row->urutan?></span> </b>
										</td>
										<td>
											<?=is_reverse_date($row->tanggal);?>
										</td>
										<td>
											<?=$row->no_giro;?>
										</td>
										<!-- <td>
											<?=$row->no_akun_giro;?>
										</td> -->
										<td>
											<?=$row->nama_bank;?>
										</td>
										<td>
											<?=is_reverse_date($row->jatuh_tempo);?>
										</td>
										<td>
											<?=$row->nama_customer;?>
										</td>
										<td>
											<?=number_format($row->amount,'0',',','.');?>
										</td>
										<td>
											<?=is_reverse_date($row->tanggal_setor);?>
											<!-- <input name='tanggal_setor' class='date-picker' value="<??>" style='width:90px;'> -->
										</td>
										<td>
											<?//=$row->keterangan;?>
											<?if ($row->data_type == 1) {?>
												Pembayaran Piutang
											<?}elseif ($row->data_type == 2){?>
												DP
											<?}elseif ($row->data_type == 3) {?>
												Pembayaran Piutang 2019	
											<?}?>
										</td>
										<td>
											<span class='pembayaran_piutang_id' hidden><?=$row->pembayaran_piutang_id?></span>
											<?if ($row->data_type == 1) {?>
												<a href="<?=base_url().is_setting_link('finance/piutang_payment_form').'/?id='.$row->pembayaran_piutang_id;?>" class="btn btn-xs blue" target='_blank'><i class='fa fa-search'></i></a>
											<?}else{?>
												<a href="<?=base_url().is_setting_link('transaction/dp_list_detail').'/'.$row->customer_id;?>" class="btn btn-xs blue" target='_blank'><i class='fa fa-search'></i></a>
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

	// dataTableTrue();

	// TableAdvanced.init();

	// oTable = $('#general_table').DataTable();
	// oTable.state.clear();
	// oTable.destroy();

	$("#general_table").DataTable({
		"ordering":false,
		// "bFilter":false
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

});
</script>
