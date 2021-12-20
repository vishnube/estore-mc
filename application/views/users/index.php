<?php $this->load->view('header') ?>

<section class="content p-0">
    <div class="container-fluid px-0">
        <!-- #usr_main_container is for ajax mask. So it should be on .card -->
        <div class="card card-primary" id="usr_main_container" style="box-shadow: none;">


            <?php $this->load->view('page_title') ?>

            <div class="card-body sr-page-nave-container">
                <ul class="sr-page-nave nav nav-tabs" id="custom-content-above-tab" role="tablist">
                    <?php
                    if ($tsk_usr_list) {
                    ?>
                        <li class="nav-item">
                            <a class="nav-link active px-5" id="user-list-tab" data-toggle="pill" href="#list" role="tab" aria-controls="custom-content-above-list" aria-selected="true">LIST</a>
                        </li>
                    <?php
                    }
                    if ($tsk_usr_utsk) {
                    ?>
                        <li class="nav-item">
                            <a class="nav-link px-5" id="user-task-tab" data-toggle="pill" href="#task" role="tab" aria-controls="custom-content-above-task" aria-selected="true">TASKS</a>
                        </li>
                    <?php
                    }
                    if ($tsk_usr_ucs) {
                    ?>
                        <li class="nav-item">
                            <a class="nav-link px-5" id="user-cstr-tab" data-toggle="pill" href="#cstr" role="tab" aria-controls="custom-content-above-cstr" aria-selected="true">CENTRAL STORES</a>
                        </li>
                    <?php
                    }
                    if ($tsk_usr_conf) {
                    ?>

                        <li class="nav-item">
                            <a class="nav-link px-5" id="user-config-tab" data-toggle="pill" href="#config" role="tab" aria-controls="custom-content-above-config" aria-selected="true">CONFIG</a>
                        </li>
                    <?php
                    }
                    if ($tsk_usr_add) {
                    ?>

                        <li class="nav-item">
                            <button class="btn btn-danger ml-2" id="add_usr">
                                <i class="fas fa-plus cursor-pointer"></i>&nbsp; ADD USER
                            </button>
                        </li>

                    <?php } ?>
                </ul>

                <div class="sr-nav-content tab-content" id="custom-content-above-tabContent">
                    <?php
                    if ($tsk_usr_list) {
                    ?>
                        <div class="tab-pane fade show active" id="list" role="tabpanel" aria-labelledby="custom-content-above-list-tab">
                            <?php $this->load->view('users/search') ?>
                            <?php $this->load->view('users/list'); ?>
                        </div>
                    <?php
                    }
                    if ($tsk_usr_utsk) {
                    ?>
                        <div class="tab-pane fade" id="task" role="tabpanel" aria-labelledby="custom-content-above-task-tab">
                            <?php $this->load->view('user_tasks/list') ?>
                        </div>
                    <?php
                    }
                    if ($tsk_usr_ucs) {
                    ?>
                        <div class="tab-pane fade" id="cstr" role="tabpanel" aria-labelledby="custom-content-above-cstr-tab">
                            <?php $this->load->view('user_central_stores/list') ?>
                        </div>
                    <?php
                    }
                    if ($tsk_usr_conf) {
                    ?>
                        <div class="tab-pane fade" id="config" role="tabpanel" aria-labelledby="custom-content-above-config-tab">
                            <?php $this->load->view('users/configs') ?>
                        </div>
                    <?php } ?>
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
<?php $this->load->view('users/add') ?>
<?php $this->load->view('users/edit') ?>

<?php $this->load->view('footer') ?>

<!-- page script -->
<script type="text/javascript">
    var tsk_usr_list = <?= $tsk_usr_list ? 'true' : 'false'; ?>;
    var tsk_usr_add = <?= $tsk_usr_add ? 'true' : 'false'; ?>;
    var tsk_usr_edit = <?= $tsk_usr_edit ? 'true' : 'false'; ?>;
    var tsk_usr_activate = <?= $tsk_usr_activate ? 'true' : 'false'; ?>;
    var tsk_usr_deactivate = <?= $tsk_usr_deactivate ? 'true' : 'false'; ?>;

    $(document).on('show.bs.collapse', '.list-container', function() {
        // For dynamically loaded bootstrap switches.
        $("input[data-bootstrap-switch]").each(function() {
            $(this).bootstrapSwitch('state', $(this).prop('checked'));
        });
        $(this).find('li a .handler').removeClass('fa-caret-down').addClass('fa-caret-up');
    });
    $(document).on('hide.bs.collapse', '.list-container', function() {
        $(this).find('li a .handler').removeClass('fa-caret-up').addClass('fa-caret-down');
    });
    $(document).on('shown.bs.collapse', '.list-container', function() {
        // For dynamically loaded bootstrap switches.
        $("input[data-bootstrap-switch]").each(function() {
            $(this).bootstrapSwitch('state', $(this).prop('checked'));
        });
    });

    $('#collapse-task').click(function() {
        $('.child-container').collapse('hide')
    });

    $('#expand-task').click(function() {
        $('.child-container').collapse('show');
    });
</script>


<!-- User Scripts -->
<script type='text/javascript' src="dependencies/js/users/index.js"></script>
<script type='text/javascript' src="dependencies/js/users/list.js"></script>
<script type='text/javascript' src="dependencies/js/users/add.js"></script>

<!-- Group Scripts -->
<script type='text/javascript' src="dependencies/js/groups/index.js"></script>
<script type='text/javascript' src="dependencies/js/groups/list.js"></script>
<script type='text/javascript' src="dependencies/js/groups/add.js"></script>


<!-- User Task Scripts -->
<script type='text/javascript' src="dependencies/js/user_tasks/list.js"></script>


<!-- User Central Store Scripts -->
<script type='text/javascript' src="dependencies/js/user_central_stores/list.js"></script>


</div> <!-- <div class="page-non-printable"> @ header.php-->


<?php $this->load->view('print_reports') ?>

<?php $this->load->view('html_close') ?>