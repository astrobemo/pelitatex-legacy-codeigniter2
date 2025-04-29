<!DOCTYPE html>
<!-- 
Template Name: Metronic - Responsive Admin Dashboard Template build with Twitter Bootstrap 3.3.2
Version: 3.3.0
Author: KeenThemes
Website: http://www.keenthemes.com/
Contact: support@keenthemes.com
Follow: www.twitter.com/keenthemes
Like: www.facebook.com/keenthemes
Purchase: http://themeforest.net/item/metronic-responsive-admin-dashboard-template/4021469?ref=keenthemes
License: You must have a valid license purchased only from themeforest(the above link) in order to legally use the theme for your project.
-->
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
<meta charset="utf-8"/>
<title>FAITHFULTDJ | Login Options - Login Form</title>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<meta http-equiv="Content-type" content="text/html; charset=utf-8">
<link rel="shortcut icon" href="<?=base_url();?>image/icon.png" type="image/x-icon">
<meta content="" name="description"/>
<meta content="" name="author"/>
<!-- BEGIN GLOBAL MANDATORY STYLES -->
<?php echo link_tag('assets_noondev/css/google-font-open-sans-400-300-600-700.css'); ?>
<?php echo link_tag("assets/global/plugins/font-awesome/css/font-awesome.min.css"); ?>
<?php echo link_tag("assets/global/plugins/simple-line-icons/simple-line-icons.min.css");  ?>
<?php echo link_tag("assets/global/plugins/bootstrap/css/bootstrap.min.css");  ?>
<?php echo link_tag("assets/global/plugins/uniform/css/uniform.default.css");  ?>
<!-- END GLOBAL MANDATORY STYLES -->
<!-- BEGIN PAGE LEVEL STYLES -->
<?php echo link_tag("assets/admin/pages/css/login2.css");  ?>
<?php echo link_tag("assets/global/css/components-rounded.css");  ?>
<!-- END PAGE LEVEL SCRIPTS -->
<!-- BEGIN THEME STYLES -->
<?php echo link_tag("assets/global/css/plugins.css");  ?>
<?php echo link_tag("assets/global/css/components-rounded.css");  ?>

<!-- END THEME STYLES -->
<link rel="shortcut icon" href="<? echo base_url('assets_noondev/img/no_img.png'); ?>"/>
<style type="text/css">
.login{
	background:#e05d25;
}

.login .content .form-control{
	background: transparent;
	color:#fff;
}

.login .btn-primary{
	background: transparent;
	color:#ddd;
}

.login .btn-primary:hover{
	background: transparent;
	color:#fff;
	border:1px solid #fff;
}

</style>

</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<body class="login">
<!-- BEGIN SIDEBAR TOGGLER BUTTON -->
<div class="menu-toggler sidebar-toggler">
</div>
<!-- END SIDEBAR TOGGLER BUTTON -->
<!-- BEGIN LOGO -->
<div class="logo">
	
</div>
<!-- END LOGO -->
<!-- BEGIN LOGIN -->
<div class="content">
	<!-- BEGIN LOGIN FORM -->
	<form class="login-form" action="<? echo base_url('home/login_soft');?>" method="post">
		<div class="form-title">
			<span class="form-title">Welcome.</span>
			<span class="form-subtitle">Please login.</span>
		</div>
		<?php if(validation_errors()){?>
			<div class="alert alert-block alert-warning fade in">
				<?php echo validation_errors(); ?>
			</div>
		<?php }?>
		<div class="alert alert-danger display-hide">
			<button class="close" data-close="alert"></button>
			<span>
			Enter any username and password. </span>
		</div>
		<div class="form-group">
			<!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
			<label class="control-label visible-ie8 visible-ie9">Username</label>
			<div class="input-icon">
				<i class="fa fa-user"></i>
				<input class="form-control placeholder-no-fix" type="text" autocomplete="off" placeholder="Username" name="username"/>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label visible-ie8 visible-ie9">Password</label>
			<div class="input-icon">
				<i class='fa fa-lock'></i> 
				<input class="form-control placeholder-no-fix" type="password" autocomplete="off" placeholder="Password" name="password"/>
			</div>
		</div>
		<div class="form-actions">
			<button type="submit" class="btn btn-primary btn-block uppercase">Login</button>
		</div>
		<!-- <div class="create-account" style='padding:10px 0 30px 0; background:transparent;'>
			<p style=" color:white">
				 SYSTEM
			</p>
		</div> -->
	</form>
	<!-- END LOGIN FORM -->
	
</div>
<div class="copyright">
	 <!-- 2017© Noondev. -->
</div>
<!-- END LOGIN -->
<!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
<!-- BEGIN CORE PLUGINS -->
<!--[if lt IE 9]>
<script src="../../assets/global/plugins/respond.min.js"></script>
<script src="../../assets/global/plugins/excanvas.min.js"></script> 
<![endif]-->
<script src="<? echo base_url('assets/global/plugins/jquery.min.js'); ?>" type="text/javascript"></script>
<script src="<? echo base_url('assets/global/plugins/jquery-migrate.min.js'); ?>" type="text/javascript"></script>
<script src="<? echo base_url('assets/global/plugins/bootstrap/js/bootstrap.min.js'); ?>" type="text/javascript"></script>
<script src="<? echo base_url('assets/global/plugins/jquery.blockui.min.js'); ?>" type="text/javascript"></script>
<script src="<? echo base_url('assets/global/plugins/uniform/jquery.uniform.min.js'); ?>" type="text/javascript"></script>
<!--script src="<? echo base_url('assets/global/plugins/jquery.cokie.min.js'); ?>" type="text/javascript"></script-->
<!-- END CORE PLUGINS -->
<!-- BEGIN PAGE LEVEL PLUGINS -->
<script src="<? echo base_url('assets/global/plugins/jquery-validation/js/jquery.validate.min.js'); ?>" type="text/javascript"></script>
<!-- END PAGE LEVEL PLUGINS -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="<? echo base_url('assets/global/scripts/metronic.js'); ?>" type="text/javascript"></script>
<script src="<? echo base_url('assets/admin/pages/scripts/login.js'); ?>" type="text/javascript"></script>
<!-- END PAGE LEVEL SCRIPTS -->
<script>
jQuery(document).ready(function() {     
	Metronic.init(); // init metronic core components
	// Layout.init(); // init current layout
	Login.init();
	// Demo.init();

	setTimeout(function(){
		$('.login-form [name=username]').focus();
	},1000)
	
});
</script>
<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>