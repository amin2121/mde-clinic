<!DOCTYPE html>
<html lang="en">
<head>
<?php $this->load->view('admin/css'); ?>
<script src="<?php echo base_url('assets/js/plugins/tables/datatables/datatables.min.js'); ?>"></script>
<script type="text/javascript">
$(function() {
$('.diagnosa').DataTable();
});
</script>
</head>

<body>

<?php $this->load->view('admin/nav'); ?>
<?php $this->load->view('admin/poli/menu'); ?>

<!-- Page container -->
<div class="page-container">

<!-- Page content -->
<div class="page-content">

<!-- Main content -->
<div class="content-wrapper">
<div class="content">

	<div class="row">
		<div class="col-sm-12">
			<div class="row">

				<div class="col-sm-6">
					<div class="panel panel-flat border-left-lg border-left-success panel-dashboard">
						<div class="panel-body panel-dashboard-item">
							<!-- <h1><i class="icon-person"></i> Supplier</h1> -->
							<p class="title-panel-dashboard"><i class="icon-person"></i> Jumlah Antrian Hari Ini</p>
							<h3 class="text-right text-content"><span id="jumlah_antrian"></span> Orang</h3>
						</div>
					</div>
				</div>

				<div class="col-sm-6">
					<div class="panel panel-flat border-left-lg border-left-success panel-dashboard">
						<div class="panel-body panel-dashboard-item">
							<!-- <h1><i class="icon-person"></i> Supplier</h1> -->
							<p class="title-panel-dashboard"><i class="fa fa-clock-o"></i> Tanggal / Waktu</p>
							<h3 class="text-right text-content">
								<span id="tanggal"></span> /
								<span id="bulan"></span> /
								<span id="tahun"></span>&nbsp;&nbsp;
								<span id="jam"></span> :
								<span id="menit"></span> :
								<span id="detik"></span>
							</h3>
						</div>
					</div>
				</div>

			</div>

		</div>
	</div>

	<div class="row">
		<div class="col-sm-12">
			<div class="panel panel-flat border-top-success border-top-lg">
				<div class="panel-heading">
					<h6 class="panel-title"><?= $subtitle ?></h6>
				</div>


				<div class="panel-body">
					<div class="row">
						<div class="col-sm-12">

							<!-- message -->
							<?php if ($this->session->flashdata('status')): ?>
								<div class="alert alert-<?= $this->session->flashdata('status'); ?> no-border">
									<button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>
									<p class="message-text"><?= $this->session->flashdata('message'); ?></p>
							    </div>
							<?php endif ?>
							<!-- message -->

							<!-- <div class="form-search form-horizontal">
								<div class="row">
									<div class="col-sm-6">
										<div class="form-group">
											<label class="control-label col-sm-2">Tanggal Dari</label>
											<div class="col-sm-10">
												<input type="text" class="form-control input-tgl" autocomplete="off" name="tgl_dari" id="input-tgl-dari">
											</div>
										</div>
									</div>
									<div class="col-sm-6">
										<div class="form-group">
											<label class="control-label col-sm-3">Tanggal Sampai</label>
											<div class="col-sm-8">
												<input type="text" class="form-control input-tgl" name="tgl_sampai" id="input-tgl-sampai" autocomplete="off">
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-12">
									 	<button class="btn btn-md btn-primary" type="button" onclick="cari_rekam_medis()"><i class="icon-search4 position-left"></i> Cari</button>
									 	<button class="btn btn-md btn-success" type="button" onclick="data_rekam_medis()"><i class="icon-eye position-left"></i> Lihat Antrian Hari Ini</button>
									</div>
								</div>
							</div> -->

							<div class="table-responsive">
								<table class="table table-striped table-bordered table-hover" id="table_antrian">
									<thead>
										<tr class="bg-indigo">
											<th class="text-center">No. </th>
											<th class="text-center">No Antrean</th>
											<th class="text-center">No RM</th>
											<th class="text-center">Nama Pasien</th>
											<th class="text-center">Usia Pasien</th>
											<th class="text-center">Jenis Kelamin</th>
											<th class="text-center">Aksi</th>
										</tr>
									</thead>
									<tbody>
									</tbody>
								</table>
							</div>

						</div>
					</div>
				</div>
			</div>
		</div>
	</div>


