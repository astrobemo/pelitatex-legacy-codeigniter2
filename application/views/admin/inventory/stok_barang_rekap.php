<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css'); ?>"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css'); ?>"/>
<?=link_tag('assets/global/plugins/bootstrap-datepicker/css/datepicker3.css'); ?>

<style type="text/css">
#general_table tr td, #general_table tr th {
	text-align: center;
	vertical-align: middle;
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
						<div class='row'>
							<div class='col-xs-6'>
								<form action='' method='get'>
								<h4><b>Tanggal Stok: </b><input name='tanggal' readonly class='date-picker padding-rl-5' style='border:none; border-bottom:1px solid #ddd; width:100px;' value='<?=$tanggal;?>'> <button class='btn btn-xs default'><i class='fa fa-search'></i></button></h4>
								</form>
							</div>
							<div class='col-xs-6 text-right'>
								<button class='btn btn-lg blue hidden-print print-mode'><i class='fa fa-eye'></i> Print Mode</button>
								<button class='btn btn-lg blue hidden-print view-mode' style='display:none'><i class='fa fa-eye'></i> View Mode</button>
							</div>
						</div>
						<hr/>

						<div id='table-on-site' class='hidden-print'>
							<table class="table table-striped table-bordered table-hover" id="general_table" style='font-size:1.05em'>
								<thead>
									<tr>
										<!-- <th scope="col" rowspan='2'>
											Nama Beli
										</th> -->
										<th scope="col" rowspan='2'>
											Nama Jual
										</th>
										<th scope="col" rowspan='2'>
											Status
										</th>
										<!-- <th scope="col">
											Satuan
										</th> -->
										<?foreach ($this->gudang_list_aktif as $row) { 
											${'total_'.$row->id.'_qty'} = 0;
											${'total_'.$row->id.'_roll'} = 0;
											?>
											<th colspan='2'><?=$row->nama;?></th>
										<?}?>
										<th colspan='2'>TOTAL</th>

									</tr>
									<tr>
										<?foreach ($this->gudang_list_aktif as $row) { ?>
											<th style='text-align:right'>Yard/Kg</th>
											<th style='text-align:right'>Jumlah Roll</th>
										<?}?>
										<th style='text-align:right'>Yard/Kg</th>
										<th style='text-align:right'>Jumlah Roll</th>
									</tr>
								</thead>
								<tbody>
									<?foreach ($stok_barang as $row) { ?>
										<tr>
											<!-- <td>
												<span class='barang_id' hidden="hidden"><?=$row->barang_id;?></span>
												<?//=$row->nama_barang;?> <?=$row->nama_warna;?>
												<?//=$row->barang_id;?><?//=$row->warna_id;?>
											</td> -->
											<td style='text-align:left'>
												<a target='_blank' href="<?=base_url().is_setting_link('inventory/stok_barang_by_barang');?>?barang_id=<?=$row->barang_id?>&tanggal=<?=is_date_formatter($tanggal);?>">
													<?=$row->nama_barang_jual;?>
												</a>
											</td>
											<td>
												<?if ($row->status_barang == 0) { ?>
													<span style='color:red'>Tidak Aktif</span> 
												<? }else{?>
													Aktif
												<?} ?>
											</td>
											<?
											$subtotal_qty = 0;
											$subtotal_roll = 0;
											foreach ($this->gudang_list_aktif as $isi) { ?>
												<?
												$qty = 'gudang_'.$isi->id.'_qty';
												$roll = 'gudang_'.$isi->id.'_roll';
												${'total_'.$isi->id.'_qty'} += $row->$qty;
												${'total_'.$isi->id.'_roll'} += $row->$roll;
												$subtotal_qty += $row->$qty;
												$subtotal_roll += $row->$roll;
												?>
												<td style='text-align:right'><?=str_replace(',00', '', number_format($row->$qty,'2',',','.')) ;?> <?=$row->nama_satuan;?></td>
												<td style='text-align:right'><?=number_format($row->$roll,'0',',','.');?></td>
											<?}?>

											<td style='text-align:right'>
												<b><?=number_format($subtotal_qty,'2',',','.');?></b> 
											</td>
											<td style='text-align:right'>
												<b><?=number_format($subtotal_roll,'0',',','.');?></b>											
											</td>
										</tr>
									<? } ?>

								</tbody>
							</table>

							<table class='table table-bordered' style='font-size:1.2em'>
								<tr>
									<th rowspan='3' style='vertical-align:middle; border:1px solid #ddd' >TOTAL</th>
									<?foreach ($this->gudang_list_aktif as $row) {?>
										<th colspan='2' class='text-center'><?=$row->nama;?></th>
									<?}?>
										<th colspan='2' class='text-center'>TOTAL</th>

								</tr>
								<tr>
									<?foreach ($this->gudang_list_aktif as $row) { ?>
										<th style='text-align:center'>Yard/Kg</th>
										<th style='text-align:center'>Jumlah Roll</th>
									<?}?>
									<th style='text-align:center'>Yard/Kg</th>
									<th style='text-align:center'>Jumlah Roll</th>
								</tr>

								<tr>
									<?$g_total_yard = 0; $g_total_roll = 0;
										foreach ($this->gudang_list_aktif as $row) { 
											$g_total_yard += ${'total_'.$row->id.'_qty'};
											$g_total_roll += ${'total_'.$row->id.'_roll'}
											?>
											<td class='text-center'><b><?=number_format(${'total_'.$row->id.'_qty'},'0',',','.')?></b></td>
											<td class='text-center'><b><?=number_format(${'total_'.$row->id.'_roll'},'0',',','.')?></b></td>
										<?}?>
										<td class='text-center'>
											<b><?=number_format($g_total_yard,'0',',','.')?></b>
										</td>
										<td class='text-center'>
											<b><?=number_format($g_total_roll,'0',',','.')?></b>
										</td>
								</tr>
							</table>
						</div>
						<div id="table-on-print" hidden>
							<table class="table table-striped table-bordered table-hover" style='font-size:1.05em'>
								<thead>
									<tr>
										<!-- <th scope="col" rowspan='2'>
											Nama Beli
										</th> -->
										<th scope="col" rowspan='2'>
											Nama Jual
										</th>
										<!-- <th scope="col">
											Satuan
										</th> -->
										<?foreach ($this->gudang_list_aktif as $row) { 
											${'total_'.$row->id.'_qty'} = 0;
											${'total_'.$row->id.'_roll'} = 0;
											?>
											<th colspan='2'><?=$row->nama;?></th>
										<?}?>
										<th colspan='2'>TOTAL</th>

									</tr>
									<tr>
										<?foreach ($this->gudang_list_aktif as $row) { ?>
											<th style='text-align:right'>Yard/Kg</th>
											<th style='text-align:right'>Jumlah Roll</th>
										<?}?>
										<th style='text-align:right'>Yard/Kg</th>
										<th style='text-align:right'>Jumlah Roll</th>
									</tr>
								</thead>
								<tbody>
									<?foreach ($stok_barang as $row) { ?>
										<tr>
											<td style='text-align:left'>
													<?=$row->nama_barang_jual;?>
											</td>
											<?
											$subtotal_qty = 0;
											$subtotal_roll = 0;
											foreach ($this->gudang_list_aktif as $isi) { ?>
												<?
												$qty = 'gudang_'.$isi->id.'_qty';
												$roll = 'gudang_'.$isi->id.'_roll';
												${'total_'.$isi->id.'_qty'} += $row->$qty;
												${'total_'.$isi->id.'_roll'} += $row->$roll;
												$subtotal_qty += $row->$qty;
												$subtotal_roll += $row->$roll;
												?>
												<td style='text-align:right'><?=str_replace(',00', '', number_format($row->$qty,'2',',','.')) ;?> <?=$row->nama_satuan;?></td>
												<td style='text-align:right'><?=number_format($row->$roll,'0',',','.');?></td>
											<?}?>

											<td style='text-align:right'>
												<b><?=number_format($subtotal_qty,'2',',','.');?></b> 
											</td>
											<td style='text-align:right'>
												<b><?=number_format($subtotal_roll,'0',',','.');?></b>											
											</td>
										</tr>
									<? } ?>
									<tr style='font-size:1.2em'>
										<td>TOTAL</td>
										<?$g_total_yard = 0; $g_total_roll = 0;
										foreach ($this->gudang_list_aktif as $row) { 
											$g_total_yard += ${'total_'.$row->id.'_qty'};
											$g_total_roll += ${'total_'.$row->id.'_roll'}
											?>
											<td class='text-center'><b><?=number_format(${'total_'.$row->id.'_qty'},'0',',','.')?></b></td>
											<td class='text-center'><b><?=number_format(${'total_'.$row->id.'_roll'},'0',',','.')?></b></td>
										<?}?>
										<td class='text-center'>
											<b><?=number_format($g_total_yard,'0',',','.')?></b>
										</td>
										<td class='text-center'>
											<b><?=number_format($g_total_roll,'0',',','.')?></b>
										</td>
									</tr>

								</tbody>
							</table>

						</div>

						<?//if (is_posisi_id() == 1) {?>
							<a style="display:none" href="<?=base_url();?>inventory/stok_barang_excel?tanggal=<?=$tanggal?>" class='btn btn-lg green hidden-print'><i class='fa fa-download'></i> Excel</a>
							<button class='btn btn-lg blue hidden-print print-mode'><i class='fa fa-eye'></i> Print Mode</button>
							<button class='btn btn-lg blue hidden-print view-mode' style='display:none'><i class='fa fa-eye'></i> View Mode</button>
							<button class='btn btn-lg blue hidden-print' onClick='window.print()' id='print_now' style='display:none'><i class='fa fa-print'></i> Print</button>
						<?//}?>

					</div>
				</div>
			</div>
		</div>
	</div>			
</div>

<script src="<?php echo base_url('assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js'); ?>" type="text/javascript"></script>

<script src="<?php echo base_url('assets/global/plugins/datatables/media/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets_noondev/js/table-advanced.js'); ?>"></script>

<script>
jQuery(document).ready(function() {
	//Metronic.init(); // init metronic core components
	//Layout.init(); // init current layout
	// TableAdvanced.init();

	$("#general_table").DataTable({
		"ordering":false,
		"orderClasses": false,
		"pageLength" : 100,
		"sDom": '<"top"lpf>rt<"bottom"p>i<"clear">'
	});

	$(".print-mode").click(function(){
		$('#table-on-site').toggle();
		$("#table-on-print").toggle();
		$("#print_now").toggle();
		$(".view-mode").toggle();
		$(".print-mode").toggle();
	});

	$(".view-mode").click(function(){
		$('#table-on-site').toggle();
		$("#table-on-print").toggle();
		$("#print_now").toggle();
		$(".view-mode").toggle();
		$(".print-mode").toggle();
	});
	
});
</script>
