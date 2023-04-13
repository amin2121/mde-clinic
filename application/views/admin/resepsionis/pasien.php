<!DOCTYPE html>
<html lang="en">
<head>
	<?php $this->load->view('admin/css'); ?>
	<script type="text/javascript" src="<?php echo base_url('assets/js/plugins/pickers/pickadate/picker.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo base_url('assets/js/plugins/pickers/pickadate/picker.date.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo base_url('assets/js/plugins/pickers/pickadate/picker.time.js'); ?>"></script>
	<!-- <script type="text/javascript" src="<?php echo base_url('assets/js/plugins/pickers/pickadate/legacy.js'); ?>"></script> -->
	<script type="text/javascript">

		$('.tanggal_lahir').pickadate({
	      format: 'dd-mm-yyyy',
	      selectMonths: true,
	      selectYears: 80,
	      max: true
	    });

		$(document).ready(function() {	
			pasien_result();
		})

		function pasien_result(){
			$('.loader').show();
      		var search = $('#cari_pasien').val();

      		$.ajax({
		      	url : '<?php echo base_url(); ?>resepsionis/pasien/pasien_result',
		        data : {search:search},
		      	type : "POST",
		      	dataType : 	"json",
		      	success : function(result){
      				$table = "";

		      		if (result == "" || result == null) {
		      			$table = '<tr>'+
		                        '<td colspan="7" style="text-align:center;">Data Kosong</td>'+
		                     '</tr>';
		      		}else {
		      			var no = 0;
            			for(var i=0; i<result.length; i++){
            				no++;

		      				$table += '<tr>'+
		                          '<td style="text-align:left;">'+result[i].no_rm+'</td>'+
															'<td style="text-align:left;">'+result[i].nama_pasien+'</td>'+
															'<td style="text-align:left;">'+result[i].no_ktp+'</td>'+
															'<td style="text-align:left;">'+result[i].tanggal_lahir+'</td>'+
		                          '<td style="text-align:left;">'+result[i].jenis_kelamin+'</td>'+
															'<td style="text-align:left;"><i class="fa fa-user"></i> '+result[i].username+'<br><i class="fa fa-key"></i> '+result[i].password+'</td>'+
		                          '<td style="text-align:center;">'+
		                            '<a href="<?php echo base_url(); ?>resepsionis/pasien/view_edit/'+result[i].id+'"><button type="button" class="btn btn-info btn-sm" name="button"><i class="icon-pencil position-left"></i> Edit</button></a>&nbsp;'+
		                            '<button onclick="hapus_pasien('+result[i].id+')" type="button" class="btn btn-danger btn-sm"  name="button"><i class="icon-trash position-left"></i> Hapus</button>'+
		                          '</td>'+
		                        '</tr>';

      					}
      				}
      				
      				$('#table_pasien tbody').html($table);
					$('.loader').hide();
          			paging();
      			}
      		});

      		$('#cari_pasien').off('keyup').keyup(function(){
          		pasien_result();
    		});
    	}

		function hapus_pasien(id){
			$('#klik_hapus_pasien').click();
			$('#id_hapus_pasien').val(id);
    	}

		function paging($selector){
    		var jumlah_tampil = '10';

	        if(typeof $selector == 'undefined')
	        {
	            $selector = $("#table_pasien tbody tr");
	        }

	        window.tp = new Pagination('#pagination', {
	            itemsCount:$selector.length,
	            pageSize : parseInt(jumlah_tampil),
	            onPageSizeChange: function (ps) {
	                console.log('changed to ' + ps);
	            },
	            onPageChange: function (paging) {
	                //custom paging logic here
	                //console.log(paging);
	                var start = paging.pageSize * (paging.currentPage - 1),
	                    end = start + paging.pageSize,
	                    $rows = $selector;

	                $rows.hide();

	                for (var i = start; i < end; i++) {
	                    $rows.eq(i).show();
	                }
	            }
	        });
   		}

		function hitung_umur(){
		    // Here are the two dates to compare
		    var tgl = $('#tanggal_lahir').val(); //10-07-2018
		    var d = tgl.substr(0,2);
		    var m = tgl.substr(3,2);
		    var y = tgl.substr(6);

		    // console.log(tgl)
		    var date2 = "<?php echo date('Y-m-d'); ?>";
		    // var date1 = y+'-'+m+'-'+d;
		    var date1 = tgl;

		    // First we split the values to arrays date1[0] is the year, [1] the month and [2] the day
		    date1 = date1.split('-');
		    date2 = date2.split('-');

		    // Now we convert the array to a Date object, which has several helpful methods
		    date1 = new Date(date1[0], date1[1], date1[2]);
		    date2 = new Date(date2[0], date2[1], date2[2]);

		    // We use the getTime() method and get the unixtime (in milliseconds, but we want seconds, therefore we divide it through 1000)
		    date1_unixtime = parseInt(date1.getTime() / 1000);
		    date2_unixtime = parseInt(date2.getTime() / 1000);

		    // This is the calculated difference in seconds
		    var timeDifference = date2_unixtime - date1_unixtime;

		    // in Hours
		    var timeDifferenceInHours = timeDifference / 60 / 60;

		    // and finaly, in days :)
		    var timeDifferenceInDays = timeDifferenceInHours  / 24;

		    //in month
		    var timeDifferenceInMonth = (date2.getMonth() - date1.getMonth());

		    //in year
		    var timeDifferenceInYear = (date2.getFullYear() - date1.getFullYear());

		    if(parseInt(timeDifferenceInMonth) < 0){
		        timeDifferenceInMonth = '0';
		    }

		    $('#umur').val(timeDifferenceInYear);
		}

		function hitung_umur_edit(){
		    // Here are the two dates to compare
		    var tgl = $('#tanggal_lahir_edit').val(); //10-07-2018
		    var d = tgl.substr(0,2);
		    var m = tgl.substr(3,2);
		    var y = tgl.substr(6);
		    var date2 = "<?php echo date('Y-m-d'); ?>";
		    var date1 = y+'-'+m+'-'+d;

		    // First we split the values to arrays date1[0] is the year, [1] the month and [2] the day
		    date1 = date1.split('-');
		    date2 = date2.split('-');

		    // Now we convert the array to a Date object, which has several helpful methods
		    date1 = new Date(date1[0], date1[1], date1[2]);
		    date2 = new Date(date2[0], date2[1], date2[2]);

		    // We use the getTime() method and get the unixtime (in milliseconds, but we want seconds, therefore we divide it through 1000)
		    date1_unixtime = parseInt(date1.getTime() / 1000);
		    date2_unixtime = parseInt(date2.getTime() / 1000);

		    // This is the calculated difference in seconds
		    var timeDifference = date2_unixtime - date1_unixtime;

		    // in Hours
		    var timeDifferenceInHours = timeDifference / 60 / 60;

		    // and finaly, in days :)
		    var timeDifferenceInDays = timeDifferenceInHours  / 24;

		    //in month
		    var timeDifferenceInMonth = (date2.getMonth() - date1.getMonth());

		    //in year
		    var timeDifferenceInYear = (date2.getFullYear() - date1.getFullYear());

		    if(parseInt(timeDifferenceInMonth) < 0){
		        timeDifferenceInMonth = '0';
		    }

		    $('#umur_edit').val(timeDifferenceInYear);
		}


	</script>
