<link href="<?=base_url('assets/global/plugins/bootstrap-modal/css/bootstrap-modal.css');?>" rel="stylesheet" type="text/css"/>


<style type="text/css">
	.container{
		width: 100%;
	}

	#dataContainer{
		position: absolute;
		top:0px;
		left: 0px;
		padding: 15px;
		background: rgba(64,64,64,0.8);
		color: white;
	}

	#dataContainer input{
		color: black;
		padding-left: 5px;
		max-width: 100px;
	}

	.table-qty tr th{
		padding: 5px;
		/*padding-right: 10px;*/
	}

	.table-qty tr td{
		padding: 5px 10px 10px 5px;
		/*padding-right: 10px;*/
	}

	.table-qty-border tr td, .table-qty-border tr th{
		border: 2px solid #333;
	}

	.table-qty-border-2 tr td, .table-qty-border-2 tr th{
		border: 2px solid #ccc;
	}

	.table-qty input{
		border: none;
		background: transparent;
	}

	.table-qty tr:nth-child(2n){
		background: #eee;
	}

	.barang-on-edit{
		background: yellow;
	}

	.keterangan-input{
		/*display:none;*/
		text-align:left; 
		width: 100%;
	}

	/*.keterangan-input:focus{
		display: block;
	}*/

	.data-container{
		position: relative;
		text-align: right;
	}

	/*.data-container:hover .additional{
		display: block;
	}*/

	.stok-display{
		position: relative;
	}

	.stok-display:hover .additional{
		display: block;
	}

	.data-selected{
		border: 2px solid orange !important;
	}

	.additional{
		position: absolute;
		padding: 5px;
		background: #ffff88;
		color: black;
		top: 0px;
		left: 100%;
		z-index: 9999;
		display: none;
		width: 150px;
		text-align:left;
	}

	.health-bar{
		position: absolute;
		bottom: 4px;
		left: 0px;
		height: 5px;
		width: 100%;
		margin: 0px;
		padding: 0px;
	}

	.performance-bar{
		background: #a3cbf7;
		position: absolute;
		font-size: 0.8em;
		top: 0px;
		left: 0px;
		height: 100%;
		margin: 0px;
		padding: 0px;
		text-align: center;
		display: none;
		color: rgba(0,0,0,0.5);
	}

	.data-container:hover .performance-bar-toggle{
		display: block;
	}

	.stok-bar{
		background: #c6ffb8;
		position: absolute;
		display: inline;
		top: 0px;
		left: 0px;
		margin: 0px;
		padding: 0px;
		font-size: 0.5em;
	}

	.empty-bar{
		background: #ff928a;
		position: absolute;
		display: inline;
		top: 0px;
		left: 0px;
		margin: 0px;
		padding: 0px;
		font-size: 0.5em;
	}
	.request-bar{
		display: inline;
		position: absolute;
		display: inline;
		top: 0px;
		left: 0px;
		/* background: #99ddff; */
		margin: 0px;
		padding: 0px;
		font-size: 0.5em;
	}

	.outstanding-bar{
		display: inline;
		position: absolute;
		display: inline;
		top: 0px;
		left: 0px;
		background: #758283;
		margin: 0px;
		padding: 0px;
		font-size: 0.5em;
	}

	.table-request-print tr th{
		text-align: center;
		vertical-align: middle;
	}

	.table-qty .thead th{
		position: -webkit-sticky;
		position: sticky;
		top: 50px;
		z-index: 99;
		min-height: 50px;
		border-bottom: 2px solid #ddd;
		background: #fff;

		-webkit-full-screen{
			top: 0px;
		}
	}

	.finished{
		background-color:#d1ffba !important;
	}

	.pre-finished{
		background: repeating-linear-gradient(
	45deg,
	#fff,
	#fff 10px,
	#e0ffd1 10px,
	#e0ffd1 20px
	);
	}

	.revised{
		background-color:yellow;
	}
	.urgent{
		background-color:#fca9a4;
	}
	.revised-urgent{
		color:red;
		background-color:yellow;
	}


	.data-container-select{
		cursor: pointer;
	}

	.data-container:hover .additional-show-history{
		/*display: block;*/
	}

	.request-selected{
		background-color: yellow;
	}

	.request-selected-old{
		/* background-color: khaki; */
		background-image: linear-gradient(to right, lightblue, khaki);
		/* border:2px solid blue !important; */
	}

	#general_table2 tr td{
		vertical-align: middle;
	}

	.table-request-print tr td, .table-request-print tr th{
		padding:5px 8px;
		border-right:1px solid #999;
		border-left:1px solid #999;
	}

	.status-cell-hover{
		background-color:#ddd;
	}

	/*.additional input:focus{
		display: block;
	}

	.additional:hover{
		display: block;
	}*/

	#tanggalRequestAktif{
		/*border: none;*/
		width: 120px;
		/*border-bottom: 1px solid #333;*/
	}

	#tanggalRequestAktif:focus{
	}

	#general-table tr:hover td:first-child{
		background-color:lime;
	}

	#requestTableContainer{
		overflow-x:auto;
	}

	@media print {

		* {
			-webkit-print-color-adjust: exact !important;
			print-color-adjust: exact !important;
		}

		a[href]:after {
			content: none !important;
		}

		table{
			font-size: 12px;
		}

		.nama-bulan{
			background: #d4e3ff !important;
			/*background: yellow;*/
		}

	}



</style>

