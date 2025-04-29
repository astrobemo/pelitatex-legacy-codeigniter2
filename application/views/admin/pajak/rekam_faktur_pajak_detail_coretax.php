<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css'); ?>"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css'); ?>"/>
<?php echo link_tag('assets/global/plugins/bootstrap-datepicker/css/datepicker3.css'); ?>

<style type="text/css">
ul.invoice-list{
    -moz-column-count: 4;
    -moz-column-gap: 20px;
    -webkit-column-count: 4;
    -webkit-column-gap: 20px;
    column-count: 4;
    column-gap: 20px;
}

.bg-warning{
	background: yellow;
}

#general_table tr th{
	font-size: 1.1em;
}
#general_table tr th, #general_table tr td{
	padding: 5px 5px;
	border: 1px solid #ddd;
}

.input-nik{
	display: none;
}

#statusData tr td{
	padding: 0px 10px;
	font-size: 1.2em;
}
</style>

<div class="page-content">
	<div class='container'>

		<?foreach ($data_rekam_faktur as $row) {
			$data_status = $row->status;
			# code...
		}?>

		<div class="modal fade" id="portlet-config-faktur" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body">
						<form action="<?=base_url('pajak/rekam_tambah_faktur');?>" class="form-horizontal" id="form_search_faktur" method="get">
							<h3 class='block'> Tambah Faktur</h3>
							
							<div class="form-group">
			                    <label class="control-label col-md-3">No Faktur<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
									<select nama='penjualan_id' class="form-control">
										<?foreach ($faktur_tambahan as $row) {?>
											<option value="<?=$row->id?>"><?=$row->no_faktur?> : <?=$row->nama_customer?></option>
										<?}?>
									</select>
			                    </div>
			                </div>	
		                </form>                
					</div>

					<div class="modal-footer">
						<button type="button" class="btn blue btn-add-faktur">Tambah</button>
						<button type="button" class="btn default" data-dismiss="modal">Close</button>
					</div>
				</div>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
		</div>

		<div class="modal fade" id="portlet-config-csv" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body">
						<form action="<?=base_url('pajak/rekam_tambah_faktur');?>" class="form-horizontal" id="form_upload_csv" method="get">
							<h3 class='block'> Upload CSV </h3> 
							
							<div class="form-group">
								<label class="control-label col-md-3">Upload CSV<span class="required"> * </span></label>
								<div class="col-md-6">
									<input type="file" id="inputCSVFaktur" name="csv_file" class="form-control" accept=".csv" required>
								</div>
							</div>

							<div class="form-group">
								<label class="control-label col-md-3">Delimiter<span class="required"> * </span></label>
								<div class="col-md-6">
									<select name="" id="delimiterCSVFaktur"  class="form-control">
										<option selected value=";">Titik Koma ( ; )</option>
										<option value=",">Koma ( , )</option>
									</select>
								</div>
							</div>
		                </form>                
					</div>

					<div class="modal-footer">
						<button type="button" class="btn blue " id="uploadCSV" onclick="csvToJson()" data-dismiss="modal">Upload</button>
						<button type="button" class="btn default" data-dismiss="modal">Batal</button>
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
							<span class="caption-subject theme-font bold uppercase"><?=$breadcrumb_small;?></span>
						</div>
						<div class="actions">
							<?if ($data_status == 1) {?>
								<a href="#portlet-config" data-toggle='modal' class="btn btn-default btn-sm btn-form-add">
								<i class="fa fa-plus"></i> + Faktur </a>
							<?}?>
						</div>
					</div>
					<div class="portlet-body">
						<div style='float:right'>
							<?/* if (is_posisi_id() == 1) {?>
								var_dump($data_status);
								<a href="<?=base_url()?>pajak/rekam_faktur_list_export_excel?id=<?=$rekam_faktur_pajak_id;?>&is_faktur_pengganti=1" class='btn btn-lg red'><i class='fa fa-download'></i> Download Faktur Pengganti</a>	
							<?} */?>
							<?if ($data_status == 0) {?>
								<button class='btn btn-lg yellow-gold btn-unlock'><i class='fa fa-unlock'></i> UNLOCK</button>
								<button onclick="generateXML('<?=$rekam_faktur_pajak_id;?>')" class='btn btn-lg btn-primary' id="buttonDownloadXML"><i class='fa fa-download'></i>Coretax <span id="fakturTerpilih">All</span> </button>
								<button class='btn btn-lg btn-success' id="buttonUploadNoFaktur" href="#portlet-config-csv" data-toggle="modal"><i class='fa fa-upload'></i> NO FAKTUR</button>
							<?}else{?>
								<!-- <p>File dapat di Download setelah Anda Lock</p>  -->
								<button class='btn btn-lg red btn-lock'><i class='fa fa-lock'></i> LOCK</button>
							<?}
							if(is_posisi_id() == 1){?>
								<!-- <a href="<?=base_url()?>pajak/rekam_faktur_list_export_excel?id=<?=$rekam_faktur_pajak_id;?>" class='btn btn-lg yellow-gold'><i class='fa fa-download'></i> Download</a>-->
								<!-- <a href="<?=base_url()?>pajak/rekam_faktur_pajak_export_xml_pretty?id=<?=$rekam_faktur_pajak_id;?>" class='btn btn-lg btn-primary'><i class='fa fa-download'></i>Coretax</a> -->								
							<?}?>

							<?if (is_posisi_id() == 1 ||  is_posisi_id() == 6) {
								// if ($data_status == 1) {?>
									<!-- <a href="#portlet-config" data-toggle="modal" class='btn btn-lg blue ' >Rekalkulasi</a> -->
								<?//}?>
							<?}?>
						</div>

						<table id="statusData">
							<tr>
								<td>TOTAL</td>
								<td> : </td>
								<td><?=count($fp_list_detail);?> Faktur</td>
							</tr>
							<tr>
								<td>
									Aktif
									<!-- <p style='font-size:1.2em'>Warning : <b class='total-warning'  style='background:yellow; padding:2px 0px;'>load...</b>
										<label><input type='checkbox' <?=($filter==1 ? 'checked': '');?> class='bg-warning-filter'>Lihat Warning Saja</label>
									</p> -->
								</td>
								<td> : </td>
								<td><span class='total-aktif'>...<i class="fa fa-spin fa-cog"></i></span></td>
							</tr>
							<tr>
								<td>Batal</td>
								<td> : </td>
								<td><span class='total-batal'>...<i class="fa fa-spin fa-cog"></i></span></td>
							</tr>
							<tr>
								<td>Ada NO Faktur</td>
								<td> : </td>
								<td id="hasNoFaktur">...<i class="fa fa-spin fa-cog"></i></td>
							</tr>
							<tr>
								<td>Belum ada No Faktur</td>
								<td> : </td>
								<td id="noneNoFaktur">...<i class="fa fa-spin fa-cog"></i></td>
							</tr>
						</table>
						<hr/>

						<?if($data_status == 0){?>
							<div class="note note-info">
								<h3>Cara download e-faktur massal di Coretax</h3>
								<p>
									ikuti petunjuk di link ini
									<a href="https://github.com/astrobemo/coretax-downloader">Coretax Downloader</a>

								</p>
	
							</div>

						<?}?>


						<hr/>
						<!-- <table class="table table-hover table-striped table-bordered" id="general_table"> -->

						<p>
							FILTER : 
							<button class='btn btn-md default' id="btnFilterAll" onclick="filterRegistered('0')">All</button>
							<button class='btn btn-md green' id="btnFilterRegistered" onclick="filterRegistered('1')">Sudah Punya NO FP(Registerd)</button>
							<button class='btn btn-md blue'  id="btnFilterUnregistered" onclick="filterRegistered('2')">Belum Punya NO FP(Unregistered)</button>
							<!-- <button class='btn btn-xs btn-primary' onclick="filterTable('')">NPWP</button>
							<button class='btn btn-xs btn-primary' onclick="filterTable('')">NIK</button>
							<button class='btn btn-xs btn-primary' onclick="filterTable('')">All</button> -->
						</p>
						<table width='100%' id="general_table">
							<thead>
								<tr>
									<th scope="col">No</th>
									<th scope="col">
										Tanggal/
										No Invoice
									</th>
									<th scope="col">
										Customer/Alamat
									</th>
									<th scope="col">
										NPWP/NIK
									</th>
									<th scope="col">
										No Faktur
									</th>
									<th scope="col">
										DPP
									</th>
									<th scope="col">
										PPn
									</th>
									<th scope="col">
										Total
									</th>

									<?/*
									<th scope="col">
										Raw
									</th>
									<th scope="col">
										PPn
									</th>
									<th scope="col">
										Total
									</th>
									*/?>
									<th scope="col" hidden>
										Updated
									</th>
									<th scope="col">
										Actions
									</th>
								</tr>
							</thead>
							<tbody>
								<?$total_nonppn = 0; $total_ppn = 0; $total = 0; $idx = 0;
								$total_aktif = 0; $total_batal = 0; $warning = 0;
								$total_asli = 0; $total_dpp = 0;
								$no_fp_awal = ""; $no_fp_akhir = "";
								$tanggal_awal = "";
								$tanggal_akhir = "";
								$hasNoFaktur = 0;
								$data_list = array();
								$xmlTerpilih = array();
								$total_registered = 0;
								$total_unregistered = 0;
								// $ppn_pengali = 0.11;
								foreach ($fp_list_detail as $row) { 
									

									$ppn_fix = $row->ppn_berlaku;
									$ppn_pengali = $ppn_fix /100;
									$ppn_pembagi = $ppn_pengali + 1;
									$no_fp_awal = ($no_fp_awal == "" ? $row->no_faktur_pajak : $no_fp_awal);
									$no_fp_akhir = $row->no_faktur_pajak;

									$rpl = [".","-"," "];
									$npwp = str_replace($rpl, "", $row->no_npwp);
									$npwp = (strlen($npwp) == 15 ? "0".$npwp : $npwp);

									
									$tanggal_awal = ($tanggal_awal == "" ? $row->tanggal : $tanggal_awal);
									$tanggal_akhir = $row->tanggal;
									
									$bg_yellow = ($row->no_npwp == '' && $row->no_nik == '' ? "bg-warning" : 'bg-none');
									$warning += ($row->no_npwp == '' && $row->no_nik == '' ? 1 : 0);
									$bg_color = ($row->status == 0 ? "background:#ffaab7" : '');
									($row->status == 1 ? $total_aktif++ : ''); 
									($row->status == 0 ? $total_batal++ : '');

									$hasNoFaktur = ($row->no_faktur_pajak != '' ? $hasNoFaktur+1 : $hasNoFaktur);

									$data_list[$row->no_invoice] = array(
										'rekam_faktur_pajak_id' => $rekam_faktur_pajak_id,
										'rekam_faktur_pajak_detail_id' => $row->id,
										'penjualan_id' => $row->penjualan_id,
										'no_invoice' => $row->no_invoice,
										'no_faktur_pajak' => $row->no_faktur_pajak,
										'npwp' => $row->no_npwp,
										'nik' => $row->no_nik,
										'dpp' => 0,
										'ppn' => 0,
									);

									$bg_npwp = ($row->no_npwp != '' && $row->no_npwp != 0 ? 'background:#c4d8ff' : '');
									$isRegistered = ($row->no_faktur_pajak != '' ? 'registered' : 'unregistered');
									($isRegistered == 'registered' ? $total_registered++ : $total_unregistered++);
									?>
									<tr style="<?=$bg_npwp;?>; <?=$bg_color;?>" class="<?=$bg_yellow;?> <?=$isRegistered?> " >
										<td><?=$idx+1;?></td>
										<td>
											<?=is_reverse_date($row->tanggal)?>
											<a target='_blank' style='font-size:1.1em; font-weight:bold' href="<?=base_url().is_setting_link('transaction/penjualan_list_detail')?>?id=<?=$row->penjualan_id;?>"><?=$row->no_invoice?></a> <?=(is_posisi_id()==1? $row->penjualan_id : '');?>
											<?$filter_nama = strtoupper(trim($row->nama_customer));?>
										</td>
										<td>
											<?=$filter_nama//$row->nama_customer;?> 
											<?//=($row->locked_status_cust==0 ? "<i style='color:blue' class='fa fa-check-circle'></i>" : '')?> 
											<?=(is_posisi_id()==1 ? '('.$row->customer_id.')' : '');?> 
											<hr style='margin:5px;' />
											<?=$row->alamat_cust_fp;?>
										</td>
										<td>
											<?=$npwp;?>
											<?if($data_status != 0){?>
												<?if(trim($row->tipe_company) == ''){?>
													<button class='btn btn-default btn-xs' onclick="toggleNikInput('<?=$row->id?>')"> <span>edit</span> </button>
													<p class='input-nik' id="sectionNIK<?=$row->id?>">
														<input type="text" id="inputNIK<?=$row->id?>" onchange="updateNIKFaktur('<?=$row->id?>')" class="" value="<?=$row->no_nik?>" style="width:150px;">
														<small><mute>enter untuk save</mute></small>
													</p>
												<?}?>
											<?}?>
											<span id="textNIK<?=$row->id?>"><?=$row->no_nik?></span>
										</td>
										<td style='font-size:1.1em; font-weight:bold'>
											<?if ($idx == 0) {
												$no_data = explode('.', $row->no_faktur_pajak);
												$no_faktur_start = end($no_data) ;
											}?>
											<?=$row->no_faktur_pajak;?>
										</td>
										<?
										$nilai_dpp = $row->total_dpp;
										$ppn = $row->total_ppn;
										$total = $nilai_dpp + $ppn;

										$total_dpp += $nilai_dpp;
										$total_ppn += $ppn;


										?>
										<td style="font-size:1.2em;"><?=number_format($row->total_dpp,'0',',','.')?></td>
										<td style="font-size:1.2em;"><?=number_format($ppn,'0',',','.' );?></td>
										<td><?=number_format($total,'0',',','.');?></td>
										<td><?=number_format($row->g_total - $total,'0',',','.');?></td>

										<?/*
										<td style="font-size:1.2em;"><?=number_format($row->g_total_raw,'2',',','.');?></td>
										<td style="font-size:1.2em;"><?=number_format($row->g_total_raw * 0.1,'2',',','.' );?></td>
										<td><?=number_format($row->g_total_raw * 1.1,'2',',','.');?></td>
										*/?>


										<td hidden><?=$row->updated?></td>
										<td>
											<span class='status' hidden><?=$row->status;?></span>
											<span class='id' hidden ><?=$row->id;?></span>
											<?if($row->no_faktur_pajak === ""){
												$xmlTerpilih[$row->id] = true;
												?>
											<?}?>
											<?if($data_status == 1){?>
												<button class="btn btn-xs red btn-remove"><i class='fa fa-times red'></i>remove</button>
											<?}else if(is_posisi_id()==1){?>
												<button class="btn btn-xs red btn-remove"><i class='fa fa-times red'></i>remove</button>
											<?}?>
											<?/* if ($row->status == 1) {?>
												<button class="btn btn-xs yellow-gold btn-batal"><i class='fa fa-warning'></i>batal</button>
											<?}else{?>
												<button class="btn btn-xs blue btn-aktif"><i class='fa fa-check'></i>Aktifkan</button>
											<?} */?>
										</td>
									</tr>
								<?$idx++;
							}?>
							</tbody>
						</table>

						<table class='table' style="font-size:2em">
							<tr>
								<th>SubTotal</th>
								<th>PPn</th>
								<th>TOTAL</th>
							</tr>
							<tr>
								<td><b><?//=number_format($total_nonppn,'2',',','.');?><?=number_format($total_dpp,'2',',','.')?></b></td>
								<td><b><?=number_format($total_ppn,'2',',','.');?></b></td>
								<td><b><?=number_format($total_dpp + $total_ppn,'2',',','.');?></b></td>
							</tr>
						</table>

							<form action="<?=base_url().'pajak/faktur_rekam_pajak_lock'?>" method="POST" id="form-lock" <?=(is_posisi_id() != 1 ? 'hidden' : '');?> >
								<input type="text"  name='rekam_faktur_pajak_id' value="<?=$rekam_faktur_pajak_id?>">
								<input type="text"  name='tanggal_awal' value="<?=$tanggal_awal?>">
								<input type="text"  name='tanggal_akhir' value="<?=$tanggal_akhir?>">
								<input type="text"  name='no_fp_awal' value="<?=$no_fp_awal?>">
								<input type="text"  name='no_fp_akhir' value="<?=$no_fp_akhir?>">
								<input type="text"  name='jumlah_trx' value="<?=$idx?>">
								<input type="text"  name='nilai' value="<?=$total_dpp?>">
								<input type="text"  name='nilai_ppn' value="<?=$total_ppn?>">
							</form>
							<?if (is_posisi_id()==1) {?>
								<button class='btn btn-xs yellow-gold btn-lock'><i class='fa fa-lock'></i> LOCK</button>
							<?}?>
					</div>
				</div>
			</div>
		</div>
	</div>			
