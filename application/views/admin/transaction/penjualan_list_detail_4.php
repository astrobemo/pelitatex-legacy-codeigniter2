<link href="<?=base_url('assets/global/plugins/bootstrap-modal/css/bootstrap-modal.css');?>" rel="stylesheet" type="text/css"/>

<!-- <link href="<?=base_url('assets_noondev/css/bootstrap-modal-bs3patch.css');?>" rel="stylesheet" type="text/css"/> -->
<?php echo link_tag('assets/global/plugins/bootstrap-datepicker/css/datepicker3.css'); ?>

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

#stok-info, #stok-info-edit{
	font-size: 1.5em;
	position: absolute;
	right: 50px;
	top: 30px;
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
	font-size: 1.1em;
}

.textarea{
	resize:none;
}

#bayar-data tr td:nth-child(even){
	font-weight: bold;
	padding: 0 0px 0 10px;
	text-align: right;
}

#bayar-data tr td input{
	padding: 0 5px 0 5px;
	border: 1px solid #ddd;
}

#bayar-data tr td{
	font-size: 1.2em;
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

#jatuh-tempo-list tr td, #jatuh-tempo-list-edit tr td{
	padding: 2px 5px;
}

#jatuh-tempo-rekap{
	display: none;
}

.print-option-judul{
	margin-top: 10px;
	text-align: center;
}

.print-option-judul:before {
  content: "";
  display: block;
  border-top: solid 2px #bebebe;
  width: 90%;
  height: 2px;
  position: absolute;
  top: 50%;
  z-index: 0;
}

.print-option-judul span{
	background: #fff;
	padding: 0 10px;
	position: relative;
}

.selected-print-green{
	font-size:1.3em;
	background-color:lime;
	padding:0 10px
}

.selected-print-blue{
	background-color:lightblue;
	padding-right:10px;
}

#general_table{
	font-size: 1.1em;
}

#qty-kirim-tbl{
	margin: auto;
}

#qty-kirim-tbl tr td, #qty-kirim-tbl tr th{
	border: 1px solid #ddd;
	/*border-bottom: 1px solid #ddd;*/
	padding: 0px 5px;
	text-align: center;
}

#overlay-kirim{
	display:none;
	position:absolute; 
	left:0px; 
	top:0px; 
	height:100%; 
	width:100%;
	background: rgba(0,0,0,0.4);
}

#overlay-kirim-content{
	padding: 10px;
	margin: auto;
	height: auto;
	width: auto;
	text-align: center;
	display:table-cell; 
	vertical-align:middle
}

#overlay-kirim-content div{
	background: #fff;
	overflow: auto;
	margin: auto;
	padding: 20px;
	text-align: right;
}

#surat-jalan-tbl{
	font-size: 1.1em;
}

.baris-data{
	cursor: pointer;
}

.baris-data:hover{
	background-color: rgba(200,0,0,0.2) !important;
}

.baris-data:hover:after{
	content:'click edit/hapus data';
	font-size: 0.8em;
	padding-left: 10px;
	position: absolute;
	font-weight: bold;
}

.qtyList{
	display: inline-block;
	width: 30px;
}

.qtyAmbil{
	display: inline-block;
	margin-right: 10px;
	cursor: pointer;
}

.qtySudahAmbil{
	display: inline-block;
	margin-right: 10px;
	cursor: no-drop;
	background: #ffdede;
}

.qtySudahKirim{
	display: inline-block;
	margin-right: 10px;
	cursor: no-drop;
	background: #e1ffde;
}

.qtyAmbilBarisAll{
	cursor: pointer;
}

.qtyAmbilSelected{
	background-color:#e6e9ff; 
	font-weight: bold;
}

.qtyAmbilSelected:after{
	content: '\2713';
	position:absolute;
	font-size: 0.8em
}

.verified:after{
	content: '\2713';
	position:absolute;
	font-size: 0.8em
}

.card{
	border:1px solid #ccc;
	display: table;
	width: 100%;
}

.card-panel-left, .card-panel-right{
	display: table-cell;
	vertical-align: middle;
	height: 100%;
	padding: 10px;
}

.card-panel-left{
	width: 40%;
}

.card-panel-right{
	font-size: 2em;
	text-align: center;
}

.card-title{
	font-weight: bold;
	font-size: 1.2em;
}

.qty-rekap-detail{
	padding: 0px 5px 0 0 ;
	font-size: 1.1em;
}

#overlay-div{
	z-index: 40000;
}

#rekapTable{
	width: 95%;
	margin:auto;
}

#rekapTable tr td, #rekapTable tr th{
	padding: 5px 10px;
	vertical-align: bottom;
	border: 1px solid #ddd;
}

	
</style>

