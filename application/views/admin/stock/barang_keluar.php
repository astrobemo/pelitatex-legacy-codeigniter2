<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css'); ?>"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css'); ?>"/>
<?php echo link_tag('assets/global/plugins/bootstrap-datepicker/css/datepicker3.css'); ?>
<script src="<?php echo base_url('assets/global/plugins/bootbox/bootbox.min.js'); ?>" type="text/javascript"></script>


<div class="page-content">
	<div class='container'>
		
		<div class="modal fade" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body">
						<form action="<?=$common_data['controller_main'].'/barang_keluar_insert';?>" class="form-horizontal" id="form_add_data" method="post"  > <!-- target="_blank" -->
							<h3 class='block'> Tambah </h3>

							<div class="form-group">
			                    <label class="control-label col-md-4">Customer<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<select class='form-control' name='customer_id'>
	                    				<?foreach ($this->customer_list_aktif as $row) { ?>
	                    					<option value="<?=$row->id;?>"><?=$row->nama;?></option>
	                    				<? } ?>
	                    			</select>
			                    </div>
			                </div>

			                <div class="form-group">
			                    <label class="control-label col-md-4">Tanggal Surat Jalan<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<input name="tanggal" class='form-control date-picker' value="<?=date('d/m/Y');?>"/>
			                    </div>
			                </div>

			                <div class="form-group">
			                    <label class="control-label col-md-4">Tanggal Order<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<input name="tanggal_order" class='form-control date-picker' value="<?=date('d/m/Y');?>"/>
			                    </div>
			                </div>


			                <div class="form-group">
			                    <label class="control-label col-md-4">Sales<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
	                    			<select class='form-control' name='sales_id'>
	                    				<?foreach ($this->sales_list_aktif as $row) { ?>
	                    					<option value="<?=$row->id;?>"><?=$row->nama;?></option>
	                    				<? } ?>
	                    			</select>
			                    </div>
			                </div>

			                <div class="form-group">
			                    <label class="control-label col-md-4">Keterangan
			                    </label>
			                    <div class="col-md-6">
	                    			<input name="keterangan" class='form-control'/>
			                    </div>
			                </div>

			                
						</form>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn blue btn-save">Save</button>
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
							<!-- <a href="#portlet-config" data-toggle='modal' class="btn btn-default btn-sm btn-form-add">
							<i class="fa fa-plus"></i> Tambah </a> -->

							<div class="btn-group">
								<a class="btn btn-default btn-sm" href="#portlet-config" data-toggle='modal'>
									<i class="fa fa-plus"></i> Tambah
								</a>
							</div>
						</div>
					</div>
					<div class="portlet-body">
						<table class="table table-striped table-bordered table-hover" id="general_table">
							<thead>
								<!-- status_aktif','tanggal_order', 'nama_sales','surat_jalan', 'tanggal','nama_customer','data' -->
								<tr>
									<th scope="col" class='status_column'>
										Status Aktif
									</th>
									<th scope="col">
										Tanggal Order
									</th>
									<th scope="col">
										Nama Sales
									</th>
									<th scope="col">
										Surat Jalan
									</th>
									<th scope="col">
										Tanggal
									</th>
									<th scope="col">
										Customer
									</th>
									<th scope="col">
										Action
									</th>
								</tr>
							</thead>
							<tbody>
								<?/*foreach ($po_pembelian_ppn_list as $row) { ?>
									<tr>
										<td>
											<span class='id' hidden="hidden"><?=$row->id;?></span>
											<span class='po_number'><?=$row->po_number;?></span> 
										</td>
										<td>
											<span class='tanggal'><?=implode('/', array_reverse(explode('-', $row->tanggal)));?></span> 
										</td>
										<td>
											<span class='supplier_id' hidden='hidden'><?=$row->supplier_id;?></span>
											<?=$row->nama_supplier;?> 
										</td>
										<td>
											<span class='currency_type_id' hidden='hidden'><?=$row->currency_type_id;?></span>
											<?=$row->simbol;?>
										</td>
										<td>
											<a href='#portlet-config-edit' data-toggle='modal' class="btn-xs btn green btn-edit"><i class="fa fa-edit"></i></a>
										</td>
									</tr>
								<? }*/ ?>

							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>			
</div>

<script src="<?php echo base_url('assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/datatables/media/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/datatables/extensions/ColReorder/js/dataTables.colReorder.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets_noondev/js/table-advanced.js'); ?>"></script>


<script src="<?php echo base_url('assets/global/plugins/bootstrap-select/bootstrap-select.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/select2/select2.min.js'); ?>" type="text/javascript" ></script>

<script>
jQuery(document).ready(function() {

	// TableAdvanced.init();
	// oTable = $('#general_table').DataTable();
	// oTable.state.clear();
	// oTable.destroy();
	// dataTableActive();
	$('#invoice_select').select2({
        placeholder: "Select...",
        allowClear: true
    });

	$('.btn-save').click(function(){
		if ($('#form_add [name=sales_id]').val() == '' && $('#form_add [name=tanggal]').val() == '' ) {
			bootbox.alert('Tanggal / Sales harus diisi.');
		}else{
			$('#form_add_data').submit();
			page_reload();
		}
	});


	$("#general_table").DataTable({
   		"fnCreatedRow": function( nRow, aData, iDataIndex ) {
   			var data = $('td:eq(6)', nRow).text().split('??');
   			var id = data[0];
   			var sales_id = data[1];
   			var customer_id = data[2];
   			var link = "<?=is_setting_link('stock/barang_keluar_detail');?>"
			var btn_view = '<a href="'+link+'/'+data[0]+'" class="btn btn-xs yellow-gold" onclick="window.open(this.href, \'newwindow\', \'width=1250, height=650\'); return false;"><i class="fa fa-search"></i></a>';
			var action = btn_view;

            $('td:eq(0)', nRow).html("<span class='status_aktif'>"+$('td:eq(0)', nRow).text()+'</span>');
            $('td:eq(0)', nRow).addClass('status_column');
            $('td:eq(1)', nRow).html(date_formatter($('td:eq(1)', nRow).text()));
            $('td:eq(4)', nRow).html(date_formatter($('td:eq(4)', nRow).text()));
            $('td:eq(6)', nRow).html(action);
            
        },
        "bStateSave" :true,
		"bProcessing": true,
		"bServerSide": true,
		"sAjaxSource": baseurl + "stock/barang_keluar_data"
	});

	// dataTableActive();
	var oTable;
    oTable = $('#general_table').dataTable();
    oTable.fnFilter( 1, 0 );

    $('#status_aktif_select').change(function(){
        oTable.fnFilter( $(this).val(), 0 ); 
    });


});
</script>
