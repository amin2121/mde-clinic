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
	    	background: black;
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
		<h1><?= $title ?></h1>
		<?php if($filter == 'hari')  : ?>
			<p>Tanggal : <?= $judul ?></p>
		<?php elseif($filter == 'bulan') : ?>
			<p>Bulan : <?= $judul ?></p>
		<?php elseif($filter == 'tahun') : ?>
			<p>Tahun : <?= $judul ?></p>
		<?php endif ?>
		<table class="grid">
			<thead>
				<tr>
					<th>No.</th>
					<th>Tanggal</th>
					<th>No Faktur</th>
					<th>Kode Barang</th>
					<th>Nama Barang</th>
					<th>Harga Jual (Rp.)</th>
					<th>HPP (Rp.)</th>
					<th>Laba (Rp.)</th>
				</tr>
			</thead>
			<tbody>
				<?php
					$total_harga_jual = 0;
					$total_hpp = 0;
					$total_laba = 0;
				 ?>
				<?php foreach ($result as $key => $r): ?>
					<tr>
						<td class="text-center"><?= ++$key ?></td>
						<td class="text-center"><?= date('d-m-Y', strtotime($r['tanggal'])) ?></td>
						<td class="text-center"><?= $r['no_faktur'] ?></td>
						<td class="text-center"><?= $r['kode_barang'] ?></td>
						<td class="text-center"><?= $r['nama_barang'] ?></td>
						<td class="text-right"><?= number_format($r['harga_jual']) ?></td>
						<td class="text-right"><?= number_format($r['harga_awal']) ?></td>
						<td class="text-right"><?= number_format($r['laba'] ) ?></td>
					</tr>
					<?php
						$total_harga_jual += (int) $r['harga_jual'];
						$total_hpp += (int) $r['harga_awal'];
						$total_laba += (int) $r['laba'];
					 ?>
				<?php endforeach ?>
					<tr>
					<td colspan="5" class="text-right"><b>Total</b></td>
					<td class="text-right text-semibold"><?= number_format($total_harga_jual) ?></td>
					<td class="text-right text-semibold"><?= number_format($total_hpp) ?></td>
					<td class="text-right text-semibold"><?= number_format($total_laba) ?></td>
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
