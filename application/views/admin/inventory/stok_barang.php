<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css'); ?>"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css'); ?>"/>
<?=link_tag('assets/global/plugins/bootstrap-datepicker/css/datepicker3.css'); ?>

<style type="text/css">
#general_table tr td, #general_table tr th {
	text-align: center;
	vertical-align: middle;
}

<?if ($print_mode == 1) {?>
	@media print {

		* {
			-webkit-print-color-adjust: exact;
			print-color-adjust: exact;
		}

		#general_table tbody tr:nth-child(even){
			background-color: #eee !important;
		}

		#general_table tbody tr td{
			padding: 2px 8px;
		}
	}
<?}?>

</style>

<div class="page-content">
	<div class='container'>

		<?if (is_posisi_id() < 4) {?>
			<div class="modal fade" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-body">
							<form action="<?=base_url('inventory/penyesuaian_stok_insert')?>" class="form-horizontal" id="form_add_data" method="post">
								<h3 class='block'> Penyesuaian Barang</h3>
								
								<div class="form-group">
				                    <label class="control-label col-md-4">Tipe<span class="required">
				                    * </span>
				                    </label>
				                    <div class="col-md-6">
						                <div class='radio-list'>
						                	<label class="radio-inline">
						                		<input type='radio' class='form-control' checked name='tipe_transaksi' value='1'> Barang Masuk
						                	</label>
						                	<label class="radio-inline">
						                		<input type='radio' class='form-control' name='tipe_transaksi' value='2'> Barang Keluar
						                	</label>
						                </div>
				                    </div>
				                </div>

				                <div class="form-group">
				                    <label class="control-label col-md-4">Barang<span class="required">
				                    * </span>
				                    </label>
				                    <div class="col-md-6">
				                    	<select name='barang_id' class='form-control' id="barang_id_select">
				                    		<?foreach ($this->barang_list_aktif as $row) {?>
				                    			<option value="<?=$row->id?>"><?=$row->nama_jual?></option>
				                    		<?}?>
				                    	</select>
				                    </div>
				                </div>

				                <div class="form-group">
				                    <label class="control-label col-md-4">Warna<span class="required">
				                    * </span>
				                    </label>
				                    <div class="col-md-6">
				                    	<select name='warna_id' class='form-control' id="warna_id_select">
				                    		<?foreach ($this->warna_list_aktif as $row) {?>
				                    			<option value="<?=$row->id?>"><?=$row->warna_jual?></option>
				                    		<?}?>
				                    	</select>
				                    </div>
				                </div>

				                <div class="form-group">
				                    <label class="control-label col-md-4">Gudang<span class="required">
				                    * </span>
				                    </label>
				                    <div class="col-md-6">
				                    	<select name='gudang_id' class='form-control'>
				                    		<?foreach ($this->gudang_list_aktif as $row) {?>
				                    			<option value="<?=$row->id?>"><?=$row->nama?></option>
				                    		<?}?>
				                    	</select>
				                    </div>
				                </div>
				                <div class="form-group">
				                    <label class="control-label col-md-4">Tanggal<span class="required">
				                    * </span>
				                    </label>
				                    <div class="col-md-6">
				                    	<input name="tanggal" type="text" readonly class="form-control date-picker" value="<?=date('d/m/Y');?>" />
				                    </div>
				                </div>

				                <div class="form-group">
				                    <label class="control-label col-md-4">Qty
				                    </label>
				                    <div class="col-md-6">
										<input type="text" class='form-control' name="qty"/>
				                    </div>
				                </div> 

				                <div class="form-group">
				                    <label class="control-label col-md-4">Jumlah Roll
				                    </label>
				                    <div class="col-md-6">
										<input type="text" class='form-control' name="jumlah_roll"/>
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
			
		<?}?>

		<div class="row margin-top-10">
			<div class="col-md-12">
				<div class="portlet light">
					<div class="portlet-title">
						<div class="caption caption-md">
							<i class="icon-bar-chart theme-font hide"></i>
							<span class="caption-subject theme-font bold uppercase"><?=$breadcrumb_small;?></span>
						</div>
						<div class="actions">
							<!-- <select class='btn btn-sm btn-default' name='status_aktif_select' id='status_aktif_select'>
								<option value="1" selected>Aktif</option>
								<option value="0">Tidak Aktif</option>
							</select>
							<a href="#portlet-config" data-toggle='modal' class="btn btn-default btn-sm btn-form-add">
							<i class="fa fa-plus"></i> Tambah </a> -->
						</div>
					</div>
					<div class="portlet-body">
						<table width='100%'>
							<tr>
								<td>
									<form action='' method='get'>
										<h4><b>Tanggal Stok: <?//=$stok_opname_id?> </b><input name='tanggal' readonly class='date-picker padding-rl-5' style='border:none; border-bottom:1px solid #ddd; width:100px;' value='<?=$tanggal;?>'> <button class='btn btn-xs default hidden-print'><i class='fa fa-search'></i></button></h4>
									</form>
								</td>
								<td class='text-right hidden-print'>
									<?if ($print_mode == 1) {?>
										<a href='<?=base_url().is_setting_link('inventory/stok_barang');?>' class='btn blue'><i class='fa fa-eye'></i> Normal Mode</a>
									<?}else{?>
										<a href='<?=base_url().is_setting_link('inventory/stok_barang').'?tanggal='.$tanggal.'&print_mode=1';?>' class='btn blue'><i class='fa fa-eye'></i> Print Mode</a>
									<?}?>
									<form action='<?=base_url();?>inventory/stok_barang_excel' method='get' style='display:inline'>
										<input name='tanggal' value='<?=$tanggal;?>' hidden>
										<button class='btn green'><i class='fa fa-download'></i> Excel</button>
									</form>
									<?if (is_posisi_id() < 3) { ?>
										<a href="#portlet-config" data-toggle='modal' class="btn btn-default btn-sm btn-form-add hidden-print">
											<i class="fa fa-plus"></i> Penyesuaian </a>
										
									<?}?>

								</td>
							</tr>
						</table>
						<hr/>
						<table class="<?=($print_mode == 0 ? 'table table-striped table-hover' : '');?> table-bordered" width='100%' id="general_table">
							<thead>
								<tr>
									<!-- <th scope="col" rowspan='2'>
										Nama Beli
									</th> -->
									<th scope="col" rowspan='2' style="width:150px !important">
										Nama Jual
									</th>
									<th scope="col" rowspan='2' class='hidden-print'>
										Status
									</th>
									<!-- <th scope="col">
										Satuan
									</th> -->
									<?foreach ($this->gudang_list_aktif as $row) { ?>
										<th colspan='2'><?=$row->nama;?></th>
										<th rowspan='2' class='hidden-print'><i class='fa fa-list'></i></th>
									<?}?>
									<th colspan='2'>TOTAL</th>

								</tr>
								<tr>
									<?foreach ($this->gudang_list_aktif as $row) {
										foreach ($this->satuan_list_aktif as $isi) {
											${'qty_gudang_'.$row->id.'_'.$isi->id} = 0;
											${'roll_gudang_'.$row->id.'_'.$isi->id} = 0;
										}
										?>
										<th>Yard/Kg</th>
										<th>Roll</th>

									<?}?>
									<th>Yard/Kg</th>
									<th>Total Roll</th>
								</tr>
							</thead>
							<tbody>
								<?foreach ($stok_barang as $row) { ?>
									<tr>
										
										<td style='text-align:left'>
												<?=$row->nama_barang_jual;?> <span class='urutan' hidden><?=$row->urutan;?></span> <?=$row->nama_warna_jual;?>
										</td>
										<td class='hidden-print'>
											<?if ($row->status_barang == 0) { ?>
												<span style='color:red'>Tidak Aktif</span> 
											<? }else{?>
												Aktif
											<?} ?>
										</td>
										<?
										$subtotal_qty = 0;
										$subtotal_roll = 0;
										$tanggal_start = date("01/m/Y", strtotime(is_date_formatter($tanggal)));
										$tanggal_end = date("t/m/Y", strtotime(is_date_formatter($tanggal)));
										foreach ($this->gudang_list_aktif as $isi) { ?>
											<?
											$qty = $isi->nama.'_qty';
											$roll = $isi->nama.'_roll';
											$subtotal_qty += $row->$qty;
											$subtotal_roll += $row->$roll;
											${'qty_gudang_'.$isi->id.'_'.$row->satuan_id} += $row->$qty;
											${'roll_gudang_'.$isi->id.'_'.$row->satuan_id} += $row->$roll;

											?>
											<td><?=str_replace(',00', '', number_format($row->$qty,'2',',','.')) ;?> <small><?=$row->nama_satuan;?></small> </td>
											<td><?=number_format($row->$roll,'0',',','.');?></td>
											<td class='hidden-print'>									
												<a href="<?=base_url().is_setting_link('inventory/kartu_stok').'/'.$isi->id.'/'.$row->barang_id.'/'.$row->warna_id;?>?tanggal_start=<?=$tanggal_start;?>&tanggal_end=<?=$tanggal_end;?>" class='btn btn-xs yellow-gold' onclick="window.open(this.href, 'newwindow', 'width=1250, height=650'); return false;"><i class='fa fa-search'></i></a>
											</td>
										<?}?>

										<td>
											<b><?=str_replace(',00', '', number_format($subtotal_qty,'2',',','.') );?></b> 
										</td>
										<td>
											<b><?=number_format($subtotal_roll,'0',',','.');?></b>											
										</td>
									</tr>
								<? } ?>
								
							</tbody>
						</table>
						<hr/>
						<?if (is_posisi_id() <= 3) { ?>
							<table class='table table-bordered' style='font-size:1.5em;'>
								<thead>
									<tr>
										<?foreach ($this->gudang_list_aktif as $row) { ?>
											<th colspan='2' class='text-center' ><?=$row->nama;?></th>
										<?}?>
										<th colspan='2' class='text-center'>TOTAL</th>
									</tr>
									<tr>
										<?foreach ($this->gudang_list_aktif as $row) { ?>
											<th class='text-center' style='border:1px solid #ddd'>Qty</th>
											<th class='text-center' style='border:1px solid #ddd'>Roll</th>
										<?}?>
										<th class='text-center' style='border:1px solid #ddd'>Qty</th>
										<th class='text-center' style='border:1px solid #ddd'>Roll</th>
									</tr>
								</thead>
								<tbody>
									<?foreach ($this->satuan_list_aktif as $isi) {?>
										<tr>
											<?
											$total_qty = 0;
											$total_roll = 0;
											foreach ($this->gudang_list_aktif as $row) { 
												$total_qty +=${'qty_gudang_'.$row->id.'_'.$isi->id};
												$total_roll +=${'roll_gudang_'.$row->id.'_'.$isi->id};
												?>
												<td class='text-center'>
													<?=str_replace(',00', '', number_format(${'qty_gudang_'.$row->id.'_'.$isi->id},'2',',','.')) ;?>
													<?=$isi->nama;?>
												</td> 
												<td class='text-center'><?=number_format(${'roll_gudang_'.$row->id.'_'.$isi->id},'0',',','.');?></td>
											<?}?>
											<td class='text-center'>
												<?=str_replace(',00', '', number_format($total_qty,'2',',','.'))?>
												<?=$isi->nama;?>
											</td>
											<td class='text-center'><?=number_format($total_roll,'0',',','.')?></td>
										</tr>
									<?}?>
								</tbody>
							</table>
						<?}?>
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

