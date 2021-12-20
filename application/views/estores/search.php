<div class="row justify-content-md-center mt-2">
    <div class="col-md-12">

        <div class="card card-primary">
            <div class="card-header">
                <h4 class="card-title">
                    <a data-toggle="collapse" href="#estore_search_form_container" role="button" aria-expanded="false" aria-controls="estore_search_form_container">
                        <i class="pr-2 fas fa-search"></i>
                        SEARCH ESTORES
                        <i class="pl-5 fas fa-chevron-down"></i>
                    </a>
                </h4>
                <!-- Handler to scroll to 'data-pagin' OR 'data-table' on click -->
                <i class="sr-go-to-tbl fas fa-angle-double-down fa-2x" title="Move down" data-pagin="#estr_pagination" data-table="#tbl_estr_container"></i>

            </div>
            <div class="sr-collapse collapse" id="estore_search_form_container">
                <form role="form" id="estr_search_form" onsubmit="$(this).find(':submit').prop('disabled', true)" data-afterFormInit="">
                    <div class="card-body">
                        <div class="row">


                            <div class="col-12 col-md-6 offset-lg-2 col-lg-4">
                                <div class="sr-wraper">
                                    <div class="form-group">
                                        <label>Central Store</label>
                                        <div class="select2-sm">
                                            <select name="estr_fk_central_stores" class="cstr_option form-control form-control-sm select2 select2-danger" data-dropdown-css-class="select2-info" style="width: 100%;">
                                                <?= get_options($cstr_option) ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="sr-wraper">
                                    <div class="form-group">
                                        <label>Estore Name</label>
                                        <input type="text" name="estr_name" id="" class="form-control form-control-sm">
                                    </div>
                                </div>

                                <div class="sr-wraper">
                                    <div class="form-group">
                                        <label class="sr-mdt-lbl">Category</label>
                                        <select data-default="" name="cat_id" class="estr_cat_option sr-no-empty-vals form-control form-control-sm">
                                            <?= get_options($estr_cat_option) ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group clearfix">
                                    <label class="sr-label sr-mdt-lbl">Status</label><br>
                                    <div class="sr-wraper sr-rad icheck-danger d-inline">
                                        <input data-default="true" type="radio" value="1" name="mbr_status" id="estr_search_status1">
                                        <label for="estr_search_status1">Active</label>
                                    </div>
                                    <div class="sr-wraper sr-rad icheck-danger d-inline">
                                        <input type="radio" value="2" name="mbr_status" id="estr_search_status2">
                                        <label for="estr_search_status2">Inactive</label>
                                    </div>
                                </div>

                            </div>

                            <div class="col-12 col-md-6 col-lg-4">
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

                                <div class="sr-wraper">
                                    <div class="form-group">
                                        <label class="sr-mdt-lbl">Taluk</label>
                                        <div class="select2-sm">
                                            <select name="tlk_id" class="tlk_option form-control form-control-sm select2 select2-danger" data-dropdown-css-class="select2-info" style="width: 100%;">
                                                <?= get_options() ?>
                                            </select>
                                        </div>
                                    </div>
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