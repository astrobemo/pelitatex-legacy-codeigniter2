<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css'); ?>"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css'); ?>"/>
<?=link_tag('assets/global/plugins/bootstrap-datepicker/css/datepicker3.css'); ?>

<style type="text/css">
#general_table tr td, #general_table tr th {
	text-align: center;
	vertical-align: middle;
}

.btn-filter-container{
	padding: 10px;
}

.btn-filter{
	border: 3px solid #171717;
	height: 60px;
}


.btn-filter-selected{
	border-color: red;
}

.row-minus{
	background-color: rgba(255,0,0,0.2);
}

.flag-penyesuaian{
	background: yellow;
}

<?
$class_gudang = ''; $idx = 0;
foreach ($this->gudang_list_aktif as $row) {
	if ($row->visible == 0) {
		$class_gudang[$idx] = ".gudang-".$row->id;
		$idx++;
	}
	${'total-gudang'.$row->id} = 0;
}?>
<?if ($class_gudang != '') {?>
	<?=implode(', ', $class_gudang);?>{
		display: none;
	}
<?}?>

<?if ($print_mode == 1) {?>
	@media print {

		* {
			-webkit-print-color-adjust: exact;
			print-color-adjust: exact;
		}

		#general_table tbody tr:nth-child(even){
			background-color: #eee !important;
		}

		#general_table tbody tr td{
			padding: 2px 8px;
		}
	}

	<?}?>

	#custom-search{
		text-transform:uppercase;
	}

	#general_table_filter{
		display:none;
	}

