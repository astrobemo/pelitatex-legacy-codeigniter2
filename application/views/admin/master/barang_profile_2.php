<?php echo link_tag('assets/global/plugins/jqvmap/jqvmap/jqvmap.css'); ?>
<?php echo link_tag('assets/global/plugins/morris/morris.css'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css'); ?>"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css'); ?>"/>
<?php echo link_tag('assets/global/plugins/bootstrap-datepicker/css/datepicker3.css'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/bootstrap-select/bootstrap-select.min.css'); ?>"/>


<style type="text/css">

.container{
	width: 100%;
}

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

svg:hover{
	fill:red;
}

.portlet-title .actions{
	.float: right;
}

.warna-list{
	cursor: pointer;
	font-size: 1.2em;
}

.warna-list:hover{
	font-weight: bold;
}

.warna-selected{
	background: #daffd1;
}

.warna-selected:after{
	content:'\f00c';
	font-family: FontAwesome;
	padding-left: 10px;
	/*position: absolute;*/
	/*left:100px;*/
	/*float:right;*/
}

#warna-bulan-container{
	position: -webkit-sticky;
	position: sticky;
	top: 0;
	background-color: #eee;
	font-size: 0.8em;
	z-index: 100;
	height: 130px;
}

#general_table{
	font-size: 0.8em;
	cursor: zoom-in;
}

#general_table .bulan{
	position: -webkit-sticky;
	position: sticky;
	top: 130px;
}

#general_table tr td,#general_table tr th{
	padding: 2px;
	border:1px solid #ddd;
}

#general_table tbody tr:nth-child(4n+3), #general_table tbody tr:nth-child(4n+4){
	background: #eee;
}

.modal-custom-content{
	margin: auto;
	width: 100%;
	margin-top:40px;
	overflow: auto;
	background: #eee;
}

.modal-custom-content table tbody tr:nth-child(4n+1), .modal-custom-content tbody tr:nth-child(4n+2){
	background: #fff;
}

.modal{
	width: 100%;
	padding: 5px;
}

.history-kredit-info{
	position: relative;
	cursor: pointer;
}

.history-kredit-info-active{
	font-weight:bold;
}

.history-kredit-info:hover{
	font-weight:bold;
}

.history-kredit-info div{
	position:absolute;
	top:0px; 
	left:100%;
	background:#eee;
	padding:10px;
	z-index: 999;
	min-width: 150px;
}

.trx-info{
	position:absolute;
	top:5px;
	right:-15px;
	font-weight:bold;
	font-size:12px !important;
	padding:2px 5px !important;
	height:20px;
	background: lightblue;
}

.trx-info-text{
	cursor: pointer;
}

.trx-info-customer{
	position:absolute;
	width:150px;
	top:5px;
	left:90%;
	font-size:12px !important;
	padding:5px 8px !important;
	text-align:left;
	text-transform: uppercase;
	background: lightgray;
	z-index: 999;
}

#sales_statistics_trx_barang text tspan{
	visibility:hidden;
	/* color:white !important; */
}

</style>


