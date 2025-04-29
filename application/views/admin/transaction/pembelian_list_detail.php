<link href="<?=base_url('assets/global/plugins/bootstrap-modal/css/bootstrap-modal.css');?>" rel="stylesheet" type="text/css"/>
<?=link_tag('assets/global/plugins/bootstrap-datepicker/css/datepicker3.css'); ?>

<style type="text/css">
.subtotal-data{
	font-size: 1.2em;
}

.po-info-hide{
	display: none;
}

#qty-table-edit input{
	width: 60px;
	padding-left: 5px
}

#qty-table input{
	width: 70px;
	padding-left: 5px
}

#qty-table .nama_satuan, #qty-table .nama_packaging, #qty-table-edit .nama_satuan, #qty-table-edit .nama_packaging{
	text-align: center;
}

.yard-info{
	font-size: 1.5em;
}
</style>

<div class="page-content">
	<div class='container'>

		<?
			$pembelian_id = '';
			$supplier_id = '';
			$nama_supplier = '';
			$gudang_id = '';
			$nama_gudang = '';
			$no_faktur = '';
			$ockh_info = '';
			$tanggal = '';
			$ori_tanggal = '';
			$toko_id = '';
			$nama_toko = '';
			
			$jatuh_tempo = '';
			$ori_jatuh_tempo = '';
			$diskon = 0;
			$status = 0;
			$status_aktif = 0;
			$keterangan = '';

			$po_pembelian_batch_id = '';
			$po_number = '';

			foreach ($pembelian_data as $row) {
				$pembelian_id = $row->id;
				$supplier_id = $row->supplier_id;
				$nama_supplier = $row->nama_supplier;
				$gudang_id = $row->gudang_id;
				$nama_gudang = $row->nama_gudang;
				$no_faktur = $row->no_faktur;

				$tanggal = is_reverse_date($row->tanggal);
				$ori_tanggal = $row->tanggal;
				$toko_id = $row->toko_id;
				$nama_toko = $row->nama_toko;
				
				$jatuh_tempo = is_reverse_date($row->jatuh_tempo);
				$ori_jatuh_tempo = $row->jatuh_tempo;

				$diskon = $row->diskon;
				$status = $row->status;
				$status_aktif = $row->status_aktif;
				$keterangan = $row->keterangan;

				$po_pembelian_batch_id = $row->po_pembelian_batch_id;
				$po_number = $row->po_number;
				$ockh_info = $row->ockh_info;

			}

			$readonly = ''; $disabled = '';
			$uniq_blessing = true;
			
			$headers = apache_request_headers();
			foreach ($headers as $header => $value) {
				if ($header == "Host") {
				    if ($value != 'sistem.blessingtdj.com') {
				    	if (is_posisi_id() == 6) {
							$readonly = 'readonly';
							$disabled = 'disabled';
							$uniq_blessing = false;
						}
				    }else{
				    }
					# code...
				}
			}

			$ockh = $ockh_info;
			foreach ($pembelian_detail as $row) {
				// echo $ockh;
				$ockh = $row->ockh;
			}

		?>


		<div class="modal fade" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body">
						<form action="<?=base_url('transaction/pembelian_list_insert')?>" class="form-horizontal" id="form_add_data" method="post">
							<h3 class='block'> Pembelian Baru</h3>

							<div class="form-group">
			                    <label class="control-label col-md-3">PO
			                    </label>
			                    <div class="col-md-6">
			                		<select name='po_pembelian_batch_id' class='form-control' id="po_list">
			                			<option value=''>Pilih</option>
			                			<?foreach ($this->po_pembelian_batch_aktif as $row) {?>
			                				<option value="<?=$row->id?>"><?=$row->po_number?></option>
			                			<?}?>
			                		</select>

			                		<select id="po_list_copy" hidden>
			                			<option value=''>Pilih</option>
			                			<?foreach ($this->po_pembelian_batch_aktif as $row) {?>
			                				<option value="<?=$row->id?>"><?=$row->supplier_id?></option>
			                			<?}?>
			                		</select>
			                    </div>
			                </div>

							<div class="form-group">
			                    <label class="control-label col-md-3">Supplier<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<select class='input1 form-control supplier-input' style='font-weight:bold' name="supplier_id" id='supplier-id-add'>
			                    		<?foreach ($this->supplier_list_aktif as $row) { ?>
			                    			<option <?=($row->id==$supplier_id ? 'selected' : '');?> value="<?=$row->id?>"><?=$row->nama;?></option>
			                    		<? } ?>
			                    	</select>
			                    </div>
			                </div>

			                <div class="form-group">
			                    <label class="control-label col-md-3">Gudang<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
	                    			<select style='font-weight:bold' class='form-control gudang-input' name="gudang_id" id='gudang_add_data'>
	                    				<option value="">Pilih</option>
			                    		<?foreach ($this->gudang_list_aktif as $row) { ?>
			                    			<option <?=($row->status_default==1 ? 'selected' : '');?> value="<?=$row->id?>"><?=$row->nama;?></option>
			                    		<? } ?>
			                    	</select>
			                    </div>
			                </div> 

			                <div class="form-group">
			                    <label class="control-label col-md-3">No Faktur<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                		<input type="text" class="form-control" name="no_faktur" id="no-faktur" />
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
			                    <label class="control-label col-md-3">OCKH
			                    </label>
			                    <div class="col-md-6">
			                		<input type="text" class='form-control' name="ockh_info" id="ockh" />
			                    	<small>Terdaftar: <b><span class='ockh-history'></span></b></small><br/>
			                    	<small class='po-info' style=="display:none">PO link: <b><span class='po-link-number' style="color:red" ></span></b></small>
			                    </div>
			                </div> 	

			                <div class="form-group" hidden>
			                    <label class="control-label col-md-3">Toko
			                    </label>
			                    <div class="col-md-6">
					                <select name="toko_id" class='form-control'>
			                    		<?foreach ($this->toko_list_aktif as $row) { ?>
			                    			<option selected value="<?=$row->id?>"><?=$row->nama;?></option>
			                    		<? } ?>
			                    	</select> 
			                    </div>
			                </div>
						</form>
					</div>

					<div class="modal-footer">
						<button type="button" class="btn blue btn-active btn-trigger btn-save">Save</button>
						<button type="button" class="btn default  btn-active" data-dismiss="modal">Close</button>
					</div>
				</div>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
		</div>

		<div class="modal fade" id="portlet-config-edit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body">
						<form action="<?=base_url('transaction/pembelian_list_update')?>" class="form-horizontal" id="form_edit_data" method="post">
							<h3 class='block'> Edit Data</h3>
							
							<div class="form-group">
			                    <label class="control-label col-md-3">PO Pembelian
			                    </label>
			                    <div class="col-md-6">
			                		<select name='po_pembelian_batch_id' class='form-control' id="po_list_edit">
			                			<option value=''>Pilih</option>
			                			<?foreach ($this->po_pembelian_batch_aktif as $row) {?>
			                				<option <?=($po_pembelian_batch_id == $row->id ? 'selected' : '');?> value="<?=$row->id?>"><?=$row->po_number?></option>
			                			<?}?>
			                		</select>
			                    </div>
			                </div>

							<div class="form-group">
			                    <label class="control-label col-md-3">Supplier<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                		<input type="text" name="pembelian_id" value='<?=$pembelian_id;?>' hidden/>
			                    	<select class='input1 form-control supplier-input-edit' style='font-weight:bold' name="supplier_id" id="supplier-id-edit">
			                    		<?foreach ($this->supplier_list_aktif as $row) { 
			                    			if ($row->tipe_supplier == 1) {?>
				                    			<option <?if ($supplier_id == $row->id) {echo 'selected';}?> value="<?=$row->id?>"><?=$row->nama;?></option>
			                    			<?}
			                    		} ?>
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
			                    			<option <?if ($gudang_id == $row->id) {echo 'selected';}?> value="<?=$row->id?>"><?=$row->nama;?></option>
			                    		<? } ?>
			                    	</select>
			                    </div>
			                </div>

			                <div class="form-group">
			                    <label class="control-label col-md-3">No Faktur<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                		<input type="text" class="form-control" name="no_faktur" value="<?=$no_faktur;?>"/>
			                    </div>
			                </div> 

			                <div class="form-group">
			                    <label class="control-label col-md-3">Tanggal<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
					                <input type="text" readonly class="form-control date-picker" value="<?=$tanggal;?>" name="tanggal"/>
			                    </div>
			                </div>

			                <div class="form-group">
			                    <label class="control-label col-md-3">OCKH
			                    </label>
			                    <div class="col-md-6">
			                		<input type="text" class='form-control' name="ockh_info" value="<?=$ockh_info?>" id="ockh_edit" />
			                    </div>
			                </div>


			                <div class="form-group" hidden>
			                    <label class="control-label col-md-3">Toko
			                    </label>
			                    <div class="col-md-6">
					                <select name="toko_id" class='form-control'>
			                    		<?foreach ($this->toko_list_aktif as $row) { ?>
			                    			<option <?if ($toko_id == $row->id) {echo 'selected';}?> value="<?=$row->id?>"><?=$row->nama;?></option>
			                    		<? } ?>
			                    	</select> 
			                    </div>
			                </div>
						</form>
					</div>

					<div class="modal-footer">
						<button type="button" class="btn blue btn-active btn-trigger btn-edit-save">Save</button>
						<button type="button" class="btn default btn-active" data-dismiss="modal">Close</button>
					</div>
				</div>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
		</div>

		<div class="modal fade" id="portlet-config-detail" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body">
						<form action="<?=base_url('transaction/pembelian_list_detail_insert')?>" class="form-horizontal" id="form_add_barang" method="post">
							<h3 class='block'> Tambah Barang</h3>
							
	                    	<input name='pembelian_detail_id' hidden>
	                    	<input name='po_pembelian_batch_id' value="<?=$po_pembelian_batch_id;?>" hidden>
	                    	<input name='pembelian_id' value='<?=$pembelian_id;?>' hidden>

	                    	<?if ($po_pembelian_batch_id != '') {?>
	                    		<div class="form-group">
				                    <label class="control-label col-md-3">PO Barang<span class="required">
				                    * </span>
				                    </label>
				                    <div class="col-md-6">
				                    	<select name="barang_id" class='form-control input1' id='barang_id_select'>
			                				<option value=''>Pilih</option>
			                				<?foreach ($barang_list as $row) { 
			                					$dt_po[$row->barang_id][$row->warna_id] = $row->tipe_barang;
			                					?>
				                    			<option value="<?=$row->barang_id?>??<?=$row->warna_id?>??<?=$row->tipe_barang;?>"><?=$row->nama;?> <?=$row->nama_warna;?></option>
				                    		<? } ?>
				                    	</select>
				                    	<select name='data_barang' <?=(is_posisi_id() != 1 ? 'hidden' : '');?> >
				                    		<?foreach ($barang_list as $row) { ?>
				                    			<option value="<?=$row->barang_id?>??<?=$row->warna_id?>??<?=$row->tipe_barang;?>">satuan??<?=$row->harga_beli;?></option>
				                    		<? } ?>
				                    	</select>
				                    </div>
				                </div>	
	                    	<?}else{?>
								<div class="form-group">
				                    <label class="control-label col-md-3">Kode Barang<span class="required">
				                    * </span>
				                    </label>
				                    <div class="col-md-6">
				                    	<select name="barang_id" class='form-control input1' id='barang_id_select'>
			                				<option value=''>Pilih</option>
			                				<?foreach ($this->barang_list_aktif_beli as $row) { ?>
				                    			<option value="<?=$row->id?>"><?=$row->nama;?></option>
				                    		<? } ?>
				                    	</select>
				                    	<select name='data_barang' hidden>
				                    		<?foreach ($this->barang_list_aktif_beli as $row) { ?>
				                    			<option value="<?=$row->id?>"><?=$row->nama_satuan;?>??<?=$row->harga_beli;?>??<?=$row->tipe_qty?></option>
				                    		<? } ?>
				                    	</select>
				                    </div>
				                </div>			                
				                
				                <div class="form-group">
				                    <label class="control-label col-md-3">Warna<span class="required">
				                    * </span>
				                    </label>
				                    <div class="col-md-6">
		                    			<select name="warna_id" class='form-control' id='warna_id_select'>
			                				<option value=''>Pilihan..</option>
				                    		<?foreach ($this->warna_list_aktif as $row) { ?>
				                    			<option value="<?=$row->id?>"><?=$row->warna_beli;?></option>
				                    		<? } ?>
				                    	</select>
				                    </div>
				                </div> 
	                    	<?}?>

	                    	<div class="form-group">
			                    <label class="control-label col-md-3">OCKH/No Order
			                    </label>
			                    <div class="col-md-6">
			                		<input name="ockh_info" value="<?=$ockh_info?>" hidden />
			                		<input type="text" class='form-control' name="ockh" value="<?=($ockh == '0' ? '' : $ockh);?>"/>
			                    </div>
			                </div>

			                <div class="form-group">
			                    <label class="control-label col-md-3">Harga Beli<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
		                			<input type="text" class='form-control' name="harga_beli"/>
		                			<input name='rekap_qty' <?=(is_posisi_id() != 1 ? 'hidden' : '' );?> hidden>
			                    </div>
			                </div>

			                <div class="form-group qty-tipe-2" hidden>
			                    <label class="control-label col-md-3">QTY
			                    </label>
			                    <div class="col-md-6">
			                		<input type="text" class='form-control qty-2' name="qty-2" />
			                    </div>
			                </div>

			                <div class="form-group roll-tipe-2" hidden>
			                    <label class="control-label col-md-3">ROLL
			                    </label>
			                    <div class="col-md-6">
			                		<input type="text" class='form-control roll-2' name="roll-2" />
			                    </div>
			                </div>

						</form>
					</div>

					<div class="modal-footer">
						<button type="button" class="btn blue btn-active btn-trigger btn-add-qty">Add Qty</button>
						<button type="button" style='display:none' id='btn-qty-2' class="btn blue btn-active btn-trigger ">Save</button>
						<button type="button" class="btn default btn-active" data-dismiss="modal">Close</button>
					</div>
				</div>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
		</div>

		<div class="modal fade" id="portlet-config-qty" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog ">
				<div class="modal-content">
					<div class="modal-body">
						<table width='100%'>
							<tr>
								<td>
									<table id='qty-table'>
										<tr>
											<th class='nama_satuan'>Yard</th>
											<th class='nama_packaging'>Roll</th>
											<th style='text-align:center'>Subtotal</th>
											<th></th>
										</tr>
										<tr>
											<td><input name='qty' class='input1'></td>
											<td><input name='jumlah_roll'> = </td>
											<td><input name='subtotal' tabindex='-1'></td>
											<td><button tabindex='-1' class='btn btn-xs blue btn-add-qty-row'><i class='fa fa-plus'></i></button></td>
										</tr>
										<tr>
											<td><input name='qty'></td>
											<td><input name='jumlah_roll'> = </td>
											<td><input name='subtotal' tabindex='-1'></td>
											<td></td>
										</tr>
										<tr>
											<td><input name='qty'></td>
											<td><input name='jumlah_roll'> = </td>
											<td><input name='subtotal' tabindex='-1'></td>
											<td></td>
										</tr>
										<tr>
											<td><input name='qty'></td>
											<td><input name='jumlah_roll'> = </td>
											<td><input name='subtotal' tabindex='-1'></td>
											<td></td>
										</tr>
										<tr>
											<td><input name='qty'></td>
											<td><input name='jumlah_roll'> = </td>
											<td><input name='subtotal' tabindex='-1'></td>
											<td></td>
										</tr>
									</table>
								</td>
								<td style='vertical-align:top'>
									<div class='yard-info'>
										TOTAL QTY: <span class='yard_total' >0</span> Yard <br/>
										TOTAL ROLL: <span class='jumlah_roll_total' >0</span> Roll
									</div>
								</td>
							</tr>
						</table>
									
						

					</div>

					<div class="modal-footer">
						<button type="button" disabled class="btn blue btn-active btn-trigger btn-brg-save">Save</button>
						<button type="button" class="btn btn-active default" data-dismiss="modal">Close</button>
					</div>
				</div>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
		</div>

		<div class="modal fade" id="portlet-config-detail-edit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body">
						<form action="<?=base_url('transaction/pembelian_list_detail_insert')?>" class="form-horizontal" id="form_edit_barang" method="post">
							<h3 class='block'> Edit Barang</h3>
							
	                    	<input name='pembelian_detail_id' <?=(is_posisi_id() != 1 ? 'hidden' : '' );?> >
	                    	<input name='po_pembelian_batch_id' value="<?=$po_pembelian_batch_id;?>" hidden>
	                    	<input name='pembelian_id' value='<?=$pembelian_id;?>' hidden>

	                    	<?if ($po_pembelian_batch_id != '') {?>
	                    		<div class="form-group">
				                    <label class="control-label col-md-3">PO Barang<span class="required">
				                    * </span>
				                    </label>
				                    <div class="col-md-6">
				                    	<select name="barang_id" class='form-control input1' id='barang_id_select_edit'>
			                				<option value=''>Pilih</option>
			                				<?foreach ($barang_list as $row) { ?>
				                    			<option value="<?=$row->barang_id?>??<?=$row->warna_id?>"><?=$row->nama;?> <?=$row->nama_warna;?></option>
				                    		<? } ?>
				                    	</select>
				                    	<select name='data_barang' hidden>
				                    		<?foreach ($barang_list as $row) { ?>
				                    			<option value="<?=$row->barang_id?>??<?=$row->warna_id?>">satuan??<?=$row->harga_beli;?></option>
				                    		<? } ?>
				                    	</select>
				                    </div>
				                </div>	
	                    	<?}else{?>
								<div class="form-group">
				                    <label class="control-label col-md-3">Kode Barang<span class="required">
				                    * </span>
				                    </label>
				                    <div class="col-md-6">
				                    	<select name="barang_id" class='form-control input1' id='barang_id_select_edit'>
			                				<option value=''>Pilih</option>
			                				<?foreach ($this->barang_list_aktif_beli as $row) { ?>
				                    			<option value="<?=$row->id?>"><?=$row->nama;?></option>
				                    		<? } ?>
				                    	</select>
				                    	<select name='data_barang' hidden>
				                    		<?foreach ($this->barang_list_aktif_beli as $row) { ?>
				                    			<option value="<?=$row->id?>"><?=$row->nama_satuan;?>??<?=$row->harga_beli;?>??<?=$row->tipe_qty?></option>
				                    		<? } ?>
				                    	</select>
				                    </div>
				                </div>			                
				                
				                <div class="form-group">
				                    <label class="control-label col-md-3">Warna<span class="required">
				                    * </span>
				                    </label>
				                    <div class="col-md-6">
		                    			<select name="warna_id" class='form-control' id='warna_id_select_edit'>
			                				<option value=''>Pilihan..</option>
				                    		<?foreach ($this->warna_list_aktif as $row) { ?>
				                    			<option value="<?=$row->id?>"><?=$row->warna_beli;?></option>
				                    		<? } ?>
				                    	</select>
				                    </div>
				                </div> 
	                    	<?}?>
			                <div class="form-group">
			                    <label class="control-label col-md-3">OCKH/No Order
			                    </label>
			                    <div class="col-md-6">
			                		<input name="ockh_info" value="<?=$ockh_info?>" hidden />
			                		<input type="text" class='form-control' name="ockh" value="<?=$ockh?>"/>
			                    </div>
			                </div>

			                <div class="form-group">
			                    <label class="control-label col-md-3">Harga Beli<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
		                			<input type="text" class='form-control ' name="harga_beli"/>
		                			<input name='rekap_qty' <?=(is_posisi_id() != 1 ? 'hidden' : '' );?>  >
			                    </div>
			                </div>

			                <div class="form-group qty-tipe-2-edit" hidden>
			                    <label class="control-label col-md-3">QTY
			                    </label>
			                    <div class="col-md-6">
			                		<input type="text" class='form-control qty-2-edit' name="qty-2" />
			                    </div>
			                </div>

			                <div class="form-group roll-tipe-2-edit" hidden>
			                    <label class="control-label col-md-3">ROLL
			                    </label>
			                    <div class="col-md-6">
			                		<input type="text" class='form-control roll-2-edit' name="roll-2" />
			                    </div>
			                </div>

						</form>
					</div>

					<div class="modal-footer">
						<a href='#portlet-config-qty-edit' data-toggle='modal' class="btn blue btn-active btn-trigger btn-edit-form-qty">Edit Qty</a>
						<button type="button" style='display:none' id='btn-qty-2-edit' class="btn blue btn-active btn-trigger ">Save</button>
						<button type="button" class="btn default btn-active" data-dismiss="modal">Close</button>
					</div>
				</div>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
		</div>

		<div class="modal fade" id="portlet-config-qty-edit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body">
						<div class='col-md-6 col-xs-12' style='height:400px;overflow:auto'>
							<table id='qty-table-edit'>
								<thead>
									<tr>
										<th>No</th>
										<th class='nama_satuan'>Yard</th>
										<th class='nama_packaging'>Roll</th>
										<th style='text-align:center'>Subtotal</th>
										<th></th>
									</tr>
								</thead>
								<tbody>

								</tbody>
							</table>
						</div>
						<div class='yard-info' style='float:right;position:relative; font-size:2em'>
							TOTAL : <span class='yard_total' >0</span> <span class='satuan-total-edit'></span> <br/>
							TOTAL ROLL : <span class='jumlah_roll_total' >0</span>
						</div> 
						<span class='total_roll' hidden></span>
					</div>

					<div class="modal-footer">
						<button type="button" class="btn blue btn-brg-edit-save">Save</button>
						<button type="button" class="btn default" data-dismiss="modal">Close</button>
					</div>
				</div>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
		</div>

		<div class="modal fade" id="portlet-config-faktur" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body">
						<form action="<?=base_url().is_setting_link('transaction/pembelian_list_detail');?>" class="form-horizontal" id="form_search_faktur" method="post">
							<h3 class='block'> Cari Faktur</h3>
							
							<div class="form-group">
			                    <label class="control-label col-md-3">No Faktur<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
									<input type="hidden" name='pembelian_id' id="search_no_faktur" class="form-control select2">
			                    </div>
			                </div>	
		                </form>		                
					</div>

					<div class="modal-footer">
						<button type="button" class="btn blue btn-search-faktur">GO!</button>
						<button type="button" class="btn default" data-dismiss="modal">Close</button>
					</div>
				</div>
				<!-- /.modal-content -->
			</div>
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
							<?//if ($uniq_blessing == true ) { ?>
								<a href="#portlet-config" data-toggle='modal' class="btn btn-default btn-sm btn-form-add">
								<i class="fa fa-plus"></i> Pembelian Baru </a>
							<?//}?>
							<a href="#portlet-config-faktur" data-toggle='modal' class="btn btn-default btn-sm ">
							<i class="fa fa-search"></i> Cari Faktur </a>
						</div>
					</div>
					<div class="portlet-body">
						<table width='100%'>
							<tr>
								<td>
									<table>
										<?if ($pembelian_id != '') { ?>
											<tr>
												<td colspan='3'>
													<button href="#portlet-config-edit" data-toggle='modal' class='btn btn-xs '><i class='fa fa-edit'></i> edit</button>
												</td>
											</tr>
										<?}?>
										<tr>
								    		<td>No Faktur</td>
								    		<td class='padding-rl-5'> : </td>
								    		<td class='td-isi-bold'>
								    			<?=$no_faktur;?>
								    		</td>
								    	</tr>
								    	<tr>
								    		<td>PO Number</td>
								    		<td class='padding-rl-5'> : </td>
								    		<td  class='td-isi-bold'>
								    			<?=$po_number;?></td>
								    	</tr>
								    	<tr>
								    		<td>OCKH</td>
								    		<td class='padding-rl-5'> : </td>
								    		<td  class='td-isi-bold'>
								    			<?=$ockh_info;?></td>
								    	</tr>
								    	<tr hidden>
								    		<td>OCKH</td>
								    		<td class='padding-rl-5'> : </td>
								    		<td  class='td-isi-bold'>
								    			<?=$ockh;?></td>
								    	</tr>
								    	<tr>
								    		<td>Tanggal</td>
								    		<td class='padding-rl-5'> : </td>
								    		<td class='td-isi-bold'><?=$tanggal;?></td>
								    	</tr>
								    	<tr>
								    		<td>Toko</td>
								    		<td class='padding-rl-5'> : </td>
								    		<td class='td-isi-bold'>
								    			<?=$nama_toko;?>
								    		</td>
								    	</tr>
								    	<tr>
									    	<td>Gudang</td>
								    		<td class='padding-rl-5'> : </td>
								    		<td class='td-isi-bold'>
								    			<?=$nama_gudang;?>
								            </td>
							            </tr>
								    	<tr>
								    		<td>Supplier</td>
								    		<td class='padding-rl-5'> : </td>
								    		<td class='td-isi-bold'>
								    			<?=$nama_supplier;?>
								    		</td>
								    	</tr>					    	
								    </table>
								</td>
								<td >
									<?if ($po_pembelian_batch_id != '' || $ockh_detail != '' ) {
										if ($barang_id_detail != '') {?>
											<div style='position:relative; width:350px; padding:5px; height:180px; overflow:auto; float:right; border:1px solid #ddd'>
												<b><?=($po_pembelian_batch_id != '' ? 'PO' : 'OCKH '.$ockh_detail) ?></b>
												<table>
												<?$idx=0; $total_qty = 0; $po_qty = 0; $locked_date = ''; $locked_by = ''; $po_pembelian_warna_id = '';
												$total_list = count($kartu_po);
												$qty_2019 = 0;
												foreach ($kartu_po as $row) {
													$total_qty += $row->qty_beli; $garis='';
													if ($idx == 0 && $po_pembelian_batch_id != '') {
														$po_pembelian_warna_id = $row->po_pembelian_warna_id;
														$po_qty = $row->po_qty;
														$locked_by = $row->locked_by;
														if (isset($row->qty_2019)) { $total_qty += $row->qty_2019; $qty_2019 = $row->qty_2019;}
													}
													$idx++;
												}
												?>

												<?if ($po_pembelian_batch_id != '') {?>
													<tr>
														<td style='border-bottom:1px solid #ddd' colspan='3' class='text-right'>PO QTY </td>
														<td style='border-bottom:1px solid #ddd' class='padding-rl-5'> : </td>
														<td style='border-bottom:1px solid #ddd; font-size:1.2em' class='text-right'><b><?=number_format($row->po_qty,'0',',','.');?></b></td>
													</tr>
												<?}?>
												<?if ($qty_2019 > 0 ) { ?>
													<tr>
														<td style='border-bottom:1px solid #ddd' colspan='3' class='text-right'>Barang Masuk 2019 </td>
														<td style='border-bottom:1px solid #ddd' class='padding-rl-5'> : </td>
														<td style='border-bottom:1px solid #ddd; font-size:1.2em' class='text-right'><b><?=$qty_2019;?></b></td>
													</tr>
												<?}?>
												<tr>
													<td colspan='3' class='text-right'> TOTAL MASUK </td>
													<td class='padding-rl-5'> : </td>
													<td class='text-right'><b style='font-size:1.2em'><?=number_format($total_qty)?></b></td>
												</tr>
												<?if ($po_pembelian_batch_id != '') {
													$sisa_po = $po_qty - $total_qty;
													?>
													<tr>
														<td colspan='3' class='text-right' style='border-bottom:1px solid #ddd' > SISA PO </td>
														<td class='padding-rl-5' style='border-bottom:1px solid #ddd' > : </td>
														<td class='text-right' style='border-bottom:1px solid #ddd' ><b style='font-size:1.2em'><?=number_format($sisa_po);?></b></td>
													</tr>
												<?}?>

												<?$idx=0;foreach ($kartu_po as $row) {
													if (($idx+1) % 5 == 0 && ($idx +1 ) != $total_list ) {
														$garis = 'border-bottom:1px solid #ddd';
													}else{$garis='';}?>
												
													<tr style="<?=($pembelian_id == $row->pembelian_id ? 'background:lightblue' : '' )?>; <?=$garis?>" >
														<td style="; <?=$garis?>" class='padding-rl-5 text-right'><?=$total_list-$idx;?>. </td>
														<td style="; <?=$garis?>"><?=is_reverse_date($row->tanggal_beli)?></td>
														<td style="; <?=$garis?>" class='padding-rl-5 text-right'>[<?=$row->no_faktur?>]</td>
														<td style="; <?=$garis?>"> : </td>
														<td style="; <?=$garis?>" class='text-right'><b><?=number_format((float)$row->qty_beli)?></b></td>
													</tr>
												<?$idx++;}?>
												
												<?if ( $po_pembelian_batch_id != '' ){?>
													<tr style="border-top:1px solid #ddd">
														<td colspan='3' class='text-right'> MASUK % </td>
														<td class='padding-rl-5'> : </td>
														<td class='text-right'><b style='font-size:1.2em'>
															<?=number_format(($total_qty/$po_qty * 100),'2')?>%</b>
														</td>
													</tr>
												<?}?>

												</table>

												<?if ($po_pembelian_batch_id != '') {?>
													<input hidden id="po_pembelian_warna_id" value='<?=$po_pembelian_warna_id;?>' style="width:50px;">
													<b class="btn-lock-open <?=($locked_by != '' ? '' : 'po-info-hide');?>" style="font-size:1.2em;position:absolute; bottom:5px; right:5px;">PO <br/> tuntas</b>
													<button class="btn btn-md <?=(($total_qty/$po_qty * 100) > 80 ? 'yellow-gold' : '');?> btn-lock <?=($locked_by != '' ? 'po-info-hide' : '');?>" style='position:absolute; bottom:5px; right:5px;'><i class="fa fa-locked"></i>FINISH</button>
												<?}?>
												<!-- po-info-hide -->
											</div>
										<?}?>
									<?}?>
								</td>
							</tr>
						</table>

					    <hr/>
						<!-- table-striped table-bordered  -->
						<table class="table table-hover table-striped table-bordered" id="general_table">
							<thead>
								<tr>
									<th scope="col">
										No
									</th>
									<th scope="col">
										Nama Barang
										<?if ($pembelian_id != '') { ?>
											<a href="#portlet-config-detail" data-toggle='modal' class="btn btn-xs blue btn-brg-add">
											<i class="fa fa-plus"></i> </a>
										<?}?>
									</th>
									<!-- <th scope="col">
										OCKH/No Order
									</th> -->
									<th scope="col">
										Jml
									</th>
									<th scope="col">
										Roll
									</th>
									<th scope="col">
										Harga
									</th>
									<th scope="col">
										Total Harga
									</th>
									<th scope="col">
										Action
									</th>
								</tr>
							</thead>
							<tbody>
								<?
	                    	$dt_po = array();
								$idx =1; $g_total = 0;
								$qty_total = 0; $roll_total = 0; $barang_id_last = ''; $harga_beli_last ='';
								foreach ($pembelian_detail as $row) { 
									$data_qty = explode('--', $row->data_qty);

									?>
									<tr>
										<td>
											<span class='id' hidden="hidden"><?=$row->id;?></span>
											<?=$idx;?> 
										</td>
										<td>
											<span class='nama_jual'><?=$row->nama_barang;?> <?=$row->nama_warna;?></span> 
										</td>
										<!-- <td>
											<?=$row->ockh;?>
										</td> -->
										<td>
											<span class='qty'><?=str_replace('.00', '', $row->qty);?></span> 
											<?=$row->nama_satuan;?>
										</td>
										<td>
											<span class='jumlah_roll' <?=($row->tipe_qty == 3 ? 'hidden' : '')?> ><?=$row->jumlah_roll;?></span>
										</td>
										<td>
											<span class='harga_beli'><?=str_replace(',00','',number_format($row->harga_beli,'2',',','.'));?></span> 
										</td>
										<td>
											<?$subtotal = $row->qty * $row->harga_beli;
											$g_total += $subtotal;
											$qty_total += $row->qty;
											$roll_total += ($row->tipe_qty != 3 ? $row->jumlah_roll : 0);
											?>
											<span <?=$readonly;?> class='subtotal'><?=number_format($subtotal,'0','.','.');?></span> 
										</td>
										<td>
											<span class='id' hidden><?=$row->id;?></span>
											<span class='barang_id' <?=(is_posisi_id() != 1 ? 'hidden' : '');?> hidden ><?=$row->barang_id;?></span>
											<?$tipe_barang_set = '';
											if($po_pembelian_batch_id != '' && $po_pembelian_batch_id != 0 && isset($dt_po[$row->barang_id][$row->warna_id])){
												$tipe_barang_set = $dt_po[$row->barang_id][$row->warna_id];
											}?>
											<span class='tipe_barang' <?=(is_posisi_id() != 1 ? 'hidden' : '');?> hidden ><?=$tipe_barang_set;?></span>
											<span class='warna_id' <?=(is_posisi_id() != 1 ? 'hidden' : '');?> hidden ><?=$row->warna_id;?></span>
											<span class='data_qty'  <?=(is_posisi_id() != 1 ? 'hidden' : '');?> hidden ><?=$row->data_qty;?></span>
											<a href='#portlet-config-detail-edit' data-toggle='modal' class="btn-xs btn green btn-qty-edit"><i class="fa fa-edit"></i> </a>
											<a class="btn-xs btn red  btn-detail-remove"><i class="fa fa-times"></i> </a>
											<!-- <a href='#portlet-config-edit' data-toggle='modal' class="btn-xs btn green btn-edit"><i class="fa fa-edit"></i> </a> -->
										</td>
										<?
											$barang_id_last = $row->barang_id;
											$harga_beli_last = $row->harga_beli;
										?>
									</tr>
								<? $idx++;} ?>
								<tr class='subtotal-data'>
									<td colspan='2' class='text-right'><b>TOTAL</b></td>
									<td class='text-left'><b><?=str_replace('.00', '',$qty_total);?></b></td>
									<td class='text-left'><b><?=$roll_total;?></b></td>
									<td class='text-right'><b>TOTAL</b></td>
									<td><b class='total'><?=number_format($g_total,'0',',','.');?> </b> </td>
									<td class='hidden-print'></td>
								</tr>
							</tbody>
						</table>

						<table style='width:100%'>
							<tr>
								<td>
									<table>
										<tr>	
											<td>Subtotal</td>
											<td class='padding-rl-5'> : </td>
								    		<td class='td-isi-bold'><span class='total'><?=number_format($g_total,'0',',','.');?></span> </td>
										</tr>
										<tr>
											<td>Diskon</td>
											<td class='padding-rl-5'> : </td>
								    		<td class='td-isi-bold'><input <?=$readonly;?> <?if ($pembelian_id =='') {?>readonly<?}?> name='diskon' class='amount_number padding-rl-5 diskon' value="<?=number_format($diskon,'0',',','.');?>"></td>
										</tr>
										<tr>
											<td>Jatuh Tempo</td>
											<td class='padding-rl-5'> : </td>
								    		<td class='td-isi-bold'>
								    			
								    			<?$style='';
								    			$diff = strtotime($ori_jatuh_tempo) - strtotime($ori_tanggal);
								    			$diff = $diff/(60*60*24);
								    			// echo $diff;
								    			if ($diff < 0) {
								    				$style = 'color:red';
								    			}?>
								    			<input name='jatuh_tempo'  <?=$readonly;?> <?if ($pembelian_id =='') {?>readonly<?}?> class="<?if ($pembelian_id !='' && $uniq_blessing == true ) {?>date-picker<?}?> padding-rl-5 jatuh_tempo" style='<?=$style;?>' value='<?=$jatuh_tempo;?>'></td>
										</tr>
										<tr>
											<td>Keterangan</td>
											<td class='padding-rl-5'> : </td>
								    		<td class='td-isi-bold'><input <?=$readonly;?> <?if ($pembelian_id =='' && $uniq_blessing == true) {?>readonly<?}?> class='padding-rl-5 keterangan' name='keterangan' value="<?=$keterangan;?>"></td>
										</tr>
									</table>
								</td>
								<td style='vertical-align:top;font-size:4em;' class='text-right'>
									<b>Rp <span class='g_total' style=''><?=number_format($g_total - $diskon,'0',',','.');?></span></b>
								</td>
							</tr>

						</table>
						<hr/>
						<div>
			                <a class='btn btn-lg blue hidden-print'><i class='fa fa-print'></i> Print </a>

						</div>
					</div>
					
				</div>
			</div>
		</div>
	</div>			
