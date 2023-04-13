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
							<button type="button" class="btn btn-md btn-danger" style="margin-bottom: 2em;" onclick="tampil_modal_hapus_semua()"><i class="icon-trash position-left"></i> Hapus Semua</button>

							<!-- Modal Hapus Riwayat Penjualan farmasi -->
							<div id="modal_hapus_semua" class="modal fade">
								<div class="modal-dialog">
									<div class="modal-content">
										<div class="modal-header bg-danger">
											<button type="button" class="close" data-dismiss="modal">&times;</button>
											<h5 class="modal-title">Hapus Semua Riwayat Penjualan</h5>
										</div>
										<form action="<?= base_url('farmasi/riwayat_hapus_penjualan/hapus_semua_riwayat_penjualan') ?>" method="POST">
											<div class="modal-body">
												<div class="alert alert-danger no-border">
													<p>Apakah Anda Ingin <span class="text-semibold">Menghapus</span> Semua Riwayat Penjualan Ini ?</p>
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
							<div class="table-responsive">
								<table class="table table-bordered table-striped table-hover" id="table-hapus-riwayat-penjualan">
									<thead>
										<tr class="bg-success">
											<th class="text-center">Invoice</th>
											<th class="text-center">Nama Pasien</th>
											<th class="text-center">Nama Kasir</th>
											<th class="text-center">Total Invoice</th>
											<th class="text-center">User Yang Hapus</th>
											<th class="text-center">Alasan</th>
											<th class="text-center">Tanggal</th>
											<th class="text-center">Aksi</th>
										</tr>
									</thead>
									<tbody id="tbody-riwayat-hapus-penjualan">
									</tbody>
								</table>
							</div>
							<br>
							<div id="pagination_riwayat_penjualan"></div>
						</div>
					</div>
				</div>

			</div>

			<!-- Modal Hapus Pengeluaran -->
			<a href="#" style="display:none;" class="btn btn-md btn-icon btn-danger" id="klik_detail_penjualan" data-toggle="modal" data-target="#modal-detail-penjualan"><i class="icon-trash position-left"></i> Detail</a>
			<div id="modal-detail-penjualan" class="modal fade">
				<div class="modal-dialog  modal-lg">
					<div class="modal-content">
						<div class="modal-header bg-warning">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
							<h5 class="modal-title">Detail Pembayaran</h5>
						</div>
						<div class="modal-body">
              <div class="row">
                <div class="col-md-6">
                   <div class="form-group">
                     <label class="control-label"><b>Nama Pelanggan</b></label>
                     <input required id="nama_pelanggan" type="text" class="form-control" readonly>
                   </div>
                   <div class="form-group">
                     <label class="control-label"><b>Nama Kasir</b></label>
                     <input required id="nama_kasir" type="text" class="form-control" readonly>
                   </div>
                   <div class="form-group">
                     <label class="control-label"><b>Tanggal & Waktu Pembayaran</b></label>
                     <input required id="tanggal_waktu_penjualan" type="text" class="form-control" readonly>
                   </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="control-label"><b>User yang Hapus</b></label>
                    <input required id="nama_kasir_hapus" type="text" class="form-control" readonly>
                  </div>
                  <div class="form-group">
                    <label class="control-label"><b>Tanggal & Waktu Hapus Pembayaran</b></label>
                    <input required id="tanggal_waktu_hapus_penjualan" type="text" class="form-control" readonly>
                  </div>
                  <div class="form-group">
                    <label class="control-label"><b>Alasan</b></label>
                    <input required id="alasan" type="text" class="form-control" readonly>
                  </div>
                </div>
              </div>
              <div class="table-responsive">
                <table class="table table-bordered table-striped table_data_barang">
                  <thead>
                    <tr class="bg-warning">
											<th class="text-center">Kode Barang</th>
											<th class="text-center">Nama Barang</th>
											<th class="text-center">Jumlah Beli</th>
											<th class="text-center">Harga Jual</th>
                      <th class="text-center">Total Harga</th>
										</tr>
                  </thead>
                  <tbody>

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
			<!-- /Modal Hapus Pengeluaran -->

