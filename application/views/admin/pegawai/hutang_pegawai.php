<!DOCTYPE html>
<html lang="en">
<head>
	<?php $this->load->view('admin/css'); ?>
	<script src="<?php echo base_url('assets/js/plugins/tables/datatables/datatables.min.js'); ?>"></script>

	<script type="text/javascript">
		$(function() {
			// $('.pegawai').DataTable();
		});
	</script>
</head>

<body>

	<?php $this->load->view('admin/nav'); ?>
	<?php $this->load->view('admin/pegawai/menu'); ?>

	<!-- Page container -->
	<div class="page-container">

		<!-- Page content -->
		<div class="page-content">

			<!-- Main content -->
			<div class="content-wrapper">
				<!-- Sidebars overview -->
				<div class="content">
					<div class="panel panel-flat border-top-success border-top-lg">
						<div class="panel-heading">
							<h5 class="panel-title">Data Hutang Pegawai</h5>
						</div>

						<div class="panel-body">

							<!-- message -->
							<?php if ($this->session->flashdata('status')): ?>
								<div class="alert alert-<?= $this->session->flashdata('status'); ?> no-border">
									<button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>
									<p class="message-text"><?= $this->session->flashdata('message'); ?></p>
							    </div>
							<?php endif ?>
							<!-- message -->

							<div class="tabbable">
								<ul class="nav nav-tabs">
									<li class="active"><a href="#basic-justified-tab1" data-toggle="tab" class="legitRipple" aria-expanded="true">Data Hutang Pegawai</a></li>
									<li class=""><a href="#basic-justified-tab2" data-toggle="tab" class="legitRipple" aria-expanded="false">Tambah Hutang Pegawai</a></li>
								</ul>

								<div class="tab-content">
									<div class="tab-pane active" id="basic-justified-tab1">
										<div class="form-group">
                    	<select id="id_pegawai" class="select-search-primary" onchange="get_hutang_pegawai()">
                      	<option value="Semua" selected>Semua</option>
                      	<?php foreach ($pegawai as $p): ?>
                        		<option value="<?php echo $p['pegawai_id'] ?>"><?php echo $p['nama']; ?></option>
                      	<?php endforeach ?>
                    	</select>
                    </div>
										<table class="table table-bordered table-striped" id="table_hutang_pegawai">
											<thead>
												<tr class="bg-success">
													<th class="text-center">No. </th>
													<th class="text-center">Nama Pegawai</th>
													<th class="text-center">Nominal Hutang</th>
													<th class="text-center" style="width: 300px;">Keterangan</th>
													<th class="text-center">Tanggal</th>
													<th class="text-center">Aksi</th>
												</tr>
											</thead>
											<tbody>

											</tbody>
										</table>
										<br>
										<ul class="pagination_hutang_pegawai"></ul>
									</div>

									<div class="tab-pane" id="basic-justified-tab2">
										<form action="<?php echo base_url('pegawai/hutang_pegawai/tambah_hutang_pegawai') ?>" method="post" id="form-tambah-hutang">
		    								<div class="form-group">
		    									<label for=""  class="control-label"><b>Hutang</b></label>
		    									<div class="input-group">
		    										<span class="input-group-addon"><b>Rp. </b></span>
			    									<input type="text" id="nominal" placeholder="Hutang" name="nominal" class="form-control rupiah">
		    									</div>
		    								</div>
											<div class="form-group">
		    									<label for=""  class="control-label"><b>Keterangan</b></label>
		    									<textarea name="keterangan" id="keterangan" placeholder="Keterangan" cols="20" rows="5" class="form-control"></textarea>
		    								</div>

		    								<div class="form-group">
					                          <button class="btn btn-md btn-warning  btn-icon" type="button" onclick="popup_pegawai();"><i class="icon-search4 position-left"></i> Cari</button>
					                        </div>

					                        <div class="form-group">
					                          <div class="table-responsive">
					                            <table class="table table-bordered table-striped" id="table_pegawai_row">
					                              <thead>
					                                <tr class="bg-success">
					                                  <th class="text-center">Kode</th>
					                                  <th class="text-center">Nama Pegawai</th>
					                                  <th class="text-center">Jabatan</th>
					                                  <th class="text-center">Aksi</th>
					                                </tr>
					                              </thead>
					                              <tbody>

					                              </tbody>
					                            </table>
					                          </div>
					                        </div>

		    								<button class="btn btn-success btn-icon" style="margin-top: 2em;" onclick="submit_hutang()"><i class="icon-floppy-disk position-left"></i> Simpan</button>
										</form>

									</div>
								</div>
							</div>

						</div>
					</div>
				</div>

				<!-- /sidebars overview -->

			</div>
			<!-- /main content -->

		</div>
		<!-- /page content -->

	</div>
	<!-- /page container -->

	<div id="modal_pegawai" class="modal fade">
	    <div class="modal-dialog modal-lg">
	      <div class="modal-content">
	        <div class="modal-header bg-primary">
	          <button type="button" class="close" data-dismiss="modal">&times;</button>
	          <h6 class="modal-title">Data Pegawai</h6>
	        </div>

	        <div class="modal-body">
	          <div class="form-group">
	            <div class="input-group">
	              <input type="text" id="cari_pegawai" placeholder="Cari Berdasarkan Nama Pegawai" class="form-control" onkeyup="get_pegawai();">
	              <span class="input-group-btn">
	                <button class="btn bg-primary" type="button"><i class="fa fa-search"></i></button>
	              </span>
	            </div>
	          </div>

	          <div class="table-responsive">
	            <table class="table table-bordered table-hover table-striped table_data_pegawai">
	              <thead>
	                <tr class="bg-primary">
	                  <th class="text-center">No</th>
	                  <th class="text-center">Kode</th>
	                  <th class="text-center">Nama Pegawai</th>
	                  <th class="text-center">Jabatan</th>
	                </tr>
	              </thead>
	              <tbody>

	              </tbody>
	            </table>
	            <br>
	            <ul class="pagination_pegawai"></ul>
	          </div>
	        </div>

	        <div class="modal-footer">
	          <button type="button" class="btn btn-link" id="tutup_data_pegawai" data-dismiss="modal"><i class="icon-cross"></i> Close</button>
	        </div>
	      </div>
	    </div>
	</div>

