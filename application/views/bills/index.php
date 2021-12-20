<?php $this->load->view('header') ?>
<link rel="stylesheet" href="dependencies/css/bills/style.css">
<link rel="stylesheet" href="dependencies/css/bills/product_window.css">

<section class="content p-0">
    <div class="container-fluid px-0">
        <div class="card card-primary card-outline card-outline-tabs sr-bill-type-tabs mb-0 px-4" style="box-shadow: none;">
            <div class="card-header border-bottom-0 p-0">
                <?php
                $active1 = ' active';
                ?>
                <ul class="nav nav-tabs mt-2" id="bill-type-tab" role="tablist">
                    <?php
                    if ($tasks['tsk_pchs_qtn'] || $tasks['tsk_pchs_odr'] || $tasks['tsk_pchs_bls'] || $tasks['tsk_pchs_rtn']) {
                    ?>
                        <li class="nav-item pt-0">
                            <a class="nav-link<?= $active1 ?>" id="bill-type-purchase-tab" data-toggle="pill" href="#bill-type-purchase" role="tab" aria-controls="bill-type-purchase" aria-selected="true">PURCHASE</a>
                        </li>
                    <?php
                        $active1 = '';
                    }
                    if ($tasks['tsk_sls_qtn'] || $tasks['tsk_sls_odr'] || $tasks['tsk_sls_bls'] || $tasks['tsk_sls_rtn']) {
                    ?>
                        <li class="nav-item pt-0">
                            <a class="nav-link<?= $active1 ?>" id="bill-type-sale-tab" data-toggle="pill" href="#bill-type-sale" role="tab" aria-controls="bill-type-sale" aria-selected="false">SALE</a>
                        </li>
                    <?php }  ?>
                </ul>
                <?php $this->load->view('page_title') ?>
            </div>


            <?php
            $active3 = 'show active';
            $checked = ' checked=""';
            ?>
            <div class="card-body p-0 sr-bill-type-card-body">
                <div class="tab-content p-2" id="bill-type-tabContent">
                    <?php
                    if ($tasks['tsk_pchs_qtn'] || $tasks['tsk_pchs_odr'] || $tasks['tsk_pchs_bls'] || $tasks['tsk_pchs_rtn']) {
                    ?>
                        <div class="tab-pane fade <?= $active3 ?>" id="bill-type-purchase" role="tabpanel" aria-labelledby="bill-type-purchase-tab">
                            <?php
                            if ($tasks['tsk_pchs_bls']) {
                            ?>
                                <div class="icheck-success d-inline-block ml-1">
                                    <input data-btp="PURCHASE BILL" <?= $checked ?> type="radio" id="bill_type_rad3" name="bill_type" class="bill_type" value="pchs_bls">
                                    <label for="bill_type_rad3">BILL</label>
                                </div>
                            <?php
                                $checked = '';
                            }
                            if ($tasks['tsk_pchs_qtn']) {
                            ?>
                                <div class="icheck-success d-inline-block ml-1">
                                    <input data-btp="PURCHASE QUOTATION" <?= $checked ?> type="radio" id="bill_type_rad1" name="bill_type" class="bill_type" value="pchs_qtn">
                                    <label for="bill_type_rad1">QUOTATION</label>
                                </div>
                            <?php
                                $checked = '';
                            }
                            if ($tasks['tsk_pchs_odr']) {
                            ?>
                                <div class="icheck-success d-inline-block ml-1">
                                    <input data-btp="PURCHASE ORDER" <?= $checked ?> type="radio" id="bill_type_rad2" name="bill_type" class="bill_type" value="pchs_odr">
                                    <label for="bill_type_rad2">ORDER</label>
                                </div>
                            <?php
                                $checked = '';
                            }
                            if ($tasks['tsk_pchs_rtn']) {
                            ?>
                                <div class="icheck-success d-inline-block ml-1">
                                    <input data-btp="PURCHASE RETURN" <?= $checked ?> type="radio" id="bill_type_rad4" name="bill_type" class="bill_type" value="pchs_rtn">
                                    <label for="bill_type_rad4">RETURN</label>
                                </div>
                            <?php
                                $checked = '';
                            }
                            ?>
                        </div>
                    <?php
                        $active3 = '';
                    }
                    if ($tasks['tsk_sls_qtn'] || $tasks['tsk_sls_odr'] || $tasks['tsk_sls_bls'] || $tasks['tsk_sls_rtn']) {
                    ?>
                        <div class="tab-pane fade <?= $active3 ?>" id="bill-type-sale" role="tabpanel" aria-labelledby="bill-type-sale-tab">
                            <?php
                            if ($tasks['tsk_sls_bls']) {
                            ?>
                                <div class="icheck-danger d-inline-block ml-1">
                                    <input data-btp="SALE BILL" <?= $checked ?> type="radio" id="bill_type_rad13" name="bill_type" class="bill_type" value="sls_bls">
                                    <label for="bill_type_rad13">BILL</label>
                                </div>
                            <?php
                                $checked = '';
                            }
                            if ($tasks['tsk_sls_qtn']) {
                            ?>
                                <div class="icheck-danger d-inline-block ml-1">
                                    <input data-btp="SALE QUOTATION" <?= $checked ?> type="radio" id="bill_type_rad11" name="bill_type" class="bill_type" value="sls_qtn">
                                    <label for="bill_type_rad11">QUOTATION</label>
                                </div>
                            <?php
                                $checked = '';
                            }
                            if ($tasks['tsk_sls_odr']) {
                            ?>
                                <div class="icheck-danger d-inline-block ml-1">
                                    <input data-btp="SALE ORDER" <?= $checked ?> type="radio" id="bill_type_rad12" name="bill_type" class="bill_type" value="sls_odr">
                                    <label for="bill_type_rad12">ORDER</label>
                                </div>
                            <?php
                                $checked = '';
                            }
                            if ($tasks['tsk_sls_rtn']) {
                            ?>
                                <div class="icheck-danger d-inline-block ml-1">
                                    <input data-btp="SALE RETURN" <?= $checked ?> type="radio" id="bill_type_rad14" name="bill_type" class="bill_type" value="sls_rtn">
                                    <label for="bill_type_rad14">RETURN</label>
                                </div>
                            <?php
                            }
                            ?>

                        </div>
                    <?php }  ?>
                </div>





            </div>

        </div>
        <!-- #bls_main_container is for ajax mask. So it should be on .card -->
        <div class="card card-primary  pl-1" id="bls_main_container" style="box-shadow: none;">

            <div class="card-body sr-page-nave-container">


                <ul class="sr-page-nave nav nav-tabs" id="custom-content-above-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link px-5 active" id="bls-add-tab" data-toggle="pill" href="#add11" role="tab">Add</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link px-5" id="bls-list-tab" data-toggle="pill" href="#list11" role="tab">List</a>
                    </li>

                    <!-- bls-conf-tab is used for jquery -->
                    <li class="nav-item bls-conf-tab">
                        <a class="nav-link px-5" id="bls-config-tab" data-toggle="pill" href="#config11" role="tab">Configs</a>
                    </li>
                </ul>

                <div class="sr-nav-content tab-content" id="custom-content-above-tabContent">
                    <div class="tab-pane fade pl-1 show active" id="add11" role="tabpanel">
                        <?php $this->load->view('bills/add') ?>
                    </div>
                    <div class="tab-pane fade p-2" id="list11" role="tabpanel">
                        <?php $this->load->view('bills/search') ?>
                        <?php $this->load->view('bills/list'); ?>
                    </div>
                    <div class="tab-pane fade bls-conf-tab" id="config11" role="tabpanel">
                        <?php $this->load->view('bills/configs') ?>
                    </div>
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
$this->load->view('bills/show_details');
$this->load->view('bills/product_window');
$this->load->view('bills/batch_prices');
?>

