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
					<form action="<?= base_url('farmasi/rak/barang_rak') ?>" class="form-horizontal" method="POST" id="form-tambah-faktur">
						<fieldset class="content-group" style="margin-top: 2em !important;">

							<div class="table-responsive" style="margin-top: 3em;">
								<table class="table table-bordered table-striped" id="table-tambah-barang-rak">
									<thead>
										<tr class="bg-success">
											<th>Nama Barang</th>
											<th>Kode Barang</th>
											<th>Stok</th>
											<th>Action</th>
										</tr>
									</thead>
									<tbody id="tbody-tambah-barang-rak">
										<tr style="cursor: pointer" id="row-barang-rak-1">
											<td>
												<input type="text" name="id_barang[]" id="id-barang-1" hidden="">
												<!-- cari barang -->
												<div class="form-group">
													<div class="input-group">
														<span class="input-group-btn">
															<button class="btn btn-success btn-icon" type="button" onclick="show_modal_barang(1)"><i class="icon-search4"></i></button>
														</span>
														<input type="text" class="form-control" placeholder="Cari Barang Anda.." readonly id="search-barang-faktur-1" name="nama_barang[]">
													</div>
												</div>

											</td>
											<td>
												<!-- kode barang -->
												<div class="form-group">
													<input type="text" class="form-control" readonly id="kode-barang-faktur-1" name="kode_barang[]">
												</div>
											</td>
											<td>
												<!-- stok -->
												<div class="form-group">
													<input type="text" class="form-control" id="stok-1" readonly>
												</div>
											</td>
											<td>
												<button class="btn btn-md btn-danger" disabled=""><i class="icon-bin"></i></button>
												<!-- Modal Barang -->
												<div id="modal_tampil_barang_1" class="modal fade">
													<div class="modal-dialog modal-lg">
														<div class="modal-content">
															<div class="modal-header bg-primary">
																<button type="button" class="close" data-dismiss="modal">&times;</button>
																<h5 class="modal-title"> &nbsp;Barang</h5>
															</div>

															<div class="modal-body">
																<div class="form-group">
														         	<div class="input-group">
															            <input type="text" id="search-barang-in-modal-1" placeholder="Cari Barang Yang Anda Inginkan..." class="form-control" onkeyup="cari_barang_in_modal(this.value, 1)">
															            <span class="input-group-btn">
															              <button class="btn bg-primary" type="button"><i class="fa fa-search"></i></button>
															            </span>
															        </div>
														        </div>

																<div class="table-responsive">
																	<table class="table table-bordered table-hover table-striped" id="table-barang-1">
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

															<div class="modal-footer">
																<button class="btn btn-warning" data-dismiss="modal"><i class="icon-cross"></i> Keluar</button>

															</div>
														</div>
													</div>
												</div>
												<!-- /Modal Barang -->
											</td>

										</tr>
									</tbody>
								</table>
							</div>

							<input type="text" hidden="" value="1" id="input-row-barang-rak">

							<div class="form-group">
								<div class="col-lg-2"></div>
								<div class="col-lg-10"><button class="btn btn-md bg-success-600" type="button" onclick="on_submit_tambah_faktur(this)"><i class="icon-floppy-disk position-left"></i> Simpan</button></div>
							</div>

						</fieldset>
					</form>
				</div>
			</div>
		</div>
	</div>

<script>
	function show_modal_barang(no) {
		$(`#modal_tampil_barang_${no}`).modal('toggle')
		get_barang(no)
	}

	function get_barang(no) {
		$.ajax({
			url : '<?= base_url('farmasi/rak/get_stok') ?>',
			method : 'POST',
			dataType : 'json',
			success : (res) => {
				console.log(res);
				let row = '';
				if(res.status) {
					let i = 0;
					for(const item of res.data) {
						row += `
							<tr onclick="add_barang(${item.id}, ${no})">
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
							<td colspan="4" class="text-semibold text-center">${res.message}</td>
						</tr>
					`
				}

				$(`#modal_tampil_barang_${no} tbody`).html(row);
				pagination_barang(no);
			},
		})
	}

	function pagination_barang($no, $selector){
		var jumlah_tampil = '10';

	    if(typeof $selector == 'undefined'){
	        $selector = $(`#table-barang-${$no} tbody tr`);
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

	let klik_barang = (id_barang, row) => {
		$.ajax({
			url : `<?= base_url('farmasi/rak/barang?id_barang=') ?>${id_barang}`,
			method : 'GET',
			dataType : 'json',
			success : (res) => {
				$(`#id-barang-${row}`).val(res.data.id)
				$(`#search-barang-faktur-${row}`).val(res.data.nama_barang)
				$(`#kode-barang-faktur-${row}`).val(res.data.kode_barang)
				$(`#stok-${row}`).val(res.data.stok)

				$(`#modal_tampil_barang`).modal('toggle')
			}
		})
	}

	let tambah_baris = () => {
		let input_row = $(`#input-row-barang-rak`).val();
		let index =  parseInt(input_row) + 1;

		let rows = ` <tr style="cursor: pointer" id="row-faktur-${index}">
				<td>
				<input type="text" name="id_barang[]" id="id-barang-${index}" hidden>
					<!-- cari barang -->
					<div class="form-group">
						<div class="input-group">
							<span class="input-group-btn">
								<button class="btn btn-success btn-icon" type="button" onclick="show_modal_barang(${index})"><i class=" icon-search4"></i></button>
							</span>
							<input type="text" class="form-control" placeholder="Cari Barang Anda.." readonly id="search-barang-faktur-${index}" name="nama_barang[]">
						</div>
					</div>

				</td>
				<td>
					<!-- kode barang -->
					<div class="form-group">
						<input type="text" class="form-control" readonly id="kode-barang-faktur-${index}" name="kode_barang[]">
					</div>
				</td>
				<td>
					<!-- stok -->
					<div class="form-group">
						<input type="text" class="form-control" id="stok-${index}" name="stok[]">
					</div>
				</td>
				<td>
					<button class="btn btn-md btn-danger" type="button" onclick="delete_rows(this)"><i class="icon-bin"></i></button>
				</td>

			</tr>
		`
		$(`#table-tambah-barang-rak #tbody-tambah-barang-rak`).append(rows);
		$(`#row-barang-rak-${index}`).find(".datepicker").removeClass('hasDatePicker').datepicker({
			showOtherMonths: true,
	        selectOtherMonths: true,
	        showButtonPanel: true,
	        dateFormat : 'dd-mm-yy'
		});

		$(`#input-row-barang-rak`).val(index);
	}

	let delete_rows = (e) => {
		let input_row = $(`#input-row-barang-rak`).val();
		let index =  parseInt(input_row) - 1;

		e.parentNode.remove()
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
