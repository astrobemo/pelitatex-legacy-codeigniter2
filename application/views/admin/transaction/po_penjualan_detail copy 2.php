<link href="<?=base_url('assets/global/plugins/bootstrap-modal/css/bootstrap-modal.css');?>" rel="stylesheet" type="text/css"/>

<!-- <link href="<?=base_url('assets_noondev/css/bootstrap-modal-bs3patch.css');?>" rel="stylesheet" type="text/css"/> -->
<?php echo link_tag('assets/global/plugins/bootstrap-datepicker/css/datepicker3.css'); ?>

<?
	$po_penjualan_id = '';
	$tipe = 0;
	$po_number = "";
	$tanggal = "";
	$tanggal_show = "";
	$tanggal_kirim = "";
	$customer_id = '';
	$alamat_kirim = '';
	$po_number ='';
	$diskon = 0;
	$biaya_lain = 0;
	$ppn_include_status = 1;
	$ppn_value ='';
	$keterangan = '';
	$keterangan_footer = '';
	$status_po = '';
	$nama_customer = '';
	$closed_date = '';
	$contact_person = '';

	$closed_by_user = '';


	foreach ($data_header as $row) {
		$po_penjualan_id = $row->id;
		$tipe = $row->tipe;
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
		$keterangan_footer = $row->keterangan_footer;
		$contact_person = $row->contact_person;

		$status_po = $row->status_po;
		$closed_date = date("d F Y", strtotime($row->closed_date));
		$closed_by_user = $row->closed_by_user;
	?>
		
	<?}?>

<style type="text/css">
#tbl-data input[type="text"], #tbl-data select{
	height: 25px;
	width: 50%;
	padding: 0 5px;
}

#qty-table input, #qty-table-edit input{
	width: 80px;
	padding: 5px;
}

#qty-table-detail tr td, #qty-table-detail-edit tr td{
	border: 1px solid #ccc;
	padding: 3px;
	text-align: center;
	min-width: 50px;
	font-size: 16px;
}

#stok-info, #stok-info-edit{
	font-size: 1.5em;
	position: absolute;
	right: 50px;
	top: 30px;
}

#qty-table-detail, #qty-table-detail-edit{
	position: absolute;
	right: 50px;
	top: 120px;
}

#qty-table-detail .selected{
	background: lime;
}

.yard-info{
	font-size: 1.5em;
}

.no-faktur-lengkap{
	font-size: 2.5em;
	font-weight: bold;
}

.input-no-border{
	border: none;
}

.subtotal-data{
	font-size: 1.2em;
}

.textarea{
	resize:none;
}

#bayar-data tr td{
	font-size: 1.5em;
	font-weight: bold;
	padding: 0 10px 0 10px;
}

#bayar-data tr td input{
	padding: 0 5px 0 5px;
	border: 1px solid #ddd;
}

.alamat{
	overflow: hidden;
	text-overflow:ellipsis;
	width: 150px;
}

#jatuh-tempo-list, #jatuh-tempo-list-edit{
	display: none;
	max-height: 150px;
	overflow: auto;
	border: 1px solid #eee;
	padding: 3px;
	/*position: absolute;
	top: 0px;
	right: 0px;*/
}

#table-barang{
	width:100%;
}

#table-barang tr td, #table-barang tr th{
	padding:5px 10px;
}

#table-barang tr td,
#table-barang tr:nth-child(1) th{
	border-top:1px solid lightblue;
}


#jatuh-tempo-list tr td, #jatuh-tempo-list-edit tr td{
	padding: 2px 5px;
}

#jatuh-tempo-rekap{
	display: none;
}

#data-header tr td:nth-child(2){
	padding:0 5px;
}

.editable{
	position:relative;
	cursor:pointer;
}

.editable small{
	display:none;
	position:absolute;
	right:0;
	background:lightpink;
}

<?if ($status_po==1) {?>
	.editable:hover small{
		display:block;
	}
<?}?>

#invoice-data{
	margin:10px 0px;
	border:1px solid #ddd;
}

#invoice-data tr td, #invoice-data tr th{
	padding:5px;
	border:1px solid #ddd;
}

.invoice-item-container{
	display: flex;
	justify-content: flex-start;
	align-items: center;
}

.invoice-item{
	width:80px;
	padding: 5px 5px 5px 0px;
}

#formFilterContainer{
	display: flex;
	flex-direction: row;
	justify-content: space-between;
	align-items: flex-start;
}

#poImageContainer{
	width: 120px;
	height: 130px;
}

#imagePlaceholder{
	display: flex;
	flex-direction: column;
	font-size: 30;
	justify-content: center;
	align-items: center;
	background: #ddd;
	width: 120px;
	height: 100px;
}

#imagePlaceholder{
	border: 1px solid #ddd;
	text-align: center;
}

#poImageUploadLabel{
	background-color: lightblue;
	width: 80px;
	text-align: center;
	cursor: pointer;
	padding: 8px;
}

