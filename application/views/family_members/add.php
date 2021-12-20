<div class="modal fade" tabindex="-1" role="dialog" id="fmlm_add_modal" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header bg-danger">
                <h4 class="modal-title sr-form-title has-default-value" data-default="ADD MEMBER" id="gridSystemModalLabel">ADD MEMBER</h4>
                <button title="Close (Esc)" type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <h5 class="text-center my-2" id="fmly_name"></h5>
            <form class="sr-form" role="form" id="fmlm_add_form" onsubmit="$(this).find(':submit').prop('disabled', true)" data-afterFormInit="">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="sr-wraper">
                                <div class="form-group">
                                    <label class="sr-mdt-lbl">Name</label>
                                    <input type="text" name="fmlm_name" class="form-control form-control-sm">
                                </div>
                            </div>
                            <div class="sr-wraper">
                                <div class="form-group">
                                    <label>Contact</label>
                                    <input type="text" name="fmlm_mob" class="form-control form-control-sm">
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="sr-wraper">
                                <div class="form-group">
                                    <label>Birth Date:</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                        </div>
                                        <input type="text" name="fmlm_dob" class="sr-date-field-1 form-control form-control-sm" data-inputmask-alias="datetime" data-inputmask-inputformat="dd-mm-yyyy" data-mask>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group clearfix">
                                <label class="sr-label">is Primary</label><br>
                                <div class="sr-wraper sr-check icheck-warning d-inline">
                                    <input data-default="false" name="fmlm_is_prime" type="checkbox" id="fmlm_is_prime_id">
                                    <label for="fmlm_is_prime_id">Primary Member</label>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>


                <div class="modal-footer clearfix d-block">
                    <input name="mbr_id" type="hidden">
                    <input name="fmlm_fk_families" id="fmlm_fk_families" type="hidden">

                    <div class="clearer">
                        <div class="float-left">
                            <div class="sr-wraper-bold">
                                <div class="btn btn-danger fmlm-back-btn"><i class="fas fa-backspace"></i> &nbsp;BACK</div>
                            </div>
                        </div>

                        <div class="float-right">
                            <!-- .sr-wraper-bold is to highlight element on focus -->
                            <div class="sr-wraper-bold">
                                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i>&nbsp;SAVE</button>
                            </div>

                            <!-- It should be hidden, because on reset $('#fmlm_fk_families') value will be cleared -->
                            <!-- <div class="sr-wraper-bold">
                                <div class="sr-reset-btn btn btn-danger fmlm-reset-btn"><i class="fas fa-repeat-alt"></i> &nbsp;RESET</div>
                            </div> -->
                        </div>
                    </div>

                    <div class="o_errors"></div>
                </div>
            </form>
        </div>
    </div>
</div>