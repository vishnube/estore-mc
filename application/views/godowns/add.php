<div class="modal fade" tabindex="-1" role="dialog" id="gdn_add_modal" data-backdrop="static">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">

            <div class="modal-header bg-danger">
                <h4 class="modal-title" id="gridSystemModalLabel">ADD GODOWN</h4>
                <button title="Close (Esc)" type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>


            <form class="sr-form" role="form" id="gdn_add_form" onsubmit="$(this).find(':submit').prop('disabled', true)">
                <div class="modal-body">




                    <div class="sr-wraper">
                        <div class="form-group">
                            <label>Central Store</label>
                            <div class="select2-sm">
                                <select name="gdn_fk_central_stores" class="cstr_option form-control form-control-sm select2 select2-danger" data-dropdown-css-class="select2-info" style="width: 100%;">
                                    <?= get_options($cstr_option) ?>
                                </select>
                            </div>
                        </div>
                    </div>


                    <div class="sr-wraper">
                        <div class="form-group">
                            <!-- '.sr-mdt-lbl' describes the label of a mandatory field -->
                            <label for="recipient-name" class="control-label sr-mdt-lbl">Name:</label>
                            <!-- Use 'tabindex' attribute for mandatory inputs -->
                            <input tabindex="1" name="gdn_name" type="text" class="form-control form-control-sm">
                        </div>
                    </div>

                    <input type="hidden" name="gdn_id">
                </div>



                <div class="modal-footer d-block">

                    <div class="">
                        <div class="text-left">
                            <div class="form-group clearfix">
                                <div class="sr-wraper sr-check icheck-danger d-inline">
                                    <!-- '.no-init' elements will be skipped from initialization of Form elements. So it keeps its final status -->
                                    <input type="checkbox" class="self-close no-init no-enter" checked id="gdn_add_modal_self_close">
                                    <label for="gdn_add_modal_self_close">Self Close</label>
                                </div>
                            </div>
                        </div>

                        <div class="text-left">
                            <!-- .sr-wraper-bold is to highlight element on focus -->
                            <div class="sr-wraper-bold">
                                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i>&nbsp;SAVE</button>
                            </div>
                            <div class="sr-wraper-bold">
                                <div class="sr-reset-btn btn btn-danger"><i class="fas fa-repeat-alt"></i> &nbsp;RESET</div>
                            </div>
                        </div>
                    </div>
                </div>



            </form>


        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->