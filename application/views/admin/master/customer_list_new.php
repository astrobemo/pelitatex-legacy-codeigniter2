<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css'); ?>"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css'); ?>"/>
<?=link_tag('assets/global/plugins/dropzone/css/dropzone.css'); ?>
<style type="text/css">
.npwp{
	background: yellow;
}

.nik{
	background: lightblue;
}
</style>

<div class="page-content">
	<div class='container'>

		<div class="modal fade bs-modal-lg" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-body">
						<div class="portlet-body form">

							
									<div class="tabbable tabbable-custom">
										<ul class="nav nav-tabs">
											<li class="active">
												<a href="#tab_1" data-toggle="tab">Data Diri</a>
											</li>
											<li>
												<a href="#tab_2" data-toggle="tab">Alamat</a>
											</li>
											<li>
												<a href="#tab_3" data-toggle="tab">Lain2</a>
											</li>
										</ul>

										<form action="<?=base_url('master/customer_list_insert')?>" class="form-horizontal" id="form_add_data" method="post">
								<div class="form-body">

										<div class="tab-content">
											<div class="tab-pane active" id="tab_1">
												<h3 class="form-section">Data Diri</h3>
													<div class='row'>
														<div class='col-md-12 pict-id-container' hidden>
															<input type="file" id="changepict"  onchange="previewFile(this)" class="hidden"/>
															<!-- <label class='btn btn-xs default' for="changepict">Select file</label> -->
									                    	<button style='display:none' type='button' onclick="removePictAdd()" class='btn btn-xs red btn-pict-remove'><i class='fa fa-times'></i> remove</button>
									                    	<br>
															<img height="200" id='customer-id-img' alt="Image preview..." >
															<textarea hidden <?//=(is_posisi_id() != 1 ? 'hidden' : '');?> name='pict_data' id='pict-data'></textarea>
														</div>
													</div>
													<div class="row">
														<div class='col-md-6'>
															<div class="form-group">
											                    <label class="control-label col-md-2">Tipe
											                    </label>
											                    <div class="col-md-10">
											                    	<div class="radio-list">
																		<label class="radio-inline">
																		<input type="radio" name="tipe_company" value="" checked > Pribadi </label>
																		<label class="radio-inline">
																		<input type="radio" name="tipe_company" value="CV" > CV </label>
																		<label class="radio-inline">
																		<input type="radio" name="tipe_company" value="PT" > PT </label>
																	</div>
											                    </div>
											                </div>
											                <div class="form-group">
											                    <label class="control-label col-md-2">Nama
											                    </label>
											                    <div class="col-md-10">
											                    	<input type="text" class="form-control" name="nama"/>
											                    </div>
											                </div>
											                <div class="form-group">
											                    <label class="control-label col-md-2">Alias
											                    </label>
											                    <div class="col-md-10">
											                    	<input type="text" class="form-control" name="alias" />
											                    </div>				                    
											                </div>
											                <div class="form-group">
											                    <label class="control-label col-md-2">Email
											                    </label>
											                    <div class="col-md-10">
											                    	<input type="text" class="form-control" name="email"/>
											                    </div>				                    
											                </div>
														</div>
														<div class='col-md-6'>
															<div class="form-group">
											                    <label class="control-label col-md-3">NPWP
											                    </label>
											                    <div class="col-md-3">
											                    	<input type="text" class="form-control mask-npwp" name="npwp" placeholder="__.___.___._-__.___" />
											                    </div>				                    
											                </div>
											                <div class="form-group">
											                    <label class="control-label col-md-3">NIK
											                    </label>
											                    <div class="col-md-3">
											                    	<input type="text" class="form-control" name="nik" placeholder="16 digit" />
											                    </div>				                    
											                </div>
											                <div class="form-group">
											                    <label class="control-label col-md-3">Telepon
											                    </label>
											                    <div class="col-md-9">
											                    	<input type="text" class="form-control" name="telepon1"/>
											                    </div>				                    
											                </div>
														</div>
													</div>
											</div>

											<div class="tab-pane" id="tab_2">
												<h3 class="form-section">Alamat</h3>
													<div class="row">
										                <div class='col-md-6'>
											                <div class="form-group">
											                    <label class="control-label col-md-3">Jalan
											                    </label>
											                    <div class="col-md-9">
											                    	<textarea class="form-control" name="alamat" rows='4' ></textarea>
											                    </div>				                    
											                </div>
										                </div>
										                <div class="col-md-6">
											                <div class="col-md-6">
											                	<div class="form-group">
												                    <label class="control-label col-md-6">Blok
												                    </label>
												                    <div class="col-md-6">
												                    	<input type="text" class="form-control" name="blok"/>
												                    </div>	
												                </div>
											                </div>
											                <div class="col-md-6">
												                <div class="form-group">
												                    <label class="control-label col-md-6">RT
												                    </label>
												                    <div class="col-md-6">
												                    	<input type="text" class="form-control" name="rt"/>
												                    </div>				                    
												                </div>
											                </div>
											                <div class="col-md-6">
											                	<div class="form-group">
												                    <label class="control-label col-md-6">NO
												                    </label>
												                    <div class="col-md-6">
												                    	<input type="text" class="form-control" name="no"/>
												                    </div>				                    
												                </div>
											                </div>
											                <div class="col-md-6">
												                <div class="form-group">
												                    <label class="control-label col-md-6">RW
												                    </label>
												                    <div class="col-md-6">
												                    	<input type="text" class="form-control" name="rw"/>
												                    </div>
												                </div>	 
											                </div>
			                   
										                </div>
													</div>

													<div class="row">
										                <div class='col-md-6'>
										                	<div class="form-group">
											                    <label class="control-label col-md-3">Kelurahan
											                    </label>
											                    <div class="col-md-9">
											                    	<input type="text" class="form-control" name="kelurahan"/>
											                    </div>				                    
											                </div>
										                </div>
										                <div class='col-md-6'>
										                	<div class="form-group">
											                    <label class="control-label col-md-3">Kecamatan
											                    </label>
											                    <div class="col-md-9">
											                    	<input type="text" class="form-control" name="kecamatan"/>
											                    </div>				                    
											                </div>
										                </div>
													</div>

													<div class="row">
										                <div class='col-md-6'>
										                	<div class="form-group">
											                    <label class="control-label col-md-3">Kab/Kota
											                    </label>
											                    <div class="col-md-9">
											                    	<input type="text" class="form-control" name="kota"/>
											                    </div>				                    
											                </div>
										                </div>
										                <div class='col-md-6'>
											                <div class="form-group">
											                    <label class="control-label col-md-3">Provinsi
											                    </label>
											                    <div class="col-md-9">
											                    	<input type="text" class="form-control" name="provinsi"/>
											                    </div>				                    
											                </div>
										                </div>
													</div>

													<div class="row">
										                <div class='col-md-6'>
											                <div class="form-group">
											                    <label class="control-label col-md-3">Kode Pos
											                    </label>
											                    <div class="col-md-9">
											                    	<input type="text" class="form-control" name="kode_pos"/>
											                    </div>				                    
											                </div>
										                </div>
													</div>						                
											</div>
											<div class="tab-pane" id="tab_3">
												<h3 class="form-section">Data Lain</h3>
													<div class="row">
														<div class='col-md-6'>
															<div class="form-group">
											                    <label class="control-label col-md-3">Tipe
											                    </label>
											                    <div class="col-md-9">
											                    	<div class="radio-list">
																		<label class="radio-inline">
																			<input name='customer_type_id' type='radio' value='1' checked>CASH</label>
																		<label class="radio-inline">
																			<input name='customer_type_id' type='radio' value='2' >KREDIT</label>
																	</div>
											                    </div>				                    
											                </div>
														</div>
														<div class='col-md-6'>
											                <div class="form-group">
											                    <label class="control-label col-md-3">Tempo
											                    </label>
											                    <div class="col-md-9">
											                    	<input type="text" readonly class="form-control kredit-field" style='width:120px; display:inline' name="tempo_kredit"/> <b>hari</b> 
											                    </div>				                    
											                </div>
														</div>
													</div>
													<div class="row">
														<div class='col-md-6'>
											                <div class="form-group">
											                    <label class="control-label col-md-3">Reminder
											                    </label>
											                    <div class="col-md-9">
											                    	<input type="text" readonly class="form-control kredit-field" name="warning_kredit" style='width:120px; display:inline;' /> <b>hari</b>
											                    	<small class='limit-text'></small>
											                    </div>
											                </div>
														</div>
														<div class='col-md-6'>
											                <div class="form-group">
											                    <label class="control-label col-md-3">Limit Belanja
											                    </label>
											                    <div class="col-md-9">
											                    	<input type="text" readonly class="form-control amount-number kredit-field" name="limit_amount" style='width:120px; display:inline;' />
											                    	<small class='limit-text'></small>
											                    </div>
											                </div>
														</div>
													</div>
													<div class="row">
														<div class='col-md-6'>
											                <div class="form-group">
											                    <label class="control-label col-md-3">Tipe Warning
											                    </label>
											                    <div class="col-md-9">
											                    	<div class="radio-list">
																		<label class="radio-inline">
																			<input name='limit_warning_type' class='kredit-field' disabled type='radio' value='1' checked>Persen</label>
																		<label class="radio-inline">
																			<input name='limit_warning_type' class='kredit-field' disabled type='radio' value='2' >Rupiah</label>
																	</div>
											                    </div>
											                </div>
														</div>

														<div class='col-md-6'>
															<div class="form-group">
											                    <label class="control-label col-md-3">Warning Ketika Mencapai
											                    </label>
											                    <div class="col-md-9">
											                    	<span class='rp-field' style="font-weight:bold" hidden>Rp.</span> <input type="text" readonly class="form-control kredit-field" style='width:120px; display:inline;' name="limit_warning_amount"/> <span class='percent-field'  style="font-weight:bold">%</span>
											                    	<small class='limit-warning-text'></small>
											                    </div>		                    
											                </div>
														</div>
													</div>
											</div>

										</div>
									</div>
							</form>
								</div>
						</div>
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

		<div class="modal fade bs-modal-full" id="portlet-config-edit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-full">
				<div class="modal-content">
					<div class="modal-body">
						<form action="<?=base_url('master/customer_list_update')?>" class="form-horizontal" id="form_edit_data" method="post">
							<h3 class='block'> Edit </h3>
							<hr/>
							<table style="width:90%">
								<tr>
									<td>
										<div class="form-group">
						                    <label class="control-label col-md-4">FOTO ID
						                    </label>
						                    <div class="col-md-8 text-center" style='overflow:hidden'>
						                    	<input type="file" id="changepict-edit"  onchange="previewFileEdit(this)" class="hidden"/>
						                    	<button type='button' onclick="removePictEdit()" class='btn btn-xs red btn-pict-remove-edit'><i class='fa fa-times'></i> remove</button>
						                    	<br>
												<img src="..." height="200" id='customer-id-img-edit' alt="Image preview..." >
						                    	<input name="img_link" hidden />
												<textarea <?=(is_posisi_id() != 1 ? 'hidden' : '');?> name='pict_data' id='pict-data-edit'></textarea>
						                    </div>				                    
						                </div>
									</td>
									<td>
										<div class="form-group">
						                    <label class="control-label col-md-4">Nama
						                    </label>
						                    <div class="col-md-8">
						                    	<input hidden name="customer_id"/>
						                    	<input type="text" class="form-control input1" name="nama"/>
						                    </div>				                    
						                </div>

						                <div class="form-group" hidden>
						                    <label class="control-label col-md-4">Alias
						                    </label>
						                    <div class="col-md-8">
						                    	<input type="text" class="form-control" name="alias"/>
						                    </div>				                    
						                </div>

						                <div class="form-group">
						                    <label class="control-label col-md-4">Alamat
						                    </label>
						                    <div class="col-md-8">
						                    	<textarea class="form-control" name="alamat" rows='4' ></textarea>
						                    </div>				                    
						                </div>

						                <div class="form-group">
						                    <label class="control-label col-md-4">Kab/Kota
						                    </label>
						                    <div class="col-md-8">
						                    	<input type="text" class="form-control" name="kota"/>
						                    </div>				                    
						                </div>

						                <div class="form-group">
						                    <label class="control-label col-md-4">Provinsi
						                    </label>
						                    <div class="col-md-8">
						                    	<input type="text" class="form-control" name="provinsi"/>
						                    </div>				                    
						                </div>

						                <div class="form-group">
						                    <label class="control-label col-md-4">Kode Pos
						                    </label>
						                    <div class="col-md-8">
						                    	<input type="text" class="form-control" name="kode_pos"/>
						                    </div>				                    
						                </div>

						                <div class="form-group">
						                    <label class="control-label col-md-4">Email
						                    </label>
						                    <div class="col-md-8">
						                    	<input type="text" class="form-control" name="email"/>
						                    </div>				                    
						                </div>

						                <div class="form-group">
						                    <label class="control-label col-md-4">Telepon
						                    </label>
						                    <div class="col-md-8">
						                    	<input type="text" class="form-control" name="telepon1"/>
						                    </div>				                    
						                </div>

						                <div class="form-group" hidden>
						                    <label class="control-label col-md-4">Telepon2
						                    </label>
						                    <div class="col-md-8">
						                    	<input type="text" class="form-control" name="telepon2"/>
						                    </div>				                    
						                </div>
									</td>
									<td>
										
										<div class="form-group">
						                    <label class="control-label col-md-4">NPWP
						                    </label>
						                    <div class="col-md-8">
						                    	<input type="text" class="form-control mask-npwp-edit" name="npwp"/>
						                    </div>				                    
						                </div>

						                <div class="form-group">
						                    <label class="control-label col-md-4">NIK
						                    </label>
						                    <div class="col-md-8">
						                    	<input type="text" class="form-control" name="nik"/>
						                    </div>				                    
						                </div>
										
						                <div class="form-group">
						                    <label class="control-label col-md-4">Tipe
						                    </label>
						                    <div class="col-md-8">
						                    	<div class="radio-list">
													<label class="radio-inline">
														<input name='customer_type_id' type='radio' value='1' checked>CASH</label>
													<label class="radio-inline">
														<input name='customer_type_id' type='radio' value='2' >KREDIT</label>
												</div>
						                    </div>				                    
						                </div>

						                <div class="form-group">
						                    <label class="control-label col-md-4">Tempo
						                    </label>
						                    <div class="col-md-8">
						                    	<input type="text" readonly class="form-control kredit-field" style='width:120px; display:inline' name="tempo_kredit"/> <b>hari</b> 
						                    </div>				                    
						                </div>

						                <div class="form-group">
						                    <label class="control-label col-md-4">Reminder
						                    </label>
						                    <div class="col-md-8">
						                    	<input type="text" readonly class="form-control kredit-field" name="warning_kredit" style='width:120px; display:inline;' /> <b>hari</b>
						                    	<small class='limit-text'></small>
						                    </div>
						                </div>

						                <div class="form-group">
						                    <label class="control-label col-md-4">Limit Belanja
						                    </label>
						                    <div class="col-md-8">
						                    	<input type="text" readonly class="form-control amount-number kredit-field" name="limit_amount" style='width:120px; display:inline;' />
						                    	<small class='limit-text'></small>
						                    </div>
						                </div>

						                <div class="form-group">
						                    <label class="control-label col-md-4">Tipe Warning
						                    </label>
						                    <div class="col-md-8">
						                    	<div class="radio-list">
													<label class="radio-inline">
														<input name='limit_warning_type' class='kredit-field' disabled type='radio' value='1' checked>Persen</label>
													<label class="radio-inline">
														<input name='limit_warning_type' class='kredit-field' disabled type='radio' value='2' >Rupiah</label>
												</div>
						                    </div>
						                </div>

										<div class="form-group">
						                    <label class="control-label col-md-4">Warning Ketika Mencapai
						                    </label>
						                    <div class="col-md-8">
						                    	<span class='rp-field' style="font-weight:bold" hidden>Rp.</span> <input type="text" readonly class="form-control kredit-field" style='width:120px; display:inline;' name="limit_warning_amount"/> <span class='percent-field'  style="font-weight:bold">%</span>
						                    	<small class='limit-warning-text'></small>
						                    </div>		                    
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
				</div>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
		</div>

		<div class="modal fade" id="portlet-config-picture" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
						<h4 class="modal-title">Select Picture</h4>
					</div>
					<div class="modal-body">
						<div class="tabbable tabs-left">
						</div>
						<ul class="nav nav-tabs">
							<li id="button_tab_modal_1" class="active">
								<a href="#tab_modal_1" data-toggle="tab" aria-expanded="false">
								Upload </a>
							</li>
							<li id="button_tab_modal_2" class="">
								<a href="#tab_modal_2" data-toggle="tab" aria-expanded="false">
								Gallery </a>
							</li>
						</ul>
						<div class="tab-content">
							<div class="tab-pane active in" id="tab_modal_1">
								<form action="<?=base_url('master/upload_dropzone_picture'); ?> " class="dropzone" id="my-dropzone">
								</form>
							</div>
							<div class="tab-pane" id="tab_modal_2">
								<div class="row mix-grid">
									<?foreach ($map as $item) { ?>
										<div class="col-md-4 col-sm-4">
											<div class="mix-inner" style='height:150px;cursor:pointer;'>
												<div style='padding:5px;overflow:hidden;border:1px solid #ddd;max-height:120px;' data-dismiss="modal" >
													<img class="img-responsive" src="<? echo base_url($dir).'/'.$item; ?>" alt="" style=' margin:auto; height:100px;'>
												</div>
												<div class="mix-details" style='text-align:center; padding-bottom:20px;'>
													<?=$item; ?>
												</div>
											</div>
										</div>
									<? } ?>
										
								</div>
							</div>
						</div>
								
					</div>
					<div class="modal-footer">
						<!-- <button type="button" class="btn blue">Save changes</button> -->
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
							<select class='btn btn-sm btn-default' name='status_aktif_select' id="status_aktif_select">
								<option value="1" selected>Aktif</option>
								<option value="0">Tidak Aktif</option>
							</select>
							<a href="#portlet-config" data-toggle='modal' class="btn btn-default btn-sm btn-form-add">
							<i class="fa fa-plus"></i> Tambah </a>
						</div>
					</div>
					<div class="portlet-body">
						<b>FILTER : </b> 
						<select id='customer_type_id_filter'>
							<option value='0'>SEMUA</option>
							<option value='2'>KREDIT</option>
							<option value='1'>NON-KREDIT</option>
						</select>
						<hr/>
						<div id='main-table'>
							<table class="table table-striped table-bordered table-hover" id="general_table">
								<thead>
									<tr>
										<th scope="col" class='status_column'>
											Status Aktif
										</th>
										<th scope="col">
											Nama
										</th>
										<th scope="col">
											Alias
										</th>
										<th scope="col">
											Tipe
										</th>
										<th scope="col">
											Alamat
										</th>
										<th scope="col">
											NPWP/NIK
										</th>
										<th scope="col">
											<i class='fa fa-phone-square'></i> 1
										</th>
										<th scope="col" class='status_column'>
											<i class='fa fa-phone-square'></i>2
										</th>
										<th scope="col">
											Tempo
										</th>
										<th scope="col">
											Limit Kredit
										</th>
										<th scope="col" style="min-width:100px !important">
											Actions
										</th>
									</tr>
								</thead>
								<tbody>
									<?/*foreach ($customer_list as $row) { ?>
										<tr>
											<td>
												<span class='id' hidden="hidden"><?=$row->id;?></span>
												<span class='nama'><?=$row->nama;?></span>
											</td>
											<td>
												<span class='alamat'><?=$row->alamat;?></span> 
											</td>
											<td>
												<span class='kota'><?=$row->kota;?></span> 
											</td>
											<td>
												<span class='telepon1'><?=$row->telepon1;?></span>
											</td>
											<td>
												<span class='telepon2'><?=$row->telepon2;?></span>
											</td>
											<td>
												<span hidden class='kode_pos'><?=$row->kode_pos;?></span> 
												<span hidden class='email'><?=$row->email;?></span> 
												<span hidden class='npwp'><?=$row->npwp;?></span>
												<a href='#portlet-config-edit' data-toggle='modal' class="btn-xs btn green btn-edit"><i class="fa fa-edit"></i> </a>
												<a class="btn-xs btn red btn-remove"><i class="fa fa-times"></i> </a>
											</td>
										</tr>
									<? } */?>

								</tbody>
							</table>
						</div>

						<div id='extra-table'>
							<table class="table table-striped table-bordered table-hover" id="extra_table">
								<thead>
									<tr>
										<th scope="col" class='status_column'>
											Status Aktif
										</th>
										<th scope="col">
											Nama
										</th>
										
										<th scope="col">
											Customer List
										</th>
										<th scope="col" style="min-width:150px !important">
											Actions
										</th>
									</tr>
								</thead>
								<tbody>
									<?/*foreach ($customer_list as $row) { ?>
										<tr>
											<td>
												<span class='id' hidden="hidden"><?=$row->id;?></span>
												<span class='nama'><?=$row->nama;?></span>
											</td>
											<td>
												<span class='alamat'><?=$row->alamat;?></span> 
											</td>
											<td>
												<span class='kota'><?=$row->kota;?></span> 
											</td>
											<td>
												<span class='telepon1'><?=$row->telepon1;?></span>
											</td>
											<td>
												<span class='telepon2'><?=$row->telepon2;?></span>
											</td>
											<td>
												<span hidden class='kode_pos'><?=$row->kode_pos;?></span> 
												<span hidden class='email'><?=$row->email;?></span> 
												<span hidden class='npwp'><?=$row->npwp;?></span>
												<a href='#portlet-config-edit' data-toggle='modal' class="btn-xs btn green btn-edit"><i class="fa fa-edit"></i> </a>
												<a class="btn-xs btn red btn-remove"><i class="fa fa-times"></i> </a>
											</td>
										</tr>
									<? } */?>

								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>			
