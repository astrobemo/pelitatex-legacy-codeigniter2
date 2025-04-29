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
							
						</div>
					</div>
					<div class="portlet-body">
						<?$nama_barang_selected = ''; $nama_warna_selected = ''; $customer_selected?>
						<form action="" class="form-horizontal" id="form_filter_data" method="get">
							<table width="500px">
								<tr>
									<td>Customer</td>
									<td class='padding-rl-5'> : </td>
									<td colspan='3' style="width:400px;">
										<select name='customer_id' id="customer_id" class='form-control' style="min-width:200px">
											<option value=''>Semua</option>
											<?foreach ($this->customer_list_aktif as $row) {?>
												<option <?=($customer_id==$row->id ? 'selected' : '');?> value="<?=$row->id?>"><?=$row->nama;?><?=($row->tipe_company != '' ? ", ".$row->tipe_company : "")?> (<?=$row->alamat.
															($row->blok !='' && $row->blok != '-' ? ' BLOK.'.$row->blok: '').
															($row->no !='' && $row->no != '-' ? ' NO.'.$row->no: '').
															" RT.".$row->rt.' RW.'.$row->rw.
															($row->kelurahan !='' && $row->kelurahan != '-' ? ' Kel.'.$row->kelurahan: '').
															($row->kecamatan !='' && $row->kecamatan != '-' ? ' Kec.'.$row->kecamatan: '').
															($row->kota !='' && $row->kota != '-' ? ' '.$row->kota: '').
															($row->provinsi !='' && $row->provinsi != '-' ? ' '.$row->provinsi: '')?>)</option>
											<?}?>
										</select>
									</td>
								</tr>
								<tr>
									<td>Barang</td>
									<td class='padding-rl-5'> : </td>
									<td colspan='3'>
										<select name="barang_id" class='form-control input1' id='barang_id'>
											<option value=''>Semua</option>
											<?foreach ($this->barang_list_aktif as $row) {?>
												<option value="<?=$row->id?>" <?=($barang_id == $row->id ? 'selected' : '')?> ><?=$row->nama_jual;?></option>
											<? } ?>
										</select>
									</td>
								</tr>
								<tr>
									<td>Warna</td>
									<td class='padding-rl-5'> : </td>
									<td colspan='3'>
										<select name="warna_id" class='form-control' id='warna_id'>
											<option value=''>Semua</option>
											<?foreach ($this->warna_list_aktif as $row) { ?>
												<option value="<?=$row->id?>"  <?=($warna_id == $row->id ? 'selected' : '')?>><?=$row->warna_beli;?></option>
											<? } ?>
										</select>
									</td>
								</tr>
								<tr>
									<td></td>
									<td></td>
									<td colspan='3'>
										<button class='btn default btn-block'>GO</button>
									</td>
								</tr>
							</table>


						</form>
						
						<hr class='hidden-print'/>
						
						<table class="table table-hover table-striped table-bordered" id="general_table">
							<thead>
								<tr>
									<th scope="col" style='width:100px;'>
										Customer
									</th>
									<th scope="col">
										NO PO
									</th>
									<th scope="col" class='status_column'>
										Tanggal
									</th>	
									<th scope="col" >
										Tanggal
									</th>								
									
									<th scope="col">
										<span class='span-table barang-span' style='width:165px;'>
											Barang
										</span>
										<span class='span-table harga-span' style='width:120px'>
											Harga
										</span>
										<span class='span-table order-span'  style='width:60px' >
											Order
										</span>
										<span class='span-table delivered-span'  style='width:80px'>
											Invoice
										</span>
										<span class='span-table outstanding-span'>
											<span style='font-size:0.9em'>Outstanding</span> 
										</span>
									</th>
									<th scope="col">
										Actions
									</th>
								</tr>
							</thead>
							<tbody>
								<?foreach ($po_penjualan_report as $row) {
									if (!isset($total_order[$row->customer_id])) {
										$total_order[$row->customer_id] = 0;
										$total_delivered[$row->customer_id] = 0;
										$total_outstanding[$row->customer_id] = 0;
									}

									$satuan = explode('??', $row->nama_satuan);
									$qty_po = explode('??', $row->data_qty_po);
									$qty_invoice = explode('??', $row->data_qty_invoice);
									$nama_barang = explode('??', $row->nama_barang);
									$harga_barang = explode('??', $row->data_harga);

									$closed_by = explode('??', $row->closed_by);
									$closed_date = explode('??', $row->closed_date);

									$subtotal_order = 0;
									$subtotal_delivered = 0;
									$subtotal_outstanding = 0;

								?>
									<tr >
										<td><?=$row->nama_customer;?></td>
										<td><b style='font-size:1.1em;' class='po-number'><?=$row->po_number;?></b>
										<td><?=date('d F Y', strtotime($row->tanggal));?></td>
										<td class='status_column'><?=$row->tanggal;?></td>
											<span class='idx' hidden><?=$ind;?></span></td>
										<td>
											<ul style='padding:0px; margin:0px' class='po-detail'>
												<?foreach ($satuan as $key => $value) {
													$color='';
													if ($closed_by[$key] != '-') {
														$color = '#FFC6B9';
													}
													$total_order[$row->customer_id] += $qty_po[$key];
													$total_delivered[$row->customer_id] += $qty_invoice[$key];
													$ots = $qty_po[$key] - $qty_invoice[$key];
													$total_outstanding[$row->customer_id] += ($ots < 0 ? 0 : $ots);

													$subtotal_order += $qty_po[$key];
													$subtotal_delivered += $qty_invoice[$key];
													$subtotal_outstanding += ($ots < 0 ? 0 : $ots);
													

													?>
													<li style="list-style-type:none; background:<?=$color;?>;">
														<div class="<?=($qty_invoice[$key] > 0 ? 'show-detail-datang' : '' );?> " style='display:inline-block' >
															<span class='span-table barang-span'><?=$nama_barang[$key];?></span>
															
															<span style='color:transparent'>;</span>
															<span class='span-table harga-span'><?=($harga_barang[$key] != '' ? number_format($harga_barang[$key] ,'0',',','.') : '') ;;?></span>
															
															<span style='color:transparent'>;</span>
															<span  class='span-table order-span qty-po'><?=number_format(($qty_po[$key] == '' ? 0 : $qty_po[$key]) ,'0',',','.');?></span>
															
															<span style='color:transparent'>;</span>
															<u class='span-table delivered-span'>
																<span class='qty-datang'><?=number_format($qty_invoice[$key],'0',',','.');?></span>
															</u>
															
															<span style='color:transparent'>;</span>
															<span class='span-table outstanding-span'>
																<?=number_format($qty_po[$key] - $qty_invoice[$key] ,'0',',','.');?>
															</span>
															
															<span style='color:transparent'>;</span>
															<span class='nama-satuan hidden-print'><?=$value;?> <?=($closed_by[$key] != '-' ? "<i class='fa fa-lock'></i>" : "")?></span> 
														</div>
													</li>
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
										</td>
										<td>
											<a class='btn btn-xs yellow-gold' href="<?=base_url().is_setting_link('report/po_penjualan_report_detail');?>?id=<?=$row->id;?>" target="_blank"><i class='fa fa-search'></i></a>
										</td>
									</tr>
								
								<?	
								}?>
								
							</tbody>
						</table>

						<hr class='hidden-print'/>

						<table class='table hidden-print' id='total-all' hidden>
							<thead>
								<tr>
									<th>Nama</th>
									<th class='text-center'>Order</th>
									<th class='text-center'>Delivered</th>
									<th class='text-center'>Outstanding</th>
								</tr>
							</thead>
							<tbody>
							</tbody>
						</table>
						<hr class='hidden-print'/>

						<hr class='hidden-print'/>
						<div class='text-right'>
							<button type='button' style='display:none' class='btn btn-lg blue btn-print hidden-print' onclick="window.print()"><i class='fa fa-print'></i> PRINT</button>
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


	$("#barang_id, #warna_id, #customer_id").select2();

	$("#general_table").DataTable({
        "lengthMenu": [
            [10, 25, 50, 100, -1],
            [10, 25, 50, 100, "All"] // change per page values here
        ],
        "pageLength": 100, // set the initial value,

	});
	
});


</script>
