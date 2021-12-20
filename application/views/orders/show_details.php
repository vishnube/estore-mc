<?php
if (!(isset($after_ajax) && $after_ajax)) {
?>

    <style type="text/css">

    </style>

    <div class="modal fade" tabindex="-1" role="dialog" id="bill_details_modal">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content"></div>
        </div>
    </div>
<?php
} else {
?>

    <div class="row">
        <div class="col-12">
            <div class="card card-widget widget-user" style="box-shadow: none; margin-bottom: 0;background-color: #682937; color:#fff;">
                <!-- Add the bg color to the header using any of the bg-* classes -->
                <div class="modal-header">

                    <h3 class="modal-title">Bill No: <?= $bill_no['blb_prefix'] . $bill_no['bln_name'] . $bill_no['blb_sufix'] ?></h3>


                    <button type="button" class="close" data-dismiss="modal" style="color:#fff;" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="card-body" style="background-color: #fff; color:#000;padding-top: 5px;">
                    <div style="font-size: 15px;">Date : <?= $bls_date ?></div>
                    <div class="clearer">
                        <div class="float-left" style="font-size: 15px;">From : <?= $from  ?></div>
                        <div class="float-right" style="font-size: 15px;">To : <?= $to ?></div>
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
                                                        } else     if ($bls_tax_state == 2) {
                                                            echo '<td>(' . $row['blp_igst_p'] . ' %) ' . (float)$row['blp_igst'] . ' </td>';
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
                                        <tr>
                                            <td colspan="<?= $foot_span ?>" rowspan="5"></td>
                                            <th>Gross Discount </th>
                                            <td><?= $bls_gross_disc ?> </td>
                                        </tr>
                                        <tr>
                                            <th>Round Off </th>
                                            <td><?= $bls_round ?> </td>
                                        </tr>
                                        <tr>
                                            <th>Net Amount </th>
                                            <td><?= $bls_net_amount ?> </td>
                                        </tr>
                                        <tr>
                                            <th>Paid </th>
                                            <td><?= $bls_paid ?> </td>
                                        </tr>
                                        <tr>
                                            <th>Balance </th>
                                            <td><?= $bls_balance ?> </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>




                </div>
            </div>

        </div>
    </div>

<?php
}
?>