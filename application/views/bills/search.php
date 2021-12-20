<div class="row justify-content-md-center mt-2">
    <div class="col-md-12">

        <div class="card card-primary">
            <div class="card-header">
                <h4 class="card-title">
                    <a data-toggle="collapse" href="#bill_search_form_container" role="button" aria-expanded="false" aria-controls="bill_search_form_container">
                        <i class="pr-2 fas fa-search"></i>
                        SEARCH BILLS
                        <i class="pl-5 fas fa-chevron-down"></i>
                    </a>
                </h4>
                <!-- Handler to scroll to 'data-pagin' OR 'data-table' on click -->
                <i class="sr-go-to-tbl fas fa-angle-double-down fa-2x" title="Move down" data-pagin="#bls_pagination" data-table=".dv-tbl-port"></i>

            </div>
            <div class="sr-collapse collapse" id="bill_search_form_container">
                <form class="sr-form" role="form" id="bls_search_form" onsubmit="$(this).find(':submit').prop('disabled', true)" data-afterFormInit="">
                    <div class="card-body">
                        <div class="row">






                            <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                                <div class="sr-wraper">
                                    <div class="form-group">
                                        <label>Date:</label>
                                        <div class="row">
                                            <div class="col-12 col-xl-6 input-group input-group-sm">
                                                <div class="input-group-prepend"><span class="input-group-text">From</div>
                                                <input type="text" data-default="<?= today() ?>" name="f_date" id="f_date" class="sr-date-field-1 form-control form-control-sm" data-inputmask-alias="datetime" data-inputmask-inputformat="dd-mm-yyyy" data-mask>
                                            </div>

                                            <div class="col-12 col-xl-6 input-group input-group-sm">
                                                <div class="input-group-prepend"><span class="input-group-text">To</div>
                                                <input type="text" data-default="<?= today() ?>" name="t_date" id="t_date" class="sr-date-field-1 form-control form-control-sm" data-inputmask-alias="datetime" data-inputmask-inputformat="dd-mm-yyyy" data-mask>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>


                            <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                                <div class="from-slot">
                                    <div class="slot-1">
                                        <div class="sup-slot">
                                            <div class="sr-wraper">
                                                <div class="form-group">
                                                    <label class="sr-mdt-lbl">Supplier</label>
                                                    <div class="select2-sm">
                                                        <select name="prty_id" id="prty_id" class="form-control form-control-sm select2 select2-danger" data-dropdown-css-class="select2-info" style="width: 100%;">
                                                            <?= get_options($prty_option) ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="fmly-slot" style="display: none;">
                                            <div class="sr-wraper">
                                                <div class="form-group">
                                                    <label class="sr-mdt-lbl">Family</label>
                                                    <div class="select2-sm">
                                                        <select name="fmly_id" id="fmly_id" class="form-control form-control-sm select2 select2-danger" data-dropdown-css-class="select2-info" style="width: 100%;"> </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="to-slot">
                                    <div class="slot-2">
                                        <div class="sr-wraper">
                                            <div class="form-group cstr-group">
                                                <label class="sr-mdt-lbl">Central Store</label>
                                                <div class="select2-sm">
                                                    <select name="cstr_id" id="cstr_id" class="form-control form-control-sm select2 select2-success" data-dropdown-css-class="select2-info" style="width: 100%;">
                                                        <?= get_options($cstr_option) ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>




                            <div class="col-12 col-sm-6 col-md-4 col-lg-3">

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

                                <div class="sr-wraper">
                                    <div class="form-group">
                                        <label>Batch No</label>
                                        <div class="select2-sm select2-info">
                                            <select name="pdbch_id[]" class="select2 pdbch_option form-control form-control-sm" multiple="multiple" data-placeholder="Select a Batch" data-dropdown-css-class="select2-info" style="width: 100%;"></select>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="col-12 col-sm-6 col-md-4 col-lg-3">

                                <div class="form-group clearfix">
                                    <label class="sr-label sr-mdt-lbl">Status</label><br>
                                    <!-- .sr-wraper sr-rad is to highlight element on focus -->
                                    <div class="sr-wraper sr-rad icheck-danger d-inline">
                                        <!-- data-default="true" is used to initialize the form.-->
                                        <input data-default="true" type="radio" value="1" name="bls_status" id="bls_search_status1111">
                                        <label for="bls_search_status1111">Active</label>
                                    </div>

                                    <div class="sr-wraper sr-rad icheck-danger d-inline">
                                        <input type="radio" value="2" name="bls_status" id="bls_search_status2222">
                                        <label for="bls_search_status2222">Converted</label>
                                    </div>

                                    <div class="sr-wraper sr-rad icheck-danger d-inline">
                                        <input type="radio" value="3" name="bls_status" id="bls_search_status3333">
                                        <label for="bls_search_status3333">Cancelled</label>
                                    </div>

                                    <div class="sr-wraper sr-rad icheck-danger d-inline">
                                        <input type="radio" value="" name="bls_status" id="bls_search_status9999">
                                        <label for="bls_search_status9999">All</label>
                                    </div>
                                </div>

                                <div class="form-group clearfix">
                                    <label class="sr-label sr-mdt-lbl">Display Format</label><br>
                                    <!-- .sr-wraper sr-rad is to highlight element on focus -->
                                    <div class="sr-wraper sr-rad icheck-danger d-inline">
                                        <!-- data-default="true" is used to initialize the form.-->
                                        <input type="radio" value="Grid" class="rep_form" name="rep_form" id="rep_form_type1">
                                        <label for="rep_form_type1">Grid</label>
                                    </div>

                                    <div class="sr-wraper sr-rad icheck-danger d-inline">
                                        <input data-default="true" type="radio" value="A4" name="rep_form" class="rep_form" id="rep_form_type2">
                                        <label for="rep_form_type2">A4</label>
                                    </div>

                                    <div class="sr-wraper sr-rad icheck-danger d-inline">
                                        <input type="radio" value="Tile" name="rep_form" class="rep_form" id="rep_form_type3">
                                        <label for="rep_form_type3">Tile</label>
                                    </div>
                                </div>

                            </div>
                            <!-- /.col -->

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