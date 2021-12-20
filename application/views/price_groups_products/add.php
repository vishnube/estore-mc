<div class="row justify-content-md-center mt-2">
    <div class="col col-12">
        <div class="card card-primary">
            <div class="card-header">

                <h3 class="sr-form-tag card-title">
                    <span class="sr-form-icon fa-stack">
                        <i class="fas fa-circle fa-stack-2x text-white"></i>
                        <?= get_add_icon($icon) ?>
                    </span>

                    <!-- Class 'has-default-value' indicates that the element is a non-input element like div, p and having a default html text for form initilize -->
                    <span class="sr-form-title has-default-value" data-default="ADD PRICE GROUPS TO PRODUCTS">ADD PRICE GROUP TO PRODUCTS</span>

                </h3>

            </div>
            <!-- /.card-header -->
            <!-- form start -->


            <div class="card-body">
                <div class="row">
                    <div class="col col-12 col-lg-5 prd-unselected">
                        <?php $this->load->view('price_groups_products/product_not_added') ?>
                    </div>
                    <div class="col col-12 col-lg-7 prd-selected">
                        <?php $this->load->view('price_groups_products/product_added') ?>
                    </div>
                </div>
            </div>
            <!-- /.card-body -->

            <!-- 
            <div class="card-footer text-center">
                <div class="sr-wraper-bold">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i>&nbsp;SAVE</button>
                </div>
                <div class="sr-wraper-bold">
                    <div class="sr-reset-btn btn btn-danger"><i class="fas fa-repeat-alt"></i> &nbsp;RESET</div>
                </div>
                <div class="o_errors"></div>
            </div> 
        -->
        </div>

    </div>
</div>