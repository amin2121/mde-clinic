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

							<div class="tabbable">
								<ul class="nav nav-tabs nav-tabs-component">
									<li class="active"><a href="#mutasi" data-toggle="tab">Mutasi Barang</a></li>
									<li><a href="#tambah-mutasi" data-toggle="tab">Tambah Mutasi Barang</a></li>
								</ul>

								<div class="tab-content">
									<div class="tab-pane active" id="mutasi">
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
												</div>
												<div class="col-sm-12">
													<button class="btn btn-sm btn-primary" type="button" onclick="cari_mutasi_by_tanggal()"><i class="icon-search4 position-left"></i> Cari</button>
													<button class="btn btn-sm btn-success" type="button" onclick="cari_mutasi_by_tanggal()"><i class="icon-book2 position-left"></i> Lihat Semua</button>
												</div>
											</div>
										</div>
										<div class="table-responsive">
											<table class="table table-bordered table-striped" id="table-mutasi">
												<thead>
													<tr class="bg-success">
														<th class="text-center">No</th>
														<th class="text-center">Kode Mutasi</th>
														<th class="text-center">Kirim Ke Cabang</th>
														<th class="text-center">Total Harga Mutasi</th>
														<th class="text-center">Tanggal Mutasi</th>
														<th class="text-center">Action</th>
													</tr>
												</thead>
												<tbody id="tbody-mutasi">
												</tbody>
											</table>
										</div>
										<br>
										<ul class="pagination_mutasi"></ul>
										<!-- /table -->
									</div>

									<div class="tab-pane" id="tambah-mutasi">
										<form action="<?= base_url('farmasi/mutasi_barang/tambah_mutasi_barang') ?>" class="form-horizontal" method="POST" id="form-tambah-mutasi">
												<div class="form-group">
													<label class="control-label col-sm-2"><b>Dikirim Ke</b></label>
													<div class="col-sm-10">
														<select class="select-search" name="id_cabang">
															<?php foreach ($cabang as $c): ?>
																<option value="<?= $c['id'] ?>"><?= $c['nama'] ?></option>
															<?php endforeach ?>
														</select>
													</div>
												</div>

												<button class="btn btn-sm btn-primary" type="button" onclick="tampil_modal_cari_barang()" style="margin-top: 2em; margin-bottom: 1em;"><i class="fa fa-search position-left"></i> Cari Barang</button>
												<div class="table-responsive">
													<table class="table table-bordered table-striped" id="table-tambah-mutasi-barang">
														<thead>
															<tr class="bg-success">
																<th class="text-center">Kode Barang</th>
																<th class="text-center">Nama Barang</th>
																<th class="text-center">Stok Barang</th>
																<th class="text-center">Stok Mutasi</th>
																<th class="text-center">Harga Awal</th>
																<th class="text-center">Harga Jual</th>
																<th class="text-center">Action</th>
															</tr>
														</thead>
														<tbody id="tbody-tambah-mutasi-barang">

														</tbody>
													</table>
												</div>

												<br>

												<div class="form-group">
													<label class="control-label col-sm-2"><b>Total Harga Kirim</b></label>
													<div class="col-sm-10">
														<input type="text" class="form-control" id="total_harga_kirim" value="0" name="total_harga_kirim" readonly>
													</div>
												</div>

												<button class="btn btn-md bg-success-600" type="submit" id="btn_tambah_mutasi" disabled=""><i class="icon-floppy-disk position-left"></i> Simpan</button>

											</fieldset>
										</form>
									</div>
								</div>

						</div>
					</div>
				</div>
			</div>

<!-- Modal Barang -->
<div id="modal_cari_barang" class="modal fade">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header bg-primary">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h5 class="modal-title"> &nbsp;Barang</h5>
			</div>

			<div class="modal-body">
				<div class="form-group">
		         	<div class="input-group">
			            <input type="text" id="search_barang" placeholder="Cari Barang Yang Anda Inginkan..." class="form-control">
			            <span class="input-group-btn">
			              <button class="btn bg-primary" type="button"><i class="fa fa-search"></i></button>
			            </span>
			        </div>
		        </div>

				<div class="table-responsive">
					<table class="table table-bordered table-hover table-striped" id="table-barang">
						<thead>
							<tr class="bg-primary">
								<th class="text-center">No</th>
								<th class="text-center">Kode Barang</th>
								<th class="text-center">Nama Barang</th>
								<th class="text-center">Stok</th>
								<th class="text-center">Harga Awal</th>
								<th class="text-center">Harga Jual</th>
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

