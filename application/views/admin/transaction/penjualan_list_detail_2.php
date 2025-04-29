<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css'); ?>"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css'); ?>"/>

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
	width: 65px;
	padding: 5px;
}

#qty-table-stok tbody, #qty-table-stok-edit tbody{
	border: 1px solid #ddd;
}

#qty-table-stok tr td, #qty-table-stok-edit tr td{
	padding: 2px 5px;
	border: 1px solid #ddd;
}

.stok-info{
	font-size: 1.5em;
	/*position: absolute;*/
	right: 50px;
	top: 30px;
}

.yard-info, .yard-info-edit{
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

</style>

<div class="page-content">
	<div class='container'>
		<?
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

			$jatuh_tempo = date('d/m/Y', strtotime("+60 days") );
			$ori_jatuh_tempo = '';
			$status = -99;

			$revisi = 0;

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
			$disabled_status = '';
			$alamat_customer = '';
			$npwp_customer = '';

			foreach ($penjualan_data as $row) {
				$tipe_penjualan = $row->tipe_penjualan;
				$penjualan_id = $row->id;
				$customer_id = $row->customer_id;
				$nama_customer = $row->nama_keterangan;
				$alamat_customer = $row->alamat_keterangan;
				// $npwp_customer = $row->npwp_customer;
				$gudang_id = $row->gudang_id;
				$nama_gudang = $row->nama_gudang;
				$no_faktur = $row->no_faktur;
				$penjualan_type_id = $row->penjualan_type_id; 
				$po_number = $row->po_number;
				$fp_status = $row->fp_status;
				
				$tanggal_print = date('d F Y', strtotime($row->tanggal));

				$tanggal = is_reverse_date($row->tanggal);
				$ori_tanggal = $row->tanggal;
				$status_cek = 0;
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
				// $revisi = $row->revisi - 1;
				$no_surat_jalan = $row->no_surat_jalan;
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

			$ary_filter = array("\n","\r", "<br>");
			$alamat_keterangan = str_replace($ary_filter," ",$alamat_keterangan);

			$alamat1 = substr(strtoupper(trim($alamat_keterangan)), 0,46);
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

			$keterangan1 = substr(strtoupper(trim($keterangan)), 0,47);
		   	$keterangan2 = substr(strtoupper(trim($keterangan)), 47);
			$last_ket1 = substr($keterangan1, -1,1);
			$last_ket2 = substr($keterangan2, 0,1);

			$positions = array();
			$pos = -1;
			while (($pos = strpos(trim($keterangan)," ", $pos+1 )) !== false) {
				$positions[] = $pos;
			}

			$max = 47;
			if ($last_ket1 != '' && $last_ket2 != '') {
				$posisi_ket =array_filter(array_reverse($positions),
					function($value) use ($max) {
						return $value <= $max;
					});

				$posisi_ket = array_values($posisi_ket);

				$keterangan1 = substr(strtoupper(trim($keterangan)), 0,$posisi_ket[0]);
			   	$keterangan2 = substr(strtoupper(trim($keterangan)), $posisi_ket[0]);
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

			                <div class="form-group add-alamat-keterangan">
			                    <label class="control-label col-md-3">Keterangan
			                    </label>
			                    <div class="col-md-6">
			                    	<input name='keterangan' maxlength='80' class='form-control'>
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

			                <div class="form-group add-alamat-keterangan">
			                    <label class="control-label col-md-3">Catatan
			                    </label>
			                    <div class="col-md-6">
			                    	<input name='keterangan' maxlength='90' class='form-control' value="<?=$keterangan;?>">
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
			                    	<span class='barang_id_before' hidden></span>
			                    	<span class='warna_id_before' hidden></span>
			                    	<span class='gudang_id_before' hidden></span>
			                    	<input name='penjualan_id' value='<?=$penjualan_id;?>' hidden>
			                    	<input name='tanggal' value='<?=$tanggal;?>' hidden>
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
			                    <label class="control-label col-md-3">Keterangan<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
	                    			<select name="warna_id" class='form-control' id='warna_id_select'>
		                				<option value=''>Pilihan..</option>
			                    		<?foreach ($this->warna_list_aktif as $row) { ?>
			                    			<option value="<?=$row->id?>"><?=$row->warna_jual;?></option>
			                    		<?}?>
			                    	</select>
			                    </div>
			                </div> 

			                <div class="form-group">
			                    <label class="control-label col-md-3">Harga Jual<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
		                    		<input type="text" class='form-control amount_number' id='harga_jual_add_noppn' name="harga_jual"/>
			                    	<input name='rekap_qty' <?=(is_posisi_id() != 1 ? 'hidden' :'' );?> >
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
						<table width='80%'>
							<tr>
								<td width='50%' style='vertical-align:top'>
									<span style='font-size:1.2em'>AMBIL</span>
									<table id='qty-table'>
										<thead>
											<tr>
												<th class='nama_satuan'>Yard</th>
												<th class='nama_packaging'>Roll</th>
												<th></th>
											</tr>
										</thead>
										<tbody>
											<tr>
													<td><input name='qty' value='' class='input1'></td>
													<td><input name='jumlah_roll' value=''></td>
													<td hidden>
														<span class='qty-get'></span><span class='roll-get'></span>
													</td>
													<td>
														<button tabindex='-1' class='btn btn-xs blue btn-add-qty-row'><i class='fa fa-plus'></i></button>
													</td>
												</tr>
											<?for($i = 0 ; $i < 9 ; $i++){?>
												<tr>
													<td><input name='qty' value=''></td>
													<td><input name='jumlah_roll' value=''></td>
													<td hidden><span class='qty-get'></span><span class='roll-get'></span></td>
												</tr>
											<?} ?>
										</tbody>
									</table>

									<div class='yard-info'>
										TOTAL QTY: <span class='yard_total' >0</span> <span class='nama_satuan'>yard</span> <br/>
										TOTAL ROLL: <span class='jumlah_roll_total' >0</span> ROLL
									</div>
								</td>
								<td style='vertical-align:top'>
									<span style='font-size:1.2em'>STOK</span>
									<div style='height:340px; overflow:auto'>
										<table id='qty-table-stok'>
											<thead>
												<tr>
													<th class='nama_satuan'  style='width:45px'></th>
													<th class='nama_packaging' style='width:45px'></th>
												</tr>
											</thead>
											<tbody></tbody>
											
										</table>
									</div>
									
									<div class='stok-info' id='stok-info-add' style='border:1px solid #ddd; padding:5px; margin-bottom:20px;'>
										STOK QTY : <span class='stok-qty'>0</span><br/>
										STOK ROLL : <span class='stok-roll'>0</span>
									</div>
								</td>
							</tr>
						</table>

						
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

		<div class="modal fade" id="portlet-config-qty-edit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body">
						<?//GA BISA JADI EDIT BIASA KARENA TAKUT QTY NYA NGACO?>
						<form id="form_qty_edit" method='post' action="<?=base_url()?>transaction/penjualan_qty_detail_update">
	                    	<table>
	                    		<tr>
	                    			<td>HARGA JUAL</td>
	                    			<td class='padding-rl-5'></td>
	                    			<td><input name='harga_jual' class='form-control amount_number' ></td>
	                    		</tr>
	                    	</table>
	                    	<input name='rekap_qty' hidden >
	                    	<input name='penjualan_list_detail_id' hidden >
	                    	<input name='penjualan_id' hidden >
						</form>
						<hr/>
						<table width='80%'>
							<tr>
								<td width='50%' style='vertical-align:top'>
									<span style='font-size:1.2em'>AMBIL</span>
									<table id='qty-table-edit'>
										<thead>
											<tr>
												<th class='nama_satuan'></th>
												<th class='nama_packaging'></th>
												<th></th>
											</tr>
										</thead>
										<tbody>
											<tr>
													<td><input name='qty' value='' class='input1'></td>
													<td><input name='jumlah_roll' value=''></td>
													<td hidden><input name='penjualan_qty_detail_id' class='penjualan_qty_detail_id'></td>
													<td hidden>
														<span class='qty-get'></span><span class='roll-get'></span>
													</td>
													<td>
														<button tabindex='-1' class='btn btn-xs blue btn-add-qty-row'><i class='fa fa-plus'></i></button>
													</td>
												</tr>
											<?for($i = 0 ; $i < 6 ; $i++){?>
												<tr>
													<td><input name='qty' value=''></td>
													<td><input name='jumlah_roll' value=''></td>
													<td hidden><input name='penjualan_qty_detail_id' class='penjualan_qty_detail_id'></td>
													<td hidden><span class='qty-get'></span><span class='roll-get'></span></td>
												</tr>
											<?} ?>
										</tbody>
									</table>

									<div class='yard-info-edit'>
										TOTAL QTY: <span class='yard_total' >0</span> <span class='nama_satuan'>yard</span> <br/>
										TOTAL ROLL: <span class='jumlah_roll_total' >0</span> <span class='nama_packaging'>roll</span>
									</div>
								</td>
								<td style='vertical-align:top'>
									<span style='font-size:1.2em'>STOK</span>
									<div style='height:240px; overflow:auto'>
										<table id='qty-table-stok-edit'>
											<thead>
												<tr>
													<th class='nama_satuan'  style='width:45px'></th>
													<th class='nama_packaging' style='width:45px'></th>
												</tr>
											</thead>
											<tbody></tbody>
											
										</table>
									</div>
									
									<div class='stok-info' id='stok-info-edit' style='border:1px solid #ddd; padding:5px; margin-bottom:20px;'>
										STOK QTY : <span class='stok-qty'>0</span><br/>
										STOK ROLL : <span class='stok-roll'>0</span>
									</div>
								</td>
							</tr>
						</table>
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
						<h3 class='block'> Penjualan Baru</h3>
						
						<div class="form-group">
		                    <label class="control-label col-md-3">Type<span class="required">
		                    * </span>
		                    </label>
		                    <div class="col-md-6">
		                    	<input name='print_target' hidden>
		                    	<select class='form-control' id='printer-name'>
		                    		<?foreach ($printer_list as $row) { ?>
		                    			<option value='<?=$row->id;?>'><?=$row->nama;?></option>
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
												<?=$row->bayar_dp;?> : 
												<?
												$type_2 = '';
												$type_3 = '';
												$type_4 = '';
												$type_6 = '';
												${'type_'.$row->pembayaran_type_id} = 'hidden';
												?>
												<ul>
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
												<span class='amount'><?=number_format($row->amount,'0','.',',');?></span>
											</td>
											<td>
												<?$dp_bayar += $row->amount_bayar;?>
												<input name="amount_<?=$row->id;?>" class='amount-bayar amount_number' value='<?=number_format($row->amount_bayar,'0','.',',');?>' <?=($row->amount_bayar == 0 ? 'readonly' : '');?> style="width:80px">
											</td>
											<td>
												<input name="penjualan_id" value="<?=$penjualan_id;?>" hidden>
												<span class='id' hidden="hidden"><?=$row->id;?></span>
												<input type="checkbox" class='dp-check' <?=($row->amount_bayar != 0 ? 'checked' : '');?> >
											</td>
										</tr>
									<? } ?>
									<tr>
										<td colspan='3'></td>
										<td><b>TOTAL</b></td>
										<td><span class='dp-total' style='font-size:1.3em'><?=number_format($dp_bayar,'0','.',',');?></span></td>
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
						<!-- table-striped table-bordered  -->
						<table class="table table-hover table-striped table-bordered" id="general_table">
							<thead>
								<tr>
									<th scope="col">
										No
									</th>
									<th scope="col">
										Nama Barang
										<?if ($penjualan_id !='' && $status == 1 && is_posisi_id() != 6 ) {?>
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
									<?/*if (is_posisi_id() == 1) {?>
										<th scope="col">
											Harga non ppn
										</th>
										<th scope="col">
											PPN
										</th>
										<th scope="col">
											Total Harga
										</th>
									<?}*/?>
									<th scope="col" class='hidden-print'>
										Action
									</th>
								</tr>
							</thead>
							<tbody>
								<?
								$total_nppn = 0;
								$idx =1; $barang_id = ''; $gudang_id_last = ''; $harga_jual = 0; $qty_total = 0; $roll_total = 0;
								foreach ($penjualan_detail as $row) { ?>
									<tr id='id_<?=$row->id;?>'>
										<td>
											<?=$idx;?> 
										</td>
										<td hidden>
											<?=$row->jenis_barang;?>
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
										<?/*if (is_posisi_id() == 1) {
											$harga_nppn = number_format($row->harga_jual/1.1,'4','.','');?>
											<td>
												<!-- <input name='harga_jual' <?=$readonly;?> class='free-input-sm amount_number harga_jual' 
												value="<?=number_format($row->harga_jual,'0','.','.');?>">  -->
												<?=number_format($harga_nppn,'4','.',',');?>
											</td>
											<td>
												<?
												$subtotal_nppn = number_format($row->qty * $harga_nppn,'2','.','');
												$ppn = $subtotal_nppn * 0.1;
												$total_nppn += $subtotal_nppn + $ppn
												?>
												<?=number_format($subtotal_nppn,'2','.',',');?>+
												<?=number_format($ppn,'2','.',',');?>
											</td>
											<td>
												<?=number_format($subtotal_nppn + $ppn,'2','.',',');?> 
											</td>
										<?}*/?>

										<td class='hidden-print'>
											<?$gudang_id_last=$row->gudang_id;?>
											<?if ($status == 1 || is_posisi_id() == 1) { ?>
												<?if (is_posisi_id() != 6) { ?>
													<span class='gudang_id' <?=(is_posisi_id() != 1 ? 'hidden' : '');?> ><?=$row->gudang_id;?></span>
													<span class='barang_id' <?=(is_posisi_id() != 1 ? 'hidden' : '');?> ><?=$row->barang_id;?></span>
													<span class='warna_id' <?=(is_posisi_id() != 1 ? 'hidden' : '');?> ><?=$row->warna_id;?></span>
													<span class='data_qty'  <?=(is_posisi_id() != 1 ? 'hidden' : '');?> ><?=$row->data_qty;?></span>
													<span class='id'  <?=(is_posisi_id() != 1 ? 'hidden' : '');?> ><?=$row->id;?></span>
													<a href='#portlet-config-qty-edit' data-toggle='modal' class="btn-xs btn green btn-edit"><i class="fa fa-edit"></i> </a>
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
									<?/*if (is_posisi_id() == 1) {?>
										<td></td>	
										<td></td>
										<td>
											<?=number_format($total_nppn,'0','.','.');?> 
										</td>
									<?}*/?>

									<td class='hidden-print'></td>
								</tr>
							</tbody>
						</table>
						<hr/>
							<p class='btn-detail-toggle' style='cursor:pointer'><b>Detail <i class='fa fa-caret-down'></i></b></p>
						
							<table id='general-detail-table' class='table table-bordered' hidden>
								<thead>
									<tr>
										<th>Barang</th>
										<th>Keterangan</th>
										<th>Qty</th>
										<th>Total</th>
										<th>Detail</th>
									</tr>
								</thead>
								<?
								if (is_posisi_id() == 1) {
									//print_r($penjualan_detail);
									# code...
								}
								foreach ($penjualan_detail as $row) {?>
									<tr>
										<td><?=$row->nama_barang?></td>
										<td><?=$row->nama_warna?></td>
										<td><?=$row->jumlah_roll?></td>
										<td><?=str_replace('.000', '',$row->qty)?></td>
										<td><?
											$data_qty = explode('=?=', $row->data_qty);
											$coll = 1;
											foreach ($data_qty as $key => $value) {
												$detail_qty = explode('??', $value);
												for ($i=1; $i <= $detail_qty[1] ; $i++) { 
													echo "<p style='display:inline-flex; width:50px; '>".str_replace('.000', '', $detail_qty[0])."</p>";
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

						<table style='width:100%'>
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
											if ($status == 0) {
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
												<tr <?=($penjualan_type_id == 3 ? "hidden" : '');?>>
													<td><?=$row->nama;?><span class='saldo_awal' hidden><?=$saldo_awal;?></span></td>
													<td>
														<a <?=($status == 1 ? "href='#portlet-config-dp' data-toggle='modal'" : '' );?> >
															<input readonly <?=$stat;?> style='<?=$style;?>' value="<?=number_format($bayar,'0','.',',');?>" >
														</a>
														<!--<a data-toggle="popover" style='color:black' data-trigger='focus' data-html="true" data-content="Saldo : <?=number_format($saldo_awal,'0','.',',');?>">
														</a>-->
														<span class='dp_copy' hidden><?=$bayar?></span>
													</td>
												</tr>
											<?}elseif ($row->id == 4) { ?>
												<tr>
													<td><?=$row->nama;?></td>
													<td>
														<input <?=$stat;?> style='<?=$style;?>' class='amount_number' id='bayar_<?=$row->id;?>' value="<?=number_format($bayar,'0','.',',');?>">
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
															<input <?=$stat;?> id='bayar_<?=$row->id;?>'  class='amount_number bayar-kredit' value="<?=number_format($bayar,'0','.',',');?>">
														</a>
													</td>
												</tr>
											<?}elseif ($row->id == 6) { ?>
												<tr hidden>
													<td><?=$row->nama;?></td>
													<td>
														<a data-toggle="popover" style='color:black' data-trigger='hover' data-html="true" data-content="Nama Bank : <b><?=$nama_bank?></b><br/>No Rek : <b><?=$no_rek_bank?></b><br/>No Akun : <b><?=$no_akun?></b><br/>Nama Bank : <b><?=$nama_bank?></b><br/>Tanggal Giro : <b><?=$tanggal_giro?></b><br/>Jatuh Tempo : <b><?=$jatuh_tempo_giro?></b><br/>">
															<input <?=$stat;?> style='<?=$style;?>' class='amount_number bayar-giro' id='bayar_<?=$row->id;?>' value="<?=number_format($bayar,'0','.',',');?>">
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
													<td><input <?=$stat;?> style='<?=$style;?>' class='amount_number' id='bayar_<?=$row->id;?>' value="<?=number_format($bayar,'0','.',',');?>"></td>
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
												<b>Rp <span class='total_bayar' style=''><?=number_format($bayar_total,'0','.',',');?></span></b>
											</td>
										</tr>
										<tr style='border:2px solid #ffd7b5'>
											<td class='padding-rl-25' style='background:#ffd7b5'>TOTAL</td>
											<td class='text-right padding-rl-10'> 
												<b>Rp <span class='g_total' style=''><?=number_format($g_total - $diskon,'0','.',',');?></span></b>
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
												<b>Rp <span class='kembali' style='<?=$kembali_style;?>'><?=number_format($kembali,'0','.',',');?></span></b>
											</td>
										</tr>
									</table>
								</td>
							</tr>

						</table>
						<hr/>
						<?if ($penjualan_id != '' && $status == 0) {?>
							<label>
								<input type='checkbox' id="view-ppn" checked /> Munculkan PPN di nota
							</label>
							<hr/>
						<?}?>
						<div>
							<button type='button'<?if ($idx == 1) { echo 'disabled'; }?> <?=$disabled;?> <?if ($status != 1) {?> disabled <?}?> class='btn btn-lg red hidden-print btn-close'><i class='fa fa-lock'></i> LOCK </button>
			                
			                <button <?=($status != 0 ? 'disabled' : '')?>  type="button" href='#portlet-config-print' data-toggle='modal' class="btn btn-lg blue btn-faktur-print print-ppn"><i class='fa fa-print'></i> Faktur</button>
                            <button <?=($status != 0 ? 'disabled' : '')?> type="button" href='#portlet-config-print' data-toggle='modal' class="btn btn-lg blue btn-print-kombi print-ppn"><i class='fa fa-print'></i> Faktur + Detail</button>
                            <button <?=($status != 0 ? 'disabled' : '')?> type="button" href='#portlet-config-print' data-toggle='modal' class="btn btn-lg green btn-surat-jalan print-ppn"><i class='fa fa-print'></i>Surat Jalan</button>

                            <button <?=($status != 0 ? 'disabled' : '')?>  type="button" href='#portlet-config-print' data-toggle='modal' class="btn btn-lg blue btn-faktur-print-2 print-noppn" style='display:none' ><i class='fa fa-print'></i> Faktur <i class='fa fa-eye-slash'></i> PPN</button>
                            <button <?=($status != 0 ? 'disabled' : '')?> type="button" href='#portlet-config-print' data-toggle='modal' class="btn btn-lg blue btn-print-kombi-2 print-noppn" style='display:none' ><i class='fa fa-print'></i> Faktur + Detail <i class='fa fa-eye-slash'></i> PPN</button>
                            <button <?=($status != 0 ? 'disabled' : '')?> type="button" href='#portlet-config-print' data-toggle='modal' class="btn btn-lg green btn-surat-jalan-2 print-noppn" style='display:none' ><i class='fa fa-print'></i>Surat Jalan <i class='fa fa-eye-slash'></i> PPN</button>


				            <a <?if($disabled_status == ''){ ?>href="<?=base_url();?>transaction/penjualan_print?penjualan_id=<?=$penjualan_id;?>"<? }else{ echo $disabled_status; } ?> target='_blank' class='btn btn-lg yellow-gold btn-print hidden-print'>Faktur PDF <i class='fa fa-download'></i>  </a>

                            <?if (is_posisi_id() == 1) {?>
	                            <!-- <button type="button" href='#portlet-config-print' data-toggle='modal' class="btn btn-lg green btn-surat-jalan-noharga print-ppn"><i class='fa fa-print'></i>SJ No Harga</button> -->
	                            <!-- <button type="button" href='#portlet-config-print' data-toggle='modal' class="btn btn-print-`">TEST</button> -->
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
<script src="<?php echo base_url('assets/global/plugins/datatables/media/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js'); ?>" type="text/javascript"></script>


<script src="<?php echo base_url('assets_noondev/js/webprint.js'); ?>" type="text/javascript"></script>

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

var table_stok_page = 1;

jQuery(document).ready(function() {	

//===========================general=================================

		<?if($penjualan_id != '' && $status==0){?>

			$("#view-ppn").change(function(){
				if ($('#view-ppn').is(":checked")) {
					$('.print-noppn').hide();
					$('.print-ppn').show();
				}else{
					$('.print-ppn').hide();
					$('.print-noppn').show();
				}
			});

			webprint = new WebPrint(true, {
		        relayHost: "127.0.0.1",
		        relayPort: "8080",
		        readyCallback: function(){
		            
		        }
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

			$('.btn-print-test').click(function(){
				$('[name=print_target]').val('6');
				// print_detail();
			});

			$('.btn-faktur-print-2').click(function(){
				$('[name=print_target]').val('1a');
			});

			$('.btn-print-kombi-2').click(function(){
				$('[name=print_target]').val('3a');
				// print_detail();
			});

			$('.btn-surat-jalan-2').click(function(){
				$('[name=print_target]').val('4a');
				// print_detail();
			});
		<?}?>

		FormNewPenjualanDetail.init();

		var form_group = {};
		var idx_gen = 0;
		var print_idx = 1;
	   	var penjualan_type_id = '<?=$penjualan_type_id;?>';


		$('[data-toggle="popover"]').popover();


	    $('#warna_id_select,#warna_id_select_edit, #barang_id_select,#barang_id_select_edit').select2({
	        placeholder: "Pilih...",
	        allowClear: true
	    });

	    $('#customer_id_select, #customer_id_select_edit').select2({
	        allowClear: true
	    });

	    <?if ($penjualan_id != '') { ?>
			$('.btn-print').click(function(){
		    	// window.print();
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
	    	cek_pin();
	    });

	    $('#pin_user').keypress(function (e) {
	        if (e.which == 13) {
	        	cek_pin();
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
	   				// alert(change_number_format(data[1]));
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
		    		// console.log(data_respond)
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
	
		var barang_id = "<?=$barang_id;?>";
		var gudang_id_last = "<?=$gudang_id_last;?>";
		var idx = "<?=$idx;?>";
		var harga_jual = "<?=number_format($harga_jual,'0','.',',');?>";


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

//===================================change===========================================

	    <?if ($penjualan_id != '') { ?>
	    	$(document).on('change','.diskon, .diskon-persen, .ongkos_kirim, .keterangan ', function(){
	    		var value = $(this).val();
	    		var name = $(this).attr('name');
	    		if ($(this).attr('name') != 'keterangan' && $(this).attr('name') == 'diskon_persen') {
	    			value = reset_number_format(value);
	    		};

	    		if ($(this).attr('name') == 'diskon_persen') {
	    			value = value.toString().replace(',','.');
	    			var diskon = reset_number_format($('#subtotal-all').html()) * value/100;
	    			$('.diskon').val(diskon);
	    			name = 'diskon';
	    			value = diskon;
	    		};

	    		if ($(this).attr('name') == 'diskon') {
	    			value = reset_number_format(value);
	    			// alert(value);
	    			value = value.toString().replace(',','.');
	    			// alert(value);
	    			var diskon = value / reset_number_format($('#subtotal-all').html()) * 100;
	    			diskon = diskon.toFixed(2)
	    			diskon = diskon.toString().replace('.',',');
	    			$('.diskon-persen').val(diskon);
	    		};

		    	var ini = $(this).closest('tr');
		    	var data = {};
		    	data['column'] = name
		    	data['penjualan_id'] =  "<?=$penjualan_id;?>";
		    	data['value'] = value;
		    	var url = 'transaction/penjualan_data_update';
		    	// update_table(ini);
		    	ajax_data_sync(url,data).done(function(data_respond  /*,textStatus, jqXHR*/ ){
		    		// console.log(data_respond);
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
	    			bootbox.alert("Error! Total tidak boleh 0");
	    		}else {
	    			if (kembali >= 0 ) {
	    				window.location.replace(baseurl+'transaction/penjualan_list_close?id='+id+"&tanggal="+tanggal);
	    			}else{
	    				bootbox.alert('Kembali tidak boleh minus');
	    			}
	    		}
		    });
	    <?}?>

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
							window.location.reload();
							// update_table();
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
			// alert($(this).is(':checked'));
			if($(this).is(':checked')){
				let dp_nilai = reset_number_format(ini.find('.amount').html());
				ini.find('.amount-bayar').prop('readonly',false);
				ini.find('.amount-bayar').val(dp_nilai);
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
		});



//======================================qty add manage====================================

	    $(".btn-add-qty-row").click(function(){
	    	var baris = "<tr><td><input name='qty'></td>"+
							"<td><input name='jumlah_roll'></td>"+
							"<td></td></tr>";
	    	$('#qty-table').append(baris);
	    });
		
	    $("#qty-table").on('change','[name=qty]',function(){
	    	let ini = $(this);
			change_qty_update(ini,'#qty-table-stok');

			data_result = table_qty_update('#qty-table').split('=*=');
			// console.log(data_result);
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
	    	table_stok_update('#stok-info-add','#qty-table-stok');

	    });

		$("#qty-table").on('change','[name=jumlah_roll]',function(){
	    	let ini = $(this).closest('tr');
			
			change_roll_update($(this), '#qty-table-stok');

			data_result = table_qty_update('#qty-table').split('=*=');
			// console.log(data_result);
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
	    	table_stok_update('#stok-info-add','#qty-table-stok');

	    });

		

	    $('#qty-table-stok').on('click','tr', function(){
	    	var ini = $(this);
	    	
	    	change_click_stok(ini, '#qty-table');
	    	
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
	    	table_stok_update('#stok-info-add','#qty-table-stok');
	    	

	    });

	    $('#qty-table').on('input', 'input', function(){
	    	var qty = $(this).val();
	    	if ($(this).val() != '') {
	    		qty = qty.replace(',','.');
	    		$(this).val(qty);
		    	var class_qty = qty.replace('.','');
		    	$('#qty-table-stok tbody tr').hide();
		    	$('[class*=main]')
		    	$('#qty-table-stok tbody [class*='+class_qty+']').closest('tr').not('.habis').show();
	    	};

	    });

	    $('#qty-table').on('focusin','input', function(){
	    	$('#qty-table-stok tbody tr').hide();
	    	$('#qty-table-stok tbody tr').not('.habis').show();
	    });



//=====================================qty edit=========================================

	$('#general_table').on('click','.btn-edit', function () {
		let ini = $(this).closest('tr');
		let form = '#form_qty_edit';
		let table_qty = $("#qty-table-edit");
		let table_stok = $("#qty-table-stok-edit");
		let data_qty = ini.find('.data_qty').html().split('--');

		let harga_jual = ini.find('.harga_jual').html();
		
		table_qty.find('[name=qty]').each(function(){
			var itu = $(this).closest('tr');
			itu.find('[name=qty]').val('');
			itu.find('[name=jumlah_roll]').val('');
			itu.find('[name=penjualan_type_id]').val('');
			itu.find('.qty-get').html('');
			itu.find('roll-get').val('');
			

		});

		table_qty.closest('td').find('.nama_satuan').html(ini.find('.nama_satuan').html());
		table_qty.closest('td').find('.nama_packaging').html(ini.find('.nama_packaging').html());

		table_stok.closest('td').find('.nama_satuan').html(ini.find('.nama_satuan').html());
		table_stok.closest('td').find('.nama_packaging').html(ini.find('.nama_packaging').html());

		$(form+" [name=harga_jual]").val(change_number_format(harga_jual));
		
		$(form+' [name=penjualan_list_detail_id]').val(ini.find('.id').html());
		$(form+' [name=penjualan_id]').val("<?=$penjualan_id;?>");
		$(form+' [name=rekap_qty]').val(ini.find('.data_qty').html());

		$.each(data_qty,function(i,v){
			var urai = v.split('??');
    		var qty_get = parseFloat(urai[0]);
    		var roll_get = urai[1];
    		var penjualan_qty_detail_id = urai[2];
    		// console.log(urai);
    		
    		$('#qty-table-edit tbody tr').each(function(){
	    		var qty = $(this).find('[name=qty]').val();
	    		var jumlah_roll = $(this).find('[name=jumlah_roll]').val();
	    		if (jumlah_roll == '' && qty == '') {
	    			$(this).find('[name=qty]').val(qty_get);
	    			$(this).find('[name=jumlah_roll]').val(roll_get);
	    			$(this).find('.qty-get').html(qty_get);
	    			$(this).find('.roll-get').html(roll_get);
	    			$(this).find('.penjualan_qty_detail_id').val(penjualan_qty_detail_id);
	    			return false;
	    		};
	    	});
    	});

		let data = {};
        let gudang_id = ini.find('.gudang_id').html();
        let warna_id = ini.find('.warna_id').html();
        let barang_id = ini.find('.barang_id').html();

        data['gudang_id'] = gudang_id;
        data['barang_id'] = barang_id;
        data['warna_id'] = warna_id;
        
        var url = "transaction/get_qty_stock_by_barang_detail";
        ajax_data_sync(url,data).done(function(data_respond  /*,textStatus, jqXHR*/ ){
            var qty = 0;
            var jumlah_roll = 0;
            let table_stok = '';
            let idx = 1;
            let qty_row = 0;
            let qty_stok = 0;
            let status = '';
            let total_page = 1;
            let tombol_page = '';
            $("#qty-table-stok-edit tbody").html();
            $.each(JSON.parse(data_respond), function(k,v){
                // alert(v.qty);
                qty += parseFloat(v.qty);
                jumlah_roll += parseFloat(v.jumlah_roll);
                status = ((v.jumlah_roll <= 0) ? 'habis' : '');
                page_idx = parseInt(idx/10) +  parseInt((idx % 10 != 0 ) ? 1 : 0);
                var class_qty = parseFloat(v.qty);
                class_qty = class_qty.toString().replace('.','');
                table_stok += `<tr class='row-${idx} page-${page_idx} baris-table ${status} '>
                		<td class='idx-${class_qty}'>
                			<span class='qty-stok'>${parseFloat(v.qty)}</span>
                		</td>
                		<td><span class='roll-stok'>${parseFloat(v.jumlah_roll)}</span> </td>
                	</tr>`;
                qty_stok += parseFloat(v.qty*v.jumlah_roll);
                qty_row = idx;
                idx++;
            });
            $("#qty-table-stok-edit tbody").html(table_stok);

            $('#stok-info-edit').find('.stok-qty').html(qty_stok);
            $('#stok-info-edit').find('.stok-roll').html(jumlah_roll);
            $('#qty-table-edit input').val();
            $('#qty-table-stok-edit .habis').hide();

            data_result = table_qty_update('#qty-table-edit').split('=*=');
			// console.log(data_result);
	    	let total = parseFloat(data_result[0]);
	    	let total_roll = parseFloat(data_result[1]);
	    	let rekap = data_result[2];
	    	if (total > 0) {
			    $('.btn-brg-save').attr('disabled',false);
	    	}else{
	    		$('.btn-brg-save').attr('disabled',true);
	    	}

	    	total = total.toFixed(2);
	    	total = total.replace('.00','');
	    	$('.yard_total').html(total);
	    	$('.jumlah_roll_total').html(total_roll);
	    	$('#form_qty_edit [name=rekap_qty]').val(rekap);
            
        });
	});

	// $('#qty-table-edit').on('input', 'input', function(){
 //    	var qty = $(this).val();
 //    	if ($(this).val() != '') {
 //    		qty = qty.replace(',','.');
 //    		$(this).val(qty);
	//     	var class_qty = qty.replace('.','');
	//     	$('#qty-table-stok-edit tbody tr').hide();
	//     	$('[class*=main]')
	//     	$('#qty-table-stok-edit tbody [class*='+class_qty+']').closest('tr').not('.habis').show();
 //    	};

 //    });

    $("#qty-table-edit").on('change','[name=qty]',function(){
    	let ini = $(this);
		// subtotal_on_change(ini);
		change_qty_update(ini,'#qty-table-stok-edit');

		data_result = table_qty_update('#qty-table-edit').split('=*=');
		// console.log(data_result);
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
    	$('#form_qty_edit [name=rekap_qty]').val(rekap);
    	table_stok_update('#stok-info-edit');

    });

    $("#qty-table-edit").on('change','[name=jumlah_roll]',function(){
    	let ini = $(this).closest('tr');
		change_roll_update($(this), '#qty-table-stok-edit');

		data_result = table_qty_update('#qty-table-edit').split('=*=');
		// console.log(data_result);
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
    	$('#form_qty_edit [name=rekap_qty]').val(rekap);
    	table_stok_update('#stok-info-add','#qty-table-stok');

    });

    $('.btn-brg-edit-save').click(function(){
    	$('#form_qty_edit').submit();
    });

    $('#qty-table-stok-edit').on('click','tr', function(){
    	var ini = $(this);
    	
    	change_click_stok(ini, '#qty-table-edit');
    	
    	data_result = table_qty_update('#qty-table-edit').split('=*=');
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
    	$('#form_qty_edit [name=rekap_qty]').val(rekap);
    	table_stok_update('#stok-info-add','#qty-table-stok');
    	

    });

//========================================btn-detail============================

		$(".btn-detail-toggle").click(function(){
			$('#general-detail-table').toggle('slow');
		});

});
</script>

<script>


function subtotal_on_change(pointer){
	var ini = pointer.closest('tr');
	let subtotal = pointer.val();
	let qty = ini.find('[name=qty]').val();
	let jumlah_roll = ini.find('[name=jumlah_roll]').val();
	if (qty != '' || jumlah_roll != '') {
		// alert('test');
		if (qty != '') {
			jumlah_roll = subtotal / qty;
			jumlah_roll = jumlah_roll.toFixed(2);
			ini.find('[name=jumlah_roll]').val(jumlah_roll.toString().replace('.00',''));
		}else{
			qty = subtotal / jumlah_roll;
			ini.find('[name=qty]').val(qty.toFixed(3));
		}
	};
}

function dp_table_update(){
	let total_dp = 0;
	$('#dp_list_table .amount-bayar').each(function(){
		total_dp += parseFloat(reset_number_format($(this).val()));
	});

	$('.dp-total').html(change_number_format(total_dp));
}


function cek_last_input(gudang_id_last,barang_id, harga_jual){
	setTimeout(function(){
		// $('#barang_id_select').select2("open");
		$('#gudang_id_select').val(gudang_id_last);
		$('#barang_id_select').val(barang_id);
    	$('#barang_id_select, #gudang_id_select').change();

	},650);
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

function update_bayar(){
	var bayar = 0;
	var g_total = reset_number_format($('.g_total').html()) ;
	$('#bayar-data tr td input').each(function(){
		if ($(this).attr('class') != 'keterangan_bayar') {
			// alert(reset_number_format($(this).val()));
			bayar += parseFloat(reset_number_format($(this).val()));
		};
	});
	// alert(change_number_format(bayar));

	var kembali = bayar - g_total ;
	// alert(change_number_format(bayar));
	$('.total_bayar').html(change_number_format(bayar) );
	$('.kembali').html(change_number_format(kembali));

	if (kembali < 0) {
		$('.kembali').css('color','red');
	}else{
		$('.kembali').css('color','#333');
	}

}

function filter_stok(ini, text){
	// console.log(ini);
	var result = false;
	var jumlah_roll = 0;
	ini.filter(function(index){
		if ($(this).text() === text.toString()) {
			var pointer = $(this).closest('tr');
			jumlah_roll = pointer.find('.roll-stok').html();
			if (jumlah_roll > 0) {
				jumlah_roll -= 1;
				pointer.find('.roll-stok').html(jumlah_roll);
				if (jumlah_roll == 0) {
					pointer.addClass('habis');

				};
				result = true
			}else{result = true};
		};
	});
	return result;
}

function table_qty_update(table){
	var total = 0; 
	var idx = 0; 
	var rekap = [];
	var total_roll = 0;
	$(table+" [name=qty]").each(function(){
		var ini = $(this).closest('tr');
		var qty = $(this).val();
		var roll = ini.find('[name=jumlah_roll]').val();
		// alert(roll);
		var id = ini.find('.penjualan_qty_detail_id').val();
		if ($(this).val() != '' || id != '' ) {
			if (typeof id === 'undefined' || id == '' ) {
				id = '0';
			};

			var subtotal = parseFloat(qty*roll);
			// if (!isNaN(roll)) {
			// 	roll = 0;
			// 	console.log(total_roll+'+='+roll);
			// };
	    	total_roll += parseFloat(roll);
			
			if (qty != '' && roll != '' && id != '') {
				rekap[idx] = qty+'??'+roll+'??'+id;
			}else if(id != 0){
				rekap[idx] = 0+'??'+0+'??'+id;
			}
			idx++; 
			total += subtotal;
			ini.find('[name=subtotal]').val(qty*roll);
		};

	});

	rekap_str = rekap.join('--');
	// console.log(total+'=*='+total_roll+'=*='+rekap_str);

	return total+'=*='+total_roll+'=*='+rekap_str;
}

function table_stok_update(div_info, table_stok_id){
	var total= 0 ;
	var total_roll = 0;
	$(table_stok_id+" .qty-stok").each(function(){
		let ini = $(this).closest('tr');
		var qty = parseFloat($(this).html());
		console.log("qty : "+qty);
		var jumlah_roll = parseFloat(ini.find('.roll-stok').html());
		total += (qty*jumlah_roll);
		total_roll += jumlah_roll;
	});

	// alert(table_stok_id);
	$(div_info).find('.stok-qty').html(total);
	$(div_info).find('.stok-roll').html(total_roll);

}

function change_qty_update(pointer, table_stok_id){
	let ini = pointer.closest('tr');
	let qty = pointer.val();
	
	let jumlah_roll = ini.find('[jumlah_roll]').val();
	let penjualan_qty_detail_id = ini.find('.penjualan_qty_detail_id').val();
	if (jumlah_roll == '') {
		jumlah_roll = 1; 
		ini.find('[name=jumlah_roll]').val(1);
	}else if(qty == ''){jumlah_roll = ''};
	if (typeof penjualan_qty_detail_id === 'undefined' || penjualan_qty_detail_id == '' ) {ini.find('.penjualan_qty_detail_id').val(0);};

	var qty_before = ini.find('.qty-get').html();
	var roll_before = ini.find('.roll-get').html();

	if (qty_before != '' && roll_before != '' ) {
		$(table_stok_id+" .qty-stok").filter(function(){
			if ($(this).text() == qty_before) {
				var baris_before = $(this).closest('tr');
				var roll_stok = baris_before.find('.roll-stok').html();
				var roll_now = parseFloat(roll_before) + parseFloat(roll_stok);
				baris_before.find('.roll-stok').html(roll_now);
				baris_before.removeClass('habis');
		    	$(table_stok_id+' tbody tr').not('.habis').show();

			};
		});
	};
	
	var result = filter_stok($(table_stok_id+' .qty-stok'), qty);
	if (result) {
		ini.find('[name=jumlah_roll]').val(1);
		ini.find('.qty-get').html(qty);
		ini.find('.roll-get').html(1);

	}else{
		if (qty != '') {
			ini.find('[name=qty]').val(qty_before);
			ini.find('[name=jumlah_roll]').val(roll_before);
		}else{
			ini.find('[name=jumlah_roll]').val('');
			ini.find('.qty-get').html('');
			ini.find('.roll-get').html('');
		}
	}
}

function change_roll_update(pointer_ini, table_stok_id){
	var ini = pointer_ini.closest('tr');
	var roll_now = pointer_ini.val();
	var qty = ini.find('[name=qty]').val();
	var roll_before = ini.find('.roll-get').html();

	$(table_stok_id+" .qty-stok").filter(function(){
		if ($(this).text() == qty) {
			var pointer = $(this).closest('tr');
			roll_stok = pointer.find('.roll-stok').html();
			var roll_max = parseFloat(roll_before) + parseFloat(roll_stok);
			// console.log("=================");
			// console.log('stok:'+roll_stok);
			// console.log('max:'+roll_max);
			// console.log('now:'+roll_now);

			if (roll_now == '') {roll_now = 1; ini.find('[name=jumlah_roll]').val(roll_now) };
			if (roll_now > roll_max) {
				roll_now = roll_max;
				ini.find("[name=jumlah_roll]").val(roll_now);
				notific8("ruby","Sisa Stok "+roll_max+" Roll")
				var roll_sisa = 0;
			}else{
				var roll_sisa = parseFloat(roll_max) - parseFloat(roll_now);
			}
			if (roll_sisa == 0) {
				pointer.addClass('habis');
			}else{
				pointer.removeClass('habis');
			}
			pointer.find('.roll-stok').html(roll_sisa);
			ini.find('.roll-get').html(roll_now);
		};
	});
}

function change_click_stok(pointer, table_id, table_stok_id){
	var ini = pointer;
	var qty_get = ini.find('.qty-stok').html();
	var roll_get = ini.find('.roll-stok').html();
	var compare = false;

	$(table_id+" .qty-get").filter(function(){
		if ($(this).text() == qty_get) {
			var baris_get = $(this).closest('tr');
			var jumlah_roll = parseFloat(baris_get.find('[name=jumlah_roll]').val());
			jumlah_roll += parseFloat(roll_get);
			baris_get.find('.roll-get').html(jumlah_roll);
			baris_get.find('[name=jumlah_roll]').val(jumlah_roll);
			compare = true;
			return true;
		};
	});

	if (compare == false) {

		$(table_id+' tbody tr').each(function(){
    		var qty = $(this).find('[name=qty]').val();
    		var jumlah_roll = $(this).find('[name=jumlah_roll]').val();
    		if (jumlah_roll == '' && qty == '') {
    			$(this).find('[name=qty]').val(qty_get);
    			$(this).find('[name=jumlah_roll]').val(roll_get);
    			$(this).find('.qty-get').html(qty_get);
    			$(this).find('.roll-get').html(roll_get);
    			return false;
    		};
    	});
	};

	ini.find('.roll-stok').html(0);
	ini.addClass('habis');
	ini.hide();
}

function update_qty_edit(){
    var total = 0; var idx = 0; var rekap = [];
	var total_roll = 0;
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
    		// console.log(subtotal);
		};

		if (qty != '' && roll != '') {
			rekap[idx] = qty+'??'+roll;
		};
		idx++;  
		total += subtotal;

	});

	if (total > 0) {
		$('.btn-brg-edit-save').attr('disabled',false);
	}else{
		$('.btn-brg-edit-save').attr('disabled',true);
	}

	$('#portlet-config-qty-edit .jumlah_roll_total').html(total_roll);
	$('#portlet-config-qty-edit .yard_total').html(total.toFixed(2));

	$('#form-qty-update [name=rekap_qty]').val(rekap.join('--'));

}

function update_table(){
	subtotal = 0;
	$('.subtotal').each(function(){
		var sub = reset_number_format($(this).html());
		subtotal += parseFloat(sub);
		// alert(subtotal);
	});

	var diskon = reset_number_format($('.diskon').val());
	var ongkir = $('.ongkos_kirim').val();
	if (typeof ongkir === 'undefined') {ongkir = 0};
	// alert(ongkir);
	ongkir = reset_number_format(ongkir);
	var g_total = subtotal - parseInt(diskon) + parseInt(ongkir);
	// alert(subtotal+ '-' +parseInt(diskon) +'+'+ parseInt(ongkir));
	$('.g_total').html(change_number_format(g_total));
	$('.total').html(change_number_format(g_total));
	update_bayar();
}

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

	if ($printer_marker == 3) {
		include_once 'print/print_faktur_3.php';
		include_once 'print/print_detail_3.php';
		include_once 'print/print_faktur_detail_3.php';
		include_once 'print/print_surat_jalan_3.php';
		include_once 'print/print_surat_jalan_noharga_3.php';
	}else{
		include_once 'print_faktur_testing.php';
		include_once 'print_detail.php';
		include_once 'print_faktur_detail.php';

		include_once 'print_surat_jalan.php';
		include_once 'print_surat_jalan_noharga.php';
	}
}?>
