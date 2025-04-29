<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css'); ?>"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css'); ?>"/>
<?=link_tag('assets/global/plugins/bootstrap-datepicker/css/datepicker3.css'); ?>
<?=link_tag('assets/global/plugins/select2/select2.css'); ?>


<style type="text/css">
#general_table tr td, #general_table tr th {
	text-align: center;
	vertical-align: middle;
}

<?
$class_gudang = ''; $idx = 0;
foreach ($this->gudang_list_aktif as $row) {
	if ($row->visible == 0) {
		$class_gudang[$idx] = ".gudang-".$row->id;
		$idx++;
	}
}?>
<?if ($class_gudang != '') {?>
	<?=implode(', ', $class_gudang);?>{
		display: none;
	}
<?}?>

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
									<h4>
										<table>
											<tr>
												<td><b>Tanggal Stok: </b></td>
												<td><input name='tanggal' readonly class='date-picker padding-rl-5' style='border:none; border-bottom:1px solid #ddd; width:200px;' value='<?=$tanggal;?>'></td>
												<td>
												</td>
											</tr>
											<tr>
												<td><b>Barang: </b></td>
												<td>
													<select name='barang_id' style='border:none; border-bottom:1px solid #ddd; width:200px;' id='barang_id'>
														<option value="">Pilih</option>
														<?foreach ($this->barang_list_aktif as $row) { ?>
															<option <?echo ($row->id == $barang_id ? 'selected' : '');?> value='<?=$row->id;?>'><?=$row->nama_jual;?></option>
														<?}?>
													</select>
												</td>
												<td></td>
											</tr>
											<tr>
												<td><b>Total Yard</b></td>
												<td><span class='total-yard'></span></td>
												<td></td>

											</tr>
											<tr>
												<td><b>Total Roll</b></td>
												<td><span class='total-roll'></span></td>
												<td></td>
											</tr>
											<tr>
												<td colspan='3'>
													<button class='btn btn-xs btn-block green'><i class='fa fa-search'></i> FILTER</button></h4>
												</td>
											</tr>
										</table>
										
										
										<br/>
									</h4>
									</form>
								</td>
								<td class='text-right'>
									<?if ($print_mode == 1) {?>
										<a href='<?=base_url().is_setting_link('inventory/stok_barang_by_barang');?>?tanggal=<?=$tanggal?>&barang_id=<?=$barang_id?>' class='btn blue hidden-print'><i class='fa fa-eye'></i> Normal Mode</a>
									<?}else{?>
										<a href='<?=base_url().is_setting_link('inventory/stok_barang_by_barang').'?tanggal='.$tanggal.'&barang_id='.$barang_id.'&print_mode=1';?>' class='btn blue'><i class='fa fa-eye'></i> Print Mode</a>
									<?}?>
								</td>
							</tr>
						</table>
						
						<hr/>
						<div>
							<b>Gudang : </b>
								<?foreach ($this->gudang_list_aktif as $row){?>
									<label>
										<input class='gudang-view' type='checkbox' <?=($row->visible == 1 ? 'checked' : '');?> value="<?=$row->id;?>" /><?=$row->nama;?></label>
								<?}?>

								<?/*foreach ($this->gudang_list_aktif as $row){?>
									<tr>
										<td><?=($row->urutan == 1 ? "GUDANG" : '');?></td>
										<td> : </td>
										<td>
											<label>
												<input class='gudang-view' type='checkbox' <?=($row->id == 1 || $row->id == 2 ? 'checked' : '');?> value="<?=$row->id;?>" /><?=$row->nama;?></label>
										</td>
									</tr>
								<?}*/?>
						</div>
						<hr/>
						<table class="table table-striped table-bordered table-hover" id="general_table">
							<thead>
								<tr>
									<!-- <th scope="col" rowspan='2'>
										Nama Beli
									</th> -->
									<th scope="col" rowspan='2'>
										Nama Jual
									</th>
									<th scope="col" rowspan='2' <?=(is_posisi_id() != 1 ? 'hidden' : '' );?> >
										Status
									</th>
									<!-- <th scope="col">
										Satuan
									</th> -->
									<?foreach ($this->gudang_list_aktif as $row) { 
										foreach ($this->satuan_list_aktif as $satuan) {
											${"total_".$row->id."_qty"}[$satuan->id] = 0;
											${"total_".$row->id."_roll"}[$satuan->id] = 0;
										}?>
										<th  colspan='<?=($print_mode == 1 ? '2' : '3')?>' class='gudang-<?=$row->id?>' ><?=$row->nama;?></th>
									<?}?>
									<th colspan='2'>TOTAL</th>

								</tr>
								<tr>
									<?foreach ($this->gudang_list_aktif as $row) { ?>
										<th class='gudang-<?=$row->id?>'>Yard/Kg</th>
										<th class='gudang-<?=$row->id?>'>Jumlah Roll</th>
										<th <?=($print_mode == 1 ? 'hidden' : '')?> class='gudang-<?=$row->id?>'><i class='fa fa-list'></i></th>

									<?}?>
									<th>Yard/Kg</th>
									<th>Jumlah Roll</th>
								</tr>
							</thead>
							<tbody>
								<?
								foreach ($stok_barang as $row) { 
									foreach ($this->gudang_list_aktif as $isi) { 
										$qty = "gudang_".$isi->id.'_qty';
										$roll = "gudang_".$isi->id.'_roll';
										
										${"total_".$isi->id."_qty"}[$row->satuan_id] += $row->$qty;
										${"total_".$isi->id."_roll"}[$row->satuan_id] += ($row->tipe_qty != 3 ? $row->$roll : 0);
										
									}?>
									<tr>
										<td>
											<?=$row->nama_barang_jual;?> <?=$row->nama_warna_jual;?>
										</td>
										<td <?=(is_posisi_id() != 1 ? 'hidden' : '' );?> >
											<?if ($row->status_barang == 0) { ?>
												<span style='color:red'>Tidak Aktif</span> 
											<? }else{?>
												Aktif
											<?} ?>
										</td>
										<?
										$subtotal_qty = 0;
										$subtotal_roll = 0;
										foreach ($gudang_list as $isi) { ?>
											<?
											$qty = 'gudang_'.$isi->id.'_qty';
											$roll = 'gudang_'.$isi->id.'_roll';
											$subtotal_qty += $row->$qty;
											$subtotal_roll += $row->$roll;

											?>
											<td class='gudang-<?=$isi->id?>'><?=str_replace(',00','',number_format($row->$qty,'2',',','.'));?> <?=$row->nama_satuan;?></td>
											<td class='gudang-<?=$isi->id?>'><?=number_format($row->$roll,'0',',','.');?></td>
											<td class='gudang-<?=$isi->id?>' <?=($print_mode == 1 ? 'hidden' : '')?>>									
												<a href="<?=base_url().is_setting_link('inventory/kartu_stok').'/'.$isi->id.'/'.$row->barang_id.'/'.$row->warna_id;?>" class='btn btn-xs yellow-gold hidden-print' onclick="window.open(this.href, 'newwindow', 'width=1250, height=650'); return false;"><i class='fa fa-search'></i></a>
											</td>
										<?}?>

										<td>
											<b><?=number_format($subtotal_qty,'2',',','.');?></b> 
										</td>
										<td>
											<b><?=number_format($subtotal_roll,'0',',','.');?></b>											
										</td>
									</tr>
								<? } ?>
							</tbody>
						</table>
						<!-- <table class='table' style='font-size:1.3em'>
							<tr>
								<th rowspan='2'></th>
								
								<?foreach ($gudang_list as $row) { ?>
									<th colspan='2'  class='gudang-<?=$row->id?>'><?=$row->nama;?></th>
								<?}?>
								<th colspan='2'>TOTAL</th>
								<tr>
									<?foreach ($this->gudang_list_aktif as $row) { ?>
										<th class='gudang-<?=$row->id?>'>Yard/Kg</th>
										<th class='gudang-<?=$row->id?>'>Jumlah Roll</th>

									<?}?>
									<th>Yard/Kg</th>
									<th>Jumlah Roll</th>
								</tr>

							</tr>
							<tr >
								<td>
									<b>TOTAL</b>
								</td>
								<?$total_yard = 0; $total_roll = 0;
								$subtotal_yard_hide = 0; $subtotal_roll_hide = 0;
								$gudang_invisible_yard = array();
								$gudang_invisible_roll = array();
								foreach ($gudang_list as $row) { 
									$total_yard += ${'total_yard_'.$row->id};
									$total_roll += ${'total_roll_'.$row->id};
									if ($row->visible == 0 && ${'total_yard_'.$row->id} > 0 ) {
										$subtotal_yard_hide += ${'total_yard_'.$row->id};
										$subtotal_roll_hide += ${'total_roll_'.$row->id};
										$gudang_invisible_yard[$row->nama] = ${'total_yard_'.$row->id}; 
										$gudang_invisible_roll[$row->nama] = ${'total_roll_'.$row->id}; 
									}
									?>
									<td class='gudang-<?=$row->id?>'><?=number_format(${'total_yard_'.$row->id},'2',',','.');?> </td>
									<td class='gudang-<?=$row->id?>'><?=${'total_roll_'.$row->id};?></td>
								<?}?>
								<td>
									<b> <?=str_replace(',00','',number_format($total_yard - $subtotal_yard_hide,'2',',','.'));?> </b>
									<?foreach ($gudang_invisible_yard as $key => $value) {?>
										<br/><span style='font-size:0.8em'>+<?=$key?> :<?=str_replace(',00','',number_format($value,'2',',','.'));?></span>
									<?}?>
								</td>
								<td>
									<b> <?=$total_roll - $subtotal_roll_hide;?></b>
									<?foreach ($gudang_invisible_roll as $key => $value) {?>
										<br/><span style='font-size:0.9em'>+<?=$key?> :<?=str_replace(',00','',number_format($value,'2',',','.'));?></span>
									<?}?>
								</td>
							</tr>
						</table> -->
						<table class="table table-bordered" style='font-size:1.2em'>
							<tr>
								<th rowspan='2' style='text-align:center; vertical-align:middle'>SATUAN/GUDANG</th>
								<?foreach ($this->gudang_list_aktif as $row) {?> 
									<th colspan='2'><?=$row->nama?></th>
								<?}?>
							</tr>
							<tr>
								<?foreach ($this->gudang_list_aktif as $row) {?> 
									<th>QTY</th>
									<th>ROLL</th>
								<?}?>
							</tr>
							<?foreach ($this->satuan_list_aktif as $isi) {?>
								<tr>
									<td><?=$isi->nama;?></td>
									<?foreach ($this->gudang_list_aktif as $row) {?> 
										<td><?=str_replace(",00", "", number_format(${"total_".$row->id."_qty"}[$isi->id],'2',',','.'));?></td>
										<td>
											<?=number_format(${"total_".$row->id."_roll"}[$isi->id],"0",',','.');?>
										</td>
									<?}?>
								</tr>
							<?}?>
						</table>
						<hr/>
					</div>
				</div>
			</div>
		</div>
	</div>			
