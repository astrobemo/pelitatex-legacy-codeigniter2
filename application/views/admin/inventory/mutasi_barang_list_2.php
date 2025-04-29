<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css'); ?>"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css'); ?>"/>
<?=link_tag('assets/global/plugins/bootstrap-datepicker/css/datepicker3.css'); ?>
<link href="<?=base_url('assets/global/plugins/bootstrap-modal/css/bootstrap-modal.css');?>" rel="stylesheet" type="text/css"/>

<style type="text/css">
#general_table tr th{
	vertical-align: middle;
	/*text-align: center;*/
}

#general_table tr td{
	color:#000;
}


.batal{
	background: #ccc;
}



#qty-table input, #qty-table-edit input{
	width: 60px;
	padding: 5px;
	border: 1px solid #ddd;
	border-radius:5%; 
}

#qty-table tr td, #qty-table-edit tr td{
	padding: 0 2px;
}

#qty-table-detail tr td, #qty-table-detail-edit tr td{
	border: 1px solid #ccc;
	padding: 3px;
	text-align: center;
	min-width: 50px;
	font-size: 16px;
}

#stok-info, #stok-info-edit{
	font-size: 1.5em;
	position: absolute;
	right: 50px;
	top: 30px;
}

#qty-table-detail, #qty-table-detail-edit{
	/*position: absolute;*/
	right: 50px;
	top: 120px;
}

#qty-table-detail .selected{
	background: lime;
}


.qty-detail-close{
	cursor: pointer;
	/* font-size:15px; */
	position: absolute;
	right:7px;
	top: 5px;
	color: red;
}

.detail-list{
	display: none;
	position: absolute;
	background-color: lightyellow;
	padding:10px;
	margin-left:40px
}

.detail-list ul{
	list-style-type: none;
	padding:0;
	border:1px solid #ccc;
	max-height: 200px;
	overflow-y: scroll;
}

.detail-list ul li:first-child{
	border-bottom:1px solid #ccc;
}

.detail-list li div{
	margin: 0px;
	padding:5px;
	text-align: center;
	display: inline-block;
	width: 50px;
}

</style>

<div class="page-content">
<div class='container'>

<div id="pembelian-modal" class="modal fade" style='width:100%' tabindex="-1">
</div>

<div class="modal fade bs-modal-full" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-full">
		<div class="modal-content">
			<div class="modal-body">
				<table width="100%">
					<tr>
						<td colspan='3'>
							<h3 class='block'> Mutasi Barang</h3>
						</td>
					</tr>
					<tr style='vertical-align:top'>
						<td style='border:1px solid #ddd; padding:10px; width:40%'>
							<form action="<?=base_url('inventory/mutasi_barang_insert')?>" class="form-horizontal" id="form_add_data" method="post">
								
								<div class="form-group">
				                    <label class="control-label col-md-4">Lokasi Sebelum<span class="required">
				                    * </span>
				                    </label>
				                    <div class="col-md-6">
				                    	<select class='input1 form-control' style='font-weight:bold' name="gudang_id_before">
				                    		<?foreach ($this->gudang_list_aktif as $row) { ?>
				                    			<option <?=($row->id == 1 ? 'selected' : '');?> value="<?=$row->id?>"><?=$row->nama;?></option>
				                    		<? } ?>
				                    	</select>
				                    </div>
				                </div>			                

				                <div class="form-group">
				                    <label class="control-label col-md-4">Lokasi Setelah<span class="required">
				                    * </span>
				                    </label>
				                    <div class="col-md-6">
		                    			<select style='font-weight:bold' class='form-control' name="gudang_id_after">
				                    		<?foreach ($this->gudang_list_aktif as $row) { ?>
				                    			<option <?=($row->id == 2 ? 'selected' : '');?> value="<?=$row->id?>"><?=$row->nama;?></option>
				                    		<? } ?>
				                    	</select>
				                    </div>
				                </div> 

				                <div class="form-group">
				                    <label class="control-label col-md-4">Tanggal<span class="required">
				                    * </span>
				                    </label>
				                    <div class="col-md-6">
						                <input type="text" readonly class="form-control date-picker" value="<?=date('d/m/Y');?>" name="tanggal"/>
				                    </div>
				                </div> 	

				                <div class="form-group">
				                    <label class="control-label col-md-4">Barang<span class="required">
				                    * </span>
				                    </label>
				                    <div class="col-md-6">
				                		<select class='form-control' name="barang_id" id='barang_id_select'>
				                			<option value=''>Pilih</option>
				                    		<?foreach ($this->barang_list_aktif as $row) { 
				                    			if ($row->status_aktif == 1) {?>
					                    			<option value="<?=$row->id?>"><?=$row->nama_jual;?></option>
				                    			<?}?>
				                    		<? } ?>
				                    	</select>
				                    </div>
				                </div>

				                <div class="form-group">
				                    <label class="control-label col-md-4">Warna<span class="required">
				                    * </span>
				                    </label>
				                    <div class="col-md-6">
				                		<select class='form-control' name="warna_id" id='warna_id_select'>
				                			<option value=''>Pilih</option>
				                    		<?foreach ($this->warna_list_aktif as $row) {
				                    			if ($row->status_aktif == 1) {?>
					                    			<option value="<?=$row->id?>"><?=$row->warna_jual;?></option>
				                    			<?}?>
				                    		<? } ?>
				                    	</select>
				                    </div>
				                </div>

				                <? /*<div class="form-group">
				                    <label class="control-label col-md-4">Stok
				                    </label>
				                    <div class="col-md-6">
										<input readonly type="text" class='form-control' name="stok" id='data-qty-add'/>
										<!--<a data-toggle="popover" data-trigger='focus' id='data-qty' title="Qty" data-html="true">
										</a>-->
				                    </div>
				                </div>*/?> 

				                <div class="form-group">
				                    <label class="control-label col-md-4">Qty
				                    </label>
				                    <div class="col-md-6">
										<input readonly type="text" class='form-control' name="qty"/>
										STOK : <b><span id='data-qty-add'></span></b>
										<!--<a data-toggle="popover" data-trigger='focus' id='data-qty' title="Qty" data-html="true">
										</a>-->
				                    </div>
				                </div> 

				                <div class="form-group">
				                    <label class="control-label col-md-4">Jumlah Roll
				                    </label>
				                    <div class="col-md-6">
				                		<input readonly type="text" class='form-control' name="jumlah_roll"/>
										STOK : <b><span id='data-roll-add'></span></b>
										<!--<a data-toggle="popover" data-trigger='focus' id='data-roll' title="Jumlah Roll" data-html="true">
					                	</a>-->
				                    </div>
				                </div>

								<div class="form-group">
				                    <label class="control-label col-md-4">Nama Kru
				                    </label>
				                    <div class="col-md-6">
				                		<input type="text" class='form-control' name="nama_kru"/>
				                    </div>
				                </div>
				                <input name="rekap_qty" <?=(is_posisi_id() != 1 ? 'hidden' : '')?>>

							</form>
						</td>
						<td style='border:1px solid #ddd; padding:10px; text-align:center'>
								<table id='qty-table' style='margin:auto'>
									<thead>
										<tr>
											<th class='nama_satuan'>Yard</th>
											<th class='nama_packaging'>Roll</th>
											<th></th>
											<th style='text-align:center'>Subtotal</th>
											<th></th>
										</tr>
									</thead>
									<tbody>
									</tbody>
								</table>
							</div>
							<div class='yard-info' style="font-size:1.2em">
								TOTAL QTY: <span class='yard_total' >0</span> Yard <br/>
								TOTAL ROLL: <span class='jumlah_roll_total' >0</span> Roll
							</div>
						</td>
						<td style='border:1px solid #ddd; padding:10px;  width:30%'>
							<b>Rincian :</b><br/> 
							<table id='qty-table-detail'>
								<tbody></tbody>
							</table>
						</td>
					</tr>
				</table>
			</div>

			<div class="modal-footer">
				<button disabled type="button" class="btn blue btn-active btn-save">Save</button>
				<button type="button" class="btn default" data-dismiss="modal">Close</button>
			</div>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>