</div>

<script src="<?php echo base_url('assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/datatables/media/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/global/plugins/bootbox/bootbox.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets_noondev/js/table-advanced.js'); ?>"></script>

<script>

	const tanggal_awal = "<?=$tanggal_awal?>";
	const tanggal_akhir = "<?=$tanggal_akhir?>";

	const data_list = <?=json_encode($data_list)?>;
	const totalFaktur = <?=count($fp_list_detail)?>;
	const hasNoFaktur = <?=$hasNoFaktur?>;
	const noneNoFaktur = <?=count($fp_list_detail) - $hasNoFaktur?>;
	const xmlTerpilih = <?=json_encode($xmlTerpilih)?>;

	const totalRegistered = <?=$total_registered?>;
	const totalUnregistered = <?=$total_unregistered?>;

	let xmlTerpilihCount = Object.keys(xmlTerpilih).length;

jQuery(document).ready(function() {

	$(`#hasNoFaktur`).text(hasNoFaktur);
	$(`#noneNoFaktur`).text(noneNoFaktur);

	$(`#btnFilterRegistered`).text(`Sudah Punya NO FP (Registered):${totalRegistered}`);
	$(`#btnFilterUnregistered`).text(`Belum Punya NO FP (Unregistered):${totalUnregistered}`);

	// dataTableTrue();

	// TableAdvanced.init();

	// oTable = $('#general_table').DataTable();
	// oTable.state.clear();
	// oTable.destroy();

	$(".total-aktif").html("<?=$total_aktif;?>");
	$(".total-batal").html("<?=$total_batal;?>");
	$(".total-warning").html("<?=$warning;?>");
	var warning = "<?=$warning;?>";

	// $("#general_table").DataTable({
	// 	"ordering":false,
	// 	// "bFilter":false
	// });

	$("#general_table").on("click", '.btn-batal', function(){
		var ini = $(this).closest("tr");
		var id = ini.find('.id').html();
		bootbox.confirm("<span style='color:red'>Batalkan</span> Faktur Pajak ini, ? <br/> Pastikan e-faktur nya telah <b>di batalkan terlebih dahulu</b> ", function(respond){
			if (respond) {
				window.location.replace(baseurl+"pajak/faktur_rekam_pajak_status_change?id="+id+"&status=0&rekam_faktur_pajak_id="+"<?=$rekam_faktur_pajak_id?>");
			};
		});
	});

	$("#no-start").val("<?=$no_faktur_start;?>");

	$("#general_table").on("click", '.btn-aktif', function(){
		var ini = $(this).closest("tr");
		var id = ini.find('.id').html();
		bootbox.confirm("<span style='color:blue'>Aktifkan</span> Faktur Pajak ini ? ", function(respond){
			if (respond) {
				window.location.replace(baseurl+"pajak/faktur_rekam_pajak_status_change?id="+id+"&status=1&rekam_faktur_pajak_id="+"<?=$rekam_faktur_pajak_id?>");
			};
		});
	});

	$("#general_table").on("click", '.btn-remove', function(){
		var ini = $(this).closest("tr");
		var id = ini.find('.id').html();
		bootbox.confirm("<b style='color:red'>Hapus</b> Faktur Pajak ini, <br/> setelah dihapus no faktur pajak akan <b style='color:red'>otomatis diurutkan ulang</b> ? ", function(respond){
			if (respond) {
				window.location.replace(baseurl+"pajak/faktur_rekam_pajak_remove_coretax?id="+id+"&rekam_faktur_pajak_id="+"<?=$rekam_faktur_pajak_id?>");
			};
		});
	});

	$(".btn-lock").click(function(){
		if (warning == 0) {
			let warn = "<br/> setelah dilock no faktur pajak <b>tidak dapat diubah</b> "
			bootbox.confirm("<b style='color:red'>LOCK</b> Faktur Pajak ini ? ", function(respond){
				if (respond) {
					// window.location.replace(baseurl+"pajak/faktur_rekam_pajak_lock?rekam_faktur_pajak_id="+"<?=$rekam_faktur_pajak_id?>");
					$('#form-lock').submit();
				};
			});
		}else{
			alert("Mohon lengkapi data terlebih dahulu, hingga warning 0");
		};
	});	

	$(".btn-unlock").click(function(){
		window.location.replace(baseurl+"pajak/faktur_rekam_pajak_unlock?rekam_faktur_pajak_id="+"<?=$rekam_faktur_pajak_id?>");
	});	

	$(".bg-warning-filter").change(function(){
		if($('.bg-warning-filter').is(':checked')){
			// alert('checked');
			// window.location.replace(baseurl+"<?=is_setting_link('pajak/rekam_faktur_pajak_detail')?>?id=<?=$rekam_faktur_pajak_id?>&filter=1");
			$("#general_table .bg-none").hide();
		}else{
			// window.location.replace(baseurl+"<?=is_setting_link('pajak/rekam_faktur_pajak_detail')?>?id=<?=$rekam_faktur_pajak_id?>");
			$("#general_table .bg-none").show();
		}
	});

	$(".btn-save").click(function(){
		$("#form_add_data").submit();
	});

	const xmlList = document.querySelectorAll('.xml-list-checkbox');


	<?if ($data_status == 0) {?>
		if(!xmlTerpilihCount){
			document.getElementById('buttonDownloadXML').disabled = true;
			document.getElementById('buttonUploadNoFaktur').disabled = true;
			
		}else if(xmlTerpilihCount == totalFaktur){
			document.getElementById('fakturTerpilih').innerText = 'All';
		}else{
			document.getElementById('fakturTerpilih').innerText = `${xmlTerpilihCount}`;
		}
	<?}?>

	/* xmlList.forEach(function(item){
		item.addEventListener('change', function(){
			const checked = item.checked;
			const dataId = item.getAttribute('data-id');
			if(checked){
				xmlTerpilihCount++;
				xmlTerpilih[dataId] = true;
			}else{
				xmlTerpilihCount--;
				xmlTerpilih[dataId] = false;
			}

			if(xmlTerpilihCount == totalFaktur){
				document.getElementById('buttonDownloadXML').disabled = false;
				document.getElementById('fakturTerpilih').innerText = 'All';
			}else if(xmlTerpilihCount == 0){
				document.getElementById('buttonDownloadXML').disabled = true;
				document.getElementById('fakturTerpilih').innerText = `None`;
			}else{
				document.getElementById('buttonDownloadXML').disabled = false;
				document.getElementById('fakturTerpilih').innerText = `${xmlTerpilihCount}`;
			}
		});
	}); */
});