<div class="page-content">
	<div class='container'>

		<?
		//==================initialize data=============================
			$color_list = [];
			$nama_barang_terpilih = '';
			$po_baru_list = array();

			// bulan berjalan bulan tanggal awal, float biar ilangin 0 di bulan 1 digit
			$bulan_berjalan=(float)date("m", strtotime($tanggal_start));

			// tgl start itu tanggal mulai
			// tg forecast itu tanggal pengambilan data, yaitu tanggal start - 1 year
			$tgl1 = new DateTime(is_date_formatter($tanggal_start_forecast));
			$tgl2 = new DateTime(is_date_formatter($tanggal_end_forecast));

			$tgl1_now = new DateTime(is_date_formatter($tanggal_start));
			$tgl2_now = new DateTime(is_date_formatter($tanggal_end));

			// generate / get tiap bulan dari tanggal start sampai tanggal end
			// biar bs di crawl tiap tahun
			$jarak = date_diff($tgl1, $tgl2);
			$range_tanggal = $jarak->format('%m')+1;
		
			// P1M - interval 1 month
			$interval = new DateInterval('P1M');
			$periode = new DatePeriod($tgl1, $interval, $tgl2->setTime(0,0,1));
			$periode_now = new DatePeriod($tgl1_now, $interval, $tgl2_now->setTime(0,0,1));

			foreach ($periode as $date) {
				$bln = $date->format("Y-m");
				$qty_total_bulan[$bln] = 0;
				if (!isset($qty_data[$bln])) {
					$qty_data[$bln] = array();	
				}
	
				// foreach ($qty_data[$bln] as $key => $value) {
				// 	// qty total per bulan
				// 	$qty_total_data[$key] += $value;
				// }
			}

			// inisialisasi data untuk setiap warna yang terdaftar 
			// yang dipilih untuk request
			foreach ($data_warna as $row) {
				$color_list[$row->warna_id] = ($row->kode_warna != '' ? $row->kode_warna : '#ccc');
				$total_qty[$row->warna_id]=0;
				$total_amount[$row->warna_id]=0;
				$total_trx_warna_all[$row->warna_id]=0;
				
				// $qty_total_data[$row->warna_id] = 0;
				$qty_stok[$row->warna_id] = 0;
				$qty_stok_update[$row->warna_id] = 0;
				$qty_stok_data[$row->warna_id] = '';
				
				$qty_outstanding[$row->warna_id] = 0;
				$qty_outstanding_update[$row->warna_id] = 0;
				$qty_jual[$row->warna_id] = [];
				$qty_request[$row->warna_id] = [];
				$qty_request_warna[$row->warna_id] = 0;
			}

		//==================end initialize data=============================
		//==================set  data dari db=============================
		// get data stok barang
		foreach ($stok_barang as $row) {
			foreach ($this->gudang_list_aktif as $row2) {
				if (isset($qty_stok[$row->warna_id])) {
					$qty_stok[$row->warna_id] += $row->{'gudang_'.$row2->id.'_qty'};
					if ($row->{'gudang_'.$row2->id.'_qty'} > 0) {
						$qty_stok_data[$row->warna_id] .= $row2->nama.' : '.number_format((float)$row->{'gudang_'.$row2->id.'_qty'},'0',',','.').'<br/>';
					}
				}else{
				}
			}
		};

		// setting data outstanding 
		$po_number_list = array(); 
		$po_qty_list = array(); 
		$po_list = array();
		foreach($data_outstanding_po as $row){
			//qty oustanding per warna
			$qty_outstanding[$row->warna_id] = $row->qty_outstanding;

			//breakdown data po untuk jadi object data per po
			// isi nya qty, tanggal , no po, qty
			$po_qty[$row->warna_id] = explode(",", $row->po_qty);

			//breakdown data
			$po_number[$row->warna_id] = explode("??", $row->po_number);
			$batch_tanggal[$row->warna_id] = explode(",", $row->batch_tanggal);
			$batch_id[$row->warna_id] = explode(",", $row->po_pembelian_batch_id);
			$qty_detail[$row->warna_id] = explode(",", $row->qty_data);
			foreach ( $batch_tanggal[$row->warna_id] as $key => $value) {

				// $value = tanggal 
				// di index tanggal biar bisa di sort nanti
				// jadi nanti bikin tampilan nya ada lah ke pinggir itu beda po, ke bawah beda warna
				// jadi matrix nya
				//======================================================//
				//	WARNA		|	PO - 1		|	PO - 2	|	TOTAL	//
				//------------------------------------------------------//
				//	MERAH		|	100			|	123		|	223		//	
				//======================================================//
				// karena dia dirender pertama dan ke pinggir dl makanya po number per tanggal
				// biar bisa di sort ke kanan
				// karena dia cmn render 1 dimensi ke kanan aja makanya array nya cmn by tanggal 
				$po_number_list[$value][$batch_id[$row->warna_id][$key]] = $po_number[$row->warna_id][$key];

				// udah gt set daftar qty po nya per warna 
				$po_qty_list[$row->warna_id][$value][$batch_id[$row->warna_id][$key]] = $po_qty[$row->warna_id][$key];
                
				// tambahan ini mksdnya porsi tambahan buat setiap po ORI sebesar 10%
				// buat nant perbandingan klo ada request yang lebih dari PO
				// tapi  beda nya di bawah 10% total po warna
				// syarat dia punya tambahan adalah po terakhir dari daftar
				$tamb = 0;
                if($key == count($batch_tanggal[$row->warna_id]) - 1){
                    $tamb = $po_qty[$row->warna_id][$key]/10; 
                }

				// ini set po sisa nya dari tiap po by warna
                $po_qty_sisa[$row->warna_id][$value][$batch_id[$row->warna_id][$key]] = $qty_detail[$row->warna_id][$key];
				// assign tamb tadi ke po
                $po_tamb[$row->warna_id][$value][$batch_id[$row->warna_id][$key]] = $tamb;
			}
		};

		//penting supaya berdasar kronologi tanggal
		ksort($po_number_list);

		

		// generate data forecast
		foreach ($data_forecast as $row) {
			$total_trx[$row->bulan_jual] = 0;
			
			$warna_id_list = explode(",", $row->warna_id);
			$qty_data[$row->bulan_jual] = explode(",", $row->qty_data);
			
			// qty asli klo ada perubahan
			$qty_history[$row->bulan_jual] = explode(",", $row->qty_history);
			// tanggal perubahan
			$created_history[$row->bulan_jual] = explode(",", $row->history_created);

			// qty data ini basis data qty
			$qty_data[$row->bulan_jual] = array_combine($warna_id_list, $qty_data[$row->bulan_jual]);
			// qty asli klo ada perubahan
			$qty_history_data[$row->bulan_jual] = array_combine($warna_id_list, $qty_history[$row->bulan_jual]);
			$created_history_data[$row->bulan_jual] = array_combine($warna_id_list, $created_history[$row->bulan_jual]);
		}


		// generate total warna per bulan request
		foreach ($data_warna as $row) {
			foreach ($periode as $date) {
				$i = $date->format('n');

				// ini key bulan forecast nya
				$key = $date->format('Y-m');
				
				// $qtyOri = '';
				if (isset($created_history_data[$key][$row->warna_id]) && $created_history_data[$key][$row->warna_id] != '-') {
					if (isset( $qty_history_data[$key][$row->warna_id])) {
						// qty total tiap bulan
						$qty_total_bulan[$key] += (isset($qty_data[$key][$row->warna_id]) ? $qty_data[$key][$row->warna_id] : 0);
						// $qtyList = explode('??', $qty_history_data[$key][$row->warna_id]); 
						// foreach ($qtyList as $key2 => $value2) {
						// 	if ($key2 == 0) {
						// 		$qtyOri += $value2;
						// 	}
						// }
					}
				}

				// if ($qtyOri == '') {
				// 	$qtyOri = (isset($qty_data[$key][$row->warna_id]) ? $qty_data[$key][$row->warna_id] : 0);
				// }
			}
			
		}


		// foreach ($data_outstanding_update as $row) {
		// 	$qty_outstanding_update[$row->warna_id] = $row->qty_outstanding;
		// }

		// foreach ($stok_barang_update as $row) {
		// 	foreach ($this->gudang_list_aktif as $row2) {
		// 		if (isset($qty_stok_update[$row->warna_id])) {
		// 			$qty_stok_update[$row->warna_id] += $row->{'gudang_'.$row2->id.'_qty'};
		// 		}
		// 	}
		// };
		

		// get data penjualan yang berefek terhadap qty forecast
		foreach ($penjualan_berjalan as $row) {
			$qty_jual[$row->warna_id][$row->bulan_berjalan]	= $row->qty;
		}

		/**
		 * ========================initialisasi request barang data===================================*
		 * klo di atas data by po
		 * klo in by request
		 *  **/
		// tanggal request yang dipilih, dapat diganti oleh user
		// no batch no batch request
		// tanggal awal tanggal request pada batch 1
		// klo ini batch 1 berarti tanggal awal = tanggal
		$request_barang_id = ''; $tanggal = ''; $no_batch = ''; 
		$locked_by = ''; $locked_date = ''; $no_request = ''; 
		$no_request_lengkap = ''; $kode_supplier = '';
		$request_barang_batch_id = ''; $nama_supplier = ''; $supplier_id = ''; 
		$attn=""; $tanggal_awal = '';

		foreach ($request_barang_data as $row) {
			$request_barang_batch_id = $row->id;
			$request_barang_id = $row->request_barang_id;
			$tanggal = $row->tanggal;
			$tanggal_awal = $row->tanggal_awal;

			$no_batch = $row->batch;
			$no_request = $row->no_request;
			$no_request_lengkap = $row->no_request_lengkap;
			
			$locked_by = $row->locked_by;
			$locked_date = $row->locked_date;
			
			$nama_supplier = $row->nama_supplier;
			$supplier_id = $row->supplier_id;
			$attn = $row->attn;
		}

		// data nama barang yang sedang diedit
		$nama_jual = ''; $nama_beli = '';
		foreach ($data_barang as $row) {
			$nama_jual = $row->nama_jual;
			$nama_beli = $row->nama;

		}


		// initialisasi barang progress terkirim
		$terkirim = array();
		$total_terkirim = array();
		// is tercapai apakah sudah tercapai 100%
		// hanya mmmberi status tercapai saja bukan status selesai
		// status selesai akan digunakan oleh user
		$isTercapai = array();
		// inisialisasi qty terkirm by warna
		$terkirim_by_warna = array();
		foreach ($barang_terkirim as $row) {
			// breakdown data
			$bReq = explode(",", $row->bulan_request);
			$qReq = explode(",", $row->qty_request);
			$req = array_combine($bReq, $qReq);

			foreach($bReq as $key => $value){
				//inisialisasi dl spya bisa di "+="
				$terkirim[$row->barang_id][$row->warna_id][$value] = 0;
				$total_terkirim[$value] = 0;
			}
			

			//===========barang masuk=============
			// break down tanggal masuk
			$tanggal_masuk = explode(',', $row->tanggal_masuk);
			// break down batch by po bukan request
			$batchId = explode(",", $row->po_pembelian_batch_id);
			// break down tiap qty barang masuk
			$qData = explode(",", $row->qty_data);
			
			// proses data barang masuk
			for ($i=0; $i < count($tanggal_masuk) ; $i++) {
				// set barang masuk ke bulan berjalan
				// data bulan masuk yg di save di database itu per tanggal 1, 
				// jadi convert barang masuk ke tgl 1
				$b_now = '';
				if ($tanggal_masuk[$i] != '') {
					$b_now = date('Y-m-01', strtotime($tanggal_masuk[$i]));
				}
				
				// kalau tanggal masuk < dari bulan request, 
				// tambah ke data terkirim // ini harus dicek ulang
				if ($b_now != '' && strtotime($tanggal_masuk[$i]) < strtotime($bReq[0]) ) {
					$terkirim[$row->barang_id][$row->warna_id][$bReq[0]] += $qData[$i];

					// if (is_posisi_id() == 1 && $row->barang_id == 10) {
					// 	echo '0 ',$qData[$i].'<br/>';
					// }

					if ($req[$bReq[0]] < ($terkirim[$row->barang_id][$row->warna_id][$bReq[0]] + $qData[$i])) {
						$pers = round($terkirim[$row->barang_id][$row->warna_id][$bReq[0]]/$req[$bReq[0]] * 100);
						
						$sisa_next = $terkirim[$row->barang_id][$row->warna_id][$bReq[0]] - $req[$bReq[0]];
						$terkirim[$row->barang_id][$row->warna_id][$bReq[0]] = $req[$bReq[0]];

						$b_next = date('Y-m-01', strtotime("+1 month", strtotime($bReq[0])));
						if (is_posisi_id() == 1 && $row->barang_id == 26 && $row->warna_id == 8 ) {
							// echo '<h1>'.$b_next.'</h1><br/>';
						}

						if (!isset($terkirim[$row->barang_id][$row->warna_id][$b_next])) {
							$terkirim[$row->barang_id][$row->warna_id][$b_next] = 0 ;
						}
						
						$terkirim[$row->barang_id][$row->warna_id][$b_next] += $sisa_next;
						// if (is_posisi_id() == 1 && $row->barang_id == 10) {
						// 	echo '1 ',$sisa_next.'<br/>';
						// }
					}
				}else if ($b_now != '' && $req[$b_now] < ($terkirim[$row->barang_id][$row->warna_id][$b_now] + $qData[$i]) ) {
					// if (is_posisi_id() == 1 && $row->barang_id == 26 ) {
					// 	// echo '<h1>TEST</h1>';
					// 	echo $row->warna_id.' '.$req[$b_now] .'<'. ($terkirim[$row->barang_id][$row->warna_id][$b_now] .'+'. $qData[$i]).'<br/>';
					// }
					if (is_posisi_id() == 1 && $row->barang_id == 10) {
						echo '2 ',$req[$b_now] .'<'. $terkirim[$row->barang_id][$row->warna_id][$b_now] .'+'. $qData[$i].'<br/>';
					}
					$b_next = date('Y-m-01', strtotime("+1 month", strtotime($b_now)));
					$sisa_next = $terkirim[$row->barang_id][$row->warna_id][$b_now] + $qData[$i] -  $req[$b_now] ;
					$pers = 0;
					if ($req[$b_now] > 0) {
						$pers = round($sisa_next/$req[$b_now] * 100, 2);
					}
					
					// cek klo barang masuk sisa nya < 10% dari request
					if ($pers <= 10 && $i == (count($tanggal_masuk)-1) ) {
						if (is_posisi_id() == 1 && $row->barang_id == 10 && $row->warna_id == 8 ) {
							// echo '<h1>TEST</h1>';
							// echo $sisa_next.' ';
							// echo ($sisa_next/$req[$b_now] * 100).' ';
							// echo $pers.' ';
							// echo $row->warna_id.' '.
							// 	$req[$b_now] .'<'. 
							// 	($terkirim[$row->barang_id][$row->warna_id][$b_now] .'+'. $qData[$i]).'<br/>';
							// echo $i.'--'.count($tanggal_masuk);
						}
						if (count($tanggal_masuk) == 1) {
							$terkirim[$row->barang_id][$row->warna_id][$b_now] = $qData[$i];
						}else if (!isset($terkirim[$row->barang_id][$row->warna_id][$b_next])) {
							$terkirim[$row->barang_id][$row->warna_id][$b_now] += $qData[$i];
						}else{
							$terkirim[$row->barang_id][$row->warna_id][$b_now] += $qData[$i];
						}
					}else{
						if (is_posisi_id() == 1 ) {
							// echo $sisa_next.'<hr/>';
							// echo $i .'=='. count($tanggal_masuk).'<br/>';
						}
						$isNextAvai = false;
						for ($j=0; $j < count($bReq) ; $j++) { 
							if ($bReq[$j] == $b_next) {
								$isNextAvai = true;
							}
						}
						if ($isNextAvai) {
							if (!isset($terkirim[$row->barang_id][$row->warna_id][$b_next])) {
								$terkirim[$row->barang_id][$row->warna_id][$b_next] = 0;
							}
							$terkirim[$row->barang_id][$row->warna_id][$b_next] += $terkirim[$row->barang_id][$row->warna_id][$b_now] + $qData[$i] -  $req[$b_now] ;
							$terkirim[$row->barang_id][$row->warna_id][$b_now] = $req[$b_now];
						}else{
							$terkirim[$row->barang_id][$row->warna_id][$b_now] += $qData[$i];
						}
					}

					
					$isTercapai[$row->barang_id][$row->warna_id][$b_now] = 1;
				}else if($b_now != ''){
					if (is_posisi_id() == 1 && $row->barang_id == 10) {
						echo '3 ',$req[$b_now] .'<'. $terkirim[$row->barang_id][$row->warna_id][$b_now] .'+'. $qData[$i].'<br/>';
					}
					$terkirim[$row->barang_id][$row->warna_id][$b_now] += $qData[$i];

				}
			}
		}
		
		// ini cmn buat ngecek status si request
		foreach ($request_barang_qty_data as $row) {
			if ($row->barang_id == $barang_id) {
				$qty_request_warna[$row->warna_id] += $row->qty;
				$qty_request[$row->warna_id][$row->bulan_request] = $row->qty;
			}
			$status_request[$row->bulan_request][$row->barang_id][$row->warna_id] =$row->tipe;
		}

		foreach ($request_barang_qty_keterangan as $row) {
			$keterangan_qty[$row->barang_id][$row->warna_id][$row->bulan_request] = $row->keterangan;
		}

		foreach ($request_barang_status as $row) {
			$isFinished[$row->barang_id][$row->warna_id][$row->bln_request] = array('id'=>$row->id,'status'=>($row->username != '' ? true : false) );
		}

		$month_color = ['lightSalmon','#FB7AAC'/*pink*/,'orange','lightGreen','lightblue','#40E0D0'/* turqoise*/,'#AFA420' /*kuning pucat*/,'thistle','lightGray','#DDA881','#B6C771'/*ijo pucat*/,'#8A7AC1'];

		?>

		<!-- ===============================table detail request========================== -->
		<div class="modal fade bs-modal-full" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-full">
				<div class="modal-content">
					<div class="modal-body">
						<div class='row'>
							<div class='col-xs-12'>
								<h3>Request Barang <b><?=$nama_beli?></b>
								</h3>
							</div>
							<div class='col-xs-12' id='requestTableContainer'>
							</div>
						</div>

					</div>

					<div class="modal-footer">
						<button type="button" class="btn blue btn-active btn-trigger btn-brg-save" onclick="submitRequest()">Save</button>
						<button type="button" class="btn btn-active default" onclick="savedCheck()" data-dismiss="modal">Close</button>
					</div>
				</div>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
		</div>

		<!-- ===============================overview data request========================== -->
		<div class="modal fade  bs-modal-full" id="portlet-config-polock" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-full">
				<div class="modal-content">
					<div class="modal-body">
						<div class='row'>
							<div class='col-xs-12'>
								<h3>Penyesuaian PO Lock </h3>
								<div class='col-xs-12' id='poLockInfo'></div>
							</div>
							<div class='col-xs-12'>
								<h3>Penyesuaian PO Baru dari tanggal <span id='tanggalPOScreen'><i style='color:#ccc'>loading...</i></span></h3>
								<div id='poBaruUpdate' class='col-xs-12 col-md-6'></div>
							</div>
							<div class='col-xs-12'>
								<h3>Penyesuaian PO Revisi </h3>
								<div id='poRevisiUpdate' class='col-xs-12 col-md-6'></div>
							</div>
						</div>
						<form action="<?=base_url('transaction/penyesuaian_po_barang_request')?>" id='form-po-penyesuaian' <?=(is_posisi_id() != 1 ? 'hidden' : '');?> method="POST">
							<textarea name="po_lock_assign" id='po_lock_assign2' <?=(is_posisi_id() != 1 ? 'hidden' : '' );?> ></textarea>
							<textarea name="po_baru_assign" id='po_baru_assign2' <?=(is_posisi_id() != 1 ? 'hidden' : '' );?> ></textarea>
							<textarea name="po_revisi_assign" id='po_revisi_assign2' <?=(is_posisi_id() != 1 ? 'hidden' : '' );?> ></textarea>
						</form>

					</div>

					<div class="modal-footer">
						<button type="button" class="btn green btn-active btn-trigger btn-refresh-po" onclick="getClosedPoRequest('1')" id="btnRefreshPenyesuaian" >Refresh</button>
						<button type="button" class="btn blue btn-active btn-trigger btn-refresh-po" onclick="submitPenyesuaian()" id="btnPenyesuaian" >Update</button>
						<button type="button" class="btn btn-active default" onclick="savedCheck()" data-dismiss="modal">Close</button>
					</div>
				</div>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
		</div>

		<!-- ===============================modal new request========================== -->
		<div class="modal fade bs-modal-full" id="portlet-config-new" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-full">
				<div class="modal-content">
					<div class="modal-body">
						<form action="<?=base_url('transaction/new_request_insert')?>" class="form-horizontal" id="form_add_data" method="post">
							<h3 class='block'><span class='keterangan-batch' style='color:green'>Batch</span> <span class='keterangan-request'>Request</span>  Baru</h3>
							
                			<input name='type' hidden>
                			<input name='request_barang_id' value="<?=$request_barang_id;?>" hidden>
                			<input name='request_barang_batch_id' value="<?=$request_barang_batch_id;?>" hidden>
                			<input name='barang_id' value="<?=$barang_id;?>" hidden>
							<div class="form-group">
			                    <label class="control-label col-md-3">Tanggal
			                    </label>
			                    <div class="col-md-6">
	                    			<input readonly style='cursor:pointer' name='tanggal' class='form-control date-picker' value="<?=date('d/m/Y')?>" >
			                    </div>
			                </div> 

			                <div class="form-group keterangan-request">
			                    <label class="control-label col-md-3">Supplier
			                    </label>
			                    <div class="col-md-6">
			                    	<select name='supplier_id' class='form-control'>
			                    		<?foreach ($this->supplier_list_aktif as $row) {?>
			                    			<option value="<?=$row->id;?>"><?=$row->nama;?></option>
			                    		<?}?>
			                    	</select>
	                    		</div>
			                </div> 
							<div class="form-group keterangan-request">
			                    <label class="control-label col-md-3">Periode Awal Request
			                    </label>
			                    <div class="col-md-6">
									<?
										// generate today + 3 months
										$this_month = date("Y-m-d");
										$next_months = date('Y-m-d', strtotime(" +3 months"));
										$periode_months = new DatePeriod($tgl1, $interval, $tgl2->setTime(0,0,1));
									?>
			                    	<select name='bulan_awal_request' class='form-control'>
			                    		<?foreach ($periode_months as $date) {?>
											<option value="<?=$date->format('Y-m-01')?>"><?=$date->format('F Y')?></option>
										<?}?>
			                    	</select>
	                    		</div>
			                </div> 

			                <div class="form-group keterangan-batch">
			                    <label class="control-label col-md-3">Batch NO<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
	                    			<input name='no_batch' id='new-batch-no' class='form-control' value="<?=$no_batch +1;?>" readonly>
			                    </div>
			                </div>

			                <div >
			                	<label class="control-label col-md-3">Info
			                    </label>
			                    <div class="col-md-8">
				                	<p  class="keterangan-batch">
				                		- Mohon jangan lupa cek <b style='color:red'>LOCK PO</b><br>
										- <span style='color:red'>No Request tetap sama</span>, namun no batch di set baru <br/>
				                		<!-- - Barang di batch sebelumnya otomatis akan ikut ke batch selanjutnya -->
				                	</p>
				                	<p class="keterangan-request">
				                		- Mohon jangan lupa cek <b style='color:red'>LOCK PO</b><br>
				                		- <span style='color:red'>No Request baru</span>, no batch akan di set ke 1<br/>
				                		- Barang di request selanjutnya TIDAK akan ikut
				                	</p>
			                    </div>
			                </div>

							<div class='col-xs-12 col-md-6'>
								<textarea name="po_baru_assign" id='po_baru_assign' <?=(is_posisi_id() != 1 ? 'hidden' : '' );?> ></textarea>
								<div id='po-baru-assign'>
									<span style='color:#ddd'>loading...</span>
								</div>
							</div>
							<div class='col-xs-12 col-md-6'>
								<textarea name="po_lock_assign" id='po_lock_assign' <?=(is_posisi_id() != 1 ? 'hidden' : '' );?> ></textarea>
								<div id='po-lock-assign'>
									<span style='color:#ddd'>loading...</span>
								</div>
							</div>
							<div class='col-xs-12 col-md-6'>
								<textarea name="po_revisi_assign" id='po_revisi_assign' <?=(is_posisi_id() != 1 ? 'hidden' : '' );?> ></textarea>
								<div id='po-revisi-assign'>
									<span style='color:#ddd'>loading...</span>
								</div>
							</div>

			            </form>

					</div>

					<div class="modal-footer">
						<button type="button" class="btn green btn-active btn-trigger btn-form-save keterangan-batch" id='button-create-new-batch' onclick="submitNewRequest()">Create New Batch</button>
						<button type="button" class="btn blue btn-active btn-trigger btn-form-save keterangan-request" onclick="submitNewRequest()">Create Fresh Request</button>

						<button type="button" class="btn btn-active default" data-dismiss="modal">Close</button>
					</div>
				</div>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
		</div>

		<div class="modal fade bs-modal-lg" id="portlet-config-new2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-body">
						<form action="<?=base_url('transaction/new_request_insert2')?>" class="form-horizontal" id="form_add_data2" method="post">
							<h3 class='block'>Request Baru</h3>
							
                			<input name='type' hidden>
                			<input name='request_barang_id' value="<?=$request_barang_id;?>" hidden>
                			<input name='request_barang_batch_id' value="<?=$request_barang_batch_id;?>" hidden>
                			<input name='barang_id' value="<?=$barang_id;?>" hidden>
							<div class="form-group">
			                    <label class="control-label col-md-3">Tanggal
			                    </label>
			                    <div class="col-md-6">
	                    			<input readonly style='cursor:pointer' name='tanggal' class='form-control date-picker' value="<?=date('d/m/Y')?>" >
			                    </div>
			                </div> 

			                <div class="form-group keterangan-request">
			                    <label class="control-label col-md-3">Supplier
			                    </label>
			                    <div class="col-md-6">
			                    	<select name='supplier_id' class='form-control'>
			                    		<?foreach ($this->supplier_list_aktif as $row) {?>
			                    			<option value="<?=$row->id;?>"><?=$row->nama;?></option>
			                    		<?}?>
			                    	</select>
	                    		</div>
			                </div> 
							<div class="form-group keterangan-request">
			                    <label class="control-label col-md-3">Periode Awal Request
			                    </label>
			                    <div class="col-md-6">
									<?
										// generate today + 3 months
										$this_month = date("Y-m-d");
										$next_months = date('Y-m-d', strtotime(" +5 months"));
										$periode_months = new DatePeriod($tgl1, $interval, $tgl2->setTime(0,0,1));
									?>
			                    	<select name='bulan_awal_request' class='form-control'>
			                    		<?foreach ($periode_months as $date) {?>
											<option value="<?=$date->format('Y-m-01')?>"><?=$date->format('F Y')?></option>
										<?}?>
			                    	</select>
	                    		</div>
			                </div> 


			            </form>

					</div>

					<div class="modal-footer">
						<button type="button" class="btn blue btn-active btn-trigger btn-form-save keterangan-request" onclick="submitNewRequest2()">Create Fresh Request</button>
						<button type="button" class="btn btn-active default" data-dismiss="modal">Close</button>
					</div>
				</div>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
		</div>

		<!-- ===============================modal remove batch========================== -->
		<div class="modal fade" id="portlet-config-pin" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body">
						<form action="<?=base_url('transaction/request_barang_batch_remove');?>" class="form-horizontal" id="form-request-delete" method="post">
							<h3 class='block'> Remove BATCH</h3>

							<div class="form-group">
			                    <label class="control-label col-md-3">BATCH<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<input name='no_batch' type='text' class="form-control" value="<?=$no_request_lengkap;?>" readonly>
			                    </div>
			                </div>
							
							<div class="form-group">
			                    <label class="control-label col-md-3">PIN<span class="required">
			                    * </span>
			                    </label>
			                    <div class="col-md-6">
			                    	<input name='request_barang_batch_id' value='<?=$request_barang_batch_id;?>' hidden>
									<input name='pin' type='password' class="pin_user form-control">
			                    </div>
			                </div>	
		                </form>		                
					</div>

					<div class="modal-footer">
						<button type="button" class="btn red" id="btnPin" onclick="deleteRequest()" disabled>DELETE</button>
						<button type="button" class="btn default" data-dismiss="modal">Close</button>
					</div>
				</div>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
		</div>

		<div class="row margin-top-10">			
			<!-- ===============================overview data request========================== -->
			<div class="col-md-12 hidden-print">
				<div class="portlet light">
					<div class="portlet-title">
						<div class="caption caption-md">
							<i class="icon-bar-chart theme-font hide"></i>
							<span class="caption-subject theme-font bold uppercase">Data Barang</span>
						</div>
					</div>
					<div class="portlet-body">
						<div style='float:right; font-size:3em; font-weight:bold' id='nama-barang'></div>
						<table style='font-size:1.2em'>
							<form method='get'>
								<input hidden name='request_barang_batch_id' value="<?=$request_barang_batch_id;?>">
								<tr>
									<td>
										Tanggal
									</td>
									<td class='padding-rl-25'>
										:
									</td>
									<td>
										<input name='tanggal_start' style="width:150px; text-align:center" class='date-picker-month' value="<?=date('F Y', strtotime(is_date_formatter($tanggal_start)));?>"> 
										to  <input name='tanggal_end'  style="width:150px; text-align:center" class='date-picker-month' value="<?=date('F Y', strtotime(is_date_formatter($tanggal_end)));?>" >
									</td>
								</tr>
								<tr>
									<td>
										Nama Barang
									</td>
									<td class='padding-rl-25'>
										:
									</td>
									<td>
										<select name='id' id="barang-list-select" style='width:325px'>
											<option value="">Pilih</option>
											<?foreach ($this->barang_list_aktif as $row2) {
												if ($row2->id == $barang_id) {
													$nama_barang_terpilih = $row2->nama;
												}?>
												<option value="<?=($row2->id)?>" <?=($barang_id == $row2->id ? 'selected' : '');?> ><?=$row2->nama?></option>
											<?}?>
										</select>
										<span hidden style='font-size:1.3em'><?=$nama_beli;?></span>
									</td>
								</tr>
								<tr>
									<td></td>
									<td></td>
									<td>
										<button class='btn btn-xs default' style="width:325px;" type='submit'>GO</button>
									</td>
								</tr>
							</form>
							<tr hidden>
								<td style='vertical-align:top' >Warna Terdaftar</td>
								<td class='padding-rl-25' style='vertical-align:top'>
									:
								</td>
								<td>
									<div style='column-count:4'>
										<?foreach ($data_warna as $row) { 
											$qty_warna[$row->warna_id] = 0;?>
											- <?=$row->warna_jual;?> <br/>
										<?}?>
									</div>
								</td>
							</tr>
						</table>
					</div>
					
				</div>
			</div>

			<!-- ===============================health planner========================= -->
			<div class="col-md-12">
				<div class="portlet light hidden-print" <?=($barang_id == 0 ? 'hidden' : '');?> >
					<div class="portlet-title">
						<div class="caption caption-md">
							<i class="icon-bar-chart theme-font hide"></i>
							<span class="caption-subject theme-font bold uppercase">Health Planner</span>
						</div>
						<?//if (is_posisi_id() == 1 ) {?>
							<div class="actions hidden-print">
								<?if ($request_barang_id != '' && $locked_by == '') {?>
									<button onclick="modeToggle()" style='display:none' class="btn btn-default btn-sm">
									Change Mode to <span class='planner' hidden>Planner</span> <span class='request'>Request</span> </button>

									<?if($barang_id != ''){?>
										<a href='#portlet-config' data-toggle="modal" onclick="populateBarang()" class="btn btn-default btn-sm"> Daftar PO </a>
									<?}?>
								<?}else if(is_posisi_id() == 1){?>
									<?if($barang_id != ''){?>
										<a href='#portlet-config' data-toggle="modal" onclick="populateBarang()" class="btn btn-default btn-sm"> Daftar PO* </a>
									<?}?>
								<?}?>
								<?if ($isLatest) {?>
									<a href='#portlet-config-polock' data-toggle="modal" class="btn btn-default btn-sm btn-po-lock-btn" onclick="getPOBaru()"> Penyesuaian PO </a>
								<?}?>
							</div>
						<?//}?>
					</div>
					<div class="portlet-body">
						note : <b style='color:red'>*</b> berarti ada penjualan
						<table style='margin-bottom:5px;'>
							<tr>
								<td style="background:yellow">Highlight Kuning</td>
								<td>Barang Request Pada Batch Ini</td>
								<td width='100px'></td>
								<td style="background:#ff928a">Bar Merah</td>
								<td>Tidak ada Stok & Outstanding</td>
							</tr>
							<tr>
								<td style="background:khaki">Highlight Kuning Muda</td>
								<td>Barang Request Pada Batch Lain</td>
								<td width='100px'></td>
								<td style="background:#c6ffb8">Bar Hijau</td>
								<td>STOK</td>
							</tr>
							<tr>
								<td></td>
								<td></td>
								<td width='100px'></td>
								<td style="background:#99ddff">Bar Biru</td>
								<td>REQUEST</td>
							</tr>
						</table>
						<table class='table-qty table-qty-border' id='general-table' width='100%'>
							<?$total_stok = 0 ; $total_outstanding = 0;
							$total_stok_update = 0;  $total_outstanding_update = 0;
							$total_kurang_stok = 0; $total_kurang_po = 0;
							$total_kurang_stok_update = 0; $total_kurang_po_update = 0;
							foreach ($data_warna as $row) {

								foreach ($periode as $date) {
									$i = $date->format("Y-m");
									$key = $i;
									$tahun_now = ($date->format('Y')+1).'-'.$date->format('m');
									$qty_jual_now = (isset($qty_jual[$row->warna_id][$tahun_now]) ? $qty_jual[$row->warna_id][$tahun_now]  : 0 ); 

									$qty_show = 0;
									if (isset($created_history_data[$key][$row->warna_id]) && $created_history_data[$key][$row->warna_id] != '-') {

										$qty_show=(isset($qty_data[$key][$row->warna_id]) ? $qty_data[$key][$row->warna_id] : 0);
									}
									// $forecast_now = (isset($qty_data[$key][$row->warna_id]) ? $qty_data[$key][$row->warna_id] - $qty_jual_now : 0);
									$forecast_now = $qty_show - $qty_jual_now;
										
									$total_trx_warna_all[$row->warna_id] += (isset($qty_data[$key][$row->warna_id]) ? ($forecast_now <0 ? 0 : $forecast_now) : 0);
								
								}

								$total_stok += $qty_stok[$row->warna_id];
								$total_outstanding += $qty_outstanding[$row->warna_id];

								$total_stok_update += $qty_stok_update[$row->warna_id];
								$total_outstanding_update += $qty_outstanding_update[$row->warna_id];

								$kekurangan_stok_update = $total_trx_warna_all[$row->warna_id] - $qty_stok_update[$row->warna_id];
								$kekurangan_stok = $total_trx_warna_all[$row->warna_id] - $qty_stok[$row->warna_id];

								$total_kurang_stok += ($kekurangan_stok < 0 ? 0 : $kekurangan_stok);
								$total_kurang_stok_update += ($kekurangan_stok_update < 0 ? 0 : $kekurangan_stok_update);


								$kekurangan_po_update = $total_trx_warna_all[$row->warna_id] - $qty_stok_update[$row->warna_id] - $qty_outstanding_update[$row->warna_id];
								$kekurangan_po = $total_trx_warna_all[$row->warna_id] - $qty_stok[$row->warna_id] - $qty_outstanding[$row->warna_id];

								$total_kurang_po += ($kekurangan_po < 0 ? 0 : $kekurangan_po);
								$total_kurang_po_update += ($kekurangan_po_update < 0 ? 0 : $kekurangan_po_update);
								
							}?>
							<thead  class='thead'>
								<tr>
									<th rowspan='2'>Warna/bulan</th>
									<th class='text-center' rowspan='2'>Stok</th>
									<th class='text-center' rowspan='2'>Outstanding</th>
									<th class='text-center' rowspan='2'>Total</th>

									<th class='text-center' rowspan='2' >Kekurangan<br/>Stok</th>
									<!-- <th>History</th> -->
									<?
									foreach ($periode_now as $date) {
									// for ($i=$bulan_berjalan; $i <= 12 ; $i++) { ?>
										<th  style='min-width:7%; max-width:10%' rowspan='2' class='text-center idx-<?=$i;?>'><?=$date->format("M y");?></th>
									<?}?>
									<th  class='text-center' rowspan='2'>TOTAL<br/> Forecast</th>
								</tr>
								<tr>
									<!-- <th class='text-center'>Stok</th> -->
									<!-- <th class='text-center'>PO</th> -->
								</tr>
							</thead>
							<thead>
								<tr>
									<th>TOTAL</th>
									<th class='text-center' style="font-size:1.1em;" > 
										<span class='info-update'><?=number_format($total_stok_update,'0',',','.')?> </span>
										<span class='info-request' hidden><?=number_format($total_stok,'0',',','.')?></span>
									</th>
									<th  class='text-center' style="font-size:1.1em;" >
										<span class='info-update'><?=number_format($total_outstanding_update,'0',',','.')?></span>
										<span class='info-request' hidden><?=number_format($total_outstanding,'0',',','.')?></span>
									</th>
									<th  class='text-center' style="font-size:1.1em;" >
										<span class='info-update'><?=number_format($total_outstanding_update + $total_stok_update,'0',',','.')?></span>
										<span class='info-request' hidden><?=number_format($total_outstanding + $total_stok,'0',',','.')?></span>
									</th>
									<th class='text-center'' style="font-size:1.1em;">
										<span class='info-update'><?=number_format($total_kurang_stok_update,'0',',','.')?></span>
										<span class='info-request' hidden><?=number_format($total_kurang_stok,'0',',','.')?></span>
									</th>
									<th hidden class='text-center'' style="font-size:1.1em;">
										<span class='info-update'><?=number_format($total_kurang_po_update,'0',',','.')?></span>
										<span class='info-request' hidden><?=number_format($total_kurang_po,'0',',','.')?></span>
									</th>
									<?$grand_total = 0;
									foreach ($periode as $date) {
										$i = $date->format('n'); 
										$grand_total += $qty_total_bulan[$date->format('Y-m')];?>
										<th class="text-center nama-bulan idx-<?=$date->format('Y-m-01');?>" id="totalBulan-<?=$date->format('Y-m')?>" style="font-size:1.1em;"><?=number_format($qty_total_bulan[$date->format('Y-m')],'0',',','.');?></th>
									<?}?>
									<th style="font-size:1.1em; text-align:right" id='grand-total'><?=number_format($grand_total,'0',',','.')?></th>
								</tr>
							</thead>
							<tbody>
								<?$total_stok = 0 ; $total_outstanding = 0; 
								foreach ($data_warna as $row) {

									$kekurangan_stok_update = $total_trx_warna_all[$row->warna_id] - $qty_stok_update[$row->warna_id];
									$kekurangan_stok = $total_trx_warna_all[$row->warna_id] - $qty_stok[$row->warna_id];

									$kekurangan_stok = ($kekurangan_stok < 0 ? 0 : $kekurangan_stok);
									$kekurangan_stok_update = ($kekurangan_stok_update < 0 ? 0 : $kekurangan_stok_update);

									$kekurangan_po_update = $total_trx_warna_all[$row->warna_id] - $qty_stok_update[$row->warna_id] - $qty_outstanding_update[$row->warna_id];
									$kekurangan_po = $total_trx_warna_all[$row->warna_id] - $qty_stok[$row->warna_id] - $qty_outstanding[$row->warna_id];

									$kekurangan_po = ($kekurangan_po < 0 ? 0 : $kekurangan_po);
									$kekurangan_po_update = ($kekurangan_po_update < 0 ? 0 : $kekurangan_po_update);
									?>
									<tr>
										<td id='idx-<?=$row->warna_id;?>'><?=$row->warna_jual?> <?=(is_posisi_id()==1 ? $row->warna_id : '')?></td>
										<td  class='text-right stok-display'>
											<span class='info-request' hidden>
												<?=number_format($qty_stok[$row->warna_id],'0',',','.');$total_stok += $qty_stok[$row->warna_id];?>
											</span>
											<span class='info-update'>
												<?=number_format($qty_stok_update[$row->warna_id],'0',',','.');?>
											</span>
											<div class='additional'>
												<?=$qty_stok_data[$row->warna_id]; ?>
											</div>
										</td>
										<td class='text-right' style='position:relative'>
											<span class='info-request' hidden>
												<?=number_format($qty_outstanding[$row->warna_id],'0',',','.');$total_outstanding += $qty_outstanding[$row->warna_id]; ?>
											</span> 
											<span class='info-update'>
												<?=number_format($qty_outstanding_update[$row->warna_id],'0',',','.'); ?>
											</span>
										</td>
										<td class='text-center'>
											<span class='info-request' hidden><?=number_format($kekurangan_stok,'0',',','.');?></span> 
											<span class='info-update'><?=number_format($kekurangan_stok_update,'0',',','.');?></span> 
										</td>
										<td class='text-center'>
											<span class='info-request' hidden><?=number_format($kekurangan_po,'0',',','.');?></span> 
											<span class='info-update'><?=number_format($kekurangan_po_update,'0',',','.');?></span> 
										</td>
										<?
											$stok_bar = $qty_stok[$row->warna_id];
											$outstanding_bar = $qty_outstanding[$row->warna_id];

										?>
										<?
										foreach ($periode as $date) {
											$i = $date->format("Y-m");
											$key = $i;
											$tahun_now = ($date->format('Y')+1).'-'.$date->format('m');
											$qty_jual_now = (isset($qty_jual[$row->warna_id][$tahun_now]) ? $qty_jual[$row->warna_id][$tahun_now]  : 0 ); 

											$qty_show = 0;
											if (isset($created_history_data[$key][$row->warna_id]) && $created_history_data[$key][$row->warna_id] != '-') {
												$qty_show=(isset($qty_data[$key][$row->warna_id]) ? $qty_data[$key][$row->warna_id] : 0);
											}
											// $forecast_now = (isset($qty_data[$key][$row->warna_id]) ? $qty_data[$key][$row->warna_id] - $qty_jual_now : 0);
											$forecast_now = $qty_show - $qty_jual_now;
											
											$qty_f = (isset($qty_data[$key][$row->warna_id]) ? $qty_data[$key][$row->warna_id] : 0);
											$performance_value = ($qty_f != 0 ? $qty_jual_now/$qty_f : 0);
											$outstanding_bar_width = 0;
											$stok_bar_width = 0;
											$request_bar_width = 0;
											
											if ($stok_bar > 0) {
												if ($forecast_now > 0) {
													$stok_bar_width = round($stok_bar/$forecast_now,2);
												}else{
													$stok_bar_width = 1;
												}
												$stok_bar_width = ($stok_bar_width > 1 ? 1 : $stok_bar_width);
											}

											if ($stok_bar_width < 1 && $outstanding_bar > 0) {
												if ($forecast_now > 0) {
												 	$outstanding_bar_width = round($outstanding_bar/$forecast_now,2);
												}else{
													$outstanding_bar_width = 1;
												}

											 	$outstanding_bar_width = ($outstanding_bar_width + $stok_bar_width > 1 ? 1-$stok_bar_width : $outstanding_bar_width);
											 	$outstanding_bar = ($outstanding_bar + $stok_bar > $forecast_now ? $outstanding_bar - ($forecast_now - $stok_bar) : $outstanding_bar - $forecast_now);
											}

											if (isset($qty_request_warna[$row->warna_id])) {
												$request_bar_width += ($forecast_now != 0 ? round($qty_request_warna[$row->warna_id] / $forecast_now,2 ) : 1);
												// if ($request_bar_width > 1) {
												// 	$request_bar_width = 1;
												// }else 
												if($request_bar_width < $outstanding_bar_width){
													$outstanding_bar_width -= $request_bar_width;
												}
												
											}

											if ($stok_bar_width > 0) {
												// if ($outstanding_bar_width > 0) {
													$outstanding_bar_width = $outstanding_bar_width + $stok_bar_width + $request_bar_width;
												// }
												if ($request_bar_width > 0) {
													$request_bar_width += $stok_bar_width;
												}
											}

											$empty_bar_width = 0;
											if($qty_stok[$row->warna_id] + $qty_outstanding[$row->warna_id] == 0){
												$empty_bar_width = 1;
											}
											
											$stok_bar = ($forecast_now < 0 ? $stok_bar : $stok_bar - $forecast_now);						
											$stok_bar = ($stok_bar < 0 ? 0 : $stok_bar);
											$outstanding_bar = ($outstanding_bar < 1 ? 0 : $outstanding_bar);

											?> 

											 <!-- ondblclick="selectData('<?=$row->warna_id?>','<?=$key?>', '<?=(isset($qty_data[$key][$row->warna_id]) ? (float)$forecast_now : 0);?>')" -->
											<td class='data-container' id='cell-<?=$key?>-<?=$row->warna_id?>' data-old="">
												<div style="width:100%: height:100%;" onclick="showHistory('<?=$row->warna_id?>','<?=$key?>', '<?=(isset($qty_data[$key][$row->warna_id]) ? (float)$forecast_now : 0);?>')" 
													>
													<div class='performance-bar performance-bar-toggle' style="width:<?=($performance_value  <= 1 ? CEIL($performance_value*100) : 100);?>%"><?=($performance_value > 0  ? ($performance_value <= 1 ? round($performance_value*100,1) : 100) .'%' : '');?></div>
													<div class='health-bar'>
														<?if ($outstanding_bar_width > 0) {?>
															<div class='outstanding-bar' style='width:<?=$outstanding_bar_width*100;?>%' ><?=($outstanding_bar_width-$stok_bar_width)*100;?>%</div>
														<?}?>
														<?if ($request_bar_width > 0) {?>
															<div class='request-bar' style='background:<?=$month_color[(int)date('m', strtotime($i))-1];?>;width:<?=$request_bar_width*100;?>%'><?=$request_bar_width*100;?>%</div>
														<?}?>
														<?if ($stok_bar_width > 0) {?>
															<div class='stok-bar' style='width:<?=$stok_bar_width*100;?>%'><?=($stok_bar_width != 1 ? ($stok_bar_width*100).'%' : '.');?></div>
														<?}?>
														<?if ($empty_bar_width > 0) {?>
															<div class='empty-bar' style='width:<?=$empty_bar_width*100;?>%'><?=$empty_bar_width*100;?>%</div>
														<?}?>
													</div>
													<span style='color:red'><?=($qty_jual_now != 0 ? '*' : '');?></span>
													<span><?=(isset($qty_data[$key][$row->warna_id]) ? number_format((float)$forecast_now ,'0',',','.') : 0)?></span>
												</div>
												<div class='additional' id="additional-<?=$key?>-<?=$row->warna_id?>">
													Forecast : <?=number_format($qty_f,'0',',','.'); ?><br/>
													Jual : <?=number_format($qty_jual_now,'0',',','.'); ?>
													<div>
														<span onclick="closeAdditional('additional-<?=$key?>-<?=$row->warna_id?>')" style="cursor:pointer;color:red; position:absolute; top:10px; right:10px"> <i class='fa fa-times'></i> </span>
													</div>
												</div>
											</td>
										<?}?>
										<th style="font-size:1.1em; text-align:right"><?=number_format($total_trx_warna_all[$row->warna_id],'0',',','.');?></th>
									</tr>
								<?}?>
							</tbody>
							<tfooter>
								<tr>
									<th>TOTAL</th>
									<th class='text-center' style="font-size:1.1em;" > 
										<span class='info-update'><?=number_format($total_stok_update,'0',',','.')?> </span>
										<span class='info-request' hidden><?=number_format($total_stok,'0',',','.')?></span>
									</th>
									<th  class='text-center' style="font-size:1.1em;" >
										<span class='info-update'><?=number_format($total_outstanding_update,'0',',','.')?></span>
										<span class='info-request' hidden><?=number_format($total_outstanding,'0',',','.')?></span>
									</th>
									<th  class='text-center' style="font-size:1.1em;" >
										<span class='info-update'><?=number_format($total_outstanding_update + $total_stok_update,'0',',','.')?></span>
										<span class='info-request' hidden><?=number_format($total_outstanding + $total_stok,'0',',','.')?></span>
									</th>
									<th class='text-center' style="font-size:1.1em;">
										<span class='info-update'><?=number_format($total_kurang_stok_update,'0',',','.')?></span>
										<span class='info-request' hidden><?=number_format($total_kurang_stok,'0',',','.')?></span>
									</th>
									<th hidden class='text-center' style="font-size:1.1em;">
										<span class='info-update'><?=number_format($total_kurang_po_update,'0',',','.')?></span>
										<span class='info-request' hidden><?=number_format($total_kurang_po,'0',',','.')?></span>
									</th>
									<?$grand_total = 0;
									foreach ($periode as $date) {
										$i=$date->format('Y-m');
									// for ($i=$bulan_berjalan; $i <= 12 ; $i++) {
										$grand_total += $qty_total_bulan[$i];?>
										<th class='text-center idx-<?=$i;?>' style="font-size:1.1em;"><?=number_format($qty_total_bulan[$i],'0',',','.');?></th>
									<?}?>
									<th style="font-size:1.1em;"><?=number_format($grand_total,'0',',','.')?></th>
								</tr>
							</tfooter>
						</table>
					</div>
					
				</div>
				
				<div class="portlet light">
					<div class="portlet-title hidden-print">
						<div class="caption caption-md">
							<i class="icon-bar-chart theme-font hide"></i>
							<span class="caption-subject theme-font bold uppercase">Request Barang</span>
						</div>
						<div class="actions hidden-print">
							<?if ($request_barang_id != '' && $locked_by != '' ) {?>
								<button class="btn green btn-sm" onclick="modalNewRequest('2')">
								<i class="fa fa-plus"></i> New Batch </button>
							<?}?>

							<?if ($request_barang_id == '' || $locked_by != '' ) {?>
								<!-- <button class="btn btn-default btn-sm" onclick="modalNewRequest('1')">
								<i class="fa fa-plus"></i> New Request </button> -->

								
								<button class="btn btn-default btn-sm" href="#portlet-config-new2" data-toggle="modal">
								<i class="fa fa-plus"></i> New Request </button>
								
							<?}?>

							<?if ($request_barang_id != '' && $locked_by == '') {?>
								<?if($barang_id != ''){?>
									<a href='#portlet-config' data-toggle="modal" onclick="populateBarang()" class="btn btn-default btn-sm"> Daftar PO </a>
								<?}?>
							<?}else if(is_posisi_id() == 1){?>
								<?if($barang_id != ''){?>
									<a href='#portlet-config' data-toggle="modal" onclick="populateBarang()" class="btn btn-default btn-sm"> Daftar PO* </a>
								<?}?>
							<?}?>

							<?if ($isLatest) {?>
								<a href='#portlet-config-polock' data-toggle="modal" class="btn btn-default btn-sm btn-po-lock-btn" onclick="getPOBaru()"> Penyesuaian PO </a>
							<?}?>

						</div>
					</div>
					<div class="portlet-body">

							<table style='font-size:1.5em; margin-bottom:15px' width='100%'>
								<tr>
									<td>
										<table>
											<tr>
												<td>REQUEST NO</td>
												<td style='padding: 0px 10px'> : </td>
												<td><span id='requestNoAktif'><?=$no_request_lengkap?></span> </td>
											</tr>
											<tr>
												<td>TANGGAL</td>
												<td style='padding: 0px 10px'> : </td>
												<td><input <?=($locked_by == '' && $request_barang_id != '' ? '' : 'readonly');?> class="<?=($locked_by == '' && $request_barang_id != '' ? 'date-picker': '');?>" id="tanggalRequestAktif" value="<?=($tanggal != '' ? is_reverse_date($tanggal) : '')?>" onchange="gantiTanggal()" ></td>
											</tr>
											<tr>
												<form action="" id="form-search-barang">
													<td>
														<input type="text" hidden name='request_barang_batch_id' value="<?=$request_barang_batch_id;?>">
														BARANG FILTER
													</td>
													<td style='padding: 0px 10px'> : </td>
													<td>
														<select name="id" class='form-control' style="width:250px; display:inline-block" id="select-barang" >
															<option value="">SEMUA</option>
															<?foreach ($request_barang_detail as $row) {
																$barang_id_get = explode("??", $row->barang_id);
																$nama_barang = explode("??", $row->nama_barang);
																for ($i=0; $i < count($barang_id_get)  ; $i++) {?> 
																	<option  <?=($barang_id == $barang_id_get[$i] ? 'selected' : '')?> value="<?=$barang_id_get[$i]?>"><?=$nama_barang[$i];?></option>
																<?}
															}?>
														</select>
													</td>
													<td>
														<button class='btn default' style="display:inline-block" ><i class='fa fa-search'></i></button>
														<?if ($barang_id != '') {?>
															<a class='btn default' onclick="toggleBarangSelected()">SHOW ALL</a>
														<?}?>
													</td>
											</form>
											</tr>
										</table>
											
									</td>
									<td>
										 
									</td>
									<td style='vertical-align:top'>
										Kepada : <?=$nama_supplier;?><br>
										Attn : <input type="text" id="attn" value="<?=$attn?>" onchange="updateAttn()" maxlength="50"> 
									</td>
								</tr>
							</table>
							
							<div style='margin-bottom:15px'>
								Keterangan : <a onclick="toggleKeterangan()" class='btn btn-xs default'>show <i class='fa fa-caret-down'></i> </a> <br>
								<div id="keterangan-info" hidden>
									- Status : <br>
									<div style="width:100px; height:20px; font-size:12px; display:inline-block; text-align:center" class='pre-finished'>Kuota Tercapai</div> 
									<div style="width:100px; height:20px; font-size:12px; display:inline-block; text-align:center" class='finished'>Selesai</div> 
									<div style="width:100px; height:20px; font-size:12px; display:inline-block; text-align:center" class='revised'>Revisi</div> 
									<div style="width:100px; height:20px; font-size:12px; display:inline-block; text-align:center" class='urgent'>Urgent</div> 
									<div style="width:100px; height:20px; font-size:12px; display:inline-block; text-align:center" class='revised-urgent'>Revisi + urgent</div>
									<br>
									<div style="font-size:12px; text-align:left" >- Untuk status urgent <b>Double Click</b> di <b>qty</b>/<b>nomor</b> po  </div> 
									<div style="font-size:12px; text-align:left" >- Untuk menyelesaikan request <b>Click</b> di baris untuk memunculkan pop up</div> 
								</div>
							</div>

							<div class='row'>
								<?//if ($barang_id != '') {?>
									<div class="col-xs-12" style="margin:10px 0px">
										<form action="" id="form-search-barang" hidden>
											<input type="text" hidden name='request_barang_batch_id' value="<?=$request_barang_batch_id;?>">
											BARANG :
											<select name="id" class='form-control' style="width:250px; display:inline-block" id="select-barang" >
												<option value="">SEMUA</option>
												<?foreach ($request_barang_detail as $row) {
													$barang_id_get = explode("??", $row->barang_id);
													$nama_barang = explode("??", $row->nama_barang);
													for ($i=0; $i < count($barang_id_get)  ; $i++) {?> 
														<option  <?=($barang_id == $barang_id_get[$i] ? 'selected' : '')?> value="<?=$barang_id_get[$i]?>"><?=$nama_barang[$i];?></option>
													<?}
												}?>
											</select>
											<button class='btn default' style="display:inline-block" ><i class='fa fa-search'></i></button>
											<?if ($barang_id != '') {?>
												<a class='btn default' onclick="toggleBarangSelected()">SHOW ALL</a>
											<?}?>
										</form>
									</div>
								<?//}?>
								<?
								// print_r($terkirim);
								$status_beda = array();
								$total_request = array();
								foreach ($request_barang_qty_all as $row) {
									$status_beda[$row->barang_id][$row->warna_id][$row->bulan_request] = ($no_batch == 1 ? 0 : $row->tipe );
								}
								
								$idx_color = 0;
								foreach ($request_barang_detail as $row) {
									$barang_id_get = explode("??", $row->barang_id);
									$nama_barang = explode("??", $row->nama_barang);
									$request_barang_detail_id = explode("??", $row->request_barang_detail_id);
									$po_pembelian_batch_id = explode("??", $row->po_pembelian_batch_id);
									$po_number = explode("??", $row->po_number);
									$warna_id = explode("??", $row->warna_id);
									$nama_warna = explode("??", $row->nama_warna);
									$qty = explode("??", $row->qty);
									$status_urgent = explode("??", $row->status_urgent);
									
									$bulan_request = $row->bulan_request;
									$baris_count = 0;
									$g_total[$row->bulan_request] = 0;?>

									<div class="col-xs-12 col-md-6" style="margin-bottom:20px">
										<table  class='table-request-print'>
											<thead>
												<tr style="border-top:2px solid #aaa">
													<th colspan='7' class='text-center nama-bulan' style='background:<?=$month_color[(int)date('m', strtotime($bulan_request))-1];?>'><span style='font-size:1.2em'><?=date('F Y', strtotime("+1 year", strtotime($bulan_request)));?> </span> </th>
												</tr>
												<tr>
													<th rowspan='2'>BARANG</th>
													<th rowspan='2' style='max-width:90px'>TOTAL ORDER</th>
													<th rowspan='2'>WARNA</th>
													<th rowspan='2'>QTY</th>
													<th colspan='2' style="border-bottom:1px solid #999">KETERANGAN</th>
													<th rowspan='2'>TERKIRIM</th>
													<!-- <th>Sisa</th> -->
												</tr>
												<tr>
													<th style='min-width:120px;'>PO</th>
													<th>QTY</th>
												</tr>
											</thead>
											<tbody>
											<?$idx_color++;?>
											<?foreach ($barang_id_get as $key => $value) {
												if (!isset($total_request[$value][$bulan_request])) {
													$total_request[$value][$bulan_request] = 0;
													if (is_posisi_id() == 1 ) {
														if ( $value == 7 || $value == 10) {
															// echo $nama_barang[$key].' ';
															// print_r($nama_warna[$key]);
															// echo '<hr/>';
															# code...
														}
													}
												}
													$request_barang_detail_id_b = explode(",", $request_barang_detail_id[$key]);
													$po_pembelian_batch_id_b = explode(",", $po_pembelian_batch_id[$key]);
													$po_number_b = explode(",", $po_number[$key]);
													$nama_warna_b = explode(",", $nama_warna[$key]);
													$warna_id_b = explode(",", $warna_id[$key]);
													//====================================================
													$qty_b = explode(",", $qty[$key]);
													$status_urgent_b = explode(",", $status_urgent[$key]);
													$warna_id_group = array_unique($warna_id_b);
													$warna_id_count = array_count_values($warna_id_b);
													//====================================================
													$idx = 0;
												?>
												<?if ($barang_id == $value) {
													foreach ($po_pembelian_batch_id_b as $key2 => $value2) {
														$bln_r = date('Y-m', strtotime($bulan_request));
														$warna_id_selected[$warna_id_b[$key2]][$bln_r][$value2] = $qty_b[$key2];
													}
												}?>
												<?
													$widList = array();
													$subtotal = array();
													$po_by_warna = array();
													foreach ($warna_id_b as $key2 => $value2) {
														if (!isset($widList[$value2])) {
															$widList[$value2] = array();
															$subtotal[$value2] = 0;
															$po_by_warna[$value2] = array();
														}
														if (is_posisi_id() == 1) {
															// echo $nama_barang[$key].' '.$nama_warna_b[$key2].'<hr/>';
															// echo $total_request[$value]; 
														}
														$total_request[$value][$bulan_request] += $qty_b[$key2];

														// echo '<hr/>';
														// echo $nama_barang[$key];
														// echo $nama_warna_b[$key2];

														$subtotal[$value2] += $qty_b[$key2];
														array_push($widList[$value2],array(
															'nama' => $nama_warna_b[$key2],
															'qty' => $qty_b[$key2],
															'po_number' => $po_number_b[$key2],
															'po_pembelian_batch_id' => $po_pembelian_batch_id_b[$key2],
															'request_barang_detail_id' => $request_barang_detail_id_b[$key2],
															'status_urgent' => $status_urgent_b[$key2],
															'username' => ''
														));

														array_push($po_by_warna[$value2], $request_barang_detail_id_b[$key2]);

													}

													$g_total[$bulan_request] += $total_request[$value][$bulan_request];

												?>
													<?foreach ($warna_id_count as $key2 => $value2) {
														$idx2 = 0;
														$brs_warna = count($widList[$key2]) - 1;
														
														$tp = (isset($status_beda[$value][$key2][$bulan_request]) ? $status_beda[$value][$key2][$bulan_request] : 0  );
														foreach ($widList[$key2] as $key3 => $value3) {
															//$key2 == warna_id
															//$key3 = index 0...n
															$urg = ($value3['status_urgent'] == 1 ? 'color:red' : '' );
															// $brdr = ($idx2 == $brs_warna ? 'border-bottom:1px solid #999' : '');
															$brdr = "";
															$brdr_warna = "border-top:1px solid brown";
															$brdr_first = 'border-top:2px solid #088';
															// $brdr_first = '';
															$bln_up = date('Y-m-01', strtotime("+1 year", strtotime($bulan_request)));
															$classStatus = '';
															$classStatusInit = 'status-cell';
															$classFinished = '';
															if (!isset($isFinished[$value][$key2][$bln_up])) {
																$isFinished[$value][$key2][$bln_up] = array('id'=>'0','status'=>false );
															}
															$request_barang_qty_id = $isFinished[$value][$key2][$bln_up]['id'];
															$popover_content = "<button class='popover-finish btn btn-xs green' onclick='markFinished(`".$request_barang_qty_id."`,`".$value3['request_barang_detail_id']."`)'> Selesai </button>";
															if ($isFinished[$value][$key2][$bln_up]['status']) {
																$classStatus = 'finished ';
																$popover_content = "<button class='popover-finish btn btn-xs yellow-gold' onclick='markUnfinished(`".$request_barang_qty_id."`,`".$value3['request_barang_detail_id']."`)'> Batal Selesai </button>";
															}

															$popover_content .= "<br/><a style='cursor:pointer; font-size:10px;' onclick=&quot;showKeterangan('$value','$key2','$bulan_request')&quot;>show keterangan</a>";
															// print_r($popover_content);
															//clas status untuk memberi warna background pada baris
															if ($tp == 1) {
																$classStatus .= 'revised';
																if ($urg != '') {
																	$classStatus .='-urgent';
																}
															}elseif($urg != ''){
																$classStatus .= 'urgent';
															}elseif(!$isFinished[$value][$key2][$bln_up]['status']){
																$classStatus .= (isset($isTercapai[$value][$key2][$bln_up]) ? 'pre-finished' : '' );
															}
															// $classUrgent = ($classFinished != 'finished' ? ($urg != '' ? 'urgent' : '') : '');
															?>
															<tr style="<?=($idx == 0 ? $brdr_first : '')?>; <?=($idx2 == 0 && $idx != 0 ? $brdr_warna : '')?>; <?=($barang_id != '' && $barang_id != $value ? 'display:none' : '')?>" class="<?=($barang_id != '' && $barang_id != $value ? 'row-hidden' : '')?>" >
																<?if ($idx == 0) {?>
																	<!-- kolom nama barang -->
																	<td rowspan="<?=count($request_barang_detail_id_b)+ count($warna_id_count);?>"  
																		style="font-size:1em; vertical-align:middle; " 
																		class="text-center">
																		<?if ($value == $barang_id) {?>
																			<span style='background:yellow'><?=$nama_barang[$key]?></span>  <!-- <span class='badge badge-roundless badge-success'>active</span> -->
																		<?}else{
																			if (strpos($nama_barang[$key],'/')) {
																				$z = explode('/', $nama_barang[$key]);
																				$nb = $z[0].' / <br/>'.$z[1];
																			}else{
																				$nb = $nama_barang[$key];
																			}?>
																			<a class='btn btn-md default' href="<?=base_url().is_setting_link('transaction/request_barang');?>?id=<?=$value;?>&request_barang_batch_id=<?=$request_barang_batch_id;?>">
																				<?=$nb;?>
																			</a>
																		<?}?>
																	</td>
																	<!-- kolom qty request total per barang -->
																	<td rowspan="<?=count($request_barang_detail_id_b)+ count($warna_id_count)?>"  style="font-size:1.1em; vertical-align:middle; " class="text-center">
																		<?=number_format($total_request[$value][$bulan_request],'0',',','.');?>
																	</td>
																<?}?>
																<?if ($idx2 == 0) {
																	$x = implode(',', $po_by_warna[$key2]);?>
																	<!-- kolom nama warna -->
																	<td class='<?=$classStatus;?> <?=$classStatusInit;?> text-left <?=$classFinished?>' 
																		data-info="req-<?=$request_barang_qty_id;?>" 
																		rowspan="<?=($value2+1)?>" 
																		ondblclick="urgentByWarna()"
																		style="position:relative;font-size:1em; vertical-align:middle" >
																			<div class="po-warna-batch" 
																			data-toggle="popover" 
																			data-placement='top' 
																			data-trigger='click' 
																			data-reqqtyid="<?=$request_barang_qty_id?>" 
																			data-detailId="<?=$value3['request_barang_detail_id'];?>" data-container="body" 
																			title="<?=$nama_barang[$key].' <b>'.$value3['nama']?></b>" 
																			data-html='true' 
																			data-content="<?=$popover_content;?>" 
																			style="z-indez:-1;position:absolute; padding:3px 5px;; top:0px; left:0px; height:100%;width:100%;" >
																			
																			</div>
																		
																		<?=$value3['nama'];?> <?=(is_posisi_id() == 1 ? $key2 : "")?>															</td>
																	<!-- kolom qty request total per warna -->
																	<td class='<?=$classStatus;?> <?=$classStatusInit;?>' data-info="req-<?=$request_barang_qty_id;?>" rowspan="<?=$value2?>" ondblclick="urgentByWarna()" style="position:relative;font-size:1em; vertical-align:middle" class="text-right <?=$classFinished?>" >
																		<div class="po-warna-batch" data-toggle="popover" data-placement='top' data-trigger='click' data-reqqtyid="<?=$request_barang_qty_id?>" data-detailId="<?=$value3['request_barang_detail_id'];?>" data-container="body" title="<?=$nama_barang[$key].' <b>'.$value3['nama']?></b>" data-html='true' data-content="<?=$popover_content;?>" style="z-indez:-1;position:absolute; padding:3px 5px;; top:0px; left:0px; height:100%;width:100%;" >	
																		</div>
																		<?=number_format($subtotal[$key2],'0',',','.')?>
																	</td>
																<?}?>
																<!-- kolom no po -->
																<td 
																	class='<?=$classStatus;?> <?=$classStatusInit;?>'
																	data-info="req-<?=$request_barang_qty_id;?>" 
																	style="position:relative; cursor:pointer;<?=$brdr;?>" 
																	data-request="req-<?=$value3['request_barang_detail_id'];?>" 
																	data-status="<?=$value3['status_urgent'];?>" 
																	ondblclick="urgentByPO(`<?=$value3['request_barang_detail_id']?>`,`<?=$tp;?>`)">
																	<?	//$popover_content = "<button class='popover-urgent btn btn-xs red urgBtn-".$value3['request_barang_detail_id']."' onclick='urgentByPO(`".$value3['request_barang_detail_id']."`,`".$tp."`)'> Urgent</button>";?>
																	<div class="po-warna-batch" data-toggle="popover" data-placement='top' data-trigger='click' data-reqqtyid="<?=$request_barang_qty_id?>" data-detailId="<?=$value3['request_barang_detail_id'];?>" data-container="body" title="<?=$nama_barang[$key].' <b>'.$value3['nama']?></b>" data-html='true' data-content="<?=$popover_content;?>" style="position:absolute; padding:3px 5px;; top:0px; left:0px; height:100%;width:100%;" >
																	</div>
																	<?if ($value3['po_number'] != 'PO BARU') {
																			echo $value3['po_number'];
																		}else{
																			$brn = date('F Y', strtotime($bln_up));
																			array_push($po_baru_list,array(
																				'barang_id'=> $value,
																				'warna_id' => $key2,
																				'bulan_request' => $bulan_request,
																				'bulan_request_n' => $brn,
																				'qty' => $value3['qty'],
																				'nama_barang' => $nama_barang[$key],
																				'nama_warna' => $value3['nama'],
																				'request_barang_detail_id' => $value3['request_barang_detail_id'],
																				'warna_bg' => $month_color[$idx_color]
																			));
																			?>
																			<b>PO BARU</b>
																		<?}?>
																</td>
																<?$bg_tipe = (isset($status_request[$bulan_request][$value][$key2]) ? 1 : 0);?>
																<!-- qty request per po -->
																<td  class='<?=$classStatus;?> <?=$classStatusInit;?>' data-info="req-<?=$request_barang_qty_id;?>" style="position:relative;cursor:pointer;text-align:right;<?=$brdr;?>" data-request="req-<?=$value3['request_barang_detail_id'];?>" data-status="<?=$value3['status_urgent'];?>" ondblclick="urgentByPO(`<?=$value3['request_barang_detail_id']?>`,`<?=$tp;?>`)">
																	<div class="po-warna-batch" data-toggle="popover" data-placement='top' data-trigger='click' data-reqqtyid="<?=$request_barang_qty_id?>" data-detailId="<?=$value3['request_barang_detail_id'];?>" data-container="body" title="<?=$nama_barang[$key].' <b>'.$value3['nama']?></b>" data-html='true' data-content="<?=$popover_content;?>" style="position:absolute; padding:3px 5px;; top:0px; left:0px; height:100%;width:100%;" >
																	</div>
																	
																	<?=number_format($value3['qty'],'0',',','.');?>
																</td>
																<?if($idx2==0){?>
																<!-- qty terkirim -->
																	<td  class='<?=$classStatus;?> <?=$classStatusInit;?>' ondblclick="urgentByWarna()" data-info="req-<?=$request_barang_qty_id;?>" rowspan="<?=$value2?>" style="position:relative;cursor:pointer;;<?=$brdr;?>;vertical-align:middle; text-align:center; font-size:1em" data-request="req-<?=$value3['request_barang_detail_id'];?>" data-status="<?=$value3['status_urgent'];?>" >
																		<div class="po-warna-batch" data-toggle="popover" data-placement='top' data-trigger='click' data-reqqtyid="<?=$request_barang_qty_id?>" data-detailId="<?=$value3['request_barang_detail_id'];?>" data-container="body" title="<?=$nama_barang[$key].' <b>'.$value3['nama']?></b>" data-html='true' data-content="<?=$popover_content;?>" style="z-indez:-1;position:absolute; padding:3px 5px;; top:0px; left:0px; height:100%;width:100%;" >
																			
																		</div>
																			<?
																			if (isset($terkirim[$value][$key2][$bln_up]) && $terkirim[$value][$key2][$bln_up] > 0 ) {?>
																				<?if($subtotal[$key2] >=$terkirim[$value][$key2][$bln_up] ){
																					// echo '=='.$subtotal[$key2] .'>='.$terkirim[$value][$key2][$bln_up].'==';
																					$total_terkirim[$bln_up] += $terkirim[$value][$key2][$bln_up];
																					// echo '--'.$terkirim[$value][$key2][$bln_up].'--';
																				}else{
																					$total_terkirim[$bln_up] += $subtotal[$key2];
																					// echo '--'.$subtotal[$key2].'--';
																				}?>
																				<?=str_replace(",00","", number_format($terkirim[$value][$key2][$bln_up],2,",",".")) ;?>
																			<?}?>
																	</td>
																<?}?>
															</tr>

															<?if (($idx2+1) == $value2 ) {
																$ket_show = '';
																$ket_rd = ($locked_by != "" ? "readonly" : "");
																if (isset($keterangan_qty[$value][$key2][$bulan_request])) {
																	$ket_show = $keterangan_qty[$value][$key2][$bulan_request];
																}?>
																<tr 
																	class="<?=($barang_id != '' && $barang_id != $value ? 'row-hidden' : '')?> " 
																	style="border-top:1px solid #555;
																		<?=($barang_id != '' && $barang_id != $value ? 'display:none' : '')?>"
																	>
																	<td <?=($ket_show == '' ? 'hidden' : '');?> class="ket<?=$value;?>-<?=$key2;?>-<?=$bulan_request;?> <?=$classStatus;?> <?=$classStatusInit;?>" ><small>KETERANGAN</small> </td>
																	<td <?=($ket_show == '' ? 'hidden' : '');?> class="ket<?=$value;?>-<?=$key2;?>-<?=$bulan_request;?>" colspan='4' style='padding:0px; position:relative'>
																		<input id="ket<?=$value;?>-<?=$key2;?>-<?=$bulan_request;?>" 
																			maxlength="200"
																			class="<?=$classStatus;?> <?=$classStatusInit;?>" 
																			onchange="updateKeterangan('<?=$value;?>','<?=$key2;?>','<?=$bulan_request;?>')" 
																			style='font-weight:bold;position:absolute; top:0px; left:0px;height:100%; width:100%; border:none; padding-left:8px' 
																			type="text" 
																			<?=$ket_rd;?>
																			value="<?=$ket_show;?>">
																	</td>
																</tr>
															<?}?>
														<?$idx2++; $idx++; $baris_count++; }?>
													<? }?>
													
													<?}?>
												<?
												if (!isset($total_terkirim[$bln_up])) {
													$total_terkirim[$bln_up] = 0;
												}
												?>
												<tr style='background-color:#aaa'><td colspan='7'><?//=(is_posisi_id()==1 ? $baris_count : '');?></td></tr>
												<tr style='font-size:1.1em'>
													<th colspan='3'>TOTAL ORDER</th>
													<th colspan='4'>
														<?=str_replace(",00","", number_format($g_total[$bulan_request],2,",","."))?>
													</th>
												</tr>
												<tr style='font-size:1.1em'>
													<th colspan='3'>TOTAL TERKIRIM</th>
													<th colspan='4'><?=number_format($total_terkirim[$bln_up],'0',',','.')?></th>
												</tr>
												<tr style='font-size:1.1em'>
													<th colspan='3'>SELISIH</th>
													<th colspan='4'><?=number_format($g_total[$bulan_request] - $total_terkirim[$bln_up],'0',',','.')?></th>
												</tr>
												<tr style='background-color:#aaa'><td colspan='7'><?=(is_posisi_id()==1 ? $baris_count : '');?></td></tr>
											
											</tbody>
										</table>
									</div>
								<?}?>
							</div>

						<div class='text-right hidden-print'>
							<hr/>
							<?if ($isLatest) {?>
								<a href="#portlet-config-pin" data-toggle="modal" class="btn btn-lg red btn-active btn-trigger">REMOVE BATCH</a>
							<?}?>

							<?if ($request_barang_id != '' && $locked_by == '') {?>
								<button type="button" class="btn btn-lg red btn-active btn-trigger btn-lock" onclick="lockRequest('1')">LOCK</button>
							<?}else if($request_barang_id != '' && $locked_by != ''){?>
								<a type="button" class="btn btn-lg yellow-gold btn-print" target="_blank" href="<?=base_url()?>transaction/generate_request_barang_pdf?request_barang_batch_id=<?=$request_barang_batch_id?>">PDF</a>
								<!-- <button type="button" class="btn btn-lg blue btn-print" onclick='window.print()'>PRINT</button> -->
								<button type="button" class="btn btn-lg green btn-active btn-trigger btn-lock" onclick="lockRequest('2')">OPEN</button>
							<?}?>
						</div>
					</div>
					
				</div>
			</div>

		</div>
	</div>			
