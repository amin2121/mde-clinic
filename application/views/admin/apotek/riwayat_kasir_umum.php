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

											<!-- message -->
											<?php if ($this->session->flashdata('status')): ?>
												<div class="alert alert-<?= $this->session->flashdata('status'); ?> no-border">
													<button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>
													<p class="message-text"><?= $this->session->flashdata('message'); ?></p>
											    </div>
											<?php endif ?>
											<!-- message -->
											<div class="form-search form-horizontal">
												<div class="row">
													<div class="col-sm-6">
														<div class="form-group">
															<label class="control-label col-sm-2"><b>Tanggal Dari</b></label>
															<div class="col-sm-10">
																<input type="text" class="form-control input-tgl" autocomplete="off" name="nama_barang" id="tanggal_dari" onkeyup="get_riwayat_kasir_umum()" placeholder="Dari Tanggal">
															</div>
														</div>
													</div>
													<div class="col-sm-6">
														<div class="form-group">
															<label class="control-label col-sm-3"><b>Tanggal Sampai</b></label>
															<div class="col-sm-9">
																<input type="text" class="form-control input-tgl col-sm-10" autocomplete="off" name="nama_barang" id="tanggal_sampai" onkeyup="get_riwayat_kasir_umum()" placeholder="Sampai Tanggal">
															</div>
														</div>
													</div>
												</div>
												<div class="row" style="margin-bottom: 1em;">
													<div class="col-sm-6">
														<button class="btn btn-primary" onclick="get_riwayat_kasir_umum()"><i class="fa fa-search position-left"></i> Cari</button>
														<button class="btn btn-success" onclick="get_riwayat_kasir_umum()"><i class="icon-book2 position-left"></i> Lihat Semua</button>
													</div>
												</div>

											</div>
											<div class="table-responsive">
												<table class="table table-bordered table-striped" id="table_riwayat_kasir_umum">
													<thead>
														<tr class="bg-success">
															<th class="text-center">No Transaksi</th>
															<th class="text-center">Nama Kasir</th>
															<th class="text-center">Nilai Transaksi</th>
															<th class="text-center">Laba</th>
															<th class="text-center">Dibayar</th>
															<th class="text-center">Tanggal & Waktu</th>
															<th class="text-center">Aksi</th>
														</tr>
													</thead>
													<tbody>

													</tbody>
												</table>
											</div>
											<br>
											<ul class="pagination"></ul>


										</div>
									</div>
								</div>
							</div>

<!-- Modal Hapus Pengeluaran -->
<div id="modal-hapus-transaksi" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header bg-danger">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h5 class="modal-title">Hapus Pembayaran</h5>
			</div>
			<form action="<?= base_url('apotek/riwayat_kasir_umum/hapus_riwayat_kasir_umum') ?>" method="POST">
			<div class="modal-body">
				<input type="hidden" name="id_riwayat_kasir_umum" id="id_riwayat_kasir_umum">
				<div class="alert alert-danger no-border">
					<p>Apakah Anda Ingin <span class="text-semibold">Menghapus</span> Transaksi Ini ?</p>
				</div>
				<div class="form-group">
					<input type="text" class="form-control" placeholder="Tuliskan Alasan" name="alasan" required>
				</div>
			</div>
			<div class="modal-footer">
				<button class="btn btn-link" data-dismiss="modal"><i class="icon-cross position-left"></i> Keluar</button>
				<button type="submit" class="btn btn-danger"><i class="icon-trash position-left"></i> Hapus</button>
			</div>
		</form>
		</div>
	</div>
 </div>
<!-- /Modal Hapus Pengeluaran -->

<script>
$(window).load(function() {
	$('.input-tgl').datepicker({
        dateFormat : 'dd-mm-yy',
        autoclose: true,
        language: 'fr',
        orientation: 'bottom auto',
        todayBtn: 'linked',
        todayHighlight: true
    });

    get_riwayat_kasir_umum();
})

