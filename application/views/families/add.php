<form class="sr-form" role="form" id="fmly_add_form" onsubmit="$(this).find(':submit').prop('disabled', true)" data-afterFormInit="afterfmlyAddFormReset">
    <div class="row justify-content-md-center mt-2">
        <div class="col-12 col-lg-10">
            <div class="card card-primary">
                <div class="card-header">

                    <h3 class="sr-form-tag card-title">
                        <span class="sr-form-icon fa-stack">
                            <i class="fas fa-circle fa-stack-2x text-white"></i>
                            <?= get_add_icon($icon) ?>
                        </span>

                        <!-- Class 'has-default-value' indicates that the element is a non-input element like div, p and having a default html text for form initilize -->
                        <span class="sr-form-title has-default-value" data-default="ADD FAMILY">ADD FAMILY</span>
                    </h3>



                </div>
                <!-- /.card-header -->
                <!-- form start -->


                <div class="card-body">
                    <div class="row">



                        <div class="col-12 col-sm-6 col-md-4">
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
                        </div>

                        <div class="col-12 col-sm-6 col-md-4">
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
                        </div>

                        <div class="col-12 col-sm-6 col-md-4">
                            <div class="sr-wraper">
                                <div class="form-group">
                                    <label>Taluk</label>
                                    <div class="select2-sm">
                                        <select name="tlk_id" class="tlk_option form-control form-control-sm select2 select2-danger" data-dropdown-css-class="select2-info" style="width: 100%;">
                                            <?= get_options() ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="col-12 col-sm-6 col-md-4">
                            <div class="sr-wraper">
                                <div class="form-group">
                                    <label>Area</label>
                                    <div class="select2-sm">
                                        <select name="ars_id" class="ars_option form-control form-control-sm select2 select2-danger" data-dropdown-css-class="select2-info" style="width: 100%;">
                                            <?= get_options() ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>



                        <div class="col-12 col-sm-6 col-md-4">
                            <div class="sr-wraper">
                                <div class="form-group">
                                    <label class="sr-mdt-lbl">Ward</label>
                                    <div class="select2-sm">
                                        <select name="fmly_fk_wards" class="wrd_option form-control form-control-sm select2 select2-danger" data-dropdown-css-class="select2-info" style="width: 100%;">
                                            <?= get_options() ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>




                        <div class="col-12 col-sm-6 col-md-4">
                            <div class="sr-wraper">
                                <div class="form-group">
                                    <label class="sr-mdt-lbl">Family Name</label>
                                    <input type="text" name="mbr_name" class="form-control form-control-sm">
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-sm-6 col-md-4">
                            <div class="sr-wraper">
                                <div class="form-group">
                                    <label>Date:</label>

                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                        </div>
                                        <!-- '.sr-date-field-1' is used in initFormInputs() to repopup with format() its value after before_edit -->
                                        <!-- data-default is used to initialize the form.-->
                                        <input type="text" data-default="<?= today() ?>" name="mbr_date" id="mbr_date" class="sr-date-field-1 form-control form-control-sm" data-inputmask-alias="datetime" data-inputmask-inputformat="dd-mm-yyyy" data-mask>
                                    </div>
                                    <!-- /.input group -->
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-sm-6 col-md-4">
                            <div class="sr-wraper">
                                <div class="form-group">

                                    <!-- '.sr-mdt-lbl' describes the label of a mandatory field -->
                                    <label class="sr-mdt-lbl">Category</label>
                                    <!-- data-default is used to initialize the form.-->
                                    <!-- .sr-no-empty-vals means that this select box don't have <option value=""> -->
                                    <div class="select2-pink select2-sm">
                                        <select data-default="" name="cat_id[]" class="select2 fmly_cat_option sr-no-empty-vals form-control form-control-sm" multiple="multiple" data-placeholder="Select a Category" data-dropdown-css-class="select2-pink" style="width: 100%">
                                            <?= get_options($cat_option, '', '', false) ?>
                                        </select>
                                    </div>


                                </div>
                            </div>
                        </div>


                        <div class="col-12 col-sm-6 col-md-4">
                            <div class="sr-wraper">
                                <div class="form-group">
                                    <label class="sr-mdt-lbl">House No:</label>
                                    <input type="text" name="fmly_no" class="form-control form-control-sm">
                                </div>
                            </div>
                        </div>


                        <div class="col-12 col-sm-6 col-md-4">
                            <div class="sr-wraper">
                                <div class="form-group">
                                    <label class="">Landmark</label>
                                    <input type="text" name="fmly_landmark" class="form-control form-control-sm">
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-sm-6 col-md-4">
                            <div class="sr-wraper">
                                <div class="form-group">
                                    <label class="">Latitude</label>
                                    <input type="text" name="fmly_lat" class="form-control form-control-sm">
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-sm-6 col-md-4">
                            <div class="sr-wraper">
                                <div class="form-group">
                                    <label class="">Longitude</label>
                                    <input type="text" name="fmly_log" class="form-control form-control-sm">
                                </div>
                            </div>
                        </div>


                        <div class="col-12 col-sm-6 col-md-4">
                            <div class="sr-wraper">
                                <div class="form-group">
                                    <label class="sr-mdt-lbl">Address</label>
                                    <textarea name="mbr_address" class="form-control form-control-sm" rows="4" placeholder="Enter ..."></textarea>
                                </div>
                            </div>
                        </div>

                    </div>


                    <div class="row dv-add-fmlm">
                        <div class="col-12">
                            <h2 class="bg-danger text-center">ADD MEMBERS</h2>
                            <table class="table sr-input-movement tbl-add-fmlm" id="" data-afrNew="on_new_fmlm_add_row_created">
                                <thead>
                                    <tr>
                                        <th style="width:10px;">#</th>
                                        <th style="width:150px;">NAME</th>
                                        <th>CONTACT</th>
                                        <th>is PRIMARY</th>
                                        <th>BIRTH DATE</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <tr class="sr-movement-row">
                                        <td>
                                            <i class="fal fa-times-circle rem cursor-pointer" title="Delete"></i>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <input type="text" name="fmlm_name[]" class="fmlm_name next-input enter-lock form-control form-control-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <input type="text" name="fmlm_mob[]" class="fmlm_mob next-input enter-lock form-control form-control-sm">
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group">
                                                <select data-default="2" name="fmlm_is_prime[]" class="fmlm_is_prime next-input enter-lock form-control form-control-sm">
                                                    <option value="1">Yes</option>
                                                    <option value="2">No</option>
                                                </select>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                                    </div>
                                                    <input type="text" name="fmlm_dob[]" class="fmlm_dob sr-date-field-1 next-input last-input enter-lock form-control form-control-sm" data-inputmask-alias="datetime" data-inputmask-inputformat="dd-mm-yyyy" data-mask>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>


                </div>
                <!-- /.card-body -->

                <div class="card-footer text-center">
                    <input name="mbr_id" id="mbr_id" type="hidden">
                    <!-- data-default is used to initialize the form.-->
                    <input name="mbr_fk_member_types" type="hidden" data-default="<?= $mbrtp_id ?>">
                    <!-- .sr-wraper-bold is to highlight element on focus -->
                    <div class="sr-wraper-bold">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i>&nbsp;SAVE</button>
                    </div>
                    <div class="sr-wraper-bold">
                        <div class="sr-reset-btn btn btn-danger"><i class="fas fa-repeat-alt"></i> &nbsp;RESET</div>
                    </div>
                    <div class="o_errors"></div>
                </div>
            </div>

        </div>
    </div>

</form>