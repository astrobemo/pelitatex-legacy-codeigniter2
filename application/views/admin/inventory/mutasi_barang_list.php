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

</style>

<div class="page-content">
	<div class='container'>

		<!-- <div id="pembelian-modal" class="modal fade" style='width:100%' tabindex="-1">
		</div> -->

		<div class="modal fade bs-modal-lg" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-body">
						<div class='col-xs-6'>
							<form action="<?=base_url('inventory/mutasi_barang_insert')?>" class="form-horizontal" id="form_add_data" method="post">
								<h3 class='block'> Mutasi Barang</h3>
								
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
				                    		<?foreach ($this->barang_list_aktif as $row) { ?>
				                    			<option value="<?=$row->id?>"><?=$row->nama_jual;?></option>
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
				                    		<?foreach ($this->warna_list_aktif as $row) { ?>
				                    			<option value="<?=$row->id?>"><?=$row->warna_jual;?></option>
				                    		<? } ?>
				                    	</select>
				                    </div>
				                </div>

				                <input name='rekap_qty' hidden <?//=(is_posisi_id() != 1 ? 'hidden' : ''); ?>>

				                <?/*<div class="form-group">
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
				                </div>*/?>
							</form>
						</div>
						<div class='col-xs-6'>
							<h3 class='block'> Rinci Barang</h3>
							
							<table id='qty-table'>
								<thead>
									<tr>
										<th>Yard/Kg</th>
										<th>Roll</th>
										<th></th>
										<th>Total</th>
										<th></th>
									</tr>
									<tr>
										<th>Stock</th>
										<th><b><span id='data-roll-add'></span></b></th>
										<th></th>
										<th><b><span id='data-qty-add'></span></b></th>
										<th></th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td><input name='qty[]' class='qty ' placeholder='yrd/kg'></td>
										<td><input name='jumlah_roll[]' class='jumlah-roll ' placeholder='roll'></td>
										<td> = </td>
										<td><input tabindex='-1' disabled readonly class='subtotal '></td>
										<td><button tabindex='-1' class='btn btn-xs blue btn-add-qty-row'><i class='fa fa-plus'></i></button></td>
									</tr>
									<?for ($i=0; $i < 3 ; $i++) {?> 
										<tr>
											<td><input name='qty[]' class='qty' ></td>
											<td><input name='jumlah_roll[]' class='jumlah-roll' ></td>
											<td> = </td>
											<td><input tabindex='-1' disabled readonly  class='subtotal '></td>
											<td></td>
										</tr>
									<?}?>
								</tbody>
								<tfoot>
									<tr style='font-size:1.2em; border-top:1px solid #171717;'>
										<td><b>TOTAL</b></td>
										<td><span class='total-roll'></span></td>
										<td></td>
										<td><span class='total-all'></span></td>
										<td></td>
									</tr>
								</tfoot>
							</table>
						</div>
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

		<div class="modal fade bs-modal-lg" id="portlet-config-edit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-body">
						<div class='col-xs-6'>
							<form action="<?=base_url('inventory/mutasi_barang_update')?>" class="form-horizontal" id="form_edit_data" method="post">
								<h3 class='block'> Mutasi Barang Edit</h3>
								
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
				                    		<?foreach ($this->barang_list_aktif as $row) { ?>
				                    			<option value="<?=$row->id?>"><?=$row->nama_jual;?></option>
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

					            <input name='rekap_qty' hidden <?//=(is_posisi_id() != 1 ? 'hidden' : ''); ?>>
				                <?/*<div class="form-group">
				                    <label class="control-label col-md-4">Qty
				                    </label>
				                    <div class="col-md-6">
										<input type="text" class='form-control' name="qty"/>
										STOK : <b><span id='data-qty-edit'></span></b>
										<!--<a data-toggle="popover" data-trigger='focus' id='data-qty-edit' title="Qty" data-html="true">
										</a>-->
				                    </div>
				                </div> 

				                <div class="form-group">
				                    <label class="control-label col-md-4">Jumlah Roll
				                    </label>
				                    <div class="col-md-6">
				                		<input type="text" class='form-control' name="jumlah_roll"/>
				                		STOK : <b><span id='data-roll-edit'></span></b>
										<!--<a data-toggle="popover" data-trigger='focus' id='data-roll-edit' title="Jumlah Roll" data-html="true">
					                	</a>-->
				                    </div>
				                </div>   */?>
							</form>
						</div>
						<div class='col-xs-6'>
							<h3 class='block'> Rinci Barang</h3>
							
							<table id='qty-table-edit'>
								<thead>
									<tr>
										<th>Yard/Kg</th>
										<th>Roll</th>
										<th></th>
										<th>Total</th>
										<th></th>
									</tr>
								</thead>
								<tbody>
									
								</tbody>
								<tfoot>
									<tr style='font-size:1.2em; border-top:1px solid #171717;'>
										<td><b>TOTAL</b></td>
										<td style='text-align:center'><span class='total-roll'></span></td>
										<td></td>
										<td><span class='total-all'></span></td>
										<td></td>
									</tr>
								</tfoot>
							</table>
						</div>
					</div>

					<div class="modal-footer">
						<button type="button" class="btn blue btn-edit-save">Save</button>
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
								<a href="#portlet-config" data-toggle='modal' class="btn btn-default btn-sm btn-form-add">
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
									<th scope="col">
										Qty
									</th>
									<th scope="col">
										Jumlah Roll
									</th>
									<?if (is_posisi_id() <= 3) {?>
										<th scope="col">
											User
										</th>
									<?}?>
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
jQuery(document).ready(function() {
	Metronic.init(); // init metronic core components
	Layout.init(); // init current layout
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

    var qty_global = 0;
    var jumlah_roll_global = 0;

	var idx = 1;
	$("#general_table").DataTable({
   		"fnCreatedRow": function( nRow, aData, iDataIndex ) {
            
            var status_aktif = $('td:eq(0)', nRow).text();
            <?if (is_posisi_id() <= 3) {?>
	            var data = $('td:eq(8)', nRow).text().split('??');
            <?}else{?>
	            var data = $('td:eq(7)', nRow).text().split('??');
        	<?}?>
            var data = $('td:eq(8)', nRow).text().split('??');
            var id = data[0];
            var gudang_id_before = data[1];
            var gudang_id_after = data[2];
            var barang_id = data[3];
            var warna_id = data[4];
            var qty_data = data[5];
            var roll_data = data[6];
            var detail_id_data = data[7];

            
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

        	var hidden = "<?=(is_posisi_id() != 1 ? 'hidden' : '');?>";
            var action = `<span class='id' hidden>${id}</span>
            			<span class='qty-data' hidden>${qty_data}</span>
            			<span class='roll-data' hidden>${roll_data}</span>
            			<span class='detail_id-data' hidden >${detail_id_data}</span>
            			
            			<span class='gudang_id_before' hidden>${gudang_id_before}</span>
            			<span class='gudang_id_after' hidden>${gudang_id_after}</span>
            			<span class='barang_id' ${hidden}>${barang_id}</span>
            			<span class='status_aktif' ${hidden}>${status_aktif}</span>
            			<span class='warna_id' hidden>${warna_id}</span>${btn_edit}${btn_status}`;


			$('td:eq(0)', nRow).addClass('status_column');
            $('td:eq(1)', nRow).html("<span class='tanggal' hidden>"+date_formatter($('td:eq(1)', nRow).text())+"</span>"+date_formatter_month_name($('td:eq(1)', nRow).text()));
            $('td:eq(5)', nRow).html("<span class='qty'>"+$('td:eq(5)', nRow).text().replace('.00','')+"</span>");
            $('td:eq(6)', nRow).html("<span class='jumlah_roll'>"+$('td:eq(6)', nRow).text()+"</span>");
            <?if (is_posisi_id() <= 3) {?>
	            $('td:eq(8)', nRow).html(action);
            <?}else{?>
	            $('td:eq(7)', nRow).html(action);
        	<?}?>
            // $('td:eq(2)', nRow).html(btn_view);


        },
        "bStateSave" :true,
		"bProcessing": true,
		"bServerSide": true,
		"ordering":false,
		"sAjaxSource": baseurl + "inventory/data_mutasi?cond="+"<?=$cond?>"
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
					
					$.each(JSON.parse(data_respond),function(k,v){
						var qty = v.qty;
						// alert(v.qty);
						var roll = v.jumlah_roll;
						$('#data-qty').attr('data-content',qty);
						$('#data-roll').attr('data-content',roll);

						$('#data-qty-add').html(qty);
						$('#data-roll-add').html(roll);


						qty_global = qty;
						jumlah_roll_global = roll;

						$('#form_add_data [name=qty]').attr('placeholder','');
						$('#form_add_data [name=jumlah_roll]').attr('placeholder','');

						$('#form_add_data [name=qty]').attr('readonly',false);
						$('#form_add_data [name=jumlah_roll]').attr('readonly',false);
					});
		   		});
			};
		}else{
			$('#form_add_data [name=qty]').attr('readonly',true);
			$('#form_add_data [name=jumlah_roll]').attr('readonly',true);
		}
	});

	$('.btn-save').click(function(){
		if($('#form_add_data [name=tanggal]').val() != '' ){
			$('#form_add_data').submit();
			btn_disabled_load($(this));
		}else{
			bootbox.alert("Mohon isi tanggal & jumlah ");
		}
	});

