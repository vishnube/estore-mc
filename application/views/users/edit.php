<div class="modal fade" tabindex="-1" role="dialog" id="usr_edit_modal" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="sr-form-tag modal-header bg-info">
                <span style="top:11px;left:5px;" class="sr-form-icon fa-stack">
                    <i class="fas fa-circle fa-stack-2x text-white"></i>
                    <?= get_add_icon($icon) ?>
                </span>
                <h4 class="modal-title" id="gridSystemModalLabel">EDIT USER</h4>
                <span title="Close (Esc)" data-dismiss="modal" aria-label="Close" class="cursor-pointer" style="font-size: 23px;">
                    <span aria-hidden="true"><i class="fas fa-times-circle fa-inverse"></i></span>
                </span>
            </div>


            <form class="sr-form" role="form" id="usr_edit_form" onsubmit="$(this).find(':submit').prop('disabled', true)">
                <div class="modal-body">




                    <div class="sr-wraper">
                        <div class="form-group">
                            <label>Date:</label>

                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                </div>
                                <!-- '.sr-date-field-1' is used in initFormInputs() to repopup with format() its value after before_edit -->
                                <!-- data-default is used to initialize the form.-->
                                <input type="text" data-default="<?= today() ?>" name="usr_date" id="usr_date" class="sr-date-field-1 form-control form-control-sm" data-inputmask-alias="datetime" data-inputmask-inputformat="dd-mm-yyyy" data-mask>
                            </div>
                        </div>
                    </div>



                    <div class="sr-wraper">
                        <div class="form-group">
                            <label>Group</label>
                            <div class="select2-success">
                                <!-- .sr-no-empty-vals means that this select box don't have <option value=""> -->
                                <select name="grp_id[]" tabindex="2" class="select2 grp_option sr-no-empty-vals" multiple="multiple" data-placeholder="Select a Group" data-dropdown-css-class="select2-success" style="width: 100%;">
                                    <?= get_options($grp_option, '', '', false) ?>
                                </select>
                            </div>
                        </div>
                    </div>


                    <input type="hidden" name="usr_id">
                </div>
                <div class="modal-footer clearfix d-block">
                    <div class="float-left">
                        <div class="form-group clearfix">
                            <div class="sr-wraper sr-check icheck-danger d-inline">
                                <!-- '.no-init' elements will be skipped from initialization of Form elements. So it keeps its final status -->
                                <input type="checkbox" class="self-close no-init" checked id="usr_edit_modal_self_close">
                                <label for="usr_edit_modal_self_close">Self Close</label>
                            </div>
                        </div>
                    </div>

                    <div class="float-right">

                        <div class="sr-wraper-bold">
                            <button type="submit" tabindex="3" class="btn btn-primary save">SAVE</button>
                        </div>
                    </div>
                </div>
            </form>


        </div>
    </div>
</div>