function get_riwayat_kasir_umum() {
	let input_tanggal_dari = $(`#tanggal_dari`).val();
	let input_tanggal_sampai = $(`#tanggal_sampai`).val();

	$.ajax({
		url : '<?= base_url('apotek/riwayat_kasir_umum/get_riwayat_kasir_umum') ?>',
		method : 'POST',
		data : {
			'tanggal_dari' 		: `${input_tanggal_dari}`,
			'tanggal_sampai'	: `${input_tanggal_sampai}`
		},
		dataType : 'json',
		beforeSend : () => {
			let loading = `<tr id="tr-loading">
								<td colspan="7">
									<div class="loader">
										<img src="<?= base_url('assets/images/svg-loader/svg-loaders/vectorpaint.svg') ?>" alt="">
									</div>
								</td>
							</tr>`

			$(`#table_riwayat_kasir_umum tbody`).html(loading);
		},
		success : (res) => {
			let row = '';

			if(res.status) {
				let i = 0;
				for(const item of res.data) {
					get_detail_riwayat_kasir_umum(item.id);
					row += 	`
						<tr>
							<td class="text-center">${item.no_transaksi}</td>
							<td class="text-center">${item.nama}</td>
							<td class="text-center"><b>Rp. </b>${NumberToMoney(item.nilai_transaksi)}</td>
							<td class="text-center"><b>Rp. </b>${NumberToMoney(item.total_laba)}</td>
							<td class="text-center"><b>Rp. </b>${NumberToMoney(item.dibayar)}</td>
							<td class="text-center">${item.tanggal} ${item.waktu}</td>
							<td>
								<div class="text-center">
									<button type="button" class="btn btn-sm btn-icon btn-success" onclick="print_struk_kasir_umum(${item.id})"><i class="icon-printer2 position-left"></i> Print</button>
									<button type="button" class="btn btn-sm btn-icon btn-info" data-toggle="modal" data-target="#modal_detail_kasir_umum_${item.id}"><i class="fa fa-search position-left"></i> Detail</button>
									<button type="button" class="btn btn-sm btn-icon btn-danger" onclick="hapus_riwayat_kasir_umum(${item.id})"><i class="icon-trash position-left"></i> Hapus</a>
								</div>
								<!-- Modal Hapus Riwayat Kasir Umum -->
								<div id="modal_hapus_riwayat_${item.id}" class="modal fade">
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-header bg-danger">
												<button type="button" class="close" data-dismiss="modal">&times;</button>
												<h5 class="modal-title">Hapus Riwayat Transaksi</h5>
											</div>

											<div class="modal-body">

											</div>

											<div class="modal-footer">
												<button class="btn btn-link" data-dismiss="modal"><i class="icon-cross"></i> Keluar</button>
											</div>
										</div>
									</div>
								</div>
								<!-- /Modal Hapus Riwayat Kasir Umum -->

								<!-- Modal Detail Kasir Umum -->
								<div id="modal_detail_kasir_umum_${item.id}" class="modal fade">
									<div class="modal-dialog modal-lg">
										<div class="modal-content">
											<div class="modal-header bg-primary">
												<button type="button" class="close" data-dismiss="modal">&times;</button>
												<h5 class="modal-title">Detail Kasir Umum</h5>
											</div>

											<div class="modal-body">
												<div class="table-responsive" style="margin-top: 2em;">
													<table class="table table-bordered table-striped" id="table_detail_kasir_umum_${item.id}">
														<thead>
															<tr class="bg-success">
																<th class="text-center">Kode Barang</th>
																<th class="text-center">Nama Barang</th>
																<th class="text-center">Jumlah Beli</th>
																<th class="text-center">Harga Barang</th>
															</tr>
														</thead>
														<tbody>

														</tbody>
														<tfoot>

														</tfoot>
													</table>
												</div>
											</div>

											<div class="modal-footer">
												<button class="btn btn-link" data-dismiss="modal"><i class="icon-cross"></i> Keluar</button>
											</div>
										</div>
									</div>
								</div>
								<!-- /Modal Detail Kasir Umum -->
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
			$(`#table_riwayat_kasir_umum tbody`).html(row);
			pagination()
		},
		complete : () => {$(`#tr-loading`).hide()}
	})

	$(`#tanggal_dari`).val('');
	$(`#tanggal_sampai`).val('');
}

function print_struk_kasir_umum(id_penjualan) {
	window.open('<?php echo base_url(); ?>apotek/riwayat_kasir_umum/print_struk_kasir_umum/' + id_penjualan, '_blank','location=yes,height=570,width=520,scrollbars=yes,status=yes');
}

function hapus_riwayat_kasir_umum(id) {
	$(`#modal-hapus-transaksi`).modal('toggle');
	$(`#id_riwayat_kasir_umum`).val(id);
}

function get_detail_riwayat_kasir_umum(id) {
	let loading = `<tr id="tr-loading">
								<td colspan="7">
									<div class="loader">
										<img src="<?= base_url('assets/images/svg-loader/svg-loaders/vectorpaint.svg') ?>" alt="">
									</div>
								</td>
							</tr>`

	$(`#modal_detail_kasir_umum_${id} #table_detail_kasir_umum_${id} tbody`).html(loading);

	$.ajax({
		url : '<?= base_url('apotek/riwayat_kasir_umum/get_detail_riwayat_kasir_umum') ?>',
		method : 'POST',
		data : {id_penjualan : id},
		dataType : 'json',
		success : (res) => {
			let row = '';
			let total_harga = 0

			if(res.status) {
				let i = 0;
				for(const item of res.data) {
					row += `
						<tr>
							<td class="text-center">${item.kode_barang}</td>
							<td class="text-center">${item.nama_barang}</td>
							<td class="text-center">${item.jumlah_beli}</td>
							<td class="text-center"><b>Rp. </b>${NumberToMoney(item.harga_jual)}</td>
						</tr>
					`
					total_harga += parseFloat(item.harga_jual);
				}
			} else {
				row += `
					<tr>
						<td colspan="4" class="text-center">${res.message}</td>
					</tr>
				`
			}
			var tfoot = `
				<tr>
					<td colspan="3" class="text-right"><b>Total</b></td>
					<td class="text-center"><b>Rp. </b>${NumberToMoney(total_harga)}</td>
				</tr>
			`

			$(`#modal_detail_kasir_umum_${id} #table_detail_kasir_umum_${id} tbody`).html(row);
			$(`#modal_detail_kasir_umum_${id} #table_detail_kasir_umum_${id} tfoot`).html(tfoot);
			$(`#modal_detail_kasir_umum_${id} #table_detail_kasir_umum_${id} tbody #tr-loading`).hide()
		}
	})
}

function pagination($selector){
	var jumlah_tampil = '10';

    if(typeof $selector == 'undefined'){
        $selector = $("#table_riwayat_kasir_umum tbody tr");
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

<!--

+62 895-1583-0336

tempat laporan progress diubah struktur nya
buat laporan my packing.
tambah menu my packing
keterangan sisa di progress. -->
