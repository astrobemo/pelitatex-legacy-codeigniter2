<style type="text/css">
#general_table input{
	border: none;
}

#header-table{
	width: 100%;
}

#header-table tr td{
	vertical-align: top;
}

p.addressing{
	margin: 0;
	padding: 0;
	width: 100%;
	border-bottom: 2px dashed #ddd;
	padding-right:100px;
}

.sign_paper{
	width: 75%;
	margin:auto;
	margin-top: 40px;
	border-spacing: 25px;
}

.sign_paper tr td{
	text-align: center;
}

.subtotal-data{
	font-size: 1.2em;
}

.warning-text{
	width: 100%;
	border: 1px solid #000;
	text-align: center;
	font-size: 1.5em;
	font-style: italic;
}

</style>
<script src="<?php echo base_url('assets/global/plugins/bootbox/bootbox.min.js'); ?>" type="text/javascript"></script>

<div class="page-content">
	<div class='container'>
		
		<?
			$barang_keluar_id = '';
			$customer_id = '';
			$nama_customer = '';
			$sales_id = '';
			$nama_sales = '';
			$surat_jalan = '';

			$tanggal_order = '';
			$tanggal_order_ori = '';

			$tanggal = '';
			$tanggal_ori = '';

			$keterangan = '';
			$status_aktif = '';
			$g_total = 0;

			foreach ($customer_data as $row) {
				$nama_customer = $row->nama;
				$alamat = $row->alamat;
			}

			foreach ($barang_keluar_data as $row) {
				$barang_keluar_id = $row->id;
				$customer_id = $row->customer_id;
				$sales_id = $row->sales_id;
				$nama_sales = $row->nama_sales;
				$surat_jalan = $row->surat_jalan_lengkap;

				$tanggal_order = is_reverse_date($row->tanggal_order);
				$tanggal_order_ori = $row->tanggal_order;

				$tanggal = is_reverse_date($row->tanggal);
				$tanggal_ori = $row->tanggal;

				$keterangan = $row->keterangan;
				$status_aktif = $row->status_aktif;
			}

		?>

		<div class="modal fade" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body">
						<form action="<?=base_url().$common_data['controller_main'].'/barang_keluar_detail_insert';?>" class="form-horizontal" id="form_add_data" method='post'>
							<h3 class='block'> Tambah </h3>

							<div class="form-group">
			                    <label class="control-label col-md-4">Barang<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<input name='barang_keluar_id' hidden='hidden' value='<?=$barang_keluar_id;?>'>
	                    			<select class='form-control' name='barang_id' id='barang_id_select'>
	                    				<?foreach ($this->barang_list_aktif as $row) { ?>
	                    					<option value="<?=$row->id;?>"><?=$row->nama;?></option>
	                    				<? } ?>
	                    			</select>
			                    </div>
			                </div>

			                <div class="form-group">
			                    <label class="control-label col-md-4">Qty<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
	                    			<input class='form-control' name='qty'>
			                    </div>
			                </div>

			                <div class="form-group">
			                    <label class="control-label col-md-4">Harga<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
	                    			<input class='form-control' name='price'>
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

		<div class="modal fade" id="portlet-config-memo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body">
						<form action="<?=base_url().$common_data['controller_main'].'/barang_keluar_detail_gudang_insert';?>" class="form-horizontal" id="form_add_memo" method='post'>
							<h3 class='block'> Memo 
								<!-- <b><span class='nama_barang'></span></b> -->
							</h3>

							<div class="form-group">
			                    <label class="control-label col-md-4">Gudang<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<input name='barang_keluar_id' hidden='hidden' value='<?=$barang_keluar_id;?>'>			                    	
			                    	<select class='form-control' name='gudang_id' id="gudang_id_select" size='4'>
			                    		<?
			                    		$i = 0;
			                    		foreach ($this->gudang_list_aktif as $row) { ?>
			                    			<?if ($i == 1) {
			                    				$selected = 'selected';
			                    			}else{
			                    				$selected = '';
			                    			}?>
	                    					<option <?=$selected;?> value="<?=$row->id;?>"><?=$row->nama;?></option>
	                    				<? $i++;} ?>
	                    			</select>
			                    </div>
			                </div>

			                <div class="form-group">
			                    <label class="control-label col-md-4">Tanggal<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
	                    			<input class='form-control date-picker' name='tanggal' value='<?=$tanggal;?>'>
			                    </div>
			                </div>

			                <!-- <div class="form-group">
			                    <label class="control-label col-md-4">Qty<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
	                    			<input class='form-control' name='qty'>
			                    </div>
			                </div> -->

						</form>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn blue btn-memo-save">Save</button>
						<button type="button" class="btn default" data-dismiss="modal">Close</button>
					</div>
				</div>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
		</div>

		<div class="modal fade" id="portlet-config-print-memo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body">
						<h3>MEMO GUDANG <span class='nama_gudang'></span> </h3>
						BANDUNG, <span class='tanggal'></span> <br/>
						Kepada Yth, <br/>
						<br/>
						<br/>
						Dengan hormat,
						<br/>
						Mohon diberikan kepada pembawa surat ini, barang sbb :
						<table style='width:70%' id='table-memo'>
							<thead>
								<tr>
									<th>No</th>
									<th>Barang</th>
									<th>Qty</th>
									<th>Total</th>
								</tr>
							</thead>
							<tbody></tbody>
						</table>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn blue btn-memo-save">Save</button>
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
					<div class="portlet-body">
						<button class="btn btn-default "><i class='fa fa-eye'></i> Nota Gudang</button>
						<a href='#portlet-config-memo' data-toggle='modal' class='btn btn-xs blue btn-add-memo'><i class='fa fa-plus'></i> Memo</a>
						<hr>
						<table>
							<?foreach ($memo_list as $row) { ?>
								<tr style='vertical-align:top'>
									<td>
										<?=$row->nama_gudang;?>
									</td>
									<td>
										<?=str_replace(',', '<br/>', $row->nama_barang);?>
									</td>
									<td>
										<?=str_replace(',', '<br/>', $row->nama_packaging);?>
									</td>
									<td>
										<?=str_replace(',', '<br/>', $row->qty_need);?>
									</td>
								</tr>
							<?}?>
						</table>
					</div>
				</div>
			</div>
		</div>

		<div class="row margin-top-10">
			<div class="col-md-12">
				<div class="portlet light">
					<div class="portlet-body">
						<table id="header-table">
							<tr>
								<td>
									<b>SURAT JALAN</b><br/>
									<b style="font-size:4em;color:red; font-family:'arial black'">FA</b><br/>
									<b>CHEMICAL SUPPLIER</b>
									<br/>
								</td>
								<td class='text-right' style=''>
									<b>Bandung, <?=date('d F Y', strtotime($tanggal_ori));?></b>
									<br/><br/>
									<div class='text-left' style='float:right;'>
										Kepada Yth, <br/>
										<?foreach ($customer_data as $row) { ?>
											<p class='addressing'><?=$row->nama;?></p>
											<p class='addressing'><?=$row->alamat;?></p><br/>
											<p class='addressing'><?=$row->telepon1.' '.$row->telepon2;?> </p><br/>										
										<?}?>

									</div>
								</td>
							</tr>
						</table>

						<b style='font-size:2em'>No. : <?=$surat_jalan;?></b>
						<table class="table table-striped table-bordered table-hover" id="general_table">
							<thead>
								<tr>
									<th scope="col">
										Barang 
										<a href="#portlet-config" class='btn btn-xs blue btn-add hidden-print' data-toggle='modal'><i class='fa fa-plus'></i></a>
									</th>
									<th scope="col">
										Qty
									</th>
									<th scope="col">
										Price
									</th>
									<th scope="col">
										Jumlah
									</th>
									<th scope="col" class='hidden-print'>
										Gudang
									</th>
									<th scope="col" class='hidden-print'>
										Action
									</th>
								</tr>
							</thead>
							<tbody>
								<?foreach ($barang_list as $row) { ?>
									<tr>
										<td>
											<span class='nama_barang'><?=$row->nama_barang;?></span>  
										</td>
										<td>
											<input name='qty' class='qty' value="<?=$row->qty;?>" style='width:70px;padding:0 5px;text-align:right' placeholder='qty'> <?=$row->nama_packaging;?>
											<br/>
											( <?="@ ";?><span class='pengali'><?=$row->pengali;?></span> <?=$row->nama_satuan;?>  = <span class='subtotal-qty'><?=$row->qty*$row->pengali;?></span> <?=$row->nama_satuan;?> )
											<!-- <input name='qty_notes' value="<?=$row->qty_notes;?>" placeholder="qty notes">  -->
										</td>
										<td>
											<input name='price' class='amount_number price' value="<?=number_format($row->price,'0',',','.');?>" style='width:100px;padding:0 5px;text-align:left' placeholder='harga'>
										</td>
										<td>
											<span class='subtotal'><?=number_format($row->qty*$row->price, '0',',','.');?></span>
											<?$g_total += $row->qty*$row->price;?>
										</td>
										<td class='hidden-print'>
											<table>
												<?
												if ($row->nama_gudang != '') {
													$qty_out = explode(',', $row->qty_out);
													$nama_gudang = explode(',', $row->nama_gudang);
													foreach ($nama_gudang as $key => $value) { ?>
														<tr>
															<td style="padding:0 10px 0 0;"><span class='nama_gudang'><?=$value;?></span> </td>
															<td> : <?=$qty_out[$key];?></td>
															<!-- <td><i href='#portlet-config-print-memo' data-toggle='modal' class='fa fa-print btn btn-xs blue print_memo'></i></td> -->
														</tr>
													<?}
												}
												?>
											</table>
										</td>
										<td class='hidden-print'>
											<span class='id' hidden='hidden'><?=$row->id;?></span>
											<a class='btn btn-xs red btn-remove'><i class='fa fa-times'></i></a>
										</td>
									</tr>
								<? } ?>

								<tr class='subtotal-data'>
									<td colspan='3' class='text-right'><b>TOTAL</b></td>
									<td>
										<b><span class='g_total'><?=number_format($g_total,'0',',','.');?></span></b>
									</td>
									<td  class='hidden-print' colspan='2'></td>
								</tr>

							</tbody>
						</table>

						<span style='font-size:1.1em'>*Harga sewaktu-waktu dapat berubah tanpa ada pemberitahuan terlebih dahulu</span>
						<table style='font-size:1.1em; margin:auto'>
							<tr>
								<td style='vertical-align:top; padding-right:15px'>Ke.</td>
								<td class='padding-rl-5' style='vertical-align:top;'> : </td>
								<td>
									<i class='fa fa-square-o' style='margin-right:5px'></i> Jerigen / Drum kembali ....pcs <br>
									<i class='fa fa-square-o' style='margin-right:5px'></i> Jerigen / Drum dipinjam ....pcs
								</td>
							</tr>
						</table>
						<br/>

						<div class='warning-text'>
							<b>Barang telah diterima dalam keadaan baik. Barang yang sudah diterima tidak dapat dikembalikan.</b>
						</div>

						<table class='sign_paper'>
							<tr>
								<td style='height:100px;vertical-align:top;width:20%'>Yang Menyerahkan, </td>
								<td style='height:100px;vertical-align:top;width:5%'></td>
								<td style='height:100px;vertical-align:top;width:20%'>Kepala Gudang, </td>
								<td style='height:100px;vertical-align:top;width:5%'></td>
								<td style='height:100px;vertical-align:top;width:20%'>Dikirim Oleh, </td>
								<td style='height:100px;vertical-align:top;width:5%'></td>
								<td style='height:100px;vertical-align:top;width:20%'>Hormat Kami, </td>
							</tr>
							<tr>
								<td style='border-top:2px solid black'></td>
								<td></td>
								<td style='border-top:2px solid black'></td>
								<td></td>
								<td style='border-top:2px solid black'></td>
								<td></td>
								<td style='border-top:2px solid black'></td>
							</tr>
						</table>
						<hr/>
						<div>
		                	<a class="btn btn-lg default hidden-print" onclick="javascript:window.open('','_self').close();"> Close</a>
		                	<a class="btn btn-lg blue hidden-print" onclick='window.print()'><i class='fa fa-print'></i> Print</a>
		                </div>
					</div>
				</div>
			</div>
		</div>
	</div>			
