<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css'); ?>"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css'); ?>"/>
<?=link_tag('assets/global/plugins/bootstrap-datepicker/css/datepicker3.css'); ?>

<style type="text/css">
#general_table tr th{
	vertical-align: middle;
	/*text-align: center;*/
}

#general_table tr td{
	color:#000;
}


.batal{
	background: #ccc;
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
						<div class="tools">
							<a href="" class="fullscreen">
							</a>
						</div>
					</div>
					<div class="portlet-body">

						<table width='100%'>
							<tr>
								<td>
									<form action='' method='get'>
										<table>
											<tr>
												<td>
													<table>
														<tr>
															<td>Periode</td>
															<td class='padding-rl-5'> : </td>
															<td><b>
																<input readonly name='tanggal' value="<?=$tanggal;?>" class='text-center date-picker-month' >
															</b></td>
															<td><button class='btn btn-xs default'><i class='fa fa-search'></i></button></td>
														</tr>

														<tr>
															<td>Gudang</td>
															<td class='padding-rl-5'> : </td>
															<td>
																<b>
																	<select class='form-control' name='gudang_id'>
																		<option value='0'>All + Nilai</option>
																		<?$nama_gudang = '';
																		foreach ($this->gudang_list_aktif as $row) { 
																			if ($gudang_id == $row->id) {
																				$nama_gudang = $row->nama;
																			}
																			?>
																			<option <?=($gudang_id == $row->id ? 'selected' : '');?> value='<?=$row->id?>'><?=$row->nama;?></option>
																		<?}?>
																	</select>
																</b>
															</td>
															<td></td>
														</tr>
													</table>
												</td>
												<td>
												</td>
											</tr>
										</table>
																	
									</form>
								</td>
								<td>
									
									<?if(date('Y',strtotime($tanggal)) > 2020 ){
										if ($tutup_buku_gudang_id == 0) {?>
											<div class='note note-warning'><?=$tanggal_before?> Belum tutup buku	 <?=($tanggal_last != '' ? "<br/>Terakhir:".date("F Y", strtotime($tanggal_last)) : '');?> </div>
										<?}else if($tutup_buku_gudang_id_now !=0){?>
											<div class='note note-success'>Tutup Buku: <b><?=$username?> ( <?=is_reverse_datetime($updated_now);?> )</b> </div>
										<?}else{?>
											<div class='note note-info'>Tutup Buku Available</b> </div>
										<?}
									}?>
								</td>
								<td class='text-right'>
									<a href="<?=base_url().'inventory/mutasi_persediaan_barang_excel?tanggal_start='.is_date_formatter($tanggal_start).'&tanggal_end='.is_date_formatter($tanggal_end).'&toko_id='.$toko_id.'&gudang_id='.$gudang_id;?>" class='btn btn-md green'><i class='fa fa-download'></i> EXCEL</a>
									<?if(date('Y',strtotime($tanggal)) > 2020 ){
										if ($tutup_buku_gudang_id != 0 && $tutup_buku_gudang_id_now == 0) {?>
											<button class='btn btn-md blue tutup-buku'><i class='fa fa-bookmark'></i> TUTUP BUKU STOK <?=$nama_gudang?></button>
										<?}else if($tutup_buku_gudang_id != 0){?>
											<button class='btn btn-md yellow-gold tutup-buku'><i class='fa fa-bookmark'></i> TUTUP ULANG STOK <?=$nama_gudang?></button>
										<?}
									}?>
								</td>
							</tr>
						</table>
									
						<hr/>
						<!-- table-striped table-bordered  -->
						<table class="table table-hover table-bordered" id="general_table">
							<thead>
								<tr>
									<th scope="col" style='width:150px;' rowspan='2' class='text-center'>
										Nama
									</th>
									<th hidden scope="col" rowspan='2' class='text-center'>
										Harga Sat.
									</th>
									<th scope="col" colspan='2' style='border-bottom:1px solid #ccc'>
										STOK PER
										( <?=strtoupper(date('d M Y', strtotime(is_date_formatter($tanggal_start))));?> )
									</th>
									<th colspan='2' style='border-bottom:1px solid #ccc' class='text-center'>
										PEMBELIAN
									</th>
									<th colspan='2' style='border-bottom:1px solid #ccc' class='text-center'>
										PENJUALAN
									</th>
									<th colspan='2' style='border-bottom:1px solid #ccc' class='text-center'>
										MUTASI masuk <?=$nama_gudang;?>
									</th>
									<th colspan='2' style='border-bottom:1px solid #ccc' class='text-center'>
										MUTASI keluar <?=$nama_gudang;?>
									</th>
									<th colspan='2' style='border-bottom:1px solid #ccc' class='text-center'>
										Penyesuaian <?=$nama_gudang;?>
									</th>
									<th colspan='2' style='border-bottom:1px solid #ccc' class='text-center'>
										Assembly <?=$nama_gudang;?>
									</th>
									<th colspan='2' style='border-bottom:1px solid #ccc' class='text-center'>
										RETUR JUAL
									</th>
									<th colspan='2' style='border-bottom:1px solid #ccc' class='text-center'>
										RETUR BELI
									</th>
									<th colspan='2' style='border-bottom:1px solid #ccc' class='text-center'>
										PENGELUARAN LAIN
									</th>
									<th colspan='2' style='border-bottom:1px solid #ccc' class='text-center'>
										SALDO AKHIR
									</th>
								</tr>
								
								<tr>
									<th>Yard/Kg</th>
									<th>Roll</th>
									<!-- <th class='text-center'>Nilai</th> -->
									<th>Yard/Kg</th>
									<th>Roll</th>
									<!-- <th class='text-center'>Nilai</th> -->
									<th>Yard/Kg</th>
									<th>Roll</th>
									<!-- <th class='text-center'>Nilai</th> -->
									<th>Yard/Kg</th>
									<th>Roll</th>
									<!-- <th class='text-center'>Nilai</th> -->
									<th>Yard/Kg</th>
									<th>Roll</th>
									<!-- <th class='text-center'>Nilai</th> -->
									<th>Yard/Kg</th>
									<th>Roll</th>
									<!-- <th class='text-center'>Nilai</th> -->
									<th>Yard/Kg</th>
									<th>Roll</th>
									<!-- <th class='text-center'>Nilai</th> -->
									<th>Yard/Kg</th>
									<th>Roll</th>
									<!-- <th class='text-center'>Nilai</th> -->
									<th>Yard/Kg</th>
									<th>Roll</th>
									<!-- <th class='text-center'>Nilai</th> -->
									<th>Yard/Kg</th>
									<th>Roll</th>
									<!-- <th class='text-center'>Nilai</th> -->
									<th>Yard/Kg</th>
									<th>Roll</th>
								</tr>
							</thead>
							<tbody>
								<?

								foreach ($this->satuan_list_aktif as $row) {
									${'qty_stock_'.$row->id} = 0;
									${'roll_stock_'.$row->id} = 0;
									${'qty_beli_'.$row->id} = 0;
									${'roll_beli_'.$row->id} = 0;
									${'qty_jual_'.$row->id} = 0;
									${'roll_jual_'.$row->id} = 0;
									${'qty_mutasi_masuk_'.$row->id} = 0;
									${'roll_mutasi_masuk_'.$row->id} = 0;
									${'qty_mutasi_keluar_'.$row->id} = 0;
									${'roll_mutasi_keluar_'.$row->id} = 0;
									${'qty_penyesuaian_'.$row->id} = 0;
									${'roll_penyesuaian_'.$row->id} = 0;
									${'qty_assembly_'.$row->id} = 0;
									${'roll_assembly_'.$row->id} = 0;
									${'qty_retur_'.$row->id} = 0;
									${'roll_retur_'.$row->id} = 0;

									${'qty_retur_beli_'.$row->id} = 0;
									${'roll_retur_beli_'.$row->id} = 0;

									${'qty_lain_'.$row->id} = 0;
									${'roll_lain_'.$row->id} = 0;

									${'g_jual_'.$row->id} = 0;
								}
								$nilai_stok_awal = 0;
								$nilai_stok_akhir = 0;
								$nilai_jual_akhir = 0;
								

								foreach ($mutasi_barang_list as $row) { 
									$total_qty = 0;
									$total_roll = 0;
									// echo '<hr/>';
									// print_r($row);
									// echo ${'qty_stock_'.$row->satuan_id};

									${'qty_stock_'.$row->satuan_id} += $row->qty_stock;
									${'roll_stock_'.$row->satuan_id} += $row->jumlah_roll_stock;
									${'qty_beli_'.$row->satuan_id} += $row->qty_beli;
									${'roll_beli_'.$row->satuan_id} += $row->jumlah_roll_beli;
									${'qty_jual_'.$row->satuan_id} += $row->qty_jual;
									${'roll_jual_'.$row->satuan_id} += $row->jumlah_roll_jual;
									${'qty_mutasi_masuk_'.$row->satuan_id} += $row->qty_mutasi_masuk;
									${'roll_mutasi_masuk_'.$row->satuan_id} += $row->jumlah_roll_mutasi_masuk;
									${'qty_mutasi_keluar_'.$row->satuan_id} += $row->qty_mutasi;
									${'roll_mutasi_keluar_'.$row->satuan_id} += $row->jumlah_roll_mutasi;
									${'qty_penyesuaian_'.$row->satuan_id} += $row->qty_penyesuaian;
									${'roll_penyesuaian_'.$row->satuan_id} += $row->jumlah_roll_penyesuaian;
									${'qty_assembly_'.$row->satuan_id} += $row->qty_assembly;
									${'roll_assembly_'.$row->satuan_id} += $row->jumlah_roll_assembly;
									${'qty_retur_'.$row->satuan_id} += $row->qty_retur;
									${'roll_retur_'.$row->satuan_id} += $row->jumlah_roll_retur;

									${'qty_retur_beli_'.$row->satuan_id} += $row->qty_retur_beli;
									${'roll_retur_beli_'.$row->satuan_id} += $row->jumlah_roll_retur_beli;

									${'qty_lain_'.$row->satuan_id} += $row->qty_lain;
									${'roll_lain_'.$row->satuan_id} += $row->jumlah_roll_lain;

									?>
									<tr>
										<td><?=$row->nama_jual;?> <?=$row->warna_jual;?> <?=(is_posisi_id() == 1 ? $row->barang_id.' '.$row->warna_id : '')?></td>
										<td hidden class='text-center'><?=number_format($row->hpp,'0',',','.');?></td>
										
										<?
											// $qty_stock += $row->qty_stock;
											// $roll_stock += $row->jumlah_roll_stock;
											// $qty_beli += $row->qty_beli;
											// $roll_beli += $row->jumlah_roll_beli;
											// $qty_jual += $row->qty_jual;
											// $roll_jual += $row->jumlah_roll_jual;
											// $qty_mutasi_masuk += $row->qty_mutasi_masuk;
											// $roll_mutasi_masuk += $row->jumlah_roll_mutasi_masuk;
											// $qty_mutasi_keluar += $row->qty_mutasi;
											// $roll_mutasi_keluar += $row->jumlah_roll_mutasi;
											// $qty_penyesuaian += $row->qty_penyesuaian;
											// $roll_penyesuaian += $row->jumlah_roll_penyesuaian;
											// $qty_retur += $row->qty_retur;
											// $roll_retur += $row->jumlah_roll_retur;
											
										?>

										<td><?=number_format($row->qty_stock,'2',',','.');?></td>
										<td><?=number_format($row->jumlah_roll_stock,'0',',','.')?></td>
										<!-- <td class='text-center'><?=number_format($row->qty_stock * $row->hpp,'2',',','.')?></td> -->
										
										<td><?=number_format($row->qty_beli,'2',',','.')?></td>
										<td><?=number_format($row->jumlah_roll_beli,'0',',','.')?></td>
										<!-- <td class='text-center'><?=number_format($row->qty_beli * $row->hpp_beli,'2',',','.')?></td> -->

										<?
										$total_qty_stock = $row->qty_stock + $row->qty_beli;
										if ($total_qty_stock == 0) {
										 	$total_qty_stock = 1;
										}
										?>
										<td><?=number_format($row->qty_jual,'2',',','.')?></td>
										<td><?=number_format($row->jumlah_roll_jual,'0',',','.')?></td>
										<!-- <td class='text-center'><?=number_format($row->qty_jual * $hpp_all,'2',',','.')?></td> -->
										
										<td><?=number_format($row->qty_mutasi_masuk,'2',',','.')?></td>
										<td><?=number_format($row->jumlah_roll_mutasi_masuk,'0',',','.')?></td>
										<!-- <td class='text-center'><?=number_format($row->qty_mutasi_masuk * $hpp_all,'0',',','.')?></td> -->

										<td><?=number_format($row->qty_mutasi,'2',',','.')?></td>
										<td><?=number_format($row->jumlah_roll_mutasi,'0',',','.')?></td>
										<!-- <td class='text-center'><?=number_format($row->qty_mutasi * $hpp_all,'0',',','.')?></td> -->

										<td><?=number_format($row->qty_penyesuaian,'2',',','.')?></td>
										<td><?=number_format($row->jumlah_roll_penyesuaian,'0',',','.')?></td>
										<!-- <td class='text-center'><?=number_format($row->qty_penyesuaian * $hpp_all,'0',',','.')?></td> -->

										<td><?=number_format($row->qty_assembly,'2',',','.')?></td>
										<td><?=number_format($row->jumlah_roll_assembly,'0',',','.')?></td>
										<!-- <td class='text-center'><?=number_format($row->qty_assembly * $hpp_all,'0',',','.')?></td> -->

										<td><?=number_format($row->qty_retur,'2',',','.')?></td>
										<td><?=number_format($row->jumlah_roll_retur,'0',',','.')?></td>
										<!-- <td class='text-center'><?=number_format($row->qty_retur * $row->hpp,'0',',','.')?></td> -->

										<td><?=number_format($row->qty_retur_beli,'2',',','.')?></td>
										<td><?=number_format($row->jumlah_roll_retur_beli,'0',',','.')?></td>
										<!-- <td class='text-center'><?=number_format($row->qty_retur * $row->hpp,'0',',','.')?></td> -->

										<td><?=number_format($row->qty_lain,'2',',','.')?></td>
										<td><?=number_format($row->jumlah_roll_lain,'0',',','.')?></td>
										<!-- <td class='text-center'><?=number_format($row->qty_retur * $row->hpp,'0',',','.')?></td> -->

										<?
											$qty_akhir[$row->barang_id.'--'.$row->warna_id] = $row->qty_stock + $row->qty_beli - $row->qty_jual 
											+ $row->qty_mutasi_masuk - $row->qty_mutasi
											+ $row->qty_retur - $row->qty_retur_beli 
											- $row->qty_lain + $row->qty_penyesuaian
											+ $row->qty_assembly;
											$jumlah_roll_akhir[$row->barang_id.'--'.$row->warna_id] = $row->jumlah_roll_stock + $row->jumlah_roll_beli - $row->jumlah_roll_jual 
											+ $row->jumlah_roll_mutasi_masuk -  $row->jumlah_roll_mutasi 
											+ $row->jumlah_roll_retur + $row->jumlah_roll_penyesuaian 
											- $row->jumlah_roll_retur_beli - $row->jumlah_roll_lain
											+ $row->jumlah_roll_assembly;
										?>

										<td><?=number_format($qty_akhir[$row->barang_id.'--'.$row->warna_id],'2',',','.')?></td>
										<td><?=number_format($jumlah_roll_akhir[$row->barang_id.'--'.$row->warna_id],'0',',','.')?></td>
										
									</tr>
								<?}?>
							</tbody>
						</table>
						<hr>

						<form id="tutup-buku-form" action="<?=base_url();?>inventory/tutup_buku_gudang_insert" method="POST" <?=(is_posisi_id() != 1 ? 'hidden' : 'hidden')?>  >
							<input name="tanggal" value="<?=$tanggal_start?>">
							<input name="gudang_id" value="<?=$gudang_id?>">
								<br/>
							<?foreach ($mutasi_barang_list as $row) {?>
								<input name="data_id[<?=$row->barang_id?>--<?=$row->warna_id?>]" value="<?=$row->barang_id?>--<?=$row->warna_id?>">
								<input style="<?=($qty_akhir[$row->barang_id.'--'.$row->warna_id] < 0 ? 'border:2px solid red':'');?>" name="qty[<?=$row->barang_id?>--<?=$row->warna_id?>]" value="<?=$qty_akhir[$row->barang_id.'--'.$row->warna_id];?>" >
								<input style="<?=($jumlah_roll_akhir[$row->barang_id.'--'.$row->warna_id] < 0 ? 'border:2px solid red':'');?>" name="roll[<?=$row->barang_id?>--<?=$row->warna_id?>]" value="<?=$jumlah_roll_akhir[$row->barang_id.'--'.$row->warna_id];?>" >
								<br/>
							<?}?>
						</form>
						<div style='width:100%; overflow:auto'>
							<table class='table table-bordered' style='font-size:1.1em;'>
								<tr>
									<th rowspan='2'></th>
									<th colspan='2'>STOK</th>
									<th colspan='2'>PEMBELIAN</th>
									<th colspan='2'>PENJUALAN</th>
									<th colspan='2'>MUTASI IN</th>
									<th colspan='2'>MUTASI OUT</th>
									<th colspan='2'>PENYESUAIAN</th>
									<th colspan='2'>ASSEMBLY</th>
									<th colspan='2'>RETUR JUAL</th>
									<th colspan='2'>RETUR BELI</th>
									<th colspan='2'>LAIN2</th>
									<th colspan='2'>AKHIR</th>
								</tr>
								<tr>
									<th>Yard</th>
									<th>Roll</th>

									<th>Yard</th>
									<th>Roll</th>
									
									<th>Yard</th>
									<th>Roll</th>
									
									<th>Yard</th>
									<th>Roll</th>
									
									<th>Yard</th>
									<th>Roll</th>
									
									<th>Yard</th>
									<th>Roll</th>

									<th>Yard</th>
									<th>Roll</th>
									
									<th>Yard</th>
									<th>Roll</th>
									
									<th>Yard</th>
									<th>Roll</th>

									<th>Yard</th>
									<th>Roll</th>

									<th>Yard</th>
									<th>Roll</th>
								</tr>
								<?$gq = 0; $gr = 0; $gaq=0; $gar=0;
								foreach ($this->satuan_list_aktif as $row) {
									$qty_total = ${'qty_stock_'.$row->id} + ${'qty_beli_'.$row->id} 
										- ${'qty_jual_'.$row->id} + ${'qty_mutasi_masuk_'.$row->id} 
										- ${'qty_mutasi_keluar_'.$row->id} + ${'qty_penyesuaian_'.$row->id} 
										+ ${'qty_retur_'.$row->id} - ${'qty_retur_beli_'.$row->id} 
										- ${'qty_lain_'.$row->id} + ${'qty_assembly_'.$row->id};
									$roll_total = ${'roll_stock_'.$row->id} + ${'roll_beli_'.$row->id} 
										- ${'roll_jual_'.$row->id} + ${'roll_mutasi_masuk_'.$row->id} 
										- ${'roll_mutasi_keluar_'.$row->id} + ${'roll_penyesuaian_'.$row->id} 
										+ ${'roll_retur_'.$row->id} - ${'roll_retur_beli_'.$row->id} 
										- ${'roll_lain_'.$row->id} + ${'roll_assembly_'.$row->id};
									
									$gq += $qty_total;
									$gr += $roll_total;

									$gaq += ${'qty_stock_'.$row->id};
									$gar += ${'roll_stock_'.$row->id};

									?>
									<tr>
										<td><?=$row->nama;?></td>
										<td><?=number_format(${'qty_stock_'.$row->id},'2',',','.');?></td>
										<td><?=number_format(${'roll_stock_'.$row->id},'2',',','.');?></td>

										<td><?=number_format(${'qty_beli_'.$row->id},'2',',','.');?></td>
										<td><?=number_format(${'roll_beli_'.$row->id},'2',',','.');?></td>

										<td><?=number_format(${'qty_jual_'.$row->id},'2',',','.');?></td>
										<td><?=number_format(${'roll_jual_'.$row->id},'2',',','.');?></td>

										<td><?=number_format(${'qty_mutasi_masuk_'.$row->id},'2',',','.');?></td>
										<td><?=number_format(${'roll_mutasi_masuk_'.$row->id},'2',',','.');?></td>

										<td><?=number_format(${'qty_mutasi_keluar_'.$row->id},'2',',','.');?></td>
										<td><?=number_format(${'roll_mutasi_keluar_'.$row->id},'2',',','.');?></td>

										<td><?=number_format(${'qty_penyesuaian_'.$row->id},'2',',','.');?></td>
										<td><?=number_format(${'roll_penyesuaian_'.$row->id},'2',',','.');?></td>

										<td><?=number_format(${'qty_assembly_'.$row->id},'2',',','.');?></td>
										<td><?=number_format(${'roll_assembly_'.$row->id},'2',',','.');?></td>

										<td><?=number_format(${'qty_retur_'.$row->id},'2',',','.');?></td>
										<td><?=number_format(${'roll_retur_'.$row->id},'2',',','.');?></td>

										<td><?=number_format(${'qty_retur_beli_'.$row->id},'2',',','.');?></td>
										<td><?=number_format(${'roll_retur_beli_'.$row->id},'2',',','.');?></td>

										<td><?=number_format(${'qty_lain_'.$row->id},'2',',','.');?></td>
										<td><?=number_format(${'roll_lain_'.$row->id},'2',',','.');?></td>

										<td><?=number_format($qty_total,'2',',','.');?></td>
										<td><?=number_format($roll_total,'2',',','.');?></td>
									</tr>
								<?}?>
								<tr <?=(is_posisi_id() != 1 ? 'hidden' : '');?> >
									<th></th>
									<th><?=number_format($gaq,'2',',','.');?></th>
									<th><?=number_format($gar,'2',',','.');?></th>
									<th colspan='16'>TOTAL</th>
									<th><?=number_format($gq,'2',',','.');?></th>
									<th><?=number_format($gr,'2',',','.');?></th>

								</tr>
							</table>
							<?if(is_posisi_id() == 1){?>
							<h4>
								TOTAL STOK  = <?=number_format($nilai_stok_awal,'2');?>
								TOTAL STOK AKHIR  = <?=number_format($nilai_stok_akhir,'2');?>
								TOTAL JUAL AKHIR  = <?=number_format($nilai_jual_akhir,'2');?>
							</h4>
							<?}?>
							
						</div>
						<div>
						<?if(date('Y',strtotime($tanggal)) > 2020 ){
										if ($tutup_buku_gudang_id != 0 && $tutup_buku_gudang_id_now == 0) {?>
											<button class='btn btn-md blue tutup-buku'><i class='fa fa-bookmark'></i> TUTUP BUKU STOK <?=$nama_gudang?></button>
										<?}else if($tutup_buku_gudang_id != 0){?>
											<button class='btn btn-md yellow-gold tutup-buku'><i class='fa fa-bookmark'></i> TUTUP ULANG STOK <?=$nama_gudang?></button>
										<?}
									}?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>			
</div>

<script src="<?php echo base_url('assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/bootbox/bootbox.min.js'); ?>" type="text/javascript"></script>

<script src="<?php echo base_url('assets/global/plugins/datatables/media/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets_noondev/js/table-advanced.js'); ?>"></script>
<script src="<?php echo base_url('assets/global/plugins/select2/select2.min.js'); ?>" type="text/javascript" ></script>


<script>
jQuery(document).ready(function() {
	

	$("#general_table").DataTable({
		"ordering":false
	});

	setTimeout(function(){
		$('#info-section').toggle('slow');
	},7000);


	$('#barang_id_select, #warna_id_select,#barang_id_select2, #warna_id_select2, #barang_select, #warna_select').select2({
        placeholder: "Pilih...",
        allowClear: true
    });

    $('.date-picker-month').datepicker({
        autoclose : true,
        format: "MM yyyy"
	});

	$(".tutup-buku").click(function(){
			bootbox.confirm("Yakin melakukan tutup buku <?=$nama_gudang?>?", function(respond){
				if (respond) {
					$("#tutup-buku-form").submit();
					btn_disabled_load($(".tutup-buku"));
				};
			});
		});

});
</script>