<div class="modal fade bs-modal-full" id="portlet-config-edit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-full">
		<div class="modal-content">
			<div class="modal-body">
				<h3 class='block'> Mutasi Barang Edit</h3>
				<table width="100%">
					<tr style='vertical-align:top'>
						<td style='border:1px solid #ddd; padding:10px;  width:40%'>
							<form action="<?=base_url('inventory/mutasi_barang_update')?>" class="form-horizontal" id="form_edit_data" method="post">
								
								<div class="form-group">
				                    <label class="control-label col-md-4">Lokasi Sebelum<span class="required">
				                    * </span>
				                    </label>
				                    <div class="col-md-6">
				                    	<input name='mutasi_barang_id' hidden>
				                    	<select class='input1 form-control' style='font-weight:bold' name="gudang_id_before">
				                    		<?foreach ($this->gudang_list_aktif as $row) { ?>
				                    			<option <?=($row->id == 1 ? 'selected' : '');?> value="<?=$row->id?>"><?=$row->nama;?></option>
				                    		<? } ?>
				                    	</select>
				                    </div>
				                </div>			                

				                <div class="form-group">
				                    <label class="control-label col-md-4">Lokasi Setelah<span class="required">
				                    * </span>
				                    </label>
				                    <div class="col-md-6">
			                			<select style='font-weight:bold' class='form-control' name="gudang_id_after">
				                    		<?foreach ($this->gudang_list_aktif as $row) { ?>
				                    			<option <?=($row->id == 2 ? 'selected' : '');?> value="<?=$row->id?>"><?=$row->nama;?></option>
				                    		<? } ?>
				                    	</select>
				                    </div>
				                </div> 

				                <div class="form-group">
				                    <label class="control-label col-md-4">Tanggal<span class="required">
				                    * </span>
				                    </label>
				                    <div class="col-md-6">
						                <input type="text" readonly class="form-control date-picker" name="tanggal"/>
				                    </div>
				                </div> 	

				                <div class="form-group">
				                    <label class="control-label col-md-4">Barang<span class="required">
				                    * </span>
				                    </label>
				                    <div class="col-md-6">
				                		<select class='form-control' name="barang_id" id='barang_id_select2'>
				                			<option value=''>Pilih</option>
				                    		<?foreach ($this->barang_list_aktif as $row) {
				                    			if ($row->status_aktif == 1) {?>
					                    			<option value="<?=$row->id?>"><?=$row->nama_jual;?></option>
					                    		<? } ?>
				                    		<? } ?>
				                    	</select>
				                    </div>
				                </div>

				                <div class="form-group">
				                    <label class="control-label col-md-4">Warna<span class="required">
				                    * </span>
				                    </label>
				                    <div class="col-md-6">
				                		<select class='form-control' name="warna_id" id='warna_id_select2'>
				                			<option value=''>Pilih</option>
				                    		<?foreach ($this->warna_list_aktif as $row) { ?>
				                    			<option value="<?=$row->id?>"><?=$row->warna_beli;?></option>
				                    		<? } ?>
				                    	</select>
				                    </div>
				                </div>

				                <div class="form-group">
				                    <label class="control-label col-md-4">Qty
				                    </label>
				                    <div class="col-md-6">
										<input readonly type="text" class='form-control' name="qty"/>
										STOK : <b><span id='data-qty-edit'></span></b>
										<!--<a data-toggle="popover" data-trigger='focus' id='data-qty-edit' title="Qty" data-html="true">
										</a>-->
				                    </div>
				                </div> 

				                <div class="form-group">
				                    <label class="control-label col-md-4">Jumlah Roll
				                    </label>
				                    <div class="col-md-6">
				                		<input readonly type="text" class='form-control' name="jumlah_roll"/>
				                		STOK : <b><span id='data-roll-edit'></span></b>
										<!--<a data-toggle="popover" data-trigger='focus' id='data-roll-edit' title="Jumlah Roll" data-html="true">
					                	</a>-->
				                    </div>
				                </div>

								<div class="form-group">
				                    <label class="control-label col-md-4">Nama Kru
				                    </label>
				                    <div class="col-md-6">
				                		<input type="text" class='form-control' name="nama_kru"/>
				                    </div>
				                </div>
				                <input name="rekap_qty" <?=(is_posisi_id() != 1 ? 'hidden' : '')?>>
							</form>
						</td>
						<td style='border:1px solid #ddd; padding:10px;  width:30%'>
							<table id='qty-table-edit'>
								<thead>
									<tr>
										<th class='nama_satuan'>Yard</th>
										<th class='nama_packaging'>Roll</th>
										<th></th>
										<th style='text-align:center'>Subtotal</th>
										<th></th>
									</tr>
								</thead>
								<tbody>
								</tbody>
							</table>
							<div class='yard-info' style="font-size:1.2em">
								TOTAL QTY: <span class='yard_total_edit' >0</span> Yard <br/>
								TOTAL ROLL: <span class='jumlah_roll_total_edit' >0</span> Roll
							</div>
						</td>
						<td style='vertical-align:top; border:1px solid #ddd; padding:10px;  width:30%'>
							<b>Rincian :</b><br/> 
							<table id='qty-table-detail-edit'></table>
						</td>
					</tr>
				</table>
			</div>

			<div class="modal-footer">
				<button type="button" class="btn blue btn-active btn-edit-save">Save</button>
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
					<span class="caption-subject theme-font bold uppercase"><?=$breadcrumb_small;?></span>
				</div>
				<div class="actions">
					<select class='btn btn-sm btn-default' name='status_aktif_select' id='status_aktif_select'>
						<option value="1" selected>Aktif</option>
						<option value="0">Tidak Aktif</option>
					</select>
					<?if (is_posisi_id() != 6) { ?>
						<a href="#portlet-config" data-toggle='modal' class="btn btn-default btn-sm btn-brg-add">
						<i class="fa fa-plus"></i> Mutasi Barang Baru </a>
					<?}?>
				</div>
			</div>
			<div class="portlet-body">
				<table width='100%'>
					<tr>
						<td>
							<form action='' method='get'>
								<table>
									<tr>
										<td>
											<table>
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
												<tr>
													<td>Barang</td>
													<td class='padding-rl-5'> : </td>
													<td>
														<b>
															<select name='barang_id' id='barang_select' style="width:100%;">
																<option <?=($barang_id == 0 ? 'selected' : '');?> value='0'>Semua</option>
																<?foreach ($this->barang_list_aktif as $row) { ?>
																	<option <?=($barang_id == $row->id ? 'selected' : '');?> value='<?=$row->id?>'><?=$row->nama_jual;?></option>
																<?}?>
															</select>
														</b>
													</td>
												</tr>
												<tr>
													<td>Warna</td>
													<td class='padding-rl-5'> : </td>
													<td>
														<b>
															<select name='warna_id' id='warna_select' style="width:100%;">
																<option <?=($warna_id == 0 ? 'selected' : '');?> value='0'>Semua</option>
																<?foreach ($this->warna_list_aktif as $row) { ?>
																	<option <?=($warna_id == $row->id ? 'selected' : '');?> value='<?=$row->id?>'><?=$row->warna_beli;?></option>
																<?}?>
															</select>
														</b>
													</td>
												</tr>
											</table>
										</td>
										<td>
											<?if (date('Y-m-d') < '2018-03-07' && $cond == '') { ?>

												<div id='info-section' class='alert alert-info' style='position:absolute; margin:10px; top:75px; '>
														<i style='font-weight:bold' class='fa fa-arrow-left'></i> User dapat memilih hanya nama barang saja atau nama warna saja <br/>
														Tanggal <i>default</i> yang dipilih adalah periode 1 minggu
												</div>
											<?}?>
										</td>
									</tr>
								</table>
								
							</form>
						</td>
						<td class='text-right'>
							<a href="<?=base_url().'inventory/mutasi_barang_excel?tanggal_start='.is_date_formatter($tanggal_start).'&tanggal_end='.is_date_formatter($tanggal_end).'&barang_id='.$barang_id.'&warna_id='.$warna_id;?>" class='btn btn-md green'><i class='fa fa-download'></i> EXCEL</a>
						</td>
					</tr>
							
				</table>

				<hr/>
				<!-- table-striped table-bordered  -->
				<table class="table table-hover table-bordered" id="general_table">
					<thead>
						<tr>
							<th scope="col" class='status_column'>
								Status Aktif
							</th>
							<th scope="col" style='width:150px;'>
								Tanggal
							</th>
							<th scope="col" style='width:150px;'>
								Nama
							</th>
							<th scope="col">
								Lokasi Sebelum
							</th>
							<th scope="col">
								Lokasi Setelah
							</th>
							<th scope="col" class="text-center">
								Qty
							</th>
							<th scope="col">
								Roll
							</th>
							<?if (is_posisi_id() <= 3) {?>
								<th scope="col">
									User
								</th>
							<?}?>
							<th scope="col">
								Kru
							</th>
							<th scope="col">
								Action 
							</th>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
