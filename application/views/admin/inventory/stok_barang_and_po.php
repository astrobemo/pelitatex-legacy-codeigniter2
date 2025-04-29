<link rel="stylesheet" type="text/css" href="<?=base_url('assets/global/plugins/bootstrap-select/bootstrap-select.min.css'); ?>"/>
<style type="text/css">
.barang-list-container, .barang-list-pool{
	height: 250px;
	overflow: auto;
	border:2px dashed #ddd;
}

.item-list li, .item-list-selected li{
	cursor: pointer;
	margin-bottom: 0px;
	padding: 3px 0 3px 25px; 
	font-size: 1.2em;
	list-style-type: none;
	border-bottom: 0.5px solid #eee;
}
.item-list, .item-list-selected{
	padding: 0px;
}

.item-list li:hover{
	background: #ddd;
}

.item-list-selected li:hover{
	background: lime;
}

.list-hidden{
	display: none;
}

.garis-bawah{
	border-bottom: 2px solid #171717 !important;
}


	@media print {
		a[href]:after {
		    content: none !important;
		}

		#general_table tbody tr td{
			border: 1px solid #ddd !important;
		}

		#general_table tbody tr:nth-child(even){
			background-color: #eee !important;
		}
	}

</style>
<?$nama_barang='';
foreach ($barang_list_selected as $row) {
	$barang_list[$row->barang_id][$row->warna_id] = $row;
	$qty_stok[$row->barang_id][$row->warna_id] = $row->qty_stok;
	$roll_stok[$row->barang_id][$row->warna_id] = $row->jumlah_roll_stok;
}

// foreach ($stok_barang as $row) {
// 	$qty_stok[$row->barang_id][$row->warna_id] = 0;
// 	$roll_stok[$row->barang_id][$row->warna_id] = 0;
// 	foreach ($this->gudang_list_aktif as $row2) {
// 		$qty_stok[$row->barang_id][$row->warna_id] += $row->{"gudang_".$row2->id."_qty"};
// 		$roll_stok[$row->barang_id][$row->warna_id] += $row->{"gudang_".$row2->id."_roll"};
// 	}
// }

?>


<div class="page-content">
<div class='container'>

