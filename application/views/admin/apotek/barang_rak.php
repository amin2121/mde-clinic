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
					<form action="<?= base_url('apotek/rak/barang_rak') ?>" class="form-horizontal" method="POST" id="form-tambah-rak">
						<fieldset class="content-group">

							<div class="input-group" style="margin-bottom: 2rem;">
					            <span class="input-group-btn">
					              <button class="btn btn-primary btn-md btn-icon" type="button" onclick="show_modal_barang()"><i class="fa fa-search position-left"></i> Cari</button>
					            </span>
					            <input type="text" id="search-barang-in-modal" placeholder="Cari Barang Yang Anda Inginkan..." class="form-control" readonly>
					        </div>

							<input type="hidden" name="id_rak" id="id-rak" hidden="" value="<?= $id_rak ?>">
							<div class="table-responsive">
								<table class="table table-bordered table-striped" id="table-tambah-barang-rak">
									<thead>
										<tr class="bg-success">
											<th>Nama Barang</th>
											<th>Kode Barang</th>
											<th>Stok</th>
											<th class="text-center">Action</th>
										</tr>
									</thead>
									<tbody id="tbody-tambah-barang-rak">
										<input type="text" hidden="" value="<?= count($barang_rak) == 0 ? 1 : count($barang_rak) ?>" id="input-row-barang-rak">

										<?php if(count($barang_rak) > 0) : ?>
											<?php foreach ($barang_rak as $key => $barang): ?>
												<tr style="cursor: pointer" id="row-barang-rak-<?= $key + 1 ?>">
													<td>
														<input type="text" name="id_barang[]" value="<?= $barang['id_barang'] ?>" id="id-barang-<?= $key + 1 ?>" hidden="">
														<input type="text" name="id_apotek[]" class="id_apotek" value="<?= $barang['id_apotek'] ?>" id="id-apotek-<?= $key + 1 ?>" hidden="">

														<!-- cari barang -->
														<div class="form-group">
															<input type="text" class="form-control" placeholder="Cari Barang Anda.." readonly id="search-barang-<?= $key + 1 ?>" value="<?= $barang['nama_barang'] ?>" name="nama_barang[]">
														</div>

													</td>
													<td>
														<!-- kode barang -->
														<div class="form-group">
															<input type="text" class="form-control" value="<?= $barang['kode_barang'] ?>" readonly id="kode-barang-<?= $key + 1 ?>" name="kode_barang[]">
														</div>
													</td>
													<td>
														<!-- stok -->
														<div class="form-group">
															<input type="text" class="form-control" value="<?= $barang['stok'] ?>" id="stok-<?= $key + 1 ?>" readonly>
														</div>
													</td>
													<td class="text-center">
														<button class="btn btn-md btn-danger" onclick="delete_rows(this, <?= $key + 1 ?>)"><i class="icon-bin"></i></button>
													</td>
												</tr>
											<?php endforeach ?>
										<?php endif; ?>	
									</tbody>
								</table>
							</div>

							<!-- Modal Barang -->
								<div id="modal_tampil_barang" class="modal fade">
									<div class="modal-dialog modal-lg">
										<div class="modal-content">
											<div class="modal-header bg-primary">
												<button type="button" class="close" data-dismiss="modal">&times;</button>
												<h5 class="modal-title"> &nbsp;Barang</h5>
											</div>

											<div class="modal-body">
												<div class="row">
													<div class="col-sm-12">
											         	<div class="input-group">
												            <input type="text" id="search-barang-in-modal" placeholder="Cari Barang Yang Anda Inginkan..." class="form-control" onkeyup="get_barang()">
												            <span class="input-group-btn">
												              <button class="btn btn-primary btn-md btn-icon" type="button" onclick="get_barang()"><i class="fa fa-search position-left"></i> Cari</button>
												            </span>
												        </div>
												    </div>
												    <div class="col-sm-12">
														<div class="table-responsive" style="margin-top: 1rem;">
															<table class="table table-bordered table-hover table-striped" id="table-barang">
																<thead>
																	<tr class="bg-primary">
																		<th>No.</th>
																		<th>Kode Barang</th>
																		<th>Nama Barang</th>
																		<th>Stok</th>
																	</tr>
																</thead>
																<tbody id="tbody-barang">
																</tbody>
															</table>
														</div>

														<br>
														<div class="pagination_barang"></div>
												    </div>
												</div>
											</div>

											<div class="modal-footer">
												<button class="btn btn-warning" data-dismiss="modal"><i class="icon-cross"></i> Keluar</button>
											</div>

										</div>
									</div>
								</div>
							<!-- /Modal Barang -->

							<button class="btn btn-md btn-icon btn-info" type="button" onclick="tambah_baris()" style="margin-top: 1em;"><i class="fa fa-plus position-left"></i> Tambah</button>

							<div class="form-group" style="margin-top: 4rem;">
								<div class="col-lg-12"><button class="btn btn-block btn-lg bg-success-600" type="submit"><i class="icon-floppy-disk position-left"></i> Simpan</button></div>
							</div>

						</fieldset>
					</form>
				</div>
			</div>
		</div>
	</div>