</div>			
</div>

<script src="<?php echo base_url('assets/global/plugins/bootstrap-modal/js/bootstrap-modalmanager.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/bootstrap-modal/js/bootstrap-modal.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/bootbox/bootbox.min.js'); ?>" type="text/javascript"></script>

<script src="<?php echo base_url('assets/global/plugins/datatables/media/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets_noondev/js/table-advanced.js'); ?>"></script>


<script>

var qtyTblDetail = []
var qtyListDetail = []

var qty_global = 0;
var jumlah_roll_global = 0;


jQuery(document).ready(function() {
	// TableAdvanced.init();

	// oTable = $('#general_table').DataTable();
	// oTable.state.clear();
	// oTable.destroy();
	$('[data-toggle="popover"]').popover();

	setTimeout(function(){
		$('#info-section').toggle('slow');
	},7000);


	$('#barang_id_select, #warna_id_select,#barang_id_select2, #warna_id_select2, #barang_select, #warna_select').select2({
        placeholder: "Pilih...",
        allowClear: true
    });

    
	var idx = 1;
	$("#general_table").DataTable({
   		"fnCreatedRow": function( nRow, aData, iDataIndex ) {
            
            var status_aktif = $('td:eq(0)', nRow).text();
            <?if (is_posisi_id() <= 3) {?>
	            var data = $('td:eq(9)', nRow).text().split('??');
            <?}else{?>
	            var data = $('td:eq(8)', nRow).text().split('??');
        	<?}?>
            var id = data[0];
            var gudang_id_before = data[1];
            var gudang_id_after = data[2];
            var barang_id = data[3];
            var warna_id = data[4];
            let new_rekap = '';
			let rekapTableQty = "";

            if (typeof data[5] !== 'undefined') {
	            var qty_data = data[5].split(',');
	            var roll_data = data[6].split(',');
	            var id_det = data[7].split(',');
	            // var rekap_qty = data[5];
	            let qty_rekap = [];
	            for (var j = 0; j < qty_data.length ; j++) {
	            	qty_rekap.push(`${parseFloat(qty_data[j])}??${roll_data[j]}??${id_det[j]}`)
					rekapTableQty += `<li>
							<div>${parseFloat(qty_data[j])}</div>x
							<div>${roll_data[j]}</div>
						</li>`;
	            };
	            console.log(qty_rekap);

	            let rQty = [];
	            new_rekap = qty_rekap.length > 0 ? qty_rekap.join('--') : '';
	            console.log(new_rekap);
				
            	
            };


            
            <?if (is_posisi_id() <= 2) { ?>
            	var btn_edit = "<a href='#portlet-config-edit' data-toggle='modal' class='btn btn-xs blue btn-edit'><i class='fa fa-edit'></i></a>";
            	if (status_aktif == 1) {
	            	var btn_status ="<a class='btn btn-xs red btn-remove'><i class='fa fa-times'></i></a>";	
            	}else{
	            	var btn_status ="<a class='btn btn-xs blue btn-remove'><i class='fa fa-play'></i></a>";
            	};
            <?}else{?>
            	var btn_edit = "";
            	var btn_status = '';
        	<?}?>

            var action = `<span class='id' hidden>${id}</span>
            			<span class='gudang_id_before' hidden>${gudang_id_before}</span>
            			<span class='gudang_id_after' hidden>${gudang_id_after}</span>
            			<span class='barang_id' hidden>${barang_id}</span>
            			<span class='status_aktif' hidden>${status_aktif}</span>
            			<span class='warna_id' hidden>${warna_id}</span>${btn_edit}${btn_status}
            			<span class='rekap_qty' hidden>${new_rekap}</span>
            			`;

			const q = parseFloat($('td:eq(5)', nRow).text());
			const popQty = `<div><span class='qty' style="cursor:pointer; color:blue">${parseFloat(q)}</span> <div class="detail-list">
				<i class="fa fa-times qty-detail-close" onclick="closeDetail(e)"></i>
				<b>total: ${parseFloat(q)}</b> 
				<br/> 
				<ul>
				<li>
							<div>qty</div>x
							<div>roll</div>
						</li>
				${rekapTableQty}
				</ul>
			</div></div>`;

			$('td:eq(0)', nRow).addClass('status_column');
            $('td:eq(1)', nRow).html("<span class='tanggal' hidden>"+date_formatter($('td:eq(1)', nRow).text())+"</span>"+date_formatter_month_name($('td:eq(1)', nRow).text()));
            $('td:eq(5)', nRow).html(popQty).addClass("text-center");
            $('td:eq(6)', nRow).html("<span class='jumlah_roll'>"+$('td:eq(6)', nRow).text()+"</span>");
            $('td:eq(8)', nRow).html("<span class='nama_kru'>"+$('td:eq(8)', nRow).text()+"</span>");
            <?if (is_posisi_id() <= 3) {?>
	            $('td:eq(9)', nRow).html(action);
            <?}else{?>
	            $('td:eq(8)', nRow).html(action);
        	<?}?>
            // $('td:eq(2)', nRow).html(btn_view);


        },
        "bStateSave" :true,
		"bProcessing": true,
		"bServerSide": true,
		"ordering":false,
		"sAjaxSource": baseurl + "inventory/data_mutasi?<?=($cond != "" ? "cond=".$cond."&" : "") ?>tanggal_start=<?=is_date_formatter($tanggal_start)?>&tanggal_end=<?=is_date_formatter($tanggal_end)?>"
	});

	var oTable;
    oTable = $('#general_table').dataTable();
    oTable.fnFilter( 1, 0 );

	$('#status_aktif_select').change(function(){
		oTable.fnFilter( $(this).val(), 0 ); 
	});

	$("#general_table").on('click',".btn-remove",function(){
		var ini = $(this).closest('tr');
		var status_aktif = ini.find('.status_aktif').html();
		var id = ini.find('.id').html();
		bootbox.confirm("Hapus mutasi ?", function(respond){
			if (respond) {
				window.location.replace(baseurl+"inventory/mutasi_barang_batal?id="+id+"&status_aktif="+status_aktif);
			};
		});
	});
	
	// href='"+baseurl+"inventory/mutasi_barang_batal/"+id+"/"+status_aktif+"'

//========================================add data=================================================

	$('#form_add_data [name=gudang_id_before]').change(function(){
		var gudang_before = $(this).val();
		var gudang_after = $('#form_add_data [name=gudang_id_after]').val();
		if (gudang_before ==  gudang_after) {
			if (gudang_before > 1) {
				gudang_after = 1;
			}else{
				gudang_after = 2;
			};
			$('#form_add_data [name=gudang_id_after]').val(gudang_after);
		}
	});

	$('#form_add_data [name=gudang_id_after]').change(function(){
		var gudang_after = $(this).val();
		var gudang_before = $('#form_add_data [name=gudang_id_before]').val();
		if (gudang_before ==  gudang_after) {
			if (gudang_after > 1) {
				gudang_before = 1;
			}else{
				gudang_before = 2;
			};
			$('#form_add_data [name=gudang_id_before]').val(gudang_before);
		}
	});

	$('#form_add_data [name=barang_id], #form_add_data [name=warna_id], #form_add_data [name=gudang_id_before],#form_add_data [name=gudang_id_after]').change(function(){
		$('#data-qty').attr('data-content','');
		$('#data-roll').attr('data-content','');
		$('#qty-table-detail tbody').empty();

		if ($('#form_add_data [name=barang_id]').val() != '' &&  $('#form_add_data [name=warna_id]').val() != '') {

			if ($('#form_add_data [name=barang_id]').val() != '' && $('#form_add_data [name=warna_id]').val() != '') {
				$('#form_add_data [name=qty]').attr('placeholder','loading...');
				$('#form_add_data [name=jumlah_roll]').attr('placeholder','loading...');

				var data = {};
				data['barang_id'] = $('#form_add_data [name=barang_id]').val();
				data['warna_id'] = $('#form_add_data [name=warna_id]').val();
				data['gudang_id'] = $('#form_add_data [name=gudang_id_before]').val();
				data['tanggal'] = $('#form_add_data [name=tanggal]').val();
				var url = 'inventory/cek_barang_qty';
				ajax_data_sync(url,data).done(function(data_respond  /*,textStatus, jqXHR*/ ){
					// alert(data_respond)

					qtyTblDetail = [];
					qtyListDetail = [];
					let tblD = '';
					
					$.each(JSON.parse(data_respond),function(k,v){
						if (k==0) {
							var qty = v[0].qty;
							// alert(v.qty);
							var roll = v[0].jumlah_roll;
							$('#data-qty').attr('data-content',qty);
							$('#data-roll').attr('data-content',roll);

							$('#data-qty-add').html(qty);
							$('#data-roll-add').html(roll);


							qty_global = qty;
							jumlah_roll_global = roll;

							$('#form_add_data [name=qty]').attr('placeholder','');
							$('#form_add_data [name=jumlah_roll]').attr('placeholder','');

							// $('#form_add_data [name=qty]').attr('readonly',false);
							// $('#form_add_data [name=jumlah_roll]').attr('readonly',false);
							
						}else if(k==1){

							console.log(`detail length ${k}`,v.length);
			            	for (var i = 0; i < v.length; i++) {
								
								let sisa_roll = parseFloat(v[i].roll_stok_masuk) + parseFloat(v[i].jumlah_roll_masuk) - v[i].roll_stok_keluar - v[i].jumlah_roll_keluar;
			            		if (v[i].qty == '100' || parseFloat(v[i].qty) == 100) {
			    					console.log('detail',parseFloat(v[i].roll_stok_masuk) +'+'+ parseFloat(v[i].jumlah_roll_masuk) +'-'+ v[i].roll_stok_keluar +'-'+ v[i].jumlah_roll_keluar);
				            		console.log(v[i].qty+':', sisa_roll);
			    				}
			            		console.log(v[i].qty+':', sisa_roll);
			            		if (sisa_roll > 0) {
			            			qtyListDetail[`s-${parseFloat(v[i].qty)}`] = sisa_roll;
			            			for (var j = 0; j < sisa_roll; j++) {
			            				if (v[i].qty != '100' && v[i].qty != 100) {
					            			qtyTblDetail.push(parseFloat(v[i].qty));
			            				};
			            			};
			            		};
			            	};

			            	// console.log('si-100', qtyListDetail[`s-100`]);
			            	let brs = Math.ceil((qtyTblDetail.length/5) );
			            	console.log('brs', brs);
			            	console.log('s-100', qtyListDetail[`s-100`]);
			            	if (qtyListDetail[`s-100`] > 0) {
			            		tblD += `<tr><td colspan='5' style='padding:5px; text-align:center' class='s-100'> 100 x ${qtyListDetail['s-100']}</td></tr>`
			            	};
			            	for (var i = 0; i < brs; i++) {
			            		tblD += '<tr>';
				            	for (var j = 0; j < 5; j++) {
				            		if (typeof qtyTblDetail[(i*5) + j] !== 'undefined') {
					            		tblD += `<td class='s-${qtyTblDetail[(i*5) + j]}'>${qtyTblDetail[(i*5) + j]}</td>`
						            	console.log('l',tblD);
				            			
				            		};
				            	};
			            		tblD += '<tr>';
			            	};
			            };
					});
					
			            	console.log('l',qtyTblDetail);
			        $('#qty-table-detail tbody').append(tblD);
		   		});
			};
		}else{
			$('#form_add_data [name=qty]').attr('readonly',true);
			$('#form_add_data [name=jumlah_roll]').attr('readonly',true);
		}
	});

//========================================baru ada detail=================================

	var map = {13: false};
	$("#qty-table tbody").on("keydown",'.qty, .jumlah_roll', function(e) {
		let ini = $(this);
		let tabindex = ini.attr('tabindex');
		// alert(e.keyCode);
	    if (e.keyCode in map) {
	        map[e.keyCode] = true;
	        if (map[13]) {
	        	if (ini.attr('name') == 'jumlah_roll') {
	        		let stat = cekStok(ini, 'qty-table');
	        		// alert(stat); 
	        		if(stat){
			        	$("#qty-table").find(`[tabindex = ${parseInt(tabindex)+1}]`).focus();
	        		}
	        	}else{
			        $("#qty-table").find(`[tabindex = ${parseInt(tabindex)+1}]`).focus();
	        	};
	        	compareDetail();
	        	// alert(ini);
	            
	        }
	    }
	}).keyup(function(e) {
	    if (e.keyCode in map) {
	        map[e.keyCode] = false;
	    }
	});
	
    
    $("#qty-table").on('change','.qty',function(){
   		let ini = $(this);
   		let qty = ini.val();
	   		// alert(qtyListDetail[`s-${parseFloat(qty)}`]);
	   		// alert(qty.length);
   		if (qty.length > 1) {
	   		let stat = true;
	   		if (typeof qtyListDetail[`s-${parseFloat(qty)}`] === 'undefined' || qtyListDetail[`s-${parseFloat(qty)}`] == 0) {
	   			stat = false;
	   			alert("Rincian tidak ada");
	   		}else{
		   		cekStok(ini, 'qty-table');
	   		}

   			if (stat==false) {
   				ini.val('');
   				ini.focus();
	   			// ini.closest('tr').find('td').eq(2).html('');
   			}else{
	   			// $("#stok-roll-info").show();
   			};

   			// console.log(qtyListAmbil);
   		};

   		if (qty.length == 0) {
   			// ini.closest('tr').find('td').eq(2).html('');
   		};
    });

    $('#qty-table').on('change','.jumlah_roll', function(){
    	let ini = $(this);
    	if (ini.closest('tr').find('.qty').val() == '') {
    		alert('Mohon isi qty');
    		ini.closest('tr').find('.qty').focus();
    		return false;
    	}else{
	   		cekStok(ini, 'qty-table');
    	};

	    });

	$('#form_add_data [name=qty]').change(function(){
    	compareDetail();
	});

	$('#form_add_data').on('input','[name=jumlah_roll]',function(){
    	compareDetail();
	});

	$('.btn-save').click(function(){
		if($('#form_add_data [name=tanggal]').val() != '' && $('#form_add_data [name=qty]').val() != '' && $('#form_add_data [name=qty]').val() != 0 && $('#form_add_data [name=jumlah_roll]').val() != '' && $('#form_add_data [name=jumlah_roll]').val() != 0){
			$('#form_add_data').submit();
			btn_disabled_load($(this));
		}else{
			bootbox.alert("Mohon isi tanggal & jumlah ");
		}
	});

//========================================edit data=================================================

	$('#general_table').on('click','.btn-edit',function(){

		$('#data-qty-edit').attr('data-content','');
		$('#data-roll-edit').attr('data-content','');

		var ini = $(this).closest('tr');
		var form = $('#form_edit_data');

		form.find('[name=mutasi_barang_id]').val(ini.find('.id').html());
		form.find('[name=gudang_id_before]').val(ini.find('.gudang_id_before').html());
		form.find('[name=gudang_id_after]').val(ini.find('.gudang_id_after').html());
		form.find('[name=tanggal]').val(ini.find('.tanggal').html());
		form.find('[name=rekap_qty]').val(ini.find('.rekap_qty').html());
		form.find('[name=nama_kru]').val(ini.find('.nama_kru').html());
		
		var barang_id = ini.find('.barang_id').html();
		var warna_id = ini.find('.warna_id').html();
		$("#barang_id_select2").val(barang_id).trigger('change');
		$("#warna_id_select2").val(warna_id).trigger('change');

		var qty_now = ini.find('.qty').html();
		var jml_roll_now = ini.find('.jumlah_roll').html();
		form.find('[name=qty]').val(ini.find('.qty').html());
		form.find('[name=jumlah_roll]').val(ini.find('.jumlah_roll').html());

		$('#form_edit_data [name=qty]').attr('placeholder','loading...');
		$('#form_edit_data [name=jumlah_roll]').attr('placeholder','loading...');

		var data = {};
		data['barang_id'] = $('#form_edit_data [name=barang_id]').val();
		data['warna_id'] = $('#form_edit_data [name=warna_id]').val();
		data['gudang_id'] = $('#form_edit_data [name=gudang_id_before]').val();
		data['tanggal'] = $('#form_edit_data [name=tanggal]').val();
		data['mutasi_barang_id'] = $('#form_edit_data [name=mutasi_barang_id]').val();

		generateQtyDetailTableEdit(ini.find('.rekap_qty').html());
		compareDetailEdit();
		var url = 'inventory/cek_barang_qty';
		$('#qty-table-detail-edit').empty();
		ajax_data_sync(url,data).done(function(data_respond  /*,textStatus, jqXHR*/ ){
			
			qtyTblDetail = [];
			qtyListDetail = [];
			let tblD = '';
			
			$.each(JSON.parse(data_respond),function(k,v){
				if (k==0) {
					var qty = v[0].qty;
					// alert(v.qty);
					var roll = v[0].jumlah_roll;
					$('#data-qty').attr('data-content',qty);
					$('#data-roll').attr('data-content',roll);

					$('#data-qty-add').html(qty);
					$('#data-roll-add').html(roll);


					qty_global = qty;
					jumlah_roll_global = roll;

					$('#form_edit_data [name=qty]').attr('placeholder','');
					$('#form_edit_data [name=jumlah_roll]').attr('placeholder','');

					// $('#form_add_data [name=qty]').attr('readonly',false);
					// $('#form_add_data [name=jumlah_roll]').attr('readonly',false);
					
				}else if(k==1){
	            	for (var i = 0; i < v.length; i++) {
	            		let sisa_roll = parseFloat(v[i].roll_stok_masuk) + parseFloat(v[i].jumlah_roll_masuk) - v[i].roll_stok_keluar - v[i].jumlah_roll_keluar;

	            		if (sisa_roll > 0) {
	            			qtyListDetail[`s-${parseFloat(v[i].qty)}`] = sisa_roll;
	            			for (var j = 0; j < sisa_roll; j++) {
	            				if (v[i].qty != '100' && v[i].qty != 100) {
			            			qtyTblDetail.push(parseFloat(v[i].qty));
	            				};
	            			};
	            		};
	            	};

	            	// console.log('si-100', qtyListDetail[`s-100`]);
	            	let brs = Math.ceil((qtyTblDetail.length/5) );
	            	console.log('brs', brs);
	            	if (qtyListDetail[`s-100`] > 0) {
	            		tblD += `<tr><td colspan='5' style='padding:5px; text-align:center' class='s-100'> 100 x ${qtyListDetail['s-100']}</td></tr>`
	            	};
	            	for (var i = 0; i < brs; i++) {
	            		tblD += '<tr>';
		            	for (var j = 0; j < 5; j++) {
		            		if (typeof qtyTblDetail[(i*5) + j] !== 'undefined') {
			            		tblD += `<td class='s-${qtyTblDetail[(i*5) + j]}'>${qtyTblDetail[(i*5) + j]}</td>`
		            			
		            		};
		            	};
	            		tblD += '<tr>';
	            	};
	            };
			});
			
	            	console.log('l',qtyTblDetail);
	            	console.log('l',tblD);
	        $('#qty-table-detail-edit').append(tblD);
   		});
		
	});

	$('#form_edit_data [name=qty]').change(function(){
		// alert(jumlah_roll_global);
		var qty = parseInt($(this).val());
		var jumlah_roll = parseInt($('#form_edit_data [name=jumlah_roll]').val());
		compareDetailEdit();
		if (qty > qty_global) {
			notific8('ruby', "Kuantiti melebihi stok");
			$('.btn-edit-save').attr('disabled',true);
		}else{
			if (jumlah_roll <= jumlah_roll_global) {
				$('.btn-edit-save').attr('disabled',false);
			}else{
				$('.btn-edit-save').attr('disabled',true);
			}
		}
	});

	$('#form_edit_data [name=jumlah_roll]').change(function(){
		var jumlah_roll = parseInt($(this).val());
		var qty = parseInt($('#form_edit_data [name=qty]').val());
		compareDetailEdit();
		console.log(jumlah_roll+'>'+jumlah_roll_global);
		if (jumlah_roll > jumlah_roll_global) {
			notific8('ruby', "Jumlah Roll melebihi stok");
			$('.btn-edit-save').attr('disabled',true);
		}else{
			if (qty <= qty_global) {
				$('.btn-edit-save').attr('disabled',false);
			}else{
				$('.btn-edit-save').attr('disabled',true);
			}
		}
	});

	$('.btn-edit-save').click(function(){
		if($('#form_edit_data [name=tanggal]').val() != '' && $('#form_edit_data [name=qty]').val() != '' && $('#form_edit_data [name=qty]').val() != 0 && $('#form_edit_data [name=jumlah_roll]').val() != '' && $('#form_edit_data [name=jumlah_roll]').val() != 0){
			$('#form_edit_data').submit();
			btn_disabled_load($(this));
		}else{
			bootbox.alert("Mohon isi tanggal & jumlah ");
		}
	});

//========================================set data=================================================
	<?if ($barang_id_latest != '') {?>
		// alert("<?=$barang_id_latest?>");
		$("#portlet-config").modal('toggle');
    	resetQtyDetailTable();
		$('#barang_id_select').select2('val','<?=$barang_id_latest;?>');
		var gudang_before = "<?=$gudang_before_latest?>";
		var gudang_after = "<?=$gudang_after_latest?>";
		$('#form_add_data [name=gudang_id_before]').val(gudang_before);
		$('#form_add_data [name=gudang_id_after]').val(gudang_after);

	<?};?>


//======================================================================

	$('.btn-brg-add').click(function(){
    	resetQtyDetailTable();
    });

    // $('#qty-table').on('keypress','.qty , .jumlah_roll ',function (e) {
	   // 	if (e.which == 13) {
	   //      let tbi = parseFloat($(this).attr('tabindex'))+1;
	   //      $("#qty-table").find(`[tabindex=${tbi}]`).focus();
    //     }
    // });

    $("#qty-table").on('change','.qty,.jumlah_roll',function(){
    	data_result = table_qty_update('#qty-table').split('=*=');
    	let total = parseFloat(data_result[0]);
    	let total_roll = parseFloat(data_result[1]);
    	let rekap = data_result[2];
    	

    	$('.yard_total').html(total.toFixed(2));
    	$('.jumlah_roll_total').html(total_roll);
    	$('#form_add_data [name=rekap_qty]').val(rekap);
    	compareDetail();
    });

    $(document).on("click", ".btn-add-qty-row", function(){
		let j = $("#qty-table tbody").find("tr").length;
		// alert(j);
		j++;
    	$("#qty-table tbody").append(`<tr>
				<td><input class='qty' tabindex="${(j*2)-1}"></td>
				<td><input class='jumlah_roll' name='jumlah_roll' tabindex="${j*2}"></td>
				<td style='padding:0 5px'> = </td>
				<td><input class='subtotal' tabindex='-1'></td>
				<td></td>
			</tr>`);
    });

//===============================edit======================================

	$('#qty-table-edit').on('keypress','.qty , .jumlah_roll ',function (e) {
	   	if (e.which == 13) {
	        let tbi = parseFloat($(this).attr('tabindex'))+1;
	        $("#qty-table-edit").find(`[tabindex=${tbi}]`).focus();
        }
    });

    $("#qty-table-edit").on('change','.qty,.jumlah_roll',function(){
    	data_result = table_qty_update('#qty-table-edit').split('=*=');
    	let total = parseFloat(data_result[0]);
    	let total_roll = parseFloat(data_result[1]);
    	let rekap = data_result[2];

		
		$('#form_edit_data [name=qty]').val(total);
		$('#form_edit_data [name=jumlah_roll]').val(total_roll);
    	$('.yard_total_edit').html(total.toFixed(2));
    	$('.jumlah_roll_total_edit').html(total_roll);
    	$('#form_edit_data [name=rekap_qty]').val(rekap);
    	compareDetailEdit();
    });

    $(document).on("click", ".btn-add-qty-row-edit", function(){
		// alert('y');
		let j = $("#qty-table tbody").find("tr").length;
		j++;
    	$("#qty-table-edit tbody").append(`<tr>
				<td><input class='qty' tabindex="${(j*2)-1}"></td>
				<td><input class='jumlah_roll' tabindex="${j*2}"></td>
				<td style='padding:0 5px'> = </td>
				<td><input class='subtotal' tabindex='-1'></td>
				<td></td>
			</tr>`);
    });
//===============================edit======================================

	$(`#general_table`).on("click",".qty", function(){
		$(".detail-list").hide();
		const ini = $(this).closest("tr");
		ini.find(".detail-list").show();
	});

	$(document).click(function(e){
		const target = $(e.target);
		if(!target.hasClass("qty")){
			$(".detail-list").hide();
		}
	});

});

