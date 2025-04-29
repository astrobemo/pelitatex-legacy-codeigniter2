<link href="<?=base_url('assets/global/plugins/bootstrap-modal/css/bootstrap-modal.css');?>" rel="stylesheet" type="text/css"/>
<?=link_tag('assets/global/plugins/bootstrap-datepicker/css/datepicker3.css'); ?>

<style type="text/css">

.btn-new-batch:hover{
	background: green;
	color: white;
	border:2px solid green;
}

.report-side{
	display: none;
}

.rinci-table{
	/*width: 100%;*/
}

.rinci-table tr td{
	padding: 0 10px 5px 5px;
	text-align: right;
	font-size: 0.9em;
}

.bg-selected{
	background: #FFB6C1 !important;
}

@media print {
	* {
		-webkit-print-color-adjust: exact;
		print-color-adjust: exact;
	}

	.blue{
		color: blue !important;
	}

	#general_table tr{
		height:10px !important;
		line-height:10px !important;
	}

	#general_table .row{
		height:10px !important;
		line-height:10px !important;	
	}

	#general_table .kosong{
		height: 5px;
	}

	.bg-selected{
		background: transparent !important;
	}

	a[href]:after {
	    content: none !important;
	}

}

</style>

<div class="page-content">
	<div class='container'>

		<?
			$po_pembelian_id = '';
			$supplier_id = '';
			$nama_supplier = '';
			$po_number = '';
			$po_number_raw = '';
			$sales_contract = '';
			$tanggal = '';
			$ori_tanggal = '';
			$toko_id = '';
			$nama_toko = '';
			
			$po_status = 0;
			$status_aktif = 0;
			$catatan = '';
			$batch_status = 0;

			$batch = '';
			$nama_satuan = '';
			// $batch_id = '';

			$tanggal = date('d/m/Y');

			foreach ($po_pembelian_data as $row) {
				$po_pembelian_id = $row->id;
				$supplier_id = $row->supplier_id;
				$nama_supplier = $row->nama_supplier;
				$po_number = $row->po_number;
				$po_number_raw = $row->po_number_raw;
				$sales_contract = $row->sales_contract;
				$nama_toko = $row->nama_toko;
				$alamat = $row->alamat;
				$up_person = $row->up_person;
				$kota = $row->kota;
				
				$status_aktif = $row->status_aktif;
				$catatan = $row->catatan;
			}

			$revisi_count = 0;
			$po_ori_id = '';

			foreach ($po_pembelian_data_batch as $row) {
				if ($row->id == $batch_id) {
					$tanggal = is_reverse_date($row->tanggal);
					$batch = $row->batch;
					$batch_id = $row->id;
					$batch_status = $row->status;
					$revisi_count = $row->revisi;
					$po_ori_id = $row->revisi_ori_id;
					$keterangan_batch = $row->keterangan_batch;
					# code...
				}
			}


			$readonly = ''; $disabled = '';
			if (is_posisi_id() == 6) {
				$readonly = 'readonly';
				$disabled = 'disabled';
			}
		?>

		<div class="modal fade" id="portlet-config-info" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body">
						<div>
							<button id="rename-info-btn" class="btn btn-sm default btn-info">RENAME</button>
							<button id="split-info-btn" class="btn btn-sm default btn-info">SPLIT</button>
							<button id="splitrename-info-btn" class="btn btn-sm default btn-info">SPLIT-RENAME</button>
						</div>
						<div id="rename-info" class='info-content' hidden>
							<h2>RENAME</h2>
							<img src="<?=base_url();?>/image/po_info/split_info.jpg">
							<p>
								- Untuk merubah nama barang, jika <b>nama barang di <span style="color:red">PO Master</span> &#8800; &#x2260; &ne; nama barang yang ingin dipesan</b><br/>
							</p>
						</div>
						<div id="split-info" class='info-content' hidden>
							<h2>SPLIT</h2>
							<p>
								- Untuk membagi(split) <b>Kuota <span style="color:red">PO Master</span></b> satu jenis barang ke barang lain nya. <br/>
								- Barang lain ini tidak harus terdaftar di PO Master
							</p>
						</div>
						<div id="splitrename-info" class='info-content' hidden>
							<h2>SPLIT-RENAME</h2>
							<p>
								- Untuk membagi(split) <b>Kuota <span style="color:red">PO Master</span></b> satu jenis barang ke barang lain nya. <br/>
								- Namun <b>nama barang yang <span style="color:red">di split</span> &#8800; &#x2260; &ne; nama barang pabrik</b>
							</p>
						</div>
					</div>

					<div class="modal-footer">
						<button type="button" class="btn default  btn-active" data-dismiss="modal">Close</button>
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
						<form action="<?=base_url('transaction/po_batch_close');?>" class="form-horizontal" id="form-request-open" method="get">
							<h3 class='block'> Request Open</h3>
							
							<div class="form-group">
			                    <label class="control-label col-md-3">PIN<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<input name='po_pembelian_id' value='<?=$po_pembelian_id;?>' hidden>
			                    	<input name='batch_id' value='<?=$batch_id;?>' hidden>
			                    	<input name='status' value='1' hidden>
									<input name='pin' type='password' class="pin_user form-control">
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

		<div class="modal fade" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body">
						<form action="<?=base_url('transaction/po_pembelian_batch_insert')?>" class="form-horizontal" id="form_add_data" method="post">
							<h3 class='block'>+ <b>PO BATCH</b> Baru </h3>

							<div class="form-group">
			                    <label class="control-label col-md-3">PO MASTER<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
					                <input type="text" disabled class="form-control" value="<?=$po_number?>"/>
			                    </div>
			                </div> 	

							<div class="form-group">
			                    <label class="control-label col-md-3">Supplier<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<select disabled class='form-control' style='font-weight:bold' name="supplier_id">
			                    		<?foreach ($this->supplier_list_aktif as $row) { ?>
			                    			<option <?=($row->id==$supplier_id ? 'selected' : '');?> value="<?=$row->id?>"><?=$row->nama;?></option>
			                    		<? } ?>
			                    	</select>
					                <input hidden value="<?=$po_pembelian_id;?>" name="po_pembelian_id"/>
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
			                    <label class="control-label col-md-3">Catatan Batch<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
					                <input type="text" class="form-control" value="" name="keterangan_batch"/>
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
						<form action="<?=base_url('transaction/po_pembelian_batch_update')?>" class="form-horizontal" id="form_edit_data" method="post">
							<h3 class='block'><b>PO BATCH</b> Edit </h3>

							<div class="form-group">
			                    <label class="control-label col-md-3">PO MASTER<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
					                <input type="text" disabled class="form-control" value="<?=$po_number?>"/>
			                    </div>
			                </div> 	

							<div class="form-group">
			                    <label class="control-label col-md-3">Supplier<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<select disabled class='form-control' style='font-weight:bold' name="supplier_id">
			                    		<?foreach ($this->supplier_list_aktif as $row) { ?>
			                    			<option <?=($row->id==$supplier_id ? 'selected' : '');?> value="<?=$row->id?>"><?=$row->nama;?></option>
			                    		<? } ?>
			                    	</select>
					                <input hidden value="<?=$po_pembelian_id;?>" name="po_pembelian_id"/>
					                <input hidden value="<?=$batch_id;?>" name="batch_id"/>
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
						</form>
					</div>

					<div class="modal-footer">
						<button type="button" class="btn blue btn-active btn-trigger btn-edit-save">Save</button>
						<button type="button" class="btn default  btn-active" data-dismiss="modal">Close</button>
					</div>
				</div>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
		</div>

		<div class="modal fade" id="portlet-config-revisi" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body">
						<form action="<?=base_url('transaction/po_pembelian_batch_revisi')?>" class="form-horizontal" id="form-revisi" method="post">
							<h3 class='block' style='color:red'>REVISI PO </h3>


			                <div class="form-group">
			                    <label class="control-label col-md-3">Tanggal <b>REVISI</b><span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<input name='po_pembelian_id' value="<?=$po_pembelian_id;?>" <?=(is_posisi_id() != 1 ? 'hidden' : '' )?> >
			                    	<input name='batch_id' value="<?=$batch_id;?>" <?=(is_posisi_id() != 1 ? 'hidden' : '' )?> >
					                <input type="text" readonly class="form-control date-picker" value="<?=date('d/m/Y');?>" name="tanggal"/>
			                    </div>
			                </div>

			                <div class="form-group">
			                    <label class="control-label col-md-3"><b>PIN</b><span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<input type="password" class="form-control" id='pin-revisi' />
			                    </div>
			                </div>
						</form>
					</div>

					<div class="modal-footer">
						<button type="button" class="btn blue btn-active btn-trigger btn-save-revisi">Save</button>
						<button type="button" class="btn default  btn-active" data-dismiss="modal">Close</button>
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
						<form action="<?=base_url('transaction/po_pembelian_warna_insert')?>" class="form-horizontal" id="form_add_barang" method="post">
							<h3 class='block'> Data Barang</h3>
							
							<div class="form-group">
			                    <label class="control-label col-md-3">Barang (PO Master)<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-8">
			                    	<input name='po_pembelian_id' value='<?=$po_pembelian_id;?>' hidden>
			                    	<input name='po_pembelian_detail_warna_id' <?=(is_posisi_id() != 1 ? 'hidden' : '');?> >
			                    	<input name='batch_id' value='<?=$batch_id;?>' hidden>
			                    	<select name="po_pembelian_detail_id" class='form-control input1' id='barang_id_select'>
		                				<? $nama_occupied = array();
		                				foreach ($data_barang_po as $row) { 
		                					$nama_occupied[$row->nama_barang] = true;
		                					?>
			                    			<option value="<?=$row->id?>" <?=($po_pembelian_detail_last == $row->id ? 'selected' : '');?> ><?=$row->nama_barang;?> <b><?=$row->harga;?></b> | Kuota : <?=str_replace(',00','',number_format($row->sisa_kuota,'2',',','.')) ;?></option>
			                    		<? } ?>
			                    	</select>

			                    	<select id='barang_id_copy' hidden>
		                				<?foreach ($data_barang_po as $row) { 
		                					if ($harga_baru_last == '') {
			                					$harga_baru_last = ($po_pembelian_detail_last == $row->id ? $row->harga : '');
			                					if ($po_pembelian_detail_last == '') {
			                						if ($row->id) {
			                							$harga_baru_last = $row->harga;
			                						}
			                					}
		                					}
		                					?>
			                    			<option value="<?=$row->id?>" ><?=$row->harga;?>??<?=$row->barang_id?>??<?=$row->nama_barang;?>??<?=$row->barang_beli_id;?></option>
			                    		<? } ?>
			                    	</select>
			                    </div>
			                </div>

			                <div class="form-group">
			                    <label class="control-label col-md-3">Tipe<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-8">
			                    	<div class="radio-list">
										<label class="radio-inline">
					                    	<input name='tipe_barang' class='tipe-barang' onchange="filterTipeBarang('1','','')" type='radio' value='1' <?=($tipe_barang_last == '1' || $tipe_barang_last == '' ? 'checked' : '')?> <?=($tipe_barang_last == 1 ? 'checked' : '');?> >Reguler</label>
										<label class="radio-inline">
					                    	<input name='tipe_barang' class='tipe-barang' onchange="filterTipeBarang('2','','')" type='radio' value='2' <?=($tipe_barang_last == 2 ? 'checked' : '');?> >Rename</label> <a id='rename-a' href="#portlet-config-info" data-toggle="modal"><sup>info</sup></a>
										<label class="radio-inline">
					                    	<input name='tipe_barang' class='tipe-barang' onchange="filterTipeBarang('3','','')" type='radio' value='3'  <?=($tipe_barang_last == 3 ? 'checked' : '');?>>Split</label> <a id="split-a" href="#portlet-config-info" data-toggle="modal"><sup>info</sup></a>
					                    <label class="radio-inline">
					                    	<input name='tipe_barang' class='tipe-barang' onchange="filterTipeBarang('4','','')" type='radio' value='4'  <?=($tipe_barang_last == 4 ? 'checked' : '');?>>Split, Rename</label> <a id='splitrename-a' href="#portlet-config-info" data-toggle="modal"><sup>info</sup></a>
			                    	</div>
			                    </div>
			                </div>

							<input name="barang_beli_id_baru" value="<?=($barang_beli_id_baru_last != '-' ? $barang_beli_id_baru_last : '')?>" hidden>
							<input name="barang_id_baru" id="input-barang-id-baru" value="<?=($barang_id_baru_last != '-' ? $barang_id_baru_last : '')?>"  hidden >
							<input name="barang_id_baru_rename" id="input-barang-id-baru-rename" value="<?=($barang_id_baru_rename_last != '-' ? $barang_id_baru_rename_last : '')?>" hidden>
			                <div class="form-group barang-id-baru" <?=($tipe_barang_last == 1 || $barang_id_baru_last == '' ? 'hidden' : ''  );?>  >
			                    <label class="control-label col-md-3">
			                    	<span class='tipe-lain'>Kode Barang Baru</span>
		                    		<span class="required">* </span>
			                    </label>
			                    <div class="col-md-8">
									
			                    	<select class='form-control' id='barang_id_baru_select'>
			                    		<option value=''>Pilih</option>
		                				<?foreach ($barang_list_beli as $row) { 
		                					if (!isset($nama_occupied[$row->nama])) {?>
				                    			<option <?=($barang_id_baru_last == $row->barang_id && $barang_beli_id_baru_last == $row->id ? 'selected' : '' );?>  value="<?=$row->id?>??<?=$row->barang_id?>" ><?=$row->nama;?></option>
		                					<?}
		                					?>
			                    		<? } ?>
			                    	</select>
			                    </div>

			                    <label class="control-label col-md-3 form-field-tipe-4" <?=($tipe_barang_last == 4 ? '' : 'hidden'  );?> >Ditulis Sebagai<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-8 form-field-tipe-4" <?=($tipe_barang_last == 4 ? '' : 'hidden'  );?> >
			                    	<select class='form-control' id='barang_id_baru_select_rename'>
			                    		<option value=''>Pilih</option>
		                				<?foreach ($barang_list_beli as $row) { 
		                					if (!isset($nama_occupied[$row->nama])) {?>
				                    			<option <?=($barang_id_baru_last == $row->barang_id && $barang_beli_id_baru_last == $row->id ? 'selected' : '' );?>  value="<?=$row->id?>??<?=$row->barang_id?>" ><?=$row->nama;?></option>
		                					<?}
		                				} ?>
			                    	</select>
			                    </div>

			                    <label class="control-label col-md-3 form-field-tipe-2" <?=( $tipe_barang_last == 2 ? '' : 'hidden'  );?> >Ditulis Sebagai<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-8 form-field-tipe-2" <?=($tipe_barang_last == 2 ? '' : 'hidden'  );?> >
			                    	<input class='form-control' disabled id="nama-barang-ori" >
			                    </div>
			                </div>

			                <div hidden>
			                	<select id="barang-id-baru-copy" class='form-control'>
		                    		<?foreach ($this->barang_list_aktif_beli as $row) { ?>
                						<option value="<?=$row->id?>" ><?=$row->harga_beli;?></option>
		                    		<? } ?>
		                    	</select>
			                </div>

			                <div class="form-group harga-baru" >
			                    <label class="control-label col-md-3">Harga<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-8">
			                		<input type="text" class="form-control amount-number" name="harga_baru" id='harga-baru' value="<?=$harga_baru_last;?>" />
			                    	<span id='harga-baru-info' style='font-size:0.9em'></span>
			                    </div>
			                </div>

			                

			                <div class="form-group">
			                    <label class="control-label col-md-3">Warna<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-8">
			                    	<select name="warna_id" class='form-control' id='warna_id_select'>
		                				<option value=''>Pilih</option>
		                				<?foreach ($this->warna_list_aktif as $row) { ?>
			                    			<option value="<?=$row->id?>"><?=$row->warna_beli;?></option>
			                    		<? } ?>
			                    	</select>
			                    </div>
			                </div>

			                <div class="form-group">
			                    <label class="control-label col-md-3">Qty <span class='satuan_unit'></span> <span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-8">
			                		<input type="text" class="form-control" name="qty"/>
									Gunakan <span style="color:red">koma</span> untuk decimal<br/>
			                    </div>
			                </div>

			                <div class="form-group">
			                    <label class="control-label col-md-3">OCKH <span class='satuan_unit'>
			                    </label>
			                    <div class="col-md-8">
			                		<input type="text" class="form-control" name="OCKH"/>
			                    </div>
			                </div>

						</form>
					</div>

					<div class="modal-footer">
						<button type="button" class="btn blue btn-active btn-trigger btn-save-brg">Save</button>
						<button type="button" class="btn default btn-active" data-dismiss="modal">Close</button>
					</div>
				</div>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
		</div>
		
		<div class="row margin-top-10">
			<div class="col-md-12">
				<div class="portlet light">
					<div class="portlet-title hidden-print">
						<div class="caption caption-md">
							<i class="icon-bar-chart theme-font hide"></i>
							<span class="caption-subject theme-font bold uppercase"><?=$breadcrumb_small;?></span>
						</div>
						<div class="actions">
							<?if (is_posisi_id() != 6) { ?>
								<a href="#portlet-config" data-toggle='modal' class="btn btn-default btn-sm btn-form-add">
								<i class="fa fa-plus"></i>PO Batch Baru </a>
							<?}?>
							<!--<a href="#portlet-config-faktur" data-toggle='modal' class="btn btn-default btn-sm btn-form-add">
							<i class="fa fa-search"></i> Cari PO </a>-->
						</div>
					</div>
					<div class="portlet-body">

						<div style='font-size:2em;text-align:right' class='hidden-print'>
							BATCH : 
							<?$alphabet = range('A', 'Z'); $letter_now = '';
							$no_batch = '';
							foreach ($po_pembelian_data_batch as $row) {
								$no_batch = $row->batch;
								$letter = ''; $color = '';
								if ($row->revisi > 1) {
									$letter = $alphabet[$row->revisi - 2];
								}
								if ($row->status == 0) {
									$color = 'color:red';
								}
								if ($batch_id == $row->id) {
									$letter_now = $letter;
									?>
									<span style="padding:5px; background:black; color:white; <?=$color;?>"><?=$row->batch.($row->revisi > 1 ? 'R'.($row->revisi-1) :'');?></span>
								<?}else{?>
									<a style="<?=$color;?>" href="<?=base_url().is_setting_link('transaction/po_pembelian_detail_warna_batch').'?id='.$po_pembelian_id.'&batch_id='.$row->id;?>"><?=$row->batch.($row->revisi > 1 ? 'R'.($row->revisi-1) :'');?></a>
								<?}?>
							<?}?>
							<a href="#portlet-config" data-toggle='modal' class='btn-new-batch'  style="padding:3px 6px; border:2px solid black; vertical-align:top">
								<i class="fa fa-plus"></i></a>
						</div>
						<hr class='hidden'/>
						<div id="po-pembelian-header" style='margin-bottom:15px'>
							<div class='text-center'>
								<?foreach ($toko_data as $row) {
									$nama_toko = $row->nama;
									?>
									<span style='font-size:2em'> <b><?=strtoupper($row->nama);?></b></span><br/>
									<span style='font-size:1.5em'> <?=$row->alamat;?><br/>
										<?=($row->telepon != '' ? 'Telp : '.$row->telepon.'<br/>' : '');?>
										<?=($row->fax != '' ? 'Fax : '.$row->fax.'<br/>' : '');?>
										<?=($row->kota != '' ? $row->kota.' '.$row->kode_pos.'<br/>' : '');?>
									</span>
								<?}?>
							</div>
							<hr/>
							<?if ($batch_id != '' && $batch_status == 0) {
								$batch_id_revisi = '';
								$batch_next = '';
								$letter_revisi ='';
								foreach ($po_revisi_result as $row) {
									$batch_id_revisi = $row->id;
									$batch_next = $row->batch;
									// $letter_revisi = $alphabet[$row->revisi - 2];
									$letter_revisi = 'R'.($row->revisi - 1);

								}?>
								<h1 class='text-center'><span style='color:red'>PO direvisi menjadi : <a href="<?=base_url().is_setting_link('transaction/po_pembelian_detail_warna_batch')?>?id=<?=$po_pembelian_id?>&batch_id=<?=$batch_id_revisi?>"><?=$po_number?> - <?=$batch_next?><?=$letter_revisi;?></a> </span></h1>
							<?}else{?>
								<h1 class='text-center'><span style='color:blue'>DETAIL BARANG & WARNA <?=($revisi_count > 1 ? '(REVISI)' : '');?></span>
								</h1>
								<?if ($revisi_count > 1) {?>
									<h4 class='hidden-print text-center'>( revisi sub PO <a href="<?=base_url().is_setting_link('transaction/po_pembelian_detail_warna_batch')?>?id=<?=$po_pembelian_id?>&batch_id=<?=$po_ori_id;?>"><?=$po_number?> - <?=$batch;?><?='R'.($revisi_count-1);?></a>)</h4>
								<?}?>
							<?}?>
							
							<br/>
							<table width='100%'>
								<tr>
									<td class='text-left' width="60%" style='vertical-align:top'>
										<table style='font-size:1.2em'>
											<tr>
												<td colspan='3'>
													<?if (is_posisi_id() != 6 && $batch_status != 0) { ?>
														<a href="#portlet-config-edit" data-toggle='modal' class='btn btn-xs default hidden-print'><i class='fa fa-edit'></i> edit</a><br/>
													<?}?>
												</td>
											</tr>
											<tr>
												<td>PO</td>
												<td style='padding-rl-5'> : </td>
												<td><?=$po_number?></td>
											</tr>
											<tr>
												<td>Sub PO</td>
												<td style='padding-rl-5'> : </td>
												<td><b><?=$po_number?> - <?=$batch?><?=($revisi_count > 1 ? 'R'.($revisi_count - 1) : '');?></b></td>
											</tr>
											<tr>
												<td>Tanggal</td>
												<td style='padding-rl-5'> : </td>
												<td><?=$tanggal?></td>
											</tr>
											<tr class='hidden-print'>
												<td>Sales Contract</td>
												<td style='padding-rl-5'> : </td>
												<td>
													<b><?=$sales_contract;?></b>
												</td>
											</tr>
										</table>
									</td>
									<td>
										<table id="supplier-data" style='font-size:1.2em'>
											<tr>
												<td>Kepada
												</td>
												<td class='padding-rl-5'> : </td>
												<td><b><?=$nama_supplier;?></b> </td>
											</tr>
											<tr>
												<td>ATTN</td>
												<td class='padding-rl-5'> : </td>
												<td><input name='up_person' maxlength='20' style="border:none; font-weight:bold" placeholder='isi up disini' value="<?=$up_person?>"></td>
											</tr>
											<tr>
												<td style='vertical-align:top'>Alamat</td>
												<td style='vertical-align:top' class='padding-rl-5'> : </td>
												<td><b><?=nl2br($alamat);?></b> </td>
											</tr>
											<tr>
												<td>Kota</td>
												<td class='padding-rl-5'> : </td>
												<td><b><?=$kota?></b> </td>
											</tr>
										</table>
										
									</td>								
								</tr>
							</table>
						</div>

						<div class='hidden-print' style="text-align:right">
							<hr/>
							<!-- <button class='btn btn-lg btn-view-po green' disabled>PO</button> -->
							<!-- <button class='btn btn-lg btn-view-kirim blue' style='display:none'>PENGIRIMAN</button> -->
							<div style="float:left">
								<?if ($batch_id != '' && $batch_status != 0) {?>
									<a target='_blank' href="<?=base_url().is_setting_link('report/po_pembelian_report_detail');?>?id=<?=$po_pembelian_id?>&batch_id=<?=$batch_id?>" class='btn btn-lg btn-view-kirim blue'>PENGIRIMAN</a>
								<?}elseif ($batch_id != '' && $revisi_count == 0) {?>
									<span style='font-size:1.2em'>PO ini telah di revisi menjadi</span>
								<?}?>
							</div>
							<div style="width:200px; margin-left:auto">
								<input class='form-control text-left' 
									name="keterangan_batch"
									data-toggle="popover" data-trigger='focus' title="Info" 
									data-html='true' data-placement="top" data-content="Catatan ini tidak akan muncul di hasil print" 
									maxlength="100" 
									style="height:45px; color:red; padding:10px" 
									placeholder="catatan"
									id="keteranganBatchInput"
									value="<?=$keterangan_batch?>"
									>
									
							</div>
							
							
							<hr/>
						</div>
						<!-- table-striped table-bordered  -->
						<table class="table table-hover table-striped table-bordered" style='font-size:1.2em' id="general_table">
							<thead>
								<tr>
									<th scope="col">No</th>
									<th scope="col" width='200px'>
										Jenis Kain
									</th>
									<th scope="col">
										Harga
									</th>
									<th scope="col" class='text-center' width='40px'>
										No
									</th>
									<th scope="col">
										Warna
										<?if ($batch_id != '' && $batch_status != 0) { ?>
											<a href="#portlet-config-detail" data-toggle='modal' class="btn btn-xs blue btn-brg-add hidden-print">
											<i class="fa fa-plus"></i> </a>
										<?}?>
									</th>
									<th scope="col" colspan='2'>
										QTY
									</th>
									<th scope="col" hidden></th>
									<th scope="col" class='hidden-print po-side' width='200px'>
										OCKH / NO Order
									</th>
									<?$action_stat = ($batch_status == 0 ? 'hidden' : '');?>
									<th scope="col" class='hidden-print po-side' <?=$action_stat?> >
										Action
									</th>
									<th scope="col" class='report-side hidden-print'>Pengiriman</th>
									<th scope="col" class='report-side hidden-print'>Total</th>
								</tr>
							</thead>
							<tbody>
								<?
								// $po_warna = array();
								$g_total = 0; $total_row = 0; $idx = 0; $history_harga = array();
								if ($batch_id != '') {
									foreach ($data_barang_po as $row2) {
										
										if (!isset($po_warna[$row2->id])) {
											$po_warna[$row2->id] = array();
										}
										foreach ($po_pembelian_data_warna[$row2->id] as $row) {
											if (!isset($po_warna[$row2->id][$row->harga])) {
												$po_warna[$row2->id][$row->harga] = array();
											}

											array_push($po_warna[$row2->id][$row->harga], $row);
										}
									
										if (is_posisi_id() == 1) {
											// print_r($po_warna[$row2->id]);
										}

										foreach ($po_warna[$row2->id] as $key => $value) {
											$i =1;
											$merge_baris = count($po_warna[$row2->id][$key]);
											if ($merge_baris > 0 ) {
												$idx++;
											}
											$total_row += $merge_baris;
											$subtotal = 0;
											$locked_by = '';
											foreach ($value as $row) { 
												$tgl_beli = explode(',', $row->tanggal_beli);
												$qty_beli = explode(',', $row->qty_beli);
												$no_faktur = explode(',', $row->no_faktur);
												$total_kirim = 0;
												$s_total[$row->id] = 0;
												$locked_by = $row->locked_by;
												$class_bg = ($row->locked_by != '' ? 'bg-selected' : '' );
												$history_harga[$row->barang_id_baru] = $row->harga;
												?>
												<tr >
													<?if ($i == 1) {?>
														<td rowspan='<?=$merge_baris;?>' style="background:parent" ><?=$idx;?></td>
														<td rowspan='<?=$merge_baris;?>'>
															<?=$row->nama_barang; ?>
															<?if ($row->ppo_qty != 0 ) {?>
																<br/><a class='hidden-print' href="<?=base_url().is_setting_link('inventory/stok_barang_ppo_2');?>?ppo_lock_id=<?=$row->ppo_lock_id;?>"><i style='color:green' class='fa fa-info'></i> ppo (<?=is_reverse_date($row->tanggal_ppo);?>)</a>
															<?}?>
														</td>
														<td rowspan='<?=$merge_baris;?>'>
															<span class="harga-idx" data-idx="<?=$idx;?>">
																<span id="harga-<?=$idx;?>"><?=(float)$row->harga;?></span><br/>
																<small class='hidden-notes hidden-print' style='font-size:0.6em;display:block;line-height:10px'>double click<br/>untuk ubah</small>
															</span>
															<div id="change-harga-<?=$idx?>" class='change-harga-div' hidden>
																<input id="input-harga-<?=$idx?>" style='max-width:80px' value="<?=(float)$row->harga;?>">
																<input id="input-<?=$idx?>" hidden><br/>
																<button class='btn btn-xs green' onclick="updateHarga('<?=$idx?>')">save</button>
																<button class='btn btn-xs default' onclick="cancelUpdateHarga('<?=$idx?>')">cancel</button>
															</div>
														</td>
													<?}?>
													<td class='text-center <?=$class_bg?> '><?=$i;?></td>
													<td class=" <?=$class_bg?>">
														<span class='nama_beli'><?=$row->nama_warna?></span>
														<?=(is_posisi_id() == 1 ? $row->warna_id : '')?> 
														<?if ($row->tipe_barang == 2) {?>
															<br/><i class='fa fa-info hidden-print' style='color:red'></i> <span class='hidden-print' style='font-size:0.7em'><?=$row->nama_baru;?></span>
														<?}?>
													</td>
													<td class='text-right <?=$class_bg?>' style='border-right:none'>
														<?$subtotal += $row->qty?>
														<?if ($batch_status == 1 && $class_bg == '') {?>
															<input class='free-input-sm qty qty-input' data-index="<?=$idx;?>" style='border:1px solid #ddd' value="<?=str_replace(',00', '', number_format($row->qty,'2',',','.'));?>">
														<?}else{?>
															<span class='free-input-sm qty' ><?=str_replace(',00', '', number_format($row->qty,'2',',','.'));?> </span>
														<?}?>
														<?if ($row->ppo_qty != 0 ) {?>
															<br/><small class='hidden-print'><i style='color:green; font-size:0.9em' class='fa fa-info'></i> ppo : <?=number_format($row->ppo_qty,'0',',','.');?></small>
														<?}?>

													</td>
													<td style='border-left:none' class=" <?=$class_bg?>">
														<?=$nama_satuan=$row->nama_satuan;?>
													</td>
													<td class="hidden-print po-side  <?=$class_bg?>">
														<input <?=($row->locked_by != '' ? 'disabled' : '')?> name="OCKH" value="<?=$row->OCKH?>" width='100px' class='OCKH'>
													</td>
													<td class="hidden-print po-side  <?=$class_bg?>"  <?=$action_stat?> >
														<span class='po_pembelian_detail_id' hidden><?=$row->po_pembelian_detail_id;?></span>
														<span class='tipe_barang' hidden><?=$row->tipe_barang;?></span>
														<span class='barang_id_baru' hidden><?=$row->barang_id_baru;?></span>
														<span class='harga_baru' hidden ><?=$row->harga;?></span>
														<span class='warna_id' hidden><?=$row->warna_id;?></span>
														<span class='id' data-harga="<?=$idx;?>" <?=(is_posisi_id() != 1 ?'hidden' : '');?> ><?=$row->id;?></span>
														<?if($row->locked_by == ''){
															if ($batch_status == 1 || is_posisi_id() == 1) {?>
																<a href='#portlet-config-detail' data-toggle='modal' class="btn-xs btn green btn-detail-edit"><i class="fa fa-edit"></i> </a>
																<a class="btn-xs btn red  btn-detail-remove"><i class="fa fa-times"></i> </a>
															<?}?>
														<?}else{?>
															LOCKED
														<?}?>
													</td>
													<td class='hidden-print report-side  <?=$class_bg?>'>
														<?if (count($qty_beli) > 0) {
															echo "<table class='rinci-table'>";
															for ($k=0; $k < count($qty_beli)/6 ; $k++) { 
																echo "<tr>";
																for ($l=0; $l <6 ; $l++) { 
																	echo "<td>";
																	echo (isset($qty_beli[$l + ($k*6)]) ? str_replace(',00', '' , number_format($qty_beli[$l + ($k*6)],'2',',','.'))  : '' );
																	$show = '';
																	if (isset($qty_beli[$l + ($k*6)])) {
																		$total_kirim += $qty_beli[$l + ($k*6)];
																		$show = $no_faktur[$l + ($k*6)]."</b> <br/>Tanggal : <b>".is_reverse_date($tgl_beli[$l + ($k*6)]);?>
																		<a target='_blank' class='hidden-print' data-toggle="popover" data-trigger='hover' title="Info" data-html='true' data-content="No Invoice : <b><?=$show;?></b>">
																			<i class='fa fa-info-circle' ></i>
																		</a>
																	<?}
																	echo "</td>";
																}
																echo "</tr>";
																if (($k+1) %2 == 0 ) {?>
																	<tr>
																		<td colspan='6'><hr style='margin:5px; '/></td>
																	</tr>
																<?}
															}
															echo "</table>";
														}?>
														<?//=$row->qty_beli;?>
														<?//=$row->tanggal_beli;?>
														<?//=$row->no_faktur;?>
													</td>
													<td class='hidden-print report-side'>
														<b><?=number_format($total_kirim,'0',',','.');?></b>
													</td>
												</tr>
											<? $i++;
											}
										}
										?>

										<?if (isset($po_pembelian_data_warna[$row2->id]) && count($po_pembelian_data_warna[$row2->id]) > 0) {?>
											<tr>
												<td></td>
												<td colspan='4' class='text-right'>TOTAL</td>
												<td class='text-right' style='border-right:0px' id='batch-qty-total-<?=$idx?>'><?=str_replace(',00', '', number_format($subtotal,'2',',','.'));?> </td>
												<td style='border-left:none'><?=$nama_satuan;?></td>
												<td class='hidden-print po-side'></td>
												<td class='hidden-print po-side'></td>
												<td class='report-side hidden-print'></td>
												<td class='report-side hidden-print'  <?=$action_stat?> ></td>
											</tr>
											<tr class='kosong'>
												<td colspan='5' style='border:none'></td>
												<td colspan='2' style='border:none'></td>
												<td class='hidden-print po-side' style='border:none'></td>
												<td class='hidden-print po-side' style='border:none'></td>
												<td class='report-side hidden-print'></td>
												<td class='report-side hidden-print'  <?=$action_stat?> ></td>
											</tr>
										<?}?>

										<?
										foreach ($po_pembelian_data_warna_split[$row2->id] as $isi) {
											if ($isi->tipe_barang == 3) {
                                                if (!isset($po_split[$row2->id][$isi->barang_id_baru][$isi->harga][$isi->nama_warna])) {
                                                    $po_split[$row2->id][$isi->barang_id_baru][$isi->harga][$isi->nama_warna] = array();
                                                }
                                                $po_split[$row2->id][$isi->barang_id_baru][$isi->harga][$isi->nama_warna] = $isi;
											}elseif($isi->tipe_barang == 4){
                                                // echo $row2->id.$isi->barang_id_baru_rename.$isi->nama_warna.$isi->harga;
                                                if (!isset($po_split[$row2->id][$isi->barang_id_baru_rename][$isi->harga][$isi->nama_warna])) {
                                                    $po_split[$row2->id][$isi->barang_id_baru_rename][$isi->harga][$isi->nama_warna] = array();
                                                }
												$po_split[$row2->id][$isi->barang_id_baru_rename][$isi->harga][$isi->nama_warna] = $isi;
											}
										}
										// $merge_baris = count($po_split_baris[$row2->id][$isi->barang_id_baru]);
										// if ($merge_baris > 0 ) {
										// 	$idx++;
										// }

										// foreach ($po_split_baris[$row2->id] as $key => $value) {
										// 	$idx++;
										// }
										if (count($po_pembelian_data_warna_split[$row2->id]) > 0) {
											# code...
											$subtotal = 0;
											foreach ($po_split[$row2->id] as $key => $value) {
												// $i =1;
												// $idx++;
												// $merge_baris = count($po_split[$row2->id][$key]);
												foreach ($po_split[$row2->id][$key] as $key2 => $value2) {
                                                    // echo $key2;
                                                    // echo count($po_split[$row2->id][$key][$key2]);
                                                    // echo '<hr/>';
                                                    $i =1;
                                                    $merge_baris = count($po_split[$row2->id][$key][$key2]);
                                                    if ($merge_baris > 0 ) {
                                                        $idx++;
                                                    }
                                                    $total_row += $merge_baris;
                                                    $subtotal = 0;
                                                    $locked_by = '';
                                                    $total_row += $merge_baris;
                                                    foreach ($value2 as $row) {
                                                        $tgl_beli = explode(',', $row->tanggal_beli);
                                                        $qty_beli = explode(',', $row->qty_beli);
                                                        $no_faktur = explode(',', $row->no_faktur);
                                                        $total_kirim = 0;
                                                        $s_total[$row->id] = 0;
                                                        $class_bg = ($row->locked_by != '' ? 'bg-selected' : '' );
                                                        $history_harga[$row->barang_id_baru] = $row->harga;
													?>
                                                        <tr>
                                                            <?if ($i == 1) {?>
                                                                <td rowspan='<?=$merge_baris;?>'><?=$idx;?></td>
                                                                <td rowspan='<?=$merge_baris;?>'>
                                                                    <?=($row->tipe_barang == 4 ? $row->nama_direname  : $row->nama_baru); ?>
                                                                    <!-- <br/><span class='hidden-print' style='font-size:0.7em'>Harga : <span class='harga-baru'><?=number_format($row->harga,'0',',','.');?></span></span> -->
                                                                    <br/><span class='hidden-print' style='font-size:0.7em'><i class='fa fa-info' style='color:red'></i> split kuota dari <?=$row->nama_barang;?></span>
                                                                </td>
                                                                <td rowspan='<?=$merge_baris;?>'>
                                                                    <?//=number_format($row->harga,'0',',','.');?>

                                                                    <span class="harga-idx" data-idx="<?=$idx;?>">
                                                                        <span id="harga-<?=$idx;?>"><?=str_replace(',00','',number_format($row->harga,'2',',','.'));?></span><br/>
                                                                        <small class='hidden-notes hidden-print' style='font-size:0.6em;display:block;line-height:10px'>double click<br/>untuk ubah</small>
                                                                    </span>
                                                                    <div id="change-harga-<?=$idx?>" class='change-harga-div' hidden>
                                                                        <input id="input-harga-<?=$idx?>" style='max-width:80px' value="<?=(float)$row->harga;?>" >
                                                                        <input id="input-<?=$idx?>" <?=(is_posisi_id()!=1 ? 'hidden' : '');?> ><br/>
                                                                        <button class='btn btn-xs green' onclick="updateHarga('<?=$idx?>')">save</button>
                                                                        <button class='btn btn-xs default' onclick="cancelUpdateHarga('<?=$idx?>')">cancel</button>
                                                                    </div>
                                                                </td>

                                                            <?}?>
                                                            <td class='text-center <?=$class_bg;?> '><?=$i;?> <?//=$row->barang_id_baru;?> <?//=$row->tipe_barang;?></td>
                                                            <td class=" <?=$class_bg;?> ">
                                                                <span class='nama_beli'><?=$row->nama_warna?></span> 
                                                                <?=(is_posisi_id() == 1 ? $row->warna_id : '')?> 
                                                                <?if ($row->tipe_barang == 4) {?>
                                                                    <br/><i class='fa fa-info hidden-print' style='color:red'></i> <span class='hidden-print' style='font-size:0.7em'><?=$row->nama_baru;?></span>
                                                                <?}?>
                                                            </td>
                                                            <td class='text-right <?=$class_bg;?> ' style='border-right:none'>
                                                                <?$subtotal += $row->qty?>
                                                                <?if ($batch_status == 1) {?>
                                                                    <input class='free-input-sm qty qty-input' data-index="<?=$idx;?>" value="<?=str_replace(',00', '', number_format($row->qty,'2',',','.'));?>">
                                                                <?}else{?>
                                                                    <span class='free-input-sm qty' ><?=str_replace(',00', '', number_format($row->qty,'2',',','.'));?> </span>
                                                                <?}?>
                                                            </td>
                                                            <td style='border-left:none' class=" <?=$class_bg;?> ">
                                                                <?=$nama_satuan=$row->nama_satuan;?>
                                                            </td>
                                                            <td class="hidden-print po-side <?=$class_bg;?> ">
                                                                <input name="OCKH" value="<?=$row->OCKH?>" width='100px' class='OCKH'>
                                                            </td>
                                                            <td class='hidden-print po-side  <?=$class_bg;?> '  <?=$action_stat?> >
                                                                <span class='po_pembelian_detail_id' hidden><?=$row->po_pembelian_detail_id;?></span>
                                                                <span class='tipe_barang'hidden><?=$row->tipe_barang;?></span>
                                                                <span class='barang_id_baru' hidden><?=$row->barang_id_baru;?></span>
                                                                <span class='barang_id_baru_rename' hidden><?=$row->barang_id_baru_rename;?></span>
                                                                <span class='harga_baru' hidden ><?=$row->harga;?></span>
                                                                <span class='warna_id' hidden><?=$row->warna_id;?></span>
                                                                <span class='id' data-harga="<?=$idx;?>" hidden><?=$row->id;?></span>
                                                                <?if($row->locked_by == ''){
                                                                    if ($batch_status == 1) {?>
                                                                        <a href='#portlet-config-detail' data-toggle='modal' class="btn-xs btn green btn-detail-edit"><i class="fa fa-edit"></i> </a>
                                                                        <a class="btn-xs btn red  btn-detail-remove"><i class="fa fa-times"></i> </a>
                                                                    <?}?>
                                                                <?}else{?>
                                                                    LOCKED
                                                                <?}?>
                                                            </td>
                                                            <td class='report-side hidden-print'>
                                                                <?if (count($qty_beli) > 0) {
                                                                    echo "<table class='rinci-table'>";
                                                                    for ($k=0; $k < count($qty_beli)/6 ; $k++) { 
                                                                        echo "<tr>";
                                                                        for ($l=0; $l <6 ; $l++) { 
                                                                            echo "<td>";
                                                                            echo (isset($qty_beli[$l + ($k*6)]) ? str_replace(',00', '' , number_format($qty_beli[$l + ($k*6)],'2',',','.'))  : '' );
                                                                            $show = '';
                                                                            if (isset($qty_beli[$l + ($k*6)])) {
                                                                                $total_kirim += $qty_beli[$l + ($k*6)];
                                                                                $show = $no_faktur[$l + ($k*6)]."</b> <br/>Tanggal : <b>".is_reverse_date($tgl_beli[$l + ($k*6)]);?>
                                                                                <a target='_blank' class='hidden-print' data-toggle="popover" data-trigger='hover' title="Info" data-html='true' data-content="No Invoice : <b><?=$show;?></b>">
                                                                                    <i class='fa fa-info-circle' ></i>
                                                                                </a>
                                                                            <?}
                                                                            echo "</td>";
                                                                        }
                                                                        echo "</tr>";
                                                                    }
                                                                    echo "</table>";
                                                                }?>
                                                            </td>
                                                            <td class='hidden-print report-side'>
                                                                <b><?=number_format($total_kirim,'0',',','.');?></b>
                                                            </td>
                                                        </tr>
                                                    <?$i++;} 
													
												
												}
												if (count($po_pembelian_data_warna[$row2->id]) > 0 || count($po_pembelian_data_warna_split[$row2->id]) ) {?>
													<tr>
														<td></td>
														<td colspan='4' class='text-right'>TOTAL</td>
														<td class='text-right' style='border-right:0px'> <?=str_replace(',00', '', number_format($subtotal,'2',',','.'));?> </td>
														<td style='border-left:none'><?=$nama_satuan;?></td>
														<td class='hidden-print po-side'></td>
														<td class='hidden-print po-side'></td>
														<td class='report-side hidden-print'></td>
														<td class='report-side hidden-print'  <?=$action_stat?> ></td>

													</tr>
													<tr class='kosong'>
														<td colspan='5' style='border:none'></td>
														<td colspan='2' style='border:none'></td>
														<td class='hidden-print po-side' style='border:none'></td>
														<td class='hidden-print po-side' style='border:none'></td>
														<td class='report-side hidden-print'></td>
														<td class='report-side hidden-print'  <?=$action_stat?> ></td>
													</tr>
												<?}
											}
										}
										?>

									<?}
								}?>
							</tbody>
						</table>
						<?if($revisi_count > 1){?>
						<span style='font-size:1.2em'>
							<b>NOTE :</b> PO ini merupakan PO revisi dari <a href="<?=base_url().is_setting_link('transaction/po_pembelian_detail_warna_batch')?>?id=<?=$po_pembelian_id?>&batch_id=<?=$po_ori_id;?>"><?=$po_number?> - <?=$batch?></a>
						</span>
							<hr/>
						<?}?>
						<hr class='hidden-print'/>
						<div id="signature_place">
							<table width='80%' style="margin:auto; margin-top:50px;font-size:1.5em;">
								<tr>
									<td style='width:40%; text-align:center'>Penerima,</td>
									<td style='width:20%'></td>
									<td style='width:40%; text-align:center'><?=$nama_toko?> ,</td>
								</tr>
								<tr>
									<td style='height:100px'></td>
									<td style='height:100px'></td>
									<td style='height:100px'></td>
								</tr>
								<tr>
									<td style='border-bottom:1px solid black'></td>
									<td></td>
									<td style='border-bottom:1px solid black'></td>
								</tr>
							</table>
						</div>
						<br/>
						<hr class='hidden-print'/>
						<div>
							<?if ($batch_status == 1) {?>
								<a <?=($total_row == 0 ? "disabled" : " href='".base_url()."transaction/po_batch_close?po_pembelian_id=$po_pembelian_id&batch_id=$batch_id&status=2'" );?>  class='btn btn-active btn-lg yellow-gold hidden-print btn-finish'><i class='fa fa-lock'></i>Finish u/ Print</a>
							<?}elseif($batch_status == 2){?>
								<a <?=($total_row == 0 ? "disabled" : " onclick='window.print()'" );?>  class='btn btn-lg blue hidden-print'><i class='fa fa-print'></i> Print </a>
								<a <?=($total_row == 0 ? "disabled" : " href='#portlet-config-pin' data-toggle='modal'" );?>  class='btn btn-active btn-lg green hidden-print'><i class='fa fa-edit'></i>Edit Item</a>
								<a <?=($total_row == 0 ? "disabled" : " href='#portlet-config-revisi' data-toggle='modal'" );?>  class='btn btn-lg yellow-gold hidden-print'><i class='fa fa-print'></i> Revisi </a>
							<?}else{}
							?>
			                <?/*if ($po_status == 1) { ?>
				                <a <?=($i == 1 ? 'disabled' : '' );?> class="btn btn-lg yellow-gold btn-active <?=($i != 1 ? 'btn-finish' : '');?> hidden-print"><i class='fa fa-print'></i> Finish </a>
			                <?}elseif ($po_status == 0 && is_posisi_id() <= 3 ) {?>
				                <a href="<?=base_url()?>transaction/po_pembelian_open?id=<?=$po_pembelian_id;?>" class='btn btn-lg yellow-gold btn-edit-po hidden-print'><i class='fa fa-print'></i> Edit </a>
			                <?}*/?>
			                <a href="javascript:window.open('','_self').close();" class="btn btn-lg default button-previous hidden-print">Close</a>
			                <button class="btn btn-lg red default button-remove hidden-print">HAPUS</button>


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
<script>
var history_harga = {};
var history_harga_global = {};
const po_pembelian_id = "<?=$po_pembelian_id?>";
var barang_list = [];

