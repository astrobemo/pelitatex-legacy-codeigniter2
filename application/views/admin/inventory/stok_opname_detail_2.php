<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css'); ?>"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css'); ?>"/>
<?=link_tag('assets/global/plugins/bootstrap-datepicker/css/datepicker3.css'); ?>

<style type="text/css">
#general_table tr td, #general_table tr th {
	text-align: center;
	vertical-align: middle;
}

.nav-tabs li.active a{
	font-weight: bold !important;
}

.table-head tr th, .table-foot tr th{
	padding: 5px;
	border:1px solid #ddd ;
	font-size: 1.2em;
	text-align: center;
}

.table-body tr td{
	/*padding: 5px;*/
	height: 31px;
	border:1px solid #ddd ;
	font-size: 1.1em;
}

.table-body input{
	border: none;
	width: 100%;
	height: 100%;
	/*padding-left: 5px;*/
	text-align: center;
}

#rekap-table tr td, #rekap-table tr th{
	padding: 5px 10px;
	font-size: 1.3em;
}

.datetimepicker .switch{
	content:'';
}

</style>

<div class="page-content">
	<div class='container'>

		<div class="modal fade" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body">
						<form action="<?=base_url('inventory/stok_opname_insert')?>" class="form-horizontal" id="form_add_data" method="post">
							<h3 class='block'> Data SO Baru</h3>
							
							
			                <?if (is_posisi_id() == 1) {?>
		                	<div class="form-group">
			                    <label class="control-label col-md-3">Tanggal<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<div class="input-group date form_datetime input-medium">
										<input type="text" size="16" readonly class="form-control">
										<span class="input-group-btn">
										<button class="btn default date-set" type="button"><i class="fa fa-calendar"></i></button>
										</span>
									</div>
			                    </div>
			                </div>
			                <?}?>

			                
			                
						</form>
					</div>

					<div class="modal-footer">
						<button type="button" class="btn blue btn-active btn-trigger btn-save" title='Save'>Save</button>
						<button type="button" class="btn btn-active default" data-dismiss="modal">Close</button>
					</div>
				</div>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
		</div>

		<div class="modal fade" id="portlet-config-lock" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body">
						<form action="#" class="form-horizontal" id="form_add_data" method="post">
							<h3 class='block'> Data LOCK SO</h3>
							
							
			                <div class="form-group">
			                    <label class="control-label col-md-3">Tanggal Laporan<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
									<input id="tanggalReport" disabled class="form-control">
			                    </div>
			                </div>

			                <div class="form-group">
			                    <label class="control-label col-md-3">Petugas SO<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
									<input id="keterangan" class="form-control">
			                    </div>
			                </div>
			                
			                
						</form>
					</div>

					<div class="modal-footer">
						<button type='button' class='btn btn-lg green hidden-print' onclick="lockSOBanyak()"><i class='fa fa-lock'></i> SAVE </button>
						<button type="button" class="btn btn-active default" data-dismiss="modal">Close</button>
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
							<span class="caption-subject theme-font bold uppercase"><?=$breadcrumb_small;?></span>
						</div>
						<div class="actions">
							
							<!-- <a target="_blank" href="<?=base_url().is_setting_link('inventory/stok_opname_overview');?>?id=<?=$stok_opname_id;?>" class="btn green btn-md">
							<i class="fa fa-search"></i> Daftar Barang Sudah SO </a> -->
							<?/*if (is_posisi_id()==1) {?>
								<a target="_blank" href="#portlet-config" class="btn btn-default btn-md">
								<i class="fa fa-search"></i> Search SO </a>
							<?}*/?>
						</div>
					</div>
					<div class="portlet-body">
						<div class='row'>
							<div class='col-xs-12'>
								<table width='100%'>
									<tr>
										<td width='40%'>
											<table width='100%' id='tbl-data-barang'>
												<tr>
													<td>Tanggal<span class="required"></td>
													<td> : </td>
													<td><input readonly id='tanggal' name='tanggal' class='form-control date-picker' onchange="gantiTanggal()" value="<?=date('d/m/Y');?>" ></td>
													<td></td>
												</tr>
												<tr>
													<td>Barang</td>
													<td> : </td>
													<td>
														<select id='barang_id_select' class='form-control' name='barang_id_filter' >
															<option value=''>Pilih</option>
															<?foreach ($this->barang_list_aktif as $row) {?>
																<option value='<?=$row->id?>'><?=$row->nama_jual;?></option>
															<?}?>
														</select>
													</td>
													<td></td>
												</tr>
												<tr>
													<td>Warna</td>
													<td> : </td>
													<td>
														<select id='warna_id_select' class='form-control' name='warna_id_filter' >
															<option value=''>Pilih</option>
															<?foreach ($this->warna_list_aktif as $row) {?>
																<option value='<?=$row->id?>'><?=$row->warna_jual;?></option>
															<?}?>
														</select>
													</td>
													<td hidden><button class='btn btn-md green form-control search-opname'><i class='fa fa-search'></i></button></td>
												</tr>
												<?//if (is_posisi_id() == 1) {?>
													<tr>
														<td></td>
														<td> : </td>
														<td>
															<label id='mode-so-otomatis-label'>
																<input type="checkbox" id='mode-so-otomatis' > Jam sesuai saya click</label>
															<div id='auto-jam'>
																<p>Mode ini mengatur program <b>otomatis menggunakan jam sesuai user click save</b>, <br> Untuk menyimpan sesuai dengan jam yang diinginkan, matikan mode ini</p>
															</div>
															<div id='single-notes'></div>
														</td>
													</tr>
												<?//}?>
											</table>
										</td>
										<td>
											<?if (is_posisi_id()==1) {?>
												Barang ID : <span id='barang-id-selected'></span><BR/> 
												Warna ID : <span id='warna-id-selected'></span>
											<?}?>
											<h1 style='float:right;font-weight:bold' id='nama-barang'></h1>
										</td>
									</tr>
								</table>
							</div>
							<div class='col-xs-12'>
								<hr id="garis-batas"/>
								<div class="tabbable tabbable-custom">
									<ul class="nav nav-tabs">
										<?$idx = 0;foreach ($this->gudang_list_aktif as $row) {
											if ($gudang_id_so == '') {?>
												<li class="<?=($idx == 0 ? 'active' : '');?>">
													<a href="#tab_1_<?=$idx+1;?>" data-toggle="tab" onclick="setGudang('<?=$idx+1;?>')" >
														<span hidden class='gudang-lock-icon' id='gudang-<?=$row->id?>-lock-icon'><i class='fa fa-lock'></i></span>
														<?=$row->nama;?> <span class='event-gudang<?=$row->id;?>' hidden style='color:red'>*</span>- 
														<input readonly id='gudangTime-<?=$row->id;?>' onchange="updateCreatedAt('<?=$row->id;?>')" data-id='' data-format="hh:mm" class='data-time text-center timepicker' style='width:60px;border:none; background:#ddd' placeholder="jam">
													</a>
												</li>
											<?}else if($gudang_id_so == $row->id){?>
												<li class="active">
													<a href="#tab_1_<?=$idx+1;?>" data-toggle="tab">
													<?=$row->nama;?> <span class='event-gudang<?=$row->id;?>' hidden style='color:red'>*</span> </a>
												</li>
											<?}?>
										<?$idx++;}?>
									</ul>

									<div class="tab-content col-md-6" id="qty-detail-container">
										<?$idx = 0;foreach ($this->gudang_list_aktif as $row) {
											if ($gudang_id_so == '') {?>
												<div class="tab-pane <?=($idx == 0 ? 'active' : '');?>" id="tab_1_<?=$idx+1;?>">
													<div class="tbl-container" id='gudang<?=$row->id?>'>
														<table class='table-head' >
															<thead>
																<th style='width:50px' onclick="sortTable(0)" >No</th>
																<th style='width:100px' onclick="sortTable(1)" >Qty</th>
																<th style='width:80px' onclick="sortTable(2)" >Roll</th>
																<th style='width:100px' onclick="sortTable(3)" >Total</th>
																<th style='width:100px; border:none; vertical-align:top' >
																	<?$hidden = (is_posisi_id()!=1 ? 'hidden' : '');?>
																	<form class='form-stok-detail' id='form-gudang-<?=$row->id;?>' style='position:absolute; margin-left:20px' method='post' hidden>
																		<input name='stok_opname_id' value="<?=$stok_opname_id?>" <?=$hidden?> > <br/>
																		<input name='gudang_id' value="<?=$row->id?>" <?=$hidden?> ><br/>
																		<input name='tanggal' value="<?=date('Y-m-d')?>" <?=$hidden?> ><br/>
																		<input name='barang_id' <?=$hidden?> ><br/>
																		<input name='warna_id' <?=$hidden?> ><br/>
																		<input name='rekap_qty' <?=$hidden?> ><br/>
																		<input name='created_at' <?=$hidden?> ><br/>
																		<small class='event-gudang<?=$row->id;?>' hidden></small>
																	</form>
																	<?if (is_posisi_id() == 1) {?>
																		<a href="#portlet-config" data-toggle='modal' style='display:none' type='button' class='btn btn-lg blue event-gudang<?=$row->id;?>-new' >SAVE</a>
																	<?}?>
																	<button style='display:none' type='button' onclick="saveDetail('<?=$row->id;?>')" class='btn btn-lg green btn-save-detail event-gudang<?=$row->id;?>' id='btn-save-gudang<?=$row->id;?>'>SAVE</button>
																	<span class='loading' hidden>load...</span>
																	<span class='done' hidden>Updated <i class='fa fa-check' style='color:blue' ></i></span>
																</th>
																<!-- <th style='width:100px'></th> -->
															</thead>
														</table>
														<div style="height:235px; overflow:auto; width:450px" >
															<table class='table-body' id="table-body">
																<tbody>
																	<?for ($i=1; $i < 51 ; $i++) { ?>
																		<tr>
																			<td style='width:50px' class='text-center'><?=$i?></td>
																			<td style='width:100px' class='text-center'><input disabled name='qty' tabindex="<?=($i*2)-1?>" class='qty'></td>
																			<td style='width:80px' class='text-center'><input disabled name='roll' tabindex="<?=($i*2)?>" class='jumlah-roll'></td>
																			<td style='width:100px; padding-left:5px;' class='subtotal text-center'></td>
																			<!-- <td style='width:100px'></td> -->
																		</tr>
																	<?}?>
																</tbody>
															</table>
														</div>
														<table class='table-foot'>
															<tfoot>
																<tr>
																	<th style='width:150px' class='text-center'>Total</th>
																	<th style='width:80px' class='jumlah_roll_total'></th>
																	<th style='width:100px' class='yard_total'></th>
																</tr>
																<!-- <th style='width:100px'></th> -->
															</tfoot>
														</table>


													</div>
												</div>	
											<?}elseif ($gudang_id_so == $row->id) {?>
												<div class="tab-pane active" id="tab_1_<?=$idx+1;?>">
													<div class="tbl-container" id='gudang<?=$row->id?>'>
														<table class='table-head' >
															<thead>
																<th style='width:50px' onclick="sortTable(0)" >No</th>
																<th style='width:100px' onclick="sortTable(1)" >Qty</th>
																<th style='width:80px' onclick="sortTable(2)" >Roll</th>
																<th style='width:100px' onclick="sortTable(3)" >Total</th>
																<th style='width:100px; border:none; vertical-align:top' >
																	<?$hidden = (is_posisi_id()!=1 ? 'hidden' : '');?>
																	<form class='form-stok-detail' style='position:absolute; margin-left:20px' method='post' hidden >
																		<input name='stok_opname_id' value="<?=$stok_opname_id?>" <?=$hidden?> > <br/>
																		<input name='gudang_id' value="<?=$gudang_id_so;?>" <?=$hidden?> ><br/>
																		<input name='barang_id' value="<?=$barang_id_so;?>" <?=$hidden?> ><br/>
																		<input name='warna_id' value="<?=$warna_id_so;?>" <?=$hidden?> ><br/>
																		<input name='rekap_qty' <?=$hidden?> ><br/>
																		<small class='event-gudang<?=$row->id;?>' hidden></small>
																	</form>
																	<button style='display:none' type='button' class='btn btn-lg green btn-save-detail event-gudang<?=$row->id;?>' id='btn-save-gudang<?=$row->id;?>'>SAVE</button>
																	<span class='loading' hidden>load...</span>
																	<span class='done' hidden>Updated <i class='fa fa-check' style='color:blue' ></i></span>
																</th>
																<!-- <th style='width:100px'></th> -->
															</thead>
														</table>
														<div style="height:235px; overflow:auto; width:450px" >
															<table class='table-body' id="table-body">
																<tbody>
																	<?for ($i=1; $i < 51 ; $i++) { ?>
																		<tr>
																			<td style='width:50px' class='text-center'><?=$i?></td>
																			<td style='width:100px' class='text-center'><input disabled name='qty' tabindex="<?=($i*2)-1?>" class='qty'></td>
																			<td style='width:80px' class='text-center'><input disabled name='roll' tabindex="<?=($i*2)?>" class='jumlah-roll'></td>
																			<td style='width:100px; padding-left:5px;' class='subtotal text-center'></td>
																			<!-- <td style='width:100px'></td> -->
																		</tr>
																	<?}?>
																</tbody>
															</table>
														</div>
														<table class='table-foot'>
															<tfoot>
																<tr>
																	<th style='width:150px' class='text-center'>Total</th>
																	<th style='width:80px' class='jumlah_roll_total'></th>
																	<th style='width:100px' class='yard_total'></th>
																</tr>
																<!-- <th style='width:100px'></th> -->
															</tfoot>
														</table>


													</div>
												</div>
											<?}?>
										<?$idx++;}?>
									</div>
									<div class='col-md-6'>
										<table id='rekap-table'>
											<thead>
												<tr>
													<th></th>
													<th></th>
													<th>QTY</th>
													<th>ROLL</th>
												</tr>
											</thead>
											<tbody>
												<?$idx = 0;foreach ($this->gudang_list_aktif as $row) {
													if ($gudang_id_so == '') {?>
														<tr>
															<td><?=$row->nama;?> </td>
															<td>:</td>
															<td><span class='rekap-yard-gudang<?=$row->id;?>'></span> </td>
															<td><span class='rekap-roll-gudang<?=$row->id;?>'></span> </td>
														</tr>
													<?}elseif ($gudang_id_so == $row->id) {?>
														<tr>
															<td><?=$row->nama;?> </td>
															<td>:</td>
															<td><span class='rekap-yard-gudang<?=$row->id;?>'></span> </td>
															<td><span class='rekap-roll-gudang<?=$row->id;?>'></span> </td>
														</tr>
													<?}?>
												<?$idx++;}?>
											</tbody>
										</table>
									</div>
								</div>
								<hr/>
								<div style='text-align:right'>
									<?/*if ($status_aktif == 0) {?>
										<button type='button' class='btn btn-lg red hidden-print btn-close' id='btn-lock-so' onclick="lockSO(<?=$id;?>)"><i class='fa fa-lock'></i> LOCK </button>
									<?}else{?>
										<h2><i class='fa fa-locked'></i> LOCKED</h2>
									<?}*/?>
								</div>
							</div>
						</div>
					</div>
				</div>

				<?//if (is_posisi_id() == 1) {?>
					<div class="portlet light">
						<div class="portlet-body">
							<div class='row'>
								<div class='col-xs-12 text-right'>
									<table class='table table-bordered' id='table-so-list'>
										<thead>
											<tr>
												<th>Tanggal</th>
												<th>Barang</th>
												<th>Gudang</th>
												<th>Stok</th>
												<th>Stok Opname</th>
												<th style='width:45%;'>Perincian</th>
												<th></th>
											</tr>
										</thead>
										<tbody></tbody>
										<tfoot>
										</tfoot>
									</table>

									<a id="btn-lock-so" href="#portlet-config-lock" data-toggle='modal' class='btn btn-lg red hidden-print'><i class='fa fa-lock'></i> LOCK </a>

								</div>
							</div>
						</div>
					</div>
				<?//}?>
			</div>
		</div>
	</div>			