function compareDetail(){
	let qty_ori = $("#form_add_data [name=qty]").val();
	let roll_ori = $("#form_add_data [name=jumlah_roll]").val();

	let total = $(".yard_total").text();
	let total_roll = $(".jumlah_roll_total").text();
	if (parseFloat(qty_ori) != parseFloat(total)) {
		console.log(parseFloat(qty_ori) +"!="+ parseFloat(total));
		// $(".yard_total").css("color","red");
		qty_ori = total
		$("#form_add_data [name=qty]").val(total);
	}else{
		$(".yard_total").css("color","#000");
	};
	if (parseFloat(roll_ori) != parseFloat(total_roll)) {
		console.log(parseFloat(roll_ori) +"!="+ parseFloat(total_roll));
		// $(".jumlah_roll_total").css("color","red");
		roll_ori = total_roll
		$("#form_add_data [name=jumlah_roll]").val(total_roll);
	}else{
		$(".jumlah_roll_total").css("color","#000");
	};

		console.log(roll_ori+'>'+jumlah_roll_global);
		console.log(qty_ori+'>'+qty_global);
	if (parseFloat(qty_ori) > parseFloat(qty_global) || parseFloat(roll_ori) > parseFloat(jumlah_roll_global)) {
		notific8('ruby', "Kuantiti/Roll melebihi stok");
		$('.btn-save').attr('disabled',true);
	}else{
		// notific8('lime', "OK");
		$('.btn-save').attr('disabled',false);
	}


}

