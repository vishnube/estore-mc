<div class="modal fade" tabindex="-1" role="dialog" id="punt_add_modal" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header bg-danger">
                <h4 class="modal-title" id="gridSystemModalLabel">ADD UNIT GROUP</h4>
                <button title="Close (Esc)" type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>


            <form class="sr-form" role="form" id="punt_add_form" onsubmit="$(this).find(':submit').prop('disabled', true)">
                <div class="modal-body">
                    <h3 class="text-primary prd_name text-center"></h3>

                    <!-- Only sto show validation errors related to punt_group_no -->
                    <h5 class="val_error_group_no_required"></h5>
                    <div class="table-responsive" id="tbl_punt_add_container" style="height: 400px;">
                        <table class="table table-head-fixed  text-nowrap  table-hover" id="tbl_punt_add">
                            <tbody>
                                <!-- 
                                Content will be loaded here by 
                                afterLoadUnitGroups() @ unit_groups\list.js  and 
                                createProductUnitAddTable() @ product_units\list.js -->
                            </tbody>
                        </table>
                        <input type="hidden" name="punt_fk_products" class="punt_fk_products">
                    </div>
                </div>



                <div class="modal-footer d-block">

                    <div class="">
                        <div>
                            <div class="form-group clearfix">
                                <div class="sr-wraper sr-check icheck-danger d-inline">
                                    <!-- '.no-init' elements will be skipped from initialization of Form elements. So it keeps its final status -->
                                    <input type="checkbox" class="self-close no-init no-enter" checked id="punt_add_modal_self_close">
                                    <label for="punt_add_modal_self_close">Self Close</label>
                                </div>
                            </div>
                        </div>

                        <div class="clearfix">
                            <div class="float-left sr-wraper-bold">
                                <div class="sr-reset-btn btn btn-danger"><i class="fas fa-repeat-alt"></i> &nbsp;RESET</div>
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