<?

	include_once 'print_faktur_header.php';
	

	$satuan_idx = 0;
	foreach ($data_penjualan_detail as $row) {
		$total+=$row->harga_jual*$row->qty;
		if (!isset($total_satuan['nama_satuan'])) {
			$total_satuan[$row->satuan_id]['qty'] = $row->qty;
			$total_satuan[$row->satuan_id]['roll'] = $row->jumlah_roll;
			$total_satuan[$row->satuan_id]['nama_satuan'] = $row->nama_satuan;
		}else{
			$total_satuan[$row->satuan_id]['qty'] += $row->qty;
			$total_satuan[$row->satuan_id]['roll'] = $row->jumlah_roll;
		}
		$pdf->Cell( 5, 5, 1, 1, 0, 'C' );
		$pdf->Cell( 15, 5, (float)$row->qty, 1, 0, 'C' );
		$pdf->Cell( 15, 5, $row->nama_satuan, 1, 0, 'C' );
		$pdf->Cell( 10, 5, $row->jumlah_roll, 1, 0, 'C' );
		$pdf->Cell( 2,5,'','TLB');
		$pdf->Cell( 23, 5, $row->jenis_barang, 'TRB', 0, 'L' );
		$pdf->Cell( 2,5,'','TLB');
		$pdf->Cell( 63, 5, $row->nama_barang, 'TRB', 0, 'L' );
		// $pdf->Cell( 10);
		$pdf->Cell( 30, 5, 'Rp '.number_format($row->harga_jual,'0',',','.'), 1, 0, 'C' );
		$pdf->Cell( 35, 5, 'Rp '.number_format($row->harga_jual*$row->qty,'0',',','.'), 1, 1, 'R' );
	}

	//=====================REKAP

	$pdf->SetFont( $font_name_bold, '', 10 );

	$b_idx = 1;$total_bayar = 0;
	foreach ($data_pembayaran as $row) {
		if ($row->amount > 0) {
			$dt[$b_idx] = $row;
			$b_idx++;
		}

		$total_bayar+=$row->amount;
	}

	$b_idx = 0; $kembali_baris = false; 
	foreach ($total_satuan as $key => $value) {
		$pdf->Cell( 5, 5, 'T', 1, 0, 'C' );
		$pdf->Cell( 15, 5, (float)$value['qty'], 1, 0, 'C' );
		$pdf->Cell( 15, 5, $value['nama_satuan'], 1, 0, 'C' );
		$pdf->Cell( 10, 5, $value['roll'], 1, 0, 'C' );

		if ($b_idx == 0) {
			$pdf->Cell( 90, 5, '', 0, 0, 'C' );
			$pdf->Cell( 30, 5, 'Total*', 0, 0, 'L' );
			$pdf->Cell( 35, 5, 'Rp '.number_format($total,'0',',','.'), 0, 1, 'R' );
		}else if (isset($dt[$b_idx])) {
			$pdf->Cell( 90, 5, '', 0, 0, 'L' );
			$pdf->Cell( 30, 5, $dt[$b_idx]->nama_bayar, 0, 0, 'L' );
			$pdf->Cell( 35, 5, 'Rp '.number_format($dt[$b_idx]->amount,'0',',','.'), 0, 1, 'R' );
		}elseif (!isset($dt[$b_idx]) && $kembali_baris == false) {
			$kembali_baris = true;
			$pdf->Cell( 135, 5, '', 0, 0, 'L' );
			$pdf->Cell( 30, 5, "KEMBALI", 0, 0, 'L' );
			$pdf->Cell( 35, 5, 'Rp '.number_format($total_bayar-$total,'0',',','.'), "T", 1, 'R' );
		}

		unset($dt[$b_idx]);

		$b_idx++;
	}

	if (count($dt) > 0) {
		foreach ($dt as $key => $value) {
			$pdf->Cell( 135, 5, '', 0, 0, 'L' );
			$pdf->Cell( 30, 5, $dt[$b_idx]->nama_bayar, 0, 0, 'L' );
			$pdf->Cell( 35, 5, 'Rp '.number_format($dt[$b_idx]->amount,'0',',','.'), 0, 1, 'R' );
		}

		$kembali_baris = true;
		$pdf->Cell( 135, 5, '', 0, 0, 'L' );
		$pdf->Cell( 30, 5, "KEMBALI", 0, 0, 'L' );
		$pdf->Cell( 35, 5, 'Rp '.number_format($total_bayar-$total,'0',',','.'), "T", 1, 'R' );
		
	}
	// $pdf->Cell( 0, 0, '', 1, 1, 'R' );
	

	$pdf->SetFont( $font_name, '', 8 );
	$pdf->Cell( 200, 4, '*harga sudah termasuk ppn', 0, 1, 'R' );


	$pdf->SetFont( $font_name, '', 9 );
	//baris 6
	$pdf->Cell( 5, 6, 'No', "TLB", 0, 'C' );
	$pdf->Cell( 36, 6, 'Kode', "TLB", 0, 'C' );
	$pdf->Cell( 26, 6, 'Warna', "TLB", 0, 'C' );
	$pdf->Cell( 10, 6, 'Sat.', "TLB", 0, 'C' );
	$pdf->Cell( 7, 6, 'Roll', "TLB", 0, 'C' );
	$pdf->Cell( 15, 6, 'Total', "TLB", 0, 'C' );
	$pdf->Cell( 101, 6, 'Detail', 1, 1, 'C' );
	// $pdf->Cell( 0, 0, '', 1, 1, 'R' );

	$no = 0;
	foreach ($data_penjualan_detail_group as $row) {
		

		$nama_warna = explode('??', $row->nama_warna);
		$data_qty = explode('??', $row->data_qty);
		$qty = explode('??', $row->qty);
		$jumlah_roll = explode('??', $row->jumlah_roll);
		$roll_qty = explode('??', $row->roll_qty);
		
		$data_all = explode('=??=', $row->data_all);
		

		$total = 0;
		$total_roll = 0;

		foreach ($nama_warna as $key => $value) {
			$no++;
			$qty_list = [];
			foreach (explode("--", $data_all[$key]) as $idx => $isi) {
				$br = explode("??", $isi);
				for ($j=0; $j < ($br[1] == 0 ? 1 : $br[1]) ; $j++) { 
					array_push($qty_list, $br[0]);
				}
			}

			// print_r($qty_list);

			$baris = ceil(count($qty_list)/10);
			//"Nylon Taslan High WP 2000mm"
			//"Loreng Digital Green"
			for ($h=0; $h < $baris; $h++) { 
				if ($h==0) {

					if (strlen($row->nama_barang) > 20 || strlen($value) > 17) {
						$pdf->SetFont( $font_name, '', 7 );
					}else{
						$pdf->SetFont( $font_name, '', 8 );
					}
					$pdf->Cell( 5, 4, $no, "LT", 0, 'C' );
					$pdf->Cell( 1, 4,'','LT');
					$pdf->Cell( 35, 4, strtoupper($row->nama_barang), "T", 0, 'L' );
					$pdf->Cell( 1, 4,'','LT');
					$pdf->Cell( 25, 4, strtoupper($value), "T", 0, 'L' );
					$pdf->Cell( 10, 4, strtoupper($row->nama_satuan), "LT", 0, 'C' );
					$pdf->Cell( 7, 4, $jumlah_roll[$key], "LT", 0, 'C' );
					$pdf->Cell( 14, 4, str_replace(",00", "", number_format($qty[$key],'2',',','.') ) , "LT", 0, 'R' );
					$pdf->Cell( 1, 4, "", "RT", 0, 'LT' );
				}else{
					$pdf->Cell( 5, 4, "", "L", 0, 'C' );
					$pdf->Cell( 1, 4,'','L');
					$pdf->Cell( 35, 4, "", 0, 0, 'L' );
					$pdf->Cell( 1, 4,'','L');
					$pdf->Cell( 25, 4, "", 0, 0, 'L' );
					$pdf->Cell( 10, 4, '', "L", 0, 'C' );
					$pdf->Cell( 7, 4, "", "L", 0, 'C' );
					$pdf->Cell( 14, 4, "", "L", 0, 'R' );
					$pdf->Cell( 1, 4, "", "R", 0, 'L' );
				}

				$pdf->SetFont( $font_name, '', 8.5 );

				for ($i=0; $i < 10 ; $i++) { 
					$border = ($h==0?"T" :0);
					$idx = ($h*10)+$i;
					$pdf->Cell( 10, 4, (isset($qty_list[$idx]) ? (float)$qty_list[$idx] : ''), $border, 0, 'R' );
					if ($i%9==0 && $i!=0) {
						$border = ($h==0?"RT" :"R");
						$pdf->Cell( 1, 4, "", $border, 1, 'R' );
					}
				}
			}
		}


	}

	//============================rekap=====================================
	$t_idx = 0;
	foreach ($total_satuan as $key => $value) {
		$border = ($t_idx == 0 ? "LTB" : "LB"  );
		$pdf->Cell( 41, 5, "", $border, 0, 'L' );
		$pdf->Cell( 25, 5, "TOTAL", $border, 0, 'R' );

		$border = ($t_idx == 0 ? "TB" : "B"  );
		$pdf->Cell( 1, 5, "", $border, 0, 'R' );

		$border = ($t_idx == 0 ? "LTB" : "LB"  );
		$pdf->Cell( 10, 5, strtoupper($value['nama_satuan']), $border, 0, 'C' );
		$pdf->Cell( 7, 5, $value['roll'], $border, 0, 'C' );
		$pdf->Cell( 14, 5, str_replace(",00", "", number_format($value['qty'],'2',',','.' )), $border, 0, 'R' );
		
		$border = ($t_idx == 0 ? "RTB" : "RB"  );
		$pdf->Cell( 1, 5, "", $border, 0, 'LT' );
		$pdf->Cell( 101, 5, "", $border, 1 );
		$t_idx++;
	}

	/**
	=====================================================================
	**/

	/**
	=====================================================================
	**/
	include_once 'print_faktur_footer.php';

	/**
	=====================================================================
	**/
	unset($baris);
	foreach ($data_penjualan as $row) {
		$nama_customer = $row->nama_keterangan;
		$no_faktur = $row->no_faktur;
		$no_faktur_lengkap = $row->no_faktur_lengkap;
		$tanggal = date('d F Y', strtotime($row->tanggal));
		$kota = $row->kota;
		$alamat = $row->alamat_keterangan;
		$bayar_dp = $row->bayar_dp;
		$penjualan_type_id = $row->penjualan_type_id;
		$po_number = $row->po_number;
		$customer_id = $row->customer_id;
	}

	foreach ($data_customer as $row) {
		$alamat_master_customer = $row->alamat.
		($row->blok !='' && $row->blok != '-' ? ' BLOK.'.$row->no: '').
		($row->no !='' && $row->no != '-' ? ' NO.'.$row->no: '').
		" RT.".$row->rt.' RW.'.$row->rw.
		($row->kelurahan !='' && $row->kelurahan != '-' ? ' Kel.'.$row->kelurahan: '').
		($row->kecamatan !='' && $row->kecamatan != '-' ? ' Kec.'.$row->kecamatan: '').
		($row->kota !='' && $row->kota != '-' ? ' '.$row->kota: '').
		($row->provinsi !='' && $row->provinsi != '-' ? ' '.$row->provinsi: '');

		if ($customer_id != 0 && $customer_id != '') {
			$alamat = $alamat_master_customer;
		}
	}

	foreach ($toko_data as $row) {
		$nama_toko = $row->nama;
		$alamat_toko = $row->alamat;
		$phone = $row->telepon;
		$fax = $row->fax;
		$npwp = $row->NPWP;
		$kota_toko = $row->kota;
	}

	$alamat_pengiriman = '';
	$no_surat_jalan = '';
	$tgl_kirim = '';

	foreach ($data_surat_jalan as $row) {
		$alamat_pengiriman = $row->alamat_log;
		$no_surat_jalan = date('dmy', strtotime($row->tanggal)).'-'.str_pad($row->no, '5', '0', STR_PAD_LEFT);
		$tgl_kirim = date("d F Y", strtotime($row->tanggal)); 
	}


	$pdf->setXY(0,6);
	$pdf->cMargin = 0;
	$pdf->AddPage();
	$pdf->SetMargins(3,0,3);
	$pdf->SetTextColor( 0,0,0 );

	$pdf->AddFont('calibriL','','calibriL.php');
	$pdf->AddFont('calibri','','calibri.php');
	$pdf->AddFont('calibriLI','','calibriLI.php');

	$font_name = 'calibriL';
	$font_name_bold = 'calibri';
	$font_name_italic = 'calibriLI';

	// $font_name = 'Arial';
	// $font_name_bold = 'Arial';
	// $font_name_italic = 'Arial';

	$pdf->setY(10.00125);
	$pdf->SetFont( "ARIAL", 'BU', 12 );

	$pdf->Ln(-5);
	// $pdf->Cell( 10 );
	// baris 1
	$pdf->Text( 3, 8.1, strtoupper($nama_toko));
	$pdf->Text( 82, 8.1, "SURAT JALAN");
	$pdf->Cell( 200, 3.8, $kota_toko.",".strtoupper($tgl_kirim), 0, 1, 'R' );

	$pdf->SetFont( $font_name, '', 11 );
	//baris 2
	// $pdf->Text( 137.5, 13, "NO.INVOICE:".$no_faktur);
	$pdf->Text( 3, 12, strtoupper($alamat_toko));
	$pdf->Text( 82, 12, "NO:SJ".$no_surat_jalan);
	$pdf->Cell( 200, 4, "NO.INVOICE:".$no_faktur, 0, 1, 'R' );
	
	//baris 3
	$pdf->Cell( 100, 4, strtoupper($kota_toko).", INDONESIA, 40181" , 0, 0, 'L' );
	$pdf->Cell( 100, 4, ($po_number != '' ? "NO PO : ".$po_number : '') , 0, 1, 'R' );
	
	$pdf->Line(3, 19, 203, 19);
	$pdf->Ln();

	$pdf->Cell( 16, 4, "Kepada :" , 0, 0, 'L' );
	$pdf->Cell( 85, 4, $nama_customer , 0, 0, 'L' );
	$pdf->Cell( 95, 4, "ALAMAT PENGIRIMAN | VIA:" , 0, 1, 'L' );

	$tempY = $pdf->getY();
	$pdf->Cell( 16, 4, "Alamat :" , 0, 0, 'L' );
	$pdf->setXY(18.5,$tempY);
	$pdf->Multicell(80,4, $alamat1.' '.$alamat2.' '.$alamat3  );
	$pdf->setXY(105, $tempY); 
	$pdf->Multicell(96,4, $alamat_pengiriman);

	$pdf->Rect(17,$tempY,85,13);
	$pdf->Rect(104,$tempY,99,13);

	$pdf->Ln(8);
	$pdf->SetFont( $font_name, '', 10 );
	//baris 6
	$pdf->Cell( 10, 6, 'NO', "TLB", 0, 'C' );
	$pdf->Cell( 15, 6, 'Jumlah', "TLB", 0, 'C' );
	$pdf->Cell( 15, 6, 'Satuan', "TLB", 0, 'C' );
	$pdf->Cell( 10, 6, 'Roll', "TLB", 0, 'C' );
	$pdf->Cell( 25, 6, 'Nama', "TLB", 0, 'C' );
	$pdf->Cell( 65, 6, 'Kode', "TLB", 0, 'C' );
	$pdf->Cell( 60, 6, 'Harga', 1, 1, 'C' );
	// $pdf->Cell( 0, 0, '', 1, 1, 'R' );

	$idx =1; 
	$qty_total = array();
	$roll_total = array();
	foreach ($this->satuan_list_aktif as $row) {
		$nama_satuan[$row->id] = $row->nama;
	}
	foreach ($data_barang as $row) {
		$qty_total[$row->satuan_id] = (isset($qty_total[$row->satuan_id]) ? $qty_total[$row->satuan_id] + $row->qty : $row->qty);
		$roll_total[$row->satuan_id] = (isset($roll_total[$row->satuan_id]) ? $roll_total[$row->satuan_id] + $row->jumlah_roll : $row->jumlah_roll);
		$pdf->Cell( 10, 5, $idx, "L", 0, 'C' );
		$pdf->Cell( 15, 5, (float)$row->qty, "L", 0, 'C' );
		$pdf->Cell( 15, 5, $row->nama_satuan, "L", 0, 'C' );
		$pdf->Cell( 10, 5, $row->jumlah_roll, "L", 0, 'C' );
		$pdf->Cell( 2,5,'','L');
		$pdf->Cell( 23, 5, $row->jenis_barang, 0, 0, 'L' );
		$pdf->Cell( 2,5,'','L');
		$pdf->Cell( 63, 5, $row->nama_barang.' '.$row->nama_warna, 0, 0, 'L' );
		$pdf->Cell( 60, 5, 'Rp '.number_format($row->harga_jual,'0',',','.'), "LR", 1, 'C' );
		$idx++;
	}

	//==============================blank row==================================

	for ($i=$idx; $i <=7 ; $i++) { 
		$pdf->Cell( 10, 5, '', "L", 0, 'C' );
		$pdf->Cell( 15, 5, '', "L", 0, 'C' );
		$pdf->Cell( 15, 5, '', "L", 0, 'C' );
		$pdf->Cell( 10, 5, '', "L", 0, 'C' );
		$pdf->Cell( 2,5,'','L');
		$pdf->Cell( 23, 5, '', 0, 0, 'L' );
		$pdf->Cell( 2,5,'','L');
		$pdf->Cell( 63, 5, '', 0, 0, 'L' );
		// $pdf->Cell( 10);
		$pdf->Cell( 60, 5, '', "LR", 1, 'C' );
	}
	//=====================REKAP===============
	$posY = $pdf->getY();
	$pdf->Line(3, $posY, 203, $posY);

	$pdf->SetFont( $font_name, '', 10 );
	foreach ($qty_total as $key => $value) {
		$pdf->Cell( 10, 5, 'T', "LB", 0, 'C' );
		$pdf->Cell( 15, 5, (float)$value, "LB", 0, 'C' );
		$pdf->Cell( 15, 5, $nama_satuan[$key], "LB", 0, 'C' );
		$pdf->Cell( 10, 5, $roll_total[$key], "LRB", 1, 'C' );
	}
	

	// $pdf->Ln(20);
	$pdf->SetFont( $font_name, '', 11 );

	//===========================================================
	$pdf->Text( 10, 120, 'Telah Diterima');
	$pdf->Text( 10, 125, 'Tanggal');
	$pdf->Text( 60, 125, 'Dikeluarkan');
	$pdf->Text( 130, 125, 'Checker');
	$pdf->Text( 170, 125, 'Pengirim');

	//=============================================================

	/**
	===========================packing list========================
	**/

	$pdf->AddPage();
	$pdf->cMargin = 0;
	$pdf->SetMargins(3,0,3);
	$pdf->SetTextColor( 0,0,0 );

	$pdf->AddFont('calibriL','','calibriL.php');
	$pdf->AddFont('calibri','','calibri.php');
	$pdf->AddFont('calibriLI','','calibriLI.php');

	$font_name = 'calibriL';
	$font_name_bold = 'calibri';
	$font_name_italic = 'calibriLI';

	
	$pdf->SetFont( "ARIAL", 'BU', 12 );

	$pdf->setY(6.25);
	$pdf->Text( 3, 8.1, strtoupper($nama_toko));
	$pdf->Text( 78, 8.1, "PACKING LIST");
	$pdf->Cell( 200, 3.8, $kota_toko.",".$tgl_kirim, 0, 1, 'R' );

	$pdf->SetFont( $font_name, '', 11 );
	//baris 2
	$pdf->Cell( 200, 4, "Kepada Yth,", 0, 1, 'R' );
	$pdf->Text( 78, 13, "NO:PL".$no_surat_jalan);
	
	//baris 3
	$pdf->Cell( 200, 4, $nama_customer , 0, 1, 'R' );
	$pdf->Text( 78, 17, "SJ:SJ".$no_surat_jalan);

	$pdf->Line(3, 19, 203, 19);
	$pdf->Ln();

	$pdf->SetFont( $font_name, '', 10 );
	//baris 6
	$pdf->Cell( 5, 6, 'NO', "TLB", 0, 'C' );
	$pdf->Cell( 57, 6, 'Kode', "TLB", 0, 'C' );
	$pdf->Cell( 43, 6, 'Warna', "TLB", 0, 'C' );
	$pdf->Cell( 10, 6, 'Sat', "TLB", 0, 'C' );
	$pdf->Cell( 10, 6, 'Roll', "TLB", 0, 'C' );
	$pdf->Cell( 19, 6, 'Total', "TLB", 0, 'C' );
	$pdf->Cell( 56, 6, 'Detail', 1, 1, 'C' );
	// $pdf->Cell( 0, 0, '', 1, 1, 'R' );

	$idx = 0;
	foreach ($data_barang_qty as $row) {
		$baris[$idx] = ceil($row->jumlah_roll_count/5);
		$brg[$idx] = $row->nama_barang;
		$warna[$idx] = $row->nama_warna;
		$qty_data[$idx] = explode('||', $row->qty);
		$roll_data[$idx] = explode('||', $row->jumlah_roll);
		$satuan[$idx] = $row->nama_satuan;
		$count_roll[$idx] = $row->jumlah_roll_count;
		$qty_all[$idx] = $row->qty_all;
		$idx++;

	}

	for ($z=0; $z < $idx; $z++) {
		unset($set_qty);
		$m=0;
		foreach ($qty_data[$z] as $key => $value) {
			for ($i=0; $i < $roll_data[$z][$key] ; $i++) { 
				$set_qty[$m] = $value;
				$m++;
			}
		}
		for ($h=0; $h < $baris[$z] ; $h++) { 
			if ($h==0) {
				$pdf->Cell( 5, 5, $z+1, "LT", 0, 'C' );
				$pdf->Cell( 1,5,'','LT');
				$pdf->Cell( 56, 5, $brg[$z], "T", 0, 'L' );
				$pdf->Cell( 1,5,'','LT');
				$pdf->Cell( 42, 5, $warna[$z], "T", 0, 'L' );
				$pdf->Cell( 10, 5, $satuan[$z], "LT", 0, 'C' );
				$pdf->Cell( 10, 5, $count_roll[$z], "LT", 0, 'C' );
				$pdf->Cell( 18, 5, (float)$qty_all[$z], "LT", 0, 'R' );
				$pdf->Cell( 1, 5, "", "RT", 0, 'LT' );
			}else{
				$pdf->Cell( 5, 5, "", "L", 0, 'C' );
				$pdf->Cell( 1,5,'','L');
				$pdf->Cell( 56, 5, "", 0, 0, 'L' );
				$pdf->Cell( 1,5,'','L');
				$pdf->Cell( 42, 5, "", 0, 0, 'L' );
				$pdf->Cell( 10, 5, '', "L", 0, 'C' );
				$pdf->Cell( 10, 5, "", "L", 0, 'C' );
				$pdf->Cell( 18, 5, "", "L", 0, 'R' );
				$pdf->Cell( 1, 5, "", "R", 0, 'L' );
			}

			for ($i=0; $i <5 ; $i++) { 
				$border = ($h==0?"T" :0);
				$pdf->Cell( 11, 5, (isset($set_qty[($h*5)+$i]) ? (float)$set_qty[($h*5)+$i] : '' ), $border, 0, 'R' );
				if ($i%4==0 && $i!=0) {
					$border = ($h==0?"RT" :"R");
					$pdf->Cell( 1, 5, "", $border, 1, 'R' );
				}
			}
		}
	}

	$posY = $pdf->getY();
	$pdf->Line(3, $posY, 203, $posY);

	//============================rekap=====================================
	
	foreach ($qty_total as $key => $value) {
		$pdf->Cell( 62, 5, "", "", 0, 'L' );
		$pdf->Cell( 43, 5, "TOTAL", "LB", 0, 'C' );
		$pdf->Cell( 10, 5, $nama_satuan[$key], "LB", 0, 'C' );
		$pdf->Cell( 10, 5, $roll_total[$key], "LB", 0, 'C' );
		$pdf->Cell( 18, 5, (float)$value, "LB", 0, 'R' );
		$pdf->Cell( 1, 5, "", "RB", 0 );
		$pdf->Cell( 56, 5, "", "", 1 );
	}

	/*$pdf->Cell( 62, 5, "", "LTB", 0, 'L' );
	$pdf->Cell( 43, 5, "TOTAL", "LTB", 0, 'R' );
	$pdf->Cell( 10, 5, 'YARD', "LTB", 0, 'C' );
	$pdf->Cell( 10, 5, 20, "LTB", 0, 'C' );
	$pdf->Cell( 18, 5, "4,000", "LTB", 0, 'R' );
	$pdf->Cell( 1, 5, "", "RTB", 0, 'LT' );
	$pdf->Cell( 56, 5, "", "RTB", 1 );*/
	
	

	// $pdf->Cell( 60, 5, 'Rp '.number_format(20250,'0',',','.'), "LR", 1, 'C' );

	// $pdf->Ln(20);
	
	//=============================================================

	include_once 'print_faktur_closing.php';



?>