function compareDetailEdit(){
	let qty_ori = $("#form_edit_data [name=qty]").val();
	let roll_ori = $("#form_edit_data [name=jumlah_roll]").val();

	let total = $(".yard_total_edit").text();
	let total_roll = $(".jumlah_roll_total_edit").text();
	// alert(total);
	// console.log(parseFloat(qty_ori) +"!="+ parseFloat(total));
	if (parseFloat(qty_ori) != parseFloat(total)) {
		$("#form_edit_data [name=qty]").val(total);
	}
	// else{
	// 	$(".yard_total_edit").css("color","#000");
	// };
	// console.log(parseFloat(roll_ori) +"!="+ parseFloat(total_roll));
	if (parseFloat(roll_ori) != parseFloat(total_roll)) {
		// $(".jumlah_roll_total_edit").css("color","red");
		$("#form_edit_data [name=jumlah_roll]").val(total_roll);
	}
	//else{
	// 	$(".jumlah_roll_total_edit").css("color","#000");
	// };
}

function resetQtyDetailTable(){
	$(".title-form-detail").text('Tambah');

	$(".yard_total").text("0");
	$(".jumlah_roll_total").text("0");
	$("#qty-table tbody").html("");
	let baris = '';
	for (var i = 1; i <= 10; i++) {
		btn='';
		if (i == 1) {
			btn = `<button tabindex='-1' class='btn btn-xs blue btn-add-qty-row'><i class='fa fa-plus'></i></button>`;
		};
		baris += `<tr><td><input class='qty' tabindex="${(i*2)-1}"></td>
		<td><input class='jumlah_roll' name='jumlah_roll'  tabindex="${i*2}" ></td>
		<td style='padding:0 5px'> = </td>
		<td><input class='subtotal' tabindex='-1'></td>
		<td>${btn}</td></tr>`
	};

	$("#qty-table tbody").append(baris);

}

