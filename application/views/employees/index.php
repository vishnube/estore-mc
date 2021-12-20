<?php $this->load->view('header') ?>
<section class="content p-0">
    <div class="container-fluid px-0">
        <!-- #emply_main_container is for ajax mask. So it should be on .card -->
        <div class="card card-primary" id="emply_main_container" style="box-shadow: none;">



            <?php $this->load->view('page_title') ?>

            <div class="card-body sr-page-nave-container">
                <ul class="sr-page-nave nav nav-tabs" id="custom-content-above-tab" role="tablist">
                    <?php
                    if ($tsk_emply_list) {
                    ?>
                        <li class="nav-item">
                            <a class="nav-link active px-5" id="emply-list-tab" data-toggle="pill" href="#list" role="tab" aria-controls="custom-content-above-list" aria-selected="true">List</a>
                        </li>
                    <?php
                    }

                    if ($tsk_emply_add) {
                    ?>
                        <li class="nav-item">
                            <a class="nav-link px-5" id="emply-add-tab" data-toggle="pill" href="#add" role="tab" aria-controls="custom-content-above-add" aria-selected="false">Add</a>
                        </li>
                    <?php
                    }

                    if ($tsk_emply_conf) {
                    ?>

                        <li class="nav-item">
                            <a class="nav-link px-5" id="emply-config-tab" data-toggle="pill" href="#config" role="tab" aria-controls="custom-content-above-config" aria-selected="false">Configs</a>
                        </li>
                    <?php
                    }
                    ?>
                </ul>

                <div class="sr-nav-content tab-content" id="custom-content-above-tabContent">
                    <?php
                    if ($tsk_emply_list) {
                    ?>
                        <div class="tab-pane fade show active" id="list" role="tabpanel" aria-labelledby="custom-content-above-list-tab">
                            <?php $this->load->view('employees/search') ?>
                            <?php $this->load->view('employees/list'); ?>
                        </div>
                    <?php
                    }

                    if ($tsk_emply_add) {
                    ?>
                        <div class="tab-pane fade" id="add" role="tabpanel" aria-labelledby="custom-content-above-add-tab">
                            <?php $this->load->view('employees/add') ?>
                        </div>
                    <?php
                    }

                    if ($tsk_emply_conf) {
                    ?>
                        <div class="tab-pane fade" id="config" role="tabpanel" aria-labelledby="custom-content-above-config-tab">
                            <?php $this->load->view('employees/configs') ?>
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

<?php $this->load->view('employees/show_details'); ?>

<?php $this->load->view('footer') ?>

<!-- page script -->
<script type="text/javascript">
    var mbrtp_id = <?= $mbrtp_id; ?>;
    var tsk_emply_list = <?= $tsk_emply_list ? 'true' : 'false'; ?>;
    var tsk_emply_add = <?= $tsk_emply_add ? 'true' : 'false'; ?>;
    var tsk_emply_edit = <?= $tsk_emply_edit ? 'true' : 'false'; ?>;
    var tsk_emply_activate = <?= $tsk_emply_activate ? 'true' : 'false'; ?>;
    var tsk_emply_deactivate = <?= $tsk_emply_deactivate ? 'true' : 'false'; ?>;
</script>


<!-- Employee Scripts -->
<script type='text/javascript' src="dependencies/js/employees/index.js"></script>
<script type='text/javascript' src="dependencies/js/employees/list.js"></script>
<script type='text/javascript' src="dependencies/js/employees/add.js"></script>

<!-- Category Scripts -->
<script type='text/javascript' src="dependencies/js/employee_categories/index.js"></script>
<script type='text/javascript' src="dependencies/js/employee_categories/list.js"></script>
<script type='text/javascript' src="dependencies/js/employee_categories/add.js"></script>

</div> <!--  <div class="page-non-printable">  @ header.php-->



<!-- Table used to print reports will be loaded here -->
<?php $this->load->view('print_reports') ?>

<?php $this->load->view('html_close') ?>