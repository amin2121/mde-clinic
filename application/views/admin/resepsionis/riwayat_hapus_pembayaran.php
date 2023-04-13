<!DOCTYPE html>
<html lang="en">
<head>
<?php $this->load->view('admin/css'); ?>
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
<div class="content">

			<div class="panel panel-flat border-top-success border-top-lg">
				<div class="panel-heading">
					<h6 class="panel-title"><?= $title ?></h6>
				</div>


				<div class="panel-body">
					<div class="form-search form-horizontal">
						<div class="row">
							<div class="col-sm-6">
								<div class="form-group">
									<label class="control-label col-sm-2"><b>Tanggal Dari</b></label>
									<div class="col-sm-10">
										<input type="text" class="form-control input-tgl" autocomplete="off" name="tgl_dari" id="input-tgl-dari">
									</div>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label class="control-label col-sm-3"><b>Tanggal Sampai</b></label>
									<div class="col-sm-8">
										<input type="text" class="form-control input-tgl" autocomplete="off" name="tgl_sampai" id="input-tgl-sampai">
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-6">
								<button class="btn btn-md btn-primary" type="button" onclick="cari_riwayat_pembayaran(this)"><i class="icon-search4"></i> Cari</button>
								<button class="btn btn-md btn-success" type="button" onclick="cari_riwayat_pembayaran(this)"><i class="icon-book2"></i> Lihat Semua</button>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-sm-12">
							<button type="button" class="btn btn-md btn-danger" style="margin-bottom: 2em;" onclick="tampil_modal_hapus_semua()"><i class="icon-trash position-left"></i> Hapus Semua</button>
							
							<!-- Modal Hapus Pengeluaran -->
							<div id="modal_hapus_semua" class="modal fade">
								<div class="modal-dialog">
									<div class="modal-content">
										<div class="modal-header bg-danger">
											<button type="button" class="close" data-dismiss="modal">&times;</button>
											<h5 class="modal-title">Hapus Semua Riwayat Pembayaran</h5>
										</div>
										<form action="<?= base_url('resepsionis/riwayat_hapus_pembayaran/hapus_semua_riwayat_pembayaran') ?>" method="POST">
											<div class="modal-body">
												<div class="alert alert-danger no-border">
													<p>Apakah Anda Ingin <span class="text-semibold">Menghapus</span> Semua Riwayat Pembayaran Ini ?</p>
												</div>
											</div>
											<div class="modal-footer">
												<button class="btn btn-link" data-dismiss="modal"><i class="icon-cross position-left"></i> Keluar</button>
												<button type="submit" class="btn btn-danger"><i class="icon-trash position-left"></i> Hapus</button>
											</div>
									</form>
									</div>
								</div>
							 </div>
							<!-- /Modal Hapus Pengeluaran -->

							<div class="table-responsive">
								<table class="table table-bordered table-striped table-hover" id="table-hapus-riwayat-pembayaran">
									<thead>
										<tr class="bg-success">
											<th class="text-center">Invoice</th>
											<th class="text-center">Nama Pasien</th>
											<th class="text-center">Nama Kasir</th>
											<th class="text-center">Total Invoice</th>
											<th class="text-center">User Yang Hapus</th>
											<th class="text-center">Alasan</th>
											<th class="text-center">Tanggal</th>
											<th class="text-center">Aksi</th>
										</tr>
									</thead>
									<tbody id="tbody-riwayat-hapus-pembayaran">
									</tbody>
								</table>
							</div>
							<br>
							<div id="pagination_riwayat_pembayaran"></div>
						</div>
					</div>
				</div>

			</div>

			<!-- Modal Hapus Pengeluaran -->
			<a href="#" style="display:none;" class="btn btn-md btn-icon btn-danger" id="klik_detail_pembayaran" data-toggle="modal" data-target="#modal-detail-pembayaran"><i class="icon-trash position-left"></i> Detail</a>
			<div id="modal-detail-pembayaran" class="modal fade">
				<div class="modal-dialog  modal-lg">
					<div class="modal-content">
						<div class="modal-header bg-primary">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
							<h5 class="modal-title">Detail Pembayaran</h5>
						</div>
						<div class="modal-body">
              <div class="tabbable">
								<ul class="nav nav-tabs nav-tabs-component">
									<li class="active"><a href="#pembayaran" data-toggle="tab">Detail</a></li>
									<li><a href="#data_tindakan" data-toggle="tab">Data Tindakan</a></li>
                  <li><a href="#data_resep" data-toggle="tab">Data Resep</a></li>
								</ul>

								<div class="tab-content" style="margin-bottom: 2em !important;">
									<div class="tab-pane active" id="pembayaran">
                    <div class="row">
                      <div class="col-md-6">
                         <div class="form-group">
                           <label class="control-label"><b>Nama Pasien</b></label>
                           <input required id="nama_pasien" type="text" class="form-control" readonly>
                         </div>
                         <div class="form-group">
                           <label class="control-label"><b>Nama Dokter</b></label>
                           <input required id="nama_dokter" type="text" class="form-control" readonly>
                         </div>
                         <div class="form-group">
                           <label class="control-label"><b>Nama Poli</b></label>
                           <input required id="nama_poli" type="text" class="form-control" readonly>
                         </div>
                         <div class="form-group">
                           <label class="control-label"><b>Nama Kasir</b></label>
                           <input required id="nama_kasir" type="text" class="form-control" readonly>
                         </div>
                         <div class="form-group">
                           <label class="control-label"><b>Tanggal & Waktu Pembayaran</b></label>
                           <input required id="tanggal_waktu_pembayaran" type="text" class="form-control" readonly>
                         </div>
                         <div class="form-group">
                           <label class="control-label"><b>User yang Hapus</b></label>
                           <input required id="nama_kasir_hapus" type="text" class="form-control" readonly>
                         </div>
                         <div class="form-group">
                           <label class="control-label"><b>Tanggal & Waktu Hapus Pembayaran</b></label>
                           <input required id="tanggal_waktu_hapus_pembayaran" type="text" class="form-control" readonly>
                         </div>
												 <div class="form-group">
                           <label class="control-label"><b>Alasan</b></label>
                           <input required id="alasan" type="text" class="form-control" readonly>
                         </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                           <label class="control-label"><b>Biaya Tindakan</b></label>
                           <div class="input-group">
                             <span class="input-group-addon"><b>Rp.</b></span>
                             <input required id="biaya_tindakan" type="text" class="form-control" readonly>
                           </div>
                         </div>
                         <div class="form-group">
                           <label class="control-label"><b>Biaya Resep</b></label>
                           <div class="input-group">
                             <span class="input-group-addon"><b>Rp.</b></span>
                             <input required id="biaya_resep" type="text" class="form-control" readonly>
                           </div>
                         </div>
                         <div class="form-group">
                           <label class="control-label"><b>Biaya Admin</b></label>
                           <div class="input-group">
                             <span class="input-group-addon"><b>Rp.</b></span>
                             <input required id="biaya_admin" type="text" class="form-control" readonly>
                           </div>
                         </div>
                         <div class="form-group">
                           <label class="control-label"><b>Biaya Id Card</b></label>
                           <div class="input-group">
                             <span class="input-group-addon"><b>Rp.</b></span>
                             <input required id="biaya_id_card" type="text" class="form-control" readonly>
                           </div>
                         </div>
                         <div class="form-group">
                           <label class="control-label"><b>Total</b></label>
                           <div class="input-group">
                             <span class="input-group-addon"><b>Rp.</b></span>
                             <input required id="total_invoice" type="text" class="form-control" readonly>
                           </div>
                         </div>
                         <div class="form-group">
                           <label class="control-label"><b>Bayar</b></label>
                           <div class="input-group">
                             <span class="input-group-addon"><b>Rp.</b></span>
                             <input required id="bayar" type="text" class="form-control" readonly>
                           </div>
                         </div>
                         <div class="form-group">
                           <label class="control-label"><b>Kembali</b></label>
                           <div class="input-group">
                             <span class="input-group-addon"><b>Rp.</b></span>
                             <input required id="kembali" type="text" class="form-control" readonly>
                           </div>
                         </div>
                      </div>
                    </div>
									</div>

									<div class="tab-pane" id="data_tindakan">
                    <div class="table-responsive">
                      <div class="form-group">
                        <label class="control-label"><b>Keluhan</b></label>
                        <input required id="keluhan" type="text" class="form-control" readonly>
                      </div>
                      <div class="form-group">
                        <label class="control-label"><b>Diagnosa</b></label>
                        <input required id="diagnosa" type="text" class="form-control" readonly>
                      </div>
											<table class="table table-bordered table-striped table_data_tindakan">
												<thead>
													<tr class="bg-warning">
														<th class="text-center">No</th>
														<th class="text-center">Nama Tarif</th>
														<th class="text-center">Jumlah</th>
														<th class="text-center">Harga Tarif</th>
														<th class="text-center">Sub Total</th>
													</tr>
												</thead>
												<tbody>

												</tbody>
                        <tfoot>
                          <tr>
                            <th colspan="4">Total</th>
                            <th id="html_total_tindakan"></th>
                          </tr>
                        </tfoot>
											</table>
										</div>
									</div>

                  <div class="tab-pane" id="data_resep">
                    <div class="table-responsive">
											<table class="table table-bordered table-striped table_data_resep">
												<thead>
													<tr class="bg-indigo">
														<th class="text-center">No</th>
														<th class="text-center">Nama Barang</th>
														<th class="text-center">Jumlah</th>
														<th class="text-center">Aturan Minum</th>
                            <th class="text-center">Harga Barang</th>
                            <th class="text-center">Sub Total</th>
													</tr>
												</thead>
												<tbody>

												</tbody>
                        <tfoot>
                          <tr>
                            <th colspan="5">Total</th>
                            <th id="html_total_resep"></th>
                          </tr>
                        </tfoot>
											</table>
										</div>
									</div>

								</div>
							</div>

						</div>
						<div class="modal-footer">
							<button class="btn btn-link" data-dismiss="modal"><i class="icon-cross position-left"></i> Keluar</button>
						</div>
					</div>
				</div>
			 </div>
			<!-- /Modal Hapus Pengeluaran -->

