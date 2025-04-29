<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css'); ?>"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css'); ?>"/>
<?php echo link_tag('assets/global/plugins/bootstrap-datepicker/css/datepicker3.css'); ?>
<link href="<?=base_url('assets/global/plugins/bootstrap-modal/css/bootstrap-modal.css');?>" rel="stylesheet" type="text/css"/>

<style type="text/css">
.tgl-beli{
	border-radius: 20%;
	background: #cddefa;;
	padding: 2px 5px;
	font-size:0.9em;
}

.harga-beli{
	border-radius: 20%;
	background: #ffdee0;
	padding: 2px 5px;
	font-size: 0.8em;
}

.data-barang-datang{
	padding-right: 10px;
}

.datang-index{
	padding: 2px;
	/*border: 1px solid #ddd;*/
	border-radius: 30%;
	opacity: 0.5;
	/*background: #cddefa;*/
	color: #800;
	font-size:0.8em; 
	position:absolute;
	left:-2px; 
	top:0;
}

.span-table{
	display: inline-block;
}

.barang-span{width: 220px}
.harga-span{width: 55px; text-align: right;}
.order-span{width: 55px; text-align: right;}
.delivered-span{width: 70px;  padding-right:10px; text-align:right}
.outstanding-span{width: 60px; padding-right:10px; text-align:right}

.selected-to-print{
	/*background: #e0e4ff !important;*/
	background: #e0e4ff;
}

	@media print {

		* {
			-webkit-print-color-adjust: exact;
			print-color-adjust: exact;
		}

		/*th.span-table{font-size: }*/

		.span-table{
			display: table-cell;
			vertical-align: middle;
		}

		#general_table_wrapper{
			display: none !important;
		}

		a[href]:after {
		    content: none !important;
		}

		#print-table{
			display: block;
		}
	}


</style>

