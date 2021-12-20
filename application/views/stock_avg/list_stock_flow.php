<div class="row">
    <div class="col-12">
        <div class="card" style="box-shadow: none;">
            <!-- 1px solid rgba(0, 0, 0, 0.125) -->
            <div class="card-header px-lg-2 py-lg-1" style="border:none;border-top:1px solid #eee8e8;border-radius: 0;">
                <div class="row">
                    <div class="col-sm-6 col-lg-4 mt-1 m-lg-0 clearfix text-nowrap">
                        <div class="float-left" id="stkavg_stock_flow_pagination_msg"></div>
                        <div class="float-left dv-perpage" data-callback="onStockFlowPerpageChanged">
                            <input type="hidden" class="per-page" id="stock_flow_per_page" value="10">
                            <div class="btn-group">
                                <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
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

                    <div class="col-sm-6 col-lg-3 mt-1 m-lg-0 clearfix">
                        <div class="btn-group float-lg-left float-sm-right rounded">
                            <button type="button" class="btn bg-gradient-info btn-flat rounded-left export-PDF" data-target="#tbl_stock_flow">
                                <i class="fas fa-file-pdf"></i>&nbsp;
                                PDF
                            </button>
                            <button type="button" class="btn bg-gradient-danger btn-flat export-EXCEL" data-target="#tbl_stock_flow">
                                <i class="fas fa-file-excel"></i>&nbsp;
                                Excel
                            </button>
                            <button type="button" class="btn bg-gradient-success btn-flat rounded-right export-PRINT" data-target="#tbl_stock_flow">
                                <i class="fas fa-print"></i>&nbsp;
                                Print
                            </button>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-2 mt-1 m-lg-0">
                        <div class="input-group float-sm-left">
                            <input type="text" id="stockFlowQuickSearch" data-callback="" data-target="#tbl_stock_flow" class="quick-search form-control float-right" placeholder="Quick Search">
                            <div class="input-group-append">
                                <button class="btn btn-default"><i class="fas fa-search"></i></button>
                            </div>
                        </div>
                    </div>

                    <!-- '.sr-pg-link-container' is used to scroll to "Pagination links" when click on .sr-go-to-tbl -->
                    <div class="col-sm-6 col-lg-3 mt-1 m-lg-0 sr-pg-link-container">
                        <div id="stock_flow_pagination" class="float-sm-right"></div>
                    </div>
                </div>
            </div>

            <!-- '.sr-tbl-cont' is also used to scroll to table when click on .sr-go-to-tbl -->
            <div class="sr-tbl-cont card-body table-responsive p-0" id="tbl_stock_flow_container">
                <table class="sr-tbl-reports table table-head-fixed text-nowrap  table-hover table-striped" id="tbl_stock_flow" data-exportTitle_1="<?= $client['clnt_print_name'] ?>" data-exportTitle_2="STOCK FLOW" data-exportTitle_3="">
                    <thead>
                        <tr>
                            <!-- $('.export').text() will be used for export (Print/PDF/Excel) -->
                            <!-- ".dv-ramp .ramp" is a close button to close the entair column-->
                            <th style="width: 10px">
                                <span class="export" data-exportStyle="text-align: left; color:orange;font-weight:bolder">#</span>
                                <div class="dv-ramp" title="Delete the column"><i class="fa fa-times-circle ramp"></i></div>
                            </th>
                            <th>
                                <span class="export">Date</span>
                                <div class="dv-ramp" title="Delete the column"><i class="fa fa-times-circle ramp"></i></div>
                            </th>
                            <th>
                                <span class="export" data-exportStyle="text-align: center; color:orange;font-weight:bolder">Godown</span>
                                <div class="dv-ramp" title="Delete the column"><i class="fa fa-times-circle ramp"></i></div>
                            </th>
                            <th>
                                <span class="export" data-exportStyle="">Description</span>
                                <div class="dv-ramp" title="Delete the column"><i class="fa fa-times-circle ramp"></i></div>
                            </th>
                            <th>
                                <span class="export" data-exportStyle="">Input</span>
                                <div class="dv-ramp" title="Delete the column"><i class="fa fa-times-circle ramp"></i></div>
                            </th>
                            <th>
                                <span class="export" data-exportStyle="">Output</span>
                                <div class="dv-ramp" title="Delete the column"><i class="fa fa-times-circle ramp"></i></div>
                            </th>
                            <th>
                                <span class="export">Central Store Balance</span>
                                <div class="dv-ramp" title="Delete the column"><i class="fa fa-times-circle ramp"></i></div>
                            </th>
                            <th>
                                <span class="export">Godown Balance</span>
                                <div class="dv-ramp" title="Delete the column"><i class="fa fa-times-circle ramp"></i></div>
                            </th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
            <!-- /.card-body -->
        </div>
    </div>
</div>