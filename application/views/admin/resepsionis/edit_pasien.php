<!DOCTYPE html>
<html lang="en">
<head>
	<?php $this->load->view('admin/css'); ?>
  <script type="text/javascript" src="<?php echo base_url('assets/js/plugins/pickers/pickadate/picker.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo base_url('assets/js/plugins/pickers/pickadate/picker.date.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo base_url('assets/js/plugins/pickers/pickadate/picker.time.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo base_url('assets/js/plugins/pickers/pickadate/legacy.js'); ?>"></script>
	<script type="text/javascript">
		$(function() {
			$('.tanggal_lahir').pickadate({
		      format: 'dd-mm-yyyy',
		      selectMonths: true,
		      selectYears: 60,
		      max: true
		    });
		});

		function hitung_umur(){
		    // Here are the two dates to compare
		    var tgl = $('#tanggal_lahir').val(); //10-07-2018
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
							<div class="row">
                <form action="<?php echo base_url() ?>resepsionis/Pasien/updatePasien/<?php echo $row['id']; ?>" method="post">

                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="control-label"><b>No RM</b></label>
                      <input required name="no_rm" type="text" readonly value="<?php echo $row['no_rm']; ?>" class="form-control">
                    </div>
                    <div class="form-group">
                      <label class="control-label"><b>Nama Pasien</b></label>
                      <input required name="nama_pasien" type="text" value="<?php echo $row['nama_pasien']; ?>" class="form-control">
                    </div>
                    <div class="form-group">
                      <label class="control-label"><b>NIK</b></label>
                      <input required name="no_ktp" type="text" value="<?php echo $row['no_ktp']; ?>" class="form-control">
                    </div>
                    <div class="form-group">
                      <label class="control-label"><b>Tanggal Lahir</b></label>
                      <input type="text" class="form-control tanggal_lahir" name="tanggal_lahir" value="<?php echo $row['tanggal_lahir']; ?>" id="tanggal_lahir" onchange="hitung_umur();">
                    </div>
                    <div class="form-group">
                      <label class="control-label"><b>Umur</b></label>
                      <input required name="umur" readonly type="text" id="umur" value="<?php echo $row['umur']; ?>" class="form-control">
                    </div>
                    <div class="form-group">
                      <label class="control-label"><b>Pekerjaan</b></label>
                      <input required name="pekerjaan" type="text" value="<?php echo $row['pekerjaan']; ?>" class="form-control">
                    </div>
                    <div class="form-group">
                      <label class="control-label"><b>Nomor Telepon</b></label>
                      <input required name="no_telp" type="text" value="<?php echo $row['no_telp']; ?>" class="form-control">
                    </div>
                    <div class="form-group">
                      <label class="control-label"><b>Nama Wali</b></label>
                      <input required name="nama_wali" type="text" value="<?php echo $row['nama_wali']; ?>" class="form-control">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="control-label"><b>Jenis Kelamin</b></label>
                      <select name="jenis_kelamin" class="bootstrap-select" data-width="100%">
                        <?php foreach ($jk as $s): ?>
                          <option value="<?php echo $s ?>" <?php if ($s == $row['jenis_kelamin']): ?> selected <?php endif; ?>><?php echo $s; ?></option>
                        <?php endforeach ?>
                      </select>
                    </div>
                    <div class="form-group">
                      <label class="control-label"><b>Status Pernikahan</b></label>
                      <select name="status_perkawinan" class="bootstrap-select" data-width="100%">
                        <?php foreach ($status as $s): ?>
                          <option value="<?php echo $s ?>" <?php if ($s == $row['status_perkawinan']): ?> selected <?php endif; ?>><?php echo $s; ?></option>
                        <?php endforeach ?>
                      </select>
                    </div>
                    <div class="form-group">
                      <label class="control-label"><b>Golongan Darah</b></label>
                      <select name="golongan_darah" class="bootstrap-select" data-width="100%">
                        <?php foreach ($gol_darah as $s): ?>
                          <option value="<?php echo $s ?>" <?php if ($s == $row['golongan_darah']): ?> selected <?php endif; ?>><?php echo $s; ?></option>
                        <?php endforeach ?>
                      </select>
                    </div>
                    <div class="form-group">
                      <label class="control-label"><b>Alergi</b></label>
                      <select name="alergi" class="bootstrap-select" data-width="100%">
                        <option value="iya" <?php if ('iya' == $row['alergi']): ?> selected <?php endif; ?>>Iya</option>
                        <option value="tidak" <?php if ('tidak' == $row['alergi']): ?> selected <?php endif; ?>>Tidak</option>
                      </select>
                    </div>
                    <div class="form-group">
                      <label class="control-label"><b>Operasi</b></label>
                      <select name="status_operasi" class="bootstrap-select" data-width="100%">
                        <option value="iya" <?php if ('iya' == $row['status_operasi']): ?> selected <?php endif; ?>>Iya</option>
                        <option value="tidak" <?php if ('tidak' == $row['status_operasi']): ?> selected <?php endif; ?>>Tidak</option>
                      </select>
                    </div>
                    <div class="form-group">
                      <label class="control-label"><b>Alamat</b></label>
                      <textarea class="form-control" name="alamat" cols="30" rows="2"><?php echo $row['alamat']; ?></textarea>
                    </div>
                    <div class="form-group">
                      <label class="control-label"><b>Username</b></label>
                      <input required name="username" type="text" value="<?php echo $row['username']; ?>" class="form-control">
                    </div>
                    <div class="form-group">
                      <label class="control-label"><b>Password</b></label>
                      <input name="password" type="password" value="" class="form-control">
                    </div>
                  </div>

                  <div class="col-md-12">
                    <div class="form-group">
                      <button type="submit" class="btn btn-success"><i class="icon-floppy-disk position-left"></i> Simpan</button>
											<a href="<?php echo base_url(); ?>resepsionis/pasien"><button type="button" class="btn btn-warning"><i class="icon-cross"></i> Batal</button></a>
                    </div>
                  </div>
                </div>
                </form>
              </div>
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
