<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
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


    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="dependencies/AdminLTE-3.0.2/plugins/fontawesome-pro-5.13.1-web/css/all.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="dependencies/AdminLTE-3.0.2/plugins/ionicons/ionicons.min.css">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="dependencies/AdminLTE-3.0.2/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="dependencies/AdminLTE-3.0.2/dist/css/adminlte.min.css">
    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>

<body class="hold-transition login-page">

    <?php
    $attributes = array('id' => 'login_form', 'class' => 'form-signin');
    echo form_open('login', $attributes); ?>

    <div class="login-box">
        <div class="card">
            <div class="card-header bg-dark">
                <div class="login-logo">
                    <a><img src="dependencies/images/logo/logo5-sm.png" style="height: 100px; width:100px;" alt="My App" class="brand-image img-circle elevation-3" /></a>
                </div>
            </div>
            <div class="card-body login-card-body">
                <p class="btn btn-block btn-danger">Mobile Number / Email / Username</p>
                <?php
                if ($permission_errors)
                    echo '<p class="m-0 text-center text-fuchsia">' . $permission_errors . '</p>';
                ?>
                <div class="input-group mb-3">

                    <?php
                    $data = array(
                        'name'          => 'username',
                        'id'            => 'username',
                        'class'         => 'form-control',
                        'placeholder'   => '',
                        'required'      => 'required',
                        'value'         => set_value('username')
                    );
                    echo form_input($data);
                    ?>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-user "></span>
                            <i class="fad fa-mobile-alt"></i>
                        </div>
                    </div>

                    <?= $errors ?>
                </div>



            </div>
            <!-- /.login-card-body -->
            <div class="card-footer bg-dark">
                <div class="row">
                    <div class="col-8">

                    </div>
                    <!-- /.col -->
                    <div class="col-4">
                        <?php
                        $data = array(
                            'class'         => 'btn btn-info btn-block',
                            'value'      => 'Login'
                        );
                        echo form_submit($data); ?>
                    </div>
                    <!-- /.col -->
                </div>
            </div>
        </div>
    </div>
    <!-- /.login-box -->
    <?php echo form_close(); ?>

    <!-- jQuery -->
    <script src="dependencies/AdminLTE-3.0.2/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="dependencies/AdminLTE-3.0.2/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- jquery-validation -->
    <script src="dependencies/AdminLTE-3.0.2/plugins/jquery-validation/jquery.validate.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dependencies/AdminLTE-3.0.2/dist/js/adminlte.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $("#username").focus();
        });
    </script>
</body>

</html>