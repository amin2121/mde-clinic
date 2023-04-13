<!DOCTYPE html>
<html lang="en">
<head>
<?php $this->load->view('admin/css'); ?>
<script src="<?php echo base_url('assets/js/plugins/tables/datatables/datatables.min.js'); ?>"></script>
<script type="text/javascript">
$(function() {
	$('.diagnosa').DataTable();
});
</script>
</head>

<body>

<?php $this->load->view('admin/nav'); ?>
<?php $this->load->view('admin/poli/menu'); ?>

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
			<div class="heading-elements">
				<ul class="icons-list">
					<li><button class="btn btn-md btn-warning" type="button" onclick="window.location.href='<?= base_url() ?>poli/poli/antrian_view/<?php echo $pol['poli_id']; ?>'"><i class="icon-arrow-left7"></i> Kembali</button></li>
				</ul>
				</div>
		</div>

		<div class="panel-body">
			<form action="<?= base_url('poli/poli/proses_tindakan') ?>" method="POST" enctype="multipart/form-data" id="form-tambah-tindakan">
				<div class="tabbable">
					<ul class="nav nav-tabs bg-primary nav-tabs-component nav-justified">
						<li class="active"><a href="#data-diri-pasien" data-toggle="tab">Data Diri Pasien</a></li>
						<li><a href="#diagnosa-keluhan" data-toggle="tab">Diagnosa & Keluhan</a></li>
						<li><a href="#resep" data-toggle="tab">Resep</a></li>
					</ul>

					<div class="tab-content">
						<div class="tab-pane active" id="data-diri-pasien">
							<fieldset class="content-group">
								<legend>Data Diri Pasien</legend>
								<div class="row">
									<div class="col-sm-12">
										<div class="row">
											<div class="col-sm-6">
												<div class="form-group">
													<label class="control-label"><b>No Invoice</b></label>
													<input type="text" class="form-control" name="no_invoice" readonly="" value="<?php echo $invoice; ?>">
													<input type="hidden" name="id_registrasi" value="<?php echo $id_registrasi; ?>">
												</div>
												<div class="form-group">
													<label class="control-label"><b>No RM</b></label>
													<input type="text" class="form-control" name="no_rm" readonly="" value="<?php echo $pas['no_rm']; ?>">
												</div>
												<div class="form-group">
													<label class="control-label"><b>Nama Pasien</b></label>
													<input type="hidden" class="form-control" name="id_pasien" readonly="" value="<?php echo $pas['id']; ?>">
													<input type="text" class="form-control" name="nama_pasien" readonly="" value="<?php echo $pas['nama_pasien']; ?>">
												</div>
												<div class="form-group">
													<label class="control-label"><b>Usia</b></label>
													<input type="text" class="form-control" name="usia_pasien" readonly="" value="<?php echo $pas['umur']; ?>">
												</div>
											</div>
											<div class="col-sm-6">
												<div class="form-group">
													<label class="control-label"><b>Jenis kelamin</b></label>
													<input type="text" class="form-control" name="jenis_kelamin" readonly="" value="<?php echo $pas['jenis_kelamin']; ?>">
												</div>
												<div class="form-group">
													<label class="control-label"><b>Poli</b></label>
													<input type="text" class="form-control" name="poli" readonly="" value="<?php echo $pol['poli_nama']; ?>">
													<input type="hidden" class="form-control" name="id_poli" readonly="" value="<?php echo $pol['poli_id']; ?>">
												</div>
												<div class="form-group">
													<label class="control-label"><b>Nama Dokter</b></label>
													<input type="text" class="form-control" name="nama_dokter" value="<?php echo $pol['nama_dokter']; ?>">
													<input type="hidden" class="form-control" name="id_dokter" value="<?php echo $pol['id_dokter']; ?>">
												</div>
											</div>
										</div>
									</div>
								</div>
							</fieldset>
						</div>

						<div class="tab-pane" id="diagnosa-keluhan">
							<fieldset>
								<legend>Diagnosa & Keluhan</legend>

								<div class="row">
									<div class="col-sm-6">
										<div class="form-group">
											<label class="control-label"><b>Keluhan</b></label>
											<input type="text" class="form-control" value="" placeholder="Keluhan" name="keluhan">
										</div>
										<div class="form-group">
											<label class="control-label"><b>Diagnosa</b></label>
											<input type="text" class="form-control" value="" placeholder="Diagnosa" name="diagnosa">
										</div>
									</div>
									<div class="col-sm-6">
										<div class="checkbox checkbox-switchery">
											<label>
												<input type="hidden" name="status_jasa_medis" value="0">
												<input type="checkbox" class="switchery-primary" value="1" name="status_jasa_medis" id="check-jasa-medis" onchange="check_jasa_medis(this)">
												<b>Tambah Jasa Medis</b>
											</label>
										</div>

										<div id="form_jasa_medis" style="display: none;">
											<div class="form-group">
												<label for="" class="control-label"><b>Jasa Medis</b></label>
												<select name="id_pegawai" id="jasa_medis" class="select-search-primary">
													<?php foreach ($perawat as $pp) : ?>
														<option value="<?php echo $pp['id_perawat']; ?>"><?php echo $pp['nama_perawat']; ?></option>
													<?php endforeach ?>
												</select>
											</div>
											<div class="form-group">
												<label class="control-label"><b>Total Jasa Medis</b></label>
												<div class="input-group">
													<span class="input-group-btn">
														<span class="input-group-addon">Rp.</span>
													</span>
													<input type="text" class="form-control" placeholder="Total Jasa Medis" id="total_jasa_medis" name="total_jasa_medis" onkeyup="FormatCurrency(this)">
												</div>
											</div>
										</div>
									</div>
									<div class="col-sm-12">
										<input type="hidden" id="number_tindakan" name="" value="0">
										<div class="table-responsive form-group">
											<table class="table table-striped table-hover table-bordered table_tambah_tindakan">
												<thead>
													<tr class="bg-success">
														<th class="text-center">Tindakan</th>
														<th class="text-center">Jumlah</th>
														<th class="text-center">Diskon</th>
														<th class="text-center">Tarif</th>
														<th class="text-center">Sub Total</th>
														<th class="text-center">Action</th>
													</tr>
												</thead>
												<tbody>
												</tbody>
											</table>
										</div>
										<div class="form-group">
					                    	<button type="button" class="btn btn-warning m-b-0" onclick="tambah_tindakan();"><i class="fa fa-plus position-left"></i> Tambah</button>
					                    </div>
									</div>
									<div class="col-sm-6">
										<div class="form-group">
											<label class="control-label"><b>Total Tarif Tindakan</b></label>
											<input type="text" class="form-control" id="total_tarif_tindakan" value="0" name="total_tarif_tindakan" readonly>
										</div>
										<!-- <div class="alert alert-primary" role="alert">

										</div> -->
										<div class="form-group">
											<label class="control-label"><b>Upload Foto Before</b></label>

											<div class="row">
													<div class="col-sm-4">
															<input type="file" class="form-control dropify" value="" placeholder="Upload Foto Before" name="before[]" data-max-file-size="60M" data-show-remove="false" >
													</div>
													<div class="col-sm-4">
															<input type="file" class="form-control dropify" value="" placeholder="Upload Foto Before" name="before[]" data-max-file-size="60M" data-show-remove="false" >
													</div>
													<div class="col-sm-4">
															<input type="file" class="form-control dropify" value="" placeholder="Upload Foto Before" name="before[]" data-max-file-size="60M" data-show-remove="false" >
													</div>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label"><b>Upload Foto After</b></label>
											<div class="row">
												<div class="col-sm-4">
														<input type="file" class="form-control dropify" value="" placeholder="Upload Foto After" name="after[]" data-max-file-size="60M" data-show-remove="false" >
												</div>
												<div class="col-sm-4">
														<input type="file" class="form-control dropify" value="" placeholder="Upload Foto After" name="after[]" data-max-file-size="60M" data-show-remove="false" >
												</div>
												<div class="col-sm-4">
														<input type="file" class="form-control dropify" value="" placeholder="Upload Foto After" name="after[]" data-max-file-size="60M" data-show-remove="false" >
												</div>
											</div>
										</div>
									</div>
								</div>
							</fieldset>
						</div>

						<div class="tab-pane" id="resep">
							<fieldset title="3">
								<legend>Resep</legend>
								<div class="row">
									<div class="col-sm-12">
										<input type="text" value="0" id="number_obat" hidden="">
										<div class="table-responsive form-group">
											<table class="table table-striped table-hover table-bordered table_tambah_obat">
												<thead>
													<tr class="bg-success">
														<th class="text-center">Nama Obat</th>
														<th class="text-center">Jumlah Obat</th>
														<th class="text-center">Aturan Minum</th>
														<th class="text-center">Harga</th>
														<th class="text-center">Sub Total</th>
														<th class="text-center">Action</th>
													</tr>
												</thead>
												<tbody>
												</tbody>
											</table>
										</div>
										<div class="row">
											<div class="col-sm-6">
												<div class="form-group">
													<button class="btn btn-warning" type="button" id="tambah-obat" onclick="tambah_obat()"><i class="fa fa-plus position-left"></i>Tambah</button>
												</div>
												<div class="form-group">
													<label for="" class="control-label"><b>Total Harga Obat</b></label>
													<div class="input-group">
														<span class="input-group-btn">
															<span class="input-group-addon">Rp.</span>
														</span>
														<input type="text" class="form-control rupiah" readonly value="0" id="total_harga_obat" name="total_harga_resep">
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<button class="btn btn-md btn-icon bg-success" style="margin-top: 1em;" onclick="submit_tambah_tindakan()"><i class="icon-floppy-disk position-left"></i> Simpan</button>
							</fieldset>
						</div>

					</div>
				</div>
			</form>
		</div>
	</div>

	<button type="button" class="btn btn-primary btn-sm" id="btn_modal_tindakan" data-toggle="modal" data-target="#modal_tindakan" style="display:none;">Launch <i class="icon-play3 position-right"></i></button>
	<div id="modal_tindakan" class="modal fade">
	  <div class="modal-dialog modal-lg">
	    <div class="modal-content">
	      <div class="modal-header bg-primary">
	        <button type="button" class="close" data-dismiss="modal">&times;</button>
	        <h6 class="modal-title">Data Tarif Tindakan</h6>
	      </div>
	      <div class="modal-body">
	        <div class="form-group">
	          <div class="input-group">
	            <input type="text" id="search_tindakan" placeholder="Cari Berdasarkan Nama Tindakan" class="form-control">
	            <span class="input-group-btn">
	              <button class="btn bg-primary" type="button"><i class="fa fa-search"></i></button>
	            </span>
	          </div>
	        </div>
	        <div class="table-responsive">
	          <table class="table table-bordered table-hover table-striped table_data_tindakan">
	            <thead>
	              <tr class="bg-primary">
	                <th class="text-center">No</th>
	                <th class="text-center">Nama Tindakan</th>
	                <th class="text-center">Tarif</th>
	              </tr>
	            </thead>
	            <tbody>

	            </tbody>
	          </table>
	        </div>
					<br>
					<div id="pagination_tindakan"></div>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-link" id="tutup_data_tindakan" data-dismiss="modal"><i class="icon-cross position-left"></i>Tutup</button>
	      </div>
	    </div>
	  </div>
	</div>

	<button type="button" class="btn btn-primary btn-sm" id="btn_modal_obat" data-toggle="modal" data-target="#modal_obat" style="display:none;">Launch <i class="icon-play3 position-right"></i></button>
	<div id="modal_obat" class="modal fade">
	  <div class="modal-dialog modal-lg">
	    <div class="modal-content">
	      <div class="modal-header bg-primary">
	        <button type="button" class="close" data-dismiss="modal">&times;</button>
	        <h6 class="modal-title">Data Tarif Obat</h6>
	      </div>
	      <div class="modal-body">
	        <div class="form-group">
	          <div class="input-group">
	            <input type="text" id="search_obat" placeholder="Cari Berdasarkan Nama Obat" class="form-control">
	            <span class="input-group-btn">
	              <button class="btn bg-primary" type="button"><i class="fa fa-search"></i></button>
	            </span>
	          </div>
	        </div>
	        <div class="table-responsive">
	          <table class="table table-bordered table-hover table-striped table_data_obat">
	            <thead>
	              <tr class="bg-primary">
	                <th class="text-center">No</th>
	                <th class="text-center">Nama Obat</th>
	                <th class="text-center">Stok</th>
					<th class="text-center">Harga Jual</th>
	              </tr>
	            </thead>
	            <tbody>

	            </tbody>
	          </table>
	        </div>
			<br>
			<div id="pagination_obat"></div>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-link" id="tutup_data_obat" data-dismiss="modal"><i class="icon-cross position-left"></i>Tutup</button>
	      </div>
	    </div>
	  </div>
	</div>