//========================================edit data=================================================

	$('#general_table').on('click','.btn-edit',function(){

		var ini = $(this).closest('tr');
		var form = $('#form_edit_data');

		form.find('[name=mutasi_barang_id]').val(ini.find('.id').html());
		form.find('[name=gudang_id_before]').val(ini.find('.gudang_id_before').html());
		form.find('[name=gudang_id_after]').val(ini.find('.gudang_id_after').html());
		form.find('[name=tanggal]').val(ini.find('.tanggal').html());
		
		var barang_id = ini.find('.barang_id').html();
		var warna_id = ini.find('.warna_id').html();
		$("#barang_id_select2").val(barang_id).trigger('change');
		$("#warna_id_select2").val(warna_id).trigger('change');	

		var qty = ini.find('.qty-data').html().split(',');
		var roll = ini.find('.roll-data').html().split(',');
		var detail_id = ini.find('.detail_id-data').html().split(',');
		var rekap_qty= [];
		var newBaris = '';

		$.each(qty, function(i,v){
			rekap_qty[i] = v+'??'+roll[i]+'??'+detail_id[i];
			var subtotal = roll[i] * v;
			var btn = '';
			if (i == 0) {
				btn = `<button tabindex='-1' class='btn btn-xs blue btn-edit-qty-row'><i class='fa fa-plus'></i></button>`;
			};
			var hidden = "<?=(is_posisi_id() != 1 ? 'hidden' : '');?>";
			newBaris += `<tr>
								<td><input name='qty[]' class='qty ' value="${v}"></td>
								<td><input name='jumlah_roll[]' class='jumlah-roll text-center' value="${roll[i]}"></td>
								<td> = </td>
								<td><input tabindex='-1' disabled readonly class='subtotal ' value="${subtotal}"></td>
								<td>
									<input name='id[]' class='id' value='${detail_id[i]}' ${hidden} >
									${btn}
								</td>
							</tr>`;
		});

		$("#qty-table-edit tbody").html(newBaris);

		form.find('[name=rekap_qty]').val(rekap_qty.join('--'));
		data_result = table_qty_update('#qty-table-edit').split('=*=');

		let total = parseFloat(data_result[0]);
    	let total_roll = parseFloat(data_result[1]);
    	let rekap = data_result[2];

		if (total == 0) {
			$('.btn-edit-save').attr('disabled',true);
		}else{
			$('.btn-edit-save').attr('disabled',false);
		}


	});

	/*$('#form_edit_data [name=qty]').change(function(){
		// alert(jumlah_roll_global);
		var qty = parseInt($(this).val());
		var jumlah_roll = parseInt($('#form_edit_data [name=jumlah_roll]').val());
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
	});*/

	/*$('#form_edit_data [name=jumlah_roll]').change(function(){
		var jumlah_roll = parseInt($(this).val());
		var qty = parseInt($('#form_edit_data [name=qty]').val());
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
	});*/

	$('.btn-edit-save').click(function(){
		if($('#form_edit_data [name=tanggal]').val() != '' && $('#form_edit_data [name=qty]').val() != '' && $('#form_edit_data [name=qty]').val() != 0 && $('#form_edit_data [name=jumlah_roll]').val() != '' && $('#form_edit_data [name=jumlah_roll]').val() != 0){
			$('#form_edit_data').submit();
		}else{
			bootbox.alert("Mohon isi tanggal & jumlah ");
		}
	});

