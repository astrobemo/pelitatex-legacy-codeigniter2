<link rel="stylesheet" type="text/css" href="<?= base_url('assets/css/map_style.css'); ?>" />

<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css'); ?>" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css'); ?>" />

<?php echo link_tag('assets/global/plugins/morris/morris.css'); ?>

<style>

    #general-table{
        width:100%;
        font-size:1.1em;
    }

    #general-table tr td, #general-table tr th{
        padding:5px;
        border:1px solid #ccc;
    }

    #general-table tr:nth-child(2n){
        background-color:#eee;
    }

    #general-table tr th:nth-child(1),
    #general-table tr th:nth-child(4),
    #general-table tr th:nth-last-child(1),
    #general-table tr td:nth-child(1),
    #general-table tr td:nth-child(4),
    #general-table tr td:nth-last-child(1),
    #general-table tfoot tr th:nth-last-child(2)
    {
        border-right:1px solid #999;
        border-left:1px solid #999;
        min-width:150px;
    }

    #general-table tr:nth-child(1) th,
    #general-table tr:nth-last-child(1) th,
    #general-table tfoot tr td:nth-child(1) th
    {    
        border-top:1px solid #999;
        border-bottom:1px solid #999;
    }

    #general-table tr:nth-last-child(1) td
    {    
        border-bottom:1px solid #999;
    }


    #general-table tfoot tr th
    {
        text-align:center;
    }
    

    .harga-input:focus, .history-harga-title, .kolom-harga, .tab-harga-title{
        background-color:rgba(212, 241, 244,0.6);
    }

    #general-table td:nth-child(3){
        /* background-color:#faa; */
    }

    .harga-berlaku-col{
        text-align:center;
    }

    .harga-baru-col{
        padding:0 !important;
    }

    .harga-baru-col input{
        text-align:center;
        width:100%;
        padding:0 0 0 10px;
        border:none;
        height:40px;
        background:transparent;
    }

    .harga-baru-col input:disabled{
        cursor:no-drop;
    }

    #general-table thead th{
        /* font-size:1.1em; */
        position: -webkit-sticky;
        position: sticky;
        background: #fff;
        top: 50px;
        min-height: 50px;
        border-bottom: 2px solid #ddd;
        background: #eee;
        z-index:2;
    }

    #general-table thead tr:first-child th { position: sticky; top: 0; }
    
    #general-table tbody tr td:first-child{position: sticky; left:0;}

    .activeRow, .activeRow td{
        background-color:lightpink !important;
        font-weight:bold;
        font-size:1.05em;
    }

    #harga-history-container{
        max-height: 200px;
        overflow-y:scroll;
    }

    #harga-history-container tr td{
        text-align:center;
        padding:5px 10px;
        vertical-align:middle;
    }

    #table-harga-section{
        display:table-cell;
        vertical-align:top;
        /* padding:0 10px 0 10px; */
    }

    #history-harga-section{
        display:inline-block;
        display:table-cell;
        /* vertical-align:top; */
        padding-left:10px;
        min-width:350px;
        max-width:300px;
        position:relative;
        font-size:12px;
    }

    #info-harga-history{
        position: -webkit-sticky;
        position: sticky;
        background: #fff;
        top: 50px;
        min-height: 150px;
        border-bottom: 2px solid #ddd;
        background: #eee;
        z-index:2;
    }

    .history-table tr th{
        text-align:center;
    }

    label.disabled {
        cursor:no-allowed;
        color:#ccc;
    }

    #hpp-value, #beli-value, #hpp-ppn-value, #beli-ppn-value{
        min-height: 50px; 
        padding:10px;
        text-align:center;
        font-weight:bold;
    }

    #hpp-value, #beli-value{
       color:#777;
    }

    .radio-selected {
        background: lightgreen; /* fallback */
        animation: switchColor 1s;
    }

    @keyframes switchColor {
        0% {
            background: lightgray;
        }
        70% {
            background: lightblue;
        }
        100% {
            background: lightgreen;
        }
    }

    .baris-hide{
        display:none;
    }

    #table-header{
        margin-top:20px;
        margin-left:10px;
    }

    #table-header tr td:nth-child(2){
        width:20px;
    }

    #table-header tr td:nth-child(1){
        width:80px;
    }

    #table-header .radio-inline  {
        padding:0px 5px 0px 0px;
    }

    #table-header .radio{
        padding: 1px 5px 0px 0px;
    }

    #table-header tr td{
        vertical-align:top;
        padding:0px 0px 5px 0px;
    }

    

</style>

