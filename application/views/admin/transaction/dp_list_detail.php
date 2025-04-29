<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css'); ?>"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css'); ?>"/>
<?php echo link_tag('assets/global/plugins/bootstrap-datepicker/css/datepicker3.css'); ?>

<style type="text/css">
textarea{
	resize:none;
}
</style>

<div class="page-content">
	<div class='container'>

		<div class="modal fade bs-modal-lg" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-body">
						<form action="<?=base_url('transaction/dp_masuk_insert')?>" class="form-horizontal" id="form_add_data" method="post">
							<h3 class='block'> DP Baru</h3>
							
							<div class="form-group">
			                    <label class="control-label col-md-4">Customer<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<input name='customer_id' value='<?=$customer_id;?>' hidden>
			                    	<input readonly class='form-control' name='nama_customer' value="<?=$nama_customer;?>">
			                    </div>
			                </div>	

			                <div class="form-group">
			                    <label class="control-label col-md-4">Jenis Pembayaran<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
	                    			<select name='pembayaran_type_id' class='form-control' id="pembayaran_type_id" >
	                    				<?foreach ($pembayaran_type_list as $row) { ?>
	                    					<option value='<?=$row->id;?>'><?=$row->nama?></option>
	                    				<?}?>
	                    			</select>
			                    </div>
			                </div>

			               <div class="form-group">
			                    <label class="control-label col-md-4">Tanggal DP<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<input class='form-control date-picker' name='tanggal' id='add-tanggal' value="<?=date('d/m/Y');?>">
			                    </div>
			                </div>

			                <div class="form-group urutan-giro-info" hidden>
			                    <label class="control-label col-md-4">Urutan<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<input readonly class='form-control' name='urutan_giro' style='background:yellow; font-weight:bold'>
			                    </div>
			                </div>


							<div class="form-group type_2">
			                    <label class="control-label col-md-4">Nama Bank<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
	                    			<input name='nama_bank' class='form-control' >
			                    </div>
			                </div> 

			                <div class="form-group type_2">
			                    <label class="control-label col-md-4">No Rek Bank<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
	                    			<input name='no_rek_bank' class='form-control' >
			                    </div>
			                </div> 

			                <div class="form-group type_2 type_3 type_4">
			                    <label class="control-label col-md-4">Jatuh Tempo<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
	                    			<input name='jatuh_tempo' class='form-control date-picker' >
			                    </div>
			                </div> 

			                <div class="form-group type_2 type_3 type_4">
			                    <label class="control-label col-md-4">No Giro<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
	                    			<input name='no_giro' class='form-control' >
			                    </div>
			                </div>

			                <div class="form-group">
			                    <label class="control-label col-md-4">Nilai<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
	                    			<input name='amount' class='form-control amount-number' >
			                    </div>
			                </div>

			                <div class="form-group type_6 type_4 type_3">
			                    <label class="control-label col-md-4">Penerima<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
	                    			<input name='nama_penerima' class='form-control' >
			                    </div>
			                </div> 

			                <div class="form-group">
			                    <label class="control-label col-md-4">Keterangan
			                    </label>
			                    <div class="col-md-6">
	                    			<textarea name='keterangan' class='form-control' rows='3'></textarea>
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

		<div class="modal fade bs-modal-lg" id="portlet-config-edit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-body">
						<form action="<?=base_url('transaction/dp_masuk_update')?>" class="form-horizontal" id="form_edit_data" method="post">
							<h3 class='block'> DP Edit</h3>
							
							<div class="form-group">
			                    <label class="control-label col-md-4">Customer<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<input name='dp_masuk_id' value='<?=$dp_masuk_id;?>' hidden>
			                    	<input name='urutan_giro' hidden>
			                    	<input name='customer_id' value='<?=$customer_id;?>' hidden>
			                    	<input readonly class='form-control' name='nama_customer' value="<?=$nama_customer;?>">
			                    </div>
			                </div>	

			                <div class="form-group">
			                    <label class="control-label col-md-4">Jenis Pembayaran<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
	                    			<select name='pembayaran_type_id' class='form-control' id="pembayaran_type_id_edit" >
	                    				<?foreach ($pembayaran_type_list as $row) { ?>
	                    					<option value='<?=$row->id;?>'><?=$row->nama?></option>
	                    				<?}?>
	                    			</select>
			                    </div>
			                </div>

							<div class="form-group">
			                    <label class="control-label col-md-4">Tanggal DP<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<input class='form-control date-picker' name='tanggal' value="<?=date('d/m/Y');?>">
			                    </div>
			                </div>


			                <div class="form-group type_2">
			                    <label class="control-label col-md-4">Nama Bank<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
	                    			<input name='nama_bank' class='form-control' >
			                    </div>
			                </div> 

			                <div class="form-group type_2">
			                    <label class="control-label col-md-4">No Rek Bank<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
	                    			<input name='no_rek_bank' class='form-control' >
			                    </div>
			                </div> 

			                <div class="form-group type_2 type_3 type_4">
			                    <label class="control-label col-md-4">Jatuh Tempo<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
	                    			<input name='jatuh_tempo' class='form-control date-picker' >
			                    </div>
			                </div> 

			                <div class="form-group type_2 type_3 type_4">
			                    <label class="control-label col-md-4">No Giro<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
	                    			<input name='no_giro' class='form-control' >
			                    </div>
			                </div>

			                <div class="form-group">
			                    <label class="control-label col-md-4">Nilai<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
	                    			<input name='amount' class='form-control amount-number' >
			                    </div>
			                </div>

			                <div class="form-group type_6 type_4 type_3 ">
			                    <label class="control-label col-md-4">Penerima<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
	                    			<input name='nama_penerima' class='form-control' >
			                    </div>
			                </div> 

			                <div class="form-group">
			                    <label class="control-label col-md-4">Keterangan
			                    </label>
			                    <div class="col-md-6">
	                    			<textarea name='keterangan' class='form-control' rows='3'></textarea>
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

		<div class="modal fade bs-modal-lg" id="portlet-config-keluar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-body">
						<form action="<?=base_url('transaction/dp_keluar_update')?>" class="form-horizontal" id="form_dp_keluar_data" method="post">
							<h3 class='block'> DP Keluar</h3>
							
							<div class="form-group">
			                    <label class="control-label col-md-4">Customer<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<input name='dp_masuk_id' hidden>
			                    	<input name='dp_keluar_id' hidden>
			                    	<input name='customer_id' value='<?=$customer_id;?>' hidden>
			                    	<input readonly class='form-control' name='nama_customer' value="<?=$nama_customer;?>">
			                    </div>
			                </div>	

			                <div class="form-group">
			                    <label class="control-label col-md-4">Jenis Pembayaran<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
	                    			<select name='pembayaran_type_id' class='form-control' id="pembayaran_type_id_keluar" >
	                    				<?foreach ($pembayaran_type_list as $row) { 
	                    					if ($row->nama == 'TRANSFER' || $row->nama == 'CASH') {?>
		                    					<option value='<?=$row->id;?>'><?=$row->nama?></option>
	                    					<?}?>
	                    				<?}?>
	                    			</select>
			                    </div>
			                </div>

							<div class="form-group">
			                    <label class="control-label col-md-4">Tanggal Transaksi<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<input class='form-control date-picker' name='tanggal' value="<?=date('d/m/Y');?>">
			                    </div>
			                </div>


			                <div class="form-group type_2">
			                    <label class="control-label col-md-4">Nama Bank<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
	                    			<input name='nama_bank' class='form-control' >
			                    </div>
			                </div> 

			                <div class="form-group type_2">
			                    <label class="control-label col-md-4">No Rek Bank<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
	                    			<input name='no_rek_bank' class='form-control' >
			                    </div>
			                </div> 

			                <div class="form-group">
			                    <label class="control-label col-md-4">Nilai<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
	                    			<input name='amount' class='form-control amount-number' >
			                    </div>
			                </div>

			                <div class="form-group type_6 type_4 type_3 ">
			                    <label class="control-label col-md-4">Penerima<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
	                    			<input name='nama_penerima' class='form-control' >
			                    </div>
			                </div> 

			                <div class="form-group">
			                    <label class="control-label col-md-4">Keterangan
			                    </label>
			                    <div class="col-md-6">
	                    			<textarea name='keterangan' class='form-control' rows='3'></textarea>
			                    </div>
			                </div> 

						</form>
					</div>

					<div class="modal-footer">
						<button type="button" class="btn blue btn-active" onclick="submitDPKeluar()" id="btn-keluar-save">Save</button>
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
						<h3 class='block'> Printer</h3>
						
						<div class="form-group">
		                    <label class="control-label col-md-3">Type<span class="required">
		                    * </span>
		                    </label>
		                    <div class="col-md-6">
		                    	<input name='print_target' hidden>
		                    	<span class='print-content'></span>
		                    	<select class='form-control' id='printer-name'>
		                    		<?foreach ($printer_list as $row) {
		                    			$default_printer = (get_default_printer() == $row->id ? $row->nama : '');?>
		                    			<option  <?=(get_default_printer() == $row->id ? 'selected' : '');?> value='<?=$row->id;?>'><?=$row->nama;?> <?//=(get_default_printer() == $row->id ? '(default)' : '');?></option>
		                    		<?}?>
		                    	</select>
		                    </div>
		                </div>
					</div>

					<div class="modal-footer">
						<button type="button" class="btn blue btn-active btn-print-action" data-dismiss="modal">Print</button>
						<button type="button" class="btn btn-active default" data-dismiss="modal">Close</button>
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
							<a href="#portlet-config" data-toggle='modal' class="btn btn-default btn-sm btn-form-add">
							<i class="fa fa-plus"></i> Tambah </a>
						</div>
					</div>
					<div class="portlet-body">
						<?if ($dp_masuk_id_group == '') {?>
							<form hidden>
								<table>
									<tr>
										<td>Tanggal</td>
										<td>
											<div class="input-group input-large date-picker input-daterange">
												<input type="text" style='border:none; border-bottom:1px solid #ddd; background:white' class="form-control" name="from" value='<?=is_reverse_date($from); ?>'>
												<span class="input-group-addon">
												s/d </span>
												<input type="text" style='border:none; border-bottom:1px solid #ddd; background:white' class="form-control" name="to" value='<?=is_reverse_date($to); ?>'>
											</div>
										</td>
										<td>
											<button class='btn btn-sm green'><i class='fa fa-search'></i></button>
										</td>
									</tr>
								</table>
							</form>
							<form id='filter-form'>
								<table>
									<tr>
										<td style='vertical-align:top'>FIlter</td>
										<td class='padding-rl-5' style='vertical-align:top'>:</td>
										<td>
											<div class="radio-list">
												<label >
													<input name='view_type' type='radio' value='1' <?=($view_type == 1 ?'checked' : "" );?> >Saldo > 0</label>
												<label >
													<input name='view_type' type='radio' value='2' <?=($view_type == 2 ?'checked' : "" );?> >Semua</label>
											</div>
										</td>
									</tr>
								</table>
							</form>
						<?}else{?>
							<a style='width:100%' class='btn btn-default green' href="<?=base_url().is_setting_link('transaction/dp_list_detail')?>/<?=$customer_id;?>"><i class='fa fa-arrow-left' style='color:white'></i> Perlihatkan Semua Transaksi DP</a>
						<?}?>
						<hr/>
						<?//=(is_posisi_id() == 1 ? "<h1>".$view_type."</h1>" : '')?>
						<?if ($view_type==1 || $view_type==2) {?>
							<table class="table table-striped table-bordered table-hover" id="general_table">
								<thead>
									<tr>
										<th scope="col">
											Tanggal
										</th>
										<th scope="col">
											No Transaksi DP
										</th>
										<th scope="col">
											DP Masuk
										</th>
										<th scope="col">
											DP Keluar
										</th>
										<th scope="col">
											Saldo
										</th>
										<th scope="col" style="min-width:150px !important">
											Actions
										</th>
									</tr>
								</thead>
								<tbody>
									<?
									$saldo_akhir = 0;
									foreach ($dp_list_detail as $row) { ?>
										<tr>
											 <td>
											 	<?//=(is_posisi_id() == 1 ? "<h1>".$row->type_out."</h1>" : '')?>
												<span class='id' hidden="hidden"><?=$row->id;?></span>
												<span class='tanggal'><?=is_reverse_date($row->tanggal);?></span>
												<span hidden class='tanggal-print'><?=sprintf('%40.27s', 'BANDUNG, '.is_reverse_date($row->tanggal));?></span>
											</td>
											<td>
												<?if ($row->type == 'i') {
													unset($dt_print);
													$data_keluar = explode(',', $row->data_keluar);
													$dp_keluar_amount_data = explode(',', $row->dp_keluar_amount_data );
													$tanggal_keluar = explode(',', $row->tanggal_keluar );
													$trx_id = explode(',', $row->trx_id );
													$type_out = explode(',', $row->type_out );
													// $ = explode(',', $row-> );
													// $ = explode(',', $row-> );

													?>
													<!--keterangan bayar-->
													<?=$row->bayar_dp;?> : <b style='font-size:1.2em'><?=$row->no_faktur_lengkap;?></b>
													<span class='no_faktur_dp' hidden><?=sprintf('%30.30s', 'NO PENERIMAAN : '.$row->no_faktur_lengkap);?></span>
													<?
													$type_2 = '';
													$type_3 = '';
													$type_4 = '';
													$type_6 = '';
													${'type_'.$row->pembayaran_type_id} = 'hidden';

													if ($row->pembayaran_type_id == 2) {
													   	$dt_print[0] = sprintf('%-13.13s', "Transaksi  :")."CASH";
													   	$dt_print[3] = sprintf('%-13.13s', "")."diterima o/ ". $row->nama_penerima;
													}

													if ($row->pembayaran_type_id == 3) {
													   	$dt_print[0] = sprintf('%-13.13s', "Transaksi  :")." EDC";
													}

													if ($row->pembayaran_type_id == 4) {
													   	$dt_print[0] = sprintf('%-13.13s', "Transaksi  :")."TRANSFER";
													   	if ($row->nama_bank != '') {
															$dt_print[3] = sprintf('%-13.13s', "").'Bank '.$row->nama_bank;
															$dt_print[4] = sprintf('%-13.13s', "").'No Rek. '.$row->no_rek_bank;
													   	}
													}
													if ($row->pembayaran_type_id == 6) {
													   	$dt_print[0] = sprintf('%-13.13s', "Transaksi  :")."GIRO";
														$dt_print[3] = sprintf('%-13.13s', "").'Bank '.$row->nama_bank;
														$dt_print[4] = sprintf('%-13.13s', "").'No Rek. '.$row->no_rek_bank;
														$dt_print[5] = sprintf('%-13.13s', "")."No Giro ".$row->no_giro;
														$dt_print[6] = sprintf('%-13.13s', "")."Jatuh Tempo ".is_reverse_date($row->jatuh_tempo);
													}
													?>
													<ul>
														<li <?=$type_2;?> <?=$type_3;?> <?=$type_4;?> >Urutan :<b><span class='urutan-giro' style='padding:2px 3px; background:#0ff' ><?=$row->urutan_giro?></span></b></li>
														<li <?=$type_3;?> <?=$type_4;?> <?=$type_6;?> >Penerima :<b><span class='nama_penerima' ><?=$row->nama_penerima?></span></b></li>
														<li <?=$type_2;?> >Bank : <b><span class='nama_bank'><?=$row->nama_bank?></span></b></li>
														<li <?=$type_2;?> >No Rek : <b><span class='no_rek_bank'><?=$row->no_rek_bank?></span></b></li>
														<li <?=$type_3;?> <?=$type_4;?> <?=$type_2;?>>Jatuh Tempo : <b><span class='jatuh_tempo' ><?=is_reverse_date($row->jatuh_tempo);?></span></b></li>
														<li <?=$type_3;?> <?=$type_4;?> <?=$type_2;?> >No Giro : <b><span class='no_giro' ><?=$row->no_giro;?></span></b></li>
														<li>Keterangan : <b><?=nl2br($row->keterangan);?></b></li>
														<?
															$tempI = 0;
															foreach (explode("\n", $row->keterangan) as $key => $value) {
																if ($tempI == 0) {
																	$keterangan_new[$tempI] = sprintf('%-13.13s', "Keterangan :").$value;
																}else{
																	$keterangan_new[$tempI] = sprintf('%-13.13s', "").$value;
																}
																$tempI++;
															}
														?>
														<span class='keterangan' hidden><?=$row->keterangan;?></span>
														<span class='keterangan-dp' hidden><?=implode('??', $keterangan_new);?></span>
														<span class='keterangan-print' hidden><?=implode('??', $dt_print);?></span>
														<span class='number-write' hidden><?=is_number_write($row->dp_masuk);?></span>
													</ul>
												<?}?>
											</td>
											<td>
												<span class='amount'><?=number_format($row->dp_masuk,'0',',','.');?></span>
												<?$saldo = $row->dp_masuk?>
											</td>
											<td>
												<?$sub_dp_keluar = 0;
												foreach ($dp_keluar_amount_data as $key => $value) {
													$sub_dp_keluar += $value;
													if ($type_out[$key] == 'pj') {?>
														<?=number_format(($value == '' ? 0 : $value),'0',',','.');?> : <b><a target='_blank' href="<?=base_url().is_setting_link('transaction/penjualan_list_detail')?>?id=<?=$trx_id[$key];?>"><?=$data_keluar[$key]?></a> </b>
														 - <small><?=is_reverse_date($tanggal_keluar[$key]);?></small>  <br/>													
													<?}elseif($type_out[$key] == 'dpk'){?>
															<?=number_format(($value == '' ? 0 : $value),'0',',','.');?> : <b><a href="#portlet-config-keluar" data-toggle="modal" onclick="setDPKeluarEdit('<?=$trx_id[$key].'??'.is_reverse_date($tanggal_keluar[$key]).'??'.$data_keluar[$key].'??'.$row->id?>')">Deposit Kembali</a> </b>
														 - <small><?=is_reverse_date($tanggal_keluar[$key]);?></small>  <br/>	
													<?}else{?>
														<?=number_format(($value == '' ? 0 : $value),'0',',','.');?> : <b><a target='_blank' href="<?=base_url().is_setting_link('finance/piutang_payment_form')?>?id=<?=$trx_id[$key];?>"><?=$data_keluar[$key]?></a> </b>
														 - <small><?=is_reverse_date($tanggal_keluar[$key]);?></small>  <br/>
													<?}?>
													<?
												}
												if (count($dp_keluar_amount_data) > 1) {?>
													<hr style="padding:0px; margin:0px; width:90px;border-color:#171717" />
													<?=number_format($sub_dp_keluar,'0',',','.');?> 
												<?}?>

											</td>
											<td>
												<?$saldo = $saldo - $sub_dp_keluar;?>
												<?=number_format($saldo,'0',',','.');?>
											</td>
											<td>
												<span class='bayar_dp_id' hidden><?=$row->bayar_dp_id;?></span>
												<span class='pembayaran_type_id' hidden><?=$row->pembayaran_type_id?></span>
												<span class='penerima' hidden><?=$row->penerima?></span>
												<?if ($row->type == 'i') { ?>
													<a href="#portlet-config-edit" data-toggle='modal' class="btn-xs btn green btn-edit" ><i class="fa fa-edit"></i> </a>
													<?if ($saldo > 0) {?>
														<a href="#portlet-config-keluar" data-toggle='modal' onclick="setDPKeluar(<?=$row->id;?>)" class="btn-xs btn yellow-gold" ><i class="fa fa-minus"></i> </a>
													<?}?>
													<a href="#portlet-config-print" data-toggle='modal' class="btn-xs btn blue btn-print print-bukti" target='blank'><i class="fa fa-print"></i> </a>
													<?if (is_posisi_id() < 4) {
														//href="<?=base_url().'transaction/dp_print?id=<?=$row->id';
														?>
														<button class="btn-xs btn red btn-remove" ><i class="fa fa-times"></i> </button>
													<?}?>
												<?}?>
											</td>
										</tr>
									<? } ?>

								</tbody>
							</table>
						<?}else{?>
							
						<?}?>
					</div>
					<div>
	                  	<a href="<?=base_url().is_setting_link('transaction/dp_list');?>" class="btn btn-lg default button-previous">Back</a>
	                </div>
				</div>
			</div>
		</div>
	</div>			
