<!DOCTYPE html>
<html lang="en">
<head>
<?php $this->load->view('admin/css'); ?>
<script src="<?php echo base_url('assets/js/plugins/tables/datatables/datatables.min.js'); ?>"></script>
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
				<?php if ($this->session->flashdata('status')): ?>
					<div class="alert alert-<?= $this->session->flashdata('status'); ?> no-border">
						<button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>
						<p class="message-text"><?= $this->session->flashdata('message'); ?></p>
				    </div>
				<?php endif ?>
			<!-- message -->

			<div class="row">
				<div class="col-sm-5">

					<div class="panel panel-flat border-top-success border-top-lg">
						<div class="panel-heading">
							<h6 class="panel-title">Barang</h6>
						</div>

						<div class="panel-body">
							<div class="search-barang">
								<div class="search-box">

									<div class="form-group">
										<div class="input-group">
											<span class="input-group-btn">
												<button class="btn btn-primary btn-icon" type="button" data-toggle="modal" data-target="#modal_tampil_barang_1"><i class=" icon-search4"></i></button>
											</span>
											<input type="text" class="form-control" placeholder="Cari Berdasarkan Nama Barang" id="cari_barang" onkeypress="enter_cari_barang(event);">
										</div>
									</div>

								</div>
								<div class="search-box-barang" id="box-barang">
									<div class="loader">
										<img src="<?= base_url('assets/images/svg-loader/svg-loaders/vectorpaint.svg') ?>" alt="">
									</div>
								</div>
							</div>
						</div>
					</div>

				</div>
				<div class="col-sm-7">

					<div class="panel panel-flat border-top-success border-top-lg">
						<div class="panel-heading">
							<h6 class="panel-title">Keranjang</h6>
						</div>

						<div class="panel-body">
							<input type="text" id="input-index" value="0" hidden="">
							<form method="POST" id="form-checkout">
								<div class="table-responsive">
									<table class="table table-striped table-hover">

										<thead class="bg-warning">
											<tr>
												<th>Qty</th>
												<th>Nama Barang</th>
												<th>Kode Barang</th>
												<th>Harga</th>
												<th>SubTotal</th>
												<th>Aksi</th>
											</tr>
										</thead>
										<tbody id="tbody-cart">

										</tbody>
										<tfoot>
											<tr>
												<td colspan="3" class="text-right text-semibold"><h2>Total (Rp.)</h2></td>
												<td colspan="3"><h2 class="text-center text-semibold" id="nilai-transaksi">: -</h2></td>
											</tr>
											<tr>
												<td colspan="6">
													<div class="text-right">
														<button class="btn btn-md btn-primary" type="button" id="btn-checkout" disabled="" data-toggle="modal" data-target="#modal_checkout"><i class="icon-cash2 position-left"></i> Bayar</button>
													</div>
													<!-- Modal Checkout -->
													<div id="modal_checkout" class="modal fade">
														<div class="modal-dialog">
															<div class="modal-content">
																<div class="modal-header bg-primary">
																	<button type="button" class="close" data-dismiss="modal">&times;</button>
																	<h5 class="modal-title">Bayar</h5>
																</div>

																<div class="modal-body">

																	<div class="form-group">
																		<label class="control-label"><b>Nama Pelanggan</b></label>
																		<input type="text" class="form-control" value="-" name="nama_pelanggan">
																	</div>

																	<div class="form-group">
																		<label class="control-label"><b>Nilai Transaksi</b></label>
																		<div class="input-group">
																			<span class="input-group-btn">
																				<button class="btn btn-primary btn-icon" type="button"><i class="icon-cash2"></i></button>
																			</span>
																			<input type="text" id="input-nilai-transaksi" value="0" name="nilai_transaksi" class="form-control rupiah" readonly>
																			<!-- total laba -->
																			<input type="text" id="input-total-laba" name="total_laba" hidden="" class="input-total-laba">
																			<!-- total laba -->
																		</div>
																	</div>

																	<div class="form-group">
																		<label class="control-label"><b>Dibayar</b></label>
																		<div class="input-group">
																			<span class="input-group-btn">
																				<button class="btn btn-primary btn-icon" type="button"><i class="icon-cash2"></i></button>
																			</span>
																			<input type="text" class="form-control rupiah" name="dibayar" id="input-dibayar" onkeyup="hitung_kembalian(this)">
																		</div>
																	</div>

																	<div class="form-group">
																		<label class="control-label"><b>Kembali</b></label>
																		<div class="input-group">
																			<span class="input-group-btn">
																				<button class="btn btn-primary btn-icon" type="button"><i class="icon-cash2"></i></button>
																			</span>
																			<input type="text" class="form-control rupiah" name="kembali" readonly="" id="input-kembali">
																		</div>
																	</div>

																</div>

																<div class="modal-footer">
																	<button class="btn btn-link" id="btn_batal_bayar" data-dismiss="modal"><i class="icon-cross"></i> Batal</button>
																	<button class="btn btn-success" type="button" id="btn_proses_bayar" disabled><i class="icon-floppy-disk position-left"></i> Proses</button>
																</div>
															</div>
														</div>
													</div>
													<!-- /Modal Checkout -->
												</td>
											</tr>
										</tfoot>
									</table>
								</form>
							</div>
						</div>
					</div>

				</div>
			</div>