</div>

<script type="text/javascript" src="<?php echo base_url('assets/global/plugins/jquery-validation/js/jquery.validate.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/global/plugins/jquery-validation/js/additional-methods.min.js'); ?>"></script>

<script src="<?php echo base_url('assets/global/plugins/datatables/media/js/jquery.dataTables.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/global/plugins/jquery-inputmask/jquery.inputmask.bundle.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/bootbox/bootbox.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets_noondev/js/table-advanced.js'); ?>"></script>
<script src="<?php echo base_url('assets_noondev/js/fnReload.js');?>"></script>
<script src="<?php echo base_url('assets/global/plugins/dropzone/dropzone.js'); ?>"></script>
<script src="<?php echo base_url('assets_noondev/js/form-customer.js'); ?>"></script>


<script>
jQuery(document).ready(function() {

	// oTable = $('#general_table').DataTable();
	// oTable.state.clear();
	// oTable.destroy();

	FormAddCustomer.init();
	FormEditCustomer.init();


	$("#general_table").DataTable({
   		"fnCreatedRow": function( nRow, aData, iDataIndex ) {
            var other_data = $('td:eq(10)', nRow).text().split('-?-');
            // console.log(other_data);
            var limit_data = $('td:eq(9)', nRow).text().split(',');
            var kode_pos = other_data[0];
            if (kode_pos == null) {kode_pos = '';}
            var email = other_data[1];
            if (email == null) {email = '';}
            var npwp = other_data[2];
	        if (npwp == null) {npwp = '';}
            var status_aktif = $('td:eq(0)', nRow).text();
            var id = other_data[4];
            var customer_type_id = other_data[5];
            var nik = other_data[6];
	        if (nik == null) {nik = '';}
            var img_link = (other_data[7] == '-' ? '' : other_data[7]);

            var btn_edit = "";
            var btn_status = "";
            <?if (is_posisi_id() <= 3 || is_posisi_id() == 6 ) {?>
            	btn_edit = "<a href='#portlet-config-edit' data-toggle='modal' class='btn-xs btn green btn-edit'><i class='fa fa-edit'></i> </a>";
	            if (status_aktif == 1 ) {
	            	btn_status = "<a class='btn-xs btn red btn-remove'><i class='fa fa-times'></i> </a>";
	            	var text_aktif = 'Aktif';
	            }else{
	            	btn_status = "<a class='btn-xs btn blue btn-remove'><i class='fa fa-play'></i> </a>";
	            	var text_aktif = 'Tidak Aktif';
	            };
        	<?};?>

        	var limit_action = '';
        	if (customer_type_id == 2) {
        		limit_action += "Limit : <span class='limit-amount'>"+change_number_format(limit_data[0])+"</span><br/>";
        		limit_action += "Warning @ : <span class='limit-warning-type' hidden>"+limit_data[1]+"</span>";
        		limit_action += ((limit_data[1] == 1) ? '' : 'Rp') + "<span class='limit-warning-amount'>"+limit_data[2]+"</span>"+((limit_data[1] == 1) ? '%' : '');
        	};

             // 
            var url = "<?=base_url(is_setting_link('master/customer_profile'));?>/"+id;
            var img_url = "<?=site_url();?>image/customer/"+img_link;
            var btn_img = "";
            if (img_link != '-' && img_link != '') {
   				btn_img = "<a href='"+img_url+"' target='_blank' class='btn btn-xs btn-success'><i class='fa fa-picture-o'></i></a>"
   			};
            var btn_profile = '<a class="btn-xs btn blue" href="'+url+'" onclick="window.open(this.href, \'newwindow\', \'width=1250, height=650\'); return false;"><i class="fa fa-file-archive-o"></i></a>';
           	<?if (is_posisi_id() == 1) {?>
   				console.log(npwp);
   			<?}?>
            var action = `<span class='id' hidden>${id}</span>
           			<span class='customer_type_id' hidden>${customer_type_id}</span>
           			<span class='email' hidden>${email}</span>
           			<span class='npwp' hidden>${npwp}</span>
           			<span class='nik' hidden>${nik}</span>
           			<span class='img_link' hidden>${img_link}</span>
           			<span class='status_aktif' hidden>${status_aktif}</span>${btn_edit}${btn_status}${btn_profile}${btn_img}`;


           	var data_pos = $('td:eq(5)', nRow).text().split('??');
           	var kota = ' Kab/Kota.<span class="kota">'+data_pos[0]+'</span>';
           	var provinsi = '<span class="provinsi">'+(typeof data_pos[1] === 'undefined' ? '' : data_pos[1])+'</span>';
            var kode_pos = "<span class='kode_pos' hidden>"+kode_pos+"</span>";

            $('td:eq(0)', nRow).html($('td:eq(0)', nRow).text());
            $('td:eq(0)', nRow).addClass('status_column');
            $('td:eq(7)', nRow).addClass('status_column');

            $('td:eq(1)', nRow).html('<span class="nama">'+$('td:eq(1)', nRow).text()+'</span>');
            $('td:eq(2)', nRow).html('<span class="alias">'+$('td:eq(2)', nRow).text()+'</span>');
            $('td:eq(4)', nRow).html('<span class="alamat">'+$('td:eq(4)', nRow).text()+'</span> '+kota+', '+provinsi+' '+kode_pos);
            $('td:eq(5)', nRow).html( (npwp != '' && npwp != 0 ? "<span class='npwp'>"+npwp+"<span>" : "<span class='nik'>"+nik+"</span>") );
            $('td:eq(6)', nRow).html('<span class="telepon1">'+$('td:eq(6)', nRow).text()+'</span>');
            $('td:eq(7)', nRow).html('<span class="telepon2">'+$('td:eq(7)', nRow).text()+'</span>');
            $('td:eq(8)', nRow).html('<span class="tempo_kredit">'+$('td:eq(8)', nRow).text()+'</span>');

            $('td:eq(9)', nRow).html(limit_action);
            $('td:eq(10)', nRow).html(action);
           	// console.log(action);
            // $(nRow).addClass('status_aktif_'+status_aktif);
            
        },
        "bStateSave" :true,
		"bProcessing": true,
		"bServerSide": true,
		"ordering":true,
		"order": [[ 1, "asc" ]],
		"sAjaxSource": baseurl + "master/data_customer?customer_type_id=0",
		"aoColumnDefs": [{ "bVisible": true, "aTargets": [1] }]

	});

	var oTable;
    oTable = $('#general_table').dataTable();
    oTable.fnFilter( 1, 0 );

	$('#status_aktif_select').change(function(){
		oTable.fnFilter( $(this).val(), 0 ); 
	});

    $('#customer_type_id_filter').change(function(e){
		var customer_type_id = $(this).val();
		// alert(customer_type_id);
		oTable.fnReloadAjax(baseurl + "master/data_customer?customer_type_id="+customer_type_id);
	});


    $('[name=customer_type_id]').change(function(){
    	var form = $(this).closest('form').attr('id');
    	if ($('#'+form).find('[name=customer_type_id]:checked').val() == 2) {
	    	$('#'+form).find('.kredit-field').prop('readonly',false);
	    	$('#'+form).find('.kredit-field').prop('disabled',false);
    	}else{
	    	$('#'+form).find('.kredit-field').prop('readonly',true);
	    	$('#'+form).find('.kredit-field').prop('disabled',true);
    	}
    });

    $('[name=limit_warning_type]').change(function(){
    	var form = $(this).closest('form').attr('id');
    	if ($('#'+form).find('[name=limit_warning_type]:checked').val() == 1) {
	    	$('#'+form).find('[name=limit_warning_amount]').removeClass('amount-number');
	    	$('#'+form).find('.percent-field').show();
	    	$('#'+form).find('.rp-field').hide();
    	}else{
	    	$('#'+form).find('[name=limit_warning_amount]').addClass('amount-number');
	    	$('#'+form).find('.rp-field').show();
	    	$('#'+form).find('.percent-field').hide();
    	}
    });
   
   	$('#general_table').on('click', '.btn-edit', function(){
   		let form = $('#form_edit_data');
   		let ini = $(this).closest('tr');
   		let customer_type_id = ini.find('.customer_type_id').html();
   		let limit_warning_type = ini.find('.limit-warning-type').html();
   		var dt_tempo_kredit = ini.find('.tempo_kredit').html().split('/');

   		// alert(ini.find('.id').html());

   		form.find('[name=customer_id]').val(ini.find('.id').html());
   		form.find('[name=customer_type_id]').prop('checked',false);
   		form.find("[name=customer_type_id][value='"+customer_type_id+"']").prop('checked',true);
   		$.uniform.update(form.find("[name=customer_type_id]"));
   		
   		form.find('[name=nama]').val(ini.find('.nama').html());
   		form.find('[name=alias]').val(ini.find('.alias').html());
   		form.find('[name=alamat]').val(ini.find('.alamat').html());
   		form.find('[name=kota]').val(ini.find('.kota').html());
   		form.find('[name=email]').val(ini.find('.email').html());

   		form.find('[name=npwp]').val(ini.find('.npwp').html());
   		form.find('[name=nik]').val(ini.find('.nik').html());
   		form.find('[name=telepon1]').val(ini.find('.telepon1').html());
   		form.find('[name=telepon2]').val(ini.find('.telepon1').html());
   		form.find('[name=kode_pos]').val(ini.find('.kode_pos').html());
   		form.find('[name=provinsi]').val(ini.find('.provinsi').html());
   		form.find('[name=tempo_kredit]').val(dt_tempo_kredit[0]);
   		form.find('[name=warning_kredit]').val(dt_tempo_kredit[1]);
   		var img_link = ini.find('.img_link').html();
   		form.find('[name=img_link]').val(img_link);

   		if (img_link == '' && img_link == '-') {
   			$(".btn-pict-remove-edit").hide();
   		}else{
	   		$("#customer-id-img-edit").attr('src',baseurl+"image/customer/"+img_link);
   			$(".btn-pict-remove-edit").show();
   		};
   		
   		if (customer_type_id == 2) {
	    	form.find('.kredit-field').prop('readonly',false);
	    	form.find('.kredit-field').prop('disabled',false);
	    	form.find('[name=limit_amount]').val(ini.find('.limit-amount').html());
	    	form.find('[name=limit_warning_type]').val(limit_warning_type);
	    	form.find('[name=limit_warning_amount]').val(ini.find('.limit-warning-amount').html());
    	}else{
	    	form.find('.kredit-field').prop('readonly',true);
	    	form.find('.kredit-field').prop('disabled',true);
    	}

    	if (customer_type_id == 2 && limit_warning_type == 1) {
	    	form.find('[name=limit_warning_amount]').removeClass('amount-number');
	    	form.find('.percent-field').show();
	    	form.find('.rp-field').hide();
    	}else if (customer_type_id == 2 && limit_warning_type == 2) {
	    	form.find('[name=limit_warning_amount]').addClass('amount-number');
	    	form.find('.rp-field').show();
	    	form.find('.percent-field').hide();
    	}

   	});	

   	// $('.btn-save').click(function(){
   	// 	if( $('#form_add_data [name=nama]').val() != '' ){
   	// 		btn_disabled_load($(this));
   	// 		$('#form_add_data').submit();
   	// 	}
   	// }); 	

   	// $('.btn-edit-save').click(function(){
   	// 	if( $('#form_edit_data [name=nama]').val() != ''){
   	// 		btn_disabled_load($(this));
   	// 		$('#form_edit_data').submit();
   	// 	}
   	// });

   	$('#general_table').on('click', '.btn-remove', function(){
   		var data = status_aktif_get($(this).closest('tr'))+'=?=customer';
   		var nama = $(this).closest('tr').find('.nama').html();
   		bootbox.confirm("Yakin untuk menonaktifkan customer <b>"+nama+ "</b> ?", function(respond){
   			if (respond) {
		   		window.location.replace(baseurl+"master/ubah_status_aktif?data_sent="+data+'&link=customer_list');

   			};
   		});

   	});
});

