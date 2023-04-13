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
        <?php 
            $get_date_of_range_date = $this->db->query("
                SELECT
                    * 
                FROM
                    (
                    SELECT
                        adddate( '1970-01-01', t4 * 10000 + t3 * 1000 + t2 * 100 + t1 * 10 + t0 ) gen_date 
                    FROM
                        (
                        SELECT
                            0 t0 UNION
                        SELECT
                            1 UNION
                        SELECT
                            2 UNION
                        SELECT
                            3 UNION
                        SELECT
                            4 UNION
                        SELECT
                            5 UNION
                        SELECT
                            6 UNION
                        SELECT
                            7 UNION
                        SELECT
                            8 UNION
                        SELECT
                            9 
                        ) t0,
                        (
                        SELECT
                            0 t1 UNION
                        SELECT
                            1 UNION
                        SELECT
                            2 UNION
                        SELECT
                            3 UNION
                        SELECT
                            4 UNION
                        SELECT
                            5 UNION
                        SELECT
                            6 UNION
                        SELECT
                            7 UNION
                        SELECT
                            8 UNION
                        SELECT
                            9 
                        ) t1,
                        (
                        SELECT
                            0 t2 UNION
                        SELECT
                            1 UNION
                        SELECT
                            2 UNION
                        SELECT
                            3 UNION
                        SELECT
                            4 UNION
                        SELECT
                            5 UNION
                        SELECT
                            6 UNION
                        SELECT
                            7 UNION
                        SELECT
                            8 UNION
                        SELECT
                            9 
                        ) t2,
                        (
                        SELECT
                            0 t3 UNION
                        SELECT
                            1 UNION
                        SELECT
                            2 UNION
                        SELECT
                            3 UNION
                        SELECT
                            4 UNION
                        SELECT
                            5 UNION
                        SELECT
                            6 UNION
                        SELECT
                            7 UNION
                        SELECT
                            8 UNION
                        SELECT
                            9 
                        ) t3,
                        (
                        SELECT
                            0 t4 UNION
                        SELECT
                            1 UNION
                        SELECT
                            2 UNION
                        SELECT
                            3 UNION
                        SELECT
                            4 UNION
                        SELECT
                            5 UNION
                        SELECT
                            6 UNION
                        SELECT
                            7 UNION
                        SELECT
                            8 UNION
                        SELECT
                            9 
                        ) t4 
                    ) v
                WHERE
                    gen_date BETWEEN STR_TO_DATE('$tgl_dari','%d-%m-%y') 
                    AND STR_TO_DATE('$tgl_sampai','%d-%m-%y')
            ")->result_array();
         ?>
        <table class="grid">
            <thead>
                <tr>
                    <th class="text-center" rowspan="2">No.</th>
                    <th class="text-center" rowspan="2">NAMA</th>
                    <th class="text-center" colspan="<?= count($get_date_of_range_date) ?>">Tanggal</th>
                    <!-- <th class="text-center">JUMLAH TELAT</th>
                    <th class="text-center">POTONGAN TELAT</th> -->
                </tr>
                <tr>
                    <?php foreach ($get_date_of_range_date as $value): ?>
                        <th><?= date('d', strtotime($value['gen_date'])) ?></th>
                    <?php endforeach ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($result as $key => $r): ?>
                    <tr>
                        <td><?= ++$key ?></td>
                        <td><?= $r['nama'] ?></td>
                        <?php foreach ($get_date_of_range_date as $value): ?>
                            <?php $get_shift = $controller->get_shift($value['gen_date'], $r['pegawai_id']); ?>
                            <td class="text-center" width="50"><?php
                                if($get_shift['shift']['nama'] != '') {
                                    echo $get_shift['shift']['nama'];
                                } else {
                                    if($get_shift['ijin']['ijin'] != '') {
                                        echo ucfirst($get_shift['ijin']['ijin']);
                                    } else {
                                        echo '-';
                                    }
                                }
                            ?></td>
                        <?php endforeach ?>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>
<script>
    window.print();
    window.onfocus = function () { window.close(); }
</script>
</body>
</html>
