<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css'); ?>"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css'); ?>"/>
<?=link_tag('assets/global/plugins/bootstrap-datepicker/css/datepicker3.css'); ?>
<link href="<?=base_url('assets/global/plugins/bootstrap-modal/css/bootstrap-modal.css');?>" rel="stylesheet" type="text/css"/>

<?=link_tag('assets/global/plugins/select2/select2.css'); ?>
<link href="<?=base_url('assets_noondev/css/bootstrap-modal-bs3patch.css');?>" rel="stylesheet" type="text/css"/>

<style type="text/css">
#general_table tr th{
	vertical-align: middle;
	text-align: center;
	/*font-size: 0.95em;*/

}

#general_table tr td{
	color:#000;
	/*font-size: 0.8em;*/
	/*font-family: Arial;*/
	/*font-size: 12px;*/
}

#general_table{
	border-bottom: 2px solid #ddd;
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
						<div class="actions">
							
						</div>
					</div>
					<div class="portlet-body">
						<table width="100%">
							<tr>
								<td>
									<form action='' method='get'>
										<table>
											<tr>
												<td>Tanggal</td>
												<td>:</td>
												<td>
													<b>
														<input name='tanggal_start' readonly class='date-picker' style='border:none; border-bottom:1px solid #ddd; width:100px;' value='<?=$tanggal_start;?>'>
														s/d
														<input name='tanggal_end' readonly class='date-picker2 ' style='border:none; border-bottom:1px solid #ddd; width:100px;' value='<?=$tanggal_end;?>'> 
													</b>
												</td>
												<td>
													<button class='btn btn-xs default'><i class='fa fa-search'></i></button>
												</td>
											</tr>
											<tr hidden>
												<td>Tipe </td>
												<td>: </td>
												<td>
													<b>
														<select name='tipe_search'>
															<option <?=($tipe_search == 1 ? "selected" : "");?> value='1'>Semua</option>
															<option <?=($tipe_search == 2 ? "selected" : "");?> value='2'>Lunas (Cash)</option>
															<option <?=($tipe_search == 3 ? "selected" : "");?> value='3' >Lunas (Kredit)</option>
															<option <?=($tipe_search == 4 ? "selected" : "");?> value='4'>Belum Lunas</option>
														</select>
													</b>
												</td>
												<td></td>
											</tr>
											<tr hidden>
												<td>Customer</td>
												<td> : </td>
												<td>
													<b>
														<select name='customer_id' id="select_customer" style='width:100%'>
															<option <?=($customer_id == 0 ? "selected" : "");?> value='0'>Semua</option>
															<?foreach ($this->customer_list_aktif as $row) { ?>
																<option <?=($customer_id == $row->id ? "selected" : "");?> value="<?=$row->id?>"><?=$row->nama;?></option>
															<?}?>
														</select>
													</b>
												</td>
												<td></td>
											</tr>
											<tr>
												<td>NOTES</td>
												<td> : </td>
												<td>
													TUTUP BUKU : <b><?=strtoupper(date('F Y', strtotime($tgl_tutup_buku)))?></b>
												</td>
												<td></td>
											</tr>
										</table>
									</form>
								</td>
								<td class='text-right'>
									<form action='<?=base_url()?>report/penjualan_laba_list_report_excel' method='get'>
										<input name='tanggal_start' hidden  value='<?=$tanggal_start;?>'>
										<input name='tanggal_end' hidden value='<?=$tanggal_end;?>'> 
										<input name='tipe' hidden value='<?=$tipe;?>'> 
										<select hidden name='tipe_search'>
											<option <?=($tipe_search == 1 ? "selected" : "");?> value='1'>Semua</option>
											<option <?=($tipe_search == 2 ? "selected" : "");?> value='2'>Lunas (Cash)</option>
											<option <?=($tipe_search == 3 ? "selected" : "");?> value='3' >Lunas (Kredit)</option>
											<option <?=($tipe_search == 4 ? "selected" : "");?> value='4'>Belum Lunas</option>
										</select>
										<select hidden name='customer_id'>
											<option <?=($customer_id == 0 ? "selected" : "");?> value='0'>Semua</option>
											<?foreach ($this->customer_list_aktif as $row) { ?>
												<option <?=($customer_id == $row->id ? "selected" : "");?> value="<?=$row->id?>"><?=$row->nama;?></option>
											<?}?>
										</select>
										<button class="btn btn-lg <?=($tipe == 1 ? 'green' : 'blue');?> "><i class="fa fa-download"></i> EXCEL</button>
									</form>
								</td>
							</tr>
						</table>
						<hr/>
						<!-- table-striped table-bordered  -->
						<table class="table table-bordered table-hover table-striped " id="general_table">
							<thead>
								<tr style='background:#eee' >
									<th scope="col" style='width:90px !important;'>
										No Faktur
									</th>
									<th scope="col">
										Tanggal<br/> Penjualan
									</th>
									<th scope="col">
										Nama <br/> Customer
									</th>
									<th scope="col">
										Jumlah <br/>
										Yard/KG
									</th>
									<th scope="col">
										Jumlah <br/> Roll
									</th>
									<th scope="col" style='min-width:250px !important'>
										Nama Barang
									</th>
									<th scope="col">
										Harga
									</th>
									<th scope="col">
										Total<br>Jual 1(+ppn)
									</th>
									<!-- <th scope="col">
										Diskon
									</th>
									<th scope="col">
										Ongkir
									</th> -->
									<th scope="col">
										HPP 1 (+ppn)
									</th>
									<th scope="col">
										Selisih 1
									</th>
									<th scope="col">
										Total<br>Jual 2
									</th>
									<th scope="col">
										HPP2
									</th>
									<th scope="col">
										Selisih 2
									</th>
								</tr>
							</thead>
							<tbody>
								<?

								foreach ($data_hpp as $row) {
									$hpp2[$row->barang_id][$row->warna_id] = $row->hpp;
								}
								$idx = 0; 
								$g_total = 0; 
								$g_total2 = 0; 
								$g_total_hpp = 0;
								$g_total_hpp2 = 0;
								$yard_total = 0; $roll_total = 0; 
								$g_total_untung = 0;
								$g_total_untung2 = 0;
								foreach ($penjualan_list as $row) { 
									$ppn = $row->ppn_berlaku;
									$ppn_pengali = $ppn/100;
									$ppn_pembagi = 1+$ppn_pengali;
										$qty = ''; $jumlah_roll = ''; $nama_barang = ''; $harga_jual = '';
										if ($row->qty != '') {
											$qty = explode('??', $row->qty);
											$jumlah_roll = explode('??', $row->jumlah_roll);
											$nama_barang = explode('??', $row->nama_barang);
											if (is_posisi_id() != 2) {
												$nama_barang = explode('??', $row->nama_barang_jual);
											}
												# code...
											$harga_jual = explode('??', $row->harga_jual);
											$hpp = explode('??', $row->hpp);
											$untung = array();
											$untung2 = array();

											$barang_id = explode('??', $row->barang_id);
											$warna_id = explode('??', $row->warna_id);
										}
									?>
									<tr class='text-center' >
										<td>
											<?=$row->no_faktur;?>
										</td>
										<td>
											<?=is_reverse_date($row->tanggal);?>
										</td>
										<td>
											<?=$row->nama_customer;?> 
										</td>
										<td>
											<?
											if ($qty != '') {
												$baris = count($qty);
												$j = 1; $idx = 1;
												foreach ($qty as $key => $value) {
													$yard_total += $value;
													$roll_total += $jumlah_roll[$key];
													echo str_replace(',00', '', number_format($value,'2',',','.')).'<br/>';
													if ($j % 3 == 0 && $baris != $idx) {
														echo "<hr style='margin:2px'/>";
														$j = 1;
													}else{													
														$j++;
													}
													$idx++;
												}
											}
											?>
										</td>
										<td>
											<?
											if ($jumlah_roll != '') {
												$j = 1; $idx = 1;
												foreach ($jumlah_roll as $key => $value) {
													echo str_replace(',00', '', number_format($value,'2',',','.')).'<br/>';
													if ($j % 3 == 0 && $baris != $idx) {
														echo "<hr style='margin:2px'/>";
														// echo '---<br/>';
														$j = 1;
													}else{													
														$j++;
													}
													$idx++;
												}
											}

											?>
										</td>
										<td>
											<span class='id' hidden="hidden"><?=$row->id;?></span>
											<?
											if ($nama_barang != '') {
												$j = 1; $idx = 1;
												foreach ($nama_barang as $key => $value) {
													echo "<span class='nama'>".$value."</span>".'<br/>';
													if ($j % 3 == 0 && $baris != $idx) {
														echo "<hr style='margin:2px'/>";
														// echo '---<br/>';
														$j = 1;
													}else{													
														$j++;
													}
													$idx++;
												}
											}
											?>
											
											
										</td>
										<td>
											<?
											if ($harga_jual != '') {
												$j = 1; $idx = 1;
												foreach ($harga_jual as $key => $value) {
													echo str_replace(',00', '', number_format($value,'2',',','.')).'<br/>';
													if ($j % 3 == 0 && $baris != $idx) {
														echo "<hr style='margin:2px'/>";
														// echo '---<br/>';
														$j = 1;
													}else{													
														$j++;
													}
													$idx++;
												}
											}?>
											<b>Subtotal:</b>
										</td>
										<td>
											
											<?
											$subtotal = 0; 
											if ($harga_jual != '') {
												$j = 1; $idx = 1;
												foreach ($harga_jual as $key => $value) {
													echo number_format(($qty[$key] * $value),'0',',','.').'<br/>';
													$subtotal +=($qty[$key] * $value); 
													$g_total += ($qty[$key] * $value);

													if ($j % 3 == 0 && $baris != $idx) {
														echo "<hr style='margin:2px'/>";
														// echo '---<br/>';
														$j = 1;
													}else{													
														$j++;
													}
													$idx++;
												}
											}
											
											?>
											<b><?=number_format($subtotal,'0',',','.')?></b>
										</td>
										<td class='hpp1'>
											<?
											$j = 1; $idx = 1; $total_hpp = 0;
											if ($harga_jual != '') {
												foreach ($harga_jual as $key => $value) {
													$total_hpp += $qty[$key] * $hpp[$key];
													$untung[$key] = (($qty[$key] * $value)) - ($qty[$key] * $hpp[$key]);
													echo number_format($qty[$key] * $hpp[$key],'0',',','.').'<br/>';
													
													if ($j % 3 == 0 && $baris != $idx) {
														echo "<hr style='margin:2px'/>";
														// echo '---<br/>';
														$j = 1;
													}else{													
														$j++;
													}
													$idx++;
												}
											}

											$g_total_hpp += $total_hpp;
											?>
											<b><?=number_format($total_hpp,'0',',','.');?></b>
										</td>
										<td class='hpp1'>
											<?
											$j = 1; $idx = 1; $total_untung = 0;
											if ($harga_jual != '') {
												foreach ($untung as $key => $value) {
													$total_untung += $value;
													echo number_format($value,'0',',','.').'<br/>';
													
													if ($j % 3 == 0 && $baris != $idx) {
														echo "<hr style='margin:2px'/>";
														// echo '---<br/>';
														$j = 1;
													}else{													
														$j++;
													}
													$idx++;
												}
											}
											$g_total_untung += $total_untung;

											?>
											<b><?=number_format($total_untung,'0',',','.')?></b>
										</td>
										<td>
											
											<?
											$subtotal = 0; 
											if ($harga_jual != '') {
												$j = 1; $idx = 1;
												foreach ($harga_jual as $key => $value) {
													$n = $qty[$key] * $value/$ppn_pembagi;
													echo number_format($n,'0',',','.').'<br/>';
													$subtotal +=$n; 
													$g_total2 += $n;

													if ($j % 3 == 0 && $baris != $idx) {
														echo "<hr style='margin:2px'/>";
														// echo '---<br/>';
														$j = 1;
													}else{													
														$j++;
													}
													$idx++;
												}
											}
											
											?>
											<b><?=number_format($subtotal,'0',',','.')?></b>
										</td>
										
										<td class='hpp2'>
											<?
											$j = 1; $idx = 1; $total_hpp = 0;
											if ($harga_jual != '') {
												foreach ($harga_jual as $key => $value) {
													$h = 0;
													if (isset($hpp2[$barang_id[$key]][$warna_id[$key]])) {
														$h = $hpp2[$barang_id[$key]][$warna_id[$key]];
													}
													$total_hpp += $qty[$key] * $h;
													$untung2[$key] = ((($qty[$key] * $value)/$ppn_pembagi) - ($qty[$key] * $h));
													echo "<div style='".($h == 0 ? 'background:orange' : '')."' >".number_format($qty[$key] * $h,'0',',','.')."</div>";
													
													if ($j % 3 == 0 && $baris != $idx) {
														echo "<hr style='margin:2px'/>";
														// echo '---<br/>';
														$j = 1;
													}else{													
														$j++;
													}
													$idx++;
												}
											}

											$g_total_hpp2 += $total_hpp;
											?>
											<b><?=number_format($total_hpp,'0',',','.');?></b>
										</td>
										<td class='hpp2'>
											<?
											$j = 1; $idx = 1; $total_untung = 0;
											if ($harga_jual != '') {
												foreach ($untung2 as $key => $value) {
													$total_untung += $value;
													echo number_format($value,'0',',','.').'<br/>';
													
													if ($j % 3 == 0 && $baris != $idx) {
														echo "<hr style='margin:2px'/>";
														// echo '---<br/>';
														$j = 1;
													}else{													
														$j++;
													}
													$idx++;
												}
											}
											$g_total_untung2 += $total_untung;

											?>
											<b><?=number_format($total_untung,'0',',','.')?></b>
										</td>
									</tr>
									<!-- <tr style='font-weight:bold; text-align:center'>
										
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td class='text-right'>subtotal</td>
										<td>
											Rp<?=number_format($subtotal,'0',',','.')?>
										</td>
										<td></td>
										<td></td>
									</tr> -->
								<? $idx++;} ?>
							</tbody>
						</table>

						<hr/>
						<table class='table table-bordered'>
							<tr>
								<th>TOTAL</th>
								<th>TRX</th>
								<th>YARD</th>
								<th>ROLL</th>
								<th hidden ></th>
								<th > JUAL 1 <small>(+ppn)</small> </th>
								<th class='hpp1'> HPP1 <small>(+ppn)</small></th>
								<th class='hpp1'> KEUNTUNGAN </th>
								<th > JUAL 2 </th>
								<th class='hpp2'> HPP2 </th>
								<th class='hpp2'> KEUNTUNGAN </th>
							</tr>
							<tr style='font-size:1.2em;font-weight:bold'>
								<td class='text-center'>TOTAL</td>
								<td class='text-center'><?=count($penjualan_list);?></td>
								<td class='text-center'><?=number_format($yard_total,'2',',','.');?></td>
								<td class='text-center'><?=number_format($roll_total,'0',',','.');?></td>
								<td class='text-center'><b><?=number_format($g_total,'0',',','.');?></b> </td>
								<td class='hpp1'><b><?=number_format($g_total_hpp,'0',',','.');?></b></td>
								<td class='hpp1'><b><?=number_format($g_total_untung,'0',',','.');?></b></td>
								<td class='text-center'><b><?=number_format($g_total2,'0',',','.');?></b> </td>
								<td class='hpp2'><b><?=number_format($g_total_hpp2,'0',',','.');?></b></td>
								<td class='hpp2'><b><?=number_format($g_total_untung2,'0',',','.');?></b></td>
								<!-- <td></td>
								<td></td> -->
							</tr>
						</table>
					</div>

					<?/*<form action='<?=base_url();?>report/penjualan_list_export_excel' method='get'>
						<input name='tanggal_start' value='<?=$tanggal_start;?>' hidden='hidden'>
						<input name='tanggal_end' value='<?=$tanggal_end;?>' hidden='hidden'>
						<input name='tipe_search' value='<?=$tipe_search;?>' hidden='hidden'>
						<input name='customer_id' value='<?=$customer_id;?>' hidden='hidden'>

						<button <?=($status_excel==0 || $idx ==0 ? "disabled" : "");?> class='btn green'><i class='fa fa-download'></i> Excel</button>
					</form>*/?>
				</div>
			</div>
		</div>
	</div>			
</div>

<script src="<?php echo base_url('assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/datatables/media/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets_noondev/js/table-advanced.js'); ?>"></script>

<script src="<?php echo base_url('assets/global/plugins/select2/select2.min.js'); ?>" type="text/javascript" ></script>
<script>
jQuery(document).ready(function() {
	// Metronic.init(); // init metronic core components
	// Layout.init(); // init current layout
	// TableAdvanced.init();

	$("#general_table").DataTable({
		"ordering":false,
		// "bFilter":false
	});

	$('#select_customer').select2({});

});
</script>