</div>

<script src="<?php echo base_url('assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/datatables/media/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/bootbox/bootbox.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets_noondev/js/table-advanced.js'); ?>"></script>
<script src="<?php echo base_url('assets_noondev/js/webprint.js'); ?>" type="text/javascript"></script>

<script>
jQuery(document).ready(function() {

	var global_keterangan_dp = "";
	var global_keterangan_print = "";
	var global_info_data = [];


	// dataTableTrue();

	// oTable = $('#general_table').DataTable();
	// oTable.state.clear();
	// oTable.destroy();
	$('[data-toggle="popover"]').popover();
	$('.type_2').hide();

	$("[name=view_type").change(function(){
		$('#filter-form').submit();
	});


	$('.btn-save').click(function(){
		if ($('#form_add_data [name=tanggal]').val() != '' && $('#form_add_data [name=amount]').val() != '' ) {
			$('#form_add_data').submit();
		}else{
			alert('Tanggal dan Jumlah harus diisi');
		}
	});

	$('.btn-edit-save').click(function(){
		if ($('#form_edit_data [name=tanggal]').val() != '' && $('#form_edit_data [name=amount]').val() != '' ) {
			$('#form_edit_data').submit();
		}else{
			alert('Tanggal dan Jumlah harus diisi');
		}
	});

	$('#general_table').on('click','.btn-edit', function(){
		var ini = $(this).closest('tr');

		$('#form_edit_data [name=dp_masuk_id]').val(ini.find('.id').html());
		$('#form_edit_data [name=urutan_giro]').val(ini.find('.urutan-giro').html());
		$('#form_edit_data [name=pembayaran_type_id]').val(ini.find('.pembayaran_type_id').html());
		$("#pembayaran_type_id_edit").change();
		$('#form_edit_data [name=tanggal]').val(ini.find('.tanggal').html());
		$('#form_edit_data [name=nama_penerima]').val(ini.find('.nama_penerima').html());
		$('#form_edit_data [name=nama_bank]').val(ini.find('.nama_bank').html());
		$('#form_edit_data [name=no_rek_bank]').val(ini.find('.no_rek_bank').html());
		$('#form_edit_data [name=no_giro]').val(ini.find('.no_giro').html());
		$('#form_edit_data [name=jatuh_tempo]').val(ini.find('.jatuh_tempo').html());
		$('#form_edit_data [name=amount]').val(ini.find('.amount').html());
		$('#form_edit_data [name=keterangan]').val(ini.find('.keterangan').html());

	});

	$("#pembayaran_type_id, #add-tanggal").change(function(){
		var form = '#'+$(this).closest('form').attr('id');
		let id = $(form).find('#pembayaran_type_id').val();
		let tanggal = $(form).find('[name=tanggal]').val();
		$('#form_add_data .form-group').show();
		$('#form_add_data .type_'+id).hide();
		// alert(id);
		if (id == 6) {
			$(form).find('.urutan-giro-info').show();
			if (tanggal != '') {
				predictive_urutan_giro(form, tanggal);
			};
		}else{
			$(form).find('.urutan-giro-info').hide();
		}
	});

	$("#pembayaran_type_id_edit").change(function(){
		var form = '#'+$(this).closest('form');

		let id = $(this).val();
		$('#form_edit_data .form-group').show();
		$('#form_edit_data .type_'+id).hide();
	});

	$("#pembayaran_type_id_keluar").change(function(){
		var form = '#'+$(this).closest('form').attr('id');
		// alert(form);
		let id = $(this).val();
		$(form+' .form-group').show();
		$(form+' .type_'+id).hide();
	});


	<?if (is_posisi_id() < 4) {?>
		$("#general_table").on("click",".btn-remove",function(){
			var dp_masuk_id = $(this).closest('tr').find('.id').html();
			var url = 'transaction/dp_masuk_delete';
			var data = {};
			data['dp_masuk_id'] = dp_masuk_id;
			bootbox.confirm("Yakin menghapus data dp ? <br/>", function(respond){
				if (respond) {
					ajax_data_sync(url,data).done(function(data_respond  /*,textStatus, jqXHR*/ ){
						if (data_respond == 'OK') {
							window.location.reload();
						}
					});
				};
			} );
		});
	<?}?>

//==================================================================================================

	$(".print-bukti").click(function(){
		var ini = $(this).closest('tr');
		var id = ini.find('.bayar_dp_id').html();
		$('[name=print_target]').val(id);

		global_keterangan_dp = ini.find('.keterangan-dp').html();
		global_keterangan_print = ini.find('.keterangan-print').html();
		global_info_data['tanggal'] = ini.find('.tanggal-print').html();
		global_info_data['amount'] = ini.find('.amount').html();
		global_info_data['no_write'] = ini.find('.number-write').html();
		global_info_data['no_faktur_dp'] = ini.find('.no_faktur_dp').html();
	});

	$(".btn-print-action").click(function(){
		var selected = $('#printer-name').val();

		var printer_name = $("#printer-name [value='"+selected+"']").text();
		printer_name = $.trim(printer_name);
		// alert(printer_name);
		print_bukti_dp(printer_name, global_keterangan_print, global_info_data, global_keterangan_dp);
	});

	/* webprint = new WebPrint(true, {
        relayHost: "127.0.0.1",
        relayPort: "8080",
        readyCallback: function(){
            // webprint.requestPrinters();
        }
    }); */

});