</div>

<script src="<?php echo base_url('assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js'); ?>" type="text/javascript"></script>

<script src="<?php echo base_url('assets/global/plugins/datatables/media/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/select2/select2.min.js'); ?>" type="text/javascript" ></script>

<script src="<?php echo base_url('assets_noondev/js/table-advanced.js'); ?>"></script>

<script>
jQuery(document).ready(function() {
	 Metronic.init(); // init metronic core components
	Layout.init(); // init current layout
	<?if($print_mode != 1){?>
		TableAdvanced.init();
		<?}else{?>
			window.print();
			<?}?>

	$('#barang_id').select2({
        allowClear: true
    });

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
	$(".gudang-view").change(function(){
		var gudang_id = $(this).val();
				var data_st = {};
				var url = "inventory/update_gudang_visible";
				data_st['gudang_id'] = gudang_id;
				// alert(gudang_id);
				
				ajax_data_sync(url,data_st).done(function(data_respond  /*,textStatus, jqXHR*/ ){
					// console.log(data_respond);
					if (data_respond == 'OK') {
						window.location.reload();
					}else{
						alert('error');
					}
		   		});
	});

   	$('#general_table').on('click', '.btn-edit', function(){
   		$('#form_edit_data [name=barang_id]').val($(this).closest('tr').find('.id').html());
   		$('#form_edit_data [name=nama]').val($(this).closest('tr').find('.nama').html());
   		$('#form_edit_data [name=nama_jual]').val($(this).closest('tr').find('.nama_jual').html());
   		$('#form_edit_data [name=harga_beli]').val($(this).closest('tr').find('.harga_beli').html());
   		$('#form_edit_data [name=harga_jual]').val($(this).closest('tr').find('.harga_jual').html());
   	});

   	// $('#general_table').on('click', '.btn-remove', function(){
   	// 	var data = status_aktif_get($(this).closest('tr'))+'=?=barang';
   	// 	window.location.replace("master/ubah_status_aktif?data_sent="+data+'&link=barang_list');
   	// });

   	// $('.btn-save').click(function(){
   	// 	if( $('#form_add_data [name=nama]').val() != '' ){
   	// 		$('#form_add_data').submit();
   	// 	}
   	// });

   	// $('.btn-edit-save').click(function(){
   	// 	if( $('#form_edit_data [name=nama]').val() != ''){
   	// 		$('#form_edit_data').submit();
   	// 	}
   	// });

   	$('.total-yard').html("<?=str_replace(',00','',number_format($total_yard,'2',',','.'));?>");
   	$('.total-roll').html("<?=$total_roll;?>");

});
</script>
