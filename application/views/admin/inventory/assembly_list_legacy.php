<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css'); ?>"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css'); ?>"/>
<?=link_tag('assets/global/plugins/bootstrap-datepicker/css/datepicker3.css'); ?>

<style>
    #qty-table-split{
        /* width:400px; */
        margin:auto;
        margin-top:30px;
        font-size:16px;
    }

    #qty-table-split tr td, #qty-table-split tr th{
        text-align:center;
        /* min-width:70px; */
        padding:2px;
    }

    .px-5{
    }

    #qty-table-split input{
        height:30px;
        width:70px;
        text-align:center;
        border:none;
        background:rgba(255,255,255,0.5);
        border-radius:2px;
    }

    #qty-table-split .subtotal{
        cursor:no-drop;
    }

    #qty-table-split input:focus{
        background:rgba(255,255,255,0.9);
        border:1px solid #ddd;
    }

    #qty-table-split input:disabled{
        background:rgba(255,255,255,0.2);
        border:1px solid #ddd;
    }

    #qty-table-split{
        border-spacing:2px;
    }

    #qty-table-split tr td{
        /* background:rgba(255,255,255,0.5); */
    }

    #qty-table-detail{
        background:rgba(255,255,255,0.5);
        margin:auto;
    }
    
    #qty-table-detail tr td, #qty-table-detail-edit tr td{
        border: 1px solid #ccc;
        /* padding: 3px; */
        text-align: center;
        min-width: 50px;
        font-size: 16px;
        border-radius:2px;
        cursor: pointer;
    }

    #qty-table-detail tbody tr:nth-child(5n) td{
        border-bottom:2px solid #333;
    }

    #qty-table-detail, #qty-table-detail-edit{
        /*position: absolute;*/
        right: 50px;
        top: 120px;
    }

    #qty-table-detail .selected{
        background: lime;
    }

    #stockContainer{
        background:lightgray;  
        position: relative;  
    }
    #formContainer{
        background:lightblue; 
        overflow:auto;
    }
    
    .section-div{
        padding:20px 10px;
        text-align:left; 
        border-radius:5px;
        height:450px !important;
        margin:10px 0px;
        position:relative
    }

    .section-header{

    }

    #tblSplitContainer{
        margin:20px 0px;
    }

    /* ================================================== */

    .searchInTable{
        height:30px;
        border:1px solid #ddd;
        border-radius:3px;
        width:250px; 
        text-align:center;
        padding:0 10px;
    }

    #general_table tr:first-child th:first-child{
        border-radius:3px 0px 0px 3px;
    }

    #general_table tr:first-child th:last-child{
        border-radius:0px 3px 3px 0px;
    }

    #general_table tr th{
        background:lightgray;
        padding:10px;
        height:45px;
    }

    #general_table tr th i{
        color:blue;
        margin-left:5px;
    }

    #general_table tr td, #general_table tr th{
        text-align:center;
        position: relative;
    }

    #general_table tr td:nth-child(n+5):nth-child(-n+6){
        background:rgba(230, 207, 207, 0.2);
    }

    #general_table tr td:nth-child(n+7):nth-child(-n+8){
        background:rgba(173, 216, 230,0.2);
    }

    .sorted{
        text-decoration:underline;
        font-size:1.1em;
    }

    /* ================================================== */

    #sticky-container{
        position:absolute;
        /* background:rgba(255,255,255,0.2); */
        background:transparent;
        height:600px;
        top:0;
        left:100%;
        width:200px;
    }

    .sticky-item{
        position:relative;
        background:#f1f58f;
        border-radius:2px;
        color:navy;
        width:175px;
        height:70px;
        rotate:-2deg;
        padding:5px 10px;
        margin-bottom:10px;
        font-size:14px;
    }

    .sticky-item p {
        margin:0px;
        padding:0px;
    }

    .sticky-header{
        font-weight:bold;
    }

    .sticky-footer{
        position:absolute;
        font-size:10px;
        font-weight:bold;
        right:0;
        bottom:0;
        height:20px;
        text-align:center;
        width:50px;
    }
    
    /* ------------------------------ */
    .select-container{
        position:relative;
        background:white;
        border-radius:2px;
    }

    .select-container-text{
        /* cursor:context-menu !important; */
        margin:0px;
        position:relative;
    }

    .select-container-text:before{
        content:'\02C7';
        content:'^';
        /* font-size:1.7em; */
        background: rgba(125, 125, 125, 0.3);
        display: block;
        position: absolute;
        bottom: 0px;
        right: 0px;
        height: 34px;
        padding:10px 7px;
        rotate:180deg;
        border-radius:2px;
        cursor: pointer;
    }

    .select-container-text{
        cursor:context-menu !important;
    }

    
    .select-option-container{
        border-radius:2px;
        background:#fff;
        position:absolute;
        z-index: 9999;
        font-size:14px;
        max-height:200px;
    }

    .select-option-search{
        width:90%;
        margin:5%;
        height:35px;
        padding:5px 10px;
        border:1px solid #ddd;
    }

    .select-option-container-out{
        display:none;
    }


    .select-option-list{
        list-style-type: none;
        padding:0px;
        height:150px;
        overflow-y:scroll;
    }

    .select-option-list li{
        padding:2px 10px;
        cursor:pointer;
    }
    
    .select-option-list li:nth-child(2n){
    }

    .select-option-list li:hover{
        background:#eee;
        font-weight:bold;
    }

    
</style>

