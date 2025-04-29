<link rel="stylesheet" type="text/css" href="<?= base_url('assets/css/map_style.css'); ?>" />

<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css'); ?>" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css'); ?>" />

<style>
    .history-kredit-info{
        position: relative;
        cursor: pointer;
    }

    .history-kredit-info-active{
        font-weight:bold;
    }

    .history-kredit-info:hover{
        font-weight:bold;
    }

    .history-kredit-info div{
        position:absolute;
        top:0px; 
        left:100%;
        background:#eee;
        padding:10px;
        z-index: 999;
        min-width: 150px;
    }

    .activeRow, .activeRow td{
        background-color:lightpink !important;
        font-weight:bold;
        font-size:1.05em;
    }

    /* #table-harga-edit tr td, #table-harga-edit tr th{
        border-right:1px solid #888;
        border-left:1px solid #888;
        padding: 2px 5px;
        font-size:16px;
    }

    
    #table-harga-edit .divider{
        background:#eee;
    }

    .kolom-cash{
        background-color:rgba(212, 241, 244,0.6);
    }

    .kolom-kredit{
        background-color:rgba(255,255,100,0.6);
    }


    #table-harga-edit input{
        display:inline-block;
        width:80px;
        padding-left:10px;
        background:transparent;
        border:1px solid #555;
    }

    #table-harga-edit thead th{
        position: -webkit-sticky;
        position: sticky;
        top: 50px;
        min-height: 50px;
        z-index:2;
    }

    #table-harga-edit thead tr:first-child th { position: sticky; top: 0; } 
    */

    #table-harga-edit .divider{
        padding:1px !important;
        height:2px !important;
        background:#ccc;
    }
    
    #table-harga-add tr td,
    #table-harga-add tr th{
        font-size:18px;
    }

    #table-harga-add tr th.active,
    #table-harga-add tr td.active{
        background:lightgreen;
    }

    #table-harga-add tr th,
    #table-harga-add tr td{
        border:1px solid #ddd;
    }

    #table-harga-edit tr td, 
    #table-harga-edit tr th{
        padding:5px;
        font-size:14px;
    }

    #table-harga-edit .kolom-cash, #table-harga-edit .kolom-kredit{
        padding-right:20px;
    }

    #s2id_customer_id_select_add .select2-choice{
        border:none;
    }

    .select2-results .select2-disabled{
        background:lightpink;
        cursor:no-drop;
    }

    .cash-input, .kredit-input, .cash-total, .kredit-total{
        text-align:center;
        width:70px;
        padding-left:5px;

    }
    
    .operator-symbol{
        font-size:12px;
        color:#555;
        display:none;
    }

    .kolom-cash{
        background-color:rgba(125,125,255,0.2);
    }

    .kolom-kredit{
        background-color:rgba(255,125,125,0.2);
        /* font-size:16px; */
    }

    .dropdown-menu .dropdown-item {
        white-space: normal;
    }

    .dropdown-menu .dropdown-item a{
        text-decoration:none;   
    }
    
    #table-harga-edit tr th:nth-child(2),
    #table-harga-edit tr th:nth-child(5),
    #table-harga-edit tr td:nth-child(2),
    #table-harga-edit tr td:nth-child(5){
        /* border-right:1px solid #999; */
    }

    #table-harga-edit tr td,
    #table-harga-edit tr th{
        border:1px solid #ccc;
    }


    .cash-input, .kredit-input,
    .cash-total, .kredit-total
    {
        border:none;
        background:#eee;
        width:100%;
    }

    .cash-input:not(:placeholder-shown), 
    .kredit-input:not(:placeholder-shown){
        background-color:rgba(125,125,255,0.2);
    }

    .no-padding{
        padding:0px;
    }


</style>

