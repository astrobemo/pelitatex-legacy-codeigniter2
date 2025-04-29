<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css'); ?>"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css'); ?>"/>
<?=link_tag('assets/global/plugins/bootstrap-datepicker/css/datepicker3.css'); ?>


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
							<table>
								<tr>
									<td>Customer</td>
									<td class='padding-rl-5'> : </td>
									<td>
										<select style='width:100%' name='customer_id' id="customer_id">
											<option <?=($customer_id == '' ? "selected" : "");?> value="">Semua</option>
											<?foreach ($this->customer_list_aktif as $row) { ?>
												<option <?=($customer_id == $row->id ? "selected" : "");?>  value="<?=$row->id;?>"><?=$row->nama;?></option>
											<?}?>
										</select>
									</td>
								</tr>
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
									<td>Barang</td>
									<td class='padding-rl-5'> : </td>
									<td>
										<select style='width:100%' name='barang_id' id='barang_select'>
											<option value='0'>Semua</option>
											<?foreach ($this->barang_list_aktif as $row) { ?>
												<option <?=($barang_id == $row->id ? "selected" : "");?>   value="<?=$row->id;?>"><?=$row->nama_jual;?></option>
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
												<input name='warna_cond' type='radio' value='1' <?=($warna_cond == 1 ? 'checked' : '')?> >Tidak, Hanya spek saja</label>
											<label class="radio-inline">
												<input name='warna_cond' type='radio' value='0' <?=($warna_cond == 0 ? 'checked' : '')?> >Ya</label>
										</div>
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
													$range = $tanggal_awal.($date_diff > 1 ? ' - '.$tanggal_akhir.' ' : ' ').$dt->format('F Y').' ('.($date_diff)." hari)";
												}
											}elseif($idx_tgl == $range_akhir ){
												$range = 's/d '.$tanggal_akhir.' '.$dt->format('F Y').' ('.$tanggal_akhir.' hari)';
											}else{
												$range = $dt->format('F Y').' ('.$dt->format('t').' hari)';
											}
										    $qty_total[$dt->format('m')] = 0;
											$roll_total[$dt->format('m')] = 0;
											$count_total[$dt->format('m')] = 0;

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
											<span class='nama'><?=$row->nama_jual.($warna_cond == 0 ? ' '.$row->nama_warna : '');?> <?=(is_posisi_id() == 1 ? $row->warna_id : "");?></span> 
										</td>
										<?foreach ($period as $dt) {
											if (isset($qty[(int)$dt->format('m')])) {
												$qty_subtotal += $qty[(int)$dt->format('m')];
												$roll_subtotal += $jumlah_roll[(int)$dt->format('m')];

												$qty_total[$dt->format('m')] += $qty[(int)$dt->format('m')];
												$roll_total[$dt->format('m')] += $jumlah_roll[(int)$dt->format('m')];
												$qty_g_total += $qty[(int)$dt->format('m')];
												$roll_g_total += $jumlah_roll[(int)$dt->format('m')];
												$count_total[$dt->format('m')] += $count[(int)$dt->format('m')];
												?>
												<td>
													<?=number_format($qty[(int)$dt->format('m')],'0','.',',');?>
												</td>
												<td>
													<?=number_format($jumlah_roll[(int)$dt->format('m')],'0','.',',');?>
												</td>
												<td>
													<?=number_format($count[(int)$dt->format('m')],'0','.',',');?>
												</td>
											<?}else{?>
												<td> - </td>
												<td> - </td>
												<td> - </td>
											<?}?>
										<?}?>
										<td>
											<b>
												<?=number_format($qty_subtotal,'0','.',',');?>
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

						<div style="width:100%; overflow-x:scroll">
							<table class='table table-bordered' id="tableRekap">
								<thead>
									<tr>
									<th rowspan="2">Periode</th>

										<?$span = (count($this->satuan_list_aktif)*2) + 1; $border_top="border-top:1px solid #ddd;"?>
										<th colspan="<?=$span?>">Penjualan</th>
										<th hidden colspan="<?=$span?>">Lain</th>
									</tr>
									<tr>
										<?foreach ($this->satuan_list_aktif as $row) {?>
											<th style="<?=$border_top?>"><?=$row->nama;?></th>
											<th style="<?=$border_top?>">Roll</th>
										<?}?>
										<th style="<?=$border_top?>">Trx</th>
										<?foreach ($this->satuan_list_aktif as $row) {?>
											<th hidden style="<?=$border_top?>"><?=$row->nama;?></th>
											<th hidden style="<?=$border_top?>">Roll</th>
										<?}?>
										<th hidden style="<?=$border_top?>">Trx</th>
									</tr>
								</thead>
								<tbody></tbody>
								<tfoot></tfoot>
								<?/*
									<tr style='font-size:1.1em; font-weight:bold;'>
										<?
											foreach ($period as $dt) {?>
												<th scope="col" colspan='3' style="text-align:center;border-bottom:1px solid #ddd">
													<?=$dt->format('F Y');?>
												</th>
												
											<?}?>
										<th scope="col" colspan='2' style="border-left:1px solid #ddd">
											TOTAL
										</th>
									</tr>
									<tr style='font-size:1.1em; font-weight:bold;'>
										<?foreach ($period as $dt) {?>
											<th>QTY</th>
											<th>ROLL</th>
											<th>TRX</th>
										<?}?>
										<th scope="col" style="border-left:1px solid #ddd">
											QTY
										</th>
										<th scope="col">
											ROLL
										</th>
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
											<td>
												<b>
													<?=number_format($count_total[$dt->format('m')],'0','.','.');?>
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
								*/?>
							</table>

						</div>
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

	var satuan_list_aktif = <?=json_encode($this->satuan_list_aktif)?>;
