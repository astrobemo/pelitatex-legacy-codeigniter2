<?=link_tag('assets/global/plugins/bootstrap-datepicker/css/datepicker3.css'); ?>
<link href="<?=base_url('assets/global/plugins/bootstrap-modal/css/bootstrap-modal.css');?>" rel="stylesheet" type="text/css"/>


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

		<div class="modal fade" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body">
						<form action="<?=base_url('transaction/retur_beli_list_update')?>" class="form-horizontal" id="form_add_data" method="post">
							<h3 class='block'> Retur Baru</h3>
							

			                <div class="form-group">
			                    <label class="control-label col-md-3">Tanggal<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
									<input type="hidden" name='toko_id' value="1" >
									<input type="hidden" name='retur_beli_id' value="" >
									<input name='tanggal' value="<?=date("d/m/Y")?>" autocomplete="off" class="form-control date-picker" value="">
			                    </div>
			                </div>

			                <div class="form-group">
			                    <label class="control-label col-md-3">OCKH
			                    </label>
			                    <div class="col-md-6">
									<input name='ockh_info' autocomplete="off" class="form-control" id="ockh-info">
			                    </div>
			                </div>


			                <div class="form-group">
			                    <label class="control-label col-md-3">Supplier
			                    </label>
			                    <div class="col-md-6">
									<select name='supplier_id' class="form-control" id="supplier_list_select">
										<option value="">Pilih</option>
										<?foreach ($this->supplier_list_aktif as $row) {?>
											<option value="<?=$row->id?>"><?=$row->nama?></option>
										<?}?>
									</select>
			                    </div>
			                </div>
							
							<div class="form-group">
			                    <label class="control-label col-md-3">PO
			                    </label>
			                    <div class="col-md-6">
									<select name='po_pembelian_batch_id' class="form-control" id="po_list">
										<option value="">NON PO</option>
										<?$idx = 1;foreach ($po_pembelian_batch as $row) {?>
											<option value="<?=$row->id?>"><?=$row->po_number?></option>
										<?=($idx%3==0 ? "<option disabled>---------</option>" : ''); $idx++;}?>
									</select>
									<small style="color:#ccc" class='loading-po' hidden><i>loading...</i></small>

									<select id='po_list_copy' hidden>
										<?foreach ($po_pembelian_batch as $row) {?>
											<option value="<?=$row->id?>"><?=$row->supplier_id?></option>
										<?}?>
									</select>
			                    </div>
			                </div>

			                <div class="form-group">
			                    <label class="control-label col-md-3">Keterangan
			                    </label>
			                    <div class="col-md-6">
									<input name='keterangan1' maxlength='75' class="form-control"/>
									<input name='keterangan2' maxlength='75' class="form-control" placeholder='no surat jalan supplier'/>
			                    </div>
			                </div>


						</form>
					</div>

					<div class="modal-footer">
						<button type="button" class="btn blue btn-active btn-save">Save</button>
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
							<select class='btn btn-sm btn-default' name='status_select' id='status_select'>
								<option value="" selected>All</option>
								<option value="0">Aktif</option>
								<!-- <option value="0">Tidak Aktif</option> -->
								<option value="-1">Batal</option>

							</select>
							<a href='#portlet-config' data-toggle='modal' class='btn btn-sm btn-default'><i class='fa fa-plus'></i> Tambah</a>

						</div>
					</div>
					<div class="portlet-body">
						<!-- table-striped table-bordered  -->
						<table class="table table-hover table-bordered" id="general_table">
							<thead>
								<tr>
									<th scope="col" class='status_column'>
										Status Aktif
									</th>
									<th scope="col">
										Tanggal
									</th>
									<th scope="col">
										SURAT JALAN
									</th>
									<th scope="col">
										OCKH / PO
									</th>
									<th scope="col">
										Qty
									</th>
									<th scope="col">
										Roll
									</th>
									<th scope="col">
										Nama Barang
									</th>
									<th scope="col">
										Gudang
									</th>
									<th scope="col">
										Harga
									</th>
									<th scope="col" class='text-center'>
										Total
									</th>
									<th scope="col">
										Keterangan
									</th>
									<th scope="col">
										Supplier
									</th>
									<th scope="col">
										Actions
									</th>
									
								</tr>
							</thead>
							<tbody>
								<?foreach ($retur_list as $row) { ?>
									<tr>
										<?
											$qty = explode(',', $row->qty);
											$jumlah_roll = explode(',', $row->jumlah_roll);
											$nama_barang = explode(',', $row->nama_barang);
											$nama_warna = explode(',', $row->nama_warna);
											$nama_gudang = explode(',', $row->nama_gudang);
											$harga = explode(',', $row->harga);

										?>
										<td  class='status_column'>
											<?=$row->status_aktif?>
										</td>
										<td >
											<?=is_reverse_date($row->tanggal);?>
										</td>
										<td >
											<?=$row->po_number;?>
										</td>
										<td >
											<?=$row->no_sj_lengkap;?>
										</td>
										<td >
											<?foreach ($qty as $key => $value) {
												echo str_replace('.00', '', $row->qty).'<br>';
											}?>
										</td>
										<td >
											<?foreach ($qty as $key => $value) {
												echo $jumlah_roll[$key].'<br>';
											}?>
										</td>
										<td >
											<?foreach ($qty as $key => $value) {
												echo $nama_barang[$key].' '.$nama_warna[$key].'<br>';
											}?>
										</td>
										<td >
											<?foreach ($qty as $key => $value) {
												echo $nama_gudang[$key].'<br>';
											}?>
										</td>
										<td >
											<?foreach ($qty as $key => $value) {
												if ($harga[$key] != '') {
													echo number_format($harga[$key],'0',',','.').'<br>';
												}
											}?>
										</td>
										<td  class='text-center'>
											<?foreach ($qty as $key => $value) {
												echo number_format($harga[$key] * $qty[$key],'0',',','.').'<br>';
											}?>
										</td>
										<td>
											<?=$row->keterangan1?><br/>
											<b><?=$row->keterangan2?></b>
										</td>
										<td>
											<?=$row->nama_supplier;?>
										</td>
										<td>
											<a class='btn btn-xs green' target='_blank' href="<?=base_url().is_setting_link('transaction/retur_beli_detail');?>?id=<?=$row->id;?>"><i class='fa fa-edit'></i></a>
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

<script src="<?php echo base_url('assets/global/plugins/select2/select2.min.js'); ?>" type="text/javascript" ></script>
<script src="<?php echo base_url('assets/global/plugins/datatables/media/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets_noondev/js/table-advanced.js'); ?>"></script>


<script>
jQuery(document).ready(function() {

	$("#supplier_list_select").select2({
        allowClear: true
	});
	TableAdvanced.init();

	// oTable = $('#general_table').DataTable();
	// oTable.state.clear();
	// oTable.destroy();

	$("#ockh-info").change(function(){
		var ockh = $(this).val();
		if (ockh.length > 4) {
			var data = {};
			var po_number = '';
			var batch_id_data = [];
			var batch_id = '';
			var supplier_id ='';
		    var data_st = {};
		    data_st['ockh'] = ockh;
			var url = "transaction/get_po_batch_by_ockh";
			ajax_data_sync(url,data_st).done(function(data_respond  /*,textStatus, jqXHR*/ ){
				$.each(JSON.parse(data_respond), function(i,v){
					data[v.po_pembelian_batch_id] = v.po_number;
					batch_id = v.po_pembelian_batch_id;
					supplier_id = v.supplier_id;
				});

				if (data.length > 1) {
					alert("OCKH ada di 2 PO : "+data.join(', '));
				}else{
					$("#po_list").val(batch_id);
					$("#supplier_list_select").val(supplier_id);
					$("#supplier_list_select").change();
				}
			});
		};
	});

	$("#supplier_list_select").change(function() {
		var po_pembelian_batch_id = $("#po_list").val();
		var supplier_id_select = $("#po_list_copy option[value='"+po_pembelian_batch_id+"'] ").text();
		var supplier_id = $(this).val();
		var data = {};
	    var data_st = {};
		var url = "transaction/get_po_batch_by_supplier";
		data_st['supplier_id'] = supplier_id;
		$('#po_list')
		    .find('option')
		    .remove();
		$(".loading-po").show();
		var idx=1;
		ajax_data_sync(url,data_st).done(function(data_respond ){
			var newOpt = new Option("NON PO", "0", true, true);
			$("#po_list").append(newOpt).attr({'disabled':false});
			idx++;
			$.each(JSON.parse(data_respond), function(i,v){
				var newOpt = new Option(v.po_number, v.id, false, false);
				$("#po_list").append(newOpt).attr({'disabled':false});
				idx++;
			});

			if (supplier_id == supplier_id_select) {
				$('#po_list').val(po_pembelian_batch_id);
			}

			$(".loading-po").hide();
		});
	})
	
	$('.btn-edit-save').click(function(){
   		if( $('#form_edit_data [name=nama]').val() != ''){
   			$('#form_edit_data').submit();
   		}
   	});


	<?if (is_posisi_id() < 3) {?>
		$('#general_table').on('click','.btn-batal',function(){
			var faktur = $(this).closest('tr').find('.no_faktur').html();
			var id = $(this).closest('tr').find('.id').html();
			bootbox.confirm("Yakin membatalkan retur ? ", function(respond){
				if (respond) {
					window.location.replace(baseurl+'transaction/retur_penbelian_batal?id='+id);
				};
			});
		});
	<?};?>

	$('.btn-save').click(function(){
		var tanggal = $('#form_add_data [name=tanggal]').val();
		
		if (tanggal != '') {
			btn_disabled_load($(this));
			$('#form_add_data').submit();
		}else{
			notific8("ruby", "Mohon isi tanggal");
		};
		
	});


});
</script>
