<div class="modal fade" tabindex="-1" role="dialog" id="ugp_add_modal" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header bg-danger">
                <h4 class="modal-title" id="gridSystemModalLabel">ADD UNIT GROUP</h4>
                <button title="Close (Esc)" type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>


            <form class="sr-form" role="form" id="ugp_add_form" onsubmit="$(this).find(':submit').prop('disabled', true)">
                <div class="modal-body">




                    <div class="row dv-add-ugp">
                        <div class="col-12">
                            <div class="sr-wraper">
                                <div class="form-group">
                                    <label class="sr-mdt-lbl">Group Name</label>
                                    <input type="text" name="ugp_name" class="form-control form-control-sm">
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <table class="table sr-input-movement tbl-add-ugp" id="" data-afrNew="on_new_ugp_add_row_created" data-afrRem="on_ugp_row_delted" data-onInit="initUnitGroupTable">
                                <thead>
                                    <tr>
                                        <th style="width: 5px;">#</th>
                                        <th style="width: 35%;">UNIT</th>
                                        <th style="width: 40%;">RELATION</th>
                                        <th>is Default</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="sr-movement-row">
                                        <td>
                                            <i class="fal fa-times-circle rem cursor-pointer" title="Delete"></i>
                                        </td>
                                        <td>


                                            <div class="input-group input-group-sm mb-3">
                                                <div class="input-group-prepend one-span">
                                                    <span class="input-group-text bg-info">1</span>
                                                </div>
                                                <select name="ugp_fk_units[]" class="ugp_fk_units unt_option next-input enter-lock  form-control form-control-sm">
                                                    <?= get_options($unt_option) ?>
                                                </select>
                                            </div>


                                        </td>
                                        <td>
                                            <div class="input-group  input-group-sm mb-3 rel-container">
                                                <input type="text" name="ugp_rel[]" class="ugp_rel next-input enter-lock form-control form-control-sm">
                                                <div class="input-group-append">
                                                    <span class="input-group-text bg-info basic_unt_txt"></span>
                                                </div>
                                            </div>
                                        </td>

                                        <td>
                                            <select data-default="1" name="ugp_default[]" class="ugp_default next-input last-input enter-lock form-control form-control-sm">
                                                <option value="1">Yes</option>
                                                <option value="2">No</option>
                                            </select>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>








                </div>



                <div class="modal-footer d-block">

                    <div class="">
                        <div>
                            <div class="form-group clearfix">
                                <div class="sr-wraper sr-check icheck-danger d-inline">
                                    <!-- '.no-init' elements will be skipped from initialization of Form elements. So it keeps its final status -->
                                    <input type="checkbox" class="self-close no-init no-enter" checked id="ugp_add_modal_self_close">
                                    <label for="ugp_add_modal_self_close">Self Close</label>
                                </div>
                            </div>
                        </div>

                        <div class="clearfix">
                            <div class="float-left sr-wraper-bold">
                                <div class="sr-reset-btn btn btn-danger" onclick="$('.tbl-add-ugp').initMovementTable()"><i class="fas fa-repeat-alt"></i> &nbsp;RESET</div>
                            </div>
                            <div class="float-right sr-wraper-bold">
                                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i>&nbsp;SAVE</button>
                            </div>
                        </div>
                    </div>
                </div>



            </form>


        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->