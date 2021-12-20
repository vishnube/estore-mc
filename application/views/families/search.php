<div class="row justify-content-md-center mt-2">
    <div class="col-md-12">

        <div class="card card-primary">




            <div class="card-header">

                <div class="row">
                    <div class="col-12  col-sm-9">
                        <h4 class="card-title">
                            <a data-toggle="collapse" href="#family_search_form_container" role="button" aria-expanded="false" aria-controls="family_search_form_container">
                                <i class="pr-2 fas fa-search"></i>
                                SEARCH FAMILY
                                <i class="pl-5 fas fa-chevron-down"></i>
                            </a>
                        </h4>
                    </div>
                    <div class="col-12  col-sm-3 mt-4 mt-sm-0">
                        <!-- Handler to scroll to 'data-pagin' OR 'data-table' on click -->
                        <i class="sr-go-to-tbl fas fa-angle-double-down fa-2x" title="Move down" data-pagin="#fmly_pagination" data-table="#tbl_fmly_container" style="top: 0;"></i>
                    </div>
                </div>

            </div>







            <div class="sr-collapse collapse" id="family_search_form_container">
                <form class="sr-form" role="form" id="fmly_search_form" onsubmit="$(this).find(':submit').prop('disabled', true)" data-afterFormInit="afterfmlySearchFormReset">
                    <div class="card-body">
                        <div class="row">


                            <div class="col-12 col-sm-6 col-md-4 col-lg-2">
                                <div class="sr-wraper">
                                    <div class="form-group">
                                        <label>State</label>
                                        <div class="select2-sm">
                                            <select name="stt_id" class="stt_option form-control form-control-sm select2 select2-danger" data-dropdown-css-class="select2-info" style="width: 100%;">
                                                <?= get_options($stt_option) ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-sm-6 col-md-4 col-lg-2">
                                <div class="sr-wraper">
                                    <div class="form-group">
                                        <label>District</label>
                                        <div class="select2-sm">
                                            <select name="dst_id" class="dst_option form-control form-control-sm select2 select2-danger" data-dropdown-css-class="select2-info" style="width: 100%;">
                                                <?= get_options() ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-sm-6 col-md-4 col-lg-2">
                                <div class="sr-wraper">
                                    <div class="form-group">
                                        <label>Taluk</label>
                                        <div class="select2-sm">
                                            <select name="tlk_id" class="tlk_option form-control form-control-sm select2 select2-danger" data-dropdown-css-class="select2-info" style="width: 100%;">
                                                <?= get_options() ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="col-12 col-sm-6 col-md-4 col-lg-2">
                                <div class="sr-wraper">
                                    <div class="form-group">
                                        <label>Area</label>
                                        <div class="select2-sm">
                                            <select name="ars_id" class="ars_option form-control form-control-sm select2 select2-danger" data-dropdown-css-class="select2-info" style="width: 100%;">
                                                <?= get_options() ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>



                            <div class="col-12 col-sm-6 col-md-4 col-lg-2">
                                <div class="sr-wraper">
                                    <div class="form-group">
                                        <label>Ward</label>
                                        <div class="select2-sm">
                                            <select name="fmly_fk_wards" class="wrd_option form-control form-control-sm select2 select2-danger" data-dropdown-css-class="select2-info" style="width: 100%;">
                                                <?= get_options() ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="col-12 col-sm-6 col-md-4 col-lg-2">
                                <div class="sr-wraper">
                                    <div class="form-group">
                                        <label>Name</label>
                                        <input type="text" name="fmly_name" id="" class="form-control form-control-sm">
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-sm-6 col-md-4 col-lg-2">
                                <div class="sr-wraper">
                                    <div class="form-group">
                                        <label>Category</label>
                                        <div class="select2-sm select2-purple">
                                            <!-- .sr-no-empty-vals means that this select box don't have <option value=""> -->
                                            <select name="cat_id[]" tabindex="2" class="select2 fmly_cat_option sr-no-empty-vals form-control form-control-sm" multiple="multiple" data-placeholder="Select a Category" data-dropdown-css-class="select2-purple" style="width: 100%;">
                                                <?= get_options($cat_option, '', '', false) ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-sm-6 col-md-4 col-lg-2">
                                <div class="form-group clearfix">
                                    <label class="sr-label sr-mdt-lbl">Status</label><br>
                                    <!-- .sr-wraper sr-rad is to highlight element on focus -->
                                    <div class="sr-wraper sr-rad icheck-danger d-inline">
                                        <!-- data-default="true" is used to initialize the form.-->
                                        <input data-default="true" type="radio" value="1" name="mbr_status" id="fmly_search_status1">
                                        <label for="fmly_search_status1">Active</label>
                                    </div>

                                    <div class="sr-wraper sr-rad icheck-danger d-inline">
                                        <input type="radio" value="2" name="mbr_status" id="fmly_search_status2">
                                        <label for="fmly_search_status2">Inactive</label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-sm-6 col-md-4 col-lg-2">
                                <div class="sr-wraper">
                                    <div class="form-group">
                                        <label>Central Store</label>
                                        <div class="select2-sm">
                                            <select name="cstr_id" class="cstr_option form-control form-control-sm select2 select2-danger" data-dropdown-css-class="select2-info" style="width: 100%;">
                                                <?= get_options($cstr_option) ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="col-12 col-sm-6 col-md-4 col-lg-2">
                                <div class="sr-wraper">
                                    <div class="form-group">
                                        <label>Estore</label>
                                        <div class="select2-sm">
                                            <select name="estr_id" class="estr_option form-control form-control-sm select2 select2-danger" data-dropdown-css-class="select2-info" style="width: 100%;">
                                                <?= get_options() ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="col-12 col-sm-6 col-md-4 col-lg-2">
                                <div class="sr-wraper">
                                    <div class="input-group input-group-sm form-group">
                                        <label>Inactive For</label>
                                        <input type="text" name="inactive_for" class="form-control form-control-sm">
                                        <div class="input-group-append">
                                            <span class="input-group-text">
                                                Days
                                                &nbsp;<i class="fas fa-map-marker-question" data-toggle="tooltip" data-html="true" title="<em>Includes Today</em>"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-sm-6 col-md-4 col-lg-2">
                                <div class="form-group clearfix">
                                    <label class="sr-label sr-mdt-lbl">Sort By</label><br>
                                    <!-- .sr-wraper sr-rad is to highlight element on focus -->
                                    <div class="sr-wraper sr-rad icheck-success d-inline">
                                        <!-- data-default="true" is used to initialize the form.-->
                                        <input data-default="true" type="radio" value="mbr_name" name="sort_by" id="fmly_sort_by_1">
                                        <label for="fmly_sort_by_1">Name</label>
                                    </div>

                                    <div class="sr-wraper sr-rad icheck-success d-inline">
                                        <input type="radio" value="sls_amount" name="sort_by" id="fmly_sort_by_2">
                                        <label for="fmly_sort_by_2">Purchase</label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-sm-6 col-md-4 col-lg-2">
                                <div class="input-group input-group-sm mb-3 dv-sls-from">
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                            PURCHASE
                                        </button>
                                        <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 38px, 0px);">
                                            <div class="dropdown-item" data-dt="">All</div>
                                            <div class="dropdown-item" data-dt="<?= date('d-m-Y') ?>">Today</div>
                                            <div class="dropdown-item" data-dt="<?= date('d-m-Y', strtotime("-1 days")); ?>">Yesterday</div>
                                            <div class="dropdown-item" data-dt="<?= date('d-m-Y', strtotime("last sunday")) ?>"">This Week</div>
                                            <div class=" dropdown-divider"></div>
                                            <div class="dropdown-item active" data-dt="<?= date('01-m-Y') ?>"">This Month</div>
                                            <div class=" dropdown-item" data-dt="<?= date('01-m-Y', strtotime("-2 months")) ?>"">This Quarter</div>
                                            <div class=" dropdown-item" data-dt="<?= date('01-01-Y') ?>"">This Year</div>
                                        </div>
                                    </div>
                                    <input type=" text" data-default="<?= date('01-m-Y') ?>" name="sls_from" id="sls_from" class="sr-date-field-1 form-control form-control-sm" data-inputmask-alias="datetime" data-inputmask-inputformat="dd-mm-yyyy" data-mask>

                                            </div>
                                        </div>



                                    </div>
                                </div>
                                <div class="card-footer text-center" style="border-bottom:2px solid #007bff">
                                    <div class="sr-wraper-bold">
                                        <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i>&nbsp;SEARCH</button>
                                    </div>
                                    <div class="sr-wraper-bold">
                                        <div class="sr-reset-btn btn btn-danger"><i class="fas fa-repeat-alt"></i> &nbsp;RESET</div>
                                    </div>
                                </div>
                </form>

            </div>
        </div>
    </div>
</div>