<div class="page-content">
    <div class='container'>

        
        <!-- tambah data -->
        <div class="modal fade bs-modal-lg" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-body">
                        <form action="<?= base_url('masters/group_harga_customer/daftar_insert') ?>" autocomplete='off' class="form-horizontal" id="form_add_data" method="post" onkeydown="return event.key != 'Enter';">
                            <h3 class='block'> Tambah Customer </h3>
                            <?php if(validation_errors()){?>
                                <div class="alert alert-block alert-warning fade in">
                                    <?php echo validation_errors(); ?>
                                </div>
                            <?}?>
                            
                            <?$cash_opt = 0; $kredit_opt = 0;
                            foreach ($harga_list as $row) {
                                $cash_opt += ($row->tipe == 1 ? 1 : 0);
                                $kredit_opt += ($row->tipe == 2 ? 1 : 0);
                            }

                            $cust_terdaftar = array();
                            foreach ($harga_customer as $row) {
                                $cust_terdaftar[$row->customer_id] = 1;
                            }
                            ?>
                            <table class='' id='table-harga-add'>
                                <thead>
                                    <tr>
                                        <th class="text-center" rowspan='2'>CUSTOMER</th>
                                        <th class="text-center" colspan='<?=$cash_opt?>'>CASH</th>
                                        <th class="text-center" colspan='<?=$kredit_opt?>'>KREDIT</th>
                                    </tr>
                                    <tr>
                                        <?foreach ($harga_list as $row) {?>
                                            <?if ($row->tipe==1) {?>
                                                <th id='header-harga-<?=$row->id?>' class="text-center header-harga-cash" style='width:100px'>
                                                    <?=$row->nama;?>
                                                </th>
                                            <?}?>
                                        <?}
                                        foreach ($harga_list as $row) {?>
                                            <?if ($row->tipe==2) {?>
                                                <th id='header-harga-<?=$row->id?>' class="text-center header-harga-kredit" style='width:100px'>
                                                    <?=$row->nama;?>
                                                </th>
                                            <?}?>
                                        <?}?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-center" style='width:250px; padding:0px !important;'>
                                            <select name="customer_id" class='form-control' id="customer_id_select_add" style='width:250px;' >
                                                <option value="">Pilih</option>
                                                <?foreach ($this->customer_list_aktif as $row) {?>
                                                    <option <?=(isset($cust_terdaftar[$row->id]) ? 'disabled' : '')?>  value="<?=$row->id;?>"><?=$row->nama;?> (<?=substr($row->alamat,0,20);?>) </option>
                                                <?}?>
                                            </select>
                                        </td>
                                        <?foreach ($harga_list as $row) {?>
                                            <?if ($row->tipe==1) {?>
                                                <td id='harga-<?=$row->id?>' class="text-center harga-cash" onclick="selectHarga(1,'<?=$row->id;?>')">
                                                </td>
                                            <?}?>
                                        <?}?>
                                        <?foreach ($harga_list as $row) {?>
                                            <?if ($row->tipe==2) {?>
                                                <td id='harga-<?=$row->id?>' class="text-center harga-kredit" onclick="selectHarga(2,'<?=$row->id;?>')">
                                                </td>
                                            <?}?>
                                        <?}?>
                                    </tr>
                                </tbody>
                            </table>
                            <input type="text" name="tipe_cash" id="hargacashSelectAdd" <?=(is_posisi_id()!=1 ? 'hidden' : '')?>>
                            <input type="text" name="tipe_kredit" id="hargakreditSelectAdd"  <?=(is_posisi_id()!=1 ? 'hidden' : '')?>>
                        </form>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn blue btn-save" onclick="saveNewData()">Save</button>
                        <button type="button" class="btn default btn-close" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade bs-modal-lg" id="portlet-config-edit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-body">
                        <form action="<?= base_url('masters/group_harga_customer/daftar_update') ?>" autocomplete='off' class="form-horizontal" id="form_edit_data" method="post" onkeydown="return event.key != 'Enter';">
                            <h3 class='block'> Edit Customer </h3>
                            <?php if(validation_errors()){?>
                                <div class="alert alert-block alert-warning fade in">
                                    <?php echo validation_errors(); ?>
                                </div>
                            <?php }?>
                            <div class="form-group">
                                <label class="control-label col-md-3">Customer
                                    <span class="required">*</span>
                                </label>

                                
                                <div class="col-md-6">
                                    <input name="customer_id" hidden id="customer_id_input_edit">
                                    <select class='form-control' id="customer_id_select_edit">
                                        <option value="0">DEFAULT</option>
                                        <?foreach ($this->customer_list_aktif as $row) {?>
                                            <option value="<?=$row->id;?>"><?=$row->nama;?> (<?=substr($row->alamat,0,20);?>) </option>
                                        <?}?>
                                    </select>
                                </div>
                            </div>

                            <hr/>
                            <table class='' id='table-harga-edit' width="100%">
                                <thead>
                                    <tr style="background-color:lightgreen">
                                        <th style='width:50px;' class='text-center'>No</th>
                                        <th style='width:150px;'>Barang</th>
                                        <th style='width:150px;' class='text-center'>
                                            <div hidden class="dropdown">
                                                <button class="btn btn-xs dropdown-toggle" type="button" id="dropdownHargacash" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <span>...</span>
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="dropdownHargacash">
                                                    <a class="dropdown-item" onclick="showHarga(1, 'edit','')" >none</a><br/>
                                                    <?foreach ($harga_list as $row) {?>
                                                        <?if ($row->tipe==1) {?>
                                                            <a class="dropdown-item" onclick="showHarga(1, 'edit','<?=$row->id?>')" ><?=$row->nama;?></a><br/>
                                                        <?}?>
                                                    <?}?>
                                                </div>
                                            </div>
                                            <?if (count($harga_list)) {?>
                                                <select name="tipe_cash" 
                                                    style='text-align:center;background:transparent; border:none; font-weight:bold; font-size:1em' 
                                                    id="hargaCashSelectEdit" onchange="showHarga(1, 'edit')">
                                                    <!-- <option value="">Pilih Harga Cash</option> -->
                                                    <?foreach ($harga_list as $row) {?>
                                                        <?if ($row->tipe==1) {?>
                                                            <option value="<?=$row->id;?>"><?=$row->nama;?> </option>
                                                        <?}?>
                                                    <?}?>
                                                </select>
                                                <span class="popover-info-harga" data-toggle="popover" title="Info" data-content="Klik untuk rubah harga"></span>    
                                            <?}else{?>
                                                <span>No group harga</span>
                                                <span class="popover-info-harga" data-toggle="popover" title="Info" data-content="Tidak ada group harga yang tersedia "></span>    
                                            <?}?>

                                        </th>
                                        <th class='text-center'>Selisih</th>
                                        <th class='text-center'>Final</th>
                                        <th style='width:150px;' class='text-center'>
                                            <select name="tipe_kredit" class='form-control text-center' style='background:transparent; border:none; font-weight:bold; font-size:1em' id="hargaKreditSelectEdit" onchange="showHarga(2, 'edit')">
                                                <option value="">Pilih Harga Kredit</option>
                                                <?foreach ($harga_list as $row) {?>
                                                    <?if ($row->tipe==2) {?>
                                                        <option value="<?=$row->id;?>"><?=$row->nama;?> </option>
                                                    <?}?>
                                                <?}?>
                                            </select>
                                        </th>
                                        <th class='text-center'>Selisih</th>
                                        <th class='text-center'>Final</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <?$idx=0;
                                    $idx_k = count($this->barang_list_aktif) + 1;
                                    foreach ($this->barang_list_aktif as $row) {
                                        $tab = $idx*2;
                                        $tab_k = $idx_k*2;
                                        $margin = "";
                                        ?>
                                        <?if ($idx%5 == 0 && $idx > 0) {?>
                                            <td class='divider' colspan='8' style='height:20px;'></td>
                                        <?}?>
                                        <tr id='baris-<?=$row->id;?>'>
                                            <td class='text-center'><?=$idx+1;?></td>
                                            <td><?=$row->nama_jual;?></td>
                                            <td class='kolom-cash text-right' id='cash-<?=$row->id;?>'></td>
                                            <td class='no-padding'> <span class='operator-symbol'>+</span> 
                                                <input type='text' readonly placeholder="0"
                                                    name='cash[<?=$row->id;?>]' 
                                                    id='input-cash-<?=$row->id;?>'
                                                    data-id='<?=$row->id?>' 
                                                    class='cash-input rupiah' 
                                                    onfocus="highlightRow('<?=$row->id;?>')" 
                                                    onchange="editHarga(1,'<?=$row->id;?>')" 
                                                    tabindex="<?=$tab+1;?>" 
                                                    >
                                            </td>
                                            <td class='no-padding'> <span class='operator-symbol'>=</span> 
                                                <input type='text' readonly placeholder="0" 
                                                    name='totalcash[<?=$row->id;?>]' 
                                                    id='total-cash-<?=$row->id;?>'
                                                    class='cash-total rupiah' 
                                                    onfocus="highlightRow('<?=$row->id;?>')" 
                                                    onfocusout="editTotalHarga('1','<?=$row->id;?>')" 
                                                    onchange="editTotalHarga(1,'<?=$row->id;?>')" 
                                                    tabindex="<?=$tab+2;?>" 
                                                    >
                                            </td>
                                            <td class='kolom-kredit text-right' id='kredit-<?=$row->id;?>'></td>
                                            <td class='no-padding'> 
                                                <span class='operator-symbol'>+</span> 
                                                <input type='text' readonly placeholder="0" 
                                                    name='kredit[<?=$row->id;?>]' 
                                                    id='input-kredit-<?=$row->id;?>'
                                                    data-id='<?=$row->id?>' 
                                                    class='kredit-input rupiah'  
                                                    onfocus="highlightRow('<?=$row->id;?>')" 
                                                    onchange="editHarga(2,'<?=$row->id;?>')" 
                                                    tabindex="<?=$tab_k+1;?>" 
                                                    >
                                            </td>
                                            <td class='no-padding'> <span class='operator-symbol'>=</span> 
                                                <input type='text' readonly placeholder="0" 
                                                    name='totalkredit[<?=$row->id;?>]' 
                                                    id='total-kredit-<?=$row->id;?>'
                                                    class='kredit-total rupiah'  
                                                    onfocus="highlightRow('<?=$row->id;?>')" 
                                                    onfocusout="editTotalHarga('2','<?=$row->id;?>')" 
                                                    onchange="editTotalHarga(2,'<?=$row->id;?>')" 
                                                    tabindex="<?=$tab_k+2;?>" 
                                                    >
                                            </td>
                                        </tr>
                                    
                                    <?$idx++; $idx_k++;}?>
                                </tbody>
                            </table>

                            <input name='id' hidden>
                        </form>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn blue btn-save" id='btn-submit-edit' onclick="submitFormEdit()">Save</button>
                        <button type="button" class="btn default btn-close" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>


        <!-- list data -->
        <div class="row margin-top-10">
            <div class="col-md-12">
                <div class="portlet light">

                <div class="tabbable tabs-left" style='margin-bottom:5px'>
                    <ul class="nav nav-tabs" style='padding:0px; margin:10px 0px;'>
                        <li>
                            <a href="<?=base_url().is_setting_link('master/customer_list')?>">
                                CUSTOMER
                            </a> 
                        </li>
                        <li class='active'>
                            <a href="#">
                                DAFTAR HARGA
                            </a> 
                        </li>
                    </ul>
                </div>

                    <!-- judul dan filtering -->
                    <div class="portlet-title">
                        <div class="caption caption-md">
                            <i class="icon-bar-chart theme-font hide"></i>
                            <span class="caption-subject theme-font bold uppercase"><?= $breadcrumb_small; ?></span>
                        </div>

                        <div class="actions">

                            <a id="show-portlet-config" href="#portlet-config" data-toggle='modal' onclick="dropdownCustActive()" class="btn btn-default btn-sm btn-form-add">
                                <i class="fa fa-plus"></i> Tambah
                            </a>
                        </div>
                    </div>

                    <div class="portlet-body">
                        <table class="table table-striped table-bordered table-hover" id="general_table">
                            <!-- header table -->
                            <thead>
                                <tr>
                                    <th scope="col" class="text-center">
                                        No
                                    </th>
                                    <th scope="col" class="text-center">
                                        Customer
                                    </th>
                                    <th scope="col" class="text-center">
                                        Harga Cash
                                    </th>
                                    <th scope="col" class="text-center">
                                        Harga Kredit
                                    </th>
                                    <th scope="col" style="min-width:150px !important" class="text-center">
                                        Actions
                                    </th>
                                </tr>
                            </thead>

                            <tbody>
                                <?foreach ($harga_customer as $key => $row) {?>
                                    <tr id="baris-<?=$row->customer_id;?>">
                                        <td class='text-center'><?=$key+1?></td>
                                        <td class='text-center'><?=$row->nama_customer?></td>
                                        <td class='text-center'><?=$row->nama_cash?></td>
                                        <td class='text-center'><?=$row->nama_kredit?></td>
                                        <td>
                                            <span class='customer_id'><?=$row->customer_id;?></span>
                                            <span data-type='1' id='tipe-1-<?=$row->customer_id;?>' hidden><?=$row->ghb_id_cash;?></span>
                                            <span data-type='2' id='tipe-2-<?=$row->customer_id;?>' hidden><?=$row->ghb_id_kredit;?></span>
                                            <a href="#portlet-config-edit" data-toggle='modal' 
                                                class='btn btn-xs green' 
                                                id='btn-id-<?=$row->customer_id;?>' 
                                                onclick="setEditData('<?=$row->customer_id;?>')">
                                                <i class='fa fa-edit'></i>
                                            </a>
                                            <button
                                                class='btn btn-xs red' 
                                                id='btn-delete-<?=$row->customer_id;?>' 
                                                onclick="deleteData('<?=$row->customer_id;?>')">
                                                <i class='fa fa-times'></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?}?>
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
<script src="<?php echo base_url('assets/global/plugins/bootbox/bootbox.min.js'); ?>" type="text/javascript"></script>

