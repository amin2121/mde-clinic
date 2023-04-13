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
        <h3 class="text-center" style="margin-bottom: 1em;"><?php echo $nama_cabang; ?></h3>
        <table class="grid">
            <thead>
                <tr>
                    <th class="text-center">No.</th>
                    <th class="text-center">TGL</th>
                    <th class="text-center">NAMA</th>
                    <th class="text-center">GAJI POKOK</th>
                    <th class="text-center">OMSET</th>
                    <th class="text-center">JASA MEDIS</th>
                    <th class="text-center">BONUS</th>
                    <th class="text-center">POTONGAN</th>
                    <th class="text-center">TAMBAHAN JAGA</th>
                    <th class="text-center">UANG MAKAN</th>
                    <th class="text-center">TOTAL TERIMA</th>
                    <th class="text-center">TTD</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $no = 0;
                    $total_keseluruhan = 0;
                foreach ($result as $r) :
                    $no++;
                    ?>
                    <tr>
                        <td><?php echo $no; ?></td>
                        <td>7</td>
                        <td><?php echo $r['nama'] ?></td>
                        <td style="text-align:right;">Rp. <?php echo number_format($r['gaji']); ?></td>
                        <td style="text-align:right;">Rp. <?php echo number_format($r['omset']); ?></td>
                        <td style="text-align:right;">Rp. <?php echo number_format($r['jasa_medis']); ?></td>
                        <td style="text-align:right;">Rp. <?php echo number_format($r['bonus']); ?></td>
                    <?php
                        $not = $controller->cek_absen($r['pegawai_id'], $bulan, $tahun);
                        $hitung_not = ($r['gaji'] * (10 / 100) * $not);
                        $potongan_telat = $controller->cek_potongan_telat($r['pegawai_id'], $tgl_dari, $tgl_sampai);
                    ?>
                        <td style="text-align:right;">Rp. <?php echo number_format($r['potongan'] + $potongan_telat['potongan_telat']); ?></td>
                        <td style="text-align:right;">Rp. <?php echo number_format($r['jaga']); ?></td>
                        <?php
                        ?>
                        <td style="text-align:right;">Rp. <?php echo number_format($r['uang_makan']); ?></td>
                        <td style="text-align:right;">Rp. 
                            <?php
                                $total_terima = ($r['gaji'] + $r['omset'] + $r['jasa_medis'] + $r['bonus'] + $r['jaga'] + $r['uang_makan']) - ($r['potongan'] + $potongan_telat['potongan_telat']);
                                $total_keseluruhan += $total_terima;
                                echo number_format($total_terima);
                            ?>
                        </td>
                        <td></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="10" style="text-align: right;"><b>Total Keseluruhan</b></td>
                    <td style="text-align: center;" colspan="2"><b>Rp. <?= number_format($total_keseluruhan) ?></b></td>
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
