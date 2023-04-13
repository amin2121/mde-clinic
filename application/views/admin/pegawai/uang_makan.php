<!DOCTYPE html>
<html lang="en">
    <head>
        <?php $this->load->view('admin/css'); ?>
        <script type="text/javascript">
            $(function() {
                // $('.pegawai').DataTable();
            });
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
                    <!-- Sidebars overview -->
                    <div class="content">
                        <div class="panel panel-flat border-top-success border-top-lg">
                            <div class="panel-heading">
                                <h5 class="panel-title">Data Uang Makan</h5>
                            </div>
                            <div class="panel-body">
                                
                                <!-- message -->
                                <?php if ($this->session->flashdata('status')) : ?>
                                <div class="alert alert-<?= $this->session->flashdata('status'); ?> no-border">
                                    <button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>
                                    <p class="message-text"><?= $this->session->flashdata('message'); ?></p>
                                </div>
                                <?php endif ?>
                                <!-- message -->

                                <div class="tabbable">
                                    <ul class="nav nav-tabs">
                                        <li class="active"><a href="#basic-justified-tab1" data-toggle="tab" class="legitRipple" aria-expanded="true">Data Uang Makan</a></li>
                                        <li class=""><a href="#basic-justified-tab2" data-toggle="tab" class="legitRipple" aria-expanded="false">Tambah Uang Makan</a></li>
                                    </ul>
                                    <div class="tab-content">
                                        <div class="tab-pane active" id="basic-justified-tab1">
                                            <div class="form-group">
                                                <div class="input-group">
                                                    <!-- <label class="control-label col-sm-2">Nama Barang</label> -->
                                                    <input type="text" class="form-control" autocomplete="off" id="input-search" onkeyup="get_uang_makan()" placeholder="Cari Berdasarkan Nama Pegawai">
                                                    <span class="input-group-btn">
                                                        <button class="btn bg-primary" type="button" onclick="get_uang_makan()"><i class="fa fa-search"></i></button>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="table-responsive">
                                                <table class="table table-bordered pegawai" id="table_uang_makan">
                                                    <thead>
                                                        <tr class="bg-success">
                                                            <th class="text-center">No. </th>
                                                            <th class="text-center">Nama Pegawai</th>
                                                            <th class="text-center">Uang Makan</th>
                                                            <th class="text-center">Tanggal</th>
                                                            <th class="text-center">Aksi</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    </tbody>
                                                </table>
                                                <br>
                                            <ul class="pagination_uang_makan"></ul>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="basic-justified-tab2">
                                        <form action="<?php echo base_url('pegawai/uang_makan/tambah_uang_makan') ?>" method="post" id="form-uang-makan">
                                            <div class="form-group">
                                                <label for=""  class="control-label"><b>Cari Pegawai</b></label>
                                                <div class="input-group">
                                                    <input type="text" id="nama_pegawai" name="nama_pegawai" class="form-control" placeholder="Cari Pegawai" readonly>
                                                    <input type="text" id="id_pegawai" name="id_pegawai" hidden="">
                                                    <span class="input-group-btn">
                                                        <button class="btn bg-primary" onclick="get_pegawai();" type="button"><i class="fa fa-search"></i> Cari</button>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for=""  class="control-label"><b>Uang Makan</b></label>
                                                <div class="input-group">
                                                    <span class="input-group-addon"><b>Rp. </b></span>
                                                    <input type="text" id="uang_makan" placeholder="Uang Makan" name="uang_makan" class="form-control rupiah">
                                                </div>
                                            </div>
                                            <button class="btn btn-success btn-icon" onclick="submit_gaji()"><i class="icon-floppy-disk position-left"></i> Simpan</button>
                                        </form>
                                        <div id="modal_pegawai" class="modal fade">
                                            <div class="modal-dialog modal-full">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-primary">
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        <h6 class="modal-title">Data Pegawai</h6>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <div class="input-group">
                                                                <input type="text" id="search_pasien" placeholder="Cari Berdasarkan Nama Pasien dan No RM" class="form-control">
                                                                <span class="input-group-btn">
                                                                    <button class="btn bg-primary" type="button" onclick="search_pegawai()"><i class="fa fa-search"></i></button>
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <div class="table-responsive">
                                                            <table class="table table-bordered table-hover table-striped" id="table-nama-pegawai">
                                                                <thead>
                                                                    <tr class="bg-primary">
                                                                        <th class="text-center">Kode</th>
                                                                        <th class="text-center">Nama Pegawai</th>
                                                                        <th class="text-center">Jabatan</th>
                                                                        <th class="text-center">Tempat Lahir, Tanggal Lahir</th>
                                                                        <th class="text-center">Alamat</th>
                                                                        <th class="text-center">Telepon</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                        <br>
                                                        <div class="pagination"></div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-warning" id="tutup_data_pasien" data-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /sidebars overview -->
            </div>
            <!-- /main content -->
        </div>
        <!-- /page content -->
    </div>
    <!-- /page container -->
    <?php $this->load->view('admin/js'); ?>
