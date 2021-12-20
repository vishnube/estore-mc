<div class="card card-warning mb-0" style="border-radius: .25rem .25rem 0 0;">
    <div class="card-header">
        <h4 class="card-title">
            <a data-toggle="collapse" href="#settings_key_search_form_container" role="button" aria-expanded="false" aria-controls="settings_key_search_form_container">
                <i class="pr-2 fas fa-search"></i>
                SETTINGS KEYS
                <i class="pl-5 fas fa-chevron-down"></i>
            </a>
        </h4>
        <div class="card-tools">
            <button type="button" title="Add Settings Key" id="add_stky" class="btn btn-tool">
                <i class="fas fa-plus mb-2 text-white" style="font-size: 20px;"></i>
            </button>
        </div>

    </div>
    <div class="sr-collapse collapse" id="settings_key_search_form_container">
        <form class="sr-form" role="form" id="stky_search_form" onsubmit="$(this).find(':submit').prop('disabled', true)">
            <div class="card-body">
                <div class="row">

                    <div class="col-12">
                        <div class="sr-wraper">
                            <div class="form-group">
                                <label>Name</label>
                                <input name="stky_name" type="text" class="form-control form-control-sm">
                            </div>
                        </div>
                    </div>


                    <div class="col-12">
                        <div class="sr-wraper">
                            <div class="form-group">
                                <label>Description</label>
                                <input name="stky_desc" type="text" class="form-control form-control-sm">
                            </div>
                        </div>
                    </div>


                    <div class="col-12">
                        <div class="form-group clearfix">
                            <!-- data-default="true" is used to initialize the form.-->
                            <div class="sr-wraper sr-rad icheck-danger d-inline">
                                <input data-default="true" type="radio" value="1" name="stky_status" id="stkeyrad1">
                                <label for="stkeyrad1">Active</label>
                            </div>
                            <div class="sr-wraper sr-rad icheck-danger d-inline">
                                <input type="radio" value="2" name="stky_status" id="stkeyrad2">
                                <label for="stkeyrad2">Inactive</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer text-center">
                <div class="sr-wraper-bold">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i>&nbsp;SEARCH</button>
                </div>
            </div>
        </form>
    </div>
</div>





<div class="card" style="border-radius: 0px;">
    <div class="card-body">
        <div class="table-responsive" id="tbl_stky_container" style="height: 400px;">
            <table class="table table-head-fixed  text-nowrap  table-hover" id="tbl_stky">
                <thead>
                    <tr>
                        <th class="col-1">#</th>
                        <th class="col-2">Name</th>
                        <th class="col-5">Description</th>
                        <th class="col-3 text-center">Action</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>