<!-- initialisize  -->
<div class="page-content">
    <div class='container'>

        
        <!-- tambah data -->
        <div class="modal fade" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <form action="<?= base_url('masters/group_harga/daftar_update') ?>" autocomplete='off' class="form-horizontal" id="form_add_data" method="post" onkeydown="return event.key != 'Enter';">
                            <h3 class='block'> Edit Data Barang </h3>
                            <?php if(validation_errors()){?>
                                <div class="alert alert-block alert-warning fade in">
                                    <?php echo validation_errors(); ?>
                                </div>
                            <?php }?>
                            <div class="form-group">
                                <label class="control-label col-md-3">Nama
                                    <span class="required">*</span>
                                </label>

                                <div class="col-md-6">
                                    <input name="id"   value='<?=$id;?>' />
                                    <input type="text"  class="form-control" name="nama" />
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3">Deskripsi
                                </label>

                                <div class="col-md-6">
                                    <textarea class='form-control' name='deskripsi'>
                                    </textarea>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn blue btn-save" id="btnSave">Save</button>
                        <button type="button" class="btn default btn-close" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>


        <!-- list data -->

        <div class="row margin-top-10">
            <div class="col-md-12">
                <div class="portlet light">
                    <div class="portlet-title">
                        <div class="caption caption-md">
                            <i class="icon-bar-chart theme-font hide"></i>
                            <span class="caption-subject theme-font bold uppercase"><?= $breadcrumb_small; ?></span>
                        </div>

                        <div class="actions">

                            <a href="#portlet-config-add" data-toggle='modal' class="btn btn-default btn-sm btn-form-add">
                                <i class="fa fa-plus"></i> Tambah
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row margin-top-10">
            <div class="col-md-12">
                <div class="portlet light">

                    <!-- judul dan filtering -->
                    <!-- <div class="portlet-title">
                        <div class="caption caption-md">
                            <i class="icon-bar-chart theme-font hide"></i>
                            <span class="caption-subject theme-font bold uppercase"><?= $breadcrumb_small; ?></span>
                        </div>

                        <div class="actions">

                            <a href="#portlet-config" data-toggle='modal' class="btn btn-default btn-sm btn-form-add">
                                <i class="fa fa-plus"></i> Tambah
                            </a>
                        </div>
                    </div> -->

                    <div class="portlet-body">
                        <?
                                $nama = '';
                                $deskripsi = '';
                                $isDefault = '';

                                $status_lock = 0;
                                $locked_at = '';
                                $harga_baru_info_id = '';
                                foreach ($harga_baru_info as $row) {
                                    $harga_baru_info_id = $row->id;
                                    $status_lock = $row->status;
                                    $locked_at = $row->updated_at;
                                }

                                foreach ($daftar_harga_all as $row) {
                                    $selected = '';
                                    if ($row->id == $id) {
                                        $nama = $row->nama;
                                        $deskripsi = $row->deskripsi;
                                        $is_default = $row->isDefault;
                                        $selected = 'active';
                                    }
                                    
                                }?>
                    </div>

                    <div class="portlet-body">
                        <div class="tabbable tabs-left">
                            <ul class="nav nav-tabs ">
                                <?
                                $nama = '';
                                $deskripsi = '';
                                $isDefault = '';

                                foreach ($daftar_harga_all as $row) {
                                    $selected = '';
                                    if ($row->id == $id) {
                                        $nama = $row->nama;
                                        $deskripsi = $row->deskripsi;
                                        $is_default = $row->isDefault;
                                        $selected = 'active';
                                    }?>
                                    <li class="<?=$selected;?>" >
                                        <a href="<?=($row->id != $id ? base_url().is_setting_link('masters/group_harga/edit').'?id='.$row->id : '#')?>" <?=($row->id != $id ? "onclick='changeData()'" : '')?>" >
                                        <?=$row->nama?> </a>
                                    </li>
                                    
                                <?}?>
                                <li style="background:lightpink">
                                    <a href="<?=base_url().is_setting_link('masters/group_harga/edit_all')?>" >
                                    Show All Price </a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div>
                                    
                                </div>
                            </div>
                        </div>
                        
                        <div>
                            <form action="<?= base_url('masters/group_harga/daftar_update') ?>" autocomplete='off' class="form-horizontal" id="form_edit_data" method="post" onkeydown="return event.key != 'Enter';">
                                <?php if(validation_errors()){?>
                                    <div class="alert alert-block alert-warning fade in">
                                        <?php echo validation_errors(); ?>
                                    </div>
                                <?php }?>
                                <table id='table-header'>
                                    <tr>
                                        <td>Nama</td>
                                        <td> : </td>
                                        <td>
                                        <!-- oninput="enableSaveUpdate()"  -->
                                            <input type="text" name='id' value="<?=$id;?>" hidden/>
                                            <input readonly style='width:300px' type="text" class="form-control" name="nama" value="<?=$nama?>" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Deskripsi</td>
                                        <td> : </td>
                                        <td>
                                        <!-- oninput="enableSaveUpdate()" -->
                                            <textarea readonly  style='width:300px' class='form-control' name='deskripsi' ><?=$deskripsi;?>
                                            </textarea>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan='3'>
                                            <!-- <button type="button" style='margin-top:20px' id='btn-header-save' onclick="submitHeader()" disabled class="btn btn-block blue btn-save">Update</button> -->
                                        </td>
                                    </tr>
                                </table>

                            </form>
                            <div class='text-left'>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row margin-top-10">
            <div class="col-md-12">
                <div class="portlet light">

                    <div class="portlet-title">
                        <div class="caption caption-md">
                            <i class="icon-bar-chart theme-font hide"></i>
                            <span class="caption-subject theme-font bold uppercase">Daftar Detail</span>
                        </div>
                    </div>

                    <div class="portlet-body">
                        <div>
                            <div id='table-harga-section'>
                                <table class="" id="general-table">
                                    <!-- header table -->
                                    <thead>
                                        <!-- <tr>
                                            <th colspan='4' style='border-top:2px solid #999; border-bottom:2px solid #999'>
                                                <h1>
                                                    DAFTAR HARGA <?=$nama?>
                                                </h1>
                                            </th>
                                        </tr> -->
                                        <tr>
                                            <th colspan='4' style='padding:20px 10px; border-bottom:2px solid #999'>
                                                    <div>
                                                        <b>Filter :</b> 
                                                        <label class='radio-inline radio-selected' style='padding:0 15px 0 5px;'>
                                                            <input checked type="radio"  name="harga_filter" id="hargaall" onchange = "FilterTable(0,'hargaall')">Tampilkan Semua</label>
                                                        <label disabled class="radio-inline <?=(count($harga_jual_baru) <= 0 ? 'disabled' : '' )?>"  style='padding:0 15px 0 5px;'>
                                                            <input type="radio" name="harga_filter" <?=(count($harga_jual_baru) <= 0 ? 'disabled' : '' )?> id="hargaChange"  onchange = "FilterTable(1, 'hargaChange')">Hanya Yang Berubah</label>
                                                    </div>

                                            </th>
                                        </tr>
                                        <tr>
                                            <th scope="col" style="min-width:250px !important" class="text-center">
                                                DAFTAR HARGA <span class='badge badge-danger'> <?=$nama;?></span>
                                            </th>
                                            <th scope="col"  class="text-center">
                                                Harga Berlaku
                                            </th>
                                            <th scope="col" class="text-center">
                                                Harga Baru
                                            </th>
                                            <th scope="col" class="text-center">
                                                Selisih
                                            </th>
                                        </tr>
                                    </thead>
        
                                    <tbody>
                                        <?foreach ($this->barang_list_aktif as $row) {
                                            $harga_berlaku[$row->id] = null;
                                            $harga_baru[$row->id] = null;
                                            $harga_history[$row->id] = array();

                                            $harga_beli[$row->id] = 0;
                                            $ppn_beli[$row->id] = 0;
                                            $tanggal_beli[$row->id] = '';

                                            $harga_hpp[$row->id] = 0;
                                            $ppn_hpp[$row->id] = 0;
                                            $tanggal_hpp[$row->id] = '';
                                        }
                                        foreach ($harga_jual_berlaku as $row) {
                                            $harga_berlaku[$row->barang_id] = $row->harga_berlaku;
                                        }
                                        foreach ($harga_jual_baru as $row) {
                                            // $harga_baru[$row->barang_id] = (float)$row->harga;
                                            // $harga_kredit_baru[$row->barang_id] = (float)$row->harga_kredit;
                                            
                                            $hb[$row->barang_id] = $row->harga_baru;
                                            $harga_baru[$row->barang_id] = str_replace(",00","", number_format($row->harga_baru,"2",",",'.'));
                                        }
                                        foreach($harga_jual_history as $row){
                                            if (!isset($last_harga[$row->barang_id] )) {
                                                $harga_history[$row->barang_id] = array();
                                                $last_harga[$row->barang_id] = $row->harga_history;
                                                $last_tanggal_harga[$row->barang_id] = $row->tanggal_archive;
                                            }
                                            array_push($harga_history[$row->barang_id], $row);
                                        }

                                        foreach ($harga_hpp_history as $row) {
                                            $harga_hpp[$row->barang_id] = round($row->harga_total/$row->jml,2);
                                            $ppn_hpp[$row->barang_id] = $row->ppn_berlaku;
                                            $tanggal_hpp[$row->barang_id] = $row->tanggal;
                                        }

                                        foreach($harga_beli_terkhir as $row){
                                            $harga_beli[$row->id] = $row->harga;
                                            $ppn_beli[$row->id] = $row->ppn_berlaku;
                                            $tanggal_beli[$row->id] = $row->tanggal;
                                        }

                                        $index = 1;
                                        foreach ($this->barang_list_aktif as $row) {?>
                                            <tr id='row-<?=$row->id;?>'>
                                                <td class='nama-barang'><?=$row->nama_jual;?></td>
                                                <td class='harga-berlaku-col kolom-harga'><?=str_replace(",00","", number_format($harga_berlaku[$row->id],"2",",",'.'));?></td>
                                                <td class='harga-baru-col kolom-harga'

                                                    <?if($status_lock==1){?>
                                                        onclick="highlightRow('<?=$row->id;?>')" 
                                                    <?}?>
                                                    <?//=print_r($is_master[$row->id]);?>
                                                >
                                                    <input type="text" 
                                                        tabindex = "<?=$index;?>"
                                                        class='amount_number harga-input'
                                                        onfocus="highlightRow('<?=$row->id;?>')" 
                                                        onfocusout="showDiff('<?=$row->id;?>')" 
                                                        data-id='harga-<?=$row->id;?>'
                                                        oninput="liveInput('<?=$row->id;?>')"
                                                        onchange="updateHargaBaru('<?=$row->id;?>')"
                                                        value = "<?=($harga_baru[$row->id] > 0 ? $harga_baru[$row->id] : "");?>"
                                                        <?=($status_lock == 1 ? 'disabled' : '');?>
                                                        >
                                                </td>
                                                <td class='text-align:center kolom-harga'>
                                                    <?
                                                    $perc = '';
                                                    $selisih = 0;
                                                    $perc = '';
                                                    $arrow = '';
                                                    if ($harga_baru[$row->id] != '' && $harga_baru[$row->id] > 0 ) {
                                                        // $selisih = ($hb[$row->id] - $harga_berlaku[$row->id]);
                                                        // $perc = ($selisih / $harga_berlaku[$row->id] * 100);
                                                        
                                                        $selisih = ($hb[$row->id] - $harga_berlaku[$row->id]);
                                                        if ($harga_berlaku[$row->id] > 0) {
                                                            $perc = ($selisih / $harga_berlaku[$row->id] * 100);
                                                        }else{
                                                            $perc = 100;
                                                        }

                                                        $perc = round($perc,2);
                                                        $addclass =  ($selisih < 0 ? 'perc-down' : 'perc-up');

                                                    }
                                                    $arrow = "<i class='fa fa-".($selisih == 0 ? '' : ($selisih < 0 ? 'caret-down' : 'caret-up'))."' style='color:".($selisih < 0 ? 'red' : 'green')."'></i>";

                                                    ?>
                                                    <?=$arrow;?>
                                                    <?=str_replace(",00","",($selisih != 0 ? number_format(abs($selisih),'2',',','.') : ''));?>
                                                    <span style="font-size:12px;"><?=($selisih == 0 ? '' : "(<b class='$addclass' >".(float)$perc.'</b>%)' );?></b></span>
                                                
                                                </td>
                                            </tr>
                                        <?
                                        $index++;
                                        ;}?>
                                    </tbody>

                                    <tfoot>
                                        <tr>
                                            <th></th>
                                            <th colspan='3'><h4>PERUBAHAN</h4></th>
                                        </tr>
                                        <tr>
                                            <th>Jumlah Perubahan</th>
                                            <th id="tCash" colspan='3'></th>
                                        </tr>
                                        <tr>
                                            <th>Harga Naik </th>
                                            <th id="up"></th>
                                            <th colspan='2' id="upRate"></th>
                                        </tr>
                                        <tr>
                                            <th>Harga Turun</th>
                                            <th id="down"></th>
                                            <th colspan='2' id="downRate"></th>
                                        </tr>   
                                    </tfoot>
                                </table>
                            </div>
                            <div id='history-harga-section'>
                                <div id='status-lock' style="padding:10px; background:lightsalmon; <?=($status_lock != 1 ? 'display:none':'') ?>">
                                        STATUS : <?=($status_lock == 1 ? "<i class='fa fa-lock'></i>" : ($status_lock=='2' ? "<i class='fa fa-unlock-alt'></i>" : ''))?>
                                        <br/><?=($status_lock==1 ? 'LOCKED' : 'UNLOCKED')?> : <?=date('d M y H:i:s', strtotime($locked_at));?>
                                </div>
                                <div id="info-harga-history">
                                    <?
                                        $class_active = "history-harga-title";
                                    ?>
                                   <div class="<?=$class_active?>" style='min-height:200px;'>
                                        <h4 class="" style='padding:10px; margin:0px;'>History Harga <b id='nama-barang-history'></b> : </h4>
                                        <div id="harga-history-container">

                                        </div>
                                    </div>
                                    <div class="<?=$class_active?>">
                                        <table style="width:100%">
                                            <tr>
                                                <th colspan='2' style="text-align:center; width:50%">
                                                    <h4 class="<?=$class_active?>" style='padding:10px; margin:0px;'><b>HPP</b></h4>
                                                </th>
                                                <th style="text-align:center; width:50%">
                                                    <h4 class="<?=$class_active?>" style='padding:10px; margin:0px;'><b>Harga Beli</b></h4>
                                                </th>
                                            </tr>
                                            <tr>
                                                <th></th>
                                                <th colspan='2' class='text-center'>
                                                <h4 hidden class="<?=$class_active?>" style='padding:10px; margin:0px;'>
                                                    <label class='checkbox-inline'>
                                                        <input type="checkbox" onchange="isDPPOptionChanged()" id="isHargaDPP"> Harga Sudah DPP <span style='font-size:12px'>(ppn:<?=(float)$ppn_berlaku;?>%)</span></label>
                                                </h4>
                                                </th>
                                            </tr>
                                            <tr>
                                                <td style="padding-left:5px;">BASE</td>
                                                <td style="text-align:center" id="hpp-ppn-value"></td>
                                                <td style="text-align:center" id="beli-ppn-value"></td>
                                            </tr>
                                            <tr>
                                                <td style="padding-left:5px;">DPP</td>
                                                <td style="text-align:center" id="hpp-value"></td>
                                                <td style="text-align:center" id="beli-value"></td>
                                            </tr>
                                        </table>

                                        <table style="width:100%"><tr>
                                            
                                        </tr></table>
                                        <div class="row">
                                            <div  class='col-xs-6'>
                                            </div>
    
                                            <div  class='col-xs-6'>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="">
                                        <h4 class="text-center history-harga-title" style='padding:10px; margin:0px;'><b>GRAFIK HARGA</b></h4>

                                        <div id="price_statistics" class="portlet-body-morris-fit morris-chart" style="height: 200px; padding:20px;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <hr/>

                        <div class="row">
                            <div class="col-xs-12">
                                <?if($status_lock == 1){?>
                                    <?}else{?>
                                        <button class="btn btn-lg red" disabled  id="lockBtn" onclick="submitLock()">
                                        <i class='fa fa-unlock-alt'></i> LOCK
                                    </button>
                                    <?}?>
                                    <a href="#portlet-config-pin" data-toggle='modal' style="<?=($status_lock != 1 ? 'display:none' : '')?>" class="btn btn-lg green" id='btn-show-pin'><i class='fa fa-unlock-alt'></i> OPEN </a>

                                    <a href="#portlet-config-launch" data-toggle='modal' class="btn btn-lg blue" id="btn-show-launch" style="<?=($status_lock != 1 ? 'display:none' : '')?>" ><i class='fa fa-rocket'></i> LAUNCH PRICE</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    
