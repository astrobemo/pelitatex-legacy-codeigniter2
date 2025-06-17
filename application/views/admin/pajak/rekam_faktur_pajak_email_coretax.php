<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css'); ?>"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css'); ?>"/>
<?=link_tag('assets/global/plugins/dropzone/css/dropzone.css'); ?>

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

.email-1{
	background: #dff7f7;
}

.manual-1{
	background: #e2ff99;
}

.action-tbl tr td{
	border: none !important;
}

.keterangan-section{
	z-index: 1000;
}

#btn-get-token, #btn-draft-mail{
	display:none;
}

#layer{
	display:none;
	position: fixed;
	height:100vh;
	width:100vw;
	background-color:rgba(0,0,0,0.5);
	top:0;
	left:0;
}

#layer .text{
	padding-top:20%;
	font-size:30px;
	width:100%;
	text-align:center;
	color:#eee;
}

#processListing, #processSending, #processDrafting{
	display:none;
}

.message-preview{
	border:1px solid #ddd;
	border-radius:3px;
	padding:10px;
	margin:10px 0px;
}

.message-preview ul li{
	list-style-type: none;
}


.message-preview ul{
	padding:0px;
}
</style>

<?$nama_toko = '';
foreach ($this->toko_list_aktif as $row) {
	$nama_toko = trim($row->nama);
}

$stat_manual[1] = "Dikirim";
$stat_manual[2] = "Diambil";
$stat_manual[3] = "Whatsapp";
$stat_manual[4] = "";

$client_id = '';
$client_secret = '';
$refresh_token = '';
$credentials = '';

foreach ($google_setting as $row) {
	$client_id = $row->google_client_id;
	$client_secret = $row->google_client_secret;
	$refresh_token = $row->google_refresh_token;
	$credentials = $row->google_credentials;
}
// $nama_toko = trim(str_replace('CV.', '', $nama_toko));
?>

