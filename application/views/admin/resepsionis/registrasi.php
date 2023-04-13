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

							<div class="table-responsive">
                <form action="<?php echo base_url('resepsionis/registrasi/tambah_registrasi') ?>" method="post">
                  <div class="col-md-6">
                    <div class="form-group">
    									<label for=""  class="control-label"><b>Nama Pasien</b></label>
  										<div class="input-group">
  											<input type="text" id="nama_pasien" name="nama_pasien" class="form-control" placeholder="Nama Pasien" readonly>
												<input type="hidden" id="id_pasien" name="id_pasien" class="form-control" readonly>
  											<span class="input-group-btn">
  												<button class="btn bg-primary" onclick="modal_pasien(); get_pasien();" type="button"><i class="fa fa-search position-left"></i> Cari</button>
  											</span>
  										</div>
    								</div>
                    <div class="form-group">
                      <label for="" class="control-label"><b>NIK</b></label>
                      <input required name="no_ktp" id="no_ktp" type="text" class="form-control" placeholder="NIK" readonly>
                    </div>
                    <div class="form-group">
                      <label for="" class="control-label"><b>Tanggal Lahir</b></label>
                      <input type="text" id="tanggal_lahir" class="form-control datepicker" name="tanggal_lahir" placeholder="Tanggal Lahir" readonly>
                    </div>
                    <div class="form-group">
                      <label for="" class="control-label"><b>Pekerjaan</b></label>
                      <input required name="pekerjaan" id="pekerjaan" type="text" class="form-control" placeholder="Pekerjaan" readonly>
                    </div>
                    <div class="form-group">
                      <label for="" class="control-label"><b>Nomor Telepon</b></label>
                      <input required name="no_telp" id="no_telp" type="text" class="form-control" placeholder="Nomor Telepon" readonly>
                    </div>
                    <div class="form-group">
                      <label for="" class="control-label"><b>Nama Wali</b></label>
                      <input required name="nama_wali" id="nama_wali" type="text" class="form-control" placeholder="Nama Wali" readonly>
                    </div>
                    <div class="form-group">
                      <label for="" class="control-label"><b>Alamat</b></label>
                      <textarea class="form-control" name="alamat" id="alamat" cols="30" rows="2" placeholder="Alamat" readonly></textarea>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="" class="control-label"><b>Jenis Kelamin</b></label>
                      <input required name="jenis_kelamin" id="jenis_kelamin" type="text" class="form-control" placeholder="Jenis Kelamin" readonly>
                    </div>
                    <div class="form-group">
                      <label for="" class="control-label"><b>Status Pernikahan</b></label>
                      <input required name="status_perkawinan" id="status_perkawinan" type="text" class="form-control" placeholder="Status Pernikahan" readonly>
                    </div>
                    <div class="form-group">
                      <label for="" class="control-label"><b>Golongan Darah</b></label>
                      <input required name="golongan_darah" type="text" id="golongan_darah" class="form-control" placeholder="Golongan Darah" readonly>
                    </div>
                    <div class="form-group">
                      <label for="" class="control-label"><b>Alergi</b></label>
                      <input required name="alergi" type="text" id="alergi" class="form-control" placeholder="Alergi" readonly>
                    </div>
                    <div class="form-group">
                      <label for="" class="control-label"><b>Operasi</b></label>
                      <input required name="status_operasi" id="status_operasi" type="text" class="form-control" placeholder="Operasi" readonly>
                    </div>
                  </div>
                  <div class="col-md-12">
                    <legend class="text-bold">Pilih Tujuan</legend>
                  </div>
                  <div class="col-md-6">
										<div class="form-group">
											<label for="" class="control-label"><b>Tanggal Datang</b></label>
											<input required name="tanggal_datang" type="text" class="form-control" value="<?php echo date('d-m-Y'); ?>" readonly>
										</div>
                    <div class="form-group">
                      <label class="control-label"><b>Poli</b></label>
                      <select class="select-search-primary" data-placeholder="Pilih Poli" name="id_poli" onchange="klik_poli(this.value);">
												<option></option>
												<?php foreach ($poli as $p): ?>
													<option value="<?php echo $p['poli_id']; ?>"><?php echo $p['poli_nama']; ?></option>
												<?php endforeach; ?>
                      </select>
                    </div>
                    <div class="form-group">
                      <label for="" class="control-label"><b>Dokter</b></label>
											<select class="select-search-warning" data-placeholder="Pilih Dokter" name="id_dokter" id="input_id_dokter">
											</select>
                      <!-- <input required name="nama_dokter" id="dokter" type="text" class="form-control" readonly>
											<input required name="id_dokter" id="id_dokter" type="hidden" class="form-control" readonly> -->
                    </div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="" class="control-label"><b>Biaya Admin</b></label>
											<div class="input-group">
												<span class="input-group-addon">Rp.</span>
												<input required name="biaya_admin" id="biaya_admin" type="text" class="form-control" readonly>
											</div>
										</div>
										<div class="form-group">
											<label for="" class="control-label"><b>Biaya Id Card</b></label>
											<div class="input-group">
												<span class="input-group-addon">Rp.</span>
												<input required name="biaya_id_card" id="biaya_id_card" type="text" class="form-control" readonly>
											</div>
										</div>
									</div>
                  <div class="col-md-12">
                    <div class="form-group">
                      <button type="submit" class="btn btn-success"><i class="icon-floppy-disk position-left"></i> Simpan</button>
                    </div>
                  </div>
                </form>
							</div>
						</div>
					</div>
				</div>
			</div>

