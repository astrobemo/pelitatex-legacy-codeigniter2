<iframe id="print-pdf-dynamic"  src="" hidden ></iframe>

		<div class="modal fade" style="position:relative" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body">
						<form action="<?=base_url('transaction/penjualan_list_insert')?>" class="form-horizontal" id="form_add_data" method="post">
							<h3 class='block'> Penjualan Baru</h3>
							
							<div class="form-group">
			                    <label class="control-label col-md-3">Type<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<select class='form-control input1' id='penjualan-type-id-add' name='penjualan_type_id' onchange="cekCustomerLimit()">
			                    		<?foreach ($penjualan_type as $row) { ?>
			                    			<option <?if ($row->id == 3) {echo 'selected';}?> value='<?=$row->id;?>'><?=$row->text;?></option>
			                    		<?}?>
			                    	</select>
			                    </div>
			                </div>

			                <div class="form-group">
			                    <label class="control-label col-md-3">Tanggal<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
	                    			<input name='tanggal' class='form-control date-picker' id='tanggal_add' value="<?=date('d/m/Y')?>" onchange="cekPPN()" >
			                    </div>
			                </div>

			                <div class="form-group" hidden>
			                    <label class="control-label col-md-3">Jatuh Tempo<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
	                    			<input name='jatuh_tempo' class='form-control date-picker' value="<?=date('d/m/Y')?>" >
			                    </div>
			                </div> 

							<div class="form-group customer_section">
			                    <label class="control-label col-md-3">Customer<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<div id='add-select-customer'  hidden>
			                    		<select name="customer_id" class='form-control' id='customer_id_select' onchange="cekCustomerLimit()">
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
				                    	<!-- <select id='customer_id_add' hidden>
			                				<?/* foreach ($customer_with_limit as $row) {
			                					$limit_set = ($row->limit_warning_type==1 ? $row->limit_amount*($row->limit_warning_amount == 0 ? 90 : $row->limit_warning_amount )/100 : $row->limit_warning_amount ); 
			                					if ($customer_id == $row->id) {
			                						$warning_type = $row->limit_warning_type;
			                						$limit_warning_amount = $row->limit_warning_amount;
			                						$limit_amount = $row->limit_amount;
			                						$limit_atas = $row->limit_atas;
			                						$sisa_now = $limit_set - $row->sisa_piutang;
			                					}
			                					if ($row->status_aktif == 1) {?>
					                    			<option value="<?=$row->id?>"><?=($row->limit_amount == '' || $row->limit_warning_amount == 0 ? '-' :  $limit_set - $row->sisa_piutang);?></option>
			                					<?}?>
				                    		<?} */?>
				                    	</select> -->
				                    	<span class='limit-warning' hidden><b style='color:red'>limit </b><i class='fa fa-warning'></i>, input PIN untuk melanjutkan</span>
				                    	<div id='jatuh-tempo-warning' hidden>
				                    		<b style='color:red'>Jatuh Tempo </b><i class='fa fa-warning'></i>, input PIN untuk melanjutkan
				                    		<div style="width:100%; position:relative">
					                    		<div id="jatuh-tempo-rekap">
					                    		</div>
					                    		<div id="jatuh-tempo-list">
							            			<table>
							            				<thead>
							                				<tr>
							                					<th>Invoice</th>
							                					<th>Jatuh Tempo</th>
							                					<th>Nilai</th>
							                				</tr>
							            				</thead>
							            				<tbody></tbody>
							            			</table>
							            		</div>
				                    		</div>
				                    	</div>
			                    	</div>
			                    	<div id='add-nama-keterangan'>
				                    	<input name='nama_keterangan' class='form-control'>
			                    	</div>
			                    </div>
			                </div>

			                <div class="form-group">
			                	<!-- po_section -->
			                    <label class="control-label col-md-3">PO
			                    </label>
			                    <div class="col-md-6">
	                    			<select style='display:none' disable name="po_penjualan_id" class="form-control" id="po_penjualan_select"></select>
									<input id="inputPOAdd" name='po_number' maxlength='30' class='form-control' placeholder="">
			                    </div>
			                </div> 

							<?if ($is_custom_view) {?>
								<div class="form-group">
									<!-- po_section -->
									<label class="control-label col-md-3">Keterangan
									</label>
									<div class="col-md-6">
										<input name='keterangan' maxlength='50' class='form-control'>
									</div>
								</div> 
							<?}?>

			                <div class="form-group add-alamat-keterangan">
			                    <label class="control-label col-md-3">Alamat
			                    </label>
			                    <div class="col-md-6">
			                    	<input name='alamat_keterangan' class='form-control'>
			                    </div>
			                </div>

			                <div class="form-group">
			                    <label class="control-label col-md-3">FP Status
			                    </label>
			                    <div class="col-md-6">
			                    	<i class='fa fa-check' id="fp-add-true" style='display:none'></i>
									<div class="checkbox-list" hidden>
				                    	<label>
				                    	<input type='checkbox' name='fp_status' id='fp_status_add' value='1'>Ya</label>
				                    </div>
			                    </div>
			                </div>

                            <div class="form-group">
			                    <label class="control-label col-md-3">PPN
			                    </label>
			                    <div class="col-md-6" ondblclick="ubahPPN()">
									<input hidden type="text" name='ppn' id="ppn_list_add_text" value='<?=$ppn_set;?>'>
                                    <select disabled id="ppn_list_add" class='form-control' style="width:100px"  title='double click to edit'>
										<option <?=($ppn_set==0 ? 'selected' : '');?> value="0">0%</option>
										<?foreach ($ppn_list as $row) {?>
											<option <?=($ppn_set==$row->ppn ? 'selected' : '');?> value="<?=(float)$row->ppn?>" ><?=(float)$row->ppn?>%</option>
										<?}?>
									</select>
								</div>
			                </div>

							<div class="form-group pin-special" style='display:none'>
			                    <label class="control-label col-md-3">PIN <span style='color:#aaa' id='pinCheckingStatus'></span>
			                    </label>
			                    <div class="col-md-6">
			                    	<input type='password' style='border-color:red' class="pin_special form-control">
			                    </div>
			                </div>
			                
						</form>
					</div>

					<div class="modal-footer">
						<!-- <button type="button" class="btn blue btn-active btn-save-tab" title='Save & Buka di Tab Baru'>Save & New Tab</button> -->
						<button type="button" class="btn blue btn-active btn-trigger btn-save" id="btnAddPenjualan" title='Save & Buka di Tab Ini' disabled>Save</button>
						<button type="button" class="btn btn-active default" data-dismiss="modal">Close</button>
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
						<form action="<?=base_url('transaction/penjualan_list_update')?>" class="form-horizontal" id="form_edit_data" method="post">
							<h3 class='block'> Penjualan Edit</h3>
							
							<div class="form-group">
			                    <label class="control-label col-md-3">Type<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<input name='id' value='<?=$penjualan_id;?>' hidden>
			                    	<select class='form-control input1' name='penjualan_type_id' onchange="cekCustomerLimitEdit()">
			                    		<?foreach ($penjualan_type as $row) { ?>
			                    			<option <?if ($penjualan_type_id == $row->id) {echo 'selected';}?> value='<?=$row->id;?>'><?=$row->text;?></option>
			                    		<?}?>
			                    	</select>
			                    </div>
			                </div>		                

			                <div class="form-group">
			                    <label class="control-label col-md-3">Tanggal<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
	                    			<input name='tanggal' class='form-control date-picker' id='tanggal_edit' value="<?=$tanggal?>" onchange="cekPPNEdit()" >
			                    </div>
			                </div>

			                <div class="form-group"  <?=($penjualan_type_id != 2 ? 'hidden' : '' )?> >
			                    <label class="control-label col-md-3">Jatuh Tempo<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
	                    			<input name='jatuh_tempo' class='form-control date-picker' value="<?=$jatuh_tempo;?>" >
			                    </div>
			                </div> 

			                <!-- <div class="form-group">
			                    <label class="control-label col-md-3">PO
			                    </label>
			                    <div class="col-md-6">
	                    			<input name='po_number' maxlength='30' class='form-control' value="<?=$po_number;?>">
			                    </div>
			                </div>  -->

							<div class="form-group">
			                    <label class="control-label col-md-3">PO
			                    </label>
			                    <div class="col-md-6">
	                    			<select style='display:none' disable name="po_penjualan_id" class="form-control" id="po_penjualan_edit_select"></select>
									<input id="inputPOEdit" name='po_number' maxlength='30' class='form-control' placeholder="">
			                    </div>
			                </div> 

							<?if ($is_custom_view) {?>
								<div class="form-group">
									<!-- po_section -->
									<label class="control-label col-md-3">Keterangan
									</label>
									<div class="col-md-6">
										<input name='keterangan' maxlength='50' class='form-control'  value="<?=$keterangan;?>">
									</div>
								</div> 
							<?}?>

			                <div class="form-group customer_section">
			                    <label class="control-label col-md-3">Customer<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<div id='edit-select-customer'  <?if ($penjualan_type_id == 3) { ?> hidden <?}?> >
			                    		<select name="customer_id" class='form-control' id='customer_id_select_edit' onchange="cekCustomerLimitEdit()">
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
			                				<?} ?>
				                    	</select>

				                    	<!-- <select id='customer_id_edit' hidden>
			                				<?/* foreach ($customer_with_limit as $row) {
			                					$limit_set = ($row->limit_warning_type==1 ? $row->limit_amount*($row->limit_warning_amount == 0 ? 90 : $row->limit_warning_amount )/100 : $row->limit_warning_amount ); 
			                					if ($customer_id == $row->id) {
			                						$warning_type = $row->limit_warning_type;
			                						$limit_warning_amount = $row->limit_warning_amount;
			                						$limit_amount = $row->limit_amount;
			                						$limit_atas = $row->limit_atas;
			                						$sisa_now = $limit_set - $row->sisa_piutang;
			                					}
			                					if ($row->status_aktif == 1) {?>
					                    			<option value="<?=$row->id?>"><?=($row->limit_amount == '' || $row->limit_warning_amount == 0 ? '-' :  $limit_set - $row->sisa_piutang);?></option>
			                					<?}?>
				                    		<?} */?>
				                    	</select> -->
				                    	<span class='limit-warning-edit' hidden><b style='color:red'>limit </b><i class='fa fa-warning'></i>, input PIN untuk melanjutkan</span>
			                    		<div id='jatuh-tempo-warning-edit' hidden>
				                    		<b style='color:red'>Jatuh Tempo </b><i class='fa fa-warning'></i>, input PIN untuk melanjutkan
				                    		<div style="width:100%; position:relative">
					                    		<div id="jatuh-tempo-rekap-edit">
					                    		</div>
					                    		<div id="jatuh-tempo-list-edit">
							            			<table>
							            				<thead>
							                				<tr>
							                					<th>Invoice</th>
							                					<th>Jatuh Tempo</th>
							                					<th>Nilai</th>
							                				</tr>
							            				</thead>
							            				<tbody></tbody>
							            			</table>
							            		</div>
				                    		</div>
				                    	</div>
			                    	</div>
			                    	<div id='edit-nama-keterangan' <?if ($penjualan_type_id != 3) { ?> hidden <?}?> >
				                    	<input name='nama_keterangan' class='form-control' value="<?=$nama_keterangan;?>">
			                    	</div>
			                    </div>
			                </div>

			                <div class="form-group edit-alamat-keterangan" <?=($penjualan_type_id != 3 ? 'hidden' : '' );?> >
			                    <label class="control-label col-md-3">Alamat
			                    </label>
			                    <div class="col-md-6">
			                    	<textarea name='alamat_keterangan' maxlength='90' rows='4' class='form-control'><?=$alamat_keterangan;?></textarea>
			                    	<!-- <div>
				                    	<input name='alamat_keterangan' class='form-control' value="">
			                    	</div> -->
			                    </div>
			                </div>

			                <div class="form-group">
			                    <label class="control-label col-md-3">FP Status
			                    </label>
			                    <div class="col-md-6">
			                    	<i class='fa fa-check' id="fp-edit-true" style="<?=($penjualan_type_id == 3 ? 'display:none' : '');?>" ></i>
									<div class="checkbox-list" hidden>
				                    	<label>
				                    	<input type='checkbox' readonly <?=($fp_status == 1 ? 'checked' : '');?> name='fp_status' id='fp_status_edit' value='1'>Ya</label>
				                    </div>
			                    </div>
			                </div>

                            <div class="form-group">
			                    <label class="control-label col-md-3">PPN
			                    </label>
			                    <div class="col-md-6" ondblclick="ubahPPNEdit()">
                                    <input id="ppn_list_edit_text" value="<?=(float)$ppn?>" name='ppn' hidden >
									<select name="ppn" disabled id="ppn_list_edit" class='form-control' style="width:100px" title='double click to edit'>
										<option <?=($ppn==0 ? 'selected' : '');?> value="0">0%</option>
										<?foreach ($ppn_list as $row) {?>
											<option <?=($ppn==$row->ppn ? 'selected' : '');?> value="<?=(float)$row->ppn?>" ><?=(float)$row->ppn?>%</option>
										<?}?>
									</select>
								</div>
			                </div>
			                
							<div class="form-group pin-special-edit" style='display:none'>
			                    <label class="control-label col-md-3">PIN <span style='color:#aaa' id='pinCheckingStatusEdit'></span>
			                    </label>
			                    <div class="col-md-6">
			                    	<input type='password' style='border-color:red' class="pin_special_edit form-control">
			                    </div>
			                </div>

			                
						</form>
					</div>

					<div class="modal-footer">
						<button type="button" class="btn blue btn-active btn-trigger btn-edit-save">Save</button>
						<button type="button" class="btn btn-active default" data-dismiss="modal">Close</button>
					</div>
				</div>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
		</div>
		
		<div class="modal fade bs-modal-lg" id="portlet-config-detail" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-body">
						<form action="<?=base_url('transaction/penjualan_list_detail_insert')?>" class="form-horizontal" id="form_add_barang" method="post">
							<h3 class='block'> Tambah Barang
							<b class='warning-limit' style='color:red' hidden><i class='fa fa-warning'></i> LIMIT</b></h3>
							
							<div class="form-group">
			                    <label class="control-label col-md-3">Lokasi Barang <span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<input name='penjualan_id' value='<?=$penjualan_id;?>' hidden>
			                    	<input name='po_penjualan_id' value='<?=$po_penjualan_id;?>' hidden>
			                    	<input name='penjualan_detail_id' value='' hidden>
			                    	<input name='tanggal' value='<?=$tanggal;?>' hidden>
	                    			<select name="gudang_id" class='form-control' id='gudang_id_select'>
		                				<?foreach ($this->gudang_list_aktif as $row) { ?>
			                    			<option value="<?=$row->id?>"><?=$row->nama;?></option>
			                    		<? } ?>
			                    	</select>
			                    </div>
			                </div>

							<div id='barang-group-baru'>
								<div class="form-group">
									<label class="control-label col-md-3">Kode Barang<span class="required">
									* </span>
									</label>
									<div class="col-md-6">
										<select name="barang_id" class='form-control input1' id='barang_id_select'>
											<option value=''>Pilih</option>
											<?foreach ($barang_list as $row) {?>
												<option value="<?=$row->id?>"><?=$row->nama_jual;?></option>
											<? } ?>
										</select>
										<select name='data_barang' hidden>
											<?foreach ($barang_list as $row) { ?>
												<option value="<?=$row->id?>"><?=$row->nama_satuan;?>??<?=(float)$row->harga_jual;?>??<?=$row->tipe_qty?></option>
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
							</div>
							
							<div class="form-group" id='barang-po-baru'>
								<label class="control-label col-md-3">Barang PO<span class="required">
								* </span>
								</label>
								<div class="col-md-6">
									<!-- <input type="text" name="barang_id" id='inputBarangPO' hidden>
									<input type="text" name="warna_id" id='inputWarnaPO' hidden > -->
									<select id="barangPOSelect" class='form-control input1' onchange="setBarangPO('1')">
										<option value=''>Pilih</option>
										<?foreach ($po_penjualan_barang as $row) {?>
											<option value="<?=$row->id;?>"><?=$row->nama_barang?> <?=$row->nama_warna?></option>
										<? } ?>
									</select>
								</div>
							</div>

							
							<?if ($is_custom_view) {?>
								
								<div class="form-group">
									<label class="control-label col-md-3">Kode Beli<span class="required">
									* </span>
									</label>
									<div class="col-md-6">
										<input name="kode_beli" maxlength='15'  class='form-control' id='kode_beli'>
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-md-3">Nama Jual<span class="required">
									* </span>
									</label>
									<div class="col-md-6">
										<input name="nama_jual_tercetak"  class='form-control nama-tercetak' id='nama_jual_tercetak'>
									</div>
								</div>
							<?}?>

			                <div class="form-group">
			                    <label class="control-label col-md-3">Satuan<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
		                			<input readonly type="text" class='form-control' name="satuan" id="satuan_add" />
			                    </div>
			                </div> 

			                <div class="form-group">
			                    <label class="control-label col-md-3">Harga Jual<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<div class='input-group'>
			                    		<input type="text" class='amount_number form-control' id='harga_jual_add' name="harga_jual"/>
			                			<span class="input-group-btn" >
											<a data-toggle="popover" class='btn btn-md default btn-cek-harga' data-trigger='click' title="History Pembelian Customer" data-html="true" data-content="<div id='data-harga'>loading...</div>"><i class='fa fa-search'></i></a>
										</span>
				                    	<input name='rekap_qty' hidden>

			                    	</div>
			                    </div>
			                </div> 
						</form>
					</div>

					<div class="modal-footer">
						<button type="button" class="btn blue btn-add-qty">Add Qty</button>
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
						<table>
							<tr>
								<td>
									<table id='qty-table'>
										<thead>
											<tr>
												<th>Yard</td>
												<th>Roll</td>
												<th id='stok-roll-info' hidden></th>
												<th></th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td><input name='qty' class='input1' tabindex='1'  autocomplete='off'></td>
												<td><input name='jumlah_roll'  tabindex='2' autocomplete='off'></td>
												<td style='padding:0 10px'></td>
												<td><button tabindex='-1' class='btn btn-xs blue btn-add-qty-row'><i class='fa fa-plus'></i></button></td>
											</tr>
											<?for ($i=0; $i < 8 ; $i++) { ?>
												<tr>
													<td><input name='qty' tabindex='<?=2*($i+1) + 1;?>' autocomplete='off'></td>
													<td><input name='jumlah_roll'  tabindex='<?=2*($i+1) + 2;?>' autocomplete='off'></td>
													<td style='padding:0 10px'></td>
													<td></td>
												</tr>
											<?}?>
										</tbody>
									</table>
								</td>
								<td class='text-right'>
									<div id='stok-info' style='border:1px solid #ddd; padding:5px; margin-bottom:20px;'>
										STOK QTY : <span class='stok-qty'>0</span><br/>
										STOK ROLL : <span class='stok-roll'>0</span>
										<div <?=(is_posisi_id() <= 3 ? '' : 'hidden')?> >
											<!-- <button class='btn btn-green' onclick="getAllStock()">AMBIL SEMUA</button> -->
										</div>
									</div>
									<div>
										<table id='qty-table-detail'></table>
									</div>
								</td>
							</tr>
						</table>
						<div class='yard-info' id="qty-info-add">
							TOTAL QTY: <span class='yard_total' >0</span> yard <br/>
							TOTAL ROLL: <span class='jumlah_roll_total' >0</span> 
						</div>

						

					</div>

					<div class="modal-footer">
						<button disabled type="button" class="btn blue btn-active btn-trigger btn-brg-save">Save</button>
						<button type="button" class="btn btn-active default" data-dismiss="modal">Close</button>
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
						<form action="<?=base_url('transaction/penjualan_list_detail_update')?>" class="form-horizontal" id="form_edit_barang" method="post">
							<h3 class='block'> Edit Barang</h3>
							
							<div class="form-group">
			                    <label class="control-label col-md-3">Lokasi Barang<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<input name='penjualan_id' value='<?=$penjualan_id;?>' <?=$hidden_spv;?> >
			                    	<input name='penjualan_detail_id' <?=$hidden_spv;?> >
			                    	<input name='tanggal' value='<?=$tanggal;?>' hidden>
			                    	<input name='gudang_id_ori' hidden>
	                    			<select name="gudang_id" class='form-control' id='gudang_id_select_edit'>
		                				<?foreach ($this->gudang_list_aktif as $row) { ?>
			                    			<option value="<?=$row->id?>"><?=$row->nama;?></option>
			                    		<? } ?>
			                    	</select>
			                    </div>
			                </div> 

							<div id='barang-group-edit'>
								<div class="form-group">
									<label class="control-label col-md-3">Kode Barang<span class="required">
									* </span>
									</label>
									<div class="col-md-6">
										<select name="barang_id" class='form-control input1' id='barang_id_select_edit'>
											<option value=''>Pilih</option>
											<?foreach ($this->barang_list_aktif as $row) { ?>
												<option value="<?=$row->id?>"><?=$row->nama_jual;?></option>
											<? } ?>
										</select>
										<select name='data_barang' hidden>
											<?foreach ($this->barang_list_aktif as $row) { ?>
												<option value="<?=$row->id?>"><?=$row->nama_satuan;?>??<?=$row->harga_jual;?>??<?=$row->tipe_qty;?></option>
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
							</div>
							

							<div class="form-group" id="barang-po-edit" >
								<label class="control-label col-md-3">Barang PO<span class="required">
								* </span>
								</label>
								<div class="col-md-6">
									<input readonly value="" class='form-control' id='namaBarangPO'>
								</div>
							</div>


							<?if ($is_custom_view) {?>
								<div class="form-group">
									<label class="control-label col-md-3">Kode Beli<span class="required">
									* </span>
									</label>
									<div class="col-md-6">
										<input name="kode_beli" maxlength='15' class='form-control nama-tercetak' id='kode_beli_edit'>
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-md-3">Nama Jual<span class="required">
									* </span>
									</label>
									<div class="col-md-6">
										<input name="nama_jual_tercetak" class='form-control nama-tercetak' id='nama_jual_tercetak_edit'>
									</div>
								</div>
							<?}?>


			                <div class="form-group">
			                    <label class="control-label col-md-3">Satuan<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
		                			<input readonly type="text" class='form-control' name="satuan" id="satuan_edit" />
			                    </div>
			                </div> 

			                <div class="form-group">
			                    <label class="control-label col-md-3">Harga Jual<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<div class='input-group'>
			                    		<input type="text" class='amount_number form-control' id='harga_jual_edit' name="harga_jual"/>
			                			<span class="input-group-btn" >
											<a data-toggle="popover" class='btn btn-md default btn-cek-harga' data-trigger='click' title="History Pembelian Customer" data-html="true" data-content="<div id='data-harga'>loading...</div>"><i class='fa fa-search'></i></a>
										</span>
				                    	<input name='rekap_qty' hidden>

			                    	</div>
			                    </div>
			                </div> 
							<input name='rekap_qty' <?=(is_posisi_id() != 1 ? 'hidden' : '' ); ?> >

						</form>
					</div>

					<div class="modal-footer">
						<a href="#portlet-config-qty-edit" data-toggle="modal" class="btn blue btn-qty-edit">Edit Qty</a>
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
						<div style='height:400px;overflow:auto'>
							<table>
								<tr>
									<td>
										<table id='qty-table-edit'>
											<thead>
												<tr>
													<th>No</th>
													<th>Yard</td>
													<th>Roll</td>
													<th id='stok-roll-info-edit'>STOK</th>
													<th></th>
												</tr>
											</thead>
											<tbody>

											</tbody>
										</table>
									</td>
									<td>
										<td class='text-right'>
											<div id='stok-info-edit' style='border:1px solid #ddd; padding:5px; margin-bottom:20px;'>
												STOK QTY : <span class='stok-qty'>0</span><br/>
												STOK ROLL : <span class='stok-roll'>0</span>
											</div>
											<div>
												<table id='qty-table-detail-edit'></table>
											</div>
										</td>
									</td>
								</tr>
							</table>
							
						</div>
						<span class='total_roll' hidden></span>
						<div class='yard-info' id="qty-info-edit">
							TOTAL : <span class='yard_total' >0</span> yard <br/>
							TOTAL ROLL : <span class='jumlah_roll_total' >0</span>
						</div> 
						<!-- <form hidden action="<?=base_url()?>transaction/penjualan_qty_update" id='form-qty-update' method="post">
							<input name='id'>
							<input name='rekap_qty'>
						</form> -->
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
							
							<div class="form-group">
			                    <label class="control-label col-md-3">No Faktur<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
									<input type="hidden" name='id' id="search_no_faktur" class="form-control select2">
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

		<div class="modal fade" id="portlet-config-alamat" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body">
						<form action="<?=base_url();?>transaction/penjualan_print" class="form-horizontal" id="form_print_sj" method="get">
							<h3 class='block'> Pilih Alamat Kirim</h3>
							
							<input name='tipe_sj' id='tipe_sj' hidden>
                              <input name='tanggal' value="<?= $tanggal; ?>" hidden>
							<input name='penjualan_id' value="<?=$penjualan_id;?>" hidden>
							<div class="form-group">
			                    <label class="control-label col-md-3">Alamat kirim<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    </div>
			                </div>	
		                </form>              
					</div>

					<div class="modal-footer">
						<button type="button" class="btn blue btn-print-sj" onclick="printRAWPrint('sj')" data-dismiss="modal">Print</button>
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
						<form action="<?=base_url('transaction/penjualan_request_open');?>" class="form-horizontal" id="form-request-open" method="post">
							<h3 class='block'> Request Open</h3>
							
							<div class="form-group">
			                    <label class="control-label col-md-3">PIN<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<input name='penjualan_id' value='<?=$penjualan_id;?>' hidden>
									<input name='pin' type='password' class="pin_user" class="form-control">
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

		<div class="modal fade" id="portlet-config-pin-limit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body">
						<form action="#" class="form-horizontal" id="form-request-submit-limit" method="post">
							<h3 class='block'> Request Open</h3>
							
							<div class="form-group">
			                    <label class="control-label col-md-3">PIN<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<input name='penjualan_id' value='<?=$penjualan_id;?>' hidden>
									<input name='pin' type='password' class="pin_user form-control">
			                    </div>
			                </div>	
		                </form>		                
					</div>

					<div class="modal-footer">
						<button type="button" class="btn blue btn-request-submit-limit">LOCK</button>
						<button type="button" class="btn default" data-dismiss="modal">Close</button>
					</div>
				</div>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
		</div>

		<div class="modal fade" id="portlet-config-posisi-barang" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body">
						<form action="<?=base_url()?>transaction/penjualan_list_close" class="form-horizontal" id="form-posisi-barang" method="post">
							<h3 class='block'> Konfirmasi</h3>
							<div class='note note-danger'>Status Barang</div>
							
									<input name='id' value="<?=$penjualan_id?>" hidden >
									<input name='tanggal' value="<?=$ori_tanggal?>" hidden >
				                    <div class="radio-list">
										<div class='row'>
											<div class='col-md-6' style='border-right:1px solid #ddd'>
												<label>
												<input type="radio" name="tipe_ambil_barang_id" value="1" checked > Langsung Diambil </label> <br/>
												<label>
												<input type="radio" name="tipe_ambil_barang_id" value="2" > Hari ini </label> <br/>
												<label>
												<input type="radio" name="tipe_ambil_barang_id" value="3" > Besok </label> <br/>
												<label>
												<input type="radio" name="tipe_ambil_barang_id" value="4" >  Tanggal : <input type="text" style='width:100px' name='tanggal_ambil' class='date-picker'> </label>
											</div>
											<div class='col-md-6'>
												<label>
												<input type="radio" name="tipe_ambil_barang_id" value="5" > Dikirim Tanggal : <input type="text" style='width:100px' name='tanggal_kirim' class='date-picker'></label>
												<textarea name='alamat_pengiriman' placeholder='Alamat Pengiriman' class='form-control' row='4'></textarea>
											</div>
										</div>					
				                    </div>
							
		                </form>		                
					</div>

					<div class="modal-footer">
						<button type="button" class="btn blue btn-close-ok">OK</button>
						<button type="button" class="btn default" data-dismiss="modal">Close</button>
					</div>
				</div>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
		</div>

		<div class="modal fade" id="portlet-config-sj" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body">
						<form action="<?=base_url()?>transaction/penjualan_list_new_sj" class="form-horizontal" id="form-new-sj" method="post">
							<h3 class='block'> Surat Jalan Baru</h3>
							<div class="form-group">
			                    <label class="control-label col-md-3">Tanggal<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<input name='penjualan_id' hidden value='<?=$penjualan_id;?>' <?=$hidden_spv;?> >
			                    	<input name='tanggal' class="form-control date-picker" value='<?=$tanggal;?>' >
			                    </div>
			                </div>
			                <div class="form-group">
			                    <label class="control-label col-md-3">Keterangan<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<input name='keterangan' class="form-control" value='<?=$tanggal;?>' >
			                    </div>
			                </div>

			                <div>
			                	<table id='general-detail-posisi' class='table table-bordered'>
			                		<tr>
			                			<th>Nama Barang</th>
			                			<th class='text-center'>QTY</th>
			                			<th class='text-center'>TOTAL</th>
			                		</tr>
				                	<?foreach ($penjualan_detail as $row) {
										$data_qty = explode('--', $row->data_qty);?>
										<tr>
											<td><?=$row->nama_barang?> <?=$row->nama_warna?></td>
											<td class='text-center'>
												<?foreach ($data_qty as $key => $value) {
													$qty = explode('??', $value);?>
														<span style='display:inline-block;width:25px;'><?=(float)$qty[0]?></span>  x <input style='width:25px; text-align:center; border:none; border-bottom:1px solid #ddd' value='<?=$qty[1]?>' ><br/>
												<?}?>		
											</td>
											<td class='text-center'><?=str_replace('.00', '',$row->qty)?></td>
										</tr>
									<?}?>
			                	</table>
			                </div>
		                </form>		                
					</div>

					<div class="modal-footer">
						<button type="button" class="btn blue btn-new-sj-ok">OK</button>
						<button type="button" class="btn default" data-dismiss="modal">Close</button>
					</div>
				</div>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
		</div>

		<div class="modal fade" id="portlet-config-sj-print" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body">
						<h3 class='block'> Surat Jalan</h3>
						<div style='font-size:1.5em'>
							<table>
								<tr>
									<td>Tanggal</td>
									<td> : </td>
									<td>18/12/2019</td>
								</tr>
								<tr>
									<td>Keterangan</td>
									<td> : </td>
									<td>Diambil oleh Pa Didi</td>
								</tr>
							</table>
						</div>
						<br/>
		                <div>
		                	<table id='general-detail-posisi' class='table table-bordered'>
		                		<tr>
		                			<th>Nama Barang</th>
		                			<th class='text-center'>QTY</th>
		                			<th class='text-center'>TOTAL</th>
		                		</tr>
			                	<?foreach ($penjualan_detail as $row) {
									$data_qty = explode('--', $row->data_qty);?>
									<tr>
										<td><?=$row->nama_barang?> <?=$row->nama_warna?></td>
										<td class='text-center'>
											<?foreach ($data_qty as $key => $value) {
												$qty = explode('??', $value);?>
													<span style='display:inline-block;width:25px;'><?=(float)$qty[0]?></span>  x 2 <br/>
											<?}?>		
										</td>
										<td class='text-center'>200</td>
									</tr>
								<?}?>
		                	</table>
		                </div>	                
					</div>

					<div class="modal-footer">
						<button type="button" class="btn blue btn-new-sj-print">PRINT</button>
						<button type="button" class="btn default" data-dismiss="modal">Close</button>
					</div>
				</div>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
		</div>

		<div class="modal fade" id="portlet-config-giro" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body">
						<form action="<?=base_url('transaction/penjualan_detail_giro');?>" class="form-horizontal" id="form-data-giro" method="post">
							<h3 class='block'> Detail Giro</h3>
							
							<div class="form-group">
			                    <label class="control-label col-md-3">Nama Bank<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<input name='penjualan_id' value='<?=$penjualan_id;?>' hidden>
									<input name='nama_bank' class="form-control" value='<?=$nama_bank;?>'>
			                    </div>
			                </div>	
			                <div class="form-group">
			                    <label class="control-label col-md-3">No Rekening<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<input name='no_rek_bank' class="form-control"  value='<?=$no_rek_bank;?>'>
			                    </div>
			                </div>	
			                <div class="form-group">
			                    <label class="control-label col-md-3">No Akun GIRO<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<input name='no_akun' class="form-control" value='<?=$no_akun;?>'>
			                    </div>
			                </div>	
			                <div class="form-group">
			                    <label class="control-label col-md-3">Tanggal Giro<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<input name='tanggal_giro' class="form-control date-picker" value='<?=$tanggal_giro;?>'>
			                    </div>
			                </div>
			                <div class="form-group">
			                    <label class="control-label col-md-3">Jatuh Tempo<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<input name='jatuh_tempo' class="form-control date-picker"  value='<?=$jatuh_tempo_giro;?>'>
			                    </div>
			                </div>	

		                </form>		                
					</div>

					<div class="modal-footer">
						<button type="button" class="btn blue btn-save-giro">SAVE</button>
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
		                    		<?foreach ($printer_list as $row) { 
		                    			$default_printer = (get_default_printer() == $row->id ? $row->nama : '');
		                    			?>
		                    			<option  <?=(get_default_printer() == $row->id ? 'selected' : '');?> value='<?=$row->id;?>'><?=$row->nama;?> <?//=(get_default_printer() == $row->id ? '(default)' : '');?></option>
		                    		<?}?>
		                    	</select>
		                    	<div class='note note-info' hidden>
			                    	Ubah default printer Anda di <a target='_blank' href="<?=base_url().is_setting_link('admin/change_default_printer');?>">Setting <i class='fa fa-arrow-right'></i> Ubah Default Printer</a>
		                    	</div>
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

		

		<div class="modal fade" id="portlet-config-dp" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body">
						<b style='font-size:2em'>DAFTAR DP</b><hr/>
						<table class="table table-striped table-bordered table-hover" id='dp_list_table'>
							<thead>
								<tr>
									<th scope="col">
										Tanggal
									</th>
									<th scope="col">
										Deskripsi
									</th>
									<th scope="col">
										No Transaksi DP
									</th>
									<th scope="col">
										Nilai
									</th>
									<th scope="col">
										Dibayar
									</th>
									<th scope="col" style="min-width:150px !important">
										Actions
									</th>
								</tr>
							</thead>
							<tbody>
								<form id='form-dp' action="<?=base_url('transaction/pembayaran_penjualan_dp_update');?>" method="POST">
									<?
									$dp_bayar = 0; $saldo_dp=0;
									foreach ($dp_list_detail as $row) { ?>
										<tr>
											<td>
												<?=is_reverse_date($row->tanggal);?>
											</td>
											<td>
												<?=$row->bayar_dp;?> : <a class='dp-more-info'>info <i class='fa fa-angle-down'></i></a>
												<?
												$type_2 = '';
												$type_3 = '';
												$type_4 = '';
												$type_6 = '';
												${'type_'.$row->pembayaran_type_id} = 'hidden';
												?>
												<ul hidden>
													<li <?=$type_3;?> <?=$type_4;?> <?=$type_6;?> >Penerima :<b><span class='nama_penerima' ><?=$row->nama_penerima?></span></b></li>
													<li <?=$type_2;?> >Bank : <b><span class='nama_bank'><?=$row->nama_bank?></span></b></li>
													<li <?=$type_2;?> <?=$type_6;?> >No Rek : <b><span class='no_rek_bank'><?=$row->no_rek_bank?></span></b></li>
													<li <?=$type_3;?> <?=$type_4;?> <?=$type_2;?>>Jatuh Tempo : <b><span class='jatuh_tempo' ><?=is_reverse_date($row->jatuh_tempo);?></span></b></li>
													<li <?=$type_3;?> <?=$type_4;?> <?=$type_2;?> >No Giro : <b><span class='no_giro' ><?=$row->no_giro;?></span></b></li>
													<li>Keterangan : <b><span class='keterangan'><?=$row->keterangan;?></span></li></b>

												</ul>
											</td>
											<td>
												<span class='no_faktur_lengkap'><?=$row->no_faktur_lengkap;?></span>
											</td>
											<td>
												<span class='amount'><?=number_format($row->amount,'0',',','.'); $saldo_dp += $row->amount;?></span>
											</td>
											<td>
												<?$dp_bayar += $row->amount_bayar; $saldo_dp -= $row->amount_bayar?>
												<input name="amount_<?=$row->id;?>" class='amount-bayar amount-number' value='<?=number_format($row->amount_bayar,'0',',','.');?>' <?=($row->amount_bayar == 0 ? 'readonly' : '');?> style="width:80px">
											</td>
											<td>
												<input name="penjualan_id" value="<?=$penjualan_id;?>" hidden>
												<span class='id' hidden="hidden"><?=$row->id;?></span>
												<input type="checkbox" class='dp-check' <?=($row->amount_bayar != 0 ? 'checked' : '');?> >
											</td>
										</tr>
									<? } ?>
									<tr style='font-size:1.3em'>
										<td colspan='3'></td>
										<td><b>BAYAR DP</b></td>
										<td><span class='dp-total' ><?=number_format($dp_bayar,'0',',','.');?></span></td>
										<td></td>
									</tr>
									<tr style='font-size:1.3em'>
										<td colspan='3'></td>
										<td><b>SISA BON</b></td>
										<td><span class='dp-nilai-bon-info' ></span></td>
										<td></td>
									</tr>
									<tr style='font-size:1.3em'>
										<td colspan='3'></td>
										<td><b>SISA</b></td>
										<td><span class='dp-sisa-info' ></span></td>
										<td></td>
									</tr>
								</form>

							</tbody>
						</table>
					</div>

					<div class="modal-footer">
						<button type="button" class="btn btn-active green btn-save-dp" >Save</button>
						<button type="button" class="btn btn-active default" data-dismiss="modal">Close</button>
					</div>
				</div>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
		</div>

		<!-- ///////////////////////////////////////////////// -->
		<!-- map -->
		<!-- ///////////////////////////////////////////////// -->
		<div class="modal fade" id="portlet-config-address" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
					<form action="<?= base_url('transaction/jual_kirim_insert'); ?>" class="form-horizontal" id="form_cetak_sj" method="post">
						<div class="modal-content">
								<div class="modal-header">
									<h3>Pilih Alamat Kirim</h3>
									<input name='tanggal' value="<?= $tanggal; ?>" hidden>
									<input name='penjualan_id' value="<?= $penjualan_id; ?>" hidden>
									<input id="isCustomPrint" name='is_custom_print' value="0" hidden>
								</div>

								<div class="modal-body">
									<div class="form-group">
											<label class="control-label col-md-3">Alamat Kirim
												<span class="required">*</span>
											</label>

											<div class="col-md-6">
												<select id="alamat_kirim_id" name="alamat_kirim_id" class="form-control">
														<option value="">Sama dengan bon</option>

														<?php foreach ($alamat_kirim as $row) { ?>

														<option value='<?= $row->id ?>'><?= $row->alamat; ?></option>

														<?php } ?>
												</select>
											</div>
									</div>
								</div>

								<div class="modal-footer">
									<!-- <button type="button" id='cetak-sj' class="btn blue btn-active btn-trigger btn-cetak pull-right" data-dismiss="modal">Cetak</button> -->
									<button type="button" id='cetak-invoice-sj' class="btn blue btn-active btn-trigger btn-invoice-cetak pull-right" data-dismiss="modal">Cetak SJ </button>
									<button type="button" id='cetak-invoice-sj-no-harga' class="btn blue btn-active btn-trigger btn-invoice-cetak pull-right" data-dismiss="modal">Cetak SJ NON HARGA </button>

									<!--<a href="#portlet-config-printHTML" data-toggle="modal" class="btn blue btn-active btn-trigger btn-invoice-cetak pull-right" onclick="webPrintHTML('3')" data-dismiss="modal">Cetak SJ </a>
									<a href="#portlet-config-printHTML" data-toggle="modal" class="btn blue btn-active btn-trigger btn-invoice-cetak pull-right" onclick="webPrintHTML('4')" data-dismiss="modal">Cetak SJ NON HARGA </a>-->
									<button type="button" class="btn default btn-active pull-right" data-dismiss="modal">Close</button>
								</div>
						</div>
					</form>
			</div>
		</div>

		<!-- non harga -->
		<div class="modal fade" id="portlet-config-address-non" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
					<form action="<?= base_url('transaction/jual_kirim_insert'); ?>" class="form-horizontal" id="form_cetak_sj_non" method="post">
						<div class="modal-content">
								<div class="modal-header">
									<h3>Pilih Alamat Kirim</h3>

									<input name='tanggal' value="<?= $tanggal; ?>" hidden>
									<input name='penjualan_id' value="<?= $penjualan_id; ?>" hidden>
									<input name='is_custom_print' value="0" hidden>
								</div>

								<div class="modal-body">
									<div class="form-group">
											<label class="control-label col-md-3">Alamat Kirim
												<span class="required">*</span>
											</label>

											<div class="col-md-6">
												<select id="alamat_kirim_id_non" name="alamat_kirim_id" class="form-control">
														<option value="">Sama dengan bon</option>

														<?php foreach ($alamat_kirim as $row) { ?>

														<option value='<?= $row->id ?>'><?= $row->alamat; ?></option>

														<?php } ?>
												</select>
											</div>
									</div>
								</div>

								<div class="modal-footer">
									<button type="button" class="btn blue btn-active btn-trigger btn-cetak-non pull-right" data-dismiss="modal">Cetak</button>
									<button type="button" class="btn default btn-active pull-right" data-dismiss="modal">Close</button>
								</div>
						</div>
					</form>
			</div>
		</div>

		<!-- printHTML -->
		<div class="modal fade bs-modal-lg" id="portlet-config-printHTML" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-body">
						<iframe id="frameprintHTML" style="width:100%" height="450px" frameborder="0"></iframe>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn default btn-active pull-right" data-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>