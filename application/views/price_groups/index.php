<?php $this->load->view('header') ?>


<style type="text/css">
    /* .pgprd-index @ price_groups_products\index.php*/
    .pgprd-index tr:focus-within td {
        background-color: aquamarine;
    }


    .pgprd-index .btn-unsel-prd {
        border: none;
        background-color: transparent;
    }
</style>


<section class="content p-0">
    <div class="container-fluid px-0">
        <!-- #pgp_main_container is for ajax mask. So it should be on .card -->
        <div class="card card-primary" id="pgp_main_container" style="box-shadow: none;">



            <?php $this->load->view('page_title') ?>

            <div class="card-body sr-page-nave-container">
                <ul class="sr-page-nave nav nav-tabs" id="custom-content-above-tab" role="tablist">
                    <?php
                    if ($tsk_pgp_list) {
                    ?>

                        <li class="nav-item">
                            <a class="nav-link active px-5" id="pgp-list-tab" data-toggle="pill" href="#pgp-list" role="tab" aria-controls="custom-content-above-add" aria-selected="true">Price Group</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link px-5" id="pgprd-tab" data-toggle="pill" href="#pgprd" role="tab" aria-controls="custom-content-above-list" aria-selected="false">Products</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link px-5" id="pgpl-tab" data-toggle="pill" href="#pgpl" role="tab" aria-controls="custom-content-above-list" aria-selected="false">Locations</a>
                        </li>
                    <?php
                    }

                    if ($tsk_pgp_conf) {
                    ?>

                        <li class="nav-item">
                            <a class="nav-link px-5" id="pgp-config-tab" data-toggle="pill" href="#config" role="tab" aria-controls="custom-content-above-config" aria-selected="false">Configs</a>
                        </li>
                    <?php
                    }
                    ?>
                </ul>

                <div class="sr-nav-content tab-content" id="custom-content-above-tabContent">
                    <?php
                    if ($tsk_pgp_list) {
                    ?>
                        <div class="tab-pane fade show active" id="pgp-list" role="tabpanel" aria-labelledby="custom-content-above-list-tab">
                            <?php $this->load->view('price_groups/search') ?>
                            <?php $this->load->view('price_groups/list'); ?>
                        </div>

                        <div class="tab-pane fade" id="pgprd" role="tabpanel" aria-labelledby="custom-content-above-add-tab">
                            <?php $this->load->view('price_groups_products/index')  ?>
                        </div>

                        <div class="tab-pane fade" id="pgpl" role="tabpanel" aria-labelledby="custom-content-above-add-tab">
                            <?php $this->load->view('price_group_locations/search')  ?>
                            <?php $this->load->view('price_group_locations/list')  ?>
                        </div>
                    <?php
                    }

                    if ($tsk_pgp_conf) {
                    ?>
                        <div class="tab-pane fade" id="config" role="tabpanel" aria-labelledby="custom-content-above-config-tab">
                            <?php $this->load->view('price_groups/configs') ?>
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
if ($tsk_pgp_add || $tsk_pgp_edit) {
    $this->load->view('price_groups/add');
    $this->load->view('price_group_locations/add');
    $this->load->view('price_groups_products/edit');
}
$this->load->view('price_groups_products/product_details');
$this->load->view('price_groups_products/central_store_stock');

// To work this need the script 'js/stock_avg/stock_flow.js', 'bcmath-min.js' and 'php_function_equivalent_for_js.js' 
// And include following line of code in your controller index() function
//      $cstr_mbrtp_id = $this->central_stores->get_member_type_id();
//      $data['cstr_mbr_option'] = $this->central_stores->get_members_option(array('mbr_fk_clients' => $this->clnt_id), ACTIVE, $cstr_mbrtp_id);
//      $data['prd_option'] = $this->products->get_active_option(array('prd_fk_clients' => $this->clnt_id));
$this->load->view('stock_avg/stock_flow');
?>

<?php $this->load->view('footer') ?>

<!-- page script -->
<script type="text/javascript">
    var tsk_pgp_list = <?= $tsk_pgp_list ? 'true' : 'false'; ?>;
    var tsk_pgp_add = <?= $tsk_pgp_add ? 'true' : 'false'; ?>;
    var tsk_pgp_edit = <?= $tsk_pgp_edit ? 'true' : 'false'; ?>;
    var tsk_pgp_activate = <?= $tsk_pgp_activate ? 'true' : 'false'; ?>;
    var tsk_pgp_deactivate = <?= $tsk_pgp_deactivate ? 'true' : 'false'; ?>;
</script>

<!-- price group Scripts -->
<script type='text/javascript' src="dependencies/js/price_groups/index.js"></script>
<script type='text/javascript' src="dependencies/js/price_groups/list.js"></script>
<script type='text/javascript' src="dependencies/js/price_groups/add.js"></script>
<script type='text/javascript' src="dependencies/js/price_groups/search.js"></script>

<!-- price group products Scripts -->
<script type='text/javascript' src="dependencies/js/price_groups_products/index.js"></script>
<script type='text/javascript' src="dependencies/js/price_groups_products/list.js"></script>
<script type='text/javascript' src="dependencies/js/price_groups_products/add.js"></script>
<script type='text/javascript' src="dependencies/js/price_groups_products/add_2.js"></script>
<script type='text/javascript' src="dependencies/js/price_groups_products/search.js"></script>

<!-- price group Location Scripts -->
<script type='text/javascript' src="dependencies/js/price_group_locations/index.js"></script>
<script type='text/javascript' src="dependencies/js/price_group_locations/list.js"></script>
<script type='text/javascript' src="dependencies/js/price_group_locations/add.js"></script>
<script type='text/javascript' src="dependencies/js/price_group_locations/search.js"></script>

<!-- Other Scripts -->
<script type='text/javascript' src="dependencies/js/bcmath-min.js"></script>
<script type='text/javascript' src="dependencies/js/php_function_equivalent_for_js.js"></script>

<!-- To work this script use the view stock_avg/stock_flow.php -->
<script type='text/javascript' src="dependencies/js/stock_avg/stock_flow.js"></script>


</div> <!--  <div class="page-non-printable">  @ header.php-->



<!-- Table used to print reports will be loaded here -->
<?php $this->load->view('print_reports') ?>

<?php $this->load->view('html_close') ?>