<div class="row margin-top-10">
	<div class="col-md-12">
		<div class="portlet light">
			<div class="portlet-title">
				<div class="caption caption-md">
					<i class="icon-bar-chart theme-font hide"></i>
					<span class="caption-subject theme-font bold uppercase hidden-print"><?=$breadcrumb_small;?></span>
				</div>
				<!-- <div class="actions">
					<a href="#portlet-config" data-toggle='modal' class="btn btn-default btn-sm btn-form-add">
					<i class="fa fa-plus"></i> Tambah </a>
				</div> -->
			</div>
			<div class="portlet-body">
				<?/*$idx = 0;
				foreach ($pre_po_list_barang as $row) {?>
					<button style="font-weight:bold;<?=($idx==0 ? 'border:2px solid red' : 'border:2px solid' );?>" data-id="<?=$row->barang_id;?>" class="btn btn-md default btn-barang"><?=$row->nama_barang;?></button>
				<?$idx++;}*/
				?>
				<div class='hidden-print'>
					<form id="form-barang" >
						<table>
							<tr>
								<th>
									Nama Barang
								</th>
								<th> : </th>
								<th>
									<select name='barang_id' style="font-size:1.2em; width:350px;" id="barang_id_select" >
										<option value="">Pilih</option>
										<?if (is_posisi_id() == 6) {
											foreach ($this->barang_list_aktif_beli as $row) {?>
												<option value="<?=$row->id?>" <?=($barang_id == $row->id ? 'selected' : '' )?> ><?=$row->nama;?></option>
											<?}
										}else{
											foreach ($this->barang_list_aktif as $row) {?>
												<option value="<?=$row->id?>" <?=($barang_id == $row->id ? 'selected' : '' )?> ><?//=(is_posisi_id() == 1 ? $row->nama.' / ' : '');?><?=$row->nama_jual;?></option>
											<?}
										}?>
									</select>
								</th>
							</tr>
							<tr>
								<th>Tanggal</th>
								<th> : </th>
								<th>
									<input style="font-size:1.2em; width:350px;" readonly name='tanggal' type="text" class='form-control date-picker' value="<?=$tanggal?>">
								</th>
							</tr>
							<?if (is_posisi_id() <= 3) {?>
								<tr>
									<th>Supplier</th>
									<th> : </th>
									<th>
										<select name="supplier_id" id="supplier-id" style="font-size:1.2em; width:350px;">
											<option value="">Semua</option>
											<?foreach ($this->supplier_list_aktif as $key => $value) {?>
												<option value="<?=$value->id?>" <?=($supplier_id == $value->id ? 'selected' : '' )?> ><?=$value->nama;?></option>
											<?}?>
										</select>
									</th>
								</tr>
							<?}?>
							
							<tr>
								<th></th>
								<th></th>
								<th style="padding:10px 0">
									<button type="button" class='btn btn-block btn-sm green' onclick="submitForm()" id='btn-submit'>GO</button>
									<button disabled class='btn btn-block btn-sm green' style="display:none" id='btn-loading' hidden>loading....</button>
								</th>
							</tr>
						</table>
					</form>
				</div>
				<hr class='hidden-print'/>
				<h2 style='font-weight:bold; display:inline' id="nama-jual"> - <span class='nama_barang_tampil' ></span> -</h2> 
				<?if ($supplier_id != "") {
					$nama_beli = "";
					foreach ($batch_for_pre_po as $row) {
						$nama_beli = $row->nama_beli;
					}
					?>
					<h2 style='font-weight:bold; display:none' id="nama-beli"> - <span class=''><?=$nama_beli;?></span> -</h2> 
				<?}?>
				<?if (count($batch_for_pre_po) > 0) {?>
					<?=($barang_id != '' ? " <button class='btn btn-xs yellow-gold btn-show-info hidden-print' style='position:relative; top:-5px'>Show INFO</button> " : '' );?>
				<?}?>
				<!-- " <button class='btn btn-xs red btn-show-po' style='position:relative; top:-5px'>Show QTY PO</button> "." <button class='btn btn-xs blue btn-show-harga' style='position:relative; top:-5px'>Show Harga</button> "." <button class='btn btn-xs green btn-show-ockh' style='position:relative; top:-5px'>Show OCKH</button>" -->
				<div style="width:100%; overflow:auto">
					<table class="table table-striped table-bordered table-hover" id="general_table">
						
						<thead>
							<tr>
								<th scope="col" rowspan='2' >
									Warna
								</th>
								<th scope="col" rowspan='2' class="hidden-print" >
									STOK
								</th>
								<th scope="col" rowspan='2' class="hidden-print" >
									ROLL
								</th >
								<?if (is_posisi_id() != 5) {?>
									<th scope="col" <?=(count($batch_for_pre_po) == 0 ? 'hidden' : '')?> colspan='<?=count($batch_for_pre_po);?>' class='text-center'>
										Sisa PO
									</th >
								<?}else{?>
									<?}?>
									<th scope="col" rowspan='2' >
										TOTAL PO
									</th >
								<th scope="col" rowspan='2' colspan='2' class='text-center hidden-print'>
									STOK + PO
								</th>
								<th  class="hidden-print" scope="col" rowspan='2' style="min-width:150px !important">
									Actions
								</th>
							</tr>
							<tr>
								<? $po_batch_id = array(); $qty_batch_id = array();
								foreach ($batch_for_pre_po as $row) {
									$po_batch_id[$row->po_pembelian_batch_id] = true; 
									$qty_batch_id[$row->po_pembelian_batch_id] = 0;?>
									<?if (is_posisi_id () != 5) {?>
										<th>
											<?if (is_posisi_id() == 6 || is_posisi_id() == 1 ) {?>
												<a target="_blank" href="<?=base_url().is_setting_link('report/po_pembelian_report_detail')?>?id=<?=$row->po_pembelian_id?>&batch_id=<?=$row->po_pembelian_batch_id;?>">
													<?=$row->batch;?>
												</a>
											<?}else if(is_posisi_id()==5){?>
												<?=$row->batch;?>
											<?}else{?>
												<a target="_blank" href="<?=base_url().is_setting_link('transaction/po_pembelian_detail_warna_batch')?>?id=<?=$row->po_pembelian_id?>&batch_id=<?=$row->po_pembelian_batch_id;?>">
													<?=$row->batch;?>
												</a>
											<?}?>
											<small><?=is_reverse_date($row->tanggal);?></small>
										</th>
									<?}?>
								<?}?>
							</tr>
						</thead>
						<?foreach ($this->barang_list_aktif as $baris) {
							$idx = 0;
							$total_stok = 0;
							$total_po = 0;
							$total_roll = 0;
							$subTotalPO = 0;
							?>
							<tbody class='data-warna' id="barang-<?=$baris->id;?>"  >
								<?if (isset($barang_list[$baris->id])) {?>
									<?foreach ($barang_list[$baris->id] as $key => $value) {
										$batch_id = explode(',', $value->batch_id);
										$batch_set = array_flip($batch_id);
										$qty_sisa = explode(',', $value->qty_sisa_data);
										$qty_sisa_data = array_combine($batch_id, $qty_sisa);
										$locked_by = explode(',', $value->locked_by);
										$locked_data = array_combine($batch_id, $locked_by);

										$qty_po_data = explode(',', $value->qty_po_data);
										if (is_posisi_id()==1 && count($qty_po_data) != count($batch_id)) {
											echo "<hr/>";
											print_r($value->nama_warna);
											echo "<hr/>";
											print_r($batch_id);
											echo "<hr/>";
											print_r($qty_po_data);
											echo "<hr/>";
										}

										$qty_po_data = array_combine($batch_id, $qty_po_data);


										$harga_po = explode(',', $value->harga_po);
										$harga_po = array_combine($batch_id, $harga_po);

										$OCKH = explode('??', $value->OCKH);
										if (is_posisi_id()==1) {
											// echo $key.'<br/>';
											// print_r($tanggal_list);
											// print_r($batch_id);
											// echo '<br/>';
											// print_r($OCKH);
											// echo '<hr/>';
										}
										
										$OCKH = array_combine($batch_id, $OCKH);
										if (is_posisi_id()==1) {
											// echo '<hr/>';
										}

										$info = "";
										$qty_now = 0;
										$sub_po = 0;

										$qty_now = $qty_stok[$value->barang_id][$value->warna_id];
										$roll_now = $roll_stok[$value->barang_id][$value->warna_id];
										$total_stok += $qty_now;
										$total_roll += $roll_now;
										// $total_po += $value->qty_sisa;
										
										?>
										<tr class="<?=(($idx+1) % 8 == 0 ? 'garis-bawah' : '' );?>">
											<td><?=$value->nama_warna?></td>

											<?/*<td><b><?=number_format($value->qty_stok,'0',',','.'); $qty_now = $value->qty_stok;?></b></td>*/?>
											<td class="hidden-print"><b><?=number_format($qty_now,'0',',','.');?></b></td>
											
											<td class="hidden-print"><?=$roll_now; //print_r($batch_set); print_r($qty_sisa_data);
											// print_r($locked_data);?>
											</td>
											<?foreach ($po_batch_id as $isi => $konten) {
												if (is_posisi_id() != 5) {?>
													<td class='text-center' style='position:relative;'>
														<?if (isset($batch_set[$isi]) ) {
															$qty_sisa_show = ($qty_sisa_data[$isi] >= 0 ? $qty_sisa_data[$isi] : 0);?>
															<?/*if (is_posisi_id()==1) {
																print_r($locked_data);
															}*/?>
															<span style='background:#fff'><?=($locked_data[$isi] != 0 ? "<i class='fa fa-lock'></i>" : number_format((float)$qty_sisa_data[$isi],'0',',','.'));?></span>
															<?//=($locked_data[$isi] == 1 ? "<i class='fa fa-lock'></i>" : '' );?>
															<? //$qty_now+=$qty_sisa_show; $qty_batch_id[$isi] += $qty_sisa_data[$isi] ?>
															<span class='badge-po text-left' style='width:100px; background:#ffdedb; padding:2px 5px; font-weight:bold; position:absolute; top:-5px; margin-left:2px; visibility:hidden'>PO: <?=number_format($qty_po_data[$isi],'0',',','.');?> </span>
															<span class='badge-harga text-left' style='width:100px; background:#9cfff0; padding:2px 5px; font-weight:bold; position:absolute; top:15px; margin-left:2px; visibility:hidden' >Harga: <?=number_format($harga_po[$isi],'0',',','.');?> </span> 
															<!-- <span class='badge-ockh text-left' style='width:100px; background:#d6ffbf; padding:2px 5px; font-weight:bold; position:absolute; top:35px; margin-left:2px; visibility:hidden'>OCKH: <?=$OCKH[$isi];?> </span>  -->
															
														<?}else{echo'-';}?>
													</td>
												<?}
												if (isset($batch_set[$isi]) ) {
													$qty_sisa_show = ($qty_sisa_data[$isi] >= 0 ? $qty_sisa_data[$isi] : 0);;
													$qty_now+=$qty_sisa_show; 
													$sub_po += $qty_sisa_data[$isi];
													$subTotalPO += $qty_sisa_data[$isi];
													$qty_batch_id[$isi] += $qty_sisa_data[$isi];
												}
												?>
											<?}?>
											<?if (is_posisi_id() == 5) {?>
												<?}?>
												<td class='text-center' style='position:relative;'>
													<?=number_format($sub_po,'0',',','.');?>
												</td>
											<!-- <a data-toggle="popover" class='btn btn-xs default' data-trigger='click' title="PO Gantung" data-html="true" data-content="<?=$info;?>"><i class='fa fa-info'></i></a> -->
											<?//$qty_now=$value->qty_stok + $value->qty_sisa;?>
											<?$total_po+=$qty_now;?>
											<td class='text-right hidden-print' style='border-right:0px; font-size:1.1em'><?=number_format($qty_now,'0',',','.');?>
											</td>
											<td class='text-left hidden-print' style='border-left:0px'><?=$value->nama_satuan?></td>
											<td class='hidden-print'>
												<?if (is_posisi_id() == 1) {?>
													<?//=$value->qty_sisa;?>//
													<?=$value->nama_barang?> // 
													<?=$value->nama_warna?>
												<?}?>
											</td>
										</tr>
									<?$idx++;} ?>
									<tr style='font-size:1.2em;text-align:right'>
										<td>TOTAL</td>
										<td class="hidden-print"><?=number_format((float)$total_stok,'0',',','.');?></td>
										<td class="hidden-print"><?=$total_roll;?></td>
										<?foreach ($batch_for_pre_po as $row) {
											if (is_posisi_id() != 5) {?>
												<td class='text-center'>
													<?=number_format($qty_batch_id[$row->po_pembelian_batch_id],'0',',','.');?>
												</td>
											<?}?>
										<?}?>
										<?if (is_posisi_id() == 5) {?>
											<?}?>
											<td><?=number_format((float)($subTotalPO),'0',',','.');?></td>
										<td  class="hidden-print" style='border-right:0px;' ><?=number_format((float)($total_po),'0',',','.');?></td>
										<td  class="hidden-print" style='border-left:0px; text-align:left'> <?=$value->nama_satuan;?> </td>
										<td class='hidden-print'></td>
									</tr>
								<?}?>
							</tbody>
						<?}?>

					</table>
						<hr/>
					<button class='btn btn-lg blue hidden-print' onclick="toggleNamaJual()"><i class='fa fa-print'></i> PRINT</button>
					<?if ($supplier_id != "") {?>
						<button class='btn btn-lg green hidden-print' onclick="toggleNamaBeli()"><i class='fa fa-print'></i> PRINT w NAMA BELI</button>
					<?}?>
				</div>
			</div>
		</div>
	</div>
