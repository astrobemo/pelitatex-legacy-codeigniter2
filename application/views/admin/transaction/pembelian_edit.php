<style type="text/css">
	.ppn{
		color: blue;
		font-weight: bold;
	}

	.no-ppn{
		color: red;
		font-weight: bold;
	}

	.po_header{
		font-size: 2em;
		color: red;
		font-weight: bold;
	}

	.portlet > .portlet-title > .caption{
		float: none;;
	}

	.po-title{
		font-size: 1.5em;
		font-weight: bold;
		text-align: center;
		width: 100%;
		margin-bottom: 40px;
	}

	.po_data_company{
		text-decoration: underline;
	}

	.po_data_company nama{
		font-weight: bold;
		font-size: 1.5em;
	}

	.po_data_company alamat{
		font-weight: bold;
		text-transform: uppercase;
	}

	.po_data_to{
		font-size: 1.1em;
		font-weight: bold;
		padding-left: 5px;
		position: relative;
		text-align: left;
		float: right;
		right: 10%;
		top:-20px;
		bottom: -20px;
	}

	.up_person{
		border: none;
		width: 150px;
		padding-left: 5px;
		text-transform: uppercase;
		font-weight: bold;
	}

	.po_data_mandatory{
		margin-top: 50px;
		margin-bottom: 10px;
		font-size: 1.1em;
	}

	.po_data_mandatory tr td{
		padding: 3px 0;
	}

	#table_detail{
		width: 100%;
	}

	#table_detail tr td, #table_detail tr th{
		border: 1px solid #131;
		/*padding: 5px 5px 5px 10px; */
	}

	#table_detail tr th{
		text-align: center;
	}

	#table_detail input{
		background: none;
		width:auto;
		border: none;
		padding:0 0 0 5px;
		text-align: right;
	}

	#table_detail input:hover{
		cursor: pointer;
	}

	.kurs{
		cursor: pointer;
	}

	.rekap_section{
		font-size: 1.1em;
		font-weight: bold;
	}

	/*.select2-container .select2-choice{
		background-image: none;
	}*/


</style>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css'); ?>"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css'); ?>"/>
<script src="<?php echo base_url('assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js'); ?>" type="text/javascript"></script>

<?
//================================initialize data=================================
	$up_person = '';
	$nama_supplier = '';
	$romani = array(
		'1' => 'I' ,
		'2' => 'II' ,
		'3' => 'III' ,
		'4' => 'IV' ,
		'5' => 'V' ,
		'6' => 'VI' ,
		'7' => 'VII' ,
		'8' => 'VIII' ,
		'9' => 'IX' ,
		'10' => 'X' ,
		'11' => 'XI' ,
		'12' => 'XII' ,
		 );
	foreach ($pembelian as $row) {
		$pembelian_id = $row->id;
		$nama_supplier = $row->nama_supplier;
		$invoice_number = $row->invoice_number;
		$po_number = $row->po_number;
		$tanggal = is_reverse_date($row->tanggal);
		$tahun = substr($row->tanggal_po, 0,4);
		$bulan = substr($row->tanggal_po, 5,2);
		if (substr($bulan, 0,1) == '0') {
			$bulan = substr($bulan, 1,1);
		}

		if ($po_type != 'noppn') {
			$ppn_status = $row->ppn_status;
			if ($ppn_status == 1) {
				$btn = "<i class='fa fa-eye-slash'></i>";
			}else{
				$btn = "<i class='fa fa-eye'></i>";
			}
		}
			
		$kurs = $row->kurs;
		if ($kurs == '' || $kurs == 0) {
			$kurs = 1;
		}
		$currency_type_id = $row->currency_type_id;
		// $remarks = $row->remarks;
	}

	$sisipan = '';
	if ($po_type == 'noppn') {
		$sisipan = 'NP/';
	}elseif ($po_type == 'import') {
		$sisipan = 'I/';
	}
?>

