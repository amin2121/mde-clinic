<!DOCTYPE html>
<html lang="en">
<head>
<?php $this->load->view('admin/css'); ?>
</head>

<body>
<?php $this->load->view('admin/nav'); ?>
<?php $this->load->view('admin/laporan/menu'); ?>
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

								<form action="<?= base_url() ?>laporan/absen_telat/print_laporan" method="POST" class="form-horizontal" target="_blank" autocomplete="off">
                  <div class="row">

										<div class="col-sm-12">
                      <div class="form-group">
                        <label class="control-label col-sm-1"><b>Pilih Cabang</b></label>
                        <div class="col-sm-5">
                          <select class="bootstrap-select" data-width="100%" name="id_cabang">
                            <option value="semua">Semua</option>
                            <?php foreach ($cabang as $c): ?>
                              <option value="<?php echo $c['id']; ?>"><?php echo $c['nama']; ?></option>
                            <?php endforeach; ?>
                          </select>
                        </div>
                      </div>
                    </div>
                    <div id="form-hari">
                      <div class="col-sm-6">
                        <div class="form-group">
                          <label class="control-label col-sm-3"><b>Tanggal Dari</b></label>
                          <div class="col-sm-8">
                            <input type="text" class="form-control input-tgl" name="tgl_dari">
                          </div>
                        </div>
                      </div>
                      <div class="col-sm-6">
                        <div class="form-group">
                          <label class="control-label col-sm-3"><b>Tanggal Sampai</b></label>
                          <div class="col-sm-8">
                            <input type="text" class="form-control input-tgl" name="tgl_sampai">
                          </div>
                        </div>
                      </div>
                    </div>
<!--                     <div id="form-bulan">
                      <div class="col-sm-6">
                        <div class="form-group">
        									<label class="control-label col-sm-3"><b>Bulan</b></label>
        									<div class="col-sm-8">
        										<select name="bulan" class="bootstrap-select" data-width="100%">
                              <?php
                              $bulan=array("Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember");
                              $jlh_bln=count($bulan);
                              $no = 0;
                              for($c=0; $c<$jlh_bln; $c+=1){
                                  $no++;
                                  $no_pas =  sprintf("%02s", $no);
                              ?>
                              <option value="<?php echo $no_pas; ?>" <?php if ($no_pas == date('m')) {echo 'selected';}; ?>> <?php echo $bulan[$c]; ?> </option>
                              <?php
                              }
                              ?>
                            </select>
        									</div>
        								</div>
                      </div>
                      <div class="col-sm-6">
                        <div class="form-group">
                          <label class="control-label col-sm-3"><b>Tahun</b></label>
                          <div class="col-sm-8">
                            <select name="bulan_tahun" class="bootstrap-select" data-width="100%">
                              <?php
                              $now=date('Y');
                              for ($a=2010;$a<=$now;$a++){
                              ?>
                                <option value="<?php echo $a; ?>" <?php if ($a == date('Y')) {echo 'selected';}; ?>><?php echo $a; ?></option>
                              <?php
                              }
                              ?>
                            </select>
                          </div>
                        </div>
                      </div>
                    </div>
 -->
      						</div>

									<button class="btn btn-primary" type="submit" style="margin-top: 1em;"><i class="fa fa-search position-left"></i> Cari</button>
								</form>



						</div>
					</div>
				</div>
			</div>


</div>
</div>
<!-- /main content -->

</div>
<!-- /page content -->

</div>
<script type="text/javascript">
  $(document).ready(function(){
    $('#filter_hari').click(function(){
    $('#form-hari').show();
    $('#form-bulan').hide();
    $('#form-tahun').hide();
  });

  $('#filter_bulan').click(function(){
    $('#form-hari').hide();
    $('#form-bulan').show();
    $('#form-tahun').hide();
  });

  $('#filter_tahun').click(function(){
    $('#form-hari').hide();
    $('#form-bulan').hide();
    $('#form-tahun').show();
  });

    $('.input-tgl').datepicker({
        dateFormat : 'dd-mm-yy',
        autoclose: true,
        language: 'fr',
        orientation: 'bottom auto',
        todayBtn: 'linked',
        todayHighlight: true
    });
  });

</script>
<!-- /page container -->
<?php $this->load->view('admin/js'); ?>
</body>
</html>
