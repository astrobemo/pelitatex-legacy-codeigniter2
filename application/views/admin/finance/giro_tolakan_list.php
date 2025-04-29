<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css'); ?>"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css'); ?>"/>
<?php echo link_tag('assets/global/plugins/bootstrap-datepicker/css/datepicker3.css'); ?>

<div class="page-content">
	<div class='container'>

		<div class="modal fade" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body">
						<form action="<?=base_url('finance/giro_tolakan_update')?>" class="form-horizontal" id="form_add_data" method="post">
							<h3 class='block'>Data Baru</h3>
							
			                <div class="form-group">
			                    <label class="control-label col-md-3">Tanggal Tolakan<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
	                    			<input name='giro_tolakan_id' hidden >
	                    			<input name='tanggal' class='form-control date-picker' value="<?=date('d/m/Y')?>" >
			                    </div>
			                </div> 

			                <div class="form-group">
			                    <label class="control-label col-md-3">Customer List
			                    </label>
			                    <div class="col-md-6">
	                    			<select name="customer_id" class="form-control" id="customer-id-select">
	                    				<option value="">Pilih..</option>
	                    				<?foreach ($this->customer_list_aktif as $row) {?>
	                    					<option value="<?=$row->id?>"><?=$row->nama;?></option>
	                    				<?}?>
	                    			</select>
			                    </div>
			                </div> 

			                <div class="form-group add-alamat-keterangan">
			                    <label class="control-label col-md-3">No Giro
			                    </label>
			                    <div class="col-md-6">
			                    	<select name="pembayaran_piutang_nilai_id" class="form-control" id="giro-list">
	                    				<option value="">Pilih..</option>
	                    			</select>

	                    			<select id="giro-list-copy" hidden >
	                    				<option value="">Pilih..</option>
	                    			</select>
			                    </div>
			                </div>

			                <div class="form-group">
			                    <label class="control-label col-md-3">Keterangan Tolakan
			                    </label>
			                    <div class="col-md-6">
			                    	<input name='keterangan' autocomplete='off' class="form-control">
			                    </div>
			                </div>
						</form>
					</div>

					<div class="modal-footer">
						<button type="button" class="btn blue btn-active btn-trigger btn-save">Save</button>
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
						<div class="actions hidden-print">
								<a href="#portlet-config" data-toggle='modal' class="btn btn-default btn-sm btn-form-add">
								<i class="fa fa-plus"></i> Tambah </a>
						</div>
					</div>
					<div class="portlet-body">
						
						<table class="table table-hover table-striped table-bordered" id="general_table">
							<thead>
								<tr>	
									<th scope="col">
										Tanggal Terima
									</th>
									<th scope="col">
										No Giro
									</th>
									<th scope="col">
										Nama Bank
									</th>
									<th scope="col">
										Jatuh Tempo
									</th>
									<th scope="col">
										Nama Customer
									</th>
									<th scope="col">
										Nominal
									</th>
									<th scope="col">
										Tanggal Setor
									</th>
									<th scope="col">
										Tanggal Tolakan
									</th>
									<th scope="col">
										Keterangan Tolakan
									</th>
									<th scope="col">
										Urutan
									</th>
									<th scope="col">
										Status
									</th>
									<th scope="col">
										Actions
									</th>
								</tr>
							</thead>
							<tbody>
								<?if (count($giro_tolakan_list) > 0) {?>
									<?foreach ($giro_tolakan_list as $row) { ?>
										<tr>
											<td><?=is_reverse_date($row->tanggal_transfer);?></td>
											<td><?=$row->no_giro?></td>
											<td><?=$row->nama_bank?></td>
											<td><?=is_reverse_date($row->jatuh_tempo);?></td>
											<td><?=$row->nama_customer;?></td>
											<td><?=number_format($row->amount);?></td>
											<td><?=is_reverse_date($row->tanggal_setor);?></td>
											<td>
												<span class='tanggal'><?=is_reverse_date($row->tanggal);?></span> 
											</td>
											<td>
												<span class="keterangan"><?=$row->keterangan;?></span> 
											</td>
											<td>
												<b style='background: #0ff;padding:0px 3px'><?=$row->urutan_giro;?></b>
											</td>
											<td>
												<?if ($row->pelunasan_id != '') {?>
													<a target="_blank" href="<?=base_url().is_setting_link('finance/piutang_payment_form');?>?id=<?=$row->pelunasan_id?>" class="btn btn-xs <?=($row->sisa_piutang > 0 ? 'yellow-gold' : 'green' )?>" ><i class='fa fa-search'></i> <?=($row->sisa_piutang > 0 ? 'Kontra Bon' : 'Lunas' )?></a>
												<?}else{?>
													Belum Lunas
												<?}?>
											</td>
											<td>
												<span class='id' hidden ><?=$row->id;?></span>
												<span class='pembayaran_piutang_nilai_id' hidden ><?=$row->pembayaran_piutang_nilai_id;?></span>
												<?if ($row->pelunasan_id == '') {?>
													<a href="#portlet-config" data-toggle="modal" class="btn btn-xs green btn-edit"><i class='fa fa-edit'></i></a>
													<button class="btn btn-xs red btn-remove"><i class='fa fa-times'></i></button>
												<?}?>
												<a target="_blank" href="<?=base_url().is_setting_link('finance/piutang_payment_form').'?id='.$row->pembayaran_piutang_id;?>" class="btn btn-xs blue"><i class='fa fa-search'></i> Sumber</a>
											</td>
										</tr>
									<?}?>
								<?}else{?>
								<td colspan='12' class='text-center'><i>no data</i><?=count($giro_tolakan_list)?></td>
								<?}?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>			
