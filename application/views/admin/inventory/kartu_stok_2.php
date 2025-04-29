<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css'); ?>"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css'); ?>"/>
<?=link_tag('assets/global/plugins/bootstrap-datepicker/css/datepicker3.css'); ?>

<style type="text/css">
#general_table tr td, #general_table tr th {
	text-align: center;
	vertical-align: middle;
}
#qty-table-stok input{
	width: 70px;
	padding-left: 5px
}

.hide-qty{display: none;}

.qty-data{
	position: absolute;
	left: 100%;
	top: -5%;
	padding: 5px;
	z-index: 99;
	background-color: #e1e7fc;
}

.qty-data-1{
	background-color: #e1e7fc;
}

.qty-data-2{
	background-color: #eeffd9;
}

.arrow-left{
	position: absolute;
	content: '';
	width: 0px; 
	height: 0px; 
	right:0px;
	top: 0;
	border-top: 10px solid transparent;
	border-bottom: 10px solid black transparent; 
}

.arrow-left-1{
	border-right:10px solid  #e1e7fc; 
}

.arrow-left-2{
	border-right:10px solid  #eeffd9; 
}


.arrow-left-roll{
	position: absolute;
	content: '';
	width: 0px; 
	height: 0px; 
	left:40px;
	top: 0;
	border-top: 10px solid transparent;
	border-bottom: 10px solid black transparent; 
}


.qty-data-roll{
	position: absolute;
	min-width: 150px;
	left: 30px;
	top: -5%;
	padding: 5px;
	z-index: 99;
	background-color: #e1e7fc;
}

.table-detail{
	font-size:1.5em;
	margin: auto;
}

.table-detail tr td{
	border: 1px solid #ddd;
	text-align: center;
	padding: 10px;
	min-width: 50px;
}

.table-detail tr:nth-child(2n) td:nth-child(2n){
	background: #f9f9f9;
}

.table-detail tr:first-child td:first-child, .table-detail tr:first-child td:nth-child(2n+1), .table-detail tr:nth-child(2n+1) td:nth-child(2n+1) {
	background: #f9f9f9;
}

.table-detail .active{
	background-color: #ffbaba !important;
}

#float-calc{
	position: absolute;
	top: 0px;
	left: 0px;
}

#tableRinci{
	display: none;
}
</style>

<style type="text/css">
#qty-table input{
	width: 60px;
	padding-left: 5px
}

#qty-table input{
	width: 70px;
	padding-left: 5px
}

#qty-table .nama_satuan, #qty-table .nama_packaging, #qty-table-edit .nama_satuan, #qty-table-edit .nama_packaging{
	text-align: center;
}

.yard-info{
	font-size: 1.5em;
}
</style>

