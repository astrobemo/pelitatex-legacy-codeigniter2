<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css'); ?>"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css'); ?>"/>
<link href="<?=base_url('assets/global/plugins/bootstrap-modal/css/bootstrap-modal.css');?>" rel="stylesheet" type="text/css"/>



<style type="text/css">


</style>
<div class="page-content">
	<div class='container'>

		<div id="pembelian-modal" class="modal fade" style='width:100%' tabindex="-1">
		</div>

		<div class="modal fade" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body">
						<form action="<?=base_url('transaction/pembelian_list_insert')?>" class="form-horizontal" id="form_add_data" method="post">
							<h3 class='block'> Pembelian Baru</h3>

							<div class="form-group">
			                    <label class="control-label col-md-3">PO
			                    </label>
			                    <div class="col-md-6">
			                		<select name='po_pembelian_batch_id' class='form-control' id="po_list">
			                			<option value=''>Pilih</option>
			                			<?foreach ($this->po_pembelian_batch_aktif as $row) {?>
			                				<option value="<?=$row->id?>"><?=$row->po_number?></option>
			                			<?}?>
			                		</select>

			                		<select id="po_list_copy" hidden>
			                			<option value=''>Pilih</option>
			                			<?foreach ($this->po_pembelian_batch_aktif as $row) {?>
			                				<option value="<?=$row->id?>"><?=$row->supplier_id?></option>
			                			<?}?>
			                		</select>
			                    </div>
			                </div>

							<div class="form-group">
			                    <label class="control-label col-md-3">Supplier<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<select class='input1 form-control supplier-input' style='font-weight:bold' name="supplier_id" id='supplier-id-add'>
			                    		<?foreach ($this->supplier_list_aktif as $row) { ?>
			                    			<option value="<?=$row->id?>"><?=$row->nama;?></option>
			                    		<? } ?>
			                    	</select>
			                    </div>
			                </div>

			                <div class="form-group">
			                    <label class="control-label col-md-3">Gudang<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
	                    			<select style='font-weight:bold' class='form-control gudang-input' name="gudang_id" id='gudang_add_data'>
	                    				<option value="">Pilih</option>
			                    		<?foreach ($this->gudang_list_aktif as $row) { ?>
			                    			<option <?=($row->status_default==1 ? 'selected' : '');?> value="<?=$row->id?>"><?=$row->nama;?></option>
			                    		<? } ?>
			                    	</select>
			                    </div>
			                </div> 

			                <div class="form-group">
			                    <label class="control-label col-md-3">No Faktur<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                		<input type="text" class="form-control" name="no_faktur" id="no-faktur" />
			                    </div>
			                </div>

			                <div class="form-group">
			                    <label class="control-label col-md-3">Tanggal<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
					                <input type="text" readonly class="form-control date-picker" value="<?=date('d/m/Y');?>" name="tanggal"/>
			                    </div>
			                </div>

			                <div class="form-group">
			                    <label class="control-label col-md-3">OCKH
			                    </label>
			                    <div class="col-md-6">
			                		<input type="text" class='form-control' name="ockh_info" id="ockh" />
			                    	<small>Terdaftar: <b><span class='ockh-history'></span></b></small><br/>
			                    	<small class='po-info' style="display:none">PO link: <b><span class='po-link-number' style="color:red" ></span></b></small>
			                    </div>
			                </div> 	

			                <div class="form-group" hidden>
			                    <label class="control-label col-md-3">Toko
			                    </label>
			                    <div class="col-md-6">
					                <select name="toko_id" class='form-control'>
			                    		<?foreach ($this->toko_list_aktif as $row) { ?>
			                    			<option selected value="<?=$row->id?>"><?=$row->nama;?></option>
			                    		<? } ?>
			                    	</select> 
			                    </div>
			                </div>
						</form>
					</div>

					<div class="modal-footer">
						<button type="button" class="btn blue btn-active btn-trigger btn-save">Save</button>
						<button type="button" class="btn default  btn-active" data-dismiss="modal">Close</button>
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
							<select class='btn btn-sm btn-default' name='status_select' id='status_select'>
								<option value="" selected>All</option>
								<option value="1">Aktif</option>
								<!-- <option value="0">Tidak Aktif</option> -->
								<option value="0">Batal</option>

							</select>

							<!-- <a href="#portlet-config" data-toggle='modal' class="btn btn-default btn-sm btn-form-add">
							<i class="fa fa-plus"></i> Tambah </a> -->
						</div>
					</div>
					<div class="portlet-body">
						Tanggal : 
						<input type="text" id="tanggalStartInput" name="tanggal_start" value="<?=($tanggal_start)?>" style="width:100px" class="text-center date-picker" > s/d
						<input type="text" id="tanggalEndInput" name="tanggal_end" value="<?=($tanggal_end)?>" style="width:100px" class="text-center date-picker">
						<button disabled class="btn btn-primary btn-sm" id="btnFilter">Filter</button>
						<hr>
						<table class="table table-hover table-striped table-bordered" id="general_table">
							<thead>
								<tr>
									<th scope="col" class='status_column'>
										Status Aktif
									</th>
									<th scope="col">
										Toko
									</th>
									<th scope="col">
										No Faktur
									</th>
									<th scope="col">
										OCKH
									</th>
									<th scope="col">
										Tanggal
									</th>
									<!-- <th scope="col">
										Satuan
									</th> -->
									<!-- <th scope="col">
										Yard/KG
									</th>
									<th scope="col">
										Jml Roll
									</th>
									<th scope="col">
										Nama Barang
									</th> -->
									<th scope="col">
										Gudang
									</th>
									<!-- <th scope="col">
										Harga
									</th> -->
									<th scope="col">
										Total
									</th>
									<th scope="col">
										Supplier
									</th>
									<th scope="col">
										Status
									</th>
									<th scope="col" >
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

