<div class="row justify-content-md-center mt-2">
    <div class="col-md-12">

        <div class="card card-primary">
            <div class="card-header">
                <h4 class="card-title">
                    <a data-toggle="collapse" href="#settings_search_form_container" role="button" aria-expanded="false" aria-controls="settings_search_form_container">
                        <i class="pr-2 fas fa-search"></i>
                        SEARCH SETTINGS
                        <i class="pl-5 fas fa-chevron-down"></i>
                    </a>
                </h4>
                <!-- Handler to scroll to 'data-pagin' OR 'data-table' on click -->
                <i class="sr-go-to-tbl fas fa-angle-double-down fa-2x" title="Move down" data-pagin="#st_pagination" data-table=".sr-tbl-cont"></i>

            </div>
            <div class="sr-collapse collapse" id="settings_search_form_container">
                <form role="form" id="st_search_form" onsubmit="$(this).find(':submit').prop('disabled', true)">
                    <div class="card-body">


                        <div class="row">
                            <div class="col-12 col-sm-3">
                                <div class="sr-wraper">
                                    <div class="form-group">
                                        <label>Reference Table</label>
                                        <div class="select2-sm">
                                            <select name="st_ref_tbl" class="form-control form-control-sm select2 select2-danger" data-dropdown-css-class="select2-success" style="width: 100%;">
                                                <?= get_options($ref_tables) ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.form-group -->
                            </div>


                            <div class="col-12 col-sm-3">
                                <div class="sr-wraper">
                                    <div class="form-group">
                                        <label>Settings</label>
                                        <div class="select2-sm">
                                            <select name="st_id" class="st_option form-control form-control-sm select2 select2-danger" data-dropdown-css-class="select2-danger" style="width: 100%;">
                                                <?= get_options($st_option) ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.form-group -->
                            </div>
                            <!-- /.col -->
                            <div class="col-12 col-sm-3">
                                <div class="sr-wraper">
                                    <div class="form-group">
                                        <label>Category</label>
                                        <div class="select2-sm select2-purple">
                                            <!-- .sr-no-empty-vals means that this select box don't have <option value=""> -->
                                            <select name="stct_id[]" tabindex="2" class="select2 stct_option sr-no-empty-vals form-control form-control-sm" multiple="multiple" data-placeholder="Select a Category" data-dropdown-css-class="select2-purple" style="width: 100%;">
                                                <?= get_options($stct_option, '', '', false) ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.form-group -->
                            </div>
                            <!-- /.col -->
                            <div class="col-12 col-sm-3">

                                <div class="form-group clearfix">
                                    <label class="sr-label">Status</label><br>
                                    <!-- .sr-wraper sr-rad is to highlight element on focus -->
                                    <div class="sr-wraper sr-rad icheck-success d-inline">
                                        <!-- data-default="true" is used to initialize the form.-->
                                        <input data-default="true" type="radio" value="1" name="st_status" id="st_search_status1">
                                        <label for="st_search_status1">Active</label>
                                    </div>

                                    <div class="sr-wraper sr-rad icheck-danger d-inline">
                                        <input type="radio" value="2" name="st_status" id="st_search_status2">
                                        <label for="st_search_status2">Inactive</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 col-sm-4">
                                <div class="form-group clearfix">
                                    <label class="sr-label">User Type</label><br>
                                    <!-- .sr-wraper sr-rad is to highlight element on focus -->
                                    <div class="sr-wraper sr-rad icheck-warning d-inline">
                                        <!-- data-default="true" is used to initialize the form.-->
                                        <input data-default="true" type="radio" value="" name="cst_usertype" id="utp0">
                                        <label for="utp0">Any</label>
                                    </div>

                                    <div class="sr-wraper sr-rad icheck-success d-inline">
                                        <input type="radio" value="1" name="cst_usertype" id="utp1">
                                        <label for="utp1">For All</label>
                                    </div>

                                    <div class="sr-wraper sr-rad icheck-danger d-inline">
                                        <input type="radio" value="2" name="cst_usertype" id="utp2">
                                        <label for="utp2">Admins Only</label>
                                    </div>

                                    <div class="sr-wraper sr-rad icheck-info d-inline">
                                        <input type="radio" value="3" name="cst_usertype" id="utp3">
                                        <label for="utp3">Developer Only</label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-sm-4">
                                <div class="form-group clearfix">
                                    <label class="sr-label">Default User Type</label><br>
                                    <!-- .sr-wraper sr-rad is to highlight element on focus -->
                                    <div class="sr-wraper sr-rad icheck-warning d-inline">
                                        <!-- data-default="true" is used to initialize the form.-->
                                        <input data-default="true" type="radio" value="" name="st_dusertype" id="dutp0">
                                        <label for="dutp0">Any</label>
                                    </div>

                                    <div class="sr-wraper sr-rad icheck-success d-inline">
                                        <input type="radio" value="1" name="st_dusertype" id="dutp1">
                                        <label for="dutp1">For All</label>
                                    </div>

                                    <div class="sr-wraper sr-rad icheck-danger d-inline">
                                        <input type="radio" value="2" name="st_dusertype" id="dutp2">
                                        <label for="dutp2">Admins Only</label>
                                    </div>

                                    <div class="sr-wraper sr-rad icheck-info d-inline">
                                        <input type="radio" value="3" name="st_dusertype" id="dutp3">
                                        <label for="dutp3">Developer Only</label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-sm-4">
                                <div class="sr-wraper">
                                    <div class="form-group">
                                        <label>Key</label>
                                        <div class="select2-sm select2-info">
                                            <!-- .sr-no-empty-vals means that this select box don't have <option value=""> -->
                                            <select name="stky_id[]" tabindex="2" class="select2 stky_option sr-no-empty-vals form-control form-control-sm" multiple="multiple" data-placeholder="Select a Key" data-dropdown-css-class="select2-info" style="width: 100%;">
                                                <?= get_options($stky_option, '', '', false) ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.form-group -->
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