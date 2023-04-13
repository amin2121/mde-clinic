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
											<label class="control-label col-sm-3"><b>Tanggal Dari</b></label>
											<div class="col-sm-8">
												<input type="text" class="form-control datepicker" name="tgl_resep_dari" id="input-tgl-dari">
											</div>
										</div>
									</div>

									<div class="col-sm-6">
										<div class="form-group">
											<label class="control-label col-sm-3"><b>Tanggal Sampai</b></label>
											<div class="col-sm-8">
												<input type="text" class="form-control datepicker" name="tgl_resep_sampai" id="input-tgl-sampai">
											</div>
										</div>
									</div>

								</div>
								<div class="row">
									<div class="col-sm-6">
										<button class="btn btn-md btn-primary" onclick="cari_resep()"><i class="icon-search4 position-left"></i> Cari</button>
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
							<div id="pagination_resep"></div>
						</div>
					</div>

				</div>

			</div>

<script>
	$(window).load(() => {
		$.ajax({
			url : '<?= base_url('farmasi/kasir/get_resep_obat_api') ?>',
			method : 'GET',
			dataType : 'json',
			success : (res) => {
				if(res.status) {
					let row = '';
					let index = 1;
					for(const item of res.data) {
						row += `
							<tr>
								<td class="text-center">${index++}</td>
								<td class="text-center">${item.invoice}</td>
								<td class="text-center">${item.nama_pasien}</td>
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

					$(`#tbody-resep-obat`).append(row);
					paging_resep();
				}
			}
		})
	});

	function paging_resep($selector){
		var jumlah_tampil = '10';

	    if(typeof $selector == 'undefined'){
	        $selector = $(".table-resep tbody tr");
	    }
			window.tp = new Pagination('#pagination_resep', {
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


	let cari_resep = () => {
		let value_tgl_resep_dari = $(`#input-tgl-dari`).val();
		let value_tgl_resep_sampai = ($(`#input-tgl-sampai`).val());

		$.ajax({
			url : '<?= base_url('farmasi/kasir/get_resep_obat_api') ?>',
			method : 'POST',
			dataType : 'json',
			data : {'tgl_dari' : `${value_tgl_resep_dari}`, 'tgl_sampai' : `${value_tgl_resep_sampai}`},
			success : (res) => {
				let row = '';
				if(res.status) {
					let index = 1;
					for(const item of res.data) {
						row += `
							<tr>
								<td class="text-center">${index++}</td>
								<td class="text-center">${item.invoice}</td>
								<td class="text-center">${item.nama_pasien}</td>
								<td class="text-right"><b>Rp. </b>${convertRupiah(parseInt(item.total_harga_resep))}</td>
								<td class="text-center">${item.tanggal}</td>
								<td>
									<div class="text-center">
										<button class="btn btn-md btn-icon btn-info" onclick="get_obat(this, ${item.id})" id="btn-detail-obat-${item.id}"><i class="icon-info22"></i> Detail</button>
									</div>
									<!-- Large modal -->
									<div id="modal_tampil_obat_${item.id}" class="modal fade">
										<div class="modal-dialog modal-lg">
											<div class="modal-content">
												<div class="modal-header">
													<button type="button" class="close" data-dismiss="modal">&times;</button>
													<h5 class="modal-title"><i class="icon-task"></i>  Detail Resep</h5>
												</div>

												<div class="modal-body">
													<div class="table-responsive">
														<table class="table table-bordered table-striped table-hover">
															<thead>
																<tr class="bg-info">
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
													<button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
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
					row += `
						<tr>
							<td colspan="6" class="text-center">${res.message}</td>
						</tr>
					`
				}
				$(`#tbody-resep-obat`).html(row);
			}
		})
	}

	let get_obat = (e, id_resep_obat) => {
		$.ajax({
			url : '<?= base_url('farmasi/kasir/get_obat_api') ?>',
			method : 'GET',
			dataType : 'json',
			data : {'id_resep_obat' : id_resep_obat},
			success : (res) => {
				let index = 1;
				let row = '';
				console.log(res);

				for(const item of res.data) {
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

				$(`#tbody-obat-${id_resep_obat}`).html(row)
				$(`#modal_tampil_obat_${id_resep_obat}`).modal('show');
			},
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