</head>

<body>

	<?php $this->load->view('admin/nav'); ?>
	<?php $this->load->view('admin/resepsionis/menu'); ?>

	<!-- Page container -->
	<div class="page-container">

		<!-- Page content -->
		<div class="page-content">

			<!-- Main content -->
			<div class="content-wrapper">
				<!-- Sidebars overview -->
				<div class="content">
					<div class="panel panel-flat border-top-success border-top-lg">
						<div class="panel-heading">
							<h5 class="panel-title">Data Pasien</h5>
						</div>

						<div class="panel-body">
							<?php echo $this->session->flashdata('success');?>
							<div class="tabbable">
								<ul class="nav nav-tabs">
									<li class="active"><a href="#basic-justified-tab1" data-toggle="tab" class="legitRipple" aria-expanded="true">Data Pasien</a></li>
									<li class=""><a href="#basic-justified-tab2" data-toggle="tab" class="legitRipple" aria-expanded="false">Tambah Pasien Baru</a></li>
								</ul>

								<div class="tab-content">
									<div class="tab-pane active" id="basic-justified-tab1">
										<div class="form-group">
											<div class="input-group">
												<input type="text" id="cari_pasien" class="form-control" placeholder="Cari Berdasarkan Nama Pasien atau No RM">
												<span class="input-group-btn">
													<button class="btn bg-primary" type="button"><i class="fa fa-search"></i></button>
												</span>
											</div>
										</div>
										<div class="table-responsive">
											<table class="table table-bordered table-striped table-hover" id="table_pasien">
												<thead>
													<tr class="bg-success">
														<th>No RM</th>
														<th>Pasien</th>
														<th>NIK</th>
														<th>Tgl Lahir</th>
														<th>Kelamin</th>
														<th>Username & Password</th>
														<th>Aksi</th>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td colspan="7">
															<div class="loader">
															<img src="<?= base_url('assets/images/svg-loader/svg-loaders/vectorpaint.svg') ?>" alt="">
														</div>
														</td>
													</tr>
												</tbody>
											</table>
										</div>
										<br>
										<ul id="pagination" class="pagination float-right">

										</ul>
									</div>

									<div class="tab-pane" id="basic-justified-tab2">
										<form action="<?php echo base_url('resepsionis/Pasien/insertPasien') ?>" method="post">
											<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<label class="control-label"><b>No RM</b></label>
													<input required name="kode_pasien" type="text" readonly value="<?php echo $kode_pasien; ?>" class="form-control">
												</div>
												<div class="form-group">
													<label class="control-label"><b>Nama Pasien</b></label>
													<input required name="nama_pasien" type="text" placeholder="Nama Pasien" class="form-control">
												</div>
												<div class="form-group">
													<label class="control-label"><b>NIK</b></label>
													<input required name="no_ktp" type="text" placeholder="NIK" class="form-control">
												</div>
												<div class="form-group">
													<label class="control-label"><b>Tanggal Lahir</b></label>
													<input type="date" class="form-control tanggal_lahir" name="tanggal_lahir" placeholder="Tanggal Lahir" id="tanggal_lahir" onchange="hitung_umur();">
												</div>
												<div class="form-group">
													<label class="control-label"><b>Umur</b></label>
													<input required name="umur" readonly type="text" id="umur" placeholder="Umur" class="form-control">
												</div>
												<div class="form-group">
													<label class="control-label"><b>Pekerjaan</b></label>
													<input required name="pekerjaan" type="text" placeholder="Pekerjaan" class="form-control">
												</div>
												<div class="form-group">
													<label class="control-label"><b>Nomor Telepon</b></label>
													<input required name="no_telp" type="text" placeholder="Nomor Telepon" class="form-control">
												</div>
												<div class="form-group">
													<label class="control-label"><b>Nama Wali</b></label>
													<input required name="nama_wali" type="text" placeholder="Nama Wali" class="form-control">
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<label class="control-label"><b>Jenis Kelamin</b></label>
													<select name="jenis_kelamin" class="bootstrap-select" data-width="100%">
														<?php foreach ($jk as $s): ?>
															<option value="<?php echo $s ?>"><?php echo $s; ?></option>
														<?php endforeach ?>
													</select>
												</div>
												<div class="form-group">
													<label class="control-label"><b>Status Pernikahan</b></label>
													<select name="status_perkawinan" class="bootstrap-select" data-width="100%">
														<?php foreach ($status as $s): ?>
															<option value="<?php echo $s ?>"><?php echo $s; ?></option>
														<?php endforeach ?>
													</select>
												</div>
												<div class="form-group">
													<label class="control-label"><b>Golongan Darah</b></label>
													<select name="golongan_darah" class="bootstrap-select" data-width="100%">
														<?php foreach ($gol_darah as $s): ?>
															<option value="<?php echo $s ?>"><?php echo $s; ?></option>
														<?php endforeach ?>
													</select>
												</div>
												<div class="form-group">
													<label class="control-label"><b>Alergi</b></label>
													<select name="alergi" class="bootstrap-select" data-width="100%">
														<option value="iya">Iya</option>
														<option value="tidak">Tidak</option>
													</select>
												</div>
												<div class="form-group">
													<label class="control-label"><b>Operasi</b></label>
													<select name="status_operasi" class="bootstrap-select" data-width="100%">
														<option value="iya">Iya</option>
														<option value="tidak">Tidak</option>
													</select>
												</div>
												<div class="form-group">
													<label class="control-label"><b>Alamat</b></label>
													<textarea class="form-control" name="alamat" placeholder="Alamat" cols="30" rows="2"></textarea>
												</div>
											</div>
											<div class="col-md-12">
												<div class="form-group">
													<button type="submit" class="btn btn-success"><i class="icon-floppy-disk position-left"></i> Simpan</button>
												</div>
											</div>
										</div>
										</form>
									</div>
								</div>
							</div>

							<!-- Modal Hapus Pemasukan -->
							<a data-toggle="modal" style="display:none;" id="klik_hapus_pasien" data-target="#modal_hapus_pasien" class="btn btn-sm btn-danger"><i class="icon-trash position-left"></i> Hapus</a>
							<div id="modal_hapus_pasien" class="modal fade">
								<div class="modal-dialog">
									<div class="modal-content">
										<div class="modal-header bg-danger">
											<button type="button" class="close" data-dismiss="modal">&times;</button>
											<h5 class="modal-title">Hapus Pegawai</h5>
										</div>
										<form action="<?php echo base_url('resepsionis/pasien/deletePasien') ?>" method="POST">
										<input type="hidden" name="id" id="id_hapus_pasien">
										<div class="modal-body">
											<div class="alert alert-danger no-border">
												<p>Apakah Anda Ingin <span class="text-semibold">Menghapus</span> Pasien Ini ?</p>
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
							<!-- /Modal Hapus Pemasukan -->

						</div>
					</div>
				</div>

				<!-- /sidebars overview -->
			</div>
			<!-- /main content -->

		</div>
		<!-- /page content -->

	</div>
	<!-- /page container -->
<?php $this->load->view('admin/js'); ?>
</body>
</html>
