<!DOCTYPE html>
<html lang="en">
<head>
<?php $this->load->view('admin/css'); ?>
</head>

<body>
<?php $this->load->view('admin/nav'); ?>
<?php $this->load->view('admin/laporan/menu'); ?>
<style media="screen">
  #form-tahun,
  #form-bulan{
    display: none;
  }
</style>
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

								<form action="<?= base_url() ?>laporan/barang_expired/print_laporan" method="POST" class="form-horizontal" target="_blank" autocomplete="off">
                    <div class="row">
                      <div class="col-sm-12">
                        <div class="form-group">
                          <label class="control-label col-sm-1"><b>Pilih Cabang</b></label>
                          <div class="col-sm-5">
                            <select class="bootstrap-select" data-width="100%" name="id_cabang" id="id_cabang">
                              <?php if($this->session->userdata('id_cabang') == 3) : ?>
                                <option value="<?= $cabang_apotek['id'] ?>"><?= $cabang_apotek['nama'] ?></option>
                              <?php else: ?>
                                <option value="semua">Semua</option>
                                <?php foreach ($cabang as $c): ?>
                                  <option value="<?php echo $c['id']; ?>"><?php echo $c['nama']; ?></option>
                                <?php endforeach; ?>
                              <?php endif ?>
                            </select>
                          </div>
                        </div>
                      </div>
                    </div>

									<button class="btn btn-primary" type="submit"><i class="fa fa-search position-left"></i> Cari</button>
                  <button type="button" class="btn btn-info" onclick="export_excel()"><i class="fa fa-file-excel-o"
                        style="margin-right: 6px;"></i> Export
                      Excel</button>

                
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
<!-- /page container -->
<?php $this->load->view('admin/js'); ?>
<script>
      function export_excel() {
      let id_cabang = $('#id_cabang').val();
      
      let link = `<?php echo base_url('laporan/barang_expired/export_excel'); ?>?id_cabang=${id_cabang}`;
      window.open(link, '_blank').focus();
    }

</script>
</body>
</html>
