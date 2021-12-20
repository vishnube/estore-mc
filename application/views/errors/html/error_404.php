<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>404 Page not found</title>
	<base href="<?= config_item('base_url') ?>" />

	<!-- Tell the browser to be responsive to screen width -->
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- Font Awesome -->
	<link rel="stylesheet" href="dependencies/AdminLTE-3.0.2/plugins/fontawesome-free/css/all.min.css">
	<!-- Ionicons -->
	<link rel="stylesheet" href="dependencies/AdminLTE-3.0.2/plugins/ionicons/ionicons.min.css">
	<!-- Theme style -->
	<link rel="stylesheet" href="dependencies/AdminLTE-3.0.2/dist/css/adminlte.min.css">
	<!-- Google Font: Source Sans Pro -->
	<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>

<body class="hold-transition sidebar-mini">
	<div class="wrapper">

		<!-- Content Wrapper. Contains page content -->
		<div class="content-wrapper m-0">
			<!-- Content Header (Page header) -->
			<section class="content-header">
				<div class="container-fluid">
					<div class="row mb-2">
						<div class="col-sm-6">
							<h1>404 Error Page</h1>
						</div>
						<div class="col-sm-6">
							<ol class="breadcrumb float-sm-right">
								<li class="breadcrumb-item active">404 Error Page</li>
							</ol>
						</div>
					</div>
				</div><!-- /.container-fluid -->
			</section>

			<!-- Main content -->
			<section class="content">
				<div class="error-page">


					<h2 class="headline text-warning"> 404</h2>

					<div class="error-content">
						<h3><i class="fas fa-exclamation-triangle text-warning"></i> Oops! <?php echo $heading; ?></h3>

						<p>
							<?php echo $message; ?>
						</p>


					</div>
					<!-- /.error-content -->
				</div>
				<!-- /.error-page -->
			</section>
			<!-- /.content -->
		</div>
		<!-- /.content-wrapper -->
		<footer class="main-footer">
			<div class="float-right d-none d-sm-block">
				<b>404</b> ERROR PAGE
			</div>
			<strong>Please check the page you visited</strong>
		</footer>
	</div>
	<!-- ./wrapper -->

	<!-- jQuery -->
	<script src="dependencies/AdminLTE-3.0.2/plugins/jquery/jquery.min.js"></script>
	<!-- Bootstrap 4 -->
	<script src="dependencies/AdminLTE-3.0.2/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
	<!-- AdminLTE App -->
	<script src="dependencies/AdminLTE-3.0.2/dist/js/adminlte.min.js"></script>
	<!-- AdminLTE for demo purposes -->
	<script src="dependencies/AdminLTE-3.0.2/dist/js/demo.js"></script>
</body>

</html>