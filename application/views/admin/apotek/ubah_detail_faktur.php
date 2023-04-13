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
<?php $this->load->view('admin/apotek/menu'); ?>

<!-- Page container -->
<div class="page-container">

<!-- Page content -->
<div class="page-content">

<!-- Main content -->
<div class="content-wrapper">
<div class="content">

			<div class="panel panel-warning">
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

							<form action="<?= base_url('apotek/faktur/ubah_detail_faktur?id_faktur='. $this->input->get('id_faktur').'&id_detail_faktur='.$this->input->get('id_detail_faktur')) ?>" method="POST" class="form-horizontal" id="form-ubah-detail-faktur">
								<input type="text" id="id-barang-tambah-detail-faktur" hidden="" name="id_barang" value="<?= $detail_faktur['id_barang'] ?>">
								<!-- nama barang -->
								<div class="form-group">
									<label class="control-label col-lg-2" for="search-nama-barang-tambah-faktur"><b>Nama Barang</b></label>
									<div class="col-lg-10">
										<input type="text" class="form-control" name="nama_barang" id="nama-barang-ubah-detail-faktur" value="<?= $detail_faktur['nama_barang'] ?>">
									</div>
								</div>
								<!-- nama barang -->

								<!-- kode barang -->
								<div class="form-group">
									<label class="control-label col-sm-2"><b>Kode Barang</b></label>
									<div class="col-sm-10">
										<input type="text" class="form-control" id="kode-barang-ubah-detail-faktur" name="kode_barang" value="<?= $detail_faktur['kode_barang'] ?>">
									</div>
								</div>
								<!-- kode barang -->

								<!-- Harga Awal -->
								<div class="form-group">
									<label for="harga-awal-detail-faktur-tambah" class="control-label col-sm-2"><b>Harga Awal</b></label>
									<div class="input-group">
										<span class="input-group-addon"><b>Rp. </b></span>
										<input type="text" class="form-control rupiah" id="harga-awal-ubah-detail-faktur" name="harga_awal" onchange="hitung_laba_by_harga_awal(this)" value="<?= $detail_faktur['harga_awal'] ?>">
									</div>
								</div>
								<!-- Harga Awal -->

								<!-- Harga Jual -->
								<div class="form-group">
									<label for="harga-jual-detail-faktur-tambah" class="control-label col-sm-2"><b>Harga Jual</b></label>
									<div class="input-group">
										<span class="input-group-addon"><b>Rp. </b></span>
										<input type="text" class="form-control rupiah" id="harga-jual-ubah-detail-faktur" name="harga_jual" onchange="hitung_laba_by_harga_jual(this)" value="<?= $detail_faktur['harga_jual'] ?>">
									</div>
								</div>
								<!-- Harga Jual -->

								<!-- Laba -->
								<div class="form-group">
									<label for="laba-detail-faktur-tambah" class="control-label col-sm-2"><b>Laba</b></label>
									<div class="input-group">
										<span class="input-group-addon"><b>Rp. </b></span>
										<input type="text" class="form-control rupiah" id="laba-ubah-detail-faktur" name="laba" readonly="" value="<?= $detail_faktur['laba'] ?>">
									</div>
								</div>
								<!-- Laba -->

								<!-- tanggal-kadaluarsa -->
								<div class="form-group">
									<div class="col-md-12">
										<label class="radio-inline">
											<input type="radio" name="radio-tgl-kadalursa" id="radio-tgl-kadalursa-ada" class="styled control-success" checked="checked" onchange="set_tanggal_kadaluarsa(this)">
											<b>Ada Kadaluarsa</b>
										</label>

										<label class="radio-inline">
											<input type="radio" name="radio-tgl-kadalursa" id="radio-tgl-kadalursa-tidak" class="styled control-success" onchange="set_tanggal_kadaluarsa(this)">
											<b>Tidak Ada Kadaluarsa</b>
										</label>
									</div>
								</div>

								<div class="form-group" style="margin-bottom: 5em;" id="form-tgl-kadaluarsa-ubah-detail-faktur">
									<label class="control-label col-sm-2"><b>Tanggal Kadaluarsa</b></label>
									<div class="col-sm-10">
										<div class="input-group">
											<span class="input-group-addon"><i class="icon-calendar"></i></span>
											<input type="text" class="form-control datepicker" placeholder="Pick a date&hellip;" name="tanggal_kadaluarsa" id="input-tgl-kadaluarsa-ubah-detail-faktur" value="<?= ($detail_faktur['tanggal_kadaluarsa'] == '') ? '' : date('d-m-Y', strtotime($detail_faktur['tanggal_kadaluarsa'])) ?>">
											<input type="text" value="<?= ($detail_faktur['tanggal_kadaluarsa'] == '') ? '' : date('d-m-Y', strtotime($detail_faktur['tanggal_kadaluarsa'])) ?>" id="default-tgl-kadaluarsa-ubah-detail-faktur" hidden>
										</div>
									</div>
								</div>
								<!-- tanggal-kadaluarsa -->
								<div class="row">
									<div class="col-sm-10">
										<a href="<?= base_url('apotek/faktur/detail_faktur?id_faktur='. $this->input->get('id_faktur')) ?>" class="btn btn-default" style="margin-right: 1em !important;"><i class="icon-arrow-left7"></i> Kembali</a>
										<button class="btn btn-warning" type="submit" onclick="on_submit_ubah_detail_faktur(this)"><i class="icon-pencil"></i> Ubah</button>
									</div>
								</div>
							</form>

						</div>
					</div>
				</div>
			</div>

