<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css'); ?>"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css'); ?>"/>
<style type="text/css">
.detail-warna tr td{
	padding-right:15px;
}

.detail-warna{
	padding: 0;
	margin: 0;
}

.detail-warna li{
	list-style-type: none;
	display: inline-block;
	margin-right:10px; 
	vertical-align: top;
	position: relative;
}

.qty-masuk-div{
	display:none; 
	position:absolute;
	background: #eee;
	padding: 5px;
	top: 0px;
	left: 60px;
	width: 150px;
}

</style>
<div class="page-content">
	<div class='container'>
		
		<div class="modal fade" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body">
						<form action="<?=base_url('transaction/ockh_non_po_store')?>" class="form-horizontal" id="form_add_data" method="post">
							<h3 class='block'> Tambah </h3>
								<div class="form-group">
				                    <label class="control-label col-md-3">Nama Supplier<span class="required">
				                    * </span>
				                    </label>
				                    <div class="col-md-6">
				                    	<select class="form-control" name='supplier_id' style="font-weight:bold" >
				                    		<?foreach ($this->supplier_list_aktif as $row) {?>
				                    			<option value="<?=$row->id?>" <?=($row->id==1 ? 'selected' : '');?> ><?=$row->nama?></option>
				                    		<?}?>
				                    	</select>
				                    </div>				                    
				                </div>

				                <div class="form-group">
				                    <label class="control-label col-md-3">Tanggal<span class="required">
				                    * </span>
				                    </label>
				                    <div class="col-md-6">
				                    	<input type="text" autocomplete='off' class="form-control date-picker" name="tanggal" value="<?=date('d/m/Y')?>"  />
				                    </div>				                    
				                </div>

				                <div class="form-group">
				                    <label class="control-label col-md-3">OCKH<span class="required">
				                    * </span>
				                    </label>
				                    <div class="col-md-6">
				                    	<input type="text" class="form-control" name="ockh" id="ockh-new" />
				                    </div>				                    
				                </div>

				                <div class="form-group">
				                    <label class="control-label col-md-3">Barang<span class="required">
				                    * </span>
				                    </label>
				                    <div class="col-md-6">
				                    	<select class="form-control" name='barang_id' id='barang_id_select'>
				                    		<option value=''>Pilih</option>
				                    		<?foreach ($this->barang_list_aktif as $row) {?>
				                    			<option value="<?=$row->id?>"><?=$row->nama?></option>
				                    		<?}?>
				                    	</select>
				                    </div>				                    
				                </div>

				                <hr/>
				                <div class='text-center'>
					                <table class='table' id='warna-add-table'>
					                	<thead>
						                	<tr>
						                		<th style='text-align:center'>Warna</th>
						                		<th>Qty</th>
						                		<th></th>
						                	</tr>
					                	</thead>
					                	<tbody>
						                	<tr>
						                		<td class='text-center'>
					                				<select name='warna_id[]' style="width:200px" class='warna_id_select first-color'>
						                    			<option value="">Pilih</option>
							                    		<?foreach ($this->warna_list_aktif as $row) {?>
							                    			<option value="<?=$row->id?>"><?=$row->warna_beli?></option>
							                    		<?}?>
							                    	</select>
						                		</td>
						                		<td style='text-align:left'>
							                    	<input type="text" class='amount_number' name="qty[]"/>
						                		</td>
						                		<td>
							                    	<input type="text" class='amount_number' name="ockh_warna_id[]" value="-" hidden />
									                <button type='button' class='btn btn-xs blue btn-add-warna'><i class="fa fa-plus"></i></button>
						                		</td>
						                	</tr>
					                	</tbody>
					                </table>

				                </div>

						</form>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn blue btn-active btn-save">Save</button>
						<button type="button" class="btn default" data-dismiss="modal">Close</button>
					</div>
				</div>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
		</div>

		<div class="modal fade" id="portlet-config-edit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body">
						<form action="<?=base_url('transaction/ockh_non_po_store')?>" class="form-horizontal" id="form_edit_data" method="post">
							<h3 class='block'> Edit </h3>

								<div class="form-group">
				                    <label class="control-label col-md-3">Nama Supplier<span class="required">
				                    * </span>
				                    </label>
				                    <div class="col-md-6">
				                    	<select class="form-control" name='supplier_id' style="font-weight:bold">
				                    		<option value=''>Pilih</option>
				                    		<?foreach ($this->supplier_list_aktif as $row) {?>
				                    			<option value="<?=$row->id?>"><?=$row->nama?></option>
				                    		<?}?>
				                    	</select>
				                    </div>				                    
				                </div>

				                <div class="form-group">
				                    <label class="control-label col-md-3">Tanggal<span class="required">
				                    * </span>
				                    </label>
				                    <div class="col-md-6">
				                    	<input name="id" <?=(is_posisi_id() != 1 ? 'hidden' : '' )?> />
				                    	<input type="text" autocomplete='off' class="form-control date-picker" name="tanggal" />
				                    </div>				                    
				                </div>

				                <div class="form-group">
				                    <label class="control-label col-md-3">OCKH<span class="required">
				                    * </span>
				                    </label>
				                    <div class="col-md-6">
				                    	<input type="text" class="form-control" name="ockh" id="ockh-edit" />
				                    </div>				                    
				                </div>

				                <div class="form-group">
				                    <label class="control-label col-md-3">Barang<span class="required">
				                    * </span>
				                    </label>
				                    <div class="col-md-6">
				                    	<select class="form-control" name='barang_id' id='barang_id_select_edit'>
				                    		<option value=''>Pilih</option>
				                    		<?foreach ($this->barang_list_aktif as $row) {?>
				                    			<option value="<?=$row->id?>"><?=$row->nama?></option>
				                    		<?}?>
				                    	</select>
				                    </div>				                    
				                </div>

				                <hr/>
				                <div class='text-center'>
					                <table class='table' id='warna-edit-table'>
					                	<thead>
						                	<tr>
						                		<th style='text-align:center'>Warna</th>
						                		<th>Qty</th>
						                		<th></th>
						                	</tr>
					                	</thead>
					                	<tbody></tbody>
					                </table>
					            </div>

				                
						</form>
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
							<!-- <select class='btn btn-sm btn-default' name='status_aktif_select' id='status_aktif_select'>
								<option value="1" selected>Aktif</option>
								<option value="0">Tidak Aktif</option>
							</select> -->
							<a href="#portlet-config" data-toggle='modal' class="btn btn-default btn-sm btn-form-add">
							<i class="fa fa-plus"></i> Tambah </a>
						</div>
					</div>
					<div class="portlet-body">
						<table style='font-size:1.2em'>
							<tr hidden>
								<td>Tanggal</td>
								<td> : </td>
								<td><input name='tanggal' value="<?=date("d/m/Y");?>"></td>
							</tr>
							<tr>
								<td>Supplier</td>
								<td> : </td>
								<td>
									<select name="supplier_list"  id="supplier-list">
										<?foreach ($this->supplier_list_aktif as $row) {?>
											<option value="<?=$row->id;?>" <?=($supplier_id == $row->id ? 'selected' : '' );?>  ><?=$row->nama;?></option>
										<?}?>
									</select>
								</td>
							</tr>
							<tr>
								<td>OCKH</td>
								<td> : </td>
								<td><input name='ockh_filter' id="ockh-filter"></td>
							</tr>
						</table>
						<hr/>
						<table class="table table-striped table-hover" id="general_table">
							<thead>
								<tr>
									<th scope="col">
										Tanggal
									</th>
									<th scope="col" hidden>
										Supplier
									</th>
									<th scope="col">
										OCKH
									</th>
									<th scope="col">
										Barang
									</th>
									<th scope="col">
										Warna
									</th>
									<th scope="col">
										TOTAL
									</th>
									<th scope="col" style="min-width:150px !important">
										Actions
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


