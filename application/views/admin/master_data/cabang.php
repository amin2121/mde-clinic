<!DOCTYPE html>
<html lang="en">
<head>
<?php $this->load->view('admin/css'); ?>
<script src="<?php echo base_url('assets/js/plugins/tables/datatables/datatables.min.js'); ?>"></script>
<script type="text/javascript">
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
									<li class="active"><a href="#cabang" data-toggle="tab">Cabang</a></li>
									<li><a href="#tambah-cabang" data-toggle="tab">Tambah Cabang</a></li>
								</ul>

								<div class="tab-content" style="margin-bottom: 2em !important;">
									<div class="tab-pane active" id="cabang">
										<div class="row">
											<div class="col-sm-6">
		                    <div class="form-group">
													<div class="input-group">
														<input type="text" id="cari_cabang" class="form-control" placeholder="Cari Berdasarkan Nama Cabang">
														<span class="input-group-btn">
															<button class="btn btn-md btn-primary btn-icon" type="button"><i class="fa fa-search position-left"></i> Cari</button>
														</span>
													</div>
												</div>
											</div>
										</div>
										<div class="table-responsive">
											<table class="table table-bordered table-striped" id="table-cabang">
												<thead>
													<tr class="bg-success">
														<th class="text-center">No</th>
														<th class="text-center">Nama Cabang</th>
														<th class="text-center">Status Cabang</th>
														<th class="text-center">Aksi</th>
													</tr>
												</thead>
												<tbody>
												</tbody>
											</table>
										</div>
										<br>
										<div class="pagination"></div>
									</div>

									<div class="tab-pane" id="tambah-cabang">
                    <form action="<?= base_url('master/cabang/tambah') ?>" method="POST">
                    	<div class="row">
                    		<div class="col-sm-6">
		                      <div class="form-group">
		                        <label class="control-label"><b>Nama Cabang</b></label>
		                        <input type="text" class="form-control" placeholder="Nama Cabang" name="nama">
		                      </div>
                    		</div>
                    	</div>

                    <button class="btn btn-md btn-success btn-icon" type="submit"><i class="icon-floppy-disk position-left"></i> Simpan</button>
                    </form>
									</div>

								</div>
							</div>
						</div>
					</div>
				</div>
			</div>


<script>
	$(window).load(function() {
    cabang_result();
	});

  function cabang_result(){
    var search = $('#cari_cabang').val();

		$.ajax({
			url : '<?php echo base_url('master/cabang/cabang_result') ?>',
      data : {search:search},
			method : 'POST',
			dataType : 'json',
			success : function(res) {
				let table = '';
				if(res.status) {
					let i = 0;
					for(const item of res.data) {
						table +=	`
							<tr>
								<td class="text-center">${++i}</td>
								<td class="text-center">${item.nama}</td>
								<td class="text-center">${item.status_cabang}</td>
								<td>
									<div class="text-center">
										<a href="#" class="btn btn-sm btn-icon btn-primary" data-toggle="modal" data-target="#modal_edit_cabang-${item.id}"><i class="icon-pencil position-left"></i> Edit</a>
										<a href="#" class="btn btn-sm btn-icon btn-danger" data-toggle="modal" data-target="#modal_hapus_cabang-${item.id}"><i class="icon-trash position-left"></i> Hapus</a>
									</div>

									<!-- Modal Ubah Cabang -->
									<div id="modal_edit_cabang-${item.id}" class="modal fade">
										<div class="modal-dialog">
											<div class="modal-content">
												<div class="modal-header bg-primary">
													<button type="button" class="close" data-dismiss="modal">&times;</button>
													<h5 class="modal-title">Ubah Cabang</h5>
												</div>
												<form action="<?= base_url('master/cabang/ubah') ?>" method="POST" id="form-ubah-cabang">
												<div class="modal-body">

													<input type="text" name="id" value="${item.id}" hidden>
                          <div class="form-group">
                            <label class="control-label"><b>Nama Cabang</b></label>
                            <input type="text" class="form-control" placeholder="Nama Cabang" value="${item.nama}" name="nama">
                          </div>

												</div>
												<div class="modal-footer">
													<button class="btn btn-link" data-dismiss="modal"><i class="icon-cross position-left"></i> Keluar</button>
													<button type="submit" class="btn btn-primary"><i class="icon-pencil position-left"></i> Ubah</button>
												</div>
												</form>
											</div>
										</div>
									</div>
									<!-- /Modal Ubah Cabang -->

									<!-- Modal Hapus Cabang -->
									<div id="modal_hapus_cabang-${item.id}" class="modal fade">
										<div class="modal-dialog">
											<div class="modal-content">
												<div class="modal-header bg-danger">
													<button type="button" class="close" data-dismiss="modal">&times;</button>
													<h5 class="modal-title">Hapus Cabang</h5>
												</div>
												<form action="<?= base_url('master/cabang/hapus') ?>" method="POST">
												<div class="modal-body">
													<input type="hidden" name="id" value="${item.id}">
													<div class="alert alert-danger no-border">
														<p>Apakah Anda Ingin <span class="text-semibold">Menghapus</span> Cabang Ini ?</p>
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
									<!-- /Modal Hapus Cabang -->

								</td>
							</tr>
					`
					}
				} else {
					table += `
						<tr>
							<td colspan="4" class="text-semibold text-center">${res.message}</td>
						</tr>
					`
				}

				$(`#table-cabang tbody`).html(table);				
				paging();
			},
		});

    $('#cari_cabang').off('keyup').keyup(function(){
        cabang_result();
    });
  }

	function paging($selector){
		var jumlah_tampil = '10';

	    if(typeof $selector == 'undefined'){
	        $selector = $("#table-cabang tbody tr");
	    }
			window.tp = new Pagination('.pagination', {
					itemsCount:$selector.length,
	        pageSize : parseInt(jumlah_tampil),
	        onPageSizeChange: function (ps) {
	            console.log('changed to ' + ps);
	        },
	        onPageChange: function (paging) {
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
