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

            <div class="col-sm-4">
              <div class="row">
                  <div class="col-sm-3" style="margin-top: 0.5em !important;">
                    <p>Filter : </p>
                  </div>
                  <div class="col-sm-9" style="margin-top: 0.5em !important;">
                    <div class="form-group">
                      <label class="radio-inline">
                        <input type="radio" name="radio-inline-left" class="styled" checked="checked" id="radio-filter-tanggal" onchange="pilih_filter()">
                        Tanggal
                      </label>

                      <label class="radio-inline">
                        <input type="radio" name="radio-inline-left" class="styled" id="radio-filter-nama" onchange="pilih_filter()">
                        Nama Pasien / Invoice
                      </label>
                    </div>
                  </div>
              </div>
            </div>
            
            <div class="col-sm-8">
                <div class="row" id="row_filter_tanggal">
                  <div class="col-sm-5">
                    <div class="form-group">
                        <input type="text" class="form-control input-tgl" autocomplete="off" name="tgl_dari" id="input-tgl-dari" placeholder="Tanggal Dari">
                    </div>
                  </div>
                  <div class="col-sm-5">
                    <div class="form-group">
                        <input type="text" class="form-control input-tgl" name="tgl_sampai" id="input-tgl-sampai" autocomplete="off" placeholder="Tanggal Sampai">
                    </div>
                  </div>
                  <div class="col-sm-2">
                    <button class="btn btn-icon btn-info" onclick="get_data_pembayaran()"><i class="fa fa-search"></i> Cari</button>
                  </div>
                </div>
                <div class="row" id="row_filter_nama" style="display: none;">
                  <div class="col-sm-12">
                      <div class="form-group">
                          <div class="input-group">
                            <input type="text" class="form-control" id="input-search" onkeyup="get_data_pembayaran()" placeholder="Cari Berdasarkan Invoice atau Nama Pasien">
                            <span class="input-group-btn">
                              <button class="btn bg-primary" type="button"><i class="fa fa-search"></i></button>
                            </span>
                          </div>
                      </div>
                  </div>
                </div>
            </div>

          </div>

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
								<table class="table table-bordered table-hover table-striped table_data_pembayaran">
									<thead>
										<tr class="bg-success">
											<th class="text-center">No</th>
											<th class="text-center">Invoice</th>
											<th class="text-center">Nama Pasien</th>
											<th class="text-center">Nama Dokter</th>
                      <th class="text-center">Tanggal</th>
                      <th class="text-center">Aksi</th>
										</tr>
									</thead>
									<tbody>

									</tbody>
								</table>
							</div>

					</div>
				</div>
			</div>

      <button type="button" class="btn btn-primary btn-sm" id="btn_modal_pembayaran" data-toggle="modal" data-target="#modal_pembayaran" style="display:none;">Launch <i class="icon-play3 position-right"></i></button>
      <div id="modal_pembayaran" class="modal fade">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header bg-primary">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h6 class="modal-title">Data Pembayaran Pasien</h6>
            </div>

            <div class="modal-body">
              <div class="tabbable">
								<ul class="nav nav-tabs nav-tabs-component">
									<li class="active"><a href="#pembayaran" data-toggle="tab">Pembayaran</a></li>
									<li><a href="#data_tindakan" data-toggle="tab">Data Tindakan</a></li>
                  <li><a href="#data_resep" data-toggle="tab">Data Resep</a></li>
								</ul>

								<div class="tab-content" style="margin-bottom: 2em !important;">
									<div class="tab-pane active" id="pembayaran">
										<form action="" id="form_pembayaran" method="post">
                      <div class="form-group">
                        <input type="hidden" name="id_registrasi" id="id_registrasi" value="">
                        <input type="hidden" name="invoice" id="invoice" value="">
                        <input type="hidden" name="id_pasien" id="id_pasien" value="">
                        <input type="hidden" name="nama_pasien" id="nama_pasien" value="">
                        <input type="hidden" name="id_poli" id="id_poli" value="">
                        <input type="hidden" name="id_dokter" id="id_dokter" value="">
                        <input type="hidden" name="nama_dokter" id="nama_dokter" value="">
                      </div>
                        <div class="form-group">
                          <label class="control-label"><b>Biaya Tindakan</b></label>
                          <div class="input-group">
    												<span class="input-group-addon">Rp.</span>
    												<input required name="biaya_tindakan" id="biaya_tindakan" type="text" class="form-control" readonly>
    											</div>
                        </div>
                        <div class="form-group">
                          <label class="control-label"><b>Biaya Resep</b></label>
                          <div class="input-group">
    												<span class="input-group-addon">Rp.</span>
    												<input required name="biaya_resep" id="biaya_resep" type="text" class="form-control" readonly>
    											</div>
                        </div>
                        <div class="form-group">
                          <label class="control-label"><b>Total</b></label>
                          <div class="input-group">
    												<span class="input-group-addon">Rp.</span>
    												<input required name="total_invoice" id="total" type="text" class="form-control" readonly>
    											</div>
                        </div>
                        <div class="form-group">
                          <label class="control-label"><b>Metode Pembayaran</b></label>
                          <select class="bootstrap-select" data-width="100%" data-placeholder="Pilih Metode Pembayaran" name="metode_pembayaran" onchange="klik_metode_pembayaran(this.value);">
    												<option value="Cash">Cash</option>
                            <option value="Transfer">Transfer</option>
                            <option value="EDC">EDC</option>
                          </select>
                        </div>
                        <div id="form_bank" style="display: none;">
                          <div class="form-group">
                            <label class="control-label"><b>Nama Bank</b></label>
                            <select class="bootstrap-select" data-width="100%" name="bank">
      												<option value="BNI">BNI</option>
                              <option value="MANDIRI">MANDIRI</option>
                              <option value="BRI">BRI</option>
                              <option value="BCA">BCA</option>
                            </select>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="control-label"><b>Bayar</b></label>
                          <div class="input-group">
    												<span class="input-group-addon">Rp.</span>
    												<input required name="bayar" id="bayar" type="text" onkeyup="FormatCurrency(this); get_bayar();" class="form-control">
    											</div>
                        </div>
                        <div class="form-group">
                          <label class="control-label"><b>Kembali</b></label>
                          <div class="input-group">
    												<span class="input-group-addon">Rp.</span>
    												<input required name="kembali" id="kembali" type="text" class="form-control" readonly>
    											</div>
                        </div>
                    </form>
									</div>

									<div class="tab-pane" id="data_tindakan">
                    <div class="table-responsive">
                      <div class="form-group">
                        <label class="control-label"><b>Nama Dokter</b></label>
                        <input required id="nama_dokter_tindakan" type="text" class="form-control" readonly>
                      </div>
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
              <button type="button" id="btn_proses_bayar" disabled class="btn btn-success"><i class="icon-floppy-disk position-left"></i> Proses</button>
              <button type="button" class="btn btn-link" id="tutup_data_pembayaran" data-dismiss="modal"><i class="icon-cross position-left"></i> Tutup</button>
            </div>
          </div>
        </div>
      </div>


