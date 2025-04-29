<style type="text/css">
	html{
		background: #ccc;
	}

	#media-print{
		width: 9.5in;
		height: 5.5in;
		background: white;
		padding: 1.5cm 2cm 0cm 2cm;
	}

	#media-print-body{
		width: 100%;
	}

	.seller-info{
		text-align: right;
		width: 100%;
	}

	.isi-nota{
		width: 100%;
	}

	#table-barang{
		width: 100%;
		border-spacing: 0px;
		border: 1.5px solid black;
	}

	#table-barang tr td{
		border: 1px solid black;
		text-align: center;
	}

	#table-ttd{
		border-spacing: 0px;
	}

	#table-ttd tr td{
		border: 1px solid black;
		width: 150px;
		text-align: center;
	}

	@media print {
	  	body * {
	    	visibility: hidden;
	  	}
	  	#media-print, #media-print * {
	    	visibility: visible;
	  	}
	  	#media-print {
	    	position: absolute;
	    	left: 0;
	    	top: 0;
	 	}
	}

</style>
<html>
	<body>
		<div id='media-print'>
			<div id="media-print-body">
				<p class="seller-info">
					From Seller : <br/>
					Kahatex : <br/>
					,telp : 
				</p>
				<hr/>

				<p class="isi-nota">
					<h4 class='invoice-info' style='text-align:center'>Invoice No. 077210/ Barang Jadi</h4>
					Tanggal Pembelian : 2017-12-04 <br/>
					Supplier Invoice Number : FPB051217-0004 <br/>
					<br/>
					<table id='table-barang'>
						<tr>
							<td>No</td>
							<td>Nama Barang</td>
							<td>Satuan</td>
							<td>Jumlah</td>
							<td>Rol</td>
							<td>Harga / Yard</td>
							<td>Total</td>
						</tr>
						<tr>
							<td>1</td>
							<td>9992 WR/CIRE Jade</td>
							<td>yard</td>
							<td>2229</td>
							<td>24</td>
							<td><?=number_format(10500,2,',','.');?></td>
							<td><?=number_format(23404500,2,',','.')?></td>
						</tr>
						<tr>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td>Subtotal </td>
							<td><?=number_format(23404500,2,',','.')?></td>
						</tr>
						<tr>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td>Total </td>
							<td><?=number_format(23404500,2,',','.')?></td>
						</tr>
					</table>
					<br/>
					<table>
						<tr>
							<td>Terbilang</td>
							<td> : </td>
							<td><?=is_number_write(23404500);?></td>
						</tr>
						<tr>
							<td>Jatuh Tempo</td>
							<td> : </td>
							<td>27/12/2017</td>
						</tr>
					</table>
					<br/>
					<br/>
					<table id='table-ttd'>
						<tr>
							<td>Kepala Gudang</td>
							<td>Pengirim</td>
							<td>Penerima</td>
						</tr>
						<tr>
							<td style='height:100px'></td>
							<td></td>
							<td></td>
						</tr>
					</table>
				</p>
			</div>
		</div>
	</body>
</html>