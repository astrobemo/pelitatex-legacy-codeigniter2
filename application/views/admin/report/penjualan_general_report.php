<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css'); ?>" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css'); ?>" />
<?php echo link_tag('assets/global/plugins/jqvmap/jqvmap/jqvmap.css'); ?>
<?php echo link_tag('assets/global/plugins/morris/morris.css'); ?>

<style type="text/css">
.highlightRow {
      background: #ffe0f3;
}

.nav-tabs li.active a {
      font-weight: bold !important;
      font-size: 1.1em
}
</style>
<div class="page-content">
      <div class='container'>

            <div class="modal fade bs-modal-lg" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                  <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                              <div class="modal-body">
                                    <button class='btn btn-md btn-show-data' data-target="0" style="background:#bae1ff">
                                          Barang </button>
                                    <button class='btn btn-md btn-show-data' data-target="1" style="background:#baffc9">
                                          Warna </button>
                                    <button class='btn btn-md btn-show-data' data-target="2" style="background:#ffffba">
                                          Barang + Warna </button>
                                    <button class='btn btn-md btn-show-data' data-target="3" style="background:#ffdfba">
                                          Customer </button>
                                    <hr style="margin:5px 0; padding:5px 0;" />

                                    <div id='barang-lain-container'>
                                          <table class="table table-bordered" id='barang-lain'>
                                                <thead>
                                                      <tr>
                                                            <th>NO</th>
                                                            <th>Barang</th>
                                                            <th>Qty</th>
                                                            <th>Transaksi</th>
                                                            <th></th>
                                                      </tr>
                                                </thead>
                                                <tbody></tbody>

                                          </table>
                                    </div>

                                    <div id='warna-lain-container'>
                                          <table class="table table-bordered" id='warna-lain'>
                                                <thead>
                                                      <tr>
                                                            <th>NO</th>
                                                            <th>Warna</th>
                                                            <th>Qty</th>
                                                            <th>Transaksi</th>
                                                            <th></th>
                                                      </tr>
                                                </thead>
                                                <tbody></tbody>

                                          </table>
                                    </div>

                                    <div id='barangwarna-lain-container'>
                                          <table class="table table-bordered" id='barangwarna-lain'>
                                                <thead>
                                                      <tr>
                                                            <th>NO</th>
                                                            <th>Barang</th>
                                                            <th>Qty</th>
                                                            <th>Transaksi</th>
                                                            <th></th>
                                                      </tr>
                                                </thead>
                                                <tbody></tbody>

                                          </table>
                                    </div>

                                    <div id='customer-lain-container'>
                                          <table class="table table-bordered" id='customer-lain'>
                                                <thead>
                                                      <tr>
                                                            <th>NO</th>
                                                            <th>Customer</th>
                                                            <th>Amount</th>
                                                            <th>Transaksi</th>
                                                            <th></th>
                                                      </tr>
                                                </thead>
                                                <tbody></tbody>

                                          </table>
                                    </div>
                              </div>

                              <div class="modal-footer">
                                    <button type="button" class="btn blue btn-active btn-trigger btn-edit-save">Save</button>
                                    <button type="button" class="btn btn-active default" data-dismiss="modal">Close</button>
                              </div>
                        </div>
                        <!-- /.modal-content -->
                  </div>
                  <!-- /.modal-dialog -->
            </div>


            <div class="modal fade bs-modal-full" id="portlet-config-detail" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                  <div class="modal-dialog modal-full">
                        <div class="modal-content">
                              <div class="modal-body">
                                    <div class='row'>
                                          <div id='data-detail-container' class='col-xs-6' style="padding:20px">
                                                <h3 id="detail-title"></h3>
                                                <hr />
                                                <div id="warna-detail-container">
                                                      <table class="table table-bordered" id='warna-detail'>
                                                            <thead>
                                                                  <tr>
                                                                        <th>NO</th>
                                                                        <th>Warna</th>
                                                                        <th>Qty</th>
                                                                        <th>Transaksi</th>
                                                                        <th></th>
                                                                  </tr>
                                                            </thead>
                                                            <tbody></tbody>
                                                      </table>
                                                </div>

                                                <div id='barang-detail-container'>
                                                      <table class="table table-bordered" id='barang-detail'>
                                                            <thead>
                                                                  <tr>
                                                                        <th>NO</th>
                                                                        <th>Barang</th>
                                                                        <th>Qty</th>
                                                                        <th>Transaksi</th>
                                                                        <th></th>
                                                                  </tr>
                                                            </thead>
                                                            <tbody></tbody>
                                                      </table>
                                                </div>
                                          </div>

                                          <div class='col-xs-6' style="padding:20px">
                                                <h3>Customer .<span id='nama-barang-detail'></span></h3>
                                                <hr />
                                                <table class="table table-bordered" id='customer-detail'>
                                                      <thead>
                                                            <tr>
                                                                  <th>NO</th>
                                                                  <th>Customer</th>
                                                                  <th>Amount</th>
                                                                  <th>Transaksi</th>
                                                            </tr>
                                                      </thead>
                                                      <tbody></tbody>
                                                </table>
                                          </div>
                                    </div>

                              </div>

                              <div class="modal-footer">
                                    <button type="button" class="btn blue btn-active btn-trigger btn-edit-save">Save</button>
                                    <button type="button" class="btn btn-active default" data-dismiss="modal">Close</button>
                              </div>
                        </div>
                        <!-- /.modal-content -->
                  </div>
                  <!-- /.modal-dialog -->
            </div>

            <div class="modal fade" id="portlet-config-customer" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                  <div class="modal-dialog">
                        <div class="modal-content">
                              <div class="modal-body">
                                    <h3>Customer .<span id='nama-barang-detail2'></span></h3>
                                    <hr />
                                    <table class="table table-bordered" id='customer-detail2'>
                                          <thead>
                                                <tr>
                                                      <th>NO</th>
                                                      <th>Customer</th>
                                                      <th>Amount</th>
                                                      <th>Transaksi</th>
                                                </tr>
                                          </thead>
                                          <tbody></tbody>
                                    </table>
                              </div>

                              <div class="modal-footer">
                                    <button type="button" class="btn blue btn-active btn-trigger btn-edit-save">Save</button>
                                    <button type="button" class="btn btn-active default" data-dismiss="modal">Close</button>
                              </div>
                        </div>
                        <!-- /.modal-content -->
                  </div>
                  <!-- /.modal-dialog -->
            </div>

            <div class="row margin-top-10">
                  <div class="col-md-12">
                        <div class="portlet light ">
                              <div class="portlet-title">
                                    <div class="caption caption-md">
                                          <i class="icon-bar-chart theme-font hide"></i>
                                          <span class="caption-subject theme-font bold uppercase">LAPORAN PENJUALAN HARIAN</span>
                                    </div>

                                    <div class="actions">
                                          <form>
                                                <table>
                                                      <tr>
                                                            <td>Tanggal</td>
                                                            <td>&nbsp;:&nbsp;</td>
                                                            <td>
                                                                  <input name='tanggal_start' class='date-picker text-center' style='width:100px' id='tanggal-start' value="<?= $tanggal_start ?>">
                                                            </td>
                                                            <td>&nbsp;s/d&nbsp;</td>
                                                            <td>
                                                                  <input name='tanggal_end' class='date-picker text-center' style='width:100px' id='tanggal-end' value="<?= $tanggal_end ?>">
                                                            </td>
                                                            <td>
                                                                  &nbsp;<button class='btn btn-xs default'>OK</button>
                                                            </td>
                                                      </tr>
                                                </table>
                                          </form>
                                    </div>
                              </div>

                              <div class="portlet-body">
                                    <div class="row list-separated text-center">
                                          <div class="col-md-6 col-sm-6 col-xs-6">
                                                <div class="font-grey-mint font-sm">
                                                      Pembelian
                                                </div>

                                                <div class="uppercase font-hg font-purple">
                                                      <?php foreach ($recap_pembelian_bulanan as $row) { ?>
                                                      Rp <?= format_angka($row->amount) ?>
                                                      <?php } ?>
                                                </div>
                                          </div>
                                          <div class="col-md-6 col-sm-6 col-xs-6">
                                                <div class="font-grey-mint font-sm">
                                                      Penjualan
                                                </div>

                                                <div class="uppercase font-hg font-blue-sharp">
                                                      <?php foreach ($recap_penjualan_bulanan as $row) { ?>
                                                      Rp <?= format_angka($row->amount) ?>
                                                      <?php } ?>
                                                </div>
                                          </div>
                                    </div>

                                    <hr />

                                    <div class="row list-separated">
                                          <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <h4 class="text-center"><b>GRAFIK PENJUALAN HARIAN</b></h4>

                                                <div id="sales_statistics" class="portlet-body-morris-fit morris-chart" style="height: 200px; padding:20px;"></div>
                                          </div>
                                    </div>
                              </div>
                        </div>

                        <div class="portlet light ">
                              <div class="portlet-title">
                                    <div class="caption caption-md">
                                          <i class="icon-bar-chart theme-font hide"></i>
                                          <span class="caption-subject theme-font bold uppercase">LAPORAN PENJUALAN TAHUNAN</span>
                                    </div>

                                    <div class="actions">
                                          <form>
                                                <table>
                                                      <tr>
                                                            <td>Tahun</td>
                                                            <td>&nbsp;:&nbsp;</td>
                                                            <td>
                                                                  <select id='tahun' name='tahun'>
                                                                        <? for ($i = 2019; $i <= date('Y'); $i++) { ?>
                                                                        <option <?= ($tahun == $i ? 'selected' : '') ?> value="<?= $i ?>"><?= $i; ?></option>
                                                                        <? } ?>
                                                                  </select>
                                                            </td>
                                                            <td>
                                                                  &nbsp;<button class='btn btn-xs default'>OK</button>
                                                            </td>
                                                      </tr>
                                                </table>
                                          </form>
                                    </div>
                              </div>

                              <div class="portlet-body">
                                    <div class="row list-separated">
                                          <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <h4 class="text-center"><b>GRAFIK PENJUALAN DAN PEMBELIAN</b></h4>
                                                <div id="chart_1" class="chart" style="height: 200px;"></div>
                                          </div>
                                    </div>
                              </div>
                        </div>

                        <div class="portlet light">
                              <div class="portlet-title">
                                    <div class="caption caption-md">
                                          <i class="icon-bar-chart theme-font hide"></i>
                                          <span class="caption-subject theme-font bold uppercase">LAPORAN TAHUNAN</span>
                                    </div>

                                    <div class="actions">
                                          <form>
                                                <table>
                                                      <tr>
                                                            <td>Tahun</td>
                                                            <td>&nbsp;:&nbsp;</td>
                                                            <td>
                                                                  <select id='tahun_2' name='tahun_2'>
                                                                        <? for ($i = 2019; $i <= date('Y'); $i++) { ?>
                                                                        <option <?= ($tahun_2 == $i ? 'selected' : '') ?> value="<?= $i ?>"><?= $i; ?></option>
                                                                        <? } ?>
                                                                  </select>
                                                            </td>
                                                            <td>
                                                                  &nbsp;<button class='btn btn-xs default'>OK</button>
                                                            </td>
                                                      </tr>
                                                </table>
                                          </form>
                                    </div>
                              </div>

                              <div class="portlet-body">
                                    <div class="tabbable tabbable-custom">
                                          <ul class="nav nav-tabs">
                                                <li class="active">
                                                      <a href="#barang-tab" data-toggle="tab" style="background:#bae1ff">
                                                            Barang
                                                      </a>
                                                </li>
                                                <li class="">
                                                      <a href="#warna-tab" data-toggle="tab" style="background:#baffc9">
                                                            Warna
                                                      </a>
                                                </li>
                                                <li class="">
                                                      <a href="#barang-warna-tab" data-toggle="tab" style="background:#ffffba">
                                                            Barang + Warna
                                                      </a>
                                                </li>
                                                <li class="">
                                                      <a href="#customer-tab" data-toggle="tab" style="background:#ffdfba">
                                                            Customer
                                                      </a>
                                                </li>
                                          </ul>

                                          <div class="tab-content">
                                                <div class="tab-pane active" id="barang-tab">
                                                      <div class="row list-separated">
                                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                  <a href="#portlet-config" data-toggle='modal' class='btn btn-default barang-lain' style="float:right">Daftar Lengkap</a>
                                                                  <h4 class='text-center'><b>10 PENJUALAN BARANG TERBANYAK <?= $tahun_2; ?></b> </h4>

                                                                  <div id="chart_2" class="chart" style="height: 250px;"></div>

                                                                  <hr />
                                                            </div>

                                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                  <div id="chart_3" class="chart" style="height: 400px;"></div>
                                                            </div>
                                                      </div>
                                                </div>

                                                <div class="tab-pane active" id="warna-tab">
                                                      <div class="row list-separated">
                                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                  <a href="#portlet-config" data-toggle='modal' class='btn btn-default warna-lain' style="float:right">Daftar Lengkap</a>
                                                                  <h4 class="text-center"><b>10 PENJUALAN WARNA TERBANYAK <?= $tahun_2; ?></b></h4>

                                                                  <div id="chart_warna_1" class="chart" style="height: 200px;"></div>

                                                                  <hr />
                                                            </div>

                                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                  <div id="chart_warna_2" class="chart" style="height: 400px;"></div>
                                                            </div>
                                                      </div>
                                                </div>

                                                <div class="tab-pane active" id="barang-warna-tab">
                                                      <div class="row list-separated">
                                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                  <a href="#portlet-config" data-toggle='modal' class='btn btn-default barangwarna-lain' style="float:right">Daftar Lengkap</a>
                                                                  <h4 class="text-center"><b>10 PENJUALAN BARANG DAN WARNA TERBANYAK <?= $tahun_2; ?></b></h4>

                                                                  <div id="chart_bw" class="chart" style="height: 250px;"></div>
                                                                  <hr />
                                                            </div>

                                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                  <div id="chart_bw_pie" class="chart" style="height: 400px;"></div>
                                                            </div>
                                                      </div>
                                                </div>

                                                <div class="tab-pane active" id="customer-tab">
                                                      <div class="row list-separated">
                                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                  <a href="#portlet-config" data-toggle='modal' class='btn btn-default customer-lain' style='float:right'>Daftar Lengkap</a>
                                                                  <h4 class="text-center"><b>10 PELANGGAN TERBAIK <?= $tahun_2; ?></b></h4>

                                                                  <div id="chart_4" class="chart" style="height: 250px;"></div>

                                                                  <hr />
                                                            </div>

                                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                  <div id="chart_5" class="chart" style="height: 400px;"></div>
                                                            </div>
                                                      </div>
                                                </div>
                                          </div>
                                    </div>
                              </div>
                        </div>
                  </div>
            </div>
      </div>