<div class="page-content">
	<div class='container'>
		<?
			$status_aktif  ='';
			$penjualan_id = '';
			$customer_id = '';
			$nama_customer = '';
			$gudang_id = '';
			$nama_gudang = '';
			$no_faktur = '';
			$tanggal = date('d/m/Y');
			$tanggal_print = '';
			$ori_tanggal = '';
			$po_number = '';
			$note_info = ''; 

			$jatuh_tempo = date('d/m/Y', strtotime("+60 days") );
			$ori_jatuh_tempo = '';
			$status = -99;

			$diskon = 0;
			$ongkos_kirim = 0;
			$nama_keterangan = '';
			$alamat_keterangan = '';
			$kota = '';
			$provinsi = '';
			$keterangan = '';
			$penjualan_type_id = 3;
			$tipe_penjualan = '';
			$customer_id = '';
			$no_faktur_lengkap = '';
			$no_surat_jalan = '';
			$fp_status = 1;
			$ppn_status = 1;
			$status_ambil = 'Diambil Semua';

			$closed_date = '';
			$nik = '';
			$npwp = '';

			$g_total = 0;
			$g_qty_total = 0;
			$readonly = '';
			$disabled = '';
			$alamat_customer = '';
			$disabled_status = '';
			$bg_info = '';
			$username = '';
			$username_close = ''; 


			$hidden_spv = 'hidden';
			if (is_posisi_id() != 1) {
				$hidden_spv = 'hidden';
			}

			foreach ($penjualan_data as $row) {
				$tipe_penjualan = $row->tipe_penjualan;
				$penjualan_id = $row->id;
				$customer_id = $row->customer_id;
				$nama_customer = $row->nama_keterangan;
				$alamat_filter_0 = str_replace('BLOK - ', '', $row->alamat_keterangan);
				$alamat_filter_0_1 = str_replace('No.- ', '', $alamat_filter_0);
				$alamat_filter_1 = str_replace('RT:000 RW:000 ', '', $alamat_filter_0_1);
				$alamat_filter_2 = str_replace('Kel.-', '', $alamat_filter_1);
				$alamat_filter_3 = str_replace('Kel.', ',', $alamat_filter_2);
				// $alamat_filter_4 = str_replace('Kec.', ',', $alamat_filter_3);
				$alamat_final = $alamat_filter_3;
				$alamat_customer = $alamat_final;
				// $gudang_id = $row->gudang_id;
				// $nama_gudang = $row->nama_gudang;
				$no_faktur = $row->no_faktur;
				$penjualan_type_id = $row->penjualan_type_id; 
				$po_number = $row->po_number;
				$fp_status = $row->fp_status;
				$username = $row->username;
				$username_close = $row->username_close;
				// $ppn_status = $row->ppn_status;
				$iClass = '';
				
				$tanggal_print = date('d F Y', strtotime($row->tanggal));

				$tanggal = is_reverse_date($row->tanggal);
				$ori_tanggal = $row->tanggal;
				$status_cek = 0;

				if ($penjualan_type_id == 1) {
					$note_info = "note note-info";
					$bg_info = "background: #95a5a6";

				}elseif ($penjualan_type_id == 2) {
					$note_info = "note note-warning";
					$bg_info = "background: #d35400";
				}elseif ($penjualan_type_id == 3) {
					$note_info = "note note-success";
					$bg_info = "background: #2980b9";
				}


				if ($penjualan_type_id == 2) {
					$dt = strtotime(' +'.get_jatuh_tempo($customer_id).' days', strtotime($row->tanggal) );
					if ($row->jatuh_tempo == $row->tanggal) {
						$status_cek = 1;
					}
				}
				$get_jt = ($row->jatuh_tempo == '' || $status_cek == 1  ? date('Y-m-d',$dt) : $row->jatuh_tempo);
				// print_r($get_jt);
				$jatuh_tempo = is_reverse_date($get_jt);
				$ori_jatuh_tempo = $row->jatuh_tempo;
				$status = $row->status;
				
				$diskon = $row->diskon;
				$ongkos_kirim = $row->ongkos_kirim;
				$status_aktif = $row->status_aktif;
				$nama_keterangan = $row->nama_keterangan;
				$alamat_keterangan = $alamat_customer;
				$kota = $row->kota;
				// $provinsi = $row->provinsi;
				$keterangan = $row->keterangan;
				$customer_id = $row->customer_id;
				$no_faktur_lengkap = $row->no_faktur_lengkap;
				$no_surat_jalan = $row->no_surat_jalan;
				$closed_date = $row->closed_date;

				$status_ambil = $row->status_ambil;


				if ($status_aktif == -1 ) {
					$note_info = 'note note-danger';
				}



			}

			foreach ($customer_data as $row) {
				$nik = $row->nik;
				$npwp = $row->npwp;
			}

			$nama_bank = '';
			$no_rek_bank = '';
			$tanggal_giro = '';
			$jatuh_tempo_giro = '';
			$no_akun = '';

			if ($status != 1) {
				if ( is_posisi_id() != 1 ) {
					$readonly = 'readonly';
				}
			}

			if ($penjualan_id == '') {
				$disabled = 'disabled';
			}

			if ($status != 0) {
				$disabled_status = 'disabled';
			}

			$lock_ = '';
			$read_ = '';
			if (is_posisi_id() == 6) {
				$disabled = 'disabled';
				$readonly = 'readonly';
			}
		?>

		<embed src="<?=base_url()?>transaction/penjualan_print?penjualan_id=<?=$penjualan_id?>&type=1" style='height:450px; width:600px' hidden></embed>
		<iframe id="print-pdf-static"  src="<?=base_url()?>transaction/penjualan_print?penjualan_id=<?=$penjualan_id?>&type=1" hidden></iframe>
		<iframe id="print-pdf-dynamic"  src="" hidden></iframe>

		<!-- <div class='row'>
			
		</div> -->

		<div class="modal fade" id="portlet-config-pinclose" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body">
						<form action="" class="form-horizontal" id="" method="post">
							<h3 class='block'> CLOSE FORM</h3>
							
							<div class="form-group">
			                    <label class="control-label col-md-3">PIN<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<input name='pin' type='password' id="pin-close" class="form-control">
			                    </div>
			                </div>	
		                </form>		                
					</div>

					<div class="modal-footer">
						<button type="button" class="btn blue" id='btn-pinclose' onclick='cekPinClose()'>OK</button>
						<button type="button" class="btn default" data-dismiss="modal">Close</button>
					</div>
				</div>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
		</div>

		<div class="modal fade bs-modal-lg" id="portlet-config-status" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-body">
						<!-- <div class='col-xs-3' style="border-right:1px solid #ddd">
							<div>
								<h3>Print & Close</h3>
									<div style="margin-bottom:10px">
										<label style='font-size:1.1em'>Pin :</label>
										<input type='password' name='pin-close' onchange="cekPinClose()" id="pin-close" class="form-control verified">
									</div>
									<div style="margin-bottom:10px">
										<label style='font-size:1.1em'>Print :</label>
										<div class="radio-list">
											<label>
											<input type="radio" class="radio-print" data-print="1" name="print_target" value="1"><span class='ket-print'> Faktur </span></label>
											<label>
											<input type="radio" class="radio-print" data-print="2" name="print_target" value="2"><span class='ket-print'> Detail </span></label>
											<label>
											<input type="radio" class="radio-print" data-print="3" name="print_target" value="3" <?=($print_now ? "checked" : '');?> ><span class="ket-print  <?=($print_now ? 'selected-print-blue' : '');?> "> Faktur + Detail <?=($print_now ? 'selected-print-blue' : '');?></span></label>
										</div>
									</div>
									<div>
										<hr/>
										<b>Di cek oleh : </b>
										<h3 id="closedBy"><span style='color:#ddd'>...</span></h3>
									</div>
							</div>
							
						</div> -->
						<div style="border-left:1px solid #ddd">
							<div>
								<h3>Status Barang</h3>
									<div class=''>
										<label style='font-size:1.1em'>Status :</label>
										<div class="radio-list">
											<label class='radio-inline'>
											<input name='statusAmbil' type='radio' value='Diambil Semua' class='radio-print'><span class='ket-print'> Diambil Semua</span></label>
											<label class='radio-inline'>
											<input name='statusAmbil' type='radio' value='Diambil Sebagian' class='radio-print' ><span class='ket-print'> Diambil Sebagian</span></label>
											<label class='radio-inline'>
											<input name='statusAmbil' type='radio' value='Dikirim Semua' class='radio-print' ><span class='ket-print'> Dikirim Semua</span></label>
											<label class='radio-inline'>
											<input name='statusAmbil' type='radio' value='Dikirim Sebagian' class='radio-print' ><span class='ket-print'> Dikirim Sebagian</span></label>
											<select name="alamat_id" class='' id='alamat_id' hidden>
					                    		<?if ($customer_id != '' && $customer_id != 0) {?>
					                    			<option value='0'>
														<?=$alamat_keterangan?>
					                    			</option>
					                    		<?}?>
				                				<?foreach ($customer_alamat_kirim as $row) {
				                					if (trim($row->alamat) != '') {?>
														<option value="<?=$row->id;?>"><?=trim($row->alamat) ?></option>
				                					<?}?>
												<?}?>
					                    	</select>
										</div>
										<div class='col-xs-12'>
											<div id='pengambilanSebagianSection' style='border-top:1px solid #ddd; margin-top:10px; padding-top:5px;' hidden>
												<ul id="diambilSebagianList">
													<li id="barisAmbilBaru"><i class='fa fa-plus'></i> : 
														Jam : <input id="waktuPengambilan" disabled> | oleh : <input id="namaPengambil">
													</li>
												</ul>
												<ul id="kirimList">
													<li id="barisKirimBaru"><i class='fa fa-plus'></i> : 
														Jam : <input id="waktuPengiriman" disabled> | via : <input id="pengirimanVia"><br/>
														Alamat : <select name="alamat_id" id='alamat-kirim-id'>
								                    		<?if ($customer_id != '' && $customer_id != 0) {?>
								                    			<option value="<?=$alamat_keterangan?>">
																	<?=$alamat_keterangan?>
								                    			</option>
								                    		<?}?>
							                				<?foreach ($customer_alamat_kirim as $row) {
							                					if (trim($row->alamat) != '') {?>
																	<option value="<?=trim($row->alamat) ?>"><?=trim($row->alamat) ?></option>
							                					<?}?>
															<?}?>
								                    	</select>
													</li>
												</ul>
												<table class='table' id="pengambilanSebagian">
													<thead>
														<tr style="border-top:1px solid #ddd">
															<th>Pengambilan Sebagian</th>
															<th>Roll</th>
															<th>Qty</th>
															<th></th>
														</tr>
													</thead>
													<tbody></tbody>
												</table>
											</div>
										</div>
									</div> 
								
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<!-- <button type="button" class="btn blue btn-active btn-save-tab" title='Save & Buka di Tab Baru'>Save & New Tab</button> -->
						<button type="button" class="btn blue btn-active btn-trigger" id='summaryAmbil' onclick="summaryPengambilan('')">SAVE AMBIL</button>
						<button type="button" class="btn green btn-active btn-trigger" id='summaryKirim' onclick="summaryPengiriman()" style='display:none'>SAVE KIRIM</button>
						<button type="button" class="btn btn-active default" data-dismiss="modal">Cancel</button>
					</div>
				</div>
			</div>
		</div>

		<div class="modal fade bs-modal-lg" id="portlet-config-close" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-body">
						<div class='row' style='margin-bottom:20px'>
							<div class='col-xs-12'>
								<div class='col-xs-6'>
									<?=$tanggal?><br/>
									KEPADA YTH,<br/>
									<b><?=$nama_customer;?></b><br/>
									<?=$alamat_customer;?>
									<?=$kota?>
								</div>
								<div class='col-xs-6 text-right'>
									<h3><b style="background:#ddd;padding:0px 10px"><?=$status_ambil?> <a href="#portlet-config-status" data-toggle="modal" style='font-size:0.5em'><u>edit</u></b></a></h3>
								</div>
							</div>
						</div>
						<div class='row row-no-gutters' id="rekapBarang">
							<table id="rekapTable"></table>
						</div>

						<div style='margin-top:30px;'>
							<div id="btn-print-all"></div>
							<hr/>
							<?/*<button href="#portlet-config-pinclose" data-toggle="modal"  class="btn btn-default btn-active " onclick="printPDF('transaction/penjualan_print?penjualan_id=<?=$penjualan_id?>&type=1')" id='print-faktur'>Print Faktur</button>
							<button href="#portlet-config-pinclose" data-toggle="modal"  class="btn btn-default btn-active " onclick="printPDF('transaction/penjualan_print?penjualan_id=<?=$penjualan_id?>&type=2')" id='print-packing-list'>Print Detail</button>
							<button href="#portlet-config-pinclose" data-toggle="modal"  class="btn btn-default btn-active " onclick="printPDF('transaction/penjualan_print?penjualan_id=<?=$penjualan_id?>&type=3')" id='print-faktur-detail'>Print Faktur + Detail</button>
							*/?>
							<button <?//=$print_disable;?> href="#portlet-config-pinclose" data-toggle="modal" type="button" tabindex='-1' onclick="printRAW('1')" class="btn btn-lg blue btn-print btn-faktur-print">FAKTUR</button>
                            <button <?//=$print_disable;?> href="#portlet-config-pinclose" data-toggle="modal" type="button" tabindex='-1' onclick="printRAW('2')" class="btn btn-lg blue btn-print btn-print-detail">DETAIL</button>
                            <button <?//=$print_disable;?> href="#portlet-config-pinclose" data-toggle="modal" type="button" tabindex='-1' onclick="printRAW('3')" class="btn btn-lg blue btn-print btn-print-kombi">FAKTUR + DETAIL</button>
                            <button <?//=$print_disable;?> href="#portlet-config-pinclose" data-toggle="modal" type="button" tabindex='-1' onclick="printRAW('4')" class="btn btn-lg green btn-print btn-surat-jalan">SURAT JALAN</button>
                            <button <?//=$print_disable;?> href="#portlet-config-pinclose" data-toggle="modal" type="button" tabindex='-1' onclick="printRAW('5')" class="btn btn-lg green btn-print btn-surat-jalan-noharga">SJ NO HARGA</button>
				            	
						</div>
					</div>
					<div class="modal-footer">
						<!-- id="btn-close-penjualan" -->
						<!-- <button type="button" class="btn blue btn-active btn-save-tab" title='Save & Buka di Tab Baru'>Save & New Tab</button> -->
						<!-- <button type="button" class="btn blue btn-active btn-trigger" >OK</button> -->
						<button type="button" class="btn btn-active default" data-dismiss="modal">Close</button>
					</div>
				</div>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
		</div>

		<div class="modal fade bs-modal-lg" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-body">
						<form action="<?=base_url('transaction/penjualan_list_insert')?>" class="form-horizontal" id="form_add_data" method="post">
							<h3 class='block'> Penjualan Baru</h3>
							
							<div class="form-group">
			                    <label class="control-label col-md-3">Type<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-8">
			                    	<select class='form-control input1 field-new' name='penjualan_type_id' id="penjualan-type-select">
			                    		<?foreach ($penjualan_type as $row) { ?>
			                    			<option <?if ($row->id == 3) {echo 'selected';}?> value='<?=$row->id;?>'><?=$row->text;?></option>
			                    		<?}?>
			                    	</select>
			                    </div>
			                </div>			                

			                <div class="form-group">
			                    <label class="control-label col-md-3">Tanggal<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-8">
	                    			<input name='tanggal' readonly class='form-control date-picker field-new' value="<?=date('d/m/Y')?>" >
			                    </div>
			                </div> 
			                <div class="form-group" hidden>
			                    <label class="control-label col-md-3">Jatuh Tempo<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-8">
	                    			<input name='jatuh_tempo' class='form-control date-picker' value="<?=date('d/m/Y')?>" >
			                    </div>
			                </div> 

			                <div class="form-group">
			                	<!-- po_section -->
			                    <label class="control-label col-md-3">PO/Ket
			                    </label>
			                    <div class="col-md-8">
	                    			<input name='po_number' maxlength='38' class='form-control field-new'>
			                    </div>
			                </div> 

			                <div class="form-group customer_section">
			                    <label class="control-label col-md-3">Customer<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-8">
			                    	<div id='add-select-customer'  hidden>
			                    		<select name="customer_id" class='form-control field-new' id='customer_id_select'>
			                				<option value=''>Pilih</option>
			                				<?foreach ($this->customer_list_aktif as $row) { 
			                					if ($row->status_aktif == 1) {?>
					                    			<option value="<?=$row->id?>"><?=$row->nama;?><?=($row->tipe_company != '' ? ", ".$row->tipe_company : "")?> - <?=$row->alamat;?></option>
			                					<?}
				                    		} ?>
				                    	</select>
				                    	<select id='customer_id_copy' hidden>
			                				<option value=''>Pilih</option>
			                				<?foreach ($this->customer_list_aktif as $row) { 
			                					if ($row->status_aktif == 1) {?>
					                    			<option value="<?=$row->id?>"><?=$row->alamat.
					                    				($row->blok !='' && $row->blok != '-' ? ' BLOK.'.$row->no: '').
					                    				($row->no !='' && $row->no != '-' ? ' NO.'.$row->no: '').
					                    				" RT.".$row->rt.' RW.'.$row->rw.
					                    				($row->kelurahan !='' && $row->kelurahan != '-' ? ' Kel.'.$row->kelurahan: '').
					                    				($row->kecamatan !='' && $row->kecamatan != '-' ? ' Kec.'.$row->kecamatan: '').
					                    				($row->kota !='' && $row->kota != '-' ? ' '.$row->kota: '').
					                    				($row->provinsi !='' && $row->provinsi != '-' ? ' '.$row->provinsi: '')
					                    				;?></option>
			                					<?}
				                    		} ?>
				                    	</select>
			                    	</div>
			                    	<div id='add-nama-keterangan'>
				                    	<input name='nama_keterangan' class='form-control field-new'>
			                    	</div>
			                    </div>
			                </div>

			                <div class="form-group add-alamat-keterangan">
			                    <label class="control-label col-md-3">Alamat
			                    </label>
			                    <div class="col-md-8">
			                    	<input name='alamat_keterangan' class='form-control field-new' id="add-alamat-keterangan">
			                    </div>
			                </div>

			                <div class="form-group">
			                    <label class="control-label col-md-3">FP Status
			                    </label>
			                    <div class="col-md-8">
									<div class="checkbox-list">
				                    	<label>
				                    	<input readonly type='checkbox' name='fp_status' id='fp_status_add' value='1'>Ya</label>
				                    </div>
			                    </div>
			                </div>

			                <!-- <div class="form-group">
			                    <label class="control-label col-md-3">PPN
			                    </label>
			                    <div class="col-md-8">
									<div class="checkbox-list">
				                    	<label>
				                    	<input type='checkbox' name='ppn_status' id='ppn_status_add' value='0'>Tidak Dipungut</label>
				                    </div>
				                   	<small>Khusus untuk kawasan berikat</small>
			                    </div>
			                </div> -->
			                
						</form>
					</div>

					<div class="modal-footer">
						<!-- <button type="button" class="btn blue btn-active btn-save-tab" title='Save & Buka di Tab Baru'>Save & New Tab</button> -->
						<button type="button" class="btn blue btn-active btn-trigger btn-save field-new" title='Save & Buka di Tab Ini'>Save</button>
						<button type="button" class="btn btn-active default" data-dismiss="modal">Close</button>
					</div>
				</div>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
		</div>

		<div class="modal fade bs-modal-lg" id="portlet-config-edit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-body">
						<form action="<?=base_url('transaction/penjualan_list_update')?>" class="form-horizontal" id="form_edit_data" method="post">
							<h3 class='block'> Penjualan Edit</h3>
							
							<div class="form-group">
			                    <label class="control-label col-md-3">Type<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-8">
			                    	<input name='id' value='<?=$penjualan_id;?>' hidden>
			                    	<select class='form-control input1' name='penjualan_type_id' id="penjualan-type-select-edit">
			                    		<?foreach ($penjualan_type as $row) { ?>
			                    			<option <?if ($penjualan_type_id == $row->id) {echo 'selected';}?> value='<?=$row->id;?>'><?=$row->text;?></option>
			                    		<?}?>
			                    	</select>
			                    </div>
			                </div>			                

			                <div class="form-group">
			                    <label class="control-label col-md-3">Tanggal<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-8">
	                    			<input name='tanggal' class='form-control date-picker' value="<?=$tanggal;?>" >
			                    </div>
			                </div> 

			                <div class="form-group"  <?=($penjualan_type_id != 2 ? 'hidden' : '' )?> >
			                    <label class="control-label col-md-3">Jatuh Tempo<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-8">
	                    			<input name='jatuh_tempo' class='form-control date-picker' value="<?=$jatuh_tempo;?>" >
			                    </div>
			                </div> 

			                <div class="form-group">
			                	<!-- po_section -->
			                    <label class="control-label col-md-3">PO/Ket
			                    </label>
			                    <div class="col-md-8">
	                    			<input name='po_number' maxlength='38' class='form-control' value='<?=$po_number?>'>
			                    </div>
			                </div> 

			                <div class="form-group customer_section">
			                    <label class="control-label col-md-3">Customer<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-8">
			                    	<div id='edit-select-customer'  <?if ($penjualan_type_id == 3) { ?> hidden <?}?> >
			                    		<select name="customer_id" class='form-control' id='customer_id_select_edit'>
			                				<option value=''>Pilih</option>
			                				<?foreach ($this->customer_list_aktif as $row) { 
			                					if ($row->status_aktif == 1) {?>
					                    			<option <?if ($customer_id == $row->id) {?>selected<?}?> value="<?=$row->id?>"><?=$row->nama;?><?=($row->tipe_company != '' ? ", ".$row->tipe_company : "")?> - <?=$row->alamat;?></option>
					                    		<? } ?>
				                    		<? } ?>
				                    	</select>

				                    	<select id='customer_id_copy_edit' hidden>
			                				<option value=''>Pilih</option>
			                				<?foreach ($this->customer_list_aktif as $row) { 
			                					if ($row->status_aktif == 1) {?>
					                    			<option value="<?=$row->id?>"><?=$row->alamat.
					                    				($row->blok !='' && $row->blok != '-' ? ' BLOK.'.$row->no: '').
					                    				($row->no !='' && $row->no != '-' ? ' NO.'.$row->no: '').
					                    				" RT.".$row->rt.' RW.'.$row->rw.
					                    				($row->kelurahan !='' && $row->kelurahan != '-' ? ' Kel.'.$row->kelurahan: '').
					                    				($row->kecamatan !='' && $row->kecamatan != '-' ? ' Kec.'.$row->kecamatan: '').
					                    				($row->kota !='' && $row->kota != '-' ? ' '.$row->kota: '').
					                    				($row->provinsi !='' && $row->provinsi != '-' ? ' '.$row->provinsi: '')
					                    				;?></option>
			                					<?}
				                    		} ?>
				                    	</select>
			                    	</div>
			                    	<div id='edit-nama-keterangan' <?if ($penjualan_type_id != 3) { ?> hidden <?}?> >
				                    	<input name='nama_keterangan' class='form-control' value="<?=$nama_keterangan;?>">
			                    	</div>
			                    </div>
			                </div> 

			                <div class="form-group edit-alamat-keterangan">
			                    <label class="control-label col-md-3">Alamat
			                    </label>
			                    <div class="col-md-8">
			                    	<input name='alamat_keterangan' maxlength='90' class='form-control' id="edit-alamat-keterangan" value="<?=$alamat_keterangan;?>">
			                    	<!-- <div>
				                    	<input name='alamat_keterangan' class='form-control' value="">
			                    	</div> -->
			                    </div>
			                </div>

			                <div class="form-group">
			                    <label class="control-label col-md-3">FP Status
			                    </label>
			                    <div class="col-md-8">
									<div class="checkbox-list">
				                    	<label>
				                    	<input readonly type='checkbox' <?=($fp_status == 1 ? 'checked' : '');?> name='fp_status' id='fp_status_edit' value='1'>Ya</label>
				                    </div>
			                    </div>
			                </div>

			                <!-- <div class="form-group">
			                    <label class="control-label col-md-3">PPN
			                    </label>
			                    <div class="col-md-8">
									<div class="checkbox-list">
				                    	<label>
				                    	<input type='checkbox' <?=($ppn_status == 0 ? 'checked' : '');?> name='ppn_status' id='ppn_status_edit' value='0'>Tidak Dipungut</label>
				                    </div>
			                    	<small>Khusus untuk kawasan berikat</small>
			                    </div>
			                </div> -->
			                
						</form>
					</div>

					<div class="modal-footer">
						<button type="button" class="btn blue btn-active btn-trigger btn-edit-save">Save</button>
						<button type="button" class="btn btn-active default" data-dismiss="modal">Close</button>
					</div>
				</div>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
		</div>
		
		<div class="modal fade bs-modal-lg" id="portlet-config-detail" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-body">
						<form action="<?=base_url('transaction/penjualan_list_detail_insert')?>" class="form-horizontal" id="form_add_barang" method="post">
							<h3 class='block'> Tambah Barang</h3>
							
							<div class="form-group">
			                    <label class="control-label col-md-3">Gudang<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<input name='penjualan_id' value='<?=$penjualan_id;?>' hidden>
			                    	<select name="gudang_id" id="gudangId" class='form-control' id='gudang_id_select'>
		                				<?foreach ($this->gudang_list_aktif as $row) { ?>
			                    			<option value="<?=$row->id?>"><?=$row->nama;?></option>
			                    		<? } ?>
			                    	</select>
			                    </div>
			                </div> 

							<div class="form-group">
			                    <label class="control-label col-md-3">Kode Barang<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<select name="barang_id" class='form-control input1' id='barang_id_select'>
		                				<option value=''>Pilih</option>
		                				<?foreach ($this->barang_list_aktif as $row) { 
		                					if ($row->status_aktif == 1) {?>
				                    			<option value="<?=$row->id?>"><?=$row->nama_jual;?> <?//=(is_posisi_id() == 1 ? "($row->id)" : '');?></option>
		                					<?}?>
			                    		<? } ?>
			                    	</select>
			                    	<select id="barang-id-copy" hidden>
			                    		<?foreach ($this->barang_list_aktif as $row) { ?>
			                    			<option value="<?=$row->id?>"><?=$row->nama_satuan;?>??<?=$row->harga_jual;?>??<?=$row->jenis_barang?>??<?=$row->satuan_id?></option>
			                    		<? } ?>
			                    	</select>
			                    </div>
			                </div>			                

			                <div class="form-group">
			                    <label class="control-label col-md-3">Warna<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
	                    			<select name="warna_id" class='form-control' id='warna_id_select'>
		                				<option value=''>Pilihan..</option>
			                    		<?foreach ($this->warna_list_aktif as $row) { ?>
			                    			<option value="<?=$row->id?>"><?=$row->warna_beli;?></option>
			                    		<? } ?>
			                    	</select>
			                    </div>
			                </div> 

			                <div class="form-group">
			                    <label class="control-label col-md-3">Satuan<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
		                			<input readonly type="text" class='form-control' name="satuan"/>
			                    </div>
			                </div> 

			                <div class="form-group">
			                    <label class="control-label col-md-3">Harga Jual<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<div class='input-group'>
		                    		<input type="text" class='amount_number form-control' id='harga_jual_add' name="harga_jual"/>
		                    		<?/*if ($ppn_status == 0 ) {?>
			                    		<input type="text" class='amount_number form-control' id='harga_jual_add_no_ppn' name="harga_jual_no_ppn"/>
		                    			<small>Non-PPN</small>
		                    		<?}*/?>
		                			<span class="input-group-btn" >
										<a data-toggle="popover" class='btn btn-md default btn-cek-harga' data-trigger='click' title="History Pembelian Customer" data-html="true" data-content="<div id='data-harga'>loading...</div>"><i class='fa fa-search'></i></a>
									</span>
			                    	<input name='rekap_qty' id='rekapQty' hidden>
			                    	</div>
			                    </div>
			                </div> 
						</form>
					</div>

					<div class="modal-footer">
						<button type="button" class="btn blue btn-add-qty">Add Qty</button>
						<button type="button" class="btn default" data-dismiss="modal">Close</button>
					</div>
				</div>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
		</div>

		<div class="modal fade" id="portlet-config-qty" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog ">
				<div class="modal-content">
					<div class="modal-body">
						<table id='qty-table'>
							<tr>
								<th>Yard</td>
								<th>Roll</td>
								<th></th>
							</tr>
							<tr>
								<td><input name='qty' class='input1'></td>
								<td><input name='jumlah_roll'></td>
								<td><button tabindex='-1' class='btn btn-xs blue btn-add-qty-row'><i class='fa fa-plus'></i></button></td>
							</tr>
							<tr>
								<td><input name='qty'></td>
								<td><input name='jumlah_roll'></td>
								<td></td>
							</tr>
							<tr>
								<td><input name='qty'></td>
								<td><input name='jumlah_roll'></td>
								<td></td>
							</tr>
							<tr>
								<td><input name='qty'></td>
								<td><input name='jumlah_roll'></td>
								<td></td>
							</tr>
							<tr>
								<td><input name='qty'></td>
								<td><input name='jumlah_roll'></td>
								<td></td>
							</tr>
							<tr>
								<td><input name='qty'></td>
								<td><input name='jumlah_roll'></td>
								<td></td>
							</tr>
							<tr>
								<td><input name='qty'></td>
								<td><input name='jumlah_roll'></td>
								<td></td>
							</tr>
							<tr>
								<td><input name='qty'></td>
								<td><input name='jumlah_roll'></td>
								<td></td>
							</tr>
							<tr>
								<td><input name='qty'></td>
								<td><input name='jumlah_roll'></td>
								<td></td>
							</tr>
							<tr>
								<td><input name='qty'></td>
								<td><input name='jumlah_roll'></td>
								<td></td>
							</tr>
						</table>
						<div class='yard-info'>
							TOTAL QTY: <span class='yard_total' >0</span> <span id='rekap-qty-satuan'>yard</span> <br/>
							TOTAL ROLL: <span class='jumlah_roll_total' >0</span> 
						</div>

						<div id='stok-info' style='border:1px solid #ddd; padding:5px; margin-bottom:20px;'>
							STOK QTY : <span class='stok-qty'>0</span><br/>
							STOK ROLL : <span class='stok-roll'>0</span>
						</div>

					</div>

					<div class="modal-footer">
						<button disabled type="button" onclick="addBarangDetail()" class="btn blue btn-active btn-trigger btn-brg-save">Save</button>
						<button type="button" class="btn btn-active default" data-dismiss="modal">Close</button>
					</div>
				</div>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
		</div>

		<div class="modal fade bs-modal-lg" id="portlet-config-detail-edit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-body">
						<form action="<?=base_url('transaction/penjualan_list_detail_update')?>" class="form-horizontal" id="form_edit_barang" method="post">
							<h3 class='block'> Edit Barang</h3>
							
							<div class="form-group">
			                    <label class="control-label col-md-3">Gudang<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<input name='penjualan_id' value='<?=$penjualan_id;?>' <?=$hidden_spv;?> >
			                    	<input name='penjualan_detail_id' id='penjualan_detail_id' <?=$hidden_spv;?> >
			                    	<input name='index' <?=$hidden_spv;?> >
			                    	<input name='tanggal' value='<?=$tanggal;?>' hidden>
	                    			<select name="gudang_id" class='form-control' id='gudangIdEdit'>
		                				<?foreach ($this->gudang_list_aktif as $row) { ?>
			                    			<option value="<?=$row->id?>"><?=$row->nama;?></option>
			                    		<? } ?>
			                    	</select>
			                    </div>
			                </div> 

							<div class="form-group">
			                    <label class="control-label col-md-3">Kode Barang<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<select name="barang_id" class='form-control input1' id='barang_id_select_edit'>
		                				<option value=''>Pilih</option>
		                				<?foreach ($this->barang_list_aktif as $row) { ?>
			                    			<option value="<?=$row->id?>"><?=$row->nama_jual;?></option>
			                    		<? } ?>
			                    	</select>
			                    	
			                    </div>
			                </div>			                

			                <div class="form-group">
			                    <label class="control-label col-md-3">Warna<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
	                    			<select name="warna_id" class='form-control' id='warna_id_select_edit'>
		                				<option value=''>Pilihan..</option>
			                    		<?foreach ($this->warna_list_aktif as $row) { ?>
			                    			<option value="<?=$row->id?>"><?=$row->warna_beli;?></option>
			                    		<? } ?>
			                    	</select>
			                    </div>
			                </div> 

			                <div class="form-group">
			                    <label class="control-label col-md-3">Satuan<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
		                			<input readonly type="text" class='form-control' name="satuan"/>
			                    </div>
			                </div> 

			                <div class="form-group">
			                    <label class="control-label col-md-3">Harga Jual<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<div class='input-group'>
			                    		<input type="text" class='amount_number form-control' id='harga_jual_edit' name="harga_jual"/>
			                			<span class="input-group-btn" >
											<a data-toggle="popover" class='btn btn-md default btn-cek-harga' data-trigger='click' title="History Pembelian Customer" data-html="true" data-content="<div id='data-harga'>loading...</div>"><i class='fa fa-search'></i></a>
										</span>
				                    	<input name='rekap_qty' hidden>

			                    	</div>
			                    </div>
			                </div> 
							<input id='rekapQtyEdit' name='rekap_qty' <?=(is_posisi_id() != 1 ? 'hidden' : '' ); ?> >

						</form>
					</div>

					<div class="modal-footer">
						<a onClick="removeItem()" class="btn red" style='float:left'><i class='fa fa-times'></i> Remove</a>

						<a href="#portlet-config-qty-edit" data-toggle="modal" class="btn blue btn-qty-edit">Edit Qty</a>
						<button type="button" class="btn default" data-dismiss="modal">Close</button>
					</div>
				</div>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
		</div>

		<div class="modal fade" id="portlet-config-qty-edit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body">
						<div style='height:400px;overflow:auto'>
							<table id='qty-table-edit'>
								<thead>
									<tr>
										<th>No</th>
										<th>Yard</td>
										<th>Roll</td>
										<th></th>
									</tr>
								</thead>
								<tbody>

								</tbody>
							</table>
						</div>
						<span class='total_roll' hidden></span>
						<div class='yard-info'>
							TOTAL : <span class='yard_total' >0</span> yard <br/>
							TOTAL ROLL : <span class='jumlah_roll_total' >0</span>
						</div> 
						<div id='stok-info-edit' style='border:1px solid #ddd; padding:5px; margin-bottom:20px;'>
							STOK QTY : <span class='stok-qty'>0</span><br/>
							STOK ROLL : <span class='stok-roll'>0</span>
						</div>
						<!-- <form hidden action="<?=base_url()?>transaction/penjualan_qty_update" id='form-qty-update' method="post">
							<input name='id'>
							<input name='rekap_qty'>
						</form> -->
					</div>

					<div class="modal-footer">
						<button type="button" id="btnBarangUpdate" onclick="updateBarangDetail()"  class="btn btn-active blue btn-brg-edit-save">Save</button>
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
						<form action="" class="form-horizontal" id="form_search_faktur" method="get">
							<h3 class='block'> Cari Faktur</h3>
							
							<div class="form-group">
			                    <label class="control-label col-md-3">No Faktur<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
									<input type="hidden" name='id' id="search_no_faktur" class="form-control select2">
			                    </div>
			                </div>	
		                </form>                
					</div>

					<div class="modal-footer">
						<button type="button" class="btn blue btn-search-faktur">GO!</button>
						<button type="button" class="btn default" data-dismiss="modal">Close</button>
					</div>
				</div>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
		</div>

		<div class="modal fade" id="portlet-config-pin" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body">
						<form action="<?=base_url('transaction/penjualan_request_open');?>" class="form-horizontal" id="form-request-open" method="post">
							<h3 class='block'> Request Open</h3>
							
							<div class="form-group">
			                    <label class="control-label col-md-3">PIN<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<input name='penjualan_id' value='<?=$penjualan_id;?>' hidden>
									<input name='pin' type='password' id="pin-spv" class="pin_user form-control">
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

		<div class="modal fade" id="portlet-config-sample" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body">
						<form action="<?=base_url()?>transaction/penjualan_list_close" class="form-horizontal" id="form-sample" method="get">
							<h3 class='block'> Konfirmasi</h3>
							
							<div class="form-group">
								<div class='note note-danger'>Jumlah total 0, Input PIN jika ingin melanjutkan</div>
			                    <label class="control-label col-md-3">PIN<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<input name='id' value='<?=$penjualan_id;?>' hidden>
			                    	<input name='tanggal' value='<?=$ori_tanggal;?>' hidden>
									<input name='pin' type='password' id='pin-sample' class="pin_user" class="form-control">
			                    </div>
			                </div>	
		                </form>		                
					</div>

					<div class="modal-footer">
						<button type="button" class="btn blue btn-sample-ok">OK</button>
						<button type="button" class="btn default" data-dismiss="modal">Close</button>
					</div>
				</div>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
		</div>

		<div class="modal fade" id="portlet-config-print" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body">
						<h3 class='block'> Print</h3>
						
						<div class="form-group">
		                    <label class="control-label col-md-3">Printer<span class="required">
		                    * </span>
		                    </label>
		                    <div class="col-md-9">
		                    	<select class='form-control' id='printer-name'>
		                    		<?foreach ($printer_list as $row) { 
		                    			$default_printer = (get_default_printer() == $row->id ? $row->nama : '');?>
		                    			<option  <?=(get_default_printer() == $row->id ? 'selected' : '');?> value='<?=$row->id;?>'><?=$row->nama;?> <?//=(get_default_printer() == $row->id ? '(default)' : '');?></option>
		                    		<?}?>
		                    	</select>
		                    	<div class='note note-info' hidden>
			                    	Ubah default printer Anda di <a target='_blank' href="<?=base_url().is_setting_link('admin/change_default_printer');?>">Setting <i class='fa fa-arrow-right'></i> Ubah Default Printer</a>
		                    	</div>
		                    </div>
		                </div>

		                <div class="form-group">
		                	<div class='col-md-3'></div>
		                	<div class='col-md-9 print-option-judul'>
		                		<span >Pilih Jenis Faktur/Surat Jalan</span>
		                	</div>
		                </div>

		                <div class="form-group">
		                    <label class="control-label col-md-3">Tipe<span class="required">
		                    * </span>
		                    </label>
		                    <div class="col-md-9" style="position:relative">
		                    	<div class="radio-list">
									<label>
									<input type="radio" class="radio-print" data-print="1" name="print_target" value="1"><span class='ket-print'> Faktur </span></label>
									<label>
									<input type="radio" class="radio-print" data-print="2" name="print_target" value="2"><span class='ket-print'> Detail </span></label>
									<label>
									<input type="radio" class="radio-print" data-print="3" name="print_target" value="3" <?=($print_now ? "checked" : '');?> ><span class="ket-print  <?=($print_now ? 'selected-print-blue' : '');?> "> Faktur + Detail <?=($print_now ? 'selected-print-blue' : '');?></span></label>
								</div>
								<div style="position:absolute; right:40px; top:40px;padding:15px; text-align:center; <?=($npwp =='' ? 'display:none' : '');?>" class='bg-red-sunglo'><i class='fa fa-check-circle' style='font-size:1.5em'></i><br/> NPWP</div>
		                    </div>
		                </div>
					</div>

					<div class="modal-footer">
						<!-- <button type="button" class="btn blue btn-active print-faktur-pdf">Print (PDF)</button> -->
						<!-- <button type="button" class="btn blue btn-active btn-print-action">Print</button> -->
						<button type="button" class="btn btn-active default" data-dismiss="modal">Close</button>
					</div>
				</div>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
		</div>

		<div class="modal fade bd-modal-lg" id="portlet-config-dp" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-body">
						<b style='font-size:2em'>DAFTAR DP</b><hr/>
						<table class="table table-striped table-bordered table-hover" id='dp_list_table'>
							<thead>
								<tr>
									<th scope="col">
										Tanggal
									</th>
									<th scope="col">
										Deskripsi
									</th>
									<th scope="col">
										No Transaksi DP
									</th>
									<th scope="col">
										Nilai
									</th>
									<th scope="col">
										Dibayar
									</th>
									<th scope="col" style="min-width:150px !important">
										Actions
									</th>
								</tr>
							</thead>
							<tbody>
								<form id='form-dp' action="<?=base_url('transaction/pembayaran_penjualan_dp_update');?>" method="POST">
									<?
									$dp_bayar = 0;
									foreach ($dp_list_detail as $row) { ?>
										<tr>
											<td>
												<?=is_reverse_date($row->tanggal);?>
											</td>
											<td>
												<?=$row->bayar_dp;?> : <a class='dp-more-info'>info <i class='fa fa-angle-down'></i></a>
												<?
												$type_2 = '';
												$type_3 = '';
												$type_4 = '';
												$type_6 = '';
												${'type_'.$row->pembayaran_type_id} = 'hidden';
												?>
												<ul hidden>
													<li <?=$type_3;?> <?=$type_4;?> <?=$type_6;?> >Penerima :<b><span class='nama_penerima' ><?=$row->nama_penerima?></span></b></li>
													<li <?=$type_2;?> >Bank : <b><span class='nama_bank'><?=$row->nama_bank?></span></b></li>
													<li <?=$type_2;?> <?=$type_6;?> >No Rek : <b><span class='no_rek_bank'><?=$row->no_rek_bank?></span></b></li>
													<li <?=$type_3;?> <?=$type_4;?> <?=$type_2;?>>Jatuh Tempo : <b><span class='jatuh_tempo' ><?=is_reverse_date($row->jatuh_tempo);?></span></b></li>
													<li <?=$type_3;?> <?=$type_4;?> <?=$type_2;?> >No Giro : <b><span class='no_giro' ><?=$row->no_giro;?></span></b></li>
													<li>Keterangan : <b><span class='keterangan'><?=$row->keterangan;?></span></li></b>

												</ul>
											</td>
											<td>
												<span class='no_faktur_lengkap'><?=$row->no_faktur_lengkap;?></span>
											</td>
											<td>
												<span class='amount'><?=number_format($row->amount,'0',',','.');?></span>
											</td>
											<td>
												<?$dp_bayar += $row->amount_bayar;?>
												<input name="amount_<?=$row->id;?>" class='amount-bayar amount-number' value='<?=number_format($row->amount_bayar,'0',',','.');?>' <?=($row->amount_bayar == 0 ? 'readonly' : '');?> style="width:80px">
											</td>
											<td>
												<input name="penjualan_id" value="<?=$penjualan_id;?>" hidden>
												<span class='id' hidden="hidden"><?=$row->id;?></span>
												<input type="checkbox" class='dp-check' <?=($row->amount_bayar != 0 ? 'checked' : '');?> >
											</td>
										</tr>
									<? } ?>
									<tr style='font-size:1.3em'>
										<td colspan='3'></td>
										<td><b>BAYAR DP</b></td>
										<td><span class='dp-total' ><?=number_format($dp_bayar,'0',',','.');?></span></td>
										<td></td>
									</tr>
									<tr style='font-size:1.3em'>
										<td colspan='3'></td>
										<td><b>SISA BON</b></td>
										<td><span class='dp-nilai-bon-info' ></span></td>
										<td></td>
									</tr>
									<tr style='font-size:1.3em'>
										<td colspan='3'></td>
										<td><b>SISA</b></td>
										<td><span class='dp-sisa-info' ></span></td>
										<td></td>
									</tr>
								</form>

							</tbody>
						</table>
					</div>

					<div class="modal-footer">
						<button type="button" class="btn btn-active green btn-save-dp" >Save</button>
						<button type="button" class="btn btn-active default" data-dismiss="modal">Close</button>
					</div>
				</div>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
		</div>

		<div class="modal fade bs-modal-lg" id="portlet-config-sj" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-body">
						<form action="<?=base_url('transaction/surat_jalan_insert')?>" class="form-horizontal" id="form_surat_jalan" method="post">
							<h3 class='block'> SURAT JALAN <span style='color:red' id='sj-edit-title'>*EDIT</span></h3>
							
							<div class="form-group">
			                    <label class="control-label col-md-3">Tanggal<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-8">
			                    	<input name='transaction_id' value='<?=$penjualan_id;?>' <?=$hidden_spv;?>>
			                    	<input id="surat-jalan-id" name='surat_jalan_id' <?=$hidden_spv;?>>
			                    	<input name='surat_jalan_type_id' value='1' <?=$hidden_spv;?> >
			                    	<input readonly name='tanggal' value='<?=$tanggal;?>' class='form-control date-picker'>
			                    </div>
			                </div> 

			                <div class="form-group">
			                    <label class="control-label col-md-3">Alamat Kirim<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-8">
			                    	<select name="alamat_id" class='form-control' id='alamat_id'>
			                    		<?if ($customer_id != '' && $customer_id != 0) {?>
			                    			<option value='0'>
												<?=$alamat_keterangan?>
			                    			</option>
			                    		<?}?>
		                				<?foreach ($customer_alamat_kirim as $row) {
		                					if (trim($row->alamat) != '') {?>
												<option value="<?=$row->id;?>"><?=trim($row->alamat) ?></option>
		                					<?}?>
										<?}?>
			                    	</select>
			                    </div>
			                </div> 

			                <div class="form-group" hidden>
			                    <label class="control-label col-md-3">QTY Barang<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<div class="radio-list">
										<label class="radio-inline">
											<input name='tipe' type='radio' value='1' checked>SEMUA</label>
										<label class="radio-inline">
											<input name='tipe' type='radio' value='2' >SPLIT</label>
									</div>
			                    </div>
			                </div>

			                    <div class='col-xs-12'>
					                <table class='table' id="surat-jalan-tbl" style='margin:auto'>
					                	<thead>
						                	<tr style='background:#eee'>
						                		<th>Barang</th>
						                		<th>Belum Terkirim<br/>Qty / Roll</th>
						                		<th>Rencana Pengiriman<br/>Qty / Roll <button type='button' id="btn-kirim-take-all" class='btn btn-xs red' style="font-size:0.8em">Kirim Semua</button></th>
						                		<th>Sisa Qty Barang<br/>Qty / Roll</th>
						                	</tr>
					                	</thead>
					                	<tbody>
						                	<?
						                	foreach ($this->satuan_list_aktif as $row) {
						                		$total_blm_kirim[$row->id] = 0;
						                		$total_rol_blm_kirim[$row->id] = 0;
						                		$ns[$row->id] =$row->nama;
						                	}
						                	foreach ($penjualan_kirim_detail as $row) { 
						                		$qk = array();
						                		$total_blm_kirim[$row->satuan_id] += $row->qty;
						                		$total_rol_blm_kirim[$row->satuan_id] += $row->jumlah_roll;
						                		?>
						                		<tr>
						                			<td><?=$row->nama_barang?> <?=$row->nama_warna?></td>
						                			<td>
						                				<b class='belum-terkirim' style='color:blue'><?=(float)$row->qty?></b>
						                				<b class='belum-terkirim-temp'  style='color:blue' hidden></b>
						                				<?=$row->nama_satuan;?>
						                				/
						                				<b class='rol-belum-terkirim'><?=$row->jumlah_roll?></b>
						                				<b class='rol-belum-terkirim-temp' hidden></b>
						                				<?$qds = []; $qdk=[];
						                				$d_qty=explode('--', $row->data_qty); $baris = ceil(count($d_qty)/5);
						                				foreach ($d_qty as $key => $value) {
						                					$x = explode('??', $value);
						                					if(isset($qk[(float)$x[0]])){
						                						$qk[(float)$x[0]] += $x[1];
						                					}else{
						                						$qk[(float)$x[0]] = $x[1];
						                					}
						                				}
						                				foreach ($qk as $key => $value) {
						                					array_push($qds, (float)$key.'??'.$value);
						                				}
						                				?>
						                				<span id="rekap-sisa-<?=$row->id?>" hidden class='qty-data-sisa'><?=implode('--', $qds)?></span> 
						                			</td>
								                	<td class='text-left'>
								                		<b class='total-subqty' style='color:blue' id='qty-kirim-<?=$row->id;?>'></b> <?=$row->nama_satuan;?> / <b class='rol-total-subqty'></b>
								                		<span class='detail-id' hidden><?=$row->id;?></span>
								                		<span class='satuan-id' hidden><?=$row->satuan_id;?></span>
								                		<?foreach ($qk as $key => $value) {
						                					array_push($qdk, (float)$key.'??0');
						                				}?>
						                				<input id="rekap-kirim-<?=$row->id?>" hidden name='qty[]' class='qty-data-kirim' value=""> <button style='font-size:0.8em' type='button' class='btn btn-xs green btn-edit-kirim-qty'>edit</button>
						                			</td>
						                			<td>
						                				<b class='sisa-terkirim'><?=(float)$row->qty?></b>
						                				<b class='sisa-terkirim-temp' hidden></b>
						                				<?=$row->nama_satuan;?>
						                				/
						                				<b class='rol-sisa-terkirim'><?=$row->jumlah_roll?></b>
						                				<b class='rol-sisa-terkirim-temp' hidden></b>
						                			</td> 
						                		</tr>
						                	<?}?>
					                	</tbody>
					                	<tfoot>
					                		<?foreach ($ns as $key => $value) {?>
						                		<tr style='background:#eee'>
						                			<th><?=$value?></th>
						                			<th>
						                				<span id='total-belum-terkirim-<?=$key?>'><?=$total_blm_kirim[$key];?></span> <?=$value;?>/
						                				<span id='rol-total-belum-terkirim-<?=$key?>'><?=$total_rol_blm_kirim[$key];?></span>
						                			</th>
						                			<th>
						                			<span id='total-terkirim-<?=$key?>'>0</span> <?=$value;?> /
						                				<span id='rol-total-terkirim-<?=$key?>'>0</span>
						                			</th>
						                			<th>
						                				<span id='total-sisa-terkirim-<?=$key?>'><?=$total_blm_kirim[$key];?></span> <?=$value;?>/
						                				<span id='rol-total-sisa-terkirim-<?=$key?>'><?=$total_rol_blm_kirim[$key];?></span>
						                			</th>
						                		</tr>
					                		<?}?>
					                	</tfoot>
					                </table>
				                </div>

						</form>

						<div id="overlay-kirim" >
							<div id="overlay-kirim-content">
								<div>
									<small>* double click untuk ambil semua roll</small>
									<table id='qty-kirim-tbl'>
	                					<thead>
		                					<tr>
		                						<th>Barang</th>
		                						<th>QTY</th>
		                						<th>ROL Sisa</th>
		                						<th>ROL Kirim</th>
		                						<th>SubTotal</th>
		                					</tr>
	                					</thead>
	                					<tbody></tbody>
	                					<tfoot>
	                						<tr>
	                							<td></td>
	                							<td></td>
	                							<th>TOTAL</th>
	                							<th class='rekap-kirim'></th>
	                							<th class='total'></th>
	                						</tr>
	                					</tfoot>
	                				</table>
	                				<hr/><button type="button" class="btn btn-sm blue" id="get-kirim-qty" style='margin-right:10px;'>OK</button>
									<button type="button" class="btn btn-sm btn-default close-overlay-kirim" ><i class='fa fa-times'></i> Cancel</button>
								</div>
							</div>
						</div>
					</div>

					<div class="modal-footer">
						<button type="button" class="btn blue btn-active" id="btn-sj-save">Save</button>
						<button type="button" class="btn default" data-dismiss="modal">Close</button>
					</div>
				</div>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
		</div>

		<div class="row margin-top-10">
			<div class="col-xs-12">
				<div class="portlet light">
					<div class="portlet-title">
						<div class="caption caption-md">
							<i class="icon-bar-chart theme-font hide"></i>
							<span class="caption-subject theme-font bold uppercase"><?=$breadcrumb_small;?></span>
						</div>
						<div class="actions hidden-print">
							<?if (is_posisi_id() != 6) { ?>
								<a href="<?=base_url().is_setting_link('transaction/penjualan_list_detail');?>" target='_blank' class="btn btn-default btn-sm">
								<i class="fa fa-files-o"></i> Tab Kosong Baru </a>
								<a href="#portlet-config" data-toggle='modal' class="btn btn-default btn-sm " id="btn-form-add">
								<i class="fa fa-plus"></i> Penjualan Baru </a>
							<?}?>
							<a href="#portlet-config-faktur" data-toggle='modal' class="btn btn-default btn-sm">
							<i class="fa fa-search"></i> Cari Faktur </a>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-4 col-xs-12">
				<div class="portlet light">
					<div class="portlet-title">
						<div class="caption caption-md">
							<i class="icon-bar-chart theme-font hide"></i>
							<span class="caption-subject theme-font bold uppercase">DATA CUSTOMER</span>
						</div>
						<div class="actions hidden-print">
							<?if ($penjualan_id != '' && $status_aktif != -1) { ?>
								<?if (is_posisi_id() != 6 ) { ?>
									<button href="#portlet-config-edit" data-toggle='modal' style="<?=($status == 0 ? 'display:none' : '')?>" class='btn btn-sm btn-default' id="btn-edit-data"><i class='fa fa-edit'></i> edit</button>
			                        <button style="float:right; <?=($status == 1 ? 'display:none' : '')?>" href="#portlet-config-pin" data-toggle='modal' class='btn btn-xs btn-default' id="btn-pin"><i class='fa fa-key'></i> OPEN <?=$status?></button>
								<?}?>
							<?}?>
						</div>
					</div>
					<div class="portlet-body">
						<!--  table header -->
						<table>
							<tr hidden>
					    		<td>Status</td>
					    		<td class='padding-rl-5'> : </td>
					    		<td class='td-isi-bold'>
					    			<?if ($status == -1 && $no_faktur_lengkap != '' && $status_aktif != -1 ) { $iClass = 'fa-ban' ?>
					    				<span style='color:red'><b>BATAL</b></span>
					    			<?}elseif ($status == 1 && $no_faktur_lengkap != '' && $status_aktif != -1  ) { $iClass = 'fa-unlock' ?>
					    				<span style='color:green'><b>OPEN</b></span>
					    			<?}elseif ($status == 0 && $no_faktur_lengkap != '' && $status_aktif != -1  ) { $iClass = 'fa-lock' ?>
					    				<span style='color:orange'><b>LOCKED</b></span>
					    			<?}elseif ($status_aktif == -1) {
					    				$iClass = 'fa-minus-circle';
					    			}elseif ($penjualan_id !='') {
					    				$iClass = 'fa-exclamation-circle';
					    			}?>
					    		</td>
					    	</tr>
							<tr>
					    		<td>Tipe</td>
					    		<td class='padding-rl-5'> : </td>
					    		<td class='td-isi-bold'>
					    			<?=$tipe_penjualan;?>
					    		</td>
					    	</tr>
					    	<tr>
						    	<!-- po_section -->
					    		<td>PO</td>
					    		<td class='padding-rl-5'> : </td>
					    		<td class='td-isi-bold'>
					    			<?=$po_number;?>
					    		</td>
					    	</tr>
					    	<tr>
					    		<td><i class='fa fa-calendar'></i></td>
					    		<td class='padding-rl-5'> : </td>
					    		<td class='td-isi-bold'><?=is_reverse_date($tanggal);?></td>
					    	</tr>
					    	<tr hidden <?=($penjualan_type_id != 2 ? 'hidden' : '' )?> >
					    		<td>Jatuh Tempo</td>
					    		<td class='padding-rl-5'> : </td>
					    		<td class='td-isi-bold'>
					    			<?
					    				// $dt = strtotime(' +60 days', strtotime($tanggal) );
										// echo $get_jt = ($jatuh_tempo == '' ? date('Y-m-d', $dt) : $row->jatuh_tempo);
					    			?>
					    			<?=$jatuh_tempo;?></td>
					    	</tr>
					    	<tr class='customer_section'>
					    		<td><i class='fa fa-user'></i></td>
					    		<td class='padding-rl-5'> : </td>
					    		<td class='td-isi-bold' style='font-size:1.2em'>
					    			<?if ($penjualan_type_id == 3) { ?>
					    				<?=$nama_keterangan;?> / <span class='alamat_keterangan'><?=$alamat_keterangan;?></span>
					    			<?} else{
					    				echo $nama_customer;
					    			}
					    			if ($npwp != '') {?>
						    			<span class='label bg-red-thunderbird'>NPWP</span>
					    			<?}?>
					    		</td>
					    	</tr>
					    	<tr class='customer_section'>
					    		<td style='vertical-align:top'><i class='fa fa-building'></i></td>
					    		<td style='vertical-align:top' class='padding-rl-5'> : </td>
					    		<td class='td-isi-bold'>
					    			<span class='alamat'><?=$alamat_customer?><?=($kota!=''? ', '.$kota:'')?></span>
					    		</td>
					    	</tr>
					    	<tr class='customer_section'>
					    		<td>FP</td>
					    		<td class='padding-rl-5'> : </td>
					    		<td class='td-isi-bold'>
					    			<?if ($fp_status == 1) { ?>
					    				<i class='fa fa-check'></i>
					    			<?} else{
					    				echo '';
					    			}?>
					    		</td>
					    	</tr>
					    </table>
					</div>
					<div class="portlet-body">
						<div class='<?//=$note_info;?>' style='<?=$bg_info;?>; color:white; padding:10px;'>
							<i id='icon-lock' class='fa <?=$iClass;?>' style='font-size:2em'><?=($status_aktif == '-1' ? 'BATAL' : '');?></i>
							<small>Created by : <span class='username'><?=$username;?></span></small> | <small>LOCKED by : <span id='locked-by'><?=$username_close?></span></small><br/>
							<span class='no-faktur-lengkap'> <?=$no_faktur_lengkap;?></span><br/>
							<small><?=($status == 0 && $no_faktur_lengkap != '' && $status_aktif != -1 ? date('d/m/Y H:i:s', strtotime($closed_date)) : ($no_faktur_lengkap == '' && $penjualan_id != '' ? 'belum di lock' : '') )?></small>
						</div>
					</div>

				</div>
			
				<div class="portlet light">
					<div class="portlet-title">
						<div class="caption caption-md">
							<i class="icon-bar-chart theme-font hide"></i>
							<span class="caption-subject theme-font bold uppercase">SURAT JALAN</span>
						</div>
						<div class="actions hidden-print">
							<?if ($penjualan_id != '') { ?>
								<?if ($status == 1 || $status == 0) { ?>
									<?if (is_posisi_id() != 6 ) { ?>
										<a href="#portlet-config-sj" data-toggle="modal" class='btn btn-default' id="btn-new-sj"><i class="fa fa-plus"></i>Baru</a>
									<?}?>
								<?}?>
							<?}?>
						</div>
					</div>
					
					<div class="portlet-body">
						<table class='table'>
							<?foreach ($surat_jalan_list as $row) {?>
								<tr>
									<td>SJ<?=date("dmy",strtotime($row->tanggal))?>-<?=str_pad($row->no, 5,"0", STR_PAD_LEFT);?></td>
									<td class='text-right'>
										<span class='id' hidden><?=$row->id;?></span>
										<span class='tanggal' hidden><?=is_reverse_date($row->tanggal);?></span>
										<span class='alamat_id' hidden><?=$row->alamat_id;?></span>
										<span class="qty-data" hidden><?=$row->qty_data;?></span>
										<button href='#portlet-config-sj' <?=($status == 0 ? '' : 'disabled');?> data-toggle="modal" class="btn btn-sm green btn-edit-sj"><i class='fa fa-edit'></i> Edit</button>
										<button <?=($status == 0 ? '' : 'disabled');?> class="btn btn-sm blue" onclick="printPDF('transaction/surat_jalan_print?surat_jalan_id=<?=$row->id?>')"><i class='fa fa-print'></i> Print</button>
									</td>
								</tr>
							<?};?>
						</table>
					</div>

				</div>
			</div>
			<div class="col-md-8 col-xs-12">
				<div class="portlet light">
					<div class="portlet-title">
						<div class="caption caption-md">
							<i class="icon-bar-chart theme-font hide"></i>
							<span class="caption-subject theme-font bold uppercase">DATA BARANG</span>
						</div>
						<div class="actions hidden-print">
							<?if ($penjualan_id !='' && $status == 1 && is_posisi_id() != 6 && $status_aktif != -1 ) {?>
								<button href="#portlet-config-detail" data-toggle='modal' id="btn-brg-add" class="btn btn-sm blue">
								<i class="fa fa-plus"></i> Barang </button>
							<?}/*elseif (is_posisi_id() == 1) {?>
								<button href="#portlet-config-detail" data-toggle='modal' id="btn-brg-add" class="btn btn-sm blue ">
								<i class="fa fa-plus"></i> </button>
							<?}*/?>
						</div>
					</div>
					<div class="portlet-body">

						<!--  table item -->
					    <div style='overflow:auto'>
							<!-- table-striped table-bordered  -->
							<table class='table table-striped' id="general_table">
								<thead>
									<tr>
										<th scope="col" style='width:40px'>
											No
										</th>
										<th scope="col">
											Barang
										</th>
										<!-- <th scope="col" style='width:80px'>
											Sumber
										</th> -->
										<th class='text-right' scope="col" style='padding-right:2px' >
											Qty
										</th>
										<th class='text-center' style='width:10px !important;padding-right:0px; padding-left:0px' >
											/
										</th>
										<th scope="col" class='text-left' style='padding-left:2px' >
											Roll
										</th>
										<th scope="col">
											Harga
										</th>
										<th scope="col" class='text-right' style='padding-right:10px'>
											Total
										</th>
									</tr>
								</thead>
								<tbody>
									<?
									$total_nppn = 0;
									$idx =1; $barang_id = ''; $gudang_id_last = ''; $harga_jual = 0; 
									foreach ($this->satuan_list_aktif as $row) {
										$total_each[$row->id] = 0;
										$qty_total[$row->id] = 0;
										$roll_total[$row->id] = 0;
										$nama_satuan[$row->id] = $row->nama;
									}
									/*foreach ($penjualan_detail as $row) { ?>
										<tr id='id_<?=$row->id;?>'>
											<td>
												<?=$idx;?> 
											</td>
											<td>
												<b class='nama_jual'><?=$row->nama_barang;?> <?=$row->nama_warna;?></b> 
												<?$barang_id=$row->barang_id;?><br/>
												<small><?=$row->jenis_barang;?></small> | 
												<small class='badge badge-roundless badge-<?=(strtolower($row->nama_gudang) == "toko" ? 'primary' : 'info');?>'> <?=$row->nama_gudang;?></small>
											</td>
											<!-- <td>
											</td> -->
											<td class='text-right' style='padding-right:2px;'>
												<span class='qty'><?=str_replace('.00', '',$row->qty);?></span><br/>
												<small class='nama_satuan'><?=$row->nama_satuan;?></small> 	
											</td>
											<td class='text-center' style='width:10px !important;'>/</td>
											<td class='text-left' style='padding-left:2px;'>
												<span class='jumlah_roll'><?=$row->jumlah_roll;?></span> 
											</td>
											<td>
												<!-- <input name='harga_jual' <?=$readonly;?> class='free-input-sm amount_number harga_jual' value="<?=number_format($row->harga_jual,'0','.','.');?>">  -->
												<span class='harga_jual'><?=number_format($row->harga_jual,'0','.','.');?></span>

											</td>
											<td class='text-right' style='padding-right:10px'>
												<?$subtotal = $row->qty * $row->harga_jual;
												$g_total += $subtotal;
												$total_each[$row->satuan_id] += $subtotal;
												$harga_jual = $row->harga_jual;
												$qty_total[$row->satuan_id] += $row->qty;
												$roll_total[$row->satuan_id] += $row->jumlah_roll;
												?>
												<span class='subtotal'><?=number_format($subtotal,'0','.','.');?></span> 
												<?$gudang_id_last=$row->gudang_id;?>
												
												<?if ($status == 1 || is_posisi_id() == 1 ) { ?>
													<?if (is_posisi_id() != 6 && $status_aktif != -1) { ?>
														<span class='id' <?=$hidden_spv?> ><?=$row->id;?></span>
														<span class='barang_id' <?=$hidden_spv?> ><?=$row->barang_id;?></span>
														<span class='warna_id' <?=$hidden_spv?> ><?=$row->warna_id;?></span>
														<span class='gudang_id'  <?=$hidden_spv?> ><?=$row->gudang_id;?></span>
														<span class='data_qty'  <?=$hidden_spv?> ><?=$row->data_qty;?></span>
														<span class='satuan_id'  <?=$hidden_spv?> ><?=$row->satuan_id;?></span>
														<a href='#portlet-config-detail-edit' data-toggle='modal' style='display:none' class="btn-xs btn green btn-edit"><i class="fa fa-edit"></i> </a>
														<a class="btn-xs btn red btn-detail-remove" style='display:none'><i class="fa fa-times"></i> </a>
													<?}?>
												<?}?>
											</td>
										</tr>
									<?
									$idx++; 
									}*/ ?>
								</tbody>
								<tfoot>
									<?/*foreach ($this->satuan_list_aktif as $row) {?>
										<tr id="rekap-<?=$row->id?>">
											<td class='text-right'></td>
											<!-- <td class='text-right'></td> -->
											<td class='text-left'><b>Total <?=$row->nama;?></b>
											</td>
											<td class='text-right' style='padding-right:2px;'><b id="totalQty-<?=$row->id;?>"></b></td>
											<td class='text-center'>/</td>
											<td class='text-left' style='padding-left:2px;'><b  id="totalRoll-<?=$row->id;?>"><?=$roll_total[$key];?></b></td>
											<td class='text-right'><b></b></td>
											<td class='text-right' style='padding-right:10px'><b class='total' id="total-<?=$row->id;?>" ></b> </td>
										</tr>
									<?}*/?>
									<tr class='subtotal-data'>
										<td class='text-right'></td>
										<!-- <td class='text-right'></td> -->
										<td class='text-left'><b>TOTAL </b>
										</td>
										<td class='text-right' style='padding-right:2px;'><b>Roll</b></td>
										<td class='text-center'>:</td>
										<td class='text-left' style='padding-left:2px;'><b class="g-total-roll"></b></td>
										<td class='text-right'><b></b></td>
										<td class='text-right' style='padding-right:10px'><b class='grand_total'><?=number_format($g_total,'0',',','.');?> </b> </td>
									</tr>

								</tfoot>
							</table>
					    </div>
					    <hr/>
							<div hidden>
								<span style='font-size:1.5em; font-weight:bold'>Posisi Barang : </span>
								<label>
			                    <input type='checkbox' name='posisi_barang' class='form-control' id='posisi-barang' value='1'>Dititip</label>
								<table id='general-detail-posisi' class='table table-bordered'>
								<thead>
									<tr>
										<th>Barang</th>
										<?for ($i=1; $i <= 5 ; $i++) {?> 
											<th class='text-center' style='width:12%;'><?=$i;?></th>
										<?}?>
										<th rowspan='2'>Total</th>
									</tr>
									<tr>
										<th>Tanggal</th>
										<?for ($i=1; $i <= 5 ; $i++) {?> 
											<th class='text-center'>
												<input style='width:70px' name='tanggal' id='tanggal-ambil' class='date-picker text-center' placeholder="tanggal" autocomplete='off'><br/>
											</th>
										<?}?>
									</tr>
								</thead>
								<?/*foreach ($penjualan_detail as $row) {
									$data_qty = explode('--', $row->data_qty);?>
									<tr>
										<td><?=$row->nama_barang?> <?=$row->nama_warna?></td>
										<td class='text-center'>
											<?foreach ($data_qty as $key => $value) {
												$qty = explode('??', $value);?>
													<span style='display:inline-block;width:25px;'><?=(float)$qty[0]?></span>  x <input style='width:25px; text-align:center; border:none; border-bottom:1px solid #ddd' value='<?=$qty[1]?>' ><br/>
											<?}?>
										</td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td><?=str_replace('.00', '',$row->qty)?></td>
									</tr>
								<?}*/?>
							</table>
							</div>
						<!-- detail table -->
							<div>
								<p class='btn-detail-toggle' style='cursor:pointer; position:relative; '>
									<b>Detail <i class='fa fa-caret-down'></i></b>
									<?if (is_posisi_id() == 5 || is_posisi_id() == 1) {?>
										<a target="_blank" style=' display:none;position:absolute; right : 10px; top:-10px' href="<?=base_url().is_setting_link('inventory/stok_barang_ajax');?>" class="btn btn-lg green"><i class='fa fa-search'></i>STOK BARANG</a>
									<?}?>
								</p>
							</div>
						
							<table id='general-detail-table' class='table table-bordered' hidden>
								<thead>
									<tr>
										<th>Barang</th>
										<th>Warna</th>
										<th>Roll</th>
										<th>Total</th>
										<th>Detail</th>
									</tr>
								</thead>
								<?/*foreach ($penjualan_detail as $row) {?>
									<tr>
										<td><?=$row->nama_barang?></td>
										<td><?=$row->nama_warna?></td>
										<td><?=$row->jumlah_roll?></td>
										<td><?=str_replace('.00', '',$row->qty)?></td>
										<td><?$data_qty = explode('--', $row->data_qty);
											$coll = 1;
											foreach ($data_qty as $key => $value) {
												$detail_qty = explode('??', $value);
												for ($i=1; $i <= ($detail_qty[1] != 0 ? $detail_qty[1] : 1) ; $i++) { 
													echo "<p style='display:inline-flex; width:50px; '>".str_replace('.00', '', $detail_qty[0])."</p>";
													$coll++;
													if ($coll == 11) {
														echo "<hr style='margin:2px' />";
														$coll = 1;
													}
												}
											}
										?></td>
									</tr>
								<?}*/?>
							</table>
						<!-- Pembayaran -->
						<div class='row' <?=($status_aktif == -1 || $penjualan_id == '' ? 'hidden' : '');?>>
							<div class='col-xs-12 col-md-6' >
								<table width="100%" id='bayar-data'>
									<?
									$bayar_total = 0;
									foreach ($pembayaran_type as $row) { 
										$bayar = null; 
										if (isset($pembayaran_penjualan[$row->id])) {
											$bayar = $pembayaran_penjualan[$row->id];
											if ($row->id == 1) {
												$bayar = $dp_bayar;
												$bayar_total += $dp_bayar;
											}else{
												$keterangan = $pembayaran_keterangan[$row->id];
												$bayar_total += $bayar;
											}
										}

										$stat = ''; $style = '';
										if (is_posisi_id() == 6) {
											$stat = 'readonly';
											$style = 'background:#ddd; border:1px solid #ddd';
										}

										if ($row->id == 1 || $status != 1) {
											if ( $customer_id == '' || $customer_id == 0 || $status != 1) {
												if (is_posisi_id() != 1) {
													$stat = 'readonly';
													$style = 'background:#ddd; border:1px solid #ddd';
												}
											}
										}
										?>
										<?if ($row->id == 1) { ?>
											<tr>
												<td><?=$row->nama;?><span class='saldo_awal' hidden><?=$saldo_awal;?></span></td>
												<td>
													<a id='manage-dp'>
														<input autocomplete='off' <?=$stat;?> style='<?=$style;?>' class='dp-val' value="<?=number_format($bayar,'0',',','.');?>" >
													</a>
													<span class='dp_copy' hidden><?=$bayar?></span>
												</td>
												<td></td>
											</tr>
										<?}elseif ($row->id == 4) { ?>
											<tr>
												<td><?=$row->nama;?></td>
												<td class='text-right'>
													<input autocomplete='off' <?=$stat;?> style='<?=$style;?>' class='amount_number bayar-input ' id='bayar_<?=$row->id;?>' value="<?=number_format($bayar,'0',',','.');?>">
												</td>
												<td class='text-left'>
													<?if ($penjualan_id != '') { ?>
														<a data-toggle="popover" style='color:black' data-trigger='click' data-html="true" data-content="<input <?=$stat;?> style='<?=$style;?>' class='keterangan_bayar' name='keterangan_<?=$row->id;?>' value='<?=$keterangan;?>'>">
															<i class='fa fa-edit'></i>
														</a>
													<?}?>
												</td>
											</tr>
										<?}elseif ($row->id == 5) { ?>
											<tr <?=($penjualan_type_id != 2 ? 'hidden' : '')?> >
												<td><?=$row->nama;?></td>
												<td>
													<a data-toggle="popover" style='color:black' data-trigger='hover' data-html="true" data-content="Hanya untuk tipe kredit pelanggan">
														<input autocomplete='off' <?=$stat;?> id='bayar_<?=$row->id;?>'  class='amount_number bayar-input bayar-kredit' value="<?=number_format($bayar,'0',',','.');?>">
													</a>
												</td>
												<td></td>
											</tr>
										<?}elseif ($row->id == 6) { ?>
											<tr hidden>
												<td><?=$row->nama;?></td>
												<td>
													<a data-toggle="popover" style='color:black' data-trigger='hover' data-html="true" data-content="Nama Bank : <b><?=$nama_bank?></b><br/>No Rek : <b><?=$no_rek_bank?></b><br/>No Akun : <b><?=$no_akun?></b><br/>Nama Bank : <b><?=$nama_bank?></b><br/>Tanggal Giro : <b><?=$tanggal_giro?></b><br/>Jatuh Tempo : <b><?=$jatuh_tempo_giro?></b><br/>">
														<input autocomplete='off' <?=$stat;?> style='<?=$style;?>' class='amount_number bayar-giro' id='bayar_<?=$row->id;?>' value="<?=number_format($bayar,'0',',','.');?>">
													</a>
													<?if ($penjualan_id != '' && is_posisi_id() != 6 && $status != 0) { ?>
														<a data-toggle="modal" href='#portlet-config-giro' style='color:black' style='<?=$style;?>' >
															<i class='fa fa-edit'></i>
														</a>
													<?}?>
												</td>
												<td></td>
											</tr>
										<?}else{?>
											<tr>
												<td><?=$row->nama;?></td>
												<td><input autocomplete='off' <?=$stat;?> style='<?=$style;?>' class='amount_number bayar-input' id='bayar_<?=$row->id;?>' value="<?=number_format($bayar,'0',',','.');?>"></td>
												<td></td>
											</tr>
										<?}?>

									<?}?>
								</table>
							</div>
							<div class='col-xs-12 col-md-6' <?=($status_aktif == -1 ? 'hidden' : '');?>>
								<table style='float:right; font-size:2em' >
									<tr style='border:2px solid #c9ddfc'>
										<td class='text-right' style='background:#c9ddfc; padding:0 5px'>BAYAR</td>
										<td class='padding-rl-10'>
											<b>Rp <span id='total_bayar' style=''></b>
										</td>
									</tr>
									<tr style='border:2px solid #ffd7b5'>
										<td class='text-right' style='background:#ffd7b5; padding:0 5px'>TOTAL</td>
										<td class='text-right padding-rl-10'> 
											<b>Rp <span class='grand_total' style=''></span></b>
										</td>
									</tr>
									<tr style='border:2px solid #ceffb4'>
										<td class='text-right' style='background:#ceffb4; padding:0 5px'>KEMBALI</td>
										<td class='padding-rl-10'>
											<b>Rp <span id='kembali' style='<?=$kembali_style;?>'></span></b>
										</td>
									</tr>
								</table>
							</div>
							<hr/>
							<?//print_r($data_penjualan_detail_group);?>
							<!-- Button print list -->
							<div class='col-xs-12' style='margin-top:10px'>
								<hr/>
								<?if ($status_aktif != -1) {?>
		                            <?$print_disable = ($penjualan_id == '' || $status == 1 ? 'disabled' : '');?>
		                            <!--  -->
									<!--<button type='button'<?if ($idx == 1) { echo 'disabled'; }?> <?=$disabled;?> style="<?=($status != 1 ? 'display:none' : '')?>" class='btn btn-lg red hidden-print' onClick="closeForm()"  id="btn-close"><i class='fa fa-lock'></i> LOCK </button>-->
									<a onClick="generateCloseForm()" class='btn btn-lg default'> GENERATE INVOICE</a>
									
									<button href="#portlet-config-print" style="display:none<?//=($status !=0 ? 'display:none' : '')?>" data-toggle="modal"  type="button" tabindex='-1' data-print="1" class="btn btn-print btn-lg blue btn-faktur-print">FAKTUR</button>
									<button  href="#portlet-config-print" style="display:none<?//=($status !=0 ? 'display:none' : '')?>" data-toggle="modal" type="button" tabindex='-1'  data-print="2" class="btn btn-print btn-lg blue btn-faktur-print">DETAIL</button>
		                            <button  href="#portlet-config-print" style="display:none<?//=($status !=0 ? 'display:none' : '')?>" data-toggle="modal" type="button" tabindex='-1'  data-print="3" class="btn btn-print btn-lg blue btn-faktur-print">FAKTUR + DETAIL</button>
		                            <!-- <button style='float:right' style="<?=($status == 1 ? 'display:none' : '')?>" href="#portlet-config-pin" data-toggle='modal' class='btn btn-lg btn-default' id="btn-pin"><i class='fa fa-key'></i> OPEN</button> -->
					                
					            <?}?>
	                            
							</div>
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

