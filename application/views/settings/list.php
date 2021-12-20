<div class="row">
    <div class="col-12">
        <div class="card" style="box-shadow: none;">
            <!-- 1px solid rgba(0, 0, 0, 0.125) -->
            <div class="card-header px-lg-2 py-lg-1" style="border:none;border-top:1px solid #eee8e8;border-radius: 0;">
                <div class="row">
                    <div class="col-sm-6 col-lg-4 mt-1 m-lg-0 text-center" id="st_pagination_msg"></div>




                    <div class="col-sm-6 col-lg-3 mt-1 m-lg-0 clearfix">
                        <div class="btn-group float-lg-left float-sm-right rounded">
                            <button type="button" class="btn bg-gradient-info btn-flat rounded-left export-PDF" data-target="#tbl_st">
                                <i class="fas fa-file-pdf"></i>&nbsp;
                                PDF
                            </button>
                            <button type="button" class="btn bg-gradient-danger btn-flat export-EXCEL" data-target="#tbl_st">
                                <i class="fas fa-file-excel"></i>&nbsp;
                                Excel
                            </button>
                            <button type="button" class="btn bg-gradient-success btn-flat rounded-right export-PRINT" data-target="#tbl_st">
                                <i class="fas fa-print"></i>&nbsp;
                                Print
                            </button>
                        </div>
                    </div>

                    <div class="col-sm-6 col-lg-2 mt-1 m-lg-0">
                        <div class="input-group float-sm-left">
                            <input type="text" id="settingsQuickSearch" data-callback="" data-target="#tbl_st" class="quick-search form-control float-right" placeholder="Quick Search">
                            <div class="input-group-append">
                                <button class="btn btn-default"><i class="fas fa-search"></i></button>
                            </div>
                        </div>
                    </div>



                    <!-- '.sr-pg-link-container' is used to scroll to "Pagination links" when click on .sr-go-to-tbl -->
                    <div class="col-sm-6 col-lg-3 mt-1 m-lg-0 sr-pg-link-container">
                        <div id="st_pagination" class="float-sm-right"></div>
                    </div>
                </div>
            </div>

            <!-- '.sr-tbl-cont' is also used to scroll to table when click on .sr-go-to-tbl -->
            <div class="sr-tbl-cont card-body table-responsive p-0" id="tbl_st_container">
                <table class="sr-tbl-reports table table-head-fixed text-nowrap  table-hover table-striped" id="tbl_st" data-exportTitle_1="<?= $client['clnt_print_name'] ?>" data-exportTitle_2="SETTINGS LIST" data-exportTitle_3="List of settings">
                    <thead>

                        <!-- $('.export').text() will be used for export (Print/PDF/Excel) -->
                        <!-- ".dv-ramp .ramp" is a close button to close the entair column-->
                        <tr>
                            <th style="width: 10px">
                                <span class="export" data-exportStyle="text-align: left;">#</span>
                                <div class="dv-ramp" title="Delete the column"><i class="fa fa-times-circle ramp"></i></div>
                            </th>
                            <th>
                                <span class="export" data-exportStyle="">Ref-Table</span>
                                <div class="dv-ramp" title="Delete the column"><i class="fa fa-times-circle ramp"></i></div>
                            </th>
                            <th>
                                <span class="export" data-exportStyle="">Category</span>
                                <div class="dv-ramp" title="Delete the column"><i class="fa fa-times-circle ramp"></i></div>
                            </th>
                            <th>
                                <span class="export" data-exportStyle="">Key</span>
                                <div class="dv-ramp" title="Delete the column"><i class="fa fa-times-circle ramp"></i></div>
                            </th>
                            <th>
                                <span class="export" data-exportStyle="">Name</span>
                                <div class="dv-ramp" title="Delete the column"><i class="fa fa-times-circle ramp"></i></div>
                            </th>
                            <th>
                                <span class="export" data-exportStyle="">Value</span>
                                <div class="dv-ramp" title="Delete the column"><i class="fa fa-times-circle ramp"></i></div>
                            </th>
                            <th>
                                <span>Possible/Default Values</span>
                                <div class="dv-ramp" title="Delete the column"><i class="fa fa-times-circle ramp"></i></div>
                            </th>
                            <th>
                                <span>User Type</span>
                                <div class="dv-ramp" title="Delete the column"><i class="fa fa-times-circle ramp"></i></div>
                            </th>
                            <th>
                                <span>Def-User Type</span>
                                <div class="dv-ramp" title="Delete the column"><i class="fa fa-times-circle ramp"></i></div>
                            </th>
                            <!-- <th>Version</th> -->
                            <th>
                                <span>Sort</span>
                                <div class="dv-ramp" title="Delete the column"><i class="fa fa-times-circle ramp"></i></div>
                            </th>
                            <th class="text-center" style="width: 200px">Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>

                </table>
            </div>
            <!-- /.card-body -->
        </div>
    </div>
</div>