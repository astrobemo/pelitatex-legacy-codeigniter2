<?php echo link_tag('assets/global/plugins/jqvmap/jqvmap/jqvmap.css'); ?>
<?php echo link_tag('assets/global/plugins/morris/morris.css'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css'); ?>"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css'); ?>"/>
<?php echo link_tag('assets/global/plugins/bootstrap-datepicker/css/datepicker3.css'); ?>

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

#stok-info{
	font-size: 1.5em;
	position: absolute;
	right: 50px;
	top: 30px;
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

#bayar-data tr td{
	font-size: 1.5em;
	font-weight: bold;
	padding: 0 10px 0 10px;
}

#bayar-data tr td input{
	padding: 0 5px 0 5px;
	border: 1px solid #ddd;
}

.table-scrollable{
	border: none;
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
							<span class="caption-subject theme-font bold uppercase">Data Barang</span>
						</div>
					</div>
					<div class="portlet-body">
						<table style='font-size:1.2em'>
							<?foreach ($data_barang as $row) { ?>
								<tr>
									<td>
										Nama Beli
									</td>
									<td class='padding-rl-25'>
										:
									</td>
									<td>
										<span style='font-size:1.3em'><?=$row->nama;?></span>
									</td>
								</tr>
								<tr>
									<td>
										Nama Jual
									</td>
									<td class='padding-rl-25'>
										:
									</td>
									<td style='font-size:1.3em'>
										<?=$row->nama_jual;?>
									</td>
								</tr>
								<tr>
									<td style='vertical-align:top' >Deskripsi</td>
									<td class='padding-rl-25' style='vertical-align:top'>
										:
									</td>
									<td>
										<?=$row->deskripsi_info?>
									</td>
								</tr>
							<?}?>
							<tr>
								<td style='vertical-align:top' >Warna Terdaftar</td>
								<td class='padding-rl-25' style='vertical-align:top'>
									:
								</td>
								<td>
									<div style='column-count:4'>
										<?foreach ($data_warna as $row) { 
											$qty_warna[$row->warna_id] = 0;?>
											- <?=$row->warna_jual;?> [<?=$row->warna_id;?>] <sup><span style="color:blue; cursor:pointer;"  class="show-warna" data-warna="<?=$row->warna_id;?>">show</span></sup> <br/>
										<?}?>
									</div>
								</td>
							</tr>
						</table>
					</div>
					
				</div>
			</div>

			<div class="col-md-12">
				<div class="portlet light">
					<div class="portlet-body">

						<div>
							<h4>Transaksi By Tanggal</h4>
								<div class="row list-separated">
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<h4><b>Grafik Penjualan Barang Harian</b> </h4>
										<form>
											<select name='tahun' hidden>
												<?for ($i=2019; $i <= date('Y') ; $i++) { ?>
													<option value="<?=$i?>" <?=($i == $tahun ? 'selected' : '');?> ><?=$i;?></option>
												<?}?>
											</select>
											<input name='tanggal_awal' class='date-picker' value="<?=is_reverse_date($tanggal_awal)?>"> s/d 
											<input name='tanggal_awal' class='date-picker' value="<?=is_reverse_date($tanggal_akhir)?>">
										</form>
										<?if (is_posisi_id() == 1) {?>
											<div id="sales_statistics" class="portlet-body-morris-fit morris-chart" style="height: 200px; padding:20px;">
											</div>
											<hr/>
											# code...
										<?}else{?>
											<div>
												Working on Bugs
											</div>
										<?}?>
									</div>
								</div>
					</div>
					</div>
					</div>
					</div>


			<div class="col-md-12">
				<div class="portlet light">
					<div class="portlet-title">
						<div class="caption caption-md">
							<i class="icon-bar-chart theme-font hide"></i>
							<span class="caption-subject theme-font bold uppercase">History</span>
							<form>TAHUN : 
								<select name='tahun'>
									<?for ($i=2019; $i <= date('Y') ; $i++) { ?>
										<option value="<?=$i?>" <?=($i == $tahun ? 'selected' : '');?> ><?=$i;?></option>
									<?}?>
								</select>
								<button class='btn btn-md green'>OK</button>
							</form>
						</div>
					</div>
					<div class="portlet-body">
						

						<h4><b>Daftar Transaksi <?=$tahun;?></b> </h4>
						<table class="table table-bordered" style='width:100%' id="general_table">
							<?$data_jual = (array)$data_penjualan;
							$data_show = array();
							foreach ($data_jual as $key => $value) {
								$data_show[$value->bulan_jual] = $value;
								$total_trx[$value->bulan_jual] = 0;
								$penjualan_id_list[$value->bulan_jual] = array();
							}
							foreach ($data_show as $i => $value) {
								$warna_id_list = explode(",", $value->warna_id);
								$penjualan_data[$i] = explode(",", $value->penjualan_data);
								$count_trx_data[$i] = explode("=?=", $value->penjualan_id);

								foreach ($count_trx_data[$i] as $key2 => $value2) {
									$break = explode("??", $value2);
									foreach ($break as $key3 => $value3) {
										$break2 = explode(",", $value3);
										foreach ($break2 as $key4 => $value4) {
											array_push($penjualan_id_list[$i], $value4);
										}
									}
								}

								$count_trx_warna[$i] = array_combine($warna_id_list, $count_trx_data[$i]);
								foreach ($count_trx_warna[$i] as $key2 => $value2) {
									$total_trx_warna[$i][$key2] = array();
								}
								foreach ($count_trx_warna[$i] as $key2 => $value2) {
									$break = explode("??", $value2);
									foreach ($break as $key3 => $value3) {
										$break2 = explode(",", $value3);
										foreach ($break2 as $key4 => $value4) {
											array_push($total_trx_warna[$i][$key2], $value4);
										}
									}
									$total_trx_warna[$i][$key2] = array_unique($total_trx_warna[$i][$key2]);
								}

								$penjualan_id_list[$i] = array_unique($penjualan_id_list[$i]);

								$total_trx[$i] = count($penjualan_id_list[$i]);
								$penjualan_data[$i] = array_combine($warna_id_list, $penjualan_data[$i]);
								// $count_trx_data[$i] = array_combine($warna_id_list, $count_trx_data[$i]);

									# code...
								
							}
							?>

							<tr>
								<th>Bulan</th>
								<?for ($i=1; $i <=6 ; $i++) { ?>
									<th colspan='2' class='text-center'><?=date('F',strtotime($tahun.'-'.$i.'-1'));?></th>
								<?}?>
							</tr>
							<tr style="font-size:1.2em;">
								<th>TOTAL</th>
								<?for ($i=1; $i <=6 ; $i++) { ?>
									<td class='text-center' style='font-size:1.1em'>
										<?if (isset($data_show[$i])) {
											echo number_format($data_show[$i]->penjualan,'0',',','.');
										}else{echo '--';}?>
									</td>
									<td>
										<?if (isset($data_show[$i])) {
											echo number_format($total_trx[$i],'0',',','.');
										}?>
									</td>
								<?}?>
							</tr>
							<?foreach ($data_warna as $row) {?>
								<tr class="data-<?=$row->warna_id;?>" hidden>
									<th><?=$row->warna_jual;?></th>
									<?for ($i=1; $i <=6 ; $i++) {?> 
										<td class='text-center'><?=(isset($penjualan_data[$i][$row->warna_id]) ? number_format($penjualan_data[$i][$row->warna_id],'0',',','.') : '--' );?></td>
										<td>
											<?=(isset($total_trx_warna[$i][$row->warna_id]) ? count($total_trx_warna[$i][$row->warna_id]) : '');?>
										</td>
									<?}?>
								</tr>	
							<?}?>

							<tr>
								<th>Bulan</th>
								<?for ($i=7; $i <=12 ; $i++) { ?>
									<th colspan='2' class='text-center'><?=date('F',strtotime($tahun.'-'.$i.'-1'));?></th>
								<?}?>
							</tr>
							<tr style="font-size:1.2em;">
								<th>TOTAL</th>
								<?for ($i=7; $i <=12 ; $i++) { ?>
									<td class='text-center' style='font-size:1.1em'>
										<?if (isset($data_show[$i])) {
											echo number_format($data_show[$i]->penjualan,'0',',','.');
										}else{echo '--';}?>
									</td>
									<td>
										<?if (isset($data_show[$i])) {
											echo number_format($data_show[$i]->count_trx,'0',',','.');
										}?>
									</td>
								<?}?>
							</tr>
							<?foreach ($data_warna as $row) {?>
								<tr class="data-<?=$row->warna_id;?>" hidden>
									<th><?=$row->warna_jual;?></th>
									<?for ($i=7; $i <=12 ; $i++) {?> 
										<td class='text-center'><?=(isset($penjualan_data[$i][$row->warna_id]) ? number_format($penjualan_data[$i][$row->warna_id],'0',',','.') : '--' );?></td>
										<td>
											<?=(isset($total_trx_warna[$i][$row->warna_id]) ? count($total_trx_warna[$i][$row->warna_id]) : '');?>
										</td>
									<?}?>
								</tr>
							<?}?>
							
						</table>

						<hr/>
						<table class="table table-bordered table-hover table-striped">
							<tr>
								<th>Banyak Data</th>
								<th>Jumlah/Yard</th>
								<th>Jumlah Roll</th>
								<th>Total Nilai</th>
							</tr>
							<tr style='font-size:1.2em;font-weight:bold'>
							</tr>
						</table>

						<?if (is_posisi_id() <= 3) {?>
							<div class="row list-separated">
								<div class="col-xs-12">
									<h4><b>Chart Rekap Transaksi Per Warna <?=$tahun;?></b> </h4>
									<div id="chart_1" class="chart" style="height: 300px;">
									</div>
								</div>
								<div class="col-xs-12">
									<h4><b>Penjualan Warna Terbanyak <?=$tahun;?></b> </h4>

									<div id="chart_pie_1" class="chart" style="height: 300px;">
									</div>
									
								</div>

								<div class="col-xs-12">
									<h4><b>Chart Rekap Per Customer <?=$tahun;?></b> </h4>
									<div id="chart_2" class="chart" style="height: 300px;">
									</div>
								</div>

							</div>
							
						<?}?>
					</div>

					<span id='barang_id_data' hidden><?=$barang_id;?></span>
					<span id='tahun_data' hidden><?=$tahun;?></span>
					
				</div>
			</div>
		</div>
	</div>			
</div>

<script src="<?php echo base_url('assets/global/plugins/datatables/media/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js'); ?>" type="text/javascript"></script>


<script src="<?=base_url('assets/global/plugins/morris/morris.min.js');?>" type="text/javascript"></script>
<script src="<?=base_url('assets/global/plugins/morris/raphael-min.js');?>" type="text/javascript"></script>


<script src="<?=base_url('assets/global/plugins/amcharts/amcharts/amcharts.js');?>" type="text/javascript"></script>
<script src="<?=base_url('assets/global/plugins/amcharts/amcharts/serial.js');?>" type="text/javascript"></script>
<script src="<?=base_url('assets/global/plugins/amcharts/amcharts/themes/light.js');?>" type="text/javascript"></script>
<script src="<?=base_url('assets/global/plugins/amcharts/amcharts/pie.js');?>" type="text/javascript"></script>

<?if (is_posisi_id() <= 3) {?>
<script src="<?=base_url('assets_noondev/js/charts-amcharts-barang.js');?>"></script>
<script src="<?=base_url('assets_noondev/js/index_jual_per_barang.js'); ?>" type="text/javascript"></script>
<?}?>



<script>
jQuery(document).ready(function() {

	let barang_id = "<?=$barang_id?>";
	let tanggal_start = "<?=$tanggal_awal?>";
	let tanggal_akhir = "<?=$tanggal_akhir?>";


	<?if (is_posisi_id() == 1) {?>
	IndexBarang(barang_id, '',tanggal_start, tanggal_akhir);
	<?}?>
	<?if (is_posisi_id() <= 3) {?>
	   	ChartsAmcharts.init(); 
	<?}?>

	$(".show-warna").click(function(){
		let warna_id = $(this).attr("data-warna");
		// alert(warna_id);
		$(".data-"+warna_id).toggle('slow');

	});

   
});

</script>
