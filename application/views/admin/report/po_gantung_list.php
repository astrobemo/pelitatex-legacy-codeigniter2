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
						
						<table class="table table-hover table-striped table-bordered" id="general_table">
							<thead>
								<tr>									
									<th scope="col">
										Tanggal PO
									</th>
									<th scope="col">
										Supplier
									</th>
									<th scope="col">
										PO Number
									</th>
									<th scope="col">
										Gantungan
									</th>
									<th scope="col">
										Actions
									</th>
								</tr>
							</thead>
							<tbody>
								<?foreach ($po_gantung_list as $row) { ?>
									<tr>
										<td>
											<?=is_reverse_date($row->tanggal);?>
										</td>
										<td>
											<?=$row->nama_supplier;?>
										</td>
										<td>
											<?=$row->po_number?>
										</td>
										<td>
											<?=str_replace(',00', '', number_format($row->qty_sisa,'2',',','.'));?>
										</td>
										<td>
											<a href="<?=base_url().is_setting_link('report/po_gantung_list_detail');?>?batch_id=<?=$row->id;?>&batch=<?=$row->batch?>" target="_blank" class='btn btn-xs yellow-gold'><i class='fa fa-search'></i></a>
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

	$("#general_table").DataTable({
			"ordering":false,
			// "bFilter":false
		});

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
