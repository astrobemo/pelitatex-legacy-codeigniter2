	<link href="<?=base_url('assets/global/plugins/bootstrap-modal/css/bootstrap-modal.css');?>" rel="stylesheet" type="text/css"/>

<!-- <link href="<?=base_url('assets_noondev/css/bootstrap-modal-bs3patch.css');?>" rel="stylesheet" type="text/css"/> -->
<?=link_tag('assets/global/plugins/bootstrap-datepicker/css/datepicker3.css'); ?>

<style type="text/css">
#tbl-data input[type="text"], #tbl-data select{
	height: 25px;
	width: 50%;
	padding: 0 5px;
}

#qty-table input, #qty-table-edit input{
	width: 80px;
	padding: 5px;
}

#stok-info, #stok-info-edit{
	font-size: 1.5em;
	position: absolute;
	right: 50px;
	top: 30px;
}

#yard-info-add, #yard-info-edit{
	font-size: 1.5em;
}

.no-faktur-lengkap{
	font-size: 2.5em;
	font-weight: bold;
}

.input-no-border{
	border: none;
}

.subtotal-data{
	font-size: 1.2em;
}

#bayar-data tr td{
	font-size: 1.5em;
	font-weight: bold;
	padding: 0 10px 0 10px;
}

#bayar-data tr td input{
	padding: 0 5px 0 5px;
	border: 1px solid #ddd;
}

</style>