</div>

<script src="<?= base_url('assets/global/plugins/morris/morris.min.js'); ?>" type="text/javascript"></script>
<script src="<?= base_url('assets/global/plugins/morris/raphael-min.js'); ?>" type="text/javascript"></script>


<script src="<?= base_url('assets/global/plugins/amcharts/amcharts/amcharts.js'); ?>" type="text/javascript"></script>
<script src="<?= base_url('assets/global/plugins/amcharts/amcharts/serial.js'); ?>" type="text/javascript"></script>
<script src="<?= base_url('assets/global/plugins/amcharts/amcharts/themes/light.js'); ?>" type="text/javascript"></script>
<script src="<?= base_url('assets/global/plugins/amcharts/amcharts/pie.js'); ?>" type="text/javascript"></script>

<script src="<?php echo base_url('assets/global/plugins/datatables/media/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js'); ?>" type="text/javascript"></script>

<script src="<?= base_url('assets_noondev/js/charts-amcharts.js'); ?>"></script>
<script src="<?= base_url('assets_noondev/js/index3.js'); ?>" type="text/javascript"></script>

<script>
$(document).ready(function() {
      //$("#sidebar").load("sidebar.html"); 
      // Metronic.init(); // init metronic core componets
      // Layout.init(); // init layout
      // Index.init(); // init index page
      // ChartsAmcharts.init();
      generateChartBulanan();

      $(".barang-lain").click(function() {
            $("#portlet-config").find(".btn-show-data").css("font-weight", 'normal');
            $("#portlet-config").find("[data-target=0]").css("font-weight", 'bold');
            $("#warna-lain-container").hide();
            $("#customer-lain-container").hide();
            $("#barangwarna-lain-container").hide();
            $("#barang-lain-container").show();
      });


      $(".warna-lain").click(function() {
            $("#portlet-config").find(".btn-show-data").css("font-weight", 'normal');
            $("#portlet-config").find("[data-target=1]").css("font-weight", 'bold');
            $("#customer-lain-container").hide();
            $("#barang-lain-container").hide();
            $("#barangwarna-lain-container").hide();
            $("#warna-lain-container").show();
      });

      $(".barangwarna-lain").click(function() {
            $("#portlet-config").find(".btn-show-data").css("font-weight", 'normal');
            $("#portlet-config").find("[data-target=3]").css("font-weight", 'bold');
            $("#warna-lain-container").hide();
            $("#customer-lain-container").hide();
            $("#barang-lain-container").hide();
            $("#barangwarna-lain-container").show();
      });

      $(".customer-lain").click(function() {
            $("#portlet-config").find(".btn-show-data").css("font-weight", 'normal');
            $("#portlet-config").find("[data-target=4]").css("font-weight", 'bold');
            $("#warna-lain-container").hide();
            $("#barang-lain-container").hide();
            $("#barangwarna-lain-container").hide();
            $("#customer-lain-container").show();
      });

      let tahun = $("#tahun_2").val();

      let barang_count = 0;
      let barang_qty_total = 0;
      let barang_trx_total = 0;
      $('#barang-lain').DataTable({
            "fnCreatedRow": function(nRow, aData, iDataIndex) {
                  if (iDataIndex < 10) {
                        $(nRow).addClass('highlightRow');
                  };
                  barang_count++;
                  barang_qty_total += parseFloat(reset_number_format($('td:eq(2)', nRow).html()));
                  barang_trx_total += parseFloat($('td:eq(3)', nRow).html());
                  let nama_barang = $('td:eq(1)', nRow).html();;
                  let barang_id = $('td:eq(4)', nRow).html();
                  $('td:eq(4)', nRow).html(`<a href="#portlet-config-detail" data-toggle="modal" class="btn btn-xs default" onclick="generateWarnaByBarang('${barang_id}','${nama_barang}')">warna</a>`);
            },
            "ajax": baseurl + 'admin/get_barang_jual_terbanyak_get?tahun=' + tahun + '&tipe=2',
            "language": {
                  decimal: ",",
                  thousand: "."
            },
            "initComplete": function() {
                  let table_rekap = `<table class='table' style="font-size:1.2em">
        		<tr>
	        		<th class='text-center'>Barang</th>
	        		<th class='text-center'>Total Qty</th>
        		</tr>
        		<tr>
	        		<td class='text-center'>${barang_count}</td>
	        		<td class='text-center'>${change_number_format(barang_qty_total)}</td>
        		</tr>
        	</table>`;

                  $("#barang-lain-container").append(table_rekap);
            }
      });

      let warna_count = 0;
      let warna_qty_total = 0;
      let warna_trx_total = 0;
      $('#warna-lain').DataTable({
            "fnCreatedRow": function(nRow, aData, iDataIndex) {
                  if (iDataIndex < 10) {
                        $(nRow).addClass('highlightRow');
                  };
                  warna_count++;
                  warna_qty_total += parseFloat(reset_number_format($('td:eq(2)', nRow).html()));
                  warna_trx_total += parseFloat($('td:eq(3)', nRow).html());
                  let nama_warna = $('td:eq(1)', nRow).html();;
                  let warna_id = $('td:eq(4)', nRow).html();
                  $('td:eq(4)', nRow).html(`<a href="#portlet-config-detail" data-toggle="modal" class="btn btn-xs default" onclick="generateBarangByWarna('${warna_id}','${nama_warna}')">barang</a>`);
            },
            "ajax": baseurl + 'admin/get_barang_jual_warna_terbanyak_get?tahun=' + tahun + '&tipe=2',
            "language": {
                  decimal: ",",
                  thousand: "."
            },
            "initComplete": function() {
                  let table_rekap = `<table class='table' style="font-size:1.2em">
        		<tr>
	        		<th class='text-center'>Warna</th>
	        		<th class='text-center'>Total Qty</th>
        		</tr>
        		<tr>
	        		<td class='text-center'>${warna_count}</td>
	        		<td class='text-center'>${change_number_format(warna_qty_total)}</td>
        		</tr>
        	</table>`;

                  $("#warna-lain-container").append(table_rekap);
            }
      });

      let barangwarna_count = 0;
      let barangwarna_qty_total = 0;
      let barangwarna_trx_total = 0;
      $('#barangwarna-lain').DataTable({
            "fnCreatedRow": function(nRow, aData, iDataIndex) {
                  if (iDataIndex < 10) {
                        $(nRow).addClass('highlightRow');
                  };
                  barangwarna_count++;
                  barangwarna_qty_total += parseFloat(reset_number_format($('td:eq(2)', nRow).html()));
                  barangwarna_trx_total += parseFloat($('td:eq(3)', nRow).html());
                  let barang_data = $('td:eq(4)', nRow).html().split('??');
                  let nama = $('td:eq(1)', nRow).html();
                  let barang_id = barang_data[0];
                  let warna_id = barang_data[1];
                  $('td:eq(4)', nRow).html(`<a href="#portlet-config-customer" data-toggle="modal" class="btn btn-xs default" onclick="generateCustomerByWarnaBarang(${barang_id}, ${warna_id}, '', 3,'${nama}')">customer</a>`);
            },
            "ajax": baseurl + 'admin/get_barang_warna_jual_terbanyak_get?tahun=' + tahun + '&tipe=2',
            "language": {
                  decimal: ",",
                  thousand: "."
            },
            "initComplete": function() {
                  let table_rekap = `<table class='table' style="font-size:1.2em">
        		<tr>
	        		<th class='text-center'>Barang+Warna</th>
	        		<th class='text-center'>Total Qty</th>
        		</tr>
        		<tr>
	        		<td class='text-center'>${barangwarna_count}</td>
	        		<td class='text-center'>${change_number_format(barangwarna_qty_total)}</td>
        		</tr>
        	</table>`;

                  $("#barangwarna-lain-container").append(table_rekap);
            }
      });

      let customer_count = 0;
      let customer_qty_total = 0;
      let customer_trx_total = 0;
      let cust_count_b10 = 0;
      let cust_count_10 = 0;
      let cust_count_20 = 0;
      let cust_count_30 = 0;
      let cust_count_40 = 0;
      let cust_count_50 = 0;
      let cust_count_a50 = 0;
      let link = "<?= is_setting_link('master/customer_profile'); ?>"
      $('#customer-lain').DataTable({
            "fnCreatedRow": function(nRow, aData, iDataIndex) {
                  if (iDataIndex < 10) {
                        $(nRow).addClass('highlightRow');
                  };
                  let trx = $('td:eq(3)', nRow).html();
                  customer_count++;
                  customer_qty_total += parseFloat(reset_number_format($('td:eq(2)', nRow).html()));
                  customer_trx_total += parseFloat(trx);

                  let customer_id = $('td:eq(4)', nRow).html();
                  $('td:eq(4)', nRow).html(`<a href="${baseurl}${link}/${customer_id}?tahun=${tahun}" data-toggle="modal" class="btn btn-xs default">customer</a>`);

                  if (trx <= 10) {
                        cust_count_10++;
                  } else if (trx <= 20) {
                        cust_count_20++;
                  } else if (trx <= 30) {
                        cust_count_30++;
                  } else if (trx <= 40) {
                        cust_count_40++;
                  } else if (trx <= 50) {
                        cust_count_50++;
                  } else {
                        cust_count_a50++;
                  };
            },
            "ajax": baseurl + 'admin/get_customer_beli_terbanyak_get?tahun=' + tahun + '&tipe=2',
            "language": {
                  decimal: ",",
                  thousand: "."
            },
            "initComplete": function() {
                  let table_rekap = `<table class='table' id="customer-table" style="font-size:1.2em">
        		<tr>
	        		<th class='text-center'>Customer</th>
	        		<th class='text-center'>Total Qty</th>
	        		<th class='text-center'>Total Transaksi</th>
        		</tr>
        		<tr>
	        		<td class='text-center'>${customer_count}</td>
	        		<td class='text-center'>${change_number_format(customer_qty_total)}</td>
	        		<td class='text-center'>${change_number_format(customer_trx_total)}</td>
        		</tr>
        	</table>`;

                  $("#customer-lain-container").append(table_rekap);

                  getNonCust();

                  let table_count = `<table class='table' id="customer-table" style="font-size:1.2em">
        		<tr>
	        		<th class='text-center'>< 10</th>
	        		<th class='text-center'>11-20</th>
	        		<th class='text-center'>21-30</th>
	        		<th class='text-center'>31-40</th>
	        		<th class='text-center'>41-50</th>
	        		<th class='text-center'>> 50</th>
        		</tr>
        		<tr>
	        		<td class='text-center'>${cust_count_10}</td>
	        		<td class='text-center'>${cust_count_20}</td>
	        		<td class='text-center'>${cust_count_30}</td>
	        		<td class='text-center'>${cust_count_40}</td>
	        		<td class='text-center'>${cust_count_50}</td>
	        		<td class='text-center'>${cust_count_a50}</td>
        		</tr>
        	</table>`;
                  $("#customer-lain-container").append(table_count);

            }
      });

      function getNonCust() {
            var data_st = {};
            data_st['tahun'] = tahun;
            var url = "admin/get_non_customer_totalbeli";

            ajax_data_sync(url, data_st).done(function(data_respond /*,textStatus, jqXHR*/ ) {
                  let jml_trx = 0;
                  let amount = 0;
                  $.each(JSON.parse(data_respond), function(k, v) {
                        jml_trx = v.jml_transaksi;
                        amount = v.amount;
                  });

                  let table_rekap = `<tr>
	        		<th class='text-center'>Keterangan</th>
	        		<th class='text-center'>Amount</th>
	        		<th class='text-center'>Jumlah Transaksi</th>
        		</tr>
        		<tr>
	        		<td class='text-center'>Non Customer</td>
	        		<td class='text-center'>${change_number_format(parseFloat(amount))}</td>
	        		<td class='text-center'>${change_number_format(jml_trx)}</td>
        		</tr>`;

                  $("#customer-table").append(table_rekap);


            });
      }


      //================================modal==================================
      let data_tab = ['#barang-lain-container', '#warna-lain-container', '#barangwarna-lain-container', '#customer-lain-container']
      $(".btn-show-data").click(function() {
            $(".btn-show-data").css("font-weight", 'normal');
            $(this).css("font-weight", 'bold');
            let idx = $(this).attr("data-target");
            for (var i = 0; i < data_tab.length; i++) {
                  if (i == idx) {
                        $(data_tab[i]).show();
                  } else {
                        $(data_tab[i]).hide();
                  };
            };
      })

});


