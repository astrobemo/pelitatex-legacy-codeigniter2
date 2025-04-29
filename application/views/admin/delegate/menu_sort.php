<style type="text/css">
.menu_list_detail li{
	cursor: pointer;
	font-size: 1.2em;
}


/*#ajax-modal{
	width: 900px;
	left: 20%;
}

.menu_list_detail { 
	list-style-type: none; 
	width: 40%;
	font-size: 1.2em; 
}

.menu_list_detail li{
	padding: 2px; 
	background: #ddd;
	margin: 2px; 
	border:2px solid blue;	
}

.menu_list{
	font-size: 1.2em;
}*/

/*.menu_list_detail { list-style-type: none; margin: 5px; padding: 2px; width: 60%; background: #ddd }
.menu_list_detail li { margin: 0 3px 3px 3px; padding: 0.4em; padding-left: 1.5em; font-size: 1.4em; height: 18px; }
.menu_list_detail li span { position: absolute; margin-left: -1.3em; }
*/

</style>

<style>
#div1, #div2 {
  float: left;
  width: 100px;
  /*height: 35px;*/
  margin: 10px;
  padding: 10px;
  border: 1px solid black;
}
</style>
<script>
/*
function allowDrop(ev) {
  ev.preventDefault();
}

function drag(ev) {
  ev.dataTransfer.setData("text", ev.target.id);
}

function drop(ev) {
	ev.preventDefault();
	var data = ev.dataTransfer.getData("text");
	ev.currentTarget.appendChild(document.getElementById(data));
	// console.log(this.contains('no-drop'));
}*/
</script>

<link href="<?=base_url('assets/global/plugins/bootstrap-modal/css/bootstrap-modal.css');?>" rel="stylesheet" type="text/css"/>
<link href="<?=base_url('assets_noondev/css/bootstrap-modal-bs3patch.css');?>" rel="stylesheet" type="text/css"/>
<div class="page-content">
	<div class='container'>
		

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
						<h2>Drag and Drop</h2>
						<p>Drag the image back and forth between the two div elements.</p>

						<!-- <div id="div1" ondrop="drop(event)" ondragover="allowDrop(event)" style='color:red'>
						  <p draggable="true" class='no-drop' ondragstart="drag(event)" id="drag1" width="88" height="31">TEST</p>
						  <p draggable="true" class='no-drop' ondragstart="drag(event)" id="drag2" width="88" height="31">TEST2</p>
						</div>

						<div id="div2" ondrop="drop(event)" ondragover="allowDrop(event)" style='color:blue'>
						  <p draggable="true" class='no-drop' ondragstart="drag(event)" id="drag3" width="88" height="31">TEST3</p>
						</div> -->

						<form action='<?=base_url()?>delegate/menu_sort_update' method='post'>
							<ul class='menu_list'>
								<?foreach ($menu_list as $row) {?>
									<li><?=$row->text;?></li>
									<ul class='menu_list_detail'>
										<?foreach ($menu_list_detail[$row->id] as $row2) {?>
											<li class="ui-state-default">
												<span class="ui-icon ui-icon-arrowthick-2-n-s"></span><?=$row2->text;?>
												<input name='menu_<?=$row->id?>[]' value="<?=$row2->id;?>" hidden>
											</li>
										<?}?>
										<hr/>
									</ul>
								<?}?>
							</ul>
							<ul class='menu-list-detail-new'>
							</ul>
							<hr/>
							<button class='btn btn-md green'>SAVE</button>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>			
</div>

<script src="<?php echo base_url('assets/global/plugins/bootstrap-modal/js/bootstrap-modalmanager.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/bootstrap-modal/js/bootstrap-modal.js'); ?>" type="text/javascript"></script><script>
jQuery(document).ready(function() {       
   

});

$( function() {
	$( ".menu_list_detail" ).sortable();
	$( ".menu_list_detail" ).disableSelection();
} );
</script>

