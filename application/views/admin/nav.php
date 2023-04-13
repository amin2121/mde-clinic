<!-- Main navbar -->
	<div class="navbar navbar-inverse bg-success">
		<div class="navbar-header">
			<a class="navbar-brand" href="#"><img src="<?php echo base_url(); ?>assets/images/logo_light.png" alt=""></a>

			<ul class="nav navbar-nav pull-right visible-xs-block">
				<li><a data-toggle="collapse" data-target="#navbar-mobile"><i class="icon-tree5"></i></a></li>
			</ul>
		</div>

		<div class="navbar-collapse collapse" id="navbar-mobile">
			<ul class="nav navbar-nav">
				<li>
					<a class="sidebar-control sidebar-main-toggle hidden-xs" data-popup="tooltip" title="Collapse main" data-placement="bottom" data-container="body">
						<i class="icon-paragraph-justify3"></i>
					</a>
				</li>
			</ul>

			<p class="navbar-text"><span class="label bg-success-400"><?php echo $this->session->userdata('nama_cabang'); ?></span></p>

			<ul class="nav navbar-nav navbar-right">
				<?php 
					$id_cabang = $this->session->userdata('id_cabang');
					$produk_apotek_kadaluarsa = $this->db->query("
						SELECT * FROM apotek_barang
						WHERE id_cabang = $id_cabang
						AND tanggal_kadaluarsa IS NOT NULL
						AND tanggal_kadaluarsa <> ''
					")->result_array();

					$data_barang_kadaluarsa = [];
					foreach ($produk_apotek_kadaluarsa as $key => $produk) {
						$tanggal_sekarang = strtotime(date('d-m-Y'));
						$tanggal_kadaluarsa = strtotime($produk['tanggal_kadaluarsa']);
						$times = $tanggal_kadaluarsa - $tanggal_sekarang;
						$jumlah_hari = round($times / (60 * 60 * 24));

						if(count($data_barang_kadaluarsa) < 5) {
							if($jumlah_hari <= 90) {
								$data_barang_kadaluarsa[] = $produk;
							}
						}
					}
				?>
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">
						<i class="icon-calendar2"></i>
						<span class="visible-xs-inline-block position-right">Produk Expired</span>
						<span class="badge bg-warning-400"><?= count($data_barang_kadaluarsa) ?></span>
					</a>
					
					<div class="dropdown-menu dropdown-content width-350">
						<div class="dropdown-content-heading">
							Produk Expired
							<ul class="icons-list">
								<li><a href="#"><i class="icon-calendar"></i></a></li>
							</ul>
						</div>

						<ul class="media-list dropdown-content-body">
							<?php if (count($data_barang_kadaluarsa) == 0): ?>
								<li class="media">
									<div class="media-body text-center">
										Barang Kosong
									</div>
								</li>
							<?php else: ?>
								<?php foreach ($data_barang_kadaluarsa as $key => $barang): ?>
									<li class="media">
										<div class="media-body">
											<a href="javascript:void(0)" class="media-heading">
												<span class="text-semibold"><?= $barang['nama_barang'] ?></span>
												<span class="media-annotation pull-right"><?= $barang['tanggal_kadaluarsa'] ?></span>
											</a>
										</div>
									</li>
								<?php endforeach ?>
							<?php endif ?>
						</ul>

						<div class="dropdown-content-footer">
							<a href="#" data-popup="tooltip" title="All messages"><i class="icon-menu display-block"></i></a>
						</div>
					</div>
				</li>

				<?php 
					$id_cabang = $this->session->userdata('id_cabang');
					$produk_apotek = $this->db->query("
						SELECT 
							a.*,
							b.stok_minimal
						FROM apotek_barang a
						LEFT JOIN farmasi_barang b ON a.id_barang = b.id
						WHERE id_cabang = $id_cabang
					")->result_array();

					$data_barang_menipis = [];
					foreach ($produk_apotek as $key => $produk) {
						$stok_minimal = $produk['stok_minimal'] == null ? 0 : $produk['stok_minimal'];
						$stok_asli = $produk['stok'];

						if($stok_asli <= $stok_minimal) {
							$data_barang_menipis[] = $produk;
						}
					}
				?>

				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">
						<i class="icon-basket"></i>
						<span class="visible-xs-inline-block position-right">Produk Akan Habis</span>
						<span class="badge bg-warning-400"><?= count($data_barang_menipis) ?></span>
					</a>
					
					<div class="dropdown-menu dropdown-content width-350">
						<div class="dropdown-content-heading">
							Produk Akan Habis
							<ul class="icons-list">
								<li><a href="#"><i class="icon-basket"></i></a></li>
							</ul>
						</div>

						<ul class="media-list dropdown-content-body">
							<?php if (count($data_barang_menipis) == 0): ?>
								<li class="media">
									<div class="media-body text-center">
										Barang Kosong
									</div>
								</li>
							<?php else: ?>
								<?php foreach ($data_barang_menipis as $key => $barang): ?>
									<li class="media">
										<div class="media-body">
											<a href="javascript:void(0)" class="media-heading">
												<span class="text-semibold"><?= $barang['nama_barang'] ?></span>
												<span class="media-annotation pull-right"><?= $barang['stok'] ?></span>
											</a>
										</div>
									</li>
								<?php endforeach ?>

							<?php endif ?>
						</ul>

						<div class="dropdown-content-footer">
							<a href="#" data-popup="tooltip" title="All messages"><i class="icon-menu display-block"></i></a>
						</div>
					</div>
				</li>

				<li class="dropdown dropdown-user">
					<a class="dropdown-toggle" data-toggle="dropdown">
						<img src="assets/images/placeholder.jpg" alt="">
						<span><?php echo $this->session->userdata('nama_user'); ?></span>
						<i class="caret"></i>
					</a>

					<ul class="dropdown-menu dropdown-menu-right">
						<li><a href="<?php echo base_url(); ?>portal"><i class="icon-grid position-left"></i> Portal</a></li>
					</ul>
				</li>
			</ul>
		</div>
	</div>
	<!-- /main navbar -->
