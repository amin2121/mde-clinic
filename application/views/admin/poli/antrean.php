<div class="container-fluid">
	<div class="row">
		<div class="col-sm-12">
			<div class="panel panel-flat">
				<div class="panel-heading">
					<h6 class="panel-title"><?= $title ?></h6>
					<div class="heading-elements">
						<ul class="icons-list">
	                		<li><a data-action="collapse"></a></li>
	                		<li><a data-action="reload"></a></li>
	                	</ul>
	            	</div>
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

							<div class="table-responsive">
								<table class="table table-striped table-bordered table-hover">
									<thead>
										<tr class="bg-indigo">
											<th class="text-center">No. </th>
											<th class="text-center">No Antrean</th>
											<th class="text-center">Kode Pasien</th>
											<th class="text-center">Nama Pasien</th>
											<th class="text-center">Usia Pasien</th>
											<th class="text-center">Jenis Kelamin</th>
											<th class="text-center">Action</th>
										</tr>
									</thead>
									<tbody>
										<?php for($i = 1; $i <= 10; $i++) : ?>
											<tr onclick="action_tambah_rekam_medis({'no_antrean' : '<?= '00'. $i ?>', 'kode_pasien' : '0992', 'nama_pasien' : 'Amin Mutawakkil', 'usia_pasien' : '25', 'jenis_kelamin' : 'lk'})" style="cursor: pointer">
												<td class="text-center"><?= $i ?></td>
												<td class="text-center"><?= '00'. $i ?></td>
												<td class="text-center">0992</td>
												<td class="text-center">Amin Mutawakkil</td>
												<td class="text-center">25 Thn</td>
												<td class="text-center"><span class="label label-info">Laki - laki</span></td>
												<td class="text-center">
													<a href="#" class="btn btn-icon btn-md btn-danger"><i class="icon-bin"></i></a>
												</td>
											</tr>
										<?php endfor; ?>
									</tbody>
								</table>
							</div>

						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
