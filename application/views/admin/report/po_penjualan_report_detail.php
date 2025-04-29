<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css'); ?>"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css'); ?>"/>
<?php echo link_tag('assets/global/plugins/bootstrap-datepicker/css/datepicker3.css'); ?>
<link href="<?=base_url('assets/global/plugins/bootstrap-modal/css/bootstrap-modal.css');?>" rel="stylesheet" type="text/css"/>

<style type="text/css">
.tbl-rinci tr td{
	padding: 2px 10px;
}

.bg-selected{
	/*border: 2px solid #FFB6C1;*/
	border: 2px solid green;
}

.popover-cell{
	position:relative;
	min-width:50px;
}

.info-popover{
	position:absolute;
	height:100%;
	width:100%;
	top:0px;
	left:0px;
	text-decoration:none;
	font-size:1.1em;
	color:black;
}

#general_table{
	/* background:green; */
	font-size:1.2em;
	width:100%;
	border-collapse: separate;
	border-spacing:1px;
}

#general_table tr td{
	padding:8px 10px;
	border:none;
	border-radius:3px;
	/* border:1px solid #ddd; */
}

#general_table tr{
	background:#eee;
}

#general_table tr th{
	border-radius:3px;
	padding:5px;
	text-align:center;
	border:none;
	/* border:1px solid #ddd; */
	background:#ccf;
}



@media print {
	#general_table tr td, #general_table tr th{
		font-size:1em;
		border:1px solid #ccc;
	}

	#general_table{
		font-size:1em;
		border-collapse: collapse;
	}

	#general_table  tr td:nth-child(3),
	#general_table  tr td:nth-child(11){
		text-align:center;
	}

	
  a[href]:after {
    content: none !important;
  }
}

</style>

<?
	$po_penjualan_id = '';
	$po_number = "";
	$tanggal = "";
	$tanggal_show = "";
	$tanggal_kirim = "";
	$customer_id = '';
	$alamat_kirim = '';
	$po_number ='';
	$diskon = '';
	$biaya_lain = '';
	$ppn_include_status = '';
	$ppn_value ='';
	$keterangan = '';
	$status_po = '';
	$nama_customer = '';
	$contact_person = '';
	foreach ($data_header as $row) {
		$po_penjualan_id = $row->id;
		$po_number = $row->po_number;
		$tanggal_show = date('d F Y', strtotime($row->tanggal) );
		$tanggal = $row->tanggal;
		$tk = $row->tanggal_kirim;
		$tanggal_kirim = ($row->tanggal_kirim != '' && $row->tanggal_kirim ? date('d F Y', strtotime($row->tanggal_kirim)) : '' );
		$customer_id = $row->customer_id;
		$alamat_kirim = $row->alamat_kirim;
		$nama_customer = $row->nama_customer;
		$diskon = $row->diskon;
		$biaya_lain = $row->biaya_lain;
		$ppn_include_status = $row->ppn_include_status;
		$ppn_value = $row->ppn_value;
		$keterangan = $row->keterangan;
		$contact_person = $row->contact_person;

		$status_po = $row->status_po;
	}

	$data_invoice=array();
	foreach ($data_barang as $row) {
		$total_po[$row->barang_id.$row->warna_id] = $row->qty_po;
		$total_invoice[$row->barang_id.$row->warna_id] = 0;
		$data_invoice[$row->barang_id.$row->warna_id] = array();
		$sisa_po[$row->barang_id.$row->warna_id] = $row->qty_po;
	}

	foreach ($po_penjualan_invoice as $row) {
		$qty_invoice = explode(",", $row->qty_invoice);
		$no_faktur = explode(",", $row->no_faktur_lengkap);
		$penjualan_id = explode(",", $row->penjualan_id);
		$tanggal_invoice = explode(",", $row->tanggal_invoice);
		$harga_invoice = explode(",", $row->harga_invoice);
		if ($row->qty_invoice != '') {
			foreach ($qty_invoice as $key => $value) {
				$total_invoice[$row->barang_id.$row->warna_id] += $value;
				array_push($data_invoice[$row->barang_id.$row->warna_id], array(
					'no_faktur_lengkap' => $no_faktur[$key],
					'tanggal' => $tanggal_invoice[$key],
					'harga' => $harga_invoice[$key],
					'qty' => $value,
					'penjualan_id' => $penjualan_id[$key]
				));
			}
		}
	}
	