<script src="<?php echo base_url('assets_noondev/js/qz-print/dependencies/rsvp-3.1.0.min.js');?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets_noondev/js/qz-print/dependencies/sha-256.min.js');?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets_noondev/js/qz-print/qz-tray.js');?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets_noondev/js/webprint.js'); ?>" type="text/javascript"></script>

<script src="<?php echo base_url('assets/global/plugins/select2/select2.min.js'); ?>" type="text/javascript" ></script>
<script src="<?php echo base_url('assets_noondev/js/form-penjualan.js'); ?>" type="text/javascript"></script>

<?
	$printer_marker = "";
	$headers = apache_request_headers();
	foreach ($headers as $header => $value) {
		if ($header == "Host") {
		    if ($value == 'sistem.blessingtdj.com') {
		    	$printer_marker = 3;
		    }
			# code...
		}
	}

	//====================alaamt list===================

	$cek_alamat = preg_split('/\r\n|[\r\n]/', $alamat_keterangan);
	$array_rep = ["\n","\r"];
	$alamat_keterangan = str_replace($array_rep, ' ', $alamat_keterangan);

	$alamat1 = substr(strtoupper(trim($alamat_keterangan)), 0,47);
   	$alamat2 = substr(strtoupper(trim($alamat_keterangan)), 47);
	$last_1 = substr($alamat1, -1,1);
	$last_2 = substr($alamat2, 0,1);

	$positions = array();
	$pos = -1;
	while (($pos = strpos(trim($alamat_keterangan)," ", $pos+1 )) !== false) {
		$positions[] = $pos;
	}

	$max = 47;
	if ($last_1 != '' && $last_2 != '') {
		$posisi =array_filter(array_reverse($positions),
			function($value) use ($max) {
				return $value <= $max;
			});

		$posisi = array_values($posisi);

		$alamat1 = substr(strtoupper(trim($alamat_keterangan)), 0,$posisi[0]);
	   	$alamat2 = substr(strtoupper(trim($alamat_keterangan)), $posisi[0]);
	}

	$alamat_kirim0[1] = $alamat1;
	$alamat_kirim0[2] = $alamat2;

	foreach ($customer_alamat_kirim as $row) {
		$alamat_get = ($row->alamat == '' ? "- " : $row->alamat);

		$alamat_n1 = substr(strtoupper(trim($alamat_get)), 0,47);
	   	$alamat_n2 = substr(strtoupper(trim($alamat_get)), 47);

	   	$last_n1 = substr($alamat_n1, -1,1);
		$last_n2 = substr($alamat_n2, 0,1);

		$positions = array();
		$pos = -1;
		while (($pos = strpos(trim($alamat_get)," ", $pos+1 )) !== false) {
			$positions[] = $pos;
		}

		$max = 47;
		if ($last_n1 != '' && $last_n2 != '') {
			$posisi =array_filter(array_reverse($positions),
				function($value) use ($max) {
					return $value <= $max;
				});

			$posisi = array_values($posisi);

			$alamat_n1 = substr(strtoupper(trim($alamat_get)), 0,$posisi[0]);
		   	$alamat_n2 = substr(strtoupper(trim($alamat_get)), $posisi[0]);
		}

	   	${'alamat_kirim'.$row->id}[1]=$alamat_n1;
	   	${'alamat_kirim'.$row->id}[2]=$alamat_n2;

	}

