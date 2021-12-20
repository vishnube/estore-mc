<div class="modal fade modal-fullscreen-sm" id="product_window">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h4 class="modal-title">PRODUCT WINDOW</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <div id="prd-accordion">
                    <div class="card card-success product-card">

                        <div class="card-header" id="headingOne">
                            <h5 class="mb-0 card-title">
                                <button class="btn btn-link text-white" data-toggle="collapse" data-target="#select-product" aria-expanded="true" aria-controls="select-product">
                                    PRODUCT
                                </button>
                            </h5>

                            <div class="card-tools float-left" style="width:85%;">






                                <div class="row">
                                    <div class="col-12 col-md-6">
                                        <input type="text" id="pw_prd_name" autocomplete="off" class="form-control form-control-sm">
                                    </div>
                                    <div class="col-4 col-md-2">
                                        <input type="text" id="pw_qty" autocomplete="off" placeholder="Quantity" class="form-control form-control-sm">
                                    </div>
                                    <div class="col-4 col-md-2 pw-unt-col">
                                        <select id="pw_ugp_id" class="form-control form-control-sm">
                                            <option value="">No Units</option>
                                        </select>

                                        <!-- Unit details will be loaded here -->
                                        <div class="unt-cont"></div>
                                    </div>
                                    <div class="col-4 col-md-2">
                                        <input type="text" id="pw_rate" autocomplete="off" placeholder="Price" class="form-control form-control-sm">
                                    </div>
                                </div>
                                <input id="pw_prd_id" type="hidden">
                                <input id="pw_gdn_id" type="hidden">
                                <input id="pw_cgst_p" type="hidden">
                                <input id="pw_sgst_p" type="hidden">
                                <input id="pw_igst_p" type="hidden">
                                <!-- <input id="pw_rate" type="hidden"> -->
                                <input id="pw_pdbch_mrp" type="hidden">
                                <input id="pw_pdbch_exp" type="hidden">
                                <input id="pw_gdn_name" type="hidden">

                                <select id="pw_pdbch_id" style="display:none;"></select>













                            </div>

                        </div>

                        <div id="select-product" class="collapse show" aria-labelledby="headingOne" data-parent="#prd-accordion">
                            <div class="card-body">
                                <div class="table-responsive p-0 dv-prod-tbl" style="height: 200px;">
                                    <table class="table table-head-fixed table-hover text-nowrap" id="prod-list-table">
                                        <tbody>
                                            <?php
                                            foreach ($prd_data as $prd) {
                                            ?>

                                                <tr class="prd-row">

                                                    <td>
                                                        <?= $prd['prd_name'] ?>
                                                        <input type="hidden" class="prd_id" value="<?= $prd['prd_id'] ?>" data-prd_exp_p="<?= $prd['prd_exp_p'] ?>" data-prd_estr_cmsn_p="<?= $prd['prd_estr_cmsn_p'] ?>">

                                                        <!-- 
                                                The product name will be placed inside <span>, because the name may contain double/single quots.
                                                for eg:- suppose the name is 4"PVC. 
                                                Then the output of the code                                                     
                                                    <input type="hidden" class="prd_name" value="<?= $prd['prd_name'] ?>">
                                                will be as
                                                    <input type="hidden" class="prd_name" value="4"PVC">
                                                So It will cause incurrect name value.
                                                -->
                                                        <span class="prd_name d-none"><?= $prd['prd_name'] ?></span>
                                                    </td>
                                                    <td><?= $prd['prd_code'] ?> <span class="d-none"><?= $prd['prd_barcode'] ?></span></td>
                                                </tr>

                                            <?php
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card card-warning product-batch-card">
                        <div class="card-header" id="headingTwo">
                            <h5 class="mb-0 card-title">
                                <button class="btn btn-link collapsed text-dark" data-toggle="collapse" data-target="#select-batch" aria-expanded="false" aria-controls="select-batch">
                                    BATCH
                                </button>
                            </h5>

                            <!-- Selected product batch will be displayed here -->
                            <div class="card-tools float-left sel-pdbch-dt"></div>

                        </div>
                        <div id="select-batch" class="collapse" aria-labelledby="headingTwo" data-parent="#prd-accordion">
                            <div class="card-body">
                                <div class="table-responsive" id="tbl_pdbch_container" style="height:300px; overflow-y:auto;">
                                    <table class="table table-head-fixed  table-hover" id="tbl_pdbch">
                                        <thead>
                                            <tr>
                                                <th>BATCH No</th>
                                                <th>BILLING PRICE</th>
                                                <th>SELLING PRICE</th>
                                                <th>MFG DATE</th>
                                                <th>EXPIRY DATE</th>
                                                <th>MRP</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                </div> <!-- <div id="prd-accordion"> -->
            </div> <!-- <div class="modal-body"> -->
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default closer" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary apply">APPLY</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>