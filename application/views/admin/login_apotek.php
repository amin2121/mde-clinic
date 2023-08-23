<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Aplikasi Apotek</title>

	<!-- Global stylesheets -->
	<link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
	<link href="<?php echo base_url(); ?>assets/css/icons/icomoon/styles.css" rel="stylesheet" type="text/css">
	<link href="<?php echo base_url(); ?>assets/css/bootstrap.css" rel="stylesheet" type="text/css">
	<link href="<?php echo base_url(); ?>assets/css/core.css" rel="stylesheet" type="text/css">
	<link href="<?php echo base_url(); ?>assets/css/components.css" rel="stylesheet" type="text/css">
	<link href="<?php echo base_url(); ?>assets/css/colors.css" rel="stylesheet" type="text/css">
	<!-- /global stylesheets -->

	<!-- Core JS files -->
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/plugins/loaders/pace.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/core/libraries/jquery.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/core/libraries/bootstrap.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/plugins/loaders/blockui.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/plugins/ui/nicescroll.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/plugins/ui/drilldown.js"></script>
	<!-- /core JS files -->

	<!-- SELECT JS -->
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/plugins/forms/selects/select2.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/pages/form_select2.js"></script>

	<!-- Theme JS files -->
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/core/app.js"></script>

	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/plugins/ui/ripple.min.js"></script>
	<!-- /theme JS files -->

</head>

