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

											<div class="form-search form-horizontal">
												<div class="row">
													<div class="col-sm-6">
														<div class="form-group">
															<label class="control-label col-sm-2"><b>Tanggal Dari</b></label>
															<div class="col-sm-10">
																<input type="text" class="form-control input-tgl" autocomplete="off" name="nama_barang" id="tanggal_dari" onkeyup="get_hapus_riwayat_kasir_umum()" placeholder="Dari Tanggal">
															</div>
														</div>
													</div>
													<div class="col-sm-6">
														<div class="form-group">
															<label class="control-label col-sm-3"><b>Tanggal Sampai</b></label>
															<div class="col-sm-9">
																<input type="text" class="form-control input-tgl col-sm-10" autocomplete="off" name="nama_barang" id="tanggal_sampai" onkeyup="get_hapus_riwayat_kasir_umum()" placeholder="Sampai Tanggal">
															</div>
														</div>
													</div>
												</div>
												<div class="row" style="margin-bottom: 1em;">
													<div class="col-sm-2">
														<button class="btn btn-primary" onclick="get_hapus_riwayat_kasir_umum()"><i class="fa fa-search position-left"></i> Cari</button>
													</div>
												</div>

											</div>
											<button type="button" class="btn btn-md btn-danger" style="margin-bottom: 2em;" onclick="tampil_modal_hapus_semua()"><i class="icon-trash position-left"></i> Hapus Semua</button>

											<!-- Modal Hapus Riwayat Penjualan Farmasi -->
											<div id="modal_hapus_semua" class="modal fade">
												<div class="modal-dialog">
													<div class="modal-content">
														<div class="modal-header bg-danger">
															<button type="button" class="close" data-dismiss="modal">&times;</button>
															<h5 class="modal-title">Hapus Semua Riwayat Kasir Umum</h5>
														</div>
														<form action="<?= base_url('farmasi/riwayat_hapus_kasir_umum/hapus_semua_riwayat_kasir_umum') ?>" method="POST">
															<div class="modal-body">
																<div class="alert alert-danger no-border">
																	<p>Apakah Anda Ingin <span class="text-semibold">Menghapus</span> Semua Riwayat Kasir Umum Ini ?</p>
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
											<!-- /Modal Hapus Riwayat Penjualan Farmasi -->

											<div class="table-responsive">
												<table class="table table-bordered table-striped" id="table_hapus_riwayat_kasir_umum">
													<thead>
														<tr class="bg-success">
															<th class="text-center">No Transaksi</th>
															<th class="text-center">Nama Kasir</th>
															<th class="text-center">Nilai Transaksi</th>
															<th class="text-center">User Yang Menghapus</th>
															<th class="text-center">Alasan</th>
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
	<!-- Modal Detail Riwayat hapus Kasir Umum -->
	<div id="modal_detail_hapus_riwayat_kasir_umum" class="modal fade">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header bg-primary">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h5 class="modal-title">Detail Kasir Umum</h5>
				</div>

				<div class="modal-body">
					<div class="tabbable">
						<ul class="nav nav-tabs nav-tabs-component">
							<li class="active"><a href="#detail_hapus_penjualan" data-toggle="tab">Detail Hapus Penjualan</a></li>
							<li><a href="#data_penjualan_detail" data-toggle="tab">Data Penjualan Detail</a></li>
						</ul>

						<div class="tab-content" style="margin-bottom: 2em !important;">
							<div class="tab-pane active" id="detail_hapus_penjualan">
								<div class="row">
									<div class="col-sm-6">
										<div class="form-group">
				                           <label class="control-label"><b>Nama Pasien</b></label>
				                           <input required id="nama_pasien" type="text" class="form-control" readonly>
				                         </div>
				                         <div class="form-group">
				                           <label class="control-label"><b>Nama Poli</b></label>
				                           <input required id="nama_poli" type="text" class="form-control" readonly>
				                         </div>
				                         <div class="form-group">
				                           <label class="control-label"><b>Nama Kasir</b></label>
				                           <input required id="nama_kasir" type="text" class="form-control" readonly>
				                         </div>
				                         <div class="form-group">
				                           <label class="control-label"><b>Tanggal & Waktu Pembayaran</b></label>
				                           <input required id="tanggal_waktu_pembayaran" type="text" class="form-control" readonly>
				                         </div>
				                         <div class="form-group">
				                           <label class="control-label"><b>Nama Kasir Yg Menghapus</b></label>
				                           <input required id="nama_kasir_hapus" type="text" class="form-control" readonly>
				                         </div>
				                         <div class="form-group">
				                           <label class="control-label"><b>Tanggal & Waktu Hapus</b></label>
				                           <input required id="tanggal_waktu_hapus" type="text" class="form-control" readonly>
				                         </div>
				                         <div class="form-group">
				                           <label class="control-label"><b>Alasan</b></label>
				                           <input required id="alasan" type="text" class="form-control" readonly>
				                         </div>
									</div>
									<div class="col-sm-6">
										<div class="form-group">
				                           <label class="control-label"><b>No Transaksi</b></label>
				                           <input required id="no_transaksi" type="text" class="form-control" readonly>
				                         </div>
				                         <div class="form-group">
				                           <label class="control-label"><b>Nilai Transaksi</b></label>
				                           <input required id="nilai_transaksi" type="text" class="form-control" readonly>
				                         </div>
				                         <div class="form-group">
				                           <label class="control-label"><b>Bayar</b></label>
				                           <input required id="bayar" type="text" class="form-control" readonly>
				                         </div>
				                         <div class="form-group">
				                           <label class="control-label"><b>kembali</b></label>
				                           <input required id="kembali" type="text" class="form-control" readonly>
				                         </div>
									</div>
								</div>
							</div>
							<div class="tab-pane" id="data_penjualan_detail">
								<div class="table-responsive" style="margin-top: 2em;">
									<table class="table table-bordered table-striped" id="table_detail_hapus_riwayat_kasir_umum">
										<thead>
											<tr class="bg-success">
												<th class="text-center">Kode Barang</th>
												<th class="text-center">Nama Barang</th>
												<th class="text-center">Jumlah Beli</th>
												<th class="text-center">Harga Barang</th>
												<th class="text-center">Sub Total</th>
											</tr>
										</thead>
										<tbody>

										</tbody>
										<tfoot>

										</tfoot>
									</table>
								</div>
							</div>
						</div>
					</div>

				</div>

				<div class="modal-footer">
					<button class="btn btn-link" data-dismiss="modal"><i class="icon-cross"></i> Keluar</button>
				</div>
			</div>
		</div>
	</div>
	<!-- /Modal Detail Riwayat hapus Kasir Umum -->
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

    get_hapus_riwayat_kasir_umum();
})

