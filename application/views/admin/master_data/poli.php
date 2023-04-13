<!DOCTYPE html>
<html lang="en">
<head>
	<?php $this->load->view('admin/css'); ?>
	<script src="<?php echo base_url('assets/js/plugins/tables/datatables/datatables.min.js'); ?>"></script>
	<script type="text/javascript">
		$(function() {
			$('.poli').DataTable();
			$('.search').DataTable();

		});

		function btn_cari_perawat(){
			$('#popup_perawat').click();
		}

		function btn_cari_dokter() {
			$(`#modal_show_dokter`).modal('toggle');
		}

		function deleteRow(btn){
		  	var row = btn.parentNode.parentNode;
		  	row.parentNode.removeChild(row);
		}

		function klik_perawat(id){
			$('#tutup_perawat').click();

			$.ajax({
				url : '<?php echo base_url(); ?>master/Poli/klik_perawat/',
				data : {id:id},
				type : "POST",
				dataType : "json",
				success : function(result){
					$tr = "";
						for(var i=0; i<result.length; i++){
							var jumlah_data = $('#tr_'+result[i].pegawai_id).length;

							var aksi = "<button type='button' class='btn waves-light btn-danger btn-sm' onclick='deleteRow(this);'><i class='icon-trash'></i></button>";

							if(jumlah_data > 0){

							}else{
								$tr = "<tr id='tr_"+result[i].pegawai_id+"'>"+
										"<input type='hidden' name='perawat_id[]' value='"+result[i].pegawai_id+"'>"+
										"<td style='vertical-align:middle;'>"+result[i].pegawai_id+"</td>"+
										"<td style='vertical-align:middle;'>"+result[i].nama+"</td>"+
										"<td style='vertical-align:middle;'>"+result[i].jabatan_nama+"</td>"+
										"<td align='center'>"+aksi+"</td>"+
									  "</tr>";
							}
						}

						$('#tb_perawat tbody').append($tr);

				}
			});
		}

		function klik_dokter(id) {
			$(`#modal_show_dokter`).modal('toggle');

			$.ajax({
				url : '<?php echo base_url(); ?>master/Poli/klik_dokter/',
				data : {id:id},
				type : "POST",
				dataType : "json",
				success : function(result){
					$tr = "";
						for(var i=0; i<result.length; i++){
							var jumlah_data = $('#tr_'+result[i].pegawai_id).length;

							var aksi = "<button type='button' class='btn waves-light btn-danger btn-sm' onclick='deleteRow(this);'><i class='icon-trash'></i></button>";

							if(jumlah_data > 0){

							}else{
								$tr = "<tr id='tr_"+result[i].pegawai_id+"'>"+
										"<input type='hidden' name='dokter_id[]' value='"+result[i].pegawai_id+"'>"+
										"<td style='vertical-align:middle;'>"+result[i].pegawai_id+"</td>"+
										"<td style='vertical-align:middle;'>"+result[i].nama+"</td>"+
										"<td style='vertical-align:middle;'>"+result[i].jabatan_nama+"</td>"+
										"<td align='center'>"+aksi+"</td>"+
									  "</tr>";
							}
						}

						$('#tb_dokter tbody').append($tr);

				}
			});
		}
	</script>
</head>

