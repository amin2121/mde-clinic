<!DOCTYPE html>
<html lang="en">
<head>
	<?php $this->load->view('admin/css'); ?>
</head>

<body>

	<?php $this->load->view('admin/nav'); ?>
	<?php $this->load->view('admin/apotek/menu'); ?>
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
										<li class="active"><a href="#rak" data-toggle="tab">Rak</a></li>
										<li><a href="#tambah-rak" data-toggle="tab">Tambah Rak</a></li>
									</ul>

									<div class="tab-content">
										<div class="tab-pane active" id="rak">
											<div class="row">
												<div class="col-sm-6">
													<div class="form-group">
														<div class="input-group">
															<input type="text" class="form-control" autocomplete="off" name="rak" id="input_rak" onkeyup="get_rak()" placeholder="Cari Berdasarkan Rak">
															<span class="input-group-btn">
																<button class="btn btn-primary btn-md btn-icon" type="button" onclick="get_rak()"><i class="fa fa-search position-left"></i> Cari</button>
															</span>
														</div>
													</div>
												</div>
											</div>	
											<div class="table-responsive">
												<table class="table table-bordered table-striped" id="table_rak">
													<thead>
														<tr class="bg-success">
															<th class="text-center">No</th>
															<th class="text-center">Rak</th>
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
										<div class="tab-pane" id="tambah-rak">
											<fieldset class="content-group">
												<form action="<?= base_url('apotek/rak/tambah_rak') ?>" method="POST">

													<div class="row col-sm-6">
														<div class="form-group">
															<label class="control-label"><b>Rak</b></label>
															<input type="text" class="form-control" placeholder="Rak" name="rak">
														</div>
														<div class="form-group" style="margin-top: 1.5em !important;">
															<button class="btn btn-success" type="submit"><i class="icon-floppy-disk position-left"></i> Simpan</button>
														</div>
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
	get_rak();
})

function get_rak() {
	let rak = $(`#input_rak`).val();

	$.ajax({
		url : '<?= base_url('apotek/rak/get_rak_ajax') ?>',
		method : 'POST',
		data : {'search' : `${rak}`},
		dataType : 'json',
		beforeSend : () => {
			let loading = `<tr id="tr-loading">
								<td colspan="7">
									<div class="loader">
										<img src="<?= base_url('assets/images/svg-loader/svg-loaders/vectorpaint.svg') ?>" alt="">
									</div>
								</td>
							</tr>`
			$(`#table_rak tbody`).html(loading);
		},
		success : (res) => {
			let row = '';
			if(res.status) {
				let i = 0;
				for(const item of res.data) {
					row += 	`
						<tr>
							<td class="text-center">${++i}</td>
							<td class="text-center">${item.rak}</td>
							<td>
								<div class="text-center">
									<a href="<?= base_url('apotek/rak/view_barang_rak/') ?>${item.id}" class="btn btn-md btn-icon btn-info"><i class="icon-basket position-left"></i> Barang Rak</a>
									<a href="#" class="btn btn-md btn-icon btn-primary" data-toggle="modal" data-target="#modal_ubah_rak_${item.id}"><i class="icon-pencil position-left"></i> Edit</a>
									<a href="#" class="btn btn-md btn-icon btn-danger" data-toggle="modal" data-target="#modal_hapus_rak_${item.id}"><i class="icon-trash position-left"></i> Hapus</a>
								</div>

								<!-- Modal Ubah rak -->
								<div id="modal_ubah_rak_${item.id}" class="modal fade">
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-header bg-primary">
												<button type="button" class="close" data-dismiss="modal">&times;</button>
												<h5 class="modal-title">Ubah Rak</h5>
											</div>

											<form action="<?= base_url('apotek/rak/ubah_rak') ?>" method="POST">
												<div class="modal-body">
													<input type="text" name="id" value="${item.id}" hidden>
													<div class="form-group">
														<label class="control-label"><b>Rak</b></label>
														<input type="text" class="form-control" placeholder="Rak" name="rak" value="${item.rak}">
													</div>
												</div>

												<div class="modal-footer">
													<button class="btn btn-primary"><i class="icon-pencil position-left"></i> Ubah</button>
													<button class="btn btn-link" data-dismiss="modal"><i class="icon-cross position-left"></i> Keluar</button>
												</div>
											</form>
										</div>
									</div>
								</div>
								<!-- /Modal Ubah rak -->

								<!-- Modal Hapus rak -->
								<div id="modal_hapus_rak_${item.id}" class="modal fade">
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-header bg-danger">
												<button type="button" class="close" data-dismiss="modal">&times;</button>
												<h5 class="modal-title">Hapus Rak</h5>
											</div>
											<div class="modal-body">
												<div class="alert alert-danger no-border">
													<p>Apakah Anda Ingin <span class="text-semibold">Menghapus</span> ${item.rak} ?</p>
											    </div>
											</div>
											<div class="modal-footer">
												<a href="<?= base_url('apotek/rak/hapus_rak?id=') ?>${item.id}" class="btn btn-danger"><i class="icon-bin position-left"></i> Hapus</a>
												<button class="btn btn-link" data-dismiss="modal"><i class="icon-cross"></i> Keluar</button>
											</div>
										</div>
									</div>
								</div>
								<!-- /Modal Hapus rak -->
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

			$(`#table_rak tbody`).html(row);
			pagination()
		},
		complete : () => {$(`#tr-loading`).hide()}
	})
}

function pagination($selector){
	var jumlah_tampil = '10';

    if(typeof $selector == 'undefined'){
        $selector = $("#table_rak tbody tr");
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
