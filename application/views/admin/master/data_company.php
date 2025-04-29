<div class="page-content">
	<div class='container'>
		
		<div class="row margin-top-10">
			<div class="col-md-2">
				<div class="portlet light">
					<!-- SIDEBAR USERPIC -->
					<div class="profile-userpic">
						<img src="<?=base_url('assets/admin/pages/media/email/photo3.jpg');?>" class="img-responsive" alt="">
					</div>
					
				</div>
			</div>

			<div class="col-md-6">
				<div class="portlet light">
					<!-- END SIDEBAR USER TITLE -->
					<!-- SIDEBAR BUTTONS -->
					<form action="master/data_company_update" class="form-horizontal" id="form_add_data" method="post">
						<h3 class='block'>Data Perusahaan</h3>
						<?
							$nama = '';
							$alamat = '';
							$telepon = '';
							$kelurahan = '';
							$kecamatan = '';
							$provinsi = '';
							$kode_pos = '';
						foreach ($data_company as $row) {
							$nama = $row->nama;
							$alamat = $row->alamat;
							$telepon = $row->telepon;
							$kelurahan = $row->kelurahan;
							$kecamatan = $row->kecamatan;
							$provinsi = $row->provinsi;
							$kode_pos = $row->kode_pos;
						}?>
							<div class="form-group">
			                    <label class="control-label col-md-3">Nama<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-7">
			                    	<input type="text" class="form-control input1" name="nama" value="<?=$nama;?>" />
			                    </div>
			                    
			                </div>
			                <div class="form-group">
			                    <label class="control-label col-md-3">Alamat<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-7">
			                    	<input type="text" class="form-control" name="alamat" value="<?=$alamat;?>"/>
			                    </div>
			                    
			                </div>
			                <div class="form-group">
			                    <label class="control-label col-md-3">Kelurahan<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-7">
			                    	<input type="text" class="form-control" name="kelurahan" value="<?=$kelurahan;?>"/>
			                    </div>
			                    
			                </div>
			                <div class="form-group">
			                    <label class="control-label col-md-3">Kecamatan<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-7">
			                    	<input type="text" class="form-control" name="kecamatan" value="<?=$kecamatan;?>"/>
			                    </div>
			                    
			                </div>
			                <div class="form-group">
			                    <label class="control-label col-md-3">Provinsi<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-7">
			                    	<input type="text" class="form-control" name="provinsi" value="<?=$provinsi;?>"/>
			                    </div>
			                    
			                </div>
			                <div class="form-group">
			                    <label class="control-label col-md-3">Kode Pos<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-7">
			                    	<input type="text" class="form-control" name="kode_pos" value="<?=$kode_pos;?>"/>
			                    </div>
			                    
			                </div>
			                <div class="form-group">
			                    <label class="control-label col-md-3">Telepon<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-7">
			                    	<input type="text" class="form-control" name="telepon" value="<?=$telepon;?>"/>
			                    </div>
			                    
			                </div>
			                <div style='text-align:center'>
								<button class="btn blue btn-edit-save">Save</button>
							</div>
					</form>
					
				</div>
			</div>
		</div>
	</div>			
</div>

<script>
jQuery(document).ready(function() {       
   	
   	$('.btn-save').click(function(){
   		if( $('#form_add_data [name=nama]').val() != '' ){
   			$('#form_add_data').submit();
   		}
   	});

   	$('.btn-edit-save').click(function(){
   		if( $('#form_edit_data [name=nama]').val() != ''){
   			$('#form_edit_data').submit();
   		}
   	});
});
</script>
