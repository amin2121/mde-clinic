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
                                                <li class="active"><a href="#jadwal_dokter" data-toggle="tab">Data Jadwal Poli</a></li>
                                                <li><a href="#tambah_jadwal_dokter" data-toggle="tab">Tambah Jadwal Poli</a></li>
                                                <li><a href="#jadwal_libur" data-toggle="tab">Data Jadwal Libur</a></li>
                                                <li><a href="#tambah_jadwal_libur" data-toggle="tab">Tambah Jadwal Libur</a></li>
                                            </ul>
                                            <div class="tab-content" style="margin-bottom: 2em !important;">
                                                <div class="tab-pane active" id="jadwal_dokter">
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered table-striped" id="table_jadwal_dokter">
                                                            <thead>
                                                                <tr class="bg-success">
                                                                    <th class="text-center">No</th>
                                                                    <th class="text-center">Hari</th>
                                                                    <th class="text-center">Range Jam</th>
                                                                    <th class="text-center">Action</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                $no = 0;
                                                                foreach ($result as $r) :
                                                                    $no++;
                                                                    ?>
                                                                <tr>
                                                                    <td class="text-center"><?php echo $no; ?></td>
                                                                    <td class="text-center"><?php echo $r['hari']; ?></td>
                                                                    <td class="text-center"><?php echo $r['jam_awal']; ?> - <?php echo $r['jam_akhir']; ?></td>
                                                                    <td>
                                                                        <div class="text-center">
                                                                            <a href="#" class="btn btn-md btn-icon btn-danger" data-toggle="modal" data-target="#modal_hapus_jadwal_<?= $r['id'] ?>"><i class="icon-trash position-left"></i> Hapus</a>
                                                                        </div>
                                                                        <div id="modal_hapus_jadwal_<?= $r['id'] ?>" class="modal fade">
                                                                            <div class="modal-dialog">
                                                                                <div class="modal-content">
                                                                                    <div class="modal-header bg-danger">
                                                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                                        <h5 class="modal-title">Hapus Jadwal Dokter</h5>
                                                                                    </div>
                                                                                    <div class="modal-body">
                                                                                        <div class="alert alert-danger no-border">
                                                                                            <p>Apakah Anda Ingin <span class="text-semibold">Menghapus</span> Jadwal Dokter Ini?</p>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="modal-footer">
                                                                                        <button class="btn btn-link" data-dismiss="modal"><i class="icon-cross position-left"></i> Keluar</button>
                                                                                        <a href="<?= base_url('') ?>poli/jadwal_dokter/hapus_jadwal_dokter/<?php echo $r['id']; ?>/<?php echo $r['id_poli']; ?>" class="btn btn-danger"><i class="icon-bin position-left"></i> Hapus</a>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                                <?php endforeach; ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                <div class="tab-pane" id="tambah_jadwal_dokter">
                                                    <form action="<?= base_url() ?>poli/jadwal_dokter/tambah_jadwal_dokter" method="POST">
                                                        <input type="hidden" name="id_poli" value="<?php echo $id_poli; ?>">
                                                        <div class="form-group">
                                                            <label class="control-label"><b>Hari</b></label>
                                                            <select class="select-search" data-placeholder="Pilih Hari" name="hari">
                                                                <option></option>
                                                                <option value="Senin">Senin</option>
                                                                <option value="Selasa">Selasa</option>
                                                                <option value="Rabu">Rabu</option>
                                                                <option value="Kamis">Kamis</option>
                                                                <option value="Jum'at">Jum'at</option>
                                                                <option value="Sabtu">Sabtu</option>
                                                                <option value="Minggu">Minggu</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label"><b>Range Jam</b></label>
                                                            <div class="row">
                                                                <div class="col-sm-6">
                                                                    <div class="input-group">
                                                                        <span class="input-group-addon"><i class="icon-watch2"></i></span>
                                                                        <input type="text" class="form-control" name="jam_awal" id="jam_awal">
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-6">
                                                                    <div class="input-group">
                                                                        <span class="input-group-addon"><i class="icon-watch2"></i></span>
                                                                        <input type="text" class="form-control" name="jam_akhir" id="jam_akhir">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <button class="btn btn-success" type="submit" style="margin-top: 2em;"><i class="icon-floppy-disk position-left"></i> Simpan</button>
                                                    </form>
                                                </div>
                                                <div class="tab-pane" id="jadwal_libur">
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered table-striped" id="table_jadwal_libur">
                                                            <thead>
                                                                <tr class="bg-success">
                                                                    <th class="text-center">No</th>
                                                                    <th class="text-center">Tanggal</th>
                                                                    <th class="text-center">Action</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                $no = 0;
                                                                foreach ($result_libur as $rl) :
                                                                    $no++;
                                                                    ?>
                                                                <tr>
                                                                    <td class="text-center"><?php echo $no; ?></td>
                                                                    <td class="text-center"><?php echo $rl['tanggal']; ?></td>
                                                                    <td>
                                                                        <div class="text-center">
                                                                            <a href="#" class="btn btn-md btn-icon btn-warning" data-toggle="modal" data-target="#modal_edit_jadwal_libur_<?= $rl['id'] ?>"><i class="icon-pencil position-left"></i> Edit</a>
                                                                            <a href="#" class="btn btn-md btn-icon btn-danger" data-toggle="modal" data-target="#modal_hapus_jadwal_libur_<?= $rl['id'] ?>"><i class="icon-trash position-left"></i> Hapus</a>
                                                                        </div>
                                                                        <div id="modal_edit_jadwal_libur_<?= $rl['id'] ?>" class="modal fade">
                                                                            <div class="modal-dialog">
                                                                                <div class="modal-content">
                                                                                    <div class="modal-header">
                                                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                                        <h5 class="modal-title">Edit Jadwal Libur</h5>
                                                                                    </div>
                                                                                    <form action="<?= base_url() ?>poli/jadwal_dokter/edit_jadwal_libur" method="POST">
                                                                                        <div class="modal-body">
                                                                                            <input type="hidden" name="id_poli" value="<?php echo $id_poli; ?>">
                                                                                            <input type="hidden" name="id_jadwal_libur" value="<?php echo $rl['id']; ?>">
                                                                                            <div class="form-group">
                                                                                                <label class="control-label"><b>Tanggal</b></label>
                                                                                                <div class="input-group">
                                                                                                    <span class="input-group-addon"><i class="icon-calendar5"></i></span>
                                                                                                    <input type="text" class="form-control pickdate" placeholder="dd-mm-yy" name="tanggal" value="<?= $rl['tanggal'] ?>">
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="modal-footer">
                                                                                            <button class="btn btn-link" data-dismiss="modal"><i class="icon-cross position-left"></i> Keluar</button>
                                                                                            <button class="btn btn-primary" type="submit"><i class="icon-floppy-disk position-left"></i>  Simpan</button>
                                                                                        </div>
                                                                                    </form>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div id="modal_hapus_jadwal_libur_<?= $rl['id'] ?>" class="modal fade">
                                                                            <div class="modal-dialog">
                                                                                <div class="modal-content">
                                                                                    <div class="modal-header bg-danger">
                                                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                                        <h5 class="modal-title">Hapus Jadwal Libur</h5>
                                                                                    </div>
                                                                                    <div class="modal-body">
                                                                                        <div class="alert alert-danger no-border">
                                                                                            <p>Apakah Anda Ingin <span class="text-semibold">Menghapus</span> Jadwal Libur Ini?</p>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="modal-footer">
                                                                                        <button class="btn btn-link" data-dismiss="modal"><i class="icon-cross position-left"></i> Keluar</button>
                                                                                        <a href="<?= base_url('') ?>poli/jadwal_dokter/hapus_jadwal_libur/<?php echo $rl['id']; ?>/<?php echo $rl['id_poli']; ?>" class="btn btn-danger"><i class="icon-bin position-left"></i> Hapus</a>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                                <?php endforeach; ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                <div class="tab-pane" id="tambah_jadwal_libur">
                                                    <form action="<?= base_url() ?>poli/jadwal_dokter/tambah_jadwal_libur" method="POST">
                                                        <input type="hidden" name="id_poli" value="<?php echo $id_poli; ?>">
                                                        <div class="form-group">
                                                            <label class="control-label"><b>Tanggal</b></label>
                                                            <div class="input-group">
                                                                <span class="input-group-addon"><i class="icon-calendar5"></i></span>
                                                                <input type="text" class="form-control pickdate" placeholder="dd-mm-yy" name="tanggal">
                                                            </div>
                                                        </div>
                                                        <button class="btn btn-success" type="submit" style="margin-top: 2em;"><i class="icon-floppy-disk position-left"></i> Simpan</button>
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
                                $("#jam_awal").AnyTime_picker({
                                    format: "%H:%i:%s"
                                });
                                $("#jam_akhir").AnyTime_picker({
                                    format: "%H:%i:%s"
                                });
                                $('.pickdate').pickadate({
                                    // Escape any “rule” characters with an exclamation mark (!).
                                    format: 'dd-mm-yyyy'
                                });

                                $(`#table_jadwal_dokter`).DataTable()
                                $(`#table_jadwal_libur`).DataTable()
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
