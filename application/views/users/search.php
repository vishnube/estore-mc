<div class="row justify-content-md-center mt-2">
    <div class="col-md-12">

        <div class="card card-primary">
            <div class="card-header">
                <h4 class="card-title">
                    <a data-toggle="collapse" href="#user_search_form_container" role="button" aria-expanded="false" aria-controls="user_search_form_container">
                        <i class="pr-2 fas fa-search"></i>
                        SEARCH USERS
                        <i class="pl-5 fas fa-chevron-down"></i>
                    </a>
                </h4>
                <!-- Handler to scroll to 'data-pagin' OR 'data-table' on click -->
                <i class="sr-go-to-tbl fas fa-angle-double-down fa-2x" title="Move down" data-pagin="#usr_pagination" data-table=".sr-tbl-cont"></i>

            </div>
            <div class="sr-collapse collapse" id="user_search_form_container">
                <form role="form" id="usr_search_form" onsubmit="$(this).find(':submit').prop('disabled', true)">
                    <div class="card-body">
                        <div class="row">


                            <?php
                            // If more than one member types, Using dropdown 
                            if (count($mbrtp_option) > 1) {
                            ?>
                                <div class="col-12 col-sm-3">
                                    <div class="sr-wraper">
                                        <div class="form-group">
                                            <label class="control-label sr-mdt-lbl">Member Type </label>
                                            <select name="mbrtp_id" tabindex="1" class="mbrtp_id form-control form-control-sm">
                                                <?= get_options($mbrtp_option) ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            <?php }
                            // If Only one member type
                            else {
                                // Getting the mbrtp_id
                                $mbrtp_id = isset($mbrtp_option[0]) ? array_keys($mbrtp_option)[0] : 0;
                                echo '<input type="hidden" tabindex="1" class="mbrtp_id" name="mbrtp_id" value="' . $mbrtp_id . '"  data-default="' . $mbrtp_id . '" >';
                            }
                            ?>


                            <div class="col-12 col-sm-3">
                                <div class="sr-wraper">
                                    <div class="form-group">
                                        <label>User</label>
                                        <input type="text" name="usr_name" id="" class="form-control form-control-sm">
                                    </div>
                                </div>
                            </div>
                            <!-- /.col -->
                            <div class="col-12 col-sm-3">
                                <div class="sr-wraper">
                                    <div class="form-group">
                                        <label>Group</label>
                                        <div class="select2-purple select2-sm">
                                            <!-- .sr-no-empty-vals means that this select box don't have <option value=""> -->
                                            <select name="grp_id[]" tabindex="2" class="select2 grp_option sr-no-empty-vals form-control form-control-sm" multiple="multiple" data-placeholder="Select a Category" data-dropdown-css-class="select2-purple" style="width: 100%;">
                                                <?= get_options($grp_option, '', '', false) ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.form-group -->
                            </div>
                            <!-- /.col -->
                            <div class="col-12 col-sm-3">

                                <div class="form-group clearfix">
                                    <label class="sr-label sr-mdt-lbl">Status</label><br>
                                    <!-- .sr-wraper sr-rad is to highlight element on focus -->
                                    <div class="sr-wraper sr-rad icheck-danger d-inline">
                                        <!-- data-default="true" is used to initialize the form.-->
                                        <input data-default="true" type="radio" value="1" name="usr_status" id="usr_search_status1">
                                        <label for="usr_search_status1">Active</label>
                                    </div>

                                    <div class="sr-wraper sr-rad icheck-danger d-inline">
                                        <input type="radio" value="2" name="usr_status" id="usr_search_status2">
                                        <label for="usr_search_status2">Inactive</label>
                                    </div>
                                </div>

                            </div>
                            <!-- /.col -->

                        </div>
                    </div>
                    <div class="card-footer text-center" style="border-bottom:2px solid #007bff">
                        <div class="sr-wraper-bold">
                            <button type="submit" tabindex="3" class="btn btn-primary"><i class="fas fa-search"></i>&nbsp;SEARCH</button>
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