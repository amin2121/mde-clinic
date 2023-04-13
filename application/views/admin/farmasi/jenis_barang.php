<!DOCTYPE html>
<html lang="en">
<head>
<?php $this->load->view('admin/css'); ?>
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

			<div class="panel pane-flat border-top-success border-top-lg">
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
									<li class="active"><a href="#jenis-barang" data-toggle="tab">Jenis Barang</a></li>
									<li><a href="#tambah-jenis-barang" data-toggle="tab">Tambah Jenis Barang</a></li>
								</ul>

								<div class="tab-content">
									<div class="tab-pane active" id="jenis-barang">
										<div class="form-group">
											<div class="input-group">
												<input type="text" class="form-control" autocomplete="off" name="cari_jenis_barang" id="cari_jenis_barang" onkeyup="cari_jenis_barang()" placeholder="Cari Berdasarkan Nama Jenis Barang">
												<span class="input-group-btn">
													<button class="btn bg-primary" type="button" onclick="cari_jenis_barang()"><i class="fa fa-search"></i></button>
												</span>
											</div>
										</div>
										<div class="table-responsive">
											<table class="table table-striped table-bordered" id="table-jenis-barang">
												<thead>
													<tr class="bg-success">
														<th>No.</th>
														<th>Jenis Barang</th>
														<th>Action</th>
													</tr>
												</thead>
												
												<tbody>
													<tr>
														<td colspan="3" id="td-loading">
															<div class="loader">
																<img src="<?= base_url('assets/images/svg-loader/svg-loaders/vectorpaint.svg') ?>" alt="">
															</div>
														</td>
													</tr>
												</tbody>
											</table>

											<br>
											<ul class="pagination"></ul>
										</div>

									</div>
									<div class="tab-pane" id="tambah-jenis-barang">
										<fieldset class="content-group">
											<form action="<?= base_url('farmasi/master/tambah_jenis_barang') ?>" id="form-tambah-jenis-barang" method="POST">

												<div class="form-group">
													<label class="control-label"><b>Jenis barang</b></label>
													<input type="text" class="form-control" placeholder="jenis barang" name="nama_jenis_barang">
												</div>

												<button class="btn btn-success" type="submit"><i class="icon-check position-left"></i> Tambah</button>
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
		$('div.dataTables_length select').addClass('form-control');
		
		$.ajax({
			url : '<?= base_url('farmasi/master/get_jenis_barang_ajax') ?>',
			method : 'POST',
			dataType : 'json',
			beforeSend : () => {$(`#td-loading`).show()},
			success : (res) => {
				let row = ''
				if(res.status) {
					let i = 0;
					for(const item of res.data) {
						row += `
							<tr>
								<td>${++i}</td>
								<td>${item.nama_jenis_barang}</td>
								<td>

									<a href="#" class="btn btn-md btn-icon btn-primary" data-toggle="modal" data-target="#modal_ubah_jenis_barang_${item.id}"><i class="icon-pencil position-left"></i> Edit</a>
									<a href="#" class="btn btn-md btn-icon btn-danger" data-toggle="modal" data-target="#modal_hapus_jenis_barang_${item.id}"><i class="icon-trash position-left"></i> Hapus</a>

									<!-- Modal Ubah Jenis barang -->
									<div id="modal_ubah_jenis_barang_${item.id}" class="modal fade">
										<div class="modal-dialog">
											<div class="modal-content">
												<div class="modal-header bg-primary">
													<button type="button" class="close" data-dismiss="modal">&times;</button>
													<h5 class="modal-title">Ubah Jenis Barang</h5>
												</div>

												<form action="<?= base_url('farmasi/master/ubah_jenis_barang') ?>" id="form-ubah-jenis-barang" method="POST">
												<div class="modal-body">
													<input type="text" value="${item.id}" name="id_jenis_barang" hidden>
													<div class="form-group">
														<label class="control-label"><b>Jenis Barang</b></label>
														<input type="text" class="form-control" placeholder="Text input" name="nama_jenis_barang" value="${item.nama_jenis_barang}">
													</div>
												</div>

												<div class="modal-footer">
													<button class="btn btn-link" data-dismiss="modal"><i class="icon-cross position-left"></i> Keluar</button>
													<button class="btn btn-primary"><i class="icon-pencil position-left"></i> Ubah</button>
												</div>
												</form>
											</div>
										</div>
									</div>
									<!-- /Modal Ubah Jenis barang -->

									<!-- Modal Hapus Jenis barang -->
									<div id="modal_hapus_jenis_barang_${item.id}" class="modal fade">
										<div class="modal-dialog">
											<div class="modal-content">
												<div class="modal-header bg-danger">
													<button type="button" class="close" data-dismiss="modal">&times;</button>
													<h5 class="modal-title">Hapus Jenis Barang</h5>
												</div>
												<div class="modal-body">
													<div class="alert alert-danger no-border">
														<p>Apakah Anda Ingin <span class="text-semibold">Menghapus</span> Jenis Barang Ini?</p>
												    </div>
												</div>
												<div class="modal-footer">
													<button class="btn btn-link" data-dismiss="modal"><i class="icon-cross position-left"></i> keluar</button>
													<a href="<?= base_url('farmasi/master/hapus_jenis_barang?id_jenis_barang=') ?>${item.id}" class="btn btn-danger"><i class="icon-bin position-left"></i> Hapus</a>
												</div>
											</div>
										</div>
									</div>
									<!-- /Modal Hapus Jenis barang -->
								</td>
							</tr>
						`
					}
				} else {
					row += `
						<tr>
							<td colspan="6" class="text-center">${res.message}</td>
						</tr>
					`
				}
				
				// $('#popup_load').fadeOut();
				$(`#table-jenis-barang tbody`).html(row);
				pagination();
			},
			complete : () => {$(`#td-loading`).fadeOut()}
		})
	})

	function cari_jenis_barang() {
		let value_jenis_barang = $(`#cari_jenis_barang`).val();
		// $('#popup_load').show();

		$.ajax({
			url : '<?= base_url('farmasi/master/get_jenis_barang_ajax') ?>',
			method : 'POST',
			dataType : 'json',
			data : {'search' : `${value_jenis_barang}`},
			beforeSend : () => {$(`#td-loading`).show()},
			success : (res) => {
				let row = '';
				if(res.status) {
					let i = 0;
					for(const item of res.data) {
						row += `
							<tr>
								<td>${++i}</td>
								<td>${item.nama_jenis_barang}</td>
								<td>
									<a href="#" class="btn btn-md btn-icon btn-primary" data-toggle="modal" data-target="#modal_ubah_jenis_barang_${item.id}"><i class="icon-pencil position-left"></i> Edit</a>
									<a href="#" class="btn btn-md btn-icon btn-danger" data-toggle="modal" data-target="#modal_hapus_jenis_barang_${item.id}"><i class="icon-trash position-left"></i> Hapus</a>
									<!-- Modal Ubah Jenis barang -->
									<div id="modal_ubah_jenis_barang_${item.id}" class="modal fade">
										<div class="modal-dialog">
											<div class="modal-content">
												<div class="modal-header bg-primary">
													<button type="button" class="close" data-dismiss="modal">&times;</button>
													<h5 class="modal-title">Ubah Jenis Barang</h5>
												</div>

												<form action="<?= base_url('farmasi/master/ubah_jenis_barang') ?>" id="form-ubah-jenis-barang" method="POST">
												<div class="modal-body">
													<input type="text" value="${item.id}" name="id_jenis_barang" hidden>
													<div class="form-group">
														<label class="control-label">Jenis Barang</label>
														<input type="text" class="form-control" placeholder="Text input" name="nama_jenis_barang" value="${item.nama_jenis_barang}">
													</div>

												</div>

												<div class="modal-footer">
													<button class="btn btn-link" data-dismiss="modal"><i class="icon-cross position-left"></i> Keluar</button>
													<button class="btn btn-primary"><i class="icon-pencil position-left"></i> Ubah</button>
												</div>
												</form>
											</div>
										</div>
									</div>
									<!-- /Modal Ubah Jenis barang -->

									<!-- Modal Hapus Jenis barang -->
									<div id="modal_hapus_jenis_barang_${item.id}" class="modal fade">
										<div class="modal-dialog">
											<div class="modal-content">
												<div class="modal-header bg-danger">
													<button type="button" class="close" data-dismiss="modal">&times;</button>
													<h5 class="modal-title">Hapus Jenis Barang</h5>
												</div>
												<div class="modal-body">
													<div class="alert alert-danger no-border">
														<p>Apakah Anda Ingin <span class="text-semibold">Menghapus</span> Jenis Barang Ini?</p>
												    </div>
												</div>
												<div class="modal-footer">
													<button class="btn btn-link" data-dismiss="modal"><i class="icon-cross position-left"></i> keluar</button>
													<a href="<?= base_url('farmasi/master/hapus_jenis_barang?id_jenis_barang=') ?>${item.id}" class="btn btn-danger"><i class="icon-bin position-left"></i> Hapus</a>
												</div>
											</div>
										</div>
									</div>
									<!-- /Modal Hapus Jenis barang -->
								</td>
							</tr>
						`
					}
				} else {
					row += `
						<tr>
							<td colspan="6" class="text-center">${res.message}</td>
						</tr>
					`
				}

				$('#popup_load').fadeOut();
				$(`#table-jenis-barang tbody`).html(row);
				pagination();
			},
			complete : () => {$(`#td-loading`).fadeOut()}
		})
	}

	function pagination() {
		var jumlah_tampil = '10';

	    if(typeof $selector == 'undefined'){
	        $selector = $("#table-jenis-barang tbody tr");
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
