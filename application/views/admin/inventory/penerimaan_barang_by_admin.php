<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css'); ?>"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css'); ?>"/>
<?php echo link_tag('assets/global/plugins/bootstrap-datepicker/css/datepicker3.css'); ?>

<div class="page-content">
	<div class='container'>


		<div class="modal fade" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body">
						<form action="<?=base_url('inventory/penerimaan_barang_insert')?>" class="form-horizontal" id="form_add_data" method="post">
							<h3 class='block'> Penerimaan Baru</h3>

							<div class="alert alert-info">
								<strong>No Plat + Tanggal Input Diisi per amplop</strong>
							</div>

							<div class="form-group">
			                    <label class="control-label col-md-3">No Plat<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
									<input autocomplete="off" type="text" class="form-control" name="no_plat" id="no-plat-add" >
			                    </div>
			                </div>

							<div class="form-group">
			                    <label class="control-label col-md-3">Tanggal Penerimaan<span class="required">* </span>
			                    </label>
			                    <div class="col-md-6">
									<div class="input-group date form_datetime">
										<input readonly autocomplete="off" type="text" size="16" class="form-control" name='tanggal_input' id="tanggal-input-add">
										<span class="input-group-btn">
										<button class="btn default date-set" type="button"><i class="fa fa-calendar"></i></button>
										</span>
									</div>
			                    </div>
			                </div>

			                <div class="form-group" hidden>
			                    <label class="control-label col-md-3">Toko
			                    </label>
			                    <div class="col-md-6">
					                <select name="toko_id" class='form-control'>
			                    		<?foreach ($this->toko_list_aktif as $row) { ?>
			                    			<option selected value="<?=$row->id?>"><?=$row->nama;?></option>
			                    		<? } ?>
			                    	</select> 
			                    </div>
			                </div>
						</form>
					</div>

					<div class="modal-footer">
						<button type="button" class="btn blue btn-active btn-trigger btn-save" onclick="submitFormAdd()">Save</button>
						<button type="button" class="btn default  btn-active" data-dismiss="modal">Close</button>
					</div>
				</div>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
		</div>

		<div class="row margin-top-10">
			<div class="col-md-12">
				<div class="portlet light">
					<div class="portlet-title">
						<div class="caption caption-md">
							<i class="icon-bar-chart theme-font hide"></i>
							<span class="caption-subject theme-font bold uppercase"><?=$breadcrumb_small;?> <span style="color:red">ON PROGRESS</span> </span>
						</div>
						<div class="actions">
							<a href="#portlet-config" data-toggle='modal' class="btn btn-primary btn-sm btn-form-add">
								<i class="fa fa-plus"></i> Penerimaan Baru </a>
						</div>
					</div>
					<div class="portlet-body">
						<table class="table table-hover table-striped table-bordered" id="general_table_uncofirmed">
							<thead>
								<tr>
									<th scope="col">
										Tanggal Penerimaan
									</th>
									<th scope="col">
										No Plat
									</th>
									<th scope="col">
										Tanggal Surat Jalan
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
											<?=$row->tanggal_input;?>
										</td>
										<td>
											<?=$row->no_plat;?>
										</td>
										<td>
											<?=is_reverse_date($row->tanggal);?>
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
										Tanggal Penerimaan
									</th>
									<th scope="col">
										No Plat
									</th>
									<th scope="col">
										Tanggal Surat Jalan
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
									$tanggal_sj = explode(",", $row->tanggal);
									?>
									<tr>
										<td>
											<?=$row->tanggal_input;?>
										</td>
										<td>
											<?=$row->no_plat;?>
										</td>
										<td>
											<?foreach($tanggal_sj as $key => $value){
												echo is_reverse_date($value);
											} ?>
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


	$(".form_datetime").datetimepicker({
		autoclose: true,
		isRTL: true,
		todayBtn: true,
		format: "dd MM yyyy - hh",
		minView: 1,
		pickerPosition: (Metronic.isRTL() ? "bottom-right" : "bottom-left")
	});
});

function submitFormAdd(){
	const form = $('#form_add_data');
	const noPlat = form.find('#no-plat-add').val();
	const tanggalInput = form.find('#tanggal-input-add').val();
	if(noPlat == '' || tanggalInput == '' || noPlat.length < 5){
		bootbox.alert('No Plat dan Tanggal Input harus diisi');
		return;
	}
	$('#form_add_data').submit();
	$(`#portlet-config`).modal('toggle');
}

function removePenerimaan(id, no_faktur){
	const nFaktur = (no_faktur == '' ? "" : "(surat jalan yang terkoneksi : "+no_faktur+")" );
	bootbox.confirm(`Yakin menghapus penerimaan ini ${nFaktur}? <br>data tidak dapat dikembalikan `, function(respond){
		if(respond){
			
			$.ajax({
				type: "POST",
				url: "<?php echo base_url('inventory/remove_penerimaan_barang'); ?>",
				data: {id:id},
				success: function(data){
					const res = JSON.parse(data);
					console.log(res);
					if(res.status == 'success'){
						notific8("lime","Sukses, halaman akan di refresh", 3000);
						location.reload();
					}else if(res.status){
						notific8("ruby","Gagal menghapus penerimaan barang, sudah ada barang yang di register", 3000);
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