<div class="page-content">
	<div class='container'>
		<?foreach ($rekam_faktur_data as $row) {
			$status_email = $row->status_email;
			$no_surat = $row->no_surat;
			$tahun = date('Y', strtotime($row->created_at));
		}
		foreach ($this->toko_list_aktif as $row) {
			$pre_po = $row->pre_po;
		}
		?>

		<div class="modal fade" id="portlet-config-pin" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body">
						<form action="<?=base_url('pajak/pajak_email_request_open');?>" class="form-horizontal" id="form-request-open" method="post">
							<h3 class='block'> Request Open</h3>
							
							<div class="form-group">
			                    <label class="control-label col-md-3">PIN<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<input name='id' value='<?=$rekam_faktur_pajak_id;?>' hidden>
									<input name='pin' type='password' class="pin_user" class="form-control">
			                    </div>
			                </div>	
		                </form>		                
					</div>

					<div class="modal-footer">
						<button type="button" class="btn blue btn-request-open">OPEN</button>
						<button type="button" class="btn default" data-dismiss="modal">Close</button>
					</div>
				</div>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
		</div>	
		
		<div class="modal fade" id="portlet-config-preview" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body">
						<p class="text-center mb-0" id="email-preview-loading"> 
							<img src="../../assets/admin/layout/img/loading.gif" alt="loading">
						</p>
						<div id="email_preview">

						</div>                
					</div>

					<div class="modal-footer">
						<!-- <button type="button" class="btn blue btn-request-open">OPEN</button> -->
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
							<?if ($status_email == 1) {?>
								<a href="#portlet-config" data-toggle='modal' class="btn btn-default btn-sm btn-form-add">
								<i class="fa fa-plus"></i> Upload File </a>
							<?}else{?>
								<a href="#portlet-config" data-toggle='modal' class="btn btn-default btn-sm btn-form-add">
								<i class="fa fa-eye"></i> Semua PDF </a>
							<?}?>
						</div>
					</div>
					<div class="portlet-body">
						<?if ($no_surat != '') {?>
							<span style='font-size:2em; float:right'><?=$pre_po.'-'.$tahun.'/01/'.str_pad($no_surat, 3,'0', STR_PAD_LEFT);?></span>
						<?}?>
						<table style='font-size:1.2em'>
							<?foreach ($rekam_faktur_data as $row) {?>
								<tr hidden>
									<td>Dibuat</td>
									<td>:</td>
									<td><?=date('d F Y H:i:s', strtotime($row->created_at, strtotime("+7 hours")))?></td>
								</tr>
								<tr>
									<td>LOCKED</td>
									<td>:</td>
									<td><?=($row->locked_date != '' ? date('d F Y H:i:s', strtotime($row->locked_date, strtotime("+7 hours"))) : "-" )?></td>
								</tr>
								<tr>
									<td>No FP </td>
									<td> : </td>
									<td>
										<b><?=$row->no_fp_awal?></b>
										s/d
										<b><?=$row->no_fp_akhir?></b>
									</td>
								</tr>
								<tr>
									<td>Tgl Invoice </td>
									<td> : </td>
									<td>
										<b><?=is_reverse_date($row->tanggal_start)?></b>
										s/d
										<b><?=is_reverse_date($row->tanggal_end);?></b>
									</td>
								</tr>
							<?}?>
						</table>
						<!-- <table class="table table-hover table-striped table-bordered" id="general_table"> -->
						<h1>NPWP : <?=count($fp_list_npwp);?> Customer</h1>
						<table class='table' id="general_table">
							<thead>
								<tr>
									<th scope="col" style="width:50px">No</th>
									<th scope="col" style="width:200px">
										Customer
									</th>
									<th scope="col" style="width:200px">
										Email
									</th>
									<th scope="col" hidden>
										NPWP
									</th>
									<th scope="col" style="width:200px">
										No FP
									</th>
									<th scope="col" class='text-center'>
										Status
									</th>
									<th scope="col">
										Actions
									</th>
								</tr>
							</thead>
							<tbody>
								<?$idx=1; $total_warn = 0; $total_complete=0; $pdf_linked = array();
								$filter_nama = [',','.',' ','/']; $nilai_ideal = count($fp_list_npwp);
								$filter_nama_cust = ["'","/","&"];
								$nilai_berjalan = 0; $total_faktur = 0; $total_email = 0; $total_status_sent = 0; $total_status_draft =0 ;
								$total_status_2 = 0; $total_status_3 = 0 ; $total_status_4=0; $total_pdf=0; $total_status_1 = 0;$total_status_null=0;
								$thread_list = array();
								$check_list = array();

								foreach ($this->toko_list_aktif as $row) {
									$nama_toko = $row->nama;
									$alamat_toko = $row->alamat;
									$telepon_toko = $row->telepon;
									$email_toko = $row->email;
									$host = $row->host;
									$relay_mail = $row->relay_mail;	
								}
								
								foreach ($fp_list_npwp as $row) {
									
									array_push($check_list, array(
										'id' => $row->rekam_faktur_pajak_email_id,
										'customer_id' => $row->customer_id,
										'thread_id' => $row->thread_id,
										'nama' => $row->nama,
										'rekam_faktur_pajak_email_id' => $row->rekam_faktur_pajak_email_id
									));
									
									
									if ($row->thread_id != '' && $row->label_id != 'SENT') {
										array_push($thread_list, array(
											'customer_id' => $row->customer_id,
											'thread_id' => $row->thread_id,
											'nama' => $row->nama,
											'rekam_faktur_pajak_email_id' => $row->rekam_faktur_pajak_email_id
										));
									}

									
									$nilai_email = 0;
									$nilai_manual = 0;
									$nilai_pdf = 1;
									$warning_filter = '';
									// $row->email = 'jong_xiang@ymail.com, hendry0485@gmail.com';
									$email = explode(',', $row->email);
									$tanggal_invoice = explode(',', $row->tanggal_invoice);
									$penjualan_id = explode(',', $row->penjualan_id);
									$filter = 2;
									// $row->email = ($i==7 || $i == 8 ? $row->email='jong_xiang@ymail.com' : $row->email);
									foreach ($email as $key => $value) {
										$break_mail = explode('@', $value);
										$domain = true;

										if (isset($break_mail[1])) {
											if (!checkdnsrr($break_mail[1], 'MX')) {
											    $domain = false;
											    $total_warn++;
											}
										}

										if (!filter_var(trim($value), FILTER_VALIDATE_EMAIL)) {
											$filter--;
											$warning_filter = "<i class='fa fa-times' style='color:red'></i> Email kosong/tidak valid";
										}
									}

									if($filter == 2){
										if($row->label_id == 'SENT'){
											$total_status_sent++;
										}elseif($row->label_id == 'DRAFT'){
											$total_status_draft++;
										}else{
											$total_status_null++;
										}
									}


									$no_faktur_pajak = explode('??', $row->no_faktur_pajak);
									$warning_file = "";
									foreach ($no_faktur_pajak as $key => $value) {
										$total_faktur++;
										$break_name = explode('.', $value);
										$nama_file[$key] = str_replace($filter_nama, '_', $nama_toko.'-'.str_replace($filter_nama_cust, '', trim($row->nama) )).'-'.date('dmY',strtotime($tanggal_invoice[$key])).'-'.end($break_name).'.pdf';
										if (!file_exists("./fp_list/fp_".$rekam_faktur_pajak_id."/".$nama_file[$key])) {$filter--; $nilai_pdf--; $warning_file = "<i class='fa fa-times' style='color:red'></i> File pdf tidak lengkap"; }else{$total_pdf++;}
									}
									
									if ($filter == 2 && $row->email_stat == 0) {
										$total_complete++;
									}

									$ket_show = ''; $ket_field_show = '';
									for ($i=1; $i <=4 ; $i++) { 
										$st = 'status_'.$i;
										if ($row->$st == 1) {
											${'total_'.$st}++;
											$nilai_manual=1;
											if ($i == 4 && $row->keterangan != '') {
												$ket_field_show="<i class='fa fa-check' style='color:blue'></i>".$row->keterangan;
											}else{
												$ket_show .="<i class='fa fa-check' style='color:blue'></i>".$stat_manual[$i];
											}?>
										<?};
									}
									?>
									<tr class="<?=($filter == 2 ? 'email-1' : ($nilai_manual == 1 && $nilai_pdf == 1 ? 'manual-1' : ''));?>">
										<td><?=$idx;?></td>
										<td class='nama'>
											<?=$row->nama?><br/>
											<?if (is_posisi_id()==1) {
												$body = "<input id='subject-".$idx."' value='Pajak Keluaran $row->nama'>";
												// echo $body;
											?>
											<!-- <button class='btn btn-xs' onclick="copyText('subject-<?=$idx?>')"><i class='fa fa-copy'></i></button> -->
											<?
												$body = "Kepada Yth.<br/>";
												$body .= $row->nama."<br/><br/>";
												$body .= "<p>Berikut adalah file attachment faktur pajak keluaran $nama_toko</p>";
												$body .= "<p>Regards, <br/><br/> 
														<b>Anthony Tedjasukmana</b></p>";
							
												// $body .= "<p><img style='width:70px' src='".base_url()."image/LOGO_MED.png'></p>";
							
												$body .="<p style='color:#990000'><b>$nama_toko</b>
														<br/>$alamat_toko, Bandung
														<br/>West Java, Indonesia
														<br/>40181
														
														</p>";
							
												$body .= "<p style='color:#990000'>";
												$body .= "\xF0\x9F\x93\x9E".": 0812 2313 0909 // ".$telepon_toko."<br/>";
												$body .= "\xE2\x9C\x89".": ".$relay_mail.' // '.$email_toko;
												$body .= "</p>";
												
												// echo $body;
											}?>
											<!-- <textarea id="body-<?=$idx?>" hidden><?=$body;?></textarea>
											<button class='btn btn-xs' onclick="copyText('body-<?=$idx?>')">COPY BODY<i class='fa fa-copy'></i></button> -->

										</td>
										<td><span class='email' ><?=$row->email;?></span>
										<?if (is_posisi_id()==1) {?>
											<!-- <input id="email-<?=$idx?>" value="<?=$row->email;?>"> -->
											<!-- <button class='btn btn-xs' onclick="copyText('email-<?=$idx?>')"><i class='fa fa-copy'></i></button> -->
											
										<?}?> 
										<?=($row->email != '' && $domain == false ? "<i class='fa fa-warning'></i>" : "");?> </td>
										<td hidden><?=set_npwp_char($row->npwp)?></td>
										<td class='pdf_list' hidden><?=$row->no_faktur_pajak?></td>
										<td>
											<table>
											<?foreach ($no_faktur_pajak as $key => $value) {
												$break_name = explode('.', $value);
												$pdf_linked[$nama_file[$key]] = true; ?>
												<tr>
													<td style='border:none; padding:0 10px 0 0;' ><?=is_reverse_date($tanggal_invoice[$key]);?></td>
													<td style='border:none' ><a href="<?=base_url().is_setting_link('transaction/penjualan_list_detail')?>?id=<?=$penjualan_id[$key]?>" target='_blank'> <?=$value;?></a></td>
													<td style='border:none' >
														<?if (file_exists("./fp_list/fp_".$rekam_faktur_pajak_id."/".$nama_file[$key])) {?>
															<a class='btn btn-xs blue' href=<?="./fp_list/fp_".$rekam_faktur_pajak_id."/".$nama_file[$key];?> target="_blank" ><i class='fa fa-file'></i> pdf</a>
														<?}?>
													</td> 
													<?//=$nama_file;?>
												</tr>
											<?}?>
											</table>
										</td>
										<td class='text-center'><?=$row->label_id?></td>
										<td>
											<span class='customer_id' <?=(is_posisi_id() != 1 ? 'hidden' : '');?>><?=$row->customer_id;?></span>

											<table class='action-tbl'>

												<tr>
													<td>PDF</td>
													<td> : </td>
													<td>
														<?=($warning_file == '' ? "<i class='fa fa-check' style='color:blue'></i>" : $warning_file);?>
													</td>
												</tr>
												<tr <?=($status_email == 0 ? ($warning_filter != '' && $row->email_stat == 0 ? 'hidden' : $total_email++ ) : '' )?> >
													<td style='vertical-align:top'>E-mail</td>
													<td style='vertical-align:top'> : </td>
													<td>
														<span class='customer_id' hidden><?=$row->customer_id;?></span>
														<?$nilai_email = ($filter == 2 ? 1 : 0);?>
														<?if ($row->kirim_id != '' && $filter == 2 && $row->email_stat == 1 && $row->label_id == 'SENT') {?>
															<span class='no-token-resend'>sediakan token untuk kirim ulang</span>
															<button onclick="resendEmail('<?=$row->rekam_faktur_pajak_email_id;?>', '<?=$row->customer_id;?>','<?=$row->nama?>')" class='btn btn-xs yellow-gold btn-active email-send-satuan'>kirim ulang</button>
														<?
															if($row->message_id != '') {?>
																<br/>
																<button style="margin-top:10px;" href="#portlet-config-preview" data-toggle="modal" onclick="viewEmail('<?=$row->thread_id;?>')" class='btn btn-xs blue btn-active email-view-satuan'>view Email</button>
																
															<?}
														}else if($row->label_id == 'DRAFT'){?>
															<i class='fa fa-warning' style='color:orange'></i> <span>Belum terkirim</span>
														<?}else{
															if ($filter == 2) {?>
																<i class='fa fa-check' style='color:blue'></i> <span>Siap Kirim</span>
															<?}else{
																echo ($warning_filter == '' ? '' : $warning_filter);
															}
														}?>
													</td>
												</tr>
												<tr <?=($status_email == 0 ? ($nilai_manual == 0 ? 'hidden' : '' ) : '');?> >
													<td style='vertical-align:top'>Others</td>
													<td style='vertical-align:top'> : </td>
													<td style='vertical-align:top'>
														<span class='customer_id' hidden><?=$row->customer_id;?></span>
														<?if ($status_email == 1) {?>
															<button class='btn btn-xs blue btn-active email-send-manual'><i class='fa fa-edit'></i></button>
														<?}?>
														<span class='email-send-manual-check'><?=$ket_show;?>
														</span>
														<span class='ket-field-show'><?=$ket_field_show;?></span>
														<div class='keterangan-section' hidden style='position:absolute; padding:10px; background:#defff2;'>
															<div class="checkbox-list">
																<label>
																<input type="checkbox" name="keterangan" value="1" class='keterangan-check' <?=($row->status_1 == 1 ? 'checked' : ''); ?> >Dikirim </label>
																<label>
																<input type="checkbox" name="keterangan" value="2" class='keterangan-check' <?=($row->status_2 == 1 ? 'checked' : ''); ?> >Diambil </label>
															</div>
															<div class="checkbox-list">
																<label>
																<input type="checkbox" name="keterangan" value="3" class='keterangan-check' <?=($row->status_3 == 1 ? 'checked' : ''); ?> >Whatsapp </label>
																<label>
																<input type="checkbox" name="keterangan" value="4" class='keterangan-check' <?=($row->status_4 == 1 ? 'checked' : ''); ?> >Others : </label>
																<input <?=($row->status_4 == 1 ? '' : 'disabled'); ?> name='keterangan_field' value="<?=($row->status_4 == 1 ? $row->keterangan : ''); ?>" >
															</div>
														</div>
													</td>
												</tr>
											</table>
											
											<?if ($nilai_email == 1 || $nilai_manual == 1) {
												if ($nilai_pdf == 1) {
													$nilai_berjalan++;
												}
											}?>
											<?//=($row->email != '' && $domain == false ? "<i class='fa fa-warning'></i>" : "");?>
										</td>
									</tr>
								<?$idx++;}?>
							</tbody>
						</table>
						<?if ($status_email == 0) {?>
							<table class='table text-center' style='font-size:1.5em'>
								<tr>
									<th class='text-center'>Email</th>
									<?for ($i=1; $i <=4 ; $i++) { 
										echo "<th class='text-center'>".($i != 4 ? $stat_manual[$i] : 'Others')."</th>";
									}?>
								</tr>
								<tr>
									<td><?=$total_email?></td>
									<?for ($i=1; $i <=4 ; $i++) { ?>
										<td>
											<?$st = 'status_'.$i;
											echo ${'total_'.$st};
											?>
										</td>
									<?}?>
								</tr>
							</table>
						<?}?>
						
						<hr/>
						<table class='table'>
							<tr>
								<th>TOTAL FAKTUR</th>
								<th>TOTAL PDF</th>
							</tr>
							<tr>
								<td><?=$total_faktur?></td>
								<td><?=$total_pdf;?></td>
							</tr>
						</table>

						<hr/>

						<table>
							<tr>
								<th colspan='3'>Info</th>
							</tr>
							<tr>
								<td>Email</td>
								<td> : </td>
								<td style='width:60px; border:1px solid #000; background:#dff7f7' class='text-center'> <i class='fa fa-check'></i> </td>
							</tr>
							<tr>
								<td>Manual</td>
								<td> : </td>
								<td style='width:60px; border:1px solid #000; background:#e2ff99' class='text-center'> <i class='fa fa-check'></i></td>
							</tr>
						</table>
						<br/>

						<?/* if (is_posisi_id() == 1) {?>
							<button type="button" onclick="refreshToken()" id='btn-get-token' class="btn btn-lg yellow-gold">TOKEN</button>
							<button type="button" onclick="createMailList()" id='btn-draft-mail' class="btn btn-lg green">SEND</button>
							<button type="button" onclick="checkMailList()" id='btn-check-mail' class="btn btn-lg blue">UPDATE STATUS EMAIL</button>
						<?} */?>

						<!-- <button type="button" onclick="createMailList()" class="btn btn-lg green">CREATE DRAFTS</button> -->


						<?if ($status_email == 1 && count($fp_list_npwp) > 0) {?>
							<div style="float:right">
								<a href='#' onclick="window.location.reload()" style="<?=($nilai_berjalan == $nilai_ideal ? 'display:none' : '');?>" class='btn btn-lg yellow-gold btn-refresh'><i class='fa fa-refresh'></i>REFRESH</a>
							</div>
							<button style="<?=($nilai_berjalan == $nilai_ideal ? '' : 'display:none');?>" class='btn btn-lg red btn-lock'><i class='fa fa-lock'></i>LOCK</a>
						<?}elseif (count($fp_list_npwp) > 0){?>
							<div style="float:right">
								<a href="#portlet-config-pin" data-toggle='modal' class='btn btn-lg red btn-open-lock'><i class='fa fa-unlock'></i>OPEN</a>
							</div>

							<button type="button" onclick="refreshToken()" id='btn-get-token' class="btn btn-lg yellow-gold">TOKEN</button>
							<?if($total_status_null + $total_status_draft > 0){?>
								<button type="button" onclick="sendAllMail()" id="btn-draft-mail" class="btn btn-lg green"> SEND All (<?=$total_status_null + $total_status_draft?>)</button>
							<?}?>
							<?/* if (count($draft_list) > 0) {?>
								<button type="button" onclick="sendMailList()" id='btn-send-mail' class="btn btn-lg blue">SEND</button>
							<?} */?>

							<?if($total_status_sent || $total_status_draft){?>
								<button type="button" onclick="checkStatusList()" id='btn-check-mail' class="btn btn-lg blue">UPDATE STATUS EMAIL</button>
							<?}?>

							<?/* if (count($thread_list > 0) && is_posisi_id() == 1) {?>
								<button type="button" onclick="checkMailList()" id='btn-check-mail' class="btn btn-lg blue">UPDATE STATUS EMAIL</button>
							<?} */?>
							
							<?if ($total_complete == 0) {?>
								<a href="<?=base_url()?>pajak/download_all_pajak_pdf?id=<?=$rekam_faktur_pajak_id;?>" class='btn btn-lg red btn-download'><i class='fa fa-download'></i> DOWNLOAD ALL PDF</a>
							<?}?>
						<?}
						/*elseif (count($fp_list_npwp) > 0){?>
							<a href="#portlet-config-pin" data-toggle='modal' class='btn btn-lg red btn-open-lock'><i class='fa fa-unlock'></i>OPEN</a>
							<button <?=($total_complete <= 0 ? 'disabled' : 'id="email-send-all"' );?> class='btn btn-lg green btn-active' >EMAIL Ke: <?=$total_complete;?> customer(s)</button>
							<?if ($total_complete == 0) {?>
								<a href="<?=base_url()?>pajak/download_all_pajak_pdf?id=<?=$rekam_faktur_pajak_id;?>" class='btn btn-lg red btn-download'><i class='fa fa-download'></i> DOWNLOAD ALL PDF</a>
							<?}?>
						<?}*/?>



					</div>
				</div>
			</div>
		</div>

		<div class="modal fade" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
						<h4 class="modal-title">Select File</h4>
					</div>
					<div class="modal-body">
						<div class="tabbable tabs-left">
						</div>
						<ul class="nav nav-tabs">
							<li id="button_tab_modal_1" class="active">
								<a href="#tab_modal_1" data-toggle="tab" aria-expanded="false">
								Upload </a>
							</li>
							<li id="button_tab_modal_2" class="">
								<a href="#tab_modal_2" data-toggle="tab" aria-expanded="false">
								Daftar FP</a>
							</li>
						</ul>
						<div class="tab-content">
							<div class="tab-pane active in" id="tab_modal_1">
								<?if($status_email == 1){?>
									<form  action="<?=base_url('pajak/upload_dropzone_faktur_pajak_coretax'); ?> " class="dropzone" id="my-dropzone">
										<input name='id' value="<?=$rekam_faktur_pajak_id;?>" hidden>
									</form>
								<?}else{?>
									<h2>Sudah Di Lock</h2>
								<?}?>
							</div>
							<div class="tab-pane" id="tab_modal_2">
								<table class='table' id="pdf-list">
								</table>
							</div>
						</div>
								
					</div>
					<div class="modal-footer">
						<!-- <button type="button" class="btn blue">Save changes</button> -->
						
						<button type="button" class="btn default" data-dismiss="modal">Close</button>
					</div>
				</div>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
		</div>
	</div>			
