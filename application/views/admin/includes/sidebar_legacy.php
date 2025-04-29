<style type="text/css">
.kategorial-header{
	font-size: 0.6em !important;
	padding: 0px 15px !important;
	position: relative;
	opacity: 20%;
}

.kategorial-header span{
	background: #424242;
	padding: 0px 10px;
}



.list-under-kategori{
	padding-left: 5px;
}
.list-under-kategori li{
	list-style-type: none;
}




</style>

<div class="hor-menu">
	<!-- BEGIN DROPDOWN MENU -->
	<ul class="nav navbar-nav">
		<!-- <li id="menu_dashboard">
			<a href="<?=site_url('dashboard');?>">
			<i class="fa fa-home"></i>
			<span class="title">Dashboard</span>
			</a>
		</li> -->

		<?//=print_r($common_data['user_menu_list']);?>
		<?foreach ($common_data['user_menu_list']['menu_list'] as $row) { ?>
			<li class="menu-dropdown classic-menu-dropdown" id="<?=$row->nama_id;?>">

				<a data-click="megamenu-dropdown" data-close-others="true" data-toggle="dropdown" href="javascript:;">
					<i class="<?=$row->icon_class;?>"></i> <span class="title"><?=$row->text;?></span> <i class="fa fa-angle-down"></i>
				</a>
				<ul class="dropdown-menu pull-left" style='max-height:450px; overflow:auto'>
					<? $i = 1;
					foreach ($common_data['user_menu_list']['menu_'.$row->id]['menu_level_1'] as $isi) {?>
							<li id="<?=$isi->page_link;?>">
								<a class="<?=($isi->level == 3 ? 'kategorial-header' : '') ?>" href="<?=($isi->level == 1 ? base_url(is_setting_link($isi->controller.'/'.$isi->page_link)) : '#') ;?>/">
								<span><?=$isi->text;?></span></a>
								<?if ($isi->level==3 && count($common_data['user_menu_list']['menu_'.$row->id]['menu_level_1'][$isi->id]) > 0 ) {?>
									<ul class='list-under-kategori'>
										<?if (isset($common_data['user_menu_list']['menu_'.$row->id]['menu_level_2'][$isi->id])) {
											foreach ($common_data['user_menu_list']['menu_'.$row->id]['menu_level_2'][$isi->id] as $row2) {?>
											<li id="<?=$row2->page_link?>">
											<a href="<?=base_url(is_setting_link($row2->controller.'/'.$row2->page_link));?>/"><?=$row2->text?></a>
											</li>
										<?}
										} ?>
									</ul>
								<?}?>
							</li>
							<?
							$i++;
							?>
					<?}?>
				</ul>
			</li>
		<?}?>

	</ul>
	<!-- END DROPDOWN MENU -->
</div>