<div class="page-content">
	<div class='container'>

		<div class="row margin-top-10">
			<div class="col-md-12">
				<div class="portlet light">
					<div class="portlet-title hidden-print">
						<div class="caption caption-md">
							<i class="icon-bar-chart theme-font hide"></i>
							<span class="caption-subject theme-font bold uppercase"><?=$breadcrumb_small;?></span>
						</div>
						<div class="actions hidden-print">
							<!-- <a href="#portlet-config" data-toggle='modal' class="btn btn-default btn-sm">
							<i class="fa fa-plus"></i> Baru </a> -->
							<?if ($view_type == 2) {?>
								<a href=".?view_type=1" class="btn btn-default btn-sm" >Munculkan Semua</a>
							<?}else{?>
								<a href=".?view_type=2" class="btn btn-default btn-sm" >Munculkan hanya yang belum LOCK</a>
							<?}?>
						</div>
					</div>
					<div class="portlet-body">
						<?$nama_barang_selected = ''; $nama_warna_selected = ''; $supplier_selected?>
						<form action="" class="form-horizontal" id="form_filter_data" method="get">
							<table width="500px">
								<tr>
									<td>Supplier</td>
									<td class='padding-rl-5'> : </td>
									<td colspan='3' style="width:400px;">
										<select class='form-control' name='supplier_id' id="supplier-id">
											<option value='' <?=($supplier_id == '' ? 'selected' : '')?> >Semua</option>
											<?foreach ($this->supplier_list_aktif as $row) {
													$supplier_selected = ($supplier_id == $row->id ? $row->nama : $supplier_selected);?>
												<option <?=($supplier_id == $row->id ? 'selected' : '')?> value="<?=$row->id?>"><?=$row->nama?></option>
											<?}?>
										</select>
									</td>
								</tr>
								<tr>
									<td>Barang</td>
									<td class='padding-rl-5'> : </td>
									<td colspan='3'>
										<select class='form-control' name='barang_beli_id' id="barang-id">
											<option value='' <?=($barang_beli_id == '' ? 'selected' : '')?> >Semua</option>
											<?foreach ($this->barang_list_aktif_beli as $row) {
													$nama_barang_selected = ($barang_beli_id == $row->id ? $row->nama : $nama_barang_selected);?>
												<option <?=($barang_beli_id == $row->id ? 'selected' : '')?> value="<?=$row->id?>"><?=$row->nama?></option>
											<?}?>
										</select>
									</td>
								</tr>
								<tr>
									<td>Warna</td>
									<td class='padding-rl-5'> : </td>
									<td colspan='3'>
										<select class='form-control' name='warna_id' id="warna-id">
											<option value='' <?=($warna_id == '' ? 'selected' : '')?> >Semua</option>
											<?foreach ($this->warna_list_aktif as $row) {
													$nama_warna_selected = ($warna_id == $row->id ? $row->warna_jual : $nama_warna_selected)?>
													<option  <?=($warna_id == $row->id ? 'selected' : '')?> value="<?=$row->id?>"><?=$row->warna_jual?></option>
											<?}?>
										</select>
									</td>
								</tr>
								<tr>
									<td></td>
									<td></td>
									<td colspan='3'>
										<button type='submit' class='btn btn-block green'>GO</button>
									</td>
								</tr>
							</table>
						</form>
						
						<hr class='hidden-print'/>
						
						<table class="table table-hover table-striped table-bordered" id="general_table">
							<thead>
								<tr>
									<th scope="col" style='width:100px;'>
										Supplier
									</th>								
									<th scope="col">
										PO-Batch
									</th>
									<th scope="col">
										Tanggal Batch
									</th>
									<th scope="col">
										<span class='span-table barang-span' style=''>
											Barang
										</span>
										<span class='span-table harga-span' style=''>
											Harga
										</span>
										<span class='span-table order-span'>
											Order
										</span>
										<span class='span-table delivered-span'>
											Delivered
										</span>
										<span class='span-table outstanding-span'>
											Outstanding
										</span>
									</th>
									<!-- <th scope="col">
										Qty Datang
									</th>
									<th scope="col">
										Balance
									</th> -->
									<th scope="col">
										Actions
									</th>
								</tr>
							</thead>
							<tbody>
								<?$idx=0; $ind=0;
								foreach ($this->supplier_list_aktif as $row2) {
									$total_order[$row2->id] = 0;
									$total_delivered[$row2->id] = 0;
									$total_outstanding[$row2->id] = 0;
								}
								foreach ($po_pembelian_report as $row) { 
									$satuan = explode(',', $row->nama_satuan);
									$qty_beli = explode(',', $row->data_qty_beli);
									$qty_po = explode(',', $row->data_po_qty);
									$qty_2019 = explode(',', $row->qty_2019);
									$nama_barang = explode(',', $row->nama_barang);
									$nama_warna = explode(',', $row->nama_warna);
									$harga_barang = explode(',', $row->harga_barang);
									$locked_po = explode(',', $row->locked_po);
									$po_warna_id = explode(',', $row->po_warna_id);
									$warna_id_list = explode(',', $row->warna_id);
									$barang_id_list = explode(',', $row->barang_id);
									$last_datang = explode(',', $row->last_datang);
									foreach ($satuan as $key => $value) {
										if (!isset($total_beli[$value])) {
											$total_beli[$value] = 0;
										} 
										if (!isset($total_po[$value])) {
											$total_po[$value] = 0;
										} 
										$total_beli[$value] += $qty_beli[$key]; 
										$total_po[$value] += $qty_po[$key]; 
									}
									$idx++;
									$subtotal_order = 0;
									$subtotal_delivered = 0;
									$subtotal_outstanding = 0;

									// $satuan = array_unique($satuan);
									?>
									<tr >
										<td class='nama-supplier'><?=$row->nama_supplier;?></td>
										<td><b style='font-size:1.1em;' class='po-number'><?=$row->po_number;?></b>
											<span class='idx' hidden><?=$ind;?></span></td>
										<td class='tanggal-batch'>
											<?=is_reverse_date($row->tanggal);?>
										</td>
										<td>
											<?//=print_r($row->nama_satuan);?>
											<!-- <a class='po-detail-expand'>Detail ></a> -->
											<ul style='padding:0px; margin:0px' class='po-detail'>
												<?foreach ($satuan as $key => $value) {
													$color='';
													if ($locked_po[$key] != '-') {
														$color = '#FFC6B9';
													}
													$total_order[$row->supplier_id] += $qty_po[$key];
													$total_delivered[$row->supplier_id] += $qty_beli[$key]+$qty_2019[$key];
													$ots = $qty_po[$key] - $qty_beli[$key] - $qty_2019[$key];
													$total_outstanding[$row->supplier_id] += ($ots < 0 ? 0 : $ots);

													$subtotal_order += $qty_po[$key];
													$subtotal_delivered += $qty_beli[$key]+$qty_2019[$key];
													$subtotal_outstanding += ($ots < 0 ? 0 : $ots);

													if ($barang_id != '' && $warna_id == '') {
														if (!isset($total_warna_order[$warna_id_list[$key]])) {
															$total_warna_order[$warna_id_list[$key]] = 0;
															$total_warna_delivered[$warna_id_list[$key]] = 0;
															$total_warna_outstanding[$warna_id_list[$key]] = 0;
														}
														$total_warna_order[$warna_id_list[$key]] += $qty_po[$key];
														$total_warna_delivered[$warna_id_list[$key]] += $qty_beli[$key] + $qty_2019[$key];
														$ots = $qty_po[$key] - $qty_beli[$key] - $qty_2019[$key];
														$total_warna_outstanding[$warna_id_list[$key]] += ($ots < 0 ? 0 : $ots);
													}
													if ($barang_id == '' && $warna_id != '' ) {
														if (!isset($total_barang_order[$barang_id_list[$key]])) {
															$total_barang_order[$barang_id_list[$key]] = 0;
															$total_barang_delivered[$barang_id_list[$key]] = 0;
															$total_barang_outstanding[$barang_id_list[$key]] = 0;
														}
														$total_barang_order[$barang_id_list[$key]] += $qty_po[$key];
														$total_barang_delivered[$barang_id_list[$key]] += $qty_beli[$key] + $qty_2019[$key];
														$ots = $qty_po[$key] - $qty_beli[$key] - $qty_2019[$key];
														$total_barang_outstanding[$barang_id_list[$key]] += ($ots < 0 ? 0 : $ots);
													}
													?>
													<li style="list-style-type:none; background:<?=$color;?>; <?=($qty_beli[$key] > 0 ? 'cursor:pointer' : 'cursor:no-drop' );?>" data-id="<?=$po_warna_id[$key];?>" data-last="<?=($last_datang[$key] != 'kosong' ? date("Y-m-d", strtotime($last_datang[$key])) : date('Y-m-d'));?>">
														<div class="<?=($qty_beli[$key] > 0 ? 'show-detail-datang' : '' );?> " style='display:inline-block' data-batch='<?=$row->id?>'  data-barang="<?=$barang_id_list[$key]?>" data-warna="<?=$warna_id_list[$key];?>"  >
															<span class='span-table barang-span'><?=$nama_barang[$key].' '.$nama_warna[$key];?></span>
															
															<span style='color:transparent'>;</span>
															<span class='span-table harga-span'><?=($harga_barang[$key] != '' ? number_format($harga_barang[$key] ,'0',',','.') : '') ;;?></span>
															
															<span style='color:transparent'>;</span>
															<span  class='span-table order-span qty-po'><?=number_format(($qty_po[$key] == '' ? 0 : $qty_po[$key]) ,'0',',','.');?></span>
															
															<span style='color:transparent'>;</span>
															<u class='span-table delivered-span'>
																<b class='qty-datang'><?=number_format($qty_beli[$key]+$qty_2019[$key],'0',',','.');?></b>
															</u>
															
															<span style='color:transparent'>;</span>
															<span class='span-table outstanding-span'>
																<?=number_format($qty_po[$key] - $qty_beli[$key] - $qty_2019[$key],'0',',','.');?>
															</span>
															
															<span style='color:transparent'>;</span>
															<span class='nama-satuan hidden-print'><?=$value;?></span> 
														</div>
														<input type='checkbox'  class='hidden-print check-to-print'>
														<?//=(is_posisi_id() == 1 ?  ($last_datang[$key] != 'kosong' ? date("Y-m-d", strtotime($last_datang[$key])) : date('Y-m-d')) : '')?>
														<b class='lock-show' style="<?=($locked_po[$key] == '-' ? 'display:none' : '');?>" ><i class='fa fa-lock'></i></b>
														<?//=(is_posisi_id() == 1 ? $po_warna_id[$key] : '' );?>
														<?//=(is_posisi_id() == 1 ? date("Y-m-d", strtotime($last_datang[$key])) : '' );?>
														<div class='detail-datang' hidden style="width:100%; background:#fff; padding:15px; border:1px solid blue; "><span style='color#ddd'>loading...</span></div>
													</li>
													<?if (($key+1) % 3 == 0 && $key != count($satuan) ) {?>
														<hr class='hidden-print' style='margin:0px; padding:5px 0px' />
													<?}?>
													<?//=number_format($row->po_qty - $row->qty_beli,'0',',','.');?>
												<?}?>
												<li style="list-style-type:none; background: #eee; font-weight:bold">
													<div  >
														<span class='span-table barang-span'></span>
														
														<span style='color:transparent'>;</span>
														<span class='span-table harga-span'>TOTAL</span>
														
														<span style='color:transparent'>;</span>
														<span  class='span-table order-span qty-po'><?=number_format($subtotal_order ,'0',',','.');?></span> 
														
														<span style='color:transparent'>;</span>
														<span class='span-table delivered-span'>
															<?=number_format($subtotal_delivered,'0',',','.');?>
														</span> 
														
														<span style='color:transparent'>;</span>
														<span class='span-table outstanding-span'>
															<?=number_format($subtotal_outstanding,'0',',','.');?>
														</span> 
														
														<span style='color:transparent'>;</span>
														<span class='nama-satuan hidden-print'></span> 
													</div>
												</li>
												
											</ul>
											<?/*<div style="display:inline-block"></div>
												<div style="display:inline-block">
													<?=number_format($row->po_qty,'0',',','.');?>
												</div>
												<div style="display:inline-block">
													<?=number_format($row->qty_beli,'0',',','.');?>
												</div>
												<?=number_format($row->po_qty - $row->qty_beli,'0',',','.');?>*/?>
										</td>
										<!-- <td></td>
										<td></td> -->
										<td>
											<?//=(is_posisi_id()==1 ? $row->po_pembelian_id : '');?>
											<?//=(is_posisi_id()==1 ? $row->id : '');?>
											<?if ($row->keterangan_batch != '') {?>
												<div class='note note-danger'><?=$row->keterangan_batch?></div>
											<?}?>
											<a class='btn btn-xs yellow-gold' href="<?=base_url().is_setting_link('report/po_pembelian_report_detail');?>?id=<?=$row->po_pembelian_id;?>&batch_id=<?=$row->id;?>" target="_blank"><i class='fa fa-search'></i></a>
										</td>
									</tr>
								<?$ind++;}?>
							</tbody>
						</table>

						<table class="table table-hover table-striped table-bordered" hidden id="print-table">
							<thead>
								<tr>
									<th scope="col">
										Supplier
									</th>								
									<th scope="col">
										PO-Batch
									</th>
									<th scope="col">
										Tanggal Batch
									</th>
									<th scope="col">
										Barang
									</th>
									<th scope="col">
										Order
									</th>
									<th scope="col">
										Delivered
									</th>
									<th scope="col">
										Outstanding
									</th>
								</tr>
							</thead>
							<tbody></tbody>
						</table>
						<hr class='hidden-print'/>

						<table class='table hidden-print' id='total-all'>
							<thead>
								<tr>
									<th>Nama</th>
									<th class='text-center'>Order</th>
									<th class='text-center'>Delivered</th>
									<th class='text-center'>Outstanding</th>
								</tr>
							</thead>
							<tbody>
								<?foreach ($this->supplier_list_aktif as $row2) {
									if ($total_order[$row2->id] != 0) {?>
										<tr style='font-size:1.2em; font-weight:bold'>
											<td><?=$row2->nama?></td>
											<td class='text-center'><?=str_replace(',00','', number_format($total_order[$row2->id],2,',','.'))?></td>
											<td class='text-center'><?=str_replace(',00','', number_format($total_delivered[$row2->id],2,',','.'))?></td>
											<td class='text-center'><?=str_replace(',00','', number_format($total_outstanding[$row2->id],2,',','.'))?></td>
										</tr>
									<?}
								}?>
							</tbody>
						</table>
						<hr class='hidden-print'/>
						<?if ($barang_id != '' && $warna_id == '') {?>
							<table class='table hidden-print' id='totalAll'>
								<thead>
									<tr>
										<th>Warna</th>
										<th class='text-center'>Order</th>
										<th class='text-center'>Delivered</th>
										<th class='text-center'>Outstanding</th>
									</tr>
								</thead>
								<tbody>
									<?foreach ($this->warna_list_aktif as $row) {
										if (isset($total_warna_order[$row->id])) {?>
											<tr style='font-size:1.2em; font-weight:bold'>
												<td><?=$nama_barang_selected;?> <?=$row->warna_jual;?></td>
												<td class='text-center'><?=str_replace(',00','', number_format($total_warna_order[$row->id],2,',','.'))?></td>
												<td class='text-center'><?=str_replace(',00','', number_format($total_warna_delivered[$row->id],2,',','.'))?></td>
												<td class='text-center'><?=str_replace(',00','', number_format($total_warna_outstanding[$row->id],2,',','.'))?></td>
											</tr>
										<?}
									}?>
								</tbody>
							</table>
						<?}?>

						<?if ($barang_id == '' && $warna_id != '') {?>
							<table class='table hidden-print' id='totalAll'>
								<thead>
									<tr>
										<th>Warna</th>
										<th class='text-center status_column' style='display:none'>Order</th>
										<th class='text-center'>Order</th>
										<th class='text-center status_column' style='display:none'>Delivered</th>
										<th class='text-center'>Delivered</th>
										<th class='text-center status_column' style='display:none'>Outstanding</th>
										<th class='text-center'>Outstanding</th>
									</tr>
								</thead>
								<tbody>
									<?foreach ($this->barang_list_aktif as $row) {
										if (isset($total_barang_order[$row->id])) {?>
											<tr style='font-size:1.2em; font-weight:bold'>
												<td><?=$row->nama?> <?=$nama_warna_selected?> </td>
												<td class='text-center'><?=str_replace(',00','', number_format($total_barang_order[$row->id],2,',','.'))?></td>
												<td class='text-center status_column' style='display:none'><?=$total_barang_order[$row->id];?></td>
												<td class='text-center'><?=str_replace(',00','', number_format($total_barang_delivered[$row->id],2,',','.'))?></td>
												<td class='text-center status_column' style='display:none'><?=$total_barang_delivered[$row->id];?></td>
												<td class='text-center'><?=str_replace(',00','', number_format($total_barang_outstanding[$row->id],2,',','.'))?></td>
												<td class='text-center status_column' style='display:none'><?=$total_barang_outstanding[$row->id];?></td>
											</tr>
										<?}
									}?>
								</tbody>
							</table>
						<?}?>

						<hr class='hidden-print'/>
							<div class='text-right'>
								<button type='button' style='display:none' class='btn btn-lg default hidden-print btn-lock' onclick="lock_po()">LOCK</button>
								<button type='button' style='display:none' class='btn btn-lg blue btn-print hidden-print' onclick="window.print()"><i class='fa fa-print'></i> PRINT</button>
								<button type='button' class='btn btn-lg yellow-gold btn-sortir hidden-print'>SORTIR</button>
								<button type='button' style='display:none' class='btn btn-lg yellow-gold btn-semua hidden-print'>SEMUA</button>
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

	// oTable = $('#general_table').DataTable();
	// oTable.state.clear();
	// oTable.destroy();

	$("#barang-id, #warna-id, #supplier-id").select2();

	$("#general_table").DataTable({
		"ordering":false,
        "lengthMenu": [
            [10, 25, 50, 100, -1],
            [10, 25, 50, 100, "All"] // change per page values here
        ],
        "pageLength": 100, // set the initial value,

	});

	$("#totalAll").DataTable({
		ordering:true,
        languange:{
			decimal:",",
			thousands:"."
		},

	});

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

	$("#general_table").on('click','.po-detail-expand', function () {
		$(this).closest('tr').find('.po-detail').toggle();
	});

	$("#btn-hide-locked").click(function() {
		$("#general_table tr").each(function() {
			if($(this).find("li").length == +$(this).find('.fa-lock').length){
				$(this).hide();
			};
		});
	});

	$("#general_table").on("click",".show-detail-datang", function() {
		var ini = $(this).closest('li');
		var batch_id = $(this).attr("data-batch");
		var barang_id = $(this).attr("data-barang");
		var warna_id = $(this).attr("data-warna");
		var qty_po = reset_number_format($.trim(ini.find('.qty-po').html()));
		var qty_datang = reset_number_format($.trim(ini.find('.qty-datang').html()));
		var persentase = (qty_datang/qty_po * 100).toFixed(1);
		persentase = (persentase>100 ? 100 : persentase)+'%';
		// console.log($.trim(ini.find('.qty-datang').html()));
		// console.log(`${qty_datang}/${qty_po}`);

		// var table = "";
		get_po_2019(batch_id, barang_id, warna_id, ini, persentase);
		
	});

	/*$("#general_table").on("click",".show-detail-datang", function() {
		var ini = $(this);
		var batch_id = $(this).attr("data-batch");
		var barang_id = $(this).attr("data-barang");
		var warna_id = $(this).attr("data-warna");
		var qty_po = reset_number_format($.trim(ini.find('.qty-po').html()));
		var qty_datang = reset_number_format($.trim(ini.find('.qty-datang').html()));
		var qty_2019 = reset_number_format($.trim(ini.find('.qty-2019').html()));
		var persentase = ((qty_datang + qty_2019)/qty_po * 100).toFixed(1);
		persentase = (persentase>100 ? 100 : persentase);
		// console.log($.trim(ini.find('.qty-datang').html()));
		// console.log(`${qty_datang}/${qty_po}`);

	});*/
	
	//=================================to print=============================
	$("#general_table").on("click",".check-to-print", function() {
		let ini = $(this);
		if (ini.is(":checked")) {
			ini.closest("li").addClass('selected-to-print');
		}else{
			ini.closest("li").removeClass('selected-to-print');
		};
	});

	$(".btn-sortir").click(function() {
		$("#print-table tbody").html('');

		let index = '';
		let data = {};
		let customer = '';
		let po_batch = '';
		let tanggal_batch = '';
		let list = '';
		po_warna_id = [];
		$("#general_table tbody tr").each(function() {
			let ini = $(this);
			let index = ini.find('.idx').html();
			let count = 0;
			let nama_barang = [];
			let order = [];
			let delivered = [];
			let outstanding = [];
			ini.find('hr').hide();
			$(ini.find('.check-to-print')).each(function() {
				if($(this).is(':checked')){
					let pointer = $(this).closest('li');
					count++;
					nama_barang.push(pointer.find('.barang-span').html());
					order.push(pointer.find('.order-span').html());
					delivered.push(pointer.find('.qty-datang').html());
					outstanding.push(pointer.find('.outstanding-span').html());
					po_warna_id.push(pointer.attr('data-id'));
					po_last_datang.push(pointer.attr('data-last'))
				}else{
					$(this).closest('li').hide();
				}					
			});
			if (count == 0) {
				ini.hide();
			}else{
				data[index] = {
					'supplier' : ini.find('.nama-supplier').html(),
					'po_number' : ini.find('.po-number').html(),
					'tanggal_batch' : ini.find('.tanggal-batch').html(),
					'count' : count,
					'barang' : nama_barang.join('??'),
					'order' : order.join('??'),
					'delivered' : delivered.join('??'),
					'outstanding' : outstanding.join('??')
				}
				/*data[index]['customer'] = ini.find('.nama-customer').html();
				data[index]['po_batch'] = ini.find('po-number').html();
				data[index]['tanggal_batch'] = ini.find('.tanggal-batch').html();
				data[index]['count'] = count;
				data[index]['barang'] = nama_barang.join('??');
				data[index]['order'] = order.join('??');
				data[index]['delivered'] = delivered.join('??');
				data[index]['outstanding'] = outstanding.join('??');*/
			};
		});
		$(this).hide();
		let new_row = '';
		let row_total = '';
		let total_order = 0;
		let total_delivered = 0;
		let total_outstanding = 0;

		$.each(data, function(i,v) {
			let barang = v.barang.split('??');
			let order = v.order.split('??');
			let delivered = v.delivered.split('??');
			let outstanding = v.outstanding.split('??');
			$.each(barang, function(id, val){
				total_order += parseFloat(reset_number_format(order[id]));
				total_delivered += parseFloat(reset_number_format(delivered[id]));
				total_outstanding += parseFloat(reset_number_format(outstanding[id]));
				if (id == 0) {
					new_row += `<tr>
						<td rowspan="${v.count}">${v.supplier}</td>
						<td rowspan="${v.count}">${v.po_number}</td>
						<td rowspan="${v.count}">${v.tanggal_batch}</td>
						<td>${val}</td>
						<td>${order[id]}</td>
						<td>${delivered[id]}</td>
						<td>${outstanding[id]}</td>
					</tr>`;
				}else{
					new_row += `<tr>
						<td>${val}</td>
						<td>${order[id]}</td>
						<td>${delivered[id]}</td>
						<td>${outstanding[id]}</td>
					</tr>`;
				};
			});
			// body...
		});

		if (new_row != '') {
			new_row += `<tr style='font-size:1.2em; font-weight:bold'>
						<td colspan='4' class='text-center'>TOTAL</td>
						<td>${change_number_format(total_order)}</td>
						<td>${change_number_format(total_delivered)}</td>
						<td>${change_number_format(total_outstanding)}</td>
					</tr>`;
			row_total = `<tr id="row-total" style='font-size:1.2em; font-weight:bold'>
				<td colspan='3'></td>
					<td>
						<span class='span-table barang-span'>TOTAL</span>
						<span  class='span-table order-span '>${change_number_format(total_order)}</span>
						<u class='span-table delivered-span'>
							<b class=''>${change_number_format(total_delivered)}</b>
						</u>
						<span class='span-table outstanding-span'>${change_number_format(total_outstanding)}</span> 
														
					</td>
					<td></td>
				</tr>`;
		};
		$("#print-table tbody").html(new_row);
		$(".btn-print").show();
		$(".btn-semua").show();
		$(".btn-lock").show();
		$(".check-to-print").hide();
		$("#general_table tbody").append(row_total);

		// $.each(data,function(i,v) {
		// })
	});

	$(".btn-semua").click(function() {
		$(this).hide();
		$('#general_table').find('hr').show();
		$("#general_table tr").show();
		$("#general_table li").show();
		$(".btn-print").hide();
		$(".btn-sortir").show();
		$(".check-to-print").show();
		$("#row-total").remove();
		$(".btn-lock").hide();
	});
	
});

