<!-- Second navbar -->
	<div class="navbar navbar-default" id="navbar-second">
		<ul class="nav navbar-nav no-border visible-xs-block">
			<li><a class="text-center collapsed" data-toggle="collapse" data-target="#navbar-second-toggle"><i class="icon-menu7"></i></a></li>
		</ul>

    <div class="navbar-collapse collapse" id="navbar-second-toggle">
  		<ul class="nav navbar-nav navbar-nav-material">
					<li><a href="<?= base_url() ?>laporan/laporan_home"><i class="icon-display4 position-left"></i> Home</a></li>					
					<?php
						$level = $this->session->userdata('level');
						$q_menu = $this->db->query("SELECT a.* FROM pengaturan_hak_akses a LEFT JOIN menu_2 b ON a.id_menu = b.id WHERE a.level = '$level' AND a.tipe_menu = 'Menu 2' AND b.id_menu_1 = '8'")->result_array();
						foreach ($q_menu as $p) {
							$id_menu = $p['id_menu'];
							$m = $this->db->query("SELECT a.* FROM menu_2 a WHERE a.id = '$id_menu'")->row_array();
						?>
						<?php if ($m['link'] == '' || $m['link'] == NULL) {
							?>
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown">
									<i class="<?php echo $m['icon']; ?> position-left"></i> <?php echo $m['nama']; ?><span class="caret"></span>
								</a>

								<ul class="dropdown-menu dropdown-menu-left">
									<?php
									$id_menu_2 = $m['id'];
									$sub_menu = $this->db->query("SELECT a.* FROM menu_3 a WHERE a.id_menu_2 = '$id_menu_2'")->result_array();
									foreach ($sub_menu as $s): ?>
										<li><a href="<?php echo base_url() ?><?php echo $s['link']; ?>"><i class="<?php echo $s['icon']; ?>"></i> <?php echo $s['nama']; ?></a></li>
									<?php endforeach; ?>
								</ul>
							</li>
						<?php
							}else {
						?>
						<li><a href="<?php echo base_url() ?><?php echo $m['link']; ?>"><i class="<?php echo $m['icon']; ?> position-left"></i> <?php echo $m['nama']; ?></a></li>
					<?php
							}
						}
					 ?>
  		</ul>
  	</div>
	</div>
	<!-- /second navbar -->
