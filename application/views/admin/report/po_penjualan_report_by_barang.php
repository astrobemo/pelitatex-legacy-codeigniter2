<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css'); ?>"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css'); ?>"/>
<?php echo link_tag('assets/global/plugins/bootstrap-datepicker/css/datepicker3.css'); ?>
<link href="<?=base_url('assets/global/plugins/bootstrap-modal/css/bootstrap-modal.css');?>" rel="stylesheet" type="text/css"/>

<style type="text/css">
.tgl-beli{
	border-radius: 20%;
	background: #cddefa;;
	padding: 2px 5px;
	font-size:0.9em;
}

.harga-beli{
	border-radius: 20%;
	background: #ffdee0;
	padding: 2px 5px;
	font-size: 0.8em;
}

.data-barang-datang{
	padding-right: 10px;
}

.datang-index{
	padding: 2px;
	/*border: 1px solid #ddd;*/
	border-radius: 30%;
	opacity: 0.5;
	/*background: #cddefa;*/
	color: #800;
	font-size:0.8em; 
	position:absolute;
	left:-2px; 
	top:0;
}

.span-table{
	display: inline-block;
}

.barang-span{width: 220px}
.harga-span{width: 55px; text-align: right;}
.order-span{width: 55px; text-align: right;}
.delivered-span{width: 70px;  padding-right:10px; text-align:right}
.outstanding-span{width: 60px; padding-right:10px; text-align:right}

.selected-to-print{
	/*background: #e0e4ff !important;*/
	background: #e0e4ff;
}

#general_table{
	width: 100%;
}

#general_table tr th,
#general_table tr td{
	padding: 5px;
	border: 1px solid #ddd;
	vertical-align: top;
}

.firstRow{
	border-top:2px solid #ccc !important;
}

#tableDetail tr th,
#tableDetail tr td{
	border: 1px solid #ddd;
	padding:5px 8px;
}

	@media print {

		* {
			-webkit-print-color-adjust: exact;
			print-color-adjust: exact;
		}

		/*th.span-table{font-size: }*/

		.span-table{
			display: table-cell;
			vertical-align: middle;
		}

		#general_table_wrapper{
			display: none !important;
		}

		a[href]:after {
		    content: none !important;
		}

		#print-table{
			display: block;
		}
	}


</style>

