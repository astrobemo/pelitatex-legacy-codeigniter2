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

		<div class="page-content">
			<div class='container'>
				<div class="row margin-top-10">
					<div class="col-md-12">
						<div class="portlet light ">
							<div class="portlet-title">
								<div class="caption caption-md">
									<i class="icon-bar-chart theme-font hide"></i>
									<span class="caption-subject theme-font bold uppercase">Dashboard</span>
									<!-- <span class="caption-helper hide">weekly stats...</span> -->
								</div>
								<div class="actions">
									<!-- <div class="btn-group btn-group-devided" data-toggle="buttons">
										<label class="btn btn-transparent grey-salsa btn-circle btn-sm active">
										<input type="radio" name="options" class="toggle" id="option1">Today</label>
										<label class="btn btn-transparent grey-salsa btn-circle btn-sm">
										<input type="radio" name="options" class="toggle" id="option2">Week</label>
										<label class="btn btn-transparent grey-salsa btn-circle btn-sm">
										<input type="radio" name="options" class="toggle" id="option2">Month</label>
									</div> -->
								</div>
							</div>
							<div class="portlet-body">
								<div class='row'>
									<div class='col-xs-12'>
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
										<h4 style='color:blue'>NOTE ORDER</h4>
										<form id='form_add_note_order' method='post' action="<?=base_url()?>admin/note_order_insert">
											<div id='form-note-div'>
												<div class='col-xs-6'>
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
												<div class='col-xs-6' style='border-left:2px solid #ddd'>
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
											<div class='col-xs-12 text-right'>
												<hr/>
												<button type='button' class='btn blue btn-lg btn-note-order-save'>SAVE</button>	
											</div>
										</form>
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
});
</script>
<!-- END JAVASCRIPTS -->