<script src="<?php echo base_url('assets/global/plugins/bootstrap-modal/js/bootstrap-modalmanager.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/bootstrap-modal/js/bootstrap-modal.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/jquery-validation/js/jquery.validate.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/datatables/media/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets_noondev/js/table-advanced.js'); ?>"></script>
<script src="<?php echo base_url('assets/global/plugins/bootbox/bootbox.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets_noondev/js/form-pembelian.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets_noondev/js/fnReload.js');?>"></script>


<script src="<?php echo base_url('assets/global/plugins/select2/select2.min.js'); ?>" type="text/javascript" ></script>
<script src="<?php echo base_url('assets_noondev/js/ui-extended-modals.js'); ?>"></script>
<script>

	let tanggal_start = '<?=$tanggal_start?>';
	let tanggal_end = '<?=$tanggal_end?>';
jQuery(document).ready(function() {
	// Metronic.init(); // init metronic core components
	// Layout.init(); // init current layout
	// TableAdvanced.init();
	FormNewPembelian.init();
	ModalsPembelianEdit.init();
	$('.barang-id, .warna-id, #po_list').select2({
        allowClear: true
    });

	// oTable = $('#general_table').DataTable();
	// oTable.state.clear();
	// oTable.destroy();
	var color_list = [];
	var kode_supplier = [];
   	<?foreach (get_color_list_all() as $key => $value) {?>
   		color_list["<?=$key;?>"] = "<?=$value;?>";
   	<?}?>;
   	console.log(color_list);
   	<?foreach ($this->supplier_list_aktif as $row) {?>
   		kode_supplier["<?=$row->id;?>"] = "<?=$row->kode;?>";
   	<?}?>;
   	// console.log(color_list)

	$("#general_table").DataTable({
   		"fnCreatedRow": function( nRow, aData, iDataIndex ) {
            var status = $('td:eq(9)', nRow).text().split('??');
            var id = status[0];
            var toko_id = status[1];            
            var gudang_id = status[2];
            var supplier_id = status[3];
            var kode = status[4];
            var tanggal = date_formatter($('td:eq(4)', nRow).html());
            var total_data = $('td:eq(6)', nRow).text().split('??');
           	var total = 0;

            if ($('td:eq(5)', nRow).text() != '') {
	            $.each(total_data, function(i,v){
	            	total += parseInt(v);
	            });
            }else{
            	total = 0;
            }
            
        	var total = change_number_format(total.toString().replace('.00',''));
            var url = "<?=base_url().rtrim(base64_encode('transaction/pembelian_list_detail'),'=');?>/"+id;
            var url_print = "<?=base_url();?>transaction/pembelian_print?pembelian_id="+id;
            var button_edit = "<a href='"+url+"' class='btn-xs btn green btn-edit'><i class='fa fa-edit'></i> </a>";
            var button_view = '<a href="'+url_print+'" class="btn-xs btn blue btn-print" onclick="window.open(this.href, \'newwindow\', \'width=1250, height=650\'); return false;"><i class="fa fa-print"></i> </a>';
           	var button_remove = '';

           	var posisi_id = "<?=is_posisi_id();?>"
           	if (posisi_id != 6) {
           		var status_aktif = $('td:eq(0)', nRow).text();
           		if (status_aktif == 1) {
		           	button_remove = "<a class='btn-xs btn red btn-remove'><i class='fa fa-times'></i> </a>";
           		}else{
		           	button_remove = "<a class='btn-xs btn blue btn-activate'><i class='fa fa-play'></i> </a>";
           		}
           	};

           	var status_ket = $('td:eq(8)', nRow).text();
           	if (status_ket < 0) {
           		var status = "<span style='color:red'>belum lunas</span>";
           	}else{
           		var status = "<span style='color:blue'>lunas</span>";
           	}

           	if (status_aktif == 0) {
           		var status = "BATAL";
           	};
           	
           	var action = "<span class='id' hidden='hidden'>"+id+"</span><span class='toko_id' hidden='hidden'>"+toko_id+"</span><span class='gudang_id' hidden='hidden'>"+gudang_id+"</span><span class='supplier_id' hidden='hidden'>"+supplier_id+"</span>"+button_edit+button_remove + button_view;

            $('td:eq(0)', nRow).addClass('status_column');
            $('td:eq(1)', nRow).html('<span class="toko_id">'+$('td:eq(1)', nRow).text()+'</span>');
            $('td:eq(2)', nRow).html('<span class="no_faktur">'+$('td:eq(2)', nRow).text()+'</span>');
            $('td:eq(4)', nRow).html(tanggal);
            
            $('td:eq(6)', nRow).html('<span class="total">'+total+'</span>');
            if (total == 0 || total == '') {
	            $('td:eq(6)', nRow).addClass('caution');
            };
            
            $('td:eq(8)', nRow).html(status);
            $('td:eq(9)', nRow).html(action);
            $('td', nRow).css("background",color_list[kode]);


            
        },
        "bStateSave" :true,
		"bProcessing": true,
		"bServerSide": true,
		"sAjaxSource": baseurl + `transaction/data_pembelian_slim?tanggal_start=${tanggal_start}&tanggal_end=${tanggal_end}`,
		"order":[[2, 'desc']]

	});

	var oTable;
    oTable = $('#general_table').dataTable();
    oTable.fnFilter( '', 0 );

	$(`#tanggalStartInput`).change(function(){
		tanggal_start = $(this).val();
		tanggal_end = $('#tanggalEndInput').val();
		cekDateInput(tanggal_start, tanggal_end);
	});

	$(`#tanggalEndInput`).change(function(){
		tanggal_start = $('#tanggalStartInput').val();
		tanggal_end = $(this).val();
		cekDateInput(tanggal_start, tanggal_end);
	});

	function cekDateInput(tgl_start, tgl_end){
		if (tgl_start == '' || tgl_end == '') {
			$(`#btnFilter`).prop('disabled',true);
		}else{
			$(`#btnFilter`).prop('disabled',false);
		}
	}

	$("#btnFilter").click(function(){
		var tanggal_start = $('#tanggalStartInput').val();
		var tanggal_end = $('#tanggalEndInput').val();
		// alert('OK');
		
		oTable.fnReloadAjax(baseurl + `transaction/data_pembelian_slim?tanggal_start=${tanggal_start}&tanggal_end=${tanggal_end}`);
		$(`#btnFilter`).prop('disabled',true);

		// notific8("lime", "Data Updated", 10000);
		
	});

	$('#status_select').change(function(){
		oTable.fnFilter( $(this).val(), 0 ); 
	});

	

   	$('.btn-edit-save').click(function(){
   		if( $('#form_edit_data [name=nama]').val() != ''){
   			$('#form_edit_data').submit();
   		}
   	});

   	$('#general_table').on('click','.btn-remove', function(){
		var ini = $(this).closest('tr');
		bootbox.confirm("Yakin <b style='color:red'>MEMBATALKAN</b> Pembelian ini?", function(respond){
			if (respond) {
				var id = ini.find('.id').html();
				window.location.replace(baseurl+'transaction/pembelian_list_batal?id='+id);


			};
		});
	}) ;  

	$('#general_table').on('click','.btn-activate', function(){
		var ini = $(this).closest('tr');
		bootbox.confirm("Yakin <b style='color:blue'>MENGAKTIVASI</b> kembali Pembelian ini?", function(respond){
			if (respond) {
				var id = ini.find('.id').html();
				window.location.replace(baseurl+'transaction/pembelian_list_undo_batal?id='+id);


			};
		});
	}) ;  

	$(".supplier-input").change(function(){
		// var kode = kode_supplier[$(this).val()];
		// $("#portlet-config .modal-body").css("background-color",color_list[kode]);
	});

	// $(".btn-form-add").click(function(){
	// 	get_po_list($(".supplier-input"));
	// });

	$("#form_add_data [name=po_pembelian_batch_id]").change(function(){
		if ($(this).val() != '') {
			get_po_data($(this).val());
		};
	});

	$("#po_list").change(function(){
		if ($(this).val() != '' ) {
			$('#ockh').val('');
			$('#ockh').prop('disabled',true);
		}else{
			$('#ockh').prop('disabled',false);
		}
	});

	$("#ockh").on('input',function(){
		let data = {};
		if ($(this).val().length >= 2) {
			data['ockh_input'] = $(this).val();
			var url = "transaction/get_ockh_suggestion";
			var list = [];
			ajax_data_sync(url,data).done(function(data_respond  /*,textStatus, jqXHR*/ ){
				$.each(JSON.parse(data_respond), function(i,v){
					// console.log(v);
					list[i] = v.ockh;
				});
				$(".ockh-history").html(list.join(", "));
			});
			
		};
	});

	$("#ockh").change(function(){
		var data = {};
		data['ockh'] = $(this).val();
		var url = "transaction/get_po_batch_by_ockh";
		var po_number = '';
		ajax_data_sync(url,data).done(function(data_respond  /*,textStatus, jqXHR*/ ){
			$.each(JSON.parse(data_respond), function(i,v){
				po_number = v.po_number;
			});	
			if (po_number != '') {
				$(".po-link-number").html(po_number);
				$(".po-info").show();
			}else{
				$(".po-info").hide();
			}
		});
	});

	var faktur_inisial = [];
	<?foreach ($this->supplier_list_aktif as $row) {?>
		faktur_inisial["<?=$row->id?>"] = "<?=$row->faktur_inisial?>";
	<?}?>
	$("#no-faktur").change(function(){
		let faktur = $(this).val();
		var inisial = faktur.substring(0,1);
		var supplier_id = $(".supplier-input").val();
			// alert(faktur_inisial[supplier_id] +"!="+ inisial);
		if (faktur_inisial[supplier_id] != inisial && faktur_inisial[supplier_id] != '' && inisial != '') {
			bootbox.alert("Untuk supplier ini umumnya diawali huruf <b>"+faktur_inisial[supplier_id]+"</b>");
		};

	});

});

