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

											<div class="tabbable">
												<ul class="nav nav-tabs nav-tabs-component">
													<li class="active"><a href="#nama-barang" data-toggle="tab">Nama Barang</a></li>
													<li><a href="#tambah-nama-barang" data-toggle="tab">Tambah Nama Barang</a></li>
												</ul>

												<div class="tab-content">
													<div class="tab-pane active" id="nama-barang">
														<div class="form-group">
															<div class="input-group">
																<!-- <label class="control-label col-sm-2">Nama Barang</label> -->
																	<input type="text" class="form-control" autocomplete="off" name="nama_barang" id="nama_barang" onkeyup="cari_barang()" placeholder="Cari Berdasarkan Nama Barang">
																	<span class="input-group-btn">
																		<button class="btn bg-primary" type="button" onclick="cari_jenis_barang()"><i class="fa fa-search"></i></button>
																	</span>
															</div>
														</div>
														<div class="table-responsive">
															<table class="table table-bordered table-striped" id="table-nama-barang">
																<thead>
																	<tr class="bg-success">
																		<th class="text-center">No</th>
																		<th class="text-center">Kode Barang</th>
																		<th class="text-center">Nama Barang</th>
																		<th class="text-center">Tanggal</th>
																		<th class="text-center">Harga Awal</th>
																		<th class="text-center">Harga Jual</th>
																		<th class="text-center">Action</th>
																	</tr>
																</thead>
																<tbody id="tbody-nama-barang">
																	
																</tbody>
															</table>
														</div>
														<br>
														<ul class="pagination"></ul>
													</div>
													<div class="tab-pane" id="tambah-nama-barang">
														<fieldset class="content-group">
															<form action="<?= base_url('apotek/master/tambah_barang') ?>" id="form-tambah-barang" method="POST">

																<div class="form-group">
																	<label class="control-label"><b>Nama Barang</b></label>
																	<input type="text" class="form-control" placeholder="nama barang" name="nama_barang">
																</div>

																<div class="form-group">
																	<label class="control-label"><b>Kode Barang</b></label>
																	<input type="text" class="form-control" placeholder="kode barang" name="kode_barang">
																</div>

																<div class="form-group">
																	<label class="control-label"><b>Jenis Barang</b></label>
																		<select class="select-search" name="jenis_barang">
																			<optgroup label="Jenis Barang">
																				<?php foreach ($jenis_barang as $jb): ?>
																					<option value="<?= $jb['id'] ?>"><?= $jb['nama_jenis_barang'] ?></option>
																				<?php endforeach ?>
																			</optgroup>
																		</select>
																</div>

																<div class="form-group" style="margin-top: 1.5em !important;">
																	<button class="btn btn-success" type="submit"><i class="icon-check"></i> Tambah</button>
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

	$.ajax({
		url : '<?= base_url('apotek/master/get_nama_barang_with_jenis_barang_ajax') ?>',
		method : 'GET',
		dataType : 'json',
		beforeSend : () => {
			let loading = `<tr id="tr-loading">
								<td colspan="7">
									<div class="loader">
										<img src="<?= base_url('assets/images/svg-loader/svg-loaders/vectorpaint.svg') ?>" alt="">
									</div>
								</td>
							</tr>`
			$(`#tbody-nama-barang`).html(loading);
		},
		success : (res) => {
			let row = '';

			if(res.status) {
				let i = 0;
				for(const item of res.data) {
					// tampilkan jenis barang
					var jenis_barang = ''
					for(const jb of res.data_jenis_barang) {
						if(jb.id == item.id_jenis_barang) {
							jenis_barang += `<option value="${jb.id}" selected>${jb.nama_jenis_barang}</option>`
						} else {
							jenis_barang += `<option value="${jb.id}">${jb.nama_jenis_barang}</option>`
						}
					}

					row += 	`
						<tr>
							<td class="text-center">${++i}</td>
							<td class="text-center">${item.kode_barang}</td>
							<td class="text-center">${item.nama_barang}</td>
							<td class="text-center">${item.tanggal.split("-").reverse().join("-")}</td>
							<td class="text-right"><b>Rp. </b>${NumberToMoney(item.harga_awal)}</td>
							<td class="text-right"><b>Rp. </b>${NumberToMoney(item.harga_jual)}</td>
							<td>
								<div class="text-center">
									<a href="#" class="btn btn-md btn-icon btn-primary" data-toggle="modal" data-target="#modal_edit_barang_${item.id}"><i class="icon-pencil position-left"></i> Edit</a>
									<a href="#" class="btn btn-md btn-icon btn-danger" data-toggle="modal" data-target="#modal_hapus_barang_${item.id}"><i class="icon-trash position-left"></i> Hapus</a>
								</div>

								<!-- Modal Edit barang -->
								<div id="modal_edit_barang_${item.id}" class="modal fade">
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-header bg-primary">
												<button type="button" class="close" data-dismiss="modal">&times;</button>
												<h5 class="modal-title">Edit Barang</h5>
											</div>

											<form action="<?= base_url('apotek/master/ubah_barang') ?>" method="POST">
											<div class="modal-body">
													<input type="text" name="id_barang" value="${item.id}" hidden>
													<div class="form-group">
														<label class="control-label"><b>Nama barang</b></label>
														<input type="text" class="form-control" placeholder="Nama Barang" name="nama_barang" value="${item.nama_barang}">
													</div>

													<div class="form-group">
														<label class="control-label"><b>Kode barang</b></label>
														<input type="text" class="form-control" placeholder="Kode Barang" name="kode_barang" value="${item.kode_barang}">
													</div>

													<div class="form-group">
														<label><b>Jenis barang</b></label>
														<select class="select-search-primary" name="jenis_barang" id="select_jenis_barang">
															<optgroup label="Jenis Barang">
																${jenis_barang}
															</optgroup>
														</select>
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
								<!-- /Modal Edit barang -->

								<!-- Modal Hapus barang -->
								<div id="modal_hapus_barang_${item.id}" class="modal fade">
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-header bg-danger">
												<button type="button" class="close" data-dismiss="modal">&times;</button>
												<h5 class="modal-title">Hapus barang</h5>
											</div>
											<div class="modal-body">
												<div class="alert alert-danger no-border">
													<p>Apakah Anda Ingin <span class="text-semibold">Menghapus</span> ${item.nama_barang} ?</p>
											    </div>
											</div>
											<div class="modal-footer">
												<button class="btn btn-link" data-dismiss="modal"><i class="icon-cross position-left"></i> Keluar</button>
												<a href="<?= base_url('apotek/master/hapus_barang?id_barang=') ?>${item.id}" class="btn btn-danger"><i class="icon-bin position-left"></i> Hapus</a>
											</div>
										</div>
									</div>
								</div>
								<!-- /Modal Hapus barang -->

							</td>
						</tr>
					`;
				}
			} else {
				row += `
					<tr>
						<td colspan="7" class="text-center">${res.message}</td>
					</tr>
				`
			}
			$(`#tbody-nama-barang`).html(row);
			$(`#tbody-nama-barang`).find(`.select-search-primary`).select2({
		        containerCssClass : 'bg-primary-400'
		      })
			pagination()
		},
		complete : () => {$(`#tr-loading`).hide()}
	})
})

