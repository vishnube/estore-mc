<?php
if (!(isset($after_ajax) && $after_ajax)) {
?>

    <style type="text/css">
        .simple-box {
            display: block;
            margin: 10px 0;
            margin-top: 10px;
            text-align: left;
            padding-left: 15px;
        }

        .simple-box i {
            font-size: 60px;
        }

        .simple-box .simple-header {
            font-size: 16px;
            font-weight: 600;
            margin: 0;
            padding: 0;
            text-align: left;
        }
    </style>

    <div class="modal fade" tabindex="-1" role="dialog" id="family_details_modal">
        <div class="modal-dialog" role="document">

            <div class="modal-content"></div>
        </div>
    </div>
<?php
} else {
?>

    <div class="card card-widget widget-user" style="box-shadow: none; margin-bottom: 0;">
        <!-- Add the bg color to the header using any of the bg-* classes -->
        <div class="bg-info modal-header">
            <h3 class="modal-title"><?= $mbr_name ?>
                <div style="font-size: 15px;">Since: <?= date('d-M-Y', strtotime($mbr_date)) ?></div>
            </h3>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="card-footer" style="padding-top: 10px;">
            <div class="row mt-3 mb-3" style="font-size: 20px;">
                <div class="col-6 text-center">
                    <i class="far fa-map-marker-alt text-orange"></i> Lat: <?= $fmly_lat ?>
                </div>
                <div class="col-6 text-center">
                    <i class="far fa-map-marker-alt text-pink"></i> Log: <?= $fmly_log ?>
                </div>
            </div>

            <div class="row">
                <div class="col-4 clearer border border-right-0">
                    <div class="simple-box mt-3 text-center">
                        <i class="fad fa-plug fa-2x text-primary"></i>
                    </div>
                    <div class="simple-box">
                        <h5 class="simple-header mt-1 text-center text-primary">ADDRESS</h5>
                        <span class="text-left"><?= nl2br($fmly_address) ?></span>
                    </div>
                </div>

                <div class="col-4 clearer border">
                    <div class="simple-box mt-3 text-center">
                        <i class="fad fa-landmark fa-2x text-success"></i>
                    </div>
                    <div class="simple-box">
                        <h5 class="simple-header mt-1 text-center text-success">LANDMARK</h5>
                        <span class="text-left"><?= $fmly_landmark ?></span>
                    </div>
                </div>

                <div class="col-4 clearer border  border-left-0">
                    <div class="simple-box mt-3 text-center">
                        <i class="fad fa-id-badge fa-2x text-danger"></i>
                    </div>
                    <div class="simple-box">
                        <h5 class="simple-header mt-1 text-center text-danger">HOUSE No:</h5>
                        <span class="text-left"><?= $fmly_no ?></span>
                    </div>
                </div>

            </div>




        </div>
    </div>

<?php
}
?>