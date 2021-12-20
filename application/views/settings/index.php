<?php $this->load->view('header') ?>
<section class="content p-0">
    <div class="container-fluid px-0">
        <!-- #st_main_container is for ajax mask. So it should be on .card -->
        <div class="card card-primary" id="st_main_container" style="box-shadow: none;">

            <?php $this->load->view('page_title') ?>

            <div class="card-body sr-page-nave-container">
                <ul class="sr-page-nave nav nav-tabs" id="custom-content-above-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active px-5" id="st-list-tab" data-toggle="pill" href="#list" role="tab" aria-controls="custom-content-above-list" aria-selected="true">List</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-5" id="st-add-tab" data-toggle="pill" href="#add" role="tab" aria-controls="custom-content-above-add" aria-selected="false">Add</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-5" id="st-config-tab" data-toggle="pill" href="#config" role="tab" aria-controls="custom-content-above-config" aria-selected="false">Configs</a>
                    </li>
                </ul>

                <div class="sr-nav-content tab-content" id="custom-content-above-tabContent">
                    <div class="tab-pane fade show active" id="list" role="tabpanel" aria-labelledby="custom-content-above-list-tab">
                        <?php $this->load->view('settings/search') ?>
                        <?php $this->load->view('settings/list'); ?>
                        <?php $this->load->view('settings/show_details'); ?>
                    </div>
                    <div class="tab-pane fade" id="add" role="tabpanel" aria-labelledby="custom-content-above-add-tab">
                        <?php $this->load->view('settings/add') ?>
                    </div>
                    <div class="tab-pane fade" id="config" role="tabpanel" aria-labelledby="custom-content-above-config-tab">
                        <?php $this->load->view('settings/configs') ?>
                    </div>
                </div>
            </div>
        </div>

    </div> <!-- /.container-fluid -->
</section><!-- /.content -->


<?php $this->load->view('footer') ?>

<!-- page script -->
<script type="text/javascript">

</script>


<!-- Settings Scripts -->
<script type='text/javascript' src="dependencies/js/settings/index.js"></script>
<script type='text/javascript' src="dependencies/js/settings/list.js"></script>
<script type='text/javascript' src="dependencies/js/settings/add.js"></script>

<!-- Settings category Scripts -->
<script type='text/javascript' src="dependencies/js/settings_categories/index.js"></script>
<script type='text/javascript' src="dependencies/js/settings_categories/list.js"></script>
<script type='text/javascript' src="dependencies/js/settings_categories/add.js"></script>

<!-- Settings keys Scripts -->
<script type='text/javascript' src="dependencies/js/settings_keys/index.js"></script>
<script type='text/javascript' src="dependencies/js/settings_keys/list.js"></script>
<script type='text/javascript' src="dependencies/js/settings_keys/add.js"></script>

<!-- Table traversor. moving front/back/up/down through input elements in a container when pressing ENTER/UP/DOWN/LEFT/RIGHT Arrows.-->
<script src="dependencies/js/keyboard_navigation_advanced.js"></script>

</div> <!-- <div class="page-non-print"> -->


<!-- Table used to print reports will be loaded here -->
<?php $this->load->view('print_reports') ?>

<?php $this->load->view('html_close') ?>