<link rel="stylesheet" type="text/css" href="<?= base_url('assets/css/map_style.css'); ?>" />

<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css'); ?>" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css'); ?>" />

<style>

    #general-table{
        width:100%;
        font-size:1.1em;
    }

    #general-table tr td, #general-table tr th{
        padding:5px;
        border:1px solid #ddd;
    }

    #general-table tr:nth-child(2n){
        background-color:#eee;
    }

    #general-table td:nth-child(3){
        /* background-color:#faa; */
    }

    .harga-berlaku-col{
        width:200px;
        text-align:center;
    }

    .harga-baru-col{
        padding:0 !important;
        width:200px;
    }

    .harga-baru-col input{
        text-align:center;
        width:100%;
        padding:0 0 0 10px;
        border:none;
        height:30px;
        background:transparent;
    }

    #general-table thead th{
        font-size:1.2em;
        position: -webkit-sticky;
        position: sticky;
        background: #fff;
        top: 50px;
        min-height: 50px;
        border-bottom: 2px solid #ddd;
        background: #eee;

        -webkit-full-screen{
            top: 0px;
        }
    }

    #general-table thead tr:first-child th { position: sticky; top: 0; }

    #general-table tbody tr td:first-child{position: sticky; left:0;}

    .activeInput{
        background-color:#FFFF66 !important;
        font-weight:bold;
        font-size:1.2em;
    }

    .activeInput input{
        height:40px;
    }

    #harga-history-container tr td{
        text-align:center;
        padding:5px 10px;
        vertical-align:middle;
    }

</style>

<div class="page-content">
    <div class='container'>

        
        <!-- tambah data -->
        <div class="modal fade" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <form action="<?= base_url('masters/group_harga/daftar_insert') ?>" autocomplete='off' class="form-horizontal" id="form_add_data" method="post" onkeydown="return event.key != 'Enter';">
                            <h3 class='block'> Tambah </h3>
                            <div class="form-group">
                                <label class="control-label col-md-3">Nama
                                    <span class="required">*</span>
                                </label>

                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="nama" />
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
                        <button type="button" class="btn blue btn-save">Save</button>
                        <button type="button" class="btn default btn-close" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>


        <!-- list data -->
        <div class="row margin-top-10">
            <div class="col-md-12">
                <div class="portlet light">

                    <!-- judul dan filtering -->
                    <div class="portlet-title">
                        <div class="caption caption-md">
                            <div class="tabbable tabs-left" style='margin-bottom:5px'>
                                <ul class="nav nav-tabs" style='padding:0px; margin:10px 0px;'>
                                    <li class='active'>
                                        <a href="">
                                            <i class="icon-bar-chart theme-font hide"></i>
                                            <span class="caption-subject theme-font bold uppercase">CUSTOMER</span>
                                        </a> 
                                    </li>
                                    <li>
                                        <a href="<?=base_url().is_setting_link()?>">
                                            <i class="icon-bar-chart theme-font hide"></i>
                                            <span class="caption-subject theme-font bold uppercase">GROUP HARGA</span>
                                        </a> 
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="actions">

                            <a href="#portlet-config" data-toggle='modal' class="btn btn-default btn-sm btn-form-add">
                                <i class="fa fa-plus"></i> Tambah
                            </a>
                        </div>
                    </div>

                    <div class="portlet-body">
                        <div class="row">
                            <div class="col-sm-8">
                                <table class="" id="general-table">
                                    <!-- header table -->
                                    <thead>
                                        <tr>
                                            <th scope="col" class="text-center">
                                                Nama Jual
                                            </th>
                                            <th scope="col" class="text-center">
                                                Harga Cash Berlaku
                                            </th>
                                            <th scope="col" class="text-center">
                                                Harga Cash Baru
                                            </th>
                                            <th scope="col" style="min-width:150px !important" class="text-center">
                                            </th>
                                            <th scope="col" class="text-center">
                                                Harga Kredit Berlaku
                                            </th>
                                            <th scope="col" class="text-center">
                                                Harga Kredit Baru
                                            </th>
                                        </tr>
                                    </thead>
        
                                    <tbody></tbody>
                                </table>
                            </div>
                            <div class="col-sm-4">
                                <div style='border:1px solid #ccc; width:350px; min-height:200px;padding:0 10px;position:fixed'>
                                    <h4 style='background:lightblue;padding:5px'>History Harga <b id='nama-barang-history'></b> : </h4>
                                    <div id="harga-history-container">

                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <hr>
                            <div class="col-xs-12">
                                <button class="btn btn-lg blue">RELEASE</button>
                                <button class="btn btn-lg blue">PRINT HARGA</button>
                            </div>
                        </div>
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

