<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" type="image/png" sizes="16x16" href="../plugins/images/favicon.png">
    <title>Admin Website MDE Clinic</title>
    <!-- Bootstrap Core CSS -->
    <link href="<?php echo base_url(); ?>assets_admin/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets_admin/plugins/bower_components/bootstrap-extension/css/bootstrap-extension.css" rel="stylesheet">
    <!-- Menu CSS -->
    <link href="<?php echo base_url(); ?>assets_admin/plugins/bower_components/sidebar-nav/dist/sidebar-nav.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets_admin/plugins/bower_components/dropify/dist/css/dropify.min.css">
    <!-- toast CSS -->
    <link href="<?php echo base_url(); ?>assets_admin/plugins/bower_components/toast-master/css/jquery.toast.css" rel="stylesheet">
    <!-- morris CSS -->
    <link href="<?php echo base_url(); ?>assets_admin/plugins/bower_components/morrisjs/morris.css" rel="stylesheet">
    <!-- animation CSS -->
    <link href="<?php echo base_url(); ?>assets_admin/css/animate.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="<?php echo base_url(); ?>assets_admin/css/style.css" rel="stylesheet">
    <!-- color CSS -->
    <link href="<?php echo base_url(); ?>assets_admin/css/colors/green.css" id="theme" rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
    <script>
    (function(i, s, o, g, r, a, m) {
        i['GoogleAnalyticsObject'] = r;
        i[r] = i[r] || function() {
            (i[r].q = i[r].q || []).push(arguments)
        }, i[r].l = 1 * new Date();
        a = s.createElement(o),
            m = s.getElementsByTagName(o)[0];
        a.async = 1;
        a.src = g;
        m.parentNode.insertBefore(a, m)
    })(window, document, 'script', 'https://www.google-analytics.com/analytics.js', 'ga');

    ga('create', 'UA-19175540-9', 'auto');
    ga('send', 'pageview');
    </script>

    <!-- /#wrapper -->
    <!-- jQuery -->
    <script src="<?php echo base_url(); ?>assets_admin/plugins/bower_components/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="<?php echo base_url(); ?>assets_admin/bootstrap/dist/js/tether.min.js"></script>
    <script src="<?php echo base_url(); ?>assets_admin/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="<?php echo base_url(); ?>assets_admin/plugins/bower_components/bootstrap-extension/js/bootstrap-extension.min.js"></script>
    <!-- Menu Plugin JavaScript -->
    <script src="<?php echo base_url(); ?>assets_admin/plugins/bower_components/sidebar-nav/dist/sidebar-nav.min.js"></script>
    <!--slimscroll JavaScript -->
    <script src="<?php echo base_url(); ?>assets_admin/js/jquery.slimscroll.js"></script>
    <!--Wave Effects -->
    <script src="<?php echo base_url(); ?>assets_admin/js/waves.js"></script>
    <!--Counter js -->
    <script src="<?php echo base_url(); ?>assets_admin/plugins/bower_components/waypoints/lib/jquery.waypoints.js"></script>
    <script src="<?php echo base_url(); ?>assets_admin/plugins/bower_components/counterup/jquery.counterup.min.js"></script>
    <!--Morris JavaScript -->
    <script src="<?php echo base_url(); ?>assets_admin/plugins/bower_components/raphael/raphael-min.js"></script>
    <script src="<?php echo base_url(); ?>assets_admin/plugins/bower_components/morrisjs/morris.js"></script>
    <!-- Custom Theme JavaScript -->
    <script src="<?php echo base_url(); ?>assets_admin/js/custom.min.js"></script>
    <script src="<?php echo base_url(); ?>assets_admin/js/dashboard1.js"></script>
    <!-- Sparkline chart JavaScript -->
    <!-- <script src="<?php echo base_url(); ?>assets_admin/plugins/bower_components/jquery-sparkline/jquery.sparkline.min.js"></script>
    <script src="<?php echo base_url(); ?>assets_admin/plugins/bower_components/jquery-sparkline/jquery.charts-sparkline.js"></script>
    <script src="<?php echo base_url(); ?>assets_admin/plugins/bower_components/toast-master/js/jquery.toast.js"></script> -->

    <!-- wysuhtml5 Plugin JavaScript -->
    <script src="<?php echo base_url(); ?>assets_admin/plugins/bower_components/tinymce/tinymce.min.js"></script>

    <!-- jQuery file upload -->
    <script src="<?php echo base_url(); ?>assets_admin/plugins/bower_components/dropify/dist/js/dropify.min.js"></script>

    <!--Style Switcher -->
    <script src="<?php echo base_url(); ?>assets_admin/plugins/bower_components/styleswitcher/jQuery.style.switcher.js"></script>

    <!--Style Custom -->
    <!-- <script type="text/javascript" src="<?php echo base_url(); ?>assets_admin/js/script.js"></script> -->
    <script type="text/javascript" src="<?php echo base_url(); ?>assets_admin/js/js-form.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets_admin/js/pagination.js"></script>
</head>

