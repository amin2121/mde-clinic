<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<title>
		<?= $title; ?>
	</title>
	<style type="text/css">
		* {
			padding: 0;
			margin: 0;
		}

		body {
			font-family: Arial, Helvetica, sans-serif
		}

		.container {
			margin: 20px;
		}

		.container h1 {
			text-align: center;
			margin-top: 2.5em;
			margin-bottom: 20px;
		}

		.container p {
			margin-bottom: 30px;
		}

		.grid th {
			background: white;
			vertical-align: middle;
			border: 1px solid black;
			color: black;
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

		.grid tfoot td {
			background: white;
			vertical-align: middle;
			color: black;
			text-align: center;
			height: 20px;
		}

		.footer {
			position: absolute;
			/* right:0; */
			bottom: 0;
		}

		.text-center {
			text-align: center;
		}

		.text-right {
			text-align: right;
		}

		.text-left {
			text-align: left;
		}

		.text-justify {
			text-align: justify;
		}
	</style>
</head>

<body>
	<div class="container">
		<h1>
			<?= $title; ?>
		</h1>
		<?php if ($filter == 'hari'): ?>
			<p>Tanggal :
				<?= $judul ?>
			</p>
		<?php elseif ($filter == 'bulan'): ?>
			<p>Bulan :
				<?= $judul ?>
			</p>
		<?php elseif ($filter == 'tahun'): ?>
			<p>Tahun :
				<?= $judul ?>
			</p>
		<?php endif; ?>
		<table class="grid">
			<thead>
				<tr>
					<th class="textcenter" rowspan="2">No</th>
					<th class="textcenter" rowspan="2">Nama Barang</th>
					<th class="textcenter" rowspan="2">Stok</th>
					<th class="textcenter" colspan="2">Keluar Farmasi</th>
					<th class="textcenter" colspan="2">Keluar Apotek</th>
					<th class="textcenter" colspan="2">Masuk</th>
				</tr>
				<tr>
					<th style="text-align: center;">Jumlah</th>
					<th style="text-align: center;">Harga</th>
					<th style="text-align: center;">Jumlah</th>
					<th style="text-align: center;">Harga</th>
					<th style="text-align: center;">Jumlah</th>
					<th style="text-align: center;">Harga</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$and = "";
				if ($id_cabang == 'semua') {
					$nama_cabang = "Semua";
					$and = "";
				} else {
					$cab = $this->db->get_where('data_cabang', array('id' => $id_cabang))->row_array();
					$nama_cabang = $cab['nama'];

					$and = "AND a.id_cabang = '$id_cabang'";
				}

				foreach ($result as $key => $rs):
					?>
					<tr>
						<td class="text-center">
							<?= ++$key ?>
						</td>
						<td class="text-left">
							<?= $rs['nama_barang'] ?>
						</td>
						<td class="text-center">
							<?= $rs['stok'] ?>
						</td>
						<?php
						$id_barang = $rs['id_barang'];

						$row_keluar_1 = '';
						if ($filter == 'hari') {
							$row_keluar_1 = $this->db->query("SELECT
																								IFNULL(SUM(a.jumlah), 0) AS jumlah,
																								IFNULL(SUM(a.nilai), 0) AS nilai
																								FROM
																								(
																									SELECT
																									a.jumlah_beli as jumlah,
																									a.subtotal as nilai
																									FROM farmasi_penjualan_detail a
																									WHERE STR_TO_DATE(a.tanggal, '%d-%m-%Y') >= STR_TO_DATE('$tanggal_dari_fix', '%d-%m-%Y')
																									AND STR_TO_DATE(a.tanggal, '%d-%m-%Y') <= STR_TO_DATE('$tanggal_sampai_fix', '%d-%m-%Y')
																									AND a.id_barang = $id_barang
																									$and

																									UNION ALL

																									SELECT
																									a.jumlah_obat as jumlah,
																									a.harga_obat as nilai
																									FROM rsi_resep_detail a
																									LEFT JOIN rsi_resep b ON a.id_resep = b.id
																									LEFT JOIN rsi_registrasi c ON b.id_registrasi = c.id
																									WHERE c.status_bayar = 1
																									AND STR_TO_DATE(b.tanggal, '%d-%m-%Y') >= STR_TO_DATE('$tanggal_dari_fix', '%d-%m-%Y')
																									AND STR_TO_DATE(b.tanggal, '%d-%m-%Y') <= STR_TO_DATE('$tanggal_sampai_fix', '%d-%m-%Y')
																									AND a.id_barang = $id_barang
																									$and
																								) a
											")->row_array();

							$row_keluar_2 = $this->db->query("SELECT
																								IFNULL(SUM(a.jumlah_beli), 0) AS jumlah,
																								IFNULL(SUM(a.subtotal), 0) AS nilai
																								FROM apotek_penjualan_detail a
																								WHERE STR_TO_DATE(a.tanggal, '%d-%m-%Y') >= STR_TO_DATE('$tanggal_dari_fix', '%d-%m-%Y')
																								AND STR_TO_DATE(a.tanggal, '%d-%m-%Y') <= STR_TO_DATE('$tanggal_sampai_fix', '%d-%m-%Y')
																								AND a.id_barang = $id_barang
																								$and
								")->row_array();
						} else if ($filter == 'bulan') {
							$row_keluar_1 = $this->db->query("SELECT
																								IFNULL(SUM(a.jumlah), 0) AS jumlah,
																								IFNULL(SUM(a.nilai), 0) AS nilai
																								FROM
																								(
																									SELECT
																									a.jumlah_beli as jumlah,
																									a.subtotal as nilai
																									FROM farmasi_penjualan_detail a
																									WHERE DATE_FORMAT(DATE_FORMAT(STR_TO_DATE(a.tanggal, '%d-%m-%y'), '%Y-%m-%d'), '%m') = '$bulan'
																									AND DATE_FORMAT(DATE_FORMAT(STR_TO_DATE(a.tanggal, '%d-%m-%y'), '%Y-%m-%d'), '%Y') = '$tahun'
																									AND a.id_barang = $id_barang
																									$and

																									UNION ALL

																									SELECT
																									a.jumlah_obat as jumlah,
																									a.harga_obat as nilai
																									FROM rsi_resep_detail a
																									LEFT JOIN rsi_resep b ON a.id_resep = b.id
																									LEFT JOIN rsi_registrasi c ON b.id_registrasi = c.id
																									WHERE c.status_bayar = 1
																									AND b.bulan = '$bulan'
																									AND b.tahun = '$tahun'
																									AND a.id_barang = $id_barang
																									$and
																								) a
											")->row_array();

							$row_keluar_2 = $this->db->query("SELECT
																									IFNULL(SUM(a.jumlah_beli), 0) AS jumlah,
																									IFNULL(SUM(a.subtotal), 0) AS nilai
																									FROM apotek_penjualan_detail a
																									WHERE DATE_FORMAT(DATE_FORMAT(STR_TO_DATE(a.tanggal, '%d-%m-%y'), '%Y-%m-%d'), '%m') = '$bulan'
																									AND DATE_FORMAT(DATE_FORMAT(STR_TO_DATE(a.tanggal, '%d-%m-%y'), '%Y-%m-%d'), '%Y') = '$tahun'
																									AND a.id_barang = $id_barang
																									$and
								")->row_array();
						} else if ($filter == 'tahun') {
							$row_keluar_1 = $this->db->query("SELECT
																								IFNULL(SUM(a.jumlah), 0) AS jumlah,
																								IFNULL(SUM(a.nilai), 0) AS nilai
																								FROM
																								(
																									SELECT
																									a.jumlah_beli as jumlah,
																									a.subtotal as nilai
																									FROM farmasi_penjualan_detail a
																									WHERE DATE_FORMAT(DATE_FORMAT(STR_TO_DATE(a.tanggal, '%d-%m-%y'), '%Y-%m-%d'), '%Y') = '$tahun'
																									AND a.id_barang = $id_barang
																									$and

																									UNION ALL

																									SELECT
																									a.jumlah_obat as jumlah,
																									a.harga_obat as nilai
																									FROM rsi_resep_detail a
																									LEFT JOIN rsi_resep b ON a.id_resep = b.id
																									LEFT JOIN rsi_registrasi c ON b.id_registrasi = c.id
																									WHERE c.status_bayar = 1
																									AND DATE_FORMAT(DATE_FORMAT(STR_TO_DATE(b.tanggal, '%d-%m-%y'), '%Y-%m-%d'), '%Y') = '$tahun'
																									AND a.id_barang = $id_barang
																									$and
																								) a
											")->row_array();
							$row_keluar_2 = $this->db->query("SELECT
																								IFNULL(SUM(a.jumlah_beli), 0) AS jumlah,
																								IFNULL(SUM(a.subtotal), 0) AS nilai
																								FROM apotek_penjualan_detail a
																								WHERE DATE_FORMAT(DATE_FORMAT(STR_TO_DATE(a.tanggal, '%d-%m-%y'), '%Y-%m-%d'), '%Y') = '$tahun'
																								AND a.id_barang = $id_barang
																								$and
							")->row_array();
						}
						?>
						<td class="text-center">
							<?php if ((int) $row_keluar_1['jumlah'] == null): ?>
								-
							<?php else: ?>
								<?= number_format((int) $row_keluar_1['jumlah']) ?>
							<?php endif; ?>
						</td>
						<td class="text-center">
							<?php if ((int) $row_keluar_1['nilai'] == null): ?>
								-
							<?php else: ?>
								<?= number_format((int) $row_keluar_1['nilai']) ?>
							<?php endif; ?>
						</td>
						<td class="text-center">
							<?php if ((int) $row_keluar_2['jumlah'] == null): ?>
								-
							<?php else: ?>
								<?= number_format((int) $row_keluar_2['jumlah']) ?>
							<?php endif; ?>
						</td>
						<td class="text-center">
							<?php if ((int) $row_keluar_2['nilai'] == null): ?>
								-
							<?php else: ?>
								<?= number_format((int) $row_keluar_2['nilai']) ?>
							<?php endif; ?>
						</td>
						<?php
						$id_barang = $rs['id_barang'];

						$row_masuk = '';
						if ($filter == 'hari') {
							$row_masuk = $this->db->query("
                                                SELECT
                                                SUM(a.jumlah_beli) as jumlah,
                                                SUM(a.harga_awal) as nilai
                                                FROM farmasi_faktur_detail a
                                                LEFT JOIN farmasi_faktur b ON a.id_faktur = b.id
												WHERE STR_TO_DATE(b.tanggal, '%d-%m-%Y') >= STR_TO_DATE('$tanggal_dari_fix', '%d-%m-%Y')
												AND STR_TO_DATE(b.tanggal, '%d-%m-%Y') <= STR_TO_DATE('$tanggal_sampai_fix', '%d-%m-%Y')
                                                AND a.id_barang = $id_barang
											")->row_array();
						} else if ($filter == 'bulan') {
							$row_masuk = $this->db->query("
                                                SELECT
                                                SUM(a.jumlah_beli) AS jumlah,
                                                SUM(a.harga_awal) AS nilai
                                                FROM farmasi_faktur_detail a
                                                LEFT JOIN farmasi_faktur b ON a.id_faktur = b.id
                                                WHERE b.bulan = '$bulan'
												AND b.tahun = '$tahun'
                                                AND a.id_barang = $id_barang
											")->row_array();
						} else if ($filter == 'tahun') {
							$row_masuk = $this->db->query("SELECT
                                                SUM(a.jumlah_beli) AS jumlah,
                                                SUM(a.harga_awal) AS nilai
                                                FROM farmasi_faktur_detail a
                                                LEFT JOIN farmasi_faktur b ON a.id_faktur = b.id
												WHERE b.tahun = '$tahun'
                                                AND a.id_barang = $id_barang
											")->row_array();
						}
						?>
						<td class="text-center">
							<?php if ((int) $row_masuk['jumlah'] == null): ?>
								-
							<?php else: ?>
								<?= number_format((int) $row_masuk['jumlah']) ?>
							<?php endif; ?>
						</td>
						<td class="text-center">
							<?= (number_format((int) $row_masuk['nilai'] * (int) $row_masuk['jumlah']) == 0) ? '-' : number_format((int) $row_masuk['nilai'] * (int) $row_masuk['jumlah']) ?>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
	<script>
		window.print();
	// window.onfocus = function () { window.close(); }
	</script>
</body>

</html>