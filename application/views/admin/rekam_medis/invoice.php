<!DOCTYPE html>
<html lang="en">
<head>
<?php $this->load->view('admin/css'); ?>
</head>

<body>

<?php $this->load->view('admin/nav'); ?>
<?php $this->load->view('admin/rekam_medis/menu'); ?>

<!-- Page container -->
<div class="page-container">

<!-- Page content -->
<div class="page-content">

<!-- Main content -->
<div class="content-wrapper">
<div class="content">

			<!-- Invoice template -->
			<div class="panel panel-flat border-top-success border-top-lg">
				<div class="panel-heading">
					<h6 class="panel-title"><?= $title; ?></h6>
					<div class="heading-elements">
						<button class="btn btn-md btn-warning" type="button" onclick="window.location.href='<?= base_url() ?>rekam_medis/rekam_medis/cari_rekam_medis/<?php echo $registrasi_pasien['id_pasien']; ?>'"><i class="icon-arrow-left7"></i> Kembali</button>
      		</div>
				</div>

				<div class="panel-body no-padding-bottom">
					<div class="row">
						<div class="col-sm-6 content-group">
							<img src="<?= base_url('assets/logo_MDE_klinik_hitam_100_pixeld.png') ?>" class="content-group mt-10" alt="" style="width: 120px;">
 							<ul class="list-condensed list-unstyled">
								<li>Jl. Kyai Ghozali No.78,</li>
								<li>Rogotrunan, Kec. Lumajang</li>
								<li>Kabupaten Lumajang, Jawa Timur 67316</li>
								<li>0852-3707-6123</li>
							</ul>
						</div>

						<div class="col-sm-6 content-group">
							<div class="invoice-details">
								<h5 class="text-uppercase text-semibold">Invoice <?= $registrasi_pasien['invoice']; ?></h5>
								<ul class="list-condensed list-unstyled">
									<li>Tanggal: <span class="text-semibold"><?= date('d F Y', strtotime($registrasi_pasien['tanggal'])) ?></span></li>
									<li>Waktu: <span class="text-semibold"><?= $registrasi_pasien['waktu'] ?></span></li>
								</ul>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-6 col-lg-9 content-group">
							<span class="text-muted">Keterangan Pasien:</span>
 							<ul class="list-condensed list-unstyled">
								<li><h5><?= $pembayaran['nama_pasien'] ?></h5></li>
								<li><span class="text-semibold"><?= $pembayaran['username'] ?></span></li>
								<li>NIK : <?= $pembayaran['no_ktp'] ?></li>
								<li><?= $pembayaran['alamat'] ?></li>
								<li><?= $pembayaran['no_telp'] ?></li>
							</ul>
						</div>

						<div class="col-md-6 col-lg-3 content-group">
							<span class="text-muted">Keterangan Lainnya:</span>
							<ul class="list-condensed list-unstyled invoice-payment-details">
								<!-- <li><h5>Total Due: <span class="text-right text-semibold">$8,750</span></h5></li> -->
								<li>Dokter: <span class="text-semibold"><?= $pembayaran['nama_dokter'] ?></span></li>
								<li>Poli: <span><?= $pembayaran['poli_nama'] ?></span></li>
								<li>Kasir: <span><?= $pembayaran['nama_kasir'] ?></span></li>
							</ul>
						</div>
					</div>
				</div>

				<h5 style="margin-left: 1em;"><b>A. Data Resep</b></h5>
				<div class="table-responsive">
				    <table class="table table-lg">
				        <thead>
				            <tr>
				                <th>Description</th>
				                <th class="col-sm-2">Harga Satuan</th>
				                <th class="col-sm-2">Jumlah</th>
				                <th class="col-sm-2">Harga Total</th>
				            </tr>
				        </thead>
				        <tbody>
				        	<?php foreach ($resep_detail as $rd): ?>
					            <tr>
					                <td>
					                	<h6 class="no-margin"><?= $rd['nama_barang'] ?></h6>
					                	<span class="text-muted"><?= $rd['jenis_barang'] ?></span>
				                	</td>
					                <td><b>Rp.</b> <?= number_format($rd['harga_obat'], 0, ".", ".") ?></td>
					                <td><?= $rd['jumlah_obat'] ?></td>
					                <td><span class="text-semibold"><b>Rp.</b> <?= number_format($rd['sub_total_obat'], 0, ".", ".") ?></span></td>
					            </tr>
				        	<?php endforeach ?>
				        </tbody>
								<tfoot>
									<tr>
										<td colspan="3" class="text-right" style="margin-right: 1em;">Biaya Resep : </td>
										<td><b>Rp. <?= number_format($pembayaran['biaya_resep'], 0, ".", ".") ?></b></td>
									</tr>
								</tfoot>
				    </table>
				</div>
				<h5 style="margin-left: 1em;"><b>B. Data Tindakan</b></h5>
				<div class="table-responsive">
				    <table class="table table-lg">
				        <thead>
				            <tr>
				                <th>Description</th>
				                <th class="col-sm-2">Harga Satuan</th>
				                <th class="col-sm-2">Jumlah</th>
				                <th class="col-sm-2">Harga Total</th>
				            </tr>
				        </thead>
				        <tbody>
									<?php foreach ($tindakan_detail as $td): ?>
				            <tr>
				                <td>
				                	<h6 class="no-margin"><?= $td['nama_tarif'] ?></h6>
				                	<span class="text-muted">Biaya Konsultasi</span>
			                	</td>
				                <td><b>Rp. </b><?= number_format($td['harga_tarif'], 0, ".", ".") ?></td>
				                <td><?= $td['jumlah'] ?></td>
				                <td><span class="text-semibold"><b>Rp.</b> <?= number_format($td['sub_total'], 0, ".", ".") ?></span></td>
				            </tr>
									<?php endforeach ?>
				        </tbody>
								<tfoot>
									<tr>
										<td colspan="3" class="text-right" style="margin-right: 1em;">Biaya Tindakan : </td>
										<td><b>Rp. <?= number_format($pembayaran['biaya_tindakan'], 0, ".", ".") ?></b></td>
									</tr>
								</tfoot>
				    </table>
				</div>

				<div class="panel-body">
					<div class="row invoice-payment"  style="margin-bottom: 3em !important;">
						<div class="col-sm-7">
							<div class="row">
								<div class="col-sm-12">
									<div class="row">
										<div class="col-sm-12"><h6>Before</h6></div>
										<div class="col-sm-12">
											<div class="row">
												<?php foreach ($gambar_before as $gb): ?>
													<div class="col-sm-4">
														<img src="<?php echo base_url(); ?>storage/before/<?php echo $gb['gambar']; ?>" alt="<?php echo $registrasi_pasien['gambar_before']; ?>" style="height: 15em; width: 100%; object-fit: cover; object-position: center;">
													</div>
												<?php endforeach ?>
											</div>
										</div>
									</div>
								</div>
								<div class="col-sm-12">
									<div class="row">
										<div class="col-sm-12">
											<h6>After</h6>
										</div>
										<div class="col-sm-12">
											<div class="row">
												<?php foreach ($gambar_after as $ga): ?>
													<div class="col-sm-4">
														<img src="<?php echo base_url(); ?>storage/after/<?php echo $ga['gambar']; ?>" alt="<?php echo $ga['gambar']; ?>" style="height: 15em;width: 100%; object-fit: cover; object-position: center;">
													</div>
												<?php endforeach ?>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="col-sm-5">
							<div class="content-group">
								<h6>Total due</h6>
								<div class="table-responsive no-border">
									<table class="table">
										<tbody>
											<tr>
												<th>Biaya Admin</th>
												<td class="text-right">Rp. <?= number_format($registrasi_pasien['biaya_admin'], 0, ".", ".") ?></td>
											</tr>
											<tr>
												<th>Biaya ID-Card:</th>
												<td class="text-right">Rp. <?= number_format($registrasi_pasien['biaya_id_card'], 0, ".", ".") ?></td>
											</tr>
											<tr>
												<th>Biaya Tindakan:</th>
												<td class="text-right">Rp. <?= number_format($pembayaran['biaya_tindakan'], 0, ".", ".") ?></td>
											</tr>
											<tr>
												<th>Biaya Resep:</th>
												<td class="text-right">Rp. <?= number_format($pembayaran['biaya_resep'], 0, ".", ".") ?></td>
											</tr>
											<tr>
												<th>Bayar:</th>
												<td class="text-right">Rp. <?= number_format($pembayaran['bayar'], 0, ".", ".") ?></td>
											</tr>
											<tr>
												<th>Kembali:</th>
												<td class="text-right">Rp. <?= number_format($pembayaran['kembali'], 0, ".", ".") ?></td>
											</tr>
											<tr>
												<th>Total Invoice:</th>
												<td class="text-right test-primary"><h5 class="text-semibold">Rp. <?= number_format($pembayaran['total_invoice'], 0, ".", ".") ?></h5></td>
											</tr>
										</tbody>
									</table>
								</div>

							</div>
						</div>
					</div>

				</div>
			</div>
			<!-- /invoice template -->

<script>
	$(window).load(function() {

	})
</script>

</div>
</div>
<!-- /main content -->

</div>
<!-- /page content -->

</div>
<!-- /page container -->
<?php $this->load->view('admin/js'); ?>
</body>
</html>