function predictive_urutan_giro(form, tanggal){
	var data = {};
	var url = 'finance/predictive_urutan_giro';
	data['tanggal'] = tanggal; 
	ajax_data_sync(url,data).done(function(data_respond  /*,textStatus, jqXHR*/ ){
		$(form+' [name=urutan_giro]').val(data_respond);
	});
}
</script>

<?

foreach ($toko_data as $row) {
	$nama_toko = $row->nama;
	$alamat_toko = $row->alamat;
	$telepon = $row->telepon;
	$fax = $row->fax;
	$kota = $row->kota;
	$npwp = $row->NPWP;
}

foreach ($customer_data as $row) {
	$nama_customer = $row->nama;
	$alamat_customer = $row->alamat;
}

$alamat1 = substr(strtoupper(trim($alamat_customer)), 0,47);
$alamat2 = substr(strtoupper(trim($alamat_customer)), 47);
$last_1 = substr($alamat1, -1,1);
$last_2 = substr($alamat2, 0,1);

$positions = array();
$pos = -1;
while (($pos = strpos(trim($alamat_customer)," ", $pos+1 )) !== false) {
	$positions[] = $pos;
}

$max = 47;
if ($last_1 != '' && $last_2 != '') {
	$posisi =array_filter(array_reverse($positions),
		function($value) use ($max) {
			return $value <= $max;
		});

	$posisi = array_values($posisi);

	$alamat1 = substr(strtoupper(trim($alamat_customer)), 0,$posisi[0]);
   	$alamat2 = substr(strtoupper(trim($alamat_customer)), $posisi[0]);
}
?>

