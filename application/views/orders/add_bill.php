<div class="border">
    <div class="row m-0 bg-srlight">
        <div class="col-6">
            <div class="m-0 p-2">
                <div class="btn btn-warning btn-sm bill-add-prd">ADD PRODUCT</div>
            </div>
        </div>
        <div class="col-6">
            <h2 class="text-center mb-0 mt-1 text-danger" style="font-family: 'Arial Black';">BILL</h2>
        </div>
    </div>
    <div class="row m-0 bg-dark">
        <div class="col-12">
            <div class="notice-board p-2 p-md-4" style="display: none;"></div>
        </div>
    </div>


    <div class="">
        <table class="table table-head-fixed text-nowrap" id="tbl-bill-items" style="width:100%;">
            <thead>
                <tr>
                    <th style="width: 10px">#</th>
                    <th>PRODUCT</th>
                    <th>TAX</th>
                    <th>QUANTITY</th>
                    <th>RATE</th>
                    <th>AMOUNT</th>
                    <th class="text-right">ACTION</th>
                </tr>
            </thead>
            <tbody>
                <tr class="no-result-row">
                    <td colspan="7">
                        <h3 class="text-danger text-center">
                            <i class="fas fa-exclamation-triangle fa-lg"></i>
                            <span class="pl-3"> NO PRODUCTS</span>
                        </h3>
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="p-2 px-md-2 py-md-4 bg-srlight">
            <div class="row">
                <div class="col col-12 col-md-6">
                    <ul class="list-group">

                        <li class="list-group-item d-flex justify-content-between align-items-center">TOTAL AMOUNT: <span class="tot-val text-success tot-amt"></span></li>

                        <li class="list-group-item d-flex justify-content-between align-items-center">TOTAL TAX : <span class="tot-val text-warning tot-tax"></span></li>

                        <li class="list-group-item d-flex justify-content-between align-items-center">GROSS AMOUNT: <span class="tot-val text-success tot-grs"></span></li>

                        <li class="list-group-item d-flex justify-content-between align-items-center">DISCOUNT: <span class="tot-val tot-disc"></span></li>
                    </ul>
                </div>
                <div class="col col-12 col-md-6">
                    <ul class="list-group">

                        <li class="list-group-item d-flex justify-content-between align-items-center">ROUND OFF: <span class="tot-val tot-round"></span></li>

                        <li class="list-group-item d-flex justify-content-between align-items-center">NET AMOUNT: <span class="tot-val text-success tot-net font-weight-bold"></span></li>

                        <!-- DO IT AFTER CLARIFICATION: The paid amount in order section won't be show in Bill section. Because it is a duplicate entry -->
                        <li class="list-group-item d-flex justify-content-between align-items-center">PAID: <span class="tot-val tot-pd"></span></li>

                        <li class="list-group-item d-flex justify-content-between align-items-center">BALANCE: <span class="tot-val text-danger tot-bs"></span></li>
                    </ul>
                </div>

            </div>
        </div>



        <div class="p-2 px-md-2 py-md-4 bg-srlight border-top border-bottom">
            <div class="row">
                <div class="col col-12 col-md-6">
                    <ul class="list-group">
                        <li class="list-group-item">
                            <div>Total
                                <span class="tot-moved sr-badge bg-danger"></span>
                                Products Moved to Bill
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div>Total <span class="tot-no-stock sr-badge bg-info"></span> Products Without Stock</div>
                        </li>
                    </ul>
                </div>
                <div class="col col-12 col-md-6">
                    <ul class="list-group">
                        <li class="list-group-item">
                            <div>Total <span class="tot-no-odr sr-badge bg-success"></span> Products Without Order</div>
                        </li>
                        <li class="list-group-item">
                            <div>Total <span class="tot-del sr-badge bg-primary"></span> Products Deleted</div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>



        <div class="p-2 px-md-2 py-md-4 bg-srlight border-top border-bottom">
            <div class="row">
                <div class="col col-12 col-md-6">
                    <div class="form-group clearfix m-0">
                        <div class="icheck-danger d-inline">
                            <input type="checkbox" class="no-init" id="check-print">
                            <label for="check-print"> PRINT</label>
                        </div>
                    </div>
                </div>
                <div class="col col-12 col-md-6 text-right">

                    <div class="sr-wraper-bold">
                        <button type="submit" class="save-btn btn btn-primary"><i class="fas fa-save"></i>&nbsp;SAVE BILL</button>
                    </div>
                </div>

            </div>
        </div>


    </div>
</div>