<div class="page-content">
	<div class='container'>
		<?
			$retur_beli_id = '';
			$supplier_id = '';
			$nama_supplier = '';
			$gudang_id = '';
			$nama_gudang = '';
			$tanggal = date('d/m/Y');
			$ori_tanggal = '';

			$no_sj = '';
			$no_sj_lengkap = '';

			$po_pembelian_id = '';
			$batch_id = '';
			$po_number = '';
			$alamat_keterangan = '';
			$kota = '';
			$ockh_info = '';

			// $keterangan = '';
			$status = '-';
			$g_total = 0;
			$readonly = '';
			$disabled = '';

			$nama_keterangan = '';
			$keterangan1 = '';
			$keterangan2 = '';

			foreach ($retur_data as $row) {
				$retur_beli_id = $row->id;
				$supplier_id = $row->supplier_id;
				$nama_supplier = $row->nama_supplier;
				$nama_keterangan = $nama_supplier;
				
				$po_pembelian_id = $row->po_pembelian_id;
				$batch_id = $row->batch_id;
				$po_number = $row->po_number;


				$no_sj = $row->no_sj;
				$no_sj_lengkap = $row->no_sj_lengkap;
				
				$tanggal = is_reverse_date($row->tanggal);
				$ori_tanggal = $row->tanggal;
				$status = $row->status;
				$ockh_info = $row->ockh_info;
				$status_aktif = $row->status_aktif;
				$keterangan1 = $row->keterangan1;
				$keterangan2 = $row->keterangan2;
								
			}

			foreach ($supplier_data as $row) {
				$alamat_keterangan = $row->alamat;
			}

			if ($status != 1) {
				if ( is_posisi_id() != 1 ) {
					$readonly = 'readonly';
				}
			}

			if ($retur_beli_id == '') {
				$disabled = 'disabled';
			}
		?>

		<div class="modal fade" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body">
						<form action="<?=base_url('transaction/retur_beli_list_update')?>" class="form-horizontal" id="form_add_data" method="post">
							<h3 class='block'> Data Baru</h3>
							

			                <div class="form-group">
			                    <label class="control-label col-md-3">Tanggal<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
									<input type name='toko_id' value="1" hidden>
									<input type name='retur_beli_id' value="" hidden >
									<input name='tanggal' value="<?=date("d/m/Y")?>" autocomplete="off" class="form-control date-picker" value="">
			                    </div>
			                </div>

			                <div class="form-group">
			                    <label class="control-label col-md-3">OCKH
			                    </label>
			                    <div class="col-md-6">
									<input name='ockh_info' class="form-control" id='ockh-info' >
			                    </div>
			                </div>


			                <div class="form-group">
			                    <label class="control-label col-md-3">Supplier
			                    </label>
			                    <div class="col-md-6">
									<select name='supplier_id' class="form-control" id="supplier_list_select">
										<option value="">Pilih</option>
										<?foreach ($this->supplier_list_aktif as $row) {?>
											<option value="<?=$row->id?>"><?=$row->nama?></option>
										<?}?>
									</select>
			                    </div>
			                </div>
							
							<div class="form-group">
			                    <label class="control-label col-md-3">PO
			                    </label>
			                    <div class="col-md-6">
									<select name='po_pembelian_batch_id' class="form-control" id="po_list">
										<option value="">NON PO</option>
										<?$idx = 1;foreach ($po_pembelian_batch as $row) {?>
											<option value="<?=$row->id?>"><?=$row->po_number?></option>
										<?=($idx%3==0 ? "<option disabled>---------</option>" : ''); $idx++;}?>
									</select>
									<small style="color:#ccc" class='loading-po' hidden><i>loading...</i></small>
			                    	<select id='po_list_copy' hidden>
										<?foreach ($po_pembelian_batch as $row) {?>
											<option value="<?=$row->id?>"><?=$row->supplier_id?></option>
										<?}?>
									</select>
			                    </div>
			                </div>

			                <div class="form-group">
			                    <label class="control-label col-md-3">Keterangan
			                    </label>
			                    <div class="col-md-6">
									<input name='keterangan1' maxlength='75' class="form-control"/>
									<input name='keterangan2' maxlength='75' class="form-control" placeholder='no surat jalan supplier'/>
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
			</div>
			<!-- /.modal-dialog -->
		</div>

		<div class="modal fade" id="portlet-config-edit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body">
						<form action="<?=base_url('transaction/retur_beli_list_update')?>" class="form-horizontal" id="form_edit_data" method="post">
							<h3 class='block'> Data EDIT</h3>
							

			                <div class="form-group">
			                    <label class="control-label col-md-3">Tanggal<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-9">
									<input type name='toko_id' value="1" hidden>
									<input type name='retur_beli_id' value="<?=$retur_beli_id?>" hidden >
									<input name='tanggal' autocomplete="off" class="form-control date-picker" value="<?=is_reverse_date($tanggal)?>">
			                    </div>
			                </div>

			                <div class="form-group">
			                    <label class="control-label col-md-3">OCKH
			                    </label>
			                    <div class="col-md-9">
									<input name='ockh_info' autocomplete="off" class="form-control" id="ockh-info-edit" value="<?=$ockh_info?>" >
			                    </div>
			                </div>

			                <div class="form-group">
			                    <label class="control-label col-md-3">Supplier
			                    </label>
			                    <div class="col-md-9">
									<select name='supplier_id' class="form-control" id="supplier_list_select_edit">
										<option value="">Pilih</option>
										<?foreach ($this->supplier_list_aktif as $row) {?>
											<option <?=($supplier_id==$row->id ? 'selected' : '');?> value="<?=$row->id?>"><?=$row->nama?></option>
										<?}?>
									</select>
			                    </div>
			                </div>
							
							<div class="form-group">
			                    <label class="control-label col-md-3">PO
			                    </label>
			                    <div class="col-md-9">
									<select name='po_pembelian_batch_id' class="form-control" id="po_list_edit">
										<option value="">NON PO</option>
										<?$idx = 1;foreach ($po_pembelian_batch as $row) {?>
											<option <?=($batch_id==$row->id ? 'selected' : '');?>  value="<?=$row->id?>"><?=$row->po_number?></option>
										<?=($idx%3==0 ? "<option disabled>---------</option>" : ''); $idx++;}?>
									</select>
									<small style="color:#ccc" class='loading-po' hidden><i>loading...</i></small>
			                    	<select id='po_list_copy_edit' hidden>
										<?foreach ($po_pembelian_batch as $row) {?>
											<option value="<?=$row->id?>"><?=$row->supplier_id?></option>
										<?}?>
									</select>
			                    </div>
			                </div>

			                <div class="form-group">
			                    <label class="control-label col-md-3">Keterangan
			                    </label>
			                    <div class="col-md-9">
									<input name='keterangan1' maxlength='75' class="form-control" value="<?=$keterangan1;?>" />
									<input name='keterangan2' maxlength='75' class="form-control" value="<?=$keterangan2;?>" />
			                    </div>
			                </div>


						</form>
					</div>

					<div class="modal-footer">
						<button type="button" class="btn blue btn-edit-save">Save</button>
						<button type="button" class="btn default" data-dismiss="modal">Close</button>
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
						<form action="<?=base_url('transaction/retur_beli_list_detail_insert')?>" class="form-horizontal" id="form_add_barang" method="post">
							<h3 class='block'> Data Barang</h3>
							
							<div class="form-group">
			                    <label class="control-label col-md-3">Gudang<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<input name='retur_beli_id' value='<?=$retur_beli_id;?>' hidden>
			                    	<input name='po_pembelian_batch_id' value='<?=$batch_id;?>' hidden>
	                    			<select name="gudang_id" class='form-control' id='gudang_id_select'>
		                				<?foreach ($this->gudang_list_aktif as $row) { ?>
			                    			<option <?if ($row->status_default == 1) {echo "selected";}?> value="<?=$row->id?>"><?=$row->nama;?></option>
			                    		<? } ?>
			                    	</select>
			                    </div>
			                </div> 

							<div class="form-group">
			                    <label class="control-label col-md-3">Kode Barang<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
	                				<?if ($batch_id != '') {?>
				                    	<select name="barang_id" class='form-control input1' id='barang_id_select'>
			                				<option value=''>Pilih</option>
			                				<?foreach ($barang_id_list as $row) {?>
												<option value="<?=$row->barang_id.'??'.$row->warna_id;?>"><?=$row->nama?> <?=$row->nama_warna?></option>
											<?}?>
				                    	</select>
				                    	<select name='barang_id_copy' hidden>
				                    		<?foreach ($barang_id_list as $row) { ?>
												<option value="<?=$row->barang_id.'??'.$row->warna_id;?>"><?=$row->harga_beli?>=??=<?=$row->qty;?></option>
											<? } ?>
				                    	</select>
				                    	QTY-PO : <small id='qty-po'></small>
	                				<?}else{?>
	                					<select name="barang_id" class='form-control input1' id='barang_id_select'>
			                				<option value=''>Pilih</option>
			                				<?foreach ($this->barang_list_aktif_beli as $row) {?>
												<option value="<?=$row->id;?>"><?=$row->nama?></option>
											<?}?>
				                    	</select>
				                    	<select name='barang_id_copy' hidden>
				                    		<?foreach ($this->barang_list_aktif_beli as $row) { ?>
												<option value="<?=$row->id;?>"><?=$row->harga_beli?>??0</option>
											<? } ?>
				                    	</select>
	                				<?}?>
			                    </div>
			                </div>
			                <?if ($batch_id == '') {?>
			                	<div class="form-group">
				                    <label class="control-label col-md-3">Warna<span class="required">
				                    * </span>
				                    </label>
				                    <div class="col-md-6">
	                					<select name="warna_id" class='form-control' id='warna_id_select'>
			                				<option value=''>Pilih</option>
			                				<?foreach ($this->warna_list_aktif as $row) {?>
												<option value="<?=$row->id;?>"><?=$row->warna_beli?></option>
											<?}?>
				                    	</select>
				                    </div>
				                </div>
			                <?}?>
			                <div class="form-group">
			                    <label class="control-label col-md-3">Harga<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<div class='input-group'>
			                    		<input name="rekap_qty" hidden />
			                    		<input type="text" class='amount_number form-control' name="harga"/>
			                			<span class="input-group-btn" >
											<a data-toggle="popover" class='btn btn-md default btn-cek-harga' data-trigger='click' title="History Pembelian Supplier" data-html="true" data-content="<div id='data-harga'>loading...</div>"><i class='fa fa-search'></i></a>
										</span>
			                    	</div>
			                    </div>
			                </div>
						</form>
					</div>

					<div class="modal-footer">
						<!-- <button data-toggle='modal' class="btn blue btn-active btn-save-detail">Save</button> -->
						<a href="#portlet-config-qty" data-toggle='modal' class="btn blue btn-active btn-add-qty">Add Qty</a>
						<button type="button" class="btn default" data-dismiss="modal">Close</button>
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
						<table id='qty-table'>
							<thead>
								<tr>
									<th>Yard</td>
									<th>Roll</td>
									<th></th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td><input name='qty' class='input1'></td>
									<td><input name='jumlah_roll'></td>
									<td><button tabindex='-1' class='btn btn-xs blue btn-add-qty-row'><i class='fa fa-plus'></i></button></td>
								</tr>
								<?for ($i=0; $i < 9; $i++) { ?>
									<tr>
										<td><input name='qty'></td>
										<td><input name='jumlah_roll'></td>
										<td></td>
									</tr>
									
								<? }?>
							</tbody>
						</table>
						<div id='yard-info-add'>
							TOTAL QTY: <span class='yard_total' >0</span> yard <br/>
							TOTAL ROLL: <span class='roll_total' >0</span> 
						</div>

						<div id='stok-info' style='border:1px solid #ddd; padding:5px; margin-bottom:20px;'>
							STOK QTY : <span id='qty-stok-add'>0</span><br/>
							STOK ROLL : <span id='roll-stok-add'>0</span>
						</div>

					</div>

					<div class="modal-footer">
						<button type="button" class="btn blue btn-active btn-brg-save">Save</button>
						<button type="button" class="btn default" data-dismiss="modal">Close</button>
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
						<form action="<?=base_url('transaction/retur_beli_list_detail_insert')?>" class="form-horizontal" id="form_edit_barang" method="post">
							<h3 class='block'> Edit Barang</h3>
							
							<div class="form-group">
			                    <label class="control-label col-md-3">Gudang<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<input name='retur_beli_id' value='<?=$retur_beli_id;?>' hidden>
			                    	<input name='retur_beli_detail_id' hidden>
			                    	<input name='po_pembelian_batch_id' value='<?=$batch_id;?>' hidden>
	                    			<select name="gudang_id" class='form-control' id='gudang_id_select_edit'>
		                				<?foreach ($this->gudang_list_aktif as $row) { ?>
			                    			<option <?if ($row->status_default == 1) {echo "selected";}?> value="<?=$row->id?>"><?=$row->nama;?></option>
			                    		<? } ?>
			                    	</select>
			                    </div>
			                </div> 

							<div class="form-group">
			                    <label class="control-label col-md-3">Kode Barang<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
	                				<?if ($batch_id != '') {?>
				                    	<select name="barang_id" class='form-control input1' id='barang_id_select_edit'>
			                				<option value=''>Pilih</option>
			                				<?foreach ($barang_id_list as $row) {?>
												<option value="<?=$row->barang_id.'??'.$row->warna_id;?>"><?=$row->nama?> <?=$row->nama_warna?></option>
											<?}?>
				                    	</select>
				                    	<select name='barang_id_copy_edit' hidden>
				                    		<?foreach ($barang_id_list as $row) { ?>
												<option value="<?=$row->barang_id.'??'.$row->warna_id;?>"><?=$row->harga_beli?>=??=<?=$row->qty;?></option>
											<? } ?>
				                    	</select>
				                    	QTY-PO : <small id='qty-po-edit'></small>
	                				<?}else{?>
	                					<select name="barang_id" class='form-control input1' id='barang_id_select_edit'>
			                				<option value=''>Pilih</option>
			                				<?foreach ($this->barang_list_aktif_beli as $row) {?>
												<option value="<?=$row->id;?>"><?=$row->nama?></option>
											<?}?>
				                    	</select>
				                    	<select name='barang_id_copy_edit' hidden>
				                    		<?foreach ($this->barang_list_aktif_beli as $row) { ?>
												<option value="<?=$row->id;?>"><?=$row->harga_beli?></option>
											<? } ?>
				                    	</select>
	                				<?}?>
			                    </div>
			                </div>
			                <?if ($batch_id == '') {?>
			                	<div class="form-group">
				                    <label class="control-label col-md-3">Warna<span class="required">
				                    * </span>
				                    </label>
				                    <div class="col-md-6">
	                					<select name="warna_id" class='form-control' id='warna_id_select_edit'>
			                				<option value=''>Pilih</option>
			                				<?foreach ($this->warna_list_aktif as $row) {?>
												<option value="<?=$row->id;?>"><?=$row->warna_beli?></option>
											<?}?>
				                    	</select>
				                    </div>
				                </div>
			                <?}?>
			                <div class="form-group">
			                    <label class="control-label col-md-3">Harga<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<div class='input-group'>
			                    		<input name="rekap_qty" hidden />
			                    		<input type="text" class='amount_number form-control' name="harga"/>
			                			<span class="input-group-btn" >
											<a data-toggle="popover" class='btn btn-md default btn-cek-harga' data-trigger='click' title="History Pembelian Supplier" data-html="true" data-content="<div id='data-harga'>loading...</div>"><i class='fa fa-search'></i></a>
										</span>
			                    	</div>
			                    </div>
			                </div>
						</form>
					</div>

					<!-- btn-save-detail-edit -->
					<div class="modal-footer">
						<a href="#portlet-config-qty-edit" data-toggle='modal' class="btn blue btn-active btn-qty-edit">Edit QTY</a>
						<button type="button" class="btn default" data-dismiss="modal">Close</button>
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
						<table id='qty-table-edit'>
							<thead>
								<tr>
									<th>Yard</td>
									<th>Roll</td>
									<th></th>
								</tr>
							</thead>
							<tbody>

							</tbody>
						</table>
						<span class='total_roll' hidden></span>
						
						<div id='yard-info-edit'>
							TOTAL QTY: <span class='yard_total' >0</span> yard <br/>
							TOTAL ROLL: <span class='roll_total' >0</span>
						</div>

						<div id='stok-info-edit' style='border:1px solid #ddd; padding:5px; margin-bottom:20px;'>
							STOK QTY : <span id='qty-stok-edit'>0</span><br/>
							STOK ROLL : <span id='roll-stok-edit'>0</span>
						</div>

						<form hidden action="<?=base_url()?>transaction/retur_beli_qty_update" id='form-qty-update' method="post">
							<input name="retur_beli_id" value="<?=$retur_beli_id;?>">
							<input name='id'>
							<input name='rekap_qty'>
						</form>
					</div>

					<div class="modal-footer">
						<button type="button" class="btn blue btn-active btn-brg-edit-save">Save</button>
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
						<form action="" class="form-horizontal" id="form_search_faktur" method="get">
							<h3 class='block'> Cari Faktur</h3>
							
							
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

		<div class="modal fade" id="portlet-config-pin" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body">
						<form action="<?=base_url('transaction/retur_beli_request_open');?>" class="form-horizontal" id="form-request-open" method="post">
							<h3 class='block'> PIN</h3>
							
							<div class="form-group">
			                    <label class="control-label col-md-3">PIN<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<input name='retur_beli_id' value='<?=$retur_beli_id;?>' hidden>
									<input name='pin' type='password' id="pin_user" class="form-control">
			                    </div>
			                </div>	
		                </form>		                
					</div>

					<div class="modal-footer">
						<button type="button" class="btn blue btn-request-open">OPEN</button>
						<button type="button" class="btn default" data-dismiss="modal">Close</button>
					</div>
				</div>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
		</div>

		<div class="modal fade" id="portlet-config-print" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body">
						<h3 class='block'> Printer</h3>
						
						<div class="form-group">
		                    <label class="control-label col-md-3">Type<span class="required">
		                    * </span>
		                    </label>
		                    <div class="col-md-6">
		                    	<input name='print_target' hidden>
		                    	<select class='form-control' id='printer-name'>
		                    		<?foreach ($printer_list as $row) { ?>
		                    			<option  <?=(get_default_printer() == $row->id ? 'selected' : '');?> value='<?=$row->id;?>'><?=$row->nama;?> <?//=(get_default_printer() == $row->id ? '(default)' : '');?></option>
		                    		<?}?>
		                    	</select>
		                    </div>
		                </div>
					</div>

					<div class="modal-footer">
						<button type="button" class="btn blue btn-active btn-print-action" data-dismiss="modal">Print</button>
						<button type="button" class="btn btn-active default" data-dismiss="modal">Close</button>
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
						<div class="actions hidden-print">
							<a href="#portlet-config" data-toggle='modal' class="btn btn-default btn-sm btn-form-add">
							<i class="fa fa-plus"></i> retur Baru </a>
							<!-- <a href="#portlet-config-faktur" data-toggle='modal' class="btn btn-default btn-sm btn-form-add">
							<i class="fa fa-search"></i> Cari Faktur </a> -->
						</div>
					</div>
					<div class="portlet-body">
						<div style='position:absolute; color:red; font-size:3em; margin:-10px 35%'><b>RETUR PEMBELIAN</b></div>
						<table style='width:100%'>
							<tr>
								<td>
									<table>
										<tr>
											<?if ($retur_beli_id != '') { ?>
												<tr>
													<td colspan='3'>
														<?if ($status != 1) { ?>
															<button href="#portlet-config-pin" data-toggle='modal' class='btn btn-xs btn-pin'><i class='fa fa-key'></i> request open</button>
														<?}else{ ?>
															<a href="#portlet-config-edit" data-toggle='modal' class='btn btn-default btn-xs btn-edit-data '><i class='fa fa-edit'></i> edit</a>
														<?}?>

													</td>
												</tr>
											<?}?>
								    		<td>Status</td>
								    		<td class='padding-rl-5'> : </td>
								    		<td class='td-isi-bold'>
								    			<?if ($status == -1) { ?>
								    				<span style='color:red'><b>BATAL</b></span>
								    			<?}elseif ($status == 1) {?>
								    				<span style='color:green'><b>OPEN</b></span>
								    			<?}elseif ($status == 0 && $status != '-') {?>
								    				<span style='color:orange'><b>LOCKED</b></span>
								    			<?}else{}?>
								    		</td>
								    	</tr>
										<tr>
								    		<td>Tanggal</td>
								    		<td class='padding-rl-5'> : </td>
								    		<td class='td-isi-bold'><?=is_reverse_date($tanggal);?></td>
								    	</tr>
								    	<tr class='supplier_section'>
								    		<td>PO Pembelian</td>
								    		<td class='padding-rl-5'> : </td>
								    		<td class='td-isi-bold'>
								    			<a target='_blank' href="<?=base_url().is_setting_link('transaction/po_pembelian_detail_warna_batch').'?id='.$po_pembelian_id.'&batch_id='.$batch_id; ?>">
								    			<?=$po_number;?>
								    			</a>
								    		</td>
								    	</tr>
								    	<tr class='supplier_section'>
								    		<td>OCKH</td>
								    		<td class='padding-rl-5'> : </td>
								    		<td class='td-isi-bold'>
								    			<a target='_blank' href="<?=base_url().is_setting_link('transaction/po_pembelian_detail_warna_batch').'?id='.$po_pembelian_id.'&batch_id='.$batch_id; ?>">
								    			<?=$ockh_info;?>
								    			</a>
								    		</td>
								    	</tr>
								    	<tr class='supplier_section'>
								    		<td>Supplier</td>
								    		<td class='padding-rl-5'> : </td>
								    		<td class='td-isi-bold'>
							    				<?=$nama_supplier;?>
								    		</td>
								    	</tr>
								    	<tr class='supplier_section' style='vertical-align:top'>
								    		<td>Keterangan</td>
								    		<td class='padding-rl-5'> : </td>
								    		<td class='td-isi-bold'>
								    			<?=($keterangan1 != '' ? $keterangan1."<br/>" : '');?>
							    				<?=($keterangan2);?>
								    		</td>
								    	</tr>
								    </table>
								</td>
								<td class='text-right'>
									<span class='no-faktur-lengkap'> <?=$no_sj_lengkap;?></span>
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
										<?if ($retur_beli_id !='' && $status == 1) {?>
											<a href="#portlet-config-detail" data-toggle='modal' class="btn btn-xs blue btn-brg-add">
											<i class="fa fa-plus"></i> </a>
										<?}?>
									</th>
									<th scope="col">
										Satuan
									</th>
									<th scope="col">
										Jml Yard/KG
									</th>
									<th scope="col">
										Jml Roll
									</th>
									<th scope="col">
										Harga
									</th>
									<th scope="col">
										Total Harga
									</th>
									<th scope="col" class='hidden-print'>
										Action
									</th>
								</tr>
							</thead>
							<tbody>
								<?
								$i =1; 
								foreach ($retur_detail as $row) { ?>
									<tr>
										<td>
											<?=$i;?> 
										</td>
										<td>
											<span class='nama_beli'><?=$row->nama_barang;?> <?=$row->nama_warna;?></span> 
										</td>
										<td>
											<?=$row->nama_satuan;?>
										</td>
										<td>
											<!-- <input name='qty' class='free-input-sm qty' value="<?=$row->qty;?>">  -->
											<span class='qty'><?=str_replace('.00', '', $row->qty);?></span>
										</td>
										<td>
											<!-- <input name='jumlah_roll' class='free-input-sm jumlah_roll' value="<?=$row->jumlah_roll;?>"> -->
											<span class='jumlah_roll'><?=$row->jumlah_roll;?></span> 
										</td>
										<td>
											<span class='harga'><?=number_format($row->harga,'0','.','.');?></span> 
										</td>
										<td>
											<?$subtotal = $row->qty * $row->harga;
											$g_total += $subtotal;
											?>
											<span class='subtotal'><?=number_format($subtotal,'0','.','.');?></span> 
										</td>
										<td class='hidden-print'>
											<?//if ($status == 1 || is_posisi_id() == 1) { ?>
												<span class='gudang_id' hidden><?=$row->gudang_id;?></span>
												<span class='barang_id' hidden><?=$row->barang_id;?></span>
												<span class='warna_id' hidden><?=$row->warna_id;?></span>
												<span class='id' hidden><?=$row->id;?></span>
												<span class='data_qty' hidden><?=$row->data_qty;?></span>
												<a href='#portlet-config-detail-edit' data-toggle='modal' class="btn-xs btn green btn-edit-detail"><i class="fa fa-edit"></i> </a>
												<a class="btn-xs btn red btn-detail-remove"><i class="fa fa-times"></i> </a>
											<?//}?>
										</td>
									</tr>
								<?
								$i++; 
								} ?>

								<tr class='subtotal-data'>
									<td colspan='6' class='text-right'><b>TOTAL</b></td>
									<td><b class='total'><?=number_format($g_total,'0',',','.');?> </b> </td>
									<td class='hidden-print'></td>
								</tr>
							</tbody>
						</table>

						<hr/>

						<table style='width:100%'>
							<tr>
								<td style='vertical-align:top'>
									<table id='bayar-data'>
									</table>
								</td>
								<td style='vertical-align:top;font-size:2.5em;' class='text-right'>
									<table style='float:right;'>
										<tr style='border:2px solid #ffd7b5'>
											<td class='padding-rl-25' style='background:#ffd7b5'>TOTAL</td>
											<td class='text-right padding-rl-10'> 
												<b>Rp <span class='g_total' style=''><?=number_format($g_total,'0',',','.');?></span></b>
											</td>
										</tr>
									</table>
								</td>
							</tr>

						</table>
						<div>
							<?if ($status == 1) {?>
								<button type='button' <?=$disabled;?> <?if ($status != 1) {?> disabled <?}?> class='btn btn-lg red hidden-print btn-close'><i class='fa fa-lock'></i> LOCK </button>
							<?}?>
			                
			                <?if ($status == 0) {?>
				                <!-- <button <?=$disabled;?> type="button" href='#portlet-config-print' data-toggle='modal' class="btn btn-lg blue btn-print btn-faktur-print">PRINT</button> -->
				                <button <?=$disabled;?> type="button" href='#portlet-config-print' data-toggle='modal' class="btn btn-lg blue btn-print btn-kombi-print">PRINT + DETAIL</button>
			                <?}?>
						</div>
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
<script src="<?php echo base_url('assets/global/plugins/bootbox/bootbox.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/select2/select2.min.js'); ?>" type="text/javascript" ></script>