</div>

<div id='layer'>
	<div id='processListing' class="text">Prepare Email List....</div>
	<div id='processSending' class="text">sending....<br/>
	<span class='info-nama-cust'></span> 
	(<span class='counter'></span>)</div>
	<div id='processDrafting' class="text">Create Draft....<br/>
		<span id='processBatching' class="text">Checking Batch....</span>
		<span class='info-nama-cust'></span> 
		(<span class='counter'></span>)</div>

	<div id='processChecking' class="text">checking....<br/>
		<span class='info-nama-cust'></span> 
		(<span class='counter'></span>)</div>
</div>

<script src="<?php echo base_url('assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/datatables/media/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/global/plugins/bootbox/bootbox.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/dropzone/dropzone.js'); ?>"></script>

<script>

var pdf_linked = {};
<?foreach ($pdf_linked as $key => $value) {?>
	pdf_linked["<?=$key?>"] = true;
<?}?>

const rekam_faktur_pajak_id = "<?=$rekam_faktur_pajak_id?>";
let tokenBerlaku = '';
const threadList = <?=json_encode($thread_list);?>;
const credential = "<?=$credentials?>";
const checkList = <?=json_encode($check_list);?>;


const cekTokenBerlaku = () =>{
	const cookies = document.cookie;
	if (cookies.length > 0) {
		const cookieList = cookies.split(';');
		for (let item of cookieList) {
			const n = item.toString().trim().split('=');
			// console.log(n);
			if (n[0] == 'rtgs') {
				tokenBerlaku = n[1];
			}
		}
	}

	// console.log('tkn',tokenBerlaku.length);

	const btn_draft = document.querySelector("#btn-draft-mail");
	const btn_mail =  document.querySelector("#btn-send-mail");
	const btn_token = document.querySelector("#btn-get-token");
	const resend_btn = document.querySelectorAll(".email-send-satuan");
	const view_btn = document.querySelectorAll(".email-view-satuan");
	const no_token_resend = document.querySelectorAll(".no-token-resend");
	const btn_check_mail = document.querySelector("#btn-check-mail");
	console.log(resend_btn.length);

	<?if ($status_email != 1) {?>
		// alert(tokenBerlaku.length);
		if (tokenBerlaku.length > 0) {
			if (btn_draft) {
				btn_draft.style.display = 'inline-block';
			}
			if (btn_mail) {
				btn_mail.style.display = 'inline-block';
			}
			btn_check_mail.style.display = 'inline-block';
			btn_token.style.display = 'none';

			
			if (resend_btn.length > 0) {
				resend_btn.forEach((btn,index) => {
					btn.style.display = "inline-block";
					if(view_btn[index]){
						view_btn[index].style.display = "inline-block";
					}
					no_token_resend[index].style.display = "none";
				});
			}else{
				resend_btn.forEach(btn => {
					btn.style.display = "none";
					view_btn[index].style.display = "none";
					no_token_resend[index].style.display = "inline-block";
				});
			}
		}else{
			if (btn_draft) {
				btn_draft.style.display = 'none';
				console.log(btn_draft.style.display);
			}
			if (btn_mail) {
				btn_mail.style.display = 'none';
				btn_check_mail.style.display = 'none';
			}
			btn_token.style.display = 'inline-block';

			if (resend_btn.length > 0) {
				resend_btn.forEach((btn,index) => {
					btn.style.display = "none";
					if(view_btn[index]){
						view_btn[index].style.display = "inline-block";
					}
					no_token_resend[index].style.display = "inline-block";
				});
			}else{
				resend_btn.forEach(btn => {
					btn.style.display = "inline-block";
					view_btn[index].style.display = "inline-block";
					no_token_resend[index].style.display = "none";
				});
			}

			// if (no_token_resend.length > 0) {
			// 	no_token_resend.forEach((btn,index) => {
			// 		btn.style.display = "inline-block";
			// 	});
			// }else{
			// 	no_token_resend.forEach(btn => {
			// 		btn.style.display = "none";
			// 	});
			// }
		}
	<?}?>
}

