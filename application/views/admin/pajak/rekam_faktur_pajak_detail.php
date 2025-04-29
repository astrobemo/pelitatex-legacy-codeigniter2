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
</style>

<div class="page-content">
	<div class='container'>

		<?foreach ($data_rekam_faktur as $row) {
			$data_status = $row->status;
			# code...
		}?>

		<div class="modal fade" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body">
						<form action="<?=base_url('pajak/rekalkulasi_no_pajak')?>" class="form-horizontal" id="form_add_data" method="post">
							<h3 class='block'> Nomer Baru</h3>


			               	<div class="form-group">
			                    <label class="control-label col-md-4">No Awal<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<input name="id" value="<?=$rekam_faktur_pajak_id;?>" hidden>
			                    	<input class='form-control' name='no_start' id="no-start" >
			                    </div>
			                </div>

						</form>
					</div>

					<div class="modal-footer">
						<button type="button" class="btn blue btn-save">Save</button>
						<button type="button" class="btn default" data-dismiss="modal">Close</button>
					</div>
				</div>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
		</div>

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
								<a href="<?=base_url()?>pajak/rekam_faktur_list_export_excel?id=<?=$rekam_faktur_pajak_id;?>" class='btn btn-lg green'><i class='fa fa-download'></i>E-Faktur</a>
								<button onclick="generateXML('<?=$rekam_faktur_pajak_id;?>')" class='btn btn-lg btn-primary'><i class='fa fa-download'></i>Coretax</button>
							<?}else{?>
								<!-- <p>File dapat di Download setelah Anda Lock</p>  -->
								<button class='btn btn-lg yellow-gold btn-lock'><i class='fa fa-lock'></i> LOCK</button>
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

						<h1>TOTAL : <?=count($fp_list_detail);?> Faktur</h1>
						<table>
							<tr>
								<td style='padding-right:20px;'>
									<h3>Aktif : <span class='total-aktif'>load...</span><br/>
									Batal : <span class='total-batal'>load...</span></h4>
									<p style='font-size:1.2em'>Warning : <b class='total-warning'  style='background:yellow; padding:2px 0px;'>load...</b>
										<label><input type='checkbox' <?=($filter==1 ? 'checked': '');?> class='bg-warning-filter'>Lihat Warning Saja</label>
									</p>
								</td>
							</tr>
						</table>
						<hr/>
						<!-- <table class="table table-hover table-striped table-bordered" id="general_table"> -->
						<p>
							Info : 
							<table>
								<tr>
									<td style="width:100px">NPWP</td>
									<td style='padding: 0 5px;'>:</td>
									<td style="background:#c4d8ff; width:100px; border:1px solid #ddd"></td>
								</tr>
								<tr>
									<td style="width:100px">NIK</td>
									<td style='padding: 0 5px;'>:</td>
									<td style="width:100px; border:1px solid #ddd"></td>
								</tr>
							</table>
						</p>
						<table width='100%' id="general_table">
							<thead>
								<tr>
									<th scope="col">No</th>
									<th scope="col">
										Tanggal
									</th>
									<th scope="col">
										No Invoice
									</th>
									<th scope="col">
										Customer
									</th>
									<th scope="col">
										NPWP/NIK
									</th>
									<th scope="col">
										No Faktur
									</th>
									<th scope="col">
										Nilai
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
								$total_asli = 0; $total_raw = 0;
								$no_fp_awal = ""; $no_fp_akhir = "";
								$tanggal_awal = "";
								$tanggal_akhir = "";
								// $ppn_pengali = 0.11;
								foreach ($fp_list_detail as $row) { 
									$ppn_fix = $row->ppn_berlaku;
									$ppn_pengali = $ppn_fix /100;
									$ppn_pembagi = $ppn_pengali + 1;
									$no_fp_awal = ($no_fp_awal == "" ? $row->no_faktur_pajak : $no_fp_awal);
									$no_fp_akhir = $row->no_faktur_pajak;

									
									$tanggal_awal = ($tanggal_awal == "" ? $row->tanggal : $tanggal_awal);
									$tanggal_akhir = $row->tanggal;
									
									$bg_yellow = ($row->npwp == '' && $row->nik == '' ? "bg-warning" : 'bg-none');
									$warning += ($row->npwp == '' && $row->nik == '' ? 1 : 0);
									$bg_color = ($row->status == 0 ? "background:#ffaab7" : '');
									($row->status == 1 ? $total_aktif++ : ''); 
									($row->status == 0 ? $total_batal++ : '');

									$bg_npwp = ($row->npwp != '' && $row->npwp != 0 ? 'background:#c4d8ff' : '');
									?>
									<tr style="<?=$bg_npwp;?>; <?=$bg_color;?>" class='<?=$bg_yellow;?>' >
										<td><?=$idx+1;?></td>
										<td><?=is_reverse_date($row->tanggal)?></td>
										<td><a target='_blank' style='font-size:1.1em; font-weight:bold' href="<?=base_url().is_setting_link('transaction/penjualan_list_detail')?>?id=<?=$row->penjualan_id;?>"><?=$row->no_invoice?></a> <?=(is_posisi_id()==1? $row->penjualan_id : '');?></td>
											<?
											$filter_nama = strtoupper(trim($row->nama_customer));
										?>
										<td><?=$filter_nama//$row->nama_customer;?> <?//=($row->locked_status_cust==0 ? "<i style='color:blue' class='fa fa-check-circle'></i>" : '')?> <?=(is_posisi_id()==1 ? '('.$row->customer_id.')' : '');?> </td>
										<td>
											<?
											$NPWP_now = $row->npwp;
											$NPWP_now = str_replace('.', '', $NPWP_now);
											$NPWP_now = (float)$NPWP_now;
											if ($row->npwp == '' && $row->nik == '') {?>
												Tidak ada NPWP/NIK yang tercatat
											<?}else{
												if ($NPWP_now != '' && $NPWP_now != 0) {
													echo $row->npwp;
												}else{
													echo $row->nik.' #NIK#NAMA#'.$row->nama_customer; 
												}
											}

											echo "<hr style='margin:5px;' />";
											echo $row->alamat_cust_fp;
											?>

											<?//=$row->alamat_lengkap;?>
										</td>
										<td style='font-size:1.1em; font-weight:bold'>
											<?if ($idx == 0) {
												$no_data = explode('.', $row->no_faktur_pajak);
												$no_faktur_start = end($no_data) ;
											}?>
											<?=$row->no_faktur_pajak;?>
										</td>
										<?
										$nilai_raw = number_format($row->g_total/($ppn_pembagi),'2','.','');
										$ppn = $nilai_raw*$ppn_pengali;

										if(count(explode('.',$nilai_raw)) > 1){
											$nilai_raw = floor($nilai_raw);
										}

										if(count(explode('.',$ppn)) > 1){
											$ppn = floor($ppn);
										}

										$total_nonppn += $nilai_raw;
										$total_ppn += $ppn;
										// $total += $nilai_raw+$ppn;
										$total_asli += $row->g_total;
										$total_raw += $row->g_total_raw;
										$total += $row->g_total_raw+$ppn;

										?>
										<td style="font-size:1.2em;"><?=number_format($row->g_total_raw,'0',',','.')?></td>
										<td style="font-size:1.2em;"><?=number_format($ppn,'0',',','.' );?></td>
										<td><?=number_format($row->g_total_raw + $ppn,'0',',','.');?></td>

										<?/*
										<td style="font-size:1.2em;"><?=number_format($row->g_total_raw,'2',',','.');?></td>
										<td style="font-size:1.2em;"><?=number_format($row->g_total_raw * 0.1,'2',',','.' );?></td>
										<td><?=number_format($row->g_total_raw * 1.1,'2',',','.');?></td>
										*/?>


										<td hidden><?=$row->updated?></td>
										<td>
											<span class='status' hidden><?=$row->status;?></span>
											<span class='id' <?=(is_posisi_id()!=1 ? 'hidden' : '');?> ><?=$row->id;?></span>
											<button class="btn btn-xs red btn-remove"><i class='fa fa-times red'></i>remove</button>
											<?if ($row->status == 1) {?>
												<button class="btn btn-xs yellow-gold btn-batal"><i class='fa fa-warning'></i>batal</button>
											<?}else{?>
												<button class="btn btn-xs blue btn-aktif"><i class='fa fa-check'></i>Aktifkan</button>
											<?}?>
										</td>
									</tr>
								<?$idx++;}?>
							</tbody>
						</table>

						<table class='table' style="font-size:2em">
							<tr>
								<th>SubTotal</th>
								<th>PPn</th>
								<th>TOTAL</th>
							</tr>
							<tr>
								<td><b><?//=number_format($total_nonppn,'2',',','.');?><?=number_format($total_raw,'2',',','.')?></b></td>
								<td><b><?=number_format($total_ppn,'2',',','.');?></b></td>
								<td><b><?=number_format($total,'2',',','.');?></b></td>
							</tr>
						</table>

							<form action="<?=base_url().'pajak/faktur_rekam_pajak_lock'?>" method="POST" id="form-lock" <?=(is_posisi_id() != 1 ? 'hidden' : '');?> >
								<input type="text"  name='rekam_faktur_pajak_id' value="<?=$rekam_faktur_pajak_id?>">
								<input type="text"  name='tanggal_awal' value="<?=$tanggal_awal?>">
								<input type="text"  name='tanggal_akhir' value="<?=$tanggal_akhir?>">
								<input type="text"  name='no_fp_awal' value="<?=$no_fp_awal?>">
								<input type="text"  name='no_fp_akhir' value="<?=$no_fp_akhir?>">
								<input type="text"  name='jumlah_trx' value="<?=$idx?>">
								<input type="text"  name='nilai' value="<?=$total_raw?>">
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
jQuery(document).ready(function() {

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
				window.location.replace(baseurl+"pajak/faktur_rekam_pajak_remove?id="+id+"&rekam_faktur_pajak_id="+"<?=$rekam_faktur_pajak_id?>");
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

});

function generateXML(rekam_faktur_pajak_id){

	let dialog = bootbox.dialog({
		message: '<p class="text-center mb-0"><i class="fas fa-spin fa-cog"></i> Please wait while generating xml...</p>',
		closeButton: false
	});

	// do something in the background

	const getToko = <?=json_encode($this->toko_list_aktif)?>[0];
	console.log(getToko);

	$.ajax({
		url: 'https://api.favourtdj.com/pajak/generate_faktur_pajak_coretax',
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
            link.download = `fp_coretax-${getToko.nama}-periode_${tanggal_awal}_sd_${tanggal_akhir}.xml`;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
			dialog.modal('hide');
            // Handle the response here
		},
		error: function(error) {
			console.error('Error fetching XML file:', error);
		}
	});
}


</script>
