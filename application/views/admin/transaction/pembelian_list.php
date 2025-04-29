<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css'); ?>"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css'); ?>"/>
<?=link_tag('assets/global/plugins/bootstrap-datepicker/css/datepicker3.css'); ?>
<link href="<?=base_url('assets/global/plugins/bootstrap-modal/css/bootstrap-modal.css');?>" rel="stylesheet" type="text/css"/>

<?=link_tag('assets/global/plugins/select2/select2.css'); ?>
<link href="<?=base_url('assets_noondev/css/bootstrap-modal-bs3patch.css');?>" rel="stylesheet" type="text/css"/>


<style type="text/css">

/*
#form_edit_data input, #form_add_data input{
	border:1px solid #ddd;
	padding: 0 5px;
	width:250px;
	height: 24px;
} 

#form_edit_data .select2-container .select2-choice, #form_add_data .select2-container .select2-choice, #form_add_data select , #form_edit_data select {
	border:1px solid #ddd;
	padding: 0 5px;
	width:250px;
	background: none;
	height: 24px;
	border-radius: 0px;
} 

#form_edit_data table tr td, #form_add_data table tr td{
	padding-right:10px;
}

.right-td{
	padding-left: 10px;
}

#rekap_barang_list input{
	width:100px;
}

.input-alert{
	color:red;
	display: none;
}

.input-success{
	color:green;
	display: none;
}



#form_add_data .gudang-input{
	background:#ddd;
}


.modal{
	width: 90%;
	left: 5%;
	margin: 0;
	height: 95%;
}


/*
#ui-datepicker-div{
	background: #eee;
	padding: 10px;
}

#ui-datepicker-div table{
	border-spacing: 10px;
	border-collapse: separate;
}

#ui-datepicker-div .ui-icon{
	padding:0 5px;
	border: 1px solid #222;
	margin-right: 10px;
	text-align: center;
}

#ui-datepicker-div .ui-icon:hover{
	background: white;
}

#ui-datepicker-div a:hover{
	text-decoration: none;
	color:black;
}
*/


