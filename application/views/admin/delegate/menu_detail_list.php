<link href="<?=base_url('assets/global/plugins/bootstrap-modal/css/bootstrap-modal.css');?>" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" type="text/css" href="<?=base_url('assets/global/plugins/jstree/dist/themes/default/style.min.css');?>"/>

<div class="page-content">
	<div class='container'>

		<div class="modal fade" id="portlet-config-detail" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<!-- <div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
						<h4 class="modal-title">Modal title</h4>
					</div> -->
					<div class="modal-body">
						<form action="<?=base_url('delegate/menu_detail_insert')?>" class="form-horizontal" id="form_detail_add_data" method="post">
							<h3 class='block'> Tambah </h3>
				                <div class="form-group">
				                    <label class="control-label col-md-3">Controller<span class="required">
				                    * </span>
				                    </label>
				                    <div class="col-md-6">
				                    	<input hidden name="menu_id" value='<?=$menu_id?>'>
				                    	<select class="form-control" name="controller">
				                    		<?foreach ($controller_list as $row) { ?>
				                    			<option value='<?=$row->name;?>'><?=$row->name;?></option>
				                    		<? } ?>
											
				                    	</select>
				                    </div>
				                    
				                </div>

				                <div class="form-group">
				                    <label class="control-label col-md-3">Tipe<span class="required">
				                    * </span>
				                    </label>
				                    <div class="col-md-6">
				                    	<select class="form-control" name="level">
				                    		<option value='1' selected>LINK LEVEL 1</option>
				                    		<option value='2'>LINK LEVEL 2</option>
				                    		<option value='3'>KATEGORIAL</option>
				                    		<option value='99'>TIDAK PERLU TAMPIL</option>
				                    	</select>
				                    </div>
				                    
				                </div>

				                <div class="form-group link-href">
				                    <label class="control-label col-md-3">Link<span class="required">
				                    * </span>
				                    </label>
				                    <div class="col-md-6">
				                    	<input type="text" class="form-control" name="page_link"/>
				                    </div>
				                    
				                </div>

				                <div class="form-group">
				                    <label class="control-label col-md-3">Text<span class="required">
				                    * </span>
				                    </label>
				                    <div class="col-md-6">
				                    	<input type="text" class="form-control" name="text"/>
				                    </div>
				                    
				                </div>

				                <div class="form-group urutan">
				                    <label class="control-label col-md-3">Urutan<span class="required">
				                    * </span>
				                    </label>
				                    <div class="col-md-6">
				                    	<input type="text" class="form-control" name="urutan"/>
				                    </div>
				                    
				                </div>

				                <div class="form-group parent-id" hidden>
				                    <label class="control-label col-md-3">Parent<span class="required">
				                    * </span>
				                    </label>
				                    <div class="col-md-6">
				                    	<select class='form-control' name='parent_id'>
				                    		<option value=''>Pilih</option>
				                    		<?foreach ($menu_list_parent as $row) { ?>
				                    			<option value="<?=$row->id;?>"><?=$row->text;?></option>
				                    		<? } ?>
				                    	</select>
				                    </div>
				                    
				                </div>

				                <div class="form-group">
				                    <label class="control-label col-md-3">Status<span class="required">
				                    * </span>
				                    </label>
				                    <div class="col-md-6">
				                    	<select class='form-control' name='status_aktif'>
				                    		<option value='0'>Tidak Aktif</option>
				                    		<option selected value='1'>Aktif</option>
				                    	</select>
				                    </div>
				                    
				                </div>			
						</form>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn blue btn-add-detail-menu">Save</button>
						<button type="button" class="btn default" data-dismiss="modal">Close</button>
					</div>
				</div>x
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
		</div>

		<div class="modal fade" id="portlet-config-edit-detail" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<!-- <div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
						<h4 class="modal-title">Modal title</h4>
					</div> -->
					<div class="modal-body">
						<form action="<?=base_url('delegate/menu_detail_update')?>" class="form-horizontal" id="form_detail_edit_data" method="post">
							<h3 class='block'> Edit </h3>
				                
				                <div class="form-group">
				                    <label class="control-label col-md-3">Controller<span class="required">
				                    * </span>
				                    </label>
				                    <div class="col-md-6">
				                    	<input name="menu_id" value="<?=$menu_id;?>"/>
				                    	<input name="menu_detail_id" hidden/>
				                    	<select class="form-control" name="controller">
				                    		<?foreach ($controller_list as $row) { ?>
				                    			<option value='<?=$row->name;?>'><?=$row->name;?></option>
				                    		<? } ?>
				                    	</select>
				                    </div>
				                    
				                </div>

				                <div class="form-group">
				                    <label class="control-label col-md-3">Tipe<span class="required">
				                    * </span>
				                    </label>
				                    <div class="col-md-6">
				                    	<select class="form-control" name="level">
				                    		<option value='1'>LINK LEVEL 1</option>
				                    		<option value='2'>LINK LEVEL 2</option>
				                    		<option value='3'>KATEGORIAL</option>
				                    		<option value='99'>TIDAK PERLU TAMPIL</option>
				                    	</select>
				                    </div>
				                    
				                </div>

				                <div class="form-group link-href">
				                    <label class="control-label col-md-3">Link<span class="required">
				                    * </span>
				                    </label>
				                    <div class="col-md-6">
				                    	<input type="text" class="form-control" name="page_link"/>
				                    </div>
				                    
				                </div>

				                <div class="form-group">
				                    <label class="control-label col-md-3">Text<span class="required">
				                    * </span>
				                    </label>
				                    <div class="col-md-6">
				                    	<input type="text" class="form-control" name="text"/>
				                    </div>
				                    
				                </div>

				                <div class="form-group urutan">
				                    <label class="control-label col-md-3">Urutan<span class="required">
				                    * </span>
				                    </label>
				                    <div class="col-md-6">
				                    	<input type="text" class="form-control" name="urutan"/>
				                    </div>
				                    
				                </div>

				                <div class="form-group parent-id">
				                    <label class="control-label col-md-3">Parent<span class="required">
				                    * </span>
				                    </label>
				                    <div class="col-md-6">
				                    	<select class='form-control' name='parent_id'>
				                    		<option value=''>Pilih</option>
				                    		<?foreach ($menu_list_parent as $row) { ?>
				                    			<option value="<?=$row->id;?>"><?=$row->text;?></option>
				                    		<? } ?>
				                    	</select>
				                    </div>
				                    
				                </div>

				                <div class="form-group">
				                    <label class="control-label col-md-3">Status<span class="required">
				                    * </span>
				                    </label>
				                    <div class="col-md-6">
				                    	<select class='form-control' name='status_aktif'>
				                    		<option value='0'>Tidak Aktif</option>
				                    		<option value='1'>Aktif</option>
				                    	</select>
				                    </div>
				                    
				                </div>			
						</form>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn blue btn-edit-detail-menu">Save</button>
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
							<a href="#portlet-config-detail" data-toggle='modal' class="btn btn-default btn-sm btn-form-add">
							<i class="fa fa-plus"></i> Tambah </a>
						</div>
					</div>
					<div class="portlet-body">
						<div class="tabbable tabbable-custom">
							<ul class="nav nav-tabs">
								<li class="active">
									<a href="#tab_1_1" data-toggle="tab">
									Daftar Menu </a>
								</li>
								<li>
									<a href="#tab_1_2" data-toggle="tab">
									Tree View </a>
								</li>
							</ul>

							<div class="tab-content">
								<div class="tab-pane active" id="tab_1_1">
									<div style='overflow:auto'>
										<table class="table table-striped table-bordered table-hover" id="general_table">
											<thead>
												<tr>
													<th scope="col">
														ID
													</th>
													<th scope="col">
														Controller
													</th>
													<th scope="col">
														Link
													</th>
													<th scope="col">
														Text
													</th>
													<th scope="col">
														Status
													</th>
													<th scope="col">
														Urutan
													</th>
													<th scope="col">
														Level
													</th>
													<th scope="col">
														Parent ID
													</th>
													<th scope="col" style="min-width:150px !important">
														Actions
													</th>
												</tr>
											</thead>
											<tbody>
												<?foreach ($menu_list_detail as $row) { 
													if ($row->urutan != 99) {?>
														<tr>
															<td>
																<?=$row->id;?>
															</td>
															<td>
																<span hidden class='menu_detail_id'><?=$row->id;?></span>
																<span class='controller'><?=$row->controller;?></span>
															</td>
															<td>
																<span class='page_link'><?=$row->page_link;?></span> 
															</td>
															<td>
																<span class='text'><?=$row->text;?></span> 
															</td>
															<td>
																<?if ($row->status_aktif == 1) { ?>
																	<span class='label label-primary'>aktif</span>
																<?}else { ?>
																	<span class='label label-danger'>tidak aktif</span>
																<?}?>
																<input hidden name='status_aktif' value='<?=$row->status_aktif;?>'>
															</td>
															<td>
																<span class='urutan'><?=$row->urutan;?></span> 
															</td>
															<td>
																<span class='level'><?=$row->level;?></span> 
															</td>
															<td>
																<span class='parent_id'><?=$row->parent_id;?></span> 
															</td>
															<td>
																<a href='#portlet-config-edit-detail' data-toggle='modal' class="btn-xs btn green btn-edit"><i class="fa fa-edit"></i> Edit</a>
															</td>
														</tr>
													
													<?}?>
													
												<? } ?>

											</tbody>
											<!-- <input name='menu_id' style='display:none' value="<?=$menu_id;?>"> -->
										</table>

										<hr/>

										<table class="table table-striped table-bordered table-hover" id="general_table_2">
											<thead>
												<tr>
													<th scope="col">
														ID
													</th>
													<th scope="col">
														Controller
													</th>
													<th scope="col">
														Link
													</th>
													<th scope="col">
														Text
													</th>
													<th scope="col">
														Status
													</th>
													<th scope="col">
														Urutan
													</th>
													<th scope="col">
														Level
													</th>
													<th scope="col">
														Parent ID
													</th>
													<th scope="col" style="min-width:150px !important">
														Actions
													</th>
												</tr>
											</thead>
											<tbody>
												<?foreach ($menu_list_detail as $row) { 
													if ($row->urutan==99) {?>
														<tr>
															<td>
																<?=$row->id;?>
															</td>
															<td>
																<span hidden class='menu_detail_id'><?=$row->id;?></span>
																<span class='controller'><?=$row->controller;?></span>
															</td>
															<td>
																<span class='page_link'><?=$row->page_link;?></span> 
															</td>
															<td>
																<span class='text'><?=$row->text;?></span> 
															</td>
															<td>
																<?if ($row->status_aktif == 1) { ?>
																	<span class='label label-primary'>aktif</span>
																<?}else { ?>
																	<span class='label label-danger'>tidak aktif</span>
																<?}?>
																<input hidden name='status_aktif' value='<?=$row->status_aktif;?>'>
															</td>
															<td>
																<span class='urutan'><?=$row->urutan;?></span> 
															</td>
															<td>
																<span class='level'><?=$row->level;?></span> 
															</td>
															<td>
																<span class='parent_id'><?=$row->parent_id;?></span> 
															</td>
															<td>
																<a href='#portlet-config-edit-detail' data-toggle='modal' class="btn-xs btn green btn-edit"><i class="fa fa-edit"></i> Edit</a>
															</td>
														</tr>	
													<?}?>										
												<? } ?>

											</tbody>
											<!-- <input name='menu_id' style='display:none' value="<?=$menu_id;?>"> -->
										</table>
									</div>
								</div>
								<div class="tab-pane" id="tab_1_2">
									<div id="tree_1" class="tree-demo">
										<ul>
											<?foreach ($menu_list_detail as $baris) {
												if ($baris->level != 2 && $baris->urutan != 99) {?>
													<li data-jstree='{ "opened" : true }'>
														<?=$baris->text;?>
														<?if ($baris->level == 3) { ?>
															<ul>
																<?foreach ($menu_list_detail as $row) {
																	if ($row->level == 2 && $row->parent_id == $baris->id ) {?>
																		<li>
																			<?=$row->text;?>
																		</li>
																	<?}
																}?>
															</ul>
														<?}?>
													</li>
												<?}?>
											<?}?>
											
										</ul>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script src="<?php echo base_url('assets/global/plugins/bootstrap-modal/js/bootstrap-modalmanager.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/bootstrap-modal/js/bootstrap-modal.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/jstree/dist/jstree.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/admin/pages/scripts/ui-tree.js'); ?>"></script>


