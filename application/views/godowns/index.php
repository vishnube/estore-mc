<div class="card card-success mb-0" style="border-radius: .25rem .25rem 0 0;">
    <div class="card-header">
        <h4 class="card-title">
            <a data-toggle="collapse" href="#godown_search_form_container" role="button" aria-expanded="false" aria-controls="godown_search_form_container">
                <i class="pr-2 fas fa-search"></i>
                GODOWNS
                <i class="pl-5 fas fa-chevron-down"></i>
            </a>
        </h4>
        <div class="card-tools">
            <button type="button" title="Add Godown" id="add_gdn" class="btn btn-tool">
                <i class="fas fa-plus mb-2 text-white" style="font-size: 20px;"></i>
            </button>
        </div>

    </div>
    <div class="sr-collapse collapse" id="godown_search_form_container">
        <form class="sr-form" role="form" id="gdn_search_form">
            <div class="card-body">
                <div class="row">

                    <div class="col-6">
                        <div class="sr-wraper">
                            <div class="form-group">
                                <label>Central Store</label>
                                <div class="select2-sm">
                                    <select name="gdn_fk_central_stores" id="gdn_fk_central_stores" class="cstr_option form-control form-control-sm select2 select2-danger" data-dropdown-css-class="select2-info" style="width: 100%;">
                                        <?= get_options($cstr_option) ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group clearfix">
                            <label class="sr-label sr-mdt-lbl">Status</label><br>
                            <!-- .sr-wraper sr-rad is to highlight element on focus -->
                            <div class="sr-wraper sr-rad icheck-danger d-inline">
                                <!-- data-default="true" is used to initialize the form.-->
                                <input data-default="true" type="radio" value="1" name="gdn_status" id="gdn_search_status1">
                                <label for="gdn_search_status1">Active</label>
                            </div>

                            <div class="sr-wraper sr-rad icheck-danger d-inline">
                                <input type="radio" value="2" name="gdn_status" id="gdn_search_status2">
                                <label for="gdn_search_status2">Inactive</label>
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
        <div class="table-responsive" id="tbl_gdn_container" style="height: 400px;">
            <table class="table table-head-fixed  text-nowrap  table-hover" id="tbl_gdn">
                <thead>
                    <tr>
                        <th class="col-1">#</th>
                        <th class="col-4">Central Store</th>
                        <th class="col-4">Godown Name</th>
                        <th class="col-3 text-center">Action</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>