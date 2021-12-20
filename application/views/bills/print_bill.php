<!DOCTYPE html>
<html lang="en">

<head>
    <base href="<?= base_url() ?>" />
    <!-- <link rel="stylesheet" href="dependencies/css/bills/print_bill.css" type="text/css" media="screen"> -->
    <?php $this->load->view('bills/print_bill_css'); ?>
</head>

<body>
    <div class="bill-container">
        <div class="company">ETERNAL STORE</div>
        <div class="upper-part" id="upper-part">
            <table id="uprtbl" cellpadding=0 cellspacing=0>
                <tbody>
                    <tr>
                        <td>Invoice No: <?= $bill_no['blb_prefix'] . $bill_no['bln_name'] . $bill_no['blb_sufix'] ?></td>
                        <td>Date:<?= $bls_date ?></td>
                    </tr>
                    <tr>
                        <td>Estore:<?= $estr_name ?></td>
                        <td>Family:<?= $family ?><br><?= nl2br($fmly_address) ?></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="middle-part">
            <table class="prd-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Rate</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>

                    <?php
                    $slNo = 1;
                    if ($blp_data) {
                        foreach ($blp_data as $row) {
                    ?>

                            <tr>
                                <td class="tc"><?= $slNo++ ?></td>
                                <td><?= $row['prd_name'] ?></td>
                                <td class="tc"><?= (float) $row['blp_qty'] . ' ' . $row['unt_name'] ?> </td>
                                <td class="tc"><?= (float)$row['blp_trate'] ?></td>
                                <td class="tc"><?= (float)$row['blp_gross_amt'] ?> </td>
                            </tr>
                    <?php }
                    } ?>
                </tbody>

            </table>
        </div>

        <div class="bottom-part">
            <table id="ftrtbl">
                <tbody>
                    <tr>
                        <td>Taxable Amount</td>
                        <td><?= number_format($bls_amt_total, 2, '.', '') ?></td>
                    </tr>
                    <?php
                    if ($bls_taxable == 1) {
                        if ($bls_tax_state == 1) {
                            echo '<tr><td>CGST</td><td>' . (float)$bls_cgst_total . '</td> </tr>';
                            echo '<tr><td>SGST</td><td>' . (float)$bls_sgst_total . '</td> </tr>';
                        } else     if ($bls_tax_state == 2) {
                            echo '<tr><td>IGST</td><td>' . (float)$bls_igst_total . '</td> </tr>';
                        }
                    }
                    ?>
                    <tr>
                        <td>Amount (TAX)</td>
                        <td><?= number_format($bls_gross_total, 2, '.', '') ?></td>
                    </tr>
                    <tr>
                        <td>Gross Discount</td>
                        <td><?= number_format($bls_gross_disc, 2, '.', '') ?></td>
                    </tr>
                    <tr>
                        <td>Round Off</td>
                        <td><?= number_format($bls_round, 2, '.', '') ?></td>
                    </tr>
                    <tr>
                        <td>Net Amount</td>
                        <td><?= number_format($bls_net_amount, 2, '.', '') ?></td>
                    </tr>
                    <tr>
                        <td>Paid</td>
                        <td><?= number_format($bls_paid, 2, '.', '') ?></td>
                    </tr>
                    <tr>
                        <td>Balance</td>
                        <td><?= number_format($bls_balance, 2, '.', '') ?></td>
                    </tr>
                </tbody>
            </table>

        </div>

        <script type="text/javascript">
            // $(document).ready equivalent without jQuery
            document.addEventListener("DOMContentLoaded", function(event) {

            });
        </script>


</body>

</html>