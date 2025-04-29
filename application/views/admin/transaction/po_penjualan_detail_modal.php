

		<div class="modal fade" style="position:relative" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body">
						<form action="<?=base_url('transaction/po_penjualan_insert')?>" class="form-horizontal" id="form_add_data" method="post">
							<h3 class='block'> PO Penjualan Baru</h3>

							<div class="form-group customer_section">
			                    <label class="control-label col-md-4">Customer<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-7">
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
			                    <label class="control-label col-md-4">Tanggal PO/Request<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-7">
	                    			<input name='tanggal' class='form-control date-picker' id='tanggal_add' value="<?=date('d/m/Y')?>"  >
			                    </div>
			                </div>

							<div class="form-group">
			                	<!-- po_section -->
			                    <label class="control-label col-md-4">TIPE<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-7">
									<div>
										<label>
											<input onclick="disablePONumber()" type="radio" name="tipe" value="2">Request</label>
									</div>
									<div>
										<label>
											<input onclick="enablePONumber()" checked type="radio" name="tipe" value="1">PO</label>
										<input name='po_number' maxlength='100' placeholder="PO Number" id="po_number" class='form-control'>

									</div>
			                    </div>
			                </div> 

							
							<div class="form-group">
			                    <label class="control-label col-md-4">Attn.
			                    </label>
			                    <div class="col-md-7">
	                    			<input name='contact_person' class='form-control' id='contact_person_add' maxlength="50" >
			                    </div>
			                </div>

							<div class="form-group" hidden>
			                    <label class="control-label col-md-4">Harga include PPN<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-7">
									<div class="checkbox-list" style='margin-top:10px;'>
										<input checked type="checkbox" class='form-control' name="ppn_include_status" id="ppn_status_add" value='1'>
									</div>
			                    </div>
			                </div>

			                <div class="form-group" hidden >
			                    <label class="control-label col-md-4">Tanggal Kirim (optional)
			                    </label>
			                    <div class="col-md-7">
	                    			<input name='tanggal_kirim' class='form-control date-picker' value="" >
			                    </div>
			                </div> 

							<div class="form-group" hidden>
			                    <label class="control-label col-md-4">Alamat Kirim (optional)
			                    </label>
			                    <div class="col-md-7">
	                    			<textarea name='alamat_kirim' class='form-control' row='3'></textarea>
			                    </div>
			                </div> 

							<div class="form-group" hidden>
								<!-- po_section -->
								<label class="control-label col-md-4">Keterangan
								</label>
								<div class="col-md-7">
									<textarea name='keterangan' maxlength='250' class='form-control'></textarea>
								</div>
							</div> 
						</form>
					</div>

					<div class="modal-footer">
						<!-- <button type="button" class="btn blue btn-active btn-save-tab" title='Save & Buka di Tab Baru'>Save & New Tab</button> -->
						<button type="button" class="btn blue btn-active btn-trigger" id="btnAddPOPenjualan" >Save</button>
						<button type="button" class="btn btn-active default" data-dismiss="modal">Close</button>
					</div>
				</div>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
		</div>

		<div class="modal fade" style="position:relative" id="portlet-config-edit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body">
						<form action="<?=base_url('transaction/po_penjualan_update')?>" class="form-horizontal" id="form_edit_data" method="post">
							<h3 class='block'> PO Penjualan Edit</h3>

							<div class="form-group customer_section">
			                    <label class="control-label col-md-4">Customer<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-7">
									<select name="customer_id" class='form-control' id='customer_id_edit' >
										<option value=''>Pilih</option>
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
			                    </div>
			                </div>
							
							
							<div class="form-group">
								<label class="control-label col-md-4">Tanggal<span class="required">
									* </span>
			                    </label>
			                    <div class="col-md-7">
									<input name='tanggal' class='form-control date-picker' id='tanggal_edit' value="<?=date('d/m/Y', strtotime($tanggal))?>" >
			                    </div>
			                </div>

							<div class="form-group">
			                	<!-- po_section -->
			                    <label class="control-label col-md-4">TIPE<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-7">
									<input type="text" value="<?=$tipe;?>" name="tipe" hidden>
									<input type="text" id="po_number_edit" value="<?=$po_number;?>" name="po_number" hidden>
									<input type="text" disabled value="<?=($tipe==1 ? 'PO' : 'Request');?>"  class='form-control' >
			                    </div>
			                </div> 

							<div class="form-group">
			                	<!-- po_section -->
			                    <label class="control-label col-md-4">NO PO<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-7">
									<input type="text" name="po_number" <?=($tipe == 2 ? 'disabled' : '')?> value="<?=$po_number;?>"  class='form-control' >
			                    </div>
			                </div> 

							<div class="form-group">
			                    <label class="control-label col-md-4">Attn.<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-7">
	                    			<input name='contact_person' class='form-control' id='contact_person_edit' value="<?=$contact_person?>"  maxlength="50" >
			                    </div>
			                </div>

							<div class="form-group" hidden>
			                    <label class="control-label col-md-4">Harga include PPN<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-7">
									<div class="checkbox-list" style='margin-top:10px;'>
										<input type="checkbox" <?=($ppn_include_status ? 'checked' : '')?> name="ppn_include_status" id="ppn_status_edit"  value='1'>
									</div>
			                    </div>
			                </div>

			                <div class="form-group" hidden>
			                    <label class="control-label col-md-4">Tanggal Kirim (optional)
			                    </label>
			                    <div class="col-md-7">
	                    			<input name='tanggal_kirim' class='form-control date-picker' value="<?=($tanggal_kirim != '' ? date('d/m/Y', strtotime($tanggal)) : '')?>" >
			                    </div>
			                </div> 

							<div class="form-group" hidden>
			                    <label class="control-label col-md-4">Alamat Kirim (optional)
			                    </label>
			                    <div class="col-md-7">
	                    			<textarea name='alamat_kirim' class='form-control' row='3'><?=$alamat_kirim;?></textarea>
			                    </div>
			                </div> 

							<div class="form-group" hidden>
								<!-- po_section -->
								<label class="control-label col-md-4">Keterangan
								</label>
								<div class="col-md-7">
									<textarea name='keterangan' maxlength='250' class='form-control'><?=$keterangan;?></textarea>
								</div>
							</div> 

							<input hidden name="po_penjualan_id" value="<?=$po_penjualan_id;?>"/>

						</form>
					</div>

					<div class="modal-footer">
						<!-- <button type="button" class="btn blue btn-active btn-save-tab" title='Save & Buka di Tab Baru'>Save & New Tab</button> -->
						<button type="button" class="btn blue btn-active btn-trigger" id="btnEditPOPenjualan" >Save</button>
						<button type="button" class="btn btn-active default" data-dismiss="modal">Close</button>
					</div>
				</div>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
		</div>
		
		<div class="modal fade bs-modal-lg" id="portlet-config-detail" 	tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-body">
						<form action="<?=base_url('transaction/po_penjualan_detail_insert')?>" class="form-horizontal" id="form_add_barang" method="post">
							<h3 class='block'> Tambah Barang<h3>

							<div class="form-group">
			                    <label class="control-label col-md-3">Kode Barang<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<select name="barang_id" class='form-control input1' id='barang_id_select'>
		                				<option value=''>Pilih</option>
		                				<?foreach ($this->barang_list_aktif as $row) { 
		                					if ($row->status_aktif == 1) {?>
				                    			<option value="<?=$row->id?>"><?=$row->nama_jual;?></option>
		                					<?}?>
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

			                

			                <div class="form-group">
			                    <label class="control-label col-md-3">QTY<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-4">
									<input type="text" class='form-control' id='qty_add' name="qty"/>
									<span id="info_qty_add"></span>
			                    </div>
								<div class="col-md-2">
			                    	<div class='input-group'>
			                    		<input type="text" readonly class='form-control' id='satuan_add'/>
										
			                    	</div>
			                    </div>
			                </div> 

							<div class="form-group">
			                    <label class="control-label col-md-3">HARGA<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
		                			<input type="text" class='form-control ' id='harga_add' name="harga"/>
			                    </div>
			                </div> 

							<div class="form-group">
			                    <label class="control-label col-md-3">HARGA DPP<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
		                			<input readonly type="text" class='form-control ' id='harga_dpp_add'  name="harga_hpp"/>
			                    </div>
			                </div> 

							<div class="form-group" hidden>
			                    <label class="control-label col-md-3">KETERANGAN<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
		                			<textarea class='form-control ' id='keterangan_barang_add'  name="keterangan"></textarea>
			                    </div>
			                </div> 

							<input hidden name="po_penjualan_id" value="<?=$po_penjualan_id;?>"/>
						</form>
					</div>

					<div class="modal-footer">
						<button type="button" class="btn green" id="btnSubmitBarang">SAVE</button>
						<button type="button" class="btn default" data-dismiss="modal">Close</button>
					</div>
				</div>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
		</div>

		<div class="modal fade bs-modal-lg" id="portlet-config-detail-edit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-body">
						<form action="<?=base_url('transaction/po_penjualan_detail_update')?>" class="form-horizontal" id="form_edit_barang" method="post">
							<h3 class='block'> Edit Barang</h3>

							<div class="form-group">
			                    <label class="control-label col-md-3">Kode Barang<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<select name="barang_id" class='form-control input1' id='barang_id_edit'>
		                				<option value=''>Pilih</option>
		                				<?foreach ($this->barang_list_aktif as $row) {
		                					if ($row->status_aktif == 1) {?>
				                    			<option value="<?=$row->id?>"><?=$row->nama_jual;?></option>
		                					<?}?>
			                    		<? } ?>
			                    	</select>
			                    </div>
			                </div>			                

			                <div class="form-group">
			                    <label class="control-label col-md-3">Warna<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
	                    			<select name="warna_id" class='form-control' id='warna_id_edit'>
		                				<option value=''>Pilihan..</option>
			                    		<?foreach ($this->warna_list_aktif as $row) { ?>
			                    			<option value="<?=$row->id?>"><?=$row->warna_beli;?></option>
			                    		<? } ?>
			                    	</select>
			                    </div>
			                </div> 

			                

			                <div class="form-group">
			                    <label class="control-label col-md-3">QTY<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-4">
									<input type="text" class='form-control' id='qty_edit' name="qty"/>
									<span id="info_qty_edit"></span>
			                    </div>
								<div class="col-md-2">
			                    	<div class='input-group'>
			                    		<input type="text" readonly class='form-control' id='satuan_edit'/>
			                    	</div>
			                    </div>
			                </div> 

							<div class="form-group">
			                    <label class="control-label col-md-3">HARGA<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
		                			<input type="text" class='form-control ' id='harga_edit'  name="harga"/>
			                    </div>
			                </div> 
							
							<div class="form-group">
			                    <label class="control-label col-md-3">HARGA DPP<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
		                			<input readonly type="text" class='form-control ' id='harga_dpp_edit'  name="harga_hpp"/>
			                    </div>
			                </div> 

							<div class="form-group" hidden>
			                    <label class="control-label col-md-3">KETERANGAN<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
		                			<textarea class='form-control ' id='keterangan_barang_edit'  name="keterangan"></textarea>
			                    </div>
			                </div> 

							<input hidden name="po_penjualan_id" value="<?=$po_penjualan_id;?>"/>
							<input hidden id="id_edit" name="id"/>

						</form>
					</div>

					<div class="modal-footer">
						<button class="btn blue" id="btnSubmitBarangEdit">Update</button>
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
						<form action="<?=base_url('transaction/po_penjualan_lock_status');?>" class="form-horizontal" id="form-lock-open" method="post">
							<h3 class='block'> REMOVE LOCK </h3>
							
							<div class="form-group">
			                    <label class="control-label col-md-3">PIN<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<input name='po_penjualan_id' value='<?=$po_penjualan_id;?>' hidden>
									<input name='status_po' hidden value="1">
									<input name='pin' type='password' id="pinInput" maxlength='6' class="pin_user form-control">
									<!-- <p class="caption-helper">cek pin... <i class="fa fa-spin fa-cog"></i></p> -->
			                    </div>
			                </div>	
		                </form>		                
					</div>

					<div class="modal-footer">
						<button disabled type="button" id="btnOpenLockSubmit" class="btn blue">OPEN</button>
						<button type="button" class="btn default" data-dismiss="modal">Close</button>
					</div>
				</div>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
		</div>

		<div class="modal fade" style="position:relative" id="portlet-config-invoice" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body">
						<form action="<?=base_url('transaction/penjualan_list_insert')?>" class="form-horizontal" id="form_invoice" method="post">
							<h3 class='block'> <span style='color:blue'>Invoice Baru</span> dari PO <b><?=$po_number?></b></h3>

							<div class="form-group">
			                    <label class="control-label col-md-3">Type<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<div class="radio-list">
										<label class="radio-inline">
											<input checked type="radio" name="penjualan_type_id" id="penjualan_1" value="1">CASH</label>
										<label class="radio-inline">
											<input type="radio" name="penjualan_type_id" id="penjualan_2" value="2">KREDIT</label>
									</div>
			                    </div>
			                </div>

							<div class="form-group">
			                    <label class="control-label col-md-3">Customer<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
									<input readonly class='form-control' value="<?=$nama_customer?>">
	                    			<input hidden name='customer_id' value="<?=$customer_id;?>">
	                    			<input hidden name='alamat_keterangan' value="">
			                    </div>
			                </div>
							
							<div class="form-group">
			                	<!-- po_section -->
			                    <label class="control-label col-md-3">NO <?=($tipe==1 ? 'PO' : 'REQUEST');?>
			                    </label>
			                    <div class="col-md-6">
	                    			<input hidden name='po_penjualan_id' value="<?=$po_penjualan_id?>">
	                    			<input readonly name='po_number' maxlength='100' class='form-control' value="<?=$po_number?>">
			                    </div>
			                </div> 

							<div class="form-group">
			                    <label class="control-label col-md-3">TANGGAL<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
	                    			<input name='tanggal' class='form-control date-picker' id='tanggal_invoice' value="<?=date('d/m/Y')?>"  >
			                    </div>
			                </div>

						</form>
					</div>

					<div class="modal-footer">
						<!-- <button type="button" class="btn blue btn-active btn-save-tab" title='Save & Buka di Tab Baru'>Save & New Tab</button> -->
						<button type="button" class="btn blue btn-active btn-trigger" id="btnAddPenjualan" onclick="submitFormInvoice()" >Save</button>
						<button type="button" class="btn btn-active default" data-dismiss="modal">Close</button>
					</div>
				</div>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
		</div>


		<form action="<?=base_url('transaction/po_penjualan_lock_status')?>" id="form-lock" method="POST" hidden>
			<input name='po_penjualan_id' value="<?=$po_penjualan_id;?>">
			<input name='tanggal' value="<?=$tanggal;?>">
			<input name='po_number' value="<?=$po_number?>">
			<input name='tipe' value="<?=$tipe;?>">
			<input name='status_po' id="inputPOStatus" value="0">
		</form>

