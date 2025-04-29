<div class="modal-full" style='padding:10px;'>
<form action="<?=base_url('transaction/pembelian_list_update')?>" class="form-horizontal" id="form_edit_data" method="post">
							<!-- <h3 class='block'> Tambah </h3> -->
	<!-- <b>Input pembelian</b>
    <hr/> -->
    <table>
    	<tr>
    		<td>Supplier</td>
    		<td> : </td>
    		<td>
    			<input name='id'>
    			<select class='input1 supplier-input' style='font-weight:bold' name="supplier_id">
            		<?foreach ($this->supplier_list_aktif as $row) { ?>
            			<option value="<?=$row->id?>"><?=$row->nama;?></option>
            		<? } ?>
            	</select>
    		</td>
    	</tr>
    	<td>Gudang</td>
    		<td> : </td>
    		<td>
    			<select style='font-weight:bold' class='gudang-input' name="gudang_id">
            		<?foreach ($this->gudang_list_aktif as $row) { ?>
            			<option value="<?=$row->id?>"><?=$row->nama;?></option>
            		<? } ?>
            	</select>
            </td>
    	<tr>
    		<td>No Faktur</td>
    		<td> : </td>
    		<td>
    			<input type="text" class="" name="no_faktur"/>
    			<span class='input-alert'><i class='fa fa-times'></i></span>
    			<span class='input-success'><i class='fa fa-check'></i></span>
    			<span hidden='hidden' class='no_faktur_status'>false</span>
    		</td>
    	</tr>
    	<tr>
    		<td>OCKH</td>
    		<td> : </td>
    		<td>
    			<input type="text" name="ockh"/>
    		</td>
    	</tr>
    	<tr>
    		<td>Tanggal</td>
    		<td> : </td>
    		<td><input type="text" readonly class="date-picker" value="<?=date('d/m/Y');?>" name="tanggal"/></td>
    	</tr>
    	<tr>
    		<td>Toko</td>
    		<td> : </td>
    		<td>
    			<select name="toko_id">
            		<?foreach ($this->toko_list_aktif as $row) { ?>
            			<option value="<?=$row->id?>"><?=$row->nama;?></option>
            		<? } ?>
            	</select>
    		</td>
    	</tr>
    </table>


    <fieldset style='border:1px solid black; padding:10px;width:60%; margin:20px 0'>
    	<legend style='background:none;border:none; width:auto; margin:0;'>Formulir Barang</legend>
    	<table style='width:100%;' id='form-edit-list'>
	    	<tr>
	    		<td>Barang</td>
	    		<td> : </td>
	    		<td>
	    			<select name="barang_id" class='barang-id' id='barang_id_select'>
	    				<option value="">Pilihan..</option>
	            		<?foreach ($this->barang_list_aktif as $row) { ?>
	            			<option value="<?=$row->id?>"><?=$row->nama;?></option>
	            		<? } ?>
	            	</select>
	            	<select name='harga' hidden='hidden'>
	            		<?foreach ($this->barang_list_aktif as $row) { ?>
	            			<option value="<?=$row->id?>"><?=$row->harga_beli;?></option>
	            		<? } ?>
	            	</select>
	            </td>

	            <td class='right-td'>Satuan</td>
	    		<td> : </td>
	    		<td><select name="satuan_id">
	            		<?foreach ($this->satuan_list_aktif as $row) { ?>
	            			<option value="<?=$row->id?>"><?=$row->nama;?></option>
	            		<? } ?>
	            	</select>
	            </td>


	    	</tr>
	    	<tr>
	    		<td>Warna</td>
	    		<td> : </td>
	    		<td>
	    			<select name="warna_id" class='warna-id'>
	    				<option value=''>Pilihan..</option>
	            		<?foreach ($this->warna_list_aktif as $row) { ?>
	            			<option value="<?=$row->id?>"><?=$row->warna_beli;?></option>
	            		<? } ?>
	            	</select>
	    		</td>

	    		<td class='right-td'>Harga Beli</td>
	    		<td> : </td>
	    		<td>
	    			<input type="text" class='amount_number' name="harga_beli"/>
	    		</td>
	    		
	    	</tr>
	    	<tr>

	    		<td>Qty</td>
	    		<td> : </td>
	    		<td>
	    			<input name='qty'>
	    		</td>

	    		<td class='right-td'>Jumlah Roll</td>
	    		<td> : </td>
	    		<td>
	    			<input type="text" name="jumlah_roll"/>
	    		</td>	
						                		
	    	</tr>

	    	<!-- <tr>

	    		
	    		
	    	</tr> -->

	    	<tr>

	    		<td>Total</td>
	    		<td> : </td>
	    		<td>
	    			<span style='font-size:1.5em;font-weight:bold' class='total'></span>
	    		</td>			                		

	    		<td colspan='3'>
	    			<button class='btn btn-xs green btn-add-list'><i class='fa fa-plus'></i> Tambah</button>
	    		</td>
	    	</tr>


	    </table>
    </fieldset>

    <table class='table table-striped table-bordered table-hover' id='rekap_barang_list'>
    	<thead>
    		<tr>
    			<th>Nama Barang</th>
    			<th>Satuan</th>
    			<!-- <th>Gudang</th> -->
    			<th>Qty</th>
    			<th>Roll</th>
    			<th>Harga</th>
    			<th>Total</th>
    			<th style='width:200px !important'>Action</th>
    		</tr>
    	</thead>
    	<tbody>
    		<?foreach ($pembelian_list as $row) { ?>
    			<tr>
					<td>
					<input value="<?=$row->id?>"> 
						<input name='barang_id[]' hidden='hidden' value="<?=$row->barang_id?>"><?=$row->nama_barang;?><input name='warna_id' hidden='hidden' value="<?=$row->warna_id;?>"><?=$row->warna_beli;?></td>
	    			<td> <input name='satuan_id[]' hidden='hidden' value="<?=$row->satuan_id?>"><?=$row->nama_satuan;?></td>
					<td> <input name='qty[]' value="<?=$row->qty;?>"></td>
					<td> <input name='jumlah_roll[]' value="<?=$row->jumlah_roll;?>"></td>
					<td> <input name='harga_beli[]' class='amount_number' value="<?=is_rupiah_format($row->harga_beli);?>"></td>
					<td> <span class='total'><?=is_rupiah_format($row->qty * $row->harga_beli);?></span></td>
					<td> <button type='button' class='btn btn-xs red btn-remove-list'><i class='fa fa-times'></i></button></td>
				</tr>
    		<? }?>
    	</tbody>
    </table>

    <table width="100%" id='rekap_harga'>
    	<tr>
    		<td>
    			<table>
    				<tr>
    					<td>Subtotal</td>
	                		<td> : </td>
	                		<td>
	                			<span class='subtotal'>0</span>
	                		</td>
	                	</tr>
	                	<tr>
	                		<td>Diskon</td>
	                		<td> : </td>
	                		<td><input name='diskon' class='amount_number' value='0'/></td>
	                	</tr>
	                	<tr>
	                		<td>Jatuh Tempo</td>
	                		<td> : </td>
	                		<td><input readonly name='jatuh_tempo' class='date-picker' value="<?=date('d/m/Y');?>"/></td>
	                	</tr>
	                	<tr>
	                		<td>Keterangan</td>
	                		<td> : </td>
	                		<td><input name='keterangan'/></td>
	                	</tr>
	                </table>
    		</td>
    		<td>
    			<div style='font-size:3em;font-weight:bold'>
    				Rp. <span class='grand_total'></span>,-
    			</div>
    		</td>
		</tr>
	</table> 
