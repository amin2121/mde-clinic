<!DOCTYPE html>
<html lang="en">
<head>
<?php $this->load->view('admin/css'); ?>
</head>

<body>
<?php $this->load->view('admin/nav'); ?>
<?php $this->load->view('admin/laporan/menu'); ?>
<style media="screen">
  #form-tahun,
  #form-bulan{
    display: none;
  }
</style>
<!-- Page container -->
<div class="page-container">

<!-- Page content -->
<div class="page-content">

<!-- Main content -->
<div class="content-wrapper">
<div class="content">

			<div class="panel panel-flat border-top-indigo border-top-lg">
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

								<form action="<?= base_url() ?>laporan/stok/print_laporan" method="POST" class="form-horizontal" target="_blank">
									<button class="btn btn-primary" type="submit" style="margin-top: 1em;"><i class="fa fa-print position-left"></i> Print</button>
								</form>

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
<script type="text/javascript">
  $(document).ready(function(){
    $('.input-tgl').datepicker({
        dateFormat : 'dd-mm-yy',
        autoclose: true,
        language: 'fr',
        orientation: 'bottom auto',
        todayBtn: 'linked',
        todayHighlight: true
    });
  });

</script>
<!-- /page container -->
<?php $this->load->view('admin/js'); ?>
</body>
</html>
