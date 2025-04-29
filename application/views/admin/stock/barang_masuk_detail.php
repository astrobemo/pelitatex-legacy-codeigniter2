<style type="text/css">
#general_table input{
	border: none;
}
</style>
<script src="<?php echo base_url('assets/global/plugins/bootbox/bootbox.min.js'); ?>" type="text/javascript"></script>

<div class="page-content">
	<div class='container'>
		
		<div class="modal fade" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body">
						<form action="<?=$common_data['controller_main'].'/barang_masuk_insert';?>" class="form-horizontal" id="form_add">

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
								<tr>
									<th scope="col">
										Barang
									</th>
									<th scope="col">
										Qty
									</th>
									<th scope="col">
										
									</th>
								</tr>
							</thead>
							<tbody>
								<?foreach ($barang_list as $row) { ?>
									<tr>
										<td>
											<?=$row->nama_barang;?> 
										</td>
										<td>
											<input name='qty' value="<?=$row->qty;?>" placeholder='qty'>
											<br/>
											<input name='qty_notes' value="<?=$row->qty_notes;?>" placeholder="qty notes"> 
										</td>
										<td>
											
										</td>
									</tr>
								<? } ?>

							</tbody>
						</table>
					</div>
					<div class="row">
		                <div class="col-md-12" style='margin-top:30px;'>
		                	<a href="javascript:window.open('','_self').close();" class="btn default button-previous hidden-print">Close</a>
		                </div>
					</div>
				</div>
			</div>
		</div>
	</div>			
</div>

<?php echo link_tag('assets/global/plugins/bootstrap-datepicker/css/datepicker3.css'); ?>

<script src="<?php echo base_url('assets/global/plugins/bootstrap-select/bootstrap-select.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/select2/select2.min.js'); ?>" type="text/javascript" ></script>

<script>
jQuery(document).ready(function() {

	// TableAdvanced.init();
	$('#invoice_select').select2({
        placeholder: "Select...",
        allowClear: true
    });

	$('#form_add [name=pembelian_id]').change(function(){
		if ($(this).val() != '') {
			var type = $(this).val().split('??');
			// alert(type);
			$("#form_add [name=po_type]").val(type[1]);
			// $("#form_add [name=po_type][value='"+type+"']").change();
		};
	});

	$('.btn-save').click(function(){
		if ($('#form_add [name=pembelian_id]').val() == '' && $('#form_add [name=surat_jalan]').val() == '') {
			bootbox.alert('Surat Jalan / No invoice harus diisi salah satu.');
		}else{
			alert('bagus');
		}
	});

});
</script>
