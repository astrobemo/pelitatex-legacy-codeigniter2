<div id="kategoriContainer" style="display:flex; flex-direction:row">
	<div style="padding-right: 10px;">
		Filter :
		<a href="<?=base_url().is_setting_link('report/po_penjualan_report')?>?view=1" class="btn btn-sm <?=($view == 1 ? 'btn-success'  : 'default' )?> ">GROUP BY Customer <?=($view == 1? "<i class='fa fa-check'></i>" : "")?></a>
	</div>
	<div style="padding-right: 10px;">
		<a href="<?=base_url().is_setting_link('report/po_penjualan_report')?>?view=2" class="btn btn-sm <?=($view == 2 ? 'btn-success'  : 'default' )?> ">GROUP BY Barang <?=($view == 2? "<i class='fa fa-check'></i>" : "")?></a>
	</div>
	<div style="padding-right: 10px;">
		<a href="<?=base_url().is_setting_link('report/po_penjualan_report')?>?view=3" class="btn btn-sm <?=($view == 3 ? 'btn-success'  : 'default' )?> ">GROUP BY PO <?=($view == 3? "<i class='fa fa-check'></i>" : "")?></a>
	</div>
</div>