</div>

<?php echo link_tag('assets/global/plugins/bootstrap-datepicker/css/datepicker3.css'); ?>

<script src="<?php echo base_url('assets/global/plugins/bootstrap-select/bootstrap-select.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/select2/select2.min.js'); ?>" type="text/javascript" ></script>

<script>
jQuery(document).ready(function() {

	// TableAdvanced.init();
	$('#barang_id_select').select2({
        placeholder: "Select...",
        allowClear: true
    });

	$('#form_add [name=pembelian_id]').change(function(){
		if ($(this).val() != '') {
			var type = $(this).val().split('??');
			// alert(type);
			$("#form_add [name=po_type]").val(type[1]);
			// $("#form_add [name=po_type][value='"+type+"']").change();
		};
	});

	$('.btn-save').click(function(){
		if ($('#form_add_data [name=barang_id]').val() == '' || $('#form_add_data [name=gudang_id]').val() == '' || $('#form_add_data [name=qty]').val() == '') {
			bootbox.alert('Nama Barang, Gudang dan Qty harus diisi.');
		}else{
			$('#form_add_data').submit();
		}
	});

	$('#general_table').on('click','.btn-remove',function(){
		var ini = $(this).closest('tr');
		var data={};
		data['id'] = ini.find('.id').html();
		var url = "stock/barang_keluar_detail_remove";
		ajax_data_sync(url,data).done(function(data_respond /* ,textStatus, jqXHR */){
   			if (data_respond == 'OK') {
   				ini.remove();
   			};
   		});
	});

	$('#general_table').on('change', '.price, .qty', function(){
		var ini = $(this).closest('tr');
		var data = {};
		data['id'] = '<?=$barang_keluar_id;?>';
		data['column'] = $(this).attr('name');
		data['value'] = $(this).val();
		if(data['column'] == 'qty'){
			var pengali = ini.find('.pengali').html();
			var qty_pack = pengali * data['value']; 
			// alert(pengali +'*'+qty_pack);
			$('.subtotal-qty').html(qty_pack);
		}
		var url = "stock/barang_keluar_detail_qty_update";
		ajax_data_sync(url,data).done(function(data_respond  /*,textStatus, jqXHR*/ ){
			if (data_respond == 'OK') {
				// alert('test');
				update_table();
			};
   		});
	});

//====================================tambah memo==============================

	$('.btn-memo-save').click(function(){
		if($('#form_add_memo [name=tanggal]').val() != '' ){
			$('#form_add_memo').submit();
		}else{
			bootbox.confirm("Tanggal harus diisi");
		}
	});

//====================================tambah memo==============================

	$('.print_memo').click(function(){
		var nama = $(this).closest('tr').find('.nama_barang').html();
	});

});

function update_table(){
	var total = 0;
	$('#general_table .qty').each(function(){
		var qty = $(this).val();
		var harga = $(this).closest('tr').find('.price').val();
		harga = reset_number_format(harga);
		var subtotal = qty * harga;
		total += subtotal;
		$(this).closest('tr').find('.subtotal').html(change_number_format(subtotal));
	});

	// alert(total);

	$('.total').html(change_number_format(total));
}
</script>
