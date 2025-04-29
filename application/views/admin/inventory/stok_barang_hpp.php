<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css'); ?>"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css'); ?>"/>
<?=link_tag('assets/global/plugins/bootstrap-datepicker/css/datepicker3.css'); ?>

<style type="text/css">
#general_table tr td, #general_table tr th {
	text-align: center;
	vertical-align: middle;
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
							
						</div>
					</div>
					<div class="portlet-body">
						<form action='' method='get'>
						<h4><b>Tanggal Stok: </b><input name='tanggal' readonly class='date-picker padding-rl-5' style='border:none; border-bottom:1px solid #ddd; width:100px;' value='<?=$tanggal;?>'> <button class='btn btn-xs default'><i class='fa fa-search'></i></button></h4>
						</form>
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
									<th scope="col" rowspan='2'>
										Warna
									</th>
									<th scope="col" rowspan='2'>
										Satuan
									</th>
									<?foreach ($this->gudang_list_aktif as $row) { ?>
										<th colspan='2' style="border-bottom:1px solid #ddd"><?=$row->nama;?></th>
									<?}?>
									<!-- <th colspan='2'>TOTAL</th> -->
									<th rowspan='2' style='border-left:1px solid #ddd'>HPP</th>

								</tr>
								<tr>
									<?
									foreach ($data_hpp as $row) {
										$hpp[$row->barang_id][$row->warna_id] = $row->hpp;
									}
									foreach ($this->gudang_list_aktif as $row) { 
										foreach ($this->satuan_list_aktif as $satuan) {
											${"total_".$row->id."_qty"}[$satuan->id] = 0;
											${"total_".$row->id."_roll"}[$satuan->id] = 0;
										}
										?>
										<th>Yard/Kg</th>
										<th>Roll</th>
									<?}?>
									<!-- <th>Yard/Kg</th>
									<th>Jumlah Roll</th> -->
								</tr>
							</thead>
							<tbody>
								<?foreach ($stok_barang as $row) { 
									foreach ($this->gudang_list_aktif as $isi) { 
										$qty = "gudang_".$isi->id.'_qty';
										$roll = "gudang_".$isi->id.'_roll';
										
										${"total_".$isi->id."_qty"}[$row->satuan_id] += $row->$qty;
										${"total_".$isi->id."_roll"}[$row->satuan_id] += ($row->tipe_qty != 3 ? $row->$roll : 0);
										
									}
									if ($row->status_aktif == 1 ) {?>
										<tr>
											<td style='text-align:left'>
												<?=$row->nama_barang_jual;?>
											</td>
											<td style='text-align:left'> <?=$row->nama_warna_jual;?>
											</td>
											
											<td style='text-align:left'> <?=$row->nama_satuan;?>
											</td>
											
											<?
											$subtotal_qty = 0;
											$subtotal_roll = 0;
											foreach ($this->gudang_list_aktif as $isi) { 
												$qty = "gudang_".$isi->id.'_qty';
												$roll = "gudang_".$isi->id.'_roll';
												
												$subtotal_qty += $row->$qty;
												$subtotal_roll += $row->$roll;
												?>
												<td><?=str_replace(",00", "", number_format($row->$qty,'2',',','.')) ;?> </td>
												<td><?=number_format($row->$roll,'0',',','.');?></td>
	
											<?}?>
											<td style='font-size:1.1em;'>
												<?if (!isset($hpp[$row->barang_id][$row->warna_id])) {
													echo "-";
												}else{?>
													<b><?=number_format($hpp[$row->barang_id][$row->warna_id],'2',',','.');?></b>
												<?}?>
											</td>
										</tr>
									<?}?>
								<? } ?>
							</tbody>
						</table>

						<table class="table table-bordered" style='font-size:1.2em'>
							<tr>
								<th rowspan='2' style='text-align:center; vertical-align:middle'>GUDANG/SATUAN</th>
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
											<?=number_format(${"total_".$row->id."_roll"}[$isi->id],"2",',','.');?>
										</td>
									<?}?>
								</tr>
							<?}?>
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

<script>
jQuery(document).ready(function() {
	$('#general_table').DataTable({
        "paging": true,
        "pageLength": 100
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
