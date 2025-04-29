<?
		$color_list = [];
		$nama_barang_terpilih = '';
		$bulan_berjalan=(float)date("m", strtotime($tanggal_start));
		$tgl1 = new DateTime(is_date_formatter($tanggal_start_forecast));
		$tgl2 = new DateTime(is_date_formatter($tanggal_end_forecast));

		$tgl1_now = new DateTime(is_date_formatter($tanggal_start));
		$tgl2_now = new DateTime(is_date_formatter($tanggal_end));

		//yg atas sama dengan bawah
		// echo is_date_formatter($tanggal_start);
		// echo is_date_formatter($tanggal_end);
		$jarak = date_diff($tgl1, $tgl2);
		$range_tanggal = $jarak->format('%m')+1;
		// echo "<h1>$range_tanggal</h1>";

		$interval = new DateInterval('P1M');
		$periode = new DatePeriod($tgl1, $interval, $tgl2->setTime(0,0,1));
		$periode_now = new DatePeriod($tgl1_now, $interval, $tgl2_now->setTime(0,0,1));
		$isShown = array();
		$barisPerBulan = array();
		$brs = array();



		// echo $bulan_berjalan;
		foreach ($data_warna as $row) {
			$color_list[$row->warna_id] = ($row->kode_warna != '' ? $row->kode_warna : '#ccc');
			$total_qty[$row->warna_id]=0;
			$total_amount[$row->warna_id]=0;
			$total_trx_warna_all[$row->warna_id]=0;
			$qty_total_data[$row->warna_id] = 0;
			$qty_stok[$row->warna_id] = 0;
			$qty_stok_update[$row->warna_id] = 0;
			$qty_stok_data[$row->warna_id] = '';
			$qty_outstanding[$row->warna_id] = 0;
			$qty_outstanding_update[$row->warna_id] = 0;
			$qty_jual[$row->warna_id] = [];
			$qty_request[$row->warna_id] = [];
		}

		// foreach ($data_penjualan as $row) {
		// 	if (is_posisi_id()==1) {
		// 		print_r($row);
		// 		echo '<hr/>';
		// 	}
		// }

		$data_jual = (array)$data_penjualan;
		$data_show = array();
		foreach ($data_jual as $key => $value) {
			$data_show[$value->bulan_jual] = $value;
			$total_trx[$value->bulan_jual] = 0;
			$penjualan_id_list[$value->bulan_jual] = array();
		}

		
		foreach ($data_show as $i => $value) {
			$warna_id_list = explode(",", $value->warna_id);
			// $penjualan_data[$i] = explode(",", $value->penjualan_data);
			// $count_trx_data[$i] = explode("=?=", $value->penjualan_id);
			$qty_data[$i] = explode(",", $value->qty_data);
			
			$qty_history[$i] = explode(",", $value->qty_history);
			$created_history[$i] = explode(",", $value->history_created);

			// foreach ($count_trx_data[$i] as $key2 => $value2) {
			// 	$break = explode("??", $value2);
			// 	foreach ($break as $key3 => $value3) {
			// 		$break2 = explode(",", $value3);
			// 		foreach ($break2 as $key4 => $value4) {
			// 			array_push($penjualan_id_list[$i], $value4);
			// 		}
			// 	}
			// }

			if (is_posisi_id() == 1) {
			}
			// $count_trx_warna[$i] = array_combine($warna_id_list, $count_trx_data[$i]);
			// foreach ($count_trx_warna[$i] as $key2 => $value2) {
			// 	$total_trx_warna[$i][$key2] = array();
			// }
			// foreach ($count_trx_warna[$i] as $key2 => $value2) {
			// 	$break = explode("??", $value2);
			// 	foreach ($break as $key3 => $value3) {
			// 		$break2 = explode(",", $value3);
			// 		foreach ($break2 as $key4 => $value4) {
			// 			array_push($total_trx_warna[$i][$key2], $value4);
			// 		}
			// 	}
			// 	$total_trx_warna[$i][$key2] = array_unique($total_trx_warna[$i][$key2]);
			// }

			// $penjualan_id_list[$i] = array_unique($penjualan_id_list[$i]);

			// $total_trx[$i] = count($penjualan_id_list[$i]);
			// $penjualan_data[$i] = array_combine($warna_id_list, $penjualan_data[$i]);
			$qty_data[$i] = array_combine($warna_id_list, $qty_data[$i]);
			// if (isset($qty_history[$i])) {
				// print_r($qty_history[$i]);
				// echo '<br/>';
				// print_r($warna_id_list);
				// echo '<br/>';
				// echo $i;
				$qty_history_data[$i] = array_combine($warna_id_list, $qty_history[$i]);
				$created_history_data[$i] = array_combine($warna_id_list, $created_history[$i]);
				// echo '<hr/>';
			// }
		}

		

		foreach ($periode as $date) {
		// for ($i=1; $i <= 12 ; $i++) {
			$bln = $date->format("Y-m");
			$qty_total_bulan[$bln] = 0;
			if (!isset($qty_data[$bln])) {
				$qty_data[$bln] = array();	
			}

			foreach ($qty_data[$bln] as $key => $value) {
				$qty_total_data[$key] += $value;
			}
		}


		// foreach ($data_warna as $row) {
		// 	$rate = round($qty_total_data[$row->warna_id]/12);
		// 	for ($i=1; $i <= 12 ; $i++) { 
		// 		$qty_rate[$tahun.'-'.str_pad($i,'2','0', STR_PAD_LEFT)][$row->warna_id] = $rate;
		// 	}
		// }

		foreach ($data_warna as $row) {
			// echo $qty_total_data[$row->warna_id].' / '.$range_tanggal;
			foreach ($periode as $date) {
				$i = $date->format('n');
				$key = $date->format('Y-m');
				
				$qtyOri = '';
				if (isset($created_history_data[$key][$row->warna_id]) && $created_history_data[$key][$row->warna_id] != '-') {
					if (isset( $qty_history_data[$key][$row->warna_id])) {
						$qty_total_bulan[$key] += (isset($qty_data[$key][$row->warna_id]) ? $qty_data[$key][$row->warna_id] : 0);
						$qtyList = explode('??', $qty_history_data[$key][$row->warna_id]); 
						foreach ($qtyList as $key2 => $value2) {
							if ($key2 == 0) {
								$qtyOri += $value2;
							}
						}
					}
				}

				if ($qtyOri == '') {
					$qtyOri = (isset($qty_data[$key][$row->warna_id]) ? $qty_data[$key][$row->warna_id] : 0);
				}

				// $qty_total_bulan_ori[$key] += $qtyOri;
			}
			
		}

		
		foreach ($stok_barang as $row) {
			foreach ($this->gudang_list_aktif as $row2) {
				if (isset($qty_stok[$row->warna_id])) {
					$qty_stok[$row->warna_id] += $row->{'gudang_'.$row2->id.'_qty'};
					if ($row->{'gudang_'.$row2->id.'_qty'} > 0) {
						$qty_stok_data[$row->warna_id] .= $row2->nama.' : '.number_format((float)$row->{'gudang_'.$row2->id.'_qty'},'0',',','.').'<br/>';
					}
				}else{
					// echo $row->nama_warna;
				}
			}
		};

		//===================================================================================================
		foreach ($barang_group as $row) {
			$bgList[$row->barang_id] = $row->barang_id_induk;
			$bgListName[$row->barang_id] = $row->nama_barang_induk;
		}
		//===================================================================================================

		$po_number_list = array(); 
		$po_qty_list = array(); 
		foreach($data_outstanding_po as $row){
			// print_r($row);
			$qty_outstanding[$row->warna_id] = $row->qty_outstanding;
			$po_qty[$row->warna_id] = explode(",", $row->po_qty);
			$po_number[$row->warna_id] = explode("??", $row->po_number);
			$batch_tanggal[$row->warna_id] = explode(",", $row->batch_tanggal);
			$batch_id[$row->warna_id] = explode(",", $row->po_pembelian_batch_id);
			$qty_detail[$row->warna_id] = explode(",", $row->qty_data);
			foreach ( $batch_tanggal[$row->warna_id] as $key => $value) {
				// echo $row->warna_id.' - '.$value.' - '.$batch_id[$row->warna_id][$key].'<hr/>';
				$po_number_list[$value][$batch_id[$row->warna_id][$key]] = $po_number[$row->warna_id][$key];
				$po_qty_list[$row->warna_id][$value][$batch_id[$row->warna_id][$key]] = $po_qty[$row->warna_id][$key];
				$po_qty_sisa[$row->warna_id][$value][$batch_id[$row->warna_id][$key]] = $qty_detail[$row->warna_id][$key];
			}
		};

		foreach ($data_outstanding_update as $row) {
			$qty_outstanding_update[$row->warna_id] = $row->qty_outstanding;
		}

		// echo count($qty_outstanding);
		// print_r($qty_outstanding);
		// echo '<hr/>';
		// echo count($qty_outstanding_update);
		// print_r($qty_outstanding_update);

		foreach ($stok_barang_update as $row) {
			foreach ($this->gudang_list_aktif as $row2) {
				if (isset($qty_stok_update[$row->warna_id])) {
					$qty_stok_update[$row->warna_id] += $row->{'gudang_'.$row2->id.'_qty'};
				}
			}
		};

		foreach ($penjualan_berjalan as $row) {
			// echo $row->warna_id.' '.$row->bulan_berjalan.' '.$row->qty.'<br/>';
			$qty_jual[$row->warna_id][$row->bulan_berjalan]	= $row->qty;
		}

		ksort($po_number_list);
		// print_r($po_number_list);
		// echo "<hr/>";
		// print_r($po_qty_list);

		$request_barang_id = ''; $tanggal = ''; $no_batch = ''; $locked_by = ''; $locked_date = '';
		$no_request = ''; $no_batch = ''; $no_request_lengkap = ''; $kode_supplier = '';
		$request_barang_batch_id = ''; $nama_supplier = ''; $attn = '';

		foreach ($request_barang_data as $row) {
			$request_barang_batch_id = $row->id;
			$request_barang_id = $row->request_barang_id;
			$tanggal = $row->tanggal;
			$no_batch = $row->batch;
			$no_request = $row->no_request;
			$locked_by = $row->locked_by;
			$locked_date = $row->locked_date;
			$no_request_lengkap = $row->no_request_lengkap;
			$nama_supplier = $row->nama_supplier;
			$attn = $row->attn;

		}

		foreach ($supplier_data as $row) {
			$nama_supplier = $row->nama;
		}

		$nama_jual = ''; $nama_beli = '';
		foreach ($data_barang as $row) {
			$nama_jual = $row->nama_jual;
			$nama_beli = $row->nama;

		}

		$terkirim = array();
		$total_terkirim = array();
		$isTercapai = array();
		$classStatus = array();
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

				if (!isset($terkirim[$row->barang_id][$row->warna_id][$b_now])) {
					$terkirim[$row->barang_id][$row->warna_id][$b_now] = 0;
					$req[$b_now] = 0;
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
						// echo '2 ',$req[$b_now] .'<'. $terkirim[$row->barang_id][$row->warna_id][$b_now] .'+'. $qData[$i].'<br/>';
					}
					$b_next = date('Y-m-01', strtotime("+1 month", strtotime($b_now)));
					$sisa_next = $terkirim[$row->barang_id][$row->warna_id][$b_now] + $qData[$i] -  $req[$b_now] ;
					$pers = 0;
					if ($req[$b_now] > 0) {
						$pers = round($sisa_next/$req[$b_now] * 100, 2);
						# code...
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
						// echo '3 ',$req[$b_now] .'<'. $terkirim[$row->barang_id][$row->warna_id][$b_now] .'+'. $qData[$i].'<br/>';
					}
					$terkirim[$row->barang_id][$row->warna_id][$b_now] += $qData[$i];

				}
			}
		}

		if (is_posisi_id() == 1) {
			// print_r($terkirim_by_warna);
			// print_r($terkirim);
			# code...
		}

		foreach ($request_barang_qty_data as $row) {
			$qty_request[$row->warna_id][$row->bulan_request] = $row->qty;
			$isShown[$row->bulan_request][$row->barang_id][$row->warna_id] = 1;
		}

		$tamb_baris = array();
		foreach ($request_barang_qty_keterangan as $row) {
			if ($row->keterangan != '') {
				$keterangan_qty[$row->bulan_request][$row->barang_id][$row->warna_id] = $row->keterangan;
				if (!isset($tamb_baris[$row->bulan_request][$row->barang_id])) {
					$tamb_baris[$row->bulan_request][$row->barang_id] = 0;
				}
				$tamb_baris[$row->bulan_request][$row->barang_id]++;
			}
		}

		foreach ($request_barang_status as $row) {
			if (isset($bgList[$row->barang_id])) {
				    $isFinished[$bgList[$row->barang_id]][$row->warna_id][$row->bln_request] = array('id'=>$row->id,'status'=>($row->username != '' ? true : false) );
			}else{
				$isFinished[$row->barang_id][$row->warna_id][$row->bln_request] = array('id'=>$row->id,'status'=>($row->username != '' ? true : false) );
            }
		}
		// print_r($isFinished);

		$tanggal = ($tanggal != '' ? is_reverse_date($tanggal) : '');
		$status_beda = array();
		$total_request = array();
		foreach ($request_barang_qty_all as $row) {
			$status_beda[$row->barang_id][$row->warna_id][$row->bulan_request] = ($no_batch == 1 ? 0 : $row->tipe );
		}
		$month_color = ["255, 160, 122",'251, 122, 172',"255,128,0","144, 238, 144","174, 216, 230","64, 224, 208","222, 210, 73","216, 191, 216","211, 211, 211","221, 168, 129","233, 255, 145","138, 122, 193"];
		$idx_color = 0;

		foreach ($request_barang_detail as $row) {
			//====================break data from database ==========================
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
			$g_total[$row->bulan_request] = 0;

			
			foreach ($barang_id_get as $key => $value) {
				if (!isset($total_request[$value][$bulan_request])) {
					if (isset($bgList[$value])) {
						if (!isset($total_request[$bgList[$value]][$bulan_request])) {
							$total_request[$bgList[$value]][$bulan_request] = 0;
						}
					}else{
						$total_request[$value][$bulan_request] = 0;
					}
				}
				//=============spread data / id barang
				$request_barang_detail_id_b = explode(",", $request_barang_detail_id[$key]);
				$po_pembelian_batch_id_b = explode(",", $po_pembelian_batch_id[$key]);
				$po_number_b = explode(",", $po_number[$key]);
				$nama_warna_b = explode(",", $nama_warna[$key]);
				$warna_id_b = explode(",", $warna_id[$key]);

				$qty_b = explode(",", $qty[$key]);
				$status_urgent_b = explode(",", $status_urgent[$key]);
				$warna_id_group = array_unique($warna_id_b);
				$warna_id_count = array_count_values($warna_id_b);
				$idx = 0;
				
				if ($barang_id == $value) {
					foreach ($po_pembelian_batch_id_b as $key2 => $value2) {
						$bln_r = date('Y-m', strtotime($bulan_request));
						$warna_id_selected[$warna_id_b[$key2]][$bln_r][$value2] = $qty_b[$key2];
					}
				}
					
				$widList = array();
				$subtotal = array();
				$po_by_warna = array();
				$list = array();
				foreach ($warna_id_b as $key2 => $value2) {
					//key2=index 0...9
					if (!isset($widList[$value2])) {
						$widList[$value2] = array();
						$subtotal[$value2] = 0;
						$po_by_warna[$value2] = array();
					}

					if (isset($bgList[$value])) {
						$total_request[$bgList[$value]][$bulan_request] += $qty_b[$key2];
						$g_total[$bulan_request] += $qty_b[$key2];
					}else{
						$total_request[$value][$bulan_request] += $qty_b[$key2];
						$g_total[$bulan_request] += $qty_b[$key2];
					}

					$subtotal[$value2] += $qty_b[$key2];
					array_push($widList[$value2],array(
						'nama' => $nama_warna_b[$key2],
						'qty' => $qty_b[$key2],
						'po_number' => $po_number_b[$key2],
						'po_pembelian_batch_id' => $po_pembelian_batch_id_b[$key2],
						'request_barang_detail_id' => $request_barang_detail_id_b[$key2],
						'status_urgent' => $status_urgent_b[$key2]
					));

					array_push($po_by_warna[$value2], $request_barang_detail_id_b[$key2]);

				}

				// if (is_posisi_id()==1) {
				// 	echo '<hr/>';
				// 	echo $value.' '.$bulan_request."<br/>";
				// 	echo $nama_barang[$key].' '.$nama_warna_b[$key2];
				// }
				
				if (isset($bgList[$value])) {
				}else{
				}

				$request_per_warna = array();
				foreach ($warna_id_count as $key2 => $value2) {
					//========index key2 warna id============
					$idx2 = 0;
					$brs_warna = count($widList[$key2]) - 1;
					$tp = (isset($status_beda[$value][$key2][$bulan_request]) ? $status_beda[$value][$key2][$bulan_request] : 0  );
					
					$bln_up = date('Y-m-01', strtotime("+1 year", strtotime($bulan_request)));
                    if (isset($bgList[$value])) {
                        $classStatus[$bgList[$value]][$key2][$bln_up] = '';
                    }else{
                        $classStatus[$value][$key2][$bln_up] = '';
                    }


					$trkrm = 0;
					if (isset($terkirim[$value][$key2][$bln_up]) && $terkirim[$value][$key2][$bln_up] > 0) {
						$trkrm = $terkirim[$value][$key2][$bln_up] ;
						if($subtotal[$key2] >=$trkrm ){
							if (is_posisi_id() == 1) {
							// echo '1'.$total_terkirim[$bln_up] .'+='. $trkrm;
							// echo "<br/>";
							}
							$total_terkirim[$bln_up] += $trkrm;
						}else{
							if (is_posisi_id() == 1) {
								// echo '2'.$total_terkirim[$bln_up] .'+='. $subtotal[$key2];
								// echo "<br/>";
							}
							$total_terkirim[$bln_up] += $subtotal[$key2];
						}
						// $trkrm = str_replace(",00","", number_format($terkirim[$value][$key2][$bln_up],2,",",".")) ;
						// if (is_posisi_id()==1) {
						// 	echo $trkrm.'<br/>';
						// }
					}

					foreach ($widList[$key2] as $key3 => $value3) {
						$nama_warna_now = $value3['nama'];
						//$key2 == warna_id
						//$key3 = index 0...n
						$urg = '';
						$urg = ($value3['status_urgent'] == 1 ? 'color:red' : '' );
						$brdr = ($idx2 == $brs_warna ? 'border-bottom:1px solid #333' : '');

						// if (is_posisi_id()==1) {
						// 	echo $value.' - '.$key2.' - '.$bln_up.' == ';
						// 	echo isset($terkirim[$value][$key2][$bln_up]);
						// 	if (isset($terkirim[$value][$key2][$bln_up])) {
						// 		echo str_replace(",00","", number_format($terkirim[$value][$key2][$bln_up],2,",",".")) ;
						// 		echo '<br/>';
						// 	}
						// }
						$trkrm = 0;
						if (isset($terkirim[$value][$key2][$bln_up]) && $terkirim[$value][$key2][$bln_up] > 0) {
							// $trkrm = $terkirim[$value][$key2][$bln_up] ;
							// if($subtotal[$key2] >=$trkrm ){
							// 	if (is_posisi_id() == 1) {
							// 	echo '1'.$total_terkirim[$bln_up] .'+='. $trkrm;
							// 	echo "<br/>";
							// 	}
							// 	$total_terkirim[$bln_up] += $trkrm;
							// }else{
							// 	if (is_posisi_id() == 1) {
							// 		// echo '2'.$total_terkirim[$bln_up] .'+='. $subtotal[$key2];
							// 		// echo "<br/>";
							// 	}
							// 	$total_terkirim[$bln_up] += $subtotal[$key2];
							// }
							$trkrm = str_replace(",00","", number_format($terkirim[$value][$key2][$bln_up],2,",",".")) ;
							// if (is_posisi_id()==1) {
							// 	echo $trkrm.'<br/>';
							// }
						}

						// if($subtotal[$key2] >=$terkirim[$value][$key2][$bln_up] ){
						// 	// echo '=='.$subtotal[$key2] .'>='.$terkirim[$value][$key2][$bln_up].'==';
						// 	$total_terkirim[$bln_up] += $terkirim[$value][$key2][$bln_up];
						// 	// echo '--'.$terkirim[$value][$key2][$bln_up].'--';
						// }else{
						// 	$total_terkirim[$bln_up] += $subtotal[$key2];
						// 	// echo '--'.$subtotal[$key2].'--';
						// }

						// echo $value3['nama'].' ';
						// echo $subtotal[$key2].'<br/>';

						$classStatusInit = 'status-cell';
						$classFinished = '';
						
                        
                        if (isset($bgList[$value])) {
                            $request_barang_qty_id = $isFinished[$bgList[$value]][$key2][$bln_up]['id'];
                            $bd_id = $bgList[$value];
                        }else{
                            $request_barang_qty_id = $isFinished[$value][$key2][$bln_up]['id'];
                            $bd_id = $value;
                        }

						if ($classStatus[$bd_id][$key2][$bln_up] == '') {
							
							
							if ($tp == 1) {
								$classStatus[$bd_id][$key2][$bln_up] .= 'revised';
								if ($urg != '') {
									$classStatus[$bd_id][$key2][$bln_up] .='-urgent';
								}
							}elseif($urg != ''){
								$classStatus[$bd_id][$key2][$bln_up] .= 'urgent';
							}

							if ($isFinished[$bd_id][$key2][$bln_up]['status']) {
								$classStatus[$bd_id][$key2][$bln_up] = 'finished ';
							}
							// echo $nama_barang[$key].' '.$bd_id3['nama'].' '.$tp;
							// var_dump($classStatus[$bd_id][$key2][$bln_up]);
							// echo $classStatus[$bd_id][$key2][$bln_up].'<br/>';
						}


						if (isset($bgList[$value])) {
							if (isset($baris[$bulan_request][$bgList[$value]]['jumlah_baris'] )) {
								$baris[$bulan_request][$bgList[$value]]['jumlah_baris'] += count($request_barang_detail_id_b);
							}else{
								$baris[$bulan_request][$bgList[$value]]['jumlah_baris'] = count($request_barang_detail_id_b);
							}
							$baris[$bulan_request][$bgList[$value]]['nama'] = $bgListName[$value];
							$baris[$bulan_request][$bgList[$value]][$key2]['jumlah_baris'] = $value2;
							$baris[$bulan_request][$bgList[$value]][$key2]['warna'] = $value3['nama'];
							$baris[$bulan_request][$bgList[$value]][$key2]['qty_warna'] = number_format($subtotal[$key2],'0',',','.');
							if(!isset($baris[$bulan_request][$bgList[$value]][$key2]['po_batch_number'])){
								$baris[$bulan_request][$bgList[$value]][$key2]['po_batch_number'] = array();
								$baris[$bulan_request][$bgList[$value]][$key2]['qty'] = array();
							}

						}else{
							$baris[$bulan_request][$value]['jumlah_baris'] = count($request_barang_detail_id_b);
							$baris[$bulan_request][$value]['nama'] = $nama_barang[$key];
							$baris[$bulan_request][$value][$key2]['jumlah_baris'] = $value2;
							$baris[$bulan_request][$value][$key2]['warna'] = $value3['nama'];
							$baris[$bulan_request][$value][$key2]['qty_warna'] = number_format($subtotal[$key2],'0',',','.');
							if(!isset($baris[$bulan_request][$value][$key2]['po_batch_number'])){
								$baris[$bulan_request][$value][$key2]['po_batch_number'] = array();
								$baris[$bulan_request][$value][$key2]['qty'] = array();
							}
						}
						
					}
					// $trkrm = 0;

					array_push($list, array(
						'nama'=> $nama_warna_now,
						'jumlah_baris'=> $value2,
						'qty_warna' => number_format($subtotal[$key2],'0',',','.'),
						'data'=> $widList[$key2],
						'warna_id' => $key2,
						'terkirim' => ($trkrm == 0 ? '' : $trkrm)
					));

				}

				
				$bln_up = date('Y-m-01', strtotime("+1 year", strtotime($bulan_request)));
				if (isset($bgList[$value])) {
                    array_push($baris[$bulan_request][$bgList[$value]][$key2]['po_batch_number'],($value3['po_number'] != 'PO BARU' ? $value3 : 'PO BARU' ));
                    array_push($baris[$bulan_request][$bgList[$value]][$key2]['qty'], number_format($value3['qty'],'0',',','.'));
                }else{
                    array_push($baris[$bulan_request][$value][$key2]['po_batch_number'],($value3['po_number'] != 'PO BARU' ? $value3 : 'PO BARU' ));
                    array_push($baris[$bulan_request][$value][$key2]['qty'], number_format($value3['qty'],'0',',','.'));
                }

				if (!isset($barisPerBulan[$bulan_request])) {
					$barisPerBulan[$bulan_request] = 0; 
				}

				$barisPerBulan[$bulan_request] += count($request_barang_detail_id_b);
				if (isset($bgList[$value])) {
                    $newList = $list;
                    $new_jumlah_baris = count($request_barang_detail_id_b);
					
					// echo $new_jumlah_baris;
					if (isset($brs[$bulan_request][$bgList[$value]])) {
						$old = $brs[$bulan_request][$bgList[$value]];
						$brs[$bulan_request][$bgList[$value]] = array(
							'jumlah_baris' => $old['jumlah_baris'] + $new_jumlah_baris,
							'nama' => $old['nama'],
							'barang_id' => $bgList[$value],
							'total_request' => $total_request[$bgList[$value]][$bulan_request],
							'warna' => array_merge($newList, $old['warna']),	
						);
					}else{
						$brs[$bulan_request][$bgList[$value]] = array(
							'jumlah_baris' => $new_jumlah_baris,
							'nama' => trim($bgListName[$value]),
							'barang_id' => $value,
							'total_request' => $total_request[$bgList[$value]][$bulan_request],
							'warna' => $newList,	
						);
					}

					
				}else{

					if (isset($brs[$bulan_request][$value])) {
						$old = $brs[$bulan_request][$value];

						$brs[$bulan_request][$value] = array(
							'jumlah_baris' => $old['jumlah_baris'] + count($request_barang_detail_id_b),
							'nama' => trim($nama_barang[$key]),
							'barang_id' => $value,
							'total_request' => $total_request[$value][$bulan_request],
							'warna' => array_merge($list, $old['warna']),	
						);
					}else{
						$brs[$bulan_request][$value] = array(
							'jumlah_baris' => count($request_barang_detail_id_b),
							'nama' => trim($nama_barang[$key]),
							'barang_id' => $value,
							'total_request' => $total_request[$value][$bulan_request],
							'warna' => $list,	
						);
					}

					if (is_posisi_id() == 1) {
						if ($value==4) {
							// print_r($brs[$bulan_request][$value]);
							// echo count($request_barang_detail_id_b);
						}
						// echo '<hr/>';
					}

					// if (is_posisi_id() == 1) {
					// 	echo '<hr/>';
					// }
				}

				// if (is_posisi_id()==1) {
				// 	echo $row->nama_barang;
				// 	print_r($brs);
				// 	echo '<hr/>';
				// }

			}
		}

							
		// foreach ($baris as $periode => $isi_periode) {
		// 	echo $key.'<br/>';
		// 	foreach ($isi_periode as $barang_id_list => $value) {
		// 		echo $barang_id_list.'<br/';
		// 		print_r($value);
		// 		echo '<hr/>';
		// 	}
		// }

		$mode = 1;
		$max_baris = 35;
		foreach ($barisPerBulan as $nama_bulan => $jml_baris) {
			if($jml_baris > 75){
				$mode=3;
			}else if($jml_baris > 35){
				$mode=2;
			}
		}
		
		if (is_posisi_id() == 1) {
			#
			// echo $mode;
			// print_r($keterangan_qty);
		}
		if ($mode == 1) {
			// include_once 'generate_request_mode_1_pdf.php';
			include_once 'generate_request_mode_4v2_pdf.php';
		}elseif ($mode == 2){
			// include_once 'generate_request_mode_2_pdf.php';
			if (is_posisi_id() ==1) {
			// echo $mode;
				include_once 'generate_request_mode_4v2_pdf.php';
			// include_once 'generate_request_mode_1_pdf.php';
			}else{
				include_once 'generate_request_mode_4v2_pdf.php';
			}
		}elseif ($mode == 3){
			if (is_posisi_id() ==1) {
				if ($data_view != 2) {
					include_once 'generate_request_mode_5_pdf.php';
				}else{
					include_once 'generate_request_mode_4v3_pdf.php';
				}
			}else{
				include_once 'generate_request_mode_4v3_pdf.php';
				// include_once 'generate_request_mode_4v3_pdf.php';
				// include_once 'generate_request_mode_4v2_pdf.php';
			}
		}
					