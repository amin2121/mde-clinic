<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Laporan Pengeluaran</title>
    <script src="<?php echo base_url(); ?>assets/bower_components/jquery-ui/jquery-ui.min.js"></script>
  </head>
  <style type="text/css" media="print">
  @page {
    /* size: landscape; */
    margin: 1cm;
  }

  table{
    width: 100%;
  }

  .body {
    font-family: Arial, Helvetica, sans-serif;
  }

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
    	border: 1px solid black;
        border-spacing: 0;
        border-collapse: collapse;
    }

    .grid tfoot{
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
  </style>
  <body class="body">
  <h2 style="text-align:center;">Total Pengeluaran per <?php echo $title; ?></h2>

  <br>
  <?php
  if ($title == 'Hari') {
  ?>
  <table>
    <tbody>
      <tr>
        <td style="width: 15%;">Tanggal</td>
        <td style="width: 2%;">:</td>
        <td><?php echo $judul; ?></td>
      </tr>
    </tbody>
  </table>
  <?php
  }elseif ($title == 'Bulan') {
  ?>
  <table>
    <tbody>
      <tr>
        <td style="width: 15%;">Bulan</td>
        <td style="width: 2%;">:</td>
        <td><?php echo $judul; ?></td>
      </tr>
    </tbody>
  </table>
  <?php
  }elseif ($title == 'Tahun') {
  ?>
  <table>
    <tbody>
      <tr>
        <td style="width: 15%;">Tahun</td>
        <td style="width: 2%;">:</td>
        <td><?php echo $judul; ?></td>
      </tr>
    </tbody>
  </table>
  <?php
    }
  ?>
  <br>

  <table style="width: 100%;" class="grid">
    <thead>
      <tr>
        <th style="text-align: center;">No</th>
        <th style="text-align: center;">Tanggal</th>
        <th style="text-align: center;">Nama</th>
        <th style="text-align: center;">Nominal (Rp)</th>
      </tr>
    </thead>
    <tbody>
      <?php
          $no = 0;
          $total_invoice = 0;
          foreach ($result as $r):
          $no++;
          ?>
          <tr>
            <td style="text-align:center;"><?php echo $no; ?></td>
            <td style="text-align:center;"><?php echo $r['tanggal']; ?></td>
            <td style="text-align:center;">
              <?php
              if ($r['status'] == 'Pengeluaran') {
                echo $r['nama_pemasukan'];
              }elseif ($r['status'] == 'Faktur') {
                echo "Faktur Tanggal ".$r['nama_pemasukan'];
              }
              ?>
            </td>
            <td style="text-align:right;"><?php echo number_format($r['nominal']); ?></td>
          </tr>
        <?php
        $total_invoice += $r['nominal'];
        endforeach;
        ?>
        <tr>
          <td colspan="3" style="text-align:right;">Total</td>
          <td style="text-align:right;"><?php echo number_format($total_invoice); ?></td>
        </tr>
    </tbody>
  </table>
  <script>
    window.print();
    // window.onfocus = function () { window.close(); }
  </script>
</body>
</html>