<script>

var isEditDaftar = false;
var harga_berlaku = [];
var customer_id_new = "<?=$customer_id_new;?>";

var customer_harga_detail = [];
customer_harga_detail[`s-0`] = [];
customer_harga_detail[`s-0`][1] = [];
customer_harga_detail[`s-0`][2] = [];

<?foreach ($this->customer_list_aktif as $row) {?>
    customer_harga_detail[`s-<?=$row->id?>`] = [];
    // customer_harga_detail[`s-<?=$row->id?>`][0] = [];
    customer_harga_detail[`s-<?=$row->id?>`][1] = [];
    customer_harga_detail[`s-<?=$row->id?>`][2] = [];
<?}?>
<?foreach ($harga_customer_detail as $row) {?>
    customer_harga_detail[`s-<?=$row->customer_id?>`][<?=$row->tipe;?>]['<?=$row->barang_id;?>'] = <?=$row->selisih_harga;?>;
<?}?>

document.addEventListener('DOMContentLoaded',(event)=>{
    docReady();
    // $('.popover-info-harga').popover();
})

function docReady(){
    if (customer_id_new.length > 0) {
        // document.querySelector(`#btn-id-${customer_id_new}`).click();
    }

    var map1 = {220: false};
    window.addEventListener('keydown',function(e) {
        if (e.keyCode in map1) {
            map1[e.keyCode] = true;
            if (map1[220]) {
                $('#portlet-config').modal('toggle');
                dropdownCustActive();
            }
        }
    });
    window.addEventListener('keyup', function(e) {
        if (e.keyCode in map1) {
            map1[e.keyCode] = false;
        }
    });

    // // console.log(currency.rupiah(10000));
    // inputCurrency(document.querySelectorAll("rupiah"),'rupiah');
    inputCurrency(document.querySelectorAll(".rupiah"),'rupiah');

    // const k_input = document.querySelectorAll('.kredit-input');
    // const k_total = document.querySelectorAll('.kredit-total');
    // const c_total = document.querySelectorAll('.cash-total');
    // document.querySelectorAll('.cash-input').forEach(el => {
    //     el.addEventListener("keyup", function(e){
    //         const t_idx = e.target.getAttribute("tabindex");
    //         if (e.key === 'Enter') {
    //             // document.querySelector();
    //         }
    //     });
    // });

    const input = document.querySelectorAll(`#table-harga-edit input[type='text']`);
    input.forEach(input => {
        input.addEventListener('keyup', function(event){
            if(event.key === 'Enter'){
                const idx = parseInt(this.getAttribute('tabindex'))+1;
                const tbl = document.querySelector(`#table-harga-edit input[tabindex='${idx}']`);
                tbl.focus();
                // const cell = tbl.children(`input[tabindex='${idx}']`);
                // console.log(tbl);
            }
        });
    });
    
}

