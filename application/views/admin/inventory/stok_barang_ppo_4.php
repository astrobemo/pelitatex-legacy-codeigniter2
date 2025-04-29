<link rel="stylesheet" type="text/css" href="<?=base_url('assets/global/plugins/bootstrap-select/bootstrap-select.min.css'); ?>"/>
<style type="text/css">

html{
	width: auto;
	display: inline-block;
	background: #fff !important;
}

#general_table, #general_table tr td, #general_table tr th{
	/*font-size: 0.8em;*/
}

.container{
	width: 100%;
	/*display: inline-block;	*/
}

.barang-list-container, .barang-list-pool{
	height: 250px;
	overflow: auto;
	border:2px dashed #ddd;
}

.item-list li, .item-list-selected li{
	cursor: pointer;
	margin-bottom: 0px;
	padding: 3px 0 3px 25px; 
	font-size: 1.2em;
	list-style-type: none;
	border-bottom: 0.5px solid #eee;
}
.item-list, .item-list-selected{
	padding: 0px;
}

.item-list li:hover{
	background: #ddd;
}

.item-list-selected li:hover{
	background: lime;
}

.list-hidden{
	display: none;
}

.garis-bawah{
	border-bottom: 3px solid #171717 !important;
}

.column-data{
	width: 150px !important;
}

.column-data-hide{
	width: 40px;
}

#include-click-info{
	display: none;
	position: absolute;
	font-size: 0.8em;
	background: #eee;
}

#daftar-po:hover~#include-click-info{
	display: block;
}

#general_table tr td, #general_table tr th{
	/*font-size: 16px;*/
	vertical-align:middle;
}

#general_table input:read-only{
	background: #eee;
}

.roll-hide{
	text-decoration: line-through;
}

.roll-header{
	cursor: pointer;
}

/*==================FREEZE PANES===============================*/

#body-table{
	/*overflow: auto;*/
	-webkit-full-screen{
		max-height: 1000px;
		overflow: scroll;
		max-width: 100%;
	}
}

#general_table thead th{
	position: -webkit-sticky;
	position: sticky;
	background: #fff;
	top: 50px;
	z-index: 99;
	min-height: 50px;
	border-bottom: 2px solid #ddd;
	background: #eee;

	-webkit-full-screen{
		top: 0px;
	}
}

#general_table thead tr:first-child th { position: sticky; top: 0; }

#general_table tbody tr td:first-child{position: sticky; left:0;}


#table-header{
  position: -webkit-sticky;
  position: sticky;
  top: 0;
  /*background-color: yellow;*/
  z-index: 9999;
  display: none;
}

#table-header table tr th{
	vertical-align: middle;
}


/*=================================================*/
.bar-progress{
	width: 100%;
	height: 20px;
	font-size: 0.9em;
	font-weight: bold;
	text-align: center;
}

.bar-progress-jual{
	width: 100%;
	height: 40px;
	font-size: 0.9em;
	font-weight: bold;
	text-align: center;
}
/*=================================================*/

:-webkit-full-screen {
	overflow: auto;
	background: #fff;
}

/* Firefox syntax */
:-moz-full-screen {
	overflow: auto;
	background: #fff;
}

/* IE/Edge syntax */
:-ms-fullscreen {
	overflow: auto;
	background: #fff;
}

/* Standard syntax */
:fullscreen {
	overflow: auto;
	background: #fff;
}

.rekap-warna{
	background: khaki;
}

.rekap-po{
	background: lightcyan;
}

	@media print {
		a[href]:after {
		    content: none !important;
		}

		#general_table tbody tr td{
			border: 1px solid #ddd !important;
		}

		#general_table tbody tr:nth-child(even){
			background-color: #eee !important;
		}
	}

</style>

<?

$tanggal_get = $tanggal;
$count_po = 0;
$batch_id_aktif_list = array();
$status_include = array();
$status_ppo = 1;

foreach ($batch_for_pre_po as $row) {
	if ($row->status_include == 1) {
		$status_include[$row->po_pembelian_batch_id] = true;
	}else{
		$status_include[$row->po_pembelian_batch_id] = false;
	}

	$batch_po_total[$row->po_pembelian_batch_id] = 0;
	$batch_bm_total[$row->po_pembelian_batch_id] = 0;
	$batch_jual_total[$row->po_pembelian_batch_id] = 0;
	$batch_sisa_total[$row->po_pembelian_batch_id] = 0;
	$batch_masa_total[$row->po_pembelian_batch_id] = 0;
	$batch_trx_total[$row->po_pembelian_batch_id] = 0;
	$batch_lt_total[$row->po_pembelian_batch_id] = 0;
}

foreach ($ppo_lock_data as $row) {

	foreach ($status_include as $key => $value) {
		$status_include[$key] = false;
	}
	$po_pembelian_batch_id_aktif = explode(',', $row->po_pembelian_batch_id_aktif);
	foreach ($po_pembelian_batch_id_aktif as $key => $value) {
		$status_include[$value] = true;
	}
	$status_ppo = $row->status;
}

foreach ($status_include as $key => $value) {
	if ($value) {
		$count_po++;
		array_push($batch_id_aktif_list, $key);
	}
}

$count_show = $count_po;


foreach ($stok_awal as $row) {
	$sa[$row->warna_id] = $row->qty;
}

foreach ($list_stok_by_tanggal as $row) {
	$stok_bt[$row->warna_id][$row->tanggal] = (isset($stok_bt[$row->warna_id][$row->tanggal]) ? $stok_bt[$row->warna_id][$row->tanggal].'=??='.$row->qty.'??'.$row->po_pembelian_batch_id : $row->qty.'??'.$row->po_pembelian_batch_id );
	$stok_per_po[$row->warna_id][$row->po_pembelian_batch_id] = 0;
	$stok_po_all[$row->po_pembelian_batch_id] = 0;
}

foreach ($list_stok as $row) {
	$stok[$row->warna_id][$row->po_pembelian_batch_id] = $row;
	$stok_bm[$row->warna_id][$row->po_pembelian_batch_id] = $row->qty;
	$bm_all[$row->warna_id] = (isset($bm_all[$row->warna_id]) ? $bm_all[$row->warna_id] + $row->qty : $row->qty); 
	$bm_first[$row->warna_id][$row->po_pembelian_batch_id] = $row->tanggal_first;
	$bm_first_po[$row->po_pembelian_batch_id][$row->warna_id] = $row->tanggal_first;
}

$stok_brg = array();
foreach ($stok_barang as $row) {
	$stok_brg[$row->warna_id] = 0;
	$stok_brg_roll[$row->warna_id] = 0;
	foreach ($this->gudang_list_aktif as $row2) {
		$stok_brg[$row->warna_id] += $row->{"gudang_".$row2->id."_qty"};
		$stok_brg_roll[$row->warna_id] += $row->{"gudang_".$row2->id."_roll"};
	}
}

$deleted = array();
$total_ppo_qty = 0;
foreach ($data_set as $val) {
	// $total_ppo_qty += $val->qty;
	$isListed[$val->warna_id] = 1;
	$stok_now[$val->warna_id] = (isset($sa[$val->warna_id]) ? $sa[$val->warna_id] : 0);

	$jual_latest[$val->warna_id] = array();
	$terjual_sa[$val->warna_id] = 0;
	// $jual_qty_latest_sisa[$val->warna_id] = 0;
	// $jual_qty_latest_qty[$val->warna_id] = '';
	$latest = 0;
	// echo $val->warna_id.' = '.(isset($jual[$val->warna_id]) ? count($jual[$val->warna_id]) : 0)."<br/>";
	if (isset($jual[$val->warna_id])) {
		foreach ($jual[$val->warna_id] as $key => $value) {
			//set milestone sebelum dikurangin
			$qty_now = $stok_now[$val->warna_id];
			//stok awal terus dikurangin qty jual
			$stok_now[$val->warna_id] -= $value->qty;
			
			// klo stok nya masih di atas 0 
			if ($stok_now[$val->warna_id] > 0) {
				
				//data penjualan terakhir
				$jual_latest[$val->warna_id] = $value;
				//data qty(total) di penjualan terakhir
				$jual_qty[$val->warna_id] = $value->qty; 
				array_push($deleted, $key);
				unset($jual[$val->warna_id][$key]);
			}else if($latest == 0){
				//data penjualan terakhir waktu pas stok awal 0
				//sisa qty penjualan di pas stok awal 0
				$jual_qty_latest_sisa[$val->warna_id][$key] = $value->qty - $qty_now;
				//qty as penjualan pad di stok 0
				$jual_qty_latest_qty[$val->warna_id][$key] = $value->qty;
				//data penjualan pas stok awal habis, untuk dipake stok awal data
				$latest_data_jual[$val->warna_id][$key] = $value;
				$latest++;
			}
		}
	}
}

$color_idx = ['#bae1ff','#baffc9','#ffffba','#ffdfba','#ffb3ba'];

$po_pembelian_kurang = array(); $j=0;
foreach ($po_pembelian_untuk_ppo as $row) {
	if ($row->sisa_qty - $row->ppo_qty < 0) {
		$po_pembelian_kurang[$j] = $row;
		$j++;
	}
}

$readonly_status = ($ppo_lock_id != '' && $status_ppo == 0 ? 'readonly' : '');

?>