</style>

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
							<!-- <select class='btn btn-sm btn-default' name='status_aktif_select' id='status_aktif_select'>
								<option value="1" selected>Aktif</option>
								<option value="0">Tidak Aktif</option>
							</select>
							<a href="#portlet-config" data-toggle='modal' class="btn btn-default btn-sm btn-form-add">
							<i class="fa fa-plus"></i> Tambah </a> -->
						</div>
					</div>
					<div class="portlet-body">
						<table width='100%'>
							<tr>
								<td>
									<form id='formTanggalFilter' method='get'>
										<h4><b>Tanggal Stok: <?//=$stok_opname_id?> </b><input name='tanggal' readonly class='date-picker padding-rl-5' id='tanggal-stok' style='border:none; border-bottom:1px solid #ddd; width:100px;' value='<?=$tanggal;?>'></h4>
									</form>
									<div <?=(is_posisi_id() == 8 ? 'hidden' : '')?> >
										<b>Gudang : </b>
											<?foreach ($this->gudang_list_aktif as $row){
												if (is_posisi_id() <= 3) {?>
													<label>
														<input class='gudang-view' type='checkbox' <?=($row->visible == 1 ? 'checked' : '');?> value="<?=$row->id;?>" /><?=$row->nama;?></label>
												<?}else{?>
													<div style="display: inline-block; margin-right:10px;<?=($row->visible == 1 ? 'color:black' : 'color:#999') ?>"><?=($row->visible == 1 ? "<i class='fa fa-check'></i>" : "<i class='fa fa-times'></i>");?> <?=$row->nama;?></div>
												<?}?>
											<?}?>
									</div>
								</td>
								<td class='text-right hidden-print' <?=(is_posisi_id() == 8 ? 'hidden' : '')?> >
									<?if ($print_mode == 1) {?>
										<a href='<?=base_url().is_setting_link('inventory/stok_barang_ajax');?>' class='btn blue'><i class='fa fa-eye'></i> Normal Mode</a>
									<?}else{?>
										<a href='<?=base_url().is_setting_link('inventory/stok_barang_ajax').'?tanggal='.$tanggal.'&print_mode=1';?>' class='btn blue'><i class='fa fa-eye'></i> Print Mode</a>
									<?}?>
									<form action='<?=base_url();?>inventory/stok_barang_excel' method='get' style='display:inline'>
										<input name='tanggal' value='<?=$tanggal;?>' hidden>
										<button class='btn green'><i class='fa fa-download'></i> Excel</button>
									</form>
								</td>
							</tr>
						</table>
						<hr  <?=(is_posisi_id() == 8 ? 'hidden' : '')?> />
						<!-- <div class='note note-info'> -->
						<div class='row' <?=(is_posisi_id() == 8 ? 'hidden' : '')?> >
							<div class='col-xs-4 btn-filter-container hidden-print'>
								<button class='btn btn-lg btn-block blue btn-filter' name="0">
									Semua
								</button>
							</div>
							<div class='col-xs-4 btn-filter-container  hidden-print'>
								<button class='btn btn-lg btn-block green btn-filter btn-filter-selected'  name="1">
									6 bulan + [Qty > 0]
								</button>
							</div>
							<div class='col-xs-4 btn-filter-container hidden-print'>
								<button class='btn btn-lg btn-block yellow-gold  btn-filter'  name="2">
									3 bulan + [Qty > 0]
								</button>
							</div>
							<table hidden>
								<tr>
									<td style='vertical-align:top'>Filter</td>
									<td style='vertical-align:top' class='padding-rl-5'> : </td>
									<td>
										<label>
											<input type='radio' name='filter_batas' class='filter-batas' value="0">Semua</label> <br/>
										<label>
											<input type='radio' name='filter_batas' checked class='filter-batas' value="1">Terlibat transaksi dalam <b>6 bulan</b> terakhir ATAU kuantiti > 0</label> <br/>
										<label>
											<input type='radio' name='filter_batas' class='filter-batas' value="2">Terlibat transaksi dalam <b>3 bulan</b> terakhir ATAU kuantiti > 0</label>
									</td>
								</tr>
							</table>
						</div>
						<hr/>
						<input 
						data-toggle="popover" data-trigger='hover' title="" 
							data-html='false' 
							data-content="Enter untuk cari"
							type="search" 
							id="custom-search" 
							style="width:150px; height:35px; position:absolute; right:35px; border-radius:4px; border:1px solid #ddd; padding:5px; text-align:center" 
							placeholder="search...">
						<table class="<?=($print_mode == 0 ? 'table table-striped table-hover' : '');?> table-bordered" width='100%' id="general_table">
							<thead>
								<tr>
									<th scope="col" rowspan='2' class='hidden-print  <?=(is_posisi_id() != 1 ? 'status_column' : '');?> '>
										Urutan
									</th>
									<th scope="col" rowspan='2' style="width:150px !important">
										Nama Jual
									</th>
									<th scope="col" rowspan='2' class='hidden-print <?=(is_posisi_id() != 1 ? 'status_column' : '');?> '>
										Status
									</th>
									<th rowspan='2' class="hidden-print <?=(is_posisi_id() != 1 ? 'status_column' : '');?> "  >
										Last Edit
									</th>
									<?foreach ($this->gudang_list_aktif as $row) { ?>
										<th colspan='2' class='gudang-<?=$row->id?>'><?=$row->nama;?></th>
										<th rowspan='2' class='hidden-print gudang-<?=$row->id?>'><i class='fa fa-list'></i></th>
									<?}?>
									<th colspan='2'>TOTAL</th>

								</tr>
								<tr>
									<?foreach ($this->gudang_list_aktif as $row) {
										foreach ($this->satuan_list_aktif as $isi) {
											${'qty_gudang_'.$row->id.'_'.$isi->id} = 0;
											${'roll_gudang_'.$row->id.'_'.$isi->id} = 0;
											${'qty_satuan'.$isi->id} = 0;
											${'roll_satuan'.$isi->id} = 0;
										}
										?>
										<th class='gudang-<?=$row->id?>'>Yard/Kg</th>
										<th class='gudang-<?=$row->id?>'>Roll</th>
									<?}?>
									<th>Yard/Kg</th>
									<th>Roll</th>
								</tr>
							</thead>
							<tbody>
								<?
								$tanggal_start = date("01/m/Y", strtotime(is_date_formatter($tanggal)));
								$tanggal_end = date("t/m/Y", strtotime(is_date_formatter($tanggal)));
								if ($print_mode == 1) {
									foreach ($stok_barang as $row) { ?>
										<tr>
											
											<td style='text-align:left'>
													<?=$row->nama_barang_jual;?> <span class='urutan' hidden><?=$row->urutan;?></span> <?=$row->nama_warna_jual;?>
											</td>
											<td class="hidden-print <?=(is_posisi_id() != 1 ? 'status_column' : '');?> " ">
												<?=$row->barang_id ?>
											</td>
											<td class="hidden-print <?=(is_posisi_id() != 1 ? 'status_column' : '');?> " ">
												<?=$row->warna_id ?>
											</td>
											<td class="hidden-print <?=(is_posisi_id() != 1 ? 'status_column' : '');?> " ">
												<?if ($row->status_barang == 0) { ?>
													<span style='color:red'>Tidak Aktif</span> 
												<? }else{?>
													Aktif
												<?} ?>
											</td>
											
											<td class="hidden-print <?=(is_posisi_id() != 1 ? 'status_column' : '');?> " ></td>
											<?
											$subtotal_qty = 0;
											$subtotal_roll = 0;
											foreach ($this->gudang_list_aktif as $isi) { ?>
												<?
												$qty = 'gudang_'.$isi->id.'_qty';
												$roll = 'gudang_'.$isi->id.'_roll';
												$subtotal_qty += $row->$qty;
												$subtotal_roll += $row->$roll;
												${'qty_gudang_'.$isi->id.'_'.$row->satuan_id} += $row->$qty;
												${'roll_gudang_'.$isi->id.'_'.$row->satuan_id} += $row->$roll;

												?>
												<!-- <td class='gudang-<?=$isi->id?>'><?=str_replace(',00', '', number_format($row->$qty,'2',',','.')) ;?> <small><?//=$row->nama_satuan;?></small> </td> -->
												<td class='gudang-<?=$isi->id?>'><?=$row->$qty;?> <small><?//=$row->nama_satuan;?></small> </td>
												<td class='gudang-<?=$isi->id?>'><?=number_format($row->$roll,'0',',','.');?></td>
												<td class='hidden-print gudang-<?=$isi->id?>'>									
													<a href="<?=base_url().is_setting_link('inventory/kartu_stok').'/'.$isi->id.'/'.$row->barang_id.'/'.$row->warna_id;?>?tanggal_start=<?=$tanggal_start;?>&tanggal_end=<?=$tanggal_end;?>" class='btn btn-xs yellow-gold' onclick="window.open(this.href, 'newwindow', 'width=1250, height=650'); return false;"><i class='fa fa-search'></i></a>
												</td>
											<?}?>

											<td>
												<!-- <b><?=str_replace(',00', '', number_format($subtotal_qty,'2',',','.') );?></b>  -->
												<b><?=$subtotal_qty;?></b> 
											</td>
											<td>
												<b><?=$subtotal_roll;?></b> 
												<!-- <b><?=number_format($subtotal_roll,'0',',','.');?></b>											 -->
											</td>
										</tr>
									<? } 
								}
								?>
								
							</tbody>
						</table>
						<hr/>
						<?if (is_posisi_id() <= 3) { ?>
							<table class='table table-bordered' style='font-size:1.2em;'>
								<thead>
									<tr>
										<th rowspan='2' style='vertical-align:center'>Sat</th>
										<?foreach ($this->gudang_list_aktif as $row) { ?>
											<th colspan='2' class='text-center gudang-<?=$row->id?>' ><?=$row->nama;?></th>
										<?}?>
										<th colspan='2' class='text-center'>TOTAL</th>
									</tr>
									<tr>
										<?foreach ($this->gudang_list_aktif as $row) { ?>
											<th class='text-center gudang-<?=$row->id?>' <?=(!$row->visible ? 'hidden' : '')?> style='border:1px solid #ddd'>Qty</th>
											<th class='text-center gudang-<?=$row->id?>' <?=(!$row->visible ? 'hidden' : '')?> style='border:1px solid #ddd'>Roll</th>
										<?}?>
										<th class='text-center' style='border:1px solid #ddd'>Qty</th>
										<th class='text-center' style='border:1px solid #ddd'>Roll</th>
									</tr>
								</thead>
								<tbody>
									<?foreach ($this->satuan_list_aktif as $isi) {?>
										<tr>
											<td id="nama<?=$isi->id?>Satuan"><?=$isi->nama;?></td>
											<?foreach ($this->gudang_list_aktif as $row) {
												${'qty_satuan'.$isi->id} += ${'qty_gudang_'.$row->id.'_'.$isi->id};
												${'roll_satuan'.$isi->id} += ${'roll_gudang_'.$row->id.'_'.$isi->id}?>
												<td class='text-center'  <?=(!$row->visible ? 'hidden' : '')?> id="total<?=$isi->id?>-<?=$row->id?>Qty"><?=str_replace(',00','',number_format(${'qty_gudang_'.$row->id.'_'.$isi->id},'2',',','.'));?></td>	
												<td class='text-center'  <?=(!$row->visible ? 'hidden' : '')?> id="total<?=$isi->id?>-<?=$row->id?>Roll"><?=str_replace(',00','',number_format(${'roll_gudang_'.$row->id.'_'.$isi->id},'2',',','.'))?></td>	
											<?}?>
											<td class='text-center' id="totalSatuan<?=$isi->id?>Qty"><b><?=str_replace(',00','',number_format(${'qty_satuan'.$isi->id},'2',',','.'));?></b></td>	
											<td class='text-center' id="totalSatuan<?=$isi->id?>Roll"><b><?=str_replace(',00','',number_format(${'roll_satuan'.$isi->id},'2',',','.'));?></b></td>	
										</tr>
									<?}?>
									
									<tr <?=(is_posisi_id() != 1 ? 'hidden' : '');?> >
										<td>TOTAL</td>
										<?foreach ($this->gudang_list_aktif as $row) {?>
											<td class='text-center' id="totalGudang<?=$row->id?>Qty"></td>	
											<td class='text-center' id="totalGudang<?=$row->id?>Roll"></td>	
											<?}?>
											<td class='text-center' id="totalAllQty"></td>	
											<td class='text-center' id="totalAllRoll"></td>	
										</tr>
								</tbody>
							</table>
						<?}?>
					</div>
				</div>
			</div>
		</div>
	</div>			
