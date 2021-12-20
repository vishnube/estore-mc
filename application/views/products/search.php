<div class="row justify-content-md-center mt-2">
    <div class="col-md-12">

        <div class="card card-primary">
            <div class="card-header">
                <h4 class="card-title">
                    <a data-toggle="collapse" href="#product_search_form_container" role="button" aria-expanded="false" aria-controls="product_search_form_container">
                        <i class="pr-2 fas fa-search"></i>
                        SEARCH PRODUCTS
                        <i class="pl-5 fas fa-chevron-down"></i>
                    </a>
                </h4>
                <!-- Handler to scroll to 'data-pagin' OR 'data-table' on click -->
                <i class="sr-go-to-tbl fas fa-angle-double-down fa-2x" title="Move down" data-pagin="#prd_pagination" data-table="#tbl_prd_container"></i>

            </div>
            <div class="sr-collapse collapse" id="product_search_form_container">
                <form class="sr-form" role="form" id="prd_search_form" onsubmit="$(this).find(':submit').prop('disabled', true)" data-afterFormInit="">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 col-sm-4">
                                <div class="sr-wraper">
                                    <div class="form-group">
                                        <label>Name</label>
                                        <input type="text" name="prd_name" class="form-control form-control-sm">
                                    </div>
                                </div>
                                <!-- /.form-group -->
                            </div>



                            <!-- <div class="col-12 col-sm-4">
                                <div class="sr-wraper">
                                    <div class="form-group">
                                        <label>Category</label>
                                        <div class="select2-sm select2-purple">
                                            <select name="pct_id[]" tabindex="2" class="select2 prd_cat_option sr-no-empty-vals form-control form-control-sm" multiple="multiple" data-placeholder="Select a Category" data-dropdown-css-class="select2-purple" style="width: 100%;">
                                                <?php //echo get_options($pct_option, '', '', false) 
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div> -->


                            <div class="col-12 col-sm-4">

                                <div class="form-group clearfix">
                                    <label class="sr-label sr-mdt-lbl">Status</label><br>
                                    <!-- .sr-wraper sr-rad is to highlight element on focus -->
                                    <div class="sr-wraper sr-rad icheck-danger d-inline">
                                        <!-- data-default="true" is used to initialize the form.-->
                                        <input data-default="true" type="radio" value="1" name="prd_status" id="prd_search_status1">
                                        <label for="prd_search_status1">Active</label>
                                    </div>

                                    <div class="sr-wraper sr-rad icheck-danger d-inline">
                                        <input type="radio" value="2" name="prd_status" id="prd_search_status2">
                                        <label for="prd_search_status2">Inactive</label>
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