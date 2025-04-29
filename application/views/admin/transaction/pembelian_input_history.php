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
									<td>Tanggal Input</td>
									<td>
										<div class="input-group input-large date-picker input-daterange">
											<input type="text" style='border:none; border-bottom:1px solid #ddd; background:white' class="form-control" name="from" value='<?=is_reverse_date($from); ?>'>
											<span class="input-group-addon">
											s/d </span>
											<input type="text" style='border:none; border-bottom:1px solid #ddd; background:white' class="form-control" name="to" value='<?=is_reverse_date($to); ?>'>
										</div>
									</td>
									<td>
										<button class='btn btn-sm green'><i class='fa fa-search'></i></button>
									</td>
								</tr>
							</table>
						</form>
						<hr/>
						<table class="table table-hover table-striped table-bordered" id="general_table">
							<thead>
								<tr>
									<th scope="col">
										Tanggal Input
									</th>
									<th scope="col">
										Tanggal Beli
									</th>
									<th scope="col">
										No Faktur
									</th>
									<th scope="col">
										Toko
									</th>
									<th scope="col">
										Gudang
									</th>
									<th scope="col">
										Total
									</th>
									<th scope="col">
										Supplier
									</th>
									<th scope="col">
										Status Aktif
									</th>
									<th scope="col">
										Input By 
									</th>
									<th scope="col" >
										Actions
									</th>									
									
								</tr>
							</thead>
							<tbody>
								<?foreach ($history as $row) { ?>
									<tr>
										<td>
											<?=is_reverse_datetime($row->created);?>
										</td>
										<td>
											<?=is_reverse_date($row->tanggal);?>
										</td>
										<td>
											<?=$row->no_faktur;?>
										</td>
										<td>
											<?=$row->toko;?>
										</td>
										<td>
											<?=$row->gudang?>
										</td>
										<td>
											<?=number_format($row->total,'0',',','.');?>
										</td>
										<td>
											<?=$row->supplier;?>
										</td>
										<td>
											<?if ($row->status_aktif == 1) { ?>
												<span class='badge badge-info'>Aktif</span>
											<?}elseif ($row->status_aktif == 0) { ?>
												<span class='badge badge-danger'>Batal</span>
											<?}?>
										</td>
										<td>
											<?=$row->username;?>
										</td>
										<td>
											<a href="<?=base_url().rtrim(base64_encode('transaction/pembelian_list_detail'),'=').'/'.$row->id;?>" class="btn btn-xs blue"><i class='fa fa-search'></i></a>
											<?if (is_posisi_id() < 3) {?>
												<button class='btn-xs btn red btn-remove' class="removeBeli('<?=$row->id;?>')" ><i class='fa fa-times'></i> </a>
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
<script src="<?php echo base_url('assets/global/plugins/bootbox/bootbox.min.js'); ?>" type="text/javascript"></script>

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

function removeBeli(id){
	bootbox.confirm("Yakin membatalkan pembelian ini ? ", function(respond){
		
	});
}

</script>
