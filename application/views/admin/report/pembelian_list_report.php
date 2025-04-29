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
	font-size: 0.95em;

}

#general_table tr td{
	color:#000;
	font-size: 0.95em;
	/*font-family: Arial;*/
	/*font-size: 12px;*/
}

#general_table{
	border-bottom: 2px solid #ddd;
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
									<form action='' method='get'>
										<table>
											<tr>
												<td>Tanggal</td>
												<td class='padding-rl-5'> : </td>
												<td>
													<input name='tanggal_start' readonly class='date-picker' style='border:none; border-bottom:1px solid #ddd; width:100px;' value='<?=$tanggal_start;?>'>
													s/d
													<input name='tanggal_end' readonly class='date-picker2 ' style='border:none; border-bottom:1px solid #ddd; width:100px;' value='<?=$tanggal_end;?>'> 
												</td>
											</tr>
											<tr>
												<td>Filter BY</td>
												<td class='padding-rl-5'> : </td>
												<td>
													<label>
														<input <?=($tipe_tanggal == 1 ? 'checked': '')?> type="radio" name="tipe_tanggal" id="filterTanggalSurat" value='1'> By Tanggal Surat Jalan </label>
													<label>
														<input <?=($tipe_tanggal == 2 ? 'checked': '')?> type="radio" name="tipe_tanggal" id="filterTanggalInput"  value='2'>By Tanggal Input</label>
												</td>
											</tr>
											<tr>
												<td>Toko</td>
												<td class='padding-rl-5'> : </td>
												<td>
													<select name='toko_id'>
														<?foreach ($this->toko_list_aktif as $row) { ?>
															<option <?=($toko_id == $row->id ? "selected" : "");?> value='<?=$row->id;?>'><?=$row->nama;?></option>
														<?}?>
													</select>
												</td>
											</tr>
											<tr>
												<td>Lokasi</td>
												<td class='padding-rl-5'> : </td>
												<td>
													<select name='gudang_id'>
														<option <?=($gudang_id == 0 ? "selected" : "");?> value='0'>Semua</option>
														<?foreach ($this->gudang_list_aktif as $row) { ?>
															<option <?=($gudang_id == $row->id ? "selected" : "");?> value='<?=$row->id;?>'><?=$row->nama;?></option>
														<?}?>
													</select>
												</td>
											</tr>											
											<tr>
												<td>Supplier</td>
												<td class='padding-rl-5'> : </td>
												<td>
													<select name='supplier_id'>
														<option <?=($supplier_id == 0 ? "selected" : "");?> value='0'>Semua</option>
														<?foreach ($this->supplier_list_aktif as $row) { ?>
															<option <?=($supplier_id == $row->id ? "selected" : "");?> value='<?=$row->id;?>'><?=$row->nama;?></option>
														<?}?>
													</select>
												</td>
											</tr>
											<tr>
												<td>Barang</td>
												<td class='padding-rl-5'> : </td>
												<td>
													<select name='barang_beli_id' id='barang_id_select' style='width:200px'>
														<option <?=($barang_beli_id == 0 ? "selected" : "");?> value='0'>Semua</option>
														<?foreach ($this->barang_list_aktif_beli as $row) { ?>
															<option <?=($barang_beli_id == $row->id ? "selected" : "");?> value='<?=$row->id;?>'><?=$row->nama;?></option>
														<?}?>
													</select>
												</td>
											</tr>
											<tr>
												<td>Warna</td>
												<td class='padding-rl-5'> : </td>
												<td>
													<select name='warna_id' id="warna_id_select" style='width:200px'>
														<option <?=($warna_id == 0 ? "selected" : "");?> value='0'>Semua</option>
														<?foreach ($this->warna_list_aktif as $row) { ?>
															<option <?=($warna_id == $row->id ? "selected" : "");?> value='<?=$row->id;?>'><?=$row->warna_beli;?></option>
														<?}?>
													</select>
												</td>
											</tr>
											<tr>
												<td colspan='3' class='text-center'>
													<button class='btn btn-xs default' style='width:100%; margin-top:10px;'><i class='fa fa-search'></i> Cari</button>
												</td>
											</tr>
										</table>
										
									</form>
								</td>
								<td class='text-right'>
									<form action='<?=base_url();?>report/pembelian_list_export_excel' method='get'>
										<div hidden>
											<input name='tanggal_start' value='<?=$tanggal_start;?>' >
											<input name='tanggal_end' value='<?=$tanggal_end;?>' >
											<input name='toko_id' value='<?=$toko_id;?>' >
											<input name='gudang_id' value='<?=$gudang_id;?>' >
											<input name='barang_beli_id' value='<?=$barang_beli_id;?>' >
											<input name='barang_id' value='<?=$barang_id;?>' >
											<input name='warna_id' value='<?=$warna_id;?>' >
											<input name='supplier_id' value='<?=$supplier_id;?>' >
											<input name='tipe_tanggal' value='<?=$tipe_tanggal;?>' >
											<input name='tipe_supplier' value='<?=$tipe_supplier;?>' >
										</div>
										<button class='btn green'><i class='fa fa-download'></i> Excel <?=($tipe_tanggal == 1 ? '(by tanggal surat jalan)' : '(by tanggal input)' )?></button>
									</form>
								</td>
							</tr>
						</table>
									
						<hr/>

						<div class="tabbable tabbable-custom">
							<ul class="nav nav-tabs">
								<li class="active">
									<a href="#tab_1_1" data-toggle="tab">
									Pembelian </a>
								</li>
								<li class="">
									<a href="#tab_1_2" data-toggle="tab">
									Pembelian Lain2 </a>
								</li>
								<li class="">
									<a href="#tab_1_3" data-toggle="tab">
									RETUR Pembelian </a>
								</li>
							</ul>

							<div class="tab-content">
								<div class="tab-pane <?=($tipe_supplier==1 ? 'active' : '');?>" id="tab_1_1">
									<table class="table table-hover table-striped table-bordered " id="general_table">
										<thead>
											<tr style='background:#eee' >
												<th scope="col" style='width:70px !important;'>
													No Faktur
												</th>
												<th scope="col" style="min-width:125px" >
													NO PO 
												</th>
												<th scope="col">
													Tgl
												</th>
												<th scope="col">
													Tgl<br/>
													Input
												</th>
												<?if (is_posisi_id()==1) {?>
													<th>OCKH</th>
												<?}?>
												<th scope="col">
													Jumlah <br/>
													<small>Yard/KG</small>
												</th>
												<th scope="col">
													Roll
												</th>
												<th scope="col" style='width:200px !important;'>
													Nama Barang
												</th>
												<th scope="col">
													Harga
												</th>
												<th scope="col">
													Total
												</th>
												<th scope="col" class='status_column'>
													Diskon
												</th>
												<th scope="col">
													Nama <br/> Supplier
												</th>
												<th scope="col">
													Lokasi
												</th>
												<th scope="col" hidden>
													Jth. Tempo
												</th>
												<th scope="col">
													Keterangan
												</th>
												
											</tr>
										</thead>
										<tbody>
											<?
											foreach ($this->satuan_list_aktif as $row) {
												${'g_total_'.$row->id} = 0;
												${'idx_'.$row->id} = 0;
												${'yard_total_'.$row->id} = 0;
												${'roll_total_'.$row->id} = 0;
											}
											$idx = 0; $g_total = 0; $yard_total = 0; $roll_total = 0; 
													$subtotal_roll_all=0;
											foreach ($pembelian_list as $row) { 

													$subtotal_yard = 0;
													$subtotal_roll = 0;
													$subtotal = 0;

													$qty = explode('??', $row->qty);
													$roll = explode('??', $row->jumlah_roll);
													$ockh = explode('??', $row->ockh);
													$harga_beli = explode('??', $row->harga_beli);
													$satuan_id = explode('??', $row->satuan_id);

													$tgl_jt = explode(',', $row->tgl_jt);
													$amount_jt = explode(',', $row->amount_jt);
													foreach ($qty as $key => $value) {
														$yard_total += $value;
														$roll_total += $roll[$key];
														${'yard_total_'.$satuan_id[$key]} += $value;
														${'roll_total_'.$satuan_id[$key]} += $roll[$key];
													}
												?>
												<tr class='text-center;' style="background:<?=get_supplier_color($row->kode_supplier);?>" >
													<td>
														<?if (is_posisi_id() == 1 && $row->satuan_id == '') {
															echo "yayaya";
														}?>
														<a href="<?=base_url().is_setting_link('transaction/pembelian_list_detail')?>/<?=$row->id;?>" target='_blank'><?=$row->no_faktur;?></a>
													</td>
													<td ><?=$row->po_number?></td>
													<td>
														<?=date("d/m/y",strtotime($row->tanggal));?>
													</td>
													<td>
														<?=date("d/m/y H:i",strtotime($row->created_at));?>
													</td>
													<?if (is_posisi_id() == 1) {?>
														<td>
															<?foreach ($ockh as $key => $value) {
																if ($value == '') {
																	$value = '-';
																}
																echo $value.'<br/>';
															};?>
														</td>
													<?}?>
													<td>
														<?//=str_replace('??', '<br/>', str_replace(',00', '', number_format($row->qty,'2',',','.')));?>
														<?foreach ($qty as $key => $value) {
															if ($value == '') {
																$value = 0;
															}
															$subtotal_yard += $value;
															echo str_replace(',00', '', number_format($value,'2',',','.')).'<br/>';
														};?>
														<hr style='margin:5px 0' />
														<b><?=str_replace(',00', '', number_format($subtotal_yard,'2',',','.')).'<br/>';?></b>
													</td>
													<td>
														<?=str_replace('??', '<br/>', $row->jumlah_roll);?>
														<?foreach ($roll as $key => $value) {
															$subtotal_roll += $value;
														}?>
														<hr style='margin:5px 0' />
														<b><?=str_replace(',00', '', number_format($subtotal_roll,'2',',','.')).'<br/>';?></b>
														<?if (is_posisi_id() == 1) { $subtotal_roll_all += $subtotal_roll;
															echo "<br/>".$subtotal_roll_all;
														}?>
													</td>
													<td>
														<span class='id' hidden="hidden"><?=$row->id;?></span>
														<span class='nama'><?=str_replace('??', '<br/>', $row->nama_barang);?></span> 
														<hr style='margin:5px 0' />
														Total :<b> <?=count($roll)?> Item</b>
													</td>
													<td>
														<?if ($row->harga_beli != '') {
															foreach ($harga_beli as $key => $value) {
																echo str_replace(',00','',number_format($value,'2',',','.'))."<br/>";
															}
														}?>
													</td>
													<td>
														
														<?foreach ($harga_beli as $key => $value) {
															$subtotal += $qty[$key] * $value;
															echo str_replace(',00', '', number_format($qty[$key] * $value,'2',',','.')).'<br/>';
															$g_total += $qty[$key] * $value;
															${'g_total_'.$satuan_id[$key]} += $qty[$key] * $value;
														}?>
														<hr style='margin:5px 0' />
														<b> <?=str_replace(',00', '', number_format($subtotal,'2',',','.')).'<br/>';?></b>
													</td>
													<td class='status_column'>
														<?if ($row->diskon != 0) {
															echo $row->diskon;
														};?> 
													</td>
													<td>
														<?=$row->nama_supplier;?> 
													</td>
													<td>
														<?=$row->nama_gudang;?>
													</td>
													<td hidden>
														<?=is_reverse_date($row->jatuh_tempo);?>
													</td>
													<td>
														<?//=(is_posisi_id()==1 ? abs(round($row->keterangan,0)) : '');?>
														<?if (round($row->keterangan,0) < 0) { ?>
															<span style='color:red'>belum lunas</span>
														<?}else if (abs(round($row->keterangan,0)) >= 0){
															$pembayaran_hutang_id = explode(',', $row->pembayaran_hutang_id);
															$tanggal_bayar = explode(',', $row->tanggal_bayar);
															foreach ($pembayaran_hutang_id as $key => $value) { 
																if (isset($tanggal_bayar[$key])) {?>
																	<a target='_blank' href="<?=base_url().is_setting_link('finance/hutang_payment_form');?>?id=<?=$value;?>" style='color:blue'>
																		<i class='fa fa-search'></i> <?=is_reverse_date($tanggal_bayar[$key]);?>
																	</a>
																<?}?>
															<?}
															?>
														<?}?> 
														<div style='padding:5px 0px'>
														<?/* if (is_posisi_id()==1 || is_posisi_id()==6) {
															if ($row->tgl_jt != '') {
																echo "JATUH TEMPO : <br/>";
																foreach ($tgl_jt as $key => $value) {?>
																	<?=$key+1?> : <?=date('d M y',strtotime($value))?><br/>
																	<?}
															}
														} */?>
														</div>
													</td>
												</tr>
											<? $idx++;} ?>
										</tbody>
									</table>
									<hr/>

									<table class='table'>
										<tr style='background:#eee' >
											<th scope="col" class='text-center'>
												Ket.
											</th>
											<th scope="col" class='text-center'>
												Trx.
											</th>
											<th scope="col" class='text-center'>
												Yard/KG
											</th>
											<th scope="col" class='text-center'>
												Roll
											</th>
											<th scope="col" class='text-center'>
												Total
											</th>
										</tr>
										<?foreach ($this->satuan_list_aktif as $row) {?>
											<tr style='font-size:1.2em;font-weight:bold;'>
												<td class='text-center'><?=$row->nama;?></td>
												<td class='text-center'>-</td>
												<td class='text-center'><?=number_format(${'yard_total_'.$row->id},'2',',','.');?></td>
												<td class='text-center'><?=number_format(${'roll_total_'.$row->id},'0',',','.');?></td>
												<td class='text-center'><b><?=number_format(${'g_total_'.$row->id},'0',',','.');?></b> </td>
												<td class='status_column'></td>
											</tr>
										<?}?>
										<tr style='font-size:1.2em;font-weight:bold;'>
											<td class='text-center'>TOTAL</td>
											<td class='text-center'><?=$idx;?></td>
											<td class='text-center'><?=number_format($yard_total,'2',',','.');?></td>
											<td class='text-center'><?=number_format($roll_total,'0',',','.');?></td>
											<td class='text-center'><b><?=number_format($g_total,'0',',','.');?></b> </td>
											<td class='status_column'></td>
										</tr>
									</table>
								</div>
								<div class="tab-pane <?=($tipe_supplier==2 ? 'active' : '');?>" id="tab_1_2">
									<table class="table table-hover table-striped table-bordered " id="general_table_2">
										<thead>
											<tr style='background:#eee' >
												<th scope="col" style='width:70px !important;'>
													Faktur
												</th>
												<th scope="col" style='width:100px !important;'>
													Tanggal
												</th>
												<th scope="col" style='width:300px !important;'>
													<div style='display:inline; padding-right:100px'>Barang</div>  . QTY  . Harga
												</th>
												<th scope="col">
													Total
												</th>
												<th scope="col">
													Supplier
												</th>
												<th scope="col">
													Jatuh Tempo
												</th>
												<th scope="col">
													Keterangan
												</th>
												
											</tr>
										</thead>
										<tbody>
											<?$idx = 0; $g_total = 0; 
											$subtotal = 0;
											foreach ($pembelian_list_lain as $row) {
												$keterangan_barang = explode('??', $row->keterangan_barang);
												$harga_beli = explode('??', $row->harga_beli);
												$qty = explode('??', $row->qty);
												$total = explode('??', $row->total);
												
												?>
												<tr>
													<td><?=$row->no_faktur?></td>
													<td><?=is_reverse_date($row->tanggal);?></td>
													<td>
														<table>
															<?foreach ($keterangan_barang as $key => $value) {
																$g_total += $total[$key]?>
																<tr>
																	<td style='width:150px;'><?=nl2br($value)?></td>
																	<td class='text-center' style='width:50px; vertical-align:top; font-size:1.1em'><?=$qty[$key]?></td>
																	<td style='width:50px; vertical-align:top; font-size:1.1em'><?=number_format($harga_beli[$key],'0',',','.');?></td>
																</tr>
															<?}?>
														</table>
													</td>
													<td style='font-size:1.2em'><?=number_format($g_total,'0',',','.');?></td>
													<td><?=$row->nama_supplier?></td>
													<td><?=is_reverse_date($row->jatuh_tempo);?></td>
													<td>
														<?if ($row->keterangan < 0) { ?>
															<span style='color:red'>belum lunas</span>
														<?}else if ($row->keterangan >= 0){?>
															<span style='color:blue'>lunas</span>
														<?}?>
													</td>
												</tr>
											<?}?>
										</tbody>
									</table>
								</div>
								<div class="tab-pane" id="tab_1_3">
									<table class="table table-hover table-striped table-bordered " id="general_table_retur">
										<thead>
											<tr style='background:#eee' >
												<th scope="col" style='width:70px !important;'>
													Surat Jalan
												</th>
												<th scope="col" style='width:100px !important;'>
													Tanggal<br/> RETUR
												</th>
												<th scope="col">
													Jumlah <br/>
													Yard/KG
												</th>
												<th scope="col">
													Jumlah <br/> Roll
												</th>
												<th scope="col" style='width:200px !important;'>
													Nama Barang
												</th>
												<th scope="col">
													Harga
												</th>
												<th scope="col">
													Total
												</th>
												<th scope="col">
													Nama <br/> Supplier
												</th>
												<th scope="col">
													Lokasi
												</th>
												<th scope="col">
													Keterangan
												</th>
											</tr>
										</thead>
										<tbody>
											<?
											$retur_yard_total = 0;
											$retur_roll_total = 0;
											$retur_gtotal = 0;
											$trx_retur=0;
											foreach ($pembelian_retur as $row) {
												$trx_retur++;
												$harga_beli_retur = explode('??', $row->harga_beli);
												$qty_retur = explode("??", $row->qty);
												$roll_retur = explode("??", $row->jumlah_roll);
												$subYard = 0;
												$subRoll = 0;
												$subNilai = 0;
												foreach ($harga_beli_retur as $key => $value) {
													$retur_yard_total += $qty_retur[$key];
													$retur_roll_total += $roll_retur[$key];
													$retur_gtotal += $qty_retur[$key]*$value;

													$subYard += $qty_retur[$key];
													$subRoll += $roll_retur[$key];
													$subNilai += ($qty_retur[$key]*$value);
												}
												?>
												<tr style='background:#eee' >
													<td><a href="<?=base_url().is_setting_link('transaction/retur_beli_detail')?>?id=<?=$row->id?>"><?=$row->no_sj_lengkap?></a> </td>
													<td><?=is_reverse_date($row->tanggal);?></td>
													<td>
														<?foreach ($harga_beli_retur as $key => $value) {
															echo (float)$qty_retur[$key]."<br/>";
														}?>
														<hr style="margin:0; padding:0";/>
														<b><?=$subYard;?></b>
													</td>
													<td>
														<?=str_replace("??", "<br/>",$row->jumlah_roll)?>
														<br/>
														<hr style="margin:0; padding:0";/>
														<b><?=$subRoll;?></b>
													</td>
													<td><?=str_replace("??", "<br/>", $row->nama_barang)?></td>
													<td>
														<?foreach ($harga_beli_retur as $key => $value) {
															echo number_format($value,'0',',','.').'<br/>';
														}?>
													</td>
													<td class='text-right'>
														<?foreach ($harga_beli_retur as $key => $value) {
															echo number_format($qty_retur[$key]*$value,'0',',','.').'<br/>';
														};?>
														<hr style="margin:0; padding:0";/>
														<b><?=number_format($subNilai,'0',',','.').'<br/>';?></b>

													</td>
													<td><?=$row->nama_supplier;?></td>
													<td><?=str_replace("??", "<br/>", $row->nama_gudang)?></td>
													<td>
														<?=$row->keterangan1?><br/>
														<?=$row->keterangan2?>
													</td>
											</tr>
											<?}?>
										</tbody>
									</table>

									<table class='table'>
										<tr style='background:#eee' >
											<th scope="col" class='text-center'>
												Ket.
											</th>
											<th scope="col" class='text-center'>
												Trx.
											</th>
											<th scope="col" class='text-center'>
												Yard/KG
											</th>
											<th scope="col" class='text-center'>
												Roll
											</th>
											<th scope="col" class='text-center'>
												Total
											</th>
										</tr>
										<tr style='font-size:1.2em;font-weight:bold;'>
											<td class='text-center'>TOTAL</td>
											<td class='text-center'><?=$trx_retur;?></td>
											<td class='text-center'><?=number_format($retur_yard_total,'2',',','.');?></td>
											<td class='text-center'><?=number_format($retur_roll_total,'0',',','.');?></td>
											<td class='text-center'><b><?=number_format($retur_gtotal,'0',',','.');?></b> </td>
											<td class='status_column'></td>
										</tr>
									</table>
								</div>
							</div>
						</div>
						<!-- table-striped table-bordered  -->
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
<script src="<?php echo base_url('assets/global/plugins/datatables/media/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets_noondev/js/table-advanced.js'); ?>"></script>

<script src="<?php echo base_url('assets/global/plugins/select2/select2.min.js'); ?>" type="text/javascript" ></script>
<script>
jQuery(document).ready(function() {

	$("#barang_id_select, #warna_id_select").select2();
	
	<?if(is_posisi_id() != 1){?>
		$("#general_table").DataTable({
			"ordering":false,
			// "bFilter":false
		});
	<?}?>

});
</script>