function generateQtyDetailTableEdit(rekap_qty){

	let baris = '';
	let total = 0;
	let total_roll = 0;
	let j = 0;
	$("#qty-table-edit tbody").html("");
	if (rekap_qty != '' && typeof rekap_qty !== 'undefined') {
		$.each(rekap_qty.split("--"), function(i,v){
			btn='';
			j=i+1;
			if (i == 0) {
				btn = `<button tabindex='-1' class='btn btn-xs blue btn-add-qty-row-edit'><i class='fa fa-plus'></i></button>`;
			};
			let val = v.split('??');
			let sub = val[0] * val[1];
			total+= parseFloat(sub);
			total_roll += parseFloat(val[1]);
			baris += `<tr><td><input class='qty' value="${parseFloat(val[0])}" tabindex="${(j*2)-1}"></td>
			<td><input class='jumlah_roll' value="${val[1]}"  tabindex="${j*2}" ></td>
			<td style='padding:0 5px'> = </td>
			<td><input class='subtotal' value="${parseFloat(sub)}" tabindex='-1'><input name='id' value='${val[2]}' hidden></td>
			<td>${btn}</td></tr>`;
			j++;

		});
		
	};

	btn='';
	if (j == 0) {
		btn = `<button tabindex='-1' class='btn btn-xs blue btn-add-qty-row-edit'><i class='fa fa-plus'></i></button>`;
	};

	for (var i = j; i < j+10; i++) {
		baris += `<tr><td><input class='qty' tabindex="${(i*2)-1}"></td>
			<td><input class='jumlah_roll' tabindex="${i*2}" ></td>
			<td style='padding:0 5px'> = </td>
			<td><input class='subtotal' tabindex='-1'></td>
			<td>${btn}</td></tr>`
	btn='';
		
	};



	$(".yard_total_edit").text(total);
	$(".jumlah_roll_total_edit").text(total_roll);

	$("#qty-table-edit tbody").append(baris);

}