#poImageRemoveLabel{
	padding: 8px;
	height:36px;
	width: 40px;
	display: flex;
	flex-direction: column;
	justify-content: center;
	align-items: center;
	background: lightpink;
	cursor: pointer;
}


#poImageLabelContainer{
	width:120px;
	display: flex;
	flex-direction: row;
	justify-content: center;
	align-items: center;
}
</style>

<?$barang_invoice = array();
foreach ($data_barang_invoice as $row) {
	if (!isset($barang_invoice[$row->barang_id][$row->warna_id])) {
		$barang_invoice[$row->barang_id][$row->warna_id] = array();
	}

	array_push($barang_invoice[$row->barang_id][$row->warna_id], array(
		'qty' => $row->qty,
		'jumlah_roll' => $row->jumlah_roll,
		'no_fp' => $row->no_faktur_fp,
		'penjualan_id' => $row->penjualan_id,
		'tanggal' => $row->tanggal
	));
}?>

<div class="page-content" >
	<div class='container'>
		

		<?include_once 'po_penjualan_detail_modal.php';?>

		<div class="row margin-top-10">
			<div class="col-md-12">
				<div class="portlet light" >
					<div class="portlet-title">
						<div class="caption caption-md">
							<i class="icon-bar-chart theme-font hide"></i>
							<span class="caption-subject theme-font bold uppercase"><?=$breadcrumb_small;?></span>
						</div>
						<div class="actions hidden-print">
							<a href="#portlet-config" data-toggle='modal' class="btn btn-default btn-sm btn-form-add">
							<i class="fa fa-plus"></i> PO Baru </a>
							<a href="#portlet-config-faktur" data-toggle='modal' class="btn btn-default btn-sm">
							<i class="fa fa-search"></i> Cari PO </a>
						</div>
					</div>
					<div class="portlet-body">

						<div>
							<?if ($status_po == 2) {?>
								<div class='note note-warning'>PO sudah di <b>tutup <?=$closed_date;?></b> oleh <?=$closed_by_user?></div>
							<?}?>
						</div>

						<div id="formFilterContainer">
							<table id="data-header">
								<tr>
									<td>
										<?if ($status_po == 1) {?>
											<a href="#portlet-config-edit" data-toggle='modal' class="btn btn-xs btn-block default"><i class="fa fa-edit"></i>edit</a>
										<?}else if($status_po == 0){?>
											<a href="#portlet-config-pin" data-toggle='modal' class="btn btn-xs btn-block yellow-gold"><i class="fa fa-edit"></i>OPEN LOCK</a>
										<?}else{?>
										<?}?>
									</td>
									<td></td>
									<td></td>
								</tr>
								<tr>
									<td style="font-size:1.3em;">Customer</td>
									<td> : </td>
									<td style="font-size:1.3em;"><b><?=$nama_customer?></b></td>
								</tr>
								<tr>
									<td style="font-size:1.3em;">No <?=($tipe==1 ? "PO" : "Request");?></td>
									<td> : </td>
									<td style="font-size:1.3em;"><b><?=$po_number?></b></td>
								</tr>
								<tr>
									<td>Attn.</td>
									<td> : </td>
									<td><?=$contact_person?></td>
								</tr>
								<tr>
									<td>Tanggal</td>
									<td> : </td>
									<td><?=$tanggal_show?></td>
								</tr>
								<tr hidden>
									<td>Tanggal Kirim</td>
									<td> : </td>
									<td><?=$tanggal_kirim?></td>
								</tr>
								<tr hidden>
									<td>Harga include PPN</td>
									<td> : </td>
									<td>
										<?if ($po_penjualan_id != '') {
											echo ($ppn_include_status ? "ya" : "tidak");
										}?>
										<!-- <input type="checkbox" <?=($ppn_include_status ? "checked" : "")?> id="ppnIncludeStatus" onchange="changePPNStatus()"> -->
									</td>
								</tr>
								<tr hidden>
									<td>Keterangan</td>
									<td> : </td>
									<td><?=$keterangan?></td>
								</tr>
							</table>
							<div id="poImageContainer" hidden>
								<div id="imagePlaceholder">
									<span>
										<i style="font-size: 24px;" class="fa fa-file-image-o"></i><br/>
										<span>
											gambar po
										</span>
									</span>
								</div>
								<div id='poImageLabelContainer'>
									<label id="poImageUploadLabel">
										Upload
										<span style="display:none">
											<input style="opacity: 0;" accept="image/*" type='file' id="imgInp" >
										</span>
									</label>
									<label id="poImageRemoveLabel" onclick="removePOImage()">
										<i class="fa fa-times"></i>
									</label>
								</div>
							</div>
							<canvas id="canvas" style="display: none;"></canvas>
							<!-- <img id="blah" src="#" alt="your image" /> -->
						</div>

						<img id="outputImage" style="display: none;">
						
						<hr>
						<!-- class='table' -->
						<table id='table-barang' >
							<thead>
								<tr>
									<th>No</th>
									<th>Barang
										<?if ($status_po == 1) {?>
											<a href="#portlet-config-detail" data-toggle="modal" class='btn btn-xs blue' id="btnAddBarang"><i class="fa fa-plus"></i></a>
										<?}?>
									</th>
									<th>Warna</th>
									<th>QTY</th>
									<th class='text-right' style="width:200px">Harga <small hidden style='font-size:0.8em'>(DPP)</small></th>
									<th class="text-right">TOTAL</th>
									<!-- <th>Note</th> -->
									<th></th>
								</tr>
							</thead>
							<tbody>
								<?$subtotal=0;$subqty=0;
								$total = 0; 
								$ppn_pembagi = 1 + ($ppn_value/100);
								$dpp_s = ($ppn_include_status == 0 ? "font-weight:bold;" : "font-size:0.8em");
								$jual_s = ($ppn_include_status == 1 ? "font-weight:bold" : "font-size:1em");
								foreach ($data_barang as $key => $row) {
									$subqty += $row->qty;
									$subtotal += $row->harga*$row->qty;
									$dpp = ($ppn_include_status == 1 ? $row->harga/$ppn_pembagi : $row->harga);
									$total_jual=0;
									?>
									<tr>
										<td><?=$key+1;?></td>
										<td>
											<b><?=$row->nama_barang?></b><br>
											<!-- <small><?=$row->keterangan?></small> -->
										</td>
										<td>
											<?=$row->nama_warna?>
										</td>
										<td><?=number_format($row->qty,'0',",",".");?></td>
										<td class='text-right'>
											<span style="<?=$jual_s?>">
												<?=number_format($row->harga,'0',",",".");?>
											</span>
											<span hidden style="<?=$dpp_s?>">
												<br/>
												(<?=number_format($dpp,($ppn_include_status == 1 ? '2' : '0'),",",".");?>)
											</span>
										</td>
										<td class="text-right">
											<span style="<?=$jual_s?>">
												<?=number_format($row->harga*$row->qty,'0',",",".");?>
											</span>
											<span hidden style="<?=$dpp_s?>">
												<br/>
												(<?=number_format($dpp*$row->qty,'0',",",".");?>)
											</span>
										</td>
										<td class="text-center">
											<?if ($status_po == 1) {?>
												<button class="btn btn-xs green" onclick="editData('<?=$row->id;?>')"><i class='fa fa-edit'></i></button>
												<button class="btn btn-xs red" onclick="removeData('<?=$row->id;?>')"><i class='fa fa-times'></i></button>
											<?}
											/* elseif ($row->closed_by != ''){?>
												<!-- closed by<br/><?=$row->username;?> -->
												<i class="fa fa-lock"></i> <?=$row->username;?>
											<?} */?>
										</td>
									</tr>
									<tr class="invoice-info">
										<td style="border-top:none; background-color:rgba(200,200,200,0.2)">
											Invoice : 
										</td>
										<td colspan='6' style="border-top:none; background-color:rgba(200,200,200,0.2)">
											<div class='invoice-item-container'>												
												<?if (isset($barang_invoice[$row->barang_id][$row->warna_id])) {
													foreach ($barang_invoice[$row->barang_id][$row->warna_id] as $k2 => $v2) {
														$total_jual += $v2['qty'];
														$info = "<i class=\"fa fa-file-o\"></i> : 
														<b>
															<a style=\"color:blue\" target=\"_blank\" href=\"".
															base_url().is_setting_link('transaction/penjualan_list_detail')."?id=".$v2['penjualan_id']."\">"
															.$v2['no_fp'].
															"</a>
														</b> <br/>
														<i class=\"fa fa-calendar\"></i> : <b>"
															.is_reverse_date($v2['tanggal']).
														"</b>";?>
														<div class='invoice-item'>
															<?=str_replace(",00","",number_format($v2['qty'],2,",","."));?>
															<a 
																style="margin-left:5px"
																data-placement="bottom"
																class='hidden-print info-popover'
																data-toggle="popover" 
																data-trigger='click' 
																title="Info" 
																data-html='true'
																data-content='<?=$info;?>'>
																	<i class="fa fa-info-circle"></i>
															</a>
															<!-- href="<?=base_url().is_setting_link('transaction/penjualan_list_detail')?>?id=<?=$v2['penjualan_id']?>"  -->
														</div>
													<?}
												}
												?>
											</div>
										</td>
									</tr>
									<tr class="invoice-info">
										<td style="border-top:none; border-bottom:1px solid lightblue; background-color:rgba(200,200,200,0.2)">
											
										</td>
										<td colspan='6'  style="border-top:none; border-bottom:1px solid lightblue; background-color:rgba(200,200,200,0.2)">
											<div style="padding-right:50px;display:inline-block">Kirim : <b><?=str_replace(",00","",number_format($total_jual,2,",","."));?></b></div>
											<div style="padding-right:50px;display:inline-block">Sisa : <b><?=str_replace(",00","",number_format($row->qty-$total_jual,2,",","."));?></b></b></div>
										</td>
									</tr>
								<?}

								$total = $subtotal - $diskon;
								$ppn_pengali = ($ppn_value/100);
								$dpp = $total;
								$ppn = 0;
								if ($ppn_include_status == 1) {
									$dpp = $total/(1+$ppn_pengali);
									$ppn = $total - $dpp;
								} 

								?>
							</tbody>
							<tfoot style='font-size:1.15em'>
								<tr>
									<th colspan='7'></th>
								</tr>
								<tr>
									<th rowspan='3' colspan='4'  style="vertical-align:top; padding:0px; padding-right:10px">
										notes : <span class="label label-sm label-success"></span> <br/>
										<textarea 
											name="keterangan_footer" <?=($status_po == 0 ? 'readonly' : '')?> 
											style="width:80%; border:none; background-color:#eee" 
											id="keteranganFooter" class="form-control" rows="4" 
											maxlength='500' 
											onchange="updateKetFooter()" placeholder="Keterangan..."><?=$keterangan_footer;?></textarea>
									</th>
									<th class="text-right" style=""> 
										<!-- TOTAL <br> -->
										<span>SUBTOTAL</span><br>
										<small style="font-weight: normal;font-size:0.9em">Includes VAT/PPN [<?=(float)$ppn_value;?>%]</small>
										
									</th>
									<th class="text-right"  style="">
										<?//=number_format($subtotal,'0',",",".");?>
										<span><?=number_format($subtotal,'0',",",".");?></span><br>
										<span style="font-weight: normal;font-size:0.9em"><?=number_format($ppn,'0',",",".");?></span>
									</th>
									<th style="" rowspan="3"></th>
								</tr>
								
								<?
								$total += $biaya_lain;
								?>
								<tr style="border-top:1px solid #ddd;border-bottom:1px solid #ddd;">
									<!-- <th></th>
									<th></th>
									<th></th> -->
									<th class="text-right" > BIAYA LAIN-LAIN </th>
									<th class="text-right editable" <?=($status_po == 1 ? "ondblclick='showInputBiayaLain()'" : '');?> style="width:200px; " >
										<span id="biayaLainContainer" hidden>
											<input class='text-right' onchange="updateBiayaLain()" style="width:150px;" value="<?=number_format($biaya_lain,'0',",",".");?>" id="inputBiayaLain" >
											<a style="cursor:pointer" onclick="hideInputBiayaLain()"><i class="fa fa-times"></i></a>
										</span>
										<span id="textBiayaLain">
											<span ><?=number_format($biaya_lain,'0',",",".");?></span><br/>
											<small>double click untuk edit</small>
										</span>
									</th>
								</tr>
								<tr  style='font-size:1.2em'>
									<!-- <th></th>
									<th></th>
									<th></th> -->
									<th class="text-right" style=""> TOTAL </th>
									<th class="text-right" style=""><?=number_format($total,'0',",",".");?></th>
								</tr>
								<tr>
									<th colspan='7'></th>
								</tr>
							</tfoot>
						</table>

						<!-- <div>
							<table id='invoice-data'>
								<thead>
									<tr>
										<th>NO INVOICE</th>
										<th>TANGGAL</th>
									</tr>
								</thead>
								<tbody>
									
								</tbody>
							</table>
						</div> -->
						<div hidden>
							<b>INVOICE LIST : </b>
							<?/*foreach ($data_invoice as $row) {?>
								<ul>
									<li> <?=$row->no_faktur_lengkap?> : <?=date('d F Y', strtotime($row->tanggal));?> <a href="<?=base_url().is_setting_link('transaction/penjualan_list_detail')?>?id=<?=$row->id?>" target="_blank">view</a></li>
								</ul>
							<?}*/?>
						</div>
						

						<div class='text-left' style='margin-top:50px'>
							<?if ($status_po == 1 && $po_penjualan_id !='') {?>
								<button <?=($status_po == 1 ? "" : "disabled")?> type='button' class='btn btn-lg red hidden-print' id='btn-lock-po' onclick="lockPO()"><i class='fa <?=($status_po == 1 ? "fa-unlock" : "fa-lock")?>'></i> <?=($status_po == 1 ? "LOCK" : "LOCKED")?> </button>
							<?}?>

							<?if ($status_po == 0 && $po_penjualan_id !='') {?>
								<!-- <a href="#portlet-config-pin" data-toggle='modal' class="btn btn-lg default"><i class="fa fa-edit"></i>OPEN LOCK</a> -->
								<a href="#portlet-config-invoice" data-toggle="modal" type='button' class='btn btn-lg blue hidden-print' id='btn-create-invoice'>CREATE INVOICE</a>
								<a type='button' class='btn btn-lg red hidden-print' id='btn-finish-po' onclick="finishingPO()">FINISH</a>
							<?}?>
						</div>
					</div>
					
				</div>
			</div>
		</div>
	</div>