</div>
<div id='overlay-div' hidden style="left:0px; top:0px; position:fixed; height:100%; width:100%; background:rgba(0,0,0,0.5)">
	<p style="position:relative;color:#fff;top:40%;left:40%">Loading....</p>
</div>

<script src="<?php echo base_url('assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js'); ?>" type="text/javascript"></script>

<script src="<?php echo base_url('assets/global/plugins/datatables/media/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets_noondev/js/table-advanced.js'); ?>"></script>
<script src="<?php echo base_url('assets/global/plugins/bootbox/bootbox.min.js'); ?>" type="text/javascript"></script>

<script>
var stok_opname_id = "<?=$stok_opname_id?>";

var gudang_id = [];
var count_gudang = [];
	<?foreach ($this->gudang_list_aktif as $row) {?>
		gudang_id.push(<?=$row->id;?>);
		count_gudang[<?=$row->id;?>] = 0;
		// window["count_gudang<?=$row->id?>"] = 0;
	<?}?>
	// console.log(count_gudang);

var barang_id_now = '';
var tanggal_now = "<?=date('d/m/Y');?>";
var warna_id_now = '';
var barang_id_select = '';		
var warna_id_select = '';
var tanggal_select = '';
var isSaved = true;
var gudang_id_aktif = 1;
var stok_opname_id_belum_lock = [];
var stok_perubahan = [];
var roll_perubahan = [];
var waktu_perubahan = [];

