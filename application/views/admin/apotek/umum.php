<!DOCTYPE html>
<html lang="en">
<head>
<?php $this->load->view('admin/css'); ?>
<script src="<?php echo base_url('assets/js/plugins/tables/datatables/datatables.min.js'); ?>"></script>
<script type="text/javascript">
$(function() {
$('.diagnosa').DataTable();
});

function modal_pasien(){
	$('#btn_modal_pasien').click();
}

function get_pasien(){
	var search = $('#search_pasien').val();

	$.ajax({
			url : '<?php echo base_url(); ?>apotek/kasir/get_pasien',
			data : {search:search},
			type : "POST",
			dataType : "json",
			success : function(result){
					$tr = "";

					if(result == "" || result == null){
							$tr = "<tr><td colspan='4' style='text-align:center;'><b>Data tidak ditemukan</b></td></tr>";
					}else{
							var no = 0;
							for(var i=0; i<result.length; i++){
									no++;

									$tr += '<tr style="cursor:pointer;" onclick="klik_pasien('+result[i].id+');">'+
															'<td>'+result[i].no_rm+'</td>'+
															'<td>'+result[i].nama_pasien+'</td>'+
															'<td>'+result[i].jenis_kelamin+'</td>'+
															'<td>'+result[i].tanggal_lahir+'</td>'+
													'</tr>';
							}
					}

					$('.table_data_pasien tbody').html($tr);
					pagination_pasien();
			}
	});

	$('#search_pasien').off('keyup').keyup(function(){
			get_pasien();
	});
}

