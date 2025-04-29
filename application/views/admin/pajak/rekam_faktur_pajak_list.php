<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css'); ?>"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css'); ?>"/>
<?php echo link_tag('assets/global/plugins/bootstrap-datepicker/css/datepicker3.css'); ?>

<div class="page-content">
	<div class='container'>

		<div class="modal fade" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body">
						<form action="<?=base_url('pajak/rekam_faktur_pajak_insert')?>" class="form-horizontal" id="form_add_data" method="post">
							<h3 class='block'> Nomer Baru</h3>

			               	<div class="form-group">
			                    <label class="control-label col-md-4">Tanggal Start<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<input class='form-control date-picker' name='tanggal_start' value="<?=is_reverse_date($last_date);?>" >
			                    </div>
			                </div>

			                <div class="form-group">
			                    <label class="control-label col-md-4">Tanggal Akhir<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<input class='form-control date-picker' name='tanggal_end' value="<?=date('d/m/Y');?>" >
			                    </div>
			                </div>

			                <div class="form-group">
			                    <label class="control-label col-md-4">Pembuat<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<input class='form-control' name='username' value='<?=is_username();?>'>
			                    </div>
			                </div>

						</form>
					</div>

					<div class="modal-footer">
						<button type="button" class="btn btn-active blue btn-save">Save</button>
						<button type="button" class="btn default" data-dismiss="modal">Close</button>
					</div>
				</div>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
		</div>

		<div class="modal fade" id="portlet-config-coretax" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body">
						<form action="<?=base_url('pajak/rekam_faktur_pajak_coretax_insert')?>" class="form-horizontal" id="form_add_data_coretax" method="post">
							<h3 class='block'> Data Baru <b>CORETAX</b></h3>

			               	<div class="form-group">
			                    <label class="control-label col-md-4">Tanggal Start<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<input class='form-control date-picker' name='tanggal_start' value="<?=is_reverse_date($last_date);?>" >
			                    </div>
			                </div>

			                <div class="form-group">
			                    <label class="control-label col-md-4">Tanggal Akhir<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<input class='form-control date-picker' name='tanggal_end' value="<?=date('d/m/Y');?>" >
			                    </div>
			                </div>

			                <div class="form-group">
			                    <label class="control-label col-md-4">Pembuat<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<input class='form-control' name='username' value='<?=is_username();?>'>
			                    </div>
			                </div>

						</form>
					</div>

					<div class="modal-footer">
						<button type="button" class="btn btn-active blue btn-save-coretax">Save</button>
						<button type="button" class="btn default" data-dismiss="modal">Close</button>
					</div>
				</div>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
		</div>

		<div class="modal fade" id="portlet-config-gunggung" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body">
						<form action="<?=base_url('pajak/rekam_faktur_pajak_gunggung_insert')?>" class="form-horizontal" id="form_add_data_gunggung" method="post">
							<h3 class='block'> Data Baru <b>Gunggung</b></h3>

			               	<div class="form-group">
			                    <label class="control-label col-md-4">Tanggal Start<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<input class='form-control date-picker' name='tanggal_start' value="<?=is_reverse_date($last_date);?>" >
			                    </div>
			                </div>

			                <div class="form-group">
			                    <label class="control-label col-md-4">Tanggal Akhir<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<input class='form-control date-picker' name='tanggal_end' value="<?=date('d/m/Y');?>" >
			                    </div>
			                </div>

			                <div class="form-group">
			                    <label class="control-label col-md-4">Pembuat<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<input class='form-control' name='username' value='<?=is_username();?>'>
			                    </div>
			                </div>

						</form>
					</div>

					<div class="modal-footer">
						<button type="button" class="btn btn-active blue btn-save-gunggung">Save</button>
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
						<div class="caption caption-md">
							<i class="icon-bar-chart theme-font hide"></i>
							<span class="caption-subject theme-font bold uppercase"><?=$breadcrumb_small;?></span>
						</div>
						<div class="actions">
							<?if (is_posisi_id() == 1) {?>
								<a href="#portlet-config" data-toggle='modal' class="btn btn-default btn-sm btn-form-add">
								<i class="fa fa-plus"></i> Data Baru </a>
								<?}?>
								
								<a href="#portlet-config-gunggung" data-toggle='modal' class="btn btn-warning btn-sm btn-form-add-coretax">
								<i class="fa fa-plus"></i> Data Baru Gunggung </a>
								
								<a href="#portlet-config-coretax" data-toggle='modal' class="btn btn-primary btn-sm btn-form-add-coretax">
								<i class="fa fa-plus"></i> Data Baru Coretax </a>
								
						</div>
					</div>
					<div class="portlet-body">
						
						<table class="table table-hover table-striped table-bordered" id="general_table">
							<thead>
								<tr>
									<th scope="col">
										Created
									</th>
									<th scope="col">
										No Faktur Pajak
									</th>
									<th scope="col">
										Tanggal Faktur
									</th>
									<th scope="col">
										Nilai
									</th>
									<th scope="col">
										PPN
									</th>
									<th scope="col">
										NO
									</th>
									<th>
										Registered
									</th>
									<th>
										Unregistered
									</th>
									<!-- <th scope="col">
										Faktur
									</th>
									<th scope="col">
										Batal
									</th> -->
									<th scope="col">
										Actions
									</th>
								</tr>
							</thead>
							<tbody>
								<?foreach ($this->toko_list_aktif as $row) {
									$nama_toko = $row->nama;
									$pre_po = $row->pre_po;
								}

								foreach($submitted_count as $row){
									$jml_submitted[$row->rekam_faktur_pajak_id] = $row->jml_submitted;
									$jml_unsubmitted[$row->rekam_faktur_pajak_id] = $row->jml_unsubmitted;
								}
								?>
								<?foreach ($fp_list as $row) { ?>
									<tr>
										<td><?=$row->created_at?></td>
										<td>
											<?if($row->is_gunggung == 0){
												echo $row->no_fp_awal."-".$row->no_fp_akhir;
											}else{
												echo "gunggung";
											}?>
										</td>
										<td>
											<b><?=is_reverse_date($row->tanggal_awal);?></b> s/d <b><?=is_reverse_date($row->tanggal_akhir);?></b>
										</td>
										<td>
											<?=number_format($row->nilai,'0',',','.');?>
										</td>
										<td>
											<?=number_format($row->nilai_ppn,'0',',','.');?>
										</td>
										<td>
											<?=($row->no_surat != '' && $row->no_surat != 0 ? $pre_po.'-'.date("Y", strtotime($row->created_at)).'/01/'.str_pad($row->no_surat, 3,'0', STR_PAD_LEFT) : '');?>
										</td>
										<!-- <td><?=$row->jml_faktur;?></td>
										<td><?=$row->jml_faktur_batal;?></td> -->
										<td>
											<?if($row->is_gunggung == 0){
												echo (isset($jml_submitted[$row->id])  ? $jml_submitted[$row->id] : '');
											}else{
												echo "-";
											};?>
										</td>
										<td>
											<?if($row->is_gunggung == 0){
												echo (isset($jml_submitted[$row->id])  ? $jml_unsubmitted[$row->id] : '');
											}else{
												echo "-";
											};;?>
										</td>
										<td>
											<span class="id" hidden><?=$row->id;?></span>
											<a href="<?=base_url().is_setting_link('pajak/rekam_faktur_pajak_detail');?>?id=<?=$row->id?>" class='btn btn-xs green' target='_blank'><i class='fa fa-search'></i></a>
											<?if ($row->status == 0) {
												if($row->is_gunggung == 0){?>
													<a href="<?=base_url().is_setting_link('pajak/rekam_faktur_email_list');?>?id=<?=$row->id?>" class='btn btn-xs blue' target='_blank'><i class='fa fa-envelope'></i></a>
												<?}?>
											<?}else{?>
												<button class='btn btn-xs red btn-remove' ><i class='fa fa-times'></i></a>
											<?}?>

										</td>
									</tr>
								<?}?>
							</tbody>
						</table>
						<?if (is_posisi_id() == 1) {?>
							<button class="btn btn-lg default" onclick="updateData();">UPDATE</button>
						<?}?>
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

