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

.datang-bar-container{
    /* width:130px; 
    border-right:1px solid #ddd;*/
    position: relative;
    display:inline-block;
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
    background:rgba(0,200,200,0.4);
    position:absolute;
    left:-5px;
    top:-5px;
    /* height:30px; */
}

.datang-bar{
    /* height:30px; */
    position:absolute;
    left:0px;
    top:0px;
    background:rgba(200,200,200,0.4);
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
						<table class="table table-striped table-bordered table-hover" id="general_table">
							<thead>
								<!-- status_aktif','tanggal_order', 'nama_sales','surat_jalan', 'tanggal','nama_customer','data' -->
								<tr>
									<th scope="col" rowspan='2'>
										NO REQUEST
									</th>
                                    <th scope="col" rowspan='2'>
										BULAN
									</th>
									<th scope="col" class='text-center' rowspan='2'>
                                        NAMA
                                    </th>
									<th scope="col" colspan='2' class='text-center' style="border-bottom:1px solid #ddd">
										REQUEST
									</th>
									<th scope="col" colspan='2' class='text-center' style="border-bottom:1px solid #ddd">
										MASUK
									</th>
									<th scope="col" rowspan='2' class='text-center'style="border-left:1px solid #ddd">
										Chart
									</th>
									<th scope="col" rowspan='2' class='text-center'>
										Action
									</th>
								</tr>
                                <tr>
                                    <th scope="col" class='text-center'>
                                        UTAMA
                                    </th>
                                    <th scope="col" class='text-center'>
                                        OTHER
                                    </th>
                                    <th scope="col" class='text-center'>
                                        REQUEST
                                    </th>
                                    <th scope="col" class='text-center'>
                                        LAIN
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
                                    }
                                    
                                    // iterating each array
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


                                        foreach ($warna_id_arr as $key2 => $value2) {
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
                                            //1. breakdown tiap qty_datang per warna
                                            $qda = explode("||", $qty_datang_arr[$key2]);
                                            //1. breakdown tiap bulan per warna
                                            $bda = explode("||", $bulan_datang_arr[$key2]);

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
                                                        $datang_awal[$rid][$bid] = 0;
                                                    }else if(!isset($request_lain[$rid][$bid])){
                                                        $request_lain[$rid][$bid] = 0;
                                                        $datang_lain[$rid][$bid] = 0;
                                                    }

                                                    //total datang per request per awal banget per barang dan next

                                                }

                                                if (!isset($total_request_all[$rid][$brq[$key3]])) {
                                                    $total_request_all[$rid][$brq[$key3]] = 0;
                                                }

                                                if ($brq[$key3] == $bulan_request_awal) {
                                                    $request_awal[$rid][$bid] += $value3;
                                                    $request_awal_total[$rid] += $value3;
                                                }else{
                                                    $request_lain[$rid][$bid] += $value3;
                                                    $request_lain_total[$rid] += $value3;
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
                                                if (date("Y-m", strtotime($bda[$key3])) == date("Y-m", strtotime($bulan_request_awal)) ) {
                                                    $request_awal[$rid][$bid] = 0;
                                                    $datang_awal[$rid][$bid] = 0;
                                                }else if(!isset($request_lain[$rid][$bid])){
                                                    $request_lain[$rid][$bid] = 0;
                                                    $datang_lain[$rid][$bid] = 0;
                                                }
                                            }
                                        }
                                        
                                        //get total datang tapi ga ada di list barang request tiap barang per bulan per warna
                                        // $total_datang_no_request[$rid][$brq][$bid] += $qty_datang_no_request[$key];
                                        //get total datang per tiap barang per bulan per warna
                                        // $total_datang[$rid][$brq][$bid] += $qty_datang[$key];
                                        
                                        
                                        $total_brg[$value][$bulan_request_awal] = 0;
                                        $total_brg[$value]['next'] = 0;
                                        $total_brg[$value]['other'] = 0;

                                        //===================data detail============================
                                        $qty_detail_arr = array();
                                        $bulan_datang_arr = array();
                                        
                                        
                                        
                                        $qr = explode(",", $qty_data_request_perbulan[$key]);
                                        if (isset($qty_datang_detail[$key])) {

                                            //break setiap barang datang detail, nanti hasilnya juga masih bentuk rekap separate by '||'
                                            $qty_detail_arr = explode(",", $qty_datang_detail[$key]);
                                            //break setiap bulan datang hasilnya juga masih bentuk rekap separate by '||'
                                            $bulan_datang_arr = explode(",", $bdr);
                                        }

                                        //break setiap warna buat data stok
                                        // foreach ($warna_id_arr as $k2 => $v2) {
                                        //     //initialize total request bulan awal
                                        //     $tor[$rid][$value][$v2][$bulan_request_awal] = 0;
                                        //     $tor[$rid][$value][$v2]['next'] = 0;
                                        //     $kl[$rid][$value][$v2] = 0;
                                        // }

                                        //break down datang barang per bulan
                                        // $nqd = array_combine($bd, $qd);
                                        foreach ($qty_detail_arr as $k2 => $v2) {
                                            //break qty detail jadi unit terkecil, ya udah data pure nya
                                            $x = explode("||", $v2);
                                            //break bulan datang jadi unit terkecil, ya udah data pure nya
                                            $x2 = explode("||", $bulan_datang_arr[$k2]);
                                            foreach ($x as $k3 => $v3) {
                                                if (date('Y-m',strtotime($bulan_request_awal)) == date('Y-m',strtotime($x2[$k3]))) {
                                                    $idxx = $bulan_request_awal;
                                                    if (isset($x2[$k3]) && $x2[$k3] != '' && isset($warna_id_arr[$k2])) {
                                                        // echo $rid.','.$bid.','.$warna_id_arr[$k2].','.$idxx.'<br/>';
                                                        if (!isset($dtg[$rid][$bid][$warna_id_arr[$k2]][$idxx])) {
                                                            $dtg[$rid][$bid][$warna_id_arr[$k2]] = 0;
                                                        }
                                                        $dtg[$rid][$bid][$warna_id_arr[$k2]] += $v3;
                                                    }
                                                }else{
                                                    $idxx = 'next';
                                                    if (isset($x2[$k3]) && $x2[$k3] != '' && isset($warna_id_arr[$k2])) {
                                                        // echo $rid.','.$bid.','.$warna_id_arr[$k2].','.$idxx.'<br/>';
                                                        if (!isset($dtg_next[$rid][$bid][$warna_id_arr[$k2]][$idxx])) {
                                                            $dtg_next[$rid][$bid][$warna_id_arr[$k2]][$idxx] = 0;
                                                        }
                                                        $dtg_next[$rid][$bid][$warna_id_arr[$k2]][$idxx] += $v3;
                                                    }
                                                }
                                                // jika bulan datang ada
                                            }
                                        }
                                        
                                        // //break down request per bulan
                                        // foreach ($br as $k2 => $v2) {
                                        //     $x = explode('||',$v2);
                                        //     $x2 = explode('||',$qr[$k2]);
                                        //     foreach ($x as $k3 => $v3) {
                                        //         $nqr[$v3] = $x2[$k3];
                                        //         if ($v3 == $bulan_request_awal) {
                                        //             //total request barang per bulan, k3 index, v3 value isinya bulan
                                        //             $tor[$rid][$value][$wi[$k2]][$v3] += $x2[$k3];
                                        //         }else{
                                        //             $tor[$rid][$value][$wi[$k2]]['next'] += $x2[$k3];
                                        //         }
                                        //     }
                                        // }

                                        
                                        // if (isset($tqd[$rid][$value])) {
                                        //     foreach ($tqd[$rid][$value] as $k2 => $v2) {
                                        //         //k2 is warna id 
                                        //         //v2 bulan datang Y-m-d
                                        //         echo $value.'|'.$k2;
                                        //         foreach ($v2 as $k3 => $v3) {
                                        //             echo ' -'.$k3.' -'.$v3.'<br/>';
                                        //             echo $tor[$rid][$value][$k2][$k3].'<br/>';
                                        //         }
    
                                        //     }
                                        // }

                                    }
                                    // echo '<hr/>';
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
                                        /** 
                                        ===============================================================================
                                        **/
                                        $qr = $qty_request[$key];
                                        $qr = ($qr == 0 ? 1 : $qr);
                                        
                                        $datang_bar = $qty_datang[$key]/$qr;
                                        $pembagi = $qty_datang_no_request[$key]+$qty_datang[$key];
                                        $pembagi = ($pembagi == 0 ? 1 : $pembagi);
                                        $persen_non_request = $qty_datang_no_request[$key]/$pembagi;
                                        $persen_non_request = number_format($persen_non_request,'2','.','');
                                        $req_bar = ($qty_datang[$key] > 0 ? 1-$persen_non_request : 0 );

                                        $persen_request = ($qty_datang[$key] > 0 ? 1-$persen_non_request : 0);
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

                                        if (isset($request_awal[$row->request_barang_id][$barang_id[$key]])) {
                                            $r_awal = $request_awal[$row->request_barang_id][$barang_id[$key]];
                                        }

                                        if (isset($request_lain[$row->request_barang_id][$barang_id[$key]])) {
                                            $r_next = $request_lain[$row->request_barang_id][$barang_id[$key]];
                                        }

                                        ?>
                                        <tr>
                                            <?if ($key == 0) {?>
                                                <td rowspan="<?=count($nama_barang)+1;?>">
                                                    <a target="_blank" href="<?=base_url().is_setting_link('transaction/request_barang')?>?request_barang_id=<?=$row->request_barang_id?>">
                                                        <?=$row->no_request_lengkap?>
                                                    </a>
                                                </td>
                                                <td rowspan="<?=count($nama_barang)+1;?>">
                                                   <b><?=date("F Y", strtotime($row->bulan_request_awal));?></b>
                                                </td>
                                            <?}?>
                                            <td>
                                                <div>
                                                    <div class='performance-bar'>
                                                        <div class='datang-bar' style='display:inline; width:<?=$datang_bar*200;?>px;'></div>
                                                        <?=$value?> <small><?=number_format($datang_bar*100,'2','.',',')?>%</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div style="float:right"><?=number_format($r_awal,'0',',','.'); ?> </div> 
                                            </td>
                                            <td>
                                                <div style="float:right"><?=number_format($r_next,'0',',','.'); ?> </div> 
                                            </td>
                                            <td hiddden>
                                                <div class='datang-bar-container'>
                                                    <div class='request-bar'  style='display:inline; width:<?=$req_bar*130;?>px;' >
                                                    </div>
                                                        <?=str_replace(',00','',number_format($qty_datang[$key],'2',',','.')) ;?> (<?=($persen_request)*100?>%)
                                                </div>
                                            </td>
                                            <td>
                                                <div class='datang-bar-container'>
                                                    <div class='request-bar'  style='display:inline; width:<?=$req_bar*130;?>px;' >
                                                    </div>
                                                        <?=str_replace(',00','',number_format($qty_datang[$key],'2',',','.')) ;?> (<?=($persen_request)*100?>%)
                                                </div>
                                            </td>
                                            <td>
                                                <div class='non-request-bar'>
                                                    <div class='non-req-bar' style='display:inline; width:<?=$persen_non_request*130;?>px;'>
                                                    </div>
                                                    <?=str_replace(',00','',number_format($qty_datang_no_request[$key],'2',',','.')) ;?> (<?=$persen_non_request*100;?>%)
                                                </div>
                                            </td>

                                            <?if ($key == 0) {?>
                                                <td class='text-center' rowspan="<?=count($nama_barang)+1;?>" style="border-left:1px solid #ddd; vertical-align:middle">
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
                                                        $req_persen = $total_req_persen;
                                                        $noreq_persen = 1-$total_req_persen;
                                                        $total_no_req_persen = $total_req_persen;
                                                        $total_req_persen = 1-$total_no_req_persen;
    
                                                        $total_req_persen *= 360;
                                                        $total_no_req_persen *= 360;
                                                    ?>
                                                    
                                                    <div class='pie' style="margin:auto;margin-bottom:20px; width:100px; height:100px; border-radius:50%; background-image: conic-gradient(#ffff91 <?=$total_req_persen?>deg, #b3e3ff 0 <?=$total_no_req_persen?>deg);"></div>
                                                    
                                                    <div style='display:inline-block; height:10px;width:10px;background-color:#b3e3ff'></div> <?=$req_persen*100?>%
                                                    <div style='display:inline-block; height:10px;width:10px;background-color:#ffff91;margin-left:10px'></div> <?=$noreq_persen*100?>%
                                                </td>
                                                <td rowspan="<?=count($nama_barang)+1;?>">
                                                    <a href="<?=base_url().is_setting_link('report/po_request_report_detail')?>?id=<?=$row->request_barang_id;?>" class='btn btn-xs yellow-gold' target='_blank'><i class='fa fa-search'></i></a>
                                                </td>
                                            <?}?>
                                        </tr>
                                        <?
                                            $total_datang_req_persen = 0;
                                            $total_datang_noreq_persen = 0;
                                            if ($total_all > 0) {
                                                $total_datang_req_persen = number_format($total_datang[$row->request_barang_id][$barang_id[$key]]/$total_all,'2','.','');
                                                $total_datang_noreq_persen = number_format($total_datang_no_request[$row->request_barang_id][$barang_id[$key]]/$total_all,'2','.','');
                                            }

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
                                    <tr>
                                        <td>
                                            <div>
                                                <div class='performance-bar'>
                                                    <div class='datang-bar' style='display:inline; width:<?=$datang_bar_all*200;?>px;'></div>
                                                    TOTAL
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div style="float:right"><?=number_format($request_awal_total[$row->request_barang_id],'0',',','.'); ?> </div> 
                                        </td>
                                        <td>
                                            <div style="float:right"><?=number_format($request_lain_total[$row->request_barang_id],'0',',','.'); ?> </div> 
                                        </td>
                                        <td>
                                            <div class='datang-bar-container'>
                                                <div class='request-bar'  style='display:inline; width:<?=$req_bar*130;?>px;' >
                                                </div>
                                                    <?//=str_replace(',00','',number_format($total_datang[$row->request_barang_id][$barang_id[$key]],'2',',','.')) ;?> (<?=($total_datang_req_persen)*100?>%)
                                            </div>
                                        </td>
                                        <td>
                                            <div class='non-request-bar'>
                                                <div class='non-req-bar' style='display:inline; width:<?=$persen_non_request*130;?>px;'>
                                                </div>
                                                <?=str_replace(',00','',number_format($total_datang_no_request[$row->request_barang_id][$barang_id[$key]],'2',',','.')) ;?> (<?=$total_datang_noreq_persen*100;?>%)
                                            </div>
                                        </td>
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
</script>