var data_lock = [];

<?if ($barang_id_so != '') {?>
	var barang_id_now = "<?=$barang_id_so?>";
	var warna_id_now = "<?=$warna_id_so?>";
<?};?>
var hari = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];


let barangSKUList = [];
let barangSKUFiltered = [];
let isBarangSKULoaded = false;

async function getBarangSKUList(){
	let data = {};
	var url = 'master/get_barang_sku_all';
	// update_table(ini);
	
	const response = await fetch(baseurl+url);
	const data_respond = await response.json();
	barangSKUList = data_respond;
}


(async function() {
	// Initialize the popover
	$('[data-toggle="popover"]').popover({
		html: true,
		trigger: 'hover',
		container: 'body'
	});

	await getBarangSKUList();
	isBarangSKULoaded = true;
})();

jQuery(document).ready(function() {
	//Metronic.init(); // init metronic core components
	//Layout.init(); // init current layout
	// TableAdvanced.init();


	$('#barang_id_select').select2();

	var dialog = bootbox.dialog({
		message: '<div class="text-center"><i class="fa fa-spinner fa-spinner"></i> Loading SKU...</div>',
		closeButton: false,
		className: 'modal-loading'
	});

	var skuInterval = setInterval(function() {
		if (isBarangSKULoaded) {
			console.log('Barang SKU loaded');
			dialog.modal('hide');
			clearInterval(skuInterval);
			// You can add additional logic here if needed
		}
	}, 500);

	
	$(".timepicker").datetimepicker({
		format: 'hh:ii',
        language:  'en',
        weekStart: 1,
        todayBtn:  1,
		autoclose: 1,
		todayHighlight: 1,
		startView: 1,
		minView: 0,
		maxView: 1,
		forceParse: 0
    }).unbind('focus').dblclick(function(argument) {
    	$(this).datetimepicker('show');
    });

	var map = {13: false};
    $(".table-body").on('keydown','input',function(e) {
      if (e.keyCode in map) {
        map[e.keyCode] = true;
        if (map[13]) {
          	var tabindex = $(this).attr('tabindex');
          	tabindex++;
          	// console.log(tabindex);
	        $('.table-body').find('input[tabindex='+tabindex+']').focus();
          // document.forms[idx].elements[2].focus();
        }
      }            
    }).keyup(function(e) {
        if (e.keyCode in map) {
            map[e.keyCode] = false;
        }
    });

	updateLaporanTable();


	/*Untuk table : 
	- sort
	- automatic show table
	- automatic add row
	- automatic delete row
	*/

	//==============================change qty-table==============================================

	$(".table-body").on('change','.qty, .jumlah-roll',function(){
		var tbl_body = $(this).closest('.table-body');
		// alert(tbl_body.html());
    	data_result = table_qty_update(tbl_body).split('=*=');
    	let total = parseFloat(data_result[0]);
    	let total_roll = parseFloat(data_result[1]);
    	let rekap = data_result[2];
    	$(this).closest('.tbl-container').find('[name=rekap_qty]').val(rekap);
    	
    	if (total == 0) {
			$('.btn-save').attr('disabled',true);
		}else{
			$('.btn-save').attr('disabled',false);
		}

		isSaved = false;

		var id = $(this).closest('.tbl-container').attr('id');
		var foot = $(this).closest('.tbl-container').find('.table-foot');
		$(this).closest('.tbl-container').find('.done').hide();
		
    	foot.find('.yard_total').html(parseFloat(total).toFixed(2));
    	foot.find('.jumlah_roll_total').html(total_roll);
    	var gudang_edited = id.toString().replace('gudang','');

    	$(".event-"+id).show();
    	count_gudang[gudang_edited]++;
    	// window['count_'+id] += 1;
    	// alert(window['count_'+id]);
    });

    //==============================================================================

    $('.table-head th').click(function(){
	    /*var table = $('.table-body');
	    var rows = table.find('tr:gt(0)').toArray().sort(comparer($(this).index()))
	    this.asc = !this.asc
	    if (!this.asc){rows = rows.reverse()}
	    for (var i = 0; i < rows.length; i++){table.append(rows[i])}*/
	});

	$("#barang_id_select, #warna_id_select, #tanggal" ).change(function(){
		if($('#barang_id_select').val() != '' && $("#warna_id_select").val() != ''  && $("#tanggal").val() != '' ){
			var totalan = 0;
			$.each(count_gudang, function(i,v){
				if (typeof v !== 'undefined') {
					// console.log(totalan+'+'+ parseInt(v) );
					totalan += parseInt(v);
				};
			});
			// totalan = count_gudang;

			// alert(count_gudang);
			if (totalan == 0) {
				// count_gudang = 0;
				searchAndDrawTable();
			}else{
				barang_id_now = $("#barang-id-selected").html();
				warna_id_now = $("#warna-id-selected").html();
				barang_id_select = $('#barang_id_select').val();		
				warna_id_select = $('#warna_id_select').val();
				tanggal_select = $('#tanggal').val();
				// console.log(barang_id_now +'!='+ barang_id_select +'||'+ warna_id_now +'!='+ warna_id_select +'||'+ tanggal_now +'!='+ tanggal_select);


				if (barang_id_now != barang_id_select || warna_id_now != warna_id_select || tanggal_now != tanggal_select) {
					bootbox.confirm("Table belum di save, yakin ubah nama barang ? ", function(respond){
						if(respond){
							count_gudang = 0;
							searchAndDrawTable();
						}else{
							$("#barang_id_select").val(barang_id_now);
							$("#warna_id_select").val(warna_id_now);
							$("#tanggal").val(tanggal_now);
							$('#barang_id_select').change();
							$('#warna_id_select').change();
						}
					});
					
				};
			}
		}else if($('#barang_id_select').val() != ''){
			const barangId = $('#barang_id_select').val();
			barangSKUFiltered = barangSKUList.filter((item) => item.barang_id_toko == barangId);
			let warnaList = '';

			$('#warna_id_select').empty();
			barangSKUFiltered.forEach((item) => {
				warnaList += `<option value="${item.warna_id_toko}">${item.nama_warna}</option>`;
			});

			$('#warna_id_select').append(warnaList);
		}

	});

	$(".search-opname").click(function(){
		var totalan = 0;
		totalan = count_gudang;
		if (totalan == 0) {
			searchAndDrawTable();
		}else{
			bootbox.confirm("Table belum di save, yakin ubah nama barang ? ", function(respond){
				if(respond){
					count_gudang = 0;
					searchAndDrawTable();
				}else{
					var barang_id_now = $("#barang-id-selected").html();
					var warna_id_now = $("#warna-id-selected").html();
					$("#barang_id_select").val(barang_id_now);
					$("#warna_id_select").val(warna_id_now);
					$('#barang_id_select').change();
					$('#warna_id_select').change();
				}
			});
		}
	});

//==========================================================================

	$("#mode-so-otomatis").change(function() {
		if($(this).is(':checked')){
			$('.data-time').prop('disabled',true);
			$('#garis-batas').css('border-color','red');
			$("#mode-so-otomatis-label").css('border','1px solid red');
		}else{
			$('.data-time').prop('disabled',false);
			$('#garis-batas').css('border-color','#ddd');
			$("#mode-so-otomatis-label").css('border','none');
		}
	})
});

