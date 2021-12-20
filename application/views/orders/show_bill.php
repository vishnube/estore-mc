<?php
if (!(isset($after_ajax) && $after_ajax)) {
?>
    <style type="text/css">

    </style>

    <div class="modal fade" tabindex="-1" role="dialog" id="show_bill_modal">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content"></div>
        </div>
    </div>
<?php
} else {
?>

    <div class="row">
        <div class="col-12">
            <div class="card card-widget widget-user  bg-srlight">
                <!-- Add the bg color to the header using any of the bg-* classes -->
                <div class="modal-header">

                    <h3 class="modal-title text-center text-info">CENTRAL STORE: <?= $cstr_name ?></h3>


                    <div class="btn-group">
                        <!-- 
                        value of href attribute of PDF download link will be set by jquery as     
		                $('#show_bill_modal #PDFdownloadLink').attr('href', site_url('bills/print_bill/' + bls_id));
-->
                        <a id="PDFdownloadLink" href="" download class="btn btn-info">
                            <i class="fas fa-file-pdf"></i> &nbsp;PDF
                        </a>

                        <!-- data-bls_id will be added by jquery -->
                        <button type="button" class="btn btn-primary print-bill" data-bls_id=""><i class="fas fa-print"></i> &nbsp;PRINT</button>

                        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-times"></i> &nbsp;CLOSE </button>
                    </div>


                </div>
                <div class="card-body" style="background-color: #fff; color:#000;padding-top: 5px;">
                    <div class="row">
                        <div class="col-12 col-sm-6 col-lg-4">
                            <div class="m-2 py-2 px-2 px-md-3">
                                <div type="button" class="btn-block btn btn-info btn-flat">ODR NO: <span class=""> <?= $bill_no['blb_prefix'] . $bill_no['bln_name'] . $bill_no['blb_sufix'] ?></span></div>
                            </div>
                            <div class="m-2 py-2 px-2 px-md-3">
                                <div type="button" class="btn-block btn bg-fuchsia btn-flat">DATE: <span class=""><?= $bls_date ?></span></div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6 col-lg-4">
                            <div class="m-2 py-2 px-2 px-md-3">
                                <div type="button" class="btn-block btn btn-success btn-flat">ESTORE: <span class=""><?= $estr_name ?></span></div>
                            </div>
                            <div class="m-2 py-2 px-2 px-md-3">
                                <div type="button" class="btn-block btn btn-danger btn-flat">STATUS: <span class=""><?= $ost_status_txt ?></span></div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6 col-lg-4">
                            <div class="m-2 py-2 px-2 px-md-3">
                                <div type="button" class="btn-block btn btn-primary btn-flat">AREA: <span class=""><?= $area ?></span></div>
                            </div>
                            <div class="m-2 py-2 px-2 px-md-3">
                                <div type="button" class="btn-block btn btn-warning btn-flat">FAMILY: <span class=""><?= $family ?></span></div>
                            </div>
                        </div>
                    </div>




                </div>


                <div class="card-footer" style="background-color: #fff; color:#000;padding-top: 5px;">



                    <div class="row">

                        <div class="col-12">

                            <div class="card-body table-responsive p-0" id="tbl_emply_container">
                                <table class="table table-head-fixed text-nowrap" id="">
                                    <thead>
                                        <tr>
                                            <th style="width: 10px">#</th>
                                            <th>GODOWN</th>
                                            <th>PRODUCT</th>
                                            <th>QUANTITY</th>
                                            <th>RATE</th>
                                            <th>AMOUNT</th>
                                            <?php
                                            if ($bls_taxable == 1) {
                                                if ($bls_tax_state == 1) {
                                                    echo '<th>CGST</th>';
                                                    echo '<th>SGST</th>';
                                                } else     if ($bls_tax_state == 2) {
                                                    echo '<th>IGST</th>';
                                                }
                                                echo '<th>Gross Amount</th>';
                                            }
                                            ?>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <?php
                                        $slNo = 1;
                                        $total_tax = 0;
                                        if ($blp_data) {
                                            foreach ($blp_data as $row) {
                                        ?>

                                                <tr>
                                                    <td><?= $slNo++ ?></td>
                                                    <td><?= $row['gdn_name'] ?> </td>
                                                    <td><?= $row['prd_name'] ?></td>
                                                    <td><?= (float) $row['blp_qty'] . ' ' . $row['unt_name'] ?> </td>
                                                    <td><?= (float)$row['blp_trate'] ?></td>
                                                    <td><?= (float)$row['blp_amount'] ?> </td>

                                                    <?php
                                                    if ($bls_taxable == 1) {
                                                        if ($bls_tax_state == 1) {
                                                            echo '<td>(' . $row['blp_cgst_p'] . ' %) ' . (float)$row['blp_cgst'] . ' </td>';
                                                            echo '<td>(' . $row['blp_sgst_p'] . ' %) ' . (float)$row['blp_sgst'] . ' </td>';
                                                            $total_tax = bcadd($total_tax, $row['blp_cgst'], 5);
                                                            $total_tax = bcadd($total_tax, $row['blp_sgst'], 5);
                                                        } else     if ($bls_tax_state == 2) {
                                                            echo '<td>(' . $row['blp_igst_p'] . ' %) ' . (float)$row['blp_igst'] . ' </td>';
                                                            $total_tax = bcadd($total_tax, $row['blp_igst'], 5);
                                                        }
                                                        echo '<td>' . (float)$row['blp_gross_amt'] . '</td>';
                                                    }
                                                    ?>
                                                </tr>
                                            <?php }
                                        } else {
                                            ?>
                                            <tr>
                                                <td colspan="8">
                                                    <h3 class="text-danger text-center"><i class="fas fa-exclamation-triangle fa-lg"></i><span class="pl-3">NO PRODUCTS FOUND</span></h3>
                                                </td>
                                            </tr>
                                        <?php
                                        }
                                        ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="5">
                                                <div class="text-left>">TOTAL</div>
                                            </th>
                                            <th><?= (float) $bls_amt_total ?> </th>
                                            <?php
                                            $foot_span = 4;
                                            if ($bls_taxable == 1) {
                                                if ($bls_tax_state == 1) {
                                                    echo '<th>' . (float)$bls_cgst_total . ' </th>';
                                                    echo '<th>' . (float)$bls_sgst_total . ' </th>';
                                                    $foot_span = 7;
                                                } else     if ($bls_tax_state == 2) {
                                                    echo '<th>' . (float)$bls_igst_total . ' </th>';
                                                    $foot_span = 6;
                                                }
                                                echo '<th>' . (float)$bls_gross_total . '</th>';
                                            }
                                            ?>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col col-12 col-md-6">
                            <ul class="list-group">

                                <li class="list-group-item d-flex justify-content-between align-items-center">TOTAL AMOUNT: <span class=""><?= (float) $bls_amt_total ?></span></li>

                                <li class="list-group-item d-flex justify-content-between align-items-center">TOTAL TAX : <span class=""><?= (float)round($total_tax, 2) ?></span></li>

                                <li class="list-group-item d-flex justify-content-between align-items-center">GROSS AMOUNT: <span class=""><?= (float)$bls_gross_total ?></span></li>

                                <li class="list-group-item d-flex justify-content-between align-items-center">DISCOUNT: <span class=""><?= $bls_gross_disc ?></span></li>
                            </ul>
                        </div>
                        <div class="col col-12 col-md-6">
                            <ul class="list-group">

                                <li class="list-group-item d-flex justify-content-between align-items-center">ROUND OFF: <span class=""><?= $bls_round ?></span></li>

                                <li class="list-group-item d-flex justify-content-between align-items-center">NET AMOUNT: <span class=""><?= $bls_net_amount ?></span></li>

                                <li class="list-group-item d-flex justify-content-between align-items-center">PAID: <span class=""><?= $bls_paid ?></span></li>

                                <li class="list-group-item d-flex justify-content-between align-items-center">BALANCE: <span class=""><?= $bls_balance ?></span></li>
                            </ul>
                        </div>

                    </div>

                </div>
            </div>

        </div>
    </div>

<?php
}
?>