</style>
<div class="page-content">
	<div class='container'>

		<div id="pembelian-modal" class="modal fade" style='width:100%' tabindex="-1">
		</div>


		<?/*<div class="modal fade" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<!-- <div class="modal-dialog modal-full"> -->
				<div class="modal-content">
					<div class="modal-body">
						<form action="<?=base_url('transaction/pembelian_list_insert')?>" class="form-horizontal" id="form_add_data" method="post">
							<!-- <h3 class='block'> Tambah </h3> -->
							<b>Input pembelian</b>
			                <hr/>
			                <table>
			                	<tr>
			                		<td>Supplier</td>
			                		<td> : </td>
			                		<td>
			                			<select class='input1 supplier-input' style='font-weight:bold' name="supplier_id">
				                    		<?foreach ($this->supplier_list_aktif as $row) { ?>
				                    			<option value="<?=$row->id?>"><?=$row->nama;?></option>
				                    		<? } ?>
				                    	</select>
			                		</td>
			                	</tr>
			                	<td>Gudang</td>
			                		<td> : </td>
			                		<td>
			                			<select style='font-weight:bold' class='gudang-input' name="gudang_id">
				                    		<?foreach ($this->gudang_list_aktif as $row) { ?>
				                    			<option value="<?=$row->id?>"><?=$row->nama;?></option>
				                    		<? } ?>
				                    	</select>
				                    </td>
			                	<tr>
			                		<td>No Faktur</td>
			                		<td> : </td>
			                		<td>
			                			<input type="text" class="" name="no_faktur"/>
			                			<span class='input-alert'><i class='fa fa-times'></i></span>
			                			<span class='input-success'><i class='fa fa-check'></i></span>
			                			<span hidden='hidden' class='no_faktur_status'>false</span>
			                		</td>
			                	</tr>
			                	<tr>
			                		<td>OCKH</td>
			                		<td> : </td>
			                		<td>
			                			<input type="text" name="ockh"/>
			                		</td>
			                	</tr>
			                	<tr>
			                		<td>Tanggal</td>
			                		<td> : </td>
			                		<td><input type="text" readonly class="date-picker" value="<?=date('d/m/Y');?>" name="tanggal"/></td>
			                	</tr>
			                	<tr>
			                		<td>Toko</td>
			                		<td> : </td>
			                		<td>
			                			<select name="toko_id">
				                    		<?foreach ($this->toko_list_aktif as $row) { ?>
				                    			<option value="<?=$row->id?>"><?=$row->nama;?></option>
				                    		<? } ?>
				                    	</select>
			                		</td>
			                	</tr>
			                </table>


			                <fieldset style='border:1px solid black; padding:10px;width:60%; margin:20px 0'>
			                	<legend style='background:none;border:none; width:auto; margin:0;'>Formulir Barang</legend>
			                	<table style='width:100%;' id='form-add-list'>
			                	<tr>
			                		<td>Barang</td>
			                		<td> : </td>
			                		<td>
			                			<select name="barang_id" class='barang-id' id='barang_id_select'>
			                				<option value="">Pilihan..</option>
				                    		<?foreach ($this->barang_list_aktif as $row) { ?>
				                    			<option value="<?=$row->id?>"><?=$row->nama;?></option>
				                    		<? } ?>
				                    	</select>
				                    	<select name='harga' hidden='hidden'>
				                    		<?foreach ($this->barang_list_aktif as $row) { ?>
				                    			<option value="<?=$row->id?>"><?=$row->harga_beli;?></option>
				                    		<? } ?>
				                    	</select>
				                    </td>

				                    <td class='right-td'>Satuan</td>
			                		<td> : </td>
			                		<td><select name="satuan_id">
				                    		<?foreach ($this->satuan_list_aktif as $row) { ?>
				                    			<option value="<?=$row->id?>"><?=$row->nama;?></option>
				                    		<? } ?>
				                    	</select>
				                    </td>


			                	</tr>
			                	<tr>
			                		<td>Warna</td>
			                		<td> : </td>
			                		<td>
			                			<select name="warna_id" class='warna-id'>
			                				<option value=''>Pilihan..</option>
				                    		<?foreach ($this->warna_list_aktif as $row) { ?>
				                    			<option value="<?=$row->id?>"><?=$row->warna_beli;?></option>
				                    		<? } ?>
				                    	</select>
			                		</td>

			                		<td class='right-td'>Harga Beli</td>
			                		<td> : </td>
			                		<td>
			                			<input type="text" class='amount_number' name="harga_beli"/>
			                		</td>
			                		
			                	</tr>
			                	<tr>

			                		<td>Qty</td>
			                		<td> : </td>
			                		<td>
			                			<input name='qty'>
			                		</td>

			                		<td class='right-td'>Jml Roll</td>
			                		<td> : </td>
			                		<td>
			                			<input type="text" name="jumlah_roll"/>
			                		</td>	
		                					                		
			                	</tr>

			                	<!-- <tr>

			                		
			                		
			                	</tr> -->

			                	<tr>

			                		<td>Total</td>
			                		<td> : </td>
			                		<td>
			                			<span style='font-size:1.5em;font-weight:bold' class='total'></span>
			                		</td>			                		

			                		<td colspan='3'>
			                			<button class='btn btn-xs green btn-add-list'><i class='fa fa-plus'></i> Tambah</button>
			                		</td>
			                	</tr>


			                </table>
			                </fieldset>

			                <table class='table table-striped table-bordered table-hover' id='rekap_barang_list'>
			                	<thead>
			                		<tr>
			                			<th>Nama Barang</th>
			                			<th>Satuan</th>
			                			<!-- <th>Gudang</th> -->
			                			<th>Qty</th>
			                			<th>Roll</th>
			                			<th>Harga</th>
			                			<th>Total</th>
			                			<th style='width:200px !important'>Action</th>
			                		</tr>
			                	</thead>
			                	<tbody>
			                	</tbody>
			                </table>

			                <table width="100%" id='rekap_harga'>
			                	<tr>
			                		<td>
			                			<table>
			                				<tr>
			                					<td>Subtotal</td>
							                		<td> : </td>
							                		<td>
							                			<span class='subtotal'>0</span>
							                		</td>
							                	</tr>
							                	<tr>
							                		<td>Diskon</td>
							                		<td> : </td>
							                		<td><input name='diskon' class='amount_number' value='0'/></td>
							                	</tr>
							                	<tr>
							                		<td>Jatuh Tempo</td>
							                		<td> : </td>
							                		<td><input readonly name='jatuh_tempo' class='date-picker' value="<?=date('d/m/Y');?>"/></td>
							                	</tr>
							                	<tr>
							                		<td>Keterangan</td>
							                		<td> : </td>
							                		<td><input name='keterangan'/></td>
							                	</tr>
							                </table>
			                		</td>
			                		<td>
			                			<div style='font-size:3em;font-weight:bold'>
			                				Rp. <span class='grand_total'></span>,-
			                			</div>
			                		</td>
		                		</tr>
		                	</table>
			                    
						</form>
					</div>
					<div>
						<input name='test' style='display:none'>
					</div>

					<div class="modal-footer">
						<button type="button" class="btn blue btn-save">Save</button>
						<button type="button" class="btn default" data-dismiss="modal">Close</button>
					</div>
				</div>
				<!-- /.modal-content -->
			<!-- </div> -->
			<!-- /.modal-dialog -->
		</div>
		*/?>

		<?/*<div class="modal fade" id="portlet-config-edit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-full">
				<div class="modal-content">
					<div class="modal-body">
						<form action="<?=base_url('transaction/pembelian_list_insert')?>" class="form-horizontal" id="form_edit_data" method="post">
							<!-- <h3 class='block'> Tambah </h3> -->
							<b>Input pembelian</b>
			                <hr/>
			                <table>
			                	<tr>
			                		<td>Supplier</td>
			                		<td> : </td>
			                		<td>
			                			<input name='id'>
			                			<select class='input1 supplier-input' style='font-weight:bold' name="supplier_id">
				                    		<?foreach ($this->supplier_list_aktif as $row) { ?>
				                    			<option value="<?=$row->id?>"><?=$row->nama;?></option>
				                    		<? } ?>
				                    	</select>
			                		</td>
			                	</tr>
			                	<td>Gudang</td>
			                		<td> : </td>
			                		<td>
			                			<select style='font-weight:bold' class='gudang-input' name="gudang_id">
				                    		<?foreach ($this->gudang_list_aktif as $row) { ?>
				                    			<option value="<?=$row->id?>"><?=$row->nama;?></option>
				                    		<? } ?>
				                    	</select>
				                    </td>
			                	<tr>
			                		<td>No Faktur</td>
			                		<td> : </td>
			                		<td>
			                			<input type="text" class="" name="no_faktur"/>
			                			<span class='input-alert'><i class='fa fa-times'></i></span>
			                			<span class='input-success'><i class='fa fa-check'></i></span>
			                			<span hidden='hidden' class='no_faktur_status'>false</span>
			                		</td>
			                	</tr>
			                	<tr>
			                		<td>OCKH</td>
			                		<td> : </td>
			                		<td>
			                			<input type="text" name="ockh"/>
			                		</td>
			                	</tr>
			                	<tr>
			                		<td>Tanggal</td>
			                		<td> : </td>
			                		<td><input type="text" readonly class="date-picker" value="<?=date('d/m/Y');?>" name="tanggal"/></td>
			                	</tr>
			                	<tr>
			                		<td>Toko</td>
			                		<td> : </td>
			                		<td>
			                			<select name="toko_id">
				                    		<?foreach ($this->toko_list_aktif as $row) { ?>
				                    			<option value="<?=$row->id?>"><?=$row->nama;?></option>
				                    		<? } ?>
				                    	</select>
			                		</td>
			                	</tr>
			                </table>


			                <fieldset style='border:1px solid black; padding:10px;width:60%; margin:20px 0'>
			                	<legend style='background:none;border:none; width:auto; margin:0;'>Formulir Barang</legend>
			                	<table style='width:100%;' id='form-add-list'>
			                	<tr>
			                		<td>Barang</td>
			                		<td> : </td>
			                		<td>
			                			<select name="barang_id" class='barang-id' id='barang_id_select'>
			                				<option value="">Pilihan..</option>
				                    		<?foreach ($this->barang_list_aktif as $row) { ?>
				                    			<option value="<?=$row->id?>"><?=$row->nama;?></option>
				                    		<? } ?>
				                    	</select>
				                    	<select name='harga' hidden='hidden'>
				                    		<?foreach ($this->barang_list_aktif as $row) { ?>
				                    			<option value="<?=$row->id?>"><?=$row->harga_beli;?></option>
				                    		<? } ?>
				                    	</select>
				                    </td>

				                    <td class='right-td'>Satuan</td>
			                		<td> : </td>
			                		<td><select name="satuan_id">
				                    		<?foreach ($this->satuan_list_aktif as $row) { ?>
				                    			<option value="<?=$row->id?>"><?=$row->nama;?></option>
				                    		<? } ?>
				                    	</select>
				                    </td>


			                	</tr>
			                	<tr>
			                		<td>Warna</td>
			                		<td> : </td>
			                		<td>
			                			<select name="warna_id" class='warna-id'>
			                				<option value=''>Pilihan..</option>
				                    		<?foreach ($this->warna_list_aktif as $row) { ?>
				                    			<option value="<?=$row->id?>"><?=$row->warna_beli;?></option>
				                    		<? } ?>
				                    	</select>
			                		</td>

			                		<td class='right-td'>Harga Beli</td>
			                		<td> : </td>
			                		<td>
			                			<input type="text" class='amount_number' name="harga_beli"/>
			                		</td>
			                		
			                	</tr>
			                	<tr>

			                		<td>Qty</td>
			                		<td> : </td>
			                		<td>
			                			<input name='qty'>
			                		</td>

			                		<td class='right-td'>Jumlah Roll</td>
			                		<td> : </td>
			                		<td>
			                			<input type="text" name="jumlah_roll"/>
			                		</td>	
		                					                		
			                	</tr>

			                	<!-- <tr>

			                		
			                		
			                	</tr> -->

			                	<tr>

			                		<td>Total</td>
			                		<td> : </td>
			                		<td>
			                			<span style='font-size:1.5em;font-weight:bold' class='total'></span>
			                		</td>			                		

			                		<td colspan='3'>
			                			<button class='btn btn-xs green btn-add-list'><i class='fa fa-plus'></i> Tambah</button>
			                		</td>
			                	</tr>


			                </table>
			                </fieldset>

			                <table class='table table-striped table-bordered table-hover' id='rekap_barang_list'>
			                	<thead>
			                		<tr>
			                			<th>Nama Barang</th>
			                			<th>Satuan</th>
			                			<!-- <th>Gudang</th> -->
			                			<th>Qty</th>
			                			<th>Roll</th>
			                			<th>Harga</th>
			                			<th>Total</th>
			                			<th style='width:200px !important'>Action</th>
			                		</tr>
			                	</thead>
			                	<tbody>
			                	</tbody>
			                </table>

			                <table width="100%" id='rekap_harga'>
			                	<tr>
			                		<td>
			                			<table>
			                				<tr>
			                					<td>Subtotal</td>
							                		<td> : </td>
							                		<td>
							                			<span class='subtotal'>0</span>
							                		</td>
							                	</tr>
							                	<tr>
							                		<td>Diskon</td>
							                		<td> : </td>
							                		<td><input name='diskon' class='amount_number' value='0'/></td>
							                	</tr>
							                	<tr>
							                		<td>Jatuh Tempo</td>
							                		<td> : </td>
							                		<td><input readonly name='jatuh_tempo' class='date-picker' value="<?=date('d/m/Y');?>"/></td>
							                	</tr>
							                	<tr>
							                		<td>Keterangan</td>
							                		<td> : </td>
							                		<td><input name='keterangan'/></td>
							                	</tr>
							                </table>
			                		</td>
			                		<td>
			                			<div style='font-size:3em;font-weight:bold'>
			                				Rp. <span class='grand_total'></span>,-
			                			</div>
			                		</td>
		                		</tr>
		                	</table>
			                    
						</form>
					</div>
					<div>
						<input name='test' style='display:none'>
					</div>

					<div class="modal-footer">
						<button type="button" class="btn blue btn-edit-save">Save</button>
						<button type="button" class="btn default" data-dismiss="modal">Close</button>
					</div>
				</div>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
		</div>*/?>

		<div class="modal fade" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<!-- <div class="modal-dialog modal-full"> -->
				<div class="modal-content">
					<div class="modal-body">
						<form action="<?=base_url('transaction/pembelian_list_insert')?>" class="form-horizontal" id="form_add_data" method="post">
							<h3 class='block'> Pembelian Baru</h3>
							
							<div class="form-group">
			                    <label class="control-label col-md-3">Supplier<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<select class='input1 form-control supplier-input' style='font-weight:bold' name="supplier_id">
			                    		<?foreach ($this->supplier_list_aktif as $row) { ?>
			                    			<option value="<?=$row->id?>"><?=$row->nama;?></option>
			                    		<? } ?>
			                    	</select>
			                    </div>
			                </div>			                

			                <div class="form-group">
			                    <label class="control-label col-md-3">Gudang<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
	                    			<select style='font-weight:bold' class='form-control gudang-input' name="gudang_id">
			                    		<?foreach ($this->gudang_list_aktif as $row) { ?>
			                    			<option value="<?=$row->id?>"><?=$row->nama;?></option>
			                    		<? } ?>
			                    	</select>
			                    </div>
			                </div> 

			                <div class="form-group">
			                    <label class="control-label col-md-3">Tanggal<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
					                <input type="text" readonly class="form-control date-picker" value="<?=date('d/m/Y');?>" name="tanggal"/>
			                    </div>
			                </div> 	

			                <div class="form-group">
			                    <label class="control-label col-md-3">No Faktur<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                		<input type="text" class="form-control" name="no_faktur"/>
			                    </div>
			                </div>

			                <div class="form-group">
			                    <label class="control-label col-md-3">OCKH
			                    </label>
			                    <div class="col-md-6">
			                		<input type="text" class='form-control' name="ockh"/>
			                    </div>
			                </div>   


			                <div class="form-group">
			                    <label class="control-label col-md-3">Toko
			                    </label>
			                    <div class="col-md-6">
					                <select name="toko_id" class='form-control'>
			                    		<?foreach ($this->toko_list_aktif as $row) { ?>
			                    			<option value="<?=$row->id?>"><?=$row->nama;?></option>
			                    		<? } ?>
			                    	</select> 
			                    </div>
			                </div>
						</form>
					</div>

					<div class="modal-footer">
						<button type="button" class="btn blue btn-save">Save</button>
						<button type="button" class="btn default" data-dismiss="modal">Close</button>
					</div>
				</div>
				<!-- /.modal-content -->
			<!-- </div> -->
			<!-- /.modal-dialog -->
		</div>

		
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
							<i class="fa fa-plus"></i> Tambah </a>
						</div>
					</div>
					<div class="portlet-body">
						<!-- table-striped table-bordered  -->
						<table class="table table-hover table-striped table-bordered" id="general_table">
							<thead>
								<tr>
									<th scope="col" class='status_column'>
										Status Aktif
									</th>
									<th scope="col">
										Toko
									</th>
									<th scope="col">
										No Faktur
									</th>
									<th scope="col">
										Tanggal Pembelian
									</th>
									<!-- <th scope="col">
										Satuan
									</th> -->
									<th scope="col">
										Yard/KG
									</th>
									<th scope="col">
										Jml Roll
									</th>
									<th scope="col">
										Nama Barang
									</th>
									<th scope="col">
										Gudang
									</th>
									<th scope="col">
										Harga
									</th>
									<th scope="col">
										Total
									</th>
									<th scope="col">
										Supplier
									</th>
									<th scope="col">
										Status
									</th>
									<th scope="col" >
										Actions
									</th>
								</tr>
							</thead>
							<tbody>
								<?/*foreach ($barang_list as $row) { ?>
									<tr>
										<td>
											<span class='id' hidden="hidden"><?=$row->id;?></span>
											<span class='nama'><?=$row->nama;?></span> 
										</td>
										<td>
											<span class='nama_jual'><?=$row->nama_jual;?></span> 
										</td>
										<td>
											<span class='satuan' hidden='hidden'><?=$row->satuan_id;?></span>
											<?=$row->nama_satuan;?> 
										</td>
										<td>
											<span class='harga_jual'><?=number_format($row->harga_jual,'0','.','.');?></span> 
										</td>
										<td>
											<span class='harga_beli'><?=number_format($row->harga_beli,'0','.','.');?></span> 
										</td>
										<td>
											<a href='#portlet-config-edit' data-toggle='modal' class="btn-xs btn green btn-edit"><i class="fa fa-edit"></i> </a>
											<a class="btn-xs btn red btn-remove"><i class="fa fa-times"></i> </a>
										</td>
									</tr>
								<? } */?>
							</tbody>
						</table>
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
<script src="<?php echo base_url('assets_noondev/js/form-pembelian.js'); ?>" type="text/javascript"></script>



