<div class="modal fade" tabindex="-1" role="dialog" id="gst_add_modal" data-backdrop="static">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">

            <div class="modal-header bg-danger">
                <h4 class="modal-title sr-form-title has-default-value" data-default="ADD GST DETAILS" id="gridSystemModalLabel">ADD GST DETAILS</h4>
                <button title="Close (Esc)" type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <h5 class="text-center my-2" id="mbr_name"></h5>
            <form class="sr-form" role="form" id="gst_add_form" onsubmit="$(this).find(':submit').prop('disabled', true)" data-afterFormInit="">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="sr-wraper">
                                <div class="form-group">
                                    <label class="sr-mdt-lbl">GSTIN</label>
                                    <input type="text" name="gst_name" class="form-control form-control-sm">
                                </div>
                            </div>
                            <div class="sr-wraper">
                                <div class="form-group">
                                    <label class="sr-mdt-lbl">State</label>
                                    <select data-default="32" name="gst_fks_states" class="gst_fks_states form-control form-control-sm">
                                        <?= get_options(get_GST_state_codes(), '', '', false) ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group clearfix">
                                <label class="sr-label">is Default</label><br>
                                <div class="sr-wraper sr-check icheck-warning d-inline">
                                    <input data-default="false" name="gst_default" type="checkbox" id="gst_default_id_add">
                                    <label for="gst_default_id_add">Default GST No:</label>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>


                <div class="modal-footer clearfix d-block">
                    <input name="gst_id" id="gst_id" type="hidden">
                    <input name="gst_fk_members" id="gst_fk_members" type="hidden">

                    <div class="clearer">
                        <div class="float-left">
                            <div class="sr-wraper-bold">
                                <div class="btn btn-danger gst-back-btn"><i class="fas fa-backspace"></i> &nbsp;BACK</div>
                            </div>
                        </div>

                        <div class="float-right">
                            <!-- .sr-wraper-bold is to highlight element on focus -->
                            <div class="sr-wraper-bold">
                                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i>&nbsp;SAVE</button>
                            </div>
                        </div>
                    </div>

                    <div class="o_errors"></div>
                </div>
            </form>
        </div>
    </div>
</div>