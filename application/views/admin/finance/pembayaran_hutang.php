<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css'); ?>"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css'); ?>"/>
<?php echo link_tag('assets/global/plugins/bootstrap-datepicker/css/datepicker3.css'); ?>

<style type="text/css">
.rincian-bayar{
	/*background: #eee;*/
	padding:10px;
	border-top: 2px solid #000;
}

.rincian-highlight{
	background-color:rgba(200,200,200,0.2);
}
</style>
<div class="page-content">
	<div class='container'>
	
		<div class="row margin-top-10">
			<div class="col-md-12">
				<div class="portlet light">
					<div class="portlet-title">
						<div class="caption caption-md">
							<i class="icon-bar-chart theme-font hide"></i>
							<span class="caption-subject theme-font bold uppercase"><?=$breadcrumb_small;?></span>
						</div>
					</div>
					<div class="portlet-body">
						<table width='100%'>
							<tr>
								<td>
									<form action='' method='get'>
										<table>
											<tr>
												<td>Supplier</td>
												<td class='padding-rl-5'> : </td>
												<td>
													<select name='supplier_id'>
														<option <?=($supplier_id == '' ? "selected" : "");?> value="">Pilih..</option>
														<?foreach ($this->supplier_list_aktif as $row) { ?>
															<option <?=($supplier_id == $row->id ? "selected" : "");?>  value="<?=$row->id;?>"><?=$row->nama;?></option>
														<?}?>
													</select>
												</td>
											</tr>
											<tr>
												<td>Toko</td>
												<td class='padding-rl-5'> : </td>
												<td>
													<select name='toko_id'>
														<?foreach ($this->toko_list_aktif as $row) { ?>
															<option <?=($toko_id == $row->id ? "selected" : "");?>   value="<?=$row->id;?>"><?=$row->nama;?></option>
														<?}?>
													</select>
												</td>
											</tr>
											<tr>
												<td>Tanggal Pelunasan</td>
												<td class='padding-rl-5'> : </td>
												<td>
													<b>
														<input name='tanggal_start' readonly class='date-picker' style='border:none; border-bottom:1px solid #ddd; width:100px;' value='<?=$tanggal_start;?>'>
														s/d
														<input name='tanggal_end' readonly class='date-picker2 ' style='border:none; border-bottom:1px solid #ddd; width:100px;' value='<?=$tanggal_end;?>'> 
														<button class='btn btn-xs default'><i class='fa fa-search'></i></button>
													</b>
												</td>
											</tr>
										</table>
									</form>
								</td>
								<td style='text-align:right'>
									<?if (is_posisi_id() == 1) {?>
										<a class='btn btn-lg green' href="<?=base_url().is_setting_link('finance/hutang_payment');?>?view_type=2">Last 10</a>
									<?}?>
								</td>
							</tr>
						</table>

						<hr/>
						
						<?if ($view_type == 1) {
							# code...
							$stat = array();
							$idx_e = 1;
							foreach ($pembayaran_hutang_list as $baris) { ?>
							<div class="rincian-bayar <?=( $idx_e % 2 == 1 ?"rincian-highlight" : "" )?> ">
								<h4>
									<table width='100%'>
										<tr>
											<td>
												<table>
												<tr>
													<td>Tanggal Pembuatan</td>
													<td style='padding: 0 10px;'> : </td>
													<td><b><?=(isset($baris->tanggal_bayar) ?  date('d/m/Y',strtotime($baris->tanggal_bayar)) :'' ) ;?></b> </td>
												</tr>
												<tr>
													<td>Supplier</td>
													<td style='padding: 0 10px;'> : </td>
													<td><b><?=$baris->nama_supplier;?></b> </td>
												</tr>
												<tr>
													<td>Toko</td>
													<td style='padding: 0 10px;'> : </td>
													<td><b><?=$baris->nama_toko;?></b> </td>
												</tr>											
												<tr>
													<td>Periode</td>
													<td style='padding: 0 10px;'> : </td>
													<td>
														<b><?=$periode[$baris->id]['tanggal_start'];?></b> 
														s/d
														<b><?=$periode[$baris->id]['tanggal_end'];?></b> 
													</td>
												</tr>
												<tr>
													<td>Nilai</td>
													<td style='padding: 0 10px;'> : </td>
													<td><b><?=number_format($baris->amount_bayar,'0',',','.');?></b> </td>
												</tr>
												<?if (is_posisi_id() == 1) {?>
													<tr>
														<td>ID</td>
														<td style='padding: 0 10px;'> : </td>
														<td>
															<?=$baris->id?>
														</td>
													</tr>
												<?}?>
											</table>
											</td>
											<td class='text-right'>
												<span class='unbalance_<?=$baris->id;?>' hidden style='color:red; margin-right:20px;'><b>UNBALANCED</b></span>
												<button id='button_<?=$baris->id;?>' class='btn btn-md yellow-gold btn-view'>VIEW <i class='fa fa-sort-down'></i></button>
												<a href="<?=base_url().is_setting_link('finance/hutang_payment_form')?>?id=<?=$baris->id;?>" class='btn btn-md green'>EDIT</a>
												<?if (is_posisi_id() < 4) {?>
												<span class='pembayaran_hutang_id' hidden><?=$baris->id?></span>
													<button class='btn btn-md red btn-remove'>DEL</a>
												<?}?>
											</td>
										</tr>
									</table>
									</h4>

									<?
									$hidden ='';
									if (isset($status_view) && $status_view == 0) { $hidden='hidden'; }?>

									<div id='rinci_<?=$baris->id?>' <?=$hidden;?>>
										<table class="table table-hover table-striped table-bordered" id="tbl-<?=$baris->id?>">
											<thead>
												<tr <?=$hidden?> >		
													<th scope="col">
														Tanggal Pembelian
													</th>
													<th scope="col">
														No Faktur <button class="btn btn-xs default" onclick="toggleFakturList('<?=$baris->id?>')"><i class='fa fa-caret-down arrow-down' ></i> <i class='fa fa-caret-up arrow-up' style='display:none'></i> Daftar Faktur</button>
													</th>
													<th scope="col">
														Nilai
													</th>
													<!-- <th scope="col">
														Actions
													</th> -->
												</tr>
											</thead>
											<tbody style='display:none'>
												<?
												$total = 0; $subtotal_bayar = 0; $idx = 0;
												foreach ($pembayaran_hutang_awal_detail[$baris->id] as $row) {$idx++; ?>
													<tr style="<?=(($idx)% 5 == 0 ? "border-bottom:2px solid #888" :"")?> ">
														<td>
															<?=is_reverse_date($row->tanggal);?>
														</td>
														<td>
															<?=$row->no_faktur;?>
														</td>
														<td>
															<?=number_format($row->sisa_hutang,'0',',','.');
															$total += $row->sisa_hutang;
															?>
														</td>
													</tr>
												<?}

												foreach ($pembayaran_hutang_detail[$baris->id] as $row) {$idx++; ?>
													<tr style="<?=(($idx)% 5 == 0 ? "border-bottom:2px solid #888" :"")?> ">
														<td>
															<?=is_reverse_date($row->tanggal);?>
														</td>
														<td>
															<?=$row->no_faktur;?>
														</td>
														<td>
															<?=number_format($row->amount,'0',',','.');
															$total += $row->amount;
															?>
														</td>
													</tr>
												<?}?>
												<?foreach ($pembayaran_hutang_retur[$baris->id] as $row) { $idx++; ?>
													<tr style="background:#ffffa8; <?=(($idx)% 5 == 0 ? "border-bottom:2px solid #888" :"")?> ">
														<td>
															<?=is_reverse_date($row->tanggal);?>(retur)
														</td>
														<td>
															<?=$row->no_sj_lengkap;?>
														</td>
														<td>
															<?=number_format($row->amount,'0',',','.');
															$total -= $row->amount;
															?>
														</td>
													</tr>
												<?}?>
											</tbody>
											<tfoot>
												<?$brdr = "border:none;";?>
												<tr style='font-size:1.2em; border-top:2px solid #ccc;border-top:2px solid #777'>
													<td style="<?=$brdr?>;"><b><?=count($pembayaran_hutang_awal_detail[$baris->id]) + count($pembayaran_hutang_detail[$baris->id]); + count($pembayaran_hutang_retur[$baris->id]);?> FAKTUR </b></td>
													<td style="<?=$brdr?>"><b>TOTAL</b></td>
													<td style="<?=$brdr?>"><b><?=number_format($total,'0',',','.');?></b></td>
												</tr>
												<?foreach ($pembayaran_hutang_nilai[$baris->id] as $row) { 
													$subtotal_bayar += $row->amount;
													
													?>
													<?if ($row->pembayaran_type_id == 1) { ?>
														<tr style='font-size:1.1em'>
															<td style="<?=$brdr?>"></td>
															<td> <b>Transfer</b> </td>
															<td> <b><?=number_format($row->amount,'0',',','.');?></b> </td>
														</tr>
													<?}elseif ($row->pembayaran_type_id == 2) { ?>
														<tr style='font-size:1.1em; border-top:0px'>
															<td style="<?=$brdr?>"></td>
															<td style="<?=$brdr?>"> <b>GIRO</b> </td>
															<td style="<?=$brdr?>"> <b><?=number_format($row->amount,'0',',','.');?></b> </td>
														</tr>
													<?}elseif ($row->pembayaran_type_id == 3) { ?>
														<tr style='font-size:1.1em; border-top:0px'>
															<td style="<?=$brdr?>"></td>
															<td style="<?=$brdr?>"> <b>CASH</b> </td>
															<td style="<?=$brdr?>"> <b><?=number_format($row->amount,'0',',','.');?></b> </td>
														</tr>
													<?}?>
												<?}?>

												<?if ($baris->pembulatan != 0) { ?>
													<tr>
														<td style="<?=$brdr;?>" ></td>
														<td style="<?=$brdr;?>" > <b>Pembulatan</b> </td>
														<td style="<?=$brdr;?>" > <b><?=number_format($baris->pembulatan,'0',',','.');?></b> </td>
													</tr>
												<?}?>

												<!-- background:#d5d5f7 -->
												<tr  style='font-size:1.1em; ;'>
													<td style="<?=$brdr;?>" > <?$sisa_hutang=$total - ($subtotal_bayar + $baris->pembulatan);?></td>
													<td style="<?=$brdr;?>" > <b>SISA</b> </td>
													<td style="<?=$brdr;?>" > <b><?=number_format($total - ($subtotal_bayar + $baris->pembulatan),'0',',','.');?></b> </td>
												</tr>
												<?if($sisa_hutang != 0) {
													$stat[$baris->id] = $baris->id;
													$idx++;
												}?>
											</tfoot>
										</table>

										<table>
											<tr>
											<?
											$idx = 1;
											if (is_posisi_id()==1) {
												// print_r($pembayaran_hutang_nilai[$baris->id]);
												// echo count($pembayaran_hutang_nilai[$baris->id]);
											}

											foreach ($pembayaran_hutang_nilai[$baris->id] as $row) { 
												$subtotal_bayar += $row->amount;
												?>
												<td style="width:33.3%">
													<table>
														<?if ($row->pembayaran_type_id == 1) { ?>
															<tr>
																<td rowspan='6' style='vertical-align:top; font-size:1.2em; padding: 0 10px 0 0'>
																	<b><?=$idx?>. </b> 
																</td>
																<td> <b>Tipe </b> </td>
																<td> : </td>
																<td> <b>Transfer</b> </td>
															</tr>
															<tr style='font-size:1.1em;'>
																<td> <b>Tanggal Transfer </b> </td>
																<td> : </td>
																<td> <b><?=date('d/m/Y', strtotime($row->tanggal_transfer));?></b> </td>
															</tr>
															<tr style='font-size:1.1em;'>
																<td> <b>Bank</b> </td>
																<td> : </td>
																<td> <b><?=$row->nama_bank;?></b> </td>
															</tr>
															<tr style='font-size:1.1em;'>
																<td> <b>No Rek</b> </td>
																<td> : </td>
																<td> <b><?=$row->no_rek_bank;?></b> </td>
															</tr>
															<tr style='font-size:1.1em;'>
																<td><b>Nilai</b></td>
																<td> : </td>
																<td> <b><?=number_format($row->amount,'0',',','.');?></b> </td>
															</tr>
																		
														<?}elseif ($row->pembayaran_type_id == 2) { ?>
															<tr>
																<td rowspan='8' style='vertical-align:top; font-size:1.2em; padding: 0 10px 0 0'>
																	<b><?=$idx?>. </b> 
																</td>
																<td> <b>Tipe </b> </td>
																<td> : </td>
																<td> <b>Giro</b> </td>
															</tr>
															<tr style='font-size:1.1em;'>
																<td> <b>Tanggal Bayar</b> </td>
																<td> : </td>
																<td> <b><?=date('d/m/Y', strtotime($row->tanggal_transfer));?></b> </td>
															</tr>
															<tr style='font-size:1.1em;'>
																<td> <b>Bank</b> </td>
																<td> : </td>
																<td> <b><?=$row->nama_bank;?></b> </td>
															</tr>
															<tr style='font-size:1.1em;'>
																<td> <b>No GIRO</b> </td>
																<td> : </td>
																<td> <b><?=$row->no_giro;?></b> </td>
															</tr>
															<tr style='font-size:1.1em;'>
																<td> <b>No Rek</b> </td>
																<td> : </td>
																<td> <b><?=$row->no_rek_bank;?></b> </td>
															</tr>
															<tr style='font-size:1.1em;'>
																<td> <b>Jatuh tempo</b> </td>
																<td> : </td>
																<td> <b><?=is_reverse_date($row->jatuh_tempo);?></b> </td>
															</tr>
															<tr style='font-size:1.1em;'>
																<td> <b>Nilai</b> </td>
																<td> : </td>
																<td> <b><?=number_format($row->amount,'0',',','.');?></b> </td>
															</tr>
														<?}elseif ($row->pembayaran_type_id == 3) { ?>
															<tr>
																<td rowspan='4' style='vertical-align:top; font-size:1.2em; padding: 0 10px 0 0'>
																	<b><?=$idx?>. </b> 
																</td>
																<td> <b>Tipe </b> </td>
																<td> : </td>
																<td> <b>Cash</b> </td>
															</tr>
															<tr style='font-size:1.1em;'>
																<td> <b>Tanggal Bayar </b> </td>
																<td> : </td>
																<td> <b><?=is_reverse_date($row->tanggal_transfer);?></b> </td>
															</tr>
															<tr style='font-size:1.1em;'>
																<td><b>Nilai</b> </td>
																<td> : </td>
																<td> <b><?=number_format($row->amount,'0',',','.');?></b> </td>
															</tr>
														<?}?>
														<tr style='font-size:1.1em;'>
															<td><b>Ket</b></td>
															<td> : </td>
															<td><b><?=$row->keterangan;?></b></td>
														</tr>
														<tr>
															<td colspan='4'>
																<hr/>
															</td>
														</tr>
													</table>
												</td>
												
												<?$idx++;}?>
											</tr>
										</table>
									</div>
							</div>
							<?$idx_e++;}
						}else{?>
							<table class='table'>
								<tr>
									<th>No</th>
									<th>Supplier</th>
									<th>Tipe Bayar</th>
									<th>Dibuat</th>
									<th>No Giro/Cek</th>
									<th>Jatuh Tempo</th>
									<th>Nilai</th>
									<th></th>
								</tr>
								<?foreach ($pembayaran_hutang_list as $row) {
									$tipe_trx = '';
									if ($row->pembayaran_type_id == 2) {
										$tipe_trx = 1;
									}elseif ($row->pembayaran_type_id == 5) {
										$tipe_trx = 2;
									}
									?>
									<tr>
										<td><?=$row->id;?></td>
										<td><?=$row->nama_supplier;?></td>
										<td style="background-color:<?=$row->{'tipe_trx_'.$tipe_trx}?>">
											<?=($tipe_trx == 1 ? 'BG' : 'CEK');?> <?=$row->nama_bank_asal;?> : <?=$row->no_rek_bank_asal?>
										</td>
										<td><?=is_reverse_date($row->tanggal_transfer);?></td>
										<td><?=$row->no_giro;?></td>
										<td><?=($tipe_trx != '' ? is_reverse_date($row->jatuh_tempo) : '');?></td>
										<td><?=$row->amount?></td>
										<td><a target="_blank" href="<?=base_url().is_setting_link('finance/hutang_payment_form')?>/?id=<?=$row->pembayaran_hutang_id;?>" class='btn btn-xs green'><i class='fa fa-search'></i></a></td>
									</tr>
								<?}?>
							</table>
						<?}?>

					</div>
					<div style="border-top: 2px solid #000;padding:10px 0;">
						<a href="<?=base_url().is_setting_link('finance/piutang_list');?>" class='btn btn-lg default'>BACK</a>
					</div>
				</div>
			</div>
		</div>
	</div>			
