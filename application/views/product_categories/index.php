<div class="card card-danger mb-0" style="border-radius: .25rem .25rem 0 0;">
    <div class="card-header">
        <h4 class="card-title">
            <a data-toggle="collapse" href="#product_category_search_form_container" role="button" aria-expanded="false" aria-controls="product_category_search_form_container">
                <i class="pr-2 fas fa-search"></i>
                PRODUCT CATEGORIES
                <i class="pl-5 fas fa-chevron-down"></i>
            </a>
        </h4>
        <div class="card-tools">
            <button type="button" title="Add Category" id="add_pct" class="btn btn-tool">
                <i class="fas fa-plus mb-2 text-white" style="font-size: 20px;"></i>
            </button>
        </div>

    </div>
    <div class="sr-collapse collapse" id="product_category_search_form_container">
        <form class="sr-form" role="form" id="pct_search_form" onsubmit="$(this).find(':submit').prop('disabled', true)" data-afterFormInit="reset_pct">
            <div class="card-body">
                <div class="row">


                    <div class="col-12 col-md-6">
                        <div class="sr-wraper">
                            <div class="form-group">
                                <label>Name:</label>
                                <input name="pct_name" type="text" class="form-control form-control-sm">
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="form-group clearfix">
                            <!-- data-default="true" is used to initialize the form.-->
                            <div class="sr-wraper sr-rad icheck-danger d-inline">
                                <input data-default="true" type="radio" value="1" name="pct_status" id="pctstatusactive">
                                <label for="pctstatusactive">Active</label>
                            </div>
                            <div class="sr-wraper sr-rad icheck-danger d-inline">
                                <input type="radio" value="2" name="pct_status" id="pctstatusinactive">
                                <label for="pctstatusinactive">Inactive</label>
                            </div>
                        </div>
                    </div>


                    <div class="col-12">
                        <div class="parent-dv">
                            <div class="sr-wraper">
                                <div class="form-group">
                                    <label>Category Head</label>
                                    <div class="input-group mb-3 input-group-sm form-group">
                                        <div class="input-group-prepend">
                                            <button type="button" class="btn btn-info reset-pctopt">Reset</button>
                                        </div>
                                        <select class="pct_parent_selector pct_option form-control form-control-sm">
                                            <?= get_options($pct_option, '', 'Select Category', true, false, 'No Categories') ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="parents m-1 p-1"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer text-center">
                <div class="sr-wraper-bold"><button type="submit" class="btn btn-primary">SEARCH</button></div>
            </div>
        </form>
    </div>
</div>





<div class="card" style="border-radius: 0px;">
    <div class="card-body">
        <div class="table-responsive" id="tbl_pct_container" style="height: 400px;">
            <table class="table table-head-fixed  text-nowrap  table-hover" id="tbl_pct">
                <thead>
                    <tr>
                        <th class="col-1">#</th>
                        <th class="col-4">Name</th>
                        <th class="col-4">Parent</th>
                        <th class="col-3 text-center">Action</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>