<!DOCTYPE html>
<html lang="en">
<head>
	<?php $this->load->view('admin/css'); ?>
	<script src="<?php echo base_url('assets/js/plugins/tables/datatables/datatables.min.js'); ?>"></script>

	<script type="text/javascript">
		$(function() {
			$('.data_pasien').DataTable();
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
				<!-- Sidebars overview -->
					<div class="panel panel-flat">
						<div class="panel-heading">
							<h5 class="panel-title">Data Pasien</h5>
						</div>

						<div class="panel-body">
							<?php echo $this->session->flashdata('success');?>
							<div class="tabbable">

								<div class="tab-content">
									<div class="tab-pane active" id="basic-justified-tab1">
										<table class="table data_pasien">
											<thead>
												<tr>
													<th>No RM</th>
													<th>Nama Pasien</th>
													<th>JK</th>
													<th>Alamat</th>
													<th>Aksi</th>
												</tr>
											</thead>
											<tbody>
                        <?php foreach ($result as $r): ?>
                          <tr>
                            <td><?php echo $r['no_rm']; ?></td>
                            <td><?php echo $r['nama_pasien']; ?></td>
                            <td><?php echo $r['jenis_kelamin']; ?></td>
                            <td><?php echo $r['alamat']; ?></td>
                            <td>
                              <button type="button" class="btn btn-warning btn-raised"><i class="icon-eye position-left"></i>Detail </button>
                            </td>
                          </tr>
                        <?php endforeach; ?>
											</tbody>
										</table>
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
