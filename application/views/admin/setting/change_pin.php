<div class="page-content">
	<div class='container'>
		<div class="row margin-top-10">
			<div class="col-md-12">
				<div class="portlet light">
					<div class="portlet-title">
						<div class="caption caption-md">
							<i class="icon-bar-chart theme-font hide"></i>
							<span class="caption-subject theme-font bold uppercase">Change PIN</span>
						</div>
						<div class="actions">
							
						</div>
					</div>
					<div class="portlet-body">
						<div class="portlet-body form">
							<form action="<?=base_url();?>admin/update_pin" class="form-horizontal" id="form_change_code" method="POST">
								<div class="form-body">
									<h3 class="block"></h3>
							            <div class="form-group">
							                <label class="control-label col-md-3">Kode Unik Anda
							                 </label>
							                 <div class="col-md-4">
							                   <input class="form-control" name="PIN" maxlength="6"/>
							                   <span class="help-block">
							                   Kode unik anda max 6 character, tidak boleh kosong. </span>
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
   		if($("#form_change_code [name=PIN]").val().length == 6){
   			$("#form_change_code").submit();
   		}else{
   			bootbox.alert('Kode harus terdiri atas 6 karekter');
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
	    $.notific8('PIN Updated', settings);
   	<?};?>	
});
</script>