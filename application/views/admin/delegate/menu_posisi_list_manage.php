<div class="col-md-12">
	<div class="portlet light">
		<div class="portlet-title">
			<div class="caption caption-md">
				<i class="icon-bar-chart theme-font hide"></i>
				<span class="caption-subject theme-font bold uppercase">Atur Level</span>
			</div>
			<div class="actions">
				
			</div>
		</div>
		<div class="portlet-body">
			<div style='height:400px;overflow:auto'>
				<table class="table table-striped table-bordered table-hover" id="general_table" >
					<thead>
						<tr>
							<th scope="col">
								Menu
							</th>
						</tr>
					</thead>
					<tbody>
						<?
						$menu_que = '';
						$menu_detail_que = '';
						foreach ($menu_detail as $row) { 
							$menu_d[$row->menu_id][$row->id] = $row->text;
						}

						foreach ($menu_posisi_list as $row) {
							$menu_que = $row->menu_id;
							$menu_detail_que = $row->menu_detail_id;
						}

						foreach (explode('??', $menu_que) as $key => $value) {
							if ($key > 0 ) {
								$menu_user_list[$value] = true;
							}
						}

						foreach (explode('??', $menu_detail_que) as $key => $value) {
							if ($key > 0 ) {
								$menu_detail_user_list[$value] = true;
							}
						}


						?>
						<tr>
							<td>
								<?foreach ($menu_list as $row) { ?>
									<div class='menu_section'>
										<input type="checkbox" class='<?=$row->id;?>' name='menu_id' <?if (isset($menu_user_list[$row->id])) { echo 'checked'; }?> >
										<?=$row->text;?> 
										<i style='margin-left:3px;cursor:pointer;' class='fa fa-minus-square-o btn-min'></i>
										<i style='margin-left:3px;cursor:pointer;display:none' class='fa fa-plus-square-o btn-plus'></i>
										<br/>
										<div class='sub_menu' style="padding:5px 0 10px 20px;">
											<?
											if (isset($menu_d[$row->id])) {
												foreach ($menu_d[$row->id] as $key => $value) { ?>
													<label><input type="checkbox" class='<?=$key;?>' name='menu_detail_id' <?if (isset($menu_detail_user_list[$key])) { echo 'checked'; }?> >
													<?=$value;?></label>
													<br/>
												<? }
											}
											?>
										</div>
										<hr style="margin:5px 0;border-top:1px dashed #ddd"/>								
									</div>
								<?}?>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			<form hidden action='<?=base_url();?>delegate/menu_posisi_update' id="form_edit_data" method="post">
				<input name='posisi_id' value="<?=$posisi_id;?>">
				<input name="menu_id" value="<?=$menu_que;?>">
				<input name="menu_detail_id" value="<?=$menu_detail_que;?>">
			</form>
		</div>
	</div>
</div>

<div class="modal-footer">
	<button type="button" class="btn default" data-dismiss="modal">Close</button>
	<button type="button" class="btn blue btn-save">Save changes</button>
</div>

<script>
jQuery(document).ready(function() {   
	//$("#sidebar").load("sidebar.html"); 
   	Metronic.init(); // init metronic core componets
   	Layout.init(); // init layout
   	//TableAdvanced.init();
   	$("#menu_delegate").addClass("active open");
   	$("#menu_delegate .title").after('<span class="selected"></span>');
   	$("#menu_delegate span.arrow").addClass("open");
   	$("#manage_user_level").addClass("active");

   	$('#general_table').on('click', '.btn-plus', function(){
   		var ini = $(this).closest('.menu_section');
   		$(this).hide('fast');
   		ini.find('.sub_menu').show('fast');
   		ini.find('.btn-min').show('fast');

   	});

   	$('#general_table').on('click', '.btn-min', function(){
   		var ini = $(this).closest('.menu_section');
   		$(this).hide('fast');
   		ini.find('.sub_menu').hide('fast');
   		ini.find('.btn-plus').show('fast');
   	});

   	$('#general_table').on('change','[name=menu_id]', function(){
   		// alert('test');
   		var menu_id = $(this).attr('class');
   		var menu_list = $('#form_edit_data [name=menu_id]').val();
   		if (menu_list == null) {menu_list = ''};
   		if ($(this).is(':checked')) {
   			var value = 1;
   			menu_list = menu_list+'??'+menu_id;
   			$('#form_edit_data [name=menu_id]').val(menu_list);
   			// alert(menu_list);
   		}else{
   			var value = 0;
   			menu_list = menu_list.replace("??"+menu_id,"");
   			$('#form_edit_data [name=menu_id]').val(menu_list);
   		};   		
   	});

   	$('#general_table').on('change','[name=menu_detail_id]', function(){
   		var menu_detail_id = $(this).attr('class');
   		var menu_detail_list = $('#form_edit_data [name=menu_detail_id]').val();
   		// alert(menu_detail_list);
   		if (menu_detail_list == null) {menu_detail_list = ''};
   		if ($(this).is(':checked')) {
   			var value = 1;
   			menu_detail_list = menu_detail_list+'??'+menu_detail_id;
   			$('#form_edit_data [name=menu_detail_id]').val(menu_detail_list);
   			// alert(menu_list);
   		}else{
   			var value = 0;
   			menu_detail_list = menu_detail_list.replace("??"+menu_detail_id,"");
   			$('#form_edit_data [name=menu_detail_id]').val(menu_detail_list);
   		};   		
   	});

   	$('.btn-save').click(function(){
   		$('#form_edit_data').submit();
   	});

});