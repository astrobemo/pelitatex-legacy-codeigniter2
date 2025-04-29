<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css'); ?>"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css'); ?>"/>
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
						<form action="<?=base_url('transaction/retur_jual_list_update')?>" class="form-horizontal" id="form_add_data" method="post">
							<h3 class='block'> Retur Baru</h3>
							
							<div class="form-group">
			                    <label class="control-label col-md-3">No Faktur Jual<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
									<input type="hidden" name='retur_jual_id' value="" >
									<input type="hidden" name='toko_id' value="1" >
									<input type="hidden" name='penjualan_id' id="search_no_faktur" class="form-control select2">
			                    </div>
			                </div>

			                <div class="form-group">
			                    <label class="control-label col-md-3">Tanggal<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
									<input name='tanggal' autocomplete="off" class="form-control date-picker" value="">
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
										Faktur Jual
									</th>
									<th scope="col">
										Faktur Retur
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
										Customer
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
											<?=$row->no_faktur_penjualan;?>
										</td>
										<td >
											<?=$row->no_faktur_lengkap;?>
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
										<td >
											<?=$row->nama_customer;?>
										</td>
										<td >
											<a class='btn btn-xs green' target='_blank' href="<?=base_url().is_setting_link('transaction/retur_jual_detail');?>?id=<?=$row->id;?>"><i class='fa fa-edit'></i></a>
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


<script src="<?php echo base_url('assets/global/plugins/select2/select2.min.js'); ?>" type="text/javascript" ></script>
<script>
jQuery(document).ready(function() {
	Metronic.init(); // init metronic core components
	Layout.init(); // init current layout
	// TableAdvanced.init();

	// oTable = $('#general_table').DataTable();
	// oTable.state.clear();
	// oTable.destroy();

	var oTable;
    oTable = $('#general_table').dataTable();
    oTable.fnFilter( '', 9 );

	$('#status_select').change(function(){
		oTable.fnFilter( $(this).val(), 9 ); 
	});

	$("#retur_type_add").change(function(){
		if ($(this).val() == 1) {
			$('#add-select-customer').show();
			$('#add-input-customer').hide();
		}else{
			$('#add-select-customer').hide();
			$('#add-input-customer').show();
		};
	});

	// $("#retur_type_edit").change(function(){
	// 	if ($(this).val() == 1) {
	// 		$('#form_edit_data .customer_section').show();
	// 	}else{
	// 		$('#form_edit_data .customer_section').hide();
	// 		$('#form_edit_data [name=customer_id]').val('');
	// 	};
	// });


	$('.supplier-input, .gudang-input').click(function(){
		$('#form_add_data .supplier-input').removeClass('supplier-input');
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
					window.location.replace(baseurl+'transaction/retur_penjualan_batal?id='+id);
				};
			});
		});
	<?};?>

	$('.btn-save').click(function(){
		var tanggal = $('#form_add_data [name=tanggal]').val();
		var penjualan_id = $('#form_add_data [name=penjualan_id]').val();
		
		if (tanggal != '') {
			if (penjualan_id != '') {
				$('#form_add_data').submit();
			}else{
				notific8("ruby", "Mohon isi no faktur jual");
			};
		}else{
			notific8("ruby", "Mohon isi tanggal");
		};
		
	});

	$("#search_no_faktur").select2({
        placeholder: "Select...",
        allowClear: true,
        minimumInputLength: 1,
        query: function (query) {
            var data = {
                results: []
            }, i, j, s;
            var data_st = {};
			var url = "transaction/get_search_no_faktur_jual";
			data_st['no_faktur'] = query.term;
			console.log(query.term);
			
			ajax_data_sync(url,data_st).done(function(data_respond  /*,textStatus, jqXHR*/ ){
				// console.log(data_respond);
				$.each(JSON.parse(data_respond),function(k,v){
					data.results.push({
	                    id: v.id,
	                    text: v.no_faktur
	                });
				});
	            query.callback(data);
	   		});
        }
    });

});
</script>
