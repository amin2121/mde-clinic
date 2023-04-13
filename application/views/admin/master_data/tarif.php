<!DOCTYPE html>
<html lang="en">
<head>
	<?php $this->load->view('admin/css'); ?>
	<script src="<?php echo base_url('assets/js/plugins/tables/datatables/datatables.min.js'); ?>"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			$('.tarif').DataTable();
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
						<h5 class="panel-title">Data Tarif</h5>
					</div>

					<div class="panel-body">
						<?php echo $this->session->flashdata('success');?>
						<div class="tabbable">
								<ul class="nav nav-tabs">
									<li class="active"><a href="#basic-justified-tab1" data-toggle="tab" class="legitRipple" aria-expanded="true">Data Tarif</a></li>
									<li class=""><a href="#basic-justified-tab2" data-toggle="tab" class="legitRipple" aria-expanded="false">Tambah Tarif</a></li>
								</ul>
								<div class="tab-content">
									<div class="tab-pane active" id="basic-justified-tab1">
										<table class="table tarif">
											<thead>
												<tr>
													<th>Kode</th>
													<th>Nama Tarif</th>
													<th>Harga</th>
													<th>Aksi</th>
												</tr>
											</thead>
											<tbody>
												<?php foreach ($tarif as $d): ?>
													<tr>
														<td><?php echo $d->tarif_id ?></td>
														<td><?php echo $d->tarif_nama ?></td>
														<td><?php echo 'Rp. '.number_format($d->tarif_harga,2,',','.'); ?></td>
														<td>
															<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal_default<?php echo $d->tarif_id ?>"><i class="icon-pencil position-left"></i>Edit </button>
															<!-- Basic modal -->
															<div id="modal_default<?php echo $d->tarif_id ?>" class="modal fade">
																<div class="modal-dialog">
																	<form method="post" action="<?php echo base_url('master/Tarif/updateTarif/'.$d->tarif_id);?>">
																	<div class="modal-content">
																		<div class="modal-header bg-primary">
																			<button type="button" class="close" data-dismiss="modal">&times;</button>
																			<h5 class="modal-title">Ubah Tarif Jasa Medis</h5>
																		</div>

																		<div class="modal-body">
																			<div class="form-group">
																				<label for="" class="control-label"><b>Nama Jasa Medis</b></label>
																				<input required name="tarif_nama" type="text" class="form-control" value="<?php echo $d->tarif_nama ?>">
																			</div>
																			<div class="form-group">
																				<label for="" class="control-label"><b>Tarif Jasa Medis</b></label>
																				<div class="input-group">
																					<span class="input-group-addon"><b>Rp. </b></span>
																					<input required name="tarif_harga" type="text" class="form-control" value="<?php echo number_format($d->tarif_harga); ?>">
																				</div>
																			</div>
																		</div>

																		<div class="modal-footer">
																			<button type="button" class="btn btn-link" data-dismiss="modal"><i class="icon-cross position-left"></i> Tutup</button>
																			<button type="submit" class="btn btn-primary"><i class="icon-pencil position-left"></i> Ubah</button>
																		</div>
																	</div>
																	</form>
																</div>
															</div>
															<!-- /basic modal -->
															<a data-toggle="modal" data-target="#modal_hapus_tarif_<?php echo $d->tarif_id; ?>" class="btn btn-sm btn-danger"><i class="icon-trash position-left"></i> Hapus</a>
															<!-- Modal Hapus Tarif -->
															<div id="modal_hapus_tarif_<?php echo $d->tarif_id; ?>" class="modal fade">
																<div class="modal-dialog">
																	<div class="modal-content">
																		<div class="modal-header bg-danger">
																			<button type="button" class="close" data-dismiss="modal">&times;</button>
																			<h5 class="modal-title">Hapus Tarif</h5>
																		</div>
																		<form action="<?php echo base_url('master/Tarif/deleteTarif/'.$d->tarif_id) ?>" method="GET">
																		<div class="modal-body">
																			<div class="alert alert-danger no-border">
																				<p>Apakah Anda Ingin <span class="text-semibold">Menghapus</span> Tarif Ini ?</p>
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
															<!-- /Modal Hapus Tarif -->
														</td>
													</tr>
												<?php endforeach ?>
											</tbody>
										</table>
									</div>
									<div class="tab-pane" id="basic-justified-tab2">
										<form method="post" action="<?php echo base_url('master/Tarif/tambahTarif/');?>">
											<div class="form-group">
												<label class="control-label"><b>Nama Jasa Medis</b></label>
												<input required name="tarif_nama" placeholder="Nama Jasa Medis" type="text" class="form-control">
											</div>
											<div class="form-group">
												<label class="control-label"><b>Tarif Jasa Medis</b></label>
												<div class="input-group">
													<span class="input-group-addon"><b>Rp. </b></span>
													<input required name="tarif_harga" type="text" onkeyup="FormatCurrency(this);" placeholder="Tarif Jasa Medis" class="form-control">
												</div>
											</div>
											<div class="form-group">
												<button class="btn btn-md btn-success btn-icon" style="margin-top: 1em;" type="submit"><i class="icon-floppy-disk position-left"></i> Simpan</button>
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
<?php $this->load->view('admin/js'); ?>
</body>
</html>