function generateWarnaByBarang(barang_id, nama_barang) {
      let tahun = $("#tahun").val();
      $('#detail-title').text(nama_barang);

      $("#barang-detail-container").hide();
      $("#warna-detail-container").show();
      oTable = $('#warna-detail').DataTable();
      oTable.state.clear();
      oTable.destroy();

      oTable = $('#customer-detail').DataTable();
      oTable.clear().draw();
      // oTable.destroy();

      $('#warna-detail').DataTable({
            "fnCreatedRow": function(nRow, aData, iDataIndex) {
                  // if (iDataIndex <10) {
                  // 	$(nRow).addClass('highlightRow');
                  // };
                  // barang_count++;
                  //       barang_qty_total += parseFloat(reset_number_format($('td:eq(2)', nRow).html()));
                  //       barang_trx_total += parseFloat($('td:eq(3)', nRow).html());
                  let warna_id = $('td:eq(4)', nRow).html();
                  let idx = $('td:eq(0)', nRow).html();
                  let nama = $('td:eq(1)', nRow).html();
                  // console.log(nRow);
                  $('td:eq(4)', nRow).html(`<button class="btn btn-xs default" onclick="generateCustomerByWarnaBarang('${barang_id}','${warna_id}','${idx}','1', '${nama}')">customer</button>`);
            },
            "ajax": baseurl + `admin/get_warna_terbanyak_by_barang?tahun=${tahun}&barang_id=${barang_id}`,
            "language": {
                  decimal: ",",
                  thousand: "."
            },
            "bLengthChange": false
      });
}

