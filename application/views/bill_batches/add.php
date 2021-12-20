<div class="modal fade" tabindex="-1" role="dialog" id="blb_add_modal" data-backdrop="static">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">

            <div class="modal-header bg-success">
                <h4 class="modal-title" id="gridSystemModalLabel">ADD BILL BATCH</h4>
                <button title="Close (Esc)" type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>


            <form class="sr-form" role="form" id="blb_add_form" onsubmit="$(this).find(':submit').prop('disabled', true)">
                <div class="modal-body">

                    <div class="sr-wraper">
                        <div class="form-group">
                            <label>Date:</label>

                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                </div>
                                <input type="text" data-default="<?= today() ?>" name="blb_date" id="blb_date" class="sr-date-field-1 form-control form-control-sm" data-inputmask-alias="datetime" data-inputmask-inputformat="dd-mm-yyyy" data-mask>
                            </div>
                        </div>
                    </div>

                    <div class="sr-wraper">
                        <div class="form-group">
                            <label for="recipient-name" class="control-label sr-mdt-lbl">Name:</label>
                            <input name="blb_name" placeholder="Apr-2021 - Mar-2022" type="text" class="form-control form-control-sm">
                        </div>
                    </div>

                    <div class="form-group clearfix">
                        <label class="sr-label sr-mdt-lbl">Purpose</label><br>
                        <!-- .sr-wraper sr-rad is to highlight element on focus -->
                        <div class="sr-wraper sr-rad icheck-danger d-inline">
                            <!-- '.sr-mdt-lbl' describes the label of a mandatory field -->
                            <!-- data-default="true" is used to initialize the form.-->
                            <input data-default="true" type="radio" value="1" name="blb_type" id="blb_typeradioDanger1">
                            <label for="blb_typeradioDanger1">Non-Tax</label>
                        </div>

                        <div class="sr-wraper sr-rad icheck-danger d-inline">
                            <input type="radio" value="2" name="blb_type" id="blb_typeradioDanger2">
                            <label for="blb_typeradioDanger2">Tax</label>
                        </div>
                    </div>
                    <div class="row m-0 p-0">
                        <div class="col-6 m-0 p-0">
                            <div class="sr-wraper">
                                <div class="form-group">
                                    <label for="recipient-name" class="control-label sr-mdt-lbl">Prefix:</label>
                                    <input name="blb_prefix" type="text" class="form-control form-control-sm">
                                </div>
                            </div>


                        </div>
                        <div class="col-6 m-0 p-0">
                            <div class="sr-wraper">
                                <div class="form-group">
                                    <label for="recipient-name" class="control-label sr-mdt-lbl">Sufix:</label>
                                    <input name="blb_sufix" type="text" class="form-control form-control-sm">
                                </div>
                            </div>
                        </div>
                    </div>


                    <input type="hidden" name="blb_id">
                </div>


                <div class="modal-footer d-block">

                    <div class="">
                        <div class="text-left">
                            <div class="form-group clearfix">
                                <div class="sr-wraper sr-check icheck-danger d-inline">
                                    <!-- '.no-init' elements will be skipped from initialization of Form elements. So it keeps its final status -->
                                    <input type="checkbox" class="self-close no-init no-enter" checked id="blb_add_modal_self_close">
                                    <label for="blb_add_modal_self_close">Self Close</label>
                                </div>
                            </div>
                        </div>

                        <div class="text-left">
                            <!-- .sr-wraper-bold is to highlight element on focus -->
                            <div class="sr-wraper-bold">
                                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i>&nbsp;SAVE</button>
                            </div>
                            <!-- <div class="sr-wraper-bold">
                                <div class="sr-reset-btn btn btn-danger"><i class="fas fa-repeat-alt"></i> &nbsp;RESET</div>
                            </div> -->
                        </div>
                    </div>
                </div>












            </form>


        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->