<script src="<?php echo base_url('assets_noondev/js/webprint.js'); ?>" type="text/javascript"></script>

<script>
jQuery(document).ready(function() {

	var form_group = {};
	var idx_gen = 0;

   	<?if($status==0){?>
			webprint = new WebPrint(true, {
		        relayHost: "127.0.0.1",
		        relayPort: "8080",
		        readyCallback: function(){
		            
		        }
		    });

		    $('.btn-faktur-print').click(function(){
				$('[name=print_target]').val('1');
			});

			$('.btn-kombi-print').click(function(){
				$('[name=print_target]').val('2');
			});

			$('.print-testing').click(function(){
				$('[name=print_target]').val('99');
				// print_detail();
			});


			$('.btn-print-action').click(function(){
				var selected = $('#printer-name').val();
				var printer_name = $("#printer-name [value='"+selected+"']").text();
				printer_name = $.trim(printer_name);
				var action = $('[name=print_target]').val();
				if (action == 1 ) {
					print_faktur(printer_name);
				}else if (action == 2 ) {
					print_kombinasi(printer_name);
				}else{
					print_test(printer_name);
				}
			});

		<?}?>

	$('[data-toggle="popover"]').popover();


    $('#warna_id_select, #barang_id_select').select2({
        placeholder: "Pilih...",
        allowClear: true
    });

    $('#supplier_id_select, #supplier_id_select_edit').select2({
        allowClear: true
    });

    $("#ockh-info").change(function(){
		var ockh = $(this).val();
		if (ockh.length > 4) {
			var data = {};
			var po_number = '';
			var batch_id_data = [];
			var batch_id = '';
			var supplier_id ='';
		    var data_st = {};
		    data_st['ockh'] = ockh;
			var url = "transaction/get_po_batch_by_ockh";
			ajax_data_sync(url,data_st).done(function(data_respond  /*,textStatus, jqXHR*/ ){
				$.each(JSON.parse(data_respond), function(i,v){
					data[v.po_pembelian_batch_id] = v.po_number;
					batch_id = v.po_pembelian_batch_id;
					supplier_id = v.supplier_id;
				});

				if (data.length > 1) {
					alert("OCKH ada di 2 PO : "+data.join(', '));
				}else{
					$("#po_list").val(batch_id);
					$("#supplier_list_select").val(supplier_id);
					$("#supplier_list_select").change();
				}
			});
		};
	});

	$("#supplier_list_select").change(function() {
		var po_pembelian_batch_id = $("#po_list").val();
		var supplier_id_select = $("#po_list_copy option[value='"+po_pembelian_batch_id+"'] ").text();
		var supplier_id = $(this).val();
		var data = {};
	    var data_st = {};
		var url = "transaction/get_po_batch_by_supplier";
		data_st['supplier_id'] = supplier_id;
		$('#po_list')
		    .find('option')
		    .remove();
		$(".loading-po").show();
		var idx=1;
		ajax_data_sync(url,data_st).done(function(data_respond ){
			$.each(JSON.parse(data_respond), function(i,v){
				console.log(data_respond);
				if (idx %3 == 0) {
					var newOpt = new Option('------', '', false, false);
					$("#po_list").append(newOpt).attr({'disabled':true});
				};
				var newOpt = new Option(v.po_number, v.id, false, false);
				$("#po_list").append(newOpt).attr({'disabled':false});
				idx++;
			});

			if (supplier_id == supplier_id_select) {
				$('#po_list').val(po_pembelian_batch_id);
			}

			$(".loading-po").hide();
		});
	})

    <?if ($retur_beli_id != '') { ?>
		$('.btn-print').click(function(){
	    	// window.print();
	    });
	<?}?>

    $('.btn-pin').click(function(){
    	setTimeout(function(){
	    	$('#pin_user').focus();    		
    	},700);
    });

    $('.btn-request-open').click(function(){
    	cek_pin();
    });

    $('#pin_user').keypress(function (e) {
        if (e.which == 13) {
        	cek_pin();
        }
    });

    <?if ($retur_beli_id != '') {?>
    	$('.btn-close').click(function(){
    		var id = "<?=$retur_beli_id;?>";
	    	window.location.replace(baseurl+'transaction/retur_beli_list_close?id='+id);
	    });
    <?}?>	

//====================================get harga beli barang + qty====================================

	$("#gudang_id_select").change(function() {
		var barang_id = $("#barang_id_select").val();
		var warna_id = $("#warna_id_select").val();
		var gudang_id = $("#gudang_id_select").val();

   		get_qty(barang_id, warna_id, gudang_id, $('#qty-stok-add'), $('#roll-stok-add'));
	});

    $('#barang_id_select').change(function(){
    	var barang_id = $('#barang_id_select').val();
   		var data = $("#form_add_barang [name=barang_id_copy] [value='"+barang_id+"']").text().split('=??=');
   		
   		// alert(data);
		$('#form_add_barang [name=harga]').val(data[0]);
		$('#qty-po').html(data[1]);
   		$('#warna_id_select').select2('open');

   		var barang_id = $("#barang_id_select").val();
		var warna_id = $("#warna_id_select").val();
		var gudang_id = $("#gudang_id_select").val();

   		get_qty(barang_id, warna_id, gudang_id, $('#qty-stok-add'), $('#roll-stok-add'));
    });

    $('#warna_id_select').change(function(){
    	$('#form_add_barang [name=harga]').focus();
    	var barang_id = $("#barang_id_select").val();
		var warna_id = $("#warna_id_select").val();
		var gudang_id = $("#gudang_id_select").val();

   		get_qty(barang_id, warna_id, gudang_id, $('#qty-stok-add'), $('#roll-stok-add'));
    });

    $('.btn-brg-save').click(function(){
    	var yard = reset_number_format($('#yard-info-add .yard_total').html());
    	if( yard > 0){
    		btn_disabled_load($(this));
    		$('#form_add_barang').submit();
    	}

    });

//====================================edit=====================================
	
	$('#barang_id_select_edit').change(function(){
    	var barang_id = $('#barang_id_select_edit').val();
   		var data = $("#form_edit_barang [name=barang_id_copy_edit] [value='"+barang_id+"']").text().split('=??=');
   		
   		// alert(data);
		$('#form_edit_barang [name=harga]').val(data[0]);
		$('#qty-po-edit').html(data[1]);
   		$('#warna_id_select_edit').select2('open');

   		var barang_id = $("#barang_id_select_edit").val();
		var warna_id = $("#warna_id_select_edit").val();
		var gudang_id = $("#gudang_id_select_edit").val();

   		get_qty(barang_id, warna_id, gudang_id, $('#qty-stok-add'), $('#roll-stok-add'));
    });

	$("#general_table").on("click", ".btn-edit-detail", function () {
		var ini = $(this).closest('tr');
			// alert("test");
		var retur_beli_detail_id  = ini.find(".id").html();
		var harga = ini.find(".harga").html();
		var gudang_id = ini.find(".gudang_id").html();
		var barang_id = ini.find(".barang_id").html();
		var warna_id = ini.find(".warna_id").html();
		var data_qty = ini.find('.data_qty').html();
			// alert(barang_id);
		<?if ($batch_id != '') {?>
			barang_id = barang_id+'??'+warna_id;
		<?}?>

		var qty = ini.find(".qty").html();
		var jumlah_roll = ini.find(".jumlah_roll").html();

		var form = $("#form_edit_barang");
		form.find("[name=retur_beli_detail_id]").val(retur_beli_detail_id);
		form.find("[name=gudang_id]").val(gudang_id);
		form.find("#warna_id_select_edit").val(warna_id);
		form.find("[name=harga]").val(harga);
		form.find("[name=rekap_qty]").val(data_qty);

		form.find("[name=qty]").val(qty);
		form.find("[name=jumlah_roll]").val(jumlah_roll);

		$("#barang_id_select_edit").val(barang_id);
		$("#barang_id_select_edit").change();

		get_qty(barang_id, warna_id, gudang_id, $('#qty-stok-edit'), $('#roll-stok-edit'));
	});

	$('.btn-save-detail-edit').click(function(){
    	var barang_id = $("#barang_id_select_edit").val();
    	var warna_id = $("#warna_id_select_edit").val();
    	var qty = $("#form_edit_barang [name=qty]").val();
    	var jumlah_roll = $("#form_edit_barang [name=jumlah_roll]").val();

    	<?if ($batch_id != '') {?>
    		var barang_set = barang_id.split('??');
			barang_id = barang_set[0];
			warna_id = barang_set[1];
    	<?}?>

    	if (barang_id != '' && warna_id != '' && qty != '' && jumlah_roll != '') {
			$('#form_edit_barang').submit();
			btn_disabled_load($(this));
    	}else{
    		alert("Mohon lengkapi data");
    	}

    });

    $("#ockh-info-edit").change(function(){
		var ockh = $(this).val();
		if (ockh.length > 4) {
			var data = {};
			var po_number = '';
			var batch_id_data = [];
			var batch_id = '';
			var supplier_id ='';
		    var data_st = {};
		    data_st['ockh'] = ockh;
			var url = "transaction/get_po_batch_by_ockh";
			ajax_data_sync(url,data_st).done(function(data_respond  /*,textStatus, jqXHR*/ ){
				$.each(JSON.parse(data_respond), function(i,v){
					data[v.po_pembelian_batch_id] = v.po_number;
					batch_id = v.po_pembelian_batch_id;
					supplier_id = v.supplier_id;
				});

				if (data.length > 1) {
					alert("OCKH ada di 2 PO : "+data.join(', '));
				}else{
					$("#po_list_edit").val(batch_id);
					$("#supplier_list_select_edit").val(supplier_id);
					$("#supplier_list_select_edit").change();
				}
			});
		};
	});

	$("#supplier_list_select_edit").change(function() {
		var po_pembelian_batch_id = $("#po_list_edit").val();
		var supplier_id_select = $("#po_list_copy_edit option[value='"+po_pembelian_batch_id+"'] ").text();
		var supplier_id = $(this).val();
		var data = {};
	    var data_st = {};
		var url = "transaction/get_po_batch_by_supplier";
		data_st['supplier_id'] = supplier_id;
		$('#po_list_edit')
		    .find('option')
		    .remove();
		$(".loading-po").show();
		var idx=1;
		ajax_data_sync(url,data_st).done(function(data_respond ){
			$.each(JSON.parse(data_respond), function(i,v){
				console.log(data_respond);
				if (idx %3 == 0) {
					var newOpt = new Option('------', '', false, false);
					$("#po_list_edit").append(newOpt).attr({'disabled':true});
				};
				var newOpt = new Option(v.po_number, v.id, false, false);
				$("#po_list_edit").append(newOpt).attr({'disabled':false});
				idx++;
			});

			if (supplier_id == supplier_id_select) {
				$('#po_list_edit').val(po_pembelian_batch_id);
			}

			$(".loading-po").hide();
		});
	})


//====================================modal barang=====================================

	<?if ($status == 1) {?>
		var map = {220: false};
		$(document).keydown(function(e) {
		    if (e.keyCode in map) {
		        map[e.keyCode] = true;
		        if (map[220]) {
		            $('#portlet-config-detail').modal('toggle');
		           	setTimeout(function(){
			    		$('#barang_id_select').select2("open");
			    	},700);
		        }
		    }
		}).keyup(function(e) {
		    if (e.keyCode in map) {
		        map[e.keyCode] = false;
		    }
		});
	<?};?>

	$('.btn-brg-add').click(function(){
    	// var select2 = $(this).data('select2');
    	setTimeout(function(){
    		$('#barang_id_select').select2("open");
    		// $('#form_add_barang .input1 .select2-choice').click();
    	},700);
    });

//====================================qty manage=============================    
	
	$(".btn-add-qty").click(function(){
		setTimeout(function() {
			$("#qty-table .input1").focus();
		},600);
    });
	
    $(".btn-add-qty-row").click(function(){
    	var baris = "<tr><td><input name='qty'></td>"+
				"<td><input name='jumlah_roll'></td>"+
				"<td></td></tr>";
    	$('#qty-table').append(baris);
    });
	
    $("#qty-table").on('change',"input",function(){

    	// alert('hmm');
    	let result = update_qty_edit('#qty-table').split('=*=');
		if (parseFloat(result[0]) > 0) {
			$('.btn-brg-edit-save').attr('disabled',false);
		}else{
			$('.btn-brg-edit-save').attr('disabled',true);
		}

		$('#yard-info-add .roll_total').html(parseFloat(result[1]));
		$('#yard-info-add .yard_total').html(parseFloat(result[0]));
		// alert(result[0]);

		$('#form_add_barang [name=rekap_qty]').val(result[2]);

    });


//====================================qty edit manage=============================    

	$('.btn-qty-edit').click(function(){
		$('#qty-table-edit tbody').html('');
		var data_qty = $('#form_edit_barang [name=rekap_qty]').val();
		var data_break  = data_qty.split('--');		
		var i = 0; var total = 0;
		$.each(data_break, function(k,v){
			var qty = v.split('??');
			total += qty[0]*qty[1]; 
			if (i == 0 ) {
				var baris = "<tr>"+
					"<td><input name='qty' value='"+parseFloat(qty[0])+"' class='input1'></td>"+
					"<td><input name='jumlah_roll' value='"+qty[1]+"'></td>"+
					"<td><button tabindex='-1' class='btn btn-xs blue btn-edit-qty-row'><i class='fa fa-plus'></i></button></td>"+
					"</tr>";
				$('#qty-table-edit tbody').append(baris);
			}else{
				var baris = "<tr>"+
					"<td><input name='qty' value='"+parseFloat(qty[0])+"' ></td>"+
					"<td><input name='jumlah_roll' value='"+qty[1]+"'></td>"+
					"<td></td>"+
					"</tr>";
				$('#qty-table-edit tbody').append(baris);
			}

			i++;
		});

		for (var i = 0; i < 5; i++) {
			var baris = "<tr>"+
						"<td><input name='qty' value='' class='input1'></td>"+
						"<td><input name='jumlah_roll' value=''></td>"+
						"<td></td>"+
						"</tr>";

			$('#qty-table-edit tbody').append(baris);
		};

		let result = update_qty_edit('#qty-table-edit').split('=*=');
		// alert(result);
		if (parseFloat(result[0]) > 0) {
			$('.btn-brg-edit-save').attr('disabled',false);
		}else{
			$('.btn-brg-edit-save').attr('disabled',true);
		}

		$('#yard-info-edit .yard_total').html(parseFloat(result[0]));
		$('#yard-info-edit .roll_total').html(parseFloat(result[1]));

		$('#form-qty-update [name=rekap_qty]').val(result[2]);

	});


	$("#qty-table-edit").on('change',"input",function(){
    	let result = update_qty_edit('#qty-table-edit').split('=*=');
		if (parseFloat(result[0]) > 0) {
			$('.btn-brg-edit-save').attr('disabled',false);
		}else{
			$('.btn-brg-edit-save').attr('disabled',true);
		}

		$('#portlet-config-qty-edit .total_roll').html(parseFloat(result[1]));
		$('#portlet-config-qty-edit .yard_total').html(parseFloat(result[0]));

		$('#form_edit_barang [name=rekap_qty]').val(result[2]);
    	
    });

	$(".btn-brg-edit-save").click(function(){
		var form = $("#form_edit_barang");
		var tanggal = form.find("[name=tanggal]").val();
		var gudang_id = $("#gudang_id_select_edit").val();

		var barang_id = $("#barang_id_select_edit").val();
    	var warna_id = $("#warna_id_select_edit").val();
    	var rekap_qty = form.find('[name=rekap_qty]').val();

    	<?if ($batch_id != '') {?>
    		var barang_set = barang_id.split('??');
			barang_id = barang_set[0];
			warna_id = barang_set[1];
    	<?}?>

    	if (barang_id != '' && warna_id != '' && rekap_qty != '') {
			form.submit();
			btn_disabled_load($(this));
    	}else{
    		alert("Mohon lengkapi data");
    	}


	});

//====================================update harga=============================    
	
	$('#general_table').on('change','[name=harga]', function(){
		var ini = $(this).closest('tr');
		var data = {};
		data['id'] = ini.find('.id').html();
		data['harga'] = $(this).val();
		var url = "transaction/update_retur_detail_harga";
		var qty = ini.find('.qty').html();
		ajax_data_sync(url,data).done(function(data_respond  /*,textStatus, jqXHR*/ ){
			if (data_respond == 'OK') {
				var subtotal = qty*data['harga'];
				ini.find('.subtotal').html(change_number_format(subtotal));
				update_table();
			}else{
				bootbox.confirm("Error, tolong muat ulang halaman", function(respond){
					if(respond){
						window.location.reload();
					}
				});
			};
   		});		
	});

//====================================btn save=============================    


    $('.btn-save-detail').click(function(){
    	var barang_id = $("#barang_id_select").val();
    	var warna_id = $("#warna_id_select").val();
    	var qty = $("#form_add_barang [name=qty]").val();
    	var jumlah_roll = $("#form_add_barang [name=jumlah_roll]").val();

    	<?if ($batch_id != '') {?>
    		var barang_set = barang_id.split('??');
			barang_id = barang_set[0];
			warna_id = barang_set[1];
    	<?}?>

    	if (barang_id != '' && warna_id != '' && qty != '' && jumlah_roll != '') {
			$('#form_add_barang').submit();
			btn_disabled_load($(this));
    	}else{
    		alert("Mohon lengkapi data");
    	}

    });


    $('.btn-save').click(function(){
    	if ($('#form_add_data [name=tanggal]').val() != '') {
    		$('#form_add_data').submit();
    	}else{
    		alert("Mohon isi tanggal !");
    	};
    });

    $('.btn-edit-save').click(function(){
    	if ($('#form_edit_data [name=tanggal]').val() != '') {
				$('#form_edit_data').submit();
    	}else{
    		alert("Mohon isi tanggal !");
    	};
    });



//=====================================remove barang=========================================
	$('#general_table').on('click','.btn-detail-remove', function(){
		var ini = $(this).closest('tr');
		bootbox.confirm("Yakin menghapus item ini?", function(respond){
			if (respond) {
				var data = {};
				data['id'] = ini.find('.id').html();
				var url = 'transaction/retur_beli_list_detail_remove';
				ajax_data_sync(url,data).done(function(data_respond  /*,textStatus, jqXHR*/ ){
					if (data_respond == "OK") {
						// ini.remove();
						window.location.reload();
						// update_table();
					}else{
						alert("Error");
					}
				}); 
			};
		});
	}) ; 

//========================================retur beli================================

	<?if ($retur_beli_id != '') {?>
		var bayar = true;
		$('#bayar-data tr td').on('change','input', function(){
			var id_data = $(this).attr('id').split('_');

			if (bayar) {
				var data = {};
				data['pembayaran_type_id'] = id_data[1];
				data['retur_beli_id'] = '<?=$retur_beli_id?>';
				data['amount'] = $(this).val();
				
				var url = 'transaction/pembayaran_retur_update';
				ajax_data_sync(url,data).done(function(data_respond  /*,textStatus, jqXHR*/ ){
					if (data_respond == 'OK') {
						update_bayar();
						if (data['pembayaran_type_id'] == 6 ) {
							$("#portlet-config-giro").modal('toggle');
						};
					}else{
						bootbox.confirm("Error, tolong muat ulang halaman", function(respond){
							if(respond){
								window.location.reload();
							}
						});
					};
		   		});
			};
		});
	<?};?>

});