<?foreach ($this->satuan_list_aktif as $row) {
	$n_satuan[$row->id] = $row->nama;
}?>
<div class="page-content">
	<div class='container'>

		<div class="modal fade" id="portlet-config" tabindex="-1">
			<div class="modal-custom-content">
				
			</div>
			<button type="button" class="btn default" style="position:absolute; top:5px; right:10px;" data-dismiss="modal">Close</button>

		</div>

		<div class="row margin-top-10">
			<div class="col-md-12">
				<div class="portlet light portlet-container">
					<div class="portlet-title">
						<div class="caption caption-md">
							<i class="icon-bar-chart theme-font hide"></i>
							<span class="caption-subject theme-font bold uppercase">Data Barang</span>
						</div>
					</div>
					<div class="portlet-body">
						<table style='font-size:1.2em' width="100%">
							<?foreach ($data_barang as $row) { 
								$harga_master_jual = $row->harga_jual;
								$harga_master_beli = $row->harga_beli;?>
							<form> 
								<tr>
									<td style='vertical-align:top' >TAHUN</td>
									<td class='padding-rl-25' style='vertical-align:top'>
										:
									</td>
									<td style='padding-bottom:5px;'>
										<select name='tahun' style='width:200px;'>
											<?for ($i=2019; $i <= date('Y') ; $i++) { ?>
												<option value="<?=$i?>" <?=($i == $tahun ? 'selected' : '');?> ><?=$i;?></option>
											<?}?>
										</select>
									</td>
								</tr>
								<tr>
									<td>
										Nama Beli
									</td>
									<td class='padding-rl-25'>
										:
									</td>
									<td>
										<select name='id' style="width:322px; " id='barang-id-select'>
											<?foreach ($this->barang_list_aktif as $row2) {?>
												<option value="<?=($row2->id)?>" <?=($barang_id == $row2->id ? 'selected' : '');?> ><?=$row2->nama?></option>
											<?}?>
										</select>
										<button class='btn btn-xs default' type='submit'>OK</button>
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
									<td>
										Nama Satuan
									</td>
									<td class='padding-rl-25'>
										:
									</td>
									<td style='font-size:1.3em'>
										<?=$nama_satuan = $n_satuan[$row->satuan_id];?>
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
							</form>
							<?}?>
							<tr>
								<td colspan='3' style="background:#eee; height:5px;"></td>
							</tr>
							<tr>
								<td style='vertical-align:top; padding:10px 0px' >Warna Terdaftar</td>
								<td class='padding-rl-25' style='vertical-align:top; padding-top:10px'>
									:
								</td>
								<td style="padding:10px 0px">
									<div style='column-count:4'>
										<?$color_list = array();
										foreach ($data_warna as $row) {
											$color = sprintf('#%06X', mt_rand(0, 0xFFFFFF));
											$color = strtolower($color);
											while ( in_array($color, $color_list)) {
												$color = sprintf('#%06X', mt_rand(0, 0xFFFFFF));
												$color = strtolower($color);
											}
											array_push($color_list, $color);
											$total_qty[$row->warna_id]=0;
											$total_amount[$row->warna_id]=0;
											$total_trx_warna_all[$row->warna_id]=0;
										}?>
										<?$idx=0;foreach ($data_warna as $row) { 
											$qty_warna[$row->warna_id] = 0;?>
											- <?=$row->warna_jual;?> [<?=$row->warna_id;?>] 
											<br/>
										<?$idx++;}?>
									</div>
								</td>
							</tr>
							<?if (is_posisi_id() <= 3) {?>
								<tr>
									<td colspan='3' style="background:#eee; height:5px;"></td>
								</tr>
								<tr>
									<td style='vertical-align:top; padding:10px 0px' >Harga History</td>
									<td class='padding-rl-25' style='vertical-align:top; padding-top:10px'>
										:
									</td>
									<td style='padding:10px 0px;'>
										<select id="tglHistoryAwal">
											<?
											$year_start = date("Y") - 3;
											$start = (new DateTime("$year_start-12-02"))->modify('first day of this month');
											$end = new DateTime();
											$interval = DateInterval::createFromDateString('1 month');
											$period   = new DatePeriod($start, $interval, $end);
											
											foreach ($period as $dt) {?>
												<option <?=($dt->format('Y-m') == date('Y-m', strtotime('-1 year')) ? 'selected' : '' );?>  value="<?=$dt->format("Y-m")?>"><?=$dt->format("F Y")?></option>;
											<?}?>
										</select>
										s/d
										<select id="tglHistoryAkhir">
											<?foreach ($period as $dt) {?>
												<option <?=($dt->format('Y-m') == date("Y-m") ? 'selected' : '' );?> value="<?=$dt->format("Y-m")?>"><?=$dt->format("F Y")?></option>;
											<?}?>
										</select>

										<button style='float:right' class='btn green' onclick="changeViewTrx()">View Jumlah Transaksi</button>

									</td>
								</tr>

								<tr>
									<td colspan='3' style="background:#eee; height:5px;"></td>
								</tr>
								<tr>
									<td colspan='3' style='padding:10px 0px;'>
										<div id='harga-history'>
											<span style='color:#ddd'>loading...</span>
										</div>
									</td>
								</tr>
							<?}?>
						</table>
					</div>
					
				</div>
			</div>

			<div class="col-md-12">
				<div class="portlet light portlet-container">
					<div class="portlet-title">
						<div class="caption caption-md">
							<i class="icon-bar-chart theme-font hide"></i>
							<span class="caption-subject theme-font bold uppercase">
								Pergerakan Transaksi Barang Harian
							</span>
						</div>
						<div class="actions hidden-print">
							<button class="btn btn-default btn-sm hideBody">Hide</button>
							<button class="btn btn-default btn-sm showBody" style="display:none">Show</button>
						</div>
					</div>
					<div class="portlet-body">
						<div class="row list-separated" >
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<form>
									<select name='tahun' hidden>
										<?for ($i=2019; $i <= date('Y') ; $i++) { ?>
											<option value="<?=$i?>" <?=($i == $tahun ? 'selected' : '');?> ><?=$i;?></option>
										<?}?>
									</select>
									<input name='tanggal_awal' id='tanggal-awal' class='date-picker' value="<?=is_reverse_date($tanggal_awal)?>"> s/d 
									<input name='tanggal_akhir'id='tanggal-akhir' class='date-picker' value="<?=is_reverse_date($tanggal_akhir)?>">
									<select name='warna_id' id="warna_id" style="width:150px">
										<option <?=($warna_id == '' ? 'selected' : '')?> value="">Semua</option>
										<?foreach ($data_warna as $row) {?>
											<option <?=($warna_id == $row->warna_id ? 'selected' : '')?> value="<?=$row->warna_id?>"><?=$row->warna_jual?></option>
										<?}?>
									</select>
									<button type="button" onClick="get_harian()">OK</button>
								</form>
									<div  id="sales_statistics" class="portlet-body-morris-fit morris-chart" style="height: 200px; padding:20px;">
									</div>
								
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="col-md-12">
				<div class="portlet light portlet-container">
					<div class="portlet-title">
						<div class="caption caption-md">
							<i class="icon-bar-chart theme-font hide"></i>
							<span class="caption-subject theme-font bold uppercase">
								Pergerakan Transaksi Barang Rekap Per Bulan
							</span>
						</div>
						<div class="actions hidden-print">
							<button class="btn btn-default btn-sm hideBody" >Hide</button>
							<button class="btn btn-default btn-sm showBody" style="display:none">Show</button>
						</div>
					</div>
					<div class="portlet-body" >
						<div style="margin-bottom:20px;" id="warna-bulan-container">
							<b style="font-size:1.2em;">DAFTAR WARNA :</b> <span id='warna-bulan-count' style='font-size:16px'></span> selected <span id="warna-bulan-show-temp" style='font-size:1.2em'></span>
							<!-- <select hidden id='warna-per-bulan' data-live-search='true' title="Pilih Warna" class="bs-select form-control" multiple>
								<?$idx=0;foreach ($data_warna as $row) { ?>
									<option selected value="<?=$color_list[$idx];?>"><?=$row->warna_jual?></option>
									<?if ($idx%5==4) {?>
										<option data-divider="true">divider</option>
									<?}?>
								<?$idx++;}?>
							</select> -->
							<div >
								<?$dvd = (count($data_warna) <= 10 ? 2 : count($data_warna) <= 15 ? 3 : 4);
									$sisa = count($data_warna) % $dvd;
								?>
								<?$data_jual = (array)$data_penjualan;
								$data_show = array();
								foreach ($data_jual as $key => $value) {
									$bj = date('m', strtotime($value->bulan_jual.'-01'));
									$bj = (float)$bj;
									$data_show[$bj] = $value;
									$total_trx[$bj] = 0;
									$penjualan_id_list[$bj] = array();
								}
								foreach ($data_show as $i => $value) {
									$warna_id_list = explode(",", $value->warna_id);
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

									if (is_posisi_id() == 1) {
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
									$qty_data[$i] = array_combine($warna_id_list, $qty_data[$i]);
									// $count_trx_data[$i] = array_combine($warna_id_list, $count_trx_data[$i]);

										# code...
									
								}

								for ($i=1; $i <=12 ; $i++) {
									if (isset($qty_data[$i])) {
										if (!isset($total_yard_by_month[$i])) {
											$total_yard_by_month[$i] = 0;
										}

										foreach ($qty_data[$i] as $key => $value) {
											$total_yard_by_month[$i] += $value;
										}
									}else{}
								}
								?>
								<!-- Daftar Warna :  -->
								<table id='warna-bulan-control' style='margin-top:10px'>
									<tr>
										<?$idx=0;foreach ($data_warna as $row) { 
											for ($i=1; $i <=12 ; $i++) { 
												$total_amount[$row->warna_id] += (isset($penjualan_data[$i][$row->warna_id]) ? $penjualan_data[$i][$row->warna_id] : 0); 
												$total_qty[$row->warna_id] += (isset($qty_data[$i][$row->warna_id]) ? $qty_data[$i][$row->warna_id] : 0);
												$total_trx_warna_all[$row->warna_id] += (isset($total_trx_warna[$i][$row->warna_id]) ? count($total_trx_warna[$i][$row->warna_id]) : 0);
											}

											if (($idx+1)%5==1) {
												echo "<td style='width:145px; vertical-align:top; position:relative;padding-right:8px'>";
											}?>
										<div class='warna-list warna-selected' data-value="<?=$color_list[$idx];?>" style='width:100%'><?=$row->warna_jual?></div>
										<?
											if (($idx+1)%5==0) {
												echo "</td>";
											}
										$idx++;}?>
									</tr>
								</table>
							</div>
							<div style='position:absolute; font-size:1.2em; right:0px; bottom:0px; right:5px; display:none'>
								<a id="warna-bulan-control-hide" style="position:relative; bottom:5px" >[hide]</a>
								<a id="warna-bulan-control-show" hidden>[show]</a>
							</div>
								
						</div>

							<table id="general_table" class='green-bg' width="100%">
								<thead>
									<tr>
										<th class='bulan' style=" background:#e3f9ff">Bulan</th>
										<?for ($i=1; $i <=12 ; $i++) { ?>
											<th style=" background:#e3f9ff" colspan='2' class='text-center bulan'><?=date('F',strtotime($tahun.'-'.$i.'-1'));?></th>
										<?}?>
									</tr>
									<tr style="background:#ffe3f2">
										<th>TOTAL</th>
										<?for ($i=1; $i <=12 ; $i++) { ?>
											<td colspan='2' class='text-center' style='font-size:1.1em'>
												<?if (isset($data_show[$i])) {
													echo number_format($data_show[$i]->penjualan,'0',',','.');
												}else{echo '--';}?>
											</td>
										<?}?>
									</tr>
									<tr  style="background:#ffe3f2">
										<th>QTY</th>
										<?for ($i=1; $i <=12 ; $i++) { ?>
											<td class='text-center' style='font-size:1.1em'>
												<?=(isset($total_yard_by_month[$i]) ? number_format($total_yard_by_month[$i],'0',',','.') : '-');?>
											</td>
											<td>
												<?if (isset($data_show[$i])) {
													echo number_format($total_trx[$i],'0',',','.');
												}?>
											</td>
											
										<?}?>
									</tr>
								</thead>
								<tbody>
								<?$idx=0;foreach ($data_warna as $row) {?>
									<tr data-warna="<?=$color_list[$idx];?>"  class="data-<?=$color_list[$idx];?>" style='border-top:2px solid #333;'>
										<th>
											<?=$row->warna_jual;?><br/>
											<span style="background:#e3f9ff"><?=number_format($total_amount[$row->warna_id],0,',','.');?></span><br/>
										</th>
										<?for ($i=1; $i <=12 ; $i++) {?> 
											<td class='text-center' colspan='2'><?=(isset($penjualan_data[$i][$row->warna_id]) ? number_format($penjualan_data[$i][$row->warna_id],'0',',','.') : '--' );?></td>
										<?}?>
									</tr>
									<tr data-warna="<?=$color_list[$idx];?>"  class="data-<?=$color_list[$idx];?>">
										<th>
											<span style="background:#e3f9ff"><?=number_format($total_qty[$row->warna_id],0,',','.');?></span> / 
											<span style="background:#e3f9ff"><?=number_format($total_trx_warna_all[$row->warna_id],0,',','.');?></span>
										</th>
										
										<?for ($i=1; $i <=12 ; $i++) {?> 
											<td class='text-right'>
												<?=(isset($penjualan_data[$i][$row->warna_id]) ? number_format($qty_data[$i][$row->warna_id],'0',',','.') : '');?> <?=$nama_satuan;?>
											</td>
											<td>
												<?=(isset($total_trx_warna[$i][$row->warna_id]) ? count($total_trx_warna[$i][$row->warna_id]) : '');?>
											</td>
										<?}?>
										
									</tr>	
								<?$idx++;}?>
								</tbody>

								<? /*
								<tr style="font-size:1.2em; background:#e3f9ff">
									<th>Bulan</th>
									<?for ($i=7; $i <=12 ; $i++) { ?>
										<th colspan='2' class='text-center'><?=date('F',strtotime($tahun.'-'.$i.'-1'));?></th>
									<?}?>
								</tr>
								<tr style="font-size:1.2em;; background:#e3f9ff">
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
								<?$idx=0;foreach ($data_warna as $row) {?>
									<tr style="<?=($idx%2==1 ? "background:#eee" : "")?>" data-warna="<?=$color_list[$idx];?>"  class="data-<?=$color_list[$idx];?>">
										<th rowspan='2'><?=$row->warna_jual;?></th>
										<?for ($i=7; $i <=12 ; $i++) {?> 
											<td class='text-center' colspan='2'><?=(isset($penjualan_data[$i][$row->warna_id]) ? number_format($penjualan_data[$i][$row->warna_id],'0',',','.') : '--' );?></td>
										<?}?>
									</tr>
									<tr style="<?=($idx%2==1 ? "background:#eee" : "")?>" data-warna="<?=$color_list[$idx];?>" class="data-<?=$color_list[$idx];?>">
										<?for ($i=7; $i <=12 ; $i++) {?> 
											<td>
												<?=(isset($penjualan_data[$i][$row->warna_id]) ? number_format($qty_data[$i][$row->warna_id],'0',',','.') : '');?> <?=$nama_satuan;?>
											</td>
											<td>
												<?=(isset($total_trx_warna[$i][$row->warna_id]) ? count($total_trx_warna[$i][$row->warna_id]) : '');?>
											</td>
										<?}?>
										
									</tr>	
								<?$idx++;}*/?>
								
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

							<div class="row list-separated" style='display:none'>
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style='position:absolute; opacity:0'>
									<h4 style='color:transparent'><b>Grafik Pergerakan Penjualan Barang By Transaksi</b> </h4>
									<div  id="sales_statistics_trx_barang" class="portlet-body-morris-fit morris-chart" style="height: 450px; padding:20px;">
									</div>
								</div>
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="display:none">
									<h4><b>Grafik Pergerakan Penjualan Barang By Nilai/Transaksi</b> </h4>
									<div id="sales_statistics_nilai_barang" class="portlet-body-morris-fit morris-chart" style="height: 450px; padding:20px;">
									</div>
								</div>
							</div>

							<!-- <div class="row list-separated">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<h4><b>Grafik Pergerakan Penjualan Barang By Transaksi</b> </h4>
									<div  id="sales_statistics_trx_barang" class="portlet-body-morris-fit morris-chart" style="height: 450px; padding:20px;">
									</div>
								</div>
							</div> -->

							<div class="row list-separated" style="display:none">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<h4><b>Grafik Pergerakan Penjualan per Warna By Nilai</b> </h4>
									<div  id="sales_statistics_nilai" class="portlet-body-morris-fit morris-chart" style="height: 450px; padding:20px;">
									</div>
								</div>
							</div>

							<div class="row list-separated" style="display:none">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<h4><b>Grafik Pergerakan Penjualan per warna By Jumlah Trx</b> </h4>
									<div  id="sales_statistics_trx" class="portlet-body-morris-fit morris-chart" style="height: 450px; padding:20px;">
									</div>
								</div>
							</div>
					</div>
				</div>
			</div>

			<div class="col-md-12">
				<div class="portlet light portlet-container">
					<div class="portlet-title">
						<div class="caption caption-md">
							<i class="icon-bar-chart theme-font hide"></i>
							<span class="caption-subject theme-font bold uppercase">
								Rekap Transaksi Barang Per Tahun
							</span>
						</div>
						<div class="actions hidden-print">
							<button class="btn btn-default btn-sm hideBody">Hide</button>
							<button class="btn btn-default btn-sm showBody" style="display:none">Show</button>
						</div>
					</div>
					<div class="portlet-body">
						<h4>
							<form>TAHUN : 
								<select name='tahun'>
									<?for ($i=2019; $i <= date('Y') ; $i++) { ?>
										<option value="<?=$i?>" <?=($i == $tahun ? 'selected' : '');?> ><?=$i;?></option>
									<?}?>
								</select>
								<button class='btn btn-md green'>OK</button>
							</form>
						</h4>
						<?if (is_posisi_id() <= 3) {?>
							<div class="row list-separated" >
								<div class="col-xs-12"  style="display:none">
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
					<span id='tahun_data' hidden><?=$tahun;?></span>
					
				</div>
			</div>
		</div>
	</div>			
</div>

<script src="<?php echo base_url('assets/global/plugins/datatables/media/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js'); ?>" type="text/javascript"></script>


<script src="<?=base_url('assets/global/plugins/morris/morris.js');?>" type="text/javascript"></script>
<script src="<?=base_url('assets/global/plugins/morris/raphael-min.js');?>" type="text/javascript"></script>


<script src="<?=base_url('assets/global/plugins/amcharts/amcharts/amcharts.js');?>" type="text/javascript"></script>
<script src="<?=base_url('assets/global/plugins/amcharts/amcharts/serial.js');?>" type="text/javascript"></script>
<script src="<?=base_url('assets/global/plugins/amcharts/amcharts/themes/light.js');?>" type="text/javascript"></script>
<script src="<?=base_url('assets/global/plugins/amcharts/amcharts/pie.js');?>" type="text/javascript"></script>

<script type="text/javascript" src="<?=base_url('assets/global/plugins/bootstrap-select/bootstrap-select.min.js');?>"></script>
<script src="<?=base_url('assets_noondev/js/charts-amcharts-barang.js');?>"></script>
<script src="<?=base_url('assets_noondev/js/index_jual_per_barang.js'); ?>" type="text/javascript"></script>



<script>
	
var harga_history = [];
harga_history['beli'] = [];
harga_history['jual'] = [];
harga_history['jual_kredit'] = [];
harga_history['jual_master'] = [];
harga_history['master'] = [];

harga_history['beli'][`i-<?=$barang_id?>`] = [];
harga_history['beli'][`i-<?=$barang_id?>`]['harga'] = [];
harga_history['beli'][`i-<?=$barang_id?>`]['master'] = []
harga_history['beli'][`i-<?=$barang_id?>`]['tanggal'] = [];

harga_history['master'][`i-<?=$barang_id?>`] = [];
harga_history['master'][`i-<?=$barang_id?>`]['tanggal'] = [];
// harga_history['master'][`i-<?=$barang_id?>`]['harga'] = [];


harga_history['jual'][`i-<?=$barang_id?>`] = [];
harga_history['jual'][`i-<?=$barang_id?>`]['tanggal'] = [];
// harga_history['jual'][`i-<?=$barang_id?>`]['harga'] = [];
// harga_history['jual_kredit'] = [];
harga_history['jual_master'][`i-<?=$barang_id?>`] = [];
harga_history['jual_master'][`i-<?=$barang_id?>`]['tanggal'] = [];
// harga_history['jual_master'][`i-<?=$barang_id?>`]['harga'] = [];

jQuery(document).ready(function() {

	$('#warna-per-bulan').selectpicker('selectAll');

	$("#general_table tr td, #general_table tr th").click(function(){
		$(".modal-custom-content").html("<table class='table table-bordered' style='font-size:1em'>"+$("#general_table").html()+'</table>');
		$("#portlet-config").modal("toggle");
	});

	$("#barang-id-select").select2();

	let data_nilai = [];
	let data_nilai_barang = [];
	let data_nilai_barang2 = [];
	let data_trx_barang = [];
    let data_trx = [];
    let line_colors = [];
    let monthKey = [];
    let lineClassName = [];
    let hoverContentNilai = ``;
    let hoverContentTrx = ``;
    let period = '';
    let val =0;
	let warna_data = "";
	let warna_bulan_show = {};
	let warna_count = 0;
	warna_bulan_show['period'] = true;
	
	get_harian();
	get_harga_history();
	

	<?if (is_posisi_id() == 1) {?>
	// IndexBarang(barang_id, '',tanggal_start, tanggal_akhir);
	<?}?>
	<?if (is_posisi_id() <= 3) {?>
		// IndexBarang.init();
	   	ChartsAmcharts.init(); 
	<?}?>

	$('.body-toggle').click(function() {
		$(this).closest('.portlet-container').find(".portlet-body").toggle('slow');
	});

	$(document).click(function(e){
        if(!$(e.target).hasClass('history-kredit-info') )
        {
            $(".history-kredit-info div").hide();
            $(".history-kredit-info").removeClass("history-kredit-info-active");
            // $('.menu').remove();                
        }
    })

	$(".show-warna").click(function(){
		warna_data = $(this).attr("data-warna");
		// console.log(warna_data);
		// console.log($("#sales_statistics_nilai").find('svg').find("[stroke='"+warna_data+"']").attr('fill'));
		$("#sales_statistics_nilai").find('svg').find("[stroke='"+warna_data+"']").hide();
		$("#sales_statistics_trx").find('svg').find("[stroke='"+warna_data+"']").hide();
		// $(".data-"+warna_id).toggle('slow');

	});

	$('#warna-per-bulan').change(function(){
		let warna_code = $(this).val();
		$("#warna-per-bulan").find("option").each(function(){
			let warna_data = $(this).val().toString().toLowerCase();
			let nama_warna = $(this).text();
			console.log(warna_data);
			if (!$(this).is(':checked')) {
				warna_bulan_show[nama_warna] = false;
				$("[data-warna='"+warna_data+"']").hide();
				$("#sales_statistics_nilai").find('svg').find("[stroke='"+warna_data+"']").hide();
				$("#sales_statistics_trx").find('svg').find("[stroke='"+warna_data+"']").hide();
			}else{
				warna_bulan_show[nama_warna] = true;
				$("[data-warna='"+warna_data+"']").show();
				$("#sales_statistics_nilai").find('svg').find("[stroke='"+warna_data+"']").slideIn();
				$("#sales_statistics_trx").find('svg').find("[stroke='"+warna_data+"']").show();
			};
		});
	});

	$(".warna-list").click(function(){
		let nama_warna = $(this).html();
		let warna_data = $(this).attr('data-value');
		// alert(nama_warna);
		warna_bulan_show[nama_warna] = !warna_bulan_show[nama_warna];
		if (!warna_bulan_show[nama_warna]) {
			warna_count--;
			$(this).removeClass("warna-selected");
			$("[data-warna='"+warna_data+"']").hide();
			$("#sales_statistics_nilai").find('svg').find("[stroke='"+warna_data+"']").hide();
			$("#sales_statistics_trx").find('svg').find("[stroke='"+warna_data+"']").hide();
		}else{
			warna_count++;
			$(this).addClass("warna-selected");
			$("[data-warna='"+warna_data+"']").show();
			$("#sales_statistics_nilai").find('svg').find("[stroke='"+warna_data+"']").show('slow');
			$("#sales_statistics_trx").find('svg').find("[stroke='"+warna_data+"']").show();
		};
		$("#warna-bulan-count").text(warna_count);

	});

	$("#warna-bulan-control-hide").click(function(){
		let w_list = [];
		$.each(warna_bulan_show,function(i,v){
			if (i != 'period' && v != false) {
				w_list.push(i);
			};
		});

		$("#warna-bulan-control-hide").toggle();
		$("#warna-bulan-control-show").toggle();
		$("#warna-bulan-control").toggle();
		$('#warna-bulan-show-temp').text(w_list.join(', '));
	});

	$("#warna-bulan-control-show").click(function(){
		$("#warna-bulan-control-hide").toggle();
		$("#warna-bulan-control-show").toggle();
		$("#warna-bulan-control").toggle();
		$('#warna-bulan-show-temp').text('');
	});


    let color = "";

    <?foreach ($color_list as $key => $value) {?>
    	line_colors.push("<?=$value?>");
    <?}?>

    <?foreach ($data_warna as $row) {?>
    	warna_bulan_show["<?=$row->warna_jual;?>"] = true;
    	monthKey.push("<?=$row->warna_jual;?>");
    	lineClassName.push("dtw-<?=$row->warna_id;?>");
    	warna_count++;    	
    <?}?>
	$("#warna-bulan-count").text(warna_count);


	let idx = 0;
	let nn = 0;
    <?
	$max_trx[0] = 0;
	$max_nilai_barang = [];
	$max_trx_barang = [];
    foreach ($data_show as $key => $value) {?>
    	period = "<?=date('Y-m',strtotime($tahun.'-'.str_pad($key,2,'0', STR_PAD_LEFT).'-01'));?>";
    	// console.log(period);
        hoverContentNilai +=`<div class='morris-hover-row-label'>${period}</div>`;
        if (<?=$key-1;?> != idx) {
        	for (var i_dx = idx; i_dx < <?=$key-1?>; i_dx++) {
        		let data_test = {
		            'period': "<?=$tahun?>-"+(idx+1).toString().padStart(2,'0'),
		            <?$idx_c = 0;foreach ($data_warna as $row) {?>
		            	"<?=$row->warna_jual;?>" : 0,
		            <?$idx_c++;}?>
		        };

		        let data_n = {
		            'period': "<?=$tahun?>-"+(idx+1).toString().padStart(2,'0'),
		        	"nilai" : 0
		        }

		        let data_tr = {
			        'period': "<?=$tahun?>-"+(idx+1).toString().padStart(2,'0'),
		        	"transaksi" : 0	
		        }
		        data_nilai[idx] = data_test;
		        data_trx[idx] =data_test;
		        data_nilai_barang[idx] = data_n;
		        data_trx_barang[idx] = data_tr;
		        idx++;
        	};
        };

        data_nilai_barang[<?=$key - 1;?>]= {
        	'period': period,
        	'nilai': <?=$data_show[$key]->penjualan?>,
        	'transaksi': <?=$total_trx[$key]?>*10000000,
        }

		if(nn == 0){
			nn = <?=$data_show[$key]->penjualan?>/<?=$total_trx[$key]?>;
		}

		<? 
		array_push($max_nilai_barang, $data_show[$key]->penjualan);
		array_push($max_trx_barang, $total_trx[$key]);
		?>
		

        data_trx_barang[<?=$key - 1;?>]= {
        	'period': period,
        	'transaksi': <?=$total_trx[$key]?>*10000000
        }

    	data_nilai[<?=$key - 1;?>] = {
            'period': period,
            <?$idx_c = 0;foreach ($data_warna as $row) {?>
            	"<?=$row->warna_jual;?>" : parseFloat(<?=(isset($penjualan_data[$key][$row->warna_id]) ? $penjualan_data[$key][$row->warna_id] : 0 );?>),
            <?$idx_c++;}?>
        };

        <?foreach ($data_warna as $row) {?>
        	val = parseFloat(<?=(isset($penjualan_data[$key][$row->warna_id]) ? $penjualan_data[$key][$row->warna_id] : 0 );?>);
	        hoverContentNilai +=`<div class='morris-hover-point'>
				<?=$row->warna_jual?>:${val}</div>`;
    	<?}?>


        data_trx[<?=$key - 1;?>] = {
            'period': period,
            <?foreach ($data_warna as $row) {
            	array_push($max_trx, (isset($penjualan_data[$key][$row->warna_id]) ? count($total_trx_warna[$key][$row->warna_id]) : 0 ));?>
            	"<?=$row->warna_jual;?>" : parseFloat(<?=(isset($penjualan_data[$key][$row->warna_id]) ? count($total_trx_warna[$key][$row->warna_id]) : 0 );?>),
            <?}?>
        };

        idx++;

    <?}?>

	<?
	$mb = 0;
	if (count($max_nilai_barang) > 0) {
		$mb = (int)max($max_nilai_barang);
	}
	$mbp = strlen((string)$mb) - 1;
	$bg = pow(10, $mbp);

	$mt = 0;
	if (count($max_nilai_barang) > 0) {
		$mt = (int)max($max_trx_barang);
	}
	$mtb = strlen((string)$mt)-1;
	$tg = pow(10, $mtb);

	$max_bg = ceil($mb/$bg)*$bg;
	$max_tg = ceil($mt/$tg)*$tg;

	foreach ($data_show as $key => $value) {
		$trx_edit = $total_trx[$key] / $max_tg * $max_bg;  ?>
    	period = "<?=date('Y-m',strtotime($tahun.'-'.str_pad($key,2,'0', STR_PAD_LEFT).'-01'));?>";
		data_nilai_barang2[<?=$key - 1;?>]= {
        	'period': period,
        	'nilai': <?=$data_show[$key]->penjualan?>,
        	'transaksi': <?=$trx_edit;?>,
        }
	<?}?>
    // console.log(data_nilai);
    console.log(<?=ceil($mb/$bg)*$bg;?>);

	console.log(<?=ceil($mt/$tg)*$tg;?>);

    // console.log(monthKey);
    // console.log(data_bulan);

    // console.log(data_nilai);
    // console.log(data_trx);
    // console.log(data_trx_barang);

    let config_nilai = {
		element:'sales_statistics_nilai',
		data: data_nilai,
		xkey: 'period',
		ykeys: monthKey,
		labels: 'monthKey',
		fillOpacity: 0.6,
		hideHover: 'auto',
		behaveLikeLine: true,
		resize: true,
		pointFillColors:['#ffffff'],
		pointStrokeColors: line_colors,
		lineColors:line_colors,
		hoverCallback: function(index, options, content, row) {
			let idx = 0
			let contentHtml = `<table>`;
			let sortable_dt = [];
			$.each(row, function(i,v){
				if (warna_bulan_show[i]) {
					if (idx == 0) {
						contentHtml += `<tr><td class='text-center' colspan='3'><b>${v}</b></td></tr>`;
					}
					else{
						sortable_dt.push([i,v]);
					}
				};
				idx++;
			});

			sortable_dt.sort(function(a,b){
				return b[1] - a[1]
			});

			for (var i = 0; i < sortable_dt.length; i++) {
				contentHtml += `<tr><td class='text-right'>${sortable_dt[i][0]}</td> <td style='padding:0 10px'>:</td> <td class='text-left'>${change_number_format(sortable_dt[i][1])}</td></tr>`;
			};
			contentHtml += '</table>';
			// console.log(contentHtml);
	    	return(contentHtml);
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
		hideHover: 'false',
		behaveLikeLine: true,
		resize: true,
		pointFillColors:['#ffffff'],
		pointStrokeColors: line_colors,
		lineColors:line_colors,
	    hoverCallback: function(index, options, content, row) {
	    	let idx = 0
			let contentHtml = `<table>`;
			let sortable_dt = [];
			$.each(row, function(i,v){
				if (warna_bulan_show[i]) {
					if (idx == 0) {
						contentHtml += `<tr><td class='text-center' colspan='3'><b>${v}</b></td></tr>`;
					}
					else{
						sortable_dt.push([i,v]);
					}
				};
				idx++;
			});

			sortable_dt.sort(function(a,b){
				return b[1] - a[1]
			});

			for (var i = 0; i < sortable_dt.length; i++) {
				contentHtml += `<tr><td class='text-right'>${sortable_dt[i][0]}</td> <td style='padding:0 10px'>:</td> <td class='text-left'>${change_number_format(sortable_dt[i][1])}</td></tr>`;
			};
			contentHtml += '</table>';
	        return contentHtml;
	    },
	};

	let config_nilai_barang = {
		element:'sales_statistics_nilai_barang',
		data: data_nilai_barang2,
		xkey: 'period',
		ykeys: ['nilai', 'transaksi'],
		labels: ['nilai', 'transaksi'],
		fillOpacity: 0.6,
		hideHover: 'auto',
		behaveLikeLine: true,
		resize: true,
		pointFillColors:['#ffffff'],
		pointStrokeColors: ["#800"],
		smooth:false,
		lineColors:['#0b62a5','#68abfc'],
		hoverCallback: function(index, options, content, row) {
			// console.log(index, row.period);
			// console.log(data_trx_barang[index]);
			let t = new Date(row.period)
			const mo = t.toLocaleString('default', { month: 'long' });
			return `<div style="padding:10px 20px; font-size:1.1em; text-transform:uppercase">${mo} ${t.getFullYear()}<br/> <table><tr><td style='text-align:right'> Nilai </td><td style='padding:0 10px'>:</td> <td style='text-align:left'><b>${change_number_format(row.nilai)}</b></td></tr><tr><td style='text-align:right'> Transaksi</td> <td style='padding:0 10px'>:</td> <td style='text-align:left'><b>${data_trx_barang[index].transaksi/10000000}</b></td></tr></div>`
		},
	};

	console.log(data_nilai_barang);
	console.log(data_trx_barang);

	let config_trx_barang = {
		element:'sales_statistics_trx_barang',
		data: data_trx_barang,
		xkey: 'period',
		ykeys: ['transaksi'],
		labels: ['transaksi'],
		fillOpacity: 0.6,
		hideHover: 'auto',
		behaveLikeLine: true,
		resize: true,
		smooth:false,
		pointFillColors:['#ffffff'],
		pointStrokeColors: ["#800"],
	};

	Morris.Line(config_nilai_barang);
	Morris.Line(config_trx_barang);
	Morris.Line(config_nilai);
	Morris.Line(config_trx);

    /*$("#sales_statistics_nilai").click(function(){
    	$(this).find('path').each(function(){
    		console.log($(this).attr('stroke'));
    	});
    });*/
	
	$(".hideBody, .showBody").click(function(){
		$(this).closest(".portlet-container").find('.portlet-body').toggle();
		$(this).closest(".portlet-container").find('.showBody').toggle();
		$(this).closest(".portlet-container").find('.hideBody').toggle();
	});

	$(window).click(function(e) {
		<?if(is_posisi_id()== 1){?>
			// alert(!$(e.target).attr('trx-info-container') +' - '+ !$(e.target).hasClass('trx-info-text'));
		<?}?>
		if(!$(e.target).attr('trx-info-container') && !$(e.target).hasClass('trx-info-text') ){
			$('.trx-info, .trx-info-customer').hide();
			$('.trx-info-text').css('font-weight','normal');
		}
	}); 
	
});

function get_harian(){
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
        $.each(JSON.parse(data_respond),function(k,v){
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
		resize: false,
		pointFillColors:['#ffffff'],
		pointStrokeColors: ['black'],
		lineColors:['gray']
		};

		config.element = 'sales_statistics';
		Morris.Area(config);

    });
}

function get_harga_history(){

	var tgl_awal = "<?=date('Y-m-d', strtotime($tanggal_awal.' -1 Year'));?>";
	var tgl_akhir = "<?=$tanggal_akhir;?>";
	var harga_master_awal = 0;

	var tAwal = new Date(tgl_awal);
	var tAkhir = new Date(tgl_akhir);
	
    var data_st = {};
	data_st['barang_id'] = "<?=$barang_id?>";
	data_st['warna_id'] = "<?=$warna_id?>";

    var url = "master/get_harga_history";
    ajax_data_sync(url,data_st).done(function(data_respond  /*,textStatus, jqXHR*/ ){
        // console.log(data_respond)
        let res = JSON.parse(data_respond);
        $.each(res.harga_beli_history, function(i,v){

			let tAwal2 = tAwal.getTime();
			let tAkhir2 = tAkhir.getTime();
			let tmp = new Date(v.tahun_bulan).getTime();
			
			if (tAwal2 <= tmp && tAkhir2 >= tmp) {
				harga_history['beli'][`i-${v.barang_id}`]['tanggal'].push(v.tahun_bulan);
				harga_history['beli'][`i-${v.barang_id}`][v.tahun_bulan] = {
					harga : v.harga_beli_data,
					trx : v.count_trx
				};
			}
        });

		$.each(res.harga_jual_history_master, function(i,v){

			let tAwal2 = tAwal.getTime();
			let tAkhir2 = tAkhir.getTime();
			let tmp = new Date(v.tahun_bulan).getTime();


			if (tAwal >= tmp) {
				harga_master_awal = v.harga_jual_data;
			}

			if (tAwal2 <= tmp && tAkhir2 >= tmp) {
				harga_history['master'][`i-${v.barang_id}`]['tanggal'].push(v.tahun_bulan);
				harga_history['master'][`i-${v.barang_id}`][v.tahun_bulan] = {
					bulan : v.tahun_bulan,
					harga : v.harga_jual_data
				};
			}

			// console.log('master',hJ);
			// harga_history['master'][`i-${v.barang_id}`]['master'] = v.harga_master;
		});


		$.each(res.harga_jual_history_all, function(i,v){
			let tAwal2 = tAwal.getTime();
			let tAkhir2 = tAkhir.getTime();
			let tmp = new Date(v.tahun_bulan).getTime();
			
			if (tAwal2 <= tmp && tAkhir2 >= tmp) {
				harga_history['jual'][`i-${v.barang_id}`]['tanggal'].push(v.tahun_bulan);
				harga_history['jual'][`i-${v.barang_id}`][v.tahun_bulan] = {
					harga : v.harga_jual_data,
					trx : v.count_trx,
					nama_customer : v.nama_customer
				};
			}
        })

		$.each(res.harga_jual_history_master_all, function(i,v){
			let tAwal2 = tAwal.getTime();
			let tAkhir2 = tAkhir.getTime();
			let tmp = new Date(v.tahun_bulan).getTime();
			
			if (tAwal2 <= tmp && tAkhir2 >= tmp) {
				harga_history['jual_master'][`i-${v.barang_id}`]['tanggal'].push(v.tahun_bulan);
				harga_history['jual_master'][`i-${v.barang_id}`][v.tahun_bulan] = {
					harga : v.harga_jual_data,
					trx : v.count_trx
				};
			}
        })
		

		

		// console.log(harga_history['jual_master']);
		
        $.each(res.harga_jual_credit_history, function(i,v){
            // console.log(i, v);
            let harga_jual_list = v.harga_jual_data.split(',');
            let bulan = v.tahun_bulan.split(",");
            let nama_customer = v.nama_customer.split(",");
            harga_history['jual_kredit'][`i-${v.barang_id}`] = [];
            let hJ = [];
            let nC = [];

            let row_tanggal = '<th>PERIODE</th>';
            let row_harga = '<th>HARGA</th>';
            for (let i = 0; i < harga_jual_list.length; i++) {
                if (typeof hJ[bulan[i]] === 'undefined') {
                    hJ[bulan[i]] = [];
                    nC[bulan[i]] = [];
                }
                hJ[bulan[i]].push(harga_jual_list[i]);    
                nC[bulan[i]].push(nama_customer[i]);    
            }

            let nB = [];
            nB = bulan.filter(function(bln, index, self){
                return self.indexOf(bln) === index;
            });

            // console.log(nB)
            

            harga_history['jual_kredit'][`i-${v.barang_id}`]['tanggal'] = nB;
            harga_history['jual_kredit'][`i-${v.barang_id}`]['harga'] = hJ;
            harga_history['jual_kredit'][`i-${v.barang_id}`]['master'] = v.harga_jual_master;
            harga_history['jual_kredit'][`i-${v.barang_id}`]['customer'] = nC;
            // harga_history['jual'][`i-${v.barang_id}`]['tanggal'] = bulan;
            // harga_history['jual'][`i-${v.barang_id}`]['harga'] = hJ;
            // harga_history['jual'][`i-${v.barang_id}`]['master'] = v.harga_jual_master;
        });

		showHargaHistory("i-<?=$barang_id?>");
        // console.log(harga_history['jual_kredit']);

        // console.log(harga_history);
    });
}

function showHargaHistory(barang_id){
	
    console.log(harga_history['beli'][barang_id])
    console.log(harga_history['jual'][barang_id])
    console.log(harga_history['jual_master'][barang_id])

	let hb = harga_history['beli'][barang_id];
	let hj = harga_history['jual'][barang_id]
	let hjm = harga_history['jual_master'][barang_id]
	let hm = harga_history['master'][barang_id]


	let tgl_beli = harga_history['beli'][barang_id]['tanggal'];
	let tgl_jual = harga_history['jual'][barang_id]['tanggal'];
	let tgl_master = harga_history['master'][barang_id]['tanggal'];
	let tgl_jual_master = harga_history['jual_master'][barang_id]['tanggal'];


	let tgls = tgl_beli.concat(tgl_jual).concat(tgl_master).concat(tgl_jual_master);

    nD = [...new Set(tgls)];
    nD.sort();

	console.log('tgls',nD);

    let row_tanggal = '<th>BULAN</th>';
    let row_harga_beli = '<th>BELI</th>';
    let row_harga_jual = '<th>JUAL</th>';
    let row_harga_jual_master = '<th>MASTER</th>';
    let row_harga_jual_kredit = '<th>JUAL KREDIT</th>';

	let jmb = 0;
	

	for (let i = 0; i < nD.length; i++) {

		let nT = new Date(nD[i])
        const mo = nT.toLocaleString('default', { month: 'short' });

        row_tanggal += `<td class='text-right'>${mo} ${nT.getFullYear()}<td>`;

		if (typeof hb[nD[i]] !== 'undefined' ) {
			let s = parseFloat(hb[nD[i]].harga);
			if (s > 0 ) {
				s = change_number_format2(parseFloat(s)).replace(",00","");
			}
            row_harga_beli += `<td class='text-right'>
                    ${s}
                <td>`;
		}else{
            row_harga_beli += `<td class='text-right'> - <td>`;
        }

		if (typeof hj[nD[i]] !== 'undefined' ) {
			let sList = hj[nD[i]].harga.split(",");
			let tmList = hj[nD[i]].trx.split(",");
			let cList =  hj[nD[i]].nama_customer.split("??");
			

			let s = '';
			let tm = '';
			let hjmTemp = [];
			if (typeof hjm[nD[i]] !== 'undefined') {
				hjmTemp = hjm[nD[i]].harga.split(",");
			}

			for (let x = 0; x < sList.length; x++) {
				if (sList[x] > 0 ) {
					let cs = cList[x].split(",");
					let isMaster = false;
					if (hjmTemp.length > 0) {
						for (let y = 0; y < hjmTemp.length; y++) {
							if (nD[i] == '2021-01' ) {
								// console.log(nD[i], hjmTemp[y], '==' ,sList[x]);
							}
							if (hjmTemp[y] == sList[x]) {
								isMaster = true;
								z = cs.length;
								y = hjmTemp.length;
							}
						}
					}
					if (!isMaster) {
						let n = [];
						let ntrx = [];
						for (let xx = 0; xx < cs.length; xx++) {
							if (!n.includes(cs[xx])) {
								n.push(cs[xx]);
								ntrx[cs[xx]] = 1;
							}else{
								ntrx[cs[xx]]++;
							}
							
						}

						let rShow = '';
						for (let xx = 0; xx < n.length; xx++) {
							rShow += `<div>${n[xx]}
									<b style='float:right; font-size:0.9em'>${ntrx[n[xx]]}</b>
								</div>
								<hr style='margin:0; padding:0'/>`;
						}

						s += `<div class='trx-info-container' style='position:relative'>
								<span onclick="showTrx('i-${nD[i]}-${parseFloat(sList[x])}')" class='trx-info-text ti-${nD[i]}-${parseFloat(sList[x])}'>${change_number_format2(parseFloat(sList[x])).replace(",00","")}</span>
								<div class='trx-info-customer i-${nD[i]}-${parseFloat(sList[x])}' style='display:none'>
									<b>${tmList[x]} transaksi<br/></b>${rShow}
								</div>
							</div>`;
					}
				}
			}

            row_harga_jual += `<td class='text-right'>
                    ${s}
                <td>`;
		}else{
            row_harga_jual += `<td class='text-right'> - <td>`;
        }

		if (typeof hjm[nD[i]] !== 'undefined' || typeof hm[nD[i]] !== 'undefined' ) {
			let s ='';
			let tm = '';
			if (typeof hjm[nD[i]] !== 'undefined') {
				let sList = hjm[nD[i]].harga.split(",");
				let tmList = hjm[nD[i]].trx.split(",");
				
				for (let x = 0; x < sList.length; x++) {
					if (sList[x] > 0 ) {

						//`<span class="trx-info badge badge-primary"> ${tmList[x]} </span></div>`
						if (jmb > 0 && jmb != sList[x]) {
							s += `<div  style='position:relative'>`+change_number_format2(parseFloat(sList[x])).replace(",00","");
							jmb = sList[x];
						}else if ( sList.length > 1){
							s += `<div  style='position:relative'>`+change_number_format2(parseFloat(sList[x])).replace(",00","");
							jmb = sList[x];
						}else if (jmb == 0){
							s += `<div  style='position:relative'>`+change_number_format2(parseFloat(sList[x])).replace(",00","");
							jmb = sList[x];
						}
					}

					if (sList.length > 1 && x != (sList.length-1)) {
						s += "<i class='fa fa-caret-down'></i>";
					}
					
				}
			}else{
				s = parseFloat(hm[nD[i]].harga);
				if (s > 0 ) {
					s = change_number_format2(parseFloat(s)).replace(",00","");
				}
			}

            row_harga_jual_master += `<td class='text-center' style='position:relative'>
                    ${s}
                <td>`;
		}else{
            row_harga_jual_master += `<td class='text-right'> - <td>`;
        }
	}

	
	hj_master_beli = "<?=$harga_master_beli;?>";
	hj_master_jual = "<?=$harga_master_jual;?>";

    let tbl = `<table class='table'>
        <tr>${row_tanggal}<th class='text-right'>MASTER</th></tr>
        <tr>
            ${row_harga_beli}
            <th class='text-right' style='border-left:1px solid #ccc'>
                ${change_number_format(parseFloat(hj_master_beli))}
            </th>
        </tr>
		<tr style='background:rgba(255,0,0,0.3)' style='border-left:1px solid #ccc'>
            ${row_harga_jual_master}
            <th class='text-right' style='border-left:1px solid #ccc'>
                ${change_number_format(parseFloat(hj_master_jual))}
            </th>
        </tr>
        <tr>
            ${row_harga_jual}
            <th class='text-right'  style='border-left:1px solid #ccc'>
            </th>
        </tr>
    </table>`;
    
    $('#harga-history').html(tbl);

    if (typeof tJC !== 'undefined') {
        let rT = '<th>BULAN</th>';
        let rHC = '<th>JUAL</th>';
        let tbl2 = '';
        let hBJ = 0;
        for (let i = 0; i < tJC.length; i++) {
            let nT = new Date(tJC[i]+'-01');
            let icon = '';
            const mo = nT.toLocaleString('default', { month: 'short' });
    
            rT += `<td class='text-right'>${mo} ${nT.getFullYear()}</td>`;
            // console.log(nC[tJC[i]]);
            let x = hJC[tJC[i]];
            let y = nC[tJC[i]];
    
            let z = [];
            for (let j = 0; j < x.length; j++) {
                // console.log(x[j],y[j]);
                let aa = y[j].split('??')
                z.push(`<div id="${mo}-${nT.getFullYear()}-${x[j]}-cont" class='history-kredit-info' onclick="showNames('${mo}-${nT.getFullYear()}-${x[j]}')">
                        ${change_number_format(x[j])} <div id="${mo}-${nT.getFullYear()}-${x[j]}" class='nama-list' hidden>${aa.join("<hr style='border-color:#555;padding:0px; margin:2px'/>")}</div>
                    </div>`);
                
            }
            rHC += `<td class='text-right'>${z.join('')}</td>`;
        }
    
        let w = "";
        if (tJC.length <=3) {
            w = "width:50%;";
        }
        $('#harga-history-kredit').html(`<table style="${w}" class='table'><tr>${rT}</tr> <tr>${rHC}</tr></table>`);
        
    }
}

function showNames(divId){
    // alert();
    $(".history-kredit-info div").hide();
    $(".history-kredit-info").removeClass("history-kredit-info-active");
    
    $(`#${divId}-cont`).addClass("history-kredit-info-active");
    $(`#${divId}`).toggle();
}

function showTrx(className){
	$(`.trx-info-text`).css('font-weight','normal');
	$('.trx-info, .trx-info-customer').hide();
	$(`.t${className}`).css('font-weight','bold');
	$(`.${className}`).toggle();
	// $(window).find(`#${className}`).toggle();
}
</script>
