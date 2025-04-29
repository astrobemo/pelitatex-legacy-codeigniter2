<link href="<?=base_url('assets/global/plugins/bootstrap-modal/css/bootstrap-modal.css');?>" rel="stylesheet" type="text/css"/>

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

#qty-table-detail tr td, 
#qty-table-detail-edit tr td{
	border: 1px solid #ccc;
	padding: 3px;
	text-align: center;
	min-width: 50px;
	font-size: 16px;
}

#qty-table-detail, #qty-table-detail-edit{
	position: absolute;
	right: 50px;
	top: 120px;
}

#qty-table-detail .selected{
	background: lime;
}

</style>

<div class="page-content">
	<div class='container'>
		<?
			$status_aktif  ='';
			$pengeluaran_stok_lain_id = '';
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

			$keterangan = '';
			$no_faktur_lengkap = '';
			$no_surat_jalan = '';
			$fp_status = 1;

			$closed_date = '';

			$g_total = 0;
			$readonly = '';
			$disabled = '';
			$disabled_status = '';
			$bg_info = '';

			$hidden_spv = (is_posisi_id() != 1 ? 'hidden' : '');

			foreach ($pengeluaran_stok_lain_data as $row) {
				$pengeluaran_stok_lain_id = $row->id;
				$no_faktur = $row->no_faktur;
				$iClass = '';
				
				$tanggal = is_reverse_date($row->tanggal);
				$ori_tanggal = $row->tanggal;
				$status_cek = 0;
				$status = $row->status;
				$status_aktif = $row->status_aktif;
				$keterangan = $row->keterangan;
				$closed_date = $row->closed_date;

				if ($no_faktur != 0) {
					$no_faktur_lengkap = "RR".date("y",strtotime($row->tanggal)).'/'.date("m",strtotime($row->tanggal)).'-'.str_pad($no_faktur, 3,"0", STR_PAD_LEFT);
					# code...
				}
			}

			if ($status != 1) {
				if ( is_posisi_id() != 1 ) {
					$readonly = 'readonly';
				}
			}

			if ($pengeluaran_stok_lain_id == '') {
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
						<form action="<?=base_url('transaction/pengeluaran_stok_lain_list_insert')?>" class="form-horizontal" id="form_add_data" method="post">
							<h3 class='block'> Data Baru</h3>
							
			                <div class="form-group">
			                    <label class="control-label col-md-3">Tanggal<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
	                    			<input name='tanggal' class='form-control date-picker' value="<?=date('d/m/Y')?>" >
			                    </div>
			                </div> 
			                
			                <div class="form-group">
			                	<!-- po_section -->
			                    <label class="control-label col-md-3">Keterangan
			                    </label>
			                    <div class="col-md-6">
	                    			<textarea class='form-control' name='keterangan' placeholder="kekurangan kain"></textarea>
			                    </div>
			                </div> 
			                
						</form>
					</div>

					<div class="modal-footer">
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
						<form action="<?=base_url('transaction/pengeluaran_stok_lain_list_update')?>" class="form-horizontal" id="form_edit_data" method="post">
							<h3 class='block'> Data Edit</h3>
							
							<div class="form-group">
			                    <label class="control-label col-md-3">Tanggal<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
	                    			<input name='pengeluaran_stok_lain_id' value="<?=$pengeluaran_stok_lain_id?>" hidden>
	                    			<input name='tanggal' class='form-control date-picker' value="<?=$tanggal?>" >
			                    </div>
			                </div> 
			                
			                <div class="form-group">
			                	<!-- po_section -->
			                    <label class="control-label col-md-3">Keterangan
			                    </label>
			                    <div class="col-md-6">
	                    			<textarea class='form-control' name='keterangan'><?=$keterangan?></textarea>
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
						<form action="<?=base_url('transaction/pengeluaran_stok_lain_list_detail_insert')?>" class="form-horizontal" id="form_add_barang" method="post">
							<h3 class='block'> Tambah Barang</h3>
							
							<div class="form-group">
			                    <label class="control-label col-md-3">Gudang<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<input name='pengeluaran_stok_lain_id' value='<?=$pengeluaran_stok_lain_id;?>' hidden>
			                    	<input name='pengeluaran_stok_lain_detail_id' value='' hidden>
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
		                				<?foreach ($this->barang_list_aktif as $row) { 
		                					if ($row->status_aktif == 1) {?>
				                    			<option value="<?=$row->id?>"><?=$row->nama_jual;?></option>
		                					<?}?>
			                    		<? } ?>
			                    	</select>
			                    	<select name='data_barang' hidden>
			                    		<?foreach ($this->barang_list_aktif as $row) { ?>
			                    			<option value="<?=$row->id?>"><?=$row->nama_satuan;?>??<?=$row->harga_beli;?></option>
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

			                <div class="form-group" hidden>
			                    <label class="control-label col-md-3">Harga<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<div class='input-group'>
			                    		<input type="text" class='amount_number form-control' id='harga_jual_add' name="harga_jual"/>
			                			<span class="input-group-btn" >
											<a data-toggle="popover" class='btn btn-md default btn-cek-harga' data-trigger='click' title="History Pembelian Customer" data-html="true" data-content="<div id='data-harga'>loading...</div>"><i class='fa fa-search'></i></a>
										</span>
										
			                    	</div>
			                    </div>
			                </div> 
							<input name='rekap_qty' hidden>
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
										<?for ($i=0; $i < 9 ; $i++) {?> 
											<tr>
												<td><input name='qty'></td>
												<td><input name='jumlah_roll'></td>
												<td></td>
											</tr>
										<?}?>
									</table>
									<div class='yard-info'>
										TOTAL QTY: <span class='yard_total' >0</span> yard <br/>
										TOTAL ROLL: <span class='jumlah_roll_total' >0</span> 
									</div>
								</td>
								<td>
									<div id='stok-info' style='border:1px solid #ddd; padding:5px; margin-bottom:20px;'>
										STOK QTY : <span class='stok-qty'>0</span><br/>
										STOK ROLL : <span class='stok-roll'>0</span>
									</div>
									<div>
										<table id='qty-table-detail'>
											<tr>
												<td><i>waiting for detail...</i></td>
											</tr>
										</table>
									</div>
								</td>
							</tr>
						</table>


					</div>

					<div class="modal-footer">
						<!-- disabled -->
						<button  type="button" class="btn blue btn-active btn-trigger btn-brg-save">Save</button>
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
						<form action="<?=base_url('transaction/pengeluaran_stok_lain_list_detail_update')?>" class="form-horizontal" id="form_edit_barang" method="post">
							<h3 class='block'> Edit Barang</h3>
							
							<div class="form-group">
			                    <label class="control-label col-md-3">Gudang<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<input name='pengeluaran_stok_lain_id' value='<?=$pengeluaran_stok_lain_id;?>' <?=$hidden_spv;?> >
			                    	<input name='pengeluaran_stok_lain_detail_id' <?=$hidden_spv;?> >
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
			                    			<option value="<?=$row->id?>"><?=$row->nama_satuan;?>??<?=$row->harga_beli;?></option>
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

			                <div class="form-group" hidden>
			                    <label class="control-label col-md-3">Harga<span class="required">
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
												<th></th>
											</tr>
										</thead>
										<tbody>
		
										</tbody>
									</table>
									<div class='yard-info'>
										TOTAL : <span class='yard_total' >0</span> yard <br/>
										TOTAL ROLL : <span class='jumlah_roll_total' >0</span>
									</div> 
								</td>
								<td>
									<div id='stok-info-edit' style='border:1px solid #ddd; padding:5px; margin-bottom:20px;'>
										STOK QTY : <span class='stok-qty'>0</span><br/>
										STOK ROLL : <span class='stok-roll'>0</span>
									</div>
									<div>
										<table id='qty-table-detail-edit'>
											<tr>
												<td><i>waiting for detail...</i></td>
											</tr>
										</table>
									</div>
								</td>
							</tr>
						</table>
						</div>
						<span class='total_roll' hidden></span>
						<!-- <form hidden action="<?=base_url()?>transaction/pengeluaran_stok_lain_qty_update" id='form-qty-update' method="post">
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
						<form action="<?=base_url('transaction/pengeluaran_stok_lain_request_open');?>" class="form-horizontal" id="form-request-open" method="post">
							<h3 class='block'> Request Open</h3>
							
							<div class="form-group">
			                    <label class="control-label col-md-3">PIN<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<input name='pengeluaran_stok_lain_id' value='<?=$pengeluaran_stok_lain_id;?>' hidden>
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

		<div class="row margin-top-10">
			<div class="col-md-12">
				<div class="portlet light">
					<div class="portlet-title">
						<div class="caption caption-md">
							<i class="icon-bar-chart theme-font hide"></i>
							<span class="caption-subject theme-font bold uppercase"><?=$breadcrumb_small;?></span>
						</div>
						<div class="actions hidden-print">
							<?//if (is_posisi_id() != 5) { ?>
								<a href="<?=base_url().is_setting_link('transaction/pengeluaran_stok_lain_list_detail');?>" target='_blank' class="btn btn-default btn-sm">
								<i class="fa fa-files-o"></i> Tab Kosong Baru </a>
								<a href="#portlet-config" data-toggle='modal' class="btn btn-default btn-sm btn-form-add">
								<i class="fa fa-plus"></i> Data Baru </a>
							<?//}?>
							<a style='display:none' href="#portlet-config-faktur" data-toggle='modal' class="btn btn-default btn-sm btn-form-add">
							<i class="fa fa-search"></i> Cari Faktur </a>
						</div>
					</div>
					<div class="portlet-body">
						<table style='width:100%'>
							<tr>
								<td>
									<table>
											<?if ($pengeluaran_stok_lain_id != '' && $status_aktif != -1) { ?>
												<tr>
													<td colspan='3'>
														<?if ($status == 0) { ?>
															<button href="#portlet-config-pin" data-toggle='modal' class='btn btn-xs btn-pin'><i class='fa fa-key'></i> request open</button>
														<?}elseif ($status != -1) { ?>
															<button href="#portlet-config-edit" data-toggle='modal' class='btn btn-xs '><i class='fa fa-edit'></i> edit</button>
															<?if (is_posisi_id() != 5 ) { ?>
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
								    			}elseif ($pengeluaran_stok_lain_id !='') {
								    				$iClass = 'fa-exclamation-circle';
								    			}?>
								    		</td>
								    	</tr>
										<tr>
								    		<td>Tanggal</td>
								    		<td class='padding-rl-5'> : </td>
								    		<td class='td-isi-bold'><?=is_reverse_date($tanggal);?></td>
								    	</tr>
										<tr>
								    		<td>Keterangan</td>
								    		<td class='padding-rl-5'> : </td>
								    		<td class='td-isi-bold'><?=$keterangan;?></td>
								    	</tr>
								    	
								    </table>
								</td>
								<td class='text-right' style='<?=$bg_info;?>; padding:10px;'>
									<div class='<?//=$note_info;?>' >
										<i class='fa <?=$iClass;?>' style='font-size:2em'><?=($status_aktif == '-1' ? 'BATAL' : '');?></i>
										<span class='no-faktur-lengkap'> <?=$no_faktur_lengkap;?></span><br/>
										<small><?=($status == 0 && $no_faktur_lengkap != '' && $status_aktif != -1 ? date('d/m/Y H:i:s', strtotime($closed_date)) : '' )?></small>
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
									<th scope="col" hidden>
										Jenis
									</th>
									<th scope="col">
										Nama Barang
										<?if ($pengeluaran_stok_lain_id !='' && $status == 1 && $status_aktif != -1 ) {?>
											<a href="#portlet-config-detail" data-toggle='modal' class="btn btn-xs blue btn-brg-add">
											<i class="fa fa-plus"></i> </a>
										<?}
										//else{
											// echo $pengeluaran_stok_lain_id .'&&'. $status .'&&'. is_posisi_id() .'&&'. $status_aktif; 
										//}?>
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
									<!-- <th scope="col">
										Harga
									</th> -->
									<!-- <th scope="col">
										Total Harga
									</th> -->
									<th scope="col" class='hidden-print'>
										Action
									</th>
								</tr>
							</thead>
							<tbody>
								<?
								$total_nppn = 0;
								$idx =1; $barang_id = ''; $gudang_id_last = ''; $harga_jual = 0; $qty_total = 0; $roll_total = 0;
								foreach ($pengeluaran_stok_lain_detail as $row) { ?>
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
										<!-- <td>
											<span class='harga_jual'><?=number_format($row->harga_jual,'0','.','.');?></span>

										</td> -->
										<!-- <td>
											<?$subtotal = $row->qty * $row->harga_jual;
											$g_total += $subtotal;
											$harga_jual = $row->harga_jual;
											$qty_total += $row->qty;
											$roll_total += $row->jumlah_roll;
											?>
											<span class='subtotal'><?=number_format($subtotal,'0','.','.');?></span> 
										</td> -->
										<td class='hidden-print'>
											<?$gudang_id_last=$row->gudang_id;?>
											
											<?if ($status == 1 || is_posisi_id() == 1 ) { ?>
												<?if (is_posisi_id() != 5 && $status_aktif != -1) { ?>
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
									<!-- <td class='text-right'><b></b></td> -->
									<!-- <td><b class='total'><?=number_format($g_total,'0',',','.');?> </b> </td> -->
									

									<td class='hidden-print'></td>
								</tr>

								
							</tbody>
						</table>
					    </div>
					    <hr/>
							<div hidden>
								<span style='font-size:1.5em; font-weight:bold'>Posisi Barang : </span>
								<label>
			                    <input type='checkbox' name='posisi_barang' class='form-control' id='posisi-barang' value='1'>Dititip</label>
								<table id='general-detail-posisi' class='table table-bordered'>
								<thead>
									<tr>
										<th>Barang</th>
										<?for ($i=1; $i <= 5 ; $i++) {?> 
											<th class='text-center' style='width:12%;'><?=$i;?></th>
										<?}?>
										<th rowspan='2'>Total</th>
									</tr>
									<tr>
										<th>Tanggal</th>
										<?for ($i=1; $i <= 5 ; $i++) {?> 
											<th class='text-center'>
												<input style='width:70px' name='tanggal' id='tanggal-ambil' class='date-picker text-center' placeholder="tanggal" autocomplete='off'><br/>
											</th>
										<?}?>
									</tr>
								</thead>
								<?foreach ($pengeluaran_stok_lain_detail as $row) {
									$data_qty = explode('--', $row->data_qty);?>
									<tr>
										<td><?=$row->nama_barang?> <?=$row->nama_warna?></td>
										<td class='text-center'>
											<?foreach ($data_qty as $key => $value) {
												$qty = explode('??', $value);?>
													<span style='display:inline-block;width:25px;'><?=(float)$qty[0]?></span>  x <input style='width:25px; text-align:center; border:none; border-bottom:1px solid #ddd' value='<?=$qty[1]?>' ><br/>
											<?}?>		
										</td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td><?=str_replace('.00', '',$row->qty)?></td>
									</tr>
								<?}?>
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
								<?foreach ($pengeluaran_stok_lain_detail as $row) {?>
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
											if (isset($pembayaran_pengeluaran_stok_lain[$row->id])) {
												$bayar = $pembayaran_pengeluaran_stok_lain[$row->id];
												$keterangan = $pembayaran_keterangan[$row->id];
												$bayar_total += $bayar;
												
											}

											$stat = ''; $style = '';
											if (is_posisi_id() == 5) {
												$stat = 'readonly';
												$style = 'background:#ddd; border:1px solid #ddd';
											}

											if ($row->id == 1 || $status != 1) {
												if ( $status != 1) {
													if (is_posisi_id() != 1) {
														$stat = 'readonly';
														$style = 'background:#ddd; border:1px solid #ddd';
													}
												}
											}
											?>
											<?/*if ($row->id != 1 && $row->id != 5 && $row->id != 6) { ?>
												<tr>
													<td><?=$row->nama;?></td>
													<td><input <?=$stat;?> style='<?=$style;?>' class='amount_number bayar-input' id='bayar_<?=$row->id;?>' value="<?=number_format($bayar,'0',',','.');?>"></td>
												</tr>
											<?}*/?>

										<?}?>
									</table>
								</td>
								<td style='vertical-align:top;font-size:2.5em; display:none' class='text-right'>
									<table style='float:right;'>
										<tr style='border:2px solid #c9ddfc' hidden>
											<td class='padding-rl-25' style='background:#c9ddfc'>BAYAR</td>
											<td class='padding-rl-10'>
												<b>Rp <span class='total_bayar' style=''><?=number_format($bayar_total,'0',',','.');?></span></b>
											</td>
										</tr>
										<tr style='border:2px solid #ffd7b5'>
											<td class='padding-rl-25' style='background:#ffd7b5'>TOTAL</td>
											<td class='text-right padding-rl-10'> 
												<b>Rp <span class='g_total' style=''><?=number_format($g_total,'0',',','.');?></span></b>
											</td>
										</tr>
										<tr style='border:2px solid #ceffb4' hidden>
											<td class='padding-rl-25' style='background:#ceffb4'>KEMBALI</td>
											<td class='padding-rl-10'>
												<?
												$kembali_style = '';
												$kembali = $bayar_total - ($g_total);
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
						<?//print_r($data_pengeluaran_stok_lain_detail_group);?>
						<div>
							<?if ($status_aktif != -1) {?>
								<button type='button'<?if ($idx == 1) { echo 'disabled'; }?> <?=$disabled;?> <?if ($status != 1) {?> disabled <?}?> class='btn btn-lg red hidden-print btn-close'><i class='fa fa-lock'></i> LOCK </button>
				                <a style='display:none' <?if($disabled_status == ''){ ?>href="<?=base_url();?>transaction/pengeluaran_stok_lain_print?pengeluaran_stok_lain_id=<?=$pengeluaran_stok_lain_id;?>"<? }else{ echo $disabled_status; } ?> target='_blank' class='btn btn-lg blue btn-print hidden-print'><i class='fa fa-print'></i> Faktur </a>
				                <a style='display:none' <?if($disabled_status == ''){ ?>href="<?=base_url();?>transaction/pengeluaran_stok_lain_detail_print?pengeluaran_stok_lain_id=<?=$pengeluaran_stok_lain_id;?>"<? }else{ echo $disabled_status; } ?> target='_blank' class='btn btn-lg blue btn-print hidden-print'><i class='fa fa-print'></i> Detail </a>
				                <a style='display:none' <?if($disabled_status == ''){ ?> href="<?=base_url();?>transaction/pengeluaran_stok_lain_print_kombinasi?pengeluaran_stok_lain_id=<?=$pengeluaran_stok_lain_id;?>"<? }else{ echo $disabled_status; } ?> target='_blank' class='btn btn-lg blue btn-print hidden-print'><i class='fa fa-print'></i> Faktur + Detail </a>
				                
				                <a style='display:none' <?if($disabled_status == ''){ ?>href="<?=base_url();?>transaction/pengeluaran_stok_lain_sj_print?pengeluaran_stok_lain_id=<?=$pengeluaran_stok_lain_id;?>&harga=yes"<? }else{ echo $disabled_status; } ?> target='_blank' class='btn btn-lg green btn-print hidden-print'><i class='fa fa-print'></i> Surat Jalan </a>
				                <a style='display:none' <?if($disabled_status == ''){ ?>href="<?=base_url();?>transaction/pengeluaran_stok_lain_sj_print?pengeluaran_stok_lain_id=<?=$pengeluaran_stok_lain_id;?>&harga=no"<? }else{ echo $disabled_status; } ?> target='_blank' class='btn btn-lg green btn-print hidden-print'><i class='fa fa-print'></i> Surat Jalan No Harga </a>
	                            <!-- <button type="button" class="btn btn-success" onclick="startConnection();">Connect</button> -->
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

<script src="<?php echo base_url('assets_noondev/js/webprint.js'); ?>" type="text/javascript"></script>

<script src="<?php echo base_url('assets/global/plugins/select2/select2.min.js'); ?>" type="text/javascript" ></script>
<script src="<?php echo base_url('assets_noondev/js/form-pengeluaran-stok-lain.js'); ?>" type="text/javascript"></script>


<script>
jQuery(document).ready(function() {

	FormNewPengeluaranDetail.init();
	//===========================general=================================

		var form_group = {};
		var idx_gen = 0;
		var print_idx = 1;


		$('[data-toggle="popover"]').popover();


	    $('#warna_id_select, #barang_id_select,#warna_id_select_edit, #barang_id_select_edit').select2({
	        placeholder: "Pilih...",
	        allowClear: true
	    });
//========================================================================================

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
	    	var id = $("#search_no_faktur").val();
	    	if (id != '' && typeof id !== 'undefined') {
	    		$('#form_search_faktur').submit();
	    	}else{
	    		alert(id);
	    	}
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
			$('#form_add_barang [name=harga_jual]').val(change_number_format(data[1]));
	   		
			$('#form_add_barang [name=satuan]').val(data[0]);
			$('#warna_id_select').select2('open');
	    });

	    $('#warna_id_select').change(function(){
	    	$('#form_add_barang [name=harga_jual]').focus();
	    });


	//====================================modal barang=============================
	
		<?if ($status == 1 && is_posisi_id() != 5) {?>
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
	    		// $('.btn-brg-save').attr('disabled',true);
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

        	form.find("[name=pengeluaran_stok_lain_detail_id]").val(ini.find(".id").html());
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
			// data['pengeluaran_stok_lain_detail_id'] = id;
			// data['rekap_qty'] = $('#form-qty-update [name=rekap_qty]').val();
			// var url = 'transaction/pengeluaran_stok_lain_qty_detail_update';
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
	    	if ($('#form_add_data [name=tanggal]').val() != '') {
    			$('#form_add_data').submit();
    			btn_disabled_load($(this));
	    	}else{
	    		alert("Mohon isi tanggal !");
	    	};
	    });

	    var idx_submit = 1;

	    $('.btn-edit-save').click(function(){
	    	if ($('#form_edit_data [name=tanggal]').val() != '') {
    			btn_disabled_load($(this));
    			$('#form_edit_data').submit();
	    	}else{
	    		alert("Mohon isi tanggal !");
	    	};
	    });

	//====================================bayar==========================================
		<?if ($pengeluaran_stok_lain_id != '') {?>

			$('.bayar-input').dblclick(function(){
				var id_data = $(this).attr('id').split('_');
				var ini = $(this);

				if ($(this).val() == 0 || $(this).val() == '' ) {
					var g_total = reset_number_format($('.g_total').html());
					var total_bayar = reset_number_format($('.total_bayar').html());
					var sisa = parseInt(g_total) - parseInt(total_bayar);

					if (sisa > 0) {
						$(this).val(change_number_format(sisa));
						var data = {};
						data['pembayaran_type_id'] = id_data[1];
						data['pengeluaran_stok_lain_id'] = '<?=$pengeluaran_stok_lain_id?>';
						data['amount'] = ini.val();
						var url = 'transaction/pembayaran_pengeluaran_stok_lain_update';
						update_db_bayar(url, data);
					};
					
				};
			});

			var bayar = true;
			$('#bayar-data tr td').on('change','input', function(){
				var id_data = $(this).attr('id').split('_');
				if (bayar) {
					var data = {};
					data['pembayaran_type_id'] = id_data[1];
					data['pengeluaran_stok_lain_id'] = '<?=$pengeluaran_stok_lain_id?>';
					data['amount'] = $(this).val();
					var url = 'transaction/pembayaran_pengeluaran_stok_lain_update';
					update_db_bayar(url, data);
				};
			});			

		<?};?>

		$(document).on('change', '.keterangan_bayar',function(){
			var data = {};
	    	data['pengeluaran_stok_lain_id'] =  "<?=$pengeluaran_stok_lain_id;?>";
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

	    <?if ($pengeluaran_stok_lain_id != '') {?>
	    	$('.btn-close').click(function(){
	    		var kembali = reset_number_format($('.kembali').html());
	    		var g_total = reset_number_format($('.g_total').html());
	    		var tanggal = "<?=$ori_tanggal;?>";
	    		var id = "<?=$pengeluaran_stok_lain_id;?>";
	    		// if (g_total <= 0) {
	    		// 	// bootbox.alert("Error! Total tidak boleh 0");
    			// 	bootbox.confirm("Total nota 0, Yakin untuk melanjutkan", function(respond){
    			// 		if (respond) {
		    	// 			window.location.replace(baseurl+'transaction/pengeluaran_stok_lain_list_close?id='+id+"&tanggal="+tanggal);
    			// 		};
    			// 	});
	    		// 	// $("#portlet-config-sample").modal('toggle');
	    		// 	// setTimeout(function(){
	    		// 	// 	$("#form-sample").find('.pin_user').focus();
	    		// 	// },700);
	    		// }else{
			    	window.location.replace(baseurl+'transaction/pengeluaran_stok_lain_list_close?id='+id+"&tanggal="+tanggal);
	    		// }
		    });
	    <?}?>

	    $(".btn-sample-ok").click(function() {
	    	var id = "<?=$pengeluaran_stok_lain_id?>";
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
					var url = 'transaction/pengeluaran_stok_lain_list_detail_remove';
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

	//========================================btn-detail============================

		$(".btn-detail-toggle").click(function(){
			$('#general-detail-table').toggle('slow');
		});

});
</script>

<?
?>

<script>


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

	let nama_form = form.attr('id');
	var pengeluaran_stok_lain_detail_id = form.find(' [name=pengeluaran_stok_lain_detail_id]').val();
	pengeluaran_stok_lain_detail_id = (typeof pengeluaran_stok_lain_detail_id === 'undefined' ? '' : pengeluaran_stok_lain_detail_id);

	if (pengeluaran_stok_lain_detail_id == '') {
		$('#qty-table-detail-edit').html(`<tr><td><p style='color:#ddd'><i>loading.....</i></p></td></tr>`);
	}else{
		$('#qty-table-detail').html(`<tr><td><p style='color:#ddd'><i>loading.....</i></p></td></tr>`);
	}
	var data = {};
	data['gudang_id'] = form.find(' [name=gudang_id]').val();
	data['barang_id'] = form.find(' [name=barang_id]').val();
	data['warna_id'] = form.find(' [name=warna_id]').val();
	data['pengeluaran_stok_lain_detail_id'] = pengeluaran_stok_lain_detail_id;
	<?if (is_posisi_id()==1) {?>
		// alert(data['barang_id']+'=='+data['warna_id']);
	<?};?>
	// alert(form.find(' [name=pengeluaran_stok_lain_detail_id]').val());
	data['tanggal'] = form.find(' [name=tanggal]').val();
	// $('#qty-table-detail').empty();
	// $('#qty-table-detail-edit').empty();
	var url = "transaction/get_qty_stock_by_barang";
	// alert(data['gudang_id']);
	$('#overlay-div').show();
	let tblD = '';
	ajax_data_sync(url,data).done(function(data_respond  ,textStatus, jqXHR ){
		// alert(data_respond);
		$('#overlay-div').hide();
		// console.log(JSON.parse(data_respond));
		qtyTblDetail = [];
		$.each(JSON.parse(data_respond), function(k,v){
			if (k==0) {
			qtyListDetail = [];
				$('#qty-table-detail').empty();
				$('#qty-table-detail-edit').empty();
				if (v[0].qty == 0) {
					tblD = "<tr><td> Tidak ada stok </td></tr>";
			
				};
				// console.log(v);
				stok_div.find('.stok-qty').html(parseFloat(v[0].qty));
				stok_div.find('.stok-roll').html(v[0].jumlah_roll);
			}else if(k==1){
				for (var i = 0; i < v.length; i++) {
								
					let sisa_roll = parseFloat(v[i].roll_stok_masuk) + parseFloat(v[i].jumlah_roll_masuk) - v[i].roll_stok_keluar - v[i].jumlah_roll_keluar;
					if (v[i].qty == '100' || parseFloat(v[i].qty) == 100) {
						// console.log('detail',parseFloat(v[i].roll_stok_masuk) +'+'+ parseFloat(v[i].jumlah_roll_masuk) +'-'+ v[i].roll_stok_keluar +'-'+ v[i].jumlah_roll_keluar);
						// console.log(v[i].qty+':', sisa_roll);
					}
					// console.log(v[i].qty+':', sisa_roll);
					if (sisa_roll > 0) {
						qtyListDetail[`s-${parseFloat(v[i].qty).toString().replace('.','_')}`] = sisa_roll;
						for (var j = 0; j < sisa_roll; j++) {
							if (v[i].qty != '100' && v[i].qty != 100) {
								qtyTblDetail.push(parseFloat(v[i].qty));
							};
						};
					}else if(v[i].qty == 100){
						qtyListDetail[`s-100`] = 0;
					};
				};

				// console.log('si-100', qtyListDetail[`s-100`]);
				let brs = Math.ceil((qtyTblDetail.length/5) );
				// console.log('brs', brs);
				// console.log('s-100', qtyListDetail[`s-100`]);
				if (qtyListDetail[`s-100`] > 0) {
					tblD += `<tr><td colspan='5' style='padding:5px; text-align:center' class='s-100'> 100 x ${qtyListDetail['s-100']}</td></tr>`
				};
				for (var i = 0; i < brs; i++) {
					tblD += '<tr>';
					for (var j = 0; j < 5; j++) {
						if (typeof qtyTblDetail[(i*5) + j] !== 'undefined') {
							tblD += `<td class='s-${qtyTblDetail[(i*5) + j].toString().replace('.','_')}'>${qtyTblDetail[(i*5) + j]}</td>`
							// console.log('l',tblD);
							
						};
					};
					tblD += '<tr>';
				};
			};

			<?if (is_posisi_id() == 1) {?>
				// console.log(k);
				if (k == 5) {
					let qData = v;
					let ins_qty = [];
					// pengeluaran_stok_lain_detail_id
					// console.log(qData);

					for (let m = 0; m < qData.length; m++) {
						if (qData[m].jumlah_roll > 0) {
							ins_qty.push({
								'pengeluaran_stok_lain_detail_id' : pengeluaran_stok_lain_detail_id,
								'qty' :  qData[m].qty,
								'jumlah_roll' : qData[m].jumlah_roll
							});
						}	
					}

					// console.log(ins_qty);
				}
			<?}?>
		});

		qtyListAmbil = [];
		if (pengeluaran_stok_lain_detail_id == '') {
			console.log(tblD);
			$('#qty-table-detail').append(tblD);
			cekStok('', 'qty-table-edit');
		}else{
			// console.log('tb;D', tblD);
			$('#qty-table-detail-edit').append(tblD);
			$('#qty-table input').val('');
		};
		
		// alert(data_respond);
		// console.log(data_respond);
	});
}

function get_qty_legacy(form, stok_div){
    var data = {};
    data['gudang_id'] = form.find(' [name=gudang_id]').val();
    data['barang_id'] = form.find(' [name=barang_id]').val();
    data['warna_id'] = form.find(' [name=warna_id]').val();
    data['pengeluaran_stok_lain_detail_id'] = form.find(' [name=pengeluaran_stok_lain_detail_id]').val();
    // alert(form.find(' [name=pengeluaran_stok_lain_detail_id]').val());
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

	console.log(total.toFixed(2)+'<='+parseFloat(total_stok));
	if (total > 0 &&  parseFloat(total.toFixed(2)) <= parseFloat(total_stok) && total_roll <= jumlah_roll_stok) {
		$('.btn-brg-edit-save').attr('disabled',false);
	}else{
		// $('.btn-brg-edit-save').attr('disabled',true);
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

function cekStok(ini, table){
	qtyListAmbil = [];
	let stat = true;
	$(`#${table} [name=qty]`).each(function(){
		let qty = $(this).val();
		let ini = $(this);
		if (qty != '') {
			if (qtyListDetail[`s-${parseFloat(qty).toString().replace('.','_')}`] > 0) {
				if (typeof qtyListAmbil[`s-${parseFloat(qty).toString().replace('.','_')}`] === 'undefined') {
					qtyListAmbil[`s-${parseFloat(qty).toString().replace('.','_')}`] = 0;
				};

				let jmlRoll= $(this).closest('tr').find('[name=jumlah_roll]').val();
				// console.log(qty, jmlRoll);
				if (jmlRoll == '' || typeof jmlRoll ==='undefined' || jmlRoll.length == 0 ) {
					jmlRoll = 1;
					$(this).closest('tr').find('[name=jumlah_roll]').val(jmlRoll);
				};
				qtyListAmbil[`s-${parseFloat(qty).toString().replace('.','_')}`]+= parseInt(jmlRoll);
				// console.log(`uus-${parseFloat(qty).toString().replace('.','_')}`,qtyListAmbil[`s-${parseFloat(qty).toString().replace('.','_')}`] +'<='+ qtyListDetail[`s-${parseFloat(qty).toString().replace('.','_')}`] );
				
				if (qtyListAmbil[`s-${parseFloat(qty).toString().replace('.','_')}`] <= qtyListDetail[`s-${parseFloat(qty).toString().replace('.','_')}`] ) {
					if (ini != '') {
		   				ini.css('color', 'green');
					}else{
						$(this).css('color','green');
					};
				}else{
					
					stat = false;
					alert('Jumlah roll tidak cukup');
	   				ini.val('');
	   				ini.css('color', 'red');
	   				ini.focus();
	   				return false;
				}
				
			};
		};
	});
	

	$('#qty-table-detail tr td').css('background','transparent');
	console.log('qtA',qtyListAmbil);
	for (var key in qtyListAmbil) {
		let q = qtyListAmbil[key];
		if (q > 0) {
			let idx = 0;
			if (key != 's-100') {
				$(`.${key}`).each(function(){
					// console.log('qB',q);
					// console.log(q+'>'+idx);
					if (q > idx) {
						// console.log('ya')
						$(this).css('background','yellow');
					}else{
						return false;
					};
					idx++;
				})	
			}else{
				$('.s-100').css('border','2px solid yellow');
			};
		};
	}


	if (stat) {
		return true;
	};
}
</script>
