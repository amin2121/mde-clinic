<!DOCTYPE html>
<html lang="en">
<head>
<?php $this->load->view('admin/css'); ?>
</head>

<body>

<?php $this->load->view('admin/nav'); ?>
<?php $this->load->view('admin/rekam_medis/menu'); ?>

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
					<div class="heading-elements">
						<button class="btn btn-md btn-warning" type="button" onclick="window.location.href='<?= base_url() ?>rekam_medis/rekam_medis/pasien'"><i class="icon-arrow-left7"></i> Kembali</button>
      		</div>
				</div>


				<div class="panel-body">
					<div class="form-search form-horizontal">
						<div class="row">
							<div class="col-sm-6">
								<div class="form-group">
									<label class="control-label col-sm-2">Tanggal Dari</label>
									<div class="col-sm-10">
										<input type="text" class="form-control input-tgl" autocomplete="off" name="tgl_dari" id="input-tgl-dari">
									</div>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label class="control-label col-sm-3">Tanggal Sampai</label>
									<div class="col-sm-8">
										<input type="text" class="form-control input-tgl" name="tgl_sampai" id="input-tgl-sampai" autocomplete="off">
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-12">
							 	<button class="btn btn-md btn-primary" type="button" onclick="cari_rekam_medis()"><i class="icon-search4 position-left"></i> Cari</button>
							 	<button class="btn btn-md btn-success" type="button" onclick="data_rekam_medis()"><i class="icon-book2 position-left"></i> Lihat Semua</button>
							</div>
						</div>
					</div>
					<h4><i class="fa fa-heartbeat position-left"></i> Pasien : <?= $pasien['nama_pasien'] ?></h4>
					<div class="table-responsive">
						<table class="table table-bordered table-hover table-striped">
							<thead>
								<tr class="bg-primary">
									<th class="text-center">No. </th>
									<th class="text-center">No Invoice</th>
									<th class="text-center">Nama Dokter</th>
									<th class="text-center">Keluhan</th>
									<th class="text-center">Diagnosa</th>
									<th class="text-center">Tanggal Rekam Medis</th>
									<th class="text-center">Aksi</th>
								</tr>
							</thead>
							<tbody id="tbody-cari-rekam-medis">
							</tbody>
						</table>
						<br>
						<ul id="pagination"></ul>
					</div>
				</div>

			</div>

			<button type="button" class="btn btn-primary btn-sm" id="btn_modal_gambar_after" data-toggle="modal" data-target="#modal_gambar_after" style="display:none;">Launch <i class="icon-play3 position-right"></i></button>
      <div id="modal_gambar_after" class="modal fade">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header bg-indigo">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h6 class="modal-title">Input Gambar After</h6>
            </div>

            <div class="modal-body">
							<form action="<?php echo base_url(); ?>rekam_medis/rekam_medis/simpan_gambar_after" id="form_gambar_after" enctype="multipart/form-data" method="post">
								<input type="hidden" id="id_registrasi" name="id_registrasi" value="">
								<input type="hidden" id="id_pasien" name="id_pasien" value="">
								<div class="form-group">
									<label class="control-label"><b>Gambar After</b></label>
									<input name="gambar_after" type="file" class="form-control">
								</div>
            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-link" id="tutup_gambar_after" data-dismiss="modal"><i class="icon-cross position-left"></i> Keluar</button>
							<button type="submit" class="btn btn-success"><i class="icon-floppy-disk position-left"></i>Simpan</button>
            </div>
						</form>
          </div>
        </div>
      </div>

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

		data_rekam_medis();

		$('#btn_simpan_gambar_after').click(function(){
			$('#tutup_gambar_after').click();
      $.ajax({
          url : '<?php echo base_url(); ?>rekam_medis/rekam_medis/simpan_gambar_after',
          data : $('#form_gambar_after').serialize(),
          type : "POST",
          dataType : "json",
          success : function(row){
						data_rekam_medis();
          }
      });
		});
	})

	function data_rekam_medis() {
		$.ajax({
			url : '<?= base_url('rekam_medis/rekam_medis/cari_rekam_medis_ajax') ?>',
			data : {'id' : '<?= $this->uri->segment('4') ?>'},
			method : 'GET',
			dataType : 'json',
			success : (res) => {
				let row_rekam_medis = '';
				if(res.status) {
					let i = 0;
					let no = 0;
					// console.log(res.data[1][0].keluhan);
					for(const item of res.data) {

						row_rekam_medis += `
							<tr>
								<td class="text-center">${++no}</td>
								<td class="text-center">${item.invoice}</td>
								<td class="text-center">${item.nama_dokter}</td>
								<td class="text-center">${item.keluhan}</td>
								<td class="text-center">${item.diagnosa}</td>
								<td class="text-center">${item.tanggal}</td>
								<td class="text-center">
									<a href="<?= base_url('rekam_medis/rekam_medis/invoice/') ?>${item.id_registrasi}" class="btn btn-sm btn-primary btn-icon"><i class="icon-info22 position-left"></i> Detail Rekam Medis</a>
									<button class="btn btn-sm btn-info btn-icon" onclick="popup_modal_detail_resep('${item.id_registrasi}'); get_detail_resep('${item.id_registrasi}');"><i class="icon-info22 position-left"></i> Detail Resep</button>

									
									<div id="modal_detail_resep_${item.id_registrasi}" class="modal fade">
										<div class="modal-dialog">
											<div class="modal-content">
												<div class="modal-header bg-primary">
													<button type="button" class="close" data-dismiss="modal">&times;</button>
													<h5 class="modal-title">Detail Resep</h5>
												</div>

												<div class="modal-body">
													<div class="form-group">
														<div class="input-group">
															<input type="text" class="form-control" autocomplete="off" name="nama_barang" id="search_nama_barang_${item.id_registrasi}" onkeyup="get_detail_resep(${item.id_registrasi})" placeholder="Cari Berdasarkan Nama Barang">
															<span class="input-group-btn">
																<button class="btn bg-primary" type="button" onclick="get_detail_resep(${item.id_registrasi})"><i class="fa fa-search"></i></button>
															</span>
														</div>
													</div>

													<div class="table-responsive">
														<table class="table table-bordered table-hover table-striped" id="table_detail_resep_${item.id_registrasi}">
															<thead>
																<tr class="bg-primary">
																	<th class="text-center">No. </th>
																	<th class="text-center">Nama Barang</th>
																	<th class="text-center">Jumlah Obat</th>
																</tr>
															</thead>
															<tbody>

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
									
								</td>
							</tr>
						`
						i++;
					}

				} else {
					row_rekam_medis += `
						<tr>
							<td colspan="7" class="text-center">Data Pasien Kosong</td>
						</tr>
					`
				}
				$(`#tbody-cari-rekam-medis`).html(row_rekam_medis);
				paging();
			}
		})
	}

	function popup_modal_detail_resep(id_registrasi) {
		$(`#modal_detail_resep_${id_registrasi}`).modal('toggle');
	}

	function get_detail_resep(id_registrasi) {
		let search_nama_barang = $(`#search_nama_barang_${id_registrasi}`).val();

		$.ajax({
			url : '<?= base_url('rekam_medis/rekam_medis/get_detail_resep') ?>',
			data : {'search_nama_barang' : `${search_nama_barang}`, 'id_registrasi' : `${id_registrasi}`},
			dataType : 'json',
			method : 'POST',
			success : function(res) {
				let html = ''
				if(res.status) {
					let no = 0;
					for(const item of res.data) {
						html += `
							<tr>
								<td class="text-center">${++no}</td>
								<td class="text-center">${item.nama_barang}</td>
								<td class="text-center">${item.jumlah_obat}</td>
							</tr>
						`;
					}
				} else {
					html = `
						<tr>
							<td colspan="3" class="text-center">${res.message}</td>
						</tr>
					`
				}

				$(`#table_detail_resep_${id_registrasi} tbody`).html(html);
			}
		})
	}

	function paging($selector){
		var jumlah_tampil = '10';

			if(typeof $selector == 'undefined')
			{
				$selector = $("#tbody-cari-rekam-medis tr");
			}

			window.tp = new Pagination('#pagination', {
					itemsCount:$selector.length,
					pageSize : parseInt(jumlah_tampil),
					onPageSizeChange: function (ps) {
							console.log('changed to ' + ps);
					},
					onPageChange: function (paging) {
							//custom paging logic here
							//console.log(paging);
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

	function modal_gambar_after(id_registrasi, id_pasien){
		$('#id_registrasi').val(id_registrasi);
		$('#id_pasien').val(id_pasien);
		$('#btn_modal_gambar_after').click();
	}

	function direct_ke_invoice(invoice, id_resep, id_tindakan) {
		window.location.href = `<?= base_url('rekam_medis/rekam_medis/invoice/') ?>${invoice}/${id_resep}/${id_tindakan}`;
	}

	function cari_rekam_medis() {
		let value_input_tgl_dari = $(`#input-tgl-dari`).val();
		let value_input_tgl_sampai = $(`#input-tgl-sampai`).val();

		$.ajax({
			url : '<?= base_url('rekam_medis/rekam_medis/cari_rekam_medis_ajax') ?>',
			data : {'id' : '<?= $this->uri->segment('4') ?>', 'tgl_dari' : `${value_input_tgl_dari}`, 'tgl_sampai' : `${value_input_tgl_sampai}`},
			method : 'GET',
			dataType : 'json',
			success : (res) => {
				let row_rekam_medis = '';
				if(res.status) {
					let i = 0;
					let no = 0;
					console.log(res.data);
					for(const item of res.data) {

						row_rekam_medis += `
							<tr>
								<td class="text-center">${++no}</td>
								<td class="text-center">${item.invoice}</td>
								<td class="text-center">${item.nama_dokter}</td>
								<td class="text-center">${item.keluhan}</td>
								<td class="text-center">${item.diagnosa}</td>
								<td class="text-center">${item.tanggal}</td>
								<td class="text-center">
									<a href="<?= base_url('rekam_medis/rekam_medis/invoice/') ?>${item.id_registrasi}" class="btn btn-sm btn-primary btn-icon"><i class="icon-info22 position-left"></i> Detail Rekam Medis</a>
									<button class="btn btn-sm btn-info btn-icon" onclick="popup_modal_detail_resep('${item.id_registrasi}'); get_detail_resep('${item.id_registrasi}');"><i class="icon-info22 position-left"></i> Detail Resep</button>

									
									<div id="modal_detail_resep_${item.id_registrasi}" class="modal fade">
										<div class="modal-dialog">
											<div class="modal-content">
												<div class="modal-header bg-primary">
													<button type="button" class="close" data-dismiss="modal">&times;</button>
													<h5 class="modal-title">Detail Resep</h5>
												</div>

												<div class="modal-body">
													<div class="form-group">
														<div class="input-group">
															<input type="text" class="form-control" autocomplete="off" name="nama_barang" id="search_nama_barang_${item.id_registrasi}" onkeyup="get_detail_resep(${item.id_registrasi})" placeholder="Cari Berdasarkan Nama Barang">
															<span class="input-group-btn">
																<button class="btn bg-primary" type="button" onclick="get_detail_resep(${item.id_registrasi})"><i class="fa fa-search"></i></button>
															</span>
														</div>
													</div>

													<div class="table-responsive">
														<table class="table table-bordered table-hover table-striped" id="table_detail_resep_${item.id_registrasi}">
															<thead>
																<tr class="bg-primary">
																	<th class="text-center">No. </th>
																	<th class="text-center">Nama Barang</th>
																	<th class="text-center">Jumlah Obat</th>
																</tr>
															</thead>
															<tbody>

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
									
								</td>
							</tr>
						`
						i++;
					}

				} else {
					row_rekam_medis += `
						<tr>
							<td colspan="7" class="text-center">Data Pasien Kosong</td>
						</tr>
					`
				}
				$(`#tbody-cari-rekam-medis`).html(row_rekam_medis);
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
