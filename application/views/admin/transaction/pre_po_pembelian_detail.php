<link rel="stylesheet" type="text/css" href="<?=base_url('assets/global/plugins/bootstrap-select/bootstrap-select.min.css'); ?>"/>
<style type="text/css">
.barang-list-container, .barang-list-pool{
	height: 250px;
	overflow: auto;
	border:2px dashed #ddd;
}

.item-list li, .item-list-selected li{
	cursor: pointer;
	margin-bottom: 0px;
	padding: 3px 0 3px 25px; 
	font-size: 1.2em;
	list-style-type: none;
	border-bottom: 0.5px solid #eee;
}
.item-list, .item-list-selected{
	padding: 0px;
}

.item-list li:hover{
	background: #ddd;
}

.item-list-selected li:hover{
	background: lime;
}

.list-hidden{
	display: none;
}

</style>

<?foreach ($barang_list_selected as $row) {
	$barang_list[$row->barang_id][$row->warna_id] = $row;
}

$idx = 0;
foreach ($pre_po_list_barang as $row) {
	$barang_id_taken[$row->barang_id] = true;
	$nama_barang = ($idx == 0 ? $row->nama_barang : $nama_barang );
	$idx++;
}

$barang_id_terambil = "";
// print_r($barang_id_taken);
?>

<div class="page-content">
	<div class='container'>
		<div class="modal fade bs-modal-lg" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-body">
						<div class='row' style='padding:10px 30px;'>
							<h3 class='block'>Tambah Barang</h3>
							<div class='col-md-5'>
								<div class="barang-list-container">
									<ul class='item-list'>
										<?foreach ($this->barang_list_aktif_beli as $row) {
											$hidden = '';
											if (isset($barang_id_taken[$row->id])) {
												$barang_id_terambil .= "<li data-id='".$row->id."'>".$row->nama."</li>";
												$hidden = 'list-hidden';
											}?>
											<li class="<?=$hidden;?>" data-id="<?=$row->id?>"><?=$row->nama?></li>

										<?}?>
									</ul>
								</div>
							</div>
							<div class='col-md-1'>
								<div style='height:125px; padding-top:125px'>
									<i class='fa fa-arrow-right'></i>
								</div>
							</div>
							<div class='col-md-6'>
								<div class='barang-list-pool'>
									<ul class='item-list-selected'>
										<?=$barang_id_terambil;?>
									</ul>
								</div>
							</div>
						</div>
						<form id="form-add-barang" action="<?=base_url('transaction/pre_po_pembelian_detail_insert')?>" method="POST">
							<input name="pre_po_pembelian_id" value="<?=$pre_po_pembelian_id;?>" >
							<input name="barang_id">
						</form>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn blue btn-active btn-save">Save</button>
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
							<a href="#portlet-config" data-toggle='modal' class="btn btn-default btn-sm btn-form-add">
							<i class="fa fa-plus"></i> Tambah </a>
						</div>
					</div>
					<div class="portlet-body">
						<?/*$idx = 0;
						foreach ($pre_po_list_barang as $row) {?>
							<button style="font-weight:bold;<?=($idx==0 ? 'border:2px solid red' : 'border:2px solid' );?>" data-id="<?=$row->barang_id;?>" class="btn btn-md default btn-barang"><?=$row->nama_barang;?></button>
						<?$idx++;}*/
						?>
						<div>
							<select style="font-size:1.2em; width:350px;" id="barang_id_select" >
								<option value="">Pilih</option>
								<?foreach ($this->barang_list_aktif_beli as $row) {?>
									<option value="<?=$row->id?>"><?=$row->nama;?> / <?=(is_posisi_id() == 1 ? $row->nama_jual : '');?></option>
								<?}?>
							</select>
						</div>
						<hr/>
						<h2 style='font-weight:bold'> - <span class='nama_barang_tampil' ></span> -</h2>
						<table class="table table-striped table-bordered table-hover" id="general_table">
							<thead>
								<tr>
									<th scope="col">
										Warna
									</th>
									<th>
										STOK
									</th>
									<th>
										ROLL
									</th>
									<th>
										PO <br/>
										<small style="font-size:0.8em">belum terikirim</small>
									</th>
									<th colspan='2' class='text-center'>
										TOTAL
									</th>
									<th>
										Perkiraan Pesanan
									</th>
									<th>
										AKHIR
									</th>
									<th scope="col" style="min-width:150px !important">
										Actions
									</th>
								</tr>
							</thead>
							<?$idx = 0;
							foreach ($this->barang_list_aktif_beli as $baris) {?>
								<tbody class='data-warna' id="barang-<?=$baris->id;?>" hidden >
									<?if (isset($barang_list[$baris->id])) {?>
										<?foreach ($barang_list[$baris->id] as $key => $value) {
											$batch_data = explode('=?=', $value->batch);
											$qty_po_data = explode('=?=', $value->qty_po_data);
											$info = "";
											foreach ($batch_data as $key => $content) {
												if ($content != 0) {
													$batch = explode(',', $content) ;
													$qty_data = explode(',', $qty_po_data[$key]);
													$info .="PO : QTY<hr style='margin:0px; padding:0px;' />";
													foreach ($batch as $key2 => $content2) {
														$info .= $content2.' : '.(float)$qty_data[$key2].'<br/>';
													}
												}
											}
											?>
											<tr>
												<td><?=$value->nama_warna?></td>
												<td><b><?=number_format($value->qty_stok,'0',',','.');?></b></td>
												<td><?=$value->jumlah_roll_stok;?></td>
												<td><b><?=(float)$value->qty_po;?></b> 
													<?if ($info != '') {?>
														<a data-toggle="popover" class='btn btn-xs default' data-trigger='click' title="PO Gantung" data-html="true" data-content="<?=$info;?>"><i class='fa fa-info'></i></a>
													<?}?>
												</td>
												<?$qty_now=$value->qty_stok + $value->qty_po;?>
												<td class='text-right' style='border-right:0px; font-szie:1.1em'><?=number_format($qty_now,'0',',','.');?>
												</td>
												<td class='text-left' style='border-left:0px'><?=$row->nama_satuan?></td>
												<td>
													<input disabled name="qty_po[]">
												</td>
												<td></td>
												<td>
													<?if (is_posisi_id() == 1) {?>
														<?=$value->nama_barang?> // 
														<?=$value->nama_warna?>
													<?}?>
												</td>
											</tr>
										<?} ?>
									<?}else{?>
										<tr>
											<td colspan='6'>No Data</td>
										</tr>
									<?}?>
								</tbody>
							<?$idx++;}?>

						</table>
					</div>
				</div>
			</div>
		</div>
	</div>			
