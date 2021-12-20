<div class="dv-print-rep" style="display: none;background-color: #fff;">
    <!-- 
        This file contains a table which is a copy of the #tbl_bls_grid (below) used to export (Excel,Print) data of #tbl_bls_grid.
        So any changes making on #tbl_bls_grid should be apply on to this table also.                 
    -->
    <?php $this->load->view('bills/list_display_grid_export') ?>
</div>

<!-- 
    '.sr-tbl-cont' is also used to scroll to table when click on .sr-go-to-tbl
    data-print-table attribute represents the copy of the table used to export (Print/Excel).
    This attribute is used when deleting columns by clicking on '<i class="fa fa-times-circle ramp2"></i>' on <thead><th> at the same time to delete the same column from the print table.
     -->
<div class="sr-tbl-cont card-body table-responsive p-0" id="tbl_bls_grid_container" style="background-color: #fff;">
    <table class="sr-tbl-reports table table-head-fixed text-nowrap  table-hover table-striped" id="tbl_bls_grid" data-print-table="#tbl_bls_grid_print">
        <thead>
            <tr>
                <!-- ".dv-ramp .ramp2" is a close button to delete the entair column-->
                <?php $col_index = 0; ?>
                <th style="width: 10px" data-col="<?= ++$col_index ?>">
                    <span class="export" data-exportStyle="text-align: left; color:orange;font-weight:bolder">#</span>
                    <div class="dv-ramp" title="Delete the column"><i class="fa fa-times-circle ramp2"></i></div>
                </th>
                <th data-col="<?= ++$col_index ?>">
                    <span class="export" data-exportStyle="text-align: center; color:orange;font-weight:bolder">Bill No:</span>
                    <div class="dv-ramp" title="Delete the column"><i class="fa fa-times-circle ramp2"></i></div>
                </th>
                <th data-col="<?= ++$col_index ?>">
                    <span class="export" data-exportStyle="">Date</span>
                    <div class="dv-ramp" title="Delete the column"><i class="fa fa-times-circle ramp2"></i></div>
                </th>
                <th data-col="<?= ++$col_index ?>">
                    <span class="export" data-exportStyle="">From</span>
                    <div class="dv-ramp" title="Delete the column"><i class="fa fa-times-circle ramp2"></i></div>
                </th>
                <th data-col="<?= ++$col_index ?>">
                    <span class="export" data-exportStyle="">To</span>
                    <div class="dv-ramp" title="Delete the column"><i class="fa fa-times-circle ramp2"></i></div>
                </th>
                <th data-col="<?= ++$col_index ?>">
                    <span class="export">Godown</span>
                    <div class="dv-ramp" title="Delete the column"><i class="fa fa-times-circle ramp2"></i></div>
                </th>
                <th data-col="<?= ++$col_index ?>">
                    <span class="export">Product</span>
                    <div class="dv-ramp" title="Delete the column"><i class="fa fa-times-circle ramp2"></i></div>
                </th>
                <th data-col="<?= ++$col_index ?>">
                    <span class="export">Batch</span>
                    <div class="dv-ramp" title="Delete the column"><i class="fa fa-times-circle ramp2"></i></div>
                </th>
                <th data-col="<?= ++$col_index ?>">
                    <span class="export">Quantity</span>
                    <div class="dv-ramp" title="Delete the column"><i class="fa fa-times-circle ramp2"></i></div>
                </th>
                <th data-col="<?= ++$col_index ?>">
                    <span class="export">Rate <small> (With Tax)</small></span>
                    <div class="dv-ramp" title="Delete the column"><i class="fa fa-times-circle ramp2"></i></div>
                </th>
                <th data-col="<?= ++$col_index ?>">
                    <span class="export">Amount <small> (Without Tax)</small></span>
                    <div class="dv-ramp" title="Delete the column"><i class="fa fa-times-circle ramp2"></i></div>
                </th>
                <th data-col="<?= ++$col_index ?>">
                    <span class="export">CGST</span>
                    <div class="dv-ramp" title="Delete the column"><i class="fa fa-times-circle ramp2"></i></div>
                </th>
                <th data-col="<?= ++$col_index ?>">
                    <span class="export">SGST</span>
                    <div class="dv-ramp" title="Delete the column"><i class="fa fa-times-circle ramp2"></i></div>
                </th>
                <th data-col="<?= ++$col_index ?>">
                    <span class="export">IGST</span>
                    <div class="dv-ramp" title="Delete the column"><i class="fa fa-times-circle ramp2"></i></div>
                </th>
                <th data-col="<?= ++$col_index ?>">
                    <span class="export">Gross</span>
                    <div class="dv-ramp" title="Delete the column"><i class="fa fa-times-circle ramp2"></i></div>
                </th>

                <th data-col="<?= ++$col_index ?>">
                    <span class="export">KFC</span>
                    <div class="dv-ramp" title="Delete the column"><i class="fa fa-times-circle ramp2"></i></div>
                </th>

                <th data-col="<?= ++$col_index ?>">
                    <span class="export">Gross Discount</span>
                    <div class="dv-ramp" title="Delete the column"><i class="fa fa-times-circle ramp2"></i></div>
                </th>
                <th data-col="<?= ++$col_index ?>">
                    <span class="export">Round Off</span>
                    <div class="dv-ramp" title="Delete the column"><i class="fa fa-times-circle ramp2"></i></div>
                </th>
                <th data-col="<?= ++$col_index ?>">
                    <span class="export">Net Amount</span>
                    <div class="dv-ramp" title="Delete the column"><i class="fa fa-times-circle ramp2"></i></div>
                </th>
                <th data-col="<?= ++$col_index ?>">
                    <span class="export">Paid</span>
                    <div class="dv-ramp" title="Delete the column"><i class="fa fa-times-circle ramp2"></i></div>
                </th>
                <th data-col="<?= ++$col_index ?>">
                    <span class="export">Balance</span>
                    <div class="dv-ramp" title="Delete the column"><i class="fa fa-times-circle ramp2"></i></div>
                </th>
                <th class="text-center" style="width: 200px">Action</th>
            </tr>
        </thead>
        <tbody></tbody>
        <tfoot> </tfoot>
    </table>
</div>

<!-- /.card-body -->