</div>

<div id='overlay-div' hidden style="left:0px; top:0px; position:fixed; height:100%; width:100%; background:rgba(0,0,0,0.5)">
	<p style="position:relative;color:#fff;top:40%;left:40%">Loading....</p>
</div>

<script src="<?php echo base_url('assets/global/plugins/bootstrap-modal/js/bootstrap-modalmanager.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/bootstrap-modal/js/bootstrap-modal.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/jquery-validation/js/jquery.validate.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/bootbox/bootbox.min.js'); ?>" type="text/javascript"></script>

<script src="<?php echo base_url('assets/global/plugins/select2/select2.min.js'); ?>" type="text/javascript" ></script>

<script>

const po_penjualan_id = "<?=$po_penjualan_id;?>";
const customer_id = "<?=$customer_id?>";

const formAddBarang = document.querySelector(`#form_add_barang`);
const formEditBarang = document.querySelector(`#form_edit_barang`);
const btnAddBarang = document.querySelector('#btnAddBarang');
const formInvoice = document.querySelector('#form_invoice');
const items = <?=json_encode($data_barang);?>;

const hargaAdd=document.querySelector("#harga_add");
const hargaDPPAdd=document.querySelector("#harga_dpp_add");
const hargaDPPEdit=document.querySelector("#harga_dpp_edit");
const ppnValue=parseFloat("<?=$ppn_value;?>");
const ppnStatus=parseFloat("<?=$ppn_include_status;?>");

