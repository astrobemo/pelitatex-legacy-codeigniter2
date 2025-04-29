<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css'); ?>"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css'); ?>"/>
<?=link_tag('assets/global/plugins/bootstrap-datepicker/css/datepicker3.css'); ?>
<link href="<?=base_url('assets/global/plugins/bootstrap-modal/css/bootstrap-modal.css');?>" rel="stylesheet" type="text/css"/>

<?=link_tag('assets/global/plugins/select2/select2.css'); ?>
<link href="<?=base_url('assets_noondev/css/bootstrap-modal-bs3patch.css');?>" rel="stylesheet" type="text/css"/>

<style type="text/css">
#general_table tr th{
	vertical-align: middle;
	text-align: center;
	/*font-size: 0.95em;*/

}

#general_table tr td{
	color:#000;
	/*font-size: 0.8em;*/
	/*font-family: Arial;*/
	/*font-size: 12px;*/
}

#general_table{
	border-bottom: 2px solid #ddd;
}

#general_table ul li{
	list-style-type: none;
	display: inline-table;
}

#general_table .barang-list .nama-barang, #general_table .pelunasan-list .tanggal-lunas{
	width:250px;
}

#general_table .barang-list .qty-barang, #general_table .barang-list .harga-barang
{
	width:50px;
}


#general_table .barang-list .harga-barang{
	text-align: center;
}

#general_table .barang-list .roll-barang
{
	width:40px;
}

#general_table .barang-list .total-barang{
	width:100px;
	text-align: right;
}

#general_table ul{
	padding: 0px;
	margin: 0px;
}

#general_table .pelunasan-list .tipe-lunas
{
	width:140px;
}

#general_table .pelunasan-list .amount-lunas
{
	width:100px;
	text-align: right;
}

</style>