function generateBarangByWarna(warna_id, nama_warna) {
      let tahun = $("#tahun_2").val();
      $('#detail-title').text(nama_warna);

      $("#warna-detail-container").hide();
      $("#barang-detail-container").show();
      oTable = $('#barang-detail').DataTable();
      oTable.state.clear();
      oTable.destroy();

      oTable = $('#customer-detail').DataTable();
      oTable.clear().draw();
      // oTable.destroy();

      $('#barang-detail').DataTable({
            "fnCreatedRow": function(nRow, aData, iDataIndex) {
                  let barang_id = $('td:eq(4)', nRow).html();
                  let idx = $('td:eq(0)', nRow).html();
                  let nama = $('td:eq(1)', nRow).html();
                  $('td:eq(4)', nRow).html(`<button class="btn btn-xs default" onclick="generateCustomerByWarnaBarang('${barang_id}','${warna_id}','${idx}','2', '${nama}')">customer</button>`);
            },
            "ajax": baseurl + `admin/get_barang_terbanyak_by_warna?tahun=${tahun}&warna_id=${warna_id}`,
            "language": {
                  decimal: ",",
                  thousand: "."
            },
            "bLengthChange": false
      });
}

function generateCustomerByWarnaBarang(barang_id, warna_id, idx, tipe, nama) {
      let tahun = $("#tahun_2").val();
      idx = idx % 10;

      if (tipe == 1) {
            $("#warna-detail tr").removeClass('highlightRow');
            $(`#warna-detail tr:eq(${idx})`).addClass('highlightRow');
      } else if (tipe == 2) {
            $("#barang-detail tr").removeClass('highlightRow');
            $(`#barang-detail tr:eq(${idx})`).addClass('highlightRow');
      }
      let tbl = "#customer-detail";

      if (tipe == 3) {
            tbl = "#customer-detail2";
            $("#nama-barang-detail2").text(nama);
      } else {
            $("#nama-barang-detail").text(nama);
      }

      oTable = $(tbl).DataTable();
      oTable.state.clear();
      oTable.destroy();

      $(tbl).DataTable({
            "fnCreatedRow": function(nRow, aData, iDataIndex) {
                  // if (iDataIndex <10) {
                  // 	$(nRow).addClass('highlightRow');
                  // };
                  // barang_count++;
                  //       barang_qty_total += parseFloat(reset_number_format($('td:eq(2)', nRow).html()));
                  //       barang_trx_total += parseFloat($('td:eq(3)', nRow).html());
                  let warna_id = $('td:eq(4)', nRow).html();
                  let nama_customer = $('td:eq(1)', nRow).html();
                  if (nama_customer == 'non customer') {
                        $('td:eq(1)', nRow).html(`<span style="background:#000; color:#fff">no customer</span>`);
                  };
                  $('td:eq(4)', nRow).html(`<button class="btn btn-xs default" onclick="generateCustomerByBarang('${barang_id}','${warna_id}')">customer</button>`);
            },
            "ajax": baseurl + `admin/get_customer_terbanyak_by_barang_warna?tahun=${tahun}&barang_id=${barang_id}&warna_id=${warna_id}`,
            "language": {
                  decimal: ",",
                  thousand: "."
            }
      });
}