<div class="page-content">
	<div class='container'>

		<div class="row margin-top-10">
			<div class="col-md-12">
				<div class="portlet light">
					<div class="portlet-title hidden-print">
						<div class="caption caption-md">
							<i class="icon-bar-chart theme-font hide"></i>
							<span class="caption-subject theme-font bold uppercase"><?=$breadcrumb_small;?></span>
						</div>
						<div class="actions hidden-print">
							<!-- <a href="#portlet-config" data-toggle='modal' class="btn btn-default btn-sm">
							<i class="fa fa-plus"></i> Baru </a> -->
							
						</div>
					</div>
					<div class="portlet-body">
						<?$nama_barang_selected = ''; $nama_warna_selected = ''; $customer_selected?>
							<? /*<table width="500px">
								<tr>
									<td>Customer</td>
									<td class='padding-rl-5'> : </td>
									<td colspan='3' style="width:400px;">
										<select name='customer_id' id="customer_id_filter" class='form-control' style="min-width:200px; max-width:450px" onchange="showFilterBtn()">
											<option value=''>Semua</option>
											<?foreach ($this->customer_list_aktif as $row) {?>
												<option <?=($customer_id==$row->id ? 'selected' : '');?> value="<?=$row->id?>"><?=$row->nama;?><?=($row->tipe_company != '' ? ", ".$row->tipe_company : "")?> (<?=$row->alamat.
															($row->blok !='' && $row->blok != '-' ? ' BLOK.'.$row->blok: '').
															($row->no !='' && $row->no != '-' ? ' NO.'.$row->no: '').
															" RT.".$row->rt.' RW.'.$row->rw.
															($row->kelurahan !='' && $row->kelurahan != '-' ? ' Kel.'.$row->kelurahan: '').
															($row->kecamatan !='' && $row->kecamatan != '-' ? ' Kec.'.$row->kecamatan: '').
															($row->kota !='' && $row->kota != '-' ? ' '.$row->kota: '').
															($row->provinsi !='' && $row->provinsi != '-' ? ' '.$row->provinsi: '')?>)</option>
											<?}?>
										</select>
									</td>
								</tr>
								<tr>
									<td>Barang</td>
									<td class='padding-rl-5'> : </td>
									<td colspan='3'>
										<select name="barang_id" class='form-control input1' id='barang_id_filter' onchange="showFilterBtn()">
											<option value=''>Semua</option>
											<?foreach ($this->barang_list_aktif as $row) {?>
												<option value="<?=$row->id?>" <?=($barang_id == $row->id ? 'selected' : '')?> ><?=$row->nama_jual;?></option>
											<? } ?>
										</select>
									</td>
								</tr>
								<tr>
									<td>Warna</td>
									<td class='padding-rl-5'> : </td>
									<td colspan='3'>
										<select name="warna_id" class='form-control' id='warna_id_filter' onchange="showFilterBtn()">
											<option value=''>Semua</option>
											<?foreach ($this->warna_list_aktif as $row) { ?>
												<option value="<?=$row->id?>"  <?=($warna_id == $row->id ? 'selected' : '')?>><?=$row->warna_beli;?></option>
											<? } ?>
										</select>
									</td>
								</tr>
								<tr>
									<td></td>
									<td></td>
									<td colspan='3'>
										<button class='btn default btn-block' id="btnFilterData" onchange="applyFilterData()">GO</button>
									</td>
								</tr>
							</table>
							*/?>

							<?include_once "po_penjualan_report_header.php";?>

						<hr class='hidden-print'/>
						<div class="text-right">
							<input id="searchFilter" 
							onkeyup="filterTable()"
							style="width:200px; border:1px solid #ddd; padding:5px 8px; margin-bottom:10px; border-radius:3px" 
							placeholder="search...">
						</div>
						<table class="" id="general_table">
							<thead>
								<tr>
									<th scope="col" style='width:100px;' onclick="sortPOList()">
										Barang
										<span id="sortIcon"></span>
									</th>
									<th scope="col" >
										Warna
									</th>
									<th scope="col"  class="text-center">
										Jml PO
									</th>
									<th scope="col" class="text-center">
										Stok Barang
									</th>
									<th scope="col" class="text-center" >
										Sisa PO
									</th>	
									<th scope="col" class="text-center" >
										Stok + PO
									</th>
									<th scope="col">
										Actions
									</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td colspan='7' style="color:#ccc; text-align:center; padding:20px">...<i style="font-size:20px" class="fa fa-cog fa-spin"></i></td>
								</tr>
							</tbody>
							<tfoot>
								<tr>
									<th colspan="3" class="text-center">TOTAL</th>
									<th class="text-center"><span id="sumStok">0</span></th>
									<th class="text-center"><span id="sumSisaPO">0</span></th>
									<th class="text-center"><span id="sumTotal">0</span></th>
									<th></th>
								</tr>
							</tfoot>
						</table>

						<hr class='hidden-print'/>

						<hr class='hidden-print'/>
						<div class='text-right'>
							<button type='button' style='display:none' class='btn btn-lg blue btn-print hidden-print' onclick="window.print()"><i class='fa fa-print'></i> PRINT</button>
						</div>						
					</div>
				</div>
			</div>
		</div>

		<div class="modal fade" id="portlet-config-detail" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body">
						<h3 id="namaBarang"></h3>
						<table id="tableDetail">
							<thead>
								<!-- <tr>
									<th>No</th>
									<th>Tanggal</th>
									<th>No PO</th>
									<th>Customer</th>
									<th>PO</th>
									<th>Sisa</th>
									<th>Action</th>
								</tr> -->
							</thead>
							<tbody>

							</tbody>
							<tfoot></tfoot>
						</table>     
					</div>

					<div class="modal-footer">
						<button type="button" class="btn default" data-dismiss="modal">Close</button>
					</div>
				</div>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
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

const customerInput = $('#customer_id_filter');
const barangInput = $('#barang_id_filter');
const warnaInput = $('#warna_id_filter');
const searchInput = document.querySelector("#searchFilter");
const btnFilterData = $("#btnFilterData");
var poList = [];
var sortedPOList = [];
var filteredPOList = [];
var sortedAsc = false;
const barangIdList = [];
const warnaList = [];
const stokList = {};
let isStokLoading = true;
const tableDetail = document.querySelector("#tableDetail");
const detailList = {};