<script type="text/javascript">

function setDPKeluar(id) {
	let form = $('#form_dp_keluar_data');
	form.find("[name=dp_masuk_id]").val(id);
}

function setDPKeluarEdit(data_list) {
	console.log(data_list);
	let dt = data_list.split("??");
	let id = dt[0];
	let tanggal = dt[1];
	let penerima = dt[2];
	let nama_bank = dt[3];
	let no_rek_bank = dt[4];
	let keterangan = dt[5];
	let pembayaran_type_id = dt[6];
	let amount = parseFloat(dt[7]);
	let dp_masuk_id = dt[8];

	let form = $('#form_dp_keluar_data');
	form.find("[name=dp_masuk_id]").val(dp_masuk_id);
	form.find("[name=dp_keluar_id]").val(id);
	form.find("[name=tanggal]").val(tanggal);
	$("#pembayaran_type_id_keluar").val(pembayaran_type_id);
	$("#pembayaran_type_id_keluar").change();
	form.find("[name=nama_bank]").val(nama_bank);
	form.find("[name=no_rek_bank]").val(no_rek_bank);
	form.find("[name=amount]").val(amount);
	form.find("[name=nama_penerima]").val(penerima);
	form.find("[name=keterangan]").val(keterangan);
}

function submitDPKeluar() {
	let form = $('#form_dp_keluar_data');
	let tanggal = form.find("[name=tanggal]").val();
	let amount = form.find("[name=amount]").val();
	if (tanggal != '' && amount != '' ) {
		btn_disabled_load($("#btn-keluar-save"));
		form.submit();
	}else{
		alert('Tanggal dan Jumlah harus diisi');
	}
}

