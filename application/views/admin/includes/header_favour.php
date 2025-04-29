<!DOCTYPE html>
<html lang="en" class="no-js" style="background: #3b434c;">
<!-- BEGIN HEAD -->
<head>
<meta charset="utf-8"/>
<title><?=$breadcrumb_title?> - FAVOURs v2.4.01 - Sistem</title>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta content="width=device-width, initial-scale=1" name="viewport"/>
<meta content="" name="description"/>
<meta content="" name="author"/>
<meta name="theme-color" content="#1b1b1b">
<script type="text/javascript">
  var baseurl = "<?php print base_url(); ?>";
  	function btn_disabled_load(ini){
	    $(".btn-active").prop('disabled',true);
	    // ini.prop('disabled',true);
	    ini.html("<i class='fa fa-upload'></i> load...");
	}

</script>

<?php include("stylesheet_favour.php"); ?>
<?php include("script.php"); ?>


</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<!-- DOC: Apply "page-header-fixed-mobile" and "page-footer-fixed-mobile" class to body element to force fixed header or footer in mobile devices -->
<!-- DOC: Apply "page-sidebar-closed" class to the body and "page-sidebar-menu-closed" class to the sidebar menu element to hide the sidebar by default -->
<!-- DOC: Apply "page-sidebar-hide" class to the body to make the sidebar completely hidden on toggle -->
<!-- DOC: Apply "page-sidebar-closed-hide-logo" class to the body element to make the logo hidden on sidebar toggle -->
<!-- DOC: Apply "page-sidebar-hide" class to body element to completely hide the sidebar on sidebar toggle -->
<!-- DOC: Apply "page-sidebar-fixed" class to have fixed sidebar -->
<!-- DOC: Apply "page-footer-fixed" class to the body element to have fixed footer -->
<!-- DOC: Apply "page-sidebar-reversed" class to put the sidebar on the right side -->
<!-- DOC: Apply "page-full-width" class to the body element to have full width page without the sidebar menu -->
<?//if (is_posisi_id() == 1) {?>
	<!--Start of Tawk.to Script-->
	<script type="text/javascript">
	/*var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
	(function(){
	var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
	s1.async=true;
	s1.src='https://embed.tawk.to/5cb55828d6e05b735b42c36b/default';
	s1.charset='UTF-8';
	s1.setAttribute('crossorigin','*');
	s0.parentNode.insertBefore(s1,s0);
	})();

	Tawk_API = Tawk_API || {};
	Tawk_API.visitor = {
	    name  : 'favour-<?=is_username();?>',
	};*/
	</script>
	<!--End of Tawk.to Script-->
<?//}?>
<body class="page-header-menu-fixed">

