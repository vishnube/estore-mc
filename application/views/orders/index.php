<?php $this->load->view('header') ?>

<link rel="stylesheet" href="dependencies/css/bills/product_window.css">

<!-- To view tooltip in "order to bill" converting view -->
<link rel="stylesheet" href="dependencies/js/orders/tooltip/srtip.css">

<style type="text/css">
    .badge-pink {
        color: #fff;
        background-color: #e83e8c;
    }

    .badge-yellow {
        color: #000;
        background-color: #ffc107;
    }

    .badge-teal {
        color: #fff;
        background-color: #20c997;
    }

    #tbl_odr .tr-top td {
        border-top: 1px solid;
        border-top-color: #e83e8c !important;
        border-bottom: none;
    }

    #tbl_odr .tr-bottom td {
        border-top: none;
        border-bottom: 1px solid;
        border-bottom-color: #e83e8c !important;
    }

    #tbl_odr .dv-logger {
        display: inline-block;
        border: 1px solid #ffc107;
        border-radius: 5px 5px 0 0;
        width: 150px;
        margin-right: 10px;
    }

    #tbl_odr .dv-logger .tp {
        text-align: center;
        padding: 3px;
    }

    #tbl_odr .dv-logger .bt {
        text-align: center;
        padding: 3px;
        background-color: blanchedalmond;
        font-size: 12px;
        font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
    }

    #tbl_odr .dv-logger .usr {
        color: #0e0ee4;
    }

    #tbl_odr .dv-logger .tm {
        color: #b60606;
    }

    #tbl_odr .level-dot {
        width: 20px;
        height: 20px;
        display: inline-block;
    }

    #tbl_odr .level-nm {
        width: 20px;
        height: 20px;
        display: inline-block;
        padding-left: 10px;
    }

    .bg-srlight {
        background-color: #eff0f1 !important;
    }

    #add_bill_modal thead th {
        background-color: #eff0f1 !important;
    }

    #add_bill_modal .scrap {
        border: 1px solid #e5e5e5;
        font-size: 12px;
        padding: 2px 5px;
        background-color: #ededed;
        border-radius: 5px;
    }

    #add_bill_modal .item-moved {
        display: none;
    }

    #add_bill_modal .v_error {
        position: relative;
        display: inline-block;
    }

    #add_bill_modal .v_error .v_error_txt {
        font-size: 12px;
        padding: 2px 5px;
        background-color: #ededed;
        border-radius: 5px;
        visibility: hidden;
        text-align: center;
        border-radius: 6px;
        position: absolute;
        z-index: 1;
        top: -15px;
        left: 0;
        color: red;
    }

    #add_bill_modal .tot-val {
        font-size: 18px;
    }

    .sr-badge {
        width: 50px;
        height: 30px;
        padding-top: 13px;
        border-radius: 50rem !important;
        display: inline-block;
        font-weight: 700;
        font-size: 18px;
        line-height: 1px;
        text-align: center;
        white-space: nowrap;
        vertical-align: baseline;
        border-radius: 0.25rem;
        transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }
</style>

<section class="content p-0">
    <div class="container-fluid px-0">
        <!-- #odr_main_container is for ajax mask. So it should be on .card -->
        <div class="card card-primary" id="odr_main_container" style="box-shadow: none;">



            <?php $this->load->view('page_title') ?>

            <div class="card-body sr-page-nave-container pl-5">
                <ul class="sr-page-nave nav nav-tabs" id="custom-content-above-tab" role="tablist">
                    <?php
                    if ($tasks['tsk_odr_list']) {
                    ?>
                        <li class="nav-item">
                            <a class="nav-link active px-5" id="odr-list-tab" data-toggle="pill" href="#list" role="tab" aria-controls="custom-content-above-list" aria-selected="true">ORDERS</a>
                        </li>
                    <?php
                    }

                    if ($tasks['tsk_odr_stk_list']) {
                    ?>
                        <li class="nav-item">
                            <a class="nav-link px-5" id="odr-add-tab" data-toggle="pill" href="#add" role="tab" aria-controls="custom-content-above-add" aria-selected="false">STOCK</a>
                        </li>
                    <?php
                    }

                    if ($tasks['tsk_odr_conf']) {
                    ?>

                        <li class="nav-item">
                            <a class="nav-link px-5" id="odr-config-tab" data-toggle="pill" href="#config" role="tab" aria-controls="custom-content-above-config" aria-selected="false">Configs</a>
                        </li>
                    <?php
                    }
                    ?>
                </ul>

                <div class="sr-nav-content tab-content" id="custom-content-above-tabContent">
                    <?php
                    if ($tasks['tsk_odr_list']) {
                    ?>
                        <div class="tab-pane fade show active" id="list" role="tabpanel" aria-labelledby="custom-content-above-list-tab">
                            <?php $this->load->view('orders/order_search') ?>
                            <?php $this->load->view('orders/order_list'); ?>
                        </div>
                    <?php
                    }

                    if ($tasks['tsk_odr_stk_list']) {
                    ?>
                        <div class="tab-pane fade" id="add" role="tabpanel" aria-labelledby="custom-content-above-add-tab">
                            <?php $this->load->view('orders/stock_search') ?>
                            <?php $this->load->view('orders/stock_list'); ?>
                        </div>
                    <?php
                    }

                    if ($tasks['tsk_odr_conf']) {
                    ?>
                        <div class="tab-pane fade" id="config" role="tabpanel" aria-labelledby="custom-content-above-config-tab">
                            <?php $this->load->view('orders/configs') ?>
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

<?php $this->load->view('orders/add');  ?>
<?php $this->load->view('orders/show_details'); ?>
<?php $this->load->view('orders/show_bill'); ?>
<?php $this->load->view('orders/product_window');  ?>
<?php $this->load->view('orders/batch_prices');  ?>

<?php $this->load->view('footer') ?>



<!-- page script -->
<script type="text/javascript">
    <?php
    // var PENDING     = 1;
    // var PICKED      = 2;
    // var BILLED      = 3;
    // var PACKED      = 4;
    // var ESTORE      = 5;
    // var DELIVERED   = 6
    // var PAID        = 7;
    foreach ($odr_status as $k => $v)
        echo "var $v = $k;\n";

    foreach ($tasks as $k => $tsk)
        echo "var $k = " . ($tsk ? 'true' : 'false') . ";\n";
    ?>
</script>



<!-- To view tooltip in "order to bill" converting view -->
<script type='text/javascript' src="dependencies/js/orders/tooltip/srtip.js"></script>

<!-- Order Scripts -->
<script type='text/javascript' src="dependencies/js/orders/index.js"></script>
<script type='text/javascript' src="dependencies/js/orders/add_bill.js"></script>
<script type='text/javascript' src="dependencies/js/orders/order_list.js"></script>
<script type='text/javascript' src="dependencies/js/orders/stock.js"></script>
<script type='text/javascript' src="dependencies/js/orders/product_window.js"></script>
<script type='text/javascript' src="dependencies/js/orders/product_batch_list.js"></script>
<script type='text/javascript' src="dependencies/js/orders/price_groups.js"></script>

<!-- Other important scripts -->
<script type='text/javascript' src="dependencies/js/php_function_equivalent_for_js.js"></script>
<script type='text/javascript' src="dependencies/js/bcmath-min.js"></script>

</div> <!--  <div class="page-non-printable">  @ header.php-->




<!-- Table used to print reports will be loaded here -->
<?php $this->load->view('print_reports') ?>



<?php $this->load->view('html_close') ?>