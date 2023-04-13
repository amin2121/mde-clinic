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

			<div class="panel panel-flat">
				<div class="panel-heading">
					<h6 class="panel-title"><?= $title ?></h6>
					<div class="heading-elements">
						<ul class="icons-list">
							<li><button class="btn btn-md btn-default" type="button" onclick="window.location.href='<?= base_url('apotek/faktur') ?>'"><i class="icon-arrow-left7"></i> Kembali</button></li>
						</ul>
					</div>
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
									<li class="active"><a href="#detail-faktur" data-toggle="tab">Detail Faktur</a></li>
								</ul>

								<div class="tab-content">
									<div class="tab-pane active" id="detail-faktur">
										<div class="table-responsive">
											<table class="table table-bordered table-striped" id="table-detail-faktur">
												<thead>
													<tr class="bg-success">
														<th class="text-center">Kode Barang</th>
														<th class="text-center">Nama Barang</th>
														<th class="text-center">Harga Awal</th>
														<th class="text-center">Harga Jual</th>
														<th class="text-center">Laba</th>
														<th class="text-center">Jumlah Beli</th>
														<th class="text-center">Tanggal Kadaluarsa</th>
														<th class="text-center">Action</th>
													</tr>
												</thead>
												<tbody>
													<?php if (empty($detail_faktur)): ?>
														<tr>
															<td colspan="8" class="text-center">Detail Faktur Kosong</td>
														</tr>
													<?php else: ?>
														<?php foreach ($detail_faktur as $key => $df): ?>
															<tr>
																<td class="text-center"><?= $df['kode_barang'] ?></td>
																<td class="text-center"><?= $df['nama_barang'] ?></td>
																<td class="text-right"><b>Rp. </b><?= number_format($df['harga_awal'],2,',','.'); ?></td>
																<td class="text-right"><b>Rp. </b><?= number_format($df['harga_jual'],2,',','.'); ?></td>
																<td class="text-right"><b>Rp. </b><?= number_format($df['laba'],2,',','.');  ?></td>
																<td class="text-center"><?= $df['jumlah_beli'] ?></td>
																<td class="text-center"><?= ($df['tanggal_kadaluarsa'] == "" || $df['tanggal_kadaluarsa'] == 0) ? 'Tidak Ada Tanggal Kadaluarsa' : date('d M, Y', strtotime($df['tanggal_kadaluarsa'])) ?></td>
																<td>
																	<div class="text-center">
																		<a href="#" class="btn btn-xs btn-icon btn-danger" data-toggle="modal" data-target="#modal_hapus_detail_faktur_<?= $df['id'] ?>"><i class="icon-bin position-left"></i> Hapus</a>
																	</div>

																	<!-- Modal Hapus Jenis barang -->
																	<div id="modal_hapus_detail_faktur_<?= $df['id'] ?>" class="modal fade">
																		<div class="modal-dialog">
																			<div class="modal-content">
																				<div class="modal-header bg-danger">
																					<button type="button" class="close" data-dismiss="modal">&times;</button>
																					<h5 class="modal-title"><i class="icon-bin"></i> &nbsp;Hapus barang</h5>
																				</div>
																				<div class="modal-body">
																					<div class="alert alert-danger no-border">
																						<p>Apakah Anda Ingin <span class="text-semibold">Menghapus</span> Barang Ini?</p>
																				    </div>
																				</div>
																				<div class="modal-footer">
																					<button class="btn btn-link" data-dismiss="modal"><i class="icon-cross"></i> keluar</button>
																					<a href="<?= base_url('apotek/faktur/hapus_detail_faktur?id_faktur='. $df['id_faktur'].'&id_detail_faktur='.$df['id']) ?>" class="btn btn-danger"><i class="icon-bin"></i> Hapus</a>
																				</div>
																			</div>
																		</div>
																	</div>
																	<!-- /Modal Hapus Jenis barang -->
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
	</div>
</div>


<script>
	$(document).ready(() => {
		$(`#table-detail-faktur`).DataTable();
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