<div class="page-header" style='height:auto'>
	<!-- BEGIN HEADER INNER -->

	<div class='page-header-top'>
		<div class="container">
			<div class="page-logo" style='width:355px'>
				<a href="<?=base_url();?>admin"><h1 style='color:#F44336'>FAVOURTDJ <b style='font-size:0.5em; color:lightblue'>v.2.4.01</b></h1></a>
			</div>
			<a href="javascript:;" class="menu-toggler"></a>
			
			<div class='top-menu'>
				<ul class="nav navbar-nav pull-right">
					<?if (is_posisi_id() != 6) { ?>
						<li class="dropdown dropdown-extended dropdown-dark dropdown-notification" id="header_limit">
							<a style='font-weight:bold; color:white' href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
								LIMIT
								<?
									$limit_warning = get_limit_belanja_warning();
									$limit_jtw = get_limit_jatuh_tempo_warning(); 
									$count_warning = $limit_warning->num_rows() + $limit_jtw->num_rows();
									
									foreach ($limit_warning->result() as $row) {
										$lw[$row->id] = $row;
										$customer[$row->id] = true;
									}

									foreach ($limit_jtw->result() as $row) {
										$jtw[$row->customer_id] = $row;
										if (isset($customer[$row->customer_id])) {
											$count_warning--;
										}
									}
									if($count_warning > 0 ){?>
										<span class="badge badge-default">
										<?=$count_warning ;?> </span>
									<?}
								?>
							</a>
							<ul class="dropdown-menu">
								<li class="external">
									<h3><strong>Daftar LIMIT Warning</strong></h3>
								</li>
								<li>
									<ul class="dropdown-menu-list scroller" style="height: 250px;" data-handle-color="#637283">
										<?$i=1;foreach ($lw as $k => $v) {
											if (isset($jtw[$k])) {
												$no_faktur_jt = explode(",", $jtw[$k]->no_faktur);
												$amount_jt = explode(",", $jtw[$k]->amount_data);
												$tgl_jt = explode(",", $jtw[$k]->jatuh_tempo);
												$tgl_jl = explode(",", $jtw[$k]->tanggal);
												$amount_jt_all = $jtw[$k]->amount;
											}
											?>
										<li>
											<a>
											<span style="float:right; background:#000; padding:0 5px ;<?=($v->sisa_limit < 0 ? 'color:red' : '' )?>">sisa:<?=number_format($v->sisa_limit,'0',',','.');?></span>
											<span class="details">
											<?='<b>'.$i.'.</b> '.$v->tipe_company.$v->nama;$i++;?></span>
											<?if (isset($jtw[$v->id]) > 0) {?>
												<hr style="padding:2px; margin:0px" />
												<span style="float:right; background:#111; padding:0 5px ; color:red">Jatuh Tempo:<?=number_format($amount_jt_all,'0',',','.');?></span>
												<table>
													<tr>
														<th>Invoice</th>
														<th>Jth Tempo</th>
														<th class='text-center'>Amount</th>
													</tr>
													<?foreach ($no_faktur_jt as $key => $value) {?>
														<tr>
															<td><?=$value?></td>
															<td class='text-center'><?=$tgl_jt[$key]?></td>
															<td class='text-center'><?=number_format($amount_jt[$key],'0',',','.')?></td>
														</tr>
													<?}?>
												</table>
											<?
											unset($jtw[$v->id]);
											}?>
											</a>
										</li>
										<?}
										// if (is_posisi_id() == 1) {
											foreach ($jtw as $key => $val) {
												$no_faktur_jt = explode(",", $val->no_faktur);
												$amount_jt = explode(",", $val->amount_data);
												$tgl_jt = explode(",", $val->jatuh_tempo);
												$tgl_jl = explode(",", $val->tanggal);
												?>
												<li>
													<a>
														<span style="float:right; background:#111; padding:0 5px ; color:red">Jatuh Tempo:<?=number_format($val->amount,'0',',','.');?></span>
														<span class="details">
														<?='<b>'.$i.'.</b> '.$val->tipe_company.$val->nama;$i++;?></span>
														<table>
															<tr>
																<th>Invoice</th>
																<th>Jth Tempo</th>
																<th class='text-center'>Amount</th>
															</tr>
															<?foreach ($no_faktur_jt as $key => $value) {?>
																<tr>
																	<td><?=$value?></td>
																	<td class='text-center'><?=$tgl_jt[$key]?></td>
																	<td class='text-center'><?=number_format($amount_jt[$key],'0',',','.')?></td>
																</tr>
															<?}?>
														</table>
													</a>
												</li>
											<?}
										// }
										?>
									</ul>
								</li>

							</ul>
						</li>

						<li class="dropdown dropdown-extended dropdown-dark dropdown-notification" id="header_notification_bar">
							<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
							<i class="icon-wrench"></i>
							</a>
							<ul class="dropdown-menu">
								<li class="external">
									<h3><strong>Tools</strong></h3>
								</li>
								<li>
									<ul class="dropdown-menu-list scroller" style="height: 250px;" data-handle-color="#637283">
										<li>
											<a href="#portlet-config-cek-harga" data-toggle='modal'>
											<span class="time">28/07/2018</span>
											<span class="details">
											<span class="label label-sm label-icon label-success">
											<i class="fa fa-search"></i>
											</span>
											Cari Harga Barang. </span>
											</a>
										</li>
										<?if (is_posisi_id() <= 3) { ?>
											<li>
												<a href="#portlet-config-akunting-notification" data-toggle='modal'>
												<span class="time">06/08/2018</span>
												<span class="details">
												<span class="label label-sm label-icon label-success">
												<i class='fa fa-file-text-o'></i>
												</span>
												Send Notif ke Akunting. </span>
												</a>
											</li>
										<?}?>
									</ul>
								</li>

							</ul>
						</li>

						<?/*<li class="dropdown dropdown-extended dropdown-tasks" id="header_task_bar" style="display:none">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
							<i class="icon-calendar"></i>
							
							</a>
							<ul class="dropdown-menu extended tasks"  style="height: 400px; overflow:scroll" >
								<li class="external">
									<h3>You have <span class="bold"><?=get_note_order_row();?> pending</span> tasks</h3>
									<a href="<?=base_url('admin/dashboard');?>">view all</a>
								</li>
								<li>
									<ul class="dropdown-menu-list scroller" style="height: 70px;" data-handle-color="#637283">
										<li>
											<a href="#portlet-config-note-order" class='btn-note-order' data-toggle='modal'>
											<span class="details">
											<span class="label label-sm label-icon label-success">
											<i class="fa fa-plus"></i>
											</span>
											Note Order </span>
											</a>
										</li>
									</ul>
									<ul>
										<?foreach (get_note_order() as $row) {
											$nama_barang = explode('??', $row->nama_barang);
											$nama_warna = explode(',', $row->nama_warna);

											$note_show = '';
											foreach ($nama_barang as $key => $value) {
												$note_show .= "<b>".$value.' '.$nama_warna[$key].'</b> & ';
											}
											?>
											<li><?=$note_show;?> <b>u/</b>  <?=$row->nama_customer;?> <?=($row->status == 1 ? "- <i style='color:blue' class='fa fa-check'></i> -" : ($row->status == -1 ? "- <span>batal</span> -" : ($row->matched == 1 ? "- <span style='color:red'><i class='fa fa-flag'></i></span> -" : "" ) ) )?></li>
										<?}?>
									</ul>
										<hr style='margin:5px;'>
										<a ><b>Upcoming Target : </b></a>
									<ul>
										<?foreach (get_note_order_target() as $row) {
											$nama_barang = explode(',', $row->nama_barang);
											$nama_warna = explode(',', $row->nama_warna);

											$note_show = '';
											foreach ($nama_barang as $key => $value) {
												$note_show .= "<b>".$value.' '.$nama_warna[$key].'</b> & ';
											}
											?>
											<li><?=is_reverse_date($row->tanggal_target)?> : <?=$note_show;?> <b>u/</b>  <?=$row->nama_customer;?></li>
										<?}?>
									</ul>
								</li>
							</ul>
						</li>*/?>
					<?}?>

					<?if (is_posisi_id() <= 3 || is_posisi_id() == 6) {
							$piutang_warn = get_piutang_warn();?>
						<li class="dropdown dropdown-extended dropdown-tasks" >
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
							<i class="fa fa-bell"></i>
							<?
								if($piutang_warn->num_rows() != 0){?>
									<span class="badge badge-default">
									<?=$piutang_warn->num_rows() ;?> </span>
								<?}
							?>
							</a>
							<ul class="dropdown-menu extended tasks"  style="height: 400px; overflow:scroll" >
								<li>
									<a><b>Jatuh Tempo : </b></a>
									<ul>
										<?foreach ($piutang_warn->result() as $row) {?>
											<li>
												<a target='_blank' style='padding:0px; color:blue; display:inline' href="<?=base_url().is_setting_link('finance/piutang_payment_form').'?customer_id='.$row->customer_id;?>&toko_id=<?=$row->toko_id;?>&tanggal_start=<?=$row->tanggal_start;?>&tanggal_end=<?=$row->tanggal_end;?>&status_jt=1"> 
													<?=$row->nama_customer;?> : <b> 
												</a>
												<?=$row->counter_invoice;?> </b> invoice jatuh tempo, nilai : <b> <?=number_format($row->sisa_piutang,'0',',','.')?> </b> </li>
										<?}?>
									</ul>
								</li>
							</ul>
						</li>
					<?}?>

					<?/*<li class="dropdown dropdown-extended dropdown-dark dropdown-notification" id="header_notification_bar">
						<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
							<i class="icon-bell"></i>
							<span class="badge badge-default">7</span>
						</a>
						<ul class="dropdown-menu extended tasks">
							<li class="external">
								<h3>Reserved <strong>Pending</strong> tasks</h3>
								<a href="javascript:;">view all</a>
							</li>
							<li>
								<ul class="dropdown-menu-list scroller" style="height: 250px;" data-handle-color="#637283">
									<li>
										<a href="javascript:;">
										<span class="time">Timeline</span>
										<span class="details">
										<span class="label label-sm label-icon label-success">
										<i class="fa fa-plus"></i>
										</span>
										Message. </span>
										</a>
									</li>
								</ul>
							</li>
						</ul>
					</li>*/?>

					<li class="dropdown dropdown-user dropdown-dark">
						<a href="#" style='background:transparent' class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
						<img alt="" class="img-circle" style='top:5px;position:relative;height:30px' src="<? echo base_url('assets/admin/layout/img/avatar.png'); ?>"/>
						<span style='' class="username username-hide-on-mobile">
						<?=is_username(); ?> </span>
						<i class="fa fa-angle-down"></i>
						</a>
						<ul class="dropdown-menu dropdown-menu-default">
							<li>
								<a href="<? echo base_url('home/logout');?>">
								<i class="icon-key"></i> Log Out </a>
							</li>
						</ul>
					</li>

				</ul>
			</div>
		</div>
	</div>

	<div class="page-header-menu">
		<div class='container'>
			<? include('sidebar.php'); ?>
		</div>
	</div>
	<!-- END HEADER INNER -->
</div>


<!-- BEGIN HEADER -->
<!-- END HEADER -->
<!-- BEGIN CONTAINER -->
<div class="page-container">
	<!-- BEGIN SIDEBAR -->
	<!-- END SIDEBAR -->
	<!-- BEGIN CONTENT -->
	<div class="page-content-wrapper">

		<div class='page-head'>
			<div class='container'>
				<div class="page-title">
					<h1><?=$breadcrumb_title;?> <small><?=$breadcrumb_small;?></small></h1> 
				</div>
					
			</div>
		</div>

		