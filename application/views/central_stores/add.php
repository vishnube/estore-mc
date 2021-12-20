<form class="sr-form" role="form" id="cstr_add_form" onsubmit="$(this).find(':submit').prop('disabled', true)" data-afterFormInit="aftercstrAddFormReset">
    <div class="row justify-content-md-center mt-2">
        <div class="col-12 col-lg-11">
            <div class="card card-primary">
                <div class="card-header">

                    <h3 class="sr-form-tag card-title">
                        <span class="sr-form-icon fa-stack">
                            <i class="fas fa-circle fa-stack-2x text-white"></i>
                            <?= get_add_icon($icon) ?>
                        </span>

                        <!-- Class 'has-default-value' indicates that the element is a non-input element like div, p and having a default html text for form initilize -->
                        <span class="sr-form-title has-default-value" data-default="ADD CENTRAL STORE">ADD CENTRAL STORE</span>
                    </h3>

                </div>
                <!-- /.card-header -->
                <!-- form start -->


                <div class="card-body">
                    <div class="row">
                        <div class="col-12 col-sm-6 col-md-4">
                            <h3 class="bg-info p-2 text-center" style="font-size: 25px; text-transform: uppercase;">Basic Details</h3>
                            <div class="sr-wraper">
                                <div class="form-group">
                                    <label class="sr-mdt-lbl">Name</label>
                                    <input type="text" name="mbr_name" id="mbr_name" class="form-control form-control-sm">
                                </div>
                            </div>
                            <div class="sr-wraper">
                                <div class="form-group">
                                    <label class="sr-mdt-lbl">Code</label>
                                    <input type="text" name="cstr_code" id="cstr_code" class="form-control form-control-sm">
                                </div>
                            </div>
                            <div class="sr-wraper">
                                <div class="form-group">
                                    <label class="sr-mdt-lbl">Category</label>
                                    <select data-default="<?= get_first_option($cstr_cat_option) ?>" name="cat_id" class="cstr_cat_option sr-no-empty-vals form-control form-control-sm">
                                        <?= get_options($cstr_cat_option, '', '', false, true) ?>
                                    </select>
                                </div>
                            </div>

                            <div class="sr-wraper">
                                <div class="form-group">
                                    <label class="sr-mdt-lbl">Address 1</label>
                                    <textarea name="mbr_address" class="form-control form-control-sm" rows="2"></textarea>
                                </div>
                            </div>
                            <div class="sr-wraper">
                                <div class="form-group">
                                    <label>Address 2</label>
                                    <textarea name="cstr_address2" class="form-control form-control-sm" rows="2"></textarea>
                                </div>
                            </div>

                            <div class="sr-wraper">
                                <div class="form-group">
                                    <label class="">Police Contact</label>
                                    <input type="text" name="cstr_police" class="form-control form-control-sm">
                                </div>
                            </div>
                            <div class="sr-wraper">
                                <div class="form-group">
                                    <label class="">Fire Contact</label>
                                    <input type="text" name="cstr_fire" class="form-control form-control-sm">
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6 col-md-4">
                            <h3 class="bg-info p-2 text-center" style="font-size: 25px; text-transform: uppercase;">Contact Details</h3>
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
                            <div class="sr-wraper">
                                <div class="form-group">
                                    <label class="">Landmark</label>
                                    <input type="text" name="cstr_landmark" class="form-control form-control-sm">
                                </div>
                            </div>
                            <div class="sr-wraper">
                                <div class="form-group">
                                    <label class="">Pincode</label>
                                    <input type="text" name="cstr_pin" class="form-control form-control-sm">
                                </div>
                            </div>
                            <div class="sr-wraper">
                                <div class="form-group">
                                    <label class="">Contact 1</label>
                                    <input type="text" name="cstr_mob1" class="form-control form-control-sm">
                                </div>
                            </div>
                            <div class="sr-wraper">
                                <div class="form-group">
                                    <label class="">Contact 2</label>
                                    <input type="text" name="cstr_mob2" class="form-control form-control-sm">
                                </div>
                            </div>


                            <div style="clear:both;">
                                <div class="sr-wraper" style="width: 50%;float:left;">
                                    <div class="form-group">
                                        <label class="">Latitude</label>
                                        <input type="text" name="cstr_lat" class="form-control form-control-sm">
                                    </div>
                                </div>
                                <div class="sr-wraper" style="width: 50%;float:left;">
                                    <div class="form-group">
                                        <label class="">Longitude</label>
                                        <input type="text" name="cstr_log" class="form-control form-control-sm">
                                    </div>
                                </div>
                            </div>






                        </div>




                        <div class="col-12 col-sm-6 col-md-4">
                            <h3 class="bg-info p-2 text-center" style="font-size: 25px; text-transform: uppercase;">Building Details</h3>

                            <div class="sr-wraper">
                                <div class="form-group">
                                    <label class="">Building Owner</label>
                                    <input type="text" name="cstr_bownr" class="form-control form-control-sm">
                                </div>
                            </div>
                            <div class="sr-wraper">
                                <div class="form-group">
                                    <label class="">Owner Contact 1</label>
                                    <input type="text" name="cstr_bownr_mob1" class="form-control form-control-sm">
                                </div>
                            </div>
                            <div class="sr-wraper">
                                <div class="form-group">
                                    <label class="">Owner Contact 2</label>
                                    <input type="text" name="cstr_bownr_mob2" class="form-control form-control-sm">
                                </div>
                            </div>
                            <div class="sr-wraper">
                                <div class="form-group">
                                    <label class="">Rent</label>
                                    <input type="text" name="cstr_rent" class="form-control form-control-sm">
                                </div>
                            </div>
                            <div class="sr-wraper">
                                <div class="form-group">
                                    <label class="">Advance</label>
                                    <input type="text" name="cstr_adv" class="form-control form-control-sm">
                                </div>
                            </div>
                            <div class="sr-wraper">
                                <div class="form-group">
                                    <label class="">Square Ft</label>
                                    <input type="text" name="cstr_sqft" class="form-control form-control-sm">
                                </div>
                            </div>


                            <!-- 
                                To work file upload you should 
                                1. Include AdminLTE-3.0.2/plugins/bs-custom-file-input/bs-custom-file-input.min.js @ index.php    
                                2. Call bsCustomFileInput.init(); // after page load (In Form initialization callback)
                                3. Use javascript FormData() object to post input data.
                                4. When calling postForm() function, give the value for 'file' parameter TRUE.
                                5. After EDIT action, To get primary key value in callback function which will be called after form save, 
                                    use FormData.get(p_key) method
                                    Eg: var mbr_id = input.get('mbr_id');
                                6. Define resetFile() function as  
                                    function resetFile(obj) {
                                        $(obj).closest('.input-group').find('.custom-file-input').val('');
                                        $(obj).closest('.input-group').find('.custom-file-label').html('')
                                    }
                            -->
                            <div class="form-group mt-4" style="position: relative;">
                                <label for="cstr_lic" class="sr-file-label">Panchayath Licence</label>
                                <!-- .sr-input-group is used for jquery -->
                                <div class="input-group sr-input-group">
                                    <div class="custom-file">
                                        <label class="custom-file-label" for="cstr_lic"></label>
                                        <input type="file" name="cstr_lic" class="custom-file-input" id="cstr_lic">
                                    </div>
                                    <div class="input-group-append">
                                        <span class="input-group-text bg-danger cursor-pointer" onclick="resetFile(this)">RESET</span>
                                    </div>
                                </div>
                                <!-- <div class="form-validation error">Photo 1 Is Required</div> -->
                            </div>





                        </div>


                    </div>






















                </div>
                <!-- /.card-body -->

                <div class="card-footer text-center">
                    <input name="mbr_id" type="hidden">
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