function updateCreatedAt(gudang_id){
	let jam = $(`#gudangTime-${gudang_id}`).val();
	$(`#form-gudang-${gudang_id} [name='created_at']`).val(jam);
}

function saveDetail(gudang_id) {
	let submit=true;
	let tgl = new Date();
	let waktu =  tgl.getHours()+':'+tgl.getMinutes();
	// $("#created").val(`<?=date('d/m/Y');?> ${waktu}`);

	let created_at=$(`#gudangTime-${gudang_id}`).val();
	if (created_at == '' && $("#mode-so-otomatis").is(':checked') == false ) {
		$(`#gudangTime-${gudang_id}`).css('border','1px solid red');
		alert("Mohon isi jam SO");
		return false;
	}else{
		if (created_at == '') {
			created_at = waktu;
		};
		$(`#form-gudang-${gudang_id} [name='created_at']`).val(created_at);
		$(`#gudangTime-${gudang_id}`).css('border','none');

	};
	

	
	var parent = $(`#gudang${gudang_id}`);
	parent.find('.btn-save-detail').hide();
	parent.find('.btn-save-detail-blue').hide();
	parent.find('.qty, .jumlah-roll').prop('disabled',true);
	parent.find('.loading').show();
	var id = parent.attr('id');
	var form = parent.find('.form-stok-detail');
	// var data_respond = ajax_form('inventory/stok_opname_detail_insert',form);
	var gudang_id = form.find('[name=gudang_id]').val();
	var baris_gudang = '';
	var idx = 1;
	ajax_form('inventory/stok_opname_detail_insert_2',form).done(function(data_respond  ,textStatus, jqXHR ){

		count_gudang[gudang_id] = 0;
		// $.each(JSON.parse(data_respond),function(k,v){
	// 		baris_gudang += `<tr>
	// 			<td style='width:50px' class='text-center'>${idx}</td>
	// 			<td style='width:100px' class='text-center'><input name='qty' autocomplete='off' value="${parseFloat(v[i].qty)}" tabindex="${(idx*2)-1}" class='qty'></td>
	// 			<td style='width:80px' class='text-center'><input name='roll' autocomplete='off' value="${v[i].jumlah_roll}" tabindex="${idx*2}" class='jumlah-roll'></td>
	// 			<td style='width:100px; padding-left:5px;' class='subtotal text-center'>${parseFloat(v[i].qty*v[i].jumlah_roll)}</td>
	// 			<td hidden><span class='id'>${v[i].id}</span></td>
	// 		</tr>`;
	// 		idx++;
	// 	});
		
	// 	for (var i = idx; i < (idx+100); i++) {
	// 		baris_gudang += `<tr>
	// 			<td style='width:50px' class='text-center'>${i}</td>
	// 			<td style='width:100px' class='text-center'><input name='qty' autocomplete='off' tabindex="${(i*2)-1}" class='qty'></td>
	// 			<td style='width:80px' class='text-center'><input name='roll' autocomplete='off' tabindex="${i*2}" class='jumlah-roll'></td>
	// 			<td style='width:100px; padding-left:5px;' class='subtotal text-center'></td>
	// 			<td hidden></td>
	// 		</tr>`;
	// 	};
	// 	$('#gudang'+gudang_id).find('.table-body').html(baris_gudang);
	// 	tbl_body = $('#gudang'+gudang_id).find('.table-body');
 //    	data_result = table_qty_update(tbl_body).split('=*=');
 //    	let rekap = data_result[2];
	// 	$('#gudang'+gudang_id).find('[name=rekap_qty]').val(rekap);
	// 	$('#gudang'+gudang_id).find('.yard_total').html(data_result[0]);
	// 	$('#gudang'+gudang_id).find('.jumlah_roll_total').html(data_result[1]);
	// 	$('.event-gudang'+gudang_id).hide();

	// 	$('.rekap-yard-gudang'+gudang_id).html(change_number_format(data_result[0]));
	// 	$('.rekap-roll-gudang'+gudang_id).html(data_result[1]);
	// 	parent.find('.loading').hide();
	// 	parent.find('.done').show();
	// 	count_gudang=0;
	// 	isSaved = true;
	// 	// window["count_gudang"+v] = 0;
		searchAndDrawTable();
	});

	// $('#warna_id_filter').val('');

}