function get_po_list(ini){
	let data = {};
	data['supplier_id'] = ini.val();
	let url = 'transaction/get_po_pembelian_by_supplier';
	$('#po_list').empty().trigger('change');
	ajax_data_sync(url,data).done(function(data_respond  /*,textStatus, jqXHR*/ ){
		// $('#po_list').select2("val","");
		var newOpt = new Option("Non PO", "", true, false);
		$("#po_list").append(newOpt).trigger('change');

		$.each(JSON.parse(data_respond), function(i,v){
			console.log(data_respond);
			var newOpt = new Option(v.po_number, v.id, false, false);
			$("#po_list").append(newOpt).trigger('change');
			// $('#po_list').select2('data',{value:v.id, text:v.tanggal});
			// $("#po_list").append($('<option>',{
			// 	value: v.id,
			// 	text: v.tanggal+'/'+v.po_number
			// }));
		})
	});
}

function get_po_data(po_pembelian_batch_id){
	let form = $('#form_add_data');
	let data = {};
	data['po_pembelian_batch_id'] = po_pembelian_batch_id;
	let url = 'transaction/get_po_pembelian_data';
	ajax_data_sync(url,data).done(function(data_respond  /*,textStatus, jqXHR*/ ){
		$.each(JSON.parse(data_respond), function(i,v){
			console.log(data_respond);
			let supplier_id = v.supplier_id;
			let ockh = v.ockh;

			form.find('[name=supplier_id]').val(supplier_id);
			form.find('[name=ockh]').val(ockh);
		});

		$('#gudang_add_data').select2('open');
	});
}
</script>