function lock_po(){
	console.log(po_warna_id);
	if (po_warna_id.length > 0) {
		let po_warna_list = po_warna_id.join(',');
		let po_warna_last = po_last_datang.join(',');
		console.log(po_warna_list);
		var data_st = {};
		data_st['po_warna_list'] = po_warna_list;
		const url = "report/po_pembelian_warna_lock_by_list";
		data_st['po_list'] = po_warna_list;
		data_st['last_datang'] = po_warna_last;
		// ini.find('.detail-datang').toggle().html("<span style='color:#ddd'>loading...</span>");
		bootbox.confirm("LOCK PO ini ?", function(respond){
			if (respond) {
				ajax_data_sync(url,data_st).done(function(data_respond  ,textStatus, jqXHR ){
					// console.log(data_respond);
					if (textStatus == 'success') {
						for (var i = 0; i < po_warna_id.length; i++) {
							$(document).find(`[data-id=${po_warna_id[i]}]`).css('background','#FFC6B9').find('.lock-show').show();
						};
						notific8('lime', "PO sudah di lock");
					}else{
						alert("Error");
					};
		   		});
			};
		})
	};
}

function get_po_now(batch_id, barang_id, warna_id, ini, content, persentase){
	// var content = ini.find('.detail-datang').html();
	var data_st = {};
	var url = "report/get_po_pembelian_detail_report";
	data_st['batch_id'] = batch_id;
	// ini.find('.detail-datang').toggle().html("<span style='color:#ddd'>loading...</span>");
	ajax_data_sync(url,data_st).done(function(data_respond){
		// console.log(data_respond);;
		var table = "";
		$.each(JSON.parse(data_respond),function(k,v){
			console.log(v.barang_id +"=="+ barang_id +"&&"+ v.warna_id +"=="+ warna_id);
			if (v.barang_id == barang_id && v.warna_id == warna_id) {
				// alert("OK");
				var qty_beli = v.qty_beli.split(',');
				var no_faktur = v.no_faktur.split(',');
				var tgl_beli = v.tanggal_datang.split(',');
				var harga_beli = v.harga_beli.split(',');
				var OCKH = v.OCKH.split(',');
				var baris = Math.ceil(harga_beli.length/8);
				if (content == '') {
					table = ` Pengiriman : <b style='font-size:1.2em'>${tgl_beli.length}x</b>, 
						OCKH : <b style='font-size:1.2em'>${v.OCKH}</b>,
						Persentase : <b style='font-size:1.2em'>${persentase}</b>
						<br/>
						<table>`;
					
				};


				// $.each(harga_beli, function(i,j){
					for (var m = 0; m < baris; m++) {
						table += ( m%2 == 1 ? "<tr style='background:#fffcd9; padding:5px 0px; border-bottom:1px solid #171717'>" : "<tr/>");
						for (var n = 0; n < 8 ; n++) {
							if (harga_beli.length > (n + (8*m))  ) {
								var i = n + (8*m);
								table+=`<td class='data-barang-datang' style="padding:5px 5px 5px 8px; position:relative"><span style='font-size:1.1em'>${change_number_format(parseFloat(qty_beli[i]))}</span>${((i)%8==0 ? "<b class='datang-index'>"+(i+1)+"</b>" : '')}<br/>
								<span class='tgl-beli'>${tgl_beli[i]}</span><br/>
								<span class='harga-beli'>${change_number_format(Math.ceil(harga_beli[i]))}</span>
								</td>`;
							}else{
								table += "<td></td>";
							}
						};
						table += "</tr>";
					};
				// });
			table += "</table>";
			};
			// alert(table);
			// get_po_2019
		});
		// console.log(content+table);
		ini.find('.detail-datang').html(content+table);
	});
}