<div class="page-content">
	<div class='container'>

		<div class="modal fade bs-modal-lg" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-body">
							<table width='100%'>
								<tr>
									<td colspan='2'><h3 class='block'> Penyesuaian Barang</h3></td>
								</tr>
								<tr>
									<td style='vertical-align:top'>
										<form action="<?=base_url('inventory/penyesuaian_stok_insert')?>" class="form-horizontal" id="form_add_data" method="post">
											<!--<div class="form-group">
							                    <label class="control-label col-md-4">Tipe<span class="required">
							                    * </span>
							                    </label>
							                    <div class="col-md-6">
									                <div class='radio-list'>
									                	<label class="radio-inline">
									                		<input type='radio' class='form-control' checked name='tipe_transaksi' value='1'> Barang Masuk
									                	</label>
									                	<label class="radio-inline">
									                		<input type='radio' class='form-control' name='tipe_transaksi' value='2'> Barang Keluar
									                	</label>
									                </div>
							                    </div>
							                </div>-->

							                <div class="form-group">
							                    <label class="control-label col-md-4">Tanggal<span class="required">
							                    * </span>
							                    </label>
							                    <div class="col-md-6">
							                		<input name='tipe_transaksi' value='1' hidden> 
							                    	<input name='barang_id' value='<?=$barang_id?>' hidden>
							                    	<input name='warna_id' value='<?=$warna_id?>' hidden>
							                    	<input name='gudang_id' value='<?=$gudang_id;?>' hidden>
							                    	<input name='penyesuaian_stok_id' hidden>
									                <input name="tanggal" type="text" readonly class="form-control date-picker" value="<?=date('d/m/Y');?>" />
							                    </div>
							                </div>

							                <div class="form-group">
							                    <label class="control-label col-md-4">Qty
							                    </label>
							                    <div class="col-md-6">
													<input type="text" readonly autocomplete='off' class='form-control' name="qty"/>
													<!-- <small>Rincian bukan total</small> -->
							                    </div>
							                </div> 

							                <div class="form-group">
							                    <label class="control-label col-md-4">Jumlah Roll
							                    </label>
							                    <div class="col-md-6">
													<input type="text" readonly autocomplete='off' class='form-control' name="jumlah_roll"/>
							                    </div>
							                </div> 

							                <!-- <div class="form-group">
							                    <label class="control-label col-md-4">Total
							                    </label>
							                    <div class="col-md-6">
													<input type="text" readonly autocomplete='off' class='form-control' name="total_qty"/>
							                    </div>
							                </div>  -->

							                <div class="form-group">
							                    <label class="control-label col-md-4">Keterangan
							                    </label>
							                    <div class="col-md-6">
													<input type="text" class='form-control' name="keterangan" value="Pemutihan oleh : <?=is_username();?>" />
							                    </div>
							                </div> 

							                <input name='rekap_qty'>

										</form>
									</td>
									<td style='vertical-align:top'>
										<div style='max-height:400px; overflow-y:auto'>
											<table id='qty-table-stok'>
												<thead>
													<tr>
														<th class='nama_satuan'>Yard</th>
														<th class='nama_packaging'>Roll</th>
														<th></th>
														<th style='text-align:center'>Subtotal</th>
														<th></th>
													</tr>
												</thead>
												<tbody>
												</tbody>
										</div>
										</table>
										<div class='yard-info' style="font-size:1.2em">
											TOTAL QTY: <span class='yard_total' >0</span> Yard <br/>
											TOTAL ROLL: <span class='jumlah_roll_total' >0</span> Roll
										</div>
									</td>
								</tr>
							</table>
							
					</div>

					<div class="modal-footer">
						<button type="button" class="btn btn-active btn-trigger blue btn-save">Save</button>
						<button type="button" class="btn default" data-dismiss="modal">Close</button>
					</div>
				</div>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
		</div>

		<div class="modal fade" id="portlet-config-edit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body">
						<form action="<?=base_url('inventory/penyesuaian_stok_update')?>" class="form-horizontal" id="form_edit_data" method="post">
							<h3 class='block'> Penyesuaian Barang</h3>
							
							<div class="form-group">
			                    <label class="control-label col-md-4">Tipe<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
					                <div class='radio-list'>
					                	<label class="radio-inline">
					                		<input type='radio' class='form-control' name='tipe_transaksi' value='1'> Barang Masuk
					                	</label>
					                	<label class="radio-inline">
					                		<input type='radio' class='form-control' name='tipe_transaksi' value='2'> Barang Keluar
					                	</label>
					                </div>
			                    </div>
			                </div>

			                <div class="form-group">
			                    <label class="control-label col-md-4">Tanggal<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<input name='barang_id' value='<?=$barang_id?>' hidden>
			                    	<input name='warna_id' value='<?=$warna_id?>' hidden>
			                    	<input name='gudang_id' value='<?=$gudang_id;?>' hidden>
			                    	<input name='penyesuaian_stok_id' hidden>
				                	<input name="tanggal" type="text" readonly class="form-control date-picker" />
			                    </div>
			                </div>

			                <div class="form-group">
			                    <label class="control-label col-md-4">Qty
			                    </label>
			                    <div class="col-md-6">
									<input type="text" autocomplete='off' class='form-control' name="qty"/>
			                    </div>
			                </div> 

			                <div class="form-group">
			                    <label class="control-label col-md-4">Jumlah Roll
			                    </label>
			                    <div class="col-md-6">
									<input type="text" autocomplete='off' class='form-control' name="jumlah_roll"/>
			                    </div>
			                </div>

			                <div class="form-group">
			                    <label class="control-label col-md-4">Total
			                    </label>
			                    <div class="col-md-6">
									<input type="text" readonly autocomplete='off' class='form-control' name="total_qty"/>
			                    </div>
			                </div>

			                <div class="form-group">
			                    <label class="control-label col-md-4">Keterangan
			                    </label>
			                    <div class="col-md-6">
									<input type="text" class='form-control' name="keterangan"/>
			                    </div>
			                </div> 

						</form>
					</div>

					<div class="modal-footer">
						<button type="button" class="btn blue btn-edit-save">Save</button>
						<button type="button" class="btn default" data-dismiss="modal">Close</button>
					</div>
				</div>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
		</div>

		<div class="modal fade" id="portlet-config-split" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body">
						<form action="<?=base_url('inventory/penyesuaian_stok_split_insert')?>" class="form-horizontal" id="form_split" method="post">
							<h3 class='block'> SPLIT Barang</h3>
							
			                <div class="form-group">
			                    <label class="control-label col-md-4">Tanggal<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
					                <input name='tipe_transaksi' value='3' hidden > 
			                    	<input name='penyesuaian_stok_id'  hidden>
			                    	<input name='barang_id' value='<?=$barang_id?>'  hidden>
			                    	<input name='warna_id' value='<?=$warna_id?>' hidden >
			                    	<input name='gudang_id' value='<?=$gudang_id;?>'  hidden>
					                <input name="tanggal" type="text" readonly class="form-control date-picker" value="<?=date('d/m/Y');?>" />
			                    </div>
			                </div>

			                <div class="form-group">
			                    <label class="control-label col-md-4">QTY <br/><br/> Roll
			                    </label>
			                    <div class="col-md-6">
									<input type="text" autocomplete='off' class='form-control' name="qty_ori" placeholder='qty' id="qty-split-ori" />
									<input type="text" tabindex='-1' readonly class='form-control' name="jumlah_roll_ori" id="roll-split-ori" value='1'/>
			                    </div>
			                </div> 

			                <div class="form-group">
			                    <label class="control-label col-md-4">QTY Hasil
			                    </label>
			                    <div class="col-md-6">
									<!-- for next maybe  -->
									<table id='qty-table'>
										<thead>
											<tr>
												<th class='nama_satuan'>Yard</th>
												<th class='nama_packaging'>Roll</th>
												<th></th>
												<th style='text-align:center'>Subtotal</th>
												<th></th>
											</tr>
										</thead>
										<tbody>
											<?for ($i=0; $i < 3 ; $i++) { ?> 
												<tr>
													<td><input disabled name='qty[]' class='qty'></td>
													<td><input disabled name='jumlah_roll[]' class='jumlah_roll'></td>
													<td>=</td>
													<td class='subtotal text-center'></td>
													<td>
														<input tabindex='-1' hidden readonly name='split_id[]' class='split_id'>
													</td>
												</tr>
											<?}?>
										</tbody>
										<tfoot>
											<tr style='border-top:2px solid #000; font-size:1.5em'>
												<td><span id='total-qty-split'></span></td>
												<td>
													<span id='total-roll-split'></span><br/>
													<small id='total-roll-info' style='font-size:0.5em; color:red'></small>
												</td>
												<td></td>
												<td class='text-center'><span id='total-all-split'></span></td>
												<td></td>
											</tr>
										</tfoot>
									</table>
			                    </div>
			                </div> 

			                <div class="form-group">
			                    <label class="control-label col-md-4">Keterangan
			                    </label>
			                    <div class="col-md-6">
									<input type="text" readonly class='form-control' name="keterangan" value="Split Kain : <?=is_username();?>" />
			                    </div>
			                </div> 

						</form>
					</div>

					<div class="modal-footer">
						<button type="button" class="btn btn-active blue btn-split-save" disabled>Save</button>
						<button type="button" class="btn default" data-dismiss="modal">Close</button>
					</div>
				</div>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
		</div>

		<form id="form_remove_data" action="<?=base_url('inventory/penyesuaian_stok_remove')?>" method='post'>
			<input name='barang_id' value='<?=$barang_id?>' hidden>
        	<input name='warna_id' value='<?=$warna_id?>' hidden>
        	<input name='gudang_id' value='<?=$gudang_id;?>' hidden>
        	<input name='penyesuaian_stok_id' hidden>
		</form>

		<div class="row margin-top-10">
			<div class="col-md-12">
				<div class="portlet light">
					<div class="portlet-title">
						<div class="caption caption-md">
							<i class="icon-bar-chart theme-font hide"></i>
							<span class="caption-subject theme-font bold uppercase"><?=$breadcrumb_small;?></span>
						</div>
						<div class="actions">
							<!-- <a href="#portlet-config-split" data-toggle='modal' class="btn green  btn-sm btn-add-split hidden-print">
							<i class="fa fa-plus"></i> Split </a> -->
							<?if (is_posisi_id() < 3) { ?>
								<a href="#portlet-config" data-toggle='modal' class="btn btn-default btn-sm btn-form-add hidden-print">
								<i class="fa fa-plus"></i> Tambah </a>
							<?}?>
						</div>
					</div>
					<div class="portlet-body">
						<form action='' method='get'>
							<table>
								<tr>
									<td>Lokasi</td>
									<td class='padding-rl-5'> : </td>
									<td>
										<b>
											<select name='gudang_id'>
											<?foreach ($this->gudang_list_aktif as $row) {?>
												<option <?=($gudang_id == $row->id ? 'selected' :"")?> value="<?=$row->id;?>" ><?=$row->nama;?></option>
											<?}?>
											</select>
										</b>
									</td>
								</tr>
								<tr>
									<td>Nama/Warna</td>
									<td class='padding-rl-5'> : </td>
									<td>
										<b>
											<select name='barang_id'>
											<?foreach ($barang_list as $row) {?>
												<option <?=($barang_id == $row->id ? 'selected' : "")?>  value="<?=$row->id;?>" ><?=$row->nama_jual;?><?=(is_posisi_id()==1 ? $barang_id.'-'.$row->id : '')?></option>
											<?}?>
											</select>
										</b>
									</td>
									<td>
										<b>
											<select name='warna_id'>
											<?foreach ($this->warna_list_aktif as $row) {?>
												<option <?=($warna_id == $row->id ? 'selected' :"")?>  value="<?=$row->id;?>" ><?=$row->warna_jual;?></option>
											<?}?>
											</select>
										</b>
									</td>
								</tr>
								<tr>
									<td>Tanggal Stok</td>
									<td class='padding-rl-5'> : </td>
									<td>
										<b>
											<input name='tanggal_start' readonly class='date-picker' style='border:none; border-bottom:1px solid #ddd; width:100px;' value='<?=$tanggal_start;?>'>
											s/d
											<input name='tanggal_end' readonly class='date-picker2 ' style='border:none; border-bottom:1px solid #ddd; width:100px;' value='<?=$tanggal_end;?>'> 
											<button class='btn btn-xs default'><i class='fa fa-search'></i></button>
										</b>
									</td>
								</tr>
							</table>
							
						</form>
						<hr/>
						<?
							$qty = 0;
							$roll = 0;
							?>
							<?
							if (is_posisi_id() != 8) {?>
								<table class="table table-striped table-bordered table-hover" id="general_table">
									<thead>
										<tr>
											<th scope="col" rowspan='2'>
												Tanggal
											</th>
											<th scope="col" rowspan='2'>
												Keterangan
											</th>
											<th scope="col" colspan='2'>
												Masuk
											</th>
											<th scope="col" colspan='2'>
												Keluar
											</th>
											<th colspan='2'>
												Saldo
											</th>
										</tr>
										<tr>
											<th scope="col">
												Qty
											</th>
											<th scope="col">
												Roll
											</th>
											<th scope="col">
												Qty
											</th>
											<th scope="col">
												Roll
											</th>
											<th scope="col">
												Qty
											</th>
											<th scope="col">
												Roll
											</th>

										</tr>
									</thead>
									<tbody>
										<?
										$total_in = 0;
										$total_out = 0;

										
										// print_r($stok_awal);
										foreach ($stok_awal as $row) { ?>
											<tr>
												<td>
													<b>Stok Awal</b>
												</td>
												<td></td>
												<td>
													<?
													//echo ($row->qty_masuk == 0 ?  '-' :  $row->qty_masuk );
														$qty += $row->qty_masuk;
														$total_in += $row->qty_masuk;
														# code...
													?>
												</td>
												<td>
													<?//=($row->jumlah_roll_masuk == 0 ? '-' : $row->jumlah_roll_masuk ) ;?>
													<?$roll += $row->jumlah_roll_masuk; ?>

												</td>

												<td>
													<?//=($row->qty_keluar == 0 ? '-' : $row->qty_keluar );?>
													<?$qty -= $row->qty_keluar;?>
													<?$total_out += $row->qty_keluar;?>
												</td>
												<td>
													<?//=($row->jumlah_roll_keluar == 0 ? '-' : $row->jumlah_roll_keluar ) ;?>
													<?$roll-= $row->jumlah_roll_keluar;?>
												</td>
												<td <? if ($qty < 0): echo "style='color:red'"; endif ?>>
													<b><?=number_format($qty,'2',',','.');?></b> 
												</td>
												<td <? if ($roll < 0): echo "style='color:red'"; endif ?>>
													<b><?=number_format($roll,'0',',','.');?></b> 
												</td>
											</tr>
										<?}?>
										<?
										$qty_temp_in = 0;
										$qty_temp_out = 0;
										$roll_in = 0;
										$roll_out = 0;
										foreach ($stok_barang as $row) { 
											$qty_data = explode(',', $row->qty_data);
											$roll_data = explode(',', $row->roll_data);
											$tipe_data = 0;
											$roll_split_hasil = 0;
											?>
											<tr class='detail-row' style="<?=($row->tipe == 'z1' ? 'background:#B71C1C; color:white' : ($row->tipe == 'a1' && $row->qty_masuk <= 5 ? 'background:#fffede' : '' ) )?>" >
												<td>
													<?=date('d F Y H:i:s', strtotime($row->time_stamp));?>
													<span class='tanggal' hidden><?=$row->tanggal?></span>
													<?=(is_posisi_id() == 1 ? $row->tipe : '');?>
												</td>
												<td>
													<?if ($row->tipe == 'a1' || $row->tipe == 'a2' || $row->tipe == 'a3'  || $row->tipe == 'a6' || $row->tipe == 'a3r' ) {
														if (is_posisi_id() < 3) {
															if ($row->tipe == 'a1') { ?>
																<a target='_blank' href="<?=base_url().is_setting_link('transaction/pembelian_list_detail').'/'.$row->trx_id;?>"><?=($row->no_faktur == '' ? '??' : $row->no_faktur) ;?></a>
															<?}elseif ($row->tipe == 'a2') { ?>
																<a target='_blank' href="<?=base_url().is_setting_link('transaction/penjualan_list_detail').'?id='.$row->trx_id;?>"><?=($row->no_faktur == '' ? '??' : $row->no_faktur);?></a>
															<?}elseif ($row->tipe == 'a3') { ?>
																<a target='_blank' href="<?=base_url().is_setting_link('transaction/retur_jual_detail').'?id='.$row->trx_id;?>"><?=($row->no_faktur == '' ? '??' : $row->no_faktur);?></a>
															<?}elseif ($row->tipe == 'a3r') { ?>
																<a target='_blank' href="<?=base_url().is_setting_link('transaction/retur_beli_detail').'?id='.$row->trx_id;?>"><?=($row->no_faktur == '' ? '??' : $row->no_faktur);?></a> <b style='color:red'>RETUR</b>
															<?}elseif ($row->tipe == 'a6') { ?>
																<a target='_blank' href="<?=base_url().is_setting_link('transaction/pengeluaran_stok_lain_list_detail').'?id='.$row->trx_id;?>"><?=$row->no_faktur;?></a>
															<?}
														}else{
															if ($row->tipe == "a2") {
																echo (is_posisi_id() == 9 ? "faktur jual ".substr($row->no_faktur,-4) : $row->no_faktur);
															}else{
																echo $row->no_faktur;

															}
														}
													}else if ($row->tipe == '1' || $row->tipe == '2' || $row->tipe == '3' ) {
														$user = explode('??', $row->no_faktur);
														?>
														<!-- <?=$row->no_faktur;?> -->
														<span class='user_id' hidden><?=$user[1];?></span>
														<span class='tipe' hidden><?=$row->tipe;?></span>
														<?
															if (is_posisi_id() < 3 && $row->tipe != 3) { ?>
																<a href="#portlet-config" data-toggle='modal' class='btn btn-xs blue btn-edit'><i class='fa fa-edit'></i></a>
																<a class='btn btn-xs red btn-remove'><i class='fa fa-times'></i></a>
																<span class='keterangan'><?=$user[0];?></span>
																<span class='penyesuaian_stok_id' hidden><?=$user[2];?></span>
															<?}else{ 
																$ket_split = explode('??', $row->no_faktur);
																// echo $ket_split[0];
																$qty_data_split = explode(',', $ket_split[1]);
																$roll_data_split = explode(',', $ket_split[2]);
																?>
																<span class='keterangan'><?=$ket_split[0];?></span>
																<span class='qty-split' hidden><?=$ket_split[1];?></span>
																<span class='roll-split' hidden><?=$ket_split[2];?></span>
																<!-- <a href="#portlet-config-split" data-toggle='modal' class='btn btn-xs green btn-edit-split'><i class='fa fa-edit'></i></a> -->
																<!-- <a class='btn btn-xs red btn-remove-split'><i class='fa fa-times'></i></a> -->
																<span class='penyesuaian_stok_id' hidden><?=$row->trx_id;?></span>
																<span class='split_id'  hidden ><?=$row->id;?></span>
															<?}
														
													}elseif ($row->tipe == 'b1') {
														echo "mutasi barang dari ".$row->no_faktur." ke ".$nama_gudang;
													}elseif ($row->tipe == 'b2') {
														echo "mutasi barang dari ".$nama_gudang." ke ".$row->no_faktur."";
													}else if($row->tipe == 0 && $row->tipe != 'b1' && $row->tipe != 'b2' && $row->tipe != 'z1'){
														echo "<b>Mutasi Stok Awal</b>";
													}elseif ($row->tipe == 'z1') {
														echo "<b>Stok Opname</b>";
													}elseif ($row->tipe == '11') {
														echo 'Penyesuaian Stok Opname';
													}?>
												</td>
												<td style='position:relative'>
													<?if ($row->qty_masuk > 0) {
														$tipe_data = 1;
													}?>
													<?if ($row->tipe != 'z1') {
														if ($row->tipe != 1 && $row->tipe != 3) {
															echo ($row->qty_masuk == 0 ?  '-' : "<span class='qty'>".(float)$row->qty_masuk."</span>" );
															// $qty += ($row->tipe == 'a1' && $row->qty_masuk <= 5 ? 0 :  $row->qty_masuk);
															$qty += $row->qty_masuk;
															$total_in += $row->qty_masuk;
															$qty_temp_in += $row->qty_masuk;
														}elseif ($row->tipe == 1) {
															echo "<span class='qty' hidden>".(float)($row->qty_masuk)."</span>";
															echo ($row->qty_masuk == 0 ?  '-' : "<span class='total-qty'>".(float)($row->qty_masuk)."</span>" );
															$id_data = explode(',', $row->id);
															$dt = [];
															foreach ($qty_data as $key => $value) {
																array_push($dt, (float)$value.'??'.$roll_data[$key].'??'.$id_data[$key]);
															}
															echo "<span class='rekap_qty' hidden >".implode('--', $dt)."</span>";
															$qty += ($row->qty_masuk);
															$total_in += $row->qty_masuk;
															$qty_temp_in += $row->qty_masuk;
														}elseif ($row->tipe == 3) {
															echo "<span class='qty_split' hidden>".$ket_split[1]."</span>";
															foreach ($qty_data_split as $key => $value) {
																echo (float)$value.'<br/>';
															}
														}
														?>
													<?}else{
														$qty_so = $row->qty_masuk;
														$total_in += $qty_so;
														$qty = $qty_so;
														echo "<span class='qty'>".(float)$qty_so."</span>";
														$tipe_data = 1;
													}

													if ($tipe_data == 1) {?>
														<div class='arrow-left-1 arrow-left' hidden></div>
														<div class='qty-data-1 qty-data' hidden>
															<table style="color:black">
																<?foreach ($qty_data as $key => $value) {?>
																	<tr>
																		<td style='text-align:right'><b><?=(float)$value?></b></td>
																		<td style='padding:0 2px;font-size:0.8em'><b> x </b></td>
																		<td><?=$roll_data[$key]?></td>
																		<td style='padding:0 2px;font-size:0.8em'><b> = </b></td>
																		<td><b><?=$value * $roll_data[$key]?></b></td>
																	</tr>
																<?}?>
																<tr style="border-top:1px solid #000">
																	<td colspan='2'>TOTAL</td>
																	<td><?=$row->jumlah_roll_masuk;?></td>
																	<td></td>
																	<td><?=(float)$row->qty_masuk;?></td>
																</tr>
															</table>
														</div>
													<?}
													?>
												</td>
												<td>
													<?if ($row->tipe != 'z1' && $row->tipe != 3) {
														if ($row->tipe == 1 && $row->jumlah_roll_masuk > 1) {?>
															<small>[<?=(float)$row->qty_masuk;?>]</small>
														<?}?>
														<?=($row->jumlah_roll_masuk == 0 ? '-' : "<span class='jumlah_roll'>".(float)$row->jumlah_roll_masuk."</span>" ) ;?>
														<?$roll += $row->jumlah_roll_masuk;?>

													<?}else if($row->tipe==3){
														echo "<span class='roll_split' hidden>".$ket_split[2]."</span>";
														foreach ($roll_data_split as $key => $value) {
															echo $value.'<br/>';
															$roll_split_hasil += $value;
														}
													}else{
														$roll_so = $row->jumlah_roll_masuk;
														$roll = $roll_so;
														echo "<span class='jumlah_roll'>".$roll_so."</span>";
													}
													$roll_in += ($row->tipe != 3 ? $row->jumlah_roll_masuk : $roll_split_hasil);
													// echo (is_posisi_id() == 1 ? '--'.$roll_in : '');
													
													?>
												</td>
												<td style="background-color:<?=($row->qty_keluar <= 1 && $row->qty_keluar > 0 ? '#ffd4fb' : '')?>; position:relative" >
													<?if ($row->qty_keluar > 0) {
														$tipe_data = 2;
													}?>
													<?if ($row->tipe != 'a2') { ?>
														<span class='qty' hidden><?=(float)$row->qty_keluar;?></span>
														<?$qty_show=$row->qty_keluar;?>
														<span class='total-qty' ><?=($qty_show != 0 ? $qty_show : '-');?></span>
														<?$qty -= ($row->tipe != 3 ? $row->qty_keluar : 0);?>
													<?}else{
														echo ($row->qty_keluar == 0 ? '-' : (float)$row->qty_keluar);
														$qty -= $row->qty_keluar;
													}?>
													<?$total_out += $row->qty_keluar;?>
													<?$qty_temp_out += $row->qty_keluar;?>
													<?if ($tipe_data == 2) {?>
														<div class='arrow-left-2 arrow-left' hidden></div>
														<div class='qty-data-2 qty-data' hidden>
															<table>
																<?foreach ($qty_data as $key => $value) {?>
																	<tr>
																		<td style='text-align:right'><b><?=(float)$value?></b></td>
																		<td style='padding:0 2px;font-size:0.8em'><b> x </b></td>
																		<td><?=$roll_data[$key]?></td>
																		<td style='padding:0 2px;font-size:0.8em'><b> = </b></td>
																		<td><b><?=$value * $roll_data[$key]?></b></td>
																	</tr>
																<?}?>
																<tr style="border-top:1px solid #000">
																	<td colspan='2'>TOTAL</td>
																	<td><?=$row->jumlah_roll_keluar;?></td>
																	<td></td>
																	<td><?=(float)$row->qty_keluar;?></td>
																</tr>
															</table>
														</div>
													<?}?>
												</td>
												<td>
													<?=($row->qty_keluar == 0 ? '-' : "<span class='jumlah_roll'>".$row->jumlah_roll_keluar."</span>" ) ;?>
													<?
														$sel_roll_split = $row->jumlah_roll_keluar - $roll_split_hasil;
														$roll-= ($row->tipe != 3 ? $row->jumlah_roll_keluar : $sel_roll_split);
														$roll_out += ($row->tipe != 3 ? $row->jumlah_roll_keluar : 0);
													?>
												</td>
												<td <? if ($qty < 0): echo "style='color:red'"; endif ?>>
													<?if ($row->tipe != 'z1') {?>
														<b><?=number_format($qty,'2',',','.');?></b> 
													<?}else{?>
														<b><?=number_format($row->qty_masuk,'2',',','.');?></b> 
													<?}?>
												</td>
												<td <? if ($roll < 0): echo "style='color:red'"; endif ?>>
													<?if ($row->tipe != 'z1') {?>
														<b><?=number_format($roll,'2',',','.');?></b> 
													<?}else{?>
														<b><?=number_format($row->jumlah_roll_masuk,'2',',','.');?></b> 
													<?}?>
												</td>
											</tr>
										<? } ?>
										<tr style='border-top:2px solid black'>
											<td>TOTAL Transaksi : </td>
											<td></td>
											<td><?=$qty_temp_in;?></td>
											<td><?=$roll_in?></td>
											<td><?=$qty_temp_out;?></td>
											<td><?=$roll_out?></td>
											<td></td>
											<td></td>
										</tr>
										<tr>
											<td>TOTAL Transaksi + Stok Awal</td>
											<td></td>
											<td><?=$total_in;?></td>
											<td></td>
											<td><?=$total_out;?></td>
											<td></td>
											<td></td>
											<td></td>
										</tr>

									</tbody>
								</table>
							<?}?>

						<!--  -->
						<hr/>

						<?
						if ((is_posisi_id() > 1 || is_posisi_id() < 4) && is_posisi_id() != 8 ) {?>
							<div >
								<button class=" btn btn-sm default" onclick="toggleTableRinci()" >Show/Hide Table Rincian</button>
								<div id="tableRinci">
									<button class='btn btn-md green show-qty'>Show Roll = 0</button>
									NOTES : <span style='font-size:1.5em'><span style='background-color:#FFEBEE'>Roll dengan yard <= 5</span> <b>tidak dihitung</b> </span>
									<br/>
									<table class='table table-striped table-bordered' id="detail-table" style='font-size:1.2em'>
										<thead>
											<tr>
												<th>QTY <?=$nama_satuan?> </th>
												<th>Roll Stok</th>
												<th>Roll Masuk</th>
												<th>Roll Keluar</th>
												<th>Roll Sisa</th>
												<th>TOTAL</th>
											</tr>
										</thead>
										<tbody>
											<?
											// print_r($stok_barang_by_satuan);
												$total_qty = 0; $total_sisa = 0; $total_masuk = 0; $total_keluar =0; $total_stok = 0;
												foreach ($stok_barang_by_satuan as $row) {
													$roll_stok  = $row->roll_stok_masuk - $row->roll_stok_keluar;
													$sisa_roll = $row->roll_stok_masuk - $row->roll_stok_keluar + $row->jumlah_roll_masuk - $row->jumlah_roll_keluar;
													$sisa_roll_no_sample = $row->roll_stok_masuk - $row->roll_stok_keluar + ( $row->qty <= 5 ? 0 : $row->jumlah_roll_masuk) - ( $row->qty <= 5 ? 0 : $row->jumlah_roll_keluar);
													$total_stok += $sisa_roll_no_sample;
													$tgl_recap_masuk = explode(',', $row->tgl_recap_masuk);
													$tgl_recap_keluar = explode(',', $row->tgl_recap_keluar);
													$roll_recap_masuk = explode(',', $row->roll_recap_masuk);
													$roll_recap_keluar = explode(',', $row->roll_recap_keluar);
													$ket_recap_masuk = explode(',', $row->ket_recap_masuk);
													$ket_recap_keluar = explode(',', $row->ket_recap_keluar);
													?>
													<tr class="<?=($roll_stok == 0 && $row->jumlah_roll_masuk == 0 && $row->jumlah_roll_keluar == 0 ? 'hide-qty' : '');?>" >
														<td>
															<?=(float)$row->qty?>
															<?//=(is_posisi_id() == 1 ? $row->tgl_recap_masuk.'<br/>'.$row->roll_recap_masuk.'<hr/>' : '');?>
															<?//=(is_posisi_id() == 1 ? $row->tgl_recap_keluar.'<br/>'.$row->roll_recap_keluar : '');?>
														</td>
														<td style="color:blue" ><?=($roll_stok == 0 ? '-' : $roll_stok); $total_sisa += $roll_stok;?></td>
														<td style="color:blue; position:relative" class='detail-col' >
															<?=($row->jumlah_roll_masuk == 0 ? '-' : $row->jumlah_roll_masuk); $total_masuk += ( $row->qty <= 5 ? 0 : $row->jumlah_roll_masuk);?>
															<?if ($row->tgl_recap_masuk != '') {?>
																<div class='arrow-left-1 arrow-left-roll' hidden></div>
																<div class='qty-data-roll' hidden>
																	<table>
																		<?foreach ($tgl_recap_masuk as $key => $value) {?>
																			<tr>
																				<td style='text-align:right;font-size:0.8em'><b><?=($value)?></b></td>
																				<td style='padding:0 2px'><b> : </b></td>
																				<td style='font-size:0.8em' ><?=$ket_recap_masuk[$key]?></td>
																				<td style='padding:0 2px'><b> = </b></td>
																				<td><b><?=$roll_recap_masuk[$key]?></b></td>
																			</tr>
																		<?}?>
																	</table>
																</div>
															<?}?>
														</td>
														<td style="color:red; position:relative" class='detail-col'  >
															<?=($row->jumlah_roll_keluar == 0 ? '-' : $row->jumlah_roll_keluar); $total_keluar += ( $row->qty <= 5 ? 0 : $row->jumlah_roll_keluar);?>
															<?if ($row->tgl_recap_keluar != '') {?>
																<div class='arrow-left-1 arrow-left-roll' hidden></div>
																<div class='qty-data-roll' hidden>
																	<table>
																		<?foreach ($tgl_recap_keluar as $key => $value) {?>
																			<tr>
																				<td style='text-align:right;font-size:0.8em'><b><?=date("d/m/y H:i:s", strtotime($value))?></b></td>
																				<td style='padding:0 2px'><b> : </b></td>
																				<td style='font-size:0.8em' ><?=(isset($ket_recap_keluar[$key]) ? $ket_recap_keluar[$key] : ''); ?></td>
																				<td style='padding:0 2px'><b> = </b></td>
																				<td><b><?=$roll_recap_keluar[$key]?></b></td>
																			</tr>
																		<?}?>
																	</table>
																</div>
															<?}?>
														</td>
														<td>
															<div style='display:inline-block; width:50px;'><?=($sisa_roll);?></div> <small>x <?=(float)$row->qty?></small>
														</td>
														<td><?=$subtotal=$row->qty*($sisa_roll); $total_qty += $subtotal;?></td>
													</tr>
												<?}?>
										</tbody>
										<tfoot>
											<tr style='font-size:1.2em; border-top:2px solid black;'>
												<td>TOTAL</td>
												<td><?=$total_sisa?></td>
												<td><?=$total_masuk?></td>
												<td><?=$total_keluar?></td>
												<td><?=$total_stok?></td>
												<td><?=$total_qty;?></td>
											</tr>
										</tfoot>
									</table>
								</div>
							</div>
						<?}?>
						<?$qtyList = [];
						$qty_100 = 0;
						// if (is_posisi_id()==1) {
							// print_r($qtyList);
							// $qtyList = ksort($qtyList);
							// print_r($qtyList);

						// }
						?>
						<hr/>
						<?
						$stokan = array();
						foreach ($stok_barang_by_satuan as $row){
								$roll_sisa = $row->jumlah_roll_masuk - $row->jumlah_roll_keluar + $row->roll_stok_masuk - $row->roll_stok_keluar;
								if ($roll_sisa > 0) {
									if (!isset($qtyList[$row->qty]) && (float)$row->qty != 100) {
										$qtyList[$row->qty] = 0;
									}
									if ((float)$row->qty != 100) {
										$qtyList[$row->qty] += $roll_sisa;
									}else{
										$qty_100 += $roll_sisa;
									}
								}
							};
						?>
						<div class='row'>
							<div class='col-xs-12 text-center'>
								<h4>DETAIL QTY <button onclick="showKalk()" class='btn btn-xs default'><i class='fa fa-info-circle'></i> kalkulasi</button></h4>
							</div>
							<div class='col-xs-12'>
								<table style='margin:auto'>
									<tr>
										<td style='vertical-align:top'>
											<table class='table-detail' id='qty-table-detail'>
												<thead>
													
												</thead>
												<tbody>
													<?$totalan = 100*$qty_100; $idx= 1;
													$stokan[100] = $qty_100;?>
													<tr>
														<td colspan='10' data-qty='100'>
															100 x <?=$qty_100;?>
														</td>
													</tr>
													<?foreach ($qtyList as $key => $value) {
														$totalan +=$key*$value;
														$nKey = str_replace(".00","", $key);
														$nKey = str_replace(".","_",$nKey);
														$stokan[$nKey] = $value;

														for ($i=0; $i < $value ; $i++) { 
															if ($idx % 10 == 1) {?>
																<tr>
															<?}?>
																<td><?=(float)$key;?></td>
															<?if ($idx % 10 == 0) {?>
																</tr>
															<?} $idx++;
														}
													}
													if ($idx % 10 > 0) {
														$closest = ceil($idx/10) * 10;
														for ($i=$idx; $i <= $closest ; $i++) {?>
															<td></td>
														<?}
														echo "</tr>";
													}
													?>
												</tbody>
												<tfoot>
													<tr>
														<td colspan='10'>TOTAL : <?=str_replace('.00','',number_format($totalan,'2','.',','));?></td>
													</tr>
													<tr>
														<td colspan='10' class='total-selected'></td>
													</tr>
												</tfoot>
											</table>
										</td>
										<td style='position:relative'>
											<table style='' class='table' id='table-detail-vertikal' hidden>
												<?$totalan=0;
												if ($qty_100 > 0) {
													$totalan=100*$qty_100;
													?>
													<tr>
														<td>100</td>
														<td>x</td>
														<td><?=$qty_100?></td>
														<td> = </td>
														<td class='text-right'><?=number_format(100*$qty_100,'0',',','.');?></td>
													</tr>
												<?}?>
												<?foreach ($qtyList as $key => $value) {
													$totalan += $key*$value;?>
													<tr>
														<td><?=(float)$key;?></td>
														<td>x</td>
														<td><?=$value?></td>
														<td>=</td>
														<td class='text-right'><?=$key*$value;?></td>
													</tr>
												<?}?>
												<tr>
													<td colspan='4'>TOTAL</td>
													<td class='text-right'><?=str_replace(",00", "", number_format($totalan,'2',',','.'));?></td>
												</tr>
											</table>
										</td>
									</tr>
								</table>
							</div>
						</div>
						<hr/>
						<div>
		                	<a href="javascript:window.open('','_self').close();" class="btn default button-previous hidden-print">Close</a>
		                	<button onclick='window.print()' class="btn blue hidden-print"><i class='fa fa-print'></i> Print</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>			