<?
if($batch_id != ''){
	foreach ($data_barang_po as $row2) {
		foreach ($po_pembelian_data_warna[$row2->id] as $row) {?>
		barang_list[`s-<?=$row->barang_beli_id?>-<?=$row->warna_id?>`] = true;
	<?}foreach ($po_pembelian_data_warna_split[$row2->id] as $row) {?>
		barang_list[`s-<?=$row->barang_beli_id_baru?>-<?=$row->warna_id?>`] = true;
	<?}
	}
}?>

jQuery(document).ready(function() {

	get_history_harga();
   	$('[data-toggle="popover"]').popover();	  	
	
	$('#barang_id_baru_select, #barang_id_baru_select_rename').select2({
        placeholder: "Pilih...",
        allowClear: true
    });

    $('#warna_id_select').select2({
        placeholder: "Pilih...",
        allowClear: true
    });

    $('#harga-baru').change(function(){
    	let barang_id = $('#barang_id_baru_select').val();

    });

    $("#rename-a, #rename-info-btn").click(function() {
    	$(".btn-info").removeClass("green").addClass("default");
    	$(".info-content").hide();
    	$("#rename-info-btn").removeClass("default").addClass("green");
    	$('#rename-info').show();
    });

    $("#split-a, #split-info-btn").click(function() {
    	$(".btn-info").removeClass("green").addClass("default");
    	$(".info-content").hide();
    	$("#split-info-btn").removeClass("default").addClass("green");
    	$('#split-info').show();
    });

    $("#splitrename-a, #splitrename-info-btn").click(function() {
    	$(".btn-info").removeClass("green").addClass("default");
    	$(".info-content").hide();
    	$("#splitrename-info-btn").removeClass("default").addClass("green");
    	$('#splitrename-info').show();
    });

    <?if ($po_pembelian_id != '' && is_posisi_id() != 6) { ?>
    	var map = {220: false};
		$(document).keydown(function(e) {
		    if (e.keyCode in map) {
		        map[e.keyCode] = true;
		        if (map[220]) {
					<?if($batch_id != ''){?>
						$('#portlet-config-detail').modal('toggle');
						setTimeout(function(){
							$('#barang_id_select').select2("open");
						},600);

					<?}elseif($batch_id == ''){?>
						alert("Buat Batch baru terlebih dahulu dengan klik tombol'+' di kanan atas");
					<?}?>
		        }
		    }
		}).keyup(function(e) {
		    if (e.keyCode in map) {
		        map[e.keyCode] = false;
		    }
		});
    <?}?>

    //===========================remove=================================================

    <?if ($batch_id != '') {?>
	    $(".button-remove").click(function() {
	    	bootbox.confirm("Yakin menghapus sub PO ini (tidak dapat dikembalikan) ? ", function(respond) {
	    		if (respond) {
	    			window.location.replace(baseurl+"transaction/po_pembelian_detail_warna_batch_remove?id="+<?=$po_pembelian_id?>+"&batch_id="+<?=$batch_id;?>);
	    		};
	    	})
	    });
    <?}?>

    //============================================================================


    $('#general_table').on('click', '.btn-detail-edit', function(){
    	const ini = $(this).closest('tr');
    	let form = $('#form_add_barang');
    	let tipe_barang = ini.find('.tipe_barang').html();
		let barang_id_baru = '';
		let harga_baru = '';

    	form.find("[name=po_pembelian_detail_warna_id]").val(ini.find('.id').html());
    	form.find("[name=po_pembelian_detail_id]").val(ini.find('.po_pembelian_detail_id').html());
    	form.find("[name=po_pembelian_detail_id]").change();
    	form.find("[name=warna_id]").val(ini.find('.warna_id').html());
    	form.find("[name=warna_id]").change();
		<?if($batch_status == 1){?>
			form.find("[name=qty]").val(ini.find('.qty').val().replace(".",""));
		<?}else{?>
			// alert(ini.find('.qty').html());
			form.find("[name=qty]").val(ini.find('.qty').html());
		<?}?>
		// alert(ini.find('.qty').html());

    	if (tipe_barang != 1) {
			// console.log('u',ini.find('.barang_id_baru').html());
			barang_id_baru = ini.find('.barang_id_baru').html();
	    	// form.find("[name=barang_id_baru]").val(ini.find('.barang_id_baru').html());
	    	if (tipe_barang == 4) {
	    		// alert(ini.find('.barang_id_baru_rename').html());
		    	form.find("[name=barang_id_baru_rename]").val(ini.find('.barang_id_baru_rename').html());
		    	form.find("[name=barang_id_baru_rename]").change();
		    	$(".form-field-tipe-4").show();
	    	};
    	}else{
	    	form.find("[name=barang_id_baru]").val('');
    	}
    	form.find("[name=barang_id_baru]").change();

    	harga_baru = ini.find('.harga_baru').html();
		harga_baru = harga_baru.replace('.00','');

    	form.find("[name=tipe_barang]").prop('checked',false);
    	$.uniform.update(form.find("[name=tipe_barang]"));
    	form.find("[name=tipe_barang][value='"+tipe_barang+"']").prop('checked',true);
    	$.uniform.update(form.find("[name=tipe_barang]"));
    	setTimeout(function(){
			filterTipeBarang(tipe_barang, barang_id_baru, harga_baru);
	    	// form.find("[name=tipe_barang]").change();
    	},600)
    	
    });

    // $(document).on("change",".tipe-barang", function(){
    // 	var tipe_barang = $('#form_add_barang').find('[name=tipe_barang]:checked').val();
    // 	// alert(tipe_barang);
    // 	<?if (is_posisi_id()==1) {?>
    // 			console.log(tipe_barang);
    // 		<?}?>
    // 	if (parseInt(tipe_barang) != 1) {
    // 		$('.barang-id-baru').slideDown();
    // 		$('[name=barang_id_baru]').val('');
    // 		$('[name=barang_id_baru]').change();
    // 		if (tipe_barang == 3 || tipe_barang == 4 ) {
    // 			$('.harga-baru').show();
    // 		}else{
    // 			$('.harga-baru').hide();
    // 		}

    	
    // 		if (tipe_barang == 4) {
    // 			$(".form-field-tipe-4").show();
    // 		}else{
    // 			$(".form-field-tipe-4").hide();
    // 		}

    // 		if (tipe_barang == 2) {
    // 			$(".form-field-tipe-2").show();
    // 			let detail_id = $('#barang_id_select').val();
	// 	    	var dt = $("#barang_id_copy [value='"+detail_id+"']").text().split('??');
	// 	    	let nama = dt[2];
	// 	    	$("#nama-barang-ori").val(nama);
    // 		}else{
    // 			$(".form-field-tipe-2").hide();
    // 		}


    // 	}else{
    			
	// 		$('.harga-baru').show();
    // 		$('#barang_id_select').select2("open");
    // 		$('.barang-id-baru').slideUp();
    // 	};
    // });

    //=============form add data========================
    $(".btn-save").click(function(){
    	if( $("#form_add_data [name=tanggal]").val() != ''){
    		$("#form_add_data").submit();
    		btn_disabled_load($(".btn-save"));
    	}else{
    		alert("Tanggal tidak boleh kosong");
    	}
    });

    $('.btn-save-brg').click(function(){
    	let po_pembelian_detail_id = $('#form_add_barang [name=po_pembelian_detail_id]').val();
		let po_pembelian_detail_warna_id = $('#form_add_barang [name=po_pembelian_detail_warna_id]').val();
    	let qty = $('#form_add_barang [name=qty]').val();
		let tipe_barang  = $('#form_add_barang [name=tipe_barang]:checked').val();
		let barang_id_baru = $('#form_add_barang [name=barang_id_baru]').val();
		let barang_id_baru_rename = $('#form_add_barang [name=barang_id_baru_rename]').val();
		let warna_id = $('#warna_id_select').val();
		let brg_selected = '';
		if(tipe_barang == 1){
			let detail_id = $('#barang_id_select').val();
	    	var dt = $("#barang_id_copy [value='"+detail_id+"']").text().split('??');
	    	brg_selected = dt[3];
		}else{
			brg_selected = barang_id_baru;
		}

		// console.log('ini',tipe_barang, brg_selected, warna_id, barang_list, po_pembelian_detail_warna_id);
		if (typeof barang_list[`s-${brg_selected}-${warna_id}`] !== 'undefined' && po_pembelian_detail_warna_id == '') {
			alert('Barang sudah Terdaftar');
		}else if ( po_pembelian_detail_id != '' && qty != '' && warna_id != '') {
    		if(tipe_barang != 1){
    			if( barang_id_baru != ''){
    				if (tipe_barang != 4) {
		    			$('#form_add_barang').submit();
			    		btn_disabled_load($(".btn-save-brg"));	
    				}else{
    					if (barang_id_baru_rename != '') {
    						$('#form_add_barang').submit();
				    		btn_disabled_load($(".btn-save-brg"));
    					}else{
		    				alert('pilih barang rename');
    					};
    				}
    			}else{
    				alert('pilih barang baru');
    			}
    		}else{
	    		$('#form_add_barang').submit();
	    		btn_disabled_load($(".btn-save-brg"));
    		}
    	}else{
    		alert('mohon lengkapi data');
    	}
    });

    $('.btn-brg-add').click(function(){
    	// var select2 = $(this).data('select2');
    	$("#form_add_barang [name=po_pembelian_detail_warna_id]").val("");
    	$("#form_add_barang [name=qty]").val("");
    	setTimeout(function(){
    		$('#barang_id_select').select2("open");
    		// $('#form_add_barang .input1 .select2-choice').click();
    	},700);
    });

    $("#barang_id_select").select2({
        placeholder: "Pilih...",
        allowClear: true
    }).on("select2-blur", function(e) { 
    	var tipe_barang = $('#form_add_barang').find('[name=tipe_barang]:checked').val();
    	let detail_id = $('#barang_id_select').val();
    	let barang_id;
    	if (tipe_barang == 1) {
	    	var dt = $("#barang_id_copy [value='"+detail_id+"']").text().split('??');
	    	barang_id = dt[1];
	    	let harga =  $("#harga-baru").val();
	    	if (harga != '') {
	    		for (var i = 0; i < history_harga.length; i++) {
	    			console.log(`${history_harga[i].barang_id} == ${barang_id} && ${history_harga[i].harga} != ${harga} `)
	    			if(history_harga[i].barang_id == barang_id && history_harga[i].harga != harga ){
				    	$("#harga-baru").val(history_harga[i].harga);
	    				// bootbox.alert(`Harga barang terakhir ${change_number_format(history_harga[i].harga)}`);
	    			}
	    		};
	    	};
	    }
    });

    $('#barang_id_select').change(function(){
    	$('#harga-baru-info').text();
    	var barang_id = $('#barang_id_select').val();
    	var dt = $("#barang_id_copy [value='"+barang_id+"']").text().split('??');
    	let harga=dt[0];
    	$("#form_add_barang [name=harga_baru]").val(parseFloat(harga));
    });

    //#barang_id_baru_select_rename, 
    $("#barang_id_baru_select, #barang_id_baru_select_rename").change(function(){
    	$('#harga-baru-info').html('');
    	let po_barang_id_ori = $("#barang_id_select").val();
    	const barang_data = $('#barang_id_baru_select').val().split('??');

    	const barang_beli_id_baru = barang_data[0];
    	let barang_id = barang_data[1];

    	const barang_data_rujukan = $('#barang_id_baru_select_rename').val().split('??');
    	let barang_id_baru_rujukan = barang_data_rujukan[0];
    	let barang_id_rujukan = barang_data_rujukan[1];
		
		$("#form_add_barang [name=barang_id_baru]").val(barang_id);
		$("#form_add_barang [name=barang_beli_id_baru]").val(barang_beli_id_baru);
		$("#form_add_barang [name=barang_id_baru_rename]").val(barang_id_rujukan);

    	var tipe_barang = $('#form_add_barang').find('[name=tipe_barang]:checked').val();
    	var dt = $("#barang_id_copy [value='"+po_barang_id_ori+"']").text().split('??');
    	let harga = '';

    	if (tipe_barang != 2) {
	    	harga = $("#barang-id-baru-copy [value='"+barang_id+"']").text();
    	}else{
    		harga=dt[0];
    	};

    	let barang_id_ori = dt[1];
		$('#form_add_barang [name=harga_baru]').val(harga);
		var tipe_barang = $('#form_add_barang').find('[name=tipe_barang]:checked').val();
    	if (tipe_barang != 1) {
	    	if (harga != '') {
	    		let fin = false;
	    		for (var i = 0; i < history_harga.length; i++) {
	    			<?if (is_posisi_id() == 1) {?>
	    				// alert(tipe_barang);
	    			<?}?>
	    			if(tipe_barang == 2 && history_harga[i].barang_id == barang_id_ori && history_harga[i].harga != harga ){
	    			console.log(`${history_harga[i].barang_id} == ${barang_id} && ${history_harga[i].harga} != ${harga} `)
						$('#harga-baru').val(history_harga[i].harga);
						fin = true;
						return;
	    			}else if(history_harga[i].barang_id == barang_id && history_harga[i].harga != harga){
	    			console.log(`${history_harga[i].barang_id} == ${barang_id} && ${history_harga[i].harga} != ${harga} `)
						$('#harga-baru').val(history_harga[i].harga);
						fin = true;
						return;
	    			}else if (tipe_barang == 4 && history_harga[i].barang_id == barang_id_rujukan){
	    			console.log(`${history_harga[i].barang_id} == ${barang_id} && ${history_harga[i].harga} != ${harga} `)
	    				$('#harga-baru').val(history_harga[i].harga);
						fin = true;
						return;
	    			}
	    		};
	    		if(tipe_barang != 2 && fin == false){
					$('#harga-baru').val('');
					get_latest_harga(barang_id);
					return;
    			}
	    	};
	    }
		/*if (typeof history_harga[barang_id_baru_rename] !== 'undefined') {
    		$('#form_add_barang [name=harga_baru]').val(history_harga[barang_id_baru_rename]);
    	};*/
    });


    $('#general_table').on('click','.btn-detail-remove', function(){
	    let ini = $(this).closest('tr');
    	let url = baseurl+'transaction/pembelian_detail_warna_remove';
	    bootbox.confirm("Mau menghapus item ini ? ", function(respond){
	    	if (respond) {
	    		let id = ini.find('.id').html();
	    		let batch_id = "<?=$batch_id?>";
	    		let po_pembelian_id = "<?=$po_pembelian_id?>";
	    		window.location.replace(url+"?id="+id+"&batch_id="+batch_id+"&po_pembelian_id="+po_pembelian_id);
	    	};
	    });
    });

    <?if ($po_pembelian_id != '') { ?>
    	$(document).on('change','.OCKH', function(){
	    	var ini = $(this).closest('tr');
	    	var data = {};
	    	data['id'] =  ini.find(".id").html();
	    	data['OCKH'] = $(this).val();
	    	var url = 'transaction/po_pembelian_OCKH_update';
	    	// update_table(ini);
	    	ajax_data_sync(url,data).done(function(data_respond  /*,textStatus, jqXHR*/ ){
				if (data_respond == 'OK') {
					notific8("lime", "OCKH updated");
				}else{
					alert("error");
				}
	   		});
	    });

    <?}?>

    // $('.btn-finish').click(function(){
    // 	btn_disabled_load($(this));
    // 	let po_pembelian_id = "<?=$po_pembelian_id?>";
    // 	window.location.replace(baseurl+"transaction/po_pembelian_finish?id="+po_pembelian_id)
    // });

    $("#general_table").on('click','.show-warna', function () {
    	const ini = $(this).closest('tr');
    	let data_id = $(this).attr("id").split('-');
    	let id = data_id[1];
    	// alert($('#data-warna-'+id).html());
    	$('.data-warna-'+id).toggle("slow");
    });
    //================change side views=========================================

    $(".btn-view-kirim").click(function() {
    	$(".po-side").hide();
    	$(".report-side").show();
    	$(this).attr('disabled',true);
    	$('.btn-view-po').attr('disabled',false);
    });

    $(".btn-view-po").click(function() {
    	$(".report-side").hide();
    	$(".po-side").show();
    	$(this).attr('disabled',true);
    	$('.btn-view-kirim').attr('disabled',false);
    });

    //=============form add data========================
    $(".btn-edit-save").click(function(){
    	if( $("#form_edit_data [name=tanggal]").val() != ''){
    		$("#form_edit_data").submit();
    		btn_disabled_load($(".btn-edit-save"));
    	}else{
    		alert("Tanggal tidak boleh kosong");
    	}
    });

    <?if ($po_pembelian_detail_last !='') {?>
    	$('#portlet-config-detail').modal('toggle');
    	$('#warna_id_select').focus();
        setTimeout(function(){
        	$('#warna_id_select').select2("open");
        },700);
    <?}?>

    $(".btn-finish").click(function(){
    	btn_disabled_load($(this));
    });
    //==================revisi===========================

    $(".btn-save-revisi").click(function(){
    	if($("#form-revisi [name=tanggal]").val() != ''){
    		var data = {};
			data['pin'] = $('#pin-revisi').val();

    		var url = 'transaction/cek_pin';
			var result = ajax_data(url,data);
			if (result == 'OK' && data['pin'] !='') {
				// alert("OK");
				btn_disabled_load($(".btn-save-revisi"));
	    		$("#form-revisi").submit();
			}else{
				alert("PIN Salah");
			}
    	}
    });

    //=====================Edit=========================

    $("#general_table").on("change",".qty-input", function(){
    	const ini = $(this).closest("tr");
		const point = $(this);
    	const id = ini.find(".id").html();
    	const data = {};
		const qty = $(this).val();
    	data['id'] =  id;
    	data['qty'] = $(this).val();
    	const url = 'transaction/po_pembelian_batch_qty_update';
    	// update_table(ini);
    	ajax_data_sync(url,data).done(function(data_respond  /*,textStatus, jqXHR*/ ){
			if (data_respond == 'OK') {
				update_total_qty();
				point.val(change_number_format(qty));
			}else{
				alert("error");
			}
   		});
    });
    //=====================request open=========================

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

    //=====================request open=========================
    $(".harga-idx").dblclick(function(){
    	$(`.change-harga-div`).hide();
    	$(`.harga-idx`).show();
    	let idx = $(this).attr("data-idx");
    	let id_warna = [];
    	$("#general_table").find(`[data-harga='${idx}']`).each(function(){
    		id_warna.push($(this).text());
    	});

    	$(`#change-harga-${idx}`).show();
    	$(`#input-${idx}`).val(id_warna.join(','));
    	$(this).hide();
    });

	$('#keteranganBatchInput').change(function(){
		let data = {};
		data['id'] =  "<?=$batch_id;?>";
		data['keterangan_batch'] = $('#keteranganBatchInput').val();

		var url = 'transaction/update_keterangan_batch';
		// update_table(ini);
		ajax_data_sync(url,data).done(function(data_respond  ,textStatus, jqXHR ){
			if (data_respond=='OK') {
				notific8("lime", "Keterangan batch di update");
			};
		});
	})

});

