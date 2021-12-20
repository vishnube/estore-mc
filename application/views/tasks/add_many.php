<div class="modal fade" tabindex="-1" role="dialog" id="add_many_modal" data-backdrop="static">
    <div class="modal-dialog" role="document" style="max-width: 600px;">
        <div class="modal-content">

            <div class="modal-header bg-danger">
                <h4 class="modal-title" id="gridSystemModalLabel">ADD CATEGORY</h4>
                <button title="Close (Esc)" type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>


            <form class="sr-form" role="form" id="add_many_form" onsubmit="$(this).find(':submit').prop('disabled', true)">
                <div class="modal-body">

                    <div class="sr-wraper">
                        <div class="form-group">
                            <label>Table prefix</label>
                            <input type="text" id="tbl_prefix" class="form-control form-control-sm" placeholder="emply">
                        </div>
                    </div>



                    <table class="table sr-input-movement tbl-addmay" id="" data-radFormat="true">
                        <thead>
                            <tr>
                                <th style="width:10px;">#</th>
                                <th style="width:150px;">Name</th>
                                <th>Key</th>
                                <th>Menu</th>
                                <th>For Whome</th>
                            </tr>
                        </thead>

                        <?php
                        $a[] = array('tsk_name' => 'List', 'sufix' => 'list');
                        $a[] = array('tsk_name' => 'Add', 'sufix' => 'add');
                        $a[] = array('tsk_name' => 'Edit', 'sufix' => 'edit');
                        $a[] = array('tsk_name' => 'Activate', 'sufix' => 'activate');
                        $a[] = array('tsk_name' => 'Deactivate', 'sufix' => 'deactivate');
                        $a[] = array('tsk_name' => 'Document PDF', 'sufix' => 'pdf');
                        $a[] = array('tsk_name' => 'Document Excel', 'sufix' => 'excel');
                        $a[] = array('tsk_name' => 'Document Print', 'sufix' => 'print');
                        $a[] = array('tsk_name' => 'Configurations', 'sufix' => 'conf');

                        ?>

                        <tbody>
                            <?php
                            foreach ($a as $r) {
                            ?>
                                <tr class="sr-movement-row">
                                    <td>
                                        <i class="fal fa-times-circle rem cursor-pointer" title="Delete"></i>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input type="text" data-default="<?= $r['tsk_name'] ?>" class="tsk_name next-input enter-lock key form-control form-control-sm">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input type="text" data-sufix="<?= $r['sufix'] ?>" class="tsk_key next-input last-input val enter-lock form-control form-control-sm">
                                        </div>
                                    </td>
                                    <td>
                                        <!-- data-default="true" is used to initialize the form.-->
                                        <input data-default="false" class="tsk_menu" type="checkbox" id="checkboxPrimary1">
                                    </td>
                                    <td>
                                        <!-- data-default="true" is used to initialize the form.-->
                                        <select data-default="2" class="tsk_type sr-no-empty-vals form-control form-control-sm">
                                            <option value="1">For Developer</option>
                                            <option value="2" selected="">For All</option>
                                        </select>
                                    </td>
                                </tr>
                            <?php
                            }
                            ?>

                        </tbody>
                    </table>











                    <input type="hidden" id="parent_id">
                </div>
                <div class="modal-footer">
                    <!-- .sr-wraper-bold is to highlight element on focus -->
                    <div class="sr-wraper-bold">
                        <button type="submit" tabindex="2" class="btn btn-primary save">SAVE</button>
                    </div>
                </div>
            </form>


            <!-- The query will be shown here -->
            <textarea class="mt-2 mb-2 query"></textarea>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->