jQuery(document).ready(function() {

    $('#customer_id_select_add, #customer_id_select_edit').select2({
        allowClear: true
    });

});

<?/* foreach ($this->barang_list_aktif as $row) {?>
    harga_berlaku[`id-<?=$row->id;?>`] = [];
    harga_berlaku[`id-<?=$row->id;?>`][`t-1`] = [];
    harga_berlaku[`id-<?=$row->id;?>`][`t-2`] = [];
<?}?>
<?foreach ($harga_berlaku as $row) {?>
    harga_berlaku[`id-<?=$row->group_harga_barang_id;?>`][`t-<?=$row->tipe?>`][<?=$row->barang_id?>] = <?=$row->harga;?>;
<?} */?>


function saveNewData(){
    const form = document.querySelector("#form_add_data");
    const customer_id = document.querySelector("#customer_id_select_add").value;

    const harga_cash = form.querySelector("#hargacashSelectAdd").value;
    const harga_kredit = form.querySelector("#hargakreditSelectAdd").value;

    if (customer_id.length>0) {
        if (harga_cash != 0 || harga_kredit != 0) {
            form.submit();
        }else{
            bootbox.alert("Minimal salah satu cash/kredit diisi");
        }
    }else{
        bootbox.alert("Mohon pilih customer");
        dropdownCustActive();
    }

}