<div class="page-content">
	<div class='container'>
		
		<?
            $romani = ['I','II','III','IV','V','VI','VII','VIII','IX','X','XI','XII'];
        ?>
		
		<div class="modal fade" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body">
						<form action="<?=$common_data['controller_main'].'/pembelian_detail_insert';?>" class="form-horizontal" id="form_add_data" method="post">
							<h3 class='block'> Tambah </h3>
			                <div class="form-group">
			                    <label class="control-label col-md-3">Barang<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<input hidden='hidden' name='po_pembelian_id' value='<?=$po_pembelian_id;?>'>
			                    	<input hidden='hidden' name='po_type' value='<?=$po_type;?>'>
		                    		<select class='form-control input1' name="barang_id" id='barang_id_select' style='background-image:none'>
		                				<option value="">Pilihan..</option>
			                    		<?foreach ($this->barang_list_aktif as $row) { ?>
			                    			<option value="<?=$row->id?>"><?=$row->nama;?></option>
			                    		<? } ?>
			                    	</select>
			                    </div>
			                </div>

			                <div class="form-group">
			                    <label class="control-label col-md-3">Qty<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<div class='input-group'>
			                    		<input name="qty" class='form-control' /> 
			                    			<span class="input-group-addon">
											- </span>
										<select name='satuan_id' class='form-control'>
											<?foreach ($this->satuan_list_aktif as $row) { ?>
												<option value='<?=$row->id;?>'><?=$row->nama;?></option>
											<? } ?>
										</select>
			                    	</div>
			                    </div>
			                </div>

			                <div class="form-group">
			                    <label class="control-label col-md-3">Qty Notes<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<div class='input-group'>
			                    		<input name="qty_notes" class='form-control' /> 
			                    	</div>
			                    </div>
			                </div>

			                <div class="form-group">
			                    <label class="control-label col-md-3">Unit Price<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
	                    			<input class='form-control' name='price'/>
			                    </div>
			                </div>

			                <div class="form-group">
			                    <label class="control-label col-md-3">Keterangan<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
	                    			<input class='form-control' name='keterangan'/>
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

		<div class="modal fade" id="portlet-config-bonus" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body">
						<form action="<?=$common_data['controller_main'].'/pembelian_detail_bonus_insert';?>" class="form-horizontal" id="form_bonus" method="post">
							<h3 class='block' style='color:blue'><b><i class='fa fa-plus'></i> BONUS</b> </h3>
			                <div class="form-group">
			                    <label class="control-label col-md-3">Barang<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<input name='pembelian_id' value='<?=$pembelian_id;?>'>
			                    	<input hidden='hidden' name='po_type' value='<?=$po_type;?>'>
			                    	<input hidden='hidden' name='pembelian_detail_id'>
			                    	
		                    		<input hidden name="barang_id">
		                    		<input readonly class='form-control' name="nama_barang">
			                    </div>
			                </div>

			                <div class="form-group">
			                    <label class="control-label col-md-3">Qty<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<div class='input-group'>
			                    		<input name="qty" class='form-control input1' /> 
			                    			<span class="input-group-addon">
											- </span>
										<select name='satuan_id' class='form-control'>
											<?foreach ($this->satuan_list_aktif as $row) { ?>
												<option value='<?=$row->id;?>'><?=$row->nama;?></option>
											<? } ?>
										</select>
			                    	</div>
			                    </div>
			                </div>

			                <div class="form-group">
			                    <label class="control-label col-md-3">Qty Notes<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<div class='input-group'>
			                    		<input name="qty_notes" class='form-control' /> 
			                    	</div>
			                    </div>
			                </div>

			                <div class="form-group">
			                    <label class="control-label col-md-3">Unit Price<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
	                    			<input readonly class='form-control' name='price' value="0"/>
			                    </div>
			                </div>

			                <div class="form-group">
			                    <label class="control-label col-md-3">Keterangan<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
	                    			<input class='form-control' name='keterangan'/>
			                    </div>
			                </div>
						</form>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn blue btn-bonus-save">Save</button>
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
						<h2 style='text-transform:uppercase'><b><?=$nama_supplier;?></b>
						</h2>
						<h4>
							INVOICE NUMBER : <?=$invoice_number;?><br/>
							PO NUMBER : <?=$po_number.'/'.$romani[$bulan-1].'/'.$sisipan.$tahun;?><br>
						</h4>
					</div>
					<div class="portlet-body">
						<table class='table table-hover' id="table_detail">
							<thead>
								<tr>
									<th>No.</th>
									<th>Descripton of Good <a href="#portlet-config" data-toggle='modal' class='hidden-print btn btn-xs yellow-gold'><i class='fa fa-plus btn-print'></i></a> </th>
									<th>Qty</th>
									<th>Unit Price</th>
									<?if ($currency_type_id == 2) { ?>
										<th class='kurs_setting'>KURS <i class='fa fa-gear'></i> </th>
									<? }?>
									<th style='width:150px;'>Amount</th>
									<th style='width:300px;'>Keterangan</th>
									<th style='width:30px;' class='hidden-print'></th>
								</tr>
							</thead>
							<tbody>
								<?
								$i = 1;
								$total = 0;
								$total_qty = 0;
								foreach ($pembelian_detail as $row) { ?>
									<tr>
										<td style='text-align:center'><?=$i;?></td>
										<td><span class='nama_barang'><?=$row->nama_barang;?></span> </td>
										<td style='text-align:right'>
											<?$total_qty+=$row->qty;?>
											<input style='text-align:right' name='qty' style='width:50px;' value='<?=$row->qty;?>'><br/>
											<input style='text-align:right' name='qty_notes' style='width:120px;' value='<?=$row->qty_notes;?>'>
											<!-- <span class='qty'><?=$row->qty;?></span> -->
											<!-- <span class='qty_notes'><?=$row->qty_notes;?></span> -->
										</td>
										<td style='text-align:right'>
											<input name='price' style='width:100px;' value='<?=$row->price;?>'>
											<!-- <span class='price'><?=$row->price?></span> -->
										</td>
										<?if ($currency_type_id == 2) { ?>
											<td style='text-align:right' class='kurs'><?=number_format($kurs,'2','.',',');?></td>
										<? }?>
										<td style='text-align:right'>
											<span class='subtotal'>
												<?if ($currency_type_id == 1) {
													echo number_format($row->qty * $row->price,'2','.',',');
													$total+=$row->qty * $row->price;
												}elseif ($currency_type_id == 2) {
													echo number_format($row->qty * $row->price * $kurs,'2','.',',');
													$total+=$row->qty * $row->price * $kurs;
												}
												?>
											</span>
										</td>
										<td>
											<span class='id' hidden='hidden'><?=$row->id;?></span>
											<span class='barang_id' hidden='hidden'><?=$row->barang_id;?></span>
										</td>
										<td class='hidden-print' style='text-align:center'>
											<i data-toggle="popover" style='cursor:pointer' class='fa fa-list' data-trigger='click' data-placement="left" data-html="true" data-content="<button class='btn btn-xs blue btn-bonus'><i class='fa fa-plus'></i> Bonus</button><br/><button class='btn btn-xs btn-remove'><i class='fa fa-times' style='color:red'></i> Remove</button>"></i>
										</td>
									</tr>
								<? $i++;
								} ?>

								<tr>
									<td colspan='7'></td>
								</tr>
								<?
								foreach ($pembelian_detail_bonus as $row) { ?>
									<tr>
										<td style='text-align:center'></td>
										<td>
											<b>BONUS : </b>
											<span class='nama_barang'><?=$row->nama_barang;?></span> 
										</td>
										<td style='text-align:right'>
											<?$total_qty+=$row->qty;?>
											<input style='text-align:right' name='qty_bonus' style='width:50px;' value='<?=$row->qty;?>'><br/>
											<input style='text-align:right' name='qty_notes_bonus' style='width:120px;' value='<?=$row->qty_notes;?>'>
											<!-- <span class='qty'><?=$row->qty;?></span> -->
											<!-- <span class='qty_notes'><?=$row->qty_notes;?></span> -->
										</td>
										<td style='text-align:right'>
											<?=$row->price;?>
											<!-- <span class='price'><?=$row->price?></span> -->
										</td>
										<?if ($currency_type_id == 2) { ?>
											<td style='text-align:right'>0</td>
										<? }?>
										<td style='text-align:right'>
											<span class='subtotal'>
												<?if ($currency_type_id == 1) {
													echo number_format($row->qty * $row->price,'2','.',',');
													$total+=$row->qty * $row->price;
												}elseif ($currency_type_id == 2) {
													echo number_format($row->qty * $row->price * $kurs,'2','.',',');
													$total+=$row->qty * $row->price * $kurs;
												}
												?>
											</span>
										</td>
										<td>
											<span class='id' hidden='hidden'><?=$row->id;?></span>
											<span class='barang_id' hidden='hidden'><?=$row->barang_id;?></span>
										</td>
										<td class='hidden-print' style='text-align:center'>
											<!-- <button class='btn btn-xs btn-remove'><i class='fa fa-times' style='color:red'></i></button> -->
											<!-- <a data-toggle="popover" class='btn btn-xs blue margin-bottom-5 btn-print' data-trigger='click' data-html="true" data-content="<button class='btn btn-xs blue hidden-print print_sampling btn-bonus'><i class='fa fa-plus'></i>Bonus</button><br/><button class='btn btn-xs btn-remove'><i class='fa fa-times' style='color:red'></i></button>"><i class='fa fa-list' ></i></a> -->

											<button class='btn btn-xs btn-remove-bonus'><i class='fa fa-times' style='color:red'></i></button>
										</td>
									</tr>
								<?
								}
								?>

								<?if ($po_type == 'ppn' && $ppn_status == 1) { ?>
									<tr class='rekap_section'>
										<td style='text-align:center;cursor:pointer'>
											
										</td>
										<td style='text-align:right'>Tax 10%</td>
										<td></td>
										<td></td>
										<?if ($currency_type_id == 2) { ?>
											<td></td>
										<? }?>
										<td style='text-align:right'>
											<span class='ppn_total'>
												<?if ($ppn_status == 1) { 
													echo number_format($total*0.1,'2','.',','); $total *= 1.1;
												} ?>
											</span>
										</td>
										<td></td>
										<td class='hidden-print' ><span hidden='hidden' class='ppn_status'><?=$ppn_status;?></span></td>
									</tr>
								<? } ?>

								<tr class='rekap_section'>
									<td></td>
									<td style='text-align:right'>TOTAL</td>
									<td style='text-align:right'> 
										<span class='qty_total'>
											<?=number_format($total_qty,'2','.',',');;?> </td>
										</span>
									<td></td>
									<?if ($currency_type_id == 2) { ?>
										<td></td>
									<? }?>
									<td style='text-align:right'>
										<span class='grand_total'>
											<?=number_format($total,'2','.',',');?>
										</span>
									</td>
									<td></td>
									<td class='hidden-print' ></td>
								</tr>
							</tbody>
						</table>
					</div>
					<div class="row">
		                <div class="col-md-12" style='margin-top:30px;'>
		                	<?if ($po_type != 'noppn') { ?>
		                		<a href="<?=$common_data['controller_main'].'/pembelian_detail_ppn_status?po_type='.$po_type.'&pembelian_id='.$pembelian_id.'&ppn_status='.$ppn_status;?>" class='btn yellow hidden-print'> PPN <?=$btn;?> </a>
		                	<? } ?>
		                	<a href="javascript:window.open('','_self').close();" class="btn default button-previous hidden-print">Close</a>
		                </div>
					</div>
				</div>
			</div>
		</div>
	</div>			
