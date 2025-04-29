<link href="<?=base_url('assets/global/plugins/bootstrap-modal/css/bootstrap-modal.css');?>" rel="stylesheet" type="text/css"/>

<!-- <link href="<?=base_url('assets_noondev/css/bootstrap-modal-bs3patch.css');?>" rel="stylesheet" type="text/css"/> -->
<?=link_tag('assets/global/plugins/bootstrap-datepicker/css/datepicker3.css'); ?>
<link rel="stylesheet" type="text/css" href="<?=base_url('assets/global/plugins/bootstrap-select/bootstrap-select.min.css'); ?>"/>

<style type="text/css">

#form-bayar-nilai input, #form-bayar-nilai select{
	width: 300px;
	height: 30px;
	font-size: 1.1em;
}

.form-bayar-nilai-update input, .form-bayar-nilai-update select{
	width: 300px;
	height: 30px;
	font-size: 1.1em;
}

#form-get input, #form-get select{
	border:none; border-bottom:1px solid #ddd; width:200px;
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
	position: relative;
}

.urutan-giro-info{
	position: absolute;
	text-align:center;
	top: 100px;
	right: 20px;
	padding:10px;
	background: #0ff;
}

.urutan-giro-info-new{
	position: absolute;
	text-align:center;
	top: 100px;
	right: 20px;
	padding:10px;
	background: yellow;
}

