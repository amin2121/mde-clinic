<!DOCTYPE html>
<html lang="en">
<head>
<?php $this->load->view('admin/css'); ?>
<script src="<?php echo base_url('assets/js/plugins/tables/datatables/datatables.min.js'); ?>"></script>
<script type="text/javascript">
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
									<li class="active"><a href="#pengeluaran" data-toggle="tab">Pengeluaran</a></li>
									<li><a href="#tambah-pengeluaran" data-toggle="tab">Tambah Pengeluaran</a></li>
								</ul>

								<div class="tab-content" style="margin-bottom: 2em !important;">
									<div class="tab-pane active" id="pengeluaran">
										<div class="table-responsive">
											<table class="table table-bordered table-striped" id="table-pengeluaran">
												<thead>
													<tr class="bg-success">
														<th class="text-center">No</th>
														<th class="text-center">Jenis Biaya</th>
														<th class="text-center">Keterangan</th>
														<th class="text-center">Nominal</th>
														<th class="text-center">Tanggal</th>
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

									<div class="tab-pane" id="tambah-pengeluaran">
										<fieldset class="content-group">
											<legend class="text-semibold">Tambah Pengeluaran</legend>
											<form action="<?= base_url('resepsionis/keuangan/tambah_pengeluaran') ?>" method="POST" id="form-tambah-pengeluaran">

												<div class="form-group">
													<label class="control-label">Jenis Biaya</label>
													<select name="jenis_biaya" class="select-search-primary">
														<?php foreach ($jenis_biaya as $jb): ?>
															<option value="<?= $jb['id'] ?>"><?= ucfirst($jb['nama']) ?></option>
														<?php endforeach ?>
													</select>
												</div>

												<div class="form-group">
													<label class="control-label">Keterangan</label>
													<input type="text" class="form-control" placeholder="Keterangan" name="keterangan">
												</div>

												<div class="form-group">
													<label class="control-label">Nominal</label>
													<div class="input-group">
														<span class="input-group-addon"><b>Rp. </b></span>
														<input type="text" class="form-control rupiah" placeholder="Nominal" name="nominal">
													</div>
												</div>

											<button class="btn btn-md btn-success btn-icon" style="margin-top: 3em;" type="button" onclick="submit_tambah_pengeluaran()"><i class="icon-floppy-disk position-left"></i> Simpan</button>
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
			url : '<?= base_url('resepsionis/keuangan/get_pengeluaran_ajax') ?>',
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
								<td class="text-center">${item.nama_jenis_biaya}</td>
								<td class="text-center">${item.keterangan}</td>
								<td class="text-right"><b>Rp. </b>${NumberToMoney(item.nominal)}</td>
								<td class="text-center">${item.tanggal.split("-").reverse().join("-")}</td>
								<td>
									<div class="text-center">
										<a href="#" class="btn btn-md btn-icon btn-primary" data-toggle="modal" data-target="#modal_edit_pengeluaran-${item.id}"><i class="icon-pencil position-left"></i> Edit</a>
										<a href="#" class="btn btn-md btn-icon btn-danger" data-toggle="modal" data-target="#modal_hapus_pengeluaran-${item.id}"><i class="icon-trash position-left"></i> Hapus</a>
									</div>

									<!-- Modal Ubah Pengeluaran -->
									<div id="modal_edit_pengeluaran-${item.id}" class="modal fade">
										<div class="modal-dialog">
											<div class="modal-content">
												<div class="modal-header bg-primary">
													<button type="button" class="close" data-dismiss="modal">&times;</button>
													<h5 class="modal-title">Ubah Pengeluaran</h5>
												</div>
												<form action="<?= base_url('resepsionis/keuangan/ubah_pengeluaran') ?>" method="POST" id="form-ubah-pengeluaran">
												<div class="modal-body">

													<input type="text" name="id" value="${item.id}" hidden>
													<div class="form-group">
														<label class="control-label">Jenis Biaya</label>
														<select name="jenis_biaya" class="select-search-primary select-jenis-biaya">
															<?php foreach($jenis_biaya as $jb) :?>
																<option value="<?= $jb['id'] ?>"><?= $jb['nama'] ?></option>
															<?php endforeach; ?>
														</select>

													</div>
													<div class="form-group">
														<label class="control-label">Keterangan</label>
														<input type="text" class="form-control" name="keterangan" value="${item.keterangan}">
													</div>
													<div class="form-group">
														<label class="control-label">Nominal</label>
														<div class="input-group">
															<span class="input-group-addon"><b>Rp. </b></span>
															<input type="text" class="form-control rupiah" name="nominal" value="${convertRupiah(item.nominal)}">
														</div>
													</div>

												</div>
												<div class="modal-footer">
													<button class="btn btn-link" data-dismiss="modal"><i class="icon-cross position-left"></i> Keluar</button>
													<button type="button" class="btn btn-primary" onclick="submit_ubah_pengeluaran()"><i class="icon-pencil position-left"></i> Ubah</button>
												</div>
												</form>
											</div>
										</div>
									</div>
									<!-- /Modal Ubah Pengeluaran -->

									<!-- Modal Hapus Pengeluaran -->
									<div id="modal_hapus_pengeluaran-${item.id}" class="modal fade">
										<div class="modal-dialog">
											<div class="modal-content">
												<div class="modal-header bg-danger">
													<button type="button" class="close" data-dismiss="modal">&times;</button>
													<h5 class="modal-title">Hapus Pengeluaran</h5>
												</div>
												<form action="<?= base_url('resepsionis/keuangan/hapus_pengeluaran') ?>" method="GET">
												<div class="modal-body">
													<input type="text" name="id" value="${item.id}" hidden="">
													<div class="alert alert-danger no-border">
														<p>Apakah Anda Ingin <span class="text-semibold">Menghapus</span> Pengeluaran Ini ?</p>
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
									<!-- /Modal Hapus Pengeluaran -->

								</td>
							</tr>
					`
					}
				} else {
					row += `
						<tr>
							<td colspan="6" class="text-semibold text-center">${res.message}</td>
						</tr>
					`
				}

				$(`#table-pengeluaran tbody`).html(row);
				$(`#table-pengeluaran tbody`).find(`.select-search-primary`).select2({
			        containerCssClass : 'bg-primary-400'
			    })
				paging();
			},
		})

	})

	function paging($selector){
		var jumlah_tampil = '10';

	    if(typeof $selector == 'undefined'){
	        $selector = $("#table-pengeluaran tbody tr");
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

	function submit_tambah_pengeluaran() {
		$(`.rupiah`).unmask();

		$(`#form-tambah-pengeluaran`).submit()
	}

	function submit_ubah_pengeluaran() {
		$(`.rupiah`).unmask();

		$(`#form-ubah-pengeluaran`).submit();
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
