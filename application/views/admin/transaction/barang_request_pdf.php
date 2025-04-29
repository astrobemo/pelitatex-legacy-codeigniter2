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

		$nama_jual = ''; $nama_beli = '';
		foreach ($data_barang as $row) {
			$nama_jual = $row->nama_jual;
			$nama_beli = $row->nama;

		}

		$terkirim = array();
		$isTercapai = array();
		$classStatus = array();
		$terkirim_by_warna = array();
		foreach ($barang_terkirim as $row) {
			$bReq = explode(",", $row->bulan_request);
			$qReq = explode(",", $row->qty_request);
			$req = array_combine($bReq, $qReq);
			foreach($bReq as $key => $value){
				$terkirim[$row->barang_id][$row->warna_id][$value] = 0;
			}

			//===========barang masuk=============
			$tanggal_masuk = explode(',', $row->tanggal_masuk);
			$batchId = explode(",", $row->po_pembelian_batch_id);
			$qData = explode(",", $row->qty_data);
			
			// $start = (new Datetime($row->tanggal))->modify('first day of this month');
			// //=====karena date nya between jadi harus end nya next month====================
			// $end = (new Datetime($row->latest))->modify('first day of next month');
			
			// $interval = DateInterval::createFromDateString('1 month');
			// $period = new DatePeriod($start, $interval, $end);
			if (is_posisi_id()==1) {
				// print_r($terkirim);
				// echo "<hr/>";
				// print_r($tanggal_masuk);
				// print_r($qData);
				// echo "<hr/>";
			}
			for ($i=0; $i < count($tanggal_masuk) ; $i++) {
				$b_now = date('Y-m-01', strtotime($tanggal_masuk[$i]));
				if (strtotime($tanggal_masuk[$i]) < strtotime($bReq[0])) {
					$terkirim[$row->barang_id][$row->warna_id][$bReq[0]] += $qData[$i];
				}else if ($req[$b_now] < ($terkirim[$row->barang_id][$row->warna_id][$b_now] + $qData[$i]) ) {
					$b_next = date('Y-m-01', strtotime("+1 month", strtotime($b_now)));
					if (!isset($terkirim[$row->barang_id][$row->warna_id][$b_next])) {
						$terkirim[$row->barang_id][$row->warna_id][$b_next] = 0;
					}
					$terkirim[$row->barang_id][$row->warna_id][$b_next] += $terkirim[$row->barang_id][$row->warna_id][$b_now] + $qData[$i] -  $req[$b_now] ;
					$terkirim[$row->barang_id][$row->warna_id][$b_now] = $req[$b_now];
					$isTercapai[$row->barang_id][$row->warna_id][$b_now] = 1;
				}else{
					$terkirim[$row->barang_id][$row->warna_id][$b_now] += $qData[$i];
				}
			}
		}
		// print_r($terkirim_by_warna);

		foreach ($request_barang_qty_data as $row) {
			$qty_request[$row->warna_id][$row->bulan_request] = $row->qty;
			$isShown[$row->bulan_request][$row->barang_id][$row->warna_id] = 1;
		}

		foreach ($request_barang_status as $row) {
			$isFinished[$row->barang_id][$row->warna_id][$row->bln_request] = array('id'=>$row->id,'status'=>($row->username != '' ? true : false) );
		}

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
			
			foreach ($barang_id_get as $key => $value) {
				if (!isset($total_request[$value])) {
					$total_request[$value] = 0;
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
					$total_request[$value] += $qty_b[$key2];

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
				
				$request_per_warna = array();
				foreach ($warna_id_count as $key2 => $value2) {
					//========index key2 warna id============
					$idx2 = 0;
					$brs_warna = count($widList[$key2]) - 1;
					$tp = (isset($status_beda[$value][$key2][$bulan_request]) ? $status_beda[$value][$key2][$bulan_request] : 0  );
					
					$bln_up = date('Y-m-01', strtotime("+1 year", strtotime($bulan_request)));
					$classStatus[$value][$key2][$bln_up] = '';

					foreach ($widList[$key2] as $key3 => $value3) {
						$nama_warna_now = $value3['nama'];
						//$key2 == warna_id
						//$key3 = index 0...n
						$urg = ($value3['status_urgent'] == 1 ? 'color:red' : '' );
						$brdr = ($idx2 == $brs_warna ? 'border-bottom:1px solid #333' : '');

						if (isset($terkirim[$value][$key2][$bln_up])) {
							$trkrm = str_replace(",00","", number_format($terkirim[$value][$key2][$bln_up],2,",",".")) ;
						}

						$classStatusInit = 'status-cell';
						$classFinished = '';
						$request_barang_qty_id = $isFinished[$value][$key2][$bln_up]['id'];
						
						if ($classStatus[$value][$key2][$bln_up] == '') {
							if ($isFinished[$value][$key2][$bln_up]['status']) {
								$classStatus[$value][$key2][$bln_up] = 'finished ';
							}
							
							if ($tp == 1) {
								$classStatus[$value][$key2][$bln_up] .= 'revised';
								if ($urg != '') {
									$classStatus[$value][$key2][$bln_up] .='-urgent';
								}
							}elseif($urg != ''){
								$classStatus[$value][$key2][$bln_up] .= 'urgent';
							}
							// echo $nama_barang[$key].' '.$value3['nama'].' '.$tp;
							// var_dump($classStatus[$value][$key2][$bln_up]);
							// echo $classStatus[$value][$key2][$bln_up].'<br/>';
						}


						
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
					$trkrm = 0;

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
				array_push($baris[$bulan_request][$value][$key2]['po_batch_number'],($value3['po_number'] != 'PO BARU' ? $value3 : 'PO BARU' ));
				array_push($baris[$bulan_request][$value][$key2]['qty'], number_format($value3['qty'],'0',',','.'));
				if (!isset($barisPerBulan[$bulan_request])) {
					$barisPerBulan[$bulan_request] = 0; 
				}

				$barisPerBulan[$bulan_request] += count($request_barang_detail_id_b);
				$brs[$bulan_request][$value] = array(
					'jumlah_baris' => count($request_barang_detail_id_b),
					'nama' => trim($nama_barang[$key]),
					'total_request' => $total_request[$value],
					'warna' => $list,
					
				);

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
		
		if ($mode == 1) {
			include_once 'generate_request_mode_1_pdf.php';
		}elseif ($mode == 2){
			include_once 'generate_request_mode_2_pdf.php';
		}elseif ($mode == 3){
			include_once 'generate_request_mode_3_pdf.php';
		}
					