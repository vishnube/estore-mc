<?php $this->load->view('header') ?>
<section class="content p-0">
    <div class="container-fluid px-0">
        <!-- #fmly_main_container is for ajax mask. So it should be on .card -->
        <div class="card card-primary" id="fmly_main_container" style="box-shadow: none;">



            <?php $this->load->view('page_title') ?>

            <div class="card-body sr-page-nave-container">
                <ul class="sr-page-nave nav nav-tabs" id="custom-content-above-tab" role="tablist">
                    <?php
                    if ($tsk_fmly_list) {
                    ?>
                        <li class="nav-item">
                            <a class="nav-link active px-5" id="fmly-list-tab" data-toggle="pill" href="#list" role="tab" aria-controls="custom-content-above-list" aria-selected="true">List</a>
                        </li>
                    <?php
                    }

                    if ($tsk_fmly_add || $tsk_fmly_edit) {
                    ?>
                        <li class="nav-item">
                            <a class="nav-link px-5" id="fmly-add-tab" data-toggle="pill" href="#add" role="tab" aria-controls="custom-content-above-add" aria-selected="false">Add</a>
                        </li>
                    <?php
                    }

                    if ($tsk_fmly_conf) {
                    ?>

                        <li class="nav-item">
                            <a class="nav-link px-5" id="fmly-config-tab" data-toggle="pill" href="#config" role="tab" aria-controls="custom-content-above-config" aria-selected="false">Configs</a>
                        </li>
                    <?php
                    }
                    ?>
                </ul>

                <div class="sr-nav-content tab-content" id="custom-content-above-tabContent">
                    <?php
                    if ($tsk_fmly_list) {
                    ?>
                        <div class="tab-pane fade show active" id="list" role="tabpanel" aria-labelledby="custom-content-above-list-tab">
                            <?php $this->load->view('families/search') ?>
                            <?php $this->load->view('families/list'); ?>
                        </div>
                    <?php
                    }

                    if ($tsk_fmly_add || $tsk_fmly_edit) {
                    ?>
                        <div class="tab-pane fade" id="add" role="tabpanel" aria-labelledby="custom-content-above-add-tab">
                            <?php $this->load->view('families/add') ?>
                        </div>
                    <?php
                    }

                    if ($tsk_fmly_conf) {
                    ?>
                        <div class="tab-pane fade" id="config" role="tabpanel" aria-labelledby="custom-content-above-config-tab">
                            <?php $this->load->view('families/configs') ?>
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

<?php $this->load->view('families/show_details'); ?>
<?php $this->load->view('family_members/add'); ?>
<?php $this->load->view('family_members/show_details'); ?>

<?php $this->load->view('footer') ?>

<!-- page script -->
<script type="text/javascript">
    var mbrtp_id = <?= $mbrtp_id; ?>;
    var tsk_fmly_list = <?= $tsk_fmly_list ? 'true' : 'false'; ?>;
    var tsk_fmly_add = <?= $tsk_fmly_add ? 'true' : 'false'; ?>;
    var tsk_fmly_edit = <?= $tsk_fmly_edit ? 'true' : 'false'; ?>;
    var tsk_fmly_activate = <?= $tsk_fmly_activate ? 'true' : 'false'; ?>;
    var tsk_fmly_deactivate = <?= $tsk_fmly_deactivate ? 'true' : 'false'; ?>;
</script>


<!-- Family Scripts -->
<script type='text/javascript' src="dependencies/js/families/index.js"></script>
<script type='text/javascript' src="dependencies/js/families/list.js"></script>
<script type='text/javascript' src="dependencies/js/families/add.js"></script>

<!-- Family Members Scripts -->
<script type='text/javascript' src="dependencies/js/family_members/index.js"></script>
<script type='text/javascript' src="dependencies/js/family_members/add.js"></script>

<!-- Family Category Scripts -->
<script type='text/javascript' src="dependencies/js/family_categories/index.js"></script>
<script type='text/javascript' src="dependencies/js/family_categories/list.js"></script>
<script type='text/javascript' src="dependencies/js/family_categories/add.js"></script>


<!-- Table traversor. moving front/back/up/down through input elements in a container when pressing ENTER/UP/DOWN/LEFT/RIGHT Arrows.-->
<script src="dependencies/js/keyboard_navigation_advanced.js"></script>

<!-- Google Map -->
<!-- <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBqobQASl8GEpBKi3S9i7C1qg054bxBSj8&callback=initMap&libraries=&v=weekly" defer></script> -->

</div> <!--  <div class="page-non-printable">  @ header.php-->



<!-- Table used to print reports will be loaded here -->
<?php $this->load->view('print_reports') ?>

<?php $this->load->view('html_close') ?>