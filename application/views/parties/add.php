<form class="sr-form" role="form" id="prty_add_form" onsubmit="$(this).find(':submit').prop('disabled', true)" data-afterFormInit="afterprtyAddFormReset">
    <div class="row justify-content-md-center mt-2">
        <div class="col col-lg-6">
            <div class="card card-primary">
                <div class="card-header">

                    <h3 class="sr-form-tag card-title">
                        <span class="sr-form-icon fa-stack">
                            <i class="fas fa-circle fa-stack-2x text-white"></i>
                            <?= get_add_icon($icon) ?>
                        </span>

                        <!-- Class 'has-default-value' indicates that the element is a non-input element like div, p and having a default html text for form initilize -->
                        <span class="sr-form-title has-default-value" data-default="ADD PARTY">ADD PARTY</span>
                    </h3>



                </div>
                <!-- /.card-header -->
                <!-- form start -->


                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <!-- .sr-wraper is to highlight element on focus -->
                            <div class="sr-wraper">
                                <div class="form-group">
                                    <!-- '.sr-mdt-lbl' describes the label of a mandatory field -->
                                    <label class="sr-mdt-lbl">Name</label>

                                    <input type="text" name="mbr_name" id="mbr_name" class="form-control form-control-sm" placeholder="">
                                </div>
                            </div>


                            <div class="sr-wraper">
                                <div class="form-group">
                                    <label>Date:</label>

                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                        </div>
                                        <!-- '.sr-date-field-1' is used in initFormInputs() to repopup with format() its value after before_edit -->
                                        <!-- data-default is used to initialize the form.-->
                                        <input type="text" data-default="<?= today() ?>" name="mbr_date" id="mbr_date" class="sr-date-field-1 form-control form-control-sm" data-inputmask-alias="datetime" data-inputmask-inputformat="dd-mm-yyyy" data-mask>
                                    </div>
                                    <!-- /.input group -->
                                </div>
                            </div>

                            <div class="sr-wraper">
                                <div class="form-group">

                                    <!-- '.sr-mdt-lbl' describes the label of a mandatory field -->
                                    <label class="sr-mdt-lbl">Category</label>
                                    <!-- data-default is used to initialize the form.-->
                                    <!-- .sr-no-empty-vals means that this select box don't have <option value=""> -->
                                    <div class="select2-pink select2-sm">
                                        <select data-default="" name="cat_id[]" class="select2 prty_cat_option sr-no-empty-vals form-control form-control-sm" multiple="multiple" data-placeholder="Select a Category" data-dropdown-css-class="select2-pink" style="width: 100%">
                                            <?= get_options($cat_option, '', '', false) ?>
                                        </select>
                                    </div>


                                </div>
                            </div>


                            <div class="sr-wraper">
                                <div class="form-group">
                                    <label>Address 1</label>
                                    <input type="text" name="mbr_address" class="form-control form-control-sm">
                                </div>
                            </div>

                        </div>
                        <div class="col-sm-6">

                            <div class="sr-wraper">
                                <div class="form-group">
                                    <label class="">Address 2</label>
                                    <input type="text" name="prty_address2" class="form-control form-control-sm">
                                </div>
                            </div>

                            <div class="sr-wraper">
                                <div class="form-group">
                                    <label class="">Address 3</label>
                                    <input type="text" name="prty_address3" class="form-control form-control-sm">
                                </div>
                            </div>
                            <div class="sr-wraper">
                                <div class="form-group">
                                    <label>Phone 1</label>
                                    <input type="text" name="prty_mob1" class="form-control form-control-sm">
                                </div>
                            </div>

                            <div class="sr-wraper">
                                <div class="form-group">
                                    <label>Phone 2</label>
                                    <input type="text" name="prty_mob2" class="form-control form-control-sm">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row dv-add-gst">
                        <div class="col-12">
                            <h2 class="bg-danger text-center">ADD GST DETAILS</h2>
                            <table class="table sr-input-movement tbl-add-gst" id="" data-afrNew="">
                                <thead>
                                    <tr>
                                        <th style="width:10px;">#</th>
                                        <th>GST</th>
                                        <th>STATE</th>
                                        <th style="white-space: nowrap;">Is DEFAULT</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <tr class="sr-movement-row">
                                        <td>
                                            <i class="fal fa-times-circle rem cursor-pointer" title="Delete"></i>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <input type="text" name="gst_name[]" class="gst_name next-input enter-lock form-control form-control-sm">
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group">
                                                <select data-default="32" name="gst_fks_states[]" class="gst_fks_states next-input enter-lock form-control form-control-sm">
                                                    <?= get_options(get_GST_state_codes(), '', '', false) ?>
                                                </select>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group">
                                                <select data-default="1" name="gst_default[]" class="gst_default next-input enter-lock last-input form-control form-control-sm">
                                                    <option value="1">Yes</option>
                                                    <option value="2">No</option>
                                                </select>
                                            </div>
                                        </td>

                                    </tr>
                                </tbody>
                            </table>
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