cekTokenBerlaku();

var stat_manual = [];
stat_manual[1] = "Dikirim";
stat_manual[2] = "Diambil";
stat_manual[3] = "Whatsapp";
stat_manual[4] = "";
// console.log(pdf_linked);
jQuery(document).ready(function() {

	// $("#general_table").DataTable({
	// 	"ordering":false,
	// 	// "bFilter":false
	// });

	$('#button_tab_modal_2').click(function(){
		var data = {};
		var url = "pajak/get_pajak_pdf_list";
		let dir = "<? echo base_url($dir).'/';?>";
		data['id'] = "<?=$rekam_faktur_pajak_id;?>";
		refresh_image_dropzone(url,data,dir);
	});


	$("#general_table").on("click",".email-send-manual", function(){
		var ini = $(this).closest('tr');
		ini.find(".keterangan-section").toggle();
	});
	

	$("#pdf-list").on("click",".remove-pdf", function(){
		var ini = $(this).closest('tr');
		var data = {};
		data['id'] = "<?=$rekam_faktur_pajak_id;?>";
		data['filename'] = ini.find('.filename').html();
		var url = "pajak/remove_pdf";
		ajax_data_sync(url,data).done(function(data_respond /* ,textStatus, jqXHR */){
	        if (data_respond == 'OK') {
	        	$(".btn-lock").hide();
				$(".btn-refresh").show();
		        ini.remove();
	        };
	    });
	});

	$("#pdf-list").on("click",".remove-pdf-w-vldtn", function(){
		var ini = $(this).closest('tr');
		bootbox.confirm("File ini match dengan no faktur pajak, lanjutkan hapus ?", function(respond){
			if (respond) {
				var data = {};
				data['id'] = "<?=$rekam_faktur_pajak_id;?>";
				data['filename'] = ini.find('.filename').html();
				var url = "pajak/remove_pdf";
				ajax_data_sync(url,data).done(function(data_respond /* ,textStatus, jqXHR */){
			        if (data_respond == 'OK') {
			        	$(".btn-lock").hide();
						$(".btn-refresh").show();
				        ini.remove();
			        };
			    });
			};
		});
	});


	//===================================================================

	$(".btn-lock").click(function(){
		window.location.replace(baseurl+"pajak/rekam_faktur_pajak_email_lock?id="+"<?=$rekam_faktur_pajak_id?>");
	});

	$("#general_table").on("change",'.keterangan-check', function(){
		var ini = $(this).closest('tr');

		$(".btn-lock").hide();
		$(".btn-refresh").show();

		var keterangan = ini.find(".keterangan-check[value='4']").is(':checked');
		if(keterangan){
			ini.find('[name=keterangan_field]').prop('disabled',false);
			ini.find('[name=keterangan_field]').focus();
		}else{
			ini.find('[name=keterangan_field]').prop('disabled',true);
			ini.find('.ket-field-show').html('');
			ini.find('[name=keterangan_field]').val('');
		}

		// console.log(ini.find(".keterangan-check:checked").serialize());

		var keterangan_status = [];
		var ket = '';
		var ket_show='';
		ini.find('.keterangan-check:checked').each(function() {
			ket = stat_manual[this.value];
			keterangan_status.push(this.value);
			if (this.value != 4) {
				ket_show += `<i class='fa fa-check' style='color:blue'></i>${ket}`;
			};
		});
		ini.find('.email-send-manual-check').html(ket_show);

		var customer_id = $(this).closest('tr').find('.customer_id').html();
		var data = {};
		data['id'] = "<?=$rekam_faktur_pajak_id;?>";
		data['customer_id'] = customer_id;
		data['status'] = keterangan_status;
		var url = "pajak/update_email_send_manual";
		ajax_data_sync(url,data).done(function(data_respond /* ,textStatus, jqXHR */){
	        if (data_respond == 'OK') {
	        };
	    });
	});

	$("#general_table").on("change",'[name=keterangan_field]', function(){
		var ini = $(this).closest('tr');
		var customer_id = ini.find('.customer_id').html();
		var data = {};
		data['id'] = "<?=$rekam_faktur_pajak_id;?>";
		data['customer_id'] = customer_id;
		data['keterangan'] = $(this).val();
		var ket = $(this).val();
		var url = "pajak/update_email_send_keterangan";
		ajax_data_sync(url,data).done(function(data_respond /* ,textStatus, jqXHR */){
	        if (data_respond == 'OK') {
				var ket_show = `<i class='fa fa-check' style='color:blue'></i>${ket}`;
				ini.find('.ket-field-show').html(ket_show);
	        };
	    });
	});

	$('.btn-request-open').click(function(){
    	if(cek_pin($('#form-request-open'), '.pin_user')){
			$('#form-request-open').submit();
    	}
    });

    $('.pin_user').keypress(function (e) {
    	var form = '#'+$(this).closest('form').attr('id');
    	var obj_form = $(form);
        if (e.which == 13) {
        	if(cek_pin(obj_form, '.pin_user')){
				obj_form.submit();
        	}
        }
    });

    $(document).mouseup(function(e){
	    var container = $(".keterangan-section");

	    // if the target of the click isn't the container nor a descendant of the container
	    if (!container.is(e.target) && container.has(e.target).length === 0) 
	    {
	        container.hide();
	    }
    	
    });

});