<?php
// $this->load->view('unit_groups/add');
// $this->load->view('product_units/add');
// $this->load->view('hsn_details/add');
?>

<?php $this->load->view('footer') ?>

<!-- page script -->
<script type="text/javascript">
    <?php
    echo "var tasks = { \n";
    foreach ($tasks as $k => $tsk)
        echo "$k : " . ($tsk ? 'true' : 'false') . ",\n";
    echo '};';
    ?>
</script>



<!-- File Upload -->
<!-- <script src="dependencies/AdminLTE-3.0.2/plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script> -->


<!-- Table traversor. moving front/back/up/down through input elements in a container when pressing ENTER/UP/DOWN/LEFT/RIGHT Arrows.-->
<script src="dependencies/js/keyboard_navigation_advanced.js"></script>


<!-- Bill Scripts -->
<script type='text/javascript' src="dependencies/js/bills/index.js"></script>
<script type='text/javascript' src="dependencies/js/bills/list.js"></script>
<script type='text/javascript' src="dependencies/js/bills/list_a4.js"></script>
<script type='text/javascript' src="dependencies/js/bills/add1.js"></script>
<script type='text/javascript' src="dependencies/js/bills/add2.js"></script>
<script type='text/javascript' src="dependencies/js/bills/add3.js"></script>
<script type='text/javascript' src="dependencies/js/bills/product_window.js"></script>
<script type='text/javascript' src="dependencies/js/bills/short_keys.js"></script>


<!-- Bill Batch Scripts -->
<script type='text/javascript' src="dependencies/js/bill_batches/index.js"></script>
<script type='text/javascript' src="dependencies/js/bill_batches/list.js"></script>
<script type='text/javascript' src="dependencies/js/bill_batches/add.js"></script>


<!-- Product Batch Scripts -->
<script type='text/javascript' src="dependencies/js/bills/product_batch_add.js"></script>
<script type='text/javascript' src="dependencies/js/bills/product_batch_list.js"></script>


<!-- PRICE GROUP Scripts -->
<script type='text/javascript' src="dependencies/js/bills/price_groups.js"></script>

<!-- Other important scripts -->
<script type='text/javascript' src="dependencies/js/php_function_equivalent_for_js.js"></script>
<script type='text/javascript' src="dependencies/js/bcmath-min.js"></script>
<script type='text/javascript' src="dependencies/js/clock.js"></script>
<script type='text/javascript' src="dependencies/js/timetool.js"></script>


<!-- Export HTML Table to excel/Print  -->
<script src="dependencies/js/html_table_export.js"></script>








</div> <!--  <div class="page-non-printable">  @ header.php-->



<!-- Table used to print reports will be loaded here -->
<?php $this->load->view('print_reports') ?>



<?php $this->load->view('html_close') ?>