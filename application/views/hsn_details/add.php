<div class="modal fade" tabindex="-1" role="dialog" id="hsn_add_modal" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header bg-danger">
                <h4 class="modal-title" id="gridSystemModalLabel">ADD HSN DETAIL</h4>
                <button title="Close (Esc)" type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>


            <form class="sr-form" role="form" id="hsn_add_form" onsubmit="$(this).find(':submit').prop('disabled', true)">
                <div class="modal-body">


                    <div class="row">
                        <div class="col-6">

                            <div class="sr-wraper">
                                <div class="form-group">
                                    <label for="recipient-name" class="control-label sr-mdt-lbl">HSN CODE:</label>
                                    <input name="hsn_name" type="text" class="form-control form-control-sm">
                                </div>
                            </div>



                            <div class="sr-wraper">
                                <div class="form-group">
                                    <label for="recipient-name" class="control-label sr-mdt-lbl">Commodity:</label>
                                    <input name="hsn_commodity" type="text" class="form-control form-control-sm">
                                </div>
                            </div>



                            <div class="sr-wraper">
                                <div class="form-group">
                                    <label for="recipient-name" class="control-label sr-mdt-lbl">Chapter:</label>
                                    <input name="hsn_chapter" type="text" class="numberOnly form-control form-control-sm">
                                </div>
                            </div>

                        </div>

                        <div class="col-6">


                            <div class="sr-wraper">
                                <div class="form-group">
                                    <label for="recipient-name" class="control-label sr-mdt-lbl">HSN CODE (4 Digit):</label>
                                    <input name="hsn_name_4_digit" type="text" class="form-control form-control-sm">
                                </div>
                            </div>



                            <div class="sr-wraper">
                                <div class="form-group">
                                    <label for="recipient-name" class="control-label sr-mdt-lbl">SCH:</label>
                                    <input name="hsn_sch" type="text" class="numberOnly form-control form-control-sm">
                                </div>
                            </div>



                            <div class="sr-wraper">
                                <div class="form-group">
                                    <label for="recipient-name" class="control-label sr-mdt-lbl">GST Rate:</label>
                                    <input name="hsn_gst" placeholder="" type="text" class="numberOnly form-control form-control-sm">
                                </div>
                            </div>
                        </div>

                    </div>




                    <input type="hidden" name="hsn_id">
                </div>



                <div class="modal-footer d-block">

                    <div class="">
                        <div class="text-left">
                            <div class="form-group clearfix">
                                <div class="sr-wraper sr-check icheck-danger d-inline">
                                    <!-- '.no-init' elements will be skipped from initialization of Form elements. So it keeps its final status -->
                                    <input type="checkbox" class="self-close no-init no-enter" checked id="hsn_add_modal_self_close">
                                    <label for="hsn_add_modal_self_close">Self Close</label>
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