function cek_pin(form, field_class){
	// alert('test');
	var data = {};
	data['pin'] = form.find(field_class).val();
	var url = 'transaction/cek_pin';
	// ajax_data_sync(url,data).done(function(data_respond  /*,textStatus, jqXHR*/ ){
	// 	if (data_respond == "OK") {
	// 		return true;
	// 	}else{
	// 		alert("PIN Invalid");
	// 	}
	// });

	var result = ajax_data(url,data);
	if (result == 'OK') {
		return true;
	};
}

function refresh_image_dropzone(url,data,dir, class_container){
    ajax_data_sync(url,data).done(function(data_respond /* ,textStatus, jqXHR */){
        // console.log(data_respond);
        // var arr = Object.values(JSON.parse(data_respond));
        var table = "";
        $.each(JSON.parse(data_respond), function(i,v){
            // console.log(i+'='+v);
			if (Number.isNaN(parseInt(i))) {
				return;
			}

            var name = v.split('.');
            // console.log(name);
            let btn = "", bg = '',ceklis='';
			<?if($status_email == 1){?>
				btn="<button class='btn btn-xs red remove-pdf'><i class='fa fa-times'></i> remove</button>";
			<?}?>
            if (typeof pdf_linked[v] !== 'undefined') {
				bg = 'background:#eee';
				ceklis="<i style='color:blue' class='fa fa-check'></i>";
				btn="<button class='btn btn-xs red remove-pdf-w-vldtn'><i class='fa fa-times'></i> remove</button>";
    		}else{
    			console.log(v);
    		};
            table += `<tr style='${bg}'>
            	<td><a target='_blank' href="${dir}${v.trim()}" class='filename'>${v}</a></td> 
            	<td>${ceklis}</td>
            	<td>${btn}</td>
            </tr>`;
        });

        $("#pdf-list").html(table);
    });
}

