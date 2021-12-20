<div class="row">
    <div class="col-12">
        <div class="card" style="box-shadow: none;">
            <!-- 1px solid rgba(0, 0, 0, 0.125) -->
            <div class="card-header px-lg-2 py-lg-1" style="border:none;border-top:1px solid #eee8e8;border-radius: 0;">
                <div class="row">
                    <div class="col-sm-6 col-lg-4 mt-1 m-lg-0 clearfix text-nowrap">
                        <div class="float-left" id="odr_pagination_msg"></div>
                        <div class="float-left dv-perpage" data-callback="onOrderPerpageChanged">
                            <input type="hidden" class="per-page" id="odr_per_page" value="10">
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
                            <?php
                            if ($tasks['tsk_odr_pdf']) {
                            ?>
                                <button type="button" class="btn bg-gradient-info btn-flat rounded-left export-PDF" data-target="#tbl_odr">
                                    <i class="fas fa-file-pdf"></i>&nbsp;
                                    PDF
                                </button>
                            <?php
                            }

                            if ($tasks['tsk_odr_excel']) {
                            ?>
                                <button type="button" class="btn bg-gradient-danger btn-flat export-EXCEL" data-target="#tbl_odr">
                                    <i class="fas fa-file-excel"></i>&nbsp;
                                    Excel
                                </button>
                            <?php
                            }

                            if ($tasks['tsk_odr_print']) {
                            ?>
                                <button type="button" class="btn bg-gradient-success btn-flat rounded-right export-PRINT" data-target="#tbl_odr">
                                    <i class="fas fa-print"></i>&nbsp;
                                    Print
                                </button>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-2 mt-1 m-lg-0">
                        <div class="input-group float-sm-left">
                            <input type="text" id="orderQuickSearch" data-callback="" data-target="#tbl_odr" class="quick-search form-control float-right" placeholder="Quick Search">
                            <div class="input-group-append">
                                <button class="btn btn-default"><i class="fas fa-search"></i></button>
                            </div>
                        </div>
                    </div>

                    <!-- '.sr-pg-link-container' is used to scroll to "Pagination links" when click on .sr-go-to-tbl -->
                    <div class="col-sm-6 col-lg-3 mt-1 m-lg-0 sr-pg-link-container">
                        <div id="odr_pagination" class="float-sm-right"></div>
                    </div>
                </div>
            </div>

            <!-- '.sr-tbl-cont' is also used to scroll to table when click on .sr-go-to-tbl -->
            <div class="sr-tbl-cont card-body table-responsive p-0" id="tbl_odr_container">
                <table class="sr-tbl-reports table table-head-fixed text-nowrap" id="tbl_odr" data-exportTitle_1="<?= $client['clnt_print_name'] ?>" data-exportTitle_2="ORDERS LIST" data-exportTitle_3="List of orders">
                    <thead>
                        <tr>
                            <!-- $('.export').text() will be used for export (Print/PDF/Excel) -->
                            <!-- ".dv-ramp .ramp" is a close button to close the entair column-->
                            <th style="width: 10px">
                                <div class="form-group clearfix m-0">
                                    <div class="icheck-danger d-inline">
                                        <input type="checkbox" class="all-checker" id="odr-all-check">
                                        <label for="odr-all-check"></label>
                                    </div>
                                    <span class="export d-none" data-exportStyle="text-align: left; color:orange;font-weight:bolder">#</span>
                                    <div class="dv-ramp" title="Delete the column"><i class="fa fa-times-circle ramp"></i></div>
                                </div>

                            </th>
                            <th>
                                <span class="export" data-exportStyle="text-align: center; color:orange;font-weight:bolder">NO:</span>
                                <div class="dv-ramp" title="Delete the column"><i class="fa fa-times-circle ramp"></i></div>
                            </th>
                            <th>
                                <span class="export" data-exportStyle="">DATE</span>
                                <div class="dv-ramp" title="Delete the column"><i class="fa fa-times-circle ramp"></i></div>
                            </th>
                            <th>
                                <span class="export" data-exportStyle="">CENTRAL STORE</span>
                                <div class="dv-ramp" title="Delete the column"><i class="fa fa-times-circle ramp"></i></div>
                            </th>
                            <th>
                                <span class="export">WARD</span>
                                <div class="dv-ramp" title="Delete the column"><i class="fa fa-times-circle ramp"></i></div>
                            </th>
                            <th>
                                <span class="export">FAMILY</span>
                                <div class="dv-ramp" title="Delete the column"><i class="fa fa-times-circle ramp"></i></div>
                            </th>
                            <th>
                                <span class="export">ITEMS</span>
                                <div class="dv-ramp" title="Delete the column"><i class="fa fa-times-circle ramp"></i></div>
                            </th>
                            <th>
                                <span class="export">STATUS</span>
                                <div class="dv-ramp" title="Delete the column"><i class="fa fa-times-circle ramp"></i></div>
                            </th>
                            <th>
                                <span class="export" data-exportStyle="">ESTORE</span>
                                <div class="dv-ramp" title="Delete the column"><i class="fa fa-times-circle ramp"></i></div>
                            </th>
                            <th class="text-center" style="width: 200px">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-danger  btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        MOVE
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item mover" data-level-id="2" data-level-name="PICKED">
                                            <div class="level-dot" style="background-color:blue">&nbsp;</div>
                                            <div class="level-nm">PICKED</div>
                                        </a>
                                        <a class="dropdown-item mover" data-level-id="4" data-level-name="PACKED">
                                            <div class="level-dot" style="background-color:tomato">&nbsp;</div>
                                            <div class="level-nm">PACKED</div>
                                        </a>
                                        <a class="dropdown-item mover" data-level-id="5" data-level-name="ESTORE">
                                            <div class="level-dot" style="background-color:teal">&nbsp;</div>
                                            <div class="level-nm">ESTORE</div>
                                        </a>
                                        <a class="dropdown-item mover" data-level-id="6" data-level-name="DELIVERED">
                                            <div class="level-dot" style="background-color:sandybrown">&nbsp;</div>
                                            <div class="level-nm">DELIVERED</div>
                                        </a>
                                        <a class="dropdown-item mover" data-level-id="7" data-level-name="PAID">
                                            <div class="level-dot" style="background-color:purple">&nbsp;</div>
                                            <div class="level-nm">PAID</div>
                                        </a>
                                    </div>
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                    <!-- <tfoot>
                        <tr>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
                        </tr>
                    </tfoot> -->
                </table>
            </div>
            <!-- /.card-body -->
        </div>
    </div>
</div>