<body>
    <!-- Preloader -->
    <div class="preloader">
        <div class="cssload-speeding-wheel"></div>
    </div>
    <div id="wrapper">
        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top m-b-0">
            <div class="navbar-header"> <a class="navbar-toggle hidden-sm hidden-md hidden-lg " href="javascript:void(0)" data-toggle="collapse" data-target=".navbar-collapse"><i class="ti-menu"></i></a>
                <div class="top-left-part">
                    <a class="logo" href="index.html">
                        <b>
                            <img src="<?php echo base_url(); ?>assets_admin/plugins/images/eliteadmin-logo.png" alt="home" class="dark-logo" />
                            <img src="<?php echo base_url(); ?>assets_admin/plugins/images/eliteadmin-logo-dark.png" alt="home" class="light-logo" />
                        </b>
                        <span class="hidden-xs"><img src="<?php echo base_url(); ?>assets_admin/plugins/images/eliteadmin-text.png" alt="home" class="dark-logo" />
                            <img src="<?php echo base_url(); ?>assets_admin/plugins/images/eliteadmin-text-dark.png" alt="home" class="light-logo" />
                        </span>
                    </a>
                </div>
                <ul class="nav navbar-top-links navbar-left hidden-xs">
                    <li><a href="javascript:void(0)" class="open-close hidden-xs waves-effect waves-light"><i class="icon-arrow-left-circle ti-menu"></i></a></li>
                </ul>
            </div>
            <!-- /.navbar-header -->
            <!-- /.navbar-top-links -->
            <!-- /.navbar-static-side -->
        </nav>
        <!-- Left navbar-header -->
        <div class="navbar-default sidebar" role="navigation">
            <div class="sidebar-nav navbar-collapse slimscrollsidebar">
                <div class="user-profile">
                    <div class="dropdown user-pro-body">
                        <div><img src="<?php echo base_url(); ?>storage/avatar.png" alt="user-img" class="img-circle"></div>
                        <a href="#" class="dropdown-toggle u-dropdown" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo $this->session->userdata('nama'); ?> <span class="caret"></span></a>
                        <ul class="dropdown-menu animated flipInY">
                            <li><a href="<?php echo base_url(); ?>admin/auth/keluar" onclick="return confirm('Apakah anda ingin keluar aplikasi?')"><i class="fa fa-power-off"></i> Logout</a></li>
                        </ul>
                    </div>
                </div>

                <ul class="nav" id="side-menu">
                    <li class="sidebar-search hidden-sm hidden-md hidden-lg">
                        <!-- input-group -->
                        <div class="input-group custom-search-form">
                            <input type="text" class="form-control" placeholder="Search...">
                            <span class="input-group-btn">
                                <button class="btn btn-default" type="button"> <i class="fa fa-search"></i> </button>
                            </span>
                        </div>
                        <!-- /input-group -->
                    </li>
                    <li class="nav-small-cap m-t-10">--- Main Menu</li>
                    <li><a href="<?php echo base_url(); ?>admin/about_us" class="waves-effect"><i class="fa fa-info"></i> <span class="hide-menu"> About Us</span></a></li>
                    <li> <a href="index.html" class="waves-effect"><i class="fa fa-heart" data-icon="v"></i> <span class="hide-menu"> Services <span class="fa arrow"></span></span></a>
                        <ul class="nav nav-second-level">
                            <li> <a href="<?php echo base_url(); ?>admin/services/medical_treatment_view">Medical Treatment</a> </li>
                            <li> <a href="<?php echo base_url(); ?>admin/services/non_medical_treatment_view">Non Medical Treatment</a> </li>
                        </ul>
                    </li>
                    <li> <a href="index.html" class="waves-effect"><i class="fa fa-glass" data-icon="v"></i> <span class="hide-menu"> Master Produk <span class="fa arrow"></span></span></a>
                        <ul class="nav nav-second-level">
                            <li> <a href="<?php echo base_url(); ?>admin/kategori_produk">Kategori Produk</a> </li>
                            <li> <a href="<?php echo base_url(); ?>admin/produk">Produk</a> </li>
                        </ul>
                    </li>
                    <li> <a href="index.html" class="waves-effect"><i class="fa fa-folder-open" data-icon="v"></i> <span class="hide-menu"> Master Artikel <span class="fa arrow"></span></span></a>
                        <ul class="nav nav-second-level">
                            <li> <a href="<?php echo base_url(); ?>admin/kategori_artikel">Kategori Artikel</a> </li>
                            <li> <a href="<?php echo base_url(); ?>admin/artikel">Artikel</a> </li>
                        </ul>
                    </li>
                    <li> <a href="index.html" class="waves-effect"><i class="fa fa-file-image-o" data-icon="v"></i> <span class="hide-menu"> Gallery <span class="fa arrow"></span></span></a>
                        <ul class="nav nav-second-level">
                            <li> <a href="<?php echo base_url(); ?>admin/picture">Picture</a> </li>
                            <li> <a href="<?php echo base_url(); ?>admin/video">Video</a> </li>
                        </ul>
                    </li>
                    <li><a href="<?php echo base_url(); ?>admin/price_list" class="waves-effect"><i class="fa fa-dollar"></i> <span class="hide-menu"> Price List</span></a></li>
                    <li><a href="<?php echo base_url(); ?>admin/testimoni" class="waves-effect"><i class="fa fa-comments"></i> <span class="hide-menu"> Testimoni</span></a></li>
                    <li><a href="<?php echo base_url(); ?>admin/user" class="waves-effect"><i class="fa fa-user"></i> <span class="hide-menu"> User</span></a></li>
                </ul>
            </div>
        </div>
        <!-- Left navbar-header end -->