<body class="login-container" style="background-image: url('<?= base_url('assets-portal/images/visi_misi.jpeg') ?>'); background-size: cover; background-attachment: fixed; background-position: 50% 50%;">
	<!-- Page container -->
	<div class="page-container">

		<!-- Page content -->
		<div class="page-content">

			<!-- Main content -->
			<div class="content-wrapper">

				<!-- Simple login form -->

				<div class="panel panel-body login-form" style="margin-top: 5%;">
					<div class="text-center">
						<div class="icon-object border-slate-300 text-slate-300"><i class="icon-reading"></i></div>
						<h5 class="content-group">Login Aplikasi Apotek</h5>
					</div>
					<!-- tab table  -->
					<div class="tabbable">

						<ul class="nav nav-tabs nav-tabs-highlight nav-justified">
							<li class="active"><a href="#owner" data-toggle="tab">owner</a></li>
							<li><a href="#spv" data-toggle="tab">Spv</a></li>
							<li><a href="#kasir" data-toggle="tab">kasir</a></li>
						</ul>
						<!-- tab owner -->
						<div class="tab-content">
							<div class="tab-pane active" id="owner">
								<form action="<?php echo base_url(); ?>auth/masuk_apotek" method="post">
									<div class="form-group has-feedback has-feedback-left">
										<input type="text" class="form-control" name="username" placeholder="Username">
										<input type="hidden" class="form-control" name="shift" value="Owner" >
										<div class="form-control-feedback">
											<i class="icon-user text-muted"></i>
										</div>
									</div>
									<div class="form-group has-feedback has-feedback-left">
										<div class="input-group">
											<input type="password" class="form-control" name="password" placeholder="Password" id="password">
											<div class="form-control-feedback">
												<i class="icon-lock2 text-muted"></i>
											</div>
											<span class="input-group-btn">
												<button class="btn btn-default" type="button" onclick="show_password()" id="button_show_password"><span class="icon-eye-blocked"></span></button>
											</span>
										</div>
									</div>

									<div class="form-group">
										<button type="submit" class="btn bg-success-400 btn-block">Submit <i class="icon-circle-right2 position-right"></i></button>
									</div>
								</form>
							</div>
							<!-- end tab owner -->
							<!-- tab kasir -->
							<div class="tab-pane" id="kasir">
								<form action="<?php echo base_url(); ?>auth/masuk_apotek" method="post">

									<div class="form-group has-feedback has-feedback-left">
										<input type="text" class="form-control" name="username" placeholder="Masukan">
										<div class="form-control-feedback">
											<i class="icon-user text-muted"></i>
										</div>
									</div>
									<div class="form-group has-feedback has-feedback-left">
										<div class="input-group">
											<input type="password" class="form-control" name="password" placeholder="Password" id="password_kasir">
											<div class="form-control-feedback">
												<i class="icon-lock2 text-muted"></i>
											</div>
											<span class="input-group-btn">
												<button class="btn btn-default" type="button" onclick="show_password_kasir()" id="button_show_password"><span class="icon-eye-blocked"></span></button>
											</span>
										</div>
									</div>

									<div class="form-group">
										<select data-placeholder="Pilih shift..." name="shift" class="select" tabindex="-1" aria-hidden="true">
											<!-- <option value=""></option> -->
											<option value="Shift Pagi">Shift Pagi</option>
											<option value="Shift Malam">Shift Malam</option>
										</select>
									</div>
									<div class="form-group">
										<button type="submit" class="btn bg-success-400 btn-block">Submit <i class="icon-circle-right2 position-right"></i></button>
									</div>
								</form>
							</div>
							<!-- end tab kasir -->
							<!-- tab SPV -->
							<div class="tab-pane" id="spv">
								<form action="<?php echo base_url(); ?>auth/masuk_apotek" method="post">
								<input type="hidden" class="form-control" name="shift" value="Spv Admin" >
									<div class="form-group has-feedback has-feedback-left">
										<input type="text" class="form-control" name="username" placeholder=" Masukan Username">
										<div class="form-control-feedback">
											<i class="icon-user text-muted"></i>
										</div>
									</div>
									<div class="form-group has-feedback has-feedback-left">
										<div class="input-group">
											<input type="password" class="form-control" name="password" placeholder="Password" id="password_kasir">
											<div class="form-control-feedback">
												<i class="icon-lock2 text-muted"></i>
											</div>
											<span class="input-group-btn">
												<button class="btn btn-default" type="button" onclick="show_password_spv()" id="button_show_password"><span class="icon-eye-blocked"></span></button>
											</span>
										</div>
									</div>
									<div class="form-group">
										<button type="submit" class="btn bg-success-400 btn-block">Submit <i class="icon-circle-right2 position-right"></i></button>
									</div>
								</form>
							</div>
							<!-- end tab spv -->
						</div>
					</div>
					<!-- tab table end -->
				</div>

				<!-- /simple login form -->

			</div>
			<!-- /main content -->

		</div>
		<!-- /page content -->

	</div>
	<!-- /page container -->

	<script>
		function show_password() {
			if ($(`#password`).attr('type') == "password") {
				$(`#password`).prop('type', 'text');
				$(`#button_show_password span`).addClass('icon-eye');
				$(`#button_show_password span`).removeClass('icon-eye-blocked');
			} else {
				$(`#password`).prop('type', 'password');
				$(`#button_show_password span`).addClass('icon-eye-blocked');
				$(`#button_show_password span`).removeClass('icon-eye');
			}
		}
		function show_password_kasir() {
			if ($(`#password_kasir`).attr('type') == "password") {
				$(`#password_kasir`).prop('type', 'text');
				$(`#button_show_password span`).addClass('icon-eye');
				$(`#button_show_password span`).removeClass('icon-eye-blocked');
			} else {
				$(`#password_kasir`).prop('type', 'password');
				$(`#button_show_password span`).addClass('icon-eye-blocked');
				$(`#button_show_password span`).removeClass('icon-eye');
			}
		}
		function show_password_spv() {
			if ($(`#password_kasir`).attr('type') == "password") {
				$(`#password_kasir`).prop('type', 'text');
				$(`#button_show_password span`).addClass('icon-eye');
				$(`#button_show_password span`).removeClass('icon-eye-blocked');
			} else {
				$(`#password_kasir`).prop('type', 'password');
				$(`#button_show_password span`).addClass('icon-eye-blocked');
				$(`#button_show_password span`).removeClass('icon-eye');
			}
		}
	</script>
	<!-- Footer -->
	<div class="footer text-muted text-center">
		&copy; 2020. Aplikasi Apotek by Ababil Soft
	</div>
	<!-- /footer -->

</body>

</html>