<script>
	$(document).ready(function() {
		$('.dropify').dropify();
	})

	function check_jasa_medis(e){
		if (e.checked == true) {
			$('#form_jasa_medis').show();
		}else {
			$('#form_jasa_medis').hide();
		}
	}

	function modal_tindakan(){
		$('#btn_modal_tindakan').click();
	}

	function get_tindakan(number){
	    var search = $('#search_tindakan').val();

	    $.ajax({
	        url : '<?php echo base_url(); ?>poli/poli/get_tindakan',
	        data : {search:search},
	        type : "POST",
	        dataType : "json",
	        success : function(result){
	            $tr = "";

	            if(result == "" || result == null){
	                $tr = "<tr><td colspan='3' style='text-align:center;'><b>Data tidak ditemukan</b></td></tr>";
	            }else{
	                var no = 0;
	                for(var i=0; i<result.length; i++){
	                    no++;

	                    $tr += '<tr style="cursor:pointer;" onclick="klik_tindakan(&quot;'+result[i].tarif_id+'&quot;, '+number+');">'+
									'<td>'+no+'</td>'+
									'<td>'+result[i].tarif_nama+'</td>'+
	                                '<td>Rp. '+NumberToMoney(result[i].tarif_harga)+'</td>'+
	                            '</tr>';
	                }
	            }

	            $('.table_data_tindakan tbody').html($tr);
	            paging_tindakan();
	        }
	    });
	    $('#search_tindakan').off('keyup').keyup(function(){
	        get_tindakan(number);
	    });
	}

	function paging_tindakan($selector){
		var jumlah_tampil = '10';

	    if(typeof $selector == 'undefined') {
	        $selector = $(".table_data_tindakan tbody tr");
	    }
		
		window.tp = new Pagination('#pagination_tindakan', {
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

	function klik_tindakan(id, number){
	    $('#tutup_data_tindakan').click();

	    $.ajax({
	        url : '<?php echo base_url(); ?>poli/poli/klik_tindakan',
	        data : {id:id},
	        type : "POST",
	        dataType : "json",
	        success : function(row){
	          	$('#tarif_id_'+number).val(id);
	          	$('#tarif_nama_'+number).val(row['tarif_nama']);
				$('#tarif_harga_'+number).val(NumberToMoney(row['tarif_harga']));
				$('#jumlah_'+number).val(NumberToMoney('0'));
				$('#sub_total_'+number).val(NumberToMoney('0'));
	        }
	    });
	}

	function hitung_diskon(number){
		var diskon = $('#diskon_'+number).val();
		diskon = diskon.split(',').join('');
		if (diskon == '') {
			diskon = 0;
		}

		var tarif_harga = $('#tarif_harga_'+number).val();
    	tarif_harga = tarif_harga.split(',').join('');
		if (tarif_harga == '') {
			tarif_harga = 0;
		}

		var jumlah = $('#jumlah_'+number).val();
    	jumlah = jumlah.split(',').join('');
		if (jumlah == '') {
			jumlah = 0;
		}

		var jual = jumlah * tarif_harga;
		var hitung_diskon = diskon / 100;
		var hitung_diskon_2 =  jual - (jual * hitung_diskon);
		var harga_jual = Math.ceil(hitung_diskon_2);
		$('#sub_total_'+number).val(NumberToMoney(harga_jual));
	}

	function hitung_jumlah(number){
		var tarif_harga = $('#tarif_harga_'+number).val();
    	tarif_harga = tarif_harga.split(',').join('');
		if (tarif_harga == '') {
			tarif_harga = 0;
		}

		var jumlah = $('#jumlah_'+number).val();
    	jumlah = jumlah.split(',').join('');
		if (jumlah == '') {
			jumlah = 0;
		}

		var total = jumlah * tarif_harga;
		$('#sub_total_'+number).val(NumberToMoney(total));
	}

	function hitung_total_tarif(){
	    var total = 0;
	    $('.sub_total_all').each(function(idx, elm){
	      var f = elm.value;
	  			f = f.split(',').join('');
	  			if (f == '') {
	  				f = 0;
	  			}
	    total += parseFloat(f);
	    });

	    $('#total_tarif_tindakan').val(NumberToMoney(total));
  }

	function tambah_tindakan(){
	    var jml_tr = $('#number_tindakan').val();
	    var i = parseInt(jml_tr) + 1;

	    $menu =
				`<tr>
					<td>
						<div class="form-group">
							<div class="input-group">
								<span class="input-group-btn">
									<button class="btn bg-primary btn-icon" type="button" onclick="modal_tindakan(); get_tindakan(${i});"><i class="icon-search4"></i></button>
								</span>
								<input type="text" class="form-control" placeholder="Cari Tarif" readonly id="tarif_nama_${i}" name="nama_tarif[]">
								<input type="hidden" id="tarif_id_${i}" name="id_tarif[]">
							</div>
						</div>
					</td>
					<td>
						<div class="form-group">
							<input type="text" class="form-control jumlah_all" id="jumlah_${i}" name="jumlah[]" onkeyup="FormatCurrency(this); hitung_jumlah(${i}); hitung_diskon(${i}); hitung_total_tarif();">
						</div>
					</td>
					<td>
						<div class="form-group">
							<div class="input-group">
								<span class="input-group-btn">
									<span class="input-group-addon">%</span>
								</span>
								<input type="text" class="form-control" size="5" value="0" id="diskon_${i}" name="diskon[]" onkeyup="FormatCurrency(this); hitung_diskon(${i}); hitung_total_tarif();">
							</div>
						</div>
					</td>
					<td>
						<div class="form-group">
							<input type="text" class="form-control" readonly id="tarif_harga_${i}" name="harga_tarif[]">
						</div>
					</td>
					<td>
						<div class="form-group">
							<input type="text" class="form-control sub_total_all" id="sub_total_${i}" name="sub_total[]">
						</div>
					</td>
					<td>
						<button class="btn btn-md btn-icon btn-danger" onclick="hapus_row_tindakan(this, ${i});"><i class="icon-bin"></i></button>
					</td>
				</tr>`;

	    $('.table_tambah_tindakan').append($menu);
	    $('#number_tindakan').val(i);
	}

	function hapus_row_tindakan(btn, id){
    	var row = btn.parentNode.parentNode;
    	row.parentNode.removeChild(row);
		hitung_total_tarif();
  }

	function modal_obat(){
		$('#btn_modal_obat').click();
	}

 	function get_obat(number){
	    var search = $('#search_obat').val();

	    $.ajax({
	        url : '<?php echo base_url(); ?>poli/poli/get_obat',
	        data : {search},
	        type : "POST",
	        dataType : "json",
	        success : function(result) {
	            $tr = "";

	            if(result == "" || result == null) {
	                $tr = "<tr><td colspan='3' style='text-align:center;'><b>Data tidak ditemukan</b></td></tr>";
	            }else{
	                var no = 0;
	                for(var i=0; i<result.length; i++) {
	                    no++;

	                    $tr += '<tr style="cursor:pointer;" onclick="klik_obat('+result[i].id_barang+', '+number+', '+result[i].stok+', '+result[i].id_jenis_barang+');">'+
									'<td>'+no+'</td>'+
									'<td>'+result[i].nama_barang+'</td>'+
									'<td>'+NumberToMoney(result[i].stok)+'</td>'+
	                                '<td>Rp. '+NumberToMoney(result[i].harga_jual)+'</td>'+
	                            '</tr>';
	                }
	            }

	            $('.table_data_obat tbody').html($tr);
	            paging_obat();
	        }
	    });

	    $('#search_obat').off('keyup').keyup(function(){
	        get_obat(number);
	    });
	}

	function paging_obat($selector){
		var jumlah_tampil = '10';

	    if(typeof $selector == 'undefined') {
	        $selector = $(".table_data_obat tbody tr");
	    }
		
		window.tp = new Pagination('#pagination_obat', {
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

	function klik_obat(id, number, stok, id_jenis_barang) {
		if(stok == 0) {
			if(id_jenis_barang == 18) {
				$('#tutup_data_obat').click();
			    $.ajax({
			        url : '<?php echo base_url(); ?>poli/poli/klik_obat',
			        data : {id:id},
			        type : "POST",
			        dataType : "json",
			        success : function(row){
								$('#id_barang_'+number).val(row['id_barang']);
								$('#nama_barang_'+number).val(row['nama_barang']);
								$('#jenis_barang_'+number).val(row['nama_jenis_barang']);
								$('#jumlah_obat_'+number).val(NumberToMoney('0'));
								$('#harga_obat_'+number).val(NumberToMoney(row['harga_jual']));
								$('#sub_total_obat_'+number).val(NumberToMoney('0'));
			        }
			    });	
			} else {
				alert('Stok Obat Kosong');
			}
		} else {
	    	$('#tutup_data_obat').click();
		    $.ajax({
		        url : '<?php echo base_url(); ?>poli/poli/klik_obat',
		        data : {id:id},
		        type : "POST",
		        dataType : "json",
		        success : function(row){
							$('#id_barang_'+number).val(row['id_barang']);
							$('#nama_barang_'+number).val(row['nama_barang']);
							$('#jenis_barang_'+number).val(row['nama_jenis_barang']);
							$('#jumlah_obat_'+number).val(NumberToMoney('0'));
							$('#harga_obat_'+number).val(NumberToMoney(row['harga_jual']));
							$('#sub_total_obat_'+number).val(NumberToMoney('0'));
		        }
		    });
		}
	}

	function hitung_jumlah_obat(number){
		var harga_obat = $('#harga_obat_'+number).val();
    	harga_obat = harga_obat.split(',').join('');
		if (harga_obat == '') {
			harga_obat = 0;
		}

		var jumlah_obat = $('#jumlah_obat_'+number).val();
    	jumlah_obat = jumlah_obat.split(',').join('');
		if (jumlah_obat == '') {
			jumlah_obat = 0;
		}

		var total = jumlah_obat * harga_obat;
		$('#sub_total_obat_'+number).val(NumberToMoney(total));
	}

	function hitung_total_obat(){
	    var total = 0;
	    $('.sub_total_obat_all').each(function(idx, elm){
	      var f = elm.value;
	  			f = f.split(',').join('');
	  			if (f == '') {
	  				f = 0;
	  			}
	    total += parseFloat(f);
	    });

    	$('#total_harga_obat').val(NumberToMoney(total));
  	}

	function tambah_obat(){
	    var jml_tr = $('#number_obat').val();
	    var i = parseInt(jml_tr) + 1;

	    $menu =
				`<tr>
					<td>
						<div class="form-group">
							<div class="input-group">
								<span class="input-group-btn">
									<button class="btn bg-primary btn-icon" type="button" onclick="modal_obat(); get_obat(${i});"><i class="icon-search4"></i></button>
								</span>
								<input type="text" class="form-control" placeholder="Cari Obat" readonly id="nama_barang_${i}" name="nama_barang[]">
								<input type="hidden" class="form-control" id="id_barang_${i}" name="id_barang[]">
								<input type="hidden" class="form-control" id="jenis_barang_${i}" name="jenis_barang[]">
							</div>
						</div>
					</td>
					<td>
						<div class="form-group">
							<input type="text" class="form-control jumlah-obat" id="jumlah_obat_${i}" name="jumlah_obat[]" onkeyup="FormatCurrency(this); hitung_jumlah_obat(${i}); hitung_total_obat();">
						</div>
					</td>
					<td class="text-center">
						<div class="form-group">
							<input type="text" class="form-control" id="aturan_minum_${i}" name="aturan_minum[]">
						</div>
					</td>
					<td>
						<div class="form-group">
							<div class="input-group">
								<span class="input-group-btn">
									<span class="input-group-addon">Rp.</span>
								</span>
								<input type="text" class="form-control" id="harga_obat_${i}" readonly name="harga_obat[]">
							</div>
						</div>
					</td>
					<td>
						<div class="form-group">
							<div class="input-group">
								<span class="input-group-btn">
									<span class="input-group-addon">Rp.</span>
								</span>
								<input type="text" class="form-control sub_total_obat_all" id="sub_total_obat_${i}" readonly name="sub_total_obat[]">
							</div>
						</div>
					</td>
					<td>
						<button class="btn btn-md btn-icon btn-danger" onclick="hapus_row_obat(this, ${i});"><i class="icon-bin"></i></button>
					</td>
				</tr>`;

	    $('.table_tambah_obat').append($menu);
	    $('#number_obat').val(i);
	}

	function hapus_row_obat(btn, id) {
	    var row = btn.parentNode.parentNode;
	    row.parentNode.removeChild(row);
		hitung_total_obat();
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
