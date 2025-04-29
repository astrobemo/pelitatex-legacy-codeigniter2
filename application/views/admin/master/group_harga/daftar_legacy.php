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

</style>

<div class="page-content">
    <div class='container'>

        
        <!-- tambah data -->
        <div class="modal fade" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <form action="<?= base_url('masters/group_harga/daftar') ?>" autocomplete='off' class="form-horizontal" id="form_add_data" method="post" onkeydown="return event.key != 'Enter';">
                            <h3 class='block'> Data Barang </h3>
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

                                <div class="col-md-6">
                                    <select name="base_price" class="form-control" onchange="showHarga(1, 'edit')">
                                        <option value="0">Harga Master</option>
                                        <?foreach ($base_price_list as $row) {?>
                                            <?if ($row->tipe==1) {?>
                                                <option value="<?=$row->id;?>"><?=$row->nama;?> <?=($row->data_baru > 0 ? "<b>*</b>" : "")?> </option>
                                            <?}?>
                                        <?}?>
                                    </select>
                                    <div class='note note-warning'>tanda <b>*</b> artinya data baru yang belum di launch</div>
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
                            <i class="icon-bar-chart theme-font hide"></i>
                            <span class="caption-subject theme-font bold uppercase"><?= $breadcrumb_small; ?></span>
                        </div>

                        <div class="actions">

                            <a id="show-portlet-config" href="#portlet-config" onclick="resetData()" data-toggle='modal' class="btn btn-default btn-sm btn-form-add">
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
                                        Nama Group
                                    </th>
                                    <th scope="col" class="text-center">
                                        Tipe
                                    </th>
                                    <th scope="col" class="text-center">
                                        Deskripsi
                                    </th>
                                    <th scope="col" class="text-center">
                                        Info
                                    </th>
                                    <th scope="col" style="min-width:150px !important" class="text-center">
                                        Actions
                                    </th>
                                </tr>
                            </thead>

                            <tbody>
                                <?foreach ($harga_list as $row) {?>
                                    <tr id="baris-<?=$row->id;?>" >
                                        <td><span class="nama"><?=$row->nama;?></span></td>
                                        <td><span class="tipe" hidden><?=$row->tipe?></span><?=($row->tipe == 1 ? 'CASH' : 'KREDIT');?></td>
                                        <td><span class="deskripsi"><?=$row->deskripsi?></span></td>
                                        <td>
                                            <?if ($row->isDefault) {?>
                                                <i style="color:blue;" class="fa fa-check-circle"></i> Harga <b>Default</b> untuk transaksi <b><?=($row->tipe == 1 ? "CASH" : "KREDIT")?></b>
                                            <?}?>
                                            <?if ($row->jml_data < count($this->barang_list_aktif)) {?>
                                                <i class="fa fa-warning"></i> Data tidak sesuai dengan data barang
                                            <?}?>
                                        </td>
                                        <td>
                                            <!-- <button onclick="setEditData('<?=$row->id?>')"  href="#portlet-config" data-toggle='modal' class='btn btn-xs green'><i class='fa fa-edit'></i></button> -->
                                            <a href="<?=base_url().is_setting_link('masters/group_harga/edit')?>?id=<?=$row->id;?>" class='btn btn-xs yellow-gold'><i class='fa fa-search'></i></a>
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
jQuery(document).ready(function() {

    <?if(validation_errors()){?>
        
        document.querySelector("#show-portlet-config").click();
        
        // document.querySelector("#portlet-config").style.display = "block";
        // document.querySelector("#portlet-config").style.paddingRight = "17px";
        // console.log(document.querySelector("#portlet-config").style.display);
    <?}?>

    $('#general_table').DataTable({
        "paging": true,
        "pageLength": 100
    });

    //////////////////////////////////////////
    // save data 
    //////////////////////////////////////////
    $('.btn-save').click(function() {
        const form = $("#form_add_data");
        if(form.find("[name='nama']").val() != '' && tipe != ''){
            form.submit();
        }else{
            bootbox.alert("Mohon isi nama group & tipe");
        }
    });

});

function resetData(tipe){
    if (isEditDaftar) {
        const form = document.querySelector("#form_add_data");
        form.querySelector("[name='nama']").value='';
        form.querySelector("[name='id']").value='';
        form.querySelector("[name='deskripsi']").value='';
        document.querySelector("#tipeCash").checked=false;
        document.querySelector("#tipeKredit").checked=false;
    }
    // tipeCash
}

function setEditData(id){
    isEditDaftar = true;
    const baris = document.querySelector(`#baris-${id}`);
    const form = document.querySelector("#form_add_data");
    form.querySelector("[name='id']").value=id;
    form.querySelector("[name='nama']").value=baris.querySelector(".nama").innerHTML;
    form.querySelector("[name='deskripsi']").value=baris.querySelector(".deskripsi").innerHTML;
    const tipe = baris.querySelector(".tipe").innerHTML
    if (tipe == 1) {
        document.querySelector("#tipeCash").checked=true;
    }else{
        document.querySelector("#tipeKredit").checked=true;
    }

}


</script>