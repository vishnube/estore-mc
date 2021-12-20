<style type="text/css">
    #batch_prices_modal thead th {
        border-bottom: none;
    }

    #batch_prices_modal tr.r1 td {
        padding: 18px 5px 2px 5px;
        font-size: 12px;
        border-top: 2px dashed #06abfc;
        border-bottom: none;
    }

    #batch_prices_modal tr.r2 td {
        padding: 2px 5px 18px 5px;
        font-size: 12px;
        border-top: none;
        border-bottom: 2px dashed #06abfc;
    }
</style>
<div class="modal fade" tabindex="-1" role="dialog" id="batch_prices_modal" style="z-index: 1060;">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header bg-success">
                <h4 class="modal-title sr-form-title has-default-value" data-default="BATCH PRICE GROUP" id="gridSystemModalLabel">BATCH PRICE GROUP</h4>
                <button title="Close (Esc)" type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>


            <div class="modal-body">
                <div class="table-responsive p-0" id="tbl_price_container" style="height:300px; overflow-y:auto;">
                    <table class="table table-head-fixed text-nowrap" id="tbl_price">
                        <thead>
                            <tr>
                                <th></th>
                                <th>PRICE GROUP</th>
                                <th>MIN-QTY</th>
                                <th>RATE </th>
                                <th>LEVEL </th>
                                <!-- <th>CREATED </th> -->
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>


            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>