const hargaEdit=document.querySelector("#harga_edit");
const keteranganFooter=document.querySelector("#keteranganFooter");
const inputDiskon=document.querySelector("#inputDiskon");
const inputBiayaLain=document.querySelector("#inputBiayaLain");
const textDiskon=document.querySelector("#textDiskon span");
const textBiayaLain=document.querySelector("#textBiayaLain span");

const btnSubmit = document.querySelector("#btnAddPOPenjualan");
const btnSubmitEdit = document.querySelector("#btnEditPOPenjualan");
const formAdd = document.querySelector("#form_add_data");
const formEdit = document.querySelector("#form_edit_data");
const formLock = document.querySelector("#form-lock");
const inputPOStatus = document.querySelector('#inputPOStatus');
const inputPIN = document.querySelector('#pinInput');
const btnOpenLockSubmit = document.querySelector("#btnOpenLockSubmit");
const barangList = <?=json_encode($this->barang_list_aktif);?>;
const imgInp = document.querySelector("#imgInp");
const canvas = document.querySelector('#canvas');
const ctx = canvas.getContext('2d');
const poInput = document.querySelector("#po_number");
const poInputEdit = document.querySelector("#po_number_edit");
let poInit = "<?=$po_number?>";


let isInfoShown = false;

addRupiahFocusin(hargaAdd);
addRupiahFocusOut(hargaAdd);
addRupiahFocusin(hargaEdit);
addRupiahFocusOut(hargaEdit);