<script>
	$(document).ready(() => {
		$(`#table-nama-barang`).DataTable();
		get_antrian();
	})

	window.setTimeout("waktu()", 1000);

	function waktu() {
		var waktu = new Date();
		setTimeout("waktu()", 1000);
		document.getElementById("jam").innerHTML = (parseInt(waktu.getHours()) < 10) ? `0${waktu.getHours()}` : waktu.getHours();
		document.getElementById("menit").innerHTML = (parseInt(waktu.getMinutes()) < 10) ? `0${waktu.getMinutes()}` : waktu.getMinutes();
		document.getElementById("detik").innerHTML = (parseInt(waktu.getSeconds()) < 10) ? `0${waktu.getSeconds()}` : waktu.getSeconds();

		var months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
		document.getElementById("tanggal").innerHTML = (parseInt(waktu.getDate()) < 10) ? `0${waktu.getDate()}` : waktu.getDate();
		document.getElementById("bulan").innerHTML = months[waktu.getMonth()];
		document.getElementById("tahun").innerHTML = waktu.getFullYear();
	}

	let action_tambah_rekam_medis = (id) => {
		window.location.href = `<?= base_url('poli/poli/tambah_tindakan/') ?>${id}`;
	}

	function get_antrian() {
		let loading = `										
					<tr>
						<td colspan="7" id="td-loading">
							<div class="loader">
								<img src="<?= base_url('assets/images/svg-loader/svg-loaders/vectorpaint.svg') ?>" alt="">
							</div>
						</td>
					</tr>`

		$(`#table_antrian tbody`).html(loading);
		$.ajax({
			url : '<?= base_url('poli/poli/get_antrian') ?>',
			data : {'id_poli' : `<?= $id_poli ?>`},
			method : 'POST',
			dataType : 'json',
			success : (res) => {
				let html = ''
				$(`#td-loading`).fadeOut();
				$(`#jumlah_antrian`).text(res?.count?.jumlah == undefined ? 0 : res.count.jumlah);
				if(res.status) {
					let i = 0;
					for(const item of res.data) {
						html += `
							<tr>
								<td class="text-center" onclick="action_tambah_rekam_medis(${item.id_registrasi});" style="cursor: pointer">${++i}</td>
								<td class="text-center" onclick="action_tambah_rekam_medis(${item.id_registrasi});" style="cursor: pointer">00${++i}</td>
								<td class="text-center" onclick="action_tambah_rekam_medis(${item.id_registrasi});" style="cursor: pointer">${item.no_rm}</td>
								<td class="text-center" onclick="action_tambah_rekam_medis(${item.id_registrasi});" style="cursor: pointer">${item.nama_pasien}</td>
								<td class="text-center" onclick="action_tambah_rekam_medis(${item.id_registrasi});" style="cursor: pointer">${item.umur}</td>
								<td class="text-center" onclick="action_tambah_rekam_medis(${item.id_registrasi});" style="cursor: pointer"><span class="label label-info">${item.jenis_kelamin}</span></td>
								<td>
									<div class="text-center">
										<a href="#" class="btn btn-xs btn-icon btn-danger" data-toggle="modal" data-target="#modal_hapus_atrean_${item.id}"><i class="icon-trash position-left"></i> Hapus</a>
									</div>

									<!-- Modal Hapus Antrean -->
									<div id="modal_hapus_atrean_${item.id}" class="modal fade">
										<div class="modal-dialog">
											<div class="modal-content">
												<div class="modal-header bg-danger">
													<button type="button" class="close" data-dismiss="modal">&times;</button>
													<h5 class="modal-title">Hapus Antrean</h5>
												</div>

												<form action="<?= base_url('poli/poli/hapus_antrean') ?>" method="POST">
													<div class="modal-body">
														<div class="alert alert-danger no-border">
															<p>Apakah Anda Ingin <span class="text-semibold">Menghapus</span> Antrean Ini?</p>
													    </div>

														<input type="hidden" name="id_antrean" value="${item.id}">
														<input type="hidden" name="id_poli" value="<?= $id_poli ?>">
														<input type="hidden" name="id_registrasi" value="${item.id_registrasi}">
													</div>

													<div class="modal-footer">
														<button class="btn btn-link" data-dismiss="modal"><i class="icon-cross position-left"></i> Keluar</button>
														<button class="btn btn-danger" type="submit"><i class="icon-bin position-left"></i> Hapus</button>
													</div>
												</form>
											</div>
										</div>
									</div>
									<!-- End Modal Hapus Barang -->
								</td>
							</tr>
						`;
					}
				} else {
					html = `
						<tr>
							<td colspan="7" class="text-center">${res.message}</td>
						</tr>
					`;
				}

				$(`#table_antrian tbody`).html(html);
			}
		})
	}

	function get_antrian_by_tanggal() {
		$.ajax({
			url : '<?= base_url('poli/poli/') ?>',
			data : {},
			dataType : 'json',
			method : 'POST',
			success : (res) => {

			}
		})
	}

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
