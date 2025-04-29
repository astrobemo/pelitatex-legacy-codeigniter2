<?php echo link_tag('assets/global/plugins/jqvmap/jqvmap/jqvmap.css'); ?>
<?php echo link_tag('assets/global/plugins/morris/morris.css'); ?>
<?=link_tag('assets/global/plugins/dropzone/css/dropzone.css'); ?>

		<div class="page-content">
			<div class='container'>
				<div class="row margin-top-10">
					<div class="col-md-12">
						<div class="portlet light" hidden>
							<div class="portlet-title">
								<div class="caption caption-md">
									<i class="icon-bar-chart theme-font hide"></i>
									<span class="caption-subject theme-font bold uppercase">Dashboard</span>
									<!-- <span class="caption-helper hide">weekly stats...</span> -->
								</div>
								<div class="actions">
									<!-- <div class="btn-group btn-group-devided" data-toggle="buttons">
										<label class="btn btn-transparent grey-salsa btn-circle btn-sm active">
										<input type="radio" name="options" class="toggle" id="option1">Today</label>
										<label class="btn btn-transparent grey-salsa btn-circle btn-sm">
										<input type="radio" name="options" class="toggle" id="option2">Week</label>
										<label class="btn btn-transparent grey-salsa btn-circle btn-sm">
										<input type="radio" name="options" class="toggle" id="option2">Month</label>
									</div> -->
								</div>
							</div>
							<div class="portlet-body">
							<form action="<?=base_url()?>admin/uploadFile" method="post" enctype="multipart/form-data">
								Select file to upload:
								<input type="file" name="file" id="fileToUpload">
								<input type="submit" value="Upload Image" name="submit">
							</form>

							<form  action="<?=base_url('admin/uploadFile'); ?> " class="dropzone" id="my-dropzone">
							</form>

							</div>
						</div>

						<div class="portlet light ">
							<div class="portlet-title">
								<div class="caption caption-md">
									<i class="icon-bar-chart theme-font hide"></i>
									<span class="caption-subject theme-font bold uppercase">Dashboard</span>
									<!-- <span class="caption-helper hide">weekly stats...</span> -->
								</div>
								<div class="actions">
								</div>
							</div>
							<div class="portlet-body">
								<input type="text" name="" id="newMessageInput">
								<button onclick="sendMessage()">send</button>
								<div id="chatContainer"></div>
							</div>
						</div>

						<div class="portlet light ">
							<div class="portlet-title">
								<div class="caption caption-md">
									<i class="icon-bar-chart theme-font hide"></i>
									<span class="caption-subject theme-font bold uppercase">Test Print</span>
									<!-- <span class="caption-helper hide">weekly stats...</span> -->
								</div>
								<div class="actions">
								</div>
							</div>
							<div class="portlet-body">
								<button class="btn btn-md green" onclick="testPrint()">Print</button>
								<div id="chatContainer"></div>
							</div>
						</div>
					</div>
				</div>
			</div>			
		</div>

		<script src="<?php echo base_url('assets/global/plugins/bootbox/bootbox.min.js'); ?>" type="text/javascript"></script>
		<script src="<?php echo base_url('assets/global/plugins/dropzone/dropzone.js'); ?>"></script>

		<script>
		jQuery(document).ready(function() {
			// cek_harga_barang
			// getText();
		});


        
		</script>

<? /*
<script src="<?=base_url('assets/global/plugins/morris/morris.min.js');?>" type="text/javascript"></script>
<script src="<?=base_url('assets/global/plugins/morris/raphael-min.js');?>" type="text/javascript"></script>


<script src="<?=base_url('assets/global/plugins/amcharts/amcharts/amcharts.js');?>" type="text/javascript"></script>
<script src="<?=base_url('assets/global/plugins/amcharts/amcharts/serial.js');?>" type="text/javascript"></script>
<script src="<?=base_url('assets/global/plugins/amcharts/amcharts/themes/light.js');?>" type="text/javascript"></script>
<script src="<?=base_url('assets/global/plugins/amcharts/amcharts/pie.js');?>" type="text/javascript"></script>

<script src="<?=base_url('assets_noondev/js/charts-amcharts.js');?>"></script>
<script src="<?=base_url('assets_noondev/js/index3.js'); ?>" type="text/javascript"></script>


<!-- END JAVASCRIPTS -->
*/?>