function pagination_pasien($selector){
	var jumlah_tampil = '10';

		if(typeof $selector == 'undefined'){
				$selector = $(".table_data_pasien tbody tr");
		}
		window.tp = new Pagination('#pagination_pasien', {
				itemsCount:$selector.length,
				pageSize : parseInt(jumlah_tampil),
				onPageSizeChange: function (ps) {
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

function klik_pasien(id){
	$('#tutup_data_pasien').click();

		$.ajax({
				url : '<?php echo base_url(); ?>apotek/kasir/klik_pasien',
				data : {id:id},
				type : "POST",
				dataType : "json",
				success : function(row){
					$('#id_pasien').val(id);
					$('#nama_pasien').val(row['nama_pasien']);
				}
		});
}
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
												<button class="btn btn-success btn-icon" type="button" data-toggle="modal" data-target="#modal_tampil_barang_1"><i class=" icon-search4"></i></button>
											</span>
											<input type="text" class="form-control" placeholder="Cari Barang Anda.." id="search-barang-kasir-umum" onkeypress="enter_cari_barang(event)">
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
							<form action="<?= base_url('apotek/kasir/transaksi_umum') ?>" method="POST" id="form-checkout">
								<div class="form-group">
									<div class="input-group">
										<input type="text" id="nama_pasien" name="nama_pasien" class="form-control" placeholder="Nama Pasien" readonly>
										<input type="hidden" id="id_pasien" name="id_pasien" class="form-control" readonly>
										<span class="input-group-btn">
											<button class="btn bg-primary" onclick="modal_pasien(); get_pasien();" type="button"><i class="fa fa-search position-left"></i> Cari</button>
										</span>
									</div>
								</div>
								<div class="table-responsive">
									<table class="table table-striped table-hover">

										<thead class="bg-primary">
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
												<td colspan="4" class="text-right text-semibold"><h2>Total (Rp.)</h2></td>
												<td colspan="2"><h2 class="text-center text-semibold" id="nilai-transaksi">: -</h2></td>
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
																		<label class="control-label" id="select-asal-poli"><b>Asal Poli</b></label>
																		<select name="asal_poli" id="select-asal-poli" class="bootstrap-select" data-width="100%">
																			<?php foreach ($poli as $p): ?>
																				<option value="<?= $p['poli_id'] ?>"><?= $p['poli_nama'] ?></option>
																			<?php endforeach ?>
																		</select>
																	</div>

																	<div class="form-group">
																		<label class="control-label"><b>Nilai Transaksi</b></label>
																		<div class="input-group">
																			<span class="input-group-btn">
																				<button class="btn btn-primary btn-icon" type="button"><i class="icon-cash2"></i></button>
																			</span>
																			<input type="text" id="input-nilai-transaksi" value="0" name="nilai_transaksi" class="form-control rupiah">
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
																	<button class="btn btn-link" data-dismiss="modal" id="btn_batal_bayar"><i class="icon-cross"></i> Batal</button>
																	<button class="btn btn-success" type="button" id="btn_proses_bayar"><i class="icon-floppy-disk position-left"></i> Proses</button>
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
			            <input type="text" id="search_pasien" placeholder="Cari Berdasarkan Nama Pasien dan No RM" class="form-control">
			            <span class="input-group-btn">
			              <button class="btn bg-primary" type="button"><i class="fa fa-search"></i></button>
			            </span>
			          </div>
			        </div>
			        <div class="table-responsive">
			          <table class="table table-bordered table-hover table-striped table_data_pasien">
			            <thead>
			              <tr class="bg-primary">
			                <th class="text-center">No RM</th>
			                <th class="text-center">Nama</th>
			                <th class="text-center">Jenis Kelamin</th>
			                <th class="text-center">Tanggal Lahir</th>
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

	$(window).load((e) => {
		$('#search-barang-kasir-umum').focus();

		if($(`#input-dibayar`).val() == "") {
			$(`#btn_proses_bayar`).prop('disabled', true)
		} else {
			$(`#btn_proses_bayar`).prop('disabled', false)
		}

		data_barang();
		$('#btn_proses_bayar').click(function(){
			$('#btn_batal_bayar').click();
			$(`.rupiah`).unmask();

			$.ajax({
					 url : '<?php echo base_url(); ?>apotek/kasir/transaksi_umum',
					 data : $('#form-checkout').serialize(),
					 type : "POST",
					 dataType : "json",
					 success : function(row){
						var id_penjualan = row['id_penjualan'];
						console.log(row['status']);
						if (row['status'] == true) {
							 var win = window.open('<?php echo base_url(); ?>apotek/kasir/cetak_struk/'+id_penjualan, '_blank','location=yes,height=570,width=520,scrollbars=yes,status=yes');
							 window.location.href = '<?php echo base_url(); ?>apotek/kasir/umum';
						}else {
						 	window.location.href = '<?php echo base_url(); ?>apotek/kasir/umum';
						}

					 }
			});
	 });

	});

	function data_barang(){
		var search = $('#search-barang-kasir-umum').val();

		$.ajax({
			url : '<?= base_url('apotek/kasir/get_barang_stok') ?>',
			data : {search:search},
			method : 'POST',
			dataType : 'json',
			beforeSend : () => {$(`.loader`).show()},
			success : (res) => {
				console.log(res);
				if(res.status == false) {
					$(`#box-barang`).html(`
						<div class="item-barang">
							<h5 class="error-barang">${res.message}</h5>
						</div>`)
				} else {
					let itemHTML = '';
					for(const item of res.data) {

						if (item.stok < 0) {
							var span_stok = `<span class="label label-danger">${item.stok}</span>`;
							var cursor = ``;
						}else {
							var span_stok = `<span class="label label-success">${item.stok}</span>`;
							var cursor = `style="cursor:pointer;" onclick="add_row('${item.id_barang}', '${item.nama_barang}', '${item.kode_barang}', '${item.harga_jual}', '${item.laba}', '${item.stok}', '${item.id_jenis_barang}')"`;
						}

						itemHTML += `
						<div class="item-barang" ${cursor}>
							<span class="label-harga"><span class="label bg-primary">Rp. ${convertRupiah(parseInt(item.harga_jual))}</span></span>
							<div class="detail-barang">
								<div class="nama-barang">
									<h5 class="title">${item.kode_barang} - ${item.nama_barang}</h5>
									<span>Stok : ${span_stok}</span>
								</div>
								<div class="action-barang">
									<button type="button" class="btn bg-primary-600 btn-icon btn-rounded"><i class="icon-plus3"></i></button>
								</div>
							</div>
						</div>`;
					}
					$(`#box-barang`).html(itemHTML);
				}
			},
			complete : () => {$(`.loader`).hide()}
		});

		$('#search-barang-kasir-umum').off('keyup').keyup(function(){
			data_barang();
		});
	}

	let enter_cari_barang = (e) => {
		if(e.keyCode === 13){
				e.preventDefault();

				var search = $('#search-barang-kasir-umum').val();

				$.ajax({
					url : '<?= base_url('apotek/kasir/search_barang_enter') ?>',
					method : 'POST',
					data : {search : search},
					dataType : 'json',
					beforeSend : () => {$(`.loader`).show()},
					success : (row) => {
						let jumlah_data = $(`.row-${row.id_barang}`).length;

						if (row.stok < 0) {

						}else {
							if(jumlah_data > 0) {
								var jumlah_sekarang = $('#qty-'+row.id_barang).val();
								var hitung = parseFloat(jumlah_sekarang) + 1;
								$('#qty-'+row.id_barang).val(hitung);

								hitung_total_harga_beli(row.id_barang, row.harga_jual, row.laba);
							} else {
								let index = parseInt($(`#input-index`).val());
								let increase_index = index + 1;

								let data = `
									<tr id="row-${row.id_barang}" class="row-${row.id_barang}">
										<td>
											<input type="hidden" value="${row.id_barang}" name="id_barang[]" id="input-id-barang-${row.id_barang}" class="input-id-barang-${row.id_barang}"/>
											<input type="hidden" value="${parseInt(row.laba)}" name="laba[]" id="input-laba-${row.id_barang}" class="input-laba input-laba-${row.id_barang}" />
											<input type="text" size="5" class="form-control qty-${row.id_barang}" id="qty-${row.id_barang}" name="qty[]" onkeyup="hitung_total_harga_beli(${row.id_barang}, '${row.harga_jual}', '${row.laba}');" value="1">
										</td>
										<td><span id="nama-barang-${row.id_barang}" class="nama-barang-${row.id_barang}">${row.nama_barang}</span></td>
										<td><span id="kode-barang-${row.id_barang}" class="kode-barang-${row.id_barang}">${row.kode_barang}</span></td>
										<td><span class="harga-${row.id_barang}" id="harga-${row.id_barang}">Rp. ${convertRupiah(parseInt(row.harga_jual))}</span></td>'
										<td>
											<span id="text-subtotal-${row.id_barang}" class="text-subtotal-${row.id_barang}">Rp. ${convertRupiah(parseInt(row.harga_jual))}</span>
										</td>
										<td>
											<button type="button" class="btn btn-danger btn-xs btn-icon" onclick="delete_row(${row.id_barang}, '${row.harga_jual}', '${row.laba}')"><i class="icon-bin"></i></button>
											<input type="text" hidden="" id="total-harga-beli-${row.id_barang}" value="${parseInt(row.harga_jual)}" class="total-harga-beli" name="total_harga_beli[]" />
										</td>
									</tr>
								`;

							$(`#tbody-cart`).append(data);
							hitung_total_harga_beli(row.id_barang, row.harga_jual, row.laba);
							$(`#input-index`).val(increase_index);
							}

							$('#search-barang-kasir-umum').val('');
							data_barang();
						}
					},
					complete : () => {$(`.loader`).hide()}
				});
		}
	}

	let add_row = (id, nama_barang, kode_barang, harga, laba, stok, id_jenis_barang) => {
		let jumlah_data = $(`.row-${id}`).length;
		if(stok == 0) {
			if(id_jenis_barang == 18) {
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
				hitung_total_harga_beli(id, harga, laba);
				$(`#input-index`).val(increase_index);

				}
			} else {
				alert('Stok Kosong');
			}
		} else {
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
			hitung_total_harga_beli(id, harga, laba);
			$(`#input-index`).val(increase_index);

			}
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
		let value_qty = parseInt($(`#qty-${index}`).val() == '' ? 0 : $(`#qty-${index}`).val())

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
