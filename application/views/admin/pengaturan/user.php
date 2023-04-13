<!DOCTYPE html>
<html lang="en">
<head>
<?php $this->load->view('admin/css'); ?>
<script src="<?php echo base_url('assets/js/plugins/tables/datatables/datatables.min.js'); ?>"></script>
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
							<div class="tabbable">
								<ul class="nav nav-tabs nav-tabs-component">
									<li class="active"><a href="#user" data-toggle="tab">User</a></li>
									<li><a href="#tambah-user" data-toggle="tab">Tambah User</a></li>
								</ul>

								<div class="tab-content" style="margin-bottom: 2em !important;">
									<div class="tab-pane active" id="user">
										<div class="table-responsive">
											<table class="table table-bordered table-striped" id="table-pengaturan-user">
												<thead>
													<tr class="bg-success">
														<th class="text-center">No</th>
														<th class="text-center">Nama Pegawai</th>
														<th class="text-center">Username</th>
														<th class="text-center">Password</th>
														<th class="text-center">Level</th>
														<th class="text-center">Action</th>
													</tr>
												</thead>
												<tbody id="tbody-user-login">
													<?php foreach ($pengaturan_user as $key => $pu): ?>
														<tr>
			                    		<td><?= ++$key; ?></td>
			                    		<td><?= $pu['nama_pegawai'] ?></td>
			                    		<td><?= $pu['username'] ?></td>
			                    		<td><?= $pu['password'] ?></td>
			                    		<td><?= $pu['level'] ?></td>
			                    		<td>
	                    				<div class="text-center">
																<a href="" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modal-ubah-pengaturan-user-<?= $pu['id'] ?>"><i class="icon-pencil position-left"></i>Edit </a>
																<a href="" data-toggle="modal" data-target="#modal-hapus-pengaturan-user-<?= $pu['id'] ?>"class="btn btn-sm bg-danger legitRipple"><i class="icon-trash position-left"></i> Hapus</a>
			                    		</div>
			                    		<div id="modal-ubah-pengaturan-user-<?= $pu['id'] ?>" class="modal fade">
														<div class="modal-dialog modal-lg">
												    	<div class="modal-content">
													      <div class="modal-header bg-primary">
													        <button type="button" class="close" data-dismiss="modal">&times;</button>
													        <h6 class="modal-title">Ubah Pengaturan User</h6>
													    	</div>
																<form action="<?= base_url('pengaturan/user/ubah_user') ?>" method="POST">
															    <div class="modal-body">
															    	<input type="text" name="id" value="<?= $pu['id'] ?>" hidden="">
																	<div class="form-group">
																		<label class="control-label"><b>Username</b></label>
																		<input type="tel" class="form-control" name="username" value="<?= $pu['username'] ?>">
																	</div>

																	<div class="form-group">
																		<label class="control-label"><b>Password</b></label>
																		<input type="text" class="form-control" name="password" value="<?= $pu['password'] ?>">
																	</div>

																	<div class="form-group">
																		<label class="control-label"><b>Level</b></label>
																		<select class="select-search-warning" name="level">
																			<?php foreach ($level as $l): ?>
																				<option value="<?php echo $l['id']; ?>" <?= ($l['id'] == $pu['id_level']) ? 'selected' : '' ?>><?php echo $l['user_level']; ?></option>
																			<?php endforeach; ?>
																		</select>
																	</div>

															    </div>

															    <div class="modal-footer">
															      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
															      <button class="btn btn-warning" type="submit"><i class="icon-pencil"></i> Ubah</button>
															    </div>

															    </form>
														    </div>
															</div>
														</div>

														<!-- Modal Hapus barang -->
														<div id="modal-hapus-pengaturan-user-<?= $pu['id'] ?>" class="modal fade">
															<div class="modal-dialog">
																<div class="modal-content">
																	<div class="modal-header bg-danger">
																		<button type="button" class="close" data-dismiss="modal">&times;</button>
																		<h5 class="modal-title"><i class="icon-bin"></i> &nbsp;Hapus Pengaturan User</h5>
																	</div>
																	<div class="modal-body">
																		<div class="alert alert-danger no-border">
																			<p>Apakah Anda Ingin <span class="text-semibold">Menghapus</span> <?= $pu['nama_pegawai'] ?> ?</p>
																	    </div>
																	</div>
																	<div class="modal-footer">
																		<button class="btn btn-link" data-dismiss="modal"><i class="icon-cross"></i> Keluar</button>
																		<a href="<?= base_url('pengaturan/user/hapus_user?id_user='. $pu['id']) ?>" class="btn btn-danger"><i class="icon-bin"></i> Hapus</a>
																	</div>
																</div>
															</div>
														</div>
														<!-- /Modal Hapus barang -->
			                    		</td>
			                    	</tr>

													<?php endforeach ?>
												</tbody>
											</table>
										</div>
									</div>
									<div class="tab-pane" id="tambah-user">
										<form action="<?= base_url('pengaturan/user/tambah_user') ?>" id="form-tambah-user" method="POST">

											<div class="form-group">
	    									<label for=""  class="control-label"><b>Nama Pegawai</b></label>
	  										<div class="input-group">
	  											<input type="text" id="nama_pegawai" placeholder="Nama Pegawai" name="nama_pegawai" class="form-control" readonly>
													<input type="hidden" id="id_pegawai" name="id_pegawai" class="form-control" readonly>
	  											<span class="input-group-btn">
	  												<button class="btn bg-primary" onclick="modal_pegawai(); get_pegawai();" type="button"><i class="fa fa-search"></i> Cari</button>
	  											</span>
	  										</div>
	    								</div>

											<div class="form-group">
												<label class="control-label"><b>Username</b></label>
												<input type="tel" class="form-control" placeholder="Username" name="username">
											</div>

											<div class="form-group">
												<label class="control-label"><b>Password</b></label>
												<input type="text" class="form-control" placeholder="Password" name="password">
											</div>

											<div class="form-group">
												<label class="control-label"><b>Level</b></label>
												<select class="select-search" name="level">
													<?php foreach ($level as $l): ?>
														<option value="<?php echo $l['user_level']; ?>"><?php echo $l['user_level']; ?></option>
													<?php endforeach; ?>
												</select>
											</div>

											<button class="btn btn-success" type="submit" style="margin-top: 2em;"><i class="icon-floppy-disk position-left"></i> Simpan</button>
										</form>

										<button type="button" class="btn btn-primary btn-sm" id="btn_modal_pegawai" data-toggle="modal" data-target="#modal_pegawai" style="display:none;">Launch <i class="icon-play3 position-right"></i></button>
										<div id="modal_pegawai" class="modal fade">
										  <div class="modal-dialog modal-lg">
										    <div class="modal-content">
										      <div class="modal-header bg-primary">
										        <button type="button" class="close" data-dismiss="modal">&times;</button>
										        <h6 class="modal-title">Data Pasien</h6>
										      </div>

										      <div class="modal-body">
										        <div class="form-group">
										          <div class="input-group">
										            <input type="text" id="search_pegawai" placeholder="Cari Berdasarkan Nama Pegawai" class="form-control">
										            <span class="input-group-btn">
										              <button class="btn bg-primary" type="button"><i class="fa fa-search"></i></button>
										            </span>
										          </div>
										        </div>
										        <div class="table-responsive">
										          <table class="table table-bordered table-hover table-striped table_data_pegawai">
										            <thead>
										              <tr class="bg-primary">
										                <th class="text-center">No</th>
										                <th class="text-center">Nama</th>
										                <th class="text-center">Jabatan</th>
										              </tr>
										            </thead>
										            <tbody>

										            </tbody>
										          </table>
										        </div>
										      </div>

										      <div class="modal-footer">
										        <button type="button" class="btn btn-warning" id="tutup_data_pegawai" data-dismiss="modal">Close</button>
										      </div>
										    </div>
										  </div>
										</div>

									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>


