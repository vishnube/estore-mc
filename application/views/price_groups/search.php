<div class="row justify-content-md-center mt-2">
    <div class="col-md-12">

        <div class="card card-primary">
            <div class="card-header">
                <div class="row">
                    <div class="col-12  col-sm-9">
                        <h4 class="card-title">
                            <a data-toggle="collapse" href="#price_group_search_form_container" role="button" aria-expanded="false" aria-controls="price_group_search_form_container">
                                <i class="pr-2 fas fa-search"></i>
                                SEARCH PRICE GROUPS
                                <i class="pl-5 fas fa-chevron-down"></i>
                            </a>
                        </h4>
                    </div>
                    <div class="col-12  col-sm-3 mt-4 mt-sm-0">
                        <!-- Handler to scroll to 'data-pagin' OR 'data-table' on click -->
                        <i class="sr-go-to-tbl fas fa-angle-double-down fa-2x" title="Move down" data-pagin="#pgp_pagination" data-table="#tbl_pgp_container" style="top: 0;"></i>

                        <?php
                        if ($tsk_pgp_add) {
                        ?>
                            <div class="add_pgp btn btn-flat btn-sm btn-warning" id="" title="Add new price group">ADD PRICE GROUPS</div>
                        <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
            <div class="sr-collapse collapse" id="price_group_search_form_container">
                <form role="form" id="pgp_search_form" onsubmit="$(this).find(':submit').prop('disabled', true)" data-afterFormInit="afterpgpSearchFormReset">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 col-sm-6 col-md-4">
                                <div class="sr-wraper">
                                    <div class="form-group">
                                        <label>Date:</label>
                                        <div class="row">
                                            <div class="col-12 col-xl-6 input-group input-group-sm">
                                                <div class="input-group-prepend"><span class="input-group-text">From</div>
                                                <input type="text" name="f_date" id="f_date" class="sr-date-field-1 form-control form-control-sm" data-inputmask-alias="datetime" data-inputmask-inputformat="dd-mm-yyyy" data-mask>
                                            </div>

                                            <div class="col-12 col-xl-6 input-group input-group-sm">
                                                <div class="input-group-prepend"><span class="input-group-text">To</div>
                                                <input type="text" name="t_date" id="t_date" class="sr-date-field-1 form-control form-control-sm" data-inputmask-alias="datetime" data-inputmask-inputformat="dd-mm-yyyy" data-mask>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-sm-6 col-md-4">
                                <div class="sr-wraper">
                                    <div class="form-group">
                                        <label>Price Group Name</label>
                                        <input type="text" name="pgp_name" id="pgp_name" class="form-control form-control-sm" placeholder="">
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-sm-6 col-md-4">
                                <div class="form-group clearfix">
                                    <label class="sr-label sr-mdt-lbl">Status</label><br>
                                    <!-- .sr-wraper sr-rad is to highlight element on focus -->
                                    <div class="sr-wraper sr-rad icheck-danger d-inline">
                                        <!-- data-default="true" is used to initialize the form.-->
                                        <input data-default="true" type="radio" value="1" name="pgp_status" id="pgp_search_status1">
                                        <label for="pgp_search_status1">Active</label>
                                    </div>

                                    <div class="sr-wraper sr-rad icheck-danger d-inline">
                                        <input type="radio" value="2" name="pgp_status" id="pgp_search_status2">
                                        <label for="pgp_search_status2">Inactive</label>
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