function get_po_2019(batch_id, barang_id, warna_id, ini, persentase) {
	// alert(batch_id);
	var table = "";
	var data_st = {};
	data_st['batch_id'] = batch_id;
	ini.find('.detail-datang').toggle().html("<span style='color:#ddd'>loading...</span>");

	var url = "https://demo.noondev.com?key=123&batch_id="+batch_id;
	
	$.ajax(url, {
	    type:"POST",
	    dataType:"json",
	    async:true,
	    data:{action:"something"}, 
	    success:function(data, textStatus, jqXHR) {
	    	// console.log(data);
	    	var table = "";
	    	if(data.po_pembelian_report_detail.length > 0){
		    	$.each(data.po_pembelian_report_detail,function(k,v){
		    		// console.log(i);
					if (v.barang_id == barang_id && v.warna_id == warna_id) {
						// alert("OK");
						var qty_beli = v.qty_beli.split(',');
						var no_faktur = v.no_faktur.split(',');
						var tgl_beli = v.tanggal_datang.split(',');
						var harga_beli = v.harga_beli.split(',');
						var OCKH = v.OCKH.split(',');
						var baris = Math.ceil(harga_beli.length/8);
						table = ` Pengiriman : <b style='font-size:1.2em'>${tgl_beli.length}x</b>, 
							OCKH : <b style='font-size:1.2em'>${v.OCKH}</b>,
							Persentase : <b style='font-size:1.2em'>${persentase}</b>
							<br/>
							<table>`;


						// $.each(harga_beli, function(i,j){
							for (var m = 0; m < baris; m++) {
								table += ( m%2 == 1 ? "<tr style='background:#fffcd9; padding:5px 0px; border-bottom:1px solid #171717'>" : "<tr/>");
								for (var n = 0; n < 8 ; n++) {
									if (harga_beli.length > (n + (8*m))  ) {
										var i = n + (8*m);
										table+=`<td class='data-barang-datang' style="padding:5px 5px 5px 8px; position:relative"><span style='font-size:1.1em'>${change_number_format(parseFloat(qty_beli[i]))}</span>${((i)%8==0 ? "<b class='datang-index'>"+(i+1)+"</b>" : '')}<br/>
										<span class='tgl-beli'>${tgl_beli[i]}</span><br/>
										<span class='harga-beli'>${change_number_format(harga_beli[i])}</span>
										</td>`;
									}else{
										table += "<td></td>";
									}
								};
								table += "</tr>";
							};
						// });
						table += "</table>";
					};
				});
	    	}
			// alert(table);
			// return table;
			// ini.find('.detail-datang').html(table);
			get_po_now(batch_id, barang_id, warna_id, ini, table, persentase);
	    	
	   	},
	  	error: function(jqXHR, textStatus, errorThrown) {
			  //alert("fail 2019"); 
			  get_po_now(batch_id, barang_id, warna_id, ini, table, persentase)}
	});
}


</script>
