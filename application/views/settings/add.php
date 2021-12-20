<form class="sr-form" role="form" id="st_add_form" onsubmit="$(this).find(':submit').prop('disabled', true)">
    <div class="row justify-content-md-center mt-2">
        <div class="col col-lg-10">
            <div class="card card-primary">
                <div class="card-header">

                    <h3 class="sr-form-tag card-title">
                        <span class="sr-form-icon fa-stack">
                            <i class="fas fa-circle fa-stack-2x text-white"></i>
                            <?= get_add_icon($icon) ?>
                        </span>

                        <!-- Class 'has-default-value' indicates that the element is a non-input element like div, p and having a default html text for form initilize -->
                        <span class="sr-form-title has-default-value" data-default="ADD SETTINGS">ADD SETTINGS</span>
                    </h3>



                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <div class="card-body">

                    <div class="row m-0 p-0">
                        <div class="col-sm-4">

                            <div class="sr-wraper">
                                <div class="form-group">

                                    <!-- '.sr-mdt-lbl' describes the label of a mandatory field -->
                                    <label class="sr-mdt-lbl">Ref Table</label>
                                    <!-- .sr-no-empty-vals means that this select box don't have <option value=""> -->
                                    <div class="select2-pink select2-sm">
                                        <select data-default="" name="st_ref_tbl[]" class="select2 sr-no-empty-vals form-control form-control-sm" multiple="multiple" data-placeholder="Reference Table" data-dropdown-css-class="select2-pink" style="width: 100%">
                                            <?= get_options($ref_tables, '', '', false) ?>
                                        </select>
                                    </div>
                                </div>
                            </div>




                            <div class="sr-wraper">
                                <div class="form-group">
                                    <label class="sr-mdt-lbl">Category</label>
                                    <select name="st_fk_settings_categories" class="stct_option form-control form-control-sm" data-default="1">
                                        <?= get_options($stct_option) ?>
                                    </select>
                                </div>
                            </div>
                            <div class="sr-wraper">
                                <div class="form-group">
                                    <label class="sr-mdt-lbl">Key</label>
                                    <select name="st_fk_settings_keys" class="stky_option form-control form-control-sm">
                                        <?= get_options($stky_option) ?>
                                    </select>
                                </div>
                            </div>
                            <div class="sr-wraper">
                                <div class="form-group">
                                    <!-- '.sr-mdt-lbl' describes the label of a mandatory field -->
                                    <label class="sr-mdt-lbl">Name</label>
                                    <input type="text" name="st_name" id="st_name" class="form-control form-control-sm" placeholder="">
                                </div>
                            </div>
                            <div class="sr-wraper">
                                <div class="form-group">
                                    <label>Sort Position</label>
                                    <input type="text" name="st_sort" id="st_sort" class="form-control form-control-sm" placeholder="">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="sr-wraper">
                                <div class="form-group">
                                    <!-- '.sr-mdt-lbl' describes the label of a mandatory field -->
                                    <label class="sr-mdt-lbl">Input Type</label>
                                    <select name="st_input" id="st_input" class="form-control form-control-sm" data-default="1">
                                        <?= get_options($input_types, '', '', false) ?>
                                    </select>
                                </div>
                            </div>
                            <div class="pvalblock" style="display: none;">
                                <h3>Possible Values</h3>
                                <div id="dv-pval-range">
                                    <span style='color: #0877c2;padding: 0px 8px;font-family:"RobotoCondensed-Italic";font-size: 14px;font-weight: lighter;display:inline-block'>Range</span>
                                    <div class="d-inline">
                                        <input type="text" class="intOnly" style="width: 50px;height:25px" id="rangeFrom">
                                        <input type="text" class="intOnly" style="width: 50px;height:25px" id="rangeTo">
                                        <span class="badge badge-info cursor-pointer" id="go">Go</span>
                                    </div>
                                </div>

                                <div class="sr-wraper sr-input-movement" data-radFormat="true">
                                    <div class="row sr-movement-row p-0 m-0">
                                        <div class="col col-xs-6 pl-4" style="position: relative;">
                                            <i class="fal fa-times-circle rem cursor-pointer" style="position:absolute;top:10px;left:0px;" title="Delete"></i>

                                            <div class="form-group">
                                                <input type="text" name="st_pval[key][]" class="next-input enter-lock key form-control form-control-sm" placeholder="Key">
                                            </div>
                                        </div>
                                        <div class="col col-xs-6 pl-2">
                                            <div class="form-group">
                                                <input type="text" name="st_pval[val][]" class="next-input last-input val enter-lock form-control form-control-sm" placeholder="Value">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="sr-wraper">
                                <div class="form-group">
                                    <!-- '.sr-mdt-lbl' describes the label of a mandatory field -->
                                    <label class="sr-mdt-lbl">Default Value</label>
                                    <input type="text" name="st_dval" id="st_dval" class="form-control form-control-sm" placeholder="">
                                </div>
                            </div>

                        </div>
                        <div class="col-sm-4">
                            <div class="sr-wraper">
                                <div class="form-group">
                                    <label>Validation</label>
                                    <input type="text" name="st_validation" id="st_validation" class="form-control form-control-sm" data-default="required">
                                </div>
                            </div>

                            <div class="sr-wraper">
                                <div class="form-group">
                                    <label>Description</label>
                                    <textarea name="st_desc" id="st_desc" class="form-control form-control-sm" rows="3" placeholder="Enter ..."></textarea>
                                </div>
                            </div>
                            <div class="form-group clearfix">
                                <label class="sr-label sr-mdt-lbl">Default User Type</label><br>
                                <!-- .sr-wraper sr-rad is to highlight element on focus -->
                                <div class="sr-wraper sr-rad icheck-danger d-inline">
                                    <!-- '.sr-mdt-lbl' describes the label of a mandatory field -->
                                    <!-- data-default="true" is used to initialize the form.-->
                                    <input data-default="true" type="radio" value="1" name="st_dusertype" id="st_dusertype1">
                                    <label for="st_dusertype1">All Users</label>
                                </div>

                                <div class="sr-wraper sr-rad icheck-danger d-inline">
                                    <input type="radio" value="2" name="st_dusertype" id="st_dusertype2">
                                    <label for="st_dusertype2">Admins Only</label>
                                </div>

                                <div class="sr-wraper sr-rad icheck-danger d-inline">
                                    <input type="radio" value="3" name="st_dusertype" id="st_dusertype3">
                                    <label for="st_dusertype3">Developer Only</label>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer text-center">
                    <input name="st_id" type="hidden">
                    <!-- .sr-wraper-bold is to highlight element on focus -->
                    <div class="sr-wraper-bold">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i>&nbsp;SAVE</button>
                    </div>
                    <div class="sr-wraper-bold">
                        <div class="sr-reset-btn btn btn-danger"><i class="fas fa-repeat-alt"></i> &nbsp;RESET</div>
                    </div>
                    <div class="o_errors"></div>

                    <!-- The query will be shown here -->
                    <textarea class="mt-2 query"></textarea>
                </div>
            </div>

        </div>
    </div>

</form>