<div class="page-content">
	<div class='container'>

		<div class="modal fade bs-modal-lg" id="portlet-config-split" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-body">
                        <div class="row">
                            <div class="col-xs-12 col-md-12">
                                <div class="col-xs-12 col-md-6">
                                    <div id="formContainer" class='section-div'>
                                        <form action="">
                                            <div class='section-header'>
                                                <h4 class='text-center'><b>FORM</b></h4>
                                            </div>
                                            <br>
                                            <div class="form-group">
                                                <label class="control-label col-md-4">Tanggal<span class="required">
                                                * </span>
                                                </label>
                                                <div class="col-md-6">
                                                    <input name='tipe_transaksi' value='3' hidden > 
                                                    <input name='id' id="penyesuaian-stok-id"  hidden>
                                                    <input name="tanggal" type="text" id='tanggal-split' readonly class="form-control" value="<?=date('d/m/Y');?>" />
                                                </div>
                                            </div>
                
                                            <div class="form-group">
                                                <label class="control-label col-md-4">Gudang <span class="required">* </span></label>
                                                <div class="col-md-6">
                                                    <select name='gudang_id' id='gudang-id-select' class="form-control">
                                                        <?foreach ($this->gudang_list_aktif as $row) {?>
                                                            <option <?=($gudang_id == $row->id ? 'selected' :"")?> value="<?=$row->id;?>" ><?=$row->nama;?></option>
                                                        <?}?>
                                                    </select>
                                                </div>
                                            </div> 
                
                                            <div class="form-group">
                                                <label class="control-label col-md-4">Barang <span class="required">* </span></label>
                                                <div class="col-md-6">
                                                    <div class='select-container'>
                                                        <label id='barang-id-select-text' class="form-control select-container-text" >Pilih..</label>
                                                        <input id='barang-id-select' hidden />
                                                        <div class='select-option-container select-option-container-out'>
                                                            <input type="search" class='select-option-search' id='barang-id-select-search' />
                                                            <ul class='select-option-list'>
                                                                <?foreach ($this->barang_list_aktif as $row) {?>
                                                                    <li data-barang="<?=$row->id;?>" <?=($barang_id == $row->id ? 'selected' : "")?> ><?=$row->nama_jual;?></li>
                                                                <?}?>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <!-- <select name='barang_id'  id='barang-id-select'  class="form-control">
                                                        <option value="" >Pilih</option>
                                                        <?/*foreach ($this->barang_list_aktif as $row) {?>
                                                            <option <?=($barang_id == $row->id ? 'selected' : "")?>  value="<?=$row->id;?>" ><?=$row->nama_jual;?><?=(is_posisi_id()==1 ? $barang_id.'-'.$row->id : '')?></option>
                                                        <?}*/?>
                                                    </select> -->
                                                </div>
                                            </div> 
                                            
                                            <div class="form-group">
                                                <label class="control-label col-md-4">Warna <span class="required">* </span></label>
                                                <div class="col-md-6">
                                                    <select name='warna_id'  id='warna-id-select'  class="form-control">
                                                        <option value="" >Pilih</option>
                                                        <?foreach ($this->warna_list_aktif as $row) {?>
                                                            <option <?=($warna_id == $row->id ? 'selected' :"")?>  value="<?=$row->id;?>" ><?=$row->warna_jual;?></option>
                                                        <?}?>
                                                    </select>
                                                </div>
                                            </div> 
                
                                            <div class="form-group">
                                                <label class="control-label col-md-4">QTY <span class="required">* </span>
                                                    <!-- <br/><br/> Roll -->
                                                </label>
                                                <div class="col-md-6">
                                                    <input type="text" autocomplete='off' class='form-control' name="qty_ori" placeholder='qty' id="qty-split-ori" />
                                                    <input hidden name="jumlah_roll_ori" id="roll-split-ori" value='1'/>
                                                </div>
                                            </div> 
                                            <div class="form-group">
                                                <label class="control-label col-md-4">Keterangan
                                                </label>
                                                <div class="col-md-6">
                                                    <input type="text" readonly class='form-control' id="keterangan" name="keterangan" value="Split Kain : <?=is_username();?>" />
                                                </div>
                                            </div> 

                                        </form>
                                    </div>
                                </div>

                                <div class="col-xs-12 col-md-6">
                                    <div id="stockContainer" class='section-div'>
                                        <h4 class='text-center'><b>STOK</b></h4>
                                        <br>

                                        <div class='note note-info' id='stok-notif'>NO DATA BARANG </div>
                                        <div style=" height:310px; overflow:auto">
                                            <table id='qty-table-detail'>
                                                <tbody></tbody>
                                            </table>
                                        </div>
                                        <div id="sticky-container">
                                            <?for ($i=0; $i < 0 ; $i++) {?> 
                                                <div class="sticky-item">
                                                    <p class='sticky-header'>crinkle blue</p>
                                                    <p class='sticky-body'><span class='qty-source'><?=$i+1;?>00</span> <i class="fa fa-hand-o-right"></i> <span class='qty-result'>40,50</span></p>
                                                    <p class='sticky-footer'>gudang</p>
                                                </div>
                                            <?}?>
                                        </div>
                                    </div>
                                    
                                </div>
                                <div class="col-xs-12">
                                    <div id="tblSplitContainer" style="padding:20px; background:lightgray; border-radius:5px;">
                                        <h4 class='text-center'><b>HASIL SPLIT</b></h4>
                                        <table id='qty-table-split'>
                                            <thead>
                                                <tr>
                                                    <th class='nama_satuan'>Yard</th>
                                                    <th class='nama_packaging'>Roll</th>
                                                    <th></th>
                                                    <th style='text-align:center'>Subtotal</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                            <tfoot>
                                                <tr style='border-top:2px solid #000; font-size:1.5em'>
                                                    <th><span id='total-qty-split'></span></th>
                                                    <th>
                                                        <span id='total-roll-split'></span><br/>
                                                    </th>
                                                    <th></th>
                                                    <th class='text-center'><span id='total-all-split' colspan='2'></span> / <span id='total-all-from'></span></th>
                                                </tr>
                                                <tr>
                                                    <th></th>
                                                    <th></th>
                                                    <th colspan='3'>
                                                        <small id='total-roll-info' style='font-size:0.5em; color:red'></small>
                                                    </th>
                                                </tr>
                                            </tfoot>
                                        </table>

                                    </div>
                                </div>
                            </div>
                            <!-- <div class="col-xs-0 col-md-3"></div> -->
                        </div>
					</div>

					<div class="modal-footer">
						<button type="button" class="btn btn-active blue" id="btnSplitSave" disabled>Save</button>
						<button type="button" class="btn default" data-dismiss="modal" id="btnSplitClose">Close</button>
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
							<a href="#portlet-config-split" data-toggle='modal' id="btnShowForm" class="btn green  btn-sm hidden-print" onclick="resetSplitForm()">
							<i class="fa fa-plus"></i> Split </a>
						</div>
					</div>
					<div class="portlet-body">
                        <table>
                            <tr>
                                <td>Lokasi</td>
                                <td class='padding-rl-5'> : </td>
                                <td>
                                    <b>
                                        <select name='gudang_id' id='gudang-id' style='width:200px;'>
                                        <option <?=($gudang_id == ''  ? 'selected' : "")?>  value="" >Pilih</option>
                                        <?foreach ($this->gudang_list_aktif as $row) {?>
                                            <option <?=($gudang_id == $row->id ? 'selected' :"")?> value="<?=$row->id;?>" ><?=$row->nama;?></option>
                                        <?}?>
                                        </select>
                                    </b>
                                </td>
                            </tr>
                            <tr>
                                <td>Nama</td>
                                <td class='padding-rl-5'> : </td>
                                <td>
                                    <b>
                                        <select name='barang_id'  id='barang-id' style='width:200px;'>
                                            <option <?=($barang_id == ''  ? 'selected' : "")?>  value="" >Pilih</option>
                                            <?foreach ($this->barang_list_aktif as $row) {?>
                                                <option <?=($barang_id == $row->id ? 'selected' : "")?>  value="<?=$row->id;?>" ><?=$row->nama_jual;?><?=(is_posisi_id()==1 ? $barang_id.'-'.$row->id : '')?></option>
                                            <?}?>
                                        </select>
                                    </b>
                                </td>
                                <td>
                                    
                                </td>
                            </tr>
                            <tr>
                                <td>Warna</td>
                                <td class='padding-rl-5'> : </td>
                                <td>
                                    <b>
                                        <select name='warna_id'  id='warna-id' style='width:200px;'>
                                            <option <?=($warna_id == ''  ? 'selected' : "")?>  value="" >Pilih</option>
                                            <?foreach ($this->warna_list_aktif as $row) {?>
                                                <option <?=($warna_id == $row->id ? 'selected' :"")?>  value="<?=$row->id;?>" ><?=$row->warna_jual;?></option>
                                            <?}?>
                                        </select>
                                    </b>
                                </td>
                                <td>
                                    
                                </td>
                            </tr>
                            <tr>
                                <td>Tanggal Split</td>
                                <td class='padding-rl-5'> : </td>
                                <td>
                                    <b>
                                        <input name='tanggal_start' id='tanggalStart' readonly class='date-picker' style='border:none; border-bottom:1px solid #ddd; width:100px;' value='<?=$tanggal_start;?>'>
                                        s/d
                                        <input name='tanggal_end' id='tanggalEnd' readonly class='date-picker2 ' style='border:none; border-bottom:1px solid #ddd; width:100px;' value='<?=$tanggal_end;?>'> 
                                    </b>
                                </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td style="padding-top:10px;"><button class='btn btn-block btn-xs default' onclick="getDataList()">Filter Data <i class='fa fa-search'></i></button></td>
                            </tr>
                        </table>
						<form action='' method='get'>
							
						</form>
						<hr/>
						<?
							$qty = 0;
							$roll = 0;
							?>
                        <div style="margin-bottom:15px;text-align:right; position:relative">
                            <div class="keterangan-jumlah-baris" style='position:absolute; bottom:0px;'>
                                Menampilkan <b style="font-size:14px;" class='countFilteredRow'>0</b> dari <b style="font-size:14px;" class='countAllRow'>0</b> baris
                            </div>
                            <div class="search-div" >
                                SEARCH : <input type="search"  class='searchInTable' style="font-size:14px;" placeholder="cari...">
                            </div>
                        </div>
						<table class="table table-striped" id="general_table">
							<thead>
								<tr>
									<th scope="col" rowspan='2' onclick="sortTable('tanggal','0')">
										Tanggal
									</th>
                                    <th scope="col" rowspan='2' onclick="sortTable('username','1')">
										User
									</th>
                                    <th scope="col" rowspan='2' onclick="sortTable('nama_gudang','2')">
										Gudang
									</th>
									<th scope="col" rowspan='2' onclick="sortTable('nama_barang','3')">
										Barang
									</th>
									<th scope="col" colspan='2' onclick="sortTable('qty', '4')" class='text-center'>
										Ori
									</th>
									<th scope="col" colspan='2' class='text-center' style='cursor:no-drop'>
										Hasil
									</th>
									<th scope="col" colspan='2'>
                                        
									</th>
								</tr>
							</thead>
							<tbody>
								
							</tbody>
                            <tfoot>
                                <tr>
                                    <th colspan='9' style='background:#eee'>
                                        <div class='paging'></div>
                                    </th>
                                </tr>
                            </tfoot>
						</table>

						
					</div>
				</div>
			</div>
		</div>
	</div>			
