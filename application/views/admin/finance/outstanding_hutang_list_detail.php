<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css'); ?>"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css'); ?>"/>
<?php echo link_tag('assets/global/plugins/bootstrap-datepicker/css/datepicker3.css'); ?>

<style>
	#div-total{
		position: -webkit-sticky; /* Safari */
		position: sticky;
		bottom: 0;
		background-color:#eee;
		word-spacing: 10px;
	}

	#sumDiv{
		font-size:18px;
		color:blue;
	}

	#sisaDiv{
		font-size:18px;
		color:red;
	}

	.nilai-selected{
		color:green;
	}

	.selected{
		display: none;
		font-size:16px;
	}

	.tanggal-cair{
		display: inline-block;
		text-align: center;
	}

	.date-picker-cair{
		/* display: none; */
		background: transparent;
		border: none;
		width: 100px;
		display: inline;
	}

	.date-picker-cair:focus{
		/* border-bottom: 1px solid black; */
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
					</div>
					<div class="portlet-body">
						<div>
							<?foreach ($supplier_data as $row) {?>
								<h1><?=$row->nama;?></h1>
							<?}?>
						</div>

						<br/>

						<h4><b>- KONTRA BON -</b></h4>
						<table class="table table-hover table-striped table-bordered" id="general_table" style='font-size:1.1em'>
							<thead>
								<tr>							
									<th scope="col">
										Tanggal Faktur
									</th>
									<th scope="col">
										Jumlah
									</th>
									<th scope="col">
										Hutang
									</th>
									<th scope="col">
										Pembayaran
									</th>
									<th scope="col">
										Pembulatan
									</th>
									<th scope="col">
										Sisa
									</th>
									<th scope="col">
										Actions
									</th>
								</tr>
							</thead>
							<tbody>
								<?
								$total_kontra_bon = 0;

								foreach ($outstanding_list_detail as $row) { ?>
									<tr>
										<td>
											<b><?=date('d F Y', strtotime($row->tanggal_awal));?></b>
											 s/d
											<b><?=date('d F Y', strtotime($row->tanggal_akhir));?></b>
										</td>
										<td>
											<?=$row->jml_faktur?> Faktur
										</td>
										<td>
											<?=number_format($row->amount,'0',',','.');?>
										</td>
										<td>
											<?=number_format($row->bayar,'0',',','.');?>
										</td>
										<td>
											<?=number_format($row->pembulatan,'0',',','.');?>
										</td>
										<td>
											<?=number_format($row->amount - $row->pembulatan - $row->bayar,'0',',','.')?>
										</td>
										<td>
											<a target="_blank" href="<?=base_url().is_setting_link('finance/hutang_payment_form');?>?id=<?=$row->id?>" class='btn btn-xs yellow-gold'><i class='fa fa-search'></i></a>
										</td>
									</tr>
								<?}?>
							</tbody>
						</table>
						<hr/>
						<h4><b>- GIRO -</b></h4>
						<table class="table table-hover table-striped table-bordered" id="general_table_giro" style='font-size:1.1em'>
							<thead>
								<tr>								
									<th scope="col" class="text-center" style='width:50px'>
										NO
									</th>		
									<th style='display:none'>
									</th>
									<th scope="col" style='width:150px'>
										Tanggal Bayar
									</th>
									<th scope="col" class="text-right" style='width:200px'>
										Nilai
									</th>
									<th style='display:none'>
									</th>
									<th scope="col" class="text-center"  style='width:250px' >
										Jatuh Tempo
									</th>
									<th scope="col" class="text-center" >
										Tanggal Debet
									</th>
									<th scope="col"  style='width:100px'>
										No Giro
									</th>
									<th scope="col" style='width:200px'>
										SUM
									</th>
									<th scope="col">
										Actions
									</th>
								</tr>
							</thead>
							<tbody>
								<?
								$total_outstanding = 0;
								$total_kontra_bon = 0;
								$amount_bayar_kontra = 0;
								$date_diff_before = 0;
								$no = 1;
								$nama_hari = ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu" ];

								foreach ($outstanding_list_giro as $row) {
									$d2=strtotime(is_date_formatter($row->jatuh_tempo));
									$d1=strtotime(date('Y-m-d'));
									$diff = $d2-$d1;
									$date_diff = round($diff / (60 * 60 * 24));
									$line = '';

									if($date_diff_before != $date_diff){
										if ($date_diff_before != 0 ) {
											$line = "border-top:2px solid #999;";
										}
										$date_diff_before = $date_diff;
									}?>
									<tr style="<?=$line;?>; <?=($date_diff < 0 ? 'background:#ddd' : ($date_diff == 0 ? 'background:lightpink' : ''))?>" >
										<td class="text-center">
											<?=$no;?>
										</td>
										<td>
											<?=is_reverse_date($row->tanggal_transfer);?>
										</td>
										<td style='display:none'>
											<?=$row->tanggal_transfer;?> 
										</td>
										<td class="text-right">
											<b style='font-size:1.1em'>
												<?=number_format($row->amount,'0',',','.'); $amount_bayar_kontra += $row->amount;?>
											</b>
										</td>
										<td class="text-center">
											<b style='font-size:1.1em'>
												<small><?=$nama_hari[date('w',strtotime($row->jatuh_tempo))];?></small>,
												<?=is_reverse_date($row->jatuh_tempo);?>
											</b>
											(<?=($date_diff != 0 ? $date_diff.' hari' : 'hari ini');?>)
										</td>
										<td style="<?=( is_posisi_id()==1 ? 'display:none' : 'display:none'); ?>">
											<?=$row->jatuh_tempo;?>
										</td>
										<td style="width:130px">
											<!-- <div class="tanggal-cair" onclick="showTanggalInput('<?=$row->id;?>')">
												<?=($row->tanggal_cair == "" ? '<span style="font-size:0.8em; color:#777">isi tanggal</span>' : $row->tanggal_cair);?>
											</div> -->
											<?if (is_posisi_id() == 1) {?>
												<input id="tanggalInput-<?=$row->id;?>" class="date-picker-cair text-center"
												data-id="<?=$row->id?>"
												value="<?=($row->tanggal_cair == '' ? '' : (is_reverse_date($row->tanggal_cair)))?>"
												placeholder="isi tanggal"
												max="<?=date('Y-m-d')?>"/>
												<?$sh = ($row->tanggal_cair == '' ? "display:none" : "" );?>
												<i style="color:red; cursor:pointer; <?=$sh;?>" id="iconRemove-<?=$row->id?>" onclick="removeTanggal('<?=$row->id?>')" class="fa fa-times"></i>
											<?}?>
										</td>
										<td>
											<b><?=$row->no_giro;?></b>
										</td>
										<td class="sum-class-cell" style='cursor:pointer'>
											<input readonly type="checkbox" value="<?=$row->amount?>" data-nilai="<?=$row->amount;?>" class="sum-class" >
											<b class='nilai-selected'></b>
										</td>
										<td>
											<?=(is_posisi_id() == 1 ? $row->pembayaran_hutang_id : '');?>
											<a target='_blank' href="<?=base_url().is_setting_link('finance/hutang_payment_form')?>?id=<?=$row->pembayaran_hutang_id;?>" class="btn btn-xs yellow-gold"> <i class='fa fa-search'></i></a>
										</td>
									</tr>
								<? $no++;}?>

							</tbody>
						</table>
					</div>
					<div id="div-total">
						<table class='table' id="total-db">
							<tr style='font-size:1.5em; font-weight:bold'>
								<td class='text-right' style="width:200px">TOTAL</td>
								<td class="text-right" style='width:200px'><?=number_format($amount_bayar_kontra,'0',',','.');?></td>
								<td class="text-center" style='width:250px'></td>
								<td class="text-center" style='width:100px'><span class='selected'>SELECTED</span></td>
								<td style='width:200px'><span id="sumDiv" class='selected'>0</span></td>
								<td></td>
							</tr>

							<tr style='font-size:1.5em; font-weight:bold'>
								<td class='text-right' style="width:200px"><span class='selected'>SISA</span></td>
								<td class="text-right" style='width:200px'><span id="sisaDiv" class='selected'>0</span></td>
								<td class="text-center" style='width:250px'></td>
								<td class="text-center" style='width:100px'><span class='selected'></span></td>
								<td style='width:200px'><small class='selected'><span id="countDiv">0</span> / <?=$no-1;?> GIRO</small></td>
								<td></td>
							</tr>
						</table>
					</div>
					<div>
						<?if ($edit_mode == 1) {?>
							<button class='btn btn-lg btn-md green btn-save-form'>SAVE</button>
						<?}?>
						<a target='_blank' href="<?=base_url().is_setting_link('finance/outstanding_list');?>" class='btn btn-lg default'>BACK</a>
					</div>
				</div>
			</div>
		</div>
	</div>			
</div>

<script src="<?php echo base_url('assets/global/plugins/bootbox/bootbox.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/datatables/media/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets_noondev/js/table-advanced.js'); ?>"></script>

<script>

var sumTotal = 0;
var total_all = <?=$amount_bayar_kontra;?>;
var terpilih = 0;

jQuery(document).ready(function() {

	$("#general_table_giro").dataTable({
		"order": [[5,"asc"]],
		"pageLength": 100,
		"paging": true,			
	});

	$(".sum-class-cell").click(function(e) {
		// console.log(e.target.className);
		if (e.target.className === 'sum-class-cell') {
			const ini = $(this).find('.sum-class');
			if (ini.is(":checked")) {
				ini.prop('checked',false);
			}else{
				ini.prop('checked',true);
			}
			$.uniform.update(ini);
			updateSum(ini)
			
		}
	});
	$(".sum-class").click(function() {
		const ini = $(this);
		updateSum(ini)
	});

	$('.date-picker-cair').datepicker({
        autoclose : true,
        format: "dd/mm/yyyy"
    }).on('changeDate', function(ev) {
		console.log("change");
		const ini = ev.currentTarget;
		const id = ini.getAttribute("data-id");
		//Functionality to be called whenever the date is changed
		registerDateCair(ini,id)
		// onchange="registerDateCair(this,'')"
	});
});

function showTanggalInput(id){
	const inputId= `#tanggalInput-${id}`;
	$(inputId).show();
	
}

function updateSum(ini){
	const amount = ini.attr('data-nilai');
	if (ini.is(":checked")) {
		terpilih++;
		ini.closest('td').find('.nilai-selected').html(change_number_format(amount));
		sumTotal += parseFloat(amount);
	}else{
		terpilih--;
		ini.closest('td').find('.nilai-selected').html('');
		sumTotal -= parseFloat(amount);
	}
	const sisa = total_all - sumTotal;

	if (sumTotal == 0) {
		$(".selected").hide();
		$("#sumDiv").html(0);
	}else{
		$(".selected").show();
		$("#sumDiv").html(`${change_number_format(sumTotal)}`);
		$("#sisaDiv").html(`${change_number_format(sisa)}`);
		$("#countDiv").html(`${terpilih}`);
	}
}

async function registerDateCair(ini, id){
	let insertDate = '';
	if (ini.value != '') {
		insertDate = ini.value.split("/").reverse().join("-");
	}

	console.log(insertDate);

	const response = await fetch(baseurl+"finance/register_giro_cair", {
      method: "POST",
	  body:`tanggal=${insertDate}&id=${id}`,
	  headers: {
		'Content-Type': 'application/x-www-form-urlencoded',
		},
    });
    const result_data = await response.json();
	const bg = (insertDate != '' ? 'lightgreen' : 'white')
	if (result_data == "OK") {
		ini.style.backgroundColor  = bg;
		$(`#iconRemove-${id}`).show();
		notific8("lime","Tanggal Cair updated");
	}
}

async function removeTanggal(id){
	const insertDate = "";
	const ini = $(`input[data-id=${id}]`);
	const response = await fetch(baseurl+"finance/register_giro_cair", {
      method: "POST",
	  body:`tanggal=${insertDate}&id=${id}`,
	  headers: {
		'Content-Type': 'application/x-www-form-urlencoded',
		},
    });
    const result_data = await response.json();
	const bg = (insertDate != '' ? 'lightgreen' : 'transparent')

	if (result_data == "OK") {
		ini.css("background-color",bg);
		ini.val("");
		$(`#iconRemove-${id}`).hide();
		notific8("ruby","Tanggal Cair removed");
	}
}

</script>
