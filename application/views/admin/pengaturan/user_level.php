<!DOCTYPE html>
<html lang="en">
<head>
<?php $this->load->view('admin/css'); ?>
</head>

<body>

<?php $this->load->view('admin/nav'); ?>
<?php $this->load->view('admin/pengaturan/menu'); ?>

<!-- Page container -->
<div class="page-container">

<!-- Page content -->
<div class="page-content">

<!-- Main content -->
<div class="content-wrapper">
<div class="content">

	<div class="row">
		<div class="col-sm-12">
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

							<div class="tabbable">
								<ul class="nav nav-tabs nav-tabs-component">
									<li class="active"><a href="#user-level" data-toggle="tab">User Level</a></li>
									<li><a href="#tambah-user-level" data-toggle="tab">Tambah User Level</a></li>
								</ul>

								<div class="tab-content">
									<div class="tab-pane active" id="user-level">
										<div class="table-responsive">
											<table class="table table-bordered table-striped table-hover" id="table-nama-barang">
												<thead>
													<tr class="bg-success">
														<th class="text-center">No</th>
														<th class="text-center">User Level</th>
														<th class="text-center">Aksi</th>
													</tr>
												</thead>
												<tbody>
													<?php if (empty($user_level)): ?>
														<tr>
															<td colspan="7" class="text-center">Data User Level Kosong</td>
														</tr>
													<?php else: ?>
														<?php foreach ($user_level as $key => $ul): ?>
															<tr>
																<td class="text-center"><?= ++$key ?></td>
																<td class="text-center"><?= $ul['user_level'] ?></td>
																<td>
																	<div class="text-center">
																		<a href="#" class="btn btn-sm btn-icon btn-primary" data-toggle="modal" data-target="#modal_edit_user_level_<?= $ul['id'] ?>"><i class="icon-pencil position-left"></i> Edit</a>
																		<a href="#" class="btn btn-sm btn-icon btn-danger" data-toggle="modal" data-target="#modal_hapus_user_level_<?= $ul['id'] ?>"><i class="icon-trash position-left"></i> Hapus</a>

																	</div>
																	<!-- Modal Edit User Level -->
																	<div id="modal_edit_user_level_<?= $ul['id'] ?>" class="modal fade">
																		<div class="modal-dialog">
																			<div class="modal-content">
																				<div class="modal-header bg-primary">
																					<button type="button" class="close" data-dismiss="modal">&times;</button>
																					<h5 class="modal-title">Ubah User Level</h5>
																				</div>
																				<form action="<?= base_url('pengaturan/user_level/edit_user_level') ?>" id="form-tambah-user-level" method="POST">
																					<div class="modal-body">
																						<input type="text" value="<?= $ul['id'] ?>" hidden="" name="id_user_level">
																						<div class="form-group">
																							<label class="control-label">User Level</label>
																							<input type="text" class="form-control" name="user_level" value="<?= $ul['user_level'] ?>">
																						</div>
																					</div>
																					<div class="modal-footer">
																						<button class="btn btn-link" data-dismiss="modal"><i class="icon-cross position-left"></i> Keluar</button>
																						<button type="submit" class="btn btn-primary"><i class="icon-pencil position-left"></i> Ubah</button>
																					</div>
																				</form>
																			</div>
																		</div>
																	</div>
																	<!-- /Modal Edit User Level -->

																	<!-- Modal Hapus User Level -->
																	<div id="modal_hapus_user_level_<?= $ul['id'] ?>" class="modal fade">
																		<div class="modal-dialog">
																			<div class="modal-content">
																				<div class="modal-header bg-danger">
																					<button type="button" class="close" data-dismiss="modal">&times;</button>
																					<h5 class="modal-title">Hapus User Level</h5>
																				</div>
																				<div class="modal-body">
																					<div class="alert alert-danger no-border">
																						<p>Apakah Anda Ingin <span class="text-semibold">Menghapus</span> User Level Ini?</p>
																				    </div>
																				</div>
																				<div class="modal-footer">
																					<button class="btn btn-link" data-dismiss="modal"><i class="icon-cross position-left"></i> Keluar</button>
																					<a href="<?= base_url('pengaturan/user_level/hapus_user_level?id_user_level='. $ul['id']) ?>" class="btn btn-danger"><i class="icon-trash position-left"></i> Hapus</a>
																				</div>
																			</div>
																		</div>
																	</div>
																	<!-- /Modal Hapus User Level -->
																</td>
															</tr>
														<?php endforeach ?>

													<?php endif ?>
												</tbody>
											</table>
										</div>

									</div>
									<div class="tab-pane" id="tambah-user-level">
										<fieldset class="content-group">
											<form action="<?= base_url('pengaturan/user_level/tambah_user_level') ?>" id="form-tambah-user-level" method="POST">

												<div class="form-group">
													<label class="control-label"><b>User Level</b></label>
													<input type="text" class="form-control" placeholder="User Level" name="user_level" required>
												</div>

												<button class="btn btn-success" type="submit"><i class="icon-floppy-disk position-left"></i> Simpan</button>
											</form>
										</fieldset>

									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

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
