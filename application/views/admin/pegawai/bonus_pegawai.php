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
        <?php $this->load->view('admin/pegawai/menu'); ?>
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
                                        <?php if ($this->session->flashdata('status')) : ?>
                                        <div class="alert alert-<?= $this->session->flashdata('status'); ?> no-border">
                                            <button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>
                                            <p class="message-text"><?= $this->session->flashdata('message'); ?></p>
                                        </div>
                                        <?php endif ?>
                                        <!-- message -->
                                        <div class="tabbable">
                                            <ul class="nav nav-tabs nav-tabs-component">
                                                <li class="active"><a href="#bonus-pegawai" data-toggle="tab">Data Bonus Pegawai</a></li>
                                                <li><a href="#tambah-bonus-pegawai" data-toggle="tab">Tambah Bonus Pegawai</a></li>
                                            </ul>
                                            <div class="tab-content" style="margin-bottom: 2em !important;">
                                                <div class="tab-pane active" id="bonus-pegawai">
                                                    <div class="form-group">
                                                        <select id="id_pegawai" class="select-search-primary">
                                                            <option value="Semua">Semua</option>
                                                            <?php foreach ($pegawai as $p) : ?>
                                                            <option value="<?php echo $p['pegawai_id'] ?>"><?php echo $p['nama']; ?></option>
                                                            <?php endforeach ?>
                                                        </select>
                                                    </div>
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered table-striped" id="table-bonus-pegawai">
                                                            <thead>
                                                                <tr class="bg-success">
                                                                    <th class="text-center">No</th>
                                                                    <th class="text-center">Nama Pegawai</th>
                                                                    <th class="text-center">Jenis Bonus</th>
                                                                    <th class="text-center">Nominal</th>
                                                                    <th class="text-center">Tanggal</th>
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
                                                <div class="tab-pane" id="tambah-bonus-pegawai">
                                                    <fieldset class="content-group">
                                                        <form action="<?= base_url('pegawai/bonus_pegawai/tambah') ?>" method="POST">
                                                            <div class="form-group">
                                                                <label class="control-label"><b>Jenis Bonus</b></label>
                                                                <select name="jenis_bonus" class="bootstrap-select" data-width="100%">
                                                                    <?php foreach ($jenis_bonus as $jb) : ?>
                                                                    <option value="<?= $jb ?>"><?= ucfirst($jb) ?></option>
                                                                    <?php endforeach ?>
                                                                </select>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="control-label"><b>Keterangan</b></label>
                                                                <input type="text" class="form-control" placeholder="Keterangan" name="keterangan">
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="control-label"><b>Nominal</b></label>
                                                                <div class="input-group">
                                                                    <span class="input-group-addon"><b>Rp. </b></span>
                                                                    <input type="text" class="form-control" placeholder="Nominal" onkeyup="FormatCurrency(this);" name="nominal">
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <button class="btn btn-md btn-warning  btn-icon" type="button" onclick="popup_pegawai(); data_pegawai();"><i class="icon-search4 position-left"></i> Cari</button>
                                                            </div>
                                                            <div class="form-group">
                                                                <div class="table-responsive">
                                                                    <table class="table table-bordered table-striped" id="table-pegawai">
                                                                        <thead>
                                                                            <tr class="bg-success">
                                                                                <th class="text-center">No</th>
                                                                                <th class="text-center">Nama Pegawai</th>
                                                                                <th class="text-center">Jabatan</th>
                                                                                <th class="text-center">Aksi</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                            <button class="btn btn-md btn-success  btn-icon" style="margin-top: 2em;" type="submit"><i class="icon-floppy-disk position-left"></i> Simpan</button>
                                                        </form>
                                                    </fieldset>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-primary btn-sm" id="btn_modal_pegawai" data-toggle="modal" data-target="#modal_pegawai" style="display:none;">Launch <i class="icon-play3 position-right"></i></button>
                        <div id="modal_pegawai" class="modal fade">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h6 class="modal-title">Data Pegawai</h6>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <input type="text" id="cari_pegawai" placeholder="Cari Berdasarkan Nama Pegawai" class="form-control">
                                                <span class="input-group-btn">
                                                    <button class="btn bg-primary" type="button"><i class="fa fa-search"></i></button>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-hover table-striped table_data_pegawai">
                                                <thead>
                                                    <tr class="bg-primary">
                                                        <th class="text-center">No</th>
                                                        <th class="text-center">Kode</th>
                                                        <th class="text-center">Nama Pegawai</th>
                                                        <th class="text-center">Jabatan</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                        <br>
                                        <div id="pagination_pegawai"></div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-link" id="tutup_data_pegawai" data-dismiss="modal"><i class="icon-cross"></i> Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
