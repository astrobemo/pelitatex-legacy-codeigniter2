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
								<td width='400px;'>
									<?foreach ($stok_opname_data as $row) {
										$tanggal = is_reverse_date($row->tanggal);
									}?>
									<h4>
										<b>Tanggal: </b>
										<input name='tanggal' readonly id='tanggal' class='date-picker padding-rl-5' style='border:none; border-bottom:1px solid #ddd; width:100px;' value='<?=$tanggal;?>'> <br/>
										<b>Filter: </b>
										<a href="<?=base_url().is_setting_link('inventory/stok_opname_overview_with_before')?>?id=<?=$stok_opname_id?>&tipe_view=1" class="btn btn-sm <?=($tipe_view==1 ? 'red' : 'btn-default' )?>" >Semua</a>
										<a href="<?=base_url().is_setting_link('inventory/stok_opname_overview_with_before')?>?id=<?=$stok_opname_id?>&tipe_view=2" class="btn btn-sm <?=($tipe_view==2 ? 'red' : 'btn-default' )?>" >Hanya yang selisih</a>
									</h4>
									<form id='form-filter-barang' hidden>
										Filter Barang : 
										<input name='id' value='<?=$stok_opname_id;?>' hidden>
										<select id='barang_id_select' name='barang_id_filter' style='width:200px'>
											<option <?=($barang_id_filter == '' ? 'selected' : '');?> value=''>Semua</option>
											<?foreach ($nama_stok_barang as $row) {?>
												<option <?=($barang_id_filter == $row->barang_id ? 'selected' : '');?> value='<?=$row->barang_id?>'><?=$row->nama_barang_jual;?></option>
											<?}?>
										</select>
									</form>
								</td>
								<td class='text-right'>
									<form action='<?=base_url();?>inventory/stok_barang_excel' method='get' hidden>
										<input name='tanggal' value='<?=$tanggal;?>' hidden>
										<button disabled class='btn green'><i class='fa fa-download'></i> Excel</button>
									</form>
								</td>
								<td width='200px;'>
									<a onclick='window.location.reload()'  class='btn blue'>REFRESH</a>
									<a href="<?=base_url();?>inventory/stok_opname_overview_with_before_excel?id=1" class='btn green'><i class='fa fa-download'></i></a>
								</td>
							</tr>
						</table>
						<hr/>
						<table class="table table-striped table-bordered table-hover" id="general_table">
							<thead>
								<tr>
									<!-- <th scope="col" rowspan='2'>
										Nama Beli
									</th> -->
									<th scope="col" rowspan='2' style="width:175px !important">
										Nama Jual
									</th>
									<!-- <th scope="col" rowspan='2'>
										Status
									</th>
									<th scope="col">
										Satuan
									</th> -->
									<?foreach ($this->gudang_list_aktif as $row) { ?>
										<th colspan='3'><?=$row->nama;?></th>
									<?}?>
									<th colspan='3'>TOTAL</th>
									<th scope="col" rowspan='2' hidden>%</th>

								</tr>
								<tr>
									<?foreach ($this->gudang_list_aktif as $row) { 
										foreach ($this->satuan_list_aktif as $isi) {
											${'qty_gudang_'.$row->id.'_'.$isi->id} = 0;
											${'roll_gudang_'.$row->id.'_'.$isi->id} = 0;
											${'beforeqty_gudang_'.$row->id.'_'.$isi->id} = 0;
											${'beforeroll_gudang_'.$row->id.'_'.$isi->id} = 0;
										}
										?>
										<th>Sebelum</th>
										<th>Yard/Kg</th>
										<th>Roll</th>
										
									<?}?>
									<th>Sebelum</th>
									<th>Setelah</th>
									<th>Selisih</th>
								</tr>
							</thead>
							<tbody>
								<?foreach ($stok_opname_detail_with_before as $row) { 

										$subtotal_qty = 0;
										$subtotal_roll = 0;
										$subtotal_beforeqty = 0;
										$subtotal_beforeroll = 0;
										$kelas = ($tipe_view == 1 ? 1 : 0);
										$persen =0;
									foreach ($this->gudang_list_aktif as $isi) { ?>
											<?
											${'qty_'.$isi->id} = $isi->id.'_qty';
											${'roll_'.$isi->id} = $isi->id.'_roll';
											$subtotal_qty += $row->${'qty_'.$isi->id};
											$subtotal_roll += $row->${'roll_'.$isi->id};
											${'qty_gudang_'.$isi->id.'_'.$row->satuan_id} += $row->${'qty_'.$isi->id};
											${'roll_gudang_'.$isi->id.'_'.$row->satuan_id} += $row->${'roll_'.$isi->id};
											
											${'beforeqty_'.$isi->id} = $isi->id.'_beforeqty';
											${'beforeroll_'.$isi->id} = $isi->id.'_beforeroll';
											$subtotal_beforeqty += $row->${'beforeqty_'.$isi->id};
											$subtotal_beforeroll += $row->${'beforeroll_'.$isi->id};
											${'beforeqty_gudang_'.$isi->id.'_'.$row->satuan_id} += $row->${'beforeqty_'.$isi->id};
											${'beforeroll_gudang_'.$isi->id.'_'.$row->satuan_id} += $row->${'beforeroll_'.$isi->id};
											$persen=($subtotal_beforeqty - $subtotal_qty)/($subtotal_beforeqty == 0 ? 1 : $subtotal_beforeqty) * 100;
											$persen = $row->${'beforeqty_'.$isi->id} - $row->${'qty_'.$isi->id};
											${'bg_color_'.$isi->id} = ( $persen > 30 ? 'background:#FF8A65' : ( $persen > 10 ? 'background:#FFCA28' : ( $persen > 0 ? 'background:#A5D6A7' : ($persen < 0 ? 'background:#fbffbd' : '' ) ) ) );
											if($persen != 0 && $tipe_view == 2){
												$kelas++;
											}
											?>
										<?}?>
									<?if ($kelas != 0) {?>
										<tr style="" class="<?=($kelas == 0 ? 'all-zero' : '');?>" >
											<td style='text-align:left'>
												<b><?=$row->nama_jual;?> <?=$row->warna_jual;?> <?=(is_posisi_id() == 1 ? '['.$row->barang_id.']['.$row->warna_id.']' : '' );?></b>
											</td>
											<?
											foreach ($this->gudang_list_aktif as $isi) { ?>
												<td style="<?=${'bg_color_'.$isi->id}?>">
													<?if (is_posisi_id()==1) {
														// echo $row->${'beforeqty_'.$isi->id}.'-'.$row->${'qty_'.$isi->id};
													}?>
													<?=str_replace(',00', '', number_format($row->${'beforeqty_'.$isi->id},'2',',','.')) ;?> / 
													<?=str_replace(',00', '', $row->${'beforeroll_'.$isi->id}) ;?>
												</td>
												<td style="<?=${'bg_color_'.$isi->id}?>">
													<?=str_replace(',00', '', number_format($row->${'qty_'.$isi->id},'2',',','.')) ;?>
												</td>
												<td style="<?=${'bg_color_'.$isi->id}?>">
													<?=$row->${'roll_'.$isi->id};?>
												</td>
											<?}?>
											<td>
												<b><span class='subtotal_beforeqty'><?=str_replace(',00', '', number_format($subtotal_beforeqty,'2',',','.') );?></span></b> 
												/ <b><span class='subtotal_beforeroll'><?=number_format($subtotal_beforeroll,'0',',','.');?></span></b>
											</td>
											<td>
												<b><span class='subtotal'><?=str_replace(',00', '', number_format($subtotal_qty,'2',',','.') );?></span></b> 
												/ <b><span class='subtotal_roll'><?=number_format($subtotal_roll,'0',',','.');?></span></b>
											</td>
											<td>
												<b><span class='selisih'><?=str_replace(',00', '', number_format($subtotal_beforeqty - $subtotal_qty,'2',',','.') );?></span></b> 
												/ <b><span class='selisih_roll'><?=number_format($subtotal_beforeroll - $subtotal_roll,'0',',','.');?></span></b>
												<span class='barang_id' hidden><?=$row->barang_id?></span>
												<span class='warna_id' hidden><?=$row->warna_id?></span>
											</td>
											<td hidden>
												<span class='persen'><?=($subtotal_beforeqty - $subtotal_qty)/($subtotal_beforeqty == 0 ? 1 : $subtotal_beforeqty) * 100;?></span>
											</td>
										</tr>
									<?}?>
								<? } ?>
								
							</tbody>
						</table>
						<hr/>
							<a onclick='window.location.reload()' style='width:100%' class='btn red'>REFRESH Untuk Update Total</a>
						<hr/>
						<?if (is_posisi_id() <= 3) { ?>
							<table class='table table-bordered' style='font-size:1.5em;'>
								<thead>
									<tr>
										<?foreach ($this->gudang_list_aktif as $row) { ?>
											<th colspan='2' class='text-center' ><?=$row->nama;?></th>
										<?}?>
										<th colspan='3' class='text-center'>TOTAL</th>
									</tr>
									<tr>
										<?foreach ($this->gudang_list_aktif as $row) { ?>
											<th class='text-center'>Sebelum</th>
											<th class='text-center'>Sesudah</th>
										<?}?>
										<th class='text-center'>Sebelum</th>
										<th class='text-center'>Sesudah</th>
										<th class='text-center'>Selisih</th>
									</tr>
								</thead>
								<tbody>
									<?foreach ($this->satuan_list_aktif as $isi) {?>
										<tr>
											<?
											$total_qty = 0;
											$total_roll = 0;
											$total_beforeqty = 0;
											$total_beforeroll = 0;
											foreach ($this->gudang_list_aktif as $row) { 
												$total_qty +=${'qty_gudang_'.$row->id.'_'.$isi->id};
												$total_roll +=${'roll_gudang_'.$row->id.'_'.$isi->id};
												$total_beforeqty +=${'beforeqty_gudang_'.$row->id.'_'.$isi->id};
												$total_beforeroll +=${'beforeroll_gudang_'.$row->id.'_'.$isi->id};
												?>
												<td class='text-center'><?=str_replace(',00', '', number_format(${'beforeqty_gudang_'.$row->id.'_'.$isi->id},'2',',','.')) ;?> / <?=number_format(${'beforeroll_gudang_'.$row->id.'_'.$isi->id},'0',',','.');?></td>
												<td class='text-center'><?=str_replace(',00', '', number_format(${'qty_gudang_'.$row->id.'_'.$isi->id},'2',',','.')) ;?> / <?=number_format(${'roll_gudang_'.$row->id.'_'.$isi->id},'0',',','.');?></td>
											<?}?>
											<td class='text-center'><?=str_replace(',00', '', number_format($total_beforeqty,'2',',','.'))?> / <?=number_format($total_beforeroll,'0',',','.')?></td>
											<td class='text-center'><?=str_replace(',00', '', number_format($total_qty,'2',',','.'))?> / <?=number_format($total_roll,'0',',','.')?></td>
											<td class='text-center'><?=str_replace(',00', '', number_format($total_beforeqty - $total_qty,'2',',','.'))?> / <?=number_format($total_beforeroll - $total_roll,'0',',','.')?></td>
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
	// TableAdvanced.init();
	$('#barang_id_select').select2();

	$("#general_table").DataTable({
		"ordering":false,
		"orderClasses": false
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

	let idx = 1;
   	$('#tanggal').change(function(){
   		if($(this).val() != ''){
			let ini = $(this).closest('tr');
	   		let data_st = {};
			let url = "inventory/update_stok_opname_tanggal";
			data_st['stok_opname_id'] = "<?=$stok_opname_id?>";
			data_st['tanggal'] = $(this).val();

			ajax_data_sync(url,data_st).done(function(data_respond  /*,textStatus, jqXHR*/ ){
				// console.log(data_respond);
				if(data_respond == 'OK'){
					if (idx == 1) {
						notific8("lime",'Tanggal Updated');
						idx++;
					}else if(idx > 2){
						idx = 1;
					}
				}else{
					alert("Error mohon refresh/reload halaman");
				}
	   		});

   		}
   	})

   	$('#general_table').on('change','.qty-number, .jumlah-roll', function(){
   		let ini = $(this).closest('tr');
   		let data_st = {};
   		let gudang_id = $(this).attr('name');
		let url = "inventory/update_stok_opname_barang";
		data_st['stok_opname_id'] = "<?=$stok_opname_id?>";
		data_st['barang_id'] = ini.find('.barang_id').html();
		data_st['warna_id'] = ini.find('.warna_id').html();
		data_st['qty'] = ini.find('.qty-'+gudang_id).val();
		data_st['jumlah_roll'] = ini.find('.roll-'+gudang_id).val();
		data_st['gudang_id'] = $(this).attr('name');

		console.log(data_st);
		
		ajax_data_sync(url,data_st).done(function(data_respond  /*,textStatus, jqXHR*/ ){
			// console.log(data_respond);
			if(data_respond == 'OK'){
				update_row_total(ini);
				notific8("lime",'Updated');
			}else{
				alert("Error mohon refresh/reload halaman");
			}
   		});
   	});

   	$("#barang_id_select").change(function(){
   		$('#form-filter-barang').submit();
   	});

   	// $('#general_table .persen').each(function(){
   	// 	let ini = $(this).closest('tr');
   	// 	change_bg_row(ini, $(this).html());
   	// });
});

function change_bg_row(ini, val){
	if(val == 0) {
		ini.css('background','#A5D6A7');
	}else if(val < 30){
		ini.css('background','#FFCA28');
	}else{
		ini.css('background','#FF8A65');
	}
}

function update_row_total(ini){
	let qty = 0;
	let roll = 0;

	let before_qty = reset_number_format(ini.find('.subtotal_beforeqty').html());
	let before_roll = parseInt(ini.find('.subtotal_beforeroll').html());

	ini.find('.qty-number').each(function(i,v){
		let qty_now = (($(this).val() != '') ? $(this).val() : '0' );
		qty += parseFloat(reset_number_format(qty_now));
	});

	ini.find('.jumlah-roll').each(function(i,v){
		let roll_now = (($(this).val() != '') ? $(this).val() : '0' );
		roll += parseFloat(roll);
	});

	ini.find('.subtotal').html(qty);
	ini.find('.subtotal_roll').html(roll);

	ini.find('.selisih').html(before_qty - qty);
	ini.find('.selisih_roll').html(before_roll - roll);
	let persen = (before_qty - qty)/(before_qty == 0 ? 1 : before_qty) * 100;
	ini.find('.persen').html(persen);
	change_bg_row(ini, persen);

}
</script>
