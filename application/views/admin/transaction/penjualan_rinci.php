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
						<form action='' id='form-get' method='get'>
							<table id='tbl-form-get' width='50%'>
								<tr>
										<td>Tanggal: </td>
										<td class='padding-rl-5'> : </td>
										<td>
											<b>
												<input autocomplete='off' name='tanggal_start' class='date-picker text-center'  style='border:none; border-bottom:1px solid #ddd; width:100px;'  value='<?=$tanggal_start;?>'>
												s/d
												<input autocomplete='off' name='tanggal_end' class='date-picker  text-center' style='border:none; border-bottom:1px solid #ddd; width:100px;' value='<?=$tanggal_end;?>'> 
											</b>
											<button class='btn btn-xs'><i class='fa fa-search'></i></button>
										</td>
									</tr>
								<tr hidden>
									<td>Toko </td>
									<td class='padding-rl-5'> : </td>
									<td>
										<b>
											<select <?if ($pembayaran_hutang_id != '') {?> disabled <?}?> name='toko_id' id="toko_id_select" style='width:100%;'>
												<option <?=($toko_id == 0 ? "selected" : "");?> value='0'>Pilih</option>
												<?foreach ($this->toko_list_aktif as $row) { ?>
													<option <?=($toko_id == $row->id ? "selected" : "");?> value='<?=$row->id;?>'><?=$row->nama;?></option>
												<?}?>
											</select>
										</b>
									</td>
								</tr>
								<tr hidden>
									<td> Supplier </td>
									<td class='padding-rl-5'> : </td>
									<td>
										<b>
											<select name='supplier_id'  <?if ($pembayaran_hutang_id != '') {?> disabled <?}?> id="supplier_id_select" style='width:100%;'>
												<option <?=($supplier_id == 0 ? "selected" : "");?> value='0'>Pilih</option>
												<?foreach ($this->supplier_list_aktif as $row) { ?>
													<option <?=($supplier_id == $row->id ? "selected" : "");?> value='<?=$row->id;?>'><?=$row->nama;?></option>
												<?}?>
											</select>
										</b>
									</td>
								</tr>
							</table>
						</form>
						<hr/>
						<!-- table-striped table-bordered  -->
						<table class="table table-hover table-bordered" id="general_table">
							<thead>
								<tr>
									<th scope="col">
										No Faktur
									</th>
									<th scope="col">
										Tanggal
									</th>
									<th scope="col">
										Nama Customer
									</th>
									<th scope="col" class='text-center'>
										Barang
									</th>
									<th scope="col" class='text-center'>
										Qty
									</th>
									<th scope="col" class='text-center'>
										Roll
									</th>
									<th scope="col" class='text-center'>
										Harga
									</th>
									<th scope="col">
										Total
									</th>
								</tr>
							</thead>
							<tbody>
								<?foreach ($penjualan_list_detail as $row) {?>
									<tr>
										<td><?=$row->no_faktur?></td>
										<td><?=is_reverse_date($row->tanggal);?></td>
										<td><?=$row->nama_customer?></td>
										<td><?=$row->nama_barang;?> <?=$row->nama_warna?></td>
										<td>
											<input name='qty' value="<?=str_replace('.00', '', $row->qty);?>" style='width:70px' class='qty text-center' >
										</td>
										<td>
											<input name='jumlah_roll' value="<?=$row->jumlah_roll;?>" style='width:50px' class='text-center'>
										</td>
										<td><input name='harga_jual' value='<?=number_format($row->harga_jual,'0',',','.');?>' class='amount_number harga_jual text-center' style='width:80px'></td>
										<td>
											<span class='subtotal'><?=number_format($row->qty * $row->harga_jual,'0',',','.');?></span>
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
	$("#general_table").dataTable();

	$("#general_table").on("change",'.qty', function(){
		var ini = $(this).closest('tr');
		var qty = $(this).val();
		// var harga = ini.find('.harga_jual').html();
		var subtotal = reset_number_format(ini.find('.subtotal').html());

		var harga = subtotal / qty;
		if (harga != 0) {
			harga = change_number_format(harga);
		};
		ini.find('.harga_jual').val(harga);
	});

	$("#general_table").on("change",'.harga_jual', function(){
		var ini = $(this).closest('tr');
		var harga_jual = $(this).val();
		var subtotal = reset_number_format(ini.find('.subtotal').html());

		var qty = subtotal / harga_jual;
		ini.find('.qty').val(qty);
	});
});
</script>