<div class="page-content">
	<div class='container'>

		<div class="modal fade" id="portlet-config-pin" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body">
						<form action="<?=base_url('inventory/ppo_request_open');?>" class="form-horizontal" id="form-request-open" method="post">
							<h3 class='block'> Request Open</h3>
							
							<div class="form-group">
			                    <label class="control-label col-md-3">PIN<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<input name='ppo_lock_id' value='<?=$ppo_lock_id;?>' hidden>
									<input name='pin' type='password' class="pin_user form-control">
			                    </div>
			                </div>	
		                </form>		                
					</div>

					<div class="modal-footer">
						<button type="button" class="btn btn-active blue btn-request-open">OPEN</button>
						<button type="button" class="btn default" data-dismiss="modal">Close</button>
					</div>
				</div>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
		</div>

		<div class="modal fade" id="portlet-config-export" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body">
						<form action="<?=base_url('inventory/ppo_export_to_po ');?>" class="form-horizontal" id="form-export" method="post" target="_blank">
							<h3 class='block'> Export</h3>
							
							<div class='row'>
								<div class='col-md-8 col-xs-12'>
									<div class="form-group">
					                    <label class="control-label col-md-5">Tanggal<span class="required">
					                    * </span>
					                    </label>
					                    <div class="col-md-7">
					                    	<input name='barang_id' hidden value="<?=$barang_id;?>">
					                    	<input name='tanggal' class='form-control date-picker' value="<?=date('d/m/Y');?>">
					                    </div>
					                </div>
								</div>
								<div class='col-xs-12 col-md-12'>
									<div class='col-md-8 col xs-12'>
										<div class="form-group">
						                    <label class="control-label col-md-5">PO Available<span class="required">
						                    * </span>
						                    </label>
						                    <div class="col-md-7">
						                    	<input name='barang_id' value='<?=$barang_id;?>' hidden>
						                    	<input name='ppo_lock_id' value='<?=$ppo_lock_id;?>' hidden>
						                    	<div class="radio-list">
						                    		<?foreach ($po_pembelian_untuk_ppo as $row) {
														if ($row->sisa_qty - $row->ppo_qty >= 0) {?>
															<label class="radio">
															<input type="radio" class='po-radio-export' data-po='1' name="po_pembelian_detail_id" value="<?=$row->po_pembelian_detail_id?>"> <?=$row->po_number?> / <?=number_format($row->harga,'0',',','.')?></label>
						                    			<? }
						                    		}
						                    		foreach (array_reverse($po_pembelian_kurang) as $key => $value) {?>
					                    				<label class="radio">
														<input type="radio" class='po-radio-export' data-po='2' name="po_pembelian_detail_id" value="<?=$value->po_pembelian_detail_id?>"> <?=$value->po_number?> / <?=number_format($row->harga,'0',',','.')?> <i class='fa fa-warning'></i></label>
						                    		<?}
						                    		?>
												</div>
						                    	<?/*<select name="po_pembelian_id" class='form-control' id='po_pembelian_select'>
					                				<option value=''>Pilihan..</option>
						                    		<?foreach ($po_pembelian_untuk_ppo as $row) {?>
						                    			<option value="<?=$row->po_pembelian_id?>"><?=$row->po_number;?><i class='fa fa-check'></i></option>
						                    		<? } ?>
						                    	</select>*/?>
						                    </div>
						                </div>	
									</div>
									<div class='col-md-4 col-xs-12' style='padding:10px; background-color:#fffac7'>
										<b>INFO</b>
						                <?foreach ($po_pembelian_untuk_ppo as $row) {?>
						                	<div id='info-po-<?=$row->po_pembelian_detail_id?>' class='info-po-container' hidden>
						                		<table>
						                			<tr>
						                				<td>Sisa Qty</td>
						                				<td> : </td>
						                				<td style="<?=($row->sisa_qty - $row->ppo_qty < 0 ? 'color:red' : '');?>"><?=number_format($row->sisa_qty,'0',',','.');?></td>
						                			</tr>
						                			<tr>
						                				<td>Tanggal</td>
						                				<td> : </td>
						                				<td><?=is_reverse_date($row->tanggal);?></td>
						                			</tr>
						                			<tr>
						                				<td>Batch</td>
						                				<td> : </td>
						                				<td><b><?=$row->batch;?></b></td>
						                			</tr>
						                			<tr>
						                				<td>Next Batch</td>
						                				<td> : </td>
						                				<td><b style='font-size:1.1em' class='next-batch'><?=$row->batch+1;?></b></td>
						                			</tr>
						                		</table>
						                	</div>
						                <?}?>
									</div>
								</div>
							</div>
		                </form>		                
					</div>

					<div class="modal-footer">
						<button type="button" class="btn btn-active blue btn-export">GENERATE</button>
						<button type="button" class="btn default" data-dismiss="modal">Close</button>
					</div>
				</div>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
		</div>

		<div class="modal fade" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body">
						<form action="<?=base_url('inventory/ppo_add_warna');?>" class="form-horizontal" id="form-add-warna" method="post">
							<h3 class='block'> + Warna</h3>
							
							<div class="form-group">
			                    <label class="control-label col-md-3">Warna<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<input name='barang_id' value='<?=$barang_id;?>' hidden>
			                    	<select name="warna_id" class='form-control' id='warna_id_select'>
		                				<option value=''>Pilihan..</option>
			                    		<?foreach ($this->warna_list_aktif as $row) { 
			                    			if (!isset($isListed[$row->id])) {?>
				                    			<option value="<?=$row->id?>"><?=$row->warna_beli;?></option>
			                    			<?}?>
			                    		<? } ?>
			                    	</select>
			                    </div>
			                </div>	
		                </form>		                
					</div>

					<div class="modal-footer">
						<button type="button" class="btn blue btn-warna-save">SAVE</button>
						<button type="button" class="btn default" data-dismiss="modal">Close</button>
					</div>
				</div>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
		</div>
	
		<div class="row margin-top-10">
			<div class="col-md-12">
				<div class="portlet light">
					<div class="portlet-title">
						<div class="caption caption-md">
							<i class="icon-bar-chart theme-font hide"></i>
							<span class="caption-subject theme-font bold uppercase hidden-print"><?=$breadcrumb_small;?></span>
						</div>
						<div class="actions">
							<a <?=($status_ppo != 1 ? "disabled" : "href='#portlet-config'");?> data-toggle='modal' class="btn btn-default btn-sm" title="<?=($ppo_lock_id != '' ? 'sudah di lock' : '')?>" >
								+ Warna </a>
							<button class="btn btn-default btn-sm" onclick="openFullscreen();">
							Fullscreen </button>
						</div>
					</div>
					<div class="portlet-body">
						<?/*$idx = 0;
						foreach ($pre_po_list_barang as $row) {?>
							<button style="font-weight:bold;<?=($idx==0 ? 'border:2px solid red' : 'border:2px solid' );?>" data-id="<?=$row->barang_id;?>" class="btn btn-md default btn-barang"><?=$row->nama_barang;?></button>
						<?$idx++;}*/
						?>
						<div class='hidden-print'>
							<table>
								<tr>
									<td>
										<form id="form-barang" >
											<table>
												<tr>
													<td>TANGGAL</td>
													<td> : </td>
													<td colspan='2'><input name='tanggal' id='tanggal' style="font-size:1.2em;" class='form-control date-picker' value="<?=is_reverse_date($tanggal_get);?>"></td>
												</tr>
												<tr>
													<td style='padding: 3px 0'>BARANG</td>
													<td style='padding: 3px 0'> : </td>
													<td colspan='2' style='padding: 3px 0'>
														<select name='barang_id' style="font-size:1.2em; width:350px;" id="barang_id_select" >
															<option value="">Pilih</option>
															<?foreach ($this->barang_list_aktif_beli as $row) {?>
																<option value="<?=$row->id?>" <?=($barang_id == $row->id ? 'selected' : '' )?> ><?=$row->nama;?><?=(is_posisi_id() == 1 ? '/'.$row->nama_jual : '');?></option>
															<?}?>
														</select>
														<input name='ppo_lock_id' id='ppo-lock-input' value="<?=$ppo_lock_id;?>" hidden>
													</td>
												</tr>
												<tr>
													<td></td>
													<td></td>
													<td <?=($ppo_lock_id !='' ? "style='width:30%; padding-right:5px'" : '')?>>
														<button class='btn btn-block default' id="btn-go">GO</button>
														
													</td>
													<td  <?=($ppo_lock_id != '' ? "style='width:30%;'" : '')?> >
														<?if ($ppo_lock_id != '') {?>
															<button class='btn btn-block green default' id="btn-go-reset"  >GO (RESET)</button>
														<?}?>
													</td>
												</tr>
												<tr <?=($ppo_lock_id == ''? 'hidden' : '');?> >
													<td></td>
													<td></td>
													<td>Gunakan Tombol ini<br/>untuk mengganti <br/>tanggal stok</td>
													<td>Gunakan RESET jika ingin<br/> <b>mengganti barang</b> atau<br/> mengabaikan PPO lock </td>
												</tr>
											</table>
										</form>
									</td>
									<td style='padding:0 0 10px 50px; position:relative'>
										<p><b>Daftar PO Lengkap</b>
										<b id='include-click-loading' style='color:red; font-size:1.2em' hidden>loading</b>
										</p>
										<div id="daftar-po" style="column-count:<?=(count($batch_for_pre_po) > 3 ? (count($batch_for_pre_po) > 6 ? 3 : 2) : 1 );?>; column-gap:50px">
											<?foreach ($batch_for_pre_po as $row) {?>
												<p style="cursor:pointer;<?=(!$status_include[$row->po_pembelian_batch_id] ? 'text-decoration:line-through' : '')?>" data-batch="<?=$row->po_pembelian_batch_id?>" data-include='<?=$status_include[$row->po_pembelian_batch_id];?>' class='btn-include'> <i style="color:<?=($status_include[$row->po_pembelian_batch_id] ? 'green' : 'red');?>" class="fa fa-<?=($status_include[$row->po_pembelian_batch_id] ? 'check' : 'times')?>"></i> <?=$row->batch;?></p> 
											<?}?>
										</div>
										<div id='include-click-info'>[click untuk include/exlcude]</div>
										
									</td>
								</tr>
							</table>
						</div>
						
						<!-- " <button class='btn btn-xs red btn-show-po' style='position:relative; top:-5px'>Show QTY PO</button> "." <button class='btn btn-xs blue btn-show-harga' style='position:relative; top:-5px'>Show Harga</button> "." <button class='btn btn-xs green btn-show-ockh' style='position:relative; top:-5px'>Show OCKH</button>" -->
						<hr class='hidden-print'/>
						<table width='100%'>
							<tr>
								<td class='text-left'>
									<h2 style='font-weight:bold; display:inline'> - <span class='nama_barang_tampil' ></span> -</h2> 
									<?if ($status_ppo != 1 && $ppo_lock_id != '') {?>
										<a href="#portlet-config-export" data-toggle='modal' class='btn btn-md blue'>Export ke PO</a>
									<?}?>

									<?if ($barang_id != '' || $ppo_lock_id != '') {?>
										<a href="<?=base_url()?>fifo/testing_fifo2?<?=($ppo_lock_id != '' ? 'ppo_lock_id='.$ppo_lock_id : 'barang_id='.$barang_id.'&tanggal='.$tanggal );?>" data-toggle='modal' class='btn btn-md green' target="_blank">RAW DATA</a>
									<?}?>
								</td>
								<td class='text-right'>
									<form>
										DAFTAR PPO : 
										<select name="ppo_lock_id" style='padding:2px 5px; font-size:1.1em; min-width:150px'>
											<option <?=($ppo_lock_id == '' ? 'selected' : '');?> value=""></option>
											<?foreach ($ppo_lock_list as $row) {?>
												<option <?=($ppo_lock_id == $row->id ? 'selected' : '');?> value="<?=$row->id;?>"><?=is_reverse_date($row->tanggal);?></option>
											<?}?>
										</select>
										<input type='submit' value="GO" id='btn-po-history'>
									</form>
								</td>
							</tr>
						</table>
						
						<hr class='hidden-print'/>
						<div id='body-table' style=''>
							<div>
								<table class="table table-striped table-bordered table-hover" id="general_table">
									<thead>
										<tr>
											<th scope="col" rowspan='2'>
												Warna
											</th>
											<th scope="col" rowspan='2' class='text-center'>
												STOK / <span class='roll-header roll-hide'>ROLL</span>
											</th>
											<td scope="col" <?=(count($batch_for_pre_po) == 0 ? 'hidden' : '')?> colspan='<?=$count_po;?>' class='text-center'>
												Data PO
											</td>
											<th scope="col" rowspan='2' class='text-center rekap-warna'>
												RECAP
											</th>
											<th scope="col" rowspan='2' class='text-center' id="po-table-header" style="width:110px" >
												STOK <br/>+<br/>
												OUT-<br/>STANDING
											</th>
											<th scope="col" rowspan='2' class='text-center' hidden>
												LEAD TIME
											</th>
											<th scope="col" rowspan='2' class='text-center' hidden>
												Penjualan
											</th>
											<th scope="col" rowspan='2' class='text-center' >
												PPO
											</th>
											<th scope="col" rowspan='2' class='text-center' >
												TOTAL
											</th>
											<!-- <th scope="col" rowspan='2' class='hidden-print' style="min-width:150px !important">
												Actions
											</th> -->
										</tr>
										<tr>
											<? $po_batch_id = array(); $qty_batch_id = array();
											for ($i=0; $i < 10 ; $i++) { 
												$_c1[$i] = rand(200,255);
												$_c2[$i] = rand(150,255);
												$_c3[$i] = rand(120,255);
											}
											$i =0; $idx_s = $count_show;
											foreach ($batch_for_pre_po as $row) {
												$batch[$row->po_pembelian_batch_id] = $row->po_pembelian_batch_id;
												$po_batch_id[$row->po_pembelian_batch_id] = true; 
												$qty_batch_id[$row->po_pembelian_batch_id] = 0;
												$color[$row->po_pembelian_batch_id] = '';
												if ($status_include[$row->po_pembelian_batch_id]) {
													$color[$row->po_pembelian_batch_id] = $color_idx[$i%5];
													?>
													<th class='column-data' data-index='<?=$row->po_pembelian_batch_id;?>' style="background:<?=$color[$row->po_pembelian_batch_id];?>;">
														<div>
															<a target="_blank" href="<?=base_url().is_setting_link('transaction/po_pembelian_detail_warna_batch')?>?id=<?=$row->po_pembelian_id?>&batch_id=<?=$row->po_pembelian_batch_id;?>"><?=$row->batch;?></a> <br/>
															<small><?=is_reverse_date($row->tanggal);?></small><br/>
														</div>
														<a style='' href="<?=base_url().is_setting_link('report/po_pembelian_report_detail')?>?id=<?=$row->po_pembelian_id?>&batch_id=<?=$row->po_pembelian_batch_id;?>" target="_blank" class='btn btn-xs blue' id="btn-ship-<?=$row->po_pembelian_batch_id;?>">Ship <i class='fa fa-truck'></i></a><br/>
														
														<p>
														<span <?=($row->status_show == 1 ? 'hidden' : '');?> class='btn-show-column' data-index="<?=$row->po_pembelian_batch_id?>" id="hide-<?=$row->po_pembelian_batch_id?>" data-batch="<?=$row->po_pembelian_batch_id?>" style='cursor:pointer; font-size:0.7em;padding:1px; background:transparent; position:absolute; top:0px; left:0px; height:100%; padding-top:50px; width:100%; text-align:center; vertical-align:middle; font-size:0.3em'><i class='fa fa-arrows-h'></i></span>
														<span <?=($row->status_show == 0 ? 'hidden' : '');?> class='btn-hide-column' data-index="<?=$row->po_pembelian_batch_id?>" id="show-<?=$row->po_pembelian_batch_id?>" data-batch="<?=$row->po_pembelian_batch_id?>" style='cursor:pointer; font-size:0.7em;padding:3px 8px; background:#ffba4a'>hide</span>
														</p>	
													</th>
												<?$i++; }?>
											<?$idx_s++;}?>
										</tr>
									</thead>
									<?$idx = 0;
									$total_stok = 0;
									$total_po = 0;
									$total_input_ppo=0;
									$g_total_qty = 0;
									$total_roll = 0;
									$total_qty_now = 0;
									$g_bm_first_warna = array();
									$g_jual_last_warna = array();
									$g_lt_each_warna = array();
									$g_stok_each_warna = array();
									$g_nama_each_warna = array();
									$g_total_qty_po_warna = 0;
									$g_total_sisa_warna = 0;
									$g_total_trx_warna = 0;
									$g_stok_all_warna = 0;
									$g_total_bm_warna = 0;
									$g_total_jual_warna = 0;
									$g_total_lt_warna = 0;
									?>
									<?/**
									==========================================bagian awal table===============================
									**/?>
									<tbody class='data-warna'>
										<?foreach ($data_set as $row) {
											$batch_id = explode(',', $row->batch_id);
											$qty_beli = explode(',', $row->qty_beli);
											$batch_set = array_combine($batch_id, $qty_beli);
												
											$qty_sisa = explode(',', $row->qty_sisa_data);
											$qty_sisa_data = array_combine($batch_id, $qty_sisa);
											$qty_beli = explode(',', $row->qty_beli);
											$qty_beli_data = array_combine($batch_id, $qty_beli);
											
											$locked_by = explode(',', $row->locked_by);
											$locked_data = array_combine($batch_id, $locked_by);

											$qty_po_data = explode(',', $row->qty_po_data);
											$qty_po_data = array_combine($batch_id, $qty_po_data);

											$harga_po = explode(',', $row->harga_po);
											$harga_po = array_combine($batch_id, $harga_po);

											$OCKH = explode(',', $row->OCKH);
											$OCKH = array_combine($batch_id, $OCKH);

											$info = "";

											$qts = 0;
											if (isset($stok_brg[$row->warna_id])) {
												$qts += $stok_brg[$row->warna_id];
											}
											// $total_stok += $row->qty_stok;
											$total_stok += $qts;
											$total_roll += $row->jumlah_roll_stok;
											$total_po += $row->qty_sisa;
											$qty_now = 0;
											$idx_s = $count_show;
											$total_bm[$row->warna_id] = 0;
											$total_jual[$row->warna_id] = 0;
											$total_trx[$row->warna_id] = 0;
											$total_lt_warna[$row->warna_id] = 0;
											$terjual_all[$row->warna_id] = 0;
											$terjual_show[$row->warna_id] = 0;
											$trx_show[$row->warna_id] = 0;
											$total_bm_show[$row->warna_id] = 0;
											$sisa_belum_datang_total = 0;
											$total_po_qty = 0;
											$jarak_total_all=0;
											$total_input_ppo += ($ppo_lock_id != '' ? $row->qty_ppo : $row->qty_current);
											$bm_first_warna[$row->warna_id] = array();
											$jual_last_warna[$row->warna_id] = array();
												?>
												<tr class="<?=(($idx+1) % 5 == 0 ? 'garis-bawah' : '' );?>">
													<td>
														<span class='nama-warna'><?=$row->nama_warna?></span> <?=(is_posisi_id() == 1 ? $row->warna_id : '');?>
														<?if ($ppo_lock_id == '') {?>
															<button class='btn btn-xs red remove-warna' data-warna="<?=$row->warna_id;?>"><i class='fa fa-trash'></i></button>
														<?}?>
													</td>
													<td class='text-center'><b class='qty-stock'><?=number_format($qts,'0',',','.'); $qty_now = $qts;?></b>
														<b class='roll-data' hidden>/ <?=$row->jumlah_roll_stok;?></b>
													</td>
													<!-- <td><?//=$row->jumlah_roll_stok; //print_r($batch_set); print_r($qty_sisa_data);?> </td>-->
													<?if (isset($stok_bt[$row->warna_id])) {
														foreach ($stok_bt[$row->warna_id] as $key => $value) {
															$break = explode('=??=', $value);

															foreach ($break as $keys => $values) {
																$data_break = explode('??', $values);
																$stok_now = $data_break[0];
																$tanggal_awal = $key;
																if (isset($jual[$row->warna_id])) {
																	foreach ($jual[$row->warna_id] as $key2 => $value2) {
																		$count_jual[$row->warna_id][$data_break[1]] = (isset($count_jual[$row->warna_id][$data_break[1]]) ? $count_jual[$row->warna_id][$data_break[1]] + 1 : 1 );
																		if (!isset($jual_now[$row->warna_id][$value2->penjualan_id])) {
																			$qty_j = $value2->qty;
																		}else{
																			$qty_j = $jual_now[$row->warna_id][$value2->penjualan_id];
																		}
																		if ($stok_now - $qty_j < 0) {
																			$jual_now[$row->warna_id][$value2->penjualan_id] = $qty_j - $stok_now; 
																			$terjual[$row->warna_id][$data_break[1]] = (isset($terjual[$row->warna_id][$data_break[1]]) ? $terjual[$row->warna_id][$data_break[1]] + $stok_now : $stok_now  );
																			$last_tgl[$row->warna_id][$data_break[1]] = $value2->tanggal;
																			$lead_time[$row->warna_id][$data_break[1]] = 0;
																			$stok_tgl[$row->warna_id][$data_break[1]][$key] = 0;
																			$stok_tgl_po[$data_break[1]][$row->warna_id][$key] = 0;
																			$terjual_all[$row->warna_id] += $stok_now;
																			$last_jual[$row->warna_id][$data_break[1]][$key] = $value2->tanggal;
																			$last_jual_po[$row->warna_id][$data_break[1]] = $value2->tanggal;
																			$last_jual_warna[$data_break[1]][$row->warna_id] = $value2->tanggal;
																			break;
																		}else{
																			$stok_now -= $qty_j;
																			$terjual_all[$row->warna_id] += $qty_j;
																			$terjual[$row->warna_id][$data_break[1]] = (isset($terjual[$row->warna_id][$data_break[1]]) ? $terjual[$row->warna_id][$data_break[1]] + $qty_j  : $qty_j ).'<br/>';
																			unset($jual[$row->warna_id][$key2]);
																			$stok_tgl[$row->warna_id][$data_break[1]][$key] = $stok_now;
																			$stok_tgl_po[$data_break[1]][$row->warna_id][$key] = $stok_now;
																			$last_jual[$row->warna_id][$data_break[1]][$key] = $value2->tanggal;
																			$last_jual_po[$row->warna_id][$data_break[1]] = $value2->tanggal;
																			$last_jual_warna[$data_break[1]][$row->warna_id] = $value2->tanggal;
																		}
																		$tanggal_akhir = $value2->tanggal;
																	}
																}
																if (!isset($stok_tgl[$row->warna_id][$data_break[1]][$key])) {
																	$stok_tgl[$row->warna_id][$data_break[1]][$key] = $stok_now;
																	$stok_tgl_po[$data_break[1]][$row->warna_id][$key] = $stok_now;
																	$last_jual[$row->warna_id][$data_break[1]][$key] = '';
																}
															}
														}									
														$stok_all[$row->warna_id] = $bm_all[$row->warna_id] - $terjual_all[$row->warna_id];
													}?>
													<?$i=0;foreach ($batch_for_pre_po as $row2) {
														$terjual[$row->warna_id][$row2->po_pembelian_batch_id] = (isset($terjual[$row->warna_id][$row2->po_pembelian_batch_id]) ? $terjual[$row->warna_id][$row2->po_pembelian_batch_id] : 0);
														// $bm_first[$row->warna_id][$row2->po_pembelian_batch_id] = '';
														$bm_last[$row->warna_id][$row2->po_pembelian_batch_id] = '';
														$last[$row->warna_id][$row2->po_pembelian_batch_id]='';
														$count_jual_by_customer[$row->warna_id][$row2->po_pembelian_batch_id]=0;
														$count_jual_by_non_customer[$row->warna_id][$row2->po_pembelian_batch_id]=0;
														unset($cust_idx);
														$total_lt[$row->warna_id][$row2->po_pembelian_batch_id] = 0;
														$total_lt_po[$row->warna_id][$row2->po_pembelian_batch_id] = 0;
														//=============================================================================================
														$jarak_jual = array();
														$jarak_total[$row2->po_pembelian_batch_id]=0;
														?>
														
														<?/**
														==========================================bagian akhir baris rekap per PO===============================
														**/?>
														<?if ($status_include[$row2->po_pembelian_batch_id]) {
															$is_locked = (isset($locked_data[$row2->po_pembelian_batch_id]) && $locked_data[$row2->po_pembelian_batch_id] != 0 ? true : false);
															$bm_full = (isset($stok_bm[$row->warna_id][$row2->po_pembelian_batch_id]) ? $stok_bm[$row->warna_id][$row2->po_pembelian_batch_id] : 0);
															$stok_bm_show = (isset($stok_bm[$row->warna_id][$row2->po_pembelian_batch_id]) ? $stok_bm[$row->warna_id][$row2->po_pembelian_batch_id] :  0);
															$terjual_now = (float)$terjual[$row->warna_id][$row2->po_pembelian_batch_id];
															//================================================================================
															$sisa_belum_datang = (isset($qty_po_data[$row2->po_pembelian_batch_id]) ? $qty_po_data[$row2->po_pembelian_batch_id] : 0) - $stok_bm_show;
															$sisa_belum_datang = (!$is_locked ? $sisa_belum_datang : 0);
															$sisa_belum_datang = ($sisa_belum_datang < 0 ? 0 : $sisa_belum_datang);
															$qty_now += (!$is_locked ? ($sisa_belum_datang >= 0 ? $sisa_belum_datang : 0 ) : 0);
															//=============================================================================================
															$batch_sisa_total[$row2->po_pembelian_batch_id] += (!$is_locked ? $sisa_belum_datang : 0);
															$batch_bm_total[$row2->po_pembelian_batch_id] += $bm_full;
															$batch_jual_total[$row2->po_pembelian_batch_id] += $terjual_now;
															
															//=======================================================================================================
															$sisa_belum_datang_total += $sisa_belum_datang;
															?>
															<td data-index='<?=$row2->po_pembelian_batch_id;?>' style="position:relative;<?=($idx_s < 0 ? 'display:none' : '')?>; background:<?=$color[$row2->po_pembelian_batch_id];?>; <?//=($is_locked ? 'border : 2px solid #ffcfcf;' : '0');?>">
																<div>
																	<?if (isset($qty_po_data[$row2->po_pembelian_batch_id])) {
																		$full = $qty_po_data[$row2->po_pembelian_batch_id];
																		$batch_po_total[$row2->po_pembelian_batch_id] += $full;
																		$total_po_qty += $full;
																		
																		// =========================================================================
																		$bm_persen = $bm_full/$full*100;
																		$terjual_persen = $terjual_now/($bm_full != 0 ? $bm_full : 1)*100;
																		$jual_persen_bar = $terjual_now/$full*100;
																		// =========================================================================
																		$terjual_show[$row->warna_id] += $terjual_now;
																		$total_bm_show[$row->warna_id] += $bm_full;
																		$count_jual_po = (isset($count_jual[$row->warna_id][$row2->po_pembelian_batch_id]) ? $count_jual[$row->warna_id][$row2->po_pembelian_batch_id] : 0);
																		if (isset($stok_tgl[$row->warna_id][$row2->po_pembelian_batch_id])) {
																			foreach ($stok_tgl[$row->warna_id][$row2->po_pembelian_batch_id] as $key => $value) {
																				if ($terjual_now != 0) {
																					$tgl_1 = date_create($key);
																					if (isset($last_jual[$row->warna_id][$row2->po_pembelian_batch_id][$key]) && $last_jual[$row->warna_id][$row2->po_pembelian_batch_id][$key] != '') {
																						$tgl_akhir_jual = date_create($last_jual[$row->warna_id][$row2->po_pembelian_batch_id][$key]);
																						$jj = date_diff($tgl_1,$tgl_akhir_jual)->format('%a');
																						// $jarak_total[$row2->po_pembelian_batch_id] += $jj;
																						// $jarak_total_all += $jj;
																						array_push($jarak_jual, $jj);
																					}
																				}
																				$stok_per_po[$row->warna_id][$row2->po_pembelian_batch_id] += $value;
																				$stok_po_all[$row2->po_pembelian_batch_id] += $value;
																			}
																			if (isset($last_jual_po[$row->warna_id][$row2->po_pembelian_batch_id]) && $last_jual_po[$row->warna_id][$row2->po_pembelian_batch_id] != '') {
																				$tgl_1 = date_create($bm_first[$row->warna_id][$row2->po_pembelian_batch_id]);
																				$tgl_2 = date_create($last_jual_po[$row->warna_id][$row2->po_pembelian_batch_id]);
																				$jt = date_diff($tgl_1,$tgl_2)->format('%a')+1;
																				$jarak_total[$row2->po_pembelian_batch_id] += $jt;
																				$jarak_total_all += $jt;
																				array_push($bm_first_warna[$row->warna_id], $bm_first[$row->warna_id][$row2->po_pembelian_batch_id]);
																				array_push($jual_last_warna[$row->warna_id], $last_jual_po[$row->warna_id][$row2->po_pembelian_batch_id]) ;
																			}
																		}?>
																		<div class='text-center'>
																			SISA : <span class='sisa-belum-datang' <?=($is_locked ? 'hidden' : '')?> ><?=(!$is_locked ? ($sisa_belum_datang < 0 ? 0 : number_format($sisa_belum_datang,'0',',','.') ) : 0);?></span>
																			<i class='fa fa-lock' style="display:<?=(!$is_locked ? 'none' : 'inline');?>" ></i>
																		</div>
																		<div class='bar-progress' style='background:#e5e4e2; border-bottom:1px dashed #333'><?=number_format($full,'0',',','.')?></div>
																		<div class='bar-progress' style='background:linear-gradient(90deg, #ffbf7a <?=$bm_persen;?>%, transparent 0%); border-bottom:1px dashed #333'><?=number_format($bm_full,'0',',','.')?></div>
																		<div class='bar-progress' style='background:linear-gradient(90deg, gold <?=$jual_persen_bar;?>%, transparent 0%); border-bottom:1px dashed #333'><?=number_format((float)$terjual_now,'0',',','.')?> / <?=round($terjual_persen,2);?>%</div>
																		<hr style='padding:0px; margin:2px'/> 
																		<span class='bm-qty' hidden><?=$bm_full;?></span>
																		<span class='terjual-qty' hidden><?=$terjual_now;?></span>
																		<?if ($stok_bm_show - $terjual_now != 0 && isset($stok_tgl[$row->warna_id][$row2->po_pembelian_batch_id])) {
																			foreach ($stok_tgl[$row->warna_id][$row2->po_pembelian_batch_id] as $key => $value) {
																				$tgl_1 = date_create($key);
																				$tgl_2 = date_create($tanggal_get);
																				$intvl = date_diff($tgl_1,$tgl_2)->format('%a')+1;
																				if ($value != 0) {
																					$persen = $value/$stok_all[$row->warna_id]*100;
																					$persen_po = $value/($stok_per_po[$row->warna_id][$row2->po_pembelian_batch_id] != 0 ? $stok_per_po[$row->warna_id][$row2->po_pembelian_batch_id] : 1) * 100;
																					//======================================================================================
																					$lt = $intvl*$persen/100;
																					$lt_po = $intvl*$persen_po/100;
																					//======================================================================================																					
																					$total_lt[$row->warna_id][$row2->po_pembelian_batch_id] += $lt;
																					$total_lt_po[$row->warna_id][$row2->po_pembelian_batch_id] += $lt_po;
																				}
																			}
																			$total_lt_warna[$row->warna_id] += $total_lt[$row->warna_id ][$row2->po_pembelian_batch_id];																		}
																			$t_rx = (isset($count_jual[$row->warna_id][$row2->po_pembelian_batch_id]) ? $count_jual[$row->warna_id][$row2->po_pembelian_batch_id] : 0);
																			$trx_show[$row->warna_id] += $t_rx;
																			$batch_masa_total[$row2->po_pembelian_batch_id] += $jarak_total[$row2->po_pembelian_batch_id];
																			$batch_trx_total[$row2->po_pembelian_batch_id] += $t_rx;
																			$batch_lt_total[$row2->po_pembelian_batch_id] += round($total_lt[$row->warna_id][$row2->po_pembelian_batch_id],2);
																		?>
																		<table>
																			<tr>
																				<!-- <td>TERJUAL</td><td> : </td><td><?=round($terjual_persen,2);?>% </td> -->
																			</tr>
																			<tr>
																				<td>TRX</td><td> : </td><td><?=$t_rx?></td>
																			</tr>
																			<tr>
																				<td>DALAM</td><td> : </td><td><?=$jarak_total[$row2->po_pembelian_batch_id];?> D</td>
																			</tr>
																			<tr>
																				<td>L. TIME</td><td> : </td><td><span class='lead-time'><?=round($total_lt_po[$row->warna_id][$row2->po_pembelian_batch_id],2)?></span> </td>
																			</tr>
																		</table>
																		<span class='trx-jual' hidden><?=$t_rx?></span>
																	<?}?>
																</div>
																<div style="top:0; left:0;<?=($is_locked ? 'height:100%; width:100%; position:absolute; background:rgba(0,0,0,0.1)' : 'width:0px; height:0px')?>"></div>
															</td>
														<?}?>	
													<?$idx_s++; $i++;}?>
													<!-- <a data-toggle="popover" class='btn btn-xs default' data-trigger='click' title="PO Gantung" data-html="true" data-content="<?=$info;?>"><i class='fa fa-info'></i></a> -->
													<?//$qty_now=$row->qty_stok + $row->qty_sisa;?>
													<td class=' rekap-warna'>
														<?if ($total_po_qty != 0) {?>
															<?$bm_persen_all = $total_bm_show[$row->warna_id]/$total_po_qty*100;
															$jual_persen_bar = $terjual_show[$row->warna_id]/$total_po_qty*100;
															$terjual_persen_all = $terjual_show[$row->warna_id]/($total_bm_show[$row->warna_id] != 0 ? $total_bm_show[$row->warna_id] : 1)*100;
															//=====================================================================
																$g_total_sisa_warna += $sisa_belum_datang_total;
																$g_total_qty_po_warna +=  $total_po_qty;
																$g_total_trx_warna += $trx_show[$row->warna_id];
																$sisa_now = $total_bm_show[$row->warna_id] - $terjual_show[$row->warna_id];
																$g_stok_all_warna += $sisa_now;
																$g_total_bm_warna += $total_bm_show[$row->warna_id];
																$g_total_jual_warna += $terjual_show[$row->warna_id];
																array_push($g_nama_each_warna, $row->nama_warna);
																array_push($g_stok_each_warna, $sisa_now);
																array_push($g_lt_each_warna, round($total_lt_warna[$row->warna_id],2) );
															?>
															<div class='text-center'>
																SISA : <?=number_format($sisa_belum_datang_total,'0',',','.');?>
															</div>
															<div class='bar-progress' style='background:#e5e4e2; border-bottom:1px dashed #333'><?=number_format($total_po_qty,'0',',','.')?></div>
															<div class='bar-progress' style='background:linear-gradient(90deg, #ffbf7a <?=$bm_persen_all;?>%, transparent 0%); border-bottom:1px dashed #333'><?=number_format($total_bm_show[$row->warna_id],'0',',','.')?></div>
															<div class='bar-progress' style='background:linear-gradient(90deg, gold <?=$jual_persen_bar;?>%, transparent 0%); border-bottom:1px dashed #333'><?=number_format($terjual_show[$row->warna_id],'0',',','.');?> / <?=round($terjual_persen_all,2);?>%</div>
															
															<table>
																<tr>
																	<td>TRX</td><td> : </td><td><b><?=$trx_show[$row->warna_id];?></b> </td>
																</tr>
																<tr>
																	<td>DALAM</td><td> : </td>
																	<td>
																		<b>
																		<?if (count($bm_first_warna[$row->warna_id]) > 0 && count($jual_last_warna[$row->warna_id]) > 0) {
																			array_push($g_bm_first_warna, min($bm_first_warna[$row->warna_id]));
																			array_push($g_jual_last_warna, max($jual_last_warna[$row->warna_id]));
																			$tgl_1 = date_create(min($bm_first_warna[$row->warna_id]));
																			$tgl_2 = date_create(max($jual_last_warna[$row->warna_id]));
																			$intvl = date_diff($tgl_1,$tgl_2)->format('%a')+1;
																			echo $intvl;
																		}else{echo 0;}?>
																		</b>
																	</td>
																</tr>
																<tr>
																	<td>L. TIME</td><td> : </td><td><b><?=round($total_lt_warna[$row->warna_id],2);?></b> </td>
																</tr>
															</table>
														<?}?>
													</td>
													<td class='text-center' style='border-right:0px; font-size:1.1em'>
														<b>+ <?=number_format($sisa_belum_datang_total,'0',',','.');?></b><br/>
														<?$total_qty_now += $qty_now;?>
														= <span class='qty-now' hidden><?=round($qty_now,2);?></span>
														<span class='qty-now-show'><?=number_format($qty_now,'0',',','.');?></span>
													</td>
													<td hidden>
														<span class='lead-time-now'><?=round($total_lt_warna[$row->warna_id],2);?></span>
													</td>
													<td hidden>
														<span class='terjual-show-data' hidden><?=$terjual_show[$row->warna_id];?></span>
														<span class='bm-show-data'><?=$total_bm_show[$row->warna_id];?></span>
														<span class='terjual_show'><?=number_format($terjual_show[$row->warna_id],'0',',','.');?></span><br/>
														<?$terjual_persen_all = $terjual_show[$row->warna_id]/($total_bm_show[$row->warna_id] != 0 ? $total_bm_show[$row->warna_id] : 1)*100?>
														<span class='terjual_persen'><?=round($terjual_persen_all,2);?></span> %<br/>
														<span class='trx_count'><?=$trx_show[$row->warna_id];?></span> TRX
													</td>
													<td class='text-center'>
														<span class='warna_id' hidden><?=$row->warna_id;?></span>
														<input <?=$readonly_status;?> class="ppo-input <?=($ppo_lock_id == '' && $status_ppo == 1 ? 'amount_number' : '');?> text-center" value="<?=number_format(($ppo_lock_id != '' ? $row->qty_ppo : $row->qty_current ),'0',',','.')?>" style='width:60px; font-size:1.1em; border:none; border-bottom:1px solid #171717; padding:5px'>
														
														<div style='padding:0px 10pxl width:100%; background:rgba(100,100,100,0.3);' class='saving-info' hidden>saving...</div>
														<div style='padding:0px 10pxl width:100%; background:rgba(0,255,0,0.3);' class='updated-info' hidden>updated</div>
													</td>
													<td class='text-center'>
														<?
														$subtotal = $qty_now + ($ppo_lock_id != '' ? $row->qty_ppo : $row->qty_current); 
														$g_total_qty += $subtotal;
														?>
														<span class="total-akhir" style='font-size:1.2em'><?=number_format($subtotal,'0',',','.')?></span>
													</td>
												</tr>
										<?$idx++;} ?>
										<?/**
										==========================================bagian akhir baris rekap per PO===============================
										**/?>
										<?
										$g_total_sisa_po = 0;
										$g_total_qty_po = 0;
										$g_total_bm_po = 0;
										$g_total_jual_po = 0;
										$g_total_stok_po = 0;
										$g_stok_po = array();
										$g_total_trx_po = 0;
										$g_total_lt_po = 0;
										$g_first_po = array();
										$g_last_po = array();
										$g_lt_po = array();
										?>
										<tr style=';text-align:center'>
											<td>TOTAL</td>
											<td><?=number_format((float)$total_stok,'0',',','.');?><span class='roll-data' hidden>/<?=$total_roll;?></span></td>
											<?$idx_s = $count_show;
											$i=0; 
											foreach ($batch_for_pre_po as $val) {
												if ($status_include[$val->po_pembelian_batch_id]) {
													$total_lt_all_per_po = 0;
													if (isset($stok_tgl_po[$val->po_pembelian_batch_id]) && is_posisi_id() == 1 ) {
														foreach ($stok_tgl_po[$val->po_pembelian_batch_id] as $key => $value) {
															foreach ($value as $key2 => $value2) {
																if ($value2 != 0) {
																	$persen_temp = round($value2/$stok_po_all[$val->po_pembelian_batch_id],4) * 100;
																	$tgl_1 = date_create($key2);
																	$tgl_2 = date_create($tanggal_get);
																	$diff = date_diff($tgl_1,$tgl_2)->format('%a')+1;
																	$lt_temp = $diff*$persen_temp/100;
																	$total_lt_all_per_po += $lt_temp;
																}
															}
															// $total_lt_all_per_po
														}
													}
													?>
													<td class='rekap-po' data-index="<?=$val->po_pembelian_batch_id;?>" style="<?=($idx_s < 0 ? 'display:none' : '');?>">
														<?//=(is_posisi_id() == 1 ? $stok_po_all[$val->po_pembelian_batch_id] : '');?>
														<div>
															<?
															$po_batch_total = $batch_po_total[$val->po_pembelian_batch_id];
															$bm_batch_all = $batch_bm_total[$val->po_pembelian_batch_id];
															$bm_batch_persen = $bm_batch_all/$po_batch_total*100;
															$jual_batch_persen_bar = $batch_jual_total[$val->po_pembelian_batch_id]/$po_batch_total*100;
															$terjual_batch_persen_all = $batch_jual_total[$val->po_pembelian_batch_id]/($bm_batch_all != 0 ? $bm_batch_all : 1)*100;
															//==========================================buat rekap gabungan=================================
															$g_total_sisa_po += $batch_sisa_total[$val->po_pembelian_batch_id];
															$g_total_qty_po += $po_batch_total;
															$g_total_bm_po += $bm_batch_all;
															$g_total_jual_po += $batch_jual_total[$val->po_pembelian_batch_id];
															$g_total_stok_po += $bm_batch_all - $batch_jual_total[$val->po_pembelian_batch_id];
															$g_total_trx_po += $batch_trx_total[$val->po_pembelian_batch_id];
															array_push($g_stok_po, ($bm_batch_all - $batch_jual_total[$val->po_pembelian_batch_id]));
															array_push($g_lt_po, $total_lt_all_per_po); 
															array_push($g_first_po, (isset($bm_first_po[$val->po_pembelian_batch_id]) ? min($bm_first_po[$val->po_pembelian_batch_id]) : null ) );
															array_push($g_last_po, (isset($last_jual_warna[$val->po_pembelian_batch_id]) ? max($last_jual_warna[$val->po_pembelian_batch_id]) : null) );
															//===========================================================================
															?>
															<div class='text-center'>
																SISA : <?=number_format($batch_sisa_total[$val->po_pembelian_batch_id],'0',',','.');?>
															</div>
															<div class='bar-progress' style='background:#e5e4e2; border-bottom:1px dashed #333'><?=number_format($po_batch_total,'0',',','.')?></div>
															<div class='bar-progress' style='background:linear-gradient(90deg, #ffbf7a <?=$bm_batch_all;?>%, transparent 0%); border-bottom:1px dashed #333'><?=number_format($bm_batch_all,'0',',','.')?></div>
															<div class='bar-progress-jual' style='background:linear-gradient(90deg, gold <?=$jual_batch_persen_bar;?>%, transparent 0%); border-bottom:1px dashed #333; font-size:0.9em'><?=number_format($batch_jual_total[$val->po_pembelian_batch_id],'0',',','.');?> / <br/> <?=round($terjual_batch_persen_all,2);?>%</div>
															
															<table>
																<tr>
																	<td>TRX</td><td> : </td><td><b><?=$batch_trx_total[$val->po_pembelian_batch_id];?></b> </td>
																</tr>
																<tr>
																	<td>DALAM</td><td> : </td>
																	<td>																		
																		<?if (isset($bm_first_po[$val->po_pembelian_batch_id]) && isset($last_jual_warna[$val->po_pembelian_batch_id]) ) {
																			$tgl_1 = date_create(min($bm_first_po[$val->po_pembelian_batch_id]));
																			$tgl_2 = date_create(max($last_jual_warna[$val->po_pembelian_batch_id]));
																			$diff = date_diff($tgl_1, $tgl_2)->format('%a'); ?>
																			<b><?=$diff+1;?></b>
																		<?}else{echo 0;}?>
																	</td>
																</tr>
																<tr <?=(is_posisi_id() == 1 ? '' : 'hidden')?>>
																	<td>L. TIME</td><td> : </td><td><b><?=round($total_lt_all_per_po,2);?></b> </td>
																</tr>
															</table>
															
														</div>
													</td>
												<?}?>
											<?$idx_s++;$i++;}?>

										<?/**
										==========================================AKHIR CROSS REFERENCE===============================
										**/?>
											<td class='hidden-print' style='padding:0px;'>
												<div class=' rekap-warna' style="border-left:3px solid red; border-bottom:3px solid red">
													<?
														$g_total_qty_po_warna = ($g_total_qty_po_warna != 0 ? $g_total_qty_po_warna : 1);
														$g_total_bm_warna = ($g_total_bm_warna != 0 ? $g_total_bm_warna : 1);
														$bm_batch_all = $g_total_bm_warna/$g_total_qty_po_warna * 100;
														$jual_batch_persen_bar = $g_total_jual_warna/$g_total_qty_po_warna * 100;
														$jual_batch_persen_text = $g_total_jual_warna/$g_total_bm_warna;
													?>
													<div class='text-center'>
														SISA : <?=number_format($g_total_sisa_warna,'0',',','.');?>
													</div>
													<div class='bar-progress' style='background:#e5e4e2; border-bottom:1px dashed #333'><?=number_format($g_total_qty_po,'0',',','.')?></div>
													<div class='bar-progress' style='background:linear-gradient(90deg, #ffbf7a <?=$bm_batch_all;?>%, transparent 0%); border-bottom:1px dashed #333'><?=number_format($g_total_bm_po,'0',',','.')?></div>
													<div class='bar-progress' style='background:linear-gradient(90deg, gold <?=$jual_batch_persen_bar;?>%, transparent 0%); border-bottom:1px dashed #333; font-size:0.9em'><?=number_format($g_total_jual_po,'0',',','.');?> / <?=round($jual_batch_persen_text,2);?>%</div>
													
													<table>
														<tr>
															<td>DALAM</td><td> : </td>
															<td>
																<?/*=is_reverse_date(min($g_bm_first_warna))?> s/d <?=is_reverse_date(max($g_jual_last_warna))*/?>
																<b>
																<?
																if (count($g_bm_first_warna) > 0 && count($g_jual_last_warna) > 0) {
																	$tgl_1 = date_create(min($g_bm_first_warna));
																	$tgl_2 = date_create(max($g_jual_last_warna));
																	$diff = date_diff($tgl_1, $tgl_2)->format('%a') + 1; 
																	echo $diff;
																}
																?>
																</b>
															</td>
														</tr>
														<tr>
															<td>TRX</td><td> : </td><td><b><?=$g_total_trx_warna;?></b> </td>
														</tr>
														<tr>
															<?
															$g_stok_all_warna = ($g_stok_all_warna !=0 ? $g_stok_all_warna : 1);
															foreach ($g_stok_each_warna as $key => $value) {
																$lt_each_warna = $value/$g_stok_all_warna * $g_lt_each_warna[$key];
																$g_total_lt_warna += round($lt_each_warna,2);
															}?>
															<td>L. TIME</td><td> : </td><td><b><?=round($g_total_lt_warna,2);?></b> </td>
														</tr>
													</table>
												</div>
												<div class=' rekap-po'>
													<?
													//=========================================================================================
														$g_total_qty_po = ($g_total_qty_po != 0 ? $g_total_qty_po : 1);
														$g_total_bm_po = ($g_total_bm_po != 0 ? $g_total_bm_po : 1);
														$bm_batch_all = $g_total_bm_po/$g_total_qty_po * 100;
														$jual_batch_persen_bar = $g_total_jual_po/$g_total_qty_po * 100;
														$jual_batch_persen_text = $g_total_jual_po/$g_total_bm_po;
													?>
													<div class='text-center'>
														SISA : <?=number_format($g_total_sisa_po,'0',',','.');?>
													</div>
													<div class='bar-progress' style='background:#e5e4e2; border-bottom:1px dashed #333'><?=number_format($g_total_qty_po,'0',',','.')?></div>
													<div class='bar-progress' style='background:linear-gradient(90deg, #ffbf7a <?=$bm_batch_all;?>%, transparent 0%); border-bottom:1px dashed #333'><?=number_format($g_total_bm_po,'0',',','.')?></div>
													<div class='bar-progress' style='background:linear-gradient(90deg, gold <?=$jual_batch_persen_bar;?>%, transparent 0%); border-bottom:1px dashed #333; font-size:0.9em'><?=number_format($g_total_jual_po,'0',',','.');?> / <?=round($jual_batch_persen_text,2);?>%</div>
													<table>
														<tr>
															<td>DALAM</td><td> : </td>
															<td>
																<?/*=is_reverse_date(min($g_first_po))?> s/d <?=is_reverse_date(max($g_last_po))*/?>
																<b>
																<?
																if (count($g_first_po) > 0 && count($g_last_po) > 0) {
																	$tgl_1 = date_create(min($g_first_po));
																	$tgl_2 = date_create(max($g_last_po));
																	$diff = date_diff($tgl_1, $tgl_2)->format('%a') + 1; 
																	echo $diff;
																}
																?>
																</b>
															</td>
														</tr>
														<tr>
															<td>TRX</td><td> : </td><td><b><?=$g_total_trx_po;?></b> </td>
														</tr>
														<tr>
															<td>
																<?foreach ($g_stok_po as $key => $value) {
																	if (is_posisi_id() ==1) {
																		// echo $g_total_stok_po .'*'. $g_lt_po[$key].'<br/>';
																	}
																	$lt_each_po = ($g_total_stok_po * $g_lt_po[$key]==0 ? 0 : $value/$g_total_stok_po * $g_lt_po[$key]);
																	$g_total_lt_po += round($lt_each_po,2);
																}?>
																L. TIME</td><td> : </td><td><b><?=round($g_total_lt_po,2);?></b> </td>
														</tr>
													</table>
												</div>
											</td>
											<td style='border-right:0px;' id='total-stok'><?=number_format($total_qty_now,'0',',','.');?></td>
											<td class='hidden-print' id='total-input-ppo'><?=number_format($total_input_ppo,'0',',','.');?></td>
											<td class='hidden-print' id='g-total-qty'><?=number_format($g_total_qty,'0',',','.');?></td>
										</tr>
									</tbody>

								</table>

								<?if (is_posisi_id() == 1) {
									$g_total_lt_po = 0;?>
									<table class='table'>
										<?foreach ($g_stok_each_warna as $key => $value) {
											$lt_each_warna = $value/$g_stok_all_warna * $g_lt_each_warna[$key];
											$g_total_lt_warna += round($lt_each_warna,2);?>
											<tr>
												<td><?=$g_nama_each_warna[$key]?></td>
												<td><?=$value?> / <?=$g_stok_all_warna?></td>
												<td>x</td>
												<td><?=$g_lt_each_warna[$key]?></td>
												<td>=</td>
												<td><?=round($lt_each_warna,2)?></td>
											</tr>
										<?}?>
									</table>
								<?}?>
							</div>
						</div>
						<hr/>
						<form hidden action="<?=base_url();?>inventory/ppo_lock_insert" method="POST" id="form-ppo-lock">
							<input name="tanggal" value="<?=$tanggal_get;?>">
							<input name="barang_id" value="<?=$barang_id;?>">
							<input name="po_pembelian_batch_id_aktif" value="<?=implode(',', $batch_id_aktif_list);?>">
						</form>
						<div class='text-right'>
							<?if ($ppo_lock_id != '') {
								if ($status_ppo == 1) {?>
									<a href="<?=base_url()?>inventory/lock_ppo_lock?ppo_lock_id=<?=$ppo_lock_id?>&status=0" data-toggle='modal' class='btn btn-lg red'>LOCK</a>
								<?}else{?>
									<a href="#portlet-config-pin" data-toggle='modal' class='btn btn-lg yellow-gold'>OPEN</a>
									<a href="#portlet-config-export" data-toggle='modal' class='btn btn-lg blue'>Export ke PO</a>
									<a href="<?=base_url('inventory/ppo_lock_download_excel')?>?ppo_lock_id=<?=$ppo_lock_id;?>" class='btn btn-lg green hidden-print' id="download-ppo"><i class='fa fa-download'></i> Download</a>
								<?}?>

							<?}?>
							<!-- <button class='btn btn-lg blue hidden-print' onclick="window.print()"><i class='fa fa-print'></i> PRINT</button> -->
							<button style="<?=($ppo_lock_id != '' ? 'display:none' : '');?>" class='btn btn-lg red hidden-print' id="lock-ppo"><i class='fa fa-lock'></i> LOCK</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>			