?>



<script>

const penjualan_id = "<?=$penjualan_id?>";
let penjualan_type_id = '<?=$penjualan_type_id;?>';

let statusPenjualan = <?=$status;?>;
let penjualanItem = [];
//untuk retrieve pengambilan sebelumnya
let pengambilanDataList = [];
let pengambilanList = [];
// untuk pengambilan live
let pengambilanItem = [];
let pengambilanData = {
	penjualan_id: penjualan_id
};

let pengirimanData = {
	transaction_id: penjualan_id
};

let qty_kirim_total = [];
let roll_kirim_total = [];
let statusAmbil = "<?=$status_ambil?>";

let grandTotal=0;
let totalBayar=0;
let kembali = 0;
let totalQty = [];
let totalRoll = [];
let totalEach = [];
let totalAmbil = 0;
let editAmbilId = '';
let editKirimId = '';

var printRAWIdx = '';

<?foreach ($this->satuan_list_aktif as $row) {?>
	qty_kirim_total[<?=$row->id?>] = 0;
	roll_kirim_total[<?=$row->id?>] = 0;
	totalEach[<?=$row->id?>] = 0;
<?}?>

jQuery(document).ready(function() {

	//form validation
	FormNewPenjualanDetail.init();
	penjualanListDetail();

	generatePengambilanData();

//============================semua print behaviour=========================
	
	let print_now = <?=$print_now?>;
	if (print_now) {
		$("#portlet-config-print").modal("toggle");
	};

	$(".print-faktur-pdf").click(function(){
		$('#overlay-div').show();
		print_faktur_frame('print-pdf-static');
	});

	$(".print-testing-pdf").click(function(){
		$('#overlay-div').show();
		print_faktur_frame('print-pdf-static');
	})

	$(".radio-print").change(function(){
		// alert('y');
		// alert($(this).closest('.radio-list').html());
		$.uniform.update($(this));
		$(this).closest('.radio-list').find(".ket-print").removeClass('selected-print-blue');
		let ini = $(this).closest('label').find('.ket-print');
		ini.addClass("selected-print-blue");
	});

	<?if($penjualan_id != '' && $status==0){?>

			var printer_default = "<?=$default_printer?>";

				/*webprint = new WebPrint(true, {
			        relayHost: "127.0.0.1",
			        relayPort: "8080",
		            listPrinterCallback: populatePrinters,
			        readyCallback: function(){
		                webprint.requestPrinters();
		            }
			    });*/
			
		    $('.btn-faktur-print').click(function(){
		    	let data_print = $(this).attr('data-print');
		    	$(`[data-print=${data_print}]`).prop("checked",true).change();
			});

		    $('.btn-print').dblclick(function(){
	    		notific8("lime", "print...");
		    	$('.btn-print-action').click();
		    });

		    $('.btn-print-action').click(function(){
				printer_marker = "<?=$printer_marker?>";
				var selected = $('#printer-name').val();
				var printer_name = $("#printer-name [value='"+selected+"']").text();
				printer_name = $.trim(printer_name);
				let status = 1;
				let action_print = [];

				if (action == 1 ) {
					printer_marker == 3 ? print_faktur_3(printer_name) : print_faktur(printer_name);
				}else if(action == 2){
					printer_marker == 3 ? print_detail_3(printer_name) : print_detail(printer_name);
				}
				else if(action == 3){
					printer_marker == 3 ? print_kombinasi_3(printer_name) : print_kombinasi(printer_name);
				}else if(action == 4){
					let alamat_get = $("[name=print_alamat]:checked").attr('data-alamat');
					print_header_surat_jalan(printer_name, alamat_get);
					printer_marker == 3 ? print_surat_jalan_3(printer_name) : print_surat_jalan_beta(printer_name);
				}else if(action == 5){
					let alamat_get = $("[name=print_alamat_2]:checked").attr('data-alamat');
					print_header_surat_jalan(printer_name, alamat_get);
					printer_marker == 3 ? print_surat_jalan_noharga_3(printer_name) : print_surat_jalan_noharga(printer_name);
				}else{
					// print_testing(printer_name);
				}

				$('#portlet-config-print').modal('hide');
			});
		<?}?>

	//===========================general=================================


		<?if ($penjualan_type_id != 3 && $penjualan_id != '') {
			if ($nik == '' && $npwp == '') {?>
				alert("Data customer kurang NIK / NPWP, mohon lengkapi !");
			<?}
		}?>


		var form_group = {};
		var idx_gen = 0;
		var print_idx = 1;
	   	// var ppn_status = '<?=$ppn_status;?>';


		$('[data-toggle="popover"]').popover();


	    $('#warna_id_select, #barang_id_select,#warna_id_select_edit, #barang_id_select_edit').select2({
	        placeholder: "Pilih...",
	        allowClear: true
	    });

	    $('#customer_id_select, #customer_id_select_edit').select2({
	        allowClear: true
	    });

//========================================================================================


	    <?if ($penjualan_id != '') { ?>
			
			$('#dp_list_table').on('click','.dp-more-info', function(){
		    	$(this).closest('td').find('ul').toggle();
		    });

		    $("#manage-dp").click(function(){
		    	if (statusPenjualan == 1) {
		    		$("#portlet-config-dp").modal('toggle');
		    	};
				var bayar = 0;
		    	$('#bayar-data tr td input').each(function(){
					if ($(this).attr('class') != 'keterangan_bayar' && $(this).attr('class') != 'dp-val') {
						bayar += reset_number_format($(this).val());			
					};
				});

				var g_total = reset_number_format($('.grand_total').html());
				g_total = g_total - bayar;
				$('#dp_list_table .dp-nilai-bon-info').html(change_number_format(g_total));
				
		    	dp_update_bayar();
		    });
		<?}?>

	//====================================Data behaviour=============================

		//search no faktur option dropdown
	    $("#search_no_faktur").select2({
	        placeholder: "Select...",
	        allowClear: true,
	        minimumInputLength: 1,
	        query: function (query) {
	            var data = {
	                results: []
	            }, i, j, s;
	            var data_st = {};
				var url = "transaction/get_search_no_faktur_jual";
				data_st['no_faktur'] = query.term;
				
				ajax_data_sync(url,data_st).done(function(data_respond  /*,textStatus, jqXHR*/ ){
					// console.log(data_respond);
					$.each(JSON.parse(data_respond),function(k,v){
						data.results.push({
		                    id: v.id,
		                    text: v.no_faktur
		                });
					});
		            query.callback(data);
		   		});
	        }
	    });

		//search no faktur action button
	    $('.btn-search-faktur').click(function(){
	    	var id = $("#search_no_faktur").val();
	    	if (id != '' && typeof id !== 'undefined') {
	    		$('#form_search_faktur').submit();
	    	}else{
	    		alert(id);
	    	}
	    });

		//set focus on field when pin is clicked
	    $('#btn-pin').click(function(){
	    	setTimeout(function(){
		    	$('#pin_user').focus();    		
	    	},700);
	    });

		//open locked form action button
	    $('.btn-request-open').click(function(){
	    	if(cek_pin('transaction/cek_pin', $('#pin-spv').val())){
				$('#form-request-open').submit();
	    	}
	    });

		//check if user pressed enter after pin was filled
	    $('.pin_user').keypress(function (e) {
	    	var form = '#'+$(this).closest('form').attr('id');
	    	var obj_form = $(form);
	        if (e.which == 13) {
	        	if(cek_pin('transaction/cek_pin'), $("#pin-spv").val() ){
					obj_form.submit();
	        	}
	        }
	    });

	//================================penjualan change type=======================

		$('#form_edit_data [name=penjualan_type_id]').change(function(){
			let customer_id = $("#customer_id_select_edit")
			if ($(this).val() == 1) {
				$('#form_edit_data .po_section').show();
				$('#form_edit_data .customer_section').show();
	   			penjualan_type_id = 1;
	   			$('#customer_id_select_edit').select2("open");
	   			$('#edit-nama-keterangan').hide();
	   			$('#edit-nama-keterangan input').val('');
	   			// $('.edit-alamat-keterangan').hide();
	   			$("#edit-alamat-keterangan").attr('readonly',true);
	   			if (customer_id == '' || customer_id == 0) {
		   			$("#edit-alamat-keterangan").val('');
	   			}
	   			$('#edit-select-customer').show();
	   			$('#fp_status_edit').prop('checked',true);

			};

			if ($(this).val() == 2) {
				$('#form_edit_data .po_section').show();
				$('#form_edit_data .customer_section').show();
	   			$('#customer_id_select_edit').select2("open");
	   			$('#edit-nama-keterangan').hide();
	   			$('#edit-nama-keterangan input').val('');
	   			// $('.edit-alamat-keterangan').hide();
	   			$("#edit-alamat-keterangan").attr('readonly',true);
	   			if (customer_id == '' || customer_id == 0) {
		   			$("#edit-alamat-keterangan").val('');
	   			}
	   			$('#edit-select-customer').show();
	   			$('#fp_status_edit').prop('checked',true);
			};

			if ($(this).val() == 3) {
				$('#form_edit_data .po_section').hide();
				penjualan_type_id = 3;
	   			$('#customer_id_select_edit').val('');
	   			$('#edit-nama-keterangan').show();
	   			$("#edit-alamat-keterangan").attr('readonly',true);
	   			$("#edit-alamat-keterangan").val('');
	   			// $('.edit-alamat-keterangan').show();
	   			$('#edit-select-customer').hide();
	   			$('#fp_status_edit').prop('checked',false);
			};

			$.uniform.update($('#fp_status_edit'));
		});

		$('#form_add_data [name=penjualan_type_id]').change(function(){
			let customer_id = $("#customer_id_select").val();
			if ($(this).val() == 1) {
				$('#form_add_data .po_section').show();
				$('#form_add_data .customer_section').show();
	   			// $('#customer_id_select').select2("open");
	   			$('#add-nama-keterangan').hide();
	   			// $('.add-alamat-keterangan').hide();
	   			$("#add-alamat-keterangan").attr('readonly', true);
	   			if (customer_id == '' || customer_id == 0) {
		   			$("#add-alamat-keterangan").val('');
	   			};
	   			$('#add-select-customer').show();
	   			$('#fp_status_add').prop('checked',true);
			};

			if ($(this).val() == 2) {
				$('#form_add_data .po_section').show();
				$('#form_add_data .customer_section').show();
	   			// $('#customer_id_select').select2("open");
	   			$("#add-alamat-keterangan").val('');
	   			$('#add-nama-keterangan').hide();
	   			// $('.add-alamat-keterangan').hide();
	   			$("#add-alamat-keterangan").attr('readonly', true);
	   			if (customer_id == '' || customer_id == 0) {
		   			$("#add-alamat-keterangan").val('');
	   			};
	   			$('#add-select-customer').show();
	   			$('#fp_status_add').prop('checked',true);
	   			// alert($('#fp_status_add').is(':checked'));

			};

			if ($(this).val() == 3) {
				$('#form_add_data .po_section').hide();
				// $('#form_add_data .customer_section').hide();
	   			$('#customer_id_select').val('');
	   			$('#add-nama-keterangan').show();
	   			$("#add-alamat-keterangan").attr('readonly', false);
	   			$("#add-alamat-keterangan").val('');
	   			$('#add-select-customer').hide();
	   			$('#fp_status_add').prop('checked',false);
			};

			$.uniform.update($('#fp_status_add'));

		});


	//================================penjualan change customer=======================

		$('#customer_id_select_edit').change(function(){
			let penjualan_type_id = $("#penjualan-type-select-edit").val();
			if (penjualan_type_id == 1 || penjualan_type_id == 2) {
				if ($(this).val() == '') {
					let customer_id = $(this).val('');
					notific8('ruby', 'Customer harus dipilih');
		   			$('#customer_id_select_edit').select2("open");

				}else{
					let customer_id = $(this).val();
					let alamat = $("#customer_id_copy_edit [value='"+customer_id+"']").text();
		   			$("#edit-alamat-keterangan").val(alamat);
				}
			};
		});


		$('#customer_id_select').change(function(){
			let penjualan_type_id = $("#penjualan-type-select").val();
			if (penjualan_type_id == 1 || penjualan_type_id == 2) {
				if ($(this).val() == '') {
					let customer_id = $(this).val('');
					notific8('ruby', 'Customer harus dipilih');
		   			$('#customer_id_select').select2("open");

				}else{
					let customer_id = $(this).val();
					let alamat = $("#customer_id_copy [value='"+customer_id+"']").text();
		   			$("#add-alamat-keterangan").val(alamat);
				}
			};
		});		

	// ======================last input ================================================

			var barang_id = "<?=$barang_id;?>";
			var barang_id_last = "<?=$barang_id;?>";
			var gudang_id_last = "<?=$gudang_id_last;?>";
			var idx = "<?=$idx;?>";
			var harga_jual = "<?=number_format($harga_jual,'0',',','.');?>";

	//===========================get harga jual barang=================================

		//set harga barang if barang is changed
	    $('#barang_id_select').change(function(){
	    	var barang_id = $('#barang_id_select').val();
	   		var data = $("#barang-id-copy [value='"+barang_id+"']").text().split('??');
	   		if (penjualan_type_id == 3 || penjualan_type_id == 1) {
	   			
	   			if (barang_id != barang_id_last) {
					$('#form_add_barang [name=harga_jual]').val(change_number_format(data[1]));
	   			}else{
					$('#form_add_barang [name=harga_jual]').val(change_number_format(harga_jual));
	   			}

	   			/*if (ppn_status == 0) {
					$('#form_add_barang [name=harga_jual_no_ppn]').val(harga_jual/1.1);
	   			};*/


	   		}else{
		    	var data_st = {};
	   			data_st['barang_id'] = $('#form_add_barang [name=barang_id]').val();
		    	data_st['customer_id'] =  "<?=$customer_id;?>";
		    	var url = "transaction/get_latest_harga";
   				
	   			ajax_data_sync(url,data_st).done(function(data_respond  /*,textStatus, jqXHR*/ ){
	   				if (data_respond > 0) {
	   					harga_jual = data_respond;
						$('#form_add_barang [name=harga_jual]').val(change_number_format(data_respond));
	   				}else{
	   					harga_jual = data[1];
						$('#form_add_barang [name=harga_jual]').val(change_number_format(data[1]));
	   				}

	   				/*if (ppn_status == 0) {
						$('#form_add_barang [name=harga_jual_no_ppn]').val(harga_jual/1.1);
		   			};*/
		   		});
	   		}

			$('#form_add_barang [name=satuan]').val(data[0]);
			$('#warna_id_select').select2('open');
	    });

		//set next focus after warna selected
	    $('#warna_id_select').change(function(){
	    	$('#form_add_barang [name=harga_jual]').focus();
	    });

	    $('#warna_id_select_edit').change(function(){
	    	let form = $('#form_edit_barang');
			let stok_div = $('#stok-info-edit');
	    	$('#form_add_barang [name=harga_jual]').focus();
	    	get_qty(form, stok_div);
	    });

	    //cek harga barang berdasarkan history
	    $('.btn-cek-harga').click(function(){
	    	var data = {};
	    	data['barang_id'] = $('#form_add_barang [name=barang_id]').val();
	    	var penjualan_type_id = parseInt("<?=$penjualan_type_id;?>");
	    	var customer_id = '';
	    	if (penjualan_type_id != 3) {
	    		customer_id = "<?=$customer_id;?>";
	    	};
	    	data['customer_id'] =  customer_id;
	    	var url = 'transaction/cek_history_harga';
	    	if (data['barang_id'] != '') {
	    		var tbl = '<table>';
		    	ajax_data_sync(url,data).done(function(data_respond  /*,textStatus, jqXHR*/ ){
		    		console.log(data_respond)
		    		var isi_tbl = '';
					$.each(JSON.parse(data_respond),function(i,v){
						// alert(i +" == "+v);
						isi_tbl += "<tr>"+
							"<td>"+date_formatter(v.tanggal)+"</td>"+
							"<td> : </td>"+
							"<td>"+change_number_format(v.harga_jual)+"</td>"+
							"</tr>";
					});

					if (isi_tbl !='') {
						tbl += isi_tbl + "</table>";
				    	$('#data-harga').html(tbl);			
					}else{
				    	$('#data-harga').html("no data");
					};

		   		});
	    	}else{
	    		$('#data-harga').html("no data");
	    	}
	    	
	    });

	//====================================modal barang=============================
	
		<?if ($status == 1 && is_posisi_id() != 6) {?>
			var map = {220: false};
			$(document).keydown(function(e) {
			    if (e.keyCode in map) {
			        map[e.keyCode] = true;
			        if (map[220]) {
			        	// alert(idx);
			            $('#portlet-config-detail').modal('toggle');
			            if (idx == 1) {
			            	setTimeout(function(){
					    		$('#barang_id_select').select2("open");
					    	},700);
			            }else{
			            	cek_last_input(gudang_id_last,barang_id, harga_jual);
			            }
			        }
			    }
			}).keyup(function(e) {
			    if (e.keyCode in map) {
			        map[e.keyCode] = false;
			 
			    }
			});
		<?};?>

		$('#btn-brg-add').click(function(){
		    if (idx == '1') {
	        	setTimeout(function(){
		    		$('#barang_id_select').select2("open");
		    	},700);
	        }else{
	        	cek_last_input(gudang_id_last,barang_id, harga_jual);
	        }
	    });

	//====================================qty manage=============================    

		$(".btn-add-qty").click(function() {
			$("#qty-table input").val("")
		})

	    $(".btn-add-qty-row").click(function(){
	    	var baris = "<tr><td><input name='qty'></td>"+
									"<td><input name='jumlah_roll'></td>"+
									"<td></td></tr>";
	    	$('#qty-table').append(baris);
	    });
		
	    $("#qty-table").on('change','input',function(){
	    	var total = 0; var idx = 0; var rekap = [];
	    	var total_roll = 0;
	    	var total_stok = $('#stok-info').find('.stok-qty').html();
	    	var jumlah_roll_stok = $('#stok-info').find('.stok-roll').html();
	    	$("#qty-table [name=qty]").each(function(){
	    		var ini = $(this).closest('tr');
	    		var qty = $(this).val();
	    		var roll = ini.find('[name=jumlah_roll]').val();
	    		if (qty != '' && roll == '') {
	    			roll = 1;
	    		}else if(roll == 0){
	    			// alert('test');
	    			if (qty == '') {
	    				qty = 0;
	    			};
	    		}else if(qty == '' && roll == ''){
	    			roll = 0;
	    			qty = 0;
	    		};

	    		// alert(roll);

	    		if (roll == 0) {
		    		var subtotal = parseFloat(qty);
		    		total_roll += 0;
	    		}else{
		    		var subtotal = parseFloat(qty*roll);
		    		total_roll += parseInt(roll);
	    		};
	    		// alert(subtotal);
	    		if (qty != '' && roll != '') {
	    			rekap[idx] = qty+'??'+roll;
	    		};
	    		idx++; 
	    		// alert(total_roll);
	    		total += subtotal;
	    	});

	    	total = total.toFixed(2);
	    	total = parseFloat(total);

	    	if (total > 0 &&  total <= total_stok && total_roll <= jumlah_roll_stok) {
			    $('.jumlah_roll_total').css('color','black');
			    $('.yard_total').css('color','black');

	    		$('.btn-brg-save').attr('disabled',false);
	    	}else{
	    		if (total_roll > jumlah_roll_stok ) {
			    	$('.jumlah_roll_total').css('color','red');
			    	// alert('stok over : '+total_roll+' > '+jumlah_roll_stok);
	    		};
	    		if (total > total_stok) {
			    	$('.yard_total').css('color','red');
			    	// alert('stok over : '+total+' > '+total_stok);
	    		};
	    		$('.btn-brg-save').attr('disabled',true);
	    	}


	    	$('.yard_total').html(total.toFixed(2));
	    	$('.jumlah_roll_total').html(total_roll);
	    	$('[name=rekap_qty]').val(rekap.join('--'));
	    });

	//====================================qty edit manage=============================    
        
        // get_qty($("#form_add_barang"), $('#stok_info'));
		

		$(document).on("click",".btn-edit-qty-row",function(){
			var jml_baris = $('#qty-table-edit tbody tr').length;
	    	var baris = `<tr><td>${jml_baris+1}</td><td><input name='qty'></td>
									<td><input name='jumlah_roll'></td>
									<td></td></tr>`;

	    	$('#qty-table-edit').append(baris);
	    });

		$('.btn-qty-edit').click(function(){
			var form = $('#form_edit_barang');
			$('#qty-table-edit tbody').html('');
			var data_qty = form.find('[name=rekap_qty]').val();
			// $('#form-qty-update [name=rekap_qty]').val(data_qty);
			// $('#form-qty-update [name=id]').val($(this).closest('tr').find('.id').html());
			var data_break  = data_qty.split('--');
			console.log(data_break);
			
			var i = 0; var total = 0; var idx = 1;
			$.each(data_break, function(k,v){
				var qty = v.split('??');
				if (qty[1] == null) {
					qty[1] = 0;
				};
				total += qty[0]*qty[1]; 
				if (i == 0 ) {
					var baris = "<tr>"+
						"<td>"+idx+"</td>"+
						"<td><input name='qty' value='"+qty_float_number(qty[0])+"' class='input1'></td>"+
						"<td><input name='jumlah_roll' value='"+qty[1]+"'></td>"+
						"<td><button tabindex='-1' class='btn btn-xs blue btn-edit-qty-row'><i class='fa fa-plus'></i></button></td>"+
						"</tr>";
					idx++;
					$('#qty-table-edit tbody').append(baris);

				}else{
					var baris = "<tr>"+
						"<td>"+idx+"</td>"+
						"<td><input name='qty' value='"+qty_float_number(qty[0])+"' ></td>"+
						"<td><input name='jumlah_roll' value='"+qty[1]+"'></td>"+
						"<td></td>"+
						"</tr>";
					idx++;

					$('#qty-table-edit tbody').append(baris);
				}
				i++;
			});

			for (var i = 0; i < 5; i++) {
				var baris = "<tr>"+
						"<td>"+idx+"</td>"+
						"<td><input name='qty' value='' class='input1'></td>"+
						"<td><input name='jumlah_roll' value=''></td>"+
						"<td></td>"+
						"</tr>";
				idx++;
				$('#qty-table-edit tbody').append(baris);	
			};

			update_qty_edit();
		});


		$("#qty-table-edit").on('change',"input",function(){
	    	update_qty_edit();    	
	    });


	//====================================btn save=============================    


	    $('.btn-save').click(function(){
	    	var ini = $(this);
			btn_disabled_load(ini);
	    	var penjualan_type_id = $('#form_add_data [name=penjualan_type_id]').val();
	    	if ($('#form_add_data [name=tanggal]').val() != '') {
	    		if (penjualan_type_id == 1 || penjualan_type_id == 2 ) {
	    			if($('#form_add_data [name=customer_id]').val() != ''){
	    				$('#form_add_data').submit();
	    			}else{
					    ini.prop('disabled',false);
					    ini.html('Save');
	    				notific8('ruby','Mohon pilih customer');
	    			}
	    		}else{
	    			if ($('#form_add_data [name=tanggal]').val() != '' && $('#form_add_data [name=nama_keterangan]').val() !='') {
		    			$('#form_add_data').submit();
	    			}else{
					    ini.prop('disabled',false);
					    ini.html('Save');
	    				notific8('ruby','Mohon isi nama customer');
	    			};
	    			// $('#form_add_data').removeAttr('target');
	    			// $('#form_add_data').attr('target','');
	    		};
	    	}else{
	    		alert("Mohon isi tanggal !");
	    	};
	    });

	    $('.btn-save').dblclick(function(){});


	    var idx_submit = 1;
	    
	    $('.btn-edit-save').click(function(){
	    	let penjualan_type_id = $('#penjualan-type-select-edit').val();
	    	if ($('#form_edit_data [name=tanggal]').val() != '') {
	    		if (penjualan_type_id == 1 || penjualan_type_id == 2 ) {
	    			if($('#customer_id_select_edit').val() != ''){
	    				$('#form_edit_data').submit();
	    			}else{
	    				notific8('ruby','Mohon pilih customer');
	    			}
	    		}else{
	    			let nama = $('#edit-nama-keterangan input').val();
	    			if (nama != '') {
	    				btn_disabled_load($('.btn-edit-save'));
		    			$('#form_edit_data').submit();
	    			}else{
	    				alert("Mohon isi nama");
	    			}
	    		};
	    	}else{
	    		alert("Mohon isi tanggal !");
	    	};
	    });

	//====================================bayar==========================================
		var saldo_awal ='<?=$saldo_awal;?>';
		<?if ($penjualan_id != '' && $status == 1 ) {?>

			$('.bayar-input').dblclick(function(){
				var id_data = $(this).attr('id').split('_');
				var penjualan_type_id = "<?=$penjualan_type_id?>";
				var ini = $(this);

				if ($(this).val() == 0 || $(this).val() == '' ) {
					var g_total = reset_number_format($('.grand_total').html());
					var total_bayar = reset_number_format($('#total_bayar').html());
					var sisa = parseInt(g_total) - parseInt(total_bayar);

					if (sisa > 0) {
						if ($(this).hasClass('bayar-kredit') && penjualan_type_id != 2) {

						}else{
							$(this).val(change_number_format(sisa));
							var data = {};
							data['pembayaran_type_id'] = id_data[1];
							data['penjualan_id'] = '<?=$penjualan_id?>';
							data['amount'] = ini.val();
							var url = 'transaction/pembayaran_penjualan_update';
							update_db_bayar(url, data);
						};
						// $('#btn-close').prop('disabled',true);
					}else{
						// $('#btn-close').prop('disabled',false);
					};
					
				};
			});

			var bayar = true;
			$('#bayar-data tr td').on('change','input', function(){
				var id_data = $(this).attr('id').split('_');
				if (id_data[1] == 1) {
					var s_awal = reset_number_format(saldo_awal);
					var isi = $(this).val();
					var dp_initial = reset_number_format($('.dp_copy').html());
					var sisa = parseInt(s_awal) + dp_initial - reset_number_format(isi);
					// alert(s_awal+'+'+dp_initial+'+'+isi);
					if (sisa >= 0) {
						// alert('true');
						bayar = true;
					}else{
						$(this).val(0);
						bayar == false;
						alert('Saldo Tidak Cukup');
					};
				};

				if (bayar) {
					var data = {};
					data['pembayaran_type_id'] = id_data[1];
					data['penjualan_id'] = '<?=$penjualan_id?>';
					data['amount'] = $(this).val();
					var penjualan_type_id = "<?=$penjualan_type_id?>";
					if (data['pembayaran_type_id'] == 5 && penjualan_type_id != 2 ) {
						data['amount'] = 0;
						$(this).val(0);
						alert("Tipe bukan kredit pelanggan");
					}
					var url = 'transaction/pembayaran_penjualan_update';
					update_db_bayar(url, data);
				};
			});			

		<?};?>

		$(document).on('change', '.keterangan_bayar',function(){
			var data = {};
	    	data['penjualan_id'] =  "<?=$penjualan_id;?>";
	    	data['keterangan'] = $(this).val();
	    	var url = 'transaction/pembayaran_transfer_update';
	    	
	    	// alert(data['keterangan']);
	    	ajax_data_sync(url,data).done(function(data_respond  /*,textStatus, jqXHR*/ ){
				if (data_respond == 'OK') {
					// update_table();
				}else{
					bootbox.confirm("Error, tolong muat ulang halaman", function(respond){
						if(respond){
							window.location.reload();
						}
					});
				};
	   		});
		});

	//==============================================================================

	    <?if ($penjualan_id != '') { ?>
	    	$(document).on('change','.diskon, .ongkos_kirim, .keterangan ', function(){
	    		var value = $(this).val();
	    		if ($(this).attr('name') != 'keterangan') {
	    			value = reset_number_format(value);
	    		};
		    	var ini = $(this).closest('tr');
		    	var data = {};
		    	data['column'] = $(this).attr('name');
		    	data['penjualan_id'] =  "<?=$penjualan_id;?>";
		    	data['value'] = value;
		    	var url = 'transaction/penjualan_data_update';
		    	// update_table(ini);
		    	ajax_data_sync(url,data).done(function(data_respond  /*,textStatus, jqXHR*/ ){
					if (data_respond == 'OK') {
						update_table();
					}else{
						bootbox.confirm("Error, tolong muat ulang halaman", function(respond){
							if(respond){
								window.location.reload();
							}
						});
					};
		   		});
		    });
	    <?}?>

	    
	    $(".btn-sample-ok").click(function() {
	    	var id = "<?=$penjualan_id?>";
			var tanggal = "<?=$ori_tanggal;?>";

	    	if(cek_pin('transaction/cek_pin'), $("#pin-sample").val()) {
				$('#form-sample').submit()
	    	}
	    })

	//=====================================bayar giro=========================================
		$(".btn-save-giro").click(function(){
			if ($('#form-data-giro [name=nama_bank]').val() != '' && $('#form-data-giro [name=no_rek_bank]').val() != '' && $('#form-data-giro [name=tanggal_giro]').val() != '' && $('#form-data-giro [name=jatuh_tempo]').val() != '' && $('#form-data-giro [name=no_akun]').val() != '' ) {
				$('#form-data-giro').submit();
			}else{
				alert("mohon lengkapi data giro")
			};
		});


	//===================================bayar dp=========================================

		$('#dp_list_table').on('change','.dp-check', function(){
			let ini = $(this).closest('tr');
			var bon = reset_number_format($(".dp-nilai-bon-info").html());
			var bayar_dp = reset_number_format($('.dp-total').html());
			var sisa = bon - bayar_dp;
			// alert($(this).is(':checked'));
			if($(this).is(':checked')){
				let dp_nilai = reset_number_format(ini.find('.amount').html());
				ini.find('.amount-bayar').prop('readonly',false);
				if (sisa > dp_nilai) {
					ini.find('.amount-bayar').val(dp_nilai);
				}else{
					ini.find('.amount-bayar').val(sisa);
				}
			}else{
				ini.find('.amount-bayar').prop('readonly',true);
				ini.find('.amount-bayar').val(0);
			}
			dp_table_update();
		});

		$('#dp_list_table').on('change','.amount-bayar', function(){
			let ini = $(this).closest('tr');
			dp_table_update();
		});
		
		$('.btn-save-dp').click(function(){
			$('#form-dp').submit();
			btn_disabled_load($(this));
		});

	//========================================btn-detail============================

		$(".btn-detail-toggle").click(function(){
			$('#general-detail-table').toggle('slow');
		});

	//==========================update print===================================

	/*function reset_print(){
		$(".radio-print").prop("checked",false);
		$.uniform.update($(this));
	}*/

	//============================modal kirim surat jalan================================
	$("#btn-new-sj").click(function(){
		$("#surat-jalan-tbl").find('.qty-data-kirim').val('');
		$("#surat-jalan-tbl").find('.belum-terkirim-temp').hide().html('');
		$("#surat-jalan-tbl").find('.rol-belum-terkirim-temp').hide().html('');
		$("#surat-jalan-tbl").find('.total-subqty').html(0);
		$("#surat-jalan-tbl").find('.rol-total-subqty').html(0);
		$("#surat-jalan-tbl").find('.belum-terkirim').show();
		$("#surat-jalan-tbl").find('.rol-belum-terkirim').show();
		$("#surat-jalan-id").val('');
		$("#sj-edit-title").hide();
		$("#btn-kirim-take-all").show();

		update_surat_jalan_table();

	});

	$(".btn-edit-kirim-qty").click(function(){
		let ini = $(this).closest('tr');
		let tbl_content = '';
		let qty_sisa = ini.find('.qty-data-sisa').html().split('--');
		let data_qty_kirim = ini.find('.qty-data-kirim').val();
		let surat_jalan_id = $("#surat-jalan-id").val();
		let breakData = [];
		let qty_kirim = [];
		if (data_qty_kirim != '') {
			breakData = data_qty_kirim.split('||');
			qty_kirim = breakData[1].split('--');
		};
		let nama_barang = ini.find('td:first').html();
		let detail_id = ini.find('.detail-id').html();

		$.each(qty_sisa, function(i,v){
			let first_col = '';
			if (i==0) {
				first_col = `<td rowspan='${qty_sisa.length}'>${nama_barang} <span class='detail-id'>${detail_id}</span></td>`
			};
			let q1 = v.split('??');
			let q2 = [];
			if (typeof qty_kirim[i] !== 'undefined') {
				q2 = qty_kirim[i].split('??');
			}else{
				q2[1] = 0;
			}

			if (surat_jalan_id !='') {
				q1[1] = parseFloat(q1[1]) + parseFloat(q2[1]);
			};
			tbl_content+=`<tr>
				${first_col}
				<td class='qty-detail'>${q1[0]}</td>
				<td>${q1[1]}</td>
				<td><input title='double click untuk ambil semua roll' class='qty-kirim-input text-center' style='width:50px' type="number" min="0" max="${q1[1]}" value="${q2[1]}"></td>
				<td class='sub-total'>${q2[1]*q1[0]}</td>
			</tr>`
		});

		$("#qty-kirim-tbl tbody").html(tbl_content);
		$("#overlay-kirim").css('display','table');
		$("#overlay-kirim-content div").append(``);
		$("#overlay-kirim-content div").find('input:first').focus();
		update_table_kirim(ini.closest('table'));
	});

	$('#overlay-kirim-content').on('dblclick', '.qty-kirim-input', function(){
		let ini = $(this);
		let max = ini.attr('max');
		ini.val(max);
		update_table_kirim(ini.closest('table'));
	});

	$('#overlay-kirim-content').on('change', '.qty-kirim-input', function(){
		let ini = $(this);
		let max = ini.attr('max');
		let min = ini.attr('min');
		if (parseFloat(ini.val()) > max || parseFloat(ini.val()) < min ) {
			alert("Value salah");
			ini.val((parseFloat(ini.val()) > max ? max : min));
		}
		update_table_kirim(ini.closest('table'));
	});

	$('#get-kirim-qty').click(function(){
		let obj = $('#overlay-kirim-content div');
		let subqty = obj.find('.rekap-kirim').html();
		let rekap_qty_data = [];
		let rekap_qty = 0
		let detail_id = obj.find(".detail-id").html();
		let t_roll = 0;
		obj.find('.qty-detail').each(function(){
			let ini = $(this);
			let qty = ini.html();
			let roll = ini.closest('tr').find('.qty-kirim-input').val();
			roll = (isNaN(roll) ? 0 : roll);
			// if (roll != 0) {
				rekap_qty_data.push(qty+'??'+roll);
				rekap_qty += parseFloat(qty*roll);
				t_roll += parseFloat(roll);
			// };
		});

		$(`#qty-kirim-${detail_id}`).html(rekap_qty);
		$(`#qty-kirim-${detail_id}`).closest('tr').find(".rol-total-subqty").html(t_roll);
		$(`#rekap-kirim-${detail_id}`).val(detail_id+'||'+rekap_qty_data.join('--'));
		$("#overlay-kirim").hide();
		update_surat_jalan_table();
	});

	$('#overlay-kirim').on('click','.close-overlay-kirim',function(){
		$("#overlay-kirim").hide();
	});

	$("#btn-sj-save").click(function(){
		let valid = false;
		
		$("#surat-jalan-tbl tbody tr").each(function(){
			if ($(this).find('.total-subqty').html() != '' && parseFloat($(this).find('.total-subqty').html()) != 0) {
				valid = true;
			};
		});
		// alert(valid);
		if (valid) {
			$('#form_surat_jalan').submit();
			btn_disabled_load($(this));
		}else{
			alert('Qty kirim tidak bisa 0');
		}
	});

	$(".btn-edit-sj").click(function(){
		$("#btn-kirim-take-all").hide();
		$("#sj-edit-title").show();
		$("#surat-jalan-tbl").find('.qty-data-kirim').val('');
		$("#surat-jalan-tbl").find('.total-subqty, .rol-total-subqty').html(0);

		$("#surat-jalan-tbl").find('.belum-terkirim').each(function(){
			let point = $(this).closest('tr');
			let isi = $(this).html();
			point.find('.belum-terkirim-temp').html(isi);
			point.find('.rol-belum-terkirim-temp').html(point.find('.rol-belum-terkirim').html());
		});

		const ini = $(this).closest('tr');
		let sj_id = ini.find('.id').html();
		$('#surat-jalan-id').val(sj_id);
		let data_break = ini.find('.qty-data').html().split('=?=');
		$.each(data_break, function(i,v){
			let d_break = v.split("||");
			let detail_id = d_break[0];
			let subTotal = 0;
			let itu = $(`#rekap-kirim-${detail_id}`).closest("tr");			
			let belum_terkirim = itu.find('.belum-terkirim').html();
			let rol_belum_terkirim = itu.find('.rol-belum-terkirim').html();
			let total_roll = 0;

			$.each(d_break[1].split('--'), function(j,w){
				let x = w.split('??');
				subTotal += parseFloat(x[0]*x[1]);
				total_roll += parseFloat(x[1]);
			});
			// ini.find('.total-subqty').html(subTotal);
			$(`#rekap-kirim-${detail_id}`).val(v);
			itu.find('.total-subqty').html(subTotal);
			itu.find('.rol-total-subqty').html(total_roll);
			itu.find('.belum-terkirim-temp').show().html(parseFloat(belum_terkirim) + parseFloat(subTotal));
			itu.find('.rol-belum-terkirim-temp').show().html(parseFloat(rol_belum_terkirim) + parseFloat(total_roll));
			itu.find('.belum-terkirim').hide();
			itu.find('.rol-belum-terkirim').hide();
		});
		update_surat_jalan_table();
	});

	$("#btn-kirim-take-all").click(function(){
		$('#surat-jalan-tbl tbody tr').each(function(){
			let ini = $(this);
			let detail_id = ini.find(".detail-id").html();
			let qty_data = ini.find('.qty-data-sisa').html();
			ini.find(".qty-data-kirim").val(`${detail_id}||${qty_data}`);
			ini.find('.total-subqty').html(ini.find('.belum-terkirim').html());
			ini.find('.rol-total-subqty').html(ini.find('.rol-belum-terkirim').html());
			ini.find('.sisa-terkirim').html(0);
			ini.find('.rol-sisa-terkirim').html(0);
				
		});
		update_surat_jalan_table();
	});

	//==============================pin for create ================================

	$("#pin-create").change(function(){
		let check = cek_pin_user('transaction/cek_pin_user', $(this).val());
		// alert(check);
		if (check != "NO") {
			let bData = check.split("??");
			if (bData[0] == "OK") {
				$('.field-new').prop('disabled',false)
				$("#user-id-new").val(bData[2]);
				$("#username-new").val(bData[1]);
			}else{
				alert(bData);
			};
		}else{
			$('.field-new').prop('disabled',true)
		}
	});

	//=============================================================
	$("[name=statusAmbil]").change(function(){
		statusAmbil = $("[name=statusAmbil]:checked").val();
		// alert(statusAmbil);
   		if(statusAmbil == 'Diambil Sebagian' || statusAmbil == "Dikirim Sebagian"){
   			var currentdate = new Date(); 
			var datetime = currentdate.getDate() + "/"
			                + (currentdate.getMonth()+1)  + "/" 
			                + currentdate.getFullYear() + " "  
			                + currentdate.getHours() + ":"  
			                + currentdate.getMinutes() + ":" 
			                + currentdate.getSeconds();
   			$("#pengambilanSebagianSection").show();
			$('#pengambilanSebagian').show();	
   			$('#waktuPengambilan').val(datetime);
   			$('#waktuPengiriman').val(datetime);

   			if (statusAmbil == 'Diambil Sebagian') {
   				$("#diambilSebagianList").show();
   				$("#summaryAmbil").css('display', '');
   				$("#kirimList").hide();
   				$("#summaryKirim").css('display','none');
   			}else if (statusAmbil == 'Dikirim Sebagian'){
   				$("#diambilSebagianList").hide();
   				$("#summaryAmbil").css('display', 'none');
   				$("#kirimList").show();
   				$("#summaryKirim").css('display','');
   			}
   		}else{
   			if (statusAmbil == "Dikirim Semua") {
	   			$("#pengambilanSebagianSection").show();
	   			$("#pengambilanSebagian").hide();
   				$("#kirimList, #summaryPengiriman").show();
   				$("#diambilSebagianList, #summaryPengambilan").hide();
   			}else{
	   			$("#pengambilanSebagianSection").hide('slow');
   			};
   		};

	});

	$("#pin-close").keydown(function(event){
	    if(event.keyCode == 13) {
	      event.preventDefault();
	      return false;
	      cekPinClose();
	    }
	  });

	$("#print-faktur,#print-detail, #print-faktur-detail, .print-faktur-custom").click(function() {
		$("#pin-close").val('');
	})

	$('#pengambilanSebagian').on('mousedown', ".qtyList",function() {
		// alert('ok');
		let index = $(this).parent().children();
		index = index.index(this);
		console.log(index)
	});

	$('#pengambilanSebagian').on('mouseup', ".qtyList",function() {
		let index = $(this).parent().children();
		index = index.index(this);
		console.log(index)
	});

});
</script>

