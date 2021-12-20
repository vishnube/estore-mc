<?php
if (!(isset($after_ajax) && $after_ajax)) {
?>

    <div class="modal fade" tabindex="-1" role="dialog" id="gstnumber_details_modal">
        <div class="modal-dialog" role="document">
            <div class="modal-content"></div>
        </div>
    </div>
<?php
} else {
?>



    <div class="card card-widget widget-user" style="box-shadow: none; margin-bottom: 0;background-color: #67660e; color:#fff;">
        <!-- Add the bg color to the header using any of the bg-* classes -->
        <div class="modal-header" style="">
            <h3 class="modal-title"><?= $mbr_row['mbr_name'] ?> </h3>
            <button type="button" class="close" data-dismiss="modal" style="color:#fff;" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="card-footer p-0" style="background-color: #fff; color:#000;">
            <div class="row">
                <div class="col-12">

                    <table class="table text-nowrap  table-hover" id="tbl_gst">
                        <thead>
                            <tr>
                                <th style="width: 10px">#</th>
                                <th>GSTIN</th>
                                <th>STATE</th>
                                <th>ACTIONS</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php
                            $slNo = 1;
                            if ($gst_data) {
                                foreach ($gst_data as $row) {
                                    $td_style = $row['gst_status'] == 2 ? 'opacity: 0.3' : '';

                            ?>

                                    <tr>
                                        <td style="<?= $td_style ?>"><?= $slNo++ ?></td>
                                        <td style="<?= $td_style ?>">
                                            <input type="hidden" class="gst_id" value="<?= $row['gst_id'] ?>" ?>
                                            <input type="hidden" class="mbr_id" value="<?= $row['gst_fk_members'] ?>" ?>
                                            <input type="hidden" class="gst_name" value="<?= $row['gst_name'] ?>" ?>

                                            <?php
                                            echo $row['gst_name'];
                                            if ($row['gst_default'] == 1)
                                                echo '&nbsp;<i class="fal fa-check text-success" title="Default GST"></i>';
                                            ?>
                                        </td>
                                        <td style="<?= $td_style ?>"><?= get_GST_state_codes($row['gst_fks_states']) ?></td>
                                        <td>
                                            <?php
                                            if ($row['gst_status'] == ACTIVE) {
                                                echo edit_btn('GST Details', array('.edit_gst'));
                                                echo delete_btn('GST Details', array('.deactivate_gst'), 'Deactivate');
                                            } else
                                                echo activate_btn('GST Details', array('.activate_gst'));

                                            ?>
                                        </td>

                                    </tr>
                                <?php }
                            } else {
                                ?>
                                <tr>
                                    <td colspan="4">
                                        <h3 class="text-danger text-center"><i class="fas fa-exclamation-triangle fa-lg"></i><span class="pl-3">NO GST DETAILS FOUND</span></h3>
                                    </td>
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>

<?php
}
?>