<button type="button" class="btn btn-primary btn-sm" id="btn_modal_pasien" data-toggle="modal" data-target="#modal_pasien" style="display:none;">Launch <i class="icon-play3 position-right"></i></button>
<div id="modal_pasien" class="modal fade">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h6 class="modal-title">Data Pasien</h6>
      </div>

      <div class="modal-body">
        <div class="form-group">
          <div class="input-group">
            <input type="text" id="search_pasien" placeholder="Cari Berdasarkan Nama Pasien dan No RM" class="form-control">
            <span class="input-group-btn">
              <button class="btn bg-primary" type="button"><i class="fa fa-search"></i></button>
            </span>
          </div>
        </div>
        <div class="table-responsive">
          <table class="table table-bordered table-hover table-striped table_data_pasien">
            <thead>
              <tr class="bg-primary">
                <th class="text-center">No RM</th>
                <th class="text-center">Nama</th>
                <th class="text-center">Jenis Kelamin</th>
                <th class="text-center">Tanggal Lahir</th>
              </tr>
            </thead>
            <tbody>

            </tbody>
          </table>
        </div>
				<br>
				<div id="pagination_pasien"></div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-link" id="tutup_data_pasien" data-dismiss="modal"><i class="icon-cross"></i> Tutup</button>
      </div>
    </div>
  </div>
</div>

<script>
	$(document).ready(() => {
		$(`#table-pasien`).DataTable();
    $(`#table-booking`).DataTable();
	})

  function modal_pasien(){
    $('#btn_modal_pasien').click();
  }

  function get_pasien(){
    var search = $('#search_pasien').val();

    $.ajax({
        url : '<?php echo base_url(); ?>resepsionis/registrasi/get_pasien',
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

                    $tr += '<tr style="cursor:pointer;" onclick="klik_pasien('+result[i].id+');">'+
																'<td>'+result[i].no_rm+'</td>'+
																'<td>'+result[i].nama_pasien+'</td>'+
                                '<td>'+result[i].jenis_kelamin+'</td>'+
																'<td>'+result[i].tanggal_lahir+'</td>'+
                            '</tr>';
                }
            }

            $('.table_data_pasien tbody').html($tr);
            pagination_pasien();
        }
    });

    $('#search_pasien').off('keyup').keyup(function(){
        get_pasien();
    });
  }

	function pagination_pasien($selector){
		var jumlah_tampil = '10';

	    if(typeof $selector == 'undefined'){
	        $selector = $(".table_data_pasien tbody tr");
	    }
			window.tp = new Pagination('#pagination_pasien', {
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

	function klik_pasien(id){
		$('#tutup_data_pasien').click();

		    $.ajax({
		        url : '<?php echo base_url(); ?>resepsionis/registrasi/klik_pasien',
		        data : {id:id},
		        type : "POST",
		        dataType : "json",
		        success : function(row){
		          $('#id_pasien').val(id);
		          $('#nama_pasien').val(row['nama_pasien']);
							$('#no_ktp').val(row['no_ktp']);
							$('#tanggal_lahir').val(row['tanggal_lahir']);
							$('#pekerjaan').val(row['pekerjaan']);
							$('#no_telp').val(row['no_telp']);
							$('#nama_wali').val(row['nama_wali']);
							$('#alamat').val(row['alamat']);
							$('#jenis_kelamin').val(row['jenis_kelamin']);
							$('#status_perkawinan').val(row['status_perkawinan']);
							$('#golongan_darah').val(row['golongan_darah']);
							$('#alergi').val(row['alergi']);
							$('#status_operasi').val(row['status_operasi']);

							if (row['status_pasien'] == 'BARU') {
								$('#biaya_admin').val(NumberToMoney('15000'));
								$('#biaya_id_card').val(NumberToMoney('25000'));
							}else if (row['status_pasien'] == 'LAMA') {
								$('#biaya_admin').val(NumberToMoney('15000'));
								$('#biaya_id_card').val(NumberToMoney('0'));
							}
		        }
		    });
	}

	function klik_poli(id){
		 $.ajax({
		        url : '<?php echo base_url(); ?>resepsionis/registrasi/klik_poli',
		        data : {id:id},
		        type : "POST",
		        dataType : "json",
		        success : function(result){
							$tr = "";

	            if(result == "" || result == null){
	                $tr = '<option value="0">Tidak ada Data</option>';
	            }else{
	                for(var i=0; i<result.length; i++){
                  		$tr += '<option value="'+result[i].id_dokter+'">'+result[i].nama_dokter+'</option>';
	                }
	            }
							$('#input_id_dokter').html($tr);
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