</div>

<script src="<?php echo base_url('assets/global/plugins/bootstrap-modal/js/bootstrap-modalmanager.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/bootstrap-modal/js/bootstrap-modal.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/bootbox/bootbox.min.js'); ?>" type="text/javascript"></script>




<script>
	
	var request_barang_id = "<?=$request_barang_id?>";
	var request_barang_batch_id = "<?=$request_barang_batch_id?>";
	var barang_id = "<?=$barang_id;?>";
	var nama_barang = "<?=$nama_barang_terpilih?>";
	var po_baru_list = JSON.parse(`<?echo json_encode($po_baru_list);?>`);

	// console.log(po_baru_list);
    let data_trx = [];
    let data_trx_warna =[];
    let dtWarna = [];
    let indexShow = '';
    let getIndex = '';
    let dataShow = '';
    let qtyHistory = [];
    let createdHistory = [];
    var mode = 1;
    var tanggalRequestAktif;
    var onSubmitTime = 2000;
    var isGantiTanggal = true;
    var tanggalOri = "<?=($tanggal != '' ? is_reverse_date($tanggal) : '')?>";
    let barangSelected = {};
	let JmlPOUpdate = 0;
	let JmlPOBaru = 0;
	let JmlPORevisi = 0;
    modeToggle();
    //barangQtySelected = qty barang request / ubahan intinya QTY request barang nya 
	let barangQtySelected = {};
    //barangPoQty = SISA PO original yang available, ga akan berubah kecuali di refresh 
	let barangPoQty = {};
    //barangPoQtyC = container untuk barangPoQty buat dirubah ubah
	let barangPoQtyC = {};
    //barangPoQtyC = container untuk tambahan qty po terakhir
	let barangPoTamb = {};
    //barangQty = list barang qty yang diambil tiap po batch jadi VALUE QTY tiap po batch
	let barangQty = {};
    //barangPoBaru = list barang qty po baru otomatis ke isi klo jatah abis
	let barangPoBaru = {};

    //barangPoSelected = 
    let barangPoSelected = {};

    //barangMax = 
	let barangMax = {};
	let namaWarna = {};

	let barangPoNum = [];
	let barangPoNumStatus = [];
	let barangPoNumLengkap = [];
	let barangRequest = [];
	let qtyRequestList = [];
	var today = "<?=date('d/m/Y', strtotime('-1 day'));?>";
	var isSaved = true;

	var dataSend = [];
	var baris = {};

	<?foreach ($request_barang_detail as $row) {?>
		baris["<?=date('Y-m', strtotime($row->bulan_request)); ?>"] = '';
	<?}?>


    <?foreach ($data_warna as $row) {?>
    	namaWarna[`s-<?=$row->warna_id;?>`] = "<?=$row->warna_jual;?>";
    	barangSelected[`s-<?=$row->warna_id;?>`] = {};
    	barangQtySelected[`s-<?=$row->warna_id;?>`] = {};
    	barangPoSelected[`s-<?=$row->warna_id;?>`] = {};
    	barangMax[`s-<?=$row->warna_id;?>`] = {};
    	barangQty[`s-<?=$row->warna_id;?>`] = {};
    	barangPoQty[`s-<?=$row->warna_id;?>`] = {};
    	barangPoBaru[`s-<?=$row->warna_id;?>`] = {};
    	barangPoTamb[`s-<?=$row->warna_id;?>`] = {};
    	<?foreach ($po_number_list as $key => $value) {
    		foreach ($value as $key2 => $value2) {
    			if(isset($po_qty_list[$row->warna_id][$key][$key2]) ){?>
					// barangPoNum.push('id-<?=$key2;?>');
					barangPoSelected[`s-<?=$row->warna_id;?>`][`id-<?=$key2;?>`] = 0;
					barangMax[`s-<?=$row->warna_id;?>`][`id-<?=$key2;?>`] = <?=$po_qty_list[$row->warna_id][$key][$key2];?>;
					// barangQty[`s-<?=$row->warna_id;?>`][`id-<?=$key2;?>`] = 0;
					barangPoQty[`s-<?=$row->warna_id;?>`][`id-<?=$key2;?>`] = <?=(ceil($po_qty_sisa[$row->warna_id][$key][$key2]/100) * 100 );?>;
					barangPoTamb[`s-<?=$row->warna_id;?>`][`id-<?=$key2;?>`] = <?=(ceil($po_tamb[$row->warna_id][$key][$key2]/100) * 100 );?>;
					// console.log(<?=((float)($po_qty_sisa[$row->warna_id][$key][$key2]/100) * 100 );?>);
				<?}
			}
		}
    }

    foreach ($po_number_list as $key => $value) {
		foreach ($value as $key2 => $value2) {?>
			barangPoNum.push('id-<?=$key2;?>');
			barangPoNumStatus.push(1);
			barangPoNumLengkap.push("<?=$value2;?>");
		<?}
	}
 
	foreach ($request_barang_qty as $row) {
		$bln = date('Y-m',strtotime($row->bulan_request));
		if ($row->request_barang_batch_id == $request_barang_batch_id && $row->barang_id == $barang_id) {?>
			barangQtySelected[`s-<?=$row->warna_id?>`][`<?=$bln;?>`] = <?=$row->qty;?>	
			$(`#cell-<?=$bln?>-<?=$row->warna_id?>`).toggleClass('request-selected');
			barangSelected[`s-<?=$row->warna_id;?>`]['<?=$bln;?>'] = 1;
		<?}elseif($row->barang_id == $barang_id){?>
			// console.log("<?=$bln?>", "<?=$row->warna_id?>");
			$(`#cell-<?=$bln?>-<?=$row->warna_id?>`).toggleClass('request-selected-old');
			$(`#cell-<?=$bln?>-<?=$row->warna_id?>`).attr('data-old',true);
		<?}
	}

	if (isset($warna_id_selected)) {
		foreach ($warna_id_selected as $key => $value) {
			foreach ($value as $bulan => $value2) {?>
				barangQty[`s-<?=$key?>`]['<?=$bulan?>'] = {};
				<?foreach ($value2 as $po_batch_id => $value3) {?>
					barangQty[`s-<?=$key?>`]['<?=$bulan?>'][`id-<?=$po_batch_id;?>`] = <?=(float)$value3;?>;
				<?}?>
			<?}?>
		<?}
	}?>

	jQuery(document).ready(function() {
		// console.log('selected',barangSelected);
		// console.log('Max',barangMax);
		// console.log('Qty',barangQty);
		// console.log('po Num',barangPoNum);
		// console.log('po qty',barangPoQty);

		$(".status-cell").mouseover(function(){
			const dataInfo = $(this).attr('data-info');
			$(".table-request-print").find(`[data-info='${dataInfo}']`).addClass('status-cell-hover')
		}).mouseleave(function(){
			$(".status-cell").removeClass('status-cell-hover')
		})

		$("#nama-barang").text(nama_barang);
		$("#barang-list-select, #select-barang").select2();

		$('.po-warna-batch').on('click', function(e){
			$('.po-warna-batch').not(this).popover('destroy');
			$(this).popover('show');
		});

		$(document).on('click', function(e){
			// console.log(e.target.className.includes('popover'));
			if(e.target.className != 'po-warna-batch' && !e.target.className.includes('popover') ){
				$('.po-warna-batch').popover('destroy');
			};
		});


		let idx = 0;
		let monthKey = [];
		let monthKeyWarna = [];
		let warna_bulan_show = [];
		let lineClassName = [];
		let line_colors = [];
		let warna_count = 0;

		$(document).on('click','.page-content',function(e) {
			// console.log($(e.target).closest('table').attr('id'));
			if ($(e.target).closest('table').attr('id') != "general-table") {
				if (mode == 1) {
					$(".additional").hide();
				};
			};
			// alert(e.target);
		});

		$('body').on('focus','.datepicker_dynamic',function() {
			
			// alert(e.target);
			$(this).datepicker({
				autoclose : true,
				format: "dd/mm/yyyy"
			});
		});

		var map1 = {220: false};
		$(document).keydown(function(e) {
			if (e.keyCode in map1) {
				map1[e.keyCode] = true;
				if (map1[220]) {
					// alert('y');
					populateBarang();
				}
			}
		}).keyup(function(e) {
			if (e.keyCode in map1) {
				map1[e.keyCode] = false;
			}
		});

		$(document).on("click",'.popover-finish', function(){
			$(this).text('loading');
			$(this).attr('disabled',true);
		})

		var map = {13: false};
		$('#requestTableContainer').on("keydown",'.request-qty', function(e) {
			let ini = $(this);
			if (e.keyCode in map) {
				map[e.keyCode] = true;
				if (map[13]) {
					ini.closest('tr').next('tr').find('.request-qty').focus();
				}
			}
		}).keyup(function(e) {
			if (e.keyCode in map) {
				map[e.keyCode] = false;
			}
		})

		<?if ($isLatest) {?>
			getClosedPoRequest(1);
		<?}?>
		
		$('.pin_user').change(function(){
			var form = '#'+$(this).closest('form').attr('id');
			var obj_form = $(form);
			if(cek_pin(obj_form, '.pin_user')){
				$('#btnPin').prop('disabled', false)
				//obj_form.submit();
			}else{
				$('#btnPin').prop('disabled', true)
			}
		});

		$('.pin_user').keypress(function (e) {
			var form = '#'+$(this).closest('form').attr('id');
			var obj_form = $(form);
			if (e.which == 13) {
				if(cek_pin(obj_form, '.pin_user')){
					$('#btnPin').prop('disabled', false)
					//obj_form.submit();
				}else{
					$('#btnPin').prop('disabled', true)
				}
			}
		});

	});

	function toggleKeterangan(){
		$('#keterangan-info').toggle();
	}

	function modeToggle(){
		mode = (mode % 2)+1;
		$(".planner").toggle();
		$(".request").toggle();
		$(".performance-bar").toggleClass("performance-bar-toggle");
		$(".additional").toggleClass("additional-show-history");
		$(".additional").removeAttr("style");
		$(".table-qty").toggleClass('table-qty-border');
		$(".table-qty").toggleClass('table-qty-border-2');
		$('.data-container').toggleClass('data-container-select');

		if (mode==1) {
			$('.info-request').hide();
			$('.info-update').show();
		}else if(mode == 2){
			$('.info-request').show();
			$('.info-update').hide();
		};
	}

	function showHistory(warna_id, periode, qty) {
		if (mode == 1) {
			$('.data-container .additional').css('display','none');
			// console.log("id",`#additional-${periode}-${warna_id}`);
			$(`#additional-${periode}-${warna_id}`).show();
		}else if (mode == 2) {
			// $(`#cell-${periode}-${warna_id}`).toggleClass('request-selected');
			selectData(warna_id, periode, qty,1);
		};
	}

	//=============================================================================================

	function modalNewRequest(tipe){
		console.log(tipe);
		$("#form_add_data [name=type]").val(tipe);
		if (tipe == 1) {
			$(".keterangan-request").show();
			$(".keterangan-batch").hide();
		}else if (tipe == 2) {
			$(".keterangan-batch").show();
			$(".keterangan-request").hide();

			$("#button-create-new-batch").prop('disabled', true);
			if (po_baru_list.length > 0) {
				var data = {};
				data['tanggal'] = "<?=$tanggal?>";
				data['tipe'] = 1;
				data['request_barang_batch_id'] = "<?=$request_barang_batch_id?>";
				data['request_barang_id'] = "<?=$request_barang_id?>";
				data['supplier_id'] = "<?=$supplier_id?>";
				data['po_baru_list'] = po_baru_list;
				var url = "transaction/po_baru_list_manage_info";
				console.log('pol',data);
				var po_baru_assign = {};
				let jatah_po = {};
				let sisa_qty_request = [];
				let sisa_qty = {};
				ajax_data_sync(url,data).done(function(data_respond  ,textStatus, jqXHR ){
					let res = JSON.parse(data_respond);
					$.each(res.data, function(i,v){
						if (typeof jatah_po[`s-${v.barang_id}-${v.warna_id}`] === 'undefined') {
							jatah_po[`s-${v.barang_id}-${v.warna_id}`] = [];
						}

						jatah_po[`s-${v.barang_id}-${v.warna_id}`].push({
							po_pembelian_batch_id : v.po_pembelian_batch_id,
							qty:v.qty,
							po_number:v.po_number
						});
					})

					for (let i = 0; i < po_baru_list.length; i++) {
						if(typeof sisa_qty[po_baru_list[i].bulan_request] == 'undefined' ){
							sisa_qty[po_baru_list[i].bulan_request] = [];
						}
						let new_qty = 0;
						let sisa_request = po_baru_list[i].qty;
						if (typeof jatah_po[`s-${po_baru_list[i].barang_id}-${po_baru_list[i].warna_id}`] !== 'undefined' ) {
							$.each(jatah_po[`s-${po_baru_list[i].barang_id}-${po_baru_list[i].warna_id}`], function(j,v){
								if (sisa_request > 0 && v.qty > 0) {
									if (parseFloat(sisa_request) <= parseFloat(v.qty) ) {
										new_qty = sisa_request;
										v.qty = v.qty - sisa_request;
										sisa_request = 0;
									}else if(sisa_request > 0 && parseFloat(sisa_request) > parseFloat(v.qty) && v.qty > 0){
										sisa_request -= v.qty;
										new_qty = v.qty;
										v.qty = 0;
										sisa_qty[po_baru_list[i].bulan_request][`s-${po_baru_list[i].barang_id}-${po_baru_list[i].warna_id}`] = sisa_request;
										
										console.log(sisa_qty);
									}

									if (typeof po_baru_assign[po_baru_list[i].bulan_request_n] === 'undefined' ) {
										po_baru_assign[po_baru_list[i].bulan_request_n] = [po_baru_list[i].warna_bg];
									}

									po_baru_assign[po_baru_list[i].bulan_request_n].push({
										request_barang_detail_id:po_baru_list[i].request_barang_detail_id,
										bulan_request:po_baru_list[i].bulan_request,
										po_pembelian_batch_id:v.po_pembelian_batch_id,
										barang_id:po_baru_list[i].barang_id,
										warna_id:po_baru_list[i].warna_id,
										qty:new_qty,
										nama_barang:po_baru_list[i].nama_barang,
										nama_warna:po_baru_list[i].nama_warna,
										po_number:v.po_number
									})
								}
								// console.log(j, jatah_po[`s-${po_baru_list[i].barang_id}-${po_baru_list[i].warna_id}`][j]);
							})
						}
					}
					
					for (let i = 0; i < po_baru_list.length; i++) {
						let sisa = sisa_qty[po_baru_list[i].bulan_request][`s-${po_baru_list[i].barang_id}-${po_baru_list[i].warna_id}`];
						console.log(sisa);
						if(sisa && parseFloat(sisa) > 0){
							po_baru_assign[po_baru_list[i].bulan_request_n].push({
								request_barang_detail_id:po_baru_list[i].request_barang_detail_id,
								bulan_request:po_baru_list[i].bulan_request,
								po_pembelian_batch_id: 0,
								barang_id:po_baru_list[i].barang_id,
								warna_id:po_baru_list[i].warna_id,
								qty:parseFloat(sisa),
								nama_barang:po_baru_list[i].nama_barang,
								nama_warna:po_baru_list[i].nama_warna,
								po_number:"<b>PO BARU</b>"
							});

						}
					}

					let po_assign = "";
					$.each(po_baru_assign, function(bulan_request, data){
						for (let i = 0; i < data.length; i++) {
							if (i == 0) {
								po_assign += `<thead>
								<tr style='background-color:${data[i]}'>
									<th colspan='4'>bulan : ${bulan_request}</th>
								</tr>
								<tr>
									<th>Barang</th>
									<th>PO</th>
									<th>PO Tujuan</th>
									<th>QTY</th>
								</tr>
							</thead>`;
							}else{
								po_assign += `<tr>
									<td>${data[i].nama_barang} ${data[i].nama_warna} </td>
									<td><b>PO BARU</b> </td>
									<td> ${data[i].po_number} </td>
									<td>${change_number_format(parseFloat(data[i].qty))}</td>
								</tr>`;
							}
						}
					});

					$('#po-baru-assign').html("");
					if (po_assign.length > 0) {
						$('#po-baru-assign').html(`<hr/><h3>PERUBAHAN PO BARU</h3><table class='table'>${po_assign}</table>`);
						$("#po_baru_assign").val(JSON.stringify(po_baru_assign));
						console.log(po_baru_assign);
					}else{
						$('#po-baru-assign').html(`<hr/><h3>PERUBAHAN PO BARU</h3><p>Tidak ada Penyesuaian</p>`);
					}

				});
				
			}
			
			$("#button-create-new-batch").prop('disabled', false);
			getClosedPoRequest(2);
			
		};

		$("#portlet-config-new").modal("toggle");
	}

	function selectData(warna_id, periode, qty, tipe){
		$(`#cell-${periode}-${warna_id}`).toggleClass('request-selected');
		isDataOld = $(`#cell-${periode}-${warna_id}`).attr('data-old');
		if(isDataOld){
			$(`#cell-${periode}-${warna_id}`).toggleClass('request-selected-old');
		}

		if (typeof barangSelected[`s-${warna_id}`][periode] === 'undefined') {
			barangSelected[`s-${warna_id}`][periode] = 1;
		}else{
			barangSelected[`s-${warna_id}`][periode] = (barangSelected[`s-${warna_id}`][periode] - 1 ) * -1;
		};

		if (barangSelected[`s-${warna_id}`][periode] && qty >= 0) {
			let qtyAdjust = Math.ceil(qty/500) *500;
			barangQtySelected[`s-${warna_id}`][periode] = qtyAdjust;
		}else{
			barangQtySelected[`s-${warna_id}`][periode] = 0;
		};

		if (tipe==2) {
			$(`#tr-${warna_id}-${periode}`).remove();
			populateBarang();
		};
		isSaved = false;
	}

	function closeAdditional(id){
		// console.log("id",id);
		$(`#${id}`).hide();
	}

	function populateBarang(){
		// for (var i = 0; i < barangPoNumStatus.length; i++) {
		// 	barangPoNumStatus[i] = 0;
		// };

		sortBarangSelected();
		var poTambSisa = {};
		$.each(barangPoQty, function(i,v){
			barangPoQtyC[i] = {...v};
			poTambSisa[i] = {...barangPoTamb[i]}
		});
		console.log('pot',poTambSisa);
		var poAktif = [];
		barangRequest = [];
		$.each(barangSelected, function(i,v){
			// console.log(v, v.length);
			$.each(v, function(tgl,status){
				let qtyRequest = barangQtySelected[i][tgl];
				barangRequest.push({
					bulan_request : tgl,
					barang_id : barang_id,
					warna_id : i.substring(2),
					qty : qtyRequest
				});
				var sisa = qtyRequest;
				barangQty[i][tgl] = {} ;
				barangPoBaru[i][tgl] = 0;
				// console.log(barangPoQty[i]);
				$.each(barangPoQtyC[i] , function(i3,v3){
					poAktif[i3] = 1;
					// poAktif.push(i3);
					let reqQty = (sisa > v3 ? v3 : sisa );
					barangQty[i][tgl][i3] = reqQty;

					if (sisa - v3 >= 0) {
						sisa = sisa - v3;
						barangPoQtyC[i][i3] = 0;
						// if(sisa < 800){
						// 	barangQty[i][tgl][i3] = reqQty+sisa;
						// 	sisa = 0;
						// }

						if(sisa > 0 && sisa <= poTambSisa[i][i3] ){
							barangQty[i][tgl][i3] = reqQty+sisa;
							poTambSisa[i][i3] -= sisa; 
							sisa = 0;
						}

					}else{
						barangPoQtyC[i][i3] = v3-sisa;
						if(barangPoQtyC[i][i3] <= 800){
							barangPoQtyC[i][i3] = 0;
						}
						sisa = 0
					};
					// console.log(i, i3, barangQty[tgl][i][i3]);
				});
				if (sisa > 0) {
					barangPoBaru[i][tgl] = sisa;
				};
			});
		});

		for (var i = 0; i < barangPoNum.length; i++) {
			// console.log(barangPoNum[i], poAktif[barangPoNum[i]]);
			if (poAktif[barangPoNum[i]] == 1) {
				barangPoNumStatus[i] = 1;
				// $(`#${barangPoNum[i]}`).show();
			}else{
				barangPoNumStatus[i] = 0;
				// $(`#${barangPoNum[i]}`).hide();
			};
		};

		console.log(barangPoQtyC);
		console.log(barangQty);


		drawRequestTableBody();
	}

	function drawRequestTableBody(){


		console.log(baris);
		// $("#general_table2 tbody").html(baris);\
		$.each(baris, function(tgl,isi){
			baris[tgl] = '';
		});
		dataSend = [];
		var dataPO = {};
		$.each(barangSelected, function(i,v){
			var idx = 0;
			$.each(v, function(tgl, status){
				if (typeof dataPO[tgl] === 'undefined') {
					dataPO[tgl] = [];
				};

				var warna_id_get = i.substring(2);
				// console.log('wig',warna_id_get, i);

				if (status == 1) {
					baris[tgl] += `<tr ${(v == 0 ? 'hidden' : '')} id="tr-${warna_id_get}-${tgl}"><td>${namaWarna[i]}</td>
						<td style='width:100px; padding:0px; vertical-align:middle'><input style='width:100%; height:100%; border:none; ' onfocus="showPoKeterangan('keterangan-${tgl}-${i}')" onchange ="populateQty('${i}', '${tgl}')" id="req-${tgl}-${i}" value="${change_number_format(barangQtySelected[i][tgl])}" class='request-qty text-center amount_number' ></td>`;
					for (var i2 = 0; i2 < barangPoNum.length; i2++) {
						let batch_id = barangPoNum[i2].substring(3);
						// console.log('stat ',barangPoNum[i2], barangPoNumStatus[i2]);
						if (typeof barangQty[i][tgl][barangPoNum[i2]] !== 'undefined' && barangQty[i][tgl][barangPoNum[i2]] !== 'undefined') {
							dataPO[tgl].push({
								bulan_request : tgl,
								po_pembelian_batch_id : batch_id,
								barang_id : barang_id,
								warna_id : warna_id_get,
								qty : barangQty[i][tgl][barangPoNum[i2]],
							});
							baris[tgl] += `<td ${(barangPoNumStatus[i2] == 1 ? '' : 'hidden')} data-warna="${tgl}-${i}" data-batchid="${barangPoNum[i2]}" data-qty="${barangPoQtyC[i][barangPoNum[i2]]}">
								${change_number_format(barangQty[i][tgl][barangPoNum[i2]]) }<br/>
								<small class='keterangan-outstanding keterangan-${tgl}-${i}' hidden>sisa : ${barangPoQtyC[i][barangPoNum[i2]]}</small>
								</td>`;
						}else{
							baris[tgl] += `<td ${(barangPoNumStatus[i2] == 1 ? '' : 'hidden')} style='background:#eee' data-batchid="${barangPoNum[i2]}"> - </td>`;
						};
					};
					baris[tgl] += `<td id="poBaru-${i}">${change_number_format(barangPoBaru[i][tgl])}</td>`;
					baris[tgl] += `<td><button onclick="selectData('${warna_id_get}', '${tgl}', '0','2')" class='btn btn-xs red'><i class='fa fa-times'></i></button></td></tr>`;
					dataPO[tgl].push({
						bulan_request : tgl,
						po_pembelian_batch_id : 0,
						barang_id : barang_id,
						warna_id : warna_id_get,
						qty : barangPoBaru[i][tgl],
					});
					// console.log(tgl, baris[tgl]);
				};			
			});
		});

		// console.log('dataPO',dataPO);

		var tglUse = {};
		// let tgl_now = today;
		// $.each(dataPO, function(tgl, isi){

		// 	let tgl_now = $(`#tgl-${tgl}`).val();
		// 	tglUse[tgl] = (tgl_now != '' && typeof tgl_now !== 'undefined' ? tgl_now : today);
		// 	// console.log(tgl, isi);
		// });

		dataSend.push({
			tanggal:$("#tanggalRequestAktif").val(),
			// bulan:tgl,
			data_barang:dataPO,
			data_request:JSON.stringify(barangRequest)
		});

		console.log('baris',barangSelected);

		$("#requestTableContainer").html('');


		var tblContent = `<table class="table table-striped table-bordered table-hover" id="general_table2">`;
		$.each(baris, function(tgl, isi){
			// console.log(tgl);
			// let tglS = tgl.split('-')
			let tblHeader = '';
			for (var i = 0; i < barangPoNum.length; i++) {
				tblHeader += `<th ${(barangPoNumStatus[i] == 1 ? '' : 'hidden')} id="${barangPoNum[i]}">
						<span style='font-size:0.8em'>
							${barangPoNumLengkap[i]} <br/>
						</span> 
					</th>`;
			};

			tblContent += `<thead>
				<tr>
					<th colspan='${parseInt(barangPoNum.length) + 4 }' style='background:#d4e3ff'>
						Bulan : <span id='nama_bulan'>${namaBulan(tgl)}</span><br/>
					</th>
				</tr>
					<tr>
						<th scope="col">
							Warna
						</th>
						<th scope="col">
							Request
						</th>
						${tblHeader}
						<th id="id-baru">PO BARU</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					${isi}
				</tbody>`;


		});
		tblContent += `</table>`;

		$('#requestTableContainer').append(tblContent);

		// $('#portlet-config').modal('toggle');

		// $("#general_table2 tbody").append(baris);
	};

	function populateQty(warna_id, periode){
		var sisa = reset_number_format($(`#req-${periode}-${warna_id}`).val());
		barangQtySelected[warna_id][periode] = sisa;
		$(`#general_table2_${periode} [data-warna='${periode}-${warna_id}']`).each(function(){
			let poQty = $(this).attr('data-qty');
			let reqQty = (parseInt(sisa) > parseInt(poQty) ? poQty : sisa );
			// console.log(poQty, sisa, reqQty);
			$(this).text(change_number_format(reqQty) );
			if (sisa - poQty > 0) {
				sisa = sisa - poQty;
			}else{sisa = 0};
		});

		$(`#poBaru-${warna_id}`).text(change_number_format(sisa) );
		populateBarang();
		$(`#req-${periode}-${warna_id}`).closest('tr').next('tr').find('.request-qty').focus();
		
		

	}

	function sortBarangSelected(){

		$.each(barangSelected, function(i,v){
			console.log(i, $.isEmptyObject(v));
			if (!$.isEmptyObject(v)) {
				let barangSorted = Object.keys(v).sort();
				// barangSelected[i] = barangSorted;
				let isi = barangSelected[i];
				// console.log(barangSorted);
				barangSelected[i] = {};
				for (var j = 0; j < barangSorted.length; j++) {
					if(parseInt(isi[barangSorted[j]]) == 1){
						console.log(barangSelected[i][barangSorted[j]]);
						console.log(isi[barangSorted[j]]);				
						barangSelected[i][barangSorted[j]] = isi[barangSorted[j]];
					}
				};

				// Object.keys(v).sort().reduce((obj, key) => {
				// 	console.log(obj, key);
				// 	// barangSorted[key] = v[key];
				// });

			};
		})

		// let barangSorted = {};
		// Object.keys(barangSelected).sort().reduce((obj, key) => {
		// 	console.log(obj, key);
		// 	// barangSorted[key] = v[key];
		// });
		// console.log('2',barangSelected);

	};


	function gantiTanggal(){
		let tgl_now = $(`#tanggalRequestAktif`).val();
		if (tgl_now != tanggalOri && tgl_now != '' && request_barang_id != '') {
			var data = {};
			data['tanggal'] = tgl_now;
			data['request_barang_id'] = request_barang_id;
			data['no_batch'] = "<?=$no_batch?>";
			data['no_request'] = "<?=$no_request?>";
			data['request_barang_batch_id'] = "<?=$request_barang_batch_id;?>";
			var url = "transaction/request_barang_update_tanggal";
			if (isGantiTanggal) {

				isGantiTanggal = false;
				ajax_data_sync(url,data).done(function(data_respond  ,textStatus, jqXHR ){
					setTimeout(function(){
						isGantiTanggal = true;
					},onSubmitTime);
					console.log(data_respond);
						// alert(data_respond)
						if (textStatus == 'success') {
							notific8("lime", "Tanggal Update");
							window.location.reload();
						}else{
							alert("Gagal Update, Mohon hubungi admin");
						};
						// window.location.reload();

					// if (data_respond == 'OK') {
						// windows.location.reload();
						
					// };
				});
				
			};		
		}else if(tgl_now == '' && request_barang_id != ''){
			alert("Mohon isi tanggal Request");
		};
	}

	function namaBulan(tanggal){
		let dt = tanggal.split('-');
		let bulan = [
			"Januari", "Februari", "Maret", "April",
			"Mei", "Juni", "Juli", "Agustus",
			"September", "Oktober", "November", "Desember"
			];

		return bulan[parseInt(dt[1] - 1)]+' '+(parseInt(dt[0])+1);
	}

	function showPoKeterangan(keterangan){
		$(document).find(`.keterangan-outstanding`).hide();
		$(document).find(`.${keterangan}`).show();
	}

	function submitNewRequest(){
		let tanggal = $("#form_add_data [name=tanggal]").val();
		if (tanggal != '') {
			btn_disabled_load($('.btn-form-save'));
			$('#form_add_data').submit();
		}else{
			alert('Mohon isi tanggal');
		};
	}

	function submitNewRequest2(){
		let tanggal = $("#form_add_data2 [name=tanggal]").val();
		if (tanggal != '') {
			btn_disabled_load($('.btn-form-save'));
			$('#form_add_data2').submit();
		}else{
			alert('Mohon isi tanggal');
		};
	}

	function submitRequest(){
		btn_disabled_load($(".btn-brg-save"));
		// console.log(dataSend);
		drawRequestTableBody();
		var data = {};
		data['barang_id'] = "<?=$barang_id;?>";
		data['request_barang_batch_id'] = "<?=$request_barang_batch_id;?>";
		data['data'] = dataSend;
		var url = "transaction/request_barang_submit";
		ajax_data_sync(url,data).done(function(data_respond  ,textStatus, jqXHR ){
			console.log(data_respond);
				// alert(data_respond)
				if(data_respond == 'OK'){
					window.location.reload();
				}else{
					alert("Error mohon contact admin");
					<?if (is_posisi_id() == 1) {?>
						alert(data['request_barang_batch_id']);
					<?}?>
				}

			// if (data_respond == 'OK') {
				// windows.location.reload();
				
			// };
		});
	}

	function lockRequest(tipe){
		btn_disabled_load($(".btn-lock"));
		var data = {};
		data['request_barang_batch_id'] = "<?=$request_barang_batch_id;?>";
		data['tipe'] = tipe;
		var url = "transaction/request_barang_lock";
		ajax_data_sync(url,data).done(function(data_respond  ,textStatus, jqXHR ){
			console.log(data_respond);
				// alert(data_respond)
				if (data_respond == "OK") {
					window.location.reload();
				}else{
					alert("error, mohon kontek admin");
				};

			// if (data_respond == 'OK') {
				// windows.location.reload();
				
			// };
		});
	}

	function savedCheck(){
		if (!isSaved) {
			alert("Jangan lupa save setelah merubah data");
		};
	}

	function urgentByWarna(){
		notific8('lemon','Mohon untuk double click di nomor PO atau Qty PO')
	}

	function urgentByPO(request_barang_detail_id, tipe){

		// if(parseInt(tipe)){
		// 	console.log(tipe)
		// 	$(".table-request-print").find(`[data-request='req-${request_barang_detail_id}']`).closest('tr').removeClass('revised').addClass('revised-urgent');
		// }else{
		// 	$(".table-request-print").find(`[data-request='req-${request_barang_detail_id}']`).closest('tr').addClass('urgent');
		// }
		
		let status_urgent = $(`[data-request='req-${request_barang_detail_id}']`).attr('data-status');
		status_urgent = (status_urgent-1)*-1;

		$(".table-request-print").find(`[data-request='req-${request_barang_detail_id}']`).css({'cursor':'no-drop'});
		$(`.urgBtn-${request_barang_detail_id}`).css({'cursor':'no-drop'});

		var data = {};
		data['request_barang_detail_id'] = request_barang_detail_id;
		data['status_urgent'] = status_urgent;
		var url = "transaction/update_status_urget_request_barang";

		ajax_data_sync(url,data).done(function(data_respond  ,textStatus, jqXHR ){
			if (data_respond == 'OK') {
				let ini = $(".table-request-print").find(`[data-request='req-${request_barang_detail_id}']`).closest('tr').find('.status-cell');
				if (status_urgent == 1) {
					if(parseInt(tipe)){
						ini.removeClass('revised').addClass('revised-urgent');
					}else{
						ini.addClass('urgent');
					}
				}else{
					if(parseInt(tipe)){
						ini.removeClass('revised-urgent').addClass('revised');
					}else{
						ini.removeClass('urgent');
					}
				};
				$(".table-request-print").find(`[data-request='req-${request_barang_detail_id}']`).css({'cursor':'pointer'});
				$(`.urgBtn-${request_barang_detail_id}`).css({'cursor':'pointer'});

				$(`[data-request='req-${request_barang_detail_id}']`).attr('data-status',status_urgent);


			}else{
				alert("error, please contact admin");
			};
		});
	}

	function updateAttn(){
		
		var data = {};
		data['request_barang_batch_id'] = "<?=$request_barang_batch_id;?>";
		data['attn'] = $('#attn').val();
		var url = "transaction/update_request_barang_attn";

		ajax_data_sync(url,data).done(function(data_respond  ,textStatus, jqXHR ){
			if (data_respond == 'OK') {
				notific8("lime", "Attn Updated")

			}else{
				alert("error, please contact admin");
			};
		});
	}

	function markFinished(request_barang_qty_id, request_barang_detail_id){
		
		var data = {};
		data['request_barang_qty_id'] = request_barang_qty_id;
		var url = "transaction/update_status_finished_request_barang";

		ajax_data_sync(url,data).done(function(data_respond  ,textStatus, jqXHR ){
			if (data_respond == 'OK') {
				let ini = $(".table-request-print").find(`[data-request='req-${request_barang_detail_id}']`).closest('tr').find('.status-cell');
				// ini.removeClass('revised-urgent');
				// ini.removeClass('pre-finished');
				ini.addClass('finished');
				$(`[data-request='req-${request_barang_detail_id}']`).attr('data-status','finished');

				const popover = $(`[data-info='req-${request_barang_qty_id}']`).find('.po-warna-batch');
				for (let i = 0; i < popover.length; i++) {
					let qtyId = $(popover[i]).attr('data-reqqtyid');
					let detailId = $(popover[i]).attr('data-detailId');
					let newButton = `<button class='popover-finish btn btn-xs yellow-gold' onclick="markUnfinished('${qtyId}','${detailId}')"> Batal Selesai </button>`;
					$(popover[i]).attr('data-content',newButton);
				}
				$('.po-warna-batch').popover('destroy');

			}else{
				alert("error, please contact admin");
			};
		});
	}

	function markUnfinished(request_barang_qty_id, request_barang_detail_id){
		
		var data = {};
		data['request_barang_qty_id'] = request_barang_qty_id;
		data['unfinished'] = 'yes';
		var url = "transaction/update_status_finished_request_barang";

		ajax_data_sync(url,data).done(function(data_respond  ,textStatus, jqXHR ){
			if (data_respond == 'OK') {
				let ini = $(".table-request-print").find(`[data-request='req-${request_barang_detail_id}']`).closest('tr').find('.status-cell');
				ini.removeClass('finished');
				// ini.removeClass('revised-urgent');
				// ini.removeClass('pre-finished');
				$(`[data-request='req-${request_barang_detail_id}']`).attr('data-status','');

				const popover = $(`[data-info='req-${request_barang_qty_id}']`).find('.po-warna-batch');
				for (let i = 0; i < popover.length; i++) {
					let qtyId = $(popover[i]).attr('data-reqqtyid');
					let detailId = $(popover[i]).attr('data-detailId');
					let newButton = `<button class='popover-finish btn btn-xs green' onclick="markFinished('${qtyId}','${detailId}')"> Selesai </button>`;
					$(popover[i]).attr('data-content',newButton);
				}
				$('.po-warna-batch').popover('destroy');

			}else{
				alert("error, please contact admin");
			};
		});
	}

	function getClosedPoRequest(tipe){
		$(".btn-refresh-po").prop('disabled',true).text('...update');
		var data = {};
		data['request_barang_id'] = "<?=$request_barang_id?>";
		data['tanggal_awal'] = "<?=$tanggal_awal;?>";
		var url = "transaction/assign_po_locked_to_new_po";
		ajax_data_sync(url,data).done(function(data_respond  ,textStatus, jqXHR ){
			let res = JSON.parse(data_respond);
			// console.log(res.text);
			console.log('rd',res.data);
			JmlPOUpdate = res.data.length;
			let po_lock_assign = [];
			let text = '';
			for (let i = 0; i < res.data.length; i++) {
				let dt = res.data[i];
				let nd = res.data[i].data;
				let idx = [];
				let t_idx = 0;
				let tcv = [];

				console.log('dt',dt);
				po_lock_assign.push({
					id:dt.id_detail,
					po_pembelian_batch_id:dt.po_pembelian_batch_id,
					bulan_request:dt.bulan_request,
					barang_id:dt.barang_id,
					warna_id:dt.warna_id,
					qty_before:dt.qty,
					qty:0,
					status:0
				});

				for (let j = 0; j < nd.length; j++) {
					if (typeof idx[`${nd[j].bulan_request_n}`] === 'undefined') {
						idx[`${nd[j].bulan_request_n}`] = 0;

						if (dt.bulan_request == nd[j].bulan_request) {
							idx[`${nd[j].bulan_request_n}`]++;
							t_idx++;
						}
					}

					if(nd[j].qty_before != nd[j].qty){
						idx[`${nd[j].bulan_request_n}`]++;
						t_idx++;
					}
					
				}
				
				let idx_r = 0;
				for (let j = 0; j < nd.length; j++) {
					let fc = '';
					let tc = '';

					if(nd[j].qty_before != nd[j].qty){
						if (idx_r == 0) {
							fc = `<td rowspan='${t_idx}'>${dt.nama_barang} ${dt.nama_warna}</td>`;
							if (tipe == 1) {
								fc += `<td rowspan='${t_idx}'>${dt.po_number}</td>
									<td rowspan='${t_idx}'>${dt.qty}</td>`;
							}
						}

						if (typeof tcv[nd[j].bulan_request_n] === 'undefined') {
							tcv[nd[j].bulan_request_n] = true;
							let nRow = idx[`${nd[j].bulan_request_n}`];
							tc = `<td rowspan='${nRow}'>${nd[j].bulan_request_n}</td>`;
						}
						
						if (idx_r==0) {
							// style='background-color:#ffaaaa'
							// style='color:red'
							text += `<tr>
										${fc}
										${tc}
										<td style='color:red'>${dt.po_number} <i class='fa fa-lock'></i></td>
										<td style='color:red' class='text-right'>${change_number_format(dt.qty)}</td>
										<td style='color:red' class='text-center'><i class='fa fa-long-arrow-right'></i></td>
										<td style='color:red' class='text-right'>${0}</td>
									</tr>
									<tr>
										<td>${nd[j].po_number}</td>
										<td class='text-right'>${change_number_format(nd[j].qty_before)}</td>
										<td class='text-center'><i class='fa fa-long-arrow-right'></i></td>
										<td class='text-right'>${change_number_format(nd[j].qty)}</td>
									</tr>
									`;
						}else{
							text += `
									<tr>
										${fc}
										${tc}
										<td>${nd[j].po_number}</td>
										<td class='text-right'>${change_number_format(nd[j].qty_before)}</td>
										<td class='text-center'><i class='fa fa-long-arrow-right'></i></td>
										<td class='text-right'>${change_number_format(nd[j].qty)}</td>
									</tr>
									`;
						}
						idx_r++;

						po_lock_assign.push({
							id:nd[j].request_barang_detail_id,
							po_pembelian_batch_id:nd[j].po_pembelian_batch_id,
							bulan_request:nd[j].bulan_request,
							barang_id:dt.barang_id,
							warna_id:dt.warna_id,
							qty_before:nd[j].qty_before,
							qty:nd[j].qty,
							status:1
						});
					}
				}
				
				let span = (tipe == 1 ? 8 : 6);
				if (text != '') {
					text += `<tr>
							<td colspan='${span}' style='background:#ccc; padding:2px;'></td>
						</tr>`;
				}

			}

			if(tipe == 1){
				let tbl_header = `<table class='table table-bordered'>
					<thead>
						<tr>
							<th>Barang</th>
							<th>PO LOCK</th>
							<th>QTY</th>
							<th>Bulan</th>
							<th>PO Baru</th>
							<th class='text-right'>QTY</th>
							<th class='text-right'></th>
							<th class='text-right'></th>
						</tr>
					</thead><tbody>`;
				if (text != '') {
					text = tbl_header+text+"</tbody></table>";
				}else{
					text = "Tidak ada penyesuaian";
				}
				// $("#poLockInfo").html(res.text);
				$("#poLockInfo").html(text);
				$(".btn-po-lock-btn").html(`Penyesuaian PO <span class="badge badge-default"  style='background-color:yellow;color:black'>${parseInt(JmlPOUpdate)+parseInt(JmlPOBaru)+parseInt(JmlPORevisi)}</span>`);
			}else if(tipe == 2){
				let tbl_header = `<table class='table'>
					<thead>
						<tr style='background-color:lightblue'>
							<th>Barang</th>
							<th>Bulan</th>
							<th>PO Baru</th>
							<th class='text-right'>QTY</th>
							<th class='text-right'></th>
							<th class='text-right'></th>
						</tr>
					</thead><tbody>`;

				if (text != '') {
					text = "<hr/><h3>PENYESUAIAN PO LOCK</h3>"+tbl_header+text+"</tbody></table>";
				}else{
					text = "<hr/><h3>PENYESUAIAN PO LOCK</h3><p>Tidak ada Penyesuaian</p>";
				}
				
				// console.log('pla',po_lock_assign);
				$("#po-lock-assign").html(text);
				
			}
			$("#po_lock_assign").val(JSON.stringify(po_lock_assign));
			$("#po_lock_assign2").val(JSON.stringify(po_lock_assign));
				
			// $("#poLockInfo").html(data_respond);
			// if(data_respond != ''){
			// 	$(".btn-po-lock-btn").html(`PO Lock Update <span class="badge badge-default"></span>`);
			// }

			$(".btn-refresh-po").prop('disabled',false);
			$("#btnRefreshPenyesuaian").text("Refresh");
			$("#btnPenyesuaian").text("Update");
		});

		getPOBaru();
		getPORevisi(2);
	}

	function toggleBarangSelected(){
		<?if ($barang_id != '') {?>
			$('.row-hidden').toggle(); 
		<?}?>
	}

	function getPOBaru(){
		$(".btn-refresh-po").prop('disabled',true).text('..update');
		let no_batch = "<?=$no_batch;?>";
		if (po_baru_list.length > 0) {
			var data = {};
			data['tanggal'] = $("#tanggalRequestAktif").val();
			data['tipe'] = 2;
			data['request_barang_batch_id'] = "<?=$request_barang_batch_id;?>";
			data['request_barang_id'] = "<?=$request_barang_id?>";
			data['supplier_id'] = "<?=$supplier_id?>";
			data['po_baru_list'] = po_baru_list;
			var url = "transaction/po_baru_list_manage_info";
			var po_baru_assign = {};
			let jatah_po = {};
			let sisa_qty_request = [];
			let sisa_qty = {};
			let text = '';
			ajax_data_sync(url,data).done(function(data_respond  ,textStatus, jqXHR ){
				let res = JSON.parse(data_respond);
				$("#tanggalPOScreen").html(res.tanggal);
				console.log(res.data);
				if (res.data) {
					$.each(res.data, function(i,v){
						if (typeof jatah_po[`s-${v.barang_id}-${v.warna_id}`] === 'undefined') {
							jatah_po[`s-${v.barang_id}-${v.warna_id}`] = [];
						}
		
						jatah_po[`s-${v.barang_id}-${v.warna_id}`].push({
							po_pembelian_batch_id : v.po_pembelian_batch_id,
							qty:v.qty,
							po_number:v.po_number
						});
					})
		
					for (let i = 0; i < po_baru_list.length; i++) {
						// console.log(i,po_baru_list[i]);
						if(typeof sisa_qty[po_baru_list[i].bulan_request] == 'undefined' ){
							sisa_qty[po_baru_list[i].bulan_request] = [];
						}
						let new_qty = 0;
						let sisa_request = po_baru_list[i].qty;
						if (typeof jatah_po[`s-${po_baru_list[i].barang_id}-${po_baru_list[i].warna_id}`] !== 'undefined' ) {

							$.each(jatah_po[`s-${po_baru_list[i].barang_id}-${po_baru_list[i].warna_id}`], function(j,v){
								// console.log('sq', sisa_request, v.qty);
								if (sisa_request > 0 && v.qty > 0) {
									if (parseFloat(sisa_request) <= parseFloat(v.qty) ) {
										new_qty = sisa_request;
										v.qty = v.qty - sisa_request;
										sisa_request = 0;
									}else if(sisa_request > 0 && parseFloat(sisa_request) > parseFloat(v.qty) && v.qty > 0){
										sisa_request -= v.qty;
										new_qty = v.qty;
										v.qty = 0;
										sisa_qty[po_baru_list[i].bulan_request][`s-${po_baru_list[i].barang_id}-${po_baru_list[i].warna_id}`] = sisa_request;
										
										// console.log('sq',sisa_qty);
									}
		
									if (typeof po_baru_assign[po_baru_list[i].bulan_request_n] === 'undefined' ) {
										po_baru_assign[po_baru_list[i].bulan_request_n] = [po_baru_list[i].warna_bg];
									}
		
									po_baru_assign[po_baru_list[i].bulan_request_n].push({
										request_barang_detail_id:po_baru_list[i].request_barang_detail_id,
										bulan_request:po_baru_list[i].bulan_request,
										bulan_request_n:po_baru_list[i].bulan_request_n,
										po_pembelian_batch_id:v.po_pembelian_batch_id,
										barang_id:po_baru_list[i].barang_id,
										warna_id:po_baru_list[i].warna_id,
										qty:new_qty,
										nama_barang:po_baru_list[i].nama_barang,
										nama_warna:po_baru_list[i].nama_warna,
										po_number:v.po_number
									});
									// console.log('ya',po_baru_list[i].warna_id);
									// console.log('ya',po_baru_assign[po_baru_list[i].bulan_request_n]);
								}
								// console.log(j, jatah_po[`s-${po_baru_list[i].barang_id}-${po_baru_list[i].warna_id}`][j]);
							})
						}
					}
					
					//ini untuk ngecek ada request yang masih lebih besar dari Po yang baru dibuat
					for (let i = 0; i < po_baru_list.length; i++) {
						// console.log(i,po_baru_list[i]);
						let sisa = sisa_qty[po_baru_list[i].bulan_request][`s-${po_baru_list[i].barang_id}-${po_baru_list[i].warna_id}`];
						// console.log(po_baru_list[i].warna_id,sisa);
						if(sisa && parseFloat(sisa) > 0){
							po_baru_assign[po_baru_list[i].bulan_request_n].push({
								request_barang_detail_id:po_baru_list[i].request_barang_detail_id,
								bulan_request:po_baru_list[i].bulan_request,
								po_pembelian_batch_id: 0,
								barang_id:po_baru_list[i].barang_id,
								warna_id:po_baru_list[i].warna_id,
								qty:parseFloat(sisa),
								nama_barang:po_baru_list[i].nama_barang,
								nama_warna:po_baru_list[i].nama_warna,
								po_number:"<b>PO BARU</b>"
							});
		
						}
					}

					// console.log('new',po_baru_assign);
		
					let po_assign = "";
					let po_assign_head = "";
					$.each(po_baru_assign, function(bulan_request, data){
						// console.log(bulan_request,data);
						for (let i = 0; i < data.length; i++) {
							if (i == 0) {
								po_assign += `<thead>
								<tr style='background-color:${data[i]}'>
									<th colspan='4'>bulan : ${bulan_request}</th>
								</tr>
								<tr>
									<th>Barang</th>
									<th>PO</th>
									<th>PO Tujuan</th>
									<th>QTY</th>
								</tr>
							</thead><tbody>`;
							}else{
								po_assign += `<tr>
									<td>${data[i].nama_barang} ${data[i].nama_warna} </td>
									<td>PO Baru </td>
									<td>${data[i].po_number} </td>
									<td>${change_number_format(parseFloat(data[i].qty))}</td>
								</tr>`;
							}
							// text += po_assign+'</tr>';
							// console.log('poa',data[i].nama_warna,text);
						}
					});

		
					$('#poBaruUpdate').html("");
					// $('#po-baru-assign').html("");
					if (po_assign.length > 0) {
						$('#poBaruUpdate').html(`<table class='table'>${po_assign}</table>`);
						$("#po_baru_assign2").val(JSON.stringify(po_baru_assign));
						// console.log(po_baru_assign);
					}else{
						$('#poBaruUpdate').html(`<p>Tidak ada Penyesuaian</p>`);
					}
				}
				
				JmlPOBaru = res.count;
				$(".btn-po-lock-btn").html(`Penyesuaian PO <span class="badge badge-default"  style='background-color:yellow;color:black'>${parseInt(JmlPOUpdate)+parseInt(JmlPOBaru)+parseInt(JmlPORevisi)}</span>`);
				// if (text.length > 0) {
					
				// 	$("#po_baru_assign2").val(JSON.stringify(po_baru_assign));
				// 	$('#poBaruUpdate').html(`<table class='table table-bordered'>${text}</table>`);
				// 	// $("#po_baru_update").val(JSON.stringify(po_baru_assign));
				// 	JmlPOBaru = res.count;
				// 	// console.log('count',JmlPOUpdate+'+'+JmlPOBaru)
				// }else{
				// 	$('#poBaruUpdate').html(`Tidak ada Penyesuaian`);
				// }
				
				$(".btn-refresh-po").prop('disabled',false);
				$("#btnRefreshPenyesuaian").text("Refresh");
				$("#btnPenyesuaian").text("Update");
			});
			
		}
	}

	function submitPenyesuaian(){
		btn_disabled_load($('#btnPenyesuaian'));
		$("#form-po-penyesuaian").submit();
	}

	function deleteRequest(){
		bootbox.confirm("Yakin menghapus REQUEST <b><?=$no_request_lengkap;?></b> ? ", function(respond){
			if(respond){
				$('#form-request-delete').submit();
			}
		});
	}

	function cek_pin(form, field_class){
		// alert('test');
		var data = {};
		data['pin'] = form.find(field_class).val();
		var url = 'transaction/cek_pin';
		// ajax_data_sync(url,data).done(function(data_respond  /*,textStatus, jqXHR*/ ){
		// 	if (data_respond == "OK") {
		// 		return true;
		// 	}else{
		// 		alert("PIN Invalid");
		// 	}
		// });

		var result = ajax_data(url,data);
		if (result == 'OK') {
			return true;
		};
	}


	function getPORevisi(tipe){
		$(".btn-refresh-po").prop('disabled',true).text('...update');
		var data = {};
		data['request_barang_id'] = "<?=$request_barang_id?>";
		data['tanggal_awal'] = "<?=$tanggal_awal?>";
		var url = "transaction/po_revisi_list_manage_info";
		ajax_data_sync(url,data).done(function(data_respond  ,textStatus, jqXHR ){
			let res = JSON.parse(data_respond);
			// console.log(res.text);
			console.log('rd',res.data);
			JmlPORevisi = res.data.length;
			let po_revisi_assign = [];
			let text = '';
			
			for (let i = 0; i < res.data.length; i++) {
				let dt = res.data[i];
				let nd = res.data[i].data;
				let idx = [];
				let t_idx = 0;
				let tcv = [];

				console.log('dt',dt);
				po_revisi_assign.push({
					id:dt.id_detail,
					po_pembelian_batch_id:dt.po_pembelian_batch_id,
					bulan_request:dt.bulan_request,
					barang_id:dt.barang_id,
					warna_id:dt.warna_id,
					qty_before:dt.qty,
					qty:0,
					status:0
				});

				for (let j = 0; j < nd.length; j++) {
					if (typeof idx[`${nd[j].bulan_request_n}`] === 'undefined') {
						idx[`${nd[j].bulan_request_n}`] = 0;

						if (dt.bulan_request == nd[j].bulan_request) {
							idx[`${nd[j].bulan_request_n}`]++;
							t_idx++;
						}
					}

					if(nd[j].qty_before != nd[j].qty){
						idx[`${nd[j].bulan_request_n}`]++;
						t_idx++;
					}
					
				}
				
				let idx_r = 0;
				for (let j = 0; j < nd.length; j++) {
					let fc = '';
					let tc = '';

					if(nd[j].qty_before != nd[j].qty){
						if (idx_r == 0) {
							fc = `<td rowspan='${t_idx}'>${dt.nama_barang} ${dt.nama_warna}</td>`;
							if (tipe == 1) {
								fc += `<td rowspan='${t_idx}'>${dt.po_number}</td>
									<td rowspan='${t_idx}'>${dt.qty}</td>`;
							}
						}

						if (typeof tcv[nd[j].bulan_request_n] === 'undefined') {
							tcv[nd[j].bulan_request_n] = true;
							let nRow = idx[`${nd[j].bulan_request_n}`];
							tc = `<td rowspan='${nRow}'>${nd[j].bulan_request_n}</td>`;
						}
						
						if (idx_r==0) {
							// style='background-color:#ffaaaa'
							// style='color:red'
							text += `<tr>
										${fc}
										${tc}
										<td style='color:red'>${dt.po_number} <i class='fa fa-lock'></i></td>
										<td style='color:red' class='text-right'>${change_number_format(dt.qty)}</td>
										<td style='color:red' class='text-center'><i class='fa fa-long-arrow-right'></i></td>
										<td style='color:red' class='text-right'>${0}</td>
									</tr>
									<tr>
										<td>${nd[j].po_number}</td>
										<td class='text-right'>${change_number_format(nd[j].qty_before)}</td>
										<td class='text-center'><i class='fa fa-long-arrow-right'></i></td>
										<td class='text-right'>${change_number_format(nd[j].qty)}</td>
									</tr>
									`;
						}else{
							text += `
									<tr>
										${fc}
										${tc}
										<td>${nd[j].po_number}</td>
										<td class='text-right'>${change_number_format(nd[j].qty_before)}</td>
										<td class='text-center'><i class='fa fa-long-arrow-right'></i></td>
										<td class='text-right'>${change_number_format(nd[j].qty)}</td>
									</tr>
									`;
						}
						idx_r++;

						po_revisi_assign.push({
							id:nd[j].request_barang_detail_id,
							po_pembelian_batch_id:nd[j].po_pembelian_batch_id,
							bulan_request:nd[j].bulan_request,
							barang_id:dt.barang_id,
							warna_id:dt.warna_id,
							qty_before:nd[j].qty_before,
							qty:nd[j].qty,
							status:1
						});
					}
				}
				
				let span = (tipe == 1 ? 8 : 6);
				if (text != '') {
					text += `<tr>
							<td colspan='${span}' style='background:#ccc; padding:2px;'></td>
						</tr>`;
				}

			}

			if(tipe == 1){
				let tbl_header = `<table class='table table-bordered'>
					<thead>
						<tr>
							<th>Barang</th>
							<th>PO LOCK</th>
							<th>QTY</th>
							<th>Bulan</th>
							<th>PO Baru</th>
							<th class='text-right'>QTY</th>
							<th class='text-right'></th>
							<th class='text-right'></th>
						</tr>
					</thead><tbody>`;
				if (text != '') {
					text = tbl_header+text+"</tbody></table>";
				}else{
					text = "Tidak ada penyesuaian";
				}
				// $("#poLockInfo").html(res.text);
				$("#poRevisiUpdate").html(text);
				$(".btn-po-lock-btn").html(`Penyesuaian PO <span class="badge badge-default"  style='background-color:yellow;color:black'>${parseInt(JmlPOUpdate)+parseInt(JmlPOBaru)+parseInt(JmlPORevisi)}</span>`);
			}else if(tipe == 2){
				let tbl_header = `<table class='table'>
					<thead>
						<tr style='background-color:lightblue'>
							<th>Barang</th>
							<th>Bulan</th>
							<th>PO Baru</th>
							<th class='text-right'>QTY</th>
							<th class='text-right'></th>
							<th class='text-right'></th>
						</tr>
					</thead><tbody>`;

				if (text != '') {
					text = "<hr/><h3>PENYESUAIAN PO REVISI</h3>"+tbl_header+text+"</tbody></table>";
				}else{
					text = "<hr/><h3>PENYESUAIAN PO REVISI</h3><p>Tidak ada Revisi</p>";
				}
				
				$("#po_revisi_assign").val(JSON.stringify(po_revisi_assign));
				$("#po_revisi_assign2").val(JSON.stringify(po_revisi_assign));
				console.log('pla',po_revisi_assign);
				$("#po-revisi-assign").html(text);
				
			}
			// $("#poLockInfo").html(data_respond);
			// if(data_respond != ''){
			// 	$(".btn-po-lock-btn").html(`PO Lock Update <span class="badge badge-default"></span>`);
			// }

			// $(".btn-refresh-po").prop('disabled',false);
			// $("#btnRefreshPenyesuaian").text("Refresh");
			// $("#btnPenyesuaian").text("Update");
		});
	}

	function updateKeterangan(barang_id, warna_id, bulan_request){
		
		var data = {};
		let kt =  $(`#ket${barang_id}-${warna_id}-${bulan_request}`).val();
		const url = "transaction/update_keterangan_request_barang";
		data['request_barang_id'] = request_barang_id;
		data['request_barang_batch_id'] = request_barang_batch_id;
		data['barang_id'] = barang_id;
		data['bulan_request'] = bulan_request;
		data['warna_id'] = warna_id;
		data['keterangan'] = kt;

		ajax_data_sync(url,data).done(function(data_respond ,textStatus, jqXHR ){
			console.log(textStatus, textStatus.length);
			if (data_respond == 'OK') {
				notific8("lime", "Keterangan Updated");
			}
			// console.log(textStatus,jqXHR);
		});
	}

	function showKeterangan(barang_id, warna_id, bulan_request) {
		$(`.ket${barang_id}-${warna_id}-${bulan_request}`).toggle();
	}
</script>