</div>

<div id="slideFontController" style="position:fixed; height:80px; bottom:50px; right:0px; background:#ccc">
	<div class="btnFontControllerContent"  style="float:right; width:150px; padding:5px 8px;">
		<span>Font Size</span><br/>
		<button class='smallerFont btn btn-default'><i class='fa fa-minus'></i></button>
		<span id="fontSize">100</span>%
		<button class='biggerFont btn btn-default'><i class='fa fa-plus'></i></button>
	</div>
	<div class="btnFont btnFontHide" style="cursor:pointer;float:right;width:30px; text-align:center; color:#000; height:100%; background:#bbb">
		<b><i style='font-size:2em; position:relative;top:30px;' class='fa fa-angle-right'></i></b>
	</div>
</div>


<script src="<?php echo base_url('assets/global/plugins/datatables/media/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets_noondev/js/table-advanced.js'); ?>"></script>
<script src="<?php echo base_url('assets/global/plugins/bootbox/bootbox.min.js'); ?>" type="text/javascript"></script>

<script src="<?php echo base_url('assets/global/plugins/select2/select2.min.js'); ?>" type="text/javascript" ></script>

<script type="text/javascript" src="<?php echo base_url('assets/global/plugins/bootstrap-select/bootstrap-select.min.js'); ?>"></script>