</div>
</div>			
</div>


<script src="<?php echo base_url('assets/global/plugins/datatables/media/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets_noondev/js/table-advanced.js'); ?>"></script>

<script src="<?php echo base_url('assets/global/plugins/select2/select2.min.js'); ?>" type="text/javascript" ></script>

<script type="text/javascript" src="<?php echo base_url('assets/global/plugins/bootstrap-select/bootstrap-select.min.js'); ?>"></script>


<script>
jQuery(document).ready(function() {
	$('[data-toggle="popover"]').popover();

	$('#barang_id_select').select2();
	$(".nama_barang_tampil").html($("#barang_id_select :selected").text());

	$("#barang_id_select").change(function(){
		if ($(this).val() != '' ) {
			submitForm();
		};

		$(".nama_barang_tampil").html("<i>loading...</i>")
    	// var id = $(this).val();
    	// $(".data-warna").hide();
    	// $("#barang-"+id).show();
    	// $(".nama_barang_tampil").html($("#barang_id_select :selected").text());
	});

	$(".btn-show-harga").click(function(){
		$('.badge-harga').css('visibility', $('.badge-harga').css('visibility') == 'hidden' ? 'visible' : 'hidden');
	});

	$(".btn-show-po").click(function(){
		$('.badge-po').css('visibility', $('.badge-po').css('visibility') == 'hidden' ? 'visible' : 'hidden');
	});

	$(".btn-show-ockh").click(function(){
		$('.badge-ockh').css('visibility', $('.badge-ockh').css('visibility') == 'hidden' ? 'visible' : 'hidden');
	});

	$(".btn-show-info").click(function(){
		$('.badge-ockh, .badge-po, .badge-harga').css('visibility', $('.badge-ockh, .badge-po, .badge-harga').css('visibility') == 'hidden' ? 'visible' : 'hidden');
	});
});

function submitForm(){
	if($("#barang_id_select").val() !=''){
		$("#btn-submit").hide();
		$("#btn-loading").show();
		$("#form-barang").submit();
	}else{
		notific8("ruby", "Mohon pilih barang")
	}
}

function toggleNamaJual(){
	<?if($supplier_id != ""){?>
		$("#nama-jual").show();
		$("#nama-beli").hide();
	<?}?>
	window.print();
}

function toggleNamaBeli(){
	$("#nama-jual").hide();
	$("#nama-beli").show();
	window.print();
}

</script>
