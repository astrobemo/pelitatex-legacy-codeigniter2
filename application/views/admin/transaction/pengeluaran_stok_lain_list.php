<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css'); ?>"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css'); ?>"/>
<?=link_tag('assets/global/plugins/bootstrap-datepicker/css/datepicker3.css'); ?>
<link href="<?=base_url('assets/global/plugins/bootstrap-modal/css/bootstrap-modal.css');?>" rel="stylesheet" type="text/css"/>

<?=link_tag('assets/global/plugins/select2/select2.css'); ?>

<style type="text/css">
#general_table tr th{
	vertical-align: middle;
	/*text-align: center;*/
}

#general_table tr td{
	color:#000;
}

.batal{
	background: #ccc;
}
</style>

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
							<!-- <select class='btn btn-sm btn-default' name='status_select' id='status_select'>
								<option value="1" selected>Aktif</option>
								<option value="2">Batal</option>

							</select> -->
						</div>
					</div>
					<div class="portlet-body">
						<table>
							<tr>
								<td>Filter</td>
								<td class='padding-rl-5'> : </td>
								<td>
									<select name='status_aktif' id="status_select">
										<option <?=($status_aktif==1 ? 'selected' : '');?> value='1'>Aktif</option>
										<option <?=($status_aktif==-1 ? 'selected' : '');?> value='-1'>Batal</option>
										<option <?=($status_aktif==2 ? 'selected' : '');?> value='2'>Kosong/Belum Register</option>
									</select>
								</td>
							</tr>
						</table>
						<hr>
						<!-- table-striped table-bordered  -->
						<table class="table table-hover table-bordered" id="general_table">
							<thead>
								<tr>
									<th scope="col">
										Tanggal
									</th>
									<th scope="col">
										No Faktur
									</th>
									<th scope="col" class='text-center'>
										Keterangan
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
								<?foreach ($pengeluaran_stok_lain_list as $row) {?>
									<tr>
										<td><?=is_reverse_date($row->tanggal);?></td>
										<td><?=($row->no_faktur !='' ? "RR".date("y",strtotime($row->tanggal))."/".date("m",strtotime($row->tanggal))."-".$row->no_faktur : '');?></td>
										<td><?=$row->keterangan?></td>
										<td><?=number_format($row->g_total,'0',',','.');?></td>
										<td>
											<span class='id' hidden><?=$row->id;?></span>
											<a class='btn btn-xs green' href="<?=base_url().is_setting_link('transaction/pengeluaran_stok_lain_list_detail');?>?id=<?=$row->id?>"><i class='fa fa-edit'></i></a> 
											<button class='btn btn-xs red btn-remove'><i class='fa fa-times'></i></button> 
										</td>
									</tr>
								<?}?>
							</tbody>
						</table>
						<!-- <button class='btn blue btn-test'>test</button> -->
					</div>
				</div>
			</div>
		</div>
	</div>			
</div>

<script src="<?php echo base_url('assets/global/plugins/bootstrap-modal/js/bootstrap-modalmanager.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/bootstrap-modal/js/bootstrap-modal.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/jquery-validation/js/jquery.validate.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/bootbox/bootbox.min.js'); ?>" type="text/javascript"></script>

<script src="<?php echo base_url('assets/global/plugins/datatables/media/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets_noondev/js/table-advanced.js'); ?>"></script>
<script src="<?php echo base_url('assets_noondev/js/fnReload.js');?>"></script>


<script>
jQuery(document).ready(function() {
	// Metronic.init(); // init metronic core components
	// Layout.init(); // init current layout
	$("#general_table").DataTable({
		"ordering":false,
	});

	$("#general_table").on("click", '.btn-remove', function(){
		var ini = $(this).closest('tr');
		var id = ini.find('.id').html();
		bootbox.confirm("Yakin menghapus ini ?", function(respond){
			if (respond) {
				var data_st = {};
				var url = "transaction/pengeluaran_stok_lain_remove";
				data_st['id'] = id;
				
				ajax_data_sync(url,data_st).done(function(data_respond  /*,textStatus, jqXHR*/ ){
					if (data_respond == 'OK') {
						ini.remove();
					};
		   		});
			};
		});
	});	

});
</script>