<!-- Modal Detail Mutasi -->
<div id="modal_detail_mutasi" class="modal fade">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header bg-warning">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h5 class="modal-title">Detail Mutasi Barang</h5>
			</div>

			<div class="modal-body">
			<div class="table-responsive">
				<table class="table table-bordered table-hover table-striped">
					<thead>
						<tr class="bg-warning">
							<th class="text-center">Kode Barang</th>
							<th class="text-center">Nama Barang</th>
							<th class="text-center">Jumlah Kirim</th>
							<th class="text-center">Harga Awal</th>
							<th class="text-center">Harga Jual</th>
						</tr>
					</thead>
					<tbody id="tbody-detail-mutasi-barang">

					</tbody>
				</table>
			</div>
			</div>

			<div class="modal-footer">
				<button class="btn btn-link" data-dismiss="modal"><i class="icon-cross position-left"></i> Keluar</button>
			</div>
		</div>
	</div>
</div>
<!-- /Modal Detail Mutasi -->

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
		   cari_mutasi_by_tanggal();
		});

		$.ajax({
			url : '<?= base_url('farmasi/mutasi_barang/get_mutasi_ajax') ?>',
			method : 'POST',
			dataType : 'json',
			beforeSend : () => {
				let loading = `<tr id="tr-loading">
								<td colspan="7">
									<div class="loader">
										<img src="<?= base_url('assets/images/svg-loader/svg-loaders/vectorpaint.svg') ?>" alt="">
									</div>
								</td>
							</tr>`
				$(`#table-mutasi tbody`).html(loading);
			},
			success : (res) => {
				let row = '';

				if(res.status) {
					let i = 0;
					for(const item of res.data) {
						row += 	`
							<tr>
								<td class="text-center">${++i}</td>
								<td class="text-center">${item.kode_mutasi_barang}</td>
								<td class="text-center">${item.nama_cabang_kirim}</td>
								<td class="text-center"><b>Rp.</b> ${NumberToMoney(item.total_harga_kirim)}</td>
								<td class="text-center">
									<span><i class="icon-calendar" style="margin-right: 5px !important;"></i> ${item.tanggal} ${item.waktu}</span>
								</td>
								<td>
									<div class="text-center">
										<a href="#" class="btn btn-sm btn-icon btn-warning" data-toggle="modal" data-target="#modal_detail_mutasi" onclick="get_detail_mutasi_barang(${item.id});"><i class="icon-search4 position-left"></i> Detail</a>
										<a href="#" class="btn btn-sm btn-icon btn-danger" data-toggle="modal" data-target="#modal_hapus_mutasi_${item.id}"><i class="icon-trash position-left"></i> Hapus</a>
									</div>

									<!-- Modal Hapus mutasi -->
									<div id="modal_hapus_mutasi_${item.id}" class="modal fade">
										<div class="modal-dialog">
											<div class="modal-content">
												<div class="modal-header bg-danger">
													<button type="button" class="close" data-dismiss="modal">&times;</button>
													<h5 class="modal-title">Hapus Mutasi Barang</h5>
												</div>
												<div class="modal-body">
													<div class="alert alert-danger no-border">
														<p>Apakah Anda Ingin <span class="text-semibold text-center">Menghapus</span> Mutasi ${item.kode_mutasi_barang} ?</p>
												    </div>
												</div>
												<div class="modal-footer">
													<button class="btn btn-link" data-dismiss="modal"><i class="icon-cross position-left"></i> Keluar</button>
													<a href="<?php echo base_url(); ?>farmasi/mutasi_barang/hapus_mutasi/${item.id}" class="btn btn-danger"><i class="icon-bin position-left"></i> Hapus</a>
												</div>
											</div>
										</div>
									</div>
									<!-- /Modal Hapus mutasi -->

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

				$(`#table-mutasi tbody`).html(row);
			    $(`.select-primary`).select2({
			        minimumResultsForSearch: Infinity,
			        containerCssClass: 'bg-primary-400'
			    })
			    $(`.select-search-primary`).select2({
			      	containerCssClass : 'bg-primary-400'
			    })
				pagination_mutasi()
			},
			complete : () => {$(`#tr-loading`).hide()}
		})
	});

	function tampil_modal_cari_barang() {
		get_barang()
		$(`#modal_cari_barang`).modal('toggle');
	}

	function cari_mutasi_by_tanggal() {
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
			url : '<?= base_url('farmasi/mutasi_barang/cari_mutasi_by_tanggal_ajax') ?>',
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
				$(`#table-mutasi tbody`).html(loading);
			},
			success : (res) => {
				let row = '';

				if(res.status) {
					let i = 0;
					for(const item of res.data) {
						row += 	`
							<tr>
								<td class="text-center">${++i}</td>
								<td class="text-center">${item.kode_mutasi_barang}</td>
								<td class="text-center">${item.nama_cabang_kirim}</td>
								<td class="text-center"><b>Rp.</b> ${NumberToMoney(item.total_harga_kirim)}</td>
								<td class="text-center">
									<span><i class="icon-calendar" style="margin-right: 5px !important;"></i> ${item.tanggal} ${item.waktu}</span>
								</td>
								<td>
									<div class="text-center">
										<a href="#" class="btn btn-sm btn-icon btn-warning" data-toggle="modal" data-target="#modal_detail_mutasi" onclick="get_detail_mutasi_barang(${item.id});"><i class="icon-search4 position-left"></i> Detail</a>
										<a href="#" class="btn btn-sm btn-icon btn-danger" data-toggle="modal" data-target="#modal_hapus_mutasi_${item.id}"><i class="icon-trash position-left"></i> Hapus</a>
									</div>

									<!-- Modal Hapus mutasi -->
									<div id="modal_hapus_mutasi_${item.id}" class="modal fade">
										<div class="modal-dialog">
											<div class="modal-content">
												<div class="modal-header bg-danger">
													<button type="button" class="close" data-dismiss="modal">&times;</button>
													<h5 class="modal-title">Hapus Mutasi Barang</h5>
												</div>
												<div class="modal-body">
													<div class="alert alert-danger no-border">
														<p>Apakah Anda Ingin <span class="text-semibold text-center">Menghapus</span> Mutasi ${item.kode_mutasi_barang} ?</p>
												    </div>
												</div>
												<div class="modal-footer">
													<button class="btn btn-link" data-dismiss="modal"><i class="icon-cross position-left"></i> Keluar</button>
													<a href="<?php echo base_url(); ?>farmasi/mutasi_barang/hapus_mutasi/${item.id}" class="btn btn-danger"><i class="icon-bin position-left"></i> Hapus</a>
												</div>
											</div>
										</div>
									</div>
									<!-- /Modal Hapus mutasi -->

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
				// $('#popup_load').fadeOut();
				$(`#table-mutasi tbody`).html(row);
			    $(`.select-primary`).select2({
			        minimumResultsForSearch: Infinity,
			        containerCssClass: 'bg-primary-400'
			    })
			    $(`.select-search-primary`).select2({
			      	containerCssClass : 'bg-primary-400'
			    })
				pagination_mutasi()
			},
			complete : () => {$(`#tr-loading`).hide()}

		})

		$(`#input-tgl`).val('');		
	}

	function pagination_mutasi($selector) {
		var jumlah_tampil = '10';

	    if(typeof $selector == 'undefined'){
	        $selector = $("#table-mutasi tbody tr");
	    }
			window.tp = new Pagination('.pagination_mutasi', {
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

	function get_barang() {
		let input_nama_barang_in_modal = $(`#search_barang`).val();

		$.ajax({
			url : '<?= base_url('farmasi/mutasi_barang/get_barang_stok') ?>',
			method : 'POST',
			data : {search : `${input_nama_barang_in_modal}`},
			dataType : 'json',
			success : (res) => {
				console.log(res);
				let row = '';
				if(res.status) {
					let i = 0;
					for(const item of res.data) {
						if(item.stok == '0' || parseInt(item.stok) < 0) {
							let stok = (item.stok == 0) ? "<span class='badge badge-danger'>Stok Kosong</span>" : "<span class='badge badge-danger'>"+item.stok+"</span>"
							row += `
								<tr>
									<td class="text-center">${++i}</td>
									<td class="text-center">${item.kode_barang}</td>
									<td class="text-center">${item.nama_barang}</td>
									<td class="text-center">${stok}</td>
									<td class="text-center"><b>Rp.</b> ${NumberToMoney(item.harga_awal)}</td>
									<td class="text-center"><b>Rp.</b> ${item.harga_jual}</td>
								</tr>
							`
						} else {
							row += `
								<tr onclick="add_row(${item.id}, '${item.kode_barang}', '${item.nama_barang}', '${item.stok}', '${item.harga_awal}', '${item.harga_jual}')" style="cursor: pointer">
									<td class="text-center">${++i}</td>
									<td class="text-center">${item.kode_barang}</td>
									<td class="text-center">${item.nama_barang}</td>
									<td class="text-center">${item.stok}</td>
									<td class="text-center"><b>Rp.</b> ${NumberToMoney(item.harga_awal)}</td>
									<td class="text-center"><b>Rp.</b> ${NumberToMoney(item.harga_jual)}</td>
								</tr>
							`
						}
					}
				} else {
					row += `
						<tr>
							<td colspan="6" class="text-semibold text-center">${res.message}</td>
						</tr>
					`
				}

				$(`#table-barang tbody`).html(row);
				pagination_barang();
			},
		});

		$('#search_barang').off('keyup').keyup(function(){
				get_barang();
		});
	}

	function add_row(id, kode_barang, nama_barang, stok, harga_awal, harga_jual) {
		let jumlah_row_barang = $(`#tr_mutasi_barang_${id}`).length;
		let row = '';

		if(jumlah_row_barang == 0) {
			row += `
				<tr id="tr_mutasi_barang_${id}">
					<td class="text-center">${kode_barang}</td>
					<td class="text-center">${nama_barang}</td>
					<td class="text-center">${stok}</td>
					<td class="text-center" width="150">
						<input type="hidden" name="id_barang[]" id="id_barang_${id}" value="${id}"/>
						<input type="hidden" name="stok_barang[]" id="stok_barang_${id}" value="${stok}"/>
						<input type="hidden" name="harga_awal[]" id="harga_awal_${id}" value="${harga_awal}"/>
						<input type="hidden" name="harga_jual[]" id="harga_jual_${id}" value="${harga_jual}"/>
						<input type="hidden" class="total_harga_awal" id="total_harga_awal_${id}" value="0"/>
						<input type="text" name="stok_mutasi[]" id="stok_mutasi_${id}" value="0" onkeyup="FormatCurrency(this); hitung_harga(${id}); hitung_total_harga();" class="form-control"/>
					</td>
					<td class="text-center">Rp. ${NumberToMoney(harga_awal)}</td>
					<td class="text-center">Rp. ${NumberToMoney(harga_jual)}</td>
					<td class="text-center">
						<button class="btn btn-danger btn-sm" type="button" onclick="delete_row(this)"><i class="icon-trash"></i></button>
					</td>
				</tr>
			`;

		}
		$(`#table-tambah-mutasi-barang tbody`).append(row);
		$(`#btn_tambah_mutasi`).prop('disabled', false);
	}

	function hitung_harga(id){
		var harga_awal = $('#harga_awal_'+id).val();
    harga_awal = harga_awal.split(',').join('');

		if (harga_awal == '') {
			harga_awal = 0;
		}

		var jumlah_kirim = $('#stok_mutasi_'+id).val();
    jumlah_kirim = jumlah_kirim.split(',').join('');

		if (jumlah_kirim == '') {
			jumlah_kirim = 0;
		}

		var total = jumlah_kirim * harga_awal;

		$('#total_harga_awal_'+id).val(NumberToMoney(total));
	}

	function hitung_total_harga(){
    var total = 0;
    $('.total_harga_awal').each(function(idx, elm){
      var f = elm.value;
  			f = f.split(',').join('');
  			if (f == '') {
  				f = 0;
  			}
    total += parseFloat(f);
    });

    $('#total_harga_kirim').val(NumberToMoney(total));
  }

	function delete_row(btn) {
		let row = btn.parentNode.parentNode;
 		row.parentNode.removeChild(row);

		hitung_total_harga();

 		let jumlah_row_table_stokopname = $(`#table-tambah-mutasi-barang tbody tr`).length
		if(jumlah_row_table_stokopname == 0) {
			$(`#btn_tambah_mutasi`).prop('disabled', true);
		}
	}

	function pagination_barang($no, $selector){
		var jumlah_tampil = '10';

	    if(typeof $selector == 'undefined'){
	        $selector = $(`#table-barang tbody tr`);
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

	function get_detail_mutasi_barang(id){
    $.ajax({
			url : '<?= base_url('farmasi/mutasi_barang/get_detail_mutasi_barang') ?>',
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
								<td class="text-center">${NumberToMoney(item.stok_kirim)}</td>
								<td class="text-center"><b>Rp. </b>${NumberToMoney(item.harga_awal)}</td>
								<td class="text-center"><b>Rp. </b>${NumberToMoney(item.harga_jual)}</td>
							</tr>
						`;

            total += parseFloat(item.harga_awal);
					}

				} else {
					row += `<tr>
						<td colspan="5" class="text-center">${res.message}</td>
					</tr>`
				}
				$(`#tbody-detail-mutasi-barang`).html(row);
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