function table_qty_update(table){
	// console.log('start');
	var total = 0; 
	var idx = 0; 
	var rekap = [];
	var total_roll = 0;
	$(table+" .qty").each(function(){
		var ini = $(this).closest('tr');
		var qty = $(this).val();
		// console.log('qty',qty);
		var roll = ini.find('.jumlah_roll').val();
		var id = ini.find('[name=id]').val();
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
			if (qty == '') {qty=0};
			rekap[idx] = qty+'??'+roll+'??'+id;
			// alert(id);
		}

		console.log('rekap',idx, rekap[idx]);
		idx++; 
		// alert(total_roll);
		total += subtotal;
		ini.find('.subtotal').val(qty*roll);

	});

	rekap_str = rekap.join('--');
	console.log(total+'=*='+total_roll+'=*='+rekap_str);

	return total+'=*='+total_roll+'=*='+rekap_str;
}

function cekStok(ini, table){
	qtyListAmbil = [];
	let stat = true;
	$(`#${table} .qty`).each(function(){
		let qty = $(this).val();
		if (qty != '') {
			// console.log(`s-${qty}`,qtyListDetail[`s-${parseFloat(qty)}`]);
			if (qtyListDetail[`s-${parseFloat(qty)}`] > 0) {
				if (typeof qtyListAmbil[`s-${parseFloat(qty)}`] === 'undefined') {
					qtyListAmbil[`s-${parseFloat(qty)}`] = 0;
				};

				let jmlRoll= $(this).closest('tr').find('[name=jumlah_roll]').val();
				if (jmlRoll == '' || typeof jmlRoll ==='undefined' ) {jmlRoll = 1;};
				qtyListAmbil[`s-${parseFloat(qty)}`]+= parseInt(jmlRoll);

				// console.log('ambil <= roll');
				// console.log(qtyListAmbil[`s-${parseFloat(qty)}`] +'<='+ qtyListDetail[`s-${parseFloat(qty)}`] );
				if (qtyListAmbil[`s-${parseFloat(qty)}`] <= qtyListDetail[`s-${parseFloat(qty)}`] ) {
	   				ini.css('color', 'green');
				}else{
					stat = false;
					alert('roll tidak cukup');
	   				ini.val('');
	   				ini.css('color', 'red');
	   				ini.focus();
	   				return false;
				}
				
			};
		};
	});

	console.log('qA',qtyListAmbil);
	$('#qty-table-detail tr td').css('background','transparent');
	for (var key in qtyListAmbil) {
		let q = qtyListAmbil[key];
		if (q > 0) {
			let idx = 0;
			if (key != 's-100') {
				$(`.${key}`).each(function(){
					console.log('qB',q);
					console.log(q+'>'+idx);
					if (q > idx) {
						console.log('ya')
						$(this).css('background','yellow');
					}else{
						return false;
					};
					idx++;
				})	
			}else{
				$('.s-100').css('border','2px solid yellow');
			};
		};
	}
	if (stat) {
		return true;
	};
}

function closeDetail(e){
	const ini = $(e.target);
	ini.hide();
}

</script>
