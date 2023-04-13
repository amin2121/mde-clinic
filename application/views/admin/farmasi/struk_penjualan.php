<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Nota Pembayaran</title>
    <script type="text/javascript" src="<?= base_url('assets/js/core/libraries/jquery.min.js') ?>"></script>
  </head>
  <style type="text/css" media="print">
  @page {
    /* size: landscape; */
    margin: 0;

  }

  .body {
    margin:0in 0.1in 0in 0.1in;
    /* margin: 1.6cm;
    mso-header-margin:.5in;
    mso-footer-margin:.5in;
    mso-paper-source:4; */
    font-family: Arial, Helvetica, sans-serif;
   }

   .footer{
    position:absolute;
    /* right:0; */
    bottom:0;
  }
  </style>
  <body class="body">
    <p style="font-size: 10px; text-align:center;">
      <b style="font-size: 12px;  text-transform:uppercase;"><?php echo $str['nama']; ?></b><br>
      <?php echo $str['alamat']; ?><br>
      Telp. <?php echo $str['no_telp']; ?>
    </p>

    <table style="width:100%; font-size: 10px;">
      <tbody>
        <tr>
          <td style="text-align:left;">Nomor</td>
          <td style="text-align:left;">:</td>
          <td style="text-align:left; width: 80%;"><?php echo $row['no_transaksi']; ?></td>
        </tr>      
        <tr>
          <td style="text-align:left;">Kasir</td>
          <td style="text-align:left;">:</td>
          <td style="text-align:left;"><?php echo $row['id_kasir'] ?></td>
        </tr>
        <tr>
          <td style="text-align:left;">Nama</td>
          <td style="text-align:left;">:</td>
          <td style="text-align:left;"><?php echo $row['nama_pasien'] ?></td>
        </tr>
      </tbody>
    </table>
    <table style="width:100%; font-size: 10px; border-top: 0.5px dashed; border-bottom: 0.5px dashed;">
      <tbody style="width:25%;">
        <?php foreach ($res as $r): ?>
         <tr>
           <td style='padding-top:1px; text-align: left;' colspan="3"><?php echo $r['nama_barang']; ?></td>
         </tr>
          <tr>
            <td style=' width:25%; padding-bottom:1px; text-align: center;'><?php echo $r['jumlah_beli']; ?></td>
            <td style=' width:25%; padding-bottom:1px; text-align: right;'><?php echo number_format($r['harga_jual']); ?></td>
            <td style=' width:25%; padding-bottom:1px; text-align: right;'><?php echo number_format($r['subtotal']); ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <table style="width:100%; font-size: 10px;">
      <tbody>
        <tr>
          <td style="width: 50%;"></td>
          <td style="text-align:left;">Total</td>
          <td style="text-align:right;">Rp :</td>
          <td style="text-align:right;"><?php echo number_format($row['nilai_transaksi']); ?></td>
        </tr>
        <tr>
          <td style="width: 50%;"></td>
          <td style="text-align:left;">BAYAR</td>
          <td style="text-align:right;">Rp :</td>
          <td style="text-align:right;"><?php echo number_format($row['dibayar']); ?></td>
        </tr>
        <tr>
          <td style="width: 50%;"></td>
          <td style="text-align:left;">Kembali</td>
          <td style="text-align:right;">Rp :</td>
          <td style="text-align:right;"><?php echo number_format($row['kembali']); ?></td>
        </tr>
      </tbody>
    </table>
    <br>
    <table style="width:100%; font-size: 10px;">
      <tbody>
        <tr>
          <td style="text-align:center; width:100%;">
            Terima Kasih Atas Kunjungan Anda<br>
          </td>
        </tr>
        <tr>
          <td style="text-align:center; width:100%;">
            Tanggal <?php echo $row['tanggal']; ?> <?php echo $row['waktu']; ?>
          </td>
        </tr>
      </tbody>
    </table>
    <script>
      window.print();
      window.onfocus = function () { window.close(); }
    </script>
  </body>
</html>
