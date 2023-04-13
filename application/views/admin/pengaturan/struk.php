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
			<div class="panel panel-flat border-top-indigo border-top-lg">
				<div class="panel-heading">
					<h6 class="panel-title"><?php echo $title ?></h6>
				</div>


				<div class="panel-body">
					<div class="row">
						<div class="col-sm-12">

							<!-- message -->
							<?php if ($this->session->flashdata('status')): ?>
								<div class="alert alert-<?php echo $this->session->flashdata('status'); ?> no-border">
									<button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>
									<p class="message-text"><?php echo $this->session->flashdata('message'); ?></p>
							  </div>
							<?php endif ?>
							<!-- message -->

							<div class="table-responsive">
								<table class="table table-bordered table-striped table-hover" id="table-nama-barang">
									<thead>
										<tr class="bg-success">
											<th class="text-center">No</th>
											<th class="text-center">Nama Cabang</th>
											<th class="text-center">Aksi</th>
										</tr>
									</thead>
									<tbody>
										<?php if (empty($struk)): ?>
											<tr>
												<td colspan="3" class="text-center">Data Cabang Kosong</td>
											</tr>
										<?php else: ?>
											<?php foreach ($struk as $key => $s): ?>
												<tr>
													<td class="text-center"><?php echo ++$key ?></td>
													<td class="text-center"><?php echo $s['nama'] ?></td>
													<td>
														<?php
														$id_cabang = $s['id'];
														$con = $this->db->query("SELECT count(id) AS jumlah, nama, alamat, no_telp FROM pengaturan_struk WHERE id_cabang = '$id_cabang'")->row_array();
														if (!$con['jumlah'] == '0'): ?>
														<div class="text-center">
															<a href="#" class="btn btn-sm btn-icon btn-primary" data-toggle="modal" data-target="#modal_edit_struk_<?php echo $s['id'] ?>"><i class="icon-pencil position-left"></i> Edit</a>
														</div>
														<!-- Modal Edit Struk -->
														<div id="modal_edit_struk_<?php echo $s['id'] ?>" class="modal fade">
															<div class="modal-dialog">
																<div class="modal-content">
																	<div class="modal-header bg-primary">
																		<button type="button" class="close" data-dismiss="modal">&times;</button>
																		<h5 class="modal-title">Ubah Struk</h5>
																	</div>
																	<form action="<?php echo base_url('pengaturan/struk/edit_struk') ?>" method="POST">
																		<div class="modal-body">
																			<input type="text" value="<?php echo $s['id'] ?>" hidden="" name="id_cabang">
																			<div class="form-group">
																				<label class="control-label">Nama</label>
																				<input type="text" class="form-control" name="nama" value="<?php echo $con['nama']; ?>">
																			</div>
																			<div class="form-group">
																				<label class="control-label">Alamat</label>
																				<input type="text" class="form-control" name="alamat" value="<?php echo $con['alamat']; ?>">
																			</div>
																			<div class="form-group">
																				<label class="control-label">No Telp</label>
																				<input type="text" class="form-control" name="no_telp" value="<?php echo $con['no_telp']; ?>">
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
														<!-- /Modal Edit Struk -->
															<?php else: ?>
																<div class="text-center">
																	<a href="#" class="btn btn-sm btn-icon btn-success" data-toggle="modal" data-target="#modal_edit_struk_<?php echo $s['id'] ?>"><i class="icon-plus3 position-left"></i> Tambah</a>
																</div>
																<!-- Modal Edit Struk -->
																<div id="modal_edit_struk_<?php echo $s['id'] ?>" class="modal fade">
																	<div class="modal-dialog">
																		<div class="modal-content">
																			<div class="modal-header bg-success">
																				<button type="button" class="close" data-dismiss="modal">&times;</button>
																				<h5 class="modal-title">Tambah Struk</h5>
																			</div>
																			<form action="<?php echo base_url('pengaturan/struk/tambah_struk') ?>" method="POST">
																				<div class="modal-body">
																					<input type="text" value="<?php echo $s['id'] ?>" hidden="" name="id_cabang">
																					<div class="form-group">
																						<label class="control-label">Nama</label>
																						<input type="text" class="form-control" name="nama" value="-">
																					</div>
																					<div class="form-group">
																						<label class="control-label">Alamat</label>
																						<input type="text" class="form-control" name="alamat" value="-">
																					</div>
																					<div class="form-group">
																						<label class="control-label">No Telp</label>
																						<input type="text" class="form-control" name="no_telp" value="-">
																					</div>
																				</div>
																				<div class="modal-footer">
																					<button class="btn btn-link" data-dismiss="modal"><i class="icon-cross position-left"></i> Keluar</button>
																					<button type="submit" class="btn btn-success"><i class="icon-plus3 position-left"></i> Tambah</button>
																				</div>
																			</form>
																		</div>
																	</div>
																</div>
																<!-- /Modal Edit Struk -->
														<?php endif; ?>

													</td>
												</tr>
											<?php endforeach ?>

										<?php endif ?>
									</tbody>
								</table>
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