</div>

<script src="<?php echo base_url('assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js'); ?>" type="text/javascript"></script>

<script src="<?php echo base_url('assets/global/plugins/datatables/media/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets_noondev/js/table-advanced.js'); ?>"></script>
<script src="<?php echo base_url('assets/global/plugins/bootbox/bootbox.min.js'); ?>" type="text/javascript"></script>

<script>

const stokan = <?=json_encode($stokan);?>;
jQuery(document).ready(function() {

	$(".detail-row").click(function() {
		$('.qty-data').hide();
		$('.arrow-left').hide();
		let ini = $(this);
		ini.find('.qty-data').show();
		ini.find('.arrow-left').show();
	});

	$(".detail-col").click(function() {
		$('.qty-data-roll').hide();
		$('.arrow-left').hide();
		let ini = $(this);
		ini.find('.qty-data-roll').show();
		ini.find('.arrow-left').show();
	});
	
	
   	$('#general_table').on('click', '.btn-edit', function(){
   		let rekap_qty = $(this).closest('tr').find('.rekap_qty').html();
   		resetQtyDetailTable(rekap_qty);
   		$('#form_add_data [name=penyesuaian_stok_id]').val($(this).closest('tr').find('.penyesuaian_stok_id').html());
   		$('#form_add_data [name=tanggal]').val(date_formatter($(this).closest('tr').find('.tanggal').html()));
   		$('#form_add_data [name=qty]').val($(this).closest('tr').find('.qty').html());
   		$('#form_add_data [name=total_qty]').val($(this).closest('tr').find('.total-qty').html());
   		$('#form_add_data [name=jumlah_roll]').val($(this).closest('tr').find('.jumlah_roll').html());
   		$('#form_add_data [name=keterangan]').val($(this).closest('tr').find('.keterangan').html());
   		$('#form_add_data [name=rekap_qty]').val(rekap_qty);
   		var tipe = $(this).closest('tr').find('.tipe').html();
   		$('#form_add_data [name=tipe_transaksi][value='+tipe+']').prop('checked','checked');
   		$.uniform.update($('#form_add_data [name=tipe_transaksi]'));

   	});


   	$('.btn-save').click(function(){
   		if( $('#form_add_data [name=tanggal]').val() != '' ){
   			btn_disabled_load($(this));
   			$('#form_add_data').submit();
   		}
   	});

   	$('.btn-edit-save').click(function(){
   		if( $('#form_edit_data [name=tanggal]').val() != ''){
   			$('#form_edit_data').submit();
   		}
   	});

   	$('#general_table').on('click','.btn-remove', function(){
   		$('#form_remove_data [name=penyesuaian_stok_id]').val($(this).closest('tr').find('.penyesuaian_stok_id').html());
   		bootbox.confirm("Hapus penyesuaian stok ini ? ", function(respond){
   			if (respond) {
   				$('#form_remove_data').submit();
   			};
   		});

   	});

   	$(".show-qty").click(function(){
   		$(".hide-qty").toggle();
   	});

   	$("#form_add_data, #form_edit_data").on("change","[name=qty],[name=jumlah_roll]", function(){
   		var form = '#'+$(this).closest('form').attr('id');
   		var qty = $(form).find("[name=qty]").val();
   		var jumlah_roll = $(form).find("[name=jumlah_roll]").val();
   		jumlah_roll = (jumlah_roll == '' ? 1 : jumlah_roll);
   		$(form).find('[name=total_qty]').val(qty*jumlah_roll);

   	});

   	//============================table qty spilit ========================================
   	$("#qty-split-ori").keypress(function (e) {
   		if ($(this).val() != '') {
   			$("#qty-table input").prop('disabled',false);
   			$("#qty-table button").prop('disabled',false);
   		};

   		$("#qty-table .qty").change();
   	});

   	$("#qty-split-ori").change(function () {
   		if ($(this).val() != '') {
   			$("#qty-table input").prop('disabled',false);
   			$("#qty-table button").prop('disabled',false);
   		};

   		$("#qty-table .qty").change();

   	});

   	$("#qty-table").on('change',".qty, .jumlah_roll",function(){
   		let ini = $(this).closest('tr');
   		let total_roll = 0;
   		let total = 0;
   		let qty_ori = $('#qty-split-ori').val();
   		let roll_ori = $('#roll-split-ori').val();
   		var qty = ini.find('.qty').val();
   		var jumlah_roll = ini.find('.jumlah_roll').val();
   		var subtotal = (qty == '' ? 0 : qty) * (jumlah_roll == '' || jumlah_roll == 0 ? 1 : jumlah_roll);
   		ini.find('.subtotal').html(subtotal);

   		$("#qty-table .jumlah_roll").each(function(){
   			var ieu = $(this).closest('tr');
   			var qty_ = ieu.find('.qty').val();
   			if (qty_ != '') {
			   	var split_id = ieu.find('.split_id').val();
			   	if (split_id == '') {
			   		ieu.find('.split_id').val(0);
			   	};

	   			var roll = $(this).val();
		   		total_roll += parseFloat(roll == '' ? 0 : roll);
		   		var sub = (qty_ == '' ? 0 : qty_) * (roll == '' || jumlah_roll == 0 ? 1 : roll);
	   			total += sub;

	   			if (qty_ < 0) {
		   			$(".btn-split-save").prop('disabled',false);
		   			alert("Qty tidak boleh minus");
	   			};
   			};
   			
   		});

   		$("#total-roll-split").html(total_roll);
   		$("#total-all-split").html(total);

   		if(parseFloat(qty_ori) == parseFloat(total) && parseFloat(roll_ori) <= parseFloat(total_roll)  ){
   			$(".btn-split-save").prop('disabled',false);
   			$('#total-roll-info').html('');
   		}else{
   			if(parseFloat(roll_ori) != parseFloat(total_roll)){$('#total-roll-info').html('Total Roll Kurang')}else{$('#total-roll-info').html('')}
   			$(".btn-split-save").prop('disabled',true);
   		};


   	});


	$(".btn-split-save").click(function(){
		const stok_ambil = $("#qty-split-ori").val();
		let nVal = stok_ambil.replace(".00","");
		nVal = nVal.replace(".","_");
		const form = $("#form_split");
		const penyesuaian_stok_id = form.find("[name='penyesuaian_stok_id']").val();
		console.log(stokan);
		if (typeof stokan[nVal] !== 'undefined' || penyesuaian_stok_id != '') {
			btn_disabled_load($(this));
			form.submit(); 
		}else{
			alert(`STOK ${stok_ambil} tidak ada`);
		}
	});

	$(".btn-add-split").click(function(){
		let form = $("#form_split");
		form.find("[name=penyesuaian_stok_id]").val('');
		form.find("#qty-split-ori").val('');
		form.find("#roll-split-ori").val(1);
		$('#qty-table input').val('');
		$('#qty-table .subtotal').html('');
		$('#qty-table tfoot span').html('');

		
	});
	$(".btn-edit-split").click(function(){
		let ini = $(this).closest('tr');
		var qty_split = ini.find(".qty-split").html().split(',');
		var roll_split = ini.find(".roll-split").html().split(',');
		var split_id = ini.find(".split_id").html().split(',');
		let form = $("#form_split");
		form.find("[name=penyesuaian_stok_id]").val(ini.find('.penyesuaian_stok_id').html());
		$("#qty-split-ori").val(ini.find('.qty').html());
		$("#roll-split-ori").val(ini.find('.jumlah_roll').html());

		var baris_add= '';

		$.each(qty_split,function(i,v){
			baris_add += `<tr>
					<td><input name='qty[]' class='qty' value='${v}'></td>
					<td><input name='jumlah_roll[]' class='jumlah_roll' value='${roll_split[i]}'></td>
					<td>=</td>
					<td class='subtotal text-center'>${v*roll_split[i]}</td>
					<td>
						<input readonly name='split_id[]' class='split_id' value='${split_id[i]}'>
					</td>
				</tr>`;
		});

		baris_add += `<tr>
					<td><input name='qty[]' class='qty'></td>
					<td><input name='jumlah_roll[]' class='jumlah_roll'></td>
					<td>=</td>
					<td class='subtotal text-center'></td>
					<td>
						<input readonly name='split_id[]' class='split_id' >
					</td>
				</tr>`;

		$("#qty-table tbody").html(baris_add);
		$("#qty-table .qty").change();
	});


	$("#general_table").on("click",'.btn-remove-split', function(){
		var ini = $(this).closest('tr');
		var penyesuaian_stok_id = ini.find(".penyesuaian_stok_id").html();
		bootbox.confirm("Hapus penyesuaian SPLIT stok ini ?", function(respond){
			if (respond) {
				window.location.replace(baseurl+"inventory/penyesuaian_stok_split_remove?penyesuaian_stok_id="+penyesuaian_stok_id);
			};
		});
	});

   	//============================table qty add for next ========================================
   	<? /*$(".btn-add-qty-row").click(function(e){
   		e.preventDefault();
   	});

   	$("#qty-table").on('change',".qty, .jumlah_roll",function(){
		
    	data_result = table_qty_update('#qty-table').split('=*=');
    	// alert(data_result);
    	let total = parseFloat(data_result[0]);
    	let total_roll = parseFloat(data_result[1]);
    	let rekap = data_result[2];
    	if (total > 0) {
		    $('.btn-brg-save').attr('disabled',false);
    	}else{
    		$('.btn-brg-save').attr('disabled',true);
    	}


    	$('#qty-table .total-all').html(total.toFixed(2));
    	$('#qty-table .total-roll').html(total_roll);
    	// $('#form_add_barang [name=rekap_qty]').val(rekap);

    });*/?>


	//=================================tambahan detail==========================

	$('.btn-form-add').click(function(){
    	resetQtyDetailTable('');
    });


    $(document).on("click",".btn-add-qty-row",function(){
    	var jml_baris = $('#qty-table-stok tbody tr').length;
    	var baris = `<tr><td><input class='qty' tabindex='${2*jml_baris+1}'></td>
								<td><input name='jumlah_roll' class='jumlah_roll'  tabindex='${2*jml_baris+2}'></td>
								<td style='padding:0 5px'> = </td>
								<td><input class='subtotal' tabindex='-1'><input name='id' value='' hidden ></td>
								<td></td>
								</tr>`;
    	$('#qty-table-stok').append(baris);
    });
	
	$('#qty-table-stok').on('keydown','.qty , .jumlah_roll ',function (e) {
	   	if (e.which == 13) {
	        let tbi = parseFloat($(this).attr('tabindex'))+1;
	        console.log(tbi);
	        // alert(tbi);

	        $("#qty-table-stok").find(`[tabindex=${tbi}]`).focus();
        }
    });

   

    $("#qty-table-stok").on('change','.qty,.jumlah_roll',function(){
    	data_result = table_qty_update('#qty-table-stok').split('=*=');
    	let total = parseFloat(data_result[0]);
    	let total_roll = parseFloat(data_result[1]);
    	let rekap = data_result[2];


    	// let qty_ori = $("#form_add_barang [name=qty]").val().replace(",","");
    	// let roll_ori = $("#form_add_barang [name=jumlah_roll]").val();
    	
    	console.log('1',$('#form_add_data [name=rekap_qty]').val());
    	console.log('2',rekap);

    	$('.yard_total').html(total.toFixed(2));
    	$('.jumlah_roll_total').html(total_roll);
    	
    	$('#form_add_data [name=rekap_qty]').val(rekap);

    	$('#form_add_data [name=qty]').val(parseFloat(total));
    	$('#form_add_data [name=jumlah_roll]').val(total_roll);
    });

    $(".table-detail tr td").click(function() {
    	$(this).toggleClass('active');
    	updateSelected();
    })
});

