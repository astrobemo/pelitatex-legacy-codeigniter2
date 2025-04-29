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

		<?foreach ($this->gudang_list_aktif as $row) {
			$gudang_list[$row->id] = $row->nama;
		}?>
		<div class="row margin-top-10">
			<div class="col-md-12">
				<div class="portlet light">
					<div class="portlet-title">
						<div class="caption caption-md">
							<i class="icon-bar-chart theme-font hide"></i>
							<span class="caption-subject theme-font bold uppercase"><?=$breadcrumb_small;?></span>
						</div>
						<div class="actions">
							
						</div>
					</div>
					<div class="portlet-body">
						<table width='100%'>
							<tr>
								<td>
									<form action='' method='get'>
									<p style="font-size:1.2em">
										<b>Tanggal: </b><input name='tanggal_start' readonly class='date-picker padding-rl-5' style='border:none; border-bottom:1px solid #ddd; width:100px;' value='<?=$tanggal_start;?>'> 
										<b>s/d</b> <input name='tanggal_end' readonly class='date-picker padding-rl-5' style='border:none; border-bottom:1px solid #ddd; width:100px;' value='<?=$tanggal_end;?>'> 
										<button class='btn btn-xs default'><i class='fa fa-search'></i></button><br/>
										<b>Gudang : </b>
										<select name='gudang_id'>
											<?foreach ($this->gudang_list_aktif as $row) {?>
												<option value="<?=$row->id?>" <?=($row->id == $gudang_id ? 'selected' : '');?> ><?=$row->nama;?></option>
											<?}?>
										</select>
									</p>
									</form>
								</td>
								<td class='text-right'>
									<a href="<?=base_url()?>report/laporan_penyesuaian_stok_excel?tanggal_start=<?=$tanggal_start;?>&tanggal_end=<?=$tanggal_end;?>&gudang_id=<?=$gudang_id;?>" class='btn green'><i class='fa fa-download'></i> Excel</a>
								</td>
							</tr>
						</table>
									
						<hr/>
						<table class="table table-striped table-bordered table-hover" id="general_table">
							<thead>
								<tr>
									<th scope="col" rowspan='2'>
										Nama
									</th>
									<th scope="col" colspan='2' style='border-bottom:1px solid #ddd'>Masuk</th>
									<th scope="col" colspan='2' style='border-bottom:1px solid #ddd'>Keluar</th>
									<th scope="col" colspan='2' style='border-bottom:1px solid #ddd'>TOTAL</th>

								</tr>
								<tr>
									<th>Yard/Kg</th>
									<th>Jumlah Roll</th>

									<th>Yard/Kg</th>
									<th>Jumlah Roll</th>

									<th>Yard/Kg</th>
									<th>Jumlah Roll</th>
								</tr>
							</thead>
							<tbody>
								<?
								$total_masuk_qty = 0;
								$total_masuk_roll = 0;
								$total_keluar_qty = 0;
								$total_keluar_roll = 0;
								foreach ($mutasi_gudang_barang as $row) { 
									?>
									<tr>
										<td style='text-align:left'>
												<?//=$row->nama_barang;?> <?=$row->nama_jual;?> <?=$row->nama_warna;?>
										</td>
										<td>
											<?=is_qty_general($row->qty_masuk); $total_masuk_qty += $row->qty_masuk;?>
										</td>
										<td>
											<?=$row->jumlah_roll_masuk; $total_masuk_roll += $row->jumlah_roll_masuk;?>
										</td>
										<td>
											<?=is_qty_general($row->qty_keluar); $total_keluar_qty += $row->qty_keluar;?>
										</td>
										<td>
											<?=$row->jumlah_roll_keluar; $total_keluar_roll += $row->jumlah_roll_keluar;?>
										</td>
										<td>
											<b><?=is_qty_general($row->qty_masuk - $row->qty_keluar);?></b> 
										</td>
										<td>
											<b><?=$row->jumlah_roll_masuk - $row->jumlah_roll_keluar;?></b>	
										</td>
									</tr>
								<? } ?>
							</tbody>
						</table>

						<table class='table' style='font-size:1.5em'>
							<tr>
								<th rowspan='2'></th>
								<th colspan='2'>MASUK</th>
								<th colspan='2'>KELUAR</th>
								<th colspan='2'>TOTAL</th>
							</tr>
							<tr>
								<th>Yard/kg</th>
								<th>Roll</th>
								<th>Yard/kg</th>
								<th>Roll</th>
								<th>Yard/kg</th>
								<th>Roll</th>
							</tr>
							<tr>
								<td>TOTAL</td>
								<td><?=is_qty_general($total_masuk_qty);?></td>
								<td><?=$total_masuk_roll?></td>
								<td><?=is_qty_general($total_keluar_qty)?></td>
								<td><?=$total_keluar_roll?></td>
								<td><?=is_qty_general($total_masuk_qty - $total_keluar_qty)?></td>
								<td><?=$total_masuk_roll - $total_keluar_roll;?></td>
							</tr>
						</table>

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
		"orderClasses": false
	});

});
</script>