<script>
	function show_modal_barang() {
		get_barang()
		$(`#modal_tampil_barang`).modal('toggle')
	}

	function get_barang() {
		let search = $(`#search-barang-in-modal`).val()

		$.ajax({
			url : '<?= base_url('apotek/rak/get_stok') ?>',
			data: {search},
			method : 'POST',
			dataType : 'json',
			success : (res) => {
				let row = '';
				if(res.data.length > 0) {
					let i = 0;
					for(const item of res.data) {
						row += `
							<tr onclick="klik_barang(${item.id})" style="cursor: pointer;">
								<td class="text-center">${++i}</td>
								<td class="text-center">${item.kode_barang}</td>
								<td class="text-center">${item.nama_barang}</td>
								<td class="text-center">${item.stok}</td>
							</tr>
						`
					}
				} else {
					row += `
						<tr>
							<td colspan="4" class="text-semibold text-center">Data Barang Kosong</td>
						</tr>
					`
				}

				$(`#table-barang tbody`).html(row);
				pagination_barang();
			},
		})
	}

	function pagination_barang($selector){
		var jumlah_tampil = '10';

	    if(typeof $selector == 'undefined'){
	        $selector = $(`#table-barang tbody tr`);
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

	let semua_id_apotek = []
	$(document).ready(function() {
		$(`.id_apotek`).each(function(index) {
			semua_id_apotek.push($(this).val())
		})
	})


	let klik_barang = (id_apotek) => {
		let row = $(`#input-row-barang-rak`).val()

		$.ajax({
			url : `<?= base_url('apotek/rak/barang?id_apotek=') ?>${id_apotek}`,
			method : 'GET',
			dataType : 'json',
			success : (res) => {
				let cariIdApotek = semua_id_apotek.find(item => item == res.data.id)
				if(cariIdApotek == undefined) {
					tambah_baris(res)
					semua_id_apotek.push(res.data.id)
				} else {
					alert('Barang Sudah Dipilih')
				}
			}
		})
	}

	let tambah_baris = (res) => {
		let input_row = $(`#input-row-barang-rak`).val()
		let index =  parseInt(input_row) + 1

		let rows = `
			<tr style="cursor: pointer" id="row-barang-rak-${index}">
				<td>

					<input type="text" name="id_barang[]" value="${res.data.id_barang}" id="id-barang-${index}" hidden>
					<input type="text" name="id_apotek[]" value="${res.data.id_apotek}" id="id-apotek-${index}" hidden>

					<div class="form-group">
						<input type="text" class="form-control" value="${res.data.nama_barang}" placeholder="Cari Barang Anda.." readonly id="search-barang-${index}" name="nama_barang[]">
					</div>

				</td>
				<td>
					<!-- kode barang -->
					<div class="form-group">
						<input type="text" class="form-control" readonly value="${res.data.kode_barang}" id="kode-barang-${index}" name="kode_barang[]">
					</div>
				</td>
				<td>
					<!-- stok -->
					<div class="form-group">
						<input type="text" class="form-control" value="${res.data.stok}" id="stok-${index}" name="stok[]">
					</div>
				</td>
				<td class="text-center">
					<button class="btn btn-md btn-danger" type="button" onclick="delete_rows(this, ${index})"><i class="icon-bin"></i></button>
				</td>

			</tr>
		`
		$(`#table-tambah-barang-rak #tbody-tambah-barang-rak`).append(rows);
		$(`#input-row-barang-rak`).val(index);
	}

	let delete_rows = (e, idx_sekarang) => {
		let input_row = $(`#input-row-barang-rak`).val();
		let index =  parseInt(input_row) - 1;
		let id_apotek = $(`#id-apotek-${idx_sekarang}`).val()
		semua_id_apotek = semua_id_apotek.filter(val => val != id_apotek)

		e.parentNode.parentNode.remove()
		$(`#input-row-barang-rak`).val(index);
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