</div>

        <div class="modal fade" id="portlet-config-pin" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body">
						<form action="<?=base_url('masters/group_harga/edit_open');?>" class="form-horizontal" id="form-request-open" method="post">
							<h3 class='block'> Request Open</h3>
							
							<div class="form-group">
			                    <label class="control-label col-md-3">PIN<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<input name='id' value='<?=$id;?>' hidden>
			                    	<input name='harga_baru_info_id' value='<?=$harga_baru_info_id;?>' hidden>
									<input name='pin' 
                                        type='password' 
                                        class="pin_user form-control"
                                        id="pin-unlock"
                                        oninput="cekPin('pin-unlock', 'btn-pin')"
                                        >
			                    </div>
			                </div>	
		                </form>		                
					</div>

					<div class="modal-footer">
						<button type="button" class="btn blue" disabled id='btn-pin'>OPEN</button>
						<button type="button" class="btn default" data-dismiss="modal">Close</button>
					</div>
				</div>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
		</div>


        <div class="modal fade" id="portlet-config-launch" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body">
						<form action="<?=base_url('masters/group_harga/edit_launch');?>" class="form-horizontal" id="form-launch" method="post">
							<h3 class='block'> LAUNCH PRICE</h3>
							
                            <div class="form-group">
			                    <label class="control-label col-md-3">Berlaku<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<input name='tanggal' class='form-control' readonly value="<?=date('d/m/Y H:i:s');?>" >
			                    </div>
			                </div>

							<div class="form-group">
			                    <label class="control-label col-md-3">PIN<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<input name='id' value='<?=$id;?>' hidden>
			                    	<input name='harga_baru_info_id' value='<?=$harga_baru_info_id;?>' hidden>
									<input name='pin' 
                                        type='password' 
                                        class="pin_user form-control"
                                        id="pin-launch"
                                        oninput="cekPin(`pin-launch`,`btn-launch-harga`,`<i class='fa fa-rocket'></i> LAUNCH`)"
                                        >
			                    </div>
			                </div>	

                            <div>
			                    <div class="note note-danger">
                                    <h4>Pastikan Harga Sudah Benar <i class="fa fa-warning"></i></h4>
                                    <p>Harga yang sudah di launch tidak dapat di batalkan</p>
                                </div>
			                </div>	
		                </form>		                
					</div>

					<div class="modal-footer">
						<button type="button" class="btn red" disabled id='btn-launch-harga'><i class='fa fa-rocket'></i> LAUNCH</button>
						<button type="button" class="btn default" data-dismiss="modal">Close</button>
					</div>
				</div>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
		</div>

        <div class="modal fade" id="portlet-config-add" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <form action="<?= base_url('masters/group_harga/daftar') ?>" autocomplete='off' class="form-horizontal" id="form_new_data" method="post" onkeydown="return event.key != 'Enter';">
                            <h3 class='block'> Data Baru </h3>
                            <?php if(validation_errors()){?>
                                <div class="alert alert-block alert-warning fade in">
                                    <?php echo validation_errors(); ?>
                                </div>
                            <?php }?>
                            <div class="form-group">
                                <label class="control-label col-md-3">Nama
                                    <span class="required">*</span>
                                </label>

                                <div class="col-md-6">
                                    <input type="text" name='id' value="<?=set_value('id');?>" hidden />
                                    <input type="text" class="form-control" name="nama" value="<?=set_value('nama');?>" />
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3">Deskripsi
                                </label>

                                <div class="col-md-6">
                                    <textarea class='form-control' name='deskripsi'><?=set_value('deskripsi');?>
                                    </textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3">Base Price
                                </label>

                                <!-- onchange="showHarga(1, 'edit')" -->
                                <div class="col-md-6">
                                    <select name="base_price" class="form-control" >
                                        <option value="0">Harga Master</option>
                                        <?foreach ($base_price_list as $row) {?>
                                            <option value="<?=$row->id;?>"><?=$row->nama;?> </option>
                                        <?}?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3">Add Price (Optional)
                                </label>

                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="add_price"/>
                                    <div class="note note-info">
                                        Info <i class="fa fa-warning"></i> <br>
                                        - Semua barang akan terefek add price <br>
                                        - Jika hanya ada beberapa barang berbeda maka kosongkan add price
                                        </div>
                                </div>
                            </div>
                            
                        </form>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn blue btn-save-new">Save</button>
                        <button type="button" class="btn default btn-close" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>


