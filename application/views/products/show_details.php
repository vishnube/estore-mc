<?php
if (!(isset($after_ajax) && $after_ajax)) {
?>

    <style type="text/css">
        .prd-max {
            width: 30%;
            display: inline-block;
            font-weight: bold;
            padding: 3px;
        }

        .nav-max {
            display: inline-block;
            width: 100%;
            border-bottom: 1px solid #ededed;
        }
    </style>

    <div class="modal fade" tabindex="-1" role="dialog" id="product_details_modal">
        <div class="modal-dialog" role="document">
            <div class="modal-content"></div>
        </div>
    </div>
<?php
} else {
?>

    <div class="card card-widget widget-user" style="box-shadow: none; margin-bottom: 0;background-color: #682937; color:#fff;">
        <!-- Add the bg color to the header using any of the bg-* classes -->
        <div class="modal-header">
            <h3 class="modal-title"><?= $prd_name ?>
                <div style="font-size: 15px;">Category: <?= $cats . '<i class="far fa-chevron-double-right mx-1" style="font-size:8px"></i><u><b>' . $cat_name . '</b></u>' ?></div>
            </h3>
            <button type="button" class="close" data-dismiss="modal" style="color:#fff;" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="card-footer" style="background-color: #fff; color:#000;padding-top: 5px;">



            <?php
            if ($photos) {
                echo '<div class="row mt-2 mb-2">';
                foreach ($photos as $p) {
                    echo '<div class="col-6 col-sm-3 col-lg-4">';
                    echo ' <img src="' . "$upload_dir/$p[prdpt_name]" . '" alt="Product Image" width="100" height="150"> ';
                    echo '</div>';
                }
                echo '</div>';
            }
            ?>
            <div class="row">

                <div class="col-12 col-lg-6">
                    <h4 class="bg-info text-center">BASIC DETAILS</h4>
                    <ul class="nav flex-column">
                        <li class="nav-item p-2">
                            <?= ($prd_organic == 1 ? '<i class="fal fa-check text-success" style="font-size: 20px;"></i>' : '<i class="fal fa-times text-danger" style="font-size: 20px;"></i>') ?>
                            <span class="pl-2">ORGANIC</span>
                        </li>
                        <li class="nav-item p-2">
                            <?= ($prd_batch == 1 ? '<i class="fal fa-check text-success" style="font-size: 20px;"></i>' : '<i class="fal fa-times text-danger" style="font-size: 20px;"></i>') ?>
                            <span class="pl-2">BATCH PRODUCT</span>
                        </li>
                        <li class="nav-item p-2">
                            <?= ($prd_varient == 1 ? '<i class="fal fa-check text-success" style="font-size: 20px;"></i>' : '<i class="fal fa-times text-danger" style="font-size: 20px;"></i>') ?>
                            <span class="pl-2">VARIENTS</span>
                        </li>
                        <li class="nav-item p-2">
                            <?= ($prd_zeta == 1 ? '<i class="fal fa-check text-success" style="font-size: 20px;"></i>' : '<i class="fal fa-times text-danger" style="font-size: 20px;"></i>') ?>
                            <span class="pl-2">ZETA / SODEX</span>
                        </li>
                    </ul>
                </div>

                <div class="col-12 col-lg-6">
                    <h4 class="bg-info text-center">TAGS</h4>
                    <ul class="nav flex-column">
                        <?php
                        foreach ($tg_ids as $t)
                            echo '<li class="nav-item p-2"><span class="">' . $t . '</span></li>';
                        ?>
                    </ul>
                </div>

            </div>


            <div class="row">
                <div class="col-12">
                    <h4 class="bg-info text-center">MORE DETAILS</h4>
                    <ul class="nav">
                        <li class="nav-item nav-max">
                            <span class="prd-max">Company:</span> <span class=""><?= $cmp_name ?></span>
                        </li>
                        <li class="nav-item nav-max">
                            <span class="prd-max">Brand:</span> <span class=""><?= $brnd_name ?></span>
                        </li>
                        <li class="nav-item nav-max">
                            <span class="prd-max">Made in:</span> <span class=""><?= $prd_madein ?></span>
                        </li>
                        <li class="nav-item nav-max">
                            <span class="prd-max">Rate Type:</span> <span class=""><?= $prd_rate_type ?></span>
                        </li>
                        <li class="nav-item nav-max">
                            <span class="prd-max">Production Type:</span> <span class=""><?= $prd_prod_type ?></span>
                        </li>
                        <li class="nav-item nav-max">
                            <span class="prd-max">Dietary Type:</span> <span class=""><?= $prd_dietary ?></span>
                        </li>
                        <li class="nav-item nav-max">
                            <span class="prd-max">Estore Commission %:</span> <span class=""><?= $prd_estr_cmsn_p ?></span>
                        </li>
                        <li class="nav-item nav-max">
                            <span class="prd-max">Expense %:</span> <span class=""><?= $prd_exp_p ?></span>
                        </li>
                    </ul>
                </div>
            </div>






            <?php
            if ($prd_disc) {
                echo '<h4 class="bg-info text-center">DESCRIPTION</h4>';
                echo '<div class="row"><div class="col-12">' . $prd_disc . '</div></div>';
            }
            ?>




        </div>
    </div>

<?php
}
?>