</div>

<script src="<?php echo base_url('assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js'); ?>" type="text/javascript"></script>

<script src="<?php echo base_url('assets/global/plugins/datatables/media/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets_noondev/js/table-advanced.js'); ?>"></script>
<script src="<?php echo base_url('assets/global/plugins/bootbox/bootbox.min.js'); ?>" type="text/javascript"></script>

<script>

var dataList = {};
const gudangList = <?=json_encode($this->gudang_list_aktif);?>;
const brgAktif = <?=json_encode($this->barang_list_aktif)?>;
// console.log(gudangList);
//============================filter dom=======================================
const brgFilter = document.querySelector("#barang-id");
const wrnFilter = document.querySelector("#warna-id");
const gdgFilter = document.querySelector("#gudang-id");
const tglStart = document.querySelector("#tanggalStart");
const tglEnd = document.querySelector("#tanggalEnd");

//============================form dom=======================================
const tglSplit = document.querySelector("#tanggal-split");
const brgSelect = document.querySelector("#barang-id-select");
const wrnSelect = document.querySelector("#warna-id-select");
const gdgSelect = document.querySelector("#gudang-id-select");

const mainTable = document.querySelector("#general_table");
const qtyOri = document.querySelector('#qty-split-ori');
const rollOri = document.querySelector('#roll-split-ori');
const keterangan = document.querySelector('#keterangan');
const id = document.querySelector('#penyesuaian-stok-id');
const btnSplitSave = document.querySelector('#btnSplitSave');
const formSplit = document.querySelector('#form_split');

const barangSearchInput = document.querySelector("#barang-id-select-search");

//============================table dom=======================================
const tblSplitContainer = document.querySelector("#tblSplitContainer");
const stockContainer = document.querySelector("#stockContainer");
const tblSplit = document.querySelector('#qty-table-split');
const tblDetail = document.querySelector('#qty-table-detail');
const hasilSplit = document.querySelector("#total-all-split");
const rollHasilSplit = document.querySelector("#total-roll-split");

// const splitInput = tblSplit.querySelectorAll('input');

//============================others=======================================
const selectContainer = document.querySelectorAll(".select-container-text");

