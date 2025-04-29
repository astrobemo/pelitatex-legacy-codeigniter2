<style>
	#christmas-tree-div{
		/* position:absolute; */
		/* top: 120px; */
		width:100%;
		position:relative;
	}
	
	#christmas-tree{
		background:rgba(255,255,127,0.3);
		/* display:table-cell; */
		height:400px;
		width:400px;
		margin:auto;
		border:20px;
		text-align:center;
		vertical-align:middle;
		font-size:1.5em;
		background:url('../image/christmas.png');
		background-repeat:no-repeat;
		background-size:200px;
		background-position:bottom;
		z-index: 9999;

		
	}

	.blink {
        animation: blink-animation 2s ease-in-out(5, start) infinite;
        -webkit-animation: blink-animation 2s steps(5, start) infinite;
	}

	@keyframes blink-animation {
        to {
          visibility: hidden;
        }
      }
      @-webkit-keyframes blink-animation {
        to {
          visibility: hidden;
        }
      }
</style>
<?php echo link_tag('assets/global/plugins/jqvmap/jqvmap/jqvmap.css'); ?>
<?php echo link_tag('assets/global/plugins/morris/morris.css'); ?>
	
		<div class="modal fade" id="portlet-config-connect" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body" style="height:140px">
						<!-- <iframe id='window-print' src="http://127.0.0.1:8080/printwindow" name='WebPrintService'></iframe> -->
					</div>
				</div>
			</div>
		</div>

		<div class="modal fade" id="portlet-config-print" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body" style="height:140px">
						<h3 class='block'> Printer</h3>
						
						<div>
		                    <label class="control-label col-md-3">Type<span class="required">
		                    * </span>
		                    </label>
		                    <div class="col-md-6">
		                    	<input name='print_target' hidden>
		                    	<select class='form-control' id='printer-name'>
		                    		<?foreach ($printer_list as $row) { ?>
		                    			<option  <?=(get_default_printer() == $row->id ? 'selected' : '');?> value='<?=$row->id;?>'><?=$row->nama;?> <?//=(get_default_printer() == $row->id ? '(default)' : '');?></option>
		                    		<?}?>
		                    	</select>
		                    	
		                    </div>
		                </div>
					</div>

					<div class="modal-footer">
						<button type="button" class="btn blue btn-active btn-test-print" data-dismiss="modal">Print</button>
						<button type="button" class="btn btn-active default" data-dismiss="modal">Close</button>
					</div>
				</div>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
		</div>	

		<div class="page-content">
			<div class='container'>
				<div class="row margin-top-10">
					<div class="col-md-12">
						<div class="portlet light ">
							<div class="portlet-title">
								<div class="caption caption-md">
									<i class="icon-bar-chart theme-font hide"></i>
									<span class="caption-subject theme-font bold uppercase">Dashboard</span>
									<!-- <span class="caption-helper hide">weekly stats...</span> -->
								</div>
								<div class="actions">
									<!-- <div class="btn-group btn-group-devided" data-toggle="buttons">
										<label class="btn btn-transparent grey-salsa btn-circle btn-sm active">
										<input type="radio" name="options" class="toggle" id="option1">Today</label>
										<label class="btn btn-transparent grey-salsa btn-circle btn-sm">
										<input type="radio" name="options" class="toggle" id="option2">Week</label>
										<label class="btn btn-transparent grey-salsa btn-circle btn-sm">
										<input type="radio" name="options" class="toggle" id="option2">Month</label>
									</div> -->
								</div>
							</div>
							<div class="portlet-body">
								<h1 id='test-cors'></h1>
								<!-- <?if (is_posisi_id() == 1) {?>
									<a href="<?=base_url()?>admin/updatePenjualanBackDate">UPDATE BACKDATE</a>
								<?}?> -->

								<!-- <div class='note note-info'>
									<?/* if (is_posisi_id() != 6 && is_posisi_id() != 1) {?>
										<b>KHUSUS PENJUALAN : </b> <br/>
										Klik <b><a href='#portlet-config-print' data-toggle='modal' class='btn btn-xs default'><i class='fa fa-print'></i>TEST</a></b> untuk testing awal koneksi dengan printer
									<?}?>
									<br/>
									<?if (is_posisi_id() == 1) {
										echo (int)'100.000';
										?>
										Klik <b><a href="http://localhost/hameyean/admin" class='btn btn-xs default'>TEST</a></b> 
									<?} */?>
								</div> -->


								<?if (date('m') == 12 && date('d') <= 25  ) {?>
									<div id="christmas-tree-div" class="blink">
										<div id="christmas-tree">
										</div>
									</div>
								<?}?>

								<?foreach ($notifikasi_faktur_kosong as $row) {?>
									<div class='note note-warning'>
										<b>WARNING : </b> <br/>
										1. Ada <?=$row->count_data_penjualan;?> faktur penjualan yang belum melakukan registrasi 
										<a target='_blank' href="<?=base_url().is_setting_link('transaction/penjualan_list');?>?status_aktif=2">Check<i class='fa fa-link'></i></a> 
										<span class="badge badge-danger"><small>new</small></span><br>
									</div>
								<?}?>

								<?if (is_posisi_id() != 1 ) { ?>
									<hr/>
									<h4>Catatan Pesanan</h4>
									<i class="fa fa-warning"></i> launch soon

									<table class='table' id='note_order_table' hidden>
										<thead>
											<tr>
												<th>No</th>
												<th>Tanggal Input</th>
												<th>Tanggal Target</th>
												<th>Customer</th>
												<th>Status - Barang - Qty - Harga</th>
												<!-- <th>Status</th> -->
												<?//if (is_posisi_id() < 4) { ?>
													<th>Action</th>
												<?//}?>
											</tr>
										</thead>
										<tbody>
											<?
										$idx = 1;
										foreach (get_note_order() as $row) {?>
											<tr <?/*style="<?=($row->matched == 1 ? 'border:2px solid red' : '');?>" */?>>
												<td>
													<?=$idx;?>
													
												</td>
												<td><span class='tanggal_note_order'><?=is_reverse_datetime2($row->tanggal_note_order);?></span> </td>
												<td><span class='tanggal_target'><?=is_reverse_date($row->tanggal_target);?></span></td>
												<td>
													<span class='tipe_customer' hidden><?=$row->tipe_customer?></span>
													<span class='customer_id' hidden><?=$row->customer_id;?></span>
													<span class='nama_customer'><?=$row->nama_customer;?></span> - 
													<span class='contact_info'><?=$row->contact_info;?></span>
												</td>
												<td>
													<?	
														$status = $row->status;
														$nama_barang = explode('??', $row->nama_barang);
														$status = explode(',', $row->status);
														$barang_id = explode(',', $row->barang_id);
														$warna_id = explode(',', $row->warna_id);
														$tipe_barang = explode(',', $row->tipe_barang);
														$nama_warna = explode(',', $row->nama_warna);
														$qty = explode(',', $row->qty);
														$roll = explode(',', $row->roll);
														$harga = explode(',', $row->harga);
														$note_order_detail_id = explode(',', $row->note_order_detail_id);
														$matched = explode('??', $row->matched);
														$done_by = explode(',', $row->done_by);
														$done_time = explode(',', $row->done_time);
													?>
													
													<table>
														<?
														if ($row->nama_barang != '') {
															foreach ($nama_barang as $key => $value) {?>
																<tr style="<?=($status[$key] == 1 ? 'text-decoration:line-through' : '' );?>">
																	<td style='width:100px'>
																		<?if ($matched[$key] == 1) { ?>
																			<span style='color:red'><i class='fa fa-flag'></i></span>
																		<?}?>
																		<span class='tipe_barang' hidden><?=$tipe_barang[$key];?></span>
																		<span class='barang_id' hidden><?=$barang_id[$key];?></span>
																		<span class='note_order_detail_id' hidden><?=$note_order_detail_id[$key];?></span>
																		<span class='note_order_id' hidden><?=$row->id;?></span>
																		<?=($row->tipe_barang==1 ? 'terdaftar' : 'tidak terdaftar');?></td>
																	<td style='width:150px'><span class='nama_barang'><?=$value;?></span></td>
																	<td style='width:100px'><span class='warna_id' hidden><?=$warna_id[$key];?></span> <span class='nama_warna'><?=$nama_warna[$key];?></span> </td>
																	<td style='width:50px'><span class='qty'><?=is_qty_general($qty[$key]);?></span></td>
																	<td style='width:50px'><span class='roll'><?=$roll[$key];?></span></td>
																	<td style="width:50px">
																		<span class='harga'><?=number_format($harga[$key],'0',',','.');?></span>
																	</td>
																	<td style="width:50px;" <?=($status == 1 ? 'hidden' : '' ); ?> >
																		<i class='fa fa-edit btn-edit-note' style='cursor:pointer; color:green'></i>
																		<i class='fa fa-times btn-remove-item-note' style='cursor:pointer; color:red'></i>
																	</td>
																	<td>
																		<span class='status' hidden><?=$status[$key];?></span>
																		<?if ($status[$key] == 1) { ?>
																			<button class='btn btn-xs blue check_note_order'><i class='fa fa-check'></i> completed <br/> by <?=is_get_username($done_by[$key]);?> <br/><?=is_reverse_datetime($done_time[$key]);?></button>
																		<?}elseif($status[$key] == -1){?>
																			<button class='btn btn-xs red check_note_order'><i class='fa fa-times'></i> cancel by <?=is_get_username($done_by[$key]);?> <?=is_reverse_datetime($done_time[$key]);?></button>
																		<?}else{?>
																			<button style='display:none' class='btn btn-xs default btn-reminder'> <i class='fa fa-plus'></i> <i class='fa fa-clock-o'></i></button>
																			<button class='btn btn-xs default check_note_order'> completed</button>
																		<?}?>
																		
																	</td>
																</tr>
															<?}
														}
														?>
													</table>
												</td>
												<?//if (is_posisi_id() < 4) { ?>
													<td>
														<span class='id' hidden><?=$row->id;?></span>
														<form hidden action="<?=base_url('admin/set_reminder');?>" hidden class='form-reminder'>
															<input name='note_order_id' value="<?=$row->id;?>" hidden>
															<input name='reminder' class='form_datetime'> <button><i class='fa fa-check'></i></button>
														</form>
														<button class='btn btn-xs blue btn-add'> <i class='fa fa-plus'></i></button>
														<button class='btn btn-xs green btn-edit'> <i class='fa fa-edit'></i></button>
													</td>
												<?//}?>
											</tr>
										<?$idx++;}?>
										</tbody>
									</table>
								<?}?>

								<?if (is_posisi_id() == 1) { ?>
									<button class='btn green hidden-print' onclick="snowEffect()"><i class='fa fa-tree'></i></button>
								<?}?>

							</div>
						</div>
					</div>
				</div>
			</div>			
		</div>


		


		<script src="<?php echo base_url('assets/global/plugins/bootbox/bootbox.min.js'); ?>" type="text/javascript"></script>
		<script src="<?php echo base_url('assets_noondev/js/webprint.js'); ?>" type="text/javascript"></script>

