<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css'); ?>"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css'); ?>"/>
<?php echo link_tag('assets/global/plugins/bootstrap-datepicker/css/datepicker3.css'); ?>

<div class="page-content">
	<div class='container'>

		<div class="modal fade" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body">
						<form action="<?=base_url('finance/piutang_awal_insert')?>" class="form-horizontal" id="form_add_data" method="post">
							<h3 class='block'> Tambah </h3>

							<div class="form-group">
			                    <label class="control-label col-md-3">Toko<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<select class='form-control' name='toko_id'>
			                    		<?foreach ($this->toko_list_aktif as $row) { ?>
			                    			<option <?=($row->id == 1 ? 'selected' : '');?> value='<?=$row->id;?>' ><?=$row->nama;?></option>
			                    		<?}?>
			                    	</select>
			                    </div>				                    
			                </div>

			                <div class="form-group">
			                    <label class="control-label col-md-3">Tanggal<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<input type="text" class="form-control input1 date-picker" name="tanggal"/>
			                    </div>				                    
			                </div>

			                <div class="form-group">
			                    <label class="control-label col-md-3">No Faktur<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<input type="text" class="form-control" name="no_faktur"/>			                    	
			                    </div>				                    
			                </div>

			                <div class="form-group">
			                    <label class="control-label col-md-3">Jumlah Roll<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<input class='form-control' name='jumlah_roll'>
			                    </div>
			                    
			                </div>

			                <div class="form-group">
			                    <label class="control-label col-md-3">Amount<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<input class='form-control amount_number' name='amount'>
			                    </div>
			                    
			                </div>

			                <div class="form-group">
			                    <label class="control-label col-md-3">Jatuh Tempo<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<input name='customer_id' hidden value='<?=$customer_id;?>'>
			                    	<input type="text" class="form-control input1 date-picker" name="jatuh_tempo"/>
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

		<div class="modal fade" id="portlet-config-edit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body">
						<form action="<?=base_url('finance/piutang_awal_update')?>" class="form-horizontal" id="form_edit_data" method="post">
							<h3 class='block'> Edit </h3>

							<div class="form-group">
			                    <label class="control-label col-md-3">Toko<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<select class='form-control' name='toko_id'>
			                    		<?foreach ($this->toko_list_aktif as $row) { ?>
			                    			<option value='<?=$row->id;?>' ><?=$row->nama;?></option>
			                    		<?}?>
			                    	</select>
			                    </div>				                    
			                </div>

			                <div class="form-group">
			                    <label class="control-label col-md-3">Tanggal<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<input name='customer_id' hidden value='<?=$customer_id;?>'>
			                    	<input name='id'  hidden>
			                    	<input type="text" class="form-control input1 date-picker" name="tanggal"/>
			                    </div>				                    
			                </div>

			                <div class="form-group">
			                    <label class="control-label col-md-3">No Faktur<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<input type="text" class="form-control" name="no_faktur"/>			                    	
			                    </div>				                    
			                </div>

			                <div class="form-group">
			                    <label class="control-label col-md-3">Jumlah Roll<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<input class='form-control' name='jumlah_roll'>
			                    </div>
			                    
			                </div>

			                <div class="form-group">
			                    <label class="control-label col-md-3">Amount<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<input class='form-control amount_number' name='amount'>
			                    </div>
			                    
			                </div>

			                <div class="form-group">
			                    <label class="control-label col-md-3">Jatuh Tempo<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<input name='customer_id' hidden value='<?=$customer_id;?>'>
			                    	<input type="text" class="form-control input1 date-picker" name="jatuh_tempo"/>
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
		
		<div class="row margin-top-10">
			<div class="col-md-12">
				<div class="portlet light">
					<!-- <div class="portlet-title">
						<div class="caption caption-md">
							<i class="icon-bar-chart theme-font hide"></i>
							<span class="caption-subject theme-font bold uppercase"><?=$breadcrumb_small;?></span>
						</div>

						
					</div> -->
					<div class="portlet-body">

						<table width='100%'>
							<tr>
								<td>
									<?foreach ($customer_data as $row) { ?>
										<h1>Piutang Awal - <?=$row->nama;?></h1>
									<?}?>
								</td>
								<td class='text-right'>
									<div class="actions">
										<a href="#portlet-config" data-toggle='modal' class='btn btn-default btn-add-data'><i class='fa fa-plus'></i> Tambah</a>
									</div>
								</td>
							</tr>
						</table>
						
						<hr/>
						<table class="table table-hover table-striped table-bordered" id="general_table">
							<thead>
								<tr>									
									<th scope="col">
										Tanggal
									</th>
									<th scope="col">
										No Faktur
									</th>
									<th scope="col">
										Jumlah Roll
									</th>
									<th scope="col">
										Amount
									</th>
									<th scope="col">
										Jatuh Tempo
									</th>
									<th scope="col">
										Pelunasan
									</th>
									<th scope="col">
										Action
									</th>
								</tr>
							</thead>
							<tbody>
								<?
								$tipe[1] = 'TRANSFER'; 
								$tipe[2] = 'GIRO';
								$tipe[3] = 'CASH';
								$tipe[4] = 'EDC';
								$tipe[5] = 'DP';
												
								foreach ($piutang_list_detail as $row) { 
									$total_bayar = 0;
									$pembayaran_type_id = explode(',', $row->pembayaran_type_id);
									$amount_bayar = explode(',', $row->amount_bayar);
									$tanggal_bayar = explode(',', $row->tanggal_bayar);
									?>
									<tr>
										<td>
											<span class='tanggal'><?=is_reverse_date($row->tanggal);?></span> 
										</td>
										<td>
											<span class='no_faktur'><?=$row->no_faktur;?></span> 
										</td>
										<td>
											<span class='jumlah_roll'><?=$row->jumlah_roll;?></span> 
										</td>
										<td>
											<span class='amount'><?=number_format($row->amount,'0',',','.');?></span> 
										</td>
										<td>
											<span class='jatuh_tempo'><?=is_reverse_date($row->jatuh_tempo);?></span> 
										</td>
										<td>
											<table>
												<?foreach ($pembayaran_type_id as $key => $value) {
													$total_bayar += $amount_bayar[$key];?>
													<tr>
														<td><?=$tipe[$value]?></td>
														<td style='padding:0 10px'><?=is_reverse_date($tanggal_bayar[$key])?></td>
														<td><?=number_format($amount_bayar[$key],'0',',','.');?></td>
													</tr>
												<?}?>
												
											</table>

										</td>
										<td>
											<span class='id' hidden><?=$row->id;?></span>
											<span class='toko_id' hidden><?=$row->toko_id;?></span>
											<a href="#portlet-config-edit" data-toggle='modal' class="btn btn-xs green btn-edit" ><i class='fa fa-edit'></i></a>
											<?if ($row->pembayaran_type_id != '') {?>
												<a href="<?=base_url().is_setting_link('finance/pembayaran_piutang_form');?>?id=<?=$row->pembayaran_piutang_id?>" data-toggle='modal' class="btn btn-xs blue btn-edit" >Pelunasan</a>
											<?}?>
										</td>
									</tr>
								<?}?>
							</tbody>
						</table>
					</div>
					<div>
	                	<a href="javascript:window.open('','_self').close();" class="btn default button-previous hidden-print">Close</a>
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

