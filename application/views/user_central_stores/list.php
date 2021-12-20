<?php
if (!(isset($after_ajax) && $after_ajax)) {
?>
    <div class="row justify-content-md-center mt-2">
        <div class="col-12 col-lg-10">
            <form class="sr-form" role="form" id="ucs_search_form" data-afterFormInit="">
                <div class="card card-primary">
                    <div class="card-header">
                        <h4 class="card-title"> USER CENTRAL STORES</h4>

                    </div>

                    <div class="card-body">
                        <div class="row mt-4">
                            <div class="col-12 col-md-6">
                                <div class="sr-wraper">
                                    <div class="form-group">
                                        <label>State</label>
                                        <div class="select2-sm">
                                            <select name="stt_id" class="stt_option form-control form-control-sm select2 select2-danger" data-dropdown-css-class="select2-info" style="width: 100%;">
                                                <?= get_options($stt_option) ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="sr-wraper">
                                    <div class="form-group">
                                        <label>District</label>
                                        <div class="select2-sm">
                                            <select name="dst_id" class="dst_option form-control form-control-sm select2 select2-danger" data-dropdown-css-class="select2-info" style="width: 100%;">
                                                <?= get_options() ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="sr-wraper">
                                    <div class="form-group">
                                        <label class="sr-mdt-lbl">Taluk</label>
                                        <div class="select2-sm">
                                            <select name="cstr_fk_taluks" class="tlk_option form-control form-control-sm select2 select2-danger" data-dropdown-css-class="select2-info" style="width: 100%;">
                                                <?= get_options() ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="col-12 col-md-6">
                                <?php
                                // If more than one member types, Using dropdown 
                                if (count($mbrtp_option) > 1) {
                                ?>

                                    <div class="sr-wraper">
                                        <div class="form-group">
                                            <label class="control-label sr-mdt-lbl">Member Type </label>
                                            <select name="mbrtp_id" class="mbrtp_id form-control form-control-sm">
                                                <?= get_options($mbrtp_option) ?>
                                            </select>
                                        </div>
                                    </div>
                                <?php }
                                // If Only one member type
                                else {
                                    // Getting the mbrtp_id
                                    $mbrtp_id = isset($mbrtp_option[0]) ? array_keys($mbrtp_option)[0] : 0;
                                    echo '<input type="hidden" class="mbrtp_id" name="mbrtp_id" value="' . $mbrtp_id . '"  data-default="' . $mbrtp_id . '" >';
                                }
                                ?>


                                <div class="sr-wraper">
                                    <div class="form-group">
                                        <label>User</label>
                                        <div class="select2-sm">
                                            <select name="usr_id" id="usr_id" class="usr_option form-control form-control-sm select2 select2-danger" data-dropdown-css-class="select2-danger" style="width: 100%;">
                                                <?= get_options($usr_option) ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="card-footer text-center" style="border-bottom:2px solid #007bff">
                        <div class="sr-wraper-bold">
                            <button type="submit" class="btn btn-primary">PROCEED&nbsp;<i class="fas fa-play"></i></button>
                        </div>
                    </div>

                </div>

            </form>


            <div class="container-fluid mt-5">
                <div class="mt-5" id="ucs-result-container" style="min-height: 300px;"></div>
            </div>


        </div>
    </div>

<?php
} else {
?>
    <div class="text-center border-top  border-warning border-2 p-3" style="background-color: #fffbdc;">

        <div class="clearer">
            <h3 class="d-inline-block text-uppercase"><?= $usr_name['mbr_name'] ?> </h3>
            <?= $user_groups ? '<span class="btn btn-success btn-sm btn-flat ml-1 mt-n2">' . implode('</span> <span class="btn btn-success btn-sm btn-flat ml-1 mt-n2">', array_values($user_groups)) . '</span>' : '' ?>


            <div class="float-md-right" style="font-size: 23px;">
                <span class="text-danger"><span class="usr_ucs_count text-bold"><?= count($ucs_ids) ?></span> CENTRAL STORES</span>
            </div>
        </div>
    </div>
    <table class="table table-head-fixed text-nowrap  table-hover border-bottom mb-2 border-warning" id="tbl_ucs">

        <thead>
            <tr>
                <th style="width: 10px">#</th>
                <th class="p-3">CENTRAL STORE</th>
                <th>LOCATION</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (!$cstr_table) {
                echo '<tr><td colspan="3"><h3 class="text-danger text-center"><i class="fas fa-exclamation-triangle fa-lg"></i><span class="pl-3">NO CENTRAL STORES FOUND</span></h3></td></tr>';
            } else {
                foreach ($cstr_table as $row) {
                    echo '<tr>';
                    echo '<td>';
                    echo '<input type="hidden" class="usr_id" value="' . $usr_id . '">';
                    echo '<input type="hidden" class="cstr_id" value="' . $row['cstr_id'] . '">';
                    if (in_array($row['cstr_id'], $ucs_ids)) {
                        echo '<i class="' . $this->rem_btn . '" title="REMOVE" style="font-size: 30px;"></i>';
                    } else {
                        echo '<i class="' . $this->add_btn . '" title="ADD" style="font-size: 30px;"></i>';
                    }
                    //echo '<i class="fad fa-times text-danger" style="font-size: 30px;"></i>';
                    echo '</td>';
                    echo '<td>' . $row['cstr_name'] . '</td>';
                    echo '<td><span class="text-info" title="Taluk">' . $row['tlk_name'] . '</span> / <span class="text-success" title="District">' . $row['dst_name'] . '</span> / <span class="text-danger" title="State">' . $row['stt_name'] . '</span></td>';
                    echo '</tr>';
                }
            }
            ?>
        </tbody>
    </table>

<?php
}
?>