<script>
$(document).ready(function() {   

	const today = new Date();
	<?if (date('m') == 12 && date('d') <= 25  ) {?>
		if (today.getMonth() && today.getDate() <= 25 ) {
			snowEffect();
			lampEffect();
		};
	<?}?>

	
});

const snowEffect = () => {
	var COUNT = 300;
	var masthead = document.querySelector('.page-content');
	// masthead.classList.add('on-blur');
	//=================snow===================================
	var canvas = document.createElement('canvas');
	var ctx = canvas.getContext('2d');
	var width = masthead.clientWidth;
	var height = masthead.clientHeight;
	var i = 0;
	var active = false;

	function onResize() {
	width = masthead.clientWidth;
	height = masthead.clientHeight;
	canvas.width = width;
	canvas.height = height;
	ctx.fillStyle = '#FFF';

	var wasActive = active;
	active = width > 600;

	if (!wasActive && active)
		requestAnimFrame(update);
	}

	var Snowflake = function () {
	this.x = 0;
	this.y = 0;
	this.vy = 0;
	this.vx = 0;
	this.r = 0;

	this.reset();
	}

	Snowflake.prototype.reset = function() {
	this.x = Math.random() * width;
	this.y = Math.random() * -height;
	this.vy = 1 + Math.random() * 3;
	this.vx = 0.5 - Math.random();
	this.r = 1 + Math.random() * 2;
	this.o = 0.5 + Math.random() * 0.5;
	}

	canvas.style.position = 'absolute';
	canvas.style.left = canvas.style.top = '0';

	var snowflakes = [], snowflake;
	for (i = 0; i < COUNT; i++) {
	snowflake = new Snowflake();
	snowflake.reset();
	snowflakes.push(snowflake);
	}

	function update() {

		ctx.clearRect(0, 0, width, height);

		if (!active)
			return;

		for (i = 0; i < COUNT; i++) {
			snowflake = snowflakes[i];
			snowflake.y += snowflake.vy;
			snowflake.x += snowflake.vx;

			ctx.globalAlpha = snowflake.o;
			ctx.beginPath();
			ctx.arc(snowflake.x, snowflake.y, snowflake.r, 0, Math.PI * 2, false);
			ctx.closePath();
			ctx.fill();

			if (snowflake.y > height) {
			snowflake.reset();
			}
		}

		requestAnimFrame(update);
	}

	// shim layer with setTimeout fallback
	window.requestAnimFrame = (function(){
		return  window.requestAnimationFrame   ||
			window.webkitRequestAnimationFrame ||
			window.mozRequestAnimationFrame    ||
			function( callback ){
				window.setTimeout(callback, 1000 / 60);
			};
	})();

	onResize();
	window.addEventListener('resize', onResize, false);
	canvas.addEventListener("click",function(e){
		// masthead.classList.remove('on-blur');
		document.querySelector(".page-content").removeChild(canvas);
		document.querySelector("#christmas-tree-div").removeChild(canvas);
	});

	
	const canvas2 = document.querySelector("#christmas-tree-div").querySelector("canvas");
	masthead.addEventListener("click",function(e){
		// masthead.classList.remove('on-blur');
		document.querySelector("#christmas-tree-div").style.display='none';
		document.querySelector("#christmas-tree-div").removeChild(canvas2);
		document.querySelector(".page-content").removeChild(canvas);
	});

	// setTimeout(() => {
	// 	if (canvas) {
	// 		document.querySelector(".page-content").removeChild(canvas);
	// 	}
	// }, 60000);

	masthead.appendChild(canvas);
}

