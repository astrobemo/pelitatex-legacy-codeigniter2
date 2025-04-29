<div class="page-content">
	<div class='container'>
		<div class="row margin-top-10">
			<div class="col-md-12">
				<div class="portlet light">
					<div class="portlet-title">
						<div class="caption caption-md">
							<i class="icon-bar-chart theme-font hide"></i>
							<span class="caption-subject theme-font bold uppercase">Change Default Printer</span>
						</div>
						<div class="actions">
							
						</div>
					</div>
					<div class="portlet-body">
						<div class="portlet-body form">
							<form action="<?=base_url();?>admin/user_printer_list_update" class="form-horizontal" id="form_change_printer" method="POST">
								<div class="form-body">
									<h3 class="block"></h3>
							            <div class="form-group">
							                <label class="control-label col-md-3">Pilih Printer Default
							                 </label>
							                 <div class="col-md-4">
							                   	<select class='form-control' name='printer_list_id' id='printer-name'>
						                    		<?foreach ($printer_list as $row) { ?>
						                    			<option  <?=(get_default_printer() == $row->id ? 'selected' : '');?> value='<?=$row->id;?>'><?=$row->nama;?> <?=(get_default_printer() == $row->id ? '(default)' : '');?></option>
						                    		<?}?>
						                    	</select>
							                   <span class="help-block">
							                   Pilih dari daftar printer. </span>
							                 </div>
							            </div>

								</div>
					        </form>
					        <div class="row">
				                <div class="col-md-offset-3 col-md-9">
				                  <button class="btn green btn-save">Save</button>
				                </div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>			
</div>

<script src="<?php echo base_url('assets/global/plugins/jquery-notific8/jquery.notific8.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/global/plugins/jquery-validation/js/jquery.validate.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/jquery-validation/js/additional-methods.min.js'); ?>" type="text/javascript" ></script>
<script src="<?php echo base_url('assets/global/plugins/bootbox/bootbox.min.js'); ?>" type="text/javascript"></script>

<script>
$(document).ready(function() { 
	$('.btn-save').click(function(e){
		// alert('test');
		e.preventDefault();
   		if($("#form_change_printer [name=printer_list_id]").val() != ''){
   			$("#form_change_printer").submit();
   		}else{
   			bootbox.alert('Mohon Pilih Printer');
   		}
   	});

   	<?if ($n8_message != '') { ?>
   		var settings = {
	        theme: 'lime',
	        sticky: false,
	        horizontalEdge: "bottom",
	        verticalEdge: "right",
	        heading: "Message",
	        life: 5000
	    };
	    $.notific8('zindex', 11500);
	    $.notific8('Printer Default Updated', settings);
   	<?};?>	
});
</script>