</form>
</div>
<div class="modal-footer">
	<button type="button" class="btn blue btn-edit-save">Save</button>
	<button type="button" class="btn default" data-dismiss="modal">Close</button>
</div>

<script>
jQuery(document).ready(function() {   
	//$("#sidebar").load("sidebar.html"); 
   	Metronic.init(); // init metronic core componets
   	Layout.init(); // init layout
   	//TableAdvanced.init();

   	$('#form-edit-list [name=barang_id]').change(function(){
   		var barang_id = $('#form-edit-list [name=barang_id]').val();
   		var harga = $("#form-edit-list [name=harga] [value='"+barang_id+"']").text();
   		// alert(harga);
		$('#form-edit-list [name=harga_beli]').val(change_number_format(harga));
   	});
   	
   	$('#form_edit_data .btn-add-list').click(function(){
   		// alert($('#form-edit-list [name=supplier_id]').val());
   		$('#form-edit-list [name=barang_id]').focus()
   		event.preventDefault();
   		var isi_tbl = $(this).closest('div').find('#rekap_barang_list').html()+"<tr>";
   		// alert($(this).closest('div').find('#rekap_barang_list').html());
		var barang_id = $('#form-edit-list [name=barang_id] :selected').val();
		var barang_nama = $('#form-edit-list [name=barang_id] :selected').text();
		var warna_id = $('#form-edit-list [name=warna_id]').val();
   		var harga = $('#form-edit-list [name=harga_beli]').val();
   		// alert(harga);
		var qty = $('#form-edit-list [name=qty]').val();
		var total = $('#form-edit-list .total').html();
		var satuan_id = $('#form-edit-list [name=satuan_id] :selected').val();
		var gudang_id = $('#form-edit-list [name=gudang_id] :selected').val();
		var jumlah_roll = $('#form-edit-list [name=jumlah_roll]').val();

		if (barang_id != '' && warna_id != '' && harga != '' && qty != '' && satuan_id != '' && jumlah_roll != '') {
			isi_tbl += "<td> <input name='barang_id[]' hidden='hidden' value='"+barang_id+"'>"+barang_nama;
			isi_tbl += "<input name='warna_id' hidden='hidden' value='"+warna_id+"'>"+$('#form-edit-list [name=warna_id]:selected').text()+"</td>";
			isi_tbl += "<td> <input name='satuan_id[]' hidden='hidden' value='"+satuan_id+"'>"+$('#form-edit-list [name=satuan_id] :selected').text()+"</td>";
			// isi_tbl += "<td> <input name='gudang_id' hidden='hidden' value='"+gudang_id+"'>"+$('#form-edit-list [name=gudang_id] :selected').text()+"</td>";
			isi_tbl += "<td> <input name='qty[]' value='"+qty+"'></td>";
			isi_tbl += "<td> <input name='jumlah_roll[]' value='"+jumlah_roll+"'></td>";
			isi_tbl += "<td> <input name='harga_beli[]' class='amount_number' value='"+harga+"'></td>";
			isi_tbl += "<td> <span class='total'>"+total+"</span></td>";
			isi_tbl += "<td> <button type='button' class='btn btn-xs red btn-remove-list'><i class='fa fa-times'></i></button></td>";
			isi_tbl += "</tr>";

			$('#barang_id_select').focus().select();
			$(this).closest('div').find('#rekap_barang_list').html(isi_tbl);

			var subtotal = reset_number_format($('#form_edit_data #rekap_harga .subtotal').html());
			
			alert(subtotal);
			subtotal = parseInt(subtotal) + parseInt(reset_number_format(total));
			$('#form_edit_data #rekap_harga .subtotal').html(change_number_format(subtotal));
			var diskon = reset_number_format($('#form-edit-list .diskon').val());
			var grand_total = parseInt(subtotal) - parseInt(diskon);
			// alert(grand_total);
			$('#form_edit_data #rekap_harga .grand_total').html(change_number_format(grand_total));


			$('#form-edit-list [name=barang_id]').val('');
			$('#form-edit-list [name=barang_id]').change();
			$('#form-edit-list [name=warna_id]').val('');
			$('#form-edit-list [name=warna_id]').change();
	   		$('#form-edit-list [name=harga_beli]').val('');
			$('#form-edit-list [name=qty]').val('');
			$('#form-edit-list .total').html('');
			// $('#form-edit-list [name=satuan_id]').val('');
			// $('#form-edit-list [name=gudang_id]').val('');
			$('#form-edit-list [name=jumlah_roll]').val('');


			setTimeout(function(){
				
			},500);
		}else{
			notific8('ruby', "Mohon lengkapi formulir ..!!");
		}
   	});

	$('#form-edit-list [name=qty], #form-edit-list [name=harga_beli]').change(function(){
   		var harga = $('#form-edit-list [name=harga_beli]').val();
   		var qty = $('#form-edit-list [name=qty]').val();

   		if (harga == '' || $.isNumeric(harga) == false) {
   			harga = 0;
   			$('#form-edit-list [name=harga_beli]').val(harga);
   		};

   		if ($.isNumeric(qty) == false) {qty = 0;};
   		var total = reset_number_format(harga) * qty;
   		if (total > 0) {total = change_number_format(total);};
   		$('#form-edit-list .total').html(total);

   	});

});