<?$pre = array_values($harga_history);?>
const data_history = <?=json_encode($pre)?>;
const harga_history = [];
const tbl_history = [];
const harga_berlaku = [];
<?foreach ($this->barang_list_aktif as $row) {?>
    harga_berlaku[`s-<?=$row->id?>`] = <?=$harga_berlaku[$row->id];?>;
    harga_history[`s-<?=$row->id?>`] = {};
    tbl_history[`s-<?=$row->id?>`] = '';
    <?if (isset($harga_history[$row->id])) {?>
        harga_history[`s-<?=$row->id?>`] = <?=json_encode($harga_history[$row->id])?>;
    <?}?>
<?}?>


jQuery(document).ready(function() {

    // console.log(harga_berlaku);
    data_history.forEach(list => {
    let before = '-'; 
    let hargaBefore = 0; 
    let counter = 0;
    list.forEach(baris => {
        let nData = countAndFormatDays(before,baris.tanggal_archive, baris.harga);
        let diff = parseFloat(baris.harga) - parseFloat(hargaBefore);
        let arrow = '';
        if (counter > 0) {
            arrow = `<i class="fa fa-${diff < 0 ? 'angle-down' : 'angle-up'}"></i>`;
        }
        diff = Math.abs(diff);


        tbl_history[`s-${baris.barang_id}`] += `
            <tr>
                <td>${counter+1}</td>
                <td>${nData[0]}</td>
                <td>${nData[1]}</td>
                <td>${nData[2]}</td>
                <td>${arrow} ${(counter==0 ? '' : diff)}</td>
            </tr>
        `;
        counter++;
        hargaBefore = baris.harga;
        before = baris.tanggal_archive;

        if (list.length == counter) {
            let nData = countAndFormatDays(baris.tanggal_archive,"<?=date('Y-m-d H:i:s');?>", harga_berlaku[`s-${baris.barang_id}`]);
            if (counter > 0) {
                arrow = `<i class="fa fa-${diff < 0 ? 'angle-down' : 'angle-up'}"></i>`;
            }
            tbl_history[`s-${baris.barang_id}`] += `
                <tr>
                    <td>${counter}</td>
                    <td>${nData[0]}</td>
                    <td>today</td>
                    <td>${nData[2]}</td>
                    <td>${arrow} ${(counter==0 ? '' : diff)}</td>
                </tr>
            `;
        }
    });

    
});

    $('#general_table').DataTable({
        "paging": true,
        "pageLength": 100
    });


    //////////////////////////////////////////
    // save data 
    //////////////////////////////////////////
    $('.btn-save').click(function() {
        const form = $("#form_add_data");
        if(form.find("[name='nama']").val() != ''){
            form.submit()
        }else{
            bootbox.alert("Mohon isi nama group");
        }
    });

});


function highlightRow(id){
    console.log(id);
    console.log(harga_history[`s-${id}`]);
    let ini = $(`#row-${id}`).closest('tr');
    $("#general-table tr").removeClass("activeInput");
    $(`#row-${id}`).addClass('activeInput');
    $("#harga-history-container").html(tbl_history[`s-${id}`]);
    $("#nama-barang-history").html(ini.find('.nama-barang').html());

}


function unhighlightRow(id){
    const el = document.querySelector(`input[data-id='${id}']`);
    console.log(el.value);
    if(el.value == 0){
        el.value='';
    }
}

function showDiff(id){
    const el = document.querySelector(`input[data-id='${id}']`);
    const col = document.querySelector(`#row-${id} td:nth-child(4)`);
    let harga_input = el.value;
    harga_input = reset_number_format(parseFloat(el.value));
    const diff = harga_input - parseFloat(harga_berlaku[`s-${id}`]);
    let arrow = `<i class="fa fa-${diff == 0 ? '' : (diff < 0 ? 'angle-down' : 'angle-up')}"></i>`;
    col.innerHTML = arrow+' '+Math.abs(diff);


}

function countAndFormatDays(before, after, harga){
    // console.log(change_number_format('10000'));
    
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



</script>