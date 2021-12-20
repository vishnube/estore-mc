<?php
if (!(isset($after_ajax) && $after_ajax)) {
?>

    <div class="modal fade" id="party_details_modal">
        <div class="modal-dialog">
            <div class="modal-content"></div>
        </div>
    </div>
<?php
} else {
?>




    <div class="modal-header" style="background-color: #265d8d; color:#fff;">
        <h4 class="modal-title">
            <input type="hidden" class="mbr_id" value="<?= $mbr_id ?>">
            <?= $mbr_name ?>
            <div style="font-size: 15px;">Since: <?= date('d-M-Y', strtotime($mbr_date)) ?></div>
        </h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true" style="color:#fff;">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-12">
                <i class="far fa-address-card text-pink mr-3" style="font-size: 22px;"></i>
                <!-- Replacing Newlines, spaces in address with comma -->
                <?= str_replace(array("\r\n", "\r", "\n"), ", ", implode(', ', $addr)); ?>
            </div>
            <div class="col-12 border-top border-warning mt-2 pt-2">
                <i class="fas fa-phone-volume text-warning mr-3" style="font-size: 22px;"></i>
                <?= implode(', ', $contacts) ?>
            </div>
        </div>
    </div>
    <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-danger edit_mbr"><i class="fas fa-pencil-alt cursor-pointer"></i> Edit</button>
    </div>
<?php } ?>