<div class="page-content">
	<div class='container'>

		
		
		<div class="row margin-top-10">
			<div class="col-md-12">
				<div class="portlet light">
					<div class="portlet-title">
						<div class="caption caption-md">
							<i class="icon-bar-chart theme-font hide"></i>
							<span class="caption-subject theme-font bold uppercase"><?=$breadcrumb_small;?></span>
						</div>
						<div class="actions">
							
						</div>
					</div>
					<div class="portlet-body">
						<form id='form-search' method='get'>
							<table>
								<tr>
									<td>Tanggal</td>
									<td>:</td>
									<td>
										<b>
											<input name='tanggal_start' readonly class='date-picker' style='border:none; border-bottom:1px solid #ddd; width:100px;' value='<?=$tanggal_start;?>'>
											s/d
											<input name='tanggal_end' readonly class='date-picker2 ' style='border:none; border-bottom:1px solid #ddd; width:100px;' value='<?=$tanggal_end;?>'> 
										</b>
									</td>
									<td>
										<button type='button' class='btn btn-xs default btn-search'><i class='fa fa-search'></i></button>
									</td>
								</tr>
								<tr hidden>
									<td>Tipe </td>
									<td>: </td>
									<td>
										<b>
											<select name='tipe_search'>
												<option <?=($tipe_search == 1 ? "selected" : "");?> value='1'>Semua</option>
												<option <?=($tipe_search == 2 ? "selected" : "");?> value='2'>Lunas (Cash)</option>
												<option <?=($tipe_search == 3 ? "selected" : "");?> value='3' >Lunas (Kredit)</option>
												<option <?=($tipe_search == 4 ? "selected" : "");?> value='4'>Belum Lunas</option>
												<option <?=($tipe_search == 5 ? "selected" : "");?> value='5'>Faktur Pajak</option>
												<option <?=($tipe_search == 6 ? "selected" : "");?> value='6'>Non Faktur Pajak</option>
											</select>
										</b>
									</td>
									<td></td>
								</tr>
								<tr>
									<td>Customer</td>
									<td> : </td>
									<td>
										<b>
											<select name='customer_id' id="select_customer" style='width:100%'>
												<option <?=($customer_id == 0 ? "selected" : "");?> value='0'>Semua</option>
												<?foreach ($this->customer_list_aktif as $row) { ?>
													<option <?=($customer_id == $row->id ? "selected" : "");?> value="<?=$row->id?>"><?=$row->nama;?></option>
												<?}?>
											</select>
										</b>
									</td>
									<td></td>
								</tr>
							</table>
						 
							
							
						
						</form>
						<hr/>
						<!-- table-striped table-bordered  -->
						<table class="table table-bordered table-hover table-striped " id="general_table">
							<thead>
								<tr style='background:#eee' >
									<th scope="col" style='width:90px !important;'>
										No Faktur
									</th>
									<th scope="col" style='width:90px !important;'>
										Tanggal
									</th>
									<th scope="col" hidden>
										Jumlah <br/>
										Yard/KG
									</th>
									<th scope="col" hidden>
										Jumlah <br/> Roll
									</th>
									<th scope="col" style='text-align:left'>
										<ul class='barang-list'>
											<li class='nama-barang'>Nama Barang</li>
							            	<li class='qty-barang'>Qty</li>
							            	<li class='roll-barang'>Roll</li>
							            	<li class='harga-barang'>Harga</li>
										</ul>
									</th>
									<th scope="col" hidden>
										Harga
									</th>
									<th scope="col" hidden>
										Total
									</th>
									<th scope="col" style='width:150px !important;'>
										Nama Customer
									</th>
									<th scope="col" hidden>
										Pembayaran
									</th>
									<th scope="col" hidden>
										Pelunasan
									</th>
									<th scope="col" style='width:70px !important;'>
										Ket.
									</th>
									<th scope="col" hidden>
										Penjualan ID
									</th>
									<!-- <th scope="col">
										Jatuh Tempo
									</th> -->
								</tr>
							</thead>
							<tbody>
								<?/*
								$idx_total = 0; $g_total = 0;
								$yard_total = 0; $roll_total = 0;
								foreach ($penjualan_list as $row) { ?>
									<?
										$qty = ''; $jumlah_roll = ''; $nama_barang = ''; $harga_jual = '';
										if ($row->qty != '') {
											$qty = explode('??', $row->qty);
											$jumlah_roll = explode('??', $row->jumlah_roll);
											$nama_barang = explode('??', $row->nama_barang);
											$harga_jual = explode('??', $row->harga_jual);
										}
										$yard_total += $row->qty;
										$roll_total += $row->jumlah_roll;
									?>
									<tr class='text-center' >
										<td>
											<a href="<?=base_url().is_setting_link('transaction/penjualan_list_detail')?>/?id=<?=$row->id;?>" target='_blank'><?=$row->no_faktur;?></a>
										</td>
										<td>
											<?=is_reverse_date($row->tanggal);?>
										</td>
										<td>
											<?
											if ($qty != '') {
												$baris = count($qty);
												$j = 1; $idx = 1;
												foreach ($qty as $key => $value) {
													echo str_replace(',00', '', number_format($value,'2',',','.')).'<br/>';
													if ($j % 3 == 0 && $baris != $idx) {
														echo "<hr style='margin:2px'/>";
														$j = 1;
													}else{													
														$j++;
													}
													$idx++;
												}
											}
											?>
										</td>
										<td>
											<?
											if ($jumlah_roll != '') {
												$j = 1; $idx = 1;
												foreach ($jumlah_roll as $key => $value) {
													echo str_replace(',00', '', number_format($value,'2',',','.')).'<br/>';
													if ($j % 3 == 0 && $baris != $idx) {
														echo "<hr style='margin:2px'/>";
														// echo '---<br/>';
														$j = 1;
													}else{													
														$j++;
													}
													$idx++;
												}
											}

											?>
										</td>
										<td>
											<span class='id' hidden="hidden"><?=$row->id;?></span>
											<?
											if ($nama_barang != '') {
												$j = 1; $idx = 1;
												foreach ($nama_barang as $key => $value) {
													echo "<span class='nama'>".$value."</span>".'<br/>';
													if ($j % 3 == 0 && $baris != $idx) {
														echo "<hr style='margin:2px'/>";
														// echo '---<br/>';
														$j = 1;
													}else{													
														$j++;
													}
													$idx++;
												}
											}
											?>
											<!-- <span class='nama'><?=str_replace('??', '<br/>', $row->nama_barang);?></span><br/> -->
											
										</td>
										<td>
											<?
											if ($harga_jual != '') {
												$j = 1; $idx = 1;
												foreach ($harga_jual as $key => $value) {
													echo str_replace(',00', '', number_format($value,'2',',','.')).'<br/>';
													if ($j % 3 == 0 && $baris != $idx) {
														echo "<hr style='margin:2px'/>";
														// echo '---<br/>';
														$j = 1;
													}else{													
														$j++;
													}
													$idx++;
												}
											}?>
											<b>Subtotal:</b>
										</td>
										<td>
											
											<?
											$subtotal = 0; 
											if ($harga_jual != '') {
												$j = 1; $idx = 1;
												foreach ($harga_jual as $key => $value) {
													echo number_format($qty[$key] * $value,'0',',','.').'<br/>';
													$subtotal +=$qty[$key] * $value; 
													$g_total += $qty[$key] * $value;

													if ($j % 3 == 0 && $baris != $idx) {
														echo "<hr style='margin:2px'/>";
														// echo '---<br/>';
														$j = 1;
													}else{													
														$j++;
													}
													$idx++;
												}
											}
											
											?>
											<b><?=number_format($subtotal,'0',',','.')?></b>
										</td>
										<td>
											<?=$row->nama_customer;?> 
										</td>
										<td>
											<?if ($row->keterangan < 0) { ?>
												<span style='color:red'>belum lunas</span>
											<?}else if ($row->keterangan >= 0){?>
												<span style='color:green'>lunas</span>
											<?}?> 
										</td>
										<?
										unset($pembayaran_type_id); unset($data_bayar);
										$pembayaran_type_id = explode(',', $row->pembayaran_type_id);
										$data_bayar = explode(',', $row->data_bayar);
										$bayar = array_combine($pembayaran_type_id, $data_bayar);

										?>
										<!-- <td>
											<?=is_reverse_date($row->jatuh_tempo);?>
										</td> -->
									</tr>
								<? $idx_total++;} */?>
							</tbody>
						</table>

						<hr/>

						<table class='table table-bordered table-hover table-striped'>
							<tr>
								<th>Transaksi</th>
								<th>Yard</th>
								<th>Roll</th>
								<th>Total</th>
								<th>Bayar</th>
								<th>Sisa</th>
							</tr>
							<tr style='font-size:1.2em;font-weight:bold'>
								<td class='text-center total-trx'></td>
								<td class='text-center total-yard'></td>
								<td class='text-center total-roll'></td>
								<td class='text-center total-grand'></td>
								<td class='text-center total-bayar'></td>
								<td class='text-center total-sisa'></td>
							</tr>

						</table>
					</div>

					<form action='<?=base_url();?>report/penjualan_list_export_excel' method='get'>
						<input name='tanggal_start' value='<?=$tanggal_start;?>' hidden='hidden'>
						<input name='tanggal_end' value='<?=$tanggal_end;?>' hidden='hidden'>
						<input name='tipe_search' value='<?=$tipe_search;?>' hidden='hidden'>
						<input name='customer_id' value='<?=$customer_id;?>' hidden='hidden'>

						<button class='btn green'><i class='fa fa-download'></i> Excel</button>
					</form>
				</div>
			</div>
		</div>
	</div>			