?>

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
						<div class="actions hidden-print">
						</div>
					</div>
					<div class="portlet-body">
					<div>
							
							<table id="data-header">
								<tr>
									<td style="font-size:1.3em;">Customer</td>
									<td class='padding-rl-5'> : </td>
									<td style="font-size:1.3em;"><b><?=$nama_customer?></b></td>
								</tr>
								<tr>
									<td style="font-size:1.3em;">No PO</td>
									<td class='padding-rl-5'> : </td>
									<td style="font-size:1.3em;"><b>
										<a href="<?=base_url().is_setting_link('transaction/po_penjualan_detail').'?id='.$po_penjualan_id?>"><?=$po_number?></a></b>
									</td>
								</tr>
								<tr>
									<td>Tanggal</td>
									<td class='padding-rl-5'> : </td>
									<td><?=$tanggal_show?></td>
								</tr>
								<tr>
									<td>Contact Person</td>
									<td class='padding-rl-5'> : </td>
									<td><?=$contact_person?></td>
								</tr>
								<tr>
									<td>Tanggal Kirim</td>
									<td class='padding-rl-5'> : </td>
									<td><?=$tanggal_kirim?></td>
								</tr>
								<tr>
									<td>Harga include PPN</td>
									<td class='padding-rl-5'> : </td>
									<td>
										<?=($ppn_include_status ? "ya" : "tidak")?>
										<!-- <input type="checkbox" <?=($ppn_include_status ? "checked" : "")?> id="ppnIncludeStatus" onchange="changePPNStatus()"> -->
									</td>
								</tr>
								<tr>
									<td>Keterangan</td>
									<td class='padding-rl-5'> : </td>
									<td><?=$keterangan?></td>
								</tr>
							</table>
						</div>
						<hr/>
						
						<table id="general_table">
							<thead>
								<tr>
									<th scope="col">
										NO
									</th>
									<th scope="col">
										NAMA BARANG
									</th>
									<th scope="col">
										SATUAN
									</th>
									<th scope="col">
										PO QTY
									</th>
									<th scope="col" colspan='5'>
										INVOICE
									</th>
									<th scope="col" >
										RETUR
									</th>
									<th scope="col">
										BALANCE
									</th>
								</tr>
							</thead>
							<tbody>
								<?$t_barang = count($data_barang);
								$no_baris= 0;
								foreach ($data_barang as $row) {
									$subtotal = 0;
									$no_baris++;
									$brs = count($data_invoice[$row->barang_id.$row->warna_id])/5;
									$brs = ceil($brs);
									if ($brs == 0) {
										$brs = 1;
									}

									if ($row->closed_user != '-') {
										$t_barang--;;
									}
									for ($x=0; $x < $brs ; $x++) {
										$bg = ($row->closed_user == '-' ? "" : "background:#cef5ce");
										$bg1 = ($row->closed_user == '-' ? "" : "border-top:2px solid green;  border-left:2px solid green; border-right:2px solid green");
										$bg2 = ($row->closed_user == '-' ? "" : "border-bottom:2px solid green;  border-left:2px solid green; border-right:2px solid green");
										if ($x == 0) {?>
												<tr style="<?=$bg;?>">
													<td rowspan="<?=$brs + 1?>" class='text-center'><?=$no_baris?></td>
													<td rowspan="<?=$brs + 1?>">
														<?=$row->nama_barang?>
														<?=$row->nama_warna?>
													</td>
													<td rowspan="<?=$brs+1?>"  class='text-center'><?=$row->nama_satuan;?> </td>
													<td rowspan="<?=$brs + 1?>" rowspan="<?=$brs?>" class='text-center'>
														<?=str_replace(",00","",number_format($row->qty_po,'2',",","."));?> <br/>
														<!-- <?=$row->nama_satuan;?> -->
													</td>
													<?for ($y=0; $y < 5 ; $y++) { 
														$idx = $y + ( $x * 5 );?>
														<td>
															<?if (isset($data_invoice[$row->barang_id.$row->warna_id][$idx])) {
																$p_id = $data_invoice[$row->barang_id.$row->warna_id][$idx]['penjualan_id'];
																$nf = $data_invoice[$row->barang_id.$row->warna_id][$idx]['no_faktur_lengkap'];
																$tgl = $data_invoice[$row->barang_id.$row->warna_id][$idx]['tanggal'];
																$q =  (float)$data_invoice[$row->barang_id.$row->warna_id][$idx]['qty'];
																$sisa_po[$row->barang_id.$row->warna_id] -= $q;
																$subtotal += $q;
																?>
																	<a target="_blank" href="<?=base_url().is_setting_link('transaction/penjualan_list_detail').'?id='.$p_id;?>"><?=$q;?></a>
																	<a 
																		
																		class='hidden-print'
																		data-toggle="popover" 
																		data-trigger='hover' 
																		title="Info"
																		data-html='true' 
																		data-content="No Invoice : <b><?=$nf?></b> <br/>Tanggal : <b><?=is_reverse_date($tgl);?></b>"
																	><i style="font-size:1.2em" class="fa fa-info-circle"></i></a>
																<?
															}?>
														</td>
													<?}?>
													<td rowspan="<?=$brs+1?>"></td>
													<td rowspan="<?=$brs+1?>">
														<?=str_replace(",00","",number_format($sisa_po[$row->barang_id.$row->warna_id],'2',",","."));?>
														<!-- <?=$row->nama_satuan;?> -->
														<br/>
														<?if ($row->closed_user == '-') {?>
															<div id='checkbox-<?=$row->id;?>' >
																<label style='background:#ddd; padding:5px 10px 5px 5px' >
																<input onchange="toggleDaftarClose('<?=$row->id;?>','<?=$row->nama_barang?> <?=$row->nama_warna?>')" type='checkbox' class='lock-po' id="box-<?=$row->id;?>" data-id='<?=$row->id;?>'>LOCK</label>
															</div>
															<!-- <button class="btn btn-sm yellow-gold" onclick="closeBarangPO('<?=$row->id;?>','<?=$row->po_penjualan_id;?>','<?=$row->nama_barang?>')">CLOSE</button> -->
														<?}else{?>
															<!-- <button class="btn btn-sm blue" > OPEN</button>
															<div style='display:inline-block; top:5px; color:#888; position:absolute; width:100px; word-wrap:break; font-size:0.9em'>Closed By <?=$row->closed_user?></div>  -->
															<button class='btn btn-xs default hidden-print' onclick="openBarangPO('<?=$row->id;?>','<?=$row->po_penjualan_id;?>','<?=$row->nama_barang?>')"><i class='fa fa-lock'></i> LOCKED</button>
														<?}?>
													</td>
												</tr>

										<?}else{?>
											<tr>
											<?for ($y=0; $y < 5 ; $y++) { 
												$idx = $y + ( $x * 5 );?>
												<td>
													<?if (isset($data_invoice[$row->barang_id.$row->warna_id][$idx])) {
														$p_id = $data_invoice[$row->barang_id.$row->warna_id][$idx]['penjualan_id'];
														$nf = $data_invoice[$row->barang_id.$row->warna_id][$idx]['no_faktur_lengkap'];
														$tgl = $data_invoice[$row->barang_id.$row->warna_id][$idx]['tanggal'];
														$q =  (float)$data_invoice[$row->barang_id.$row->warna_id][$idx]['qty'];
														$sisa_po[$row->barang_id.$row->warna_id] -= $q;
														$subtotal += $q;
														?>
															<a target="_blank" href="<?=base_url().is_setting_link('transaction/penjualan_list_detail').'?id='.$p_id;?>"><?=$q;?></a>
															<a 
																
																class='hidden-print'
																data-toggle="popover" 
																data-trigger='hover' 
																title="Info"
																data-html='true' 
																data-content="No Invoice : <b><?=$nf?></b> <br/>Tanggal : <b><?=is_reverse_date($tgl);?></b>"
															><i style="font-size:1.2em" class="fa fa-info-circle"></i></a>
														<?
													}?>
												</td>
											<?}?>
											</tr>
										<?}?>
										<?}?>
										<tr style="<?=$bg;?>;">
											<td colspan="5" style="border-right:1px solid #ddd" >TOTAL : <?=str_replace(",00","",number_format($subtotal,'2',",","."));?> </td>
										</tr> 
								<?}?>
							</tbody>
						</table>

						<div style="margin-top:20px;">
							
							<button class='btn btn-lg blue hidden-print' onclick="window.print()"><i class='fa fa-print'></i> Print</button>
							<?if ($t_barang > 0) {?>
								<button disabled class='btn btn-lg red hidden-print' id="btnCloseMultiple" onclick="lockPOAll()" ><i class='fa fa-lock'></i> LOCK <span id="lock-count"></span></button>
							<?}?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>			
