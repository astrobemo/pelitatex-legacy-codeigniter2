<?

	$packing_list_header = "<table width='100%' style='border-collapse: collapse;' cellspacing='0'>
		<tr>
			<td style='height:5mm'><b style='font-size:1.1em;'>PACKING LIST [PJ03]</b></td>
			<td rowspan='2'>
				<table>
					<tr>
						<td style='text-align:right'>NO</td>
						<td style='text-align:center'> : </td>
						<td style='text-align:left;font-size:0.9em'>$no_packing_list</td>
					</tr>
					<tr>
						<td style='text-align:right'>SURAT JALAN</td>
						<td style='text-align:center'> : </td>
						<td style='text-align:left; font-size:0.9em'>$no_surat_jalan</td>
					</tr>
				</table>
			</td>
			<td style='text-align:right'>".strtoupper($kota_toko.', ').strtoupper($tanggal_print)."</td>	
		</tr>
		<tr>
			<td style='font-size:4.5mm'>".strtoupper($nama_toko)."</td>
			<td style='text-align:right;font-size:0.9em'>
				KEPADA YTH.  <br/>
				".strtoupper($nama_keterangan)."
			</td>
		</tr>
	</table>";

?>