function setEditData(customer_id){
    isEditDaftar = true;
    const custInput = document.querySelector('#customer_id_input_edit');
    const sel = document.querySelector('#customer_id_select_edit');
    sel.value = customer_id;
    custInput.value = customer_id;
    
    // $('[data-toggle="popover"]').popover();
    setTimeout(() => {
        $('.popover-info-harga').popover("show");
        document.querySelector(".popover-info-harga").click();
    }, 500);

    setTimeout(() => {
        $('.popover-info-harga').popover("hide");
    }, 5000);

    // sel.change();
    // sel.click();
    $('#customer_id_select_edit').change();

    if (customer_id == 0) {
        sel.setAttribute('disabled',"");
    }else{
        sel.removeAttribute('disabled');
    }    

    const tipe_1 = document.querySelector(`#tipe-1-${customer_id}`).innerHTML;
    const tipe_2 = document.querySelector(`#tipe-2-${customer_id}`).innerHTML
    document.querySelector(`#hargaCashSelectEdit`).value = tipe_1;
    document.querySelector(`#hargaKreditSelectEdit`).value = tipe_2;

    const tbl = document.querySelector('#table-harga-edit');
    if (tipe_1 !== '') {
        showHarga(1, 'edit');
    }else{
        document.querySelector('#hargaCashSelectEdit').value = '';
        showHarga(1, 'add');
    }

    if (tipe_2 !== '') {
        showHarga(2, 'edit');
    }else{
        document.querySelector('#hargaKreditSelectEdit').value = '';
        showHarga(2, 'edit');
    }

}

