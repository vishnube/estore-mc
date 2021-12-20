<?php $this->load->view('header') ?>
<section class="content p-0">
    <div class="container-fluid px-0">
        <!-- #wrd_main_container is for ajax mask. So it should be on .card -->
        <div class="card card-primary" id="wrd_main_container" style="box-shadow: none;">



            <?php $this->load->view('page_title') ?>

            <div class="card-body sr-page-nave-container">
                <ul class="sr-page-nave nav nav-tabs" id="custom-content-above-tab" role="tablist">
                    <?php
                    if ($tsk_wrd_list) {
                    ?>
                        <li class="nav-item">
                            <a class="nav-link active px-5" id="ars-list-tab" data-toggle="pill" href="#ars-list" role="tab" aria-controls="custom-content-above-list" aria-selected="true">Areas</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link px-5" id="wrd-list-tab" data-toggle="pill" href="#wrd-list" role="tab" aria-controls="custom-content-above-add" aria-selected="false">Wards</a>
                        </li>
                    <?php
                    }

                    if ($tsk_wrd_conf) {
                    ?>

                        <li class="nav-item">
                            <a class="nav-link px-5" id="wrd-config-tab" data-toggle="pill" href="#config" role="tab" aria-controls="custom-content-above-config" aria-selected="false">Configs</a>
                        </li>
                    <?php
                    }
                    ?>
                </ul>

                <div class="sr-nav-content tab-content" id="custom-content-above-tabContent">
                    <?php
                    if ($tsk_wrd_list) {
                    ?>

                        <div class="tab-pane fade show active" id="ars-list" role="tabpanel" aria-labelledby="custom-content-above-add-tab">
                            <?php $this->load->view('areas/search') ?>
                            <?php $this->load->view('areas/list') ?>
                        </div>
                        <div class="tab-pane fade" id="wrd-list" role="tabpanel" aria-labelledby="custom-content-above-list-tab">
                            <?php $this->load->view('wards/search') ?>
                            <?php $this->load->view('wards/list'); ?>
                        </div>
                    <?php
                    }

                    if ($tsk_wrd_conf) {
                    ?>
                        <div class="tab-pane fade" id="config" role="tabpanel" aria-labelledby="custom-content-above-config-tab">
                            <?php $this->load->view('wards/configs') ?>
                        </div>
                    <?php
                    }
                    ?>
                </div>
            </div>
        </div>

    </div> <!-- /.container-fluid -->
</section><!-- /.content -->


<!-- 
    If you use any Modal window (Eg:- users/add.php), don't place it under any tab container. 
    Because you can see the Modal window only if its cantainer tab is active.
    So it is good to place it just before the 'footer.php'
 -->
<?php
if ($tsk_wrd_add || $tsk_wrd_edit) {
    $this->load->view('wards/add');
    $this->load->view('areas/add');
}
?>
<?php $this->load->view('wards/show_details'); ?>

<?php $this->load->view('footer') ?>

<!-- page script -->
<script type="text/javascript">
    var tsk_wrd_list = <?= $tsk_wrd_list ? 'true' : 'false'; ?>;
    var tsk_wrd_add = <?= $tsk_wrd_add ? 'true' : 'false'; ?>;
    var tsk_wrd_edit = <?= $tsk_wrd_edit ? 'true' : 'false'; ?>;
    var tsk_wrd_activate = <?= $tsk_wrd_activate ? 'true' : 'false'; ?>;
    var tsk_wrd_deactivate = <?= $tsk_wrd_deactivate ? 'true' : 'false'; ?>;
</script>


<!-- Area Scripts -->
<script type='text/javascript' src="dependencies/js/areas/index.js"></script>
<script type='text/javascript' src="dependencies/js/areas/list.js"></script>
<script type='text/javascript' src="dependencies/js/areas/add.js"></script>
<script type='text/javascript' src="dependencies/js/areas/search.js"></script>

<!-- Ward Scripts -->
<script type='text/javascript' src="dependencies/js/wards/index.js"></script>
<script type='text/javascript' src="dependencies/js/wards/list.js"></script>
<script type='text/javascript' src="dependencies/js/wards/add.js"></script>
<script type='text/javascript' src="dependencies/js/wards/search.js"></script>

<!-- States Scripts -->
<script type='text/javascript' src="dependencies/js/states/index.js"></script>
<script type='text/javascript' src="dependencies/js/states/list.js"></script>
<script type='text/javascript' src="dependencies/js/states/add.js"></script>

<!-- Districts Scripts -->
<script type='text/javascript' src="dependencies/js/districts/index.js"></script>
<script type='text/javascript' src="dependencies/js/districts/list.js"></script>
<script type='text/javascript' src="dependencies/js/districts/add.js"></script>

<!-- Taluk Scripts -->
<script type='text/javascript' src="dependencies/js/taluks/index.js"></script>
<script type='text/javascript' src="dependencies/js/taluks/list.js"></script>
<script type='text/javascript' src="dependencies/js/taluks/add.js"></script>

</div> <!--  <div class="page-non-printable">  @ header.php-->



<!-- Table used to print reports will be loaded here -->
<?php $this->load->view('print_reports') ?>

<?php $this->load->view('html_close') ?>