<div class="modal fade" tabindex="-1" role="dialog" id="pct_add_modal" data-backdrop="static">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h4 class="modal-title" id="gridSystemModalLabel">ADD CATEGORY</h4>
                <button title="Close (Esc)" type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <form class="sr-form" role="form" id="pct_add_form" onsubmit="$(this).find(':submit').prop('disabled', true)" data-afterFormInit="reset_pct">
                <div class="modal-body">
                    <div class="parent-dv">
                        <div class="sr-wraper">
                            <div class="form-group">
                                <label>Category Head</label>
                                <div class="input-group mb-3 input-group-sm form-group">
                                    <div class="input-group-prepend">
                                        <button type="button" class="btn btn-danger reset-pctopt">Reset</button>
                                    </div>
                                    <select class="pct_parent_selector pct_option form-control form-control-sm">
                                        <?= get_options($pct_option, '', 'Select Category', true, false, 'No Categories') ?>
                                    </select>
                                </div>
                            </div>
                            <div class="parents m-1 p-1"></div>
                        </div>
                    </div>

                    <div class="sr-wraper">
                        <div class="form-group">
                            <label for="recipient-name" class="control-label sr-mdt-lbl">Name:</label>
                            <input name="pct_name" type="text" class="form-control form-control-sm">
                        </div>
                    </div>
                    <input type="hidden" name="pct_id">
                </div>
                <div class="modal-footer">
                    <div class="sr-wraper-bold">
                        <button type="submit" tabindex="2" class="btn btn-primary save">SAVE</button>
                    </div>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->