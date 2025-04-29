<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css'); ?>"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css'); ?>"/>
<?=link_tag('assets/global/plugins/bootstrap-datepicker/css/datepicker3.css'); ?>
<link href="<?=base_url('assets/global/plugins/bootstrap-modal/css/bootstrap-modal.css');?>" rel="stylesheet" type="text/css"/>

<?=link_tag('assets/global/plugins/select2/select2.css'); ?>
<link href="<?=base_url('assets_noondev/css/bootstrap-modal-bs3patch.css');?>" rel="stylesheet" type="text/css"/>

<style type="text/css">
#general_table tr th{
	vertical-align: middle;
	text-align: center;
	/*font-size: 0.95em;*/

}

#general_table tr td{
	color:#000;
	/*font-size: 0.8em;*/
	/*font-family: Arial;*/
	/*font-size: 12px;*/
}

#general_table{
	border-bottom: 2px solid #ddd;
}

.nama-barang-column{
	 min-width:200px !important;
}

.ket-lunas{
	padding: 0;
	margin: 0;
}

.ket-lunas li{
	list-style-type: none;
	padding: none;
	margin: none;
}

.pembayaran-info{
	font-weight: bold;
}

.card{
	position: absolute;
	background: white;
	padding: 10px;
	width: 200px;
	right: 80px;
	top: 0;
	border: 1px solid #ddd;
}

#general_table hr{
	border-top:1px solid #ccc;
}

.remove-penyesuaian:hover i{
	font-size:1.5em;
}

@media print {

	* {
		-webkit-print-color-adjust: exact;
		print-color-adjust: exact;
	}

	a[href]:after {
	    content: none !important;
	}

	.nama-barang-column{
		 width:200px !important;
	}

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
						<table width='100%'>
							<tr>
								<td>
									<form id='form-filter' action='' method='get'>
										<table>
											<tr>
												<td>Tanggal</td>
												<td>:</td>
												<td>
													<b>
														<input name='tanggal_start' readonly class='date-picker' style='border:none; border-bottom:1px solid #ddd; width:100px;' value='<?=is_reverse_date($tanggal_start);?>'>
														s/d
														<input name='tanggal_end' readonly class='date-picker2 ' style='border:none; border-bottom:1px solid #ddd; width:100px;' value='<?=is_reverse_date($tanggal_end);?>'> 
													</b>
												</td>
											</tr>
											<tr>
												<td>Gudang</td>
												<td> : </td>
												<td>
													<b>
														<select name='gudang_id' style='width:100%'>
															<option <?=($gudang_id == "" ? "selected" : "");?> value=''>Semua</option>
															<?foreach ($this->gudang_list_aktif as $row) { ?>
																<option <?=($gudang_id == $row->id ? "selected" : "");?> value="<?=$row->id?>"><?=$row->nama;?></option>
															<?}?>
														</select>
													</b>
												</td>
											</tr>
											<tr>
												<td>Barang</td>
												<td class='padding-rl-5'> : </td>
												<td>
													<select name='barang_id' id='barang_id_select' style="width:200px;">
														<option <?=($barang_id == "" ? "selected" : "");?> value=''>Semua</option>
														<?foreach ($this->barang_list_aktif as $row) { 
															if ($row->status_aktif == 1) {?>
																<option <?=($barang_id == $row->id ? "selected" : "");?> value='<?=$row->id;?>'><?=$row->nama_jual;?></option>
															<?}?>
														<?}?>
													</select>
												</td>
											</tr>
											<tr>
												<td>Warna</td>
												<td class='padding-rl-5'> : </td>
												<td>
													<select name='warna_id' id="warna_id_select" style="width:200px;">
														<option <?=($warna_id == "" ? "selected" : "");?> value=''>Semua</option>
														<?foreach ($this->warna_list_aktif as $row) { 
															if ($row->status_aktif == 1) {?>
																<option <?=($warna_id == $row->id ? "selected" : "");?> value='<?=$row->id;?>'><?=$row->warna_jual;?></option>
															<?}?>
														<?}?>
													</select>
												</td>
											</tr>
											<tr>
												<td></td>
												<td></td>
												<td>
													<button class='btn btn-xs default' style='width:100%'><i class='fa fa-search'></i></button>
												</td>
											</tr>
										</table>
									</form>
								</td>
								
							</tr>
						</table>
									
						<hr/>
						<!-- table-striped table-bordered  -->
						<table class="table table-bordered table-hover table-striped " id="general_table">
							<thead>
								<tr style='background:#eee' >
									<th scope="col">
										No
									</th>
									<th scope="col">
										TANGGAL
									</th>
									<th scope="col">
										LOKASI
									</th>
									<th scope="col">
										WARNA
									</th>
									<th scope="col">
										QTY
									</th>
									<th scope="col">
										ROLL
									</th>
									<th scope="col">
										Dokumen
									</th>
								</tr>
							</thead>
							<tbody>
								<?$i = 1;
								foreach ($mutasi_list as $row) {?>
									<tr>
										<td><?=$i;?></td>
										<td><?=is_reverse_date($row->tanggal);?></td>
										<td><?=$row->nama_gudang?></td>
										<td><?=$row->nama_barang?> <?=$row->nama_warna?></td>
										<td><?=$row->qty?></td>
										<td><?=$row->jumlah_roll?></td>
										<td><?=$row->no_dokumen?></td>
									</tr>
								<?$i++;}?>
							</tbody>
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
<script src="<?php echo base_url('assets_noondev/js/table-advanced.js'); ?>"></script>

<script src="<?php echo base_url('assets/global/plugins/select2/select2.min.js'); ?>" type="text/javascript" ></script>
<script>
jQuery(document).ready(function() {
	// Metronic.init(); // init metronic core components
	// Layout.init(); // init current layout
	// TableAdvanced.init();

	$("#general_table").on("click",".btn-view-card", function(){
		$(this).closest('tr').find(".card").toggle();
	});



	// alert("<?=current_url();?>");
	$('#select_customer, #barang_id_select, #warna_id_select').select2({});

	$(".btn-normal").click(function(){
		$("#view-type").val('0');
		$("#form-filter").submit();
	});
	$(".btn-print").click(function(){
		$("#view-type").val('1');
		$("#form-filter").submit();
	});


});

<?if (is_posisi_id() == 1) {?>
	function remove_penyesuaian_stok(id, stok_opname_id){
		// $(`.qty-${id}`).css('text-decoration','line-through');

		const data = {};
		data['penyesuaian_stok_id'] = id;
		data['stok_opname_id'] = stok_opname_id;
		const url = 'report/penyesuaian_stok_opname_remove';
		ajax_data_sync(url,data).done(function(data_respond  ,textStatus, jqXHR ){
			console.log(jqXHR);
			if (data_respond == 'OK') {
				$(`.qty-${id}`).css('text-decoration','line-through');
			}
		});
	}

	function updatePenyesuaian(column,id){
		const value = document.querySelector(`#${column}-${id}`).value; 
		const data = {};
		data['penyesuaian_stok_id'] = id;
		data['column'] = column;
		data['value'] = value;
		const url = 'report/penyesuaian_stok_opname_update';
		ajax_data_sync(url,data).done(function(data_respond  ,textStatus, jqXHR ){
			notific8("lime",data_respond);
		});
	}
<?}?>
</script>