function generateXML(rekam_faktur_pajak_id){

	/* let dialog = bootbox.dialog({
		message: '<p class="text-center mb-0"><i class="fa fa-spin fa-cog"></i> Please wait while generating xml...</p>',
		closeButton: false
	}); */

	// do something in the background

	const getToko = <?=json_encode($this->toko_list_aktif)?>[0];
	console.log(getToko);

	let devUrl = 'http://localhost:3000/pajak/generate_faktur_pajak_coretax';
	let prodUrl = 'https://api.favourtdj.com/pajak/generate_faktur_pajak_coretax';

	const currentDateTime = new Date().toISOString().replace(/T/, '_').replace(/\..+/, '');

	$.ajax({
		url: prodUrl,
		type: 'GET',
		data: {
			rekam_faktur_pajak_id: rekam_faktur_pajak_id,
			company_name: getToko.alias
		},
		success: function(response) {
			console.log('XML file fetched successfully:', response);
			const serializer = new XMLSerializer();
            const xmlString = serializer.serializeToString(response);
            const blob = new Blob([xmlString], { type: 'text/xml' });
            const link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = `fp_coretax-${getToko.nama}_${tanggal_awal}_sd_${tanggal_akhir}_${currentDateTime}.xml`;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
			// dialog.modal('hide');
            // Handle the response here
		},
		error: function(error) {
			console.error('Error fetching XML file:', error);
		}
	});
}

