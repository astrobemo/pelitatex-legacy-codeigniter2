<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css'); ?>"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css'); ?>"/>
<?php echo link_tag('assets/global/plugins/bootstrap-datepicker/css/datepicker3.css'); ?>

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
							<div style='float:right; top:0px'>
								<?if ($edit_mode == 0) {?>
									<a class='btn btn-lg green' href="<?=base_url().is_setting_link('finance/outstanding_list_detail');?>?customer_id=<?=$customer_id?>&toko_id=<?=$toko_id?>&edit_mode=1">EDIT</a>
								<?}else{?>
									<a class='btn btn-lg blue' href="<?=base_url().is_setting_link('finance/outstanding_list_detail');?>?customer_id=<?=$customer_id?>&toko_id=<?=$toko_id?>">VIEW</a>
								<?}?>
							</div>
							<?foreach ($customer_data as $row) {?>
								<h1><?=$row->nama;?></h1>
							<?}?>
						</div>

						<br/>

						
						<?if ($edit_mode == 1) {?>
							<form action='<?=base_url()?>finance/piutang_list_detail_rearrange' id='form-edit-kontra' method='post'>
								<input name='customer_id' value="<?=$customer_id?>" hidden>
								<input name='toko_id' value="<?=$toko_id?>" hidden>
								<ul class='kontrabon_list' style='font-size:1.2em;'>
									<?foreach ($outstanding_list_detail as $row) { 
										$no_faktur = explode('??', $row->no_faktur);
										$data_kontra_bon = explode(',', $row->data_kontra_bon);
										$piutang_detail_id = explode(',', $row->piutang_detail_id);
										$pembayaran_piutang_detail_id_info = explode(',', $row->pembayaran_piutang_detail_id_info);
										$pembayaran_piutang_detail_id_info = array_flip($pembayaran_piutang_detail_id_info);

										?>
										<li><?=is_reverse_date($row->tanggal_kontra);?></li>
										<ul class='kontrabon_list_detail'  ondrop="drop(event)" ondragover="allowDrop(event)" id="list-<?=$row->id;?>" >
											<?foreach ($data_kontra_bon as $key => $value) {
												$bg_detail = '';
												if (isset($pembayaran_piutang_detail_id_info[$piutang_detail_id[$key]])) {
													$bg_detail = 'background:#ffaab7';
												}
												?>
											<li class="ui-state-default" style="<?=$bg_detail;?>;<?=($bg_detail =='' ? 'cursor:pointer' : '') ?>"  draggable="<?=($bg_detail =='' ? 'true' : 'false') ?>" class='no-drop' ondragstart="drag(event)" id="drag-<?=$piutang_detail_id[$key]?>" class='no-drop' >
												<span class="ui-icon ui-icon-arrowthick-2-n-s no-drop"></span><?=$no_faktur[$key];?> : 
													<b><?=number_format($value,'0',',','.');?></b>
												<input name='detail_id[<?=$piutang_detail_id[$key]?>]?>' id="input-<?=$piutang_detail_id[$key]?>" value="<?=$row->id;?>" hidden>
											</li>
											<?}?>
										</ul>
										<hr/>
									<?}?>
								</ul>
								<ul class='menu-list-detail-new'>
								</ul>
								<hr/>
							</form>
						<?}else{?>
							<table class="table table-hover table-striped table-bordered" id="general_table" style='font-size:1.1em'>
								<thead>
									<tr>									
										<th scope="col">
											Tanggal Kontra
										</th>
										<th scope="col">
											Jumlah Kontra
										</th>
										<th scope="col">
											Pelunasan
										</th>
										<th scope="col">
											Outstanding
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
									foreach ($outstanding_list_detail as $row) { 
										$no_faktur = explode('??', $row->no_faktur);
										$data_kontra_bon = explode(',', $row->data_kontra_bon);
										$piutang_detail_id = explode(',', $row->piutang_detail_id);
										$pembayaran_piutang_detail_id_info = explode(',', $row->pembayaran_piutang_detail_id_info);
										$pembayaran_piutang_detail_id_info = array_flip($pembayaran_piutang_detail_id_info);
										?>
										<tr style="<? if(isset($penjualan_id[$row->id])){echo 'background:#ddd';} ?>" >
											<td>
												<?=is_reverse_date($row->tanggal_kontra);?>
											</td>
											<td>
												<b style='font-size:1.1em'>
													<?=number_format($row->total_kontra_bon,'0',',','.'); $total_kontra_bon += $row->total_kontra_bon;?>
												</b>
												<div>
													<table>
														<?foreach ($data_kontra_bon as $key => $value) {
															$bg_detail = '';
															if (isset($pembayaran_piutang_detail_id_info[$piutang_detail_id[$key]])) {
																$bg_detail = 'background:#ffaab7';
															}
															?>
															<tr style="<?=$bg_detail;?>">
																<td></td>
																<td><?=$no_faktur[$key];?></td>
																<td style='text-align:center; width:20px;'>:</td>
																<td>
																	<?=number_format($value,'0',',','.');?>
																</td>
															</tr>
														<?}?>
													</table>
												</div>
											</td>
											<td>
												<b style='font-size:1.1em'>
													<?=number_format($row->amount_bayar_kontra,'0',',','.'); $amount_bayar_kontra += $row->amount_bayar_kontra;?>
												</b>
											</td>
											<td>
												<b style='font-size:1.1em'>
													<?=number_format($row->sisa_outstanding,'0',',','.'); $total_outstanding += $row->sisa_outstanding;?>
												</b>
											</td>
											<td>
												<a target='_blank' href="<?=base_url().is_setting_link('finance/piutang_payment_form')?>?id=<?=$row->id;?>" class="btn btn-xs yellow-gold"> <i class='fa fa-search'></i></a>
											</td>
										</tr>
									<?}?>
									<tr style='font-size:1.5em; font-weight:bold'>
										<td class='text-center'>TOTAL</td>
										<td><?=number_format($total_kontra_bon,'0',',','.');?></td>
										<td><?=number_format($amount_bayar_kontra,'0',',','.');?></td>
										<td><?=number_format($total_outstanding,'0',',','.');?></td>
										<td></td>
									</tr>
								</tbody>
							</table>
						<?}?>
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
function allowDrop(ev) {
  ev.preventDefault();
}

function drag(ev) {
  ev.dataTransfer.setData("text", ev.target.id);
}

function drop(ev) {
	ev.preventDefault();
	var data = ev.dataTransfer.getData("text");
	console.log(ev.currentTarget.id);
	console.log(data);

	var source_data = data.split('-');
	var source_id = source_data[1];

	var target_data = ev.currentTarget.id.split('-');
	var target_id = target_data[1]; 

	// console.log(source_id);
	ev.currentTarget.appendChild(document.getElementById(data));
	document.getElementById("input-"+source_id).value = target_id;
}
</script>

<script>
jQuery(document).ready(function() {


	$(".btn-save-form").click(function () {
		bootbox.confirm("Yakin memperbaharui kontrabon list ?", function(respond){
			if (respond) {
				$('#form-edit-kontra').submit();
			};
		});
	})
	// $( ".kontrabon_list_detail" ).sortable();
	// $( ".kontrabon_list_detail" ).disableSelection();

});
</script>