<div id='overlay-div' hidden style="z-index:4;left:0px; top:0px; position:fixed; height:100%; width:100%; background:rgba(0,0,0,0.5)">
	<p style="position:relative;color:#fff;top:40%;left:40%">Loading....</p>
</div>


<script src="<?= base_url('assets/global/plugins/morris/morris.min.js'); ?>" type="text/javascript"></script>
<script src="<?= base_url('assets/global/plugins/morris/raphael-min.js'); ?>" type="text/javascript"></script>

<script src="<?php echo base_url('assets/global/plugins/datatables/media/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/bootbox/bootbox.min.js'); ?>" type="text/javascript"></script>

<script>

<?
$pre = array_values($harga_history);
?>
const ppn_berlaku = "<?=$ppn_berlaku?>";
const daftar_harga_all = <?=json_encode($daftar_harga_all);?>;

var barang_id_active = '';


const status_lock = "<?=$status_lock;?>";

const data_history = <?=json_encode($pre)?>;

const harga_history = [];

const tbl_history = [];
const harga_berlaku = [];

let config_nilai = [];

let harga_hpp = [];
let ppn_hpp = [];
let harga_hpp_raw = [];
let tanggal_hpp = [];

let harga_beli = [];
let ppn_beli = [];
let harga_beli_raw = [];
let tanggal_beli = [];