function tampil_modal_hapus_semua() {
	$(`#modal_hapus_semua`).modal('toggle');
}

function get_hapus_riwayat_kasir_umum() {
	let input_tanggal_dari = $(`#tanggal_dari`).val();
	let input_tanggal_sampai = $(`#tanggal_sampai`).val();

	$.ajax({
		url : '<?= base_url('farmasi/riwayat_hapus_kasir_umum/get_riwayat_hapus_kasir_umum') ?>',
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

			$(`#table_hapus_riwayat_kasir_umum tbody`).html(loading);
		},
		success : (res) => {
			let row = '';

			if(res.status) {
				let i = 0;
				for(const item of res.data) {
					row += 	`
						<tr>
							<td class="text-center">${item.no_transaksi}</td>
							<td class="text-center">${item.nama}</td>
							<td class="text-center"><b>Rp. </b>${NumberToMoney(item.nilai_transaksi)}</td>
							<td class="text-center">${item.nama_kasir_hapus}</td>
							<td class="text-center">${item.alasan}</td>
							<td class="text-center">${item.tanggal_hapus} ${item.waktu_hapus}</td>
							<td>
								<div class="text-center">
									<button type="button" class="btn btn-sm btn-icon btn-info" onclick="detail_hapus_riwayat_kasir_umum(${item.id_penjualan}, ${item.id})"><i class="icon-eye position-left"></i> Detail</button>
								</div>
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
			$(`#table_hapus_riwayat_kasir_umum tbody`).html(row);
			pagination()
		},
		complete : () => {$(`#tr-loading`).hide()}
	})
}

function detail_hapus_riwayat_kasir_umum(id_penjualan, id_riwayat_hapus_kasir_umum) {
	$(`#modal_detail_hapus_riwayat_kasir_umum`).modal('toggle');

	$.ajax({
		url : '<?= base_url('farmasi/riwayat_hapus_kasir_umum/get_detail_hapus_riwayat_kasir_umum') ?>',
		data : {'id_penjualan' : id_penjualan, 'id_riwayat_hapus_kasir_umum' : id_riwayat_hapus_kasir_umum},
		method : 'POST',
		dataType : 'json',
		success : (res) => {
			var row = ''
			var total = 0;
			if(res.status) {
				$(`#modal_detail_hapus_riwayat_kasir_umum #nama_pasien`).val(res.data_riwayat_hapus_kasir_umum.nama_pasien);
				$(`#modal_detail_hapus_riwayat_kasir_umum #nama_poli`).val(res.data_riwayat_hapus_kasir_umum.asal_poli);
				$(`#modal_detail_hapus_riwayat_kasir_umum #nama_kasir`).val(res.data_riwayat_hapus_kasir_umum.nama);
				$(`#modal_detail_hapus_riwayat_kasir_umum #tanggal_waktu_pembayaran`).val(`${res.data_riwayat_hapus_kasir_umum.tanggal} ${res.data_riwayat_hapus_kasir_umum.waktu}`);
				$(`#modal_detail_hapus_riwayat_kasir_umum #nama_kasir_hapus`).val(res.data_riwayat_hapus_kasir_umum.nama_kasir_hapus);
				$(`#modal_detail_hapus_riwayat_kasir_umum #tanggal_waktu_hapus`).val(`${res.data_riwayat_hapus_kasir_umum.tanggal_hapus} ${res.data_riwayat_hapus_kasir_umum.waktu_hapus}`);
				$(`#modal_detail_hapus_riwayat_kasir_umum #alasan`).val(res.data_riwayat_hapus_kasir_umum.alasan);
				$(`#modal_detail_hapus_riwayat_kasir_umum #no_transaksi`).val(res.data_riwayat_hapus_kasir_umum.no_transaksi);
				$(`#modal_detail_hapus_riwayat_kasir_umum #nilai_transaksi`).val(NumberToMoney(res.data_riwayat_hapus_kasir_umum.nilai_transaksi));
				$(`#modal_detail_hapus_riwayat_kasir_umum #bayar`).val(NumberToMoney(res.data_riwayat_hapus_kasir_umum.dibayar));
				$(`#modal_detail_hapus_riwayat_kasir_umum #kembali`).val(NumberToMoney(res.data_riwayat_hapus_kasir_umum.kembali));

				for(const item of res.data_detail_riwayat_hapus_kasir_umum) {
					row += `
						<tr>
							<td>${item.kode_barang}</td>
							<td>${item.nama_barang}</td>
							<td>${item.jumlah_beli}</td>
							<td><b>Rp. </b>${NumberToMoney(item.harga_jual)}</td>
							<td><b>Rp. </b>${NumberToMoney(item.subtotal)}</td>
						</tr>
					`

					total += parseFloat(item.subtotal);
				}
			} else {
				row += `
					<tr>
						<td colspan="5">${res.message}</td>
					</tr>
				`
			}

			var tfoot = `
				<tr>
					<td colspan="4" class="text-right"><b>Total</b></td>
					<td><b>Rp. </b>${NumberToMoney(total)}</td>
				</tr>
			`

			$(`#modal_detail_hapus_riwayat_kasir_umum #table_detail_hapus_riwayat_kasir_umum tbody`).html(row)
			$(`#modal_detail_hapus_riwayat_kasir_umum #table_detail_hapus_riwayat_kasir_umum tfoot`).html(tfoot)
		}
	})
}

function pagination($selector){
	var jumlah_tampil = '10';

    if(typeof $selector == 'undefined'){
        $selector = $("#table_hapus_riwayat_kasir_umum tbody tr");
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
