<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css'); ?>"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css'); ?>"/>
<?=link_tag('assets/global/plugins/bootstrap-datepicker/css/datepicker3.css'); ?>
<link href="<?=base_url('assets/global/plugins/bootstrap-modal/css/bootstrap-modal.css');?>" rel="stylesheet" type="text/css"/>

<?=link_tag('assets/global/plugins/select2/select2.css'); ?>

<style type="text/css">
#general_table tr th{
	vertical-align: middle;
	/*text-align: center;*/
}

#general_table tr td{
	color:#000;
}

.batal{
	background: #ccc;
}

.barang-list{
	list-style-type:none;
	padding:0px;
}

.barang-list div{
	display:inline-block;
}

.barang-list li:nth-child(2n){
	background-color:rgba(170,170,170,0.1);
}

.nama-barang{
	width:150px;
	margin-right:20px;
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
							<a href="#portlet-config" data-toggle='modal' class="btn btn-default btn-sm btn-form-add">
								<i class="fa fa-plus"></i> PO Baru </a>
							<!-- <select class='btn btn-sm btn-default' name='status_select' id='status_select'>
								<option value="1" selected>Aktif</option>
								<option value="2">Batal</option>

							</select> -->
						</div>
					</div>
					<div class="portlet-body">
						<!-- <h4>Filter : </h4> -->
						<table width="400px">
							<tr>
								<td>Tanggal</td>
								<td class='padding-rl-5'> : </td>
								<td>
									<input type="text" class='form-control date-picker text-center' style="background:white; cursor:auto" readonly name="tanggal_start" value="<?=$tanggal_start?>">
								</td>
								<td>s/d</td>
								<td>
									<input type="text" class='form-control date-picker text-center' style="background:white;  cursor:auto" readonly name="tanggal_end"  value="<?=$tanggal_end?>">
								</td>
							</tr>
							<tr>
								<td>Customer</td>
								<td class='padding-rl-5'> : </td>
								<td colspan='3'>
									<select name='customer_id' id="customer_id_filter" class='form-control' style="width:100%">
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
									<select name="barang_id" class='form-control input1' id='barang_id_filter'>
		                				<option value=''>Pilih</option>
		                				<?foreach ($barang_list as $row) {?>
											<option value="<?=$row->id?>" <?=($barang_id == $row->id ? 'selected' : '')?> ><?=$row->nama_jual;?></option>
			                    		<? } ?>
			                    	</select>
								</td>
							</tr>
							<tr>
								<td>Warna</td>
								<td class='padding-rl-5'> : </td>
								<td colspan='3'>
									<select name="warna_id" class='form-control' id='warna_id_filter'>
		                				<option value=''>Pilih</option>
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
						<hr>
						<!-- table-striped table-bordered  -->
						<table class="table table-hover table-bordered" id="general_table">
							<thead>
								<tr>
								<th scope="col">
										Tanggal
									</th>
									<th scope="col" >
										No PO
									</th>
									<th scope="col">
										Barang
									</th>
									<th scope="col" >
										Status
									</th>
									<th scope="col" class='status_column'>
										Status Aktif
									</th>
									<th scope="col">
										Customer
									</th>
									
									<th scope="col" class='text-center'>
										Keterangan
									</th>
									<th scope="col" >
										PIC
									</th>
									<th scope="col">
										Actions
									</th>
									<th scope="col" class='status_column'>
										Status 
									</th>
								</tr>
							</thead>
							<tbody>
								<?foreach ($po_penjualan_list as $row) {
									$nama_barang = explode('??', $row->nama_barang);
									$qty = explode('??', $row->qty);
									$closed_by = explode('??', $row->closed_by);
									?>
									<tr>
										<td><?=is_reverse_date($row->tanggal);?></td>
										<td><?=$row->po_number?></td>
										<td class='status_column'><?=$row->status_aktif?></td>
										<td><?=$row->nama_customer;?></td>
										<td>
											<ul class='barang-list'>
												<?foreach ($nama_barang as $key => $value) {?>
													<li style="<?=($closed_by[$key] != '-' ? "background:lightpink" : '' )?>">
														<div class="nama-barang"><?=$value?></div>
														<div class="qty"><?=$qty[$key];?></div>
														<?=($closed_by[$key] != '-' ? "<i class='fa fa-lock'></i>" : '' )?>
													</li>
												<?}?>
											</ul>
										</td>
										<td><?=$row->keterangan?></td>
										<td><?=$row->username;?></td>
										<td><?=($row->status_po == 2 ? "<span class='label label-sm label-success'>closed</span>" : ( $row->status_po == 0 ? "<span class='label label-sm label-info'>locked</span>" : "<span class='label label-sm label-warning'>open</span>"))?></td>
										<td>
											<a href="<?=base_url().is_setting_link('transaction/po_penjualan_detail')?>?id=<?=$row->id?>" class='btn btn-xs yellow-gold'><i class="fa fa-search"></i></a>
										</td>
										<td class='status_column'>
											<?=$row->status;?>
										</td>
									</tr>
								<?}?>
							</tbody>
						</table>
						<!-- <button class='btn blue btn-test'>test</button> -->
					</div>
				</div>
			</div>
		</div>

		<div class="modal fade" style="position:relative" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body">
						<form action="<?=base_url('transaction/po_penjualan_insert')?>" class="form-horizontal" id="form_add_data" method="post">
							<h3 class='block'> PO Penjualan Baru</h3>

							<div class="form-group customer_section">
			                    <label class="control-label col-md-3">Customer<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
									<select name="customer_id" class='form-control' id='customer_id_select' >
										<option value=''>Pilih</option>
										<?foreach ($this->customer_list_aktif as $row) {?>
												<option value="<?=$row->id?>"><?=$row->nama;?><?=($row->tipe_company != '' ? ", ".$row->tipe_company : "")?> (<?=$row->alamat.
													($row->blok !='' && $row->blok != '-' ? ' BLOK.'.$row->blok: '').
													($row->no !='' && $row->no != '-' ? ' NO.'.$row->no: '').
													" RT.".$row->rt.' RW.'.$row->rw.
													($row->kelurahan !='' && $row->kelurahan != '-' ? ' Kel.'.$row->kelurahan: '').
													($row->kecamatan !='' && $row->kecamatan != '-' ? ' Kec.'.$row->kecamatan: '').
													($row->kota !='' && $row->kota != '-' ? ' '.$row->kota: '').
													($row->provinsi !='' && $row->provinsi != '-' ? ' '.$row->provinsi: '')?>)</option>
										<?}?>
									</select>
			                    </div>
			                </div>
							
							<div class="form-group">
			                	<!-- po_section -->
			                    <label class="control-label col-md-3">NO PO
			                    </label>
			                    <div class="col-md-6">
	                    			<input name='po_number' maxlength='100' id="po_number" class='form-control'>
			                    </div>
			                </div> 

							<div class="form-group">
			                    <label class="control-label col-md-3">Tanggal<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
	                    			<input name='tanggal' class='form-control date-picker' id='tanggal_add' value="<?=date('d/m/Y')?>"  >
			                    </div>
			                </div>

							<div class="form-group">
			                    <label class="control-label col-md-3">Contact Person<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
	                    			<input name='contact_person' class='form-control' id='contact_person_add' >
			                    </div>
			                </div>

							<div class="form-group">
			                    <label class="control-label col-md-3">Harga include PPN<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
									<div class="checkbox-list" style='margin-top:10px;'>
										<input checked type="checkbox" name="ppn_include_status" id="ppn_status_add" value='1'>
									</div>
			                    </div>
			                </div>

			                <div class="form-group" >
			                    <label class="control-label col-md-3">Tanggal Kirim (optional)
			                    </label>
			                    <div class="col-md-6">
	                    			<input name='tanggal_kirim' class='form-control date-picker' value="" >
			                    </div>
			                </div> 

							<div class="form-group">
			                    <label class="control-label col-md-3">Alamat Kirim (optional)
			                    </label>
			                    <div class="col-md-6">
	                    			<textarea name='alamat_kirim' class='form-control' row='3'></textarea>
			                    </div>
			                </div> 

							<div class="form-group">
								<!-- po_section -->
								<label class="control-label col-md-3">Keterangan
								</label>
								<div class="col-md-6">
									<textarea name='keterangan' maxlength='250' class='form-control'></textarea>
								</div>
							</div> 
						</form>
					</div>

					<div class="modal-footer">
						<!-- <button type="button" class="btn blue btn-active btn-save-tab" title='Save & Buka di Tab Baru'>Save & New Tab</button> -->
						<button type="button" class="btn blue btn-active btn-trigger" id="btnAddPOPenjualan" onclick="submitFormAdd()" >Save</button>
						<button type="button" class="btn btn-active default" data-dismiss="modal">Close</button>
					</div>
				</div>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
		</div>
	</div>			
</div>

<script src="<?php echo base_url('assets/global/plugins/bootstrap-modal/js/bootstrap-modalmanager.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/bootstrap-modal/js/bootstrap-modal.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/jquery-validation/js/jquery.validate.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/bootbox/bootbox.min.js'); ?>" type="text/javascript"></script>

<script src="<?php echo base_url('assets/global/plugins/datatables/media/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets_noondev/js/table-advanced.js'); ?>"></script>
<script src="<?php echo base_url('assets_noondev/js/fnReload.js');?>"></script>


<script>

	$('#general_table').DataTable({
        "paging": true,
        "pageLength": 100
    });
	const btnSubmit = document.querySelector("#btnAddPOPenjualan");
	const formAdd = document.querySelector("#form_add_data");

	btnSubmit.addEventListener("click", function(){
		const customer_id = document.querySelector("#customer_id_select").value;
		const tanggal = document.querySelector("#tanggal_add").value;
		const po_number = document.querySelector("#po_number").value;
		
		if (customer_id != '' && tanggal != '' && po_number != '') {
			formAdd.submit();
		}else{
			bootbox.alert("Mohon isi tanggal, customer, dan po number !");
		}
	})

jQuery(document).ready(function() {

	$('#warna_id_select, #barang_id_select, #warna_id_filter, #barang_id_filter, #customer_id_filter, #customer_id_select').select2({
		placeholder: "Pilih...",
		allowClear: true
	});
});


</script>
