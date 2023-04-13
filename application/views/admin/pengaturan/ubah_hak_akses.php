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


									<form action="<?= base_url('pengaturan/hak_akses/ubah_hak_akses') ?>" id="form-tambah-hak-akses" method="POST">
										<div class="form-group">
											<label class="control-label"><b>Level</b></label>
											<input type="text" class="form-control" name="level" readonly value="<?php echo $nama_level; ?>">
										</div>
										<!-- <div class="col-md-12"> -->
											<legend class="text-bold">Pilih Hak Akses</legend>
										<!-- </div> -->
	                  <div class="panel-group content-group-lg">
	                    <?php
                        $level = $nama_level;
                        foreach ($portal as $p):
                        $id_menu_portal = $p['id'];
                        $m_portal = $this->db->query("SELECT a.* FROM pengaturan_hak_akses a WHERE level = '$level' AND id_menu = '$id_menu_portal' AND tipe_menu = 'Portal'")->row_array();
                      ?>
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
	                                  <input type="checkbox" name="id_menu_portal[]" value="<?php echo $p['id']; ?>" <?php if ($p['id'] == $m_portal['id_menu']) { echo 'checked'; } ?> class="control-primary" >
	                                  Portal <?php echo $p['nama']; ?>
	                                </label>
	                              </div>
	                            </div>
	                          <?php
	                          $med = $this->db->get_where('menu_2', array('id_menu_1' => $p['id']))->result_array();
	                          foreach ($med as $d):
                              $id_menu2 = $d['id'];
                              $level = urldecode($level);
                              $m_menu2 = $this->db->query("SELECT a.* FROM pengaturan_hak_akses a WHERE level = '$level' AND id_menu = '$id_menu2' AND tipe_menu = 'Menu 2'")->row_array();
	                          ?>
	                            <div class="col-md-4">
	                              <div class="col-md-12">
	                                <div class="checkbox">
	                                  <label>
	                                    <input type="checkbox" name="id_menu_2[]" value="<?php echo $d['id']; ?>" <?php if ($d['id'] == $m_menu2['id_menu']) { echo 'checked'; } ?> class="styled">
	                                    <?php echo $d['nama']; ?>
	                                  </label>
	                                </div>
	                              </div>
	                              <?php
                                  $met = $this->db->get_where('menu_3', array('id_menu_2' => $d['id']))->result_array();
                                  foreach ($met as $t):
                                    $id_menu3 = $t['id'];
                                    $m_menu3 = $this->db->query("SELECT a.* FROM pengaturan_hak_akses a WHERE level = '$level' AND id_menu = '$id_menu3' AND tipe_menu = 'Menu 3'")->row_array();
                                  ?>
	                                <div class="col-md-1"></div>
	                                <div class="col-md-11">
	                                  <div class="checkbox">
	                                    <label>
	                                      <input type="checkbox" name="id_menu_3[]" value="<?php echo $t['id']; ?>" <?php if ($t['id'] == $m_menu3['id_menu']) { echo 'checked'; } ?> class="styled">
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
										<a href="<?php echo base_url(); ?>pengaturan/hak_akses"><button class="btn btn-warning" type="button" style="margin-top: 2em;"><i class="icon-cross position-left"></i> Batal</button></a>
									</form>


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