/**
==========================================generate rekapan per bulan====================
**/

function generateChartBulanan() {
      let tahun = $("#tahun").val();
      console.log(tahun);
      var url = "admin/get_penjualan_pembelian_tahun?tahun=" + tahun;
      var data_st = {};
      var chartData = [];
      var j = 0;

      ajax_data_sync(url, data_st).done(function(data_respond, textStatus, jqXHR) {
            // console.log(data_respond);
            $.each(JSON.parse(data_respond), function(k, v) {
                  chartData[j] = {
                        'month': tahun + "-" + v.bulan,
                        'penjualan': v.amount_jual,
                        'pembelian': v.amount_beli
                  }
                  j++;
            });
            console.log(chartData);
            let dt_trx = ["penjualan", "pembelian"];


            let config_nilai = {
                  element: 'chart_1',
                  data: chartData,
                  xkey: 'month',
                  ykeys: ['penjualan', 'pembelian'],
                  labels: ['penjualan', 'pembelian'],
                  fillOpacity: 0.6,
                  hideHover: 'auto',
                  // stacked: true,
                  barColors: ["#0b62a4", "#7a92a3"],
                  // behaveLikeLine: true,
                  resize: true
            };

            Morris.Bar(config_nilai);
      });
}
</script>
<!-- END JAVASCRIPTS -->