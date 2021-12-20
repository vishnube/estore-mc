<div class="row justify-content-md-center mt-2">
    <div class="col-md-12">

        <div class="card card-primary">
            <div class="card-header">
                <h4 class="card-title">
                    <a data-toggle="collapse" href="#stock_search_form_container" role="button" aria-expanded="false" aria-controls="stock_search_form_container">
                        <i class="pr-2 fas fa-search"></i>
                        SEARCH STOCKS
                        <i class="pl-5 fas fa-chevron-down"></i>
                    </a>
                </h4>
                <!-- Handler to scroll to 'data-pagin' OR 'data-table' on click -->
                <i class="sr-go-to-tbl fas fa-angle-double-down fa-2x" title="Move down" data-pagin="#stk_pagination" data-table="#tbl_stk_container"></i>

            </div>
            <div class="sr-collapse collapse" id="stock_search_form_container">
                <form class="sr-form" role="form" id="stk_search_form" onsubmit="$(this).find(':submit').prop('disabled', true)" data-afterFormInit="">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 col-sm-3">
                                <div class="sr-wraper">
                                    <div class="form-group">
                                        <label>Date:</label>
                                        <div class="row">
                                            <div class="col-12 col-xl-6 input-group input-group-sm">
                                                <div class="input-group-prepend"><span class="input-group-text">From</div>
                                                <input type="text" name="f_date" id="f_date" class="sr-date-field-1 form-control form-control-sm" data-inputmask-alias="datetime" data-inputmask-inputformat="dd-mm-yyyy" data-mask>
                                            </div>

                                            <div class="col-12 col-xl-6 input-group input-group-sm">
                                                <div class="input-group-prepend"><span class="input-group-text">To</div>
                                                <input type="text" name="t_date" id="t_date" class="sr-date-field-1 form-control form-control-sm" data-inputmask-alias="datetime" data-inputmask-inputformat="dd-mm-yyyy" data-mask>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-sm-3">
                                <div class="sr-wraper">
                                    <div class="form-group">
                                        <label>Central Store</label>

                                        <div class="select2-sm">
                                            <select name="cstr_id" class="select2 cstr_option form-control form-control-sm select2-danger" data-dropdown-css-class="select2-info" style="width: 100%;">
                                                <?= get_options($cstr_option) ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="col-12 col-sm-3">
                                <div class="sr-wraper">
                                    <div class="form-group">
                                        <label>Godown</label>
                                        <div class="select2-sm select2-success">
                                            <select name="gdn_id[]" class="select2 gdn_option form-control form-control-sm" multiple="multiple" data-placeholder="Select a Godown" data-dropdown-css-class="select2-success" style="width: 100%;"></select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-sm-3">
                                <div class="sr-wraper">
                                    <div class="form-group">
                                        <label>Product</label>
                                        <div class="select2-sm select2-danger">
                                            <select name="prd_id[]" class="select2 prd_option form-control form-control-sm" multiple="multiple" data-placeholder="Select a Product" data-dropdown-css-class="select2-danger" style="width: 100%;">
                                                <?= get_options($prd_option, '', '', false) ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="col-12 col-sm-3">
                                <div class="sr-wraper">
                                    <div class="form-group">
                                        <label>Batch No</label>
                                        <div class="select2-sm select2-info">
                                            <select name="pdbch_id[]" class="select2 pdbch_option form-control form-control-sm" multiple="multiple" data-placeholder="Select a Batch" data-dropdown-css-class="select2-info" style="width: 100%;"></select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-sm-3">
                                <div class="form-group clearfix">
                                    <label class="sr-label">Group By</label><br>
                                    <div class="sr-wraper sr-check icheck-primary d-inline">
                                        <input data-default="false" name="grpby_batch" value="1" type="checkbox" id="lbl_grpby_batch">
                                        <label for="lbl_grpby_batch">
                                            <label>Batch No:</label>
                                        </label>
                                    </div>
                                    <div class="sr-wraper sr-check icheck-primary d-inline">
                                        <input data-default="false" name="grpby_cstr" value="1" type="checkbox" id="lbl_grpby_cstr">
                                        <label for="lbl_grpby_cstr">
                                            <label>Central Store</label>
                                        </label>
                                    </div>
                                </div>
                            </div>


                            <div class="col-12 col-sm-3">
                                <div class="form-group clearfix">
                                    <label class="sr-label">Records</label><br>
                                    <div class="select2-sm select2-success">
                                        <select name="records[]" class="select2 rec_option form-control form-control-sm" multiple="multiple" data-placeholder="Select a Record" data-dropdown-css-class="select2-success" style="width: 100%;">
                                            <?= get_options($rec_option, '', '', false) ?>
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