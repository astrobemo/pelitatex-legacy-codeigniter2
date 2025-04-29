<link href="<?=base_url('assets/global/plugins/bootstrap-modal/css/bootstrap-modal.css');?>" rel="stylesheet" type="text/css"/>
<?=link_tag('assets/global/plugins/bootstrap-datepicker/css/datepicker3.css'); ?>

<style type="text/css">
.subtotal-data{
	font-size: 1.2em;
}

.po-info-hide{
	display: none;
}
</style>
<div class="page-content">
	<div class='container'>

		<?
			$pembelian_id = '';
			$supplier_id = '';
			$nama_supplier = '';
			$gudang_id = '';
			$nama_gudang = '';
			$no_faktur = '';
			$ockh_info = '';
			$tanggal = '';
			$ori_tanggal = '';
			$toko_id = '';
			$nama_toko = '';
			
			$jatuh_tempo = '';
			$ori_jatuh_tempo = '';
			$diskon = 0;
			$status = 0;
			$status_aktif = 0;
			$keterangan = '';

			$po_pembelian_batch_id = '';
			$po_number = '';

			foreach ($pembelian_data as $row) {
				$pembelian_id = $row->id;
				$supplier_id = $row->supplier_id;
				$nama_supplier = $row->nama_supplier;
				$no_faktur = $row->no_faktur;

				$tanggal = is_reverse_date($row->tanggal);
				$ori_tanggal = $row->tanggal;
			
				$jatuh_tempo = is_reverse_date($row->jatuh_tempo);
				$ori_jatuh_tempo = $row->jatuh_tempo;

				// $diskon = $row->diskon;
				$status_aktif = $row->status_aktif;
				$keterangan = $row->keterangan;

			
			}

			$readonly = ''; $disabled = '';


		?>


		<div class="modal fade" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body">
						<form action="<?=base_url('transaction/pembelian_lain_insert')?>" class="form-horizontal" id="form_add_data" method="post">
							<h3 class='block'> Pembelian Baru</h3>

							<div class="form-group">
			                    <label class="control-label col-md-3">Supplier<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<select class='input1 form-control supplier-input' id="supplier-input-add" style='font-weight:bold' name="supplier_id">
			                    		<?foreach ($this->supplier_list_aktif as $row) { ?>
			                    			<?if ($row->tipe_supplier == 2) {?>
				                    			<option value="<?=$row->id?>"><?=$row->nama;?></option>
			                    			<?} ?>
			                    		<? } ?>
			                    	</select>
			                    </div>
			                </div>

			                <div class="form-group">
			                    <label class="control-label col-md-3">No Faktur<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                		<input type="text" class="form-control" name="no_faktur" id="no-faktur" />
			                    	<div class='note note-warning info-faktur' hidden></div>
			                    </div>
			                </div>

			                <div class="form-group">
			                    <label class="control-label col-md-3">Tanggal<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
					                <input type="text" readonly class="form-control date-picker" value="<?=date('d/m/Y');?>" name="tanggal"/>
			                    </div>
			                </div>
						</form>
					</div>

					<div class="modal-footer">
						<button type="button" class="btn blue btn-active btn-trigger btn-save">Save</button>
						<button type="button" class="btn default  btn-active" data-dismiss="modal">Close</button>
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
						<form action="<?=base_url('transaction/pembelian_lain_update')?>" class="form-horizontal" id="form_edit_data" method="post">
							<h3 class='block'> Edit Data</h3>
							
							<input name='pembelian_id' value="<?=$pembelian_id;?>" hidden>
							<div class="form-group">
			                    <label class="control-label col-md-3">Supplier<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<select class='input1 form-control supplier-input' id="supplier-input-add" style='font-weight:bold' name="supplier_id">
			                    		<?foreach ($this->supplier_list_aktif as $row) { ?>
			                    			<?if ($row->tipe_supplier == 2) {?>
				                    			<option value="<?=$row->id?>"><?=$row->nama;?></option>
			                    			<?} ?>
			                    		<? } ?>
			                    	</select>
			                    </div>
			                </div>

			                <div class="form-group">
			                    <label class="control-label col-md-3">No Faktur<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                		<input type="text" class="form-control" name="no_faktur" id="no-faktur" value="<?=$no_faktur?>" />
			                    	<div class='note note-warning info-faktur' hidden></div>
			                    </div>
			                </div>

			                <div class="form-group">
			                    <label class="control-label col-md-3">Tanggal<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
					                <input type="text" readonly class="form-control date-picker" value="<?=date('d/m/Y');?>" name="tanggal"/>
			                    </div>
			                </div>
						</form>
					</div>

					<div class="modal-footer">
						<button type="button" class="btn blue btn-active btn-trigger btn-edit-save">Save</button>
						<button type="button" class="btn default btn-active" data-dismiss="modal">Close</button>
					</div>
				</div>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
		</div>

		<div class="modal fade" id="portlet-config-detail" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body">
						<form action="<?=base_url('transaction/pembelian_lain_detail_insert')?>" class="form-horizontal" id="form_add_barang" method="post">
							<h3 class='block'> Tambah Barang</h3>
							
	                    	<input name='pembelian_detail_id' hidden>
	                    	<input name='pembelian_id' value='<?=$pembelian_id;?>' hidden>
	                    	<div class="form-group">
			                    <label class="control-label col-md-3">Keterangan
			                    </label>
			                    <div class="col-md-6">
			                		<textarea type="text" class='form-control' name="keterangan_barang" rows='5' /></textarea>
			                    </div>
			                </div>   

			                <div class="form-group">
			                    <label class="control-label col-md-3">Qty <span class='satuan_unit'></span> <span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                		<input type="text" class="form-control" name="qty"/>
			                    </div>
			                </div>

			                <div class="form-group">
			                    <label class="control-label col-md-3">Harga <span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
		                			<input type="text" class='amount_number form-control' name="harga_beli" id="harga_beli" />
			                    </div>
			                </div>
						</form>
					</div>

					<div class="modal-footer">
						<button type="button" class="btn blue btn-active btn-trigger btn-save-brg">Save</button>
						<button type="button" class="btn default btn-active" data-dismiss="modal">Close</button>
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
							<i class="fa fa-plus"></i> Pembelian Baru </a>
						</div>
					</div>
					<div class="portlet-body">
						<table width='100%'>
							<tr>
								<td>
									<table>
										<?if ($pembelian_id != '') { ?>
											<tr>
												<td colspan='3'>
													<button href="#portlet-config-edit" data-toggle='modal' class='btn btn-xs '><i class='fa fa-edit'></i> edit</button>
												</td>
											</tr>
										<?}?>
										<tr>
								    		<td>No Faktur</td>
								    		<td class='padding-rl-5'> : </td>
								    		<td class='td-isi-bold'>
								    			<?=$no_faktur;?>
								    		</td>
								    	</tr>
								    	<tr>
								    		<td>Tanggal</td>
								    		<td class='padding-rl-5'> : </td>
								    		<td class='td-isi-bold'><?=$tanggal;?></td>
								    	</tr>
								    	<tr>
								    		<td>Supplier</td>
								    		<td class='padding-rl-5'> : </td>
								    		<td class='td-isi-bold'>
								    			<?=$nama_supplier;?>
								    		</td>
								    	</tr>					    	
								    </table>
								</td>
							</tr>
						</table>

					    <hr/>
						<!-- table-striped table-bordered  -->
						<table class="table table-hover table-striped table-bordered" id="general_table">
							<thead>
								<tr>
									<th scope="col">
										No
									</th>
									<th scope="col">
										Barang
										<?if ($pembelian_id != '' ) { ?>
											<a href="#portlet-config-detail" data-toggle='modal' class="btn btn-xs blue btn-brg-add">
											<i class="fa fa-plus"></i> </a>
										<?}?>
									</th>
									<th scope="col">
										Qty
									</th>
									<th scope="col">
										Harga
									</th>
									<th scope="col">
										Total Harga
									</th>
									<th scope="col">
										Action
									</th>
								</tr>
							</thead>
							<tbody>
								<?
								$idx =1; $g_total = 0;
								$qty_total = 0; 
								foreach ($pembelian_detail as $row) { ?>
									<tr>
										<td>
											<?=$idx;?> 
										</td>
										<td>
											<?=nl2br($row->keterangan_barang);?>
										</td>
										<td>
											<span class='qty'><?=str_replace('.00', '', $row->qty);?></span> 
										</td>
										<td>
											<span class='harga_beli'><?=number_format($row->harga_beli,'0','.','.');?></span> 
										</td>
										<td>
											<?$subtotal = $row->qty * $row->harga_beli;
											$g_total += $subtotal;
											$qty_total += $row->qty;
											?>
											<span <?=$readonly;?> class='subtotal'><?=number_format($subtotal,'0','.','.');?></span> 
										</td>
										<td>
											<span class='keterangan_barang' hidden><?=$row->keterangan_barang;?></span> 
											<span class='id' hidden><?=$row->id;?></span>
											<a href='#portlet-config-detail' data-toggle='modal' class="btn-xs btn green btn-detail-edit"><i class="fa fa-edit"></i> </a>
											<a class="btn-xs btn red  btn-detail-remove"><i class="fa fa-times"></i> </a>
											<!-- <a href='#portlet-config-edit' data-toggle='modal' class="btn-xs btn green btn-edit"><i class="fa fa-edit"></i> </a> -->
										</td>
									</tr>
								<? $idx++;} ?>
								<tr class='subtotal-data'>
									<td colspan='2' class='text-right'><b>TOTAL</b></td>
									<td class='text-left'><b><?=str_replace('.00', '',$qty_total);?></b></td>
									<td class='text-right'><b>TOTAL</b></td>
									<td><b class='total'><?=number_format($g_total,'0',',','.');?> </b> </td>
									<td class='hidden-print'></td>
								</tr>
							</tbody>
						</table>

						<table style='width:100%'>
							<tr>
								<td>
									<table>
										<tr>
											<td>Subtotal</td>
											<td class='padding-rl-5'> : </td>
								    		<td class='td-isi-bold'><span class='total'><?=number_format($g_total,'0',',','.');?></span> </td>
										</tr>
										<tr>
											<td>Jatuh Tempo</td>
											<td class='padding-rl-5'> : </td>
								    		<td class='td-isi-bold'>
								    			
								    			<?$style='';
								    			$diff = strtotime($ori_jatuh_tempo) - strtotime($ori_tanggal);
								    			$diff = $diff/(60*60*24);
								    			// echo $diff;
								    			if ($diff < 0) {
								    				$style = 'color:red';
								    			}?>
								    			<input name='jatuh_tempo'  <?=$readonly;?> <?if ($pembelian_id =='') {?>readonly<?}?> class="<?if ($pembelian_id !='' ) {?>date-picker<?}?> padding-rl-5 jatuh_tempo" style='<?=$style;?>' value='<?=$jatuh_tempo;?>'></td>
										</tr>
										<tr>
											<td>Keterangan</td>
											<td class='padding-rl-5'> : </td>
								    		<td class='td-isi-bold'><input <?=$readonly;?> <?if ($pembelian_id =='') {?>readonly<?}?> class='padding-rl-5 keterangan' name='keterangan' value="<?=$keterangan;?>"></td>
										</tr>
									</table>
								</td>
								<td style='vertical-align:top;font-size:4em;' class='text-right'>
									<b>Rp <span class='g_total' style=''><?=number_format($g_total - $diskon,'0',',','.');?></span></b>
								</td>
							</tr>

						</table>
						<hr/>
						<div>
			                <a class='btn btn-lg blue hidden-print'><i class='fa fa-print'></i> Print </a>

						</div>
					</div>
					
				</div>
			</div>
		</div>
	</div>			
