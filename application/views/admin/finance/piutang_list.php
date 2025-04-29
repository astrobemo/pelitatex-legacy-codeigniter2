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
					</div>
					<div class="portlet-body">
						<form>
							<table>
								<tr>
									<td>Filter</td>
									<td class='padding-rl-5'> : </td>
									<td>
										<select name="view_type">
											<option value='1' <?=($view_type == 1 ? 'selected' : '');?> > Semua </option>
											<option value='2' <?=($view_type == 2 ? 'selected' : '');?> > By Tanggal Transaksi </option>
										</select>
									</td>
								</tr>
								<tr class='tanggal-filter' <?=($view_type==1 ? 'hidden' : '' );?> >
									<td>Hingga Transaksi Tanggal </td>
									<td class='padding-rl-5'> : </td>
									<td>
										<input readonly name='tanggal' class='date-picker' value="<?=$tanggal_filter?>">
									</td>
								</tr>
								<tr>
									<td></td>
									<td class='padding-rl-5'> </td>
									<td>
										<button style="width:100%; margin-top:10px" class='default' >Filter</button>
									</td>
								</tr>
							</table>
						</form>
						<hr/>
						
						<table class="table table-hover table-striped table-bordered" id="general_table">
							<thead>
								<tr>									
									<th scope="col">
										Nama Customer
									</th>
									<th scope="col" hidden>
										Jumlah Faktur
									</th>
									<th scope="col">
										Piutang
									</th>
									<?if ($view_type == 1) {?>
										<th scope="col" hidden>
											Kontra Bon
										</th>
										<th scope="col">
											Outstanding
										</th>
										<th scope="col">
											Actions
										</th>
									<?}?>
								</tr>
							</thead>
							<tbody>
								<?$piutang_total = 0;
								foreach ($piutang_list as $row) { ?>
									<tr>
										<td>
											<?=$row->nama_customer;?>
										</td>
										<td hidden>
											<?=$row->jml_trx?>
										</td>
										<td>
											<?$piutang_total += $row->sisa_piutang;?>
											<?=number_format($row->sisa_piutang,'0',',','.');?>
										</td>
										<?if ($view_type == 1) {?>
											<td>
												<?=number_format($row->kontra_bon,'0',',','.');?>
											</td>
											<td hidden>
												<?=number_format($row->outstanding,'0',',','.');?>
											</td>
											<td>
												<a target='_blank' href="<?=base_url().is_setting_link('finance/piutang_payment_form').'/?customer_id='.$row->customer_id;?>&toko_id=<?=$row->toko_id;?>&tanggal_start=<?=$row->tanggal_start;?>&tanggal_end=<?=$row->tanggal_end;?>" class="btn btn-xs blue"> Kontra Bon Baru</a>
												<a target="_blank" href="<?=base_url().is_setting_link('finance/outstanding_list_detail').'/?customer_id='.$row->customer_id;?>&toko_id=<?=$row->toko_id;?>" class="btn btn-xs yellow-gold">Outstanding</a>
												<?if ($view_type == 1) {?>
												<?}else{?>
												<?}?>
											</td>
										<?}?>
									</tr>
								<?}?>
							</tbody>
						</table>
						<table class='table' style='font-size:1.5em'>
							<tr>
								<th>TOTAL</th>
								<th>
									<?=number_format($piutang_total,'0',',','.');?>
								</th>
							</tr>
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

	TableAdvanced.init();

	// oTable = $('#general_table').DataTable();
	// oTable.state.clear();
	// oTable.destroy();

	$('.btn-save').click(function(){
		if ($('#form_add_data [name=tanggal]').val() != '' && $('#form_add_data [name=amount]').val() != '' ) {
			$('#form_add_data').submit();
		}else{
			alert('Tanggal dan Jumlah harus diisi');
		}
	});

	$("[name=view_type]").change(function(){
		if($(this).val() == 2){
			$(".tanggal-filter").show();
		}else{
			$(".tanggal-filter").hide();
		}
	});

});
</script>
