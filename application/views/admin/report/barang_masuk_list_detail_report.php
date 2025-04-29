<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css'); ?>"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css'); ?>"/>
<?=link_tag('assets/global/plugins/bootstrap-datepicker/css/datepicker3.css'); ?>


<div class="page-content">
	<div class='container'>

		<div class="row margin-top-10">
			<div class="col-md-12">
				<div class="portlet light">
					<div class="portlet-body">			
					<h1><?=$nama_barang.' '.$nama_warna;?></h1>
					<hr/>			
						<table class="table table-striped table-bordered table-hover" id="general_table">
							<thead>
								<tr>
									<th scope="col" style='width:25px !important'>
										No
									</th>
									<th scope="col">
										Tanggal
									</th>
									<th scope="col">
										No Faktur
									</th>
									<th scope="col">
										Qty
									</th>
									<th scope="col">
										Roll
									</th>
									<th scope="col">
										Harga
									</th>
								</tr>
							</thead>
							<tbody>
								<?
								$idx = 1; $qty_total = 0; $roll_total = 0;
								foreach ($barang_list as $row) { 
									$qty_total += $row->qty;
									$roll_total += $row->jumlah_roll;
									?>
									<tr>
										<td><?=$idx;?></td>
										<td><span hidden><?=$row->tanggal?></span> <?=is_reverse_date($row->tanggal);?></td>
										<td>
											<?=$row->no_faktur;?>
										</td>
										<td>
											<span class='qty'><?=number_format($row->qty,'2',',','.');?></span> 
										</td>
										<td>
											<?=$row->jumlah_roll;?>
										</td>
										<td>
											<?=number_format($row->harga_beli,'2',',','.');?>
										</td>
									</tr>
								<? 
								$idx++;
								} ?>


								<tr style='font-size:1.1em; font-weight:bold;'>
									<td></td>
									<td hidden></td>
									<td colspan='2'>TOTAL</td>
									<td><?=number_format($qty_total,'2',',','.');?></td>
									<td><?=number_format($roll_total,'0',',','.')?></td>
									<td></td>
								</tr>

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
<script src="<?php echo base_url('assets/global/plugins/select2/select2.min.js'); ?>" type="text/javascript" ></script>

<script>
jQuery(document).ready(function() {
	// Metronic.init(); // init metronic core components
	// Layout.init(); // init current layout
	// TableAdvanced.init();

	$("#general_table").DataTable({
		"ordering":false,
		// "bFilter":false
	});
	
	$('#barang_select').select2();

	// oTable = $('#general_table').DataTable();
	// oTable.state.clear();
	// oTable.destroy();
});
</script>
