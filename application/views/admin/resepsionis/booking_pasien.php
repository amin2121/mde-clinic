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
<?php $this->load->view('admin/resepsionis/menu'); ?>

<!-- Page container -->
<div class="page-container">

<!-- Page content -->
<div class="page-content">

<!-- Main content -->
<div class="content-wrapper">
<div class="content">

	<div class="panel panel-flat border-top-success border-top-lg">
		<div class="panel-heading">
			<h6 class="panel-title"><?= $title ?></h6>
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
					
					<div class="table-responsive">
						<table class="table table-bordered table-striped" id="table-booking">
							<thead>
								<tr class="bg-success">
									<th class="text-center">No</th>
									<th class="text-center">Nama Pasien</th>
									<th class="text-center">Tujuan Poli</th>
									<th class="text-center">Tanggal & Waktu Boking</th>
									<th class="text-center">Action</th>
								</tr>
							</thead>
							<tbody>
								<?php
									$no = 0;
									foreach ($konfirmasi_booking as $b):
									$no++;
								 ?>
								 <tr>
									 <td><?php echo $no; ?></td>
									 <td><?php echo $b['nama_pasien']; ?></td>
									 <td><?php echo $b['poli_nama']; ?></td>
									 <td><?php echo $b['hari']; ?><br><?php echo $b['tanggal']; ?> <?php echo $b['jam']; ?></td>
									 <td>
										 <a href="#" class="btn btn-md btn-icon btn-success" data-toggle="modal" data-target="#modal_booking_<?= $b['id'] ?>"><i class="icon-checkmark"></i> Konfirmasi</a>
									 </td>
									 <div id="modal_booking_<?= $b['id'] ?>" class="modal fade">
										 <div class="modal-dialog">
											 <div class="modal-content">
												 <div class="modal-header bg-success">
													 <button type="button" class="close" data-dismiss="modal">&times;</button>
													 <h5 class="modal-title">Konfirmasi Booking</h5>
												 </div>
												 <div class="modal-body">
													 <div class="success success-danger no-border">
														 <p>Apakah Anda Ingin <span class="text-semibold">Mengkonfirmasi Booking Praktik</span> atas nama <span class="text-semibold"><?php echo $b['nama_pasien']; ?></span>?</p>
														 </div>
												 </div>
												 <div class="modal-footer">
													 <button class="btn btn-link" data-dismiss="modal"><i class="icon-cross"></i> keluar</button>
													 <a href="<?= base_url('') ?>resepsionis/konfirmasi_pasien/tambah_konfirmasi_booking/<?php echo $b['id']; ?>" class="btn btn-success"><i class="icon-checkmark"></i> Proses</a>
												 </div>
											 </div>
										 </div>
									 </div>
								 </tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>


<script>
	$(document).ready(() => {
    	$(`#table-booking`).DataTable();
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