<script>

	const fpList = <?=json_encode($fp_list);?>;
jQuery(document).ready(function() {

	// dataTableTrue();

	// TableAdvanced.init();

	// oTable = $('#general_table').DataTable();
	// oTable.state.clear();
	// oTable.destroy();

	
	$("#general_table").DataTable({
		"ordering":false,
		// "bFilter":false
	});

//==================================================================================

	$('.btn-test').click(function(){
		alert(fp_get_end($('#test1').html()));
	});


	$('#general_table').on('change','[name=tanggal_setor]',function(){
		var ini = $(this).closest('tr');
		var data = {};
		data['pembayaran_piutang_id'] = ini.find('.pembayaran_piutang_id').html();
		data['tanggal_setor'] = $(this).val();
		// var this = $(this);
		var url = 'finance/update_setor_fp';
		ajax_data_sync(url,data).done(function(data_respond  /*,textStatus, jqXHR*/ ){
			if (data_respond == 'OK') {
				
			}else{
				$(this).val('');
				alert("error mohon refresh dengan menekan F5");
			}
   		});
	});

//=======================================fp no===========================================

	$("[name=no_fp_awal], [name=no_fp_akhir] ").change(function(){
		var form = '#'+$(this).closest('form').attr('id');
		form = $(form);
		update_fp(form);
	});

	$('.btn-form-add').click(function(){
		$('#form_add_data [name=no_fp_awal]').val('');
		// $('#form_add_data [name=jml_fp]').val('');
		$('#form_add_data [name=no_fp_akhir]').val('');
	});

	$("#general_table").on("click", ".btn-remove", function(){
		var ini = $(this).closest("tr");
		bootbox.confirm("Yakin menghapus semua data di rekam faktur ini ?", function(respond){
			if (respond) {
				var data = {};
				data['id'] = ini.find('.id').html();
				var url = 'pajak/rekam_faktur_pajak_remove';
				ajax_data_sync(url,data).done(function(data_respond  /*,textStatus, jqXHR*/ ){
					if (data_respond == 'OK') {
						window.location.reload();
					}else{
						alert("error mohon refresh dengan menekan F5");
					}
		   		});
			};
		});
	});

	$('.btn-save-coretax').click(function(){
		var tgl_awal = $("#form_add_data_coretax [name=tanggal_start]").val();
		var tgl_akhir = $('#form_add_data_coretax [name=tanggal_end').val();
		var tahun_awal = tgl_awal.substring(6,10);
		var tahun_akhir = tgl_akhir.substring(6,10);
		if (tahun_awal != tahun_akhir) {
			alert("Tidak bisa beda tahun");
		}else{
			$('#form_add_data_coretax').submit();
		}
	});


	$('.btn-save-gunggung').click(function(){
		var tgl_awal = $("#form_add_data_gunggung [name=tanggal_start]").val();
		var tgl_akhir = $('#form_add_data_gunggung [name=tanggal_end').val();
		var tahun_awal = tgl_awal.substring(6,10);
		var tahun_akhir = tgl_akhir.substring(6,10);
		if (tahun_awal != tahun_akhir) {
			alert("Tidak bisa beda tahun");
		}else{
			$('#form_add_data_gunggung').submit();
		}

	});


});

