<link href="<?=base_url('assets/global/plugins/bootstrap-modal/css/bootstrap-modal.css');?>" rel="stylesheet" type="text/css"/>

<!-- <link href="<?=base_url('assets_noondev/css/bootstrap-modal-bs3patch.css');?>" rel="stylesheet" type="text/css"/> -->
<?=link_tag('assets/global/plugins/bootstrap-datepicker/css/datepicker3.css'); ?>

<style type="text/css">

#form-get input, #form-get select{
	border:none; border-bottom:1px solid #ddd; width:100px;
}

#form-get select{
	border:none; border-bottom:1px solid #ddd; width:150px;
}

.block-info-bayar{
	display: block;
	background: #eee;
	padding: 10px;
	font-size: 1.2em;
	font-weight: bold;
	text-align: right;
}

.block-info-bayar:hover{
	text-decoration: none;
	font-weight: bold;
}

.info-bayar-div{
	border-bottom:1px solid #ccc;
	border-top:1px solid #ccc;
}

</style>

<div class="page-content">
	<div class='container'>
		<?
			$pembayaran_piutang_id = '';
			$pembayaran_type_id = 1;
			
			$keterangan = '';
			$tanggal_giro = '';
			$jatuh_tempo = '';
			$tanggal_transfer = '';
			$nama_bank = '';
			$no_rek_bank = '';
			$no_giro = '';
			$nama_penerima = '';
			$total_jual = 0;
			$pembulatan = 0;
			$kota='';

			$g_total = 0;
			$readonly = '';
			$disabled = '';

			foreach ($pembayaran_piutang_data as $row) {
				$pembayaran_piutang_id = $row->id;
				$pembulatan = $row->pembulatan;
				
				$toko_id = $row->toko_id;
				$customer_id = $row->customer_id;
				$nama_customer = $row->nama_customer;
				$alamat_customer = trim($row->alamat_customer);
				$kota = $row->kota;
			}

			// if (is_posisi_id() == 6 ) {
			// 	$readonly = 'readonly';
			// 	$disabled = 'disabled';
			// }

			// if ($penjualan_id == '') {
			// 	$disabled = 'disabled';
			// }
		?>

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


		<div class="row margin-top-10">
			<div class="col-md-12">
				<div class="portlet light">
					<div class="portlet-body">
						<form action='' id='form-get' method='get'>
							<table id='tbl-form-get'>
								<?if ($pembayaran_piutang_id == '') { ?>
									<tr>
										<td>Tanggal: </td>
										<td class='padding-rl-5'> : </td>
										<td>
											<b>
												<input name='tanggal_start' class='date-picker' value='<?=$tanggal_start;?>'>
												s/d
												<input name='tanggal_end' class='date-picker2 ' style='border:none; border-bottom:1px solid #ddd; width:100px;' value='<?=$tanggal_end;?>'> 
											</b>
										</td>
									</tr>
								<?}?>
								<tr>
									<td>Toko </td>
									<td class='padding-rl-5'> : </td>
									<td>
										<b>
											<select <?if ($pembayaran_piutang_id != '') {?> disabled <?}?> name='toko_id' id="toko_id_select">
												<option <?=($toko_id == 0 ? "selected" : "");?> value='0'>Pilih</option>
												<?foreach ($this->toko_list_aktif as $row) { ?>
													<option <?=($toko_id == $row->id ? "selected" : "");?> value='<?=$row->id;?>'><?=$row->nama;?></option>
												<?}?>
											</select>
										</b>
									</td>
								</tr>
								<tr>
									<td> Customer </td>
									<td class='padding-rl-5'> : </td>
									<td>
										<b>
											<select name='customer_id'  <?if ($pembayaran_piutang_id != '') {?> disabled <?}?> id="customer_id_select"  style='width:250px;'>
												<option <?=($customer_id == 0 ? "selected" : "");?> value='0'>Pilih</option>
												<?foreach ($this->customer_list_aktif as $row) { ?>
													<option <?=($customer_id == $row->id ? "selected" : "");?> value='<?=$row->id;?>'><?=$row->nama;?></option>
												<?}?>
											</select>
										</b>
									</td>
								</tr>
							</table>
						</form>
						<?if ($pembayaran_piutang_id == '') {?>
							<button <?if ($toko_id == '' && $customer_id == '') { ?> disabled <?}?> class='btn btn-xs default btn-form-get'><i class='fa fa-search'></i> Cari</button>
						<?}?>

					    <hr/>
						<!-- table-striped table-bordered  -->
						<?if ($pembayaran_piutang_id == '') { ?>
							<form method="post" action="<?=base_url()?>transaction/pembayaran_piutang_insert" id='form-bayar'>
						<?}?>

							<?$total_piutang = 0; $i =1; ?>
							<table class="table table-hover table-striped" id="general_table">
								<thead>
									<tr>
										<th scope="col">
											No
										</th>
										<th scope="col">
											No Faktur
										</th>
										<th scope="col">
											Total Faktur
										</th>
										<th scope="col">
											Total piutang
										</th>
										<th scope="col">
											Jatuh Tempo
										</th>
										<th scope="col">
											Bayar
										</th>
										<th scope="col" style='width:150px'>
											Sisa
										</th>
										<th scope="col" class='hidden-print'>
											Action
										</th>
									</tr>
								</thead>
								<tbody>

									<?
									$i =1; $total_piutang = 0;
									foreach ($pembayaran_piutang_awal_detail as $row) { ?>
										<tr>
											<td>
												<?=$i;?>
											</td>
											<td>
												<?=$row->no_faktur;?>
											</td>
											<td>
												<?=number_format($row->total_jual,'0',',','.');?>
											</td>
											<td>
												<?$total_piutang += $row->sisa_piutang;?>
												<?//=$row->sisa_piutang;?>
												<span class='piutang'><?=number_format($row->sisa_piutang,'0',',','.');?></span>
											</td>
											<td>
												<?=is_reverse_date($row->jatuh_tempo);?>
											</td>
											<td>
												<input <?=$readonly;?> name='piutang_<?=$row->penjualan_id;?>' class='amount-number bayar-piutang' value="<?=number_format($row->amount,'0',',','.');?>">
											</td>
											<td>
												<?$sisa = $row->sisa_piutang - $row->amount;?>
												<span class='sisa-piutang amount-number'><?=number_format($sisa,'0',',','.');?></span>
											</td>
											<td class='hidden-print'>
												<?if ($pembayaran_piutang_id != '') { ?>
													<span class='pembayaran_piutang_detail_id' hidden><?=$row->id;?></span>
												<?}?>
												<input name='penjualan_id_<?=$row->penjualan_id;?>' hidden value="<?=$row->penjualan_id;?>"> 
												<label><input <?=$disabled;?> type='checkbox' <?if ($sisa == 0) { echo 'checked'; }?> name='status_<?=$row->penjualan_id?>' class='lunas-check amount-number' >
												lunas </label>| 
												<button <?=$disabled;?> class='btn btn-xs red btn-reset-bayar'>reset</button>
											</td>
										</tr>
									<?
									$i++; 
									} 

									foreach ($pembayaran_piutang_detail as $row) { ?>
										<tr>
											<td>
												<?=$i;?>
											</td>
											<td>
												<?=$row->no_faktur;?>
											</td>
											<td>
												<?=number_format($row->total_jual,'0',',','.');?>
											</td>
											<td>
												<?$total_piutang += $row->sisa_piutang;?>
												<?//=$row->sisa_piutang;?>
												<span class='piutang'><?=number_format($row->sisa_piutang,'0',',','.');?></span>
											</td>
											<td>
												<?=is_reverse_date($row->jatuh_tempo);?>
											</td>
											<td>
												<input <?=$readonly;?> name='bayar_<?=$row->penjualan_id;?>' class='amount-number bayar-piutang' value="<?=number_format($row->amount,'0',',','.');?>">
											</td>
											<td>
												<?$sisa = $row->sisa_piutang - $row->amount;?>
												<span class='sisa-piutang amount-number'><?=number_format($sisa,'0',',','.');?></span>
											</td>
											<td class='hidden-print'>
												<?if ($pembayaran_piutang_id != '') { ?>
													<span class='pembayaran_piutang_detail_id' hidden><?=$row->id;?></span>
												<?}?>
												<input name='penjualan_id_<?=$row->penjualan_id;?>' hidden value="<?=$row->penjualan_id;?>"> 
												<?if ($pembayaran_piutang_id == '') {?>
													<label><input <?=$disabled;?> type='checkbox' <?if ($sisa == 0) { echo 'checked'; }?> name='status_<?=$row->penjualan_id?>' class='lunas-check amount-number' >
													lunas </label>| 
													<button <?=$disabled;?> class='btn btn-xs red btn-reset-bayar'>reset</button>
												<?}?>
											</td>
										</tr>
									<?
									$i++; 
									} ?>
									<tr style='font-size:1.2em; border-top:2px solid #ccc;border-bottom:2px solid #ccc;'>
										<td></td>
										<td></td>
										<td>TOTAL</td>
										<td><b><span class='total_piutang'><?=number_format($total_piutang,'0',',','.');?></span></b> </td>
										<td></td>
										<td><b><span class='total_bayar amount-number'></span></b></td>
										<td><b><span class='total_sisa_piutang'><?=number_format($total_piutang,'0',',','.');?></span></b></td>
										<td>
											<?if ($pembayaran_piutang_id == '') { ?>
												<label>
												<input type='checkbox' name='check_all' id='check_all'> lunas all </label>| 
												<button class='btn btn-sm red btn-reset-form'>reset all</button>
											<?}?>
										</td>

									</tr>
								</tbody>
							</table>
							<hr/>
							<?if ($pembayaran_piutang_id != '') { ?>
								<form method="post" action="<?=base_url()?>transaction/pembayaran_piutang_insert" id='form-bayar'>
							<?}?>
									<input name='pembayaran_piutang_id' value="<?=$pembayaran_piutang_id;?>" hidden>
									<input name='customer_id' value="<?=$customer_id;?>" hidden>
									<input name='toko_id' value="<?=$toko_id;?>" hidden>

								</form>

							<table width='100%'>
								<tr>
									<td style='vertical-align:top;'  width='45%'>
										
										<?
										$idx = 1; $total_bayar_piutang = 0;
										if ($pembayaran_piutang_id != '') { ?>
										<div class='list-group'>
											<?$title = '-';
											foreach ($pembayaran_piutang_nilai as $row) { 
												if ($row->pembayaran_type_id == 1) { $title = 'TRANSFER'; }
													elseif ($row->pembayaran_type_id == 2) { $title = 'GIRO'; }
														elseif ($row->pembayaran_type_id == 3) { $title = 'CASH'; }
															elseif ($row->pembayaran_type_id == 4) { $title = 'EDC'; }
												?>
												<div class='info-bayar-div'>
													<a class='block-info-bayar'> 
														<span style='font-size:0.8em;color:#aaa; position:absolute;left:50px;'><?=$idx;?></span> 
														<?=$title;?> : 
														<span style='color:black'> <?=number_format($row->amount,'0',',','.');?></span></a>
													<form method="post" action="<?=base_url()?>transaction/pembayaran_piutang_nilai_update" id='form-bayar-nilai-update-<?=$row->id;?>'>
													
														<table class='bayar-info' width='100%;' style='margin-bottom:5px;' hidden >
															<tr>
																<td>Jenis Pembayaran</td>
																<td class='padding-rl-5'> : </td>
																<td>
																	<label>
																	<input type='radio' <?if ($row->pembayaran_type_id == 1) { echo 'checked'; }?> name='pembayaran_type_id' value="1">Transfer</label>
																	<label>
																	<input type='radio' <?if ($row->pembayaran_type_id == 2) { echo 'checked'; }?> name='pembayaran_type_id' value="2">Giro</label>
																	<label>
																	<input type='radio' <?if ($row->pembayaran_type_id == 3) { echo 'checked'; }?> name='pembayaran_type_id' value="3">Cash</label>
																	<label>
																	<input type='radio' <?if ($row->pembayaran_type_id == 4) { echo 'checked'; }?> name='pembayaran_type_id' value="4">EDC</label>
																</td>
															</tr>
															<tr class='tanggal-transfer'>
																<td>Tanggal <span class='status-terima' <?if ($row->pembayaran_type_id == 3 || $row->pembayaran_type_id == 2 || $row->pembayaran_type_id == 4) {?> hidden <?}?> > Bayar</span> <span class='status-transfer' <?if ($row->pembayaran_type_id == 1) {?> hidden <?}?> >Transfer</span></td>
																<td class='padding-rl-5'> : </td>
																<td>
																	<input name='tanggal_transfer' class='date-picker' value='<?=is_reverse_date($row->tanggal_transfer);?>'>
																	<?$tanggal_transfer = is_reverse_date($row->tanggal_transfer);?>
																</td>
															</tr>
															<tr class='nama-bank' <?if ($row->pembayaran_type_id == 3) {?> hidden <?}?> >
																<td>Nama Bank</td>
																<td class='padding-rl-5'> : </td>
																<td>
																	<input name='nama_bank' value="<?=$row->nama_bank;?>">
																</td>
															</tr>
															<tr class='no-rek-bank' <?if ($row->pembayaran_type_id == 3) {?> hidden <?}?> >
																<td>No Rekening Bank</td>
																<td class='padding-rl-5'> : </td>
																<td>
																	<input name='no_rek_bank' value="<?=$row->no_rek_bank;?>">
																</td>
															</tr>
															<tr class='jatuh-tempo'  hidden  >
																<td>Jatuh Tempo</td>
																<td class='padding-rl-5'> : </td>
																<td>
																	<input name='jatuh_tempo' class='date-picker' value='<?=is_reverse_date($row->jatuh_tempo);?>'>
																</td>
															</tr>
															<tr class='no-giro' <?if ($row->pembayaran_type_id != 2 ) {?> hidden <?}?> >
																<td>No GIRO</td>
																<td class='padding-rl-5'> : </td>
																<td>
																	<input name='no_giro' value='<?=$row->no_giro;?>'>
																</td>
															</tr>
															
															<tr class='nama-penerima' <?if ($row->pembayaran_type_id == 1 || $row->pembayaran_type_id == 2) {?> hidden <?}?> >
																<td>Nama Penerima </td>
																<td class='padding-rl-5'> : </td>
																<td>
																	<input name='nama_penerima' value="<?=$row->nama_penerima;?>">
																</td>
															</tr>
															<tr>
																<td>Nilai</td>
																<td class='padding-rl-5'> : </td>
																<td>
																	<?$total_bayar_piutang += $row->amount;?>
																	<input name='amount' class='amount-number' value="<?=number_format($row->amount,'0',',','.');?>">
																</td>
															</tr>
															<tr>
																<td>Keterangan</td>
																<td class='padding-rl-5'> : </td>
																<td>
																	<input name="keterangan" value="<?=$row->keterangan;?>">
																</td>
															</tr>
															<tr>
																<td></td>
																<td></td>
																<td class='text-right'>
																	<input name='pembayaran_piutang_id' value="<?=$pembayaran_piutang_id;?>" hidden>
																	<input name='pembayaran_piutang_nilai_id' value='<?=$row->id;?>' hidden>
																	<button style='margin:5px;' id='remove-<?=$row->id;?>' class='btn btn-xs red btn-active btn-remove-nilai'>HAPUS</button>
																	<button style='margin:5px;' id='update-<?=$row->id;?>' class='btn btn-xs green btn-active btn-update-nilai'>SIMPAN</button>
																</td>
															</tr>
														</table>
													</form>
												</div>
											<?
											$idx++;
											}?>
											<div class='info-bayar-div' id='bayar-section'>
												<a class='block-info-bayar'><i class='fa fa-plus'></i> Tambah Bayar</a>
												<form method="post" action="<?=base_url()?>transaction/pembayaran_piutang_nilai_insert" id='form-bayar-nilai'>
													<table class='bayar-info' width='100%;' style='margin-bottom:5px;' hidden>
														<tr>
															<td>Jenis Pembayaran</td>
															<td class='padding-rl-5'> : </td>
															<td>
																<label>
																<input type='radio' <?if ($pembayaran_type_id == 1 || $pembayaran_type_id =='') { echo 'checked'; }?> name='pembayaran_type_id' value="1">Transfer</label>
																<label>
																<input type='radio' <?if ($pembayaran_type_id == 2) { echo 'checked'; }?> name='pembayaran_type_id' value="2">Giro</label>
																<label>
																<input type='radio' <?if ($pembayaran_type_id == 3) { echo 'checked'; }?> name='pembayaran_type_id' value="3">Cash</label>
																<label>
																<input type='radio' <?if ($pembayaran_type_id == 4) { echo 'checked'; }?> name='pembayaran_type_id' value="4">EDC</label>
															</td>
														</tr>
														<tr class='tanggal-transfer'>
															<td>Tanggal <span class='status-terima' <?if ($pembayaran_type_id == 3 || $pembayaran_type_id == 2 || $pembayaran_type_id == 4) {?> hidden <?}?> > Bayar</span> <span class='status-transfer' <?if ($pembayaran_type_id == 1) {?> hidden <?}?> >Transfer</span></td>
															<td class='padding-rl-5'> : </td>
															<td>
																<input name='tanggal_transfer' class='date-picker' value='<?=$tanggal_transfer;?>'>
															</td>
														</tr>
														<tr class='nama-bank' <?if ($pembayaran_type_id == 3) {?> hidden <?}?>>
															<td>Nama Bank</td>
															<td class='padding-rl-5'> : </td>
															<td>
																<?
																$bank_list = "<table>";
																foreach ($bank_history as $row) {
																	$bank_list .= "<tr class='bank-history' style='cursor:pointer'>";
																	$bank_list .= "<td><span class='nama-bank-history'>".$row->nama_bank."</span></td>";
																	$bank_list .= "<td> : </td>";
																	$bank_list .= "<td><span class='no-rek-history'>".$row->no_rek_bank."</span></td>";
																	$bank_list .= "</tr>";
																}
																$bank_list .= "</table>";

																?>
																<a data-toggle="popover" data-trigger='click' data-html="true" data-content="<?=$bank_list;?>">
																	<input name='nama_bank' value="<?=$nama_bank;?>">
																</a>
															</td>
														</tr>
														<tr class='no-rek-bank' <?if ($pembayaran_type_id == 3) {?> hidden <?}?>>
															<td><span class='nrk-bank'>No Rekening Bank</span></td>
															<td class='padding-rl-5'> : </td>
															<td>
																<input name='no_rek_bank' value="<?=$no_rek_bank;?>">
															</td>
														</tr>
														<tr class='no-giro' <?if ($pembayaran_type_id != 2) {?> hidden <?}?> >
															<td>No GIRO</td>
															<td class='padding-rl-5'> : </td>
															<td>
																<input name='no_giro' value="<?=$no_giro;?>">
															</td>
														</tr>
														<tr class='jatuh-tempo' <?if ($pembayaran_type_id == 3 || $pembayaran_type_id == 1) {?> hidden <?}?> >
															<td>Jatuh Tempo</td>
															<td class='padding-rl-5'> : </td>
															<td>
																<input name='jatuh_tempo' class='date-picker' value='<?=$jatuh_tempo;?>'>
															</td>
														</tr>
														<tr>
															<td>Nilai</td>
															<td class='padding-rl-5'> : </td>
															<td>
																<input name='amount' class='amount-number'>
															</td>
														</tr>
														
														<tr class='nama-penerima' <?if ($pembayaran_type_id == 1) {?> hidden <?}?> >
															<td>Nama Penerima </td>
															<td class='padding-rl-5'> : </td>
															<td>
																<input name='nama_penerima' value="<?=$nama_penerima;?>">
															</td>
														</tr>
														<tr>
															<td>Keterangan</td>
															<td class='padding-rl-5'> : </td>
															<td>
																<input name="keterangan" value="<?=$keterangan;?>">
															</td>
														</tr>
														<tr>
															<td></td>
															<td></td>
															<td class='text-right'>
																<input name='pembayaran_piutang_id' value="<?=$pembayaran_piutang_id;?>" hidden>
																<button style='margin:5px;' class='btn btn-xs green btn-active btn-save-nilai'>SIMPAN</button>
															</td>
														</tr>
													</table>

												</form>
												

											</div>
												
										</div>
										<?}else{?>
											<i>
												<div class='alert alert-info'>
													<b>NOTES : </b><br/>
													1. <b>Pilih daftar nota</b> yang akan dibayar terlebih dahulu kemudian <b> klik SIMPAN</b><br>
													2. <b>Pilihan pembayaran</b> akan muncul setelah daftar nota tersimpan
												</div>
											</i> 
										<?}?>
									</td>
									<td style='vertical-align:top;font-size:2.5em;'  width='55%' class='text-right'>
										<table style='float:right;'>
											<tr style='border:2px solid #c9ddfc'>
												<td class='padding-rl-25' style='background:#c9ddfc'>BAYAR</td>
												<td class='padding-rl-10'>
													<b>Rp <span class='total_nilai_bayar' style=''><?=number_format($total_bayar_piutang,'0',',','.');?></span></b>
												</td>
											</tr>
											<tr style='border:2px solid #ffd7b5'>
												<td class='padding-rl-25' style='background:#ffd7b5'>TOTAL</td>
												<td class='text-right padding-rl-10'> 
													<b>Rp <span class='total_bayar' style=''></span></b>
												</td>
											</tr>
											<tr style='border:2px solid #ffd700'>
												<td class='padding-rl-25' style='background:#ffd700'>PEMBULATAN</td>
												<td class='text-right padding-rl-10'>
													<b style='float:left'>Rp</b> <b><input <?=($pembayaran_piutang_id == '' ? 'readonly' :'');?> name='pembulatan' value="<?=$pembulatan;?>" style='width:140px;border:none;text-align:right'></b>
												</td>
											</tr>
											<tr style='border:2px solid #ceffb4'>
												<td class='padding-rl-25' style='background:#ceffb4'>SELISIH</td>
												<td class='padding-rl-10'>
													<?
														$sisa_bayar = ($total_bayar_piutang + $pembulatan) - $total_piutang ;

														if ($sisa_bayar < 0) {
															$kembali_style = "color:red";
														}else{
															$kembali_style = "color:black";
														}
													?>
													<b>Rp <span class='selisih' style='<?=$kembali_style;?>'><?=number_format($sisa_bayar,'0',',','.'); ?></span></b>
												</td>
											</tr>
										</table>
									</td>
								</tr>
							</table>


						<hr/>
						<div>
							<?if ($pembayaran_piutang_id == '') { ?>
								<button type='button' <?=($customer_id == '' ? 'disabled' : '');?> class='btn btn-lg green hidden-print btn-save-bayar'><i class='fa fa-save'></i> Simpan </button>
							<?}?>
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
<script src="<?php echo base_url('assets_noondev/js/webprint.js'); ?>" type="text/javascript"></script>

