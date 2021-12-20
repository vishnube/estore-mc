<form class="sr-form" role="form" id="bls_add_form" onsubmit="$(this).find(':submit').prop('disabled', true)" data-afterFormInit="afterBillAddFormReset">
    <div class="row justify-content-md-center mt-2">
        <div class="col-12">
            <div class="card card-primary">
                <div class="card-body p-0">
                    <div class="card card-primary card-outline card-outline-tabs">
                        <div class="card-header p-0 border-bottom-0">
                            <ul class="nav nav-tabs  nav-justified" id="custom-tabs-three-tab" role="tablist">
                                <!-- <li class="nav-item pt-0">
                                    <a class="nav-link active" id="custom-tabs-three-home-tab" data-toggle="pill" href="#custom-tabs-three-home" role="tab" aria-controls="custom-tabs-three-home" aria-selected="true">BILLING</a>
                                </li> -->
                                <!-- <li class="nav-item pt-0">
                                    <a class="nav-link" id="custom-tabs-three-profile-tab" data-toggle="pill" href="#custom-tabs-three-profile" role="tab" aria-controls="custom-tabs-three-profile" aria-selected="false">Profile</a>
                                </li>
                                <li class="nav-item pt-0">
                                    <a class="nav-link" id="custom-tabs-three-messages-tab" data-toggle="pill" href="#custom-tabs-three-messages" role="tab" aria-controls="custom-tabs-three-messages" aria-selected="false">Messages</a>
                                </li>
                                <li class="nav-item pt-0">
                                    <a class="nav-link" id="custom-tabs-three-settings-tab" data-toggle="pill" href="#custom-tabs-three-settings" role="tab" aria-controls="custom-tabs-three-settings" aria-selected="false">Settings</a>
                                </li> -->
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content" id="custom-tabs-three-tabContent">
                                <div class="tab-pane fade show active" id="custom-tabs-three-home" role="tabpanel" aria-labelledby="custom-tabs-three-home-tab">
                                    <div class="row">

                                        <div class="col-md-3">
                                            <div class="card card-info">
                                                <div class="card-header">
                                                    <h3 class="card-title">FROM</h3>
                                                    <!-- <div class="card-tools">
                                                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                                                        </button>
                                                    </div> -->
                                                </div>
                                                <div class="card-body from-slot">

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

                                                            <div class="sr-wraper">
                                                                <div class="form-group">
                                                                    <label class="sr-mdt-lbl">GSTIN</label>
                                                                    <select name="prty_gst_id" id="prty_gst_id" class="form-control form-control-sm"><?= get_options() ?> </select>

                                                                    <!-- Current GST details -->
                                                                    <div class="dv-gst-dt text-center" data-stt_id=""></div>

                                                                    <!-- All GST Details -->
                                                                    <div class="dv-all-gst-dt"></div>
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



                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="card card-success">
                                                <div class="card-header">
                                                    <h3 class="card-title">TO</h3>
                                                    <!-- <div class="card-tools">
                                                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                                                        </button>
                                                    </div> -->
                                                </div>
                                                <div class="card-body to-slot">
                                                    <div class="slot-2">
                                                        <div class="sr-wraper">
                                                            <div class="form-group cstr-group">
                                                                <label class="sr-mdt-lbl">Central Store</label>
                                                                <div class="select2-sm">
                                                                    <select name="cstr_id" id="cstr_id" class="form-control form-control-sm select2 select2-success" data-dropdown-css-class="select2-info" style="width: 100%;">
                                                                        <?= get_options($cstr_option) ?>
                                                                    </select>
                                                                    <div class="dv-cstr-stt-dt text-center" data-stt_id=""></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="card card-primary">
                                                <div class="card-header">
                                                    <h3 class="card-title">INVOICE</h3>
                                                    <!-- <div class="card-tools">
                                                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                                                        </button>
                                                    </div> -->
                                                </div>
                                                <div class="card-body">
                                                    <div class="row m-0 p-0">
                                                        <div class="col-6 m-0 p-0">
                                                            <div class="sr-wraper">
                                                                <div class="form-group">
                                                                    <label>Date:</label>

                                                                    <div class="input-group TTool">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                                                        </div>
                                                                        <!-- '.sr-date-field-1' is used in initFormInputs() to repopup with format() its value after before_edit -->
                                                                        <!-- data-default is used to initialize the form.-->
                                                                        <input type="text" data-default="<?= today() ?>" name="bls_date" id="bls_date" class="sr-date-field-1 form-control form-control-sm" data-inputmask-alias="datetime" data-inputmask-inputformat="dd-mm-yyyy" data-mask>

                                                                        <!-- 'clock' class will make the element as a digital clock by "js\clock.js". It is need only when the bill is "Add". -->
                                                                        <input class="TTool_target clock form-control form-control-sm" id="bill_time" name="bill_time" type="text" readonly="" value="">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-6 m-0 p-0">
                                                            <div class="sr-wraper sr-check icheck-primary mt-4 ml-1">
                                                                <input type="checkbox" data-default="true" class="taxable" name="taxable" id="istxchk">
                                                                <label for="istxchk">Taxable</label>
                                                            </div>
                                                            <input type="hidden" name="bls_tax_state" class="bls_tax_state">
                                                        </div>
                                                    </div>


                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="card card-danger mb-0">
                                        <div class="card-header">
                                            <h2 class="card-title text-center">ADD PRODUCTS
                                                <!-- &nbsp;&nbsp; <small>Ctrl+P</small> -->
                                            </h2>
                                        </div>
                                    </div>
                                    <div class="card-body table-responsive p-0 dv-blp-tbl sr-input-movement-container" data-scrlHt="" data-howMany="2">
                                        <?php $this->load->view('bills/add_products') ?>
                                    </div>
                                </div>
                                <!-- 
                                TAB 2:
                                <div class="tab-pane fade" id="custom-tabs-three-profile" role="tabpanel" aria-labelledby="custom-tabs-three-profile-tab"> Mauris erat gravida</div>

                                TAB 3:
                                <div class="tab-pane fade" id="custom-tabs-three-messages" role="tabpanel" aria-labelledby="custom-tabs-three-messages-tab"> Morbi turpis dolor</div>
                                
                                TAB 4:
                                <div class="tab-pane fade" id="custom-tabs-three-settings" role="tabpanel" aria-labelledby="custom-tabs-three-settings-tab"> commodo nibh nec blandit. </div>
                                 -->
                            </div>
                        </div>
                        <!-- /.card -->
                    </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer text-center">
                    <input name="bls_id" id="bls_id" type="hidden">
                    <input name="bls_ref_key" id="bls_ref_key" type="hidden">
                    <!-- .sr-wraper-bold is to highlight element on focus -->
                    <div class="sr-wraper-bold">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i>&nbsp;SAVE</button>
                    </div>
                    <div class="sr-wraper-bold">
                        <div class="sr-reset-btn btn btn-danger"><i class="fas fa-repeat-alt"></i> &nbsp;RESET</div>
                    </div>
                    <div class="o_errors"></div>
                </div>
            </div>

        </div>
    </div>

</form>