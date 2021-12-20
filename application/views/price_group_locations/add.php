<div class="modal fade" tabindex="-1" role="dialog" id="pgpl_add_modal" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header bg-danger">
                <h4 class="modal-title sr-form-title has-default-value" data-default="ADD price_group" id="gridSystemModalLabel">ADD LOCATION TO PRICE GROUP</h4>
                <button title="Close (Esc)" type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>


            <form class="sr-form" role="form" id="pgpl_add_form" onsubmit="$(this).find(':submit').prop('disabled', true)" data-afterFormInit="">
                <div class="modal-body">
                    <div class="row">


                        <div class="col-12 col-md-6">


                            <div class="sr-wraper">
                                <div class="form-group">
                                    <label>Valid From:</label>
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend"><span class="input-group-text">From</div>
                                        <input type="text" name="pgpl_vf" id="pgpl_vf" class="sr-date-field-1 form-control form-control-sm" data-inputmask-alias="datetime" data-inputmask-inputformat="dd-mm-yyyy" data-mask>
                                    </div>
                                </div>
                            </div>

                            <div class="sr-wraper">
                                <div class="form-group">
                                    <label>Valid Up to:</label>
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend"><span class="input-group-text">To</div>
                                        <input type="text" name="pgpl_vt" id="pgpl_vt" class="sr-date-field-1 form-control form-control-sm" data-inputmask-alias="datetime" data-inputmask-inputformat="dd-mm-yyyy" data-mask>
                                    </div>
                                </div>
                            </div>




                            <div class="sr-wraper">
                                <div class="form-group">
                                    <label class="sr-mdt-lbl">Price Group</label>
                                    <div class="select2-sm">
                                        <select name="pgpl_fk_price_groups" class="pgp_option form-control form-control-sm select2 select2-danger" data-dropdown-css-class="select2-info" style="width: 100%;">
                                            <?= get_options($pgp_option) ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="sr-wraper">
                                <div class="form-group">
                                    <label>Central Store</label>
                                    <div class="select2-sm">
                                        <select name="cstr_id" class="cstr_option form-control form-control-sm select2 select2-danger" data-dropdown-css-class="select2-danger" style="width: 100%;">
                                            <?= get_options($cstr_option) ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">

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


                            <div class="sr-wraper">
                                <div class="form-group">
                                    <label>Area</label>
                                    <div class="select2-sm">
                                        <select name="ars_id" class="ars_option form-control form-control-sm select2 select2-danger" data-dropdown-css-class="select2-info" style="width: 100%;">
                                            <?= get_options() ?>
                                        </select>
                                    </div>
                                </div>
                            </div>


                            <div class="sr-wraper">
                                <div class="form-group">
                                    <label>Ward</label>
                                    <div class="select2-sm">
                                        <select name="wrd_id" class="wrd_option form-control form-control-sm select2 select2-danger" data-dropdown-css-class="select2-info" style="width: 100%;">
                                            <?= get_options() ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>



                </div>


                <div class="modal-footer clearfix d-block">
                    <input name="pgpl_id" type="hidden">

                    <div class="clearer">
                        <div class="float-left">
                            <div class="form-group clearfix">
                                <div class="sr-wraper sr-check icheck-danger d-inline">
                                    <!-- '.no-init' elements will be skipped from initialization of Form elements. So it keeps its final status -->
                                    <input type="checkbox" class="self-close no-init" checked id="pgpl_add_modal_self_close">
                                    <label for="pgpl_add_modal_self_close">Self Close</label>
                                </div>
                            </div>
                        </div>

                        <div class="float-right">
                            <!-- .sr-wraper-bold is to highlight element on focus -->
                            <div class="sr-wraper-bold">
                                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i>&nbsp;SAVE</button>
                            </div>
                            <div class="sr-wraper-bold">
                                <div class="sr-reset-btn btn btn-danger"><i class="fas fa-repeat-alt"></i> &nbsp;RESET</div>
                            </div>
                        </div>
                    </div>

                    <div class="o_errors"></div>
                </div>
            </form>
        </div>
    </div>
</div>