function copyText(ele){
	var copyText = document.getElementById(ele);
	console.log(copyText);

  /* Select the text field */
  copyText.select();
  copyText.setSelectionRange(0, 99999); /* For mobile devices */

   /* Copy the text inside the text field */
  navigator.clipboard.writeText(copyText.value);

  /* Alert the copied text */
  notific8("Lime","Copied the text: " + copyText.value);
}

function getToken() {
	const url = "google_service/get_token";
	const data_st = {};
	const res = ajax_data(url,data_st);
	console.log(res);
}

const refreshToken = async () => {
	const item = {
	client_id:"<?=$client_id?>",
	client_secret:"<?=$client_secret?>",
	grant_type:"refresh_token",
	refresh_token:"<?=$refresh_token?>"
	};
	
	document.querySelector('#layer').style.display = 'block';


	const response = await fetch('https://oauth2.googleapis.com/token', {
		method: 'POST',
		body: `client_id=${item.client_id}&client_secret=${item.client_secret}&grant_type=${item.grant_type}&refresh_token=${item.refresh_token}`, // string or object
		headers: {
			'Content-Type': 'application/x-www-form-urlencoded'
		},
	});

	const myJson = await response.json(); //extract JSON from the http response
	const div = document.querySelector("#token-cont");
	if (typeof myJson.access_token !== 'undefined') {
		document.cookie = `rtgs=${myJson.access_token}; max-age=3600; SameSite=None; Secure`;
		const dialog = bootbox.dialog({
			message: `<p class="text-center mb-0"><i class="fa fa-check"></i> sukses... refreshing now <i class="fa fa-spin fa-cog"></i> </p>`,
			closeButton: false
		});
		window.location.reload();
	}else{
		alert("Harus minta token lagi ( mohon contact IT / Pa Anthony )");
		document.querySelector('#layer').style.display = 'none';
	}
}


function sendAllMail(){

	document.querySelector("#processListing").style.display = 'block';
	if (cekTokenBerlaku) {

		bootbox.confirm(`Yakin mengirim email faktur pajak kepada customer ?.`, function(respond){
			if (respond) {
				processingMailList();		
			}
		});
	}else{
		alert("Tidak ada token berlaku");
	}
}

async function processingMailList(){

	const batchLimit = 15; // Set the batch limit
	const batch = await checkDraftList(batchLimit);
	console.log('batch', batch);
	for (let i = 0; i < batch; i++) {
		document.querySelector("#processBatching").innerHTML = 'Batching ' + (i+1) +'/'+batch+ '  data...';
		document.querySelector("#processBatching").style.display = 'none';
		await generateDraftData(batchLimit);
	}

	await generateMailData();
}

async function checkDraftList(batchLimit){
	document.querySelector("#processBatching").style.display = 'block';
	document.querySelector("#processListing").style.display = 'none';
	document.querySelector("#processDrafting").style.display = 'none';
	document.querySelector("#processSending").style.display = 'none';
	document.querySelector("#processChecking").style.display = 'none';
	document.querySelector('#layer').style.display = 'block';

	const response = await fetch(baseurl+`pajak/email_list_draft_list?id=${rekam_faktur_pajak_id}`);
	const data = await response.json();
	
	let batch = Math.ceil(data / batchLimit);	
	document.querySelector("#processBatching").innerHTML = 'Batching ' + batch + ' data...';
	console.log('batch', batch);

	return batch;

	// if (Array.isArray(data) && data.length > 0) {
		
	// 	document.querySelector("#processBatching").style.display = 'none';
	// 	document.querySelector('#processDrafting .info-nama-cust').innerHTML = content;
		
	// }else{
	// 	alert("Tidak ada email draft yang perlu di cek");
	// }
}

async function chunkArray(array, chunkSize) {
	const chunks = [];
	for (let i = 0; i < array.length; i += chunkSize) {
		chunks.push(array.slice(i, i + chunkSize));
	}
	return chunks;
}


