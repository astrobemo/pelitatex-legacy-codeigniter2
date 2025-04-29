<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css'); ?>"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css'); ?>"/>
<?=link_tag('assets/global/plugins/bootstrap-datepicker/css/datepicker3.css'); ?>

<style>
	#table-filter tr td{
		padding: 2px 5px;
	}

	#table-filter input{
		background:white !important;
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
						<form action='' method='get'>
							<table id='table-filter'>
								<tr>
									<td>Toko</td>
									<td class='padding-rl-5'> : </td>
									<td>
										<select style='width:100%' name='toko_id'>
											<?foreach ($this->toko_list_aktif as $row) { ?>
												<option <?=($toko_id == $row->id ? "selected" : "");?>   value="<?=$row->id;?>"><?=$row->nama;?></option>
											<?}?>
										</select>
									</td>
								</tr>
								<tr>
									<td>Supplier</td>
									<td class='padding-rl-5'> : </td>
									<td>
										<select style='width:100%' name='supplier_id' id="supplier_id">
											<option <?=($supplier_id == '' ? "selected" : "");?> value="">Pilih..</option>
											<?foreach ($this->supplier_list_aktif as $row) { ?>
												<option <?=($supplier_id == $row->id ? "selected" : "");?>  value="<?=$row->id;?>"><?=$row->nama;?></option>
											<?}?>
										</select>
									</td>
								</tr>
								<tr>
									<td>Barang</td>
									<td class='padding-rl-5'> : </td>
									<td>
										<select style='width:100%' name='barang_id' id='barang_select'>
											<option value='0'>Semua</option>
											<?foreach ($this->barang_list_aktif as $row) { ?>
												<option <?=($barang_id == $row->id ? "selected" : "");?>   value="<?=$row->id;?>"><?=$row->nama;?></option>
											<?}?>
										</select>
									</td>
								</tr>
								<tr>
									<td>Pisahkan berdasar Warna</td>
									<td class='padding-rl-5'> : </td>
									<td>
										<div class="radio-list">
											<label class="radio-inline">
												<input name='warna_cond' type='radio' value='1' <?=($warna_cond == 1 ? 'checked' : '')?> >Tidak</label>
											<label class="radio-inline">
												<input name='warna_cond' type='radio' value='0' <?=($warna_cond == 0 ? 'checked' : '')?> >Ya</label>
										</div>
									</td>
								</tr>
								<tr>
									<td>Tipe tanggal</td>
									<td class='padding-rl-5'> : </td>
									<td>
										<label><input <?=($tipe == 1 ? 'checked' : '');?> type="radio" onchange="submitFormFilter()" name="tipe" id="tipe1" value='1'>By Tanggal <b>Surat Jalan</b></label>
										<label><input <?=($tipe == 2 ? 'checked' : '');?> type="radio" onchange="submitFormFilter()" name="tipe" id="tipe2" value='2'>By Tanggal <b>Input</b></label>
									</td>
								</tr>
								<tr>
									<td>Periode</td>
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
						<hr/>
						<?
							$d1 = strtotime(is_date_formatter($tanggal_start));
							$d2 = strtotime(is_date_formatter($tanggal_end));

							$bulan_awal = date("m", strtotime(is_date_formatter($tanggal_start))); 
							$bulan_akhir = date("m", strtotime(is_date_formatter($tanggal_end)));

							$tahun_awal = date("Y", strtotime(is_date_formatter($tanggal_start))); 
							$tahun_akhir = date("Y", strtotime(is_date_formatter($tanggal_end))); 

							$tanggal_awal = date("d", strtotime(is_date_formatter($tanggal_start))); 
							$tanggal_akhir = date("d", strtotime(is_date_formatter($tanggal_end))); 
							
							$diff = $d2-$d1;
							$date_diff = round($diff / (60 * 60 * 24)) + 1;

							$tanggal_start = date('Y-m-01', strtotime(is_date_formatter($tanggal_start)));
							// @link http://www.php.net/manual/en/class.datetime.php
							$d1 = new DateTime(is_date_formatter($tanggal_start));
							$d2 = new DateTime(is_date_formatter($tanggal_end));
							$d2->modify('first day of next month');
							// @link http://www.php.net/manual/en/class.dateinterval.php
							$interval = $d2->diff($d1);
							$c_bulan = $interval->format('%m');

							$interval = DateInterval::createFromDateString('1 month');
							$period   = new DatePeriod($d1, $interval, $d2);
							$range_akhir = (iterator_count($period)) - 1;
						?>

						<h2>
							Total Barang Masuk <?=($tipe == 1 ? 'By Tanggal Surat Jalan' : 'By Tanggal Input');?>
						</h2>
						<table class="table table-striped table-bordered table-hover" id="general_table">
							<thead>
								<tr>
									<th scope="col" rowspan='2'>
										No
									</th>
									<th scope="col" rowspan='2'>
										Nama
									</th>
									<?	$idx_tgl = 0;
									// print_r($period);
										foreach ($period as $dt) {
											if ($idx_tgl == 0) {
												if ($bulan_awal != $bulan_akhir || $tahun_akhir != $tahun_awal) {
													$range = $tanggal_awal.' - '.$dt->format('t').' '.$dt->format('F Y'). ' ('.($dt->format('t') - $tanggal_awal + 1).' hari)';
												}else{
													$range = $tanggal_awal.' - '.$tanggal_akhir.' '.$dt->format('F Y').' ('.($date_diff)." hari)";
												}
											}elseif($idx_tgl == $range_akhir ){
												$range = 's/d '.$tanggal_akhir.' '.$dt->format('F Y').' ('.$tanggal_akhir.' hari)';
											}else{
												$range = $dt->format('F Y').' ('.$dt->format('t').' hari)';
											}
										    $qty_total[$dt->format('m')] = 0;
											$roll_total[$dt->format('m')] = 0;
											?>
											<th scope="col" colspan='3' style="border-bottom:1px solid #ddd">
												<?=$range?>
											</th>
											
										<?$idx_tgl++;}?>
									<th scope="col" rowspan='2' style="border-left:1px solid #ddd">
										QTY
									</th>
									<th scope="col" rowspan='2'>
										ROLL
									</th>
								</tr>
								<tr>
									<?foreach ($period as $dt) {?>
										<th scope="col">
											Qty
										</th>
										<th scope="col">
											Roll
										</th>
										<th scope="col">
											Count 
										</th>
										
									<?}?>
								</tr>
							</thead>
							<tbody>
								<?
								$idx = 1; $qty_g_total = 0; $roll_g_total = 0;
								foreach ($barang_list as $row) { 
									$qty = explode('??', $row->qty);
									$jumlah_roll = explode('??', $row->jumlah_roll);
									$count = explode('??', $row->count);
									$bulan = explode('??', $row->bulan);

									$qty = array_combine($bulan, $qty);
									$count = array_combine($bulan, $count);
									$jumlah_roll = array_combine($bulan, $jumlah_roll);
									$qty_subtotal = 0;
									$roll_subtotal = 0;
									?>
									<tr>
										<td><?=$idx?> <?//=print_r($bulan);?> </td>
										<td>
											<span class='nama'><?=$row->nama_beli.($warna_cond == 0 ? ' '.$row->nama_warna : '');?></span> 
										</td>
										<?foreach ($period as $dt) {
											if (isset($qty[(int)$dt->format('m')])) {
												$qty_subtotal += $qty[(int)$dt->format('m')];
												$roll_subtotal += $jumlah_roll[(int)$dt->format('m')];

												$qty_total[$dt->format('m')] += $qty[(int)$dt->format('m')];
												$roll_total[$dt->format('m')] += $jumlah_roll[(int)$dt->format('m')];
												$qty_g_total += $qty[(int)$dt->format('m')];
												$roll_g_total += $jumlah_roll[(int)$dt->format('m')];
												?>
												<td>
													<?=number_format($qty[(int)$dt->format('m')],'0','.','.');?>
												</td>
												<td>
													<?=number_format($jumlah_roll[(int)$dt->format('m')],'0','.','.');?>
												</td>
												<td>
													<?=number_format($count[(int)$dt->format('m')],'0','.','.');?>
												</td>
											<?}else{?>
												<td> - </td>
												<td> - </td>
												<td> - </td>
											<?}?>
										<?}?>
										<td>
											<b>
												<?=number_format($qty_subtotal,'0','.','.');?>
											</b>
										</td>
										<td>
											<b>
												<?=number_format($roll_subtotal,'0','.','.');?>
											</b>
										</td>
										
									</tr>
								<? 
								$idx++;
								} ?>

							</tbody>
						</table>
						<hr/>
						<h2>
							Total Barang Masuk <?=($tipe == 1 ? 'By Tanggal Surat Jalan' : 'By Tanggal Input');?>
						</h2>
						<table class='table'>
							<tr style='font-size:1.1em; font-weight:bold;'>
								<?
									foreach ($period as $dt) {?>
										<th scope="col" colspan='2' style="border-bottom:1px solid #ddd">
											<?=$dt->format('F Y');?>
										</th>
										
									<?}?>
								<th scope="col" rowspan='2' style="border-left:1px solid #ddd">
									QTY
								</th>
								<th scope="col" rowspan='2'>
									ROLL
								</th>
							</tr>
							<tr style='font-size:1.1em; font-weight:bold;'>
								<?foreach ($period as $dt) {?>
									<th>QTY</th>
									<th>ROLL</th>
								<?}?>

							</tr>
							<tr style='font-size:1.2em; font-weight:bold;'>
								<?$qty_grand_total = 0;
								$roll_grand_total = 0;

								foreach ($period as $dt) {
									$qty_grand_total += $qty_total[$dt->format('m')];
									$roll_grand_total += $roll_total[$dt->format('m')]
									?>
									<td>
										<b>
											<?=number_format($qty_total[$dt->format('m')],'0','.','.');?>
										</b>
									</td>
									<td>
										<b>
											<?=number_format($roll_total[$dt->format('m')],'0','.','.');?>
										</b>
									</td>
								<?}?>
								
								<td>
									<?=number_format($qty_grand_total,'0','.','.');?>
								</td>
								<td>
									<?=number_format($roll_grand_total,'0','.','.');?>
								</td>
							</tr>
						</table>
					</div>
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

	$('#barang_select, #supplier_id').select2();

	// oTable = $('#general_table').DataTable();
	// oTable.state.clear();
	// oTable.destroy();
});
</script>
