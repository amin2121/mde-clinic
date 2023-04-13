<!DOCTYPE html>
<html lang="en">
<head>
    <?php $this->load->view('admin/css'); ?>
    <!-- Theme JS files -->
    <!-- <script src="https://maps.googleapis.com/maps/api/js?v=3.exp"></script> -->
    <!-- AIzaSyB5R_rRW7B6iGkUX92hF1tQYiZUy-8c63s AIzaSyCs7VPOQbQHCQOzOD5g_o6Knm6PiAybuMI -->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBMG_Dbq0vWVRnRSVxLnfdKKUbbX17DyMk" type="text/javascript"></script>
    <script type="text/javascript" src="<?php echo base_url('assets/js/plugins/forms/selects/select2.min.js');?>"></script>
    <script src="<?php echo base_url('assets/js/plugins/tables/datatables/datatables.min.js'); ?>"></script>
    <script type="text/javascript">
        $(function() {
            $('.pegawai').DataTable();
            $('.select').select2({
            minimumResultsForSearch: Infinity
            });


            // Select with search
            $('.select-search').select2();
            });
            // Setup map
    </script>
</head><!--
 - Untuk Foto before dan after sebanyak 3 foto dan tertampil setelah memasukkan foto.
 - Menambahkan fitur search di stok cabang.
 - Menambahkan Riwayat pemakaian obat atau produk sebelumnya.
 - Untuk data resep di rekam medis minta rincian produk atau obat.
 - urutan data rekam medis di urutkan dari yang terbaru.
 - prosentase di jasa medis poli diganti nominal.
 - gambar before dan after langsung ditampilkan saat diagnosa. -->
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
                            <h5 class="panel-title">Data Absensi</h5>
                        </div>

                        <div class="panel-body">
                            <?php echo $this->session->flashdata('success');?>
                            <div class="tabbable">
                                <ul class="nav nav-tabs">
                                    <li class="active"><a href="#basic-justified-tab1" data-toggle="tab" class="legitRipple" aria-expanded="true">Data Absensi</a></li>
                                </ul>

                                <div class="tab-content">
                                    <div class="tab-pane active" id="basic-justified-tab1">
                                        <form action="<?php echo base_url('pegawai/Absen/search') ?>" method="post">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for=""><b>Tahun</b></label>
                                                        <select name="tahun" class="select form-control" id="">
                                                            <?php
                                                                $bulan = array(
                                                                    'Januari'=>'01',
                                                                    'Februari'=>'02',
                                                                    'Maret'=>'03',
                                                                    'April'=>'04',
                                                                    'Mei'=>'05',
                                                                    'Juni'=>'06',
                                                                    'Juli'=>'07',
                                                                    'Agustus'=>'08',
                                                                    'September'=>'09',
                                                                    'Oktober'=>'10',
                                                                    'November'=>'11',
                                                                    'Desember'=>'12'
                                                                );
                                                                $olddate= date("Y")-4;
                                                                $date= date("Y");
                                                                for ($i=$date; $i >= $olddate; $i--) {
                                                                    echo "<option value='".$i."'>".$i."</option>";
                                                                }
                                                                ?>
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for=""><b>Bulan</b></label>
                                                        <select name="bulan" class="select form-control" id="">
                                                            <?php foreach ($bulan as $key => $value) : ?>
                                                                <option <?php if ($value == date("m")) : ?>
                                                                    selected
                                                                        <?php endif ?> value="<?php echo $value ?>"><?php echo $key ?></option>
                                                            <?php endforeach ?>
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for=""><b>Pegawai</b></label>
                                                        <select name="pegawai" class="select-search">
                                                            <optgroup label="Pilih Pegawai">
                                                                <option value="semua">Semua</option>
                                                                <?php foreach ($pegawai as $p) : ?>
                                                                    <option value="<?php echo $p->pegawai_id; ?>"><?php echo $p->nama; ?></option>
                                                                <?php endforeach ?>
                                                            </optgroup>
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <input type="submit" class="btn btn-primary" value="Cari">
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                        <?php if ($search != 0) : ?>
                                        <div style="float: right">
                                            <a href="<?php echo base_url('pegawai/Absen/export'); ?>?tahun=<?php echo $this->input->post('tahun');?>&bulan=<?php echo $this->input->post('bulan');?>&pegawai=<?php echo $this->input->post('pegawai');?>" class="btn btn-success"><i class="icon-file-excel position-left"></i> Export Excel</a><br><br>
                                        </div>
                                        <table class="table pegawai">
                                            <thead>
                                                <tr>
                                                    <th width="10%" style="text-align: center;">Tanggal</th>
                                                    <th width="10%" style="text-align: center;">Nama</th>
                                                    <th width="10%" style="text-align: center;">Absen Masuk</th>
                                                    <th width="10%" style="text-align: center;">Absen Pulang</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($absen as $p) : ?>
                                                    <tr>
                                                        <td><?php echo $p->tanggal ?></td>
                                                        <td><?php echo $p->nama ?></td>
                                                        <td>
                                                            <center><?php echo $p->jam_masuk ?><br><br>
                                                            <button type="button" class="btn btn-primary btn-raised" data-toggle="modal" data-target="#modal_foto_masuk<?php echo $p->id ?>"><i class="icon-file-picture position-left"></i>Lihat Foto </button><br><br>
                                                            <button type="button" class="btn btn-primary btn-raised" onclick="popup_modal_lokasi_masuk(<?php echo $p->id ?>, '<?= $p->latitude_masuk ?>', '<?= $p->longitude_masuk ?>')"><i class="icon-location4 position-left"></i>Lihat Lokasi </button>

                                                            <div id="modal_foto_masuk<?php echo $p->id ?>" class="modal fade">
                                                                <div class="modal-dialog">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                            <h5 class="modal-title">Foto Masuk</h5>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <img src="https://mdeclinic.id/api_mobile/assets/foto/<?php echo $p->foto ?>" class="img-responsive" alt="">
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button" class="btn btn-link" data-dismiss="modal">Tutup</button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                
                                                            </div>
                                                            <div id="modal_lokasi_masuk_<?php echo $p->id ?>" class="modal fade">
                                                                <div class="modal-dialog">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                            <h5 class="modal-title">Lokasi Masuk</h5>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <?php if ($p->latitude_masuk == '-' && $p->longitude_masuk == '-') : ?>
                                                                                <div style="height:300px;width:500px;" class="map-container text-center">
                                                                                    <p style="margin-top: 30px;"><b>Maps Kosong</b></p>
                                                                                </div>
                                                                            <?php else : ?>
                                                                                <div style="height:300px;width:500px;" class="map-container" id="map-masuk-<?php echo $p->id ?>"></div>
                                                                            <?php endif ?>
                                                                            <!-- <p><?php echo $p->latitude_masuk;?>    <?php echo $p->longitude_masuk;?></p> -->
                                                                            <!-- -8.1282626 113.228668 -8.2833659 113.3807144 -->
                                                                            <!-- AIzaSyBMG_Dbq0vWVRnRSVxLnfdKKUbbX17DyMk -->
                                                                            <!-- <iframe
                                                                              height="300"
                                                                              width="500"
                                                                              frameborder="0" style="border:0"
                                                                              src="https://www.google.com/maps/embed/v1/view?key=AIzaSyCs7VPOQbQHCQOzOD5g_o6Knm6PiAybuMI&center=<?php echo $p->latitude_masuk;?>,<?php echo $p->longitude_masuk;?>&zoom=18" allowfullscreen>
                                                                            </iframe> -->
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button" class="btn btn-link" data-dismiss="modal">Tutup</button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <!-- <?php include 'absen_masuk.php'; ?> -->
                                                        </center></td>
                                                        <td><center><?php echo $p->jam_pulang ?><br><br>
                                                            <button type="button" class="btn btn-primary btn-raised" data-toggle="modal" data-target="#modal_foto_pulang_<?php echo $p->id ?>"><i class="icon-file-picture position-left"></i>Lihat Foto </button><br><br>
                                                            <button type="button" class="btn btn-primary btn-raised" onclick="popup_modal_lokasi_pulang(<?= $p->id ?>, '<?= $p->latitude_pulang ?>', '<?= $p->longitude_pulang ?>')"><i class="icon-location4 position-left"></i>Lihat Lokasi </button>

                                                            <div id="modal_foto_pulang_<?php echo $p->id ?>" class="modal fade">
                                                                <div class="modal-dialog">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                            <h5 class="modal-title">Foto Pulang</h5>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <img src="https://mdeclinic.id/api_mobile/assets/foto/<?php echo $p->foto_pulang ?>" class="img-responsive" alt="">
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button" class="btn btn-link" data-dismiss="modal">Tutup</button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div id="modal_lokasi_pulang_<?php echo $p->id ?>" class="modal fade">
                                                                <div class="modal-dialog">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                            <h5 class="modal-title">Lokasi Pulang</h5>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <?php if ($p->latitude_pulang == '-' && $p->longitude_pulang == '-') : ?>
                                                                                <div style="height:300px;width:500px;" class="map-container text-center">
                                                                                    <p style="margin-top: 30px;"><b>Maps Kosong</b></p>
                                                                                </div>
                                                                            <?php else : ?>
                                                                                <div style="height:300px;width:500px;" class="map-container" id="map-pulang-<?php echo $p->id ?>"></div>
                                                                            <?php endif; ?>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button" class="btn btn-link" data-dismiss="modal">Tutup</button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <!-- <?php include 'absen_pulang.php'; ?> -->
                                                        </center></td>
                                                    </tr>
                                                <?php endforeach ?>
                                            </tbody>
                                        </table>
                                        <?php endif ?>
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
<script type="text/javascript">
    function popup_modal_lokasi_masuk(id, lat_masuk, long_masuk) {
        //  // Set coordinates
            
        $(`#modal_lokasi_masuk_${id}`).modal('toggle');
        // });
        var myLatlng = new google.maps.LatLng(lat_masuk == '-' ? 0 : lat_masuk, long_masuk == '-' ? 0 : long_masuk);

        // Options
        var mapOptions = {
            zoom: 18,
            center: myLatlng,
            mapTypeId : 'satellite'
        };

        var map = new google.maps.Map(document.getElementById(`map-masuk-${id}`), mapOptions);
        // Add marker
        var marker = new google.maps.Marker({
            position: myLatlng,
            map: map,
        });
    }

    function popup_modal_lokasi_pulang(id, lat_pulang, long_pulang) {
        // Initialize map on window load
        $(`#modal_lokasi_pulang_${id}`).modal('toggle');
        var myLatlng = new google.maps.LatLng(lat_pulang == '-' ? 0 : lat_pulang, long_pulang == '-' ? 0 : long_pulang);

        // Options
        var mapOptions = {
            zoom: 18,
            center: myLatlng,
            mapTypeId : 'satellite'
        };

        // Apply options
        var map = new google.maps.Map((document.getElementById(`map-pulang-${id}`)), mapOptions);

        // Add marker
        var marker = new google.maps.Marker({
            position: myLatlng,
            map: map,
        });
    }
</script>
<?php $this->load->view('admin/js'); ?>
</body>
</html>