</div>

<script src="<?php echo base_url('assets/global/plugins/bootstrap-modal/js/bootstrap-modalmanager.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/bootstrap-modal/js/bootstrap-modal.js'); ?>" type="text/javascript"></script>

<script src="<?php echo base_url('assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/datatables/media/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/bootbox/bootbox.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets_noondev/js/table-advanced.js'); ?>"></script>

<script>
var po_warna_id = [];
var po_last_datang = [];
const po_penjualan_id = "<?=$po_penjualan_id;?>";
var closeList = [];
const btnCloseMultiple = document.querySelector("#btnCloseMultiple");

jQuery(document).ready(function() {

	// dataTableTrue();

	// TableAdvanced.init();

	// oTable = $('#general_table').DataTable();
	// oTable.state.clear();
	// oTable.destroy();
   	$('[data-toggle="popover"]').popover();	  	
   	$('#other-po').select2();
   	var lock_po_nama = {};


	$('.btn-save').click(function(){
		if ($('#form_add_data [name=tanggal]').val() != '' ) {
			// $('#form_add_data').submit();
			if ($('#form_add_data [name=tanggal]').prop('selectedIndex') != 0) {
				bootbox.confirm("Disarankan untuk membuat penutupan <b>sesuai urutan bulan</b>. <br/>Yakin lanjutkan ?", function(respond){
					if (respond) {
						$('#form_add_data').submit();
					};
				});
			}else{
				$('#form_add_data').submit();
			};
		}else{
			alert('Tanggal harus diisi');
		}
	});

	/*$("#general_table").on('change','.lock-po', function(){
		var ini = $(this).closest('tr');
		var id = $(this).attr('data-id');
		if ($(this).is(':checked')) {
			lock_po_nama[id] = ini.find('.nama-barang').html();
		}else{
			var lock_po_nama_ori = lock_po_nama;
			lock_po_nama = {};
			lock_po = {};
			$.each(lock_po_nama_ori, function(i,v){
				if (id != i) {
					lock_po_nama[i]=v;
				};
			});
		}
	});*/

	/*$("#general_table .lock-po").each(function(){
		po_warna_id = [];
		var ini = $(this).closest('tr');
		var id = $(this).attr('data-id');
		var last_datang = $(this).attr('data-last');
		if ($(this).is(':checked')) {
			po_warna_id.push(id);
			po_last_datang.push(last_datang);
		}
	});*/

	

	$(document).on('click','.unlock-po', function(){
		var button_ini = $(this);
		var ini = $(this).closest('tr');
		var po_penjualan_warna_id = ini.find('.po_penjualan_warna_id').html();
		bootbox.confirm("<span style='color:red'>Unlock</span> PO <b>"+ini.find('.nama-barang').html()+"</b> ini?", function(respond){
			// if (respond) {
			// 	let data = {};
			// 		data['po_penjualan_warna_id'] = ini.find('.po_penjualan_warna_id').html();
			// 		console.log(data['po_penjualan_warna_id']);
			// 		let url = 'report/po_penjualan_warna_unlock';
			// 		ajax_data_sync(url,data).done(function(data_respond  /*,textStatus, jqXHR*/ ){
			// 			if (data_respond == 'OK') {
			// 				button_ini.hide();
			// 				ini.removeClass('bg-selected');
			// 				ini.find(".lock-po").show();
			// 				ini.find('.locked-info').hide();
			// 				$("#checkbox-"+po_penjualan_warna_id).show();
			// 				ini.find('.loading-info').hide();
			// 			};
			// 		});
			// };
		});
	});

});

