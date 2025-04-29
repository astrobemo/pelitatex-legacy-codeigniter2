<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css'); ?>"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css'); ?>"/>
<link href="<?=base_url('assets/global/plugins/bootstrap-modal/css/bootstrap-modal.css');?>" rel="stylesheet" type="text/css"/>

<?=link_tag('assets/global/plugins/select2/select2.css'); ?>


<style type="text/css">
.flexrow {
    display: -webkit-flex;
    display: -moz-flex;
    display: -ms-flex;
    display: -o-flex;
    -webkit-justify-content: space-between;
    -moz-justify-content: space-between;
    -ms-justify-content: space-between;
    -o-justify-content: space-between;
    margin-bottom: 10px;
    -webkit-align-items: center;
    -moz-align-items: center;
    -ms-align-items: center;
    -o-align-items: center;
    -webkit-flex: none;
    -moz-flex: none;
    -ms-flex: none;
    -o-flex: none;
}
.item {
    -webkit-flex: 1;
    -moz-flex: 1;
    -ms-flex: 1;
    -o-flex: 1;
    margin: auto 10px;
    border-radius: 5px;
    border:1px solid black;
}

.status-batal{
	background: #ffcccc;
}

/* ========untuk alt======= */

.po-ket{
	list-style-type: none;
	text-align:left;
	padding:0px;
	display:none;
}

.barang-div{
	margin-bottom:10px;
}

.po-ket ul{
	padding:0px;
	border-bottom:1px solid #ccc;
}

.po-ket ul li {
	display:inline-block;
	display:table-cell;
	vertical-align:middle;
}

.po-ket{
	max-width:300px;
	padding-top:20px;
	/* border-top: 2px solid #ddd; */
	columns:  2;
	-webkit-columns: 2;
	-moz-columns: 2;
	column-rule: 1px solid lightblue;
	column-gap:0px;
}

.po-ket li a{
	text-decoration:none;
	text-align:center;
}

.po-ket li a i{
	font-size:10px;
}

.po-ket li a:hover ul{
	font-weight:bold;
	background-color:rgba(123,74,94,0.2);
	/* border:2px solid;
	padding:5px;
	font-size:1.1em; */
}

.brs-2{
	/* background-color:rgba(200,200,200,0.2); */
	background-color:rgba(255,255,255,0.5);
}

.brs-1{
	background-color:rgba(255,255,255,0.5);
}

.po-box{
	text-align:center;
	padding:7px 0px;
	width:40px;
	/* border:2px solid skyblue; */
	display:table-cell;
	vertical-align:middle;
	background-color:#ddd;
}

.po-box-master{
	background:#1d55ad;
	color:white;
}

.po-ket a .disabled{
	color:#ddd;
}

/* ======================== */

<?if (is_posisi_id() <= 3) {?>
	.c-info:hover .catatan-info{
		display:block;
	}
<?}?>