function updateHarga(idx){
	let data = {};
	data['id_list'] =  $(`#input-${idx}`).val();
	data['harga'] = $(`#input-harga-${idx}`).val();

	var url = 'transaction/po_pembelian_update_harga_baru';
	// update_table(ini);
	ajax_data_sync(url,data).done(function(data_respond  ,textStatus, jqXHR ){
		if (textStatus=='success') {
			notific8("lime", "Harga updated");
	    	$(`#change-harga-${idx}`).hide();
	    	$(`.harga-idx`).show();
			$(`[data-harga='${idx}']`).closest("tr").find(".harga_baru").html(data['harga']);
			$(`#harga-${idx}`).text(change_number_format(data['harga']));
		};
	});
}

function cancelUpdateHarga(idx){
    $(`#change-harga-${idx}`).hide();
    $(`[data-idx='${idx}']`).show();

}

function get_history_harga(){
	let data = {};
	data['po_pembelian_id'] =  po_pembelian_id;
	var url = 'transaction/po_pembelian_get_history_harga';
	// update_table(ini);
	ajax_data_sync(url,data).done(function(data_respond  /*,textStatus, jqXHR*/ ){
		history_harga = JSON.parse(data_respond);	
		console.log(history_harga);
	});
}

function get_latest_harga(barang_id){
	let data = {};
	data['po_pembelian_id'] =  po_pembelian_id;
	data['barang_id'] =  barang_id;
	var url = 'transaction/po_pembelian_get_latest_harga_barang';
	// update_table(ini);
	ajax_data_sync(url,data).done(function(data_respond  /*,textStatus, jqXHR*/ ){
		$.each(JSON.parse(data_respond), function(i,v){
			// console.log(v);
			if (v.harga != '' && v.harga != 0) {
				$('#harga-baru-info').html(`Harga terakhir <b>${v.harga}</b> di PO (${v.po_number}) - <b>${v.tanggal}</b>`);
			};
		});
		// console.log(data_respond);
	});
}