</div>

<script src="<?php echo base_url('assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/datatables/media/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/global/plugins/bootbox/bootbox.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets_noondev/js/table-advanced.js'); ?>"></script>

<script>
jQuery(document).ready(function() {

	// dataTableTrue();

	// oTable = $('#general_table').DataTable();
	// oTable.state.clear();
	// oTable.destroy();

	//initial
	$('#customer-id-select, #giro-list').select2();
	get_giro_list("");	

	$(".btn-form-add").click(function(){
		$("#form_add_data [name=giro_tolakan_id]").val('');
		$("#form_add_data [name=keterangan]").val('');
		$("#customer-id-select").val("").change();
	});

	$("#customer-id-select").change(function(){
		get_giro_list($(this).val());
	});

	$("#giro-list").change(function(){
		var id = $(this).val();
		if(id != null && id != ''){
			var customer_id = $("#giro-list-copy [value='"+id+"']").text();
			var customer_id_select = $("#customer-id-select").val();
			$("#giro-list-copy").val(id);
			if (customer_id != customer_id_select) {
				$("#customer-id-select").val(customer_id).change();
			};
		};
	});

	//===============================================================================================
	$('.btn-save').click(function(){
		if ($('#form_add_data [name=tanggal]').val() != '' && $('#form_add_data [name=pembayaran_piutang_nilai_id]').val() != '' && $('#form_add_data [name=keterangan]').val() != '' ) {
			btn_disabled_load($(".btn-save"));
			$('#form_add_data').submit();
		}else{
			alert('Tanggal, No Giro, Keterangan harus diisi');
		}
	});

	//===============================================================================================
	$("#general_table").on("click", ".btn-edit", function(){
		var ini = $(this).closest('tr');
		var form = $("#form_add_data");
		var id = ini.find(".id").html();
		var pembayaran_piutang_nilai_id = ini.find(".pembayaran_piutang_nilai_id").html();
		var tanggal = ini.find('.tanggal').html();
		var keterangan = ini.find('.keterangan').html();
		form.find("[name=giro_tolakan_id]").val(id);
		form.find("[name=pembayaran_piutang_nilai_id]").val(pembayaran_piutang_nilai_id);
		form.find("#giro-list").change();
		form.find("[name=tanggal]").val(tanggal);
		form.find("[name=keterangan]").val(keterangan);
	});

	$("#general_table").on("click", ".btn-remove", function(){
		var ini = $(this).closest('tr');
		var id = ini.find(".id").html();
		bootbox.confirm("Yakin hapus dari daftar giro tolakan ? ", function(respond){
			if (respond) {
				var data_st = {};
				data_st['id'] = id;
				var url = "finance/giro_tolakan_remove";
				ajax_data_sync(url,data_st).done(function(data_respond  /*,textStatus, jqXHR*/ ){
					if (data_respond == 'OK') {
						ini.remove();
					};
				});
				
			};
		});
	});

	//===============================================================================================

});

function get_giro_list(customer_id){
	var customer_id_select = $("#giro-list-copy option:selected").text();
	var giro_list_selected = $("#giro-list").val();
	// alert(giro_list_selected);
	// alert(customer_id +'!='+ customer_id_select);
	$('#giro-list').empty().trigger('change');	
	var options = [];
	var data_st = {};
	var options_copy = "";
	data_st['customer_id'] = customer_id;
	var url = "finance/get_giro_list";
	ajax_data_sync(url,data_st).done(function(data_respond  /*,textStatus, jqXHR*/ ){
		if(JSON.parse(data_respond).length > 0){
			if (customer_id == '') {
				// alert(giro_list_selected);
				var newOption = new Option('Pilih', '', false, false);
				$('#giro-list').append(newOption).trigger('change');
			}else if(customer_id != customer_id_select){
				// alert(giro_list_selected);
				var newOption = new Option('Pilih', '', false, false);
				$('#giro-list').append(newOption).trigger('change');
			}

			$.each(JSON.parse(data_respond),function(k,v){
				options['id']= v.id;
	            options['text']= v.urutan_giro + '| '+v.no_giro;
	            options_copy += `<option value="${v.id}">${v.customer_id}</option>`;
		        // });
		        var newOption = new Option(options['text'], options['id'], false, false);
		        // console.log(newOption);
				$('#giro-list').append(newOption);	
		        
	        });
	        $("#giro-list-copy").empty().append(options_copy);
		}else{
		    var newOption = new Option('No data', '', false, false);
			$('#giro-list').append(newOption).trigger('change');	
		}

		if(customer_id == customer_id_select  && giro_list_selected != '' && customer_id_select != '' ){
			// alert(giro_list_selected);
			$("#giro-list").val(giro_list_selected).change();
			// alert($('#giro-list').val() + giro_list_selected);
			// alert(giro_list_selected);
		}
	});
}


</script>
