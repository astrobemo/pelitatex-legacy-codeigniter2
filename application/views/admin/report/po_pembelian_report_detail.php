<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css'); ?>"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css'); ?>"/>
<?php echo link_tag('assets/global/plugins/bootstrap-datepicker/css/datepicker3.css'); ?>
<link href="<?=base_url('assets/global/plugins/bootstrap-modal/css/bootstrap-modal.css');?>" rel="stylesheet" type="text/css"/>

<style type="text/css">
.tbl-rinci tr td{
	padding: 2px 10px;
}

.bg-selected{
	/*border: 2px solid #FFB6C1;*/
	border: 2px solid green;
}

.popover-cell{
	position:relative;
	min-width:50px;
}

.info-popover{
	position:absolute;
	height:100%;
	width:100%;
	top:0px;
	left:0px;
	text-decoration:none;
	font-size:1.1em;
	color:black;
}

@media print {
  a[href]:after {
    content: none !important;
  }
}
</style>

<div class="page-content">
	<div class='container'>

		<?$po_number= '';foreach ($po_batch_data as $row) {
			$batch = $row->batch;
			$tanggal_po = $row->tanggal;
			// $po_pembelian_batch_id = $row->id;
			$po_number = $row->po_number_lengkap;
		}

		?>

		

		<div class="row margin-top-10">
			<div class="col-md-12">
				<div class="portlet light">
					<div class="portlet-title">
						<div class="caption caption-md">
							<i class="icon-bar-chart theme-font hide"></i>
							<span class="caption-subject theme-font bold uppercase"><?=$breadcrumb_small;?></span>
						</div>
						<div class="actions hidden-print">
							LIHAT PO LAIN : 
							<select class='po-pembelian-list' style="font-size:1.2em" id="other-po">
								<?foreach ($po_pembelian_report as $row) {
									$selected = ''; 
									if ($row->po_pembelian_id == $po_pembelian_id && $row->batch_id == $po_pembelian_batch_id ) {
										$selected = 'selected';
										$po_number = $row->po_number;
									}
									?>
									<option <?=$selected;?> value="id=<?=$row->po_pembelian_id;?>&batch_id=<?=$row->id;?>"><?=$row->po_number;?></option>
								<?}?>
							</select>
						</div>
					</div>
					<div class="portlet-body">
						<table style='font-size:1.5em'>
							<tr>
								<td>NO PO</td>
								<td class='padding-rl-5'> : </td>
								<td>
									<?if (is_posisi_id() == 6) {?>
										<?=$po_number?>
									<?}else{?>
										<a href="<?=base_url().is_setting_link('transaction/po_pembelian_detail_warna_batch');?>?id=<?=$po_pembelian_id;?>&batch_id=<?=$po_pembelian_batch_id;?>" target='_blank'><?=$po_number?></a>
									<?}?>
								</td>
							</tr>
							<tr>
								<td>Tanggal</td>
								<td class='padding-rl-5'> : </td>
								<td><?=is_reverse_date($tanggal_po);?></td>
							</tr>
						</table>
						<hr/>
						<table class="table table-hover table-striped table-bordered" id="general_table">
							<thead>
								<tr>
									<th scope="col">
										Nama Barang
									</th>
									<th scope="col">
										PO Qty
									</th>
									<th scope="col">
										Pengiriman 2019
									</th>
									<th scope="col" colspan='10'>
										Pengiriman
									</th>
									<th scope="col" >
										Retur
									</th>
									<th scope="col">
										Balance
									</th>
								</tr>
							</thead>
							<tbody>
								<?foreach ($po_pembelian_detail_report as $row) { 
									$qty_beli = explode(',', $row->qty_beli);
									$pembelian_id = explode(',', $row->pembelian_id);
									$tanggal_beli = explode(',', $row->tanggal_beli);
									$last_datang = date('Y-m-d', strtotime($row->last_datang));
									$no_faktur = explode(',', $row->no_faktur);
									$baris = count($qty_beli) / 10;
									$baris = ceil($baris);
									$total_beli = 0;
									foreach ($qty_beli as $key => $value) {
										$total_beli += $value;
									}

									$locked_stat = 0;
									if ($row->locked_date != '') {
										$locked_stat = 1;
									}

									$persen = ($total_beli + $row->qty_2019) / $row->po_qty * 100 ;
									?>
									<?for ($i=0; $i <= $baris ; $i++) { ?>
									<tr class="<?=($locked_stat==1 ? 'bg-selected' : '');?>" style="<?=($persen > 90 ? 'background-color:#e1ecfc' : '' )?>">
									<?if ($i == 0) {?>
										<td rowspan='<?=$baris+1;?>'>
											<b style='font-size:1.1em' class='nama-barang'><?=($row->tipe_barang == 3 ? $row->nama_baru : $row->nama_barang);?> <?=$row->nama_warna;?></b>
											<?if ($row->tipe_barang == 2 ) {?>
												<br/><span style='font-size:0.9em'><i class='fa fa-info' style='color:red'></i>ORI : <?=$row->nama_baru;?> </span>
											<?}?>
										</td>
										<td rowspan='<?=$baris+1;?>' style='font-size:1.2em'>
											<?=number_format($row->po_qty,'0',',','.').' '.$row->nama_satuan;?>
										</td>
										<td rowspan='<?=$baris+1;?>' style='font-size:1.2em'>
											<?=number_format($row->qty_2019,'0',',','.').' '.$row->nama_satuan;?>
										</td>
									<?}?>
												<?if ($i != $baris) {?>
												<?for ($j=0; $j < 10 ; $j++) {?>
														<td class='popover-cell'>
															<?$idx = ($i*10)+$j; 
															if (isset($qty_beli[$idx]) && $qty_beli[$idx] != 0 ) {?>
																<a href="<?=base_url().is_setting_link('transaction/pembelian_list_detail')?>/<?=$pembelian_id[$idx]?>" class='hidden-print info-popover' data-toggle="popover" data-trigger='hover' title="Info" data-html='true' data-content="No Invoice : <b><?=$no_faktur[$idx]?></b> <br/>Tanggal : <b><?=is_reverse_date($tanggal_beli[$idx]);?></b>">
																	<a target='_blank' style='color:black'  >
																		<?=str_replace(',00', '', number_format($qty_beli[$idx],'2',',','.'));?>
																	</a> 
																	<!-- <i class='fa fa-info-circle' ></i> -->
																</a>
															<?}
															?>
														</td>
													<?}?>
												<?}else{?>
														<td colspan='10' style='font-size:1.2em'>
															<b>TOTAL</b> : 
															<b>
																<?=str_replace(',00', '', number_format($total_beli,'2',',','.')).' '.$row->nama_satuan;?>
															</b>
														</td>
													<?}?>
									<?if ($i == 0) {?>
										<td rowspan='<?=$baris+1?>' style='font-size:1.2em'><?=number_format($row->qty_retur,'0',',','.');?></td>
										<td rowspan='<?=$baris+1?>' style='font-size:1.2em'>
											<span class='po_pembelian_warna_id' hidden><?=$row->po_pembelian_warna_id;?></span>
											<?=number_format($row->po_qty - $total_beli - $row->qty_2019 + $row->qty_retur,'0',',','.').' '.$row->nama_satuan;?><br/>
											<div id='checkbox-<?=$row->po_pembelian_warna_id;?>' style="<?=($locked_stat == 1 ? 'display:none' : '')?>">
												<label style='background:#ddd; padding:5px 10px 5px 5px' >
												<input type='checkbox' class='lock-po' id="box-<?=$row->po_pembelian_warna_id;?>" data-id='<?=$row->po_pembelian_warna_id;?>' data-last="<?=($last_datang == '' ? date('Y-m-d') : $last_datang);?>">LOCK</label>	
												<?=(is_posisi_id() == 1 ? $row->po_pembelian_warna_id.'||'.($last_datang == '' ? date('Y-m-d') : $last_datang) : '');?>
											</div>
											<!-- <button style="<?=($locked_stat == 1 ? 'display:none' : '')?>" class='btn btn-md yellow lock-po hidden-print'><i class='fa fa-unlock'></i> LOCK</button> -->
											<button style="<?=($locked_stat == 0 ? 'display:none' : '')?>" class='btn btn-md default unlock-po hidden-print'><i class='fa fa-lock'></i> LOCKED</button>
											<?=($locked_stat == 1 ? '<br/>' : '')?>
											<small style="font-size:0.8em" <?=($locked_stat == 0 ? 'hidden' : '')?> class='hidden-print locked-info' >locked <?=$row->locked_date?> by <?=$row->username?></small>
										</td>
									<?}?>	
										</tr>
									<?};?>
									<tr>
										<td colspan='15'></td>
									</tr>
								<?}?>
							</tbody>
						</table>

						<div>
							<button class='btn btn-lg blue hidden-print' onclick="window.print()"><i class='fa fa-print'></i> Print</button>
							<button class='btn btn-lg red hidden-print lock-po-selected' ><i class='fa fa-lock'></i> LOCK</button>
						</div>
						
					</div>
				</div>
			</div>
		</div>
	</div>			