let totalUp = 0;
let totalDown = 0;

let isMirroringActive = false;

let nData = '';

let tabindexShow = [];


<?foreach ($this->barang_list_aktif as $row) {
    // if (!isset($last_tanggal_harga[$row->id])) {
    //     $last_tanggal_harga[$row->id] = '-';
    // }?>

    harga_berlaku[`s-<?=$row->id?>`] = Number("<?=$harga_berlaku[$row->id];?>");
    harga_history[`s-<?=$row->id?>`] = {};

    harga_hpp[`s-<?=$row->id?>`] = "<?=str_replace(",00","",number_format($harga_hpp[$row->id],'2',',','.'));?>";
    harga_hpp_raw[`s-<?=$row->id?>`] = "<?=$harga_hpp[$row->id];?>";
    ppn_hpp[`s-<?=$row->id?>`] = "<?=$ppn_hpp[$row->id];?>";
    tanggal_hpp[`s-<?=$row->id?>`] = "<?=date('M Y' ,strtotime($tanggal_hpp[$row->id]));?>";

    harga_beli[`s-<?=$row->id?>`] = "<?=str_replace(",00","",number_format($harga_beli[$row->id],'2',',','.'));?>";
    harga_beli_raw[`s-<?=$row->id?>`] = "<?=$harga_beli[$row->id]?>";
    ppn_beli[`s-<?=$row->id?>`] = "<?=$ppn_beli[$row->id]?>";
    tanggal_beli[`s-<?=$row->id?>`] = "<?=date('M Y' ,strtotime($tanggal_beli[$row->id]));?>";

    tbl_history[`s-<?=$row->id?>`] = '';

    <?if (isset($harga_history[$row->id])) {?>
        harga_history[`s-<?=$row->id?>`] = <?=json_encode($harga_history[$row->id])?>;
    <?}?>
<?}?>

// function docReady(fn) {
//     // see if DOM is already available
//     if (document.readyState === "complete" || document.readyState === "interactive") {
//         // call on next available tick
//         setTimeout(fn, 1);
//     } else {
//         document.addEventListener("DOMContentLoaded", fn);
//     }
// }

document.addEventListener('DOMContentLoaded',(event)=>{
    docReady();

    $('.btn-save-new').click(function() {
        const form = document.querySelector("#form_new_data");
        const namaInput = form.querySelector("[name='nama']").value;
        
        let isNameUsed = false;
        daftar_harga_all.forEach(list => {
            if (list.nama === namaInput) {
                bootbox.alert("Nama sudah terdaftar ");
                isNameUsed = true;
                return;
            }
        });

        if (isNameUsed) {
            return;
        }
        
        if(namaInput != ''){
            form.submit();
        }
        else{
            bootbox.alert("Mohon isi nama");
        }
    });
    
})