const lampEffect = () => {
	var COUNT = 100;
	var masthead = document.querySelector('#christmas-tree-div');
	// masthead.classList.add('on-blur');
	//=================lamp===================================
	var canvas = document.createElement('canvas');
	var ctx = canvas.getContext('2d');
	var width = masthead.clientWidth;
	var height = masthead.clientHeight;
	var i = 0;
	var active = false;

	function onResize() {
	width = masthead.clientWidth;
	height = masthead.clientHeight;
	canvas.width = width;
	canvas.height = height;
	
	ctx.shadowOffsetX = 0;
	ctx.shadowOffsetY = 0;
	ctx.shadowBlur = 10;

	var wasActive = active;
	active = width > 600;

	if (!wasActive && active)
		// requestAnimFrame(update);
		createFlake();
	}

	var Lampflake = function () {
	this.x = 0;
	this.y = 0;
	this.vy = 0;
	this.vx = 0;
	this.r = 0;

	this.reset();
	}

	Lampflake.prototype.reset = function() {
	this.x = 335;
	this.y = 0;
	this.vy = 1 + Math.random() * 3;
	this.vx = 0.5 - Math.random();
	this.r = 2 + Math.random() * 2;
	this.o = 0.5 + Math.random() * 0.5;
	}

	canvas.style.position = 'absolute';
	canvas.style.left = canvas.style.top = '0';

	var lampflakes = [], lampflake;
	for (i = 0; i < COUNT; i++) {
		lampflake = new Lampflake();
		lampflake.reset();
		lampflakes.push(lampflake);
	}

	const space = 50;
	const layer = Math.ceil(COUNT / 40);
	const red = "rgba(255,0,0,0.5)";
	const green = "green";
	const yellow = "yellow";
	function createFlake(){

		const cT = document.querySelector("#christmas-tree");
		let yStart = 10;
		
		for (let j = 3; j >= 0; j--) {
			for (let jk = 0; jk < 2; jk++) {
				let xStart = cT.offsetLeft - 50;
				for (let k = 0; k <= 3; k++) {
					xStart += 50;
					const r = Math.ceil(Math.random() * 3);
					if (r%2 == 0 ) {
						ctx.fillStyle = red;
						ctx.shadowColor = red;
					}else if(r%3 == 0){
						ctx.fillStyle = yellow;
						ctx.shadowColor = yellow;
					}else{
						ctx.fillStyle = green;
						ctx.shadowColor = green;
					}

					if (j - k > 0) {
					}else{
						for (let ll = 0; ll < layer; ll++) {
							ctx.globalAlpha = lampflake.o;
							const w = Math.random() * 50;
							const h = Math.random() * 50;
							ctx.beginPath();
							ctx.arc(xStart + w, yStart + h, lampflake.r, 0, Math.PI * 2, false);
							ctx.arc(xStart + w, yStart + h, (lampflake.r+3), 0, Math.PI * 2, false);
							ctx.closePath();
							ctx.fill();
							
						}
					}
	
				}

				for (let k = 3; k >= 0; k--) {
					xStart += 50;
					const r = Math.ceil(Math.random() * 3);
					if (r%2 == 0 ) {
						ctx.fillStyle = red;
						ctx.shadowColor = red;
					}else if(r%3 == 0){
						ctx.fillStyle = yellow;
						ctx.shadowColor = yellow;
					}else{
						ctx.fillStyle = green;
						ctx.shadowColor = green;
					}

					if (k-j >= 0) {
						for (let ll = 0; ll < layer; ll++) {
							ctx.globalAlpha = lampflake.o;
							const w = Math.random() * 50;
							const h = Math.random() * 50;
							ctx.beginPath();
							
							ctx.arc(xStart + w, yStart + h, lampflake.r, 0, Math.PI * 2, false);
							ctx.arc(xStart + w, yStart + h, (lampflake.r+3), 0, Math.PI * 2, false);
							ctx.closePath();
							ctx.fill();
							
						}

					}
				}
				
				yStart += 50;
				
			}

		}

		// console.log(canvas);

	}

	// function update() {

	// 	ctx.clearRect(0, 0, width, height);

	// 	if (!active)
	// 		return;

	// 	for (i = 0; i < COUNT; i++) {
	// 		lampflake = lampflakes[i];
	// 		lampflake.y += lampflake.vy;
	// 		lampflake.x += lampflake.vx;

	// 		ctx.globalAlpha = lampflake.o;
	// 		ctx.beginPath();
	// 		// console.log(i);
	// 		// console.log(lampflake.x, lampflake.y, lampflake.r, 0, Math.PI * 2);
	// 		ctx.arc(lampflake.x, lampflake.y, lampflake.r, 0, Math.PI * 2, false);
	// 		// ctx.arc(500,100, lampflake.r, 0, Math.PI * 2, false);
	// 		ctx.closePath();
	// 		ctx.fill();

	// 		if (lampflake.y > height) {
	// 			lampflake.reset();
	// 		}
	// 	}

	// 	// requestAnimFrame(update);
	// }

	// shim layer with setTimeout fallback
	// window.requestAnimFrame = (function(){
	// 	return  window.requestAnimationFrame   ||
	// 		window.webkitRequestAnimationFrame ||
	// 		window.mozRequestAnimationFrame    ||
	// 		function( callback ){
	// 			window.setTimeout(callback, 1000 / 60);
	// 		};
	// })();

	onResize();
	window.addEventListener('resize', onResize, false);
	canvas.addEventListener("click",function(e){
		// masthead.classList.remove('on-blur');
		document.querySelector(".page-content").removeChild(canvas);
		document.querySelector("#christmas-tree-div").removeChild(canvas);
	});

	const canvas2 = document.querySelector(".page-content").querySelector("canvas");
	
	masthead.addEventListener("click",function(e){
		// masthead.classList.remove('on-blur');
		document.querySelector("#christmas-tree-div").style.display='none';
		document.querySelector("#christmas-tree-div").removeChild(canvas);
		document.querySelector(".page-content").removeChild(canvas2);
	});
	// canvas.addEventListener("click",function(e){
	// 	// masthead.classList.remove('on-blur');
	// 	COUNT = 0;
	// 	document.querySelector(".page-content").removeChild(canvas);
	// });

	// setTimeout(() => {
	// 	if (canvas) {
	// 		document.querySelector(".page-content").removeChild(canvas);
	// 	}
	// }, 60000);

	masthead.appendChild(canvas);
}


</script>
<!-- END JAVASCRIPTS -->
<?include_once 'print_connect_test.php';?>
