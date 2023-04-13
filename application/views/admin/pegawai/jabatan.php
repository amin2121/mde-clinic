<!DOCTYPE html>
<html lang="en">
<head>
	<?php $this->load->view('admin/css'); ?>
	<script src="<?php echo base_url('assets/js/plugins/tables/datatables/datatables.min.js'); ?>"></script>

	<script type="text/javascript">
		$(function() {
			$('.pegawai').DataTable();
		});
	</script>
</head>

<body>

	<?php $this->load->view('admin/nav'); ?>
	<?php $this->load->view('admin/pegawai/menu'); ?>

	<!-- Page container -->
	<div class="page-container">

		<!-- Page content -->
		<div class="page-content">

			<!-- Main content -->
			<div class="content-wrapper">
				<!-- Sidebars overview -->
				<div class="content">
					<div class="panel panel-flat border-top-success border-top-lg">
						<div class="panel-heading">
							<h5 class="panel-title">Data Jabatan</h5>
						</div>

						<div class="panel-body">
							<?php echo $this->session->flashdata('success');?>
							<div class="tabbable">
								<ul class="nav nav-tabs">
									<li class="active"><a href="#basic-justified-tab1" data-toggle="tab" class="legitRipple" aria-expanded="true">Data Jabatan</a></li>
									<li class=""><a href="#basic-justified-tab2" data-toggle="tab" class="legitRipple" aria-expanded="false">Tambah Jabatan</a></li>
								</ul>

								<div class="tab-content">
									<div class="tab-pane active" id="basic-justified-tab1">
										<table class="table pegawai">
											<thead>
												<tr>
													<th>Kode</th>
													<th>Jabatan</th>
													<th>Aksi</th>
												</tr>
											</thead>
											<tbody>
												<?php foreach ($jabatan as $p): ?>
													<tr>
														<td><?php echo $p->jabatan_id ?></td>
														<td><?php echo $p->jabatan_nama ?></td>
														<td>
															<button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modal_default<?php echo $p->jabatan_id ?>"><i class="icon-pencil position-left"></i>Edit </button>
															<!-- Basic modal -->
															<div id="modal_default<?php echo $p->jabatan_id ?>" class="modal fade">
																<div class="modal-dialog">
																	<form method="post" action="<?php echo base_url('pegawai/Jabatan/updateJabatan/'.$p->jabatan_id);?>">
																	<div class="modal-content">
																		<div class="modal-header bg-primary">
																			<button type="button" class="close" data-dismiss="modal">&times;</button>
																			<h5 class="modal-title">Ubah Jabatan</h5>
																		</div>

																		<div class="modal-body">
																			<div class="form-group">
																				<label for="" class="control-label"><b>Nama Jabatan</b></label>
																				<input required name="jabatan_nama" type="text" class="form-control" value="<?php echo $p->jabatan_nama ?>">
																			</div>
																		</div>

																		<div class="modal-footer">
																			<button type="button" class="btn btn-link" data-dismiss="modal"><i class="icon-cross position-left"></i> Keluar</button>
																			<button type="submit" class="btn btn-primary"><i class="icon-pencil position-left"></i> Ubah</button>
																		</div>
																	</div>
																	</form>
																</div>
															</div>
															<!-- /basic modal -->
															<a data-toggle="modal" data-target="#modal_hapus_jabatan_<?php echo $p->jabatan_id; ?>" class="btn btn-sm btn-danger"><i class="icon-trash position-left"></i> Hapus</a>
															<!-- Modal Hapus Pemasukan -->
															<div id="modal_hapus_jabatan_<?php echo $p->jabatan_id; ?>" class="modal fade">
																<div class="modal-dialog">
																	<div class="modal-content">
																		<div class="modal-header bg-danger">
																			<button type="button" class="close" data-dismiss="modal">&times;</button>
																			<h5 class="modal-title">Hapus Jabatan</h5>
																		</div>
																		<form action="<?php echo base_url('pegawai/Jabatan/deleteJabatan/'.$p->jabatan_id) ?>" method="GET">
																		<div class="modal-body">
																			<div class="alert alert-danger no-border">
																				<p>Apakah Anda Ingin <span class="text-semibold">Menghapus</span> Jabatan Ini ?</p>
																		    </div>
																		</div>
																		<div class="modal-footer">
																			<button class="btn btn-link" data-dismiss="modal"><i class="icon-cross position-left"></i> Keluar</button>
																			<button type="submit" class="btn btn-danger"><i class="icon-bin position-left"></i> Hapus</button>
																		</div>
																		</form>
																	</div>
																</div>
															</div>
															<!-- /Modal Hapus Pemasukan -->
														</td>
													</tr>
												<?php endforeach ?>
											</tbody>
										</table>
									</div>

									<div class="tab-pane" id="basic-justified-tab2">
										<form action="<?php echo base_url('pegawai/Jabatan/insertJabatan') ?>" method="post">
											<div class="form-group">
												<label for="" class="control-label"><b>Nama Jabatan</b></label>
												<input type="text" class="form-control" name="jabatan_nama" placeholder="Nama Jabatan">
											</div>
											<div class="form-group">
												<button class="btn btn-md btn-success btn-icon" style="margin-top: 2em;" type="submit"><i class="icon-floppy-disk position-left"></i> Simpan</button>
											</div>
										</form>
									</div>
								</div>
							</div>

						</div>
					</div>
				</div>

				<!-- /sidebars overview -->

			</div>
			<!-- /main content -->

		</div>
		<!-- /page content -->

	</div>
	<!-- /page container -->

</body>
</html>
