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
				<div class="col-sm-6">
					<div class="form-group">
						<div class="input-group">
							<input type="text" class="form-control" name="nama_barang" id="input-nama-barang" onkeyup="get_barang()" placeholder="Cari Nama Barang">
							<span class="input-group-btn">
								<button class="btn btn-primary btn-md btn-icon" type="button" onclick="get_barang()"><i class="fa fa-search position-left"></i> Cari</button>
							</span>
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
						<table class="table table-bordered table-striped" id="table-stok-barang">
							<thead>
							<tr class="bg-success">
									<th class="text-center">Kode</th>
									<th class="text-center">Nama</th>
									<th class="text-center">Harga Awal</th>
									<th class="text-center">Harga Jual</th>
									<?php if($this->session->userdata('id_level') == 1 || $this->session->userdata('id_level') == 11): ?>
									<th class="text-center">Laba</th>
									<?php endif; ?>
									<th class="text-center">Stok</th>
									<th class="text-center">Tanggal Kadaluarsa</th>
									<!-- <th class="text-center">Supplier</th> -->
									<?php if($this->session->userdata('level') != 'Spv'): ?>
										<th class="text-center">Action</th>
									<?php endif; ?>

								</tr>
							</thead>
							<tbody>
								<tr>
									<td colspan="8" id="td-loading">
										<div class="loader">
											<img src="<?= base_url('assets/images/svg-loader/svg-loaders/vectorpaint.svg') ?>" alt="">
										</div>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
					<br>
					<ul class="pagination"></ul>
				</div>
			</div>
		</div>
	</div>

