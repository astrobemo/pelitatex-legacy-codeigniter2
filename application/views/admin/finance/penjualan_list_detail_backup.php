<link href="<?=base_url('assets/global/plugins/bootstrap-modal/css/bootstrap-modal.css');?>" rel="stylesheet" type="text/css"/>

<!-- <link href="<?=base_url('assets_noondev/css/bootstrap-modal-bs3patch.css');?>" rel="stylesheet" type="text/css"/> -->
<?php echo link_tag('assets/global/plugins/bootstrap-datepicker/css/datepicker3.css'); ?>

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

.yard-info{
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

.textarea{
	resize:none;
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

.alamat{
	overflow: hidden;
	text-overflow:ellipsis;
	width: 150px;
}

</style>

<div class="page-content">
	<div class='container'>
		<?
			$status_aktif  ='';
			$penjualan_id = '';
			$customer_id = '';
			$nama_customer = '';
			$gudang_id = '';
			$nama_gudang = '';
			$no_faktur = '';
			$tanggal = date('d/m/Y');
			$tanggal_print = '';
			$ori_tanggal = '';
			$po_number = '';
			$note_info = ''; 

			$jatuh_tempo = date('d/m/Y', strtotime("+60 days") );
			$ori_jatuh_tempo = '';
			$status = -99;

			$diskon = 0;
			$ongkos_kirim = 0;
			$nama_keterangan = '';
			$alamat_keterangan = '';
			$kota = '';
			$keterangan = '';
			$penjualan_type_id = 3;
			$tipe_penjualan = '';
			$customer_id = '';
			$no_faktur_lengkap = '';
			$no_surat_jalan = '';
			$fp_status = 1;

			$g_total = 0;
			$readonly = '';
			$disabled = '';
			$alamat_customer = '';
			$disabled_status = '';
			$bg_info = '';

			$hidden_spv = '';
			if (is_posisi_id() != 1) {
				$hidden_spv = 'hidden';
			}

			foreach ($penjualan_data as $row) {
				$tipe_penjualan = $row->tipe_penjualan;
				$penjualan_id = $row->id;
				$customer_id = $row->customer_id;
				$nama_customer = $row->nama_keterangan;
				$alamat_customer = $row->alamat_keterangan;
				$gudang_id = $row->gudang_id;
				$nama_gudang = $row->nama_gudang;
				$no_faktur = $row->no_faktur;
				$penjualan_type_id = $row->penjualan_type_id; 
				$po_number = $row->po_number;
				$fp_status = $row->fp_status;
				$iClass = '';
				
				$tanggal_print = date('d F Y', strtotime($row->tanggal));

				$tanggal = is_reverse_date($row->tanggal);
				$ori_tanggal = $row->tanggal;
				$status_cek = 0;

				if ($penjualan_type_id == 1) {
					$note_info = "note note-info";
					$bg_info = "background: #95a5a6";

				}elseif ($penjualan_type_id == 2) {
					$note_info = "note note-warning";
					$bg_info = "background: #d35400";
				}elseif ($penjualan_type_id == 3) {
					$note_info = "note note-success";
					$bg_info = "background: #2980b9";
				}


				if ($penjualan_type_id == 2) {
					$dt = strtotime(' +'.get_jatuh_tempo($customer_id).' days', strtotime($row->tanggal) );
					if ($row->jatuh_tempo == $row->tanggal) {
						$status_cek = 1;
					}
				}
				$get_jt = ($row->jatuh_tempo == '' || $status_cek == 1  ? date('Y-m-d',$dt) : $row->jatuh_tempo);
				// print_r($get_jt);
				$jatuh_tempo = is_reverse_date($get_jt);
				$ori_jatuh_tempo = $row->jatuh_tempo;
				$status = $row->status;
				
				$diskon = $row->diskon;
				$ongkos_kirim = $row->ongkos_kirim;
				$status_aktif = $row->status_aktif;
				$nama_keterangan = $row->nama_keterangan;
				$alamat_keterangan = $row->alamat_keterangan;
				$kota = $row->kota;
				$keterangan = $row->keterangan;
				$customer_id = $row->customer_id;
				$no_faktur_lengkap = $row->no_faktur_lengkap;
				$no_surat_jalan = $row->no_surat_jalan;

				if ($status_aktif == -1 ) {
					$note_info = 'note note-danger';
				}

			}

			$nama_bank = '';
			$no_rek_bank = '';
			$tanggal_giro = '';
			$jatuh_tempo_giro = '';
			$no_akun = '';

			foreach ($data_giro as $row) {
				$nama_bank = $row->nama_bank;
				$no_rek_bank = $row->no_rek_bank;
				$tanggal_giro =is_reverse_date($row->tanggal_giro) ;
				$jatuh_tempo_giro = is_reverse_date($row->jatuh_tempo);
				$no_akun = $row->no_akun;
			}

			if ($status != 1) {
				if ( is_posisi_id() != 1 ) {
					$readonly = 'readonly';
				}
			}

			if ($penjualan_id == '') {
				$disabled = 'disabled';
			}

			if ($status != 0) {
				$disabled_status = 'disabled';
			}

			$lock_ = '';
			$read_ = '';
			if (is_posisi_id() == 6) {
				$disabled = 'disabled';
				$readonly = 'readonly';
			}
		?>


		<div class="modal fade" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
			                    	<select class='form-control input1' name='penjualan_type_id'>
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
	                    			<input name='tanggal' class='form-control date-picker' value="<?=date('d/m/Y')?>" >
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

			                <div class="form-group">
			                	<!-- po_section -->
			                    <label class="control-label col-md-3">PO/Ket
			                    </label>
			                    <div class="col-md-6">
	                    			<input name='po_number' maxlength='38' class='form-control'>
			                    </div>
			                </div> 

			                <div class="form-group customer_section">
			                    <label class="control-label col-md-3">Customer<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<div id='add-select-customer'  hidden>
			                    		<select name="customer_id" class='form-control' id='customer_id_select'>
			                				<option value=''>Pilih</option>
			                				<?foreach ($this->customer_list_aktif as $row) { ?>
				                    			<option value="<?=$row->id?>"><?=$row->nama;?></option>
				                    		<? } ?>
				                    	</select>
			                    	</div>
			                    	<div id='add-nama-keterangan'>
				                    	<input name='nama_keterangan' class='form-control'>
			                    	</div>
			                    </div>
			                </div>

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
			                    	<label>
			                    	<input type='checkbox' name='fp_status' class='form-control' id='fp_status_add' value='1'>Ya</label>
			                    </div>
			                </div>
			                
						</form>
					</div>

					<div class="modal-footer">
						<button type="button" class="btn blue btn-active btn-save-tab" title='Save & Buka di Tab Baru'>Save & New Tab</button>
						<button type="button" class="btn blue btn-active btn-trigger btn-save" title='Save & Buka di Tab Ini'>Save</button>
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
			                    	<select class='form-control input1' name='penjualan_type_id'>
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
	                    			<input name='tanggal' class='form-control date-picker' value="<?=$tanggal;?>" >
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

			                <div class="form-group">
			                	<!-- po_section -->
			                    <label class="control-label col-md-3">PO/Ket
			                    </label>
			                    <div class="col-md-6">
	                    			<input name='po_number' maxlength='38' class='form-control' value='<?=$po_number?>'>
			                    </div>
			                </div> 

			                <div class="form-group customer_section">
			                    <label class="control-label col-md-3">Customer<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<div id='edit-select-customer'  <?if ($penjualan_type_id == 3) { ?> hidden <?}?> >
			                    		<select name="customer_id" class='form-control' id='customer_id_select2'>
			                				<option value=''>Pilih</option>
			                				<?foreach ($this->customer_list_aktif as $row) { ?>
				                    			<option <?if ($customer_id == $row->id) {?>selected<?}?> value="<?=$row->id?>"><?=$row->nama;?></option>
				                    		<? } ?>
				                    	</select>
			                    	</div>
			                    	<div id='edit-nama-keterangan' <?if ($penjualan_type_id != 3) { ?> hidden <?}?> >
				                    	<input name='nama_keterangan' class='form-control' value="<?=$nama_keterangan;?>">
			                    	</div>
			                    </div>
			                </div> 

			                <div class="form-group edit-alamat-keterangan">
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
			                    	<label>
			                    	<input type='checkbox' <?=($fp_status == 1 ? 'checked' : '');?> name='fp_status' class='form-control' id='fp_status_edit' value='1'>Ya</label>
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
							<h3 class='block'> Tambah Barang</h3>
							
							<div class="form-group">
			                    <label class="control-label col-md-3">Gudang<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<input name='penjualan_id' value='<?=$penjualan_id;?>' hidden>
			                    	<input name='penjualan_detail_id' value='' hidden>
			                    	<input name='tanggal' value='<?=$tanggal;?>' hidden>
	                    			<select name="gudang_id" class='form-control' id='gudang_id_select'>
		                				<?foreach ($this->gudang_list_aktif as $row) { ?>
			                    			<option value="<?=$row->id?>"><?=$row->nama;?></option>
			                    		<? } ?>
			                    	</select>
			                    </div>
			                </div> 

							<div class="form-group">
			                    <label class="control-label col-md-3">Kode Barang<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<select name="barang_id" class='form-control input1' id='barang_id_select'>
		                				<option value=''>Pilih</option>
		                				<?foreach ($this->barang_list_aktif as $row) { ?>
			                    			<option value="<?=$row->id?>"><?=$row->nama_jual;?></option>
			                    		<? } ?>
			                    	</select>
			                    	<select name='data_barang' hidden>
			                    		<?foreach ($this->barang_list_aktif as $row) { ?>
			                    			<option value="<?=$row->id?>"><?=$row->nama_satuan;?>??<?=$row->harga_jual;?></option>
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
			                    <label class="control-label col-md-3">Satuan<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
		                			<input readonly type="text" class='form-control' name="satuan"/>
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
						<table id='qty-table'>
							<tr>
								<th>Yard</td>
								<th>Roll</td>
								<th></th>
							</tr>
							<tr>
								<td><input name='qty' class='input1'></td>
								<td><input name='jumlah_roll'></td>
								<td><button tabindex='-1' class='btn btn-xs blue btn-add-qty-row'><i class='fa fa-plus'></i></button></td>
							</tr>
							<tr>
								<td><input name='qty'></td>
								<td><input name='jumlah_roll'></td>
								<td></td>
							</tr>
							<tr>
								<td><input name='qty'></td>
								<td><input name='jumlah_roll'></td>
								<td></td>
							</tr>
							<tr>
								<td><input name='qty'></td>
								<td><input name='jumlah_roll'></td>
								<td></td>
							</tr>
							<tr>
								<td><input name='qty'></td>
								<td><input name='jumlah_roll'></td>
								<td></td>
							</tr>
							<tr>
								<td><input name='qty'></td>
								<td><input name='jumlah_roll'></td>
								<td></td>
							</tr>
							<tr>
								<td><input name='qty'></td>
								<td><input name='jumlah_roll'></td>
								<td></td>
							</tr>
							<tr>
								<td><input name='qty'></td>
								<td><input name='jumlah_roll'></td>
								<td></td>
							</tr>
							<tr>
								<td><input name='qty'></td>
								<td><input name='jumlah_roll'></td>
								<td></td>
							</tr>
							<tr>
								<td><input name='qty'></td>
								<td><input name='jumlah_roll'></td>
								<td></td>
							</tr>
						</table>
						<div class='yard-info'>
							TOTAL QTY: <span class='yard_total' >0</span> yard <br/>
							TOTAL ROLL: <span class='jumlah_roll_total' >0</span> 
						</div>

						<div id='stok-info' style='border:1px solid #ddd; padding:5px; margin-bottom:20px;'>
							STOK QTY : <span class='stok-qty'>0</span><br/>
							STOK ROLL : <span class='stok-roll'>0</span>
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
			                    <label class="control-label col-md-3">Gudang<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<input name='penjualan_id' value='<?=$penjualan_id;?>' <?=$hidden_spv;?> >
			                    	<input name='penjualan_detail_id' <?=$hidden_spv;?> >
			                    	<input name='tanggal' value='<?=$tanggal;?>' hidden>
	                    			<select name="gudang_id" class='form-control' id='gudang_id_select_edit'>
		                				<?foreach ($this->gudang_list_aktif as $row) { ?>
			                    			<option value="<?=$row->id?>"><?=$row->nama;?></option>
			                    		<? } ?>
			                    	</select>
			                    </div>
			                </div> 

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
			                    			<option value="<?=$row->id?>"><?=$row->nama_satuan;?>??<?=$row->harga_jual;?></option>
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

			                <div class="form-group">
			                    <label class="control-label col-md-3">Satuan<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
		                			<input readonly type="text" class='form-control' name="satuan"/>
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
							<table id='qty-table-edit'>
								<thead>
									<tr>
										<th>No</th>
										<th>Yard</td>
										<th>Roll</td>
										<th></th>
									</tr>
								</thead>
								<tbody>

								</tbody>
							</table>
						</div>
						<span class='total_roll' hidden></span>
						<div class='yard-info'>
							TOTAL : <span class='yard_total' >0</span> yard <br/>
							TOTAL ROLL : <span class='jumlah_roll_total' >0</span>
						</div> 
						<div id='stok-info-edit' style='border:1px solid #ddd; padding:5px; margin-bottom:20px;'>
							STOK QTY : <span class='stok-qty'>0</span><br/>
							STOK ROLL : <span class='stok-roll'>0</span>
						</div>
						<!-- <form hidden action="<?=base_url()?>transaction/penjualan_qty_update" id='form-qty-update' method="post">
							<input name='id'>
							<input name='rekap_qty'>
						</form> -->
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

		<div class="modal fade" id="portlet-config-sample" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body">
						<form action="<?=base_url()?>transaction/penjualan_list_close" class="form-horizontal" id="form-sample" method="get">
							<h3 class='block'> Konfirmasi</h3>
							
							<div class="form-group">
								<div class='note note-danger'>Jumlah total 0, Input PIN jika ingin melanjutkan</div>
			                    <label class="control-label col-md-3">PIN<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<input name='id' value='<?=$penjualan_id;?>' hidden>
			                    	<input name='tanggal' value='<?=$ori_tanggal;?>' hidden>
									<input name='pin' type='password' class="pin_user" class="form-control">
			                    </div>
			                </div>	
		                </form>		                
					</div>

					<div class="modal-footer">
						<button type="button" class="btn blue btn-sample-ok">OK</button>
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

		<div class="modal fade bd-modal-lg" id="portlet-config-dp" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-lg">
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
									$dp_bayar = 0;
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
												<span class='amount'><?=number_format($row->amount,'0',',','.');?></span>
											</td>
											<td>
												<?$dp_bayar += $row->amount_bayar;?>
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

		<div class="row margin-top-10">
			<div class="col-md-12">
				<div class="portlet light">
					<div class="portlet-title">
						<div class="caption caption-md">
							<i class="icon-bar-chart theme-font hide"></i>
							<span class="caption-subject theme-font bold uppercase"><?=$breadcrumb_small;?></span>
						</div>
						<div class="actions hidden-print">
							<?if (is_posisi_id() != 6) { ?>
								<a href="<?=base_url().is_setting_link('transaction/penjualan_list_detail');?>" target='_blank' class="btn btn-default btn-sm">
								<i class="fa fa-files-o"></i> Tab Kosong Baru </a>
								<a href="#portlet-config" data-toggle='modal' class="btn btn-default btn-sm btn-form-add">
								<i class="fa fa-plus"></i> Penjualan Baru </a>
							<?}?>
							<a href="#portlet-config-faktur" data-toggle='modal' class="btn btn-default btn-sm btn-form-add">
							<i class="fa fa-search"></i> Cari Faktur </a>
						</div>
					</div>
					<div class="portlet-body">
						<table style='width:100%'>
							<tr>
								<td>
									<table>
											<?if ($penjualan_id != '' && $status_aktif != -1) { ?>
												<tr>
													<td colspan='3'>
														<?if ($status == 0) { ?>
															<button href="#portlet-config-pin" data-toggle='modal' class='btn btn-xs btn-pin'><i class='fa fa-key'></i> request open</button>
														<?}elseif ($status != -1) { ?>
															<?if (is_posisi_id() != 6 ) { ?>
																<button href="#portlet-config-edit" data-toggle='modal' class='btn btn-xs '><i class='fa fa-edit'></i> edit</button>
															<?}?>
														<?}?>

													</td>
												</tr>
											<?}?>
										<tr hidden>
								    		<td>Status</td>
								    		<td class='padding-rl-5'> : </td>
								    		<td class='td-isi-bold'>
								    			<?if ($status == -1 && $no_faktur_lengkap != '' && $status_aktif != -1 ) { $iClass = 'fa-ban' ?>
								    				<span style='color:red'><b>BATAL</b></span>
								    			<?}elseif ($status == 1 && $no_faktur_lengkap != '' && $status_aktif != -1  ) { $iClass = 'fa-unlock' ?>
								    				<span style='color:green'><b>OPEN</b></span>
								    			<?}elseif ($status == 0 && $no_faktur_lengkap != '' && $status_aktif != -1  ) { $iClass = 'fa-lock' ?>
								    				<span style='color:orange'><b>LOCKED</b></span>
								    			<?}elseif ($status_aktif == -1) {
								    				$iClass = 'fa-minus-circle';
								    			}elseif ($penjualan_id !='') {
								    				$iClass = 'fa-exclamation-circle';
								    			}?>
								    		</td>
								    	</tr>
										<tr>
								    		<td>Tipe</td>
								    		<td class='padding-rl-5'> : </td>
								    		<td class='td-isi-bold'>
								    			<?=$tipe_penjualan;?>
								    		</td>
								    	</tr>
								    	<tr>
									    	<!-- po_section -->
								    		<td>PO/Ket</td>
								    		<td class='padding-rl-5'> : </td>
								    		<td class='td-isi-bold'>
								    			<?=$po_number;?>
								    		</td>
								    	</tr>
								    	<tr>
								    		<td>Tanggal</td>
								    		<td class='padding-rl-5'> : </td>
								    		<td class='td-isi-bold'><?=is_reverse_date($tanggal);?></td>
								    	</tr>
								    	<tr hidden <?=($penjualan_type_id != 2 ? 'hidden' : '' )?> >
								    		<td>Jatuh Tempo</td>
								    		<td class='padding-rl-5'> : </td>
								    		<td class='td-isi-bold'>
								    			<?
								    				// $dt = strtotime(' +60 days', strtotime($tanggal) );
													// echo $get_jt = ($jatuh_tempo == '' ? date('Y-m-d', $dt) : $row->jatuh_tempo);
								    			?>
								    			<?=$jatuh_tempo;?></td>
								    	</tr>
								    	<tr class='customer_section'>
								    		<td>Customer</td>
								    		<td class='padding-rl-5'> : </td>
								    		<td class='td-isi-bold'>
								    			<?if ($penjualan_type_id == 3) { ?>
								    				<?=$nama_keterangan;?> / <span class='alamat_keterangan'><?=$alamat_keterangan;?></span>
								    			<?} else{
								    				echo $nama_customer;
								    			}?>
								    		</td>
								    	</tr>
								    	<tr class='customer_section'>
								    		<td>Alamat</td>
								    		<td class='padding-rl-5'> : </td>
								    		<td class='td-isi-bold'>
								    			<span class='alamat'><?=$alamat_customer?></span>
								    		</td>
								    	</tr>
								    	<tr class='customer_section'>
								    		<td>FP</td>
								    		<td class='padding-rl-5'> : </td>
								    		<td class='td-isi-bold'>
								    			<?if ($fp_status == 1) { ?>
								    				<i class='fa fa-check'></i>
								    			<?} else{
								    				echo '';
								    			}?>
								    		</td>
								    	</tr>
								    	
								    </table>
								</td>
								<td class='text-right' style='<?=$bg_info;?>; color:white; padding:10px;'>
									<div class='<?//=$note_info;?>' sr>
										<i class='fa <?=$iClass;?>' style='font-size:2em'><?=($status_aktif == '-1' ? 'BATAL' : '');?></i>
										<span class='no-faktur-lengkap'> <?=$no_faktur_lengkap;?></span>
									</div>
								</td>
							</tr>
						</table>

					    <hr/>
					    <div style='overflow:auto'>
						<!-- table-striped table-bordered  -->
						<table class="table table-hover table-striped table-bordered" id="general_table">
							<thead>
								<tr>
									<th scope="col">
										No
									</th>
									<th scope="col">
										Nama Barang
										<?if ($penjualan_id !='' && $status == 1 && is_posisi_id() != 6 && $status_aktif != -1 ) {?>
											<a href="#portlet-config-detail" data-toggle='modal' class="btn btn-xs blue btn-brg-add">
											<i class="fa fa-plus"></i> </a>
										<?}?>
									</th>
									<th scope="col">
										Gudang
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
								$idx =1; $barang_id = ''; $gudang_id_last = ''; $harga_jual = 0; $qty_total = 0; $roll_total = 0;
								foreach ($penjualan_detail as $row) { ?>
									<tr id='id_<?=$row->id;?>'>
										<td>
											<?=$idx;?> 
										</td>
										<td>
											<span class='nama_jual'><?=$row->nama_barang;?> <?=$row->nama_warna;?></span> 
											<?$barang_id=$row->barang_id;?>
										</td>
										<td>
											<?=$row->nama_gudang;?>
										</td>
										<td>
											<span class='nama_satuan'><?=$row->nama_satuan;?></span>  
										</td>
										<td>
											<!-- <input name='qty' class='free-input-sm qty' value="<?=$row->qty;?>">  -->
											<span class='qty'><?=str_replace('.00', '',$row->qty);?></span>
										</td>
										<td>
											<!-- <input name='jumlah_roll' class='free-input-sm jumlah_roll' value="<?=$row->jumlah_roll;?>"> -->
											<span class='jumlah_roll'><?=$row->jumlah_roll;?></span> 
										</td>
										<td>
											<!-- <input name='harga_jual' <?=$readonly;?> class='free-input-sm amount_number harga_jual' value="<?=number_format($row->harga_jual,'0','.','.');?>">  -->
											<span class='harga_jual'><?=number_format($row->harga_jual,'0','.','.');?></span>

										</td>
										<td>
											<?$subtotal = $row->qty * $row->harga_jual;
											$g_total += $subtotal;
											$harga_jual = $row->harga_jual;
											$qty_total += $row->qty;
											$roll_total += $row->jumlah_roll;
											?>
											<span class='subtotal'><?=number_format($subtotal,'0','.','.');?></span> 
										</td>
										<td class='hidden-print'>
											<?$gudang_id_last=$row->gudang_id;?>
											
											<?if ($status == 1 || is_posisi_id() == 1 ) { ?>
												<?if (is_posisi_id() != 6 && $status_aktif != -1) { ?>
													<span class='id' <?=$hidden_spv?> ><?=$row->id;?></span>
													<span class='barang_id' <?=$hidden_spv?> ><?=$row->barang_id;?></span>
													<span class='warna_id' <?=$hidden_spv?> ><?=$row->warna_id;?></span>
													<span class='gudang_id'  <?=$hidden_spv?> ><?=$row->gudang_id;?></span>
													<span class='data_qty'  <?=$hidden_spv?> ><?=$row->data_qty;?></span>
													<a href='#portlet-config-detail-edit' data-toggle='modal' class="btn-xs btn green btn-edit"><i class="fa fa-edit"></i> </a>
													<a class="btn-xs btn red btn-detail-remove"><i class="fa fa-times"></i> </a>
												<?}?>
											<?}?>
										</td>
									</tr>
								<?
								$idx++; 
								} ?>

								<tr class='subtotal-data'>
									<td colspan='4' class='text-right'><b>TOTAL</b></td>
									<td class='text-left'><b><?=str_replace('.00', '',$qty_total);?></b></td>
									<td class='text-left'><b><?=$roll_total;?></b></td>
									<td class='text-right'><b>TOTAL</b></td>
									<td><b class='total'><?=number_format($g_total,'0',',','.');?> </b> </td>
									<td class='hidden-print'></td>
								</tr>

								<!-- <tr class='subtotal-data'>
									<td colspan='6' class='text-right'><b>DISKON</b></td>
									<td>
										<b>
											<input name='diskon' <?=$readonly;?> class='input-no-border amount_number diskon' value="<?=number_format($diskon,'0',',','.');?>" /> 
										</b>
									</td>
									<td class='hidden-print'></td>
								</tr>
								<tr class='subtotal-data'>
									<td colspan='6' class='text-right'><b>ONGKOS KIRIM</b></td>
									<td><b><input name='ongkos_kirim' <?=$readonly;?> class='input-no-border amount_number ongkos_kirim' value="<?=number_format($ongkos_kirim,'0',',','.');?>" /> </b> </td>
									<td class='hidden-print'></td>
								</tr> -->
								<!--<tr class='subtotal-data'>
									<td colspan='9'>
										KETERANGAN : <input style='width:75%' <?=$readonly;?> class='input-no-border padding-rl-5 keterangan' name='keterangan' value="<?=$keterangan;?>">
									</td>
								</tr>-->
							</tbody>
						</table>
					    </div>

						<hr/>
							<p class='btn-detail-toggle' style='cursor:pointer'><b>Detail <i class='fa fa-caret-down'></i></b></p>
						
							<table id='general-detail-table' class='table table-bordered' hidden>
								<thead>
									<tr>
										<th>Barang</th>
										<th>Warna</th>
										<th>Roll</th>
										<th>Total</th>
										<th>Detail</th>
									</tr>
								</thead>
								<?foreach ($penjualan_detail as $row) {?>
									<tr>
										<td><?=$row->nama_barang?></td>
										<td><?=$row->nama_warna?></td>
										<td><?=$row->jumlah_roll?></td>
										<td><?=str_replace('.00', '',$row->qty)?></td>
										<td><?
											$data_qty = explode('--', $row->data_qty);
											$coll = 1;
											foreach ($data_qty as $key => $value) {
												$detail_qty = explode('??', $value);
												for ($i=1; $i <= $detail_qty[1] ; $i++) { 
													echo "<p style='display:inline-flex; width:50px; '>".str_replace('.00', '', $detail_qty[0])."</p>";
													$coll++;
													if ($coll == 11) {
														echo "<hr style='margin:2px' />";
														$coll = 1;
													}
												}
											}
										?></td>
									</tr>
								<?}?>
							</table>
						<hr/>
								
						<div style='overflow:auto'>
						<table style='width:100%' <?=($status_aktif == -1 ? 'hidden' : '');?> >
							<tr>
								<td>
									<table id='bayar-data'>
										<?
										$bayar_total = 0;
										foreach ($pembayaran_type as $row) { 
											$bayar = null; 
											if (isset($pembayaran_penjualan[$row->id])) {
												$bayar = $pembayaran_penjualan[$row->id];
												if ($row->id == 1) {
													$bayar = $dp_bayar;
													$bayar_total += $dp_bayar;
												}else{
													$keterangan = $pembayaran_keterangan[$row->id];
													$bayar_total += $bayar;
												}
											}

											$stat = ''; $style = '';
											if (is_posisi_id() == 6) {
												$stat = 'readonly';
												$style = 'background:#ddd; border:1px solid #ddd';
											}

											if ($row->id == 1 || $status != 1) {
												if ( $customer_id == '' || $customer_id == 0 || $status != 1) {
													if (is_posisi_id() != 1) {
														$stat = 'readonly';
														$style = 'background:#ddd; border:1px solid #ddd';
													}
												}
											}
											?>
											<?if ($row->id == 1) { ?>
												<tr>
													<td><?=$row->nama;?><span class='saldo_awal' hidden><?=$saldo_awal;?></span></td>
													<td>
														<a href="#portlet-config-dp" data-toggle='modal' class='manage-dp'>
															<input <?=$stat;?> style='<?=$style;?>' class='dp-val' value="<?=number_format($bayar,'0',',','.');?>" >
														</a>
														<span class='dp_copy' hidden><?=$bayar?></span>
													</td>
												</tr>
											<?}elseif ($row->id == 4) { ?>
												<tr>
													<td><?=$row->nama;?></td>
													<td>
														<input <?=$stat;?> style='<?=$style;?>' class='amount_number bayar-input ' id='bayar_<?=$row->id;?>' value="<?=number_format($bayar,'0',',','.');?>">
														<?if ($penjualan_id != '') { ?>
															<a data-toggle="popover" style='color:black' data-trigger='click' data-html="true" data-content="<input <?=$stat;?> style='<?=$style;?>' class='keterangan_bayar' name='keterangan_<?=$row->id;?>' value='<?=$keterangan;?>'>">
																<i class='fa fa-edit'></i>
															</a>
														<?}?>
													</td>
												</tr>
											<?}elseif ($row->id == 5) { ?>
												<tr>
													<td><?=$row->nama;?></td>
													<td>
														<a data-toggle="popover" style='color:black' data-trigger='hover' data-html="true" data-content="Hanya untuk tipe kredit pelanggan">
															<input <?=$stat;?> id='bayar_<?=$row->id;?>'  class='amount_number bayar-input bayar-kredit' value="<?=number_format($bayar,'0',',','.');?>">
														</a>
													</td>
												</tr>
											<?}elseif ($row->id == 6) { ?>
												<tr hidden>
													<td><?=$row->nama;?></td>
													<td>
														<a data-toggle="popover" style='color:black' data-trigger='hover' data-html="true" data-content="Nama Bank : <b><?=$nama_bank?></b><br/>No Rek : <b><?=$no_rek_bank?></b><br/>No Akun : <b><?=$no_akun?></b><br/>Nama Bank : <b><?=$nama_bank?></b><br/>Tanggal Giro : <b><?=$tanggal_giro?></b><br/>Jatuh Tempo : <b><?=$jatuh_tempo_giro?></b><br/>">
															<input <?=$stat;?> style='<?=$style;?>' class='amount_number bayar-giro' id='bayar_<?=$row->id;?>' value="<?=number_format($bayar,'0',',','.');?>">
														</a>
														<?if ($penjualan_id != '' && is_posisi_id() != 6 && $status != 0) { ?>
															<a data-toggle="modal" href='#portlet-config-giro' style='color:black' style='<?=$style;?>' >
																<i class='fa fa-edit'></i>
															</a>
														<?}?>
													</td>
												</tr>
											<?}else{?>
												<tr>
													<td><?=$row->nama;?></td>
													<td><input <?=$stat;?> style='<?=$style;?>' class='amount_number bayar-input' id='bayar_<?=$row->id;?>' value="<?=number_format($bayar,'0',',','.');?>"></td>
												</tr>
											<?}?>

										<?}?>
									</table>
								</td>
								<td style='vertical-align:top;font-size:2.5em;' class='text-right'>
									<table style='float:right;'>
										<tr style='border:2px solid #c9ddfc'>
											<td class='padding-rl-25' style='background:#c9ddfc'>BAYAR</td>
											<td class='padding-rl-10'>
												<b>Rp <span class='total_bayar' style=''><?=number_format($bayar_total,'0',',','.');?></span></b>
											</td>
										</tr>
										<tr style='border:2px solid #ffd7b5'>
											<td class='padding-rl-25' style='background:#ffd7b5'>TOTAL</td>
											<td class='text-right padding-rl-10'> 
												<b>Rp <span class='g_total' style=''><?=number_format($g_total - $diskon,'0',',','.');?></span></b>
											</td>
										</tr>
										<tr style='border:2px solid #ceffb4'>
											<td class='padding-rl-25' style='background:#ceffb4'>KEMBALI</td>
											<td class='padding-rl-10'>
												<?
												$kembali_style = '';
												$kembali = $bayar_total - ($g_total - $diskon + $ongkos_kirim);
												if ($kembali < 0 ) {
													$kembali_style = 'color:red';
												}
												?>
												<b>Rp <span class='kembali' style='<?=$kembali_style;?>'><?=number_format($kembali,'0',',','.');?></span></b>
											</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
						</div>
						<hr/>
						<?//print_r($data_penjualan_detail_group);?>
						<div>
							<?if ($status_aktif != -1) {?>
								<button type='button'<?if ($idx == 1) { echo 'disabled'; }?> <?=$disabled;?> <?if ($status != 1) {?> disabled <?}?> class='btn btn-lg red hidden-print btn-close'><i class='fa fa-lock'></i> LOCK </button>
				                <a style='display:none' <?if($disabled_status == ''){ ?>href="<?=base_url();?>transaction/penjualan_print?penjualan_id=<?=$penjualan_id;?>"<? }else{ echo $disabled_status; } ?> target='_blank' class='btn btn-lg blue btn-print hidden-print'><i class='fa fa-print'></i> Faktur </a>
				                <a style='display:none' <?if($disabled_status == ''){ ?>href="<?=base_url();?>transaction/penjualan_detail_print?penjualan_id=<?=$penjualan_id;?>"<? }else{ echo $disabled_status; } ?> target='_blank' class='btn btn-lg blue btn-print hidden-print'><i class='fa fa-print'></i> Detail </a>
				                <a style='display:none' <?if($disabled_status == ''){ ?> href="<?=base_url();?>transaction/penjualan_print_kombinasi?penjualan_id=<?=$penjualan_id;?>"<? }else{ echo $disabled_status; } ?> target='_blank' class='btn btn-lg blue btn-print hidden-print'><i class='fa fa-print'></i> Faktur + Detail </a>
				                
				                <a style='display:none' <?if($disabled_status == ''){ ?>href="<?=base_url();?>transaction/penjualan_sj_print?penjualan_id=<?=$penjualan_id;?>&harga=yes"<? }else{ echo $disabled_status; } ?> target='_blank' class='btn btn-lg green btn-print hidden-print'><i class='fa fa-print'></i> Surat Jalan </a>
				                <a style='display:none' <?if($disabled_status == ''){ ?>href="<?=base_url();?>transaction/penjualan_sj_print?penjualan_id=<?=$penjualan_id;?>&harga=no"<? }else{ echo $disabled_status; } ?> target='_blank' class='btn btn-lg green btn-print hidden-print'><i class='fa fa-print'></i> Surat Jalan No Harga </a>
	                            <!-- <button type="button" class="btn btn-success" onclick="startConnection();">Connect</button> -->
	                            <?$print_disable = ($penjualan_id == '' || $status == 1 ? 'disabled' : '');?>
	                            <button <?=$print_disable;?> type="button" tabindex='-1' class="btn btn-lg blue btn-print btn-faktur-print">FAKTUR</button>
	                            <button <?=$print_disable;?> type="button" tabindex='-1' class="btn btn-lg blue btn-print btn-print-detail">DETAIL</button>
	                            <button <?=$print_disable;?> type="button" tabindex='-1' class="btn btn-lg blue btn-print btn-print-kombi">FAKTUR + DETAIL</button>
	                            <button <?=$print_disable;?> type="button" tabindex='-1' class="btn btn-lg green btn-print btn-surat-jalan">SURAT JALAN</button>
	                            <button <?=$print_disable;?> type="button" tabindex='-1' class="btn btn-lg green btn-print btn-surat-jalan-noharga">SJ NO HARGA</button>
				            	<?if (is_posisi_id() == 1) {?>
					                <button type="button" class='btn btn-lg default btn-print hidden-print print-testing'><i class='fa fa-print'></i> TESTING </button>
				                <?}?>
				            <?}?>
                            <?foreach ($faktur_link as $row) { ?>
	                            <a style='float:right' href="<?=base_url().is_setting_link('transaction/penjualan_list_detail').'?id='.$row->id;?>" class="btn btn-lg btn-default "><i class='fa fa-angle-double-right'></i> <?=$row->no_faktur_lengkap;?></a>
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

<script src="<?php echo base_url('assets_noondev/js/qz-print/dependencies/rsvp-3.1.0.min.js');?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets_noondev/js/qz-print/dependencies/sha-256.min.js');?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets_noondev/js/qz-print/qz-tray.js');?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets_noondev/js/webprint.js'); ?>" type="text/javascript"></script>

<script src="<?php echo base_url('assets/global/plugins/select2/select2.min.js'); ?>" type="text/javascript" ></script>
<script src="<?php echo base_url('assets_noondev/js/form-penjualan.js'); ?>" type="text/javascript"></script>

<?
	$printer_marker = "";
	$headers = apache_request_headers();
	foreach ($headers as $header => $value) {
		if ($header == "Host") {
		    if ($value == 'sistem.blessingtdj.com') {
		    	$printer_marker = 3;
		    }
			# code...
		}
	}
?>

<script>
jQuery(document).ready(function() {

	//===========================general=================================

		<?if($penjualan_id != '' && $status==0){?>

			var printer_default = "<?=$default_printer?>";

			webprint = new WebPrint(true, {
		        relayHost: "127.0.0.1",
		        relayPort: "8080",
	            listPrinterCallback: populatePrinters,
		        readyCallback: function(){
	                webprint.requestPrinters();
	            }
		    });

		    $('.btn-faktur-print').click(function(){
				$('[name=print_target]').val('1');
			});

			$('.btn-print-detail').click(function(){
				$('[name=print_target]').val('2');
				// print_detail();
			});

			$('.btn-print-kombi').click(function(){
				$('[name=print_target]').val('3');
				// print_detail();
			});

			$('.btn-surat-jalan').click(function(){
				$('[name=print_target]').val('4');
				// print_detail();
			});

			$('.btn-surat-jalan-noharga').click(function(){
				$('[name=print_target]').val('5');
				// print_detail();
			});

			$('.print-testing').click(function(){
				$('[name=print_target]').val('99');
				// print_detail();
			});

			$('.btn-print').click(function(){
	    		$('#portlet-config-print').modal('toggle');
		    	// console.log(webprint.requestPrinters());
		    });

		    $('.btn-print').dblclick(function(){
	    		notific8("lime", "print...");
		    	$('.btn-print-action').click();
		    	// console.log(webprint.requestPrinters());
		    });

		    $('.btn-print-action').click(function(){
				printer_marker = "<?=$printer_marker?>";
				var selected = $('#printer-name').val();
				var printer_name = $("#printer-name [value='"+selected+"']").text();
				printer_name = $.trim(printer_name);
				var action = $('[name=print_target]').val();
				if (action == 1 ) {
					// print_faktur(printer_name);
					// print_font_test(printer_name);
					printer_marker == 3 ? print_faktur_3(printer_name) : print_faktur(printer_name);

				}else if(action == 2){
					// print_detail(printer_name);
					printer_marker == 3 ? print_detail_3(printer_name) : print_detail(printer_name);
				}
				else if(action == 3){
					// print_kombinasi(printer_name);
					printer_marker == 3 ? print_kombinasi_3(printer_name) : print_kombinasi(printer_name);
				}else if(action == 4){
					// print_surat_jalan(printer_name);
					printer_marker == 3 ? print_surat_jalan_3(printer_name) : print_surat_jalan(printer_name);
				}else if(action == 5){
					// print_surat_jalan_noharga(printer_name);
					printer_marker == 3 ? print_surat_jalan_noharga_3(printer_name) : print_surat_jalan_noharga(printer_name);
				}else{
					// print_surat_jalan_noharga(printer_name);
					print_testing(printer_name);
				}
				// alert(printer_name);
			});

		<?}?>

		FormNewPenjualanDetail.init();

		var form_group = {};
		var idx_gen = 0;
		var print_idx = 1;
	   	var penjualan_type_id = '<?=$penjualan_type_id;?>';


		$('[data-toggle="popover"]').popover();


	    $('#warna_id_select, #barang_id_select,#warna_id_select_edit, #barang_id_select_edit').select2({
	        placeholder: "Pilih...",
	        allowClear: true
	    });

	    $('#customer_id_select, #customer_id_select_edit').select2({
	        allowClear: true
	    });

//========================================================================================


	    <?if ($penjualan_id != '') { ?>
			$('.btn-print').click(function(){
		    });

		    $('#dp_list_table').on('click','.dp-more-info', function(){
		    	$(this).closest('td').find('ul').toggle();
		    });

		    $(".manage-dp").click(function(){
				var bayar = 0;
		    	$('#bayar-data tr td input').each(function(){
					if ($(this).attr('class') != 'keterangan_bayar' && $(this).attr('class') != 'dp-val') {
						bayar += reset_number_format($(this).val());			
					};
				});

				var g_total = reset_number_format($('.g_total').html());
				g_total = g_total - bayar;
				$('#dp_list_table .dp-nilai-bon-info').html(change_number_format(g_total));
				
		    	dp_update_bayar();
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
				var url = "transaction/get_search_no_faktur_jual";
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
	    	var id = $("#form_search_faktur [name=penjualan_id]").val();
	    	if (id != '') {
	    		$('#form_search_faktur').submit();
	    	};
	    });

	    $('.btn-pin').click(function(){
	    	setTimeout(function(){
		    	$('#pin_user').focus();    		
	    	},700);
	    });

	    $('.btn-request-open').click(function(){
	    	if(cek_pin($('#form-request-open'))){
				$('#form-request-open').submit();
	    	}
	    });

	    $('.pin_user').keypress(function (e) {
	    	var form = '#'+$(this).closest('form').attr('id');
	    	var obj_form = $(form);
	        if (e.which == 13) {
	        	if(cek_pin(obj_form)){
					obj_form.submit();
	        	}
	        }
	    });

	//====================================penjualan type=============================


		$('#form_edit_data [name=penjualan_type_id]').change(function(){
			if ($(this).val() == 1) {
				$('#form_edit_data .po_section').show();
				$('#form_edit_data .customer_section').show();
	   			penjualan_type_id = 1;
	   			// $('#customer_id_select_edit').select2("open");
	   			$('#edit-nama-keterangan').hide();
	   			$('.edit-alamat-keterangan').hide();
	   			$('#edit-select-customer').show();
	   			$('#fp_status_edit').prop('checked',false);

			};

			if ($(this).val() == 2) {
				$('#form_edit_data .po_section').show();
				$('#form_edit_data .customer_section').show();
	   			// $('#customer_id_select_edit').select2("open");
	   			$('#edit-nama-keterangan').hide();
	   			$('.edit-alamat-keterangan').hide();
	   			$('#edit-select-customer').show();
	   			$('#fp_status_edit').prop('checked',true);
			};

			if ($(this).val() == 3) {
				$('#form_edit_data .po_section').hide();
				penjualan_type_id = 3;
	   			$('#customer_id_select_edit').val('');
	   			$('#edit-nama-keterangan').show();
	   			$('.edit-alamat-keterangan').show();
	   			$('#edit-select-customer').hide();
	   			$('#fp_status_edit').prop('checked',false);
			};

			$.uniform.update($('#fp_status_edit'));
		});

		$('#customer_id_select').change(function(){
			if (penjualan_type_id == 1 || penjualan_type_id == 2) {
				if ($(this).val() == '') {
					var customer_id = $(this).val('');
					notific8('ruby', 'Customer harus dipilih');
		   			$('#customer_id_select').select2("open");
				}else{
					var customer_id = $(this).val();
				}
			};
		});

		$('#form_add_data [name=penjualan_type_id]').change(function(){
			if ($(this).val() == 1) {
				$('#form_add_data .po_section').show();
				$('#form_add_data .customer_section').show();
	   			// $('#customer_id_select').select2("open");
	   			$('#add-nama-keterangan').hide();
	   			$('.add-alamat-keterangan').hide();
	   			$('#add-select-customer').show();
	   			$('#fp_status_add').prop('checked',false);
			};

			if ($(this).val() == 2) {
				$('#form_add_data .po_section').show();
				$('#form_add_data .customer_section').show();
	   			// $('#customer_id_select').select2("open");
	   			$('#add-nama-keterangan').hide();
	   			$('.add-alamat-keterangan').hide();
	   			$('#add-select-customer').show();
	   			$('#fp_status_add').prop('checked',true);
	   			// alert($('#fp_status_add').is(':checked'));

			};

			if ($(this).val() == 3) {
				$('#form_add_data .po_section').hide();
				// $('#form_add_data .customer_section').hide();
	   			$('#customer_id_select').val('');
	   			$('#add-nama-keterangan').show();
	   			$('.add-alamat-keterangan').show();
	   			$('#add-select-customer').hide();
	   			$('#fp_status_add').prop('checked',false);
			};

			$.uniform.update($('#fp_status_add'));

		});

	// ======================last input ================================================

			var barang_id = "<?=$barang_id;?>";
			var barang_id_last = "<?=$barang_id;?>";
			var gudang_id_last = "<?=$gudang_id_last;?>";
			var idx = "<?=$idx;?>";
			var harga_jual = "<?=number_format($harga_jual,'0',',','.');?>";

	//====================================get harga jual barang====================================

	    $('#barang_id_select').change(function(){
	    	var barang_id = $('#barang_id_select').val();
	   		var data = $("#form_add_barang [name=data_barang] [value='"+barang_id+"']").text().split('??');
	   		if (penjualan_type_id == 3 || penjualan_type_id == 1) {
	   			
	   			if (barang_id != barang_id_last) {
					$('#form_add_barang [name=harga_jual]').val(change_number_format(data[1]));
	   			}else{
					$('#form_add_barang [name=harga_jual]').val(change_number_format(harga_jual));
	   			}


	   		}else{
		    	var data_st = {};
	   			data_st['barang_id'] = $('#form_add_barang [name=barang_id]').val();
		    	data_st['customer_id'] =  "<?=$customer_id;?>";
		    	var url = "transaction/get_latest_harga";
   				
	   			ajax_data_sync(url,data_st).done(function(data_respond  /*,textStatus, jqXHR*/ ){
	   				if (data_respond > 0) {
						$('#form_add_barang [name=harga_jual]').val(change_number_format(data_respond));
	   				}else{
						$('#form_add_barang [name=harga_jual]').val(change_number_format(data[1]));
	   				}
		   		});
	   		}

			$('#form_add_barang [name=satuan]').val(data[0]);
			$('#warna_id_select').select2('open');
	    });

	    $('#warna_id_select').change(function(){
	    	$('#form_add_barang [name=harga_jual]').focus();
	    });

	    $('.btn-cek-harga').click(function(){
	    	var data = {};
	    	data['barang_id'] = $('#form_add_barang [name=barang_id]').val();
	    	var penjualan_type_id = parseInt("<?=$penjualan_type_id;?>");
	    	var customer_id = '';
	    	if (penjualan_type_id != 3) {
	    		customer_id = "<?=$customer_id;?>";
	    	};
	    	data['customer_id'] =  customer_id;
	    	var url = 'transaction/cek_history_harga';
	    	if (data['barang_id'] != '') {
	    		var tbl = '<table>';
		    	ajax_data_sync(url,data).done(function(data_respond  /*,textStatus, jqXHR*/ ){
		    		console.log(data_respond)
		    		var isi_tbl = '';
					$.each(JSON.parse(data_respond),function(i,v){
						// alert(i +" == "+v);
						isi_tbl += "<tr>"+
							"<td>"+date_formatter(v.tanggal)+"</td>"+
							"<td> : </td>"+
							"<td>"+change_number_format(v.harga_jual)+"</td>"+
							"</tr>";
					});

					if (isi_tbl !='') {
						tbl += isi_tbl + "</table>";
				    	$('#data-harga').html(tbl);			
					}else{
				    	$('#data-harga').html("no data");
					};

		   		});
	    	}else{
	    		$('#data-harga').html("no data");
	    	}
	    	
	    });

	//====================================modal barang=============================
	
		<?if ($status == 1 && is_posisi_id() != 6) {?>
			var map = {220: false};
			$(document).keydown(function(e) {
			    if (e.keyCode in map) {
			        map[e.keyCode] = true;
			        if (map[220]) {
			        	// alert(idx);
			            $('#portlet-config-detail').modal('toggle');
			            if (idx == 1) {
			            	setTimeout(function(){
					    		$('#barang_id_select').select2("open");
					    	},700);
			            }else{
			            	cek_last_input(gudang_id_last,barang_id, harga_jual);
			            }
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
	    	// alert(harga_jual);
		    if (idx == '1') {
	        	setTimeout(function(){
		    		$('#barang_id_select').select2("open");
		    	},700);
	        }else{
	        	cek_last_input(gudang_id_last,barang_id, harga_jual);
	        }
	    });

	//====================================qty manage=============================    

	    $(".btn-add-qty-row").click(function(){
	    	var baris = "<tr><td><input name='qty'></td>"+
									"<td><input name='jumlah_roll'></td>"+
									"<td></td></tr>";
	    	$('#qty-table').append(baris);
	    });
		
	    $("#qty-table").on('change','input',function(){
	    	var total = 0; var idx = 0; var rekap = [];
	    	var total_roll = 0;
	    	var total_stok = $('#stok-info').find('.stok-qty').html();
	    	var jumlah_roll_stok = $('#stok-info').find('.stok-roll').html();
	    	$("#qty-table [name=qty]").each(function(){
	    		var ini = $(this).closest('tr');
	    		var qty = $(this).val();
	    		var roll = ini.find('[name=jumlah_roll]').val();
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

	    		// alert(roll);

	    		if (roll == 0) {
		    		var subtotal = parseFloat(qty);
		    		total_roll += 0;
	    		}else{
		    		var subtotal = parseFloat(qty*roll);
		    		total_roll += parseInt(roll);
	    		};
	    		// alert(subtotal);
	    		if (qty != '' && roll != '') {
	    			rekap[idx] = qty+'??'+roll;
	    		};
	    		idx++; 
	    		// alert(total_roll);
	    		total += subtotal;
	    	});

	    	total = total.toFixed(2);
	    	total = parseFloat(total);

	    	if (total > 0 &&  total <= total_stok && total_roll <= jumlah_roll_stok) {
			    $('.jumlah_roll_total').css('color','black');
			    $('.yard_total').css('color','black');

	    		$('.btn-brg-save').attr('disabled',false);
	    	}else{
	    		if (total_roll > jumlah_roll_stok ) {
			    	$('.jumlah_roll_total').css('color','red');
			    	// alert('stok over : '+total_roll+' > '+jumlah_roll_stok);
	    		};
	    		if (total > total_stok) {
			    	$('.yard_total').css('color','red');
			    	// alert('stok over : '+total+' > '+total_stok);
	    		};
	    		$('.btn-brg-save').attr('disabled',true);
	    	}


	    	$('.yard_total').html(total.toFixed(2));
	    	$('.jumlah_roll_total').html(total_roll);
	    	$('[name=rekap_qty]').val(rekap.join('--'));
	    });

	//====================================qty edit manage=============================    
        
        $('#general_table').on('click','.btn-edit', function(){
        	var ini = $(this).closest('tr');
        	form = $('#form_edit_barang');
        	stok_div = $('#stok-info-edit');

        	form.find("[name=penjualan_detail_id]").val(ini.find(".id").html());
        	form.find("[name=barang_id]").val(ini.find(".barang_id").html());
        	form.find("[name=warna_id]").val(ini.find(".warna_id").html());
        	form.find("[name=gudang_id]").val(ini.find(".gudang_id").html());
        	form.find("[name=harga_jual]").val(ini.find(".harga_jual").html());
        	form.find("[name=satuan]").val(ini.find(".nama_satuan").html());
        	form.find("[name=rekap_qty]").val(ini.find(".data_qty").html());

        	form.find("[name=barang_id]").change();
        	form.find("[name=warna_id]").change();

        	get_qty(form, stok_div);
        });
        // get_qty($("#form_add_barang"), $('#stok_info'));
		

		$(document).on("click",".btn-edit-qty-row",function(){
			var jml_baris = $('#qty-table-edit tbody tr').length;
	    	var baris = `<tr><td>${jml_baris+1}</td><td><input name='qty'></td>
									<td><input name='jumlah_roll'></td>
									<td></td></tr>`;

	    	$('#qty-table-edit').append(baris);
	    });

		$('.btn-qty-edit').click(function(){
			var form = $('#form_edit_barang');
			$('#qty-table-edit tbody').html('');
			var data_qty = form.find('[name=rekap_qty]').val();
			// $('#form-qty-update [name=rekap_qty]').val(data_qty);
			// $('#form-qty-update [name=id]').val($(this).closest('tr').find('.id').html());
			var data_break  = data_qty.split('--');
			console.log(data_break);
			
			var i = 0; var total = 0; var idx = 1;
			$.each(data_break, function(k,v){
				var qty = v.split('??');
				if (qty[1] == null) {
					qty[1] = 0;
				};
				total += qty[0]*qty[1]; 
				if (i == 0 ) {
					var baris = "<tr>"+
						"<td>"+idx+"</td>"+
						"<td><input name='qty' value='"+qty_float_number(qty[0])+"' class='input1'></td>"+
						"<td><input name='jumlah_roll' value='"+qty[1]+"'></td>"+
						"<td><button tabindex='-1' class='btn btn-xs blue btn-edit-qty-row'><i class='fa fa-plus'></i></button></td>"+
						"</tr>";
					idx++;
					$('#qty-table-edit tbody').append(baris);

				}else{
					var baris = "<tr>"+
						"<td>"+idx+"</td>"+
						"<td><input name='qty' value='"+qty_float_number(qty[0])+"' ></td>"+
						"<td><input name='jumlah_roll' value='"+qty[1]+"'></td>"+
						"<td></td>"+
						"</tr>";
					idx++;

					$('#qty-table-edit tbody').append(baris);
				}
				i++;
			});

			for (var i = 0; i < 5; i++) {
				var baris = "<tr>"+
						"<td>"+idx+"</td>"+
						"<td><input name='qty' value='' class='input1'></td>"+
						"<td><input name='jumlah_roll' value=''></td>"+
						"<td></td>"+
						"</tr>";
				idx++;
				$('#qty-table-edit tbody').append(baris);	
			};

			update_qty_edit();
		});


		$("#qty-table-edit").on('change',"input",function(){
	    	update_qty_edit();    	
	    });

		$(".btn-brg-edit-save").click(function(){
			// var data = {};
			// var id = $('#form-qty-update [name=id]').val();
			// var row = $('#id_'+id);
			// var ini = $('#id_'+id);
			// // alert(row.find('.jumlah_roll').html());
			// data['penjualan_detail_id'] = id;
			// data['rekap_qty'] = $('#form-qty-update [name=rekap_qty]').val();
			// var url = 'transaction/penjualan_qty_detail_update';
			// ajax_data_sync(url,data).done(function(data_respond  /*,textStatus, jqXHR*/ ){
			// 	// alert(data_respond);
			// 	if (data_respond == 'OK') {				
			// 		$('#portlet-config-qty-edit').modal('toggle');
						
			// 		var qty = $('#portlet-config-qty-edit .yard_total').html();
			// 		var total_roll = $('#portlet-config-qty-edit .jumlah_roll_total').html();

			// 		var harga = ini.find('[name=harga_jual]').val();
			// 		// alert(harga);
			// 		var subtotal = parseFloat(qty) * reset_number_format(harga);
			// 		// alert(subtotal);
			// 		ini.find('.subtotal').html(change_number_format(subtotal));
			// 		ini.find('.jumlah_roll').html(total_roll);
			// 		ini.find('.qty').html(qty);
			// 		ini.find('.data_qty').html(data['rekap_qty']);
			// 		update_table();
			// 	}else{
			// 		bootbox.confirm("Error, tolong muat ulang halaman", function(respond){
			// 			if(respond){
			// 				window.location.reload();
			// 			}
			// 		});
			// 	};
	  		//	});

			$('#form_edit_barang').submit();

		});

	//====================================update harga=============================    
	
		$('#general_table').on('change','[name=harga_jual]', function(){
			var ini = $(this).closest('tr');
			var data = {};
			data['id'] = ini.find('.id').html();
			data['harga_jual'] = $(this).val();
			var url = "transaction/update_penjualan_detail_harga";
			var qty = ini.find('.qty').html();
			ajax_data_sync(url,data).done(function(data_respond  /*,textStatus, jqXHR*/ ){
				if (data_respond == 'OK') {
					var subtotal = qty*data['harga_jual'];
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


	    $('.btn-brg-save').click(function(){
	    	var ini = $(this);
	    	var yard = reset_number_format($('.yard_total').html());
	    	if( yard > 0){
	    		$('#form_add_barang').submit();
	            btn_disabled_load(ini);

	    	}
	    });


	    $('.btn-save').click(function(){
	    	var ini = $(this);
	    	var penjualan_type_id = $('#form_add_data [name=penjualan_type_id]').val();
	    	if ($('#form_add_data [name=tanggal]').val() != '') {
	    		if (penjualan_type_id == 1 || penjualan_type_id == 2 ) {
	    			if($('#form_add_data [name=customer_id]').val() != ''){
	    				$('#form_add_data').submit();
	    			}else{
	    				notific8('ruby','Mohon pilih customer');
	    			}
	    		}else{
	    			$('#form_add_data').removeAttr('target');
	    			$('#form_add_data').submit();
	    			btn_disabled_load($(this));
	    		};
	    	}else{
	    		alert("Mohon isi tanggal !");
	    	};
	    });

	    var idx_submit = 1;
	    $('.btn-save-tab').click(function(){
	    	let ini = $(this);
	    	let penjualan_type_id = $('#form_add_data [name=penjualan_type_id]').val();
	    	if ($('#form_add_data [name=tanggal]').val() != '') {
	    		if (penjualan_type_id == 1 || penjualan_type_id == 2 ) {
	    			if($('#form_add_data [name=customer_id]').val() != ''){
	    				$('#form_add_data').submit();
	    			}else{
	    				notific8('ruby','Mohon pilih customer');
	    			}
	    		}else{
					idx++;
	    			$('#form_add_data').attr('target','_blank');
	    			$('#portlet-config').modal('toggle');
	    			$('#form_add_data [name=nama_keterangan]').val('');
	    			btn_disabled_load($('.btn-save-tab'));
	    			setTimeout(function(){
	    				if (idx_submit == 2) {
			    			$('#form_add_data').submit();
	    				}else{
	    					idx_submit = 2;
	    				};
		    			$(".btn-active").prop('disabled',false);
					    $('.btn-save-tab').html("Save & New Tab");
					    // alert(idx_submit);
	    			},2000);
	    		};
	    	}else{
	    		alert("Mohon isi tanggal !");
	    	};
	    });

	    $('.btn-edit-save').click(function(){
	    	var penjualan_type_id = $('#form_edit_data [name=penjualan_type_id]').val();
	    	if ($('#form_edit_data [name=tanggal]').val() != '') {
	    		if (penjualan_type_id == 1 || penjualan_type_id == 2 ) {
	    			if($('#form_edit_data [name=customer_id]').val() != ''){
	    				$('#form_edit_data').submit();
	    			}else{
	    				notific8('ruby','Mohon pilih customer');
	    			}
	    		}else{
	    			$('#form_edit_data').submit();
	    		};
	    	}else{
	    		alert("Mohon isi tanggal !");
	    	};
	    });

	//====================================bayar==========================================
		var saldo_awal ='<?=$saldo_awal;?>';
		<?if ($penjualan_id != '') {?>

			$('.bayar-input').dblclick(function(){
				var id_data = $(this).attr('id').split('_');
				var penjualan_type_id = "<?=$penjualan_type_id?>";
				var ini = $(this);

				if ($(this).val() == 0 || $(this).val() == '' ) {
					var g_total = reset_number_format($('.g_total').html());
					var total_bayar = reset_number_format($('.total_bayar').html());
					var sisa = parseInt(g_total) - parseInt(total_bayar);

					if (sisa > 0) {
						if ($(this).hasClass('bayar-kredit') && penjualan_type_id != 2) {

						}else{
							$(this).val(change_number_format(sisa));
							var data = {};
							data['pembayaran_type_id'] = id_data[1];
							data['penjualan_id'] = '<?=$penjualan_id?>';
							data['amount'] = ini.val();
							var url = 'transaction/pembayaran_penjualan_update';
							update_db_bayar(url, data);
						};
					};
					
				};
			});

			var bayar = true;
			$('#bayar-data tr td').on('change','input', function(){
				var id_data = $(this).attr('id').split('_');
				if (id_data[1] == 1) {
					var s_awal = reset_number_format(saldo_awal);
					var isi = $(this).val();
					var dp_initial = reset_number_format($('.dp_copy').html());
					var sisa = parseInt(s_awal) + dp_initial - reset_number_format(isi);
					// alert(s_awal+'+'+dp_initial+'+'+isi);
					if (sisa >= 0) {
						// alert('true');
						bayar = true;
					}else{
						$(this).val(0);
						bayar == false;
						alert('Saldo Tidak Cukup');
					};
				};

				if (bayar) {
					var data = {};
					data['pembayaran_type_id'] = id_data[1];
					data['penjualan_id'] = '<?=$penjualan_id?>';
					data['amount'] = $(this).val();
					var penjualan_type_id = "<?=$penjualan_type_id?>";
					if (data['pembayaran_type_id'] == 5 && penjualan_type_id != 2 ) {
						data['amount'] = 0;
						$(this).val(0);
						alert("Tipe bukan kredit pelanggan");
					}
					var url = 'transaction/pembayaran_penjualan_update';
					update_db_bayar(url, data);
				};
			});			

		<?};?>

		$(document).on('change', '.keterangan_bayar',function(){
			var data = {};
	    	data['penjualan_id'] =  "<?=$penjualan_id;?>";
	    	data['keterangan'] = $(this).val();
	    	var url = 'transaction/pembayaran_transfer_update';
	    	
	    	// alert(data['keterangan']);
	    	ajax_data_sync(url,data).done(function(data_respond  /*,textStatus, jqXHR*/ ){
				if (data_respond == 'OK') {
					// update_table();
				}else{
					bootbox.confirm("Error, tolong muat ulang halaman", function(respond){
						if(respond){
							window.location.reload();
						}
					});
				};
	   		});
		});

	//==============================================================================

	    <?if ($penjualan_id != '') { ?>
	    	$(document).on('change','.diskon, .ongkos_kirim, .keterangan ', function(){
	    		var value = $(this).val();
	    		if ($(this).attr('name') != 'keterangan') {
	    			value = reset_number_format(value);
	    		};
		    	var ini = $(this).closest('tr');
		    	var data = {};
		    	data['column'] = $(this).attr('name');
		    	data['penjualan_id'] =  "<?=$penjualan_id;?>";
		    	data['value'] = value;
		    	var url = 'transaction/penjualan_data_update';
		    	// update_table(ini);
		    	ajax_data_sync(url,data).done(function(data_respond  /*,textStatus, jqXHR*/ ){
					if (data_respond == 'OK') {
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
	    <?}?>

	    <?if ($penjualan_id != '') {?>
	    	$('.btn-close').click(function(){
	    		var kembali = reset_number_format($('.kembali').html());
	    		var g_total = reset_number_format($('.g_total').html());
	    		var tanggal = "<?=$ori_tanggal;?>";
	    		var id = "<?=$penjualan_id;?>";
	    		if (g_total <= 0) {
	    			// bootbox.alert("Error! Total tidak boleh 0");
    				bootbox.confirm("Total nota 0, Yakin untuk melanjutkan", function(respond){
    					if (respond) {
		    				window.location.replace(baseurl+'transaction/penjualan_list_close?id='+id+"&tanggal="+tanggal);
    					};
    				});
	    			// $("#portlet-config-sample").modal('toggle');
	    			// setTimeout(function(){
	    			// 	$("#form-sample").find('.pin_user').focus();
	    			// },700);
	    		}else if (penjualan_type_id != 2) {
	    			if (kembali >= 0 ) {
	    				window.location.replace(baseurl+'transaction/penjualan_list_close?id='+id+"&tanggal="+tanggal);
	    			}else{
	    				bootbox.alert('Kembali tidak boleh minus');
	    			}
	    		}else{
			    	window.location.replace(baseurl+'transaction/penjualan_list_close?id='+id+"&tanggal="+tanggal);
	    		}
		    });
	    <?}?>

	    $(".btn-sample-ok").click(function() {
	    	var id = "<?=$penjualan_id?>";
			var tanggal = "<?=$ori_tanggal;?>";

	    	if(cek_pin($('#form-sample'))) {
				$('#form-sample').submit()
	    	}
	    })

	//=====================================remove barang=========================================
		$('#general_table').on('click','.btn-detail-remove', function(){
			var ini = $(this).closest('tr');
			bootbox.confirm("Yakin mengahpus item ini?", function(respond){
				if (respond) {
					var data = {};
					data['id'] = ini.find('.id').html();
					var url = 'transaction/penjualan_list_detail_remove';
					ajax_data_sync(url,data).done(function(data_respond  /*,textStatus, jqXHR*/ ){
						if (data_respond == "OK") {
							ini.remove();
							update_table();
						}else{
							alert("Error");
						}
					}); 
				};
			});
		}) ;  

	//=====================================bayar giro=========================================
		$(".btn-save-giro").click(function(){
			if ($('#form-data-giro [name=nama_bank]').val() != '' && $('#form-data-giro [name=no_rek_bank]').val() != '' && $('#form-data-giro [name=tanggal_giro]').val() != '' && $('#form-data-giro [name=jatuh_tempo]').val() != '' && $('#form-data-giro [name=no_akun]').val() != '' ) {
				$('#form-data-giro').submit();
			}else{
				alert("mohon lengkapi data giro")
			};
		});


	//=====================================bayar dp=========================================

		$('#dp_list_table').on('change','.dp-check', function(){
			let ini = $(this).closest('tr');
			var bon = reset_number_format($(".dp-nilai-bon-info").html());
			var bayar_dp = reset_number_format($('.dp-total').html());
			var sisa = bon - bayar_dp;
			// alert($(this).is(':checked'));
			if($(this).is(':checked')){
				let dp_nilai = reset_number_format(ini.find('.amount').html());
				ini.find('.amount-bayar').prop('readonly',false);
				if (sisa > dp_nilai) {
					ini.find('.amount-bayar').val(dp_nilai);
				}else{
					ini.find('.amount-bayar').val(sisa);
				}
			}else{
				ini.find('.amount-bayar').prop('readonly',true);
				ini.find('.amount-bayar').val(0);
			}
			dp_table_update();
		});

		$('#dp_list_table').on('change','.amount-bayar', function(){
			let ini = $(this).closest('tr');
			dp_table_update();
		});
		
		$('.btn-save-dp').click(function(){
			$('#form-dp').submit();
			btn_disabled_load($(this));
		});

	//========================================btn-detail============================

		$(".btn-detail-toggle").click(function(){
			$('#general-detail-table').toggle('slow');
		});

});
</script>

<?
$nama_toko = '';
$alamat_toko = '';
$telepon = '';
$fax = '';
$npwp = '';



if ($penjualan_id != '') {

	foreach ($data_toko as $row) {
		$nama_toko = trim($row->nama);
		$alamat_toko = trim($row->alamat.' '.$row->kota);
		$telepon = trim($row->telepon);
		$fax = trim($row->fax);
		$npwp = trim($row->NPWP);

	}

	$garis1 = "'-";
	$garis2 = "=";

	// include_once 'print_faktur.php';
	// include_once 'print_detail.php';
	// include_once 'print_faktur_detail.php';

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

	if ($printer_marker == 3) {
		include_once 'print/print_faktur_3.php';
		include_once 'print/print_detail_3.php';
		include_once 'print/print_faktur_detail_3.php';
		include_once 'print/print_surat_jalan_3.php';
		include_once 'print/print_surat_jalan_noharga_3.php';
	}else{
		include_once 'print_faktur.php';
		include_once 'print_detail.php';
		include_once 'print_faktur_detail.php';

		include_once 'print_surat_jalan.php';
		include_once 'print_surat_jalan_noharga.php';
	}

	if (is_posisi_id() == 1) {
		// include_once 'print_font_test.php';
		// include_once 'print_testing.php';
	}
}?>

<script>

function dp_update_bayar(){
	var dp_total = reset_number_format($("#dp_list_table .dp-total").html());
	var g_total = $(".dp-nilai-bon-info").html();
	g_total = reset_number_format(g_total);
	$('#dp_list_table .dp-sisa-info').html(change_number_format(g_total - dp_total));
}

var populatePrinters = function(printers){
    var printerlist = $("#printer-name");
    var test = [];
    // printerlist.html('');
    for (var i in printers){
    	test.push('<option value="'+printers[i]+'">'+printers[i]+'</option>');
        // printerlist.append('<option value="'+printers[i]+'">'+printers[i]+'</option>');
    }
    // return test.join('||');
};

function update_db_bayar(url,data){
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
}

function get_qty(form, stok_div){
    var data = {};
    data['gudang_id'] = form.find(' [name=gudang_id]').val();
    data['barang_id'] = form.find(' [name=barang_id]').val();
    data['warna_id'] = form.find(' [name=warna_id]').val();
    data['penjualan_detail_id'] = form.find(' [name=penjualan_detail_id]').val();
    // alert(form.find(' [name=penjualan_detail_id]').val());
    data['tanggal'] = form.find(' [name=tanggal]').val();
    var url = "transaction/get_qty_stock_by_barang";
    // alert(data['gudang_id']);
    ajax_data_sync(url,data).done(function(data_respond  /*,textStatus, jqXHR*/ ){
        // alert(data_respond);
        $.each(JSON.parse(data_respond), function(k,v){
            // alert(v.qty);
            stok_div.find('.stok-qty').html(v.qty);
            stok_div.find('.stok-roll').html(v.jumlah_roll);
        });
        // alert(data_respond);
        // console.log(data_respond);
    });
}

function dp_table_update(){
	let total_dp = 0;
	$('#dp_list_table .amount-bayar').each(function(){
		total_dp += reset_number_format($(this).val());
	});

	$('.dp-total').html(change_number_format(total_dp));

	dp_update_bayar();

}


function cek_last_input(gudang_id_last,barang_id, harga_jual){
	setTimeout(function(){
		// $('#barang_id_select').select2("open");
		$('#gudang_id_select').val(gudang_id_last);
		$('#barang_id_select').val(barang_id);
    	$('#barang_id_select, #gudang_id_select').change();
    	setTimeout(function(){
        	$('#harga_jual_add').val(harga_jual);
    	},700);

	},650);
}

function print_try(idx){
	if (idx == 1) {
		qz.websocket.connect().then(function() {
		});
	};

	var config = qz.configs.create("Nota");             // Exact printer name from OS
  	var data = ['\x1B' + '\x40',
  		<?for ($i=0; $i <= 100 ; $i++) { ?>
  			'\x1B' + '\x21' + '\x<?=str_pad($i,2,"0",STR_PAD_LEFT);?>', // em mode on
		   	'<?=$i;?>'+' : 1234567890',
		   	'\x0A',
  		<?}?>


  	];

  	qz.print(config, data).then(function() {
	   // alert("Sent data to printer");
	});
}

function save_penjualan_baru(ini){
	ini.prop('disabled',true);
	// $('#form_add_data').submit();
	setTimeout(function(){
		ini.prop('disabled',false);
	},2000);
}

function startConnection(config) {
    qz.websocket.connect().then(function() {
	   	alert("Connected!");
		find_printer();
	});

}

function find_printer(){
	qz.printers.find("Nota").then(function(found) {
	   	alert("Printer: " + found);
	 //   	var config = qz.configs.create("Nota");             // Exact printer name from OS
		// var data = ['^XA^FO50,50^ADN,36,20^FDRAW ZPL EXAMPLE^FS^XZ'];   // Raw commands (ZPL provided)
		// qz.print(config, data).then(function() {
		//    alert("Sent data to printer");
		// });
	});
}




function cek_pin(form){
	// alert('test');
	var data = {};
	data['pin'] = form.find('.pin_user').val();
	var url = 'transaction/cek_pin';
	// ajax_data_sync(url,data).done(function(data_respond  /*,textStatus, jqXHR*/ ){
	// 	if (data_respond == "OK") {
	// 		return true;
	// 	}else{
	// 		alert("PIN Invalid");
	// 	}
	// });

	var result = ajax_data(url,data);
	if (result == 'OK') {
		return true;
	};
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

function update_qty_edit(){
    var total = 0; var idx = 0; var rekap = [];
	var total_roll = 0;
	var total_stok = $('#stok-info-edit').find('.stok-qty').html();
	var jumlah_roll_stok = $('#stok-info-edit').find('.stok-roll').html();

	$("#qty-table-edit [name=qty]").each(function(){
		var ini = $(this).closest('tr');
		var qty = $(this).val();
		var roll = ini.find('[name=jumlah_roll]').val();
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
		}

		if (roll == 0) {
    		var subtotal = parseFloat(qty);
    		total_roll += 0;
		}else{
    		var subtotal = parseFloat(qty*roll);
    		// alert(qty+'*'+roll);
    		total_roll += parseInt(roll);
    		console.log(subtotal);
		};

		if (qty != '' && roll != '') {
			rekap[idx] = qty+'??'+roll;
		};
		idx++;  
		total += subtotal;

	});

	// alert(total);
	if (total > 0 &&  total <= total_stok && total_roll <= jumlah_roll_stok) {
		$('.btn-brg-edit-save').attr('disabled',false);
	}else{
		$('.btn-brg-edit-save').attr('disabled',true);
	}

	$('#portlet-config-qty-edit .jumlah_roll_total').html(total_roll);
	$('#portlet-config-qty-edit .yard_total').html(total.toFixed(2));

	$('#form-qty-update [name=rekap_qty]').val(rekap.join('--'));
	$('#form_edit_barang [name=rekap_qty]').val(rekap.join('--'));


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
</script>
