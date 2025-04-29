<?
	$alamat1 = substr(strtoupper(trim($alamat_keterangan)), 0,47);
	$alamat2 = substr(strtoupper(trim($alamat_keterangan)), 47);
	$last_1 = substr($alamat1, -1,1);
	$last_2 = substr($alamat2, 0,1);

	$positions = array();
	$pos = -1;
	while (($pos = strpos(trim($alamat_keterangan)," ", $pos+1 )) !== false) {
		$positions[] = $pos;
	}

	$max = 35;
	if ($last_1 != '' && $last_2 != '') {
		$posisi =array_filter(array_reverse($positions),
			function($value) use ($max) {
				return $value <= $max;
			});

		$posisi = array_values($posisi);

		$alamat1 = substr(strtoupper(trim($alamat_keterangan)), 0,$posisi[0]);
		$alamat2 = substr(strtoupper(trim($alamat_keterangan)), $posisi[0]);
	}

	$alamat_tujuan = $alamat_keterangan;
	foreach ($alamat_kirim as $row) {
		$alamat_tujuan = $row->alamat;
	}
	$length_alamat = 0;
	foreach ($alamat_kirim as $row) {
		$alamat_tujuan = $row->alamat;
		$length_alamat = strlen($alamat_tujuan);
	}

	$alamat_kirim1 = substr(strtoupper(trim($alamat_tujuan)), 0,47);
	$alamat_kirim2 = substr(strtoupper(trim($alamat_tujuan)), 47);
	$last_1 = substr($alamat_kirim1, -1,1);
	$last_2 = substr($alamat_kirim2, 0,1);

	$positions = array();
	$pos = -1;
	while (($pos = strpos(trim($alamat_tujuan)," ", $pos+1 )) !== false) {
		$positions[] = $pos;
	}

	$max = 35;
	if ($last_1 != '' && $last_2 != '') {
		$posisi =array_filter(array_reverse($positions),
			function($value) use ($max) {
				return $value <= $max;
			});

		$posisi = array_values($posisi);

		$alamat_kirim1 = substr(strtoupper(trim($alamat_tujuan)), 0,$posisi[0]);
		$alamat_kirim2 = substr(strtoupper(trim($alamat_tujuan)), $posisi[0]);
	}

	$sj_header = "<table width='100%' style='border-collapse: collapse;' cellspacing='0'>
		<tr>
			<td style='height:5mm'><b style='font-size:1.1em;'>SURAT JALAN [PJ02]</b></td>
			<td style='text-align:right'>".strtoupper($kota_toko.', ').strtoupper($tanggal_print)."</td>
		</tr>
		<tr>
			<td style='font-size:3mm'>
				<span style='font-size:4.5mm'>".strtoupper($nama_toko)."</span><br/>
				".strtoupper($alamat_toko)."<br/>
				".strtoupper($kota_toko)." | TELP : ".$telepon_toko."
			</td>
			<td style='text-align:right; font-size:3mm'>
				SJ : ".$no_surat_jalan." <br/>
				INVOICE : ".$no_faktur_lengkap."<br/>
				".($po_number != '' ? 'PO: '.$po_number : '')."
			</td>
		</tr>
	</table>";

	$p0 = " padding:0mm ;";
	$hg = "height:2mm ;";
	$sj_header .= "<table width='100%' style='border-collapse: collapse;' cellspacing='0'>
					<tr>
						<td style='$bT width:15mm; text-align:left; font-size:0.9em; $hg $p0'>KEPADA</td>
						<td style='$bT width:4mm; text-align:center; font-size:0.9em;$p0 '> : </td>
						<td style='$bT width:80mm; text-align:left; font-size:0.9em;$p0'>".strtoupper($nama_keterangan)."</td>

						<td style='$bT font-size:0.9em;  $hg $p0'>
							ALAMAT PENGIRIMAN :
						</td>
					</tr>
					<tr>
						<td style='text-align:left; font-size:0.9em; vertical-align:top;  $hg $p0'>ALAMAT</td>
						<td style='width:4mm; text-align:center; vertical-align:top;$p0'> : </td>
						<td style='text-align:left; width:70mm; font-size:0.9em; vertical-align:top;$p0'>".strtoupper($alamat1)."</td>

						<td style='text-align:left; font-size:0.9em; vertical-align:top;$p0'>".strtoupper($alamat_kirim1)."</td>
					</tr>
					<tr>
						<td style='$bB text-align:left; font-size:0.9em; vertical-align:top;  $hg $p0'></td>
						<td style='$bB width:4mm; text-align:center; vertical-align:top;$p0'></td>
						<td style='$bB  text-align:left; width:70mm; font-size:0.9em; vertical-align:top;$p0'>".strtoupper($alamat2)."</td>

						<td style='$bB text-align:left; font-size:0.9em; vertical-align:top;$p0'>".strtoupper($alamat_kirim2)."</td>
					</tr>
					
			</td>
			
		</tr>
	</table>";

	// <tr>
	// 					<td style='$bB text-align:left; font-size:0.9em; vertical-align:top;  $hg $p0'></td>
	// 					<td style='$bB width:4mm; text-align:center; vertical-align:top;$p0'></td>
	// 					<td style='$bB text-align:left; width:70mm; font-size:0.9em; vertical-align:top;$p0'></td>

	// 					<td style='$bB text-align:left; font-size:0.9em; vertical-align:top'></td>
	// 				</tr>

?>