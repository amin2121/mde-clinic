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
<?php $this->load->view('admin/poli/menu'); ?>

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


								<div class="table-responsive">
									<table class="table table-bordered table-striped" id="table-pasien">
										<thead>
											<tr class="bg-success">
												<th class="text-center">No</th>
												<th class="text-center">Nama Poli</th>
												<th class="text-center">Action</th>
											</tr>
										</thead>
										<tbody>
                      <?php
                        $no = 0;
                        foreach ($poli as $p):
                        $no++;
                      ?>
                      <tr>
                        <td><?php echo $no; ?></td>
                        <td><?php echo $p['poli_nama']; ?></td>
                        <td>
                          <a href="<?php echo base_url(); ?>poli/jadwal_dokter/atur_jadwal_dokter/<?php echo $p['poli_id']; ?>"><button type="button" class="btn btn-primary"><i class="icon-calendar position-left"></i> Atur Jadwal</button></a>
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


<script>
	$(document).ready(() => {
		$(`#table-pasien`).DataTable();
    $(`#table-booking`).DataTable();
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
