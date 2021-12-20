<div class="row">
    <div class="col-12">
        <div class="card" style="box-shadow: none;">
            <!-- 1px solid rgba(0, 0, 0, 0.125) -->
            <div class="card-header px-lg-2 py-lg-1" style="border:none;border-top:1px solid #eee8e8;border-radius: 0;">
                <div class="row">
                    <div class="col-sm-6 col-lg-4 mt-1 m-lg-0 clearfix text-nowrap">
                        <div class="float-left" id="bls_pagination_msg"></div>
                        <div class="float-left dv-perpage" data-callback="onBillsPerpageChanged">
                            <input type="hidden" class="per-page" id="bls_per_page" value="10">
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

                    <div class="col-sm-6 col-lg-3 mt-1 m-lg-0 clearfix bls-export-container">


                        <!-- To export you need dependencies\js\html_table_export.js -->
                        <div class="btn-group float-lg-left float-sm-right rounded">
                            <button type="button" class="btn bg-gradient-danger btn-flat rounded-left export-EXCEL-2" data-target=".dv-data-table.active .print-table-excel">
                                <i class="fas fa-file-excel"></i>&nbsp;
                                Excel
                            </button>
                            <button type="button" class="btn bg-gradient-success btn-flat rounded-right export-PRINT-2" data-target=".dv-data-table.active .print-table">
                                <i class="fas fa-print"></i>&nbsp;
                                Print
                            </button>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-2 mt-1 m-lg-0">
                        <div class="input-group float-sm-left">
                            <input type="text" id="billQuickSearch" data-callback="" data-target="#tbl_bls" class="quick-search form-control float-right" placeholder="Quick Search">
                            <div class="input-group-append">
                                <button class="btn btn-default"><i class="fas fa-search"></i></button>
                            </div>
                        </div>
                    </div>

                    <!-- '.sr-pg-link-container' is used to scroll to "Pagination links" when click on .sr-go-to-tbl -->
                    <div class="col-sm-6 col-lg-3 mt-1 m-lg-0 sr-pg-link-container">
                        <div id="bls_pagination" class="float-sm-right"></div>
                    </div>
                </div>
            </div>

            <div class="card-body" style="background-color: #343a40;">
                <!--  '.dv-tbl-port' is also used to scroll to table when click on .sr-go-to-tbl   -->
                <div class="dv-tbl-port">
                    <div id="dv-Grid" class="dv-data-table" style="display: none;"><?php $this->load->view('bills/list_display_grid') ?></div>
                    <div id="dv-A4" class="dv-data-table active"><?php $this->load->view('bills/list_display_a4');  ?></div>
                    <div id="dv-Tile" class="dv-data-table" style="display: none;"></div>
                </div>
            </div>


        </div>
    </div>
</div>