function cari_barang() {
	let value_input_nama_barang = $(`#nama_barang`).val();

	$.ajax({
		url : '<?= base_url('apotek/master/get_nama_barang_with_jenis_barang_ajax') ?>',
		method : 'POST',
		data : {'search_barang' : `${value_input_nama_barang}`},
		dataType : 'json',
		beforeSend : () => {
			let loading = `<tr id="tr-loading">
								<td colspan="7">
									<div class="loader">
										<img src="<?= base_url('assets/images/svg-loader/svg-loaders/vectorpaint.svg') ?>" alt="">
									</div>
								</td>
							</tr>`
			$(`#tbody-nama-barang tbody`).html(loading);
		},
		success : (res) => {
			let row = '';

			if(res.status) {
				let i = 0;
				for(const item of res.data) {
					var jenis_barang = ''
					for(const jb of res.data_jenis_barang) {
						if(jb.id == item.id_jenis_barang) {
							jenis_barang += `<option value="${jb.id}" selected>${jb.nama_jenis_barang}</option>`
						} else {
							jenis_barang += `<option value="${jb.id}">${jb.nama_jenis_barang}</option>`
						}
					}
					row += 	`
						<tr>
							<td class="text-center">${++i}</td>
							<td class="text-center">${item.kode_barang}</td>
							<td class="text-center">${item.nama_barang}</td>
							<td class="text-center">${item.tanggal.split("-").reverse().join("-")}</td>
							<td class="text-right"><b>Rp. </b>${NumberToMoney(item.harga_awal)}</td>
							<td class="text-right"><b>Rp. </b>${NumberToMoney(item.harga_jual)}</td>
							<td>
								<div class="text-center">
									<a href="#" class="btn btn-md btn-icon btn-primary" data-toggle="modal" data-target="#modal_edit_barang_${item.id}"><i class="icon-pencil position-left"></i> Edit</a>
									<a href="#" class="btn btn-md btn-icon btn-danger" data-toggle="modal" data-target="#modal_hapus_barang_${item.id}"><i class="icon-trash position-left"></i> Hapus</a>
								</div>

								<!-- Modal Edit barang -->
								<div id="modal_edit_barang_${item.id}" class="modal fade">
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-header bg-primary">
												<button type="button" class="close" data-dismiss="modal">&times;</button>
												<h5 class="modal-title">Edit Barang</h5>
											</div>

											<form action="<?= base_url('apotek/master/ubah_barang') ?>" method="POST">
											<div class="modal-body">
													<input type="text" name="id_barang" value="${item.id}" hidden>
													<div class="form-group">
														<label class="control-label"><b>Nama barang</b></label>
														<input type="text" class="form-control" placeholder="Nama Barang" name="nama_barang" value="${item.nama_barang}">
													</div>

													<div class="form-group">
														<label class="control-label"><b>Kode barang</b></label>
														<input type="text" class="form-control" placeholder="Kode Barang" name="kode_barang" value="${item.kode_barang}">
													</div>

													<div class="form-group">
														<label>Jenis Barang</label>
														<select class="select-search-primary" name="jenis_barang">
															<optgroup label="Jenis Barang">
																${jenis_barang}
															</optgroup>
														</select>
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
								<!-- /Modal Edit barang -->

								<!-- Modal Hapus barang -->
								<div id="modal_hapus_barang_${item.id}" class="modal fade">
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-header bg-danger">
												<button type="button" class="close" data-dismiss="modal">&times;</button>
												<h5 class="modal-title">Hapus barang</h5>
											</div>
											<div class="modal-body">
												<div class="alert alert-danger no-border">
													<p>Apakah Anda Ingin <span class="text-semibold">Menghapus</span> ${item.nama_barang} ?</p>
											    </div>
											</div>
											<div class="modal-footer">
												<button class="btn btn-link" data-dismiss="modal"><i class="icon-cross"></i> Keluar</button>
												<a href="<?= base_url('apotek/master/hapus_barang?id_barang=') ?>${item.id}" class="btn btn-danger"><i class="icon-bin position-left"></i> Hapus</a>
											</div>
										</div>
									</div>
								</div>
								<!-- /Modal Hapus barang -->
							</td>
						</tr>
					`;

				}
			} else {
				row += `
					<tr>
						<td colspan="7" class="text-center">${res.message}</td>
					</tr>
				`
			}
			$('#popup_load').fadeOut();
			$(`#tbody-nama-barang`).html(row);
			$(`#tbody-nama-barang`).find(`.select-search-primary`).select2({
		        containerCssClass : 'bg-primary-400'
		      })
			pagination()
		},
		complete : () => {$(`#tr-loading`).hide()}
	})	
}

function pagination($selector){
	var jumlah_tampil = '10';

    if(typeof $selector == 'undefined'){
        $selector = $(".table tbody tr");
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