</div>

<script src="<?php echo base_url('assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/datatables/media/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/bootbox/bootbox.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets_noondev/js/table-advanced.js'); ?>"></script>

<script>
jQuery(document).ready(function() {

	// dataTableTrue();

	// oTable = $('#general_table').DataTable();
	// oTable.state.clear();
	// oTable.destroy();
	// TableAdvanced.init();

	$('.btn-view').click(function(){
		var data_id = $(this).attr('id').split('_');
		var id = data_id[1];
		// alert(id);
		$("#rinci_"+id).toggle('slow');
	});

	<?foreach ( $stat as $key => $value) { ?>
		$(".unbalance_"+"<?=$value?>").show();
	<?}?>

	<?if (is_posisi_id() < 4) {?>
		$(".btn-remove").click(function(){
			var pembayaran_hutang_id = $(this).closest('tr').find('.pembayaran_hutang_id').html();
			bootbox.confirm("Yakin menghapus data pembayaran ? <br/>", function(respond){
				if (respond) {
					window.location.replace(baseurl+"finance/pembayaran_hutang_remove?pembayaran_hutang_id="+pembayaran_hutang_id);
				};
			} );
		});
	<?}?>

});

function toggleFakturList(id){
	$(`#tbl-${id}`).find('tbody').toggle();
	$(`#tbl-${id}`).find('.arrow-up, .arrow-down').toggle();
}
</script>