<script src="<?php echo base_url('assets_noondev/js/form-penjualan.js'); ?>" type="text/javascript"></script>


<script>
jQuery(document).ready(function() {

	<?if($pembayaran_piutang_id != ''){?>
		webprint = new WebPrint(true, {
	        relayHost: "127.0.0.1",
	        relayPort: "8080",
	        readyCallback: function(){
	            
	        }
	    });

	    $('.btn-print').click(function(){
		$('#portlet-config-print').modal('toggle');

		});

	    $('.btn-print-action').click(function(){
			var selected = $('#printer-name').val();
			var printer_name = $("#printer-name [value='"+selected+"']").text();
			printer_name = $.trim(printer_name);
			// alert(printer_name);
			print_pembayaran_piutang(printer_name);
		});
		
	<?}?>

	// FormNewPenjualanDetail.init();

	var form_group = {};
	var idx_gen = 0;

	update_total_bayar();

	$('.btn-reset-form, .btn-reset-bayar').click(function(e){
		e.preventDefault();
	});

	$('#customer_id_select').select2();

	$('[data-toggle="popover"]').popover();

	$("#toko_id_select").change(function(){
		if ($(this).val() != 0 && $("#customer_id_select").val() != 0) {
			$('.btn-form-get').prop("disabled",false);
		}else{
			$('.btn-form-get').prop("disabled",true);
		};
	});

	$("#customer_id_select").change(function(){
		// alert($(this).val());
		if ($(this).val() != 0 && $("#toko_id_select").val() != 0) {
			$('.btn-form-get').prop('disabled',false);
		}else{
			$('.btn-form-get').prop('disabled',true);
		};
	});

	$('.btn-form-get').click(function(){
		$('#form-get').submit();
	});

	$('#general_table').on('change','.lunas-check', function(){
		var ini = $(this).closest('tr');
		if($(this).is(':checked')){
			check_bayar(ini);
		}else{
			undo_bayar(ini);
		}
		update_total_bayar();
	});

	$('#general_table').on('change','.bayar-piutang', function(){


		var ini = $(this).closest('tr');
		var piutang = reset_number_format(ini.find('.piutang').html());
		var bayar = $(this).val();
		if (bayar == '') {
			bayar = 0;
		};
		var sisa_piutang = piutang - bayar;
		// alert(piutang +'-'+ bayar);
		if (sisa_piutang == '' || sisa_piutang == 0) {
			sisa_piutang = 0;
		}else{
			sisa_piutang = change_number_format(sisa_piutang);
		}
		ini.find('.sisa-piutang').html(sisa_piutang);

		if (sisa_piutang != 0) {
			ini.find('.lunas-check').prop('checked',false);
		}else{
			ini.find('.lunas-check').prop('checked',true);
		};
		$.uniform.update('.lunas-check');


		<?if ($pembayaran_piutang_id != '') { ?>
			var data = {};
			data['id'] = ini.find('.pembayaran_piutang_detail_id').html();
			data['amount'] = reset_number_format(bayar);
			var url = 'transaction/update_bayar_piutang_detail';
			ajax_data_sync(url,data).done(function(data_respond  /*,textStatus, jqXHR*/ ){
				if (data_respond == 'OK') {
					
				}else{
					alert("error mohon refresh dengan menekan F5");
				}
	   		});
		<?};?>
		update_total_bayar();
	});

	$('#check_all').change(function(){
		if($(this).is(':checked')){
			$("#general_table .lunas-check").each(function(){
				$(this).prop('checked',true);
				var ini = $(this).closest('tr');
				check_bayar(ini);
			});
		}else{
			$("#general_table .lunas-check").each(function(){
				$(this).prop('checked',false);
				var ini = $(this).closest('tr');
				undo_bayar(ini);
			});
		}
		$.uniform.update('.lunas-check');
		update_total_bayar();
	});
    
    $('.btn-save-bayar').click(function(){
    	// $('#form-bayar').submit();
    	var ini = $(this);
    	var bayar_id = $('[name=pembayaran_type_id]:checked').val();
    	var total_bayar = reset_number_format($('.total_bayar').html());
    	// alert(total_bayar);

    	if (total_bayar != 0) {
	    	$('#form-bayar').submit();    		
    	}else{
    		alert("Total Nota Tidak bisa 0");
    	};

    });

	

    $(document).on('click', '.block-info-bayar', function(){
		// alert($(this).next(".bayar-info").html());
		var ini = $(this).closest('div').find(".bayar-info");
		ini.toggle();
	});


    $('.bayar-info').on('change', '[name=pembayaran_type_id]', function(){
    	var ini = $('.bayar-info');

    	if ($(this).val() == 1) {
	    	ini.find('.nama-penerima').hide();
	    	ini.find('.tanggal-giro').hide();
	    	ini.find('.jatuh-tempo').hide();
	    	ini.find('.no-giro').hide();
	    	ini.find('.no-acc-giro').hide();
	    	ini.find('.status-terima').hide();

	    	ini.find('.status-transfer').show();
	    	ini.find('.nama-bank').show();
	    	ini.find('.no-rek-bank').show();
	    	
    	}else if ($(this).val() == 2) {
    		ini.find('.no-giro').show();
	    	ini.find('.no-acc-giro').show();
	    	ini.find('.tanggal-giro').show();
	    	ini.find('.jatuh-tempo').show();
	    	ini.find('.status-terima').show();

	    	ini.find('.nama-bank').show();
	    	ini.find('.no-rek-bank').show();

	    	ini.find('.status-transfer').hide();
	    	ini.find('.nama-penerima').hide();

    	}else if ($(this).val() == 3) {
    		ini.find('.nama-penerima').show();
	    	ini.find('.tanggal-giro').hide();
	    	ini.find('.no-acc-giro').hide();
	    	ini.find('.jatuh-tempo').hide();
	    	ini.find('.nama-bank').hide();
	    	ini.find('.no-rek-bank').hide();
	    	ini.find('.status-terima').show();
	    	ini.find('.no-giro').hide();

	    	ini.find('.status-transfer').hide();
    	}else if ($(this).val() == 4) {
    		ini.find('.nama-penerima').hide();
	    	ini.find('.tanggal-giro').hide();
	    	ini.find('.no-acc-giro').hide();
	    	ini.find('.jatuh-tempo').hide();
	    	ini.find('.nama-bank').hide();
	    	ini.find('.no-rek-bank').hide();
	    	ini.find('.status-terima').show();
	    	ini.find('.no-giro').hide();

	    	ini.find('.status-transfer').hide();
    	};
    });

	$(".btn-save-nilai").click(function(e){
		// 
		e.preventDefault();
		var ini = $(this);
    	var form = $('#form-bayar-nilai');
    	var ini = $(this);
    	form_bayar_nilai(form, ini);
    	
	});

	$(document).on('click', '.btn-update-nilai', function(e){
		e.preventDefault();
		var ini = $(this);
		var data_id = $(this).attr('id').split('-');
		var id = data_id[1];
		var form = $("#form-bayar-nilai-update-"+id);
    	
    	form_bayar_nilai(form, ini);
	});

	$(document).on('click','.btn-remove-nilai', function(e){
		e.preventDefault();
		var ini = $(this);
		var data_id = $(this).attr('id').split('-');
		var id = data_id[1];
		var pembayaran_piutang_id = "<?=$pembayaran_piutang_id?>";
		bootbox.confirm("Yakin menghapus data pembayaran ini ?", function(respond){
			if (respond) {
				window.location.replace(baseurl+"transaction/pembayaran_piutang_nilai_delete?id="+id+"&pembayaran_piutang_id="+pembayaran_piutang_id);
			};
		});
	});

	<?if ($pembayaran_piutang_id == '') { 
		$today = date('d/m/Y');
		?>
	    $('[name=pembayaran_type_id]').change(function(){
	    	if ($(this).val() == 1) {
	    		$('#tgl-transfer').val("");
	    	}else{
	    		var tgl = "<?=$today;?>";
	    		$('#tgl-transfer').val(tgl);
	    		// alert($('[name=tanggal_transfer]').val());
	    	}

	    });

	<?};?>
//=============================================bank history click==========================

	$(document).on('click','.bank-history', function(){
		var nama_bank = $(this).find('.nama-bank-history').html();
		var rek_bank = $(this).find('.no-rek-history').html();

		var form = '#form-bayar-nilai';
		$(form+" [name=nama_bank]").val(nama_bank);
		$(form+" [name=no_rek_bank]").val(rek_bank);
		$('[data-toggle="popover"]').popover('hide');


	});

	<?if ($pembayaran_piutang_id != '') { ?>
		$("[name=pembulatan]").change(function(){
			var data = {};
			data['id'] = "<?=$pembayaran_piutang_id?>";
			data['pembulatan'] = $(this).val();
			var url = 'transaction/update_pembulatan_piutang';
			ajax_data_sync(url,data).done(function(data_respond  ,textStatus, jqXHR ){
				if (data_respond == 'OK') {				
					update_total_bayar();
				}else{
					alert("error mohon refresh dengan menekan F5");
				}
	   		});
		});
	<?}?>

});
</script>

