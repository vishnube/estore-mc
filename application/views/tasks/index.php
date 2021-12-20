<?php $this->load->view('header') ?>
<section class="content p-0">
    <div class="container-fluid px-0">
        <!-- #tsk_main_container is for ajax mask. So it should be on .card -->
        <div class="card card-primary" id="tsk_main_container" style="box-shadow: none;">

            <?php $this->load->view('page_title') ?>

            <div class="card-body sr-page-nave-container">
                <ul class="sr-page-nave nav nav-tabs" id="custom-content-above-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active px-5" id="tsk-list-tab" data-toggle="pill" href="#list" role="tab" aria-controls="custom-content-above-list" aria-selected="true">List</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-5" id="tsk-add-tab" data-toggle="pill" href="#add" role="tab" aria-controls="custom-content-above-add" aria-selected="false">Add</a>
                    </li>
                </ul>

                <div class="sr-nav-content tab-content" id="custom-content-above-tabContent">
                    <div class="tab-pane fade show active" id="list" role="tabpanel" aria-labelledby="custom-content-above-list-tab">
                        <?php $this->load->view('tasks/list'); ?>
                    </div>
                    <div class="tab-pane fade" id="add" role="tabpanel" aria-labelledby="custom-content-above-add-tab">
                        <?php $this->load->view('tasks/add') ?>
                    </div>
                </div>
            </div>
        </div>

    </div> <!-- /.container-fluid -->
</section><!-- /.content -->


<?php $this->load->view('tasks/add_many') ?>
<?php $this->load->view('footer') ?>

<!-- page script -->
<script type="text/javascript">
    $(document).on('show.bs.collapse', '.list-container', function() {
        $(this).find('li a .handler').removeClass('fa-caret-down').addClass('fa-caret-up');
    });
    $(document).on('hide.bs.collapse', '.list-container', function() {
        $(this).find('li a .handler').removeClass('fa-caret-up').addClass('fa-caret-down');
    });

    $('#collapse-task').click(function() {
        $('.child-container').collapse('hide')
    });

    $('#expand-task').click(function() {
        $('.child-container').collapse('show')
    });
</script>


<!-- Task Scripts -->
<script type='text/javascript' src="dependencies/js/tasks/index.js"></script>
<script type='text/javascript' src="dependencies/js/tasks/list.js"></script>
<script type='text/javascript' src="dependencies/js/tasks/add.js"></script>
<script type='text/javascript' src="dependencies/js/tasks/add_many.js"></script>


<!-- Table traversor. moving front/back/up/down through input elements in a container when pressing ENTER/UP/DOWN/LEFT/RIGHT Arrows.-->
<script src="dependencies/js/keyboard_navigation_advanced.js"></script>

</div> <!-- <div class="page-non-printable"> @ header.php-->

<?php $this->load->view('html_close') ?>