</div>
</div>
<!-- /main content -->

</div>
<!-- /page content -->

<!-- Modal Hapus Antrean -->
<div id="modal_hapus_pembayaran" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-danger">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h5 class="modal-title">Hapus Pembayaran</h5>
      </div>

      <form action="<?= base_url('resepsionis/pembayaran/hapus_pembayaran') ?>" method="POST">
        <div class="modal-body">
          <div class="alert alert-danger no-border">
            <p>Apakah Anda Ingin <span class="text-semibold">Menghapus</span> Pembayaran Dengan Invoice <span id="span_invoice"></span> ?</p>
            </div>

          <input type="hidden" name="id_registrasi" id="input_id_registrasi">
        </div>

        <div class="modal-footer">
          <button class="btn btn-link" data-dismiss="modal"><i class="icon-cross position-left"></i> Keluar</button>
          <button class="btn btn-danger" type="submit"><i class="icon-bin position-left"></i> Hapus</button>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- End Modal Hapus Barang -->

</div>
<script type="text/javascript">

$(document).ready(function(){
    $('.input-tgl').datepicker({
        dateFormat : 'dd-mm-yy',
        autoclose: true,
        language: 'fr',
        orientation: 'bottom auto',
        todayBtn: 'linked',
        todayHighlight: true
    });

    get_data_pembayaran();
});