function print_bukti_dp(printer_name, keterangan_print, info_data, keterangan_dp){
	
	var print_info = "";
	$.each(keterangan_print.split('??'), function(i,v){
		if ($.trim(v) != '') {
			print_info += v +'\x0A';
		};
	});

	var info_count = keterangan_print.split('??').length;

	var keterangan_new="";
	$.each(keterangan_dp.split('??'), function(i,v){
		if ($.trim(v) != '') {
			keterangan_new += v;
		};
	});

	var keterangan_count = keterangan_dp.split('??').length;

	var add_line = '';
	for (var i = keterangan_count; i < 3; i++) {
	   	add_line +='\x0A';
   	};

   	for (var i = info_count; i < 5; i++) {
	   	add_line +='\x0A';
   	};

   	for (var i = 0; i < 7; i++) {
	   	add_line +='\x0A';
   	};

	// var config = qz.configs.create("Nota");             // Exact printer name from OS
  	var data = ['\x1B' + '\x40'+          // init
	   	'\x1B' + '\x21' + '\x39'+ // em mode on
	   	<?="'".sprintf('%-20.18s','PENERIMAAN DP')."'";?>+'\x09'+

	   	'\x1B' + '\x21' + '\x18'+ // em mode on
	   	`${info_data['tanggal']}` + '\x0A'+
	   	<?="'".sprintf('%-35.35s', strtoupper($nama_toko) )."'";?>+
	   	<?="'".sprintf('%45.45s', 'Kepada Yth,')."'";?> + '\x0A'+
	   	
	   	<?="'".sprintf('%-35.35s', strtoupper($alamat_toko))."'";?>+'\x09'+
	   	<?='"'.sprintf('%40.40s', strtoupper($nama_customer) ).'"';?> + '\x0A'+

	   	'\x1B' + '\x21' + '\x18'+ // em mode on
	   	<?="'".sprintf('%-30.30s', 'TELP:'.$telepon)."'";?>+'\x09'+
	   	<?="'".sprintf('%48.48s', trim($alamat1))."'";?> + '\x0A'+

	   	<?="'".sprintf('%-30.30s', ($fax != '' ? 'FAX:'.$fax : '') )."'";?>+'\x09'+
	   	<?="'".sprintf('%48.48s', trim($alamat2) )."'";?> + '\x0A'+

	   	<?="'".sprintf('%-30.30s', 'NPWP : '.$npwp)."'";?>+'\x09'+
	   	<?="'".sprintf('%48.48s', strtoupper($kota))."'";?> + '\x0A'+


	   	'\x1B' + '\x21' + '\x18'+ // em mode on
	   	<?="'".sprintf("%'-80s", '')."'";?>+ '\x0A'+
	   	<?="'".sprintf('%-47.47s', "" )."'";?>+'\x09'+
	   	`${info_data['no_faktur_dp']}` + '\x0A'+
	   	<?="'".sprintf("%'-80s", '')."'";?>+ '\x0A'+

	   	<?="'".sprintf('%-57.57s', "Telah Diterima Dari ".$nama_customer )."'";?>+'\x0A'+
	   	<?="'".sprintf('%-11.11s', "Nilai ")."'";?>+`: ${info_data['amount']}`+'\x0A'+
	   	<?="'".sprintf('%-11.11s', "Terbilang ")."'";?>+`: ${info_data['no_write']}`+'\x0A'+
	   	`${keterangan_new}`+'\x0A'+
	   	`${print_info}`+'\x0A'+
	   	`${add_line}`+
	   	

		<?echo "'".sprintf('%-5.0s %-17.17s %-12.12s %-17.17s ', '','Yang Menyerahkan', '','Yang Menerima')."'";?>+'\x0a',
	   	
	   	//'\x1D' + '\x56',          // cut paper
	   	
    ];

	console.log(data);
	// alert(printer_name);
    webprint.printRaw(data, printer_name);


	// return data;

	// qz.print(config, data).then(function() {
	//    // alert("Sent data to printer");
	// });
}

</script>