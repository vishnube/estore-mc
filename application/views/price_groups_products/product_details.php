<div class="modal modal-fullscreen-sm" id="product-details">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h4 class="modal-title">PRODUCT DETAILS</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="sr-form" role="form" id="prd-dt-from" onsubmit="$(this).find(':submit').prop('disabled', true)">
                <div class="modal-body">
                    <table class="table table-hover table-striped" id="tbl_prd_dt" data-onInit="" data-blockNewRow="false">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>BATCH</th>
                                <th>MRP</th>
                                <th>MIN-QTY</th>
                                <th>UNIT</th>
                                <th>Avg RATE</th>
                                <th>DISC</th>
                                <th>DISC %</th>
                                <th>RATE</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
                <div class="modal-footer">

                    <input type="hidden" name="pgprd_fk_price_groups" class="pgprd_fk_price_groups" value="">
                    <input type="hidden" name="pgprd_fk_products" class="pgprd_fk_products" value="">
                    <div class="sr-wraper-bold">
                        <button type="submit" class="btn btn-primary save">SAVE</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>