async function updateData(){
	const dialog = bootbox.dialog({
        message: `<p class="text-center mb-0"><i class="fa fa-spin fa-cog"></i> update data.</p>`,
        closeButton: false
    });

	// const nData = [];
	// fpList.forEach(list => {
	// 	nData.push({
	// 		'id':list.id,
	// 		'tanggal_awal':list.tanggal_start,
	// 		'tanggal_akhir':list.tanggal_end,
	// 		'nilai':list.g_total_raw,
	// 		'nilai_ppn' : list.g_total_ppn,
	// 		'jumlah_trx':list.jml_faktur
	// 	});
	// });

	const nData = [];
	fpList.forEach(list => {
		nData.push({
			'id':list.id,
			'no_fp_awal':list.no_fp_awal,
			'no_fp_akhir':list.no_fp_akhir
		});
	});

	
	console.log(nData);
	const res = await fetch(baseurl+"pajak/updateFakturHeader", {
        method:"POST",
        headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `data=${JSON.stringify(nData)}`
    });
    
    res.json().then(data=>{
		if (data == "OK") {
			dialog.find('.bootbox-body').html(`<p class="text-center mb-0">Update Sukses</p>`);
			setTimeout(() => {
				dialog.modal('hide');
			}, 1500);
		}
	});;
}


</script>
