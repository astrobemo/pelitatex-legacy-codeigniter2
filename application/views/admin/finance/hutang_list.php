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
								<tr class='tanggal-filter'  >
									<td>Per Tanggal </td>
									<td class='padding-rl-5'> : </td>
									<td>
										<input readonly name='tanggal' class='date-picker' value="<?=$tanggal?>">
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
										Nama Supplier
									</th>
									<th scope="col">
										Hutang
									</th>
									<th scope="col">
										Retur
									</th>
									<th scope="col">
										Outstanding<br/>
										<small>(Giro/Cek Belum Cair)</small>
									</th>
									<th scope="col">
										Total
									</th>
									<th scope="col">
										Actions
									</th>
								</tr>
							</thead>
							<tbody>
								<?$sisa_hutang = 0; $sisa_retur = 0; $sisa_outstanding = 0;
								foreach ($hutang_list as $row) { 
									if ($row->sisa_hutang != 0 || $row->sisa_retur != 0 || $row->amount_outstanding != 0) {
										$sisa_hutang += $row->sisa_hutang;
										$sisa_retur += $row->sisa_retur;
										$sisa_outstanding += $row->amount_outstanding;?>
										<tr>
											<td>
												<?=$row->nama_supplier;?>
											</td>
											<td>
												<?=number_format($row->sisa_hutang,'0',',','.');?>
											</td>
											<td>
												<?=number_format($row->sisa_retur,'0',',','.');?>
											</td>
											<td>
												<?=number_format($row->amount_outstanding,'0',',','.');?>
											</td>
											<td style='font-size:1.1em'>
												<?=number_format($row->sisa_hutang - $row->sisa_retur + $row->amount_outstanding,'0',',','.');?>
											</td>
											<td>
												<a target="_blank" href="<?=base_url().rtrim(base64_encode('finance/hutang_list_detail'),'=').'/?supplier_id='.$row->supplier_id;?>&tanggal=<?=$tanggal?>" class="btn btn-xs blue" onclick="window.open(this.href, 'newwindow', 'width=1050, height=750'); return false;"><i class='fa fa-search'></i>Hutang</a>
												<a target="_blank" href="<?=base_url().is_setting_link('finance/outstanding_hutang_list_detail').'?supplier_id='.$row->supplier_id;?>&tanggal=<?=$tanggal?>" class="btn btn-xs yellow-gold"><i class='fa fa-search'></i> Outstanding</a>
											</td>
											<td></td>
										</tr>
									<?}
									?>
								<?}?>
							</tbody>
							<tfoot>
								<tr style='font-size:1.1em'>
									<th>TOTAL</th>
									<th>
										<?=number_format($sisa_hutang,'0',',','.');?>
									</th>
									<th>
										<?=number_format($sisa_retur,'0',',','.');?>
									</th>
									<th>
										<?=number_format($sisa_outstanding,'0',',','.');?>
									</th>
									<th>
										<?=number_format($sisa_hutang+$sisa_retur+$sisa_outstanding,'0',',','.');?>
									</th>
									<th></th>
								</tr>
							</tfoot>
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

});
</script>