function toggleNikInput(id){
	$("#textNIK"+id).toggle();
	$("#sectionNIK"+id).toggle();
}

function updateNIKFaktur(id){
	let nik = $("#inputNIK"+id).val();

	let dialog = bootbox.dialog({
		message: '<p class="text-center mb-0"><i class="fas fa-spin fa-cog"></i> Please wait while updating nik...</p>',
		closeButton: false
	});

	$.ajax({
		url: '<?=base_url()?>pajak/faktur_rekam_pajak_update_nik',	
		type: 'POST',
		data: {
			id: id,
			nik: nik
		},
		success: function(response){
			$("#textNIK"+id).html(nik);
			$("#textNIK"+id).toggle();
			$("#sectionNIK"+id).toggle();
			dialog.modal('hide');

		}
	});
}

async function csvToJson() {
	const fileInput = document.getElementById('inputCSVFaktur');
	const delimiter = document.getElementById('delimiterCSVFaktur').value;
	const file = fileInput.files[0];
	const reader = new FileReader();

	const failedList = [];
	const successList = [];

	if (!file) {
		alert('File belum dipilih');
		return;
	}

	const newList = [];

	let dialog = bootbox.dialog({
		message: '<p class="text-center mb-0"><i class="fas fa-spin fa-cog"></i> Checking Faktur...</p>',
		closeButton: false,
		buttons: {
        ok: {
          className: 'btn-primary disabled'
        }
      }
	});

	try {
		await new Promise((resolve, reject) => {
			reader.onload = function(event) {

				try {
					const csv = event.target.result;
					const lines = csv.split("\n");
					const result = [];
					const headers = lines[0].split(delimiter);
	
					for (let i = 1; i < lines.length; i++) {
						const obj = {};
						const currentline = lines[i].split(delimiter);
						let npwp = '';
						let no_invoice='';
						let no_fp = '';
						let harga = 0;
						let ppn = 0;
						let status = "";
						let namaCustomer = '';
						
		
						for (let j = 0; j < headers.length; j++) {
							obj[headers[j]] = currentline[j];
		
							
							if(headers[j].includes('NPWP')){
								no_npwp = currentline[j];
							}else if(headers[j].toLowerCase().includes('referensi')){
								no_invoice = currentline[j];
							}else if(headers[j].toLowerCase().includes('nomor faktur')){
								no_fp = currentline[j];
							}else if(headers[j].toLowerCase().includes('harga')){
								harga = currentline[j];
							}else if(headers[j].toLowerCase() == 'ppn'){
								// console.log(j, 'data', headers[j], '=', currentline[j] );
								ppn = currentline[j];
							}else if(headers[j].toLowerCase() == 'status faktur'){
								// console.log(j, 'data', headers[j], '=', currentline[j] );
								status = currentline[j];
							}else if(headers[j].toLowerCase() == 'nama pembeli'){
								// console.log(j, 'data', headers[j], '=', currentline[j] );
								namaCustomer = currentline[j];
							}
	
						}
						// console.log(harga, ppn);
						
						
						if(typeof no_fp !== 'undefined' && no_fp.length > 0 && data_list[no_invoice] && status.toLowerCase() == 'approved'){
							// console.log(no_invoice,data_list[no_invoice]);
							const no_fp_list = data_list[no_invoice].no_faktur_pajak;
							if(no_fp_list != ""){
								// throw new Error(`${no_invoice} sudah memiliki no faktur pajak`);
								failedList.push({
									message: `${no_invoice} - ${namaCustomer} sudah memiliki no faktur pajak`,
									customer: namaCustomer,
									status: status
								});
							}else{
								successList.push({
									message: `${no_invoice} - ${namaCustomer} status ${status} : ${no_fp}`,
									customer: namaCustomer,
									status: status
								});
								const id = data_list[no_invoice].rekam_faktur_pajak_detail_id;
								newList.push({
									id: id,
									no_faktur_pajak: no_fp,
									total_dpp: parseFloat(harga),
									total_ppn: parseFloat(ppn)
								});
							}
	
						}else{
							if(no_invoice){
								failedList.push({
									message: `${no_invoice} - ${namaCustomer} gagal di update <span style='color:red'>${status}</span>`,
									customer: namaCustomer,
									status: status
								});
							}
						}
		
						result.push(obj);
					}
		
					const json = JSON.stringify(result);
					resolve(json);

				} catch (error) {
					alert(error);
					reject(error);
					dialog.modal('hide');
					return
				}

			};		
			
			reader.onerror = function() {
				reject(reader.error);
			};

			reader.readAsText(file);
		});
	} catch (error) {
		return ;
	}

	dialog.modal('hide');

	let msgSuccess = "";
	if (successList.length > 0) {
		msgSuccess += `<h4>${successList.length} Faktur Berhasil Diupdate:</h4><ul>`;
		successList.forEach(item => {
			msgSuccess += `<li>${item.message}</li>`;
		});
		msgSuccess += "</ul>";
	}

	let msgFailed = "";
	if (failedList.length > 0) {
		msgFailed += `<h4>${failedList.length} Faktur Gagal Diupdate:</h4><ul>`;
		failedList.forEach(item => {
			msgFailed += `<li>${item.message}</li>`;
		});
		msgFailed += "</ul>";
	}

	let actionMsg = "<hr/><h3 class='text-center'>SAVE ke database ? </h3> ";

	if(newList.length == 0){
		actionMsg = "<hr/>Tidak ada data baru";
	}

	bootbox.confirm({
		title: "Hasil Upload CSV",
		message: msgSuccess + msgFailed + actionMsg,
		buttons: {
			confirm: {
				label: 'OK',
				className: 'btn-success'
			}
		},
		callback: function (result) {
			if (result) {
				if(newList.length > 0){
					updateNoFakturPajak(newList);
				}else{
					return
				}
			}
		}
	});
	
}