function toggleDaftarClose(id, nama_barang){
	console.log(id, nama_barang);
	const isChecked = document.querySelector(`#box-${id}`).checked;
	if (isChecked) {
		closeList.push({
			id:id,
			nama_barang:nama_barang
		});
	}else{
		// let index = null;
		// closeList.forEach((list,index) => {
		// 	if (list.id == id) {
		// 		index = index
		// 	}
		// });
		
		const n = closeList.filter((list, index) => list.id != id);
		closeList = n;
	}

	if (closeList.length == 0) {
		btnCloseMultiple.disabled = true;
	}else{
		btnCloseMultiple.disabled = false;
	}

}

function lockBarangPO(id, po_penjualan_id, nama_barang){
	bootbox.confirm(`Close <b>${nama_barang}</b>  di PO ini ? <br/> Close artinya barang sudah terpenuhi, <br/>dan <b>${nama_barang}</b> tidak akan muncul di daftar barang <br/>ketika invoice baru untuk nomor PO ini dibuat`, function(respond){
		if (respond) {
			const dialog = bootbox.dialog({
				message: '<p class="text-center mb-0"><i class="fa fa-spin fa-cog"></i> Closing po.....</p>',
				closeButton: false
			});
			fetch(baseurl+`report/po_penjualan_close_perbarang?id=${po_penjualan_id}&id=${id}&status=1`)
			.then((response) => response.json())
			.then((data) => {
				if (data == "OK") {
					dialog.find('.bootbox-body').html('Update sukses. refresh page....');
					setTimeout(() => {
						dialog.modal('hide');
						window.location.reload();
					}, 1000);
				}else{
					dialog.modal('hide');
					bootbox.alert("Error");
				}
			});
		}
	})
}

