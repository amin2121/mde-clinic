<!DOCTYPE html>
<html lang="en">
<head>
<?php $this->load->view('admin/css'); ?>
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
					<div class="form-search form-horizontal">
						<div class="row">
							<div class="col-sm-6">
								<div class="form-group">
									<label class="control-label col-sm-2"><b>Tanggal Dari</b></label>
									<div class="col-sm-10">
										<input type="text" class="form-control input-tgl" autocomplete="off" name="tgl_dari" id="input-tgl-dari">
									</div>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label class="control-label col-sm-3"><b>Tanggal Sampai</b></label>
									<div class="col-sm-8">
										<input type="text" class="form-control input-tgl" autocomplete="off" name="tgl_sampai" id="input-tgl-sampai">
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-6">
								<button class="btn btn-md btn-primary" type="button" onclick="cari_riwayat_pembayaran(this)"><i class="icon-search4"></i> Cari</button>
								<button class="btn btn-md btn-success" type="button" onclick="cari_riwayat_pembayaran(this)"><i class="icon-book2"></i> Lihat Semua</button>
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
								<table class="table table-bordered table-striped table-hover" id="table-riwayat-pembayaran">
									<thead>
										<tr class="bg-success">
											<th class="text-center">Invoice</th>
											<th class="text-center">Nama Pasien</th>
											<th class="text-center">Nama Kasir</th>
											<th class="text-center">Total Invoice</th>
											<th class="text-center">Bayar</th>
											<th class="text-center">Kembali</th>
											<th class="text-center">Tanggal</th>
											<th class="text-center">Aksi</th>
										</tr>
									</thead>
									<tbody id="tbody-riwayat-pembayaran">
									</tbody>
								</table>
							</div>
							<br>
							<div id="pagination_riwayat_pembayaran"></div>
						</div>
					</div>
				</div>

			</div>

			<!-- Modal Hapus Pengeluaran -->
			<a href="#" style="display:none;" class="btn btn-md btn-icon btn-danger" id="klik_hapus_transaksi" data-toggle="modal" data-target="#modal-hapus-transaksi"><i class="icon-trash position-left"></i> Hapus</a>
			<div id="modal-hapus-transaksi" class="modal fade">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header bg-danger">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
							<h5 class="modal-title">Hapus Pembayaran</h5>
						</div>
						<form action="<?= base_url('resepsionis/riwayat_pembayaran/hapus_transaksi') ?>" method="POST">
						<div class="modal-body">
							<input type="hidden" name="id_registrasi" id="id_registrasi">
							<div class="alert alert-danger no-border">
								<p>Apakah Anda Ingin <span class="text-semibold">Menghapus</span> Pembayaran Ini ?</p>
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

		$.ajax({
			url : '<?= base_url('resepsionis/riwayat_pembayaran/riwayat_pembayaran_ajax') ?>',
			method : 'GET',
			dataType : 'json',
			success : (res) => {
				let row = '';
				if(res.status) {
					let i = 0;
					for(const item of res.data) {

						row += `
							<tr>
								<td class="text-center">${item.invoice}</td>
								<td class="text-center">${item.nama_pasien}</td>
								<td class="text-center">${item.nama_kasir}</td>
								<td class="text-center"><b>Rp. </b>${NumberToMoney(item.total_invoice)}</td>
								<td class="text-center"><b>Rp. </b>${NumberToMoney(item.bayar)}</td>
								<td class="text-center"><b>Rp. </b>${NumberToMoney(item.kembali)}</td>
								<td class="text-center">${item.tanggal}</td>
								<td class="text-center">
									<button type="button" class="btn btn-primary btn-sm" onclick="window.open('<?php echo base_url(); ?>resepsionis/pembayaran/cetak_nota/${item.id_registrasi}', '_blank','location=yes,height=570,width=520,scrollbars=yes,status=yes');"><i class="icon-printer2 position-left"></i> Print </button>
									<button type="button" class="btn btn-danger btn-sm" onclick="popup_hapus_transaksi(${item.id_registrasi});"><i class="icon-trash position-left"></i> Hapus</button>
								</td>
							</tr>
						`
					}

				} else {
					row += `<tr>
						<td colspan="10" class="text-center">${res.message}</td>
					</tr>`
				}
				$(`#tbody-riwayat-pembayaran`).html(row);
				paging_riwayat_pembayaran();
			}
		});
	});

	function popup_hapus_transaksi(id_registrasi){
		$('#klik_hapus_transaksi').click();
		$('#id_registrasi').val(id_registrasi);
	}

	function paging_riwayat_pembayaran($selector){
		var jumlah_tampil = '10';

	    if(typeof $selector == 'undefined'){
	        $selector = $("#table-riwayat-pembayaran tbody tr");
	    }
			window.tp = new Pagination('#pagination_riwayat_pembayaran', {
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

	function cari_riwayat_pembayaran(e) {
		let value_tgl_dari = $(`#input-tgl-dari`).val();
		let value_tgl_sampai = $(`#input-tgl-sampai`).val();

		$.ajax({
			url : '<?= base_url('resepsionis/riwayat_pembayaran/riwayat_pembayaran_ajax') ?>',
			data : {'tgl_dari' : `${value_tgl_dari}`, 'tgl_sampai' : `${value_tgl_sampai}`},
			method : 'GET',
			dataType : 'json',
			success : (res) => {
								let row = '';
				if(res.status) {
					let i = 0;
					for(const item of res.data) {

						row += `
							<tr>
								<td class="text-center">${item.invoice}</td>
								<td class="text-center">${item.nama_pasien}</td>
								<td class="text-center">${item.nama_kasir}</td>
								<td class="text-center"><b>Rp. </b>${NumberToMoney(item.total_invoice)}</td>
								<td class="text-center"><b>Rp. </b>${NumberToMoney(item.bayar)}</td>
								<td class="text-center"><b>Rp. </b>${NumberToMoney(item.kembali)}</td>
								<td class="text-center">${item.tanggal}</td>
								<td class="text-center">
									<button type="button" class="btn btn-primary btn-sm" onclick="window.open('<?php echo base_url(); ?>resepsionis/pembayaran/cetak_nota/${item.id_registrasi}', '_blank','location=yes,height=570,width=520,scrollbars=yes,status=yes');"><i class="fa fa-print position-left"></i> Print </button>
									<button type="button" class="btn btn-danger btn-sm" onclick="popup_hapus_transaksi(${item.id_registrasi});"><i class="icon-trash position-left"></i> Hapus</button>
								</td>
							</tr>
						`
					}

				} else {
					row += `<tr>
						<td colspan="10" class="text-center">${res.message}</td>
					</tr>`
				}
				$(`#tbody-riwayat-pembayaran`).html(row);
				paging_riwayat_pembayaran();
			}
		})

		$(`#input-tgl-dari`).val('');
		$(`#input-tgl-sampai`).val('');
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