// addRupiahFocusin(inputDiskon);
// addRupiahFocusOut(inputDiskon);
addRupiahFocusin(inputBiayaLain);
addRupiahFocusOut(inputBiayaLain);

btnSubmit.addEventListener("click", function(){
	const customer_id = document.querySelector("#customer_id_select").value;
	const tanggal = document.querySelector("#tanggal_add").value;
	const po_number = document.querySelector("#po_number").value;
	const tipe = tipePO;
	
	<?if (is_posisi_id()==1) {?>
		alert(tipe)
	<?}?>
	if (customer_id != '' && tanggal != '') {
		if (tipe == 2) {
			formAdd.submit();
		}else if (tipe == 1){
			if (po_number == '') {
				bootbox.alert("Mohon isi po number !");
			}else{
				formAdd.submit();
			}
		}else{
			alert("error");
		}
	}else{
		bootbox.alert("Mohon isi tanggal dan customer");
	}
});

btnSubmitEdit.addEventListener("click", function(){
	const customer_id = document.querySelector("#customer_id_edit").value;
	const tanggal = document.querySelector("#tanggal_edit").value;
	const po_number = document.querySelector("#po_number_edit").value;
	
	if (customer_id != '' && tanggal != '' && po_number != '') {
		formEdit.submit();
	}else{
		bootbox.alert("Mohon isi tanggal, customer, dan po number !");
	}
});

hargaAdd.addEventListener("change", function(e){
	const v = currency.removeRupiah(e.target.value);
	let dpp = v;
	if (ppnStatus == 1) {
		dpp = v / (1+(ppnValue/100));
	}
	hargaDPPAdd.value = dpp.toFixed(2);
})

hargaEdit.addEventListener("change", function(e){
	const v = currency.removeRupiah(e.target.value);
	let dpp = v;
	if (ppnStatus == 1) {
		dpp = v / (1+(ppnValue/100));
	}
	hargaDPPEdit.value = dpp.toFixed(2);
})