function showKalk() {
	$("#table-detail-vertikal").toggle();
}

function updateSelected(){
	var totalSelected = 0;
	$(".table-detail .active").each(function() {
		totalSelected += parseFloat($(this).text());
	});

	if (totalSelected) {};
}

function resetQtyDetailTable(data){
	$(".title-form-detail").text('Tambah');

	$(".yard_total").text("0");
	$(".jumlah_roll_total").text("0");
	$("#qty-table-stok tbody").empty();
	let baris = '';
	let row = 5;
	let dt = [];
	if (data != '') {
		let d = data.split('--')
		for (var i = 0; i < d.length; i++) {
			dt[i] = d[i];
		};
		row = parseFloat(i) + 2;
	};
	// console.log(dt);

	for (var i = 1; i <= row; i++) {
		btn='';
		let qty = '';
		let roll = '';
		let id = '';

		if (dt.length > 0) {
			// console.log(dt[i-1]);
			if (typeof dt[i-1] !== 'undefined') {
				let br = dt[i-1].split('??');
				qty = br[0];
				roll = br[1];
				id = br[2];
				
			};
		};


		if (i == 1) {
			btn = `<button tabindex='-1' class='btn btn-xs blue btn-add-qty-row'><i class='fa fa-plus'></i></button>`;
		};
		baris += `<tr><td><input class='qty' tabindex="${(i*2)-1}" value='${qty}'></td>
		<td><input class='jumlah_roll'  tabindex="${i*2}"  value='${roll}'></td>
		<td style='padding:0 5px'> = </td>
		<td><input class='subtotal' tabindex='-1'><input name='id' value='${id}' hidden ></td>
		<td>${btn}</td></tr>`
	};
	// console.log(baris);

	$("#qty-table-stok tbody").append(baris);

	$("#form_add_data").find("[name=qty]").val('');
	$("#form_add_data [name=jumlah_roll]").val('');
	$("#form_add_data [name=rekap_qty]").val('');

}

