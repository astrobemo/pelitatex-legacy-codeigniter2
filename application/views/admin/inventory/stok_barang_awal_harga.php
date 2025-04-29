<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css'); ?>"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css'); ?>"/>

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
							<select class='btn btn-sm btn-default' name='status_aktif_select' id='status_aktif_select'>
								<option value="1" selected>Aktif</option>
								<option value="0">Tidak Aktif</option>
							</select>
							<a href="#portlet-config" data-toggle='modal' class="btn btn-default btn-sm btn-form-add">
							<i class="fa fa-plus"></i> Tambah </a>
						</div>
					</div>
					<div class="portlet-body">
						<table class="table table-striped table-bordered table-hover" id="general_table">
							<thead>
								<tr>
									<th scope="col">
										Nama Jual
									</th>
									<th scope="col">
										Warna
									</th>
									<?if (is_posisi_id() == 1) {?>
										<th scope="col">
											BarangID
										</th>
										<th scope="col">
											WarnaID
										</th>
									<?}?>
									<th scope="col">
										Harga
									</th>
									<th scope="col">
										Action
									</th>
								</tr>
							</thead>
							<tbody>
								<?foreach ($stok_awal_list as $row) { ?>
									<tr>
										<td>
											<?=$row->nama_barang;?> <?=(is_posisi_id() == 1 ? ' / '.$row->nama_beli : '')?>
										</td>
										<td>
											<?=$row->nama_warna;?>
										</td>
										<?if (is_posisi_id() == 1) {?>
											<td><?=$row->barang_id;?></td>
											<td><?=$row->warna_id;?></td>
										<?}?>

										<td>
											<input name='harga_stok_awal' class='amount-number harga_stok_awal' value='<?=number_format($row->harga_stok_awal,'0',',','.');?>' style='width:70px; text-align:right; padding-right:5px'> 
										</td>
										<td>
											<span class='id' hidden><?=$row->id;?></span>
											<span class='barang_id' hidden><?=$row->barang_id;?></span> 
											<span class='warna_id' hidden ><?=$row->warna_id;?></span><a href="<?=base_url('inventory/mutasi_stok_awal_delete')?>?id=<?=$row->id;?>" tabindex='-1' class='btn btn-xs red btn-del'><i class='fa fa-times'></i></a>
										</td>
										
									</tr>
								<? } ?>

							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>			
</div>

<script src="<?php echo base_url('assets/global/plugins/datatables/media/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets_noondev/js/table-advanced.js'); ?>"></script>

<script>
jQuery(document).ready(function() {
	
	// oTable = $('#general_table').DataTable();
	// oTable.state.clear();
	// oTable.destroy();

	$("#general_table").DataTable({
		"ordering":false,
		// "bFilter":false
	});

	$('.btn-save').click(function(){
   		if( $('#form_add_data [name=nama]').val() != '' ){
   			$('#form_add_data').submit();
   		}
   	});

   	$('.btn-edit-save').click(function(){
   		if( $('#form_edit_data [name=nama]').val() != ''){
   			$('#form_edit_data').submit();
   		}
   	});

   	$("#general_table").on("change",'.harga_stok_awal', function(){
   		var ini = $(this).closest('tr');
   		var data = {};
   		data['barang_id'] = ini.find('.barang_id').html();
   		data['warna_id'] = ini.find('.warna_id').html();
   		data['harga_stok_awal'] = ini.find('[name=harga_stok_awal]').val();
		var url = "inventory/harga_stok_awal_update";
   		ajax_data_sync(url,data).done(function(data_respond  /*,textStatus, jqXHR*/ ){
   			if(data_respond == 'OK'){
   				notific8('lime', 'OK');
   			}else{
   				notific8('ruby', 'ERROR !!');
   			}
   		});
   	});
});
</script>