<?
/**
==================================================batas jquery=========================================
**/


$nama_toko = '';
$alamat_toko = '';
$telepon = '';
$fax = '';
$npwp = '';
$kembali=0;


if ($penjualan_id != '') {

	foreach ($data_toko as $row) {
		$nama_toko = trim($row->nama);
		$alamat_toko = trim($row->alamat.' '.$row->kota);
		$telepon = trim($row->telepon);
		$fax = trim($row->fax);
		$npwp = trim($row->NPWP);

	}

	$garis1 = "'-";
	$garis2 = "=";

	// include_once 'print_faktur.php';
	// include_once 'print_detail.php';
	// include_once 'print_faktur_detail.php';

	if ($printer_marker == 3) {
		// include_once 'print/print_faktur_3.php';
		// include_once 'print/print_detail_3.php';
		// include_once 'print/print_faktur_detail_3.php';
		// include_once 'print/print_surat_jalan_3.php';
		// include_once 'print/print_surat_jalan_noharga_3.php';
	}else{
		// include_once 'print_faktur_testing.php';
		// include_once 'print_faktur.php';
		// include_once 'print_detail.php';
		// include_once 'print_faktur_detail.php';

		// include_once 'print_surat_jalan.php';
		// include_once 'print_surat_jalan_beta.php';
		// include_once 'print_surat_jalan_noharga.php';
	}

	if (is_posisi_id() == 1) {
		// include_once 'print_font_test.php';
		// include_once 'print_testing.php';
	}


}?>

