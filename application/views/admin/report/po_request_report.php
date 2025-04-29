<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css'); ?>"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css'); ?>"/>
<?php echo link_tag('assets/global/plugins/bootstrap-datepicker/css/datepicker3.css'); ?>
<script src="<?php echo base_url('assets/global/plugins/bootbox/bootbox.min.js'); ?>" type="text/javascript"></script>

<style>

.performance-bar-title{
    /* height:30px;
    border-right:1px solid #ddd;
    padding:5px;
    width:300px; */
    position: relative;
    display:inline-block;
}

.performance-bar{
    /* height:30px;
    border:1px solid #ddd;
    padding:5px;
    width:300px; */
    position: relative;
    display:inline-block;
}

.datang-bar-container, .datanglain-bar-container{
    /* width:130px; 
    border-right:1px solid #ddd;*/
    position: relative;
    display:inline-block;
    top:0px;
    left:0px;
    padding:8px 0 0 5px;
}

.requested-bar{
    /* height:30px;
    padding:5px;
    width:130px;
    border-right:1px solid #ddd; */
    position: relative;
    display:inline-block;
}

.request-bar{
    background:rgba(200,200,200,0.4);
    position:absolute;
    left:-5px;
    top:-5px;
    height:30px;
}

.datang-bar{
    height:35px;
    position:absolute;
    left:0px;
    top:0px;
    background:rgba(0,200,200,0.4);
}

.datanglain-bar{
    height:35px;
    position:absolute;
    left:0px;
    top:0px;
    background:rgba(255,255,0,0.4);
}

.non-request-bar{
    display:inline-block;
    /* width:100px; */
    padding:0px;
    position: relative;
}

.non-req-bar{
    /* height:30px; */
    position:absolute;
    left:-5px;
    top:-5px;
    background:rgba(255,255,0,0.4);
}

#general_table tbody th{
	position: -webkit-sticky;
	position: sticky;
	background: #fff;
	top: 50px;
	z-index: 99;
	min-height: 50px;
	border-bottom: 2px solid #ddd;
	background: #eee;

	-webkit-full-screen{
		top: 0px;
	}
}

.bg-awal{
	background-color:#bfffc6;
}
.bg-lain{
	background-color:#ffffc7;
}
.bg-nr{
	background-color:#ffe5e3;
}
.bg-request{
    background-color:#eee;
}
.bg-total{
    background-color:#e6faea;
}

.venn-diagram{
	position: relative;
	height:130px;
	text-align:center;
	margin:auto;
	width:200px;
}