<script>
	$(document).ready(function() {
		$(`#table-pengaturan-user`).DataTable()
	});

	function modal_pegawai(){
    	$('#btn_modal_pegawai').click();
  	}

  function get_pegawai(){
    var search = $('#search_pegawai').val();

	    $.ajax({
	        url : '<?php echo base_url(); ?>pengaturan/user/get_pegawai',
	        data : {search:search},
	        type : "POST",
	        dataType : "json",
	        success : function(result){
	            $tr = "";

	            if(result == "" || result == null){
	                $tr = "<tr><td colspan='4' style='text-align:center;'><b>Data tidak ditemukan</b></td></tr>";
	            }else{
	                var no = 0;
	                for(var i=0; i<result.length; i++){
	                    no++;

	                    $tr += '<tr style="cursor:pointer;" onclick="klik_pegawai(&quot;'+result[i].pegawai_id+'&quot;);">'+
																	'<td>'+no+'</td>'+
																	'<td>'+result[i].nama+'</td>'+
	                                '<td>'+result[i].jabatan_nama+'</td>'+
	                            '</tr>';
	                }
	            }

	            $('.table_data_pegawai tbody').html($tr);
	            // paging();
	        }
	    });

	    $('#search_pegawai').off('keyup').keyup(function(){
	        get_pegawai();
	    });
  }

	function klik_pegawai(id){
		$('#tutup_data_pegawai').click();

		    $.ajax({
		        url : '<?php echo base_url(); ?>pengaturan/user/klik_pegawai',
		        data : {id:id},
		        type : "POST",
		        dataType : "json",
		        success : function(row){
		          $('#id_pegawai').val(id);
		          $('#nama_pegawai').val(row['nama']);
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
