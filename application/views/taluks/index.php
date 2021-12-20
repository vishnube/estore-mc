<div class="card card-info mb-0" style="border-radius: .25rem .25rem 0 0;">
    <div class="card-header">
        <h4 class="card-title">
            <a data-toggle="collapse" href="#taluk_search_form_container" role="button" aria-expanded="false" aria-controls="taluk_search_form_container">
                <i class="pr-2 fas fa-search"></i>
                TALUKS
                <i class="pl-5 fas fa-chevron-down"></i>
            </a>
        </h4>
        <div class="card-tools">
            <button type="button" title="Add Taluk" id="add_tlk" class="btn btn-tool">
                <i class="fas fa-plus mb-2 text-white" style="font-size: 20px;"></i>
            </button>
        </div>

    </div>
    <div class="sr-collapse collapse" id="taluk_search_form_container">
        <form class="sr-form" role="form" id="tlk_search_form" onsubmit="$(this).find(':submit').prop('disabled', true)">
            <div class="card-body">
                <div class="row">

                    <div class="col-6 col-md-4">
                        <div class="sr-wraper">
                            <div class="form-group">
                                <label>State</label>
                                <select name="stt_id" class="stt_option form-control form-control-sm">
                                    <?= get_options($stt_option) ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-6 col-md-4">
                        <div class="sr-wraper">
                            <div class="form-group">
                                <label>District</label>
                                <select name="dst_id" class="dst_option form-control form-control-sm">
                                    <?= get_options() ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="form-group clearfix">
                            <label class="sr-label sr-mdt-lbl">Status</label><br>
                            <!-- data-default="true" is used to initialize the form.-->
                            <div class="sr-wraper sr-rad icheck-danger d-inline">
                                <input data-default="true" type="radio" value="1" name="tlk_status" id="tlkstatusactive">
                                <label for="tlkstatusactive">Active</label>
                            </div>
                            <div class="sr-wraper sr-rad icheck-danger d-inline">
                                <input type="radio" value="2" name="tlk_status" id="tlkstatusinactive">
                                <label for="tlkstatusinactive">Inactive</label>
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

        <div class="row">
            <div class="col-12 col-sm-8 clearfix text-nowrap">
                <div class="row">
                    <div class="col-8">
                        <div class="float-left" id="tlk_pagination_msg"></div>
                    </div>
                    <div class="col-4">
                        <div class="float-left dv-perpage" data-callback="onTalukPerpageChanged">
                            <input type="hidden" class="per-page" id="tlk_per_page" value="10">
                            <div class="btn-group">
                                <button type="button" class="btn btn-secondary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Per Page (<span class="spn-per-page">10</span>)
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" data-numRec="">All Records</a>
                                    <a class="dropdown-item active" data-numRec="10">10 Records</a>
                                    <a class="dropdown-item" data-numRec="50">50 Records</a>
                                    <a class="dropdown-item" data-numRec="100">100 Records</a>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="col-12 col-sm-4 sr-pg-link-container">
                <div id="tlk_pagination" class="float-sm-right"></div>
            </div>

        </div>



        <div class="table-responsive" id="tbl_tlk_container" style="height: 650px;">
            <table class="table table-head-fixed  text-nowrap  table-hover" id="tbl_tlk">
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