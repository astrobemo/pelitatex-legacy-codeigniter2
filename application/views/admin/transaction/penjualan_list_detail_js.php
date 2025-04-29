	<link href="<?=base_url('assets/global/plugins/bootstrap-modal/css/bootstrap-modal.css');?>" rel="stylesheet" type="text/css"/>
	<?php echo link_tag('assets/global/plugins/bootstrap-datepicker/css/datepicker3.css'); ?>
	<style type="text/css">
		body{
			/*background: #24252a;*/
			/*background: #34495e;*/
			background-color: #ddd;
			color: #fff;
		}
		
		img{
			max-width: 100%;
		}

		a{
			text-decoration: none;
			color: #fff;
		}
		.margin-rl-10{
			margin: 0px 10px;
		}

		.no-faktur-lengkap{
			font-size: 2em;
		}

		.invoice-info-container{
			padding:10px 20px;
			float: right;
			text-align: right;
		}

		.toko-info-container{
			padding:10px 20px;
			float: left;
			text-align: left;
		}

		.wrapper{
			display: grid;
			width: 100%;
			grid-template-columns: 30px repeat(4, 1fr);
			/*grid-template-rows : 0.3fr 1fr 4fr 0.1fr;*/
			grid-template-rows : 35px 140px 535px 40px;
		}

		.item-wrapper{
			display: grid;
			width: 100%;
			grid-template-columns: repeat(5, 1fr);
			/*grid-template-rows : 0.3fr 1fr 4fr 0.1fr;*/
			/*grid-template-rows : 60px;*/
			text-align: center;
			font-size: 1.2em;
			color: black;
		}

		.item-wrapper div{
			background: #eee;
			border: 1px solid #171717;
			height: 50px;
			padding: 10px;
		}
		
		.sidebar{
			grid-row:1/5;
			background: #364150;
		}


		.tab-section{
			grid-row:1/1;
			grid-column:2/6;
		}

		.trx-info-container{
			background: #364150;
			grid-column-start: 2;
			grid-column-end: 6;
			padding: 2px 10px;
			text-transform: uppercase;
		}

		.konten-wrapper{
			grid-column: 2/6;
			grid-row: 3;
			display: grid;
			grid-gap :15px;
			padding: 15px;
			grid-template-columns: repeat(4, 1fr);
			overflow: hidden;

		}

		.input-section{
			position: relative;
			padding: 7px;
			background-color: #fff;
			color: #000;
			grid-column:1/2;
			grid-row:1;
		}

		.konten{
			padding: 10px;
			background-color: #fff;
			color: #000;
			grid-column:2/6;
			grid-row:1;
		}

		.footer{
			background-color: #171717;
			grid-column:2/6;
			grid-row:4/4;
			padding: 5px 10px;
		}

		.select2-container .select2-choice{
			height: 35px;
			background: none !important;
		}

		/*========================================*/

		#main-form{
			position: absolute;
			top: 7px;
			padding: 7px;
			width: 95%;
		}

		#qty-form{
			position: absolute;
			padding: 7px;
			padding-top: 14px;
			top: 0px;
			left: 7px;
			width: 95%;
			background: #fff;
			left: -100%;
			height: 100%;
			overflow: auto;
		}

		#qty-table input{
			width: 50px;
		}

		#qty-sum-table{
			position:absolute; 
			right:5px; 
			top:60px;
			font-size: 1.2em;
			text-align: center;
		}

		#qty-sum-table tr td{
			width: 50px;
			border:1px solid #ddd;
		}

		.result-data{
			font-size: 1.5em;
		}

		@media (max-width:767px){
			.input-section{
				background-size: 90%;
			}
		}

	</style>

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
			$keterangan = '';
			$penjualan_type_id = 3;
			$tipe_penjualan = '';
			$customer_id = '';
			$no_faktur_lengkap = '';
			$no_surat_jalan = '';
			$fp_status = 1;

			$g_total = 0;
			$readonly = '';
			$disabled = '';
			$alamat_customer = '';
			$disabled_status = '';
			$bg_info = '';

			$hidden_spv = '';
			// if (is_posisi_id() != 1) {
				$hidden_spv = 'hidden';
			// }

			foreach ($penjualan_data as $row) {
				$tipe_penjualan = $row->tipe_penjualan;
				$penjualan_id = $row->id;
				$customer_id = $row->customer_id;
				$nama_customer = $row->nama_keterangan;
				$alamat_customer = $row->alamat_keterangan;
				$gudang_id = $row->gudang_id;
				$nama_gudang = $row->nama_gudang;
				$no_faktur = $row->no_faktur;
				$penjualan_type_id = $row->penjualan_type_id; 
				$po_number = $row->po_number;
				$fp_status = $row->fp_status;
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
				$alamat_keterangan = $row->alamat_keterangan;
				$kota = $row->kota;
				$keterangan = $row->keterangan;
				$customer_id = $row->customer_id;
				$no_faktur_lengkap = $row->no_faktur_lengkap;
				$no_surat_jalan = $row->no_surat_jalan;

				if ($status_aktif == -1 ) {
					$note_info = 'note note-danger';
				}

			}

			$nama_bank = '';
			$no_rek_bank = '';
			$tanggal_giro = '';
			$jatuh_tempo_giro = '';
			$no_akun = '';

			foreach ($data_giro as $row) {
				$nama_bank = $row->nama_bank;
				$no_rek_bank = $row->no_rek_bank;
				$tanggal_giro =is_reverse_date($row->tanggal_giro) ;
				$jatuh_tempo_giro = is_reverse_date($row->jatuh_tempo);
				$no_akun = $row->no_akun;
			}

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

			$nama_toko = '';
			$alamat_toko = '';
			$telepon = '';
			$fax = '';
			$npwp = '';



			
			foreach ($data_toko as $row) {
				$nama_toko = trim($row->nama);
				$alamat_toko = trim($row->alamat.' '.$row->kota);
				$telepon = trim($row->telepon);
				$fax = trim($row->fax);
				$npwp = trim($row->NPWP);

			}
			
		?>

		<div class="modal fade bs-modal-lg" id="portlet-config-detail" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-body">
						<h3 class='block'> Daftar Barang</h3>
							
							<?/*<div class="form-group">
			                    <label class="control-label col-md-3">Kode Barang<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<select name="barang_id" class='form-control input1' id='barang_id_select'>
		                				<option value=''>Pilih</option>
		                				<?foreach ($this->barang_list_aktif as $row) { ?>
			                    			<option value="<?=$row->id?>"><?=$row->nama_jual;?></option>
			                    		<? } ?>
			                    	</select>
			                    	<select name='data_barang' hidden>
			                    		<?foreach ($this->barang_list_aktif as $row) { ?>
			                    			<option value="<?=$row->id?>"><?=$row->nama_satuan;?>??<?=$row->harga_jual;?></option>
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
			                </div> */?>

			                <div class='item-wrapper'>
			                	<?$grade = ['grade', 'GRADE', 'Grade'];
			                	foreach ($this->barang_list_aktif as $row) { ?>
				                	<div>
		                    			<?=str_replace($grade, '', $row->nama_jual);?>
				                	</div>
	                    		<? } ?>
			                </div>


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

	<div class='wrapper'>

		<div class='sidebar'>
			sidebar
		</div>
		<div class='tab-section'>
			tab
		</div>
		<div class='trx-info-container' style="<?=$bg_info;?>" >

			<div style='float:right;margin:15px;'>
				<span style='font-size:4em;'>FPJ<?=date('ymd-xxxx')?></span>
			</div>
			<div class='toko-info-container'>
				<?/*<table>
					<tr>
						<td><span style='font-size:1.5em'><?=$nama_toko;?></span></td>
					</tr>
			    	<tr>
			    		<td><?=$alamat_toko;?></td>
			    	</tr>
			    	<tr class='customer_section'>
			    		<td class='td-isi-bold'>
			    			<i class='fa fa-phone'></i> : <?=$telepon;?>
			    		</td>
			    	</tr>
			    	<tr class='customer_section'>
			    		<td class='td-isi-bold'>
			    			<i class='fa fa-fax'></i> : <?=$fax?>
			    		</td>
			    	</tr>
			    	<tr class='customer_section'>
			    		<td class='td-isi-bold'>
			    			<?=$npwp;?>
			    		</td>
			    	</tr>
		    	
			    </table>*/?>

			    <table>
					<tr>
						<td><span style='font-size:1.5em'>Nama Customer</span></td>
					</tr>
			    	<tr>
			    		<td>Alamat Customer</td>
			    	</tr>
			    	<tr class='customer_section'>
			    		<td class='td-isi-bold'>
			    			Telepon
			    		</td>
			    	</tr>
			    	<tr class='customer_section'>
			    		<td class='td-isi-bold'>
			    			FP
			    		</td>
			    	</tr>
		    	
			    </table>
			</div>

			<div class='invoice-info-container' <?=($penjualan_id=='' ? 'hidden' : '');?> >
				<table>
					<?if ($status == -1 && $no_faktur_lengkap != '' && $status_aktif != -1 ) { $iClass = 'fa-ban' ?>
	    				<span style='color:red' hidden><b>BATAL</b></span>
	    			<?}elseif ($status == 1 && $no_faktur_lengkap != '' && $status_aktif != -1  ) { $iClass = 'fa-unlock' ?>
	    				<span style='color:green' hidden><b>OPEN</b></span>
	    			<?}elseif ($status == 0 && $no_faktur_lengkap != '' && $status_aktif != -1  ) { $iClass = 'fa-lock' ?>
	    				<span style='color:orange' hidden><b>LOCKED</b></span>
	    			<?}elseif ($status_aktif == -1) {
	    				$iClass = 'fa-minus-circle';
	    			}elseif ($penjualan_id !='') {
	    				$iClass = 'fa-exclamation-circle';
	    			}?>
	    			<tr>
				    	<td class='td-isi-bold'>
		    				<?if ($status == 0) { ?>
								<button href="#portlet-config-pin" data-toggle='modal' class='btn btn-xs btn-pin margin-rl-10'><i class='fa fa-key'></i> request open</button>
							<?}elseif ($status != -1) { ?>
								<?if (is_posisi_id() != 6 ) { ?>
									<button href="#portlet-config-edit" data-toggle='modal' class='btn btn-xs  margin-rl-10'><i class='fa fa-edit'></i> edit</button>
								<?}?>
							<?}?>
							<i class='fa <?=$iClass;?>' style='font-size:1.5em'></i>
			    			<span style='font-size:1.5em'><?=$no_faktur_lengkap;?></span>
			    		</td>
			    	</tr>
					<tr>
				    	<td class='td-isi-bold'>
			    			<?=$po_number;?>
			    		</td>
			    	</tr>
			    	<tr>
			    		<td class='td-isi-bold'><?=is_reverse_date($tanggal);?></td>
			    	</tr>
			    	<tr class='customer_section'>
			    		<td class='td-isi-bold'>
			    			<?if ($penjualan_type_id == 3) { ?>
			    				<?=$nama_keterangan;?> / <span class='alamat_keterangan'><?=$alamat_keterangan;?></span>
			    			<?} else{
			    				echo $nama_customer;
			    			}?>
			    		</td>
			    	</tr>
			    	<tr class='customer_section'>
			    		<td class='td-isi-bold'>
			    			<span class='alamat'><?=$alamat_customer?></span>
			    		</td>
			    	</tr>
			    	<tr class='customer_section'>
			    		<td class='td-isi-bold'>
			    			<?if ($fp_status == 1) { ?>
			    				<i class='fa fa-check'></i> FP
			    			<?} else{
			    				echo '';
			    			}?>
			    		</td>
			    	</tr>
		    	
			    </table>
			</div>
		</div>
		<div class='konten-wrapper'>
			<div class='input-section'>
				<div id='main-form'>
					<form action="<?=base_url('transaction/penjualan_list_detail_insert')?>" class="form-horizontal" id="form_add_barang" method="post">
						<i class="fa fa-circle" style="font-size:1.5em;" ></i> 
						<span style='font-size:1.5em'>Form Barang</span>
						<hr style='margin:10px 0px 15px 0px;'/>
						<div class="form-group">
			               	<div class="col-md-12">
			                	<input name='penjualan_id' value='<?=$penjualan_id;?>' hidden>
			                	<input name='penjualan_detail_id' value='' hidden>
			                	<input name='tanggal' value='<?=$tanggal;?>' hidden>
			        			<select name="gudang_id" class='form-control' id='gudang_id_select'>
			        				<?foreach ($this->gudang_list_aktif as $row) { ?>
			                			<option value="<?=$row->id?>"><?=$row->nama;?></option>
			                		<? } ?>
			                	</select>
			                </div>
			            </div> 

						<div class="form-group">
			                <div class="col-md-12">
			                	<select name="barang_id" class='form-control input1' id='barang_id_select' style='padding:0px; height:37px;'>
			        				<option value=''>Pilih Barang</option>
			        				<?foreach ($this->barang_list_aktif as $row) { ?>
			                			<option value="<?=$row->id?>"><?=$row->nama_jual;?></option>
			                		<? } ?>
			                	</select>
			                	<select name='data_barang' hidden>
			                		<?foreach ($this->barang_list_aktif as $row) { ?>
			                			<option value="<?=$row->id?>"><?=$row->nama_satuan;?>??<?=$row->harga_jual;?></option>
			                		<? } ?>
			                	</select>
			                </div>
			            </div>			                

			            <div class="form-group">
			                <div class="col-md-12">
			        			<select name="warna_id" class='form-control' id='warna_id_select' style='padding:0px; height:37px;'>
			        				<option value=''>Pilih Warna</option>
			                		<?foreach ($this->warna_list_aktif as $row) { ?>
			                			<option value="<?=$row->id?>"><?=$row->warna_beli;?></option>
			                		<? } ?>
			                	</select>
			                </div>
			            </div> 

			            <div class="form-group">
			                <div class="col-md-12">
			        			<input readonly type="text" class='form-control' name="satuan" placeholder="satuan" />
			                </div>
			            </div> 

			            <div class="form-group">
			                <div class="col-md-12">
		                		<input type="text" class='amount_number form-control' id='harga_jual_add' name="harga_jual" placeholder="harga_jual" />
		                    	<input name='rekap_qty' hidden>
			                </div>
			            </div>

						<button type="button" class="btn blue btn-add-qty" style='width:100%'>Add Qty</button>
					</form>
				</div>
				<div id='qty-form'>
					<i class="fa fa-arrow-circle-left back-main-form" style="font-size:1.5em; cursor:pointer" ></i> 
					<span style='font-size:1.5em'>Form QTY</span>
					<hr style='margin:10px 0px 15px 0px;'/>
					<table id='qty-table'>
						<tr>
							<th>Yard</td>
							<th>Roll</td>
							<th></th>
						</tr>
						<tr>
							<td><input name='qty' class='input1 qty-get'></td>
							<td><input name='jumlah_roll' class='roll-get'></td>
							<td><button tabindex='-1' class='btn btn-xs blue btn-add-qty-row'><i class='fa fa-plus'></i></button></td>
						</tr>
						<?for ($i=0; $i <9 ; $i++) {?> 
							<tr>
								<td><input name='qty' class='qty-get'></td>
								<td><input name='jumlah_roll' class='roll-get'></td>
								<td></td>
							</tr>
						<?}?>
					</table>
					<table id='qty-sum-table'>
						<tr>
							<th></th>
							<th>Yard</th>
							<th>Roll</th>
						</tr>
						<tr class='stok-info'>
							<td>STOK</td>
							<td><span class='stok-qty' >0</span></td>
							<td><span class='stok-roll' >0</span></td>
						</tr>
						<tr class='yard-info'>
							<td>AMBIL</td>
							<td><span class='yard_total' >0</span></td>
							<td><span class='jumlah_roll_total' >0</span></td>
						</tr>
						<tr class='sisa-info' style='border-top:1px solid black;'>
							<td>SISA</td>
							<td><span class='yard_sisa' >0</span></td>
							<td><span class='jumlah_roll_sisa' >0</span></td>
						</tr>
					</table>
					<br/>
					<button type="button" class="btn green btn-add-record" style='width:100%'>SUBMIT</button>
				</div>
			</div>
			
			<div class='konten' >
				<table class="table table-hover table-striped" id="general_table">
					<thead>
						<tr>
							<th scope="col">
								No
							</th>
							<th scope="col">
								Nama Barang
							</th>
							<th scope="col">
								Jml Yard/KG
							</th>
							<th scope="col">
								Jml Roll
							</th>
							<th scope="col">
								Harga
							</th>
							<th scope="col">
								Total Harga
							</th>
							<th scope="col" class='hidden-print'>
								Action
							</th>
						</tr>
					</thead>
					<tbody class='tbody-main'>
						<?
						$idx =1; $barang_id = ''; $gudang_id_last = ''; $harga_jual = 0; $qty_total = 0; $roll_total = 0;
						foreach ($penjualan_detail as $row) { ?>
							<tr id='id_<?=$row->id;?>'>
								<td>
									<?=$idx;?> 
								</td>
								<td>
									<span class='nama_jual'><?=$row->nama_barang;?> <?=$row->nama_warna;?></span> <i><small>(<?=$row->nama_gudang;?>)</small></i>
									<?$barang_id=$row->barang_id;?>
								</td>
								<td>
									<!-- <input name='qty' class='free-input-sm qty' value="<?=$row->qty;?>">  -->
									<span class='qty'><?=str_replace('.00', '',$row->qty);?></span>
									<span class='nama_satuan'><?=$row->nama_satuan;?></span>  
								</td>
								<td>
									<!-- <input name='jumlah_roll' class='free-input-sm jumlah_roll' value="<?=$row->jumlah_roll;?>"> -->
									<span class='jumlah_roll'><?=$row->jumlah_roll;?></span> 
								</td>
								<td>
									<!-- <input name='harga_jual' <?=$readonly;?> class='free-input-sm amount_number harga_jual' value="<?=number_format($row->harga_jual,'0','.','.');?>">  -->
									<span class='harga_jual'><?=number_format($row->harga_jual,'0','.','.');?></span>

								</td>
								<td>
									<?$subtotal = $row->qty * $row->harga_jual;
									$g_total += $subtotal;
									$harga_jual = $row->harga_jual;
									$qty_total += $row->qty;
									$roll_total += $row->jumlah_roll;
									?>
									<span class='subtotal'><?=number_format($subtotal,'0','.','.');?></span> 
								</td>
								<td class='hidden-print'>
									<?$gudang_id_last=$row->gudang_id;?>
									
									<?if ($status == 1 || is_posisi_id() == 1 ) { ?>
										<?if (is_posisi_id() != 6 && $status_aktif != -1) { ?>
											<span class='id' <?=$hidden_spv?> ><?=$row->id;?></span>
											<span class='barang_id' <?=$hidden_spv?> ><?=$row->barang_id;?></span>
											<span class='warna_id' <?=$hidden_spv?> ><?=$row->warna_id;?></span>
											<span class='gudang_id'  <?=$hidden_spv?> ><?=$row->gudang_id;?></span>
											<span class='data_qty'  <?=$hidden_spv?> ><?=$row->data_qty;?></span>
											<a href='#portlet-config-detail-edit' data-toggle='modal' class="btn-xs btn green btn-edit"><i class="fa fa-edit"></i> </a>
											<a class="btn-xs btn red btn-detail-remove"><i class="fa fa-times"></i> </a>
										<?}?>
									<?}?>
								</td>
							</tr>
						<?
						$idx++; 
						} ?>

						
					</tbody>
					<tbody>
						<tr class='result-data'>
							<td colspan='2' class='text-right'></td>
							<td class='text-left'><b><?=str_replace('.00', '',$qty_total);?></b></td>
							<td class='text-left'><b><?=$roll_total;?></b> roll</td>
							<td class='text-center' style="background:#c9ddfc"><b>TOTAL</b></td>
							<td><b class='total'><?=number_format($g_total,'0',',','.');?> </b> </td>
							<td class='hidden-print'></td>
						</tr>
						<tr class='result-data'>
							<td colspan='2' class='text-right'></td>
							<td class='text-left'></td>
							<td class='text-left'></td>
							<td class='text-center' style="background:#ffd7b5"><b>BAYAR</b></td>
							<td><b class='total-bayar'></b> </td>
							<td class='hidden-print'></td>
						</tr>
						<tr class='result-data'>
							<td colspan='2' class='text-right'></td>
							<td class='text-left'></td>
							<td class='text-left'></td>
							<td class='text-center' style='background:#ceffb4'><b>KEMBALI</b></td>
							<td><b class='kembali'></b> </td>
							<td class='hidden-print'></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>

		
		<div class='footer' ><?=SITE_URL();?></div>
	</div>

