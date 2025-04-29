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
								<td style='width:35%'>
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
															<?/*<td hidden>
																<b>
																	<input name='tanggal_start' readonly class='date-picker' style='border:none; border-bottom:1px solid #ddd; width:100px;' value='<?=$tanggal_start;?>'>
																	s/d
																	<input name='tanggal_end' readonly class='date-picker2 ' style='border:none; border-bottom:1px solid #ddd; width:100px;' value='<?=$tanggal_end;?>'> 
																</b>
															</td>*/?>
															<td><button class='btn btn-xs default'><i class='fa fa-search'></i></button></td>
														</tr>

														<tr>
															<td>Gudang</td>
															<td class='padding-rl-5'> : </td>
															<td>
																<b>
																	<select class='form-control' name='gudang_id'>
																		<option value='0'>All + Nilai</option>
																		<?foreach ($this->gudang_list_aktif as $row) { 
																			if ($gudang_id == $row->id) {
																				$nama_gudang = $row->nama;
																			}
																			?>
																			<option <?=($gudang_id == $row->id ? 'selected' : '');?> value='<?=$row->id?>'><?=$row->nama;?></option>
																		<?}?>
																	</select>
																</b>
															</td>
														</tr>
													</table>
												</td>
												<td>
												</td>
											</tr>
										</table>
																	
									</form>
								</td>
								<td style='width:30%' class='text-center'>
									<div style='font-size:2em;'>
										<?=$tanggal;?>
									</div>
									<?if ($tutup_buku_id == 0) {?>
										<div class='note note-warning'><?=$tanggal_before?> Belum tutup buku <?=($tanggal_last != '' ? "<br/>Terakhir:".date("F Y", strtotime($tanggal_last)) : '');?> </div>
									<?}else if($tutup_buku_id_now !=0){?>
										<div class='note note-success'>Tutup Buku: <b><?=$username?> ( <?=is_reverse_datetime($updated_now);?> )</b> </div>
									<?}else{?>
										<div class='note note-info'>Tutup Buku Available</b> </div>
									<?}?>
								</td>
								<td style='width:35%' class='text-right'>
									<?//if ($tutup_buku_id_now != 0) {?>
										<a href="<?=base_url().'inventory/mutasi_persediaan_barang_nilai_excel?tanggal_start='.is_date_formatter($tanggal_start).'&tanggal_end='.is_date_formatter($tanggal_end).'&toko_id='.$toko_id.'&gudang_id='.$gudang_id;?>" class='btn btn-md green'><i class='fa fa-download'></i> EXCEL</a>
									<?//}

									if ($tutup_buku_id != 0 && $tutup_buku_id_now == 0) {?>
										<button class='btn btn-md blue tutup-buku'><i class='fa fa-bookmark'></i> TUTUP BUKU</button>
									<?}else{?>
										<button class='btn btn-md blue tutup-buku'><i class='fa fa-bookmark'></i> TUTUP BUKU ULANG</button>
									<?}

									?>
								</td>
							</tr>
						</table>
						
						
						<hr/>
						<!-- table-striped table-bordered  -->
							<?if (is_posisi_id() != 1) {?>
								<table class="table table-hover table-bordered" id="general_table">
							<?}else{?>
								<table class="table-bordered" id="general_table">
							<?}?>
								<thead>
									<tr>
										<th scope="col" style='width:150px;' rowspan='2' class='text-center'>
											Nama
										</th>
										<th scope="col" rowspan='2' class='text-center'>
											Harga Sat.
										</th>
										<th scope="col" colspan='3' style='border-bottom:1px solid #ccc'>
											STOK PER
											( <?=strtoupper(date('d M Y', strtotime(is_date_formatter($tanggal_start))));?> )
										</th>
										<th colspan='3' style='border-bottom:1px solid #ccc' class='text-center'>
											PEMBELIAN
										</th>
										<th colspan='3' style='border-bottom:1px solid #ccc' class='text-center'>
											PENJUALAN
										</th>
										<? /*
										<th colspan='3' style='border-bottom:1px solid #ccc' class='text-center'>
											MUTASI masuk <?=$nama_gudang;?>
										</th>
										<th colspan='3' style='border-bottom:1px solid #ccc' class='text-center'>
											MUTASI keluar <?=$nama_gudang;?>
										</th>*/?>
										<th colspan='3' style='border-bottom:1px solid #ccc' class='text-center'>
											Penyesuaian
										</th>
										<th colspan='3' style='border-bottom:1px solid #ccc' class='text-center'>
											RETUR JUAL
										</th>
										<th colspan='3' style='border-bottom:1px solid #ccc' class='text-center'>
											RETUR BELI
										</th>
										<th colspan='3' style='border-bottom:1px solid #ccc' class='text-center'>
											PENGELUARAN LAIN
										</th>
										<th colspan='3' style='border-bottom:1px solid #ccc' class='text-center'>
											SALDO AKHIR
										</th>
										<?if (is_posisi_id()==1) {?>
											<th rowspan='2' style='border-bottom:1px solid #ccc' class='text-center'>
												BID/WID
											</th>
										<?}?>
									</tr>
									
									<tr>
										<th>Yard/Kg</th>
										<th>Roll</th>
										<th class='text-center'>Nilai</th>
										<th>Yard/Kg</th>
										<th>Roll</th>
										<th class='text-center'>Nilai</th>
										<th>Yard/Kg</th>
										<th>Roll</th>
										<th class='text-center'>Nilai</th>
										<?/*
										<th>Yard/Kg</th>
										<th>Roll</th>
										<th class='text-center'>Nilai</th>
										<th>Yard/Kg</th>
										<th>Roll</th>
										<th class='text-center'>Nilai</th>
										*/?>
										<th>Yard/Kg</th>
										<th>Roll</th>
										<th class='text-center'>Nilai</th>

										<th>Yard/Kg</th>
										<th>Roll</th>
										<th class='text-center'>Nilai</th>
										
										<th>Yard/Kg</th>
										<th>Roll</th>
										<th class='text-center'>Nilai</th>

										<th>Yard/Kg</th>
										<th>Roll</th>
										<th class='text-center'>Nilai</th>
										
										<th>Yard/Kg</th>
										<th>Roll</th>
										<th class='text-center'>Nilai</th>
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

										${'qty_lain_'.$row->id} = 0;
										${'roll_lain_'.$row->id} = 0;
										
										${'qty_penyesuaian_'.$row->id} = 0;
										${'roll_penyesuaian_'.$row->id} = 0;
										${'qty_retur_'.$row->id} = 0;
										${'roll_retur_'.$row->id} = 0;

										${'qty_retur_beli_'.$row->id} = 0;
										${'roll_retur_beli_'.$row->id} = 0;

										${'g_jual_'.$row->id} = 0;
									}

									$nilai_stok_awal =0;
									$nilai_stok_akhir =0;
									$per_100 = 0;
									$per_200 = 0;
									$after_200 = 0;
									$idx=0;

									foreach ($mutasi_barang_list as $row) { 
										$total_qty = 0;
										$total_roll = 0;
										$tjr[$row->barang_id] = $row->nama_jual;
										if (!isset($hpp_akhir[$row->barang_id.'--'.$row->warna_id])) {
											$hpp_akhir[$row->barang_id.'--'.$row->warna_id] = 0;
										}

										${'qty_stock_'.$row->satuan_id} += $row->qty_stock;
										${'qty_beli_'.$row->satuan_id} += $row->qty_beli;
										${'qty_jual_'.$row->satuan_id} += $row->qty_jual;
										${'qty_lain_'.$row->satuan_id} += $row->qty_lain;
										${'qty_penyesuaian_'.$row->satuan_id} += $row->qty_penyesuaian;
										${'qty_retur_'.$row->satuan_id} += $row->qty_retur;
										${'qty_retur_beli_'.$row->satuan_id} += $row->qty_retur_beli;
										
										//if ($row->qty_beli > 0) {?>
											<tr <?//=($row->qty_beli > 0 ? '' : 'hidden');?> >
												<td><?=$row->nama_jual;?> <?=$row->warna_jual;?></td>
												<?if ($row->satuan_id != 1) {
													$nama_kg[$idx] = $row->nama_jual.' '.$row->warna_jual;
												}?>
												<td class='text-center'><?=number_format($row->hpp,'2',',','.');?></td>
												
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
	
													
	
													// ${'qty_mutasi_masuk_'.$row->satuan_id} += $row->qty_mutasi_masuk;
													// ${'roll_mutasi_masuk_'.$row->satuan_id} += $row->jumlah_roll_mutasi_masuk;
													// ${'qty_mutasi_keluar_'.$row->satuan_id} += $row->qty_mutasi;
													// ${'roll_mutasi_keluar_'.$row->satuan_id} += $row->jumlah_roll_mutasi;
													
													if ($row->tipe_qty != 3) {
														${'roll_stock_'.$row->satuan_id} += $row->jumlah_roll_stock;
														${'roll_beli_'.$row->satuan_id} += $row->jumlah_roll_beli;
														${'roll_jual_'.$row->satuan_id} += $row->jumlah_roll_jual;
														${'roll_lain_'.$row->satuan_id} += $row->jumlah_roll_lain;
														${'roll_penyesuaian_'.$row->satuan_id} += $row->jumlah_roll_penyesuaian;
														${'roll_retur_'.$row->satuan_id} += $row->jumlah_roll_retur;
														${'roll_retur_beli_'.$row->satuan_id} += $row->jumlah_roll_retur_beli;
													}
													
	
													$nilai_stok_awal += ($row->qty_stock * $row->hpp);
													if ($idx < 100) {
														$per_100 += $row->jumlah_roll_beli;
													}
	
													if ($idx < 200) {
														$per_200 += $row->jumlah_roll_beli;
													}else{
														$after_200 += $row->jumlah_roll_beli;
													}
													$idx++;
												?>
	
												<td><?=$row->qty_stock;?></td>
												<td><?=($row->tipe_qty != 3 ? $row->jumlah_roll_stock : '');?></td>
												<td class='text-center'><?=$row->qty_stock * $row->hpp?></td>
												
												<td><?=$row->qty_beli?></td>
												<td><?=($row->tipe_qty != 3 ?$row->jumlah_roll_beli : '');?></td>
												<td class='text-center'><?=$row->qty_beli * $row->hpp_beli?></td>
	
												<?
												$total_nilai =($row->hpp * $row->qty_stock) + ($row->hpp_beli * $row->qty_beli);
												$total_qty_stock = $row->qty_stock + $row->qty_beli;
												if ($total_qty_stock == 0) {
													 $total_qty_stock = 1;
												}
												$hpp_all = ($total_nilai / $total_qty_stock);?>
												<td><?=$row->qty_jual?> </td>
												<td><?=($row->tipe_qty != 3 ? $row->jumlah_roll_jual : '')?></td>
												<td class='text-center'><?=$row->qty_jual * $hpp_all?></td>
												
												<?/*
												<td><?=$row->qty_mutasi_masuk?></td>
												<td><?=$row->jumlah_roll_mutasi_masuk?></td>
												<td class='text-center'><?=$row->qty_mutasi_masuk * $hpp_all?></td>
	
												<td><?=$row->qty_mutasi?></td>
												<td><?=$row->jumlah_roll_mutasi?></td>
												<td class='text-center'><?=$row->qty_mutasi * $hpp_all?></td>
												*/?>
	
												<td><?=$row->qty_penyesuaian?></td>
												<td><?=($row->tipe_qty != 3 ? $row->jumlah_roll_penyesuaian : '')?></td>
												<td class='text-center'><?=$row->qty_penyesuaian * $hpp_all?></td>
	
												<td><?=$row->qty_retur?></td>
												<td><?=($row->tipe_qty != 3 ? $row->jumlah_roll_retur : '')?></td>
												<td class='text-center'><?=$row->qty_retur * $hpp_all?></td>
	
												<td><?=$row->qty_retur_beli?></td>
												<td><?=($row->tipe_qty != 3 ? $row->jumlah_roll_retur_beli : '')?></td>
												<td class='text-center'><?=$row->qty_retur_beli * $hpp_all?></td>
	
												<td><?=$row->qty_lain?></td>
												<td><?=($row->tipe_qty != 3 ? $row->jumlah_roll_lain : '')?></td>
												<td class='text-center'><?=$row->qty_lain * $hpp_all?></td>
	
												<?
												$qty_akhir = $row->qty_stock + $row->qty_beli - $row->qty_jual + $row->qty_penyesuaian+ $row->qty_retur- $row->qty_retur_beli-$row->qty_lain;
												$q_akhir[$row->barang_id.'--'.$row->warna_id] = $qty_akhir;
												$jumlah_roll_akhir = $row->jumlah_roll_stock + $row->jumlah_roll_beli - $row->jumlah_roll_jual + $row->jumlah_roll_mutasi_masuk -  $row->jumlah_roll_mutasi + $row->jumlah_roll_penyesuaian + $row->jumlah_roll_retur-$row->jumlah_roll_retur_beli-$row->jumlah_roll_lain;
												$roll_akhir[$row->barang_id.'--'.$row->warna_id] = $jumlah_roll_akhir;
                                                $nilai_akhir[$row->barang_id.'--'.$row->warna_id] = $qty_akhir * $hpp_all;
												$hpp_akhir[$row->barang_id.'--'.$row->warna_id] = $hpp_all;
												$show='';
												if (is_posisi_id() == 1) {
													if ($row->barang_id == 50 && $row->warna_id == 7) {
														$show =  $row->qty_stock .'+'. $row->qty_beli .'-'. $row->qty_jual .'+'. $row->qty_penyesuaian .'+'. $row->qty_retur;
													}
												}
												?>
												<td><?= $qty_akhir ?> <?=$show;?> </td>
												<td><?=($row->tipe_qty != 3 ? $jumlah_roll_akhir : '')?></td>
												<td class='text-center'>
													<?$nilai_stok_akhir += ($qty_akhir * $hpp_all); ?>
													<?=$qty_akhir * $hpp_all?>
												</td>
												<?if (is_posisi_id()==1) {?>
													<td><?=$row->barang_id;?>/<?=$row->warna_id;?></td>
												<?}?>
											</tr><?
										//}
										?>
									<?}?>
								</tbody>
							</table>


						<?//if ($tutup_buku_id != 0 && $tutup_buku_id_now == 0) {?>
							<form id="tutup-buku-form" action="<?=base_url();?>inventory/tutup_buku_insert" method="POST" hidden>
								<input name="tanggal" value="<?=$tanggal_start?>">
									<br/>
								<?foreach ($mutasi_barang_list as $row) {?>
									<input name="data_id[<?=$row->barang_id?>--<?=$row->warna_id?>]" value="<?=$row->barang_id?>--<?=$row->warna_id?>">
                                    <input name="qty[<?=$row->barang_id?>--<?=$row->warna_id?>]" value="<?=$q_akhir[$row->barang_id.'--'.$row->warna_id];?>" >
									<input name="roll[<?=$row->barang_id?>--<?=$row->warna_id?>]" value="<?=$roll_akhir[$row->barang_id.'--'.$row->warna_id];?>" >
									<input name="harga[<?=$row->barang_id?>--<?=$row->warna_id?>]" value="<?=$hpp_akhir[$row->barang_id.'--'.$row->warna_id];?>" >
                                    <br/>
								<?}?>
							</form>
						<?//}?>

						<hr>
						<div style='width:100%; overflow:auto'>
							<table class='table table-bordered' style='font-size:1.1em;'>
								<tr>
									<th rowspan='2'></th>
									<th colspan='2'>STOKS</th>
									<th colspan='2'>PEMBELIAN</th>
									<th colspan='2'>PENJUALAN</th>
									<? /*
									<th colspan='2'>MUTASI IN</th>
									<th colspan='2'>MUTASI OUT</th>
									*/?>
									<th colspan='2'>PENYESUAIAN</th>
									<th colspan='2'>RETUR JUAL</th>
									<th colspan='2'>RETUR BELI</th>
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
									<?/*
									<th>Yard</th>
									<th>Roll</th>
									<th>Yard</th>
									<th>Roll</th>
									*/?>
									<th>Yard </th>
									<th>Roll</th>

									<th>Yard </th>
									<th>Roll</th>

									<th>Yard</th>
									<th>Roll</th>
								</tr>
								<?
                                $total_st = 0;
                                $total_str = 0;
								$total_bl = 0;
								$total_blr = 0;
								$total_jl = 0;
								$total_jlr = 0;
								$total_pn = 0;
								$total_pnr = 0;
								$total_rj = 0;
								$total_rjr = 0;
								$total_rb = 0;
								$total_rbr = 0;
                                foreach ($this->satuan_list_aktif as $row) {
									$qty_total = ${'qty_stock_'.$row->id} + ${'qty_beli_'.$row->id} 
										- ${'qty_jual_'.$row->id} + ${'qty_penyesuaian_'.$row->id} 
										- ${'qty_retur_beli_'.$row->id} + ${'qty_retur_'.$row->id};
									 // + ${'qty_mutasi_masuk_'.$row->id} - ${'qty_mutasi_keluar_'.$row->id}
									$roll_total = ${'roll_stock_'.$row->id} + ${'roll_beli_'.$row->id} 
										- ${'roll_jual_'.$row->id} + ${'roll_penyesuaian_'.$row->id} 
										- ${'roll_retur_beli_'.$row->id}+ ${'roll_retur_'.$row->id};
									// + ${'roll_mutasi_masuk_'.$row->id} - ${'roll_mutasi_keluar_'.$row->id}

                                    $total_st += ${'qty_stock_'.$row->id};
                                    $total_str += ${'roll_stock_'.$row->id};
									$total_bl += ${'qty_beli_'.$row->id};
									$total_blr += ${'roll_beli_'.$row->id};
									$total_jl += ${'qty_jual_'.$row->id};
									$total_jlr += ${'roll_jual_'.$row->id};
									
                                    $total_pn += ${'qty_penyesuaian_'.$row->id};
									$total_pnr += ${'roll_penyesuaian_'.$row->id};
                                    $total_rb += ${'qty_retur_beli_'.$row->id};
									$total_rbr += ${'roll_retur_beli_'.$row->id};
                                    $total_rj += ${'qty_retur_'.$row->id};
									$total_rjr += ${'roll_retur_'.$row->id};
									?>
									<tr>
										<td><?=$row->nama;?></td>
										<td><?=number_format(${'qty_stock_'.$row->id},'2',',','.');?></td>
										<td><?=number_format(${'roll_stock_'.$row->id},'2',',','.');?></td>
										<td><?=number_format(${'qty_beli_'.$row->id},'2',',','.');?></td>
										<td><?=number_format(${'roll_beli_'.$row->id},'2',',','.');?></td>
										<td><?=number_format(${'qty_jual_'.$row->id},'2',',','.');?></td>
										<td><?=number_format(${'roll_jual_'.$row->id},'2',',','.');?></td>
										<?/*
										<td><?=number_format(${'qty_mutasi_masuk_'.$row->id},'2',',','.');?></td>
										<td><?=number_format(${'roll_mutasi_masuk_'.$row->id},'2',',','.');?></td>
										<td><?=number_format(${'qty_mutasi_keluar_'.$row->id},'2',',','.');?></td>
										<td><?=number_format(${'roll_mutasi_keluar_'.$row->id},'2',',','.');?></td>
										*/?>
										<td><?=number_format(${'qty_penyesuaian_'.$row->id},'2',',','.');?></td>
										<td><?=number_format(${'roll_penyesuaian_'.$row->id},'2',',','.');?></td>
										<td><?=number_format(${'qty_retur_'.$row->id},'2',',','.');?></td>
										<td><?=number_format(${'roll_retur_'.$row->id},'2',',','.');?></td>
										
										<td><?=number_format(${'qty_retur_beli_'.$row->id},'2',',','.');?></td>
										<td><?=number_format(${'roll_retur_beli_'.$row->id},'2',',','.');?></td>
										
										<td><?=number_format($qty_total,'2',',','.');?></td>
										<td><?=number_format($roll_total,'2',',','.');?></td>
									</tr>
								<?}?>

                                <?$g_total = $total_st + $total_bl 
										- $total_jl + $total_pn 
										- $total_rb + $total_rj;
									 // + ${'qty_mutasi_masuk_'.$row->id} - ${'qty_mutasi_keluar_'.$row->id}
									$r_total = $total_str + $total_blr 
                                    - $total_jlr + $total_pnr 
                                    - $total_rbr + $total_rjr;?>
                                <tr>
									<td></td>
									<td><?=number_format($total_st,'2',',','.')?></td>
									<td><?=number_format($total_str,'0',',','.')?></td>
									<td><?=number_format($total_bl,'2',',','.')?></td>
									<td><?=number_format($total_blr,'0',',','.')?></td>
									<td><?=number_format($total_jl,'2',',','.')?></td>
									<td><?=number_format($total_jlr,'0',',','.')?></td>

									<td><?=number_format($total_pn,'2',',','.')?></td>
									<td><?=number_format($total_pnr,'0',',','.')?></td>
									<td><?=number_format($total_rb,'2',',','.')?></td>
									<td><?=number_format($total_rbr,'0',',','.')?></td>
									<td><?=number_format($total_rj,'2',',','.')?></td>
									<td><?=number_format($total_rjr,'0',',','.')?></td>
									<td><?=number_format($g_total,'2',',','.')?></td>
									<td><?=number_format($r_total,'0',',','.')?></td>
								</tr>
							</table>
							<?if(is_posisi_id() == 1){?>
							<h4>
								<?=$ttt;?>
								TOTAL STOK  = <?=number_format($nilai_stok_awal,'2');?>
								TOTAL STOK AKHIR  = <?=number_format($nilai_stok_akhir,'2');?>
							</h4>
							<?}?>
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
	

	<?//if (is_posisi_id() != 1) {?>
		$("#general_table").DataTable({
			"ordering":false
		});
	<?//}?>

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

	<?//if ($tutup_buku_id != 0 && $tutup_buku_id_now == 0) {?>
		$(".tutup-buku").click(function(){
			bootbox.confirm("Yakin melakukan tutup buku ?", function(respond){
				if (respond) {
					$("#tutup-buku-form").submit();
					btn_disabled_load($(".tutup-buku"));
				};
			});
		});
	<?//}?>

});
</script>
