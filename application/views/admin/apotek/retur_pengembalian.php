<!DOCTYPE html>
<html lang="en">

<head>
	<?php $this->load->view('admin/css'); ?>
	<script src="<?php echo base_url('assets/js/plugins/tables/datatables/datatables.min.js'); ?>"></script>
	<script type="text/javascript">
		$(function() {
			$('.diagnosa').DataTable();
		});

		function modal_retur() {
			$('#btn_modal_pasien').click();
		}
		function get_retur() {
    var search = $('#search_pasien').val();

    $.ajax({
        url: '<?php echo base_url(); ?>apotek/Retur_pengembalian/get_retur_pengembalian',
        data: {
            search: search
        },
        type: "POST",
        dataType: "json",
        success: function(result) {
            $tr = "";

            if (result == "" || result == null) {
                $tr = "<tr><td colspan='4' style='text-align:center;'><b>Data tidak ditemukan</b></td></tr>";
            } else {
                var no = 0;
                for (var i = 0; i < result.length; i++) {
                    no++;

                    $tr +=
					'<tr style="cursor:pointer;" onclick="klik_retur_pengembalian(' + result[i].id + ');">' +
					'<td style="text-align:center;">' + (result[i].no_faktur !== '' ? result[i].no_faktur : '-') + '</td>' +
					'<td style="text-align:center;">Rp.' + convertRupiah(parseInt(result[i].total_harga_beli)).replace(/\./g, ',') + '</td>'+					'<td style="text-align:center;">' + result[i].tipe_pembayaran + '</td>' +
					'<td style="text-align:center;">' + result[i].tanggal_pembayaran + '</td>' +
					'</tr>';


                }
            }

            $('.table_data_pasien tbody').html($tr);
            pagination_pasien();
        }
    });

    $('#search_pasien').off('keyup').keyup(function() {
        get_retur();
    });
}

		function pagination_pasien($selector) {
			var jumlah_tampil = '10';

			if (typeof $selector == 'undefined') {
				$selector = $(".table_data_pasien tbody tr");
			}
			window.tp = new Pagination('#pagination_pasien', {
				itemsCount: $selector.length,
				pageSize: parseInt(jumlah_tampil),
				onPageSizeChange: function(ps) {},
				onPageChange: function(paging) {
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

					<!-- message -->
					<?php if ($this->session->flashdata('status')) : ?>
						<div class="alert alert-<?= $this->session->flashdata('status'); ?> no-border">
							<button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>
							<p class="message-text"><?= $this->session->flashdata('message'); ?></p>
						</div>
					<?php endif ?>
					<!-- message -->

					<div class="row">

						<div class="col-sm-12">
							<!-- Tambahkan elemen div berikut di tempat yang sesuai di halaman HTML -->
							<div id="success-message" style="display: none;"></div>
							<div id="error-message" style="display: none;"></div>

							<div class="panel panel-flat border-top-success border-top-lg">
								<div class="panel-heading">
									<h6 class="panel-title">Retur pengembalian</h6>
								</div>

								<div class="panel-body">
									<form action="<?= base_url('apotek/retur_pengembalian/tambah_retur_pengembalian') ?>" method="POST">
										<div class="form-group">
											<div class="input-group">
												<span class="input-group-btn">
													<button class="btn bg-primary btn-sm" onclick="modal_retur(); get_retur();" type="button" style="margin-bottom:20px;">
														<i class="fa fa-search position-left"></i> Pilih
													</button>
												</span>
												<input type="text" id="no_faktur" name="no_faktur" class="form-control" placeholder="no faktur" readonly>
												<input type="hidden" id="id_pasien" name="id_pasien" class="form-control">
												<input type="hidden" id="id_penjualan" name="id_penjualan" class="form-control">
											</div>
										</div>
										<div class="table-responsive">
											<table class="table table-striped table-hover">
												<thead class="bg-primary">
													<tr>
														<th style="text-align:center;" >Nama Barang</th>
														<th style="text-align:center;">Kode Barang</th>
														<th style="text-align:center;">jumlah beli</th>
														<th style="text-align:center;">dikembalikan</th>
													</tr>
												</thead>
												<tbody id="tbody-cart">
													<!-- Dynamic rows will be inserted here using JavaScript -->
												</tbody>
												<tfoot>
													<tr>
														<td colspan="3" class="text-right text-semibold">
															<h2>Total (Rp.)</h2>
														</td>
														<td colspan="2">
															<h2 class="text-center text-semibold" id="nilai-transaksi">: -</h2>
														</td>
													</tr>
													<tr>
														<td colspan="4" class="text-right">
															<button class="btn btn-md btn-primary" type="submit" id="btn-checkout">
																<i class="icon-cash2 position-left"></i> Simpan
															</button>
														</td>
													</tr>
												</tfoot>
											</table>
										</div>
									</form>
								</div>

							</div>
						</div>

					</div>
				</div>

				<button type="button" class="btn btn-primary btn-sm" id="btn_modal_pasien" data-toggle="modal" data-target="#modal_pasien" style="display:none;">Launch <i class="icon-play3 position-right"></i></button>
				<div id="modal_pasien" class="modal fade">
					<div class="modal-dialog modal-lg">
						<div class="modal-content">
							<div class="modal-header bg-primary">
								<button type="button" class="close" data-dismiss="modal">&times;</button>
								<h6 class="modal-title">Data Pasien</h6>
							</div>

							<div class="modal-body">
								<div class="form-group">
									<div class="input-group">
										<input type="text" id="search_pasien" placeholder="Cari Berdasarkan No Faktur" class="form-control">
										<span class="input-group-btn">
											<button class="btn bg-primary" type="button"><i class="fa fa-search"></i></button>
										</span>
									</div>
								</div>
								<div class="table-responsive">
									<table class="table table-bordered table-hover table-striped table_data_pasien">
										<thead>
											<tr class="bg-primary">
												<th class="text-center">No faktur</th>
												<th class="text-center">jumlah transaksi</th>
												<th class="text-center">tipe pembayaran </th>
												<th class="text-center">Tanggal </th>
											</tr>
										</thead>
										<tbody>

										</tbody>
									</table>
								</div>
								<br>
								<div id="pagination_pasien"></div>
							</div>

							<div class="modal-footer">
								<button type="button" class="btn btn-link" id="tutup_data_pasien" data-dismiss="modal"><i class="icon-cross"></i> Tutup</button>
							</div>
						</div>
					</div>
				</div>

				<script>
					function klik_retur_pengembalian(id) {
						$('#tutup_data_pasien').click();

						$.ajax({
								url: '<?php echo base_url(); ?>apotek/Retur_pengembalian/klik_retur_pengembalian',
								data: {
									id: id
								},
								type: "POST",
								dataType: "json",
							})
							.done(function(data) {
								$('#tbody-cart').empty(); // Clear the table body first

								$('#no_faktur').val(data[0].no_faktur);
								$('#tanggal_pembayaran').val(data[0].tanggal_pembayaran);
								$('#nama_barang').val(data[0].nama_barang);
								$('#tipe_pembayaran').val(data[0].tipe_pembayaran);
								$('#harga_awal').val(data[0].harga_awal);
								$('#jumlah_beli').val(data[0].jumlah_beli);

								let totalTransaksi = 0;



								$.each(data, function(index, row) {

									let totalHargaJual = parseFloat(row.harga_awal) * parseFloat(row.jumlah_beli);
									totalTransaksi += totalHargaJual;

									let jumlah_data = $(`.row-${row.id}`).length;
									// Handle the row data using ES6 template literals
									let newRow = `
									<tr id="row-${row.id}" class="row-${row.id}" style="padding-right:200px;">
									<td style="text-align:center;"><span id="nama-barang-${row.id}" name="nama_barang[]" class="nama-barang-${row.id}">${row.nama_barang}</span></td>
									<td style="text-align:center;"><span id="kode-barang-${row.id}" name="kode_barang[]" class="kode-barang-${row.id}">${row.kode_barang}</span></td>									
									<td style="text-align:center;">
										<span id="jumlah-beli-${row.id}" name="jumlah_beli[]" class="jumlah-beli-${row.id}">${row.jumlah_beli}</span>
										<input type="hidden" name="id_barang[]" value="${row.id_barang}">
										<input type="hidden" name="kode_barang[]" value="${row.kode_barang}">
										<input type="hidden" name="nama_barang[]" value="${row.nama_barang}">
										<input type="hidden" name="id_supplier" value="${row.id_supplier}">
										<input type="hidden" name="id_faktur" value="${row.id_faktur}">
										<input type="hidden" name="tanggal_kadaluarsa" value="${row.tanggal_kadaluarsa}">
										<input type="hidden" name="tipe_pembayaran" value="${row.tipe_pembayaran}">
										<input type="hidden" name="updated_at" value="${row.updated_at}">
										<input type="hidden" name="persentase" value="${row.persentase}">
										<input type="hidden" name="harga_awal" value="${row.harga_awal}">
										<input type="hidden" name="ppn" value="${row.ppn}">
										<input type="hidden" name="laba[]" value="${row.laba}">
										<input type="hidden" name="waktu[]" value="${row.waktu}">
										<input type="hidden" name="tanggal[]" value="${row.tanggal}">
										<input type="hidden" name="created_at[]" value="${row.created_at}">

										</td>
										
									<td style="text-align:center;">
										<span><input type="text" size="5" style="text-align:center;" class="form-control jumlah-beli-${row.id}" id="jumlah-beli-${row.id}" name="jumlah_beli[]" onkeyup="hitung_total_harga_beli(${row.id}, '${row.harga_jual}', '${row.laba}');"></span>
										<input type="text" hidden="" id="total-harga-beli-${row.id}" nama="total_harga" value="${parseInt(row.harga_jual)}" class="total-harga-beli" name="total_harga_beli[]">
										</td>

									</tr>
								`;

									$('#tbody-cart').append(newRow);
								});
								$('#nilai-transaksi').text("Rp." + convertRupiah(parseInt(totalTransaksi)));
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