jQuery(document).ready(function() {

	// dataTableTrue();

	// oTable = $('#general_table').DataTable();
	// oTable.state.clear();
	// oTable.destroy();


	$("#barang_id_filter, #warna_id_filter, #customer_id_filter").select2();

	// $("#general_table").DataTable({
    //     "lengthMenu": [
    //         [10, 25, 50, 100, -1],
    //         [10, 25, 50, 100, "All"] // change per page values here
    //     ],
    //     "pageLength": 100, // set the initial value,

	// });
	getPOList();
});

function showFilterBtn(){
	btnFilterData
		.addClass("green")
		.removeClass("default");
}

function applyFilterData(){
	
}

function getPOList(){
	// const customerId = customerInput.val();
	// const barangId = barangInput.val();
	// const warnaId = warnaInput.val()

	const customerId = '';
	const barangId = '';
	const warnaId = '';

	var data = {};
	var url = `report/data_po_penjualan_by_barang?customer_id=${customerId}&barang_id=${barangId}&warna_id=${warnaId}`;
	ajax_data_sync(url,data).done(function(data_respond  /*,textStatus, jqXHR*/ ){
		JSON.parse(data_respond).forEach((list, index) => {
			barangIdList.push(list.barang_id);
			//initialisasi buat detail 
			detailList[`detail-${list.barang_id}`] = [];

		});
		poList = JSON.parse(data_respond);
		sortPOList();
		getStok();
		convertToDetail();
	});
	
}

function sortPOList(){
	sortedAsc = !sortedAsc;
	document.querySelector('#sortIcon').innerHTML = `<i class="fa fa-sort-down"></i>`;
	if (sortedAsc)
		document.querySelector('#sortIcon').innerHTML = `<i class="fa fa-sort-up"></i>`;

	sortedPOList = poList.sort((a, b) => {
		const nameA = a.nama_barang.toLowerCase();
		const nameB = b.nama_barang.toLowerCase();

		if (sortedAsc) {
			if (nameA > nameB) return 1;
			if (nameA < nameB) return -1;
		}else{
			if (nameA > nameB) return -1;
			if (nameA < nameB) return 1;
		}
		return 0;
	});

	filterTable();
}

function filterTable(){
	const searchVal = searchInput.value.toLowerCase();
	if (searchVal.length > 0) {
		filteredPOList = sortedPOList.filter((value, index)=>{
			let returnVal = false;
			for (const [k, v] of Object.entries(value)) {
				if (v != null) {
					if (v.toLowerCase().includes(searchVal)) {
						returnVal = true;
					}
				}
			}
			return returnVal;
		})
	}else{
		filteredPOList = sortedPOList;
	}

	drawTable();
	
}