imgInp.onchange = evt => {
	if (imgInp.files && imgInp.files[0]) {
        const reader = new FileReader();
		const selectedFile = imgInp.files[0];
		const fileSize = selectedFile.size;
		const fileSizeMB = (fileSize / (1024 * 1024)).toFixed(2); // Size in megabytes (MB)

		const qFinal = (fileSizeMB > 7 ? 0.3 : (fileSizeMB > 3 ? 0.5 : (fileSizeMB > 1 ? 0.7 : 1)));
        reader.onload = function (e) {

			const img = new Image();
				
			img.onload = function() {
				console.log(qFinal);
				// Set the canvas size to the new dimensions
				canvas.width = img.width;
				canvas.height = img.height;

				// Draw the image onto the canvas with the new dimensions
				ctx.drawImage(img, 0, 0, img.width, img.height);

				// Convert the canvas content to a compressed image
				canvas.toBlob(function(blob) {
					const compressedImage = new Image();
					compressedImage.src = URL.createObjectURL(blob);
	
					compressedImage.onload = function() {
						// Display the compressed image
						// outputImage.src = compressedImage.src;
						imagePlaceholder.style.backgroundImage = `url('${compressedImage.src}')`;

						outputImage.src = compressedImage.src;
						uploadImage(blob);
						// outputImage.style.display = 'block';
					};
				}, 'image/jpeg', qFinal);
			}

			// console.log(compressedImage.src);
			img.src = e.target.result;

			imagePlaceholder.style.backgroundRepeat = 'no-repeat';
			imagePlaceholder.style.backgroundSize = 'contain';
			imagePlaceholder.style.backgroundPosition = 'center center';

			const spanImage = imagePlaceholder.querySelector("span");
			spanImage.style.opacity = 0;
			
        };

        reader.readAsDataURL(imgInp.files[0]);
    }
}

function uploadImage(compressedImageBlob){

    const formData = new FormData();
    // formData.append('image', imgInp.files[0]);
	formData.append('compressedImage', compressedImageBlob, 'image.jpg');
	formData.append('po_penjualan_id', po_penjualan_id);
	formData.append('customer_id', customer_id);
	console.log(compressedImageBlob);
	const url = "transaction/po_penjualan_upload_image";

	fetch(baseurl+url,{
		method: 'POST',
		body: formData
	}).then(data => {
        // Handle the data from the server (e.g., display a success message)
		console.log(data.status);
		if (data.status != 200) {
			bootbox.alert(`Error ${data.status} <br/> image not saved !!`);
		}
    })
    .catch(error => {
        // Handle any errors that occurred during the fetch
        console.error('Error:', error);
    });

	// ajax_data_sync(url,formData).done(function(data_respond  /*,textStatus, jqXHR*/ ){
	// 	console.log('respond',data_respond);
	// });
}

function removePOImage(){
	bootbox.confirm("Yakin menghapus gambar PO ? ", function(respond){
		if (respond) {
			imagePlaceholder.style.backgroundImage = ``;
			const spanPO = imagePlaceholder.querySelector("span");
			spanPO.style.opacity = 100;
		}
	})
}

const disablePONumber = () =>{
	poInput.readOnly = true;
	poInput.value = "";
}

const enablePONumber = () =>{
	poInput.readOnly = false;
}

const disablePONumberEdit = () =>{
	console.log('1',poInputEdit.value);
	poInit = poInputEdit.value;
	poInputEdit.readOnly = true;
	poInputEdit.value = "";
}

const enablePONumberEdit = () =>{
	console.log('2',poInit);
	poInputEdit.readOnly = false;
	poInputEdit.value = poInit;
}


jQuery(document).ready(function() {
	$('[data-toggle="popover"]').popover();	  	
	

	$('#btnAddBarang').click(function(e){
		setTimeout(function(){
					$('#barang_id_select').select2("open");
				},700);
	});

	var map1 = {220: false};
	$(document).keydown(function(e) {
		if (e.keyCode in map1) {
			map1[e.keyCode] = true;
			if (map1[220]) {
				$('#portlet-config-detail').modal('toggle');
				setTimeout(function(){
					$('#barang_id_select').select2("open");
				},700);
			}
		}

		
		if(e.key=='Alt'){
			e.preventDefault();
			// alert(e.key)
			if (isInfoShown) {
				$(".invoice-info").show();
			}else{
				$(".invoice-info").hide();
			}
			isInfoShown = !isInfoShown;

		}
	}).keyup(function(e) {
		if (e.keyCode in map1) {
			map1[e.keyCode] = false;
		}
	});

	$('#warna_id_select, #barang_id_select, #barang_id_edit, #warna_id_edit, #customer_id_edit, #customer_id_select').select2({
		placeholder: "Pilih...",
		allowClear: true
	});

	$("#btnSubmitBarang").click(function(){
		const barang_id = $("#barang_id_select").val();
		const warna_id = $("#warna_id_select").val();
		const qty = $("#qty_add").val();
		const harga = $("#harga_add").val();
		
		
		if (barang_id != '' && warna_id != '' && qty != '' && parseInt(qty) > 0 && harga != '' && parseInt(harga) > 0) {
			const isListed = checkIsListed(barang_id, warna_id);

			if (isListed) {
				return;
			}else{
				formAddBarang.submit();
			}
		}else{
			bootbox.alert("Mohon isi semua data");
		}
	});

	$("#btnSubmitBarangEdit").click(function(){
		const barang_id = $("#barang_id_edit").val();
		const warna_id = $("#warna_id_edit").val();
		const qty = $("#qty_edit").val();
		const harga = $("#harga_edit").val();
		if (barang_id != '' && warna_id != '' && qty != '' && parseInt(qty) > 0 && harga != '' && parseInt(harga) > 0) {
			formEditBarang.submit();
		}else{
			bootbox.alert("Mohon isi semua data");
		}
	});

	$("#barang_id_select").change(function(){
		const barang_id = $("#barang_id_select").val();
		barangList.forEach(item => {
			if (item.id == barang_id) {
				$('#satuan_add').val(item.nama_satuan);
			}
		});
		$("#warna_id_select").select2("open");
	});

	$("#barang_id_select, #warna_id_select").change(function(){
		const barang_id = $("#barang_id_select").val();
		const warna_id = $("#warna_id_select").val();
		const isListed = checkIsListed(barang_id, warna_id);
		if (!isListed) {
			$("#qty_add").focus();
		}
	});

	$("#barang_id_edit").change(function(){
		const barang_id = $("#barang_id_edit").val();
		barangList.forEach(item => {
			if (item.id == barang_id) {
				$('#satuan_edit').val(item.nama_satuan);
			}
		});

	});
});

