<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css'); ?>"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css'); ?>"/>

<div class="page-content">
	<div class='container'>

		<?
		$readonly = '';
		if (is_posisi_id() > 3) {
			$readonly = 'readonly';
		}?>

		<div class="modal fade" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body">
						<form action="<?=base_url('inventory/mutasi_stok_awal_insert')?>" class="form-horizontal" id="form_add_data" method="post">
							<h3 class='block'> Tambah </h3>
							<div class="form-group">
			                    <label class="control-label col-md-3">Nama Gudang<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<input name='id' hidden>
			                    	<select name='gudang_id' class='form-control'>
			                    		<?foreach ($this->gudang_list_aktif as $row) { ?>
			                    			<option <?if ($row->nama == 'Toko') { echo 'selected'; }?> value='<?=$row->id?>'><?=$row->nama;?></option>
			                    		<?}?>
			                    	</select>
			                    </div>				                    
			                </div>
			                <div class="form-group">
			                    <label class="control-label col-md-3">Nama Barang<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<select name='barang_id' class='form-control' id="barang_id_select">
			                    		<?foreach ($this->barang_list_aktif as $row) { ?>
			                    			<option value='<?=$row->id?>'><?=$row->nama_jual;?></option>
			                    		<?}?>
			                    	</select>
			                    </div>				                    
			                </div>

			                <div class="form-group">
			                    <label class="control-label col-md-3">Nama Warna<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<select name='warna_id' class='form-control' id='warna_id_select'>
			                    		<?foreach ($this->warna_list_aktif as $row) { ?>
			                    			<option value='<?=$row->id?>'><?=$row->warna_jual;?></option>
			                    		<?}?>
			                    	</select>
			                    </div>				                    
			                </div>

			                <div class="form-group">
			                    <label class="control-label col-md-3">Qty<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<input type="text" class="form-control" name="qty"/>
			                    </div>				                    
			                </div>

			                <div class="form-group">
			                    <label class="control-label col-md-3">Jumlah Roll<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<input type="text" class="form-control" name="jumlah_roll"/>
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
						<div class="actions">
							<select class='btn btn-sm btn-default' name='status_aktif_select' id='status_aktif_select'>
								<option value="1" selected>Aktif</option>
								<option value="0">Tidak Aktif</option>
							</select>
							<?if (is_posisi_id() < 3) { ?>
								<a href="#portlet-config" data-toggle='modal' class="btn btn-default btn-sm btn-form-add">
								<i class="fa fa-plus"></i> Tambah </a>
							<?}?>
								
						</div>
					</div>
					<div class="portlet-body">
						<table class="table table-striped table-bordered table-hover" id="general_table">
							<thead>
								<tr>
									<th scope="col">
										Lokasi Stok
									</th>
									<th scope="col">
										Nama Jual
									</th>
									<th scope="col">
										Warna
									</th>
									<th scope="col">
										Qty
									</th>
									<th scope="col">
										Jumlah Roll
									</th>
									<th scope="col">
										Action
									</th>
								</tr>
							</thead>
							<tbody>
								<?foreach ($stok_awal_list as $row) { ?>
									<tr>
										<td><span class='gudang_id' hidden><?=$row->gudang_id;?></span><?=$row->nama_gudang;?></td>
										<td>
											<?=$row->nama_barang;?>
											<span class='id' hidden><?=$row->id;?></span>
										</td>
										<td>
											<?=$row->nama_warna;?>
										</td>
										<td>
											<input <?=$readonly;?> name='qty' value='<?=$row->qty;?>' class='qty' style='width:70px; text-align:right; padding-right:5px'>
											<?=$row->nama_satuan;?> 
										</td>
										<td>
											<input <?=$readonly;?> name='jumlah_roll' value='<?=$row->jumlah_roll;?>' class='jumlah-roll' style='width:70px; text-align:right; padding-right:5px'> 
										</td>
										<td>
											<span class='barang_id' hidden><?=$row->barang_id;?></span> 
											<span class='warna_id' hidden ><?=$row->warna_id;?></span> 
											<a href="#portlet-config" data-toggle='modal' class='btn btn-xs green btn-edit'><i class='fa fa-edit'></i></a>
											<a href="<?=base_url('inventory/mutasi_stok_awal_delete')?>?id=<?=$row->id;?>" class='btn btn-xs red btn-del'><i class='fa fa-times'></i></a>
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
	// Metronic.init(); // init metronic core components
	// Layout.init(); // init current layout
	// TableAdvanced.init();

	$("#general_table").DataTable({
		"ordering":false,
		// "bFilter":false
	});

	// oTable = $('#general_table').DataTable();
	// oTable.state.clear();
	// oTable.destroy();

	$('#barang_id_select, #warna_id_select').select2();

	<?if ($data_double == 'benar') {?>
		notific8("ruby",'Data stok awal sudah ada');
	<?};?>
		// notific8("ruby",'test');

	var map = {220: false};
	$(document).keydown(function(e) {
	    if (e.keyCode in map) {
	        map[e.keyCode] = true;
	        if (map[220]) {
	            $('#portlet-config').modal('toggle');
	           	setTimeout(function(){
		    		$('#form_add_data [name=barang_id]').focus();
		    	},700);
	        }
	    }
	}).keyup(function(e) {
	    if (e.keyCode in map) {
	        map[e.keyCode] = false;
	    }
	});



	$('#status_aktif_select').change(function(){
		oTable.fnFilter( $(this).val(), 0 ); 
	});
	
   	$('#general_table').on('click', '.btn-edit', function(){
   		let form = '#form_add_data';
   		$(form+'  [name=id]').val($(this).closest('tr').find('.id').html());
   		$(form+'  [name=gudang_id]').val($(this).closest('tr').find('.gudang_id').html());
   		$(form+'  [name=barang_id]').val($(this).closest('tr').find('.barang_id').html());
   		$(form+'  [name=warna_id]').val($(this).closest('tr').find('.warna_id').html());
   		$(form+'  [name=barang_id]').change();
   		$(form+'  [name=warna_id]').change();
   		$(form+'  [name=qty]').val($(this).closest('tr').find('.qty').val());
   		$(form+'  [name=jumlah_roll]').val($(this).closest('tr').find('.jumlah-roll').val());
   	});

   	$('#general_table').on('click', '.btn-form-add', function(){
   		let form = '#form_add_data';
   		$(form+'  [name=id]').val('');
   		$(form+'  [name=gudang_id]').val('');
   		$(form+'  [name=barang_id]').val('');
   		$(form+'  [name=warna_id]').val('');
   		$(form+'  [name=qty]').val('');
   		$(form+'  [name=jumlah_roll]').val('');
   	});

   	$('#general_table').on('click', '.btn-remove', function(){
   		var data = status_aktif_get($(this).closest('tr'))+'=?=barang';
   		window.location.replace("master/ubah_status_aktif?data_sent="+data+'&link=barang_list');
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

   	$("#general_table").on("change",'[name=qty],[name=jumlah_roll]', function(){
   		var ini = $(this).closest('tr');
   		var data = {};
   		data['id'] = ini.find('.id').html();
   		data['qty'] = ini.find('[name=qty]').val();
   		data['jumlah_roll'] = ini.find('[name=jumlah_roll]').val();
		var url = "inventory/mutasi_stok_awal_update";
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