<script>

let po_list = {};
<?foreach ($po_pembelian_untuk_ppo as $row) {?>
	po_list["<?=$row->po_pembelian_detail_id?>"] = "<?=$row->po_number?>";
<?}?>
jQuery(document).ready(function() {

	let ppo_lock_id = "<?=$ppo_lock_id?>";

	let fontSize = 1;
	
	$(".biggerFont").click(function(){
		fontSize = parseFloat(fontSize) + 0.05;
		$("#general_table tr td, #general_table tr th").css("font-size",fontSize+"em");
		$("#fontSize").text(fontSize.toFixed(2)*100);
	});
	$(".smallerFont").click(function(){
		fontSize = parseFloat(fontSize) - 0.05;
		$("#general_table tr td, #general_table tr th").css("font-size",fontSize+"em");
		$("#fontSize").text(fontSize.toFixed(2)*100);
	});

	$(".remove-warna").click(function(){
		let ini = $(this);
		let nama = $(this).closest("tr").find(".nama-warna").html();
		let barang_id = "<?=$barang_id?>";
		let warna_id = ini.attr('data-warna')
		bootbox.confirm(`Yakin menghapus warna <b>${nama}</b> dari daftar ? `, function(respond){
			if (respond) {
				window.location.replace(baseurl+"inventory/ppo_remove_warna?barang_id="+barang_id+"&warna_id="+warna_id);
			};
		});
	});

	$(document).on("click",".btnFontHide", function(){
		$('#slideFontController').animate({
			right:"-150"
		},500);
		$(".btnFont").removeClass("btnFontHide");
		$(".btnFont").addClass("btnFontShow");
		$(".btnFont").find('i').removeClass("fa-angle-right");
		$(".btnFont").find('i').addClass("fa-angle-left");
	})

	$(document).on("click",".btnFontShow", function(){
		$('#slideFontController').animate({
			right:"0"
		},500);
		$(".btnFont").addClass("btnFontHide");
		$(".btnFont").removeClass("btnFontShow");
		$(".btnFont").find('i').addClass("fa-angle-right");
		$(".btnFont").find('i').removeClass("fa-angle-left");
	})

	$('[data-toggle="popover"]').popover();
	let table_posisi = $("#general_table thead").position();
	let atas = table_posisi.top;
	let kiri = table_posisi.left;
	$("#table-float").css({'top':atas+'px','left':kiri+'px'});
	let idx = 0;
	$("#general_table thead tr").each(function() {
		$(this).find('th').each(function(index){
			console.log(idx,index, $(this).css('width'));
		});
		idx++;
	})

	$('#barang_id_select,#warna_id_select').select2();
	$(".nama_barang_tampil").html($("#barang_id_select :selected").text());

	$(".roll-header").click(function() {
		$(this).toggleClass('roll-hide');
		$("#general_table .roll-data").toggle('slow');
	});

	$("#btn-go").click(function(){
		if ($("#barang_id_select").val() != '' && $("#tanggal").val() != '' ) {
			$("#form-barang").submit();
			$(".nama_barang_tampil").html("<i>loading...</i>")
		}else{
			alert('Tanggal dan Barang tidak boleh kosong');
		}

    	// var id = $(this).val();
    	// $(".data-warna").hide();
    	// $("#barang-"+id).show();
    	// $(".nama_barang_tampil").html($("#barang_id_select :selected").text());
	});

	$("#btn-go-reset").click(function() {
		$("#ppo-lock-input").val('');

		if ($("#barang_id_select").val() != '' && $("#tanggal").val() != '' ) {
			$("#form-barang").submit();
			$(".nama_barang_tampil").html("<i>loading...</i>")
		}else{
			alert('Tanggal dan Barang tidak boleh kosong');
		}

	})

	$("#count-po").change(function(){
		if ($("#barang_id_select").val() != '') {
			$(".nama_barang_tampil").html("<i>loading...</i>");
		};
	});

	$(".btn-show-harga").click(function(){
		$('.badge-harga').css('visibility', $('.badge-harga').css('visibility') == 'hidden' ? 'visible' : 'hidden');
	});

	$(".btn-show-po").click(function(){
		$('.badge-po').css('visibility', $('.badge-po').css('visibility') == 'hidden' ? 'visible' : 'hidden');
	});

	$(".btn-show-ockh").click(function(){
		$('.badge-ockh').css('visibility', $('.badge-ockh').css('visibility') == 'hidden' ? 'visible' : 'hidden');
	});

	$(".btn-show-info").click(function(){
		$('.badge-ockh, .badge-po, .badge-harga').css('visibility', $('.badge-ockh, .badge-po, .badge-harga').css('visibility') == 'hidden' ? 'visible' : 'hidden');
	});


	$(".btn-warna-save").click(function(){
		$("#form-add-warna").submit();
	});
	//=============================column control=============================

	$(".btn-include").click(function(){
		<?if ($ppo_lock_id == '') {?>
			let status_include = $(this).attr('data-include');
			let data = {};
			data['batch_id'] = $(this).attr('data-batch');
			data['barang_id'] = "<?=$barang_id;?>";
			data['column'] = 'status_include';
			data['value'] = (status_include == 1 ? 0 : 1);
			let url = "inventory/ppo_change_table_setting";
			ajax_data_sync(url,data).done(function(data_respond  /*,textStatus, jqXHR*/ ){
				if (data_respond == 'OK') {
					$("#include-click-loading").show();
					window.location.reload();
				};
	   		});
		<?}else{?>alert('Pre Purchased Order sudah lock');<?}?>
	});

//==========================================================================================
	$("#general_table").on('change', '.ppo-input', function(){
		let ini = $(this).closest('tr');
		ini.find('.saving-info').show();
		let qty_now = parseInt(ini.find('.qty-now').html());
		let qty = parseInt(reset_number_format($(this).val()));
		let data = {};
		data['barang_id'] = "<?=$barang_id;?>";
		data['warna_id'] = ini.find('.warna_id').html();
		data['qty'] = qty;
		data['ppo_lock_id'] = ppo_lock_id;
		let url = "inventory/ppo_update_qty_current";
		// alert(ppo_lock_id);
		ajax_data_sync(url,data).done(function(data_respond  /*,textStatus, jqXHR*/ ){
			if (data_respond == 'OK') {
				ini.find('.total-akhir').html(change_number_format(qty_now+qty));
				ini.find('.saving-info').hide();
				ini.find('.updated-info').show();
				let stok_now = reset_number_format($("#total-stok").html());
				let ppo_input = 0;
				$('.ppo-input').each(function() {
					ppo_input += reset_number_format($(this).val());
				});
				$('#total-input-ppo').html(change_number_format(ppo_input));
				$('#g-total-qty').html(change_number_format(stok_now+ppo_input));
			   	setTimeout(function() { 
					ini.find('.updated-info').fadeOut('slow');
			   	}, 2000);
			}else{
				alert("Error");
			};
   		});
	});

	$(".btn-hide-column").click(function(){
		let idx = $(this).attr('data-index');
		$("#hide-"+idx).toggle();
		$("#show-"+idx).toggle();
		$("#btn-ship-"+idx).toggle();
		
		$("#general_table").find(`[data-index='${idx}'] div`).hide();
		$("#general_table").find(`[data-index='${idx}']`).removeClass('column-data');

	});

	$(".btn-show-column").click(function(){
		let idx = $(this).attr('data-index');
		$("#hide-"+idx).toggle();
		$("#show-"+idx).toggle();
		$("#btn-ship-"+idx).toggle();
		
		$("#general_table").find(`[data-index='${idx}'] div`).show();
		$("#general_table").find(`[data-index='${idx}']`).addClass('column-data');

	});

//==========================================================================================
	
	$("#lock-ppo").click(function(){
		let total_input_ppo = reset_number_format($("#total-input-ppo").html());
		if (parseInt(total_input_ppo) > 0) {
			bootbox.confirm("Yakin mengunci daftar <b>Pre Purchased Order</b> ini ? ", function(respond){
				if(respond){
					$("#form-ppo-lock").submit();
				}
			});
		}else{
			alert("Tidak ada kuantiti PPO yang diisi ");
		}
	});

//=======================================export===================================================

	$('.po-radio-export').click(function(){
		$(".info-po-container").hide();
		let po_detail_id = $(this).val();
		let data_po = $(this).attr('data-po');
		// alert(data_po);
		if (data_po == 2) {$(".btn-export").prop('disabled',true)}
			else{$(".btn-export").prop('disabled',false)};
		$("#info-po-"+po_detail_id).show();
	});

	$(".btn-export").click(function(){
		let po_detail_id = $(".po-radio-export:checked").val();
		let batch = $("#info-po-"+po_detail_id).find('.next-batch').html();

		if (typeof po_detail_id !== 'undefined') {
			bootbox.confirm(`Pre Purchased Order akan generate PO Pembelian <b>Batch BARU</b> <b style='font-size:1.2em'>${po_list[po_detail_id]}-${batch}</b>`, function(respond){
				if (respond) {
					btn_disabled_load($(this));
					$("#form-export").submit();
					$("#portlet-config-export").modal('toggle');
				};
			});
		}else{
			alert("Mohon Pilih PO");
		};
		// alert(po_detail_id);
	});

	//===========================================================================

	$('.btn-request-open').click(function(){
    	if(cek_pin($('#form-request-open'))){
    		btn_disabled_load($('.btn-request-open'));
			$('#form-request-open').submit();
    	}
    });

});

function cek_pin(form){
	// alert('test');
	var data = {};
	data['pin'] = form.find('.pin_user').val();
	var url = 'transaction/cek_pin';
	// ajax_data_sync(url,data).done(function(data_respond  /*,textStatus, jqXHR*/ ){
	// 	if (data_respond == "OK") {
	// 		return true;
	// 	}else{
	// 		alert("PIN Invalid");
	// 	}
	// });

	var result = ajax_data(url,data);
	if (result == 'OK') {
		return true;
	};
}

var elem = document.getElementById("body-table");
function recount_table(){

}

// var elem = document.documentElement;
function openFullscreen() {
  if (elem.requestFullscreen) {
    elem.requestFullscreen();
  } else if (elem.mozRequestFullScreen) { /* Firefox */
    elem.mozRequestFullScreen();
  } else if (elem.webkitRequestFullscreen) { /* Chrome, Safari & Opera */
    elem.webkitRequestFullscreen();
  } else if (elem.msRequestFullscreen) { /* IE/Edge */
    elem.msRequestFullscreen();
  }

  $("#general_table thead th").css("top","0px");

  $("#body-table").focus();
}

</script>