</div>

<script src="<?php echo base_url('assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/datatables/media/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets_noondev/js/table-advanced.js'); ?>"></script>

<script src="<?php echo base_url('assets/global/plugins/select2/select2.min.js'); ?>" type="text/javascript" ></script>
<script src="<?php echo base_url('assets_noondev/js/fnReload.js');?>"></script>

<script>
jQuery(document).ready(function() {
	// Metronic.init(); // init metronic core components
	// Layout.init(); // init current layout
	// TableAdvanced.init();

	// oTable = $('#general_table').DataTable();
	// oTable.state.clear();
	// oTable.destroy();

	var oTable;
    oTable = $('#general_table');

    var total_grand = 0;
    var total_yard = 0;
    var total_roll = 0;
    var total_bayar = 0;
    var idx = 0;

	oTable.dataTable({
   		"fnCreatedRow": function( nRow, aData, iDataIndex ) {
            
            // var tgl = $('td:eq(3)', nRow).text();
            var tanggal = date_formatter($('td:eq(1)', nRow).text());
            var data_show = '';
            
            var penjualan_id = $('td:eq(11)', nRow).text();
            var no_faktur = $('td:eq(0)', nRow).text();
         	var pembayaran_data = $('td:eq(8)', nRow).text().split('??');
            var pelunasan_data = $('td:eq(9)', nRow).text().split('??');
            var keterangan = $('td:eq(10)', nRow).text();
            
            if ($('td:eq(2)', nRow).text() != '') {
	            var qty_data = $('td:eq(2)', nRow).text().split('??');
	            var roll_data = $('td:eq(3)', nRow).text().split('??');
	            var nama_barang_data = $('td:eq(4)', nRow).text().split('??');
	            var harga_jual = $('td:eq(5)', nRow).text().split('??');
	            var total = $('td:eq(6)', nRow).text().split('??');

	            var subtotal = 0;
	            var subtotal_roll = 0;
	            var subtotal_yard = 0;

	           

	            $.each(nama_barang_data, function(i,v){
	            	data_show += "<ul class='barang-list'>";
	            	data_show += "<li class='nama-barang'>"+v+"</li>";
	            	data_show += "<li class='qty-barang'>"+qty_general(qty_data[i])+"</li>";
	            	data_show += "<li class='roll-barang'>"+roll_data[i]+"</li>";
	            	data_show += "<li class='harga-barang'>"+change_number_format(harga_jual[i])+"</li>";
	            	data_show += "<li class='total-barang'>"+change_number_format(parseInt(total[i]))+"</li>";
	            	
	            	subtotal_yard += parseInt(qty_data[i]);
	            	subtotal_roll += parseInt(roll_data[i]);
	            	subtotal += parseInt(total[i]);
	            	data_show += "</ul>";
	            });
	            data_show += "<hr style='margin:0px; padding:0px; border-width:2px' />";
	            data_show += "<ul class='barang-list'>";
            	data_show += "<li class='nama-barang'><b>TOTAL</b></li>";
            	data_show += "<li class='qty-barang'><b>"+change_number_format(subtotal_yard)+"</b></li>";
            	data_show += "<li class='roll-barang'><b>"+subtotal_roll+"</b></li>";
            	data_show += "<li class='harga-barang'></li>";
            	data_show += "<li class='total-barang'><b>"+change_number_format(subtotal)+"</b></li>";

            	total_yard += parseInt(subtotal_yard);
            	total_roll += parseInt(subtotal_roll);
            	total_grand += parseInt(subtotal);
            	data_show += "</ul>";
            	

            	data_show += "<b class='btn-see-bayar' style='cursor:pointer'>Pembayaran <i class='fa fa-sort-down'></i> </b>";
            	data_show += "<div class='bayar-data' hidden>";

            		// console.log(pembayaran_data);
            		if (pembayaran_data != '') {
			            var tgl_byr = pembayaran_data[0];
			            var tipe_bayar = pembayaran_data[1].split(',');
			            var jumlah_bayar = pembayaran_data[2].split(',');
			            var tipe_show = '';
		            	$.each(tipe_bayar, function(i,v){
		            		if (jumlah_bayar[i] != 0) {
			            		if (v == '1') {
		            				tipe_show = "DP";
		        				}else if (v == '2'){
		            				tipe_show = "CASH";
		        				}else if (v == '3'){
		            				tipe_show = "EDC";
		        				}else if (v == '4'){
		            				tipe_show = "TRANSFER";
		        				}
			            		data_show +="<ul class=pelunasan-list>";
		            			data_show +="<li class='tanggal-lunas'>"+tgl_byr+"</li>";
		            			data_show +="<li class='tipe-lunas'>"+tipe_show+"</li>";
		            			data_show +="<li class='amount-lunas'>"+change_number_format(jumlah_bayar[i])+"</li>";
		            			data_show +="</ul>";
					            data_show += "<hr style='margin:0px; padding:0px; border-width:2px' />";
			            		
			            		subtotal -= jumlah_bayar[i];
			            		total_bayar += jumlah_bayar[i];
		            			
		            		};
		            	});

		            	
	            	};

	            	if (pelunasan_data != '') {

	            		$.each(pelunasan_data, function(i,v){
	            			var piutang = v.split('--');
	            			var link_bayar = "<?=base_url().rtrim(base64_encode('finance/piutang_payment_form'),'=').'/?id=';?>" + piutang[0];

	            			var tgl = piutang[2].split(',');
	            			var tipe = piutang[3].split(',');

	            			var tgl_show = tgl.join(' / ');
	            			// console.log(tgl);
	            			var tipe_show = [];
	            			$.each(tipe, function(k,v2){
	            				if (v2 == '1') {
		            				tipe_show[k] = "TRANSFER(piutang) ";
	            				}else if (v2 == '2'){
		            				tipe_show[k] = "GIRO(piutang)";
	            				}else if (v2 == '3'){
		            				tipe_show[k] = "CASH(piutang)";
	            				}else if (v2 == '4'){
		            				tipe_show[k] = "EDC(piutang)";
	            				}
	            			});

	            			var tipe_show = tipe_show.join(' / ');
	            			data_show +="<ul class=pelunasan-list>";
	            			data_show +="<li class='tanggal-lunas'>"+tgl_show+"</li>";
	            			data_show +="<li class='tipe-lunas'><a target='_blank' href='"+link_bayar+"'> "+tipe_show+"</a></li>";
	            			data_show +="<li class='amount-lunas'>"+change_number_format(piutang[1])+"</li>";
		            		total_bayar += parseInt(piutang[1]);
	            			data_show +="</ul>";
		            		subtotal -= piutang[1];
			            });

			            data_show += "<hr style='margin:0px; padding:0px; border-width:2px' />";
	            	};

	            data_show +="<ul class=pelunasan-list>";
    			data_show +="<li class='tanggal-lunas'><b>SISA</b></li>";
    			data_show +="<li class='tipe-lunas'></li>";
    			data_show +="<li class='amount-lunas'><b>"+change_number_format(subtotal)+"</b></li>";
    			data_show +="</ul>";

            	data_show += "</div>";           	


            	idx++;

            };
            
            var link_detail = "<?=base_url().rtrim(base64_encode('transaction/penjualan_list_detail'),'=').'/?id=';?>" + penjualan_id;

            $('td:eq(0)', nRow).html("<a href='"+link_detail+"' target='_blank'>"+no_faktur+"</a>");
            $('td:eq(1)', nRow).html(tanggal);
            $('td:eq(4)', nRow).html(data_show);

            if (keterangan < 0) { 
				var ket_show = "<span style='color:red'>belum lunas</span>";
			}else if (keterangan >= 0){
				var ket_show = "<span style='color:green'>lunas</span>";
			} 

            $('td:eq(2)', nRow).addClass('status_column');
            $('td:eq(3)', nRow).addClass('status_column');
            $('td:eq(5)', nRow).addClass('status_column');
            $('td:eq(6)', nRow).addClass('status_column');
            $('td:eq(8)', nRow).addClass('status_column');
            $('td:eq(9)', nRow).addClass('status_column');
            // $('td:eq(10)', nRow).addClass('status_column');
            $('td:eq(11)', nRow).addClass('status_column');
            
            $('td:eq(10)', nRow).html(ket_show);
          

            $('.total-trx').html(idx);
            $('.total-yard').html(total_yard);
            $('.total-roll').html(total_roll);
            $('.total-bayar').html(change_number_format(total_bayar));
            $('.total-grand').html(change_number_format(total_grand));
            $('.total-sisa').html(change_number_format(total_grand - total_bayar));

            idx++;
        },
        "bStateSave" :true,
		"bProcessing": true,
		"bServerSide": true,
		"order":[[1, 'desc']],
		"sAjaxSource": baseurl + "report/data_buku_laporan_piutang?tanggal_start=<?=$tanggal_start?>&tanggal_end=<?=$tanggal_end?>&customer_id=<?=$customer_id?>"
	});

	$(document).on('click','.btn-see-bayar', function(){
		$(this).closest('td').find('.bayar-data').toggle('fast');
	});

	$('.btn-search').click(function(e){
		e.preventDefault();
		var form = $("#form-search");
		var customer_id = form.find('[name=customer_id]').val();
		var tanggal_start = form.find('[name=tanggal_start]').val();
		var tanggal_end = form.find('[name=tanggal_end]').val();
		oTable.fnReloadAjax(baseurl + "report/data_buku_laporan_piutang?tanggal_start="+tanggal_start+"&tanggal_end="+tanggal_end+"&customer_id="+customer_id);
	});
    

});
</script>
