<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title><?= $title; ?></title>
	<style type="text/css">
		* {padding: 0; margin: 0;}
		body {font-family: Arial, Helvetica, sans-serif}
		.container {margin: 20px;}
		.container h1 {text-align: center; margin-top: 2.5em; margin-bottom: 20px;}
		.container p {margin-bottom: 30px;}
		.grid th {
	    	background: white;
	    	vertical-align: middle;
	      	border: 1px solid black;
	    	color : black;
	        text-align: center;
	        height: 30px;
	        font-size: 13px;
	    }
	    .grid td {
	    	background: #FFFFFF;
	    	vertical-align: middle;
	      	border: 1px solid black;
	    	font: 11px/15px sans-serif;
	    	font-size: 11px;
	        height: 20px;
	        padding-left: 5px;
	        padding-right: 5px;
	    }
	    .grid {
	      	border-collapse: collapse;
	    	border: 1px solid black;
	        border-spacing: 0;
	        width: 100%;
	    }

	    .grid tfoot td{
	    	background: white;
	    	vertical-align: middle;
	    	color : black;
	        text-align: center;
	        height: 20px;
	    }

	   .footer{
		    position:absolute;
		    /* right:0; */
		    bottom:0;
	  }
	  .text-center {text-align: center;}
	  .text-right {text-align: right;}
	  .text-left {text-align: left;}
	  .text-justify {text-align: justify ;}
	</style>
</head>
<body>
	<div class="container">
		<h1><?= $judul; ?></h1>
		<p>Tanggal : <?= date('d-m-Y') ?></p>
		<table class="grid">
			<thead>
				<tr>
					<th>No.</th>
					<th>Nama Barang</th>
					<th>Kode Barang</th>
					<th>Harga Awal (Rp.)</th>
					<th>Harga Jual (Rp.)</th>
					<th>Stok</th>
					<th>Nilai (Rp.)</th>
				</tr>
			</thead>
			<tbody>
				<?php 
					$total_harga_awal = 0;
					$total_harga_jual = 0;
					$total_harga_stok = 0;
					$total_harga_nilai = 0;
				 ?>
				<?php foreach ($result as $key => $rs): ?>
					<tr>
						<td class="text-center"><?= ++$key ?></td>
						<td class="text-center"><?= $rs['nama_barang'] ?></td>
						<td class="text-center"><?= $rs['kode_barang'] ?></td>
						<td class="text-right"><?= number_format($rs['harga_awal']) ?></td>
						<td class="text-right"><?= number_format($rs['harga_jual']) ?></td>
						<td class="text-center"><?= $rs['stok'] ?></td>
						<td class="text-right"><?= number_format((int) $rs['stok'] * (int) $rs['harga_awal']) ?></td>
					</tr>
					<?php 
						$total_harga_awal += (int) $rs['harga_awal'];
						$total_harga_jual += (int) $rs['harga_jual'];
						$total_harga_stok += (int) $rs['stok'];
						$total_harga_nilai += (int) $rs['stok'] * (int) $rs['harga_awal'];
				 	?>
				<?php endforeach ?>
					<tr>
					<td colspan="3" class="text-right"><b>Total</b></td>
					<td class="text-right"><?= number_format($total_harga_awal) ?></td>
					<td class="text-right"><?= number_format($total_harga_jual) ?></td>
					<td class="text-center"><?= number_format($total_harga_stok) ?></td>
					<td class="text-right"><?= number_format($total_harga_nilai) ?></td>
					</tr>
			</tbody>
		</table>
	</div>
<script>
    window.print();
    // window.onfocus = function () { window.close(); }
</script>
</body>
</html>