</div>


<script src="<?php echo base_url('assets/global/plugins/datatables/media/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets_noondev/js/table-advanced.js'); ?>"></script>

<script src="<?php echo base_url('assets/global/plugins/select2/select2.min.js'); ?>" type="text/javascript" ></script>

<script type="text/javascript" src="<?php echo base_url('assets/global/plugins/bootstrap-select/bootstrap-select.min.js'); ?>"></script>


<script>
jQuery(document).ready(function() {

	$('#barang_id_select').select2();

	$("#barang_id_select").change(function(){
    	var id = $(this).val();
    	$(".data-warna").hide();
    	$("#barang-"+id).show();
    	$(".nama_barang_tampil").html($("#barang_id_select :selected").text());
	});

	$('[data-toggle="popover"]').popover();


    $(".barang-list-container").on('click','.item-list li', function(){
    	var pointer = $(this);
    	var ini = $(this)[0];
    	console.log(ini);
    	$(".item-list-selected").prepend(`${ini.outerHTML}`);
    	pointer.addClass("list-hidden");
    });

    $(".btn-save").click(function(){
    	populate_item_list();
    	$("#form-add-barang").submit();
    });

    $(".btn-barang").click(function(){
    	var id = $(this).attr("data-id");
    	$(".data-warna").hide();
    	$("#barang-"+id).show();
    	$(".btn-barang").css("border","2px solid black");
    	$(this).css("border","2px solid red");
    	$(".nama_barang_tampil").html($(this).html());
    });
    

    $(document).on("click",".item-list-selected",function(){
    	var ini = $(this);
    	var id = $(this).attr("data-id");
    	// $(".barang-list-container").find("data-id");
    	ini.remove();
    });

});

function populate_item_list(){
	var barang_id = [];
	$(".item-list-selected li").each(function(){
		barang_id.push($(this).attr('data-id'));
	});
	$("#form-add-barang [name=barang_id]").val(barang_id.join());
}
</script>