.venn-diagram .pie{
	position: absolute;
	margin:auto; 
	border-radius:50%;
	/* margin-left: auto;
	margin-right: auto; */
}
</style>
<div class="page-content">
	<div class='container'>
    
    <div class="modal fade bs-modal-lg" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-body">
						<div class="row">
                            <div class="col-xs-12 col-md-6"></div>
                            <div class="col-xs-12 col-md-6"></div>
                        </div>
					</div>

					<div class="modal-footer">
						<button type="button" class="btn blue btn-active btn-trigger btn-save">Save</button>
						<button type="button" class="btn default  btn-active" data-dismiss="modal">Close</button>
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
							<select class='btn btn-sm btn-default' name='status_aktif_select' id='status_aktif_select'>
								<option value="1" selected>Aktif</option>
								<option value="0">Tidak Aktif</option>
							</select>
							<!-- <a href="#portlet-config" data-toggle='modal' class="btn btn-default btn-sm btn-form-add">
							<i class="fa fa-plus"></i> Tambah </a> -->

							<div class="btn-group">
								<a class="btn btn-default btn-sm" href="#portlet-config" data-toggle='modal'>
									<i class="fa fa-plus"></i> Tambah
								</a>
							</div>
						</div>
					</div>
					<div class="portlet-body">
                        <?//print_r($request_list);?>
                        <div style="position:relative">
                            <form action="" id="form-filter">
                                RANGE BY : 
                                <label><input <?=($tipe == 1 ? 'checked' : '');?> type="radio" onchange="submitFormFilter()" name="tipe" id="tipe1" value='1'>By Tanggal <b>Surat Jalan</b></label>
                                <label><input <?=($tipe == 2 ? 'checked' : '');?> type="radio" onchange="submitFormFilter()" name="tipe" id="tipe2" value='2'>By Tanggal <b>Input</b></label>
                            </form>
                            <div style='position:absolute;height:100%; width:100%; top:0; left:0; background:#999;opacity:0.5; font-size:1.2em;'
                                id='form-filter-loader' 
                                class='text-center' hidden>loading....</div>
                        </div>
                        <hr>
						<table class="table table-striped table-bordered table-hover" id="general_table">
							<thead>
								<!-- status_aktif','tanggal_order', 'nama_sales','surat_jalan', 'tanggal','nama_customer','data' -->
								<tr>
									<th scope="col" --rowspan='2'>
										NO REQUEST
									</th>
									<th scope="col" class='text-center' --rowspan='2'>
                                        BARANG
                                    </th>
									<th scope="col" colspan='5' class='text-center' style="border-bottom:1px solid #ddd">
										REQUEST UTAMA
									</th>
									<th scope="col" colspan='4' class='text-center' style="border-bottom:1px solid #ddd">
										NEXT
									</th>
									<th scope="col" --rowspan='2' class='text-center'style="border-left:1px solid #ddd">
										Chart
									</th>
									<th scope="col" --rowspan='2' class='text-center'>
										Action
									</th>
								</tr>
							</thead>
							<tbody>
                                <?foreach ($request_list as $row) {
                                    
                                    $rid = $row->request_barang_id;
                                    //break barang_id
                                    $barang_id = explode("??", $row->barang_id);
                                    
                                    $qty_request = explode("??", $row->qty_request);
                                    // hasilnya array request per barang, group warna dipisahkan by koma (,), 
                                    // tapi udah total tiap warna, ga per bulan
                                    $qty_request_perbulan = explode("??", $row->qty_data_request_perbulan);
                                    
                                    // hasilnya array request per barang, group warna dipisahkan by koma (,), 
                                    // tapi dipisahkan request per bulan, group by ||
                                    $qty_data_request_perbulan = explode("??", $row->qty_data_request_perbulan);

                                    // hasilnya array qty datang per barang, tapi totalan
                                    $qty_datang = explode("??", $row->qty_datang);
                                    
                                    // hasilnya array bulan request per barang, group per warna dipisahkan koma (,)
                                    // group per bulan dipisahkan by ||, 
                                    // nanti di link sama qty_request_perbulan
                                    $bulan_request = explode("??", $row->bulan_request);

                                    $bulan_request_awal = $row->bulan_request_awal;
                                    //==============data detail==================

                                    // hasilnya jadi array warna per barang, group warna nya dipisahkan by koma (,)
                                    $warna_id = explode("??", $row->warna_id);

                                    // hasilnya jadi array warna non request per barang, group warna nya dipisahkan by koma (,)
                                    $warna_id_nr = explode("-?-", $row->warna_id_no_request);
                                    
                                    // hasilnya array qty datang per barang, 
                                    //group warna dipisahkan by koma, tapi total all bulan
                                    $qty_data_datang = explode("??", $row->qty_datang);

                                    // hasilnya array qty datang per barang, masih group by warna,  group warna by koma (,), 
                                    // juga array per datang dipisahkan by ||
                                    // klo dia ga ada nilai nya 0
                                    $qty_datang_detail = explode("??", $row->qty_datang_detail);

                                    // hasilnya array bulan datang per barang, masih group by warna, group warna by koma (,)
                                    // juga masih di pisahkan by tanggal, group tanggal by ||
                                    // nanti di lik sama qty_datang_detail
                                    // klo dia ga ada nilai nya 'x'
                                    $bulan_datang = explode("??", $row->bulan_datang);
                                    
                                    $qty_datang_no_request = explode("??", $row->qty_datang_no_request);
                                    $qty_data_datang_no_request = explode("??", $row->qty_data_datang_no_request);
                                    $total_request_all = [];
                                    
                                    if (!isset($request_awal_total[$rid])) {
                                        $request_awal_total[$rid] = 0;
                                        $request_lain_total[$rid] = 0;
                                        $datang_awal_total[$rid] = 0;
                                        $datang_lain_total[$rid] = 0;
                                        $datang_noreq_total[$rid] = 0;
                                    }
                                    
                                    foreach ($barang_id as $key => $value) {

                                        //===simplify variable name===
                                        //short barang_id
                                        $bid = $barang_id[$key];
                                        //short warna_id list
                                        $wid = $warna_id[$key];
                                        //request_id
                                        $rid = $row->request_barang_id;
                                        //short bulan_datang, inget masih group by warna, by tanggal kedatangan ya
                                        $bdr = $bulan_datang[$key];
                                        //bulan_request
                                        $brq = $bulan_request[$key];

                                        // breakdown bulanan tiap request
                                        $bulan_req_arr = explode(",", $brq);
                                        // breakdown tiap warna
                                        $warna_id_arr = explode(",", $wid);
                                        // breakdown tiap request warna
                                        $req_arr_perbulan = explode(",", $qty_data_request_perbulan[$key]);

                                        // breakdown datang per warna
                                        $qty_datang_arr = explode(",", $qty_datang_detail[$key]);
                                        // breakdown bulan datang per warna
                                        $bulan_datang_arr = explode(",", $bulan_datang[$key]);

                                        if (!isset($request_awal[$rid][$bid])) {
											$request_awal[$rid][$bid] = 0;
											$request_lain[$rid][$bid] = 0;
										}
                                        foreach ($warna_id_arr as $key2 => $value2) {

                                            if (!isset($request_awal_warna[$rid][$bid][$value2])) {
												# code...
												$request_awal_warna[$rid][$bid][$value2] = 0;
												$request_lain_warna[$rid][$bid][$value2] = 0;
											}

                                            $isRequestWarna = false;
                                            // initialize total reqeust per bulan
                                            // jadi kita akan bikin total request per barang saja
                                            // soalnya klo per warna udah ada data di brq
                                            // tapi akan di group per bulan

                                            //1. breakdown tiap qty_request_perbulan
                                            $qrb = explode("||", $req_arr_perbulan[$key2]);
                                            //1. breakdown tiap bulan request
                                            $brq = explode("||",$bulan_req_arr[$key2]);
                                            //1. breakdown tiap qty_datang per warna
                                            $qda = explode("||", $qty_datang_arr[$key2]);
                                            //1. breakdown tiap bulan per warna
                                            $bda = explode("||", $bulan_datang_arr[$key2]);

                                            // echo $rid;
                                            // print_r($qda);

                                            foreach ($qrb as $key3 => $value3) {
                                                if (!isset($total_request[$rid][$brq[$key3]][$bid])) {
                                                    //total request per request per bulan per barang
                                                    $total_request[$rid][$bid][$brq[$key3]] = 0;
                                                    //total datang barang non request per tempo per barang
                                                    $total_datang_no_request[$rid][$bid][$brq[$key3]] = 0;
                                                    //total datang per request per bulan per barang
                                                    $total_datang[$rid][$bid][$brq[$key3]] = 0;

                                                    
                                                    if ($brq[$key3] == $bulan_request_awal && !isset($request_awal[$rid][$bid])) {
                                                        $request_awal[$rid][$bid] = 0;
                                                    }else if(!isset($request_lain[$rid][$bid])){
                                                        $request_lain[$rid][$bid] = 0;
                                                    }

                                                    //total datang per request per awal banget per barang dan next

                                                }



                                                if (!isset($total_request_all[$rid][$brq[$key3]])) {
                                                    $total_request_all[$rid][$brq[$key3]] = 0;
                                                }

                                                if ($brq[$key3] == $bulan_request_awal) {
                                                    $request_awal[$rid][$bid] += $value3;
                                                    $request_awal_total[$rid] += $value3;
													$request_awal_warna[$rid][$bid][$value2] += $value3;
                                                    $isRequestWarna = true;
                                                }else{
                                                    $request_lain[$rid][$bid] += $value3;
                                                    $request_lain_total[$rid] += $value3;
													$request_lain_warna[$rid][$bid][$value2] += $value3;
                                                    $isRequestWarna = true;
                                                }

                                                //add setiap qty request per request per warna per bulan
                                                // hasilnya nanti request tiap request per barang per bulan
                                                $total_request[$rid][$bid][$brq[$key3]] += $value3;
                                                
                                                // add setiap qty request per request per bulan
                                                // hasilnya nanti request tiap request semua barang per bulan
                                                $total_request_all[$rid][$brq[$key3]] += $value3;
                                            }

                                            foreach ($qda as $key3 => $value3) {
                                                // initialisasi udah di atas di breakdown request
                                                // karena data per barang per warna dan per bulan udah di breakdown di atas
                                                // echo $bid.' '.$value2.' '.$value3.'<hr/>';
                                                if (!isset($datang_awal[$rid][$bid])) {
                                                    $datang_awal[$rid][$bid] = 0;
                                                }

                                                if (!isset($datang_lain[$rid][$bid])) {
                                                    $datang_lain[$rid][$bid] = 0;
                                                }

                                                if (!isset($datang_noreq[$rid][$bid])) {
                                                    $datang_noreq[$rid][$bid] = 0;
                                                }

                                                //====================================================
												if (!isset($datang_awal_warna[$rid][$bid][$value2])) {
													$datang_awal_warna[$rid][$bid][$value2] = 0;
												}
												if (!isset($datang_lain_warna[$rid][$bid][$value2])) {
													$datang_lain_warna[$rid][$bid][$value2] = 0;
												}
												if (!isset($datang_nr_warna[$rid][$bid][$value2])) {
													$datang_nr_warna[$rid][$bid][$value2] = 0;
												}


                                                if (date("Y-m", strtotime($bda[$key3])) == date("Y-m", strtotime($bulan_request_awal)) && isset($request_awal[$rid][$bid]) ) {
                                                    if ($datang_awal_warna[$rid][$bid][$value2] + $value3 <= $request_awal_warna[$rid][$bid][$value2]) {
                                                        $datang_awal[$rid][$bid] += $value3;
                                                        $datang_awal_total[$rid] += $value3;
														$datang_awal_warna[$rid][$bid][$value2] += $value3;
                                                    }else{
                                                        $sisa = $datang_awal_warna[$rid][$bid][$value2] + $value3 - $request_awal_warna[$rid][$bid][$value2];
                                                        
                                                        $datang_awal[$rid][$bid] += $value3 - $sisa;
                                                        $datang_awal_total[$rid] += $value3 - $sisa;
														$datang_awal_warna[$rid][$bid][$value2] += $value3 - $sisa;

                                                        if (!isset($datang_lain[$rid][$bid]) ) {
                                                            $datang_lain[$rid][$bid] = 0;
															$datang_lain_warna[$bid][$value2] = 0;
                                                        }

                                                        if ($datang_lain_warna[$rid][$bid][$value2] + $sisa <= $request_lain_warna[$rid][$bid][$value2]) {
															$datang_lain_warna[$rid][$bid][$value2] += $sisa;
															$datang_lain[$rid][$bid] += $sisa;
															$datang_lain_total[$rid] += $sisa;
														}else{
															$sisa_nr = $datang_lain_warna[$rid][$bid][$value2] + $sisa - $request_lain_warna[$rid][$bid][$value2];

															$datang_lain_warna[$rid][$bid][$value2] += $sisa - $sisa_nr;
															$datang_lain[$rid][$bid] += $sisa - $sisa_nr;
															$datang_lain_total[$rid] += $sisa - $sisa_nr;

															$datang_nr_warna[$rid][$bid][$value2] += $sisa_nr;
															$datang_noreq[$rid][$bid] += $sisa_nr;
															$datang_noreq_total[$rid] += $sisa_nr;
														}
                                                    }
                                                }
                                                

                                                if (!isset($request_awal[$rid][$bid]) && !$isRequestWarna && $request_awal[$rid][$bid] == 0 && $value3 != 0 ) {
                                                    $datang_noreq[$rid][$bid] += $value3;
                                                    // echo $bid.' '.$value2.'<br/>';
                                                    $datang_noreq_total[$rid] += $value3;

                                                    // echo date("Y-m", strtotime($bda[$key3])) .'=='. date("Y-m", strtotime($bulan_request_awal)).'<br/>';
                                                    // echo $isRequestWarna.'<br/>';
                                                    // echo $datang_noreq_total[$rid].'<hr/>';
                                                }
                                            }   
                                        }

                                        //=======barang tidak request================
                                        // print_r($warna_id_nr[$key]);
                                        // echo '<br/>';
                                        $wid_nr = explode(",", $warna_id_nr[$key]);
                                        $qty_d_nr = explode(",", $qty_data_datang_no_request[$key]);

                                        // echo '<br/>'.$rid.' '.$bid;
                                        // print_r($wid_nr);
                                        // print_r($qty_d_nr);
                                        foreach ($wid_nr as $key2 => $value2) {
                                            if (!isset($datang_noreq[$rid][$bid])) {
                                                $datang_noreq[$rid][$bid] = 0;
                                                $datang_noreq_total[$rid] = 0;
                                            }

                                            $datang_noreq[$rid][$bid] += $qty_d_nr[$key2];
                                            $datang_noreq_total[$rid] += $qty_d_nr[$key2];
                                            
                                        }
                                        
                                        // echo '<hr/>';

                                    }
                                }
                                // print_r($tqd);
                                ?>
								<?foreach ($request_list as $row) { 
                                    $barang_id = explode("??", $row->barang_id);
                                    $warna_id = explode("??", $row->warna_id);
                                    $nama_barang = explode("??", $row->nama_barang);
                                    $nama_warna = explode("??", $row->nama_warna);
                                    $qty_data_request = explode("??", $row->qty_data_request);
                                    $qty_data_datang = explode("??", $row->qty_data_datang);
                                    
                                    $bulan_request = explode("??", $row->bulan_request);
                                    $qty_request = explode("??", $row->qty_request);
                                    $qty_datang = explode("??", $row->qty_datang);
                                    
                                    $qty_datang_no_request = explode("??", $row->qty_datang_no_request);
                                    $nama_warna_no_request = explode("??", $row->nama_warna_no_request);
                                    $qty_data_datang_no_request = explode("??", $row->qty_data_datang_no_request);
                                    
                                    foreach ($nama_barang as $key => $value) {
                                        $warna = explode(",",$nama_warna[$key]);
                                        $wid = explode(",",$warna_id[$key]);
                                        $request = explode(",",$qty_data_request[$key]);
                                        $datang = explode(",",$qty_data_datang[$key]);

                                        //bulan_request
                                        $brq = $bulan_request[$key];
                                        // breakdown bulanan tiap request
                                        $bulan_req_arr = explode(",", $brq);

                                        //======================warna no request=======================
                                        $warna_nr = array(); $datang_nr=array();
                                        if ($nama_warna_no_request[$key] != '') {
                                            $warna_nr = explode(",", $nama_warna_no_request[$key]);
                                            $datang_nr = explode(",", $qty_data_datang_no_request[$key]);
                                        }
                                        //==============================================================
                                        if ($qty_request[$key] != 0) {
                                            $datang_bar = $qty_datang[$key]/$qty_request[$key];
                                        }else{
                                            $datang_bar = 1;
                                        }
                                        $pembagi = $qty_datang_no_request[$key]+$qty_datang[$key];
                                        $pembagi = ($pembagi == 0 ? 1 : $pembagi);
                                        $persen_non_request = $qty_datang_no_request[$key]/$pembagi;
                                        $persen_non_request = number_format($persen_non_request,'2','.','');
                                        $req_bar = ($qty_datang[$key] > 0 ? 1-$persen_non_request : 0 );

                                        $qdtg = 0;
                                        $qdtg_next = 0;
                                        foreach ($wid as $key2 => $value2) {
                                            if (isset($dtg[$row->request_barang_id][$barang_id[$key]][$value2])) {
                                                $qdtg += $dtg[$row->request_barang_id][$barang_id[$key]][$value2];
                                            }
                                            if (isset($qdtg_next[$row->request_barang_id][$barang_id[$key]][$value2])) {
                                                $qdtg_next += $dtg_next[$row->request_barang_id][$barang_id[$key]][$value2];
                                            }
                                        }
                                        
                                        $r_awal = 0;
                                        $r_next = 0;
                                        $d_awal = 0;
                                        $d_next = 0;
                                        $noreq_q = 0;

                                        if (isset($request_awal[$row->request_barang_id][$barang_id[$key]])) {
                                            $r_awal = $request_awal[$row->request_barang_id][$barang_id[$key]];
                                        }

                                        if (isset($request_lain[$row->request_barang_id][$barang_id[$key]])) {
                                            $r_next = $request_lain[$row->request_barang_id][$barang_id[$key]];
                                        }

                                        if (isset($datang_awal[$row->request_barang_id][$barang_id[$key]])) {
                                            $d_awal = $datang_awal[$row->request_barang_id][$barang_id[$key]];
                                        }

                                        if (isset($datang_lain[$row->request_barang_id][$barang_id[$key]])) {
                                            $d_next = $datang_lain[$row->request_barang_id][$barang_id[$key]];
                                        }

                                        if (isset($datang_noreq[$row->request_barang_id][$barang_id[$key]])) {
                                            $noreq_q = $datang_noreq[$row->request_barang_id][$barang_id[$key]];
                                        }

                                        $persen_request = ($qty_datang[$key] > 0 ? 1-$persen_non_request : 0);

                                        

                                        $p_req = 0;
                                        if ($r_awal != 0) {
                                            $p_req = round($d_awal / $r_awal, 2) ;
                                            $r_awal_print = number_format($r_awal,'0',',','.');
                                        }else{
                                            $r_awal_print = '-';
                                        }

                                        $p_req_next = 0;
                                        if ($r_next != 0) {
                                            $p_req_next = round($d_next / $r_next, 2) ;
                                        }
                                        $subtotal = $d_awal + $d_next + $noreq_q;
                                        
                                        ?>

                                        <?if ($key == 0) {?>
                                            <tr>
                                                <th rowspan="<?=count($nama_barang)+3;?>" style='font-size:1.2em;'>
                                                    <a target="_blank" href="<?=base_url().is_setting_link('transaction/request_barang')?>?request_barang_id=<?=$row->request_barang_id?>">
                                                        <b><?=$row->no_request_lengkap?></b>
                                                    </a>
                                                </th>
                                                <th>
                                                    BATCH AWAL :
                                                   <b hidden><?=strtoupper(date("d F Y", strtotime($row->tanggal_request_awal)));?></b>
                                                </th>
                                                <th colspan='5' class='text-center'>
                                                    <b><?=date("F Y", strtotime($row->bulan_request_awal));?></b> 
                                                </th>
                                                <th colspan='4' class='text-center'>
                                                    LAIN
                                                </th>
                                                <td class='text-center' rowspan="<?=count($nama_barang)+3;?>" style="border-left:1px solid #ddd; vertical-align:middle">
                                                    <?
                                                        //karena pengen di kanan jadi di reverse
                                                        $total_all = 0;
                                                        $total_datang_no_request = 0;
                                                        // $total_all = $total_datang[$row->request_barang_id][$barang_id[$key]] + 
                                                                // $total_datang_no_request[$row->request_barang_id][$barang_id[$key]];
                                                        if ($total_all != 0) {
                                                            $total_req_persen = number_format($total_datang[$row->request_barang_id][$barang_id[$key]]/$total_all,'2','.','');
                                                        }else{
                                                            $total_req_persen = 1;
                                                        }

                                                        $total_all = $datang_awal_total[$row->request_barang_id] + $datang_lain_total[$row->request_barang_id] + $datang_noreq_total[$row->request_barang_id];
                                                        
                                                        $total_awal_persen = number_format($datang_awal_total[$row->request_barang_id]/$total_all,'4','.','');
                                                        $total_lain_persen = number_format($datang_lain_total[$row->request_barang_id]/$total_all,'4','.','');
                                                        $total_nr_persen = number_format($datang_noreq_total[$row->request_barang_id]/$total_all,'4','.','');
                                                        
                                                        $awal_persen = $total_awal_persen*360;
                                                        $lain_persen = $total_lain_persen*360;
                                                        $nr_persen = $total_nr_persen*360;
                                                        $rid = $row->request_barang_id;
                                                    ?>
                                                    
                                                    <div class='pie' style="margin:auto;margin-bottom:20px; width:100px; height:100px; border-radius:50%; background-image: conic-gradient(#35aa47 <?=$awal_persen?>deg, #ffff91 <?=$awal_persen?>deg <?=$awal_persen+$lain_persen;?>deg, #ffbcb8 <?=$nr_persen;?>deg);"></div>

                                                    <table style='margin:auto'>
                                                        <tr style="background-color:#35aa47; color:white">
                                                            <td class="text-right" hidden>UTAMA</td>
                                                            <td hidden> :</td>
                                                            <td class="text-right"><?=number_format($datang_awal_total[$rid],'0',',','.'); ?></td>
                                                            <td class="text-right;" style="padding-left:10px;"><?=$total_awal_persen * 100?>%
                                                            </td>
                                                        </tr>
                                                        <tr style="background-color:#ffff91">
                                                            <td class="text-right" hidden>SELANJUTNYA</td>
                                                            <td hidden> : </td>
                                                            <td class="text-right"><?=number_format($datang_lain_total[$rid],'0',',','.'); ?></td>
                                                            <td class="text-right"><?=$total_lain_persen * 100?>%</td>
                                                        </tr>
                                                        <tr style="background-color:#fcdedc">
                                                            <td class="text-right" hidden>NON REQUEST</td>
                                                            <td hidden> : </td>
                                                            <td class="text-right"><?=number_format($datang_noreq_total[$rid],'0',',','.'); ?></td>
                                                            <td class="text-right"><?=$total_nr_persen * 100?>%
                                                            </td>
                                                        </tr>
                                                        <tr style="background-color:#ddd; font-weight:bold">
                                                        <td class="text-right"  hidden>TOTAL</td>
                                                            <td hidden> : </td>
                                                            <td class="text-right"><?=number_format($total_all,'0',',','.'); ?></td>
                                                            <td class="text-right" style='padding-left:10px'>100%</td>
                                                        </tr>
                                                    </table>

                                                    <?$g_total = $datang_awal_total[$row->request_barang_id] + $datang_lain_total[$row->request_barang_id] + $datang_noreq_total[$row->request_barang_id];?>
                                                    <div class="venn-diagram">
                                                        <?
                                                            $rTotal = $request_awal_total[$row->request_barang_id];
                                                            $sisi = ($rTotal > $g_total ? $rTotal : $g_total);
                                                            $part_request = round($rTotal / $sisi,2);
                                                            $part_datang = round($g_total / $sisi,2);
                                                            $persen_total = $g_total/$rTotal;

                                                            //width
                                                            $w_request = $part_request * 100;
                                                            $w_datang = $part_datang * 100;

                                                            //top margin
                                                            $t_request = 20 + ((100-$w_request)/2);
                                                            $t_datang = 20 + ((100-$w_datang)/2);
                                                            
                                                            //left/right
                                                            //klo request kiri klo datang kanan
                                                            $l_request = 100 - $w_request;
                                                            $r_datang = 100 - $w_datang;
                                                            
                                                            $offset = ($persen_total*100)/2;
                                                            $l_request += $offset;
                                                            $r_datang += $offset;


                                                        ?>
                                                        <div class='pie' style="width:<?=$w_request;?>px;height:<?=$w_request?>px;top:<?=$t_request?>px;left:<?=$l_request?>px;background-color:rgba(255,0,0,0.3) "></div>
                                                        <div class='pie' style="width:<?=$w_datang;?>px;height:<?=$w_datang?>px;top:<?=$t_datang?>px;right:<?=$r_datang;?>px;background-color:rgba(0,0,255,0.3) "></div>
                                                        <?=$offset;?>
                                                    </div>

                                                    <div style='position:relative; width:250px; height:45px; margin:auto'>
                                                        <div style="position:absolute;text-align:left; top:0px;height:40px;width:165px;padding-left:10px;left:0px;background-color:rgba(255,0,0,0.3)">Request<br/> Total</div>
                                                        <div style="position:absolute;text-align:center; top:0px;height:40px;width:75px;left:88px; background:transparent;"><b>Pengiriman<br/> Tepat</b></div>
                                                        <div style="position:absolute;text-align:right; top:0px;height:40px;width:165px;padding-right:10px;right:0px;background-color:rgba(0,0,255,0.3);">Pengiriman<br/> Total</div>
                                                    </div>
                                                </td>
                                                <td rowspan="<?=count($nama_barang)+3;?>">
                                                    <a href="<?=base_url().is_setting_link('report/po_request_report_detail')?>?id=<?=$row->request_barang_id;?>" class='btn btn-xs yellow-gold' target='_blank'><i class='fa fa-search'></i></a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th></th>
                                                <th class='text-center'>REQUEST</th>
                                                <th class='text-center'>DATANG</th>
                                                <th class='text-center'>%</th>
                                                <th class='text-center'>ON REQUEST</th>
                                                <th class='text-center'>%</th>
                                                <th class='text-center'>REQUEST</th>
                                                <th class='text-center'>MASUK</th>
                                                <th class='text-center'>%</th>
                                                <th class='text-center'>NO REQUEST</th>
                                            </tr>
                                        <?}?>
                                        <tr style="<?=($key%5==0 ? 'border-top:2px solid #aaa' : '');?>">
                                            <td>
                                                <div>
                                                    <div class='performance-bar'>
                                                        <?=$value?> <small><?//=number_format($datang_bar*100,'2','.',',').'%';?></small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class='bg-request text-right'>
                                                <div style="float:right"><?=$r_awal_print; ?> </div>
                                            </td>
                                            <td class='bg-total text-right'>
                                                <?=str_replace(',00','',number_format($subtotal,'2',',','.')) ;?>
                                            </td>
                                            <td class='bg-total text-center'>
                                                <?
                                                    $pt = 0;
                                                    if ($r_awal_print > 0) {
                                                        $pt = $subtotal/$r_awal;
                                                    }
                                                ?>
                                                <?=str_replace(',00','',number_format($pt*100,'2',',','.')) ;?>
                                            </td>
                                            <!-- style='padding:0px; overflow:hidden; position:relative'  -->
                                            <td class='bg-awal text-right' >
                                                <div style='display:none' class='datang-bar-container'>
                                                    <div class='datang-bar' style='display:inline; width:<?=$p_req*105;?>px;' >
                                                    </div>
                                                </div>
                                                <?=str_replace(',00','',number_format($d_awal,'2',',','.')) ;?>
                                            </td>
                                            <td class='bg-awal text-center'><?=($p_req)*100?>%</td>
                                            <td class="bg-lain ">
                                                <div style="float:right"><?=number_format($r_next,'0',',','.'); ?> </div> 
                                            </td>
                                            <td class="bg-lain " style='padding:0px; overflow:hidden'>
                                                <div class='datang-bar-container'>
                                                    <div class='datanglain-bar'  style='display:inline; width:<?=$p_req_next*105;?>px;' >
                                                    </div>
                                                    <?=str_replace(',00','',number_format($d_next,'2',',','.')) ;?> 
                                            </div>
                                            </td>
                                            <td class='bg-lain text-center'><?=$p_req_next*100;?>%</td>
                                            <td class="bg-nr">
                                                <?=str_replace(',00','',number_format($noreq_q,'2',',','.')) ;?>
                                            </td>

                                        </tr>
                                        <?
                                            

                                            /**
                                            ===============================================================================
                                            **/

                                            // print_r($total_request[$row->request_barang_id][$barang_id[$key]]);
                                            // echo ',,'.$barang_id[$key].',,'.$wid[$key].'<br/>';
                                            if (isset($wid[$key]) && isset($barang_id[$key]) && isset($total_request[$row->request_barang_id][$barang_id[$key]][$wid[$key]])) {
                                                $tr = $total_request[$row->request_barang_id][$barang_id[$key]][$wid[$key]];
                                                $tr = ($tr == 0 ? 1 : $tr);
                                                $datang_bar_all = $total_datang[$row->request_barang_id][$barang_id[$key]][$warna_id[$key]]/$tr;
                                            }
                                            // echo "<hr/>";
                                        ?>
                                    <?}?>
                                    <?
                                        $persen = 0;
                                        if ($request_awal_total[$row->request_barang_id] != 0) {
                                            $persen = $datang_awal_total[$row->request_barang_id]/$request_awal_total[$row->request_barang_id];
                                        }
                                        $persen_lain = 0;
                                        if ($request_lain_total[$row->request_barang_id] !=0) {
                                            $persen_lain = $datang_lain_total[$row->request_barang_id]/$request_lain_total[$row->request_barang_id];
                                        }  
                                    ?>
                                    <tr style='font-size:1.1em; border-top:2px solid #aaa; font-weight:bold'>
                                        <td>
                                            <div>
                                                <div class='performance-bar'>
                                                    <div class='datang-bar' style='display:inline; width:<?=$datang_bar_all*200;?>px;'></div>
                                                    TOTAL
                                                </div>
                                            </div>
                                        </td>
                                        <td class='bg-request text-center'>
                                            <div><?=number_format($request_awal_total[$row->request_barang_id],'0',',','.'); ?> </div> 
                                        </td>
                                        <td class='bg-total text-center'>
                                            <div><?=number_format($datang_awal_total[$row->request_barang_id] + $datang_lain_total[$row->request_barang_id] + $datang_noreq_total[$row->request_barang_id],'0',',','.'); ?> </div> 
                                        </td>
                                        <td class='bg-total text-center'><?=(float)number_format($g_total/$request_awal_total[$row->request_barang_id] *100,'2',".","");?>%</td>
                                        <td class='bg-awal text-center'>
                                            <div><?=number_format($datang_awal_total[$row->request_barang_id],'0',',','.'); ?> </div> 
                                        </td>
                                        <td class='bg-awal text-center'><?=(float)number_format($persen*100,'2',".","");?>%</td>
                                        <td class='bg-lain text-center'>
                                                    <?=str_replace(',00','',number_format($request_lain_total[$row->request_barang_id],'2',',','.')) ;?>
                                        </td>
                                        <td class='bg-lain text-center'>
                                            <?=str_replace(',00','',number_format($datang_lain_total[$row->request_barang_id],'2',',','.')) ;?> 
                                        </td>
                                        <td class='bg-lain text-center'><?=(float)number_format($persen_lain*100,'2',".","");?>%</td>

                                        <td class='bg-nr text-center'>
                                            <div>
                                                <?=str_replace(',00','',number_format($datang_noreq_total[$row->request_barang_id],'2',',','.')) ;?>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan='9'></td>
                                    </tr>
								<? } ?>

							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>			
</div>

<script src="<?php echo base_url('assets/global/plugins/datatables/media/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/datatables/extensions/ColReorder/js/dataTables.colReorder.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets_noondev/js/table-advanced.js'); ?>"></script>


<script src="<?php echo base_url('assets/global/plugins/bootstrap-select/bootstrap-select.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/select2/select2.min.js'); ?>" type="text/javascript" ></script>

<script>
jQuery(document).ready(function() {

	// $('#general_table').dataTable();


});

function submitFormFilter(){
    $("#form-filter").submit();
    $("#form-filter-loader").show();
}
</script>