</style>
<div class="page-content">
	<div class='container'>
		
		<div class="modal fade bs-modal-lg" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-body">
						<form action="<?=base_url('transaction/po_pembelian_list_insert')?>" class="form-horizontal" id="form_add_data" method="post">
							<h3 class='block' style='background:#eee; padding:5px'> PO Pembelian Baru</h3>
							
							<div class="form-group">
			                    <label class="control-label col-md-3">Supplier<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
					                <select class='input1 form-control supplier-input' style='font-weight:bold' name="supplier_id">
			                    		<?foreach ($this->supplier_list_aktif as $row) { ?>
			                    			<option <?=($row->id==1 ? 'selected' : '');?> value="<?=$row->id?>"><?=$row->nama;?></option>
			                    		<? } ?>
			                    	</select>
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
			                    <label class="control-label col-md-3">UP / ATTN<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
					                <input type="text" class="form-control" value="" name="up_person"/>
			                    </div>
			                </div> 	

			                <div class="form-group">
			                    <label class="control-label col-md-3">Sales Contract
			                    </label>
			                    <div class="col-md-6">
			                		<input type="text" class='form-control' name="sales_contract"/>
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

			                <div class="form-group" hidden>
			                    <label class="control-label col-md-3">Catatan
			                    </label>
			                    <div class="col-md-6">
			                		<input type="text" class='form-control' name="catatan"/>
			                    </div>
			                </div>
						</form>
					</div>

					<div class="modal-footer">
						<button type="button" class="btn btn-active btn-trigger blue btn-save">Save</button>
						<button type="button" class="btn  btn-active default" data-dismiss="modal">Close</button>
					</div>
				</div>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
		</div>

		<div class="modal fade bs-modal-lg" id="portlet-config-batal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-body">
						<form action="<?=base_url('transaction/po_pembelian_list_batal')?>" class="form-horizontal" id="form_batal" method="post">
							<h3 class='block' style='background:#ffcccc; padding:5px'> PO Pembelian Batal</h3>
							
			                <div class="form-group">
			                    <label class="control-label col-md-3">No PO<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
					                <input <?=(is_posisi_id() != 1 ? 'hidden' : '' );?> readonly name='id' />
					                <input type="text" style='font-size:1.5em;' readonly class="form-control" name="po_number"/>
			                    </div>
			                </div>

			                <div class="form-group">
			                    <label class="control-label col-md-3">Dibatalkan Oleh<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
					                <input <?=(is_posisi_id() != 1 ? 'hidden' : '' );?> readonly value="<?=is_user_id();?>"/>
					                <input readonly type="text" class="form-control" value="<?=is_username();?>" />
			                    </div>
			                </div> 	

			                <div class="form-group" >
			                    <label class="control-label col-md-3">Catatan
			                    </label>
			                    <div class="col-md-6">
			                		<input type="text" class='form-control' name="catatan"/>
			                    </div>
			                </div>
						</form>
					</div>

					<div class="modal-footer">
						<button type="button" class="btn btn-active btn-trigger blue btn-batal">Save</button>
						<button type="button" class="btn  btn-active default" data-dismiss="modal">Close</button>
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
							<?//if (is_posisi_id() == 1) {?>
								<select class='btn btn-sm btn-default' name='status_select' id='status_select'>
									<option value="" selected>All</option>
									<option value="1">Aktif</option>
									<option value="0">Batal</option>
								</select>
							<?//}?>

							<button class="btn btn-default btn-sm" id="sisa_sortir">
							<i class="fa fa-search"></i> Show Sisa </button>
							<button class="btn btn-default btn-sm" id="remove_sortir" style="display:none">
							<i class="fa fa-search"></i> Show Semua </button>

							<a href="#portlet-config" data-toggle='modal' class="btn btn-default btn-sm btn-form-add">
							<i class="fa fa-plus"></i> Tambah </a>
						</div>
					</div>
					<div class="portlet-body">
						<table class="table table-hover table-striped table-bordered" id="general_table">
							<thead>
								<tr>
									<th scope="col" class='status_column'>
										Status Aktif
									</th>
									<th scope="col">
										Tanggal
									</th>
									<th scope="col">
										No PO
									</th>
									<th scope="col" class='status_column'>
										PO Batch
									</th>
									<th scope="col">
										<div style="display:inline-block; width:180px;">Barang</div>
										<div style="display:inline-block; width:80px;">Harga</div>
										<div style="display:inline-block; width:60px;">PO</div>
										sisa kuota
									</th>							
									<th scope="col" style='width:160px;'>
										Supplier
									</th>
									<th scope="col" style='width:150px'>
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
<script src="<?php echo base_url('assets_noondev/js/fnReload.js');?>"></script>

<script>
var statusView = 0;
	
