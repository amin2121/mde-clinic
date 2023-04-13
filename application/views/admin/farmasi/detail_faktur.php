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
<?php $this->load->view('admin/farmasi/menu'); ?>

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
					<div class="heading-elements">
						<ul class="icons-list">
							<li><button class="btn btn-md btn-default" type="button" onclick="window.location.href='<?= base_url('farmasi/faktur') ?>'"><i class="icon-arrow-left7"></i> Kembali</button></li>
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