<script>

function get_qty(form, stok_div){
    var data = {};
    // alert(form);
    console.log('warna',form.find(' [name=warna_id]').val());
    data['gudang_id'] = form.find(' [name=gudang_id]').val();
    data['barang_id'] = form.find(' [name=barang_id]').val();
    data['warna_id'] = form.find(' [name=warna_id]').val();
    data['penjualan_detail_id'] = form.find(' [name=penjualan_detail_id]').val();
    // alert(form.find(' [name=penjualan_detail_id]').val());
    data['tanggal'] = form.find(' [name=tanggal]').val();
    var url = "transaction/get_qty_stock_by_barang";
    // alert(data['gudang_id']);
    ajax_data_sync(url,data).done(function(data_respond  /*,textStatus, jqXHR*/ ){
        // alert(data_respond);
        $.each(JSON.parse(data_respond), function(k,v){
            // alert(v.qty);
            stok_div.find('.stok-qty').html(v.qty);
            stok_div.find('.stok-roll').html(v.jumlah_roll);
        });
        // alert(data_respond);
        console.log(`warna_id:= ${data['warna_id']}`, data_respond);
    });
}

function dp_update_bayar(){
	var dp_total = reset_number_format($("#dp_list_table .dp-total").html());
	var g_total = $(".dp-nilai-bon-info").html();
	g_total = reset_number_format(g_total);
	$('#dp_list_table .dp-sisa-info').html(change_number_format(g_total - dp_total));
}

var populatePrinters = function(printers){
    var printerlist = $("#printer-name");
    var test = [];
    // printerlist.html('');
    for (var i in printers){
    	test.push('<option value="'+printers[i]+'">'+printers[i]+'</option>');
        // printerlist.append('<option value="'+printers[i]+'">'+printers[i]+'</option>');
    }
    // return test.join('||');
};

function update_db_bayar(url,data){
	$('#overlay-div').show();
	ajax_data_sync(url,data).done(function(data_respond  /*,textStatus, jqXHR*/ ){
		if (data_respond == 'OK') {
			$('#overlay-div').hide();
			update_bayar();
			if (data['pembayaran_type_id'] == 6 ) {
				$("#portlet-config-giro").modal('toggle');
			};
		}else{
			bootbox.confirm("Error, tolong muat ulang halaman", function(respond){
				if(respond){
					window.location.reload();
				}
			});
		};
	});
}

function dp_table_update(){
	let total_dp = 0;
	$('#dp_list_table .amount-bayar').each(function(){
		total_dp += reset_number_format($(this).val());
	});

	$('.dp-total').html(change_number_format(total_dp));

	dp_update_bayar();

}


function cek_last_input(gudang_id_last,barang_id, harga_jual){
	setTimeout(function(){
		// $('#barang_id_select').select2("open");
		$('#gudang_id_select').val(gudang_id_last);
		$('#barang_id_select').val(barang_id);
    	$('#barang_id_select, #gudang_id_select').change();
    	setTimeout(function(){
        	$('#harga_jual_add').val(harga_jual);
    	},700);

	},650);
}

function print_try(idx){
	if (idx == 1) {
		qz.websocket.connect().then(function() {
		});
	};

	var config = qz.configs.create("Nota");             // Exact printer name from OS
  	var data = ['\x1B' + '\x40',
  		<?for ($i=0; $i <= 100 ; $i++) { ?>
  			'\x1B' + '\x21' + '\x<?=str_pad($i,2,"0",STR_PAD_LEFT);?>', // em mode on
		   	'<?=$i;?>'+' : 1234567890',
		   	'\x0A',
  		<?}?>


  	];

  	qz.print(config, data).then(function() {
	   // alert("Sent data to printer");
	});
}

function save_penjualan_baru(ini){
	ini.prop('disabled',true);
	// $('#form_add_data').submit();
	setTimeout(function(){
		ini.prop('disabled',false);
	},2000);
}

function startConnection(config) {
    qz.websocket.connect().then(function() {
	   	alert("Connected!");
		find_printer();
	});

}

function find_printer(){
	qz.printers.find("Nota").then(function(found) {
	   	alert("Printer: " + found);
	 //   	var config = qz.configs.create("Nota");             // Exact printer name from OS
		// var data = ['^XA^FO50,50^ADN,36,20^FDRAW ZPL EXAMPLE^FS^XZ'];   // Raw commands (ZPL provided)
		// qz.print(config, data).then(function() {
		//    alert("Sent data to printer");
		// });
	});
}

function cek_pin(url, pin){
	// alert('test');
	var data = {};
	data['pin'] = pin;

	var result = ajax_data(url,data);
	if (result == 'OK') {
		return true;
	}else{
		return false;
	}
}

function cek_pin_user(url, pin){
	// alert('test');
	var data = {};
	data['pin'] = pin;

	var result = ajax_data(url,data);
	return result;
}

function update_bayar(){
	totalBayar = 0;
	var g_total = reset_number_format($('.grand_total').html()) ;
	$('#bayar-data tr td input').each(function(){
		if ($(this).attr('class') != 'keterangan_bayar') {
			totalBayar += reset_number_format($(this).val());			
		};
	});

	kembali = totalBayar - g_total ;
	$('#total_bayar').html(change_number_format(totalBayar) );
	$('#kembali').html(change_number_format(kembali));

	if (kembali < 0) {
		$('#kembali').css('color','red');
		$('#btn-close').prop('disabled',true);
	
	}else{
		$('#kembali').css('color','#333');
		$('#btn-close').prop('disabled',false);
	}

}

function update_qty_edit(){
    var total = 0; var idx = 0; var rekap = [];
	var total_roll = 0;
	var total_stok = $('#stok-info-edit').find('.stok-qty').html();
	var jumlah_roll_stok = $('#stok-info-edit').find('.stok-roll').html();

	$("#qty-table-edit [name=qty]").each(function(){
		var ini = $(this).closest('tr');
		var qty = $(this).val();
		var roll = ini.find('[name=jumlah_roll]').val();
		if (qty != '' && roll == '') {
			roll = 1;
		}else if(roll == 0){
			// alert('test');
			if (qty == '') {
				qty = 0;
			};
		}else if(qty == '' && roll == ''){
			roll = 0;
			qty = 0;
		}

		if (roll == 0) {
    		var subtotal = parseFloat(qty);
    		total_roll += 0;
		}else{
    		var subtotal = parseFloat(qty*roll);
    		// alert(qty+'*'+roll);
    		total_roll += parseInt(roll);
    		console.log(subtotal);
		};

		if (qty != '' && roll != '') {
			rekap[idx] = qty+'??'+roll;
		};
		idx++;  
		total += subtotal;

	});

	console.log(total.toFixed(2)+'<='+parseFloat(total_stok));
	if (total > 0 &&  parseFloat(total.toFixed(2)) <= parseFloat(total_stok) && total_roll <= jumlah_roll_stok) {
		$('.btn-brg-edit-save').attr('disabled',false);
	}else{
		$('.btn-brg-edit-save').attr('disabled',true);
	}

	$('#portlet-config-qty-edit .jumlah_roll_total').html(total_roll);
	$('#portlet-config-qty-edit .yard_total').html(total.toFixed(2));

	$('#form-qty-update [name=rekap_qty]').val(rekap.join('--'));
	$('#form_edit_barang [name=rekap_qty]').val(rekap.join('--'));


}

function update_table(){
	subtotal = 0;
	$('.subtotal').each(function(){
		subtotal+= reset_number_format($(this).html());
	});

	$('.total').html(change_number_format(subtotal));
	var diskon = reset_number_format($('.diskon').val());
	var ongkir = reset_number_format($('.ongkos_kirim').val());
	var g_total = subtotal - parseInt(diskon) + parseInt(ongkir);
	$('.grand_total').html(change_number_format(g_total));
	update_bayar();
}

