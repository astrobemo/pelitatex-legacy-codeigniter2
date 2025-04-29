<?

	$table_barang = "<table width='100%' style='font-size:3mm; border-collapse: collapse;' cellspacing='0'>
	<tr>
		<td style='$bB solid #ccc; text-align:center; width:6mm;'>NO</td>
		<td style='$bB text-align:right; width:12mm'>JML</td>
		<td style='$bB text-align:center; '>SAT</td>
		<td style='$bB text-align:center; '>ROLL</td>
		<td style='$bB width:6mm'></td>
		<td style='$bB'>BARANG</td>
		<td style='$bB'>HARGA</td>
		<td style='$bB text-align:center; '>TOTAL</td>
		<td style='$bB width:5mm'></td>

	</tr>";
	
	$total = 0; $total_qty = 0; $total_roll = 0; 
	// print_r($penjualan_print);
	$idx = 1;
	$TOTALPAGE++;


	foreach ($data_penjualan_detail_group as $row) {
		foreach (explode("??",$row->jumlah_roll) as $key => $value) {
			$TOTALROW += ceil($row->jumlah_roll/10);
		}
	}
	foreach ($penjualan_print as $row) {
			$total += $row->qty * $row->harga_jual;
			$total_qty += $row->qty;
			$total_roll += $row->jumlah_roll;
			$TOTALBARANG++;
			$TOTALROW += ceil($row->jumlah_roll/10);


		$table_barang .= "<tr>
			<td style='height:5mm; text-align:center;'>$idx</td>
			<td style='text-align:right;'>".str_replace(',00','',number_format($row->qty,'2',',','.'))."</td>
			<td style='text-align:center;'>".strtoupper($row->nama_satuan)."</td>
			<td style='text-align:center;'>".($row->tipe_qty != 3 ? $row->jumlah_roll : '')."</td>
			<td></td>
			<td>".strtoupper($row->nama_barang)."</td>
			<td style='font-size:3mm'>".number_format($row->harga_jual,'0',',','.')."</td>
			<td style='font-size:3mm; text-align:right; $pR2'>".number_format($row->qty*$row->harga_jual,'0',',','.')."</td>
			<td style='width:5mm'></td>

		</tr>";
		$idx++;
	}
	
	$table_barang .= "<tr>
		<td style='$bdT $bdL $bdB text-align:center'>TOTAL</td>
		<td style='$bdT $bdB text-align:right;'>".str_replace(',00','',number_format($total_qty,'2',',','.'))."</td>
		<td style='$bdT $bdB text-align:center;'>YARD</td>
		<td style='$bdT $bdR $bdB text-align:center; font-size:3mm'>".$total_roll."</td>
		<td></td>
		<td></td>
		<td style=' $bdT $bdL '>TOTAL *</td>
		<td style='$bdT $pR1 text-align:right; font-size:3mm'>".number_format($total,'0',',','.')."</td>
		<td style='$bdT $bdR width:5.1mm'></td>

	</tr>";

	$total_bayar = 0;
	foreach ($data_pembayaran as $row) {
		$total_bayar += $row->amount;
		$table_barang .= "<tr>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td style='$bdL '>".$row->nama_bayar."</td>
			<td style='$pR1 text-align:right;height:4mm; font-size:3mm'>".number_format($row->amount,'0',',','.')."</td>
			<td style='$bdR width:5.1mm'></td>

		</tr>";
	}

	$table_barang .= "<tr>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td style='$bdT $bdL $bdB '>KEMBALI</td>
		<td style='$bdT $bdB $pR1 height:5mm; text-align:right; font-size:3mm'>".number_format($total_bayar - $total,'0',',','.')."</td>
		<td style='$bdT $bdR $bdB width:5mm'></td>

	</tr>
	<tr>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td style='$pR1 font-size:2.5mm; text-align:right;'><span>*harga sudah termasuk ppn</span></td>
			<td style='width:5mm'></td>
	</tr>
	</table>";

	if ($TOTALBARANG > 9 && ($print_type != 1)) {
		$TOTALPAGE++;
	}


?>