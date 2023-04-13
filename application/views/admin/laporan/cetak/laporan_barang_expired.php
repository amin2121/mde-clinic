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
		<h1><?= $title; ?></h1>
		<table class="grid">
			<thead>
		        <tr>
		          	<th class="text-center">No</th>
		          	<th class="text-center">Kode Barang</th>
		          	<th class="text-center">Nama Barang</th>
		          	<th class="text-center">Stok</th>
		          	<th class="text-center">Tanggal Kadaluarsa</th>
					<th class="text-center">Jumlah Hari Kadaluarsa</th>
		        </tr>
	      	</thead>
			<tbody>
				<?php foreach($result as $key => $row) : ?>
				<tr>
					<td class="text-center"><?= ++$key ?></td>
					<td class="text-center"><?= $row['kode_barang'] ?></td>
					<td class="text-left"><?= $row['nama_barang'] ?></td>
					<td class="text-center"><?= $row['stok'] ?></td>
					<td class="text-center"><?= $row['tanggal_kadaluarsa'] ?></td>
					<td class="text-center"><?= $row['jumlah_hari'] == null ? 0 : $row['jumlah_hari'] ?> Hari</td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
<script>
    window.print();
    window.onfocus = function () { window.close(); }
</script>
</body>
</html>