function setGudang(gudang_id) {
	gudang_id_aktif = gudang_id;
}

function searchAndDrawTable(){

	let tgl = new Date();
	let waktu =  tgl.getHours()+':'+tgl.getMinutes();

	$('#table-body input').attr('disabled', true);
	var data_st = {};
	var barang_id = $("#barang_id_select").val();
	var warna_id = $("#warna_id_select").val();
	var tanggal =  $('#tanggal').val();
	var jam = $(`#gudangTime-${gudang_id_aktif}`).val();
	if (jam == '') {jam = waktu;};


	if (barang_id == '' || warna_id == '') {
		return false;
	};

	var nama_barang = $("#barang_id_select [value='"+barang_id+"']").text();
	var nama_warna = $("#warna_id_select [value='"+warna_id+"']").text();
	var nama_lengkap = nama_barang+' '+nama_warna;
	$("#nama-barang").html(nama_lengkap);

	$('#barang-id-selected').html(barang_id);
	$('#warna-id-selected').html(warna_id);
	

	var url = "inventory/get_data_stok_opname_detail_by_tanggal";
	data_st['tanggal'] =  tanggal;
	data_st['barang_id'] = barang_id;
	data_st['warna_id'] = warna_id;
	var baris_gudang = [];
	var idx_gudang = [];
	$.each(gudang_id, function(k,v){
		baris_gudang[v] = '';
		idx_gudang[v] = 1;
	});
	
	var status_aktif = [];
	ajax_data_sync(url,data_st).done(function(data_respond  ,textStatus, jqXHR ){
		// console.log(data_respond);
		$(".gudang-lock-icon").hide();
		$.each(JSON.parse(data_respond),function(k,v){
			console.log('sa',status_aktif);
			
			if (k == 0) {
				// stok_opname_id = v;
				var so_id_list = [];
				var timeList = [];
				var gudang_id_icon = '';
				$.each(v, function(i2,v2){
					status_aktif[v2.gudang_id_so] = v2.status_aktif;
					if (status_aktif[v2.gudang_id_so] == 1) {
						$(`#gudang-${v2.gudang_id_so}-lock-icon`).show();
					};
					so_id_list[v2.gudang_id_so] = v2.id;
					timeList[v2.gudang_id_so] = v2.created_at.toString().substring(v2.created_at.length - 8, v2.created_at.length-3 );
				});

				<?foreach ($this->gudang_list_aktif as $row) {?>
					$(`#form-gudang-<?=$row->id?> [name='stok_opname_id']`).val(so_id_list["<?=$row->id?>"]);
					$(`#gudangTime-<?=$row->id?>`).attr('data-id',so_id_list["<?=$row->id?>"]);
					$(`#gudangTime-<?=$row->id?>`).val(timeList["<?=$row->id?>"]);
				<?}?>
			}else{
				for (var i = 0; i < v.length; i++) {
					baris_gudang[v[i].gudang_id] += `<tr>
						<td style='width:50px' class='text-center'>${idx_gudang[v[i].gudang_id]}</td>
						<td style='width:100px' class='text-center'><input ${(status_aktif[v[i].gudang_id] == 1 ? 'disabled' : '')} name='qty' autocomplete='off' value="${parseFloat(v[i].qty)}" tabindex="${(idx_gudang[v[i].gudang_id]*2)-1}" class='qty'></td>
						<td style='width:80px' class='text-center'><input ${(status_aktif[v[i].gudang_id] == 1 ? 'disabled' : '')} name='roll' autocomplete='off' value="${v[i].jumlah_roll}" tabindex="${idx_gudang[v[i].gudang_id]*2}" class='jumlah-roll'></td>
						<td style='width:100px; padding-left:5px;' class='subtotal text-center'>${parseFloat(v[i].qty*v[i].jumlah_roll)}</td>
						<td hidden><span class='id'>${v[i].id}</span></td>
					</tr>`;
					idx_gudang[v[i].gudang_id]++;
				};
				
			};
		});

		// console.log(baris_gudang);


		$.each(gudang_id, function(k,v){			
			for (var i = idx_gudang[v]; i < (idx_gudang[v]+100); i++) {
				baris_gudang[v] += `<tr>
					<td style='width:50px' class='text-center'>${i}</td>
					<td style='width:100px' class='text-center'><input ${(status_aktif[v] == 1 ? 'disabled' : '')} name='qty' autocomplete='off' tabindex="${(i*2)-1}" class='qty'></td>
					<td style='width:80px' class='text-center'><input ${(status_aktif[v] == 1 ? 'disabled' : '')} name='roll' autocomplete='off' tabindex="${i*2}" class='jumlah-roll'></td>
					<td style='width:100px; padding-left:5px;' class='subtotal text-center'></td>
					<td hidden></td>
				</tr>`;
			};

			// console.log(v);

			// $('#gudang'+v).find('.table-body').html('');
			$('#gudang'+v).find('.table-body').html(baris_gudang[v]);
			// alert($('#gudang'+v).find('.table-body').html());
			tbl_body = $('#gudang'+v).find('.table-body');
	    	data_result = table_qty_update(tbl_body).split('=*=');
	    	let rekap = data_result[2];
			$('#gudang'+v).find('[name=rekap_qty]').val(rekap);
			$('#gudang'+v).find('.yard_total').html(parseFloat(data_result[0]).toFixed(2));
			$('#gudang'+v).find('.jumlah_roll_total').html(data_result[1]);
			
			$('.rekap-yard-gudang'+v).html(change_number_format(data_result[0]));
			if(parseFloat(data_result[0]) > 0){
				let tst = data_result[0].toString().split('.');
				if(tst[1] > 0){
					$('.rekap-yard-gudang'+v).html(parseFloat(data_result[0]).toFixed(2));
				}
			}
			
			$('.rekap-roll-gudang'+v).html(data_result[1]);
			
			$(".loading").hide();
			$(".done").hide();
			$('.event-gudang'+v).hide();
			
		});

		$(".form-stok-detail [name=barang_id]").val(barang_id);
		$(".form-stok-detail [name=warna_id]").val(warna_id);
		updateLaporanTable();
	}).fail(function(){
		// alert('y');
		$('.table-body').find('.qty, .jumlah-roll').prop('disabled',false);

	});

}

