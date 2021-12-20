<div class="border">
    <div class="row m-0 bg-srlight">
        <div class="col-6">
            <div class="form-group m-0 p-2">
                <input type="text" placeholder="BARCODE" class="odr-barcode form-control form-control-sm">
            </div>
        </div>
        <div class="col-6">
            <h2 class="text-center mb-0 mt-1 text-danger" style="font-family: 'Arial Black';">ORDER</h2>
        </div>
    </div>
    <div class="row m-0 bg-dark">
        <div class="col-12">
            <div class="notice-board p-2 p-md-4" style="display: none;"></div>
        </div>
    </div>
    <div class="">
        <table class="table table-head-fixed text-nowrap" id="tbl-odr-items" style="width:100%;">
            <thead>
                <tr>
                    <th style="width: 10px">
                        <div class="form-group clearfix m-0">
                            <div class="icheck-warning d-inline">
                                <input type="checkbox" class="all-checker" id="odr-all-items-check">
                                <label for="odr-all-items-check"></label>
                            </div>
                        </div>
                    </th>
                    <th>PRODUCT</th>
                    <th>QUANTITY</th>
                </tr>
            </thead>
            <tbody>

                <!-- 
                    the class '.item-moved' will be added to <tr> after it moved to Bill section 
                    And in Css its display property is 'hidden'.
                    
                    The class '.item-updated' means that the qty of the product in this row has been updated in Billing section.
                    That is, there is a lesser qty entered in Billing. 
                    So this row means that we need to add a new Order with the remaining qty. 
                    This class will be applied to a row when the user update the qty by a lesser value than real value.

                    The class 'item-deleted' will be added to a row when a product delete after it moved to Billing. 
                    -->
                <?php
                $slNo = 1;
                if ($blp_data) {
                    foreach ($blp_data as $row) {
                ?>
                        <tr id="order-row-<?= $row['blp_id'] ?>">
                            <td>
                                <?php $slNo ?>
                                <div class="form-group clearfix m-0">
                                    <div class="icheck-warning d-inline">
                                        <input type="checkbox" class="odr-item-check" id="order-item-<?= $slNo ?>">
                                        <label for="order-item-<?= $slNo ?>"></label>
                                    </div>
                                    <span class="sno"><?= $slNo++ ?></span>
                                </div>

                                <div class="blp-data" data-gdn_name="<?= $row['gdn_name'] ?>" data-prd_name="<?= $row['prd_name'] ?>" data-pdbch_name="<?= $row['pdbch_name'] ?>" data-pdbch_exp="<?= $row['pdbch_exp'] ?>" data-pdbch_mrp="<?= $row['pdbch_mrp'] ?>" data-unt_name="<?= $row['unt_name'] ?>" data-blp_fk_godowns="<?= $row['gdn_id'] ?>" data-blp_fk_products="<?= $row['prd_id'] ?>" data-blp_fk_product_batches="<?= $row['pdbch_id'] ?>" data-blp_qty="<?= $row['blp_qty'] ?>" data-blp_fk_unit_groups="<?= $row['blp_fk_unit_groups'] ?>" data-blp_rate="<?= $row['blp_rate'] ?>" data-blp_trate="<?= $row['blp_trate'] ?>" data-blp_amount="<?= $row['blp_amount'] ?>" data-blp_cgst_p="<?= $row['blp_cgst_p'] ?>" data-blp_cgst="<?= $row['blp_cgst'] ?>" data-blp_sgst_p="<?= $row['blp_sgst_p'] ?>" data-blp_sgst="<?= $row['blp_sgst'] ?>" data-blp_igst_p="<?= $row['blp_igst_p'] ?>" data-blp_igst="<?= $row['blp_igst'] ?>" data-blp_gross_amt="<?= $row['blp_gross_amt'] ?>"></div>


                            </td>
                            <td style="white-space: normal; word-wrap: break-word">
                                <div class="text-left">
                                    <?= $row['prd_name'] ?>
                                </div>
                                <div>
                                    <span class="new-product scrap" title="A new entry" style="display:none; color: goldenrod"><i class="fas fa-stars"></i>&nbsp;&nbsp;NEW</span>
                                    <span class="text-primary scrap" title="Batch">
                                        <?= $row['pdbch_name'] ?>
                                    </span>
                                </div>
                            </td>
                            <td>
                                <input type="hidden" class="blp_fk_godowns" name="blp_fk_godowns[]" value="<?= $row['blp_fk_godowns'] ?>">
                                <input type="hidden" class="prd_id" name="blp_fk_products[]" value="<?= $row['blp_fk_products'] ?>">
                                <input type="hidden" class="blp_fk_product_batches" name="blp_fk_product_batches[]" value="<?= $row['blp_fk_product_batches'] ?>">
                                <input type="hidden" class="blp_mrp" name="blp_mrp[]" value="<?= $row['pdbch_mrp'] ?>">
                                <input type="hidden" class="blp_cgst_p" name="blp_cgst_p[]" value="<?= $row['blp_cgst_p'] ?>">
                                <input type="hidden" class="blp_cgst" name="blp_cgst[]" value="<?= $row['blp_cgst'] ?>">
                                <input type="hidden" class="blp_sgst_p" name="blp_sgst_p[]" value="<?= $row['blp_sgst_p'] ?>">
                                <input type="hidden" class="blp_sgst" name="blp_sgst[]" value="<?= $row['blp_sgst'] ?>">
                                <input type="hidden" class="blp_igst_p" name="blp_igst_p[]" value="<?= $row['blp_igst_p'] ?>">
                                <input type="hidden" class="blp_igst" name="blp_igst[]" value="<?= $row['blp_igst'] ?>">

                                <!-- '.new_qty' will be updated when user enter a lesser qty in Billing than it in Order -->
                                <input type="text" class="new_qty" name="new_qty[]" value="<?= $row['blp_qty'] ?>" style="display: none;width:50px;text-align: center;height: 21px;">

                                <input type="hidden" class="blp_qty" name="blp_qty[]" value="<?= $row['blp_qty'] ?>">
                                <input type="hidden" class="ugp_id" name="blp_fk_unit_groups[]" value="<?= $row['blp_fk_unit_groups'] ?>">
                                <input type="hidden" class="blp_trate" name="blp_trate[]" value="<?= $row['blp_trate'] ?>">
                                <input type="hidden" class="blp_rate" name="blp_rate[]" value="<?= $row['blp_rate'] ?>">
                                <input type="hidden" class="blp_amount" name="blp_amount[]" value="<?= $row['blp_amount'] ?>">
                                <input type="hidden" class="blp_gross_amt" name="blp_gross_amt[]" value="<?= $row['blp_gross_amt'] ?>">

                                <!-- 
                                    Tooltip: srtip
                                    Source: https://www.w3schools.com/css/css_tooltip.asp   
                                    To workout the tooltip you need srtip.css and srtip.js @ dependencies/js/orders/tooltip
                                    -->
                                <?php
                                echo '<span class="qtyhtml">' . (float) $row['blp_qty'] . '</span> ' . $row['unt_name'] . '&nbsp;';
                                echo  '<div class="srtip-container" style="display: inline-block;">
                                                <i class="ml-2 fal fa-backpack text-primary cursor-pointer" title="Show details"></i>
                                                <div class="srtip">
                                                    <div class="srtiptext srtip-arrow-right">
                                                        <ul class="list-group">
                                                            <li class="list-group-item list-title bg-info">' . $row['prd_name'] . '</li>
                                                            <li class="list-group-item d-flex justify-content-between align-items-center">Quantity: <span><span class="pop-odr-qty">' . (float) $row['blp_qty'] . '</span> ' . $row['unt_name'] . '</span></li>
                                                            <li class="list-group-item d-flex justify-content-between align-items-center">Rate (With Tax): <span class="pop-odr-trate">' . (float)$row['blp_trate'] . '</span></li>
                                                            <li class="list-group-item d-flex justify-content-between align-items-center">Amount (Without Tax): &nbsp;&nbsp;<span class="pop-odr-amt">' . (float)$row['blp_amount'] . '</span></li>';
                                if ($bls_taxable == 1) {
                                    if ($bls_tax_state == 1) {
                                        echo  '<li class="list-group-item d-flex justify-content-between align-items-center">CGST: (' . $row['blp_cgst_p'] . ' %) <span class="pop-odr-cgst">' . (float)$row['blp_cgst'] . '</span></li>';
                                        echo  '<li class="list-group-item d-flex justify-content-between align-items-center">SGST: (' . $row['blp_sgst_p'] . ' %) <span class="pop-odr-sgst">' . (float)$row['blp_sgst'] . '</span></li>';
                                    } else if ($bls_tax_state == 2) {
                                        echo  '<li class="list-group-item d-flex justify-content-between align-items-center">IGST: (' . $row['blp_igst_p'] . ' %) <span class="pop-odr-igst">' . (float)$row['blp_igst'] . '</span></li>';
                                    }
                                }


                                echo  '         <li class="list-group-item d-flex justify-content-between align-items-center">GROSS AMOUNT: <span class="pop-odr-gross">' . (float)$row['blp_gross_amt'] . '</span></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>';

                                ?>


                            </td>
                        </tr>
                <?php }
                } ?>
            </tbody>
        </table>
        <div class="bg-srlight border-top border-bottom">
            <ul class="list-group">
                <li class="bg-srlight border-0 rounded-0 list-group-item d-flex justify-content-between align-items-center">
                    <div>Total <span class="tot-prd-sel sr-badge bg-pink">0</span> Product Selected</div>
                    <div class="btn btn-success move-to-bill">MOVE TO BILL</div>
                </li>
            </ul>
        </div>

        <div class="p-2 px-md-2 py-md-4 bg-srlight">
            <div class="">
                <ul class="list-group">
                    <?php
                    $amt_style = $tax_style = $sg_style = $ig_style = $grs_style =  $disc_style = $round_style = $net_style = $pd_style = $bs_style = 'display:none !important;';
                    $tax = 0;
                    if ((float)$bls_amt_total)
                        $amt_style = '';
                    if ($bls_taxable == 1) {
                        $tax_style = '';
                        if ($bls_tax_state == 1) {
                            $tax = bcadd($bls_cgst_total, $bls_sgst_total, 3);
                        } else if ($bls_tax_state == 2) {
                            $tax = $bls_igst_total;
                        }
                    }
                    if ((float)$bls_gross_total)
                        $grs_style = '';
                    if ((float)$bls_gross_disc)
                        $disc_style = '';
                    if ((float)$bls_round)
                        $round_style = '';
                    if ((float)$bls_net_amount)
                        $net_style = '';
                    if ((float)$bls_paid)
                        $pd_style = '';
                    if ((float)$bls_balance)
                        $bs_style = '';
                    ?>

                    <li style="<?= $amt_style ?>" class="list-group-item d-flex justify-content-between align-items-center">TOTAL AMOUNT: <span class="tot-val text-success tot-amt"><?= (float)$bls_amt_total ?></span></li>

                    <li style="<?= $tax_style ?>" class="list-group-item d-flex justify-content-between align-items-center">TOTAL TAX : <span class="tot-val text-warning tot-tax"><?= (float)$tax ?></span></li>

                    <li style="<?= $grs_style ?>" class="list-group-item d-flex justify-content-between align-items-center">GROSS AMOUNT: <span class="tot-val text-success tot-grs"><?= (float)$bls_gross_total ?></span></li>

                    <li style="<?= $disc_style ?>" class="list-group-item d-flex justify-content-between align-items-center">DISCOUNT: <span class="tot-val tot-disc"><?= (float)$bls_gross_disc ?></span></li>

                    <li style="<?= $round_style ?>" class="list-group-item d-flex justify-content-between align-items-center">ROUND OFF: <span class="tot-val tot-round"><?= (float)$bls_round ?></span></li>

                    <li style="<?= $net_style ?>" class="list-group-item d-flex justify-content-between align-items-center">NET AMOUNT: <span class="tot-val text-success tot-net font-weight-bold"><?= (float)$bls_net_amount ?></span></li>

                    <li style="<?= $pd_style ?>" class="list-group-item d-flex justify-content-between align-items-center">PAID: <span class="tot-val tot-pd"><?= (float)$bls_paid ?></span></li>

                    <li style="<?= $bs_style ?>" class="list-group-item d-flex justify-content-between align-items-center">BALANCE: <span class="tot-val text-danger tot-bs"><?= (float)$bls_balance ?></span></li>
                </ul>
            </div>
        </div>
    </div>
</div>