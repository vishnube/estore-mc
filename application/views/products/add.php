<form class="sr-form" role="form" id="prd_add_form" onsubmit="$(this).find(':submit').prop('disabled', true)" data-afterFormInit="afterProductAddFormReset">
    <div class="row justify-content-md-center mt-2">
        <div class="col-12">
            <div class="card card-primary">
                <div class="card-header">

                    <h3 class="sr-form-tag card-title">
                        <span class="sr-form-icon fa-stack">
                            <i class="fas fa-circle fa-stack-2x text-white"></i>
                            <?= get_add_icon($icon) ?>
                        </span>

                        <!-- Class 'has-default-value' indicates that the element is a non-input element like div, p and having a default html text for form initilize -->
                        <span class="sr-form-title has-default-value" data-default="ADD PRODUCT">ADD PRODUCT</span>
                    </h3>



                </div>
                <!-- /.card-header -->
                <!-- form start -->


                <div class="card-body">
                    <div class="row">
                        <div class="col-12 col-sm-6 col-lg-4">
                            <!-- .sr-wraper is to highlight element on focus -->
                            <div class="sr-wraper">
                                <div class="form-group">
                                    <!-- '.sr-mdt-lbl' describes the label of a mandatory field -->
                                    <label class="sr-mdt-lbl">Name</label>
                                    <input type="text" name="prd_name" class="form-control form-control-sm">
                                </div>
                            </div>



                            <div>
                                <div style="display:flex;">
                                    <div class="sr-wraper">
                                        <div class="form-group">
                                            <label class="">Product Code</label>
                                            <input type="text" name="prd_code" class="form-control form-control-sm">
                                        </div>
                                    </div>
                                    <div class="sr-wraper">
                                        <div class="form-group">
                                            <label class="">Barcode</label>
                                            <input type="text" name="prd_barcode" class="form-control form-control-sm">
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="sr-wraper">
                                <div class="form-group">
                                    <label>Tags</label>
                                    <!-- .sr-no-empty-vals means that this select box don't have <option value=""> -->
                                    <div class="select2-info select2-sm">
                                        <select name="tg_id[]" class="tg_option select2 sr-no-empty-vals form-control form-control-sm" multiple="multiple" data-placeholder="Select a Tag" data-dropdown-css-class="select2-info" style="width: 100%">
                                            <?= get_options($tg_option, '', '', false) ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="sr-wraper">
                                <div class="form-group">
                                    <!-- '.sr-mdt-lbl' describes the label of a mandatory field -->
                                    <label class="sr-mdt-lbl">Made in</label>
                                    <select name="prd_madein" data-default="1" class="form-control form-control-sm">
                                        <?= get_options($made_in_option, '', '', false) ?>
                                    </select>
                                </div>
                            </div>




                            <div class="sr-wraper">
                                <div class="form-group">
                                    <!-- '.sr-mdt-lbl' describes the label of a mandatory field -->
                                    <label class="sr-mdt-lbl">Production Type</label>
                                    <select name="prd_prod_type" data-default="1" class="form-control form-control-sm">
                                        <?= get_options($prodction_option, '', '', false) ?>
                                    </select>
                                </div>
                            </div>
                            <div class="sr-wraper">
                                <div class="form-group">
                                    <!-- '.sr-mdt-lbl' describes the label of a mandatory field -->
                                    <label class="sr-mdt-lbl">Dietary Type</label>
                                    <select name="prd_dietary" data-default="1" class="form-control form-control-sm">
                                        <?= get_options($dietary_option, '', '', false) ?>
                                    </select>
                                </div>
                            </div>

                        </div>


                        <div class="col-12 col-sm-6 col-lg-4">

                            <div class="form-group clearfix">
                                <label class="sr-label sr-mdt-lbl">Rate Type</label><br>
                                <div class="sr-wraper sr-rad icheck-primary d-inline">
                                    <input data-default="true" type="radio" value="1" name="prd_rate_type" id="prd_rate_type1">
                                    <label for="prd_rate_type1">MRP</label>
                                </div>

                                <div class="sr-wraper sr-rad icheck-primary d-inline">
                                    <input type="radio" value="2" name="prd_rate_type" id="prd_rate_type2">
                                    <label for="prd_rate_type2">Daily Varient</label>
                                </div>

                                <div class="sr-wraper sr-rad icheck-primary d-inline">
                                    <input type="radio" value="3" name="prd_rate_type" id="prd_rate_type3">
                                    <label for="prd_rate_type3">Frequently Varient</label>
                                </div>
                            </div>

                            <div class="form-group clearfix">
                                <label class="sr-label sr-mdt-lbl">is Organic</label><br>
                                <div class="sr-wraper sr-rad icheck-danger d-inline">
                                    <input data-default="true" type="radio" value="1" name="prd_organic" id="prd_organic1">
                                    <label for="prd_organic1">Yes</label>
                                </div>

                                <div class="sr-wraper sr-rad icheck-danger d-inline">
                                    <input type="radio" value="2" name="prd_organic" id="prd_organic2">
                                    <label for="prd_organic2">No</label>
                                </div>
                            </div>

                            <div class="form-group clearfix">
                                <label class="sr-label sr-mdt-lbl">Batch Product</label><br>
                                <div class="sr-wraper sr-rad icheck-warning d-inline">
                                    <input data-default="true" type="radio" value="1" name="prd_batch" id="prd_batch1">
                                    <label for="prd_batch1">Yes</label>
                                </div>

                                <div class="sr-wraper sr-rad icheck-warning d-inline">
                                    <input type="radio" value="2" name="prd_batch" id="prd_batch2">
                                    <label for="prd_batch2">No</label>
                                </div>
                            </div>


                            <div class="form-group clearfix">
                                <label class="sr-label sr-mdt-lbl">has Varients</label><br>
                                <div class="sr-wraper sr-rad icheck-success d-inline">
                                    <input data-default="true" type="radio" value="1" name="prd_varient" id="prd_varient1">
                                    <label for="prd_varient1">Yes</label>
                                </div>

                                <div class="sr-wraper sr-rad icheck-success d-inline">
                                    <input type="radio" value="2" name="prd_varient" id="prd_varient2">
                                    <label for="prd_varient2">No</label>
                                </div>
                            </div>

                            <div class="form-group clearfix">
                                <label class="sr-label sr-mdt-lbl">Zeta/Sodexo</label><br>
                                <div class="sr-wraper sr-rad icheck-info d-inline">
                                    <input data-default="true" type="radio" value="1" name="prd_zeta" id="prd_zeta1">
                                    <label for="prd_zeta1">Acceptable</label>
                                </div>

                                <div class="sr-wraper sr-rad icheck-info d-inline">
                                    <input type="radio" value="2" name="prd_zeta" id="prd_zeta2">
                                    <label for="prd_zeta2">Not</label>
                                </div>
                            </div>




                            <div class="sr-wraper">
                                <div class="form-group">
                                    <label>HSN CODE</label>
                                    <input type="text" name="prd_hsn_code" class="form-control form-control-sm">
                                </div>
                            </div>


                            <div class="sr-wraper">
                                <div class="form-group">
                                    <label>Estore Commission %</label>
                                    <input type="text" name="prd_estr_cmsn_p" class="form-control form-control-sm">
                                </div>
                            </div>


                        </div>


                        <div class="col-12 col-sm-6 col-lg-4">

                            <div class="parent-dv">
                                <div class="sr-wraper">
                                    <div class="form-group">
                                        <label>Category</label>
                                        <div class="input-group mb-3 input-group-sm form-group">
                                            <div class="input-group-prepend">
                                                <button type="button" class="btn btn-danger reset-pctopt">Reset</button>
                                            </div>
                                            <select name="pct_parent_selector" class="pct_parent_selector pct_option form-control form-control-sm">
                                                <?= get_options($pct_option, '', 'Select Category', true, false, 'No Categories') ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="parents m-1 p-1"></div>
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
                            <div class="form-group mt-5" style="position: relative;">
                                <label for="photo1" class="sr-file-label">Photo 1</label>
                                <!-- .sr-input-group is used for jquery -->
                                <div class="input-group sr-input-group">
                                    <div class="custom-file">
                                        <label class="custom-file-label" for="photo1"></label>
                                        <input type="file" name="photo1" class="custom-file-input" id="photo1">
                                    </div>
                                    <div class="input-group-append">
                                        <span class="input-group-text bg-danger cursor-pointer" onclick="resetFile(this)">RESET</span>
                                    </div>
                                </div>
                                <!-- <div class="form-validation error">Photo 1 Is Required</div> -->
                            </div>

                            <div class="form-group mt-5" style="position: relative;">
                                <label for="photo2" class="sr-file-label">Photo 2</label>
                                <div class="input-group sr-input-group">
                                    <div class="custom-file">
                                        <input type="file" name="photo2" class="custom-file-input" id="photo2">
                                        <label class="custom-file-label" for="photo2"></label>
                                    </div>
                                    <div class="input-group-append">
                                        <span class="input-group-text bg-danger cursor-pointer" onclick="resetFile(this)">RESET</span>
                                    </div>
                                </div>
                            </div>


                            <div class="sr-wraper">
                                <div class="form-group">
                                    <label>Company</label>
                                    <div class="select2-sm">
                                        <select name="prd_fk_companies" class="cmp_option form-control form-control-sm select2 select2-danger" data-dropdown-css-class="select2-info" style="width: 100%;">
                                            <?= get_options($cmp_option) ?>
                                        </select>
                                    </div>

                                </div>
                            </div>
                            <div class="sr-wraper">
                                <div class="form-group">
                                    <label>Brand</label>
                                    <select name="prd_fk_brands" class="brnd_option form-control form-control-sm">
                                        <?= get_options($brnd_option) ?>
                                    </select>
                                </div>
                            </div>


                            <div class="sr-wraper">
                                <div class="form-group">
                                    <label>Expense %</label>
                                    <input type="text" name="prd_exp_p" class="form-control form-control-sm">
                                </div>
                            </div>

                        </div>

                    </div>

                    <div class="row">
                        <div class="col-12 col-lg-4">
                            <div class="sr-wraper">
                                <div class="form-group">
                                    <!-- '.sr-mdt-lbl' describes the label of a mandatory field -->
                                    <label class="sr-mdt-lbl">Description</label>
                                    <textarea name="prd_disc" class="prd_disc" style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-8 dv-product-units">
                            <div class="bg-info row p-2">
                                <div class="col col-12 col-md-6 col-lg-4">
                                    <h3 class="text-center" style="font-size: 25px; text-transform: uppercase;">
                                        SELECT A UNIT</h3>
                                </div>
                                <div class="col col-12 col-md-6 col-lg-4">
                                    <div class="input-group">
                                        <input type="text" id="productUnitQuickSearch" data-callback="" data-target="#tbl_prd_add_ugp" class="quick-search form-control float-right" placeholder="Quick Search">
                                        <div class="input-group-append">
                                            <button class="btn btn-default"><i class="fas fa-search"></i></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col col-12 col-md-6 col-lg-4">
                                    <button type="button" title="Add Unit Group" id="add_ugp" class="btn btn-secondary">
                                        <i class="fas fa-plus"></i> &nbsp; ADD UNITS
                                    </button>
                                </div>
                            </div>



                            <div class="table-responsive" id="tbl_prd_add_ugp_container" style="height: 400px;">
                                <table class="table table-head-fixed  text-nowrap  table-hover" id="tbl_prd_add_ugp">
                                    <tbody></tbody>
                                </table>
                                <!-- Only for form validation purpose -->
                                <input type="hidden" name="product_units">
                            </div>
                        </div>
                    </div>


                </div>
                <!-- /.card-body -->

                <div class="card-footer text-center">
                    <input name="prd_id" type="hidden">
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