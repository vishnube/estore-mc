<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>My App</title>
    <base href="<?= base_url() ?>" />
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1" />


    <link rel="icon" type="image/x-icon" href="dependencies/images/logo/favicon.ico" />

    <!-- JQuery UI -->
    <link rel="stylesheet" href="dependencies/AdminLTE-3.0.2/plugins/jquery-ui/jquery-ui.min.css">


    <!-- Font Awesome Icons -->
    <!-- <link rel="stylesheet" href="dependencies/AdminLTE-3.0.2/plugins/fontawesome-free/css/all.min.css"> -->
    <link rel="stylesheet" href="dependencies/AdminLTE-3.0.2/plugins/fontawesome-pro-5.13.1-web/css/all.min.css">


    <!-- Ionicons -->
    <link rel="stylesheet" href="dependencies/AdminLTE-3.0.2/plugins/ionicons/ionicons.min.css">

    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="dependencies/AdminLTE-3.0.2/plugins/icheck-bootstrap/icheck-bootstrap.min.css">

    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="dependencies/plugins/sweetalert2-9.15.2/package/dist/sweetalert2.min.css">

    <!-- Toastr -->
    <link rel="stylesheet" href="dependencies/AdminLTE-3.0.2/plugins/toastr/toastr.min.css">

    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="dependencies/AdminLTE-3.0.2/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">

    <!-- Theme style -->
    <link rel="stylesheet" href="dependencies/AdminLTE-3.0.2/dist/css/adminlte.min.css">

    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    <link href="dependencies/fonts/webfonts.css" rel="stylesheet">



    <!-- daterange picker -->
    <link rel="stylesheet" href="dependencies/AdminLTE-3.0.2/plugins/daterangepicker/daterangepicker.css">

    <!-- Bootstrap Color Picker -->
    <link rel="stylesheet" href="dependencies/AdminLTE-3.0.2/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css">

    <!-- Tempusdominus Bbootstrap 4 -->
    <link rel="stylesheet" href="dependencies/AdminLTE-3.0.2/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">

    <!-- Select2 -->
    <link rel="stylesheet" href="dependencies/AdminLTE-3.0.2/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="dependencies/AdminLTE-3.0.2/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">

    <!-- Bootstrap4 Duallistbox -->
    <link rel="stylesheet" href="dependencies/AdminLTE-3.0.2/plugins/bootstrap4-duallistbox/bootstrap-duallistbox.min.css">



    <!-- Common Styles -->
    <link rel="stylesheet" href="dependencies/css/my_adminLTE.css">
    <link rel="stylesheet" href="dependencies/css/common.css">
    <link rel="stylesheet" href="dependencies/css/arrows.css">
    <link rel="stylesheet" href="dependencies/css/xs.css">
    <link rel="stylesheet" href="dependencies/css/sm.css">
    <link rel="stylesheet" href="dependencies/css/md.css">
    <link rel="stylesheet" href="dependencies/css/lg.css">
    <link rel="stylesheet" href="dependencies/css/xl.css">


    <!-- page script -->
    <script type='text/javascript'>
        function site_url(url) {
            var site_url = '<?= site_url() ?>/';
            site_url = typeof url == 'undefined' ? site_url : site_url + url
            return site_url;
        }

        function base_url(url) {
            var base_url = '<?= base_url() ?>';
            base_url = typeof url == 'undefined' ? base_url : base_url + url
            return base_url;
        }
    </script>
    <?php
    echo '<script type="text/javascript">';
    echo '  var ACTIVE  = ' . ACTIVE . ';';
    echo '  var INACTIVE  = ' . INACTIVE . ';';
    //      The value of ENVIRONMENT defined @ index.php. Usage @ views\footer.php
    echo '  var CI_ENVIRONMENT = "' . ENVIRONMENT . '";';
    echo '  var ENTER = 13;'; // keyCode of ENTER key
    echo '  var TAB = 9;'; // keyCode of TAB key

    // Firm Details
    foreach ($client as $f => $v)
        echo "  var $f = '" . str_ireplace(array("\n", "\r"), '', $v) . "';";

    echo '</script>';
    ?>

    <style type="text/css">
        @media print {
            .page-non-printable {
                display: none;
            }

            /*
            Use "sr-printable-common" class name for every print container. 
            When taking print, all body parts except '.sr-printable-common' will be hidden. */
            body * {
                visibility: hidden;
            }

            .sr-printable-common * {
                visibility: visible;
            }

            .sr-printable-common {
                visibility: visible;
                position: absolute;
                top: 1px;
                left: 1px;
            }

        }

        @media screen {
            .print-holder {
                width: 100%;
                margin-left: 100px;
                margin-bottom: 10px;
            }

            .sr-printable-common .print-data {
                -webkit-box-shadow: 10px 10px 5px 0px rgba(0, 0, 0, 0.75);
                -moz-box-shadow: 10px 10px 5px 0px rgba(0, 0, 0, 0.75);
                box-shadow: 10px 10px 5px 0px rgba(0, 0, 0, 0.75);
                border: 1px solid #dedada;
                padding: 6px;
            }
        }
    </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed <?= $MENU_APPEAR ?>">

    <!-- When we take a print, this part will be hidden. And only shows what we need to print -->
    <div class="page-non-printable">

        <!-- Site wrapper -->
        <div class="wrapper">
            <?php $this->load->view('nav_bar') ?>
            <?php $this->load->view('side_bar') ?>
        </div>
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">