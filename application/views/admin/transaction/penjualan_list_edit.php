<link href="<?=base_url('assets/global/plugins/bootstrap-modal/css/bootstrap-modal.css');?>" rel="stylesheet" type="text/css"/>

<!-- <link href="<?=base_url('assets_noondev/css/bootstrap-modal-bs3patch.css');?>" rel="stylesheet" type="text/css"/> -->
<?=link_tag('assets/global/plugins/bootstrap-datepicker/css/datepicker3.css'); ?>

<style type="text/css">
#tbl-data input[type="text"], #tbl-data select{
	height: 25px;
	width: 50%;
	padding: 0 5px;
}

#qty-table input{
	width: 80px;
	padding: 5px;
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

.table_invoice_sj, .penjualan_data{
	font-size: 1.2em;
}

</style>

<div class="page-content">
	<div class='container'>
		<?
			$penjualan_id = '';
			$customer_id = '';
			$nama_customer = '';
			$sales_id = '';
			$nama_sales = '';

			$pembayaran_type_id = '';
			$no_faktur = '';

			$tanggal_order = '';
			$ori_tanggal_order = '';
			$tanggal_invoice = date('d/m/Y');
			$ori_tanggal_invoice = '';
			
			$jatuh_tempo = '';
			$ori_jatuh_tempo = '';
			
			$diskon = 0;
			$keterangan = '';
			$no_faktur_lengkap = '';

			$g_total = 0;
			$readonly = '';
			$disabled = '';
			$status_aktif = '';
			$sj_idx = 0;
			$alamat = '';

			foreach ($penjualan_data as $row) {
				$penjualan_id = $row->id;
				$customer_id = $row->customer_id;
				$nama_customer = $row->nama_customer;
				$sales_id = $row->sales_id;
				$nama_sales = $row->nama_sales;

				$pembayaran_type_id = $row->pembayaran_type_id;
				$no_faktur = $row->no_faktur;
				
				$tanggal_order = is_reverse_date($row->tanggal_order);
				$ori_tanggal_order = $row->tanggal_order;
				$tanggal_invoice = is_reverse_date($row->tanggal_invoice);
				$ori_tanggal_invoice = $row->tanggal_invoice;

				$jatuh_tempo = is_reverse_date($row->jatuh_tempo);
				$ori_jatuh_tempo = $row->jatuh_tempo;
				

				$diskon = $row->diskon;
				$status_aktif = $row->status_aktif;
				$keterangan = $row->keterangan;
				$no_faktur_lengkap = ($no_faktur !='') ? $row->no_faktur_lengkap : '';
			}

			if ($penjualan_id == '') {
				$disabled = 'disabled';
			}

			foreach ($customer_data as $row) {
				$alamat = $row->alamat;
			}
		?>


		<div class="modal fade" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body">
						<form action="<?=base_url('transaction/penjualan_list_insert')?>" class="form-horizontal" id="form_add_data" method="post">
							<h3 class='block'> Penjualan Baru</h3>
							
							<div class="form-group">
			                    <label class="control-label col-md-3">Tanggal<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<input name="tanggal" class='form-control date-picker' value="<?=date('d/m/Y');?>"/>
			                    </div>
			                </div>			                

			                <div class="form-group">
			                    <label class="control-label col-md-3">Sales<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
	                    			<select class='form-control' name='sales_id'>
	                    				<?foreach ($this->sales_list_aktif as $row) { ?>
	                    				<!-- romani[parseInt(temp[1]) - 1]; -->
	                    					<option value="<?=$row->id;?>"><?=$row->nama;?></option>
	                    				<? } ?>
	                    			</select>
			                    </div>
			                </div>

			                <div class="form-group">
			                    <label class="control-label col-md-3">Customer<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
	                    			<select class='form-control' name='customer_id'>
	                    				<?foreach ($this->customer_list_aktif as $row) { ?>
	                    				<!-- romani[parseInt(temp[1]) - 1]; -->
	                    					<option value="<?=$row->id;?>"><?=$row->nama;?></option>
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
			</div>
			<!-- /.modal-dialog -->
		</div>
		<div class="modal fade" id="portlet-config-edit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body">
						<form action="<?=base_url('transaction/penjualan_list_update')?>" class="form-horizontal" id="form_edit_data" method="post">
							<h3 class='block'> Penjualan Baru</h3>
							
							<div class="form-group">
			                    <label class="control-label col-md-3">Tanggal<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<input name="tanggal" class='form-control date-picker' value="<?=date('d/m/Y');?>"/>
			                    </div>
			                </div>			                

			                <div class="form-group">
			                    <label class="control-label col-md-3">Sales<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
	                    			<select class='form-control' name='sales_id'>
	                    				<?foreach ($this->sales_list_aktif as $row) { ?>
	                    				<!-- romani[parseInt(temp[1]) - 1]; -->
	                    					<option value="<?=$row->id;?>"><?=$row->nama;?></option>
	                    				<? } ?>
	                    			</select>
			                    </div>
			                </div>

			                <div class="form-group">
			                    <label class="control-label col-md-3">Customer<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
	                    			<select class='form-control' name='customer_id'>
	                    				<?foreach ($this->customer_list_aktif as $row) { ?>
	                    				<!-- romani[parseInt(temp[1]) - 1]; -->
	                    					<option value="<?=$row->id;?>"><?=$row->nama;?></option>
	                    				<? } ?>
	                    			</select>
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
						<form action="<?=base_url('transaction/penjualan_list_detail_insert')?>" class="form-horizontal" id="form_add_barang" method="post">
							<h3 class='block'> Tambah Barang</h3>
							
							<div class="form-group">
			                    <label class="control-label col-md-3"> Barang<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<input name='penjualan_id' value='<?=$penjualan_id;?>' hidden='hidden'>
			                    	<select name="barang_id" class='form-control input1' id='barang_id_select'>
		                				<option value=''>Pilih</option>
		                				<?foreach ($this->barang_list_aktif as $row) { ?>
			                    			<option value="<?=$row->id?>"><?=$row->nama;?></option>
			                    		<? } ?>
			                    	</select>
			                    	<select name='data_barang' hidden='hidden'>
			                    		<?foreach ($this->barang_list_aktif as $row) { ?>
			                    			<option value="<?=$row->id?>"><?=$row->nama_satuan;?></option>
			                    		<? } ?>
			                    	</select>
			                    </div>
			                </div>			                

			                <div class="form-group">
			                    <label class="control-label col-md-3">Kuantiti<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
		                    		<!-- <div class='input-group'>
			                			<span class="input-group-btn" >
											<a class='satuan'></a>
										</span>
			                    	</div> -->
			                    	<div class='input-group'>
			                    		<input type="text" class='form-control' name="qty"/>
			                    		<span class="input-group-btn" >
											<span disabled class='btn btn-md default satuan'>-</span>
										</span>
			                    	</div>
			                    </div>
			                </div> 

			                <div class="form-group">
			                    <label class="control-label col-md-3">Harga Jual<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<div class='input-group'>
			                    		<input type="text" class='amount_number form-control' name="price"/>
			                			<span class="input-group-btn" >
											<a data-toggle="popover" class='btn btn-md default btn-cek-harga' data-trigger='click' title="History Pembelian Customer" data-html="true" data-content="<div id='data-harga'>loading...</div>"><i class='fa fa-search'></i></a>
										</span>
			                    	</div>
			                    </div>
			                </div> 
				            <input name='rekap_qty' hidden='hidden'>
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
		<div class="modal fade" id="portlet-config-faktur" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body">
						<form action="#" class="form-horizontal" id="form_search_faktur" method="post">
							<h3 class='block'> Cari Faktur</h3>
							
							<div class="form-group">
			                    <label class="control-label col-md-3">No Faktur<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
									<input type="hidden" name='penjualan_id' id="search_no_faktur" class="form-control select2">
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
		<div class="modal fade" id="portlet-config-sj" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body">
						<form action="<?=base_url('transaction/penjualan_sj_link')?>" class="form-horizontal" id="form_add_sj" method="post">
							<h3 class='block'> Cari Surat Jalan</h3>
							
							<div class="form-group">
			                    <label class="control-label col-md-3">No Surat Jalan<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<input name='penjualan_id' value="<?=$penjualan_id;?>" hidden='hidden'> 
									<!-- <input type="hidden" name='barang_keluar_id' id="search_sj" class="form-control select2"> -->
									<select class='form-control' name='barang_keluar_id' id="surat_jalan_select" >
										<option value=''>Pilih..</option>
										<?foreach ($surat_jalan_list as $row) { ?>
											<option value='<?=$row->id;?>'><?=$row->surat_jalan;?></option>
										<?}?>
									</select>
			                    </div>
			                </div>	
		                </form>                
					</div>

					<div class="modal-footer">
						<button type="button" class="btn blue btn-sj-save">Select</button>
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
						<div class="actions hidden-print">
							<a href="#portlet-config" data-toggle='modal' class="btn btn-default btn-sm btn-form-add">
							<i class="fa fa-plus"></i> Penjualan Baru </a>
							<a href="#portlet-config-faktur" data-toggle='modal' class="btn btn-default btn-sm btn-form-add">
							<i class="fa fa-search"></i> Cari Faktur </a>
						</div>
					</div>
					<div class="portlet-body">
						<table style='width:100%' class='hidden-print'>
							<tr>
								<td>
									<table class='penjualan_data'>
										<tr>
											<?if ($penjualan_id != '') { ?>
												<tr>
													<td colspan='3'>
														<button href="#portlet-config-edit" data-toggle='modal' class='btn btn-xs '><i class='fa fa-edit'></i> edit</button>
													</td>
												</tr>
											<?}?>
								    		<td>Status</td>
								    		<td class='padding-rl-5'> : </td>
								    		<td class='td-isi-bold'>
								    			<?if ($status_aktif == 0 && $status_aktif != '' ) { ?>
								    				<span style='color:red'><b>TIDAK AKTIF</b></span>
								    			<?}elseif ($status_aktif == 1) {?>
								    				<span style='color:green'><b>AKTIF</b></span>
								    			<?}else{}?>
								    		</td>
								    	</tr>
								    	<tr>
								    		<td>Tanggal Order</td>
								    		<td class='padding-rl-5'> : </td>
								    		<td class='td-isi-bold'><?=($tanggal_order);?></td>
								    	</tr>
								    	<!-- <tr>
								    		<td>Customer</td>
								    		<td class='padding-rl-5'> : </td>
								    		<td class='td-isi-bold'>
								    			<?=$nama_customer;?>
								    		</td>
								    	</tr> -->
								    	<tr>
								    		<td>Sales</td>
								    		<td class='padding-rl-5'> : </td>
								    		<td class='td-isi-bold'><?=$nama_sales;?></td>
								    	</tr>
								    </table>
								</td>
								<td class='text-right' style='vertical-align:top'>
									<!-- <table class='table_invoice_sj'>
										<?if ($penjualan_id != '') { ?>
											<tr>
											</tr>
										<?}?>
										<tr>
											<td>
												
											</td>
											<td class='padding-rl-5'>:</td>
											<td>
												
											</td>
										</tr>
									</table>
									<span class='no-faktur-lengkap'> <?=$no_faktur_lengkap;?></span> -->
								</td>
							</tr>
						</table>

						<div style='border-top:1px solid #ccc;border-bottom:1px solid #ccc; text-align:center; margin:15px 0;padding:5px 0 ;'>
							<span class='text-center;' style='font-size:2em;'><b>FAKTUR PENJUALAN</b></span>
						</div>
					    
					    <table style='width:100%; margin:15px 0;'>
					    	<tr>
					    		<td style='width:65%;padding-left:5px; vertical-align:top'>
					    			<span style='font-size:1.2em;'>Kepada : </span><br/>
								    <b style='font-size:1.5em;'><?=$nama_customer?></b> <br/>
								    <b style='font-size:1.2em;'><?=$alamat;?></b><br/>
					    		</td>
					    		<td>
					    			<table>
					    				<tr>
					    					<td>Tgl. Kirim</td>
					    					<td class='padding-rl-5'> : </td>
					    					<td></td>
					    				</tr>
					    				<tr>
					    					<td>Pembayaran</td>
					    					<td class='padding-rl-5'> : </td>
					    					<td></td>
					    				</tr>
					    				<tr>
					    					<td>
					    						No. Surat Jalan
					    						<?if ($penjualan_id != '') { ?>
													<i href='#portlet-config-sj' data-toggle='modal' class='fa fa-edit btn btn-xs hidden-print'></i>
												<?}?><br/>
												<button class='btn btn-xs red btn-calculate hidden-print'><i class='fa fa-chain'></i> kalkulasi</button>
					    					</td>
					    					<td class='padding-rl-5' style='vertical-align:top' > : </td>
					    					<td style='vertical-align:top'>
					    						<div class='btn-sj-section hidden-print'>
					    							<?foreach ($surat_jalan_link as $row) { ?>
														<a href='<?=base_url().is_setting_link('stock/barang_keluar_detail').'/'.$row->barang_keluar_id;?>' onclick="window.open(this.href, 'newwindow', 'width=1250, height=650'); return false;" class='btn btn-xs default' id='sj_<?=$row->id;?>'><?=$row->surat_jalan;?> <i class='fa fa-times btn btn-xs btn-sj-remove'></i></a>
													<?
													$sj_idx++;
													}?>
					    						</div>
					    						<div class='text-sj' hidden='hidden'>
					    							<?foreach ($surat_jalan_link as $row){
					    								echo $row->surat_jalan;
					    							}?>
					    						</div>
					    					</td>
					    				</tr>
					    			</table>
					    		</td>
					    	</tr>
					    </table>

						<!-- table-striped table-bordered  -->
						<table class="table table-hover table-striped table-bordered" id="general_table">
							<thead>
								<tr>
									<th scope="col">
										NO
									</th>
									<th scope="col">
										KETERANGAN
										<?if ($penjualan_id !='' && $status_aktif == 1) {?>
											<a href="#portlet-config-detail" data-toggle='modal' class="btn btn-xs blue btn-brg-add">
											<i class="fa fa-plus"></i> </a>
										<?}?>
									</th>
									<th scope="col" class='text-center'>
										QTY
									</th>
									<th scope="col" class='text-center'>
										HARGA
									</th>
									<th scope="col" class='text-center'>
										JUMLAH
									</th>
									<th scope="col" class='hidden-print'>
										Action
									</th>
								</tr>
							</thead>
							<tbody>
								<?
								$i =1; 
								foreach ($penjualan_list_detail as $row) { ?>
									<tr>
										<td>
											<?=$i;?> 
										</td>
										<td>
											<span class='nama_barang'><?=$row->nama_barang;?></span> 
										</td>
										<td class='text-center'>
											<!-- <input name='qty' class='free-input-sm qty' value="<?=$row->qty;?>">  -->
											<input name='qty' class='qty padding-rl-5 text-right' style='width:100px; border:none;' value="<?=$row->qty;?>"> <?=$row->nama_packaging;?> <br/>
											( <?="@ ";?><span class='pengali'><?=$row->pengali;?></span> <?=$row->nama_satuan;?>  =  <span class='subtotal-qty'><?=$row->qty*$row->pengali;?></span> <?=$row->nama_satuan;?> )

										</td>
										<td class='text-right'>
											<input name='price' class='amount_number price padding-rl-5' style='width:100px;border:none;' value="<?=number_format($row->price,'0','.','.');?>"> 
										</td>
										<td class='text-right'>
											<?$subtotal = $row->qty * $row->price;
											$g_total += $subtotal;
											?>
											Rp <span class='subtotal'><?=number_format($subtotal,'0','.','.');?>,00</span> 
										</td>
										<td class='hidden-print'>
											<?if ($status_aktif == 1) { ?>
												<span class='id' hidden="hidden"><?=$row->id;?></span>
												<a class="btn-xs btn red btn-detail-remove"><i class="fa fa-times"></i> </a>
											<?}?>
										</td>
									</tr>
								<?
								$i++; 
								} ?>

								<!-- <tr class='subtotal-data'>
									<td colspan='4' class='text-right'><b>SUBTOTAL</b></td>
									<td><b class='total'><?=number_format($g_total,'0',',','.');?> </b> </td>
									<td></td>
								</tr> -->
								<!-- <tr class='subtotal-data'>
									<td colspan='4' class='text-right'><b>DISKON</b></td>
									<td>
										<b>
											<input name='diskon' <?=$readonly;?> class='input-no-border amount_number diskon' value="<?=number_format($diskon,'0',',','.');?>" /> 
										</b>
									</td>
									<td></td>
								</tr> -->
								<tr class='subtotal-data'>
									<td colspan='4' class='text-right'><b>PPN</b></td>
									<? $ppn = ($g_total)*0.1;?>
									<td class='text-right'><b>Rp </b><b class='ppn'><?=number_format($ppn,'0',',','.');?> </b> </td>
									<td class='hidden-print'></td>
								</tr>
								<tr class='subtotal-data'>
									<td colspan='4' class='text-right'><b>JUMLAH TAGIHAN</b></td>
									<td class='text-right'>
										<b>Rp </b><b class='g_total'><?=number_format($g_total + $ppn,'0',',','.');?> </b> 
									</td>
									<td class='hidden-print'></td>
								</tr>
								<!-- <tr class='subtotal-data'>
									<td colspan='7'>
										KETERANGAN : <input style='width:75%' <?=$readonly;?> class='input-no-border padding-rl-5 keterangan' name='keterangan' value="<?=$keterangan;?>">
									</td>
								</tr> -->
								<tr class='subtotal-data'>
									<td colspan='5'>
										Terbilang : <b style='font-style:italic'><?=is_number_write($g_total+$ppn);?></b>
									</td>
									<td class='hidden-print'></td>
								</tr>
								<tr>
									<td colspan='5'>
										
										<table style='width:100%;border:1px solid #fff;'>
											<tr>
												<td style='width:70%; vertical-align:top'>
													<table style='font-weight:bold'>
														<tr>
															<td colspan='3'>
																Pembayaran Transfer :
															</td>
														</tr>
														<tr>
															<td>Atas Nama</td>
															<td class='padding-rl-5'> : </td>
															<td>FREDDY S. TEDJASENDJAJA</td>
														</tr>
														<tr>
															<td>No Rek</td>
															<td class='padding-rl-5'> : </td>
															<td>157-500-7777</td>
														</tr>
														<tr>
															<td>Bank</td>
															<td class='padding-rl-5'> : </td>
															<td>BCA</td>
														</tr>
													</table>
												</td>
												<td>
													<b>Bandung, <?=date('d-M-y');?></b>
													<br/>
													<br/>
													<br/>
													<br/>
													<b>Freddy S. Tedjasendjaja</b>
												</td>
											</tr>
										</table>
									</td>
									<td class='hidden-print'></td>
								</tr>
							</tbody>
						</table>

						<hr/>
						<div>
							<!-- <button type='button' <?=$disabled;?> class='btn btn-lg red hidden-print btn-close'><i class='fa fa-save'></i> Closed </button> -->
			                <a <?=$disabled;?> class='btn btn-lg blue btn-print hidden-print'><i class='fa fa-print'></i> Print </a>
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
<script>
jQuery(document).ready(function() {

	// FormNewPenjualanDetail.init();

	$('[data-toggle="popover"]').popover();


  	$('#customer_id_select, #customer_id_select_edit, #barang_id_select, #surat_jalan_select').select2({
        allowClear: true
    });

    $('.btn-print').click(function(){
    	$('.text-sj').show();
    	window.print();
    	setTimeout(function(){
    		$('.text-sj').hide();
    	},500);
    })

    
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

   
//====================================get harga jual barang====================================

    $('#barang_id_select').change(function(){
    	var barang_id = $('#barang_id_select').val();
   		var satuan = $("#form_add_barang [name=data_barang] [value='"+barang_id+"']").text()
   		var data_st = {};
		data_st['barang_id'] = $('#form_add_barang [name=barang_id]').val();
    	data_st['customer_id'] =  "<?=$customer_id;?>";
    	var url = 'transaction/get_latest_harga';

			ajax_data_sync(url,data_st).done(function(data_respond  /*,textStatus, jqXHR*/ ){
				if (data_respond > 0) {
				$('#form_add_barang [name=price]').val(change_number_format(data_respond));
				}else{
				$('#form_add_barang [name=price]').val(change_number_format(data[1]));
				}
   		});
   		// alert(data);
		$('#form_add_barang .satuan').html(satuan);
		$('#form_add_barang [name=qty]').focus();
    });


    $('.btn-cek-harga').click(function(){
    	var data = {};
    	data['barang_id'] = $('#form_add_barang [name=barang_id]').val();
    	data['customer_id'] =  $('#customer_id_select').val();
    	var url = 'transaction/cek_history_harga';
    	if (data['barang_id'] != '') {
    		var tbl = '<table>';
	    	ajax_data_sync(url,data).done(function(data_respond  /*,textStatus, jqXHR*/ ){
	    		var isi_tbl = '';
				$.each(JSON.parse(data_respond),function(i,v){
					isi_tbl += "<tr>"+
						"<td>"+date_formatter(v.tanggal)+"</td>"+
						"<td>"+date_formatter(v.price)+"</td>"+
						"</tr>";
				});

				tbl += "</table>";
				if (isi_tbl !='') {
			    	$('#data-harga').html(tbl);			
				}else{
			    	$('#data-harga').html("no data");
				};

	   		});
    	}else{
    		$('#data-harga').html("no data");
    	}
    	
    });

    $('.btn-sj-remove').click(function(){
    	var ini = $(this).closest('span');
    	var data = $(this).closest('span').attr('id').split('_');
    	id = data[1];
    	// alert(id);
    	bootbox.confirm('Hapus link surat jalan ini dari daftar penjualan ini ?', function(respond){
    		if(respond){
    			var data = {};
    			data['id'] = id;
    			var url = 'transaction/penjualan_sj_remove';
    			ajax_data_sync(url,data).done(function(data_respond  /*,textStatus, jqXHR*/ ){
					if (data_respond == 'OK') {
						ini.remove();
					};
		   		});
    		}
    	});
    });

//====================================update harga=============================
	
	$('#general_table').on('change', '.price, .qty', function(){
		var ini = $(this).closest('tr');
		var data = {};
		// data['id'] = '<?=$penjualan_id;?>';
		data['id'] = ini.find('.id').html();
		data['column'] = $(this).attr('name');
		data['value'] = $(this).val();
		if(data['column'] == 'qty'){
			var pengali = ini.find('.pengali').html();
			var qty_pack = pengali * data['value']; 
			// alert(pengali +'*'+qty_pack);
			$('.subtotal-qty').html(qty_pack);
		}
		var url = "transaction/penjualan_list_update_data";
		ajax_data_sync(url,data).done(function(data_respond  /*,textStatus, jqXHR*/ ){
			if (data_respond == 'OK') {
				// alert('test');
				update_table();
			};
   		});
	});

//====================================diskon=============================
	
	// $('#general_table').on('change','.diskon',function(){
	// 	var data = {};
	// 	data['id'] = '<?=$penjualan_id;?>';
	// 	data['column'] = 'diskon';
	// 	data['value'] = reset_number_format($(this).val());
	// 	var url = "transaction/penjualan_update_data";
	// 	ajax_data_sync(url,data).done(function(data_respond  /*,textStatus, jqXHR*/ ){
	// 		if (data_respond == 'OK') {
	// 			// alert('test');
	// 			update_table();
	// 		};
 //   		});
	// });

//====================================modal barang=============================
	

	<?if ($status_aktif == 1) {?>
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

    $('.btn-sj-save').click(function(){
    	if ($('#surat_jalan_select').val() != '') {
    		$('#form_add_sj').submit();
    	}else{
    		alert('Mohon pilih surat jalan');
    	}
    });

    $('.btn-calculate').click(function(){
    	var sj_idx = "<?=$sj_idx;?>";
    	var penjualan_id = "<?=$penjualan_id?>";
    	if (parseInt(sj_idx) > 0) {
    		bootbox.confirm("<b style='color:red'>Peringatan !!!</b> <br/> Jika melakukan kalkulasi maka daftar barang pada halaman ini akan di hapus,<br/> kemudian system akan mengganti dengan daftar barang dari surat jalan, <br/> Yakin ? ", function(respond){
	    		if(respond){
	    			window.location.replace(baseurl+"transaction/penjualan_sj_kalkulasi/"+penjualan_id);
	    		}
	    	});
    	};
    });

//====================================btn-detail-remove=============================

	$("#general_table").on('click', '.btn-detail-remove' , function(){
		var ini = $(this).closest('tr');
		var data = {};
		data['id'] = ini.find('.id').html();
		var url = "transaction/penjualan_list_detail_remove";

		ajax_data_sync(url,data).done(function(data_respond  /*,textStatus, jqXHR*/ ){
			if (data_respond == 'OK') {
				ini.remove();
				update_table();
			};
   		});
	});
});

function update_table(){
	var total = 0;
	$('#general_table .qty').each(function(){
		var qty = $(this).val();
		var harga = $(this).closest('tr').find('.price').val();
		harga = reset_number_format(harga);
		var subtotal = qty * harga;
		total += subtotal;
		$(this).closest('tr').find('.subtotal').html(change_number_format(subtotal));
	});

	$('.total').html(change_number_format(total));

	// var diskon = reset_number_format($('.diskon').val());
	// total -= diskon;
	var ppn = total * 0.1;
	$('.ppn').html(change_number_format(ppn));
	$('.g_total').html(change_number_format(total+ppn));
}

</script>
