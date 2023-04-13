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

			<div class="panel panel-flat border-top-indigo border-top-lg">
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

							<div class="tabbable">
								<ul class="nav nav-tabs nav-tabs-component">
									<li class="active"><a href="#faktur" data-toggle="tab">Faktur</a></li>
									<li><a href="#tambah-faktur" data-toggle="tab">Tambah Faktur</a></li>
								</ul>

								<div class="tab-content">
									<div class="tab-pane active" id="faktur">
										<!-- table -->
										<div class="form-search form-horizontal" style="margin-top: 1.5em !important;">
											<div class="row">
												<div class="col-sm-12">
													<div class="form-group">
														<label class="control-label col-sm-1"><b>Tanggal</b></label>
														<div class="col-sm-11">
															<input type="text" class="form-control input-tgl" autocomplete="off" name="tgl_dari" id="input-tgl">
														</div>
													</div>
													<button class="btn btn-md btn-primary" type="button" onclick="cari_faktur_by_tanggal()"><i class="icon-search4 position-left"></i> Cari</button>
												</div>
											</div>

										</div>
										<div class="table-responsive">
											<table class="table table-bordered table-striped" id="table-faktur">
												<thead>
													<tr class="bg-success">
														<th class="text-center">No</th>
														<th class="text-center">Tanggal Pembayaran</th>
														<th class="text-center">Total</th>
														<th class="text-center">Tipe Pembayaran</th>
														<th class="text-center">Status Bayar</th>
														<th class="text-center">Action</th>
													</tr>
												</thead>
												<tbody id="tbody-faktur">
												</tbody>
											</table>
										</div>
										<br>
										<ul class="pagination_faktur"></ul>
										<!-- /table -->
									</div>

									<div class="tab-pane" id="tambah-faktur">
										<form action="<?= base_url('apotek/faktur/tambah_faktur') ?>" class="form-horizontal" method="POST" id="form-tambah-faktur">
											<fieldset class="content-group" style="margin-top: 2em !important;">


												<!-- radio no faktur -->
												<div class="form-group">
													<div class="col-sm-12">
														<label class="radio-inline">
															<input type="radio" name="radio-no-faktur" id="radio-no-faktur-default" class="styled control-success" checked="checked" onchange="onDefaultOrGenerateNoFaktur(this)">
															<b>Default</b>
														</label>

														<label class="radio-inline">
															<input type="radio" name="radio-no-faktur" id="radio-no-faktur-generate" class="styled control-success" onchange="onDefaultOrGenerateNoFaktur(this)">
															<b>Generate</b>
														</label>
													</div>
												</div>
												<!-- radio no faktur -->

												<div class="form-group">
													<label class="control-label col-sm-2"><b>No Faktur</b></label>
													<div class="col-sm-10">
														<input type="text" class="form-control" id="no-tambah-faktur" name="no_faktur" placeholder="No Faktur">
													</div>
												</div>

												<div class="form-group">
													<label class="control-label col-sm-2"><b>Supplier</b></label>
													<div class="col-sm-10">
														<select class="select-search" name="supplier">
															<?php foreach ($suppliers as $supplier): ?>
																<option value="<?= $supplier['id'] ?>"><?= $supplier['nama_supplier'] ?></option>
															<?php endforeach ?>
														</select>
													</div>
												</div>

												<div class="form-group">
													<label class="control-label col-sm-2"><b>Tipe Pembayaran</b></label>
													<div class="col-sm-10">
														<select class="select" name="tipe_pembayaran" onchange="select_tunai_or_kredit(this)">
															<option value="tunai">Tunai</option>
															<option value="kredit">Kredit</option>
														</select>
													</div>
												</div>

												<div class="form-group" hidden id="form-tgl-pembayaran">
													<label class="control-label col-sm-2"><b>Tanggal Pembayaran</b></label>
													<div class="col-sm-10">
														<div class="input-group">
															<span class="input-group-addon"><i class="icon-calendar"></i></span>
															<input type="text" class="form-control datepicker" placeholder="Pick a date&hellip;" name="tanggal_pembayaran" id="input-tgl-pembayaran-tambah-faktur">
														</div>
													</div>
												</div>

												<div class="table-responsive" style="margin-top: 3em;">
													<table class="table table-bordered table-striped" id="table-tambah-faktur">
														<thead>
															<tr class="bg-success">
																<th>Nama Barang</th>
																<th>Kode Barang</th>
																<th>Jumlah Beli</th>
																<th>Harga Awal</th>
																<th>Harga Jual</th>
																<th>Laba</th>
																<th>Tanggal Kadaluarsa</th>
																<th>Action</th>
															</tr>
														</thead>
														<tbody id="tbody-tambah-barang-faktur">
															<tr style="cursor: pointer" id="row-faktur-1">
																<td>
																	<input type="text" name="id_barang[]" id="id-barang-1" hidden="">
																	<!-- cari barang -->
																	<div class="form-group">
																		<div class="input-group">
																			<span class="input-group-btn">
																				<button class="btn btn-success btn-icon" type="button" onclick="get_barang(this, 1)"><i class="icon-search4"></i></button>
																			</span>
																			<input type="text" class="form-control" placeholder="Cari Barang Anda.." readonly id="search-barang-faktur-1" name="nama_barang[]">
																		</div>
																	</div>

																</td>
																<td>
																	<!-- kode barang -->
																	<div class="form-group">
																		<input type="text" class="form-control" readonly id="kode-barang-faktur-1" name="kode_barang[]">
																	</div>
																</td>
																<td>
																	<!-- jumlah beli -->
																	<div class="form-group">
																		<input type="text" class="form-control" id="jumlah-beli-faktur-1" name="jumlah_beli[]" onchange="hitung_total_harga_by_jumlah_beli(1)">
																	</div>
																</td>
																<td>
																	<!-- harga awal -->
																	<div class="form-group">
																		<div class="input-group">
																			<span class="input-group-addon"><b>Rp. </b></span>
																			<input type="text" class="form-control rupiah" id="harga-awal-faktur-1" name="harga_awal[]" onchange="hitung_total_harga_by_harga_awal(1)" placeholder="Harga Awal">
																		</div>
																	</div>
																</td>
																<td>
																	<!-- harga jual -->
																	<div class="form-group">
																		<div class="input-group">
																			<span class="input-group-addon"><b>Rp. </b></span>
																			<input type="text" class="form-control rupiah input-harga-jual" id="harga-jual-faktur-1" name="harga_jual[]" onchange="hitung_total_harga_beli(1)" placeholder="Harga Jual">
																		</div>
																	</div>
																</td>
																<td>
																	<!-- laba -->
																	<div class="form-group">
																		<div class="input-group">
																			<span class="input-group-addon"><b>Rp. </b></span>
																			<input type="text" class="form-control rupiah" name="laba[]" id="laba-faktur-1" readonly>
																		</div>
																	</div>
																</td>
																<td>
																	<!-- tanggal kadaluarsa -->
																	<div class="form-group">
																		<div class="input-group">
																			<span class="input-group-addon"><i class="icon-calendar"></i></span>
																			<input type="text" class="form-control datepicker" placeholder="Tanggal" name="tanggal_kadaluarsa[]" id="input-tgl-kadaluarsa-tambah-faktur-1">
																		</div>
																	</div>
																	<!-- tanggal kadaluarsa -->
																</td>
																<td>
																	<button class="btn btn-md btn-danger" disabled=""><i class="icon-bin"></i></button>
																	<!-- Modal Barang -->
																	<div id="modal_tampil_barang_1" class="modal fade">
																		<div class="modal-dialog modal-lg">
																			<div class="modal-content">
																				<div class="modal-header bg-primary">
																					<button type="button" class="close" data-dismiss="modal">&times;</button>
																					<h5 class="modal-title"> &nbsp;Barang</h5>
																				</div>

																				<div class="modal-body">
																					<div class="form-group">
																			         	<div class="input-group">
																				            <input type="text" id="search-barang-in-modal-1" placeholder="Cari Barang Yang Anda Inginkan..." class="form-control" onkeyup="cari_barang_in_modal(this.value, 1)">
																				            <span class="input-group-btn">
																				              <button class="btn bg-primary" type="button"><i class="fa fa-search"></i></button>
																				            </span>
																				        </div>
																			        </div>

																					<div class="table-responsive">
																						<table class="table table-bordered table-hover table-striped" id="table-barang-1">
																							<thead>
																								<tr class="bg-primary">
																									<th>No.</th>
																									<th>Kode Barang</th>
																									<th>Nama Barang</th>
																									<th>Stok</th>
																									<th>Harga Awal</th>
																									<th>Harga Jual</th>
																								</tr>
																							</thead>
																							<tbody id="tbody-barang">
																							</tbody>
																						</table>
																					</div>
																					<br>
																					<div class="pagination_barang"></div>
																				</div>

																				<div class="modal-footer">
																					<button class="btn btn-warning" data-dismiss="modal"><i class="icon-cross"></i> Keluar</button>

																				</div>
																			</div>
																		</div>
																	</div>
																	<!-- /Modal Barang -->
																</td>

															</tr>
														</tbody>
													</table>
												</div>

												<input type="text" hidden="" value="1" id="input-row-faktur-1">
												<input type="text" id="total_beli_1" hidden="" class="harga_total_beli">
												<button class="btn btn-md btn-warning" type="button" style="margin: 2em 0;" onclick="add_rows()"><i class="icon-plus3 position-left"></i> Tambah</button>

												<div class="form-group">
													<label class="control-label col-lg-2"><h6>Total Harga Beli</h6></label>
													<div class="col-lg-10">
														<div class="input-group">
															<span class="input-group-addon">Rp. </span>
															<input type="text" class="form-control rupiah" id="total-harga-beli-faktur" name="total_harga_beli" readonly="">
														</div>
													</div>
												</div>

												<div class="form-group">
													<div class="col-lg-2"></div>
													<div class="col-lg-10"><button class="btn btn-md bg-success-600" type="button" onclick="on_submit_tambah_faktur(this)"><i class="icon-floppy-disk position-left"></i> Simpan</button></div>
												</div>

											</fieldset>
										</form>
									</div>
								</div>

						</div>
					</div>
				</div>
			</div>

