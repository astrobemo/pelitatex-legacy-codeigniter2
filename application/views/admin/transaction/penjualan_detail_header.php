                        <table style='width:100%' id="table-header" >
							<tr>
								<td style="padding-right:10px">
                                    <div id="data-header">
                                        <table>
                                                <?if ($penjualan_id != '' && $status_aktif != -1) { ?>
                                                    <tr>
                                                        <td colspan='3'>
                                                            <?if ($status == 0) { ?>
                                                                <button href="#portlet-config-pin" data-toggle='modal' class='btn btn-xs btn-pin'><i class='fa fa-key'></i> request open</button>
                                                            <?}elseif ($status != -1) { ?>
                                                                <?if (is_posisi_id() != 6 ) { ?>
                                                                    <button onclick="poEvt('#po_penjualan_edit_select', '#inputPOEdit')" href="#portlet-config-edit" data-toggle='modal' class='btn btn-xs '><i class='fa fa-edit'></i> edit</button>
                                                                <?}?>
                                                            <?}?>
    
                                                        </td>
                                                    </tr>
                                                <?}?>
                                            <tr hidden>
                                                <td>Status</td>
                                                <td class='padding-rl-5'> : </td>
                                                <td class='td-isi-bold'>
                                                    <?$iClass = "";
                                                    if ($status == -1 && $no_faktur_lengkap != '' && $status_aktif != -1 ) { $iClass = 'fa-ban' ?>
                                                        <span style='color:red'><b>BATAL</b></span>
                                                    <?}elseif ($status == 1 && $no_faktur_lengkap != '' && $status_aktif != -1  ) { $iClass = 'fa-unlock' ?>
                                                        <span style='color:green'><b>OPEN</b></span>
                                                    <?}elseif ($status == 0 && $no_faktur_lengkap != '' && $status_aktif != -1  ) { $iClass = 'fa-lock' ?>
                                                        <span style='color:orange'><b>LOCKED</b></span>
                                                    <?}elseif ($status_aktif == -1) {
                                                        $iClass = 'fa-minus-circle';
                                                    }elseif ($penjualan_id !='') {
                                                        $iClass = 'fa-exclamation-circle';
                                                    }?>
                                                </td>
                                            </tr>
                                            <?if (is_posisi_id() <= 3 || is_posisi_id() == 6) {?>
                                                <td>Custom View</td>
                                                <td class='padding-rl-5'> : </td>
                                                <td class='td-isi-bold'>
                                                    <input type="checkbox" id="tdkn-checkbox" <?=($is_custom_view ? "checked" : "");?> >
                                                </td>
                                            <?}?>
                                            <tr>
                                                <td>Tipe</td>
                                                <td class='padding-rl-5'> : </td>
                                                <td class='td-isi-bold'>
                                                    <?=$tipe_penjualan;?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <!-- po_section -->
                                                <td>PO</td>
                                                <td class='padding-rl-5'> : </td>
                                                <td class='td-isi-bold'>
                                                    <?=$po_number;?>
                                                    <?if ($po_penjualan_id != '') {?>
                                                        <a class="btn btn-xs green" href="#portlet-config-poframe" data-toggle="modal">View</a>
                                                    <?}?>
                                                </td>
                                            </tr>
                                            <?if ($is_custom_view) {?>
                                                <tr>
                                                    <!-- po_section -->
                                                    <td>Ket</td>
                                                    <td class='padding-rl-5'> : </td>
                                                    <td class='td-isi-bold'>
                                                        <?=$keterangan;?>
                                                    </td>
                                                </tr>
                                            <?}?>
                                            <tr>
                                                <td>Tanggal</td>
                                                <td class='padding-rl-5'> : </td>
                                                <td class='td-isi-bold'><?=is_reverse_date($tanggal);?></td>
                                            </tr>
                                            <tr hidden <?=($penjualan_type_id != 2 ? 'hidden' : '' )?> >
                                                <td>Jatuh Tempo</td>
                                                <td class='padding-rl-5'> : </td>
                                                <td class='td-isi-bold'>
                                                    <?
                                                        // $dt = strtotime(' +60 days', strtotime($tanggal) );
                                                        // echo $get_jt = ($jatuh_tempo == '' ? date('Y-m-d', $dt) : $row->jatuh_tempo);
                                                    ?>
                                                    <?=$jatuh_tempo;?></td>
                                            </tr>
                                            <tr class='customer_section'>
                                                <td>Customer</td>
                                                <td class='padding-rl-5'> : </td>
                                                <td class='td-isi-bold'>
                                                    <?if ($penjualan_type_id == 3) { ?>
                                                        <?=$nama_keterangan;?> / <span class='alamat_keterangan'><?=$alamat_keterangan;?></span>
                                                    <?} else{
                                                        echo $nama_customer;
                                                        if ($npwp != '') {?>
                                                        <span class='label bg-red-thunderbird'>NPWP</span>
                                                        <?}
                                                    }?>
                                                </td>
                                            </tr>
                                            <tr class='customer_section'>
                                                <td>Alamat</td>
                                                <td class='padding-rl-5'> : </td>
                                                <td class='td-isi-bold'>
                                                    <span class='alamat'><?=$alamat_customer?></span>
                                                </td>
                                            </tr>
                                            <tr class='customer_section'>
                                                <td>FP</td>
                                                <td class='padding-rl-5'> : </td>
                                                <td class='td-isi-bold'>
                                                    <?if ($fp_status == 1) { ?>
                                                        <i class='fa fa-check'></i>
                                                    <?} else{
                                                        echo '';
                                                    }?>
                                                </td>
                                            </tr>
                                            <tr class='customer_section' <?=($fp_status == 1 ? '' : 'hidden')?> >
                                                <td>PPN</td>
                                                <td class='padding-rl-5'> : </td>
                                                <td class='td-isi-bold'>
                                                    <?=(float)$ppn?>%
                                                </td>
                                            </tr>
                                            
                                        </table>
                                    </div>
								</td>
								<td class='text-right' style='<?=$bg_info;?>;border-radius:5px; color:white; padding:10px;'>
									<div style="" class='<?//=$note_info;?>' sr>
										<i class='fa <?=$iClass;?>' style='font-size:2em'><?=($status_aktif == '-1' ? 'BATAL' : '');?></i>
										<span class='no-faktur-lengkap'> <?=$no_faktur_lengkap;?></span>
										<?=(is_posisi_id() == 1 ? "<br/>".($closed_date != '' ? date('d/m/Y H:i:s',strtotime($closed_date, strtotime("+7 hours"))) : '' ) : '' );?>
									</div>
								</td>
							</tr>
						</table>

						<hr/>

                        <?if (is_posisi_id() <= 3 && $penjualan_id != '') {?>
                            <script>
                                const customView = document.querySelector("#tdkn-checkbox");
                                customView.addEventListener("change", (e) => {
                                        const dialog = bootbox.dialog({
                                            message: '<p class="text-center mb-0"><i class="fa fa-spin fa-cog"></i> Changing View</p>',
                                            closeButton: false
                                        });
                                    fetch(baseurl+`transaction/penjualan_detail_update_view?is_custom_view=${Number(customView.checked)}&id=<?=$penjualan_id?>`)
                                    .then((response) => response.json())
                                    .then((data) => {
                                        if (data === "OK") {
                                            window.location.reload();
                                        }
                                    });
                                })
                            </script>
                        <?}?>

                        <div class="modal fade bs-modal-lg" id="portlet-config-poframe" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-body" style="text-align: center;">
                                        <iframe style="width:100%; height:600px" src="<?=base_url().is_setting_link('transaction/po_penjualan_detail');?>?view_type=2&id=<?=$po_penjualan_id;?>" ></iframe>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn default" data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                                <!-- /.modal-content -->
                            </div>
                            <!-- /.modal-dialog -->
                        </div>
