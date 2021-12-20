<h3 class="p-3 bg-danger text-center">UNSELECTED PRODUCTS</h3>

<form class="sr-form" role="form" id="product_load_form" onsubmit="$(this).find(':submit').prop('disabled', true)" data-afterFormInit="">
    <div class="row">
        <div class="col col-12 col-sm-6 col-lg-12">
            <div class="parent-dv">
                <div class="sr-wraper">
                    <div class="form-group">
                        <label>Category</label>
                        <div class="input-group mb-3 input-group-sm form-group">
                            <div class="input-group-prepend">
                                <button type="button" class="btn btn-danger reset-pctopt">Reset</button>
                            </div>
                            <select name="pct_parent_selector" class="pct_parent_selector pct_option form-control form-control-sm">
                                <?= get_options($pct_option, '', 'Select Category', true, false, 'No Categories') ?>
                            </select>
                        </div>
                    </div>
                    <div class="parents m-1 p-1"></div>
                </div>
            </div>
        </div>
        <div class="col col-12 col-sm-4 col-lg-7">
            <div class="sr-wraper">
                <div class="form-group">
                    <label>Product Name</label>
                    <input type="text" name="prd_name" class="form-control form-control-sm">
                </div>
            </div>
        </div>

        <div class="col col-12 col-sm-2 col-lg-5">
            <div class="sr-wraper-bold">
                <div style="margin-top:-8px">&nbsp</div>
                <button type="submit" class="btn btn-primary"><i class="fad fa-spinner-third"></i>&nbsp;LOAD</button>
            </div>
        </div>

    </div>
    <div class="row">
        <div class="col col-7 col-lg-5">
            <div class="sr-wraper">
                <div class="form-group">
                    <label>Price Group</label>
                    <div class="select2-sm">
                        <select name="pgp_id" class="pgp_option form-control form-control-sm select2 select2-danger" data-dropdown-css-class="select2-danger" style="width: 100%;">
                            <?= get_options($pgp_option) ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="col col-5">

            <?php
            if ($tsk_pgp_add) {
            ?>
                <div class="add_pgp btn btn-flat btn-sm btn-warning" title="Add new price group" style="margin-top: 25px;">ADD PRICE GROUPS</div>
            <?php
            }
            ?>
        </div>

    </div>
</form>

<div class="row">
    <div class="col-12">
        <div class="card" style="box-shadow: none;">
            <!-- 1px solid rgba(0, 0, 0, 0.125) -->
            <div class="card-header px-lg-2 py-lg-1" style="border:none;border-top:1px solid #eee8e8;border-radius: 0;">
                <div class="row">
                    <div class="col-12 col-md-6 mt-1 m-lg-0 clearfix text-nowrap">
                        <div class="float-left" id="prd_pagination_msg"></div>
                        <div class="float-left dv-perpage" data-callback="onProductPerpageChanged">
                            <input type="hidden" class="per-page" id="prd_per_page" value="10">
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




                    <!-- '.sr-pg-link-container' is used to scroll to "Pagination links" when click on .sr-go-to-tbl -->
                    <div class="col-12 col-md-6 mt-1 m-lg-0 sr-pg-link-container">
                        <div id="prd_pagination" class="float-sm-right"></div>
                    </div>
                </div>
            </div>

            <!-- '.sr-tbl-cont' is also used to scroll to table when click on .sr-go-to-tbl -->
            <div class="sr-tbl-cont card-body table-responsive p-0" id="tbl_prd_container">
                <table class="table table-head-fixed text-nowrap  table-hover table-striped" id="tbl_prd">

                    <tbody></tbody>

                </table>
            </div>
            <!-- /.card-body -->
        </div>
    </div>
</div>