function docReady(){
    <?foreach ($this->barang_list_aktif as $row) {?>
        
        <?if (isset($last_tanggal_harga[$row->id])) {?>
            nData = countAndFormatDays("<?=$last_tanggal_harga[$row->id];?>", "<?=date('Y-m-d H:i:s')?>", "<?=$harga_berlaku[$row->id]?>")
            tbl_history[`s-<?=$row->id?>`] = `<tr>
                        <td>1</td>
                        <td style="font-size:10px;">${nData[0]}</td>
                        <td>today</td>
                        <td>${nData[2]}</td>
                        <td></td>
                    </tr>`;
        <?}?>
    <?}?>

    data_history.forEach((list,list_index) => {
        let after = '-'; 
        let hargaAfter = 0; 
        let counter = 0;
        let data_idx =0 ;
        let chartData = [];
        let brg_id = '';
        list.forEach((baris, index) => {
            brg_id = baris.barang_id;
            let tgl_only = baris.tanggal_archive.split(' ');

            if (index == 0) {
                let nData = countAndFormatDays(baris.tanggal_archive, "<?=date('Y-m-d H:i:s')?>", harga_berlaku[`s-${baris.barang_id}`]);
                let diff = harga_berlaku[`s-${baris.barang_id}`] - parseFloat(baris.harga_history);
                tbl_history[`s-${baris.barang_id}`] = `<tr style='color:red;'>
                        <td>1</td>
                        <td style="font-size:10px;">${nData[0]}</td>
                        <td>today</td>
                        <td>${nData[2]}</td>
                        <td>${currency.rupiah(diff)}</td>
                    </tr>`;
                counter++;

                chartData[data_idx] = {
                    'date' : "<?=date('Y-m-d')?>",
                    'harga': harga_berlaku[`s-${baris.barang_id}`]
                };
                data_idx++;
            }

            chartData[data_idx] = {
                'date' : tgl_only[0],
                'harga': baris.harga_history
            };
            data_idx++;

            let after = '-';
            if (list.length != counter) {
                // console.log(list[parseInt(index)+1].tanggal_archive);
                after = list[parseInt(index)+1].tanggal_archive;

            }
            let nData = countAndFormatDays(after,baris.tanggal_archive, baris.harga_history);
            let diff = parseFloat(baris.harga_history) - (counter == 1 ? harga_berlaku[`s-${baris.barang_id}`] : parseFloat(hargaAfter));
            let arrow = '';
            diff = Math.abs(diff);

            tbl_history[`s-${baris.barang_id}`] += `
                <tr>
                    <td>${counter+1}</td>
                    <td style="font-size:10px;">${nData[0]}</td>
                    <td style="font-size:10px;">${nData[1]}</td>
                    <td>${nData[2]}</td>
                    <td>${arrow} ${(counter == list.length ? '' : diff)}</td>
                </tr>
            `;
            counter++;
            hargaAfter = baris.harga;
            after = baris.tanggal_archive;

        });


        chartData = chartData.reverse();
        config_nilai[`s-${brg_id}`]= {
            element: 'price_statistics',
            data: chartData,
            xkey: 'date',
            ykeys: ['harga'],
            labels: ['harga'],
            fillOpacity: 0.6,
            hideHover: 'auto',
            // stacked: true,
            barColors: ["#0b62a4", "#7a92a3"],
            // behaveLikeLine: true,
            resize: true
        };

    });


    //////////////////////////////////////////
    // save data 
    //////////////////////////////////////////
    // $('.btn-save').click(function() {
    //     const form = $("#form_add_data");
    //     if(form.find("[name='nama']").val() != ''){
    //         form.submit()
    //     }else{
    //         bootbox.alert("Mohon isi nama group");
    //     }
    // });

    const btnSave = document.querySelector('#btnSave');
    btnSave.addEventListener('click', function(event){
        const form = document.querySelector("#form_add_data");
        const nama = form.find("#form_add_data input[name='nama']").value;
        console.log(nama);
        if(nama != ''){

        }
        else{
            bootbox.alert("Mohon isi nama group")
        }
    });

    const inputs = document.querySelectorAll('.harga-baru-col input');
    inputs.forEach(input => {
        input.addEventListener('keypress', function(event){
            if(event.key === 'Enter'){
                const idx = parseInt(this.getAttribute('tabindex'))+1;    
                const tbl = document.querySelector(`#general-table input[tabindex='${idx}']`);
                tbl.focus();
            }
        });

        input.addEventListener('keydown', function(event){
            let idx = parseInt(this.getAttribute('tabindex'));
            // let listRow = document.querySelectorAll(`#table-harga-section tbody tr:not(.baris-hide)`);
            
            if(event.key === 'ArrowDown'){
                const list = tabindexShow.filter(indx => indx > idx);
                const next = list.length > 0 ? list[0] : idx + 1;
                const tbl = document.querySelector(`#general-table input[tabindex='${next}']`);
                tbl.focus();
            }else if(event.key === 'ArrowUp' && idx - 1 > 0 ){
                const list = tabindexShow.filter(indx => indx < idx);
                const next = list.length > 0 ? list[list.length-1] : idx - 1;
                const tbl = document.querySelector(`#general-table input[tabindex='${next}']`);
                tbl.focus();
            }
        });
    });
    
    document.querySelector('#btn-show-pin').addEventListener("click", function(){
        setTimeout(() => {
            document.querySelector("#pin-unlock").focus();
            document.querySelector("#btn-pin").innerHTML = 'OPEN'
        }, 500);
    });

    document.querySelector("#btn-pin").addEventListener("click", function(){
        this.disabled = '...true';
        this.innerHTML = '...submit';
        document.querySelector("#form-request-open").submit();
    });

    document.querySelector('#btn-show-launch').addEventListener("click", function(){
        setTimeout(() => {
            document.querySelector("#pin-launch").focus();
            document.querySelector("#btn-launch-harga").innerHTML = "<i class='fa fa-rocket'></i> LAUNCH"
        }, 500);
    });

    document.querySelector("#btn-launch-harga").addEventListener("click", function(){
        this.disabled = '...true';
        this.innerHTML = '...submit';
        document.querySelector("#form-launch").submit();
    });    

    updateRekap();

}

jQuery(document).ready(function() {

});

function cekPin(input_id, btn_id){
    const pin = document.querySelector(`#${input_id}`).value;
    const btn = document.querySelector(`#${btn_id}`);
    btn.disabled = true

    if (pin.length >= 4) {
        btn.innerHTML = '..check..'
        var data = {};
        data['pin'] = pin;
        var url = 'transaction/cek_pin';
    
        var result = ajax_data(url,data);
        if (result == 'OK') {
            btn.disabled = false
        }
        // btn.innerHTML = 'OPEN';
    }
}


function isDPPOptionChanged(){
    const isChecked = document.querySelector("#isHargaDPP").checked;
    notific8((isChecked ? 'tangerine' : 'teal'),`${(isChecked ? 'Harga Jual exclude PPN':'Harga Jual include PPN')}`);
    if (barang_id_aktif != '') {
        // alert('y')l
        percentageHarga(barang_id_aktif);
    }
}