function update_surat_jalan_table(){
	// alert('hm');

	let qty_blm_kirim = [];
	let roll_blm_kirim = [];
	let surat_jalan_id = $('#surat-jalan-id').val();
	$.each(qty_kirim_total, function(i,v){
		qty_kirim_total[i] =0;
		roll_kirim_total[i] = 0;

		qty_blm_kirim[i] = 0;
		roll_blm_kirim[i] = 0;
	});
	$("#surat-jalan-tbl tbody tr").each(function(){
		let ini = $(this);
		let satuan_id = ini.find('.satuan-id').html();
		let qty = ini.find('.total-subqty').html();
		let roll = ini.find('.rol-total-subqty').html();

		let qty_bk =0;
		let rol_qty_bk = 0;
		if (surat_jalan_id == '') {
			qty_bk = ini.find('.belum-terkirim').html();
			rol_qty_bk = ini.find('.rol-belum-terkirim').html();
		}else{	
			qty_bk = ini.find('.belum-terkirim-temp').html();
			rol_qty_bk = ini.find('.rol-belum-terkirim-temp').html();
		}

		qty_kirim_total[satuan_id] += parseFloat(qty);
		roll_kirim_total[satuan_id] += parseFloat(roll);

		qty_blm_kirim[satuan_id] += parseFloat(qty_bk);
		roll_blm_kirim[satuan_id] += parseFloat(rol_qty_bk);

	});

	$.each(qty_kirim_total, function(i,v){

		$(`#total-belum-terkirim-${i}`).html(qty_blm_kirim[i]);
		$(`#rol-total-belum-terkirim-${i}`).html(roll_blm_kirim[i]);

		$(`#total-terkirim-${i}`).html(v);
		$(`#rol-total-terkirim-${i}`).html(roll_kirim_total[i]);

		$(`#total-sisa-terkirim-${i}`).html(parseFloat(qty_blm_kirim[i]) - parseFloat(v));
		$(`#rol-total-sisa-terkirim-${i}`).html(parseFloat(roll_blm_kirim[i]) - parseFloat(roll_kirim_total[i]));
	});
}

function update_table_kirim(ini){
	// alert('y');
	let total = 0;
	let gTotal = 0;
	ini.find(".qty-kirim-input").each(function(){
		let ini = $(this);
		let val = parseFloat(ini.val());
		ini.attr('value',val);
		let qty_detail = ini.closest('tr').find(".qty-detail").html();
		ini.closest('tr').find('.sub-total').html(qty_detail*val);
		total += (isNaN(val) ? 0 : val);
		gTotal += (qty_detail*val);
	});

	ini.find(".rekap-kirim").html(total);
	ini.find(".total").html(gTotal);
}

function printPDF(src){
	$("#pin-close").val("");
	// alert(src);
	// $('#overlay-div').show();
		
	$("#print-pdf-dynamic").attr('src',baseurl+src);
	$('#print-pdf-dynamic').load(function(){
        // print_faktur_frame('print-pdf-dynamic');
    });
}

function print_faktur_frame(print_frame){
	// alert(print_frame);
	var getMyFrame = document.getElementById(print_frame);
    getMyFrame.focus();
    getMyFrame.contentWindow.print();
	$('#overlay-div').hide();
}


function penjualanListDetail(){
	var data = {};
    data['penjualan_id'] = penjualan_id;
    var url = "transaction/get_penjualan_detail_item";
    // alert(data['gudang_id']);
    ajax_data_sync(url,data).done(function(data_respond  /*,textStatus, jqXHR*/ ){
    	$.each(JSON.parse(data_respond), function(i,v){
	        penjualanItem.push(v);
    	})
        generateBarisDetail();
        // alert(data_respond);
        // console.log(data_respond);
    });
}


function addBarangDetail(){
	let barang_id = $('#barang_id_select').val(); 
	let warna_id = $('#warna_id_select').val(); 
	let gudang_id = $("#gudangId").val();
	let dt_a = $(`#barang-id-copy [value='${barang_id}']`).text().split('??');
	let qty = 0;
	let jumlah_roll = 0;
	let rekapQty = $('#rekapQty').val();
	$.each(rekapQty.split('--'), function(i,v){
		let dq = v.split('??');
		jumlah_roll+=parseFloat(dq[1]);
		qty+=parseFloat(dq[0]) * (parseFloat(dq[1]) == 0 ? 1 : parseFloat(dq[1]));
	});


	let data_st = {
		barang_id: barang_id,
		warna_id: warna_id,
		data_qty: rekapQty,
		gudang_id: gudang_id,
		harga_jual: reset_number_format($('#harga_jual_add').val()),
		jenis_barang: dt_a[2],
		jumlah_roll: jumlah_roll,
		nama_barang: $(`#barang_id_select [value='${barang_id}']`).text(),
		nama_gudang: $(`#gudangId [value='${gudang_id}']`).text(),
		nama_satuan: dt_a[0],
		nama_warna: $(`#warna_id_select [value='${warna_id}']`).text(),
		penjualan_id: penjualan_id,
		qty: qty,
		satuan_id : dt_a[3],
	};


	let url = "transaction/penjualan_list_detail_insert_2";
	$("#portlet-config-qty").modal('toggle');
	$("#portlet-config-detail").modal('toggle');
	$('#overlay-div').show();

	ajax_data_sync(url,data_st).done(function(data_respond  /*,textStatus, jqXHR*/ ){
		// console.log(data_respond);
		let result = data_respond.split('??');
			if (result[0]=='success') {
				data_st['id'] = result[1];
				penjualanItem.push(data_st);
				$('#overlay-div').hide();
				// $("#portlet-config-detail-edit").modal('toggle');
				generateBarisDetail();
			};
				$('#overlay-div').hide();
		// query.callback(data);
	});
}

function updateBarangDetail(){
	// btn_disabled_load($("#btnBarangUpdate"));
	$("#portlet-config-qty-edit").modal('toggle');
	$("#portlet-config-detail-edit").modal('toggle');
	$('#overlay-div').show();
	
	let id = $('#penjualan_detail_id').val(); 
	let index = $('#form_edit_barang [name=index]').val(); 
	let barang_id = $('#barang_id_select_edit').val(); 
	let warna_id = $('#warna_id_select_edit').val(); 
	let gudang_id = $("#gudangIdEdit").val();
	let dt_a = $(`#barang-id-copy [value='${barang_id}']`).text().split('??');
	let qty = 0;
	let jumlah_roll = 0;
	let rekapQty = $('#rekapQtyEdit').val();
	$.each(rekapQty.split('--'), function(i,v){
		let dq = v.split('??');
		jumlah_roll+=parseFloat(dq[1]);
		qty+=parseFloat(dq[0]) * (parseFloat(dq[1]) == 0 ? 1 : parseFloat(dq[1]));
	});
	let data_st = {
		id: id,
		barang_id: barang_id,
		warna_id: warna_id,
		gudang_id: gudang_id,
		data_qty: rekapQty,
		harga_jual: reset_number_format($('#harga_jual_edit').val()),
		jenis_barang: dt_a[2],
		jumlah_roll: jumlah_roll,
		nama_barang: $(`#barang_id_select [value='${barang_id}']`).text(),
		nama_gudang: $(`#gudangIdEdit [value='${gudang_id}']`).text(),
		nama_satuan: dt_a[0],
		nama_warna: $(`#warna_id_select [value='${warna_id}']`).text(),
		penjualan_id: penjualan_id,
		qty: qty,
		satuan_id : dt_a[3],
	};

	let url = "transaction/penjualan_list_detail_update_2";
	ajax_data_sync(url,data_st).done(function(data_respond  ,textStatus, jqXHR ){
		if (textStatus=='success') {
			penjualanItem[index] = data_st ;
			$("#overlay-div").hide();
			generateBarisDetail();
		}else{
			alert('Error, mohon coba refresh halaman');
		}
		// query.callback(data);
	});
}

function generateBarisDetail(){
	// $("#portlet-config-qty-edit").modal('toggle');
	$('#general_table tbody').html('');
	// $('#rekapBarang').html('');
	$('#rekapTable').html('');

	<?foreach ($this->satuan_list_aktif as $row) {?>
		totalQty[<?=$row->id?>] = 0;
		totalRoll[<?=$row->id?>] = 0;
		totalEach[<?=$row->id?>] = 0;
	<?}?>

	for (let i = 0 ; i < penjualanItem.length; i++) {
		item = penjualanItem[i];
		let control_btn = '';
		let dataQty = penjualanItem[i].data_qty.split('--');


		totalQty[item.satuan_id] += parseFloat(item.qty);
		totalRoll[item.satuan_id] += parseFloat(item.jumlah_roll);
		totalEach[item.satuan_id] += item.qty*item.harga_jual;
		// console.log(item.satuan_id,totalEach[item.satuan_id]);
		let clickFunc = '';
		let dataContent = '';

		if (statusPenjualan == 1 && penjualan_id != '') {
			clickFunc = `onClick="editItem('${i}')"`;
		};
		$('#general_table tbody').append(`<tr class="baris-data" ${clickFunc}>
			<td>
				${i+1} 
			</td>
			<td>
				<b class='nama_jual'>${item.nama_barang} ${item.nama_warna}</b><br/> 
				<small>${item.jenis_barang}</small> | 
				<small class="badge badge-roundless badge-${(item.nama_gudang.toString().toLowerCase() == 'toko' ? 'primary' : 'info')}"> ${item.nama_gudang}</small>
			</td>
			<!-- <td>
			</td> -->
			<td class='text-right' style='padding-right:2px;'>
				<span class='qty'>${parseFloat(item.qty)}</span><br/>
				<small class='nama_satuan'>${item.nama_satuan}</small> 	
			</td>
			<td class='text-center' style='width:10px !important;'>/</td>
			<td class='text-left' style='padding-left:2px;'>
				<span class='jumlah_roll'>${item.jumlah_roll}</span> 
			</td>
			<td>
				<span class='harga_jual'>${change_number_format(item.harga_jual)}</span>
			</td>
			<td class='text-right' style='padding-right:10px'>
				<span class='subtotal'>${change_number_format(item.qty*item.harga_jual)}</span>
			</td>
		</tr>`);

		let qtyDetail ='';
		let idxQ = 0;
		$.each(dataQty, function(x,m){
			let b = m.split('??');
			for (var j = 0; j < b[1]; j++) {
				let c = '' ;//(parseFloat(b[0]) == 100 ? 'background:yellow' : 'background:lightblue' );
				qtyDetail += `<span style='${c}' class="qty-rekap-detail">${(idxQ%5==0 && idxQ > 0 ? '<b>| </b>' : '')} ${parseFloat(b[0])}</span>`;
				idxQ++;
			};
		});
		

	};
	resetPengembalianItem();
	updateTotalBarang();
				
}

function generateRekapBarang () {
	
	$('#rekapTable').html('');

	<?foreach ($this->satuan_list_aktif as $row) {?>
		totalQty[<?=$row->id?>] = 0;
		totalRoll[<?=$row->id?>] = 0;
		totalEach[<?=$row->id?>] = 0;
	<?}?>

	// console.log('list',pengambilanDataList);
	// console.log('plist',pengambilanList);
	
	let col_idx = 0;
	let qtyList = [];
	let rollList = [];
	let dtList = [];

	for (let i = 0 ; i < penjualanItem.length; i++) {		
		// qtyList[penjualanItem[i].id] = [];
		// rollList[penjualanItem[i].id] = [];
		if (typeof qtyList[penjualanItem[i].id] === 'undefined' ) {
			qtyList[`q-${penjualanItem[i].id}`] = {};
			rollList[`q-${penjualanItem[i].id}`] = {};
			dtList[`q-${penjualanItem[i].id}`] = {};

		};
		for (var m = 0; m < pengambilanDataList.length; m++) {
			qtyList[`q-${penjualanItem[i].id}`][`${pengambilanDataList[m].id}-${pengambilanDataList[m].tipe}`] = 0;
			rollList[`q-${penjualanItem[i].id}`][`${pengambilanDataList[m].id}-${pengambilanDataList[m].tipe}`] = 0;
			dtList[`q-${penjualanItem[i].id}`][`${pengambilanDataList[m].id}-${pengambilanDataList[m].tipe}`] = [];
		};
		let jRoll = 0;
		let uList = [];
	
		if (typeof pengambilanList[`i-${penjualanItem[i].id}`] !== 'undefined') {
			$.each(pengambilanList[`i-${penjualanItem[i].id}`], function(x,v){
				let pList = v.pengambilan_list;
				console.log(`pli-${penjualanItem[i].id}`, pList);
				for (var j = 0; j < pList.length; j++) {
					qtyList[`q-${penjualanItem[i].id}`][`${pList[j].penjualan_pengambilan_id}-${pList[j].tipe}`] += parseFloat(v.qty*pList[j].jumlah_roll);
					rollList[`q-${penjualanItem[i].id}`][`${pList[j].penjualan_pengambilan_id}-${pList[j].tipe}`] += parseFloat(pList[j].jumlah_roll);
					dtList[`q-${penjualanItem[i].id}`][`${pList[j].penjualan_pengambilan_id}-${pList[j].tipe}`].push(v.qty+"??"+pList[j].jumlah_roll);
				};
			});
		};
	}

	// console.log('dtList',dtList);


	for (let i = 0 ; i < penjualanItem.length; i++) {
		item = penjualanItem[i];
		let control_btn = '';
		let dataQty = penjualanItem[i].data_qty.split('--');


		totalQty[item.satuan_id] += parseFloat(item.qty);
		totalRoll[item.satuan_id] += parseFloat(item.jumlah_roll);
		totalEach[item.satuan_id] += item.qty*item.harga_jual;
		// console.log(item.satuan_id,totalEach[item.satuan_id]);
		let clickFunc = '';
		let dataContent = '';

		
		let qtyDetail ='<table>';
		let idxQ = 0;
		$.each(dataQty, function(x,m){
			let b = m.split('??');
			for (var j = 0; j < b[1]; j++) {
				let c = '' ;//(parseFloat(b[0]) == 100 ? 'background:yellow' : 'background:lightblue' );
				if (idxQ%10==0 && idxQ > 0) {
					qtyDetail += `</tr><tr>`;
				}
				qtyDetail += `<td style="border:none;padding:0px 5px;${(idxQ+1) % 5 == 0  ? 'border-right:1px solid black;' : ''}"><span style='${c}' class="">${parseFloat(b[0])}</span></td>`;
				idxQ++;
			};
		});
		qtyDetail += '</tr></table>';

		/*$("#rekapBarang").append(`<div class="col-xs-12"> 
			<div class='card'>
				<div class='card-panel-left' style='border-right:1px solid #ddd'>
				  <div class="card-body">
				  	<h5 class='card-title'>${item.nama_barang} ${item.nama_warna}</h5>
				  	<p class='card-text'>
					  	<b style="font-size:1.2em">${parseFloat(item.qty)}</b> ${item.nama_satuan} | <b style="font-size:1.2em">${item.jumlah_roll}</b> Roll
				  	</p>
				  	<p>${qtyDetail}</p>
				  </div>
				</div>
				<div class='card-panel-right' style='border-right:1px solid #ddd'>
					<span>${change_number_format(item.qty*item.harga_jual)}</span>
				</div>
				<div class='card-panel-right'>
					TESTING
				</div>
			</div>
			</div>
		`);*/

		let sisa_detail = {};
		let rekapQty = penjualanItem[i].data_qty.split('--');
		for (var n = 0; n < rekapQty.length; n++) {
			let cQTY = rekapQty[n].split('??');
			sisa_detail[`s-${parseFloat(cQTY[0])}`] = cQTY[1];
		};


		let ambil = '';
		ambil_idx = 1;
		tipe_ambil = [];
		let sub_ambil_qty = 0;
		let sub_ambil_roll = 0;
		$.each(qtyList[`q-${penjualanItem[i].id}`], function(x,m){
			let r_idx= rollList[`q-${penjualanItem[i].id}`][x];
			if (x.includes('-1')) {
				tipe_ambil[ambil_idx] = 1;
			}else{
				tipe_ambil[ambil_idx] = 2;
			};
			let dtl = '';
			for (var n = 0; n < dtList[`q-${penjualanItem[i].id}`][x].length; n++) {
				let br = dtList[`q-${penjualanItem[i].id}`][x][n].split('??');
				sisa_detail[`s-${br[0]}`] -= parseFloat(br[1]);
				for (var o = 0; o < br[1]; o++) {
					dtl += `<span style='margin-right:10px'>${parseFloat(br[0])}</span>`;					
				};
			};
			if (r_idx != 0 && m != 0) {
				let title = (i == 0 ? `${ambil_idx}` : '');
				// <h5 class='card-title'>${title}</h5>
				ambil += `<td>
						<p class='card-text'>
							Q: <b>${m}</b><br/>
							R: <b>${r_idx}</b>
						</p>
						<p>
							<a>rinci <i class='fa fa-caret-right'></i> </a>
							<span hidden>${dtl}</hidden>
						</p>
					</td>`;
				sub_ambil_qty += parseFloat(m);	
				sub_ambil_roll += parseFloat(r_idx);	
			}else{
				ambil += "<td></td>";
			};
			ambil_idx++;
		});


		if (ambil != '' ) {
			// console.log('sisa',sisa_detail);

			let dtl = '';

			$.each(sisa_detail, function(j,l) {
				// console.log(j,l);
				if (parseFloat(l) != 0) {
					for (var o = 0; o < l; o++) {
						// dtl += `<span style='margin-right:10px'>${j.replace('s-','')}</span>`;					
					};
				}
			})

			if (dtl == '') {
				// dtl += `<span style='margin-right:10px'>-</span>`;
			};
			// console.log('dtl',dtl);

			ambil += `<td>
				<h5 class='card-title'>${(i == 0 ? '' : '')}</h5>
				<p>
					Q: <b>${parseFloat(item.qty) - parseFloat(sub_ambil_qty)}</b><br/>
					R: <b>${parseFloat(item.jumlah_roll) - parseFloat(sub_ambil_roll)}</b>
				</p>
				<p>
					<a>rinci <i class='fa fa-caret-right'></i> </a>
					<span hidden:>${dtl}</span>
				</p>
				
			</td>`
		};
		// console.log(ambil);

		// for (var m = 0; m < pengambilanDataList.length; m++) {
		// 	let q_idx = qtyList[`q-${penjualanItem[i].id}`][pengambilanDataList[m].id];
		// 	let r_idx = rollList[`q-${penjualanItem[i].id}`][pengambilanDataList[m].id];
		// 	ambil += `<td>
		// 			${pengambilanDataList[m].id}<br/>
		// 			${q_idx} | ${r_idx} 
		// 		</td>`;
		// }



		if (i==0) {
			let col = ''
			for (var j = 0; j < ambil_idx; j++) {
				let bg = (tipe_ambil[j+1] == 1 ? "#ffdede" : "#e1ffde");
				col += `<th style="background-color:${bg}">${(j+1 == ambil_idx ? 'SISA' : j+1 )}</th>`
			};

			$("#rekapTable").append(`<tr>
				<th rowspan='2'>REKAP BARANG</th>
				<th colspan='${ambil_idx}'>AMBIL/KIRIM</th>
				</tr>
				<tr>
					${col}
				</tr>`);			
		};

		if (ambil == '' && i==0) {
			ambil = `<td style="text-align:center;vertical-align:middle" rowspan="${penjualanItem.length}">${statusAmbil}</td>`
		};


		$("#rekapTable").append(`<tr>
				<td style="width:${ambil_idx > 4 ? '55' : (ambil_idx > 2 ? '60' : '70' )}%">
					<div style='position:relative'>
					  	<h5 class='card-title'>${item.nama_barang} ${item.nama_warna}</h5>
					  	<p class='card-text'>
						  	<b style="font-size:1.2em">${parseFloat(item.qty)}</b> ${item.nama_satuan} | <b style="font-size:1.2em">${item.jumlah_roll}</b> Roll
					  	</p>
					  	<p>${qtyDetail}</p>

						<h5 class='card-title' style='position:absolute; margin-top:0px;top:0px; right:10px;'>${change_number_format(item.qty*item.harga_jual)}</h5>
					</div>
			 	</td>
			 	${ambil}
			</tr>
		`);

	};
	resetPengembalianItem();
	updateTotalBarang();
}


function updateTotalBarang(){
	grandTotal = 0;
	g_total_roll = 0;
	$.each(totalQty,function(i,v){
		if (i != 0 && v != 0 && typeof totalEach[i] !== 'undefined') {
			grandTotal += parseFloat(totalEach[i]);
			g_total_roll += parseFloat(totalRoll[i]);
			$(`#totalQty-${i}`).text(v);
			$(`#totalRoll-${i}`).text(totalRoll[i]);
			$(`#total-${i}`).text(change_number_format(totalEach[i]));
		}else if(v == 0){
			$(`#totalQty-${i}`).closest('tr').hide();
		};
	});

	$(".grand_total").text(change_number_format(grandTotal));
	$(".g-total-roll").text(change_number_format(g_total_roll));
	update_bayar();
}

function editItem(itemIndex){
	// console.log('edit item','status:'+statusPenjualan, 'idx'+itemIndex);

	if (statusPenjualan == 1) {
		$("#portlet-config-detail-edit").modal('toggle');

		form = $('#form_edit_barang');
		stok_div = $('#stok-info-edit');

		item = penjualanItem[itemIndex];

		form.find("[name=index]").val(itemIndex);
		form.find("[name=penjualan_detail_id]").val(item.id);
		form.find("[name=barang_id]").val(item.barang_id);
		form.find("[name=warna_id]").val(item.warna_id);
		form.find("[name=gudang_id]").val(item.gudang_id);
		form.find("[name=harga_jual]").val(item.harga_jual);
		form.find("[name=satuan]").val(item.satuan_id);
 		form.find("[name=rekap_qty]").val(item.data_qty);
		// alert(ini.find('.barang_id').html());

		form.find("[name=barang_id]").change();
		form.find("[name=warna_id]").change();

		get_qty(form, stok_div);
	};

}

function removeItem(){
	let index = $("#form_edit_barang [name=index]").val();
	let data = {};
	id = $("#form_edit_barang [name=penjualan_detail_id]").val();
	data['id'] = id;
	bootbox.confirm("Yakin mengahapus item ini ?", function(respond){
		if (respond) {
			$("#portlet-config-detail-edit").modal("toggle");
			$("#overlay-div").show();
			let url='transaction/penjualan_list_detail_remove';
		    ajax_data_sync(url,data).done(function(data_respond  ,textStatus, jqXHR ){
		    	if (textStatus=='success') {
		    		penjualanItem.splice(index,1);
					$("#overlay-div").hide();
					generateBarisDetail();
		    	}else{
		    		alert('Error, mohon coba refresh halaman')
		    	}
		    });
		};
	});
}