function drawTable(){
	let tRow = '';
	const urlLink = "<?=base_url().is_setting_link('transaction/po_penjualan_detail')?>";
	const searchVal = searchInput.value;
	if (filteredPOList.length == 0) {
		tRow = `<tr>
					<td class="text-center" style="padding: 20px;" colspan="10"><i>no data</i></td>
				</tr>`;
	}
	let sumStok = 0;
	let sumSisaPO = 0;
	let sumTotal = 0;
	// if (searchVal.length > 0) {
	// 	filteredPOList = poList.filter((value, index)=>{
	// 		console.log(value);
	// 	})
	// }else{
	// 	filteredPOList = poList;
	// }
	
	filteredPOList.forEach((value, index) => {
		const nama_barang = value.nama_barang;
		const jmlWarna = value.jml_warna;
		let namaBarang = '';
		let namaWarna = [];
		let qty = [];
		let hargaPo = [];
		let sisaQty = [];
		let qtyPo = [];
		let jmlPo = [];
		let jmlCust = [];
		let jmlBaris = value.jml_baris;
		let warnaIdList = [];

		if (jmlWarna > 0 ) {
			warnaIdList = value.warna_id.split('||');
			namaWarna = value.nama_warna.split('||');
			qtyPoData = value.po_qty_data.split('||');
			qtyPo = value.po_qty.split('||');
			qtySisaData = value.po_qty_sisa_data.split('||');
			qtySisa = value.po_qty_sisa.split('||');

			jmlPo = value.jml_po.split('||');
			jmlCust = value.jml_customer.split('||');
			let subStok = 0;
			let subSisaPo = 0;
			let subStokPo = 0;

			let rspanTotal = parseInt(jmlWarna)+1;
	
			for (let i = 0; i < jmlWarna; i++) {
				let stokCurrent = 0;
				if(typeof stokList[`stb-${value.barang_id}-${warnaIdList[i]}`] !== 'undefined'){
					stokCurrent = stokList[`stb-${value.barang_id}-${warnaIdList[i]}`];
				}
				let stok_po = parseFloat(stokCurrent) + parseFloat(qtySisa[i]);
				sumStok += parseFloat(stokCurrent);
				sumSisaPO += parseFloat(qtySisa[i]);
				sumTotal += parseFloat(stok_po);

				subStok += parseFloat(stokCurrent);
				subSisaPo += parseFloat(qtySisa[i]);
				subStokPo += parseFloat(stok_po);


				const bg = (i%2 == 1 ? 'background:#eee' : '');

				tRow += `<tr>`;
				if(i==0)
					tRow += `<td class="firstRow" rowspan="${rspanTotal}">${nama_barang}</td>`;

				tRow += `<td style="${bg}" class="${(i==0 ? 'firstRow' : '')}" >
						${namaWarna[i]}
					</td>`;

				tRow += `<td class="${(i==0 ? 'firstRow' : '')} text-center" style="${bg}" >${jmlPo[i]}</td>`;
				if (isStokLoading) {
					tRow += `<td class="${(i==0 ? 'firstRow' : '')} text-right" style="${bg}" >
							<small style='color:#ccc'>...loading</small>
						</td>`;
				}else{
					tRow += `<td id="stokRow${value.barang_id}-${warnaIdList[i]}" class="${(i==0 ? 'firstRow' : '')} text-right" style="${bg}" >
							${new Intl.NumberFormat(["ban", "id"]).format(stokCurrent)}
						</td>`;
				}
				tRow += `<td class="${(i==0 ? 'firstRow' : '')} text-right" style="${bg}" >${new Intl.NumberFormat(["ban", "id"]).format(qtySisa[i])}</td>`;
				if (isStokLoading) {
					tRow += `<td class="${(i==0 ? 'firstRow' : '')} text-right"  style="${bg}" >...</td>`;
					
				}else{
					tRow += `<td class="${(i==0 ? 'firstRow' : '')} text-right" style="${bg}" >${new Intl.NumberFormat(["ban", "id"]).format(stok_po)}</td>`;
				}
				if (i==0) {
					tRow += `<td class="firstRow " rowspan="${rspanTotal}" style="text-align:center;vertical-align:middle;">
						<a href="#portlet-config-detail" onclick="drawTableDetail('${value.barang_id}')" data-toggle="modal" class='btn btn-xs yellow-gold'>
							show detail</a></td>`;
				}
				tRow += `</tr>`;
			}

			tRow += `<tr>
				<td colspan='2'>SubTotal</td>
				<td class="text-center">${new Intl.NumberFormat(["ban", "id"]).format(subStok)}</td>
				<td class="text-center">${new Intl.NumberFormat(["ban", "id"]).format(subSisaPo)}</td>
				<td class="text-center">${new Intl.NumberFormat(["ban", "id"]).format(subStokPo)}</td>
			</tr>`;
		}else{
			tRow += `<tr>`;
			tRow += `<td>${nama_barang}</td>`;
			tRow += `<td colspan='4'>no item</td>`;
			tRow += `<tr>`;
			
		}

		
	});

	$(`#general_table tbody`).html(tRow);
	$(`#sumStok`).html(new Intl.NumberFormat(["ban", "id"]).format(sumStok));
	$(`#sumSisaPO`).html(new Intl.NumberFormat(["ban", "id"]).format(sumSisaPO));
	$(`#sumTotal`).html(new Intl.NumberFormat(["ban", "id"]).format(sumTotal));
}

function getStok(){
	var data = {};
	var url = `report/get_stok_for_po_jual`;
	if (barangIdList.length > 0) {
		data['item_list'] = [...new Set(barangIdList)].join(',');
	}
	ajax_data_sync(url,data).done(function(data_respond  /*,textStatus, jqXHR*/ ){
		JSON.parse(data_respond).forEach((row, index) => {
			stokList[`stb-${row.barang_id+'-'+row.warna_id}`] = row.qty_stok; 
		});

		isStokLoading = false;
		drawTable();
	});
}

