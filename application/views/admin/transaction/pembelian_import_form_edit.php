<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css'); ?>"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css'); ?>"/>
<script src="<?php echo base_url('assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js'); ?>" type="text/javascript"></script>
<style type="text/css">
	#form_pembelian input{
		width: 350px;
	}

	#calc_tbl .subtotal{
		text-align: right;
	}

	#tbl_hcs{
		width: 100%;
	}

	#tbl_hcs tr td{
		padding: 0 5px 0 10px;
	}

	#tbl_hcs tr:nth-child(even){
		background: #ddd;
	}

	.hcs-total{
		font-weight: bold;
		font-size: 1.3em;
	}
</style>
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

		$nama_barang = $row->nama_barang;
			
		$kurs = $row->kurs;
		if ($kurs == '' || $kurs == 0) {
			$kurs = 1;
		}
		$currency_type_id = 2;
		// $remarks = $row->remarks;
	}

	$sisipan = '';
	if ($po_type == 'noppn') {
		$sisipan = 'NP/';
	}elseif ($po_type == 'import') {
		$sisipan = 'I/';

		$sewa_gudang= 0;
		$lift_on_1= 0;
		$pas_truck= 0;
		$cost_recovery= 0;
		$adm_nota_1= 0;
		$adm_sp2 = 0;
		$ppn_1= 0;
		$materai_1= 0;
		$behandle = 0;
		$segel= 0;
		$adm_nota_2= 0;
		$ppn_2= 0;
		$materai_2= 0;
		$lift_on_2= 0;
		$adm_sp= 0;
		$ppn_3= 0;
		$adm_do= 0;
		$adm_bank = 0;
		$edi = 0;
		$trucking_clearance= 0;
		$ob= 0;
		$lift_on_off = 0; 
		$washing = 0;
		$adm = 0;
		$ppn_4= 0;
		$materai_3= 0;
		$handling = 0;
		$moving= 0;
		$gerakan = 0;  
		$extra_move= 0;
		$behandle_2= 0;
		$surcharge= 0;
		$adm_2= 0;
		$ppn_5= 0;
		$jasa_inklaring= 0;
		$ppn_jasa_inklaring= 0;
		$trucking = 0;
		$kawalan = 0;
		$lift_off = 0;
		$adm_do_2 = 0;
		$jalur_kuning = 0;
		$materai_4= 0;
		$depkes= 0;
		$cleaning = 0;
		$do_fee= 0;
		$adm_do_3 = 0;
		$adm_fee = 0;  
		$materai_5= 0;
		$dg= 0;
		$agency_fee= 0;
		$extra_tarik = 0;
		$extra_lancar = 0;
		$demurrage= 0;
		$cleaning_2= 0;
		$lab = 0; 
		$thc = 0;
		$doc_fee = 0;
		$adm_fee_2= 0;
		$import_service_charge= 0;
		$asuransi = 0;
		$hcs_total = 0;



		foreach ($hcs_data as $row) {
			$sewa_gudang= $row->sewa_gudang;
			$lift_on_1= $row->lift_on_1;
			$pas_truck= $row->pas_truck;
			$cost_recovery= $row->cost_recovery;
			$adm_nota_1= $row->adm_nota_1;
			$adm_sp2 = $row->adm_sp2;  
			$ppn_1= $row->ppn_1;
			$materai_1= $row->materai_1;
			$behandle = $row->behandle;
			$segel= $row->segel;
			$adm_nota_2= $row->adm_nota_2;
			$ppn_2= $row->ppn_2;
			$materai_2= $row->materai_2;
			$lift_on_2= $row->lift_on_2;
			$adm_sp= $row->adm_sp;
			$ppn_3= $row->ppn_3;
			$adm_do= $row->adm_do;
			$adm_bank = $row->adm_bank;
			$edi = $row->edi;
			$trucking_clearance= $row->trucking_clearance;
			$ob= $row->ob;
			$lift_on_off = $row->lift_on_off;  
			$washing = $row->washing;  
			$adm = $row->adm;  
			$ppn_4= $row->ppn_4;
			$materai_3= $row->materai_3;
			$handling = $row->handling;
			$moving= $row->moving;
			$gerakan = $row->gerakan;  
			$extra_move= $row->extra_move;
			$behandle_2= $row->behandle_2;
			$surcharge= $row->surcharge;
			$adm_2= $row->adm_2;
			$ppn_5= $row->ppn_5;
			$jasa_inklaring= $row->jasa_inklaring;
			$ppn_jasa_inklaring= $row->ppn_jasa_inklaring;
			$trucking = $row->trucking;
			$kawalan = $row->kawalan;  
			$lift_off = $row->lift_off;
			$adm_do_2 = $row->adm_do_2;
			$jalur_kuning = $row->jalur_kuning;
			$materai_4= $row->materai_4;
			$depkes= $row->depkes;
			$cleaning = $row->cleaning;
			$do_fee= $row->do_fee;
			$adm_do_3 = $row->adm_do_3;
			$adm_fee = $row->adm_fee;  
			$materai_5= $row->materai_5;
			$dg= $row->dg;
			$agency_fee= $row->agency_fee;
			$extra_tarik = $row->extra_tarik;  
			$extra_lancar = $row->extra_lancar;
			$demurrage= $row->demurrage;
			$cleaning_2= $row->cleaning_2;
			$lab = $row->lab;  
			$thc = $row->thc;  
			$doc_fee = $row->doc_fee;  
			$adm_fee_2= $row->adm_fee_2;
			$import_service_charge= $row->import_service_charge;
			$asuransi = $row->asuransi;

			$hcs_total+= $row->sewa_gudang;
			$hcs_total+= $row->lift_on_1;
			$hcs_total+= $row->pas_truck;
			$hcs_total+= $row->cost_recovery;
			$hcs_total+= $row->adm_nota_1;
			$hcs_total+= $row->adm_sp2;  
			$hcs_total+= $row->ppn_1;
			$hcs_total+= $row->materai_1;
			$hcs_total+= $row->behandle;
			$hcs_total+= $row->segel;
			$hcs_total+= $row->adm_nota_2;
			$hcs_total+= $row->ppn_2;
			$hcs_total+= $row->materai_2;
			$hcs_total+= $row->lift_on_2;
			$hcs_total+= $row->adm_sp;
			$hcs_total+= $row->ppn_3;
			$hcs_total+= $row->adm_do;
			$hcs_total+= $row->adm_bank;
			$hcs_total+= $row->edi;
			$hcs_total+= $row->trucking_clearance;
			$hcs_total+= $row->ob;
			$hcs_total+= $row->lift_on_off;  
			$hcs_total+= $row->washing;  
			$hcs_total+= $row->adm;  
			$hcs_total+= $row->ppn_4;
			$hcs_total+= $row->materai_3;
			$hcs_total+= $row->handling;
			$hcs_total+= $row->moving;
			$hcs_total+= $row->gerakan;  
			$hcs_total+= $row->extra_move;
			$hcs_total+= $row->behandle_2;
			$hcs_total+= $row->surcharge;
			$hcs_total+= $row->adm_2;
			$hcs_total+= $row->ppn_5;
			$hcs_total+= $row->jasa_inklaring;
			$hcs_total+= $row->ppn_jasa_inklaring;
			$hcs_total+= $row->trucking;
			$hcs_total+= $row->kawalan;  
			$hcs_total+= $row->lift_off;
			$hcs_total+= $row->adm_do_2;
			$hcs_total+= $row->jalur_kuning;
			$hcs_total+= $row->materai_4;
			$hcs_total+= $row->depkes;
			$hcs_total+= $row->cleaning;
			$hcs_total+= $row->do_fee;
			$hcs_total+= $row->adm_do_3;
			$hcs_total+= $row->adm_fee;  
			$hcs_total+= $row->materai_5;
			$hcs_total+= $row->dg;
			$hcs_total+= $row->agency_fee;
			$hcs_total+= $row->extra_tarik;  
			$hcs_total+= $row->extra_lancar;
			$hcs_total+= $row->demurrage;
			$hcs_total+= $row->cleaning_2;
			$hcs_total+= $row->lab;  
			$hcs_total+= $row->thc;  
			$hcs_total+= $row->doc_fee;  
			$hcs_total+= $row->adm_fee_2;
			$hcs_total+= $row->import_service_charge;
			$hcs_total+= $row->asuransi;

		}
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

		<div class="modal fade" id="portlet-config-hcs" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body">
						<form action="<?=$common_data['controller_main'].'/pembelian_detail_hcs_insert';?>" class="form-horizontal" id="form_hcs" method="post">
							<h3 class='block'> Handling Charges Settlement Detail</h3>
			                <input hidden='hidden' name="pembelian_id" value="<?=$pembelian_id;?>">
			                <table id='tbl_hcs'>
			                	<tr><td>sewa gudang n lift on</td><td style='padding:5px'> : </td><td><input name="sewa_gudang" class='' value='<?=$sewa_gudang;?>' /> </td></tr>
			                	<tr><td>lift on</td><td style='padding:5px'> : </td><td><input name="lift_on_1" class='' value='<?=$lift_on_1;?>' /></td></tr>
			                	<tr><td>Cost Recovery</td><td style='padding:5px'> : </td><td><input name="cost_recovery" class='' value='<?=$cost_recovery;?>' /></td></tr>
			                	<tr><td>pas Truck</td><td style='padding:5px'> : </td><td><input name="pas_truck" class='' value='<?=$pas_truck;?>' /> </td></tr>
			                	<tr><td>Adm Nota</td><td style='padding:5px'> : </td><td><input name="adm_nota_1" class='' value='<?=$adm_nota_1;?>' /> </td></tr>
			                	<tr><td>Adm SP2</td><td style='padding:5px'> : </td><td><input name="adm_sp2" class='' value='<?=$adm_sp2;?>' /> </td></tr>
			                	<tr><td>ppn</td><td style='padding:5px'> : </td><td><input name="ppn_1" class='' value='<?=$ppn_1;?>' /> </td></tr>
			                	<tr><td>materai</td><td style='padding:5px'> : </td><td><input name="materai_1" class='' value='<?=$materai_1;?>' /> </td></tr>
			                	<tr><td>behandle / gerakan</td><td style='padding:5px'> : </td><td><input name="behandle" class='' value='<?=$behandle;?>' /> </td></tr>
			                	<tr><td>Segel @1</td><td style='padding:5px'> : </td><td><input name="segel" class='' value='<?=$segel;?>' /> </td></tr>

			                	<?//==========================================?>

			                	<tr><td>Adm nota</td><td style='padding:5px'> : </td><td><input name="adm_nota_2" class='' value='<?=$adm_nota_2;?>' /> </td></tr>
			                	<tr><td>ppn</td><td style='padding:5px'> : </td><td><input name="ppn_2" class='' value='<?=$ppn_2;?>' /> </td></tr>
			                	<tr><td>materai</td><td style='padding:5px'> : </td><td><input name="materai_2" class='' value='<?=$materai_2;?>' /> </td></tr>
			                	<tr><td>lift on</td><td style='padding:5px'> : </td><td><input name="lift_on_2" class='' value='<?=$lift_on_2;?>' /> </td></tr>
			                	<tr><td>adm,sp</td><td style='padding:5px'> : </td><td><input name="adm_sp" class='' value='<?=$adm_sp;?>' /> </td></tr>
			                	<tr><td>ppn</td><td style='padding:5px'> : </td><td><input name="ppn_3" class='' value='<?=$ppn_3;?>' /> </td></tr>
			                	<tr><td>adm D/O, B/L</td><td style='padding:5px'> : </td><td><input name="adm_do" class='' value='<?=$adm_do;?>' /> </td></tr>
			                	<tr><td>adm bank (NO PNBP)</td><td style='padding:5px'> : </td><td><input name="adm_bank" class='' value='<?=$adm_bank;?>' /> </td></tr>
			                	<tr><td>edi</td><td style='padding:5px'> : </td><td><input name="edi" class='' value='<?=$edi;?>' /> </td></tr>
			                	<tr><td>trucking & clearance</td><td style='padding:5px'> : </td><td><input name="trucking_clearance" class='' value='<?=$trucking_clearance;?>' /> </td></tr>

			                	<?//==========================================?>
			                	<tr><td>OB</td><td style='padding:5px'> : </td><td><input name="ob" class='' value='<?=$ob;?>' /> </td></tr>
			                	<tr><td>lift on/off</td><td style='padding:5px'> : </td><td><input name="lift_on_off" class='' value='<?=$lift_on_off;?>' /> </td></tr>
			                	<tr><td>washing</td><td style='padding:5px'> : </td><td><input name="washing" class='' value='<?=$washing;?>' /> </td></tr>
			                	<tr><td>adm</td><td style='padding:5px'> : </td><td><input name="adm" class='' value='<?=$adm;?>' /> </td></tr>
			                	<tr><td>PPN</td><td style='padding:5px'> : </td><td><input name="ppn_4" class='' value='<?=$ppn_4;?>' /> </td></tr>
			                	<tr><td>materai</td><td style='padding:5px'> : </td><td><input name="materai_3" class='' value='<?=$materai_3;?>' /> </td></tr>
			                	<tr><td>handling</td><td style='padding:5px'> : </td><td><input name="handling" class='' value='<?=$handling;?>' /></td></tr>
			                	<tr><td>moving</td><td style='padding:5px'> : </td><td><input name="moving" class='' value='<?=$moving;?>' /></td></tr>
			                	<tr><td>gerakan</td><td style='padding:5px'> : </td><td><input name="gerakan" class='' value='<?=$gerakan;?>' /> </td></tr>
			                	<tr><td>extra move</td><td style='padding:5px'> : </td><td><input name="extra_move" class='' value='<?=$extra_move;?>' /> </td></tr>

			                	<?//==========================================?>

			                	<tr><td>behandle</td><td style='padding:5px'> : </td><td><input name="behandle_2" class='' value='<?=$behandle_2;?>' /> </td></tr>
								<tr><td>surcharge 25%</td><td style='padding:5px'> : </td><td><input name="surcharge" class='' value='<?=$surcharge;?>' /> </td></tr>
								<tr><td>adm</td><td style='padding:5px'> : </td><td><input name="adm_2" class='' value='<?=$adm_2;?>' /> </td></tr>
								<tr><td>ppn</td><td style='padding:5px'> : </td><td><input name="ppn_5" class='' value='<?=$ppn_5;?>' /></td></tr>
								<tr><td>jasa inklaring </td><td style='padding:5px'> : </td><td><input name="jasa_inklaring" class='' value='<?=$jasa_inklaring;?>' /> </td></tr>
								<tr><td>ppn jasa inklaring</td><td style='padding:5px'> : </td><td><input name="ppn_jasa_inklaring" class='' value='<?=$ppn_jasa_inklaring;?>' /> </td></tr>
								<tr><td>trucking</td><td style='padding:5px'> : </td><td><input name="trucking" class='' value='<?=$trucking;?>' /> </td></tr>
								<tr><td>KAWALAN</td><td style='padding:5px'> : </td><td><input name="kawalan" class='' value='<?=$kawalan;?>' /> </td></tr>
								<tr><td>lift off</td><td style='padding:5px'> : </td><td><input name="lift_off" class='' value='<?=$lift_off;?>' /> </td></tr>
								<tr><td>adm DO </td><td style='padding:5px'> : </td><td><input name="adm_do_2" class='' value='<?=$adm_do_2;?>' /> </td></tr>

								<?//==========================================?>

								<tr><td>jalur KUNING</td><td style='padding:5px'> : </td><td><input name="jalur_kuning" class='' value='<?=$jalur_kuning;?>' /> </td></tr>
								<tr><td>materai</td><td style='padding:5px'> : </td><td><input name="materai_4" class='' value='<?=$materai_4;?>' /> </td></tr>
								<tr><td>depkes</td><td style='padding:5px'> : </td><td><input name="depkes" class='' value='<?=$depkes;?>' /> </td></tr>
								<tr><td>cleaning</td><td style='padding:5px'> : </td><td><input name="cleaning" class='' value='<?=$cleaning;?>' /> </td></tr>
								<tr><td>DO fee</td><td style='padding:5px'> : </td><td><input name="do_fee" class='' value='<?=$do_fee;?>' /> </td></tr>
								<tr><td>adm DO</td><td style='padding:5px'> : </td><td><input name="adm_do_3" class='' value='<?=$adm_do_3;?>' /> </td></tr>
								<tr><td>adm fee</td><td style='padding:5px'> : </td><td><input name="adm_fee" class='' value='<?=$adm_fee;?>' /> </td></tr>
								<tr><td>materai</td><td style='padding:5px'> : </td><td><input name="materai_5" class='' value='<?=$materai_5;?>' /> </td></tr>
								<tr><td>DG </td><td style='padding:5px'> : </td><td><input name="dg" class='' value='<?=$dg;?>' /> </td></tr>
								<tr><td>agency fee $20+30+5 </td><td style='padding:5px'> : </td><td><input name="agency_fee" class='' value='<?=$agency_fee;?>' /> </td></tr>

								<?//==========================================?>

								<tr><td>extra tarik cont</td><td style='padding:5px'> : </td><td><input name="extra_tarik" class='' value='<?=$extra_tarik;?>' /></td></tr>
								<tr><td>extra kelancaran</td><td style='padding:5px'> : </td><td><input name="extra_lancar" class='' value='<?=$extra_lancar;?>' /></td></tr>
								<tr><td>demurrage</td><td style='padding:5px'> : </td><td><input name="demurrage" class='' value='<?=$demurrage;?>' /></td></tr>
								<tr><td>cleaning</td><td style='padding:5px'> : </td><td><input name="cleaning_2" class='' value='<?=$cleaning_2;?>' /></td></tr>
								<tr><td>lab</td><td style='padding:5px'> : </td><td><input name="lab" class='' value='<?=$lab;?>' /> </td></tr>
								<tr><td>THC $95</td><td style='padding:5px'> : </td><td><input name="thc" class='' value='<?=$thc;?>' /> </td></tr>
								<tr><td>DOC FEE</td><td style='padding:5px'> : </td><td><input name="doc_fee" class='' value='<?=$doc_fee;?>' /></td></tr>
								<tr><td>Adm FEE</td><td style='padding:5px'> : </td><td><input name="adm_fee_2" class='' value='<?=$adm_fee_2;?>' /> </td></tr>
								<tr><td>IMPORT SERVICE CHARGE</td><td style='padding:5px'> : </td><td><input name="import_service_charge" class='' value='<?=$import_service_charge;?>' /> </td></tr>
								<tr><td>asuransi (lokal)</td><td style='padding:5px'> : </td><td><input name="asuransi" class='' value='<?=$asuransi;?>' /></td></tr>
								<tr>
			                		<td colspan='3' style='text-align: center;'>
			                			<h2>TOTAL  : <span class='hcs-total'><?=number_format($hcs_total,'4','.',',');?></span></h2>
			                		</td>
			                	</tr>
			                </table>
						</form>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn green btn-hcs-save" data-dismiss="modal">Save</button>
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
						<div class='col-md-6'>
							<h2 style='text-transform:uppercase'><b><?=$nama_supplier;?></b>
							</h2>
							<h4>
								PO NUMBER : <?=$po_number.'/'.$romani[$bulan-1].'/'.$sisipan.$tahun;?><br>
							</h4>
						</div>
						<div class='col-md-6 text-right'>
							<h2>
								<button class='btn btn-lg btn-default btn-table hidden-print' style='display:none'><i class='fa fa-table'></i></button>
								<button class='btn btn-lg btn-default btn-list hidden-print'><i class='fa fa-list'></i></button>
							</h2>
						</div>

					</div>
					<div class="portlet-body">
						<div id="row-form" style='display:none'>
							<form id="form_pembelian" action="<?=$common_data['controller_main'].'/pembelian_import_update';?>" method="post">
								<table class='table table-hover' id="table_detail">
									<thead>
										<tr>
											<th>Item</th>
											<th style="width:30%;">Value</th>
											<th>Keterangan</th>
										</tr>
									</thead>
									<tbody>
										<?foreach ($pembelian as $row) { ?>
											<input value='<?=$row->id;?>' hidden='hidden' name='id'>
											<tr><td>No.</td><td><input name="no" value="<?=$row->no;?>"></td><td></td></tr>
											<tr><td>Principal</td><td><input name="principal" value="<?=$row->principal?>"></td><td></td></tr>
											<tr><td>L/C no.</td><td><input name="lc_number" value="<?=$row->lc_number?>"></td><td></td></tr>
											<tr><td>Invoice no.</td><td><input name="invoice_number" value="<?=$row->invoice_number;?>"></td><td></td></tr>
											<tr><td>Vessel</td><td><input name="vessel" value="<?=$row->vessel?>"></td><td></td></tr>
											<tr><td>ETD</td><td><input name="etd" value="<?=is_date_monthname($row->etd);?>" class='date-picker2'></td><td></td></tr>
											<tr><td>ETA</td><td><input name="eta" value="<?=is_date_monthname($row->eta);?>" class='date-picker2'></td><td></td></tr>
											<tr><td>Go Down</td><td><input name="go_down" value="<?=is_date_monthname($row->go_down);?>" class='date-picker2'></td><td></td></tr>
											<tr><td>Product</td><td><?=$nama_barang;?></td><td></td></tr>
											<tr><td>HS Number</td><td><input name="hs_number" value="<?=$row->hs_number;?>"></td><td></td></tr>
											
											<tr>
												<td>Packing Qty</td>
												<td><input name="packing_qty" value="<?=$row->packing_qty;?>"></td>
												<td>...kg</td>
											</tr>
											<tr>
												<td>Packing Container</td>
												<td><input name="packing_container" value="<?=$row->packing_container;?>"></td>
												<td>drum/zak</td>
											</tr>
											<tr>
												<td>Quantity of product</td>
												<td><input name="quantity_of_product" value="<?=$row->quantity_of_product;?>"></td>
												<td>net weight</td>
											</tr>
											<tr>
												<td>Quantity of invoice</td>
												<td><input name="quantity_of_invoice" value="<?=$row->quantity_of_invoice;?>"></td>
												<td>net weight</td>
											</tr>
											<tr>
												<td>Quantity of invoice (custom dutied)</td>
												<td><input name="quantity_of_invoice_custom_dutied" value="<?=$row->quantity_of_invoice_custom_dutied;?>"></td>
												<td>net weight</td>
											</tr>
											<tr>
												<td>Quantity</td>
												<td><input name="quantity" value="<?=$row->quantity?>"></td>
												<td>kg / m</td>
											</tr>
											<tr>
												<td>Total value of product</td>
												<td><input name="total_value_of_product" value="<?=$row->total_value_of_product?>"></td>
												<td>net value</td>
											</tr>
											<tr>
												<td>Total value of invoice</td>
												<td><input name="total_value_of_invoice" value="<?=$row->total_value_of_invoice?>"></td>
												<td>net value</td>
											</tr>
											<tr>
												<td>Exchange rate</td>
												<td><input name="exchange_rate" value="<?=$row->exchange_rate?>"></td>
												<td>Rp./USD</td>
											</tr>

											<tr><td>Tax exchange rate</td><td><input name="tax_exchange_rate" value="<?=$row->tax_exchange_rate;?>"></td><td>Rp./USD</td></tr>
											<tr><td>First payment rate</td><td><input name="first_payment_rate" value="<?=$row->first_payment_rate;?>"></td><td>Rp./USD</td></tr>
											<tr><td>Settlement rate </td><td><input name="settlement_rate" value="<?=$row->settlement_rate;?>"></td><td>Rp./USD</td></tr>
											<tr><td>Custom Duty (if there's non-customed-dutied)</td><td><input name="custom_duty_if_non" value="<?=$row->custom_duty_if_non;?>"></td><td>Rp./USD</td></tr>
											<tr><td>Custom Duty</td><td><input name="custom_duty" value="<?=$row->custom_duty;?>"></td><td>in percent</td></tr>
											<tr><td>Import VAT</td><td><input name="import_vat" value="<?=$row->import_vat;?>"></td><td>in percent</td></tr>
											<tr><td>Import Income Tax</td><td><input name="import_income_tax" value="<?=$row->import_income_tax;?>"></td><td>in percent</td></tr>
											<tr><td>Margin Deposit</td><td><input name="margin_deposit" value="<?=$row->margin_deposit;?>"></td><td>in percent</td></tr>
											<tr><td>First payment</td><td><input name="first_payment" value="<?=$row->first_payment;?>"></td><td>in percent</td></tr>
											<tr><td>Settlement</td><td><input name="settlement" value="<?=$row->settlement;?>"></td><td>in percent</td></tr>

											<tr><td>Bank Commission (1st payment)</td><td><input name="bank_commission_1st_payment" value="<?=$row->bank_commission_1st_payment;?>"></td><td></td></tr>
											<tr><td>Bank Commission (settlement)</td><td><input name="bank_commission_settlement" value="<?=$row->bank_commission_settlement;?>"></td><td></td></tr>
											<tr><td>Cable Charges </td><td><input name="cable_charges" value="<?=$row->cable_charges;?>"></td><td>in percent, or US$25.00 minimum (enter directly in import calculation)</td></tr>
											<tr><td>Bank Administration Charges</td><td><input name="bank_administration_charges_1" value="<?=$row->bank_administration_charges_1;?>"></td><td>US$20.00 for non L/C & US$10.00 for L/C</td></tr>
											<tr><td>Bank Administration Charges</td><td><input name="bank_administration_charges_2" value="<?=$row->bank_administration_charges_2;?>"></td><td>US$20.00 for non L/C & US$10.00 for L/C</td></tr>
											<tr><td>Negotiation Interest</td><td><input name="negotiation_interest" value="<?=$row->negotiation_interest;?>"></td><td>in percent</td></tr>
											<tr><td>Insurance Charges</td><td><input name="insurance_charges" value="<?=$row->insurance_charges;?>"></td><td></td></tr>
											
											<tr>
												<td>Handling Charges Settlement 
													<button href="#portlet-config-hcs" data-toggle='modal' class='btn btn-xs blue btn-hcs hidden-print'><i class='fa fa-edit'></i></button> 
												</td>
												<td>
													<a href="#portlet-config-hcs" data-toggle='modal' style='color:black'><b><?=number_format($hcs_total,'4',',','.');?></b></a>
													<!-- <input name="handling_charges_settlement" value="<?=$row->handling_charges_settlement;?>"> -->
												</td>
												<td></td>
											</tr>
											<tr><td>Handling Charges Settlement (Cadangan)</td><td><input name="handling_charges_settlement_cadangan" value="<?=$row->handling_charges_settlement_cadangan;?>"></td><td></td></tr>
											<tr><td>Commission</td><td><input name="commission" value="<?=$row->commission;?>"></td><td>in percent</td></tr>
											<tr><td>Handling Charges Settlement (US$)</td><td><input name="" value="<?=$row->handling_charges_settlement_us;?>"></td><td></td></tr>
											<tr><td>Extra Charges For Notul</td><td><input name="extra_charges_for_notul" value="<?=$row->extra_charges_for_notul;?>"></td><td></td></tr>
											<tr><td>BPOM</td><td><input name="bpom" value="<?=$row->bpom;?>"></td><td>in US$ (deletable)</td></tr>
											<tr><td>Interest</td><td><input name="interest" value="<?=$row->interest;?>"></td><td></td></tr>
											</tr><td>Additional Import Cost</td><td><input name="additional_import_cost" value="<?=$row->additional_import_cost;?>"></td><td></td></tr>
										<?}?>
											
									</tbody>
								</table>
							</form>
						</div>					

						<div id='table-form'>
							<p>
								Catatan:
								<ol>
									<li>Yang dapat diisi adalah: No. PRN#, Date</li>
									<li>Yang dapat diganti adalah: Rounding-up</li>
									<li>Bank Commission minimum US$25 (dapat diisi langsung)</li>
								</ol>
							</p>

							<?foreach ($pembelian as $row) { ?>
								<p style='width:100%;text-align:right; font-size:2em;'>
									<?=$row->no;?>
								</p>

								<h2 style='text-align:center'><b>IMPORT CALCULATION</b></h2>
								
								<table>
									<tr>
										<td colspan='4'><b>No.Urut / Tanggal / No. POR:</b></td>
									</tr>
									<tr>
										<td><b>Principal</b></td>
										<td colspan='3'><?=$row->principal;?></td>
									</tr>
									<tr>
										<td style='width:10%;'><b>L/C No.</b></td>
										<td style='width:60%;'><?=$row->lc_number;?></td>
										<td style='width:15%;'><b>Invoice No.</b></td>
										<td><?=$row->invoice_number;?></td>
									</tr>
									<tr>
										<td><b>Vessel</b></td>
										<td><?=$row->vessel;?></td>
										<td><b>Import VAT</b></td>
										<?
											$custom_duty = $row->total_value_of_product*$row->tax_exchange_rate*$row->custom_duty;
											$vat = round((($row->total_value_of_product*$row->tax_exchange_rate)+$row->custom_duty)*$row->import_vat/100,-3,PHP_ROUND_HALF_UP);
										?>
										<td><?=is_number_format4($vat); ?></td>
									</tr>
									<tr>
										<td><b>ETD</b></td>
										<td><?=$row->etd;?></td>
										<td><b>ETA</b></td>
										<td><?=is_date_monthname($row->eta);?></td>
									</tr>
									<tr>
										<td><b>Go Down</b></td>
										<td><?=$row->go_down;?></td>
										<td><b></b></td>
										<td></td>
									</tr>
									<tr>
										<td><b>USD per unit</b></td>
										<td></td>
										<td><b>Total Value US$</b></td>
										<td><?=is_number_format4($row->total_value_of_invoice);?></td>
									</tr>
								</table>

								<br/>
								<table class='table' style='width:100%'>
									<thead>
										<tr>
											<th>No. PRN#</th>
											<th>Product / Commodity</th>
											<th>HS Number</th>
											<th>Packing</th>
											<th>Quantity</th>
											<th>Unit Price</th>
											<th>Sub Total</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td></td>
											<td><?=$row->nama_barang;?></td>
											<td><?=$row->hs_number;?></td>
											<td><?=$row->packing_qty.' '.$row->packing_container;?></td>
											<td><?=$row->qty.' '.$row->nama_satuan;?></td>
											<td><?=$row->price;?></td>
											<td><?=is_number_format4($row->total_value_of_product);?></td>
										</tr>
									</tbody>
								</table>

								<table id='calc_tbl' class='table table-border' style='width:100%'>
									<thead>
										<tr>
											<th></th>
											<th colspan='6' style='text-align:center'>CALCULATION BREAKDOWN / DESCRIPTION</th>
											<th></th>
										</tr>
									</thead>
									<tbody>
										<? 
											$d18 = $row->total_value_of_product/$row->total_value_of_invoice;
											$usd = 0;
											$subtotal = 0;
										?>
										<!-- 1 -->
										<tr>
											<td></td>
											<td>Margin Deposit</td>
											<td><?=$row->margin_deposit;?></td>
											<td> x CIF</td>
											<td>USD</td>
											<?$j = $row->margin_deposit*$row->total_value_of_product;?>
											<td><?=is_number_format4($j); ?></td>
											<td> x <?=is_number_format4($row->exchange_rate);?></td>
											<td class='subtotal'><?=is_number_format4($j*$row->exchange_rate)?></td>
											<? $subtotal += $j*$row->exchange_rate;?>
										</tr>

										<!-- 2 -->
										<tr>
											<td></td>
											<td>Bank Commission (First Payment)</td>
											<td></td>
											<td> x CIF</td>
											<td>USD</td>
											<?$j=$row->bank_commission_1st_payment*$d18;?>
											<td><?=is_number_format4($j);?></td>
											<td> x <?=is_number_format4($row->first_payment_rate);?></td>
											<td class='subtotal'><?=is_number_format4($j*$row->first_payment_rate);?></td>
											<? $subtotal += $j*$row->first_payment_rate;?>
										</tr>

										<!-- 3 -->
										<tr>
											<td></td>
											<td>Bank Commission (Settlement)</td>
											<td></td>
											<td> x CIF</td>
											<td>USD</td>
											<?$j=$row->bank_commission_settlement*$d18;?>
											<td><?=is_number_format4($j);?></td>
											<td> x <?=is_number_format4($row->settlement_rate);?></td>
											<td class='subtotal'><?=is_number_format4($j*$row->settlement_rate);?></td>
											<? $subtotal += $j*$row->settlement_rate;?>
										</tr>

										<!-- 4 -->
										<tr>
											<td></td>
											<td>Cable Charges</td>
											<td></td>
											<td></td>
											<td>USD</td>
											<?$j = $row->cable_charges; ?>
											<td><?=is_number_format4($j);?></td>
											<td> x <?=is_number_format4($row->settlement_rate);?></td>
											<td class='subtotal'><?=is_number_format4($j*$row->settlement_rate);?></td>
											<? $subtotal += $j*$row->settlement_rate;?>
										</tr>

										<!-- 5 -->
										<tr>
											<td></td>
											<td>First Payment</td>
											<td></td>
											<td></td>
											<td>USD</td>
											<?$j = $row->first_payment*$row->total_value_of_invoice*$d18;?>
											<td><?=is_number_format4($j);?></td>
											<td> x <?=is_number_format4($row->first_payment_rate);?></td>
											<td class='subtotal'><?=is_number_format4($j*$row->first_payment_rate);?></td>
											<? $subtotal += $j*$row->first_payment_rate;?>
										</tr>

										<!-- 6 -->
										<tr>
											<td></td>
											<td>Settlement</td>
											<td></td>
											<td>x CIF</td>
											<td>USD</td>
											<?$j = $row->settlement/100*$row->total_value_of_invoice*$d18;?>
											<td><?=is_number_format4($j);?></td>
											<td> x <?=is_number_format4($row->settlement_rate);?></td>
											<td class='subtotal'><?=is_number_format4($j*$row->settlement_rate);?></td>
											<? $subtotal += $j*$row->settlement_rate;?>
										</tr>

										<!-- 7 -->
										<tr>
											<td></td>
											<td>Bank Administration Charges</td>
											<td></td>
											<td></td>
											<td>USD</td>
											<?$j = $row->bank_administration_charges_1*$d18;?>
											<td><?=is_number_format4($j);?></td>
											<td> x <?=is_number_format4($row->exchange_rate);?></td>
											<td class='subtotal'><?=is_number_format4($j*$row->exchange_rate);?></td>
											<? $subtotal += $j*$row->exchange_rate;?>
										</tr>

										<!-- 8 -->
										<tr>
											<td></td>
											<td>Bank Administration Charges</td>
											<td></td>
											<td></td>
											<td>USD</td>
											<?$j = $row->bank_administration_charges_2*$d18;?>
											<td><?=is_number_format4($j);?></td>
											<td> x <?=is_number_format4($row->exchange_rate);?></td>
											<td class='subtotal'><?=is_number_format4($j*$row->exchange_rate);?></td>
											<? $subtotal += $j*$row->exchange_rate;?>
										</tr>

										<!-- 9 -->
										<tr>
											<td></td>
											<td>Negotiation Interest</td>
											<td><?=$row->negotiation_interest;?></td>
											<td>x CIF</td>
											<td>USD</td>
											<?$j = $row->negotiation_interest*$row->total_value_of_product;?>
											<td><?=is_number_format4($j);?></td>
											<td> x <?=is_number_format4($row->exchange_rate);?></td>
											<td class='subtotal'><?=is_number_format4($j*$row->exchange_rate);?></td>
											<? $subtotal += $j*$row->exchange_rate;?>
										</tr>

										<!-- 10 -->
										<tr>
											<td></td>
											<td>Insurance Charges</td>
											<td></td>
											<td></td>
											<td>USD</td>
											<?$j = $row->insurance_charges;?>
											<td><?=is_number_format4($j/$row->exchange_rate);?></td>
											<td> x <?=is_number_format4($row->exchange_rate);?></td>
											<td class='subtotal'><?=is_number_format4($j);?></td>
											<? $subtotal += $j;?>
										</tr>

										<!-- 11 -->
										<tr>
											<td></td>
											<td>Custom Duty (0% - BBS:100%) ACFTA </td>
											<td></td>
											<td></td>
											<td>USD</td>
											<?$j = $custom_duty?>
											<td><?=is_number_format4($j/$row->tax_exchange_rate);?></td>
											<td> x <?=is_number_format4($row->tax_exchange_rate);?></td>
											<td class='subtotal'><?=is_number_format4($j);?></td>
											<? $subtotal += $j;?>
										</tr>

										<!-- 12 -->
										<tr>
											<td></td>
											<td>Handling Charges Settlement</td>
											<td></td>
											<td></td>
											<td>USD</td>
											<?$j = $hcs_total*$d18; ?>
											<td><?=is_number_format4($j/$row->exchange_rate);?></td>
											<td> x <?=is_number_format4($row->exchange_rate);?></td>
											<td class='subtotal'><?=is_number_format4($j);?></td>
											<? $subtotal += $j;?>
										</tr>

										<tr>
											<td></td>
											<td>Handling Charges Settlement (Cadangan)</td>
											<td></td>
											<td></td>
											<td>USD</td>
											<?$j = $row->handling_charges_settlement_cadangan;?>
											<td><?=is_number_format4($j/$row->exchange_rate);?></td>
											<td> x <?=is_number_format4($row->exchange_rate);?></td>
											<td class='subtotal'><?=is_number_format4($j);?></td>
											<? $subtotal += $j;?>
										</tr>

										<tr>
											<td></td>
											<td>Commision</td>
											<td><?=$row->commission;?> %</td>
											<td>x CIF</td>
											<td>USD</td>
											<? $j = $row->commission*$row->total_value_of_product; ?>
											<td><?=is_number_format4($j);?></td>
											<td> x <?=is_number_format4($row->exchange_rate);?></td>
											<td class='subtotal'><?=is_number_format4($j*$row->exchange_rate);?></td>
											<? $subtotal += $j*$row->exchange_rate;?>
										</tr>

										<tr>
											<td></td>
											<td>Import Income Tax (2.5%)</td>
											<td>2.5 %</td>
											<td>x CIF</td>
											<td>USD</td>
											<?$j = (($row->total_value_of_invoice*$row->tax_exchange_rate)+($row->total_value_of_product*$row->tax_exchange_rate*$row->custom_duty))*(2.5/100)+929.45;?>
											<td><?=is_number_format4($j/$row->tax_exchange_rate);?></td>
											<td> x <?=is_number_format4($row->tax_exchange_rate);?></td>
											<td class='subtotal'><?=is_number_format4($j);?></td>
											<? $subtotal += $j;?>
										</tr>

										<tr>
											<td></td>
											<td>VAT</td>
											<td>10%</td>
											<td>x CIF</td>
											<td>USD</td>
											<?$j = $vat;?>
											<td><?=is_number_format4($j/$row->tax_exchange_rate);?></td>
											<td> x <?=is_number_format4($row->tax_exchange_rate);?></td>
											<td class='subtotal'><?=is_number_format4($j);?></td>
											<? $subtotal += $j;?>
										</tr>

										<tr>
											<td></td>
											<td>Handling Charges Settlement (US$)</td>
											<td></td>
											<td></td>
											<td>USD</td>
											<?$j = $row->handling_charges_settlement_us/$d18;?>
											<td><?=is_number_format4($j);?></td>
											<td> x <?=is_number_format4($row->exchange_rate);?></td>
											<td class='subtotal'><?=is_number_format4($j*$row->exchange_rate);?></td>
											<? $subtotal += $j*$row->exchange_rate;?>
										</tr>

										<tr>
											<td></td>
											<td>Additional Import Cost</td>
											<td>3.000%</td>
											<td>x CIF</td>
											<td>USD</td>
											<?$j = $row->additional_import_cost;?>
											<td><?=is_number_format4($j);?></td>
											<td> x <?=is_number_format4($row->exchange_rate);?></td>
											<td class='subtotal'><?=is_number_format4($j*$row->exchange_rate);?></td>
											<? $subtotal += $j*$row->exchange_rate;?>
										</tr>

										<tr>
											<td></td>
											<td>Extra Charges For Notul</td>
											<td></td>
											<td></td>
											<td>USD</td>
											<?$j = $row->extra_charges_for_notul*$d18;?>
											<td><?=is_number_format4($j/$row->exchange_rate);?></td>
											<td> x <?=is_number_format4($row->exchange_rate);?></td>
											<td class='subtotal'><?=is_number_format4($j);?></td>
											<? $subtotal += $j;?>
										</tr>

										<tr>
											<td></td>
											<td>BPOM</td>
											<td></td>
											<td></td>
											<td>USD</td>
											<?$j = $row->bpom;?>
											<td><?=is_number_format4($j/$row->exchange_rate);?></td>
											<td> x <?=is_number_format4($row->exchange_rate);?></td>
											<td class='subtotal'><?=is_number_format4($j);?></td>
											<? $subtotal += $j;?>
										</tr>

										<tr>
											<td colspan='8' style='border-bottom:1px solid black;'></td>
										</tr>

										<tr>
											<td></td>
											<td><b>Sub Total</b></td>
											<td></td>
											<td></td>
											<td>USD</td>
											<td></td>
											<td></td>
											<td class='subtotal'><?=is_number_format4($subtotal);?></td>
										</tr>

									
										<tr>
											<td></td>
											<td><b>Interest</b></td>
											<td colspan='2'>3 month @ 7.5% p.a.</td>
											<td>USD</td>
											<td><?=is_number_format4(0);?></td>
											<td></td>
											<td class='subtotal'><?=is_number_format4(0);?></td>
										</tr>

										<tr>
											<td></td>
											<td><b>Total</b></td>
											<td></td>
											<td></td>
											<td>USD</td>
											<td><?=is_number_format4(0);?></td>
											<td></td>
											<td class='subtotal'><?=is_number_format4($subtotal);?></td>
										</tr>

										<tr>
											<td></td>
											<td><b>Rounding-up</b></td>
											<td></td>
											<td></td>
											<td>USD</td>
											<td colspan='3'><?=is_number_format4(0);?></td>
											
										</tr>

										<tr>
											<td></td>
											<td><b>Grand Total</b></td>
											<td></td>
											<td></td>
											<td>USD</td>
											<td><?=is_number_format4(0);?></td>
											<td></td>
											<td class='subtotal'><?=is_number_format4($subtotal);?></td>
										</tr>

									</tbody>
								</table>
							<? } ?>
						</div>
					</div>
					<div class="row">
		                <div class="col-md-12" style='margin-top:30px;'>
		                	<button class='btn green btn-submit' >SAVE</button>
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

    $('.btn-submit').click(function(){
    	$('#form_pembelian').submit();
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

    $('#form_pembelian [name=quantity_of_product]').change(function(){
    	$('#form_pembelian [name=total_value_of_product]').val(number_format4(0.278 * $(this).val()));
    	$('#form_pembelian [name=additional_import_cost]').val(number_format4(0.278 * $(this).val()*0.03));
    });

    $('#form_pembelian [name=quantity_of_invoice]').change(function(){
    	$('#form_pembelian [name=total_value_of_invoice]').val(number_format4(0.278 * $(this).val()));
    });

    $('#form_pembelian [name=exchange_rate]').change(function(){
    	$('#form_pembelian [name=bank_administration_charges_1]').val(number_format4(100000 * $(this).val()));
    });

    $('.btn-hcs').click(function(){
    	event.preventDefault();
    });

    $('.btn-list').click(function(){
    	$(this).hide('fast');
    	$('#table-form').hide();
    	$('#row-form').show();
    	$('.btn-table').show();
    });

    $('.btn-table').click(function(){
    	$(this).hide('fast');
    	$('#row-form').hide();
    	$('#table-form').show();
    	$('.btn-list').show();
    });  

    $('.btn-hcs-save').click(function(){
    	$('#form_hcs').submit();
    });

    $('#form_hcs [name=sewa_gudang],#form_hcs [name=lift_on_1],#form_hcs [name=pas_truck],#form_hcs [name=cost_recovery],#form_hcs [name=adm_nota_1],#form_hcs [name=adm_sp2]').change(function(){
    	var sewa_gudang  = number_format4($('#form_hcs [name=sewa_gudang]').val());
		var lift_on_1 = number_format4($('#form_hcs [name=lift_on_1]').val());
		var pas_truck = number_format4($('#form_hcs [name=pas_truck]').val());
		var cost_recovery = number_format4($('#form_hcs [name=cost_recovery]').val());
		var adm_nota_1 = number_format4($('#form_hcs [name=adm_nota_1]').val());
		var adm_sp2 = number_format4($('#form_hcs [name=adm_sp2]').val());

		var ppn = parseFloat(sewa_gudang)+parseFloat(lift_on_1)+parseFloat(pas_truck)+parseFloat(cost_recovery)+parseFloat(adm_nota_1)+parseFloat(adm_sp2);
		ppn *= 0.1;

		$('#form_hcs [name=ppn_1]').val(number_format4(ppn));

    });

    $('#form_hcs [name=behandle],#form_hcs [name=segel],#form_hcs [name=adm_nota_2]').change(function(){
    	var behandle  = number_format4($('#form_hcs [name=behandle]').val());
    	var segel  = number_format4($('#form_hcs [name=segel]').val());
		var adm_nota_2 = number_format4($('#form_hcs [name=adm_nota_2]').val());

		var ppn = parseFloat(behandle)+parseFloat(segel)+parseFloat(adm_nota_2);
		ppn *= 0.1;

		$('#form_hcs [name=ppn_2]').val(number_format4(ppn));

    });

    $('#form_hcs [name=lift_on_off],#form_hcs [name=adm]').change(function(){
    	var lift_on_off  = number_format4($('#form_hcs [name=lift_on_off]').val());
		var adm = number_format4($('#form_hcs [name=adm]').val());

		var ppn = parseFloat(lift_on_off)+parseFloat(adm);
		ppn *= 0.1;

		$('#form_hcs [name=ppn_4]').val(number_format4(ppn));

    });

    $('#form_hcs [name=jasa_inklaring]').change(function(){
    	var jasa_inklaring  = number_format4($('#form_hcs [name=jasa_inklaring]').val());
		var ppn = jasa_inklaring * 0.1;

		$('#form_hcs [name=ppn_jasa_inklaring]').val(number_format4(ppn));

    });

    $('#form_hcs').on('change','input',function(){
    	var hcs_total = 0;
    	$('#form_hcs input').each(function(){
    		hcs_total += parseFloat($(this).val());
    	})

    	$('.hcs-total').html(number_4comma(hcs_total));
    	// alert(number_4comma(hcs_total));
    })
});
</script>

