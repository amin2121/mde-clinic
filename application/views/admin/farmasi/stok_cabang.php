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
				<!-- Sidebars overview -->
				<div class="panel panel-flat border-top-success border-top-lg">
					<div class="panel-heading">
						<h5 class="panel-title"><?php echo $title; ?></h5>
					</div>

					<div class="panel-body">
						<div class="tabbable">
								<ul class="nav nav-tabs">
									<?php
									foreach ($cabang as $key => $c):
									?>
										<?php if ($key == 0): ?>
											<li class="active" onclick="get_stok_cabang(<?= $c['id'] ?>)">
												<a href="#basic-justified-<?php echo $c['id']; ?>" data-toggle="tab" class="legitRipple" aria-expanded="true"><?php echo $c['nama']; ?></a>
											</li>
										<?php else: ?>
											<li onclick="get_stok_cabang(<?= $c['id'] ?>)">
												<a href="#basic-justified-<?php echo $c['id']; ?>" data-toggle="tab" class="legitRipple" aria-expanded="true"><?php echo $c['nama']; ?></a>
											</li>
										<?php endif ?>
									<?php endforeach; ?>
								</ul>

								<div class="tab-content">
									<?php
									$no = 0;
									foreach ($cabang as $c):
									$no++;
									?>
									<?php if ($no == '1'): ?>
										<div class="tab-pane active" id="basic-justified-<?php echo $c['id']; ?>">

											<div class="form-group">
												<div class="input-group">
														<input type="text" class="form-control" autocomplete="off" name="nama_barang" id="search_nama_barang_<?= $c['id'] ?>" onkeyup="get_stok_cabang(<?= $c['id'] ?>)" placeholder="Cari Berdasarkan Nama Barang">
														<span class="input-group-btn">
															<button class="btn bg-primary" type="button" onclick="get_stok_cabang(<?= $c['id'] ?>)"><i class="fa fa-search"></i></button>
														</span>
												</div>
											</div>

											<div class="table-responsive">
												<table class="table table-bordered table-striped" id="table-stok-cabang-<?= $c['id'] ?>">
													<thead>
														<tr class="bg-success">
															<th class="text-center">No</th>
															<th class="text-center">Kode</th>
															<th class="text-center">Nama Barang</th>
															<!-- <th class="text-center">Stok Mutasi</th> -->
															<th class="text-center">Stok</th>
														</tr>
													</thead>
													<tbody>
													</tbody>
												</table>
											</div>
											<br>
											<div id="pagination_stok_cabang_<?= $c['id'] ?>"></div>
										</div>
										<?php else: ?>
											<div class="tab-pane" id="basic-justified-<?php echo $c['id']; ?>">
												<div class="form-group">
													<div class="input-group">
															<input type="text" class="form-control" autocomplete="off" name="nama_barang" id="search_nama_barang_<?= $c['id'] ?>" onkeyup="get_stok_cabang(<?= $c['id'] ?>)" placeholder="Cari Berdasarkan Nama Barang">
															<span class="input-group-btn">
																<button class="btn bg-primary" type="button" onclick="get_stok_cabang(<?= $c['id'] ?>)"><i class="fa fa-search"></i></button>
															</span>
													</div>
												</div>
												<div class="table-responsive">
													<table class="table table-bordered table-striped" id="table-stok-cabang-<?= $c['id'] ?>">
														<thead>
															<tr class="bg-success">
																<th class="text-center">No</th>
																<th class="text-center">Kode</th>
																<th class="text-center">Nama Barang</th>
																<th class="text-center">Stok</th>
															</tr>
														</thead>
														<tbody>
														</tbody>
													</table>
												</div>
												<br>
												<div id="pagination_stok_cabang_<?= $c['id'] ?>"></div>
											</div>
									<?php endif; ?>
									<?php endforeach; ?>
								</div>
							</div>

					</div>
				</div>
				<!-- /sidebars overview -->


			</div>
			<!-- /main content -->
		<script>
			$(document).ready(function() {
				get_stok_cabang(1);
			});

			function get_stok_cabang(id_cabang) {
				let loading = `
					<tr id="loading">
						<td colspan="4">
							<div class="loader">
								<img src="<?= base_url('assets/images/svg-loader/svg-loaders/vectorpaint.svg') ?>" alt="">
							</div>
						</td>
					</tr>
				`;

				$(`#table-stok-cabang-${id_cabang} tbody`).html(loading);

				let search = $(`#search_nama_barang_${id_cabang}`).val();
				$.ajax({
					url : '<?= base_url('farmasi/stok_cabang/get_stok_cabang') ?>',
					type : 'POST',
					data : {'id_cabang' : `${id_cabang}`, 'search_nama_barang' : `${search}`},
					dataType : "json",
					success : (res) => {
						let html = '';
						if(res.status) {
							let i = 0;
							for(const item of res.data) {
								html += `
									<tr>
										<td class="text-center">${++i}</td>
										<td class="text-center">${item.kode_barang}</td>
										<td class="text-center">${item.nama_barang}</td>
										<td class="text-center">${item.stok}</td>
									</tr>
								`;
							}
						} else {
							html = `
								<tr>
									<td colspan="4" class="text-center">${res.message}</td>
								</tr>
							`
						}

						$(`#table-stok-cabang-${id_cabang} tbody`).html(html);
						pagination_stok_cabang(id_cabang);
					}
				})
			}

			function pagination_stok_cabang(id_cabang) {
				var jumlah_tampil = '10';

			    if(typeof $selector == 'undefined'){
			        $selector = $(`#table-stok-cabang-${id_cabang} tbody tr`);
			    }
					window.tp = new Pagination(`#pagination_stok_cabang_${id_cabang}`, {
							itemsCount:$(`#table-stok-cabang-${id_cabang} tbody tr`).length,
			        pageSize : parseInt(jumlah_tampil),
			        onPageSizeChange: function (ps) {
			            console.log('changed to ' + ps);
			        },
			        onPageChange: function (paging) {
			            var start = paging.pageSize * (paging.currentPage - 1),
			                end = start + paging.pageSize,
			                $rows = $(`#table-stok-cabang-${id_cabang} tbody tr`);

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
		<!-- /page content -->

	</div>
	<!-- /page container -->
<?php $this->load->view('admin/js'); ?>
</body>
</html>
