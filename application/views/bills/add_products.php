<table class="text-nowrap sr-input-movement blp-tbl" data-onInit="initProductTable" data-afrNew="onNewProductRow" data-initNewRow="false" data-afrRem="onProductRowRemoved">
    <thead>
        <tr>
            <th>#</th>
            <th>Product</th>
            <th>Batch</th>
            <th>Godown</th>
            <th>Qty</th>
            <th>Unit</th>
            <th>Rate <small> (With Tax)</small></th>
            <th>Amount <small> (Without Tax)</small></th>
            <th class="tax-col cgst-col" style="width: 150px;">CGST</th>
            <th class="tax-col sgst-col" style="width: 150px;">SGST</th>
            <th class="tax-col igst-col" style="width: 150px; display:none;">IGST</th>
            <th style="width: 150px;">Gross Amount</th>
        </tr>
    </thead>
    <tbody>

        <tr class="sr-movement-row">
            <td>
                <i class="fal fa-times-circle rem cursor-pointer" title="Delete [Alt + x]"></i>
                <span class="sr-movement-slno" style="padding:0 2px;"></span>
            </td>

            <td>
                <div class="">
                    <input type="text" class="prd-pop next-input enter-lock no-movement form-control form-control-sm">
                    <input type="hidden" name="blp_fk_products[]" class="blp_fk_products">
                    <input type="hidden" name="prd_name[]" class="prd_name">
                </div>
            </td>
            <td>
                <div class="">
                    <select name="blp_fk_product_batches[]" class="blp_fk_product_batches next-input enter-lock form-control form-control-sm">
                        <option value="">No Batches</option>
                    </select>
                </div>
            </td>
            <td>
                <div class="">
                    <select name="blp_fk_godowns[]" class="blp_fk_godowns next-input enter-lock form-control form-control-sm">
                        <option value="">No Godowns</option>
                    </select>
                </div>
            </td>
            <td>
                <div class="">
                    <input type="text" name="blp_qty[]" class="blp_qty numberOnly next-input enter-lock form-control form-control-sm">
                </div>
            </td>
            <td>
                <div class="">
                    <select name="blp_fk_unit_groups[]" class="blp_fk_unit_groups next-input enter-lock form-control form-control-sm">
                        <option value="">No Units</option>
                    </select>
                </div>
            </td>
            <td>
                <div class="">
                    <input type="hidden" name="blp_rate[]" class="blp_rate rate_field form-control form-control-sm">
                    <input type="text" name="blp_trate[]" class="blp_trate rate_field numberOnly next-input last-input enter-lock form-control form-control-sm">
                </div>
            </td>
            <td>
                <div class="">
                    <input type="text" name="blp_amount[]" disabled class="blp_amount numberOnly next-input enter-lock text-right form-control form-control-sm">
                </div>
            </td>

            <!-- .opened is used in calculateBill() function to identify the column is visible -->
            <td class="tax-col cgst-col opened">
                <div class="input-group input-group-sm">
                    <div class="input-group-prepend">
                        <span class="input-group-text span-tax-pcntg"></span>
                    </div>
                    <input type="hidden" name="blp_cgst_p[]" class="blp_cgst_p">
                    <input type="text" name="blp_cgst[]" disabled class="blp_cgst numberOnly next-input enter-lock text-right form-control form-control-sm">
                </div>
            </td>

            <!-- .opened is used in calculateBill() function to identify the column is visible -->
            <td class="tax-col sgst-col opened">
                <div class="input-group input-group-sm">
                    <div class="input-group-prepend">
                        <span class="input-group-text span-tax-pcntg"></span>
                    </div>
                    <input type="hidden" name="blp_sgst_p[]" class="blp_sgst_p">
                    <input type="text" name="blp_sgst[]" disabled class="blp_sgst numberOnly next-input enter-lock text-right form-control form-control-sm">
                </div>
            </td>

            <!-- .closed is used in calculateBill() function to identify the column is invisible -->
            <td class="tax-col igst-col closed" style="display:none;">
                <div class="input-group input-group-sm">
                    <div class="input-group-prepend">
                        <span class="input-group-text span-tax-pcntg"></span>
                    </div>
                    <input type="hidden" name="blp_igst_p[]" class="blp_igst_p">
                    <input type="text" name="blp_igst[]" disabled class="blp_igst numberOnly next-input enter-lock text-right form-control form-control-sm">
                </div>
            </td>


            <td>
                <div class="">
                    <input type="text" name="blp_gross_amt[]" disabled class="blp_gross_amt numberOnly next-input enter-lock text-right form-control form-control-sm">
                </div>
            </td>
        </tr>

    </tbody>

    <tfoot>
        <tr class="total-row">
            <td></td>
            <th>TOTAL</th>
            <td colspan="5"></td>
            <td><input type="text" name="bls_amt_total" disabled class="bls_amt_total numberOnly text-right form-control form-control-sm total-fields"></td>
            <td class="tax-col cgst-col">
                <input type="text" name="bls_cgst_total" disabled class="bls_cgst_total numberOnly text-right form-control form-control-sm total-fields">
            </td>
            <td class="tax-col sgst-col">
                <input type="text" name="bls_sgst_total" disabled class="bls_sgst_total numberOnly text-right form-control form-control-sm total-fields">
            </td>
            <td class="tax-col igst-col" style="display:none;">
                <input type="text" name="bls_igst_total" disabled class="bls_igst_total numberOnly text-right form-control form-control-sm total-fields">
            </td>
            <td><input type="text" name="bls_gross_total" disabled class="bls_gross_total numberOnly text-right form-control form-control-sm total-fields"></td>
        </tr>
        <tr>
            <!-- Offset for tax column -->
            <td class="tax-col"></td>

            <td colspan="8" rowspan="6" class="notice-col">
                <div class="notice-board"></div>
            </td>
            <th>KFC</th>
            <td><input type="text" name="bls_cess_total" disabled class="bls_cess_total no-movement numberOnly  text-right form-control form-control-sm"></td>
        </tr>


        <tr>
            <!-- Offset for tax column -->
            <td class="tax-col"></td>

            <th>DISCOUNT <small>Alt + D</small></th>
            <td><input type="text" name="bls_gross_disc" class="bls_gross_disc no-movement numberOnly  text-right form-control form-control-sm"></td>
        </tr>



        <tr>
            <!-- Offset for tax column -->
            <td class="tax-col"></td>

            <th>ROUND OFF</th>
            <td><input type="text" name="bls_round" class="bls_round no-movement numberOnly text-right form-control form-control-sm"></td>
        </tr>
        <tr>
            <!-- Offset for tax column -->
            <td class="tax-col"></td>

            <th>NET AMOUNT</th>
            <td><input type="text" name="bls_net_amount" disabled class="bls_net_amount numberOnly text-right form-control form-control-sm"></td>
        </tr>
        <tr>
            <!-- Offset for tax column -->
            <td class="tax-col"></td>

            <th>PAID</th>
            <td><input type="text" name="bls_paid" class="bls_paid no-movement numberOnly text-right form-control form-control-sm"></td>
        </tr>
        <tr>
            <!-- Offset for tax column -->
            <td class="tax-col"></td>

            <th>BALANCE</th>
            <td><input type="text" name="bls_balance" disabled class="bls_balance numberOnly text-right form-control form-control-sm"></td>
        </tr>
    </tfoot>
</table>