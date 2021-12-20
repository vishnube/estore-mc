<div class="card card-danger mb-0" style="border-radius: .25rem .25rem 0 0;">
    <div class="card-header">
        <h4 class="card-title">
            <a data-toggle="collapse" href="#company_search_form_container" role="button" aria-expanded="false" aria-controls="company_search_form_container">
                <i class="pr-2 fas fa-search"></i>
                COMPANIES
                <i class="pl-5 fas fa-chevron-down"></i>
            </a>
        </h4>
        <div class="card-tools">
            <button type="button" title="Add Company" id="add_cmp" class="btn btn-tool">
                <i class="fas fa-plus mb-2 text-white" style="font-size: 20px;"></i>
            </button>
        </div>

    </div>
    <div class="sr-collapse collapse" id="company_search_form_container">
        <form class="sr-form" role="form" id="cmp_search_form" onsubmit="$(this).find(':submit').prop('disabled', true)">
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <div class="form-group clearfix">
                            <!-- data-default="true" is used to initialize the form.-->
                            <div class="sr-wraper sr-rad icheck-danger d-inline">
                                <input data-default="true" type="radio" value="1" name="cmp_status" id="cmpstatusactive">
                                <label for="cmpstatusactive">Active</label>
                            </div>
                            <div class="sr-wraper sr-rad icheck-danger d-inline">
                                <input type="radio" value="2" name="cmp_status" id="cmpstatusinactive">
                                <label for="cmpstatusinactive">Inactive</label>
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
        <div class="table-responsive" id="tbl_cmp_container" style="height: 400px;">
            <table class="table table-head-fixed  text-nowrap  table-hover" id="tbl_cmp">
                <thead>
                    <tr>
                        <th class="col-1">#</th>
                        <th class="col-3">Name</th>
                        <th class="col-3">Commission</th>
                        <th class="col-3">Expense</th>
                        <th class="col-2 text-center">Action</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>