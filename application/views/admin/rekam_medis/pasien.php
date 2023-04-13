<!DOCTYPE html>
<html lang="en">
<head>
<?php $this->load->view('admin/css'); ?>
</head>

<body>

<?php $this->load->view('admin/nav'); ?>
<?php $this->load->view('admin/rekam_medis/menu'); ?>

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
					<div class="form-search form-horizontal">
						<div class="row">
							<div class="col-sm-6">

								<div class="form-group">
									<label class="control-label col-sm-2">Nama Pasien</label>
									<div class="col-sm-10">
										<input type="text" class="form-control" placeholder="Cari Berdasarkan Nama Pasien" name="nama_pasien" id="input-nama-pasien">
									</div>
								</div>

								<button class="btn btn-md btn-primary" type="button" onclick="cari_pasien(this)"><i class="icon-search4 position-left"></i> Cari</button>
							</div>
						</div>
					</div>

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
								<table class="table table-bordered table-striped table-hover" id="table-nama-barang">
									<thead>
										<tr class="bg-primary">
											<th class="text-center">No</th>
											<th class="text-center">No RM</th>
											<th class="text-center">Nama Pasien</th>
											<th class="text-center">Jenis Kelamin</th>
											<th class="text-center">Tanggal Lahir</th>
											<th class="text-center">Alamat</th>
											<th class="text-center">Aksi</th>
										</tr>
									</thead>
									<tbody id="tbody-pasien">
									</tbody>
								</table>
							</div>
							<br>
							<ul id="pagination" class="pagination float-right">

							</ul>

						</div>
					</div>
				</div>

			</div>

<script>
	$(window).load(function() {
		$.ajax({
			url : '<?= base_url('rekam_medis/rekam_medis/get_pasien_ajax') ?>',
			method : 'GET',
			dataType : 'json',
			success : (res) => {
				let row_pasien = '';
				if(res.status) {
					let i = 0;
					for(const pasien of res.data) {
						row_pasien += `
							<tr>
								<td class="text-center">${++i}</td>
								<td class="text-center">${pasien.no_rm}</td>
								<td class="text-center">${pasien.nama_pasien}</td>
								<td class="text-center">${pasien.jenis_kelamin}</td>
								<td class="text-center">${pasien.tanggal_lahir}</td>
								<td class="text-center">${pasien.alamat}</td>
								<td class="text-center">
									<a href="<?= base_url('rekam_medis/rekam_medis/cari_rekam_medis/') ?>${pasien.id}" class="btn btn-sm btn-primary btn-icon"><i class="icon-info22 position-left"></i> Detail Rekam Medis</a>
								</td>
							</tr>
						`
					}

					$(`#tbody-pasien`).html(row_pasien);
					paging();
				}
			}
		});
	});

	function paging($selector){
		var jumlah_tampil = '10';

			if(typeof $selector == 'undefined')
			{
					$selector = $("#tbody-pasien tr");
			}

			window.tp = new Pagination('#pagination', {
					itemsCount:$selector.length,
					pageSize : parseInt(jumlah_tampil),
					onPageSizeChange: function (ps) {
							console.log('changed to ' + ps);
					},
					onPageChange: function (paging) {
							//custom paging logic here
							//console.log(paging);
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

	function direct_ke_cari_rekam_medis(e, id) {
		window.location.href = `<?= base_url('rekam_medis/rekam_medis/cari_rekam_medis/') ?>${id}`;
	}

	function cari_pasien(e) {
		let value_input_search = $(`#input-nama-pasien`).val();

		$.ajax({
			url : '<?= base_url('rekam_medis/rekam_medis/get_pasien_ajax') ?>',
			data : {'search' : value_input_search},
			method : 'GET',
			dataType : 'json',
			success : (res) => {
				let row_pasien = '';

				if(res.status) {
					let i = 0;
					for(const pasien of res.data) {
						row_pasien += `
							<tr>
								<td class="text-center">${++i}</td>
								<td class="text-center">${pasien.no_rm}</td>
								<td class="text-center">${pasien.nama_pasien}</td>
								<td class="text-center">${pasien.jenis_kelamin}</td>
								<td class="text-center">${pasien.tanggal_lahir}</td>
								<td class="text-center">${pasien.alamat}</td>
								<td class="text-center">
									<a href="<?= base_url('rekam_medis/rekam_medis/cari_rekam_medis/') ?>${pasien.id}" class="btn btn-sm btn-primary btn-icon"><i class="icon-info22 position-left"></i> Detail Rekam Medis</a>
								</td>
							</tr>
						`
					}
				} else {
					row_pasien += `
						<tr>
							<td colspan="7" class="text-center">Data Pasien Kosong</td>
						</tr>
					`
				}

				$(`#tbody-pasien`).html(row_pasien);
			}
		})
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
