<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Laporan Gaji Per Pegawai</title>
    <style type="text/css">
        * {padding: 0; margin: 0;}
        body {font-family: Arial, Helvetica, sans-serif}
        .container {margin: 20px;}
        .container h1 {text-align: center; margin-top: 2.5em; margin-bottom: 20px;}
        .container p {margin-bottom: 30px;}
        .grid th {
            background: white;
            vertical-align: middle;
            color : black;
            text-align: center;
            height: 35px;
            font-size: 15px;
        }
        .grid tr:first-child {
            border-top: 1px solid black;
        }
        .grid td {
            background: #FFFFFF;
            vertical-align: middle;
            font-size: 16px;
            height: 35px;
            /*padding-left: 5px;*/
            border-bottom: 1px solid black;
            padding-right: 5px;
            /*text-transform: uppercase;*/
        }
        .grid {
            border-collapse: collapse;
            /*border: 1px solid black;*/
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
        <h1 class="text-center">Laporan Gaji Per Pegawai</h1>
        <table>
            <tr>
                <td>Hari</td>
                <td>: <?= $judul ?></td>
            </tr>
            <tr>
                <td>Nama</td>
                <td>: <?= $pegawai['nama'] ?></td>
            </tr>
        </table>
        <h4 style="margin-top: 2em;">Rincian : </h4>
        <?php
            $hitung_bonus = $controller->hitung_bonus($pegawai['pegawai_id'], $tgl_dari, $tgl_sampai);
            $hitung_hutang = $controller->hitung_hutang($pegawai['pegawai_id'], $tgl_dari, $tgl_sampai);
        ?>
        <table class="grid" style="margin-top: 10px;">
            <tbody>
                <tr>
                    <td width="50%">1. Gaji Pokok</td>
                    <td>: Rp. <?= number_format(empty($gaji) ? 0 : $gaji['gaji']) ?></td>
                </tr>
                <tr>
                    <td width="50%">2. Uang Makan</td>
                    <td>: Rp. <?= number_format(empty($gaji) ? 0 : $gaji['uang_makan']) ?></td>
                </tr>
                <tr>
                    <td width="50%">3. Omset</td>
                    <td>: Rp. <?= number_format($hitung_bonus['omset']) ?></td>
                </tr>
                <tr>
                    <td width="50%">4. Tambahan Jaga</td>
                    <td>: Rp. <?= number_format($hitung_bonus['tambahan_jaga']) ?></td>
                </tr>
                <tr>
                    <td width="50%">5. Bonus</td>
                    <td>: Rp. <?= number_format($hitung_bonus['bonus']) ?></td>
                </tr>
                <tr>
                    <td width="50%">6. Hutang</td>
                    <td>: Rp. <?= number_format($hitung_hutang['potongan']) ?></td>
                </tr>
                <tr>
                    <td width="50%">7. Potongan Telat</td>
                    <td>: Rp. <?= number_format($hitung_hutang['potongan_telat']) ?></td>
                </tr>
                <tr>
                    <td width="50%"><b>Total Terima</b></td>
                    <td style="font-size: 19px;"><b>: Rp. 
                        <?php
                            $total_terima = ((int) empty($gaji) ? 0 : $gaji['gaji']) + ((int) empty($gaji) ? 0 : $gaji['uang_makan']) + (int) $hitung_bonus['omset'] + (int) $hitung_bonus['tambahan_jaga'] + (int) $hitung_bonus['bonus'] - (int) $hitung_hutang['potongan'] - (int) $hitung_hutang['potongan_telat'];
                            echo number_format($total_terima);
                        ?>
                    </b></td>
                </tr>

            </tbody>
        </table>
    </div>
<script>
    window.print();
    window.onfocus = function () { window.close(); }
</script>
</body>
</html>