<?if ($pembayaran_piutang_id != '') {
	include_once 'print_pembayaran_piutang.php';
}?>

<script>
function check_bayar(ini){
	var bayar = reset_number_format(ini.find('.piutang').html());
	ini.find('.bayar-piutang').val(bayar);
	ini.find('.sisa-piutang').html(0);
}

function undo_bayar(ini){
	var bayar = ini.find('.piutang').html();
	ini.find('.bayar-piutang').val(0);
	ini.find('.sisa-piutang').html(bayar);
}

function update_total_bayar(){
	var total_bayar = 0; var total_piutang = 0;
	var pembulatan = $("[name=pembulatan]").val();
	var total_nilai_bayar = reset_number_format($('.total_nilai_bayar').html());

	$('#general_table .bayar-piutang').each(function(){
		var bayar = reset_number_format($(this).val());
		if (bayar == '') {
			bayar = 0;
		};

		total_bayar += parseInt(bayar);
	});

	$('#general_table .sisa-piutang').each(function(){
		var sisa = reset_number_format($(this).html());
		if (sisa == '') {
			sisa = 0;
		};
		total_piutang += parseInt(sisa);
		// alert($(this).html());
	});
	
	var selisih = total_nilai_bayar - total_bayar + parseInt(pembulatan);
	// alert(selisih);

	$('.total_bayar').html(change_number_format(total_bayar));
	$('.total_sisa_piutang').html(change_number_format(total_piutang));
	$('.selisih').html(change_number_format(selisih));

}

