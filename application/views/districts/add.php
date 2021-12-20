<div class="modal fade" tabindex="-1" role="dialog" id="dst_add_modal" data-backdrop="static">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">

            <div class="modal-header bg-success">
                <h4 class="modal-title" id="gridSystemModalLabel">ADD DISTRICT</h4>
                <button title="Close (Esc)" type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>


            <form class="sr-form" role="form" id="dst_add_form" onsubmit="$(this).find(':submit').prop('disabled', true)">
                <div class="modal-body">

                    <div class="sr-wraper">
                        <div class="form-group">
                            <label>State</label>
                            <select name="dst_fk_states" class="stt_option form-control form-control-sm">
                                <?= get_options($stt_option) ?>
                            </select>
                        </div>
                    </div>



                    <!-- .sr-wraper is to highlight element on focus -->
                    <div class="sr-wraper">
                        <div class="form-group">
                            <!-- '.sr-mdt-lbl' describes the label of a mandatory field -->
                            <label for="recipient-name" class="control-label sr-mdt-lbl">Name:</label>
                            <input name="dst_name" type="text" class="form-control form-control-sm">
                        </div>
                    </div>

                    <input type="hidden" name="dst_id">
                </div>


                <div class="modal-footer d-block">

                    <div class="">
                        <div class="text-left">
                            <div class="form-group clearfix">
                                <div class="sr-wraper sr-check icheck-danger d-inline">
                                    <!-- '.no-init' elements will be skipped from initialization of Form elements. So it keeps its final status -->
                                    <input type="checkbox" class="self-close no-init no-enter" checked id="dst_add_modal_self_close">
                                    <label for="dst_add_modal_self_close">Self Close</label>
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