function openBarangPO(id, po_penjualan_id, nama_barang){
	bootbox.confirm(`Buka lock <b>${nama_barang}</b>  di PO ini ? <br/> Open artinya barang <b>${nama_barang}</b> bisa akan muncul di daftar barang <br/>ketika invoice baru untuk nomor PO ini dibuat`, function(respond){
		if (respond) {
			const dialog = bootbox.dialog({
				message: '<p class="text-center mb-0"><i class="fa fa-spin fa-cog"></i> Closing po.....</p>',
				closeButton: false
			});
			fetch(baseurl+`report/po_penjualan_close_perbarang?id=${po_penjualan_id}&id=${id}&status=0`)
			.then((response) => response.json())
			.then((data) => {
				if (data == "OK") {
					dialog.find('.bootbox-body').html('Update sukses. refresh page....');
					setTimeout(() => {
						dialog.modal('hide');
						window.location.reload();
					}, 1000);
				}else{
					dialog.modal('hide');
					bootbox.alert("Error");
				}
			});
		}
	})
}

function lockPOAll(){
	let list  = "";

	closeList.forEach(item => {
		list += `<b>- ${item.nama_barang} </b> <br/>`;
	});

	const ids = closeList.map(list => list.id).join("-");
	bootbox.confirm(`Yakin close PO untuk barang di bawah ini ? <br> ${list}`, function(respond){
		if (respond) {
			const dialog = bootbox.dialog({
				message: '<p class="text-center mb-0"><i class="fa fa-spin fa-cog"></i> Updating.....</p>',
				closeButton: false
			});
			fetch(baseurl+`report/po_penjualan_close?po_penjualan_id=${po_penjualan_id}&ids=${ids}`)
			.then((response) => response.json())
			.then((data) => {
				if (data == "OK") {
					dialog.find('.bootbox-body').html('Update sukses. refresh page....');
					setTimeout(() => {
						dialog.modal('hide');
						window.location.reload();
					}, 1000);
				}else{
					dialog.modal('hide');
					bootbox.alert("Error");
				}
			});
		}
	})
}

function unlockPO(){
	bootbox.confirm("Yakin buka kembali PO ini ? ", function(respond){
		if (respond) {
			const dialog = bootbox.dialog({
				message: '<p class="text-center mb-0"><i class="fa fa-spin fa-cog"></i> Updating.....</p>',
				closeButton: false
			});
			fetch(baseurl+`report/po_penjualan_close?po_penjualan_id=${po_penjualan_id}&status_po=0`)
			.then((response) => response.json())
			.then((data) => {
				if (data == "OK") {
					dialog.find('.bootbox-body').html('Update sukses. refresh page....');
					setTimeout(() => {
						dialog.modal('hide');
						window.location.reload();
					}, 1000);
				}else{
					dialog.modal('hide');
					bootbox.alert("Error");
				}
			});
		}
	})
}

function change_bg(ini){
	ini.find('td').addClass('.bg-selected');
}

</script>