<script src="<?php echo base_url('assets/global/plugins/bootstrap-modal/js/bootstrap-modalmanager.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/bootstrap-modal/js/bootstrap-modal.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/jquery-validation/js/jquery.validate.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js'); ?>" type="text/javascript"></script>

<script>
jQuery(document).ready(function() {
	$("#barang_id_select").select2();
	$("#warna_id_select").select2();

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

	$("#barang_id_select").change(function () {
		$('#form_add_barang [name=warna_id]').select2('open');
	})

	$('.btn-add-qty').click(function(){
		$('#qty-form').animate({
			left:'7px',
		}, 200);

		setTimeout(function(){
			// console.log('test');
			$('#qty-table .input1').focus();
		},700);
	});

	$('.btn-add-record').click(function(){

		var form = $('#form_add_barang');
		var barang_val = form.find('[name=barang_id]').val();
		// console.log(barang_val);
		var warna_val = form.find('[name=warna_id]').val();
		var nama_barang = form.find("[name=barang_id] [value='"+barang_val+"'] ").text();
		var nama_warna = form.find("[name=warna_id] [value='"+warna_val+"'] ").text();
		var harga = form.find("[name=harga_jual]").val();
		var qty_total = 0;
		var roll_total = 0;
		var qty_table = $("#qty-table");
		qty_table.find(".qty-get").each(function(){
			if($(this).val() != ''){
				var roll_beside = $(this).closest('tr').find('.roll-get').val();
				// console.log(roll_beside);
				if (roll_beside == 0) { roll_beside = 1; };
				qty_total += parseInt($(this).val() * roll_beside);
			}
		});

		qty_table.find(".roll-get").each(function(){
			if($(this).val() != ''){
				roll_total += parseInt($(this).val());
			}
		});

		var new_row = `<tr>
				<td></td>
				<td>${nama_barang} ${nama_warna}</td>
				<td>${qty_total}</td>
				<td>${roll_total}</td>
				<td>${harga}</td>
				<td>${change_number_format(qty_total * reset_number_format(harga)) }</td>
				<td>-</td></tr>
				`;

		$('#general_table .tbody-main').append(new_row);
		reset_form_barang();
		back_to_main();
	});

	$("#warna_id_select").change(function() {
		var form = $("#form_add_barang");
		var barang_val = form.find('[name=barang_id]').val();
		var barang_data = form.find("[name=data_barang] [value='"+barang_val+"'] ").text().split('??');
		var satuan = barang_data[0];
		var harga_jual = barang_data[1];
		form.find('[name=satuan]').val(satuan);
		form.find('[name=harga_jual]').val(harga_jual);
		// console.log(barang_val);
		console.log(barang_data);
		
	})

	$('.back-main-form').click(function(){
		back_to_main();
	});

	function back_to_main () {
		$("#qty-form").animate({
			left:'-100%',
		}, 300);
	}

	function reset_form_barang(){
		$('.qty-get, .roll-get ').val('');
		setTimeout(function () {
			$('#warna_id_select').select2('open');
		},400);
	}

});
</script>