</div>

<script src="<?php echo base_url('assets/global/plugins/bootbox/bootbox.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/bootstrap-modal/js/bootstrap-modalmanager.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/bootstrap-modal/js/bootstrap-modal.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/jquery-validation/js/jquery.validate.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets_noondev/js/form-pembelian.js'); ?>" type="text/javascript"></script>

<script src="<?php echo base_url('assets/global/plugins/select2/select2.min.js'); ?>" type="text/javascript" ></script>

<script>
jQuery(document).ready(function() {

	FormNewPembelian.init();
	FormEditPembelian.init();
	FormNewPembelianDetail.init();

	$('#barang_id_select,#warna_id_select, #po_list, #po_list_edit, #barang_id_select_edit, #warna_id_select_edit, #gudang_add_data').select2({
        allowClear: true
    });

    <?if ($pembelian_id != '') { ?>
    	var map = {220: false};
		$(document).keydown(function(e) {
			if (e.keyCode == 220) {
	        	// alert('ya');
	            $('#portlet-config-detail').modal('toggle');
	            cek_last_input();
	        }
		}).keyup(function(e) {
		    if (e.keyCode in map) {
		        map[e.keyCode] = false;
		    }
		});
    <?}?>

    <?if ($pembelian_id != '' && count($pembelian_detail) == 0) {?>
    	$("#portlet-config-detail").modal('toggle');
    	setTimeout(function(){
	    	$("#barang_id_select").select2('open');
    	},2500);
    <?}?>

//==============================filter po==========================================
	
	$(".btn-form-add").click(function(){
		setTimeout(function(){
			$('#po_list').select2("open");
		},600)
	});


	$('#gudang_add_data').change(function(){
		$("#no-faktur").focus();
	});


//==============================brg-add===============================================

    $('.btn-brg-add').click(function(){
    	// var select2 = $(this).data('select2');
    	cek_last_input();
    });

    var map = {13: false, 72:false};
	$("#form_add_barang").keydown(function(e) {
	    if (e.keyCode in map) {
	        map[e.keyCode] = true;
	        if (map[13] || map[72] ) {
	        	// alert(idx);
	            e.preventDefault();
	            $('.btn-add-qty').click();
	        }
	    }
	}).keyup(function(e) {
	    if (e.keyCode in map) {
	        map[e.keyCode] = false;
	    }
	});

	$('#barang_id_select').change(function(){
	    <?if ($po_pembelian_batch_id != '' && $po_pembelian_batch_id != 0){?>
	    	var barang_id = $('#barang_id_select').val();
	   		var get_brg = $("#form_add_barang [name=data_barang] [value='"+barang_id+"']").text();
	   		var data_brg = get_brg.split('??');
	   		<?if(is_posisi_id() == 1) {?>
		   		alert(data_brg[1]);
	   		<?}?>

	   		// alert('str:'+data_brg[1]);
	   		// alert('To str:'+data_brg[1].toString());
			$('#form_add_barang [name=harga_beli]').val(change_number_format2(data_brg[1]));

	    	var data = {};
	    	data['barang_id'] = barang_id;
	    	data['po_pembelian_batch_id'] = "<?=$po_pembelian_batch_id?>";
	    	var url = "transaction/get_ockh";
	    	var ockh_info = "<?=$ockh_info;?>";
	    	if (ockh_info == '' || ockh_info == 0 ) {
		    	ajax_data_sync(url,data).done(function(data_respond  /*,textStatus, jqXHR*/ ){
		    		// alert(data_respond);
					if (data_respond != '') {
						$("#form_add_barang [name=ockh]").val(data_respond);
					}else{
						$("#form_add_barang [name=ockh]").val('');
						notific8("ruby","ockh belum terdaftar");
					}
		   		});
	    		
	    	};
		<?}else{?>
	    	var barang_id = $('#barang_id_select').val();
	   		var data = $("#form_add_barang [name=data_barang] [value='"+barang_id+"']").text().split('??');
	   		var tipe_qty = data[2];
	   		if (tipe_qty == 2 || tipe_qty == 3) {
	   			$(".qty-tipe-2").show();
	   			if (tipe_qty == 2) {
		   			$(".roll-tipe-2").show();
	   			}else{
	   				$("#form_add_barang [name=roll-2]").val(1);
	   			}
	   			$("#btn-qty-2").show();
	   			$(".btn-add-qty").hide();
	   		}else{
	   			$(".qty-tipe-2").hide();
	   			$(".roll-tipe-2").hide();
	   			$(".btn-add-qty").show();
	   			$("#btn-qty-2").hide();
	   		}
	   		// alert(data);
			$('#form_add_barang [name=harga_beli]').val(parseFloat(data[1]).toString().replace('.',','));
			$('#form_add_barang .satuan_unit').html(data[0]+'/kg');
			$('#form_add_barang [name=satuan]').val(data[0]);
		<?}?>
    });

	$(".qty-2, .roll-2").change(function(){
		var qty = $('.qty-2').val();
		var roll = $('.roll-2').val();

		if (qty != '' && roll != '') {
			if (roll > 1) {
				roll = parseInt(roll) - 1;
				var qty_detail_1 = parseFloat(qty/roll).toFixed(0);
				// console.log(qty_detail_1)
				var sisa = qty - (qty_detail_1 * roll);
				var rekap = qty_detail_1+'??'+roll+'??0--'+sisa+'??1??0';
			}else{
				var rekap = qty+'??'+roll+'??0';	
			}
	    	$('#form_add_barang [name=rekap_qty]').val(rekap);

		}else{
	    	$('#form_add_barang [name=rekap_qty]').val('');
		}
	});

    $('#general_table').on('click','.btn-detail-remove', function(){
	    var ini = $(this).closest('tr');
	    bootbox.confirm("Mau menghapus item ini ? ", function(respond){
	    	if (respond) {
	    		var data = {};
		    	data['id'] =  ini.find('.id').html();
		    	var url = 'transaction/pembelian_detail_remove';
		    	// update_table(ini);
		    	ajax_data_sync(url,data).done(function(data_respond  /*,textStatus, jqXHR*/ ){
					if (data_respond == 'OK') {
						ini.remove();
						update_table();
					};
		   		});
	    	};
	    });
    });

    <?if ($pembelian_id != '') { ?>
    	$(document).on('change','.diskon, .keterangan', function(){
	    	var ini = $(this).closest('tr');
	    	var data = {};
	    	data['column'] = $(this).attr('name');
	    	data['pembelian_id'] =  "<?=$pembelian_id;?>";
	    	data['value'] = $(this).val();
	    	var url = 'transaction/pembelian_data_update';
	    	// update_table(ini);
	    	ajax_data_sync(url,data).done(function(data_respond  /*,textStatus, jqXHR*/ ){
				if (data_respond == 'OK') {
					update_table(ini);
				};
	   		});
	    });

	    $(document).on('change','.jatuh_tempo', function(){
	    	var ini = $(this).closest('tr');
	    	var data = {};
	    	data['ori_tanggal'] = "<?=$ori_tanggal;?>";
	    	data['pembelian_id'] =  "<?=$pembelian_id;?>";
	    	data['jatuh_tempo'] = $(this).val();
	    	var url = 'transaction/pembelian_jatuh_tempo_update';
	    	// update_table(ini);
	    	ajax_data_sync(url,data).done(function(data_respond  /*,textStatus, jqXHR*/ ){
				// alert(data_respond);
				if (data_respond == 'OK') {
					$('.jatuh_tempo').css('color','black');
				}else{
					$('.jatuh_tempo').css('color','red');
				}
	   		});
	    });
    <?}?>

    $("#search_no_faktur").select2({
        placeholder: "Select...",
        allowClear: true,
        minimumInputLength: 1,
        query: function (query) {
            var data = {
                results: []
            }, i, j, s;
            var data_st = {};
			var url = "transaction/get_search_no_faktur";
			data_st['no_faktur'] = query.term;
			
			ajax_data_sync(url,data_st).done(function(data_respond  /*,textStatus, jqXHR*/ ){
				// console.log(data_respond);
				$.each(JSON.parse(data_respond),function(k,v){
					data.results.push({
	                    id: v.id,
	                    text: v.no_faktur
	                });
				});
	            query.callback(data);
	   		});
        }
    });

    $('.btn-search-faktur').click(function(){
    	var id = $("#form_search_faktur [name=pembelian_id]").val();
    	var action = $("#form_search_faktur").attr('action');
    	if (id != '') {
    		window.location.replace(action+'/'+id);
    	};
    });

//==========================po====================================================

	$("#po_list").change(function(){
		if ($(this).val() != '' ) {
			// get_po_data($(this).val());
			let id = $(this).val();
			let supplier_id = $(`#po_list_copy [value=${id}]`).text();
			$("#supplier-id-add").val(supplier_id);
			$('#gudang_add_data').select2('open');
			$('#ockh').val('');
			$('#ockh').prop('disabled',true);

		}else{
			$('#ockh').prop('disabled',false);
		}
	});

	$("#po_list_edit").change(function(){
		if ($(this).val() != '' ) {
			$('#ockh_edit').val('');
			$('#ockh_edit').prop('disabled',true);
			let supplier_id = $(`#po_list_copy [value=${id}]`).text();
			$("#supplier-id-edit").val(supplier_id);
			
		}else{
			$('#ockh_edit').prop('disabled',false);
		}
	});

	$("#ockh").on('input',function(){
		let data = {};
		if ($(this).val().length >= 2) {
			data['ockh_input'] = $(this).val();
			var url = "transaction/get_ockh_suggestion";
			var list = [];
			ajax_data_sync(url,data).done(function(data_respond  /*,textStatus, jqXHR*/ ){
				$.each(JSON.parse(data_respond), function(i,v){
					// console.log(v);
					list[i] = v.ockh;
				});

				$(".ockh-history").html(list.join(", "));
				
			});
			
		};
	});

//=====================================lock=======================================

	$(document).on('click','.btn-lock', function(){
		var button_ini = $(this);
		var ini = $(this).closest('tr');
		bootbox.confirm("Mengganti status PO ini menjadi tuntas?", function(respond){
			if (respond) {
				let data = {};
				data['po_pembelian_warna_id'] = $('#po_pembelian_warna_id').val();
				// console.log(data['po_pembelian_warna_id']);
				let url = 'transaction/po_pembelian_warna_lock';
				ajax_data_sync(url,data).done(function(data_respond  ,textStatus, jqXHR ){
					if (data_respond == 'OK') {
						button_ini.toggleClass("po-info-hide");
						$(".btn-lock-open").toggleClass("po-info-hide");
						notific8("lime", "PO tuntas");
					};
				});
			};
		});
	});

//================================pckh============================================
	$("#ockh").change(function(){
		var data = {};
		data['ockh'] = $(this).val();
		var url = "transaction/get_po_batch_by_ockh";
		var po_number = '';
		ajax_data_sync(url,data).done(function(data_respond  /*,textStatus, jqXHR*/ ){
			$.each(JSON.parse(data_respond), function(i,v){
				po_number = v.po_number;
			});	
			if (po_number != '') {
				$(".po-link-number").html(po_number);
				$(".po-info").show();
			}else{
				$(".po-info").hide();
			}
		});
	});

//==============================initisial no faktur==============================================
	var faktur_inisial = [];
	<?foreach ($this->supplier_list_aktif as $row) {?>
		faktur_inisial["<?=$row->id?>"] = "<?=strtoupper($row->faktur_inisial);?>";
	<?}?>
	// console.log(faktur_inisial);
	$("#no-faktur").change(function(){
		let faktur = $(this).val();
		var inisial = faktur.substring(0,1).toUpperCase();
		var supplier_id = $(".supplier-input").val();
			// alert(faktur_inisial[supplier_id] +"!="+ inisial);
		if (faktur_inisial[supplier_id] != inisial && faktur_inisial[supplier_id] != '' && inisial != '') {
			bootbox.alert("Untuk supplier ini umumnya diawali huruf <b>"+faktur_inisial[supplier_id]+"</b>");
		};
	});

//==============================change qty-table==============================================

	$("#qty-table").on('change','[name=qty],[name=jumlah_roll]',function(){
    	data_result = table_qty_update('#qty-table').split('=*=');
    	let total = parseFloat(data_result[0]);
    	let total_roll = parseFloat(data_result[1]);
    	let rekap = data_result[2];
    	if (total > 0) {
		    $('.btn-brg-save').attr('disabled',false);
    	}else{
    		$('.btn-brg-save').attr('disabled',true);
    	}


    	$('.yard_total').html(total.toFixed(2));
    	$('.jumlah_roll_total').html(total_roll);
    	$('#form_add_barang [name=rekap_qty]').val(rekap);
    });

    $(".btn-add-qty-row").click(function(){
    	$("#qty-table").append(`<tr>
				<td><input name='qty'></td>
				<td><input name='jumlah_roll'> = </td>
				<td><input name='subtotal' tabindex='-1'></td>
				<td></td>
			</tr>`);
    });

    $('.btn-brg-save').click(function(){
    	var ini = $(this);
    	var yard = reset_number_format($('.yard_total').html());
    	if( yard > 0){
    		$('#form_add_barang').submit();
            btn_disabled_load(ini);
    	}
    });


//============================pembelian qty edit=============================

	$('.btn-qty-edit').click(function(){
    	var form = $('#form_edit_barang');
    	var ini=$(this).closest('tr');
		$('#qty-table-edit tbody').html('');
		var barang_id = ini.find('.barang_id').html();
    	var warna_id = ini.find('.warna_id').html();
    	var nama_jual = ini.find('.nama_jual').html();
    	var tipe_barang = ini.find('.tipe_barang').html();
		var data_qty = $(this).closest('tr').find('.data_qty').html();
		form.find('[name=rekap_qty]').val(data_qty);
		form.find('[name=pembelian_detail_id]').val(ini.find('.id').html());
    	form.find("[name=harga_beli]").val(ini.find('.harga_beli').html());
    	form.find(".qty-2-edit").val(ini.find('.qty').html());
    	form.find(".roll-2-edit").val(ini.find('.jumlah_roll').html());

		<?if ($po_pembelian_batch_id != '' && $po_pembelian_batch_id != 0) {?>
    		var brg_id = barang_id+'??'+warna_id;
    		$("#barang_id_select_edit").val(brg_id);
    		$('#barang_id_select_edit').select2("val" , brg_id);
    		
    		// alert(brg_id);
    		// $("#barang_id_select").change();
    	<?}else{?>
    		form.find("[name=barang_id]").val(barang_id);
	   		var data = $("#form_edit_barang [name=data_barang] [value='"+barang_id+"']").text().split('??');
	   		var tipe_qty = data[2];
	   		if (tipe_qty == 2 || tipe_qty == 3 ) {
	   			$(".qty-tipe-2-edit").show();
	   			if (tipe_qty == 2) {
		   			$(".roll-tipe-2-edit").show();
	   			}else{
	   				$("#form_edit_barang [name=jumlah_roll]").val(1);
	   			}
	   			$("#btn-qty-2-edit").show();
	   			$(".btn-edit-form-qty").hide();
	   		}else{
	   			$(".qty-tipe-2-edit").hide();
	   			$(".roll-tipe-2-edit").hide();
	   			$(".btn-edit-form-qty").show();
	   			$("#btn-qty-2-edit").hide();
	   		}
	    	form.find("[name=warna_id]").val(warna_id);
	    	form.find("[name=barang_id]").change();
	    	form.find("[name=warna_id]").change();
    	<?}?>


		var data_break  = data_qty.split('--');
		
		var i = 0; var total = 0; var idx = 1;
		if (data_qty !='') {
			$.each(data_break, function(k,v){
				// console.log('break:'+k+','+v);
				var qty = v.split('??');
				if (qty[1] == null) {
					qty[1] = 0;
				};
				total += qty[0]*qty[1]; 
				if (i == 0 ) {
					var baris = "<tr>"+
						"<td>"+idx+"</td>"+
						"<td><input name='qty' autocomplete='off' value='"+qty_float_number(qty[0])+"' class='input1'></td>"+
						"<td><input name='jumlah_roll' autocomplete='off' value='"+qty[1]+"'></td>"+
						"<td> = <input name='subtotal' value='"+qty[0]*qty[1]+"'></td>"+
						"<td hidden><input name='id' value='"+qty[2]+"'></td>"+
						"<td><button tabindex='-1' class='btn btn-xs blue btn-edit-qty-row'><i class='fa fa-plus'></i></button></td>"+
						"</tr>";
					idx++;
					$('#qty-table-edit tbody').append(baris);

				}else{
					var baris = "<tr>"+
						"<td>"+idx+"</td>"+
						"<td><input name='qty' autocomplete='off' value='"+qty_float_number(qty[0])+"' ></td>"+
						"<td><input name='jumlah_roll' autocomplete='off' value='"+qty[1]+"'></td>"+
						"<td> = <input name='subtotal' value='"+qty[0]*qty[1]+"'></td>"+
						"<td hidden> = <input name='id' value='"+qty[2]+"'></td>"+
						"<td></td>"+
						"</tr>";
					idx++;

					$('#qty-table-edit tbody').append(baris);
				}

				i++;
			});
		};

		for (var i = 0; i < 5; i++) {
			var baris = "<tr>"+
					"<td>"+idx+"</td>"+
					"<td><input name='qty' autocomplete='off' value='' class='input1'></td>"+
					"<td><input name='jumlah_roll' autocomplete='off' value=''></td>"+
					"<td> = <input name='subtotal' value=''></td>"+
					"<td></td>"+
					"</tr>";
			idx++;
			$('#qty-table-edit tbody').append(baris);	
		};

		data_result = table_qty_update('#qty-table-edit').split('=*=');
    	let total_qty = parseFloat(data_result[0]);
    	let total_roll = parseFloat(data_result[1]);
    	let rekap = data_result[2];

		if (total_qty > 0) {
			$('.btn-brg-edit-save').attr('disabled',false);
		}else{
			$('.btn-brg-edit-save').attr('disabled',true);
		}

		$('#portlet-config-qty-edit .jumlah_roll_total').html(total_roll);
		$('#portlet-config-qty-edit .yard_total').html(total_qty.toFixed(2));

		$('#form_edit_barang [name=rekap_qty]').val(rekap);
	});

	$(".qty-2-edit, .roll-2-edit").change(function(){
		var qty = $('.qty-2-edit').val();
		var roll = $('.roll-2-edit').val();

		if (qty != '' && roll != '') {
			if (roll > 1) {
				roll = parseInt(roll) - 1;
				var qty_detail_1 = parseFloat(qty/roll).toFixed(0);
				var sisa = qty - (qty_detail_1 * roll);
				if (sisa < 0) {
					qty_detail_1--;
				};
				sisa = qty - (qty_detail_1 * roll);
				var rekap = qty_detail_1+'??'+roll+'??0--'+sisa+'??1??0';
			}else{
				var rekap = qty+'??'+roll+'??0';	
			}
	    	$('#form_edit_barang [name=rekap_qty]').val(rekap);
		}else{
	    	$('#form_edit_barang [name=rekap_qty]').val('');
		}
	});

	$("#btn-qty-2-edit").click(function(){
		var form = $('#form_edit_barang');
        var rekap = $("#form_edit_barang [name=rekap_qty]").val();
        var barang_id = $("#barang_id_select_edit").val();
        var warna_id = $("#warna_id_select_edit").val();
        // alert(barang_id);
        if(barang_id != '' && warna_id != '' && typeof barang_id !== 'undefined' && typeof warna_id !== 'undefined'){
	        if (rekap != '') {
	            btn_disabled_load($("#btn-qty-2-edit"));
	            form.submit();
	        }else{
	            alert("lengkapi qty");
	        }
        	
        }else{
        	alert('lengkapi data');
        }

	});
	
	$("#qty-table-edit").on('change',"[name=qty],[name=jumlah_roll]",function(){
		
    	data_result = table_qty_update('#qty-table-edit').split('=*=');
    	let total_qty = parseFloat(data_result[0]);
    	let total_roll = parseFloat(data_result[1]);
    	let rekap = data_result[2];

		if (total_qty > 0) {
			$('.btn-brg-edit-save').attr('disabled',false);
		}else{
			$('.btn-brg-edit-save').attr('disabled',true);
		}

		$('#portlet-config-qty-edit .jumlah_roll_total').html(total_roll);
		$('#portlet-config-qty-edit .yard_total').html(total_qty.toFixed(2));

		$('#form_edit_barang [name=rekap_qty]').val(rekap);
    });

    $('#qty-table-edit').on('change','[name=subtotal]', function(){

    	let ini = $(this);

    	subtotal_on_change(ini);

    	data_result = table_qty_update('#qty-table-edit').split('=*=');
    	let total_qty = parseFloat(data_result[0]);
    	let total_roll = parseFloat(data_result[1]);
    	let rekap = data_result[2];

		if (total_qty > 0) {
			$('.btn-brg-edit-save').attr('disabled',false);
		}else{
			$('.btn-brg-edit-save').attr('disabled',true);
		}

		$('#portlet-config-qty-edit .jumlah_roll_total').html(total_roll);
		$('#portlet-config-qty-edit .yard_total').html(total_qty.toFixed(2));

		$('#form_edit_barang [name=rekap_qty]').val(rekap);
    });


    $('.btn-brg-edit-save').click(function(){
    	$('#form_edit_barang').submit();
    });

    $(document).on("click", ".btn-edit-qty-row", function(){
    	$("#qty-table-edit").append(`<tr>
				<td><input name='qty'></td>
				<td><input name='jumlah_roll'> = </td>
				<td><input name='subtotal' tabindex='-1'></td>
				<td></td>
			</tr>`);
    });


});

