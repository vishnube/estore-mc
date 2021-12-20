<div class="modal fade" tabindex="-1" role="dialog" id="usr_profile_edit_modal" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="sr-form-tag modal-header bg-info">
                <span style="top:11px;left:5px;" class="sr-form-icon fa-stack">
                    <i class="fas fa-circle fa-stack-2x text-white"></i>
                    <i class="fad fa-chalkboard-teacher  fa-stack-1x fa-inverse" style="--fa-primary-color:#5877F2;--fa-secondary-color:#13F693;"></i>

                </span>
                <h4 class="modal-title" id="gridSystemModalLabel">EDIT PROFILE</h4>
                <span title="Close (Esc)" data-dismiss="modal" aria-label="Close" class="cursor-pointer" style="font-size: 23px;">
                    <span aria-hidden="true"><i class="fas fa-times-circle fa-inverse"></i></span>
                </span>
            </div>


            <form class="sr-form" role="form" id="usr_profile_edit_form" onsubmit="$(this).find(':submit').prop('disabled', true)">
                <div class="modal-body">

                    <div class="sr-wraper">
                        <div class="form-group">
                            <label for="recipient-name" class="control-label sr-mdt-lbl">Username:</label>
                            <input tabindex="1" name="usr_username" type="text" class="form-control form-control-sm">
                        </div>
                    </div>
                    <div class="sr-wraper">
                        <div class="form-group">
                            <label class="control-label sr-mdt-lbl">Email:</label>
                            <input name="usr_email" type="text" class="form-control form-control-sm">
                        </div>
                    </div>
                    <div class="sr-wraper">
                        <div class="form-group">
                            <label class="control-label sr-mdt-lbl">Mobile:</label>
                            <input name="usr_mob" type="text" class="form-control form-control-sm">
                        </div>
                    </div>
                    <div class="sr-wraper">
                        <div class="form-group">
                            <label class="control-label sr-mdt-lbl">Old Password:</label>
                            <input name="opw" type="text" class="form-control form-control-sm">
                        </div>
                    </div>
                    <div class="sr-wraper">
                        <div class="form-group">
                            <label class="control-label sr-mdt-lbl">New Password:</label>
                            <input name="npw" type="text" class="form-control form-control-sm">
                        </div>
                    </div>

                </div>
                <div class="modal-footer clearfix d-block">
                    <div class="float-left">
                        <div class="form-group clearfix">
                            <div class="sr-wraper sr-check icheck-danger d-inline">
                                <!-- '.no-init' elements will be skipped from initialization of Form elements. So it keeps its final status -->
                                <input type="checkbox" class="self-close no-init" checked id="usr_profile_edit_modal_self_close">
                                <label for="usr_profile_edit_modal_self_close">Self Close</label>
                            </div>
                        </div>
                    </div>

                    <div class="float-right">

                        <div class="sr-wraper-bold">
                            <!-- Javascripts related to this view is @ footer.php -->
                            <button type="submit" tabindex="3" class="btn btn-primary save">SAVE</button>
                        </div>
                    </div>
                </div>
            </form>


        </div>
    </div>
</div>