//========================================set data=================================================
	<?if ($barang_id_latest != '') {?>
		// alert("<?=$barang_id_latest?>");
		$("#portlet-config").modal('toggle');
		$('#barang_id_select').select2('val','<?=$barang_id_latest;?>');
		var gudang_before = "<?=$gudang_before_latest?>";
		var gudang_after = "<?=$gudang_after_latest?>";
		$('#form_add_data [name=gudang_id_before]').val(gudang_before);
		$('#form_add_data [name=gudang_id_after]').val(gudang_after);

	<?};?>

//==============================change qty-table==============================================
	
	$("#qty-table-edit").on('change','.qty, .jumlah-roll',function(){
    	data_result = table_qty_update('#qty-table-edit').split('=*=');
    	let total = parseFloat(data_result[0]);
    	let total_roll = parseFloat(data_result[1]);
    	let rekap = data_result[2];
    	
    	if (total == 0) {
			$('.btn-edit-save').attr('disabled',true);
		}else{
			$('.btn-edit-save').attr('disabled',false);
		}



    	$('.yard_total').html(total.toFixed(2));
    	$('.jumlah_roll_total').html(total_roll);
    	$('#form_edit_data [name=rekap_qty]').val(rekap);
    });


	$("#qty-table").on('change','.qty, .jumlah-roll',function(){
    	data_result = table_qty_update('#qty-table').split('=*=');
    	let total = parseFloat(data_result[0]);
    	let total_roll = parseFloat(data_result[1]);
    	let rekap = data_result[2];

    	var qty_stok = $("#data-qty-add").html();
    	var roll_stok = $("#data-roll-add").html();
    	
    	if (total == 0 || parseFloat(total) > parseFloat(qty_stok) ) {
			$('.btn-save').attr('disabled',true);
			if (parseFloat(total) > parseFloat(qty_stok)) {$("#data-qty-add").css('color','red')};
		}else{
			$('.btn-save').attr('disabled',false);
			$("#data-qty-add").css('color','black');
		}



    	$('.yard_total').html(total.toFixed(2));
    	$('.jumlah_roll_total').html(total_roll);
    	$('#form_add_data [name=rekap_qty]').val(rekap);
    });

    $('.btn-add-qty-row').click(function(){
    	addNewBaris("#qty-table");
    });

    $(document).on('click', '.btn-edit-qty-row', function(){
    	addNewBaris("#qty-table-edit");
    });

});

function addNewBaris(table){
	var newBaris = `<tr>
			<td><input name='qty[]' class='qty ' ></td>
			<td><input name='jumlah_roll[]' class='jumlah-roll text-center' ></td>
			<td> = </td>
			<td><input tabindex='-1' disabled readonly class='subtotal '></td>
			<td></td>
		</tr>`;
	$(table+" tbody").append(newBaris);

}


function table_qty_update(table){
	var total = 0; 
	var idx = 0; 
	var rekap = [];
	var total_roll = 0;
	$(table+" .qty").each(function(){
		var ini = $(this).closest('tr');
		var qty = $(this).val();
		var roll = ini.find('.jumlah-roll').val();
		var id = ini.find('.id').val();
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
		ini.find('.subtotal').val(qty*roll);

	});

	$(table+' .total-roll').html(total_roll);
	$(table+' .total-all').html(total);

	rekap_str = rekap.join('--');
	// console.log(total+'=*='+total_roll+'=*='+rekap_str);

	return total+'=*='+total_roll+'=*='+rekap_str;
}

</script>