<script>
	function tampil_modal_hapus_semua() {
		$(`#modal_hapus_semua`).modal('toggle');
	}

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
			url : '<?= base_url('farmasi/riwayat_hapus_penjualan/riwayat_penjualan_ajax') ?>',
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
								<td class="text-center">${item.nama_kasir_hapus}</td>
								<td class="text-center">${item.alasan}</td>
								<td class="text-center">${item.tanggal}</td>
								<td class="text-center">
									<button type="button" class="btn btn-warning btn-xs" onclick="detail_penjualan(${item.id});"><i class="icon-eye position-left"></i> Detail</button>
								</td>
							</tr>
						`;
					}

				} else {
					row += `<tr>
						<td colspan="10" class="text-center">${res.message}</td>
					</tr>`;
				}
				$(`#tbody-riwayat-hapus-penjualan`).html(row);
				paging_riwayat_penjualan();
			}
		});
	});

	function detail_penjualan(id){
		$('#klik_detail_penjualan').click();
    $.ajax({
        url : '<?php echo base_url(); ?>farmasi/riwayat_hapus_penjualan/detail_penjualan',
        data : {id:id},
        type : "POST",
        dataType : "json",
        success : function(data){
					$('#nama_pelanggan').val(data['row'].nama_pelanggan);
					$('#nama_kasir').val(data['row'].nama_kasir);
					$('#tanggal_waktu_penjualan').val(data['row'].tanggal+' '+data['row'].waktu);
					$('#nama_kasir_hapus').val(data['row'].nama_kasir_hapus);
					$('#tanggal_waktu_hapus_penjualan').val(data['row'].tanggal_hapus+' '+data['row'].waktu_hapus);
					$('#alasan').val(data['row'].alasan);

          $tr = "";
          var total = 0;

          if(data['res'] == "" || data['res'] == null){
              $tr = "<tr><td colspan='5' style='text-align:center;'><b>Data tidak ditemukan</b></td></tr>";
          }else{
              var no = 0;
              for(var i=0; i<data['res'].length; i++){
                  no++;

                  $tr += '<tr>'+
                              '<td>'+data['res'][i].kode_barang+'</td>'+
                              '<td>'+data['res'][i].nama_barang+'</td>'+
                              '<td>'+NumberToMoney(data['res'][i].jumlah_beli)+'</td>'+
                              '<td class="text-center">Rp. '+NumberToMoney(data['res'][i].harga_jual)+'</td>'+
                              '<td class="text-center">Rp. '+NumberToMoney(data['res'][i].subtotal)+'</td>'+
                          '</tr>';
               total += parseFloat(data['res'][i].subtotal);
              }
          }
          $('#html_total_transaksi').html('Rp. '+NumberToMoney(total));
          $('.table_data_barang tbody').html($tr);
        }
    });
	}

	function paging_riwayat_penjualan($selector){
		var jumlah_tampil = '10';

	    if(typeof $selector == 'undefined'){
	        $selector = $("#table-hapus-riwayat-penjualan tbody tr");
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
			url : '<?= base_url('farmasi/riwayat_hapus_penjualan/riwayat_penjualan_ajax') ?>',
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
								<td class="text-center">${item.nama_kasir_hapus}</td>
								<td class="text-center">${item.alasan}</td>
								<td class="text-center">${item.tanggal}</td>
								<td class="text-center">
									<button type="button" class="btn btn-warning btn-xs" onclick="detail_penjualan(${item.id});"><i class="icon-eye position-left"></i> Detail</button>
								</td>
							</tr>
						`;
					}

				} else {
					row += `<tr>
						<td colspan="10" class="text-center">${res.message}</td>
					</tr>`;
				}
				$(`#tbody-riwayat-hapus-penjualan`).html(row);
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