<script>
$(document).ready((e) => {

})

	$(window).load(function() {
		get_barang();
	})

	function get_barang() {
		let input_barang = $(`#input-nama-barang`).val();

		$.ajax({
			url : '<?= base_url('apotek/stok_barang/get_stok_barang') ?>',
			method : 'POST',
			data : {'search' : `${input_barang}`},
			dataType : 'json',
			beforeSend : () => {$(`#td-loading`).show()},
			success : (res) => {
				let row = '';
				if(res.status) {
					for(const item of res.data) {
						let laba = '';
						<?php if($this->session->userdata('id_level') == 1 || $this->session->userdata('id_level') == 11): ?>
							laba = `<td class="text-right"><b>Rp. </b>${NumberToMoney(item.laba)}</td>`
						<?php endif; ?>

						let button_owner = ``;
						<?php if($this->session->userdata('id_level') == 1 || $this->session->userdata('id_level') == 4 || $this->session->userdata('id_level') == 11 ) : ?>
							button_owner = `
								<button type="button" class="btn btn-xs btn-icon bg-primary" onclick="show_modal_ubah_harga(this, ${item.id})"><i class="icon-pencil position-left"></i> Harga</button>
								<a href="#" class="btn btn-xs btn-icon btn-danger" data-toggle="modal" data-target="#modal_hapus_stok_barang_${item.id}"><i class="icon-trash position-left"></i> Hapus Stok </a>
								<a href="#" class="btn btn-xs btn-icon btn-success" data-toggle="modal" data-target="#modal_hapus_nama_barang_${item.id}"><i class="icon-trash position-left"></i> Hapus Barang</a>
								
							`;
						<?php else : ?>
							button_owner = ''
						<?php endif; ?>
						
						let button_kadaluarsa = '';
						<?php if($this->session->userdata('level') != 'Spv'): ?>
							button_kadaluarsa = `
							<td>
									<div class="text-center">
								<a href="<?= base_url('apotek/stok_barang/view_ubah_tanggal_kadaluarsa?id_barang=') ?>${item.id}" class="btn btn-xs btn-icon btn-warning btn-text-small"><i class="icon-calendar position-left"></i> Kadaluarsa</a>
										${button_owner}
									</div>

									<!-- Modal Ubah Harga -->
									<div id="modal_ubah_harga_stok_barang_${item.id}" class="modal fade">
										<div class="modal-dialog">
											<div class="modal-content">
												<div class="modal-header bg-primary">
													<button type="button" class="close" data-dismiss="modal">&times;</button>
													<h5 class="modal-title">Ubah Harga</h5>
												</div>

												<form action="<?= base_url('apotek/stok_barang/ubah_harga') ?>" id="form-ubah-harga-${item.id}" method="POST">
												<div class="modal-body">

													<input type="text" name="id_barang" hidden="" value="${item.id}">
													<div class="form-group">
														<label class="control-label"><b>Harga Awal</b></label>
														<div class="input-group">
															<span class="input-group-btn">
																<button class="btn btn-primary btn-icon" type="button">Rp.</button>
															</span>
															<input type="text" class="form-control" placeholder="Text input" name="harga_awal" value="${item.harga_awal}" id="harga-awal-ubah-harga-${item.id}" onkeyup="hitung_laba_by_harga_awal(this, ${item.id})">
														</div>
													</div>

													<div class="form-group">
														<label class="control-label"><b>Harga Jual</b></label>
														<div class="input-group">
															<span class="input-group-btn">
																<button class="btn btn-primary btn-icon" type="button">Rp.</button>
															</span>
															<input type="tel" class="form-control" placeholder="Text input" name="harga_jual" value="${item.harga_jual}" id="harga-jual-ubah-harga-${item.id}" onkeyup="hitung_laba_by_harga_jual(this, ${item.id})">
														</div>

													</div>

													<div class="form-group">
														<label class="control-label"><b>Laba</b></label>
														<div class="input-group">
															<span class="input-group-btn">
																<button class="btn btn-primary btn-icon" type="button">Rp.</button>
															</span>
															<input type="text" class="form-control" placeholder="Text input" name="laba" value="${item.laba}" id="laba-ubah-harga-${item.id}" readonly="">
														</div>
													</div>

												</div>

												<div class="modal-footer">
													<button class="btn btn-link" data-dismiss="modal"><i class="icon-cross position-left"></i> Keluar</button>
													<button class="btn btn-primary" type="button" onclick="on_submit_ubah_harga(this, ${item.id})"><i class="icon-pencil position-left"></i> Ubah</button>
												</div>
												</form>
											</div>
										</div>
									</div>
									<!-- End Modal Ubah Harga -->

									<!-- Modal Hapus Stok -->
									<div id="modal_hapus_stok_barang_${item.id}" class="modal fade">
										<div class="modal-dialog">
											<div class="modal-content">
												<div class="modal-header bg-danger">
													<button type="button" class="close" data-dismiss="modal">&times;</button>
													<h5 class="modal-title">Hapus Stok Barang</h5>
												</div>

												<form action="<?= base_url('apotek/stok_barang/hapus_stok_barang') ?>" id="form-hapus-stok-barang-${item.id}" method="POST">
												<div class="modal-body">
													<div class="alert alert-danger no-border">
														<p>Apakah Anda Ingin <span class="text-semibold">Menghapus</span> Stok Barang ${item.nama_barang} Ini?</p>
												    </div>

													<input type="text" name="id_barang" value="${item.id}" hidden>

												</div>

												<div class="modal-footer">
													<button class="btn btn-link" data-dismiss="modal"><i class="icon-cross position-left"></i> Keluar</button>
													<button class="btn btn-danger" type="submit"><i class="icon-bin position-left"></i> Hapus</button>
												</div>
												</form>
											</div>
										</div>
									</div>
									<!-- End Modal Hapus Stok -->

									<!-- Modal Hapus Barang -->
									<div id="modal_hapus_nama_barang_${item.id}" class="modal fade">
										<div class="modal-dialog">
											<div class="modal-content">
												<div class="modal-header bg-danger">
													<button type="button" class="close" data-dismiss="modal">&times;</button>
													<h5 class="modal-title">Hapus Nama Barang</h5>
												</div>

												<form action="<?= base_url('apotek/stok_barang/hapus_nama_barang') ?>" id="form-hapus-nama-barang-${item.id}" method="POST">
												<div class="modal-body">
													<div class="alert alert-danger no-border">
														<p>Apakah Anda Ingin <span class="text-semibold">Menghapus</span> Nama Barang ${item.nama_barang} Ini?</p>
												    </div>

													<input type="text" name="id_barang" value="${item.id}" hidden>

												</div>

												<div class="modal-footer">
													<button class="btn btn-link" data-dismiss="modal"><i class="icon-cross position-left"></i> Keluar</button>
													<button class="btn btn-danger" type="submit"><i class="icon-bin position-left"></i> Hapus</button>
												</div>
												</form>
											</div>
										</div>
									</div>
								</td>
							`
						<?php endif; ?>

						row += `
							<tr>
								<td class="text-center" width="70px">${item.kode_barang}</td>
								<td class="text-center">${item.nama_barang}</td>
								<td class="text-right"><b>Rp. </b>${NumberToMoney(item.harga_awal)}</td>
								<td class="text-right"><b>Rp. </b>${NumberToMoney(item.harga_jual)}</td>
f								${laba}
								<td class="text-center">${NumberToMoney(item.stok)}</td>
								
								<td class="text-center">${item.tanggal_kadaluarsa}</td>
								${button_kadaluarsa}

							</tr>
						`
					}
				} else {
					row += `
					<tr>
						<td colspan="8" class="text-center">${res.message}</td>
					</tr>
					`
				}

				// $('#popup_load').fadeOut();
				$(`#table-stok-barang tbody`).html(row);
				pagination()
			},
			complete : () => {$(`#td-loading`).fadeOut()}
		})
	}

	function pagination($selector){
		var jumlah_tampil = '10';

	    if(typeof $selector == 'undefined'){
	        $selector = $(".table tbody tr");
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

	let show_modal_ubah_harga = (e, id) => {
		$(`#modal_ubah_harga_stok_barang_${id}`).modal('toggle');
	}

	let hitung_laba_by_harga_awal = (e, id) => {
		let input_harga_awal = $(`#${e.id}`)
		let input_harga_jual = $(`#harga-jual-ubah-harga-${id}`)

		if(e.value == "") {
			$(`#${e.id}`).val(0)
			e.setSelectionRange(0,0)
		}

		if(input_harga_jual.val() !== 0 || input_harga_jual.val() !== "") {
			let value_harga_awal = parseInt(FormatCurrency(input_harga_awal.val()));
			let value_harga_jual = parseInt(FormatCurrency(input_harga_jual.val()));

			let laba = value_harga_jual - value_harga_awal;

			$(`#laba-ubah-harga-${id}`).val(NumberToMoney(laba));
		}
	}

	let hitung_laba_by_harga_jual = (e, id) => {
		let input_harga_jual = $(`#${e.id}`)
		let input_harga_awal = $(`#harga-awal-ubah-harga-${id}`)

		if(e.value == "") {
			input_harga_jual.val(0)
			e.setSelectionRange(0,0)
		}

		let value_harga_awal = parseInt(FormatCurrency(input_harga_awal.val()));
		let value_harga_jual = parseInt(FormatCurrency(input_harga_jual.val()));

		let laba = value_harga_jual - value_harga_awal;

		$(`#laba-ubah-harga-${id}`).val(NumberToMoney(laba));
	}

	let on_submit_ubah_harga = (e, id) => {
		$(`.rupiah`).unmask();
		$(`#form-ubah-harga-${id}`).submit();
	}

	let set_tanggal_kadaluarsa = (e) => {
		let form_tgl_kadaluarsa = $(`#form-tgl-kadaluarsa-ubah-tanggal-kadaluarsa`)
		let input_tgl_kadaluarsa = $(`#input-tgl-kadaluarsa-ubah-tanggal-kadaluarsa`)
		let value_of_tgl_kadaluarsa = input_tgl_kadaluarsa.val();
		let value_default_tgl_kadaluarsa = $(`#default-tgl-kadaluarsa-ubah-tanggal-kadaluarsa`).val()

		if(e.id == "radio-tgl-kadalursa-ada") {
			input_tgl_kadaluarsa.val(value_default_tgl_kadaluarsa)
			form_tgl_kadaluarsa.show()
		} else {
			input_tgl_kadaluarsa.val('');
			form_tgl_kadaluarsa.hide();
		}

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
