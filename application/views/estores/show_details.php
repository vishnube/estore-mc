<?php
if (!(isset($after_ajax) && $after_ajax)) {
?>
    <div class="modal fade" id="estore_details_modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success">
                    <h4 class="modal-title"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body"></div>
                <div class="modal-footer justify-content-between bg-success">
                    <button type="button" class="btn btn-default  ml-auto" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
<?php
} else {
?>

    <div class="row">
        <div class="col-12 col-md-6">
            <div class="border border-secondary mb-3 rounded">
                <div class="bg-secondary text-center show-dt-title rounded-top">Address 1</div>
                <div class="show-dt-data rounded-bottom p-2"><?= $estr_address1 ? nl2br($estr_address1) : '<div class="text-center"><i class="fad fa-question"></i></div>' ?></div>
            </div>

            <div class="border border-secondary mb-3 rounded">
                <div class="bg-secondary text-center show-dt-title rounded-top">Address 2</div>
                <div class="show-dt-data rounded-bottom p-2"><?= $estr_address2 ? nl2br($estr_address2) : '<div class="text-center"><i class="fad fa-question"></i></div>' ?></div>
            </div>

            <div class="border border-secondary mb-3 rounded">
                <div class="bg-secondary text-center show-dt-title rounded-top">Pincode</div>
                <div class="show-dt-data rounded-bottom p-2"><?= $estr_pin ? $estr_pin : '<div class="text-center"><i class="fad fa-question"></i></div>' ?></div>
            </div>

            <div class="border border-secondary mb-3 rounded">
                <div class="bg-secondary text-center show-dt-title rounded-top">Close Relative</div>
                <div class="show-dt-data rounded-bottom p-2">
                    <?php
                    if (!$estr_clsrel && !$estr_clsrel_mob)
                        echo '<div class="text-center"><i class="fad fa-question"></i></div>';
                    else {
                        echo '<div>' . $estr_clsrel . '</div>';
                        echo '<div>' . $estr_clsrel_mob . '</div>';
                    }
                    ?>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6">


            <div class="border border-secondary mb-3 rounded">
                <div class="bg-secondary text-center show-dt-title rounded-top">Family</div>
                <div class="show-dt-data rounded-bottom p-2">
                    <?php
                    if (!$estr_fmly && !$estr_fmly_mob)
                        echo '<div class="text-center"><i class="fad fa-question"></i></div>';
                    else {
                        echo '<div>' . $estr_fmly . '</div>';
                        echo '<div>' . $estr_fmly_mob . '</div>';
                    }
                    ?>
                </div>
            </div>


            <div class="border border-secondary mb-3 rounded">
                <div class="bg-secondary text-center show-dt-title rounded-top">Bank</div>
                <div class="show-dt-data rounded-bottom p-2">
                    <?php
                    if (!$estr_bank_name && !$estr_bank_acc)
                        echo '<div class="text-center"><i class="fad fa-question"></i></div>';
                    else {
                        echo '<div>Bank: ' . $estr_bank_name . '</div>';
                        echo '<div>A/c No: ' . $estr_bank_acc . '</div>';
                        echo '<div>IFSC: ' . $estr_ifsc . '</div>';
                    }
                    ?>
                </div>
            </div>


            <div class="border border-secondary mb-3 rounded">
                <div class="bg-secondary text-center show-dt-title rounded-top">UID</div>
                <div class="show-dt-data rounded-bottom p-2"><?= $estr_uid ? $estr_uid : '<div class="text-center"><i class="fad fa-question"></i></div>' ?></div>
            </div>

        </div>

    </div>


<?php
}
?>