function table_qty_update(table){
	// console.log('start');
	var total = 0; 
	var idx = 0; 
	var rekap = [];
	var total_roll = 0;

	$(table+" .qty").each(function(){
		var ini = $(this).closest('tr');
		var qty = $(this).val();
		// console.log(`row-${idx}`,ini.html());
		var roll = ini.find('.jumlah_roll').val();
		var id = ini.find('[name=id]').val();
		if (typeof id === 'undefined' || id == '') {
			id = '0';
		};
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



		if (roll == 0) {
    		var subtotal = parseFloat(qty);
    		total_roll += 0;
		}else{
    		var subtotal = parseFloat(qty*roll);
    		total_roll += parseFloat(roll);
		};

		// alert(subtotal);
		if (qty != '' && roll != '' && id != '') {
			roll = (roll == '' ? 0 : roll);
			rekap[idx] = qty+'??'+roll+'??'+id;
		}else if(id != 0){
			roll = (roll == '' ? 0 : roll);
			rekap[idx] = qty+'??'+roll+'??'+id;
			// alert(id);
		}
		console.log(`rekap-${idx}`, rekap[idx]);

		idx++; 

		total += subtotal;
		ini.find('.subtotal').val(qty*roll);

	});

	rekap_str = rekap.join('--');
	console.log('3',rekap_str);
	console.log(total+'=*='+total_roll+'=*='+rekap_str);

	return total+'=*='+total_roll+'=*='+rekap_str;
}

function toggleTableRinci(){
	$(`#tableRinci`).toggle();
}

</script>