</div>

<script src="<?php echo base_url('assets/global/plugins/bootstrap-modal/js/bootstrap-modalmanager.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/bootstrap-modal/js/bootstrap-modal.js'); ?>" type="text/javascript"></script>

<script src="<?php echo base_url('assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/datatables/media/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/bootbox/bootbox.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets_noondev/js/table-advanced.js'); ?>"></script>

<script>
var po_warna_id = [];
var po_last_datang = [];
jQuery(document).ready(function() {

	// dataTableTrue();

	// TableAdvanced.init();

	// oTable = $('#general_table').DataTable();
	// oTable.state.clear();
	// oTable.destroy();
   	$('[data-toggle="popover"]').popover();	  	
   	$('#other-po').select2();
   	var lock_po_nama = {};


	$('.btn-save').click(function(){
		if ($('#form_add_data [name=tanggal]').val() != '' ) {
			// $('#form_add_data').submit();
			if ($('#form_add_data [name=tanggal]').prop('selectedIndex') != 0) {
				bootbox.confirm("Disarankan untuk membuat penutupan <b>sesuai urutan bulan</b>. <br/>Yakin lanjutkan ?", function(respond){
					if (respond) {
						$('#form_add_data').submit();
					};
				});
			}else{
				$('#form_add_data').submit();
			};
		}else{
			alert('Tanggal harus diisi');
		}
	});

	/*$("#general_table").on('change','.lock-po', function(){
		var ini = $(this).closest('tr');
		var id = $(this).attr('data-id');
		if ($(this).is(':checked')) {
			lock_po_nama[id] = ini.find('.nama-barang').html();
		}else{
			var lock_po_nama_ori = lock_po_nama;
			lock_po_nama = {};
			lock_po = {};
			$.each(lock_po_nama_ori, function(i,v){
				if (id != i) {
					lock_po_nama[i]=v;
				};
			});
		}
	});*/

	/*$("#general_table .lock-po").each(function(){
		po_warna_id = [];
		var ini = $(this).closest('tr');
		var id = $(this).attr('data-id');
		var last_datang = $(this).attr('data-last');
		if ($(this).is(':checked')) {
			po_warna_id.push(id);
			po_last_datang.push(last_datang);
		}
	});*/

	$('.lock-po-selected').click(function(){
		var button_ini = $(this);
		var list_nama = '';
		var po_id_list = [];
		var j = 1;
		po_warna_id = [];

		$("#general_table .lock-po").each(function(){
			var ini = $(this).closest('tr');
			var id = $(this).attr('data-id');
			var last_datang = $(this).attr('data-last');
			if ($(this).is(':checked')) {
				// console.log(id);
				po_warna_id.push(id);
				po_last_datang.push(last_datang);
			}
		});


		$.each(lock_po_nama, function(i,v){
			list_nama += '- '+v;
			po_id_list.push(i);
			if (j != Object.keys(lock_po_nama).length) {
				list_nama += '<br/> ';
			};
			j++;
		});
		// if (Object.keys(lock_po_nama).length > 0) {			
		if (po_warna_id.length > 0) {			
			bootbox.confirm("Lock PO ? :<br/> "+list_nama, function(respond){
				if (respond) {
					$.each(po_id_list, function(i,v){
						var ini = $("#checkbox-"+v).closest('tr');
						ini.find('.loading-info').show();
					});

					let data = {};
					/*data['po_pembelian_warna_id'] = po_id_list.join('??');
					let url = 'report/po_pembelian_warna_lock';
					ajax_data_sync(url,data).done(function(data_respond ){
						if (data_respond == 'OK') {
							$.each(po_id_list, function(i,v){
								$("#box-"+v).prop('checked',false);
								$("#checkbox-"+v).hide();
								var ini = $("#checkbox-"+v).closest('tr');
								ini.addClass('bg-selected');
								ini.find(".unlock-po").show();
								ini.find('.loading-info').hide();
							});
							$.uniform.update($('.lock-po'));
							lock_po_nama = {};
						};
					});*/

					var po_warna_list = po_warna_id.join(',');
					var po_warna_last = po_last_datang.join(',');
					var data_st = {};

					data['po_warna_list'] = po_warna_list;
					data['last_datang'] = po_warna_last;
					console.log(po_warna_list);
					const url = "report/po_pembelian_warna_lock_by_list";

					ajax_data_sync(url,data).done(function(data_respond ){
						if (data_respond == 'OK') {
							$.each(po_warna_id, function(i,v){
								$("#box-"+v).prop('checked',false);
								$("#checkbox-"+v).hide();
								var ini = $("#checkbox-"+v).closest('tr');
								ini.addClass('bg-selected');
								ini.find(".unlock-po").show();
								ini.find('.loading-info').hide();
							});
							$.uniform.update($('.lock-po'));
							po_warna_id = {};
						};
					});

				};
			});
		};
	});

	$(document).on('click','.unlock-po', function(){
		var button_ini = $(this);
		var ini = $(this).closest('tr');
		var po_pembelian_warna_id = ini.find('.po_pembelian_warna_id').html();
		bootbox.confirm("<span style='color:red'>Unlock</span> PO <b>"+ini.find('.nama-barang').html()+"</b> ini?", function(respond){
			if (respond) {
				let data = {};
					data['po_pembelian_warna_id'] = ini.find('.po_pembelian_warna_id').html();
					console.log(data['po_pembelian_warna_id']);
					let url = 'report/po_pembelian_warna_unlock';
					ajax_data_sync(url,data).done(function(data_respond  /*,textStatus, jqXHR*/ ){
						if (data_respond == 'OK') {
							button_ini.hide();
							ini.removeClass('bg-selected');
							ini.find(".lock-po").show();
							ini.find('.locked-info').hide();
							$("#checkbox-"+po_pembelian_warna_id).show();
							ini.find('.loading-info').hide();
						};
					});
			};
		});
	});

	$("#other-po").change(function(){
		var other_po = $(this).val();
		window.location.replace(baseurl+"<?=is_setting_link('report/po_pembelian_report_detail')?>"+'?'+other_po);
	});

});

function change_bg(ini){
	ini.find('td').addClass('.bg-selected');
}
</script>