function highlightRow(id){

    barang_id_aktif = id;
    
    // ========NGACO ini harga berlaku apa =========================
    const harga_berlaku = document.querySelector(`#row-${id} .harga-berlaku-col`).innerHTML;
    const harga_input = document.querySelector(`#row-${id} [data-id='harga-${id}']`).innerHTML;
    let perc_hpp = 0;
    let perc_beli = 0;
    
    if (harga_input != '') {
        const hrg = parseFloat(reset_number_format(harga_input));
        perc_hpp =  (hrg - harga_hpp['s-'+id])/hrg;
        perc_beli = (hrg - harga_beli['s-'+id])/hrg;
    }else{
        const hrg = parseFloat(reset_number_format(harga_berlaku));

        perc_hpp =  (hrg - harga_hpp['s-'+id])/hrg;
        perc_beli = (hrg - harga_beli['s-'+id])/hrg;
    }

    // console.log(perc_hpp, perc_beli);
    const divhpp = document.querySelector("#hpp-value");
    const divbeli = document.querySelector("#beli-value");

    const divhppppn = document.querySelector("#hpp-ppn-value");
    const divbelippn = document.querySelector("#beli-ppn-value");

    let ppn_pembagi = 1 + (ppn_hpp['s-'+id] / 100);
    const hpp_ppn = (harga_hpp_raw['s-'+id] * ppn_pembagi).toFixed(2);
    if (harga_hpp['s-'+id] != '' && parseFloat(harga_hpp_raw['s-'+id]) > 0) {
        
        divhpp.innerHTML = `<span style='font-size:16px'>${harga_hpp['s-'+id]}</span>
        <span id='hpp-perc' hidden></span>`;

        divhppppn.innerHTML = `<span style='font-size:16px'>${currency.rupiah(hpp_ppn)}</span>
        <span id='hpp-ppn-perc'></span><br/>
        <span style='font-size:12px'>( ${tanggal_hpp['s-'+id]} )</span>`;
    }else{
        divhpp.innerHTML = `<span style='font-size:16px'>${harga_hpp['s-'+id]}</span><br/>
        <span style='font-size:12px' hidden></span>`;
        
        divhppppn.innerHTML = `<span style='font-size:16px'>${currency.rupiah(hpp_ppn)}</span>
        <span id='hpp-ppn-perc' hidden></span>`;
    }

    ppn_pembagi = 1 + (ppn_beli['s-'+id] / 100);
    const beli_ppn = (harga_beli_raw['s-'+id] * ppn_pembagi).toFixed(2);
    if (harga_beli['s-'+id] != '' && parseFloat(harga_beli_raw['s-'+id]) > 0) {
        divbeli.innerHTML = `<span style='font-size:16px'>${harga_beli['s-'+id]}</span>
        <span id='harga-beli-perc' hidden></span>`;

        divbelippn.innerHTML = `<span style='font-size:16px'>${currency.rupiah(beli_ppn)}</span>
        <span id='harga-beli-ppn-perc'></span><br/>
        <span style='font-size:12px'>( ${tanggal_beli['s-'+id]} )</span>`;
    
    }else{
        divbeli.innerHTML = `<span style='font-size:16px'>${harga_beli['s-'+id]}</span>
        <span id='harga-beli-perc'></span><br/>
        <span style='font-size:12px' hidden>( - )</span>`;
        
        divbelippn.innerHTML = `<span style='font-size:16px'>${currency.rupiah(beli_ppn)}</span>
        <span id='harga-beli-ppn-perc'>( - )</span>`;
    
    }


    
    
    let ini = document.querySelector(`#row-${id}`).closest('tr');
    let nm_brg = ini.querySelector('.nama-barang').innerText;
    document.querySelector(`#nama-barang-history`).innerHTML = nm_brg;
    // let ini = $(`#row-${id}`).closest('tr');
    const active =document.querySelector('.activeRow');
    if(active){
        document.querySelector('.activeRow').classList.remove("activeRow");
    } 

    
    $(`#row-${id}`).addClass('activeRow');
    let header_tbl = `<table class='history-table'>
        <tr>
            <th></th>
            <th>start</th>
            <th>latest</th>
            <th>harga</th>
            <th>selisih</th>
        </tr>`;
    let content = ''
    const titles = document.querySelectorAll("#info-harga-history h4");

    
    if (tbl_history[`s-${id}`] != '') {
        // content = tbl_history[`s-${id}`];
        // content = header_tbl+content+'</table>';
        content = header_tbl+tbl_history[`s-${id}`]+'</table>';
    }else{
        content = "<p class='text-center'>No History</p>";
    }
    // document.querySelectorAll("#info-harga-history h4").classList.add('history-kredit-title');
    // document.querySelectorAll("#info-harga-history h4").classList.remove('history-harga-title');

    

    document.querySelector("#harga-history-container").innerHTML = content ;
    generateChartHarga(id);
    percentageHarga(id);


    // console.log(ini.querySelectorAll('.nama-barang'));
}


function showDiff(id){
    
    const col_idx = 4;
    const el = document.querySelector(`input[data-id='harga-${id}']`);
    const baris = document.querySelector(`#row-${id}`);
    const col = baris.querySelector(`td:nth-child(${col_idx})`);
    let harga_input = el.value;

    if(el.value == 0){
        el.value='';
        harga_input='';
    }

    // console.log(harga_input);
    harga_input = reset_number_format(harga_input);
    if (harga_input.length > 0 || harga_input > 0 ) {
        const harga_awal = harga_berlaku[`s-${id}`];
        let diff = harga_input - parseFloat(harga_awal);
        let perc = (harga_awal != 0 && harga_awal != '' ? ((diff/harga_awal).toFixed(4)) * 100 : 100);
        perc = parseFloat(perc.toFixed(2)); 
        let arrow = `<i class="fa fa-${diff == 0 ? '' : (diff < 0 ? 'caret-down' : 'caret-up')}" style="color:${(diff < 0 ? 'red' : 'green')}"></i>`;
        let addclass =  (diff < 0 ? 'perc-down' : 'perc-up');
        diff = Math.abs(diff);
        col.innerHTML = arrow+' '+change_number_format(diff)+` <span style="font-size:12px;">(<b class='${addclass}' >${perc}</b>%)</span>`;
    
    }else{
        col.innerHTML = '';
    }

    baris.classList.remove('harga-up','harga-down');
    const isUp = baris.querySelector(".perc-up");
    const isDown = baris.querySelector(".perc-down");
    if (isUp) {
        baris.classList.add('harga-up');
    }

    if (isDown) {
        baris.classList.add('harga-down');
    }

    updateRekap();

}

function countAndFormatDays(before, after, harga){
    
    // console.log(change_number_format('10000'));
    // console.log(before,'-', after, parseFloat(harga));
    // return [before, after, parseFloat(harga)];
    
    let ret1 = '-';
    let ret2 = '-';
    let ret3 = '-';
    if (before != '-') {
        let br = before.split(' ');
        ret1 = date_formatter(br[0]);
    }
    
    let br2 = after.split(' ');
    // console.log(br2[0]);
    ret2 = date_formatter(br2[0]);

    ret3 = change_number_format(parseFloat(harga))

    return [ret1, ret2, ret3];
    // return [before, after, parseFloat(harga)]
}

function updateHargaBaru(id){
    let el = document.querySelector(`[data-id='harga-${id}']`);
    let harga_baru = el.value;
    
    if (harga_baru == harga_berlaku[`s-${id}`]) {
        harga = null;
        el.value = '';
        bootbox.alert("update gagal, harga tidak berubah");
    }else{
        const data = {};
        data['group_harga_barang_id'] = "<?=$id;?>";
        data['barang_id'] = id;
        data[`harga_baru`] = harga_baru;
        data['mirroring'] = isMirroringActive;
        // alert(data['mirroring']);
        const url = "masters/group_harga/edit_insert";
        ajax_data_sync(url,data).done(function(data_respond  ,textStatus, jqXHR ){
            console.log(jqXHR);
            console.log(data_respond);
            // if (data_respond == 'OK') {
            // 	update_bayar();
            // 	if (data['pembayaran_type_id'] == 6 ) {
            // 		$("#portlet-config-giro").modal('toggle');
            // 	};
            // }else{
            // 	bootbox.confirm("Error, tolong muat ulang halaman", function(respond){
            // 		if(respond){
            // 			window.location.reload();
            // 		}
            // 	});
            // };
        });
    }
}

//==============generate grafik==================

function generateChartHarga(barang_id) {
    $('#price_statistics').html('');
}