function generateCloseForm(){
	$("#btn-close-penjualan").prop('disabled',true);
	$("#pengambilanSebagian tbody").html("");
	$("[name=statusAmbil][value='"+statusAmbil+"']").prop('checked',true).change();
	// $.uniform.update($("[name=statusAmbil]"));s
	let kembali = reset_number_format($('#kembali').html());
	let g_total = reset_number_format($('.grand_total').html());
	if (g_total <= 0) {
		bootbox.confirm("Total nota 0, Yakin untuk melanjutkan", function(respond){
			if (respond) {
				// window.location.replace(baseurl+'transaction/penjualan_list_close?id='+id+"&tanggal="+tanggal);
			};
		});
	}else{
		if (kembali < 0 ) {
			bootbox.alert('Kembali tidak boleh minus');
			return false;
		}
	}

	$("#portlet-config-close").modal("toggle");

	if (statusAmbil == 'Diambil Sebagian') {
		getDaftarPengambilan();
	};
	buildTablePengambilan();
	
}

function getDaftarPengambilan(){
	let data = {
		penjualan_id:penjualan_id
	}

	$("#btn-print-all").html("");
	const url = 'transaction/get_penjualan_pengambilan_data';
	ajax_data_sync(url,data).done(function(data_respond  ,textStatus, jqXHR ){
		$("#diambilSebagianList").find('.data-item-diambil').remove();
		$("#kirimList").find('.data-item-diambil').remove();
		pengambilanDataList = data_respond;
		// console.log('pdl',pengambilanDataList);
		for (var i = 0; i < data_respond.length; i++) {
			let h = data_respond[i]
			if (h.tipe == 1) {
				$("#diambilSebagianList").prepend(`<li class='data-item-diambil' onClick="highlightAmbil('${h.id}')">
					Tgl : ${h.waktu_diambil},<br/> 
					oleh: <span class='keterangan' id="span-keterangan-${h.id}">${h.keterangan}</span><input id="keterangan-input-${h.id}" value="${h.keterangan}" hidden><br/> 
					<button class='btn btn-xs red' onClick="removePengambilan(${h.id})" id="remove-ambil-${h.id}" style='display:none'><i class='fa fa-trash-o'></i></button>
					<button class='btn btn-xs green' onClick="summaryPengambilan(${h.id})" id="update-ambil-${h.id}"  style='display:none'><i class='fa fa-save'></i></button>
					<button class='btn btn-xs default' id="cancel-ambil-${h.id}" style='display:none'>cancel</button>
					</li>`);
			}else{
				$("#kirimList").prepend(`<li class='data-item-diambil' onClick="highlightKirim('${h.id}')">
					Tgl : ${h.waktu_diambil}, <br/>NO : <b class='no-sj'>${h.no_sj}</b>,<br/>
					via: <span class='keterangan' id="span-keterangan-kirim-${h.id}">${h.keterangan}<input id="keterangan-input-kirim-${h.id}" value="${h.keterangan}" hidden><br/></span>
					<button class='btn btn-xs red' onClick="removePengiriman(${h.id})" id="remove-kirim-${h.id}" style='display:none'><i class='fa fa-trash-o'></i></button>
					<button class='btn btn-xs green' onClick="summaryPengiriman(${h.id})" id="update-kirim-${h.id}"  style='display:none'><i class='fa fa-save'></i></button>
					<button class='btn btn-xs default' id="cancel-kirim-${h.id}" style='display:none'>cancel</button>
					
					</li>`);	

				$("#btn-print-all").append(`<button href="#portlet-config-pinclose" data-toggle="modal"  
					class="btn green btn-lg btn-active " style="margin-right:20px"
					onclick="printPDF('transaction/penjualan_print?penjualan_id=<?=$penjualan_id?>&type=99&surat_jalan_id=${h.id}')" class='print-faktur-custom'>
					Faktur + Detail + <br/>${h.no_sj}</button>`);
			};
		};
		generateRekapBarang();
	});
}

function buildTablePengambilan(){
	$("#pengambilanSebagian tbody").html('');
	// console.log(pengambilanList);plist
	for (var i = 0; i < penjualanItem.length; i++) {
		let rekapQty = penjualanItem[i].data_qty;
		let qtyList = '';
		let qtyListAmbil = '';
		let qtyListKirim = '';
		let jRoll = 0;
		let uList = [];
		let qtyIdx = 0;

		if (typeof pengambilanList[`i-${penjualanItem[i].id}`] !== 'undefined') {
			$.each(pengambilanList[`i-${penjualanItem[i].id}`], function(x,v){
				diambil = v.jumlah_roll_ambil;
				jRoll += parseFloat(diambil);
				let pList = v.pengambilan_list;

				let idxA = [];
				let idxB = [];
				let tiA = [];
				for (var j = 0; j < pList.length; j++) {
					if (pList[j].tipe == 1) {
						for (var k = 0; k < pList[j].jumlah_roll; k++) {
							idxA.push(pList[j].penjualan_pengambilan_id);
							idxB.push(pList[j].penjualan_pengambilan_detail_id);
							tiA.push(pList[j].tipe);							
						};
					};
				};
				// console.log('p',idxA,tiA);
				for (var j = 0; j < diambil; j++) {
					if (tiA[j]== 1) {
						qtyList += `<span style='margin-right' onClick="chooseQty(${penjualanItem[i].jumlah_roll},${i},this)" 
							data-detail-id="${penjualanItem[i].id}" 
							data-ambil-detail-id="${idxB[j]}" 
							data-ambil-id="${idxA[j]}" 
							class="${(tiA[j]==1?'qtySudahAmbil' : 'qtySudahKirim')} qtyList">${parseFloat(v.qty)}</span>`;
						qtyIdx++;
						if(qtyIdx % 5 == 0) qtyList += `<b> | </b>`;
						if(qtyIdx % 10 == 0) qtyList += '<br/>';
					}
				};
			});
			$.each(pengambilanList[`i-${penjualanItem[i].id}`], function(x,v){
				diambil = v.jumlah_roll_ambil;
				jRoll += parseFloat(diambil);
				let pList = v.pengambilan_list;
				let idxA = [];
				let tiA = [];
				let tiB = [];
				for (var j = 0; j < pList.length; j++) {
					if (pList[j].tipe == 2) {
						for (var k = 0; k < pList[j].jumlah_roll; k++) {
							idxA.push(pList[j].penjualan_pengambilan_id);
							tiA.push(pList[j].tipe);							
							idxB.push(pList[j].penjualan_pengambilan_detail_id);
						};
					};
				};
				// console.log('p',idxA,tiA);
				for (var j = 0; j < diambil; j++) {
					if (tiA[j]== 2) {
						qtyList += `<span style='margin-right' onClick="chooseQty(${penjualanItem[i].jumlah_roll},${i},this)" 
							data-detail-id="${penjualanItem[i].id}"  
							data-kirim-id="${idxA[j]}" 
							class="${(tiA[j]==1?'qtySudahAmbil' : 'qtySudahKirim')} qtyList">${parseFloat(v.qty)}</span>`;
						qtyIdx++;
						if(qtyIdx % 5 == 0) qtyList += `<b> | </b>`;
						if(qtyIdx % 10 == 0) qtyList += '<br/>';
					}
				};
			});
		};

		$.each(rekapQty.split('--'), function(x,v){
			let dq = v.split('??');
			let diambil = 0;
			if (typeof pengambilanList[`i-${penjualanItem[i].id}`] !== 'undefined' && typeof pengambilanList[`i-${penjualanItem[i].id}`][`q-${parseFloat(dq[0])}`] !== 'undefined') {
				let h = pengambilanList[`i-${penjualanItem[i].id}`][`q-${parseFloat(dq[0])}`];
				diambil = h.jumlah_roll_ambil;
				// console.log('diambil',diambil,h);
			}
			let tTemp = parseFloat(dq[1]) - diambil
			for (var j = 0; j < tTemp ; j++) {				
				qtyList += `<span style='margin-right' class='qtyAmbil qtyList' onClick="chooseQty(${penjualanItem[i].jumlah_roll},${i},this)">${parseFloat(dq[0])}</span>`;
					qtyIdx++;
					if(qtyIdx % 5 == 0) qtyList += `<b> | </b>`;
					if(qtyIdx % 10 == 0) qtyList += '<br/>';	
			};
		});
		$("#pengambilanSebagian tbody").append(`<tr>
				<td>${penjualanItem[i].nama_barang} ${penjualanItem[i].nama_warna}</td>
				<td>${penjualanItem[i].jumlah_roll}</td>
				<td>${qtyList}</td>
				<td>| <label><input type="checkbox" class='qtyAmbilBarisAll' id="qtyAmbil-${penjualanItem[i].id}"  onClick="chooseQtyBaris(${i},'${penjualanItem[i].id}')">All</label></td>
			</tr>`)				
	};
}



function chooseQty(jumlah_roll,index,ini){
	$(ini).toggleClass("qtyAmbilSelected");

	let qtyIndex;
	for (var i = 0; i < pengambilanItem[index].qtyList.length; i++) {
		if (pengambilanItem[index].qtyList[i].qty == $(ini).text() ) {
			qtyIndex=i;
		};
	};
	if ($(ini).hasClass("qtyAmbilSelected")) {
		$(ini).closest('tr').find('.qtyAmbilBarisAll').prop("checked", false);
		pengambilanItem[index].qtyList[qtyIndex].jumlah_roll++;
	}else{
		pengambilanItem[index].qtyList[qtyIndex].jumlah_roll--;
	};
	if(jumlah_roll == $(ini).closest('tr').find('.qtyAmbilSelected').length ){
		$(ini).closest('tr').find('.qtyAmbilBarisAll').prop("checked", true);
	}

}

function chooseQtyBaris(index, id){
	let ini = $(`#qtyAmbil-${id}`); 
	let idxTemp = [];
	for (var i = 0; i < pengambilanItem[index].qtyList.length; i++) {
		pengambilanItem[index].qtyList[i].jumlah_roll = 0;
		idxTemp[pengambilanItem[index].qtyList[i].qty] = i;
	};
	if (ini.is(':checked')) {
		ini.closest('tr').find('.qtyAmbil').addClass("qtyAmbilSelected");
		$(ini).closest('tr').find('.qtyAmbil').each(function(){
			pengambilanItem[index].qtyList[idxTemp[$(this).text()]].jumlah_roll++;
		});
	}else{
		ini.closest('tr').find('.qtyAmbil').removeClass("qtyAmbilSelected");
	}
	// summaryPengambilan();
}

function summaryPengambilan(id){
	let pengambilanTemp = [];
	for (var i = 0; i < pengambilanItem.length; i++) {
		let jRoll = 0;
		let jQty = pengambilanItem[i].qtyList;
		for (var j = 0; j < jQty.length; j++) {
			jRoll += jQty[j].jumlah_roll;
		};
		if (jRoll > 0) {
			pengambilanTemp.push(pengambilanItem[i]);
		};
	};
	pengambilanData['barang_list'] = pengambilanTemp;
	pengambilanData['statusAmbil'] = statusAmbil;
	pengambilanData['id'] = id;
	pengambilanData['user_id'] = 1;
	if (id=='') {
		pengambilanData['keterangan'] = $("#namaPengambil").val();
	}else{
		pengambilanData['keterangan'] = $(`#keterangan-input-${id}`).val();
	};
	pengambilanData['tanggal'] = "<?=date('d/m/Y');?>"
	if (statusAmbil == 'Diambil Sebagian') {
		for (var i = 0; i < pengambilanItem.length; i++) {
			totalAmbil = 0;
		};
	};

	if (pengambilanData.barang_list.length > 0) {
		let url = "transaction/penjualan_pengambilan_update";
		ajax_data_sync(url,pengambilanData).done(function(data_respond  ,textStatus, jqXHR ){
			console.log('ambilData',data_respond);
			if (textStatus == 'success') {
				generatePengambilanData();
			};
		});
		$('#barisAmbilBaru').show();
	}else{
		alert('Tidak ada barang yang dipilih');
	};



	/**/

	// $("#pengambilanSebagian tbody tr").each(function(){
	// 	let qtyTemp = {};
	// 	$(this).find(".qtyAmbilSelected").function(){
	// 		qtyTemp[]
	// 	}

	// });
}

function summaryPengiriman(){
	let pengambilanTemp = [];
	for (var i = 0; i < pengambilanItem.length; i++) {
		let jRoll = 0;
		let jQty = pengambilanItem[i].qtyList;
		for (var j = 0; j < jQty.length; j++) {
			jRoll += jQty[j].jumlah_roll;
		};
		if (jRoll > 0) {
			pengambilanTemp.push(pengambilanItem[i]);
		};
	};
	pengirimanData['surat_jalan_type_id'] = 1;
	pengirimanData['tanggal'] = "<?=date('d/m/Y');?>"
	pengirimanData['alamat_log'] = $("#alamat-kirim-id").val();
	pengirimanData['barang_list'] = pengambilanTemp;
	pengirimanData['user_id'] = 1;
	pengirimanData['keterangan'] = $("#pengirimanVia").val();
	if (statusAmbil == 'Dikirim Sebagian') {
		for (var i = 0; i < pengambilanItem.length; i++) {
			totalAmbil = 0;
		};
	};

	if (pengirimanData.barang_list.length > 0) {
		let url = "transaction/surat_jalan_insert_ajax";
		ajax_data_sync(url,pengirimanData).done(function(data_respond  ,textStatus, jqXHR ){
			// console.log(data_respond);
			if (textStatus == 'success') {
				generatePengambilanData();
			};

		});
	}else{
		alert('Tidak ada barang yang dipilih');
	};

}


function resetPengembalianItem(){
	for (let i = 0 ; i < penjualanItem.length; i++) {

		let dataQty = penjualanItem[i].data_qty.split('--');
		pengambilanItem[i]={
			penjualan_detail_id:penjualanItem[i].id,
			penjualan_pengambilan_detail_id:'',
		};
		pengambilanItem[i]["qtyList"]=[]
		$.each(dataQty, function(x,v){
			let dQty=v.split('??');
			pengambilanItem[i]["qtyList"].push({"qty":parseFloat(dQty[0]),"jumlah_roll":0});
		})
	}
}

function generatePengambilanData(){

	let data = {}; 
	data['penjualan_id'] = penjualan_id;
	const url = "transaction/get_penjualan_pengambilan_data_by_qty";
    ajax_data_sync(url,data).done(function(data_respond  ,textStatus, jqXHR ){
    	if (textStatus == 'success') {
    		buildPengambilanData(data_respond);
    		buildTablePengambilan();
			getDaftarPengambilan();
			resetPengembalianItem();
    	}else{};
    });

}

function buildPengambilanData(data_respond){
	pengambilanList = [];
	console.log('buildData',data_respond);
	for (var i = 0; i < data_respond.length; i++) {
		let dt = data_respond[i]
		if (typeof pengambilanList[`i-${dt.penjualan_detail_id}`] === "undefined") {
			pengambilanList[`i-${dt.penjualan_detail_id}`] = {};
		};

		pengambilanList[`i-${dt.penjualan_detail_id}`][`q-${dt.qty}`] = {
			qty:dt.qty,
			jumlah_roll_ambil : dt.jumlah_roll_ambil,
			pengambilan_list : dt.pengambilan_list
		};
	};
}

function highlightAmbil(ambilId){

	$('#barisKirimBaru').show();


	if (editAmbilId != ambilId) {

		editAmbilId = ambilId;
		resetPengembalianItem();

		let itemDetail = [];
		$('.qtySudahAmbil').css({'background-color':'#ffdede','cursor':'no-drop'});
		$('.qtySudahKirim').css({'background-color':'#e1ffde','cursor':'no-drop'});
		$(`#pengambilanSebagian`).find(`.qtyList`).removeClass("qtyAmbilSelected");
		$(`[data-ambil-id=${ambilId}]`).addClass("qtyAmbilSelected");
		$(`[data-ambil-id=${ambilId}]`).css({"background-color":"#ff0","cursor":"pointer"});
		$("#diambilSebagianList").find(".btn, input").css("display","none");
		$(`#remove-ambil-${ambilId}`).css("display","");
		$(`#update-ambil-${ambilId}`).css("display","");
		$(`#cancel-ambil-${ambilId}`).css("display","");
		$(`#span-keterangan-${ambilId}`).hide();
		$(`#keterangan-input-${ambilId}`).show();
		$('#barisKirimBaru').hide();
		$(`[data-ambil-id=${ambilId}]`).each(function(){
			let detail_id = $(this).attr("data-detail-id");
			let qty = $(this).text();
			if (typeof itemDetail[`i-${detail_id}`] === 'undefined') {
				itemDetail[`i-${detail_id}`] = [];
			};
			if (typeof itemDetail[`i-${detail_id}`][`q-${qty}`] === 'undefined') {
				itemDetail[`i-${detail_id}`][`q-${qty}`] = 1;
			}else{
				itemDetail[`i-${detail_id}`][`q-${qty}`]++;
			};
		});

		for (var i = 0; i < pengambilanItem.length; i++) {
			let idx = pengambilanItem[i];
			for (var j = 0; j < idx.qtyList.length; j++) {
				if (typeof itemDetail[`i-${idx.penjualan_detail_id}`] !== 'undefined' && typeof itemDetail[`i-${idx.penjualan_detail_id}`][`q-${idx.qtyList[j].qty}`] !== 'undefined' ) {
					idx.qtyList[j].jumlah_roll++;
				};
			};
		};
		console.log('pI',pengambilanItem);
	};


	// let qtyIndex;
	// for (var i = 0; i < pengambilanItem[index].qtyList.length; i++) {
	// 	if (pengambilanItem[index].qtyList[i].qty == $(ini).text() ) {
	// 		qtyIndex=i;
	// 	};
	// };
	// if ($(ini).hasClass("qtyAmbilSelected")) {
	// 	$(ini).closest('tr').find('.qtyAmbilBarisAll').prop("checked", false);
	// 	pengambilanItem[index].qtyList[qtyIndex].jumlah_roll++;
	// }else{
	// 	pengambilanItem[index].qtyList[qtyIndex].jumlah_roll--;
	// };
	// if(jumlah_roll == $(ini).closest('tr').find('.qtyAmbilSelected').length ){
	// 	$(ini).closest('tr').find('.qtyAmbilBarisAll').prop("checked", true);
	// }
}


function highlightKirim(ambilId){

	$('#barisAmbilBaru').show();
	if (editKirimlId != ambilId) {

		editKirimId = ambilId;
		resetPengembalianItem();

		let itemDetail = [];
		$('.qtySudahAmbil').css({'background-color':'#ffdede','cursor':'no-drop'});
		$('.qtySudahKirim').css({'background-color':'#e1ffde','cursor':'no-drop'});
		$(`#pengambilanSebagian`).find(`.qtyList`).removeClass("qtyAmbilSelected");
		$(`[data-ambil-id=${ambilId}]`).addClass("qtyAmbilSelected");
		$(`[data-ambil-id=${ambilId}]`).css({"background-color":"#ff0","cursor":"pointer"});
		$("#diambilSebagianList").find(".btn, input").css("display","none");
		$(`#remove-kirim-${ambilId}`).css("display","");
		$(`#update-kirim-${ambilId}`).css("display","");
		$(`#cancel-kirim-${ambilId}`).css("display","");
		$(`#span-keterangan-kirim-${ambilId}`).hide();
		$(`#keterangan-input-kirim-${ambilId}`).show();
		$('#barisAmbilBaru').hide();
		$(`[data-kirim-id=${ambilId}]`).each(function(){
			let detail_id = $(this).attr("data-detail-id");
			let qty = $(this).text();
			if (typeof itemDetail[`i-${detail_id}`] === 'undefined') {
				itemDetail[`i-${detail_id}`] = [];
			};
			if (typeof itemDetail[`i-${detail_id}`][`q-${qty}`] === 'undefined') {
				itemDetail[`i-${detail_id}`][`q-${qty}`] = 1;
			}else{
				itemDetail[`i-${detail_id}`][`q-${qty}`]++;
			};
		});

		for (var i = 0; i < pengambilanItem.length; i++) {
			let idx = pengambilanItem[i];
			for (var j = 0; j < idx.qtyList.length; j++) {
				if (typeof itemDetail[`i-${idx.penjualan_detail_id}`] !== 'undefined' && typeof itemDetail[`i-${idx.penjualan_detail_id}`][`q-${idx.qtyList[j].qty}`] !== 'undefined' ) {
					idx.qtyList[j].jumlah_roll++;
				};
			};
		};
	};

}

/**
===================================cek pin close============================
**/
function cekPinClose(){
	$("#overlay-div").show();
	let dt = cek_pin_user('transaction/cek_pin_user', $('#pin-close').val()).toString().split('??');
	if (dt[0] == "OK") {
		if (statusPenjualan == 1) {
			closeForm();
		};
		$('#closedBy').text(dt[1]);
		pengambilanData['user_id'] = dt[0];
		// $("#btn-close-penjualan").prop('disabled',false);
		$("#portlet-config-pinclose").modal("toggle");
	    // print_faktur_frame('print-pdf-dynamic');
	    printRAWPrint();

	}else{
		$("#overlay-div").hide();
		alert('Salah PIN');
		// $("#btn-close-penjualan").prop('disabled',true);
	};

}

function closeForm(){
	// $("#overlay-div").show();

	let id = penjualan_id;
	$("#overlay-div").show();

	let data = {}; 
	data['id'] = penjualan_id;
	data['tanggal'] = "<?=$ori_tanggal?>";
	const url = "transaction/penjualan_list_close_2";
    ajax_data_sync(url,data).done(function(data_respond  ,textStatus, jqXHR ){
    	if (textStatus == 'success') {
			statusPenjualan = 0;
   //  		$(".no-faktur-lengkap").text(data_respond);
   //  		$("#general_table tbody").find("tr").removeClass("baris-data");
			// $("#bayar-data input").prop("disabled", true);
			// $("#btn-close").hide();
			// // $(".btn-print").show();
			// $("#btn-pin").css('display','block');
			// $("#btn-brg-add").hide();
			// $("#icon-lock").removeClass("fa-unlock").addClass("fa-lock");
			// $('#btn-edit-data').hide();
			$("#overlay-div").hide();
			// printRAWPrint();
    	};
    });

}

function removePengambilan(id) {
	bootbox.confirm("Yakin mengahapus data ini ? ", function(respond) {
		let data = {}; 
		data['id'] = id;
		const url = "transaction/pengambilan_remove";
	    ajax_data_sync(url,data).done(function(data_respond  ,textStatus, jqXHR ){
	    	if (textStatus == 'success') {
	    		generatePengambilanData();				
	    	};
	    });
	})
}

function printRAW(idx){
	printRAWIdx = idx;
}

function printRAWPrint(){
	$("#print-pdf-dynamic").attr('src',baseurl+`transaction/penjualan_print_page?id=${penjualan_id}&action=${printRAWIdx}`);
}


</script>