function updateLaporanTable() {

	stok_opname_id_belum_lock = [];
	stok_perubahan = [];
	roll_perubahan = [];
	waktu_perubahan = [];
	var data_st = {};
	var url = "inventory/get_stok_opname_belum_lock";
	$("#overlay-div").show();
	var barang_id_stok = [];
	var roll_id_stok = [];
	data_lock = [];
	ajax_data_sync(url,data_st).done(function(data_respond  ,textStatus, jqXHR ){

		$('#table-so-list tbody').html('');
		console.log('lok',data_respond);
		$.each(JSON.parse(data_respond),function(k,v){
			if (k == 1) {
				console.log(v);
				$.each(v, function(i2,v2) {
					var new_data = {};
					var created_at = v2.created_at.split(' ');
					var tgl = created_at[0].split('/').reverse().join('-');
					stok_opname_id_belum_lock.push(v2.id);
					var stok_before = barang_id_stok['s-'+v2.barang_id_so+'-'+v2.warna_id_so+'-'+v2.gudang_id_so];
					var roll_before = roll_id_stok['s-'+v2.barang_id_so+'-'+v2.warna_id_so+'-'+v2.gudang_id_so];
					stok_perubahan.push(v2.qty - stok_before);
					roll_perubahan.push(v2.jumlah_roll - roll_before);
					waktu_perubahan.push(`${tgl} ${created_at[1]}`);
					let qty_data = v2.qty_data.split('??');
					let jumlah_roll_data = v2.jumlah_roll_data.split('??');
					// console.log(jumlah_roll_data);
					new_data['id'] = v2.id;
					new_data['barang_id'] = v2.barang_id;
					new_data['warna_id'] = v2.warna_id;
					new_data['gudang_id'] = v2.gudang_id;
					new_data['qty'] = v2.qty - stok_before;
					new_data['jumlah_roll'] = v2.jumlah_roll - roll_before;
					new_data['created_at'] = `${tgl} ${created_at[1]}`;
					data_lock.push(new_data);

					let qty_dtl = '';
					for (var i = 0; i < qty_data.length; i++) {
						qty_dtl += `<p style='display:inline-flex; width:70px; '>${jumlah_roll_data[i]} x ${parseFloat(qty_data[i])}</p>`;				
					};
					$('#table-so-list tbody').append(`<tr id='baris-${v2.id}'>
							<td class='text-left'>${hari[v2.hari]} ${v2.created_at}</td>
							<td class='text-left'>${v2.nama_barang} ${v2.nama_warna}</td>
							<td class='text-left'>${v2.nama_gudang}</td>
							<td class='text-left'>${parseFloat(stok_before)} / ${parseFloat(roll_before)} </td>
							<td class='text-left'>${parseFloat(v2.qty)} / ${v2.jumlah_roll}</td>
							<td class='text-left'>
								${qty_dtl}
							</td>
							<td class='text-center'>
								<button type='button' class='btn btn-xs green' onclick="editSO('${v2.tanggal_ori}','${v2.barang_id_so}','${v2.warna_id_so}','${v2.gudang_id_so}')"><i class='fa fa-edit'></i>
								<button type='button' class='btn btn-xs red' onclick="removeSO('${v2.id}','${v2.nama_barang} ${v2.nama_warna} [${v2.nama_gudang}] jam ${v2.created_at}')"><i class='fa fa-times'></i>
							</td>
					</tr>`);
				})
			}else{
				$.each(v, function(i2,v2){
					barang_id_stok[`s-${v2.barang_id}-${v2.warna_id}-${v2.gudang_id}`] = eval('v2.qty');
					roll_id_stok[`s-${v2.barang_id}-${v2.warna_id}-${v2.gudang_id}`] = eval('v2.jumlah_roll');
					// console.log(roll_id_stok);
					// console.log(barang_id_stok);
						// barang_id_stok[`s-${v2.barang_id}-${v2.warna_id}`] gudang_id[j] = 
				});
			}

			// alert($("#table-so-list tbody tr").length);
			if ($("#table-so-list tbody tr").length > 0) {
				$("#btn-lock-so").css('display','inline');
			}else{
				$("#btn-lock-so").css('display','none');
			};
		});

		$("#overlay-div").hide();

	}).fail(function(){
		alert('--error--');
		$("#overlay-div").hide();
	});
	
}