<script src="<?php echo base_url('assets/global/plugins/datatables/media/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets_noondev/js/table-advanced.js'); ?>"></script>
<script src="<?php echo base_url('assets/global/plugins/bootbox/bootbox.min.js'); ?>" type="text/javascript"></script>

<script>
jQuery(document).ready(function() {       

	// TableAdvanced.init();
	redraw_table_data();

	var main_table =$('#general_table'); 
	
	$('#barang_id_select, #barang_id_select_edit, .warna_id_select').select2();
   
   	main_table.on('click', '.btn-edit', function(){
   		var form = $("#form_edit_data");
   		var ini = $(this).closest('tr');
   		form.find('[name=id]').val(ini.find('.id').html());
   		form.find('[name=tanggal]').val(ini.find('.tanggal').html());
   		form.find('[name=supplier_id]').val(ini.find('.supplier_id').html());
   		form.find('[name=ockh]').val(ini.find('.ockh').html());
   		form.find('[name=barang_id]').val(ini.find('.barang_id').html());
   		form.find('[name=barang_id]').change();

		$("#warna-edit-table tbody").html("");

   		var nama_warna = ini.find(".nama_warna").html();
   		var warna_id = ini.find(".warna_id").html();
   		var qty = ini.find(".qty").html();
   		var ockh_warna_id = ini.find(".ockh_warna_id").html();
   		if (nama_warna != '') {
   			var nama_warna_data = nama_warna.split(',');
	   		var warna_id_data = warna_id.split(',');
	   		var qty_data = qty.split(',');
	   		var ockh_warna_id_data = ockh_warna_id.split(',');
			$(".warna_id_select").select2("destroy");

	   		$.each(nama_warna_data, function(i,v){
	   			// console.log(v);
	   			if (i == 0) {
	   				var first_color = "first-color";
	   				var btn = `<button type='button' class='btn btn-xs blue btn-add-warna'><i class="fa fa-plus"></i></button>`;
	   			}else{
	   				var first_color = "";
	   				var btn = `<button type='button' class='btn btn-xs red btn-remove-warna'><i class="fa fa-times"></i></button>`;
	   			}
	   			var new_row = `<tr>
		    		<td>
						<select name='warna_id[]' style="width:200px" class='warna_id_select ${first_color}'>
		        			<option value="">Pilih</option>
		            		<?foreach ($this->warna_list_aktif as $row) {?>
		            			<option value="<?=$row->id?>"><?=$row->warna_beli?></option>
		            		<?}?>
		            	</select>
		    		</td>
		    		<td style='text-align:left'><input type="text" class='amount_number' name="qty[]" value="${change_number_format(qty_data[i])}" /></td>
		    		<td>
					    <input name="ockh_warna_id[]" value="${ockh_warna_id_data[i]}" hidden />
						${btn}
		    		</td>
		    	</tr>`;
		    	// console.log(new_row);
	   			$("#warna-edit-table tbody").append(new_row);
	   		});
			
			var rows = $('tr', "#warna-edit-table");

			$.each(warna_id_data, function(i,v){
				rows.eq(i+1).find('.warna_id_select').val($.trim(v));
				// console.log(rows.eq(i).html());
			});

	   		setTimeout(function(){
				$(".warna_id_select").select2({width:'200px'});
	       	},500);
   		};
   	});

   	$('.btn-save').click(function(){
   		var form = '#form_add_data';
   		if( $(form+' [name=ockh]').val() != '' && $(form+' [name=supplier_id]').val() != '' && $(form+' [name=barang_id]').val() != '' && $(form+' .first-color :selected').val() != '' ){
   			var ockh = $(form+' [name=ockh]').val();
   			if(cek_table_warna("#warna-add-table")){
				$(form).submit();
				btn_disabled_load($(this));
			}; 
   		}else{
   			// alert($(".first-color :selected").val());
   			alert("1. Mohon isi lengkap data  \n2. Mohon isi warna pada baris pertama ");
   		}
   	});

   	$('.btn-edit-save').click(function(){
   		var form = '#form_edit_data';
   		if( $(form+' [name=ockh]').val() != '' && $(form+' [name=supplier_id]').val() != '' && $(form+' [name=barang_id]').val() != '' && $(form+' .first-color :selected').val() != '' && typeof $(form+' .first-color :selected').val() !== 'undefined' ){
   			// alert($(form+' .first-color :selected').val());
			if(cek_table_warna("#warna-edit-table")){
				$(form).submit();
				btn_disabled_load($(this));
			};   			
   			// $(form).submit();
   			// btn_disabled_load($(this));
   		}else{
   			alert("1. Mohon isi lengkap data  \n2. Mohon isi warna pada baris pertama ");
   		}
   	});

   	$("#warna-add-table, #warna-edit-table").on('click', '.btn-add-warna', function(){
   		var ini = $(this);
   		var table = $(this).closest("tbody");
   		var new_row = generate_new_row();

       	table.append(new_row);
		$(".warna_id_select").select2("destroy");
       	setTimeout(function(){
			$(".warna_id_select").select2({width:'200px'});
       	},500);

   	});

   	$(document).on("click",'.btn-remove-warna',function(){
   		$(this).closest('tr').remove();
   	});

//=============================================================================

	$("#ockh-filter, #supplier-list").change(function(){
		redraw_table_data();
	});	

	$("#general_table").on("click",'.btn-remove-all', function(){
		var ini = $(this);
		var ockh_non_po_id = ini.closest("tr").find(".id").html();
		bootbox.confirm("<span style='font-size:1.2em'>Yakin untuk menghapus data <b>ockh</b> ini ?</span> ", function(respond){
			if (respond) {
				var data_st = {};
				data_st['id'] = ockh_non_po_id;
				var url = "transaction/ockh_non_po_remove";
				ajax_data_sync(url,data_st).done(function(data_respond  /*,textStatus, jqXHR*/ ){
					if(data_respond == 'OK'){
						ini.closest("tr").remove();
						notific8("lime","Data OCKH Non PO berhasil di hapus");
					}
				});
			};
		});
	});

	$(document).on('click','.btn-view-masuk', function(){
		var data_toggle = $(this).attr('data-toggle');
		$("#"+data_toggle).toggle('200');
	});

	$("#ockh-new").change(function(){
		ockh_checker($(this),'');
	});
	$("#ockh-edit").change(function(){
		ockh_checker($(this), $("#form_edit_data [name=id]").val() );
	});

});

