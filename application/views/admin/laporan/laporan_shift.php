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

                <form action="<?= base_url() ?>laporan/shift/print_laporan" method="POST" class="form-horizontal" target="_blank" autocomplete="off">
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
