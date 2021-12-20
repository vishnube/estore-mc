<div class="row justify-content-md-center mt-2">
    <div class="col-md-12">

        <div class="card card-primary">
            <div class="card-header">
                <div class="row">
                    <div class="col-12  col-sm-9">
                        <h4 class="card-title">
                            <a data-toggle="collapse" href="#price_groups_location_search_form_container" role="button" aria-expanded="false" aria-controls="price_groups_location_search_form_container">
                                <i class="pr-2 fas fa-search"></i>
                                SEARCH LOCATIONS
                                <i class="pl-5 fas fa-chevron-down"></i>
                            </a>
                        </h4>
                    </div>
                    <div class="col-12  col-sm-3 mt-4 mt-sm-0">
                        <!-- Handler to scroll to 'data-pagin' OR 'data-table' on click -->
                        <i class="sr-go-to-tbl fas fa-angle-double-down fa-2x" title="Move down" data-pagin="#pgpl_pagination" data-table="#tbl_pgpl_container" style="top: 0;"></i>

                        <?php
                        if ($tsk_pgp_add) {
                        ?>
                            <div class="btn btn-flat btn-sm btn-warning" id="add_pgpl" title="Add locations to price group">ADD LOCATIONS</div>
                        <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
            <div class="sr-collapse collapse" id="price_groups_location_search_form_container">
                <form role="form" id="pgpl_search_form" onsubmit="$(this).find(':submit').prop('disabled', true)" data-afterFormInit="afterpgplSearchFormReset">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 col-sm-6 col-md-3">
                                <div class="sr-wraper">
                                    <div class="form-group">
                                        <label>Valid On:</label>
                                        <input type="text" data-default="<?= today() ?>" name="v_date" class="sr-date-field-1 form-control form-control-sm" data-inputmask-alias="datetime" data-inputmask-inputformat="dd-mm-yyyy" data-mask>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-sm-6 col-md-3">
                                <div class="sr-wraper">
                                    <div class="form-group">
                                        <label>Price Group</label>
                                        <div class="select2-sm select2-info">
                                            <select name="pgpl_fk_price_groups[]" class="pgp_option select2 sr-no-empty-vals form-control form-control-sm" multiple="multiple" data-placeholder="Select Price Group" data-dropdown-css-class="select2-info" style="width: 100%;">
                                                <?= get_options($pgp_option, '', '', false) ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-sm-6 col-md-3">
                                <div class="sr-wraper">
                                    <div class="form-group">
                                        <label>Central Store</label>
                                        <div class="select2-sm">
                                            <select name="cstr_id" class="cstr_option form-control form-control-sm select2 select2-danger" data-dropdown-css-class="select2-danger" style="width: 100%;">
                                                <?= get_options($cstr_option) ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-sm-6 col-md-3">
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

                            <div class="col-12 col-sm-6 col-md-3">
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

                            <div class="col-12 col-sm-6 col-md-3">
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

                            <div class="col-12 col-sm-6 col-md-3">
                                <div class="sr-wraper">
                                    <div class="form-group">
                                        <label>Ward</label>
                                        <div class="select2-sm">
                                            <select name="wrd_id" class="wrd_option form-control form-control-sm select2 select2-danger" data-dropdown-css-class="select2-info" style="width: 100%;">
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