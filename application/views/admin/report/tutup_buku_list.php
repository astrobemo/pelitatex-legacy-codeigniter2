<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css'); ?>"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css'); ?>"/>
<?php echo link_tag('assets/global/plugins/bootstrap-datepicker/css/datepicker3.css'); ?>
<link href="<?=base_url('assets/global/plugins/bootstrap-modal/css/bootstrap-modal.css');?>" rel="stylesheet" type="text/css"/>

<div class="page-content">
	<div class='container'>

		<div class="modal fade" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body">
						<form action="<?=base_url('report/tutup_buku_insert')?>" class="form-horizontal" id="form_add_data" method="post">
							<h3 class='block'> Data Baru</h3>
							
							<div class="form-group">
			                    <label class="control-label col-md-3">Tanggal<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<select class='input1 form-control' name="tanggal">
			                    		<?for ($i=$tahun_awal; $i <=$tahun_now ; $i++) { 
			                    			$month_end = ($i < $tahun_now ? 12 : date('n') - 1);
			                    			for ($j=1; $j <= $month_end ; $j++) { 
			                    				if (!isset($tgl_tutup[$i][$j])) {?>
			                    					<option value='<?=$i;?>-<?=$j?>'><?=date('F Y', strtotime($i.'-'.$j.'-1'))?></option>
			                    				<?}
			                    			}
			                    		}?>
			                    	</select>
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
										Tanggal Tutup Buku
									</th>
									<th scope="col">
										By
									</th>
									<th scope="col">
										Update
									</th>
									<th scope="col">
										Actions
									</th>
								</tr>
							</thead>
							<tbody>
								<?foreach ($tutup_buku_list as $row) { ?>
									<tr>
										<td>
											<?=date('F Y',strtotime($row->tanggal));?>
										</td>
										<td>
											<?;?>
										</td>
										<td>
											<?=$row->created;?>
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