function update_total_qty(){
	var total = 0;
	const subtotal = [];
	$("#general_table .qty-input").each(function(){
		let qtyString = $(this).val().toString();
		const idx = $(this).attr("data-index");
		if (typeof subtotal[idx] === 'undefined') {
			subtotal[idx] = 0;
		}
		// qtyString = qtyString.replace(/\./g,'');
		qtyString = qtyString.toString().replaceAll('.','');
		subtotal[idx] += parseFloat(qtyString);
		total += parseFloat(qtyString);
	});

	console.log('sb', subtotal);
	subtotal.forEach((el,idx) => {
		$(`#batch-qty-total-${idx}`).html(el);
	});
	// $("#batch-qty-total").html(total.toString().replace(/\./g,','));
	notific8("lime", "qty updated");
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
	}
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

function filterTipeBarang(tipe_barang, barang_id_baru, harga_baru) {
    	// alert(tipe_barang);
	<?if (is_posisi_id()==1) {?>
			console.log(barang_id_baru);
		<?}?>
	if (parseInt(tipe_barang) != 1) {
		$('.barang-id-baru').slideDown();
		$('[name=barang_id_baru]').val('');
		$('[name=barang_beli_id_baru]').val('');
		$('#barang_id_baru_select').val('');
		$('#barang_id_baru_select').change();
		if (tipe_barang == 3 || tipe_barang == 4 ) {
			$('.harga-baru').show();
			$('#harga-baru').val(harga_baru);
		}else{
			$('.harga-baru').hide();
		}

	
		if (tipe_barang == 4) {
			$(".form-field-tipe-4").show();
		}else{
			$(".form-field-tipe-4").hide();
		}

		if (tipe_barang == 2) {
			$(".form-field-tipe-2").show();
			let detail_id = $('#barang_id_select').val();
			var dt = $("#barang_id_copy [value='"+detail_id+"']").text().split('??');
			let nama = dt[2];
			$("#nama-barang-ori").val(nama);
		}else{
			$(".form-field-tipe-2").hide();
		}


	}else{
		
		// alert(harga_baru);
		$('#harga-baru').val(harga_baru);
		$('.harga-baru').show();
		$('#barang_id_select').select2("open");
		$('.barang-id-baru').slideUp();
	};
}
</script>