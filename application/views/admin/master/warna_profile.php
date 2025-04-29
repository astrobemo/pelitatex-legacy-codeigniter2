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
							<?foreach ($data_warna as $row) { ?>
								<tr>
									<td>
										Warna Beli
									</td>
									<td class='padding-rl-25'>
										:
									</td>
									<td>
										<span style='font-size:1.3em'><?=$row->warna_beli;?></span>
									</td>
								</tr>
								<tr>
									<td>
										Warna Jual
									</td>
									<td class='padding-rl-25'>
										:
									</td>
									<td style='font-size:1.3em'>
										<?=$row->warna_jual;?>
									</td>
								</tr>
							<?}?>
							<tr>
								<td style='vertical-align:top'>Barang Terdaftar</td>
								<td class='padding-rl-25' style='vertical-align:top'>
									:
								</td>
								<td>
									<div style='column-count:4'>
										<?foreach ($data_barang as $row) {?>
											- <?=$row->nama_jual;?> <sup><span style="color:blue; cursor:pointer;"  class="show-warna" data-warna="<?=$row->barang_id;?>">show</span></sup> <br/>
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
								$barang_id_list = explode(",", $value->barang_id);
								$penjualan_data[$i] = explode(",", $value->penjualan_data);
								$count_trx_data[$i] = explode("=?=", $value->penjualan_id);
								$qty_data[$i] = explode(",", $value->qty_data);

								foreach ($count_trx_data[$i] as $key2 => $value2) {
									$break = explode("??", $value2);
									foreach ($break as $key3 => $value3) {
										$break2 = explode(",", $value3);
										foreach ($break2 as $key4 => $value4) {
											array_push($penjualan_id_list[$i], $value4);
										}
									}
								}

								$count_trx_barang[$i] = array_combine($barang_id_list, $count_trx_data[$i]);
								/*if (is_posisi_id() == 1) {
									echo count($barang_id_list).'<br/>';
									echo count($count_trx_data[$i]);
									echo "<hr/>";
								}*/
								foreach ($count_trx_barang[$i] as $key2 => $value2) {
									$total_trx_barang[$i][$key2] = array();
								}
								foreach ($count_trx_barang[$i] as $key2 => $value2) {
									$break = explode("??", $value2);
									foreach ($break as $key3 => $value3) {
										$break2 = explode(",", $value3);
										foreach ($break2 as $key4 => $value4) {
											array_push($total_trx_barang[$i][$key2], $value4);
										}
									}
									$total_trx_barang[$i][$key2] = array_unique($total_trx_barang[$i][$key2]);
								}

								$penjualan_id_list[$i] = array_unique($penjualan_id_list[$i]);

								$total_trx[$i] = count($penjualan_id_list[$i]);
								$penjualan_data[$i] = array_combine($barang_id_list, $penjualan_data[$i]);
								$qty_data[$i] = array_combine($barang_id_list, $qty_data[$i]);
								// $count_trx_data[$i] = array_combine($barang_id_list, $count_trx_data[$i]);

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
							<?foreach ($data_barang as $row) {?>
								<tr class="data-<?=$row->barang_id;?>" hidden>
									<th rowspan='2'><?=$row->nama_jual;?></th>
									<?for ($i=1; $i <=6 ; $i++) {?> 
										<td class='text-center' colspan='2'><?=(isset($penjualan_data[$i][$row->barang_id]) ? number_format($penjualan_data[$i][$row->barang_id],'0',',','.') : '--' );?></td>
									<?}?>
								</tr>
								<tr class="data-<?=$row->barang_id;?>" hidden>
									<?for ($i=1; $i <=6 ; $i++) {?> 
										<td>
											<?=(float)(isset($penjualan_data[$i][$row->barang_id]) ? $qty_data[$i][$row->barang_id] : '');?> yrd
										</td>
										<td>
											<?=(isset($total_trx_barang[$i][$row->barang_id]) ? count($total_trx_barang[$i][$row->barang_id]) : '');?>
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
							<?foreach ($data_barang as $row) {?>
								<tr class="data-<?=$row->barang_id;?>" hidden>
									<th rowspan='2'><?=$row->nama_jual;?></th>
									<?for ($i=7; $i <=12 ; $i++) {?> 
										<td class='text-center' colspan='2'><?=(isset($penjualan_data[$i][$row->barang_id]) ? number_format($penjualan_data[$i][$row->barang_id],'0',',','.') : '--' );?></td>
									<?}?>
								</tr>
								<tr class="data-<?=$row->barang_id;?>" hidden>
									<?for ($i=7; $i <=12 ; $i++) {?> 
										<td>
											<?=(float)(isset($penjualan_data[$i][$row->barang_id]) ? $qty_data[$i][$row->barang_id] : '');?> yrd
										</td>
										<td>
											<?=(isset($total_trx_barang[$i][$row->barang_id]) ? count($total_trx_barang[$i][$row->barang_id]) : '');?>
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

						<div class="row list-separated">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<h4><b>Grafik Penjualan Warna Tahunan(Nilai)</b> </h4>
								<div  id="sales_statistics_nilai" class="portlet-body-morris-fit morris-chart" style="height: 200px; padding:20px;">
								</div>
							</div>
						</div>

						<div class="row list-separated">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<h4><b>Grafik Penjualan Warna Tahunan (Trx)</b> </h4>
								<div  id="sales_statistics_trx" class="portlet-body-morris-fit morris-chart" style="height: 200px; padding:20px;">
								</div>
							</div>
						</div>

						<?if (is_posisi_id() <= 3) {?>
							<div class="row list-separated">
								<div class="col-xs-12">
									<h4><b>Chart Rekap Transaksi Per Warna <?=$tahun;?>(Trx)</b> </h4>
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
					<span id='warna_id_data' hidden><?=$warna_id;?></span>
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
<script src="<?=base_url('assets_noondev/js/charts-amcharts-warna.js');?>"></script>
<script src="<?=base_url('assets_noondev/js/index_jual_per_barang.js'); ?>" type="text/javascript"></script>
<?}?>



<script>
jQuery(document).ready(function() {

	
	<?if (is_posisi_id() == 1) {?>
	// IndexBarang(barang_id, '',tanggal_start, tanggal_akhir);
	<?}?>
	<?if (is_posisi_id() <= 3) {?>
		// IndexBarang.init();
	   	ChartsAmcharts.init(); 
	<?}?>

	$(".show-warna").click(function(){
		let warna_id = $(this).attr("data-warna");
		// alert(warna_id);
		$(".data-"+warna_id).toggle('slow');

	});

	var barang_id = $('#barang_id_data').html();
    var warna_id = $('#warna_id').val();
    var tanggal_awal = $('#tanggal-awal').val();
    var tanggal_akhir = $('#tanggal-akhir').val();

    var url = "admin/get_penjualan_per_barang_bulan?barang_id="+barang_id+"&warna_id="+warna_id+"&tanggal_awal="+tanggal_awal+"&tanggal_akhir="+tanggal_akhir;
    var data_st  ={};
    var data_set = [];
    var i = 0;
    let config = {};
    ajax_data_sync(url,data_st).done(function(data_respond /* ,textStatus, jqXHR */){
        // console.log(data_respond);
        /*$.each(JSON.parse(data_respond),function(k,v){
            data_set[i] = {
                'period': v.tanggal,
                'sales': v.amount
            }
            i++;
        });

        config = {
		data: data_set,
		xkey: 'period',
		ykeys: ['sales'],
		labels: ['Sales'],
		fillOpacity: 0.6,
		hideHover: 'auto',
		behaveLikeLine: true,
		resize: true,
		pointFillColors:['#ffffff'],
		pointStrokeColors: ['black'],
		lineColors:['gray']
		};

		

		config.element = 'sales_statistics';
		Morris.Area(config);*/

    });


    let data_nilai = [];
    let data_trx = [];
    let line_colors = [];
    let monthKey = [];
    let hoverContentNilai = ``;
    let hoverContentTrx = ``;
    let period = '';
    let val =0;
    let idx = 0;
    let key = 0;

    <?$max_trx[0] = 0;
    foreach ($data_show as $key => $value) {?>
    	key = <?=$key?>;
    	if (idx != (key -1) ) {
    		for (let i = idx; i < (key-1); i++) {
	    		period = "<?=$tahun?>-"+(parseInt(i)+1).toString().padStart(2,'0');
	    		data_nilai[i] = {
		            'period': period,
		            <?foreach ($data_barang as $row) {?>
		            	"<?=$row->nama_jual;?>" : 0,
		            <?}?>
		        };

		        data_trx[i] = {
		            'period': period,
		            <?foreach ($data_barang as $row) {
		            	array_push($max_trx, (isset($penjualan_data[$key][$row->barang_id]) ? count($total_trx_barang[$key][$row->barang_id]) : 0 ));?>
		            	"<?=$row->nama_jual;?>" : 0,
		            <?}?>
		        };
    		};

    		idx = key-1;
    	};
    	period = "<?=date('Y-m',strtotime($tahun.'-'.str_pad($key,2,'0', STR_PAD_LEFT).'-01'));?>";
        hoverContentNilai +=`<div class='morris-hover-row-label'>${period}</div>`;
    	data_nilai[<?=$key - 1;?>] = {
            'period': period,
            <?foreach ($data_barang as $row) {?>
            	"<?=$row->nama_jual;?>" : parseFloat(<?=(isset($penjualan_data[$key][$row->barang_id]) ? $penjualan_data[$key][$row->barang_id] : 0 );?>),
            <?}?>
        };
    	console.log(<?=$key?>);

        <?foreach ($data_barang as $row) {?>
        	val = parseFloat(<?=(isset($penjualan_data[$key][$row->barang_id]) ? $penjualan_data[$key][$row->barang_id] : 0 );?>);
	        hoverContentNilai +=`<div class='morris-hover-point'>
				<?=$row->nama_jual?>:${val}</div>`;
    	<?}?>


        data_trx[<?=$key - 1;?>] = {
            'period': period,
            <?foreach ($data_barang as $row) {
            	array_push($max_trx, (isset($penjualan_data[$key][$row->barang_id]) ? count($total_trx_barang[$key][$row->barang_id]) : 0 ));?>
            	"<?=$row->nama_jual;?>" : parseFloat(<?=(isset($penjualan_data[$key][$row->barang_id]) ? count($total_trx_barang[$key][$row->barang_id]) : 0 );?>),
            <?}?>
        };

    	idx++;

        line_colors.push('#'+Math.floor(Math.random()*16777215).toString(16));
    <?}?>
    <?foreach ($data_barang as $row) {?>
        monthKey.push("<?=$row->nama_jual;?>");
    <?}?>

    console.log('data_nilai',data_nilai);

    let config_nilai = {
		element:'sales_statistics_nilai',
		data: data_nilai,
		xkey: 'period',
		ykeys: monthKey,
		labels: monthKey,
		fillOpacity: 0.6,
		hideHover: 'auto',
		behaveLikeLine: true,
		resize: true,
		pointFillColors:['#ffffff'],
		pointStrokeColors: ['black'],
		lineColors:line_colors,
		hoverCallback: function(index, options, content, row) {
	    	return(content);
	    },
	};

	let config_trx = {
		element:'sales_statistics_trx',
		data: data_trx,
		xkey: 'period',
		ykeys: monthKey,
		ymax: <?=max($max_trx);?>,
		labels: monthKey,
		fillOpacity: 1,
		hideHover: 'auto',
		behaveLikeLine: true,
		resize: true,
		pointFillColors:['#ffffff'],
		pointStrokeColors: ['black'],
		lineColors:line_colors,
	    hoverCallback: function(index, options, content, row) {
	    	// console.log(index,content);
	        return(content);
	    },
		};

	Morris.Line(config_nilai);
	Morris.Line(config_trx);

    

   
});

</script>