function deleteData(customer_id){
    bootbox.confirm("Yakin hapus data ? ", function (respond){
        if (respond) {
            const dialog = bootbox.dialog({
                message: `<p class="text-center mb-0"><i class="fa fa-spin fa-cog"></i> delete data...</p>`,
                closeButton: false
            });

            
            fetch(baseurl+`masters/group_harga_customer/remove_harga_customer?customer_id=${customer_id}`)
            .then((response) => response.json())
            .then((res) => {
                if (res==="OK") {
                    window.location.reload();
                }
            });
        }
    });

}

function highlightRow(id){

    const baris = document.querySelector(`#baris-${id}`);

    const active = document.querySelector('.activeRow');
    if (active) {
        document.querySelector('.activeRow').classList.remove("activeRow");
    }
    baris.classList.add("activeRow");
    

    // console.log(ini.querySelectorAll('.nama-barang'));
}

function showHarga(tipe, table, harga_id){
    // $(".popover-info-harga").hide();
    let customer_id = null
    let group_harga_barang_id = '';
    if (table == 'add') {
        customer_id = document.querySelector('#customer_id_select_add').value;
        if (tipe == 1 && document.querySelector(`#hargaCashSelectAdd`)) {
            group_harga_barang_id = document.querySelector(`#hargaCashSelectAdd`).value;
        }else if (document.querySelector(`#hargaKreditSelectAdd`)){
            group_harga_barang_id = document.querySelector(`#hargaKreditSelectAdd`).value;
        }
    }else{
        customer_id = document.querySelector('#customer_id_select_edit').value;
        if (tipe == 1 && document.querySelector(`#hargaCashSelectEdit`)) {
            group_harga_barang_id = document.querySelector(`#hargaCashSelectEdit`).value;
        }else if (document.querySelector(`#hargaKreditSelectEdit`)){
            group_harga_barang_id = document.querySelector(`#hargaKreditSelectEdit`).value;
        }
    }

    let input = null;
    let input_total = null;
    let label = '';
    let kolom = null;
    if (tipe==1) {
        kolom = document.querySelectorAll('.kolom-cash');
        input = document.querySelectorAll(".cash-input");
        input_total = document.querySelectorAll(".cash-total");
        label = 'cash';
    }else{
        kolom = document.querySelectorAll('.kolom-kredit');
        input = document.querySelectorAll(".kredit-input");
        input_total = document.querySelectorAll(".kredit-total");
        label = 'kredit';
    }

    if (group_harga_barang_id.length > 0) {

        const r3 = document.querySelectorAll('#table-harga-edit tbody tr td:nth-child(4)');
        const r5 = document.querySelectorAll('#table-harga-edit tbody tr td:nth-child(5)');
        const r7 = document.querySelectorAll('#table-harga-edit tbody tr td:nth-child(7)');
        const r8 = document.querySelectorAll('#table-harga-edit tbody tr td:nth-child(8)');

        const h3 = document.querySelector('#table-harga-edit tr th:nth-child(4)');
        const h5 = document.querySelector('#table-harga-edit tr th:nth-child(5)');
        const h7 = document.querySelector('#table-harga-edit tr th:nth-child(7)');
        const h8 = document.querySelector('#table-harga-edit tr th:nth-child(8)');

        if (customer_id != 0) {
            input.forEach((element,index) => {
                element.removeAttribute('readonly');
                input_total[index].removeAttribute('readonly');
            });

            h3.removeAttribute('hidden');
            h5.removeAttribute('hidden');
            h7.removeAttribute('hidden');
            h8.removeAttribute('hidden');

            r3.forEach((element, index) => {
                element.removeAttribute('hidden');
                r5[index].removeAttribute('hidden');
                r7[index].removeAttribute('hidden');
                r8[index].removeAttribute('hidden');
            });
        }else{
            h3.setAttribute('hidden','hidden');
            h5.setAttribute('hidden','hidden');
            h7.setAttribute('hidden','hidden');
            h8.setAttribute('hidden','hidden');

            r3.forEach((element, index) => {
                element.setAttribute('hidden','hidden');
                r5[index].setAttribute('hidden','hidden');
                r7[index].setAttribute('hidden','hidden');
                r8[index].setAttribute('hidden','hidden');
            });
        }
    
        const dialog = bootbox.dialog({
            message: `<p class="text-center mb-0"><i class="fa fa-spin fa-cog"></i> loading data...</p>`,
            closeButton: false
        });

        
        fetch(baseurl+`masters/group_harga_customer/get_harga_berlaku_by_group_id?group_harga_barang_id=${group_harga_barang_id}`)
        .then((response) => response.json())
        .then((data_res) => {
            data_res.forEach(row => {
                // console.log(row);
                const el = document.querySelector(`#${label}-${row.barang_id}`);
                if (el) {
                    const harga = (tipe==1 ? row.harga_cash : row.harga_kredit);
                    el.innerHTML = `<span class='raw-value' id='raw-${label}-${row.barang_id}' hidden>${parseFloat(harga)}</span>
                        ${currency.rupiah(harga )}`;
                }
            });
            dialog.modal('hide');
        });

        // harga_berlaku[`id-${group_harga_barang_id}`][`t-${tipe}`].forEach((element, index)=> {
        //     const el = document.querySelector(`#${label}-${index}`);
        //     if (el) {
        //         el.innerHTML = `<span class='raw-value' id='raw-${label}-${index}' hidden>${element}</span>
        //             ${currency.rupiah(element)}`;
        //     }
        // });
        
    }else{
        input.forEach((element,index) => {
            element.value='';
            element.setAttribute('readonly','readonly');
            input_total[index].setAttribute('readonly','readonly');
        });

        kolom.forEach(element => {
            element.innerHTML='';
        });
    }

    const inputs = document.querySelectorAll(`.${label}-input`);
    inputs.forEach(element => {
        const id = element.getAttribute('data-id');
        if (customer_harga_detail[`s-${customer_id}`]) {
            if (typeof customer_harga_detail[`s-${customer_id}`][tipe][id] !== 'undefined') {
                const inp = customer_harga_detail[`s-${customer_id}`][tipe][id];
                if (inp != 0) {
                    document.querySelector(`#input-${label}-${id}`).value = inp;
                }
            }
        }
        else{
            document.querySelector(`#input-${label}-${id}`).value = '';
            document.querySelector(`#total-${label}-${id}`).value = '';
        }
    });
    updateShowHarga(tipe, label);
}