<script>
	function tampil_modal_hapus_semua() {
		$(`#modal_hapus_semua`).modal('toggle');
	}
	$(window).load(function() {
		$('.input-tgl').datepicker({
        dateFormat : 'dd-mm-yy',
        autoclose: true,
        language: 'fr',
        orientation: 'bottom auto',
        todayBtn: 'linked',
        todayHighlight: true
    });

		$.ajax({
			url : '<?= base_url('resepsionis/riwayat_hapus_pembayaran/riwayat_pembayaran_ajax') ?>',
			method : 'POST',
			dataType : 'json',
			success : (res) => {
				let row = '';
				if(res.status) {
					let i = 0;
					for(const item of res.data) {

						row += `
							<tr>
								<td class="text-center">${item.invoice}</td>
								<td class="text-center">${item.nama_pasien}</td>
								<td class="text-center">${item.nama_kasir}</td>
								<td class="text-center"><b>Rp. </b>${NumberToMoney(item.total_invoice)}</td>
								<td class="text-center">${item.nama_kasir_hapus}</td>
								<td class="text-center">${item.alasan}</td>
								<td class="text-center">${item.tanggal}</td>
								<td class="text-center">
									<button type="button" class="btn btn-warning btn-sm" onclick="detail_pembayaran(${item.id});"><i class="icon-eye position-left"></i> Detail</button>
								</td>
							</tr>
						`
					}

				} else {
					row += `<tr>
						<td colspan="10" class="text-center">${res.message}</td>
					</tr>`
				}
				$(`#tbody-riwayat-hapus-pembayaran`).html(row);
				paging_riwayat_pembayaran();
			}
		});
	});

	function detail_pembayaran(id_registrasi){
		$('#klik_detail_pembayaran').click();
    $.ajax({
        url : '<?php echo base_url(); ?>resepsionis/riwayat_hapus_pembayaran/detail_pembayaran',
        data : {id_registrasi:id_registrasi},
        type : "POST",
        dataType : "json",
        success : function(row){
					$('#nama_pasien').val(row.nama_pasien);
					$('#nama_dokter').val(row.nama_dokter);
					$('#nama_poli').val(row.nama_poli);
					$('#nama_kasir').val(row.nama_kasir);
					$('#tanggal_waktu_pembayaran').val(row.tanggal+' '+row.waktu);
					$('#nama_kasir_hapus').val(row.nama_kasir_hapus);
					$('#tanggal_waktu_hapus_pembayaran').val(row.tanggal_hapus+' '+row.waktu_hapus);
					$('#biaya_tindakan').val(NumberToMoney(row.biaya_tindakan));
					$('#biaya_resep').val(NumberToMoney(row.biaya_resep));
					$('#biaya_admin').val(NumberToMoney(row.biaya_admin));
					$('#biaya_id_card').val(NumberToMoney(row.biaya_id_card));
					$('#total_invoice').val(NumberToMoney(row.total_invoice));
					$('#bayar').val(NumberToMoney(row.bayar));
					$('#kembali').val(NumberToMoney(row.kembali));
					$('#keluhan').val(row.keluhan);
					$('#diagnosa').val(row.diagnosa);
					$('#alasan').val(row.alasan);
        }
    });

		get_tindakan(id_registrasi);
	  get_resep(id_registrasi);
	}

	function paging_riwayat_pembayaran($selector){
		var jumlah_tampil = '10';

	    if(typeof $selector == 'undefined'){
	        $selector = $("#table-hapus-riwayat-pembayaran tbody tr");
	    }
			window.tp = new Pagination('#pagination_riwayat_pembayaran', {
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

	function cari_riwayat_pembayaran(e) {
		let value_tgl_dari = $(`#input-tgl-dari`).val();
		let value_tgl_sampai = $(`#input-tgl-sampai`).val();

		$.ajax({
			url : '<?= base_url('resepsionis/riwayat_hapus_pembayaran/riwayat_pembayaran_ajax') ?>',
			data : {'tgl_dari' : `${value_tgl_dari}`, 'tgl_sampai' : `${value_tgl_sampai}`},
			method : 'POST',
			dataType : 'json',
			success : (res) => {
								let row = '';
				if(res.status) {
					let i = 0;
					for(const item of res.data) {

						row += `
							<tr>
								<td class="text-center">${item.invoice}</td>
								<td class="text-center">${item.nama_pasien}</td>
								<td class="text-center">${item.nama_kasir}</td>
								<td class="text-center"><b>Rp. </b>${NumberToMoney(item.total_invoice)}</td>
								<td class="text-center">${item.nama_kasir_hapus}</td>
								<td class="text-center">${item.alasan}</td>
								<td class="text-center">${item.tanggal}</td>
								<td class="text-center">
									<button type="button" class="btn btn-warning btn-sm" onclick="detail_pembayaran(${item.id});"><i class="icon-eye position-left"></i> Detail</button>
								</td>
							</tr>
						`
					}

				} else {
					row += `<tr>
						<td colspan="10" class="text-center">${res.message}</td>
					</tr>`
				}
				$(`#tbody-riwayat-hapus-pembayaran`).html(row);
				paging_riwayat_pembayaran();
			}
		})

		$(`#input-tgl-dari`).val('');
		$(`#input-tgl-sampai`).val('');
	}

	function get_tindakan(id_registrasi){
	  $.ajax({
	      url : '<?php echo base_url(); ?>resepsionis/riwayat_hapus_pembayaran/get_tindakan',
	      data : {id_registrasi:id_registrasi},
	      type : "POST",
	      dataType : "json",
	      success : function(data){
	          $tr = "";
	          var total = 0;

	          if(data == "" || data == null){
	              $tr = "<tr><td colspan='5' style='text-align:center;'><b>Data tidak ditemukan</b></td></tr>";
	          }else{
	              var no = 0;
	              for(var i=0; i<data.length; i++){
	                  no++;

	                  $tr += '<tr>'+
	                              '<td>'+no+'</td>'+
	                              '<td>'+data[i].nama_tarif+'</td>'+
	                              '<td>'+NumberToMoney(data[i].jumlah)+'</td>'+
	                              '<td>Rp. '+NumberToMoney(data[i].harga_tarif)+'</td>'+
	                              '<td>Rp. '+NumberToMoney(data[i].sub_total)+'</td>'+
	                          '</tr>';
	               total += parseFloat(data[i].sub_total);
	              }
	          }
	          $('#html_total_tindakan').html('Rp. '+NumberToMoney(total));
	          $('.table_data_tindakan tbody').html($tr);
	      }
	  });
	}

	function get_resep(id_registrasi){
	  $.ajax({
	      url : '<?php echo base_url(); ?>resepsionis/riwayat_hapus_pembayaran/get_resep',
	      data : {id_registrasi:id_registrasi},
	      type : "POST",
	      dataType : "json",
	      success : function(result){
	          $tr = "";
	          var total = 0;

	          if(result == "" || result == null){
	              $tr = "<tr><td colspan='7' style='text-align:center;'><b>Data tidak ditemukan</b></td></tr>";
	          }else{
	              var no = 0;
	              for(var i=0; i<result.length; i++){
	                  no++;

	                  $tr += '<tr>'+
	                              '<td>'+no+'</td>'+
	                              '<td>'+result[i].nama_barang+'</td>'+
	                              '<td>'+result[i].jumlah_obat+'</td>'+
	                              '<td>'+result[i].aturan_minum+'</td>'+
	                              '<td>Rp. '+NumberToMoney(result[i].harga_obat)+'</td>'+
	                              '<td>Rp. '+NumberToMoney(result[i].sub_total_obat)+'</td>'+
	                          '</tr>';
	                  total += parseFloat(result[i].sub_total_obat);
	              }
	          }
	          $('#html_total_resep').html('Rp. '+NumberToMoney(total));
	          $('.table_data_resep tbody').html($tr);
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