<body>

	<?php $this->load->view('admin/nav'); ?>
	<?php $this->load->view('admin/master_data/menu'); ?>


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
						<h5 class="panel-title">Data Poli</h5>
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
									<li class="active"><a href="#basic-justified-tab1" data-toggle="tab" class="legitRipple" aria-expanded="true">Data Poli</a></li>
									<li class=""><a href="#basic-justified-tab2" data-toggle="tab" class="legitRipple" aria-expanded="false">Tambah Poli</a></li>
								</ul>

								<div class="tab-content">
									<div class="tab-pane active" id="basic-justified-tab1">
										<table class="table poli">
											<thead>
												<tr>
													<th>Kode</th>
													<th>Nama Poli</th>
													<th>Aksi</th>
												</tr>
											</thead>
											<tbody>
												<?php foreach ($poli as $d): ?>
													<tr>
														<td><?php echo $d->poli_id ?></td>
														<td><?php echo $d->poli_nama ?></td>
														<td>
															<a href="<?php echo base_url('master/Poli/editpoli/'.$d->poli_id) ?>" class="btn btn-primary btn-sm"><i class="icon-pencil position-left"></i>Edit </a>
															<a data-toggle="modal" data-target="#modal_hapus_poli_<?php echo $d->poli_id; ?>" class="btn btn-sm btn-danger"><i class="icon-trash position-left"></i> Hapus</a>
															<!-- Modal Hapus Tarif -->
															<div id="modal_hapus_poli_<?php echo $d->poli_id; ?>" class="modal fade">
																<div class="modal-dialog">
																	<div class="modal-content">
																		<div class="modal-header bg-danger">
																			<button type="button" class="close" data-dismiss="modal">&times;</button>
																			<h5 class="modal-title">Hapus Poli</h5>
																		</div>
																		<form action="<?php echo base_url('master/Poli/deletePoli/'.$d->poli_id) ?>" method="GET">
																		<div class="modal-body">
																			<div class="alert alert-danger no-border">
																				<p>Apakah Anda Ingin <span class="text-semibold">Menghapus</span> Poli Ini ?</p>
																		    </div>
																		</div>
																		<div class="modal-footer">
																			<button class="btn btn-link" data-dismiss="modal"><i class="icon-cross position-left"></i> Keluar</button>
																			<button type="submit" class="btn btn-danger"><i class="icon-bin position-left"></i> Hapus</button>
																		</div>
																		</form>
																	</div>
																</div>
															</div>
															<!-- /Modal Hapus Tarif -->
														</td>
													</tr>
												<?php endforeach ?>
											</tbody>
										</table>
									</div>

									<div class="tab-pane" id="basic-justified-tab2">
										<form method="post" action="<?php echo base_url('master/Poli/insertPoli/');?>">
											<div class="form-group">
												<label for="" class="control-label"><b>Nama Poli</b></label>
												<input required name="poli_nama" placeholder="Nama Poli" type="text" class="form-control">
											</div>
											<div class="form-group">
												<label class="control-label"><b>Cabang</b></label>
												<select name="id_cabang" class="bootstrap-select" data-width="100%">
													<?php foreach ($cabang as $c): ?>
														<option value="<?php echo $c['id'] ?>"><?php echo $c['nama']; ?></option>
													<?php endforeach ?>
												</select>
											</div>
											<div class="form-group">
												<label for=""><b>Dokter Penanggung Jawab</b></label><br><br>
													<button type="button" class="btn btn-primary m-b-5" onclick="btn_cari_dokter();"> <i class="fa fa-search position-left"></i> Cari</button>
							            			<br><br>
								            		<div class="table-responsive">
										                <table class="table table-bordered table-hover" id="tb_dokter">
										                    <thead>
										                        <tr class="bg-primary">
										                            <th>No</th>
										                            <th>Nama Dokter</th>
										                            <th>Jabatan</th>
										                            <th>#</th>
										                        </tr>
										                    </thead>
										                    <tbody>

										                    </tbody>
										                </table>
									            	</div>
											</div>
											<div class="form-group">
								            	<label class="control-label"><b>Perawat</b></label><br><br>
							            			<button type="button" class="btn btn-primary m-b-5" onclick="btn_cari_perawat();"> <i class="fa fa-search position-left"></i> Cari</button>
							            			<br><br>
								            		<div class="table-responsive">
										                <table class="table table-bordered table-hover" id="tb_perawat">
										                    <thead>
										                        <tr class="bg-primary">
										                            <th>No</th>
										                            <th>Nama Perawat</th>
										                            <th>Jabatan</th>
										                            <th>#</th>
										                        </tr>
										                    </thead>
										                    <tbody>

										                    </tbody>
										                </table>
									            	</div>
								            </div>
											<div class="form-group">
												<button type="submit" class="btn btn-success"><i class="icon-floppy-disk position-left"></i> Simpan</button>
											</div>
										</form>
									</div>
								</div>
							</div>

					</div>
				</div>
				<!-- /sidebars overview -->
				<!--MODAL-->
				<button id="popup_perawat" class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#myModal5" style="display:none;">Standard Modal</button>
				<div id="myModal5" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				    <div class="modal-dialog" style="width:55%;">
				    	<div class="modal-content">
				            <div class="modal-header bg-primary">
				                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				                <h4 class="modal-title" id="myModalLabel">Data Perawat</h4>
				            </div>
				        	<div class="modal-body">
				            	<div class="table-responsive">
				            		<div class="scroll-y">
						                <table class="table table-bordered table-hover search" id="tabel_perawat">
						                    <thead>
						                        <tr class="merah_popup">
						                            <th>No</th>
						                            <th>Kode</th>
						                            <th>Nama Perawat</th>
						                            <th>Jabatan</th>
						                        </tr>
						                    </thead>
						                    <tbody>
						                        <?php $no=1; foreach ($perawat as $p): ?>
						                        	<tr style="cursor:pointer;" onclick="klik_perawat('<?php echo $p->pegawai_id; ?>');">
						                        		<td><?php echo $no++; ?></td>
						                        		<td><?php echo $p->pegawai_id; ?></td>
						                        		<td><?php echo $p->nama; ?></td>
						                        		<td><?php echo $p->jabatan_nama; ?></td>
						                        	</tr>
						                        <?php endforeach ?>
						                    </tbody>
						                </table>
				            		</div>
				            	</div>
				        	</div>
				            <div class="modal-footer">
				                <button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal" id="tutup_perawat"><i class="icon-cross position-left"></i> Keluar</button>
				            </div>
				    	</div><!-- /.modal-content -->
				    </div><!-- /.modal-dialog -->
				</div><!-- /.modal -->

				<div id="modal_show_dokter" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				    <div class="modal-dialog" style="width:55%;">
				    	<div class="modal-content">
				            <div class="modal-header bg-primary">
				                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				                <h4 class="modal-title" id="myModalLabel">Data Dokter</h4>
				            </div>
				        	<div class="modal-body">
				            	<div class="table-responsive">
				            		<div class="scroll-y">
						                <table class="table table-bordered table-hover search" id="tabel_perawat">
						                    <thead>
						                        <tr class="merah_popup">
						                            <th>No</th>
						                            <th>Kode</th>
						                            <th>Nama Dokter</th>
						                            <th>Jabatan</th>
						                        </tr>
						                    </thead>
						                    <tbody>
						                        <?php $no=1; foreach ($dokter as $p): ?>
						                        	<tr style="cursor:pointer;" onclick="klik_dokter('<?php echo $p->pegawai_id; ?>');">
						                        		<td><?php echo $no++; ?></td>
						                        		<td><?php echo $p->pegawai_id; ?></td>
						                        		<td><?php echo $p->nama; ?></td>
						                        		<td><?php echo $p->jabatan_nama; ?></td>
						                        	</tr>
						                        <?php endforeach ?>
						                    </tbody>
						                </table>
				            		</div>
				            	</div>
				        	</div>
				            <div class="modal-footer">
				                <button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal" id="tutup_perawat"><i class="icon-cross position-left"></i> Keluar</button>
				            </div>
				    	</div><!-- /.modal-content -->
				    </div><!-- /.modal-dialog -->
				</div><!-- /.modal -->

			</div>
			<!-- /main content -->

		</div>
		</div>
		<!-- /page content -->

	</div>
	<!-- /page container -->

</body>
</html>
