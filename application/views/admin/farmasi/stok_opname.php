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

			<div class="panel pane-flat border-top-success border-top-lg">
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

								<div class="form-group">
									<div class="input-group">
										<span class="input-group-btn">
											<button class="btn bg-primary" type="button" onclick="tampil_modal_barang();"><i class="fa fa-search"></i></button>
										</span>
										<input type="text" class="form-control" autocomplete="off" name="cari_jenis_barang" placeholder="Cari Barang" readonly="">
									</div>
								</div>
								<br>

								<!-- Modal Tampil Barang -->
								<div id="modal_tampil_barang" class="modal fade">
									<div class="modal-dialog modal-lg">
										<div class="modal-content">
											<div class="modal-header bg-primary">
												<button type="button" class="close" data-dismiss="modal">&times;</button>
												<h5 class="modal-title">Barang</h5>
											</div>

											<div class="modal-body">
												<div class="form-group">
													<div class="input-group">
															<input type="text" class="form-control" autocomplete="off" name="cari_barang" id="nama_barang" onkeyup="cari_barang()" placeholder="cari nama barang">
															<span class="input-group-btn">
																<button class="btn bg-primary" type="button" onclick="cari_barang()"><i class="fa fa-search"></i></button>
															</span>
													</div>
												</div>	
												<div class="table-responsive">
													<table class="table table-bordered table-striped" id="table_nama_barang">
														<thead>
															<tr class="bg-success">
																<th class="text-center">No</th>
																<th class="text-center">Kode Barang</th>
																<th class="text-center">Nama Barang</th>
															</tr>
														</thead>
														<tbody id="tbody-nama-barang">
															
														</tbody>
													</table>
												</div>
												<br>
												<ul id="pagination" class="pagination float-right">

												</ul>
											</div>
												
												
											<div class="modal-footer">
												<button class="btn btn-link" data-dismiss="modal"><i class="icon-cross"></i> Keluar</button>
											</div>
										</div>
									</div>
								</div>

								<form action="<?= base_url('farmasi/stok_opname/tambah_stok_opname') ?>" method="POST">
									<div class="table-responsive">
										<table class="table table-bordered table_stokopname">
											<thead>
												<tr class="bg-success">
													<th class="text-center">Kode Barang</th>
													<th class="text-center">Nama Barang</th>
													<th class="text-center">Stok Sistem</th>
													<th class="text-center">Stok Fisik</th>
													<th class="text-center">Aksi</th>
												</tr>
											</thead>
											
											<tbody>
											</tbody>
										</table>
										<br>
									</div>
									
									<button class="btn btn-lg btn-primary btn-block" id="btn_simpan_stokopname" type="submit"><i class="icon-floppy-disk position-left"></i> Simpan</button>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
<?php $this->load->view('admin/js'); ?>
<script>
	let loading = `
					<tr class="tr-loading">
						<td colspan="3" id="td-loading">
							<div class="loader">
								<img src="<?= base_url('assets/images/svg-loader/svg-loaders/vectorpaint.svg') ?>" alt="">
							</div>
						</td>
					</tr>
				`
	$(`#btn_simpan_stokopname`).prop('disabled', true);
	cari_barang();

	function tampil_modal_barang() {
		$(`#modal_tampil_barang`).modal('toggle');
	}

	function cari_barang() {
		let input_value_cari_barang = $(`#nama_barang`).val();

		$.ajax({
			url : '<?= base_url('farmasi/stok_opname/get_nama_barang_ajax') ?>',
			data : {'search_barang' : `${input_value_cari_barang}`},
			method : 'POST',
			dataType : 'json',
			beforeSend : () => {
				$(`#table_nama_barang tbody`).html(loading)
			},
			success : (res) => {
				let row = '';
				if(res.status) {
					let i = 0;
					for(const item of res.data) {
						row += `
							<tr onclick="select_barang(${item.id}, '${item.kode_barang}', '${item.nama_barang}', '${item.stok}')" style="cursor: pointer">
								<td class="text-center">${++i}</td>
								<td class="text-center">${item.kode_barang}</td>
								<td class="text-center">${item.nama_barang}</td>
							</tr>
						`
					}
				} else {
					row += `
						<tr><td colspan="3" class="text-center">${res.message}</td></tr>
					`
				}

				$(`#table_nama_barang tbody`).html(row);
				paging();
			},
			complete : () => {
				$(`#table_nama_barang tbody .tr-loading`).fadeOut();
			}
		})
	}

	function paging($selector){
    	var jumlah_tampil = '10';

        if(typeof $selector == 'undefined')
        {
            $selector = $("#table_nama_barang tbody tr");
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

	function select_barang(id_barang, kode_barang, nama_barang, stok_sistem) {
		let jumlah_row_barang = $(`.tr_barang_${id_barang}`).length
		let row = ''
		if(jumlah_row_barang == 0) {
			row += `
				<tr class="tr_barang_${id_barang}">
					<td class="text-center">${kode_barang}</td>
					<td class="text-center">${nama_barang}</td>
					<td class="text-center">${stok_sistem}</td>
					<td width="200" class="text-center">
						<input type="hidden" name="id_barang[]" value="${id_barang}">
						<input type="hidden" name="nama_barang[]" value="${nama_barang}">
						<input type="hidden" name="kode_barang[]" value="${kode_barang}">
						<input type="hidden" name="stok_sistem[]" value="${stok_sistem}">
						<input type="text" name="stok_fisik[]" class="form-control">
					</td>
					<td class="text-center">
						<button class="btn btn-danger" onclick="hapus_row(this)"><i class="icon-trash"></i></button>
					</td>
				</tr>
			`
		}

		$(`.table_stokopname tbody`).append(row);
		$(`#btn_simpan_stokopname`).prop('disabled', false);
		
	}

	function hapus_row(btn) {
		let row = btn.parentNode.parentNode;
 		row.parentNode.removeChild(row);
 		let jumlah_row_table_stokopname = $(`.table_stokopname tbody tr`).length
		if(jumlah_row_table_stokopname == 0) {
			$(`#btn_simpan_stokopname`).prop('disabled', true);
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
</body>
</html>
