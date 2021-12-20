<?php
if (!(isset($after_ajax) && $after_ajax)) {
?>
    <div class="modal fade" id="central_store_details_modal">
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


    <div class="row mt-3 mb-3" style="font-size: 20px;">
        <div class="col-6 text-center">
            <i class="far fa-map-marker-alt text-orange"></i> Lat: <?= $cstr_lat ?>
        </div>
        <div class="col-6 text-center">
            <i class="far fa-map-marker-alt text-pink"></i> Log: <?= $cstr_log ?>
        </div>
    </div>


    <div class="row">
        <div class="col-12 col-md-6">
            <div class="border border-secondary mb-3 rounded">
                <div class="bg-secondary text-center show-dt-title rounded-top">Address 1</div>
                <div class="show-dt-data rounded-bottom p-2"><?= $cstr_address1 ? nl2br($cstr_address1) : '<div class="text-center"><i class="fad fa-question"></i></div>' ?></div>
            </div>

            <div class="border border-secondary mb-3 rounded">
                <div class="bg-secondary text-center show-dt-title rounded-top">Address 2</div>
                <div class="show-dt-data rounded-bottom p-2"><?= $cstr_address2 ? nl2br($cstr_address2) : '<div class="text-center"><i class="fad fa-question"></i></div>' ?></div>
            </div>

            <div class="border border-secondary mb-3 rounded">
                <div class="bg-secondary text-center show-dt-title rounded-top">Landmark</div>
                <div class="show-dt-data rounded-bottom p-2"><?= $cstr_landmark ? $cstr_landmark : '<div class="text-center"><i class="fad fa-question"></i></div>' ?></div>
            </div>

            <div class="border border-secondary mb-3 rounded">
                <div class="bg-secondary text-center show-dt-title rounded-top">Pin</div>
                <div class="show-dt-data rounded-bottom p-2"><?= $cstr_pin ? $cstr_pin : '<div class="text-center"><i class="fad fa-question"></i></div>' ?></div>
            </div>
        </div>
        <div class="col-12 col-md-6">

            <div class="border border-secondary mb-3 rounded">
                <div class="bg-secondary text-center show-dt-title rounded-top">Building Owner</div>
                <div class="show-dt-data rounded-bottom p-2"><?= $cstr_bownr ? $cstr_bownr : '<div class="text-center"><i class="fad fa-question"></i></div>' ?></div>
            </div>

            <div class="border border-secondary mb-3 rounded">
                <div class="bg-secondary text-center show-dt-title rounded-top">Contact</div>
                <div class="show-dt-data rounded-bottom p-2">
                    <?php
                    if (!$cstr_bownr_mob1 && !$cstr_bownr_mob2)
                        echo '<div class="text-center"><i class="fad fa-question"></i></div>';
                    else {
                        echo $cstr_bownr_mob1 ? $cstr_bownr_mob1 : '';
                        if ($cstr_bownr_mob2)
                            echo "<br>$cstr_bownr_mob2";
                    }
                    ?>

                </div>
            </div>

            <div class="border border-secondary mb-3 rounded">
                <div class="bg-secondary text-center show-dt-title rounded-top">Rent/Month</div>
                <div class="show-dt-data rounded-bottom p-2"><?= $cstr_rent ? $cstr_rent : '<div class="text-center"><i class="fad fa-question"></i></div>' ?></div>
            </div>


            <div class="border border-secondary mb-3 rounded">
                <div class="bg-secondary text-center show-dt-title rounded-top">Advance Paid</div>
                <div class="show-dt-data rounded-bottom p-2"><?= $cstr_adv ? $cstr_adv : '<div class="text-center"><i class="fad fa-question"></i></div>' ?></div>
            </div>

            <div class="border border-secondary mb-3 rounded">
                <div class="bg-secondary text-center show-dt-title rounded-top">Area</div>
                <div class="show-dt-data rounded-bottom p-2"><?= $cstr_sqft ? $cstr_sqft : '<div class="text-center"><i class="fad fa-question"></i></div>' ?> / SqFt</div>
            </div>
        </div>
        <div class="col-12 col-md-6">


            <div class="border border-secondary mb-3 rounded">
                <div class="bg-secondary text-center show-dt-title rounded-top">Local Police</div>
                <div class="show-dt-data rounded-bottom p-2"><?= $cstr_police ? $cstr_police : '<div class="text-center"><i class="fad fa-question"></i></div>' ?></div>
            </div>

            <div class="border border-secondary mb-3 rounded">
                <div class="bg-secondary text-center show-dt-title rounded-top">Fire Station</div>
                <div class="show-dt-data rounded-bottom p-2"><?= $cstr_fire ? $cstr_fire : '<div class="text-center"><i class="fad fa-question"></i></div>' ?></div>
            </div>


        </div>
    </div>


<?php
}
?>