<?php $this->load->view('header') ?>
<section class="content p-0">
    <div class="container-fluid px-0">
        <!-- #stk_main_container is for ajax mask. So it should be on .card -->
        <div class="card card-primary" id="stk_main_container" style="box-shadow: none;">



            <?php $this->load->view('page_title') ?>

            <div class="card-body sr-page-nave-container mt-sm-3 mt-md-4  mt-lg-5">
                <?php $this->load->view('stocks/search') ?>
                <?php $this->load->view('stocks/list'); ?>
            </div>
        </div>

    </div> <!-- /.container-fluid -->
</section><!-- /.content -->



<?php $this->load->view('footer') ?>

<!-- page script -->
<script type="text/javascript">
    var tsk_stk_list = <?= $tsk_stk_list ? 'true' : 'false'; ?>;
</script>


<!-- Stock Scripts -->
<script type='text/javascript' src="dependencies/js/stocks/list.js"></script>


</div> <!--  <div class="page-non-printable">  @ header.php-->



<!-- Table used to print reports will be loaded here -->
<?php $this->load->view('print_reports') ?>

<?php $this->load->view('html_close') ?>