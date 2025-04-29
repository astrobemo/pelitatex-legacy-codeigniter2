<link href="<?=base_url('assets_noondev/css/bootstrap-modal-bs3patch.css');?>" rel="stylesheet" type="text/css"/>
<link href="<?=base_url('assets/global/plugins/bootstrap-modal/css/bootstrap-modal.css');?>" rel="stylesheet" type="text/css"/>
<div class="page-content">
	<div class='container'>
		
		<div id="ajax-modal" class="modal fade" tabindex="-1">
		</div>

		<div class="modal fade" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<!-- <div class="modal-dialog"> -->
				<div class="modal-content">
					<!-- <div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
						<h4 class="modal-title">Modal title</h4>
					</div> -->
					<div class="modal-body">
						<form action="<?=base_url('delegate/menu_insert')?>" class="form-horizontal" id="form_add_data" method="post">
							<h3 class='block'> Tambah </h3>
				                <div class="form-group">
				                    <label class="control-label col-md-3">Name<span class="required">
				                    * </span>
				                    </label>
				                    <div class="col-md-8">
				                    	<input type="text" class="form-control" name="nama_id"/>
				                    </div>
				                    
				                </div>
				                <div class="form-group">
				                    <label class="control-label col-md-3">Text<span class="required">
				                    * </span>
				                    </label>
				                    <div class="col-md-8">
				                    	<input type="text" class="form-control" name="text" />
				                    </div>
				                </div>

				                <div class="form-group">
				                    <label class="control-label col-md-3">Icon Class<span class="required">
				                    * </span>
				                    </label>
				                    <div class="col-md-8">
				                    	<input type="text" class="form-control" name="icon_class" />
				                    </div>
				                </div>

				                <div class="form-group">
				                    <label class="control-label col-md-3">Urutan<span class="required">
				                    * </span>
				                    </label>
				                    <div class="col-md-8">
				                    	<input type="text" class="form-control" name="urutan" />
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
			<!-- </div> -->
			<!-- /.modal-dialog -->
		</div>

		<div class="modal fade" id="portlet-config-edit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<!-- <div class="modal-dialog"> -->
				<div class="modal-content">
					<!-- <div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
						<h4 class="modal-title">Modal title</h4>
					</div> -->
					<div class="modal-body">
						<form action="<?=base_url('delegate/menu_update')?>" class="form-horizontal" id="form_edit_data" method="post">
							<h3 class='block'> Edit </h3>
				                <div class="form-group">
				                    <label class="control-label col-md-3">Name<span class="required">
				                    * </span>
				                    </label>
				                    <div class="col-md-8">
				                    	<input name="menu_id"/>
				                    	<input type="text" class="form-control" name="nama_id"/>
				                    </div>
				                    
				                </div>
				                <div class="form-group">
				                    <label class="control-label col-md-3">Text<span class="required">
				                    * </span>
				                    </label>
				                    <div class="col-md-8">
				                    	<input type="text" class="form-control" name="text" />
				                    </div>
				                </div>

				                <div class="form-group">
				                    <label class="control-label col-md-3">Icon Class<span class="required">
				                    * </span>
				                    </label>
				                    <div class="col-md-8">
				                    	<input type="text" class="form-control" name="icon_class" />
				                    </div>
				                </div>

				                <div class="form-group">
				                    <label class="control-label col-md-3">Urutan<span class="required">
				                    * </span>
				                    </label>
				                    <div class="col-md-8">
				                    	<input type="text" class="form-control" name="urutan" />
				                    </div>
				                </div>		
						</form>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn blue btn-edit-save">Save</button>
						<button type="button" class="btn default" data-dismiss="modal">Close</button>
					</div>
				</div>
				<!-- /.modal-content -->
			<!-- </div> -->
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
							<a href="#portlet-config" data-toggle='modal' class="btn btn-default btn-sm btn-form-add">
							<i class="fa fa-plus"></i> Tambah </a>
						</div>
					</div>
					<div class="portlet-body">
						<table class="table table-striped table-bordered table-hover" id="general_table">
							<thead>
								<tr>
									<th scope="col">
										Name
									</th>
									<th scope="col">
										Text
									</th>
									<th scope="col">
										Icon Class
									</th>
									<th scope="col">
										Urutan
									</th>
									<th scope="col" style="min-width:150px !important">
										Actions
									</th>
								</tr>
							</thead>
							<tbody>
								<?foreach ($menu_list as $row) { ?>
									<tr>
										<td>
											<span class='nama_id'><?=$row->nama_id;?></span> 
										</td>
										<td>
											<span class='text'><?=$row->text;?></span> 
										</td>
										<td>
											<i class="<?=$row->icon_class;?>"></i><span class='icon_class'><?=$row->icon_class?></span> 
										</td>
										<td>
											<span class='urutan'><?=$row->urutan;?></span> 
										</td>
										<td>
											<span hidden class='id'><?=$row->id;?></span>
											<a href='#portlet-config-edit' data-toggle='modal' class='btn btn-xs green btn-edit'><i class='fa fa-edit'></i> Edit</a>
											<!-- <a data-toggle='modal' class="btn-xs btn yellow-gold btn-manage"><i class="fa fa-edit"></i> Manage</a> -->
											<a href="<?=base_url().is_setting_link('delegate/menu_detail_list');?>?menu_id=<?=$row->id;?>" class="btn-xs btn yellow-gold"><i class="fa fa-edit"></i> Manage</a>
											<!-- <a href="delegate/menu_detail_list?menu_id=<?=$row->id;?>&menu_name=<?=$row->text;?>" class='btn btn-xs blue'><i class='fa fa-edit'></i> Manage</a> -->
										</td>
									</tr>
								<? } ?>

							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>			
</div>

<script src="<?php echo base_url('assets_noondev/js/ui-extended-modals.js'); ?>"></script>
<script src="<?php echo base_url('assets/global/plugins/bootstrap-modal/js/bootstrap-modalmanager.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/bootstrap-modal/js/bootstrap-modal.js'); ?>" type="text/javascript"></script>
<script>
jQuery(document).ready(function() {       
   	menuListDetail.init();
   	$('.btn-edit').click(function(){
   		var ini = $(this).closest('tr');
   		$('#form_edit_data [name=menu_id]').val(ini.find('.id').html());
   		$('#form_edit_data [name=nama_id]').val(ini.find('.nama_id').html());
   		$('#form_edit_data [name=icon_class]').val(ini.find('.icon_class').html());
   		$('#form_edit_data [name=text]').val(ini.find('.text').html());
   		$('#form_edit_data [name=urutan]').val(ini.find('.urutan').html());
   		$('#form_edit_data [name=status_aktif]').val(ini.find('[name=status_aktif]').val());
   			
   	});

   	$(document).on('click','.btn-add-menu',function(){
   		var menu_id = $('#ajax-modal .portlet-body').find('[name=menu_id]').val();
   		$('#ajax-modal').load('delegate/menu_detail_list?menu_id='+menu_id);
   	});
   	// var $modal = $('#ajax-modal');
   	// $modal.load('delegate/menu_detail_list?menu_id='+menu_id+'&menu_name'+menu_name, '', function(){

   	$('.btn-save').click(function(){
   		$('#form_add_data').submit();
   	});

   	$('.btn-edit-save').click(function(){
   		$('#form_edit_data').submit();
   	});
});
</script>
