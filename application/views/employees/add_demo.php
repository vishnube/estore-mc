<form class="sr-form" role="form" id="emply_add_form" onsubmit="$(this).find(':submit').prop('disabled', true)">
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
                        <span class="sr-form-title has-default-value" data-default="ADD EMPLOYEE">ADD EMPLOYEE</span>
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
                                    <label class="sr-mdt-lbl">No Init</label>
                                    <!-- '.no-init' elements will be skipped from initialization of Form elements. So it keeps its final status -->
                                    <input type="text" name="input_noInit" id="" class="no-init form-control form-control-sm" placeholder="">
                                </div>
                                <div class="form-group clearfix">
                                    <div class="sr-wraper sr-check icheck-danger d-inline">
                                        <input type="checkbox" class="no-init" checked id="checkbox_noInit">
                                        <label for="checkbox_noInit">No Init checkbox</label>
                                    </div>
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

                        </div>
                        <div class="col-sm-6">
                            <div class="sr-wraper">
                                <div class="form-group">
                                    <label>Address</label>
                                    <textarea name="mbr_address" class="form-control form-control-sm" rows="4" placeholder="Enter ..."></textarea>
                                </div>
                            </div>

                            <div class="sr-wraper">
                                <div class="form-group">
                                    <label>&lt;ENTER&gt; navigation locked</label>
                                    <!-- Use 'enter-lock' Class to lock ENTER key navigation on this element -->
                                    <input type="text" name="enter_lock" class="enter-lock form-control form-control-sm">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">

                            <div class="sr-wraper">
                                <div class="form-group">

                                    <!-- '.sr-mdt-lbl' describes the label of a mandatory field -->
                                    <label class="sr-mdt-lbl">Category</label>
                                    <!-- data-default is used to initialize the form.-->
                                    <!-- .sr-no-empty-vals means that this select box don't have <option value=""> -->
                                    <div class="select2-pink select2-sm">
                                        <select data-default="[3,4]" name="cat_id[]" class="select2 cat_option sr-no-empty-vals form-control form-control-sm" multiple="multiple" data-placeholder="Select a Category" data-dropdown-css-class="select2-pink" style="width: 100%">
                                            <?= get_options($cat_option, '', '', false) ?>
                                        </select>
                                    </div>


                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">

                            <div class="sr-wraper">
                                <div class="form-group">
                                    <label>State</label>
                                    <!-- data-default is used to initialize the form.-->
                                    <!-- .sr-no-empty-vals means that this select box don't have <option value=""> -->
                                    <div class="select2-info select2-sm">
                                        <select data-default="[1,2]" name="state_id[]" id="state_id" class="select2 sr-no-empty-vals form-control form-control-sm" multiple="multiple" data-placeholder="Select a State" data-dropdown-css-class="select2-info" style="width: 100%">
                                            <?php $st = array(1 => 'Kerala', 2 => 'Tamilnadu', 3 => 'Karnataka', 4 => 'Andra', 5 => 'Maharashtra', 6 => 'Teleangana',); ?>
                                            <?= get_options($st, '', '', false) ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group clearfix">
                                <label class="sr-label sr-mdt-lbl">Wage Option</label><br>
                                <!-- .sr-wraper sr-check is to highlight element on focus -->

                                <div class="sr-wraper sr-check icheck-primary d-inline">
                                    <!-- '.sr-mdt-lbl' describes the label of a mandatory field -->
                                    <!-- data-default="true" is used to initialize the form.-->
                                    <input data-default="true" name="salary" type="checkbox" id="checkboxPrimary1">
                                    <label for="checkboxPrimary1">
                                        <label>Salary</label>
                                    </label>
                                </div>

                                <div class="sr-wraper sr-check icheck-primary d-inline">
                                    <input type="checkbox" name="daily" id="checkboxPrimary2">
                                    <label for="checkboxPrimary2">Daily</label>
                                </div>

                                <div class="sr-wraper sr-check icheck-primary d-inline">
                                    <input type="checkbox" name="commission" id="checkboxPrimary3">
                                    <label for="checkboxPrimary3">Commission</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group clearfix">
                                <label class="sr-label sr-mdt-lbl">Status</label><br>
                                <!-- .sr-wraper sr-rad is to highlight element on focus -->
                                <div class="sr-wraper sr-rad icheck-danger d-inline">
                                    <!-- '.sr-mdt-lbl' describes the label of a mandatory field -->
                                    <!-- data-default="true" is used to initialize the form.-->
                                    <input data-default="true" type="radio" value="1" name="emply_status" id="radioDanger1">
                                    <label for="radioDanger1">Active</label>
                                </div>

                                <div class="sr-wraper sr-rad icheck-danger d-inline">
                                    <input type="radio" value="2" name="emply_status" id="radioDanger2">
                                    <label for="radioDanger2">Inactive</label>
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