function pilih_filter() {
    if($(`#radio-filter-nama`).is(':checked')) {
        $(`#row_filter_nama`).show();
        $(`#row_filter_tanggal`).hide();
        $(`#input-tgl-dari`).val('');
        $(`#input-tgl-sampai`).val('');
    } else if($(`#radio-filter-tanggal`).is(':checked')) {
        $(`#row_filter_nama`).hide();
        $(`#row_filter_tanggal`).show();
        $(`#input-search`).val('');
    }
}

$('#btn_proses_bayar').click(function(){
      $('#tutup_data_pembayaran').click();
			$('#popup_load').show();
      $.ajax({
          url : '<?php echo base_url(); ?>resepsionis/pembayaran/simpan_pembayaran',
          data : $('#form_pembayaran').serialize(),
          type : "POST",
          dataType : "json",
          success : function(row){
							var id_registrasi = row.id_registrasi;
							window.location.href = '<?php echo base_url(); ?>resepsionis/pembayaran';
							var win = window.open('<?php echo base_url(); ?>resepsionis/pembayaran/cetak_nota/'+id_registrasi, '_blank','location=yes,height=570,width=520,scrollbars=yes,status=yes');
          }
      });
  });

function klik_metode_pembayaran(value){
  if (value == 'Transfer' || value == 'EDC') {
    $('#form_bank').show();
  }else {
    $('#form_bank').hide();
  }
}

function get_bayar(){
    var total = $('#total').val();
		total = total.split(',').join('');
    var bayar = $('#bayar').val();
    bayar = bayar.split(',').join('');

    if(bayar == ""){
        bayar = 0;
    }

    if(parseFloat(bayar) < parseFloat(total)){
        var kembali = parseFloat(bayar) - parseFloat(total);
        $('#kembali').val(formatNumber(kembali));
				$('#btn_proses_bayar').attr('disabled','disabled');
    }else{
        var kembali = parseFloat(bayar) - parseFloat(total);
        $('#kembali').val(formatNumber(kembali));
				$('#btn_proses_bayar').removeAttr('disabled');
    }
}

function get_data_pembayaran() {
  let search = $(`#input-search`).val();
  let tanggal_dari = $(`#input-tgl-dari`).val();
  let tanggal_sampai = $(`#input-tgl-sampai`).val();

  $.ajax({
      url : '<?php echo base_url(); ?>resepsionis/pembayaran/get_data_pembayaran',
      type : "POST",
      data : {'search' : `${search}`, 'tanggal_dari' : `${tanggal_dari}`, 'tanggal_sampai' : `${tanggal_sampai}`},
      dataType : "json",
      success : function(result){
          $tr = "";

          if(result == "" || result == null){
              $tr = "<tr><td colspan='6' style='text-align:center;'><b>Data tidak ditemukan</b></td></tr>";
          }else{
              var no = 0;
              for(var i=0; i<result.length; i++){
                  no++;

                  $tr += `<tr style="cursor:pointer;">
                              <td class="text-center" onclick="modal_pembayaran(${result[i].id});">${no}</td>
                              <td class="text-center" onclick="modal_pembayaran(${result[i].id});">${result[i].invoice}</td>
                              <td class="text-center" onclick="modal_pembayaran(${result[i].id});">${result[i].nama_pasien}</td>
                              <td class="text-center" onclick="modal_pembayaran(${result[i].id});">${result[i].nama_dokter}</td>
                              <td class="text-center" onclick="modal_pembayaran(${result[i].id});">${result[i].tanggal}</td>
                              <td class="text-center"><a href="#" class="btn btn-xs btn-icon btn-danger" onclick="hapus_pembayaran(${result[i].id}, '${result[i].invoice}')"><i class="icon-trash position-left"></i> Hapus</a></td></td>
                          </tr>`;
              }
          }

          $('.table_data_pembayaran tbody').html($tr);
      }
  });
}

