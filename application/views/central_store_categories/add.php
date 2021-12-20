<div class="modal fade" tabindex="-1" role="dialog" id="cstr_cat_add_modal" data-backdrop="static">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h4 class="modal-title" id="gridSystemModalLabel">ADD CATEGORY</h4>
                <button title="Close (Esc)" type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <form class="sr-form" role="form" id="cstr_cat_add_form" onsubmit="$(this).find(':submit').prop('disabled', true)">
                <div class="modal-body">
                    <div class="sr-wraper">
                        <div class="form-group">
                            <label for="recipient-name" class="control-label sr-mdt-lbl">Name:</label>
                            <input name="cat_name" type="text" class="form-control form-control-sm">
                        </div>
                    </div>
                    <input type="hidden" name="cat_id">
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