function checkIsListed(barang_id, warna_id){
	let isListed = false;
	items.forEach(item => {
		if (item.barang_id == barang_id && item.warna_id == warna_id) {
			isListed = true;
		}
	});

	if (isListed) {
		bootbox.alert("Barang Sudah Terdaftar");
	}
	return isListed;
}

function addRupiahFocusin(el){
	el.addEventListener("focusin", function(e){
		const v = e.target.value;
		if (v.length  > 0) {
			// console.log(v.length);
			// el.value = currency.removeRupiah(v);
		}
	})
}

function addRupiahFocusOut(el){
	el.addEventListener("focusin", function(e){
		const v = e.target.value;
		// el.value = currency.rupiah(v);
	})
}

//=============================================================================
// edit data barang
//=============================================================================

function editData(id){
	
	const getItem = items.filter(item=>{
		if (item.id === id) {
			return item;
		}
	});

	
	const selectedItem = getItem[0];
	
	$("#barang_id_edit").val(selectedItem.barang_id);
	$("#warna_id_edit").val(selectedItem.warna_id);

	$("#barang_id_edit, #warna_id_edit").change();

	$("#qty_edit").val(selectedItem.qty);
	$("#harga_edit").val(parseFloat(selectedItem.harga));
	const dpp = selectedItem.harga / (1+(ppnValue/100));
	$('#harga_dpp_edit').val(dpp.toFixed(2))
	$("#id_edit").val(selectedItem.id);
	$("#keterangan_barang_edit").val(selectedItem.keterangan);

	$('#portlet-config-detail-edit').modal("toggle");

}