selectContainer.forEach(el => {
    el.addEventListener("click", (e)=>{
        const optContainer = el.parentElement.querySelector(".select-option-container");
        const textLabel = el.parentElement.querySelector(".select-container-text");
        const cl = optContainer.classList;
        optContainer.style.transition="transition: height 0.5s, background-color 2s, transform 2s";
        // console.log(cl.value.indexOf('select-option-container-out'),'cl');
        if(cl.value.indexOf('select-option-container-out') !== -1){
            textLabel.style.border = 'none';
            optContainer.style.display="block";
            optContainer.querySelector(".select-option-search").focus();
            cl.remove("select-option-container-out");
        }else{
            textLabel.style.border = '1px solid #ddd';
            optContainer.style.display="none";
            cl.add("select-option-container-out");
        }
    })
});




let getSetting = sessionStorage.getItem("assambelyMainTable")

const sortSetting = {
    tanggal:1,
    username:0,
    nama_gudang:0,
    nama_barang:0,
    qty:0
}
let qtyOriEdit = null;
const stickyContainer = document.querySelector("#sticky-container");
const username = "<?=is_username()?>";
const today = new Date();
var dataDetail = {
    id:'',
    tanggal : `${today.getDate()}/${today.getMonth()+1}/${today.getFullYear()}`,
    gudang_id : gudangList[0].id,
    barang_id : '',
    warna_id : '',
    nama_barang : '',
    nama_warna : '',
    nama_gudang : '',
    nama_barang : '',
    nama_warna : '',
    qty: '',
    qty_data: {
        qty:'',
        jumlah_roll:''
    },
    jumlah_roll : 1,
    keterangan : `Split Kain : ${username}`,
    splitData : []
}



brgSelect.addEventListener("change", (e)=> {;get_stok_from_db()});
wrnSelect.addEventListener("change", (e)=> {;get_stok_from_db()});
gdgSelect.addEventListener("change", (e)=> {;get_stok_from_db()});
let stokan = {};

qtyOri.addEventListener('change', function(e){
    const ambil = this.value;
    cekQtyOri();
});

function resetSplitForm(){
    // addStickyNotes();
    const form = document.querySelector("#form_split");
    dataDetail.tanggal = `${today.getDate()}/${today.getMonth()+1}/${today.getFullYear()}`;
    // dataDetail.gudang_id = gudangList[0].id;
    // dataDetail.barang_id = '';
    dataDetail.warna_id = '';
    dataDetail.id = '';
    dataDetail.qty = '';
    dataDetail.keterangan = `Split Kain : ${username}`;
    dataDetail.splitData = [];

    stockContainer.style.backgroundColor="lightgray";
    tblSplitContainer.style.backgroundColor="lightgray";
    btnSplitSave.disabled = true;
    
    tblDetail.innerHTML = "";
    tblDetail.innerHTML = "<tbody></tbody>";
    document.querySelector('#stok-notif').style.display = 'block';
    document.querySelector('#total-all-from').innerHTML = "";

    if (dataDetail.gudang_id != '' && dataDetail.barang_id != '') {
        wrnSelect.click();
    }
    setDataToForm();
};

barangSearchInput.addEventListener("keyup", function(e){
    console.log(e.key);
    if (e.key === "ArrowDown") {
        
    }
});

qtyOri.addEventListener("keyup", function(e){
    e.preventDefault();
    const tIndex = this.getAttribute("tabindex");
    if (e.key === 'Enter' && this.value !='') {
        focusTblSplit();
    }
});

const initiateEventTblSplit = () =>{
    const splitInput = tblSplit.querySelectorAll('input');
    splitInput.forEach((input, index) => {
        input.addEventListener("keyup", function(e){
            e.preventDefault();
            const tIndex = this.getAttribute("tabindex");
            if (e.key === 'Enter' && this.value !='' && parseFloat(this.value) >= 0) {
                this.style.color = 'black';
                const indexNext = parseInt(tIndex) + 1;
                const next = document.querySelector(`[tabindex='${indexNext}']`);
                next.focus();
                if (indexNext%2 == 1 && next.value == '') {
                    next.value=1;
                }
                evaluateSplitQty();
            }else if(parseFloat(this.value) <= 0){
                this.style.color = 'red';
                btnSplitSave.disabled=true;
            }
        });
    });
}

document.addEventListener("keyup",(e)=>{
    if(e.keyCode === 220){
        document.querySelector('#btnShowForm').click();
    };
})


document.addEventListener('DOMContentLoaded',(event)=>{
    get_stok_from_db();
    getDataList();

    const optSearch = document.querySelectorAll('.select-option-search');
    optSearch.forEach(el => {
        const optContainer = el.parentElement;
        const options = optContainer.querySelector("ul").querySelectorAll("li");
        // console.log(options);
        el.addEventListener("keyup", (e)=>{
            const char = el.value;
            filterOptions(options, char, optContainer);
        });

        options.forEach(option=>{
            option.addEventListener("click", (e) => {
                const get_id = e.target.getAttribute('data-barang');
                const get_text = e.target.innerHTML;
                console.log(get_id);
                const container = option.parentElement.parentElement;
                container.style.display = 'none';
                barang_id=get_id;
                document.querySelector("#barang-id-select").value = get_id;
                document.querySelector("#barang-id-select-text").innerHTML = get_text;
                // console.log('1',e, option.parentElement.parentElement);
                // const parent = e.parentElement('.select-container');
                // console.log('2',parent);
                // parent.querySelector(".select-container-text").value = 
            });
        })
    });

    const srch = document.querySelector(".searchInTable");
    srch.addEventListener("keyup", function(e){
        filterMainTable();
    });

    

})

const getDataList = () => {
    const dialog = bootbox.dialog({
        message: '<p class="text-center mb-0"><i class="fa fa-spin fa-cog"></i> Loading Data...</p>',
        closeButton: false
    });
    fetch(baseurl+`inventory/data_penyesuaian`,{
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `tanggal_start=${tglStart.value}&tanggal_end=${tglEnd.value}&gudang_id=${gdgFilter.value}&barang_id=${brgFilter.value}&warna_id=${wrnFilter.value}`//+JSON.stringify(dataDetail)
    })
    .then((response) => response.json())
    .then((data) => {
        dataList = data;
        drawMainTable();
        dialog.modal('hide');
    });
}


