<!DOCTYPE html>
<html lang="en">

    <head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>Aplikasi Klinik</title>

        <!-- Bootstrap Core CSS -->
        <link href="<?php echo base_url(); ?>assets-portal/bootstrap/css/bootstrap.min.css" rel="stylesheet">

        <!-- Font Awesome CSS -->
        <link href="<?php echo base_url(); ?>assets-portal/css/font-awesome.min.css" rel="stylesheet">

		<!-- Custom CSS -->
        <link href="<?php echo base_url(); ?>assets-portal/css/animate.css" rel="stylesheet">

        <!-- Custom CSS -->
        <link href="<?php echo base_url(); ?>assets-portal/css/style.css" rel="stylesheet">

        <!-- Custom Fonts -->
        <link href='http://fonts.googleapis.com/css?family=Lobster' rel='stylesheet' type='text/css'>


        <!-- Template js -->
        <script src="<?php echo base_url(); ?>assets-portal/js/jquery-2.1.1.min.js"></script>
        <script src="<?php echo base_url(); ?>assets-portal/bootstrap/js/bootstrap.min.js"></script>
        <script src="<?php echo base_url(); ?>assets-portal/js/jquery.appear.js"></script>
        <script src="<?php echo base_url(); ?>assets-portal/js/contact_me.js"></script>
        <script src="<?php echo base_url(); ?>assets-portal/js/jqBootstrapValidation.js"></script>
        <script src="<?php echo base_url(); ?>assets-portal/js/modernizr.custom.js"></script>
        <script src="<?php echo base_url(); ?>assets-portal/js/script.js"></script>

        <!--[if lt IE 9]>
            <script src="<?php echo base_url(); ?>assets-portal/https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
            <script src="<?php echo base_url(); ?>assets-portal/https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->

    </head>
    <style media="screen">
      .merah{
        background: #E53935;
      }

      .hijau{
        background: #4CAF50;
      }

      .biru{
        background: #039BE5;
      }

      .kuning{
        background: #FFD600;
      }

      .oranye{
        background: #FB8C00;
      }

      .ungu{
        background: #7E57C2;
      }

      .coklat{
        background: #6D4C41;
      }
    </style>
    <body>

        <!-- Start Logo Section -->
        <section id="logo-section" class="text-center">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="logo text-center">
                            <h1>MDE</h1>
                            <span><?php echo $this->session->userdata('nama_cabang'); ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- End Logo Section -->


        <!-- Start Main Body Section -->
        <div class="mainbody-section text-center">
            <div class="container">
                <div class="row">
                  <?php
                      $level = $this->session->userdata('level');
                      $q_portal = $this->db->query("SELECT a.* FROM pengaturan_hak_akses a WHERE level = '$level' AND tipe_menu = 'Portal'")->result_array();
                      $no = 0;
                      $warna = "";
                    foreach ($q_portal as $p) {
                      $no++;
                      $id_menu = $p['id_menu'];
                      $m = $this->db->query("SELECT a.* FROM menu_1 a WHERE a.id = '$id_menu'")->row_array();
                        if($no == 8){
                            $no = 1;
                        }

                        if($no == 1){
                            $warna = "ungu";
                        } else if($no == 2){
                            $warna = "hijau";
                        } else if($no == 3){
                            $warna = "biru";
                        } else if($no == 4){
                            $warna = "kuning";
                        } else if($no == 5){
                            $warna = "oranye";
                        } else if($no == 6){
                            $warna = "coklat";
                        } else if($no == 7){
                            $warna = "merah";
                        }

                        // if($no == 7){
                        //     $no = 1;
                        // }
                        //
                        // if($no == 1){
                        //     $warna = "blue";
                        // } else if($no == 2){
                        //     $warna = "green";
                        // } else if($no == 3){
                        //     $warna = "light-red";
                        // } else if($no == 4){
                        //     $warna = "light-orange";
                        // } else if($no == 5){
                        //     $warna = "color";
                        // } else if($no == 6){
                        //     $warna = "red";
                        // }
                      ?>
                      <div class="col-md-4">
                          <div class="menu-item <?php echo $warna; ?>">
                              <a href="<?php echo base_url($m['link']); ?>">
                                  <i class="<?php echo $m['icon']; ?>"></i>
                                  <p><?php echo $m['nama']; ?></p>
                              </a>
                          </div>
                      </div>
                  <?php
                    }
                   ?>
                   <div class="col-md-4">
                       <div class="menu-item merah">
                           <a href="<?php echo base_url(); ?>auth/keluar"  onclick="return confirm('Anda yakin ingin Keluar Aplikasi?');">
                               <i class="fa fa-sign-out"></i>
                               <p>Log Out</p>
                           </a>
                       </div>
                   </div>
                </div>
            </div>
        </div>
        <!-- End Main Body Section -->


    </body>

</html>