async function generateDraftData(batchLimit){

	document.querySelector("#processDrafting").style.display = 'none';
	document.querySelector("#processSending").style.display = 'none';
	document.querySelector("#processChecking").style.display = 'none';
	document.querySelector('#layer').style.display = 'block';

	const response = await fetch(baseurl+`pajak/email_list_body?id=${rekam_faktur_pajak_id}&limit=${batchLimit}`);
	const data = await response.json();
	console.log(data.head);
	console.log(data.body);
	if (Array.isArray(data.body) && data.body.length > 0) {
		document.querySelector("#processListing").style.display = 'none';
		document.querySelector("#processChecking").style.display = 'none';
		document.querySelector("#processSending").style.display = 'none';
		document.querySelector("#processDrafting").style.display = 'block';
		const emailList = data.body;
		const customerList = data.head;
		// const max = (emailList.length > 5 ? 5 : emailList.length);

		const chunckedEmailList = await chunkArray(emailList, 15);
		const chuckedCustomerList = await chunkArray(customerList, 15);
		for (let i = 0; i < chunckedEmailList.length; i++) {
			const emailListChunk = chunckedEmailList[i];
			const customerListChunk = chuckedCustomerList[i];
			await generateDraftList(emailListChunk, customerListChunk);
		}

	}else{
		return false;
	}
}


async function generateDraftList(emailList, customerList){
	const fetchPromises = [];
	const draftList = [];
	const insertList = [];
	let draftCounter = 0;

	let content = '';
	
	for(const email of emailList){
		try {
			const fetchPromise = fetch('https://gmail.googleapis.com/gmail/v1/users/relay.do.bdg@gmail.com/drafts?access_token='+tokenBerlaku, {
				method: 'POST',
				body: JSON.stringify(email), // string or object
				headers: {
					'Content-Type': 'application/json'
				}
			})
			.then((response) => response.json())
			.then((data) => {
				content= `<p>draft untuk ... ${customerList[draftCounter].nama}</p>`;
				if(email.rekam_email_id != ''){
					insertList.push({
						rekam_faktur_pajak_id: rekam_faktur_pajak_id,
						customer_id: customerList[draftCounter].customer_id,
						email_stat: 1,
						message_id:data.message.id,
						thread_id:data.message.threadId,
						draft_id:data.id,
						label_id: 'DRAFT',
					});
				}else if(email.rekam_email_id == ''){
					draftList.push({
						id: email.rekam_email_id,
						label_id: 'DRAFT',
					});
				}
				document.querySelector('#processDrafting .info-nama-cust').innerHTML = content;
				document.querySelector('#processDrafting .counter').innerHTML = draftCounter +'/'+emailList.length;

				draftCounter++;
			});
	
	
			fetchPromises.push(fetchPromise);
		} catch (error) {
			console.error('Error:', error);
			break;
		}
	}

	await Promise.all(fetchPromises);
	if(insertList.length > 0){
		await insertKeteranganBatch(insertList);
	}

	if(draftList.length > 0){
		await updateKeteranganBatch(draftList);
	}
};


async function generateMailData(){
	let emailList = [];
	document.querySelector("#processListing").style.display = 'block';

	document.querySelector("#processSending").style.display = 'none';
	document.querySelector("#processDrafting").style.display = 'none';
	document.querySelector("#processChecking").style.display = 'none';
	document.querySelector('#layer').style.display = 'block';

	try {
		const response = await fetch(baseurl+`pajak/email_list_drafts?id=${rekam_faktur_pajak_id}`);
		const data = await response.json();
		if (Array.isArray(data) && data.length > 0) {
			document.querySelector("#processListing").style.display = 'none';
			document.querySelector("#processChecking").style.display = 'none';
			document.querySelector("#processDrafting").style.display = 'none';
			document.querySelector("#processSending").style.display = 'block';
			emailList = data;

			const chunckedEmailList = await chunkArray(emailList, 15);
			for (let i = 0; i < chunckedEmailList.length; i++) {
				const emailListChunk = chunckedEmailList[i];
				await generateMailList(emailListChunk);
			}

			setTimeout(async() => {
				await checkStatusList();
				// location.reload();
			}, 3000);

			// const max = (emailList.length > 5 ? 5 : emailList.length);
		} else {
			document.querySelector('#layer').style.display = 'none';
			alert("Email Draft sudah terkirim semua");
		}
	} catch (error) {
		console.error('Error:', error);
		document.querySelector('#layer').style.display = 'none';
		alert("An error occurred while fetching the data.");
	}
}

async function generateMailList(emailList){
	const fetchPromises = [];
	const sentList = [];
	let emailCounter = 0;
	let delayText='';

	if(!cekTokenBerlaku){
		document.querySelector('#layer').style.display = 'none';
		alert("Token tidak berlaku/habis waktu");
		return;
	}

	const delay = async (ms, nama, counter) => {
		while(ms > 0){
			delayText = `melanjukan dalam ${ms/1000} detik...`;
			await new Promise((resolve) => setTimeout(resolve, ms));
			content= `<p>Send email untuk ... ${nama}<br/>${delayText}</p>`;
			document.querySelector('#processSending .info-nama-cust').innerHTML = content;
			document.querySelector('#processSending .counter').innerHTML = counter +'/'+emailList.length;
			
			ms-=1000;
		}
		delayText = '';
	}

	for(const email of emailList){
		try {
			if (cekTokenBerlaku) {
				await delay(2000, email.nama_customer, emailCounter);
				
				const fetchPromise = fetch('https://gmail.googleapis.com/gmail/v1/users/relay.do.bdg@gmail.com/drafts/send?access_token='+tokenBerlaku, {
					method: 'POST',
					body: JSON.stringify({"id":`${email.draft_id}`}), // string or object
					headers: {
						'Content-Type': 'application/json'
					},
				})
				.then((response) => response.json())
				.then((data) => {					
					sentList.push({
						id: email.id,
						label_id: 'SENT'
					});
				});
	
				fetchPromises.push(fetchPromise);
				emailCounter++;
				
			}else{
				document.querySelector('#layer').style.display = 'none';
				bootbox.alert("Token tidak berlaku/habis waktu");
				throw new Error("Token tidak berlaku");
				return;
			}
		} catch (error) {
			console.error('Error:', error);
			break;
			
		}

	}

	await Promise.all(fetchPromises);		
	
}

function resendEmail(rekam_faktur_pajak_email_id, customer_id, nama_customer) {
	bootbox.confirm(`Yakin mengirim ulang email pajak untuk ${nama_customer}`, function(resp){
		if (resp) {
			emailCounter = 0;
			document.querySelector("#processListing").style.display = 'block';
			
			if (cekTokenBerlaku) {
				document.querySelector("#processDrafting").style.display = 'none';
				document.querySelector("#processSending").style.display = 'none';
				document.querySelector("#processChecking").style.display = 'none';
				document.querySelector('#layer').style.display = 'block';
		
				fetch(baseurl+`pajak/email_single_list_body?rekam_faktur_pajak_id=${rekam_faktur_pajak_id}&customer_id=${customer_id}&rekam_faktur_pajak_email_id=${rekam_faktur_pajak_email_id}`)
				.then((response) => response.json())
				.then((data) => {
					console.log(data.head);
					console.log(data.body);
					if (Array.isArray(data.body) && data.body.length > 0) {
						document.querySelector("#processListing").style.display = 'none';
						document.querySelector("#processChecking").style.display = 'none';
						document.querySelector("#processSending").style.display = 'none';
						document.querySelector("#processDrafting").style.display = 'block';
						emailList = data.body;
						customerList = data.head;
						// const max = (emailList.length > 5 ? 5 : emailList.length);
						draftLoop(emailCounter);
						
					}else{
						document.querySelector('#layer').style.display = 'none';
						alert("No Data");
					}
				});
			}else{
				alert("Tidak ada token berlaku");
			}
		}
	})
}

