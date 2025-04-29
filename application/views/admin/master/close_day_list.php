<div class="page-content">
	<div class='container'>
		
		<div class="modal fade" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body">
						<form action="<?=base_url('master/close_day_list_insert')?>" class="form-horizontal" id="form_add_data" method="post">
							<h3 class='block'> Tambah </h3>
				                <div class="form-group">
				                    <label class="control-label col-md-3">Tanggal<span class="required">
				                    * </span>
				                    </label>
				                    <div class="col-md-6">
				                    	<div class="input-group input-large date-picker input-daterange" data-date-format="mm/dd/yyyy">
											<input type="text" class="form-control" name="tanggal_start" autocomplete='off'>
											<span class="input-group-addon">
											to </span>
											<input type="text" class="form-control" name="tanggal_end" autocomplete='off'>
										</div>
				                    </div>				                    
				                </div>

				                <div class="form-group">
				                    <label class="control-label col-md-3">Keterangan
				                    </label>
				                    <div class="col-md-6">
				                    	<input type="text" class="form-control" name="keterangan"/>
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

		<div class="modal fade" id="portlet-config-edit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body">
						<form action="<?=base_url('master/close_day_list_update')?>" class="form-horizontal" id="form_edit_data" method="post">
							<h3 class='block'> Edit </h3>
				                <div class="form-group">
				                    <label class="control-label col-md-3">Tanggal<span class="required">
				                    * </span>
				                    </label>
				                    <div class="col-md-6">
				                    	<input name="close_day_id" hidden='hidden'/>
				                    	<div class="input-group input-large date-picker input-daterange" data-date-format="mm/dd/yyyy">
											<input type="text" class="form-control" name="tanggal_start" autocomplete='off'>
											<span class="input-group-addon">
											to </span>
											<input type="text" class="form-control" name="tanggal_end" autocomplete='off'>
										</div>
				                    </div>				                    
				                </div>

				                <div class="form-group">
				                    <label class="control-label col-md-3">Keterangan
				                    </label>
				                    <div class="col-md-6">
				                    	<input type="text" class="form-control" name="keterangan"/>
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
							<a href="#portlet-config" data-toggle='modal' class="btn btn-default btn-sm btn-form-add">
							<i class="fa fa-plus"></i> Tambah </a>
						</div>
					</div>
					<div class="portlet-body">
						<table class="table table-striped table-bordered table-hover" id="general_table">
							<thead>
								<tr>
									<th scope="col">
										Tanggal
									</th>
									<th scope="col">
										Keterangan
									</th>
									<th scope="col" style="min-width:150px !important">
										Actions
									</th>
								</tr>
							</thead>
							<tbody>
								<?foreach ($close_day_list as $row) { ?>
									<tr class='status_aktif_<?=$row->status_aktif;?>'>
										<td>
											<span class='tanggal_start'><?=is_reverse_date($row->tanggal_start);?></span> s/d 
											<span class='tanggal_end'><?=is_reverse_date($row->tanggal_end);?></span> 
										</td>
										<td>
											<span class='keterangan'><?=$row->keterangan;?></span> 
										</td>
										<td>
											<span class='id' hidden="hidden"><?=$row->id;?></span>
											<a href='#portlet-config-edit' data-toggle='modal' class="btn-xs btn green btn-edit"><i class="fa fa-edit"></i></a>
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

<script>
jQuery(document).ready(function() {       
   
   	$('#general_table').on('click', '.btn-edit', function(){
   		$('#form_edit_data [name=close_day_id]').val($(this).closest('tr').find('.id').html());
   		$('#form_edit_data [name=tanggal_start]').val($(this).closest('tr').find('.tanggal_start').html());
   		$('#form_edit_data [name=tanggal_end]').val($(this).closest('tr').find('.tanggal_end').html());
   		$('#form_edit_data [name=keterangan]').val($(this).closest('tr').find('.keterangan').html());
   	});

   	$('.btn-save').click(function(){
   		var tanggal_start = date_format_default($('#form_add_data [name=tanggal_start]').val()); 
   		var tanggal_end = date_format_default($('#form_add_data [name=tanggal_end]').val()); 
   		if( tanggal_start != '' && tanggal_end != '' ){
   			if (new Date(tanggal_start) <= new Date(tanggal_end) ) {
	   			$('#form_add_data').submit();
   			}else{
   				alert("Tanggal Invalid");
   			};
   		}
   	});

   	$('.btn-edit-save').click(function(){
   		var tanggal_start = date_format_default($('#form_edit_data [name=tanggal_start]').val()); 
   		var tanggal_end = date_format_default($('#form_edit_data [name=tanggal_end]').val()); 
   		if( tanggal_start != '' && tanggal_end != '' ){
   			if (new Date(tanggal_start) <= new Date(tanggal_end) ) {
	   			$('#form_edit_data').submit();
   				// alert('OK');
   			}else{
   				alert("Tanggal Invalid");
   			};
   		}
   	});

   	$('#general_table').on('click', '.btn-remove', function(){
   		var data = status_aktif_get($(this).closest('tr'))+'=?=close_day';
   		window.location.replace("master/ubah_status_aktif?data_sent="+data+'&link=close_day_list');
   	});
});
</script>