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
						<div class="actions hidden-print">
							<a href="#portlet-config" data-toggle='modal' class="btn btn-default btn-sm">
							<i class="fa fa-plus"></i> Baru </a>
						</div>
					</div>
					<div class="portlet-body">
						<div style='font-size:1.4em'>
							<table>
							<?foreach ($po_pembelian_data as $row) {?>
							<tr>
								<td>Supplier</td>
								<td class='padding-rl-5'> : </td>
								<td><?=$row->nama_supplier?></td>
							</tr>
							<tr>
								<td>Tanggal</td>
								<td class='padding-rl-5'> : </td>
								<td><?=is_reverse_date($row->tanggal);?></td>
							</tr>
							<tr>
								<td>No PO</td>
								<td class='padding-rl-5'> : </td>
								<td><?=$row->po_number;?> - <?=$batch;?></td>
							</tr>

							<?}?>
							</table>
						</div>
						<hr/>
						
						<table class="table table-hover table-striped table-bordered" id="general_table">
							<thead>
								<tr>									
									<th scope="col">
										Nama Barang
									</th>
									<th scope="col">
										Qty PO
									</th>
									<th scope="col">
										Qty Datang
									</th>
									<th scope="col">
										Qty Retur
									</th>
									<th scope="col">
										Qty Sisa
									</th>
									<!-- <th scope="col">
										Action
									</th> -->
								</tr>
							</thead>
							<tbody>
								<?$qty_po = 0; $qty_datang=0; $qty_retur =0; 
								foreach ($po_gantung_detail as $row) { 
									$qty_po += $row->qty;
									$qty_datang += $row->qty_beli;
									$qty_retur += $row->qty_retur;
									?>
									<tr>
										<td>
											<?=$row->nama_barang;?>
											<?=$row->nama_warna;?>
										</td>
										<td>
											<?=str_replace(',00', '', number_format($row->qty,'2',',','.')); ?>
											<?=$row->nama_satuan;?>
										</td>
										<td>
											<?=str_replace(',00', '', number_format($row->qty_beli,'2',',','.'));?>
											<?=$row->nama_satuan;?>
										</td>
										<td>
											<?=str_replace(',00', '', number_format($row->qty_retur,'2',',','.'));?>

											<?=$row->nama_satuan;?>
										</td>
										<td>
											<?=str_replace(',00', '', number_format($row->qty - $row->qty_beli + $row->qty_retur,'2',',','.'));?>

											<?=$row->nama_satuan;?>
										</td>
										<!-- <td>

										</td> -->
									</tr>
								<?}?>
							</tbody>
						</table>
						<hr/>
						<table class='table table-bordered' STYLE='font-size:1.2em'>
							<tr>
								<th rowspan='2' style='vertical-align:middle; text-align:center
								'>TOTAL</th>
								<th>PO</th>
								<th>Datang</th>
								<th>Retur</th>
								<th>Sisa</th>
							</tr>
							<tr>
								<td><?=number_format($qty_po,'0',',','.');?></td>
								<td><?=number_format($qty_datang,'0',',','.');?></td>
								<td><?=number_format($qty_retur,'0',',','.');?></td>
								<td><?=number_format($qty_po - $qty_datang + $qty_retur,'0',',','.');?></td>
							</tr>
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
