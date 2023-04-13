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

								<div class="form-search form-horizontal">
									<div class="row">
										<div class="col-sm-6">
											<div class="form-group">
												<label class="control-label col-sm-2"><b>Tanggal Dari</b></label>
												<div class="col-sm-9">
													<input type="text" class="form-control input-tgl" autocomplete="off" name="nama_barang" id="tanggal_dari" placeholder="Dari Tanggal">
												</div>
											</div>
										</div>
										<div class="col-sm-6">
											<div class="form-group">
												<label class="control-label col-sm-3"><b>Tanggal Sampai</b></label>
												<div class="col-sm-9">
													<input type="text" class="form-control input-tgl" autocomplete="off" name="nama_barang" id="tanggal_sampai" placeholder="Sampai Tanggal">
												</div>
											</div>
										</div>
									</div>
									<div class="row" style="margin-top: 2em;">
										<div class="col-sm-12">
											<div class="form-group">
												<label class="control-label col-sm-1"><b>Nama Pegawai</b></label>
												<div class="col-sm-11">
													<select name="pegawai" id="pegawai" class="select-search-primary">
														<option value="Semua" selected>Semua</option>
														<?php foreach ($pegawai as $p): ?>
															<option value="<?= $p['pegawai_id'] ?>"><?= $p['nama'] ?></option>
														<?php endforeach ?>
													</select>
												</div>
											</div>
										</div>
									</div>
									<div class="row" style="margin-top: 1em;">
										<div class="col-sm-2">
											<button class="btn btn-primary" onclick="get_riwayat_stok_opname()"><i class="fa fa-search position-left"></i> Cari</button>
										</div>
									</div>

								</div>
								<div class="table-responsive">
									<table class="table table-bordered table-striped" id="table_riwayat_stok_opname">
										<thead>
											<tr class="bg-success">
												<th class="text-center">No.</th>
												<th class="text-center">Nama Kasir</th>
												<th class="text-center">Barang</th>
												<th class="text-center">Kode Barang</th>
												<th class="text-center">Stok Fisik</th>
												<th class="text-center">Stok Sistem</th>
												<th class="text-center">Selisih</th>
												<th class="text-center">Tanggal & Waktu</th>
											</tr>
										</thead>
										<tbody>
											
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
$(window).load(function() {
	$('.input-tgl').datepicker({
        dateFormat : 'dd-mm-yy',
        autoclose: true,
        language: 'fr',
        orientation: 'bottom auto',
        todayBtn: 'linked',
        todayHighlight: true
    });

    get_riwayat_stok_opname();
})

function get_riwayat_stok_opname() {
	let input_tanggal_dari = $(`#tanggal_dari`).val();
	let input_tanggal_sampai = $(`#tanggal_sampai`).val();
	let input_pegawai = $(`#pegawai`).val();
	
	$.ajax({
		url : '<?= base_url('farmasi/riwayat_stok_opname/get_riwayat_stok_opname') ?>',
		method : 'POST',
		data : {
			'tanggal_dari' 		: `${input_tanggal_dari}`,
			'tanggal_sampai'	: `${input_tanggal_sampai}`,
			'pegawai'			: `${input_pegawai}`,
		},
		dataType : 'json',
		beforeSend : () => {
			let loading = `<tr id="tr-loading">
								<td colspan="8">
									<div class="loader">
										<img src="<?= base_url('assets/images/svg-loader/svg-loaders/vectorpaint.svg') ?>" alt="">
									</div>
								</td>
							</tr>`

			$(`#table_riwayat_stok_opname tbody`).html(loading);
		},
		success : (res) => {
			let row = '';

			if(res.status) {
				let i = 0;
				for(const item of res.data) {
					row += 	`
						<tr>
							<td class="text-center">${++i}</td>
							<td class="text-center">${item.nama_kasir}</td>
							<td class="text-center">${item.nama_barang}</td>
							<td class="text-center">${item.kode_barang}</td>
							<td class="text-center">${item.stok_sistem}</td>
							<td class="text-center">${item.stok_fisik}</td>
							<td class="text-center">${item.selisih}</td>
							<td class="text-center">${item.tanggal} ${item.waktu}</td>
						</tr>
					`;
				}
			} else {
				row += `
					<tr>
						<td colspan="8" class="text-center">${res.message}</td>
					</tr>
				`
			}
			$(`#table_riwayat_stok_opname tbody`).html(row);
			pagination()
		},
		complete : () => {$(`#tr-loading`).hide()}
	})	
}

function pagination($selector){
	var jumlah_tampil = '10';

    if(typeof $selector == 'undefined'){
        $selector = $("#table_riwayat_stok_opname tbody tr");
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