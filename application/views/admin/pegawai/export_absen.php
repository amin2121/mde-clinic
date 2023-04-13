<?php 
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=".$filename.".xls");
 ?>
<html>
	<body>
		<table class="table pegawai" cellpadding="3" border="1">
			<thead>
				<tr>
					<th rowspan="2" style="text-align: center;">Tanggal</th>
					<th rowspan="2" style="text-align: center;">Nama</th>
					<th colspan="3" style="text-align: center;">Masuk</th>
					<th colspan="3" style="text-align: center;">Pulang</th>
				</tr>
				<tr>
					<th style="text-align: center;">Jam</th>
					<th style="text-align: center;">Longitude</th>
					<th style="text-align: center;">Latitude</th>
					<th style="text-align: center;">Jam</th>
					<th style="text-align: center;">Longitude</th>
					<th style="text-align: center;">Latitude</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($absen as $p): ?>
					<tr>
						<td><?php echo $p->tanggal ?></td>
						<td><?php echo $p->nama ?></td>
						<td><?php echo $p->jam_masuk ?></td>
						<td><?php echo $p->longitude_masuk ?></td>
						<td><?php echo $p->latitude_masuk ?></td>
						<td><?php echo $p->jam_pulang ?></td>
						<td><?php echo $p->longitude_pulang ?></td>
						<td><?php echo $p->latitude_pulang ?></td>
					</tr>
				<?php endforeach ?>
			</tbody>
		</table>
	</body>
</html>