<script>

	let hitung_laba_by_harga_awal = (e) => {
		let inputHargaJual = $(`#harga-jual-ubah-detail-faktur`)
		let inputHargaAwal = $(`#harga-awal-ubah-detail-faktur`)
		if (inputHargaJual.val() !== 0 || inputHargaJual.val() !== "") {
			let unMaskInputHargaAwal = $(`#harga-awal-ubah-detail-faktur`).unmask();
			let unMaskInputHargaJual = $(`#harga-jual-ubah-detail-faktur`).unmask();
			let unMaskInputLaba = $(`#laba-ubah-detail-faktur`).unmask();

			let hargaAwal = $(`#harga-awal-ubah-detail-faktur`).val();
			let hargaJual = $(`#harga-jual-ubah-detail-faktur`).val();
			let laba = $(`#laba-ubah-detail-faktur`).val();

			let valueLaba = parseInt(hargaJual) - parseInt(hargaAwal);

			$(`#laba-ubah-detail-faktur`).val(valueLaba);
		}
	}

	let hitung_laba_by_harga_jual = (e) => {
		let unMaskInputHargaAwal = $(`#harga-awal-ubah-detail-faktur`).unmask();
		let unMaskInputHargaJual = $(`#harga-jual-ubah-detail-faktur`).unmask();
		let unMaskInputLaba = $(`#laba-ubah-detail-faktur`).unmask();

		let hargaAwal = $(`#harga-awal-ubah-detail-faktur`).val();
		let hargaJual = $(`#harga-jual-ubah-detail-faktur`).val();
		let laba = $(`#laba-ubah-detail-faktur`).val();

		let valueLaba = parseInt(hargaJual) - parseInt(hargaAwal);

		$(`#laba-ubah-detail-faktur`).val(valueLaba);

	}

	let on_submit_ubah_detail_faktur = (e) => {
		$(`.rupiah`).unmask();

		$(`#form-ubah-detail-faktur`).submit()
	}

	let set_tanggal_kadaluarsa = (e) => {
		let form_tgl_kadaluarsa = $(`#form-tgl-kadaluarsa-ubah-detail-faktur`)
		let input_tgl_kadaluarsa = $(`#input-tgl-kadaluarsa-ubah-detail-faktur`)
		let value_of_tgl_kadaluarsa = input_tgl_kadaluarsa.val();
		let value_default_tgl_kadaluarsa = $(`#default-tgl-kadaluarsa-ubah-detail-faktur`).val()

		if(e.id == "radio-tgl-kadalursa-ada") {
			input_tgl_kadaluarsa.val(value_default_tgl_kadaluarsa)
			form_tgl_kadaluarsa.show()
			console.log(value_of_tgl_kadaluarsa);
		} else {
			input_tgl_kadaluarsa.val('');
			form_tgl_kadaluarsa.hide();
			console.log(value_of_tgl_kadaluarsa);
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
