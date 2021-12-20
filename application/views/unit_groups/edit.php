<div class="modal fade" tabindex="-1" role="dialog" id="ugp_edit_modal" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header bg-danger">
                <h4 class="modal-title" id="gridSystemModalLabel">EDIT UNIT GROUP</h4>
                <button title="Close (Esc)" type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>


            <form class="sr-form" role="form" id="ugp_edit_form" onsubmit="$(this).find(':submit').prop('disabled', true)">
                <div class="modal-body">




                    <div class="row dv-edit-ugp">
                        <div class="col-12">
                            <div class="sr-wraper">
                                <div class="form-group">
                                    <label class="sr-mdt-lbl">Group Name</label>
                                    <input type="text" name="ugp_name" class="form-control form-control-sm">
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <table class="table tbl-edit-ugp">
                                <caption class="text-danger">
                                    <i class="fad fa-exclamation-triangle" style="font-size: 30px;"></i>
                                    Editing <b>RELATION</b> will affect clossing stock calculation
                                </caption>
                                <thead>
                                    <tr>
                                        <th style="width: 30%;">UNIT</th>
                                        <th style="width: 30%;">RELATION</th>
                                        <th>is Default</th>
                                    </tr>
                                </thead>
                                <tbody>

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
                                    <input type="checkbox" class="self-close no-init no-enter" checked id="ugp_edit_modal_self_close">
                                    <label for="ugp_edit_modal_self_close">Self Close</label>
                                </div>
                            </div>
                        </div>

                        <div class="clearfix">
                            <div class="float-left sr-wraper-bold">
                                <div class="sr-reset-btn btn btn-danger" onclick="$('.tbl-edit-ugp').initMovementTable()"><i class="fas fa-repeat-alt"></i> &nbsp;RESET</div>
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