function hapus_pembayaran(id_registrasi, invoice) {
    $(`#modal_hapus_pembayaran`).modal('toggle');
    $(`#input_id_registrasi`).val(id_registrasi);
    $(`#span_invoice`).text(invoice);
}

function modal_pembayaran(id_registrasi){
  $('#btn_modal_pembayaran').click();

  get_pembayaran(id_registrasi);
  get_tindakan(id_registrasi);
  get_resep(id_registrasi);
}


function get_pembayaran(id_registrasi){
  $.ajax({
      url : '<?php echo base_url(); ?>resepsionis/pembayaran/get_pembayaran',
      data : {id_registrasi:id_registrasi},
      type : "POST",
      dataType : "json",
      success : function(data){
        var total = parseFloat(data.biaya_admin) + parseFloat(data.biaya_id_card) + parseFloat(data.total_tarif_tindakan) + parseFloat(data.total_harga_resep);
        $('#id_registrasi').val(data.id);
        $('#invoice').val(data.invoice);
        $('#id_pasien').val(data.id_pasien);
        $('#nama_pasien').val(data.nama_pasien);
        $('#id_poli').val(data.id_poli);
        $('#id_dokter').val(data.id_dokter);
        $('#nama_dokter').val(data.nama_dokter);
        $('#biaya_tindakan').val(NumberToMoney(data.total_tarif_tindakan));
        $('#biaya_resep').val(NumberToMoney(data.total_harga_resep));
        $('#total').val(NumberToMoney(total));
        $('#bayar').val('0');
        $('#kembali').val('0');
      }
  });
}

function get_tindakan(id_registrasi){
  $.ajax({
      url : '<?php echo base_url(); ?>resepsionis/pembayaran/get_tindakan',
      data : {id_registrasi:id_registrasi},
      type : "POST",
      dataType : "json",
      success : function(data){
          $('#nama_dokter_tindakan').val(data['row'].nama_dokter);
          $('#keluhan').val(data['row'].keluhan);
          $('#diagnosa').val(data['row'].diagnosa);

          $tr = "";
          var total = 0;

          if(data['result'] == "" || data['result'] == null){
              $tr = "<tr><td colspan='5' style='text-align:center;'><b>Data tidak ditemukan</b></td></tr>";
          }else{
              var no = 0;
              for(var i=0; i<data['result'].length; i++){
                  no++;

                  $tr += '<tr>'+
                              '<td>'+no+'</td>'+
                              '<td>'+data['result'][i].nama_tarif+'</td>'+
                              '<td>'+NumberToMoney(data['result'][i].jumlah)+'</td>'+
                              '<td>Rp. '+NumberToMoney(data['result'][i].harga_tarif)+'</td>'+
                              '<td>Rp. '+NumberToMoney(data['result'][i].sub_total)+'</td>'+
                          '</tr>';
               total += parseFloat(data['result'][i].sub_total);
              }
          }
          $('#html_total_tindakan').html('Rp. '+NumberToMoney(total));
          $('.table_data_tindakan tbody').html($tr);
      }
  });
}

function get_resep(id_registrasi){
  $.ajax({
      url : '<?php echo base_url(); ?>resepsionis/pembayaran/get_resep',
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
<!-- /page container -->
<?php $this->load->view('admin/js'); ?>
</body>
</html>
