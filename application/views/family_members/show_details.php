<?php
if (!(isset($after_ajax) && $after_ajax)) {
?>

    <div class="modal fade" tabindex="-1" role="dialog" id="family_member_details_modal">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content"></div>
        </div>
    </div>
<?php
} else {
?>



    <div class="card card-widget widget-user" style="box-shadow: none; margin-bottom: 0;background-color: #2b769a; color:#fff;">
        <!-- Add the bg color to the header using any of the bg-* classes -->
        <div class="modal-header" style="">
            <h3 class="modal-title"><?= $fmly_row['fmly_name'] ?>
                <div style="font-size: 15px;">Since: <?= date('d-M-Y', strtotime($fmly_row['mbr_date'])) ?></div>
            </h3>
            <button type="button" class="close" data-dismiss="modal" style="color:#fff;" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="card-footer p-0" style="background-color: #fff; color:#000;">
            <div class="row">
                <div class="col-12">

                    <table class="table text-nowrap  table-hover" id="tbl_fmlm">
                        <thead>
                            <tr>
                                <th style="width: 10px">#</th>
                                <th>NAME</th>
                                <th>CONTACT</th>
                                <th>JOIN DATE</th>
                                <th>BIRTH DATE</th>
                                <th>ACTIONS</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php
                            $slNo = 1;
                            if ($fmlm_data) {
                                foreach ($fmlm_data as $row) {
                                    $td_style = $row['fmlm_status'] == 2 ? 'opacity: 0.3' : '';

                            ?>

                                    <tr>
                                        <td style="<?= $td_style ?>"><?= $slNo++ ?></td>
                                        <td style="<?= $td_style ?>">
                                            <!-- member_id of Family Member -->
                                            <input type="hidden" class="fmlm_mbr_id" value="<?= $row['mbr_id'] ?>" ?>

                                            <!-- Family id of Family Member -->
                                            <input type="hidden" class="fmly_id" value="<?= $row['fmlm_fk_families'] ?>" ?>

                                            <!-- Name of the Member -->
                                            <input type="hidden" class="fmlm_name" value="<?= $row['fmlm_name'] ?>" ?>

                                            <?php
                                            echo $row['fmlm_name'];
                                            if ($row['fmlm_is_prime'] == 1)
                                                echo '&nbsp;<i title="Primary Member" class="nav-icon fad fa-house-return" style="--fa-primary-color:#FD9A00;--fa-secondary-color:#FD9A00;;"></i>';
                                            ?>
                                        </td>
                                        <td style="<?= $td_style ?>"><?= $row['fmlm_mob'] ?></td>
                                        <td style="<?= $td_style ?>"><?= date('d-M-Y', strtotime($row['mbr_date'])) ?></td>
                                        <td style="<?= $td_style ?>"><?= date('d-M-Y', strtotime($row['fmlm_dob'])) ?></td>
                                        <td>
                                            <?php
                                            if ($row['fmlm_status'] == ACTIVE) {
                                                if (has_task('tsk_fmly_edit'))
                                                    echo edit_btn('Member', array('.edit_fmlm'));
                                                if (has_task('tsk_fmly_deactivate'))
                                                    echo delete_btn('Member', array('.deactivate_fmlm'), 'Deactivate');
                                            } else if (has_task('tsk_fmly_activate'))
                                                echo activate_btn('Member', array('.activate_fmlm'));

                                            ?>
                                        </td>

                                    </tr>
                                <?php }
                            } else {
                                ?>
                                <tr>
                                    <td colspan="6">
                                        <h3 class="text-danger text-center"><i class="fas fa-exclamation-triangle fa-lg"></i><span class="pl-3">NO MEMBERS FOUND</span></h3>
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