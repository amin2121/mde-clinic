<!DOCTYPE html>
<html lang="en">
    <head>
        <?php $this->load->view('admin/css'); ?>
        <script src="<?php echo base_url('assets/js/plugins/tables/datatables/datatables.min.js'); ?>"></script>
        <script type="text/javascript">
            $(function() {
                $('.pegawai').DataTable();
                $(`.pegawai-shift`).DataTable();
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
                                <h5 class="panel-title">Data Shift</h5>
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
                                        <li class="active"><a href="#basic-justified-tab1" data-toggle="tab" class="legitRipple" aria-expanded="true">Data Shift</a></li>
                                        <li class=""><a href="#basic-justified-tab2" data-toggle="tab" class="legitRipple" aria-expanded="false">Tambah Shift</a></li>
                                        <li class=""><a href="#basic-justified-tab3" data-toggle="tab" class="legitRipple" aria-expanded="false">Pegawai Shift</a></li>
                                    </ul>
                                    <div class="tab-content">
                                        <div class="tab-pane active" id="basic-justified-tab1">
                                            <table class="table pegawai">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center">No.</th>
                                                        <th class="text-center">Nama</th>
                                                        <th class="text-center">Jam Masuk</th>
                                                        <th class="text-center">Jam Pulang</th>
                                                        <th class="text-center">Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($shift as $key => $s) : ?>
                                                    <tr>
                                                        <td class="text-center"><?= ++$key ?></td>
                                                        <td class="text-center"><?= $s['nama'] ?></td>
                                                        <td class="text-center"><i class="fa fa-clock-o"></i> <?= $s['jam_masuk'] ?></td>
                                                        <td class="text-center"><i class="fa fa-clock-o"></i> <?= $s['jam_pulang'] ?></td>
                                                        <td>
                                                            <div class="text-center">
                                                                <a href="#" class="btn btn-sm btn-icon btn-primary" data-toggle="modal" data-target="#modal_edit_shift_<?= $s['id'] ?>"><i class="icon-pencil position-left"></i> Edit</a>
                                                                <a data-toggle="modal" data-target="#modal_hapus_shift_<?php echo $s['id']; ?>" class="btn btn-sm btn-danger"><i class="icon-trash position-left"></i> Hapus</a>
                                                            </div>
                                                            <!-- Modal Edit Gaji Pegawai -->
                                                            <div id="modal_edit_shift_<?= $s['id'] ?>" class="modal fade">
                                                                <div class="modal-dialog">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header bg-primary">
                                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                            <h6 class="modal-title">Edit Shift</h6>
                                                                        </div>
                                                                        <form action="<?= base_url('pegawai/shift/ubah_shift') ?>" method="POST">
                                                                            <div class="modal-body">
                                                                                <input type="text" name="id" hidden="" value="<?= $s['id'] ?>">
                                                                                <div class="form-group">
                                                                                    <label class="control-label">Nama</label>
                                                                                    <input type="text" class="form-control" name="nama" value="<?= $s['nama'] ?>">
                                                                                </div>
                                                                                <div class="form-group">
                                                                                    <label class="control-label"><b>Cabang</b></label>
                                                                                    <select name="id_cabang" class="bootstrap-select" data-width="100%">
                                                                                        <?php foreach ($cabang as $c) : ?>
                                                                                        <option <?php if ($c['id'] == $s['id_cabang']) : ?>
                                                                                            selected
                                                                                                <?php endif ?> value="<?php echo $c['id']; ?>"><?php echo $c['nama']; ?></option>
                                                                                        <?php endforeach ?>
                                                                                    </select>
                                                                                </div>
                                                                                <div class="form-group">
                                                                                    <label class="control-label">Jam Masuk</label>
                                                                                    <div class="input-group">
                                                                                        <span class="input-group-addon"><b><i class="fa fa-clock-o"></i></b></span>
                                                                                        <input type="text" class="form-control time" name="jam_masuk" value="<?= $s['jam_masuk'] ?>">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group">
                                                                                    <label class="control-label">Jam Pulang</label>
                                                                                    <div class="input-group">
                                                                                        <span class="input-group-addon"><b><i class="fa fa-clock-o"></i></b></span>
                                                                                        <input type="text" class="form-control time" name="jam_pulang" value="<?= $s['jam_pulang'] ?>">
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                <button type="button" class="btn btn-link" data-dismiss="modal"><i class="icon-cross position-left"></i>Close</button>
                                                                                <button type="submit" class="btn btn-primary btn-icon"><i class="icon-pencil position-left"></i> Ubah</button>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <!-- Modal Edit Gaji Pegawai -->
                                                            <!-- Modal Hapus Pemasukan -->
                                                            <div id="modal_hapus_shift_<?php echo $s['id']; ?>" class="modal fade">
                                                                <div class="modal-dialog">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header bg-danger">
                                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                            <h5 class="modal-title">Hapus Shift</h5>
                                                                        </div>
                                                                        <form action="<?php echo base_url('pegawai/shift/hapus_shift/'.$s['id']) ?>" method="GET">
                                                                            <div class="modal-body">
                                                                                <div class="alert alert-danger no-border">
                                                                                    <p>Apakah Anda Ingin <span class="text-semibold">Menghapus</span> Shift Ini ?</p>
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
                                                            <!-- /Modal Hapus Pemasukan -->
                                                        </td>
                                                    </tr>
                                                    <?php endforeach ?>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="tab-pane" id="basic-justified-tab2">
                                            <form action="<?php echo base_url('pegawai/shift/tambah_shift') ?>" method="post">
                                                <div class="form-group">
                                                    <label class="control-label"><b>Nama Shift</b></label>
                                                    <input type="text" class="form-control" placeholder="Nama Shift" name="nama">
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label"><b>Cabang</b></label>
                                                    <select name="id_cabang" class="bootstrap-select" data-width="100%">
                                                        <?php foreach ($cabang as $c) : ?>
                                                        <option value="<?php echo $c['id']; ?>"><?php echo $c['nama']; ?></option>
                                                        <?php endforeach ?>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label"><b>Jam Masuk</b></label>
                                                    <input type="text" class="form-control time" placeholder="Jam Masuk" name="jam_masuk">
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label"><b>Jam Pulang</b></label>
                                                    <input type="text" class="form-control time" placeholder="Jam Pulang" name="jam_pulang">
                                                </div>
                                                <div class="form-group">
                                                    <button class="btn btn-md btn-success btn-icon" style="margin-top: 2em;" type="submit"><i class="icon-floppy-disk position-left"></i> Simpan</button>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="tab-pane" id="basic-justified-tab3">
                                            <div class="alert alert-primary" role="alert">
                                              <b>Perhatian!</b><br>
                                              Jika Shift Kosong Maka Pasien Diwajibkan Absen Terlebih Dahulu
                                            </div>
                                            <table class="table pegawai-shift">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center">Kode</th>
                                                        <th class="text-center">nama</th>
                                                        <th class="text-center">Shift</th>
                                                        <th class="text-center">Telepon</th>
                                                        <th class="text-center">Cabang</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($pegawai_shift as $key => $ps) : ?>
                                                    <tr>
                                                        <td class="text-center"><?= $ps['pegawai_id'] ?></td>
                                                        <td class="text-center"><?= $ps['nama'] ?></td>
                                                        <td class="text-center"><?php 
                                                            if($ps['shift'] == null) {
                                                                if($ps['ijin'] == null) {
                                                                    echo '-';       
                                                                } else {
                                                                    echo ucfirst($ps['ijin']);
                                                                }
                                                            } else {
                                                                echo ucfirst($ps['shift']);
                                                            }
                                                        ?></td>
                                                        <td class="text-center"><?= $ps['telepon'] ?></td>
                                                        <td class="text-center"><?= $ps['cabang'] ?></td>
                                                    </tr>
                                                    <?php endforeach ?>
                                                </tbody>
                                            </table>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php $this->load->view('admin/js'); ?>
                    </div>
                    <!-- /sidebars overview -->
                </div>
                <!-- /main content -->
            </div>
            <!-- /page content -->
        </div>
        <!-- /page container -->
        <script>
            $('.time').mask('00:00:00');
        </script>
    </body>
</html>