function viewEmail(thread_id){
	const div = document.querySelector("#email_preview");
	document.querySelector('#email-preview-loading').style.display= "block";
	div.innerHTML = "";

	let mail_show = ''; 
	fetch(`https://gmail.googleapis.com/gmail/v1/users/relay.do.bdg@gmail.com/threads/${thread_id}?access_token=${tokenBerlaku}`)
	.then((response) => response.json())
	.then((data) => {
		
		const messages = data.messages;
		messages.forEach(msg => {
			let from = '';
			let to = '';
			let subject = '';
			let datetime = new Date(parseInt(msg.internalDate));
			
			msg.payload.headers.forEach(header => {
				if (header.name.toLowerCase() === 'from') {
					from = header.value;
				}else if(header.name.toLowerCase() === 'to'){
					to = header.value;
				}else if(header.name.toLowerCase() === 'subject'){
					subject = header.value;
				}
				
			});

			let statusClass = '';
			if (msg.labelIds[0].toLowerCase() === "sent") {
				statusClass = `class="label label-sm label-info"`;
			}else{
				statusClass = `class="label label-sm label-warning"`;
			}

			
			let status = `<h4 ${statusClass}>${msg.labelIds[0]}</h4>`

			let header = `<div class='message-preview message-header'>
					<b>Header : </b><br/>
					<ul>
						<li>FROM : ${from}</li>
						<li>To : ${to}</li>
						<li>Subject : ${subject}</li>
						<li>Date/Time : ${datetime.toTimeString()}</li>
					</ul>
				</div>`;

			const parts = msg.payload.parts;

			let body = "";
			let attachment = [];
			parts.forEach(part => {
			
				if (part.mimeType !== "application/pdf") {
					body += part.parts[0].body.data;
				}else if (part.mimeType === "application/pdf") {
					attachment.push(part.filename);
				}
			});


			mail_show += `${status} ${header}`;


			decode(body, mail_show, attachment);

		});
	});
}

async function checkStatusList(){
	
	if(tokenBerlaku){
		const updateList = [];
		let dialog = bootbox.dialog({
			title: 'Checking Status',
			message: `<p>
				<ul id="checkStatusBootbox">
					<li id='checkStatusBootboxLoader'><i class="fa fa-spin fa-spinner"></i> Loading...</li>
				</ul>
			</p>`
		});
	
		let counter = 0;
		const fetchPromises = [];
	
		for (const list of checkList) {
			counter++;
			if(list.thread_id !== null){
				try {
					const fetchPromise = fetch(`https://gmail.googleapis.com/gmail/v1/users/relay.do.bdg@gmail.com/threads/${list.thread_id}?access_token=${tokenBerlaku}`)
					.then((response) => response.json())
					.then((data) => {
						
						const messages = data.messages;
						const status = messages[0].labelIds[0];
						updateList.push({
							id: list.id,
							label_id: status.toUpperCase()
						});
						dialog.find('.bootbox-body').find(`#checkStatusBootbox`).prepend(`<li>${list.nama} : ${status}</li>`);
					});
		
					fetchPromises.push(fetchPromise);
				} catch (error) {
					console.error('Error:', error);
					dialog.find('.bootbox-body').find(`#checkStatusBootbox`).prepend(`<li>${list.nama} : Error fetching status</li>`);
					continue;
					
				}
			}else{
				dialog.find('.bootbox-body').find(`#checkStatusBootbox`).prepend(`<li>${list.nama} : null</li>`);
			}
		}
	
		await Promise.all(fetchPromises);	
	
		if(counter == checkList.length){
			dialog.find('.bootbox-body').find(`#checkStatusBootboxLoader`).html(``);
			if(updateList.length > 0){
				const afRow = await updateKeteranganBatch(updateList);
				if(afRow == 0){
					dialog.find('.bootbox-body').append(`<i class="fas fa-spin fa-cog"></i> <h3>${afRow} Data sudah paling update </h3>refreshing page...`);
					dialog.modal('hide');
	
				}else{
					dialog.find('.bootbox-body').append(`<i class="fas fa-spin fa-cog"></i> <h3>${afRow} Status berhasil diupdate </h3>refreshing page...`);
					location.reload();
				}
			}
			
	
		}
	}
}

async function insertKeteranganBatch(insertList){
	try {
		const response = await fetch('<?=base_url()?>pajak/insert_email_send_keterangan_batch', {
			method: 'POST',
			headers: {
				'Content-Type': 'application/json'
			},
			body: JSON.stringify(insertList)
		});
		const result = await response.json();
		const afRow = result.affected_rows;
		return afRow;
	} catch (error) {
		alert(error);
	}
}

async function updateKeteranganBatch(updateList){
	try {
		const response = await fetch('<?=base_url()?>pajak/update_email_send_keterangan_batch', {
			method: 'POST',
			headers: {
				'Content-Type': 'application/json'
			},
			body: JSON.stringify(updateList)
		});
		const result = await response.json();
		const afRow = result.affected_rows;
		return afRow;
	} catch (error) {
		alert(error);
	}
}

const decode = async(data, mail_show, attachment) =>{


	const div = document.querySelector("#email_preview");

	const response = await fetch(baseurl+`pajak/decode64Base`, {
		method: 'POST',
		body: `data=${data}`, // string or object
		headers: {
			'Content-Type': 'application/x-www-form-urlencoded'
		},
	});

	
	const res = await response.json();
	// let text = atob(res);
	// console.log(text)
	let attach_show = "";
	attachment.forEach((attach,index) => {
		attach_show += `<li>${index + 1}. ${attach}</li>`;
	});
	mail_show += `<div class='message-preview message-body'>
			<b>Message : </b><br/>
			${res}
		</div>
		<div class='message-preview message-attachment'>
			<b>Attachment [<b>${attachment.length} files</b>] : </b><br/>
			<ul>
				${attach_show}
			</ul>
		</div>
		`;
	
	div.innerHTML = mail_show;
	document.querySelector('#email-preview-loading').style.display= "none";
	

}


</script>
