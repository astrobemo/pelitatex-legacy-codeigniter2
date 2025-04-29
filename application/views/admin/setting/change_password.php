<div class="page-content">
	<div class='container'>
		<div class="row margin-top-10">
			<div class="col-md-12">
				<div class="portlet light">
					<div class="portlet-title">
						<div class="caption caption-md">
							<i class="icon-bar-chart theme-font hide"></i>
							<span class="caption-subject theme-font bold uppercase">Ganti Password</span>
						</div>
						<div class="actions">
							
						</div>
					</div>
					<div class="portlet-body">
						<form action="<?=base_url()?>admin/update_password" class="form-horizontal" id="form_change_password" method="POST">
							<div class="form-body">
							<h3 class="block"></h3>
								<!-- <div class="form-group">
					                <label class="control-label col-md-3">Old Password<span class="required">
					                    * </span>
					                 </label>
					                 <div class="col-md-4">
					                   <input type="password" class="form-control" name="old_password"/>
					                   <span class="help-block">
					                   Old Password. </span>
					                 </div>
					             </div>
-->
					             <div class="form-group">
					                <label class="control-label col-md-3">New Password<span class="required">
					                    * </span>
					                 </label>
					                 <div class="col-md-4">
					                   <input type="password" class="form-control" name="password"  id='new_password'/>
					                   <span class="help-block">
					                   Provide new password. </span>
					                 </div>
					             </div>

					             <div class="form-group">
					                 <label class="control-label col-md-3">Confirm New Password<span class="required">
					                    * </span>
					                 </label>
					                 <div class="col-md-4">
					                   <input type="password" class="form-control" name="rpassword"/>
					                   <span class="help-block">
					                   Confirm the password </span>
					                 </div>
					             </div>

							</div>
							<div class="form-actions">
				              <div class="row">
				                <div class="col-md-offset-3 col-md-9">
				                  	<button class="btn green btn-save">Save</button>
				                </div>
				              </div>
				            </div>
				        </form>
					</div>
				</div>
			</div>
		</div>
	</div>			
</div>

<script src="<?php echo base_url('assets/global/plugins/jquery-notific8/jquery.notific8.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/global/plugins/jquery-validation/js/jquery.validate.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/jquery-validation/js/additional-methods.min.js'); ?>" type="text/javascript" ></script>
<script src="<?php echo base_url('assets_noondev/js/new_password.js'); ?>"></script>

<script>
$(document).ready(function() { 
   	FormPassword.init();
   	<?if ($n8_message != '') { ?>
   		notific8('lime', '<?=$n8_message;?>');
   	<? } ;?>

});
</script>