<script>

	$(document).ready(() => {
		
	})
	
	$(window).load(function() {
		$('.input-tgl').datepicker({
	        dateFormat : 'dd-mm-yy',
	        autoclose: true,
	        language: 'fr',
	        orientation: 'bottom auto',
	        todayBtn: 'linked',
	        todayHighlight: true
	    });

		let date_indonesian = (date_object, status) => {
		    let months_abjad = ['January', 'February', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'November', 'Desember'];
		    let date = (date_object.getDate() >= 10) ? date_object.getDate() : `0${date_object.getDate()}`;
		    let month_abjad = months_abjad[date_object.getMonth()];
		    let month_number = (date_object.getMonth() + 1 >= 10) ? date_object.getMonth() + 1 : `0${date_object.getMonth() + 1}`;
		    let year = date_object.getFullYear();

		    let format_1 = `${date} - ${month_abjad} - ${year}`;
		    let format_2 = `${date} - ${month_number} - ${year}`;

		    return status == true ? format_1 : format_2;
	    }

	    let time_indonesian = (timestamp) => {
			let unix = new Date(timestamp).getTime() / 1000
			let dateObj = new Date(unix * 1000)
			let hour = dateObj.getHours()
			let minute = "0" + dateObj.getMinutes()
			let second = "0" + dateObj.getSeconds()

			var formattedTime = hour + ':' + minute.substr(-2) + ':' + second.substr(-2);
			return formattedTime;
	    }

	    $('#input-tgl').change(function(e){
		   cari_faktur_by_tanggal();
		});

		$.ajax({
			url : '<?= base_url('apotek/faktur/get_faktur_ajax') ?>',
			method : 'GET',
			dataType : 'json',
			beforeSend : () => {
				let loading = `<tr id="tr-loading">
								<td colspan="7">
									<div class="loader">
										<img src="<?= base_url('assets/images/svg-loader/svg-loaders/vectorpaint.svg') ?>" alt="">
									</div>
								</td>
							</tr>`
				$(`#table-faktur tbody`).html(loading);
			},
			success : (res) => {
				let row = '';

				if(res.status) {
					let i = 0;
					for(const item of res.data) {
						row += 	`
							<tr>
								<td class="text-center">${++i}</td>
								<td class="text-center">
									<span><i class="icon-calendar" style="margin-right: 5px !important;"></i> ${date_indonesian(new Date(item.created_at))}</span>
								</td>
								<td class="text-right"></i><b>Rp. </b>${NumberToMoney(item.total_harga_beli)}</td>
								<td class="text-center">${item.tipe_pembayaran}</td>
								<td class="text-center">
									${item.status_bayar == 1 ? 'Lunas' : 'Belum Lunas<br><span class="label label-primary">'+item.tanggal_pembayaran.split("-").reverse().join("-")+'</span>'}
								</td>
								<td>
									<div class="text-center">
										<a href="#" class="btn btn-xs btn-icon btn-primary" onclick="get_supplier(this, ${item.id}, ${item.id_supplier})"><i class="icon-pencil position-left"></i> Edit</a>
										<a href="<?= base_url("apotek/faktur/detail_faktur?id_faktur=") ?>${item.id}" class="btn btn-xs btn-icon btn-info"><i class="icon-search4 position-left"></i> Detail</a>
										<a href="#" class="btn btn-xs btn-icon btn-danger" data-toggle="modal" data-target="#modal_hapus_faktur_${item.id}"><i class="icon-trash position-left"></i> Hapus</a>
									</div>

									<!-- Modal Ubah Faktur -->
									<div id="modal_ubah_faktur_${item.id}" class="modal fade">
										<div class="modal-dialog">
											<div class="modal-content">
												<div class="modal-header bg-primary">
													<button type="button" class="close" data-dismiss="modal">&times;</button>
													<h5 class="modal-title">Ubah Faktur</h5>
												</div>

												<form action="<?= base_url('apotek/faktur/ubah_faktur') ?>" method="POST">
												<div class="modal-body">
													<input type="text" value="${item.id}" name="id_faktur" hidden>

													<div class="form-group">
														<label class="control-label"><b>No Faktur</b></label>
														<input type="text" class="form-control" name="no_faktur" id="no-ubah-faktur-${item.id}" value="${item.no_faktur}">
													</div>

													<div class="form-group">
														<label class="control-label"><b>Supplier</b></label>
															<select class="select-search-primary" name="supplier" id="select-supplier-${item.id}">
																
															</select>
													</div>

													<div class="form-group">
														<label class="control-label"><b>Status Bayar</b></label>
														<select class="select-primary" name="status_bayar">
															<option value="1" ${item.status_bayar == 1 ? 'selected' : ''}>Lunas</option>
															<option value="0" ${item.status_bayar == 0 ? 'selected' : ''}>Belum Lunas</option>
														</select>
													</div>

												</div>

												<div class="modal-footer">
													<button class="btn btn-link" data-dismiss="modal"><i class="icon-cross position-left"></i> Keluar</button>
													<button class="btn btn-primary" type="submit"><i class="icon-pencil position-left"></i> Ubah</button>
												</div>
												</form>
											</div>
										</div>
									</div>
									<!-- /Modal Ubah Faktur -->

									<!-- Modal Hapus faktur -->
									<div id="modal_hapus_faktur_${item.id}" class="modal fade">
										<div class="modal-dialog">
											<div class="modal-content">
												<div class="modal-header bg-danger">
													<button type="button" class="close" data-dismiss="modal">&times;</button>
													<h5 class="modal-title">Hapus Faktur</h5>
												</div>
												<div class="modal-body">
													<div class="alert alert-danger no-border">
														<p>Apakah Anda Ingin <span class="text-semibold text-center">Menghapus</span> Faktur ${item.no_faktur} ?</p>
												    </div>
												</div>
												<div class="modal-footer">
													<button class="btn btn-link" data-dismiss="modal"><i class="icon-cross position-left"></i> Keluar</button>
													<a href="<?= base_url('apotek/faktur/hapus_faktur?id_faktur=') ?>${item.id}" class="btn btn-danger"><i class="icon-bin position-left"></i> Hapus</a>
												</div>
											</div>
										</div>
									</div>
									<!-- /Modal Hapus faktur -->

								</td>

							</tr>
						`
					}
				} else {
					row += `
						<tr>
							<td colspan="6" class="text-center">${res.message}</td>
						</tr>
					`
				}

				$(`#table-faktur tbody`).html(row);
			    $(`.select-primary`).select2({
			        minimumResultsForSearch: Infinity,
			        containerCssClass: 'bg-primary-400'
			    })
			    $(`.select-search-primary`).select2({
			      	containerCssClass : 'bg-primary-400'
			    })
				pagination_faktur()
			},
			complete : () => {$(`#tr-loading`).hide()}
		})
	});

	function cari_faktur_by_tanggal() {
		let date_indonesian = (date_object, status) => {
		    let months_abjad = ['January', 'February', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'November', 'Desember'];
		    let date = (date_object.getDate() >= 10) ? date_object.getDate() : `0${date_object.getDate()}`;
		    let month_abjad = months_abjad[date_object.getMonth()];
		    let month_number = (date_object.getMonth() + 1 >= 10) ? date_object.getMonth() + 1 : `0${date_object.getMonth() + 1}`;
		    let year = date_object.getFullYear();

		    let format_1 = `${date} - ${month_abjad} - ${year}`;
		    let format_2 = `${date} - ${month_number} - ${year}`;

		    return status == true ? format_1 : format_2;
	    }

	    let time_indonesian = (timestamp) => {
			let unix = new Date(timestamp).getTime() / 1000
			let dateObj = new Date(unix * 1000)
			let hour = dateObj.getHours()
			let minute = "0" + dateObj.getMinutes()
			let second = "0" + dateObj.getSeconds()

			var formattedTime = hour + ':' + minute.substr(-2) + ':' + second.substr(-2);
			return formattedTime;
	    }
		let value_tanggal = $(`#input-tgl`).val();
		// $('#popup_load').show();
		$.ajax({
			url : '<?= base_url('apotek/faktur/cari_faktur_by_tanggal_ajax') ?>',
			data : {'tanggal' : `${value_tanggal}`},
			dataType : 'json',
			method : 'POST',
			beforeSend : () => {
				let loading = `<tr id="tr-loading">
								<td colspan="7">
									<div class="loader">
										<img src="<?= base_url('assets/images/svg-loader/svg-loaders/vectorpaint.svg') ?>" alt="">
									</div>
								</td>
							</tr>`
				$(`#table-faktur tbody`).html(loading);
			},
			success : (res) => {
				let row = '';

				if(res.status) {
					let i = 0;
					for(const item of res.data) {
						row += 	`
							<tr>
								<td class="text-center">${++i}</td>
								<td class="text-center">
									<span><i class="icon-calendar" style="margin-right: 5px !important;"></i> ${date_indonesian(new Date(item.created_at))}</span>
								</td>
								<td class="text-right"></i><b>Rp. </b>${NumberToMoney(item.total_harga_beli)}</td>
								<td class="text-center">${item.tipe_pembayaran}</td>
								<td class="text-center">
									${item.status_bayar == 1 ? 'Lunas' : 'Belum Lunas<br><span class="label label-primary">'+item.tanggal_pembayaran.split("-").reverse().join("-")+'</span>'}
								</td>
								<td>
									<div class="text-center">
										<a href="#" class="btn btn-xs btn-icon btn-primary" onclick="get_supplier(this, ${item.id}, ${item.id_supplier})"><i class="icon-pencil position-left"></i> Edit</a>
										<a href="<?= base_url("apotek/faktur/detail_faktur?id_faktur=") ?>${item.id}" class="btn btn-xs btn-icon btn-info"><i class="icon-search4 position-left"></i> Detail</a>
										<a href="#" class="btn btn-xs btn-icon btn-danger" data-toggle="modal" data-target="#modal_hapus_faktur_${item.id}"><i class="icon-trash position-left"></i> Hapus</a>
									</div>

									<!-- Modal Ubah Faktur -->
									<div id="modal_ubah_faktur_${item.id}" class="modal fade">
										<div class="modal-dialog">
											<div class="modal-content">
												<div class="modal-header bg-primary">
													<button type="button" class="close" data-dismiss="modal">&times;</button>
													<h5 class="modal-title">Ubah Faktur</h5>
												</div>

												<form action="<?= base_url('apotek/faktur/ubah_faktur') ?>" method="POST">
												<div class="modal-body">
													<input type="text" value="${item.id}" name="id_faktur" hidden>

													<div class="form-group">
														<label class="control-label"><b>No Faktur</b></label>
														<input type="text" class="form-control" name="no_faktur" id="no-ubah-faktur-${item.id}" value="${item.no_faktur}">
													</div>

													<div class="form-group">
														<label class="control-label"><b>Supplier</b></label>
															<select class="select-search-primary" name="supplier" id="select-supplier-${item.id}">
															</select>
													</div>

													<div class="form-group">
														<label class="control-label"><b>Status Bayar</b></label>
														<select class="select-primary" name="status_bayar">
															<option value="1" ${item.status_bayar == 1 ? 'selected' : ''}>Lunas</option>
															<option value="0" ${item.status_bayar == 0 ? 'selected' : ''}>Belum Lunas</option>
														</select>
													</div>

												</div>

												<div class="modal-footer">
													<button class="btn btn-link" data-dismiss="modal"><i class="icon-cross position-left"></i> Keluar</button>
													<button class="btn btn-primary" type="submit"><i class="icon-pencil position-left"></i> Ubah</button>
												</div>
												</form>
											</div>
										</div>
									</div>
									<!-- /Modal Ubah Faktur -->

									<!-- Modal Hapus faktur -->
									<div id="modal_hapus_faktur_${item.id}" class="modal fade">
										<div class="modal-dialog">
											<div class="modal-content">
												<div class="modal-header bg-danger">
													<button type="button" class="close" data-dismiss="modal">&times;</button>
													<h5 class="modal-title"><i class="icon-bin"></i> &nbsp;Hapus Faktur</h5>
												</div>
												<div class="modal-body">
													<div class="alert alert-danger no-border">
														<p>Apakah Anda Ingin <span class="text-semibold text-center">Menghapus</span> Faktur ${item.no_faktur} ?</p>
												    </div>
												</div>
												<div class="modal-footer">
													<button class="btn btn-link" data-dismiss="modal"><i class="icon-cross"></i> Keluar</button>
													<a href="<?= base_url('apotek/faktur/hapus_faktur?id_faktur=') ?>${item.id}" class="btn btn-danger"><i class="icon-bin"></i> Hapus</a>
												</div>
											</div>
										</div>
									</div>
									<!-- /Modal Hapus faktur -->

								</td>

							</tr>
						`
					}
				} else {
					row += `
						<tr>
							<td colspan="6" class="text-center">${res.message}</td>
						</tr>
					`
				}
				// $('#popup_load').fadeOut();
				$(`#table-faktur tbody`).html(row);
			    $(`.select-primary`).select2({
			        minimumResultsForSearch: Infinity,
			        containerCssClass: 'bg-primary-400'
			    })
			    $(`.select-search-primary`).select2({
			      	containerCssClass : 'bg-primary-400'
			    })
				pagination_faktur()
			},
			complete : () => {$(`#tr-loading`).hide()}

		})
	}

	function pagination_faktur($selector) {
		var jumlah_tampil = '10';

	    if(typeof $selector == 'undefined'){
	        $selector = $("#table-faktur tbody tr");
	    }
			window.tp = new Pagination('.pagination_faktur', {
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
	
	function get_barang(e, no) {
		$.ajax({
			url : '<?= base_url('apotek/master/get_nama_barang_ajax') ?>',
			method : 'GET',
			dataType : 'json',
			success : (res) => {
				console.log(res);
				let row = '';
				if(res.status) {
					let i = 0;
					for(const item of res.data) {
						row += `
							<tr onclick="add_barang(${item.id}, ${no})">
								<td class="text-center">${++i}</td>
								<td class="text-center">${item.kode_barang}</td>
								<td class="text-center">${item.nama_barang}</td>
								<td class="text-center">${item.stok}</td>
								<td class="text-center">${item.harga_awal}</td>
								<td class="text-center">${item.harga_jual}</td>
							</tr>
						`
					}
				} else {
					row += `
						<tr>
							<td colspan="6" class="text-semibold text-center">${res.message}</td>
						</tr>
					`
				}

				$(`#modal_tampil_barang_${no} tbody`).html(row);
				pagination_barang(no);
				$(`#modal_tampil_barang_${no}`).modal('toggle')
			},
		})
	}

	function cari_barang_in_modal(value, no) {
		$.ajax({
			url : '<?= base_url('apotek/master/get_nama_barang_ajax') ?>',
			data : {'search_barang' : `${value}`},
			method : 'GET',
			dataType : 'json',
			success : (res) => {
				console.log(res);
				let row = '';
				if(res.status) {
					let i = 0;
					for(const item of res.data) {
						row += `
							<tr onclick="add_barang(${item.id}, ${no})">
								<td class="text-center">${++i}</td>
								<td class="text-center">${item.kode_barang}</td>
								<td class="text-center">${item.nama_barang}</td>
								<td class="text-center">${item.stok}</td>
								<td class="text-center">${item.harga_awal}</td>
								<td class="text-center">${item.harga_jual}</td>
							</tr>
						`
					}
				} else {
					row += `
						<tr>
							<td colspan="6" class="text-semibold text-center">${res.message}</td>
						</tr>
					`
				}

				$(`#modal_tampil_barang_${no} tbody`).html(row);
				pagination_barang(no);
			},
		})
	}

	function pagination_barang($no, $selector){
		var jumlah_tampil = '10';

	    if(typeof $selector == 'undefined'){
	        $selector = $(`#table-barang-${$no} tbody tr`);
	    }
			window.tp = new Pagination('.pagination_barang', {
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

	function get_supplier(e, id, id_supplier) {
		$.ajax({
			url : '<?= base_url('apotek/faktur/get_supplier_ajax') ?>',
			method : 'GET',
			dataType : 'json',
			success : (res) => {
				let i = 0;
				let row = '';
				for(const item of res.data) {
					if(item.id == id_supplier) {
						row += `
							<option value="${item.id}" selected>${item.nama_supplier}</option>						
						`
					} else {
						row += `
							<option value="${item.id}">${item.nama_supplier}</option>						
						`
					}
				}

				$(`#select-supplier-${id}`).html(row);
				$(`#modal_ubah_faktur_${id}`).modal('toggle');
			}
		})
	}

	let onDefaultOrGenerateNoFaktur = (e) => {
		let inputNoFaktur = $(`#no-tambah-faktur`)

		if(e.id == "radio-no-faktur-generate") {
			let random_number = Math.floor(Math.random() * 100);
			let today = new Date();
			let today_code = `${today.getFullYear()}${(today.getMonth()+1)}${today.getDate()}-${random_number}`;

			inputNoFaktur.val(today_code);
		} else {
			inputNoFaktur.val('');
		}
	}

	let select_tunai_or_kredit = (e) => {
		if(e.value == "kredit") {
			$(`#form-tgl-pembayaran`).show();
		} else {
			$(`#form-tgl-pembayaran`).hide();
		}
	}

	let add_barang = (id_barang, row) => {
			$.ajax({
				url : `<?= base_url('apotek/faktur/barang?id_barang=') ?>${id_barang}`,
				method : 'GET',
				dataType : 'json',
				success : (res) => {
					console.log(res);
					$(`#id-barang-${row}`).val(res.data.id)
					$(`#search-barang-faktur-${row}`).val(res.data.nama_barang)
					$(`#kode-barang-faktur-${row}`).val(res.data.kode_barang)
					$(`#harga-awal-faktur-${row}`).val(convertRupiah(parseInt(res.data.harga_awal)))
					$(`#harga-jual-faktur-${row}`).val(convertRupiah(parseInt(res.data.harga_jual)))
					$(`#laba-faktur-${row}`).val(res.data.laba)
					$(`#input-tgl-kadaluarsa-tambah-faktur-${row}`).val(res.data.tanggal_kadaluarsa)

					$(`#modal_tampil_barang_${row}`).modal('toggle')
				}
			})
	}

	let hitung_total_harga_beli = (row) => {
		let unMaskInputHargaAwal = $(`#harga-awal-faktur-${row}`).unmask();
		let unMaskInputHargaJual = $(`#harga-jual-faktur-${row}`).unmask();
		let unMaskInputLaba = $(`#laba-faktur-${row}`).unmask();

		let hargaAwal = $(`#harga-awal-faktur-${row}`).val();
		let hargaJual = $(`#harga-jual-faktur-${row}`).val();
		let laba = $(`#laba-faktur-${row}`).val();
		let jumlah_beli = $(`#jumlah-beli-faktur-${row}`).val();

		let total_harga_beli = parseInt(jumlah_beli) * parseInt(hargaJual);
		let valueLaba = parseInt(hargaJual) - parseInt(hargaAwal);
		$(`#laba-faktur-${row}`).val(valueLaba);
		$(`#total_beli_${row}`).val(total_harga_beli);

		$('#total-harga-beli-faktur').val(0);

		let array_total_harga_beli_semua = new Array();

		$(`.harga_total_beli`).each((id, element) => {
			let value = element.value
			console.log(value);
			array_total_harga_beli_semua.push(parseInt(value))
		})
		// menambahkan semua isi array
		let sum_total_harga_beli = array_total_harga_beli_semua.reduce((total, value) => total + value, 0);
		// console.log(sum_total_harga_beli);
		$(`#total-harga-beli-faktur`).val(convertRupiah(sum_total_harga_beli));
	}

	let hitung_total_harga_by_harga_awal = (row) => {
		if($(`#harga-jual-faktur-${row}`).val() !== 0 || $(`#harga-jual-faktur-${row}`).val() !== "") {
			let unMaskInputHargaAwal = $(`#harga-awal-faktur-${row}`).unmask();
			let unMaskInputHargaJual = $(`#harga-jual-faktur-${row}`).unmask();
			let unMaskInputLaba = $(`#laba-faktur-${row}`).unmask();

			let hargaAwal = $(`#harga-awal-faktur-${row}`).val();
			let hargaJual = $(`#harga-jual-faktur-${row}`).val();
			let laba = $(`#laba-faktur-${row}`).val();
			let jumlah_beli = $(`#jumlah-beli-faktur-${row}`).val();

			let total_harga_beli = parseInt(jumlah_beli) * parseInt(hargaJual);
			let valueLaba = parseInt(hargaJual) - parseInt(hargaAwal);

			$(`#laba-faktur-${row}`).val(valueLaba);
			$(`#total_beli_${row}`).val(total_harga_beli);

			$('#total-harga-beli-faktur').val(0);

			let array_total_harga_beli_semua = new Array();

			$(`.harga_total_beli`).each((id, element) => {
				let value = element.value
				console.log(value);
				array_total_harga_beli_semua.push(parseInt(value))
			})
			// menambahkan semua isi array
			let sum_total_harga_beli = array_total_harga_beli_semua.reduce((total, value) => total + value, 0);
			// console.log(sum_total_harga_beli);
			$(`#total-harga-beli-faktur`).val(convertRupiah(sum_total_harga_beli));

			}
	}

	let hitung_total_harga_by_jumlah_beli = (row) => {
		let inputHargaJual = $(`#harga-jual-faktur-${row}`)
		let inputHargaAwal = $(`#harga-awal-faktur-${row}`)
		if (inputHargaJual.val() !== 0 || inputHargaJual.val() !== "") {
			let unMaskInputHargaAwal = $(`#harga-awal-faktur-${row}`).unmask();
			let unMaskInputHargaJual = $(`#harga-jual-faktur-${row}`).unmask();
			let unMaskInputLaba = $(`#laba-faktur-${row}`).unmask();

			let hargaAwal = $(`#harga-awal-faktur-${row}`).val();
			let hargaJual = $(`#harga-jual-faktur-${row}`).val();
			let laba = $(`#laba-faktur-${row}`).val();
			let jumlah_beli = $(`#jumlah-beli-faktur-${row}`).val();

			let total_harga_beli = parseInt(jumlah_beli) * parseInt(hargaJual);
			let valueLaba = parseInt(hargaJual) - parseInt(hargaAwal);

			$(`#laba-faktur-${row}`).val(valueLaba);
			$(`#total_beli_${row}`).val(total_harga_beli);

			$('#total-harga-beli-faktur').val(0);

			let array_total_harga_beli_semua = new Array();

			$(`.harga_total_beli`).each((id, element) => {
				let value = element.value
				console.log(value);
				array_total_harga_beli_semua.push(parseInt(value))
			})
			// menambahkan semua isi array
			let sum_total_harga_beli = array_total_harga_beli_semua.reduce((total, value) => total + value, 0);
			// console.log(sum_total_harga_beli);
			$(`#total-harga-beli-faktur`).val(convertRupiah(sum_total_harga_beli));
		}

		if(inputHargaAwal.val() !== 0 || inputHargaAwal.val() !== "") {
			let unMaskInputHargaAwal = $(`#harga-awal-faktur-${row}`).unmask();
			let unMaskInputHargaJual = $(`#harga-jual-faktur-${row}`).unmask();
			let unMaskInputLaba = $(`#laba-faktur-${row}`).unmask();

			let hargaAwal = $(`#harga-awal-faktur-${row}`).val();
			let hargaJual = $(`#harga-jual-faktur-${row}`).val();
			let laba = $(`#laba-faktur-${row}`).val();
			let jumlah_beli = $(`#jumlah-beli-faktur-${row}`).val();

			let total_harga_beli = parseInt(jumlah_beli) * parseInt(hargaJual);
			let valueLaba = parseInt(hargaJual) - parseInt(hargaAwal);

			$(`#laba-faktur-${row}`).val(valueLaba);
			$(`#total_beli_${row}`).val(total_harga_beli);

			$('#total-harga-beli-faktur').val(0);

			let array_total_harga_beli_semua = new Array();

			$(`.harga_total_beli`).each((id, element) => {
				let value = element.value
				console.log(value);
				array_total_harga_beli_semua.push(parseInt(value))
			})
			// menambahkan semua isi array
			let sum_total_harga_beli = array_total_harga_beli_semua.reduce((total, value) => total + value, 0);
			// console.log(sum_total_harga_beli);
			$(`#total-harga-beli-faktur`).val(convertRupiah(sum_total_harga_beli));
		}
	}

	let add_rows = () => {
		let input_row = $(`#input-row-faktur-1`).val();
		let increment_row =  parseInt(input_row) + 1;

		let rows = ` <tr style="cursor: pointer" id="row-faktur-${increment_row}">
				<td>
				<input type="text" name="id_barang[]" id="id-barang-${increment_row}" hidden>
					<!-- cari barang -->
					<div class="form-group">
						<div class="input-group">
							<span class="input-group-btn">
								<button class="btn btn-success btn-icon" type="button" onclick="get_barang(this, ${increment_row})"><i class=" icon-search4"></i></button>
							</span>
							<input type="text" class="form-control" placeholder="Cari Barang Anda.." readonly id="search-barang-faktur-${increment_row}" name="nama_barang[]">
						</div>
					</div>

				</td>
				<td>
					<!-- kode barang -->
					<div class="form-group">
						<input type="text" class="form-control" readonly id="kode-barang-faktur-${increment_row}" name="kode_barang[]">
					</div>
				</td>
				<td>
					<!-- jumlah beli -->
					<div class="form-group">
						<input type="text" class="form-control" id="jumlah-beli-faktur-${increment_row}" name="jumlah_beli[]" onchange="hitung_total_harga_by_jumlah_beli(${increment_row})">
					</div>
				</td>
				<td>
					<!-- harga awal -->
					<div class="form-group">
						<div class="input-group">
							<span class="input-group-addon"><b>Rp. </b></span>
							<input type="text" class="form-control rupiah" id="harga-awal-faktur-${increment_row}" name="harga_awal[]" onchange="hitung_total_harga_by_harga_awal(${increment_row})">
						</div>
					</div>
				</td>
				<td>
					<!-- harga jual -->
					<div class="form-group">
						<div class="input-group">
							<span class="input-group-addon"><b>Rp. </b></span>
							<input type="text" class="form-control rupiah input-harga-jual" id="harga-jual-faktur-${increment_row}" name="harga_jual[]" onchange="hitung_total_harga_beli(${increment_row})">
						</div>
					</div>
				</td>
				<td>
					<!-- laba -->
					<div class="form-group">
						<div class="input-group">
							<span class="input-group-addon"><b>Rp. </b></span>
							<input type="text" class="form-control rupiah" id="laba-faktur-${increment_row}" readonly name="laba[]">
						</div>
					</div>
				</td>
				<td>
					<!-- tanggal kadaluarsa -->
					<div class="form-group">
						<div class="input-group">
							<span class="input-group-addon"><i class="icon-calendar"></i></span>
							<input type="text" class="form-control datepicker" placeholder="Pick a date&hellip;" name="tanggal_kadaluarsa[]" id="input-tgl-kadaluarsa-tambah-faktur-${increment_row}">
						</div>
					</div>
					<!-- tanggal kadaluarsa -->
				</td>
				<td>
					<input type="text" id="total_beli_${increment_row}" hidden="" class="harga_total_beli">
					<button class="btn btn-md btn-danger" type="button" onclick="delete_rows(${increment_row})"><i class="icon-bin"></i></button>

					<!-- Modal Barang -->
					<div id="modal_tampil_barang_${increment_row}" class="modal fade">
						<div class="modal-dialog modal-lg">
							<div class="modal-content">
								<div class="modal-header bg-primary">
									<button type="button" class="close" data-dismiss="modal">&times;</button>
									<h5 class="modal-title"><i class="icon-plus3"></i> &nbsp;Barang</h5>
								</div>

								<div class="modal-body">
									<div class="form-group">
							         	<div class="input-group">
								            <input type="text" id="search-barang-in-modal-${increment_row}" placeholder="Cari Barang Yang Anda Inginkan..." class="form-control" onkeyup="cari_barang_in_modal(this.value, ${increment_row})">
								            <span class="input-group-btn">
								              <button class="btn bg-primary" type="button"><i class="fa fa-search"></i></button>
								            </span>
								        </div>
							        </div>

									<div class="table-responsive">
										<table class="table table-bordered table-hover table-striped" id="table-barang-${increment_row}">
											<thead>
												<tr class="bg-primary">
													<th>No.</th>
													<th>Kode Barang</th>
													<th>Nama Barang</th>
													<th>Stok</th>
													<th>Harga Awal</th>
													<th>Harga Jual</th>
												</tr>
											</thead>
											<tbody id="tbody-barang">
											</tbody>
										</table>
									</div>
									<br>
									<div class="pagination_barang"></div>
								</div>

								<div class="modal-footer">
									<button class="btn btn-warning" data-dismiss="modal"><i class="icon-cross"></i> Keluar</button>

								</div>
							</div>
						</div>
					</div>
					<!-- /Modal Barang -->
				</td>

			</tr>
		`
		$(`#table-tambah-faktur #tbody-tambah-barang-faktur`).append(rows);
		$(`#row-faktur-${increment_row}`).find(".datepicker").removeClass('hasDatePicker').datepicker({
			showOtherMonths: true,
	        selectOtherMonths: true,
	        showButtonPanel: true,
	        dateFormat : 'dd-mm-yy'
		});

		$(`#input-row-faktur-1`).val(increment_row);
	}

	let delete_rows = (row) => {
		let decrement_row = row - 1;
		$(`.rupiah`).unmask();

		let total_harga_beli = parseInt($(`#total-harga-beli-faktur`).val())
		let harga_total_beli = parseInt($(`#total_beli_${row}`).val())

		let total_harga_beli_decrease = total_harga_beli - harga_total_beli
		$(`#total-harga-beli-faktur`).val(total_harga_beli_decrease)

		$(`#row-faktur-${row}`).remove();
		$(`#input-row-faktur-1`).val(decrement_row);

		$('.rupiah').mask('000.000.000', {reverse: true});
	}

	let on_submit_tambah_faktur = (e) => {
		// e.preventDefault()
		$('.rupiah').unmask();

		$(`#form-tambah-faktur`).submit()
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
