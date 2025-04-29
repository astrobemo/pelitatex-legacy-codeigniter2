<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css'); ?>"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css'); ?>"/>
<?=link_tag('assets/global/plugins/bootstrap-datepicker/css/datepicker3.css'); ?>


<div class="page-content">
	<div class='container'>

		<div class="modal fade" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body">
						<form action="<?=base_url('inventory/stok_opname_insert')?>" class="form-horizontal" id="form_add_data" method="post">
							<h3 class='block'> Data Baru</h3>
							
							<div class="form-group">
			                    <label class="control-label col-md-3">Tanggal Stok Opname<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<input readonly name='tanggal' class='form-control date-picker' value="<?=date('d/m/Y')?>" autocomplete="off">
			                    </div>
			                </div>

			                <?if (is_posisi_id() == 1) {?>
			                	<div class="form-group">
				                    <label class="control-label col-md-3">Tanggal<span class="required">
				                    * </span>
				                    </label>
				                    <div class="col-md-6">
				                    	<div class="input-group date form_datetime input-medium">
											<input type="text" id='created' name='created' readonly class="form-control">
											<span class="input-group-btn">
											<button class="btn default date-set" type="button"><i class="fa fa-calendar"></i></button>
											</span>
										</div>
				                    </div>
				                </div>


			                <?}?>

			                <div class="form-group">
			                    <label class="control-label col-md-3">Kode Barang<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<select name='barang_id_so'  class='form-control' id='barang_id_select'>
		                				<option value=''>Pilihan..</option>
			                    		<?foreach ($this->barang_list_aktif as $row) { 
			                    			if ($row->status_aktif == 1) {?>
				                    			<option value="<?=$row->id?>"><?=$row->nama_jual?></option>
			                    			<?}?>
			                    		<? } ?>
			                    	</select>
			                    </div>
			                </div>

			               
			                <div class="form-group">
			                    <label class="control-label col-md-3">Warna<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
	                    			<select name="warna_id_so" class='form-control' id='warna_id_select'>
		                				<option value=''>Pilihan..</option>
			                    		<?foreach ($this->warna_list_aktif as $row) { ?>
			                    			<option value="<?=$row->id?>"><?=$row->warna_beli;?></option>
			                    		<? } ?>
			                    	</select>
			                    </div>
			                </div>

			                <div class="form-group">
			                    <label class="control-label col-md-3">Gudang<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<select name='gudang_id_so'  class='form-control' >
			                    		<?foreach ($this->gudang_list_aktif as $row) { ?>
			                    			<option value="<?=$row->id?>"><?=$row->nama?></option>
			                    		<? } ?>
			                    	</select>
			                    </div>
			                </div>
			                
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

		
		<div class="row margin-top-10">
			<div class="col-md-12">
				<div class="portlet light">
					<div class="portlet-title">
						<div class="caption caption-md">
							<i class="icon-bar-chart theme-font hide"></i>
							<span class="caption-subject theme-font bold uppercase"><?=$breadcrumb_small;?></span>
						</div>
						<div class="tools">
							<a href="" class="fullscreen">
							</a>
						</div>

						<div class="actions hidden-print">
							<?if (is_posisi_id() == 1) {?>
								<a href="<?=base_url().is_setting_link('inventory/stok_opname_detail_2')?>" class="btn green "><i class="fa fa-plus"></i> SO Mini </a>
							<?}?>
							<a href="#portlet-config" data-toggle='modal' onclick="setCreatedTime()" class="btn btn-default"><i class="fa fa-plus"></i> SO Besar </a>
						</div>
					</div>
					<div class="portlet-body">

						<table class="table table-hover table-bordered" id="general_table">
							<thead>
								<tr>
									<th>
										Tanggal
									</th>
									<th>
										Lokasi
									</th>
									<th>
										<!-- Status -->
										Barang
									</th>
									<th>
										Qty
									</th>
									<th>
										Action
									</th>
								</tr>
							</thead>
							<tbody>
								<?foreach ($stok_opname_list as $row) {?>
									<tr>
										<!--<td><?=is_reverse_date($row->tanggal);?></td>-->
										<td><?=$row->created_at;?></td>
										<td><?=$row->nama_gudang;?></td>
										<!--<td><?=($row->status_aktif == 1 ? "<span style='color:blue'>aktif</span>" : 'batal')?></td>-->
										<td><?=($row->barang_id_so != '' ? $row->nama_jual.' '.$row->warna_jual : 'SO BESAR')?></td>
										<td><?=($row->barang_id_so != '' ? number_format($row->qty,'0',',','.') : '-')?></td>
										<td>
											<?if ($row->barang_id_so == '') {?>
												<a href="<?=base_url().is_setting_link('inventory/stok_opname_detail').'?id='.$row->id;?>" class='btn btn-xs yellow-gold'><i class='fa fa-search'></i></a>
											<?}else{?>
												<a href="<?=base_url().is_setting_link('inventory/stok_opname_detail_2').'?id='.$row->id;?>" class='btn btn-xs yellow-gold'><i class='fa fa-search'></i></a>
											<?}?>
											<?if ($row->id == 1) {?>
												<a href="<?=base_url().is_setting_link('inventory/stok_opname_overview_with_before').'?id='.$row->id;?>" class='btn btn-xs blue'><i class='fa fa-link'></i>2020</a>
											<?}?>
										</td>
									</tr>
								<?}?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>			
</div>

<script src="<?php echo base_url('assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/bootbox/bootbox.min.js'); ?>" type="text/javascript"></script>

<script src="<?php echo base_url('assets/global/plugins/datatables/media/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets_noondev/js/table-advanced.js'); ?>"></script>
<script src="<?php echo base_url('assets/global/plugins/select2/select2.min.js'); ?>" type="text/javascript" ></script>


<script>
jQuery(document).ready(function() {
	

	$("#general_table").DataTable({
		"ordering":false
	});

	$('#barang_id_select,#warna_id_select').select2();

	$(".btn-save").click(function(){
		if ($('#form_add_data [name=tanggal]').val() != '' ) {
			btn_disabled_load($(this));
			$("#form_add_data").submit();
		}else{
			alert("Lengkapi data");
		}
	});
	
});

function setCreatedTime(){
	let tgl = new Date();
	let waktu =  tgl.getHours()+':'+tgl.getMinutes();
	$("#created").val(`<?=date('d/m/Y');?> ${waktu}`);

}
</script>
