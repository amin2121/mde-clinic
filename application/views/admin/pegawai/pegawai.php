<!DOCTYPE html>
<html lang="en">
<head>
	<?php $this->load->view('admin/css'); ?>
	<script src="<?php echo base_url('assets/js/plugins/tables/datatables/datatables.min.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo base_url('assets/js/plugins/notifications/jgrowl.min.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo base_url('assets/js/plugins/ui/moment/moment.min.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo base_url('assets/js/plugins/pickers/daterangepicker.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo base_url('assets/js/plugins/pickers/anytime.min.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo base_url('assets/js/plugins/pickers/pickadate/picker.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo base_url('assets/js/plugins/pickers/pickadate/picker.date.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo base_url('assets/js/plugins/pickers/pickadate/picker.time.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo base_url('assets/js/plugins/pickers/pickadate/legacy.js'); ?>"></script>
	<script type="text/javascript">
		$(function() {
			$('.pegawai').DataTable();
			$('.datepicker').pickadate({
		      format: 'yyyy-mm-dd',
		      selectMonths: true,
		      selectYears: 60,
		      max: true
		    });
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
							<h5 class="panel-title">Data Pegawai</h5>
						</div>

						<div class="panel-body">
							<?php echo $this->session->flashdata('success');?>
							<div class="tabbable">
								<ul class="nav nav-tabs">
									<li class="active"><a href="#basic-justified-tab1" data-toggle="tab" class="legitRipple" aria-expanded="true">Data Pegawai</a></li>
									<li class=""><a href="#basic-justified-tab2" data-toggle="tab" class="legitRipple" aria-expanded="false">Tambah Pegawai</a></li>
								</ul>

								<div class="tab-content">
									<div class="tab-pane active" id="basic-justified-tab1">
										<table class="table pegawai">
											<thead>
												<tr>
													<th>Kode</th>
													<th>Nama</th>
													<th>Jabatan</th>
													<th>Telepon</th>
													<th>Cabang</th>
													<th>Aksi</th>
												</tr>
											</thead>
											<tbody>
												<?php foreach ($pegawai as $p): ?>
													<tr>
														<td><?php echo $p->pegawai_id ?></td>
														<td><?php echo $p->nama ?></td>
														<td><?php echo $p->jabatan_nama ?></td>
														<td><?php echo $p->telepon ?></td>
														<td><?php echo $p->cabang_nama ?></td>
														<td>
															<button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modal_default<?php echo $p->pegawai_id ?>"><i class="icon-pencil position-left"></i>Edit </button>
															<!-- Basic modal -->
															<div id="modal_default<?php echo $p->pegawai_id ?>" class="modal fade">
																<div class="modal-dialog modal-lg">
																	<form method="post" action="<?php echo base_url('pegawai/Pegawai/updatePegawai/'.$p->pegawai_id);?>">
																	<div class="modal-content">
																		<div class="modal-header bg-primary">
																			<button type="button" class="close" data-dismiss="modal">&times;</button>
																			<h5 class="modal-title">Ubah Pegawai</h5>
																		</div>

																		<div class="modal-body">
																			<div class="form-group">
																				<label for="" class="control-label"><b>Nama Pegawai</b></label>
																				<input required name="nama" type="text" class="form-control" value="<?php echo $p->nama ?>">
																			</div>
																			<div class="form-group">
																				<label for="" class="control-label"><b>Cabang</b></label>
																				<select name="id_cabang" id="" class="bootstrap-select" data-width="100%">
																					<?php foreach ($cabang as $c): ?>
																						<option <?php if ($c['id'] == $p->id_cabang): ?>
																							selected
																						<?php endif ?> value="<?php echo $c['id']; ?>"><?php echo $c['nama']; ?></option>
																					<?php endforeach ?>
																				</select>
																			</div>
																			<div class="form-group">
																				<label for="" class="control-label"><b>Jabatan</b></label>
																				<select name="jabatan_id" id="" class="bootstrap-select" data-width="100%">
																						<?php foreach ($jabatan as $j): ?>
																							<option <?php if ($j->jabatan_id == $p->jabatan_id): ?>
																								selected
																							<?php endif ?> value="<?php echo $j->jabatan_id ?>"><?php echo $j->jabatan_nama; ?></option>
																						<?php endforeach ?>
																					</select>
																			</div>
																			<div class="form-group">
																				<label for="" class="control-label"><b>Tempat Lahir</b></label>
																				<input type="text" class="form-control" value="<?php echo $p->tempat_lahir ?>" name="tempat_lahir">
																			</div>
																			<div class="form-group">
																				<label for="" class="control-label"><b>Tanggal Lahir</b></label>
																				<input type="text" class="form-control datepicker" value="<?php echo $p->tgl_lahir ?>" name="tgl_lahir">
																			</div>
																			<div class="form-group">
																				<label for="" class="control-label"><b>Telepon</b></label>
																				<input type="text" class="form-control" value="<?php echo $p->telepon ?>" name="telepon">
																			</div>
																			<div class="form-group">
																				<label for="" class="control-label"><b>Alamat</b></label>
																				<textarea name="alamat" class="form-control" id="" cols="30" rows="2"><?php echo $p->alamat ?></textarea>
																			</div>
																			<div class="form-group">
																				<label for="" class="control-label"><b>Username</b></label>
																				<input type="text" class="form-control" value="<?php echo $p->username ?>" name="username">
																			</div>
																			<div class="form-group">
																				<label for="" class="control-label"><b>Password</b></label>
																				<input type="text" class="form-control" value="<?php echo $p->password ?>" name="password">
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
															<a data-toggle="modal" data-target="#modal_hapus_pegawai_<?php echo $p->pegawai_id; ?>" class="btn btn-sm btn-danger"><i class="icon-trash position-left"></i> Hapus</a>
															<!-- Modal Hapus Pemasukan -->
															<div id="modal_hapus_pegawai_<?php echo $p->pegawai_id; ?>" class="modal fade">
																<div class="modal-dialog">
																	<div class="modal-content">
																		<div class="modal-header bg-danger">
																			<button type="button" class="close" data-dismiss="modal">&times;</button>
																			<h5 class="modal-title">Hapus Pegawai</h5>
																		</div>
																		<form action="<?php echo base_url('pegawai/Pegawai/deletePegawai/'.$p->pegawai_id) ?>" method="GET">
																		<div class="modal-body">
																			<div class="alert alert-danger no-border">
																				<p>Apakah Anda Ingin <span class="text-semibold">Menghapus</span> Pegawai Ini ?</p>
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
										<form action="<?php echo base_url('pegawai/Pegawai/insertPegawai') ?>" method="post">
											<div class="form-group">
												<label for="" class="control-label"><b>Nama Pegawai</b></label>
												<input type="text" class="form-control" name="nama" placeholder="Nama Pegawai">
											</div>
											<div class="form-group">
												<label for="" class="control-label"><b>Cabang</b></label>
												<select name="id_cabang" class="bootstrap-select" data-width="100%">
													<?php foreach ($cabang as $c): ?>
														<option value="<?php echo $c['id']; ?>"><?php echo $c['nama']; ?></option>
													<?php endforeach ?>
												</select>
											</div>
											<div class="form-group">
												<label for="" class="control-label"><b>Jabatan</b></label>
												<select name="jabatan_id" class="bootstrap-select" data-width="100%">
													<?php foreach ($jabatan as $j): ?>
														<option value="<?php echo $j->jabatan_id ?>"><?php echo $j->jabatan_nama; ?></option>
													<?php endforeach ?>
												</select>
											</div>
											<div class="form-group">
												<label for="" class="control-label"><b>Tempat Lahir</b></label>
												<input type="text" class="form-control" name="tempat_lahir" placeholder="Tempat Lahir">
											</div>
											<div class="form-group">
												<label for="" class="control-label"><b>Tanggal Lahir</b></label>
												<input type="text" name="tgl_lahir" class="form-control datepicker" placeholder="Tanggal Lahir">
											</div>
											<div class="form-group">
												<label for="" class="control-label"><b>Telepon</b></label>
												<input type="text" class="form-control" name="telepon" placeholder="Telepon">
											</div>
											<div class="form-group">
												<label for="" class="control-label"><b>Alamat</b></label>
												<textarea name="alamat" class="form-control" id="" cols="30" rows="2" placeholder="Alamat"></textarea>
											</div>
											<div class="form-group">
												<label for="" class="control-label"><b>Username</b></label>
												<input type="text" class="form-control" name="username" placeholder="Username">
											</div>
											<div class="form-group">
												<label for="" class="control-label"><b>Password</b></label>
												<input type="text" class="form-control" name="password" placeholder="Password">
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
