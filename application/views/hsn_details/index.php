<div class="card card-danger mb-0" style="border-radius: .25rem .25rem 0 0;">
    <div class="card-header">
        <h4 class="card-title">
            <a data-toggle="collapse" href="#hsn_detail_search_form_container" role="button" aria-expanded="false" aria-controls="hsn_detail_search_form_container">
                <i class="pr-2 fas fa-search"></i>
                HSN DETAILS
                <i class="pl-5 fas fa-chevron-down"></i>
            </a>
        </h4>
        <div class="card-tools">
            <button type="button" title="Add Hsn_detail" id="add_hsn" class="btn btn-tool">
                <i class="fas fa-plus mb-2 text-white" style="font-size: 20px;"></i>
            </button>
        </div>

    </div>
    <div class="sr-collapse collapse" id="hsn_detail_search_form_container">
        <form class="sr-form" role="form" id="hsn_search_form" onsubmit="$(this).find(':submit').prop('disabled', true)">
            <div class="card-body">

                <div class="row">
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3">

                        <div class="sr-wraper">
                            <div class="form-group">
                                <label for="recipient-name" class="control-label">HSN CODE:</label>
                                <input name="hsn_name" type="text" class="form-control form-control-sm">
                            </div>
                        </div>

                    </div>
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3">



                        <div class="sr-wraper">
                            <div class="form-group">
                                <label for="recipient-name" class="control-label">Commodity:</label>
                                <input name="hsn_commodity" type="text" class="form-control form-control-sm">
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3">


                        <div class="sr-wraper">
                            <div class="form-group">
                                <label for="recipient-name" class="control-label">Chapter:</label>
                                <input name="hsn_chapter" type="text" class="form-control form-control-sm">
                            </div>
                        </div>

                    </div>

                    <div class="col-12 col-sm-6 col-md-4 col-lg-3">


                        <div class="sr-wraper">
                            <div class="form-group">
                                <label for="recipient-name" class="control-label">HSN CODE (4 Digit):</label>
                                <input name="hsn_name_4_digit" type="text" class="form-control form-control-sm">
                            </div>
                        </div>

                    </div>
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3">

                        <div class="sr-wraper">
                            <div class="form-group">
                                <label for="recipient-name" class="control-label">SCH:</label>
                                <input name="hsn_sch" type="text" class="form-control form-control-sm">
                            </div>
                        </div>

                    </div>
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3">

                        <div class="sr-wraper">
                            <div class="form-group">
                                <label for="recipient-name" class="control-label">GST Rate:</label>
                                <input name="hsn_gst" placeholder="" type="text" class="form-control form-control-sm">
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                        <div class="form-group clearfix">
                            <label class="sr-label">Status</label><br>
                            <div class="sr-wraper sr-rad icheck-danger d-inline">
                                <input data-default="true" type="radio" value="1" name="hsn_status" id="hsn_search_status1">
                                <label for="hsn_search_status1">Active</label>
                            </div>

                            <div class="sr-wraper sr-rad icheck-danger d-inline">
                                <input type="radio" value="2" name="hsn_status" id="hsn_search_status2">
                                <label for="hsn_search_status2">Inactive</label>
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
                        <div class="float-left" id="hsn_pagination_msg"></div>
                    </div>
                    <div class="col-4">
                        <div class="float-left dv-perpage" data-callback="onHsnPerpageChanged">
                            <input type="hidden" class="per-page" id="hsn_per_page" value="10">
                            <div class="btn-group">
                                <button type="button" class="btn btn-secondary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Per Page (<span class="spn-per-page">10</span>)
                                </button>
                                <div class="dropdown-menu">
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
                <div id="hsn_pagination" class="float-sm-right"></div>
            </div>

        </div>



        <div class="table-responsive" id="tbl_hsn_container" style="height: 400px;">
            <table class="table table-head-fixed  text-nowrap  table-hover" id="tbl_hsn">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>HSN Code</th>
                        <th>HSN Code <small>(4 Digits)</small></th>
                        <th>Commodity</th>
                        <th>Chapter</th>
                        <th>SCH</th>
                        <th>GST Rate</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>


                <tbody></tbody>
            </table>
        </div>
    </div>
</div>