<script src="<?php echo base_url('assets/global/plugins/select2/select2.min.js'); ?>" type="text/javascript" ></script>
<script src="<?php echo base_url('assets_noondev/js/ui-extended-modals.js'); ?>"></script>
<script>
jQuery(document).ready(function() {
	Metronic.init(); // init metronic core components
	Layout.init(); // init current layout
	// TableAdvanced.init();
	FormNewPembelian.init();
	ModalsPembelianEdit.init();
	$('.barang-id, .warna-id').select2({
        allowClear: true
    });


	$("#general_table").DataTable({
   		"fnCreatedRow": function( nRow, aData, iDataIndex ) {
            var status = $('td:eq(11)', nRow).text().split('??');
            var id = status[0];
            var toko_id = status[1];            
            var gudang_id = status[2];
            var supplier_id = status[3];
            var tanggal = date_formatter($('td:eq(3)', nRow).html());
            // href='#portlet-config-edit' data-toggle='modal'
            // var button_edit = "<button class='btn-xs btn green btn-edit'><i class='fa fa-edit'></i> </button>";
            var url = "<?=rtrim(base64_encode('transaction/pembelian_list_detail'),'=');?>/"+id;
            var button_edit = "<a href='"+url+"' class='btn-xs btn green btn-edit'><i class='fa fa-edit'></i> </a>";
           	var action = "<span class='id' hidden='hidden'>"+id+"</span><span class='toko_id' hidden='hidden'>"+toko_id+"</span><span class='gudang_id' hidden='hidden'>"+gudang_id+"</span><span class='supplier_id' hidden='hidden'>"+supplier_id+"</span>"+button_edit+"<a class='btn-xs btn red btn-remove'><i class='fa fa-times'></i> </a>";
            // var qty = $('td:eq(4)', nRow).html();
            var harga = $('td:eq(8)', nRow).html();
            // var total = change_number_format(qty * harga);

            $('td:eq(0)', nRow).addClass('status_column');
            $('td:eq(1)', nRow).html('<span class="toko_id">'+$('td:eq(1)', nRow).text()+'</span>');
            $('td:eq(2)', nRow).html('<span class="no_faktur">'+$('td:eq(2)', nRow).text()+'</span>');
            $('td:eq(3)', nRow).html(tanggal);
            $('td:eq(8)', nRow).html('<span class="harga">'+change_number_format(harga)+'</span>');
            $('td:eq(9)', nRow).html('<span class="total">'+change_number_format($('td:eq(2)', nRow).text())+'</span>');
            // $(nRow).addClass('test_pink')

            // $('td:eq(4)', nRow).html('<span class="harga_beli">'+change_number_format($('td:eq(4)', nRow).text())+'</span>');
            $('td:eq(12)', nRow).html(action);
            
        },
        "bStateSave" :true,
		"bProcessing": true,
		"bServerSide": true,
		"sAjaxSource": baseurl + "transaction/data_pembelian"
	});

	$('.supplier-input, .gudang-input').click(function(){
		$('#form_add_data .supplier-input').removeClass('supplier-input');
	})


	// $('#form_add_data [name=no_faktur]').change(function(){
	// 	$('#form_add_data .gudang-input').removeClass('gudang-input');
	// 	var no_faktur = $(this).val();
	// 	var ini = $(this).closest('tr');
	// 	var data = {};
 //   		data['no_faktur'] = no_faktur;
 //   		var url = "transaction/check_faktur_pembelian";
		
	// 	ajax_data_sync(url,data).done(function(data_respond /* ,textStatus, jqXHR */){
 //   			if(data_respond > 0){
 //   				// alert(data_respond);
 //   				ini.find('.input-success').hide();
 //   				ini.find('.input-alert').show();
 //   				$('.no_faktur_status').html('false');
 //   			}else if (data_respond == 0) {
 //   				// alert('OK');
 //   				ini.find('.input-alert').hide();
 //   				ini.find('.input-success').show();
 //   				$('.no_faktur_status').html('true');
 //   			}
 //   		});
	// });
	
   	// $('#general_table').on('click', '.btn-edit', function(){
   	// 	var ini = $(this).closest('tr');
   	// 	$('#form_edit_data [name=id]').val($(this).closest('tr').find('.id').html());
   	// 	$('#form_edit_data [name=nama]').val($(this).closest('tr').find('.nama').html());
   	// 	$('#form_edit_data [name=nama_jual]').val($(this).closest('tr').find('.nama_jual').html());
   	// 	$('#form_edit_data [name=harga_beli]').val($(this).closest('tr').find('.harga_beli').html());
   	// 	$('#form_edit_data [name=harga_jual]').val($(this).closest('tr').find('.harga_jual').html());

   	// 	var data = {};
   	// 	data['id'] = ini.find('.id').html();
   	// 	var url = 'transaction/data_pembelian_list_edit';
   	// 	ajax_data_sync(url,data).done(function(data_respond /* ,textStatus, jqXHR */){
   	// 		$('#portlet-config-edit #rekap_barang_list tbody').html(data_respond);

   	// 	});

   	// });

   	$('.btn-edit-save').click(function(){
   		if( $('#form_edit_data [name=nama]').val() != ''){
   			$('#form_edit_data').submit();
   		}
   	});

   	$('#form-add-list [name=barang_id]').change(function(){
   		var barang_id = $('#form-add-list [name=barang_id]').val();
   		var harga = $("#form-add-list [name=harga] [value='"+barang_id+"']").text();
   		// alert(harga);
		$('#form-add-list [name=harga_beli]').val(change_number_format(harga));
   	});

   	$('#form-add-list [name=qty], #form-add-list [name=harga_beli]').change(function(){
   		var harga = $('#form-add-list [name=harga_beli]').val();
   		var qty = $('#form-add-list [name=qty]').val();

   		if (harga == '' || $.isNumeric(harga) == false) {
   			harga = 0;
   			$('#form-add-list [name=harga_beli]').val(harga);
   		};

   		if ($.isNumeric(qty) == false) {qty = 0;};
   		var total = reset_number_format(harga) * qty;
   		if (total > 0) {total = change_number_format(total);};
   		$('#form-add-list .total').html(total);

   	});

  //  	$('#portlet-config .btn-add-list').click(function(){
  //  		// alert($('#form-add-list [name=supplier_id]').val());
  //  		$('#form-add-list [name=barang_id]').focus()
  //  		event.preventDefault();
  //  		var isi_tbl = $('#rekap_barang_list').html()+"<tr>";
		// var barang_id = $('#form-add-list [name=barang_id] :selected').val();
		// var barang_nama = $('#form-add-list [name=barang_id] :selected').text();
		// var warna_id = $('#form-add-list [name=warna_id]').val();
  //  		var harga = $('#form-add-list [name=harga_beli]').val();
		// var qty = $('#form-add-list [name=qty]').val();
		// var total = $('#form-add-list .total').html();
		// var satuan_id = $('#form-add-list [name=satuan_id] :selected').val();
		// var gudang_id = $('#form-add-list [name=gudang_id] :selected').val();
		// var jumlah_roll = $('#form-add-list [name=jumlah_roll]').val();

		// if (barang_id != '' && warna_id != '' && harga != '' && qty != '' && satuan_id != '' && jumlah_roll != '') {
		// 	isi_tbl += "<td> <input name='barang_id[]' hidden='hidden' value='"+barang_id+"'>"+barang_nama;
		// 	isi_tbl += "<input name='warna_id' hidden='hidden' value='"+warna_id+"'>"+$('#form-add-list [name=warna_id]:selected').text()+"</td>";
		// 	isi_tbl += "<td> <input name='satuan_id[]' hidden='hidden' value='"+satuan_id+"'>"+$('#form-add-list [name=satuan_id] :selected').text()+"</td>";
		// 	// isi_tbl += "<td> <input name='gudang_id' hidden='hidden' value='"+gudang_id+"'>"+$('#form-add-list [name=gudang_id] :selected').text()+"</td>";
		// 	isi_tbl += "<td> <input name='qty[]' value='"+qty+"'></td>";
		// 	isi_tbl += "<td> <input name='jumlah_roll[]' value='"+jumlah_roll+"'></td>";
		// 	isi_tbl += "<td> <input name='harga_beli[]' class='amount_number' value='"+harga+"'></td>";
		// 	isi_tbl += "<td> <span class='total'>"+total+"</span></td>";
		// 	isi_tbl += "<td> <button type='button' class='btn btn-xs red btn-remove-list'><i class='fa fa-times'></i></button></td>";
		// 	isi_tbl += "</tr>";

		// 	$('#barang_id_select').focus().select();
		// 	$('#rekap_barang_list').html(isi_tbl);

		// 	var subtotal = reset_number_format($('#rekap_harga .subtotal').html());
			
		// 	// alert(subtotal);
		// 	subtotal = parseInt(subtotal) + parseInt(reset_number_format(total));
		// 	$('#rekap_harga .subtotal').html(change_number_format(subtotal));
		// 	var diskon = reset_number_format($('#form-add-list .diskon').val());
		// 	var grand_total = parseInt(subtotal) - parseInt(diskon);
		// 	// alert(grand_total);
		// 	$('#rekap_harga .grand_total').html(change_number_format(grand_total));


		// 	$('#form-add-list [name=barang_id]').val('');
		// 	$('#form-add-list [name=barang_id]').change();
		// 	$('#form-add-list [name=warna_id]').val('');
		// 	$('#form-add-list [name=warna_id]').change();
	 //   		$('#form-add-list [name=harga_beli]').val('');
		// 	$('#form-add-list [name=qty]').val('');
		// 	$('#form-add-list .total').html('');
		// 	// $('#form-add-list [name=satuan_id]').val('');
		// 	// $('#form-add-list [name=gudang_id]').val('');
		// 	$('#form-add-list [name=jumlah_roll]').val('');


		// 	setTimeout(function(){
				
		// 	},500);
		// }else{
		// 	notific8('ruby', "Mohon lengkapi formulir ..!!");
		// }
  //  	});

	$('#rekap_barang_list').on('click','.btn-remove-list',function(){
		$(this).closest('tr').remove();
	});

	$('#rekap_barang_list').on('change','[name="qty[]"], [name="harga_beli[]"]',function(){
		var qty = $('#rekap_barang_list [name="qty[]"]').val();
		var harga = reset_number_format($('#rekap_barang_list [name="harga_beli[]"]').val());

		// alert(qty);
		var total = qty * harga;
		total = change_number_format(total);
		$('#rekap_barang_list .total').html(total);

		var subtotal = 0;
		$('#rekap_barang_list .total').each(function(){
			subtotal += reset_number_format($(this).html());
		});

		$('#rekap_harga .subtotal').html(change_number_format(subtotal));
		var diskon = reset_number_format($('#rekap_harga .diskon').val());
		var grand_total = parseInt(subtotal) - parseInt(diskon);
		$('#rekap_harga .grand_total').html(change_number_format(grand_total));

	});

	$('#rekap_harga [name=diskon]').change(function(){
		var diskon = reset_number_format($(this).val());
		var subtotal = reset_number_format($('#rekap_harga .subtotal').html());
		var grand_total = subtotal - diskon;
		$('#rekap_harga .grand_total').html(change_number_format(grand_total));
	});

	// $('.btn-save').click(function(){
	// 	var data = {};
	// 	data['no_faktur'] = $('#form_add_data [name=no_faktur]').val();
	// 	data['tanggal'] = $('#form_add_data [name=no_faktur]').val();
	// 	$.each( data, function( key, value ) {
	// 	  if (value == '') {
	// 	  	notific8('ruby', 'Mohon isi data '+key);
	// 	  }else{
	// 	  	if ($('.no_faktur_status').html() == 'true') {
	// 	  		$('#form_add_data').submit();
	// 	  	}else{
	// 	  		notific8('ruby', 'No Faktur invalid.');
	// 	  	};
	// 	  };
	// 	});
	// });

	


});
</script>