@media print {

	* {
		-webkit-print-color-adjust: exact;
		print-color-adjust: exact;
	}

	.kontra-bon-header{
		display: block;
	}

	a[href]:after {
	    content: none !important;
	}

	.kontra-bon-footer{
		display: block;
	}

	iframe{
		display: none !important;
	}
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

			$g_total = 0;
			$readonly = '';
			$disabled = '';
			$tanggal_kontra = date('d/m/Y');

			foreach ($pembayaran_piutang_data as $row) {
				$pembayaran_piutang_id = $row->id;
				$pembulatan = $row->pembulatan;
				$tanggal_kontra = is_reverse_date($row->tanggal_kontra);
				
				$toko_id = $row->toko_id;
				$customer_id = $row->customer_id;
			}

			// if (is_posisi_id() == 6 ) {
			// 	$readonly = 'readonly';
			// 	$disabled = 'disabled';
			// }

			// if ($penjualan_id == '') {
			// 	$disabled = 'disabled';
			// }
		?>

		<div class="modal fade" id="portlet-config-piutang" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body">
						<b style='font-size:2em'>DAFTAR PIUTANG</b><hr/>
						<table class="table table-hover table-striped table-bordered" id="piutang_table">
							<thead>
								<tr>									
									<th scope="col">
										Nama Customer
									</th>
									<th scope="col">
										Piutang
									</th>
									<th scope="col">
										Actions
									</th>
								</tr>
							</thead>
							<tbody>
								<?foreach ($piutang_list as $row) { ?>
									<tr>
										<td>
											<?=$row->nama_customer;?>
										</td>
										<td>
											<?=number_format($row->sisa_piutang,'0',',','.');?>
										</td>
										<td>
											<!-- <a href="<?=base_url().rtrim(base64_encode('finance/piutang_list_detail'),'=').'/?customer_id='.$row->customer_id;?>" class="btn btn-xs blue"><i class='fa fa-search'></i></a> -->
											<a href="<?=base_url().rtrim(base64_encode('finance/piutang_payment_form'),'=').'/?customer_id='.$row->customer_id;?>&toko_id=<?=$row->toko_id;?>&tanggal_start=<?=$row->tanggal_start;?>&tanggal_end=<?=$row->tanggal_end;?>" class="btn btn-xs blue"> Lihat / Pelunasan</a>
											
										</td>
									</tr>
								<?}?>
							</tbody>
						</table>
						
					</div>

					<div class="modal-footer">
						<button type="button" class="btn btn-active default" data-dismiss="modal">Close</button>
					</div>
				</div>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
		</div>

		<div class="modal fade" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog ">
				<div class="modal-content">
					<div class="modal-body">
						<b style='font-size:2em'>DAFTAR DP</b><hr/>
						<form id='form-dp' action="<?=base_url('finance/pembayaran_piutang_dp_update');?>" method="POST">

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
											Saldo
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
										<input name="tanggal_transfer" value="<?=$tanggal_transfer;?>" hidden>

										<?
										$dp_bayar = 0; $saldo_dp=0;
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
													<span class='amount'><?=number_format($row->amount,'0',',','.'); $saldo_dp += $row->amount;?></span>
												</td>
												<td>
													<?$dp_bayar += $row->amount_bayar; $saldo_dp -= $row->amount_bayar;?>
													<input name="amount_<?=$row->id;?>" class='amount-bayar amount-bayar-dp amount_number' value='<?=number_format($row->amount_bayar,'0',',','.');?>' <?=($row->amount_bayar == 0 ? 'readonly' : '');?> style="width:80px">
												</td>
												<td>
													<input name="pembayaran_piutang_id" value="<?=$pembayaran_piutang_id;?>" hidden >
													<span class='id' hidden="hidden"><?=$row->id;?></span>
													<input type="checkbox" class='dp-check' <?=($row->amount_bayar != 0 ? 'checked' : '');?> >
												</td>
											</tr>
										<? } ?>
										<tr>
											<td colspan='3'></td>
											<td><b>TOTAL</b></td>
											<td><span class='dp-total' style='font-size:1.3em'><?=number_format($dp_bayar,'0',',','.');?></span></td>
											<td></td>
										</tr>

								</tbody>
							</table>
						</form>
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
		                    	<span class='print-content'></span>
		                    	<select class='form-control' id='printer-name'>
		                    		<?foreach ($printer_list as $row) {
		                    			$default_printer = (get_default_printer() == $row->id ? $row->nama : '');?>
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
					<div class="portlet-body">
						<table width='100%'>
							<tr>
								<td>
									<h1 class='kontra-bon-header' hidden><b>KONTRA BON</b></h1>
									<form action='' id='form-get' method='get'>
										<table id='tbl-form-get'>
											<tr>
												<td>Tanggal Kontra </td>
												<td class='padding-rl-5'> : </td>
												<td>
													<b>
														<input name='tanggal_kontra' class='date-picker kontra-bon' value='<?=$tanggal_kontra;?>'>
													</b>
												</td>
											</tr>
											<?if ($pembayaran_piutang_id == '') { ?>
												<tr>
													<td>Tanggal Faktur </td>
													<td class='padding-rl-5'> : </td>
													<td>
														<b>
															<input name='tanggal_start' class='date-picker' value='<?=$tanggal_start;?>'>
															s/d
															<input name='tanggal_end' class='date-picker' style='border:none; border-bottom:1px solid #ddd; width:100px;' value='<?=$tanggal_end;?>'> 
														</b>
													</td>
												</tr>

												<tr>
													<td>Status </td>
													<td class='padding-rl-5'> : </td>
													<td>
														<b>
															<select name='status_jt' style="<?=($status_jt == 1 ? 'color:red' : ''); ?>" >
																<option <?=($status_jt == 0 ? 'selected' : '' );?> value="0" >Semua</option>
																<option <?=($status_jt == 1 ? 'selected' : '' );?> value="1" >Jatuh Tempo</option>
															</select>
														</b>
													</td>
												</tr>									
											<?}?>
											<tr>
												<td>Toko </td>
												<td class='padding-rl-5'> : </td>
												<td>
													<b>
														<?if ($pembayaran_piutang_id == '') {?>
															<select <?if ($pembayaran_piutang_id != '') {?> disabled <?}?> name='toko_id' id="toko_id_select">
																<option <?=($toko_id == 0 ? "selected" : "");?> value='0'>Pilih</option>
																<?foreach ($this->toko_list_aktif as $row) { ?>
																	<option <?=($toko_id == $row->id ? "selected" : "");?> value='<?=$row->id;?>'><?=$row->nama;?></option>
																<?}?>
															</select>
														<?}else{
															foreach ($this->toko_list_aktif as $row) {
																if ($toko_id == $row->id) {
																	$nama_toko = $row->nama;
																}
															}?>
															<input value="<?=$nama_toko;?>">
														<?}?>
													</b>
												</td>
											</tr>
											<tr>
												<td> Customer </td>
												<td class='padding-rl-5'> : </td>
												<td>
													<b>
														<?if ($pembayaran_piutang_id == '') {?>
															<select name='customer_id'  <?if ($pembayaran_piutang_id != '') {?> disabled <?}?> id="customer_id_select"  style='width:250px;'>
																<option <?=($customer_id == 0 ? "selected" : "");?> value='0'>Pilih</option>
																<?foreach ($this->customer_list_aktif as $row) { 
																	if($customer_id == $row->id){
																		$nama_customer =  $row->nama;
																	}
																	?>
																	<option <?=($customer_id == $row->id ? "selected" : "");?> value='<?=$row->id;?>'><?=$row->nama;?></option>
																<?}?>
															</select>
														<?}else{
															foreach ($this->customer_list_aktif as $row) { 
																if($customer_id == $row->id){
																	$nama_customer =  $row->nama;
																}
															}
															?>
															<input value="<?=$nama_customer;?>">
															<?
														}?>
													</b>
												</td>
											</tr>
										</table>
									</form>
									<?if ($pembayaran_piutang_id == '') {?>
										<button <?if ($toko_id == '' && $customer_id == '') { ?> disabled <?}?> class='btn btn-xs default btn-form-get'><i class='fa fa-search'></i> Cari</button>
									<?}?>
								</td>
								<td class='text-right'>
									<?if ($pembayaran_piutang_id != '') {?>
										<form id="penjualan_add_form" action="<?=base_url();?>finance/piutang_list_detail_add_faktur" method="POST">
											Add Faktur : 
											<select name='penjualan_id' id="penjualan_id_select" style='width:200px; text-align:left'>
												<option value=''></option>
												<?foreach ($piutang_other as $row) {?>
													<option value='<?=$row->penjualan_id;?>??<?=$row->sisa_piutang;?>'><?=$row->no_faktur;?> : <?=number_format($row->sisa_piutang,'0',',','.');?></option>
												<?}?>
											</select> 
											<input name='pembayaran_piutang_id' value="<?=$pembayaran_piutang_id?>" hidden>
											<button type="button" class='btn btn-xs green btn-add-faktur'>Tambah</button>
										</form>
									<?}?>
								</td>
							</tr>
						</table>

					    <hr/>
						<!-- table-striped table-bordered  -->
						<?if ($pembayaran_piutang_id == '') { ?>
							<form method="post" action="<?=base_url()?>finance/pembayaran_piutang_insert" id='form-bayar'>
						<?}?>


							<input name='tanggal_kontra' value='<?=$tanggal_kontra;?>' class='kontra-bon-copy' hidden>
							<?$total_piutang = 0; $i =1; ?>
							<table class="table table-hover table-striped" id="general_table">
								<thead>
									<tr>
										<th scope="col">
											No
										</th>
										<th scope="col">
											Tanggal
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
										<th scope="col" class='hidden-print' hidden>
											Jatuh Tempo
										</th>
										<th scope="col" class='hidden-print'>
											Bayar
										</th>
										<th scope="col" style='width:150px' class='hidden-print'>
											Sisa
										</th>
										<th scope="col" class='hidden-print'>
											Action
										</th>
									</tr>
								</thead>
								<tbody style='font-size:1.1em;'>

									<?
									$i =1; $total_piutang = 0;

									foreach ($pembayaran_piutang_awal_detail as $row) { 
										$bg = 'transparent';
										if (isset($row->nilai_info) && $row->nilai_info != '') {
											$bg = '#ffaab7';

										}
										?>
										<tr style="background:<?=$bg;?>" >
											<td>
												<?=$i;?>
											</td>
											<td><?=is_reverse_date($row->tanggal)?></td>
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
											<td class='hidden-print' hidden>
												<?=is_reverse_date($row->jatuh_tempo);?>
											</td>
											<td class='hidden-print'>
												<?//=$row->penjualan_id;?>
												<input <?=$readonly;?> name='bayar_<?=$row->penjualan_id;?>' class='amount_number bayar-piutang' value="<?=number_format($row->amount,'0',',','.');?>">
											</td>
											<td class='hidden-print'>
												<?$sisa = $row->sisa_piutang - $row->amount;?>
												<span class='sisa-piutang amount_number'><?=number_format($sisa,'0',',','.');?></span>
											</td>
											<td class='hidden-print'>
												<?if ($pembayaran_piutang_id != '') { ?>
													<span class='pembayaran_piutang_detail_id' hidden><?=$row->id;?></span>
												<?}?>
												<input name='penjualan_id_<?=$row->penjualan_id;?>' hidden value="<?=$row->penjualan_id;?>"> 
												<?if ($pembayaran_piutang_id == '') {?>
													<label><input <?=$disabled;?> type='checkbox' <?if ($sisa == 0) { echo 'checked'; }?> name='status_<?=$row->penjualan_id?>' class='lunas-check amount_number' >
													lunas </label>| 
													<button <?=$disabled;?> class='btn btn-xs red btn-reset-bayar'>reset</button>
												<?}?>
											</td>
										</tr>
									<?
									$i++; 
									} 

									foreach ($pembayaran_piutang_detail as $row) { 
										$bg = 'transparent';
										if (isset($row->nilai_info) && $row->nilai_info != '') {
											$bg = '#ffaab7';
										}
										?>
										<tr style="background:<?=$bg;?>" >
											<td>
												<?=$i;?>
											</td>
											<td><?=is_reverse_date($row->tanggal)?></td>
											<td>
												<a target='_blank' href="<?=base_url().is_setting_link('transaction/penjualan_list_detail')?>?id=<?=$row->penjualan_id;?>">
													<?=$row->no_faktur;?>
												</a>
											</td>
											<td>
												<?=number_format($row->total_jual,'0',',','.');?>
											</td>
											<td>
												<?$total_piutang += $row->sisa_piutang;?>
												<?//=$row->sisa_piutang;?>
												<span class='piutang'><?=number_format($row->sisa_piutang,'0',',','.');?></span>
											</td>
											<td class='hidden-print' hidden>
												<?=is_reverse_date($row->jatuh_tempo);?>
											</td>
											<td class='hidden-print'>
												<?//=$row->penjualan_id;?>
												<input <?=$readonly;?> name='bayar_<?=$row->penjualan_id;?>' class='amount_number bayar-piutang' value="<?=number_format($row->amount,'0',',','.');?>">
											</td>
											<td class='hidden-print'>
												<?$sisa = $row->sisa_piutang - $row->amount;?>
												<span class='sisa-piutang amount_number'><?=number_format($sisa,'0',',','.');?></span>
											</td>
											<td class='hidden-print'>
												<?if ($pembayaran_piutang_id != '') { ?>
													<span class='pembayaran_piutang_detail_id' hidden><?=$row->id;?></span>
												<?}?>
												<input name='penjualan_id_<?=$row->penjualan_id;?>' id="penjualan_id_<?=$row->penjualan_id;?>" hidden value="<?=$row->penjualan_id;?>"> 
												<?if ($pembayaran_piutang_id == '') {?>
													<label><input <?=$disabled;?> type='checkbox' <?if ($sisa == 0) { echo 'checked'; }?> name='status_<?=$row->penjualan_id?>' class='lunas-check amount_number' >
													lunas </label>| 
													<button <?=$disabled;?> class='btn btn-xs red btn-reset-bayar'>reset</button>
												<?}?>
											</td>
										</tr>
									<?
									$i++; 
									} ?>

									<?if (count($pembayaran_piutang_retur) > 0) {?>
										<tr><td colspan='8' class='text-center'><small>retur</small></td></tr>
									<?}?>
									<?foreach ($pembayaran_piutang_retur as $row) { ?>
										<tr>
											<td>
												<?=$i;?>
											</td>
											<td><?=is_reverse_date($row->tanggal)?></td>
											<td>
												<a target='_blank' href="<?=base_url().is_setting_link('transaction/retur_jual_detail')?>?id=<?=$row->penjualan_id;?>">
													<?=$row->no_faktur;?>
												</a>
											</td>
											<td>
												<span style='color:red'><?=number_format($row->total_jual,'0',',','.');?></span> 
											</td>
											<td>
												<?$total_piutang -= $row->sisa_piutang;?>
												<?//=$row->sisa_piutang;?>
												<span class='piutang' style='color:red'><?=number_format($row->sisa_piutang,'0',',','.');?></span>
											</td>
											<td class='hidden-print' hidden>
												<?=is_reverse_date($row->jatuh_tempo);?>
											</td>
											<td class='hidden-print'>
												<?//=$row->penjualan_id;?>
												<input <?=$readonly;?> style='color: red' name='retur_<?=$row->penjualan_id;?>' class='amount_number bayar-piutang' value="<?=number_format($row->amount,'0',',','.');?>">
											</td>
											<td class='hidden-print'>
												<?$sisa = $row->sisa_piutang - $row->amount;?>
												<span class='sisa-piutang amount_number'><?=number_format($sisa,'0',',','.');?></span>
											</td>
											<td class='hidden-print'>
												<span class='data-status' hidden>3</span>
												<?if ($pembayaran_piutang_id != '') { ?>
													<span class='pembayaran_piutang_detail_id' hidden><?=$row->id;?></span>
												<?}?>
												<input name='penjualan_id_<?=$row->penjualan_id;?>' hidden value="<?=$row->penjualan_id;?>"> 
												<?if ($pembayaran_piutang_id == '') {?>
													<label><input <?=$disabled;?> type='checkbox' <?if ($sisa == 0) { echo 'checked'; }?> name='status_<?=$row->penjualan_id?>' class='lunas-check amount_number' >
													lunas </label>| 
													<button <?=$disabled;?> class='btn btn-xs red btn-reset-bayar'>reset</button>
												<?}?>
											</td>
										</tr>
									<?
									$i++; 
									}?>

									<?if (count($giro_tolakan_list) > 0) {?>
										<tr><td colspan='8' class='text-center' style='background:#d9e7ff'><small>GIRO TOLAKAN</small></td></tr>
										<tr style='font-size:0.9em'>
											<th scope="col">
												No
											</th>
											<th scope="col">
												Tanggal Terima
											</th>
											<th scope="col">
												No Giro
											</th>
											<th scope="col">
												Jatuh Tempo
											</th>
											<th scope="col">
												Nilai
											</th>
											<th scope="col">
												Bayar
											</th>
											<th></th>
										</tr>
									<?}
									?>
									<?foreach ($giro_tolakan_list as $row) {?>
										<tr>
												<td>
													<?=$i;?>
												</td>
												<td class='hidden-print' hidden><?=is_reverse_date($row->tanggal_tolakan);?></td>
												<td><?=is_reverse_date($row->tanggal_transfer);?></td>
												<td>
													<?=$row->no_giro;?><br/>
													<?=$row->nama_bank;?><br/>
													<?=$row->no_rek_bank;?>
												</td>
												<td><?=is_reverse_date($row->jatuh_tempo);?></td>
												<td>
													<?$total_piutang += $row->amount_giro;?>
													<span class='piutang'><?=number_format($row->amount_giro,'0',',','.');?></span>
												</td>
												<td class='hidden-print'>
													<input <?=$readonly;?> name='girotolak_<?=$row->penjualan_id;?>' class='amount_number bayar-piutang' value="<?=number_format($row->amount,'0',',','.');?>">
												</td>
												<td class='hidden-print'>
													<?$sisa = $row->sisa_piutang - $row->amount;?>
													<span class='sisa-piutang amount_number'><?=number_format($sisa,'0',',','.');?></span>
												</td>
												
												<td class='hidden-print'>
													<?if ($pembayaran_piutang_id != '') { ?>
														<span class='pembayaran_piutang_detail_id' hidden><?=$row->id;?></span>
													<?}?>
													<input name='penjualan_id_<?=$row->penjualan_id;?>' hidden value="<?=$row->penjualan_id;?>"> 
													<?if ($pembayaran_piutang_id == '') {?>
														<label><input <?=$disabled;?> type='checkbox' <?if ($sisa == 0) { echo 'checked'; }?> name='status_<?=$row->penjualan_id?>' class='lunas-check amount_number' >
														lunas </label>| 
														<button <?=$disabled;?> class='btn btn-xs red btn-reset-bayar'>reset</button>
													<?}?>
												</td>
											</tr>
										<?$i++;
									}?>

									<tr style='font-size:1.2em; border-top:2px solid #ccc;border-bottom:2px solid #ccc;'>
										<td></td>
										<td></td>
										<td>TOTAL</td>
										<td><b><span class='total_piutang'><?=number_format($total_piutang,'0',',','.');?></span></b> </td>
										<td class='hidden-print'></td>
										<td class='hidden-print'><b><span class='total_bayar amount_number'></span></b></td>
										<td class='hidden-print'><b><span class='total_sisa_piutang'><?=number_format($total_piutang,'0',',','.');?></span></b></td>
										<td class='hidden-print'>
											<?if ($pembayaran_piutang_id == '') { ?>
												<label>
												<input type='checkbox' name='check_all' id='check_all'> lunas all </label>| 
												<button class='btn btn-sm red btn-reset-form'>reset all</button>
											<?}?>
										</td>

									</tr>
								</tbody>
							</table>
							<hr class='hidden-print'/>
							<?if ($pembayaran_piutang_id != '') { ?>
								<form method="post" action="<?=base_url()?>finance/pembayaran_piutang_insert" id='form-bayar' target="_blank" class='hidden-print'>
							<?}?>
									<input name='pembayaran_piutang_id' value="<?=$pembayaran_piutang_id;?>" hidden>
									<input name='customer_id' value="<?=$customer_id;?>" hidden>
									<input name='toko_id' value="<?=$toko_id;?>" hidden>

								</form>

							<table width='100%' class='hidden-print'>
								<tr>
									<td style='vertical-align:top;'  width='45%'>
										<?if ($saldo_dp > 0) {?>
											<div class="note note-warning" style='font-size:1.3em'><?=$nama_customer?> memiliki saldo DP <b><?=number_format($saldo_dp,"0",',','.');?></b></div>
										<?}?>
										<?
										$idx = 1; $total_bayar_piutang = 0;
										if ($pembayaran_piutang_id != '') { ?>
										<div class='list-group'>
											<?$title = '-';
											foreach ($pembayaran_piutang_nilai as $row) { 
												//disabled giro tolakan
												$disabled_gt = ($row->giro_tolakan_id != '' ? 'disabled' : '' );
												$pembayaran_piutang_detail_id_list = explode(',', $row->pembayaran_piutang_detail_id);
												$pembayaran_piutang_detail_id_list = array_flip($pembayaran_piutang_detail_id_list);
												$pernah_bayar = true;
												if ($row->pembayaran_type_id == 1) { $title = 'TRANSFER'; }
													elseif ($row->pembayaran_type_id == 2) { $title = 'GIRO'; }
														elseif ($row->pembayaran_type_id == 3) { $title = 'CASH'; }
															elseif ($row->pembayaran_type_id == 4) { $title = 'EDC'; }
																elseif ($row->pembayaran_type_id == 5) { $title = 'DP'; }
												?>
												<div class='info-bayar-div hidden-print' id='bayar-section-<?=$row->id;?>'>
													<a class='block-info-bayar' style="<?=($row->giro_tolakan_id != '' ? 'background:#ffe3e3' : '' );?>" > 
														<span style='font-size:0.8em;color:#aaa; position:absolute;left:50px;'><?=$idx;?></span> 
														<?=$title;?> <b> <?=($row->pembayaran_type_id==2 ? '['.$row->urutan_giro.']' : '')?></b>: 
														<span style='color:black'> <?=number_format($row->amount,'0',',','.');?></span><br/>
														<?if ($row->giro_tolakan_id != '' ) {?>
															<b>Ditolak : <?=is_reverse_date($row->tanggal_tolakan)?>, <?=$row->keterangan_tolakan?></b>
														<?}?>
													</a>
													<form method="post" action="<?=base_url()?>finance/pembayaran_piutang_nilai_update" id='form-bayar-nilai-update-<?=$row->id;?>' class='form-bayar-nilai-update'>
														
														<table class='bayar-info' width='100%;' style='margin-bottom:5px;' <?=($latest_pembayaran_piutang_nilai_id != $row->id ? 'hidden' : '' );?>  >
															<tr>
																<td>Jenis Pembayaran</td>
																<td class='padding-rl-5'> : </td>
																<td>
																	<?if ($row->pembayaran_type_id != 5) {?>
																		<label>
																		<input <?=$disabled_gt?> type='radio' <?if ($row->pembayaran_type_id == 1) { echo 'checked'; }?> name='pembayaran_type_id' value="1">Transfer</label>
																		<label>
																		<input <?=$disabled_gt?> type='radio' <?if ($row->pembayaran_type_id == 2) { echo 'checked'; }?> name='pembayaran_type_id' value="2">Giro</label>
																		<label>
																		<input <?=$disabled_gt?> type='radio' <?if ($row->pembayaran_type_id == 3) { echo 'checked'; }?> name='pembayaran_type_id' value="3">Cash</label>
																		<label>
																		<input <?=$disabled_gt?> type='radio' <?if ($row->pembayaran_type_id == 4) { echo 'checked'; }?> name='pembayaran_type_id' value="4">EDC</label>
																	<?}else{?>

																		<label>
																		<input <?=$disabled_gt?> type='radio' <?if ($row->pembayaran_type_id == 5) { echo 'checked'; }?> name='pembayaran_type_id' value="5">DP</label>
																	<?}?>
																</td>
															</tr>
															<?$total_bayar_piutang += $row->amount;
															if ($row->pembayaran_type_id != 5) {?>
																<tr class='tanggal-transfer'>
																	<td>Tanggal <span class='status-terima' <?if ($row->pembayaran_type_id == 1) {?> hidden <?}?> > Bayar</span> <span class='status-transfer' <?if ($row->pembayaran_type_id != 1) {?> hidden <?}?> >Transfer</span></td>
																	<td class='padding-rl-5'> : </td>
																	<td>
																		<input <?=$disabled_gt?> name='tanggal_transfer' class='date-picker' value='<?=is_reverse_date($row->tanggal_transfer);?>'>
																		<?$tanggal_transfer = is_reverse_date($row->tanggal_transfer);?>
																	</td>
																</tr>
																<tr class='nama-bank no-dp' <?=($row->pembayaran_type_id == 3 || $row->pembayaran_type_id == 5 ? 'hidden' : '');?> >
																	<td>Nama Bank</td>
																	<td class='padding-rl-5'> : </td>
																	<td>
																		<input <?=$disabled_gt?> name='nama_bank' value="<?=$row->nama_bank;?>">
																	</td>
																</tr>
																<tr class='no-rek-bank no-dp' <?=($row->pembayaran_type_id == 3 || $row->pembayaran_type_id == 5 ? 'hidden' : '');?> >
																	<td>No Rekening Bank</td>
																	<td class='padding-rl-5'> : </td>
																	<td>
																		<input <?=$disabled_gt?> name='no_rek_bank' value="<?=$row->no_rek_bank;?>">
																	</td>
																</tr>
																<tr class='jatuh-tempo no-dp' <?=($row->pembayaran_type_id != 2 || $row->pembayaran_type_id == 5 ? 'hidden' : '');?>   >
																	<td>Jatuh Tempo</td>
																	<td class='padding-rl-5'> : </td>
																	<td>
																		<input <?=$disabled_gt?> name='jatuh_tempo' class='date-picker' value='<?=is_reverse_date($row->jatuh_tempo);?>'>
																	</td>
																</tr>
																<tr class='no-giro no-dp' <?=($row->pembayaran_type_id != 2 || $row->pembayaran_type_id == 5 ? 'hidden' : '');?> >
																	<td>No GIRO</td>
																	<td class='padding-rl-5'> : </td>
																	<td>
																		<input <?=$disabled_gt?> name='no_giro' autocomplete='off' value='<?=$row->no_giro;?>'>
																	</td>
																</tr>
																<tr class='nama-penerima no-dp' <?=($row->pembayaran_type_id == 1 || $row->pembayaran_type_id == 2 || $row->pembayaran_type_id == 5 ? 'hidden' : '' ); ?> >
																	<td>Nama Penerima </td>
																	<td class='padding-rl-5'> : </td>
																	<td>
																		<input <?=$disabled_gt?> name='nama_penerima' value="<?=$row->nama_penerima;?>">
																	</td>
																</tr>
																<tr class='pembayaran-piutang-detail-id-info'>
																	<td>Info Faktur (Opt.) </td>
																	<td class='padding-rl-5'> : </td>
																	<td>
																		<select <?=$disabled_gt?> name="pembayaran_piutang_detail_id_info[]"  class="bs-select piutang-info" data-width="300px" data-style="border:1px solid #000" multiple>
																			<?foreach ($pembayaran_piutang_detail as $row2) {?>
																				<option <?=(isset($pembayaran_piutang_detail_id_list[$row2->id]) ? 'selected' : '');?> value="<?=$row2->penjualan_id;?>??<?=$row2->id?>"><?=$row2->no_faktur?></option>
																			<?}?>
																		</select>
																		<!-- <select class="bs-select form-control" multiple>
																			<option>Mustard</option>
																			<option>Ketchup</option>
																			<option>Relish</option>
																		</select> -->
																	</td>
																</tr>
																<tr>
																	<td>Nilai</td>
																	<td class='padding-rl-5'> : </td>
																	<td>
																		<?if ($row->pembayaran_type_id == 5) {?>
																			<a class='btn-dp-show'>
																				<input <?=$disabled_gt?> readonly value="<?=number_format($row->amount,'0',',','.');?>">
																			</a>
																		<?}else{?>
																			<input <?=$disabled_gt?> name='amount' class='amount_number' value="<?=number_format($row->amount,'0',',','.');?>">
																		<?}?>
																	</td>
																</tr>
																<tr>
																	<td>Keterangan</td>
																	<td class='padding-rl-5'> : </td>
																	<td>
																		<input <?=$disabled_gt?> name="keterangan" value="<?=$row->keterangan;?>">
																	</td>
																</tr>
																<tr>
																	<td></td>
																	<td></td>
																	<td class='text-right'>
																		<input name='urutan_giro' value="<?=$row->urutan_giro;?>" hidden>
																		<input name='pembayaran_piutang_id' value="<?=$pembayaran_piutang_id;?>" hidden>
																		<input name='pembayaran_piutang_nilai_id' value='<?=$row->id;?>' hidden>
																		<?if ($row->giro_tolakan_id == '' ) {?>
																			<button style='margin:5px;' id='remove-<?=$row->id;?>' class='btn btn-xs red btn-active btn-remove-nilai'>HAPUS</button>
																			<?if ($row->pembayaran_type_id != 5) {?>
																				<button style='margin:5px;' id='update-<?=$row->id;?>' class='btn btn-xs green btn-active btn-update-nilai'>SIMPAN</button>
																			<?}?>
																		<?}?>
																	</td>
																</tr>
															<?}else{?>
																<tr>
																	<td>Tanggal DP</td>
																	<td class='padding-rl-5'> : </td>
																	<td>

																		<input disabled value='<?=is_reverse_date($row->tanggal_dp);?>'>
																	</td>
																</tr>
																<tr>
																	<td>TIPE</td>
																	<td class='padding-rl-5'> : </td>
																	<td>
																		<input disabled value="<?=$row->tipe_dp;?>">
																	</td>
																</tr>
																<tr>
																	<td>NILAI</td>
																	<td class='padding-rl-5'> : </td>
																	<td>
																		<input disabled value="<?=number_format($row->amount,'0',',','.');?>">
																	</td>
																</tr>
															<?}?>
														</table>
														<?if ($row->pembayaran_type_id == 2) {?>
															<div class='urutan-giro-info' hidden>
																Urutan : <br/>
																<b style='font-size:1.5em;'><?=$row->urutan_giro;?></b>
															</div>
														<?}?>
													</form>
												</div>
											<?
											$idx++;
											}?>
											<div class='info-bayar-div' id='bayar-section'>
												<a class='block-info-bayar'><i class='fa fa-plus'></i> Tambah Bayar</a>
												<form method="post" action="<?=base_url()?>finance/pembayaran_piutang_nilai_insert" id='form-bayar-nilai'>
													<table class='bayar-info' width='100%;' style='margin-bottom:5px;' hidden>
														<tr>
															<td>Jenis Pembayaran</td>
															<td class='padding-rl-5'> : </td>
															<td>
																<label>
																<input type='radio' <?if ($pembayaran_type_id == 1 || $pembayaran_type_id =='') { echo 'checked'; }?> name='pembayaran_type_id' value="1" class='new-bayar'>Transfer</label>
																<label>
																<input type='radio' <?if ($pembayaran_type_id == 2) { echo 'checked'; }?> name='pembayaran_type_id' value="2" class='new-bayar'>Giro</label>
																<label>
																<input type='radio' <?if ($pembayaran_type_id == 3) { echo 'checked'; }?> name='pembayaran_type_id' value="3" class='new-bayar'>Cash</label>
																<label>
																<input type='radio' <?if ($pembayaran_type_id == 4) { echo 'checked'; }?> name='pembayaran_type_id' value="4" class='new-bayar'>EDC</label>
																<label>
																<input type='radio' <?if ($pembayaran_type_id == 5) { echo 'checked'; }?> name='pembayaran_type_id' value="5" class='new-bayar'>DP</label>
															</td>
														</tr>
														<tr class='tanggal-transfer'>
															<td>Tanggal <span class='status-terima' <?if ($pembayaran_type_id == 3 || $pembayaran_type_id == 2 || $pembayaran_type_id == 4) {?> hidden <?}?> > Bayar</span> <span class='status-transfer' <?if ($pembayaran_type_id == 1) {?> hidden <?}?> >Transfer</span></td>
															<td class='padding-rl-5'> : </td>
															<td>
																<input autocomplete='off' name='tanggal_transfer' class='date-picker new-tanggal' value='<?=$tanggal_transfer;?>'>
															</td>
														</tr>
														<tr class='nama-bank no-dp' <?if ($pembayaran_type_id == 3) {?> hidden <?}?>>
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
														<tr class='no-rek-bank no-dp' <?if ($pembayaran_type_id == 3) {?> hidden <?}?>>
															<td><span class='nrk-bank'>No Rekening Bank</span></td>
															<td class='padding-rl-5'> : </td>
															<td>
																<input name='no_rek_bank' value="<?=$no_rek_bank;?>">
															</td>
														</tr>
														<tr class='no-giro no-dp' <?if ($pembayaran_type_id != 2) {?> hidden <?}?> >
															<td>No GIRO</td>
															<td class='padding-rl-5'> : </td>
															<td>
																<input name='no_giro'  autocomplete='off' value="<?=$no_giro;?>">
															</td>
														</tr>
														<tr class='jatuh-tempo no-dp' <?if ($pembayaran_type_id == 3 || $pembayaran_type_id == 1) {?> hidden <?}?> >
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
																<input name='amount' class='amount_number isian-nilai'>
																<div class='dp-btn' hidden>
																	<a class='btn btn-default btn-dp-show'>Pilih DP</a>
																</div>
															</td>
														</tr>
														<tr class='pembayaran-piutang-detail-id-info'>
															<td>Info Faktur (Opt.) </td>
															<td class='padding-rl-5'> : </td>
															<td>
																<?//print_r(expression)?>
																<select name="pembayaran_piutang_detail_id_info[]"  class="bs-select piutang-info" data-width="300px" data-style="border:1px solid #000" multiple>
																	<?foreach ($pembayaran_piutang_detail as $row2) {?>
																		<option value="<?=$row2->penjualan_id;?>??<?=$row2->id?>"><?=$row2->no_faktur?></option>
																	<?}?>
																</select>
															</td>
														</tr>
														<tr class='nama-penerima no-dp' <?if ($pembayaran_type_id == 1) {?> hidden <?}?> >
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
													<div class='urutan-giro-info-new' hidden>
														Urutan : <br/>
														<b style='font-size:1.5em;'></b>
													</div>
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
													<b style='float:left'>Rp</b> <b><input <?=($pembayaran_piutang_id == '' ? 'readonly' :'');?> name='pembulatan' class='pembulatan' value="<?=$pembulatan;?>" style='width:140px;border:none;text-align:right'></b>
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

							<table class='kontra-bon-footer' width='100%' hidden>
								<tr>
									<td width='5%'></td>
									<td width='30%'></td>
									<td width='30%'></td>
									<td width='30%'>Bandung, <?=$tanggal_kontra;?></td>
									<td width='5%'></td>
								</tr>
								<tr>
									<td width='5%'></td>
									<td width='30%'></td>
									<td width='30%'></td>
									<td width='30%' style='height:100px; border-bottom:1px solid black'></td>
									<td width='5%'></td>
								</tr>
								<tr>
									<td width='5%'></td>
									<td width='30%'></td>
									<td width='30%'></td>
									<td width='30%'><?=$nama_toko;?></td>
									<td width='5%'></td>
								</tr>

							</table>

						<hr class='hidden-print'/>
						<div>
							<?if ($pembayaran_piutang_id == '') { ?>
								<button title='double click ini untuk save' type='button' <?=($customer_id == '' ? 'disabled' : '');?> class='btn btn-lg green hidden-print btn-save-bayar'><i class='fa fa-save'></i> Simpan </button>
							<?}else {?>
				                <a href="<?=base_url().is_setting_link('finance/piutang_payment_form')?>?customer_id=<?=$customer_id?>&toko_id=<?=$toko_id?>&tanggal_start=<?=is_date_formatter($tanggal_end);?>&tanggal_end=<?=date('Y-m-d')?>" class='btn btn-lg yellow-gold hidden-print'><i class='fa fa-plus'></i> Baru </a>
							<?}?>
							<!-- onclick='window.print()'  -->
			                <a <?=($disabled == '' ? "href='#portlet-config-print' data-toggle='modal'" : '');?> class='btn btn-lg blue btn-print hidden-print print-kontra'><i class='fa fa-print'></i> Print </a>
			                <button class='btn btn-lg blue btn-print hidden-print ' onclick="window.print()" ><i class='fa fa-print'></i> Print HVS </button>
			                <?if ($pembayaran_piutang_id != '' && is_posisi_id()<3 ) {?>
				                <a class='btn btn-lg red hidden-print hidden-print btn-remove-pembayaran'><i class='fa fa-times'></i> DELETE </a>
			                <?}?>
			                <a href="#portlet-config-piutang" data-toggle='modal' style='float:right' class='btn btn-lg yellow-gold hidden-print'><i class='fa fa-plus'></i> Baru </a>
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

<script type="text/javascript" src="<?php echo base_url('assets/global/plugins/bootstrap-select/bootstrap-select.min.js'); ?>"></script>

<script src="<?php echo base_url('assets/global/plugins/datatables/media/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets_noondev/js/webprint.js'); ?>" type="text/javascript"></script>


<script>
var today = "<?=date('d/m/Y');?>";
jQuery(document).ready(function() {

	// FormNewPenjualanDetail.init();

	$('.bs-select').selectpicker({
		noneSelectedText: 'Pilih',
        iconBase: 'fa',
        tickIcon: 'fa-check'
    });

	$("#piutang_table").dataTable({});

	$('.kontra-bon').change(function(){
		$('.kontra-bon-copy').val($(this).val());
	});

	<?if ($pembayaran_piutang_id != '') {?>
		var idx = 0;
		$('.kontra-bon').change(function(){
			var data = {};
			data['id'] = "<?=$pembayaran_piutang_id;?>";
			data['tanggal_kontra'] = $(this).val();
			var url = 'finance/update_tanggal_kontra_bon';
			ajax_data_sync(url,data).done(function(data_respond  /*,textStatus, jqXHR*/ ){
				if (data_respond == 'OK') {
					if (idx == 0) {
						notific8("lime",'Tanggal Kontra Bon Updated');
					};

					if (idx == 3) {
						idx = 0;
					}else{
						idx++;
					};
				}else{
					alert("error mohon refresh dengan menekan F5");
				}
	   		});
		});
	<?}?>

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

		// alert('OK');
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
			var url = 'finance/update_bayar_piutang_detail';
			ajax_data_sync(url,data).done(function(data_respond  /*,textStatus, jqXHR*/ ){
				if (data_respond == 'OK') {
					alert('OK');
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
    
    $('.btn-save-bayar').dblclick(function(){
    	// $('#form-bayar').submit();
    	var ini = $(this);
    	var bayar_id = $('[name=pembayaran_type_id]:checked').val();
    	var total_bayar = reset_number_format($('.total_bayar').html());
    	// alert(total_bayar);

    	if (total_bayar != 0) {
	    	$('#form-bayar').submit();
	    	$(this).attr('disabled',true);    		
    	}else{
    		alert("Total Nota Tidak bisa 0");
    	};

    });

    $(document).on('click', '.block-info-bayar', function(){
			// alert($(this).next(".bayar-info").html());
			var ini = $(this).closest('div').find(".bayar-info");
			var urutan_giro = $(this).closest('div').find(".urutan-giro-info");

			ini.toggle();
			urutan_giro.toggle();
		});

    $('.new-bayar, .new-tanggal').change(function(){
    	var ini = $(this).closest('.info-bayar-div');
    	var pembayaran_type_id = ini.find('[name=pembayaran_type_id]:checked').val();
    	var tanggal = ini.find('[name=tanggal_transfer]').val();
			if (pembayaran_type_id == 2) {
				if (tanggal != '') {
					predictive_urutan_giro(ini, tanggal);
					$('.urutan-giro-info-new').show();
				};
			}else{
				ini.find('.urutan-giro-info-new').hide();
			}
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
	    	ini.find('.dp-btn').hide();

    		ini.find(".tanggal-transfer").show();
	    	ini.find('.status-transfer').show();
	    	ini.find('.nama-bank').show();
	    	ini.find('.no-rek-bank').show();
	    	ini.find('.isian-nilai').show();
	    	
    	}else if ($(this).val() == 2) {
    		ini.find(".tanggal-transfer").show();
    		ini.find('.no-giro').show();
	    	ini.find('.no-acc-giro').show();
	    	ini.find('.tanggal-giro').show();
	    	ini.find('.jatuh-tempo').show();
	    	ini.find('.status-terima').show();

	    	ini.find('.nama-bank').show();
	    	ini.find('.no-rek-bank').show();
	    	ini.find('.isian-nilai').show();

	    	ini.find('.status-transfer').hide();
	    	ini.find('.nama-penerima').hide();
	    	ini.find('.dp-btn').hide();

    	}else if ($(this).val() == 3) {
    		ini.find(".tanggal-transfer").show();
    		ini.find('.nama-penerima').show();
	    	ini.find('.tanggal-giro').hide();
	    	ini.find('.no-acc-giro').hide();
	    	ini.find('.jatuh-tempo').hide();
	    	ini.find('.nama-bank').hide();
	    	ini.find('.no-rek-bank').hide();
	    	ini.find('.status-terima').show();
	    	ini.find('.isian-nilai').show();

	    	ini.find('.no-giro').hide();
	    	ini.find('.dp-btn').hide();

	    	ini.find('.status-transfer').hide();
    	}else if ($(this).val() == 4) {
    		ini.find(".tanggal-transfer").show();
    		ini.find('.nama-penerima').hide();
	    	ini.find('.tanggal-giro').hide();
	    	ini.find('.no-acc-giro').hide();
	    	ini.find('.jatuh-tempo').hide();
	    	ini.find('.nama-bank').hide();
	    	ini.find('.no-rek-bank').hide();

	    	ini.find('.status-terima').show();
	    	ini.find('.isian-nilai').show();

	    	ini.find('.no-giro').hide();
	    	ini.find('.dp-btn').hide();


	    	ini.find('.status-transfer').hide();
    	}else if ($(this).val() == 5) {
    		ini.find("[name='tanggal_transfer']").val(today).hide();
    		ini.find(".tanggal-transfer").hide();
    		ini.find('.no-dp').hide();
	    	ini.find('.dp-btn').show();
	    	ini.find('.isian-nilai').hide();
    	};

    	if($(this).val() == 5){
    		$('.btn-save-nilai').hide();
    	}else{
    		$('.btn-save-nilai').show();	
    	}
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
				window.location.replace(baseurl+"finance/pembayaran_piutang_nilai_delete?id="+id+"&pembayaran_piutang_id="+pembayaran_piutang_id);
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
			data['kolom'] = 'pembulatan';
			data['nilai'] = $(this).val();
			var url = 'finance/update_pembulatan_piutang';
			ajax_data_sync(url,data).done(function(data_respond  /*,textStatus, jqXHR*/ ){
				if (data_respond == 'OK') {				
					update_total_bayar();
				}else{
					alert("error mohon refresh dengan menekan F5");
				}
	   		});
		});
	<?}?>

//=====================================bayar dp=========================================

		$('.btn-dp-show').click(function(){
			let ini = $(this).closest('.bayar-info');
			let tanggal = ini.find('[name=tanggal_transfer]').val();
			// alert(tanggal);
			if (tanggal != '') {
				$("#form-dp [name=tanggal_transfer]").val(tanggal);
				$("#form-dp .testing").html(tanggal);
				// alert($('#form-dp [name=tanggal]').val());
				$('#portlet-config').modal('toggle');
			}else{
				alert("isi tanggal terlebih dahulu");
			}
		});

		$('#dp_list_table').on('change','.dp-check', function(){
			let ini = $(this).closest('tr');
			// alert($(this).is(':checked'));
			if($(this).is(':checked')){
				let dp_nilai = reset_number_format(ini.find('.amount').html());
				let bayar = reset_number_format($(".total_nilai_bayar").html());
				let bon = reset_number_format($('.total_bayar').html());
				let pembulatan = reset_number_format($('.pembulatan').html());
				let selisih = bon - (bayar+pembulatan);
				if (selisih < dp_nilai) {
					dp_nilai = selisih;
				};
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
			let amount_dp = reset_number_format(ini.find('.amount').html());
			if ($(this).val() > amount_dp) {
				$(this).val(amount_dp);
				alert("dana dp tidak cukup");
			}else{
				dp_table_update();
			}
		});
		
		$('.btn-save-dp').click(function(){
			$('#form-dp').submit();
		});

		<?if ($pembayaran_piutang_id != '' && is_posisi_id() < 3) {?>
			$(".btn-remove-pembayaran").click(function(){
				var id = "<?=$pembayaran_piutang_id?>";
				bootbox.confirm("Hapus seluruh data pembayaran ini pada form ini ? ", function(respond){
					window.location.replace(baseurl+"finance/pembayaran_piutang_remove?pembayaran_piutang_id="+id);
				});
			});
		<?}?>

	<?if ($pembayaran_piutang_id != '') {?>
		// webprint = new WebPrint(true, {
	    //     relayHost: "127.0.0.1",		
	    //     relayPort: "8080",
	    //     readyCallback: function(){
        //     }
	    // });

		// $(".btn-print-action").click(function(){
		// 	var selected = $('#printer-name').val();

		// 	var printer_name = $("#printer-name [value='"+selected+"']").text();
		// 	printer_name = $.trim(printer_name);
		// 	// alert(printer_name);
		// 	kontra_bon(printer_name);
		// });
	<?}?>

//==============================add faktur================================

	$(".btn-add-faktur").click(function(){
		if ($('#penjualan_id_select').val() != '' ) {
			$('#penjualan_add_form').submit();
		}else{
			alert("no faktur tidak boleh kosong");
		}
	});

//================================piutang-info=================================
	
	$(".piutang-info").change(function(){
		var info_get = $(this).val();
		var ini = $(this).closest('table');
		var total = 0;
		console.log(info_get);
		$.each(info_get, function(i,v){
			console.log[v];
			var info = v.split("??");
			console.log(info);
			var penjualan_id = info[0];
			var bayar = $("#general_table").find('#penjualan_id_'+penjualan_id).closest('tr').find('.bayar-piutang').val();
			bayar = reset_number_format(bayar);
			total+=parseInt(bayar);
		});

		if (total != 0) { total = change_number_format(total) };
		ini.find("[name=amount]").val(total);
		// .split('??');
		// var penjualan_id = info[0];
		// console.log($("#general_table").find(".penjualan_id_"+penjualan_id).closest('tr').html());

	});

});

function predictive_urutan_giro(ini, tanggal){
	var data = {};
	var url = 'finance/predictive_urutan_giro';
	data['tanggal'] = tanggal;
	ajax_data_sync(url,data).done(function(data_respond  /*,textStatus, jqXHR*/ ){
		$('.urutan-giro-info-new b').html(data_respond);
	});
}

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
		var ini = $(this).closest('tr');
		var bayar = reset_number_format($(this).val());
		// alert(bayar+'=='+ini.find('.data-status').html());
		if (bayar == '') {
			bayar = 0;
		};

		if (ini.find('.data-status').html() == '3') {
			total_bayar -= parseInt(bayar);
		}else{
			total_bayar += parseInt(bayar);
		};
	});

	$('#general_table .sisa-piutang').each(function(){
		var ini = $(this).closest('tr');
		var sisa = reset_number_format($(this).html());
		if (sisa == '') {
			sisa = 0;
		};

		if (ini.find('.data-status').html() == '3') {
			total_piutang -= parseInt(sisa);
		}else{
			total_piutang += parseInt(sisa);
		};

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
    	
    	if (tanggal_transfer != '') {
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

<?

if ($pembayaran_piutang_id != '') {

	foreach ($toko_data as $row) {
		$nama_toko = trim($row->nama);
		$alamat_toko = trim($row->alamat.' '.$row->kota);
		$telepon = trim($row->telepon);
		$fax = trim($row->fax);
		$npwp = trim($row->NPWP);
		$kota = trim($row->kota);

	}

	foreach ($customer_data as $row) {
		$nama_customer = $row->nama;
		$alamat_customer = $row->alamat;
	}
	$alamat1 = substr(strtoupper(trim($alamat_customer)), 0,47);
   	$alamat2 = substr(strtoupper(trim($alamat_customer)), 47);
	$last_1 = substr($alamat1, -1,1);
	$last_2 = substr($alamat2, 0,1);

	$positions = array();
	$pos = -1;
	while (($pos = strpos(trim($alamat_customer)," ", $pos+1 )) !== false) {
		$positions[] = $pos;
	}

	$max = 47;
	if ($last_1 != '' && $last_2 != '') {
		$posisi =array_filter(array_reverse($positions),
			function($value) use ($max) {
				return $value <= $max;
			});

		$posisi = array_values($posisi);

		$alamat1 = substr(strtoupper(trim($alamat_customer)), 0,$posisi[0]);
	   	$alamat2 = substr(strtoupper(trim($alamat_customer)), $posisi[0]);
	}


	include 'print_kontra_bon.php';
}

?>
