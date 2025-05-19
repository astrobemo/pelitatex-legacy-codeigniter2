<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css'); ?>"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css'); ?>"/>
<?php echo link_tag('assets/global/plugins/bootstrap-datepicker/css/datepicker3.css'); ?>

<div class="page-content">
	<div class='container'>

	
		<div class="row margin-top-10">
			<div class="col-md-12">
				<div class="portlet light">
					<div class="portlet-title">
						<div class="caption caption-md">
							<i class="icon-bar-chart theme-font hide"></i>
							<span class="caption-subject theme-font bold uppercase"><?=$breadcrumb_small;?> <span style="color:red">ON PROGRESS</span> </span>
						</div>
					</div>
					<div class="portlet-body">
						<table class="table table-hover table-striped table-bordered" id="general_table_uncofirmed">
							<thead>
								<tr>
									<th scope="col">
										Tanggal
									</th>
									<th scope="col">
										No Plat
									</th>
									<th scope="col">
										Jam
									</th>
									<th scope="col">
										Barang List
									</th>
									<th scope="col">
										Status
									</th>
									<th scope="col">
										Action
									</th>
								</tr>
							</thead>
							<tbody>
								<?foreach ($penerimaan_barang_unconfirmed as $row) { 
									$nama_barang = explode("??", $row->nama_barang);
									$nama_warna = explode("??", $row->nama_warna);
									$qty_data = explode("??", $row->qty_data);
									$jumlah_roll_data = explode("??", $row->jumlah_roll_data);
									?>
									<tr>
										<td>
											<?=is_reverse_date($row->tanggal);?>
										</td>
										<td>
											<?=$row->no_plat;?>
										</td>
										<td>
											<?=$row->tanggal_input;?>
										</td>
										<td>
											<?if($nama_barang[0] != ''){?>
												<ul>
													<?for($i=0; $i<count($nama_barang); $i++){?>
														<li><?=$nama_barang[$i];?> <?=$nama_warna[$i];?> - <?=(float)$qty_data[$i];?> - <?=$jumlah_roll_data[$i];?> roll</li>
													<?}?>
												</ul>
											<?}?>
										</td>
										<td>
											<?=$row->status_penerimaan;?>
										</td>
										<td>
											<?if($row->nama_barang == '' ){?>
												<button class="btn btn-xs red" onclick="removePenerimaan('<?=$row->id?>','<?=$row->no_faktur?>')"><i class="fa fa-times"></i> del</button>
											<?}else if($row->status_penerimaan === 'MENUNGGU_KONFIRMASI_ADMIN'){?>
												<button class="btn btn-xs green" onclick="updatePenerimaan('<?=$row->id?>','<?=$row->no_faktur?>')"><i class="fa fa-check"></i> Confirm</button>
											<?}?>
										</td>
										
									</tr>
								<?}?>
							</tbody>
						</table>
					</div>
				</div>

				<div class="portlet light">
					<div class="portlet-title">
						<div class="caption caption-md">
							<i class="icon-bar-chart theme-font hide"></i>
							<span class="caption-subject theme-font bold uppercase"><?=$breadcrumb_small;?></span>
						</div>
					</div>
					<div class="portlet-body">
						<form>
							<table>
								<tr>
									<td>Tanggal</td>
									<td>
										<div class="input-group input-large date-picker input-daterange">
											<input type="text" style='border:none; border-bottom:1px solid #ddd; background:white' class="form-control" name="from" value='<?=is_reverse_date($from); ?>'>
											<span class="input-group-addon">
											s/d </span>
											<input type="text" style='border:none; border-bottom:1px solid #ddd; background:white' class="form-control" name="to" value='<?=is_reverse_date($to); ?>'>
										</div>
									</td>
									<td>
										<button class='btn btn-sm green'><i class='fa fa-search'></i></button>
									</td>
								</tr>
							</table>
						</form>
						<hr/>
						<table class="table table-hover table-striped table-bordered" id="general_table">
							<thead>
								<tr>
									<th scope="col">
										Tanggal
									</th>
									<th scope="col">
										No Plat
									</th>
									<th scope="col">
										Jam
									</th>
									<th scope="col">
										Barang List
									</th>
									<th scope="col">
										Status
									</th>
								</tr>
							</thead>
							<tbody>
								<?foreach ($penerimaan_barang as $row) { 
									$nama_barang = explode("??", $row->nama_barang);
									$nama_warna = explode("??", $row->nama_warna);
									$qty_data = explode("??", $row->qty_data);
									$jumlah_roll_data = explode("??", $row->jumlah_roll_data);
									?>
									<tr>
										<td>
											<?=is_reverse_date($row->tanggal);?>
										</td>
										<td>
											<?=$row->no_plat;?>
										</td>
										<td>
											<?=$row->tanggal_input;?>
										</td>
										<td>
											<?if($nama_barang[0] != ''){?>
												<ul>
													<?for($i=0; $i<count($nama_barang); $i++){?>
														<li><?=$nama_barang[$i];?> <?=$nama_warna[$i];?> - <?=(float)$qty_data[$i];?> - <?=$jumlah_roll_data[$i];?> roll</li>
													<?}?>
												</ul>
											<?}?>
										</td>
										<td>
											<?=$row->status_penerimaan;?>
										</td>
										
									</tr>
								<?}?>
							</tbody>
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
<script src="<?php echo base_url('assets/global/plugins/bootbox/bootbox.min.js'); ?>" type="text/javascript"></script>

<script>
jQuery(document).ready(function() {

	// dataTableTrue();

	// oTable = $('#general_table').DataTable();
	// oTable.state.clear();
	// oTable.destroy();

	$('.btn-save').click(function(){
		if ($('#form_add_data [name=tanggal]').val() != '' && $('#form_add_data [name=amount]').val() != '' ) {
			$('#form_add_data').submit();
		}else{
			alert('Tanggal dan Jumlah harus diisi');
		}
	});

});

function removePenerimaan(id, no_faktur){
	const nFaktur = (no_faktur == '' ? "" : "(surat jalan yang terkoneksi : "+no_faktur+")" );
	bootbox.confirm(`Yakin menghapus penerimaan ini ${nFaktur}? <br>data tidak dapat dikembalikan `, function(respond){
		if(respond){
			$.ajax({
				type: "POST",
				url: "<?php echo base_url('inventory/remove_penerimaan_barang'); ?>",
				data: {id:id},
				success: function(data){
					console.log(data);
					if(JSON.parse(data).status == 'success'){
						notific8("lime","Sukses, halaman akan di refresh", 3000);
						location.reload();
					}else{
						alert('Gagal menghapus penerimaan barang');
					}
				}
			});
		}
	});
}

function updatePenerimaan(id, no_faktur){
	bootbox.confirm(`Yakin mengkonfirmasi penerimaan ini ? <br>Barcode dapat <b>di print</b> setelah konfirmasi `, function(respond){
		if(respond){
			$.ajax({
				type: "POST",
				url: "<?php echo base_url('inventory/update_status_penerimaan_barang'); ?>",
				data: {id:id, status_penerimaan:'SUDAH_KONFIRMASI'},
				success: function(data){
					console.log(data);
					if(JSON.parse(data).status == 'success'){
						notific8("lime","Sukses, halaman akan di refresh", 3000);
						location.reload();
					}else{
						alert('Gagal mengkonfirmasi penerimaan barang');
					}
				}
			});
		}
	});
}

</script>
