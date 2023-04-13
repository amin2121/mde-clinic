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
	<?php $this->load->view('admin/master_data/menu'); ?>


	<!-- Page container -->
	<div class="page-container">

		<!-- Page content -->
		<div class="page-content">


			<!-- Main content -->
			<div class="content-wrapper">

				<div class="content">

				<!-- Sidebars overview -->
				<div class="panel panel-flat border-top-success border-top-lg">
					<div class="panel-heading">
						<h5 class="panel-title">Data Diagnosa</h5>
					</div>

					<div class="panel-body">
						<?php echo $this->session->flashdata('success');?>
						<div class="tabbable">
								<ul class="nav nav-tabs">
									<li class="active"><a href="#basic-justified-tab1" data-toggle="tab" class="legitRipple" aria-expanded="true">Data Diagnosa</a></li>
									<li class=""><a href="#basic-justified-tab2" data-toggle="tab" class="legitRipple" aria-expanded="false">Tambah Diagnosa</a></li>
								</ul>

								<div class="tab-content">
									<div class="tab-pane active" id="basic-justified-tab1">
										<table class="table diagnosa">
									<thead>
										<tr>
											<th>Kode</th>
											<th>Nama Diagnosa</th>
											<th>Aksi</th>
										</tr>
									</thead>
									<tbody>
										<?php foreach ($diagnosa as $d): ?>
											<tr>
												<td><?php echo $d->diagnosa_id ?></td>
												<td><?php echo $d->diagnosa_nama ?></td>
												<td>
													<button type="button" class="btn btn-primary btn-raised" data-toggle="modal" data-target="#modal_default<?php echo $d->diagnosa_id ?>"><i class="icon-pencil position-left"></i>Edit </button>
													<!-- Basic modal -->
													<div id="modal_default<?php echo $d->diagnosa_id ?>" class="modal fade">
							<div class="modal-dialog">
								<form method="post" action="<?php echo base_url('master/Diagnosa/updateDiagnosa/'.$d->diagnosa_id);?>">
								<div class="modal-content">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal">&times;</button>
										<h5 class="modal-title">Edit Diagnosa</h5>
									</div>

									<div class="modal-body">
										<div class="form-group">
											<label for="" class="control-label"><b>Nama Diagnosa</b></label>
											<input required name="diagnosa_nama" type="text" class="form-control" value="<?php echo $d->diagnosa_nama ?>">
										</div>
									</div>

									<div class="modal-footer">
										<button type="button" class="btn btn-link" data-dismiss="modal">Tutup</button>
										<button type="submit" class="btn btn-primary">Simpan</button>
									</div>
								</div>
								</form>
							</div>
						</div>
						<!-- /basic modal -->
													<a href="<?php echo base_url('master/Diagnosa/deleteDiagnosa/'.$d->diagnosa_id) ?>" onclick="return confirm('Anda yakin ingin menghapus data ini ?');" class="btn bg-danger btn-raised legitRipple"><i class="icon-trash position-left"></i> Hapus</a>
												</td>
											</tr>
										<?php endforeach ?>
									</tbody>
								</table>
									</div>

									<div class="tab-pane" id="basic-justified-tab2">
										<form method="post" action="<?php echo base_url('master/Diagnosa/tambahDiagnosa/');?>">
									<div class="form-group">
										<label for="" class="control-label"><b>Nama Diagnosa</b></label>
										<input required name="diagnosa_nama" type="text" class="form-control">
									</div>
									<div class="form-group">
										<button type="submit" class="btn btn-primary">Simpan</button>
									</div>
								</form>
									</div>
								</div>
							</div>

					</div>
				</div>
				<!-- /sidebars overview -->

			</div>
			</div>
			<!-- /main content -->

		</div>
		<!-- /page content -->

	</div>
	<!-- /page container -->

</body>
</html>
