<?php $this->load->view('header') ?>
<section class="content p-0">
    <div class="container-fluid px-0">
        <!-- #estr_main_container is for ajax mask. So it should be on .card -->
        <div class="card card-primary" id="estr_main_container" style="box-shadow: none;">



            <?php $this->load->view('page_title') ?>

            <div class="card-body sr-page-nave-container">
                <ul class="sr-page-nave nav nav-tabs" id="custom-content-above-tab" role="tablist">
                    <?php
                    if ($tsk_estr_list) {
                    ?>
                        <li class="nav-item">
                            <a class="nav-link active px-5" id="estr-list-tab" data-toggle="pill" href="#list" role="tab" aria-controls="custom-content-above-list" aria-selected="true">List</a>
                        </li>
                    <?php
                    }

                    if ($tsk_estr_add || $tsk_estr_edit) {
                    ?>
                        <li class="nav-item">
                            <a class="nav-link px-5" id="estr-add-tab" data-toggle="pill" href="#add" role="tab" aria-controls="custom-content-above-add" aria-selected="false">Add</a>
                        </li>
                    <?php
                    }

                    if ($tsk_estr_conf) {
                    ?>

                        <li class="nav-item">
                            <a class="nav-link px-5" id="estr-config-tab" data-toggle="pill" href="#config" role="tab" aria-controls="custom-content-above-config" aria-selected="false">Configs</a>
                        </li>
                    <?php
                    }
                    ?>
                </ul>

                <div class="sr-nav-content tab-content" id="custom-content-above-tabContent">
                    <?php
                    if ($tsk_estr_list) {
                    ?>
                        <div class="tab-pane fade show active" id="list" role="tabpanel" aria-labelledby="custom-content-above-list-tab">
                            <?php $this->load->view('estores/search') ?>
                            <?php $this->load->view('estores/list'); ?>
                        </div>
                    <?php
                    }

                    if ($tsk_estr_add || $tsk_estr_edit) {
                    ?>
                        <div class="tab-pane fade" id="add" role="tabpanel" aria-labelledby="custom-content-above-add-tab">
                            <?php $this->load->view('estores/add') ?>
                        </div>
                    <?php
                    }

                    if ($tsk_estr_conf) {
                    ?>
                        <div class="tab-pane fade" id="config" role="tabpanel" aria-labelledby="custom-content-above-config-tab">
                            <?php $this->load->view('estores/configs') ?>
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

<?php $this->load->view('estores/show_details'); ?>

<?php $this->load->view('footer') ?>

<!-- page script -->
<script type="text/javascript">
    var mbrtp_id = <?= $mbrtp_id; ?>;
    var tsk_estr_list = <?= $tsk_estr_list ? 'true' : 'false'; ?>;
    var tsk_estr_add = <?= $tsk_estr_add ? 'true' : 'false'; ?>;
    var tsk_estr_edit = <?= $tsk_estr_edit ? 'true' : 'false'; ?>;
    var tsk_estr_activate = <?= $tsk_estr_activate ? 'true' : 'false'; ?>;
    var tsk_estr_deactivate = <?= $tsk_estr_deactivate ? 'true' : 'false'; ?>;
</script>


<!-- estore Scripts -->
<script type='text/javascript' src="dependencies/js/estores/index.js"></script>
<script type='text/javascript' src="dependencies/js/estores/list.js"></script>
<script type='text/javascript' src="dependencies/js/estores/add.js"></script>

<!-- Category Scripts -->
<script type='text/javascript' src="dependencies/js/estore_categories/index.js"></script>
<script type='text/javascript' src="dependencies/js/estore_categories/list.js"></script>
<script type='text/javascript' src="dependencies/js/estore_categories/add.js"></script>

</div> <!--  <div class="page-non-printable">  @ header.php-->



<!-- Table used to print reports will be loaded here -->
<?php $this->load->view('print_reports') ?>

<?php $this->load->view('html_close') ?>