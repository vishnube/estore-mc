<form class="sr-form" role="form" id="estr_add_form" onsubmit="$(this).find(':submit').prop('disabled', true)" data-afterFormInit="">
    <div class="row justify-content-md-center mt-2">
        <div class="col-12">
            <div class="card card-primary">
                <div class="card-header">

                    <h3 class="sr-form-tag card-title">
                        <span class="sr-form-icon fa-stack">
                            <i class="fas fa-circle fa-stack-2x text-white"></i>
                            <?= get_add_icon($icon) ?>
                        </span>

                        <!-- Class 'has-default-value' indicates that the element is a non-input element like div, p and having a default html text for form initilize -->
                        <span class="sr-form-title has-default-value" data-default="ADD ESTORE">ADD ESTORE</span>
                    </h3>



                </div>
                <!-- /.card-header -->
                <!-- form start -->


                <div class="card-body">
                    <div class="row">
                        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                            <h3 class="bg-info p-2 text-center" style="font-size: 25px; text-transform: uppercase;">BASIC DETAILS</h3>

                            <div class="sr-wraper">
                                <div class="form-group">
                                    <label class="sr-mdt-lbl">Central Store</label>
                                    <div class="select2-sm">
                                        <select name="estr_fk_central_stores" class="cstr_option form-control form-control-sm select2 select2-danger" data-dropdown-css-class="select2-info" style="width: 100%;">
                                            <?= get_options($cstr_option) ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="sr-wraper">
                                <div class="form-group">
                                    <label class="sr-mdt-lbl">Name</label>
                                    <input type="text" name="mbr_name" id="mbr_name" class="form-control form-control-sm">
                                </div>
                            </div>


                            <div class="sr-wraper">
                                <div class="form-group">
                                    <label class="sr-mdt-lbl">Primary Contact</label>
                                    <input type="text" name="estr_mob1" class="form-control form-control-sm">
                                </div>
                            </div>

                            <div class="sr-wraper">
                                <div class="form-group">
                                    <label class="sr-mdt-lbl">UID</label>
                                    <input type="text" name="estr_uid" class="form-control form-control-sm">
                                </div>
                            </div>

                            <div class="sr-wraper">
                                <div class="form-group">
                                    <label class="sr-mdt-lbl">Category</label>
                                    <select data-default="<?= get_first_option($estr_cat_option) ?>" name="cat_id" class="estr_cat_option sr-no-empty-vals form-control form-control-sm">
                                        <?= get_options($estr_cat_option, '', '', false, true) ?>
                                    </select>
                                </div>
                            </div>



                        </div>
                        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                            <h3 class="bg-info p-2 text-center" style="font-size: 25px; text-transform: uppercase;">Contact Details</h3>


                            <div class="sr-wraper">
                                <div class="form-group">
                                    <label class="">Place</label>
                                    <input type="text" name="estr_place" class="form-control form-control-sm">
                                </div>
                            </div>

                            <div class="sr-wraper">
                                <div class="form-group">
                                    <label class="">Pincode</label>
                                    <input type="text" name="estr_pin" class="form-control form-control-sm">
                                </div>
                            </div>

                            <div class="sr-wraper">
                                <div class="form-group">
                                    <label class="">Secondary Contact</label>
                                    <input type="text" name="estr_mob2" class="form-control form-control-sm">
                                </div>
                            </div>

                            <div class="sr-wraper">
                                <div class="form-group">
                                    <label class="sr-mdt-lbl">Address 1</label>
                                    <textarea name="mbr_address" class="form-control form-control-sm" rows="2"></textarea>
                                </div>
                            </div>
                            <div class="sr-wraper">
                                <div class="form-group">
                                    <label>Address 2</label>
                                    <textarea name="estr_address2" class="form-control form-control-sm" rows="2"></textarea>
                                </div>
                            </div>

                        </div>



                        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                            <h3 class="bg-info p-2 text-center" style="font-size: 25px; text-transform: uppercase;">Relationship Details</h3>

                            <div class="sr-wraper">
                                <div class="form-group">
                                    <label class="">Close Relative Name</label>
                                    <input type="text" name="estr_clsrel" class="form-control form-control-sm">
                                </div>
                            </div>
                            <div class="sr-wraper">
                                <div class="form-group">
                                    <label class="">Close Relative Contact</label>
                                    <input type="text" name="estr_clsrel_mob" class="form-control form-control-sm">
                                </div>
                            </div>
                            <div class="sr-wraper">
                                <div class="form-group">
                                    <label class="">Family</label>
                                    <input type="text" name="estr_fmly" class="form-control form-control-sm">
                                </div>
                            </div>
                            <div class="sr-wraper">
                                <div class="form-group">
                                    <label class="">Family Contact</label>
                                    <input type="text" name="estr_fmly_mob" class="form-control form-control-sm">
                                </div>
                            </div>


                        </div>
                        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                            <h3 class="bg-info p-2 text-center" style="font-size: 25px; text-transform: uppercase;">BANK Details</h3>
                            <div class="sr-wraper">
                                <div class="form-group">
                                    <label class="">Bank Name</label>
                                    <input type="text" name="estr_bank_name" class="form-control form-control-sm">
                                </div>
                            </div>
                            <div class="sr-wraper">
                                <div class="form-group">
                                    <label class="">A/c No:</label>
                                    <input type="text" name="estr_bank_acc" class="form-control form-control-sm">
                                </div>
                            </div>
                            <div class="sr-wraper">
                                <div class="form-group">
                                    <label class="">IFSC</label>
                                    <input type="text" name="estr_ifsc" class="form-control form-control-sm">
                                </div>
                            </div>

                        </div>
                    </div>






















                </div>
                <!-- /.card-body -->

                <div class="card-footer text-center">
                    <input name="mbr_id" type="hidden">
                    <!-- data-default is used to initialize the form.-->
                    <input name="mbr_fk_member_types" type="hidden" data-default="<?= $mbrtp_id ?>">
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