function convertToDetail(){

	// jangan lupa diinitialisasi di getPOList()
	poList.forEach((list,index)=>{
		const nama_barang = list.nama_barang;
		const jmlWarna = list.jml_warna;
		let namaBarang = '';
		let namaCustomer = [];
		let namaWarna = [];
		let tanggalPo = [];
		let nomorPo = [];
		let qty = [];
		let hargaPo = [];
		let sisaQty = [];
		let qtyPo = [];
		let jmlPo = [];
		let jmlCust = [];
		let jmlBaris = list.jml_baris;
		let warnaIdList = [];
		let newData = {};
		let po_jual_id = [];
		
		if (jmlWarna > 0 ) {
			namaCustomer = list.nama_customer.split('||');
			tanggalPo = list.po_tanggal.split('||');
			nomorPo = list.po_number.split('||');
			po_jual_id = list.po_penjualan_id.split('||');

			warnaIdList = list.warna_id.split('||');
			namaWarna = list.nama_warna.split('||');
			qtyPoData = list.po_qty_data.split('||');
			qtyPo = list.po_qty.split('||');
			qtySisaData = list.po_qty_sisa_data.split('||');
			qtySisa = list.po_qty_sisa.split('||');
	
			jmlPo = list.jml_po.split('||');
			jmlCust = list.jml_customer.split('||');
			namaWarna.forEach((value, idx) => {
				const newData = [];
				const customers = namaCustomer[idx].split('??');
				const poNum =  nomorPo[idx].split('??');
				const poTgl = tanggalPo[idx].split('??');
				const poQty = qtyPoData[idx].split('??');
				const poSisa = qtySisaData[idx].split('??');
				const poId = po_jual_id[idx].split('??');

				customers.forEach((customer,urutan) => {
					newData.push({
						po_penjualan_id: poId[urutan],
						nama_customer:customer,
						po_number: poNum[urutan],
						tanggal: poTgl[urutan],
						qty: poQty[urutan],
						sisa: poSisa[urutan]
					})
				});


				detailList[`detail-${list.barang_id}`].push({
					barang_id: list.barang_id,
					warna_id: warnaIdList[idx],
					nama_barang: nama_barang,
					nama_warna: value,
					data:newData
				});
			});

		}

	});
	console.table('test',detailList);
}

//=========================================================
function drawTableDetail(barangId){

	const base = "<?=base_url().is_setting_link('transaction/po_penjualan_detail')?>";
	let namaBarang = '';
	let tRow = '';
	if (detailList[`detail-${barangId}`].length > 0) {
		detailList[`detail-${barangId}`].forEach((list, indexList) => {
			if(typeof stokList[`stb-${barangId}-${list.warna_id}`] !== 'undefined'){
				stokCurrent = stokList[`stb-${barangId}-${list.warna_id}`];
			}
			namaBarang = list.nama_barang;
			tRow += `<tr>
				<td colspan='7' style='border:none'><b>${list.nama_warna}</b></td>
			</tr>
			<tr>
				<th>No</th>
				<th>Tanggal</th>
				<th>No PO</th>
				<th>Customer</th>
				<th>PO</th>
				<th>Sisa</th>
			</tr>
			`;

			let subPo = 0;
			let subSisa = 0;

			list.data.forEach((row, indexRow) => {
				subPo += parseFloat(row.qty);
				subSisa += parseFloat(row.sisa);
				tRow += '<tr>';
				tRow += `<td>${indexRow+1}</td>
						<td>${row.tanggal.split('-').reverse().join('/')}</td>
						<td>
							<a href="${base}?id=${row.po_penjualan_id}">${row.po_number}</a>
						</td>
						<td>${row.nama_customer}</td>
						<td class="text-right">${new Intl.NumberFormat(["ban", "id"]).format(row.qty)}</td>
						<td class="text-right">${new Intl.NumberFormat(["ban", "id"]).format(row.sisa)}</td>`;
				tRow += '</tr>';
			});

			tRow += `<tr>
				<th colspan='3' style="border:none"></th>
				<th class='text-right'> STOK : ${(isStokLoading ? '...' : new Intl.NumberFormat(["ban", "id"]).format(stokCurrent))} </th>
				<th class='text-center'>${new Intl.NumberFormat(["ban", "id"]).format(subPo)}</th>
				<th class='text-center'>${new Intl.NumberFormat(["ban", "id"]).format(subSisa)}</th>
			</tr>`;
		});
		
	}else{
		tRow += `<tr>
				<td colspan='7'>No</td>
			</tr>`;

	}
	
	document.querySelector("#namaBarang").innerHTML = namaBarang;
	tableDetail.querySelector("tbody").innerHTML = tRow;
}

</script>
