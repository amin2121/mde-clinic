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
    <style type="print"></style>
</head>
<body>
    <div class="container">
        <h1><?= $judul; ?></h1>
        <h3 class="text-center" style="margin-bottom: 1em;">MDE Clinic Dr. Ajeng</h3>
        <table class="grid">
            <thead>
                <tr>
                    <th class="text-center">No.</th>
                    <th class="text-center">NAMA</th>
                    <th class="text-center">JUMLAH TELAT</th>
                    <th class="text-center">POTONGAN TELAT</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $no = 0;
                    $total_keseluruhan = 0;
                foreach ($result as $r) :
                    $no++;
                    ?>
                    <?php
                    $jumlah_telat = $controller->get_jumlah_telat($r['pegawai_id'], $tanggal_dari, $tanggal_sampai);
                    // $potongan_telat = $this->get_potongan_telat($r['id']);
                    ?>
                    <tr>
                        <td><?php echo $no; ?></td>
                        <td><?php echo $r['nama']; ?></td>
                        <td class="text-center"><?php echo $jumlah_telat['jumlah_telat'] < 0 ? '0 Menit' :$jumlah_telat['jumlah_telat'].' Menit' ; ?></td>
                        <td class="text-center">Rp. 
                        <?php
                            $total_potongan = $jumlah_telat['potongan_telat'];
                            $total_keseluruhan += $total_potongan;
                            echo number_format($total_potongan)
                        ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" style="text-align: right;"><b>Total Keseluruhan</b></td>
                    <td style="text-align: center;"><b>Rp. <?= number_format($total_keseluruhan) ?></b></td>
                </tr>
            </tfoot>
        </table>
    </div>
<script>
    window.print();
    window.onfocus = function () { window.close(); }
</script>
</body>
</html>