btnSplitSave.addEventListener("click", ()=>{
    if(evaluateSplitQty()){
        const dialog = bootbox.dialog({
            message: '<p class="text-center mb-0"><i class="fa fa-spin fa-cog"></i> Saving...</p>',
            closeButton: false
        });

        const namaGudang = gdgSelect.selectedOptions;
        const namaBarang = brgSelect.selectedOptions;
        const namaWarna = wrnSelect.selectedOptions;

        for (let i = 0; i < namaGudang.length; i++) {
            dataDetail.nama_gudang = namaGudang[i].label;
            // dataDetail.nama_barang = namaBarang[i].label;
            dataDetail.nama_warna = namaWarna[i].label;
        }

        dataDetail.tanggal = tglSplit.value;
        dataDetail.gudang_id = gdgSelect.value;
        dataDetail.barang_id = brgSelect.value;
        dataDetail.warna_id = wrnSelect.value;
        dataDetail.qty = qtyOri.value;
        dataDetail.penyesuaian_stok_id  = id.value;
        const q = [];
        const jR= [];
        dataDetail.splitData.forEach((el,i) => {
            if (el.qty != '') {
                q.push(el.qty);
                jR.push(el.jumlah_roll);
            }
        });

        dataDetail.qty_data.qty = q.join(',');
        dataDetail.qty_data.jumlah_roll = jR.join(',')

        fetch(baseurl+`inventory/penyesuaian_stok_split_data_json`,{
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                // 'Content-Type': 'application/json'
            },
            body: `data=${JSON.stringify(dataDetail)}`//+JSON.stringify(dataDetail)
        })
		.then((response) => response.json())
		.then((data) => {
            if (data == 'OK') {
                dialog.find('.bootbox-body').html('Data tersimpan -->');
                setTimeout(() => {
                    dialog.modal('hide');
                }, 1000);
                getDataList();
                if (dataDetail.id  == '') {
                    addStickyNotes(dataDetail);
                    resetSplitForm();
                    return;
                }
                btnSplitClose.click();
                // dialog.find('.bootbox-body').html('Sukses, refreshing...');
                // window.location.reload();
            }
		});
        
        // formSplit.submit();
    }
});

// fungsi untuk ambil data Stok
const get_stok_from_db = () => {

    const barang_id = brgSelect.value;
    const warna_id = wrnSelect.value;
    const gudang_id = gdgSelect.value;

    stokan = {};
    if (barang_id !== '' && warna_id !== '' && gudang_id !== '') {
        document.querySelector('#stok-notif').style.display = 'none';
        // tblSplit.querySelector(".subtotal").innerHTML = "";
        tblSplit.querySelector("tfoot span").innerHTML = "";
        enableTblSplit();
        // $('#qty-table-detail tbody').html("<tr><td>Checking Qty....</td>");
        const dialog = bootbox.dialog({
            message: '<p class="text-center mb-0"><i class="fa fa-spin fa-cog"></i> Checking Stok...</p>',
            closeButton: false
        }); 
        var data = {};
        data['barang_id'] = barang_id;
        data['warna_id'] = warna_id;
        data['gudang_id'] = gudang_id;
        data['tanggal'] = $(`#tanggal-split`).val();
        var url = 'inventory/cek_barang_qty';
        ajax_data_sync(url,data).done(function(data_respond  /*,textStatus, jqXHR*/ ){
            // alert(data_respond)

            qtyTblDetail = [];
            qtyListDetail = [];
            let tblD = '';
            
            $.each(JSON.parse(data_respond),function(k,v){
                if (k==0) {
                    var qty = v[0].qty;
                    var roll = v[0].jumlah_roll;
                    $('#data-qty').attr('data-content',qty);
                    $('#data-roll').attr('data-content',roll);

                    $('#data-qty-add').html(qty);
                    $('#data-roll-add').html(roll);


                    qty_global = qty;
                    jumlah_roll_global = roll;

                    $('#form_add_data [name=qty]').attr('placeholder','');
                    $('#form_add_data [name=jumlah_roll]').attr('placeholder','');

                    // $('#form_add_data [name=qty]').attr('readonly',false);
                    // $('#form_add_data [name=jumlah_roll]').attr('readonly',false);
                    
                }else if(k==1){

                    // console.log(`detail length ${k}`,v.length);
                    for (var i = 0; i < v.length; i++) {
                        
                        let sisa_roll = parseFloat(v[i].roll_stok_masuk) + parseFloat(v[i].jumlah_roll_masuk) - v[i].roll_stok_keluar - v[i].jumlah_roll_keluar;
                        // if (v[i].qty == '100' || parseFloat(v[i].qty) == 100) {
                        //     console.log('detail',parseFloat(v[i].roll_stok_masuk) +'+'+ parseFloat(v[i].jumlah_roll_masuk) +'-'+ v[i].roll_stok_keluar +'-'+ v[i].jumlah_roll_keluar);
                        //     console.log(v[i].qty+':', sisa_roll);
                        // }
                        // console.log(v[i].qty+':', sisa_roll);
                        if (sisa_roll > 0) {
                            stokan[v[i].qty] = sisa_roll;
                            qtyListDetail[`s-${parseFloat(v[i].qty)}`] = sisa_roll;
                            for (var j = 0; j < sisa_roll; j++) {
                                if (v[i].qty != '100' && v[i].qty != 100) {
                                    qtyTblDetail.push(parseFloat(v[i].qty));
                                };
                            };
                        };
                    };

                    // console.log('si-100', qtyListDetail[`s-100`]);
                    let brs = Math.ceil((qtyTblDetail.length/5) );
                    // console.log('brs', brs);
                    // console.log('s-100', qtyListDetail[`s-100`]);
                    if (qtyListDetail[`s-100`] > 0) {
                        tblD += `<tr><td colspan='5' style='padding:5px; text-align:center' class='s-100' onclick="setQtyOri('100')"> 100 x ${qtyListDetail['s-100']}</td></tr>`;
                        tblD += `<tr><td colspan='5' style='background:#ccc'></td></tr>`;

                    };
                    for (var i = 0; i < brs; i++) {
                        // if(i%5 == 0 && i!=0){
                        //     tblD += `<tr><td colspan='5' style='background:#ccc'></td></tr>`;
                        // }

                        tblD += '<tr>';
                        for (var j = 0; j < 5; j++) {
                            if (typeof qtyTblDetail[(i*5) + j] !== 'undefined') {
                                tblD += `<td class='s-${qtyTblDetail[(i*5) + j]}' onclick="setQtyOri('${qtyTblDetail[(i*5) + j]}')">${qtyTblDetail[(i*5) + j]}</td>`
                                // console.log('l',tblD);
                                
                            };
                        };
                        tblD += '<tr>';
                    };
                };
            });

            // console.log(tblD, tblD,length);
            if (tblD.length > 0) {
                $('#qty-table-detail tbody').html(tblD);
                stockContainer.style.backgroundColor="lightblue";
                // console.log(stokan);
            }else{
                disableTblSplit();
                tblD = "<tr><td><span style='color:red'>NO STOK</span></td></tr>";
                stockContainer.style.backgroundColor="lightgray";
            }

            $('#qty-table-detail tbody').html(tblD);
            dialog.modal('hide');

            
                   

        });
    }else{
        stockContainer.style.backgroundColor="lightgray";
        disableTblSplit();
        $('#qty-table-detail tbody').html("");
        document.querySelector('#stok-notif').style.display = 'block';
    }
}