function updateShowHarga(tipe, label){
    const input = document.querySelectorAll(`.${label}-input`);
    input.forEach(element => {
        const id = element.getAttribute('data-id');
        editHarga(tipe,id);         

        // if (element.value.length > 0 && element.value != 0) {
        // }
    });
    
}

function selectHarga(tipe, harga_id){
    const cust = document.querySelector(`#customer_id_select_add`);
    if (cust.value != '') {
        const tbl = document.querySelector("#table-harga-add");
        const isContent = document.querySelector(`#harga-${harga_id}`).innerHTML.trim().length;
        
        let label = '';
        if (tipe==1) {
            label = 'cash';
        }else{
            label = 'kredit';
        }
    
        const col = tbl.querySelectorAll(`.harga-${label}`);
        const col_head = tbl.querySelectorAll(`.header-harga-${label}`);
        col.forEach((element,index) => {
            element.innerHTML = '';
            element.classList.remove('active');
            col_head[index].classList.remove('active');
            document.querySelector(`#harga${label}SelectAdd`).value = '';
        });
    
        if (isContent == 0) {
            document.querySelector(`#harga${label}SelectAdd`).value = harga_id;
            document.querySelector(`#header-harga-${harga_id}`).classList.add('active');
            document.querySelector(`#harga-${harga_id}`).classList.add('active');
            document.querySelector(`#harga-${harga_id}`).innerHTML = "<i class='fa fa-check'></i>";
        }
    }else{
        bootbox.alert('mohon pilih customer dahulu');
    }
    
}