function get_qty(barang_id, warna_id, gudang_id, qty_stok, roll_stok) {
	var data = {};

	<?if ($batch_id != '') {?>
		var barang_set = barang_id.split('??');
		barang_id = barang_set[0];
		warna_id = barang_set[1];
	<?}?>

	if (barang_id != '' && warna_id != '') {
		data['barang_id'] = barang_id;
		data['warna_id'] = warna_id;
		data['gudang_id'] = gudang_id;
		var url = "transaction/get_qty_stock_by_barang";
	    // alert(data['gudang_id']);
	    ajax_data_sync(url,data).done(function(data_respond  /*,textStatus, jqXHR*/ ){
	        // alert(data_respond);
	        $.each(JSON.parse(data_respond), function(k,v){
	            // alert(v.qty);
	            qty_stok.html(parseFloat(v.qty));
	            roll_stok.html(parseFloat(v.jumlah_roll));
	        });
	        // alert(data_respond);
	        // console.log(data_respond);
	    });
	};

}

function cek_pin(){
	// alert('test');
	var data = {};
	data['pin'] = $('#pin_user').val();
	var url = 'transaction/cek_pin';
	ajax_data_sync(url,data).done(function(data_respond  /*,textStatus, jqXHR*/ ){
		if (data_respond == "OK") {
			$('#form-request-open').submit();
		}else{
			alert("PIN Invalid");
		}
	}); 
}



