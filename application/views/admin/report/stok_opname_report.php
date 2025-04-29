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
															<option <?=($gudang_id == 0 ? "selected" : "");?> value='0'>Semua</option>
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
														<option <?=($barang_id == 0 ? "selected" : "");?> value='0'>Semua</option>
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
														<option <?=($warna_id == 0 ? "selected" : "");?> value='0'>Semua</option>
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
								<td class='text-right'>
									<form action='<?=base_url();?>report/stok_opname_report_excel' method='get'>
										<input name='tanggal_start' value='<?=$tanggal_start;?>' hidden>
										<input name='tanggal_end' value='<?=$tanggal_end;?>' hidden>
										<input name='barang_id' value='<?=$barang_id;?>' hidden>
										<input name='warna_id' value='<?=$warna_id;?>' hidden>
										<input name='gudang_id' value='<?=$gudang_id;?>' hidden>

										<button <?=(count($so_list) == 0 ? "disabled" : "");?> class='hidden-print btn green'><i class='fa fa-download'></i> Excel</button>
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
										No SO
									</th>
									<th scope="col">
										TANGGAL
									</th>
									<th scope="col">
										LOKASI
									</th>
									<th scope="col">
										BARANG
									</th>
									<th scope="col">
										QTY
									</th>
									<th scope="col">
										ROLL
									</th>
									<th scope="col">
										PENYESUAIAN
									</th>
									<th scope="col">
										PETUGAS
									</th>
								</tr>
							</thead>
							<tbody>
								<?$total_error=0;
								foreach ($so_list as $row) {
									$tanggal_so = explode(',', $row->tanggal_so);
									// print_r($tanggal_so);
									$penyesuaian_stok_id = explode(",", $row->penyesuaian_stok_id);
									$nama_barang = explode(',', $row->nama_barang);
									$barang_id = explode(',', $row->barang_id);
									$nama_warna = explode(',', $row->nama_warna);
									$warna_id = explode(',', $row->warna_id);
									$gudang_id = explode(',', $row->gudang_id);
									$nama_gudang = explode(',', $row->nama_gudang);
									$stok_opname_id = explode(',', $row->stok_opname_id);
									$qty = explode(',', $row->qty_data);
									$jumlah_roll = explode(',', $row->jumlah_roll_data);

									$qty_current = explode(',', $row->qty_current);
									$roll_current = explode(',', $row->jumlah_roll_current);
									
									$qty_penyesuaian = explode(',', $row->qty_data_penyesuaian);
									$jumlah_roll_penyesuaian = explode(',', $row->jumlah_roll_data_penyesuaian);
									$subtotal = 0;
									$sub_roll = 0;
									?>
									<tr>
										<td class='text-center' style='width:120px'><?=$row->no_so;?></td>
										<td class='text-center' style='width:120px'><?foreach ($tanggal_so as $key => $value) {
											if ($key % 5 == 0 && $key != 0) {?>
												<hr style='margin:0px 5px; padding:0px;'>
											<?}
											echo $value."<br/>";
										}?></td>
										<td class='text-center'>
											<?foreach ($nama_gudang as $key => $value) {
											if ($key % 5 == 0 && $key != 0) {?>
												<hr style='margin:0px 5px; padding:0px;'>
											<?}
											echo /* (is_posisi_id()== 1 ? $stok_opname_id[$key].' ' : ''). */ $value."<br/>";
										}?></td>
										<td  class='text-left'>
											<?foreach ($nama_barang as $key => $value) {
												$ts = is_date_formatter(substr($tanggal_so[$key], 0, 10));
												if ($key % 5 == 0 && $key != 0) {?>
													<hr style='margin:0px 5px; padding:0px;'>
												<?}?>
												<?if (is_posisi_id() == 1 || is_posisi_id() == 6 || is_posisi_id() == 7) { ?>
													<a target="_blank" href="<?=base_url().is_setting_link('inventory/kartu_stok')?>/<?=$gudang_id[$key]?>/<?=$barang_id[$key]?>/<?=$warna_id[$key]?>?tanggal_start=<?=is_date_formatter($ts)?>&tanggal_end=<?=is_date_formatter($ts)?>">
														<span class='qty-<?=$penyesuaian_stok_id[$key];?>'><?=$value.' '.$nama_warna[$key];?></span><?=(is_posisi_id()==1 ? $barang_id[$key].'--'.$warna_id[$key] : '')?><br/>
													</a>
												<?}else{?>
													<span class='qty-<?=$penyesuaian_stok_id[$key];?>'><?=$value.' '.$nama_warna[$key];?></span><?=(is_posisi_id()==1 ? $barang_id[$key].'--'.$warna_id[$key] : '')?><br/>
												<?}?>
											<?}?>
											<hr style='margin:2px; padding:0;; border-color:#999'/>
											<b>TOTAL</b>
										</td>
										<td class='text-right' style='padding-right:20px'>
											<?foreach ($qty as $key => $value) {
												if ($key % 5 == 0 && $key != 0) {?>
													<hr style='margin:0px 5px; padding:0px;'>
												<?}
												$subtotal += $value;
												echo "<span class='qty-$penyesuaian_stok_id[$key]'>".str_replace(',00', "", number_format($value,'2',',','.'))."</span>";
												if (is_posisi_id()==1) {?>
													<span class='remove-penyesuaian' onclick="remove_penyesuaian_stok('<?=$penyesuaian_stok_id[$key];?>','<?=$stok_opname_id[$key]?>')" style='cursor:pointer'><i class='fa fa-times'></i></span>
												<?}
												echo "<br/>";
											}?>
											<hr style='margin:2px; padding:0;; border-color:#999'/>
											<b><?=str_replace(',00', "", number_format($subtotal,'2',',','.'))?></b>
										</td>
										<td class='text-right' >
											<?foreach ($jumlah_roll as $key => $value) {
												if ($key % 5 == 0 && $key != 0) {?>
													<hr style='margin:0px 5px; padding:0px;'>
												<?}
												$sub_roll += $value;
												echo $value."<br/>";
											}?>
											<hr style='margin:2px; padding:0; border-color:#999'/>
											<b><?=$sub_roll?></b>
										</td>
										<td>
											<?foreach ($qty_penyesuaian as $key => $value) {
												$tepat = 1;
												if ($qty_current[$key] + $value != $qty[$key] || $roll_current[$key] + $jumlah_roll_penyesuaian[$key] != $jumlah_roll[$key]) {
													$tepat = 0;
													$total_error++;
												}
												if ($key % 5 == 0 && $key != 0) {?>
													<hr style='margin:0px 5px; padding:0px;'>
												<?}
												if ($tepat == 0) {
													echo $value;
												}else{
													echo $value;
												}
												// echo $value;
												if (is_posisi_id() == 1 || is_posisi_id() == 6 || is_posisi_id() == 7) {
													// echo "--".$jumlah_roll_penyesuaian[$key];
													// echo "**".$qty_current[$key];
													// echo "**".$roll_current[$key];
													?>
														<input style="width: 80px; background:lightpink;border:none" id='qty-<?=$penyesuaian_stok_id[$key]?>' value="<?=$value?>" onchange="updatePenyesuaian('qty','<?=$penyesuaian_stok_id[$key]?>')">
														<input style="width: 80px; background:lightgreen;border:none" id='jumlah_roll-<?=$penyesuaian_stok_id[$key]?>' value="<?=$jumlah_roll_penyesuaian[$key]?>" onchange="updatePenyesuaian('jumlah_roll','<?=$penyesuaian_stok_id[$key]?>')">
													<?
												}
												echo "<br/>";

											}?>
										</td>
										<td><?=$row->keterangan;?></td>
									</tr>
								<?}?>
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

<?if (is_posisi_id() == 1 || is_posisi_id() == 6 || is_posisi_id() == 7) {?>
	function remove_penyesuaian_stok(id, stok_opname_id){

		bootbox.confirm("Yakin Hapus ?", function(respond){
			if(respond){
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
		});
		// $(`.qty-${id}`).css('text-decoration','line-through');
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
