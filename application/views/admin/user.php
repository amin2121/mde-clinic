<!-- Page Content -->
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row bg-title">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">User</h4>
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                <ol class="breadcrumb">
                    <li class="active">User</li>
                </ol>
            </div>
            <!-- /.col-lg-12 -->
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="white-box">
                  <h3 class="box-title m-b-0">User</h3>
                  <p class="text-muted m-b-10">Halaman User</p>


                    <a href="<?php echo base_url(); ?>admin/user/tambah_view"><button type="submit" class="btn btn-success waves-effect waves-light m-t-10"><i class="fa fa-plus"></i> Tambah</button><br><br></a>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Username</th>
                                    <th>Password</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($result as $r): ?>
                                  <tr>
                                    <td style="text-align:center;"><?php echo $r['nama']; ?></td>
                                    <td style="text-align:center;"><?php echo $r['username']; ?></td>
                                    <td style="text-align:center;"><?php echo $r['password']; ?></td>
                                    <td>
                                      <a href="<?php echo base_url(); ?>admin/user/edit_view/<?php echo $r['id']; ?>"><button type="button" class="btn btn-info" name="button">Edit</button></a>
                                      <a href="<?php echo base_url(); ?>admin/user/hapus/<?php echo $r['id']; ?>"><button type="button" onclick="return confirm('Apakah anda ingin menghapus data?')" class="btn btn-danger" name="button">Hapus</button></a>
                                    </td>


                                  </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?= $this->pagination->create_links() ?>

                </div>
            </div>
        </div>

    </div>
    <!-- /.container-fluid -->
</div>
<!-- /#page-wrapper -->
