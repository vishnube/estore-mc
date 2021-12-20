<div class="modal fade" tabindex="-1" role="dialog" id="stct_add_modal" data-backdrop="static">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">

            <div class="modal-header bg-danger">
                <h4 class="modal-title" id="gridSystemModalLabel">ADD SETTINGS CATEGORY</h4>
                <button title="Close (Esc)" type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>


            <form class="sr-form" role="form" id="stct_add_form" onsubmit="$(this).find(':submit').prop('disabled', true)">
                <div class="modal-body">
                    <!-- .sr-wraper is to highlight element on focus -->
                    <div class="sr-wraper">
                        <div class="form-group">
                            <!-- '.sr-mdt-lbl' describes the label of a mandatory field -->
                            <label for="recipient-name" class="control-label sr-mdt-lbl">Name:</label>
                            <input name="stct_name" type="text" class="form-control form-control-sm">
                        </div>
                    </div>
                    <div class="sr-wraper">
                        <div class="form-group">
                            <label class="control-label">Sort Position</label>
                            <input name="stct_sort" type="text" class="form-control form-control-sm">
                        </div>
                    </div>

                    <input type="hidden" name="stct_id">

                </div>
                <div class="modal-footer">
                    <!-- .sr-wraper-bold is to highlight element on focus -->
                    <div class="sr-wraper-bold">
                        <button type="submit" class="btn btn-primary save"><i class="fas fa-save"></i>&nbsp;SAVE</button>
                    </div>
                </div>
                <!-- The query will be shown here -->
                <div style="padding:3px;"><textarea class="mt-2 query"></textarea></div>
            </form>


        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->