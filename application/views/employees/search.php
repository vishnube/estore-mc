<div class="row justify-content-md-center mt-2">
    <div class="col-md-12">

        <div class="card card-primary">
            <div class="card-header">
                <h4 class="card-title">
                    <a data-toggle="collapse" href="#employee_search_form_container" role="button" aria-expanded="false" aria-controls="employee_search_form_container">
                        <i class="pr-2 fas fa-search"></i>
                        SEARCH EMPLOYEES
                        <i class="pl-5 fas fa-chevron-down"></i>
                    </a>
                </h4>
                <!-- Handler to scroll to 'data-pagin' OR 'data-table' on click -->
                <i class="sr-go-to-tbl fas fa-angle-double-down fa-2x" title="Move down" data-pagin="#emply_pagination" data-table="#tbl_emply_container"></i>

            </div>
            <div class="sr-collapse collapse" id="employee_search_form_container">
                <form class="sr-form" role="form" id="emply_search_form" onsubmit="$(this).find(':submit').prop('disabled', true)" data-afterFormInit="">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 col-sm-4">
                                <div class="sr-wraper">
                                    <div class="form-group">
                                        <label>Name</label>
                                        <div class="select2-sm">
                                            <select name="mbr_id" class="emply_option form-control form-control-sm select2 select2-danger" data-dropdown-css-class="select2-danger" style="width: 100%;">
                                                <?= get_options($mbr_option) ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.form-group -->
                            </div>
                            <!-- /.col -->
                            <div class="col-12 col-sm-4">
                                <div class="sr-wraper">
                                    <div class="form-group">
                                        <label>Category</label>
                                        <div class="select2-sm select2-purple">
                                            <!-- .sr-no-empty-vals means that this select box don't have <option value=""> -->
                                            <select name="cat_id[]" class="select2 emply_cat_option sr-no-empty-vals form-control form-control-sm" multiple="multiple" data-placeholder="Select a Category" data-dropdown-css-class="select2-purple" style="width: 100%;">
                                                <?= get_options($cat_option, '', '', false) ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.form-group -->
                            </div>
                            <!-- /.col -->
                            <div class="col-12 col-sm-4">

                                <div class="form-group clearfix">
                                    <label class="sr-label sr-mdt-lbl">Status</label><br>
                                    <!-- .sr-wraper sr-rad is to highlight element on focus -->
                                    <div class="sr-wraper sr-rad icheck-danger d-inline">
                                        <!-- data-default="true" is used to initialize the form.-->
                                        <input data-default="true" type="radio" value="1" name="mbr_status" id="emply_search_status1">
                                        <label for="emply_search_status1">Active</label>
                                    </div>

                                    <div class="sr-wraper sr-rad icheck-danger d-inline">
                                        <input type="radio" value="2" name="mbr_status" id="emply_search_status2">
                                        <label for="emply_search_status2">Inactive</label>
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