function ockh_checker(ini, id){
	var data_st = {};
	data_st['id'] = id;
	data_st['ockh'] = ini.val();
	var url = "transaction/cek_ockh_registered";
	ajax_data_sync(url,data_st).done(function(data_respond  /*,textStatus, jqXHR*/ ){
		if (data_respond > 0) {
			alert("OCKH sudah terdaftar");
			ini.val('');
		};
	});
}

function cek_table_warna(table){
	var result = true;
	$(table+" .warna_id_select").each(function(){
		// console.log($(this).val());
		var ini = $(this);
		console.log(ini.closest('tr').find(".warna_id_select :selected").val());
		if (ini.closest('tr').find(".warna_id_select :selected").val() == '' || typeof ini.closest('tr').find(".warna_id_select :selected").val() == 'undefined' || ini.closest('tr').find("[name='qty[]']").val() == '' || ini.closest('tr').find("[name='qty[]']").val() == '0' ) {
			// console.log($(this)[0]);
			// console.log("======");
			// console.log(ini.closest('tr').find(".warna_id_select :selected").val() +'&&'+ ini.closest('tr').find("[name='qty[]']").val());
			// console.log(ini.val());
			// console.log(ini.closest('tr').find("[name='qty[]']").val());
			alert("Nama Warna & Qty Mohon diisi semua ");
			result = false;
			return false;
		}
	});

	return result;
}

