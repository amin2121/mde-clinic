<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Nota Pembayaran</title>
    <script type="text/javascript" src="<?= base_url('assets/js/core/libraries/jquery.min.js') ?>"></script>
  </head>
  <?php if($this->session->userdata('id_cabang') == 1) : ?>
    <style type="text/css" media="print">
        @page {
          /* size: landscape; */
          margin: 0;

        }
        .body {
          margin:0in 0.2in 0in 0.2in;
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
  <?php elseif($this->session->userdata('id_cabang') == 2) :?>
    <?php var_dump('2') ?>
    <style type="text/css" media="print">
        @page {
          /* size: landscape; */
          margin: 0;
        }

        .body {
          margin:0 !important;
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
  <?php endif; ?>

  <body class="body">
    <p style="font-size: 11px; text-align:center;">
      <b style="font-size: 13px;  text-transform:uppercase;"><?php echo $str['nama']; ?></b><br>
      <?php echo $str['alamat']; ?><br>
      Telp. <?php echo $str['no_telp']; ?>
    </p>

    <table style="width:100%; font-size: 11px;">
      <tbody>
        <tr>
          <td style="text-align:left;">Nomor</td>
          <td style="text-align:left;">:</td>
          <td style="text-align:left; width: 80%;"><?php echo $row['invoice']; ?></td>
        </tr>
        <tr>
          <td style="text-align:left;">Kasir</td>
          <td style="text-align:left;">:</td>
          <td style="text-align:left;"><?php echo $row['nama_kasir'] ?></td>
        </tr>
        <tr>
          <td style="text-align:left;">Pasien</td>
          <td style="text-align:left;">:</td>
          <td style="text-align:left;"><?php echo $row['nama_pasien'] ?></td>
        </tr>
        <tr>
          <td style="text-align:left;">Poli</td>
          <td style="text-align:left;">:</td>
          <td style="text-align:left;"><?php echo $row['nama_poli'] ?></td>
        </tr>
      </tbody>
    </table>

    <br>
    <table style="width:100%; font-size: 11px; border-top: 0.5px dashed; border-bottom: 0.5px dashed;">
      <tbody style="width:25%;">
        <?php foreach ($res as $r): ?>
         <tr>
           <td style='padding-top:1px; text-align: left;' colspan="3"><?php echo $r['nama']; ?></td>
         </tr>
          <tr>
            <td style=' width:25%; padding-bottom:1px; text-align: center;'><?php echo $r['jumlah']; ?></td>
            <td style=' width:25%; padding-bottom:1px; text-align: right;'><?php echo number_format($r['harga']); ?></td>
            <td style=' width:25%; padding-bottom:1px; text-align: right;'><?php echo number_format($r['sub_total']); ?></td>
          </tr>
        <?php endforeach; ?>
        <tr>
          <td style='padding-top:1px; text-align: left;' colspan="3">Biaya Admin</td>
        </tr>
         <tr>
           <td style=' width:25%; padding-bottom:1px; text-align: center;'>1</td>
           <td style=' width:25%; padding-bottom:1px; text-align: right;'><?php echo number_format($rgs['biaya_admin']); ?></td>
           <td style=' width:25%; padding-bottom:1px; text-align: right;'><?php echo number_format($rgs['biaya_admin']); ?></td>
         </tr>

         <?php if ($rgs['biaya_id_card'] != '0'): ?>
           <tr>
             <td style='padding-top:1px; text-align: left;' colspan="3">Biaya ID Card<td>
           </tr>
            <tr>
              <td style=' width:25%; padding-bottom:1px; text-align: center;'>1</td>
              <td style=' width:25%; padding-bottom:1px; text-align: right;'><?php echo number_format($rgs['biaya_id_card']); ?></td>
              <td style=' width:25%; padding-bottom:1px; text-align: right;'><?php echo number_format($rgs['biaya_id_card']); ?></td>
            </tr>
         <?php endif; ?>
      </tbody>
    </table>

    <table style="width:100%; font-size: 11px;">
      <tbody>
        <tr>
          <td style="width: 30%;"></td>
          <td style="text-align:left;">Total</td>
          <td style="text-align:right;">Rp :</td>
          <td style="text-align:right;"><?php echo number_format($row['total_invoice']); ?></td>
        </tr>
        <tr>
          <td style="width: 30%;"></td>
          <td style="text-align:left;">Jumlah Uang</td>
          <td style="text-align:right;">Rp :</td>
          <td style="text-align:right;"><?php echo number_format($row['bayar']); ?></td>
        </tr>
        <tr>
          <td style="width: 30%;"></td>
          <td style="text-align:left;">Kembali</td>
          <td style="text-align:right;">Rp :</td>
          <td style="text-align:right;"><?php echo number_format($row['kembali']); ?></td>
        </tr>
      </tbody>
    </table>

    <br>
    <table style="width:100%; font-size: 11px;">
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
