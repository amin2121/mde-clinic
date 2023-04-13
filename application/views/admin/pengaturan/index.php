<!DOCTYPE html>
<html lang="en">
<head>
	<?php $this->load->view('admin/css'); ?>
</head>

<body>

	<?php $this->load->view('admin/nav'); ?>
	<?php $this->load->view('admin/pengaturan/menu'); ?>

	<!-- Page container -->
	<div class="page-container">

		<!-- Page content -->
		<div class="page-content">

			<!-- Main content -->
			<div class="content-wrapper">

				<div class="content">

				<!-- Sidebars overview -->
				<div class="panel panel-flat border-top-success border-top-lg">
					<div class="panel-heading">
						<h5 class="panel-title">Selamat Datang di Menu Pengaturan,</h5>
					</div>

					<div class="panel-body">
						<p class="content-group"> Silahkan Klik menu disamping untuk memulai layanan Pengaturan</p>
						<div class="row">
						<?php
							$level = $this->session->userdata('level');
							$q_menu = $this->db->query("SELECT a.* FROM pengaturan_hak_akses a LEFT JOIN menu_2 b ON a.id_menu = b.id WHERE a.level = '$level' AND a.tipe_menu = 'Menu 2' AND b.id_menu_1 = '6'")->result_array();
							foreach ($q_menu as $p) {
								$id_menu = $p['id_menu'];
								$m = $this->db->query("SELECT a.* FROM menu_2 a WHERE a.id = '$id_menu'")->row_array();
								if ($m['link'] == '' || $m['link'] == NULL) {
						 ?>
						 <?php
						 $id_menu_2 = $m['id'];
						 $sub_menu = $this->db->query("SELECT a.* FROM menu_3 a WHERE a.id_menu_2 = '$id_menu_2'")->result_array();
						 foreach ($sub_menu as $s): ?>
							 <div class="col-sm-3">
								 <a href="<?= base_url() ?><?php echo $s['link']; ?>">
								 <div class="panel panel-bordered panel-success">
									 <div class="panel-body">

										 <h3 class="title-item text-center">
											 <i class="<?php echo $s['icon']; ?>" style="font-size: 200%"></i><br><?php echo $s['nama']; ?>
										 </h3>
									 </div>
								 </div>
							 </a>
							 </div>
						 <?php endforeach; ?>
						 <?php
					 			}else {
						  ?>
 							 <div class="col-sm-3">
								 <a href="<?= base_url() ?><?php echo $m['link']; ?>">
 								 <div class="panel panel-bordered panel-success">
 									 <div class="panel-body">

 										 <h3 class="title-item text-center">
 											 <i class="<?php echo $m['icon']; ?>" style="font-size: 200%"></i><br><?php echo $m['nama']; ?>
 										 </h3>
 									 </div>
 								 </div>
							 </a>
 							 </div>
						 <?php
					 			}
					 		}
						 ?>
						</div>
					</div>
				</div>
				<!-- /sidebars overview -->

			</div>
			</div>
			<!-- /main content -->

		</div>
		<!-- /page content -->

	</div>
	<!-- /page container -->

</body>
</html>