function FilterTable(kode, id){
    
    let idx = 0;
    tabindexShow = [];
    document.querySelector(".radio-selected").classList.remove("radio-selected");
    document.querySelector(`#${id}`).closest("label").classList.add("radio-selected");
    const allRow = document.querySelectorAll('#general-table tbody tr');
    if (id=='hargaChange') {
        let indikator = 0;
        for (const baris of allRow) {
            let isChange = 0;

            const harga_list = baris.querySelector(".harga-input").value;
            if (harga_list.length > 1) {
                isChange = 1;
            }


            if (isChange == 1) {
                indikator = 1;
                baris.classList.remove('baris-hide');
                const tab = baris.querySelector("input").getAttribute("tabindex");
                tabindexShow.push(tab);
            }else{
                baris.classList.add('baris-hide');
            }
        }
    }else{
        for (const baris of allRow) {
            baris.classList.remove('baris-hide');
        }
    }
}

function activateMirroring(){
    const btn = document.querySelector("#mirroring-btn");
    bootbox.confirm(`${isMirroringActive ? 'Matikan' : 'Hidupkan'} mirroring harga ?`, function(respond){
        if (respond) {
            isMirroringActive = !isMirroringActive;
            btn.classList.remove(`${isMirroringActive ? 'default' : 'yellow-gold'}`);
            btn.classList.add(`${!isMirroringActive ? 'default' : 'yellow-gold'}`);
            notific8("lime", `Mirroring set to ${(isMirroringActive ? 'Active' : 'Off')}`);
        }
    })
}

function liveInput(id){
    
    percentageHarga(id);
    
    // const src = document.querySelector(`[data-id='harga-${id}']`).value;
    // document.querySelector(`[data-id='harga-${id}']`).value = src;
}

function percentageHarga(id){
    const hpp_selected = harga_hpp_raw[`s-${id}`];
    const harga_beli_selected = harga_beli_raw[`s-${id}`];
    let harga_input = document.querySelector(`[data-id='harga-${id}']`).value;
    harga_input = reset_number_format(harga_input);
    const hpp_perc_div = document.querySelector(`#hpp-perc`);
    const beli_perc_div = document.querySelector(`#harga-beli-perc`);
    const ppn_pembagi = 1 + parseFloat(ppn_berlaku/100);

    const isHargaDPP = document.querySelector("#isHargaDPP").checked;
    if (!isHargaDPP) {
        harga_input = harga_input/ppn_pembagi;
    }

    // console.log(harga_input.toString().length );
    if (harga_input > 99) {
        const hpp_diff = harga_input - hpp_selected;
        const beli_diff = harga_input - harga_beli_selected;
        let hpp_perc = hpp_diff/harga_input * 100;
        let beli_perc = beli_diff/harga_input * 100;
        
    
        if (hpp_perc_div) {
            hpp_perc_div.innerHTML = `[${hpp_perc.toFixed(2)}%]`;
        }

        if (beli_perc_div) {
            beli_perc_div.innerHTML = `[${beli_perc.toFixed(2)}%]`;
        }
    }
}

function updateRekap(){
    const up = document.querySelectorAll("#general-table .kolom-harga .fa-caret-up").length;
    const down = document.querySelectorAll("#general-table .kolom-harga .fa-caret-down").length;
    const t_harga = up + down;

    
    //======================================hitung percentage cash============================================
    let count_harga_up = 0;
    let count_harga_down = 0;

    let total_perc_up = 0;
    let total_perc_down = 0;
    const changed_cash_up = document.querySelectorAll(".kolom-harga .perc-up");
    for (const perc of changed_cash_up) {
        const v = parseFloat(perc.innerHTML);
        count_harga_up++;
        total_perc_up += v;

    }

    const changed_cash_down = document.querySelectorAll(".kolom-harga .perc-down");
    for (const perc of changed_cash_down) {
        const v = parseFloat(perc.innerHTML);
        count_harga_down++;
        total_perc_down += Math.abs(v);
    }

    const res_perc_up = (count_harga_up > 0 ? total_perc_up/count_harga_up : 0 );
    const res_perc_down = (count_harga_down > 0 ? total_perc_down/count_harga_down : 0 );
    
    //===============================================================================================

    // const radioUp = document.querySelector("#hargaup");
    // const radioDown = document.querySelector("#hargadown");
    // if (up > 0) {
    //     radioUp.removeAttribute("disabled");
    //     radioUp.closest('label').classList.remove('disabled');
    // }else if(up == 0){
    //     radioUp.setAttribute("disable", true);
    //     radioUp.closest('label').classList.add('disabled');
    // }

    // if (down > 0) {
    //     radioDown.disabled = false;
    //     radioDown.closest('label').classList.remove('disabled');
    // }else if(down == 0){
    //     radioDown.removeAttribute("disabled");
    //     radioDown.closest('label').classList.add('disabled');
    // }

    const berubah = document.querySelector("#hargaChange");
    
    if (up > 0 || down > 0) {
        berubah.removeAttribute("disabled");
        //berubah.disabled = false;
        berubah.closest('label').classList.remove('disabled');
    }else{
        // berubah.addAttribute("disabled");
        berubah.disabled = true;
        berubah.closest('label').classList.add('disabled');    
    }

    if (status_lock != 1) {
        if (t_harga > 0 ) {
            // console.log('asda', t_harga, t_kredit)
            document.querySelector("#lockBtn").disabled = false;
            // document.querySelector("#lockBtn").removeAttribute("disabled");
    
        }else{
            document.querySelector("#lockBtn").disabled = true;
        }
    }

    document.querySelector("#tCash").innerHTML = t_harga;
    document.querySelector("#up").innerHTML =  up + ' item' ;
    document.querySelector("#upRate").innerHTML =  res_perc_up.toFixed(2) + '%' ;
    document.querySelector("#down").innerHTML = down + ' item';
    document.querySelector("#downRate").innerHTML =  res_perc_down.toFixed(2) + '%';

}

function submitLock(){
    bootbox.confirm("LOCK daftar harga ?", function(respond){
        if (respond) {
            window.location.replace(baseurl+"masters/group_harga/edit_lock?id=<?=$id?>&harga_baru_info_id=<?=$harga_baru_info_id;?>");
        }
    })
}

function changeData(){
	$("#overlay-div").show();
}

function enableSaveUpdate(){
    document.querySelector(`#btn-header-save`).disabled=false;
}

function submitHeader(){
    const form = document.querySelector(`#form_add_data`);
    const nama = form.find("[name='nama']").value;
    const deskripsi = form.find("[name='deskripsi']").value;
    if (nama.length > 0) {
        form.submit();
    }

}

</script>