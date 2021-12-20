<form class="sr-form" role="form" id="pdbch_add_form" onsubmit="$(this).find(':submit').prop('disabled', true)" data-afterFormInit="AfterInitProductBatchAddForm">

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="sr-wraper">
                        <div class="form-group">
                            <label class="sr-mdt-lbl">Name</label>
                            <input type="text" name="pdbch_name" class="form-control form-control-sm">
                        </div>
                    </div>
                    <div class="sr-wraper">
                        <div class="form-group">
                            <label>Details</label>
                            <textarea name="pdbch_dt" class="form-control form-control-sm" rows="4" placeholder="Enter ..."></textarea>
                        </div>
                    </div>

                    <div class="sr-wraper">
                        <div class="form-group">
                            <label class="">Estore Commission %</label>
                            <input type="text" name="pdbch_ecp" class="form-control form-control-sm">
                        </div>
                    </div>

                    <div class="sr-wraper">
                        <div class="form-group">
                            <label class="">Estore Commission Amount </label>
                            <input type="text" name="pdbch_eca" class="form-control form-control-sm">
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="sr-wraper">
                        <div class="form-group">
                            <label class="sr-mdt-lbl">MFG Date</label>

                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                </div>
                                <!-- '.sr-date-field-1' is used in initFormInputs() to repopup with format() its value after before_edit -->
                                <!-- data-default is used to initialize the form.-->
                                <input type="text" data-default="<?= today() ?>" name="pdbch_mfg" id="pdbch_mfg" class="sr-date-field-1 form-control form-control-sm" data-inputmask-alias="datetime" data-inputmask-inputformat="dd-mm-yyyy" data-mask>
                            </div>
                            <!-- /.input group -->
                        </div>
                    </div>

                    <div class="form-group clearfix">
                        <label class="sr-label">Is Farmer</label><br>
                        <!-- .sr-wraper sr-check is to highlight element on focus -->

                        <div class="sr-wraper sr-check icheck-primary d-inline">
                            <input name="pdbch_farmer" data-default="1" type="checkbox" id="checkboxPrimary1">
                            <label for="checkboxPrimary1">
                                <label>Farmer</label>
                            </label>
                        </div>
                    </div>



                    <div class="sr-wraper">
                        <div class="form-group">
                            <label class="">Expense %</label>
                            <input type="text" name="pdbch_expp" class="form-control form-control-sm">
                        </div>
                    </div>

                    <div class="sr-wraper">
                        <div class="form-group">
                            <label class="">Expense Amount</label>
                            <input type="text" name="pdbch_expa" class="form-control form-control-sm">
                        </div>
                    </div>

                </div>
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="sr-wraper">
                        <div class="form-group">
                            <label class="sr-mdt-lbl">Expiry Date</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                </div>
                                <input type="text" data-default="<?= today() ?>" name="pdbch_exp" id="pdbch_exp" class="sr-date-field-1 form-control form-control-sm" data-inputmask-alias="datetime" data-inputmask-inputformat="dd-mm-yyyy" data-mask>
                            </div>
                        </div>
                    </div>
                    <div class="sr-wraper">
                        <div class="form-group">
                            <label class="sr-mdt-lbl">Billing Price</label>
                            <input type="text" name="pdbch_bp" class="form-control form-control-sm">
                        </div>
                    </div>
                    <div class="sr-wraper">
                        <div class="form-group">
                            <label class="sr-mdt-lbl">Selling Price</label>
                            <input type="text" name="pdbch_sp" class="form-control form-control-sm">
                        </div>
                    </div>
                    <div class="sr-wraper">
                        <div class="form-group">
                            <label class="">Balance of Eternal</label>
                            <input type="text" name="pdbch_ba" class="form-control form-control-sm">
                        </div>
                    </div>
                    <div class="sr-wraper">
                        <div class="form-group">
                            <label class="">MRP</label>
                            <input type="text" name="pdbch_mrp" class="form-control form-control-sm">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer text-center">
            <input name="pdbch_id" type="hidden">
            <input name="pdbch_fk_products" id="pdbch_fk_products" type="hidden">

            <!-- .sr-wraper-bold is to highlight element on focus -->
            <div class="sr-wraper-bold">
                <button class="btn btn-primary" type="submit"><i class="fas fa-save"></i>&nbsp;SAVE</button>
            </div>

            <div class="sr-wraper-bold">
                <div class="sr-reset-btn btn btn-danger"><i class="fas fa-repeat-alt"></i> &nbsp;RESET</div>
            </div>
            <div class="o_errors"></div>
        </div>
    </div>
</form>