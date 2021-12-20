<!-- 
    Class '.dv-data-table.active .print-table' is for export (print/excel) 
    Where '.dv-data-table.active' @ list.php
    -->
<style type="text/css">
    #tbl_bls_grid_print {
        border: 1px solid #000;
        border-collapse: collapse;
        color: #000;
    }

    #tbl_bls_grid_print th,
    #tbl_bls_grid_print td {
        border: 1px solid #000;
        vertical-align: text-top;
        padding: 2px;
    }
</style>
<!-- data-file-format attribute can have two values, EXCEL/CSV. It is used when export to excel. -->
<table class="print-table print-table-excel" id="tbl_bls_grid_print" data-file-format="CSV" data-exportTitle_1="<?= $client['clnt_print_name'] ?>" data-exportTitle_2="BILLS LIST" data-exportTitle_3="List of bills">

    <thead>
        <tr>
            <?php $col_index = 0; ?>
            <th style="width: 10px" data-col="<?= ++$col_index ?>">#</th>
            <th data-col="<?= ++$col_index ?>">Bill No:</th>
            <th data-col="<?= ++$col_index ?>">Date</th>
            <th data-col="<?= ++$col_index ?>">From</th>
            <th data-col="<?= ++$col_index ?>">To</th>

            <th data-col="<?= ++$col_index ?>">Godown </th>
            <th data-col="<?= ++$col_index ?>">Product </th>
            <th data-col="<?= ++$col_index ?>">Batch </th>
            <th data-col="<?= ++$col_index ?>">Quantity</th>
            <th data-col="<?= ++$col_index ?>">Rate</th>
            <th data-col="<?= ++$col_index ?>">Amount </th>
            <th data-col="<?= ++$col_index ?>">CGST</th>
            <th data-col="<?= ++$col_index ?>">SGST</th>
            <th data-col="<?= ++$col_index ?>">IGST</th>
            <th data-col="<?= ++$col_index ?>">Gross</th>

            <th data-col="<?= ++$col_index ?>">KFC</th>
            <th data-col="<?= ++$col_index ?>">Gross Discount</th>
            <th data-col="<?= ++$col_index ?>">Round Off</th>

            <th data-col="<?= ++$col_index ?>">Net Amount</th>
            <th data-col="<?= ++$col_index ?>">Paid</th>
            <th data-col="<?= ++$col_index ?>">Balance</th>
        </tr>
    </thead>
    <tbody></tbody>

    <tfoot></tfoot>
</table>