function update_qty_edit(table_id){
    var total = 0; var idx = 0; var rekap = [];
	var total_roll = 0;
	$(table_id+" [name=qty]").each(function(){
		var ini = $(this).closest('tr');
		var qty = $(this).val();
		var roll = ini.find('[name=jumlah_roll]').val() || 0;
		if (qty != '' && roll == '') {
			roll = 1;
		};

		var subtotal = qty*roll;
		if (qty != '' && roll != '') {
			rekap[idx] = qty+'??'+roll;
		};
		idx++; 
		// alert(roll);
		total_roll += parseInt(roll); 
		total += subtotal;
	});

	return total+'=*='+total_roll+'=*='+rekap.join('--');
}

function update_table(){
	subtotal = 0;
	$('.subtotal').each(function(){
		subtotal+= reset_number_format($(this).html());
	});

	$('.total').html(change_number_format(subtotal));
	var diskon = reset_number_format($('.diskon').val());
	var ongkir = reset_number_format($('.ongkos_kirim').val());
	var g_total = subtotal - parseInt(diskon) + parseInt(ongkir);
	$('.g_total').html(change_number_format(g_total));
	update_bayar();
}

function update_bayar(){
	var bayar = 0;
	var g_total = reset_number_format($('.g_total').html()) ;
	$('#bayar-data tr td input').each(function(){
		if ($(this).attr('class') != 'keterangan_bayar') {
			bayar += reset_number_format($(this).val());			
		};
	});

	var kembali = bayar - g_total ;
	$('.total_bayar').html(change_number_format(bayar) );
	$('.kembali').html(change_number_format(kembali));

	if (kembali < 0) {
		$('.kembali').css('color','red');
	}else{
		$('.kembali').css('color','#333');
	}

}
</script>