document.addEventListener("keyup", (e) => {
	e.preventDefault();
	if(e.key === 'Alt'){
		console.log('alt', statusView);
		statusView++;
		statusView %= 2;

		const barangDiv = document.querySelectorAll(".barang-div");
		const poDiv = document.querySelectorAll(".po-ket");

		barangDiv.forEach((el,idx) => {
			if (statusView === 0) {
				el.style.display = 'block';
				if (typeof poDiv[idx] !== 'undefined' ) {
					poDiv[idx].style.display = 'none';
				}
			}else if(statusView === 1){
				// alert('y');
				el.style.display = 'block';
				if (typeof poDiv[idx] !== 'undefined' ) {
					poDiv[idx].style.display = 'block';
				}
			}else if(statusView === 2){
				el.style.display = 'none';
				if (typeof poDiv[idx] !== 'undefined' ) {
					poDiv[idx].style.display = 'block';
				}
			}
		});
	}
});

jQuery(document).ready(function() {
	
	$('.barang-id, .warna-id').select2({
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
   	// console.log(color_list);
   	<?foreach ($this->supplier_list_aktif as $row) {?>
   		kode_supplier["<?=$row->id;?>"] = "<?=$row->kode;?>";
   	<?}?>;

	   const w_st = 30;

	$("#general_table").DataTable({
   		"fnCreatedRow": function( nRow, aData, iDataIndex ) {
            var status = $('td:eq(7)', nRow).text().split('??');
			// console.log(status);
            var id = status[0];
            var toko_id = status[1];
            var supplier_id = status[2];
            // var batch_id = status[2];
            var kode = status[4];
            var batch_list = [3];
	        var barang_list = '';
			
            var locked_master = [];
            var locked_batch =  [];

			var batch_lock = [];
			var batch_release = [];

            if ($('td:eq(4)', nRow).text() != '') {

				locked_master = status[5].split('||');
				locked_batch =  status[6].split('||');

				batch_lock = locked_batch[0].split(',');
				batch_release = locked_batch[1].split(',');

		        var po_qty = $('td:eq(4)', nRow).text().split('=?=');
		        var nm_brg = po_qty[0].split('??');
		        var jumlah = po_qty[1].split('??');
		        var sisa = po_qty[2].split('??');
		        var harga = po_qty[3].split('??');
		        var total_jml = nm_brg.length;
				var status_aktif = $('td:eq(0)', nRow).text();
		        
            	
            	// barang_list = "<table>";
            	$.each(nm_brg, function(i,v){
					var bg_div = '';
					if(status_aktif == 1){
            			bg_div = (sisa[i] <= 0 ? 'background:#ffbabf': '');
					}

					// <div style='display:inline-block; width:170px; font-size:1.1em; padding-right:5px;'> ${v}</div>
		        	// 	<div style='display:inline-block; width:70px; font-size:1.1em'> ${change_number_format(parseFloat(harga[i]))}</div>
		        	// 	<div style='display:inline-block; width:70px; font-size:1.1em'> ${change_number_comma(jumlah[i])}</div> 
			        // console.log(sisa+':'+bg_div);
		        	barang_list += `<div style="${bg_div};position:relative "> 
		        		<div style='display:inline-block; width:170px; font-size:1.1em; padding-right:5px;'> ${v}</div>
		        		<div style='position:absolute; top:0px; left:155px; text-align:right; width:70px; font-size:1.1em'> ${change_number_format(parseFloat(harga[i]))}</div>
		        		<div style='position:absolute; top:0px; left:235px; text-align:right;width:70px; font-size:1.1em'> ${change_number_comma(jumlah[i])}</div> 
		        		<div style='position:absolute; top:0px; left:325px; text-align:right;width:70px;font-size:1.1em'>${change_number_comma(parseFloat(sisa[i]))}</div></div>`;
		        	if ((i+1)%5 == 0 && (i+1) != total_jml) {
		        		barang_list += `<hr style="margin:0; padding:0;" />`;
		        	};
		        	// console.log(i);
		        	// barang_list += "<tr>";
		        	// barang_list += `<td>${v} </td><td>: ${jumlah[i]}</td>`;
	            	// barang_list += "</tr>";
		        });
            	// barang_list += "</table>";

            };      

			var po_pembelian_batch_id = [];
			var po_batch = [];


            if (status[3] !='' && typeof status[3] !== 'undefined') {
	            po_pembelian_batch_id = status[3].toString().split(',');
	            po_batch = $('td:eq(3)', nRow).text().split(',');
	            var po_number = $('td:eq(2)', nRow).text();

	            $.each(po_pembelian_batch_id, function(i,v){
	            	batch_list[i] = `<a target='_blank' href="<?=(base_url().is_setting_link('transaction/po_pembelian_detail_warna_batch'));?>?id=${id}&batch_id=${v}">${$('td:eq(1)', nRow).text()}-${po_batch[i]} <i class='fa fa-search'></i> </a>`;
	            });
            	
            };
            
            var url = "<?=base_url().is_setting_link('transaction/po_pembelian_detail');?>?id="+id;
            // var button_edit = "<a href='"+url+"' class='btn-xs btn green btn-edit'><i class='fa fa-edit'></i> </a>";
            var button_view = '<a href="'+url+'" class="btn-xs btn green btn-view" target="_blank"><i class="fa fa-edit"></i> </a>';
           	var button_remove = '';

           	var posisi_id = "<?=is_posisi_id();?>"
           	if (posisi_id != 6) {
           		if (status_aktif == 1) {
		           	button_remove = `<a href='#portlet-config-batal' data-toggle='modal' class='btn-xs btn red btn-remove')"><i class='fa fa-times'></i> </a>`;
           		}else{
		           	button_remove = "<a class='btn-xs btn blue btn-activate'><i class='fa fa-play'></i> </a>";
           		}
           	};

			let wrn = 0;
			let wrn_list = '';

			if(locked_master[0] !== '-' && locked_master[1] === '-' ){
				wrn++;
				wrn_list = `<b>Master</b>`;
			}
           	
           	var action = `<span class='id' hidden>${id}</span>
				<span class='toko_id' hidden>${toko_id}</span>
				<span class='supplier_id' hidden>${supplier_id}</span>
				${button_remove} ${button_view}`;
			
			
			var tbl_po_list = `<ul class='po-ket'>
				<li>
				<ul hidden>
					<li style="width:40px">PO</li>
					<li style="width:${w_st}px;"><b><i class='fa fa-lock'></i></b></li>
					<li style="width:40px"><b><i class='fa fa-send'></i></b></li>
				</ul>
				</li>
				<li>
					<a target='_blank' href="<?=base_url().is_setting_link('transaction/po_pembelian_detail')?>?id=${id}">
					<ul class='brs-1'>
						<li style="width:40px"> <div class='po-box po-box-master'>M</div></li>
						<li style="width:${w_st}px;"><i class="fa ${(locked_master[0] != '-' ? 'fa-circle' : 'fa-circle-o' )}"></i></li>
						<li style="width:${w_st}px"><i class="fa ${(locked_master[1] != '-' ? 'fa-circle' : 'fa-circle-o' )}"></i></li>
					</ul>	
					</a>
				</li>
				`;

				/* 
				<li>
					<a target='_blank' href="<?=base_url().is_setting_link('transaction/po_pembelian_detail')?>?id=${id}">
					<ul class='brs-1'>
						<li style="width:40px"> <div class='po-box po-box-master'>M</div></li>
						<li style="width:${w_st}px;"><i class="fa ${(locked_master[0] != '-' ? 'fa-circle' : 'fa-circle-o' )}"></i></li>
						<li style="width:${w_st}px"><i class="fa ${(locked_master[1] != '-' ? 'fa-circle' : 'fa-circle-o' )}"></i></li>
					</ul>	
					</a>
				</li>
				
				<li>
					<a target='_blank' href="<?=base_url().is_setting_link('transaction/po_pembelian_detail')?>?id=${id}">
					<ul class='brs-1'>
						<li style="width:40px"> <div class='po-box po-box-master'>M</div></li>
						<li style="width:40px;"><i class="fa fa-lock ${(locked_master[0] != '-' ? '' : 'disabled' )}"></i></li>
						<li style="width:40px"><i class="fa fa-send ${(locked_master[1] != '-' ? '' : 'disabled' )}"></i></li>
					</ul>	
					</a>
				</li> */

			

			po_pembelian_batch_id.forEach((batch_id, index) => {
				if (batch_id !=='-') {
					if(batch_lock[index] !== '-' && batch_release[index] === '-' ){
						wrn++;
						wrn_list += (wrn_list.length > 0 ? ', ' : '');
						wrn_list += `<b>BATCH ${po_batch[index]}</b>`; 
					}
					tbl_po_list += `<li>
						<a target="_blank" href="<?=base_url().is_setting_link('transaction/po_pembelian_detail_warna_batch')?>?id=${id}&batch_id=${batch_id}">
						<ul class="${index%2 == 0 ? 'brs-2' : 'brs-1'}">
							<li style="width:40px"><div class='po-box'>${po_batch[index]}</div></li>
							<li style="width:${w_st}px;"><i class="fa ${(batch_lock[index] != '-' ? 'fa-circle' : 'fa-circle-o' )}"></i></li>
							<li style="width:${w_st}px"><i class="fa  ${(batch_release[index] != '-' ? 'fa-circle' : 'fa-circle-o' )}"></i></li>
						</ul>	
						</a>
					</li>
					`;
				}
			});

			

			tbl_po_list += '</ul>';

			tbl_po_list = (barang_list.length > 0 ?  tbl_po_list : '');
			
			let catatan = $('td:eq(6)', nRow).text();
			let input_catatan = `<span id='span-${id}' class='catatan'>${catatan}</span>`;
			<?if (is_posisi_id() <= 3) {?>
				input_catatan += `<div id='cat-${id}' class='catatan-container' hidden><textarea class='catatan-text' onfocusout="closeInputCatatan('${id}')" rows='3' onchange="updateCatatan('${id}')">${catatan}</textarea></div>`
			<?}?>

			var div_warn = '';
			if (wrn > 0) {
				div_warn = `<div class='note note-warning'><i class='fa fa-warning'></i> PO ${wrn_list} belum release</div>`;
			}

            $('td:eq(0)', nRow).addClass('status_column');
            $('td:eq(3)', nRow).addClass('status_column');
            $('td:eq(2)', nRow).html("<b class='po_number' style='font-size:1.2em'>"+$('td:eq(2)', nRow).text()+'</b>');
            $('td:eq(3)', nRow).html(batch_list.join('<br/>'));
            $('td:eq(4)', nRow).html(`<div class='barang-div'>${barang_list}</div> ${tbl_po_list}`);
            $('td:eq(1)', nRow).html('<span class="tanggal">'+$('td:eq(1)', nRow).text()+'</span>');
            $('td:eq(6)', nRow).html(`${div_warn} ${input_catatan}<div hidden class='catatan-info' style='font-size:0.8em'>double click untuk edit</div>`);
            $('td:eq(6)', nRow).addClass('c-info');
			$('td:eq(7)', nRow).html(action);
			$('td:eq(7)', nRow).addClass("cell-action");
            // console.log(kode+':'+color_list[kode]);
            $('td', nRow).css("background",color_list[kode]);  
            if (status_aktif != 1) {
	            $('td', nRow).css("background",'#fff6b5');            

            };          
            
        },
		"lengthMenu": [100, 50, 25, "All"],
		"ordering":false,
        "bStateSave" :true,
		"bProcessing": true,
		"bServerSide": true,
		"sAjaxSource": baseurl + "transaction/data_po_pembelian",
		"order":[[1,'desc']],
	});

	$('#status_select').change(function(){
		let status_aktif = $(this).val();
		oTable.fnReloadAjax(baseurl + "transaction/data_po_pembelian?status_aktif="+status_aktif);
		// alert('test');
	});

	$('#sisa_sortir').click(function(){
		oTable.fnReloadAjax(baseurl + "transaction/data_po_pembelian?filter=sisa");
		$('#remove_sortir').show();
		$(this).hide();
		// alert('test');
	});

	$('#remove_sortir').click(function(){
		oTable.fnReloadAjax(baseurl + "transaction/data_po_pembelian?status_aktif=1");
		$('#sisa_sortir').show();
		$(this).hide();
		// alert('test');
	});

	var oTable;
    oTable = $('#general_table').dataTable();
    oTable.fnFilter( '', 0 );

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
		var po_pembelian_id = ini.find('.id').html();
		$("#form_batal [name=id]").val(po_pembelian_id);
		$("#form_batal [name=po_number]").val(ini.find('.po_number').html());
		$("#form_batal [name=catatan]").val(ini.find('.catatan').html());
		
		/*bootbox.confirm("Yakin <b style='color:red'>MEMBATALKAN</b> PO Pembelian ini?", function(respond){
			if (respond) {
				var id = ini.find('.id').html();
				window.location.replace(baseurl+'transaction/po_pembelian_list_batal?id='+id);
			};
		});*/
	}) ;

	$(".btn-batal").click(function(){
		$("#form_batal").submit();
	});

	$('#general_table').on('click','.btn-activate', function(){
		var ini = $(this).closest('tr');
		bootbox.confirm("Yakin <b style='color:blue'>MENGAKTIVASI</b> kembali PO Pembelian ini?", function(respond){
			if (respond) {
				var id = ini.find('.id').html();
				window.location.replace(baseurl+'transaction/po_pembelian_list_undo_batal?id='+id);


			};
		});
	}) ;  


	$('.btn-save').click(function () {
        if ($('#form_add_data [name=tanggal]').val() != '')
        {
            $('#form_add_data').submit();
            btn_disabled_load(ini);
        }else{
        	alert("Tanggal harus diisi");
        }
    });

	$(document).on("dblclick", ".c-info", function(){
		let id = $(this).closest('tr').find('.id').text();
		openInputCatatan(id);
	});

	$(document).click(function(e){
        if(!$(e.target).hasClass('catatan-container') && !$(e.target).hasClass('catatan-text') && !$(e.target).hasClass('catatan-info') && !$(e.target).hasClass('c-info'))
        {
			// console.log(e.target);
            $(".catatan-container").hide();
			$(".catatan").show();
        }
    })

});



function change_number_comma(number){
	let n = parseFloat(number).toString().split('.');
	if(typeof n[1] === 'undefined'){
		n[1] = '';
	}else{
		n[1] = ','+n[1];
	}

	return change_number_format(n[0])+n[1];
}

<?if (is_posisi_id() <= 3) {?>

	function openInputCatatan(id){
		$(".catatan-container").hide();
		$(`#span-${id}`).hide();
		$(`#cat-${id}`).show();
		$(`#cat-${id}`).find('textarea').focus();
	}

	function closeInputCatatan(id){
		$(".catatan-container").hide();
		$(`#span-${id}`).show();
	}

	function updateCatatan(bId){
		// alert('y');

		let cat = $(`#cat-${bId}`).find('textarea').val();
		var data_st = {};
		const url = "transaction/po_pembelian_catatan_update";
		data_st['id'] = bId;
		data_st['catatan'] = cat;
		console.log(data_st);
		
		ajax_data_sync(url,data_st).done(function(data_respond  ,textStatus, jqXHR ){
			// console.log(data_respond);
			if (textStatus == 'success') {
				notific8("lime","data updated");
				$(`#span-${bId}`).html(cat);
				// openInputCatatan(bId, 2)
			};
		});
	}

<?}?>
</script>