</div>

<script src="<?php echo base_url('assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js'); ?>" type="text/javascript"></script>

<script src="<?php echo base_url('assets/global/plugins/datatables/media/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets_noondev/js/fnReload.js');?>"></script>

<script>

var isTotalStock = false;
let searchVal = '';

<?if (is_posisi_id()==1) {?>
	const ajax_source =  baseurl + "inventory/data_stok_barang_with_tutup_buku?tanggal=<?=$tanggal?>&filter_type=1";
<?}else{?>
	const ajax_source =  baseurl + "inventory/data_stok_barang_with_tutup_buku?tanggal=<?=$tanggal?>&filter_type=1";

	// const ajax_source =  baseurl + "inventory/data_stok_barang?tanggal=<?=$tanggal?>&filter_type=1";
<?}?>

jQuery(document).ready(function() {
	//Metronic.init(); // init metronic core components
	//Layout.init(); // init current layout
	// $("#general_table").DataTable({
	// 	"ordering":false,
	// 	"orderClasses": false
	// });

	$('[data-toggle="popover"]').popover();	  	
	
	oTable = $('#general_table').DataTable();
	oTable.state.clear();
	oTable.destroy();

	<?if ($print_mode == 0) {?>
		// var get_dt='';
		var gudang_aktif = "<?=count($this->gudang_list_aktif)?>";
		var oTable = $("#general_table").dataTable({
	   		"fnCreatedRow": function( nRow, aData, iDataIndex ) {
	            <?if(is_posisi_id() != 1){?>
		            $('td:eq(0)', nRow).addClass('status_column');
		            $('td:eq(2)', nRow).addClass('status_column');
		            $('td:eq(3)', nRow).addClass('status_column');
	            <?}?>


	            var brg_dt = '';
	            var idx_col = '';
	            for (var i = 1; i <= parseInt(gudang_aktif); i++) {
	            	
	            	idx_col = (i*3) + 3;
	            	//buat link ke kartu stok
	            	var id_link = $('td:eq('+idx_col+')', nRow).text();

	            	let idx_col_status = (i*3) + 2;
	            	let data_status = $('td:eq('+idx_col_status+')', nRow).text().split('??');
	            	let roll = data_status[0];
	            	let stat = data_status[1];


				    // console.log($('td:eq(1)', nRow).text(), stat);
				   

	            	var dt_b = id_link.split('/'); 

	            	var idx_qty = (i*3)+1;
	            	var id_qty = $('td:eq('+idx_qty+')', nRow).text();
	            	if (id_qty != '0' && id_qty != 0) {
		            	<?//if (is_posisi_id() != 1) {?>
		            		if (id_qty > 0) {
				            	$('td:eq('+idx_qty+')', nRow).html(change_number_format2(id_qty).replace(',00',''));
		            		};
	            		<?//};?>
	            	}else{
	            		if (id_qty < 0) {
				            $(nRow).addClass('row-minus');
	            		};
		            	$('td:eq('+idx_qty+')', nRow).html(id_qty.replace('.00',''));
	            	};

				    $('td:eq('+idx_col_status+')', nRow).html(roll);
	            	if (stat != 0) {
					    $('td:eq('+idx_qty+')', nRow).addClass('flag-penyesuaian');
				    };

	            	$('td:eq('+idx_qty+')', nRow).addClass('gudang-'+dt_b[0]);
	            	idx_qty++;
	            	$('td:eq('+idx_qty+')', nRow).addClass('gudang-'+dt_b[0]);
	            	idx_qty++;
	            	$('td:eq('+idx_qty+')', nRow).addClass('gudang-'+dt_b[0]);


	            	// console.log(idx_col+' = '+id_link); 
		            var link = "<?=base_url().is_setting_link('inventory/kartu_stok');?>/"+id_link+"?tanggal_start=<?=$tanggal_start;?>&tanggal_end=<?=$tanggal_end;?>";
		            var link = "<?=base_url().is_setting_link('inventory/kartu_stok');?>/"+id_link+"?tanggal_start=<?=$tanggal_start;?>&tanggal_end=<?=$tanggal_end;?>&view_type=2";
		            var btn_link = `<a href="${link}" class='btn btn-xs yellow-gold' onclick="window.open(this.href, 'newwindow', 'width=1250, height=650'); return false;"><i class='fa fa-search'></i></a>`;
	            	$('td:eq('+idx_col+')', nRow).html(btn_link);
	            	var get_dt = id_link.split('/');
					var brg_dt = '';
	            	<?if (is_posisi_id() == 1) {?>
	            		brg_dt = "["+get_dt[1]+"]["+get_dt[2]+"]";
	            	<?}?>
	            	// alert(get_dt);
	            };

	            idx_col++;
	            var total = $('td:eq('+idx_col+')', nRow).text();
	            if (total != '0' && total != 0) {
	            	$('td:eq('+idx_col+')', nRow).html(change_number_format2(total).replace(',00',''));
            	}else{
		            $('td:eq('+idx_col+')', nRow).html(total.replace('.00',''));
            	};


	            // console.log($('td:eq(1)', nRow).text());
	            $('td:eq(1)', nRow).html($('td:eq(1)', nRow).text()+brg_dt);
	            $('td:eq(0)', nRow).data('order', $('td:eq(0)', nRow).text());
	        },
	        "bStateSave" :false,
			"bProcessing": true,
			"bServerSide": true,
			"pageLength": 100,
			"paging": true,
			// "searching": false,
			// "order": [[0,"asc"]],
			// "bSort": false,
			"ordering":false,
			"sAjaxSource": ajax_source,
			"initComplete": function( settings, json ) {
				if (!isTotalStock) {
					getTotalStock();
				}
			}
		});

		// oTable.fnFilter("");


		$('#custom-search').keyup(function (e) {
			if (e.key == 'Enter' && this.value != '' && this.value != searchVal) {
				oTable.fnFilter(this.value);
				searchVal = this.value;

				// $("#general_table_filter").find('input').val(this.value);
				// $("#general_table_filter").find('input').change();
				// $("#general_table_filter").css('display','block');
				// console.log(this.value);
				// oTable.search( this.value ).draw();
			// oTable.fnReloadAjax(baseurl + "inventory/data_stok_barang?tanggal="+tanggal);				
			}
			else if(this.value == '' && searchVal != this.value){
				oTable.fnFilter(this.value);
				searchVal = "";
			}
		} );

		// oTable.on( 'search.dt', function (e) {
		// 	console.log(e)
		// } );


		$('#tanggal-stok').change(function(){
			let tanggal = $(this).val();
			$('#formTanggalFilter').submit();
			// oTable.fnReloadAjax(baseurl + "inventory/data_stok_barang?tanggal="+tanggal);
		});
	<?}?>


	<?if ($print_mode == 1) {?>
		// window.print();
	<?}?>

	$(".filter-batas").change(function(){
		var filter = $('.filter-batas:checked').val();
		var tgl = $("#tanggal-stok").val().split('/');
		var tanggal = tgl.reverse().join('-');
		// alert(filter);
		oTable.fnReloadAjax(baseurl + "inventory/data_stok_barang?tanggal="+tanggal+"&filter_type="+filter);		
	});

	$(".gudang-view").change(function(){
		var gudang_id = $(this).val();
		var data_st = {};
		var url = "inventory/update_gudang_visible";
		data_st['gudang_id'] = gudang_id;
		// alert(gudang_id);
		
		ajax_data_sync(url,data_st).done(function(data_respond  /*,textStatus, jqXHR*/ ){
			// console.log(data_respond);
			if (data_respond == 'OK') {
				window.location.reload();
			}else{
				alert('error');
			}
   		});
			
	});
	

//===============================================================================================

	$(".btn-filter").click(function(){

		var filter = $(this).attr('name');
		// alert(filter);
		var tgl = $("#tanggal-stok").val().split('/');
		var tanggal = tgl.reverse().join('-');
		
		$(".btn-filter").css("border-color",'#171717');
		$(this).css("border-color",'red');
		// alert(filter);
		oTable.fnReloadAjax(baseurl + "inventory/data_stok_barang?tanggal="+tanggal+"&filter_type="+filter);

	});

});

