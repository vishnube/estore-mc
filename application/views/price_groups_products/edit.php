<div class="modal fade" tabindex="-1" role="dialog" id="pgprd_edit_modal" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h4 class="modal-title" id="gridSystemModalLabel">EDIT PRICE GROUP</h4>
                <button title="Close (Esc)" type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <form class="sr-form" role="form" id="pgprd_edit_form" onsubmit="$(this).find(':submit').prop('disabled', true)">

                <div class="prd-dt text-center mt-2"></div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col col-6">
                            <div class="sr-wraper">
                                <div class="form-group">
                                    <label for="recipient-name" class="control-label sr-mdt-lbl">Min-Qty:</label>
                                    <input name="pgprd_qty" type="text" class="form-control form-control-sm">
                                </div>
                            </div>
                        </div>
                        <div class="col col-6">
                            <div class="sr-wraper">
                                <div class="form-group">
                                    <label for="recipient-name" class="control-label sr-mdt-lbl">Unit:</label>
                                    <select name="pgprd_fk_unit_groups" class="pgprd_fk_unit_groups form-control form-control-sm"></select>
                                </div>
                            </div>
                        </div>
                        <div class="col col-4">
                            <div class="sr-wraper">
                                <div class="form-group">
                                    <label for="recipient-name" class="control-label sr-mdt-lbl">Discount:</label>
                                    <input name="pgprd_dsc" type="text" class="form-control form-control-sm">
                                </div>
                            </div>
                        </div>
                        <div class="col col-4">
                            <div class="sr-wraper">
                                <div class="form-group">
                                    <label for="recipient-name" class="control-label sr-mdt-lbl">Discount %:</label>
                                    <input name="pgprd_dscp" type="text" class="form-control form-control-sm">
                                </div>
                            </div>
                        </div>
                        <div class="col col-4">
                            <div class="sr-wraper">
                                <div class="form-group">
                                    <label for="recipient-name" class="control-label sr-mdt-lbl">Rate:</label>
                                    <input name="pgprd_rate" type="text" class="form-control form-control-sm">
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <div class="sr-wraper-bold">
                        <input type="hidden" name="pgprd_id">
                        <button type="submit" class="btn btn-primary save">SAVE</button>
                    </div>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->