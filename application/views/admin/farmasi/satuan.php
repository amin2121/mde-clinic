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
													<li class="active"><a href="#satuan" data-toggle="tab">Satuan</a></li>
													<li><a href="#tambah-satuan" data-toggle="tab">Tambah Satuan</a></li>
												</ul>

												<div class="tab-content">
													<div class="tab-pane active" id="satuan">
														<div class="form-group">
															<div class="input-group">
																<!-- <label class="control-label col-sm-2">Nama Barang</label> -->
																	<input type="text" class="form-control" autocomplete="off" name="satuan" id="input_satuan" onkeyup="get_satuan()" placeholder="Cari Berdasarkan Satuan">
																	<span class="input-group-btn">
																		<button class="btn bg-primary" type="button" onclick="get_satuan()"><i class="fa fa-search"></i></button>
																	</span>
															</div>
														</div>
														<div class="table-responsive">
															<table class="table table-bordered table-striped" id="table_satuan">
																<thead>
																	<tr class="bg-success">
																		<th class="text-center">No</th>
																		<th class="text-center">Satuan</th>
																		<th class="text-center">Aksi</th>
																	</tr>
																</thead>
																<tbody>

																</tbody>
															</table>
														</div>
														<br>
														<ul class="pagination"></ul>
													</div>
													<div class="tab-pane" id="tambah-satuan">
														<fieldset class="content-group">
															<form action="<?= base_url('farmasi/satuan/tambah_satuan') ?>" id="form-tambah-barang" method="POST">

																<div class="form-group">
																	<label class="control-label"><b>Satuan</b></label>
																	<input type="text" class="form-control" placeholder="Satuan" name="satuan">
																</div>

																<div class="form-group" style="margin-top: 1.5em !important;">
																	<button class="btn btn-success" type="submit"><i class="icon-floppy-disk position-left"></i> Simpan</button>
																</div>
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
	// $(`#table-nama-barang`).DataTable()
})

$(window).load(function() {
	// $('#popup_load').show();
	get_satuan();
})

function get_satuan() {
	let satuan = $(`#input_satuan`).val();

	$.ajax({
		url : '<?= base_url('farmasi/satuan/get_satuan_ajax') ?>',
		method : 'POST',
		data : {'search' : `${satuan}`},
		dataType : 'json',
		beforeSend : () => {
			let loading = `<tr id="tr-loading">
								<td colspan="7">
									<div class="loader">
										<img src="<?= base_url('assets/images/svg-loader/svg-loaders/vectorpaint.svg') ?>" alt="">
									</div>
								</td>
							</tr>`
			$(`#table_satuan tbody`).html(loading);
		},
		success : (res) => {
			let row = '';
			console.log(res);
			if(res.status) {
				let i = 0;
				for(const item of res.data) {
					row += 	`
						<tr>
							<td class="text-center">${++i}</td>
							<td class="text-center">${item.satuan}</td>
							<td>
								<div class="text-center">
									<a href="#" class="btn btn-md btn-icon btn-primary" data-toggle="modal" data-target="#modal_ubah_satuan_${item.id}"><i class="icon-pencil position-left"></i> Edit</a>
									<a href="#" class="btn btn-md btn-icon btn-danger" data-toggle="modal" data-target="#modal_hapus_satuan_${item.id}"><i class="icon-trash position-left"></i> Hapus</a>
								</div>

								<!-- Modal Ubah satuan -->
								<div id="modal_ubah_satuan_${item.id}" class="modal fade">
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-header bg-primary">
												<button type="button" class="close" data-dismiss="modal">&times;</button>
												<h5 class="modal-title">Ubah Satuan</h5>
											</div>

											<form action="<?= base_url('farmasi/satuan/ubah_satuan') ?>" method="POST">
												<div class="modal-body">
													<input type="text" name="id" value="${item.id}" hidden>
													<div class="form-group">
														<label class="control-label"><b>Satuan</b></label>
														<input type="text" class="form-control" placeholder="Satuan satuan" name="satuan" value="${item.satuan}">
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
								<!-- /Modal Ubah satuan -->

								<!-- Modal Hapus satuan -->
								<div id="modal_hapus_satuan_${item.id}" class="modal fade">
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-header bg-danger">
												<button type="button" class="close" data-dismiss="modal">&times;</button>
												<h5 class="modal-title">Hapus Satuan</h5>
											</div>
											<div class="modal-body">
												<div class="alert alert-danger no-border">
													<p>Apakah Anda Ingin <span class="text-semibold">Menghapus</span> ${item.satuan} ?</p>
											    </div>
											</div>
											<div class="modal-footer">
												<button class="btn btn-link" data-dismiss="modal"><i class="icon-cross"></i> Keluar</button>
												<a href="<?= base_url('farmasi/satuan/hapus_satuan?id=') ?>${item.id}" class="btn btn-danger"><i class="icon-bin position-left"></i> Hapus</a>
											</div>
										</div>
									</div>
								</div>
								<!-- /Modal Hapus satuan -->
							</td>
						</tr>
					`;

				}
			} else {
				row += `
					<tr>
						<td colspan="3" class="text-center">${res.message}</td>
					</tr>
				`
			}
			// $('#popup_load').fadeOut();
			$(`#table_satuan tbody`).html(row);
			pagination()
		},
		complete : () => {$(`#tr-loading`).hide()}
	})
}

function pagination($selector){
	var jumlah_tampil = '10';

    if(typeof $selector == 'undefined'){
        $selector = $("#table_satuan tbody tr");
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