function table_qty_update(table){
	var total = 0; 
	var idx = 0; 
	var rekap = [];
	var total_roll = 0;
	$(table+" [name=qty]").each(function(){
		var ini = $(this).closest('tr');
		var qty = $(this).val();
		var roll = ini.find('[name=jumlah_roll]').val();
		var id = ini.find('[name=id]').val();
		if (typeof id === 'undefined') {
			id = '0';
		};
		if (qty != '' && roll == '') {
			roll = 1;
		}else if(roll == 0){
			// alert('test');
			if (qty == '') {
				qty = 0;
			};
		}else if(qty == '' && roll == ''){
			roll = 0;
			qty = 0;
		};

		if (roll == 0) {
    		var subtotal = parseFloat(qty);
    		total_roll += 0;
		}else{
    		var subtotal = parseFloat(qty*roll);
    		total_roll += parseFloat(roll);
		};

		// alert(subtotal);
		if (qty != '' && roll != '' && id != '') {
			roll = (roll == '' ? 0 : roll);
			rekap[idx] = qty+'??'+roll+'??'+id;
		}else if(id != 0){
			roll = (roll == '' ? 0 : roll);
			rekap[idx] = qty+'??'+roll+'??'+id;
			// alert(id);
		}
		idx++; 
		// alert(total_roll);
		total += subtotal;
		ini.find('[name=subtotal]').val(qty*roll);

	});

	rekap_str = rekap.join('--');
	// console.log(total+'=*='+total_roll+'=*='+rekap_str);

	return total+'=*='+total_roll+'=*='+rekap_str;
}