function removeSO(so_id, keterangan) {
	bootbox.confirm(`Yakin menghapus SO <b>${keterangan}</b> ?`,function(respond) {
		if (respond) {
			$("#overlay-div").show();
			var data = {};
			data['id'] = so_id;
			var url = 'inventory/stok_opname_remove';

			ajax_data_sync(url,data).done(function(data_respond  ,textStatus, jqXHR ){
				// console.log('lok',data_respond);
				updateLaporanTable();
				$("#overlay-div").hide();

			}).fail(function(){
				alert('--error--');
				$("#overlay-div").hide();
			});			

		};
	})
}

function table_qty_update(table){
	var total = 0; 
	var idx = 0; 
	var rekap = [];
	var total_roll = 0;
	$(table).find(".qty").each(function(){
		var ini = $(this).closest('tr');
		var qty = $(this).val();
		var roll = ini.find('.jumlah-roll').val();
		var id = ini.find('.id').html();
		if (typeof id === 'undefined') {
			id = '0';
		};
		if (qty != '' && roll == '') {
			roll = 1;
		}else if(roll == 0){
			// alert('test');
			if (qty == '') {
				qty = 0;
			};
		}else if(qty == '' && roll == ''){
			roll = 0;
			qty = 0;
		};

		if (roll == 0) {
    		var subtotal = parseFloat(qty);
    		total_roll += 0;
		}else{
    		var subtotal = parseFloat(qty*roll);
    		total_roll += parseFloat(roll);
		};

		// alert(subtotal);
		if (qty != '' && roll != '' && id != '') {
			roll = (roll == '' ? 0 : roll);
			rekap[idx] = qty+'??'+roll+'??'+id;
		}else if(id != 0){
			roll = (roll == '' ? 0 : roll);
			rekap[idx] = qty+'??'+roll+'??'+id;
			// alert(id);
		}
		idx++; 
		// alert(total_roll);
		total += subtotal;
		if (qty != '' && roll != '') {
			ini.find('.subtotal').html(qty*roll);
		};

	});

	$(table).find('.total-roll').html(total_roll);
	$(table).find('.total-all').html(total);

	rekap_str = rekap.join('--');
	// console.log(total+'=*='+total_roll+'=*='+rekap_str);

	return total+'=*='+total_roll+'=*='+rekap_str;
}

