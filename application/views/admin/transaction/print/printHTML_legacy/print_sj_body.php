<?

	$sj_body = "<table width='100%'   style='font-size:2.75mm; border-collapse: collapse;' cellspacing='0'>
	<tr>
		<td style='$bB solid #ccc; text-align:center; width:6mm;'>NO</td>
		<td style='$bB text-align:right; width:12mm'>JML</td>
		<td style='$bB text-align:center; '>SAT</td>
		<td style='$bB text-align:center; '>ROLL</td>
		<td style='$bB width:6mm'></td>
		<td style='$bB'>BARANG</td>
		<td style='$bB'>".($is_print_harga ? "HARGA" : "")."</td>
		<td style='$bB width:5mm'></td>

	</tr>";
	
	$total = 0; $total_qty = 0; $total_roll = 0; 
	// print_r($penjualan_print);
	$idx = 1;
	
	$TOTALROW = 12;
	$PAGEDETAIL++;
	$TOTALPAGE++;

	foreach ($penjualan_print as $row) {
			$total += $row->qty * $row->harga_jual;
			$total_qty += $row->qty;
			$total_roll += $row->jumlah_roll;

			$sj_body .= "<tr>
			<td style='height:5mm; text-align:center;'>$idx</td>
			<td style='text-align:right;'>".str_replace(',00','',number_format($row->qty,'2',',','.'))."</td>
			<td style='text-align:center;'>".strtoupper($row->nama_satuan)."</td>
			<td style='text-align:center;'>".($row->tipe_qty != 3 ? $row->jumlah_roll : '')."</td>
			<td></td>
			<td>".strtoupper($row->nama_barang)."</td>
			<td style='font-size:3mm'>".($is_print_harga ? number_format($row->harga_jual,'0',',','.') : "")."</td>
			<td style='width:5mm'></td>

		</tr>";
		$idx++;
		}
	
		$sj_body .= "<tr>
			<td style='$bdT $bdL $bdB text-align:center'>TOTAL</td>
			<td style='$bdT $bdB text-align:right;'>".str_replace(',00','',number_format($total_qty,'2',',','.'))."</td>
			<td style='$bdT $bdB text-align:center;'>YARD</td>
			<td style='$bdT $bdR $bdB text-align:center'>".number_format($total_roll,'0',',','.')."</td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
	</table>";


?>