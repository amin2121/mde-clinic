	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Aplikasi Klinik</title>

	<!-- Global stylesheets -->
	<link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
	<link href="<?= base_url('assets/css/icons/icomoon/styles.css') ?>" rel="stylesheet" type="text/css">
	<link rel="stylesheet" href="<?= base_url('assets/css/icons/fontawesome/styles.min.css') ?>">
	<link href="<?= base_url('assets/css/bootstrap.css') ?>" rel="stylesheet" type="text/css">
	<link href="<?= base_url('assets/css/core.css') ?>" rel="stylesheet" type="text/css">
	<link href="<?= base_url('assets/css/components.css') ?>" rel="stylesheet" type="text/css">
	<link href="<?= base_url('assets/css/colors.css') ?>" rel="stylesheet" type="text/css">
	<link rel="stylesheet" href="<?= base_url('assets/css/custom.css') ?>" type="text/css">
	<link rel="stylesheet" href="<?= base_url('assets/js/plugins/uploaders/dropify/dist/css/dropify.css') ?>" type="text/css">
	<!-- /global stylesheets -->

	<!-- Core JS files -->
	<script type="text/javascript" src="<?= base_url('assets/js/core/libraries/jquery.min.js') ?>"></script>
	<script type="text/javascript" src="<?= base_url('assets/js/plugins/loaders/pace.min.js') ?>"></script>
	<script type="text/javascript" src="<?= base_url('assets/js/core/libraries/bootstrap.min.js') ?>"></script>
	<script type="text/javascript" src="<?= base_url('assets/js/plugins/loaders/blockui.min.js') ?>"></script>
	<script type="text/javascript" src="<?= base_url('assets/js/plugins/forms/selects/select2.min.js') ?>"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/plugins/forms/selects/bootstrap_select.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/pages/form_bootstrap_select.js"></script>
	<!-- /core JS files -->

	<!-- Theme JS files -->
	<!-- <script src="assets/js/app.js"></script>
	<script src="../../../../global_assets/js/demo_pages/uploader_dropzone.js"></script> -->
	<!-- /theme JS files -->

	<!-- /theme JS files -->
	<script type="text/javascript">
	let convertRupiah = (angka) => {
		let angkaToString = angka.toString();
		let sisa 	= angkaToString.length % 3;
		let	rupiah 	= angkaToString.substr(0, sisa);
		let	ribuan 	= angkaToString.substr(sisa).match(/\d{3}/g);

		if (ribuan) {
			let separator = sisa ? '.' : '';
			rupiah += separator + ribuan.join('.');
		}

		return rupiah
	}
	</script>
	<style media="screen">
		#popup_load {
		  width: 100%;
		  height: 100%;
		  position: fixed;
		  background: #fff;
		  z-index: 9999;
		  opacity:0.8;
		  filter:alpha(opacity=80); /* For IE8 and earlier */
		  top: 0;
		  left: 0;
		  display: none;
		}
		.window_load {
		  width:60%;
		  height:auto;
		  border-radius: 10px;
		  position: relative;
		  padding: 10px;
		  text-align: center;
		  margin-top: 20%;
		  margin-left: 20%;
		}
		</style>
