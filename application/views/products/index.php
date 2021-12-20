<?php $this->load->view('header') ?>


<!-- Rich text editor -->
<link rel="stylesheet" href="dependencies/AdminLTE-3.0.2/plugins/summernote/summernote-bs4.css">

<section class="content p-0">
    <div class="container-fluid px-0">
        <!-- #prd_main_container is for ajax mask. So it should be on .card -->
        <div class="card card-primary" id="prd_main_container" style="box-shadow: none;">



            <?php $this->load->view('page_title') ?>

            <div class="card-body sr-page-nave-container">
                <ul class="sr-page-nave nav nav-tabs" id="custom-content-above-tab" role="tablist">
                    <?php
                    if ($tsk_prd_list) {
                    ?>
                        <li class="nav-item">
                            <a class="nav-link active px-5" id="prd-list-tab" data-toggle="pill" href="#list" role="tab" aria-controls="custom-content-above-list" aria-selected="true">List</a>
                        </li>
                    <?php
                    }

                    if ($tsk_prd_add) {
                    ?>
                        <li class="nav-item">
                            <a class="nav-link px-5" id="prd-add-tab" data-toggle="pill" href="#add" role="tab" aria-controls="custom-content-above-add" aria-selected="false">Add</a>
                        </li>
                    <?php
                    }

                    if ($tsk_prd_conf) {
                    ?>

                        <li class="nav-item">
                            <a class="nav-link px-5" id="prd-config-tab" data-toggle="pill" href="#config" role="tab" aria-controls="custom-content-above-config" aria-selected="false">Configs</a>
                        </li>
                    <?php
                    }
                    ?>
                </ul>

                <div class="sr-nav-content tab-content" id="custom-content-above-tabContent">
                    <?php
                    if ($tsk_prd_list) {
                    ?>
                        <div class="tab-pane fade show active" id="list" role="tabpanel" aria-labelledby="custom-content-above-list-tab">
                            <?php $this->load->view('products/search') ?>
                            <?php $this->load->view('products/list'); ?>
                        </div>
                    <?php
                    }

                    if ($tsk_prd_add) {
                    ?>
                        <div class="tab-pane fade" id="add" role="tabpanel" aria-labelledby="custom-content-above-add-tab">
                            <?php $this->load->view('products/add') ?>
                        </div>
                    <?php
                    }

                    if ($tsk_prd_conf) {
                    ?>
                        <div class="tab-pane fade" id="config" role="tabpanel" aria-labelledby="custom-content-above-config-tab">
                            <?php $this->load->view('products/configs') ?>
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

<?php $this->load->view('products/show_details'); ?>

<!-- It should be in global scope -->
<?php $this->load->view('unit_groups/add') ?>
<?php $this->load->view('product_units/add') ?>
<?php $this->load->view('hsn_details/add') ?>

<?php $this->load->view('footer') ?>

<!-- page script -->
<script type="text/javascript">
    var tsk_prd_list = <?= $tsk_prd_list ? 'true' : 'false'; ?>;
    var tsk_prd_add = <?= $tsk_prd_add ? 'true' : 'false'; ?>;
    var tsk_prd_edit = <?= $tsk_prd_edit ? 'true' : 'false'; ?>;
    var tsk_prd_activate = <?= $tsk_prd_activate ? 'true' : 'false'; ?>;
    var tsk_prd_deactivate = <?= $tsk_prd_deactivate ? 'true' : 'false'; ?>;

    $(document).ready(function() {});
</script>



<!-- For rich text editor -->
<script src="dependencies/AdminLTE-3.0.2/plugins/summernote/summernote-bs4.min.js"></script>

<!-- File Upload -->
<script src="dependencies/AdminLTE-3.0.2/plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>


<!-- Table traversor. moving front/back/up/down through input elements in a container when pressing ENTER/UP/DOWN/LEFT/RIGHT Arrows.-->
<script src="dependencies/js/keyboard_navigation_advanced.js"></script>


<!-- Product Scripts -->
<script type='text/javascript' src="dependencies/js/products/index.js"></script>
<script type='text/javascript' src="dependencies/js/products/list.js"></script>
<script type='text/javascript' src="dependencies/js/products/add.js"></script>


<!-- Product Unit Scripts -->
<script type='text/javascript' src="dependencies/js/product_units/index.js"></script>
<script type='text/javascript' src="dependencies/js/product_units/list.js"></script>
<script type='text/javascript' src="dependencies/js/product_units/add.js"></script>

<!-- PROCUCT Category Scripts -->
<script type='text/javascript' src="dependencies/js/product_categories/index.js"></script>
<script type='text/javascript' src="dependencies/js/product_categories/list.js"></script>
<script type='text/javascript' src="dependencies/js/product_categories/add.js"></script>


<!-- PROCUCT Tags -->
<script type='text/javascript' src="dependencies/js/tags/index.js"></script>
<script type='text/javascript' src="dependencies/js/tags/list.js"></script>
<script type='text/javascript' src="dependencies/js/tags/add.js"></script>


<!-- Companies -->
<script type='text/javascript' src="dependencies/js/companies/index.js"></script>
<script type='text/javascript' src="dependencies/js/companies/list.js"></script>
<script type='text/javascript' src="dependencies/js/companies/add.js"></script>


<!-- Brands -->
<script type='text/javascript' src="dependencies/js/brands/index.js"></script>
<script type='text/javascript' src="dependencies/js/brands/list.js"></script>
<script type='text/javascript' src="dependencies/js/brands/add.js"></script>


<!-- hsn_details -->
<script type='text/javascript' src="dependencies/js/hsn_details/index.js"></script>
<script type='text/javascript' src="dependencies/js/hsn_details/list.js"></script>
<script type='text/javascript' src="dependencies/js/hsn_details/add.js"></script>


<!-- Unit Groups -->
<script type='text/javascript' src="dependencies/js/unit_groups/index.js"></script>
<script type='text/javascript' src="dependencies/js/unit_groups/list.js"></script>
<script type='text/javascript' src="dependencies/js/unit_groups/add.js"></script>
<script type='text/javascript' src="dependencies/js/unit_groups/edit.js"></script>




</div> <!--  <div class="page-non-printable">  @ header.php-->



<!-- Table used to print reports will be loaded here -->
<?php $this->load->view('print_reports') ?>



<?php $this->load->view('html_close') ?>