<script>
    $(window).load(function() {
        $(`#nama_pegawai`).focus();
        get_uang_makan();
    })

    function get_uang_makan() {
        let search = $(`#input-search`).val();

        $.ajax({
            url : '<?= base_url('pegawai/uang_makan/get_uang_makan') ?>',
            method : 'POST',
            data : {'nama_pegawai' : `${search}`},
            dataType : 'json',
            success : (res) => {
                var row = ''
                if(res.status) {
                    let i = 0;
                    for(const item of res.data) {
                        row += `
                            <tr>
                                <td class="text-center">${++i}</td>
                                <td class="text-center">${item.nama}</td>
                                <td class="text-center"><b>Rp. </b>${NumberToMoney(item.uang_makan)}</td>
                                <td class="text-center">${item.tanggal}</td>
                                <td>
                                    <div class="text-center">
                                        <a href="#" class="btn btn-md btn-icon btn-primary" data-toggle="modal" data-target="#modal_edit_uang_makan_${item.id}"><i class="icon-pencil position-left"></i> Edit</a>
                                        <a href="<?php echo base_url('pegawai/uang_makan/hapus_uang_makan?id_uang_makan=') ?>${item.id}" onclick="return confirm('Anda yakin ingin menghapus data?');" class="btn btn-danger"><i class="icon-trash position-left"></i> Hapus</a>
                                    </div>

                                    <!-- Modal Edit Uang Makan -->
                                    <div id="modal_edit_uang_makan_${item.id}" class="modal fade">
                                      <div class="modal-dialog">
                                        <div class="modal-content">
                                          <div class="modal-header bg-primary">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h6 class="modal-title">Edit Uang Makan</h6>
                                          </div>

                                          <form action="<?= base_url('pegawai/uang_makan/edit_uang_makan') ?>" method="POST" id="form-ubah-uang-makan-${item.id}">
                                              <div class="modal-body">
                                                <input type="text" name="id_uang_makan" hidden="" value="${item.id}">
                                                <input type="text" name="pegawai_id" hidden="" value="${item.pegawai_id}">
                                                <div class="form-group">
                                                    <label class="control-label">Uang Makan</label>
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><b>Rp. </b></span>
                                                        <input type="text" class="form-control rupiah" name="gaji" value="${NumberToMoney(item.uang_makan)}">
                                                    </div>
                                                </div>
                                              </div>

                                              <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                <button type="button" onclick="" class="btn btn-primary btn-icon"><i class="icon-pencil"></i> Ubah</button>
                                              </div>
                                          </form>
                                        </div>
                                      </div>
                                    </div>
                                    <!-- Modal Edit Uang Makan -->

                                </td>
                            </tr>
                        `
                    }
                } else {
                    row += `
                        <tr>
                            <td colspan="6" class="text-center"><b>${res.message}</b></td>
                        </tr>
                    `
                }

                $(`#table_uang_makan tbody`).html(row);
                pagination_uang_makan()
            }
        })
    }

    function pagination_uang_makan($selector){
        var jumlah_tampil = '10';

        if(typeof $selector == 'undefined'){
            $selector = $(".table tbody tr");
        }
            window.tp = new Pagination('.pagination_', {
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

    function get_pegawai() {
        $(`#modal_pegawai`).modal('toggle');

        $.ajax({
            url : '<?= base_url('pegawai/uang_makan/get_pegawai_ajax') ?>',
            method : 'GET',
            dataType : 'json',
            success : (res) => {
                let row = '';

                if(res.status) {
                    let i = 0;
                    for(const item of res.data) {
                        row += `
                            <tr onclick="set_input_nama_pegawai('${item.nama}', '${item.pegawai_id}')" style="cursor: pointer;">
                                <td>${item.pegawai_id}</td>
                                <td>${item.nama}</td>
                                <td>${item.jabatan}</td>
                                <td>${item.tempat_lahir}, ${item.tgl_lahir}</td>
                                <td>${item.alamat}</td>
                                <td>${item.telepon}</td>
                            </tr>
                        `
                    }
                } else {
                    row += `
                        <tr>
                            <td colspan="6" class="text-center">${res.message}</td>
                        </tr>
                    `
                }
                $(`#table-nama-pegawai tbody`).html(row);
                pagination()
            }
        })
    }

    function search_pegawai() {
        let value = $(`#search_pasien`).val();

        $.ajax({
            url : '<?= base_url('pegawai/uang_makan/get_pegawai_ajax') ?>',
            data : {'search' : `${value}`},
            method : 'GET',
            dataType : 'json',
            success : (res) => {
                let row = '';

                if(res.status) {
                    let i = 0;
                    for(const item of res.data) {
                        row += `
                            <tr onclick="set_input_nama_pegawai('${item.nama}', '${item.pegawai_id}')" style="cursor: pointer;">
                                <td>${item.pegawai_id}</td>
                                <td>${item.nama}</td>
                                <td>${item.jabatan}</td>
                                <td>${item.tempat_lahir}, ${item.tgl_lahir}</td>
                                <td>${item.alamat}</td>
                                <td>${item.telepon}</td>
                            </tr>
                        `
                    }
                } else {
                    row += `
                        <tr>
                            <td colspan="6" class="text-center">${res.message}</td>
                        </tr>
                    `
                }
                $(`#table-nama-pegawai tbody`).html(row);
                pagination()
            }
        })
    }

    function set_input_nama_pegawai(nama, pegawai_id) {
        $(`#nama_pegawai`).val(nama);
        $(`#id_pegawai`).val(pegawai_id);

        $(`#modal_pegawai`).modal('toggle');
    }

    function submit_gaji() {
        $(`.rupiah`).unmask();

        $(`#form-tambah-gaji`).submit()
    }

    function submit_ubah_gaji(id_gaji_pegawai) {
        $(`.rupiah`).unmask();

        $(`#form-ubah-gaji-${id_gaji_pegawai}`).submit()
    }

    function pagination($selector){
        var jumlah_tampil = '10';

        if(typeof $selector == 'undefined'){
            $selector = $("#table-nama-pegawai tbody tr");
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
</body>
</html>