function generate_new_row(){
	var new_row = `<tr>
    		<td>
				<select name='warna_id[]' style="width:200px" class='warna_id_select'>
        			<option value="">Pilih</option>
            		<?foreach ($this->warna_list_aktif as $row) {?>
            			<option value="<?=$row->id?>"><?=$row->warna_beli?></option>
            		<?}?>
            	</select>
    		</td>
    		<td style='text-align:left'>
            	<input type="text" class='amount_number' name="qty[]"/>
    		</td>
    		<td>
			    <input name="ockh_warna_id[]" value="-" hidden />
				<button type='button' class='btn btn-xs red btn-remove-warna'><i class="fa fa-times"></i></button>
    		</td>
    	</tr>`;

    	return new_row;
}

function redraw_table_data(){
	$("#general_table tbody").html("");

	var data_st = {};
	data_st['ockh'] = $("#ockh-filter").val();
	data_st['supplier_id'] = $("#supplier-list").val();
	var url = "transaction/get_ockh_non_po_ajax";
	ajax_data_sync(url,data_st).done(function(data_respond  /*,textStatus, jqXHR*/ ){
		if(data_respond){
			$.each(JSON.parse(data_respond),function(k,v){
				var table_warna = '';
				var col = 10;
				var total_qty = 0;
				if (v.nama_warna != '' && v.nama_warna != null) {
					// console.log(v.nama_warna);
					var nama_warna_dt = v.nama_warna.split(',');
					var warna_id_dt = v.warna_id.split(',');
					var qty_dt = v.qty.split(',');
					var ockh_warna_id_dt = v.ockh_warna_id.split(',');

					//data barang masuk
					var qty_masuk_data = v.qty_masuk.split(',');
					var harga_masuk_data = v.harga_masuk.split(',');
					var tanggal_masuk_data = v.tanggal_masuk.split(',');
					var warna_id_masuk = v.warna_id_masuk.split(',');
					var qty_masuk_jml_data = v.qty_masuk_jml.split(',');
					

					var table_warna = `<ul class='detail-warna'>`;
					// alert(Math.ceil(warna_id_dt.length/col));
					for (var i=0; i < Math.ceil(warna_id_dt.length/col) ; i++) {
						// table_warna += `<li>`;
							for (var j=0; j < col ; j++) {
								var key = parseInt((i*col)) + parseInt(j); 
								if (typeof(nama_warna_dt[key]) !== 'undefined' ) {
									var qty_masuk = qty_masuk_data[key].split('??');
									var harga_masuk = harga_masuk_data[key].split('??');
									var tanggal_masuk = tanggal_masuk_data[key].split('??');
									$.each(qty_masuk, function(i2,v2){
										qty_masuk[i2] = change_number_format(parseFloat(v2));
									});
									
									table_warna += `<li>`;
									total_qty += parseInt(qty_dt[key]);
									table_warna += `<b><u>${nama_warna_dt[key]}</u></b><br/>`;
									table_warna += `(${change_number_format(qty_dt[key])})`;
									if (parseFloat(qty_masuk_jml_data[key]) != 0) {
										table_warna += `<a class='btn-view-masuk' data-toggle="${v.id}-${warna_id_masuk[j]}"  ><i class='fa fa-info-circle'></i></a>`;
									};
									if (qty_masuk_data[i] != '' && warna_id_masuk[key] == warna_id_dt[key]  ) {
										table_warna += `<div id="${v.id}-${warna_id_masuk[j]}" class='qty-masuk-div'>Qty Masuk : <br/>`;
										table_warna += `<div style='border-bottom:1px solid #ddd'>
											${qty_masuk.join('<br/>')}
											</div>`;
										table_warna += `<b>SISA : ${qty_dt[key] - qty_masuk_jml_data[key]}<b/></div>`;
									};
									table_warna += ``;
									table_warna += `</li>`;
								}
							}
						// table_warna += `</li>`;
					}
					table_warna += `</ul>`;
					// alert(table_warna);

				};

				var hidden = "<?=(is_posisi_id() != 1 ? 'hidden' : '' );?>";

				var baris_general_table= `<tr>
					<td class='tanggal'>${v.tanggal}</td>
					<td hidden>${v.nama_supplier}</td>
					<td class='ockh'>${v.ockh}</td>
					<td><b style='font-size:1.2em'>${v.nama_barang}</b> </td>
					<td>
						${table_warna}
					</td>
					<td>
						<b style='font-size:1.2em'>${change_number_format(total_qty)} ${v.nama_satuan}</b>
					</td>
					<td>
						<span class="ockh_warna_id" ${hidden} >${v.ockh_warna_id}</span>
						<span class="warna_id"> ${hidden} ${v.warna_id}</span>
						<span class="nama_warna" ${hidden} >${v.nama_warna}</span>
						<span class="qty" ${hidden} >${v.qty}</span>
						<span class='id' ${hidden} >${v.id}</span>
						<span class='supplier_id' ${hidden} >${v.supplier_id}</span>
						<span class='barang_id' ${hidden} >${v.barang_id}</span>
						<a href='#portlet-config-edit' data-toggle='modal' class='btn btn-xs green btn-edit'><i class='fa fa-edit'></i></a>
						<button class='btn btn-xs red btn-remove-all'><i class='fa fa-times'></i></button>
					</td>
				</tr>`;

				table_warna = '';
				// console.log(table_warna);

				$("#general_table tbody").append(baris_general_table);
			});
		}else{
			var empty_baris = "<tr><td colspan='7' class='text-center'> Tidak ada data </td></tr>";
			$("#general_table tbody").append(empty_baris);
		}
		// query.callback(data);
	});
};
</script>
