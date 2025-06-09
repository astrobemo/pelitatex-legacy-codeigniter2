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
	width: 80px;
	padding: 5px;
}

#stok-info, #stok-info-edit{
	font-size: 1.5em;
	position: absolute;
	right: 50px;
	top: 30px;
}

#qty-table-detail tr td, #qty-table-detail-edit tr td{
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

#jatuh-tempo-list, #jatuh-tempo-list-edit{
	display: none;
	max-height: 150px;
	overflow: auto;
	border: 1px solid #eee;
	padding: 3px;
	/*position: absolute;
	top: 0px;
	right: 0px;*/
}

#jatuh-tempo-list tr td, #jatuh-tempo-list-edit tr td{
	padding: 2px 5px;
}

#jatuh-tempo-rekap{
	display: none;
}

</style>

<div class="page-content">
	<div class='container'>
		<?
			$status_aktif  ='';
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
			$note_info = ''; 

			$jatuh_tempo = date('d/m/Y', strtotime("+60 days") );
			$ori_jatuh_tempo = '';
			$status = -99;

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

			$nik = '';
			$npwp = '';

			$g_total = 0;
			$readonly = '';
			$disabled = '';
			$alamat_customer = '';
			$disabled_status = '';
			$bg_info = '';
			$closed_date = '';
			$warning_type = 0;
			$limit_warning_amount = 0;
			$po_penjualan_id = '';
			$tipe_po ='';
			$limit_amount = 0;
			$batas_atas = 0;
			$limit_sisa_atas = 0;
			$sisa_update = 0;
			$limit_sisa = 0;
			$ppn = $ppn_set;

			$hidden_spv = '';
			// if (is_posisi_id() != 1) {
				$hidden_spv = 'hidden';
			// }

			foreach ($penjualan_data as $row) {
				$tipe_penjualan = $row->tipe_penjualan;
				$penjualan_id = $row->id;
				$customer_id = $row->customer_id;
				$nama_customer = $row->nama_keterangan;
				$alamat_filter_0 = str_replace('BLOK - ', '', $row->alamat_keterangan);
				$alamat_filter_0_1 = str_replace('No.- ', '', $alamat_filter_0);
				$alamat_filter_1 = str_replace('RT:000 RW:000 ', '', $alamat_filter_0_1);
				$alamat_filter_2 = str_replace('Kel.-', '', $alamat_filter_1);
				$alamat_filter_3 = str_replace('Kel.-', ',', $alamat_filter_2);
				// $alamat_filter_4 = str_replace('Kec.', ',', $alamat_filter_3);
				$alamat_final = $alamat_filter_3;
				$alamat_customer = $alamat_final;
				// $gudang_id = $row->gudang_id;
				// $nama_gudang = $row->nama_gudang;
				$no_faktur = $row->no_faktur;
				$penjualan_type_id = $row->penjualan_type_id; 
				$po_number = $row->po_number;
				$fp_status = $row->fp_status;
				$iClass = '';

				$closed_date = $row->closed_date;
				
				$tanggal_print = date('d F Y', strtotime($row->tanggal));

				$tanggal = is_reverse_date($row->tanggal);
				$ori_tanggal = $row->tanggal;
				$status_cek = 0;

				if ($penjualan_type_id == 1) {
					$note_info = "note note-info";
					$bg_info = "background: #95a5a6";

				}elseif ($penjualan_type_id == 2) {
					$note_info = "note note-warning";
					$bg_info = "background: #d35400";
				}elseif ($penjualan_type_id == 3) {
					$note_info = "note note-success";
					$bg_info = "background: #2980b9";
				}


				if ($penjualan_type_id == 2) {
					$dt = strtotime(' +'.get_jatuh_tempo($customer_id).' days', strtotime($row->tanggal) );
					if ($row->jatuh_tempo == $row->tanggal) {
						$status_cek = 1;
					}
				}
				$get_jt = '';
				if (isset($dt)) {
					$get_jt = ($row->jatuh_tempo == '' || $status_cek == 1  ? date('Y-m-d',$dt) : $row->jatuh_tempo);
				}
				// print_r($get_jt);
				$jatuh_tempo = is_reverse_date($get_jt);
				$ori_jatuh_tempo = $row->jatuh_tempo;
				$status = $row->status;
				
				$diskon = $row->diskon;
				$ongkos_kirim = $row->ongkos_kirim;
				$status_aktif = $row->status_aktif;
				$nama_keterangan = $row->nama_keterangan;
				$alamat_keterangan = $alamat_customer;
				$kota = $row->kota;
				$keterangan = $row->keterangan;
				$customer_id = $row->customer_id;
				$no_faktur_lengkap = $row->no_faktur_lengkap;
				$no_surat_jalan = $row->no_surat_jalan;
                $ppn = $row->ppn;

				if ($status_aktif == -1 ) {
					$note_info = 'note note-danger';
				}

				$po_penjualan_id = $row->po_penjualan_id;
				$tipe_po =$row->tipe_po;

			}

			$ppn_fix = $ppn;

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

			$tanggal_ambil = '';
			$tipe_ambil_barang_id = '';
			$status_ambil = '';
			/*foreach ($penjualan_posisi_barang as $row) {
				$tipe_ambil_barang_id = $row->tipe_ambil_barang_id;
				$tanggal_ambil = is_reverse_date($row->tanggal_pengambilan);
				$status_ambil = $row->status;
			}*/

			if ($status != 1) {
				if ( is_posisi_id() != 1 ) {
					$readonly = 'readonly';
				}
			}

			foreach ($customer_data as $row) {
				$nik = $row->nik;
				$npwp = $row->npwp;
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
				// $disabled = 'disabled';
				// $readonly = 'readonly';
			}
		?>

		<?include_once 'penjualan_detail_modal.php';?>

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
								<a href="#portlet-config" data-toggle='modal' onclick="resetFormAdd()" class="btn btn-default btn-sm btn-form-add">
								<i class="fa fa-plus"></i> Penjualan Baru </a>
							<?}?>
							<a href="#portlet-config-faktur" data-toggle='modal' class="btn btn-default btn-sm btn-form-add">
							<i class="fa fa-search"></i> Cari Faktur </a>
						</div>
					</div>
					<div class="portlet-body">
						<?include_once "penjualan_detail_header.php";?>
					    <div style='overflow:auto'>
							<!-- table-striped table-bordered  -->
							<table class="table table-hover table-striped table-bordered" id="general_table">
								<thead>
									<tr>
										<th scope="col">
											No
										</th>
										<th scope="col">
											Nama
										</th>
										<th scope="col">
											<b class='warning-limit' style='color:red' hidden><i class='fa fa-warning'></i> LIMIT</b><br/>
											Kode <?=(is_posisi_id()==1 ? $tipe_po : '');?>
											<?if ($penjualan_id !='' && $status == 1 && is_posisi_id() != 6 && $status_aktif != -1 ) {?>
												<?if ($po_penjualan_id != '') {?>
													<a href="#portlet-config-detail" data-toggle="modal" onclick="showBarangPO('baru')" class='btn btn-xs green' id="btnAddBarang"><i class="fa fa-plus"></i></a>
												<?}?>
												<?if ($tipe_po != 1 ) {?>
													<a href="#portlet-config-detail" data-toggle="modal"  onclick="hideBarangPO('baru')" class='btn btn-xs blue' id="btnAddBarang"><i class="fa fa-plus"></i></a>
												<?}?>
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
										<?if (is_posisi_id() == 1) {?>
											<th scope="col">
												Harga dpp
											</th>
											<th scope="col">
												Subtotal
											</th>
											<th scope="col">
												PPN
											</th>
											<th scope="col">
												Total Harga
											</th>
										<?}?>
										<th scope="col" class='hidden-print'>
											Action
										</th>
									</tr>
								</thead>
								<tbody>
									
								</tbody>
								<tfoot>
								<?
									$total_nppn = 0;
									$total_dpp = 0;
									$total_ppn = 0;
									$idx =1; $barang_id = ''; $gudang_id_last = ''; $harga_jual = 0; $qty_total = 0; $roll_total = 0;
									foreach ($penjualan_detail as $row) { ?>
										<tr id='id_<?=$row->id;?>'>
											<td>
												<?=$idx;?> 
											</td>
											<td>
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
												<span class='jumlah_roll'  <?=($row->tipe_qty == 3 ? 'hidden' : '')?> ><?=$row->jumlah_roll;?></span> 
											</td>
											<td>
												<!-- <input name='harga_jual' <?=$readonly;?> class='free-input-sm amount_number harga_jual' value="<?=number_format($row->harga_jual,'0','.','.');?>">  -->
												<span class='harga_jual'><?=number_format($row->harga_jual,'0','.','.');?></span>

											</td>
											<td>
												<?
													$subtotal = $row->qty * $row->harga_jual;
													$g_total += $subtotal;
													$harga_jual = $row->harga_jual;
													$qty_total += $row->qty;
													$roll_total += ($row->tipe_qty != 3 ? $row->jumlah_roll : 0);
												?>
												<span class='subtotal'><?=number_format($subtotal,'0','.','.');?></span> 
											</td>
											<?if (is_posisi_id() == 1) {
												$nppn_var = ($ppn/100) + 1;
												$harga_nppn = number_format($row->harga_jual/$nppn_var,'2','.','');?>
												<td>
													<!-- <input name='harga_jual' <?=$readonly;?> class='free-input-sm amount_number harga_jual' 
													value="<?=number_format($row->harga_jual,'0','.','.');?>">  -->
													<?=number_format($harga_nppn,'2','.',',');?>
												</td>
												<td>
													<?
													$subtotal_nppn = ceil(number_format($row->qty * $harga_nppn,'2','.',''));
													$total_dpp += $subtotal_nppn;
													// $ppn = round($subtotal_nppn * 0.11,0);
													$ppn_nilai = $subtotal - $subtotal_nppn;
													$total_ppn += $ppn_nilai;
													$total_nppn += $subtotal_nppn + $ppn_nilai
													?>
													<?=number_format($subtotal_nppn,'0','.',',');?>
												</td>
												<td>
													<?=number_format($ppn_nilai,'0','.',',');?>
												</td>
												<td>
													<?=number_format($subtotal_nppn + $ppn_nilai,'0','.',',');?> 
												</td>
											<?}?>

											<td class='hidden-print'>
												<?$gudang_id_last=$row->gudang_id;?>
												
												<?if ($status == 1 || is_posisi_id() == 1 ) { ?>
													<?//if (is_posisi_id() != 6 && $status_aktif != -1) { ?>
														<span class='id' <?=$hidden_spv?> ><?=$row->id;?></span>
														<span class='barang_id' <?=$hidden_spv?> ><?=$row->barang_id;?></span>
														<span class='warna_id' <?=$hidden_spv?> ><?=$row->warna_id;?></span>
														<span class='gudang_id'  <?=$hidden_spv?> ><?=$row->gudang_id;?></span>
														<span class='data_qty'  <?=$hidden_spv?> ><?=$row->data_qty;?></span>
														<a href='#portlet-config-detail-edit' data-toggle='modal' class="btn-xs btn green btn-edit"><i class="fa fa-edit"></i> </a>
														<a class="btn-xs btn red btn-detail-remove"><i class="fa fa-times"></i> </a>
													<?//}?>
												<?}?>
											</td>
										</tr>
									<?
									$idx++; 
									} ?>

									<tr class='subtotal-data'>
										<td colspan='5' class='text-right'><b>TOTAL</b></td>
										<td class='text-left'><b><?=str_replace('.00', '',$qty_total);?></b></td>
										<td class='text-left'><b><?=$roll_total;?></b></td>
										<td class='text-right'><b></b></td>
										<td><b class='total'><?=number_format($g_total,'0',',','.');?> </b> </td>
										<td class='text-hidden'><b>TOTAL</b></td>
										<?if (is_posisi_id() == 1) {?>
											<td>
												<?=number_format($total_dpp,'0','.','.');?> 
											</td>	
											<td>
												<?=number_format($total_ppn,'0','.','.');?> 
											</td>
											<td>
												<?=number_format($total_nppn,'0','.','.');?> 
											</td>
										<?}?>

										<td class='hidden-print'></td>
									</tr>
								</tfoot>
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
								<?foreach ($penjualan_detail as $row) {?>
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
									<?if ($saldo_dp > 0) {?>
										<div class="note note-warning" style='font-size:1.3em'><?=$nama_customer?> memiliki saldo DP <b><?=number_format($saldo_dp,"0",',','.');?></b></div>
									<?}?>
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
											if (is_posisi_id() == 6) {
												// $stat = 'readonly';
												// $style = 'background:#ddd; border:1px solid #ddd';
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
											
											<?if ($row->id == 1  ) { ?>
												<tr <?=($penjualan_type_id == 2 && $bayar == null ? 'hidden' : '')?>>
													<td><?=$row->nama;?><span class='saldo_awal' hidden>												<td>
														<a href="#portlet-config-dp" data-toggle='modal' class='manage-dp'>
															<input <?=$stat;?> style='<?=$style;?>' class='dp-val' value="<?=number_format($bayar,'0',',','.');?>" >
														</a>
														<span class='dp_copy' hidden><?=$bayar?></span>
													</td>
												</tr>
											<?}elseif ($row->id == 4 ) { ?>
												<tr <?=($penjualan_type_id == 2 && $bayar == null ? 'hidden' : '')?>>
													<td><?=$row->nama;?> </td>
													<td>
														<input <?=$stat;?> style='<?=$style;?>' class='amount_number bayar-input ' id='bayar_<?=$row->id;?>' value="<?=number_format($bayar,'0',',','.');?>">
														<?if ($penjualan_id != '') { 
															$stat_ex = (is_posisi_id() != 6 ? $stat : "");
															$style_ex = (is_posisi_id() != 6 ? $style : "");?>
															<a data-toggle="popover" style='color:black' data-trigger='click' data-html="true" data-content="<input <?=$stat_ex;?> style='<?=$style_ex;?>' class='keterangan_bayar' name='keterangan_<?=$row->id;?>' value='<?=$keterangan;?>'>">
																<i class='fa fa-edit'></i>
															</a>
														<?}?>
													</td>
												</tr>
											<?}elseif ($row->id == 5 ) { ?>
												<tr <?=($penjualan_type_id != 2 ? 'hidden' : '')?>>
													<td><?=$row->nama;?></td>
													<td>
														<a data-toggle="popover" style='color:black' data-trigger='hover' data-html="true" data-content="Hanya untuk tipe kredit pelanggan">
															<input <?=$stat;?> id='bayar_<?=$row->id;?>'  class='amount_number bayar-input bayar-kredit' value="<?=number_format($bayar,'0',',','.');?>">
														</a>
													</td>
												</tr>
											<?}elseif ($row->id == 6) { ?>
												<tr hidden >
													<td><?=$row->nama;?></td>
													<td>
														<a data-toggle="popover" style='color:black' data-trigger='hover' data-html="true" data-content="Nama Bank : <b><?=$nama_bank?></b><br/>No Rek : <b><?=$no_rek_bank?></b><br/>No Akun : <b><?=$no_akun?></b><br/>Nama Bank : <b><?=$nama_bank?></b><br/>Tanggal Giro : <b><?=$tanggal_giro?></b><br/>Jatuh Tempo : <b><?=$jatuh_tempo_giro?></b><br/>">
															<input <?=$stat;?> style='<?=$style;?>' class='amount_number bayar-giro' id='bayar_<?=$row->id;?>' value="<?=number_format($bayar,'0',',','.');?>">
														</a>
														<?if ($penjualan_id != '' && is_posisi_id() != 6 && $status != 0) { ?>
															<a data-toggle="modal" href='#portlet-config-giro' style='color:black' style='<?=$style;?>' >
																<i class='fa fa-edit'></i>
															</a>
														<?}?>
													</td>
												</tr>
											<?}else{?>
												<tr  <?=($penjualan_type_id == 2 ? 'hidden' : '')?>>
													<td><?=$row->nama;?></td>
													<td><input <?=$stat;?> style='<?=$style;?>' class='amount_number bayar-input' id='bayar_<?=$row->id;?>' value="<?=number_format($bayar,'0',',','.');?>"></td>
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
												<b>Rp <span class='total_bayar' style=''><?=number_format($bayar_total,'0',',','.');?></span></b>
											</td>
										</tr>
										<tr style='border:2px solid #ffd7b5'>
											<td class='padding-rl-25' style='background:#ffd7b5'>TOTAL</td>
											<td class='text-right padding-rl-10'> 
												<b>Rp <span class='g_total' style=''><?=number_format($g_total - $diskon,'0',',','.');?></span></b>
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
												<b>Rp <span class='kembali' style='<?=$kembali_style;?>'><?=number_format($kembali,'0',',','.');?></span></b>
											</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
						</div>
						<hr/>
						<?if ($no_faktur != '') {?>
							<div>
								<table class='table' style='font-size:1.5em; font-weight:bold'>
									<tr>
										<td>Posisi Barang</td>
										<td> : </td>
										<td>
											<?if ($tipe_ambil_barang_id != '') {?>
												<span>
												<?if ($tipe_ambil_barang_id != 5) { ?>
													Barang diambil <b><?=is_reverse_date($tanggal_ambil);?></b>	
												<?}else{?>
													Barang dikirim <b><?=is_reverse_date($tanggal_ambil);?></b>	
												<?}?>
												<?=($status_ambil == 1 ? "<span style='color:red'>[not confirmed]</span>" : "<span style='color:blue'>[confirmed]</span>"  );?>
												</span> 
											<?}elseif($penjualan_id != '' && $no_faktur != ''){?><b>Barang Langsung Diambil</b><?}?>
										</td>
									</tr>
									<tr hidden>
										<td>
											Surat Jalan
										</td>
										<td> : </td>
										<td>
											<a href="#portlet-config-sj-print" class='btn btn-lg blue' style='margin:0px 10px;' data-toggle='modal'> 
														Tgl : 18/12/2019 
													</a><a href="#portlet-config-sj" data-toggle='modal'><i class='fa fa-plus'></i> Baru</a>
										</td>
									</tr>
								</table>		
							</div>
						<?}?>
						<? $limit_warning_amount = ($warning_type==1 ? $limit_amount*($limit_warning_amount == 0 ? 90 : $limit_warning_amount)/100 : $limit_warning_amount ); ?>
						<!--limit_warning_amount<?=$limit_warning_amount?><br/>
						limit amount<?=$limit_amount?><br/>
						limit_atas<?=$limit_atas;?><br/>
						sisa_now : <?=$sisa_now;?><br/>-->
						<?if ($penjualan_type_id == 2 && $limit_amount != 0) {
							// echo 'sisa_update'.$sisa_update=$sisa_now - $g_total + $diskon;
							// echo 'limit_sisa'.$limit_sisa = $limit_amount - $limit_warning_amount + $sisa_update;
							// echo 'limit_sisa_atas'.$limit_sisa_atas = $limit_atas - $limit_warning_amount + $sisa_update;
							// echo 'limit_amount '.$limit_amounts;
							if($limit_sisa_atas < 0 ){?>
								<p class='note note-danger' style='font-size:1.2em'><b><i class='fa fa-warning' style='color:red'></i></b> Pembelanjaan Customer <b>sudah melebihi limit</b>, input PIN untuk melanjutkan <input type='password' id='pin-limit'></p>
							<?}else if($limit_sisa < 0 ){?>
								<p class='note note-warning' style='font-size:1.2em'><b><i class='fa fa-warning' style='color:red'></i></b> Pembelanjaan Customer <b>sudah melebihi limit</b>, mohon ingatkan untuk segera melakukan pelunasan</p>
							<?}elseif ($sisa_update < 0 ) {?>
								<p class='note note-info' style='font-size:1.2em'><b><i class='fa fa-warning' style='color:red'></i></b> Pembelanjaan Customer <b>hampir melebihi limit</b>, mohon ingatkan untuk membayar bon sebelumnya</p>
							<?}
						}else{$limit_sisa_atas = 0;}?>
						<??>
						<hr/>
						<?//print_r($data_penjualan_detail_group);?>
						<div>
							<?if ($status_aktif != -1) {
								?>
	                            <?$print_disable = ($penjualan_id == '' || $status == 1 ? 'disabled' : '');?>
	                            <?$display = (is_posisi_id() != 1 ? 'display:none' : '');?>

								<button style="<?//=($limit_sisa_atas < 0 && $penjualan_type_id == 2 ? 'display:none' : '')?>" type='button'<?if ($idx == 1) { echo 'disabled'; }?> <?=$disabled;?> <?if ($status != 1) {?> disabled <?}?> class='btn btn-lg red hidden-print btn-close' id='btn-lock-transaction'><i class='fa fa-lock'></i> LOCK </button>
							    <button style='<?=$display;?>' <?=$print_disable;?> class='btn btn-lg blue hidden-print' onclick="printRAWPrint('1','')"><i class='fa fa-print'></i> Faktur</button>
							    <button style='<?=$display;?>' <?=$print_disable;?> class='btn btn-lg blue hidden-print' onclick="printRAWPrint('2','')"><i class='fa fa-print'></i> Detail</button>
							    <button style='<?=$display;?>' <?=$print_disable;?> class='btn btn-lg blue hidden-print' onclick="printRAWPrint('3','')"><i class='fa fa-print'></i> Faktur+Detail</button>
							    <button style='<?=$display;?>' <?=$print_disable;?> class='btn btn-lg green hidden-print' onclick="setAlamat('1')"><i class='fa fa-print'></i> Surat Jalan</button>
							    <button style='<?=$display;?>' <?=$print_disable;?> class='btn btn-lg green hidden-print' onclick="setAlamat('2')"><i class='fa fa-print'></i> Surat Jalan no Harga</button>
								
	                            <?$display = ($penjualan_id == '' || $status == 1 ? 'display:none' : '');?>
								
								<!-- print PDF -->
								<!-- <button style='<?=$display;?>'  type="button" tabindex='-1' class="btn btn-lg blue" onclick="printInvoice()">FAKTUR</button>
								<button style='<?=$display;?>'  type="button" tabindex='-1' class="btn btn-lg blue" onclick="printInvoicePL()">FAKTUR + DETAIL</button> -->
								<!-- end print PDF -->
								
								<!-- print HTMLPDF -->
								<a href="#portlet-config-printHTML" data-toggle="modal" style='<?=$display;?>'  type="button" tabindex='-1' class="btn btn-lg blue" onclick="webPrintHTML('1')">FAKTUR</a>
								<a href="#portlet-config-printHTML" data-toggle="modal" style='<?=$display;?>'  type="button" tabindex='-1' class="btn btn-lg blue" onclick="webPrintHTML('2')">FAKTUR + DETAIL.</a>
								<!-- end print HTML -->
								
								<a id="btn-invoice-sj" style='<?=$display;?>'  tabindex='-1' class='btn btn-lg yellow-gold hidden-print' href='#portlet-config-address' data-toggle="modal">
									<i class='fa fa-print'></i> FAKTUR + SJ
								</a>


							    <?$display='display:none';?>
							    <a style='<?=$display;?>' <?if($disabled_status == ''){ ?>href="<?=base_url();?>transaction/penjualan_print?penjualan_id=<?=$penjualan_id;?>&type=1"<? }else{ echo $disabled_status; } ?> target='_blank' class='btn btn-lg blue  hidden-print'><i class='fa fa-print'></i> Faktur </a>
				                <a style='<?=$display;?>' <?if($disabled_status == ''){ ?>href="<?=base_url();?>transaction/penjualan_print?penjualan_id=<?=$penjualan_id;?>&type=2"<? }else{ echo $disabled_status; } ?> target='_blank' class='btn btn-lg blue  hidden-print'><i class='fa fa-print'></i> Detail </a>
				                <a style='<?=$display;?>' <?if($disabled_status == ''){ ?> href="<?=base_url();?>transaction/penjualan_print?penjualan_id=<?=$penjualan_id;?>&type=3"<? }else{ echo $disabled_status; } ?> target='_blank' class='btn btn-lg blue hidden-print'><i class='fa fa-print'></i> Faktur + Detail </a>
				                
				                <a style='<?=$display;?>' <?if($disabled_status == ''){ ?>onclick="setAlamat('1')"<? }else{ echo $disabled_status; } ?> target='_blank' class='btn btn-lg green  hidden-print'><i class='fa fa-print'></i> Surat Jalan </a>
				                <a style='<?=$display;?>' <?if($disabled_status == ''){ ?>onclick="setAlamat('2')"<? }else{ echo $disabled_status; } ?> target='_blank' class='btn btn-lg green  hidden-print'><i class='fa fa-print'></i> Surat Jalan No Harga </a>
	                            
				                <!-- <a style='<?=$display;?>' <?if($disabled_status == ''){ ?>href="<?=base_url();?>transaction/penjualan_sj_print?penjualan_id=<?=$penjualan_id;?>&harga=yes"<? }else{ echo $disabled_status; } ?> target='_blank' class='btn btn-lg green btn-print hidden-print'><i class='fa fa-print'></i> Surat Jalan </a>
				                <a style='<?=$display;?>' <?if($disabled_status == ''){ ?>href="<?=base_url();?>transaction/penjualan_sj_print?penjualan_id=<?=$penjualan_id;?>&harga=no"<? }else{ echo $disabled_status; } ?> target='_blank' class='btn btn-lg green btn-print hidden-print'><i class='fa fa-print'></i> Surat Jalan No Harga </a>
	                             --><!-- <button type="button" class="btn btn-success" onclick="startConnection();">Connect</button> -->
	                            <?$print_disable = ($penjualan_id == '' || $status == 1 ? 'disabled' : '');?>
	                            <?$display = (is_posisi_id() != 1 ? 'display:none' : '');?>
	                            <?$display = 'display:none';?>
	                            <button style="<?=$display;?>" <?=$print_disable;?> type="button" tabindex='-1' class="btn btn-lg blue btn-print btn-faktur-print">FAKTUR</button>
	                            <button style="<?=$display;?>" <?=$print_disable;?> type="button" tabindex='-1' class="btn btn-lg blue btn-print btn-print-detail">DETAIL</button>
	                            <button style="<?=$display;?>" <?=$print_disable;?> type="button" tabindex='-1' class="btn btn-lg blue btn-print btn-print-kombi">FAKTUR + DETAIL</button>
	                            <button style="<?=$display;?>" <?=$print_disable;?> type="button" tabindex='-1' class="btn btn-lg green btn-print btn-surat-jalan">SURAT JALAN</button>
	                            <button style="<?=$display;?>" <?=$print_disable;?> type="button" tabindex='-1' class="btn btn-lg green btn-print btn-surat-jalan-noharga">SJ NO HARGA</button>
				            	<?/* if (is_posisi_id() == 1) {?>
					                <button type="button" class='btn btn-lg secondary btn-print hidden-print' onclick="printHTML('1')"><i class='fa fa-print'></i> FAKTUR </button>
					                <button type="button" class='btn btn-lg secondary btn-print hidden-print' onclick="printHTML('2')"><i class='fa fa-print'></i> FAKTUR DETAIL </button>
					                <button type="button" class='btn btn-lg secondary btn-print hidden-print' onclick="printHTML('3')"><i class='fa fa-print'></i> FAKTUR + SJ </button>
									<iframe id="webprinthtml"  src="" ></iframe>
									
								<?} */?>
				            <?}?>
                            <?foreach ($faktur_link as $row) { ?>
	                            <a style='float:right' href="<?=base_url().is_setting_link('transaction/penjualan_list_detail').'?id='.$row->id;?>" class="btn btn-lg btn-default "><i class='fa fa-angle-double-right'></i> <?=$row->no_faktur_lengkap;?></a>
                            <?}?>

						</div>

					</div>
					
				</div>
			</div>
		</div>


	</div>
</div>

<div id='overlay-div' hidden style="left:0px; top:0px; position:fixed; height:100%; width:100%; background:rgba(0,0,0,0.5)">
	<p style="position:relative;color:#fff;top:40%;left:40%">Loading....</p>
</div>

<script src="<?php echo base_url('assets/global/plugins/bootstrap-modal/js/bootstrap-modalmanager.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/bootstrap-modal/js/bootstrap-modal.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/jquery-validation/js/jquery.validate.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/bootbox/bootbox.min.js'); ?>" type="text/javascript"></script>

<script src="<?php echo base_url('assets_noondev/js/qz-print/dependencies/rsvp-3.1.0.min.js');?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets_noondev/js/qz-print/dependencies/sha-256.min.js');?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets_noondev/js/qz-print/qz-tray.js');?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets_noondev/js/webprint.js'); ?>" type="text/javascript"></script>

<script src="<?php echo base_url('assets/global/plugins/select2/select2.min.js'); ?>" type="text/javascript" ></script>
<script src="<?php echo base_url('assets_noondev/js/form-penjualan.js'); ?>" type="text/javascript"></script>


<script>
	function printHTML(print_type){

	}
</script>
<?include_once "penjualan_detail_script.php";?>