function getTotalStock(){
	var data_st = {};
	var url = "inventory/get_total_stok";
	data_st['tanggal'] = $("#tanggal-stok").val();
	// alert(gudang_id);

	ajax_data_sync(url,data_st).done(function(data_respond  /*,textStatus, jqXHR*/ ){
		var totalAllQty = 0 ;
		var totalAllRoll = 0;
		<?foreach ($this->satuan_list_aktif as $row) {?>
			window[`totalSatuan<?=$row->id?>Qty`] = 0;
			window[`totalSatuan<?=$row->id?>Roll`] = 0;
		<?}?>
		<?foreach ($this->gudang_list_aktif as $row) {?>
			window[`totalGudang<?=$row->id?>Qty`] = 0;
			window[`totalGudang<?=$row->id?>Roll`] = 0;
		<?}?>
		$.each(JSON.parse(data_respond), function(i,v){
			let satuan_id = v.satuan_id;
			$(`#nama${satuan_id}Satuan`).html(v.nama_satuan);
			<?foreach ($this->gudang_list_aktif as $row) {?>
				var qN = v.total_<?=$row->id?>_qty;

				if (qN > 0 ) {
					qN = change_number_format2(v.total_<?=$row->id?>_qty).replace(",00","");
				}else{
					qN = 0;
				}
				$(`#total${satuan_id}-<?=$row->id?>Qty`).html(qN);
				$(`#total${satuan_id}-<?=$row->id?>Roll`).html(parseFloat(v.total_<?=$row->id?>_roll));

				window[`totalGudang<?=$row->id?>Qty`] += parseFloat(v.total_<?=$row->id?>_qty);
				window[`totalGudang<?=$row->id?>roll`] += parseFloat(v.total_<?=$row->id?>_roll);
				
				window[`totalSatuan${satuan_id}Qty`] += parseFloat(v.total_<?=$row->id?>_qty);
				window[`totalSatuan${satuan_id}Roll`] += parseFloat(v.total_<?=$row->id?>_roll);
				
				totalAllQty += parseFloat(v.total_<?=$row->id?>_qty);
				totalAllRoll += parseFloat(v.total_<?=$row->id?>_roll);

				// console.log(v.nama_satuan, window[`totalSatuan${satuan_id}Qty`]);
				
			<?}?>	
		})

		<?foreach ($this->satuan_list_aktif as $row) {?>
			window[`totalSatuan<?=$row->id?>Qty`] = window[`totalSatuan<?=$row->id?>Qty`].toFixed(2);
			$(`#totalSatuan<?=$row->id?>Qty`).html(change_number_format2(window[`totalSatuan<?=$row->id?>Qty`]).replace(",00",""));
			$(`#totalSatuan<?=$row->id?>Roll`).html(change_number_format(window[`totalSatuan<?=$row->id?>Roll`]));
		<?}?>

		<?foreach ($this->gudang_list_aktif as $row) {?>
			console.log("<?=$row->nama;?>", window[`totalGudang<?=$row->id?>Qty`]);
			var tG1 = window[`totalGudang<?=$row->id?>Qty`];
			if (window[`totalGudang<?=$row->id?>Qty`] > 0) {
				tG1 = change_number_format2(window[`totalGudang<?=$row->id?>Qty`]).replace(",00","");
			}else{
				tG1 = 0;
			}
			$(`#totalGudang<?=$row->id?>Qty`).html(tG1);
			$(`#totalGudang<?=$row->id?>Roll`).html(change_number_format(window[`totalGudang<?=$row->id?>Roll`]));
		<?}?>

		var tA = totalAllQty;
		console.log(tA);
		if (tA > 0) {
			tA = change_number_format2(Math.round(totalAllQty * 100,2)/100).replace(",00","");
		}else{
			tA = 0;
		}
		$("#totalAllQty").html(tA);
		$("#totalAllRoll").html(change_number_format(totalAllRoll))

	});
}
</script>
