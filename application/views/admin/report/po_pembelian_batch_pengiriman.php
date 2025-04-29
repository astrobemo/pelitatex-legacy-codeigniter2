<link href="<?=base_url('assets/global/plugins/bootstrap-modal/css/bootstrap-modal.css');?>" rel="stylesheet" type="text/css"/>
<?=link_tag('assets/global/plugins/bootstrap-datepicker/css/datepicker3.css'); ?>
<?php echo link_tag('assets_noondev/css/progress-bubble.css'); ?>

<style type="text/css">
.input-ockh{
	border: none;
	border-bottom:2px solid #ddd;
	width: 100px;
	background: none;
}

.percent-bar{
	height: 50px;
	text-align: center;
	color: #fff;
	vertical-align: middle;
	display: inline-block;
	font-size: 16px;
	overflow: hidden;
}

.percent-bar span{
	position: relative;
	top: 6px;
}

.percent-bar small{
	font-size: 12px;
}

</style>

<div class="page-content">
	<div class='container'>

		<?
			$po_pembelian_id = '';
			$supplier_id = '';
			$nama_supplier = '';
			$po_number = '';
			$sales_contract = '';
			$tanggal = '';
			$ori_tanggal = '';
			$toko_id = '';
			$nama_toko = '';
			
			$po_status = 0;
			$status_aktif = 0;
			$catatan = '';

			foreach ($po_pembelian_data as $row) {
				$po_pembelian_id = $row->id;
				$supplier_id = $row->supplier_id;
				$nama_supplier = $row->nama_supplier;
				$po_number = $row->po_number;
				$sales_contract = $row->sales_contract;
				$tanggal = is_reverse_date($row->tanggal);
				$ori_tanggal = $row->tanggal;
				$toko_id = $row->toko_id;
				$nama_toko = $row->nama_toko;
				
				$po_status = $row->po_status;
				$status_aktif = $row->status_aktif;
				$catatan = $row->catatan;
			}

			foreach ($po_pembelian_data_detail as $row) {
				$nama_satuan = $row->nama_satuan;
				$po_pembelian_detail_id = $row->id;
				$barang_id_detail = $row->barang_id;
				$qty_order = $row->qty;
				$qty_warna_total = $row->qty_warna_total;
			}

			$qty_datang_total = 0;
			foreach ($po_pembelian_data_warna as $row) {
				$qty_datang_total += $row->qty_datang;
			}

			$readonly = ''; $disabled = '';
			if (is_posisi_id() == 6) {
				$readonly = 'readonly';
				$disabled = 'disabled';
			}
		?>

		<div class="modal fade" id="portlet-config-detail" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body">
						<form action="<?=base_url('transaction/po_pembelian_list_detail_warna_insert')?>" class="form-horizontal" id="form_add_barang" method="post">
							<h3 class='block'> Tambah Warna</h3>
							
							<div class="form-group">
			                    <label class="control-label col-md-3">Barang<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<input name='po_pembelian_detail_id' value='<?=$po_pembelian_detail_id;?>' hidden>
			                    	<input name='po_pembelian_id' value='<?=$po_pembelian_id;?>' hidden>
			                    	<select disabled name="barang_id" id='barang_id_select'>
		                				<option value=''>Pilih</option>
		                				<?foreach ($this->barang_list_aktif as $row) { ?>
			                    			<option <?=($barang_id_detail == $row->id ? 'selected' : '' )?> value="<?=$row->id?>"><?=$row->nama;?></option>
			                    		<? } ?>
			                    	</select>
			                    </div>
			                </div>

			                <div class="form-group">
			                    <label class="control-label col-md-3">Warna<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
					                <select name="warna_id" id='warna_id_select' class='form-control'>
		                				<option value=''>Pilih</option>
		                				<?foreach ($this->warna_list_aktif as $row) { ?>
			                    			<option value="<?=$row->id?>"><?=$row->warna_beli;?></option>
			                    		<? } ?>
			                    	</select>
			                    </div>
			                </div> 			                

			                <div class="form-group">
			                    <label class="control-label col-md-3">Qty <span class='satuan_unit'></span> <span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                		<input type="text" class="form-control amount_number" name="qty"/>
			                    </div>
			                </div>

			                <div class="form-group">
			                    <label class="control-label col-md-3">OCKH
			                    </label>
			                    <div class="col-md-6">
			                		<input type="text" class='form-control' name="OCKH"/>
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
				<div class="portlet dark">
					<div class="portlet-title">
						<div class="caption caption-md">
							<i class="icon-bar-chart theme-font hide"></i>
							<span class="caption-subject theme-font bold uppercase"><?=$breadcrumb_small;?></span>
						</div>
					</div>
					<div class="portlet-body">
						<table width='100%'>
							<tr>
								<td>
									<table>
								    	<?foreach ($po_pembelian_data_detail as $row) {?>
									    	<tr>
									    		<td>Barang</td>
									    		<td class='padding-rl-5'> : </td>
									    		<td  class='td-isi-bold'>
									    			<?=$row->nama_barang;?></td>
									    	</tr>
									    	<tr>
									    		<td>Harga</td>
									    		<td class='padding-rl-5'> : </td>
									    		<td class='td-isi-bold'>
									    			<?=number_format($row->harga,'0',',','.');?>
									    		</td>
									    	</tr>
									    	<tr>
									    		<td>Qty</td>
									    		<td class='padding-rl-5'> : </td>
									    		<td class='td-isi-bold'>
									    			<?=number_format($row->qty,'0',',','.');?>
									    		</td>
									    	</tr>
								    	<?}?>
								    </table>
								</td>
								<td>
									<table>
								    	<tr>
								    		<td>Sales Contract</td>
								    		<td class='padding-rl-5'> : </td>
								    		<td  class='td-isi-bold'>
								    			<?=$sales_contract;?></td>
								    	</tr>
								    	<tr>
								    		<td>PO Number</td>
								    		<td class='padding-rl-5'> : </td>
								    		<td  class='td-isi-bold'>
								    			<?=$po_number;?></td>
								    	</tr>
								    	<tr>
								    		<td>Tanggal</td>
								    		<td class='padding-rl-5'> : </td>
								    		<td class='td-isi-bold'><?=$tanggal;?></td>
								    	</tr>
								    	<tr>
								    		<td>Toko</td>
								    		<td class='padding-rl-5'> : </td>
								    		<td class='td-isi-bold'>
								    			<?=$nama_toko;?>
								    		</td>
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
							<?
								$type = 'progress-bar-success';
								$width = number_format($qty_warna_total/$qty_order * 100,'2','.','');
								$progress = $width;
								if ($progress == 0) {
									$type = 'progress-bar-danger';
									$width = 100;
									$progress = 0;
								}

								$sisa = 100 - $progress;

								$persen_datang_width = number_format($qty_datang_total/$qty_order * 100,'2','.','');
								$persen_order_width = $width - $persen_datang_width;
								$persen_total = 100 - $persen_datang_width - $persen_order_width;
							?>
							<!-- <div class="progress" style='background:#ccc; height:50px; '>
							  	<div class="progress-bar <?=$type;?> progress-bar-striped" role="progressbar" aria-valuenow="<?=$width?>" aria-valuemin="0" aria-valuemax="100" style="width:<?=$width;?>%;; padding-top:10px;font-size:1.1em; border-radius:5px;">
							    	<?=($progress == '' ? "0% (No Data)" : $progress.'%<br/>('.number_format($qty_warna_total,'0',',','.')." ".$nama_satuan.')');?>
							  	</div>
							  	<div style='padding:10px; font-size:1.1em'>
							  		<span style='padding:0 15px;'>
								  		<?=(100 - $width).'%';?> sisa <br/>
							  		</span>
							  		<span style='padding:0 15px;'>
								  		( <?=number_format($qty_order - $qty_warna_total,'0',',','.');?> <?=$nama_satuan?> )
							  		</span>
							  	</div>
							</div> -->
							<div style='background:#999; height:50px; border-radius:5px;font-size:0'>
								<div class='percent-bar' style="width:<?=$persen_datang_width?>%; background:#5cb85c"><span><?=number_format($qty_datang_total,'0',',','.');?><br/><small>(<?=$persen_datang_width?>%)</small></span></div>
								<div class='percent-bar' style="width:<?=$persen_order_width;?>%;background:#45B6AF"><span><?=number_format($qty_warna_total,'0',',','.');?><br/><small>(<?=$persen_order_width;?>%)</small> </span></div>
								<div class='percent-bar' style="width:<?=$persen_total;?>%"><span><?=number_format($qty_order,'0',',','.');?><br><small>(<?=$persen_total?>%)</small> </span></div>
							</div>
							<div>
								Keterangan : 
								<ul>
									<li><span style='width:25px; height:10px;background:#5cb85c; display:inline-block'></span> Jumlah Barang PO yang <b>Sudah Datang</b></li>
									<li><span style='width:25px; height:10px;background:#45B6AF; display:inline-block'></span> Jumlah Barang PO yang <b>Sudah Dipesan</b></li>
									<li><span style='width:25px; height:10px;background:#999; display:inline-block'></span> Jumlah Barang PO yang <b>Belum Dipesan</b></li>
								</ul>
							</div>
						<hr/>

						<!-- table-striped table-bordered  -->
						<div class='col-xs-12'>
							<?if (count($po_pembelian_data_warna) == 0) {?>
								Belum ada daftar warna
							<?}?>
							<?foreach ($po_pembelian_data_warna as $row) {
								$qty_warna_total = 0;
								$qty_total_datang = $row->qty_datang;
								$qty_datang = number_format($row->qty_datang/$row->qty_warna * 100,'2');
								$top = 100 - $qty_datang;

								if ($qty_datang < 20) {
									$class = 'red';
								}elseif ($qty_datang < 50) {
									$class = 'orange';
								}else {
									$class = 'green';
								}
								?>
								<div class='col-xs-12 bubble-container warna<?=strtolower($row->nama_warna)?>'>
									<div class='col-xs-12 col-md-4'>
										<h3>INFO</h3>
										<hr style='margin:0px; border-color:#ccc'/>
										<table>
											<tr>
												<td style='width:120px'>
													<div class='<?=$class?>'>
														<div class="<?=$class;?> progress">
													      	<div class="inner">
													        	<div class="percent">
													        		<span><?=str_replace('.00', '', $qty_datang)?></span>%
													        	</div>
													        	<div class="water" style='top:<?=$top;?>%'></div>
													        	<div class="glare"></div>
													      	</div>
													    </div>
														
													</div>
												</td>
												<td style='vertical-align:top; padding:20px 0 0 20px'>
													<div class='text-left'>
														<table>
															<tr>
																<td>Order</td>
																<td class='padding-rl-5'>:</td>
																<td><b><?=number_format($row->qty_warna,'0',',','.');?></b></td>
															</tr>
															<tr>
																<td>Warna</td>
																<td class='padding-rl-5'>:</td>
																<td><b><?=$row->nama_warna;?></b></td>
															</tr>
															<tr>
																<td>PO-BATCH</td>
																<td class='padding-rl-5'> : </td>
																<td>
																	<b><a target='_blank' href="<?=base_url().is_setting_link('transaction/po_pembelian_detail_warna_batch');?>?id=<?=$po_pembelian_id;?>&batch_id=<?=$row->po_pembelian_batch_id?>"><?=$po_number?>-<?=$row->batch?></a></b>
																</td>
															</tr>
															<tr>
																<td>Tgl</td>
																<td class='padding-rl-5'> : </td>
																<td>
																	<b><?=is_reverse_date($row->tanggal);?></b><br/>
																</td>
															</tr>
															<tr>
																<td>OCKH</td>
																<td class='padding-rl-5'> : </td>
																<td>
																	<input name='OCKH' class='input-ockh' value='<?=$row->OCKH;?>'>
																	<span hidden class='id'><?=$row->id?></span>
																</td>
															</tr>
														</table>
													</div>
												</td>
											</tr>
										</table>
									</div>
									<div class='col-xs-12 col-md-4'>
										<h3>PENGIRIMAN</h3>
										<hr style='margin:0px; border-color:#ccc'/>
										<table>
											<tr>
												<td></td>
											</tr>
										</table>
									</div>
								</div>

							<?} ?>
						</div>
					</div>
					<div class='col-xs-12'>
						<a href="javascript:window.open('','_self').close();" class="btn btn-lg default button-previous hidden-print">Close</a>

					</div>	
					
				</div>
			</div>
		</div>
	</div>			
