<?=link_tag('assets/global/plugins/bootstrap-datepicker/css/datepicker3.css'); ?>
<link href="<?=base_url('assets/global/plugins/bootstrap-modal/css/bootstrap-modal.css');?>" rel="stylesheet" type="text/css"/>

<?=link_tag('assets/global/plugins/select2/select2.css'); ?>

<style type="text/css">
#general_table tr th{
	border: 1px solid #ddd;
	vertical-align: middle;
	/*text-align: center;*/
}

#general_table{
	width: 100%;
}

#general_table tr th,
#general_table tr td{
	padding: 5px 8px;
	color:#000;
	border: 1px solid #ddd;
}

.batal{
	background: #ccc;
}

.barang-list{
	list-style-type:none;
	padding:0px;
}

.barang-list div{
	display:inline-block;
}

.barang-list li:nth-child(2n){
	background-color:rgba(170,170,170,0.1);
}

.nama-barang{
	width:150px;
	margin-right:20px;
}

#tableFilterContainer{
	display: flex;
	flex-direction: row;
	justify-content: space-between;
}

.btn-filter-init span {
	display: none;
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
							<a href="#portlet-config" data-toggle='modal' class="btn btn-default btn-sm btn-form-add">
								<i class="fa fa-plus"></i> PO Baru </a>
							<!-- <select class='btn btn-sm btn-default' name='status_select' id='status_select'>
								<option value="1" selected>Aktif</option>
								<option value="2">Batal</option>

							</select> -->
						</div>
					</div>
					<div class="portlet-body">
						<!-- <h4>Filter : </h4> -->
						<form>
							<table width="400px" hidden>
								<!-- <tr>
									<td>Tanggal</td>
									<td class='padding-rl-5'> : </td>
									<td>
										<input type="text" class='form-control date-picker text-center' style="background:white; cursor:auto" readonly name="tanggal_start" value="<?=$tanggal_start?>">
									</td>
									<td>s/d</td>
									<td>
										<input type="text" class='form-control date-picker text-center' style="background:white;  cursor:auto" readonly name="tanggal_end"  value="<?=$tanggal_end?>">
									</td>
								</tr> -->
								<tr>
									<td>Customer</td>
									<td class='padding-rl-5'> : </td>
									<td colspan='3'>
										<select name='customer_id' id="customer_id_filter" class='form-control' style="width:350px">
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
										<select name="barang_id" class='form-control input1' id='barang_id_filter'>
											<option value=''>Pilih</option>
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
										<select name="warna_id" class='form-control' id='warna_id_filter'>
											<option value=''>Pilih</option>
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
										<button type="submit" class='btn default btn-block'>GO</button>
									</td>
								</tr>
							</table>
						</form>
						<hr hidden>
						<!-- table-striped table-bordered  -->
						
						<div>
							Filter :
							<button class="btn btn-sm default btn-filter-init" id="btnFilter3" onclick="filterInitTable(3)" >Semua <span><i class="fa fa-check"></i></span></button>
							<button class="btn btn-sm default btn-filter-init" id="btnFilter0" onclick="filterInitTable(0)" >On Going <span><i class="fa fa-check"></i></span></button>
							<button class="btn btn-sm default btn-filter-init" id="btnFilter2" onclick="filterInitTable(2)" >Finished <span><i class="fa fa-check"></i></span></button>
							<button class="btn btn-sm default btn-filter-init" id="btnFilter1" onclick="filterInitTable(1)" >Belum Di Lock <span><i class="fa fa-check"></i></span></button>
						</div>
						<div id="tableFilterContainer">
							<div style="position:relative; min-width:200px">
								<div style="position: absolute;bottom:10px;">
									Menampilkan <b style="font-size:14px;" id='countFilteredRow'>0</b> dari <b style="font-size:14px;" id='countAllRow'>0</b> baris
								</div>
							</div>
							<input id="searchFilter" 
							onkeyup="filterTable()"
							style="width:200px; border:1px solid #ddd; padding:5px 8px; margin-bottom:10px; border-radius:3px" 
							placeholder="search...">
						</div>
						<table class="" id="general_table">
							<thead>
								<tr>
								<th scope="col">
										Tanggal
									</th>
									<th scope="col" >
										PO/Request
									</th>
									<th scope="col">
										Barang
									</th>
									<th scope="col">
										Warna
									</th>
									<th scope="col" class="text-center">
										Harga
									</th>
									<th scope="col" class="text-center">
										QTY
									</th>
									<th scope="col">
										Sisa
									</th>
									<th scope="col">
										Customer
									</th>
									<th scope="col" class='text-center'>
										Keterangan
									</th>
									<!-- <th scope="col" >
										Status
									</th>
									<th scope="col" class='status_column'>
										Status Aktif
									</th>
									<th scope="col" >
										PIC
									</th> -->
									<th scope="col">
										Actions
									</th>
									<!-- <th scope="col" class='status_column'>
										Status 
									</th> -->
								</tr>
							</thead>
							<tbody>
								<?/* foreach ($po_penjualan_list as $row) {
									$nama_barang = explode('??', $row->nama_barang);
									$nama_warna = explode('??', $row->nama_warna);
									$qty = explode('??', $row->qty);
									$harga_po = explode('??', $row->harga_po);
									$closed_by = explode('??', $row->closed_by);
									$rspan = count($nama_barang);
									$rspan = ($rspan == 0 ? 1 : $rspan);

									$subqty = explode('??', $row->subqty);

									
									for ($i=0; $i < $rspan ; $i++) { ?>
										<tr style="<?=($row->status_po == 2 ? "background-color:rgba(255,128,128,0.2)" : "")?>">
											<?if($i == 0){?>
												<td rowspan="<?=$rspan;?>"><?=is_reverse_date($row->tanggal);?></td>
												<td rowspan="<?=$rspan;?>"><?=$row->po_number?></td>
											<?}?>
											<?if(count($nama_barang) > 0){
												$sisa = ($qty[$i]) - $subqty[$i];
												$sisa= ($sisa < 0 ? 0 : $sisa);
												?>
												<td><?=$nama_barang[$i];?></td>
												<td><?=$nama_warna[$i]?></td>
												<td class="text-right"><?=($harga_po[$i] != '' ? str_replace(",00","",number_format($harga_po[$i],'2',",",".")) : '')?></td>
												<td class="text-right"><?=$qty[$i]?></td>
												<td class="text-right" style="border-right:1px solid #ddd"> <?=$sisa;?> </td>
											<?}?>
											<?if($i == 0){?>
												<td rowspan="<?=$rspan;?>"><?=$row->nama_customer;?></td>
												<td rowspan="<?=$rspan;?>" style="vertical-align:top">
													<?=($row->status_po == 2 ? 
														"<span class='label label-sm label-success'>finished</span>" : 
														( $row->status_po == 0 ? 
															"<span class='label label-sm label-warning'>on going</span>" : 
															"<span class='label label-sm label-danger'>belum di lock</span>"
														)
													)?>
													<p><?=$row->keterangan_footer?></p>
												</td>
												<!-- <td><?=$row->username;?></td> -->
												<td rowspan="<?=$rspan;?>">
													<a href="<?=base_url().is_setting_link('transaction/po_penjualan_detail')?>?id=<?=$row->id?>" class='btn btn-xs yellow-gold'><i class="fa fa-search"></i></a>
												</td>
												<!-- <td class='status_column'><?=$row->status_aktif?></td> -->
											<?}?>
											
										</tr>
									<?}	
								} */?>
								<tr>
									<td class="text-center" style="padding: 20px;" colspan="10"><i>load data...</i></td>
								</tr>
							</tbody>
						</table>
						<!-- <button class='btn blue btn-test'>test</button> -->
					</div>
				</div>
			</div>
		</div>

		<div class="modal fade" style="position:relative" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body">
						<form action="<?=base_url('transaction/po_penjualan_insert')?>" class="form-horizontal" id="form_add_data" method="post">
							<h3 class='block'> PO Penjualan Baru</h3>

							<div class="form-group customer_section">
			                    <label class="control-label col-md-4">Customer<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-7">
									<select name="customer_id" class='form-control' id='customer_id_select' >
										<option value=''>Pilih</option>
										<?foreach ($this->customer_list_aktif as $row) {?>
												<option value="<?=$row->id?>"><?=$row->nama;?><?=($row->tipe_company != '' ? ", ".$row->tipe_company : "")?> (<?=$row->alamat.
													($row->blok !='' && $row->blok != '-' ? ' BLOK.'.$row->blok: '').
													($row->no !='' && $row->no != '-' ? ' NO.'.$row->no: '').
													" RT.".$row->rt.' RW.'.$row->rw.
													($row->kelurahan !='' && $row->kelurahan != '-' ? ' Kel.'.$row->kelurahan: '').
													($row->kecamatan !='' && $row->kecamatan != '-' ? ' Kec.'.$row->kecamatan: '').
													($row->kota !='' && $row->kota != '-' ? ' '.$row->kota: '').
													($row->provinsi !='' && $row->provinsi != '-' ? ' '.$row->provinsi: '')?>)</option>
										<?}?>
									</select>
			                    </div>
			                </div>

							<div class="form-group">
			                    <label class="control-label col-md-4">Tanggal PO/Request<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-7">
	                    			<input name='tanggal' class='form-control date-picker' id='tanggal_add' value="<?=date('d/m/Y')?>"  >
			                    </div>
			                </div>

							<div class="form-group">
			                	<!-- po_section -->
			                    <label class="control-label col-md-4">TIPE<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-7">
									<div>
										<label onclick="disablePONumber()">
											<input type="radio" name="tipe" value="2">Request</label>
									</div>
									<div>
										<label onclick="enablePONumber()">
											<input checked type="radio" name="tipe" value="1">PO</label>
										<input name='po_number' maxlength='100' placeholder="PO Number" id="po_number" class='form-control'>

									</div>
			                    </div>
			                </div> 

							
							<div class="form-group">
			                    <label class="control-label col-md-4">Attn.
			                    </label>
			                    <div class="col-md-7">
	                    			<input name='contact_person' class='form-control' id='contact_person_add' maxlength="50" >
			                    </div>
			                </div>

							<div class="form-group" hidden>
			                    <label class="control-label col-md-4">Harga include PPN<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-7">
									<div class="checkbox-list" style='margin-top:10px;'>
										<input checked type="checkbox" class='form-control' name="ppn_include_status" id="ppn_status_add" value='1'>
									</div>
			                    </div>
			                </div>

			                <div class="form-group" hidden >
			                    <label class="control-label col-md-4">Tanggal Kirim (optional)
			                    </label>
			                    <div class="col-md-7">
	                    			<input name='tanggal_kirim' class='form-control date-picker' value="" >
			                    </div>
			                </div> 

							<div class="form-group" hidden>
			                    <label class="control-label col-md-4">Alamat Kirim (optional)
			                    </label>
			                    <div class="col-md-7">
	                    			<textarea name='alamat_kirim' class='form-control' row='3'></textarea>
			                    </div>
			                </div> 

							<div class="form-group" hidden>
								<!-- po_section -->
								<label class="control-label col-md-4">Keterangan
								</label>
								<div class="col-md-7">
									<textarea name='keterangan' maxlength='250' class='form-control'></textarea>
								</div>
							</div> 
						</form>
					</div>

					<div class="modal-footer">
						<!-- <button type="button" class="btn blue btn-active btn-save-tab" title='Save & Buka di Tab Baru'>Save & New Tab</button> -->
						<button type="button" class="btn blue btn-active btn-trigger" id="btnAddPOPenjualan" >Save</button>
						<button type="button" class="btn btn-active default" data-dismiss="modal">Close</button>
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
<script src="<?php echo base_url('assets/global/plugins/jquery-validation/js/jquery.validate.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/bootbox/bootbox.min.js'); ?>" type="text/javascript"></script>

<script src="<?php echo base_url('assets_noondev/js/fnReload.js');?>"></script>


<script>

	// $('#general_table').DataTable({
    //     "paging": true,
    //     "pageLength": 100
    // });
	
	let tipePO=1;
	const btnSubmit = document.querySelector("#btnAddPOPenjualan");
	const formAdd = document.querySelector("#form_add_data");
	const poInput = document.querySelector("#po_number");
	const customerInput = $('#customer_id_filter');
	const barangInput = $('#barang_id_filter');
	const warnaInput = $('#warna_id_filter');
	const searchInput = document.querySelector("#searchFilter");
	const countFilteredRow = document.querySelector('#countFilteredRow');
	const countAllRow = document.querySelector('#countAllRow');

	const btnFilterCode = ['btn-warning', 'btn-danger', 'btn-success', 'btn-primary',];

	var indexFilter = 1;
	var poList = [];
	var filteredPOList = [];
	var filterInitPOList = [];

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

	const disablePONumber = () =>{
		poInput.readOnly = true;
		poInput.value = "";
		tipePO = 2;
	}

	const enablePONumber = () =>{
		poInput.readOnly = false;
		tipePO = 1;
	}

jQuery(document).ready(function() {

	$('#warna_id_select, #barang_id_select, #warna_id_filter, #barang_id_filter, #customer_id_filter, #customer_id_select').select2({
		placeholder: "Pilih...",
		allowClear: true
	});

	get_po_list();
});

function get_po_list(){
	// alert('test');
	const customerId = customerInput.val();
	const barangId = barangInput.val();
	const warnaId = warnaInput.val()

	var data = {};
	var url = `transaction/get_po_penjualan_list?customer_id=${customerId}&barang_id=${barangId}&warna_id=${warnaId}`;
	ajax_data_sync(url,data).done(function(data_respond  /*,textStatus, jqXHR*/ ){
		poList = JSON.parse(data_respond);
		countAllRow.innerHTML = poList.length;
		
		filterInitTable(0);
	});
	
}

function filterInitTable(indexBtn){
	indexFilter = indexBtn;
	const allBtn = $(`.btn-filter-init`);
	allBtn
		.removeClass('default '+btnFilterCode.join(' '))
		.addClass('default');

	allBtn
		.find('span').hide();

		
	$(`#btnFilter${indexBtn}`)
		.removeClass('default')
		.addClass(btnFilterCode[indexBtn]);
	$(`#btnFilter${indexBtn} span`).show();
	
	if (indexBtn != 3) {
		filterInitPOList = poList.filter((item, index)=>{
			return item.status_po == indexBtn;
		})
	}else{
		filterInitPOList = poList;
	}
	filterTable();
}

function filterTable(){
	const searchVal = searchInput.value.toLowerCase();
	if (searchVal.length > 0) {
		filteredPOList = filterInitPOList.filter((value, index)=>{
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
		filteredPOList = filterInitPOList;
	}

	countFilteredRow.innerHTML = filteredPOList.length;
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
		let nama_barang = [];
		let nama_warna = [];
		let qty = [];
		let harga_po = [];
		let subqty = [];
		let closed_by = value.closed_by;
		let status_po = value.status_po;
		let totalSubQty = 0;

		if (value.nama_barang != null ) {
				nama_barang = value.nama_barang.split('??');
				nama_warna = value.nama_warna.split('??');
				qty = value.qty.split('??');
				harga_po = value.harga_po.split('??');
				subqty = value.subqty.split('??');
				closed_by = value.closed_by;
				status_po = value.status_po;
		}

		let rspan = nama_barang.length;
		rspan = (rspan == 0 ? 1 : rspan);

		let bg = (status_po == 2 ? 'background-color:rgba(255,128,128,0.2)' : (index%2 == 1 ? 'background-color:#eee' : '' ) );
		for (let i = 0; i < rspan; i++) {
			tRow += `<tr style="${bg}">`;
			if (i == 0) {
				tRow += `
					<td rowspan="${rspan}">${value.tanggal.split('-').reverse().join('/')}</td>
					<td rowspan="${rspan}">${value.po_number}</td>
				`
			}

			if(nama_barang.length > 0){
				let sisa = (qty[i]) - subqty[i];
				totalSubQty += parseFloat(subqty[i]);
				let hj = ''
				sisa= (sisa < 0 ? 0 : sisa);
				if (harga_po[i] != '') {
					hj = (new Intl.NumberFormat(["ban", "id"]).format(harga_po[i]));
				}
				tRow += `
					<td>${nama_barang[i]}</td>
					<td>${nama_warna[i]}</td>
					<td class="text-right">${hj}</td>
					<td class="text-right">${new Intl.NumberFormat(["ban", "id"]).format(qty[i])}</td>
					<td class="text-right" style="border-right:1px solid #ddd"> ${new Intl.NumberFormat(["ban", "id"]).format(sisa)} </td>
				`
			}else{
				tRow += `<td colspan='5'>Belum ada Item</td>`;
			}

			if (i==0) {
				const delBtn = `<button 
					onClick="deletePO('${value.id}','${value.po_number}')" ${totalSubQty > 0 ? 'disabled' : ''} 
					class='btn btn-xs btn-danger'>
						<i class="fa fa-times"></i>
					</button>`;
				tRow += `
					<td rowspan="${rspan}">${value.nama_customer}</td>
					<td rowspan="${rspan}" style="vertical-align:top">
						${(value.status_po == 2 ? 
							"<span class='label label-sm label-success'>finished</span>" : 
							( value.status_po == 0 ? 
								"<span class='label label-sm label-warning'>on going</span>" : 
								"<span class='label label-sm label-danger'>belum di lock</span>"
							)
						)}
						<p>${value.keterangan_footer != null ? value.keterangan_footer : '-'}</p>
					</td>
					<td rowspan="${rspan}">
						<a href="${urlLink}?id=${value.id}" class='btn btn-xs yellow-gold'><i class="fa fa-search"></i></a>
						${delBtn}
					</td>
				`
			}
			tRow += `</tr>`;
		}
		
	});

	$(`#general_table tbody`).html(tRow);
}

function deletePO(id, number){
	bootbox.confirm(`Yakin membatalkan PO/Request <b>${number}</b> ? `, function(respond){
		if (respond) {
			const link = "<?=base_url()?>transaction/po_penjualan_batal";
			fetch(link+`?id=${id}`)
			.then((response) => {
				if (!response.ok) {
					throw new Error(`HTTP error! Status: ${response.status}`);
				}
				notific8("lime", `PO ${number} berhasil dibatalkan`);
				get_po_list();
				return;
			});
		}
	})
}

</script>
