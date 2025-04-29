<?

	
	$style='vertical-align:top; border-top:1px solid black; font-weight:bold; ';
    $packing_list_start = "<table width='100%' style='font-size:2.7mm; border-collapse: collapse; border-bottom:1px solid black;' cellspacing='0'>";
    $packing_list_title = "
    <tr>
        <td style='$style width:32mm; text-align:left'>KODE</td>
        <td style='$style width:32mm; text-align:left'>WARNA</td>
        <td style='$style width:8mm; text-align:center'>UNIT</td>
        <td style='$style width:8mm; text-align:right'>ROL</td>
        <td style='$style $bR solid black; width:17mm;text-align:right; padding-right:3mm; width:12mm'>TOTAL</td>
        <td style='$style text-align:center; width:120mm' colspan='10'>DETAIL</td>
    </tr>";

    $packing_list_body = [];
    $st = "height:3mm; padding:0mm;overflow:hidden;";

    $packing_list_body[0] = "";
    foreach ($data_penjualan_detail_group as $row) {
        $nama_warna = explode('??',$row->nama_warna);
        $warna_id = explode('??',$row->warna_id);
        $total_qty = explode('??', $row->total_qty);
        $total_roll = explode('??', $row->total_roll);
        $data_qtys = explode('??', $row->qty);
        $data_rolls = explode('??', $row->jumlah_roll);
        

        $row_detail_print[$row->barang_id] = array(
            "nama_barang" => $row->nama_barang,
            "data_warna" => array()

        );
        foreach ($nama_warna as $key => $value) {
            $packing_list_body[0] .= "<tr>";
            if ($key == 0) {
                $packing_list_body[0] .= "<td rowspan = '".count($nama_warna)."' style='$style padding:0mm; height:'".(count($nama_warna) * 4 * ceil($total_roll[$key]/10))."mm'>".strtoupper($row->nama_barang)."</td>";
            }

            $data_qty = explode(',', $data_qtys[$key]);
            $data_roll = explode(',', $data_rolls[$key]);
            $q_array = [];
            $q_100 = [];
            $q_pecah = [];
            $q_row = 0;
            foreach ($data_qty as $k => $v) {
                $x = $data_roll[$k];
                for ($y=0; $y < $x ; $y++) {
                    if ($v == 100) {
                        array_push($q_100, $v);
                    }else{
                        array_push($q_pecah, $v);
                    }
                }
            }
            
            // rsort($q_pecah);
            $q_array = array_merge($q_100, $q_pecah);
            



            $packing_list = "";
            $q_row = ceil($total_roll[$key]/10);

            if (is_posisi_id()==1) {
                // echo $total_roll[$key].' '.$q_row.'<br/>';
                // print_r($q_array);
                // echo str_replace(',00','',number_format($total_qty[$key],'2',',','.'));
            }

            array_push($row_detail_print[$row->barang_id]["data_warna"], array(
                "nama_warna" => $nama_warna[$key],
                "nama_satuan" => $row->nama_satuan,
                "total_roll" => $total_roll[$key],
                "total_qty" => str_replace(',00','',number_format($total_qty[$key],'2',',','.')),
                "total_row" => $q_row,
                "packing_list" => array()
            ));

            for ($aa=0; $aa < $q_row ; $aa++) {
                $row_render = ""; 
                // if ($aa % 5 ==0 && $aa != 0) {
                // 	$row_render .=  "<td style='height:1mm; ' colspan='10'></td>";
                // 	array_push($row_detail_print[$row->barang_id]["data_warna"][$key]["packing_list"], $row_render);
                // 	$row_render = ""; 
                // }
                for ($z=0; $z < 10 ; $z++) {
                    $border = 0;
                    $i_arr = ($aa*10)+$z;
                    if ($z == 0) {
                        $packing_list .= "<tr>";
                    }
                    $packing_list .= "<td style='height:4mm; width:10.5mm; text-align:right; '>
                            ".(isset($q_array[$i_arr]) ? str_replace('.00', '', $q_array[$i_arr]) : '')."
                        </td>";
                    if ($z == 9 ) {
                        $packing_list .= "</tr>";
                    }


                    $row_render .=  "<td style='$st width:10.5mm; text-align:right; ".($aa==0 ? ($key == 0 ? $bT : $bdT) : "")." '>
                        ".(isset($q_array[$i_arr]) ? str_replace('.00', '', $q_array[$i_arr]) : '')."
                    </td>";
                }

                array_push($row_detail_print[$row->barang_id]["data_warna"][$key]["packing_list"], $row_render);
            }

            

            // $packing_list_body[0] .= "<td style=' $style font-size:2.8mm'>".strtoupper($nama_warna[$key])."</td>
            //     <td style='$style text-align:center'>".strtoupper($row->nama_satuan)."</td>
            //     <td style='$style text-align:center'>".$total_roll[$key]."</td>
            //     <td style='$style border-right:1px solid black; text-align:center'>
            //         ".str_replace(',00','',number_format($total_qty[$key],'2',',','.'))."
            //     </td>
            //     <td style=' $style padding:0mm'>
            //         <table> $packing_list </table>
            //     </td>
            // </tr>";
        }
    }

    // $table_detail .= $packing_list_body;
    // $table_detail .="</table>";

    $pgdtl = 0;
    $table_row = array();
    $max_row = ($is_packing_list  ? 15 : 20);
    
    $packing_list_body[$pgdtl] = "";
    $row_idx = 0;
    $nama_barang_before = "";
    foreach ($row_detail_print as $row) {
        // echo $row['nama_barang'];
        foreach ($row['data_warna'] as $k => $vl) {
            $row_after = $row_idx + $vl['total_row'];
            $style = "";
            $sisa_row = count($vl['packing_list']);
            foreach ($vl['packing_list'] as $k2 => $vl2) {
                $row_idx++;
                if ($k2 == 0) {
                    $nama_print = ($nama_barang_before != $row['nama_barang'] ? $row['nama_barang'] : "");
                    $nama_barang_before = $row['nama_barang'];
                    $packing_list_body[$pgdtl] .= "<tr>
                        <td 
                        style='".($nama_print != '' ? $bT : '')." height: 3mm; font-size:2.3mm;'>".
                        strtoupper($nama_print).
                        "</td>
                        <td style='".($nama_print != '' ? $bT : $bdT)." $st font-size:2.3mm; '>".strtoupper($vl['nama_warna'])."</td>
                        <td style='".($nama_print != '' ? $bT : $bdT)." $st text-align:center;  font-size:2.3mm;'>".strtoupper($vl['nama_satuan'])."</td>
                        <td style='".($nama_print != '' ? $bT : $bdT)." $st text-align:center'>".strtoupper($vl['total_roll'])."</td>
                        <td style='".($nama_print != '' ? $bT : $bdT)." $bR $st text-align:right; padding-right:2.3mm'>".strtoupper($vl['total_qty'])."</td>
                        $vl2</tr>";
                }else{
                    $packing_list_body[$pgdtl] .= "<tr><td 
                        style='height: 3mm'></td>
                        <td style=''></td>
                        <td style=''></td>
                        <td style=''></td>
                        <td style='$bR'></td>
                        $vl2
                        </tr>";
                }

                if ($row_idx >= $max_row) {
                    $max_row = 20;
                    $PAGEDETAIL++;
                    $TOTALPAGE ++;
                    $pgdtl++;
                    $packing_list_body[$pgdtl] = "";
                    $row_idx = 0;
                }else if($row_idx !== 0 && $row_idx%5 == 0 && $k2 != (count($vl['packing_list']) - 1) ){
                    $packing_list_body[$pgdtl] .= "<tr><td 
                        style='$bR height:0.5mm; padding:0px' colspan='5'></td>
                        <td style='height:0.5mm; padding:0px' colspan='10'></td>
                        </tr>";
                }
                $sisa_row--;
            }
        }
    }


    // $table_detail .= $packing_list_body;
    // $table_detail .="</table>";


?>