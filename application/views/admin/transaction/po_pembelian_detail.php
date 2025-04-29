<link href="<?=base_url('assets/global/plugins/bootstrap-modal/css/bootstrap-modal.css');?>" rel="stylesheet" type="text/css"/>
<?=link_tag('assets/global/plugins/bootstrap-datepicker/css/datepicker3.css'); ?>

<style type="text/css">

.overlayer-full{
	width: 100%;
	height: 100%;
	position: absolute;
	top: -10px;
	left: 0px;
	border-radius: 10px;
	padding:100px 0;
	background-image: linear-gradient(to bottom right, rgba(43, 40, 50, 0.9) 0%, rgba(83, 86, 99, 0.9) 45%, rgba(69, 77, 91, 0.8) 60%);
}

.overlayer-full div{
	padding: 20px 0;
}

.overlayer-full .btn{
	padding: 20px 30px;
	font-size: 3em;
}

.bg-selected{
	background: #FFB6C1;
}

@media print{
	#supplier-data textarea{
		border: none;
	}

	html{
		padding-left: 50px;
	}

	.bg-selected{
		background: transparent;
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
			$sales_contract = '';
			$tanggal = '';
			$ori_tanggal = '';
			$toko_id = '';
			$nama_toko = '';

			$ppn_berlaku = get_ppn_now(date('Y-m-d'));
			$ppn_berlaku = (float)$ppn_berlaku;
			
			$po_status = 0;
			$status_aktif = 0;
			$catatan = "Harga di atas sudah termasuk PPN ".(float)$ppn_berlaku."%";
			$nama_satuan = '';

			$release_person = '';
			$release_date = '';

			foreach ($po_pembelian_data as $row) {
				$po_pembelian_id = $row->id;
				$supplier_id = $row->supplier_id;
				$nama_supplier = $row->nama_supplier;
				$po_number = $row->po_number;
				$sales_contract = $row->sales_contract;
				$tanggal = is_reverse_date($row->tanggal);
				$ori_tanggal = $row->tanggal;
				$toko_id = $row->toko_id;
				$nama_toko = $row->nama_toko;
				$alamat = $row->alamat;
				$up_person = $row->up_person;
				$kota = $row->kota;

				$ppn_berlaku = get_ppn_now($row->tanggal);
				$catatan = "Harga di atas sudah termasuk PPN ".(float)$ppn_berlaku."%";
				
				$po_status = $row->po_status;
				$status_aktif = $row->status_aktif;
				if($row->catatan != ''){
					$catatan = $row->catatan;
				}

				$release_person = $row->release_master_person;
				$release_date = $row->release_master_date;
			}

			$readonly = ''; $disabled = '';
			if (is_posisi_id() == 6) {
				$readonly = 'readonly';
				$disabled = 'disabled';
			}
		?>


		<div class="modal fade bs-modal-lg" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-body">
						<form action="<?=base_url('transaction/po_pembelian_list_insert')?>" class="form-horizontal" id="form_add_data" method="post">
							<h3 class='block' style='background:#eee; padding:5px'> PO Pembelian Baru</h3>
							
							<div class="form-group">
			                    <label class="control-label col-md-3">Supplier<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
					                <select class='input1 form-control supplier-input' style='font-weight:bold' name="supplier_id">
			                    		<?foreach ($this->supplier_list_aktif as $row) { ?>
			                    			<option <?=($row->id==1 ? 'selected' : '');?> value="<?=$row->id?>"><?=$row->nama;?></option>
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
			                    <label class="control-label col-md-3">UP / ATTN<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
					                <input type="text" class="form-control" maxlength='24' value="" name="up_person"/>
			                    </div>
			                </div> 	

			                <div class="form-group" >
			                    <label class="control-label col-md-3">Sales Contract
			                    </label>
			                    <div class="col-md-6">
			                		<input type="text" class='form-control' name="sales_contract"/>
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

			                <div class="form-group" hidden>
			                    <label class="control-label col-md-3">Catatan
			                    </label>
			                    <div class="col-md-6">
			                		<input type="text" class='form-control' name="catatan"/>
			                    </div>
			                </div>
						</form>
					</div>

					<div class="modal-footer">
						<button type="button" class="btn btn-active btn-trigger blue btn-save">Save</button>
						<button type="button" class="btn  btn-active default" data-dismiss="modal">Close</button>
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
						<form action="<?=base_url('transaction/po_pembelian_list_update')?>" class="form-horizontal" id="form_edit_data" method="post">
							<h3 class='block'> Edit Data</h3>
							
							<div class="form-group">
			                    <label class="control-label col-md-3">Supplier<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                		<input type="text" name="po_pembelian_id" value='<?=$po_pembelian_id;?>' <?=(is_posisi_id() == 1 ? '' : 'hidden')?> />
			                    	<select class='input1 form-control supplier-input' style='font-weight:bold' name="supplier_id">
			                    		<?foreach ($this->supplier_list_aktif as $row) { ?>
			                    			<option <?if ($supplier_id == $row->id) {echo 'selected';}?> value="<?=$row->id?>"><?=$row->nama;?></option>
			                    		<? } ?>
			                    	</select>
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
			                    <label class="control-label col-md-3">UP / ATTN<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
					                <input type="text" class="form-control"  maxlength='24' value="<?=$up_person?>" name="up_person"/>
			                    </div>
			                </div> 	

							<div class="form-group" hidden>
			                    <label class="control-label col-md-3">Sales Contract
			                    </label>
			                    <div class="col-md-6">
			                		<input type="text" class='form-control' name="sales_contract" value="<?=$sales_contract?>"/>
			                    </div>
			                </div>

			                
			                <div class="form-group">
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
						<form action="<?=base_url('transaction/po_pembelian_list_detail_insert')?>" class="form-horizontal" id="form_add_barang" method="post">
							<h3 class='block'> Data Barang</h3>
							
							<div class="form-group">
			                    <label class="control-label col-md-3">Kode Barang<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<input name='po_pembelian_detail_id' hidden>
			                    	<input name='barang_id' hidden >
			                    	<input name='barang_beli_id' hidden >
			                    	<input name='po_pembelian_id' value='<?=$po_pembelian_id;?>' hidden>
			                    	<select name="barang_data_id" class='form-control input1' id='barang_id_select'>
		                				<option value=''>Pilih</option>
		                				<?foreach ($barang_list_beli as $row) { ?>
			                    			<option value="<?=$row->id?>??<?=$row->barang_id?>"><?=$row->nama;?></option>
			                    		<? } ?>
			                    	</select>
									
			                    	<?if ($po_pembelian_id == '') {?>
				                    	<select name='data_barang' hidden >
				                    		<?foreach ($barang_list_beli as $row) { ?>
				                    			<option value="<?=$row->id?>"><?=$row->nama_satuan;?>??<?=$row->harga_beli;?></option>
				                    		<? } ?>
				                    	</select>
			                    	<?}else{?>
			                    		<select name='data_barang' hidden>
				                    		<?foreach ($barang_list_extra as $row) { ?>
				                    			<option value="<?=$row->id?>"><?=$row->nama_satuan;?>??<?=$row->harga_beli;?>??<?=$row->nama_tercetak;?></option>
				                    		<? } ?>
				                    	</select>
			                    	<?}?>
			                    </div>
			                </div>

			                <div class="form-group" hidden>
			                    <label class="control-label col-md-3">Nama Tercetak<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<input name='nama_tercetak' class='form-control'>
			                    </div>
			                </div>

			                <div class="form-group">
			                    <label class="control-label col-md-3">Harga<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
					                <input type="text" class="form-control" value="" name="harga"/>
			                    </div>
			                </div> 			                

			                <div class="form-group">
			                    <label class="control-label col-md-3">Qty <span class='satuan_unit'></span> <span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                		<input type="text" class="form-control" name="qty"/>
									Gunakan <span style="color:red">koma</span> untuk decimal<br/>
			                		PO warna : <span class='po-warna-qty' hidden></span> Yard
			                    </div>
			                </div>

			                <div class="form-group" hidden>
			                    <label class="control-label col-md-3">Jumlah Roll
			                    </label>
			                    <div class="col-md-6">
			                		<input type="text" class='form-control' name="jumlah_roll"/>
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

		<div class="modal fade" id="portlet-config-faktur" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body">
						<form action="<?=base_url().is_setting_link('transaction/po_pembelian_detail');?>" class="form-horizontal" id="form_search_po" method="post">
							<h3 class='block'> Cari PO</h3>
							
							<div class="form-group">
			                    <label class="control-label col-md-3">PO Number<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
									<input type="hidden" name='po_pembelian_id' id="search_po_number" class="form-control select2">
			                    </div>
			                </div>	
		                </form>		                
					</div>

					<div class="modal-footer">
						<button type="button" class="btn blue btn-search-po">GO!</button>
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
						<form action="<?=base_url('transaction/po_pembelian_open');?>" class="form-horizontal" id="form-request-open" method="get">
							<h3 class='block'> Request Open</h3>
							
							<?if ($release_person != '') {?>
								<div class="note note-danger">
									Status <b>release</b> otomatis di lepaskan jika melakukan edit PO
								</diV>
							<?}?>
								
							<div class="form-group">
			                    <label class="control-label col-md-3">PIN<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<input name='id' value='<?=$po_pembelian_id;?>' hidden>
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
								<i class="fa fa-plus"></i>PO Pembelian Baru </a>
							<?}?>
							<!--<a href="#portlet-config-faktur" data-toggle='modal' class="btn btn-default btn-sm btn-form-add">
							<i class="fa fa-search"></i> Cari PO </a>-->
						</div>
					</div>
					<div class="portlet-body">

						<?if ($release_person !='') {?>
							<h4 class='note note-warning hidden-print text-center' style='margin:40px 0px;'>
								PO released oleh <b><?=$release_person?></b> pada <b><?=date('d M Y', strtotime($release_date));?></b>
							</h4>
						<?}?>

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
							<h1 class='text-center'>PURCHASE ORDER</h1>
							<br/>
							<table width='100%'>
								<tr>
									<td class='text-left' width="60%" style='vertical-align:top'>
										<span style='font-size:2em'><b> PO: <?=$po_number;?></b></span><br/>
										<span style='font-size:1.4em'>Tanggal : <?=$tanggal?> </span> <br/>
										<div class='hidden-print'>
											Sales Contract : <?=$sales_contract;?>
										</div>
									</td>
									<td>
										<?if (is_posisi_id() != 6 && $po_status == 1) { ?>
											<button href="#portlet-config-edit" data-toggle='modal' class='btn btn-xs hidden-print'><i class='fa fa-edit'></i> edit</button><br/>
										<?}?>
										<table id="supplier-data" style='font-size:1.2em'>
											<tr>
												<td>Kepada</td>
												<td class='padding-rl-5'> : </td>
												<td><b><?=$nama_supplier;?></b> </td>
											</tr>
											<tr>
												<td>ATTN</td>
												<td class='padding-rl-5'> : </td>
												<!-- <td><input id="up-person-updated" name='up_person' style="border:none; font-weight:bold" placeholder='isi up disini' value="<?=$up_person?>"></td> -->
												<td><b><?=$up_person?></b></td>
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

						<?if (count($po_pembelian_detail) > 0 && $po_number != '' ) {?>
							<div class='hidden-print' style="margin:10px 0">
								<a target='_blank' href="<?=base_url().is_setting_link('transaction/po_pembelian_detail_warna_batch').'?id='.$po_pembelian_id;?>" class='btn default' style="width:100%"><i class='fa fa-arrow-right'></i>PO DETAIL BATCH</a>
							</div>
						<?}else{?>
							<div class='note note-info'>Untuk mengakses PO (Detail Warna) <i>generate</i> dahulu PO Number (<i>klik</i> Finish)</div>
						<?}?>

						<!-- table-striped table-bordered  -->
						<table class="table table-hover table-striped table-bordered" style='font-size:1.4em' id="general_table">
							<thead>
								<tr>
									<th scope="col">
										No
									</th>
									<th scope="col">
										Jenis Kain
										<?if ($po_pembelian_id != '' && is_posisi_id() !=6 &&  $po_status == 1) { ?>
											<a href="#portlet-config-detail" data-toggle='modal' class="btn btn-xs blue btn-brg-add hidden-print">
											<i class="fa fa-plus"></i> </a>
										<?}?>
									</th>
									<th scope="col">
										QTY
									</th>
									<th scope="col">
										Harga
									</th>
									<th scope="col">
										Total
									</th>
									<th scope="col" class='hidden-print'>
										Action
									</th>
								</tr>
							</thead>
							<tbody>
								<?
								$i =1; $g_total = 0; $qty_total = 0;
								foreach ($po_pembelian_detail as $row) { 
									$s_total[$row->id] = 0;?>
									<tr>
										<td>
											<span class='id' hidden="hidden"><?=$row->id;?></span>
											<?=$i;?> 
										</td>
										<td>
											<span class='nama_beli'><?=($row->nama_tercetak == '' ? $row->nama_barang : $row->nama_tercetak);?></span> 
											<span class='nama_tercetak' hidden><?=$row->nama_tercetak;?></span>
											<?if(count($po_pembelian_warna[$row->id]) > 0) { ?>
												<a id="show-<?=$row->id;?>" class='show-warna hidden-print' style='font-size:0.9em;'><i class='fa fa-arrow-down'></i> warna</a>
											<?}?>
											<?if ($row->nama_tercetak != '') {?>
												<br/><i class='fa fa-info hidden-print' style='color:red'></i><small style='font-size:0.7em;' class='hidden-print'> ori: <?=$row->nama_barang;?></small>
											<?}?> 
										</td>
										<td class='text-right'>
											<?$qty_total += $row->qty;?>
											<span class='qty'><?=str_replace(',00', '', number_format($row->qty,'2',',','.'));?></span> 
											<?=$nama_satuan=$row->nama_satuan;?>
										</td>
										<td class='text-right'>
											<span class='harga'><?=str_replace(',00', '', number_format($row->harga,'2',',','.'));?></span> 
										</td>
										<td class='text-right'>
											<?
												$subtotal = $row->qty * $row->harga;
												$g_total += $subtotal;
											?>
											<span <?=$readonly;?> class='subtotal'><?=number_format($subtotal,'0','.','.');?></span> 
										</td>
										<td class='hidden-print'>
											<span class='id' hidden><?=$row->id;?></span>
											<span class='qty-warna-total' hidden><?=$row->qty_warna_total;?></span>
											<span class='barang_id' hidden><?=$row->barang_id;?></span>
											<span class='barang_beli_id' hidden><?=$row->barang_beli_id;?></span>
											<?if(is_posisi_id() != 6){?>
												<a href="<?=base_url().is_setting_link('transaction/po_pembelian_detail_warna');?>?po_pembelian_id=<?=$po_pembelian_id?>&po_pembelian_detail_id=<?=$row->id;?>&view_type=1" class="btn-xs btn yellow-gold" onclick="window.open(this.href, 'newwindow', 'width=1250, height=650'); return false;"><i class="fa fa-search"></i> </a>
												<?if ( $po_status == 1) {?>
													<a href='#portlet-config-detail' data-toggle='modal' class="btn-xs btn green btn-detail-edit"><i class="fa fa-edit"></i> </a>
													<?if (count($po_pembelian_warna[$row->id]) == 0) {?>
														<a class="btn-xs btn red  btn-detail-remove"><i class="fa fa-times"></i> </a>
													<?}?>
												<?}?>
											<?} ?>
										</td>
									</tr>
									<tr class='hidden-print data-warna-<?=$row->id;?> ' style="border:none; background:#ccd; background-size:80%; display:none">
											<td>
												<td onclick="sortTable(1,'switchable-<?=$row->id;?>')">Warna</td>
												<td onclick="sortTable(2,'switchable-<?=$row->id;?>')">Qty</td>
												<td  colspan='3' onclick="sortTable(3,'switchable-<?=$row->id;?>')">Qty Masuk <span style="float:right">Persen</span> </td>
											</td>
										</tr>
									<?
									$batch_before = 0;
									$border = '';
									$tanggal_batch = "";
									foreach ($po_pembelian_warna[$row->id] as $row2) {
										if ($batch_before != $row2->batch) {
											if ($batch_before != 0) {
												$border= "border-top:2px solid #171717 !important";
											}
											$batch_before = $row2->batch;
											$tanggal_batch  = is_reverse_date($row2->tanggal);
										}else{
											$border = "";
											$tanggal_batch = "";
										}
										?>
										<tr class='hidden-print data-warna-<?=$row->id;?> switchable-<?=$row->id;?>' style="border:none; background:#ccd; background-size:80%; display:none; <?=$border?>">
											<td style='border-color:#ddd;'>
											</td>
											<td>
												<?=$row2->nama_beli_baru;?>
												<span class='nama_warna'><?=$row2->nama_warna;?></span> <i><small>(batch <?=$row2->batch;?>) - <?=$tanggal_batch;?></small></i>
											</td>
											<td class='text-right'>
												<span class='qty-warna'> <?=str_replace(',00', '', number_format($row2->qty,'2',',','.'));?></span>
												<?=$row->nama_satuan;
													$s_total[$row->id] += $row2->qty;
												?>
											</td>
											<?
												$persen = $row2->qty_beli / $row2->qty * 100;
											?>
											<td colspan='3' style="padding:0px;">
												<div style='background:<?=($persen > 100 ? '#ffbabf' : '#a8c9ff');?>;font-size:0.9em; text-align:right;width:<?=($persen > 100 ? '100' : $persen);?>%; display:block; height:40px; padding:10px;'><?=str_replace(',00','',number_format($persen,'2',',','.'))?>%
													<?if ($row2->qty_beli > 0) {?>
														<span style="float:left"><?=number_format((float)$row2->qty_beli,'0',',','.');?></span>
													<?}?>

												</div>
												<span class='po_pembelian_warna_id' hidden="hidden"><?=$row2->id;?></span>
												<span class='warna_id' hidden><?=$row2->barang_id;?></span>
											</td>
										</tr>
									<?}
									if ($s_total[$row->id] != 0) {?>
										<tr class='hidden-print data-warna-<?=$row->id;?>' style="border:none; background:#ccd;  display:none">
											<td></td>
											<td></td>
											<td class="text-right" style=""><b style='font-size:1.2em; border-top:1px solid black; padding-left:20px'>SISA KUOTA : <?=number_format($row->qty - $s_total[$row->id],'0',',','.')?> <?=$row->nama_satuan;?></b></td>
											<td colspan='3'></td>
										</tr>
									<?}
									?>
								<? $i++;} ?>
								<tr style='font-weight:bold'>
									<td></td>
									<td>TOTAL</td>
									<td class='text-right'><?=str_replace(',00','',number_format($qty_total,'2',',','.')) ;?> <?=$nama_satuan?></td>
									<td></td>
									<td class='text-right'><?=number_format($g_total,'0',',','.');?></td>
									<td class='hidden-print'></td>
								</tr>
							</tbody>
						</table>
						<table width='100%' style='font-size:1.5em'>
							<tr>
								<td width='100px' style='vertical-align:top'><b>NOTE : </b></td>
								<td>
									 <textarea <?=($po_status == 0 ? 'readonly' : '' );?> style='width:100%; border:none' rows='3' id="catatanInput" onchange="updateCatatan()" ><?=$catatan;?></textarea>
								</td>
							</tr>
						</table>

						<hr class='hidden-print'/>
						<div id="signature_place">
							<table width='80%' style="margin:auto; margin-top:20px;font-size:1.5em;">
								<tr>
									<td style='width:30%; text-align:center'>Penerima,</td>
									<td style='width:40%'></td>
									<td style='width:40%; text-align:center'><?=$nama_toko?> ,</td>
								</tr>
								<tr>
									<td style='height:150px'></td>
									<td style='height:150px'></td>
									<td style='height:150px'></td>
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
							<?if ($po_status == 0 && is_posisi_id() <= 3) {?>
				                <a class='btn btn-lg green hidden-print btn-print' ><i class='fa fa-print'></i> Print </a>
				                <!--<a href="<?=$_SERVER['REQUEST_URI']?>&print_view=0" class='btn btn-lg blue hidden-print'><i class='fa fa-print'></i> Edit View </a>-->
							<?}?>
			                <?if ($po_status == 1) { ?>
				                <a <?=($i == 1 ? 'disabled' : '' );?> class="btn btn-lg yellow-gold btn-active <?=($i != 1 ? 'btn-finish' : '');?> hidden-print"><i class='fa fa-print'></i> Finish </a>
			                <?}elseif ($po_status == 0 && is_posisi_id() <= 3 ) {?>
				                <a href="#portlet-config-pin" data-toggle="modal" class='btn btn-lg yellow-gold btn-edit-po hidden-print' ><i class='fa fa-print'></i> Edit </a>
			                <?}?>
							<?if ($po_status == 0 && $release_person == '') {?>
								<button class="btn btn-lg purple default hidden-print" id="btn-release" onclick="releasePOMaster('<?=$po_pembelian_id;?>')"><i class='fa fa-send'></i> Release</button>

							<?}?>
			                <!-- <a href="javascript:window.open('','_self').close();" class="btn btn-lg default button-previous hidden-print">Close</a> -->
						</div>

						<?if ($po_status == 99) {?>
							<div class='overlayer-full'>
								<div class='col-xs-12 text-center'><button class='btn btn-lg blue btn-master'>PO MASTER</button></div>
								<div class='col-xs-12 text-center'><a href="<?=base_url().is_setting_link('transaction/po_pembelian_detail_warna_batch').'?id='.$po_pembelian_id;?>" class='btn btn-lg green'>PO BATCH</a></div>
							</div>
						<?}?>
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

const po_pembelian_id = "<?=$po_pembelian_id;?>";
var up_person = "<?=$up_person?>";
if (up_person == '') {
	$("#up-person-updated").val();
};

jQuery(document).ready(function() {

	
	$('#barang_id_select').select2({
        placeholder: "Pilih...",
        allowClear: true
    });

    $('#warna_id_select').select2({
        placeholder: "Pilih...",
        allowClear: true
    });

    <?if ($po_pembelian_id != '' && is_posisi_id() != 6) { ?>
    	var map = {220: false};
		$(document).keydown(function(e) {
		    if (e.keyCode in map) {
		        map[e.keyCode] = true;
		        if (map[220]) {
		            $('#portlet-config-detail').modal('toggle');
		            setTimeout(function(){
		            	$('#barang_id_select').select2("open");
		            },600);
		        }
		    }
		}).keyup(function(e) {
		    if (e.keyCode in map) {
		        map[e.keyCode] = false;
		    }
		});
    <?}?>

    $('.btn-save-brg').click(function(){
    	let po_warna_qty = parseFloat(reset_number_format($('#form_add_barang').find('.po-warna-qty').html()));
    	let po_qty = parseFloat(reset_number_format($('#form_add_barang').find('[name=qty]').val()));

		console.log(po_qty, po_warna_qty);
    	if(po_qty < po_warna_qty){
    		alert("Qty PO Total minimal sama atau lebih besar dari PO warna");
    	}else{
    		if ($('#form_add_barang [name=barang_id]').val() != '') {
	    		$('#form_add_barang').submit();
	    		btn_disabled_load($(this));
	    	};
    	}
    });

    $(".btn-save").click(function(){
    	if ( $("#form_add_data [name=tanggal]").val() != '' ) {
    		btn_disabled_load($(".btn-save"));
	    	$("#form_add_data").submit();
    	}else{
    		alert("Mohon isi tanggal");
    	}
    });

    $(".btn-edit-save").click(function(){
    	if ( $("#form_edit_data [name=tanggal]").val() != '' ) {
    		btn_disabled_load($(".btn-save"));
	    	$("#form_edit_data").submit();
    	}else{
    		alert("Mohon isi tanggal");
    	}
    });

    $('#general_table').on('click', '.btn-detail-edit', function(){
    	const ini = $(this).closest('tr');
    	const form = $('#form_add_barang');
    	const qty = reset_number_format(ini.find('.qty').html());
    	const harga = reset_number_format(ini.find('.harga').html());
		const barang_id = ini.find('.barang_id').html();
		const barang_beli_id = ini.find('.barang_beli_id').html();

    	form.find("[name=po_pembelian_detail_id]").val(ini.find('.id').html());
    	form.find("#barang_id_select").val(`${barang_beli_id}??${barang_id}`);
    	form.find("#barang_id_select").change();
    	form.find("[name=barang_id]").val(barang_id);
    	form.find("[name=barang_beli_id]").val(barang_beli_id);
    	form.find("[name=qty]").val(change_number_format(qty));
    	form.find("[name=nama_tercetak]").val(ini.find('.nama_tercetak').html());
    	form.find(".po-warna-qty").html(change_number_format(parseFloat(ini.find('.qty-warna-total').html())));
    	form.find(".po-warna-qty").show();
    	form.find("[name=harga]").val(change_number_format2(harga));

    });

    $('.btn-brg-add').click(function(){
    	// var select2 = $(this).data('select2');
    	setTimeout(function(){
    		$('#barang_id_select').select2("open");
    		// $('#form_add_barang .input1 .select2-choice').click();
    	},700);
    });

    $('#barang_id_select').change(function(){
    	const barang_beli_data = $(this).val().split('??');
		console.log(barang_beli_data);
    	const barang_id = barang_beli_data[1];
    	const barang_beli_id = barang_beli_data[0];
		const data = $("#form_add_barang [name=data_barang] [value='"+barang_id+"']").text().split('??');
		
		$('#form_add_barang [name=barang_id]').val(barang_id);
		$('#form_add_barang [name=barang_beli_id]').val(barang_beli_id);
   		// alert(data);
		$('#form_add_barang [name=harga]').val(change_number_format2(parseFloat(data[1])) );
		$('#form_add_barang .satuan_unit').html(data[0]+'/kg');
		$('#form_add_barang [name=satuan]').val(data[0]);
		if (data[2] != '') {
			$('#form_add_barang [name=nama_tercetak]').val(data[2]);
		}else{
			$('#form_add_barang [name=nama_tercetak]').val('');
		}
    });


    $('#general_table').on('click','.btn-detail-remove', function(){
	    var ini = $(this).closest('tr');
	    bootbox.confirm("Mau menghapus item ini ? ", function(respond){
	    	if (respond) {
	    		var data = {};
		    	data['id'] =  ini.find('.id').html();
		    	var url = 'transaction/po_pembelian_detail_remove';
		    	// update_table(ini);
		    	ajax_data_sync(url,data).done(function(data_respond  /*,textStatus, jqXHR*/ ){
					if (data_respond == 'OK') {
						ini.remove();
						update_table();
						window.location.reload();
					};
		   		});
	    	};
	    });
    });

    <?if ($po_pembelian_id != '') { ?>
    	$(document).on('change','[name=sales_contract]', function(){
	    	var ini = $(this).closest('tr');
	    	var data = {};
	    	data['po_pembelian_id'] =  "<?=$po_pembelian_id;?>";
	    	data['sales_contract'] = $(this).val();
	    	var url = 'transaction/po_pembelian_sales_contract_update';
	    	// update_table(ini);
	    	ajax_data_sync(url,data).done(function(data_respond  /*,textStatus, jqXHR*/ ){
				if (data_respond == 'OK') {
					notific8("lime", "Sales Contract updated");
				};
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
			var url = "transaction/get_search_po_number";
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
    	var id = $("#form_search_po [name=po_pembelian_id]").val();
    	var action = $("#form_search_po").attr('action');
    	if (id != '') {
    		window.location.replace(action+'/'+id);
    	};
    });



    $('.btn-finish').click(function(){
    	btn_disabled_load($(this));
    	let po_pembelian_id = "<?=$po_pembelian_id?>";
    	if (up_person == '') {
    		bootbox.confirm("ATTN (Up) belum diisi, Yakin mengunci PO ? ", function(respond){
    			if (respond) {
			    	window.location.replace(baseurl+"transaction/po_pembelian_finish?id="+po_pembelian_id)
    			}
    		});
    	}else{
	    	window.location.replace(baseurl+"transaction/po_pembelian_finish?id="+po_pembelian_id)
    	}
    	// alert('ok');
    });

    
    $(".btn-print").click(function(){
    	if (up_person == '') {
			bootbox.confirm("ATTN (Up) belum diisi, Tetap mencetak PO ? ", function(respond){
				if (respond) {
					setTimeout(() => {
						window.print();
					}, 1000);
				}
			});
    	}else{
	    	window.print();
    	}
    })

    $("#general_table").on('click','.show-warna', function () {
    	const ini = $(this).closest('tr');
    	let data_id = $(this).attr("id").split('-');
    	let id = data_id[1];
    	// alert($('#data-warna-'+id).html());
    	$('.data-warna-'+id).toggle("slow");
    });

//=============================supplier data================================

	$("#supplier-data [name=up_person]").change(function(){
		let form = $("#supplier-data");
		let po_pembelian_id = "<?=$po_pembelian_id;?>";
    	let url = 'transaction/po_pembelian_data_update';
		let data = {};
    	data['id'] =  "<?=$po_pembelian_id?>";
    	data['up_person'] = form.find('[name=up_person]').val();
    	// update_table(ini);
    	ajax_data_sync(url,data).done(function(data_respond  /*,textStatus, jqXHR*/ ){
			if (data_respond == 'OK') {
				notific8("lime","updated");
			};
   		});

	});

//==============================add data=======================================

	$(".btn-form-add").click(function(){
		let form = $("#form_add_barang");
		
		form.find("[name=po_pembelian_detail_id]").val('');
		form.find("#barang_id_select").val('');
		form.find("#barang_id_select").change();
		form.find("[name=qty]").val('');
		form.find(".po-warna-qty").hide();
		form.find(".po-warna-qty").html('0');
		form.find("[name=harga]").val('');
	});

	$(".btn-master").click(function() {
		$(".overlayer-full").hide();
	});

//==============================open data=======================================


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

});

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
</script>

<script>
function sortTable(idx, classRow) {
  var table, rows, switching, i, x, y, shouldSwitch;
  // table = document.getElementById("myTable");
  switching = true;
  while (switching) {
    switching = false;
    // rows = table.rows;
    rows = document.getElementsByClassName(classRow);
    for (i = 0; i < (rows.length - 1); i++) {
		shouldSwitch = false;
		x = rows[i].getElementsByTagName("TD")[idx];
		y = rows[i + 1].getElementsByTagName("TD")[idx];
	    console.log(x);
		if (idx != 2) {
			if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
			shouldSwitch = true;
			break;
			}
		}else{
			var x_qty = x.getElementsByClassName('qty-warna')[0].innerHTML.replace('.','');
			var y_qty = y.getElementsByClassName('qty-warna')[0].innerHTML.replace('.','');
			// console.log(parseInt(x_qty) +' > '+ parseInt(y_qty));
			// alert(x.find('.qty-warna').innerHTML.replace('.',''));
			if (parseInt(x_qty) > parseInt(y_qty) ) {
				// console.log(parseInt(x_qty) +' > '+ parseInt(y_qty));
				shouldSwitch = true;
				break;
			}
		}
    }
    if (shouldSwitch) {
      rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
      switching = true;
    }
  }
}

function updateCatatan(){
	var data = {};
	data['id'] =  "<?=$po_pembelian_id?>";
	data['catatan'] = $('#catatanInput').val();
	var url = 'transaction/po_pembelian_catatan_update';
	// update_table(ini);
	ajax_data_sync(url,data).done(function(data_respond  /*,textStatus, jqXHR*/ ){
		notific8("lime", "Note updated")
	});
}

function cek_pin(form){
	// alert('test');
	var data = {};
	data['pin'] = form.find('.pin_user').val();
	var url = 'transaction/cek_pin';

	var result = ajax_data(url,data);
	if (result == 'OK') {
		return true;
	}
}

function releasePOMaster(){
	const txt = (up_person !== '' ? "Yakin untuk merelease <b>PO MASTER</b> ini ? " : "<b> Belum diisi</b>.<br/>Yakin untuk merelease <b>PO MASTER</b> ini ? " )
	bootbox.confirm(txt, function(res){
		if(res){
			var dialog = bootbox.dialog({
				title: 'Releasing PO....',
				message: '<p><i class="fa fa-spin fa-spinner"></i> Loading...</p>'
			});
			
			fetch(baseurl+`transaction/po_pembelian_release?id=${po_pembelian_id}`)
				.then((response) => response.json())
				.then((data) => {
					dialog.find('.bootbox-body').html('PO MASTER Released...refreshing page');
					setTimeout(() => {
						window.location.reload();
					}, 1500);
				}).catch((error) => {
					dialog.find('.modal-title').html(`Error <i class='fa fa-warning'></i>`);
					dialog.find('.bootbox-body').html('Releasing PO error, mohon kontak admin');
				});
		}
	})
}

</script>