function cek_last_input(){

	var idx = "<?=$idx;?>";
	setTimeout(function(){
    	if (idx == 1) {
    		$('#barang_id_select').select2("open");
    	}else{
    		$('#warna_id_select').select2("open");
    	}
		// $('#form_add_barang .input1 .select2-choice').click();
	},700);

	if (idx > 1) {
		setTimeout(function(){
			// $('#barang_id_select').select2("open");
			$('#barang_id_select').val("<?=$barang_id_last;?>");
	    	$('#barang_id_select').change();
	    	setTimeout(function(){
	        	$('#harga_beli').val("<?=$harga_beli_last;?>");
	    	},700);

		},650);
		
	};

}

function update_table(){
	subtotal = 0;
	$('.subtotal').each(function(){
		subtotal+= reset_number_format($(this).html());
	});

	$('.total').html(change_number_format(subtotal));
	var diskon = reset_number_format($('.diskon').val());
	var g_total = subtotal - parseInt(diskon);
	$('.g_total').html(change_number_format(g_total));

}

function get_po_list(ini){
	let data = {};
	data['supplier_id'] = ini.val();
	let url = 'transaction/get_po_pembelian_by_supplier';
	$('#po_list').empty().trigger('change');
	ajax_data_sync(url,data).done(function(data_respond  /*,textStatus, jqXHR*/ ){
		// $('#po_list').select2("val","");
		var newOpt = new Option("Non PO", "", true, false);
		$("#po_list").append(newOpt).trigger('change');

		$.each(JSON.parse(data_respond), function(i,v){
			// console.log(data_respond);
			var newOpt = new Option(v.po_number, v.id, false, false);
			$("#po_list").append(newOpt).trigger('change');
			// $('#po_list').select2('data',{value:v.id, text:v.tanggal});
			// $("#po_list").append($('<option>',{
			// 	value: v.id,
			// 	text: v.tanggal+'/'+v.po_number
			// }));
		})
	});
}

function get_po_data(po_pembelian_batch_id){
	let form = $('#form_add_data');
	let data = {};
	data['po_pembelian_batch_id'] = po_pembelian_batch_id;
	let url = 'transaction/get_po_pembelian_data';
	ajax_data_sync(url,data).done(function(data_respond  /*,textStatus, jqXHR*/ ){
		$.each(JSON.parse(data_respond), function(i,v){
			// console.log(data_respond);
			let supplier_id = v.supplier_id;
			let ockh = v.ockh;

			form.find('[name=supplier_id]').val(supplier_id);
			form.find('[name=ockh]').val(ockh);
		});

		$('#gudang_add_data').select2('open');
	});
}

</script>
