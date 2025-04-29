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
										Customer
										<span id="sortIcon"></span>
									</th>					
									<th scope="col" >
										Barang
									</th>
									<th scope="col" >
										Sisa PO
									</th>
									<th scope="col" >
										Stok PO
									</th>
									<th scope="col" >
										Jml PO
									</th>	
									<th scope="col">
										Actions
									</th>
								</tr>
							</thead>
							<tbody>
								
							</tbody>
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
						<h3 id="namaCustomer"></h3>
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
const barangIdList = [];
const stokList = {};
const detailList = {};

var poList = [];
var sortedPOList = [];
var filteredPOList = [];
var sortedAsc = false;

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
	var url = `report/data_po_penjualan_by_customer?customer_id=${customerId}&barang_id=${barangId}&warna_id=${warnaId}`;
	ajax_data_sync(url,data).done(function(data_respond  /*,textStatus, jqXHR*/ ){

		JSON.parse(data_respond).forEach((list, index) => {
			barangIdList.push(list.barang_id);
			detailList[`detail-${list.customer_id}`] = [];

		});
		poList = JSON.parse(data_respond);
		sortPOList();
		convertToDetail();
	});
	
}

function sortPOList(){
	sortedAsc = !sortedAsc;
	document.querySelector('#sortIcon').innerHTML = `<i class="fa fa-sort-down"></i>`;
	if (sortedAsc)
		document.querySelector('#sortIcon').innerHTML = `<i class="fa fa-sort-up"></i>`;

	sortedPOList = poList.sort((a, b) => {
		const nameA = a.nama_customer.toLowerCase();
		const nameB = b.nama_customer.toLowerCase();

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
	// if (searchVal.length > 0) {
	// 	filteredPOList = poList.filter((value, index)=>{
	// 		console.log(value);
	// 	})
	// }else{
	// 	filteredPOList = poList;
	// }
	
	filteredPOList.forEach((value, index) => {
		const nama_customer = value.nama_customer;
		const jmlBarang = value.jml_barang;
		let namaBarang = [];
		let namaWarna = [];
		let qty = [];
		let harga_po = [];
		let subqty = [];
		let closed_by = value.closed_by;
		let status_po = value.status_po;
		let jmlWarna = [];
		let jmlBaris = value.jml_baris;
		let jmlPo = [];
		let po_penjualan_id = [];
		let barangId= [];
		let warnaId= [];

		if (jmlBarang > 0 ) {

			barangId = value.barang_id.split('||');
			warnaId = value.warna_id.split('||');
			jmlWarna = value.jml_warna.split('||');
			namaBarang = value.nama_barang.split('||');
			namaWarna = value.nama_warna.split('||');
			namaSatuan = value.nama_satuan.split('||');
			hargaPo = value.harga_po.split('||');
			qtyPoData = value.qtyPoData.split('||');
			qtyPo = value.qtyPo.split('||');
			qtyJualData = value.qtyJualData.split('||');
			qtyJual = value.qtyJual.split('||');
			tglJual = value.qtyJual.split('||');
			jmlPo = value.po_penjualan_id.split('||');
			

			let rspanTotal = jmlBaris;
	
			for (let i = 0; i < jmlBarang; i++) {
				const warna = namaWarna[i].split('??');
				const qtyPoWarna = qtyPoData[i].split('??');
				const countPO = jmlPo[i].split('??');
				const poId = jmlPo[i].split('??');
				const uniqId = [...new Set(poId)];
				const warnas = warnaId[i].split('??');
				// let countPO = [...new Set(jmlPo[i].split('??'))];
				for (let j = -1; j < jmlWarna[i]; j++) {

					tRow += `<tr>`;
					if(i==0 && j==-1)
						tRow += `<td style="border-top:2px solid #ccc" rowspan="${rspanTotal}">${nama_customer}</td>`;

					if(j==-1){
						tRow += `<td style="${(i==0 ? 'border-top:2px solid #ccc' : '')}; background:rgba(125,125,125,0.1)" colspan='4'>
								<small>${namaBarang[i]}</small>
							</td>`;
						if (i==0) {
							tRow += `<td rowspan="${rspanTotal}" style="text-align:center;vertical-align:middle;${(i==0 ? 'border-top:2px solid #ccc' : '')}">
								<button class='btn btn-xs yellow-gold'>
									view detail</button></td>`;
						}
						tRow += `</tr>`;
					}else{

						stokCurrent = '...';
						if(typeof stokList[`stb-${barangId[i]}-${warnas[j]}`] !== 'undefined'){
							stokCurrent = stokList[`stb-${barangId[i]}-${warnas[j]}`];
						}


						tRow += `<td>${warna[j]}</td>`;
						tRow += `<td>${stokCurrent}</td>`;
						tRow += `<td>${qtyPoWarna[j]}</td>`;
						tRow += `<td>${uniqId.length}</td>`;
						tRow += `</tr>`;
					}
				}
			}
		}else{
			tRow += `<tr>`;
			tRow += `<td>${nama_customer}</td>`;
			tRow += `<td colspan='4'>no item</td>`;
			tRow += `<tr>`;
			
		}

		
	});

	$(`#general_table tbody`).html(tRow);
}

//-==================================================

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
		
		const nama_customer = list.nama_customer;
		const jmlBarang = list.jml_barang;
		let namaBarang = [];
		let namaWarna = [];
		let qty = [];
		let harga_po = [];
		let subqty = [];
		let closed_by = list.closed_by;
		let status_po = list.status_po;
		let jmlWarna = [];
		let jmlBaris = list.jml_baris;
		let jmlPo = [];
		let poNumber = [];
		let po_penjualan_id = [];
		let tanggalPo = [];
		let barangId= [];
		let warnaId= [];

		if (jmlBarang > 0 ) {

			barangId = list.barang_id.split('||');
			warnaId = list.warna_id.split('||');
			jmlWarna = list.jml_warna.split('||');
			namaBarang = list.nama_barang.split('||');
			namaWarna = list.nama_warna.split('||');
			namaSatuan = list.nama_satuan.split('||');
			hargaPo = list.harga_po.split('||');
			qtyPoData = list.qtyPoData.split('||');
			qtyPo = list.qtyPo.split('||');
			qtyJualData = list.qtyJualData.split('||');
			qtyJual = list.qtyJual.split('||');
			tglJual = list.qtyJual.split('||');
			poNumber = list.po_number.split('||');
			po_penjualan_id = list.po_penjualan_id.split('||');
			tanggalPo = list.po_tanggal.split('||');
				
			
			namaBarang.forEach((value, idx) => {
				const newData = [];
				warnas = namaWarna[idx].split('??');
				warnaIdList = warnaId[idx].split('??');
				poNum =  poNumber[idx].split('??');
				poTgl = tanggalPo[idx].split('??');
				poQty = qtyPoData[idx].split('??');
				poJual = qtyJualData[idx].split('??');
				poId = po_penjualan_id[idx].split('??');
				poNum = poNumber[idx].split('??');
				poTgl = tanggalPo[idx].split('??');

				warnas.forEach((warna,urutan) => {
					newData.push({
						po_penjualan_id: poId[urutan],
						nama_warna:warna,
						po_number: poNum[urutan],
						tanggal: poTgl[urutan],
						qty: poQty[urutan],
						sisa: poQty[urutan] - poJual[urutan]
					})
				});


				detailList[`detail-${list.customer_id}`].push({
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

</script>
