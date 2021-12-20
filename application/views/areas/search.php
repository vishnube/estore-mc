<div class="row justify-content-md-center mt-2">
    <div class="col-md-12">

        <div class="card card-primary">
            <div class="card-header">

                <div class="row">
                    <div class="col-12  col-sm-9">
                        <h4 class="card-title">
                            <a data-toggle="collapse" href="#area_search_form_container" role="button" aria-expanded="false" aria-controls="area_search_form_container">
                                <i class="pr-2 fas fa-search"></i>
                                SEARCH AREA
                                <i class="pl-5 fas fa-chevron-down"></i>
                            </a>
                        </h4>
                    </div>
                    <div class="col-12  col-sm-3 mt-4 mt-sm-0">
                        <!-- Handler to scroll to 'data-pagin' OR 'data-table' on click -->
                        <i class="sr-go-to-tbl fas fa-angle-double-down fa-2x" title="Move down" data-pagin="#ars_pagination" data-table="#tbl_ars_container" style="top: 0;"></i>

                        <?php
                        if ($tsk_wrd_add) {
                        ?>
                            <div class="btn btn-flat btn-sm btn-warning" id="add_ars" title="Add new Area">ADD AREA</div>
                        <?php
                        }
                        ?>
                    </div>
                </div>

            </div>
            <div class="sr-collapse collapse" id="area_search_form_container">
                <form role="form" id="ars_search_form" onsubmit="$(this).find(':submit').prop('disabled', true)">
                    <div class="card-body">
                        <div class="row">



                            <div class="col-12 col-sm-4 col-md-3">
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

                            <div class="col-12 col-sm-4 col-md-3">
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

                            <div class="col-12 col-sm-4 col-md-3">
                                <div class="sr-wraper">
                                    <div class="form-group">
                                        <label>Taluk</label>
                                        <div class="select2-sm">
                                            <select name="ars_fk_taluks" class="tlk_option form-control form-control-sm select2 select2-danger" data-dropdown-css-class="select2-info" style="width: 100%;">
                                                <?= get_options() ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-sm-4 col-md-3">
                                <div class="sr-wraper">
                                    <div class="form-group">
                                        <label>Central Store</label>
                                        <div class="select2-sm">
                                            <select name="ars_fk_central_stores" tabindex="1" class="cstr_option form-control form-control-sm select2 select2-danger" data-dropdown-css-class="select2-danger" style="width: 100%;">
                                                <?= get_options($cstr_option) ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="col-12 col-sm-4 col-md-3">
                                <div class="sr-wraper">
                                    <div class="form-group">
                                        <label>Area Type</label>
                                        <div class="select2-sm">
                                            <select name="ars_type" class="form-control form-control-sm select2 select2-danger" data-dropdown-css-class="select2-info" style="width: 100%;">
                                                <?= get_options(get_area_types()) ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>



                            <div class="col-12 col-sm-4 col-md-3">
                                <div class="sr-wraper">
                                    <div class="form-group">
                                        <label>Area Name</label>
                                        <input type="text" name="ars_name" id="ars_name" class="form-control form-control-sm" placeholder="">
                                    </div>
                                </div>
                            </div>


                            <div class="col-12 col-sm-4 col-md-3">
                                <div class="form-group clearfix">
                                    <label class="sr-label sr-mdt-lbl">Status</label><br>
                                    <!-- .sr-wraper sr-rad is to highlight element on focus -->
                                    <div class="sr-wraper sr-rad icheck-danger d-inline">
                                        <!-- data-default="true" is used to initialize the form.-->
                                        <input data-default="true" type="radio" value="1" name="ars_status" id="ars_search_status1">
                                        <label for="ars_search_status1">Active</label>
                                    </div>

                                    <div class="sr-wraper sr-rad icheck-danger d-inline">
                                        <input type="radio" value="2" name="ars_status" id="ars_search_status2">
                                        <label for="ars_search_status2">Inactive</label>
                                    </div>
                                </div>

                            </div>
                            <!-- /.col -->

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