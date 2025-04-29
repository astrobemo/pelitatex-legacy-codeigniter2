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

    /* #general-table tr th:nth-child(1),
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
    } */

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
    

    .cash-input:focus, .history-cash-title, .kolom-cash, .tab-cash-title{
        background-color:rgba(212, 241, 244,0.6);
    }

    .kredit-input:focus, .history-kredit-title, .kolom-kredit, .tab-kredit-title{
        background-color:rgba(255,255,100,0.6);
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
                            <button class="btn btn-default btn-sm" onclick="testPrint()">
                                <i class="fa fa-print"></i> Print
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row margin-top-10">
            <div class="col-md-12">
                <div class="portlet light">

                
                    <div class="portlet-body">
                        <div class="tabbable tabs-left">
                            <ul class="nav nav-tabs ">
                                <?
                                $nama = '';
                                $deskripsi = '';
                                $tipe = '';
                                $isDefault = '';

                                foreach ($daftar_harga_all as $row) {?>
                                    <li class="<?=$row->tipe == 1 ? "tab-cash-title" : "tab-kredit-title"?>" >
                                        <a href="<?=base_url().is_setting_link('masters/group_harga/edit')?>?id=<?=$row->id;?>" >
                                        <?=$row->nama?> </a>
                                    </li>
                                <?}?>
                                <li class='active' style="background:lightpink">
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
                            <table id='table-data'>
                                    
                            </table>    
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
                            <table class="" id="general-table">
                                <!-- header table -->
                                <thead>
                                    <tr>
                                        <th colspan="<?=count($daftar_harga_all) + 2;?>">
                                            Filter : 
                                                <!-- <label for="">
                                                    <input type="checkbox" id="highlight-row" /> Highlight perbedaan</label> -->
                                        </th>
                                    </tr>
                                    <tr>
                                        <th>No</th>
                                        <th>Barang</th>
                                        <?foreach ($daftar_harga_all as $row) {?>
                                            <th><?=$row->nama;?></th>
                                        <?}?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?foreach ($this->barang_list_aktif as $key => $baris) {?>
                                        <?if ($key%5 == 0 && $key != 0) {?>
                                            <tr>
                                                <td colspan="<?=count($daftar_harga_all) + 2;?>"></td>
                                            </tr>
                                        <?}?>
                                        <tr>
                                            <td><?=$key + 1;?></td>
                                            <td><?=$baris->nama_jual;?></td>
                                            <?foreach ($daftar_harga_all as $cols) {?>
                                                <td>
                                                    <?if (isset($brg[$cols->id][$baris->id])) {
                                                       echo str_replace(",00","",number_format($brg[$cols->id][$baris->id],'2',",","."));
                                                    }?>
                                                </td>
                                            <?}?>
                                        </tr>
                                    <?}?>
                                </tbody>
                            </table>
                        
                        <hr/>
                    </div>
                </div>
            </div>
        </div>

    </div>
    
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
                                <label class="control-label col-md-3">Tipe Harga
                                </label>

                                <div class="col-md-6">
                                    <div>
                                        <label class='radio-inline'><input type="radio" id='tipeCashNew' <?=(set_value('tipe') == 1 ? 'checked' : '' );?> name='tipe' value='1'>CASH</label>
                                        <label class='radio-inline'><input type="radio" id='tipeKreditNew'  <?=(set_value('tipe') == 2 ? 'checked' : '' );?> name='tipe' value='2'>KREDIT</label>
                                    </div>
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
                                            <?if ($row->tipe==1) {?>
                                                <option value="<?=$row->id;?>"><?=$row->nama;?> </option>
                                            <?}?>
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

const checkboxHightlight = document.querySelector

document.addEventListener('DOMContentLoaded',(event)=>{
    docReady();

    $('.btn-save-new').click(function() {
        const form = document.querySelector("#form_new_data");
        const namaInput = form.querySelector("[name='nama']").value;
        let tipe = '';
        
        if($("#tipeCashNew").is(":checked")){
            tipe = 'CASH';
        }else if($("#tipeKreditNew").is(":checked")){
            tipe = 'KREDIT';
        }
        
        if (tipe == "") {
            bootbox.alert("Mohon isi tipe");
            return;
        }

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
    
});

function testPrint(){
    var w = window.open();
    w.document.write("======================\n");
    w.document.write("Hi\n");
    w.document.write("======================\n");
    w.document.close();
    w.print();
    w.close();
}

</script>