const setQtyOri = (qty) =>{
    qtyOri.value = qty;
    cekQtyOri();
}

const cekQtyOri = () =>{
    const qty = parseFloat(qtyOri.value);
    const penyesuaian_stok_id = id.value;
    if (typeof stokan[qty] === 'undefined' && dataDetail.id == '') {
        bootbox.alert(`STOK dengan yard <b>${qty}</b> tidak ada`);
        qtyOri.value = "";
        return false
    }else if(dataDetail.id != '' && qty !== qtyOriEdit && typeof stokan[qty] === 'undefined'){
        bootbox.alert(`STOK dengan yard <b>${qty}</b> tidak ada`);
        qtyOri.value = "";
        return false;
    }else{
        document.querySelector('#total-all-from').innerHTML = qtyOri.value;
        focusTblSplit();
        return evaluateSplitQty();
    }
}

const enableTblSplit = () => {
    const splitInput = tblSplit.querySelectorAll('input');
    tblSplitContainer.style.backgroundColor = 'lightblue';
    splitInput.forEach((input, index) => {
        // console.log(input.classList, input.classList.contains('subtotal'));
        if (!input.classList.contains('subtotal')) {
            input.disabled = false;
        }
    });
}

const disableTblSplit = () => {

    const splitInput = tblSplit.querySelectorAll('input');
    tblSplitContainer.style.backgroundColor = 'lightgray';
    splitInput.forEach((input, index) => {
        input.value = '';
        input.disabled = true;
    });
}

const evaluateSplitQty = () => {
    const q_ori = qtyOri.value;
    const q_split = hasilSplit.innerHTML;

    // console.log(parseFloat(q_ori) === parseFloat(q_split), q_ori, q_split);
    if (parseFloat(q_ori) === parseFloat(q_split)) {
        btnSplitSave.disabled = false;
        hasilSplit.style.color = 'black';
        return true;
    }else{
        hasilSplit.style.color = 'red';
        btnSplitSave.disabled = true;
        return false;
    }

}

const focusTblSplit = () => {
    document.querySelector('#portlet-config-split').scrollTo({top:50, behavior:'smooth'});
    const next = document.querySelector(`[tabindex='100']`);
    next.focus();
}

const editData = (id) => {
    const get = dataList.filter(el=>el.id==id);
    dataDetail = JSON.parse(JSON.stringify(get[0]));
    qtyOriEdit = dataDetail.qty;
    // console.log(get, dataDetail);
    setDataToForm();
    get_stok_from_db();
    cekQtyOri();
}

const removeData = (id, custom) => {
    const get = dataList.filter(el=>el.id==id);
    const dataDelete = JSON.parse(JSON.stringify(get[0]));

    bootbox.prompt({
        title: `Yakin hapus data <b>${dataDelete.nama_barang} ${dataDelete.nama_warna}</b> di <b>${dataDelete.nama_gudang}</b> ? <br/>Mohon input <b>PIN</b> untuk melanjutkan. ${custom} `,
        inputType: 'password',
        callback: function (result) {
            if (result) {
                
                if (result.length == 0) {
                    removeData(id, `<br/> <span style="color:red">PIN harus diisi</span>`);
                    return;
                }
    
                let dialog = bootbox.dialog({
                    message: '<p class="text-center mb-0"><i class="fa fa-spin fa-cog"></i> Cek PIN, Remove Data...</p>',
                    closeButton: false
                }); 
    
                fetch(baseurl+`inventory/penyesuaian_stok_split_remove`,{
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `pin=${result}&id=${id}`
                })
                .then((response) => response.json())
                .then((data) => {
                    if(data == "OK"){
                        dialog.find('.bootbox-body').html(`<p class="text-center mb-0">Sukses, Reload Data... <i class="fa fa-spin fa-cog"></i></p>`);
                        setTimeout(() => {
                            dialog.modal('hide');
                            getDataList();
                        }, 500);
                    }else{
                        dialog.modal('hide');
                        bootbox.confirm(`<p class="text-center mb-0"><i class="fa fa-warning"></i> ${data}, Coba Lagi ?</p>`,function(respond){
                            if(respond){
                                removeData(id,'');
                            }
                        }); 
                    }
    
                });
            }
        }
    });
    
    
    // setDataToForm();
    // get_stok_from_db();
    // cekQtyOri();
}

const setDataToForm = () =>{
    tglSplit.value = dataDetail.tanggal;
    gdgSelect.value = dataDetail.gudang_id;
    brgSelect.value = dataDetail.barang_id;
    wrnSelect.value = dataDetail.warna_id;
    id.value = dataDetail.id;
    qtyOri.value = dataDetail.qty;
    rollOri.value = dataDetail.jumlah_roll;
    keterangan.value = dataDetail.keterangan;
    drawTableSplit();
}

