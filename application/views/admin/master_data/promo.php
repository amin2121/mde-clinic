<!DOCTYPE html>
<html lang="en">
<head>
<?php $this->load->view('admin/css'); ?>
</head>

<body>

<?php $this->load->view('admin/nav'); ?>
<?php $this->load->view('admin/farmasi/menu'); ?>

<!-- Page container -->
<div class="page-container">

<!-- Page content -->
<div class="page-content">

<!-- Main content -->
<div class="content-wrapper">
<div class="content">

			<div class="panel panel-flat">
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
									<li class="active"><a href="#supplier" data-toggle="tab">Promo</a></li>
									<li><a href="#tambah-supplier" data-toggle="tab">Tambah Promo</a></li>
								</ul>

								<div class="tab-content" style="margin-bottom: 2em !important;">
									<div class="tab-pane active" id="supplier">
										<div class="table-responsive">
											<table class="table table-bordered table-striped" id="table-supplier">
												<thead>
													<tr class="bg-success">
														<th class="text-center">No</th>
														<th class="text-center">Promo</th>
														<th class="text-center">No HP</th>
														<th class="text-center">No Rekening</th>
														<th class="text-center">bank</th>
														<th class="text-center">Action</th>
													</tr>
												</thead>
												<tbody>

												</tbody>
											</table>
										</div>

									</div>
									<div class="tab-pane" id="tambah-supplier">
										<form action="<?= base_url('farmasi/supplier/tambah_supplier') ?>" id="form-tambah-supplier" method="POST">

											<div class="form-group">
												<label class="control-label"><b>Nama Promo</b></label>
												<input type="text" class="form-control" name="nama_supplier">
											</div>

                      <div class="table-responsive form-group">
                        <table class="table table-striped table-hover table-bordered table_tambah_tindakan">
                          <thead>
                            <tr>
                              <th class="text-center">Tindakan</th>
                              <th class="text-center">Jumlah</th>
                              <th class="text-center">Tarif</th>
                              <th class="text-center">Sub Total</th>
                              <th class="text-center">Action</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr>
                              <td>
                                <div class="form-group">
                                  <div class="input-group">
                                    <span class="input-group-btn">
                                      <button class="btn bg-primary btn-icon" type="button" onclick="modal_tindakan(); get_tindakan(1);"><i class="icon-search4"></i></button>
                                    </span>
                                    <input type="text" class="form-control" placeholder="Cari Tarif" readonly id="tarif_nama_1" name="nama_tarif[]">
                                    <input type="hidden" id="tarif_id_1" name="id_tarif[]">
                                  </div>
                                </div>
                              </td>
                              <td>
                                <div class="form-group">
                                  <input type="text" class="form-control jumlah_all" id="jumlah_1" name="jumlah[]" onkeyup="FormatCurrency(this); hitung_jumlah(1); hitung_total_tarif();">
                                </div>
                              </td>
                              <td>
                                <div class="form-group">
                                  <input type="text" class="form-control" readonly id="tarif_harga_1" name="harga_tarif[]">
                                </div>
                              </td>
                              <td>
                                <div class="form-group">
                                  <input type="text" class="form-control sub_total_all" readonly id="sub_total_1" name="sub_total[]">
                                </div>
                              </td>
                              <td>
                                <button class="btn btn-md btn-icon btn-danger" disabled=""><i class="icon-bin"></i></button>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                      <div class="form-group">
                          <button type="button" class="btn btn-warning m-b-0" onclick="tambah_tindakan();"><i class="fa fa-plus"></i> Tambah</button>
                      </div>

											<button class="btn btn-success" type="submit" style="margin-top: 2em;"><i class="icon-check"></i> Tambah</button>
										</form>

									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>


<script>
	$(document).ready(() => {
		$(`#table-supplier`).DataTable()
	})
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