<script>
jQuery(document).ready(function() {
	$('#tree_1').jstree({
        "core" : {
            "themes" : {
                "responsive": false
            }            
        },
        "types" : {
            "default" : {
                "icon" : "fa fa-folder icon-state-warning icon-lg"
            },
            "file" : {
                "icon" : "fa fa-file icon-state-warning icon-lg"
            }
        },
        "plugins": ["types"]
    });
   
   	$('.btn-edit').click(function(){
   		var ini = $(this).closest('tr');
   		var form = $('#form_detail_edit_data'); 
   		form.find('[name=menu_detail_id]').val(ini.find('.menu_detail_id').html());
   		form.find('[name=controller]').val(ini.find('.controller').html());
   		form.find('[name=page_link]').val(ini.find('.page_link').html());
   		form.find('[name=text]').val(ini.find('.text').html());
   		form.find('[name=urutan]').val(ini.find('.urutan').html());
   		form.find('[name=level]').val(ini.find('.level').html());
   		form.find('[name=parent_id]').val(ini.find('.parent_id').html());
   		form.find('[name=status_aktif]').val(ini.find('[name=status_aktif]').val());
   		form.find("[name=level]").change();
   			
   	});

   	$('.btn-add-detail-menu').click(function(){
   		form = $('#form_detail_add_data');
   		var url = form.attr("action");
   		//ga bisa untuk multidimensional array
	    var formData = $(form).serializeArray();
		$('#form_detail_add_data').submit();
   	});

   	$('.btn-edit-detail-menu').click(function(){
   		$('#form_detail_edit_data').submit();
   	});

   	//=================================================

   	$("[name=level]").change(function(){
   		var form = $(this).closest("form");
		var urutan = form.find("[name=urutan]").val();
		var level = $(this).val();
   		// alert($(this).val());
   		if (level == 1) {
   			//link langsung
   			form.find(".parent-id").hide();
   			form.find(".link-href").show();
   			form.find(".urutan").show();
   		}else if(level == 2){
   			form.find(".parent-id").show();
   			form.find(".urutan").show();
   			form.find(".link-href").show();
   		}else if(level == 3){
   			form.find(".parent-id").hide();
   			form.find(".link-href").hide();
   			form.find(".urutan").show();
   		}else if(level == 99){
   			form.find(".parent-id").hide();
   			form.find(".urutan").hide();
   			form.find("[name=urutan]").val('99');
   			form.find(".link-href").show();
   		}

   		if (level == 1 || level == 2 || level == 3) {
   			if (urutan == 99) {
	   			form.find("[name=urutan]").val('');
   			};
   		};

   	});
});
</script>
