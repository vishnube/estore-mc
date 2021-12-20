<div class="card card-success mb-0" style="border-radius: .25rem .25rem 0 0;">
    <div class="card-header">
        <h4 class="card-title">
            <a data-toggle="collapse" href="#district_search_form_container" role="button" aria-expanded="false" aria-controls="district_search_form_container">
                <i class="pr-2 fas fa-search"></i>
                DISTRICTS
                <i class="pl-5 fas fa-chevron-down"></i>
            </a>
        </h4>
        <div class="card-tools">
            <button type="button" title="Add District" id="add_dst" class="btn btn-tool">
                <i class="fas fa-plus mb-2 text-white" style="font-size: 20px;"></i>
            </button>
        </div>

    </div>
    <div class="sr-collapse collapse" id="district_search_form_container">
        <form class="sr-form" role="form" id="dst_search_form" onsubmit="$(this).find(':submit').prop('disabled', true)">
            <div class="card-body">
                <div class="row">

                    <div class="col-6">
                        <div class="sr-wraper">
                            <div class="form-group">
                                <label>State</label>
                                <select name="stt_id" class="stt_option form-control form-control-sm">
                                    <?= get_options($stt_option) ?>
                                </select>
                            </div>
                        </div>
                    </div>


                    <div class="col-6">
                        <div class="form-group clearfix">
                            <!-- data-default="true" is used to initialize the form.-->
                            <div class="sr-wraper sr-rad icheck-danger d-inline">
                                <input data-default="true" type="radio" value="1" name="dst_status" id="dststatusactive">
                                <label for="dststatusactive">Active</label>
                            </div>
                            <div class="sr-wraper sr-rad icheck-danger d-inline">
                                <input type="radio" value="2" name="dst_status" id="dststatusinactive">
                                <label for="dststatusinactive">Inactive</label>
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

        <div class="clearer">
            <div class="clearer text-nowrap">
                <div class="float-left">
                    <div id="dst_pagination_msg"></div>
                </div>
                <div class="float-right">
                    <div class="dv-perpage" data-callback="onDistrictPerpageChanged">
                        <input type="hidden" class="per-page" id="dst_per_page" value="10">
                        <div class="btn-group">
                            <button type="button" class="btn btn-secondary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Per Page (<span class="spn-per-page">10</span>)
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" data-numRec="">All Records</a>
                                <a class="dropdown-item" data-numRec="2">2 Records</a>
                                <a class="dropdown-item active" data-numRec="10">10 Records</a>
                                <a class="dropdown-item" data-numRec="50">50 Records</a>
                                <a class="dropdown-item" data-numRec="100">100 Records</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="clearer mx-auto sr-pg-link-container" style="width:50%">
                <div id="dst_pagination" class=""></div>
            </div>

        </div>



        <div class="table-responsive" id="tbl_dst_container" style="height: 324px;">
            <table class="table table-head-fixed  text-nowrap  table-hover" id="tbl_dst">
                <thead>
                    <tr>
                        <th class="col-1">#</th>
                        <th class="col-8">Name</th>
                        <th class="col-3 text-center">Action</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>