</div>

<script src="<?php echo base_url('assets/global/plugins/bootbox/bootbox.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/bootstrap-modal/js/bootstrap-modalmanager.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/bootstrap-modal/js/bootstrap-modal.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/jquery-validation/js/jquery.validate.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js'); ?>" type="text/javascript"></script>

<script src="<?php echo base_url('assets/global/plugins/select2/select2.min.js'); ?>" type="text/javascript" ></script>
<script>
jQuery(document).ready(function() {

	$(".btn-edit-save").click(function(){
		if($("#form_edit_data [name=tanggal]").val() != ''){
			$("#form_edit_data").submit();
		}
	});

	$(".btn-save").click(function(){
		if($("#form_add_data [name=tanggal]").val() != ''){
			$("#form_add_data").submit();
		}
	});	

	$(".btn-save-brg").click(function(){
		$("#form_add_barang").submit();
		btn_disabled_load($(this));
	});


	$("#general_table").on('click', '.btn-detail-edit', function(){
		var ini = $(this).closest('tr');
		var form = $("#form_add_barang");
		form.find("[name=keterangan_barang]").val(ini.find('.keterangan_barang').html());
		form.find("[name=qty]").val(ini.find('.qty').html());
		form.find("[name=harga_beli]").val(ini.find('.harga_beli').html());
		form.find("[name=pembelian_detail_id]").val(ini.find('.id').html());
	});
	
	$("#general_table").on('click', '.btn-detail-remove', function(){
		var ini = $(this).closest('tr');
		var id = ini.find('.id').html();
		bootbox.confirm("Yakin menghapus item ini ? ", function(respond){
			if (respond) {
				window.location.replace(baseurl+"transaction/pembelian_lain_detail_remove?id="+id+"&pembelian_id="+"<?=$pembelian_id?>");
			};
		});
	});

	//===================================jatuh tempo========================================

	$(document).on('change','.jatuh_tempo', function(){
    	var ini = $(this).closest('tr');
    	var data = {};
    	data['ori_tanggal'] = "<?=$ori_tanggal;?>";
    	data['pembelian_id'] =  "<?=$pembelian_id;?>";
    	data['jatuh_tempo'] = $(this).val();
    	var url = 'transaction/pembelian_lain_jatuh_tempo_update';
    	// update_table(ini);
    	ajax_data_sync(url,data).done(function(data_respond  /*,textStatus, jqXHR*/ ){
			if (data_respond == 'OK') {
				$('.jatuh_tempo').css('color','black');
			}else{
				$('.jatuh_tempo').css('color','red');
				notific8("ruby","oops");
			}
   		});
    });

});


</script>
