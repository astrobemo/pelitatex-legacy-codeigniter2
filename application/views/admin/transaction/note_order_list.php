<link href="<?=base_url('assets/global/plugins/typeahead/typeahead.css');?>" rel="stylesheet" type="text/css"/>

<style type="text/css">
/*.note-order-list-horizontal{}*/
.terdaftar{
	position: absolute;
	top: 8px;
	right: 8px;
	color: blue;
}

.note-order-list-horizontal tr td{
	vertical-align: top;
	padding: 0 5px 5px 5px;
	min-width: 80px;
}


.note-order-list-horizontal tr{
	border-bottom: 1px solid #eee;
}

.note-order-list-horizontal tr:nth-last-child(1){
	border: none;
}

.note-order-list-horizontal .item-konten{
	padding-right: 18px;
}

.btn-note-order-item-inline{
	background: rgba(221,221,221,0.7);
	position: absolute;
	width: 100%;
	text-align: center;
	display: none;
	height: 100%;
	/*padding: 0 5px 5px 5px;*/
}

.btn-note-order-item-inline .btn{
	margin-top: 10%;
}

.required{
	color: red;
}

.kolom-note-item-content{

}
</style>
		
		<div class="modal fade bs-modal-lg" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-body">
						<div class='row'>								
							<select id='nama-barang-note-copy' hidden>
								<?foreach ($this->barang_list_aktif as $row) {?>
									<option value="<?=strtoupper($row->nama_jual);?>"><?=strtoupper($row->nama_jual);?>??<?=$row->harga_jual?>??<?=$row->id?></option>
								<?}?>
							</select>
							<select id='nama-warna-note-copy' hidden>
								<?foreach ($this->warna_list_aktif as $row) {?>
									<option value="<?=strtoupper($row->id);?>"><?=strtoupper($row->warna_jual);?></option>
								<?}?>
							</select>
							<form id='form_add_note_order' method='post' action="<?=base_url()?>admin/note_order_insert">
								<div id='form-note-div'>
									<div class='col-xs-12 col-md-7'>
										<table width='100%'>
											<tr>
												<td style='padding-bottom:5px'>
													<small style='padding-left:10px;'>Tanggal Pesan<span class="required">* </span></small>
													<input name='tanggal_note_order' class='form-control date-picker'  value="<?=date('d/m/Y')?>" tabindex='1'>
												</td>
												<td style='padding:0 2px; text-align:center'></td>
												<!-- <td style='padding-bottom:5px'>
													<input name='tanggal_target' class='form-control date-picker' placeholder='tanggal target' tabindex='2' >
													<small style='padding-left:10px;'>Tanggal target</small>
												</td> -->
												<td style='padding-bottom:5px'>
													<small style='padding-left:10px;'>PIC<span class="required">* </span></small>
													<select name='pic_id' id='pic-id' class='form-control'>
														<?foreach (get_sales_list() as $row) {?>
															<option value="<?=$row->id?>"><?=$row->username;?></option>
														<?}?>
													</select>
												</td>
											</tr>
											<tr>
												<td style='padding-bottom:5px; position:relative'>
													<small style='padding-left:10px;'>Customer (nama tercatat di program)<span class="required">* </span></small>
													<input name='nama_customer' class='form-control' id="customers_note" placeholder='Nama Perusahaan/pribadi' tabindex='3'><span class='terdaftar' hidden><i class='fa fa-check-circle'></i></span>
													<select id="customer-note-list" hidden>
														<?foreach ($this->customer_list_aktif as $row) {?>
															<option value="<?=strtoupper($row->nama)?>"><?=$row->id?>??<?=$row->alamat;?><?=($row->blok != '-' ? ' blok:'.$row->blok : '')?><?=($row->no != '-' ? ' no:'.$row->no : ''); ?>??<?=$row->telepon1?></option>
														<?}?>
													</select>
													<div class='info-alamat' hidden style="position:absolute; left:80%; bottom:50%; background:#d9f9f9; padding:10px; min-width:250px;"></div>
												</td>
												<td style='padding:0 2px; text-align:center'> </td>
												<td style='padding-bottom:5px'>
													<small style='padding-left:10px;'>Nama untuk di hub. kembali </small>
													<input name='contact_person' class='form-control' placeholder='Contact Person' tabindex='4' >
												</td>
											</tr>
										</table>
										<input name='customer_id' hidden>
									</div>
									<div class='col-xs-12 col-md-5' style='border-left:2px solid #ddd'>
										<small style='padding-left:10px;'>No Kontak</small>
										<input name='contact_info' class='form-control' placeholder='Contact Info' style='margin-bottom:4px' tabindex='5'/>
										<small style='padding-left:10px;'>Catatan </small>
										<input name='catatan' id="catatan-note-order" class="form-control" placeholder="Catatan" tabindex='6'/>
										<input name='link' value="http://<?=$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];?>" >

									</div>
								</div>
								<div class='col-xs-12'>
									<hr/>
									<table id="note-order-list" class='table'>
										<thead>
											<tr>
												<th>Barang</th>
												<th>Warna</th>
												<th>Qty</th>
												<th>Harga</th>
												<th>Total</th>
												<th></th>
											</tr>
										</thead>
										<tbody>

										</tbody>
										<tfoot>
											<tr id="barang-note-form">
												<td>
													<input name='nama_barang' tabindex='7' class='form-control' id="nama-barang-note" placeholder="Barang" >
												</td>
												<td>
													<input name='nama_warna' tabindex='8' class='form-control' id="nama-warna-note" placeholder="Warna" >
												</td>
												<td style='width:80px'>
													<input name='qty' tabindex='9' class='form-control text-center' id="qty-barang-note" placeholder="qty" >
												</td>
												<td style='width:150px'>
													<input name='harga' tabindex='10'  class='form-control text-center' id='harga-barang-note' placeholder="harga" >
												</td>
												<td>
													<input name='total' id='total-barang-note' style='font-weight:bold' class='form-control' placeholder="total" disabled >
												</td>
												<td>
													<input name='barang_id' id="id-barang-note" hidden>
													<input name='warna_id' id='id-warna-note' hidden>
													<input id="id-detail-note" hidden>
													<input id='note-item-index' hidden>
													<button type='button' class='btn btn-md green' id='btn-add-note-item'><i class='fa fa-check'></i></button>
												</td>
											</tr>
										</tfoot>
									</table>
									
								</div>
							</form>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn blue btn-active btn-note-order-save">Save</button>
						<button type="button" class="btn default" data-dismiss="modal">Close</button>
					</div>
				</div>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
		</div>

		<div class="page-content">
			<div class='container'>
				<div class="row margin-top-10">
					<div class="col-md-12">
						<div class="portlet light ">
							<div class="portlet-title">
								<div class="caption caption-md">
									<i class="icon-bar-chart theme-font hide"></i>
									<span class="caption-subject theme-font bold uppercase"><?=$breadcrumb_small;?></span>
									<!-- <span class="caption-helper hide">weekly stats...</span> -->
								</div>
								<div class="actions">
									<a href="#portlet-config" data-toggle='modal' id="btn-note-order" class="btn btn-default btn-sm btn-form-add">
									<i class="fa fa-plus"></i> Tambah </a>
								</div>
							</div>
							<div class="portlet-body">
								
								<div class='row'>
									<div class="tabbable tabbable-custom">
										<ul class="nav nav-tabs">
											<li class="active">
												<a href="#tab_1_1" data-toggle="tab">
												Follow Up Note List </a>
											</li>
											<li>
												<a href="#tab_1_2" data-toggle="tab">
												Catatan Pesanan </a>
											</li>
											<li>
												<a href="#tab_1_3" data-toggle="tab">
												Catatan Tuntas </a>
											</li>
										</ul>

										<div class="tab-content">
											<div class="tab-pane active" id="tab_1_1">
												<h4>Follow Up Note List</h4>

												<table class="table">
													<thead>
														<tr>
															<th>Tanggal</th>
															<th>Customer</th>
															<th>Barang</th>
															<th>Order[Stok]</th>
															<th>Action</th>
														</tr>
													</thead>
													<tbody>
														<?foreach ($rekap_note_fu as $keys => $values) {
															$idx = 0; 
															foreach ($values as $key => $value) {?>
																<tr>
																	<?if ($idx == 0) {?>
																		<td rowspan="<?=count($values)?>" ><?=is_reverse_datetime2($value->tanggal);?></td>
																		<td rowspan="<?=count($values)?>" >
																			<span class='customer_id' hidden><?=$value->customer_id;?></span>
																			<i class='fa fa-database'></i> : <span class='nama_customer'><?=$value->nama_customer;?></span> <?=($value->customer_id != 0 ? "<i style='color:blue' class='fa fa-check-circle'></i>" : '')?> <br/>
																			<i class='fa fa-user'></i> : <span class='contact_person'><?=($value->contact_person == 0 ? '' : $value->contact_person) ;?></span> <br/>
																			<i class='fa fa-phone'></i> : <span class='contact_info'><?=$value->contact_info;?></span><br/>
																			<i class='fa fa-info'></i> : <span class='catatan'><?=$value->catatan;?></span>
																		</td>
																	<?}?>
																	<td>
																		<?=$value->nama_barang_jual;?>
																		<?=$value->nama_warna;?>
																	</td>
																	<td><b><?=(float)$value->qty_note;?></b> [<?=(float)$value->qty;?>]</td>
																	<td>
																		<span style='padding:0 5px; color:#000; cursor:pointer'><i class='fa fa-phone'></i>1</span>
																		<span style='padding:0 5px; color:#000; cursor:pointer'><i class='fa fa-phone'></i>2</span>
																		<span style='padding:0 5px; color:#000; cursor:pointer' alt='to penjualan'><i class='fa fa-check'></i></span>
																		<span style='padding:0 5px; color:red; cursor:pointer' alt='batal'><i class='fa fa-times'></i></span>
																	</td>																	
																</tr>
															<?$idx++;}?>
														<?}?>
													</tbody>
												</table>
											</div>
											<div class="tab-pane" id="tab_1_2">
												<h4>Catatan Pesanan</h4>
												<table class='table' id='note_order_table'>
													<thead>
														<tr>
															<th>No</th>
															<th>Tanggal Input</th>
															<!-- <th>Tanggal Target</th> -->
															<th>Customer</th>
															<th>Barang - Qty - Harga</th>
															<!-- <th>Status</th> -->
															<?//if (is_posisi_id() < 4) { ?>
																<th>Action</th>
															<?//}?>
														</tr>
													</thead>
													<tbody>
														<?
													$idx = 1;
													foreach (get_note_order() as $row) {?>
														<tr <?/*style="<?=($row->matched == 1 ? 'border:2px solid red' : '');?>" */?>>
															<td>
																<?=$idx;?>													
															</td>
															<td>
																<span class='tanggal_note_order' hidden><?=is_reverse_datetime2($row->tanggal_note_order);?></span> 
																<?=str_replace('-', '<br/>', is_reverse_datetime2($row->tanggal_note_order));?>
															</td>
															<!--<td><span class='tanggal_target'><?=is_reverse_date($row->tanggal_target);?></span></td>-->
															<td>
																<span class='customer_id' hidden><?=$row->customer_id;?></span>
																<i class='fa fa-database'></i> : <span class='nama_customer'><?=$row->nama_customer;?></span> <?=($row->customer_id != 0 ? "<i style='color:blue' class='fa fa-check-circle'></i>" : '')?> <br/>
																<i class='fa fa-user'></i> : <span class='contact_person'><?=($row->contact_person == 0 ? '' : $row->contact_person) ;?></span> <br/>
																<i class='fa fa-phone'></i> : <span class='contact_info'><?=$row->contact_info;?></span><br/>
																<i class='fa fa-info'></i> : <span class='catatan'><?=$row->catatan;?></span>

															</td>
															<td>
																<?	
																	$status = $row->status;
																	$nama_barang = explode('??', $row->nama_barang);
																	$status = explode(',', $row->status);
																	$barang_id = explode(',', $row->barang_id);
																	$warna_id = explode(',', $row->warna_id);
																	$tipe_barang = explode(',', $row->tipe_barang);
																	$nama_warna = explode(',', $row->nama_warna);
																	$qty = explode(',', $row->qty);
																	$roll = explode(',', $row->roll);
																	$harga = explode(',', $row->harga);
																	$note_order_detail_id = explode(',', $row->note_order_detail_id);
																	
																	// $barang_wrap = array_combine($nama_barang, $nama_warna);
																	$barang_wrap = array();
																	foreach ($nama_barang as $key => $value) {
																		$barang_wrap[$value][$key] = $key;
																	}
																?>
																<table class='note-order-list-horizontal'>
																<?
																	//==================barang list group by barang==========
																	foreach ($barang_wrap as $key => $values) {?>
																		<tr>
																			<td style='padding-right:20px' >
																				<span class='note_order_id' hidden><?=$row->id;?></span>
																				<span class='nama-barang'><?=$key?></span>
																			</td>
																			<?foreach ($values as $value) {?>
																				<td class='note-item-konten'>
																					<div style='position:relative'>
																						<div class='btn-note-order-item-inline'>
																							<a href="#portlet-config-detail" data-toggle='modal' class='btn btn-xs green btn-edit-note'><i class='fa fa-edit'></i></a>
																							<button class='btn btn-xs red btn-remove-item-note-h'><i class='fa fa-times '></i></button>
																						</div>
																						<span class='note_order_detail_id' hidden ><?=$note_order_detail_id[$value];?></span>

																						<div class='item-konten'>
																							<span class='id-barang'  hidden ><?=$barang_id[$value]?></span>
																							<span class='id-warna'  hidden  ><?=$warna_id[$value];?></span>
																							<span class='harga'  hidden ><?=$harga[$value];?></span>
																							<u>
																								<b class='nama-warna'><?=$nama_warna[$value];?></b>
																								<?=($warna_id[$value] == 0 ? "<span class='badge badge-roundless badge-danger'>new</span>" : '');?>
																							</u>
																							<br/> 
																							<span class='qty'><?=(float)$qty[$value];?></span> 
																						</div>
																					</div>
																				</td>
																			<?}?>
																		</tr>
																		
																	<?}
																?>
																</table>
																
																<table class='note-order-item-registered' hidden>
																	<?
																	if ($row->nama_barang != '') {
																		foreach ($nama_barang as $key => $value) {?>
																			<tr style="<?=($status[$key] == 1 ? 'text-decoration:line-through' : '' );?>">
																				<td>
																					<?if ($matched[$key] == 1) { ?>
																						<span style='color:red'><i class='fa fa-flag'></i></span>
																					<?}?>
																					<span class='tipe_barang' hidden><?=$tipe_barang[$key];?></span>
																					<span class='barang_id' hidden><?=$barang_id[$key];?></span>
																					<span class='note_order_detail_id' <?=(is_posisi_id() != 1 ? 'hidden' :'')?> ><?=$note_order_detail_id[$key];?></span>
																					<span class='note_order_id' hidden><?=$row->id;?></span>
																				</td>
																				<td style='width:150px'><span class='nama_barang'><?=$value;?></span> <?=($barang_id[$key] == 0 ? "<span class='badge badge-roundless badge-danger'>new</span>" : '');?></td>
																				<td style='width:100px'><span class='warna_id' hidden><?=$warna_id[$key];?></span> <span class='nama_warna'><?=$nama_warna[$key];?></span> </td>
																				<td style='width:50px'><span class='qty'><?=is_qty_general($qty[$key]);?></span></td>
																				<td style='width:50px' hidden><span class='roll'><?=$roll[$key];?></span></td>
																				<td style="width:50px">
																					<?=number_format($harga[$key],'0',',','.');?>
																					<span class='harga' hidden><?=$harga[$key];?></span>
																				</td>
																				<td style="width:50px;" <?=($status == 1 ? 'hidden' : '' ); ?> >
																					<i class='fa fa-edit btn-edit-note' style='cursor:pointer; color:green'></i>
																					<i class='fa fa-times btn-remove-item-note' style='cursor:pointer; color:red'></i>
																				</td>
																				<td>
																					<span class='status' hidden><?=$status[$key];?></span>
																					<?if ($status[$key] == 1) { ?>
																						<button class='btn btn-xs blue check_note_order'><i class='fa fa-check'></i> completed <br/> by <?=is_get_username($done_by[$key]);?> <br/><?=is_reverse_datetime($done_time[$key]);?></button>
																					<?}elseif($status[$key] == -1){?>
																						<button class='btn btn-xs red check_note_order'><i class='fa fa-times'></i> cancel by <?=is_get_username($done_by[$key]);?> <?=is_reverse_datetime($done_time[$key]);?></button>
																					<?}else{?>
																						<!-- <button style='display:none' class='btn btn-xs default btn-reminder'> <i class='fa fa-plus'></i> <i class='fa fa-clock-o'></i></button> -->
																						<button class='btn btn-xs default check_note_order'> completed</button>
																					<?}?>
																					
																				</td>
																			</tr>
																		<?}
																	}
																	?>
																</table>
															</td>
															<?//if (is_posisi_id() < 4) { ?>
																<td>
																	<span class='id' hidden><?=$row->id;?></span>
																	<form hidden action="<?=base_url('admin/set_reminder');?>" hidden class='form-reminder'>
																		<input name='note_order_id' value="<?=$row->id;?>" hidden>
																		<input name='reminder' class='form_datetime'> <button><i class='fa fa-check'></i></button>
																	</form>
																	<!-- <button class='btn btn-xs blue btn-add'> <i class='fa fa-plus'></i></button> -->
																	<a href="#portlet-config" data-toggle='modal' class='btn btn-xs green btn-edit-note-data'> <i class='fa fa-edit'></i></a>
																	<button data-toggle='modal' class='btn btn-xs red btn-edit-note-remove'> <i class='fa fa-times'></i></button>
																</td>
															<?//}?>
														</tr>
													<?$idx++;}?>
													</tbody>
												</table>
											</div>
											<div class="tab-pane" id="tab_1_3">
												<h4>Note Order Tuntas</h4>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>			
		</div>


		<script src="<?php echo base_url('assets/global/plugins/bootbox/bootbox.min.js'); ?>" type="text/javascript"></script>
		<script src="<?php echo base_url('assets/global/plugins/typeahead/typeahead.bundle.min.js'); ?>" type="text/javascript"></script>
		<script src="<?php echo base_url('assets_noondev/js/webprint.js'); ?>" type="text/javascript"></script>
		<script src="<?php echo base_url('assets_noondev/js/note-order-typehead.js'); ?>" type="text/javascript"></script>

<script>
$(document).ready(function() {
	$("#btn-note-order").click(function(){
      alert('te');
      form = $("#form_add_note_order");
      form.find('input').each(function() {
        $(this).val('');
      });
      setTimeout(function(){
        form.find('input[tabindex=1]').focus();
      },700);      
      // $("#customers_note").val('');
      // $("#catatan-note-order").val("");
      
      reset_form_order_item();

      var d = new Date();
      var month = d.getMonth()+1;
      var day = d.getDate();

      var output = (day<10 ? '0' : '') + day+ '/' +
          (month<10 ? '0' : '') + month + '/' + d.getFullYear();
      form.find("[name=tanggal]").val(output);
      $("#note-order-list tbody").html('');
    });
});
</script>
<!-- END JAVASCRIPTS -->
