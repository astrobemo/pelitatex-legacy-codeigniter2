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
											- <?=$row->warna_jual;?> <br/>
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
					<div class="portlet-title">
						<div class="caption caption-md">
							<i class="icon-bar-chart theme-font hide"></i>
							<span class="caption-subject theme-font bold uppercase">History</span>
						</div>
					</div>
					<div class="portlet-body">
						<h4><b>Daftar Transaksi <?=date('Y');?></b> </h4>
						<table class="table table-bordered" style='width:100%' id="general_table">
							<tr>
								<?for ($i=1; $i <=6 ; $i++) { ?>
									<th class='text-center'><?=date('F',strtotime($tahun.'-'.$i.'-1'));?></th>
								<?}?>
							</tr>
							<?$data_jual = (array)$data_penjualan;
							foreach ($data_jual as $key => $value) {
								$data_show[$value->bulan_jual] = $value;
							}?>
							<tr>
								<?for ($i=1; $i <=6 ; $i++) { ?>
									<td class='text-center' style='font-size:1.1em'>
										<?if (isset($data_show[$i])) {
											echo number_format($data_show[$i]->penjualan,'0',',','.');
										}else{echo '--';}?>
									</td>
								<?}?>
							</tr>
							<tr>
								<?for ($i=7; $i <=12 ; $i++) { ?>
									<th class='text-center'><?=date('F',strtotime($tahun.'-'.$i.'-1'));?></th>
								<?}?>
							</tr>
							<tr>
								<?for ($i=7; $i <=12 ; $i++) { ?>
									<td class='text-center' style='font-size:1.1em'>
										<?if (isset($data_show[$i])) {
											echo number_format($data_show[$i]->penjualan,'0',',','.');
										}else{echo '--';}?>
									</td>
								<?}?>
							</tr>
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
									<h4><b>Chart Rekap Transaksi Per Warna <?=date('Y');?></b> </h4>
									<div id="chart_1" class="chart" style="height: 300px;">
									</div>
								</div>
								<div class="col-xs-12">
									<h4><b>Penjualan Warna Terbanyak <?=date('Y');?></b> </h4>

									<div id="chart_pie_1" class="chart" style="height: 300px;">
									</div>
									
								</div>

								<div class="col-xs-12">
									<h4><b>Chart Rekap Per Customer <?=date('Y');?></b> </h4>
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
<?}?>



<script>
jQuery(document).ready(function() {

	<?if (is_posisi_id() <= 3) {?>
	   	ChartsAmcharts.init(); 
	<?}?>

   
});

</script>