const drawTableSplit = () => {
    let sIndex = 100;
    tblSplit.querySelector('tbody').innerHTML = ``;
    const bodyRow = tblSplit.querySelector('tbody');
    let gTotal = 0;
    let rTotal = 0;
    dataDetail.splitData.forEach((row,index) => {
        
        const nR = bodyRow.insertRow(-1);
        
        const nC0 = nR.insertCell(0);
        const nC1 = nR.insertCell(1);
        const nC2 = nR.insertCell(2);
        const nC3 = nR.insertCell(3);
        const nC4 = nR.insertCell(4);

        nC0.innerHTML = `<input disabled tabindex="${sIndex + (2 * index)}" name='qty[]' class='qty' onchange="editQty('${index}')" value="${row.qty}" autocomplete="Off">`;
        nC1.innerHTML = `<input disabled tabindex="${sIndex + (2 * index) + 1}" name='jumlah_roll[]' onchange="editQty('${index}')" value="${row.jumlah_roll}" autocomplete="Off" class='jumlah_roll'>`;
        nC2.innerHTML = `=`;
        // nC3.className += (nC3.className ? " " : "")+"";
        
        const subtotal = row.qty*row.jumlah_roll;
        nC3.innerHTML = `<input value=${subtotal} disabled class='subtotal text-center'>`;
        rTotal += parseFloat(row.jumlah_roll);
        if (index === 0 ) {
            nC4.innerHTML += `<button style='border:none; background:transparent' onclick="addSplitTableRow()"><i class="fa fa-plus"></i></button>`
        }
        // nC4.innerHTML = `<input tabindex='-1' hidden readonly name='split_id[]' value="${row.id}" class='split_id'>`;
        gTotal += subtotal;
    });
    hasilSplit.innerHTML = gTotal;
    rollHasilSplit.innerHTML = rTotal;

    initiateEventTblSplit();

    let mT = 0;
    

    for (let index = bodyRow.querySelectorAll('tr').length; index < 3; index++) {
        addSplitTableRow();
    }

}

const addSplitTableRow = () => {
    const bodyRow = tblSplit.querySelector('tbody');

    const countRow = bodyRow.querySelectorAll('tr').length;
    const sIndex = 100;
    const index = countRow;
    const nR = bodyRow.insertRow(-1);
        
    const nC0 = nR.insertCell(0);
    const nC1 = nR.insertCell(1);
    const nC2 = nR.insertCell(2);
    const nC3 = nR.insertCell(3);
    const nC4 = nR.insertCell(4);

    const barang_id = document.querySelector("#barang-id-select").value;
    const warna_id = document.querySelector("#warna-id-select").value;
    const gudang_id = document.querySelector("#gudang-id-select").value;
    let disabled = 'disabled'
    if (barang_id !== '' && warna_id !== '' && gudang_id !== '') {
        disabled = '';
    }
    nC0.innerHTML = `<input ${disabled} tabindex="${sIndex + (2 * index)}" onchange="editQty('${index}')" name='qty[]' class='qty' value="" autocomplete="Off">`;
    nC1.innerHTML = `<input ${disabled} tabindex="${sIndex + (2 * index) + 1}"  onchange="editQty('${index}')" name='jumlah_roll[]' value="" autocomplete="Off" class='jumlah_roll'>`;
    nC2.innerHTML = `=`;
    // nC3.className += (nC3.className ? " " : "")+"subtotal text-center";
    nC3.innerHTML = `<input value='' disabled class='subtotal text-center'>`;
    if (index === 0 ) {
        nC4.innerHTML += `<button style='border:none; background:transparent' onclick="addSplitTableRow()"><i class="fa fa-plus"></i></button>`
    }

    initiateEventTblSplit();
}

const editQty = (indexRow) => {
    
    const bodyRow = tblSplit.querySelector('tbody');
    const rows = bodyRow.querySelectorAll('tr');
    let gTotal = 0;
    let rTotal = 0;
    rows.forEach((row,index) => {
        if (typeof dataDetail.splitData[index] === 'undefined' ) {
            dataDetail.splitData[index] = {};
            dataDetail.splitData[index].id = '';
            dataDetail.splitData[index].penyesuaian_stok_id = '';
            dataDetail.splitData[index].qty = "";
            dataDetail.splitData[index].jumlah_roll = "";
        }


        const qty = row.querySelector(`.qty`).value;
        let roll = row.querySelector(`.jumlah_roll`).value;
        roll = (roll == '' ? (qty != '' ? 1 : 0) : roll);
        const subtotal = qty*roll;
        rTotal += parseFloat(roll);
        gTotal += subtotal;
        row.querySelector(`.subtotal`).value = subtotal;
        dataDetail.splitData[index].penyesuaian_stok_id = id.value;
        dataDetail.splitData[index].qty = qty;
        dataDetail.splitData[index].jumlah_roll = roll;
    });
    hasilSplit.innerHTML = gTotal;
    rollHasilSplit.innerHTML = rTotal;
    evaluateSplitQty();
}

const addStickyNotes = (data) => {
    
    const color = (data.nama_gudang.toLowerCase().indexOf("toko") != -1 ? "lightgreen" : "lightgray");
    const stickyItem = `<div class="sticky-item">
        <p class='sticky-header'>${data.nama_barang} ${data.nama_warna}</p>
        <p class='sticky-body'><span class='qty-source'>${data.qty}</span> <i class="fa fa-hand-o-right"></i> <span class='qty-result'>${data.qty_data.qty}</span></p>
        <p class='sticky-footer' style='background:${color}'>${data.nama_gudang}</p>
        </div>`
    stickyContainer.innerHTML = stickyItem + stickyContainer.innerHTML;
    removeStickyNotes();
    
}

const removeStickyNotes = () => {
    const countSticky =  stickyContainer.querySelectorAll('.sticky-item');
    if (countSticky.length > 8) {
        const stickyItem = document.querySelector('.sticky-item'); 
        setTimeout(function(){
            stickyItem.style.transition = "opacity 0.5s ease-out";
            stickyItem.style.opacity=0;
            setTimeout(function(){
                stickyItem.parentNode.removeChild(stickyItem);
                removeStickyNotes();
            },1000);
        },1000);
    }
}