<?if ($retur_beli_id != '') {
	$garis1 = "'-";
	$garis2 = "=";
	foreach ($data_toko as $row) {
		$nama_toko = trim($row->nama);
		$alamat_toko = trim($row->alamat.' '.$row->kota);
		$telepon = trim($row->telepon);
		$fax = trim($row->fax);
		$npwp = trim($row->NPWP);

	}

	$alamat1 = substr(strtoupper(trim($alamat_keterangan)), 0,47);
   	$alamat2 = substr(strtoupper(trim($alamat_keterangan)), 47);
	$last_1 = substr($alamat1, -1,1);
	$last_2 = substr($alamat2, 0,1);

	$positions = array();
	$pos = -1;
	while (($pos = strpos(trim($alamat_keterangan)," ", $pos+1 )) !== false) {
		$positions[] = $pos;
	}

	$max = 47;
	if ($last_1 != '' && $last_2 != '') {
		$posisi =array_filter(array_reverse($positions),
			function($value) use ($max) {
				return $value <= $max;
			});

		$posisi = array_values($posisi);

		$alamat1 = substr(strtoupper(trim($alamat_keterangan)), 0,$posisi[0]);
	   	$alamat2 = substr(strtoupper(trim($alamat_keterangan)), $posisi[0]);
	}

	include_once 'print_retur_beli.php';
	include_once 'print_retur_beli_detail.php';
	include_once __DIR__.'/print_test.php';
}?>