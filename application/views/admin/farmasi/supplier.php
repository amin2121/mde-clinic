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
									<li class="active"><a href="#supplier" data-toggle="tab">Supplier</a></li>
									<li><a href="#tambah-supplier" data-toggle="tab">Tambah Supplier</a></li>
								</ul>

								<div class="tab-content" style="margin-bottom: 2em !important;">
									<div class="tab-pane active" id="supplier">
										<div class="form-group">
											<div class="input-group">
												<!-- <label class="control-label col-sm-2">Nama Barang</label> -->
													<input type="text" class="form-control" autocomplete="off" name="nama_supplier" id="nama_supplier" onkeyup="get_supplier()" placeholder="Cari Berdasarkan Nama Supplier">
													<span class="input-group-btn">
														<button class="btn bg-primary" type="button" onclick="get_supplier()"><i class="fa fa-search"></i></button>
													</span>
											</div>
										</div>
										<div class="table-responsive">
											<table class="table table-bordered table-striped" id="table-supplier">
												<thead>
													<tr class="bg-success">
														<th class="text-center">No</th>
														<th class="text-center">Supplier</th>
														<th class="text-center">No HP</th>
														<th class="text-center">No Rekening</th>
														<th class="text-center">bank</th>
														<th class="text-center">Action</th>
													</tr>
												</thead>
												<tbody>
												</tbody>
											</table>
											<br>
											<ul class="pagination"></ul>
										</div>

									</div>
									<div class="tab-pane" id="tambah-supplier">
										<form action="<?= base_url('farmasi/supplier/tambah_supplier') ?>" id="form-tambah-supplier" method="POST">

											<div class="form-group">
												<label class="control-label"><b>Nama Supplier</b></label>
												<input type="text" class="form-control" placeholder="Nama Supplier" name="nama_supplier">
											</div>

											<div class="form-group">
												<label class="control-label"><b>No Hp</b></label>
												<input type="tel" class="form-control" placeholder="No Hp" name="no_hp">
											</div>

											<div class="form-group">
												<label class="control-label"><b>No Rekening</b></label>
												<input type="text" class="form-control" placeholder="No Rekening" name="no_rekening">
											</div>

											<div class="form-group">
												<label class="control-label"><b>Bank</b></label>
												<select class="select-search" name="bank">
													<optgroup label="Jenis Barang">
														<option value="bni">BNI</option>
														<option value="bri">BRI</option>
														<option value="mandiri">Mandiri</option>
														<option value="mega">Mega</option>
														<option value="bca">BCA</option>
													</optgroup>
												</select>
											</div>

											<div class="form-group">
												<label class="control-label"><b>Alamat</b></label>
												<textarea name="alamat" placeholder="Alamat" class="form-control" rows="10"></textarea>
											</div>

											<button class="btn btn-success" type="submit" style="margin-top: 2em;"><i class="icon-floppy-disk position-left"></i> Tambah</button>
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
		// $(`#table-supplier`).DataTable()
		get_supplier()
	})

	function get_supplier() {
		let input_value_nama_supplier = $(`#nama_supplier`).val();

		$.ajax({
			url : '<?= base_url('farmasi/supplier/get_supplier_with_bank_ajax') ?>',
			method : 'POST',
			data: {'nama_supplier' : `${input_value_nama_supplier}`},
			dataType : 'json',
			beforeSend : () => {
			let loading = `<tr id="tr-loading">
								<td colspan="7">
									<div class="loader">
										<img src="<?= base_url('assets/images/svg-loader/svg-loaders/vectorpaint.svg') ?>" alt="">
									</div>
								</td>
							</tr>`
			$(`#table-supplier tbody`).html(loading);
			},
			success : (res) => {
				let row = ''
				if(res.status) {
					let i = 0;
					for(const item of res.data) {
						var bank = '';
						for(const b of res.data_bank) {
							if(b.bank == item.bank) {
								bank += `<option value="${b.bank}" selected style="text-transform: uppercase;">${b.bank}</option>`
							} else {
								bank += `<option value="${b.bank}" style="text-transform: uppercase;">${b.bank}</option>`
							}
						}
						row += `
							<tr id="row_${item.id}">
								<td class="text-center">${++i}</td>
								<td>
									<h5>${item.nama_supplier}</h5>
									<p>${item.alamat}</p>
								</td>
								<td class="text-center">${item.no_hp}</td>
								<td class="text-center">${item.no_rekening}</td>
								<td class="text-center">${item.bank}</td>
								<td>
									<div class="text-center">
										<a href="#" class="btn btn-sm btn-icon btn-primary" data-toggle="modal" data-target="#modal_ubah_supplier_${item.id}"><i class="icon-pencil position-left"></i> Edit</a>
										<a href="#" class="btn btn-sm btn-icon btn-danger" data-toggle="modal" data-target="#modal_hapus_supplier_${item.id}"><i class="icon-trash position-left"></i> Hapus</a>
									</div>

									<!-- Modal Ubah Jenis barang -->
									<div id="modal_ubah_supplier_${item.id}" class="modal fade">
										<div class="modal-dialog">
											<div class="modal-content">
												<div class="modal-header bg-primary">
													<button type="button" class="close" data-dismiss="modal">&times;</button>
													<h5 class="modal-title">Ubah Supplier</h5>
												</div>

												<form action="<?= base_url('farmasi/supplier/ubah_supplier') ?>" id="form-ubah-supplier" method="POST">
												<div class="modal-body">
													<input type="text" value="${item.id}" name="id_supplier" hidden>
													<div class="form-group">
														<label class="control-label">Nama Supplier</label>
														<input type="text" class="form-control" placeholder="nama supplier" name="nama_supplier" value="${item.nama_supplier}">
													</div>

													<div class="form-group">
														<label class="control-label">No Hp</label>
														<input type="tel" class="form-control" placeholder="no hp" name="no_hp" value="${item.no_hp}">
													</div>

													<div class="form-group">
														<label class="control-label">No Rekening</label>
														<input type="text" class="form-control" placeholder="no rekening" name="no_rekening" value="${item.no_rekening}">
													</div>

													<div class="form-group">
														<label class="control-label">Bank</label>
														<select class="select-search-primary" name="bank">
															<optgroup label="Jenis Barang">
																${bank}
															</optgroup>
														</select>
													</div>

													<div class="form-group">
														<label class="control-label">Alamat</label>
														<textarea name="alamat" id="" class="form-control">${item.alamat}</textarea>
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
									<div id="modal_hapus_supplier_${item.id}" class="modal fade">
										<div class="modal-dialog">
											<div class="modal-content">
												<div class="modal-header bg-danger">
													<button type="button" class="close" data-dismiss="modal">&times;</button>
													<h5 class="modal-title">Hapus Supplier</h5>
												</div>
												<div class="modal-body">
													<div class="alert alert-danger no-border">
														<p>Apakah Anda Ingin <span class="text-semibold">Menghapus</span> Supplier Ini?</p>
												    </div>
												</div>
												<div class="modal-footer">
													<button class="btn btn-link" data-dismiss="modal"><i class="icon-cross position-left"></i> keluar</button>
													<a href="<?= base_url('farmasi/supplier/hapus_supplier?id_supplier=') ?>${item.id}" class="btn btn-danger"><i class="icon-trash position-left"></i> Hapus</a>
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
						<tr><td colspan="6" class="text-center"><b>${res.message}</b></td></tr>
					`
				}

				$(`#table-supplier tbody`).html(row);
				$(`#select-search-warning`)
				$(`#table-supplier`).find(`.select-search-primary`).select2({
		        	containerCssClass : 'bg-primary-400'
		      	})
				pagination();
			},
			complete : () => {$(`#tr-loading`).hide()}
		})
	}

	function pagination($selector){
		var jumlah_tampil = '10';

	    if(typeof $selector == 'undefined'){
	        $selector = $("#table-supplier tbody tr");
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
