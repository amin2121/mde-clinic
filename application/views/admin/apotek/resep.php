<!DOCTYPE html>
<html lang="en">

<head>
	<?php $this->load->view('admin/css'); ?>
	<script src="<?php echo base_url('assets/js/plugins/tables/datatables/datatables.min.js'); ?>"></script>
	<script type="text/javascript">
		$(function() {
			$('.diagnosa').DataTable();
		});
	</script>
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
									<?php if ($this->session->flashdata('status')) : ?>
										<div class="alert alert-<?= $this->session->flashdata('status'); ?> no-border">
											<button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>
											<p class="message-text"><?= $this->session->flashdata('message'); ?></p>
										</div>
									<?php endif ?>
									<!-- message -->


									<div class="row" style="padding-top: 50px;">
										<div class="col-sm-2">
											<div class="form-group">
												<label class="control-label col-sm-3"><b>Tanggal Dari</b></label>
												<div class="col-sm-9">
													<input type="text" class="form-control datepicker" name="tgl_resep_dari" id="input-tgl-dari">
												</div>
											</div>
										</div>
										<div class="col-sm-2">
											<div class="form-group">
												<label class="control-label col-sm-3"><b>Tanggal Sampai</b></label>
												<div class="col-sm-9">
													<input type="text" class="form-control datepicker" name="tgl_resep_sampai" id="input-tgl-sampai">
												</div>
											</div>
										</div>

										<div class="col-sm-4 justify-end-content">
											<div class="form-group">
												<input type="text" id="cari_pasien" class="form-control" placeholder="Cari Berdasarkan Nama Pasien atau No RM">
											</div>
										</div>

										<div class="col-sm-4 col-md-2"> <!-- Use col-md-2 to adjust for medium screens -->
											<div class="d-flex justify-content-end"> <!-- Align buttons to the right -->
												<button class="btn btn-sm btn-primary ml-2" onclick="cari_resep()"><i class="icon-search4 position-left"></i> Cari</button>
												<!-- <button class="btn btn-sm btn-success ml-2" onclick="get_resep_obat()"><i class="icon-book2 position-left"></i> Lihat Semua</button> -->
											</div>
										</div>
									</div>


									<div class="table-responsive">
										<table class="table table-bordered table-striped table-hover table-resep">
											<thead>
												<tr class="bg-success">
													<th class="text-center">No.</th>
													<th class="text-center">No Invoice</th>
													<th class="text-center">Nama Pasien</th>
													<th class="text-center">Nama dokter</th>
													<th class="text-center">Usia</th>
													<th class="text-center">Berat badan</th>
													<th class="text-center">No telepon</th>
													<th class="text-center">Alamat</th>
													<th class="text-center">Total Harga</th>
													<th class="text-center">Tanggal</th>
													<th class="text-center">Action</th>
												</tr>
											</thead>
											<tbody id="tbody-resep-obat">

											</tbody>
										</table>
									</div>
									<br>
									<div class="row">
										<div class="col-sm-7">
												<div id="pagination_resep"></div>
										</div>
										<div class="col-sm-1 offset-sm-4" style="margin-left:420px; margin-top:-30" id="live-search-container">
												<select class="form-control" style="margin: 20px 0;" id="select-show-data" onchange="paging_resep()">
														<option>10</option>
														<option>20</option>
														<option>50</option>
														<option>100</option>
												</select>
										</div>
								</div>

								</div>
							</div>

						</div>

					</div>

					<script>
						$(window).load(() => {
							get_resep_obat();
						});

						function get_resep_obat() {
							function fetchResepObat(searchValue = "") {
								$.ajax({
									url: '<?= base_url('apotek/kasir/get_resep_obat_api') ?>',
									method: 'GET',
									dataType: 'json',
									data: {
										search: searchValue
									},
									success: (res) => {
										let row = '';
										if (res.status) {
											let index = 1;
											for (const item of res.data) {
												row += `
									<!-- Table row for each item -->
									<tr>
										<td class="text-center">${index++}</td>
										<td class="text-center">${item.invoice}</td>
										<td class="text-center">${item.nama_pasien}</td>
										<td class="text-center">${item.nama_dokter}</td>
										<td class="text-center">${item.umur}</td>
										<td class="text-center">${item.berat_badan}</td>
										<td class="text-center">${item.no_telp}</td>
										<td class="text-center">${item.alamat}</td>
										<td class="text-right"><b>Rp. </b>${convertRupiah(parseInt(item.total_harga_resep))}</td>
										<td class="text-center">${item.tanggal}</td>
										<td>
										<div class="text-center">
											<button class="btn btn-sm btn-icon btn-primary" onclick="get_obat(this, ${item.id})" id="btn-detail-obat-${item.id}">
											<i class="icon-info22 position-left"></i> Detail
											</button>
										</div>

									<!-- Large modal -->
									<div id="modal_tampil_obat_${item.id}" class="modal fade">
										<div class="modal-dialog modal-lg">
											<div class="modal-content">
												<div class="modal-header bg-primary">
													<button type="button" class="close" data-dismiss="modal">&times;</button>
													<h5 class="modal-title">Detail Resep</h5>
												</div>

												<div class="modal-body">
													<div class="table-responsive">
														<table class="table table-bordered table-striped table-hover">
															<thead>
																<tr class="bg-primary">
																	<th class="text-center">No. </th>
																	<th class="text-center">Nama Obat</th>
																	<th class="text-center">Jenis Obat</th>
																	<th class="text-center">Jumlah Obat</th>
																	<th class="text-center">Harga</th>
																	<th class="text-center">Total</th>
																</tr>
															</thead>
															<tbody id="tbody-obat-${item.id}">

															</tbody>
														</table>
													</div>
												</div>

												<div class="modal-footer">
													<button type="button" class="btn btn-link" data-dismiss="modal"><i class="icon-cross position-left"></i> Keluar</button>
												</div>
											</div>
										</div>
									</div>
									<!-- /large modal -->
									</td>
								</tr>
								`;
											}
										} else {
											row = `<tr>
									<td colspan="6" class="text-center">${res.message}</td>
								</tr>`;
										}

										$(`#tbody-resep-obat`).html(row);
										paging_resep();
									}
								});
							}

							// Initial fetch without search value
							fetchResepObat();

							// Live search event listener
							$('#live_search_input').on('keyup', function() {
								let searchValue = $(this).val();
								fetchResepObat(searchValue);
							});
						}





						let cari_resep = () => {
							let value_tgl_resep_dari = $(`#input-tgl-dari`).val();
							let value_tgl_resep_sampai = $(`#input-tgl-sampai`).val();

							$.ajax({
								url: '<?= base_url('apotek/kasir/get_resep_obat_api') ?>',
								method: 'POST',
								dataType: 'json',
								data: {
									'tgl_dari': value_tgl_resep_dari,
									'tgl_sampai': value_tgl_resep_sampai
								},
								success: (res) => {
									let row = '';
									if (res.status) {
										for (let i = 0; i < res.data.length; i++) {
											const item = res.data[i];
											row += `
            <tr>
              <td class="text-center">${i + 1}</td>
              <td class="text-center">${item.invoice}</td>
              <td class="text-center">${item.nama_pasien}</td>
              <td class="text-center">${item.nama_dokter}</td>
              <td class="text-center">${item.umur}</td>
              <td class="text-center">${item.berat_badan}</td>
              <td class="text-center">${item.no_telp}</td>
              <td class="text-center">${item.alamat}</td>
              <td class="text-right"><b>Rp. </b>${convertRupiah(parseInt(item.total_harga_resep))}</td>
              <td class="text-center">${item.tanggal}</td>
              <td>
                <div class="text-center">
                  <button class="btn btn-sm btn-icon btn-primary" onclick="get_obat(this, ${item.id})" id="btn-detail-obat-${item.id}"><i class="icon-info22 position-left"></i> Detail</button>
                </div>

								<!-- Large modal -->
									<div id="modal_tampil_obat_${item.id}" class="modal fade">
										<div class="modal-dialog modal-lg">
											<div class="modal-content">
												<div class="modal-header bg-primary">
													<button type="button" class="close" data-dismiss="modal">&times;</button>
													<h5 class="modal-title">Detail Resep</h5>
												</div>

												<div class="modal-body">
													<div class="table-responsive">
														<table class="table table-bordered table-striped table-hover">
															<thead>
																<tr class="bg-primary">
																	<th class="text-center">No. </th>
																	<th class="text-center">Nama Obat</th>
																	<th class="text-center">Jenis Obat</th>
																	<th class="text-center">Jumlah Obat</th>
																	<th class="text-center">Harga</th>
																	<th class="text-center">Total</th>
																</tr>
															</thead>
															<tbody id="tbody-obat-${item.id}">

															</tbody>
														</table>
													</div>
												</div>

												<div class="modal-footer">
													<button type="button" class="btn btn-link" data-dismiss="modal"><i class="icon-cross position-left"></i> Keluar</button>
												</div>
											</div>
										</div>
									</div>
									<!-- /large modal -->

              </td>
            </tr>
          `;
										}
									} else {
										row = `<tr>
          <td colspan="6" class="text-center">${res.message}</td>
        </tr>`;
									}

									$(`#tbody-resep-obat`).html(row);
									paging_resep();
								}
							});
						};





						let get_obat = (e, id_resep_obat) => {
							$.ajax({
								url: '<?= base_url('apotek/kasir/get_obat_api') ?>',
								method: 'GET',
								dataType: 'json',
								data: {
									'id_resep_obat': id_resep_obat
								},
								success: (res) => {
									let index = 1;
									let row = '';
									if (res.status) {
										for (const item of res.data) {
											row += `
							<tr>
								<td class="text-center">${index++}</td>
								<td class="text-center">${item.nama_barang}</td>
								<td class="text-center">${item.jenis_barang}</td>
								<td class="text-center"><span class="label label-primary">${item.jumlah_obat}</span></td>
								<td class="text-right"><b>Rp. </b>${convertRupiah(parseInt(item.harga_obat))}</td>
								<td class="text-right"><b>Rp. </b>${convertRupiah(parseInt(item.sub_total_obat))}</td>
							</tr>
						`
										}
									} else {
										row = `
						<tr>
							<td colspan="6" class="text-center">${res.message}</td>
						</tr>
					`;
									}


									$(`#tbody-obat-${id_resep_obat}`).html(row)
								},
							})

							$(`#modal_tampil_obat_${id_resep_obat}`).modal('show');
						}

						$(document).ready(function() {
							paging_resep();
						});

						function paging_resep($selector) {
							var jumlah_tampil = $("#select-show-data").val() || 10;

							if (typeof $selector === 'undefined') {
								$selector = $(".table-resep #tbody-resep-obat tr");
							}

							var itemsCount = $selector.length;

							window.tp = new Pagination('#pagination_resep', {
								itemsCount: itemsCount,
								pageSize: parseInt(jumlah_tampil),
								onPageChange: function(paging) {
									var start = paging.pageSize * (paging.currentPage - 1),
										end = start + paging.pageSize;

									$selector.hide().slice(start, end).show();
								}
							});
						}

						$(document).ready(function() {
							// Simpan tampilan awal tabel saat halaman pertama kali dimuat
							originalTableHTML = $('#tbody-resep-obat').html();

							// fungsi get_resep_obat saat halaman pertama kali dimuat
							get_resep_obat();

							// event listener untuk kolom inputan pencarian
							$('#cari_pasien').on('keyup', function() {
								// Cek apakah kolom inputan tidak diisi (kosong)
								if ($(this).val().trim() === '') {
									// Jika kolom inputan tidak diisi, panggil fungsi get_resep_obat untuk mendapatkan data awal
									get_resep_obat();
									
								} else {
									// Jika kolom inputan diisi, panggil fungsi pasien_result untuk melakukan pencarian data berdasarkan inputan yang dimasukkan
									paging_resep();
									pasien_result();
								}
							});
						});

						function pasien_result() {
							$('.loader').show();
							var search = $('#cari_pasien').val();

							$.ajax({
								url: '<?php echo base_url(); ?>apotek/kasir/pasien_result',
								data: {
									search: search
								},
								type: "POST",
								dataType: "json",
								success: function(result) {
									$table = "";

									if (result == "" || result == null) {
										$table = '<tr>' +
											'<td colspan="7" style="text-align:center;">Data Kosong</td>' +
											'</tr>';
									} else {
										var no = 0;
										for (var i = 0; i < result.length; i++) {
											no++;

											$table += '<tr>' +
												'<td style="text-align:left;">' + (i + 1) + '</td>' + // Corrected this line to display the correct row number
												'<td style="text-align:left;">' + result[i].invoice + '</td>' +
												'<td style="text-align:left;">' + result[i].nama_pasien + '</td>' +
												'<td style="text-align:left;">' + result[i].nama_dokter + '</td>' +
												'<td style="text-align:left;">' + result[i].umur + '</td>' +
												'<td style="text-align:left;">' + result[i].berat_badan + '</td>' +
												'<td style="text-align:left;">' + result[i].no_telp + '</td>' +
												'<td style="text-align:left;">' + result[i].alamat + '</td>' +
												'<td style="text-align:left;"><b>Rp. </b>' + convertRupiah(parseInt(result[i].total_harga_resep)) + '</td>' +
												'<td style="text-align:left;">' + result[i].tanggal + '</td>' +
												'<td style="text-align:center;">' +
												'<div class="text-center">' +
												'<button class="btn btn-sm btn-icon btn-primary" onclick="get_obat(this, ' + result[i].id + ')" id="btn-detail-obat-' + result[i].id + '"><i class="icon-info22 position-left"></i> Detail</button>' +
												'</div>' +

												'<div id="modal_tampil_obat_' + result[i].id + '" class="modal fade">' +
												'<div class="modal-dialog modal-lg">' +
												'<div class="modal-content">' +
												'<div class="modal-header bg-primary">' +
												'<button type="button" class="close" data-dismiss="modal">&times;</button>' +
												'<h5 class="modal-title">Detail Resep</h5>' +
												'</div>' +

												'<div class="modal-body">' +
												'<div class="table-responsive">' +
												'<table class="table table-bordered table-striped table-hover">' +
												'<thead>' +
												'<tr class="bg-primary">' +
												'<th class="text-center">No. </th>' +
												'<th class="text-center">Nama Obat</th>' +
												'<th class="text-center">Jenis Obat</th>' +
												'<th class="text-center">Jumlah Obat</th>' +
												'<th class="text-center">Harga</th>' +
												'<th class="text-center">Total</th>' +
												'</tr>' +
												'</thead>' +
												'<tbody id="tbody-obat-' + result[i].id + '">' +

												'</tbody>' +
												'</table>' +
												'</div>' +
												'</div>' +

												'<div class="modal-footer">' +
												'<button type="button" class="btn btn-link" data-dismiss="modal"><i class="icon-cross position-left"></i> Keluar</button>' +
												'</div>' +
												'</div>' +
												'</div>' +
												'</div>' +

												'</td>' +
												'</tr>';

										}
									}

									$('#tbody-resep-obat').html($table);
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