</div>

<script src="<?php echo base_url('assets/global/plugins/bootbox/bootbox.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/bootstrap-modal/js/bootstrap-modalmanager.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/bootstrap-modal/js/bootstrap-modal.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js'); ?>" type="text/javascript"></script>


<script>
jQuery(document).ready(function() {

	
	$('#barang_id_select, #warna_id_select').select2({
        placeholder: "Pilih...",
        allowClear: true
    });


    <?if ($po_pembelian_id != '' && is_posisi_id() != 6) { ?>
    	var map = {220: false};
		$(document).keydown(function(e) {
		    if (e.keyCode in map) {
		        map[e.keyCode] = true;
		        if (map[220]) {
		            $('#portlet-config-detail').modal('toggle');
		            setTimeout(function(){
		            	$('#barang_id_select').select2("open");
		            },600);
		        }
		    }
		}).keyup(function(e) {
		    if (e.keyCode in map) {
		        map[e.keyCode] = false;
		    }
		});
    <?}?>

    $('.btn-save-brg').click(function(){
    	if ($('#form_add_barang [name=barang_id]').val() != '') {
    		$('#form_add_barang').submit();
    	};
    });

    $('.btn-brg-add').click(function(){
    	// var select2 = $(this).data('select2');
    	setTimeout(function(){
    		$('#barang_id_select').select2("open");
    		// $('#form_add_barang .input1 .select2-choice').click();
    	},700);
    });


    <?if ($po_pembelian_id != '') { ?>
    	$(document).on('change','.catatan', function(){
	    	var ini = $(this).closest('tr');
	    	var data = {};
	    	data['column'] = $(this).attr('name');
	    	data['pembelian_id'] =  "<?=$po_pembelian_id;?>";
	    	data['value'] = $(this).val();
	    	var url = 'transaction/po_pembelian_data_update';
	    	// update_table(ini);
	    	ajax_data_sync(url,data).done(function(data_respond  /*,textStatus, jqXHR*/ ){
				if (data_respond == 'OK') {
					update_table(ini);
				};
	   		});
	    });

    <?}?>

    $('.btn-search-faktur').click(function(){
    	var id = $("#form_search_po [name=po_pembelian_id]").val();
    	var action = $("#form_search_po").attr('action');
    	if (id != '') {
    		window.location.replace(action+'/'+id);
    	};
    });

    $(document).on('input','.search-warna', function(){
    	var cari = $(this).val();
    	if (cari != '') {
    		$('.bubble-container').hide();
    		$("div[class*='warna"+cari+"']").show();
    	}else{
    		$('.bubble-container').show();
    	}
    });

//========================OCKH====================================

	$('#general_table').on('change','.input-ockh', function () {
		// alert('test');
		const ini = $(this).closest('div');
		var data = {};
    	data['id'] =  ini.find('.id').html();
    	data['OCKH'] = $(this).val();
    	var url = 'transaction/po_ockh_update';
    	// update_table(ini);
    	ajax_data_sync(url,data).done(function(data_respond  /*,textStatus, jqXHR*/ ){
			if (data_respond != 'OK') {
				alert("Error, mohon muat ulang halaman");
			}else{
				notific8('lime', 'saved');
			};
   		});
	});


});

function update_table(){
	subtotal = 0;
	$('.subtotal').each(function(){
		subtotal+= reset_number_format($(this).html());
	});

	$('.total').html(change_number_format(subtotal));
	var diskon = reset_number_format($('.diskon').val());
	var g_total = subtotal - parseInt(diskon);
	$('.g_total').html(change_number_format(g_total));

}
</script>