//========================================MAIN TABLE =================================
const drawMainTable = () => {
    const tblBody = mainTable.querySelector("tbody");
    emptyRow(tblBody);
    
    dataList.forEach((row,index) => {
        const splitData = row.splitData;
        let hsl = '';
        let qtyData = [];
        let rollData = [];
        splitData.forEach(dt => {
            qtyData.push(dt.qty);
            rollData.push(dt.jumlah_roll);
        });

            
        const nR = tblBody.insertRow(-1);
        const nC0 = nR.insertCell(0);
        const nC1 = nR.insertCell(1);
        const nC2 = nR.insertCell(2);
        const nC3 = nR.insertCell(3);
        const nC4 = nR.insertCell(4);
        const nC5 = nR.insertCell(5);
        const nC6 = nR.insertCell(6);
        const nC7 = nR.insertCell(7);
        const nC8 = nR.insertCell(8);

        nC0.innerHTML = row.tanggal;
        nC1.innerHTML = row.user.username;
        nC2.innerHTML = row.nama_gudang;
        nC3.innerHTML = row.nama_barang+ ' '+row.nama_warna;
        nC4.innerHTML = row.qty;
        nC5.innerHTML = row.jumlah_roll;
        nC6.innerHTML = qtyData.join(`<br/>`);
        nC7.innerHTML = rollData.join(`<br/>`);
        nC8.innerHTML = "";
        <?if (is_posisi_id() != 6) {?>
            nC8.innerHTML = `<a href="#portlet-config-split" data-toggle="modal" class="btn btn-xs green" onclick="editData('${row.id}')"><i class="fa fa-edit"></i></a>
                    <button class="btn btn-xs red" onclick="removeData('${row.id}','')"><i class="fa fa-times"></i></button>`;
        <?}?>
    });

    const nR = tblBody.insertRow(-1);
    nR.classList.add("no-data-info");
    nR.hidden = true;
    nR.innerHTML = `<td colspan='9'><i>No data</i></td>`;

    filterMainTable();
}

const emptyRow = (tblBody) => {
    tblBody.querySelectorAll("tr").forEach((row, index) => {
        tblBody.deleteRow(0);
    });
}

const drawTablePaging = (table) => {
    const tblBody = table.querySelector("tbody");
    const tblFoot = table.querySelector("tfoot");

}

const filterMainTable = () => {

    const str = mainTable.parentElement.querySelector('.searchInTable').value;
    const rows = mainTable.querySelector("tbody").querySelectorAll('tr');
    const countAllRow = rows.length - 1;
    let countFilteredRow = (str.length == 0 ? countAllRow : 0);

    rows.forEach((row,index) => {
        if(row.classList.value.includes("no-data-info") === false ){
            let isShow = false;
            const cells = row.querySelectorAll('td');
            if (str.length == 0) {
                row.hidden = false;
            }else{
                row.hidden = true;
                cells.forEach(cell => {
                    if (cell.textContent.toLowerCase().indexOf(str) !== -1 ) {
                        row.hidden = false;
                        isShow = true;      
                    }
                });
            }
            countFilteredRow += (isShow ? 1 : 0);
        }
    });
    mainTable.parentElement.querySelector('.countAllRow').innerHTML = countAllRow;
    mainTable.parentElement.querySelector('.countFilteredRow').innerHTML = countFilteredRow;

    if (countAllRow == 1 || countFilteredRow == 0) {
        mainTable.querySelector(".no-data-info").hidden = false;
    }else{
        mainTable.querySelector(".no-data-info").hidden = true;
    }
}

const sortTable = (prop, colIndex) =>{
    let set;
    const titleRow = mainTable.querySelector("thead tr").querySelectorAll('th');
    titleRow.forEach((el,idx) => {
        const sp = el.querySelector("span");
        if (typeof sp === 'object' && sp !== null) {
            el.removeChild(sp);
        }
        el.classList.remove('sorted');
    });
    
    if (sortSetting[prop]) {
        titleRow[colIndex].classList.add('sorted');
        titleRow[colIndex].innerHTML += "<span style='position:absolute;'><i class='fa fa-caret-down'></i></span>";
        set = 0;
        sortSetting.tanggal=0;
        sortSetting.username = 0;
        sortSetting.nama_gudang = 0;
        sortSetting.nama_barang = 0;
        sortSetting.qty = 0;        
        titleRow
    }else{
        titleRow[colIndex].classList.add('sorted');
        set = 1;
        sortSetting.tanggal=0;
        sortSetting.username = 0;
        sortSetting.nama_gudang = 0;
        sortSetting.nama_barang = 0;
        sortSetting.qty = 0;


        sortSetting[prop] = 1;
        titleRow[colIndex].innerHTML += "<span style='position:absolute;'><i class='fa fa-caret-up'></i></span>";
    }

    const tbody = mainTable.querySelector('tbody');
    const rows = tbody.querySelectorAll('tr');
    const arrRow = [];
   

    dataList.sort(function(a,b){
        if (prop === 'tanggal') {
            return (set ? new Date(date_format_default(a.tanggal)) - new Date(date_format_default(b.tanggal)) : new Date(date_format_default(b.tanggal)) - new Date(date_format_default(a.tanggal)));
        }else if(colIndex == 3){
            return (set ? (a[prop]+a['nama_warna']) > (b[prop]+b['nama_warna']) : (a[prop]+a['nama_warna']) < (b[prop]+b['nama_warna']) );
        }else {
            return (set ? a[prop].toLowerCase() > b[prop].toLowerCase() : a[prop].toLowerCase() < b[prop].toLowerCase() );
        }
    });


    drawMainTable();
        
}

//========================================MAIN TABLE  END=================================


const filterOptions = (options, char, container) => {
    let filteredOptions;
    // if (char.length === 0) {
    //     filteredOptions = options;
    // }else{
    //     filteredOptions = options.filter(option => option.consistOf(char) );
    // }

    options.forEach(option => {
        // console.log(option.textContent.toLowerCase().includes(char.toLowerCase()), option.textContent,  char);
        if (option.textContent.toLowerCase().indexOf(char.toLowerCase()) !== -1) {
            option.hidden = false;
            // console.log(option.textContent.toLowerCase().indexOf(char.toLowerCase()),option.textContent,  char);
        }else{
            option.hidden = true;
        }
    });

    // const ul = container.querySelector(".select-option-list");
    // ul.innerHTML = '';
    // filteredOptions.forEach(option => {
    //     ul.innerHTML += `<li onclick="selectOption('${option.id}')" >${option.nama_jual}</li>`;
    // });

    // prototype search all position characters
    // har = 'a';
    // arr = ['Alpen','Bolpe','WP2 a','Cacing', 'Blabla', 'DDEE'];
    // arr.forEach(val=>{
    //   const v = val.toLowerCase();
    //   const c = char.toLowerCase()
    //   const r = v.indexOf(c);
    //   if(r !== -1){
    //     console.log('1',r, c, v, v.length);
    //     let rs = r;
    //     console.log('2',rs);
    //     while(rs !== -1){
    //       rs++;
    //     	rs = v.indexOf(c, rs);
    //       console.log('3',rs);
    //     }
    //   };
    // });
    
}

</script>
