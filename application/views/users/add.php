<div class="modal fade" tabindex="-1" role="dialog" id="usr_add_modal" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="sr-form-tag modal-header bg-success">
                <span style="top:11px;left:5px;" class="sr-form-icon fa-stack">
                    <i class="fas fa-circle fa-stack-2x text-white"></i>
                    <?= get_add_icon($icon) ?>
                </span>
                <h4 class="modal-title" id="gridSystemModalLabel">ADD USER</h4>
                <span title="Close (Esc)" data-dismiss="modal" aria-label="Close" class="cursor-pointer" style="font-size: 23px;">
                    <span aria-hidden="true"><i class="fas fa-times-circle fa-inverse"></i></span>
                </span>
            </div>


            <form class="sr-form" role="form" id="usr_add_form" onsubmit="$(this).find(':submit').prop('disabled', true)">
                <div class="modal-body">


                    <?php
                    // If more than one member types, Using dropdown 
                    if (count($mbrtp_option) > 1) {
                    ?>
                        <div class="row">
                            <div class="col col-lg-6">

                                <div class="sr-wraper">
                                    <div class="form-group">
                                        <label class="control-label sr-mdt-lbl">Member Type</label>
                                        <select name="mbr_fk_member_types" class="mbr_fk_member_types form-control form-control-sm">
                                            <?= get_options($mbrtp_option) ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                    <?php }
                    // If Only one member type
                    else {
                        // Getting the mbrtp_id
                        $mbrtp_id = isset($mbrtp_option[0]) ? array_keys($mbrtp_option)[0] : 0;
                        echo '<input type="hidden"  class="mbr_fk_member_types" name="mbr_fk_member_types" value="' . $mbrtp_id . '"  data-default="' . $mbrtp_id . '" >';
                    }
                    ?>

                    <div class="row">
                        <div class="col col-lg-6">
                            <div class="sr-wraper">
                                <div class="form-group">
                                    <label class="control-label sr-mdt-lbl">Name</label>
                                    <select name="usr_fk_members" class="usr_fk_members form-control form-control-sm">
                                        <?= get_options(array()) ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col col-lg-6">
                            <div class="sr-wraper">
                                <div class="form-group">
                                    <label>Group</label>
                                    <div class="select2-sm select2-success">
                                        <!-- .sr-no-empty-vals means that this select box don't have <option value=""> -->
                                        <select name="grp_id[]" class="select2 grp_option sr-no-empty-vals" multiple="multiple" data-placeholder="Select a Group" data-dropdown-css-class="select2-success" style="width: 100%;">
                                            <?= get_options($grp_option, '', '', false) ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col col-lg-6">
                            <div class="sr-wraper">
                                <div class="form-group">
                                    <label class="control-label sr-mdt-lbl">Username:</label>
                                    <input name="usr_username" type="text" class="form-control form-control-sm">
                                </div>
                            </div>
                        </div>

                        <div class="col col-lg-6">
                            <div class="sr-wraper">
                                <div class="form-group">
                                    <label class="control-label sr-mdt-lbl">Password:</label>
                                    <input name="usr_password" type="text" class="form-control form-control-sm">
                                </div>
                            </div>
                        </div>

                    </div>




                    <div class="row">
                        <div class="col col-lg-6">
                            <div class="sr-wraper">
                                <div class="form-group">
                                    <label class="control-label sr-mdt-lbl">Email:</label>
                                    <input name="usr_email" type="text" class="form-control form-control-sm">
                                </div>
                            </div>
                        </div>

                        <div class="col col-lg-6">
                            <div class="sr-wraper">
                                <div class="form-group">
                                    <label class="control-label sr-mdt-lbl">Mobile:</label>
                                    <input name="usr_mob" type="text" class="form-control form-control-sm">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer clearfix d-block">
                    <div class="float-left">
                        <div class="form-group clearfix">
                            <div class="sr-wraper sr-check icheck-danger d-inline">
                                <!-- '.no-init' elements will be skipped from initialization of Form elements. So it keeps its final status -->
                                <input type="checkbox" class="self-close no-init" checked id="usr_add_modal_self_close">
                                <label for="usr_add_modal_self_close">Self Close</label>
                            </div>
                        </div>
                    </div>

                    <div class="float-right">

                        <div class="sr-wraper-bold">
                            <button type="submit" class="btn btn-primary save">SAVE</button>
                        </div>
                    </div>
                </div>
            </form>


        </div>
    </div>
</div>