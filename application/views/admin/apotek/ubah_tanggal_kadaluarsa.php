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
<?php $this->load->view('admin/apotek/menu'); ?>

<!-- Page container -->
<div class="page-container">

	<!-- Page content -->
	<div class="page-content">

		<!-- Main content -->
		<div class="content-wrapper">
			<div class="content">

				<div class="panel panel-flat border-top-indigo border-top-lg">
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

								<form action="<?= base_url('apotek/stok_barang/action_ubah_tanggal_kadaluarsa?id_barang='. $barang['id']) ?>" class="form-horizontal" id="form-tanggal-kadaluarsa" method="POST">

									<input type="text" name="id_barang" hidden="" value="<?= $barang['id'] ?>" id="id-barang-ubah-tanggal-kadaluarsa">
									
									<div class="form-group">
										<div class="col-sm-10">
											<label class="radio-inline">
												<input type="radio" name="radio-tgl-kadalursa" id="radio-tgl-kadalursa-ada" class="styled control-success" checked="checked" onchange="set_tanggal_kadaluarsa(this)">
												<b>Ada Kadaluarsa</b>
											</label>

											<label class="radio-inline">
												<input type="radio" name="radio-tgl-kadalursa" id="radio-tgl-kadalursa-tidak" class="styled control-success" onchange="set_tanggal_kadaluarsa(this)">
												<b>Tidak Ada Kadaluarsa</b>
											</label>
										</div>
									</div>

									<div class="form-group" style="margin-bottom: 3em; margin-top: 2em" id="form-tgl-kadaluarsa-ubah-tanggal-kadaluarsa">
										<label class="control-label col-sm-2"><b>Tanggal Kadaluarsa</b></label>
										<div class="col-sm-10">
											<div class="input-group">
												<span class="input-group-addon"><i class="icon-calendar"></i></span>
												<input type="text" class="form-control datepicker" placeholder="Pick a date&hellip;" name="tanggal_kadaluarsa" id="input-tgl-kadaluarsa-ubah-tanggal-kadaluarsa" value="<?= $barang['tanggal_kadaluarsa'] ?>">
												<input type="text" value="<?= $barang['tanggal_kadaluarsa'] ?>" id="default-tgl-kadaluarsa-ubah-tanggal-kadaluarsa" hidden>
											</div>
										</div>
									</div>

									<a href="<?php echo base_url(); ?>apotek/stok_barang"><button class="btn btn-md btn-default" type="button"><i class="icon-arrow-left7 position-left"></i> Kembali</button></a>
									<button class="btn btn-md btn-warning" type="submit"><i class="icon-pencil position-left"></i> Ubah</button>
								</form>

							</div>
						</div>
					</div>
				</div>

				<script>
					function set_tanggal_kadaluarsa (e) {
						let form_tgl_kadaluarsa = $(`#form-tgl-kadaluarsa-ubah-tanggal-kadaluarsa`)
						let input_tgl_kadaluarsa = $(`#input-tgl-kadaluarsa-ubah-tanggal-kadaluarsa`)
						let value_of_tgl_kadaluarsa = input_tgl_kadaluarsa.val();
						let value_default_tgl_kadaluarsa = $(`#default-tgl-kadaluarsa-ubah-tanggal-kadaluarsa`).val()

						if(e.id == "radio-tgl-kadalursa-ada") {
							input_tgl_kadaluarsa.val(value_default_tgl_kadaluarsa)
							form_tgl_kadaluarsa.show()
						} else {
							input_tgl_kadaluarsa.val('');
							form_tgl_kadaluarsa.hide();
						}

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
