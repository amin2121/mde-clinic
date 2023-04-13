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

			<div class="panel pane-flat border-top-indigo border-top-lg">
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

								<div class="form-group">
									<div class="input-group">
										<span class="input-group-btn">
											<button class="btn bg-primary" type="button" onclick="tampil_modal_barang()"><i class="icon-search4 position-left"></i> Cari</button>
										</span>
										<input type="text" class="form-control" autocomplete="off" readonly="">
									</div>
								</div>

								<!-- Modal Detail Lacak Barang -->
									<div id="modal_detail_lacak_barang" class="modal fade">
										<div class="modal-dialog modal-lg">
											<div class="modal-content">
												<div class="modal-header bg-primary">
													<button type="button" class="close" data-dismiss="modal">&times;</button>
													<h5 class="modal-title">Detail Lacak Barang</h5>
												</div>

												<div class="modal-body">

													<div class="form-group">
														<div class="input-group">
															<span class="input-group-btn">
																<button class="btn bg-primary" type="button" onclick="get_barang()"><i class="icon-search4 position-left"></i></button>
															</span>
															<input type="text" class="form-control" autocomplete="off" name="search" id="search" onkeyup="get_barang()" placeholder="Cari Barang Berdasarkan Nama Barang atau Kode Barang">
														</div>
													</div>

													<div class="table-responsive">
														<table class="table table-striped table-bordered" id="table_barang">
															<thead>
																<tr class="bg-success">
																	<th>No.</th>
																	<th>Kode Barang</th>
																	<th>Nama Barang</th>
																</tr>
															</thead>
																
															<tbody>
																
															</tbody>
														</table>
														<br>
														<ul class="pagination_barang"></ul>
													</div>
												</div>

												<div class="modal-footer">
													<button class="btn btn-link" data-dismiss="modal"><i class="icon-cross position-left"></i> Keluar</button>
												</div>
											</div>
										</div>
									</div>
								<!-- /Modal Detail Lacak Barang -->
							</div>
						</div>
					</div>
				</div>
			</div>

<!-- <script type="text/javascript" src="<?= base_url('assets/js/pages/datatables_basic.js') ?>"></script> -->
<script>
	$(document).ready(() => {
		$('div.dataTables_length select').addClass('form-control');
		
		// $('#popup_load').show();
	})

	function tampil_modal_barang() {
		$(`#modal_detail_lacak_barang`).modal('toggle');
		get_barang();
	}

	function get_barang() {
		let search = $(`#search`).val();

		$.ajax({
			url : '<?= base_url('farmasi/lacak_barang/get_barang') ?>',
			method : 'POST',
			dataType : 'json',
			data : {'search' : `${search}`},
			beforeSend : () => {
				let loading = `<tr class="tr-loading">
									<td colspan="3">
										<div class="loader">
											<img src="<?= base_url('assets/images/svg-loader/svg-loaders/vectorpaint.svg') ?>" alt="">
										</div>
									</td>
								</tr>`
				$(`#table_barang tbody`).html(loading);
			},
			success : (res) => {
				let row = '';
				if(res.status) {
					let i = 0;
					for(const item of res.data) {
						row += `
							<tr>
								<td>${++i}</td>
								<td>${item.kode_barang}</td>
								<td>${item.nama_barang}</td>
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

				$(`#table_barang tbody`).html(row);
				pagination_barang();
			},
			complete : () => {$(`.tr-loading`).fadeOut()}
		})
	}

	function pagination_barang() {
		var jumlah_tampil = '10';

	    if(typeof $selector == 'undefined'){
	        $selector = $("#table_barang tbody tr");
	    }
			window.tp = new Pagination('.pagination_barang', {
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
