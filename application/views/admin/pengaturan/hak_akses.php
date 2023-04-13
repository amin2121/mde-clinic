<!DOCTYPE html>
<html lang="en">
<head>
<?php $this->load->view('admin/css'); ?>
<script src="<?php echo base_url('assets/js/plugins/tables/datatables/datatables.min.js'); ?>"></script>
<script type="text/javascript">
$(function() {
$('.user').DataTable();
});
</script>
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
									<li class="active"><a href="#hak-akses" data-toggle="tab">Hak Akses</a></li>
									<li><a href="#tambah-hak-akses" data-toggle="tab">Tambah Hak Akses</a></li>
								</ul>
								<div class="tab-content">
									<div class="tab-pane active" id="hak-akses">
										<div class="table-responsive">
											<table class="table table-bordered table-striped" id="table-supplier">
												<thead>
													<tr class="bg-success">
														<th class="text-center">No</th>
														<th class="text-center">Level</th>
														<th class="text-center">Action</th>
													</tr>
												</thead>
												<tbody>
													<?php
														$no = 0;
														foreach ($level as $l):
														$no++;
													?>
													<tr>
														<td><?php echo $no; ?></td>
														<td><?php echo $l['user_level']; ?></td>
														<td>
															<?php
																$user_level = $l['user_level'];
																$con = $this->db->query("SELECT count(id) AS jumlah FROM pengaturan_hak_akses WHERE level = '$user_level'")->row_array();
															 ?>
															 <?php if (!$con['jumlah'] == '0'): ?>
																 <a href="<?php echo base_url(); ?>pengaturan/hak_akses/view_ubah_hak_akses/<?php echo $l['id']; ?>/<?php echo $l['user_level']; ?>" class="btn btn-sm btn-icon btn-primary"><i class="icon-pencil position-left"></i> Edit</a>
															 <?php endif; ?>
														</td>
													</tr>
													<?php endforeach; ?>
												</tbody>
											</table>
										</div>
									</div>

									<div class="tab-pane" id="tambah-hak-akses">
										<form action="<?= base_url('pengaturan/hak_akses/tambah_hak_akses') ?>" id="form-tambah-hak-akses" method="POST">
											<div class="form-group">
												<label class="control-label"><b>Level</b></label>
												<select class="select-search" name="level">
													<?php foreach ($level as $e): ?>
														<?php
														$user_level = $e['user_level'];
														$con = $this->db->query("SELECT count(id) AS jumlah FROM pengaturan_hak_akses WHERE level = '$user_level'")->row_array();
														if ($con['jumlah'] == '0'): ?>
															<option value="<?php echo $e['user_level']; ?>"><?php echo $e['user_level']; ?></option>
														<?php endif; ?>
													<?php endforeach; ?>
												</select>
											</div>
											<!-- <div class="col-md-12"> -->
												<legend class="text-bold">Pilih Hak Akses</legend>
											<!-- </div> -->
		                  <div class="panel-group content-group-lg">
		                    <?php foreach ($portal as $p): ?>
		      							<div class="panel panel-white">
		      								<div class="panel-heading">
		      									<h6 class="panel-title">
		      										<a data-toggle="collapse" href="#collapse-group-<?php echo $p['id']; ?>"><?php echo $p['nama']; ?></a>
		      									</h6>
		      								</div>
		      								<div id="collapse-group-<?php echo $p['id']; ?>" class="panel-collapse collapse">
		      									<div class="panel-body">
		                          <div class="row">
		                            <div class="col-md-12">
		                              <div class="checkbox">
		                                <label>
		                                  <input type="checkbox" name="id_menu_portal[]" value="<?php echo $p['id']; ?>" class="control-primary">
		                                  Portal <?php echo $p['nama']; ?>
		                                </label>
		                              </div>
		                            </div>
		                          <?php
		                          $med = $this->db->get_where('menu_2', array('id_menu_1' => $p['id']))->result_array();
		                          foreach ($med as $d):
		                            $met = $this->db->get_where('menu_3', array('id_menu_2' => $d['id']))->result_array();
		                          ?>
		                            <div class="col-md-4">
		                              <div class="col-md-12">
		                                <div class="checkbox">
		                                  <label>
		                                    <input type="checkbox" name="id_menu_2[]" value="<?php echo $d['id']; ?>" class="styled">
		                                    <?php echo $d['nama']; ?>
		                                  </label>
		                                </div>
		                              </div>
		                              <?php foreach ($met as $t): ?>
		                                <div class="col-md-1"></div>
		                                <div class="col-md-11">
		                                  <div class="checkbox">
		                                    <label>
		                                      <input type="checkbox" name="id_menu_3[]" value="<?php echo $t['id']; ?>" class="styled">
		                                      <?php echo $t['nama']; ?>
		                                    </label>
		                                  </div>
		                                </div>
		                              <?php endforeach; ?>
		                            </div>
		                          <?php endforeach; ?>
		                          </div>
		      									</div>
		      								</div>
		      							</div>
		                    <?php endforeach; ?>
		      						</div>
											<button class="btn btn-success" type="submit" style="margin-top: 2em;"><i class="icon-floppy-disk position-left"></i> Simpan</button>											
										</form>
									</div>
								</div>
							</div>


						</div>
					</div>
				</div>
			</div>


<script>
	$(document).ready(() => {
		$(`#table-user`).DataTable()
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
