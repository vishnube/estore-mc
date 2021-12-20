<div class="modal fade" tabindex="-1" role="dialog" id="ars_add_modal" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header bg-danger">
                <h4 class="modal-title sr-form-title has-default-value" data-default="ADD AREA" id="gridSystemModalLabel">ADD AREA</h4>
                <button title="Close (Esc)" type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>


            <form class="sr-form" role="form" id="ars_add_form" onsubmit="$(this).find(':submit').prop('disabled', true)" data-afterFormInit="afterArsAddFormReset">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-6">

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
                                    <label class="sr-mdt-lbl">Taluk</label>
                                    <div class="select2-sm">
                                        <select name="ars_fk_taluks" class="tlk_option form-control form-control-sm select2 select2-danger" data-dropdown-css-class="select2-info" style="width: 100%;">
                                            <?= get_options() ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">


                            <div class="sr-wraper">
                                <div class="form-group">
                                    <label class="sr-mdt-lbl">Central Store</label>
                                    <div class="select2-sm">
                                        <select name="ars_fk_central_stores" class="cstr_option form-control form-control-sm select2 select2-danger" data-dropdown-css-class="select2-danger" style="width: 100%;">
                                            <?= get_options($cstr_option) ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="sr-wraper">
                                <div class="form-group">
                                    <!-- '.sr-mdt-lbl' describes the label of a mandatory field -->
                                    <label class="sr-mdt-lbl">Area Name</label>

                                    <input type="text" name="ars_name" id="ars_name" class="form-control form-control-sm" placeholder="">
                                </div>
                            </div>


                            <div class="sr-wraper">
                                <div class="form-group">
                                    <label class="sr-mdt-lbl">Area Type</label>
                                    <div class="select2-sm">
                                        <select name="ars_type" class="form-control form-control-sm select2 select2-danger" data-dropdown-css-class="select2-info" style="width: 100%;">
                                            <?= get_options(get_area_types()) ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>





                </div>


                <div class="modal-footer clearfix d-block">
                    <input name="ars_id" type="hidden">

                    <div class="clearer">
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