<script>
	
const PORT_MESSAGE = 3303;
const serverUrl = `ws://localhost:${PORT_MESSAGE}/chat`;
const chatHistory = [];

var connectionId = null;
var socket = null;
var connectionState = 'Connecting...';

const inputMessage = document.querySelector("#newMessageInput");
const chatContainer = document.querySelector("#chatContainer");


const initWebSocket = () => {
	if (connectionId === null) {
            
		const ws = new WebSocket(serverUrl, ["hameyean"]);

		ws.addEventListener('open', () => {
			console.log('WebSocket connection established');
			connectionState = 'Connected';
		});

		ws.addEventListener('close', () => {
			console.log('WebSocket connection closed');
			connectionState = 'Connection Closed';
		});

		ws.addEventListener('error', (error) => {
			console.error('WebSocket error:', error);
			connectionState = 'Connection Error';
			// Handle WebSocket errors
		});

		ws.addEventListener('message',(event) => {
			const message = JSON.parse(event.data);
			if (message.contentType === 'userID') {
				console.log(message.content);
				connectionId = message.content;
			}else if(message.contentType === 'message'){
				const newMsg = message.content;
				chatHistory.push(newMsg);
				updateMsg();
			}
		});

		socket = ws;

		return () => {
			if (ws.readyState === 1) { // <-- This is important
				ws.close();
				connectionId = null;
			}
		}
	}
}

const sendMessage = () => {
	let message = null;
	message = inputMessage.value;
	if (socket && socket.readyState === WebSocket.OPEN && message.trim() !== '') {
		socket.send(message);
		chatHistory.push(message);
		updateMsg();

	}
}

const updateMsg = () => { 
	let msgShow = ``;
	chatHistory.map((msg,index) => {
		msgShow += `${msg} <br/>`;
	});
	chatContainer.innerHTML = (msgShow);


}

(function () {
    initWebSocket();
})();

async function testPrint(){
	const dataTest = {
			"id": "936",
			"no_faktur": "TX:PJ01/2403/0379",
			"tanggal": "2024-03-25",
			"ongkos_kirim": "14000",
			"diskon": "0",
			"customer": {
				"id": "202",
				"nama": " Kakang",
				"alamat": "mobilkita (jl emong no 5)",
				"no_hp" :"082218676776"
			},
			"toko": {
				"id": "1",
				"nama": "TAMATEX",
				"alamat": "JL MAYOR SUNARYA 22",
				"kota": "BANDUNG",
				"telepon": "081211006459"
			},
			"user": {
				"id": "4",
				"username": "Destri"
			},
			"item_list": [
				{
					"barang_id": "5",
					"nama_barang": "Dusky Crinkle",
					"nama_satuan": "Yard",
					"warna_list": [
						{
							"warna_id": "7",
							"harga_jual": "17000",
							"nama_warna": "BLACK",
							"qty": "9.00",
							"subtotal": "153000.00"
						},
						{
							"warna_id": "58",
							"harga_jual": "17000",
							"nama_warna": "BLACK",
							"qty": "10.00",
							"subtotal": "170000.00"
						}
					]
				}
			],
			"pembayaran": [
				{
					"id": "976",
					"penjualan_id": "936",
					"pembayaran_type_id": "4",
					"dp_masuk_id": "",
					"amount": "345000",
					"keterangan": "",
					"user_id": "4",
					"nama_bayar": "TRANSFER",
					"username": "Destri"
				}
			]
		};
	const response = await fetch("http://localhost:3000/v0/print-invoice", {
      method: "POST",
	  body: JSON.stringify(dataTest)
    });
    const result = await response.json();
	console.log(result);
	
}


</script>