function form_bayar_nilai(form, ini){

	var bayar_id = form.find('[name=pembayaran_type_id]:checked').val();
	var total_bayar = reset_number_format($('.total_bayar').html());
	var tanggal_transfer = form.find('[name=tanggal_transfer]').val();
	var no_rek_bank = form.find('[name=no_rek_bank]').val();
	var nama_bank = form.find('[name=nama_bank]').val();
	var tanggal_giro = form.find('[name=tanggal_giro]').val();
	var jatuh_tempo = form.find('[name=jatuh_tempo]').val();
	// alert(total_bayar);

	// $('#form-bayar').submit();
	if (bayar_id == 1) {
    	
    	if (tanggal_transfer != '' && no_rek_bank != '' && nama_bank != '') {
    		if (total_bayar <= 0) {
    			bootbox.alert("Jumlah total pembayaran tidak boleh 0");
	    		}else{
	    			// $('#form-bayar-nilai').submit();
	    			form.submit();
	    			btn_disabled_load(ini);
	    			// $(this).prop('disabled',true);
	    		};
	    	}else{
	    		bootbox.alert("Mohon lengkapi data pembayaran");
	    	}
	    	
    	}else if ( bayar_id == 2) {
    		
    		if (tanggal_transfer != '' && no_rek_bank != '' && nama_bank != '' && jatuh_tempo != ''  ) {
	    		if (total_bayar <= 0) {
	    			bootbox.alert("Jumlah total pembayaran tidak boleh 0");
	    		}else{
	    			form.submit();
	    			btn_disabled_load(ini);
	    		};
	    	}else{
	    		bootbox.alert("Mohon lengkapi data pembayaran");
	    	}

    	}else if (bayar_id ==  3) {
    		if ( tanggal_transfer != '' ) {
	    		if (total_bayar <= 0) {
	    			bootbox.alert("Jumlah total pembayaran tidak boleh 0");
	    		}else{
	    			// $('#form-bayar-nilai').submit();
	    			form.submit();
	    			btn_disabled_load(ini);
	    		};
	    	}else{
	    		bootbox.alert("Mohon lengkapi data pembayaran");
	    	}
    	}else if (bayar_id ==  4) {
    		if ( tanggal_transfer != '' ) {
	    		if (total_bayar <= 0) {
	    			bootbox.alert("Jumlah total pembayaran tidak boleh 0");
	    		}else{
	    			// $('#form-bayar-nilai').submit();
	    			form.submit();
	    			btn_disabled_load(ini);
	    		};
	    	}else{
	    		bootbox.alert("Mohon lengkapi data pembayaran");
	    	}
    	};



}


</script>
