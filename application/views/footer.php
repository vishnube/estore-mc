</div>
<!-- /.content-wrapper -->

<footer class="main-footer">
    <div class="mx-4">
        <div class="float-right d-none d-sm-block"><b>Version</b> 3.0.2</div>
        <strong>Copyright &copy; 2014-2019 <a href="http://eternalstore.co.in/">Estore.co.in</a>.</strong> All rights reserved.
    </div>
</footer>

<!-- Control Sidebar -->
<aside class="control-sidebar control-sidebar-dark sr-settings-sidebar" style="width: auto;">
    <?php $this->load->view('user_settings'); ?>
</aside>
<!-- /.control-sidebar -->


<?php $this->load->view('users/edit_profile'); ?>





<!-- REQUIRED SCRIPTS -->


<!-- ionicons Fonts @ https://ionicons.com/ -->
<!-- <script src="https://unpkg.com/ionicons@5.0.0/dist/ionicons.js"></script> -->


<!-- REQUIRED SCRIPTS -->
<!-- jQuery -->
<script src="dependencies/AdminLTE-3.0.2/plugins/jquery/jquery.min.js"></script>
<script src="dependencies/AdminLTE-3.0.2/plugins/jquery-ui/jquery-ui.min.js"></script>

<!-- Bootstrap -->
<script src="dependencies/AdminLTE-3.0.2/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- SweetAlert2 -->
<script src="dependencies/plugins/sweetalert2-9.15.2/package/dist/sweetalert2.min.js"></script>

<!-- Toastr -->
<script src="dependencies/AdminLTE-3.0.2/plugins/toastr/toastr.min.js"></script>

<!-- AdminLTE App -->
<script src="dependencies/AdminLTE-3.0.2/dist/js/adminlte.js"></script>



<!-- Select2 -->
<script src="dependencies/AdminLTE-3.0.2/plugins/select2/js/select2.full.min.js"></script>

<!-- Bootstrap4 Duallistbox -->
<script src="dependencies/AdminLTE-3.0.2/plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js"></script>

<!-- InputMask -->
<script src="dependencies/AdminLTE-3.0.2/plugins/moment/moment.min.js"></script>
<script src="dependencies/AdminLTE-3.0.2/plugins/inputmask/min/jquery.inputmask.bundle.min.js"></script>

<!-- date-range-picker -->
<script src="dependencies/AdminLTE-3.0.2/plugins/daterangepicker/daterangepicker.js"></script>

<!-- bootstrap color picker -->
<script src="dependencies/AdminLTE-3.0.2/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js"></script>

<!-- Tempusdominus Bootstrap 4 -->
<script src="dependencies/AdminLTE-3.0.2/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>

<!-- Bootstrap Switch -->
<script src="dependencies/AdminLTE-3.0.2/plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>


<!-- Export PDF -->
<script src="dependencies/plugins/pdfmake-0.2.0/build/pdfmake.min.js"></script>
<script src="dependencies/plugins/pdfmake-0.2.0/build/vfs_fonts.js"></script>
<script src="dependencies/js/export_pdf.js"></script>

<!-- Export Excel -->
<script src="dependencies/js/export_excel.js"></script>

<!-- Export Print -->
<script src="dependencies/js/export_printer.js"></script>


<!-- Common SCRIPTS -->
<script src="dependencies/plugins/overlay/loadingoverlay.min.js"></script>
<script src="dependencies/js/my_adminLTE.js"></script>
<script src="dependencies/js/common.js"></script>
<script src="dependencies/js/user_settings.js"></script>
<!-- <script src="dependencies/js/settings/export.js" type='text/javascript'></script> -->
<script src="dependencies/js/keyboard_navigation.js"></script>
<script src="dependencies/plugins/dateformat/jquery_dateformat.js"></script>

<script type="text/javascript">
    $(document).ready(function() {

        // If the application is in development mode, searching for errors (php) in the current page
        $(function() {
            // CI_ENVIRONMENT @ views\header.php
            // Its value = constant ENVIRONMENT @ index.php.
            if (CI_ENVIRONMENT == 'development') {
                var count = 0;
                count = $("*").html().match(/A PHP Error/gi).length

                // Minimum value of count will be 2 (Because the above,below codes contains itself the searching text)
                // So if any errors occured, count will be greater than 2.
                if (count > 2)
                    alert("A php error occured");
            }
        });


        $(document).on('click', '#edit_user_profile', function() {
            $('#usr_profile_edit_modal #usr_profile_edit_form').postForm(site_url("users/load_profile"), [], function(res, form) {
                form.loadFormInputs(res);
                $('#usr_profile_edit_modal').modal('show');
            });
        });



        $('form#usr_profile_edit_form').on('submit', function(e) {
            e.preventDefault();

            var input = $(this).serializeArray();

            $(this).postForm(site_url("users/edit_profile"), input, function() {
                showSuccessToast('Profile saved successfully');
            });
        });


    });
</script>