<script>
jQuery(document).ready(function() {
	//Metronic.init(); // init metronic core components
	//Layout.init(); // init current layout
	<?if ($print_mode == 0) {?>
		// TableAdvanced.init();
		$("#general_table").DataTable({
			"ordering":false,
	        "bStateSave" :true,
			
			// "bFilter":false
		});
	<?}?>

	$("#barang_id_select, #warna_id_select").select2();

	// $("#general_table").DataTable({
	// 	"ordering":false,
	// 	"orderClasses": false
	// });

	// oTable = $('#general_table').DataTable();
	// oTable.state.clear();
	// oTable.destroy();

	// $("#general_table").DataTable({
 //   		"fnCreatedRow": function( nRow, aData, iDataIndex ) {
 //            var status = $('td:eq(6)', nRow).text().split('??');
 //            var id = status[0];
 //            var satuan_id = status[1];
 //            var status_aktif = $('td:eq(0)', nRow).text();
 //            if (status_aktif == 1 ) {
 //            	var btn_status = "<a class='btn-xs btn red btn-remove'><i class='fa fa-times'></i> </a>";
 //            }else{
 //            	var btn_status = "<a class='btn-xs btn blue btn-remove'><i class='fa fa-play'></i> </a>";
 //            };
 //           	var action = "<span class='id' hidden='hidden'>"+id+"</span><span class='satuan' hidden='hidden'>"+satuan_id+"</span><span class='status_aktif' hidden='hidden'>"+status_aktif+"</span><a href='#portlet-config-edit' data-toggle='modal' class='btn-xs btn green btn-edit'><i class='fa fa-edit'></i> </a>"+btn_status;
            
 //            $('td:eq(0)', nRow).html($('td:eq(0)', nRow).text());
 //            $('td:eq(0)', nRow).addClass('status_column');
 //            $('td:eq(1)', nRow).html('<span class="nama">'+$('td:eq(1)', nRow).text()+'</span>');
 //            $('td:eq(2)', nRow).html('<span class="nama_jual">'+$('td:eq(2)', nRow).text()+'</span>');
 //            $('td:eq(4)', nRow).html('<span class="harga_jual">'+change_number_format($('td:eq(4)', nRow).text())+'</span>');
 //            $('td:eq(5)', nRow).html('<span class="harga_beli">'+change_number_format($('td:eq(5)', nRow).text())+'</span>');
 //            $('td:eq(6)', nRow).html(action);
 //            // $(nRow).addClass('status_aktif_'+status_aktif);
            
 //        },
 //        "bStateSave" :true,
	// 	"bProcessing": true,
	// 	"bServerSide": true,
	// 	"sAjaxSource": baseurl + "master/data_barang"
	// });

	// var oTable;
 //    oTable = $('#general_table').dataTable();
 //    oTable.fnFilter( 1, 0 );

	// $('#status_aktif_select').change(function(){
	// 	oTable.fnFilter( $(this).val(), 0 ); 
	// });

	<?if ($print_mode == 1) {?>
		window.print();
	<?}?>
	
   	$('#general_table').on('click', '.btn-edit', function(){
   		$('#form_edit_data [name=barang_id]').val($(this).closest('tr').find('.id').html());
   		$('#form_edit_data [name=nama]').val($(this).closest('tr').find('.nama').html());
   		$('#form_edit_data [name=nama_jual]').val($(this).closest('tr').find('.nama_jual').html());
   		$('#form_edit_data [name=harga_beli]').val($(this).closest('tr').find('.harga_beli').html());
   		$('#form_edit_data [name=harga_jual]').val($(this).closest('tr').find('.harga_jual').html());
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
});
</script>
