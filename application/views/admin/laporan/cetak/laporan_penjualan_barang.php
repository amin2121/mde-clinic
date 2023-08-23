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
    <table>
      <tbody>
      <?php
      if ($filter == 'hari') {
      ?>
          <tr>
            <td style="width: 15%;">Tanggal</td>
            <td style="width: 2%;">:</td>
            <td><?php echo $judul; ?></td>
          </tr>
      <?php
      }elseif ($filter == 'bulan') {
      ?>
          <tr>
            <td style="width: 15%;">Bulan</td>
            <td style="width: 2%;">:</td>
            <td><?php echo $judul; ?></td>
          </tr>
      <?php
      }elseif ($filter == 'tahun') {
      ?>
          <tr>
            <td style="width: 15%;">Tahun</td>
            <td style="width: 2%;">:</td>
            <td><?php echo $judul; ?></td>
          </tr>
      <?php
        }
      ?>
	  <tr>
        <td style="width: 15%;">Penjualan</td>
        <td style="width: 2%;">:</td>
        <td><?php echo $penjualan; ?></td>
      </tr>
    </tbody>
  </table>
  <br>
		<table class="grid">
			<thead>
				<tr>
					<th>No.</th>
					<th>Nama Barang</th>
					<th>Jumlah Penjualan</th>
				</tr>
			</thead>
			<tbody>
			<?php if (!empty($result)): ?>
				<?php foreach ($result as $key => $r): ?>
					<tr>
						<td class="text-center"><?= ++$key ?></td>
						<td class="text-center"><?= $r['nama_barang'] ?></td>
						<td class="text-center"><?php echo !empty($r['totalpenjualan']) ? $r['totalpenjualan'] : 0; ?></td>
					</tr>
				<?php endforeach; ?>
				<?php endif; ?>
			</tbody>
		</table>
	</div>
<script>
    window.print();
    window.onfocus = function () { window.close(); }
</script>
</body>
</html>
