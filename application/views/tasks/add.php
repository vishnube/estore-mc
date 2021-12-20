<form class="sr-form" role="form" id="tsk_add_form" onsubmit="$(this).find(':submit').prop('disabled', true)">
    <div class="row justify-content-md-center mt-2">
        <div class="col col-lg-6">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="sr-form-tag card-title">
                        <span style="font-size: 1.5rem;" class="sr-form-icon fa-stack">
                            <i class="fas fa-circle fa-stack-2x text-white"></i>
                            <?= get_add_icon($icon) ?>
                        </span>

                        <!-- Class 'has-default-value' indicates that the element is a non-input element like <div>, <p> and having a default html text for form initilize -->
                        <span class="sr-form-title has-default-value" data-default="ADD TASK">ADD TASK</span>
                    </h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->


                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6">

                            <!-- .sr-wraper is to highlight element on focus -->
                            <div class="sr-wraper">
                                <div class="form-group">
                                    <!-- '.sr-mdt-lbl' describes the label of a mandatory field -->
                                    <label class="sr-mdt-lbl">Name</label>
                                    <input type="text" name="tsk_name" id="tsk_name" class="form-control form-control-sm" placeholder="">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="sr-wraper">
                                <div class="form-group">
                                    <label>URL</label>
                                    <input type="text" name="tsk_url" id="tsk_url" class="form-control form-control-sm" placeholder="">
                                </div>
                            </div>

                        </div>
                    </div>


                    <div class="row">
                        <div class="col-sm-6">
                            <div class="sr-wraper">
                                <div class="form-group">
                                    <label>Icon Class</label>
                                    <input type="text" name="tsk_icon" id="tsk_icon" class="form-control form-control-sm" placeholder="fas fa-home">
                                </div>
                            </div>

                        </div>
                        <div class="col-sm-6">

                            <div class="sr-wraper">
                                <div class="form-group">
                                    <label>Icon Color</label>
                                    <input type="text" name="tsk_color" id="tsk_color" class="form-control form-control-sm my-colorpicker1" placeholder="#d3ff0">
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-sm-6">
                            <div class="sr-wraper">
                                <div class="form-group">
                                    <label>Primary Color (Duotone)</label>
                                    <input type="text" name="tsk_primary" id="tsk_primary" class="form-control form-control-sm my-colorpicker1">
                                </div>
                            </div>

                        </div>
                        <div class="col-sm-6">

                            <div class="sr-wraper">
                                <div class="form-group">
                                    <label>Secondary Color (Duotone)</label>
                                    <input type="text" name="tsk_secondary" id="tsk_secondary" class="form-control form-control-sm my-colorpicker1">
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group clearfix">
                                <label class="sr-mdt-lbl">Task Type</label><br>
                                <!-- .sr-wraper sr-rad is to highlight element on focus -->
                                <div class="sr-wraper sr-rad icheck-danger d-inline">
                                    <!-- '.sr-mdt-lbl' describes the label of a mandatory field -->
                                    <!-- data-default="true" is used to initialize the form.-->
                                    <input type="radio" value="1" name="tsk_type" id="tsk_type1">
                                    <label for="tsk_type1">
                                        <label class="sr-mdt-lbl">Developer</label>
                                    </label>
                                </div>

                                <div class="sr-wraper sr-rad icheck-danger d-inline">
                                    <input data-default="true" type="radio" value="2" name="tsk_type" id="tsk_type2">
                                    <label for="tsk_type2">All</label>
                                </div>
                            </div>
                        </div>


                        <div class="col-sm-6">
                            <div class="form-group clearfix">
                                <label class="sr-mdt-lbl">Is Menu</label><br>
                                <!-- .sr-wraper sr-rad is to highlight element on focus -->
                                <div class="sr-wraper sr-rad icheck-danger d-inline">
                                    <!-- '.sr-mdt-lbl' describes the label of a mandatory field -->
                                    <!-- data-default="true" is used to initialize the form.-->
                                    <input type="radio" value="1" name="tsk_menu" id="tsk_menu1">
                                    <label for="tsk_menu1">
                                        <label class="sr-mdt-lbl">Yes</label>
                                    </label>
                                </div>

                                <div class="sr-wraper sr-rad icheck-danger d-inline">
                                    <input data-default="true" type="radio" value="2" name="tsk_menu" id="tsk_menu2">
                                    <label for="tsk_menu2">No</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="sr-wraper">
                                <div class="form-group">
                                    <!-- '.sr-mdt-lbl' describes the label of a mandatory field -->
                                    <label class="sr-mdt-lbl">Parent Id</label>
                                    <input type="text" name="tsk_parent" id="tsk_parent" class="form-control form-control-sm" placeholder="">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="sr-wraper">
                                <div class="form-group">
                                    <label>Sort Position</label>
                                    <input type="text" name="tsk_sort" id="tsk_sort" class="form-control form-control-sm" placeholder="">
                                </div>
                            </div>
                        </div>
                    </div>



                    <div class="row">
                        <div class="col-sm-6">
                            <div class="sr-wraper">
                                <div class="form-group">
                                    <label>Key</label>
                                    <input type="text" name="tsk_key" id="tsk_key" class="form-control form-control-sm" placeholder="tsk_emply_add">
                                </div>
                                <div class="sr-bknote" style="color:blue">(Note: Use prefix 'tsk_')</div>
                            </div>
                        </div>
                        <div class="col-sm-6">

                        </div>
                    </div>



                </div>
                <!-- /.card-body -->
                <div class="card-footer text-center">
                    <input name="tsk_id" type="hidden">
                    <!-- .sr-wraper-bold is to highlight element on focus -->
                    <div class="sr-wraper-bold">
                        <button type="submit" class="btn btn-primary">SAVE</button>
                    </div>
                    <div class="sr-wraper-bold">
                        <div class="sr-reset-btn btn btn-danger">RESET</div>
                    </div>
                    <div class="o_errors"></div>

                    <!-- The query will be shown here -->
                    <textarea class="mt-2 query"></textarea>
                </div>

            </div>

        </div>


    </div>
</form>