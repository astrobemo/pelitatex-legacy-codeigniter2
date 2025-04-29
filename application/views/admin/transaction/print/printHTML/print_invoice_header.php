<?
	$table_header_jual = "<table width='100%' style='border-collapse: collapse;' cellspacing='0'>
		<tr>
			<td style='height:3mm; padding:0mm'><b style='font-size:4.5mm'>INVOICE [PJ01]</b></td>
			<td style='height:3mm; text-align:right; padding:0mm'>".strtoupper($kota_toko.', ').strtoupper($tanggal_print)."</td>
		</tr>
		<tr>
			<td style='font-size:3mm; padding:0mm '>
				<span style='font-size:5mm; '>".strtoupper($nama_toko)."</span><br/>
				".strtoupper($alamat_toko)."<br/>
				".strtoupper($kota_toko)." | TELP : ".$telepon_toko."<br/>
				NPWP : ".strtoupper($npwp_toko)."<br/>
			</td>
			<td style='text-align:right; font-size:3mm; padding:0mm'>
				KEPADA YTH.
				<br/>".strtoupper($nama_keterangan)."
				<br/>".strtoupper($alamat1)."
				<br/>".trim(strtoupper($alamat2))."</td>
		</tr>
		<tr>
			<td style='border-top:1px solid #ccc; border-bottom:1px solid #ccc;'>".($po_number != '' ? 'Ket : '.$po_number : '')."</td>
			<td style='text-align:right; border-top:1px solid #ccc; border-bottom:1px solid #ccc;'>INVOICE NO : ".$no_faktur_lengkap."</td>
		</tr>
	</table>";

?>