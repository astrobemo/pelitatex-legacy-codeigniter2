<?php echo link_tag('assets/global/plugins/jqvmap/jqvmap/jqvmap.css'); ?>
<?php echo link_tag('assets/global/plugins/morris/morris.css'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css'); ?>"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css'); ?>"/>

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
							<span class="caption-subject theme-font bold uppercase">Data Customer</span>
						</div>
					</div>
					<div class="portlet-body">
						<table style='width:100%'>
							<?foreach ($data_customer as $row) { ?>
								<tr>
									<td>
										<i class='fa fa-user'></i>
									</td>
									<td>
										:
									</td>
									<td>
										<?=$row->nama;?>
									</td>
								</tr>
								<tr>
									<td>
										<i class='fa fa-home'></i> 
									</td>
									<td>
										:
									</td>
									<td>
										<?=$row->alamat;?>
									</td>
								</tr>
								<tr>
									<td>
										<i class='fa fa-phone'></i> 
									</td>
									<td>
										:
									</td>
									<td>
										<?=$row->telepon1;?>/<?=$row->telepon2;?>
									</td>
								</tr>
								<tr>
									<td>
										<i class='fa fa-credit-card'></i>
									</td>
									<td>
										:
									</td>
									<td>
										<?=$row->npwp;?>
									</td>
								</tr>
								<tr>
									<td>
										<i class='fa fa-globe'></i>
									</td>
									<td>
										:
									</td>
									<td>
										<?=$row->kota;?>
									</td>
								</tr>
								<tr>
									<td>
										<i class='icon-envelope-letter'></i> 
									</td>
									<td>
										:
									</td>
									<td>
										<?=$row->email;?>
									</td>
								</tr>
							<?}?>
						</table>
					</div>
					
				</div>
			</div>

			<div class="col-md-12">
				<div class="portlet light">
					<div class="portlet-title">
						<div class="caption caption-md">
							<i class="icon-bar-chart theme-font hide"></i>
							<span class="caption-subject theme-font bold uppercase">History</span>
						</div>
					</div>
					<div class="portlet-body">
						<h4><b>Daftar Transaksi <?//=date('Y');?></b> </h4>
						<hr/>
						<table>
							<form action="">
								<tr>
									<td>Tanggal</td>
									<td style='padding:0 10px'> : </td>
									<td>
										<!-- <input name='customer_id' value="<?=$customer_id;?>" hidden> -->
										<input type="text" name='tanggal_start' value="<?=$tanggal_start?>" style="width:100px" readonly class='text-center date-picker'> s/d
										<input type="text" name='tanggal_end' value="<?=$tanggal_end?>" style="width:100px" readonly class='text-center date-picker'>
									</td>
								</tr>
								<tr>
									<td></td>
									<td></td>
									<td><button class='btn btn-xs btn-block blue'><i class='fa fa-search'></i></button></td>
								</tr>
							</form>
						</table>
						<hr/>
						<table class="table table-bordered table-hover table-striped" style='width:100%' id="general_table">
							<thead>
								<tr style='background:#eee' >
									<th scope="col" style='width:90px !important;'>
										No Faktur
									</th>
									<th scope="col">
										Tanggal<br/> Penjualan
									</th>
									<th scope="col" style='display:none'>
										Tanggal (ORI)
									</th>
									<th scope="col">
										Jumlah <br/>
										Yard/KG
									</th>
									<th scope="col">
										Jumlah <br/> Roll
									</th>
									<th scope="col" style='min-width:300px !important'>
										Nama Barang
									</th>
									<th scope="col">
										Harga
									</th>
									<th scope="col">
										Total
									</th>
									<th scope="col">
										Keterangan
									</th>
									<!-- <th scope="col">
										Jatuh Tempo
									</th> -->
								</tr>
							</thead>
							<tbody>
								<?
								$idx_total = 0; $g_total = 0;
								$yard_total = 0; $roll_total = 0;
								foreach ($data_penjualan as $row) { ?>
									<?
										$qty = ''; $jumlah_roll = ''; $nama_barang = ''; $harga_jual = '';
										if ($row->qty != '') {
											$qty = explode('??', $row->qty);
											$jumlah_roll = explode('??', $row->jumlah_roll);
											$nama_barang = explode('??', $row->nama_barang);
											$harga_jual = explode('??', $row->harga_jual);
										}
										$yard_total += $row->qty;
										$roll_total += $row->jumlah_roll;
									?>
									<tr class='text-center' >
										<td>
											<a target='_blank' href="<?=base_url().is_setting_link('transaction/penjualan_list_detail')?>/?id=<?=$row->id;?>"><?=$row->no_faktur;?></a>
										</td>
										<td  style='display:none'>
											<?=$row->tanggal;?>
										</td>
										<td>
											<?=is_reverse_date($row->tanggal);?>
										</td>
										<td>
											<?
											if ($qty != '') {
												$baris = count($qty);
												$j = 1; $idx = 1;
												foreach ($qty as $key => $value) {
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
											<!-- <span class='nama'><?=str_replace('??', '<br/>', $row->nama_barang);?></span><br/> -->
											
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
													echo number_format($qty[$key] * $value,'0',',','.').'<br/>';
													$subtotal +=$qty[$key] * $value; 
													$g_total += $qty[$key] * $value;

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
										<td>
											<?if ($row->keterangan < 0) { ?>
												<span style='color:red'>belum lunas</span>
											<?}else if ($row->keterangan >= 0){?>
												<span style='color:green'>lunas</span>
											<?}?> 
										</td>
										<!-- <td>
											<?=is_reverse_date($row->jatuh_tempo);?>
										</td> -->
									</tr>
								<? $idx_total++;} ?>
							</tbody>
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
								<td class='text-center'><?=$idx_total;?></td>
								<td class='text-center'><?=number_format($yard_total,'2',',','.');?></td>
								<td class='text-center'><?=number_format($roll_total,'0',',','.');?></td>
								<td class='text-center'><b><?=number_format($g_total,'0',',','.');?></b> </td>
								<!-- <td></td>
								<td></td>
								<td></td> -->
							</tr>
						</table>

						<?if (is_posisi_id() <= 3) {?>
							<div class="row list-separated">
								<hr/>
								<div class="col-md-4 col-sm-6 col-xs-6">
									<div class="font-grey-mint font-sm">
										Total Piutang
									</div>
									<div class="uppercase font-hg font-purple">
										<?foreach ($customer_profile_hutang as $row) { ?>
											Rp <?=number_format($row->sisa_piutang,'0',',','.')?> <span class="font-lg font-grey-mint"></span>
										<?}?>
									</div>
								</div>
								<div class="col-md-4 col-sm-6 col-xs-6">
									<div class="font-grey-mint font-sm">
										Saldo DP
									</div>
									<div class="uppercase font-hg font-blue">
										<?foreach ($customer_dp as $row) { ?>
											Rp <?=number_format($row->saldo,'0',',','.')?> <span class="font-lg font-grey-mint"></span>
										<?}?>
									</div>
								</div>
								<hr/>
							</div>
						<?}?>

						<?if (is_posisi_id() <= 3) {?>
							<div class="row list-separated">
								<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
									<h4><b>Chart Rekap Transaksi Per Bulan Tahun <?=date('Y');?></b> </h4>
									<div id="chart_1" class="chart" style="height: 300px;">
									</div>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
									<h4><b>10 Penjualan Barang Terbanyak <?=date('Y');?></b> </h4>

									<div id="chart_2" class="chart" style="height: 300px;">
									</div>
									
								</div>

							</div>
							<div class="row list-separated">
							
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<h4><b>10 Penjualan Barang Terbanyak <?=date('Y');?></b> </h4>

									<div id="chart_3" class="chart" style="height: 400px;">
									</div>
									
								</div>

							</div>
							
						<?}?>
					</div>

					<span id='customer_id_data' hidden='hidden'><?=$customer_id;?></span>
					
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
	<script src="<?=base_url('assets_noondev/js/charts-amcharts-cust.js');?>"></script>
<?}?>



<script>
jQuery(document).ready(function() {

	<?if (is_posisi_id() <= 3) {?>
	   	ChartsAmcharts.init(); 
	<?}?>

   	$("#general_table").DataTable({
		"ordering":false,
		// "scrollY":        "200px",
  //       "scrollCollapse": true,
  //       "paging":         false
		// "bFilter":false
	});
	
});

</script>