<script>
jQuery(document).ready(function() {

	// dataTableTrue();

	// oTable = $('#general_table').DataTable();
	// oTable.state.clear();
	// oTable.destroy();

	$('#general_table').on('click','.btn-edit', function(){
		var form = "#form_edit_data";
		var ini = $(this).closest('tr');

		$(form+" [name=id]").val(ini.find('.id').html());
		$(form+" [name=toko_id]").val(ini.find('.toko_id').html());
		$(form+" [name=tanggal]").val(ini.find('.tanggal').html());
		$(form+" [name=no_faktur]").val(ini.find('.no_faktur').html());
		$(form+" [name=jumlah_roll]").val(ini.find('.jumlah_roll').html());
		$(form+" [name=amount]").val(ini.find('.amount').html());
		$(form+" [name=jatuh_tempo]").val(ini.find('.jatuh_tempo').html());
		
	});

	$('.btn-save').click(function(){
		var form = "#form_add_data";
		if ($(form+' [name=tanggal]').val() != '' && $(form+' [name=no_faktur]').val() != '' && $(form+' [name=amount]').val() != '' ) {
			$(form).submit();
		}else{
			alert('Semua data harus diisi');
		}
	});

	$('.btn-edit-save').click(function(){
		var form = "#form_edit_data";
		if ($(form+' [name=tanggal]').val() != '' && $(form+' [name=no_faktur]').val() != '' && $(form+' [name=amount]').val() != '' ) {
			$(form).submit();
		}else{
			alert('Semua data harus diisi');
		}
	});

});
</script>