</div>

<?php echo link_tag('assets/global/plugins/bootstrap-datepicker/css/datepicker3.css'); ?>
<script src="<?php echo base_url('assets/global/plugins/datatables/media/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/datatables/extensions/ColReorder/js/dataTables.colReorder.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets_noondev/js/table-advanced.js'); ?>"></script>


<script src="<?php echo base_url('assets/global/plugins/bootstrap-select/bootstrap-select.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/select2/select2.min.js'); ?>" type="text/javascript" ></script>

<script>
jQuery(document).ready(function() {

	// TableAdvanced.init();

	// oTable = $('#general_table').DataTable();
	// oTable.state.clear();
	// oTable.destroy();

	$('[data-toggle="popover"]').popover();

	$('#barang_id_select').select2({
        placeholder: "Select...",
        allowClear: true
    });

    $("#remarks [name=list_alamat]").change(function(){
    	if ($(this).val() != '') {
    		$('#remarks [name=alamat]').val($(this).val());
    		var pjg = $(this).val().length;
	    	pjg = (pjg + 1) * 5.7;
	    	$('#remarks [name=alamat]').css('width',pjg+'px');
    	};
    });

    $('.btn-save').click(function(){
    	$('#form_add_data').submit();
    });

    $('.btn-kurs-save').click(function(){
		$('#form_kurs').submit();
	});

    $('.btn-bonus-save').click(function(){
		if($('#form_bonus [name=qty]').val()==''){
			notific8('ruby', 'Kuantitas tidak boleh 0 !!');
		}else{
			$('#form_bonus').submit();
		}
	})

    $('#table_detail').on('click','.kurs', function(){
    	$('#portlet-config-kurs').modal('toggle');
    	setTimeout(function(){
    		$('#form_kurs [name=kurs]').focus();
    	},700);
    });

	
	// $('#table_detail').on('click','tbody td:not(:last-child)',function(){
	// 	var ini = $(this).closest('tr');
	// 	$('#form_edit_data [name=id]').val(ini.find('.id').html());
	// 	$('#form_edit_data [name=barang_id]').val(ini.find('.barang_id').html());
	// 	$('#form_edit_data [name=qty]').val(ini.find('.qty').html());
	// 	$('#form_edit_data [name=qty_notes]').val(ini.find('.qty_notes').html());
	// 	$('#form_edit_data [name=price]').val(ini.find('.price').html());
	// 	// $('#form_edit_data [name=]').val(ini.find().html());
	// 	$('#portlet-config-edit').modal('toggle');
	// });

	$('#table_detail').on('click','.btn-remove',function(){
		var ini = $(this).closest('tr');
		bootbox.confirm('Yakin menghapus baris ini ? ', function(result){
			if (result) {
				var data = {};
				data['po_type'] = '<?=$po_type;?>';
				data['id'] = ini.find('.id').html();
				var url = "<?=$common_data['controller_main'].'/po_pembelian_detail_remove';?>";
				ajax_data_sync(url,data).done(function(data_respond  /*,textStatus, jqXHR*/ ){
		   			if(data_respond == 'OK'){
		   				ini.remove();
		   				notific8('lime', 'OK');
		   			}else{
		   				notific8('ruby', 'ERROR !!');
		   			}
		   		});
			};
		});
	});

	$('#table_detail').on('click','.btn-remove-bonus',function(){
		var ini = $(this).closest('tr');
		bootbox.confirm('Yakin menghapus baris ini ? ', function(result){
			if (result) {
				var data = {};
				data['po_type'] = '<?=$po_type;?>';
				data['id'] = ini.find('.id').html();
				var url = "<?=$common_data['controller_main'].'/po_pembelian_detail_remove_bonus';?>";
				ajax_data_sync(url,data).done(function(data_respond  /*,textStatus, jqXHR*/ ){
		   			if(data_respond == 'OK'){
		   				ini.remove();
		   				notific8('lime', 'OK');
		   			}else{
		   				notific8('ruby', 'ERROR !!');
		   			}
		   		});
			};
		});
	});

	$('#table_detail').on('click','.btn-bonus',function(){
		var ini = $(this).closest('tr');
		var barang_id = ini.find('.barang_id').html();
		var id = ini.find('.id').html();
		var nama_barang = ini.find('.nama_barang').html();
		$('#form_bonus [name=po_pembelian_detail_id]').val(id);
		$('#form_bonus [name=barang_id]').val(barang_id);
		$('#form_bonus [name=nama_barang]').val(nama_barang);	
		$('#portlet-config-bonus').modal('toggle');
		
	});

	$('.edit-remarks').click(function(){
    	var isi = $('#cke-content').html();
    	$("#editor").data("wysihtml5").editor.setValue(isi);
		
    	// alert(isi);
    	// $();
    });

   	$('.btn-save-editor').click(function(){
		var isi = $('#editor').val();
		$('#form_editor [name=remarks]').val(isi);
		$('#form_editor').submit();
	});


	$('#table_detail').on('change','[name=qty],[name=qty_notes],[name=price]', function(){
		var ini = $(this).closest('tr');
		var qty = ini.find('[name=qty]').val();
		var price = ini.find('[name=price]').val();
		var ppn_status = '';
		<?if ($po_type != 'noppn') {?>
			ppn_status = <?=$ppn_status;?>;
		<?};?>
		
		var currency_type_id = "<?=$currency_type_id;?>";
		if (currency_type_id == 2) {
			var kurs = "<?=$kurs;?>";
			var total = qty * price * kurs;
			// alert(qty+' : '+price+ ' : ' +kurs);
		}else{
			var total = qty * price;
		};
		var data = {};
		data['column'] = $(this).attr('name');
		data['value'] = $(this).val();
		data['po_type'] = "<?=$po_type;?>";
		data['id'] = $(this).closest('tr').find('.id').html();
		var url = "<?=$common_data['controller_main'].'/pembelian_detail_update';?>";
		ajax_data_sync(url,data).done(function(data_respond  /*,textStatus, jqXHR*/ ){
   			if (data_respond == 'OK') {
   				var g_total = 0;
   				var qty_total = 0;

   				notific8('lime', 'OK');
   				ini.find('.subtotal').html(change_number_format2(total));
   				$('#table_detail .subtotal').each(function(){
   					g_total += reset_number_format2($(this).html());
   					alert(reset_number_format2($(this).html()));
   				});


   				$('#table_detail [name=qty]').each(function(){
   					qty_total += parseFloat($(this).val());
   				});

   				$('#table_detail [name=qty_bonus]').each(function(){
   					qty_total += parseFloat($(this).val());
   				});

   				if (ppn_status == 1) {
   					var ppn_total = g_total * 0.1;
   					$('.ppn_total').html(change_number_format2(ppn_total));
   					g_total += ppn_total;
   					// alert(g_total);
   				}
   				$('.qty_total').html(qty_total);
   				$('.grand_total').html(change_number_format2(g_total));
   				// alert(g_total);
   			};
		});
	});

	$('#table_detail').on('change','[name=qty_bonus],[name=qty_notes_bonus]', function(){
		var ini = $(this).closest('tr');
		var qty = ini.find('[name=qty_bonus]').val();
		
		var data = {};
		data['column'] = $(this).attr('name');
		data['value'] = $(this).val();
		data['po_type'] = "<?=$po_type;?>";
		data['id'] = $(this).closest('tr').find('.id').html();
		var url = "<?=$common_data['controller_main'].'/po_pembelian_detail_update_bonus';?>";
		ajax_data_sync(url,data).done(function(data_respond  /*,textStatus, jqXHR*/ ){
   			if (data_respond == 'OK') {
   				var qty_total = 0;
   				
   				$('#table_detail [name=qty]').each(function(){
   					qty_total += parseFloat($(this).val());
   				});
   				
   				$('#table_detail [name=qty_bonus]').each(function(){
   					qty_total += parseFloat($(this).val());
   				});

   				
   				$('.qty_total').html(qty_total);
   				// alert(g_total);
   			};
		});
	});

	$('.btn-edit-save').click(function(){
    	$('#form_edit_data').submit();
    });
});
</script>