function dropdownCustActive(){
    setTimeout(function(){
        $('#customer_id_select_add').select2('open');
    },600)
}

function editHarga(tipe, id){
    if (tipe==1) {
        label = 'cash';
    }else{
        label = 'kredit';
    }

    let selisih = document.querySelector(`#input-${label}-${id}`).value;
    selisih = remove_currency.rupiah(selisih);
    if (document.querySelector(`#raw-${label}-${id}`)) {
        const harga_raw = document.querySelector(`#raw-${label}-${id}`).innerHTML;
        if (selisih.toString().length == 0) {
            selisih = 0;
        }
        const total = parseFloat(harga_raw) + parseFloat(selisih);
        // console.log(harga_raw, selisih);
        document.querySelector(`#total-${label}-${id}`).value = currency.rupiah(total);
    }

}

function editTotalHarga(tipe, id){
    if (tipe==1) {
        label = 'cash';
    }else{
        label = 'kredit';
    }

    const harga_raw = document.querySelector(`#raw-${label}-${id}`).innerHTML;
    let total = document.querySelector(`#total-${label}-${id}`).value;
    let selisih = 0;

    if (total.toString().length > 0 && total > 0) {
        selisih = parseFloat(total) - parseFloat(harga_raw);
    }else{
        const selisih = document.querySelector(`#input-${label}-${id}`).value;
        if (selisih.toString().length > 0 && selisih != 0) {
            total = parseFloat(harga_raw) + parseFloat(selisih);
        }else{
            document.querySelector(`#total-${label}-${id}`).value = harga_raw;
        }
    }
    document.querySelector(`#input-${label}-${id}`).value = selisih;
}

function submitFormEdit(){

    const cS = document.querySelector('#hargaCashSelectEdit').value;
    const kS = document.querySelector('#hargaKreditSelectEdit').value;
    const cust_edit = document.querySelector("#customer_id_select_edit").value;

    // console.log(cS.length,kS.length);

    if (cS.length + kS.length > 0) {
        const form = document.querySelector("#form_edit_data");
        const ci = form.querySelector("[name='customer_id']").value;
        // alert(ci);
        form.submit();
    }else{
        bootbox.alert("Mohon pilih paket harga yang sesuai");
    }
}


</script>