jQuery(document).ready(function() {
	// Metronic.init(); // init metronic core components
	// Layout.init(); // init current layout
	// TableAdvanced.init();

	$("#general_table").DataTable({
		"ordering":true,
		// "bFilter":false
	});

	$('#barang_select, #customer_id').select2();

	// oTable = $('#general_table').DataTable();
	// oTable.state.clear();
	// oTable.destroy();

	get_barang_rekap();

	
});

async function get_barang_rekap(){
	const customer_id = "<?=$customer_id?>";
	const toko_id=  1;
	const barang_id = "<?=$barang_id?>";
	const warna_cond = "<?=$warna_cond?>";
	const tanggal_start = "<?=$tanggal_start?>";
	const tanggal_end = "<?=$tanggal_end?>";

	const param = `tanggal_start=${tanggal_start}&tanggal_end=${tanggal_end}&customer_id=${customer_id}&toko_id=${toko_id}&barang_id=${barang_id}&warna_cond=${warna_cond}`;
	const response = await fetch(baseurl+`report/get_barang_rekap?${param}`, {
      method: "GET",
    });
    const result = await response.json();

	totalQtyJual = {};
	totalRollJual = {};
	totalQtyLain = {};
	totalRollLain = {};
	totalTrx = 0;
	totalTrxLain = 0;
	satuan_list_aktif.forEach((sat,index) => {
		totalQtyJual[sat.id] = 0;
		totalRollJual[sat.id] = 0;
		
		totalQtyLain[sat.id] = 0;
		totalRollLain[sat.id] = 0;
	});

	const rows = result.map((row,index)=>{

		const trxData = row.jml_trx_data.split(",");
		const tipeData = row.tipe_data.split(",");
		let subTrx = 0
		trxData.forEach(trx=>subTrx += parseInt(trx) );
		
		totalTrx += parseInt(trxData[0]);
		console.log(totalTrx)
		

		let barisJual = "";
		let barisLain = "";
		let dQty = "";
		satuan_list_aktif.forEach((sat, ind) => {

			if (row[`qty_${sat.id}`] !== 'undefined') {
				const q = row[`qty_${sat.id}`].split(",");
				const jR = row[`jumlah_roll_${sat.id}`].split(",");
				let qS = 0;
				let rS = 0;
				if (tipeData[0] == 1) {
					const n = new Intl.NumberFormat(["ban", "id"]).format(q[0]);
					const r = new Intl.NumberFormat(["ban", "id"]).format(jR[0]);
					totalQtyJual[sat.id] += parseFloat(q[0]);
					totalRollJual[sat.id] += parseFloat(jR[0]);
					barisJual += `<td class='text-right'>${n}</td>
						<td class='text-right'>${r}</td>
						`;
				}else{
					totalQtyLain[sat.id] += parseFloat(q[0]);
					totalRollLain[sat.id] += parseFloat(jR[0]);
					barisJual = `<td></td>
						<td></td>`
					barisLain += `
					<td class='text-right'>${parseFloat(q[0])}</td>
					<td>${jR[0]}</td>`;
				}

				if (typeof tipeData[1] !== 'undefined') {
					const n = new Intl.NumberFormat(["ban", "id"]).format(q[1]);
					totalQtyLain[sat.id] += parseFloat(q[1]);
					totalRollLain[sat.id] += parseFloat(jR[1]);
					barisLain += `<td class='text-right'>${n}</td>
						<td>${jR[1]}</td>
						`;
				}else if(tipeData[0] == 1){
					barisLain = `<td></td>
						<td></td>
						`;
				}

				
				
			}else{
				barisJual += '<td></td><td></td>';
				barisLain += '<td></td><td></td>';
			}
		})

			const n = new Intl.NumberFormat(["ban", "id"]).format(trxData[0]);
				const o = new Intl.NumberFormat(["ban", "id"]).format(trxData[1]);
		dQty = barisJual + 
			`<td style="text-align:right">${tipeData[0] == 1 ? n : ''}</td>`
			// +barisLain +
			// `<td>${tipeData[0] == 1 ? (typeof trxData[1] !== 'undefined' ? o : '') : ''}</td>`
		;

		return `<tr>
			<td>${row.nama_bulan} ${row.tahun}</td>
			${dQty}
		</tr>`;
		
	})
	console.log(rows)

	let tFoot = '<th></th>';
	satuan_list_aktif.forEach(sat=>{
		const n = new Intl.NumberFormat(["ban", "id"]).format(totalQtyJual[sat.id]);
		const x = new Intl.NumberFormat(["ban", "id"]).format(totalRollJual[sat.id]);
		tFoot += `<th style="font-size:1.1em; text-align:center">${n}</th>`;
		tFoot += `<th style="font-size:1.1em; text-align:center">${x}</th>`;
	});
	const n = new Intl.NumberFormat(["ban", "id"]).format(totalTrx);

	tFoot += `<th style="font-size:1.1em; text-align:center">${n}</th>`;

	$("#tableRekap tbody").html(rows);
	$("#tableRekap tfoot").html(`<tr>${tFoot}</tr>`);

}

</script>
