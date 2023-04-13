<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Laporan Pendapatan</title>
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
  <h2 style="text-align:center;">Total Pendapatan per <?php echo $title; ?></h2>

  <br>
  <table>
    <tbody>
    <?php
    if ($title == 'Hari') {
    ?>
        <tr>
          <td style="width: 15%;">Tanggal</td>
          <td style="width: 2%;">:</td>
          <td><?php echo $judul; ?></td>
        </tr>
    <?php
    }elseif ($title == 'Bulan') {
    ?>
        <tr>
          <td style="width: 15%;">Bulan</td>
          <td style="width: 2%;">:</td>
          <td><?php echo $judul; ?></td>
        </tr>
    <?php
    }elseif ($title == 'Tahun') {
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
      <td style="width: 15%;">Cabang</td>
      <td style="width: 2%;">:</td>
      <td><?php echo $nama_cabang; ?></td>
    </tr>
  </tbody>
</table>
<br>

  <table style="width: 100%;" class="grid">
    <thead>
      <tr>
        <th style="text-align: center;">No</th>
        <th style="text-align: center;">Tanggal</th>
        <th style="text-align: center;">Invoice</th>
        <th style="text-align: center;">Nama Pasien</th>
        <th style="text-align: center;">Total Invoice (Rp)</th>
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
            <td style="text-align:center;"><?php echo $r['invoice']; ?></td>
            <td style="text-align:center;"><?php echo wordwrap($r['nama_pasien'], 20, '<br/>', true); ?></td>
            <td style="text-align:right;"><?php echo number_format($r['total_invoice']); ?></td>
          </tr>
        <?php
        $total_invoice += $r['total_invoice'];
        endforeach;
        ?>
        <tr>
          <td colspan="4" style="text-align:right;">Total</td>
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