function previewFile(el) {
	var preview = document.getElementById('customer-id-img');
	var textarea = document.getElementById('pict-data');
	var file    = el.files[0];
	var reader  = new FileReader();

	reader.addEventListener("load", function () {
		preview.src = reader.result;
		textarea.value = reader.result;
	}, false);

	if (file) {
	    reader.readAsDataURL(file);
	    // $('#changepict').hide();
	    $('.btn-pict-remove').show();
	}
}

function previewFileEdit(el) {
	var preview = document.getElementById('customer-id-img-edit');
	var textarea = document.getElementById('pict-data-edit');
	var file    = el.files[0];
	var reader  = new FileReader();

	reader.addEventListener("load", function () {
		preview.src = reader.result;
		textarea.value = reader.result;
	}, false);

	if (file) {
	    reader.readAsDataURL(file);
	    // $('#changepict').hide();
	    $('.btn-pict-remove-edit').show();
	}
}

function removePictAdd(){
	$("#changepict").val('');
	$("#customer-id-img").attr("src",'');
	$("#pict-data").val("");
    $('.btn-pict-remove').hide();
    // $('#changepict').show();

}

function removePictEdit(){
	$("#changepict-edit").val('');
	$("#customer-id-img-edit").attr("src",'');
	$("#pict-data-edit").val("");
    $('.btn-pict-remove-edit').hide();
    $('#form_edit_data [name=img_link]').val('');
    // $('#changepict').show();

}
</script>