<script>

	$(window).load((e) => {
		$('#cari_barang').focus();

		data_barang();
		$('#btn_proses_bayar').click(function(){
			 $('#btn_batal_bayar').click();
			 $(`.rupiah`).unmask();

			 $.ajax({
					 url : '<?php echo base_url(); ?>farmasi/kasir/proses_transaksi',
					 data : $('#form-checkout').serialize(),
					 type : "POST",
					 dataType : "json",
					 success : function(row){
							 var id_apotek_penjualan = row['id_farmasi_penjualan'];

							 if (row['status'] == true) {
  							 var win = window.open('<?php echo base_url(); ?>farmasi/kasir/cetak_struk/'+id_apotek_penjualan, '_blank','location=yes,height=570,width=520,scrollbars=yes,status=yes');
								 window.location.href = '<?php echo base_url(); ?>farmasi/kasir';
							 }else {
							 	 window.location.href = '<?php echo base_url(); ?>farmasi/kasir';
							 }
					 }
			 });
	 });

	});

	function data_barang(){
		var search = $('#cari_barang').val();

		$.ajax({
			url : '<?= base_url('farmasi/kasir/get_barang_stok') ?>',
			data : {search:search},
			method : 'POST',
			dataType : 'json',
			beforeSend : () => {$(`.loader`).show()},
			success : (res) => {
				if(res.status == false) {
					$(`#box-barang`).html(`
						<div class="item-barang">
							<h5 class="error-barang">${res.message}</h5>
						</div>`)
				} else {
					let itemHTML = '';
					for(const item of res.data) {

						if (item.stok < 0 || item.stok == 0) {
							var span_stok = `<span class="label label-danger">${item.stok}</span>`;
							var cursor = ``;
						}else {
							var span_stok = `<span class="label label-success">${item.stok}</span>`;
							var cursor = `style="cursor:pointer;" onclick="add_row('${item.id}', '${item.nama_barang}', '${item.kode_barang}', '${item.harga_jual}', '${item.laba}')"`;
						}

						itemHTML += `
						<div class="item-barang" ${cursor}>
							<span class="label-harga"><span class="label bg-warning">Rp. ${convertRupiah(parseInt(item.harga_jual))}</span></span>
							<div class="detail-barang">
								<div class="nama-barang">
									<h5 class="title">${item.kode_barang} - ${item.nama_barang}</h5>
									<span>Stok : ${span_stok}</span>
								</div>
								<div class="action-barang">
									<button type="button" class="btn bg-warning-600 btn-icon"><i class="icon-plus3"></i></button>
								</div>
							</div>
						</div>`;
					}
					$(`#box-barang`).html(itemHTML);
				}
			},
			complete : () => {$(`.loader`).hide()}
		});

		$('#cari_barang').off('keyup').keyup(function(){
			data_barang();
		});
	}

	let enter_cari_barang = (e) => {
		if(e.keyCode === 13){
				e.preventDefault();

				var search = $('#cari_barang').val();

				$.ajax({
					url : '<?= base_url('farmasi/kasir/search_barang_enter') ?>',
					method : 'POST',
					data : {search : search},
					dataType : 'json',
					beforeSend : () => {$(`.loader`).show()},
					success : (row) => {
						let jumlah_data = $(`.row-${row.id}`).length;
						console.log(jumlah_data, row.id);
						if (row.stok < 0 || row.stok == 0) {

						}else {
							if(jumlah_data > 0) {
								var jumlah_sekarang = $('#qty-'+row.id).val();
								var hitung = parseFloat(jumlah_sekarang) + 1;
								$('#qty-'+row.id).val(hitung);

								hitung_total_harga_beli(row.id, row.harga_jual, row.laba);
							} else {
								let index = parseInt($(`#input-index`).val());
								let increase_index = index + 1;

								let data = `
									<tr id="row-${row.id}" class="row-${row.id}">
										<td>
											<input type="hidden" value="${row.id}" name="id_barang[]" id="input-id-barang-${row.id}" class="input-id-barang-${row.id}"/>
											<input type="hidden" value="${parseInt(row.laba)}" name="laba[]" id="input-laba-${row.id}" class="input-laba input-laba-${row.id}" />
											<input type="text" size="5" class="form-control qty-${row.id}" id="qty-${row.id}" name="qty[]" onkeyup="hitung_total_harga_beli(${row.id}, '${row.harga_jual}', '${row.laba}');" value="1">
										</td>
										<td><span id="nama-barang-${row.id}" class="nama-barang-${row.id}">${row.nama_barang}</span></td>
										<td><span id="kode-barang-${row.id}" class="kode-barang-${row.id}">${row.kode_barang}</span></td>
										<td><span class="harga-${row.id}" id="harga-${row.id}">Rp. ${convertRupiah(parseInt(row.harga_jual))}</span></td>'
										<td>
											<span id="text-subtotal-${row.id}" class="text-subtotal-${row.id}">Rp. ${convertRupiah(parseInt(row.harga_jual))}</span>
										</td>
										<td>
											<button type="button" class="btn btn-danger btn-xs btn-icon" onclick="delete_row(${row.id}, '${row.harga_jual}', '${row.laba}')"><i class="icon-bin"></i></button>
											<input type="text" hidden="" id="total-harga-beli-${row.id}" value="${parseInt(row.harga_jual)}" class="total-harga-beli" name="total_harga_beli[]" />
										</td>
									</tr>
								`;

							$(`#tbody-cart`).append(data);
							hitung_total_harga_beli(increase_index, row.harga_jual, row.laba);
							$(`#input-index`).val(increase_index);
							}

							$('#cari_barang').val('');
							data_barang();
						}
					},
					complete : () => {$(`.loader`).hide()}
				});
			}
	}

	let add_row = (id, nama_barang, kode_barang, harga, laba) => {
		let jumlah_data = $(`.row-${id}`).length;

		if(jumlah_data > 0) {
			var jumlah_sekarang = $('#qty-'+id).val();
			var hitung = parseFloat(jumlah_sekarang) + 1;
			$('#qty-'+id).val(hitung);

			hitung_total_harga_beli(id, harga, laba);
		} else {
			let index = parseInt($(`#input-index`).val());
			let increase_index = index + 1;

			let row = `
				<tr id="row-${id}" class="row-${id}">
					<td>
						<input type="hidden" value="${id}" name="id_barang[]" id="input-id-barang-${id}" class="input-id-barang-${id}"/>
						<input type="hidden" value="${parseInt(laba)}" name="laba[]" id="input-laba-${id}" class="input-laba input-laba-${id}" />
						<input type="text" size="5" class="form-control qty-${id}" id="qty-${id}" name="qty[]" onkeyup="hitung_total_harga_beli(${id}, '${harga}', '${laba}');" value="1">
					</td>
					<td><span id="nama-barang-${id}" class="nama-barang-${id}">${nama_barang}</span></td>
					<td><span id="kode-barang-${id}" class="kode-barang-${id}">${kode_barang}</span></td>
					<td><span class="harga-${id}" id="harga-${id}">Rp. ${convertRupiah(parseInt(harga))}</span></td>'
					<td>
						<span id="text-subtotal-${id}" class="text-subtotal-${id}">Rp. ${convertRupiah(parseInt(harga))}</span>
					</td>
					<td>
						<button type="button" class="btn btn-danger btn-xs btn-icon" onclick="delete_row(${id}, '${harga}', '${laba}')"><i class="icon-bin"></i></button>
						<input type="text" hidden="" id="total-harga-beli-${id}" value="${parseInt(harga)}" class="total-harga-beli" name="total_harga_beli[]" />
					</td>
				</tr>
			`;

		$(`#tbody-cart`).append(row);
		hitung_total_harga_beli(increase_index, harga, laba);
		$(`#input-index`).val(increase_index);
		}
	}

	let delete_row = (index, harga, laba) => {
		let decrease_index = parseInt(index) - 1;

		$(`.rupiah`).unmask();

		let value_nilai_transaksi = parseInt($(`#input-nilai-transaksi`).val());
		let value_qty = parseInt(($(`#qty-${index}`).val() == "") ? 0 : $(`#qty-${index}`).val());
		let total_harga_beli = parseInt(harga) * value_qty
		let kurang_nilai_transaksi = value_nilai_transaksi - total_harga_beli
		$(`#input-nilai-transaksi`).val(convertRupiah(parseInt(kurang_nilai_transaksi)));
		$(`#nilai-transaksi`).text(convertRupiah(parseInt(kurang_nilai_transaksi)));

		$(`#row-${index}`).remove();
		$(`#input-index`).val(decrease_index);

		$(`.rupiah`).mask('000.000.000', {reverse: true});

		if($(`#input-nilai-transaksi`).val() == 0) {
			$('#btn-checkout').prop("disabled", true);
		}
	}

	let hitung_total_harga_beli = (index, harga, laba) => {
		let value_qty = parseInt($(`#qty-${index}`).val())

		let hargaConvert = parseInt(harga);

		let total_harga_beli = value_qty * hargaConvert;
		$(`#total-harga-beli-${index}`).val(total_harga_beli);
		$(`#text-subtotal-${index}`).text(`Rp. ${convertRupiah(parseInt(total_harga_beli))}`)

		let total_laba_per_barang = value_qty * parseInt(laba);
		$(`#input-laba-${index}`).val(total_laba_per_barang);

		let nilai_transaksi = [];
		$('.total-harga-beli').each(function(i, obj) {
		    nilai_transaksi.push(parseInt(obj.value));
		});

		total_nilai_transaksi = nilai_transaksi.reduce((total, num) => total + num);

		$(`#nilai-transaksi`).text(': ' + convertRupiah(parseInt(total_nilai_transaksi)));
		$(`#input-nilai-transaksi`).val(convertRupiah(parseInt(total_nilai_transaksi)));

		let total_laba = [];
		$(`.input-laba`).each((i, obj) => {
			total_laba.push(parseInt((obj.value == "") ? 0 : obj.value));
		})

		let total_all_laba = total_laba.reduce((total, num) => total + num);
		$(`#input-total-laba`).val(total_all_laba);

		if($(`#input-nilai-transaksi`).val() != 0) {
			$(`#btn-checkout`).prop('disabled', false);
		} else {
			$(`#btn-checkout`).prop('disabled', true);
		}

	}

	let hitung_kembalian = (e) => {
		$(`.rupiah`).unmask();

		let value_nilai_transaksi = parseInt($(`#input-nilai-transaksi`).val());
		let value_nilai_dibayar = parseInt($(`#${e.id}`).val());

		let kembalian = value_nilai_dibayar - value_nilai_transaksi

		if (parseFloat(value_nilai_dibayar) < parseFloat(value_nilai_transaksi)) {
			$('#btn_proses_bayar').attr('disabled','disabled');
		}else {
			$('#btn_proses_bayar').removeAttr('disabled');
		}

		$(`.rupiah`).mask('000.000.000', {reverse: true});
		$(`#input-kembali`).val(convertRupiah(parseInt(kembalian)));
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
