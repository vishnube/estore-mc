<div class="row justify-content-md-center mt-2">
    <div class="col-md-12">

        <div class="card card-primary">
            <div class="card-header">
                <h4 class="card-title">
                    <a data-toggle="collapse" href="#stock_flow_search_form_container" role="button" aria-expanded="false" aria-controls="stock_flow_search_form_container">
                        <i class="pr-2 fas fa-search"></i>
                        SEARCH STOCKS
                        <i class="pl-5 fas fa-chevron-down"></i>
                    </a>
                </h4>
                <!-- Handler to scroll to 'data-pagin' OR 'data-table' on click -->
                <i class="sr-go-to-tbl fas fa-angle-double-down fa-2x" title="Move down" data-pagin="#stock_flow_pagination" data-table="#tbl_stock_flow_container"></i>

            </div>
            <div class="sr-collapse collapse" id="stock_flow_search_form_container">
                <form class="sr-form" role="form" id="stock_flow_search_form" data-afterFormInit="">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 col-sm-3">
                                <div class="sr-wraper">
                                    <div class="form-group">
                                        <label>Date:</label>
                                        <div class="row">
                                            <div class="col-12 col-xl-6 input-group input-group-sm">
                                                <div class="input-group-prepend"><span class="input-group-text">From</div>
                                                <input type="text" name="f_stkavg_date" id="f_stkavg_date" class="sr-date-field-1 form-control form-control-sm" data-inputmask-alias="datetime" data-inputmask-inputformat="dd-mm-yyyy" data-mask>
                                            </div>

                                            <div class="col-12 col-xl-6 input-group input-group-sm">
                                                <div class="input-group-prepend"><span class="input-group-text">To</div>
                                                <input type="text" name="t_stkavg_date" id="t_stkavg_date" class="sr-date-field-1 form-control form-control-sm" data-inputmask-alias="datetime" data-inputmask-inputformat="dd-mm-yyyy" data-mask>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-sm-3">
                                <div class="sr-wraper">
                                    <div class="form-group">
                                        <label>Central Store</label>
                                        <select name="stkavg_cstr_mbr_id" class="cstr_option form-control form-control-sm">
                                            <?= get_options($cstr_mbr_option) ?>
                                        </select>

                                    </div>
                                </div>
                            </div>


                            <div class="col-12 col-sm-3">
                                <div class="sr-wraper">
                                    <div class="form-group">
                                        <label>Godown</label>
                                        <select name="stkavg_fk_godowns" class="gdn_option form-control form-control-sm">
                                            <?= get_options(array(), '', 'No Godowns') ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-sm-3">
                                <div class="sr-wraper">
                                    <div class="form-group">
                                        <label>Product</label>
                                        <select name="stkavg_fk_products" class="prd_option form-control form-control-sm">
                                            <?= get_options($prd_option) ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-sm-3">
                                <div class="sr-wraper">
                                    <div class="form-group">
                                        <label>Unit</label>
                                        <select name="stkavg_ugp_id" class="ugp_option form-control form-control-sm">
                                            <?= get_options(array(), '', 'No Units') ?>
                                        </select>
                                    </div>
                                </div>
                            </div>


                            <div class="col-12 col-sm-3">
                                <div class="sr-wraper">
                                    <div class="form-group">
                                        <label>Batch No</label>
                                        <select name="stkavg_fk_product_batches" class="pdbch_option form-control form-control-sm">
                                            <?= get_options(array(), '', 'No Batches') ?>
                                        </select>
                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>
                    <div class="card-footer text-center" style="border-bottom:2px solid #007bff">
                        <div class="sr-wraper-bold">
                            <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i>&nbsp;SEARCH</button>
                        </div>
                        <div class="sr-wraper-bold">
                            <div class="sr-reset-btn btn btn-danger"><i class="fas fa-repeat-alt"></i> &nbsp;RESET</div>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>