<script>
    $(window).load(function() {
        data_bonus_pegawai();

        function data_bonus_pegawai(){
            let id_pegawai = $('#id_pegawai').val();

            $.ajax({
                url : '<?= base_url('pegawai/bonus_pegawai/bonus_pegawai_result') ?>',
                data : {id_pegawai:id_pegawai},
                method : 'POST',
                dataType : 'json',
                success : function(res) {
                    let row = '';
                    if(res.status) {
                        let i = 0;
                        for(const item of res.data) {
                            row +=  `
                                <tr>
                                    <td class="text-center">${++i}</td>
                                    <td class="text-center">${item.nama_pegawai}</td>
                                    <td class="text-center">${item.jenis_bonus}</td>
                                    <td class="text-right"><b>Rp. </b>${NumberToMoney(item.nominal)}</td>
                                    <td class="text-center">${item.tanggal}</td>
                                    <td>
                                        <div class="text-center">
                                            <a href="#" class="btn btn-md btn-icon btn-primary btn-sm" data-toggle="modal" data-target="#modal_edit_pemasukan-${item.id}" onclick="select_jenis_bonus(${item.id}, &quot;${item.jenis_bonus}&quot;);"><i class="icon-pencil position-left"></i> Edit</a>
                                            <a href="#" class="btn btn-md btn-icon btn-danger btn-sm" data-toggle="modal" data-target="#modal_hapus_pemasukan-${item.id}"><i class="icon-trash position-left"></i> Hapus</a>
                                        </div>

                                        <!-- Modal Ubah Pemasukan -->
                                        <div id="modal_edit_pemasukan-${item.id}" class="modal fade">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-primary">
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        <h5 class="modal-title">Ubah Bonus Pegawai</h5>
                                                    </div>
                                                    <form action="<?= base_url('pegawai/bonus_pegawai/ubah') ?>" method="POST">
                                                    <div class="modal-body">

                                                        <input type="text" name="id" value="${item.id}" hidden>
                                                        <div class="form-group">
                                                            <label class="control-label"><b>Jenis Bonus</b></label>
                                                            <select name="jenis_bonus" id="jenis_bonus_${item.id}" class="select-jenis-bonus" data-width="100%">
                                                                <?php foreach ($jenis_bonus as $jb) : ?>
                                                                    <option value="<?= $jb ?>"><?= ucfirst($jb) ?></option>
                                                                <?php endforeach ?>
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label"><b>Keterangan</b></label>
                                                            <input type="text" class="form-control" name="keterangan" value="${item.keterangan}">
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label"><b>Nominal</b></label>
                                                            <div class="input-group">
                                                                <span class="input-group-addon"><b>Rp. </b></span>
                                                                <input type="text" class="form-control" onkeyup="FormatCurrency(this);" name="nominal" value="${NumberToMoney(item.nominal)}">
                                                            </div>
                                                        </div>

                                                    </div>
                                                    <div class="modal-footer">
                                                        <button class="btn btn-link" data-dismiss="modal"><i class="icon-cross"></i> Tutup</button>
                                                        <button type="submit" class="btn btn-primary"><i class="icon-pencil position-left"></i> Ubah</button>
                                                    </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- /Modal Ubah Pemasukan -->

                                        <!-- Modal Hapus Pemasukan -->
                                        <div id="modal_hapus_pemasukan-${item.id}" class="modal fade">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-danger">
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        <h5 class="modal-title">Hapus Bonus Pegawai</h5>
                                                    </div>
                                                    <form action="<?= base_url('pegawai/bonus_pegawai/hapus') ?>" method="POST">
                                                    <div class="modal-body">
                                                        <input type="hidden" name="id" value="${item.id}">
                                                        <div class="alert alert-danger no-border">
                                                            <p>Apakah Anda Ingin <span class="text-semibold">Menghapus</span> Bonus Pegawai Ini ?</p>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button class="btn btn-link" data-dismiss="modal"><i class="icon-cross"></i> Keluar</button>
                                                        <button type="submit" class="btn btn-danger"><i class="icon-trash"></i> Hapus</button>
                                                    </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- /Modal Hapus Pemasukan -->

                                    </td>
                                </tr>
                        `;
                        }
                    } else {
                        row += `
                            <tr>
                                <td colspan="6" class="text-semibold text-center">${res.message}</td>
                            </tr>
                        `;
                    }

                    $(`#table-bonus-pegawai tbody`).html(row);
                    paging();
                },
            });

            $('#id_pegawai').change(function(){
            data_bonus_pegawai();
        });
        }

    });

    function select_jenis_bonus(id, jenis_bonus){
        $('#jenis_bonus_'+id).val(jenis_bonus);
        $('.select-jenis-bonus').selectpicker('refresh');
    }

    function paging($selector){
        var jumlah_tampil = '10';

        if(typeof $selector == 'undefined'){
            $selector = $("#table-bonus-pegawai tbody tr");
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

  function popup_pegawai(){
    $('#btn_modal_pegawai').click();
  }

  function data_pegawai(){
    var search = $('#cari_pegawai').val();

    $.ajax({
      url : '<?php echo base_url(); ?>pegawai/bonus_pegawai/data_pegawai',
      data : {search:search},
      type : "POST",
      dataType :    "json",
      success : function(result){
        $table = "";

        if (result == "" || result == null) {
          $table = '<tr>'+
                      '<td colspan="7" style="text-align:center;">Data Kosong</td>'+
                   '</tr>';
        }else {
          var no = 0;
          for(var i=0; i<result.length; i++){
            no++;

            $table += '<tr style="cursor:pointer;" onclick="klik_pegawai(&quot;'+result[i].pegawai_id+'&quot;);">'+
                        '<td style="text-align:left;">'+no+'</td>'+
                        '<td style="text-align:left;">'+result[i].pegawai_id+'</td>'+
                        '<td style="text-align:left;">'+result[i].nama+'</td>'+
                        '<td style="text-align:left;">'+result[i].jabatan_nama+'</td>'+
                      '</tr>';

          }
        }
        $('.table_data_pegawai tbody').html($table);
        paging_pegawai();
      }
    });

    $('#cari_pegawai').off('keyup').keyup(function(){
        data_pegawai();
    });
  }

    function paging_pegawai($selector){
        var jumlah_tampil = '10';

        if(typeof $selector == 'undefined'){
            $selector = $(".table_data_pegawai tbody tr");
        }
            window.tp = new Pagination('#pagination_pegawai', {
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

    function klik_pegawai(id){
        $.ajax({
        url : '<?php echo base_url(); ?>pegawai/bonus_pegawai/klik_pegawai',
        data : {id:id},
        type : "POST",
        dataType : "json",
        success : function(row){
                    var jumlah_data = $('#tr_'+row.pegawai_id).length;

                    if (jumlah_data > 0) {

                    }else {
                        $menu = `<tr id="tr_${row.pegawai_id}">
                                                <td class="text-center">
                                                    <input type="hidden" class="form-control" name="id_pegawai[]" value="${row.pegawai_id}">
                                                    <input type="hidden" class="form-control" name="nama_pegawai[]" value="${row.nama}">
                                                    <input type="hidden" class="form-control" name="jabatan[]" value="${row.jabatan_nama}">
                                                    ${row.pegawai_id}
                                                </td>
                                                <td class="text-center">${row.nama}</td>
                                                <td class="text-center">${row.jabatan_nama}</td>
                                                <td class="text-center">
                                                    <button type="button" class="btn btn-danger" onclick="hapus_row_pegawai(this);" name="button"><i class="icon-bin"></i></button>
                                                </td>
                                         </tr>`;

                        $('#table-pegawai').append($menu);
                    }
        }
    });
    }

    function hapus_row_pegawai(btn){
        var row = btn.parentNode.parentNode;
    row.parentNode.removeChild(row);
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
