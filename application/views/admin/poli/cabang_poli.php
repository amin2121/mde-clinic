<!DOCTYPE html>
<html lang="en">
<head>
	<?php $this->load->view('admin/css'); ?>
</head>

<body>

	<?php $this->load->view('admin/nav'); ?>
	<?php $this->load->view('admin/poli/menu'); ?>

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
						<h5 class="panel-title"><?php echo $title; ?></h5>
					</div>

					<div class="panel-body">
						<div class="tabbable">
								<ul class="nav nav-tabs">
									<?php
									$no = 0;
									foreach ($cabang as $c):
									$no++;
									?>
									<?php if ($no == '1'): ?>
										<li class="active"><a href="#basic-justified-<?php echo $c['id']; ?>" data-toggle="tab" class="legitRipple" aria-expanded="true"><?php echo $c['nama']; ?></a></li>
										<?php else: ?>
										<li><a href="#basic-justified-<?php echo $c['id']; ?>" data-toggle="tab" class="legitRipple" aria-expanded="true"><?php echo $c['nama']; ?></a></li>
									<?php endif; ?>
									<?php endforeach; ?>
								</ul>

								<div class="tab-content">
									<?php
									$no = 0;
									foreach ($cabang as $c):
									$no++;
									?>
									<?php if ($no == '1'): ?>
										<div class="tab-pane active" id="basic-justified-<?php echo $c['id']; ?>">
											<?php
												$id_cabang = $c['id'];
												$poli = $this->db->get_where('data_poli', array('id_cabang' => $id_cabang))->result_array();
											 ?>
											 <?php foreach ($poli as $p): ?>
													<div class="col-sm-3">
														<a href="<?= base_url() ?>poli/poli/antrian_view/<?php echo $p['poli_id']; ?>">
														<div class="panel panel-bordered panel-success">
															<div class="panel-body">

																<h3 class="title-item text-center">
																	<i class="icon-dots" style="font-size: 200%"></i><br><?php echo $p['poli_nama']; ?>
																</h3>
															</div>
														</div>
													</a>
												</div>
											 <?php endforeach; ?>
										</div>
										<?php else: ?>
											<div class="tab-pane" id="basic-justified-<?php echo $c['id']; ?>">
												<?php
													$id_cabang = $c['id'];
													$poli = $this->db->get_where('data_poli', array('id_cabang' => $id_cabang))->result_array();
												 ?>
												 <?php foreach ($poli as $p): ?>
														<div class="col-sm-3">
															<a href="<?= base_url() ?>poli/poli/antrian_view/<?php echo $p['poli_id']; ?>">
															<div class="panel panel-bordered panel-success">
																<div class="panel-body">

																	<h3 class="title-item text-center">
																		<i class="icon-dots" style="font-size: 200%"></i><br><?php echo $p['poli_nama']; ?>
																	</h3>
																</div>
															</div>
														</a>
													</div>
												 <?php endforeach; ?>
											</div>
									<?php endif; ?>
									<?php endforeach; ?>
								</div>
							</div>

					</div>
				</div>
				<!-- /sidebars overview -->


			</div>
			<!-- /main content -->

		</div>
		</div>
		<!-- /page content -->

	</div>
	<!-- /page container -->

</body>
</html>
