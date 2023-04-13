<!DOCTYPE html>
<html lang="en">
<head>
	<?php $this->load->view('admin/css'); ?>
	<script src="<?php echo base_url('assets/js/plugins/tables/datatables/datatables.min.js'); ?>"></script>
	<script type="text/javascript">
		$(function() {
			$('.poli').DataTable();
			$('.search').DataTable();
			$('.btn_cari_perawat').click(function(){
				$('#popup_perawat').click();
    		});

			get_data_pegawai_perawat();
			get_data_pegawai_dokter();
		});

		function get_data_pegawai_perawat() {
			$.ajax({
		  		url : '<?= base_url('master/poli/data_pegawai_perawat') ?>',
		  		data : {'id' : '<?= $this->uri->segment(4) ?>'},
		  		method : 'GET',
		  		dataType : 'json',
		  		success : function(res) {
					let row = '';
					if(res.status) {
						let no = 0;
						for(const item of res.data) {
							row += `
								<tr onclick="klik_perawat('${item.pegawai_id}')" style="cursor:pointer">
	                        		<td>${++no}</td>
	                        		<td>${item.pegawai_id}</td>
	                        		<td>${item.nama}</td>
	                        		<td>${item.jabatan_nama}</td>
	                        	</tr>
							`
						}
					}

					$('#tabel_perawat tbody').html(row);
		  		}
		  	})
		}

		function get_data_pegawai_dokter() {
			$.ajax({
		  		url : '<?= base_url('master/poli/data_pegawai_dokter') ?>',
		  		data : {'id' : '<?= $this->uri->segment(4) ?>'},
		  		method : 'GET',
		  		dataType : 'json',
		  		success : function(res) {
					let row = '';
					if(res.status) {
						let no = 0;
						for(const item of res.data) {
							row += `
								<tr onclick="klik_dokter('${item.pegawai_id}')" style="cursor:pointer">
	                        		<td>${++no}</td>
	                        		<td>${item.pegawai_id}</td>
	                        		<td>${item.nama}</td>
	                        		<td>${item.jabatan_nama}</td>
	                        	</tr>
							`
						}
					}

					$('#table_dokter tbody').html(row);
		  		}
		  	})
		}

		function deleteRowPerawat(btn, id_perawat) {
		  	$.ajax({
		  		url : '<?= base_url('master/poli/delete_poli_perawat') ?>',
		  		data : {'id_perawat' : id_perawat, 'id_poli' : '<?= $this->uri->segment(4) ?>'},
		  		method : 'GET',
		  		dataType : 'json',
		  		success : function(res) {
					alert(res.message)
				  	var row = btn.parentNode.parentNode;
				  	row.parentNode.removeChild(row);
				  	get_data_pegawai_perawat();
		  		}
		  	})
		}

		function deleteRowDokter(btn, id_dokter) {
			$.ajax({
		  		url : '<?= base_url('master/poli/delete_poli_dokter') ?>',
		  		data : {'id_dokter' : id_dokter, 'id_poli' : '<?= $this->uri->segment(4) ?>'},
		  		method : 'GET',
		  		dataType : 'json',
		  		success : function(res) {
					alert(res.message)
				  	var row = btn.parentNode.parentNode;
				  	row.parentNode.removeChild(row);
				  	get_data_pegawai_dokter();
		  		}
		  	})
		}

		function klik_perawat(id){
			$('#myModal5').modal('toggle');

			$.ajax({
				url : '<?php echo base_url(); ?>master/Poli/klik_perawat/',
				data : {id:id},
				type : "POST",
				dataType : "json",
				success : function(result){
					$tr = "";
						for(var i=0; i<result.length; i++){
							var jumlah_data = $('#tr_'+result[i].pegawai_id).length;

							var aksi = `<button type='button' class='btn waves-light btn-danger btn-sm' onclick='deleteRowPerawat(this, "${result[i].pegawai_id}");'><i class='icon-trash'></i></button>`;

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

		function klik_dokter(id){
			$(`#data_show_dokter`).modal('toggle')

			$.ajax({
				url : '<?php echo base_url(); ?>master/Poli/klik_dokter/',
				data : {id:id},
				type : "POST",
				dataType : "json",
				success : function(result){
					$tr = "";
						for(var i=0; i<result.length; i++){
							var jumlah_data = $('#tr_'+result[i].pegawai_id).length;

							var aksi = `<button type='button' class='btn waves-light btn-danger btn-sm' onclick='deleteRowDokter(this, "${result[i].pegawai_id}");'><i class='icon-trash'></i></button>`;

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
						<h5 class="panel-title">Edit Poli</h5>
					</div>

					<div class="panel-body">
						<?php echo $this->session->flashdata('success');?>
						<div class="tabbable">

								<div class="tab-content">
									<div class="tab-pane active" id="basic-justified-tab2">
										<form method="post" action="<?php echo base_url('master/Poli/updatePoli/'. $this->uri->segment(4));?>">
									<div class="form-group">
										<label for="" class="control-label"><b>Nama Poli</b></label>
										<input required value="<?= $poli['poli_nama'] ?>" name="poli_nama" type="text" class="form-control">
									</div>
									<div class="form-group">
										<label class="control-label"><b>Cabang</b></label>
										<select name="id_cabang" class="bootstrap-select" data-width="100%">
											<?php foreach ($cabang as $c): ?>
												<option value="<?php echo $c['id'] ?>" <?php if ($c['id'] == $poli['id_cabang']): ?> selected <?php endif; ?>><?php echo $c['nama']; ?></option>
											<?php endforeach ?>
										</select>
									</div>
									<div class="form-group">
										<label for=""><b>Dokter Penanggung Jawab</b></label><br><br>
										<button type="button" class="btn btn-primary m-b-5" data-toggle="modal" data-target="#data_show_dokter"><i class="fa fa-search position-left"></i> Cari</button>
										<div id="data_show_dokter" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
										    <div class="modal-dialog modal-lg">
										    	<div class="modal-content">
										            <div class="modal-header bg-primary">
										                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
										                <h4 class="modal-title" id="myModalLabel">Data Dokter</h4>
										            </div>
										        	<div class="modal-body">
										            	<div class="table-responsive">
										            		<div class="scroll-y">
												                <table class="table table-bordered table-hover search" id="table_dokter">
												                    <thead>
												                        <tr class="merah_popup">
												                            <th>No</th>
												                            <th>Kode</th>
												                            <th>Nama Perawat</th>
												                            <th>Jabatan</th>
												                        </tr>
												                    </thead>
												                    <tbody>
												                    </tbody>
												                </table>
										            		</div>
										            	</div>
										        	</div>
										            <div class="modal-footer">
										                <button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal">Tutup</button>
										            </div>
										    	</div><!-- /.modal-content -->
										    </div><!-- /.modal-dialog -->
										</div><!-- /.modal -->
					            <br><br>
											<div class="table-responsive">
				                <table class="table table-bordered table-hover perawat" id="tb_dokter">
				                    <thead>
				                        <tr class="bg-primary">
				                            <th>No</th>
				                            <th>Nama Dokter</th>
				                            <th>Jabatan</th>
				                            <th>#</th>
				                        </tr>
				                    </thead>
				                    <tbody>
				                    	<?php foreach ($data_poli_dokter as $d): ?>
				                    		<tr>
				                    			<td><?= $d['id_dokter'] ?></td>
				                    			<td><?= $d['nama_dokter'] ?></td>
				                    			<td><?= $d['jabatan'] ?></td>
				                    			<td align="center">
				                        			<button type='button' class='btn waves-light btn-danger btn-sm' onclick='deleteRowDokter(this, "<?= $d['id_dokter'] ?>");'><i class='icon-trash'></i></button>
				                        		</td>
				                    		</tr>
				                    	<?php endforeach ?>
				                    </tbody>
				                </table>
				            </div>
									</div>
									<div class="form-group">
		            	<label class="control-label"><b>Perawat</b></label><br><br>
	            			<button type="button" class="btn btn-primary m-b-5 btn_cari_perawat"><i class="fa fa-search position-left"></i> Cari</button>
	            			<br><br>
		            		<div class="table-responsive">
			                <table class="table table-bordered table-hover perawat" id="tb_perawat">
			                    <thead>
			                        <tr class="bg-primary">
			                            <th>No</th>
			                            <th>Nama Perawat</th>
			                            <th>Jabatan</th>
			                            <th>#</th>
			                        </tr>
			                    </thead>
			                    <tbody>
			                        <?php foreach ($data_poli_perawat as $p): ?>
			                        	<tr id="tr_<?php echo $p['id_perawat']; ?>">
			                        		<input type='hidden' name='perawat_id[]' value='<?php echo $p['id_perawat']; ?>'>
			                        		<td><?php echo $p['id_perawat']; ?></td>
			                        		<td><?php echo $p['nama_perawat']; ?></td>
			                        		<td><?php echo $p['jabatan']; ?></td>
			                        		<td align="center">
			                        			<button type='button' class='btn waves-light btn-danger btn-sm' onclick='deleteRowPerawat(this, "<?= $p['id_perawat'] ?>");'><i class='icon-trash'></i></button>
			                        		</td>
			                        	</tr>
			                        <?php endforeach ?>
			                    </tbody>
			                </table>
			            	</div>
			            </div>
									<div class="form-group">
										<button type="submit" class="btn btn-success"><i class="icon-floppy-disk position-left"></i> Simpan</button>
										<a href="<?php echo base_url("master/Poli") ?>" class="btn btn-danger"><i class="icon-arrow-left7 position-left"></i> Kembali</a>
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
				    <div class="modal-dialog modal-lg">
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
						                    </tbody>
						                </table>
				            		</div>
				            	</div>
				        	</div>
				            <div class="modal-footer">
				                <button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal" id="tutup_perawat">Tutup</button>
				            </div>
				    	</div><!-- /.modal-content -->
				    </div><!-- /.modal-dialog -->
				</div><!-- /.modal -->

			</div>
			</div>
			<!-- /main content -->

		</div>
		<!-- /page content -->

	</div>
	<!-- /page container -->

</body>
</html>