async function updateNoFakturPajak(fakturList){

	let dialog = bootbox.dialog({
		message: '<p class="text-center mb-0"><i class="fas fa-spin fa-cog"></i> Updating Faktur...</p>',
		closeButton: false
	});

	try {
		const response = await fetch('<?=base_url()?>pajak/rekam_pajak_update_no_faktur_coretax', {
			method: 'POST',
			headers: {
				'Content-Type': 'application/json'
			},
			body: JSON.stringify(fakturList)
		});
		const result = await response.json();
		const afRow = result.affected_rows;
		if(afRow == 0){
			throw new Error('No faktur pajak tidak ada yang diupdate');
		}else{
			dialog.find('.bootbox-body').html(`<i class="fas fa-spin fa-cog"></i> <h3>${afRow} Faktur berhasil diupdate </h3>refreshing page...`);
			location.reload();
		}
	} catch (error) {
		alert(error);
	}

	
	setTimeout(() => {
		dialog.modal('hide');
	}, 3000);
}

function filterRegistered(status){
	// 0 = all, 1 = registered, 2 = unregistered
	// 0 = #btnFilterAll, 1 = #btnFilterRegistered, 2 = #btnFilterUnregistered
	if(status == 0){
		$('#general_table tr').show();
	}else if(status == 1){
		$('#general_table tbody tr').hide();
		$('#general_table tbody tr.registered').show();
	}else if(status == 2){
		$('#general_table tbody tr').hide();
		$('#general_table tbody tr.unregistered').show();
	}

}


</script>
