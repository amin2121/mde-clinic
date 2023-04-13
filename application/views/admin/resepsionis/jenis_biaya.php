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
<?php $this->load->view('admin/resepsionis/menu'); ?>

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
									<li class="active"><a href="#jenis-biaya" data-toggle="tab">Jenis Biaya</a></li>
									<li><a href="#tambah-jenis-biaya" data-toggle="tab">Tambah Jenis Biaya</a></li>
								</ul>

								<div class="tab-content" style="margin-bottom: 2em !important;">
									<div class="tab-pane active" id="jenis-biaya">
										<div class="table-responsive">
											<table class="table table-bordered table-striped" id="table-jenis-biaya">
												<thead>
													<tr class="bg-success">
														<th class="text-center">No</th>
														<th class="text-center">Nama</th>
														<th class="text-center">Aksi</th>
													</tr>
												</thead>
												<tbody>
												</tbody>
											</table>
										</div>
										<br>
										<div class="pagination"></div>
									</div>

									<div class="tab-pane" id="tambah-jenis-biaya">
										<fieldset class="content-group">
											<legend class="text-semibold">Tambah Jenis Biaya</legend>
											<form action="<?= base_url('resepsionis/keuangan/tambah_jenis_biaya') ?>" method="POST">

												<div class="form-group">
													<label class="control-label">Nama</label>
													<input type="text" class="form-control" placeholder="Nama" name="nama">
												</div>

											<button class="btn btn-md btn-success btn-icon" style="margin-top: 3em;"><i class="icon-floppy-disk position-left"></i> Tambah</button>
											</form>
										</fieldset>
									</div>

								</div>
							</div>
						</div>
					</div>
				</div>
			</div>


<script>
	$(document).ready(() => {
	})

	$(window).load(function() {
		$.ajax({
			url : '<?= base_url('resepsionis/keuangan/get_jenis_biaya_ajax') ?>',
			method : 'GET',
			dataType : 'json',
			success : function(res) {
				let row = '';
				if(res.status) {
					let i = 0;
					for(const item of res.data) {
						row +=	`
							<tr>
								<td class="text-center">${++i}</td>
								<td class="text-center">${item.nama}</td>
								<td>
									<div class="text-center">
										<a href="#" class="btn btn-sm btn-icon btn-info" data-toggle="modal" data-target="#modal_edit_jenis_biaya-${item.id}"><i class="icon-pencil position-left"></i> Edit</a>
										<a href="#" class="btn btn-sm btn-icon btn-danger" data-toggle="modal" data-target="#modal_hapus_jenis_biaya-${item.id}"><i class="icon-trash position-left"></i> Hapus</a>
									</div>

									<!-- Modal Ubah Jenis Biaya -->
									<div id="modal_edit_jenis_biaya-${item.id}" class="modal fade">
										<div class="modal-dialog">
											<div class="modal-content">
												<div class="modal-header bg-primary">
													<button type="button" class="close" data-dismiss="modal">&times;</button>
													<h5 class="modal-title">Ubah Jenis Biaya</h5>
												</div>
												<form action="<?= base_url('resepsionis/keuangan/ubah_jenis_biaya') ?>" method="POST">
												<div class="modal-body">
													<input type="text" name="id" value="${item.id}" hidden>
														<div class="form-group">
															<label class="control-label">Nama</label>
															<input type="text" class="form-control" name="nama" value="${item.nama}">
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
									<!-- /Modal Ubah Jenis Biaya -->

									<!-- Modal Ubah Jenis Biaya -->
									<div id="modal_hapus_jenis_biaya-${item.id}" class="modal fade">
										<div class="modal-dialog">
											<div class="modal-content">
												<div class="modal-header bg-danger">
													<button type="button" class="close" data-dismiss="modal">&times;</button>
													<h5 class="modal-title">Hapus Jenis Biaya</h5>
												</div>
												<form action="<?= base_url('resepsionis/keuangan/hapus_jenis_biaya') ?>" method="GET">
												<div class="modal-body">
													<input type="text" name="id" value="${item.id}" hidden="">
													<div class="alert alert-danger no-border">
														<p>Apakah Anda Ingin <span class="text-semibold">Menghapus</span> ${item.nama} ?</p>
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
									<!-- /Modal Ubah Jenis Biaya -->

								</td>
							</tr>
					`
					}
				} else {
					row += `
						<tr>
							<td colspan="3" class="text-semibold text-center">${res.message}</td>
						</tr>
					`
				}

				$(`#table-jenis-biaya tbody`).html(row);
				paging();
			},
		})
	})

	function paging($selector){
		var jumlah_tampil = '10';

	    if(typeof $selector == 'undefined'){
	        $selector = $("#table-jenis-biaya tbody tr");
	    }
			window.tp = new Pagination('.pagination', {
					itemsCount:$selector.length,
	        pageSize : parseInt(jumlah_tampil),
	        onPageSizeChange: function (ps) {
	            console.log('changed to ' + ps);
	        },
	        onPageChange: function (paging) {
	            var start = paging.pageSize * (paging.currentPage - 1),
	                end = start + paging.pageSize,
	                $rows = $selector;

	            $rows.hide();

	            for (var i = start; i < end; i++) {
	                $rows.eq(i).show();
	            }
	        }
	    });
	}
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
