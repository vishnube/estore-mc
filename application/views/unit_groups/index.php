<div class="card card-primary mb-0" style="border-radius: .25rem .25rem 0 0;">
    <div class="card-header">
        <h4 class="card-title">
            <a data-toggle="collapse" href="#unit_group_search_form_container" role="button" aria-expanded="false" aria-controls="unit_group_search_form_container">
                <i class="pr-2 fas fa-search"></i>
                UNIT GROUPS
                <i class="pl-5 fas fa-chevron-down"></i>
            </a>
        </h4>
        <div class="card-tools">
            <button type="button" title="Add Unit Group" id="add_ugp" class="btn btn-tool">
                <i class="fas fa-plus mb-2 text-white" style="font-size: 20px;"></i>
            </button>
        </div>

    </div>
    <div class="sr-collapse collapse" id="unit_group_search_form_container">
        <form class="sr-form" role="form" id="ugp_search_form" onsubmit="$(this).find(':submit').prop('disabled', true)">
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-sm-6 col-lg-4">
                        <div class="form-group clearfix">
                            <!-- data-default="true" is used to initialize the form.-->
                            <div class="sr-wraper sr-rad icheck-danger d-inline">
                                <input data-default="true" type="radio" value="1" name="ugp_status" id="ugpstatusactive">
                                <label for="ugpstatusactive">Active</label>
                            </div>
                            <div class="sr-wraper sr-rad icheck-danger d-inline">
                                <input type="radio" value="2" name="ugp_status" id="ugpstatusinactive">
                                <label for="ugpstatusinactive">Inactive</label>
                            </div>
                        </div>
                    </div>


                    <div class="col-12 col-sm-6 col-lg-4">
                        <div class="sr-wraper">
                            <div class="form-group">
                                <label>Group Name</label>
                                <input type="text" name="ugp_name" class="form-control form-control-sm">
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
        <div class="table-responsive" id="tbl_ugp_container" style="height: 400px;">
            <table class="table table-head-fixed  text-nowrap  table-hover" id="tbl_ugp">
                <thead>
                    <tr>
                        <th class="col-1" title="Group No">Grp No:</th>
                        <th class="col-4">Group Name</th>
                        <th class="col-4">Units</th>
                        <th class="col-3 text-center">Action</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>