<script>
	$(window).load(function() {
		get_hutang_pegawai()
	})

	function get_hutang_pegawai() {
		let id_pegawai = $(`#id_pegawai`).val();

		$.ajax({
			url : '<?= base_url('pegawai/hutang_pegawai/get_hutang_pegawai') ?>',
			method : 'POST',
			dataType : 'json',
			data : {'id_pegawai' : `${id_pegawai}`},
			beforeSend : () => {
				let loading = `<tr id="tr-loading">
								<td colspan="7">
									<div class="loader">
										<img src="<?= base_url('assets/images/svg-loader/svg-loaders/vectorpaint.svg') ?>" alt="">
									</div>
								</td>
							</tr>`
			$(`#table_hutang_pegawai tbody`).html(loading);
			},
			success : function(res) {
				var row = '';
				if(res.status) {
					var i = 0;
					for(const item of res.data) {
						row += `
							<tr>
								<td style="text-align:center;">${++i}</td>
								<td style="text-align:center;">${item.nama_pegawai}</td>
								<td style="text-align:center;"><b>Rp. </b>${NumberToMoney(item.nominal)}</td>
								<td style="text-align:center;">${item.keterangan}</td>
								<td style="text-align:center;">${item.tanggal}</td>
								<td>
									<div class="text-center">
										<a href="#" class="btn btn-sm btn-icon btn-primary" data-toggle="modal" data-target="#modal_edit_hutang_pegawai_${item.id}"><i class="icon-pencil position-left"></i> Edit</a>
										<a href="<?php echo base_url('pegawai/hutang_pegawai/hapus_hutang_pegawai?id_hutang_pegawai=') ?>${item.id}" onclick="return confirm('Anda yakin ingin menghapus data?');" class="btn btn-danger btn-sm"><i class="icon-trash position-left"></i> Hapus</a>
									</div>

									<!-- Modal Edit Hutang Pegawai -->
									<div id="modal_edit_hutang_pegawai_${item.id}" class="modal fade">
									  <div class="modal-dialog">
									    <div class="modal-content">
									      <div class="modal-header bg-primary">
									        <button type="button" class="close" data-dismiss="modal">&times;</button>
									        <h6 class="modal-title">Edit Hutang Pegawai</h6>
									      </div>

										  <form action="<?= base_url('pegawai/hutang_pegawai/ubah_hutang_pegawai') ?>" method="POST" id="form-ubah-hutang-pegawai-${item.id}">
										      <div class="modal-body">
												<input type="text" name="id_hutang_pegawai" hidden="" value="${item.id}">
										    	<div class="form-group">
													<label class="control-label"><b>Nominal</b></label>
													<div class="input-group">
														<span class="input-group-addon"><b>Rp. </b></span>
														<input type="text" class="form-control rupiah" name="nominal" value="${NumberToMoney(item.nominal)}">
													</div>
												</div>

												<div class="form-group">
													<label class="control-label"><b>Keterangan</b></label>
													<textarea name="keterangan" cols="20" rows="5" class="form-control">${item.keterangan}</textarea>
												</div>

										      </div>

										      <div class="modal-footer">
										        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
										        <button type="button" onclick="submit_ubah_hutang_pegawai(${item.id})" class="btn btn-primary btn-icon"><i class="icon-pencil position-left"></i> Ubah</button>
										      </div>
										  </form>
									    </div>
									  </div>
									</div>
									<!-- Modal Edit Hutang Pegawai -->
								</td>
							</tr>
						`
					}
				} else {
					row += `
						<tr>
							<td colspan="6" class="text-semibold text-center">${res.message}</td>
						</tr>`
				}

				$(`#table_hutang_pegawai tbody`).html(row);
				pagination_hutang_pegawai()
			},
			complete : () => {$(`#tr-loading`).hide()}
		})
	}

	function pagination_hutang_pegawai($selector){
		var jumlah_tampil = '10';

	    if(typeof $selector == 'undefined'){
	        $selector = $("#table_hutang_pegawai tbody tr");
	    }
			window.tp = new Pagination('.pagination_hutang_pegawai', {
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

	function get_pegawai() {
		let search = $(`#cari_pegawai`).val()

		$.ajax({
			url : '<?= base_url('pegawai/hutang_pegawai/get_pegawai') ?>',
			data: {search},
			method : 'POST',
			dataType : 'json',
			success : (res) => {
				var row = '';
				if(res.status) {
					var i = 0;
					for(const item of res.data) {
						row += `
							<tr onclick="add_row('${item.pegawai_id}', '${item.nama}', '${item.jabatan}')">
								<td>${++i}</td>
								<td>${item.pegawai_id}</td>
								<td>${item.nama}</td>
								<td>${item.jabatan}</td>
							</tr>
						`
					}
				} else {
					row += `
						<tr>
							<td colspan="4">${res.message}</td>
						</tr>
					`
				}

				$(`.table_data_pegawai tbody`).html(row);
				pagination_pegawai();
			}
		})
	}

	function popup_pegawai() {
		$(`#modal_pegawai`).modal('toggle');
		get_pegawai()
	}

	function add_row(pegawai_id, nama_pegawai, jabatan) {
		var jumlah_row = $(`#row_${pegawai_id}`).length
		if(jumlah_row == 0) {
			row = `
				<tr id="row_${pegawai_id}" style="cursor: pointer;">
					<td class="text-center">
						${pegawai_id}
						<input type="hidden" name="pegawai_id[]" value="${pegawai_id}"/>
					</td>
					<td class="text-center">${nama_pegawai}</td>
					<td class="text-center">${jabatan}</td>
					<td class="text-center" width="180">
						<button type="button" class="btn btn-icon btn-danger" onclick="remove_row(this)"><i class="icon-trash"></i></button>
					</td>
				</tr>
			`
			$(`#table_pegawai_row tbody`).append(row);
		}

	}

	function remove_row(btn) {
		var row = btn.parentNode.parentNode;
    	row.parentNode.removeChild(row);
	}

	function pagination_pegawai($selector){
		var jumlah_tampil = '10';

	    if(typeof $selector == 'undefined'){
	        $selector = $(".table_data_pegawai tbody tr");
	    }
			window.tp = new Pagination('.pagination_pegawai', {
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
	// function set_input_nama_pegawai(nama, pegawai_id) {
	// 	$(`#nama_pegawai`).val(nama);
	// 	$(`#id_pegawai`).val(pegawai_id);

	// 	$(`#modal_pegawai`).modal('toggle');
	// }

	function submit_hutang() {
		$(`.rupiah`).unmask();

		$(`#form-tambah-hutang`).submit()
	}

	function submit_ubah_hutang_pegawai(id_hutang_pegawai) {
		$(`.rupiah`).unmask();

		$(`#form-ubah-hutang-pegawai-${id_hutang_pegawai}`).submit()
	}

</script>
<?php $this->load->view('admin/js'); ?>
</body>
</html>