function sortTable(n) {
  var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
  table = document.getElementById("table-body");
  switching = true;
  //Set the sorting direction to ascending:
  dir = "asc"; 
  /*Make a loop that will continue until
  no switching has been done:*/
  while (switching) {
    //start by saying: no switching is done:
    switching = false;
    rows = table.rows;
    /*Loop through all table rows (except the
    first, which contains table headers):*/
    for (i = 0; i < (rows.length - 1); i++) {
      //start by saying there should be no switching:
      shouldSwitch = false;
      /*Get the two elements you want to compare,
      one from current row and one from the next:*/
  	if (n == 1) {
  		x = rows[i].querySelector('.qty');
  		y = rows[i+1].querySelector('.qty');	
  	}else if (n == 2) {
  		x = rows[i].querySelector('.jumlah-roll');
  		y = rows[i+1].querySelector('.jumlah-roll');	
  	}else{
      x = rows[i].getElementsByTagName("TD")[n];
      y = rows[i + 1].getElementsByTagName("TD")[n];
  	}
      /*check if the two rows should switch place,
      based on the direction, asc or desc:*/
      if (dir == "asc") {
      	if (n == 1 || n == 2) {
      		if (Number(x.value) > Number(y.value) && y.value != '' ) {
			  shouldSwitch = true;
			  break;
			}
      	}else{
	        if (Number(x.innerHTML) > Number(y.innerHTML) && x.innerHTML!= '' && y.innerHTML != '' ) {
			  shouldSwitch = true;
			  break;
			}
      	};
      } else if (dir == "desc") {
      	if (n == 1 || n == 2) {
      		if (Number(x.value) < Number(y.value) && y.value != '' ) {
			  shouldSwitch = true;
			  break;
			}
      	}else{
	        if (Number(x.innerHTML) < Number(y.innerHTML) && x.innerHTML!= '' && y.innerHTML != '' ) {
			  shouldSwitch = true;
			  break;
			}
      	}
      }
    }
    if (shouldSwitch) {
      /*If a switch has been marked, make the switch
      and mark that a switch has been done:*/
      rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
      switching = true;
      //Each time a switch is done, increase this count by 1:
      switchcount ++;      
    } else {
      /*If no switching has been done AND the direction is "asc",
      set the direction to "desc" and run the while loop again.*/
      if (switchcount == 0 && dir == "asc") {
        dir = "desc";
        switching = true;
      }
    }
  }
}

function ajax_form(url,form){
    var hasil = "";
    return $.ajax({
        type:"POST",
        url:baseurl+url,
        data:$(form).serialize(),
        async:true,
        success: function(data)
        {
            hasil = data;
        }
    });
    return hasil;
}

function lockSO(id){
	let data_st = {};
	data_st['id'] = id;
	const url = 'inventory/stok_opname_lock';
	let text = '';
	if (isSaved == true) {
		text = "Yakin untuk lock stok opname ini ?";
	}else{
		text = `Table belum di save, yakin lock ?`;	
	};
	bootbox.confirm(text, function(respond){
		if (respond) {
			ajax_data_sync(url,data_st).done(function(data_respond  ,textStatus, jqXHR ){
				console.log(textStatus, jqXHR);
				if (textStatus == 'success') {
					window.location.reload();
				};
			});
			
		};
	});
}

function lockSOBanyak(){
	$("#overlay-div").show();
	
	let data_st = {};
	
	data_st['keterangan'] = $("#keterangan").val();
	data_st['data'] = data_lock;
	var petugas_ket = "";
	if ($("#keterangan").val() == '') {
		petugas_ket = "Petugas SO Belum diisi, tetap save ?";
	}; 
	const url = 'inventory/stok_opname_lock_banyak';
	if (petugas_ket != '') {
		bootbox.confirm(petugas_ket, function(respond){
			if (respond) {
				// if(respond == 'ok'){
					ajax_data_sync(url,data_st).done(function(data_respond  ,textStatus, jqXHR ){
						console.log(textStatus, jqXHR);
						if (textStatus == 'success') {
							window.location.reload();
						};
					});
					$('#portlet-config-lock').modal('toggle');
				// }else{
				// 	alert("Data saving error, mohon kontak admin")
				// }
				
			};
		});
		
	}else{
		ajax_data_sync(url,data_st).done(function(data_respond  ,textStatus, jqXHR ){
			console.log(textStatus, jqXHR);
			if (textStatus == 'success') {
				window.location.reload();
			};
		});
		$('#portlet-config-lock').modal('toggle');

	};
}

function editSO(tanggal, barang_id, warna_id, gudang_id){
	$('#tanggal').val(tanggal);
	$('#barang_id_select').val(barang_id);
	$('#warna_id_select').val(warna_id);

	$("#barang_id_select").change();
	$("#warna_id_select").change();

	// document.body.scrollTop = 0; // For Safari
	// document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera

	$('html,body').animate({scrollTop:0}, 'slow');

	// $(`#tab_1_${gudang_id}`).click();
}

function gantiTanggal(){
	let tanggal = $('#tanggal').val();
	if (tanggal == '') {
		alert('Tanggal tidak boleh kosong');
		tanggal = "<?=date('d/m/Y');?>";
		$('#tanggal').val(tanggal);
	};
	tanggal = tanggal.split('/').reverse().join('-');
	$('.form-stok-detail').find('[name=tanggal]').val(tanggal);
}
</script>