function removeData(id){
	bootbox.confirm("Yakin hapus data ini ? ", function(respond){
		if (respond) {
			const dialog = bootbox.dialog({
				message: '<p class="text-center mb-0"><i class="fa fa-spin fa-cog"></i> Removing.....</p>',
				closeButton: false
			});
			fetch(baseurl+`transaction/po_penjualan_remove_item?id=${id}`)
			.then((response) => response.json())
			.then((data) => {
				if (data == "OK") {
					dialog.find('.bootbox-body').html('Remove sukses. refresh page....');
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


//=============================================================================
// ppn status
//=============================================================================

function changePPNStatus(){
	const dialog = bootbox.dialog({
		message: '<p class="text-center mb-0"><i class="fa fa-spin fa-cog"></i> Updating.....</p>',
		closeButton: false
	});

	const ppnCheck = document.querySelector("#ppnIncludeStatus");
	const status = (ppnCheck.checked ? 1 : 0 );
	
	fetch(baseurl+`transaction/po_penjualan_changeppnstatus?id=${po_penjualan_id}&status=${status}`)
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

//=============================================================================
// update keterangan footer diskon & biaya lain
//=============================================================================
function updateKetFooter (){
	let ket = keteranganFooter.value;
	const dialog = bootbox.dialog({
		message: '<p class="text-center mb-0"><i class="fa fa-spin fa-cog"></i> Updating Keterangan.....</p>',
		closeButton: false
	});
	fetch(baseurl+`transaction/po_penjualan_update_others`,{
		method: 'POST',
		headers: {
			'Content-Type': 'application/x-www-form-urlencoded',
		},
		body: `po_penjualan_id=${po_penjualan_id}&column=keterangan_footer&value=${ket}`
	}).then((response) => response.json())
	.then((data) => {
		if (data == "OK") {
			dialog.find('.bootbox-body').html('<p class="text-center mb-0"><i class="fa fa-check"></i> Keterangan updated</p>');
			setTimeout(() => {
				dialog.modal('hide');
			}, 500);	
		}else{
			dialog.modal('hide');
			bootbox.alert("Error");
		}
		
	});
}

function updateDiskon (){
	let diskon = parseFloat(currency.removeRupiah(inputDiskon.value));
	diskon = (diskon == '' ? 0 : diskon);
	const dialog = bootbox.dialog({
		message: '<p class="text-center mb-0"><i class="fa fa-spin fa-cog"></i> Updating Diskon.....</p>',
		closeButton: false
	});
	fetch(baseurl+`transaction/po_penjualan_update_others`,{
		method: 'POST',
		headers: {
			'Content-Type': 'application/x-www-form-urlencoded',
		},
		body: `po_penjualan_id=${po_penjualan_id}&column=diskon&value=${diskon}`
	}).then((response) => response.json())
	.then((data) => {
		if (data == "OK") {
			dialog.find('.bootbox-body').html('<p class="text-center mb-0"><i class="fa fa-check"></i> Diskon updated. Refresh... </p>');
			setTimeout(() => {
				window.location.reload();
			}, 500);	
		}else{
			dialog.modal('hide');
			bootbox.alert("Error");
		}
		
	});
}
function updateBiayaLain (){
	let biayaLain = parseFloat(currency.removeRupiah(inputBiayaLain.value));
	biayaLain = (biayaLain == '' ? 0 : biayaLain);
	
	const dialog = bootbox.dialog({
		message: '<p class="text-center mb-0"><i class="fa fa-spin fa-cog"></i> Updating Biaya Lain-lain.....</p>',
		closeButton: false
	});
	fetch(baseurl+`transaction/po_penjualan_update_others`,{
		method: 'POST',
		headers: {
			'Content-Type': 'application/x-www-form-urlencoded',
		},
		body: `po_penjualan_id=${po_penjualan_id}&column=biaya_lain&value=${biayaLain}`
	}).then((response) => response.json())
	.then((data) => {
		if (data == "OK") {
			dialog.find('.bootbox-body').html('<p class="text-center mb-0"><i class="fa fa-check"></i> Biaya Lain-lain updated. Refresh... </p>');
			setTimeout(() => {
				window.location.reload();
			}, 500);	
		}else{
			dialog.modal('hide');
			bootbox.alert("Error");
		}			
	});
}
function showInputDiskon(){
	const span = document.querySelector("#diskonContainer").hidden = false;
	inputDiskon.focus();
	document.querySelector("#textDiskon").hidden = true;
}

function hideInputDiskon(){
	const span = document.querySelector("#diskonContainer").hidden = true;
	document.querySelector("#textDiskon").hidden = false;
}

function showInputBiayaLain(){
	const span = document.querySelector("#biayaLainContainer").hidden = false;
	inputBiayaLain.focus();
	document.querySelector("#textBiayaLain").hidden = true;
}

function hideInputBiayaLain(){
	const span = document.querySelector("#biayaLainContainer").hidden = true;
	document.querySelector("#textBiayaLain").hidden = false;
}

//=============================================================================
// lock and unlock PO
//=============================================================================

function lockPO(){
	bootbox.confirm("Lock PO ini ?", function(respond){
		if (respond) {
			inputPOStatus.value = 0;
			formLock.submit();
		}
	});
}

inputPIN.addEventListener("keyup", function(){
	cek_pin();
})

btnOpenLockSubmit.addEventListener('click', function(){
	document.querySelector("#form-lock-open").submit();
})


function cek_pin(){
	
	const pinUser = inputPIN.value;
	if (pinUser.length == 6) {
		const dialog = bootbox.dialog({
			message: '<p class="text-center mb-0">CEK PIN ... <i class="fa fa-spin fa-cog"></i> </p>',
			closeButton: false
		});
		
		var data = {};
		data['pin'] = pinUser;
		var url = 'transaction/cek_pin';
		var result = ajax_data(url,data);
		if (result == 'OK') {
			btnOpenLockSubmit.disabled = false;
			setTimeout(() => {
				dialog.modal('hide');
			}, 500);
			return;
		}else{
			setTimeout(() => {
				dialog.modal('hide');
			}, 500);
		};
	}

	btnOpenLockSubmit.disabled = true;

}

function submitFormInvoice(){
	const tgl = $("#tanggal_invoice").val();
	if (tgl != '') {
		// bootbox.alert("OK");
		formInvoice.submit();
	}else{
		bootbox.alert("Mohon isi alamat");
	}
}

function finishingPO(){
	bootbox.confirm("Yakin mengubah status PO menjadi selesai ?<br/> PO tidak akan muncul di modul penjualan", function(respond){
		if (respond) {
			$("#overlay-div").show();
			
			var data_st = {};
			var url = "transaction/po_penjualan_finish";
			data_st['po_penjualan_id'] = po_penjualan_id;
			
			ajax_data_sync(url,data_st).done(function(data_respond  /*,textStatus, jqXHR*/ ){
				// console.log(data_respond);
				windows.location.reload();
			});
		}
	})
}


</script>


