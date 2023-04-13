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
					<div class="form-search form-horizontal">
						<div class="row">
							<div class="col-sm-6">
								<div class="form-group">
									<label class="control-label col-sm-2"><b>Tanggal Dari</b></label>
									<div class="col-sm-10">
										<input type="text" class="form-control input-tgl" autocomplete="off" name="tgl_dari" id="input-tgl-dari">
									</div>
								</div>
								<button class="btn btn-md btn-primary" type="button" onclick="cari_riwayat_penjualan(this)"><i class="icon-search4 position-left"></i> Cari</button>
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
								<table class="table table-bordered table-striped table-hover" id="table-riwayat-penjualan">
									<thead>
										<tr class="bg-success">
											<th class="text-center">Invoice</th>
											<th class="text-center">Nama Pelanggan</th>
											<th class="text-center">Nama Kasir</th>
											<th class="text-center">Total Invoice</th>
											<th class="text-center">Bayar</th>
											<th class="text-center">Kembali</th>
											<th class="text-center">Tanggal</th>
											<th class="text-center">Aksi</th>
										</tr>
									</thead>
									<tbody id="tbody-riwayat-penjualan">
									</tbody>
								</table>
							</div>
							<br>
							<div id="pagination_riwayat_penjualan"></div>
						</div>
					</div>
				</div>

			</div>

      <!-- Modal Detail Penjualan -->
			<a href="#" style="display:none;" class="btn btn-md btn-icon btn-danger" id="klik_detail_transaksi" data-toggle="modal" data-target="#modal-detail-transaksi"><i class="icon-trash position-left"></i> Detail</a>
			<div id="modal-detail-transaksi" class="modal fade">
				<div class="modal-dialog modal-lg">
					<div class="modal-content">
						<div class="modal-header bg-warning">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
							<h5 class="modal-title">Detail Transaksi</h5>
						</div>

						<div class="modal-body">
              <div class="table-responsive">
								<table class="table table-bordered table-striped table-hover" id="table-detail-penjualan">
									<thead>
										<tr class="bg-warning">
											<th class="text-center">Kode Barang</th>
											<th class="text-center">Nama Barang</th>
											<th class="text-center">Jumlah Beli</th>
											<th class="text-center">Harga Jual</th>
                      <th class="text-center">Total Harga</th>
										</tr>
									</thead>
									<tbody id="tbody-detail-penjualan">
									</tbody>
                  <tfoot>
                    <tr>
                      <th colspan="4" class="text-center">Total</th>
                      <th id="html_total_transaksi" class="text-center"></th>
                    </tr>
                  </tfoot>
								</table>
							</div>
						</div>
						<div class="modal-footer">
							<button class="btn btn-link" data-dismiss="modal"><i class="icon-cross position-left"></i> Keluar</button>
						</div>
					</div>
				</div>
			 </div>
			<!-- /Modal Hapus Penjualan -->

			<!-- Modal Hapus Penjualan -->
			<a href="#" style="display:none;" class="btn btn-md btn-icon btn-danger" id="klik_hapus_transaksi" data-toggle="modal" data-target="#modal-hapus-transaksi"><i class="icon-trash position-left"></i> Hapus</a>
			<div id="modal-hapus-transaksi" class="modal fade">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header bg-danger">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
							<h5 class="modal-title">Hapus Pembayaran</h5>
						</div>
						<form action="<?= base_url('farmasi/riwayat_penjualan/hapus_transaksi') ?>" method="POST">
						<div class="modal-body">
							<input type="hidden" name="id_transaksi" id="id_transaksi">
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
			<!-- /Modal Hapus Penjualan -->

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
			url : '<?= base_url('farmasi/riwayat_penjualan/riwayat_penjualan_ajax') ?>',
			method : 'POST',
			dataType : 'json',
			success : (res) => {
				let row = '';
				if(res.status) {
					let i = 0;
					for(const item of res.data) {

						row += `
							<tr>
								<td class="text-center">${item.no_transaksi}</td>
								<td class="text-center">${item.nama_pelanggan}</td>
								<td class="text-center">${item.nama_kasir}</td>
								<td class="text-center"><b>Rp. </b>${NumberToMoney(item.nilai_transaksi)}</td>
								<td class="text-center"><b>Rp. </b>${NumberToMoney(item.dibayar)}</td>
								<td class="text-center"><b>Rp. </b>${NumberToMoney(item.kembali)}</td>
								<td class="text-center">${item.tanggal}<br>${item.waktu}</td>
								<td class="text-center">
                  <button type="button" class="btn btn-warning btn-xs" onclick="popup_detail_transaksi(${item.id});"><i class="icon-eye position-left"></i> Detail</button>
									<button type="button" class="btn btn-primary btn-xs" onclick="window.open('<?php echo base_url(); ?>farmasi/kasir/cetak_struk/${item.id}', '_blank','location=yes,height=570,width=520,scrollbars=yes,status=yes');"><i class="icon-printer2 position-left"></i> Print </button>
									<button type="button" class="btn btn-danger btn-xs" onclick="popup_hapus_transaksi(${item.id});"><i class="icon-trash position-left"></i> Hapus</button>
								</td>
							</tr>
						`;
					}

				} else {
					row += `<tr>
						<td colspan="8" class="text-center">${res.message}</td>
					</tr>`;
				}
				$(`#tbody-riwayat-penjualan`).html(row);
				paging_riwayat_penjualan();
			}
		});
	});

	function popup_hapus_transaksi(id){
		$('#klik_hapus_transaksi').click();
		$('#id_transaksi').val(id);
	}

  function popup_detail_transaksi(id){
    $('#klik_detail_transaksi').click();

    $.ajax({
			url : '<?= base_url('farmasi/riwayat_penjualan/detail_transaksi') ?>',
      data : {id:id},
			method : 'POST',
			dataType : 'json',
			success : (res) => {
				let row = '';
        let total = 0;
				if(res.status) {
					let i = 0;
					for(const item of res.data) {

						row += `
							<tr>
								<td class="text-center">${item.kode_barang}</td>
								<td class="text-center">${item.nama_barang}</td>
								<td class="text-center">${NumberToMoney(item.jumlah_beli)}</td>
								<td class="text-center"><b>Rp. </b>${NumberToMoney(item.harga_jual)}</td>
								<td class="text-center"><b>Rp. </b>${NumberToMoney(item.subtotal)}</td>
							</tr>
						`;

            total += parseFloat(item.subtotal);
					}

				} else {
					row += `<tr>
						<td colspan="5" class="text-center">${res.message}</td>
					</tr>`
				}
        $('#html_total_transaksi').html('Rp. '+NumberToMoney(total));
				$(`#tbody-detail-penjualan`).html(row);
			}
		});
  }

	function paging_riwayat_penjualan($selector){
		var jumlah_tampil = '10';

	    if(typeof $selector == 'undefined'){
	        $selector = $("#table-riwayat-penjualan tbody tr");
	    }
			window.tp = new Pagination('#pagination_riwayat_penjualan', {
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

	function cari_riwayat_penjualan(e) {
		let value_tgl_dari = $(`#input-tgl-dari`).val();
		let value_tgl_sampai = $(`#input-tgl-sampai`).val();

		$.ajax({
			url : '<?= base_url('farmasi/riwayat_penjualan/riwayat_penjualan_ajax') ?>',
			data : {'tgl_dari' : `${value_tgl_dari}`, 'tgl_sampai' : `${value_tgl_sampai}`},
			method : 'POST',
			dataType : 'json',
			success : (res) => {
								let row = '';
				if(res.status) {
					let i = 0;
					for(const item of res.data) {

            row += `
							<tr>
								<td class="text-center">${item.no_transaksi}</td>
								<td class="text-center">${item.nama_pelanggan}</td>
								<td class="text-center">${item.nama_kasir}</td>
								<td class="text-center"><b>Rp. </b>${NumberToMoney(item.nilai_transaksi)}</td>
								<td class="text-center"><b>Rp. </b>${NumberToMoney(item.dibayar)}</td>
								<td class="text-center"><b>Rp. </b>${NumberToMoney(item.kembali)}</td>
								<td class="text-center">${item.tanggal}<br>${item.waktu}</td>
								<td class="text-center">
                  <button type="button" class="btn btn-warning btn-xs" onclick="popup_detail_transaksi(${item.id});"><i class="icon-eye position-left"></i> Detail</button>
									<button type="button" class="btn btn-primary btn-xs" onclick="window.open('<?php echo base_url(); ?>farmasi/kasir/cetak_struk/${item.id}', '_blank','location=yes,height=570,width=520,scrollbars=yes,status=yes');"><i class="icon-printer2 position-left"></i> Print </button>
									<button type="button" class="btn btn-danger btn-xs" onclick="popup_hapus_transaksi(${item.id});"><i class="icon-trash position-left"></i> Hapus</button>
								</td>
							</tr>
						`;
					}

				} else {
					row += `<tr>
						<td colspan="8" class="text-center">${res.message}</td>
					</tr>`
				}
				$(`#tbody-riwayat-penjualan`).html(row);
				paging_riwayat_penjualan();
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
